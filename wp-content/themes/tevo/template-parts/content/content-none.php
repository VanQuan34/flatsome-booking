<?php
/**
 * Template part for displaying a message when no posts are found.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="tevo-no-results">
	<h2 class="tevo-no-results__title"><?php esc_html_e( 'Nothing Found', 'tevo' ); ?></h2>

	<?php if ( is_search() ) : ?>
		<p class="tevo-no-results__text">
			<?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'tevo' ); ?>
		</p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p class="tevo-no-results__text">
			<?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'tevo' ); ?>
		</p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</section>
