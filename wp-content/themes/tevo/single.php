<?php
/**
 * The template for displaying single posts.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

tevo_page_title();
?>

<div class="tevo-container">
	<div class="tevo-content-area">
		<div class="tevo-content">
			<?php
			tevo_before_single_post();

			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/content', 'single' );
			endwhile;

			tevo_after_single_post();

			// Post navigation.
			the_post_navigation( array(
				'prev_text' => '<span class="tevo-post-nav__label">' . esc_html__( 'Previous Post', 'tevo' ) . '</span><span class="tevo-post-nav__title">%title</span>',
				'next_text' => '<span class="tevo-post-nav__label">' . esc_html__( 'Next Post', 'tevo' ) . '</span><span class="tevo-post-nav__title">%title</span>',
			) );

			// Comments.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</div>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php
get_footer();
