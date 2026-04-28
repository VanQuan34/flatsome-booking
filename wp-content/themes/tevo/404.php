<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="tevo-404">
	<div class="tevo-container">
		<div class="tevo-404__content">
			<div class="tevo-404__icon">
				<?php echo tevo_get_svg_icon( 'shield', 80 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<h1 class="tevo-404__title"><?php esc_html_e( '404', 'tevo' ); ?></h1>
			<h2 class="tevo-404__subtitle"><?php esc_html_e( 'Page Not Found', 'tevo' ); ?></h2>
			<p class="tevo-404__text">
				<?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'tevo' ); ?>
			</p>

			<div class="tevo-404__search">
				<?php get_search_form(); ?>
			</div>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="tevo-btn tevo-btn--primary">
				<?php echo tevo_get_svg_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php esc_html_e( 'Back to Homepage', 'tevo' ); ?>
			</a>
		</div>
	</div>
</section>

<?php
get_footer();
