<?php
/**
 * Template for displaying single insurance service.
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
	<div class="tevo-content-area tevo-content-area--no-sidebar">
		<div class="tevo-content">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'tevo-single-service' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="tevo-single-service__thumbnail">
							<?php the_post_thumbnail( 'tevo-blog-large', array( 'class' => 'tevo-single-service__img' ) ); ?>
						</div>
					<?php endif; ?>

					<div class="tevo-single-service__content entry-content">
						<?php the_content(); ?>
					</div>

					<?php
					// Service details from meta.
					$price    = get_post_meta( get_the_ID(), '_tevo_service_price', true );
					$features = get_post_meta( get_the_ID(), '_tevo_service_features', true );

					if ( $price || $features ) :
						?>
						<div class="tevo-single-service__details">
							<?php if ( $price ) : ?>
								<div class="tevo-single-service__price">
									<strong><?php esc_html_e( 'Starting from:', 'tevo' ); ?></strong>
									<span><?php echo esc_html( $price ); ?></span>
								</div>
							<?php endif; ?>

							<?php if ( $features ) : ?>
								<div class="tevo-single-service__features">
									<h3><?php esc_html_e( 'Key Features', 'tevo' ); ?></h3>
									<ul>
										<?php
										$feature_lines = explode( "\n", $features );
										foreach ( $feature_lines as $feature ) :
											$feature = trim( $feature );
											if ( ! empty( $feature ) ) :
												?>
												<li>
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
													<?php echo esc_html( $feature ); ?>
												</li>
												<?php
											endif;
										endforeach;
										?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<!-- CTA -->
					<div class="tevo-single-service__cta">
						<div class="tevo-cta">
							<h3 class="tevo-cta__title"><?php esc_html_e( 'Interested in this coverage?', 'tevo' ); ?></h3>
							<p class="tevo-cta__desc"><?php esc_html_e( 'Contact us today for a personalized quote.', 'tevo' ); ?></p>
							<a href="<?php echo esc_url( tevo_get_option( 'tevo_header_cta_link', '#' ) ); ?>" class="tevo-btn tevo-btn--secondary">
								<?php esc_html_e( 'Get a Quote', 'tevo' ); ?>
							</a>
						</div>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</div>
	</div>
</div>

<?php
get_footer();
