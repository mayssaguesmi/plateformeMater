<?php
/**
 * Logique métier Presentation (CRUD)
 */
if (!defined('ABSPATH')) { exit; }

/* ====== Helpers tables ====== */
if (!function_exists('svc_presentation_table')) {
  function svc_presentation_table() { global $wpdb; return $wpdb->prefix . 'pmo'; }
}

/* ====== Helpers colonnes ====== */
if (!function_exists('svc_presentation_col_exists')) {
  function svc_presentation_col_exists($table, $col) {
    global $wpdb;
    return (bool)$wpdb->get_var($wpdb->prepare("SHOW COLUMNS FROM {$table} LIKE %s", $col));
  }
}

/* ====== Validation & sanitisation ====== */
if (!function_exists('svc_presentation_allowed_fields')) {
  function svc_presentation_allowed_fields(): array {
  return array(
    'creation_year' => 'int',
    'location'      => 'text',
    'email'         => 'email',
    'phone'         => 'text',
    'mission_html'  => 'text',
    'mission_delta' => 'json',
    'directeur_id'  => 'int',   // <--- AJOUT
  );
}

}

if (!function_exists('svc_presentation_fmt')) {
  function svc_presentation_fmt($type) { 
    return $type === 'int' ? '%d' : ($type === 'json' ? '%s' : '%s'); 
  }
}

if (!function_exists('svc_presentation_sanitize')) {
  function svc_presentation_sanitize($key, $val, $type) {
    switch ($type) {
      case 'int':
        return is_numeric($val) ? intval($val) : null;
      case 'email':
        return is_email($val) ? sanitize_email($val) : null;
      case 'json':
        return is_array($val) || is_object($val) ? json_encode($val) : (is_string($val) ? $val : null);
      default:
        return is_scalar($val) ? sanitize_text_field($val) : null;
    }
  }
}

/* ====== GET /presentation (liste) ====== */
if (!function_exists('svc_presentation_list')) {
  function svc_presentation_list(WP_REST_Request $req) {
  global $wpdb; 
  $t = svc_presentation_table();
  
  $page = max(1, intval($req->get_param('page') ?: 1));
  $per  = max(1, min(200, intval($req->get_param('per_page') ?: 50)));
  $off  = ($page - 1) * $per;

  $sql = "SELECT id, creation_year, location, email, phone,
                 mission_html, mission_delta,
                 directeur_id,
                 created_by, created_at, updated_by, updated_at
          FROM {$t}
          ORDER BY id DESC
          LIMIT %d OFFSET %d";
  
  $rows = $wpdb->get_results($wpdb->prepare($sql, $per, $off), ARRAY_A) ?: array();

  // enrichir avec les infos du directeur
  foreach ($rows as &$r) {
    $dir_id = isset($r['directeur_id']) ? intval($r['directeur_id']) : 0;
    $r['director'] = $dir_id ? svc_user_brief($dir_id) : null;
  }
  return $rows;
}

}

/* ====== GET /presentation/{id} ====== */
if (!function_exists('svc_presentation_get')) {
  function svc_presentation_get(WP_REST_Request $req) {
  global $wpdb; 
  $t = svc_presentation_table(); 
  $id = intval($req['id']);
  
  $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
  if (!$row) return new WP_Error('not_found', 'Introuvable', array('status' => 404));

  $dir_id = isset($row['directeur_id']) ? intval($row['directeur_id']) : 0;
  $row['director'] = $dir_id ? svc_user_brief($dir_id) : null;

  return $row;
}

}

/* ====== POST /presentation (create) ====== */
if (!function_exists('svc_presentation_create')) {
  function svc_presentation_create(WP_REST_Request $req) {
    global $wpdb; 
    $t = svc_presentation_table();
    
    $allowed = svc_presentation_allowed_fields();
    $data = $req->get_json_params(); 
    if (!$data) $data = $req->get_params();
    
    $ins = array(); 
    $fmts = array();
    // Si la colonne existe, on met par défaut le user connecté comme directeur
$uid = get_current_user_id();
if (svc_presentation_col_exists($t, 'directeur_id') && empty($ins['directeur_id'])) {
  $ins['directeur_id'] = $uid;
  $fmts[] = '%d';
}

    foreach ($allowed as $k => $type) {
      if (!array_key_exists($k, $data)) continue;
      $v = svc_presentation_sanitize($k, $data[$k], $type);
      if ($v === null || $v === '') continue;
      $ins[$k] = $v; 
      $fmts[] = svc_presentation_fmt($type);
    }

    // Champs requis minimaux
    if (empty($ins['creation_year']) || empty($ins['location']) || empty($ins['email'])) {
      return new WP_Error('bad_request', 'Les champs "creation_year", "location" et "email" sont requis', array('status' => 400));
    }

    // Audit
    $now = current_time('mysql'); 
    $uid = get_current_user_id();

// Directeur par défaut = user connecté
if (svc_presentation_col_exists($t, 'directeur_id') && empty($ins['directeur_id'])) {
  $ins['directeur_id'] = $uid; 
  $fmts[] = '%d';
}
    if (svc_presentation_col_exists($t, 'created_by')) { $ins['created_by'] = $uid; $fmts[] = '%d'; }
    if (svc_presentation_col_exists($t, 'updated_by')) { $ins['updated_by'] = $uid; $fmts[] = '%d'; }
    if (svc_presentation_col_exists($t, 'created_at')) { $ins['created_at'] = $now; $fmts[] = '%s'; }
    if (svc_presentation_col_exists($t, 'updated_at')) { $ins['updated_at'] = $now; $fmts[] = '%s'; }

    $ok = $wpdb->insert($t, $ins, $fmts);
    if (!$ok) return new WP_Error('db_error', 'Insert failed: ' . $wpdb->last_error, array('status' => 500));

    $id  = (int)$wpdb->insert_id;
$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
$row['director'] = !empty($row['directeur_id']) ? svc_user_brief((int)$row['directeur_id']) : null;
return $row;

  }
}

/* ====== PUT/PATCH /presentation/{id} (update) ====== */
if (!function_exists('svc_presentation_update')) {
  function svc_presentation_update(WP_REST_Request $req) {
    global $wpdb; 
    $t = svc_presentation_table(); 
    $id = intval($req['id']);
    
    $cur = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
    if (!$cur) return new WP_Error('not_found', 'Introuvable', array('status' => 404));

    $allowed = svc_presentation_allowed_fields();
    $data = $req->get_json_params(); 
    if (!$data) $data = $req->get_params();

    $upd = array(); 
    $fmts = array();
    foreach ($allowed as $k => $type) {
      if (!array_key_exists($k, $data)) continue;
      $v = svc_presentation_sanitize($k, $data[$k], $type);
      if ($v === null) continue;
      $upd[$k] = $v; 
      $fmts[] = svc_presentation_fmt($type);
    }

    if (empty($upd)) return new WP_Error('bad_request', 'Aucun champ valide', array('status' => 400));

    // Audit
    if (svc_presentation_col_exists($t, 'updated_by')) { $upd['updated_by'] = get_current_user_id(); $fmts[] = '%d'; }
    if (svc_presentation_col_exists($t, 'updated_at')) { $upd['updated_at'] = current_time('mysql'); $fmts[] = '%s'; }

    $ok = $wpdb->update($t, $upd, array('id' => $id), $fmts, array('%d'));
    if ($ok === false) return new WP_Error('db_error', 'Update failed: ' . $wpdb->last_error, array('status' => 500));

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
    return $row;
  }

  if (!function_exists('svc_user_brief')) {
  function svc_user_brief($user_id) {
    $u = get_userdata($user_id);
    if (!$u) return null;

    $first = get_user_meta($user_id, 'first_name', true);
    $last  = get_user_meta($user_id, 'last_name',  true);

    // Métas optionnelles (adapte les clés à tes usages)
    $grade = get_user_meta($user_id, 'grade',       true);
    $spec  = get_user_meta($user_id, 'specialite',  true);
    $phone = get_user_meta($user_id, 'phone',       true);
    if (!$phone) $phone = get_user_meta($user_id, 'billing_phone', true);

    $full  = trim(($first ?: '') . ' ' . ($last ?: ''));
    if (!$full) $full = $u->display_name;

    return array(
      'id'          => (int)$user_id,
      'display_name'=> $u->display_name,
      'first_name'  => $first ?: '',
      'last_name'   => $last  ?: '',
      'full_name'   => $full,
      'email'       => $u->user_email,
      'phone'       => $phone ?: '',
      'grade'       => $grade ?: '',
      'specialite'  => $spec  ?: '',
      'avatar'      => get_avatar_url($user_id),
    );
  }
}

}


