<?php
/**
 * Starter 2026 — Category / Product Listing Page
 *
 * @contract  Store API v1 — page: category
 * @endpoint  GET store/api/v1/pages/category/{slug}
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url, $category_tree, $add_tocart_url
 *
 * PAGE VARIABLES
 *   $settings      array   Store settings
 *   $category      array   Current category row (id, name, slug, image). Empty for "all"
 *   $category_tree array   Full category tree for sidebar nav
 *   $colors        array   All distinct product variation colours
 *   $tags          array   All distinct product tags
 *   $user_id       int     Affiliate user ID (0 if none)
 */
?>

<?php if($category) { ?>
<?php
    $image = $category['image'] != '' ? 'assets/images/product/upload/thumb/' . $category['image'] : '';
    $background_image = $category['background_image'] != '' ? 'assets/images/product/upload/thumb/' . $category['background_image'] : '';
?>
<!-- Category Banner -->
<section class="s26-category-banner" <?php if($background_image): ?>style="background-image: linear-gradient(135deg, rgba(15,23,42,0.85), rgba(37,99,235,0.7)), url(<?= base_url($background_image) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <div class="s26-category-banner__inner">
            <?php if($image): ?>
            <div class="s26-category-banner__img">
                <img src="<?= base_url($image) ?>" alt="<?= htmlspecialchars($category['name']) ?>">
            </div>
            <?php endif; ?>
            <div class="s26-category-banner__text">
                <h1><?= htmlspecialchars($category['name']) ?></h1>
                <p><?= (!empty($category['description'])) ? $category['description'] : __('store.category_description_if_not_exist') ?? 'Explore our curated collection' ?></p>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<!-- Breadcrumb -->
<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <a href="<?= $base_url ?>category"><?= __('store.categories') ?? 'Categories' ?></a>
        <?php if(isset($category['name'])): ?>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= htmlspecialchars($category['name']) ?></span>
        <?php endif; ?>
    </nav>
</div>

<!-- Main Content -->
<section class="s26-category-page">
    <div class="container">
        <div class="row g-4">

            <!-- ═══════════ SIDEBAR ═══════════ -->
            <div class="col-lg-3">
                <div class="s26-sidebar" id="s26-sidebar">

                    <!-- Mobile Filter Toggle (visible on mobile only via offcanvas) -->
                    <div class="s26-sidebar__header d-lg-none d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0"><?= __('store.filters') ?? 'Filters' ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>

                    <!-- Category Tree -->
                    <div class="s26-sidebar-block">
                        <h6 class="s26-sidebar-block__title">
                            <i class="fas fa-layer-group"></i>
                            <?= __('store.related_categories') ?? 'Categories' ?>
                        </h6>
                        <?php
                        function display_with_children($parentRow, $level = 0) { 
                            $space = $level > 0 ? str_repeat("", $level).' ' : '';
                            foreach ($parentRow as $key => $row) {
                                echo '<li data-id="'. $row['id'] .'" class="s26-cat-item '. ($row['children'] ? 'has-children' : '') .'">';
                                echo '<span>'. $space .'<a href="'. base_url('store/category/'. $row['slug']) .'">'. htmlspecialchars($row['name']) ."</a></span>".($row['children'] ? "<i class='fas fa-chevron-down s26-cat-toggle'></i>" : ""); 
                                if ($row['children']) {
                                    echo '<ul class="s26-cat-children" style="display: none;">';
                                    display_with_children($row['children'], $level + 1);
                                    echo '</ul>';
                                }
                                echo '</li>';
                            }
                        }

                        echo '<ul class="s26-category-tree category_block">';
                        echo '<li data-id="0" class="s26-cat-item"><span><a href="'. base_url('store/category/') .'">'.__('store.all_categories').'</a></span></li>'; 
                        display_with_children($category_tree, 0);
                        echo '</ul>';
                        ?>
                    </div>

                    <!-- Search Filter -->
                    <div class="s26-sidebar-block">
                        <h6 class="s26-sidebar-block__title">
                            <i class="fas fa-search"></i>
                            <?= __('store.refine_by') ?? 'Refine By' ?>
                        </h6>
                        <div class="s26-sidebar-search">
                            <div class="s26-filter-input-wrap">
                                <i class="fas fa-search"></i>
                                <input id="searchProduct" type="text" placeholder="<?= __('store.enter_keywords') ?? 'Search products...' ?>" class="s26-filter-input">
                                <img src="<?= base_url('assets/store/default/img/cancel.png') ?>" class="cancel-img" alt="<?= __('store.cancel') ?? 'Cancel' ?>" style="display:none;cursor:pointer;width:14px;position:absolute;right:12px;top:50%;transform:translateY(-50%);">
                            </div>
                            <a href="javascript:void(0);" id="clear-all-search" class="s26-clear-link">
                                <i class="fas fa-times-circle"></i> <?= __('store.clear_all') ?? 'Clear All' ?>
                            </a>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="s26-sidebar-block">
                        <h6 class="s26-sidebar-block__title">
                            <i class="fas fa-tag"></i>
                            <?= __('store.price') ?? 'Price' ?>
                        </h6>
                        <div class="s26-price-slider">
                            <div id="slider-range" class="mb-3"></div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="s26-price-label" id="slider-range-value1"></span>
                                <span class="s26-price-divider">—</span>
                                <span class="s26-price-label" id="slider-range-value2"></span>
                            </div>
                            <a href="javascript:void(0);" id="filter-price-range" class="s26-btn-filter">
                                <i class="fas fa-filter"></i> <?= __('store.filter') ?? 'Apply Filter' ?>
                            </a>
                            <form>
                                <input type="hidden" name="min-value" value="0">
                                <input type="hidden" name="max-value" value="10000">
                            </form>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="s26-sidebar-block">
                        <h6 class="s26-sidebar-block__title">
                            <i class="fas fa-star"></i>
                            <?= __('store.product_rating') ?? 'Rating' ?>
                        </h6>
                        <div class="s26-rating-filter sidebar-rating-filter">
                            <?php for($r = 5; $r >= 1; $r--): ?>
                            <label class="s26-rating-option filter-rating-row">
                                <input type="radio" name="rating-filter" value="<?= $r ?>">
                                <div class="s26-rating-stars">
                                    <?php for($s = 1; $s <= 5; $s++): ?>
                                        <i class="<?= $s <= $r ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="s26-rating-label">(<?= $r ?>)</span>
                            </label>
                            <?php endfor; ?>
                            <a href="javascript:void(0);" id="clear-rating-filter" class="s26-clear-link">
                                <i class="fas fa-times-circle"></i> <?= __('store.clear_all') ?? 'Clear' ?>
                            </a>
                        </div>
                    </div>

                    <!-- Color Filter (if enabled) -->
                    <?php 
                    if(!empty($store_setting['is_variation_filter'])){ 
                    ?>
                    <div class="s26-sidebar-block">
                        <h6 class="s26-sidebar-block__title">
                            <i class="fas fa-palette"></i>
                            <?= __('store.color') ?? 'Color' ?>
                        </h6>
                        <div class="s26-color-filter sidebar-colors">
                            <?php 
                                if(sizeOf($colors) > 0) {
                                    for ($i=0; $i < sizeOf($colors); $i++) { 
                                        echo '<span class="s26-color-swatch" data-color="'.$colors[$i].'" style="background: '.$colors[$i].'"></span>';
                                    } 
                                }
                            ?>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Tags Filter -->
                    <div class="s26-sidebar-block">
                        <h6 class="s26-sidebar-block__title">
                            <i class="fas fa-tags"></i>
                            <?= __('store.product_tag') ?? 'Tags' ?>
                        </h6>
                        <div class="s26-tag-filter sidebar-tags">
                            <?php 
                                if(isset($tags) && sizeOf($tags) > 0) {
                                    foreach ($tags as $tag) {
                                        echo '<a href="javascript:void(0);" class="s26-tag-chip" data-tag="'.$tag.'">'.htmlspecialchars($tag).'</a>';
                                    }
                                }
                            ?>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ═══════════ PRODUCT GRID ═══════════ -->
            <div class="col-lg-9">

                <!-- Page Title -->
                <div class="s26-category-heading">
                    <h2 class="s26-category-heading__title"><?= (isset($category['name'])) ? htmlspecialchars($category['name']): (__('store.all_categories') ?? 'All Products') ?></h2>
                </div>

                <!-- Sort Bar -->
                <div class="s26-sort-bar">
                    <div class="s26-sort-bar__info">
                        <span class="s26-sort-bar__count">
                            <?= __('store.showing') ?? 'Showing' ?> <strong id="show-count">0</strong> / <strong id="total-count">0</strong> <?= __('store.results') ?? 'results' ?>
                        </span>
                    </div>
                    <div class="s26-sort-bar__actions">
                        <!-- Mobile Filter Button -->
                        <button class="s26-btn-filter-mobile d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#s26FilterOffcanvas">
                            <i class="fas fa-sliders-h"></i> <?= __('store.filters') ?? 'Filters' ?>
                        </button>
                        <div class="s26-sort-select-wrap">
                            <i class="fas fa-sort-amount-down"></i>
                            <select id="sort-by" class="s26-sort-select">
                                <option value="popular" selected><?= __('store.popular_products') ?? 'Popular' ?></option>
                                <option value="low-to-high"><?= __('store.price_low_to_high') ?? 'Price: Low to High' ?></option>
                                <option value="high-to-low"><?= __('store.price_high_to_low') ?? 'Price: High to Low' ?></option>
                                <option value="latest"><?= __('store.newest_first') ?? 'Newest First' ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="row g-3 product-list">
                </div>

                <!-- Load More -->
                <div class="text-center mt-4">
                    <a href="javascript:void(0);" class="s26-btn-outline see-more" data-next_page="1" style="display:none">
                        <i class="fas fa-sync-alt me-1"></i> <?= __('store.show_more') ?? 'Show More' ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Mobile Filter Offcanvas -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="s26FilterOffcanvas">
    <div class="offcanvas-header border-bottom">
        <h5 class="fw-bold mb-0"><i class="fas fa-sliders-h me-2"></i><?= __('store.filters') ?? 'Filters' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="s26-filter-mobile-body">
        <!-- Sidebar content cloned here via JS -->
    </div>
</div>

<?php include APPPATH . 'views/store/starter2026/product-list-template.php'; ?>

<!-- Category Page Styles -->

<script type="text/javascript">
$(document).ready(function() {
    // Clone sidebar to mobile offcanvas
    var sidebarHtml = $('#s26-sidebar').html();
    if (sidebarHtml) {
        $('#s26-filter-mobile-body').html(sidebarHtml);
    }

    // Initial load
    load_Product($('#searchProduct').val(), {
        category_slug : '<?= (isset($category['slug'])) ? $category['slug'] : ""; ?>',
    });

    // Tag filter
    $(document).on('click', '.sidebar-tags a, .s26-tag-filter a', function() {
        $(this).toggleClass('active');
        load_Product($('#searchProduct').val(), {
            next_page: $(this).data('next_page'),
        });
    });

    // Color filter
    $(document).on('click', '.sidebar-colors span, .s26-color-swatch', function() {
        $(this).toggleClass('active');
        load_Product($('#searchProduct').val(), {
            next_page: $(this).data('next_page'),
        });
    });

    // Show more
    $(document).on('click', '.see-more', function() {
        var url = $(location).attr('href'),
            parts = url.split("/"),
            category_slug = parts[parts.length-1];
        load_Product($('#searchProduct').val(), {
            next_page: $(this).data('next_page'),
            category_slug: category_slug,
        });
    });

    // Search input
    $('#searchProduct').keyup(function(e) {
        e.preventDefault();
        var search = $(this).val();
        load_Product(search);
    });

    // Category tree expand/collapse
    $(document).on('click', ".category_block a", function(e){ 
        e.stopPropagation(); 
    });
    $(document).on('click', ".category_block .has-children", function(e){
        e.stopPropagation();
        $(this).find("> ul, > .s26-cat-children").slideToggle();
    });
    $(document).on('click', '.s26-cat-toggle', function(e){
        e.stopPropagation();
        $(this).parent().find('> ul, > .s26-cat-children').slideToggle();
    });

    // Sort change
    $(document).on('change', '#sort-by', function(){
        load_Product($('#searchProduct').val());
    });

    // Price filter
    $(document).on('click', '#filter-price-range', function(){
        load_Product($('#searchProduct').val());
    });

    // Rating filter
    $(document).on('click', 'input[name="rating-filter"]', function(){
        load_Product($('#searchProduct').val());
    });

    // Clear rating
    $(document).on('click', '#clear-rating-filter', function(){
        $('input[name="rating-filter"]:checked').prop('checked', false);
        load_Product($('#searchProduct').val());
    });

    // Clear all search
    $(document).on('click', '#clear-all-search', function(){
        $('input[name="rating-filter"]:checked').prop('checked', false);
        $('#searchProduct').val('');
        load_Product($('#searchProduct').val());
    });
});

function load_Product(search, postData) {
    postData = postData || {};
    var data = postData;
    data.search = search;
    data.order_by = $('#sort-by').val();
    data.min_price = $('input[name="min-value"]').val();
    data.max_price = $('input[name="max-value"]').val();
    if($('input[name="rating-filter"]:checked').length){
        data.product_avg_rating = $('input[name="rating-filter"]:checked').val();
    }

    data.colors = [];
    data.tags = [];

    $('.sidebar-tags a, .s26-tag-filter a').each(function( index ) {
        if($(this).hasClass('active')){
            data.tags.push($(this).data('tag'));
        }
    });

    $('.sidebar-colors span, .s26-color-swatch').each(function( index ) {
        if($(this).hasClass('active')){
            data.colors.push($(this).data('color'));
        }
    });

    data.request_page = 'category';
    data.limit = 15;
    var ajaxReq = 'ToCancelPrevReq';
    var ajaxReq = $.ajax({
        url: "<?= base_url() ?>" + 'Store/load_Product',
        type: 'POST',
        dataType: 'JSON',
        data: data,
        beforeSend : function() {
            if(ajaxReq != 'ToCancelPrevReq' && ajaxReq.readyState < 4) {
                ajaxReq.abort();
            }
        },
        success: function(res) {
            if(res.category) {
                if(postData.next_page && postData.next_page > 1) {
                    $('.product-list').append(Mustache.render($('#product-list-template').html(), res.category));
                } else {
                    $('.product-list').html(Mustache.render($('#product-list-template').html(), res.category));
                }

                $('.see-more').data('next_page', res.category.next_page);
                if(res.category.is_last_page) {
                    $('.see-more').hide();
                } else {
                    $('.see-more').show();
                }

                if(res.category.total_count) {
                    $('#total-count').text(res.category.total_count);
                }

                if(postData.next_page && postData.next_page > 1){
                    $('#show-count').text((parseInt($('#show-count').text())+res.category.count));
                } else {
                    $('#show-count').text(res.category.count);
                };
            }
        }
    });
}

<?php if($category) { ?>
    var c = $('[data-id="<?= $category['id'] ?>"]').parents("li");
    var ele = c[c.length-1];
<?php } ?>

// noUiSlider initialization
$(document).ready(function() {
    $('.noUi-handle').on('click', function() {
        $(this).width(50);
    });
    var rangeSlider = document.getElementById('slider-range');
    if (rangeSlider && typeof wNumb !== 'undefined' && typeof noUiSlider !== 'undefined') {
        var moneyFormat = wNumb({
            decimals: 0,
            thousand: ',',
            prefix: $('a[data-currency-symbol]').data('currency-symbol'),
            edit: function(value){
                if(value == "$10,000") {
                    return "$10,000 +";
                } else {
                    return value;
                }
            }
        });
        
        noUiSlider.create(rangeSlider, {
            start: [0, 10000],
            step: 50,
            range: {
                'min': [0],
                'max': [10000]
            },
            format: moneyFormat,
            connect: true
        });
        
        rangeSlider.noUiSlider.on('update', function(values, handle) {
            document.getElementById('slider-range-value1').innerHTML = values[0];
            document.getElementById('slider-range-value2').innerHTML = values[1];
            $('input[name="min-value"]').val(moneyFormat.from(values[0]));
            $('input[name="max-value"]').val(moneyFormat.from(values[1]));
        });
    }
});
</script>
