<?php
/**
 * Starter 2026 — Vendor Store Page
 *
 * @contract  Store API v1 — page: vendor_store (marketplace / multi-vendor mode)
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url, $category_tree, $add_tocart_url
 *
 * PAGE VARIABLES
 *   $vendor    array   Vendor/seller profile row (username, bio, avatar, banner, ratings, etc.)
 *   $products  array   Products listed by this vendor (paginated)
 *   $settings  array   Store settings
 *   $colors    array   Distinct variation colours in vendor's catalogue
 *   $tags      array   Distinct tags in vendor's catalogue
 */
$currency = $store_setting['currency_sign'] ?? '$';

// Controller provides $store_details array with store_meta JSON
$store_meta = (!empty($store_details['store_meta'])) ? json_decode($store_details['store_meta'], true) : [];

$vendor_name = $store_details['store_name'] ?? __('store.vendor') ?? 'Vendor';
$vendor_owner = $store_details['store_owner'] ?? '';
$vendor_desc = isset($store_meta['store_description']) ? $store_meta['store_description'] : '';
$vendor_avatar = !empty($store_details['avatar'])
    ? base_url('assets/images/users/' . $store_details['avatar'])
    : base_url('assets/store/default/img/blog1.png');
$vendor_banner = isset($store_meta['cover_background']) && !empty($store_meta['cover_background'])
    ? base_url('assets/user_upload/vendor_store/' . $store_meta['cover_background'])
    : '';
$vendor_logo = isset($store_meta['store_logo']) && !empty($store_meta['store_logo'])
    ? base_url('assets/user_upload/vendor_store/' . $store_meta['store_logo'])
    : '';
$cover_text_color = isset($store_meta['cover_text_color']) ? $store_meta['cover_text_color'] : '#FFFFFF';
?>

<!-- Vendor Banner / Header -->
<section class="s26-vendor-header" <?= $vendor_banner ? 'style="background-image:url(' . $vendor_banner . ')"' : '' ?>>
    <div class="s26-vendor-header__overlay"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="s26-vendor-profile">
            <?php if ($vendor_logo): ?>
            <div class="s26-vendor-logo">
                <img src="<?= $vendor_logo ?>" alt="<?= htmlspecialchars($vendor_name) ?>"
                     onerror="this.style.display='none'">
            </div>
            <?php endif; ?>
            <div class="s26-vendor-avatar">
                <img src="<?= $vendor_avatar ?>" alt="<?= htmlspecialchars($vendor_name) ?>"
                     onerror="this.src='<?= base_url('assets/store/default/img/blog1.png') ?>'">
            </div>
            <div class="s26-vendor-info" style="color:<?= $cover_text_color ?>">
                <h1 class="s26-vendor-info__name"><?= htmlspecialchars($vendor_name) ?></h1>
                <?php if (isset($store_meta['cover_show_vendor_name']) && $store_meta['cover_show_vendor_name'] == 1 && !empty($vendor_owner)): ?>
                <p class="s26-vendor-info__owner"><?= htmlspecialchars($vendor_owner) ?></p>
                <?php endif; ?>
                <?php if (!empty($vendor_desc)): ?>
                <p class="s26-vendor-info__desc"><?= htmlspecialchars($vendor_desc) ?></p>
                <?php endif; ?>
                <div class="s26-vendor-info__meta">
                    <?php if (!empty($store_details['store_email'])): ?>
                    <span><i class="fas fa-envelope"></i> <?= $store_details['store_email'] ?></span>
                    <?php endif; ?>
                    <?php if (!empty($store_details['store_contact_number'])): ?>
                    <span><i class="fas fa-phone"></i> <?= $store_details['store_contact_number'] ?></span>
                    <?php endif; ?>
                    <?php if (!empty($store_details['store_address'])): ?>
                    <span><i class="fas fa-map-marker-alt"></i> <?= $store_details['store_address'] ?></span>
                    <?php endif; ?>
                    <?php if (!empty($store_details['country_name'])): ?>
                    <span><i class="fas fa-globe"></i> <?= $store_details['country_name'] ?><?= !empty($store_details['state_name']) ? ', ' . $store_details['state_name'] : '' ?></span>
                    <?php endif; ?>
                </div>
                <div class="s26-vendor-actions mt-3">
                    <a href="javascript:void(0);" class="s26-vendor-contact-btn" data-toggle="modal" data-target="#vendorModal">
                        <i class="fas fa-envelope me-1"></i> <?= __('store.contact_vendor') ?? 'Contact Vendor' ?>
                    </a>
                    <?php if (!empty($store_details['store_terms_condition'])): ?>
                    <a href="javascript:void(0);" class="s26-vendor-terms-btn" data-toggle="modal" data-target="#vendorTermsModal">
                        <i class="fas fa-file-contract me-1"></i> <?= __('store.terms_n_conditions') ?? 'Terms & Conditions' ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<div class="container mt-3">
    <nav class="s26-breadcrumb">
        <a href="<?= $home_link ?? base_url('store/') ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator">/</span>
        <span class="current"><?= htmlspecialchars($vendor_name) ?></span>
    </nav>
