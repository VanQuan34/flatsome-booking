<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';
require get_template_directory() . '/inc/cpt/init.php';

flatsome()->init();

/**
 * It's not recommended to add any custom code here. Please use a child theme
 * so that your customizations aren't lost during updates.
 *
 * Learn more here: https://developer.wordpress.org/themes/advanced-topics/child-themes/
 */


update_option( 'flatsome_wup_purchase_code', '99dcbf02-cd62-41d2-bf60-bf0d62d95d62' ); 
update_option( 'flatsome_wup_supported_until', '01.01.2050' );
update_option( 'flatsome_wup_buyer', 'License' ); 
update_option( 'flatsome_wup_sold_at', time() ); 
delete_option( 'flatsome_wup_errors', '' ); 
delete_option( 'flatsome_wupdates', '');

add_action('wp_ajax_filter_products', 'filter_products_callback');
add_action('wp_ajax_nopriv_filter_products', 'filter_products_callback');

add_action('wp_ajax_load_child_categories', 'load_child_categories_callback');
add_action('wp_ajax_nopriv_load_child_categories', 'load_child_categories_callback');

function load_child_categories_callback() {
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;

    $child_cats = get_terms([
        'taxonomy'   => 'product_cat',
        'parent'     => $parent_id,
        'hide_empty' => false
    ]);

    // Hiển thị button "All"
    echo '<button class="child-btn active" data-child="">All</button>';

    // Hiển thị danh mục con
    if (!empty($child_cats)) {
        foreach ($child_cats as $child) {
            echo '<button class="child-btn" data-child="' . esc_attr($child->slug) . '">' . esc_html($child->name) . '</button>';
        }
    }

    wp_die();
}

function filter_products_callback() {

    $parent = isset($_POST['parent']) ? intval($_POST['parent']) : 0;
    $child  = isset($_POST['child']) ? sanitize_text_field($_POST['child']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $tax_query = [];

    if ($parent) {
        $tax_query[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $parent,
            'include_children' => !empty($child) ? false : true
        ];
    }

    if ($child) {
        $tax_query[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $child
        ];
    }

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 10,
        'paged'          => $page
    ];

    if (!empty($search)) {
        $args['s'] = $search;
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    // Lấy và hiển thị description của parent category
    if ($parent) {
        $parent_term = get_term($parent, 'product_cat');
        if ($parent_term && !is_wp_error($parent_term)) {
            $description = $parent_term->description;
            if ($description) {
                echo '<div class="category-description">';
                echo wp_kses_post($description);
                echo '</div>';
            }
        }
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        woocommerce_product_loop_start();

        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }

        woocommerce_product_loop_end();

    } else {
        echo '<p>No products found</p>';
    }

    wp_reset_postdata();
    
    // Hiển thị pagination nếu có nhiều trang
    if ($query->max_num_pages > 1) {
        echo '<div class="shop-pagination">';
        for ($i = 1; $i <= $query->max_num_pages; $i++) {
            $active = ($i == $page) ? ' active' : '';
            echo '<button class="page-btn' . $active . '" data-page="' . $i . '">' . $i . '</button>';
        }
        echo '</div>';
    }
    
    // Hiển thị custom HTML của parent category bên dưới sản phẩm
    if ($parent) {
        $custom_html = get_term_meta($parent, 'category_custom_html', true);
        if ($custom_html) {
            echo '<div class="category-custom-html">';
            echo wp_kses_post($custom_html);
            echo '</div>';
        }
    }
    
    wp_die();
}

