<?php
/**
 * Custom Meta Boxes for Room Booking
 * Path: flatsome/inc/cpt/meta-boxes.php
 */

function flatsome_room_booking_add_meta_boxes() {
    add_meta_box(
        'room_details_meta_box',
        'Thông tin chi tiết phòng',
        'flatsome_room_booking_meta_box_callback',
        'room_booking',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'flatsome_room_booking_add_meta_boxes' );

function flatsome_room_booking_meta_box_callback( $post ) {
    // Thêm nonce để bảo mật
    wp_nonce_field( 'flatsome_room_booking_save_meta', 'flatsome_room_booking_meta_nonce' );

    // Lấy dữ liệu đã lưu
    $fields = array(
        '_room_price', '_room_status', '_room_quantity',
        '_max_adults', '_max_children', '_extra_beds',
        '_room_size', '_bed_type', '_room_view',
        '_min_stay', '_checkin_time', '_checkout_time',
        '_room_gallery', '_room_video', '_room_address', '_room_map_url'
    );

    $data = array();
    foreach ( $fields as $field ) {
        $data[$field] = get_post_meta( $post->ID, $field, true );
    }

    ?>
    <style>
        .room-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 10px; }
        .room-meta-item { display: flex; flex-direction: column; }
        .room-meta-item label { font-weight: bold; margin-bottom: 5px; }
        .room-meta-item input, .room-meta-item select { padding: 8px; }
        .room-meta-section-title { grid-column: 1 / -1; background: #f0f0f0; padding: 5px 10px; margin-top: 10px; font-style: italic; }
    </style>

    <div class="room-meta-grid">
        <div class="room-meta-section-title">A. Nhóm Giá & Trạng thái</div>
        <div class="room-meta-item">
            <label>Giá mỗi đêm (_room_price)</label>
            <input type="number" name="_room_price" value="<?php echo esc_attr( $data['_room_price'] ); ?>">
        </div>
        <div class="room-meta-item">
            <label>Trạng thái (_room_status)</label>
            <select name="_room_status">
                <option value="available" <?php selected( $data['_room_status'], 'available' ); ?>>Available</option>
                <option value="booked" <?php selected( $data['_room_status'], 'booked' ); ?>>Booked</option>
                <option value="maintenance" <?php selected( $data['_room_status'], 'maintenance' ); ?>>Maintenance</option>
            </select>
        </div>
        <div class="room-meta-item">
            <label>Số lượng phòng trống (_room_quantity)</label>
            <input type="number" name="_room_quantity" value="<?php echo esc_attr( $data['_room_quantity'] ); ?>">
        </div>

        <div class="room-meta-section-title">B. Sức chứa</div>
        <div class="room-meta-item">
            <label>Người lớn tối đa (_max_adults)</label>
            <input type="number" name="_max_adults" value="<?php echo esc_attr( $data['_max_adults'] ); ?>">
        </div>
        <div class="room-meta-item">
            <label>Trẻ em tối đa (_max_children)</label>
            <input type="number" name="_max_children" value="<?php echo esc_attr( $data['_max_children'] ); ?>">
        </div>
        <div class="room-meta-item">
            <label>Số giường phụ tối đa (_extra_beds)</label>
            <input type="number" name="_extra_beds" value="<?php echo esc_attr( $data['_extra_beds'] ); ?>">
        </div>

        <div class="room-meta-section-title">C. Thông tin chi tiết phòng</div>
        <div class="room-meta-item">
            <label>Diện tích m² (_room_size)</label>
            <input type="text" name="_room_size" value="<?php echo esc_attr( $data['_room_size'] ); ?>">
        </div>
        <div class="room-meta-item">
            <label>Loại giường (_bed_type)</label>
            <input type="text" name="_bed_type" value="<?php echo esc_attr( $data['_bed_type'] ); ?>" placeholder="VD: King Size, Twin...">
        </div>
        <div class="room-meta-item">
            <label>Hướng nhìn (_room_view)</label>
            <input type="text" name="_room_view" value="<?php echo esc_attr( $data['_room_view'] ); ?>" placeholder="VD: Sea View, City View...">
        </div>

        <div class="room-meta-section-title">D. Quy định</div>
        <div class="room-meta-item">
            <label>Số đêm tối thiểu (_min_stay)</label>
            <input type="number" name="_min_stay" value="<?php echo esc_attr( $data['_min_stay'] ); ?>">
        </div>
        <div class="room-meta-item">
            <label>Giờ nhận phòng (_checkin_time)</label>
            <input type="time" name="_checkin_time" value="<?php echo esc_attr( $data['_checkin_time'] ); ?>">
        </div>
        <div class="room-meta-item">
            <label>Giờ trả phòng (_checkout_time)</label>
            <input type="time" name="_checkout_time" value="<?php echo esc_attr( $data['_checkout_time'] ); ?>">
        </div>

        <div class="room-meta-section-title">E. Truyền thông</div>
        <div class="room-meta-item" style="grid-column: 1 / -1;">
            <label>Ảnh chi tiết (Gallery - _room_gallery)</label>
            <div id="room_gallery_container">
                <ul class="room-gallery-list clearfix">
                    <?php
                    $gallery_ids = explode( ',', $data['_room_gallery'] );
                    if ( ! empty( $gallery_ids ) ) {
                        foreach ( $gallery_ids as $attachment_id ) {
                            $image = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
                            if ( $image ) {
                                echo '<li class="room-gallery-image" data-id="' . esc_attr( $attachment_id ) . '">
                                        <img src="' . esc_url( $image[0] ) . '" />
                                        <a href="#" class="remove-image">×</a>
                                      </li>';
                            }
                        }
                    }
                    ?>
                </ul>
                <input type="hidden" name="_room_gallery" id="room_gallery_input" value="<?php echo esc_attr( $data['_room_gallery'] ); ?>">
                <button type="button" class="button button-primary" id="add_room_gallery_btn">Thêm ảnh vào bộ sưu tập</button>
            </div>
            <p class="description">Click vào nút để chọn ảnh, kéo thả để sắp xếp vị trí và nhấn dấu × để xóa ảnh.</p>
        </div>
        <div class="room-meta-item" style="grid-column: 1 / -1;">
            <label>Video URL (_room_video)</label>
            <input type="url" name="_room_video" value="<?php echo esc_url( $data['_room_video'] ); ?>" style="width: 100%;">
        </div>

        <div class="room-meta-section-title">F. Vị trí & Bản đồ</div>
        <div class="room-meta-item" style="grid-column: 1 / -1;">
            <label>Địa chỉ hiển thị (_room_address)</label>
            <input type="text" name="_room_address" value="<?php echo esc_attr( $data['_room_address'] ); ?>" style="width: 100%;" placeholder="VD: 123 Đường ABC, Quận 1, TP. HCM">
        </div>
        <div class="room-meta-item" style="grid-column: 1 / -1;">
            <label>Mã nhúng Google Maps (Iframe code - _room_map_url)</label>
            <textarea name="_room_map_url" rows="4" style="width: 100%;" placeholder="Dán toàn bộ mã iframe hoặc URL từ Google Maps"><?php echo esc_textarea( $data['_room_map_url'] ); ?></textarea>
            <p class="description">Bạn có thể dán toàn bộ đoạn mã <code>&lt;iframe ...&gt;&lt;/iframe&gt;</code> lấy từ Google Maps (Chia sẻ -> Nhúng bản đồ).</p>
        </div>
    </div>

    <style>
        .room-gallery-list { list-style: none; padding: 0; margin: 0 0 10px 0; display: flex; flex-wrap: wrap; gap: 10px; }
        .room-gallery-image { width: 100px; height: 100px; position: relative; border: 1px solid #ddd; background: #f9f9f9; cursor: move; }
        .room-gallery-image img { width: 100%; height: 100%; object-fit: cover; }
        .room-gallery-image .remove-image { position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 20px; height: 20px; text-align: center; text-decoration: none; line-height: 18px; font-weight: bold; }
        .room-gallery-image .remove-image:hover { background: darkred; }
    </style>

    <script media="screen">
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('#add_room_gallery_btn').on('click', function(e) {
                e.preventDefault();
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                mediaUploader = wp.media({
                    title: 'Chọn ảnh cho bộ sưu tập phòng',
                    button: { text: 'Thêm vào bộ sưu tập' },
                    multiple: true
                });

                mediaUploader.on('select', function() {
                    var selections = mediaUploader.state().get('selection');
                    var ids = $('#room_gallery_input').val().split(',').filter(Boolean);

                    selections.map(function(attachment) {
                        attachment = attachment.toJSON();
                        if (ids.indexOf(attachment.id.toString()) === -1) {
                            ids.push(attachment.id);
                            $('.room-gallery-list').append(
                                '<li class="room-gallery-image" data-id="' + attachment.id + '">' +
                                '<img src="' + (attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" />' +
                                '<a href="#" class="remove-image">×</a>' +
                                '</li>'
                            );
                        }
                    });
                    $('#room_gallery_input').val(ids.join(','));
                });
                mediaUploader.open();
            });

            // Xóa ảnh
            $(document).on('click', '.remove-image', function(e) {
                e.preventDefault();
                var $li = $(this).closest('li');
                var id = $li.data('id').toString();
                var ids = $('#room_gallery_input').val().split(',').filter(Boolean);
                
                ids = ids.filter(function(v) { return v !== id; });
                $('#room_gallery_input').val(ids.join(','));
                $li.remove();
            });

            // Sắp xếp kéo thả (Dùng jQuery UI Sortable có sẵn trong WP Admin)
            if (typeof $.fn.sortable !== 'undefined') {
                $('.room-gallery-list').sortable({
                    update: function(event, ui) {
                        var ids = [];
                        $('.room-gallery-image').each(function() {
                            ids.push($(this).data('id'));
                        });
                        $('#room_gallery_input').val(ids.join(','));
                    }
                });
            }
        });
    </script>
    <?php
}

/**
 * Enqueue scripts cho Media Uploader trong Admin
 */
function flatsome_room_booking_admin_scripts($hook) {
    global $post_type;
    if ( 'room_booking' === $post_type && ( 'post.php' === $hook || 'post-new.php' === $hook ) ) {
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
    }
}
add_action( 'admin_enqueue_scripts', 'flatsome_room_booking_admin_scripts' );

function flatsome_room_booking_save_meta( $post_id ) {
    // Kiểm tra nonce
    if ( ! isset( $_POST['flatsome_room_booking_meta_nonce'] ) || ! wp_verify_nonce( $_POST['flatsome_room_booking_meta_nonce'], 'flatsome_room_booking_save_meta' ) ) {
        return;
    }

    // Kiểm tra quyền
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array(
        '_room_price', '_room_status', '_room_quantity',
        '_max_adults', '_max_children', '_extra_beds',
        '_room_size', '_bed_type', '_room_view',
        '_min_stay', '_checkin_time', '_checkout_time',
        '_room_gallery', '_room_video', '_room_address', '_room_map_url'
    );

    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            $value = $_POST[$field];
            if ( $field === '_room_map_url' ) {
                // Cho phép iframe trong trường map
                update_post_meta( $post_id, $field, wp_kses( $value, array(
                    'iframe' => array(
                        'src'             => true,
                        'width'           => true,
                        'height'          => true,
                        'style'           => true,
                        'allowfullscreen' => true,
                        'loading'         => true,
                        'referrerpolicy'  => true,
                    ),
                ) ) );
            } else {
                update_post_meta( $post_id, $field, sanitize_text_field( $value ) );
            }
        }
    }
}
add_action( 'save_post', 'flatsome_room_booking_save_meta' );
