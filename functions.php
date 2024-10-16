<?php
// Enqueue styles and scripts
function mota_enqueue_styles()
{
    wp_enqueue_style('style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'mota_enqueue_styles');

// Register navigation menus
function menu_setup()
{
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'mota'), // Enregistrement de l'emplacement du menu
        'footer' => __('Menu Footer', 'mota'), // Enregistrement de l'emplacement du menu
    ));
}
add_action('after_setup_theme', 'menu_setup');
