<?php
/**
 * Service Publications
 * - CRUD + statuts + partage d'articles
 * - Co-validation multi-labos : chaque directeur pose une décision locale;
 *   le statut global n'évolue que si toutes les décisions concordent.
 */
if (!defined('ABSPATH')) exit;

class PubService {

  /* ======================== Tables ======================== */
  public static function t_publications()   { global $wpdb; return $wpdb->prefix . 'recherche_publication'; }
  public static function t_membres()        { global $wpdb; return $wpdb->prefix . 'recherche_membre'; }
  public static function t_labs()           { global $wpdb; return $wpdb->prefix . 'recherche_laboratoire'; }
  public static function t_pub_shares()     { global $wpdb; return $wpdb->prefix . 'recherche_publication_share'; }
  public static function t_pub_files()      { global $wpdb; return $wpdb->prefix . 'recherche_fichier_publication'; }
  public static function t_pub_keywords()   { global $wpdb; return $wpdb->prefix . 'recherche_publication_keyword'; }
  public static function t_pub_approvals()  { global $wpdb; return $wpdb->prefix . 'recherche_publication_approval'; } // NEW

  /* ======================== Rôles ======================== */
  public static $ROLE_DIR        = ['um_directeur_laboratoire','directeur_laboratoire','directeur-laboratoire'];
  public static $ROLE_UTM        = ['um_service-utm','service_utm','service-utm'];
  public static $ROLE_ETAB       = ['um_service-etablissement','service_etablissement','service-etablissement'];
  public static $ROLE_CHERCHEUR  = ['um_chercheur','chercheur'];

  public static function user_has_any_role(array $choices): bool {
    $u = wp_get_current_user(); if (!$u || empty($u->roles)) return false;
    $roles = array_map('strtolower', (array)$u->roles);
    foreach ($choices as $r) if (in_array(strtolower($r), $roles, true)) return true;
    return false;
  }
  public static function is_directeur()    { return self::user_has_any_role(self::$ROLE_DIR); }
  public static function is_service_utm()  { return self::user_has_any_role(self::$ROLE_UTM); }
  public static function is_service_etab() { return self::user_has_any_role(self::$ROLE_ETAB); }

  /* ======================== Bootstrap table approbations ======================== */
private static function ensure_approval_table(): void {
  global $wpdb;
  $t = self::t_pub_approvals();

  // Test cross-plateforme, évite les permissions sur information_schema
  $exists = $wpdb->get_var( $wpdb->prepare("SHOW TABLES LIKE %s", $t) );
  if ($exists === $t) return;

  require_once ABSPATH . 'wp-admin/includes/upgrade.php';
  $charset = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE {$t} (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    publication_id   BIGINT UNSIGNED NOT NULL,
    director_user_id BIGINT UNSIGNED NOT NULL,
    decision         VARCHAR(20) NOT NULL DEFAULT 'En attente',
    updated_at       DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_pub_dir (publication_id, director_user_id),
    KEY idx_pub (publication_id),
    KEY idx_dir (director_user_id)
  ) {$charset};";
  dbDelta($sql);
}


  /* ======================== Contexte utilisateur ======================== */
  /** Labos dont je suis membre OU directeur (mix) */
  public static function my_lab_ids(): array {
    global $wpdb;
    $uid = get_current_user_id(); if (!$uid) return [];
    $mT = self::t_membres(); $lT = self::t_labs();
    $ids = [];
    $rows = $wpdb->get_col($wpdb->prepare("SELECT laboratoire_id FROM {$mT} WHERE user_id=%d", $uid)) ?: [];
    $ids = array_merge($ids, array_map('intval',$rows));
    $rows = $wpdb->get_col($wpdb->prepare("SELECT id FROM {$lT} WHERE directeur_user_id=%d", $uid)) ?: [];
    $ids = array_merge($ids, array_map('intval',$rows));
    return array_values(array_unique(array_filter($ids)));
  }

  /** Labos que JE dirige (uniquement) */
  public static function labs_directed_by(int $user_id = 0): array {
    global $wpdb;
    $uid = $user_id ?: get_current_user_id(); if (!$uid) return [];
    $lT = self::t_labs();
    $rows = $wpdb->get_col($wpdb->prepare("SELECT id FROM {$lT} WHERE directeur_user_id=%d", $uid)) ?: [];
    return array_values(array_unique(array_map('intval', $rows)));
  }

  public static function user_etab_id(int $user_id): int {
    return (int) get_user_meta($user_id, 'etablissement_id', true) ?: 0;
  }

  /* ======================== Partage (helpers) ======================== */
  public static function eligible_share_users(string $search = ''): array {
    $uid = get_current_user_id();
    if (!$uid) return [];

    $args = [
      'role__in'        => array_merge(self::$ROLE_CHERCHEUR, self::$ROLE_DIR),
      'orderby'         => 'display_name',
      'order'           => 'ASC',
      'fields'          => ['ID','display_name','user_email'],
      'number'          => 100,
      'exclude'         => [$uid],
    ];
    if ($search !== '') {
      $args['search'] = '*' . $search . '*';
      $args['search_columns'] = ['display_name','user_email'];
    }

    $users = get_users($args);
    return array_map(function($u){
      return [
        'id'    => (int) $u->ID,
        'label' => sprintf('%s <%s>', $u->display_name, $u->user_email),
      ];
    }, $users);
  }

  public static function lab_user_ids(array $labIds): array {
    global $wpdb;
    $labIds = array_values(array_unique(array_map('intval', $labIds)));
    if (empty($labIds)) return [];
    $mT = self::t_membres();
    $lT = self::t_labs();

    $u1 = $wpdb->get_col("SELECT DISTINCT user_id FROM {$mT} WHERE laboratoire_id IN (".implode(',', $labIds).")") ?: [];
    $u2 = $wpdb->get_col("SELECT DISTINCT directeur_user_id FROM {$lT} WHERE id IN (".implode(',', $labIds).") AND directeur_user_id IS NOT NULL") ?: [];

    $ids = array_values(array_unique(array_map('intval', array_merge($u1, $u2))));
    return array_filter($ids, fn($id)=> $id > 0);
  }

  public static function my_lab_user_ids(): array {
    global $wpdb;
    $uid  = get_current_user_id();
    if (!$uid) return [];

    $labs = self::my_lab_ids();
    if (empty($labs)) return [];

    $mT = self::t_membres();
    $lT = self::t_labs();

    $members = $wpdb->get_col("SELECT DISTINCT user_id FROM {$mT} WHERE laboratoire_id IN (".implode(',', array_map('intval',$labs)).")") ?: [];
    $dirs    = $wpdb->get_col("SELECT DISTINCT directeur_user_id FROM {$lT} WHERE id IN (".implode(',', array_map('intval',$labs)).") AND directeur_user_id IS NOT NULL") ?: [];

    $ids = array_map('intval', array_unique(array_merge($members, $dirs)));
    return array_values(array_filter($ids));
  }

  /** Remplace toutes les lignes de partage d'une publication par une nouvelle liste d'IDs (utilisé en création) */
  public static function replace_shares(int $pub_id, array $user_ids, int $added_by): void {
    global $wpdb; $sT = self::t_pub_shares();
    $user_ids = array_values(array_unique(array_map('intval', array_filter($user_ids))));
    $wpdb->query('START TRANSACTION');
    try{
      $wpdb->delete($sT, ['publication_id'=>$pub_id], ['%d']);
      foreach ($user_ids as $uid) {
        if ($uid <= 0) continue;
        $wpdb->insert($sT, [
          'publication_id' => $pub_id,
          'user_id'        => $uid,
          'added_by'       => $added_by,
          'created_at'     => current_time('mysql'),
        ], ['%d','%d','%d','%s']);
      }
      $wpdb->query('COMMIT');
    } catch (\Throwable $e) {
      $wpdb->query('ROLLBACK');
      throw $e;
    }
  }

