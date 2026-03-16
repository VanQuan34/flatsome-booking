<?php
/**
 * The template for displaying single room booking posts
 *
 * @package flatsome
 */

get_header(); ?>

<div id="content" class="content-area page-wrapper" role="main">
    <div class="row row-main">
        <div class="large-12 col">
            <div class="col-inner">
                <?php while ( have_posts() ) : the_post(); 
                    $post_id = get_the_ID();
                    
                    // Meta Data
                    $price    = (float) get_post_meta( $post_id, '_room_price', true );
                    $status   = get_post_meta( $post_id, '_room_status', true );
                    $quantity = get_post_meta( $post_id, '_room_quantity', true );
                    $adults   = get_post_meta( $post_id, '_max_adults', true );
                    $children = get_post_meta( $post_id, '_max_children', true );
                    $extra    = get_post_meta( $post_id, '_extra_beds', true );
                    $size     = get_post_meta( $post_id, '_room_size', true );
                    $bed      = get_post_meta( $post_id, '_bed_type', true );
                    $view     = get_post_meta( $post_id, '_room_view', true );
                    $min_stay = get_post_meta( $post_id, '_min_stay', true );
                    $checkin  = get_post_meta( $post_id, '_checkin_time', true );
                    $checkout = get_post_meta( $post_id, '_checkout_time', true );
                    $video    = get_post_meta( $post_id, '_room_video', true );
                    $address  = get_post_meta( $post_id, '_room_address', true );
                    $map_url  = get_post_meta( $post_id, '_room_map_url', true );
                    
                    // Taxonomies
                    $types = get_the_terms( $post_id, 'room_type' );
                    $amenities = get_the_terms( $post_id, 'room_amenity' );
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="room-header-v5 mb-2">
                            <h1 class="room-title-v5"><?php the_title(); ?></h1>
                            <div class="room-meta-v5">
                                <span class="rating-v5">
                                    <i class="icon-star"></i> 
                                    <strong><?php echo flatsome_get_average_rating($post_id); ?></strong> 
                                    (<?php echo get_comments_number(); ?> đánh giá)
                                </span>
                                <span class="sep-v5">•</span>
                                <span class="location-v5 underline">
                                    <i class="icon-map-pin-fill"></i> <?php echo esc_html($address); ?>
                                </span>
                            </div>
                        </header>
 
                        <!-- NEW GRID GALLERY 50/50 (V5) - Full Width -->
                        <div class="room-gallery-grid-v5 mb-3">
                            <div class="gallery-left-v5">
                                <div class="gallery-item-v5 main-item image-lightbox-group">
                                    <?php 
                                    if ( has_post_thumbnail() ) {
                                        echo '<a href="' . get_the_post_thumbnail_url($post_id, 'full') . '" class="image-lightbox">';
                                        the_post_thumbnail('large'); 
                                        echo '</a>';
                                    } else {
                                        echo '<img src="https://placehold.jp/800x600.png" alt="No image" class="placeholder-img">';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="gallery-right-v5">
                                <?php 
                                $gallery_ids = get_post_meta( $post_id, '_room_gallery', true );
                                $ids = !empty($gallery_ids) ? explode( ',', $gallery_ids ) : array();
                                for ($i = 0; $i < 4; $i++) : 
                                    $attachment_id = isset($ids[$i]) ? $ids[$i] : null;
                                    $img_url = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'large' ) : 'https://placehold.jp/400x310.png';
                                ?>
                                    <div class="gallery-item-v5 sub-item">
                                        <a href="<?php echo esc_url($img_url); ?>" class="image-lightbox">
                                            <img src="<?php echo esc_url($img_url); ?>" alt="Gallery Image">
                                        </a>
                                        <?php if ($i == 3) : ?>
                                            <div class="show-all-btn-v5" onclick="jQuery('.gallery-left-v5 .image-lightbox').click();">
                                                <i class="icon-menu"></i> Xem tất cả ảnh
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Left: Media & Description -->
                            <div class="large-8 col">

                        <div class="room-description-content mt">
                                    <h3>Mô tả phòng</h3>
                                    <?php the_content(); ?>
                                </div>

                                <?php if($map_url): ?>
                                    <div class="room-map mt">
                                        <h3>Vị trí trên bản đồ</h3>
                                        <div class="map-container shadow-1" style="border-radius: 8px; overflow: hidden; min-height: 350px;">
                                            <?php 
                                            if ( strpos( $map_url, '<iframe' ) !== false ) {
                                                // Nếu là cả đoạn iframe
                                                echo $map_url; 
                                            } else {
                                                // Nếu chỉ là URL
                                                echo '<iframe src="' . esc_url($map_url) . '" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
                                            }
                                            ?>
                                        </div>
                                        <?php if($address): ?>
                                            <p class="mt-half"><i class="icon-map-pin-fill"></i> <?php echo esc_html($address); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if($video): ?>
                                    <div class="room-video mt">
                                        <h3>Video giới thiệu</h3>
                                        <div class="video-container shadow-1" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                                            <iframe src="<?php echo esc_url($video); ?>" style="position:absolute; top:0; left:0; width:100%; height:100%;" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Right: Booking Info Sidebar -->
                            <div class="large-4 col">
                                <div class="room-sidebar-sticky col-inner">
                                    <div class="room-booking-box box-shadow-2">
                                        <div class="room-price-header">
                                            <span class="price"><?php echo number_format($price); ?> VNĐ</span>
                                            <span class="per-night">/ đêm</span>
                                        </div>

                                        <div class="room-meta-details-list">
                                            <div class="meta-item-row">
                                                <span class="label">Sức chứa:</span>
                                                <span class="value"><?php echo $adults; ?> Người lớn, <?php echo $children; ?> Trẻ em</span>
                                            </div>
                                            <div class="meta-item-row">
                                                <span class="label">Giường phụ:</span>
                                                <span class="value"><?php echo $extra > 0 ? 'Tối đa ' . $extra : 'Không hỗ trợ'; ?></span>
                                            </div>
                                            <div class="meta-item-row">
                                                <span class="label">Diện tích:</span>
                                                <span class="value"><?php echo $size; ?> m²</span>
                                            </div>
                                            <div class="meta-item-row">
                                                <span class="label">Loại giường:</span>
                                                <span class="value"><?php echo $bed; ?></span>
                                            </div>
                                            <div class="meta-item-row">
                                                <span class="label">Hướng nhìn:</span>
                                                <span class="value"><?php echo $view; ?></span>
                                            </div>
                                            <div class="meta-item-row">
                                                <span class="label">Ở tối thiểu:</span>
                                                <span class="value"><?php echo $min_stay; ?> đêm</span>
                                            </div>
                                        </div>

                                        <div class="room-policy-box mt-half mb-half">
                                            <p><i class="icon-clock"></i> <strong>Check-in:</strong> <?php echo $checkin; ?></p>
                                            <p><i class="icon-clock"></i> <strong>Check-out:</strong> <?php echo $checkout; ?></p>
                                        </div>

                                        <div class="room-amenities-section mt-half">
                                            <strong>Tiện ích:</strong>
                                            <div class="amenities-grid mt-half">
                                                <?php if($amenities): foreach($amenities as $amenity): ?>
                                                    <span class="amenity-tag-single"><?php echo $amenity->name; ?></span>
                                                <?php endforeach; endif; ?>
                                            </div>
                                        </div>

                                        <div class="booking-button-action mt">
                                            <?php 
                                            // Get carried parameters
                                            $s_checkin  = isset($_GET['checkin']) ? esc_attr($_GET['checkin']) : '';
                                            $s_checkout = isset($_GET['checkout']) ? esc_attr($_GET['checkout']) : '';
                                            $s_rooms    = isset($_GET['rooms']) ? esc_attr($_GET['rooms']) : '';
                                            $s_adults   = isset($_GET['adults']) ? esc_attr($_GET['adults']) : '';
                                            $s_children = isset($_GET['children']) ? esc_attr($_GET['children']) : '';

                                            if($s_checkin && $s_checkout) : ?>
                                                <div class="user-search-summary mb p-half border-radius-5" style="background: #f0f7ff; border: 1px solid #cce5ff; font-size: 0.85em; padding: 15px !important;">
                                                    <p class="mb-half"><strong>Lựa chọn của bạn:</strong></p>
                                                    <p class="mb-1"><i class="icon-clock"></i> <?php echo date('d/m/Y', strtotime($s_checkin)); ?> - <?php echo date('d/m/Y', strtotime($s_checkout)); ?></p>
                                                    <p class="mb-0"><i class="icon-user"></i> <?php echo $s_rooms; ?> phòng, <?php echo $s_adults; ?> lớn, <?php echo $s_children; ?> trẻ em</p>
                                                </div>
                                            <?php endif; ?>
                                            <button type="button" id="book-now-button" 
                                               class="button primary expand uppercase"
                                               data-room-id="<?php echo $post_id; ?>"
                                               data-room-name="<?php echo esc_attr(get_the_title()); ?>"
                                               data-room-price="<?php echo $price; ?>"
                                               data-checkin="<?php echo $s_checkin; ?>"
                                               data-checkout="<?php echo $s_checkout; ?>"
                                               data-rooms="<?php echo $s_rooms; ?>"
                                               data-adults="<?php echo $s_adults; ?>"
                                               data-children="<?php echo $s_children; ?>">
                                               Đặt phòng ngay
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="room-comments-section mt-3 mb-3 p-2 border-radius-10" style="background: #fff; border: 1px solid #eee; display: block !important;">
                            <?php comments_template(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header V5 Styling */
    .room-header-v5 { margin-bottom: 24px; text-align: left; }
    .room-title-v5 { 
        font-size: 1.65em; font-weight: 600; color: #222; 
        text-transform: none !important; margin-bottom: 8px !important; letter-spacing: -0.02em; 
    }
    .room-meta-v5 { display: flex; align-items: center; gap: 8px; font-size: 0.9em; color: #222; flex-wrap: wrap; }
    .rating-v5 { display: flex; align-items: center; gap: 4px; font-weight: 600; }
    .rating-v5 i:before { content: "\2605"; }
    .rating-v5 i { color: #f1c40f; font-size: 1.1em; line-height: 1; }
    .sep-v5 { color: #222; opacity: 0.5; }
    .location-v5 { font-weight: 600; color: #222; }
    
    /* RESET & CORE (Airbnb Style) */
    .room-airbnb-layout { font-family: "Inter", -apple-system, sans-serif; color: #222; }
    
    /* Airbnb Gallery V5 - Perfect 50/50 Grid */
    .room-gallery-grid-v5 { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 8px; 
        height: 500px; 
        border-radius: 12px; 
        overflow: hidden; 
        margin-bottom: 24px;
        position: relative;
    }
    .gallery-left-v5, .gallery-right-v5 { height: 100%; }
    .gallery-right-v5 { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        grid-template-rows: 1fr 1fr; 
        gap: 8px; 
    }
    .gallery-item-v5 { position: relative; overflow: hidden; height: 100%; }
    .gallery-item-v5 img { 
        width: 100%; height: 100%; object-fit: cover; display: block; 
        transition: transform 0.45s cubic-bezier(0.165, 0.84, 0.44, 1), filter 0.3s ease; 
    }
    .gallery-item-v5:hover img { transform: scale(1.05); filter: brightness(0.85); }

    .show-all-btn-v5 { 
        position: absolute; bottom: 20px; right: 20px; background: #fff; border: 1px solid #222; 
        padding: 7px 15px; border-radius: 8px; font-size: 0.85em; font-weight: 600; cursor: pointer; 
        z-index: 10; display: flex; align-items: center; gap: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.08); 
    }
    .show-all-btn-v5:hover { background: #f7f7f7; }

    /* Booking Card & Sidebar */
    .room-sidebar-sticky { position: sticky; top: 100px; z-index: 10; }
    .room-booking-box { 
        background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #ebebeb; 
        box-shadow: rgba(0, 0, 0, 0.12) 0px 6px 16px; 
    }
    .room-price-header { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .room-price-header .price { font-size: 1.5em; font-weight: 800; }
    
    .meta-item-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95em; }
    .meta-item-row .label { color: #717171; }
    .meta-item-row .value { font-weight: 600; }

    .amenity-tag-single { 
        display: inline-block; background: #f7f7f7; padding: 6px 14px; 
        border-radius: 20px; margin: 4px 4px 4px 0; font-size: 0.85em; color: #484848; 
    }

    /* Modals */
    .room-booking-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: none; }
    .room-booking-modal { 
        position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); 
        width: 95%; max-width: 850px; background: #fff; z-index: 10000; border-radius: 12px; 
        display: none; box-shadow: 0 15px 45px rgba(0,0,0,0.3); overflow: hidden;
    }
    .modal-body-flex { display: flex; flex-wrap: wrap; }
    .modal-left-info { flex: 1; min-width: 300px; padding: 30px; background: #f9f9f9;}
    .modal-right-form { flex: 1; min-width: 300px; padding: 30px; }
    .modal-header { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h4 { margin: 0; font-size: 1.1em; font-weight: 800; text-transform: uppercase; }
    .close-modal { font-size: 1.8em; font-weight: bold; cursor: pointer; line-height: 1; padding: 5px; transition: opacity 0.2s; }
    .close-modal:hover { opacity: 0.6; }
    .modal-content { padding: 25px;max-height: calc(100vh - 150px);overflow: auto;}

    /* Star Rating */
    .stars-outer { display: inline-block; position: relative; font-size: 1.1em; color: #ccc; }
    .stars-outer::before { content: "\2605 \2605 \2605 \2605 \2605"; }
    .stars-inner { position: absolute; top: 0; left: 0; white-space: nowrap; overflow: hidden; color: #f1c40f; }
    .stars-inner::before { content: "\2605 \2605 \2605 \2605 \2605"; }
    .avg-score { margin-left: 6px; font-weight: 600; color: #222; }

    /* Star Rating Input */
    .star-rating-input { display: inline-flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
    .star-rating-input input { display: none; }
    .star-rating-input label { font-size: 1.8em; color: #ccc; cursor: pointer; transition: color 0.2s; line-height: 1; }
    .star-rating-input label:before { content: "\2605"; }
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label,
    .star-rating-input input:checked ~ label { color: #f1c40f; }

    /* Existing comment stars */
    .comment-rating { color: #f1c40f; margin-bottom: 8px; }
    .comment-rating .star.filled { color: #f1c40f !important; }
    .comment-rating .star { color: #ccc !important; font-size: 1.25em; }

    /* Modal Form Styling */
    .modal-item .label { display: block; font-size: 0.8em; text-transform: uppercase; color: #717171; font-weight: 700; margin-bottom: 2px; }
    .modal-item .value { font-size: 1.05em; color: #222; font-weight: 600; }
    .modal-total-v2 { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

    @media (max-width: 849px) {
        .modal-body-flex { flex-direction: column; }
        .modal-left-info { border-right: none; border-bottom: 1px solid #eee; padding: 20px; }
        .modal-right-form { padding: 20px; }
        .room-gallery-grid-v5 { grid-template-columns: 1fr; height: auto; }
        .gallery-right-v5 { display: none; }
        .gallery-left-v5 { height: 300px; }
        .room-sidebar-sticky { position: static !important; }
        .room-booking-box { position: static; margin-top: 24px; box-shadow: none; border: 1px solid #eee; }
    }
</style>

<!-- Modal HTML -->
<div class="room-booking-overlay"></div>
<div class="room-booking-modal" id="confirmation-modal">
    <div class="modal-header">
        <h4>Xác nhận đặt phòng</h4>
        <div class="close-modal">&times;</div>
    </div>
    <div class="modal-content p-0">
        <div class="modal-body-flex">
            <!-- Left: Room Highlights -->
            <div class="modal-left-info">
                <h5 class="uppercase mb-1" style="color: #FF385C; font-size: 0.9em;">Chi tiết đặt phòng</h5>
                <div class="booking-summary">
                    <div class="modal-item highlight-item mb">
                        <span class="label">Phòng đang chọn</span>
                        <div class="value-large" id="modal-room-name" style="font-size: 1.2em; font-weight: 700; color: #222;">-</div>
                    </div>
                    <div class="flex-row gap-1 mb">
                        <div class="modal-item flex-grow">
                            <span class="label">Ngày nhận</span>
                            <span class="value" id="modal-checkin" style="font-weight: 600;">-</span>
                        </div>
                        <div class="modal-item flex-grow">
                            <span class="label">Ngày trả</span>
                            <span class="value" id="modal-checkout" style="font-weight: 600;">-</span>
                        </div>
                    </div>
                    <div class="modal-item mb">
                        <span class="label">Chi tiết</span>
                        <span class="value" id="modal-details">-</span>
                    </div>
                    <div class="modal-item mb">
                        <span class="label">Thời gian lưu trú</span>
                        <span class="value" id="modal-nights">0 đêm</span>
                    </div>
                    <div class="modal-total-v2 mt pt border-top" style="border-top: 2px solid #eee;">
                        <span class="label block uppercase mb-half" style="font-size: 0.8em; opacity: 0.7;">Tổng cộng</span>
                        <span class="total-price" id="modal-total-price" style="font-size: 1.8em; font-weight: 800; color: #FF385C;">0 VNĐ</span>
                    </div>
                </div>
            </div>

            <!-- Right: Guest Info & Payment -->
            <div class="modal-right-form">
                <div class="guest-info-form">
                    <h5 class="mb-1 uppercase" style="font-size: 0.9em; color: #222;">Thông tin khách hàng</h5>
                    <div class="form-group mb-half">
                        <label class="form-label">Họ và tên <span class="required-star">*</span></label>
                        <input type="text" id="guest-name" placeholder="Ví dụ: Nguyễn Văn A" style="width: 100%;">
                    </div>
                    <div class="form-group mb-half">
                        <label class="form-label">Số điện thoại <span class="required-star">*</span></label>
                        <input type="tel" id="guest-phone" placeholder="Ví dụ: 0912345678" style="width: 100%;">
                    </div>
                    <div class="form-group mb-1">
                        <label class="form-label">Email</label>
                        <input type="email" id="guest-email" placeholder="Ví dụ: example@gmail.com" style="width: 100%;">
                    </div>
                </div>
                
                <h5 class="mb-1 uppercase mt" style="font-size: 0.9em; color: #222;">Phương thức thanh toán</h5>
                <div id="paypal-button-container" style="width: 100%; margin-bottom: 10px;"></div>
                <button type="button" id="final-confirm-button" class="button primary expand uppercase" style="margin: 0; padding: 15px; display: none; background-color: #222; border-color: #222;">Đặt phòng & Thanh toán sau</button>
            </div>
        </div>
    </div>
    <!-- Footer removed as buttons moved inside modal-right-form -->
</div>

<!-- Thank You Modal -->
<div class="room-booking-modal" id="thank-you-modal">
    <div class="modal-content thank-you-modal">
        <div class="thank-you-icon">
            <i class="icon-checkmark"></i>
        </div>
        <h3>Cảm ơn <span id="display-guest-name">Quý khách</span>!</h3>
        <p>Yêu cầu đặt phòng của bạn đã được gửi đi thành công. Nhân viên của chúng tôi sẽ liên hệ lại với bạn sớm nhất để xác nhận thông tin.</p>
        <button type="button" class="button primary expand close-modal-btn mt-1" style="margin: 0;">Đóng</button>
    </div>
</div>

<script>
// Tích hợp PayPal
function initPayPalButtons(bookingData, totalPriceVND) {
    if (!window.paypal) return;

    // Quy đổi VND sang USD (tạm tính 1 USD = 25,000 VND)
    var totalUSD = (totalPriceVND / 25000).toFixed(2);

    jQuery('#paypal-button-container').empty(); // Reset container

    paypal.Buttons({
        style: {
            layout: 'vertical',
            color:  'gold',
            shape:  'rect',
            label:  'paypal'
        },
        createOrder: function(data, actions) {
            var name = jQuery('#guest-name').val().trim();
            var phone = jQuery('#guest-phone').val().trim();
            if (!name || !phone) {
                alert('Vui lòng điền Họ tên và Số điện thoại trước khi thanh toán!');
                return actions.reject();
            }

            return actions.order.create({
                purchase_units: [{
                    amount: {
                        currency_code: 'USD',
                        value: totalUSD
                    },
                    description: 'Đặt phòng: ' + bookingData.roomName
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                saveOrderViaAjax(bookingData, totalPriceVND, details.id);
            });
        },
        onError: function(err) {
            console.error('PayPal Error:', err);
            alert('Có lỗi xảy ra trong quá trình thanh toán PayPal.');
        }
    }).render('#paypal-button-container');
}

function saveOrderViaAjax(bookingData, totalPrice, paypalOrderId) {
    var ajaxData = {
        action: 'save_room_order',
        name: jQuery('#guest-name').val().trim(),
        phone: jQuery('#guest-phone').val().trim(),
        email: jQuery('#guest-email').val().trim(),
        room_id: bookingData.roomId,
        checkin: bookingData.checkin,
        checkout: bookingData.checkout,
        rooms: bookingData.rooms,
        adults: bookingData.adults,
        children: bookingData.children,
        nights: bookingData.totalNights,
        total: totalPrice,
        paypal_id: paypalOrderId || ''
    };

    jQuery('body').append('<div class="loading-overlay" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10001; display:flex; align-items:center; justify-content:center;">Đang lưu đơn hàng...</div>');

    jQuery.ajax({
        url: '<?php echo admin_url("admin-ajax.php"); ?>',
        type: 'POST',
        data: ajaxData,
        success: function(response) {
            jQuery('.loading-overlay').remove();
            if (response.success) {
                jQuery('#confirmation-modal').hide().removeClass('is-visible');
                jQuery('#display-guest-name').text(ajaxData.name);
                jQuery('#thank-you-modal').fadeIn(300).addClass('is-visible');
            } else {
                alert(response.data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
            }
        },
        error: function() {
            jQuery('.loading-overlay').remove();
            alert('Không thể kết nối với máy chủ.');
        }
    });
}

jQuery(document).ready(function($) {
    $('#book-now-button').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var bookingData = {
            roomId: $btn.data('room-id'),
            roomName: $btn.data('room-name'),
            price: parseFloat($btn.data('room-price')),
            checkin: $btn.data('checkin'),
            checkout: $btn.data('checkout'),
            rooms: $btn.data('rooms') || 1,
            adults: $btn.data('adults') || 1,
            children: $btn.data('children') || 0,
            totalNights: 0
        };

        if (!bookingData.checkin || !bookingData.checkout) {
            alert('Vui lòng chọn ngày nhận và trả phòng trước khi đặt!');
            return;
        }

        var start = new Date(bookingData.checkin);
        var end = new Date(bookingData.checkout);
        bookingData.totalNights = Math.ceil(Math.abs(end - start) / (1000 * 60 * 60 * 24));
        var totalPriceVND = bookingData.price * bookingData.totalNights * bookingData.rooms;

        $('#modal-room-name').text(bookingData.roomName);
        $('#modal-checkin').text(formatDateVN(bookingData.checkin));
        $('#modal-checkout').text(formatDateVN(bookingData.checkout));
        $('#modal-details').text(bookingData.rooms + ' phòng, ' + bookingData.adults + ' lớn, ' + bookingData.children + ' trẻ');
        $('#modal-nights').text(bookingData.totalNights + ' đêm');
        $('#modal-total-price').text(totalPriceVND.toLocaleString('vi-VN') + ' VNĐ');

        // Init PayPal
        initPayPalButtons(bookingData, totalPriceVND);

        $('.room-booking-overlay').fadeIn(300);
        $('#confirmation-modal').fadeIn(300).addClass('is-visible');
    });

    $('#final-confirm-button').on('click', function() {
        var name = $('#guest-name').val().trim();
        var phone = $('#guest-phone').val().trim();
        if (!name || !phone) {
            alert('Vui lòng điền Họ tên và Số điện thoại!');
            return;
        }
        
        var $bookBtn = $('#book-now-button');
        var bookingData = {
            roomId: $bookBtn.data('room-id'),
            checkin: $bookBtn.data('checkin'),
            checkout: $bookBtn.data('checkout'),
            rooms: $bookBtn.data('rooms') || 1,
            adults: $bookBtn.data('adults') || 1,
            children: $bookBtn.data('children') || 0,
            totalNights: 0
        };
        var start = new Date(bookingData.checkin);
        var end = new Date(bookingData.checkout);
        bookingData.totalNights = Math.ceil(Math.abs(end - start) / (1000 * 60 * 60 * 24));
        var totalPrice = parseFloat($bookBtn.data('room-price')) * bookingData.totalNights * bookingData.rooms;

        saveOrderViaAjax(bookingData, totalPrice, '');
    });

    $('.close-modal, .close-modal-btn, .room-booking-overlay').on('click', function() {
        $('.room-booking-overlay').fadeOut(300);
        $('.room-booking-modal').fadeOut(300).removeClass('is-visible');
    });

    function formatDateVN(dateStr) {
        var d = new Date(dateStr);
        return d.getDate().toString().padStart(2, '0') + '/' + (d.getMonth() + 1).toString().padStart(2, '0') + '/' + d.getFullYear();
    }
});
</script>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>

<?php get_footer(); ?>
