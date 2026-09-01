<?php
/**
 * Starter 2026 — Home Page
 *
 * @contract  Store API v1 — page: home
 * @endpoint  GET store/api/v1/pages/home
 *
 * GLOBALS (injected by Storeapp::view on every page)
 *   $store_setting  array   All store settings (name, logo, currency, etc.)
 *   $SiteSetting    array   Global site settings
 *   $client         array   Logged-in customer (empty if guest)
 *   $home_link      string  URL to the store homepage
 *   $base_url       string  Full site base URL
 *   $store_currency string  Active currency code
 *   $LanguageHtml   string  Language switcher HTML
 *   $CurrencyHtml   string  Currency switcher HTML
 *   $category_tree  array   Full nested category tree
 *   $googlerecaptcha array  reCAPTCHA settings
 *   $add_tocart_url string  POST URL for add-to-cart
 *
 * PAGE VARIABLES
 *   $settings        array   Store settings (alias of $store_setting)
 *   $category_tags   array   Featured categories (tagged)
 *   $category        array   Root-level categories
 *   $new_products    array   Latest 8 products
 *   $best_sellers    array   Top 4 best-selling products
 *   $product_ratings array   Map of product_id → avg_star, cnt
 */
$currency = $store_setting['currency_sign'] ?? '$';
$slider_raw = !empty($store_setting['homepage_slider']) ? $store_setting['homepage_slider'] : '[]';
$slider = is_array($slider_raw) ? $slider_raw : json_decode($slider_raw, true);
if (!is_array($slider)) $slider = [];

$home_categories = !empty($category) ? $category : (!empty($category_tree) ? $category_tree : []);

$_products    = isset($new_products)  ? $new_products  : [];
$_best_sellers = isset($best_sellers) ? $best_sellers  : [];

$store_name = $store_setting['name'] ?? 'Store';
?>

<!-- ═══════════════════════════════════════════════════════════════
     SECTION 1 — HERO SLIDER (Custom Carousel — Admin → Theme Sections → Homepage Slider)
     ═══════════════════════════════════════════════════════════════ -->