  /** Synchronise les partages (update fin) : ajoute les nouveaux, supprime seulement ceux retirés. */
  public static function sync_shares(int $pub_id, array $user_ids, int $actor_id): array {
    global $wpdb;
    $sT = self::t_pub_shares();

    $desired = array_values(array_unique(array_map('intval', array_filter($user_ids))));
    $current = self::list_share_user_ids($pub_id);

    $to_add    = array_values(array_diff($desired, $current));
    $to_remove = array_values(array_diff($current, $desired));
    $kept      = array_values(array_intersect($current, $desired));

    if (empty($to_add) && empty($to_remove)) return ['added'=>[], 'removed'=>[], 'kept'=>$kept];

    $wpdb->query('START TRANSACTION');
    try {
      if (!empty($to_remove)) {
        $in = implode(',', array_fill(0, count($to_remove), '%d'));
        $rows = $wpdb->get_col($wpdb->prepare(
          "SELECT id FROM {$sT} WHERE publication_id=%d AND user_id IN ($in)", $pub_id, ...$to_remove
        )) ?: [];
        if ($rows) {
          $kT = self::t_pub_keywords();
          $fT = self::t_pub_files();
          $idIn = implode(',', array_fill(0, count($rows), '%d'));
          $wpdb->query($wpdb->prepare("DELETE FROM {$kT} WHERE publication_share_id IN ($idIn)", ...$rows));
          $wpdb->query($wpdb->prepare("DELETE FROM {$fT} WHERE publication_share_id IN ($idIn)", ...$rows));
          $wpdb->query($wpdb->prepare("DELETE FROM {$sT} WHERE id IN ($idIn)", ...$rows));
        }
      }
      foreach ($to_add as $uid) {
        $wpdb->insert($sT, [
          'publication_id' => $pub_id,
          'user_id'        => $uid,
          'added_by'       => $actor_id,
          'created_at'     => current_time('mysql'),
        ], ['%d','%d','%d','%s']);
      }
      $wpdb->query('COMMIT');
    } catch (\Throwable $e) {
      $wpdb->query('ROLLBACK');
      throw $e;
    }

    return ['added'=>$to_add, 'removed'=>$to_remove, 'kept'=>$kept];
  }

  /** Supprime précisément des fichiers (par IDs) rattachés à une publication */
  private static function delete_share_files_by_ids(int $pub_id, array $file_ids): void {
    global $wpdb;
    $fT = self::t_pub_files();
    $ids = array_values(array_unique(array_map('intval', array_filter($file_ids))));
    if (!$ids) return;
    $in = implode(',', array_fill(0, count($ids), '%d'));
    $wpdb->query($wpdb->prepare("DELETE FROM {$fT} WHERE publication_id=%d AND id IN ($in)", $pub_id, ...$ids));
  }

  /** Seed des mots-clés & fichiers pour chaque part fournie (remplace keywords si fournis, ajoute fichiers) */
  private static function seed_share_extras(int $pub_id, array $user_ids, array $keywords = [], array $files = [], int $added_by = 0): void {
    global $wpdb;
    $user_ids = array_values(array_unique(array_map('intval', array_filter($user_ids))));
    if (empty($user_ids)) return;

    $sT = self::t_pub_shares();
    $kT = self::t_pub_keywords();
    $fT = self::t_pub_files();

    $idsList = implode(',', $user_ids);
    $rows = $wpdb->get_results($wpdb->prepare(
      "SELECT id, user_id FROM {$sT} WHERE publication_id=%d AND user_id IN ({$idsList})", $pub_id
    ), ARRAY_A) ?: [];
    if (empty($rows)) return;

    $now = current_time('mysql');
    $creator = $added_by ?: get_current_user_id();

    $wpdb->query('START TRANSACTION');
    try {
      foreach ($rows as $r) {
        $share_id = (int)$r['id'];

        if (!empty($keywords)) {
          $wpdb->delete($kT, ['publication_share_id'=>$share_id], ['%d']);
          foreach ($keywords as $kw) {
            $kw = trim((string)$kw); if ($kw==='') continue;
            $wpdb->insert($kT, [
              'contenu'              => mb_substr($kw,0,255),
              'publication_id'       => $pub_id,
              'publication_share_id' => $share_id,
              'created_by'           => $creator,
              'created_at'           => $now,
            ], ['%s','%d','%d','%d','%s']);
          }
        }

        if (!empty($files) && is_array($files)) {
          foreach ($files as $f) {
            $name = trim((string)($f['original_name'] ?? ''));
            $path = trim((string)($f['storage_path'] ?? ''));
            if ($name==='' || $path==='') continue;
            $wpdb->insert($fT, [
              'publication_id'       => $pub_id,
              'publication_share_id' => $share_id,
              'original_name'        => mb_substr($name,0,255),
              'storage_path'         => mb_substr($path,0,255),
              'created_by'           => $creator,
              'created_at'           => $now,
              'updated_at'           => $now,
            ], ['%d','%d','%s','%s','%d','%s','%s']);
          }
        }
      }
      $wpdb->query('COMMIT');
    } catch (\Throwable $e) {
      $wpdb->query('ROLLBACK');
      error_log('[PubService] seed_share_extras failed: '.$e->getMessage());
    }
  }

  /* ======================== Droits édition ======================== */
  public static function can_edit_row(array $row, int $uid): bool {
    if (self::is_service_utm()) return true;

    // Directeur du labo de cette publication
    global $wpdb;
    $is_dir_for_lab = (bool)$wpdb->get_var(
      $wpdb->prepare("SELECT 1 FROM ".self::t_labs()." WHERE id=%d AND directeur_user_id=%d", (int)$row['laboratoire_id'], $uid)
    );
    if ($is_dir_for_lab) return true;

    // Auteur : peut modifier tant que non "Validée"
    if ((int)$row['created_by'] === $uid && strtolower(trim((string)$row['statut'])) !== 'validée') {
      return true;
    }
    return false;
  }

  /* ======================== Helpers CO-VALIDATION ======================== */

  /** Labos impliqués dans une publication : labo de l’auteur + labos des utilisateurs partagés */
  private static function involved_lab_ids(int $pub_id): array {
    global $wpdb;
    $t = self::t_publications();
    $s = self::t_pub_shares();
    $m = self::t_membres();

    $lab0 = (int)$wpdb->get_var($wpdb->prepare("SELECT laboratoire_id FROM {$t} WHERE id=%d", $pub_id));
    $labs = $lab0 ? [$lab0] : [];

    // labos des users avec qui c'est partagé
    $userIds = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT user_id FROM {$s} WHERE publication_id=%d", $pub_id)) ?: [];
    if ($userIds) {
      $in = implode(',', array_fill(0, count($userIds), '%d'));
      $rows = $wpdb->get_col($wpdb->prepare(
        "SELECT DISTINCT laboratoire_id FROM {$m} WHERE user_id IN ($in)", ...array_map('intval',$userIds)
      )) ?: [];
      $labs = array_merge($labs, array_map('intval',$rows));
    }

    $labs = array_values(array_unique(array_filter($labs)));
    return $labs;
  }

