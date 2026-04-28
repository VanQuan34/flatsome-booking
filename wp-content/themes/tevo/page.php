<?php
/**
 * The template for displaying pages.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	// Check if built with Elementor.
	if ( tevo_is_elementor_active() && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor() ) {
		the_content();
	} else {
		tevo_page_title();
		?>
		<div class="tevo-container">
			<div class="tevo-content-area tevo-content-area--no-sidebar">
				<div class="tevo-content">
					<?php get_template_part( 'template-parts/content/content', 'page' ); ?>

					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
				</div>
			</div>
		</div>
		<?php
	}
endwhile;

get_footer();
