<?php

function locate_wp_load(): ?string {
    // 1) Variable d'environnement optionnelle
    $env = getenv('WP_LOAD_PATH');
    if ($env && file_exists($env)) return $env;

    // 2) Parcours en remontant depuis le dossier courant (…/plugins/plateforme-master/scripts)
    $dir = __DIR__;
    for ($i = 0; $i < 8; $i++) {
        $candidate = $dir . '/wp-load.php';
        if (file_exists($candidate)) return $candidate;
        $dir = dirname($dir);
    }

    // 3) Chemins connus en fallback (à adapter si besoin)
    $fallbacks = [
        '/home/utmresearchplatform/public_html/wp-load.php', // prod
        '/var/www/html/wp-load.php',                         // générique
    ];
    foreach ($fallbacks as $f) {
        if (file_exists($f)) return $f;
    }
    return null;
}

$wp_load = locate_wp_load();
if ($wp_load) {
    require_once $wp_load;
} else {
    exit("❌ wp-load.php introuvable. Vérifiez le chemin ou définissez WP_LOAD_PATH.\n");
}

global $wpdb;

// Liste des slugs/pages à créer
$pages_USCR = [
    
    'uscr-plateformes'                               => 'uscr-plateformes',
    'uscr-equipements'                               => 'uscr-equipements',
    'uscr-reservation-et-planning'                   => 'uscr-reservation-et-planning',
    'uscr-maintenance-et-incidents'                  => 'uscr-maintenance-et-incidents',
    'uscr-utilisateurs'                              => 'uscr-utilisateurs',
    'uscr-statistiques-et-historique'                => 'uscr-statistiques-et-historique',
    'uscr-salles'                              => 'uscr-salles',
    'uscr-calender'                              => 'uscr-calender',
    'uscr-finance_uscr'                              => 'uscr-finance_uscr',
    'uscr-details_equipements'                              => 'uscr-details_equipements',
    'uscr-details_plateforme'                              => 'uscr-details_plateforme',
    'uscr-fiche-budget'                              => 'uscr-fiche-budget',
    
    
    
];


// Répertoire des fichiers à générer
$plugin_root = dirname(__DIR__); // …/wp-content/plugins/plateforme-master
$base_dir    = $plugin_root . '/Modules/Unités_Service_Communs/';
if (!is_dir($base_dir)) {
    exit("❌ Dossier cible introuvable : $base_dir\n");
}

foreach ($pages_USCR as $slug) {
    $title = ucwords(str_replace("-", " ", $slug));

    // Vérifier si la page existe déjà
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->prefix}posts WHERE post_name = %s AND post_type = 'page'",
        $slug
    ));

    if ($exists) {
        echo "⏩ Page '$slug' déjà existante (ID: $exists).\n";
    } else {
        // Créer la page
        $wpdb->insert("{$wpdb->prefix}posts", [
            'post_author'    => 1,
            'post_date'      => current_time('mysql'),
            'post_date_gmt'  => current_time('mysql', 1),
            'post_content'   => '',
            'post_title'     => $title,
            'post_status'    => 'publish',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_name'      => $slug,
            'post_type'      => 'page'
        ]);

        $post_id = $wpdb->insert_id;

        // Ajout des métadonnées
        add_post_meta($post_id, '_wp_page_template', 'espace');
        add_post_meta($post_id, 'um_content_restriction', serialize([
            "_um_custom_access_settings" => false,
            "_um_accessible" => 0
        ]));

        echo "✅ Page '$slug' créée (ID: $post_id).\n";
    }

    // Créer le fichier PHP associé
    $filepath = $base_dir . $slug . ".php";

    if (!file_exists($filepath)) {
        $php_content = "<?php\n// Page $slug\nget_header();\necho '<h1>$title</h1>';\nget_footer();\n";
        file_put_contents($filepath, $php_content);
        echo "📄 Fichier '$slug.php' généré dans $base_dir.\n";
    } else {
        echo "📁 Fichier '$slug.php' déjà existant.\n";
    }

    echo "--------------------------------------------\n";
}

echo "✅ Script terminé.\n";
?>
