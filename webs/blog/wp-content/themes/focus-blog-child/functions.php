<?php
// Enqueue parent and child theme styles
function my_child_theme_enqueue_styles() {
    // Load parent theme stylesheet first
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    
    // Load child theme stylesheet
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));

    
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri().'/custom.js', array('jquery'), NULL, true);

}
add_action('wp_enqueue_scripts', 'my_child_theme_enqueue_styles');

/**
 * Load init.
 */
require_once get_template_directory() . '/inc/init.php';


?>

