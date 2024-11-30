<?php

// Enqueue styles and scripts
function mota_enqueue_styles()
{
    wp_enqueue_style('style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'mota_enqueue_styles');

function mota_enqueue_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/script.js');
    wp_enqueue_script('home-script', get_template_directory_uri() . '/js/frontpage.js');

    wp_localize_script('custom-script', 'ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));

    $reference = get_field('reference', get_the_ID());

    wp_localize_script('custom-script', 'motaData', array(
        'reference' => esc_js($reference), // Sécurise et passe la valeur au JS
    ));

    // Passez les données à votre script
    wp_localize_script('custom-script', 'photoData', mota_get_related_photos());
    wp_localize_script('home-script', 'photoData', mota_get_related_photos());
}
add_action('wp_enqueue_scripts', 'mota_enqueue_scripts');


// Register navigation menus
function menu_setup()
{
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'mota'), // Enregistrement de l'emplacement du menu
        'footer' => __('Menu Footer', 'mota'), // Enregistrement de l'emplacement du menu
    ));
}
add_action('after_setup_theme', 'menu_setup');



// Récupère toutes les photos sans filtrage par catégorie
function mota_get_related_photos()
{
    $post_id = get_the_ID();

    // Préparez les arguments pour la requête
    $all_args = array(
        'post_type' => 'photo',
        'orderby' => 'date',
        'post__not_in' => array($post_id), // Exclure l'article en cours
        'posts_per_page' => -1, // Récupère tous les articles
    );

    // Exécutez la requête pour récupérer toutes les photos
    $all_photos_query = new WP_Query($all_args);
    $all_photos = array();

    if ($all_photos_query->have_posts()) :
        while ($all_photos_query->have_posts()) :
            $all_photos_query->the_post();

            // Récupération des informations de la photo
            $thumbnail_related_id = get_post_meta(get_the_ID(), 'thumbnail', true);
            $thumbnail_related_url = $thumbnail_related_id ? wp_get_attachment_url($thumbnail_related_id) : '';
            $photo_ref = esc_attr(get_field('reference'));

            // Récupérer les termes de la taxonomie 'categorie' pour chaque photo
            $terms = get_the_terms(get_the_ID(), 'categorie');
            $categorie_names = array();
            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $categorie_names[] = esc_html($term->name);
                }
            }
            $categorie_slug = implode(', ', $categorie_names); // Concaténer les catégories en une chaîne

            // Ajout des données dans le tableau si l'URL de l'image est valide
            if ($thumbnail_related_url) {
                $all_photos[] = array(
                    'title' => get_the_title(),
                    'url' => $thumbnail_related_url,
                    'ref' => $photo_ref,
                    'category' => $categorie_slug, // Utilise la catégorie récupérée pour chaque photo
                );
            }
        endwhile;
        wp_reset_postdata();
    endif;

    return $all_photos;
}


// AFFICHER LES 8 PHOTOS ACCUEIL
add_action('wp_ajax_load_photos', 'load_photos');
add_action('wp_ajax_nopriv_load_photos', 'load_photos');

function load_photos()
{
    // Récupérer les filtres de la requête AJAX
    $category = isset($_POST['categorie']) ? sanitize_text_field($_POST['categorie']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $date_order = isset($_POST['date_order']) ? sanitize_text_field($_POST['date_order']) : 'desc';
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;

    // Arguments de la requête
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 9,
        'offset' => $offset,
        'orderby' => 'date',
        'order' => $date_order,
        'tax_query' => array(),
    );

    // Ajouter les filtres de taxonomie si définis
    if ($category) {
        $args['tax_query'][] = array(
            'taxonomy' => 'categorie',
            'field' => 'slug',
            'terms' => $category,
        );
    }
    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $format,
        );
    }

    // La requête
    $photo_query = new WP_Query($args);

    // Initialisation des variables pour html et comptage
    $output = '';
    $count = 0;

    // Générer le HTML pour les photos
    if ($photo_query->have_posts()) {
        while ($photo_query->have_posts()) {
            $photo_query->the_post();
            $photo_id = get_permalink($photo_query->ID);
            $thumbnail_id = get_post_meta(get_the_ID(), 'thumbnail', true);
            $thumbnail_url = wp_get_attachment_url($thumbnail_id);
            $terms = get_the_terms(get_the_ID(), 'categorie');
            $photo_ref = esc_attr(get_field('reference'));

            $global_index = $offset + $count;

            if ($terms && !is_wp_error($terms)) {
                // On extrait et affiche les noms des termes
                $categorie_names = array();
                foreach ($terms as $term) {
                    $categorie_names[] = esc_html($term->name); // Ajoute le nom de chaque terme à un tableau
                }
                $category = implode(', ', $categorie_names); // Affiche les noms séparés par une virgule
            };

            if ($thumbnail_url) {
                $lightbox_photos[] = array(
                    'title' => get_the_title(),
                    'url' => $thumbnail_url,
                    'ref' => $photo_ref,
                    'index' => $global_index,
                    'category' => $category, // Utilise la catégorie récupérée pour chaque photo
                );
            }


            $output .= '<div class="photo-item" data-index="' . $global_index . '" data-ref="' . $photo_ref . '" data-id="' . $photo_id . '" data-url="' . esc_url($thumbnail_url) . '">';
            $output .= '<i class="fa-solid fa-eye"></i>';
            $output .= '<i class="fa-solid fa-expand"></i>';
            $output .= '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '" data-url="' . esc_url($thumbnail_url) . '">';
            $output .= '<div class="photo-item-infos">
                        <p class="photo-item-infos-title">' . esc_attr(get_the_title()) . '</p>
                        <p class="photo-item-infos-category">' . esc_attr($category) . '</p>
                        </div>';
            $output .= '</div>';
            $count++;
        }
    } else {
        $output = '<p>Aucune photo trouvée.</p>';
    }

    // Retourner les données en JSON
    echo json_encode(array(
        'html' => $output,
        'lightbox_photos' => $lightbox_photos, // Les données nécessaires pour la lightbox
        'count' => $count // Nombre de photos trouvées
    ));

    wp_reset_postdata();
    die();
}
