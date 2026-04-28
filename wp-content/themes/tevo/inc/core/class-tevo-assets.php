<?php
/**
 * Tevo Assets Manager.
 *
 * Handles enqueuing of all styles and scripts.
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tevo_Assets
 */
class Tevo_Assets {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'dynamic_styles' ), 20 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
	}

	/**
	 * Enqueue frontend styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		// Google Fonts.
		$google_fonts_url = $this->get_google_fonts_url();
		if ( $google_fonts_url ) {
			wp_enqueue_style(
				'tevo-google-fonts',
				$google_fonts_url,
				array(),
				TEVO_VERSION
			);
		}

		// Main theme stylesheet.
		wp_enqueue_style(
			'tevo-style',
			TEVO_ASSETS_URI . '/css/style.css',
			array(),
			TEVO_VERSION
		);

		// Theme identity stylesheet (style.css in root — required by WP).
		wp_enqueue_style(
			'tevo-theme',
			get_stylesheet_uri(),
			array( 'tevo-style' ),
			TEVO_VERSION
		);

		// WooCommerce styles.
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style(
				'tevo-woocommerce',
				TEVO_ASSETS_URI . '/css/woocommerce.css',
				array( 'tevo-style' ),
				TEVO_VERSION
			);
		}
	}

	/**
	 * Enqueue frontend scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Main theme script.
		wp_enqueue_script(
			'tevo-main',
			TEVO_ASSETS_URI . '/js/main.js',
			array( 'jquery' ),
			TEVO_VERSION,
			true
		);

		// Localize script for AJAX and other data.
		wp_localize_script( 'tevo-main', 'tevoData', array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'tevo_ajax_nonce' ),
			'siteUrl'  => esc_url( home_url( '/' ) ),
			'i18n'     => array(
				'loading'   => esc_html__( 'Loading...', 'tevo' ),
				'error'     => esc_html__( 'Something went wrong.', 'tevo' ),
				'noResults' => esc_html__( 'No results found.', 'tevo' ),
			),
		) );

		// Comment reply script.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Add dynamic styles from Customizer.
	 *
	 * @return void
	 */
	public function dynamic_styles() {
		$primary_color   = tevo_get_option( 'tevo_primary_color', '#1a3a5c' );
		$secondary_color = tevo_get_option( 'tevo_secondary_color', '#f0a030' );
		$accent_color    = tevo_get_option( 'tevo_accent_color', '#2ecc71' );
		$text_color      = tevo_get_option( 'tevo_text_color', '#4a5568' );
		$heading_color   = tevo_get_option( 'tevo_heading_color', '#1a202c' );

		$heading_font = tevo_get_option( 'tevo_heading_font', 'Outfit' );
		$body_font    = tevo_get_option( 'tevo_body_font', 'Inter' );

		$css = "
			:root {
				--tevo-primary: {$primary_color};
				--tevo-secondary: {$secondary_color};
				--tevo-accent: {$accent_color};
				--tevo-text: {$text_color};
				--tevo-heading: {$heading_color};
				--tevo-heading-font: '{$heading_font}', sans-serif;
				--tevo-body-font: '{$body_font}', sans-serif;
				--tevo-primary-rgb: " . $this->hex_to_rgb( $primary_color ) . ";
				--tevo-secondary-rgb: " . $this->hex_to_rgb( $secondary_color ) . ";
			}
		";

		wp_add_inline_style( 'tevo-style', $this->minify_css( $css ) );
	}

	/**
	 * Enqueue block editor assets.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets() {
		wp_enqueue_style(
			'tevo-editor-style',
			TEVO_ASSETS_URI . '/css/editor-style.css',
			array(),
			TEVO_VERSION
		);
	}

	/**
	 * Get Google Fonts URL.
	 *
	 * @return string Google Fonts URL.
	 */
	private function get_google_fonts_url() {
		$heading_font = tevo_get_option( 'tevo_heading_font', 'Outfit' );
		$body_font    = tevo_get_option( 'tevo_body_font', 'Inter' );

		$fonts = array();

		if ( $heading_font ) {
			$fonts[] = str_replace( ' ', '+', $heading_font ) . ':wght@400;500;600;700;800';
		}
		if ( $body_font && $body_font !== $heading_font ) {
			$fonts[] = str_replace( ' ', '+', $body_font ) . ':wght@300;400;500;600;700';
		}

		if ( empty( $fonts ) ) {
			return '';
		}

		$fonts_url = 'https://fonts.googleapis.com/css2?' . implode( '&', array_map( function( $font ) {
			return 'family=' . $font;
		}, $fonts ) ) . '&display=swap';

		return esc_url_raw( $fonts_url );
	}

	/**
	 * Convert hex color to RGB values.
	 *
	 * @param string $hex Hex color code.
	 * @return string RGB values.
	 */
	private function hex_to_rgb( $hex ) {
		$hex = ltrim( $hex, '#' );

		if ( strlen( $hex ) === 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		return "{$r}, {$g}, {$b}";
	}

	/**
	 * Minify CSS string.
	 *
	 * @param string $css CSS string to minify.
	 * @return string Minified CSS.
	 */
	private function minify_css( $css ) {
		$css = preg_replace( '/\s+/', ' ', $css );
		$css = preg_replace( '/\s*([:;{},])\s*/', '$1', $css );
		return trim( $css );
	}
}

new Tevo_Assets();