if (!function_exists('svc_pmo_master_table')) {
  function svc_pmo_master_table(){ global $wpdb; return $wpdb->prefix.'pmo'; }
}


/** ====== PMO - Projets (CRUD) ====== */
if (!defined('ABSPATH')) { exit; }

/* ---------- Helpers tables ---------- */
if (!function_exists('svc_pmo_projet_table')) {
  function svc_pmo_projet_table() { global $wpdb; return $wpdb->prefix.'pmo_projet'; }
}
if (!function_exists('svc_recherche_type_projet_table')) {
  function svc_recherche_type_projet_table() { global $wpdb; return $wpdb->prefix.'recherche_type_projet'; }
}
if (!function_exists('svc_recherche_source_financement_table')) {
  function svc_recherche_source_financement_table() { global $wpdb; return $wpdb->prefix.'recherche_source_financement'; }
}

/* ---------- Utils ---------- */
if (!function_exists('svc_pmo_col_exists')) {
  function svc_pmo_col_exists($table, $col){
    global $wpdb;
    return (bool)$wpdb->get_var($wpdb->prepare("SHOW COLUMNS FROM {$table} LIKE %s", $col));
  }
}
if (!function_exists('svc_pmo_fmt')) {
  function svc_pmo_fmt($type) {
    switch($type){
      case 'int':  return '%d';
      case 'date': return '%s';
      default:     return '%s';
    }
  }
}
if (!function_exists('svc_pmo_sanitize')) {
  function svc_pmo_sanitize($key, $val, $type){
    if ($val === null) return null;
    switch ($type){
      case 'int':  return is_numeric($val) ? intval($val) : null;
      case 'date': // attend YYYY-MM-DD
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$val) ? $val : null;
      default:     return is_scalar($val) ? sanitize_text_field($val) : null;
    }
  }
}
if (!function_exists('svc_pmo_allowed_fields')) {
  function svc_pmo_allowed_fields(){
    return array(
      'intitule'               => 'text',
      'type_id'                => 'int',
      'date_debut'             => 'date', // YYYY-MM-DD
      'date_fin'               => 'date',
      'financement'            => 'text',
      'source_financement_id'  => 'int',
      'site_web_source'        => 'text',
      'objectifs'              => 'text',
      'id_pmo' => 'int',

    );
  }
}

/* ---------- Libellés Type & Source (auto-détection colonne label) ---------- */
if (!function_exists('svc_pmo_pick_label_col')) {
  function svc_pmo_pick_label_col($table){
    global $wpdb;
    $cols = $wpdb->get_col("SHOW COLUMNS FROM {$table}");
    $candidates = array('libelle','label','nom','name','type','source','designation','titre','intitule');
    foreach($candidates as $c){ if(in_array($c,$cols,true)) return $c; }
    foreach($cols as $c){ if($c !== 'id') return $c; }
    return 'id';
  }
}
if (!function_exists('svc_pmo_list_types')) {
  function svc_pmo_list_types(){
    global $wpdb;
    $t = svc_recherche_type_projet_table();
    $label = svc_pmo_pick_label_col($t);
    return $wpdb->get_results("SELECT id, {$label} AS label FROM {$t} ORDER BY {$label}", ARRAY_A) ?: array();
  }
}
if (!function_exists('svc_pmo_list_sources')) {
  function svc_pmo_list_sources(){
    global $wpdb;
    $t = svc_recherche_source_financement_table();
    $label = svc_pmo_pick_label_col($t);
    return $wpdb->get_results("SELECT id, {$label} AS label FROM {$t} ORDER BY {$label}", ARRAY_A) ?: array();
  }
}

/* ---------- GET /projets (list) ---------- */
if (!function_exists('svc_pmo_projet_list')) {
  function svc_pmo_projet_list(WP_REST_Request $req){
    global $wpdb;
    $p = svc_pmo_projet_table();
    $t = svc_recherche_type_projet_table();
    $s = svc_recherche_source_financement_table();
    $type_lbl = svc_pmo_pick_label_col($t);
    $src_lbl  = svc_pmo_pick_label_col($s);

    $page = max(1, intval($req->get_param('page') ?: 1));
    $per  = max(1, min(200, intval($req->get_param('per_page') ?: 100)));
    $off  = ($page - 1) * $per;
$pmo = svc_pmo_master_table();
$pmo_lbl = svc_pmo_pick_label_col($pmo);
    $sql = "
  SELECT 
    p.id, p.intitule, p.type_id, p.date_debut, p.date_fin, p.financement,
    p.source_financement_id, p.site_web_source, p.objectifs,
    p.id_pmo,
    DATE_FORMAT(p.date_debut, '%%d/%%m/%%Y') AS date_debut_fr,
    DATE_FORMAT(p.date_fin,   '%%d/%%m/%%Y') AS date_fin_fr,
    tp.{$type_lbl}  AS type_label,
    sf.{$src_lbl}   AS source_label,
    pm.{$pmo_lbl}   AS pmo_label
  FROM {$p} p
  LEFT JOIN {$t}  tp ON tp.id = p.type_id
  LEFT JOIN {$s}  sf ON sf.id = p.source_financement_id
  LEFT JOIN {$pmo} pm ON pm.id = p.id_pmo
  ORDER BY p.id DESC
  LIMIT %d OFFSET %d
";
    return $wpdb->get_results($wpdb->prepare($sql, $per, $off), ARRAY_A) ?: array();
  }
}

/* ---------- GET /projets/{id} ---------- */
if (!function_exists('svc_pmo_projet_get')) {
  function svc_pmo_projet_get(WP_REST_Request $req){
    $id = intval($req->get_param('id'));
    if (!$id) return new WP_Error('bad_request', 'ID manquant', array('status'=>400));
    $row = svc_pmo_projet_select_one($id);
    if (!$row) return new WP_Error('not_found', 'Projet introuvable', array('status'=>404));
    return $row;
  }
}


/* ---------- POST /projets ---------- */
if (!function_exists('svc_pmo_projet_create')) {
  function svc_pmo_projet_create(WP_REST_Request $req){
    global $wpdb;
    $p = svc_pmo_projet_table();
    $allowed = svc_pmo_allowed_fields();
    $data = $req->get_json_params(); if(!$data) $data = $req->get_params();

    $ins = array(); $fmts = array();
    foreach($allowed as $k=>$type){
      if(!array_key_exists($k,$data)) continue;
      $v = svc_pmo_sanitize($k,$data[$k],$type);
      if($v === null || $v === '') continue;
      $ins[$k] = $v; $fmts[] = svc_pmo_fmt($type);
    }
    if (empty($ins['intitule']))  return new WP_Error('bad_request','Le champ "intitule" est requis', array('status'=>400));
// --- set id_pmo si non fourni ---
$uid = get_current_user_id(); // s'assurer qu'il existe ici
if (empty($ins['id_pmo']) && svc_pmo_col_exists($p, 'id_pmo')) {
  $pmo_tbl = svc_pmo_master_table();
  $picked  = 0;

  // 1) PMO où l'utilisateur courant est directeur
  if (svc_pmo_col_exists($pmo_tbl, 'directeur_id')) {
    $picked = (int) $wpdb->get_var($wpdb->prepare(
      "SELECT id FROM {$pmo_tbl} WHERE directeur_id=%d ORDER BY id DESC LIMIT 1",
      $uid
    ));
  }

  // 2) sinon PMO créé par l'utilisateur courant
  if (!$picked && svc_pmo_col_exists($pmo_tbl, 'created_by')) {
    $picked = (int) $wpdb->get_var($wpdb->prepare(
      "SELECT id FROM {$pmo_tbl} WHERE created_by=%d ORDER BY id DESC LIMIT 1",
      $uid
    ));
  }

  // 3) sinon s'il n'y a qu'un seul PMO
  if (!$picked) {
    $cnt = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$pmo_tbl}");
    if ($cnt === 1) {
      $picked = (int) $wpdb->get_var("SELECT id FROM {$pmo_tbl} LIMIT 1");
    }
  }

  if ($picked) { $ins['id_pmo'] = $picked; $fmts[] = '%d'; }
}

    // audit
    $now = current_time('mysql'); $uid = get_current_user_id();
    if (svc_pmo_col_exists($p,'created_by')) { $ins['created_by'] = $uid; $fmts[]='%d'; }
    if (svc_pmo_col_exists($p,'updated_by')) { $ins['updated_by'] = $uid; $fmts[]='%d'; }
    if (svc_pmo_col_exists($p,'created_at')) { $ins['created_at'] = $now; $fmts[]='%s'; }
    if (svc_pmo_col_exists($p,'updated_at')) { $ins['updated_at'] = $now; $fmts[]='%s'; }

    $ok = $wpdb->insert($p, $ins, $fmts);
    if(!$ok) return new WP_Error('db_error','Insert failed: '.$wpdb->last_error, array('status'=>500));

    $id = (int)$wpdb->insert_id;
    $id = (int) $wpdb->insert_id;
$row = svc_pmo_projet_select_one($id);
return $row ?: array('id'=>$id); // au pire on renvoie l'id
  }
}
if (!function_exists('svc_pmo_projet_select_one')) {
 function svc_pmo_projet_select_one($id){
  global $wpdb;
  $p = svc_pmo_projet_table();
  $t = svc_recherche_type_projet_table();
  $s = svc_recherche_source_financement_table();
  $pmo = svc_pmo_master_table();

  $type_lbl = svc_pmo_pick_label_col($t);
  $src_lbl  = svc_pmo_pick_label_col($s);
  $pmo_lbl  = svc_pmo_pick_label_col($pmo);

  $sql = "
    SELECT 
      p.id, p.intitule, p.type_id, p.date_debut, p.date_fin, p.financement,
      p.source_financement_id, p.site_web_source, p.objectifs,
      p.id_pmo,
      DATE_FORMAT(p.date_debut, '%%d/%%m/%%Y') AS date_debut_fr,
      DATE_FORMAT(p.date_fin,   '%%d/%%m/%%Y') AS date_fin_fr,
      tp.{$type_lbl}  AS type_label,
      sf.{$src_lbl}   AS source_label,
      pm.{$pmo_lbl}   AS pmo_label
    FROM {$p} p
    LEFT JOIN {$t}  tp ON tp.id = p.type_id
    LEFT JOIN {$s}  sf ON sf.id = p.source_financement_id
    LEFT JOIN {$pmo} pm ON pm.id = p.id_pmo
    WHERE p.id = %d
    LIMIT 1
  ";
  return $wpdb->get_row($wpdb->prepare($sql, $id), ARRAY_A);
}

}