  /** Liste des directeurs requis (user_id) pour un pub (un par labo impliqué) */
  private static function required_directors_for_pub(int $pub_id): array {
    global $wpdb;
    $lT = self::t_labs();
    $labs = self::involved_lab_ids($pub_id);
    if (empty($labs)) return [];
    $in = implode(',', array_map('intval',$labs));
    $rows = $wpdb->get_col("SELECT DISTINCT directeur_user_id FROM {$lT} WHERE id IN ($in) AND directeur_user_id IS NOT NULL") ?: [];
    $ids = array_values(array_unique(array_map('intval',$rows)));
    return array_filter($ids, fn($x)=>$x>0);
  }

  /** Enregistre/écrase la décision locale du directeur courant pour ce pub */
  private static function record_director_decision(int $pub_id, int $director_uid, string $decision): void {
    global $wpdb;
    self::ensure_approval_table();
    $t = self::t_pub_approvals();
    $decision = in_array($decision, ['En attente','Validée','Rejetée','Publiée'], true) ? $decision : 'En attente';

    // upsert
    $row_id = (int)$wpdb->get_var($wpdb->prepare(
      "SELECT id FROM {$t} WHERE publication_id=%d AND director_user_id=%d LIMIT 1",
      $pub_id, $director_uid
    ));
    if ($row_id) {
      $wpdb->update($t, [
        'decision'  => $decision,
        'updated_at'=> current_time('mysql'),
      ], ['id'=>$row_id], ['%s','%s'], ['%d']);
    } else {
      $wpdb->insert($t, [
        'publication_id'   => $pub_id,
        'director_user_id' => $director_uid,
        'decision'         => $decision,
        'updated_at'       => current_time('mysql'),
      ], ['%d','%d','%s','%s']);
    }
  }

  /** Retourne la décision locale d’un directeur (ou 'En attente') */
  private static function get_director_decision(int $pub_id, int $director_uid): string {
    global $wpdb;
    self::ensure_approval_table();
    $t = self::t_pub_approvals();
    $d = (string)$wpdb->get_var($wpdb->prepare(
      "SELECT decision FROM {$t} WHERE publication_id=%d AND director_user_id=%d",
      $pub_id, $director_uid
    ));
    return $d ?: 'En attente';
  }

  /** Si toutes les décisions concordent, retourne cette décision, sinon null */
  private static function aggregate_if_all_agree(int $pub_id): ?string {
    global $wpdb;
    self::ensure_approval_table();
    $t = self::t_pub_approvals();
    $req_dirs = self::required_directors_for_pub($pub_id);
    if (empty($req_dirs)) return null;

    // Récup décisions des directeurs requis
    $in = implode(',', array_fill(0, count($req_dirs), '%d'));
    $rows = $wpdb->get_col($wpdb->prepare(
      "SELECT decision FROM {$t} WHERE publication_id=%d AND director_user_id IN ($in)",
      $pub_id, ...$req_dirs
    )) ?: [];

    // Si tous n'ont pas encore statué => pas d'agrégation
    if (count($rows) < count($req_dirs)) return null;

    // Tous identiques ET dans l'ensemble terminal ?
    $uniq = array_values(array_unique(array_map('strval', $rows)));
    if (count($uniq) === 1 && in_array($uniq[0], ['Validée','Rejetée','Publiée'], true)) {
      return $uniq[0];
    }
    return null;
  }

  /** Statut vu par un utilisateur (membre d’un labo impliqué) */
 /** Statut vu par un utilisateur (membre d’un labo impliqué ou directeur d’un de ces labos) */
private static function viewer_local_statut(array $pub_row, int $viewer_uid): string {
  global $wpdb;
  $pub_id = (int)$pub_row['id'];
  $labs_involved = self::involved_lab_ids($pub_id);
  if (empty($labs_involved)) {
    return (string)($pub_row['statut'] ?? 'En attente');
  }

  // Labos du viewer = (labos où il est membre) U (labos qu'il dirige)
  $mT = self::t_membres();
  $lT = self::t_labs();

  $myMemberLabs = $wpdb->get_col($wpdb->prepare(
    "SELECT DISTINCT laboratoire_id FROM {$mT} WHERE user_id=%d", $viewer_uid
  )) ?: [];

  $myDirectorLabs = $wpdb->get_col($wpdb->prepare(
    "SELECT DISTINCT id FROM {$lT} WHERE directeur_user_id=%d", $viewer_uid
  )) ?: [];

  $myLabs = array_values(array_unique(array_map('intval', array_merge($myMemberLabs, $myDirectorLabs))));
  $inter  = array_values(array_intersect($labs_involved, $myLabs));
  if (empty($inter)) {
    // le viewer n'est ni membre ni directeur d'un labo impliqué -> statut global
    return (string)($pub_row['statut'] ?? 'En attente');
  }

  // Récupérer les directeurs des labos (intersections)
  $in = implode(',', array_map('intval',$inter));
  $dirIds = $wpdb->get_col("SELECT DISTINCT directeur_user_id FROM {$lT} WHERE id IN ($in) AND directeur_user_id IS NOT NULL") ?: [];
  $dirIds = array_values(array_unique(array_map('intval',$dirIds)));
  if (empty($dirIds)) return (string)($pub_row['statut'] ?? 'En attente');

  // Décisions locales des directeurs pertinents
  $decisions = [];
  foreach ($dirIds as $d) { $decisions[] = self::get_director_decision($pub_id, $d); }

  // Règle d'affichage locale (priorité Rejetée > En attente > Publiée > Validée)
  if (in_array('Rejetée', $decisions, true)) return 'Rejetée';
  if (in_array('En attente', $decisions, true)) return 'En attente';
  if (in_array('Publiée',  $decisions, true)) return 'Publiée';
  if (in_array('Validée',  $decisions, true)) return 'Validée';

  return (string)($pub_row['statut'] ?? 'En attente');
}

/** S'assure que la table de partage possède bien la colonne summary_en */
private static function ensure_share_schema(): void {
  global $wpdb;
  $t = self::t_pub_shares();
  $col = $wpdb->get_var($wpdb->prepare(
    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = %s AND COLUMN_NAME = 'summary_en'", $t
  ));
  if ($col !== 'summary_en') {
    // On ajoute la colonne en TEXT (null/empty ok)
    $wpdb->query("ALTER TABLE {$t} ADD COLUMN summary_en TEXT NULL");
  }
  $col2 = $wpdb->get_var($wpdb->prepare(
    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = %s AND COLUMN_NAME = 'commentaire'", $t
  ));
  if ($col2 !== 'commentaire') {
    $wpdb->query("ALTER TABLE {$t} ADD COLUMN commentaire TEXT NULL");
  }
}

  /* ======================== CRUD ======================== */

