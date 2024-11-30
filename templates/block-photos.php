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


    // QUERY POUR AFFICHER LES ARTICLES EN RELATIONS
    $my_query = new WP_Query($args);

    if ($my_query->have_posts()) :
        while ($my_query->have_posts()) : $my_query->the_post();

            // Récupère l'ID de l'image depuis le champ personnalisé 'thumbnail'
            $thumbnail_related_id = get_post_meta(get_the_ID(), 'thumbnail', true);
            $post_url = get_permalink();

            // Si on obtient un ID d'image, récupère l'URL correspondante
            if ($thumbnail_related_id) {
                $thumbnail_related_url = wp_get_attachment_url($thumbnail_related_id);
                $photo_ref = esc_attr(get_field('reference')); // Assurez-vous que la référence est bien récupérée

                // Si l'URL existe, affiche l'image
                if ($thumbnail_related_url) {
                    echo "<div class='single-related-photo' data-ref='$photo_ref' data-url='$post_url'>
                        <img src='" . esc_url($thumbnail_related_url) . "' data-url='" . esc_url($thumbnail_related_url) . "' alt='" . get_the_title() . "'/>
                    <i class='fa-solid fa-eye'></i>
                    <i class='fa-solid fa-expand'></i>
                        <div class='related-photo-infos'>
                            <p class='related-photo-title'>" . get_the_title()  . "</p>
                            <p class='related-photo-category'>" . $categorie_slug . "</p>
                        </div>
                    </div>";
                } else {
                    echo '<p>Aucune image en rapport trouvée.</p>';
                }
            }

        endwhile;
        wp_reset_postdata();

    endif;
}
