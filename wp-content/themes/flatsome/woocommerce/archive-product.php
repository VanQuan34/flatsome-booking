<?php
defined('ABSPATH') || exit;
get_header('shop');
?>

<div class="shop-wrapper row row-large row-divided">

<?php
// LẤY DANH MỤC CHA (level 0)
$parent_cats = get_terms([
    'taxonomy'   => 'product_cat',
    'parent'     => 0,
    'hide_empty' => false,
		'exclude'    => [15]
]);

$first_parent = !empty($parent_cats) ? $parent_cats[0] : null;
?>

<?php if (!empty($parent_cats)) : ?>

    <!-- MENU DANH MỤC CHA -->
    <div class="parent-category-tabs">
        <?php foreach ($parent_cats as $index => $parent) : ?>
            <button 
                class="parent-btn <?php echo $index === 0 ? 'active' : ''; ?>" 
                data-parent="<?php echo esc_attr($parent->term_id); ?>"
            >
                <?php echo esc_html($parent->name); ?>
            </button>
        <?php endforeach; ?>
    </div>

<?php endif; ?>


<?php if ($first_parent) : 

    // LẤY DANH MỤC CON CỦA DANH MỤC ĐẦU TIÊN
    $child_cats = get_terms([
        'taxonomy'   => 'product_cat',
        'parent'     => $first_parent->term_id,
        'hide_empty' => false
    ]);
?>

    <!-- FILTER DANH MỤC CON -->
    <div id="child-category-filter" class="child-category-filter">
        <button class="child-btn active" data-child="">
            All
        </button>

        <?php if (!empty($child_cats)) : ?>
            <?php foreach ($child_cats as $child) : ?>
                <button 
                    class="child-btn"
                    data-child="<?php echo esc_attr($child->slug); ?>"
                >
                    <?php echo esc_html($child->name); ?>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- SEARCH BOX -->
    <div class="product-search-box">
        <input 
            type="text" 
            id="product-search" 
            class="product-search-input" 
            placeholder="Search..."
        />
        <div id="product-search-btn" class="product-search-btn">
            🔍 Search
        </div>
    </div>

<?php endif; ?>


<!-- PRODUCT GRID -->
<div id="product-results">

<?php
// Hiển thị description của parent category đầu tiên
if ($first_parent && !empty($first_parent->description)) {
    echo '<div class="category-description">';
    echo wp_kses_post($first_parent->description);
    echo '</div>';
}

// Load sản phẩm của danh mục đầu tiên mặc định
$args = [
    'post_type'      => 'product',
    'posts_per_page' => 12,
    'tax_query'      => [
        [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $first_parent ? $first_parent->term_id : 0,
            'include_children' => true
        ]
    ]
];

$query = new WP_Query($args);

if ($query->have_posts()) :

    woocommerce_product_loop_start();

    while ($query->have_posts()) :
        $query->the_post();
        wc_get_template_part('content', 'product');
    endwhile;

    woocommerce_product_loop_end();

else :
    echo '<p>No products found</p>';
endif;

wp_reset_postdata();

// Hiển thị custom HTML của parent category bên dưới sản phẩm
if ($first_parent) {
    $custom_html = get_term_meta($first_parent->term_id, 'category_custom_html', true);
    if ($custom_html) {
        echo '<div class="category-custom-html">';
        echo wp_kses_post($custom_html);
        echo '</div>';
    }
}
?>
</div>

</div>

<?php get_footer('shop'); ?>