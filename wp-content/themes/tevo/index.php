<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

tevo_page_title( array(
	'title' => esc_html__( 'Blog', 'tevo' ),
) );
?>

<div class="tevo-container">
	<div class="tevo-content-area">
		<div class="tevo-content">
			<?php tevo_before_content(); ?>

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

			<?php tevo_after_content(); ?>
		</div>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php
get_footer();
