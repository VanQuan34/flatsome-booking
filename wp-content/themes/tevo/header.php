<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and the site header.
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}

tevo_before_header();

$header_style = tevo_get_option( 'tevo_header_style', 'default' );
?>

<!-- Top Bar -->
<?php if ( tevo_get_option( 'tevo_topbar_enable', true ) ) : ?>
<div id="tevo-topbar" class="tevo-topbar">
	<div class="tevo-container">
		<div class="tevo-topbar__inner">
			<div class="tevo-topbar__left">
				<?php if ( tevo_get_option( 'tevo_topbar_phone', '+1 (800) 123-4567' ) ) : ?>
					<a href="tel:<?php echo esc_attr( tevo_get_option( 'tevo_topbar_phone', '+1 (800) 123-4567' ) ); ?>" class="tevo-topbar__item">
						<?php echo tevo_get_svg_icon( 'phone', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG is hardcoded. ?>
						<span><?php echo esc_html( tevo_get_option( 'tevo_topbar_phone', '+1 (800) 123-4567' ) ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( tevo_get_option( 'tevo_topbar_email', 'info@tevo-insurance.com' ) ) : ?>
					<a href="mailto:<?php echo esc_attr( tevo_get_option( 'tevo_topbar_email', 'info@tevo-insurance.com' ) ); ?>" class="tevo-topbar__item">
						<?php echo tevo_get_svg_icon( 'mail', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php echo esc_html( tevo_get_option( 'tevo_topbar_email', 'info@tevo-insurance.com' ) ); ?></span>
					</a>
				<?php endif; ?>
			</div>
			<div class="tevo-topbar__right">
				<?php if ( tevo_get_option( 'tevo_topbar_hours', 'Mon - Fri: 9:00 AM - 6:00 PM' ) ) : ?>
					<span class="tevo-topbar__item">
						<?php echo tevo_get_svg_icon( 'clock', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php echo esc_html( tevo_get_option( 'tevo_topbar_hours', 'Mon - Fri: 9:00 AM - 6:00 PM' ) ); ?></span>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<!-- Main Header -->
<header id="tevo-header" class="tevo-header tevo-header--<?php echo esc_attr( $header_style ); ?>">
	<div class="tevo-container">
		<div class="tevo-header__inner">
			<!-- Logo -->
			<div class="tevo-header__logo">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="tevo-logo-text" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
					<?php
					$description = get_bloginfo( 'description', 'display' );
					if ( $description ) :
						?>
						<p class="tevo-logo-tagline"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<!-- Navigation -->
			<nav id="tevo-nav" class="tevo-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'tevo' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'tevo-nav__list',
					'menu_id'        => 'tevo-primary-menu',
					'walker'         => new Tevo_Walker_Nav(),
					'fallback_cb'    => 'tevo_fallback_menu',
					'depth'          => 3,
				) );
				?>
			</nav>

			<!-- Header Actions -->
			<div class="tevo-header__actions">
				<!-- Search Toggle -->
				<button id="tevo-search-toggle" class="tevo-header__action-btn" aria-label="<?php esc_attr_e( 'Toggle Search', 'tevo' ); ?>">
					<?php echo tevo_get_svg_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>

				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<!-- Mini Cart -->
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="tevo-header__action-btn tevo-mini-cart-toggle" aria-label="<?php esc_attr_e( 'View Cart', 'tevo' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="m1 1 4 2 2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
					<span class="tevo-cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
				</a>
				<?php endif; ?>

				<!-- CTA Button -->
				<?php if ( tevo_get_option( 'tevo_header_cta_text', 'Get a Quote' ) ) : ?>
					<a href="<?php echo esc_url( tevo_get_option( 'tevo_header_cta_link', '#' ) ); ?>" class="tevo-btn tevo-btn--primary tevo-header__cta">
						<?php echo esc_html( tevo_get_option( 'tevo_header_cta_text', 'Get a Quote' ) ); ?>
					</a>
				<?php endif; ?>

				<!-- Mobile Menu Toggle -->
				<button id="tevo-mobile-toggle" class="tevo-mobile-toggle" aria-label="<?php esc_attr_e( 'Toggle Mobile Menu', 'tevo' ); ?>" aria-expanded="false">
					<span class="tevo-mobile-toggle__bar"></span>
					<span class="tevo-mobile-toggle__bar"></span>
					<span class="tevo-mobile-toggle__bar"></span>
				</button>
			</div>
		</div>
	</div>

	<!-- Search Overlay -->
	<div id="tevo-search-overlay" class="tevo-search-overlay" aria-hidden="true">
		<div class="tevo-container">
			<form role="search" method="get" class="tevo-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" class="tevo-search-form__input" placeholder="<?php esc_attr_e( 'Search...', 'tevo' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" aria-label="<?php esc_attr_e( 'Search', 'tevo' ); ?>" />
				<button type="submit" class="tevo-search-form__submit" aria-label="<?php esc_attr_e( 'Submit Search', 'tevo' ); ?>">
					<?php echo tevo_get_svg_icon( 'search', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
			</form>
			<button id="tevo-search-close" class="tevo-search-overlay__close" aria-label="<?php esc_attr_e( 'Close Search', 'tevo' ); ?>">&times;</button>
		</div>
	</div>
</header>

<?php tevo_after_header(); ?>

<main id="tevo-main" class="tevo-main">