  public static function create(array $payload) {
    global $wpdb; $t = self::t_publications();
    $uid = get_current_user_id(); if (!$uid) return new WP_Error('forbidden','Non connecté', ['status'=>401]);

    $labs = self::my_lab_ids(); if (empty($labs))
      return new WP_Error('forbidden','Aucun laboratoire rattaché', ['status'=>403]);

    $lab_id = (int)($payload['laboratoire_id'] ?? 0);
    if ($lab_id && !in_array($lab_id, $labs, true))
      return new WP_Error('forbidden','Labo invalide pour cet utilisateur', ['status'=>403]);
    if (!$lab_id) $lab_id = (int)$labs[0];

    // statut initial : on laisse "En attente" (la co-validation décidera ensuite)
    $data = [
      'date_publication' => sanitize_text_field($payload['date_publication'] ?? ''),
      'type'             => sanitize_text_field($payload['type'] ?? ''),
      'titre'            => sanitize_text_field($payload['titre'] ?? ''),
      'resume'           => sanitize_textarea_field($payload['resume'] ?? ''),
      'commentaire'      => sanitize_textarea_field($payload['commentaire'] ?? ''),
      'laboratoire_id'   => $lab_id,
      'chercheur_id'     => $uid,
      'created_by'       => $uid,
      'updated_by'       => $uid,
      'created_at'       => current_time('mysql'),
      'updated_at'       => current_time('mysql'),
      'statut'           => 'En attente',
      'doi'              => sanitize_text_field($payload['doi'] ?? ''),
      'nb_pages'         => isset($payload['nb_pages']) ? (int)$payload['nb_pages'] : null,
      'maison_edition_scientifique' => sanitize_text_field($payload['maison_edition_scientifique'] ?? ''),
      'title_en'   => sanitize_text_field($payload['title_en'] ?? ''),
      'summary_en' => sanitize_textarea_field($payload['summary_en'] ?? ''),
    ];

    // DOI: format + unicité
    if (!empty($payload['doi'])) {
      $doi = trim((string)$payload['doi']);
      if (!preg_match('/^10\.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i', $doi)) {
        return new WP_Error('invalid_doi', 'DOI invalide', ['status'=>400]);
      }
      $dup = (bool)$wpdb->get_var($wpdb->prepare(
        "SELECT 1 FROM {$t} WHERE doi <> '' AND LOWER(doi)=LOWER(%s) LIMIT 1", $doi
      ));
      if ($dup) return new WP_Error('duplicate_doi','Ce DOI existe déjà',['status'=>409]);
      $data['doi'] = $doi;
    }

    $ok = $wpdb->insert($t, $data);
    if (!$ok) return new WP_Error('db_error','Insert failed: '.$wpdb->last_error, ['status'=>500]);

    $pub_id = (int)$wpdb->insert_id;
    $actor  = $uid;

    // Base keywords/files
    if (!empty($payload['keywords']) && is_array($payload['keywords'])) {
      $kw = array_values(array_filter(array_map('strval',$payload['keywords'])));
      if ($kw) self::replace_base_keywords($pub_id, $kw, $actor);
    }
    if (!empty($payload['files']) && is_array($payload['files'])) {
      self::add_base_files($pub_id, $payload['files'], $actor);
    }

    // Partage si Article
    $isArticle = preg_match('/^\s*article\b/i', (string)$data['type']) === 1;
    if ($isArticle) {
      try {
        $share_ids = [];
        if (!empty($payload['share_with_user_ids']) && is_array($payload['share_with_user_ids'])) {
          $share_ids = array_map('intval', $payload['share_with_user_ids']);
        }
        $share_keywords = !empty($payload['share_keywords'])
          ? array_values(array_filter(array_map('strval',$payload['share_keywords'])))
          : [];
        $share_files = (!empty($payload['share_files']) && is_array($payload['share_files']))
          ? $payload['share_files'] : [];

        if (($share_keywords || $share_files) && empty($share_ids)) {
          $share_ids = [$uid];
        }

        if (!empty($share_ids)) {
          self::replace_shares($pub_id, $share_ids, $uid);
          self::prefill_share_meta($pub_id, $share_ids, null, null);
          if ($share_keywords || $share_files) {
            self::seed_share_extras($pub_id, $share_ids, $share_keywords, $share_files, $uid);
          }
        }
      } catch (\Throwable $e) {
        error_log('[PubService] share insert failed: '.$e->getMessage());
      }
    }

    return self::get($pub_id);
  }

  public static function get(int $id) {
    global $wpdb; $t = self::t_publications();
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
    if (!$row) return null;
    $row['auteur_display_name'] = get_the_author_meta('display_name', (int)$row['chercheur_id']) ?: '';
    $row['base_keywords'] = self::list_base_keywords($id);
    $row['base_files']    = self::list_base_files($id);

    // statut vu par le lecteur courant
    $viewer = get_current_user_id();
    $row['viewer_statut'] = $viewer ? self::viewer_local_statut($row, $viewer) : (string)($row['statut'] ?? 'En attente');
    return $row;
  }

  public static function update(int $id, array $payload) {
    global $wpdb; $t = self::t_publications();
    $uid = get_current_user_id(); if (!$uid) return new WP_Error('forbidden','Non connecté', ['status'=>401]);

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
    if (!$row) return new WP_Error('not_found','Publication introuvable', ['status'=>404]);

    if (!self::can_edit_row($row, $uid)) {
      return new WP_Error('forbidden','Modification non autorisée', ['status'=>403]);
    }

    $data = [];
    if (array_key_exists('type', $payload))             $data['type']             = sanitize_text_field($payload['type']);
    if (array_key_exists('date_publication',$payload))  $data['date_publication'] = sanitize_text_field($payload['date_publication']);
    if (array_key_exists('titre', $payload))            $data['titre']            = sanitize_text_field($payload['titre']);
    if (array_key_exists('resume', $payload))           $data['resume']           = sanitize_textarea_field($payload['resume']);
    if (array_key_exists('title_en', $payload))         $data['title_en']         = sanitize_text_field($payload['title_en']);
    if (array_key_exists('summary_en', $payload))       $data['summary_en']       = sanitize_textarea_field($payload['summary_en']);
    if (array_key_exists('commentaire', $payload))      $data['commentaire']      = sanitize_textarea_field($payload['commentaire']);
    if (array_key_exists('fichier_url', $payload))      $data['fichier_url']      = esc_url_raw($payload['fichier_url']);
    if (array_key_exists('doi', $payload))              $data['doi']              = sanitize_text_field($payload['doi']);
    if (array_key_exists('nb_pages', $payload))         $data['nb_pages']         = (int)$payload['nb_pages'] ?: null;
    if (array_key_exists('maison_edition_scientifique', $payload))
      $data['maison_edition_scientifique'] = sanitize_text_field($payload['maison_edition_scientifique']);

    if (array_key_exists('doi', $payload)) {
      $newDoi = trim((string)$payload['doi']);
      if ($newDoi !== '') {
        if (!preg_match('/^10\.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i', $newDoi)) {
          return new WP_Error('invalid_doi', 'DOI invalide', ['status'=>400]);
        }
        $dup = (bool) $wpdb->get_var(
          $wpdb->prepare("SELECT 1 FROM {$t} WHERE id <> %d AND doi <> '' AND LOWER(doi) = LOWER(%s) LIMIT 1", $id, $newDoi)
        );
        if ($dup) {
          return new WP_Error('duplicate_doi', 'Ce DOI existe déjà pour une autre publication.', ['status'=>409]);
        }
      }
    }

    if (!empty($data)) {
      $data['updated_by'] = $uid;
      $data['updated_at'] = current_time('mysql');
      $ok = $wpdb->update($t, $data, ['id'=>$id], null, ['%d']);
      if ($ok === false) return new WP_Error('db_error','Update failed: '.$wpdb->last_error, ['status'=>500]);
    }

    $curType  = $data['type'] ?? $row['type'];
    $isArticle = preg_match('/^\s*article\b/i', (string)$curType) === 1;

    if ($isArticle) {
      try {
        if (!empty($payload['share_file_ids_delete']) && is_array($payload['share_file_ids_delete'])) {
          self::delete_share_files_by_ids($id, $payload['share_file_ids_delete']);
        }

        $share_users_changed = false;
        if (array_key_exists('share_with_user_ids', $payload) && is_array($payload['share_with_user_ids'])) {
          self::sync_shares($id, $payload['share_with_user_ids'], $uid);
          $share_users_changed = true;
        }

        $target_user_ids = $share_users_changed
          ? array_map('intval', (array)$payload['share_with_user_ids'])
          : self::list_share_user_ids($id);

        $share_keywords = [];
        $share_files    = [];
        if (!empty($payload['share_keywords']) && is_array($payload['share_keywords'])) {
          $share_keywords = array_values(array_filter(array_map('strval', $payload['share_keywords'])));
        }
        if (!empty($payload['share_files']) && is_array($payload['share_files'])) {
          $share_files = $payload['share_files'];
        }

        if (empty($target_user_ids) && ($share_keywords || $share_files)) {
          self::sync_shares($id, [$uid], $uid);
          $target_user_ids = [$uid];
        }

        if (!empty($share_keywords) || !empty($share_files)) {
          self::seed_share_extras($id, $target_user_ids, $share_keywords, $share_files, $uid);
        }
      } catch (\Throwable $e) {
        error_log('[PubService] share update failed: '.$e->getMessage());
      }
    }

    // base keywords/files
    $actor = $uid;
    if (array_key_exists('keywords', $payload) && is_array($payload['keywords'])) {
      $kw = array_values(array_filter(array_map('strval',$payload['keywords'])));
      self::replace_base_keywords($id, $kw, $actor);
    }
    if (!empty($payload['files']) && is_array($payload['files'])) {
      self::add_base_files($id, $payload['files'], $actor);
    }
    if (!empty($payload['file_ids_delete']) && is_array($payload['file_ids_delete'])) {
      self::delete_share_files_by_ids($id, $payload['file_ids_delete']);
    }

    return self::get($id);
  }

