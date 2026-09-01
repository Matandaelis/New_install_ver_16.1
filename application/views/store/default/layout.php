<?php
/**
 * Default theme — Main layout wrapper
 *
 * @contract  Store API v1 — global: layout
 * @see       Storeapp::view() — injects all variables before rendering
 *
 * LAYOUT-SPECIFIC INJECTED VARIABLES (not from API pages, set by Storeapp/Store controller)
 *   $content                     string  The rendered inner page HTML (from the page view file)
 *   $page                        string  Page identifier slug (e.g. 'home', 'product', 'cart')
 *   $meta_title                  string  Page title suffix shown in <title> and OG tags
 *   $meta_description            string  Meta description for SEO
 *   $meta_image                  string  OG image URL for social sharing
 *   $canonical_url               string  Canonical URL for SEO
 *   $SiteSetting                 array   Global site/store settings (same as $store_setting)
 *   $settings                    array   Alias of $SiteSetting
 *   $store_setting               array   Alias of $SiteSetting
 *   $user_id                     int     Logged-in user ID; 0 if guest
 *   $affiliate_localstorage_init string  JS snippet to init affiliate tracking (injected in <head>)
 *   $page_custom_script          string  Per-page extra JS/CSS snippet (injected before </body>)
 *   $is_logged                   bool    Whether a customer is logged in
 *   $client                      array   Logged-in customer array; empty array if guest
 *   $home_link                   string  Absolute URL to store homepage
 *
 * NOTE  $content holds the rendered output of the page-specific view file (e.g. home.php).
 *       $meta_title is used for both <title> and og:title. $SiteSetting['name'] is the store name prefix.
 */
  // Settings provided by Storeapp::view() — $SiteSetting is already a key→value map
  $cart_store_side_font  = $SiteSetting['cart_store_side_font']  ?? '';
  $cookies_consent       = $SiteSetting['cookies_consent']       ?? '0';
  $cookies_consent_mesag = $SiteSetting['cookies_consent_mesag'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
    <meta name="author" content=""/>
    
    <meta property='og:url' content='<?= $_SERVER['REQUEST_URI']; ?>'/>
    <?php if(isset($meta_title)){ ?> <meta property="og:title" content="<?php echo $meta_title ?>"/><?php } ?>
    <?php if(isset($meta_description)){ ?> 
      <meta name="description" content="<?php echo $meta_description ?>"/>
      <meta property="og:description" content="<?php echo $meta_description ?>"/>
    <?php } ?>
    <?php if(isset($meta_image)){ ?> <meta property="og:image" content="<?php echo $meta_image ?>"/><?php } ?>
    <?php 
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    ?>
    <meta property="og:url" content="<?= $actual_link ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>

    <?php if($store_setting['favicon']){ ?>
        <link rel="icon" href="<?= base_url('assets/images/site/'.$store_setting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php } ?>

    <?php 
    // Add canonical URL for SEO to prevent duplicate content issues
    if(isset($product) && isset($product['product_slug']) && isset($user_id)) {
        // Use the standard affiliate URL format as canonical
        $canonical_url = base_url("store/" . base64_encode((int)$user_id) . "/product/" . $product['product_slug']);
        echo '<link rel="canonical" href="' . $canonical_url . '" />';
    } else {
        // For other pages, use current URL
        echo '<link rel="canonical" href="' . $actual_link . '" />';
    }
    ?>

    <title><?= $store_setting['name'] ?>  <?= isset($meta_title) ? '- ' . $meta_title : '' ?></title>

    <!--  CSS — Bootstrap 5.3 (shared template bundle) -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/css/amazon-tokens.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/fonts/fonts.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/css/placeholder-loading.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/template/css/sweetalert2.min.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/css/nouislider.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/store/default/css/style.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/store/default/css/thankyou.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/store/default/css/responsive.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/store/default/css/v14-modern.css') ?>?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/all.min.css') ?>?v=<?= av() ?>" />
    
    <!-- ═══════════ MODERN UI LIBRARIES ═══════════ -->
    <!-- GSAP + ScrollTrigger for animations -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    
    <!-- Swiper for carousels -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- GLightbox for product image lightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    
    <!-- Tippy.js for tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy.min.css" />
    
    <!-- Alpine.js for reactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <!-- ═══════════ END MODERN UI LIBRARIES ═══════════ -->
    
    <!-- JS — jQuery loaded from shared store bundle (same source as starter2026 theme) -->
    <script src="<?= base_url('assets/store/shared/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/plugins/store/jquery.star-rating-svg.js') ?>"></script>
    <script src="<?= base_url('assets/store/shared/js/nouislider.min.js') ?>"></script>
    <script src="<?= base_url('assets/template/js/sweetalert2.all.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/plugins/mustache.js') ?>"></script>

    <?php if(!empty($store_setting['google_analytics'])): ?>
    <script type="text/javascript">
    <?php echo $store_setting['google_analytics']; ?>
    </script>
    <?php endif; ?>
 
    <?php 
        if(isset($store_setting['per_task']) && !empty($store_setting['per_task'])){
            $per_tasks = json_decode($store_setting['per_task'], true);
            if(!empty($per_tasks)){
                foreach ($per_tasks as $per_task){
                    // Clean the task content
                    $per_task_clean = trim($per_task);
                    
                    // Check if it's CSS (contains <style> tags or looks like CSS)
                    if (strpos($per_task_clean, '<style') !== false || strpos($per_task_clean, '</style>') !== false) {
                        // Render as CSS directly
                        echo $per_task_clean;
                    }
                    // Check if it's HTML (contains HTML tags but not script)
                    elseif (strpos($per_task_clean, '<') !== false && strpos($per_task_clean, '<script') === false) {
                        // Render as HTML directly
                        echo $per_task_clean;
                    }
                    // Check if it contains <script> tags
                    elseif (strpos($per_task_clean, '<script') !== false) {
                        // Render script tags directly
                        echo $per_task_clean;
                    }
                    // Otherwise treat as pure JavaScript
                    else {
                        // Remove any existing script tags if present
                        $per_task_js = preg_replace('/<script[^>]*>/', '', $per_task_clean);
                        $per_task_js = preg_replace('/<\/script>/', '', $per_task_js);
                        ?>
                        <script type="text/javascript">
                        try {
                            <?php echo $per_task_js; ?>
                        } catch (error) {
                            console.log('Script error:', error);
                        }
                        </script>
                        <?php
                    }
                }
            }
        }
    ?>

    <?php 
        $global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
        if(in_array('store', $global_script_status)){
            echo $SiteSetting['global_script'];
        }
    ?>

    <!-- Restore $ after per_task / global_script may have called jQuery.noConflict() -->
    <script>if(typeof jQuery!=='undefined'&&typeof $==='undefined'){window.$=jQuery;}</script>

    <script type="text/javascript">
        (function ($) {
            $.fn.btn = function (action) {
                var self = $(this);
                if (action == 'loading') {
                    if ($(self).attr("disabled") == "disabled") {
                    }
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
            if (!(window.FormData && formData instanceof window.FormData)) return formData
            if (!formData.keys) return formData
            var newFormData = new window.FormData()
            Array.from(formData.entries()).forEach(function(entry) {
                var value = entry[1]
                if (value instanceof window.File && value.name === '' && value.size === 0) {
                    newFormData.append(entry[0], new window.Blob([]), '')
                } else {
                    newFormData.append(entry[0], value)
                }
            });
            return newFormData;
        }
    </script>

    <?php if (is_rtl()) { ?>
      <!-- place here your RTL css code -->
      <link rel="stylesheet" href="<?= base_url('assets/store/default/css/rtl.css') ?>?v=<?= av() ?>" />
    <?php } ?>

    <style type="text/css">
      :root {
        --amz-font: <?= $cart_store_side_font ?: 'Arial, Helvetica, sans-serif' ?>, Arial, Helvetica, sans-serif;
      }
      * {
        font-family: <?= $cart_store_side_font ?: 'Arial, Helvetica, sans-serif' ?>, Arial, Helvetica, sans-serif !important;
      }
      h1, h2, h3, h4, h5, h6 {
        font-family: <?= $cart_store_side_font ?: 'Arial, Helvetica, sans-serif' ?>, Arial, Helvetica, sans-serif !important;
      }
    </style>
<?= render_js_error_reporter() ?>
</head>

<body style="font-family: <?= $cart_store_side_font ?> !important;">
    <!-- Skip Navigation Link for Accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <?php 
    $fbmessager_status = (array)json_decode($SiteSetting['fbmessager_status'],1);
    if(in_array('store', $fbmessager_status)){
        echo $SiteSetting['fbmessager_script'];
    }
    ?>

    <?php show_messenger_button($SiteSetting, 'store'); ?>
          
    <?php
    $_notif = !empty($store_setting['notification']) ? json_decode($store_setting['notification']) : [];
    if(is_array($_notif) && count($_notif) > 0 && !empty($_notif[0])) { 
    ?>
    
    <!-- Top notification bar -->
    <div class="top-bar bg-main text-white text-center">
      <div class="container">
        <img alt="<?= __('store.image') ?>" src="<?= base_url('assets/store/default/'); ?>img/top-icon.png" /> <?= $_notif[0] ?? '' ?>
      </div>
    </div>
    <?php
    } else {
    ?>
    <!-- Dummy Top notification bar -->
    <div class="top-bar bg-main text-white text-center">
      <div class="container">
        <img alt="<?= __('store.image') ?>" src="<?= base_url('assets/store/default/'); ?>img/top-icon.png" /> Lorem Ipsum is simply dummy text of the printing and typesetting industry.
      </div>
    </div>

        <?php
        }
        $storelogoheight=36;
        $storelogowidthstr='';
          if($store_setting['store_custom_logo_size']!=0)
          {
            $storelogowidth=$store_setting['store_logo_custom_width'];
            $storelogoheight=$store_setting['store_logo_custom_height'];
            $storelogowidthstr= 'width="'.$storelogowidth.'"'; 
          }
        ?>
        <!-- ═══ Amazon-Style Header ═══ -->
        <header id="myHeader">
            <?php $logo = ($store_setting['logo']) ? base_url('assets/images/site/'.$store_setting['logo']) : base_url('assets/store/default/').'img/logo.png'; ?>

            <!-- Primary Nav Bar — #131921 -->
            <div class="amz-header-primary">
                <div class="container-fluid">
                    <div class="amz-header-row">
                        <!-- Logo -->
                        <a class="amz-logo" href="<?= $home_link ?>">
                            <img alt="<?= $store_setting['name'] ?>" src="<?= $logo ?>" onerror="this.src='<?=base_url('assets/store/default/').'img/logo.png'?>';" height="<?= $storelogoheight ?>" <?= $storelogowidthstr ?> />
                        </a>

                        <!-- Deliver To -->
                        <div class="amz-deliver-to">
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            <div>
                                <span class="amz-deliver-label"><?= __('store.deliver_to') ?: 'Deliver to' ?></span>
                                <span class="amz-deliver-location"><?= $store_setting['address'] ? htmlspecialchars(substr($store_setting['address'], 0, 20)) : 'Select Location' ?></span>
                            </div>
                        </div>

                        <!-- Search Bar -->
                        <form class="amz-search" action="<?= $base_url ?>category" method="GET">
                            <select class="amz-search-select" name="cat" aria-label="Search category">
                                <option value="all"><?= __('store.all') ?: 'All' ?></option>
                                <?php if(!empty($category_tree)) { foreach($category_tree as $cat) { ?>
                                    <option value="<?= $cat['slug'] ?>"><?= $cat['name'] ?></option>
                                <?php } } ?>
                            </select>
                            <input type="search" class="amz-search-input" name="search" placeholder="<?= __('store.search_products') ?: 'Search products...' ?>" aria-label="Search" />
                            <button type="submit" class="amz-search-btn" aria-label="Search">
                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            </button>
                        </form>

                        <!-- Language -->
                        <div class="amz-lang-selector">
                            <?= $LanguageHtml ?>
                        </div>

                        <!-- Account -->
                        <div class="amz-account">
                            <?php if($is_logged){ ?>
                                <div class="dropdown">
                                    <?php $avatar = $client['avatar'] != '' ? base_url('assets/images/users/'. $client['avatar']) : base_url('assets/store/default/img/blog1.png'); ?>
                                    <a href="javascript:void(0)" class="amz-nav-link js-link2">
                                        <span class="amz-nav-label"><?= __('store.hello') ?: 'Hello' ?>, <?= $client['name'] ?? __('store.account') ?></span>
                                        <span class="amz-nav-bold"><?= __('store.account_list') ?: 'Account & Lists' ?></span>
                                    </a>
                                    <ul class="js-dropdown-list2 amz-dropdown">
                                        <li><a href="<?= $base_url ?>profile"><i class="fas fa-user" aria-hidden="true"></i> <?= __('store.profile') ?></a></li>
                                        <li><a href="<?= $base_url ?>order"><i class="fas fa-box" aria-hidden="true"></i> <?= __('store.order') ?></a></li>
                                        <li><a href="<?= $base_url ?>my_courses"><i class="fas fa-graduation-cap" aria-hidden="true"></i> <?= __('store.my_courses') ?></a></li>
                                        <li><a href="<?= $base_url ?>shipping"><i class="fas fa-truck" aria-hidden="true"></i> <?= __('store.shipping') ?></a></li>
                                        <li><a href="<?= $base_url ?>wishlist"><i class="fas fa-heart" aria-hidden="true"></i> <?= __('store.wishlist') ?></a></li>
                                        <li class="amz-dropdown-divider"></li>
                                        <li><a href="<?= $base_url ?>logout"><i class="fas fa-right-from-bracket" aria-hidden="true"></i> <?= __('store.logout') ?></a></li>
                                    </ul>
                                </div>
                            <?php } else { ?>
                                <a href="<?= $base_url ?>login" class="amz-nav-link">
                                    <span class="amz-nav-label"><?= __('store.hello') ?: 'Hello' ?></span>
                                    <span class="amz-nav-bold"><?= __('store.sign_in') ?: 'Sign in' ?></span>
                                </a>
                            <?php } ?>
                        </div>

                        <!-- Orders -->
                        <a href="<?= $base_url ?>order" class="amz-orders-link">
                            <span class="amz-nav-label"><?= __('store.returns') ?: 'Returns' ?></span>
                            <span class="amz-nav-bold"><?= __('store.orders') ?: '& Orders' ?></span>
                        </a>

                        <!-- Cart -->
                        <a href="javascript:void(0);" class="amz-cart cart-top position-relative" aria-label="Shopping cart"
                           x-data="amzCart()" x-on:mouseenter="showCart = true" x-on:mouseleave="showCart = false">
                            <span class="amz-cart-count cart-count" x-text="cartCount">0</span>
                            <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                            <span class="amz-cart-text"><?= __('store.cart') ?: 'Cart' ?></span>
                            <div class="cart-dropdown amz-cart-dropdown" x-show="showCart" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.away="showCart = false">
                                <template x-if="cartItems.length === 0">
                                    <div class="cart-empty">
                                        <img src="<?= base_url('assets/store/default/'); ?>img/cart-icon-empty.png" alt="<?= __('store.icon') ?>">
                                        <p><?= __('store.cart_is_blank') ?></p>
                                    </div>
                                </template>
                                <template x-if="cartItems.length > 0">
                                    <div class="amz-cart-dropdown-inner">
                                        <div class="amz-cart-dropdown-title"><?= __('store.your_cart_items') ?: 'Your Cart Items' ?></div>
                                        <template x-for="item in cartItems" :key="item.id">
                                            <div class="amz-cart-dropdown-item">
                                                <img :src="item.image" :alt="item.name" class="amz-cart-dropdown-img">
                                                <div class="amz-cart-dropdown-info">
                                                    <div class="amz-cart-dropdown-name" x-text="item.name"></div>
                                                    <div class="amz-cart-dropdown-price" x-text="item.price"></div>
                                                </div>
                                                <button class="amz-cart-dropdown-remove" @click="removeItem(item.id)" aria-label="Remove item">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>
                                        <div class="amz-cart-dropdown-footer">
                                            <a href="<?= $base_url ?>cart" class="amz-btn amz-btn-cart-dropdown"><?= __('store.view_cart') ?: 'View Cart' ?></a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Secondary Nav Bar — #232F3E -->
            <div class="amz-header-secondary">
                <div class="container-fluid">
                    <div class="amz-secondary-nav">
                        <!-- All / Hamburger -->
                        <a href="#" class="amz-nav-all" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Menu">
                            <i class="fa-solid fa-bars" aria-hidden="true"></i> <?= __('store.all') ?: 'All' ?>
                        </a>

                        <!-- Category Links -->
                        <div class="collapse navbar-collapse amz-secondary-links" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item <?= ($page == 'home') ? 'active' : ''; ?>">
                                    <a href="<?= $home_link ?>" class="nav-link"><?= __('store.today_deals') ?: "Today's Deals" ?></a>
                                </li>
                                <li class="nav-item <?= ($page == 'contact') ? 'active' : ''; ?>">
                                    <a href="<?= $base_url ?>contact" class="nav-link"><?= __('store.customer_service') ?: 'Customer Service' ?></a>
                                </li>
                                <li id="dropdownMenu2" class="nav-item dropdown <?= ($page == 'product'||$page == 'product_list'|| $page == 'category') ? 'active' : ''; ?>">
                                    <a href="<?= $base_url ?>category" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?= __('store.categories') ?></a>
                                    <ul class="dropdown-menu amz-mega-dropdown" role="menu">
                                        <li><a href="<?= base_url('store/category/') ?>"><?= __('store.all_categories') ?></a></li>
                                        <?php
                                        function display_with_children_maincategory($parentRow, $level = 0) { 
                                            $space = str_repeat("&nbsp;&nbsp;", $level);
                                            foreach ($parentRow as $key => $row) {
                                                echo '<li'.($row['children'] ? ' class="has-children"' : '').'>';
                                                echo '<a href="'. base_url('store/category/'. $row['slug']) .'">'.$space.$row['name'].'</a>';
                                                if ($row['children']) {
                                                    echo '<ul>'; display_with_children_maincategory($row['children'], $level + 1); echo '</ul>';
                                                }
                                                echo '</li>';
                                            }
                                        }
                                        display_with_children_maincategory($category_tree, 0);
                                        ?>
                                    </ul>
                                </li>
                                <li class="nav-item <?= ($page == 'about') ? 'active' : ''; ?>">
                                    <a href="<?= $base_url ?>about" class="nav-link"><?= __('store.gift_cards') ?: 'Gift Cards' ?></a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= $base_url ?>category" class="nav-link"><?= __('store.sell') ?: 'Sell' ?></a>
                                </li>
                            </ul>
                        </div>

                        <!-- Promo Banner (right side) -->
                        <div class="amz-promo-banner">
                            <?= __('store.promo_banner') ?: 'Shop deals in Electronics' ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

      <main id="main-content" class="page-wrapper" tabindex="-1">
          <?php echo $content; ?>
      </main>

    <!-- Recently Viewed Container (rendered by JS) - must be above footer -->
    <div class="container recently-viewed-section py-4">
        <h5><i class="fas fa-history me-2" aria-hidden="true"></i><?= __('store.recently_viewed') ?></h5>
        <div id="recently-viewed-container"></div>
    </div>
      
        <?php 
        $storelogowidth='';
        $storelogoheight=36;

          if($store_setting['store_custom_logo_size']==1)
          {
            $storelogowidth=$store_setting['store_logo_custom_width'];
            $storelogoheight=$store_setting['store_logo_custom_height'];
          }
        ?>
    <!-- ═══ Amazon-Style Footer ═══ -->
    <!-- Back to Top -->
    <a href="#" class="amz-back-to-top" id="amzBackToTop"><?= __('store.back_to_top') ?: 'Back to top' ?></a>

    <!-- Main Footer — #232F3E -->
    <footer class="amz-footer">
        <div class="container">
            <div class="row amz-footer-cols">
                <!-- Column 1: Get to Know Us -->
                <div class="col-6 col-md-3">
                    <div class="amz-footer-col">
                        <h5><?= __('store.get_to_know_us') ?: 'Get to Know Us' ?></h5>
                        <ul>
                            <li><a href="<?= $base_url ?>about"><?= __('store.about_us') ?: 'Careers' ?></a></li>
                            <li><a href="<?= $base_url ?>about"><?= __('store.about') ?></a></li>
                            <li><a href="#"><?= __('store.press') ?: 'Press Releases' ?></a></li>
                            <li><a href="#"><?= __('store.investor') ?: 'Amazon Science' ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Column 2: Make Money with Us -->
                <div class="col-6 col-md-3">
                    <div class="amz-footer-col">
                        <h5><?= __('store.make_money') ?: 'Make Money with Us' ?></h5>
                        <ul>
                            <li><a href="#"><?= __('store.sell_products') ?: 'Sell products' ?></a></li>
                            <li><a href="#"><?= __('store.become_affiliate') ?: 'Become an Affiliate' ?></a></li>
                            <li><a href="#"><?= __('store.advertise') ?: 'Advertise' ?></a></li>
                            <li><a href="#"><?= __('store.self_publish') ?: 'Self-Publish' ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Column 3: Amazon Payment Products -->
                <div class="col-6 col-md-3">
                    <div class="amz-footer-col">
                        <h5><?= __('store.payment_products') ?: 'Payment Products' ?></h5>
                        <ul>
                            <li><a href="#"><?= __('store.business_card') ?: 'Business Card' ?></a></li>
                            <li><a href="#"><?= __('store.shop_points') ?: 'Shop with Points' ?></a></li>
                            <li><a href="#"><?= __('store.reload_balance') ?: 'Reload Your Balance' ?></a></li>
                            <li><a href="#"><?= __('store.currency_converter') ?: 'Currency Converter' ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Column 4: Let Us Help You -->
                <div class="col-6 col-md-3">
                    <div class="amz-footer-col">
                        <h5><?= __('store.let_us_help') ?: 'Let Us Help You' ?></h5>
                        <ul>
                            <li><a href="<?= $base_url ?>profile"><?= __('store.your_account') ?: 'Your Account' ?></a></li>
                            <li><a href="<?= $base_url ?>order"><?= __('store.your_orders') ?: 'Your Orders' ?></a></li>
                            <li><a href="#"><?= __('store.shipping_rates') ?: 'Shipping Rates' ?></a></li>
                            <li><a href="#"><?= __('store.returns') ?: 'Returns & Replacements' ?></a></li>
                            <li><a href="#"><?= __('store.help') ?: 'Help' ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="amz-footer-divider"></div>

            <!-- Footer Bottom -->
            <div class="amz-footer-bottom">
                <div class="amz-footer-logo">
                    <?php $footerLogo = ($store_setting['logo']) ? base_url('assets/images/site/'.$store_setting['logo']) : base_url('assets/store/default/').'img/logo.png'; ?>
                    <a href="<?= $home_link ?>">
                        <img src="<?= $footerLogo ?>" alt="<?= $store_setting['name'] ?>" height="24" onerror="this.onerror=null;this.src='<?= base_url('assets/store/default/img/logo.png') ?>'">
                    </a>
                </div>
                <div class="amz-footer-legal">
                    <a href="#"><?= __('store.conditions') ?: 'Conditions of Use' ?></a>
                    <a href="#"><?= __('store.privacy') ?: 'Privacy Notice' ?></a>
                    <a href="#"><?= __('store.interest_ads') ?: 'Interest-Based Ads' ?></a>
                </div>
                <div class="amz-footer-copyright">
                    &copy; <?= date('Y') ?> <?= $store_setting['name'] ?? __('store.all_rights_reserved') ?>
                </div>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->


    <!-- flash message -->
    <div class="print-message"><?php print_message($this); ?></div>
    <!-- flash message -->

    <!-- ═══ Amazon-Style Payment Footer ═══ -->
    <div class="amz-payment-footer">
      <div class="container">
        <div class="amz-payment-row">
          <?php 
          $payments = get_payment_gateways();
          foreach ($payments as $key => $payment) {
              if($payment['status']){
                  echo '<a href="javaScript:void(0);" class="amz-payment-icon" title="'. htmlspecialchars($payment['title']) .'"><img alt="'. htmlspecialchars($payment['title']) .'" src="'. base_url($payment['icon']) .'" width="50" height="30" onerror="this.onerror=null;this.style.display=\'none\'"/></a>';
              }
          }
          ?>
        </div>
      </div>
    </div>
    </div>
    <div style="display:none;">
        <a href="<?= base_url() ?>"><?= __('store.affiliate_pro') ?></a>
    </div>

<div class="modal fade" id="cart-confirm" tabindex="-1" aria-labelledby="cart-confirm" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="popup-content">
    <img src="<?= base_url('assets/store/default/'); ?>img/shopping-cart.png" class="pop-cart-img" alt="<?= __('store.icon') ?>">
    <h2 id="product-name-prev"></h2>
    <p><?= __('store.has_beent_added_to_your_cart') ?></p>
    <img src="<?= base_url('assets/store/default/'); ?>img/popline.png" class="img-fluid img-popline" alt="<?= __('store.icon') ?>">
    <div class="pop-btn-row">
      <a href="<?= $base_url ?>checkout" class="btn btn-poup" style="background: linear-gradient(to bottom, #FFD814, #FEBD69); color: #0F1111; border: 1px solid #A7ACB2; border-radius: 8px; padding: 10px 20px; font-weight: 700;"><?= __('store.procceed_to_checkout') ?></a>
      <a href="javascript:void(0);" type="button" class="btn btn-poup" style="background: linear-gradient(to bottom, #F7FAFA, #E7E9EA); color: #0F1111; border: 1px solid #A7ACB2; border-radius: 8px; padding: 10px 20px;" data-bs-dismiss="modal">
        <?= __('store.continue_shopping') ?>
      </a>
    </div>
    </div>
  </div>
  </div>
</div>

<?php if(!empty($cookies_consent) && $cookies_consent == 1){ ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
        // Check if the user has already given consent
        if (!localStorage.getItem("cookieConsent")) {
          // If not, show the popup
          document.getElementById("cookie-consent-popup").style.display = "flex";
        }
        // When the user clicks "Accept"
        document.getElementById("cookie-consent-accept").addEventListener("click", function () {
          localStorage.setItem("cookieConsent", "accepted");
           $("#cookie-consent-popup").remove();
          
        });
        // When the user clicks "Decline"
        document.getElementById("cookie-consent-decline").addEventListener("click", function () {
          localStorage.setItem("cookieConsent", "declined");
          $("#cookie-consent-popup").remove();
        });
      });
</script>
<?php }?>

<script type="text/javascript">
  /* flash message auto remove script */
  function removeAlertsAfterTimeout() {
    window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove(); 
      });
    }, 4000);
  }
  removeAlertsAfterTimeout();
  /* flash message auto remove script */
