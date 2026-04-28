<?php
/**
 * Template for displaying archive of insurance services.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

tevo_page_title( array(
	'title' => esc_html__( 'Our Insurance Services', 'tevo' ),
) );
?>

<div class="tevo-container">
	<div class="tevo-content-area tevo-content-area--no-sidebar">
		<div class="tevo-content">
			<?php if ( have_posts() ) : ?>
				<div class="tevo-services-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:30px;">
					<?php
					while ( have_posts() ) :
						the_post();
						$icon = get_post_meta( get_the_ID(), '_tevo_service_icon', true );
						?>
						<div class="tevo-insurance-card">
							<?php if ( $icon ) : ?>
								<div class="tevo-insurance-card__icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></div>
							<?php endif; ?>
							<h3 class="tevo-insurance-card__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<p class="tevo-insurance-card__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<a href="<?php the_permalink(); ?>" class="tevo-insurance-card__btn">
								<?php esc_html_e( 'Learn More', 'tevo' ); ?> &rarr;
							</a>
						</div>
					<?php endwhile; ?>
				</div>

				<?php get_template_part( 'template-parts/components/pagination' ); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
get_footer();