  public static function delete(int $id) {
    global $wpdb; $t = self::t_publications(); $uid = get_current_user_id();
    $cur = $wpdb->get_row($wpdb->prepare("SELECT created_by, statut FROM {$t} WHERE id=%d", $id), ARRAY_A);
    if (!$cur) return new WP_Error('not_found','Introuvable', ['status'=>404]);

    $can = false;
    if (self::is_service_utm()) $can = true;
    else if ((int)$cur['created_by'] === $uid && strtolower($cur['statut']) !== 'validée') $can = true;

    if (!$can) return new WP_Error('forbidden','Suppression non autorisée', ['status'=>403]);

    $ok = $wpdb->delete($t, ['id'=>$id], ['%d']);
    if (!$ok) return new WP_Error('db_error','Delete failed', ['status'=>500]);
    return true;
  }

  /** Directeur pose une décision (validate/reject/publish) → co-validation */
 public static function set_status(int $id, string $status) {
  global $wpdb; $t = self::t_publications(); $uid = get_current_user_id();

  $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
  if (!$row) return new WP_Error('not_found','Introuvable', ['status'=>404]);

  // Le directeur doit diriger au moins UN des labos impliqués
  $dir_labs = self::labs_directed_by($uid);
  if (!self::is_service_utm()) {
    $involved = self::involved_lab_ids($id);
    if (empty(array_intersect($dir_labs, $involved))) {
      return new WP_Error('forbidden','Action réservée au(x) directeur(s) des labos impliqués', ['status'=>403]);
    }
  }

  // Normaliser le statut demandé
  $status = in_array($status, ['Validée','Rejetée','En attente','Publiée'], true) ? $status : 'En attente';

  // 1) Enregistrer la décision locale du directeur courant
  self::record_director_decision($id, $uid, $status);

  // *** NOUVEAU : si Service UTM OU directeur du labo "propriétaire" de la pub,
  // on "pousse" immédiatement le statut global. ***
  $isOwnerDirector = in_array((int)$row['laboratoire_id'], $dir_labs, true);

  if (self::is_service_utm() || $isOwnerDirector) {
    $upd = [
      'statut'     => $status,
      'updated_by' => $uid,
      'updated_at' => current_time('mysql'),
    ];
    if ($status === 'Validée') {
      $upd['validated_by'] = $uid;
      $upd['validated_at'] = current_time('mysql');
    }
    // Pour 'Publiée', on conserve validated_* s’ils existent déjà
    $wpdb->update($t, $upd, ['id'=>$id], null, ['%d']);
    return self::get($id);
  }

  // 2) Sinon, on garde la co-validation : push global seulement si tous d’accord
  $agree = self::aggregate_if_all_agree($id);
  if ($agree !== null) {
    $upd = [
      'statut'     => $agree,
      'updated_by' => $uid,
      'updated_at' => current_time('mysql'),
    ];
    if ($agree === 'Validée') {
      $upd['validated_by'] = $uid;
      $upd['validated_at'] = current_time('mysql');
    }
    $wpdb->update($t, $upd, ['id'=>$id], null, ['%d']);
  }

  return self::get($id);
}


