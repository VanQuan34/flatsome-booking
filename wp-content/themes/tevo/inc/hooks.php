<?php
/**
 * Tevo theme hooks.
 *
 * Custom action and filter hooks for theme extensibility.
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook: tevo_before_header.
 *
 * Fires before the site header.
 */
function tevo_before_header() {
	do_action( 'tevo_before_header' );
}

/**
 * Hook: tevo_after_header.
 *
 * Fires after the site header.
 */
function tevo_after_header() {
	do_action( 'tevo_after_header' );
}

/**
 * Hook: tevo_before_footer.
 *
 * Fires before the site footer.
 */
function tevo_before_footer() {
	do_action( 'tevo_before_footer' );
}

/**
 * Hook: tevo_after_footer.
 *
 * Fires after the site footer.
 */
function tevo_after_footer() {
	do_action( 'tevo_after_footer' );
}

/**
 * Hook: tevo_before_content.
 *
 * Fires before the main content area.
 */
function tevo_before_content() {
	do_action( 'tevo_before_content' );
}

/**
 * Hook: tevo_after_content.
 *
 * Fires after the main content area.
 */
function tevo_after_content() {
	do_action( 'tevo_after_content' );
}

/**
 * Hook: tevo_before_sidebar.
 *
 * Fires before the sidebar.
 */
function tevo_before_sidebar() {
	do_action( 'tevo_before_sidebar' );
}

/**
 * Hook: tevo_after_sidebar.
 *
 * Fires after the sidebar.
 */
function tevo_after_sidebar() {
	do_action( 'tevo_after_sidebar' );
}

/**
 * Hook: tevo_before_single_post.
 *
 * Fires before a single post content.
 */
function tevo_before_single_post() {
	do_action( 'tevo_before_single_post' );
}

/**
 * Hook: tevo_after_single_post.
 *
 * Fires after a single post content.
 */
function tevo_after_single_post() {
	do_action( 'tevo_after_single_post' );
}

/**
 * Add preloader markup.
 */
function tevo_preloader() {
	$preloader = tevo_get_option( 'tevo_preloader', true );

	if ( ! $preloader ) {
		return;
	}
	?>
	<div id="tevo-preloader" class="tevo-preloader" aria-hidden="true">
		<div class="tevo-preloader__spinner">
			<div class="tevo-preloader__bounce1"></div>
			<div class="tevo-preloader__bounce2"></div>
			<div class="tevo-preloader__bounce3"></div>
		</div>
	</div>
	<?php
}
add_action( 'tevo_before_header', 'tevo_preloader' );

/**
 * Add scroll to top button.
 */
function tevo_scroll_to_top() {
	$enabled = tevo_get_option( 'tevo_scroll_to_top', true );

	if ( ! $enabled ) {
		return;
	}
	?>
	<button id="tevo-scroll-top" class="tevo-scroll-top" aria-label="<?php esc_attr_e( 'Scroll to top', 'tevo' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
	</button>
	<?php
}
add_action( 'tevo_after_footer', 'tevo_scroll_to_top' );

/**
 * Add body classes for theme options.
 *
 * @param array $classes Existing body classes.
 * @return array Modified body classes.
 */
function tevo_body_classes( $classes ) {
	// Header style.
	$header_style = tevo_get_option( 'tevo_header_style', 'default' );
	$classes[]    = 'tevo-header-' . sanitize_html_class( $header_style );

	// Sidebar position.
	if ( is_singular() || is_archive() || is_home() ) {
		$sidebar = tevo_get_option( 'tevo_sidebar_position', 'right' );
		$classes[] = 'tevo-sidebar-' . sanitize_html_class( $sidebar );
	}

	// Sticky header.
	$sticky = tevo_get_option( 'tevo_sticky_header', true );
	if ( $sticky ) {
		$classes[] = 'tevo-sticky-header';
	}

	return $classes;
}
add_filter( 'body_class', 'tevo_body_classes' );
