<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sidebar_position = tevo_get_option( 'tevo_sidebar_position', 'right' );

if ( 'none' === $sidebar_position ) {
	return;
}

// Use shop sidebar for WooCommerce pages.
$sidebar_id = 'sidebar-main';
if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
	$sidebar_id = 'sidebar-shop';
}

if ( ! is_active_sidebar( $sidebar_id ) ) {
	return;
}

tevo_before_sidebar();
?>

<aside id="tevo-sidebar" class="tevo-sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'tevo' ); ?>">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</aside>

<?php
tevo_after_sidebar();
