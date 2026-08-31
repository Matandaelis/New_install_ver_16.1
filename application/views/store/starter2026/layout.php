<?php
/**
 * Starter 2026 — Main Theme Layout
 *
 * @contract  Store API v1 — global layout wrapper (wraps ALL store pages)
 *
 * ALL GLOBALS are available (injected by Storeapp::view()):
 *   $store_setting   array   All store settings (name, logo, theme, colors, fonts, etc.)
 *   $SiteSetting     array   Global site settings (cookie consent, custom fonts, etc.)
 *   $client          array   Logged-in customer session (empty if guest)
 *   $home_link       string  URL of the store homepage
 *   $base_url        string  Full site base URL
 *   $store_currency  string  Active currency code
 *   $LanguageHtml    string  Rendered language switcher HTML
 *   $CurrencyHtml    string  Rendered currency switcher HTML
 *   $category_tree   array   Full nested category tree for navigation
 *   $googlerecaptcha array   reCAPTCHA settings
 *   $add_tocart_url  string  POST URL for add-to-cart action
 *
 * LAYOUT-SPECIFIC VARIABLES
 *   $page   string  Current page identifier (home|category|product|cart|checkout|profile|...)
 *   $title  string  <title> tag value (if set by the controller)
 *
 * LAYOUT-SPECIFIC VARIABLES (injected alongside globals by Storeapp::view())
 *   $content                string  The rendered inner page HTML (set by Storeapp). Use: <?php $this->load->view($content, $data); ?> or echo $content_view.
 *   $page                   string  Current page identifier (home|category|product|cart|checkout|profile|...)
 *   $meta_title             string  Value for the <title> tag (falls back to $store_setting['name'])
 *   $meta_description       string  Meta description for SEO
 *   $meta_image             string  OG image URL for social sharing
 *   $canonical_url          string  Canonical URL for the current page
 *   $settings               array   Store settings alias (same as $store_setting, used in footer)
 *   $user_id                int     Affiliate user ID (used in canonical URL / tracking JS)
 *   $affiliate_localstorage_init  string  JS snippet to init affiliate tracking in localStorage
 *   $page_custom_script     string  Optional custom JS snippet injected at end of <body>
 *   $is_logged              array|false  Alias of $client — logged-in user (set from session check in layout)
 *
 * NOTE  The layout echoes page content via the variable $content (not $content_view).
 *       $title is never used — page title comes from $meta_title + $store_setting['name'].
 */
// Settings come from Storeapp::view() — SiteSetting[] has all 'site' type rows
$cart_store_side_font  = $SiteSetting['cart_store_side_font']  ?? '';
$cookies_consent       = $SiteSetting['cookies_consent']       ?? '0';
$cookies_consent_mesag = $SiteSetting['cookies_consent_mesag'] ?? '';

// Session & login (use Storeapp-injected variables)
$client    = $client ?? [];
$user      = $user   ?? [];
$is_logged = (isset($client['id']) && $client['id'] > 0) || isset($is_logged) && $is_logged;

// RTL support
$is_rtl = (isset($store_setting['store_direction']) && $store_setting['store_direction'] == 'rtl');

// $category_tree is always set by Storeapp::view() fallback — no DB call needed here

// Logo helpers
$logo = (!empty($store_setting['logo']))
    ? base_url('assets/images/site/' . $store_setting['logo'])
    : base_url('assets/store/default/img/logo.png');

$storelogoheight    = 36;
$storelogowidthstr  = '';
if (!empty($store_setting['store_custom_logo_size']) && $store_setting['store_custom_logo_size'] != 0) {
    $storelogoheight   = $store_setting['store_logo_custom_height'];
    $storelogowidthstr = 'width="' . $store_setting['store_logo_custom_width'] . '"';
}

// Store font
$font_family = !empty($cart_store_side_font)
    ? $cart_store_side_font
    : "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";

// Notification bar items — mirrors the default theme: the bar is ALWAYS visible.
// Custom messages are shown when the admin has configured them (notification_enabled = 1);
// otherwise a built-in welcome fallback is used so the store never looks empty.
$notification_items = [];
if (!empty($store_setting['notification'])) {
    $decoded = json_decode($store_setting['notification'], true);
    if (is_array($decoded)) {
        $notification_items = array_filter($decoded, function($v) {
            return !empty(trim($v));
        });
        $notification_items = array_values($notification_items);
    }
}
// Fallback — always show a welcome message when no custom messages are configured
if (empty($notification_items)) {
    $store_name = !empty($store_setting['name']) ? htmlspecialchars($store_setting['name']) : 'our store';
    $fallback   = __('store.demo_notif_welcome');
    $notification_items = [
        !empty($fallback) ? $fallback : ('🎉 Welcome to ' . $store_name . '! Free shipping on orders over $50.'),
    ];
}

