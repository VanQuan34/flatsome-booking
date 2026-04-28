<?php
/**
 * The template for displaying the front page.
 *
 * When Elementor is active and a page is built with Elementor,
 * this template provides the Elementor content. Otherwise,
 * it displays a default front page.
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	// Check if this page is built with Elementor.
	if ( tevo_is_elementor_active() && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor() ) {
		the_content();
	} else {
		// Default front page content.
		?>
		<div class="tevo-container">
			<div class="tevo-front-page">
				<?php the_content(); ?>
			</div>
		</div>
		<?php
	}
endwhile;

get_footer();
