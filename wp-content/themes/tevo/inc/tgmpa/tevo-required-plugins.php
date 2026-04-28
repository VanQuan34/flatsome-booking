<?php
/**
 * Tevo Required Plugins (TGM Plugin Activation).
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'tgmpa_register', 'tevo_register_required_plugins' );

/**
 * Register required and recommended plugins.
 *
 * @return void
 */
function tevo_register_required_plugins() {
	$plugins = array(
		array(
			'name'     => 'Elementor',
			'slug'     => 'elementor',
			'required' => true,
		),
		array(
			'name'     => 'WooCommerce',
			'slug'     => 'woocommerce',
			'required' => true,
		),
		array(
			'name'     => 'Contact Form 7',
			'slug'     => 'contact-form-7',
			'required' => true,
		),
		array(
			'name'     => 'One Click Demo Import',
			'slug'     => 'one-click-demo-import',
			'required' => false,
		),
		array(
			'name'     => 'Slider Revolution',
			'slug'     => 'revslider',
			'source'   => get_template_directory() . '/plugins/revslider.zip',
			'required' => false,
		),
	);

	$config = array(
		'id'           => 'tevo',
		'default_path' => '',
		'menu'         => 'tevo-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'is_automatic' => false,
		'strings'      => array(
			'page_title' => esc_html__( 'Install Required Plugins', 'tevo' ),
			'menu_title' => esc_html__( 'Install Plugins', 'tevo' ),
		),
	);

	tgmpa( $plugins, $config );
}
