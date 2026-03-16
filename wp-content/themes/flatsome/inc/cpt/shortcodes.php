<?php
/**
 * Room Booking Shortcodes
 * Path: flatsome/inc/cpt/shortcodes.php
 */

function flatsome_room_list_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'posts_per_page' => 6,
        'columns'        => 3,
    ), $atts );

    $args = array(
        'post_type'      => 'room_booking',
        'posts_per_page' => $atts['posts_per_page'],
        'post_status'    => 'publish',
    );

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return '<p>Không tìm thấy phòng nào.</p>';
    }

    ob_start();
    ?>
    <div class="row room-list-container">
        <?php while ( $query->have_posts() ) : $query->the_post(); 
            $post_id = get_the_ID();
            
            // Lấy Meta Data
            $price    = (float) get_post_meta( $post_id, '_room_price', true );
            $status   = get_post_meta( $post_id, '_room_status', true );
            $size     = get_post_meta( $post_id, '_room_size', true );
            $adults   = get_post_meta( $post_id, '_max_adults', true );
            $children = get_post_meta( $post_id, '_max_children', true );
            $bed      = get_post_meta( $post_id, '_bed_type', true );
            $view     = get_post_meta( $post_id, '_room_view', true );
            
            // Lấy Taxonomy
            $types     = get_the_terms( $post_id, 'room_type' );
            $amenities = get_the_terms( $post_id, 'room_amenity' );
        ?>
            <div class="col medium-<?php echo 12 / $atts['columns']; ?> small-12">
                <div class="col-inner">
                    <div class="room-card box has-hover">
                        <div class="box-image">
                            <div class="image-cover" style="padding-top:60%;">
                                <?php 
                                if ( has_post_thumbnail() ) {
                                    the_post_thumbnail('medium_large'); 
                                } else {
                                    echo '<img src="https://placehold.jp/600x400.png" alt="No image" class="attachment-medium_large size-medium_large wp-post-image">';
                                }
                                ?>
                            </div>
                            <?php if($status): ?>
                                <div class="room-badge badge-<?php echo esc_attr($status); ?>">
                                    <?php echo ucfirst($status); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="box-text text-left">
                            <div class="room-price-tag mb-0">
                                <span class="amount"><?php echo number_format($price); ?> VNĐ</span> / đêm
                            </div>

                            <!-- Rating Display for List -->
                            <div class="room-card-rating mb-half">
                                <?php $avg_rating = flatsome_get_average_rating($post_id); ?>
                                <div class="star-rating-display small-stars">
                                    <div class="stars-outer">
                                        <div class="stars-inner" style="width: <?php echo ($avg_rating / 5) * 100; ?>%;"></div>
                                    </div>
                                    <!-- <span class="avg-score"><strong><?php echo $avg_rating; ?></strong></span> -->
                                </div>
                            </div>

                            <div class="room-features grid-info">
                                <span><i class="icon-expand"></i> <?php echo $size; ?> m²</span>
                                <span><i class="icon-user"></i> <?php echo $adults; ?> Lớn, <?php echo $children; ?> Nhỏ</span>
                                <span><i class="icon-menu"></i> <?php echo $bed; ?></span>
                            </div>

                            <p class="room-view"><i class="icon-checkmark"></i> View: <?php echo $view; ?></p>

                            <div class="room-amenities">
                                <?php if($amenities): foreach($amenities as $amenity): ?>
                                    <span class="amenity-tag"><?php echo $amenity->name; ?></span>
                                <?php endforeach; endif; ?>
                            </div>

                            <div class="room-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                            </div>

                            <a href="<?php the_permalink(); ?>" class="button primary is-outline is-small expand">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <style>
        .room-card { border: 1px solid #eee; border-radius: 8px; overflow: hidden; background: #fff; transition: transform 0.3s; }
        .room-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .room-badge { position: absolute; top: 10px; right: 10px; padding: 5px 10px; color: #fff; border-radius: 4px; font-size: 0.8em; z-index: 10; }
        .badge-available { background: #4CAF50; }
        .badge-booked { background: #f44336; }
        .badge-maintenance { background: #ff9800; }
        .box-text { padding: 15px; }
        .room-title { margin-top: 0; font-size: 1.25em; color: #333; }
        .room-price-tag { color: #d2691e; font-weight: bold; font-size: 1.1em; margin-bottom: 15px; }
        .grid-info { display: flex; flex-wrap: wrap; gap: 10px; font-size: 0.9em; color: #666; margin-bottom: 10px; }
        .amenity-tag { display: inline-block; background: #f1f1f1; padding: 2px 8px; border-radius: 12px; font-size: 0.75em; margin: 2px; color: #555; }
        .room-view { font-size: 0.85em; color: #2ecc71; margin-top: 5px; }

        /* Star Rating for Room Card */
        .stars-outer { display: inline-block; position: relative; font-size: 1em; color: #ccc; line-height: 1; }
        .stars-outer::before { content: "\2605 \2605 \2605 \2605 \2605"; }
        .stars-inner { position: absolute; top: 0; left: 0; white-space: nowrap; overflow: hidden; color: #f1c40f; line-height: 1; }
        .stars-inner::before { content: "\2605 \2605 \2605 \2605 \2605"; }
        .avg-score { margin-left: 5px; font-size: 0.85em; color: #666; vertical-align: middle; }
        .small-stars .stars-outer { font-size: 0.9em; }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode( 'room_list', 'flatsome_room_list_shortcode' );

/**
 * Shortcode: Advanced Room Search Bar
 */
function flatsome_room_search_bar_shortcode() {
    $room_types = get_terms( array(
        'taxonomy'   => 'room_type',
        'hide_empty' => false,
    ) );

    ob_start();
    ?>
    <!-- Litepicker CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <div class="room-search-bar-wrapper box-shadow-2">
        <form role="search" method="get" class="room-search-form" action="<?php echo esc_url( get_post_type_archive_link( 'room_booking' ) ); ?>">
            <div class="search-grid">
                <!-- Keywords -->
                <div class="search-field" style="flex: 1.5;">
                    <label>Bạn muốn đi đâu?</label>
                    <div class="input-wrapper">
                        <i class="icon-search"></i>
                        <input type="text" name="room_kw" placeholder="Tên phòng..." value="<?php echo isset($_GET['room_kw']) ? esc_attr($_GET['room_kw']) : ''; ?>">
                    </div>
                </div>

                <!-- Date Range Picker (Litepicker) -->
                <div class="search-field" style="flex: 1.5;">
                    <label>Thời gian</label>
                    <div class="input-wrapper">
                        <i class="icon-clock"></i>
                        <input type="text" id="room-date-range" placeholder="Chọn ngày nhận - trả" readonly style="padding-left: 35px !important;">
                        <input type="hidden" name="checkin" id="checkin-hidden" value="<?php echo isset($_GET['checkin']) ? esc_attr($_GET['checkin']) : ''; ?>">
                        <input type="hidden" name="checkout" id="checkout-hidden" value="<?php echo isset($_GET['checkout']) ? esc_attr($_GET['checkout']) : ''; ?>">
                    </div>
                </div>

                <!-- Room Type -->
                <div class="search-field">
                    <label>Loại phòng</label>
                    <div class="input-wrapper">
                        <i class="icon-menu"></i>
                        <select name="type">
                            <option value="">Tất cả loại phòng</option>
                            <?php foreach ( $room_types as $type ) : ?>
                                <option value="<?php echo esc_attr( $type->slug ); ?>" <?php selected( isset($_GET['type']) ? $_GET['type'] : '', $type->slug ); ?>>
                                    <?php echo esc_html( $type->name ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Guests & Rooms Dropdown -->
                <div class="search-field guests-search-field">
                    <label>Khách & Phòng</label>
                    <div class="input-wrapper guests-toggle">
                        <i class="icon-user"></i>
                        <div class="guests-display-text">
                            <span id="guests-summary">1 Phòng, 1 Người lớn, 0 Trẻ em</span>
                        </div>
                        <i class="icon-angle-down" style="position: absolute; right: 10px; left: auto;"></i>
                    </div>
                    <div class="guests-dropdown-menu box-shadow-2">
                        <div class="guests-menu-item">
                            <div class="guest-info">
                                <span class="guest-label">Số lượng phòng</span>
                            </div>
                            <div class="guest-counter">
                                <select name="rooms" id="rooms-select">
                                    <?php for($i=1; $i<=10; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php selected( isset($_GET['rooms']) ? $_GET['rooms'] : '1', $i ); ?>><?php echo $i; ?> Phòng</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="guests-menu-item">
                            <div class="guest-info">
                                <span class="guest-label">Người lớn</span>
                            </div>
                            <div class="guest-counter">
                                <select name="adults" id="adults-select">
                                    <?php for($i=1; $i<=20; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php selected( isset($_GET['adults']) ? $_GET['adults'] : '1', $i ); ?>><?php echo $i; ?> Người lớn</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="guests-menu-item">
                            <div class="guest-info">
                                <span class="guest-label">Trẻ em</span>
                                <span class="guest-sub-label">2-12 tuổi</span>
                            </div>
                            <div class="guest-counter">
                                <select name="children" id="children-select">
                                    <?php for($i=0; $i<=10; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php selected( isset($_GET['children']) ? $_GET['children'] : '0', $i ); ?>><?php echo $i; ?> Trẻ em</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="search-submit">
                    <button type="submit" class="button primary mb-0">TÌM KIẾM</button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .room-search-bar-wrapper { background: #fff; padding: 15px; border-radius: 10px; border: 4px solid #f1c40f; margin-bottom: 30px; position: relative; }
        .search-grid { display: flex; align-items: flex-end; gap: 15px; flex-wrap: wrap; }
        .search-field { flex: 1.2; display: flex; flex-direction: column; position: relative; }
        .search-field label { font-weight: bold; font-size: 0.85em; margin-bottom: 5px; color: #333; display: block; }
        .search-field .input-wrapper { position: relative; border: 1px solid #ddd; border-radius: 5px; height: 45px; background: #fff; cursor: pointer; display: flex; align-items: center; }
        .search-field .input-wrapper i.icon-search, 
        .search-field .input-wrapper i.icon-clock, 
        .search-field .input-wrapper i.icon-menu, 
        .search-field .input-wrapper i.icon-user { position: absolute; left: 10px; color: #999; z-index: 1; }
        .search-field input, .search-field select { width: 100%; padding: 0 10px 0 35px !important; border: none; border-radius: 5px; height: 100%; background: transparent; margin-bottom:0; }
        
        /* Ensure Flatpickr year is visible */
        .flatpickr-calendar .flatpickr-month { color: #333 !important; }
        .flatpickr-calendar .numInputWrapper span.arrowUp:after { border-bottom-color: #333 !important; }
        .flatpickr-calendar .numInputWrapper span.arrowDown:after { border-top-color: #333 !important; }
        .flatpickr-current-month .numInputWrapper input.cur-year { color: inherit !important; font-weight: bold !important; }
        
        .guests-search-field .input-wrapper { padding-left: 35px; }
        .guests-display-text { font-size: 0.9em; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .guests-dropdown-menu { position: absolute; top: 100%; left: 0; right: 0; background: #fff; z-index: 100; padding: 15px; border-radius: 5px; margin-top: 5px; display: none; border: 1px solid #eee; }
        .guests-menu-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .guests-menu-item:last-child { margin-bottom: 0; }
        .guest-info { display: flex; flex-direction: column; }
        .guest-label { font-weight: bold; font-size: 0.9em; }
        .guest-sub-label { font-size: 0.75em; color: #999; }
        .guest-counter select { width: 70px !important; padding: 5px !important; text-align: center; border: 1px solid #ddd !important; height: 35px; }

        .search-submit { flex: 0.8; min-width: 120px; }
        .search-submit button { width: 100%; height: 45px; font-weight: bold; font-size: 1em; }
        
        @media (max-width: 849px) {
            .search-grid { flex-direction: column; align-items: stretch; }
            .search-field { flex: none; }
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        $('.guests-toggle').on('click', function(e) {
            e.stopPropagation();
            $('.guests-dropdown-menu').toggle();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.guests-search-field').length) {
                $('.guests-dropdown-menu').hide();
            }
        });

        function updateGuestsSummary() {
            var rooms = $('#rooms-select').val();
            var adults = $('#adults-select').val();
            var children = $('#children-select').val();
            
            var text = rooms + ' Phòng, ' + adults + ' Người lớn';
            if(parseInt(children) > 0) {
                text += ', ' + children + ' Trẻ em';
            }
            $('#guests-summary').text(text);
        }

        $('#rooms-select, #adults-select, #children-select').on('change', function() {
            updateGuestsSummary();
        });

        // Initial call
        updateGuestsSummary();

        // Litepicker Initialization
        var checkinVal = $('#checkin-hidden').val();
        var checkoutVal = $('#checkout-hidden').val();
        
        const picker = new Litepicker({
            element: document.getElementById('room-date-range'),
            singleMode: false,
            numberOfMonths: 2,
            numberOfColumns: 2,
            format: 'DD/MM/YYYY',
            lang: 'vi-VN',
            tooltipText: {
                one: 'đêm',
                other: 'đêm'
            },
            setup: (picker) => {
                picker.on('selected', (date1, date2) => {
                    $('#checkin-hidden').val(date1.format('YYYY-MM-DD'));
                    $('#checkout-hidden').val(date2.format('YYYY-MM-DD'));
                });
            }
        });

        // Set default dates if exist
        if(checkinVal && checkoutVal) {
            picker.setDateRange(checkinVal, checkoutVal);
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'room_search_bar', 'flatsome_room_search_bar_shortcode' );
