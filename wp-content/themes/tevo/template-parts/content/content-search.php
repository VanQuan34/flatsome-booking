<?php
/**
 * Template part for displaying search results.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'tevo-search-result' ); ?>>
	<h2 class="tevo-search-result__title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
	<div class="tevo-search-result__meta">
		<span><?php echo esc_html( get_the_date() ); ?></span>
		<span><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
	</div>
	<div class="tevo-search-result__excerpt">
		<?php the_excerpt(); ?>
	</div>
</article>
