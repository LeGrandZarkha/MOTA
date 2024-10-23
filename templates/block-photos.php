<?php

$terms = wp_get_post_terms(get_the_ID(), 'categorie'); // Récupère les termes de la taxonomie 'categorie'

if (!empty($terms) && !is_wp_error($terms)) {
    $categorie_slug = $terms[0]->slug; // Récupère le slug de la catégorie

    $args = array(
        'post_type' => 'photo',
        'orderby' => 'rand',
        'posts_per_page' => '2',
        'tax_query' => array(
            array(
                'taxonomy' => 'categorie',
                'field' => 'slug',
                'terms' => $categorie_slug,
            ),
        ),
        'post__not_in' => array(get_the_ID()), // On exclut l'article actuel pour ne pas afficher sa photo
    );

    $my_query = new WP_Query($args);

    if ($my_query->have_posts()) :
        while ($my_query->have_posts()) : $my_query->the_post();

            // Récupère l'ID de l'image depuis le champ personnalisé 'thumbnail'
            $thumbnail_related_id = get_post_meta(get_the_ID(), 'thumbnail', true);

            // Si on obtient un ID d'image, récupère l'URL correspondante
            if ($thumbnail_related_id) {
                $thumbnail_related_url = wp_get_attachment_url($thumbnail_related_id);

                // Si l'URL existe, affiche l'image
                if ($thumbnail_related_url) {
                    echo '<img src="' . esc_url($thumbnail_related_url) . '" alt="' . get_the_title() . '"/>';
                } else {
                    echo '<p>Aucune image en rapport trouvée.</p>';
                }
            }

        endwhile;
        wp_reset_postdata();

    endif;
}