</script>

<script type="text/javascript">
    var _btnCartTooltip = null;
    function _disposeBtnCartTooltip() {
      if (_btnCartTooltip) { try { _btnCartTooltip.dispose(); } catch (e) {} _btnCartTooltip = null; }
    }
    function initBtnCartTooltip() {
      var el = document.querySelector('.btn-cart');
      if (!el || typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;
      _disposeBtnCartTooltip();
      _btnCartTooltip = new bootstrap.Tooltip(el, { trigger: 'click', placement: 'top' });
    }
    initBtnCartTooltip();

    function setTooltip(message) {
      var el = document.querySelector('.btn-cart');
      if (!el || typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;
      _disposeBtnCartTooltip();
      el.setAttribute('data-bs-title', message);
      _btnCartTooltip = new bootstrap.Tooltip(el, { trigger: 'manual', placement: 'top' });
      _btnCartTooltip.show();
    }

    function hideTooltip() {
      if (_btnCartTooltip) { try { _btnCartTooltip.hide(); } catch (e) {} }
    }


  $(function(){ 
    $(document).on('click', ".btn-cart", function(){
      let quantity = ($('input#product-quantity').length) ? $('input#product-quantity').val() : 1;
      let product_name = $(this).data('product_name');
      let product_id = $(this).data('product_id');
      $this = $(this);

      let variationNotSelected = [];
      let variationSelected = {};

      if($('.variation-row .variations').length != 0) {
        $('.variation-row .variations').each(function(){
          let type = $(this).find('span:first-child').data('variation-type');
          let optionSpan = $(this).find('.active');
          if(optionSpan.length) {
            variationSelected['price'] = optionSpan.data('variation-price');
            if(type == 'colors') {
              variationSelected[type] = optionSpan.data('variation-code')+"-"+optionSpan.data('variation-name');
            } else {
              variationSelected[type] = optionSpan.data('variation-option');
            }
          } else {
            variationNotSelected.push(type);
          }
        });
      }

      if(variationNotSelected.length){
        let warningMessage = '<?= __('store.please_select') ?>' + ' ';
        for (let index = 0; index < variationNotSelected.length; index++) {
          const element = variationNotSelected[index];
          warningMessage += (index == 0) ? element : ", "+element
        }
        setTooltip(warningMessage+' '+'<?= __('store.before_add_to_cart') ?>');
      } else {

        $.ajax({
            url: '<?= $add_tocart_url ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                quantity: quantity,
                product_id: product_id,
                variation: variationSelected,
            },
            beforeSend: function() {
                $this.btn("loading");
            },
            complete: function() {
                $this.btn("reset");
            },
            success: function(json) {
                if (json['location']) {
                    updateCart();
                    $('#cart-confirm #product-name-prev').text(product_name);
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                      bootstrap.Modal.getOrCreateInstance(document.getElementById('cart-confirm')).show();
                    }
                } else {
                    if (json['error']) {
                        $(".print-message").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            '<i class="bi bi-exclamation-triangle-fill me-2"></i>&nbsp;' + json['error'] +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        removeAlertsAfterTimeout();
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $(".print-message").html('<div class="alert alert-danger">An error occurred. Please try again later.</div>');
            }
        });

      }
    });

    $(document).on("click", ".cart-dropdown .btn-remove-cart", function(){
      $this = $(this);
      $.ajax({
          url:$this.attr("data-href"),
          type:'POST',
          dataType:'json',
          beforeSend:function(){},
          complete:function(){},
          success:function(json){
              updateCart();              
          },
      })
      return false;
    });

    $(document).on('click', ".cart-top", function(){
      $(".js-dropdown-list").hide();
      $(".js-dropdown-list1").hide();
      $(".js-dropdown-list2").hide();
      $(".cart-dropdown").slideToggle();
    });

    updateCart();
  });

  $(function(){
    $("#login-form input, #register-form input").focus(function(){
      if($(document).width() <= 408){
        $(".navbar-expand-lg,footer").hide();
      }
    });

    $("#login-form input, #register-form input").blur(function(){
      $(".navbar-expand-lg,footer").show();
    });
  });
  
  $(function(){
    function updateSymbol(e) {
      var selected = $(".currency-selector option:selected");
      $(".currency-symbol").text(selected.data("symbol"));
      $(".currency-amount").prop("placeholder", selected.data("placeholder"));
      $(".currency-addon-fixed").text(selected.text());
    }

    $(".currency-selector").on("change", updateSymbol);

    updateSymbol(); 
  });
  
  $(function () {
    var list = $(".js-dropdown-list");
    var link = $(".js-link");
    link.click(function (e) {
      e.preventDefault();
      $(".js-dropdown-list1").hide();
      $(".js-dropdown-list2").hide();
      $(".cart-dropdown").hide();
      list.slideToggle(200);
    });
    list.find("li").click(function () {
      var text = $(this).html();
      link.html(text);
      list.slideToggle(200);
      if (text === "* Reset") {
        link.html('<?= __('store.select_one_option') ?>' + icon);
      }
    });
  });

  $(function() {
    var list = $('.js-dropdown-list1');
    var link = $('.js-link1');
    link.click(function(e) {
        e.preventDefault();
        $(".js-dropdown-list").hide();
        $(".js-dropdown-list2").hide();
        $(".cart-dropdown").hide();
        list.slideToggle(200);
    });
    list.find('li').click(function() {
        var text = $(this).html();
        link.html(text);
        list.slideToggle(200);
        if (text === '* Reset') {
        link.html('<?= __('store.select_one_option') ?>'+icon);
        }
    });
  });

  $(function () {
    var list = $(".js-dropdown-list2");
    var link = $(".js-link2");
    link.click(function (e) {
      e.preventDefault();
      $(".js-dropdown-list1").hide();
      $(".js-dropdown-list").hide();
      $(".cart-dropdown").hide();
      list.slideToggle(200);
    });
  });
    
  window.onscroll = function() {
    let header = document.getElementById("myHeader");
    let sticky = header.offsetTop;
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }

    // Back to Top button
    var backToTop = document.getElementById("amzBackToTop");
    if (backToTop) {
        if (window.pageYOffset > 300) {
            backToTop.style.display = "block";
        } else {
            backToTop.style.display = "none";
        }
    }
  }

  // Account dropdown hover
  $(document).ready(function() {
    $('.amz-account .dropdown').on('mouseenter', function() {
        $(this).find('.amz-dropdown').stop(true, true).fadeIn(100);
    }).on('mouseleave', function() {
        $(this).find('.amz-dropdown').stop(true, true).fadeOut(100);
    });

    // Cart dropdown hover
    $('.amz-cart').on('mouseenter', function() {
        $('.amz-cart-dropdown').stop(true, true).fadeIn(100);
    }).on('mouseleave', function() {
        $('.amz-cart-dropdown').stop(true, true).fadeOut(100);
    });

    // Category dropdown hover
    $('.amz-secondary-links .dropdown').on('mouseenter', function() {
        $(this).find('.dropdown-menu').stop(true, true).fadeIn(100);
    }).on('mouseleave', function() {
        $(this).find('.dropdown-menu').stop(true, true).fadeOut(100);
    });
  });
</script>

<script type="text/javascript">
<?php 
$_notif2 = !empty($store_setting['notification']) ? json_decode($store_setting['notification']) : [];
if(is_array($_notif2) && count($_notif2) > 0) { 
?>
  $(document).ready(function() {
    var items = <?= $store_setting['notification']; ?>,
    $text = $('.top-bar .container'),
    delay = 2;

    var filtered = items.filter(function (el) {
      return (el != null && el != ""  );
    });

    if(filtered.length > 0) {
      filtered.push(filtered.shift());
      function loop ( delay ) {
          $.each(filtered, function ( i, elm ){
            $text.delay( delay*1E3).fadeOut();
            $text.queue(function(){
                $text.html('<img alt="'+'<?= __('store.image') ?>'+'" src="<?= base_url('assets/store/default/'); ?>img/top-icon.png" /> '+filtered[i]);
                $text.dequeue();
            });
            $text.fadeIn();
            $text.queue(function(){
                if ( i == filtered.length -1 ) {
                    loop(delay);   
                }
                $text.dequeue();
            });
          });
      }
      loop(delay);
    }
  });
  <?php } ?>

    function updateCart(){
      $.ajax({
          url:'<?= $base_url ?>/mini_cart',
          type:'POST',
          dataType:'json',
          beforeSend:function(){},
          complete:function(){},
          success:function(json){
              $(".cart-top .cart-dropdown").html(json['cart']);
              $(".cart-top .cart-count").html(json['total']);
              $('#cart-sub-total').text(json['sub_total']);
          },
      });
    }
</script>


<script>
    <?php if (!empty($affiliate_localstorage_init)): ?>
        var setLocalStorageAffiliateAjax = <?= $affiliate_localstorage_init ?>;
        setWithExpiry("affiliate_id", setLocalStorageAffiliateAjax[0], setLocalStorageAffiliateAjax[1]);
    <?php endif; ?>

    function setWithExpiry(key, value, ttl) {
    	const now = new Date()
    	const item = {
    		value: value,
    		expiry: now.getTime() + ttl,
    	}
    	localStorage.setItem(key, JSON.stringify(item))
    }
    
    function getWithExpiry(key) {
    	const itemStr = localStorage.getItem(key)
    
    	if (!itemStr) {
    		return 1
    	}
    
    	const item = JSON.parse(itemStr)
    	const now = new Date()
    
    	if (now.getTime() > item.expiry) {
    		localStorage.removeItem(key)
    		return 1
    	}
    	return item.value
    }
</script>

<!-- Accessibility: Lazy Loading for Images -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark lazy images as loaded when they finish loading
    var lazyImages = document.querySelectorAll('img[loading="lazy"]');
    lazyImages.forEach(function(img) {
        if (img.complete) {
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        }
    });

    // Add ARIA labels to icon-only buttons
    var iconButtons = document.querySelectorAll('button:not([aria-label])');
    iconButtons.forEach(function(btn) {
        var text = btn.textContent.trim();
        var icon = btn.querySelector('i, svg');
        if (icon && !text) {
            // Button has icon but no text - add aria-label based on nearby context
            var parent = btn.closest('a, div, form');
            if (parent) {
                var label = parent.getAttribute('aria-label') || parent.getAttribute('title') || 'Button';
                btn.setAttribute('aria-label', label);
            }
        }
    });

    // Form validation on blur
    var forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        var inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            input.addEventListener('input', function() {
                if (this.classList.contains('amz-input-error')) {
                    validateField(this);
                }
            });
        });
    });

    function validateField(field) {
        var isValid = field.checkValidity();
        var errorEl = document.getElementById('error-' + field.id);
        
        if (isValid) {
            field.classList.remove('amz-input-error');
            field.classList.add('amz-input-success');
            field.removeAttribute('aria-invalid');
            if (errorEl) {
                errorEl.style.display = 'none';
            }
        } else {
            field.classList.remove('amz-input-success');
            field.classList.add('amz-input-error');
            field.setAttribute('aria-invalid', 'true');
            if (errorEl) {
                errorEl.style.display = 'block';
            }
        }
    }
});
</script>

