<?php
/**
 * Template part for pagination.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

the_posts_pagination( array(
	'mid_size'  => 2,
	'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg> ' . esc_html__( 'Previous', 'tevo' ),
	'next_text' => esc_html__( 'Next', 'tevo' ) . ' <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>',
	'class'     => 'tevo-pagination',
) );
