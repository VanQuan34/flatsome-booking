<?php
/**
 * Template part for displaying page content.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'tevo-page-content' ); ?>>
	<div class="tevo-page-content__body entry-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="tevo-page-links">' . esc_html__( 'Pages:', 'tevo' ),
			'after'  => '</div>',
		) );
		?>
	</div>
</article>