<?php if (($store_setting['homepage_slider_enabled'] ?? '1') !== '0'): ?>
<section class="s26-hero s26-hero--home">
    <div class="s26-hero__canvas">
        <!-- Fixed background layers (not part of slides) -->
        <div class="s26-hero__gradient"></div>
        <div class="s26-hero__shape s26-hero__shape--1"></div>
        <div class="s26-hero__shape s26-hero__shape--2"></div>
        <div class="s26-hero__shape s26-hero__shape--3"></div>

        <div id="s26HeroSlider" class="s26-hero__slider">
            <div class="swiper s26-hero-swiper" data-swiper-autoplay="6000">
                <div class="swiper-wrapper">

            <?php if (empty($slider)): ?>
            <!-- ── Default hero: no slides configured in admin ── -->
            <div class="swiper-slide">
            <div class="s26-hero__slide">
                <div class="container">
                    <div class="row align-items-center s26-hero__row">
                        <div class="col-lg-6 col-xl-5">
                            <div class="s26-hero__content">
                                <div class="s26-hero__tag">
                                    <span class="s26-hero__tag-dot"></span>
                                    <?= __('store.new_collection') ?? 'New Collection' ?>
                                </div>
                                <h1 class="s26-hero__title"><?= __('store.welcome_to_store') ?? 'Discover What\'s Next' ?></h1>
                                <p class="s26-hero__subtitle"><?= __('store.curated_collection_desc') ?? 'Curated products, unbeatable prices, delivered with care.' ?></p>
                                <div class="s26-hero__actions">
                                    <a href="<?= base_url('store/category') ?>" class="s26-btn-hero-primary">
                                        <?= __('store.shop_now') ?? 'Shop Now' ?>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                    <a href="#s26-featured" class="s26-btn-hero-ghost">
                                        <?= __('store.explore') ?? 'Explore' ?>
                                        <i class="fas fa-chevron-down"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-7 d-none d-lg-block">
                            <div class="s26-hero__visual">
                                <div class="s26-hero__collage">
                                    <?php
                                    // Build up to 3 collage images — real products first, then demo placeholders
                                    $_demo_collage = [
                                        base_url('assets/store/starter2026/img/demo-slide-1.jpg'),
                                        base_url('assets/store/starter2026/img/demo-card-1.jpg'),
                                        base_url('assets/store/starter2026/img/demo-card-2.jpg'),
                                    ];
                                    $_collage_imgs = [];
                                    foreach (array_slice($_products, 0, 3) as $hp) {
                                        $_collage_imgs[] = !empty($hp['product_featured_image'])
                                            ? base_url('assets/images/product/upload/thumb/' . $hp['product_featured_image'])
                                            : base_url('assets/store/default/img/pr-img.png');
                                    }
                                    // Pad to exactly 3 slots with demo images
                                    for ($__i = count($_collage_imgs); $__i < 3; $__i++) {
                                        $_collage_imgs[] = $_demo_collage[$__i];
                                    }
                                    foreach ($_collage_imgs as $__ci => $__cimg):
                                    ?>
                                    <div class="s26-hero__collage-item s26-hero__collage-item--<?= $__ci + 1 ?>">
                                        <img src="<?= $__cimg ?>" alt="" loading="<?= $__ci === 0 ? 'eager' : 'lazy' ?>">
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="s26-hero__float-badge s26-hero__float-badge--top">
                                    <i class="fas fa-fire"></i>
                                    <span><?= count($_products) ?>+ <?= __('store.products') ?? 'Products' ?></span>
                                </div>
                                <div class="s26-hero__float-badge s26-hero__float-badge--bottom">
                                    <i class="fas fa-star"></i>
                                    <span><?= __('store.top_rated') ?? 'Top Rated' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <?php else: ?>
            <?php foreach ($slider as $si => $slide):
                $txt_color = !empty($slide['slider_text_color']) ? $slide['slider_text_color'] : '#ffffff';
                $btn_bg    = !empty($slide['button_bg_color'])   ? $slide['button_bg_color']   : '#ffffff';
                $btn_color = !empty($slide['button_text_color']) ? $slide['button_text_color'] : '';
                $btn_link  = !empty($slide['button_link'])       ? $slide['button_link']       : base_url('store/category');
            ?>
            <!-- ── Slide <?= $si + 1 ?> ── -->
            <div class="swiper-slide">
            <div class="s26-hero__slide">
                <div class="container">
                    <div class="row align-items-center s26-hero__row"
                         style="--s26-slide-text:<?= htmlspecialchars($txt_color) ?>;--s26-btn-bg:<?= htmlspecialchars($btn_bg) ?>;<?= $btn_color ? '--s26-btn-color:'.htmlspecialchars($btn_color).';' : '' ?>">

                        <!-- Left: text content -->
                        <div class="col-lg-6 col-xl-5">
                            <div class="s26-hero__content">
                                <div class="s26-hero__tag">
                                    <span class="s26-hero__tag-dot"></span>
                                    <?= __('store.new_collection') ?? 'New Collection' ?>
                                </div>
                                <h1 class="s26-hero__title"><?= htmlspecialchars($slide['title'] ?? '') ?></h1>
                                <?php if (!empty($slide['sub_title'])): ?>
                                <p class="s26-hero__subtitle"><?= htmlspecialchars($slide['sub_title']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($slide['content'])): ?>
                                <p class="s26-hero__desc"><?= htmlspecialchars($slide['content']) ?></p>
                                <?php endif; ?>
                                <div class="s26-hero__actions">
                                    <?php if (!empty($slide['button_text'])): ?>
                                    <a href="<?= htmlspecialchars($btn_link) ?>" class="s26-btn-hero-primary">
                                        <?= htmlspecialchars($slide['button_text']) ?>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                    <?php else: ?>
                                    <a href="<?= base_url('store/category') ?>" class="s26-btn-hero-primary">
                                        <?= __('store.shop_now') ?? 'Shop Now' ?>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="#s26-featured" class="s26-btn-hero-ghost">
                                        <?= __('store.explore') ?? 'Explore' ?>
                                        <i class="fas fa-chevron-down"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Right: image or product collage -->
                        <div class="col-lg-6 col-xl-7 d-none d-lg-block">
                            <div class="s26-hero__visual">
                                <?php if (!empty($slide['slider_background_image'])): ?>
                                <div class="s26-hero__image-frame">
                                    <img src="<?= base_url('assets/images/site/' . $slide['slider_background_image']) ?>"
                                         alt="<?= htmlspecialchars($slide['title'] ?? $store_name) ?>"
                                         class="s26-hero__image"
                                         loading="<?= $si === 0 ? 'eager' : 'lazy' ?>">
                                </div>
                                <?php else: ?>
                                <div class="s26-hero__collage">
                                    <?php foreach (array_slice($_products, 0, 3) as $hi => $hp):
                                        $himg = !empty($hp['product_featured_image'])
                                            ? base_url('assets/images/product/upload/thumb/' . $hp['product_featured_image'])
                                            : base_url('assets/store/default/img/pr-img.png');
                                    ?>
                                    <div class="s26-hero__collage-item s26-hero__collage-item--<?= $hi + 1 ?>">
                                        <img src="<?= $himg ?>" alt="" loading="lazy">
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <?php if ($si === 0): ?>
                                <div class="s26-hero__float-badge s26-hero__float-badge--top">
                                    <i class="fas fa-fire"></i>
                                    <span><?= count($_products) ?>+ <?= __('store.products') ?? 'Products' ?></span>
                                </div>
                                <div class="s26-hero__float-badge s26-hero__float-badge--bottom">
                                    <i class="fas fa-star"></i>
                                    <span><?= __('store.top_rated') ?? 'Top Rated' ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

                </div><!-- /.swiper-wrapper -->
                <button class="s26-hero__prev" type="button" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></button>
                <button class="s26-hero__next" type="button" aria-label="Next slide"><i class="fas fa-chevron-right"></i></button>
                <div class="s26-hero__dots s26-hero-pagination"></div>
            </div><!-- /.swiper -->
        </div><!-- /#s26HeroSlider -->
    </div><!-- /.s26-hero__canvas -->
