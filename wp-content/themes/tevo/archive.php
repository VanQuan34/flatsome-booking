<?php
/**
 * The template for displaying archive pages.
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
			<?php if ( have_posts() ) : ?>
				<?php the_archive_description( '<div class="tevo-archive-description">', '</div>' ); ?>

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
