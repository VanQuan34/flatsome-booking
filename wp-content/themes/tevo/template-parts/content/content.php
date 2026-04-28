<?php
/**
 * Template part for displaying post content in index/archive views.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'tevo-post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="tevo-post-card__thumb">
			<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'tevo-blog-thumb', array( 'class' => 'tevo-post-card__img' ) ); ?>
			</a>
			<div class="tevo-post-card__category">
				<?php the_category( ', ' ); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="tevo-post-card__content">
		<div class="tevo-post-card__meta">
			<span class="tevo-post-card__date">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
			</span>
			<span class="tevo-post-card__author">
				<?php
				printf(
					/* translators: %s: Author name. */
					esc_html__( 'by %s', 'tevo' ),
					'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
				);
				?>
			</span>
			<span class="tevo-post-card__reading-time">
				<?php
				printf(
					/* translators: %d: Number of minutes. */
					esc_html__( '%d min read', 'tevo' ),
					absint( tevo_get_reading_time() )
				);
				?>
			</span>
		</div>

		<h2 class="tevo-post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<div class="tevo-post-card__excerpt">
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="tevo-post-card__readmore">
			<?php esc_html_e( 'Read More', 'tevo' ); ?>
			<?php echo tevo_get_svg_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</div>
</article>
