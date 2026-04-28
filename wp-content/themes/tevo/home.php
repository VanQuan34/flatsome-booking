<?php
/**
 * The template for displaying the blog home page.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

tevo_page_title( array(
	'title' => esc_html__( 'Latest News & Articles', 'tevo' ),
) );
?>

<div class="tevo-container">
	<div class="tevo-content-area">
		<div class="tevo-content">
			<?php if ( have_posts() ) : ?>
				<div class="tevo-posts-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/content' );
					endwhile;
					?>
				</div>

				<?php get_template_part( 'template-parts/components/pagination' ); ?>

			<?php else : ?>
				<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php
get_footer();
