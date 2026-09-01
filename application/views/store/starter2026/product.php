<?php
/**
 * Starter 2026 — Product Detail Page
 *
 * @contract  Store API v1 — page: product
 * @endpoint  GET store/api/v1/pages/product/{slug}
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url, $add_tocart_url
 *
 * PAGE VARIABLES
 *   $product              array        Full product row (product_id, product_name, product_price, product_type, product_quantity, etc.)
 *   $categories           array        Categories this product belongs to
 *   $meta_title           string       Page <title> value
 *   $meta_description     string       SEO meta description
 *   $meta_image           string       Absolute URL of featured image for OG tags
 *   $ratings              array        All approved ratings [{rating_number, rating_name, rating_review, ...}]
 *   $avg_rating           float        Average star rating (0–5)
 *   $review_count         int          Total number of approved reviews
 *   $review_list          array        Full review list (same rows as $ratings, with extra detail)
 *   $all_images           array        Additional gallery images [{product_media_upload_path, ...}]
 *   $all_videos           array        Gallery videos [{product_media_upload_path, product_media_upload_video_image}]
 *   $allowReview          bool         true if logged-in user purchased this product and may review
 *   $login_usr            array|false  Logged-in user session (alias of $client; false if guest)
 *   $is_wishlisted_class  string       "w-listed" if product is in user wishlist, else ""
 *   $setting              array        Payment settings (for payment icons display)
 *   $social_share_modal   string       Pre-rendered social share modal HTML
 *   $user_id              int          Affiliate user ID
 *   $add_tocart_url       string       POST endpoint for add-to-cart
 *   $add_coupon_url       string       POST endpoint for applying a coupon code
 *   $order_id             int|null     Order ID if user already purchased this digital product (enables "Start Course" button)
 *   $user                 array|null   Affiliate/vendor user row [{username, store_slug}] — shown in "Promoted by" banner
 *
 * NOTE  $session is NOT used — use the global $client for the logged-in user identity
 */

// ─── Featured Image Resolution ──────────────────────────────────
$product_featured_image = '';
if (!empty($product['product_featured_image'])) {
    if (strpos($product['product_featured_image'], 'http://') === 0 || strpos($product['product_featured_image'], 'https://') === 0) {
        $product_featured_image = $product['product_featured_image'];
    } else {
        $product_featured_image = base_url('assets/images/product/upload/thumb/' . $product['product_featured_image']);
    }
} else {
    $product_featured_image = base_url('assets/store/default/img/pr-img.png');
}

// ─── Gallery Data ───────────────────────────────────────────────
$allimages = $all_images ?? [];
$allvideo  = $all_videos ?? [];

// ─── Rating Aggregates ──────────────────────────────────────────
$avg_rating   = isset($avg_rating) ? $avg_rating : 0;
$review_count = $review_count ?? 0;

// ─── Rating from controller (for display) ───────────────────────
$ratingAvg       = 0;
$totalRating     = 0;
$numberOfRatings = 0;
if (!empty($ratings)) {
    foreach ($ratings as $rating) {
        $totalRating += (int) $rating['rating_number'];
        $numberOfRatings++;
    }
}
if ($totalRating > 0 && $numberOfRatings > 0) {
    $ratingAvg = number_format(($totalRating / $numberOfRatings), 1);
}
$ratingAvgRounded = (int) round($ratingAvg);

// ─── Variations ─────────────────────────────────────────────────
$variations = [];
if (isset($product['product_variations']) && !empty($product['product_variations'])) {
    $variations = json_decode($product['product_variations']);
}

// ─── Price helpers ──────────────────────────────────────────────
$currency       = $store_setting['currency_sign'] ?? '$';
$has_msrp       = !empty($product['product_msrp']) && (float) $product['product_msrp'] > (float) $product['product_price'];
$discount_pct   = $has_msrp ? round((1 - (float) $product['product_price'] / (float) $product['product_msrp']) * 100) : 0;
$is_digital     = in_array($product['product_type'] ?? '', ['video', 'videolink', 'downloadable']);
$in_stock       = ((int)($product['product_quantity'] ?? 0) > 0 || (int)($product['product_quantity'] ?? 0) == -1);

// ─── YouTube embed helper ───────────────────────────────────────
if (!function_exists('convertToEmbedUrl')) {
    function convertToEmbedUrl($url) {
        if (strpos($url, 'embed') !== false) return $url;
        $videoId = '';
        if (preg_match('/[?&]v=([^&]+)/', $url, $matches))                         $videoId = $matches[1];
        elseif (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches))                $videoId = $matches[1];
        elseif (preg_match('/m\.youtube\.com\/watch\?v=([^&]+)/', $url, $matches))  $videoId = $matches[1];
        if ($videoId) return "https://www.youtube.com/embed/{$videoId}";
        if (strpos($url, 'vimeo.com') !== false && preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return "https://player.vimeo.com/video/{$matches[1]}";
        }
        return $url;
    }
}

