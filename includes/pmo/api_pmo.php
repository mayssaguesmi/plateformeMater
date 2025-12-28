<?php
/**
 * Routes REST pour Presentation
 */

if (!defined('ABSPATH')) exit;

$plugin_root = dirname(__DIR__, 2); // remonte de .../includes/pmo => .../plateforme-master
require_once $plugin_root . '/services/pmo/services_pmo.php';

add_action('rest_api_init', function () {
  // /presentation : liste + création
  register_rest_route('plateforme-recherche/v1', '/presentation', array(
    array(
      'methods'  => WP_REST_Server::READABLE,   // GET
      'callback' => 'svc_presentation_list',
      'permission_callback' => function() { return is_user_logged_in(); },
      'args' => array(
        'page'     => array('required' => false, 'type' => 'integer'),
        'per_page' => array('required' => false, 'type' => 'integer'),
      ),
    ),
    array(
      'methods'  => WP_REST_Server::CREATABLE,  // POST
      'callback' => 'svc_presentation_create',
      'permission_callback' => function() { return is_user_logged_in(); },
    ),
  ));

  // /presentation/{id} : lecture + mise à jour
  register_rest_route('plateforme-recherche/v1', '/presentation/(?P<id>\d+)', array(
    array(
      'methods'  => WP_REST_Server::READABLE,    // GET /{id}
      'callback' => 'svc_presentation_get',
      'permission_callback' => function() { return is_user_logged_in(); },
    ),
    array(
      'methods'  => 'PUT, PATCH',                // PUT/PATCH /{id}
      'callback' => 'svc_presentation_update',
      'permission_callback' => function() { return is_user_logged_in(); },
    ),
  ));
});

/** Routes REST pour PMO (Projets) */
if (!defined('ABSPATH')) { exit; }

add_action('rest_api_init', function () {

  /* ===== Projets ===== */
  register_rest_route('plateforme-pmo/v1', '/projets', array(
    array(
      'methods'  => WP_REST_Server::READABLE, // GET
      'callback' => 'svc_pmo_projet_list',
      'permission_callback' => function(){ return is_user_logged_in(); },
      'args' => array(
        'page'     => array('required'=>false,'type'=>'integer'),
        'per_page' => array('required'=>false,'type'=>'integer'),
      ),
    ),
    array(
      'methods'  => WP_REST_Server::CREATABLE, // POST
      'callback' => 'svc_pmo_projet_create',
      'permission_callback' => function(){
        return is_user_logged_in() && ( current_user_can('administrator') || current_user_can('um_pmo') );
      },
    ),
  ));

  register_rest_route('plateforme-pmo/v1', '/projets/(?P<id>\d+)', array(
    array(
      'methods'  => WP_REST_Server::READABLE, // GET /{id}
      'callback' => 'svc_pmo_projet_get',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => 'PUT, PATCH', // PUT/PATCH /{id}
      'callback' => 'svc_pmo_projet_update',
      'permission_callback' => function(){
        return is_user_logged_in() && ( current_user_can('administrator') || current_user_can('um_pmo') );
      },
    ),
  ));

  /* ===== Listes déroulantes ===== */
  register_rest_route('plateforme-pmo/v1', '/projet-types', array(
    array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'svc_pmo_list_types',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));
  register_rest_route('plateforme-pmo/v1', '/projet-sources', array(
    array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'svc_pmo_list_sources',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));
});

if (!defined('ABSPATH')) exit;

