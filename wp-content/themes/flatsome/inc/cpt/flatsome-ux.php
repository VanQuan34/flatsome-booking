<?php
/**
 * Flatsome UX Builder Integration
 * Path: flatsome/inc/cpt/flatsome-ux.php
 */

// Enable UX Builder for Room Booking CPT
add_filter('flatsome_custom_post_types', function($post_types) {
    if ( ! in_array( 'room_booking', $post_types ) ) {
        $post_types[] = 'room_booking';
    }
    return $post_types;
});

// Optional: Add custom template support or integrations for Flatsome here