</section>
<script>
/* ── Swiper Hero Carousel ── */
document.addEventListener('DOMContentLoaded', function() {
    var heroSwiper = new Swiper('.s26-hero-swiper', {
        loop: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 800,
        navigation: {
            nextEl: '.s26-hero__next',
            prevEl: '.s26-hero__prev',
        },
        pagination: {
            el: '.s26-hero-pagination',
            clickable: true,
            renderBullet: function(index, className) {
                return '<li class="' + className + '"><button type="button" aria-label="Slide ' + (index + 1) + '"></button></li>';
            }
        },
        on: {
            slideChangeTransitionStart: function() {
                var activeSlide = this.slides[this.activeIndex];
                if (activeSlide) {
                    var content = activeSlide.querySelector('.s26-hero__content');
                    if (content) {
                        gsap.fromTo(content.children,
                            { opacity: 0, y: 30 },
                            { opacity: 1, y: 0, stagger: 0.15, duration: 0.6, ease: 'power3.out' }
                        );
                    }
                }
            }
        }
    });
});
</script>
<?php endif; /* homepage_slider_enabled */ ?>


<!-- ═══════════════════════════════════════════════════════════════
     SECTION 2 — FEATURES BAR (Admin-managed via Store Settings → Theme Sections → Homepage Features)
     ═══════════════════════════════════════════════════════════════ -->
<?php
$homepage_features = (isset($store_setting['homepage_features']) && !empty($store_setting['homepage_features']))
    ? json_decode($store_setting['homepage_features']) : [];
