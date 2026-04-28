<?php
/**
 * Tevo helper functions.
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get theme option from Customizer with a default fallback.
 *
 * @param string $key     Option key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function tevo_get_option( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/**
 * Check if a plugin is active.
 *
 * @param string $plugin Plugin path (e.g., 'woocommerce/woocommerce.php').
 * @return bool
 */
function tevo_is_plugin_active( $plugin ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	return is_plugin_active( $plugin );
}

/**
 * Check if Elementor is active.
 *
 * @return bool
 */
function tevo_is_elementor_active() {
	return did_action( 'elementor/loaded' );
}

/**
 * Check if WooCommerce is active.
 *
 * @return bool
 */
function tevo_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Get SVG icon markup.
 *
 * @param string $icon  Icon name.
 * @param int    $size  Icon size in pixels.
 * @return string SVG markup.
 */
function tevo_get_svg_icon( $icon, $size = 24 ) {
	$icons = array(
		'phone'     => '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
		'mail'      => '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
		'location'  => '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
		'clock'     => '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
		'shield'    => '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
		'check'     => '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
		'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
		'search'    => '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
	);

	if ( ! isset( $icons[ $icon ] ) ) {
		return '';
	}

	$size = absint( $size );

	return sprintf( $icons[ $icon ], $size, $size );
}

/**
 * Get the breadcrumb trail.
 *
 * @return void
 */
function tevo_breadcrumb() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="tevo-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'tevo' ) . '">', '</nav>' );
		return;
	}

	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="tevo-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'tevo' ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'tevo' ) . '</a>';

	if ( is_category() || is_single() ) {
		echo ' <span class="tevo-breadcrumb__sep">/</span> ';
		the_category( ' <span class="tevo-breadcrumb__sep">/</span> ' );
		if ( is_single() ) {
			echo ' <span class="tevo-breadcrumb__sep">/</span> ';
			the_title( '<span class="tevo-breadcrumb__current">', '</span>' );
		}
	} elseif ( is_page() ) {
		echo ' <span class="tevo-breadcrumb__sep">/</span> ';
		the_title( '<span class="tevo-breadcrumb__current">', '</span>' );
	} elseif ( is_search() ) {
		echo ' <span class="tevo-breadcrumb__sep">/</span> ';
		/* translators: %s: Search query. */
		printf( '<span class="tevo-breadcrumb__current">' . esc_html__( 'Search Results for: %s', 'tevo' ) . '</span>', esc_html( get_search_query() ) );
	} elseif ( is_archive() ) {
		echo ' <span class="tevo-breadcrumb__sep">/</span> ';
		echo '<span class="tevo-breadcrumb__current">';
		the_archive_title();
		echo '</span>';
	} elseif ( is_404() ) {
		echo ' <span class="tevo-breadcrumb__sep">/</span> ';
		echo '<span class="tevo-breadcrumb__current">' . esc_html__( '404 Not Found', 'tevo' ) . '</span>';
	}

	echo '</nav>';
}

/**
 * Get post reading time in minutes.
 *
 * @param int $post_id Post ID.
 * @return int Reading time in minutes.
 */
function tevo_get_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$content    = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$minutes    = max( 1, (int) ceil( $word_count / 200 ) );

	return $minutes;
}

/**
 * Get social share links for the current post.
 *
 * @return array Array of social share links.
 */
function tevo_get_share_links() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );

	return array(
		'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		'twitter'   => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
		'linkedin'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title,
		'pinterest' => 'https://pinterest.com/pin/create/button/?url=' . $url . '&description=' . $title,
	);
}

/**
 * Display page title section / hero banner.
 *
 * @param array $args Optional arguments.
 * @return void
 */
function tevo_page_title( $args = array() ) {
	$defaults = array(
		'title'      => '',
		'subtitle'   => '',
		'show_bread' => true,
	);

	$args = wp_parse_args( $args, $defaults );

	if ( empty( $args['title'] ) ) {
		if ( is_archive() ) {
			$args['title'] = get_the_archive_title();
		} elseif ( is_search() ) {
			/* translators: %s: Search query. */
			$args['title'] = sprintf( esc_html__( 'Search Results for: %s', 'tevo' ), get_search_query() );
		} elseif ( is_404() ) {
			$args['title'] = esc_html__( '404 - Page Not Found', 'tevo' );
		} else {
			$args['title'] = get_the_title();
		}
	}

	get_template_part( 'template-parts/components/page-title', null, $args );
}
