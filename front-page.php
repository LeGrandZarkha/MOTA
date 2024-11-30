<?php
get_header();
wp_body_open();
?>
<?php
$args = array(
    'post_type' => 'photo',
    'posts_per_page' => 1,
    'orderby' => 'rand',
    'tax_query' => array(             // Utilise tax_query pour la taxonomie
        array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => 'paysage',
        ),
    ),
);
$hero_query = new WP_Query($args);
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php if ($hero_query->have_posts()) : ?>
            <?php while ($hero_query->have_posts()) : $hero_query->the_post(); ?>

                <?php
                // Récupérer l'ID de la miniature depuis le champ personnalisé 'thumbnail'
                $thumbnail_id = get_post_meta(get_the_ID(), 'thumbnail', true);
                $thumbnail_url = wp_get_attachment_url($thumbnail_id);
                ?>

                <?php if ($thumbnail_url) : ?>
                    <section id="hero-banner" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">
                        <h1>PHOTOGRAPHIE EVENT</h1>
                    </section>
                <?php else : ?>
                    <!-- Cas où aucune miniature n'est trouvée -->
                    <p>Image non disponible.</p>
                <?php endif; ?>

            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
        <section id="catalogue-section">
            <div class="catalogue-filters">
                <div class="filters-container">
                    <div class="dropdown dropdown-category" id="category-filter">
                        <div class="dropdown-select">Catégorie</div>
                        <div class="dropdown-menu">
                            <div class="dropdown-option" data-value="">Catégorie</div>
                            <div class="dropdown-option" data-value="reception">Réception</div>
                            <div class="dropdown-option" data-value="television">Télévision</div>
                            <div class="dropdown-option" data-value="concert">Concert</div>
                            <div class="dropdown-option" data-value="mariage">Mariage</div>
                        </div>
                    </div>
                    <div class="dropdown dropdown-format" id="format-filter">
                        <div class="dropdown-select">Format</div>
                        <div class="dropdown-menu">
                            <div class="dropdown-option" data-value="">Format</div>
                            <div class="dropdown-option" data-value="portrait">Portrait</div>
                            <div class="dropdown-option" data-value="paysage">Paysage</div>
                        </div>
                    </div>
                    <div class="dropdown dropdown-date" id="date-filter">
                        <div class="dropdown-select">Date</div>
                        <div class="dropdown-menu">
                            <div class="dropdown-option" data-value="">Date</div>
                            <div class="dropdown-option" data-value="desc">Récentes vers Anciennes</div>
                            <div class="dropdown-option" data-value="asc">Anciennes vers Récentes</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="catalogue-photos"></div>
            <div class="wrapper-load-more">
                <button id="load-more">Charger Plus</button>
            </div>
        </section>

        <?php echo get_template_part('templates/popup') ?>
        <?php echo get_template_part('templates/lightbox'); ?>
    </main><!-- #main -->
    <script src="https://kit.fontawesome.com/7992438ddc.js" crossorigin="anonymous"></script>

</div><!-- #primary -->

<?php get_footer(); ?>