$has_admin_features = !empty($homepage_features);
if (($store_setting['homepage_features_enabled'] ?? '1') !== '0'):
?>
<div class="container" style="margin-top:-48px;position:relative;z-index:10;">
    <div class="s26-features-bar s26-features-bar--home">
        <div class="row g-0">
        <?php if ($has_admin_features): ?>
            <?php foreach ($homepage_features as $hf): ?>
            <div class="col-6 col-md-3">
                <div class="s26-feature-item s26-feature-item--home">
                    <div class="s26-feature-icon-ring">
                        <?php if (!empty($hf->feature_image)): ?>
                            <img src="<?= base_url('assets/images/site/' . $hf->feature_image) ?>" alt="<?= htmlspecialchars($hf->title) ?>" style="width:32px;height:32px;object-fit:contain;">
                        <?php else: ?>
                            <i class="fas fa-star"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h6><?= htmlspecialchars($hf->title) ?></h6>
                        <p><?= htmlspecialchars($hf->sub_title) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-6 col-md-3">
                <div class="s26-feature-item s26-feature-item--home">
                    <div class="s26-feature-icon-ring"><i class="fas fa-truck"></i></div>
                    <div>
                        <h6><?= __('store.free_shipping') ?? 'Free Shipping' ?></h6>
                        <p><?= __('store.on_orders_over_50') ?? 'On orders over $50' ?></p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="s26-feature-item s26-feature-item--home">
                    <div class="s26-feature-icon-ring"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h6><?= __('store.secure_payment') ?? 'Secure Payment' ?></h6>
                        <p><?= __('store.100_protected') ?? '100% protected' ?></p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="s26-feature-item s26-feature-item--home">
                    <div class="s26-feature-icon-ring"><i class="fas fa-undo"></i></div>
                    <div>
                        <h6><?= __('store.easy_returns') ?? 'Easy Returns' ?></h6>
                        <p><?= __('store.30_day_policy') ?? '30-day return policy' ?></p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="s26-feature-item s26-feature-item--home">
                    <div class="s26-feature-icon-ring"><i class="fas fa-headset"></i></div>
                    <div>
                        <h6><?= __('store.247_support') ?? '24/7 Support' ?></h6>
                        <p><?= __('store.always_here') ?? 'Always here to help' ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; /* homepage_features_enabled */ ?>


<!-- ═══════════════════════════════════════════════════════════════
     SECTION 3 — CATEGORIES (Horizontal Scroll Pill Cards)
     ═══════════════════════════════════════════════════════════════ -->