function shop_ajax_scripts() {

    wp_enqueue_script(
        'shop-ajax',
        get_stylesheet_directory_uri() . '/assets/js/shop-ajax.js',
        ['jquery'],
        null,
        true
    );

    wp_localize_script('shop-ajax', 'shop_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}

add_action('wp_enqueue_scripts', 'shop_ajax_scripts');

// Add custom HTML field to product category
add_action('product_cat_add_form_fields', 'add_category_custom_html_field');
add_action('product_cat_edit_form_fields', 'edit_category_custom_html_field', 10, 2);

function add_category_custom_html_field() {
    ?>
    <div class="form-field">
        <label for="category_custom_html"><?php esc_html_e('Custom HTML', 'woocommerce'); ?></label>
        <textarea id="category_custom_html" name="category_custom_html" rows="6" style="width: 100%;"></textarea>
        <p class="description"><?php esc_html_e('Add custom HTML content for this category (displays on archive page)', 'woocommerce'); ?></p>
    </div>
    <?php
}

function edit_category_custom_html_field($term, $taxonomy) {
    $custom_html = get_term_meta($term->term_id, 'category_custom_html', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="category_custom_html"><?php esc_html_e('Custom HTML', 'woocommerce'); ?></label>
        </th>
        <td>
            <textarea id="category_custom_html" name="category_custom_html" rows="6" style="width: 100%;"><?php echo esc_textarea($custom_html); ?></textarea>
            <p class="description"><?php esc_html_e('Add custom HTML content for this category (displays on archive page)', 'woocommerce'); ?></p>
        </td>
    </tr>
    <?php
}

// Save custom HTML field
add_action('edited_product_cat', 'save_category_custom_html_field');
add_action('create_product_cat', 'save_category_custom_html_field');

function save_category_custom_html_field($term_id) {
    if (isset($_POST['category_custom_html'])) {
        update_term_meta($term_id, 'category_custom_html', wp_kses_post($_POST['category_custom_html']));
    }
}



//fetch post

function mobio_monopoly_posts_shortcode() {

//     $cache = get_transient('mobio_monopoly_cache');

    // if ($cache) {
    //     return $cache;
    // }

    $rss = fetch_feed('https://monopolygo.wiki/rss/');

    if (is_wp_error($rss)) {
        return "Cannot load RSS";
    }

    $maxitems = $rss->get_item_quantity(10);
    $items = $rss->get_items(0, $maxitems);

    $output = '<div class="mobio-list">';

    foreach ($items as $item) {

        $title = $item->get_title();
        $link  = $item->get_permalink();
        $date  = $item->get_date('M j, Y');

		$desc = $item->get_description();

		// remove style + script
		$desc = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $desc);
		$desc = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $desc);

		// remove html comments
		$desc = preg_replace('/<!--(.|\s)*?-->/', '', $desc);

		// strip html
		$text = wp_strip_all_tags($desc);

		// remove css rules
		$text = preg_replace('/[.#]?[a-zA-Z0-9\-\_\s\>\:\,\(\)]+\{[^}]*\}/', '', $text);

		// remove leftover braces
		$text = preg_replace('/\{[^}]*\}/', '', $text);

		// clean whitespace
		$text = trim(preg_replace('/\s+/', ' ', $text));

		// create excerpt
		$excerpt = wp_trim_words($text, 40);
		if (preg_match('/^\./', $excerpt)) {
			$excerpt = $item->get_title();
		}

        $img = '';

        // 1. lấy ảnh từ media:content
        $enclosure = $item->get_enclosure();
        if ($enclosure) {
            $img = $enclosure->get_link();
        }

        // 2. fallback parse img trong html
        if (!$img) {
            preg_match('/<img.+src=[\'"]([^\'"]+)[\'"]/i', $desc, $matches);
            if (!empty($matches[1])) {
                $img = $matches[1];
            }
        }

        // excerpt
//         $text = wp_strip_all_tags($desc);
//         $excerpt = wp_trim_words($text, 25);

        $output .= '
        <div class="mobio-item">

            <div class="mobio-thumb">
                <a href="'.$link.'" target="_blank">
                    <img src="'.$img.'" alt="'.$title.'">
                </a>
            </div>

            <div class="mobio-content">

                <h3 class="mobio-title">
                    <a href="'.$link.'" target="_blank">'.$title.'</a>
                </h3>

                <p class="mobio-excerpt">'.$excerpt.'</p>

                <div class="mobio-meta">
                    By Monopoly GO! Wiki — '.$date.'
                </div>

            </div>

        </div>';
    }

    $output .= '</div>';

//     set_transient('mobio_monopoly_cache', $output, 3600);

    return $output;
}

function mobio_clean_excerpt($html){

    // remove style/script
    $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);
    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);

    // remove remaining html
    $text = wp_strip_all_tags($html);

    // remove css pattern
    $text = preg_replace('/\{.*?\}/', '', $text);

    return wp_trim_words($text, 25);
}