  /* ======================== LIST ======================== */
  public static function list(array $opts = []) {
    global $wpdb;
    $t   = self::t_publications();
    $sT  = self::t_pub_shares();
    $uid = get_current_user_id();
    if (!$uid) return [];

    $with_auteur    = !empty($opts['with_auteur']);
    $me             = !empty($opts['me']);
    $include_shared = !empty($opts['include_shared']);
    $shared_scope   = strtolower(trim((string)($opts['shared_scope'] ?? ''))); // '' | 'lab'
    $scope          = strtolower(trim((string)($opts['scope'] ?? '')));        // '' | 'director_labs'
    $search         = trim((string)($opts['search'] ?? ''));

    $where   = [];
    $params  = [];
    $joins   = '';
    $selectX = '';

    if ($search !== '') {
      $like = '%'.$wpdb->esc_like($search).'%';
      $where[] = "(p.titre LIKE %s OR p.type LIKE %s OR p.resume LIKE %s)";
      array_push($params, $like, $like, $like);
    }

    if ($me && $include_shared) {
      $joins   .= " LEFT JOIN {$sT} s_me ON (s_me.publication_id=p.id AND s_me.user_id=%d) ";
      $params[] = $uid;
      $selectX .= " , CASE WHEN s_me.user_id IS NULL THEN 0 ELSE 1 END AS shared_for_me ";
    } else {
      $selectX .= " , 0 AS shared_for_me ";
    }

    if ($me) {
      $cond_me  = "(p.created_by=%d";
      $params[] = $uid;
      if ($include_shared) {
        $cond_me .= " OR EXISTS(SELECT 1 FROM {$sT} s WHERE s.publication_id=p.id AND s.user_id=%d)";
        $params[] = $uid;
      }
      $cond_me .= ")";

      $cond_lab = '';
      if ($shared_scope === 'lab' && self::is_directeur()) {
        $myLabIds = self::my_lab_ids();
        if (!empty($myLabIds)) {
          $mT = self::t_membres();
          $lT = self::t_labs();

          $memberIds = $wpdb->get_col("SELECT DISTINCT user_id FROM {$mT} WHERE laboratoire_id IN (".implode(',', array_map('intval',$myLabIds)).")") ?: [];
          $dirIds    = $wpdb->get_col("SELECT DISTINCT directeur_user_id FROM {$lT} WHERE id IN (".implode(',', array_map('intval',$myLabIds)).") AND directeur_user_id IS NOT NULL") ?: [];
          $labUserIds = array_values(array_unique(array_map('intval', array_merge($memberIds, $dirIds))));

          if (!empty($labUserIds)) {
            $cond_a = "EXISTS(SELECT 1 FROM {$sT} s2 WHERE s2.publication_id=p.id AND s2.user_id IN (".implode(',', $labUserIds)."))";
            $cond_b = "(p.created_by IN (".implode(',', $labUserIds).") AND EXISTS(SELECT 1 FROM {$sT} s_any WHERE s_any.publication_id=p.id))";
            $cond_lab = "({$cond_a} OR {$cond_b})";

            $joins   .= " LEFT JOIN {$sT} s_lab ON (s_lab.publication_id=p.id AND s_lab.user_id IN (".implode(',', $labUserIds).")) ";
            $joins   .= " LEFT JOIN {$sT} s_any ON (s_any.publication_id=p.id) ";

            $selectX .= " , CASE WHEN (s_lab.user_id IS NOT NULL OR (s_any.publication_id IS NOT NULL AND p.created_by IN (".implode(',', $labUserIds).")))
                                  THEN 1 ELSE 0 END AS shared_for_lab ";
          } else {
            $selectX .= " , 0 AS shared_for_lab ";
          }
        } else {
          $selectX .= " , 0 AS shared_for_lab ";
        }
      } else {
        $selectX .= " , 0 AS shared_for_lab ";
      }

      if ($cond_lab !== '') {
        $where[] = "({$cond_me} OR {$cond_lab})";
      } else {
        $where[] = $cond_me;
      }

    } else if ($with_auteur) {

      if ($scope === 'director_labs' && self::is_directeur()) {
        $labIds = self::labs_directed_by();
        if (empty($labIds)) $where[] = '1=0';
        else $where[] = "p.laboratoire_id IN (".implode(',', array_map('intval',$labIds)).")";

      } else if (self::is_service_utm()) {
        // tout voir

      } else if (self::is_service_etab()) {
        $my_etab = (int) get_user_meta($uid, 'etablissement_id', true);
        if (!$my_etab) $where[]='1=0';
        else {
          $um = $wpdb->usermeta;
          $where[] = "p.created_by IN (SELECT user_id FROM {$um} WHERE meta_key='etablissement_id' AND CAST(meta_value AS UNSIGNED)=%d)";
          $params[] = $my_etab;
        }

      } else if (self::is_directeur()) {
        $labIds = self::labs_directed_by();
        if (empty($labIds)) $where[]='1=0';
        else $where[] = "p.laboratoire_id IN (".implode(',', array_map('intval',$labIds)).")";

      } else {
        $labs = self::my_lab_ids();
        if (empty($labs)) $where[]='1=0';
        else {
          $where[] = "p.laboratoire_id IN (".implode(',', array_map('intval',$labs)).")";
          $where[] = "p.statut = 'Validée'";
        }
      }

      $selectX .= " , 0 AS shared_for_lab ";

    } else {
      $where[] = '1=0';
      $selectX .= " , 0 AS shared_for_lab ";
    }

    $where_sql = empty($where) ? '' : 'WHERE '.implode(' AND ', $where);
    $sql = "SELECT p.* {$selectX} FROM {$t} p {$joins} {$where_sql} ORDER BY p.created_at DESC";
    $rows = $wpdb->get_results($wpdb->prepare($sql, ...$params), ARRAY_A) ?: [];

    foreach ($rows as &$r) {
      $r['auteur_display_name'] = get_the_author_meta('display_name', (int)$r['chercheur_id']) ?: '';
      $r['can_moderate'] = (self::is_service_utm() ? 1 : 0);
      if (!$r['can_moderate'] && self::is_directeur()) {
        $r['can_moderate'] = (int)$wpdb->get_var(
          $wpdb->prepare("SELECT 1 FROM ".self::t_labs()." WHERE id=%d AND directeur_user_id=%d", (int)$r['laboratoire_id'], $uid)
        );
      }
      // Ajouter le statut local vu par le lecteur
      $r['viewer_statut'] = self::viewer_local_statut($r, $uid);
    }
    return $rows;
  }

  public static function eligible_share_users_all(string $search = ''): array {
    $roles = array_merge(self::$ROLE_CHERCHEUR, self::$ROLE_DIR);
    $args = [
      'role__in'        => $roles,
      'orderby'         => 'display_name',
      'order'           => 'ASC',
      'number'          => 50,
      'fields'          => ['ID','display_name','user_email'],
    ];
    if ($search !== '') {
      $args['search'] = '*' . $search . '*';
      $args['search_columns'] = ['display_name', 'user_email'];
    }
    $users = get_users($args);
    return array_map(function($u){
      return [
        'id'    => (int) $u->ID,
        'label' => sprintf('%s <%s>', $u->display_name, $u->user_email),
      ];
    }, $users);
  }

  /* ========= PARTAGE : lecture/édition par le destinataire ========= */

  public static function get_my_share_row(int $pub_id) : ?array {
    global $wpdb; $uid = get_current_user_id(); if(!$uid) return null;
    self::ensure_share_schema();
    $sT = self::t_pub_shares();
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$sT} WHERE publication_id=%d AND user_id=%d", $pub_id, $uid), ARRAY_A);
    return $row ?: null;
  }

  public static function can_edit_share(int $pub_id, int $uid): bool {
    global $wpdb;
    $sT = self::t_pub_shares();
    $ok = (bool) $wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$sT} WHERE publication_id=%d AND user_id=%d", $pub_id, $uid));
    return $ok;
  }

  public static function get_with_my_share(int $pub_id): ?array {
    $p = self::get($pub_id); if(!$p) return null;
    $share = self::get_my_share_row($pub_id);
    if($share){
      $share['files'] = self::list_share_files((int)$share['id']);
      $share['keywords'] = self::list_share_keywords((int)$share['id']);
    }
    return ['publication'=>$p, 'my_share'=>$share];
  }

  public static function list_share_files(int $share_id): array {
    global $wpdb; $t = self::t_pub_files();
    return $wpdb->get_results($wpdb->prepare("SELECT id, original_name, storage_path, created_at FROM {$t} WHERE publication_share_id=%d ORDER BY id ASC", $share_id), ARRAY_A) ?: [];
  }

  public static function list_share_keywords(int $share_id): array {
    global $wpdb; $t = self::t_pub_keywords();
    $rows = $wpdb->get_col($wpdb->prepare("SELECT contenu FROM {$t} WHERE publication_share_id=%d ORDER BY id ASC", $share_id)) ?: [];
    return array_values(array_filter(array_map('strval',$rows)));
  }

 public static function upsert_my_share(int $pub_id, array $payload){
  global $wpdb;
  self::ensure_share_schema();
  $uid = get_current_user_id(); if(!$uid) return new WP_Error('forbidden','Non connecté',['status'=>401]);

  $sT = self::t_pub_shares();
  $kT = self::t_pub_keywords();
  $fT = self::t_pub_files();

  // récupérer/ créer la ligne de part
  $row = self::get_my_share_row($pub_id); // peut être null

  $data = [];
  if(array_key_exists('resume',$payload))           $data['resume']           = sanitize_textarea_field($payload['resume']);
    if(array_key_exists('summary_en',$payload))       $data['summary_en']       = sanitize_textarea_field($payload['summary_en']); // <<--- AJOUT
      if(array_key_exists('commentaire',$payload))      $data['commentaire']      = sanitize_textarea_field($payload['commentaire']);
  if(array_key_exists('nb_pages',$payload))         $data['nb_pages']         = (int)$payload['nb_pages'] ?: null;
  if(array_key_exists('date_publication',$payload)) $data['date_publication'] = sanitize_text_field($payload['date_publication']);
  if(array_key_exists('fichier_url',$payload))      $data['fichier_url']      = esc_url_raw($payload['fichier_url']);

  $wpdb->query('START TRANSACTION');
  try{
    if($row){ // update
      if(!empty($data)){
        $wpdb->update($sT, $data, ['id'=>(int)$row['id']], null, ['%d']);
      }
      $share_id = (int)$row['id'];
    } else {  // insert automatique
      $wpdb->insert($sT, array_merge([
        'publication_id' => $pub_id,
        'user_id'        => $uid,
        'added_by'       => $uid,
        'created_at'     => current_time('mysql'),
      ], $data), ['%d','%d','%d','%s']);
      $share_id = (int)$wpdb->insert_id;
    }

    // === Keywords de MA part : remplacement complet si envoyés
    if (array_key_exists('keywords',$payload) && is_array($payload['keywords'])) {
      $kws = array_values(array_filter(array_map('strval',$payload['keywords'])));
      self::replace_share_keywords($pub_id, $share_id, $kws, $uid);
    }

    // === Suppression ciblée de fichiers de MA part
    if (!empty($payload['file_ids_delete']) && is_array($payload['file_ids_delete'])) {
      self::delete_files_of_share_by_ids($share_id, $payload['file_ids_delete']);
    }

    // === Ajout de nouveaux fichiers à MA part
    if (!empty($payload['files']) && is_array($payload['files'])) {
      self::add_files_to_share($pub_id, $share_id, $payload['files'], $uid);
    }

    $wpdb->query('COMMIT');
    return self::get_with_my_share($pub_id);
  } catch (\Throwable $e) {
    $wpdb->query('ROLLBACK');
    return new WP_Error('db_error', 'Share upsert failed: '.$e->getMessage(), ['status'=>500]);
  }
}

