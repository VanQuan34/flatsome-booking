<?php
/**
 * The template for displaying comments.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Do not load if the post cannot be commented on.
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="tevo-comments">

	<?php if ( have_comments() ) : ?>
		<h3 class="tevo-comments__title">
			<?php
			$tevo_comment_count = get_comments_number();
			if ( '1' === $tevo_comment_count ) {
				printf(
					/* translators: 1: Title of the post. */
					esc_html__( 'One comment on &ldquo;%1$s&rdquo;', 'tevo' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: Number of comments, 2: Post title. */
					esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $tevo_comment_count, 'comments title', 'tevo' ) ),
					number_format_i18n( $tevo_comment_count ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h3>

		<ol class="tevo-comments__list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 60,
			) );
			?>
		</ol>

		<?php
		the_comments_navigation( array(
			'prev_text' => esc_html__( '&laquo; Older Comments', 'tevo' ),
			'next_text' => esc_html__( 'Newer Comments &raquo;', 'tevo' ),
		) );
		?>

	<?php endif; ?>

	<?php
	// If comments are closed and there are comments, show a note.
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="tevo-comments__closed"><?php esc_html_e( 'Comments are closed.', 'tevo' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form( array(
		'class_form'    => 'tevo-comment-form',
		'class_submit'  => 'tevo-btn tevo-btn--primary',
		'title_reply'   => esc_html__( 'Leave a Comment', 'tevo' ),
	) );
	?>

</div>
