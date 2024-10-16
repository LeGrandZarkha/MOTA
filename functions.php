<?php
// Enqueue styles and scripts
function mota_enqueue_styles()
{
    wp_enqueue_style('style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'mota_enqueue_styles');
