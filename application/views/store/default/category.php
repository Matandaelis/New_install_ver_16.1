<?php
/**
 * Default theme — Category / Shop page (Amazon wireframe)
 *
 * @contract  Store API v1 — page: category
 * @see       Store_cart_payload::page_category()
 * @see       /store/api/v1/pages/category
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $category      array   Current category data {id, name, image, background_image, color, description}; empty if viewing all
 *   $categories    array   All root categories for sidebar filter
 *   $products      array   Product list for this category/search [{id, name, price, old_price, image, link, ...}, ...]
 *   $pagination    string  Pre-rendered pagination HTML
 *   $sort_options  array   Sort-by options [['value'=>'newest','label'=>'Newest'], ...]
 *   $filter        array   Active filter state {sort, price_min, price_max, category_id}
 *   $total_count   int     Total matching products (for display)
 *   $search_term   string  Active search keyword (empty string if none)
 */
?>

<?php if (!empty($category)): ?>
<?php
    $ctg_img   = !empty($category['image'])            ? base_url('assets/images/product/upload/thumb/' . $category['image'])            : '';
    $ctg_bg    = !empty($category['background_image']) ? base_url('assets/images/product/upload/thumb/' . $category['background_image']) : '';
    $ctg_color = !empty($category['color'])            ? $category['color'] : '#0F1111';
    $ctg_bg_style = $ctg_bg ? "background-image: linear-gradient(135deg, rgba(19,25,33,0.85), rgba(35,47,62,0.75)), url({$ctg_bg}); background-size: cover; background-position: center;" : 'background: linear-gradient(135deg, var(--amz-navy-dark, #131921), var(--amz-navy, #232F3E));';