<?= $page_custom_script; ?>

<!-- V14 Store Module Enhancements -->
<script>
window.storeConfig = {
    base_url: '<?= base_url() ?>',
    store_url: '<?= base_url("store") ?>',
    currency: <?= json_encode($store_currency) ?>,
    social_proof: <?= (!empty($store_setting['social_proof_enabled'])) ? 'true' : 'false' ?>
};
</script>
<script src="<?= base_url('assets/store/shared/js/store.js') ?>?v=<?= av() ?>"></script>

<!-- ═══════════ ALPINE.JS COMPONENTS ═══════════ -->
<script>
// Initialize Tippy.js tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Wishlist button tooltips
    tippy('[data-tippy-content]', {
        placement: 'top',
        animation: 'fade',
        delay: [300, 0],
        theme: 'amz'
    });
    
    // Product card quick-view tooltips
    tippy('.amz-product-quick-view', {
        content: 'Quick View',
        placement: 'top',
        animation: 'fade'
    });
    
    // Share button tooltips
    tippy('.amz-share-btn', {
        content: 'Share this product',
        placement: 'top',
        animation: 'fade'
    });
    
    // Wishlist tooltip
    tippy('#btn-add-to-wishlist', {
        content: 'Add to Wishlist',
        placement: 'top',
        animation: 'fade'
    });
});
</script>

