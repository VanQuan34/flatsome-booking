<?php
/**
 * The footer for our theme.
 *
 * @package Tevo
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main><!-- #tevo-main -->

<?php tevo_before_footer(); ?>

<footer id="tevo-footer" class="tevo-footer">
	<!-- Footer Widgets Area -->
	<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
	<div class="tevo-footer__widgets">
		<div class="tevo-container">
			<div class="tevo-footer__widgets-grid">
				<?php
				$footer_columns = absint( tevo_get_option( 'tevo_footer_columns', 4 ) );
				if ( $footer_columns < 1 ) {
					$footer_columns = 4;
				}

				for ( $i = 1; $i <= $footer_columns; $i++ ) :
					if ( is_active_sidebar( 'footer-' . $i ) ) :
						?>
						<div class="tevo-footer__col tevo-footer__col--<?php echo esc_attr( $i ); ?>">
							<?php dynamic_sidebar( 'footer-' . $i ); ?>
						</div>
						<?php
					endif;
				endfor;
				?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- Footer Bottom / Copyright -->
	<div class="tevo-footer__bottom">
		<div class="tevo-container">
			<div class="tevo-footer__bottom-inner">
				<div class="tevo-footer__copyright">
					<?php
					$copyright = tevo_get_option(
						'tevo_footer_copyright',
						/* translators: 1: Current year, 2: Site name. */
						sprintf( esc_html__( '&copy; %1$s %2$s. All Rights Reserved.', 'tevo' ), date_i18n( 'Y' ), get_bloginfo( 'name' ) )
					);
					echo wp_kses_post( $copyright );
					?>
				</div>
				<div class="tevo-footer__menu">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'tevo-footer__menu-list',
						'depth'          => 1,
						'fallback_cb'    => false,
					) );
					?>
				</div>
			</div>
		</div>
	</div>
</footer>

<?php tevo_after_footer(); ?>

<?php wp_footer(); ?>
</body>
</html>