add_shortcode('monopoly_posts', 'mobio_monopoly_posts_shortcode');


// add_action('wp', 'mobio_save_browsing_history');
function mobio_browsing_history_shortcode() {

    if(!isset($_COOKIE['wp_browsing_history'])) return '';

    $history = json_decode(stripslashes($_COOKIE['wp_browsing_history']), true);

    if(empty($history)) return '';

    ob_start();
	echo '<div class="container">';
    echo '<div class="wp-browsing-history">';
    echo '<h2>Browsing History</h2>';
	echo '<div class="list-browsing-history">';
    foreach($history as $post_id){

        $title = get_the_title($post_id);
        $link  = get_permalink($post_id);
        $thumb = get_the_post_thumbnail_url($post_id,'thumbnail');

        $time = get_the_time('M/d H:i',$post_id);

        echo '<div class="history-card">';

        if($thumb){
            echo '<img src="'.$thumb.'" class="history-thumb">';
        }

        echo '<div class="history-content">';
        echo '<a href="'.$link.'">'.$title.'</a>';
        echo '<div class="history-time">🕒 on '.$time.'</div>';
        echo '</div>';

        echo '</div>';
    }

    echo '</div></div></div>';

    return ob_get_clean();
}

add_shortcode('mobio_browsing_history', 'mobio_browsing_history_shortcode');


function update_product_prices_by_category($category_id, $new_price) {
    if (empty($category_id) || !is_numeric($new_price)) {
        return;
    }

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ],
        ],
        'fields' => 'ids'
    ];

    $query = new WP_Query($args);
    $count = 0;

    if ($query->have_posts()) {
        foreach ($query->posts as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $product->set_regular_price($new_price);
                $product->set_price($new_price);
                $product->save();
                $count++;
            }
        }
    }

    return $count;
}

function create_star_products() {

    $image_url = 'http://test2.quantv.store/wp-content/uploads/2026/03/6_stars.webp'; // ảnh sản phẩm
    $price = 4.2;
	$category_ids = [24, 37];

    $product_names = [
       "Posed Pawsy <br> Set 19-9",

"Beach Day <br> Set 20-9",

"Wall of Fame <br> Set 21-9",

"Croak-et <br> Set 22-9",

"Vibes n Throne <br> Set 23-9",

"Best in Show <br> Set 24-9"
    ];

    if (!function_exists('media_sideload_image')) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
    }

    // upload ảnh 1 lần
    $image_id = media_sideload_image($image_url, 0, null, 'id');

    foreach ($product_names as $name) {

        $product = new WC_Product_Simple();

        $product->set_name($name);
        $product->set_regular_price($price);
        $product->set_price($price);
        $product->set_status('publish');
		$product->set_category_ids($category_ids);

        $product_id = $product->save();

        set_post_thumbnail($product_id, $image_id);
    }

}

add_action('init', function(){

    if(isset($_GET['create_products'])){
        create_star_products();
    }

    if(isset($_GET['update_price']) && isset($_GET['cat_id']) && isset($_GET['new_price'])){
        $cat_id = intval($_GET['cat_id']);
        $price = floatval($_GET['new_price']);
        
        $count = update_product_prices_by_category($cat_id, $price);
        
        echo "✅ Đã cập nhật giá mới ($price) cho $count sản phẩm thuộc danh mục ID: $cat_id";
        exit;
    }

});