/** Remplace les mots-clés de MA part */
private static function replace_share_keywords(int $pub_id, int $share_id, array $keywords, int $actor_id): void {
  global $wpdb; $t = self::t_pub_keywords();
  $now = current_time('mysql');
  $wpdb->delete($t, ['publication_share_id'=>$share_id], ['%d']);
  foreach ($keywords as $kw) {
    $kw = trim((string)$kw); if ($kw==='') continue;
    $wpdb->insert($t, [
      'contenu'              => mb_substr($kw,0,255),
      'publication_id'       => $pub_id,
      'publication_share_id' => $share_id,
      'created_by'           => $actor_id,
      'created_at'           => $now,
    ], ['%s','%d','%d','%d','%s']);
  }
}

/** Supprime des fichiers (IDs) mais UNIQUEMENT s’ils appartiennent à MA part */
private static function delete_files_of_share_by_ids(int $share_id, array $file_ids): void {
  global $wpdb; $t = self::t_pub_files();
  $ids = array_values(array_unique(array_map('intval', array_filter($file_ids))));
  if (!$ids) return;
  $in = implode(',', array_fill(0, count($ids), '%d'));
  // sécurité: vérifier le share_id
  $wpdb->query($wpdb->prepare("DELETE FROM {$t} WHERE publication_share_id=%d AND id IN ($in)", $share_id, ...$ids));
}

/** Ajoute des fichiers à MA part */
private static function add_files_to_share(int $pub_id, int $share_id, array $files, int $actor_id): void {
  global $wpdb; $t = self::t_pub_files();
  $now = current_time('mysql');
  foreach ($files as $f) {
    $name = trim((string)($f['original_name'] ?? ''));
    $path = trim((string)($f['storage_path'] ?? ''));
    if ($name==='' || $path==='') continue;
    $wpdb->insert($t, [
      'publication_id'       => $pub_id,
      'publication_share_id' => $share_id,
      'original_name'        => mb_substr($name,0,255),
      'storage_path'         => mb_substr($path,0,255),
      'created_by'           => $actor_id,
      'created_at'           => $now,
      'updated_at'           => $now,
    ], ['%d','%d','%s','%s','%d','%s','%s']);
  }
}


 public static function list_shares(int $pub_id): array {
  global $wpdb;
  $sT = self::t_pub_shares();
  $wp_users = $wpdb->users;

  $rows = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT s.id AS share_id,
              s.user_id,
              s.nb_pages,             -- << ajouté
              s.resume, 
              s.summary_en,              -- (optionnel selon besoin)
              s.date_publication,     -- (optionnel)
              s.fichier_url,          -- (optionnel)
              s.created_at,
              u.display_name,
              u.user_email
       FROM {$sT} s
       LEFT JOIN {$wp_users} u ON u.ID = s.user_id
       WHERE s.publication_id=%d
       ORDER BY s.id ASC",
       $pub_id
    ),
    ARRAY_A
  ) ?: [];

  foreach ($rows as &$r) {
    $sid = (int)$r['share_id'];
    $labelName  = $r['display_name'] ?: ('User #'.$r['user_id']);
    $labelEmail = $r['user_email'] ?: '';
    $r['label']    = sprintf('%s <%s>', $labelName, $labelEmail);
    $r['keywords'] = self::list_share_keywords($sid);
    $r['files']    = self::list_share_files($sid);
  }
  return $rows;
}