// Canonical URL
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="<?= $store_setting['store_language'] ?? 'en' ?>" <?= $is_rtl ? 'dir="rtl"' : '' ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="">

    <!-- Open Graph / SEO -->
    <meta property="og:url" content="<?= htmlspecialchars($actual_link) ?>">
    <?php if (isset($meta_title)): ?>
        <meta property="og:title" content="<?= htmlspecialchars($meta_title) ?>">
    <?php endif; ?>
    <?php if (isset($meta_description)): ?>
        <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
        <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>">
    <?php endif; ?>
    <?php if (isset($meta_image)): ?>
        <meta property="og:image" content="<?= $meta_image ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">

    <!-- Favicon -->
    <?php if (!empty($store_setting['favicon'])): ?>
        <link rel="icon" href="<?= base_url('assets/images/site/' . $store_setting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php endif; ?>

    <!-- Canonical -->
    <?php
    if (isset($product) && isset($product['product_slug']) && isset($user_id)) {
        $canonical_url = base_url('store/' . base64_encode((int)$user_id) . '/product/' . $product['product_slug']);
        echo '<link rel="canonical" href="' . $canonical_url . '">';
    } elseif (isset($canonical_url) && !empty($canonical_url)) {
        echo '<link rel="canonical" href="' . $canonical_url . '">';
    } else {
        echo '<link rel="canonical" href="' . $actual_link . '">';
    }
    ?>

    <title><?= htmlspecialchars($store_setting['name'] ?? '') ?><?= isset($meta_title) ? ' - ' . htmlspecialchars($meta_title) : '' ?></title>

    <!-- ═══════════ CSS ═══════════ -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/fonts/fonts.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/css/placeholder-loading.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/sweetalert2.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/css/nouislider.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/default/slick/slick.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/default/slick/slick-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/starter2026/css/amazon-tokens.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/starter2026/css/theme.css') ?>?v=<?= av() ?>">

    <!-- RTL -->
    <?php if ($is_rtl): ?>
        <link rel="stylesheet" href="<?= base_url('assets/store/default/css/rtl.css') ?>?v=<?= av() ?>">
    <?php endif; ?>

    <!-- ═══════════ JS (Head) ═══════════ -->
    <script src="<?= base_url('assets/store/shared/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/plugins/store/jquery.star-rating-svg.js') ?>"></script>
    <script src="<?= base_url('assets/store/shared/js/nouislider.min.js') ?>"></script>
    <script src="<?= base_url('assets/template/js/sweetalert2.all.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/plugins/mustache.js') ?>"></script>
    <script src="<?= base_url('assets/store/default/slick/slick.min.js') ?>"></script>

    <!-- Google Analytics (external allowed) -->
    <?php if (!empty($store_setting['google_analytics'])): ?>
    <script type="text/javascript">
    <?= $store_setting['google_analytics']; ?>
    </script>
    <?php endif; ?>

    <!-- Custom per_task scripts (CSS/HTML/JS) in head -->
    <?php
    if (isset($store_setting['per_task']) && !empty($store_setting['per_task'])) {
        $per_tasks = json_decode($store_setting['per_task'], true);
        if (!empty($per_tasks)) {
            foreach ($per_tasks as $per_task) {
                $per_task_clean = trim($per_task);
                if (strpos($per_task_clean, '<style') !== false || strpos($per_task_clean, '</style>') !== false) {
                    echo $per_task_clean;
                } elseif (strpos($per_task_clean, '<') !== false && strpos($per_task_clean, '<script') === false) {
                    echo $per_task_clean;
                } elseif (strpos($per_task_clean, '<script') !== false) {
                    echo $per_task_clean;
                } else {
                    $per_task_js = preg_replace('/<script[^>]*>/', '', $per_task_clean);
                    $per_task_js = preg_replace('/<\/script>/', '', $per_task_js);
                    ?>
                    <script type="text/javascript">
                    try { <?= $per_task_js; ?> } catch(e) { console.log('Script error:', e); }
                    </script>
                    <?php
                }
            }
        }
    }
    ?>

    <!-- Global script -->
    <?php
    if (isset($SiteSetting['global_script_status'])) {
        $global_script_status = (array)json_decode($SiteSetting['global_script_status'], 1);
        if (in_array('store', $global_script_status)) {
            echo $SiteSetting['global_script'];
        }
    }
    ?>

    <!-- Restore $ after per_task / global_script may have called jQuery.noConflict() -->
    <script>if(typeof jQuery!=='undefined'&&typeof $==='undefined'){window.$=jQuery;}</script>

    <!-- jQuery btn plugin & formDataFilter -->
    <script type="text/javascript">
        (function($) {
            $.fn.btn = function(action) {
                var self = $(this);
                if (action == 'loading') {
                    if ($(self).attr("disabled") == "disabled") {}
                    $(self).attr("disabled", "disabled");
                    $(self).attr('data-btn-text', $(self).html());
                    $(self).html('<div class="spinner-border spinner-border-sm"></div>&nbsp;' + $(self).text());
                }
                if (action == 'reset') {
                    $(self).html($(self).attr('data-btn-text'));
                    $(self).removeAttr("disabled");
                }
            }
        })(jQuery);

        var formDataFilter = function(formData) {
            if (!(window.FormData && formData instanceof window.FormData)) return formData;
            if (!formData.keys) return formData;
            var newFormData = new window.FormData();
            Array.from(formData.entries()).forEach(function(entry) {
                var value = entry[1];
                if (value instanceof window.File && value.name === '' && value.size === 0) {
                    newFormData.append(entry[0], new window.Blob([]), '');
                } else {
                    newFormData.append(entry[0], value);
                }
            });
            return newFormData;
        }
    </script>

    <style>:root{--s26-font:<?= $font_family ?>}</style>
<?= render_js_error_reporter() ?>
</head>

