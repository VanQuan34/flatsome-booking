<?php
/**
 * CPT Loader
 * Path: flatsome/inc/cpt/init.php
 */

// Include registration logic
require_once get_template_directory() . '/inc/cpt/room-booking.php';

// Include Flatsome integration
require_once get_template_directory() . '/inc/cpt/flatsome-ux.php';

// Include Custom Meta Boxes
require_once get_template_directory() . '/inc/cpt/meta-boxes.php';

// Include Shortcodes
require_once get_template_directory() . '/inc/cpt/shortcodes.php';

// Include Demo Data Script (Optional)
require_once get_template_directory() . '/inc/cpt/demo-data.php';

// You can add more files here (e.g., meta-boxes.php, shortcodes.php)