// ─── Current URL ────────────────────────────────────────────────
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// ─── Reviews (provided by controller) ───────────────────────────
$review_list = $review_list ?? [];
?>

<!-- ═══════════════════════════════════════════════════════════════
     JSON-LD STRUCTURED DATA — Product Schema
     ═══════════════════════════════════════════════════════════════ -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": <?= json_encode($product['product_name']) ?>,
    "description": <?= json_encode(strip_tags($product['product_short_description'] ?? '')) ?>,
    "image": <?= json_encode($product_featured_image) ?>,
    "sku": <?= json_encode($product['product_id']) ?>,
    "url": <?= json_encode(current_url()) ?>,
    "offers": {
        "@type": "Offer",
        "price": <?= json_encode(number_format((float)($product['product_price'] ?? 0), 2, '.', '')) ?>,
        "priceCurrency": <?= json_encode($store_setting['currency_code'] ?? 'USD') ?>,
        "availability": "<?= $in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>",
        "url": <?= json_encode(current_url()) ?>
    }
    <?php if ($review_count > 0): ?>,
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?= $avg_rating ?>",
        "reviewCount": "<?= $review_count ?>"
    }
    <?php endif; ?>
}
</script>

<!-- JSON-LD BreadcrumbList -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": <?= json_encode($home_link ?? base_url('store')) ?>},
        {"@type": "ListItem", "position": 2, "name": "Products", "item": <?= json_encode(base_url('store/category')) ?>},
        {"@type": "ListItem", "position": 3, "name": <?= json_encode($product['product_name']) ?>}
    ]
}
</script>


<!-- ═══════════════════════════════════════════════════════════════
     BREADCRUMB
     ═══════════════════════════════════════════════════════════════ -->
<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?>"><?= __('store.home') ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <a href="<?= $base_url ?>category"><?= __('store.categories') ?></a>
        <?php if (!empty($categories)):
            foreach ($categories as $cat): ?>
                <span class="separator"><i class="fas fa-chevron-right"></i></span>
                <a href="<?= base_url('store/category/' . $cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></a>
            <?php endforeach;
        endif; ?>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= htmlspecialchars($product['product_name'] ?? '') ?></span>
    </nav>
</div>


<!-- ═══════════════════════════════════════════════════════════════
     PRODUCT DETAIL — Main Grid
     ═══════════════════════════════════════════════════════════════ -->
