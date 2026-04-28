<?php
/**
 * Tevo Theme Setup.
 *
 * Registers theme supports, menus, sidebars, and image sizes.
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tevo_Setup
 */
class Tevo_Setup {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
	}

	/**
	 * Theme setup.
	 *
	 * @return void
	 */
	public function setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'tevo', TEVO_DIR . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails.
		add_theme_support( 'post-thumbnails' );

		// Custom image sizes.
		add_image_size( 'tevo-blog-thumb', 750, 500, true );
		add_image_size( 'tevo-blog-large', 1170, 650, true );
		add_image_size( 'tevo-team', 400, 500, true );
		add_image_size( 'tevo-service', 600, 400, true );

		// Register navigation menus.
		register_nav_menus( array(
			'primary'    => esc_html__( 'Primary Menu', 'tevo' ),
			'footer'     => esc_html__( 'Footer Menu', 'tevo' ),
			'mobile'     => esc_html__( 'Mobile Menu', 'tevo' ),
			'top-bar'    => esc_html__( 'Top Bar Menu', 'tevo' ),
		) );

		// HTML5 markup support.
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		) );

		// Custom logo support.
		add_theme_support( 'custom-logo', array(
			'height'      => 80,
			'width'       => 250,
			'flex-height' => true,
			'flex-width'  => true,
		) );

		// Custom header support.
		add_theme_support( 'custom-header', array(
			'default-image' => '',
			'width'         => 1920,
			'height'        => 400,
			'flex-height'   => true,
			'flex-width'    => true,
		) );

		// Custom background support.
		add_theme_support( 'custom-background', array(
			'default-color' => 'ffffff',
		) );

		// Editor styles.
		add_editor_style( 'assets/css/editor-style.css' );

		// WooCommerce support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Wide alignment support for Gutenberg.
		add_theme_support( 'align-wide' );

		// Responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}

	/**
	 * Register widget areas / sidebars.
	 *
	 * @return void
	 */
	public function register_sidebars() {
		// Main sidebar.
		register_sidebar( array(
			'name'          => esc_html__( 'Main Sidebar', 'tevo' ),
			'id'            => 'sidebar-main',
			'description'   => esc_html__( 'Add widgets here to appear in the main sidebar.', 'tevo' ),
			'before_widget' => '<div id="%1$s" class="tevo-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="tevo-widget__title">',
			'after_title'   => '</h3>',
		) );

		// Footer widget areas.
		$footer_columns = absint( tevo_get_option( 'tevo_footer_columns', 4 ) );
		if ( $footer_columns < 1 ) {
			$footer_columns = 4;
		}

		for ( $i = 1; $i <= $footer_columns; $i++ ) {
			register_sidebar( array(
				/* translators: %d: Footer column number. */
				'name'          => sprintf( esc_html__( 'Footer Column %d', 'tevo' ), $i ),
				'id'            => 'footer-' . $i,
				/* translators: %d: Footer column number. */
				'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %d.', 'tevo' ), $i ),
				'before_widget' => '<div id="%1$s" class="tevo-footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="tevo-footer-widget__title">',
				'after_title'   => '</h4>',
			) );
		}

		// Shop sidebar (if WooCommerce is active).
		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Shop Sidebar', 'tevo' ),
				'id'            => 'sidebar-shop',
				'description'   => esc_html__( 'Add widgets here to appear in the shop sidebar.', 'tevo' ),
				'before_widget' => '<div id="%1$s" class="tevo-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="tevo-widget__title">',
				'after_title'   => '</h3>',
			) );
		}
	}

	/**
	 * Set the content width.
	 *
	 * @return void
	 */
	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'tevo_content_width', 1170 );
	}
}

new Tevo_Setup();