public static function list_public_by_lab(int $lab_id, array $args = []) : array {
  global $wpdb;
  if ($lab_id <= 0) return [];

  $t = self::t_publications();

  $page     = max(1, (int)($args['page'] ?? 1));
  $per_page = min(200, max(1, (int)($args['per_page'] ?? 20)));
  $offset   = ($page - 1) * $per_page;

  $type = trim((string)($args['type'] ?? ''));

  $where = [
    "p.laboratoire_id = %d",
    // Statuts publics uniquement
    "LOWER(TRIM(p.statut)) IN ('validée','validee','valide','publiée','publiee','published')"
  ];
  $params = [ $lab_id ];

  if ($type !== '') {
    $where[]  = "p.type = %s";
    $params[] = $type;
  }

  $sql = "
    SELECT
      p.id,
      p.titre,
      p.resume,
      p.type,
      COALESCE(NULLIF(p.date_publication,'0000-00-00'), DATE(p.created_at)) AS date,
      p.created_by AS chercheur_id
    FROM {$t} p
    WHERE ".implode(' AND ', $where)."
    ORDER BY date DESC
    LIMIT %d OFFSET %d
  ";
  $params[] = $per_page;
  $params[] = $offset;

  $rows = $wpdb->get_results($wpdb->prepare($sql, ...$params), ARRAY_A) ?: [];

  // enrichissements attendus par le front
  foreach ($rows as &$r) {
    $r['auteur_display'] = get_the_author_meta('display_name', (int)$r['chercheur_id']) ?: '';
    // $r['revue'] = ... ; // si tu as une colonne ou une meta pour la revue
  }
  return $rows;
}

  public static function list_share_user_ids(int $pub_id): array {
    global $wpdb; $sT = self::t_pub_shares();
    $ids = $wpdb->get_col($wpdb->prepare("SELECT user_id FROM {$sT} WHERE publication_id=%d", $pub_id)) ?: [];
    return array_values(array_unique(array_map('intval',$ids)));
  }

  private static function prefill_share_meta(int $pub_id, array $user_ids, ?string $resume, ?int $nb_pages) : void {
    if (empty($user_ids)) return;
    global $wpdb;
    $sT = self::t_pub_shares();
    $ids = implode(',', array_map('intval', $user_ids));

    $data = [];
    $fmt  = [];
    if ($resume !== null)   { $data['resume']   = sanitize_textarea_field($resume); $fmt[] = '%s'; }
    if ($nb_pages !== null) { $data['nb_pages'] = (int)$nb_pages;                   $fmt[] = '%d'; }
    if (empty($data)) return;

    $rows = $wpdb->get_col(
      $wpdb->prepare("SELECT id FROM {$sT} WHERE publication_id=%d AND user_id IN ($ids)", $pub_id)
    );
    foreach ($rows as $sid) {
      $wpdb->update($sT, $data, ['id'=>(int)$sid], $fmt, ['%d']);
    }
  }

  /* ======================== STATS ======================== */
  public static function stats(array $args = []) {
    global $wpdb;

    $tp   = $wpdb->prefix . 'recherche_publication';
    $tlab = $wpdb->prefix . 'recherche_laboratoire';
    $tmem = $wpdb->prefix . 'recherche_membre';

    $uid   = get_current_user_id();
    $roles = wp_get_current_user() ? (array) wp_get_current_user()->roles : [];

    $yearLabel = trim((string)($args['year'] ?? ''));
    $dateFrom = null; $dateTo = null;
    if ($yearLabel && preg_match('/(\d{4}).*?(\d{4})/', $yearLabel, $m)) {
      $y1 = (int)$m[1]; $y2 = (int)$m[2];
      $dateFrom = sprintf('%04d-09-01', $y1);
      $dateTo   = sprintf('%04d-08-31', $y2);
    }

    $roleL = array_map('strtolower', $roles);
    $isServiceUTM  = in_array('um_service-utm', $roleL, true) || in_array('service-utm', $roleL, true);
    $isServiceEtab = in_array('um_service-etablissement', $roleL, true) || in_array('service-etablissement', $roleL, true);
    $isDirecteur   = in_array('um_directeur_laboratoire', $roleL, true) || in_array('directeur_laboratoire', $roleL, true);

    $joins  = [];
    $where  = [];
    $params = [];

    if ($isServiceUTM) {
      // rien
    } elseif ($isServiceEtab) {
      $myEtab = (int) get_user_meta($uid, 'etablissement_id', true);
      if (!$myEtab) $where[] = '1=0';
      else {
        $joins[]  = "JOIN {$tlab} lab ON lab.id = p.laboratoire_id";
        $where[]  = 'lab.etablissement_id = %d';
        $params[] = $myEtab;
      }
    } elseif ($isDirecteur) {
      $labIds = self::labs_directed_by($uid);
      if (empty($labIds)) $where[]='1=0';
      else $where[] = 'p.laboratoire_id IN ('.implode(',', array_map('intval',$labIds)).')';
    } else {
      $labIds = $wpdb->get_col($wpdb->prepare("SELECT laboratoire_id FROM {$tmem} WHERE user_id=%d", $uid)) ?: [];
      $labIds = array_map('intval',$labIds);
      if (empty($labIds)) $where[]='1=0';
      else $where[] = 'p.laboratoire_id IN ('.implode(',', $labIds).')';
    }

    $scopeJoin = implode("\n", $joins);
    $scopeWhere = empty($where) ? '1=1' : implode(' AND ', $where);

    $sql = "
      SELECT
        COUNT(*)                                                     AS total,
        SUM(CASE WHEN x.st = 'publiees'   THEN 1 ELSE 0 END)         AS publiees,
        SUM(CASE WHEN x.st = 'en_attente' THEN 1 ELSE 0 END)         AS en_attente,
        SUM(CASE WHEN x.st = 'rejetees'   THEN 1 ELSE 0 END)         AS rejetees
      FROM (
        SELECT
          p.id,
          DATE(COALESCE(NULLIF(p.date_publication,'0000-00-00'), DATE(p.created_at))) AS dref,
          CASE
            WHEN LOWER(TRIM(p.statut)) IN ('validée','validee','valide','publiee','publiée','published') THEN 'publiees'
            WHEN LOWER(TRIM(p.statut)) IN ('rejete','rejetee','rejetée','rejected')                      THEN 'rejetees'
            ELSE 'en_attente'
          END AS st
        FROM {$tp} p
        {$scopeJoin}
        WHERE {$scopeWhere}
      ) x
      WHERE 1=1
    ";

    if ($dateFrom && $dateTo) {
      $sql    .= " AND x.dref BETWEEN %s AND %s ";
      $params[] = $dateFrom;
      $params[] = $dateTo;
    }

    $row = $wpdb->get_row($wpdb->prepare($sql, ...$params), ARRAY_A);

    return [
      'total'      => (int)($row['total'] ?? 0),
      'publiees'   => (int)($row['publiees'] ?? 0),
      'en_attente' => (int)($row['en_attente'] ?? 0),
      'rejetees'   => (int)($row['rejetees'] ?? 0),
      'from'       => $dateFrom ?: null,
      'to'         => $dateTo   ?: null,
    ];
  }

  /* ======================== Keywords/Fichiers base ======================== */

  public static function list_base_keywords(int $pub_id): array {
    global $wpdb; $t = self::t_pub_keywords();
    $rows = $wpdb->get_col($wpdb->prepare(
      "SELECT contenu FROM {$t} WHERE publication_id=%d AND (publication_share_id IS NULL OR publication_share_id=0) ORDER BY id ASC",
      $pub_id
    )) ?: [];
    return array_values(array_filter(array_map('strval',$rows)));
  }

  public static function list_base_files(int $pub_id): array {
    global $wpdb; $t = self::t_pub_files();
    return $wpdb->get_results($wpdb->prepare(
      "SELECT id, original_name, storage_path, created_at FROM {$t}
       WHERE publication_id=%d AND (publication_share_id IS NULL OR publication_share_id=0)
       ORDER BY id ASC",
      $pub_id
    ), ARRAY_A) ?: [];
  }

 private static function replace_base_keywords(int $pub_id, array $keywords, int $actor_id): void {
  global $wpdb; $t = self::t_pub_keywords();
  $now = current_time('mysql');
  $wpdb->query('START TRANSACTION');
  try{
    $wpdb->query($wpdb->prepare(
      "DELETE FROM {$t} WHERE publication_id=%d AND (publication_share_id IS NULL OR publication_share_id=0)",
      $pub_id
    ));
    foreach ($keywords as $kw) {
      $kw = trim((string)$kw); if ($kw==='') continue;
      // ⚠️ NE PAS mettre publication_share_id => 0
      $wpdb->insert($t, [
        'contenu'        => mb_substr($kw,0,255),
        'publication_id' => $pub_id,
        'created_by'     => $actor_id,
        'created_at'     => $now,
      ], ['%s','%d','%d','%s']);
      // NB: on omet la colonne => restera NULL (compat. FK)
    }
    $wpdb->query('COMMIT');
  } catch (\Throwable $e) {
    $wpdb->query('ROLLBACK');
    error_log('[PubService] replace_base_keywords failed: '.$e->getMessage());
  }
}


  private static function add_base_files(int $pub_id, array $files, int $actor_id): void {
  if (empty($files)) return;
  global $wpdb; $t = self::t_pub_files();
  $now = current_time('mysql');
  $wpdb->query('START TRANSACTION');
  try{
    foreach ($files as $f) {
      $name = trim((string)($f['original_name'] ?? ''));
      $path = trim((string)($f['storage_path'] ?? ''));
      if ($name==='' || $path==='') continue;
      // ⚠️ NE PAS mettre publication_share_id => 0
      $wpdb->insert($t, [
        'publication_id' => $pub_id,
        'original_name'  => mb_substr($name,0,255),
        'storage_path'   => mb_substr($path,0,255),
        'created_by'     => $actor_id,
        'created_at'     => $now,
        'updated_at'     => $now,
      ], ['%d','%s','%s','%d','%s','%s']);
    }
    $wpdb->query('COMMIT');
  } catch (\Throwable $e) {
    $wpdb->query('ROLLBACK');
    error_log('[PubService] add_base_files failed: '.$e->getMessage());
  }
}

}
