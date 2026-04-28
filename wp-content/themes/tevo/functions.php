<?php
/**
 * Tevo Theme functions and definitions.
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define theme constants.
 */
define( 'TEVO_VERSION', '1.0.0' );
define( 'TEVO_DIR', get_template_directory() );
define( 'TEVO_URI', get_template_directory_uri() );
define( 'TEVO_ASSETS_URI', TEVO_URI . '/assets' );
define( 'TEVO_INC_DIR', TEVO_DIR . '/inc' );
define( 'TEVO_MIN_PHP_VERSION', '7.0' );
define( 'TEVO_MIN_WP_VERSION', '5.0' );

/**
 * Check minimum PHP version.
 */
if ( version_compare( PHP_VERSION, TEVO_MIN_PHP_VERSION, '<' ) ) {
	add_action( 'admin_notices', 'tevo_php_version_notice' );
	/**
	 * Display a notice for outdated PHP version.
	 *
	 * @return void
	 */
	function tevo_php_version_notice() {
		/* translators: 1: Required PHP version, 2: Current PHP version. */
		$message = sprintf(
			esc_html__( 'Tevo requires PHP version %1$s or higher. You are running version %2$s. Please upgrade your PHP version.', 'tevo' ),
			TEVO_MIN_PHP_VERSION,
			PHP_VERSION
		);
		printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $message ) );
	}
	return;
}

/**
 * Load core theme files.
 */
require_once TEVO_INC_DIR . '/helpers.php';
require_once TEVO_INC_DIR . '/hooks.php';
require_once TEVO_INC_DIR . '/core/class-tevo-setup.php';
require_once TEVO_INC_DIR . '/core/class-tevo-assets.php';
require_once TEVO_INC_DIR . '/core/class-tevo-walker-nav.php';

/**
 * Load Customizer.
 */
require_once TEVO_INC_DIR . '/customizer/class-tevo-customizer.php';

/**
 * Load WooCommerce integration (if WooCommerce is active).
 */
if ( class_exists( 'WooCommerce' ) ) {
	require_once TEVO_INC_DIR . '/woocommerce/class-tevo-woocommerce.php';
}

/**
 * Load Elementor integration (if Elementor is active).
 */
if ( did_action( 'elementor/loaded' ) ) {
	require_once TEVO_INC_DIR . '/elementor/class-tevo-elementor.php';
} else {
	add_action( 'plugins_loaded', function () {
		if ( did_action( 'elementor/loaded' ) ) {
			require_once TEVO_INC_DIR . '/elementor/class-tevo-elementor.php';
		}
	} );
}

/**
 * Load TGM Plugin Activation.
 */
require_once TEVO_INC_DIR . '/tgmpa/class-tgm-plugin-activation.php';
require_once TEVO_INC_DIR . '/tgmpa/tevo-required-plugins.php';

/**
 * Load Demo Import (if One Click Demo Import is active).
 */
if ( class_exists( 'OCDI_Plugin' ) ) {
	require_once TEVO_INC_DIR . '/demo-import/class-tevo-demo-import.php';
}