<?php if (!empty($home_categories)): ?>
<section class="s26-section s26-categories-section">
    <div class="container">
        <div class="s26-section-header">
            <div>
                <p class="s26-section-eyebrow"><?= __('store.browse_by') ?? 'Browse By' ?></p>
                <h2 class="s26-section-title"><?= __('store.categories') ?? 'Categories' ?></h2>
            </div>
            <a href="<?= base_url('store/category') ?>" class="s26-link-arrow">
                <?= __('store.all_categories') ?? 'All Categories' ?>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="s26-category-scroll">
            <?php
            $cat_icons = ['fas fa-tshirt','fas fa-laptop','fas fa-couch','fas fa-football-ball','fas fa-gem','fas fa-book','fas fa-gamepad','fas fa-utensils','fas fa-camera','fas fa-paint-brush'];
            $cat_gradients = [
                'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
                'linear-gradient(135deg, #fccb90 0%, #d57eeb 100%)',
                'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)',
                'linear-gradient(135deg, #f5576c 0%, #ff6a88 100%)',
                'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            ];
            $ci = 0;
            foreach ($home_categories as $cat):
            ?>
            <a href="<?= base_url('store/category/' . ($cat['slug'] ?? $cat['id'] ?? '')) ?>" class="s26-category-card s26-category-card--pill s26-reveal">
                <div class="s26-category-card__icon" style="background:<?= $cat_gradients[$ci % count($cat_gradients)] ?>">
                    <?php if (!empty($cat['image'])): ?>
                        <img src="<?= base_url('assets/images/product/upload/thumb/' . $cat['image']) ?>" alt="">
                    <?php else: ?>
                        <i class="<?= $cat_icons[$ci % count($cat_icons)] ?>"></i>
                    <?php endif; ?>
                </div>
                <span class="s26-category-card__name"><?= htmlspecialchars($cat['name']) ?></span>
                <span class="s26-category-card__arrow"><i class="fas fa-chevron-right"></i></span>
            </a>
            <?php $ci++; endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- ═══════════════════════════════════════════════════════════════
     SECTION 4 — FEATURED PRODUCTS (Staggered Grid)
     ═══════════════════════════════════════════════════════════════ -->
<?php if (!empty($_products)): ?>
<section class="s26-section" id="s26-featured">
    <div class="container">
        <div class="s26-section-header">
            <div>
                <p class="s26-section-eyebrow"><?= __('store.hand_picked_for_you') ?? 'Hand-Picked For You' ?></p>
                <h2 class="s26-section-title"><?= __('store.featured_products') ?? 'Featured Products' ?></h2>
            </div>
            <a href="<?= base_url('store/category') ?>" class="s26-btn-outline s26-btn--sm">
                <?= __('admin.view_all') ?? 'View All' ?>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3 g-lg-4 s26-products-row">
            <?php foreach ($_products as $pi => $p):
                $img = !empty($p['product_featured_image'])
                    ? base_url('assets/images/product/upload/thumb/' . $p['product_featured_image'])
                    : base_url('assets/store/default/img/pr-img.png');
                $qty = (int)($p['product_quantity'] ?? 0);
                $url = base_url('store/product/' . $p['product_slug']);
                $is_oos = ($qty == 0 && isset($p['product_quantity']) && $p['product_quantity'] !== '');

                // Rating from batch-fetched controller data
                $avg = 0; $rev_cnt = 0;
                $_r = isset($product_ratings[$p['product_id']]) ? $product_ratings[$p['product_id']] : null;
                if ($_r && $_r['cnt'] > 0) { $avg = round($_r['avg_star'], 1); $rev_cnt = (int)$_r['cnt']; }
            ?>
            <div class="col-6 col-md-4 col-lg-3 s26-reveal">
                <div class="s26-product-card s26-product-card--home">
                    <!-- Image -->
                    <div class="card-img-wrapper">
                        <a href="<?= $url ?>">
                            <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" loading="lazy">
                        </a>

                        <?php if ($is_oos): ?>
                        <span class="s26-badge s26-badge-oos"><?= __('store.out_of_stock') ?? 'Out of Stock' ?></span>
                        <?php endif; ?>

                        <!-- Quick actions overlay -->
                        <div class="quick-actions">
                            <button class="quick-view-btn" data-product-id="<?= $p['product_id'] ?>">
                                <i class="fas fa-eye"></i>
                                <span class="d-none d-md-inline"><?= __('store.quick_view') ?? 'Quick View' ?></span>
                            </button>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="card-body">
                        <div class="s26-stars">
                            <?php if ($avg > 0): for ($s = 1; $s <= 5; $s++): ?>
                                <i class="<?= $s <= round($avg) ? 'fas' : 'far' ?> fa-star"></i>
                            <?php endfor; ?>
                            <small class="text-muted ms-1">(<?= $rev_cnt ?>)</small>
                            <?php endif; ?>
                        </div>
                        <a href="<?= $url ?>" class="product-title"><?= htmlspecialchars($p['product_name']) ?></a>
                        <div class="product-price"><?= c_format($p['product_price']) ?></div>
                    </div>
                    <div class="s26-card-footer">
                        <a href="<?= $url ?>" class="s26-card-details-btn">
                            <i class="fas fa-arrow-right"></i>
                            <?= __('store.details') ?? 'Details' ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- ═══════════════════════════════════════════════════════════════
     SECTION 4.5 — BEST SELLERS MARQUEE (if available)
     ═══════════════════════════════════════════════════════════════ -->
<?php if (!empty($_best_sellers)): ?>
<section class="s26-section s26-bestsellers-section">
    <div class="container">
        <div class="s26-section-header">
            <div>
                <p class="s26-section-eyebrow"><i class="fas fa-fire" style="color:var(--s26-danger);"></i> <?= __('store.trending_now') ?? 'Trending Now' ?></p>
                <h2 class="s26-section-title"><?= __('store.best_sellers') ?? 'Best Sellers' ?></h2>
            </div>
        </div>

        <div class="s26-bestseller-strip">
            <?php foreach ($_best_sellers as $bp):
                $bimg = !empty($bp['product_featured_image'])
                    ? base_url('assets/images/product/upload/thumb/' . $bp['product_featured_image'])
                    : base_url('assets/store/default/img/pr-img.png');
                $burl = base_url('store/product/' . $bp['product_slug']);

                $bavg = 0;
                $_br = isset($product_ratings[$bp['product_id']]) ? $product_ratings[$bp['product_id']] : null;
                if ($_br && $_br['cnt'] > 0) { $bavg = round($_br['avg_star'], 1); }
            ?>
            <a href="<?= $burl ?>" class="s26-bestseller-card s26-reveal">
                <div class="s26-bestseller-card__img">
                    <img src="<?= $bimg ?>" alt="<?= htmlspecialchars($bp['product_name']) ?>" loading="lazy">
                </div>
                <div class="s26-bestseller-card__info">
                    <h4><?= htmlspecialchars($bp['product_name']) ?></h4>
                    <?php if ($bavg > 0): ?>
                    <div class="s26-stars" style="font-size:11px;">
                        <?php for ($s = 1; $s <= 5; $s++): ?>
                            <i class="<?= $s <= round($bavg) ? 'fas' : 'far' ?> fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                    <span class="s26-bestseller-card__price"><?= c_format($bp['product_price']) ?></span>
                </div>
                <span class="s26-bestseller-card__badge"><?= (int)$bp['product_sales_count'] ?> <?= __('store.sold') ?? 'sold' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- ═══════════════════════════════════════════════════════════════
     SECTION 5 — HOMEPAGE BOTTOM BANNER (Admin → Theme Sections → Homepage Bottom Banner)
     ═══════════════════════════════════════════════════════════════ -->
<?php
$banner     = null;
$hbanimage  = !empty($store_setting['hbanimage']) ? $store_setting['hbanimage'] : '';
$banner_raw = !empty($store_setting['homepage_banner']) ? $store_setting['homepage_banner'] : '';
if ($banner_raw) {
    $banner_dec = is_array($banner_raw) ? (object)$banner_raw : json_decode($banner_raw);
    if ($banner_dec && !empty($banner_dec->title)) $banner = $banner_dec;
}
/* Fallback demo banner when no admin data is saved */
if (!$banner) {
    $banner = (object)[
        'title'       => __('store.demo_banner_title'),
        'content'     => __('store.demo_banner_content'),
        'button_text' => __('store.demo_banner_btn'),
        'button_link' => base_url('store/category'),
    ];
    if (empty($hbanimage)) $hbanimage = '__demo__'; // sentinel — use local demo image
}
if (($store_setting['homepage_banner_enabled'] ?? '1') !== '0'):
?>
<section class="s26-bottom-banner s26-reveal">
    <?php
    $bannerImgUrl = '';
    if ($hbanimage === '__demo__') {
        $bannerImgUrl = base_url('assets/store/starter2026/img/demo-banner-ad.jpg');
    } elseif (!empty($hbanimage)) {
        $bannerImgUrl = base_url('assets/images/site/' . htmlspecialchars($hbanimage));
    }
    ?>
    <?php if ($bannerImgUrl): ?>
    <div class="s26-bottom-banner__bg"
         style="background-image:url('<?= $bannerImgUrl ?>')">
    </div>
    <?php endif; ?>
    <div class="container s26-bottom-banner__body">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h2 class="s26-bottom-banner__title"><?= htmlspecialchars($banner->title) ?></h2>
                <?php if (!empty($banner->content)): ?>
                <p class="s26-bottom-banner__text"><?= nl2br(htmlspecialchars($banner->content)) ?></p>
                <?php endif; ?>
                <?php if (!empty($banner->button_text)): ?>
                <a href="<?= htmlspecialchars($banner->button_link ?? '#') ?>" class="s26-bottom-banner__btn">
                    <?= htmlspecialchars($banner->button_text) ?>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php if ($bannerImgUrl): ?>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="s26-bottom-banner__img">
                    <img src="<?= $bannerImgUrl ?>"
                         alt="<?= htmlspecialchars($banner->title) ?>" loading="lazy">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- ═══════════════════════════════════════════════════════════════
     SECTION 6 — HOMEPAGE BOTTOM SECTION (Admin → Theme Sections → Homepage Bottom Section)
     ═══════════════════════════════════════════════════════════════ -->
<?php
$bs_cards_raw = !empty($store_setting['bs_cards']) ? $store_setting['bs_cards'] : '[]';
$bs_cards     = is_array($bs_cards_raw) ? $bs_cards_raw : json_decode($bs_cards_raw);
if (!$bs_cards) $bs_cards = [];

$bs_sec_raw  = !empty($store_setting['homepage_bottom_section']) ? $store_setting['homepage_bottom_section'] : '';
$bs_content  = '';
if ($bs_sec_raw) {
    $bs_sec_dec = is_array($bs_sec_raw) ? (object)$bs_sec_raw : json_decode($bs_sec_raw);
    if ($bs_sec_dec && !empty($bs_sec_dec->content)) $bs_content = $bs_sec_dec->content;
}

/* Fallback demo cards and content when no admin data is saved */
if (empty($bs_cards)) {
    $bs_cards = [
        (object)['title'=>__('store.quality_guaranteed'), 'sub_title'=>__('store.quality_guaranteed_desc'), 'feature_image'=>'__demo_card_1__', 'bg_color'=>'', 'button_link'=>base_url('store/category'), 'link_target'=>'_self'],
        (object)['title'=>__('store.free_shipping'),      'sub_title'=>__('store.fast_delivery_desc'),      'feature_image'=>'__demo_card_2__', 'bg_color'=>'', 'button_link'=>base_url('store/category'), 'link_target'=>'_self'],
        (object)['title'=>__('store.best_prices'),        'sub_title'=>__('store.best_prices_desc'),        'feature_image'=>'__demo_card_3__', 'bg_color'=>'', 'button_link'=>base_url('store/category'), 'link_target'=>'_self'],
    ];
}
if (empty($bs_content)) {
    $bs_content = '<p>' . __('store.homepage_bottom_default_text') . '</p>';
}

if (($store_setting['homepage_bottom_section_enabled'] ?? '1') !== '0'):
?>
<section class="s26-section s26-bottom-section s26-reveal">
    <div class="container">

        <?php if (!empty($bs_cards)): ?>
        <div class="row g-3 g-md-4 mb-5">
            <?php foreach ($bs_cards as $bsc):
                if (is_array($bsc)) $bsc = (object)$bsc;
                $bsc_bg  = !empty($bsc->bg_color)      ? $bsc->bg_color      : '';
                $bsc_img = !empty($bsc->feature_image)  ? $bsc->feature_image  : '';
                $bsc_url = !empty($bsc->button_link)    ? $bsc->button_link    : '#';
                $bsc_tgt = !empty($bsc->link_target) && $bsc->link_target === 'true' ? '_blank' : '_self';
                /* Resolve image URL — support demo sentinel values */
                if ($bsc_img === '__demo_card_1__') {
                    $bsc_img_url = base_url('assets/store/starter2026/img/demo-card-1.jpg');
                } elseif ($bsc_img === '__demo_card_2__') {
                    $bsc_img_url = base_url('assets/store/starter2026/img/demo-card-2.jpg');
                } elseif ($bsc_img === '__demo_card_3__') {
                    $bsc_img_url = base_url('assets/store/starter2026/img/demo-card-3.jpg');
                } elseif (!empty($bsc_img)) {
                    $bsc_img_url = base_url('assets/images/site/' . htmlspecialchars($bsc_img));
                } else {
                    $bsc_img_url = '';
                }
            ?>
            <div class="col-12 col-sm-6 col-lg-3 s26-reveal">
                <a href="<?= htmlspecialchars($bsc_url) ?>" target="<?= $bsc_tgt ?>" class="text-decoration-none">
                <div class="s26-bottom-section__card"<?= $bsc_bg ? ' style="--s26-card-accent:'.htmlspecialchars($bsc_bg).';"' : '' ?>>
                    <?php if ($bsc_img_url): ?>
                    <img src="<?= $bsc_img_url ?>"
                         alt="<?= htmlspecialchars($bsc->title ?? '') ?>"
                         class="s26-bottom-section__card-img" loading="lazy">
                    <?php endif; ?>
                    <div class="s26-bottom-section__card-body">
                        <?php if (!empty($bsc->title)): ?>
                        <p class="s26-bottom-section__card-title"><?= htmlspecialchars($bsc->title) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($bsc->sub_title)): ?>
                        <p class="s26-bottom-section__card-sub"><?= htmlspecialchars($bsc->sub_title) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($bs_content)): ?>
        <div class="s26-bottom-section__content">
            <?= $bs_content ?>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>