</div>

<!-- Main Content: Sidebar + Products -->
<section class="s26-vendor-products">
    <div class="container">
        <div class="row">

            <!-- ═══ SIDEBAR ═══ -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="s26-vs-sidebar">

                    <!-- Sidebar: Vendor Contact Card -->
                    <div class="s26-vs-block">
                        <div class="s26-vs-vendor-card">
                            <div class="s26-vs-vendor-card__avatar">
                                <img src="<?= $vendor_avatar ?>" alt="<?= htmlspecialchars($vendor_name) ?>"
                                     onerror="this.src='<?= base_url('assets/store/default/img/blog1.png') ?>'">
                            </div>
                            <div class="s26-vs-vendor-card__info">
                                <p class="fw-bold mb-1"><?= $store_details['firstname'] ?? '' ?> <?= $store_details['lastname'] ?? '' ?></p>
                                <?php if (!empty($store_details['country_name'])): ?>
                                <div class="s26-vs-vendor-card__country">
                                    <?php if (!empty($store_details['country_code'])): ?>
                                    <img src="<?= function_exists('getFlag') ? getFlag($store_details['country_code']) : '' ?>" alt="<?= __('store.image') ?? '' ?>" style="width:20px;height:14px;object-fit:cover;border-radius:2px">
                                    <?php endif; ?>
                                    <span><?= $store_details['country_name'] ?><?= !empty($store_details['state_name']) ? ', ' . $store_details['state_name'] : '' ?></span>
                                </div>
                                <?php endif; ?>
                                <a href="javascript:void(0);" class="s26-vs-contact-link" data-toggle="modal" data-target="#vendorModal">
                                    <i class="fas fa-comment-dots me-1"></i> <?= __('store.contact_me') ?? 'Contact Me' ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar: Search -->
                    <div class="s26-vs-block">
                        <h3 class="s26-vs-block__title"><?= __('store.refine_by') ?? 'Refine By' ?></h3>
                        <div class="s26-vs-search-wrap">
                            <i class="fas fa-search"></i>
                            <input id="searchProduct" type="text" class="s26-vs-search-input" placeholder="<?= __('store.enter_keywords') ?? 'Enter Keywords' ?>">
                            <a href="javascript:void(0);" id="clear-all-search" class="s26-vs-search-clear" title="<?= __('store.clear_all') ?? 'Clear' ?>">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Sidebar: Price Range -->
                    <div class="s26-vs-block">
                        <h3 class="s26-vs-block__title"><?= __('store.price') ?? 'Price' ?></h3>
                        <div class="s26-vs-price-slider">
                            <div id="slider-range"></div>
                            <div class="s26-vs-price-caption">
                                <span><?= __('store.price') ?? 'Price' ?>:</span>
                                <span id="slider-range-value1"></span>
                                <span> &ndash; </span>
                                <span id="slider-range-value2"></span>
                            </div>
                            <a href="javascript:void(0);" id="filter-price-range" class="s26-vs-filter-btn">
                                <i class="fas fa-filter me-1"></i> <?= __('store.filter') ?? 'Filter' ?>
                            </a>
                            <form>
                                <input type="hidden" name="min-value" value="0">
                                <input type="hidden" name="max-value" value="10000">
                            </form>
                        </div>
                    </div>

                    <!-- Sidebar: Rating Filter -->
                    <div class="s26-vs-block">
                        <h3 class="s26-vs-block__title"><?= __('store.product_rating') ?? 'Product Rating' ?></h3>
                        <div class="s26-vs-rating-filter">
                            <?php for ($r = 5; $r >= 1; $r--): ?>
                            <label class="s26-vs-rating-row">
                                <input type="radio" name="rating-filter" value="<?= $r ?>">
                                <span class="s26-vs-stars">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <i class="fa<?= $s <= $r ? 's' : 'r' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </span>
                                <span class="s26-vs-rating-num">(<?= $r ?>)</span>
                            </label>
                            <?php endfor; ?>
                            <a href="javascript:void(0);" id="clear-rating-filter" class="s26-vs-clear-link">
                                <i class="fas fa-times-circle me-1"></i> <?= __('store.clear_all') ?? 'Clear' ?>
                            </a>
                        </div>
                    </div>

                    <!-- Sidebar: Color Filter (if variation filter enabled) -->
                    <?php
                    if (!empty($store_setting['is_variation_filter'])):
                    ?>
                    <div class="s26-vs-block">
                        <h3 class="s26-vs-block__title"><?= __('store.color') ?? 'Color' ?></h3>
                        <div class="s26-vs-colors">
                            <?php
                            if (isset($colors) && is_array($colors) && count($colors) > 0) {
                                foreach ($colors as $clr) {
                                    echo '<span class="s26-vs-color-swatch" data-color="' . htmlspecialchars($clr) . '" style="background:' . htmlspecialchars($clr) . '"></span>';
                                }
                            } else {
                                $default_colors = ['#BE0027','#CF8D2E','#E4E932','#371777','#037EF3','#000000','#FFFFFF','#808080'];
                                foreach ($default_colors as $clr) {
                                    echo '<span class="s26-vs-color-swatch" data-color="' . $clr . '" style="background:' . $clr . '"></span>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Sidebar: Tags Filter -->
                    <div class="s26-vs-block">
                        <h3 class="s26-vs-block__title"><?= __('store.product_tag') ?? 'Product Tags' ?></h3>
                        <div class="s26-vs-tags">
                            <?php
                            if (isset($tags) && is_array($tags) && count($tags) > 0) {
                                foreach ($tags as $tag) {
                                    echo '<a href="javascript:void(0);" class="s26-vs-tag" data-tag="' . htmlspecialchars($tag) . '">' . htmlspecialchars($tag) . '</a>';
                                }
                            } else {
                                // No tags available for this vendor — show nothing
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ═══ MAIN CONTENT ═══ -->
            <div class="col-lg-9 col-md-8">

                <!-- Sort Bar + Product Count -->
                <div class="s26-vs-topbar">
                    <div class="s26-vs-topbar__count">
                        <?= __('store.showing') ?? 'Showing' ?> <strong id="show-count">0</strong> / <strong id="total-count">0</strong> <?= __('store.results') ?? 'results' ?>
                    </div>
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

                <!-- Product Grid (loaded via AJAX/Mustache) -->
                <div class="row g-3 g-lg-4 product-list"></div>

                <div class="text-center mt-4">
                    <a href="javascript:void(0);" class="s26-btn-outline see-more" data-next_page="1" style="display:none">
                        <i class="fas fa-sync-alt me-1"></i> <?= __('store.show_more') ?? 'Show More' ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include APPPATH . 'views/store/starter2026/product-list-template.php'; ?>


<!-- Contact Vendor Modal -->
<div class="modal fade" id="vendorModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius:var(--s26-radius-lg);overflow:hidden">
            <div class="modal-header" style="background:var(--s26-light);border-bottom:1px solid var(--s26-border-light)">
                <h5 class="modal-title fw-bold"><?= __('store.contact_vendor') ?? 'Contact Vendor' ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="padding:28px">
                <div class="row">
                    <!-- Vendor Contact Info -->
                    <div class="col-md-5 mb-4 mb-md-0">
                        <div class="s26-vendor-modal-info">
                            <div class="s26-vendor-modal-avatar mb-3">
                                <img src="<?= $vendor_avatar ?>" alt="<?= htmlspecialchars($vendor_name) ?>"
                                     onerror="this.src='<?= base_url('assets/store/default/img/blog1.png') ?>'"
                                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--s26-border-light)">
                            </div>
                            <h6 class="fw-bold mb-2"><?= htmlspecialchars($vendor_name) ?></h6>

                            <?php if (!empty($store_details['store_contact_number'])): ?>
                            <p class="mb-2" style="font-size:13px;color:var(--s26-text-muted)">
                                <i class="fas fa-phone me-2" style="width:16px;color:var(--s26-primary)"></i>
                                <?= $store_details['store_contact_number'] ?>
                            </p>
                            <?php endif; ?>
                            <?php if (!empty($store_details['store_email'])): ?>
                            <p class="mb-2" style="font-size:13px;color:var(--s26-text-muted)">
                                <i class="fas fa-envelope me-2" style="width:16px;color:var(--s26-primary)"></i>
                                <?= $store_details['store_email'] ?>
                            </p>
                            <?php endif; ?>
                            <?php if (!empty($store_details['store_address'])): ?>
                            <p class="mb-2" style="font-size:13px;color:var(--s26-text-muted)">
                                <i class="fas fa-map-marker-alt me-2" style="width:16px;color:var(--s26-primary)"></i>
                                <?= $store_details['store_address'] ?>
                            </p>
                            <?php endif; ?>
                            <?php if (!empty($store_details['country_name'])): ?>
                            <p class="mb-0" style="font-size:13px;color:var(--s26-text-muted)">
                                <i class="fas fa-globe me-2" style="width:16px;color:var(--s26-primary)"></i>
                                <?= $store_details['country_name'] ?><?= !empty($store_details['state_name']) ? ', ' . $store_details['state_name'] : '' ?>
                            </p>
                            <?php endif; ?>

                            <!-- Map -->
                            <?php if (!empty($store_details['store_contact_us_map'])): ?>
                            <div class="s26-vendor-modal-map mt-3">
                                <?= htmlspecialchars_decode($store_details['store_contact_us_map']) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Contact Form -->
                    <div class="col-md-7">
                        <h6 class="fw-bold mb-3"><?= __('store.contact_info') ?? 'Contact Info' ?></h6>
                        <form class="s26-vendor-contact-form" action="<?= base_url('store/vendor_contact') ?>" method="post">
                            <input type="hidden" name="vendoremail" value="<?= !empty($store_details['store_email']) ? $store_details['store_email'] : '' ?>">
                            <input type="hidden" name="vendor" value="<?= !empty($store_details['id']) ? $store_details['id'] : '' ?>">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="s26-form-label"><?= __('store.your_name') ?? 'Your Name' ?></label>
                                    <input name="name" type="text" class="s26-form-control form-control" placeholder="<?= __('store.your_name') ?? 'Your Name' ?>">
                                    <p class="error-message text-danger" style="font-size:12px;margin:4px 0 0"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="s26-form-label"><?= __('store.your_email') ?? 'Your Email' ?></label>
                                    <input name="email" type="email" class="s26-form-control form-control" placeholder="<?= __('store.your_email') ?? 'Your Email' ?>">
                                    <p class="error-message text-danger" style="font-size:12px;margin:4px 0 0"></p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="s26-form-label"><?= __('store.your_phone') ?? 'Phone' ?></label>
                                <input name="phone" type="text" class="s26-form-control form-control" placeholder="<?= __('store.your_phone') ?? 'Phone Number' ?>">
                                <p class="error-message text-danger" style="font-size:12px;margin:4px 0 0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="s26-form-label"><?= __('store.message') ?? 'Message' ?></label>
                                <textarea name="message" rows="4" class="s26-form-control form-control" placeholder="<?= __('store.please_enter_your_message_here') ?? 'Write your message...' ?>"></textarea>
                                <p class="error-message text-danger" style="font-size:12px;margin:4px 0 0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="d-flex align-items-center" style="font-size:13px;cursor:pointer">
                                    <input type="checkbox" name="terms" value="1" class="me-2" style="width:18px;height:18px" checked>
                                    <a href="javascript:void(0);" class="vendor-store-terms-condition" style="color:var(--s26-primary);text-decoration:underline">
                                        <?= __('store.terms_n_conditions') ?? 'Terms & Conditions' ?>
                                    </a>
                                </label>
                                <p class="error-message text-danger" style="font-size:12px;margin:4px 0 0"></p>
                            </div>
                            <button type="button" class="s26-btn-primary cn-sbt-btn" style="width:100%;justify-content:center">
                                <i class="fas fa-paper-plane"></i> <?= __('store.send') ?? 'Send Message' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms & Conditions Modal -->
<?php if (!empty($store_details['store_terms_condition'])): ?>
<div class="modal fade" id="vendorTermsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:var(--s26-radius-lg);overflow:hidden">
            <div class="modal-header" style="background:var(--s26-light);border-bottom:1px solid var(--s26-border-light)">
                <h5 class="modal-title fw-bold"><?= __('store.terms_n_conditions') ?? 'Terms & Conditions' ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="padding:28px;line-height:1.8;font-size:14px;color:var(--s26-text)">
                <?= $store_details['store_terms_condition'] ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function() {
    // Initial product load
    load_Product($('#searchProduct').val());

    // Search keyup
    $('#searchProduct').keyup(function(e) {
        e.preventDefault();
        load_Product($(this).val());
    });

    // Clear all search
    $(document).on('click', '#clear-all-search', function(){
        $('input[name="rating-filter"]:checked').prop('checked', false);
        $('#searchProduct').val('');
        $('.s26-vs-tag').removeClass('active');
        $('.s26-vs-color-swatch').removeClass('active');
        load_Product('');
    });

    // Sort change
    $(document).on('change', '#sort-by', function(){
        load_Product($('#searchProduct').val());
    });

    // Show more
    $(document).on('click', '.see-more', function() {
        load_Product($('#searchProduct').val(), {
            next_page: $(this).data('next_page'),
        });
    });

    // Tags filter
    $(document).on('click', '.s26-vs-tag', function() {
        $(this).toggleClass('active');
        load_Product($('#searchProduct').val());
    });

    // Colors filter
    $(document).on('click', '.s26-vs-color-swatch', function() {
        $(this).toggleClass('active');
        load_Product($('#searchProduct').val());
    });

    // Rating filter
    $(document).on('click', 'input[name="rating-filter"]', function(){
        load_Product($('#searchProduct').val());
    });

    // Clear rating filter
    $(document).on('click', '#clear-rating-filter', function(){
        $('input[name="rating-filter"]:checked').prop('checked', false);
        load_Product($('#searchProduct').val());
    });

    // Price range filter button
    $(document).on('click', '#filter-price-range', function(){
        load_Product($('#searchProduct').val());
    });

    // Contact form submission
    $(".cn-sbt-btn").on('click', function(e){
        e.preventDefault();
        var $this = $(this);
        $this.prop('disabled', true);
        var form = $(this).parents('form');
        $.ajax({
            type: 'POST', dataType: 'json',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(result){
                $("input, textarea").removeClass('error');
                $(".error-message").text('');
                if(result.validation){
                    $.each(result.validation, function(key, value){
                        $("[name='" + key + "']").addClass('error');
                        $("[name='" + key + "']").siblings('.error-message').text(value);
                    });
                } else {
                    if(result.status){
                        form[0].reset();
                        $('#vendorModal').modal('hide');
                        Swal.fire({ icon: 'success', html: result.message });
                    } else {
                        Swal.fire({ icon: 'error', html: result.message });
                    }
                }
                $this.prop('disabled', false);
            },
        });
    });

    // Terms & Conditions link inside contact form
    $(".vendor-store-terms-condition").on('click', function(e){
        e.preventDefault();
        $('#vendorTermsModal').modal('show');
    });

    // ── noUiSlider Price Range ──
    var rangeSlider = document.getElementById('slider-range');
    if (rangeSlider && typeof noUiSlider !== 'undefined') {
        var currencySymbol = <?= json_encode($currency) ?>;
        var moneyFormat = typeof wNumb !== 'undefined' ? wNumb({
            decimals: 0,
            thousand: ',',
            prefix: currencySymbol,
            edit: function(value){
                if(value == currencySymbol + "10,000") return currencySymbol + "10,000 +";
                return value;
            }
        }) : { to: function(v){ return currencySymbol + Math.round(v); }, from: function(v){ return Number(String(v).replace(/[^0-9]/g,'')); } };

        noUiSlider.create(rangeSlider, {
            start: [0, 10000],
            step: 50,
            range: { 'min': [0], 'max': [10000] },
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
    postData = postData || {};
    var data = postData;
    data.created_by = <?= $store_details['id']; ?>;
    data.search = search;
    data.order_by = $('#sort-by').val();
    data.request_page = 'category';
    data.limit = 15;
    data.category_slug = '';
    data.min_price = $('input[name="min-value"]').val() || 0;
    data.max_price = $('input[name="max-value"]').val() || 10000;

    // Rating filter
    if ($('input[name="rating-filter"]:checked').length) {
        data.product_avg_rating = $('input[name="rating-filter"]:checked').val();
    }

    // Colors filter
    data.colors = [];
    $('.s26-vs-color-swatch').each(function() {
        if ($(this).hasClass('active')) {
            data.colors.push($(this).data('color'));
        }
    });

    // Tags filter
    data.tags = [];
    $('.s26-vs-tag').each(function() {
        if ($(this).hasClass('active')) {
            data.tags.push($(this).data('tag'));
        }
    });

    var ajaxReq = 'ToCancelPrevReq';
    ajaxReq = $.ajax({
        url: "<?= base_url() ?>Store/load_Product",
        type: 'POST',
        dataType: 'JSON',
        data: data,
        beforeSend: function() {
            if (ajaxReq != 'ToCancelPrevReq' && ajaxReq.readyState < 4) ajaxReq.abort();
        },
        success: function(res) {
            if (res.category) {
                if (postData.next_page && postData.next_page > 1) {
                    $('.product-list').append(Mustache.render($('#product-list-template').html(), res.category));
                } else {
                    $('.product-list').html(Mustache.render($('#product-list-template').html(), res.category));
                }
                $('.see-more').data('next_page', res.category.next_page);
                if (res.category.is_last_page) { $('.see-more').hide(); } else { $('.see-more').show(); }
                if (res.category.total_count) { $('#total-count').text(res.category.total_count); }
                if (postData.next_page && postData.next_page > 1) {
                    $('#show-count').text((parseInt($('#show-count').text()) + res.category.count));
                } else {
                    $('#show-count').text(res.category.count);
                }
            }

            if (res.category && res.category.products && res.category.products.length <= 0 && !(postData.next_page && postData.next_page > 1)) {
                var noHtml = '<div class="col-12 text-center py-5">' +
                    '<i class="fas fa-box-open" style="font-size:48px;color:var(--s26-text-muted);margin-bottom:16px;display:block"></i>' +
                    '<p class="fw-bold" style="color:var(--s26-text-muted)"><?= addslashes(__('store.no_product_avilable_to_store') ?? 'No products available') ?></p></div>';
                $('.product-list').html(noHtml);
            }
        }
    });
}
</script>
