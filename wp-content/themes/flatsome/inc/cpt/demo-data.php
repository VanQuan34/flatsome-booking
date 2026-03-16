<?php
/**
 * Room Booking Demo Data
 * Path: flatsome/inc/cpt/demo-data.php
 * 
 * Run by visiting: your-site.com/?create_room_demo=1
 */

function flatsome_create_room_demo_data() {
    if ( ! isset( $_GET['create_room_demo'] ) || ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $rooms = array(
        array(
            'title'    => 'Phòng Deluxe Hướng Biển',
            'content'  => 'Phòng Deluxe với tầm nhìn tuyệt đẹp ra biển, trang bị đầy đủ tiện nghi hiện đại.',
            'price'    => '1500000',
            'status'   => 'available',
            'quantity' => '5',
            'adults'   => '2',
            'children' => '1',
            'extra_beds' => '1',
            'size'     => '35',
            'bed'      => 'King Size',
            'view'     => 'Sea View',
            'min_stay' => '1',
            'checkin'  => '14:00',
            'checkout' => '12:00',
            'video'    => 'https://www.youtube.com/watch?v=demo1',
            'type'     => 'Phòng Deluxe',
            'amenities' => array( 'Wifi', 'Điều hòa', 'Mini Bar', 'Ban công' )
        ),
        array(
            'title'    => 'Phòng Suite Hoàng Gia',
            'content'  => 'Trải nghiệm không gian sang trọng bậc nhất với phòng Suite đầy đủ khu vực phòng khách và làm việc.',
            'price'    => '3500000',
            'status'   => 'available',
            'quantity' => '2',
            'adults'   => '2',
            'children' => '0',
            'extra_beds' => '0',
            'size'     => '65',
            'bed'      => 'Super King Size',
            'view'     => 'City & Garden View',
            'min_stay' => '1',
            'checkin'  => '14:00',
            'checkout' => '12:00',
            'video'    => 'https://www.youtube.com/watch?v=demo2',
            'type'     => 'Phòng Suite',
            'amenities' => array( 'Wifi', 'Điều hòa', 'Bể bơi riêng', 'Bồn tắm' )
        ),
        array(
            'title'    => 'Phòng Gia Đình Family Connection',
            'content'  => 'Không gian rộng rãi cho cả gia đình với 2 phòng ngủ thông nhau.',
            'price'    => '2800000',
            'status'   => 'available',
            'quantity' => '3',
            'adults'   => '4',
            'children' => '2',
            'extra_beds' => '1',
            'size'     => '55',
            'bed'      => '2 Queen Beds',
            'view'     => 'Pool View',
            'min_stay' => '2',
            'checkin'  => '14:00',
            'checkout' => '11:00',
            'video'    => 'https://www.youtube.com/watch?v=demo3',
            'type'     => 'Phòng Gia đình',
            'amenities' => array( 'Wifi', 'Điều hòa', 'Bếp nấu', 'Tivi 4K' )
        ),
    );

    foreach ( $rooms as $room ) {
        // 1. Create Post
        $post_id = wp_insert_post( array(
            'post_title'   => $room['title'],
            'post_content' => $room['content'],
            'post_status'  => 'publish',
            'post_type'    => 'room_booking',
        ) );

        if ( $post_id ) {
            // 2. Add Meta Data
            $meta_map = array(
                '_room_price'     => $room['price'],
                '_room_status'    => $room['status'],
                '_room_quantity'  => $room['quantity'],
                '_max_adults'     => $room['adults'],
                '_max_children'   => $room['children'],
                '_extra_beds'     => $room['extra_beds'],
                '_room_size'      => $room['size'],
                '_bed_type'       => $room['bed'],
                '_room_view'       => $room['view'],
                '_min_stay'       => $room['min_stay'],
                '_checkin_time'   => $room['checkin'],
                '_checkout_time'  => $room['checkout'],
                '_room_video'     => $room['video'],
            );

            foreach ( $meta_map as $key => $val ) {
                update_post_meta( $post_id, $key, $val );
            }
            
            // 3. Set Taxonomy: Room Type
            wp_set_object_terms( $post_id, $room['type'], 'room_type' );

            // 4. Set Taxonomy: Room Amenities
            wp_set_object_terms( $post_id, $room['amenities'], 'room_amenity' );
        }
    }

    wp_die( 'Đã tạo xong 3 phòng demo! Hãy vào danh sách phòng để kiểm tra.' );
}
add_action( 'init', 'flatsome_create_room_demo_data' );
