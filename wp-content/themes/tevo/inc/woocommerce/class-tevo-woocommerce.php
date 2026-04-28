<?php
/**
 * Tevo WooCommerce Integration.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tevo_WooCommerce {

	public function __construct() {
		// Declare WC support.
		add_action( 'after_setup_theme', array( $this, 'setup' ) );

		// Override WC defaults.
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'dequeue_default_styles' ) );
		add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ) );
		add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ) );
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );

		// Wrapper hooks.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_before' ) );
		add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_after' ) );

		// Remove default sidebar.
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		// AJAX cart count update.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_count_fragment' ) );
	}

	public function setup() {
		add_theme_support( 'woocommerce', array(
			'thumbnail_image_width' => 400,
			'gallery_thumbnail_image_width' => 150,
			'single_image_width' => 600,
			'product_grid' => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 4,
			),
		) );
	}

	public function dequeue_default_styles( $enqueue_styles ) {
		unset( $enqueue_styles['woocommerce-general'] );
		return $enqueue_styles;
	}

	public function products_per_page() {
		return absint( tevo_get_option( 'tevo_products_per_page', 12 ) );
	}

	public function loop_columns() {
		return 3;
	}

	public function related_products_args( $args ) {
		$args['posts_per_page'] = 3;
		$args['columns']        = 3;
		return $args;
	}

	public function wrapper_before() {
		echo '<div class="tevo-container"><div class="tevo-content-area"><div class="tevo-content">';
	}

	public function wrapper_after() {
		echo '</div></div></div>';
	}

	public function cart_count_fragment( $fragments ) {
		$fragments['.tevo-cart-count'] = '<span class="tevo-cart-count">' . esc_html( WC()->cart->get_cart_contents_count() ) . '</span>';
		return $fragments;
	}
}

new Tevo_WooCommerce();