/* ---------- PUT/PATCH /projets/{id} ---------- */
if (!function_exists('svc_pmo_projet_update')) {
  function svc_pmo_projet_update(WP_REST_Request $req){
    global $wpdb;
    $p = svc_pmo_projet_table();
    $id = intval($req['id']);

    $cur = $wpdb->get_row($wpdb->prepare("SELECT id FROM {$p} WHERE id=%d", $id), ARRAY_A);
    if(!$cur) return new WP_Error('not_found','Projet introuvable', array('status'=>404));

    $allowed = svc_pmo_allowed_fields();
    $data = $req->get_json_params(); if(!$data) $data = $req->get_params();

    $upd = array(); $fmts = array();
    foreach($allowed as $k=>$type){
      if(!array_key_exists($k,$data)) continue;
      $v = svc_pmo_sanitize($k,$data[$k],$type);
      if($v === null) continue;
      $upd[$k] = $v; $fmts[] = svc_pmo_fmt($type);
    }
    if(empty($upd)) return new WP_Error('bad_request','Aucun champ valide', array('status'=>400));

    // audit
    if (svc_pmo_col_exists($p,'updated_by')) { $upd['updated_by'] = get_current_user_id(); $fmts[]='%d'; }
    if (svc_pmo_col_exists($p,'updated_at')) { $upd['updated_at'] = current_time('mysql'); $fmts[]='%s'; }

    $ok = $wpdb->update($p, $upd, array('id'=>$id), $fmts, array('%d'));
    if($ok===false) return new WP_Error('db_error','Update failed: '.$wpdb->last_error, array('status'=>500));

    return svc_pmo_projet_select_one($id);
 }
}







if (!defined('ABSPATH')) exit;

/* ------------ Tables helpers ------------ */
if (!function_exists('svc_pmo_team_table')) {
  function svc_pmo_team_table(){ global $wpdb; return $wpdb->prefix.'pmo_projet_equipe'; }
}
if (!function_exists('svc_pmo_phase_table')) {
  function svc_pmo_phase_table(){ global $wpdb; return $wpdb->prefix.'pmo_projet_phase'; }
}

/* ------------ Users list (chercheur + directeur) ------------ */
if (!function_exists('svc_pmo_users_for_project')) {
  function svc_pmo_users_for_project(WP_REST_Request $req){
    $roles  = $req->get_param('roles');
    $search = trim((string)$req->get_param('search'));
    $roles  = $roles ? array_map('trim', explode(',', $roles)) : array('um_chercheur','um_directeur_laboratoire');

    $args = array(
      'role__in' => $roles,
      'number'   => 200,
      'fields'   => array('ID','display_name','user_email'),
    );
    if ($search) $args['search'] = '*'.esc_attr($search).'*';

    $q = new WP_User_Query($args);
    $out = array();
    foreach ($q->get_results() as $u) {
      $first = get_user_meta($u->ID, 'first_name', true);
      $last  = get_user_meta($u->ID, 'last_name',  true);
      $orcid = get_user_meta($u->ID, 'orcid', true);
      if (!$orcid) $orcid = get_user_meta($u->ID, 'orcid_id', true);
      $full = trim(($first ?: '').' '.($last ?: '')) ?: $u->display_name;

      $out[] = array(
        'id'         => (int)$u->ID,
        'display'    => $full,
        'email'      => $u->user_email,
        'first_name' => $first ?: '',
        'last_name'  => $last ?: '',
        'orcid'      => $orcid ?: '',
      );
    }
    return $out;
  }
}

