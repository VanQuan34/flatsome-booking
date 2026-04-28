<?php
/**
 * Tevo Demo Import Configuration.
 *
 * Integrates with One Click Demo Import plugin (OCDI).
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tevo_Demo_Import
 */
class Tevo_Demo_Import {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'ocdi/import_files', array( $this, 'import_files' ) );
		add_action( 'ocdi/after_import', array( $this, 'after_import' ) );
		add_filter( 'ocdi/register_plugins', array( $this, 'register_plugins' ) );
	}

	/**
	 * Define demo import files.
	 *
	 * @return array Demo import configurations.
	 */
	public function import_files() {
		return array(
			array(
				'import_file_name'           => esc_html__( 'Tevo - Insurance Agency', 'tevo' ),
				'categories'                 => array( 'Insurance', 'Business' ),
				'local_import_file'          => TEVO_INC_DIR . '/demo-import/demo-data/content.xml',
				'local_import_widget_file'   => TEVO_INC_DIR . '/demo-import/demo-data/widgets.wie',
				'local_import_customizer_file' => TEVO_INC_DIR . '/demo-import/demo-data/customizer.dat',
				'import_preview_image_url'   => TEVO_URI . '/screenshot.png',
				'preview_url'                => 'https://demo.tevo-theme.com/',
			),
		);
	}

	/**
	 * Actions to run after import.
	 *
	 * @param array $selected_import Selected import data.
	 * @return void
	 */
	public function after_import( $selected_import ) {
		// Set front page.
		$front_page = get_page_by_title( 'Home' );
		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}

		// Set blog page.
		$blog_page = get_page_by_title( 'Blog' );
		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}

		// Set primary menu.
		$primary_menu = wp_get_nav_menu_object( 'Primary Menu' );
		if ( $primary_menu ) {
			$locations = get_theme_mod( 'nav_menu_locations', array() );
			$locations['primary'] = $primary_menu->term_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}

		// Set footer menu.
		$footer_menu = wp_get_nav_menu_object( 'Footer Menu' );
		if ( $footer_menu ) {
			$locations = get_theme_mod( 'nav_menu_locations', array() );
			$locations['footer'] = $footer_menu->term_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}

		// Set permalink structure.
		update_option( 'permalink_structure', '/%postname%/' );
		flush_rewrite_rules();
	}

	/**
	 * Register required plugins for demo import.
	 *
	 * @param array $plugins Plugins array.
	 * @return array Modified plugins array.
	 */
	public function register_plugins( $plugins ) {
		$theme_plugins = array(
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
		);

		return array_merge( $plugins, $theme_plugins );
	}
}

new Tevo_Demo_Import();
