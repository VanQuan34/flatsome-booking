<?php
/**
 * Tevo Child Theme functions.
 *
 * @package Tevo_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue child theme styles.
 */
function tevo_child_enqueue_styles() {
	wp_enqueue_style(
		'tevo-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'tevo' )->get( 'Version' )
	);

	wp_enqueue_style(
		'tevo-child-style',
		get_stylesheet_uri(),
		array( 'tevo-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'tevo_child_enqueue_styles' );