<body style="font-family: <?= $font_family ?> !important;">

    <!-- FB Messenger -->
    <?php
    if (isset($SiteSetting['fbmessager_status'])) {
        $fbmessager_status = (array)json_decode($SiteSetting['fbmessager_status'], 1);
        if (in_array('store', $fbmessager_status)) {
            echo $SiteSetting['fbmessager_script'];
        }
    }
    if (function_exists('show_messenger_button') && isset($SiteSetting)) {
        show_messenger_button($SiteSetting, 'store');
    }
    ?>

    <!-- ═══════════════════════════════════════════════════════════
         ANNOUNCEMENT BAR
         ═══════════════════════════════════════════════════════════ -->
    <?php if (!empty($notification_items)): ?>
    <div class="s26-announcement-bar" id="s26-announcement">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-bullhorn" style="font-size:12px;opacity:0.8"></i>
                <span class="s26-announcement-text"><?= $notification_items[0]; ?></span>
            </div>
        </div>
        <button type="button" class="s26-announcement-close" onclick="document.getElementById('s26-announcement').style.display='none'" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════════════
         HEADER — Amazon-Style
         ═══════════════════════════════════════════════════════════ -->

    <!-- Primary Nav -->
    <div class="amz-header-primary">
        <div class="container">
            <div class="amz-header-inner">
                <!-- Logo -->
                <a class="amz-logo" href="<?= $home_link ?>">
                    <img alt="<?= htmlspecialchars($store_setting['name'] ?? 'Store') ?>"
                         src="<?= $logo ?>"
                         onerror="this.src='<?= base_url('assets/store/default/img/logo.png') ?>';"
                         height="<?= $storelogoheight ?>"
                         <?= $storelogowidthstr ?>>
                    <span class="amz-logo-suffix">.com</span>
                </a>

                <!-- Deliver To -->
                <div class="amz-deliver-to d-none d-md-flex">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <small><?= __('store.deliver_to') ?? 'Deliver to' ?></small>
                        <strong><?= htmlspecialchars($store_setting['store_country'] ?? 'United States') ?></strong>
                    </div>
                </div>

                <!-- Search -->
                <div class="amz-search">
                    <select class="amz-search-select d-none d-lg-block" id="amzSearchCat">
                        <option value=""><?= __('store.all') ?? 'All' ?></option>
                        <?php if (!empty($category_tree)): ?>
                            <?php foreach ($category_tree as $cat): ?>
                                <option value="<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="instant-search-wrapper amz-search-input-wrap">
                        <input type="text"
                               class="amz-search-input store-search-input"
                               placeholder="<?= __('store.search_products') ?? 'Search products' ?>"
                               id="s26-search-input"
                               autocomplete="off">
                        <button class="amz-search-btn" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                        <div id="s26-search-results" class="s26-search-dropdown" style="display:none"></div>
                    </div>
                </div>

                <!-- Language -->
                <?php if (isset($LanguageHtml) && !empty($LanguageHtml)): ?>
                <div class="amz-nav-item d-none d-lg-block" id="store_lang_menu"><?= $LanguageHtml ?></div>
                <?php endif; ?>

                <!-- Account -->
                <?php if ($is_logged): ?>
                <div class="amz-nav-item amz-dropdown d-none d-lg-block" id="amzAccountDropdown">
                    <a href="javascript:void(0)">
                        <small><?= __('store.hello') ?? 'Hello' ?>, <?= htmlspecialchars($client['firstname'] ?? __('store.sign_in') ?? 'Sign in') ?></small>
                        <strong><?= __('store.account') ?? 'Account & Lists' ?> <i class="fas fa-caret-down"></i></strong>
                    </a>
                    <div class="amz-dropdown-menu">
                        <?php if ($is_logged): ?>
                            <a href="<?= $base_url ?>profile"><i class="fas fa-user me-2"></i><?= __('store.profile') ?></a>
                            <a href="<?= $base_url ?>order"><i class="fas fa-gift me-2"></i><?= __('store.order') ?></a>
                            <a href="<?= $base_url ?>my_courses"><i class="fas fa-graduation-cap me-2"></i><?= __('store.my_courses') ?></a>
                            <a href="<?= $base_url ?>shipping"><i class="fas fa-truck me-2"></i><?= __('store.shipping') ?></a>
                            <a href="<?= $base_url ?>wishlist"><i class="fas fa-heart me-2"></i><?= __('store.wishlist') ?></a>
                            <hr>
                            <a href="<?= $base_url ?>logout" class="text-danger"><i class="fas fa-power-off me-2"></i><?= __('store.logout') ?></a>
                        <?php else: ?>
                            <a href="<?= $base_url ?>login" class="amz-btn amz-btn-cart" style="width:100%;text-align:center"><?= __('store.sign_in') ?? 'Sign in' ?></a>
                            <small class="d-block text-center mt-2"><?= __('store.new_customer') ?? 'New customer?' ?> <a href="<?= $base_url ?>register"><?= __('store.start_here') ?? 'Start here' ?></a></small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?= $base_url ?>login" class="amz-nav-item d-none d-lg-block">
                    <small><?= __('store.hello') ?? 'Hello' ?>, <?= __('store.sign_in') ?? 'Sign in' ?></small>
                    <strong><?= __('store.account') ?? 'Account & Lists' ?> <i class="fas fa-caret-down"></i></strong>
                </a>
                <?php endif; ?>

                <!-- Orders -->
                <?php if ($is_logged): ?>
                <a href="<?= $base_url ?>order" class="amz-nav-item d-none d-lg-block">
                    <small><?= __('store.returns') ?? 'Returns' ?></small>
                    <strong><?= __('store.orders') ?? '& Orders' ?></strong>
                </a>
                <?php endif; ?>

                <!-- Cart -->
                <div class="amz-cart position-relative cart-top" style="cursor:pointer">
                    <div class="amz-cart-count">
                        <span class="cart-count" style="display:none">0</span>
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <strong><?= __('store.cart') ?? 'Cart' ?></strong>
                    <small id="cart-sub-total" class="d-none"></small>
                    <!-- Cart Dropdown -->
                    <div class="cart-dropdown amz-dropdown-menu" style="display:none;right:0;left:auto;min-width:320px">
                        <div class="cart-empty text-center py-4">
                            <i class="fas fa-shopping-cart" style="font-size:36px;color:var(--amz-border-input);margin-bottom:12px;display:block"></i>
                            <p class="text-muted mb-0"><?= __('store.cart_is_blank') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu toggle -->
                <button class="amz-mobile-toggle d-lg-none" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#s26MobileMenu"
                        aria-label="<?= __('store.menu') ?>">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Secondary Nav -->
    <div class="amz-header-secondary">
        <div class="container">
            <div class="amz-nav-inner">
                <a href="javascript:void(0);" class="amz-nav-item amz-all-btn d-none d-lg-block"
                   data-bs-toggle="offcanvas" data-bs-target="#s26MobileMenu">
                    <i class="fas fa-bars"></i> <?= __('store.all') ?? 'All' ?>
                </a>
                <?php if (!empty($category_tree)): ?>
                    <?php foreach (array_slice($category_tree, 0, 7) as $cat): ?>
                        <a href="<?= base_url('store/category/' . $cat['slug']) ?>" class="amz-nav-item">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                <a href="<?= $base_url ?>about" class="amz-nav-item"><?= __('store.about') ?></a>
                <a href="<?= $base_url ?>contact" class="amz-nav-item"><?= __('store.contact') ?></a>
                <a href="<?= $base_url ?>category" class="amz-nav-item amz-promo"><?= __('store.deals') ?? 'Today\'s Deals' ?></a>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         MOBILE OFFCANVAS MENU
         ═══════════════════════════════════════════════════════════ -->
    <div class="offcanvas offcanvas-<?= $is_rtl ? 'end' : 'start' ?>" tabindex="-1" id="s26MobileMenu">
        <div class="offcanvas-header border-bottom">
            <a href="<?= $home_link ?>" class="s26-logo">
                <img alt="<?= htmlspecialchars($store_setting['name'] ?? 'Store') ?>"
                     src="<?= $logo ?>"
                     onerror="this.src='<?= base_url('assets/store/default/img/logo.png') ?>';"
                     height="32">
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Mobile Search -->
            <div class="mb-3">
                <div class="instant-search-wrapper position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control rounded-pill store-search-input"
                           placeholder="<?= __('store.search_products') ?? 'Search...' ?>">
                </div>
            </div>

            <!-- Mobile Nav Links -->
            <nav class="nav flex-column gap-1">
                <a href="<?= $home_link ?>" class="nav-link text-dark fw-medium <?= (isset($page) && $page == 'home') ? 'text-primary' : '' ?>">
                    <i class="fas fa-home me-2"></i><?= __('store.products') ?>
                </a>

                <?php if (!empty($category_tree)): ?>
                <div class="py-2">
                    <p class="text-uppercase small fw-bold text-muted mb-2 px-3" style="font-size:11px;letter-spacing:0.05em">
                        <?= __('store.categories') ?>
                    </p>
                    <?php foreach ($category_tree as $cat): ?>
                    <a href="<?= base_url('store/category/' . $cat['slug']) ?>" class="nav-link text-dark py-1">
                        <i class="fas fa-tag me-2 text-muted" style="font-size:12px"></i><?= htmlspecialchars($cat['name']) ?>
                    </a>
                    <?php endforeach; ?>
                    <a href="<?= base_url('store/category/') ?>" class="nav-link text-primary fw-medium py-1">
                        <i class="fas fa-th-large me-2"></i><?= __('store.all_categories') ?>
                    </a>
                </div>
                <?php endif; ?>

                <hr class="my-2">

                <a href="<?= $base_url ?>about" class="nav-link text-dark fw-medium">
                    <i class="fas fa-info-circle me-2"></i><?= __('store.about') ?>
                </a>
                <a href="<?= $base_url ?>contact" class="nav-link text-dark fw-medium">
                    <i class="fas fa-envelope me-2"></i><?= __('store.contact') ?>
                </a>

                <hr class="my-2">

                <a href="<?= $base_url ?>cart" class="nav-link text-dark fw-medium">
                    <i class="fas fa-shopping-bag me-2"></i><?= __('store.my_cart') ?>
                </a>

                <?php if ($is_logged): ?>
                <a href="<?= $base_url ?>profile" class="nav-link text-dark fw-medium">
                    <i class="fas fa-user me-2"></i><?= __('store.profile') ?>
                </a>
                <a href="<?= $base_url ?>order" class="nav-link text-dark fw-medium">
                    <i class="fas fa-gift me-2"></i><?= __('store.order') ?>
                </a>
                <a href="<?= $base_url ?>wishlist" class="nav-link text-dark fw-medium">
                    <i class="fas fa-heart me-2"></i><?= __('store.wishlist') ?>
                </a>
                <a href="<?= $base_url ?>logout" class="nav-link text-danger fw-medium">
                    <i class="fas fa-power-off me-2"></i><?= __('store.logout') ?>
                </a>
                <?php else: ?>
                <a href="<?= $base_url ?>login" class="nav-link fw-medium">
                    <i class="fas fa-sign-in-alt me-2"></i><?= __('store.login') ?>
                </a>
                <?php endif; ?>

                <!-- Mobile Currency & Language -->
                <hr class="my-2">
                <?php if (isset($CurrencyHtml) && !empty($CurrencyHtml)): ?>
                <div class="px-3 py-1" id="store_currency_menu_mobile"><?= $CurrencyHtml ?></div>
                <?php endif; ?>
                <?php if (isset($LanguageHtml) && !empty($LanguageHtml)): ?>
                <div class="px-3 py-1" id="store_lang_menu_mobile"><?= $LanguageHtml ?></div>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         PAGE CONTENT
         ═══════════════════════════════════════════════════════════ -->
    <main class="s26-main">
        <div class="page-wrapper">
            <?= $content; ?>
        </div>
    </main>

    <!-- Flash Messages -->
    <div class="print-message" style="position:fixed;top:80px;right:20px;z-index:9999;max-width:400px">
        <?php if (function_exists('print_message')): ?>
            <?php print_message($this); ?>
        <?php endif; ?>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         RECENTLY VIEWED (rendered by v14-store.js)
         ═══════════════════════════════════════════════════════════ -->
    <div class="container recently-viewed-section" id="s26-recently-viewed-wrap" style="display:none; margin-bottom:40px">
        <h5 class="s26-section-title" style="font-size:1.25rem"><i class="fas fa-history me-2 text-muted"></i><?= __('store.recently_viewed') ?? 'Recently Viewed' ?></h5>
        <div id="recently-viewed-container"></div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         FOOTER — Amazon-Style
         ═══════════════════════════════════════════════════════════ -->

    <!-- Back to Top -->
    <a href="#" class="amz-back-to-top" id="amzBackToTop">
        <i class="fas fa-chevron-up"></i> <?= __('store.back_to_top') ?? 'Back to top' ?>
    </a>

    <footer class="amz-footer">
        <div class="container">
            <div class="amz-footer-grid">
                <!-- Column 1 -->
                <div class="amz-footer-col">
                    <h6><?= __('store.get_to_know_us') ?? 'Get to Know Us' ?></h6>
                    <a href="<?= $base_url ?>about"><?= __('store.about') ?></a>
                    <a href="<?= $base_url ?>contact"><?= __('store.contact') ?></a>
                    <?php if (!empty($store_setting['social_links'])): ?>
                    <?php $social_links = is_array($store_setting['social_links']) ? $store_setting['social_links'] : json_decode($store_setting['social_links'], true); ?>
                    <?php if (!empty($social_links)): ?>
                    <div class="amz-social-links">
                        <?php foreach ($social_links as $link): ?>
                            <?php if (!empty($link['url'])): ?>
                            <a href="<?= $link['url'] ?>" target="_blank" rel="noopener">
                                <?php if (!empty($link['image'])): ?>
                                    <img src="<?= base_url('assets/images/site/' . $link['image']) ?>" alt="" height="16">
                                <?php elseif (!empty($link['icon'])): ?>
                                    <i class="<?= $link['icon'] ?>"></i>
                                <?php endif; ?>
                            </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Column 2 -->
                <div class="amz-footer-col">
                    <h6><?= __('store.make_money') ?? 'Make Money with Us' ?></h6>
                    <?php if (!empty($category_tree)): ?>
                        <?php foreach (array_slice($category_tree, 0, 4) as $cat): ?>
                            <a href="<?= base_url('store/category/' . $cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <a href="<?= $base_url ?>category"><?= __('store.all_categories') ?? 'All Categories' ?></a>
                </div>

                <!-- Column 3 -->
                <div class="amz-footer-col">
                    <h6><?= __('store.let_us_help_you') ?? 'Let Us Help You' ?></h6>
                    <?php if ($is_logged): ?>
                        <a href="<?= $base_url ?>profile"><?= __('store.profile') ?></a>
                        <a href="<?= $base_url ?>order"><?= __('store.order') ?></a>
                        <a href="<?= $base_url ?>shipping"><?= __('store.shipping') ?></a>
                        <a href="<?= $base_url ?>wishlist"><?= __('store.wishlist') ?></a>
                        <a href="<?= $base_url ?>my_courses"><?= __('store.my_courses') ?></a>
                    <?php else: ?>
                        <a href="<?= $base_url ?>login"><?= __('store.login') ?></a>
                        <a href="<?= $base_url ?>register"><?= __('store.register') ?? 'Register' ?></a>
                    <?php endif; ?>
                    <a href="<?= $base_url ?>policy"><?= __('store.policy') ?></a>
                    <a href="<?= $base_url ?>contact"><?= __('store.help') ?? 'Help' ?></a>
                </div>

                <!-- Column 4 -->
                <div class="amz-footer-col">
                    <h6><?= htmlspecialchars($store_setting['name'] ?? '') ?></h6>
                    <p class="amz-footer-desc"><?= htmlspecialchars($store_setting['store_description'] ?? '') ?></p>
                    <?php if (!empty($store_setting['contact_number'])): ?>
                    <div class="amz-footer-contact">
                        <i class="fas fa-phone"></i>
                        <span><?= $store_setting['contact_number'] ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($store_setting['email'])): ?>
                    <div class="amz-footer-contact">
                        <i class="fas fa-envelope"></i>
                        <span><?= $store_setting['email'] ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($store_setting['address'])): ?>
                    <div class="amz-footer-contact">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= $store_setting['address'] ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="amz-footer-bottom">
            <div class="container">
                <div class="amz-footer-bottom-inner">
                    <a href="<?= $home_link ?>" class="amz-footer-logo">
                        <img src="<?= $logo ?>" alt="<?= htmlspecialchars($store_setting['name'] ?? 'Store') ?>"
                             onerror="this.src='<?= base_url('assets/store/default/img/logo.png') ?>';"
                             height="24">
                    </a>
                    <?php if (isset($settings['footer']) && $settings['footer'] != ''): ?>
                        <span><?= $settings['footer'] ?></span>
                    <?php else: ?>
                        <span>&copy; <?= date('Y') ?> <?= htmlspecialchars($store_setting['name'] ?? '') ?>. <?= __('store.all_rights_reserved') ?? 'All rights reserved.' ?></span>
                    <?php endif; ?>
                    <a href="<?= $base_url ?>policy"><?= __('store.privacy') ?? 'Privacy' ?></a>
                    <a href="<?= $base_url ?>policy"><?= __('store.terms') ?? 'Terms' ?></a>

                    <!-- Payment Gateways -->
                    <?php if (function_exists('get_payment_gateways')): ?>
                    <div class="amz-payment-icons">
                        <?php
                        $payments = get_payment_gateways();
                        foreach ($payments as $payment) {
                            if (!empty($payment['status'])) {
                                echo '<img alt="' . htmlspecialchars($payment['title']) . '" src="' . base_url($payment['icon']) . '" title="' . htmlspecialchars($payment['title']) . '" onerror="this.onerror=null;this.style.display=\'none\'">';
                            }
                        }
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <div style="display:none;">
        <a href="<?= base_url() ?>"><?= __('store.affiliate_pro') ?></a>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         MOBILE BOTTOM NAVIGATION BAR
         ═══════════════════════════════════════════════════════════ -->
    <div class="s26-mobile-nav d-lg-none">
        <a href="<?= $home_link ?>" class="<?= (isset($page) && $page == 'home') ? 'active' : '' ?>">
            <i class="fas fa-home"></i><span><?= __('store.home') ?? 'Home' ?></span>
        </a>
        <a href="<?= $base_url ?>category" class="<?= (isset($page) && $page == 'category') ? 'active' : '' ?>">
            <i class="fas fa-th-large"></i><span><?= __('store.categories') ?></span>
        </a>
        <a href="<?= $base_url ?>cart" class="<?= (isset($page) && $page == 'cart') ? 'active' : '' ?>">
            <i class="fas fa-shopping-bag"></i><span><?= __('store.cart') ?? 'Cart' ?></span>
        </a>
        <a href="<?= $is_logged ? $base_url . 'profile' : $base_url . 'login' ?>" class="<?= (isset($page) && in_array($page, ['profile','account'])) ? 'active' : '' ?>">
            <i class="fas fa-user"></i><span><?= $is_logged ? (__('store.my_account') ?? 'Account') : (__('store.login') ?? 'Login') ?></span>
        </a>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         ADD-TO-CART CONFIRMATION MODAL
         ═══════════════════════════════════════════════════════════ -->
    <div class="modal fade" id="cart-confirm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:var(--s26-radius-lg);overflow:hidden">
                <div class="text-center py-5 px-4">
                    <div style="width:64px;height:64px;border-radius:50%;background:var(--s26-success-light);display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px">
                        <i class="fas fa-check" style="font-size:28px;color:var(--s26-success)"></i>
                    </div>
                    <h5 id="product-name-prev" class="fw-bold mb-2"></h5>
                    <p class="text-muted mb-4"><?= __('store.has_beent_added_to_your_cart') ?></p>
                    <div class="d-flex flex-column gap-2" style="max-width:280px;margin:0 auto">
                        <a href="<?= $base_url ?>checkout" class="s26-btn-primary w-100 justify-content-center">
                            <?= __('store.procceed_to_checkout') ?>
                        </a>
                        <button type="button" class="s26-btn-outline w-100 justify-content-center" data-bs-dismiss="modal">
                            <?= __('store.continue_shopping') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         COOKIES CONSENT
         ═══════════════════════════════════════════════════════════ -->
    <?php include APPPATH . 'views/store/starter2026/cookies_consent.php'; ?>

    <!-- ═══════════════════════════════════════════════════════════
         SCRIPTS — End of Body
         ═══════════════════════════════════════════════════════════ -->

    <!-- Cookie consent script (inline fallback for legacy) -->
    <?php if (!empty($cookies_consent) && $cookies_consent == 1): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (!localStorage.getItem("cookieConsent")) {
            var popup = document.getElementById("cookie-consent-popup");
            if (popup) popup.style.display = "flex";
        }
        var acceptBtn = document.getElementById("cookie-consent-accept");
        if (acceptBtn) acceptBtn.addEventListener("click", function() {
            localStorage.setItem("cookieConsent", "accepted");
            $("#cookie-consent-popup").remove();
        });
        var declineBtn = document.getElementById("cookie-consent-decline");
        if (declineBtn) declineBtn.addEventListener("click", function() {
            localStorage.setItem("cookieConsent", "declined");
            $("#cookie-consent-popup").remove();
        });
    });
    </script>
    <?php endif; ?>

    <!-- Flash message auto-remove -->
    <script type="text/javascript">
    function removeAlertsAfterTimeout() {
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() { $(this).remove(); });
        }, 4000);
    }
    removeAlertsAfterTimeout();
    </script>

    <!-- ═══════════════════════════════════════════════════════════
         MAIN STORE JAVASCRIPT
         ═══════════════════════════════════════════════════════════ -->
    <script type="text/javascript">
    var store_base_url = '<?= $base_url ?>';

    /* ── Tooltip for cart buttons ── */
    $(function() {
        $(document).tooltip && $('.btn-cart').tooltip({ trigger: 'click', placement: 'top' });
    });

    function setTooltip(message) {
        $('.btn-cart').tooltip('hide').attr('data-original-title', message).tooltip('show');
    }
    function hideTooltip() {
        $('.btn-cart').tooltip('hide');
    }

    /* ── Add to cart ── */
    $(function() {
        $(document).on('click', '.btn-cart', function() {
            var quantity = ($('input#product-quantity').length) ? $('input#product-quantity').val() : 1;
            var product_name = $(this).data('product_name');
            var product_id = $(this).data('product_id');
            var $this = $(this);

            var variationNotSelected = [];
            var variationSelected = {};

            if ($('.variation-row .variations').length != 0) {
                $('.variation-row .variations').each(function() {
                    var type = $(this).find('span:first-child').data('variation-type');
                    var optionSpan = $(this).find('.active');
                    if (optionSpan.length) {
                        variationSelected['price'] = optionSpan.data('variation-price');
                        if (type == 'colors') {
                            variationSelected[type] = optionSpan.data('variation-code') + '-' + optionSpan.data('variation-name');
                        } else {
                            variationSelected[type] = optionSpan.data('variation-option');
                        }
                    } else {
                        variationNotSelected.push(type);
                    }
                });
            }

            if (variationNotSelected.length) {
                var warningMessage = '<?= __('store.please_select') ?>' + ' ';
                for (var i = 0; i < variationNotSelected.length; i++) {
                    warningMessage += (i == 0) ? variationNotSelected[i] : ', ' + variationNotSelected[i];
                }
                if (typeof setTooltip === 'function') setTooltip(warningMessage + ' ' + '<?= __('store.before_add_to_cart') ?>');
            } else {
                $.ajax({
                    url: '<?= isset($add_tocart_url) ? $add_tocart_url : $base_url . "add_to_cart" ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        quantity: quantity,
                        product_id: product_id,
                        variation: variationSelected,
                    },
                    beforeSend: function() { $this.btn("loading"); },
                    complete: function() { $this.btn("reset"); },
                    success: function(json) {
                        if (json['location']) {
                            updateCart();
                            $('#cart-confirm #product-name-prev').text(product_name);
                            $('#cart-confirm').modal('show');
                        } else {
                            if (json['error']) {
                                $(".print-message").html(
                                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                    '<i class="bi bi-exclamation-triangle-fill me-2"></i>&nbsp;' + json['error'] +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                );
                                removeAlertsAfterTimeout();
                            }
                        }
                    },
                    error: function() {
                        $(".print-message").html('<div class="alert alert-danger alert-dismissible fade show">An error occurred. Please try again later.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                        removeAlertsAfterTimeout();
                    }
                });
            }
        });

        /* ── Remove from mini-cart ── */
        $(document).on('click', '.cart-dropdown .btn-remove-cart', function() {
            var $this = $(this);
            $.ajax({
                url: $this.attr('data-href'),
                type: 'POST',
                dataType: 'json',
                success: function() { updateCart(); }
            });
            return false;
        });

        /* ── Cart flyout toggle ── */
        $(document).on('click', '.cart-top', function(e) {
            if ($(e.target).closest('.btn-remove-cart, a').length) return;
            $('.cart-dropdown').slideToggle(200);
        });

        /* ── Close dropdowns on outside click ── */
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.cart-top').length) {
                $('.cart-dropdown').slideUp(200);
            }
        });

        /* ── Init cart on load ── */
        updateCart();
    });

    /* ── Login form mobile fix ── */
    $(function() {
        $("#login-form input, #register-form input").focus(function() {
            if ($(document).width() <= 408) {
                $(".s26-header, .s26-footer").hide();
            }
        });
        $("#login-form input, #register-form input").blur(function() {
            $(".s26-header, .s26-footer").show();
        });
    });

    /* ── Language / Currency dropdown toggle ── */
    $(function() {
        // Toggle language dropdown
        $(document).on('click', '#store_lang_menu > a.js-link, #store_lang_menu_mobile > a.js-link', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $parent = $(this).closest('[id^="store_lang_menu"]');
            var $list = $parent.find('.js-dropdown-list');
            // Close currency dropdown if open
            $('.js-dropdown-list1').removeClass('s26-dd-show');
            $('[id^="store_currency_menu"]').removeClass('s26-dd-open');
            // Toggle this one
            $list.toggleClass('s26-dd-show');
            $parent.toggleClass('s26-dd-open');
        });
        // Toggle currency dropdown
        $(document).on('click', '#store_currency_menu > a.js-link1, #store_currency_menu_mobile > a.js-link1', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $parent = $(this).closest('[id^="store_currency_menu"]');
            var $list = $parent.find('.js-dropdown-list1');
            // Close language dropdown if open
            $('.js-dropdown-list').removeClass('s26-dd-show');
            $('[id^="store_lang_menu"]').removeClass('s26-dd-open');
            // Toggle this one
            $list.toggleClass('s26-dd-show');
            $parent.toggleClass('s26-dd-open');
        });
        // Close dropdowns when clicking elsewhere
        $(document).on('click', function() {
            $('.js-dropdown-list, .js-dropdown-list1').removeClass('s26-dd-show');
            $('[id^="store_lang_menu"], [id^="store_currency_menu"]').removeClass('s26-dd-open');
        });
        // Prevent closing when clicking inside the dropdown list
        $(document).on('click', '.js-dropdown-list, .js-dropdown-list1', function(e) {
            e.stopPropagation();
        });
    });

    /* ── Currency symbol updater ── */
    $(function() {
        function updateSymbol() {
            var selected = $(".currency-selector option:selected");
            $(".currency-symbol").text(selected.data("symbol"));
            $(".currency-amount").prop("placeholder", selected.data("placeholder"));
            $(".currency-addon-fixed").text(selected.text());
        }
        $(".currency-selector").on("change", updateSymbol);
        updateSymbol();
    });


    /* ── Recently Viewed: show wrapper only when valid items exist ── */
    $(function() {
        var wrap = document.getElementById('s26-recently-viewed-wrap');
        if (!wrap) return;
        var inner = document.getElementById('recently-viewed-container');
        if (!inner) return;

        // Inline SVG data-URI placeholder (no external file needed)
        var noImgSrc = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'%3E%3Crect fill='%23f1f5f9' width='200' height='200'/%3E%3Cg transform='translate(100,90)' text-anchor='middle'%3E%3Crect x='-30' y='-24' width='60' height='44' rx='6' fill='none' stroke='%23cbd5e1' stroke-width='2'/%3E%3Ccircle cx='-14' cy='-10' r='5' fill='%23cbd5e1'/%3E%3Cpath d='M-26 14 L-10-2 L0 8 L10-6 L26 14Z' fill='%23cbd5e1'/%3E%3Ctext y='44' font-family='sans-serif' font-size='11' fill='%2394a3b8'%3ENo Image%3C/text%3E%3C/g%3E%3C/svg%3E";

        function processRecentlyViewed() {
            if (inner.style.display === 'none' || inner.children.length === 0) return;
            var items = inner.querySelectorAll('.recently-viewed-item');
            var validCount = 0;
            items.forEach(function(item) {
                var nameEl = item.querySelector('.name');
                var imgEl = item.querySelector('img');
                var href = item.getAttribute('href') || '';
                // Hide item if name is missing/empty or URL is invalid
                if (!nameEl || !nameEl.textContent.trim() || !href || href === 'undefined' || href.indexOf('http') !== 0) {
                    item.style.display = 'none';
                    return;
                }
                if (imgEl) {
                    var imgSrc = imgEl.getAttribute('src') || '';
                    // If src is "undefined" or empty, replace immediately
                    if (!imgSrc || imgSrc === 'undefined' || imgSrc.indexOf('undefined') !== -1) {
                        imgEl.src = noImgSrc;
                        imgEl.classList.add('s26-img-fallback');
                    } else {
                        imgEl.onerror = function() {
                            this.onerror = null;
                            this.src = noImgSrc;
                            this.classList.add('s26-img-fallback');
                        };
                        if (imgEl.complete && imgEl.naturalWidth === 0) {
                            imgEl.src = noImgSrc;
                            imgEl.classList.add('s26-img-fallback');
                        }
                    }
                }
                validCount++;
            });
            if (validCount > 0) {
                wrap.style.display = '';
            }
        }

        var mo = new MutationObserver(function() { processRecentlyViewed(); });
        mo.observe(inner, { childList: true, attributes: true });
        // Also check immediately (in case v14 already ran)
        processRecentlyViewed();
    });

    /* ── Header scroll effect ── */
    (function() {
        var header = document.getElementById('s26Header');
        if (header) {
            var lastScroll = 0;
            window.addEventListener('scroll', function() {
                var st = window.pageYOffset || document.documentElement.scrollTop;
                if (st > 10) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                lastScroll = st;
            }, { passive: true });
        }
    })();
    </script>

    <!-- Announcement bar rotation -->
    <?php if (!empty($notification_items) && count($notification_items) > 1): ?>
    <script type="text/javascript">
    $(document).ready(function() {
        var items = <?= json_encode(array_values($notification_items)); ?>;
        var $text = $('.s26-announcement-text');
        var idx = 0;
        if (items.length > 1) {
            setInterval(function() {
                idx = (idx + 1) % items.length;
                $text.fadeOut(300, function() {
                    $text.text(items[idx]).fadeIn(300);
                });
            }, 4000);
        }
    });
    </script>
    <?php endif; ?>

    <!-- updateCart() AJAX function -->
    <script type="text/javascript">
    function updateCart() {
        $.ajax({
            url: store_base_url + 'mini_cart',
            type: 'POST',
            dataType: 'json',
            success: function(json) {
                if (json) {
                    $(".cart-top .cart-dropdown").html(json['cart']);
                    var total = parseInt(json['total']) || 0;
                    $(".cart-top .cart-count").html(total);
                    if (total > 0) {
                        $(".cart-top .cart-count").show();
                    } else {
                        $(".cart-top .cart-count").hide();
                    }
                    if ($('#cart-sub-total').length) {
                        $('#cart-sub-total').text(json['sub_total']);
                    }
                }
            }
        });
    }
    </script>

    <!-- Affiliate localStorage handler -->
    <script>
    <?php if (!empty($affiliate_localstorage_init)): ?>
        var setLocalStorageAffiliateAjax = <?= $affiliate_localstorage_init ?>;
        setWithExpiry("affiliate_id", setLocalStorageAffiliateAjax[0], setLocalStorageAffiliateAjax[1]);
    <?php endif; ?>

    function setWithExpiry(key, value, ttl) {
        var now = new Date();
        var item = { value: value, expiry: now.getTime() + ttl };
        localStorage.setItem(key, JSON.stringify(item));
    }

    function getWithExpiry(key) {
        var itemStr = localStorage.getItem(key);
        if (!itemStr) return 1;
        var item = JSON.parse(itemStr);
        var now = new Date();
        if (now.getTime() > item.expiry) {
            localStorage.removeItem(key);
            return 1;
        }
        return item.value;
    }
    </script>

    <!-- Page custom script -->
    <?php if (isset($page_custom_script)): ?>
        <?= $page_custom_script; ?>
    <?php endif; ?>

    <!-- V14 Store Config & Enhancement -->
    <script>
    window.storeConfig = {
        base_url: '<?= base_url() ?>',
        store_url: '<?= base_url("store") ?>',
        currency: <?= json_encode($store_currency) ?>,
        social_proof: <?= (!empty($store_setting['social_proof_enabled'])) ? 'true' : 'false' ?>
    };
    </script>
    <script src="<?= base_url('assets/store/shared/js/store.js') ?>?v=<?= av() ?>"></script>

    <!-- Store Search Functionality -->
    <script type="text/javascript">
    $(function() {
        var searchTimer;
        $(document).on('keyup', '.store-search-input', function() {
            var $input = $(this);
            var query = $input.val().trim();
            var $results = $('#s26-search-results');

            clearTimeout(searchTimer);

            if (query.length < 2) {
                $results.hide();
                return;
            }

            searchTimer = setTimeout(function() {
                $.ajax({
                    url: store_base_url + 'search',
                    type: 'GET',
                    data: { q: query },
                    dataType: 'json',
                    success: function(data) {
                        if (data && data.length > 0) {
                            var html = '<div class="s26-search-list">';
                            $.each(data, function(i, item) {
                                var img = item.image || '<?= base_url("assets/store/default/img/pr-img.png") ?>';
                                html += '<a href="' + item.url + '" class="s26-search-item">';
                                html += '<img src="' + img + '" alt="">';
                                html += '<div><strong>' + item.name + '</strong>';
                                if (item.price) html += '<span class="text-primary fw-bold d-block">' + item.price + '</span>';
                                html += '</div></a>';
                            });
                            html += '</div>';
                            $results.html(html).show();
                        } else {
                            $results.html('<div class="p-3 text-center text-muted small">No products found</div>').show();
                        }
                    }
                });
            }, 300);
        });

        // Submit search on Enter
        $(document).on('keypress', '.store-search-input', function(e) {
            if (e.which === 13) {
                var query = $(this).val().trim();
                if (query.length > 0) {
                    window.location.href = store_base_url + 'search?q=' + encodeURIComponent(query);
                }
            }
        });

        // Close search results on outside click
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.instant-search-wrapper').length) {
                $('#s26-search-results').hide();
            }
        });
    });
    </script>

    <!-- Intersection Observer for scroll animations -->
    <script>
    (function() {
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('.s26-reveal, .store-fade-in').forEach(function(el) {
                observer.observe(el);
            });
        }
    })();
    </script>

    <!-- Back to Top Button -->
    <script>
    (function() {
        var btn = document.getElementById('amzBackToTop');
        if (!btn) return;
        window.addEventListener('scroll', function() {
            btn.style.display = window.pageYOffset > 300 ? 'block' : 'none';
        }, { passive: true });
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();

    /* Account/Cart Dropdown Hover (Desktop) */
    (function() {
        if (window.innerWidth < 992) return;
        $('.amz-dropdown, .amz-cart').on('mouseenter', function() {
            $(this).find('.amz-dropdown-menu').stop(true, true).fadeIn(150);
        }).on('mouseleave', function() {
            $(this).find('.amz-dropdown-menu').stop(true, true).fadeOut(150);
        });
    })();
    </script>

<?php include APPPATH . 'views/store/includes/store_img_error_fallback.php'; ?>

</body>
</html>