<!-- ═══════════════════════════════════════════════════════════════
     HOME PAGE ANIMATIONS (scroll reveal re-init)
     ═══════════════════════════════════════════════════════════════ -->
<script>
(function() {
    // Re-initialize intersection observer for home page reveal elements
    if ('IntersectionObserver' in window) {
        var homeObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry, idx) {
                if (entry.isIntersecting) {
                    // Stagger the animation
                    var delay = Math.min(idx * 80, 400);
                    setTimeout(function() {
                        entry.target.classList.add('visible');
                    }, delay);
                    homeObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

        document.querySelectorAll('.s26-reveal').forEach(function(el) {
            homeObserver.observe(el);
        });
    } else {
        // Fallback: show all immediately
        document.querySelectorAll('.s26-reveal').forEach(function(el) {
            el.classList.add('visible');
        });
    }

    // Smooth scroll for "Explore" button
    document.querySelectorAll('a[href="#s26-featured"]').forEach(function(a) {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.getElementById('s26-featured');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
})();
</script>

<!-- ═══════════ GSAP ANIMATIONS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    gsap.registerPlugin(ScrollTrigger);
    
    // ═══ HERO SECTION — Parallax + Text Animation ═══
    const heroSection = document.querySelector('.s26-hero');
    if (heroSection) {
        // Hero background shapes parallax
        gsap.to('.s26-hero__shape--1', {
            y: -80,
            rotation: 10,
            ease: 'none',
            scrollTrigger: {
                trigger: '.s26-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });
        
        gsap.to('.s26-hero__shape--2', {
            y: -60,
            rotation: -8,
            ease: 'none',
            scrollTrigger: {
                trigger: '.s26-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });
        
        gsap.to('.s26-hero__shape--3', {
            y: -40,
            scale: 1.1,
            ease: 'none',
            scrollTrigger: {
                trigger: '.s26-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });
        
        // Hero collage images
        gsap.utils.toArray('.s26-hero__collage-item').forEach((img, i) => {
            gsap.from(img, {
                opacity: 0,
                scale: 0.8,
                rotation: -5 + (i * 5),
                duration: 0.8,
                delay: 0.5 + (i * 0.15),
                ease: 'back.out(1.4)'
            });
        });
    }
    
    // ═══ PRODUCT CARDS — Stagger Reveal ═══
    gsap.utils.toArray('.s26-product-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 60,
            duration: 0.7,
            delay: (i % 4) * 0.1,
            ease: 'power3.out'
        });
    });
    
    // ═══ CATEGORY CARDS — Scale + Fade ═══
    gsap.utils.toArray('.s26-category-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 90%'
            },
            opacity: 0,
            scale: 0.85,
            y: 30,
            duration: 0.6,
            delay: i * 0.1,
            ease: 'back.out(1.2)'
        });
    });
    
    // ═══ FEATURE CARDS — Slide In ═══
    gsap.utils.toArray('.s26-feature-item').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%'
            },
            opacity: 0,
            x: i % 2 === 0 ? -50 : 50,
            duration: 0.6,
            delay: i * 0.1,
            ease: 'power2.out'
        });
    });
    
    // ═══ SECTION HEADERS — Fade In ═══
    gsap.utils.toArray('.s26-section-header').forEach(header => {
        gsap.from(header.children, {
            scrollTrigger: {
                trigger: header,
                start: 'top 85%'
            },
            opacity: 0,
            y: 40,
            stagger: 0.15,
            duration: 0.8,
            ease: 'power3.out'
        });
    });
    
    // ═══ FOOTER — Slide Up ═══
    gsap.from('footer', {
        scrollTrigger: {
            trigger: 'footer',
            start: 'top 95%'
        },
        opacity: 0,
        y: 40,
        duration: 0.7,
        ease: 'power2.out'
    });
});
</script>
