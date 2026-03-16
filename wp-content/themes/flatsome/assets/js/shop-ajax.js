jQuery(document).ready(function($){

    let selectedParent = '';
    let selectedChild = '';
    let searchQuery = '';
    let currentPage = 1;
    let isLoadingParent = false;

    function loadProducts() {
        $.ajax({
            url: shop_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_products',
                parent: selectedParent,
                child: selectedChild,
                search: searchQuery,
                page: currentPage
            },
            beforeSend: function() {
                $('#product-results').html('<p>Loading...</p>');
            },
            success: function(response) {
                $('#product-results').html(response);
            }
        });
    }

    function loadChildCategories(parentId, button) {
        isLoadingParent = true;
        
        // Ẩn child-category-filter
        $('#child-category-filter').hide();
        
        // Thêm loading icon vào button
        const loadingIcon = '<span class="loading-spinner" style="margin-left: 8px; display: inline-block;">⟳</span>';
        $(button).html($(button).text() + loadingIcon);
        $(button).find('.loading-spinner').css({
            'animation': 'spin 1s linear infinite',
            'display': 'inline-block'
        });
        
        // Disable tất cả parent buttons
        $('.parent-btn').prop('disabled', true).css('opacity', '0.5');
        
        $.ajax({
            url: shop_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'load_child_categories',
                parent_id: parentId
            },
            success: function(response) {
                $('#child-category-filter').html(response);
                $('#child-category-filter').show();
                
                // Xóa loading icon từ button
                $(button).html($(button).text().replace(/⟳/, '').trim());
                
                // Enable lại tất cả parent buttons
                $('.parent-btn').prop('disabled', false).css('opacity', '1');
                isLoadingParent = false;
                
                // Gắn lại event listener cho child buttons mới
                $('.child-btn').on('click', function(){
                    selectedChild = $(this).data('child');
                    $('.child-btn').removeClass('active');
                    $(this).addClass('active');
                    loadProducts();
                });
            },
            error: function() {
                $('#child-category-filter').html('<p style="text-align: center; color: red;">Error loading categories</p>');
                $('#child-category-filter').show();
                
                // Xóa loading icon từ button
                $(button).html($(button).text().replace(/⟳/, '').trim());
                
                // Enable lại tất cả parent buttons
                $('.parent-btn').prop('disabled', false).css('opacity', '1');
                isLoadingParent = false;
            }
        });
    }

    $('body').on('click', '.parent-btn', function(){
        // Nếu đang load parent, không cho click thêm
        if (isLoadingParent) {
            return;
        }
        
        selectedParent = $(this).data('parent');
        selectedChild = ''; // Reset child filter
        searchQuery = ''; // Reset search
        currentPage = 1; // Reset page
        $('#product-search').val(''); // Clear search input
        $('.parent-btn').removeClass('active');
        $(this).addClass('active');
        
        // Load lại danh mục con
        loadChildCategories(selectedParent, this);
        
        // Load sản phẩm
        loadProducts();
    });

    $('body').on('click', '.child-btn', function(){
        selectedChild = $(this).data('child');
        searchQuery = ''; // Reset search
        currentPage = 1; // Reset page
        $('#product-search').val(''); // Clear search input
        $('.child-btn').removeClass('active');
        $(this).addClass('active');
        loadProducts();
    });

    // Search functionality
    $('body').on('click', '#product-search-btn', function(){
        searchQuery = $('#product-search').val();
        currentPage = 1; // Reset page
        loadProducts();
    });

    // Search on Enter key
    $('body').on('keypress', '#product-search', function(e){
        if (e.which == 13) { // Enter key
            searchQuery = $(this).val();
            currentPage = 1; // Reset page
            loadProducts();
            return false;
        }
    });

    // Pagination functionality
    $('body').on('click', '.page-btn', function(){
        currentPage = $(this).data('page');
        loadProducts();
    });

     $('body').on('click','.loop-qty .qty-plus',function(){
        let input = $(this).siblings('.qty');
        input.val(parseInt(input.val()) + 1)
		$(this).closest(".box-text-products").find(".add-to-cart-button a").attr("data-quantity", input.val())
    });

    $('body').on('click','.loop-qty .qty-minus',function(){
        let input = $(this).siblings('.qty');
        let val = parseInt(input.val()) - 1;
        if(val >= 1) input.val(val);
    });

    $('body').on('click','.add_to_cart_button',function(){

        let qty = $(this)
            .closest('.product')
            .find('.qty')
            .val();

        $(this).attr('data-quantity', qty);

    });

    // initial load of products when page opens
    let firstParentBtn = $('.parent-btn').first();
    if (firstParentBtn.length > 0) {
        selectedParent = firstParentBtn.data('parent');
        firstParentBtn.addClass('active');
        loadChildCategories(selectedParent, firstParentBtn[0]);
        loadProducts();
    } else {
        loadProducts();
    }

});