?>
<section class="amz-ctg-hero" style="<?= $ctg_bg_style ?>">
    <div class="container">
        <div class="amz-ctg-hero-inner">
            <?php if ($ctg_img): ?>
            <div class="amz-ctg-hero-icon">
                <img src="<?= $ctg_img ?>" alt="<?= htmlspecialchars($category['name'] ?? '') ?>">
            </div>
            <?php endif; ?>
            <div class="amz-ctg-hero-text">
                <h1 style="color: <?= $ctg_color ?>;"><?= htmlspecialchars($category['name'] ?? '') ?></h1>
                <p><?= !empty($category['description']) ? $category['description'] : __('store.category_description_if_not_exist') ?></p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="amz-category-page">
    <div class="container">
        <div class="amz-category-layout">

            <!-- Sidebar -->
            <aside class="amz-sidebar">

                <!-- Category Filter -->
                <div class="amz-filter-block">
                    <h3 class="amz-filter-title" data-toggle="cat-filter">
                        <i class="fas fa-layer-group"></i> <?= __('store.related_categories') ?>
                        <i class="fas fa-chevron-down amz-filter-toggle"></i>
                    </h3>
                    <div class="amz-filter-body" id="cat-filter">
                        <?php
                        function amz_display_with_children($parentRow, $level = 0) {
                            foreach ($parentRow as $row) {
                                $indent = $level > 0 ? ' style="padding-left:' . ($level * 16) . 'px"' : '';
                                echo '<li data-id="' . $row['id'] . '"' . $indent . ' class="' . ($row['children'] ? 'amz-cat-has-children' : '') . '">';
                                echo '<a href="' . base_url('store/category/' . $row['slug']) . '">' . htmlspecialchars($row['name']) . '</a>';
                                if ($row['children']) {
                                    echo '<i class="fas fa-angle-down amz-cat-toggle"></i>';
                                    echo '<ul class="amz-cat-sub" style="display:none;">';
                                    amz_display_with_children($row['children'], $level + 1);
                                    echo '</ul>';
                                }
                                echo '</li>';
                            }
                        }
                        ?>
                        <ul class="amz-cat-tree">
                            <li data-id="0">
                                <a href="<?= base_url('store/category') ?>"><?= __('store.all_categories') ?></a>
                            </li>
                            <?php amz_display_with_children($category_tree, 0); ?>
                        </ul>
                    </div>
                </div>

                <!-- Search Filter -->
                <div class="amz-filter-block">
                    <h3 class="amz-filter-title" data-toggle="search-filter">
                        <i class="fas fa-search"></i> <?= __('store.refine_by') ?>
                        <i class="fas fa-chevron-down amz-filter-toggle"></i>
                    </h3>
                    <div class="amz-filter-body" id="search-filter">
                        <div class="amz-search-input-group">
                            <input id="searchProduct" type="text" class="amz-search-input" placeholder="<?= __('store.enter_keywords') ?>">
                            <button type="button" id="clear-all-search" class="amz-search-clear" title="<?= __('store.clear_all') ?>"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="amz-filter-block">
                    <h3 class="amz-filter-title" data-toggle="price-filter">
                        <i class="fas fa-dollar-sign"></i> <?= __('store.price') ?>
                        <i class="fas fa-chevron-down amz-filter-toggle"></i>
                    </h3>
                    <div class="amz-filter-body" id="price-filter">
                        <div class="amz-price-range">
                            <div id="slider-range"></div>
                            <div class="amz-price-values">
                                <span class="amz-price-val" id="slider-range-value1"></span>
                                <span class="amz-price-sep">–</span>
                                <span class="amz-price-val" id="slider-range-value2"></span>
                            </div>
                            <button type="button" id="filter-price-range" class="amz-btn-filter"><?= __('store.filter') ?></button>
                        </div>
                        <form>
                            <input type="hidden" name="min-value" value="0">
                            <input type="hidden" name="max-value" value="10000">
                        </form>
                    </div>
                </div>

                <!-- Rating Filter -->
                <div class="amz-filter-block">
                    <h3 class="amz-filter-title" data-toggle="rating-filter">
                        <i class="fas fa-star"></i> <?= __('store.product_rating') ?>
                        <i class="fas fa-chevron-down amz-filter-toggle"></i>
                    </h3>
                    <div class="amz-filter-body" id="rating-filter">
                        <div class="amz-rating-filters">
                            <?php for ($r = 5; $r >= 1; $r--): ?>
                            <label class="amz-rating-row">
                                <input type="radio" name="rating-filter" value="<?= $r ?>">
                                <div class="amz-stars">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <i class="fas fa-star <?= $s <= $r ? 'active' : 'empty' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span>&amp; <?= __('store.up') ?></span>
                            </label>
                            <?php endfor; ?>
                            <button type="button" id="clear-rating-filter" class="amz-btn-text-sm"><?= __('store.clear_all') ?></button>
                        </div>
                    </div>
                </div>

                <!-- Color Filter -->
                <?php if (!empty($store_setting['is_variation_filter'])): ?>
                <div class="amz-filter-block">
                    <h3 class="amz-filter-title" data-toggle="color-filter">
                        <i class="fas fa-palette"></i> <?= __('store.color') ?>
                        <i class="fas fa-chevron-down amz-filter-toggle"></i>
                    </h3>
                    <div class="amz-filter-body" id="color-filter">
                        <div class="amz-color-swatches">
                            <?php if (sizeOf($colors) > 0): ?>
                                <?php for ($i = 0; $i < sizeOf($colors); $i++): ?>
                                    <button type="button" class="amz-swatch" data-color="<?= $colors[$i] ?>" style="background:<?= $colors[$i] ?>" title="<?= $colors[$i] ?>"></button>
                                <?php endfor; ?>
                            <?php else: ?>
                                <button type="button" class="amz-swatch" data-color="#BE0027" style="background:#BE0027"></button>
                                <button type="button" class="amz-swatch" data-color="#CF8D2E" style="background:#CF8D2E"></button>
                                <button type="button" class="amz-swatch" data-color="#E4E932" style="background:#E4E932"></button>
                                <button type="button" class="amz-swatch" data-color="#371777" style="background:#371777"></button>
                                <button type="button" class="amz-swatch" data-color="#037EF3" style="background:#037EF3"></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tag Filter -->
                <div class="amz-filter-block">
                    <h3 class="amz-filter-title" data-toggle="tag-filter">
                        <i class="fas fa-tag"></i> <?= __('store.product_tag') ?>
                        <i class="fas fa-chevron-down amz-filter-toggle"></i>
                    </h3>
                    <div class="amz-filter-body" id="tag-filter">
                        <div class="amz-tag-list">
                            <?php if (sizeOf($tags) > 0): ?>
                                <?php foreach ($tags as $tag): ?>
                                    <button type="button" class="amz-tag-chip" data-tag="<?= htmlspecialchars($tag) ?>"><?= htmlspecialchars($tag) ?></button>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button type="button" class="amz-tag-chip">Lorem Ipsum</button>
                                <button type="button" class="amz-tag-chip">Lorem Ipsum</button>
                                <button type="button" class="amz-tag-chip">Lorem Ipsum</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </aside>

            <!-- Main Content -->
            <div class="amz-category-main">

                <!-- Breadcrumb -->
                <nav class="amz-breadcrumb">
                    <a href="<?= $home_link ?>"><?= __('store.home') ?></a>
                    <span>/</span>
                    <a href="<?= base_url('store/category') ?>"><?= __('store.categories') ?></a>
                    <?php if (!empty($category['name'])): ?>
                    <span>/</span>
                    <span class="amz-breadcrumb-active"><?= htmlspecialchars($category['name']) ?></span>
                    <?php endif; ?>
                </nav>

                <!-- Results Bar -->
                <div class="amz-results-bar">
                    <div class="amz-results-info">
                        <span><?= __('store.showing') ?></span>
                        <strong id="show-count">0</strong>
                        <span>/</span>
                        <strong id="total-count">0</strong>
                        <span><?= __('store.results') ?></span>
                    </div>
                    <div class="amz-sort-group">
                        <label for="sort-by"><?= __('store.sort_by') ?>:</label>
                        <select id="sort-by" class="amz-sort-select">
                            <option value="popular" selected><?= __('store.popular_products') ?></option>
                            <option value="low-to-high"><?= __('store.price_low_to_high') ?></option>
                            <option value="high-to-low"><?= __('store.price_high_to_low') ?></option>
                            <option value="latest"><?= __('store.newest_first') ?></option>
                        </select>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="amz-product-grid product-list"></div>

                <!-- Show More -->
                <a href="javascript:void(0);" class="amz-show-more see-more" data-next_page="1">
                    <span><?= __('store.show_more') ?></span>
                    <i class="fas fa-chevron-down"></i>
                </a>

            </div>
        </div>
    </div>
