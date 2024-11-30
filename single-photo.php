<?php
get_header();
wp_body_open();
?>

<main>
    <div class="main-wrapper">
        <div class="wrapper-photo">
            <div class="photo-infos">
                <h1><?php the_title(); ?></h1>
                <ul>
                    <li>RÉFÉRENCE : <span><?php echo get_post_meta(get_the_ID(), 'reference', true); ?></span></li>
                    <li>CATÉGORIE :
                        <span>
                            <?php
                            $terms = get_the_terms(get_the_ID(), 'categorie');
                            if ($terms && !is_wp_error($terms)) {
                                // On extrait et affiche les noms des termes
                                $categorie_names = array();
                                foreach ($terms as $term) {
                                    $categorie_names[] = esc_html($term->name); // Ajoute le nom de chaque terme à un tableau
                                }
                                echo implode(', ', $categorie_names); // Affiche les noms séparés par une virgule
                            } else {
                                echo 'N/A'; // Message si aucun terme n'est trouvé
                            }
                            ?>
                        </span>
                    </li>
                    <li>FORMAT :
                        <span>
                            <?php
                            $terms = get_the_terms(get_the_ID(), 'format');
                            if ($terms && !is_wp_error($terms)) {
                                // On extrait et affiche les noms des termes
                                $format_names = array();
                                foreach ($terms as $term) {
                                    $format_names[] = esc_html($term->name); // Ajoute le nom de chaque terme à un tableau
                                }
                                echo implode(', ', $format_names); // Affiche les noms séparés par une virgule
                            } else {
                                echo 'N/A'; // Message si aucun terme n'est trouvé
                            }
                            ?>
                        </span>
                    </li>
                    <li>TYPE : <span><?php echo get_post_meta(get_the_ID(), 'type', true); ?></span></li>
                    <li>ANNÉE : <span><?php echo get_the_date('Y'); ?></span></li>
                </ul>
            </div>

            <div class="photo-img">
                <?php
                // Récupère l'URL de l'image depuis le champ personnalisé 'thumbnail'
                $thumbnail_url = get_field('thumbnail');

                if ($thumbnail_url) {
                    echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '"/>';
                } else {
                    echo "No image found.";
                }
                ?>
            </div>

        </div>
        <div class="wrapper-contact">
            <div class="contact-left">
                <p>Cette photo vous intéresse ?</p>
                <button class="contact-photo-button">Contact</button>
            </div>
            <div class="contact-right">
                <div class="contact-right-photo">
                    <img id="thumbnail-image" src="<?php echo esc_url(get_field('thumbnail')); ?>" data-thumbnail="<?php echo esc_url(get_field('thumbnail')); ?>" alt="">
                </div>
                <div class="contact-right-arrows">
                    <?php
                    // On récupère les articles
                    $prev_post = get_adjacent_post(false, '', true);
                    $next_post = get_adjacent_post(false, '', false);

                    $prev_thumbnail_url = '';
                    $next_thumbnail_url = '';

                    // On récupère les miniatures
                    if ($prev_post) {
                        $prev_post_id = get_permalink($prev_post->ID);
                        $prev_thumbnail_id = get_post_meta($prev_post->ID, 'thumbnail', true);
                        $prev_thumbnail_url = $prev_thumbnail_id ? wp_get_attachment_url($prev_thumbnail_id) : '';
                    }

                    if ($next_post) {
                        $next_post_id = get_permalink($next_post->ID);
                        $next_thumbnail_id = get_post_meta($next_post->ID, 'thumbnail', true);
                        $next_thumbnail_url = $next_thumbnail_id ? wp_get_attachment_url($next_thumbnail_id) : '';
                    }
                    ?>

                    <div class="arrow-left" data-id-prev="<?php echo $prev_post_id ?>" data-thumbnail="<?php echo esc_url($prev_thumbnail_url); ?>">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                    <div class="arrow-right" data-id-next="<?php echo $next_post_id ?>" data-thumbnail="<?php echo esc_url($next_thumbnail_url); ?>">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>


        <div class="wrapper-related-photos">
            <p>VOUS AIMEREZ AUSSI</p>
            <div class="related-photos">
                <?php get_template_part('templates/block', 'photos'); ?>
            </div>
        </div>
    </div>

    <?php echo get_template_part('templates/popup'); ?>

    <?php echo get_template_part('templates/lightbox'); ?>

    <script src="https://kit.fontawesome.com/7992438ddc.js" crossorigin="anonymous"></script>

</main>

<?php
get_footer();
?>