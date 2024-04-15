<?php
/**
 * Focus Blog Theme Customizer
 *
 * @package Focus Blog
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function focus_blog_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Register custom section types.
	$wp_customize->register_section_type( 'focus_blog_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section(
		new focus_blog_Customize_Section_Upsell(
			$wp_customize,
			'theme_upsell',
			array(
				'title'    => esc_html__( 'Focus Blog', 'focus-blog' ),
				'pro_text' => esc_html__( 'Buy Pro', 'focus-blog' ),
				'pro_url'  => 'http://www.creativthemes.com/downloads/focus-blog-pro/',
				'priority'  => 10,
			)
		)
	);

	// Load customize sanitize.
	include get_template_directory() . '/inc/customizer/sanitize.php';

	// Load header sections option.
	include get_template_directory() . '/inc/customizer/theme-section.php';

}
add_action( 'customize_register', 'focus_blog_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function focus_blog_customize_preview_js() {
	wp_enqueue_script( 'focus_blog_customizer', get_template_directory_uri() . '/inc/customizer/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'focus_blog_customize_preview_js' );
/**
 *
 */
function focus_blog_customize_backend_scripts() {

	wp_enqueue_style( 'focus-blog-fontawesome-all', get_template_directory_uri() . '/assets/css/all.css' );

	wp_enqueue_style( 'focus-blog-admin-customizer-style', get_template_directory_uri() . '/inc/customizer/css/customizer-style.css' );

	wp_enqueue_script( 'focus-blog-admin-customizer', get_template_directory_uri() . '/inc/customizer/js/customizer-script.js', array( 'jquery', 'customize-controls' ), '20151215', true );
}
add_action( 'customize_controls_enqueue_scripts', 'focus_blog_customize_backend_scripts', 10 );