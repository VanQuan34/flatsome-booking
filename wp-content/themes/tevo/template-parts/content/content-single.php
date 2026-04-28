<?php
/**
 * Template part for displaying single post content.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'tevo-single-post' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="tevo-single-post__thumbnail">
			<?php the_post_thumbnail( 'tevo-blog-large', array( 'class' => 'tevo-single-post__img' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="tevo-single-post__meta">
		<span class="tevo-single-post__date">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>
		</span>
		<span class="tevo-single-post__author">
			<?php
			printf(
				/* translators: %s: Author name. */
				esc_html__( 'by %s', 'tevo' ),
				'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
			);
			?>
		</span>
		<span class="tevo-single-post__categories">
			<?php the_category( ', ' ); ?>
		</span>
		<span class="tevo-single-post__reading-time">
			<?php
			printf(
				/* translators: %d: Number of minutes. */
				esc_html__( '%d min read', 'tevo' ),
				absint( tevo_get_reading_time() )
			);
			?>
		</span>
	</div>

	<div class="tevo-single-post__content entry-content">
		<?php
		the_content( sprintf(
			wp_kses(
				/* translators: %s: Post title. */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'tevo' ),
				array( 'span' => array( 'class' => array() ) )
			),
			wp_kses_post( get_the_title() )
		) );

		wp_link_pages( array(
			'before' => '<div class="tevo-page-links">' . esc_html__( 'Pages:', 'tevo' ),
			'after'  => '</div>',
		) );
		?>
	</div>

	<div class="tevo-single-post__footer">
		<?php
		$tags_list = get_the_tag_list( '', ', ' );
		if ( $tags_list ) :
			?>
			<div class="tevo-single-post__tags">
				<strong><?php esc_html_e( 'Tags:', 'tevo' ); ?></strong>
				<?php echo wp_kses_post( $tags_list ); ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/components/social-share' ); ?>
	</div>

	<!-- Author Box -->
	<div class="tevo-author-box">
		<div class="tevo-author-box__avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
		</div>
		<div class="tevo-author-box__info">
			<h4 class="tevo-author-box__name">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
					<?php the_author(); ?>
				</a>
			</h4>
			<p class="tevo-author-box__bio"><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>
		</div>
	</div>
</article>