</section>

<?php include 'product-list-template.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
    load_Product($('#searchProduct').val(), {
        category_slug: '<?= isset($category['slug']) ? $category['slug'] : '' ?>'
    });

    $(document).on('click', '.amz-filter-title', function() {
        var target = $(this).data('toggle');
        var body = $('#' + target);
        body.slideToggle(200);
        $(this).find('.amz-filter-toggle').toggleClass('rotated');
    });

    $(document).on('click', '.amz-cat-has-children > a', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var li = $(this).parent();
        li.find('> .amz-cat-sub').slideToggle(200);
        li.find('> .amz-cat-toggle').toggleClass('rotated');
    });

    $(document).on('click', '.amz-tag-chip', function() {
        $(this).toggleClass('active');
        load_Product($('#searchProduct').val());
    });

    $(document).on('click', '.amz-swatch', function() {
        $(this).toggleClass('active');
        load_Product($('#searchProduct').val());
    });

    $(document).on('click', '.see-more', function() {
        var url = $(location).attr('href'),
            parts = url.split("/"),
            category_slug = parts[parts.length - 1];
        load_Product($('#searchProduct').val(), {
            next_page: $(this).data('next_page'),
            category_slug: category_slug
        });
    });

    $('#searchProduct').keyup(function(e) {
        e.preventDefault();
        load_Product($(this).val());
    });

    $(document).on('change', '#sort-by', function() {
        load_Product($('#searchProduct').val());
    });

    $(document).on('click', '#filter-price-range', function() {
        load_Product($('#searchProduct').val());
    });

    $(document).on('click', 'input[name="rating-filter"]', function() {
        load_Product($('#searchProduct').val());
    });

    $(document).on('click', '#clear-rating-filter', function() {
        $('input[name="rating-filter"]:checked').prop('checked', false);
        load_Product($('#searchProduct').val());
    });

    $(document).on('click', '#clear-all-search', function() {
        $('input[name="rating-filter"]:checked').prop('checked', false);
        $('#searchProduct').val('');
        load_Product($('#searchProduct').val());
    });

    <?php if (!empty($category)): ?>
        var c = $('[data-id="<?= $category['id'] ?>"]').parents("li");
        var ele = c[c.length - 1];
    <?php endif; ?>

    var rangeSlider = document.getElementById('slider-range');
    if (rangeSlider && typeof wNumb !== 'undefined' && typeof noUiSlider !== 'undefined') {
        var moneyFormat = wNumb({
            decimals: 0,
            thousand: ',',
            prefix: $('a[data-currency-symbol]').data('currency-symbol'),
            edit: function(value) {
                return value == "$10,000" ? "$10,000 +" : value;
            }
        });
        noUiSlider.create(rangeSlider, {
            start: [0, 10000],
            step: 50,
            range: { min: [0], max: [10000] },
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

function load_Product(search, postData) {
    var data = postData || {};
    data.search = search;
    data.order_by = $('#sort-by').val();
    data.min_price = $('input[name="min-value"]').val();
    data.max_price = $('input[name="max-value"]').val();

    if ($('input[name="rating-filter"]:checked').length) {
        data.product_avg_rating = $('input[name="rating-filter"]:checked').val();
    }

    data.colors = [];
    data.tags = [];

    $('.amz-tag-chip.active').each(function() {
        data.tags.push($(this).data('tag'));
    });
    $('.amz-swatch.active').each(function() {
        data.colors.push($(this).data('color'));
    });

    data.request_page = 'category';
    data.limit = 15;

    var ajaxReq = $.ajax({
        url: "<?= base_url() ?>" + 'Store/load_Product',
        type: 'POST',
        dataType: 'JSON',
        data: data,
        beforeSend: function() {
            if (ajaxReq !== 'ToCancelPrevReq' && ajaxReq.readyState < 4) ajaxReq.abort();
        },
        success: function(res) {
            if (res.category) {
                if (postData && postData.next_page && postData.next_page > 1) {
                    $('.product-list').append(Mustache.render($('#product-list-template').html(), res.category));
                } else {
                    $('.product-list').html(Mustache.render($('#product-list-template').html(), res.category));
                }

                $('.see-more').data('next_page', res.category.next_page);
                if (res.category.is_last_page) $('.see-more').hide();

                if (res.category.total_count) $('#total-count').text(res.category.total_count);

                if (postData && postData.next_page && postData.next_page > 1) {
                    $('#show-count').text(parseInt($('#show-count').text()) + res.category.count);
                } else {
                    $('#show-count').text(res.category.count);
                }
            }
        }
    });
}
</script>
