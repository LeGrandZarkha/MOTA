<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header>
        <img src="<?php echo get_stylesheet_directory_uri() . '/images/Logo.png'; ?>" alt="Logo de Nathalie MOTA">
        <nav>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class' => 'nav-menu',
            ));
            ?>
        </nav>
    </header>