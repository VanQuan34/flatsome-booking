<?php
/**
 * Template part for the page title / hero section.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title      = isset( $args['title'] ) ? $args['title'] : get_the_title();
$subtitle   = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$show_bread = isset( $args['show_bread'] ) ? $args['show_bread'] : true;
?>

<section class="tevo-page-title">
	<div class="tevo-page-title__overlay"></div>
	<div class="tevo-container">
		<div class="tevo-page-title__content">
			<h1 class="tevo-page-title__heading"><?php echo wp_kses_post( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="tevo-page-title__subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
			<?php if ( $show_bread ) : ?>
				<?php tevo_breadcrumb(); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