<!-- Tippy.js Custom Theme -->
<style>
.tippy-box[data-theme='amz'] {
    background: #131921;
    color: #fff;
    border-radius: 4px;
    font-size: 12px;
    padding: 6px 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.tippy-box[data-theme='amz'] .tippy-arrow {
    color: #131921;
}
/* Micro-interactions */
.amz-product-wrapper {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.amz-product-wrapper:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.amz-btn {
    transition: all 0.2s ease;
}
.amz-btn:active {
    transform: scale(0.97);
}
.amz-product-wrapper:hover .amz-product-img img {
    transform: scale(1.05);
}
.amz-product-img img {
    transition: transform 0.4s ease;
}
.amz-thumb {
    transition: border-color 0.2s ease, opacity 0.2s ease;
}
.amz-thumb:hover {
    border-color: var(--amz-orange);
}
.amz-cat-chip {
    transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
}
.amz-cat-chip:hover {
    transform: translateY(-2px);
}
/* Smooth scroll */
html {
    scroll-behavior: smooth;
}
/* Focus ring */
*:focus-visible {
    outline: 2px solid var(--amz-orange);
    outline-offset: 2px;
}
</style>
document.addEventListener('alpine:init', function() {
    // Cart dropdown component
    Alpine.data('amzCart', () => ({
        showCart: false,
        cartItems: [],
        cartCount: 0,
        init() {
            this.loadCart();
            window.addEventListener('cart-updated', () => this.loadCart());
        },
        loadCart() {
            try {
                const cart = JSON.parse(localStorage.getItem('amz_cart') || '[]');
                this.cartItems = cart;
                this.cartCount = cart.reduce((sum, item) => sum + (item.qty || 1), 0);
            } catch(e) {
                this.cartItems = [];
                this.cartCount = 0;
            }
        },
        removeItem(id) {
            this.cartItems = this.cartItems.filter(item => item.id !== id);
            this.cartCount = this.cartItems.reduce((sum, item) => sum + (item.qty || 1), 0);
            localStorage.setItem('amz_cart', JSON.stringify(this.cartItems));
        }
    }));
    
    // Product quantity stepper component
    Alpine.data('amzQty', () => ({
        qty: 1,
        min: 1,
        max: 999,
        disabled: false,
        inc() {
            if (this.qty < this.max) this.qty++;
            this.$dispatch('qty-change', { qty: this.qty });
        },
        dec() {
            if (this.qty > this.min) this.qty--;
            this.$dispatch('qty-change', { qty: this.qty });
        },
        validate() {
            let val = parseInt(this.qty) || 1;
            this.qty = Math.max(this.min, Math.min(this.max, val));
            this.$dispatch('qty-change', { qty: this.qty });
        }
    }));
    
    // Search with live suggestions
    Alpine.data('amzSearch', () => ({
        query: '',
        results: [],
        loading: false,
        showResults: false,
        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.showResults = false;
                return;
            }
            this.loading = true;
            try {
                const resp = await fetch('<?= base_url("store/api/v1/search") ?>?q=' + encodeURIComponent(this.query));
                const data = await resp.json();
                this.results = (data.products || []).slice(0, 5);
                this.showResults = true;
            } catch(e) {
                this.results = [];
            }
            this.loading = false;
        },
        close() {
            setTimeout(() => { this.showResults = false; }, 200);
        }
    }));
});
</script>

<?php
include __DIR__ . "/cookies_consent.php";
?>
<?php include APPPATH . 'views/store/includes/store_img_error_fallback.php'; ?>
</body>
</html>