add_action('rest_api_init', function(){

  // Liste utilisateurs éligibles (chercheur + directeur)
  register_rest_route('plateforme-pmo/v1','/projet-users', array(
    array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'svc_pmo_users_for_project',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));

  // ÉQUIPE
  register_rest_route('plateforme-pmo/v1','/projets/(?P<id>\d+)/membres', array(
    array(
      'methods'  => WP_REST_Server::READABLE, // GET liste
      'callback' => 'svc_pmo_team_list',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::CREATABLE, // POST
      'callback' => 'svc_pmo_team_create',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));
  register_rest_route('plateforme-pmo/v1','/projets/(?P<id>\d+)/membres/(?P<mid>\d+)', array(
    array(
      'methods'  => 'PUT, PATCH',
      'callback' => 'svc_pmo_team_update',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::DELETABLE,
      'callback' => 'svc_pmo_team_delete',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));

  // PHASES
  register_rest_route('plateforme-pmo/v1','/projets/(?P<id>\d+)/phases', array(
    array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'svc_pmo_phase_list',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::CREATABLE,
      'callback' => 'svc_pmo_phase_create',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));
  register_rest_route('plateforme-pmo/v1','/projets/(?P<id>\d+)/phases/(?P<pid>\d+)', array(
    array(
      'methods'  => 'PUT, PATCH',
      'callback' => 'svc_pmo_phase_update',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::DELETABLE,
      'callback' => 'svc_pmo_phase_delete',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));

  // TÂCHES par phase
  register_rest_route('plateforme-pmo/v1','/projets/(?P<id>\d+)/phases/(?P<pid>\d+)/taches', array(
    array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'svc_pmo_task_list',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::CREATABLE,
      'callback' => 'svc_pmo_task_create',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));
  register_rest_route('plateforme-pmo/v1','/projets/(?P<id>\d+)/phases/(?P<pid>\d+)/taches/(?P<tid>\d+)', array(
    array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'svc_pmo_task_get',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => 'PUT, PATCH',
      'callback' => 'svc_pmo_task_update',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::DELETABLE,
      'callback' => 'svc_pmo_task_delete',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));

  // ===== PIÈCES JOINTES =====
  // Liste et création pour un projet
  register_rest_route('plateforme-pmo/v1', '/projets/(?P<id>\d+)/pieces', array(
    array(
      'methods'  => WP_REST_Server::READABLE, // GET liste
      'callback' => 'svc_pmo_pieces_list',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::CREATABLE, // POST création
      'callback' => 'svc_pmo_pieces_create',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));
  // Mise à jour et suppression pour une pièce spécifique
  register_rest_route('plateforme-pmo/v1', '/projets/(?P<id>\d+)/pieces/(?P<piece_id>\d+)', array(
    array(
      'methods'  => 'PUT, PATCH',
      'callback' => 'svc_pmo_pieces_update',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::DELETABLE,
      'callback' => 'svc_pmo_pieces_delete',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
  ));
  // ===== FIN PIÈCES JOINTES =====

  // ===== RUBRIQUES BUDGÉTAIRES =====
  register_rest_route('plateforme-pmo/v1', '/projets/(?P<id>\d+)/budgets', array(
    array(
      'methods'  => WP_REST_Server::READABLE, // GET - Liste des rubriques pour un projet
      'callback' => 'svc_pmo_budget_list',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => WP_REST_Server::CREATABLE, // POST - Ajouter une rubrique
      'callback' => 'svc_pmo_budget_create',
      'permission_callback' => function(){ return is_user_logged_in() && (current_user_can('administrator') || current_user_can('um_pmo')); },
    ),
  ));

  register_rest_route('plateforme-pmo/v1', '/budgets/(?P<id>\d+)', array(
    array(
      'methods'  => WP_REST_Server::READABLE, // GET - Une rubrique spécifique
      'callback' => 'svc_pmo_budget_get',
      'permission_callback' => function(){ return is_user_logged_in(); },
    ),
    array(
      'methods'  => 'PUT, PATCH', // PUT/PATCH - Modifier une rubrique
      'callback' => 'svc_pmo_budget_update',
      'permission_callback' => function(){ return is_user_logged_in() && (current_user_can('administrator') || current_user_can('um_pmo')); },
    ),
    array(
      'methods'  => WP_REST_Server::DELETABLE, // DELETE - Supprimer une rubrique
      'callback' => 'svc_pmo_budget_delete',
      'permission_callback' => function(){ return is_user_logged_in() && (current_user_can('administrator') || current_user_can('um_pmo')); },
    ),
  ));

  // ===== DÉPENSES =====
 register_rest_route('plateforme-pmo/v1', '/projets/(?P<id>\d+)/depenses', [
  [
    'methods'  => WP_REST_Server::READABLE, // GET liste
    'callback' => 'svc_pmo_depense_list',
    'permission_callback' => function(){ return is_user_logged_in(); },
  ],
  [
    'methods'  => WP_REST_Server::CREATABLE, // POST create (multipart OK)
    'callback' => 'svc_pmo_depense_create',
    'permission_callback' => function(){
      return is_user_logged_in() && ( current_user_can('administrator') || current_user_can('um_pmo') );
    },
  ],
]);
if (!function_exists('svc_pmo_can_edit')) {
  function svc_pmo_can_edit() {
    return is_user_logged_in() && ( current_user_can('administrator') || current_user_can('um_pmo') );
  }
}

  register_rest_route('plateforme-pmo/v1', '/depenses/(?P<id>\d+)', [
  [
    'methods'  => WP_REST_Server::READABLE, // GET une dépense
    'callback' => 'svc_pmo_depense_get',
    'permission_callback' => function(){ return is_user_logged_in(); },
  ],
  [
    'methods'  => 'PUT',                     // update JSON
    'callback' => 'svc_pmo_depense_update',
    'permission_callback' => 'svc_pmo_can_edit',
  ],
  [
    'methods'  => 'POST',                    // update via POST (multipart + _method=PUT)
    'callback' => 'svc_pmo_depense_update',
    'permission_callback' => 'svc_pmo_can_edit',
  ],
  [
    'methods'  => WP_REST_Server::DELETABLE, // DELETE
    'callback' => 'svc_pmo_depense_delete',
    'permission_callback' => 'svc_pmo_can_edit',
  ],
]);


  register_rest_route('plateforme-pmo/v1', '/budgets/(?P<id>\d+)', array(
  array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'svc_pmo_budget_get',
    'permission_callback' => function(){ return is_user_logged_in(); },
  ),
  array(
    'methods'  => 'PUT, PATCH, POST', // <--- AJOUT DE POST
    'callback' => 'svc_pmo_budget_update',
    'permission_callback' => function(){
      return is_user_logged_in() && (current_user_can('administrator') || current_user_can('um_pmo'));
    },
  ),
  array(
    'methods'  => WP_REST_Server::DELETABLE,
    'callback' => 'svc_pmo_budget_delete',
    'permission_callback' => function(){
      return is_user_logged_in() && (current_user_can('administrator') || current_user_can('um_pmo'));
    },
  ),
));

  // ===== FIN DÉPENSES =====
});

function pmo_add_membre( WP_REST_Request $req ) {
  if ( ! current_user_can('read') ) {
    return new WP_Error('rest_forbidden', 'Non autorisé', ['status'=>401]);
  }

  global $wpdb;
  $table = $wpdb->prefix . 'pmo_projet_equipe'; // => utm_pmo_projet_equipe

  $project_id = (int) $req['id'];
  $p          = $req->get_json_params();

  $user_id = isset($p['user_id']) ? (int) $p['user_id'] : 0;
  // on accepte role_projet OU role_dans_projet
  $role = sanitize_text_field( $p['role_projet'] ?? $p['role_dans_projet'] ?? '' );

  if (!$project_id || !$user_id || $role==='') {
    return new WP_Error('bad_request', 'Champs manquants', ['status'=>400]);
  }

  $user  = get_userdata($user_id);
  $email = $user ? $user->user_email : null;
  $grade = get_user_meta($user_id, 'grade', true);

  // ⚠️ PAS de 'orcid' dans l'insert (il n'y a pas de colonne)
  $data = [
    'projet_id'        => $project_id,
    'user_id'          => $user_id,
    'role_dans_projet' => $role,
    'grade'            => $grade ?: null,
    'email'            => $email ?: null,
    'created_at'       => current_time('mysql'),
  ];
  $fmt  = ['%d','%d','%s','%s','%s','%s'];

  $ok = $wpdb->insert($table, $data, $fmt);
  if ($ok === false) {
    return new WP_Error('db_error', 'Insert failed: '.$wpdb->last_error, ['status'=>500]);
  }

  return ['id' => (int) $wpdb->insert_id];
}

function pmo_list_membres( WP_REST_Request $req ) {
  global $wpdb;
  $project_id = (int) $req['id'];
  $t = $wpdb->prefix.'pmo_projet_equipe';

  $sql = $wpdb->prepare("
    SELECT 
      e.id,
      e.projet_id,
      e.user_id,
      e.role_dans_projet AS role_projet,           -- alias pour le front
      e.email,
      u.user_email AS wp_email,
      CONCAT(COALESCE(fn.meta_value,''),' ',COALESCE(ln.meta_value,'')) AS full_name,
      orc.meta_value AS orcid
    FROM $t e
    LEFT JOIN {$wpdb->users} u         ON u.ID = e.user_id
    LEFT JOIN {$wpdb->usermeta} fn     ON fn.user_id = e.user_id AND fn.meta_key = 'first_name'
    LEFT JOIN {$wpdb->usermeta} ln     ON ln.user_id = e.user_id AND ln.meta_key = 'last_name'
    LEFT JOIN {$wpdb->usermeta} orc    ON orc.user_id = e.user_id AND orc.meta_key = 'orcid'
    WHERE e.projet_id = %d
    ORDER BY e.id ASC
  ", $project_id);

  $rows = $wpdb->get_results($sql, ARRAY_A) ?: [];
  return array_map(function($r){
    return [
      'id'          => (int)$r['id'],
      'user_id'     => (int)$r['user_id'],
      'full_name'   => trim($r['full_name']) ?: null,
      'orcid'       => $r['orcid'] ?: null,                    // ← récupéré via usermeta
      'email'       => $r['email'] ?: ($r['wp_email'] ?? null),
      'role_projet' => $r['role_projet'] ?? null,
    ];
  }, $rows);
}

function pmo_update_membre( WP_REST_Request $req ) {
  global $wpdb;
  $project_id = (int) $req['id'];
  $mid        = (int) $req['mid'];
  $p          = $req->get_json_params();

  $role = sanitize_text_field( $p['role_projet'] ?? $p['role_dans_projet'] ?? '' );
  if ($role === '') {
    return new WP_Error('bad_request', 'role_projet manquant', ['status'=>400]);
  }

  $ok = $wpdb->update(
    $wpdb->prefix.'pmo_projet_equipe',
    ['role_dans_projet' => $role],
    ['id'=>$mid, 'projet_id'=>$project_id],
    ['%s'], ['%d','%d']
  );
  if ($ok === false) {
    return new WP_Error('db_error', 'Update failed: '.$wpdb->last_error, ['status'=>500]);
  }
  return ['ok'=>true];
}

