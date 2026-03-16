<?php
/**
 * Archive Room Booking Template
 * Path: flatsome/archive-room_booking.php
 */

get_header(); ?>

<div id="content" class="content-area page-wrapper" role="main">
    <div class="row row-main">
        <!-- Sidebar Filters -->
        <div class="large-3 col">
            <div id="room-sidebar" class="col-inner">
                <aside id="room_filters_widget" class="widget">
                    <h3 class="widget-title">Bộ lọc tìm kiếm</h3>
                    <!-- Litepicker CSS & JS -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
                    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

                    <form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'room_booking' ) ); ?>" class="room-filter-form">
                        
                        <!-- Checkin/Checkout (Litepicker) -->
                        <div class="filter-item mb-1">
                            <label class="uppercase font-bold">Thời gian</label>
                            <input type="text" id="sidebar-date-range" placeholder="Chọn ngày nhận - trả" readonly>
                            <input type="hidden" name="checkin" id="sidebar-checkin" value="<?php echo isset($_GET['checkin']) ? esc_attr($_GET['checkin']) : ''; ?>">
                            <input type="hidden" name="checkout" id="sidebar-checkout" value="<?php echo isset($_GET['checkout']) ? esc_attr($_GET['checkout']) : ''; ?>">
                        </div>

                        <!-- Room Type -->
                        <div class="filter-item mb-1">
                            <label class="uppercase font-bold">Loại phòng</label>
                            <select name="type" class="full-width">
                                <option value="">Tất cả loại phòng</option>
                                <?php 
                                $types = get_terms( 'room_type' );
                                foreach ( $types as $t ) : ?>
                                    <option value="<?php echo $t->slug; ?>" <?php selected( isset($_GET['type']) ? $_GET['type'] : '', $t->slug ); ?>><?php echo $t->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Amenities -->
                        <div class="filter-item mb-1">
                            <label class="uppercase font-bold">Tiện ích</label>
                            <div class="checkbox-list" style="max-height: 200px; overflow-y: auto; padding: 10px; border: 1px solid #eee;">
                                <?php 
                                $amenities = get_terms( 'room_amenity' );
                                $selected_amenities = isset($_GET['amenity']) ? (array)$_GET['amenity'] : array();
                                foreach ( $amenities as $a ) : ?>
                                    <label style="display: block; margin-bottom: 5px; font-weight: normal;">
                                        <input type="checkbox" name="amenity[]" value="<?php echo $a->slug; ?>" <?php checked( in_array($a->slug, $selected_amenities) ); ?>>
                                        <?php echo $a->name; ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Capacity & Rooms -->
                        <div class="filter-item mb-1">
                            <label class="uppercase font-bold">Số lượng phòng</label>
                            <select name="rooms" class="full-width">
                                <?php for($i=1; $i<=10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php selected( isset($_GET['rooms']) ? $_GET['rooms'] : '1', $i ); ?>><?php echo $i; ?> Phòng</option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="row row-small mb-1">
                            <div class="col large-6">
                                <label class="uppercase font-bold" style="font-size: 10px;">Người lớn</label>
                                <select name="adults" class="full-width">
                                    <?php for($i=1; $i<=20; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php selected( isset($_GET['adults']) ? $_GET['adults'] : '1', $i ); ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col large-6">
                                <label class="uppercase font-bold" style="font-size: 10px;">Trẻ em</label>
                                <select name="children" class="full-width">
                                    <?php for($i=0; $i<=10; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php selected( isset($_GET['children']) ? $_GET['children'] : '0', $i ); ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="button primary expand mt">LỌC KẾT QUẢ</button>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'room_booking' ) ); ?>" class="button is-link expand is-xsmall text-center mt-half">Xóa tất cả bộ lọc</a>
                    </form>
                </aside>

                <aside class="widget">
                    <h3 class="widget-title">Tại sao chọn chúng tôi?</h3>
                    <div class="is-divider small"></div>
                    <ul class="sidebar-info-list" style="list-style: none; padding: 0;">
                        <li><i class="icon-checkmark color-primary"></i> Giá tốt nhất thị trường</li>
                        <li><i class="icon-checkmark color-primary"></i> Đảm bảo chất lượng 5 sao</li>
                        <li><i class="icon-checkmark color-primary"></i> Hỗ trợ 24/7 nhiệt tình</li>
                    </ul>
                </aside>
            </div>
        </div>

        <div class="large-9 col">
            <div class="col-inner">
                
                <header class="archive-header mt-half mb">
                    <h1 class="archive-title uppercase">Kết quả tìm kiếm phòng</h1>
                </header>

                <?php if ( have_posts() ) : ?>
                    <div class="row room-list-container">
                        <?php 
                        // Prepare query args to carry over
                        $query_args = array();
                        $params_to_carry = array('checkin', 'checkout', 'type', 'rooms', 'adults', 'children');
                        foreach($params_to_carry as $param) {
                            if(isset($_GET[$param]) && !empty($_GET[$param])) {
                                $query_args[$param] = $_GET[$param];
                            }
                        }
                        
                        while ( have_posts() ) : the_post(); 
                            $post_id = get_the_ID();
                            $price    = (float) get_post_meta( $post_id, '_room_price', true );
                            $status   = get_post_meta( $post_id, '_room_status', true );
                            $size     = get_post_meta( $post_id, '_room_size', true );
                            $adults   = get_post_meta( $post_id, '_max_adults', true );
                            $children = get_post_meta( $post_id, '_max_children', true );
                            $bed      = get_post_meta( $post_id, '_bed_type', true );
                            $view     = get_post_meta( $post_id, '_room_view', true );
                            $amenities = get_the_terms( $post_id, 'room_amenity' );
                            
                            $room_link = get_the_permalink();
                            if(!empty($query_args)) {
                                $room_link = add_query_arg($query_args, $room_link);
                            }
                        ?>
                            <div class="col medium-6 small-12 mb-1">
                                <div class="col-inner">
                                    <div class="room-card box has-hover">
                                        <div class="box-image">
                                            <a href="<?php echo esc_url($room_link); ?>">
                                                <div class="image-cover" style="padding-top:60%;">
                                                    <?php 
                                                    if ( has_post_thumbnail() ) {
                                                        the_post_thumbnail('medium_large'); 
                                                    } else {
                                                        echo '<img src="https://placehold.jp/600x400.png" alt="No image" class="attachment-medium_large size-medium_large wp-post-image">';
                                                    }
                                                    ?>
                                                </div>
                                            </a>
                                            <?php if($status): ?>
                                                <div class="room-badge badge-<?php echo esc_attr($status); ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="box-text text-left" style="padding: 15px;">
                                            <h3 class="room-title" style="margin-top:0;"><a href="<?php echo esc_url($room_link); ?>"><?php the_title(); ?></a></h3>
                                            <div class="room-price-tag" style="color: #d2691e; font-weight: bold; font-size: 1.1em; margin-bottom: 15px;">
                                                <span class="amount"><?php echo number_format($price); ?> VNĐ</span> / đêm
                                            </div>
                                            <div class="room-features grid-info" style="display: flex; flex-wrap: wrap; gap: 10px; font-size: 0.9em; color: #666; margin-bottom: 10px;">
                                                <span><i class="icon-expand"></i> <?php echo $size; ?> m²</span>
                                                <span><i class="icon-user"></i> <?php echo $adults; ?> Lớn, <?php echo $children; ?> Nhỏ</span>
                                            </div>
                                            <p class="room-view" style="font-size: 0.85em; color: #2ecc71; margin-top: 5px;"><i class="icon-checkmark"></i> View: <?php echo $view; ?></p>
                                            <div class="room-amenities">
                                                <?php if($amenities): $count=0; foreach($amenities as $amenity): if($count++ < 3): ?>
                                                    <span class="amenity-tag" style="display: inline-block; background: #f1f1f1; padding: 2px 8px; border-radius: 12px; font-size: 0.75em; margin: 2px; color: #555;"><?php echo $amenity->name; ?></span>
                                                <?php endif; endforeach; endif; ?>
                                            </div>
                                            <a href="<?php echo esc_url($room_link); ?>" class="button primary is-outline is-small expand mt-1">Xem chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <?php 
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '<i class="icon-angle-left"></i>',
                        'next_text' => '<i class="icon-angle-right"></i>',
                    ) ); 
                    ?>
                <?php else : ?>
                    <p>Không tìm thấy phòng nào phù hợp với yêu cầu của bạn.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<style>
    .room-card { border: 1px solid #eee; border-radius: 8px; overflow: hidden; background: #fff; transition: transform 0.3s; }
    .room-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .room-badge { position: absolute; top: 10px; right: 10px; padding: 5px 10px; color: #fff; border-radius: 4px; font-size: 0.8em; z-index: 10; }
    .badge-available { background: #4CAF50; }
    .badge-booked { background: #f44336; }
    .badge-maintenance { background: #ff9800; }
    .widget-title { border-bottom: 2px solid #f1c40f; padding-bottom: 10px; margin-bottom: 20px; }
    .filter-item label { display: block; margin-bottom: 8px; }
    .full-width { width: 100%; }

    /* Ensure Flatpickr year is visible */
    .flatpickr-calendar .flatpickr-month { color: #333 !important; }
    .flatpickr-calendar .numInputWrapper span.arrowUp:after { border-bottom-color: #333 !important; }
    .flatpickr-calendar .numInputWrapper span.arrowDown:after { border-top-color: #333 !important; }
    .flatpickr-current-month .numInputWrapper input.cur-year { color: inherit !important; font-weight: bold !important; }
</style>

<script>
jQuery(document).ready(function($) {
    var sCheckin = $('#sidebar-checkin').val();
    var sCheckout = $('#sidebar-checkout').val();

    const sidebarPicker = new Litepicker({
        element: document.getElementById('sidebar-date-range'),
        singleMode: false,
        numberOfMonths: 1,
        numberOfColumns: 1,
        format: 'DD/MM/YYYY',
        lang: 'vi-VN',
        setup: (picker) => {
            picker.on('selected', (date1, date2) => {
                $('#sidebar-checkin').val(date1.format('YYYY-MM-DD'));
                $('#sidebar-checkout').val(date2.format('YYYY-MM-DD'));
            });
        }
    });

    if(sCheckin && sCheckout) {
        sidebarPicker.setDateRange(sCheckin, sCheckout);
    }
});
</script>

<?php get_footer(); ?>