<section class="s26-product-detail single-product" data-product-id="<?= $product['product_id'] ?>" data-product-image="<?= htmlspecialchars($product_featured_image) ?>">
    <div class="container">
        <div class="row g-4 g-lg-5">

            <!-- ────────────────────────────────────────────────────
                 LEFT COLUMN — Image Gallery
                 ──────────────────────────────────────────────────── -->
            <div class="col-lg-6">
                <div class="s26-gallery">
                    <!-- Main Image -->
                    <div class="s26-gallery-main product-main-image" id="s26-main-image-wrap">
                        <img src="<?= $product_featured_image ?>"
                             alt="<?= htmlspecialchars($product['product_name']) ?>"
                             id="s26-main-image"
                             onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>';">
                    </div>

                    <!-- Thumbnails -->
                    <div class="s26-gallery-thumbs" id="s26-thumbs">
                        <!-- Featured image thumb -->
                        <img src="<?= $product_featured_image ?>"
                             alt="<?= __('store.featured_image') ?>"
                             class="active"
                             data-type="image"
                             data-full="<?= $product_featured_image ?>"
                             onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>';">

                        <?php foreach ($allimages as $img):
                            $imgPath = $img['product_media_upload_path'] ?? '';
                            $imgSrc = (strpos($imgPath, 'http://') === 0 || strpos($imgPath, 'https://') === 0 || strpos($imgPath, 's3://') === 0)
                                ? $imgPath
                                : (!empty($imgPath) ? base_url('assets/images/product/upload/thumb/' . $imgPath) : base_url('assets/store/default/img/pr-img.png'));
                        ?>
                        <img src="<?= $imgSrc ?>"
                             alt="<?= __('store.product_image') ?>"
                             data-type="image"
                             data-full="<?= $imgSrc ?>"
                             onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>';">
                        <?php endforeach; ?>

                        <?php foreach ($allvideo as $vid):
                            $vidThumb = (!empty($vid['product_media_upload_video_image']))
                                ? base_url('assets/images/product/upload/thumb/' . $vid['product_media_upload_video_image'])
                                : base_url('assets/store/default/img/pr-img.png');
                            $vidSrc = convertToEmbedUrl($vid['product_media_upload_path'] ?? '');
                        ?>
                        <img src="<?= $vidThumb ?>"
                             alt="<?= __('store.product_video_image') ?? 'Video' ?>"
                             data-type="video"
                             data-video="<?= $vidSrc ?>"
                             onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>';"
                             style="position:relative;">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>


            <!-- ────────────────────────────────────────────────────
                 RIGHT COLUMN — Product Info
                 ──────────────────────────────────────────────────── -->
            <div class="col-lg-6">
                <div class="s26-product-info">

                    <!-- Product Name -->
                    <h1 class="product-title"><?= htmlspecialchars($product['product_name'] ?? '') ?></h1>

                    <!-- Rating + Reviews Count -->
                    <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                        <div class="s26-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="<?= $i <= $ratingAvgRounded ? 'fas' : 'far' ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <a href="#s26-reviews-tab" class="s26-pdp-review-link" style="font-size:13px;font-weight:600;color:var(--s26-text-muted)">
                            <?= $numberOfRatings ?> <?= __('store.customer_reviews') ?>
                        </a>

                        <?php if (!empty($product['product_sku'])): ?>
                        <span style="font-size:12px;color:var(--s26-text-muted)">
                            <span style="opacity:.4">|</span>
                            <?= __('store.sku') ?>: <?= $product['product_sku'] ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Seller & Category Meta -->
                    <div class="s26-pdp-meta d-flex flex-wrap gap-2 mb-4" style="font-size:13px;color:var(--s26-text-muted);">
                        <?php if (!empty($product['product_created_by_name'])): ?>
                        <span>
                            <i class="fas fa-user" style="font-size:11px;opacity:.5"></i>
                            <a href="<?= base_url('store/productionstore/' . base64_encode($product['product_created_by'])) ?>" style="color:var(--s26-text-muted)">
                                <?= htmlspecialchars($product['product_created_by_name']) ?>
                            </a>
                        </span>
                        <span style="opacity:.3">|</span>
                        <?php endif; ?>

                        <?php if (isset($user) && !empty($user['username'])): ?>
                        <span><?= __('store.promoted_by') ?>:
                            <?php if (!empty($user['store_slug'])): ?>
                                <a href="<?= base_url('store/' . $user['store_slug']) ?>" style="color:var(--s26-text-muted)"><?= $user['username'] ?></a>
                            <?php else: ?>
                                <?= $user['username'] ?>
                            <?php endif; ?>
                        </span>
                        <span style="opacity:.3">|</span>
                        <?php endif; ?>

                        <span id="product-category"
                              data-product_id="<?= $product['product_id'] ?>"
                              data-category_id="<?= !empty($categories) ? $categories[0]['id'] ?? '' : '' ?>">
                            <?= __('store.category') ?>:
                            <?php
                            $categotyAvailble = false;
                            if (!empty($categories)) {
                                foreach ($categories as $cat) {
                                    $categotyAvailble = true;
                                    echo '<a href="' . base_url('store/category/' . $cat['slug']) . '" style="color:var(--s26-primary)">' . htmlspecialchars($cat['name']) . '</a> ';
                                }
                            }
                            if (!$categotyAvailble) echo __('store.not_available');
                            ?>
                        </span>
                    </div>

                    <!-- Price Box -->
                    <div class="s26-price-box">
                        <span class="current-price sale-price product-price" data-price="<?= $product['product_price'] ?>">
                            <?= (!empty($product['product_price'])) ? c_format($product['product_price']) : '' ?>
                        </span>
                        <?php if ($has_msrp): ?>
                        <span class="original-price regular-price" data-price="<?= $product['product_msrp'] ?>">
                            <?= c_format($product['product_msrp']) ?>
                        </span>
                        <span class="discount-pct">-<?= $discount_pct ?>%</span>
                        <?php endif; ?>
                    </div>

                    <!-- Stock Status Badge -->
                    <?php if ($in_stock): ?>
                        <?php if ((int) $product['product_quantity'] > 0 && (int) $product['product_quantity'] <= 10): ?>
                        <div class="s26-stock-badge s26-stock-badge--low mb-3">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= __('store.only') ?? 'Only' ?> <?= $product['product_quantity'] ?> <?= __('store.left_in_stock') ?? 'left in stock' ?>
                        </div>
                        <?php else: ?>
                        <div class="s26-stock-badge s26-stock-badge--in mb-3">
                            <i class="fas fa-check-circle"></i>
                            <?= __('store.in_stock') ?? 'In Stock' ?>
                            <?php if ((int) $product['product_quantity'] > 0): ?>
                                (<?= $product['product_quantity'] ?>)
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                    <div class="s26-stock-badge s26-stock-badge--out mb-3">
                        <i class="fas fa-times-circle"></i>
                        <?= __('store.out_of_stock') ?>
                    </div>
                    <?php endif; ?>

                    <!-- Short Description -->
                    <?php if (!empty($product['product_short_description'])): ?>
                    <div class="s26-pdp-short-desc mb-4">
                        <?= $product['product_short_description'] ?>
                    </div>
                    <?php endif; ?>

                    <!-- ── Variations ─────────────────────────────── -->
                    <?php if (!empty($variations)):
                        foreach ($variations as $key => $value): ?>
                    <div class="s26-variation-group mb-3 variation-row <?= ($key != 'colors') ? 'ft-variation-row' : 'ft-color-row' ?>">
                        <label class="s26-variation-label"><?= ucwords(strtolower($key)) ?></label>
                        <div class="s26-variation-options variations">
                            <?php for ($i = 0; $i < sizeOf($value); $i++):
                                $this_price = isset($value[$i]->price) ? $value[$i]->price : 0;
                                if ($key == 'colors'):
                            ?>
                            <span class="s26-variation-chip s26-variation-chip--color"
                                  data-variation-type="<?= $key ?>"
                                  data-variation-price="<?= $this_price ?>"
                                  data-variation-code="<?= $value[$i]->code ?>"
                                  data-variation-name="<?= $value[$i]->name ?>">
                                <i class="s26-color-dot" style="background:<?= $value[$i]->code ?>"></i>
                                <?= $value[$i]->name ?>
                            </span>
                            <?php else:
                                $this_name = isset($value[$i]->name) ? $value[$i]->name : $value[$i];
                            ?>
                            <span class="s26-variation-chip"
                                  data-variation-type="<?= $key ?>"
                                  data-variation-price="<?= $this_price ?>"
                                  data-variation-option="<?= $this_name ?>">
                                <?= $this_name ?>
                            </span>
                            <?php
                                endif;
                            endfor; ?>
                        </div>
                    </div>
                    <?php endforeach;
                    endif; ?>


                    <!-- ── Quantity + Add to Cart ─────────────────── -->
                    <div class="s26-add-to-cart" x-data="amzQty()">
                        <!-- Quantity Selector -->
                        <div class="s26-quantity-selector" id="field1">
                            <button type="button" class="sub" @click="dec()" <?= $is_digital ? 'disabled' : '' ?>>
                                <i class="fas fa-minus" style="font-size:12px"></i>
                            </button>
                            <input type="text" id="product-quantity" min="1" name="quantity" x-model.number="qty" @change="validate()" value="1" <?= $is_digital ? 'disabled' : '' ?>>
                            <button type="button" class="add" @click="inc()" <?= $is_digital ? 'disabled' : '' ?>>
                                <i class="fas fa-plus" style="font-size:12px"></i>
                            </button>
                        </div>

                        <!-- Add to Cart / Start Course / Out of Stock -->
                        <?php if (isset($order_id) && $order_id && $is_digital): ?>
                            <?php $urls = base_url('store/vieworderdetails/' . $order_id . '?referance=' . $product['product_id']); ?>
                            <button class="s26-btn-primary flex-fill justify-content-center" onclick="location.href='<?= $urls ?>'">
                                <i class="fas fa-play-circle"></i>
                                <?= __('store.start_course') ?>
                            </button>
                        <?php elseif ($in_stock): ?>
                            <button class="s26-btn-primary flex-fill justify-content-center btn-cart"
                                    data-product_id="<?= $product['product_id'] ?>"
                                    data-product_name="<?= htmlspecialchars($product['product_name'] ?? 'Product') ?>">
                                <i class="fas fa-shopping-bag"></i>
                                <?= __('store.add_to_cart') ?>
                            </button>
                        <?php else: ?>
                            <button class="s26-btn-primary flex-fill justify-content-center" disabled style="opacity:.5;cursor:not-allowed">
                                <i class="fas fa-ban"></i>
                                <?= __('store.out_of_stock') ?>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Wishlist + Share Row -->
                    <div class="d-flex align-items-center gap-3 mb-4 mt-2">
                        <button type="button" id="btn-add-to-wishlist" class="s26-action-btn <?= $is_wishlisted_class ?? '' ?>">
                            <i class="fa fa-heart"></i>
                            <span><?= __('store.wishlist') ?? 'Wishlist' ?></span>
                        </button>
                        <button type="button" class="s26-action-btn" data-social-share data-share-url="<?= $actual_link ?>">
                            <i class="fas fa-share-alt"></i>
                            <span><?= __('store.share') ?? 'Share' ?></span>
                        </button>
                    </div>

                    <!-- Coupon Code -->
                    <div class="s26-coupon-row">
                        <div class="s26-coupon-input-group">
                            <i class="fas fa-ticket-alt" style="color:var(--s26-text-muted);font-size:14px"></i>
                            <input type="text" class="coupon-code" name="coupon" placeholder="<?= __('store.enter_coupon_code') ?>">
                            <button type="button" class="btn-apply-coupon"><?= __('store.apply') ?></button>
                        </div>
                        <div class="coupon-msg mt-2"></div>
                    </div>

                    <!-- Tags -->
                    <?php
                    $product_tags_arr = parse_product_tags($product['product_tags'] ?? '');
                    if (!empty($product_tags_arr)):
                    ?>
                    <div class="s26-pdp-tags mt-4">
                        <span class="s26-pdp-tags-label">
                            <i class="fas fa-tags"></i> <?= __('store.tags') ?>:
                        </span>
                        <?php foreach ($product_tags_arr as $tag): ?>
                        <a href="<?= base_url('store/category?tag=' . urlencode($tag)) ?>" class="s26-tag-chip"><?= htmlspecialchars($tag) ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Trust Signals -->
                    <div class="s26-trust-strip mt-4">
                        <div class="s26-trust-item">
                            <i class="fas fa-shield-alt"></i>
                            <span><?= __('store.secure_payment') ?? 'Secure Payment' ?></span>
                        </div>
                        <div class="s26-trust-item">
                            <i class="fas fa-truck"></i>
                            <span><?= __('store.fast_delivery') ?? 'Fast Delivery' ?></span>
                        </div>
                        <div class="s26-trust-item">
                            <i class="fas fa-undo"></i>
                            <span><?= __('store.easy_returns') ?? 'Easy Returns' ?></span>
                        </div>
                    </div>

                </div><!-- /.s26-product-info -->
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->


        <!-- ═══════════════════════════════════════════════════════
             PRODUCT TABS — Description + Reviews
             ═══════════════════════════════════════════════════════ -->
        <div class="s26-product-tabs">
            <ul class="nav nav-tabs" id="s26ProductTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="s26-desc-tab"
                            data-bs-toggle="tab" data-bs-target="#s26-desc-pane"
                            type="button" role="tab" aria-selected="true">
                        <i class="fas fa-file-alt me-1" style="font-size:13px"></i>
                        <?= __('store.description') ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="s26-reviews-tab"
                            data-bs-toggle="tab" data-bs-target="#s26-reviews-pane"
                            type="button" role="tab" aria-selected="false">
                        <i class="fas fa-star me-1" style="font-size:13px"></i>
                        <?= __('store.reviews') ?> (<?= $numberOfRatings ?>)
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="s26ProductTabContent">

                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="s26-desc-pane" role="tabpanel">
                    <?php if (!empty($product['product_description'])): ?>
                        <iframe class="s26-desc-iframe" id="s26DescIframe" srcdoc="" frameborder="0" scrolling="no"
                                style="width:100%;border:none;overflow:hidden;min-height:60px"></iframe>
                        <script>
                        (function(){
                            var raw = <?= json_encode(html_entity_decode($product['product_description'])) ?>;
                            var iframe = document.getElementById('s26DescIframe');
                            var doc = '<html><head><style>body{margin:0;padding:0;font-family:inherit;font-size:15px;line-height:1.8;color:#1e293b;overflow:hidden}img{max-width:100%;height:auto}a{color:#2563eb}</style></head><body>' + raw + '</body></html>';
                            iframe.setAttribute('srcdoc', doc);
                            // Auto-resize iframe to content height
                            iframe.onload = function(){
                                try {
                                    var h = iframe.contentDocument.body.scrollHeight;
                                    iframe.style.height = (h + 20) + 'px';
                                } catch(e){}
                            };
                        })();
                        </script>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt" style="font-size:48px;color:var(--s26-border);margin-bottom:16px;display:block"></i>
                            <p class="text-muted"><?= __('store.product_description_not_available') ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="s26-reviews-pane" role="tabpanel">

                    <!-- Reviews Summary Bar -->
                    <div class="s26-reviews-summary">
                        <div class="s26-reviews-summary__score">
                            <span class="s26-reviews-summary__number"><?= number_format($ratingAvg, 1) ?></span>
                            <div>
                                <div class="s26-stars" style="font-size:16px">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $ratingAvgRounded ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted"><?= $numberOfRatings ?> <?= __('store.customer_reviews') ?></small>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="s26-reviews-list">
                        <?php if (!empty($review_list)):
                            foreach ($review_list as $rev):
                                $rev_stars = (int)($rev['rating_number'] ?? 0);
                        ?>
                        <div class="s26-review-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="s26-review-avatar">
                                    <?= strtoupper(substr($rev['user_name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                        <div>
                                            <span class="reviewer-name"><?= htmlspecialchars($rev['user_name'] ?? __('store.anonymous') ?? 'Anonymous') ?></span>
                                            <span class="review-date ms-2"><?= !empty($rev['rating_created']) ? date('M d, Y', strtotime($rev['rating_created'])) : '' ?></span>
                                        </div>
                                        <div class="s26-stars" style="font-size:12px">
                                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                                <i class="<?= $s <= $rev_stars ? 'fas' : 'far' ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="review-text mb-0"><?= htmlspecialchars($rev['rating_comment'] ?? '') ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;
                        else: ?>
                            <div class="text-center py-5">
                                <i class="far fa-comment-dots" style="font-size:48px;color:var(--s26-border);margin-bottom:16px;display:block"></i>
                                <p class="text-muted mb-0"><?= __('store.there_are_no_reviews_for_this_product') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Write a Review Form (logged-in users) -->
                    <?php if (isset($allowReview) && $allowReview): ?>
                    <div class="s26-review-form-wrap mt-4">
                        <h5 class="fw-bold mb-3" style="font-size:16px;letter-spacing:-0.01em">
                            <i class="fas fa-pen me-2" style="font-size:14px;color:var(--s26-primary)"></i>
                            <?= __('store.write_a_review') ?>
                        </h5>
                        <div id="createRatting" class="create_Rating">
                            <input type="hidden" name="user_id" id="user_id" value="<?= !empty($client) ? (int)$client['id'] : '' ?>">
                            <input type="hidden" name="product_id" id="product_id" value="<?= $product['product_id'] ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px"><?= __('store.rating') ?></label>
                                <div class="give-rating"></div>
                                <input type="hidden" name="rating" value="0" id="rating_star">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px"><?= __('store.your_review') ?></label>
                                <textarea name="comment" id="comment"
                                          class="form-control"
                                          placeholder="<?= __('store.enter_your_review') ?>"
                                          rows="4"
                                          style="border-radius:var(--s26-radius-sm);border-color:var(--s26-border);font-size:14px"></textarea>
                                <small class="text-muted mt-1 d-block">
                                    <span class="text-danger"><?= __('store.note') ?></span> <?= __('store.html_is_not_translated') ?>
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px"><?= __('store.email') ?></label>
                                <input type="email" name="email" id="post_email"
                                       class="form-control"
                                       placeholder="<?= __('store.enter_your_email') ?>"
                                       style="border-radius:var(--s26-radius-sm);border-color:var(--s26-border);font-size:14px">
                            </div>

                            <button type="button" class="s26-btn-primary" id="submit" onclick="processRating()">
                                <i class="fas fa-paper-plane"></i>
                                <?= __('store.leave_a_review') ?>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>

                </div><!-- /#s26-reviews-pane -->
            </div><!-- /.tab-content -->
        </div><!-- /.s26-product-tabs -->

    </div><!-- /.container -->
</section>


<!-- ═══════════════════════════════════════════════════════════════
     RELATED PRODUCTS
     ═══════════════════════════════════════════════════════════════ -->
<section class="s26-related-products">
    <div class="container">
        <div class="s26-section-header">
            <div>
                <p class="s26-section-eyebrow"><?= __('store.you_may_also_like') ?? 'You May Also Like' ?></p>
                <h2 class="s26-section-title"><?= __('store.similar_products') ?></h2>
            </div>
        </div>
        <div class="row g-3 g-lg-4 product-list-related"></div>
        <div class="text-center mt-4">
            <a href="javascript:void(0);" class="s26-btn-outline see-more see-more-related" data-next_page="1" style="display:none">
                <i class="fas fa-sync-alt me-1"></i> <?= __('store.show_more') ?>
            </a>
        </div>
    </div>
</section>


<!-- Social Share Modal -->
<?php if (isset($social_share_modal)) echo $social_share_modal; ?>

<!-- Mustache Template for Related Products -->
<?php include APPPATH . 'views/store/starter2026/product-list-template.php'; ?>


<!-- ═══════════════════════════════════════════════════════════════
     STICKY ADD-TO-CART BAR (appears on scroll)
     ═══════════════════════════════════════════════════════════════ -->
<div class="s26-sticky-bar" id="s26-sticky-bar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3 min-width-0">
                <img src="<?= $product_featured_image ?>" alt=""
                     class="s26-sticky-bar__img"
                     onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>';">
                <div class="d-none d-md-block" style="min-width:0">
                    <p class="mb-0 fw-bold text-truncate" style="font-size:14px;max-width:280px"><?= htmlspecialchars($product['product_name']) ?></p>
                    <p class="mb-0 fw-bold" style="font-size:16px;color:var(--s26-primary)"><?= c_format($product['product_price']) ?></p>
                </div>
            </div>
            <?php if ($in_stock && !(isset($order_id) && $order_id && $is_digital)): ?>
            <button type="button"
                    class="s26-btn-primary btn-cart"
                    data-product_id="<?= $product['product_id'] ?>"
                    data-product_name="<?= htmlspecialchars($product['product_name'] ?? 'Product') ?>"
                    style="white-space:nowrap">
                <i class="fas fa-shopping-bag"></i>
                <?= __('store.add_to_cart') ?>
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════════════
     PRODUCT PAGE INLINE STYLES
     ═══════════════════════════════════════════════════════════════ -->

<!-- ═══════════════════════════════════════════════════════════════
     PRODUCT PAGE JAVASCRIPT
     ═══════════════════════════════════════════════════════════════ -->
<script type="text/javascript">
$(document).ready(function() {

    // ── Image Gallery ────────────────────────────────────────────
    $(document).on('click', '#s26-thumbs img', function() {
        var $this = $(this);
        $('#s26-thumbs img').removeClass('active');
        $this.addClass('active');

        var type = $this.data('type');
        var $mainWrap = $('#s26-main-image-wrap');

        if (type === 'video') {
            var videoUrl = $this.data('video');
            $mainWrap.html('<iframe class="s26-video-frame" src="' + videoUrl + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;height:100%;min-height:400px;border-radius:var(--s26-radius-lg);"></iframe>');
        } else {
            var fullSrc = $this.data('full');
            $mainWrap.html('<img src="' + fullSrc + '" alt="Product" id="s26-main-image" onerror="this.src=\'<?= base_url('assets/store/default/img/no-image.png') ?>\';">');
        }
    });

    // ── Quantity Controls ────────────────────────────────────────
    $(document).on('click', '.s26-quantity-selector .add', function() {
        var $inp = $(this).siblings('input');
        if (parseInt($inp.val()) < 350) $inp.val(parseInt($inp.val()) + 1);
    });
    $(document).on('click', '.s26-quantity-selector .sub', function() {
        var $inp = $(this).siblings('input');
        if (parseInt($inp.val()) > 1) $inp.val(parseInt($inp.val()) - 1);
    });

    // ── Variation Selection ──────────────────────────────────────
    $(document).on('click', '.s26-variation-options span', function() {
        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');
        $('.btn-cart').removeAttr('data-original-title');
        if (typeof hideTooltip === 'function') hideTooltip();
        display_price_changes();
    });

    // ── Fix DOM if description HTML broke tab structure ─────────
    (function() {
        var tabContent = document.getElementById('s26ProductTabContent');
        var reviewsPane = document.getElementById('s26-reviews-pane');
        if (tabContent && reviewsPane && reviewsPane.parentNode !== tabContent) {
            tabContent.appendChild(reviewsPane);
        }
    })();

    // ── Manual Tab Switching (bypass Bootstrap Tab API) ──────────
    $('#s26ProductTabs button[data-bs-toggle="tab"]').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var target = $(this).attr('data-bs-target');
        // Deactivate all tab buttons
        $('#s26ProductTabs .nav-link').removeClass('active').attr('aria-selected', 'false');
        // Activate clicked tab button
        $(this).addClass('active').attr('aria-selected', 'true');
        // Hide all panes
        $('#s26ProductTabContent > .tab-pane').removeClass('show active');
        // Show target pane
        $(target).addClass('show active');
    });

    // ── Tab switching via review link (in product header) ────────
    $(document).on('click', '.s26-pdp-review-link', function(e) {
        e.preventDefault();
        var $btn = $('#s26-reviews-tab');
        if ($btn.length) {
            $btn.trigger('click');
            $btn[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    // ── Wishlist Toggle ──────────────────────────────────────────
    $(document).on('click', '#btn-add-to-wishlist', function() {
        <?php if (isset($login_usr) && $login_usr == false): ?>
            window.location.replace('<?= base_url('store/login') ?>');
        <?php else: ?>
            $(this).toggleClass('w-listed');
            $.ajax({
                url: '<?= base_url('Store/toggle_wishlist') ?>',
                type: 'POST',
                dataType: 'json',
                data: { product_id: <?= $product['product_id'] ?> },
                success: function(json) { }
            });
        <?php endif; ?>
    });

    // ── Star Rating Widget ───────────────────────────────────────
    if ($('.give-rating').length) {
        $('.give-rating').starRating({
            initialRating: 0,
            starSize: 24,
            readOnly: false,
            disableAfterRate: false,
            callback: function(currentRating, $el) {
                $('#rating_star').val(currentRating);
            }
        });
    }

    // ── Coupon Apply ─────────────────────────────────────────────
    $(document).on('click', '.btn-apply-coupon', function() {
        var coupon_code = $(".s26-coupon-input-group .coupon-code").val();
        var product_id  = '<?= $product['product_id'] ?>';
        if (coupon_code != '' && coupon_code != null) {
            var $this = $(this);
            $.ajax({
                url: '<?= isset($add_coupon_url) ? $add_coupon_url : '' ?>',
                type: 'POST',
                dataType: 'json',
                data: { product_id: product_id, coupon_code: coupon_code },
                beforeSend: function() { $this.btn("loading"); },
                complete:   function() { $this.btn("reset"); },
                success: function(json) {
                    $(".coupon-msg").html('');
                    if (json['success']) {
                        $(".coupon-msg").html('<div class="alert alert-success" style="border-radius:var(--s26-radius-sm);font-size:13px;padding:10px 16px">' + json['success'] + '</div>');
                    }
                    if (json['error']) {
                        $(".coupon-msg").html('<div class="alert alert-danger" style="border-radius:var(--s26-radius-sm);font-size:13px;padding:10px 16px">' + json['error'] + '</div>');
                    }
                }
            });
        }
    });

    // ── Related Products Load ────────────────────────────────────
    load_Product(null, {
        product_id:  $('#product-category').data('product_id'),
        category_id: $('#product-category').data('category_id')
    });

    $(document).on('click', '.see-more-related', function() {
        load_Product(null, {
            next_page:   $(this).data('next_page'),
            product_id:  $('#product-category').data('product_id'),
            category_id: $('#product-category').data('category_id')
        });
    });

    // ── Sticky Bar on Scroll ─────────────────────────────────────
    var $stickyBar = $('#s26-sticky-bar');
    var $addToCart = $('.s26-add-to-cart');
    if ($stickyBar.length && $addToCart.length) {
        $(window).on('scroll', function() {
            var cartBottom = $addToCart.offset().top + $addToCart.outerHeight();
            if ($(window).scrollTop() > cartBottom) {
                $stickyBar.addClass('visible');
            } else {
                $stickyBar.removeClass('visible');
            }
        });
    }
});

// ── Process Rating (AJAX) ────────────────────────────────────────
function processRating() {
    var name        = ($('#name').length > 0) ? $('#name').val() : '';
    var email       = $('#post_email').val();
    var rating_star = $('#rating_star').val();
    var product_id  = $('#product_id').val();
    var user_id     = $('#user_id').val();
    var comment     = $('#comment').val();

    if (comment != '' && rating_star != 0) {
        $('#submit').prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>product/rating',
            data: 'product_id=' + product_id + '&user_id=' + user_id + '&comment=' + comment + '&name=' + name + '&email=' + email + '&number=' + rating_star,
            success: function(data) {
                window.location.reload();
                $('#submit').prop('disabled', false);
            }
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: '<?= __('store.please_write_some_comment') ?>',
            confirmButtonColor: 'var(--s26-primary)'
        });
    }
}

// ── Load Related Products ────────────────────────────────────────
function load_Product(search, postData) {
    postData = postData || {};
    var data = postData;
    data.search = search;
    data.request_page = 'product-details';
    var ajaxReq = 'ToCancelPrevReq';
    ajaxReq = $.ajax({
        url: '<?= base_url() ?>Store/load_Product',
        type: 'POST',
        dataType: 'JSON',
        data: data,
        beforeSend: function() {
            if (ajaxReq != 'ToCancelPrevReq' && ajaxReq.readyState < 4) ajaxReq.abort();
        },
        success: function(res) {
            if (res.related) {
                if (postData.next_page && postData.next_page > 1) {
                    $('.product-list-related').append(Mustache.render($('#product-list-template').html(), res.related));
                } else {
                    $('.product-list-related').html(Mustache.render($('#product-list-template').html(), res.related));
                }
                $('.see-more-related').data('next_page', res.related.next_page);
                if (res.related.is_last_page) {
                    $('.see-more-related').hide();
                } else {
                    $('.see-more-related').show();
                }
            }
        }
    });
}

// ── Display Price Changes (Variations) ───────────────────────────
function display_price_changes() {
    var variationSelectedPrice = 0;
    if ($('.variation-row .variations').length != 0) {
        $('.variation-row .variations').each(function() {
            var optionSpan = $(this).find('.active');
            variationSelectedPrice += optionSpan.length ? parseFloat(optionSpan.data('variation-price')) : 0;
        });
    }

    var currencyRatio        = '<?= str_replace(',', '', c_format(1, false)) ?>';
    var currency             = $('a[data-currency-symbol]').data('currency-symbol');
    var product_regular_price = $('.regular-price').attr('data-price');
    var product_sale_price    = $('.sale-price').attr('data-price');

    $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>product/displayprice',
        data: 'currencyRatio=' + currencyRatio + '&currency=' + currency + '&variationSelectedPrice=' + variationSelectedPrice + '&product_regular_price=' + product_regular_price + '&product_sale_price=' + product_sale_price,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            $('.sale-price').text(obj.value1);
            $('.regular-price').text(obj.value2);
        }
    });
}
</script>
