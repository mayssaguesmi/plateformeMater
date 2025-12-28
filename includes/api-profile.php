<?php
// Fichier: /home/utmresearchplatform/public_html/wp-content/plugins/plateforme-master/includes/api-profile.php
/**
 * API Profile – Plateforme profile
 * Namespace: /wp-json/plateforme-profile/v1/...
 *
 * Endpoints :
 * - GET   /profile[?user_id=ID]       (récupérer le profil : connecté ou membre ciblé)
 * - PATCH /profile                    (mettre à jour les champs du profil connecté)
 * - POST  /profile/password           (changer le mot de passe)
 * - POST  /profile/avatar             (uploader un avatar)
 * - POST  /profile/cv                 (uploader un CV)
 * - GET   /profile/refs               (référentiels grade/spécialité)
 */

if (!defined('ABSPATH')) exit;

/* ===========================
   Helpers autorisation lecture
   =========================== */
if (!function_exists('profile_current_can_view_user')) {
  function profile_current_can_view_user($target_user_id){
    $me = get_current_user_id();
    if (!$me || !$target_user_id) return false;
    if ((int)$me === (int)$target_user_id) return true;            // soi-même
    if (current_user_can('manage_options')) return true;            // admin
    $u = wp_get_current_user();
    $roles = array_map('strtolower', (array)$u->roles);
    if (in_array('um_directeur_laboratoire', $roles, true)) return true; // directeur
    return false;
  }
}

/* ===========================
   Déclaration des routes
   =========================== */
add_action('rest_api_init', function () {

  register_rest_route('plateforme-profile/v1', '/profile/refs', [
    'methods'  => 'GET',
    'callback' => 'api_profile_refs',
    'permission_callback' => function () { return is_user_logged_in(); },
  ]);

  // ------- PROFIL -------
  register_rest_route('plateforme-profile/v1', '/profile', [
    [
      'methods'  => 'GET',
      'callback' => 'api_profile_get',
      'permission_callback' => function () { return is_user_logged_in(); },
      'args' => [
        'user_id' => [
          'type' => 'integer',
          'required' => false,
        ],
      ],
    ],
    [
      'methods'  => 'PATCH',
      'callback' => 'api_profile_update',
      'permission_callback' => function () { return is_user_logged_in(); },
    ],
  ]);

  register_rest_route('plateforme-profile/v1', '/profile/password', [
    'methods'  => 'POST',
    'callback' => 'api_profile_change_password',
    'permission_callback' => function () { return is_user_logged_in(); },
  ]);

  register_rest_route('plateforme-profile/v1', '/profile/avatar', [
    'methods'  => 'POST',
    'callback' => 'api_profile_upload_avatar',
    'permission_callback' => function () { return is_user_logged_in(); },
  ]);

  register_rest_route('plateforme-profile/v1', '/profile/cv', [
    'methods'  => 'POST',
    'callback' => 'api_profile_upload_cv',
    'permission_callback' => function () { return is_user_logged_in(); },
  ]);
});

/* ===========================================
   Implémentations (appellent le layer service)
   =========================================== */
require_once dirname(__DIR__) . '/services/services_profile.php';
// Doivent exister dans services_profile.php :
// - profile_get($user_id)
// - profile_update($user_id, $patch)
// - profile_change_password($user_id, $payload)
// - profile_upload_avatar($user_id, $payload)
// - profile_upload_cv($user_id, $payload)
// - profile_refs()

/* ------- PROFIL ------- */
function api_profile_get(WP_REST_Request $req) {
  // Si ?user_id=… fourni → lecture du profil de ce membre (si autorisé)
  $target = (int)($req->get_param('user_id') ?: 0);
  if ($target) {
    if (!profile_current_can_view_user($target)) {
      return new WP_Error('forbidden', 'Accès refusé', ['status' => 403]);
    }
  } else {
    // Sinon : profil de l’utilisateur connecté
    $target = get_current_user_id();
    if (!$target) return new WP_Error('not_logged_in', 'Non connecté', ['status' => 401]);
  }

  $data = profile_get($target);

  // Confort : si academic_info est une string JSON, on la décode
  if (is_array($data) && isset($data['academic_info']) && is_string($data['academic_info']) && $data['academic_info'] !== '') {
    $tmp = json_decode($data['academic_info'], true);
    if (json_last_error() === JSON_ERROR_NONE) {
      $data['academic_info'] = $tmp;
    }
  }

  return $data;
}

function api_profile_update(WP_REST_Request $req) {
  // MAJ = toujours sur le profil connecté (pas d’édition d’autrui ici)
  $uid = get_current_user_id();
  if (!$uid) return new WP_Error('not_logged_in', 'Non connecté', ['status' => 401]);
  $patch = $req->get_json_params(); // {email1?, email2?, tel_country?, tel?, ... , expertises?, domaines?, orcid?, grade_id?, specialite_id?, academic_info?}
  return profile_update($uid, $patch);
}

function api_profile_change_password(WP_REST_Request $req) {
  $uid = get_current_user_id();
  if (!$uid) return new WP_Error('not_logged_in', 'Non connecté', ['status' => 401]);
  $payload = $req->get_json_params(); // {old_password, new_password, confirm_password}
  return profile_change_password($uid, $payload);
}

function api_profile_upload_avatar(WP_REST_Request $req) {
  $uid = get_current_user_id();
  if (!$uid) return new WP_Error('not_logged_in', 'Non connecté', ['status' => 401]);
  $payload = $req->get_json_params(); // {file_name, mime_type, content(base64)}
  return profile_upload_avatar($uid, $payload);
}

function api_profile_upload_cv(WP_REST_Request $req) {
  $uid = get_current_user_id();
  if (!$uid) return new WP_Error('not_logged_in', 'Non connecté', ['status' => 401]);
  $payload = $req->get_json_params(); // {file_name, mime_type, content(base64)}
  return profile_upload_cv($uid, $payload);
}

function api_profile_refs(WP_REST_Request $req) {
  return profile_refs(); // défini dans services_profile.php
}
