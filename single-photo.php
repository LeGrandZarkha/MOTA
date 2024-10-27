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
                    <img src="" alt="">
                </div>
                <div class="contact-right-arrows"></div>
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

    <script src="https://kit.fontawesome.com/7992438ddc.js" crossorigin="anonymous"></script>

</main>

<?php
get_footer();
?>