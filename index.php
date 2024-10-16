<?php get_header(); ?>

<main>
    <h1><?php the_title(); ?></h1>
    <div>
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        else :
            echo '<p>Aucun contenu trouvé.</p>';
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>