/* ------------ ÉQUIPE: GET (liste) ------------ */
if (!function_exists('svc_pmo_team_list')) {
  function svc_pmo_team_list(WP_REST_Request $req){
    global $wpdb;
    $te = svc_pmo_team_table();
    $pid = (int)$req['id'];

    $rows = $wpdb->get_results($wpdb->prepare("
      SELECT 
        e.id, e.projet_id, e.user_id,
        e.role_projet, e.grade, e.email, e.created_at,
        e.piece_jointe_path
      FROM {$te} e
      WHERE e.projet_id = %d
      ORDER BY e.id ASC
    ", $pid), ARRAY_A) ?: array();

    $uploads = wp_get_upload_dir();

    return array_map(function($r) use($uploads){
      // nom complet + orcid via helpers/usermeta
      $brief = function_exists('svc_user_brief') ? svc_user_brief((int)$r['user_id']) : null;
      $full  = $brief['full_name'] ?? null;
      if (!$full) {
        $fn = get_user_meta($r['user_id'], 'first_name', true);
        $ln = get_user_meta($r['user_id'], 'last_name',  true);
        $full = trim(($fn ?: '').' '.($ln ?: ''));
      }
      $orcid = get_user_meta($r['user_id'], 'orcid', true);
      if (!$orcid) $orcid = get_user_meta($r['user_id'], 'orcid_id', true);

      // grade fallback si null en BDD
      $grade = $r['grade'];
      if ($grade === null || $grade === '') {
        $grade = get_user_meta($r['user_id'], 'grade', true);
        if ($grade === '') $grade = get_user_meta($r['user_id'], 'utm_grade', true);
        if ($grade === '') $grade = get_user_meta($r['user_id'], 'user_grade', true);
      }

      // construire l'URL depuis le chemin disque
      $doc_url = null;
      if (!empty($r['piece_jointe_path'])) {
        $doc_url = str_replace($uploads['basedir'], $uploads['baseurl'], $r['piece_jointe_path']);
      }

      return array(
        'id'          => (int)$r['id'],
        'user_id'     => (int)$r['user_id'],
        'full_name'   => $full ?: null,
        'orcid'       => $orcid ?: null,
        'email'       => $r['email'] ?: ($brief['email'] ?? null),
        'role_projet' => $r['role_projet'] ?: null,
        'grade'       => $grade ?: null,
        'doc_url'     => $doc_url,
        'created_at'  => $r['created_at'],
      );
    }, $rows);
  }
}


/* ------------ ÉQUIPE: POST (create) ------------ */
if (!function_exists('svc_pmo_team_create')) {
  function svc_pmo_team_create(WP_REST_Request $req){
    global $wpdb;
    $te  = svc_pmo_team_table();
    $pid = (int)$req['id'];

    $p = $req->get_json_params(); if(!$p) $p = $req->get_params();
    $user_id = isset($p['user_id']) ? (int)$p['user_id'] : 0;
    $role    = sanitize_text_field($p['role_projet'] ?? 'membre');
    if (!$pid || !$user_id) return new WP_Error('bad_request','projet_id et user_id requis',['status'=>400]);

    // Snapshot utilisateur
    $u     = get_userdata($user_id);
    $email = $u ? $u->user_email : null;

    // grade depuis usermeta (+ fallbacks)
    $grade = get_user_meta($user_id, 'grade', true);
    if ($grade === '') $grade = get_user_meta($user_id, 'utm_grade', true);
    if ($grade === '') $grade = get_user_meta($user_id, 'user_grade', true);

    // Upload pièce jointe si présente
    $pj_path = null;
    if (!empty($_FILES['piece_jointe']) && is_uploaded_file($_FILES['piece_jointe']['tmp_name'])) {
      require_once ABSPATH.'wp-admin/includes/file.php';
      $over = array('test_form' => false);
      $m = wp_handle_upload($_FILES['piece_jointe'], $over);
      if (is_array($m) && empty($m['error'])) {
        $pj_path = $m['file']; // chemin absolu (disk)
      } else {
        return new WP_Error('upload_error', 'Upload échoué: '.($m['error'] ?? 'inconnu'), ['status'=>400]);
      }
    }

    $ok = $wpdb->insert($te, array(
      'projet_id'         => $pid,
      'user_id'           => $user_id,
      'role_projet'       => $role,
      'grade'             => $grade ?: null,
      'email'             => $email ?: null,
      'piece_jointe_path' => $pj_path,
      'created_at'        => current_time('mysql'),
    ), array('%d','%d','%s','%s','%s','%s','%s'));

    if ($ok === false) {
      return new WP_Error('db_error','Insert failed: '.$wpdb->last_error,['status'=>500]);
    }
    return array('id' => (int)$wpdb->insert_id);
  }
}


/* ------------ ÉQUIPE: PUT (update) ------------ */
if (!function_exists('svc_pmo_team_update')) {
  function svc_pmo_team_update(WP_REST_Request $req){
    global $wpdb;
    $te = svc_pmo_team_table();
    $mid = (int)$req['mid'];

    $p = $req->get_json_params(); if(!$p) $p = $req->get_params();
    $role = sanitize_text_field($p['role_projet'] ?? $p['role_projet'] ?? '');
    if ($role==='') return new WP_Error('bad_request','role_projet manquant',['status'=>400]);

    $ok = $wpdb->update($te, array('role_projet'=>$role), array('id'=>$mid), array('%s'), array('%d'));
    if ($ok === false) return new WP_Error('db_error','Update failed: '.$wpdb->last_error,['status'=>500]);

    return array('ok'=>true);
  }
}

/* ------------ ÉQUIPE: DELETE ------------ */
if (!function_exists('svc_pmo_team_delete')) {
  function svc_pmo_team_delete(WP_REST_Request $req){
    global $wpdb;
    $te = svc_pmo_team_table();
    $mid = (int)$req['mid'];
    $ok = $wpdb->delete($te, array('id'=>$mid), array('%d'));
    if ($ok === false) return new WP_Error('db_error','Delete failed: '.$wpdb->last_error,['status'=>500]);
    return array('deleted'=>(bool)$ok);
  }
}


/* ------------ PHASES: GET liste ------------ */
if (!function_exists('svc_pmo_phase_list')) {
  function svc_pmo_phase_list(WP_REST_Request $req){
    global $wpdb; $t = svc_pmo_phase_table();
    $projet_id = (int)$req->get_param('id');
    $rows = $wpdb->get_results($wpdb->prepare("
      SELECT id, projet_id, titre, etat, progression, position, created_at, updated_at
      FROM {$t} WHERE projet_id=%d ORDER BY position ASC, id ASC
    ", $projet_id), ARRAY_A) ?: array();
    return $rows;
  }
}

/* ------------ PHASES: POST create ------------ */
if (!function_exists('svc_pmo_phase_create')) {
  function svc_pmo_phase_create(WP_REST_Request $req){
    global $wpdb; $t = svc_pmo_phase_table();
    $projet_id = (int)$req->get_param('id');
    $data = $req->get_json_params(); if(!$data) $data = $req->get_params();

    $titre = isset($data['titre']) ? sanitize_text_field($data['titre']) : '';
    $etat  = isset($data['etat'])  ? sanitize_text_field($data['etat'])  : 'Prévu';
    $prog  = isset($data['progression']) ? max(0, min(100, (int)$data['progression'])) : 0;
    $pos   = isset($data['position']) ? (int)$data['position'] : 0;

    if(!$projet_id || !$titre) return new WP_Error('bad_request','projet_id et titre requis', array('status'=>400));
    if (!in_array($etat, array('Prévu','En cours','Terminé'), true)) $etat='Prévu';

    $now = current_time('mysql'); $uid=get_current_user_id();
    $ok = $wpdb->insert($t, array(
      'projet_id'=>$projet_id, 'titre'=>$titre, 'etat'=>$etat, 'progression'=>$prog, 'position'=>$pos,
      'created_at'=>$now, 'updated_at'=>$now, 'created_by'=>$uid, 'updated_by'=>$uid
    ), array('%d','%s','%s','%d','%d','%s','%s','%d','%d'));

    if(!$ok) return new WP_Error('db_error','Insert failed: '.$wpdb->last_error, array('status'=>500));
    $id = (int)$wpdb->insert_id;
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
  }
}

/* ------------ PHASES: PUT update ------------ */
if (!function_exists('svc_pmo_phase_update')) {
  function svc_pmo_phase_update(WP_REST_Request $req){
    global $wpdb; $t = svc_pmo_phase_table();
    $id = (int)$req->get_param('pid');
    $cur = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d",$id), ARRAY_A);
    if(!$cur) return new WP_Error('not_found','Introuvable', array('status'=>404));

    $data = $req->get_json_params(); if(!$data) $data = $req->get_params();
    $upd=array(); $fmt=array();
    if(isset($data['titre'])){ $upd['titre']=sanitize_text_field($data['titre']); $fmt[]='%s'; }
    if(isset($data['etat'])){ 
      $etat=sanitize_text_field($data['etat']);
      if(in_array($etat,array('Prévu','En cours','Terminé'), true)){ $upd['etat']=$etat; $fmt[]='%s'; }
    }
    if(isset($data['progression'])){ $upd['progression']=max(0,min(100,(int)$data['progression'])); $fmt[]='%d'; }
    if(isset($data['position'])){ $upd['position']=(int)$data['position']; $fmt[]='%d'; }

    if(empty($upd)) return new WP_Error('bad_request','Aucun champ', array('status'=>400));
    $upd['updated_at']=current_time('mysql'); $fmt[]='%s';
    $upd['updated_by']=get_current_user_id(); $fmt[]='%d';

    $ok = $wpdb->update($t, $upd, array('id'=>$id), $fmt, array('%d'));
    if($ok===false) return new WP_Error('db_error','Update failed: '.$wpdb->last_error, array('status'=>500));

    return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
  }
}

/* ------------ PHASES: DELETE ------------ */
if (!function_exists('svc_pmo_phase_delete')) {
  function svc_pmo_phase_delete(WP_REST_Request $req){
    global $wpdb; $t = svc_pmo_phase_table();
    $id = (int)$req->get_param('pid');
    $ok = $wpdb->delete($t, array('id'=>$id), array('%d'));
    if($ok===false) return new WP_Error('db_error','Delete failed: '.$wpdb->last_error, array('status'=>500));
    return array('deleted'=>(bool)$ok);
  }
}


/* ===== TÂCHES ===== */
if (!function_exists('svc_pmo_task_table')) {
  function svc_pmo_task_table(){ global $wpdb; return $wpdb->prefix.'pmo_projet_tache'; }
}

/* -- LISTE des tâches d’une phase -- */
if (!function_exists('svc_pmo_task_list')) {
  function svc_pmo_task_list(WP_REST_Request $req){
    global $wpdb;
    $t   = svc_pmo_task_table();
    $pid = (int)$req['pid']; // phase_id

    $rows = $wpdb->get_results($wpdb->prepare("
      SELECT id, phase_id, titre, membre_id, etat,
             date_debut, date_fin_prevu, date_limite, date_fin_reel,
             created_at, created_by, updated_at, updated_by
      FROM {$t}
      WHERE phase_id=%d
      ORDER BY id ASC
    ", $pid), ARRAY_A) ?: array();

    // enrichir avec le nom du membre
    return array_map(function($r){
      $name=''; $email='';
      if (!empty($r['membre_id'])) {
        $u = get_userdata((int)$r['membre_id']);
        if ($u) {
          $fn = get_user_meta($u->ID,'first_name',true);
          $ln = get_user_meta($u->ID,'last_name',true);
          $name = trim(($fn?:'').' '.($ln?:'')) ?: $u->display_name;
          $email = $u->user_email;
        }
      }
      $r['membre_nom']   = $name ?: null;
      $r['membre_email'] = $email ?: null;
      $r['id'] = (int)$r['id'];
      $r['phase_id'] = (int)$r['phase_id'];
      $r['membre_id'] = $r['membre_id'] ? (int)$r['membre_id'] : null;
      $r['created_by'] = $r['created_by'] ? (int)$r['created_by'] : null;
      $r['updated_by'] = $r['updated_by'] ? (int)$r['updated_by'] : null;
      return $r;
    }, $rows);
  }
}

/* -- GET 1 tâche -- */
if (!function_exists('svc_pmo_task_get')) {
  function svc_pmo_task_get(WP_REST_Request $req){
    global $wpdb; $t = svc_pmo_task_table();
    $tid = (int)$req['tid'];
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $tid), ARRAY_A);
    if (!$row) return new WP_Error('not_found','Tâche introuvable',['status'=>404]);
    return $row;
  }
}

/* -- CREATE -- */
if (!function_exists('svc_pmo_task_create')) {
  function svc_pmo_task_create(WP_REST_Request $req){
    global $wpdb; $t = svc_pmo_task_table();
    $pid = (int)$req['pid']; // phase_id
    $p   = $req->get_json_params(); if(!$p) $p = $req->get_params();

    $titre = sanitize_text_field($p['titre'] ?? '');
    if ($titre==='') return new WP_Error('bad_request','titre requis',['status'=>400]);

    $etat = sanitize_text_field($p['etat'] ?? 'Prévu');
    // normalisation
    if (strcasecmp($etat,'en cours')===0 || strcasecmp($etat,'en cours')===0) $etat='En cours';
    if (!in_array($etat, ['Prévu','À faire','En cours','Terminé','Annulé'], true)) $etat='Prévu';

    $membre_id = isset($p['membre_id']) ? (int)$p['membre_id'] : null;

    $rx = '/^\d{4}-\d{2}-\d{2}$/';
    $date_debut     = !empty($p['date_debut'])     && preg_match($rx,$p['date_debut'])     ? $p['date_debut']     : null;
    $date_fin_prev  = !empty($p['date_fin_prevu']) && preg_match($rx,$p['date_fin_prevu']) ? $p['date_fin_prevu'] : null;
    $date_limite    = !empty($p['date_limite'])    && preg_match($rx,$p['date_limite'])    ? $p['date_limite']    : null;

    $now = current_time('mysql'); $uid = get_current_user_id();

    $ok = $wpdb->insert($t, array(
      'phase_id'       => $pid,
      'titre'    => $titre,
      'membre_id'      => $membre_id,
      'etat'           => $etat,
      'date_debut'     => $date_debut,
      'date_fin_prevu' => $date_fin_prev,
      'date_limite'    => $date_limite,
      'created_at'     => $now,
      'created_by'     => $uid,
      'updated_at'     => $now,
      'updated_by'     => $uid,
    ), array('%d','%s','%d','%s','%s','%s','%s','%s','%d','%s','%d'));

    if ($ok===false) return new WP_Error('db_error','Insert failed: '.$wpdb->last_error,['status'=>500]);
    return array('id'=>(int)$wpdb->insert_id);
  }
}

/* -- UPDATE -- */
if (!function_exists('svc_pmo_task_update')) {
  function svc_pmo_task_update(WP_REST_Request $req){
    global $wpdb; $t = svc_pmo_task_table();
    $tid = (int)$req['tid'];
    $p   = $req->get_json_params(); if(!$p) $p = $req->get_params();

    $upd = array(); $fmt=array();

    if (isset($p['titre'])) { $upd['titre'] = sanitize_text_field($p['titre']); $fmt[]='%s'; }
    if (array_key_exists('membre_id',$p)) { $upd['membre_id'] = $p['membre_id']!==null ? (int)$p['membre_id'] : null; $fmt[]='%d'; }
    if (isset($p['etat'])) {
      $etat = sanitize_text_field($p['etat']);
      if (strcasecmp($etat,'en cours')===0) $etat='En cours';
      if (in_array($etat,['Prévu','À faire','En cours','Terminé','Annulé'],true)) { $upd['etat']=$etat; $fmt[]='%s'; }
      // si on passe à "Terminé" et pas de date_fin_reel fournie → on fixe aujourd’hui
      if ($etat==='Terminé' && empty($p['date_fin_reel'])) {
        $upd['date_fin_reel'] = current_time('Y-m-d'); $fmt[]='%s';
      }
    }

    $rx = '/^\d{4}-\d{2}-\d{2}$/';
    foreach (['date_debut','date_fin_prevu','date_limite','date_fin_reel'] as $k) {
      if (array_key_exists($k,$p)) {
        $upd[$k] = ($p[$k] && preg_match($rx,(string)$p[$k])) ? $p[$k] : null; $fmt[]='%s';
      }
    }

    if (empty($upd)) return new WP_Error('bad_request','Aucun champ',['status'=>400]);
    $upd['updated_at'] = current_time('mysql'); $fmt[]='%s';
    $upd['updated_by'] = get_current_user_id(); $fmt[]='%d';

    $ok = $wpdb->update($t, $upd, array('id'=>$tid), $fmt, array('%d'));
    if ($ok===false) return new WP_Error('db_error','Update failed: '.$wpdb->last_error,['status'=>500]);
    return array('ok'=>true);
  }
}

/* -- DELETE -- */
if (!function_exists('svc_pmo_task_delete')) {
  function svc_pmo_task_delete(WP_REST_Request $req){
    global $wpdb; $t = svc_pmo_task_table();
    $tid = (int)$req['tid'];
    $ok  = $wpdb->delete($t, array('id'=>$tid), array('%d'));
    if ($ok===false) return new WP_Error('db_error','Delete failed: '.$wpdb->last_error,['status'=>500]);
    return array('deleted'=>(bool)$ok);
  }
}
/* ------------ Tables helpers ------------ */
if (!function_exists('svc_pmo_pieces_table')) {
  function svc_pmo_pieces_table() { global $wpdb; return $wpdb->prefix . 'pmo_projet_pieces'; }
}

/* ------------ PIÈCES JOINTES: GET (liste pour un projet) ------------ */
if (!function_exists('svc_pmo_pieces_list')) {
  function svc_pmo_pieces_list(WP_REST_Request $req) {
  global $wpdb;
  $t = svc_pmo_pieces_table();
  $pid = (int)$req['id'];

  $rows = $wpdb->get_results($wpdb->prepare("
    SELECT id, projet_id, ref_doc, type_document, fichier_path, version, date, created_at
    FROM {$t} WHERE projet_id = %d ORDER BY id DESC
  ", $pid), ARRAY_A) ?: array();

  $uploads = wp_get_upload_dir();
  return array_map(function($r) use($uploads) {
    $r['doc_url'] = !empty($r['fichier_path']) ? str_replace($uploads['basedir'], $uploads['baseurl'], $r['fichier_path']) : null;
    $r['id'] = (int)$r['id'];
    $r['projet_id'] = (int)$r['projet_id'];
    $r['ref_doc'] = $r['ref_doc'] ?: null;
    $r['type_document'] = $r['type_document'] ?: null;
    $r['version'] = $r['version'] ?: null;
    $r['date'] = $r['date'] ?: null;
    return $r;
  }, $rows);
}
}

/* ------------ PIÈCES JOINTES: POST (create) ------------ */
if (!function_exists('svc_pmo_pieces_create')) {
 function svc_pmo_pieces_create(WP_REST_Request $req) {
  global $wpdb;
  $t = svc_pmo_pieces_table();
  $pid = (int)$req['id'];

  $p = $req->get_params(); // Utiliser get_params() pour FormData
  $type_doc = sanitize_text_field($p['type_document'] ?? '');
  $version = sanitize_text_field($p['version'] ?? '');

  if (!$pid || !$type_doc) {
    return new WP_Error('bad_request', 'projet_id et type_document requis', ['status' => 400]);
  }

  // Upload fichier si présent
  $fichier_path = null;
  if (!empty($_FILES['fichier']) && is_uploaded_file($_FILES['fichier']['tmp_name'])) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $over = ['test_form' => false];
    $m = wp_handle_upload($_FILES['fichier'], $over);
    if (is_array($m) && empty($m['error'])) {
      $fichier_path = $m['file']; // Chemin absolu
    } else {
      error_log('Upload échoué: ' . ($m['error'] ?? 'inconnu'));
      return new WP_Error('upload_error', 'Upload échoué: ' . ($m['error'] ?? 'inconnu'), ['status' => 400]);
    }
  }

  $now = current_time('mysql');
  $date = date('Y-m-d', strtotime($now)); // Date au format YYYY-MM-DD
  $uid = get_current_user_id();

  // Insérer d'abord pour obtenir l'ID
  $ok = $wpdb->insert($t, [
    'projet_id' => $pid,
    'ref_doc' => 'TEMP', // Placeholder temporaire
    'type_document' => $type_doc,
    'fichier_path' => $fichier_path,
    'version' => $version ?: null,
    'date' => $date,
    'created_at' => $now,
    'updated_at' => $now,
    'created_by' => $uid,
    'updated_by' => $uid,
  ], ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d']);

  if ($ok === false) {
    error_log('Insert failed in svc_pmo_pieces_create: ' . $wpdb->last_error);
    return new WP_Error('db_error', 'Insert failed: ' . $wpdb->last_error, ['status' => 500]);
  }

  // Générer ref_doc avec l'ID (par ex: DOC-0001)
  $id = (int)$wpdb->insert_id;
  $ref_doc = sprintf('DOC-%04d', $id); // Format DOC-XXXX
  $wpdb->update($t, ['ref_doc' => $ref_doc], ['id' => $id], ['%s'], ['%d']);

  // Récupérer l'enregistrement complet
  $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id=%d", $id), ARRAY_A);
  $uploads = wp_get_upload_dir();
  $row['doc_url'] = !empty($row['fichier_path']) ? str_replace($uploads['basedir'], $uploads['baseurl'], $row['fichier_path']) : null;
  return $row;
}
}

/* ------------ PIÈCES JOINTES: PUT (update) ------------ */
if (!function_exists('svc_pmo_pieces_update')) {
  function svc_pmo_pieces_update(WP_REST_Request $req) {
    global $wpdb;
    $t = svc_pmo_pieces_table();
    $piece_id = (int)$req['piece_id'];

    $p = $req->get_json_params(); if(!$p) $p = $req->get_params();
    $upd = []; $fmt = [];

    if (isset($p['type_document'])) { $upd['type_document'] = sanitize_text_field($p['type_document']); $fmt[] = '%s'; }
    if (isset($p['version'])) { $upd['version'] = sanitize_text_field($p['version']); $fmt[] = '%s'; }
    if (isset($p['ref_doc'])) { $upd['ref_doc'] = sanitize_text_field($p['ref_doc']); $fmt[] = '%s'; }
    if (isset($p['date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $p['date'])) { $upd['date'] = $p['date']; $fmt[] = '%s'; }

    // Upload nouveau fichier si présent (remplace l'ancien)
    if (!empty($_FILES['fichier']) && is_uploaded_file($_FILES['fichier']['tmp_name'])) {
      require_once ABSPATH . 'wp-admin/includes/file.php';
      $over = ['test_form' => false];
      $m = wp_handle_upload($_FILES['fichier'], $over);
      if (is_array($m) && empty($m['error'])) {
        $upd['fichier_path'] = $m['file'];
        $fmt[] = '%s';
      } else {
        return new WP_Error('upload_error', 'Upload échoué: ' . ($m['error'] ?? 'inconnu'), ['status'=>400]);
      }
    }

    if (empty($upd)) return new WP_Error('bad_request', 'Aucun champ valide', ['status'=>400]);

    $upd['updated_at'] = current_time('mysql'); $fmt[] = '%s';
    $upd['updated_by'] = get_current_user_id(); $fmt[] = '%d';

    $ok = $wpdb->update($t, $upd, ['id' => $piece_id], $fmt, ['%d']);
    if ($ok === false) return new WP_Error('db_error', 'Update failed: ' . $wpdb->last_error, ['status'=>500]);

    return ['ok' => true];
  }
}

/* ------------ PIÈCES JOINTES: DELETE ------------ */
if (!function_exists('svc_pmo_pieces_delete')) {
  function svc_pmo_pieces_delete(WP_REST_Request $req) {
    global $wpdb;
    $t = svc_pmo_pieces_table();
    $piece_id = (int)$req['piece_id'];
    $ok = $wpdb->delete($t, ['id' => $piece_id], ['%d']);
    if ($ok === false) return new WP_Error('db_error', 'Delete failed: ' . $wpdb->last_error, ['status'=>500]);
    return ['deleted' => (bool)$ok];
  }
}

/* ===== Services pour Budget (Rubriques budgétaires) ===== */

/* ---------- Helpers tables ---------- */
if (!function_exists('svc_pmo_budget_table')) {
  function svc_pmo_budget_table() { global $wpdb; return $wpdb->prefix . 'pmo_projet_budget'; }
}

/* ---------- Utils ---------- */
if (!function_exists('svc_pmo_budget_allowed_fields')) {
  function svc_pmo_budget_allowed_fields() {
    return array(
      'ref_code' => 'text',
      'rubrique' => 'text',
      'montant_max' => 'decimal',
      'montant_alloue' => 'decimal',
      'montant_total' => 'decimal',
      'fichier_justificatif' => 'text',
      'commentaire' => 'text',
    );
  }
}

if (!function_exists('svc_pmo_budget_sanitize')) {
  function svc_pmo_budget_sanitize($key, $val, $type) {
    if ($val === null) return null;
    switch ($type) {
      case 'decimal':
        return is_numeric($val) ? floatval($val) : null;
      case 'text':
        return is_scalar($val) ? sanitize_text_field($val) : null;
      default:
        return null;
    }
  }
}

if (!function_exists('svc_pmo_budget_fmt')) {
  function svc_pmo_budget_fmt($type) {
    return in_array($type, ['decimal']) ? '%f' : '%s';
  }
}

/* ---------- GET /projets/{id}/budgets (liste) ---------- */
if (!function_exists('svc_pmo_budget_list')) {
  function svc_pmo_budget_list(WP_REST_Request $req) {
    global $wpdb;
    $t = svc_pmo_budget_table();
    $pid = intval($req['id']);

    $rows = $wpdb->get_results($wpdb->prepare("
      SELECT id, projet_id, ref_code, rubrique, montant_max, montant_alloue, montant_total,
             fichier_justificatif, commentaire, created_at, updated_at
      FROM {$t}
      WHERE projet_id = %d
      ORDER BY id ASC
    ", $pid), ARRAY_A) ?: array();

    // Transformer fichier_justificatif en URL si présent
    $uploads = wp_get_upload_dir();
    return array_map(function($r) use($uploads) {
      $r['fichier_url'] = !empty($r['fichier_justificatif']) ? str_replace($uploads['basedir'], $uploads['baseurl'], $r['fichier_justificatif']) : null;
      $r['id'] = (int)$r['id'];
      $r['projet_id'] = (int)$r['projet_id'];
      $r['montant_max'] = (float)($r['montant_max'] ?? 0);
      $r['montant_alloue'] = (float)($r['montant_alloue'] ?? 0);
      $r['montant_total'] = (float)($r['montant_total'] ?? 0);
      return $r;
    }, $rows);
  }
}

/* ---------- GET /budgets/{id} ---------- */
if (!function_exists('svc_pmo_budget_get')) {
function svc_pmo_budget_get(WP_REST_Request $req) {
  global $wpdb;
  $t = svc_pmo_budget_table();
  $id = intval($req['id']);

  $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id = %d", $id), ARRAY_A);
  if (!$row) {
    return new WP_Error('not_found', 'Rubrique introuvable avec ID ' . $id, array('status' => 404));
  }

  $uploads = wp_get_upload_dir();
  $row['fichier_url'] = !empty($row['fichier_justificatif']) ? str_replace($uploads['basedir'], $uploads['baseurl'], $row['fichier_justificatif']) : null;
  $row['id'] = (int)$row['id'];
  $row['projet_id'] = (int)$row['projet_id'];
  $row['montant_max'] = (float)($row['montant_max'] ?? 0);
  $row['montant_alloue'] = (float)($row['montant_alloue'] ?? 0);
  $row['montant_total'] = (float)($row['montant_total'] ?? 0);

  return $row;
}
}

/* ---------- POST /projets/{id}/budgets (create) ---------- */
if (!function_exists('svc_pmo_budget_create')) {
  function svc_pmo_budget_create(WP_REST_Request $request) {
    if (!is_user_logged_in() || !current_user_can('administrator') && !current_user_can('um_pmo')) {
      return new WP_Error('rest_forbidden', 'Non autorisé', ['status' => 401]);
    }

    global $wpdb;
    $table = svc_pmo_budget_table();
    $projet_id = intval($request->get_param('id'));

    $rubrique = sanitize_text_field($request->get_param('rubrique'));
    $montant_max = floatval($request->get_param('montant_max'));
    $montant_alloue = floatval($request->get_param('montant_alloue')) ?: 0;

    if (!$rubrique || $montant_max < 0) {
      return new WP_Error('bad_request', 'Champs invalides', ['status' => 400]);
    }

    // Gestion du fichier (si fourni via FormData)
    $fichier_url = null;
    if (!empty($request->get_file_params()['fichier']['tmp_name'])) {
      $upload = wp_handle_upload($request->get_file_params()['fichier'], ['test_form' => false]);
      if (!isset($upload['error'])) {
        $fichier_url = $upload['url'];
      } else {
        return new WP_Error('upload_error', 'Échec de l\'upload : ' . $upload['error'], ['status' => 400]);
      }
    }

    // Insérer d'abord avec une référence temporaire
    $data = [
      'projet_id' => $projet_id,
      'ref_code' => 'TEMP', // Placeholder temporaire
      'rubrique' => $rubrique,
      'montant_max' => $montant_max,
      'montant_alloue' => $montant_alloue,
      'fichier_justificatif' => $fichier_url ?: null,
      'created_at' => current_time('mysql'),
    ];
    $fmt = ['%d', '%s', '%s', '%f', '%f', '%s', '%s'];

    $result = $wpdb->insert($table, $data, $fmt);
    if ($result === false) {
      return new WP_Error('db_error', 'Erreur insertion : ' . $wpdb->last_error, ['status' => 500]);
    }

    // Générer ref_code avec l'ID (exemple : BUD-YYYYMMDD-XXXX)
    $id = (int)$wpdb->insert_id;
    $date = date('Ymd', strtotime(current_time('mysql'))); // Format YYYYMMDD
    $ref_code = "BUD-{$date}-" . str_pad($id, 4, '0', STR_PAD_LEFT); // Format BUD-YYYYMMDD-XXXX
    $wpdb->update($table, ['ref_code' => $ref_code], ['id' => $id], ['%s'], ['%d']);

    // Récupérer l'enregistrement complet avec la nouvelle référence
$r = new WP_REST_Request('GET');
$r->set_param('id', $id);
return svc_pmo_budget_get($r);
  }
}

/* ---------- PUT/PATCH /budgets/{id} (update) ---------- */
if (!function_exists('svc_pmo_budget_update')) {
  function svc_pmo_budget_update(WP_REST_Request $req) {
  global $wpdb;
  $t = svc_pmo_budget_table();
  $id = intval($req['id']);

  $cur = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id = %d", $id), ARRAY_A);
  if (!$cur) return new WP_Error('not_found', 'Rubrique introuvable', array('status' => 404));

  $p = $req->get_params();
  $upd = array(); $fmts = array();

  if (isset($p['rubrique'])) { $upd['rubrique'] = sanitize_text_field($p['rubrique']); $fmts[] = '%s'; }
  if (isset($p['montant_max'])) { $upd['montant_max'] = floatval($p['montant_max']); $fmts[] = '%f'; }
  if (isset($p['commentaire'])) { $upd['commentaire'] = sanitize_textarea_field($p['commentaire']); $fmts[] = '%s'; }

  // Fichier via $req->get_file_params()
  $files = $req->get_file_params();
  if (!empty($files['fichier']) && !empty($files['fichier']['tmp_name'])) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $upload = wp_handle_upload($files['fichier'], array('test_form' => false));
    if (is_array($upload) && empty($upload['error'])) {
      // stocker le chemin disque (cohérent avec list/get où tu fais basedir->baseurl)
      $upd['fichier_justificatif'] = $upload['file'];
      $fmts[] = '%s';
    } else {
      return new WP_Error('upload_error', 'Upload échoué: ' . ($upload['error'] ?? 'inconnu'), array('status' => 400));
    }
  }

  if (empty($upd)) return new WP_Error('bad_request', 'Aucun champ valide', array('status' => 400));

  $upd['updated_at'] = current_time('mysql');
  $upd['updated_by'] = get_current_user_id();
  $fmts[] = '%s'; $fmts[] = '%d';

  $ok = $wpdb->update($t, $upd, array('id' => $id), $fmts, array('%d'));
  if ($ok === false) return new WP_Error('db_error', 'Update failed: '.$wpdb->last_error, array('status'=>500));

  // renvoyer l’objet à jour
$r = new WP_REST_Request('GET');
$r->set_param('id', $id);
return svc_pmo_budget_get($r);
}

}

/* ---------- DELETE /budgets/{id} ---------- */
if (!function_exists('svc_pmo_budget_delete')) {
  function svc_pmo_budget_delete(WP_REST_Request $req) {
    global $wpdb;
    $t = svc_pmo_budget_table();
    $id = intval($req['id']);

    // Vérifier si des dépenses utilisent cette rubrique
    $depense_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}pmo_projet_depenses WHERE budget_id = %d", $id));
    if ($depense_count > 0) {
      return new WP_Error('dependent_records', 'Impossible de supprimer : des dépenses utilisent cette rubrique', array('status' => 400));
    }

    $ok = $wpdb->delete($t, array('id' => $id), array('%d'));
    if ($ok === false) return new WP_Error('db_error', 'Delete failed: ' . $wpdb->last_error, array('status' => 500));

    return array('deleted' => true);
  }
}

/* ===== Services pour Dépenses ===== */

/* ---------- Helpers tables ---------- */
if (!function_exists('svc_pmo_depense_table')) {
  function svc_pmo_depense_table() { global $wpdb; return $wpdb->prefix . 'pmo_projet_depenses'; }
}

/* ---------- Utils ---------- */
if (!function_exists('svc_pmo_depense_allowed_fields')) {
  function svc_pmo_depense_allowed_fields() {
    return array(
      'ref' => 'text',
      'designation' => 'text',
      'montant' => 'decimal',
      'date_depense' => 'date',
      'piece_jointe' => 'text',
    );
  }
}

if (!function_exists('svc_pmo_depense_sanitize')) {
  function svc_pmo_depense_sanitize($key, $val, $type) {
    if ($val === null) return null;
    switch ($type) {
      case 'decimal':
        return is_numeric($val) ? floatval($val) : null;
      case 'date':
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$val) ? $val : null;
      case 'text':
        return is_scalar($val) ? sanitize_text_field($val) : null;
      default:
        return null;
    }
  }
}

if (!function_exists('svc_pmo_depense_fmt')) {
  function svc_pmo_depense_fmt($type) {
    return in_array($type, ['decimal']) ? '%f' : '%s';
  }
}

/* ---------- GET /projets/{id}/depenses (liste) ---------- */
if (!function_exists('svc_pmo_depense_list')) {
  function svc_pmo_depense_list(WP_REST_Request $req) {
    global $wpdb;
    $dep_t = svc_pmo_depense_table();
    $bud_t = svc_pmo_budget_table();
    $pid = intval($req['id']);

    $rows = $wpdb->get_results($wpdb->prepare("
      SELECT d.id, d.projet_id, d.budget_id, d.ref, d.designation, d.montant, d.date_depense,
             d.piece_jointe, d.created_at, d.updated_at,
             b.rubrique AS budget_rubrique, b.montant_max AS budget_max
      FROM {$dep_t} d
      LEFT JOIN {$bud_t} b ON b.id = d.budget_id
      WHERE d.projet_id = %d
      ORDER BY d.id ASC
    ", $pid), ARRAY_A) ?: array();

    // Transformer piece_jointe en URL si présent
    $uploads = wp_get_upload_dir();
    return array_map(function($r) use($uploads) {
      $r['piece_url'] = !empty($r['piece_jointe']) ? str_replace($uploads['basedir'], $uploads['baseurl'], $r['piece_jointe']) : null;
      $r['id'] = (int)$r['id'];
      $r['projet_id'] = (int)$r['projet_id'];
      $r['budget_id'] = (int)$r['budget_id'];
      $r['montant'] = (float)($r['montant'] ?? 0);
      $r['budget_max'] = (float)($r['budget_max'] ?? 0);
      return $r;
    }, $rows);
  }
}

/* ---------- GET /depenses/{id} ---------- */
if (!function_exists('svc_pmo_depense_get')) {
  function svc_pmo_depense_get(WP_REST_Request $req) {
    global $wpdb;
    $t = svc_pmo_depense_table();
    $id = intval($req['id']);

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE id = %d", $id), ARRAY_A);
    if (!$row) return new WP_Error('not_found', 'Dépense introuvable', array('status' => 404));

    $uploads = wp_get_upload_dir();
    $row['piece_url'] = !empty($row['piece_jointe']) ? str_replace($uploads['basedir'], $uploads['baseurl'], $row['piece_jointe']) : null;
    $row['id'] = (int)$row['id'];
    $row['projet_id'] = (int)$row['projet_id'];
    $row['budget_id'] = (int)$row['budget_id'];
    $row['montant'] = (float)($row['montant'] ?? 0);

    return $row;
  }
}
/* ---------- POST /projets/{id}/depenses (create) ---------- */
/* ---------- POST /projets/{id}/depenses (create) ---------- */
if (!function_exists('svc_pmo_depense_create')) {
  function svc_pmo_depense_create(WP_REST_Request $req) {
    if (!is_user_logged_in() || !(current_user_can('administrator') || current_user_can('um_pmo'))) {
      return new WP_Error('rest_forbidden', 'Non autorisé', ['status' => 401]);
    }

    global $wpdb;
    $dep_t = svc_pmo_depense_table();
    $bud_t = svc_pmo_budget_table();

    $projet_id   = (int) $req['id'];
    $budget_id   = (int) $req->get_param('budget_id');
    $designation = sanitize_textarea_field($req->get_param('designation') ?? '');
    $montant     = (float) $req->get_param('montant');
    $date_dep    = $req->get_param('date_depense');

    if (!$projet_id || !$budget_id || $montant <= 0 || $designation === '') {
      return new WP_Error('bad_request', 'Champs manquants ou invalides', ['status' => 400]);
    }

    // Upload fichier (optionnel) — on enregistre le CHEMIN DISQUE
    $piece_path = null;
    $files = $req->get_file_params();
    if (!empty($files['piece_jointe']['tmp_name'])) {
      require_once ABSPATH . 'wp-admin/includes/file.php';
      $u = wp_handle_upload($files['piece_jointe'], ['test_form' => false]);
      if (is_array($u) && empty($u['error'])) {
        $piece_path = $u['file'];  // <-- chemin disque, cohérent avec list/get
      } else {
        return new WP_Error('upload_error', 'Upload échoué: ' . ($u['error'] ?? 'inconnu'), ['status' => 400]);
      }
    }

    // Insert (ref temporaire)
    $ok = $wpdb->insert($dep_t, [
      'projet_id'    => $projet_id,
      'budget_id'    => $budget_id,
      'ref'          => 'TEMP',
      'designation'  => $designation,
      'montant'      => $montant,
      'date_depense' => $date_dep ?: current_time('Y-m-d'),
      'piece_jointe' => $piece_path,
      'created_at'   => current_time('mysql'),
      'created_by'   => get_current_user_id(),
    ], ['%d','%d','%s','%s','%f','%s','%s','%s']);

    if ($ok === false) {
      return new WP_Error('db_error', 'Insert failed: '.$wpdb->last_error, ['status'=>500]);
    }

    $new_id = (int) $wpdb->insert_id;
    // Générer une ref lisible
    $ref = 'DEP-' . date('Ymd', strtotime(current_time('mysql'))) . '-' . str_pad($new_id, 4, '0', STR_PAD_LEFT);
    $wpdb->update($dep_t, ['ref' => $ref], ['id' => $new_id], ['%s'], ['%d']);

    // Mettre à jour montant_alloue de la rubrique
    $wpdb->query($wpdb->prepare("UPDATE {$bud_t} SET montant_alloue = COALESCE(montant_alloue,0) + %f WHERE id = %d", $montant, $budget_id));

    // Retourner la dépense créée
    $r = new WP_REST_Request('GET');
$r->set_param('id', $new_id);   // ou $id
return svc_pmo_depense_get($r);
 }
}


/* ---------- PUT/PATCH /depenses/{id} (update) ---------- */
if (!function_exists('svc_pmo_depense_update')) {
  function svc_pmo_depense_update(WP_REST_Request $req) {
    global $wpdb;
    $dep_t = svc_pmo_depense_table();
    $bud_t = svc_pmo_budget_table();
    $id = intval($req['id']);

    $cur = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$dep_t} WHERE id = %d", $id), ARRAY_A);
    if (!$cur) return new WP_Error('not_found', 'Dépense introuvable', array('status' => 404));

    $p = $req->get_params();
    $allowed = svc_pmo_depense_allowed_fields();
    $upd = array();
    $fmts = array();

    // Champs modifiables
    if (isset($p['budget_id'])) {
      $upd['budget_id'] = intval($p['budget_id']);
      $fmts[] = '%d';
    }
    if (isset($p['designation'])) {
      $upd['designation'] = sanitize_textarea_field($p['designation']);
      $fmts[] = '%s';
    }
    if (isset($p['montant'])) {
      $upd['montant'] = floatval($p['montant']);
      $fmts[] = '%f';
    }
    if (isset($p['date_depense'])) {
      $date_depense = svc_pmo_depense_sanitize('date_depense', $p['date_depense'], 'date');
      if ($date_depense) {
        $upd['date_depense'] = $date_depense;
        $fmts[] = '%s';
      }
    }

    // Upload nouveau fichier si présent
    if (!empty($_FILES['piece_jointe']) && is_uploaded_file($_FILES['piece_jointe']['tmp_name'])) {
      require_once ABSPATH . 'wp-admin/includes/file.php';
      $over = array('test_form' => false);
      $m = wp_handle_upload($_FILES['piece_jointe'], $over);
      if (is_array($m) && empty($m['error'])) {
        $upd['piece_jointe'] = $m['file'];
        $fmts[] = '%s';
      } else {
        return new WP_Error('upload_error', 'Upload échoué: ' . ($m['error'] ?? 'inconnu'), array('status' => 400));
      }
    }

    if (empty($upd)) return new WP_Error('bad_request', 'Aucun champ valide', array('status' => 400));

    // Si montant changé, ajuster le budget précédent et nouveau
    if (isset($upd['montant']) || isset($upd['budget_id'])) {
      $old_montant = $cur['montant'];
      $old_budget_id = $cur['budget_id'];
      $new_montant = $upd['montant'] ?? $old_montant;
      $new_budget_id = $upd['budget_id'] ?? $old_budget_id;

      if ($old_budget_id != $new_budget_id) {
        // Ajuster ancien budget
        $wpdb->query($wpdb->prepare("UPDATE {$bud_t} SET montant_alloue = montant_alloue - %f WHERE id = %d", $old_montant, $old_budget_id));
        // Ajouter au nouveau budget
        $wpdb->query($wpdb->prepare("UPDATE {$bud_t} SET montant_alloue = montant_alloue + %f WHERE id = %d", $new_montant, $new_budget_id));
      } else {
        // Même budget, ajuster la différence
        $diff = $new_montant - $old_montant;
        $wpdb->query($wpdb->prepare("UPDATE {$bud_t} SET montant_alloue = montant_alloue + %f WHERE id = %d", $diff, $old_budget_id));
      }
    }

    // Audit
    $upd['updated_at'] = current_time('mysql');
    $upd['updated_by'] = get_current_user_id();
    $fmts[] = '%s'; $fmts[] = '%d';

    $ok = $wpdb->update($dep_t, $upd, array('id' => $id), $fmts, array('%d'));
    if ($ok === false) return new WP_Error('db_error', 'Update failed: ' . $wpdb->last_error, array('status' => 500));


// ✅ correct
$r = new WP_REST_Request('GET');
$r->set_param('id', $id);
return svc_pmo_depense_get($r);  }
}

/* ---------- DELETE /depenses/{id} ---------- */
if (!function_exists('svc_pmo_depense_delete')) {
  function svc_pmo_depense_delete(WP_REST_Request $req) {
    global $wpdb;
    $dep_t = svc_pmo_depense_table();
    $id = intval($req['id']);

    // Récupérer la dépense pour ajuster le budget
    $depense = $wpdb->get_row($wpdb->prepare("SELECT montant, budget_id FROM {$dep_t} WHERE id = %d", $id), ARRAY_A);
    if (!$depense) return new WP_Error('not_found', 'Dépense introuvable', array('status' => 400));

    $ok = $wpdb->delete($dep_t, array('id' => $id), array('%d'));
    if ($ok === false) return new WP_Error('db_error', 'Delete failed: ' . $wpdb->last_error, array('status' => 500));

    // Ajuster le budget (retirer le montant dépensé)
    $wpdb->query($wpdb->prepare("UPDATE " . svc_pmo_budget_table() . " SET montant_alloue = montant_alloue - %f WHERE id = %d", $depense['montant'], $depense['budget_id']));

    return array('deleted' => true);
  }
}