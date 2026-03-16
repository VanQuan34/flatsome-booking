<?php
/**
 * Room Booking Registration
 * Path: flatsome/inc/cpt/room-booking.php
 */

function flatsome_register_room_booking_cpt() {
    $labels = array(
        'name'                  => 'Phòng',
        'singular_name'         => 'Phòng',
        'menu_name'             => 'Room Booking',
        'add_new'               => 'Thêm phòng mới',
        'add_new_item'          => 'Thêm phòng mới',
        'edit_item'             => 'Chỉnh sửa phòng',
        'new_item'              => 'Phòng mới',
        'view_item'             => 'Xem phòng',
        'all_items'             => 'Tất cả phòng',
        'search_items'          => 'Tìm kiếm phòng',
        'not_found'             => 'Không tìm thấy phòng nào',
        'not_found_in_trash'    => 'Không có phòng nào trong thùng rác',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_rest'       => true, 
        'menu_icon'          => 'dashicons-admin-home',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author', 'comments' ),
        'rewrite'            => array( 'slug' => 'rooms' ),
        'hierarchical'       => false,
        'menu_position'      => 5,
    );

    register_post_type( 'room_booking', $args );

    // Register Post Type: Room Order (Booking Dashboard)
    $order_labels = array(
        'name'                  => 'Đơn đặt phòng',
        'singular_name'         => 'Đơn đặt phòng',
        'menu_name'             => 'Đơn đặt phòng',
        'all_items'             => 'Tất cả đơn hàng',
        'add_new'               => 'Thêm đơn mới',
        'add_new_item'          => 'Thêm đơn mới',
        'edit_item'             => 'Xem chi tiết đơn',
        'search_items'          => 'Tìm đơn hàng',
    );

    $order_args = array(
        'labels'             => $order_labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => 'edit.php?post_type=room_booking', // Put it inside Room Booking menu
        'supports'           => array( 'title' ), // Only title, other data in meta
        'menu_icon'          => 'dashicons-list-view',
        'capability_type'    => 'post',
        'capabilities'       => array( 'create_posts' => false ), // Prevent manual creation if preferred
        'map_meta_cap'       => true,
    );

    register_post_type( 'room_order', $order_args );

    // Taxonomy: Room Type
    register_taxonomy( 'room_type', 'room_booking', array(
        'label'        => 'Loại phòng',
        'rewrite'      => array( 'slug' => 'room-type' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    // Taxonomy: Room Amenity
    register_taxonomy( 'room_amenity', 'room_booking', array(
        'label'        => 'Tiện ích phòng',
        'rewrite'      => array( 'slug' => 'room-amenity' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'flatsome_register_room_booking_cpt' );

/**
 * Add Comments Submenu to Room Booking
 */
function flatsome_room_booking_comments_menu() {
    add_submenu_page(
        'edit.php?post_type=room_booking',
        'Bình luận',
        'Bình luận',
        'edit_posts',
        'edit-comments.php?post_type=room_booking'
    );
}
add_action( 'admin_menu', 'flatsome_room_booking_comments_menu' );

/**
 * Force Comments Open for Room Booking
 */
function flatsome_force_comments_open( $open, $post_id ) {
    $post = get_post( $post_id );
    if ( $post->post_type == 'room_booking' ) {
        return true;
    }
    return $open;
}
add_filter( 'comments_open', 'flatsome_force_comments_open', 10, 2 );

/**
 * Save Comment Rating
 */
function flatsome_save_comment_rating( $comment_id ) {
    if ( isset( $_POST['rating'] ) && ! empty( $_POST['rating'] ) ) {
        $rating = intval( $_POST['rating'] );
        add_comment_meta( $comment_id, 'rating', $rating );
    }
}
add_action( 'comment_post', 'flatsome_save_comment_rating' );

/**
 * Get Average Rating for a Room
 */
function flatsome_get_average_rating( $post_id ) {
    $comments = get_comments( array(
        'post_id' => $post_id,
        'status'  => 'approve',
    ) );

    if ( ! $comments ) {
        return 4.5; // Default rating
    }

    $total_rating = 0;
    $count = 0;

    foreach ( $comments as $comment ) {
        $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
        if ( $rating ) {
            $total_rating += intval( $rating );
            $count++;
        }
    }

    if ( $count == 0 ) {
        return 4.5;
    }

    return round( $total_rating / $count, 1 );
}

/**
 * Add Rating Field to Comment Form
 */
function flatsome_add_rating_field_to_comment_form() {
    ?>
    <div class="comment-form-rating mb-1">
        <label for="rating">Đánh giá của bạn <span class="required">*</span></label>
        <div class="star-rating-input">
            <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="5 stars"></label>
            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars"></label>
            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars"></label>
            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars"></label>
            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"></label>
        </div>
    </div>
    <?php
}
add_action( 'comment_form_logged_in_after', 'flatsome_add_rating_field_to_comment_form' );
add_action( 'comment_form_after_fields', 'flatsome_add_rating_field_to_comment_form' );

/**
 * Display Rating in Comment Text
 */
function flatsome_display_rating_in_comment( $comment_text, $comment ) {
    $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
    if ( $rating && is_singular( 'room_booking' ) ) {
        $stars = '<div class="comment-rating mb-half" style="font-size: 1.1em; line-height: 1;">';
        for ( $i = 1; $i <= 5; $i++ ) {
            $stars .= '<span class="star' . ( $i <= $rating ? ' filled' : '' ) . '" style="color: ' . ( $i <= $rating ? '#f1c40f' : '#ccc' ) . ';">&#9733;</span>';
        }
        $stars .= '</div>';
        $comment_text = $stars . $comment_text;
    }
    return $comment_text;
}
add_filter( 'get_comment_text', 'flatsome_display_rating_in_comment', 10, 2 );

/**
 * Filter Room Booking Query on Archive Page
 */
function flatsome_room_booking_filter_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! is_post_type_archive( 'room_booking' ) ) {
        return;
    }

    $meta_query = array();
    $tax_query  = array();

    // Filter by Keyword (Title)
    if ( ! empty( $_GET['room_kw'] ) ) {
        $query->set( 's', sanitize_text_field( $_GET['room_kw'] ) );
    }

    // Filter by Taxonomy: Room Type
    if ( ! empty( $_GET['type'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'room_type',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $_GET['type'] ),
        );
    }

    // Filter by Taxonomy: Amenity (from sidebar filters)
    if ( ! empty( $_GET['amenity'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'room_amenity',
            'field'    => 'slug',
            'terms'    => (array) $_GET['amenity'],
            'operator' => 'IN',
        );
    }

    // Filter by Meta: Adults
    if ( ! empty( $_GET['adults'] ) ) {
        $meta_query[] = array(
            'key'     => '_max_adults',
            'value'   => intval( $_GET['adults'] ),
            'compare' => '>=',
            'type'    => 'NUMERIC',
        );
    }

    // Filter by Meta: Children
    if ( ! empty( $_GET['children'] ) ) {
        $meta_query[] = array(
            'key'     => '_max_children',
            'value'   => intval( $_GET['children'] ),
            'compare' => '>=',
            'type'    => 'NUMERIC',
        );
    }

    if ( ! empty( $meta_query ) ) {
        $query->set( 'meta_query', $meta_query );
    }

    if ( ! empty( $tax_query ) ) {
        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'flatsome_room_booking_filter_query' );

/**
 * Custom Columns for Room Order Dashboard
 */
function flatsome_room_order_columns( $columns ) {
    $new_columns = array(
        'cb'            => $columns['cb'] ?? '',
        'title'         => 'Mã đơn',
        'guest_info'    => 'Khách hàng',
        'room_name'     => 'Phòng',
        'booking_dates' => 'Thời gian lưu trú',
        'paypal_id'     => 'Mã PayPal',
        'order_total'   => 'Tổng tiền',
        'date'          => $columns['date'] ?? '',
    );
    return $new_columns;
}
add_filter( 'manage_room_order_posts_columns', 'flatsome_room_order_columns' );

function flatsome_room_order_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'guest_info':
            echo '<strong>' . esc_html( get_post_meta( $post_id, '_guest_name', true ) ) . '</strong><br>';
            echo esc_html( get_post_meta( $post_id, '_guest_phone', true ) ) . '<br>';
            echo esc_html( get_post_meta( $post_id, '_guest_email', true ) );
            break;
        case 'room_name':
            $room_id = get_post_meta( $post_id, '_room_id', true );
            echo $room_id ? '<a href="' . get_edit_post_link( $room_id ) . '">' . get_the_title( $room_id ) . '</a>' : 'N/A';
            break;
        case 'booking_dates':
            $checkin  = get_post_meta( $post_id, '_checkin', true );
            $checkout = get_post_meta( $post_id, '_checkout', true );
            $nights   = get_post_meta( $post_id, '_total_nights', true );
            echo esc_html( $checkin ) . ' - ' . esc_html( $checkout ) . '<br>';
            echo '<small>' . $nights . ' đêm</small>';
            break;
        case 'paypal_id':
            $paypal_id = get_post_meta( $post_id, '_paypal_id', true );
            echo $paypal_id ? '<code style="background:#e7f3ff; padding:2px 5px; border-radius:3px;">' . esc_html( $paypal_id ) . '</code>' : '<span style="color:#999;">Thanh toán sau</span>';
            break;
        case 'order_total':
            $total = get_post_meta( $post_id, '_total_price', true );
            echo '<strong style="color: #d2691e;">' . number_format( $total ) . ' VNĐ</strong>';
            break;
    }
}
add_action( 'manage_room_order_posts_custom_column', 'flatsome_room_order_column_content', 10, 2 );

/**
 * Handle AJAX Booking Submission
 */
function flatsome_save_room_order_ajax() {
    $name     = sanitize_text_field( $_POST['name'] );
    $phone    = sanitize_text_field( $_POST['phone'] );
    $email    = sanitize_email( $_POST['email'] );
    $room_id  = intval( $_POST['room_id'] );
    $checkin  = sanitize_text_field( $_POST['checkin'] );
    $checkout = sanitize_text_field( $_POST['checkout'] );
    $rooms    = intval( $_POST['rooms'] );
    $adults   = intval( $_POST['adults'] );
    $children = intval( $_POST['children'] );
    $nights   = intval( $_POST['nights'] );
    $total    = floatval( $_POST['total'] );
    $paypal_id = sanitize_text_field( $_POST['paypal_id'] ?? '' );

    if ( empty( $name ) || empty( $phone ) ) {
        wp_send_json_error( array( 'message' => 'Họ tên và Số điện thoại là bắt buộc.' ) );
    }

    $post_title = 'Đơn hàng - ' . $name . ' - ' . date( 'd/m/Y H:i' );
    
    $order_id = wp_insert_post( array(
        'post_title'  => $post_title,
        'post_type'   => 'room_order',
        'post_status' => 'publish',
    ) );

    if ( $order_id ) {
        update_post_meta( $order_id, '_guest_name', $name );
        update_post_meta( $order_id, '_guest_phone', $phone );
        update_post_meta( $order_id, '_guest_email', $email );
        update_post_meta( $order_id, '_room_id', $room_id );
        update_post_meta( $order_id, '_checkin', $checkin );
        update_post_meta( $order_id, '_checkout', $checkout );
        update_post_meta( $order_id, '_rooms_count', $rooms );
        update_post_meta( $order_id, '_adults_count', $adults );
        update_post_meta( $order_id, '_children_count', $children );
        update_post_meta( $order_id, '_total_nights', $nights );
        update_post_meta( $order_id, '_total_price', $total );
        update_post_meta( $order_id, '_paypal_id', $paypal_id );

        wp_send_json_success( array( 'message' => 'Đặt phòng thành công!', 'order_id' => $order_id ) );
    } else {
        wp_send_json_error( array( 'message' => 'Có lỗi xảy ra khi lưu đơn hàng.' ) );
    }
}
add_action( 'wp_ajax_save_room_order', 'flatsome_save_room_order_ajax' );
add_action( 'wp_ajax_nopriv_save_room_order', 'flatsome_save_room_order_ajax' );
