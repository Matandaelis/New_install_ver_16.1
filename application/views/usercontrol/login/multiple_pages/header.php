<!doctype html>
<html lang="en">
<head>
<?php
    if($site_setting['google_analytics']){ echo $site_setting['google_analytics']; }
    if($site_setting['faceboook_pixel']){ echo $site_setting['faceboook_pixel']; }

    $logo = base_url('assets/login/multiple_pages/img/logo.png');
    $has_custom_logo = false;
    if (!empty($theme_settings[0]->logo)) {
        $logo = base_url('assets/images/theme_images/' . $theme_settings[0]->logo);
        $has_custom_logo = true;
    }
    if($site_setting['favicon']){
        echo '<link rel="icon" href="' . base_url('assets/images/site/' . $site_setting['favicon']) . '" type="image/*" sizes="16x16">';
    }

    $global_script_status = (array)json_decode($site_setting['global_script_status'],1);
    if(in_array('front', $global_script_status)){ echo $site_setting['global_script']; }

    $db =& get_instance();
    $products = $db->Product_model;
    $googlerecaptcha = $db->Product_model->getSettings('googlerecaptcha');
    $front_side_font = $products->getSettings('site','front_side_font');
    $front_side_font_value = $front_side_font['front_side_font'] ?? '';
    $cookies_menu_setting = $db->Product_model->getSettings('site');

    $front_header_color_before_scroll = $products->getFrontThemeSettings('theme','front_header_color_before_scroll');
    $front_header_color_after_scroll = $products->getFrontThemeSettings('theme','front_header_color_after_scroll');
    $front_footer_color = $products->getFrontThemeSettings('theme','front_footer_color');
    $bottom_banner_before_footer = $products->getFrontThemeSettings('theme','bottom_banner_before_footer');
    $front_button_color = $products->getFrontThemeSettings('theme','front_button_color');
    $front_button_hover_color = $products->getFrontThemeSettings('theme','front_button_hover_color');
    $front_button_text_color = $products->getFrontThemeSettings('theme','front_button_text_color');
    $front_header_button_color_before_scroll = $products->getFrontThemeSettings('theme','front_header_button_color_before_scroll');
    $front_header_button_color_after_scroll = $products->getFrontThemeSettings('theme','front_header_button_color_after_scroll');
    $front_header_button_text_color_before_scroll = $products->getFrontThemeSettings('theme','front_header_button_text_color_before_scroll');
    $front_header_button_text_color_after_scroll = $products->getFrontThemeSettings('theme','front_header_button_text_color_after_scroll');
    $front_header_button_hover_color_before_scroll = $products->getFrontThemeSettings('theme','front_header_button_hover_color_before_scroll');
    $front_header_button_hover_color_after_scroll = $products->getFrontThemeSettings('theme','front_header_button_hover_color_after_scroll');
    $front_runner_bar_color = $products->getFrontThemeSettings('theme','front_runner_bar_color');
    $front_runner_bar_text_color = $products->getFrontThemeSettings('theme','front_runner_bar_text_color');
    $front_theme_text_color = $products->getFrontThemeSettings('theme','front_theme_text_color');
    $front_faq_before_hover_color = $products->getFrontThemeSettings('theme','front_faq_before_hover_color');
    $front_faq_after_hover_color = $products->getFrontThemeSettings('theme','front_faq_after_hover_color');
    $header_menu_bg_color_responsive = $products->getFrontThemeSettings('theme','header_menu_bg_color_responsive');

    $LanguageHtml = $products->getLanguageHtml('usercontrol');

    // Fallback defaults — guarantee a valid CSS value even if DB row is missing / empty
    $c = function($arr, $key, $fallback) {
        return (!empty($arr[$key]) && $arr[$key] !== '') ? $arr[$key] : $fallback;
    };

    $val_header_before     = $c($front_header_color_before_scroll, 'front_header_color_before_scroll', 'transparent');
    $val_header_after      = $c($front_header_color_after_scroll, 'front_header_color_after_scroll', '#ffffff');
    $val_footer            = $c($front_footer_color, 'front_footer_color', '#0f172a');
    $val_btn               = $c($front_button_color, 'front_button_color', '#4361ee');
    $val_btn_hover         = $c($front_button_hover_color, 'front_button_hover_color', '#3a56d4');
    $val_btn_text          = $c($front_button_text_color, 'front_button_text_color', '#ffffff');
    $val_hbtn_before       = $c($front_header_button_color_before_scroll, 'front_header_button_color_before_scroll', '#4361ee');
    $val_hbtn_after        = $c($front_header_button_color_after_scroll, 'front_header_button_color_after_scroll', '#4361ee');
    $val_hbtn_txt_before   = $c($front_header_button_text_color_before_scroll, 'front_header_button_text_color_before_scroll', '#ffffff');
    $val_hbtn_txt_after    = $c($front_header_button_text_color_after_scroll, 'front_header_button_text_color_after_scroll', '#ffffff');
    $val_hbtn_hov_before   = $c($front_header_button_hover_color_before_scroll, 'front_header_button_hover_color_before_scroll', '#3a56d4');
    $val_hbtn_hov_after    = $c($front_header_button_hover_color_after_scroll, 'front_header_button_hover_color_after_scroll', '#f72585');
    $val_runner            = $c($front_runner_bar_color, 'front_runner_bar_color', '#4361ee');
    $val_runner_text       = $c($front_runner_bar_text_color, 'front_runner_bar_text_color', '#ffffff');
    $val_theme_text        = $c($front_theme_text_color, 'front_theme_text_color', '#4361ee');
    $val_faq_before        = $c($front_faq_before_hover_color, 'front_faq_before_hover_color', '#ffffff');
    $val_faq_after         = $c($front_faq_after_hover_color, 'front_faq_after_hover_color', '#4361ee');
    $val_bottom_banner     = $c($bottom_banner_before_footer, 'bottom_banner_before_footer', '#4361ee');
    $val_menu_bg_resp      = $c($header_menu_bg_color_responsive, 'header_menu_bg_color_responsive', '#0f172a');
?>

    <?= show_messenger_button($site_setting, 'front') ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $setting['heading'] ?></title>
    <meta name="author" content="<?= $meta_author ?>">
    <meta name="keywords" content="<?= $meta_keywords ?>">
    <meta name="description" content="<?= $meta_description ?>">
    <input type="hidden" id="theme_Name" value="<?= ($theme_name['front_template']); ?>">

    <meta property="og:url" content="<?= base_url() ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $title ?>" />
    <meta property="og:description" content="<?= $meta_description ?>" />
    <meta property="og:image" content="<?= $logo ?>" />

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/login/multiple_pages/css/font-awesome.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/login/multiple_pages/css/owl.carousel.min.css') ?>?v=<?= av() ?>">
    <link href="<?= base_url('assets/template/css/common.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/login/multiple_pages/css/style.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/login/multiple_pages/css/login-style.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/template/css/front-dark-mode-base.css') ?>?v=<?= av() ?>" rel="stylesheet">

    <!-- Dark mode: apply stored preference immediately to prevent flash -->
    <script>
    (function(){try{var t=localStorage.getItem('front_theme_mode');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-bs-theme',t);}catch(e){}})();
    </script>

    <!-- jQuery -->
    <script src="<?= base_url('assets/template/js/jquery.min.js') ?>?v=<?= av() ?>"></script>

    <?php
    $dynamic_css = '';
    if(!empty($theme_settings[0]) && $theme_settings[0]->custom_logo_size && (int)$theme_settings[0]->log_custom_width > 0 && (int)$theme_settings[0]->log_custom_height > 0) {
        $dynamic_css .= '.customLogoClass{width:' . (int)$theme_settings[0]->log_custom_width . 'px!important;height:' . (int)$theme_settings[0]->log_custom_height . 'px!important;max-width:none!important;max-height:none!important;object-fit:contain!important;}';
    }
    if($front_side_font_value) {
        $dynamic_css .= 'body,h1,h2,h3,h4,h5,h6,button,input,optgroup,select,textarea{font-family:' . $front_side_font_value . '!important;}';
    }
    ?>

    <style>
        <?= $dynamic_css ?>

        /* ===== Header / Navbar ===== */
        .mp-navbar {
            background: <?= $val_header_before ?> !important;
        }
        .mp-navbar.stick {
            background: <?= $val_header_after ?> !important;
            box-shadow: 0 4px 30px rgba(0,0,0,0.12), 0 1px 6px rgba(0,0,0,0.06);
            border-bottom: 1px solid rgba(0,0,0,0.08);
            padding: 0.5rem 0;
        }
        .mp-navbar.stick .navbar-nav .nav-item .nav-link {
            color: #1e293b !important;
            background: transparent !important;
            backdrop-filter: none !important;
        }
        .mp-navbar.stick .navbar-nav .nav-item .nav-link:hover {
            color: <?= $val_btn ?> !important;
            background: rgba(67,97,238,0.08) !important;
        }
        .mp-navbar.stick .navbar-nav .nav-item.active .nav-link {
            color: <?= $val_btn ?> !important;
            background: rgba(67,97,238,0.1) !important;
        }
        .mp-navbar.stick .navbar-brand img {
            filter: <?= $has_custom_logo ? 'none' : 'brightness(0)' ?> !important;
        }
        .mp-navbar.stick .navbar-toggler {
            border-color: rgba(0,0,0,0.2) !important;
        }
        .mp-navbar.stick .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }
        .mp-navbar.stick .dropdown-toggle::after {
            color: #1e293b !important;
        }
        .mp-navbar.stick .dropdown-menu {
            background: #fff !important;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .mp-navbar.stick .dropdown-menu .dropdown-item {
            color: #1e293b !important;
        }
        .mp-navbar.stick .dropdown-menu .dropdown-item:hover {
            color: <?= $val_btn ?> !important;
            background: rgba(67,97,238,0.06) !important;
        }

        /* ===== Header Login Button ===== */
        .mp-navbar .mp-btn-login {
            background: <?= $val_hbtn_before ?> !important;
            color: <?= $val_hbtn_txt_before ?> !important;
        }
        .mp-navbar .mp-btn-login:hover {
            background: <?= $val_hbtn_hov_before ?> !important;
        }
        .mp-navbar.stick .mp-btn-login {
            background: <?= $val_hbtn_after ?> !important;
            color: <?= $val_hbtn_txt_after ?> !important;
        }
        .mp-navbar.stick .mp-btn-login:hover {
            background: <?= $val_hbtn_hov_after ?> !important;
        }

        /* ===== Footer ===== */
        .mp-footer {
            background: <?= $val_footer ?> !important;
        }

        /* ===== Buttons ===== */
        .front_button_color {
            background: <?= $val_btn ?> !important;
        }
        .front_button_hover_color:hover {
            background: <?= $val_btn_hover ?> !important;
        }
        .front_button_text_color {
            color: <?= $val_btn_text ?> !important;
        }

        /* ===== Runner / Ticker ===== */
        .mp-ticker {
            background: <?= $val_runner ?> !important;
        }
        .mp-ticker-inner p {
            color: <?= $val_runner_text ?> !important;
        }

        /* ===== Theme Text / Accents ===== */
        .front_theme_text_color {
            color: <?= $val_theme_text ?> !important;
        }
        .mp-featured-slider .owl-dots button.owl-dot.active {
            background: <?= $val_theme_text ?> !important;
        }
        .mp-testimonial-card::before {
            background: <?= $val_theme_text ?> !important;
        }

        /* ===== FAQ ===== */
        .mp-faq-accordion .card .card-header h2 button[aria-expanded="true"],
        .mp-faq-accordion .card .card-header h2 button:hover {
            background: <?= $val_faq_after ?> !important;
        }

        /* ===== Auth Forms ===== */
        .mp-auth-form.register-form .checkbox input[type="checkbox"]:checked + .box:after {
            background-color: <?= $val_btn ?> !important;
            border-color: <?= $val_btn ?> !important;
        }

        /* ===== CTA / Banner ===== */
        .mp-cta::before {
            background: <?= $val_bottom_banner ?> !important;
        }

        /* ===== Responsive ===== */
        @media (max-width: 991px) {
            .mp-navbar .collapse.show,
            .mp-navbar .collapsing {
                background: <?= $val_menu_bg_resp ?> !important;
            }
        }
    </style>

    <script>
    /* Sticky navbar — nuclear approach: multiple listeners + inline styles */
    (function(){
        var _navbar = null, _innerNav = null, _stuck = false;

        function getScrollY() {
            return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        }

        function isDark() {
            return document.documentElement.getAttribute('data-bs-theme') === 'dark';
        }

        function applyStick() {
            if (_stuck) return;
            _stuck = true;
            if (!_navbar) return;
            _navbar.classList.add('stick');
            var dark = isDark();
            _navbar.style.background = dark ? '#161926' : '<?= $val_header_after ?>';
            _navbar.style.boxShadow = dark ? '0 4px 30px rgba(0,0,0,0.3)' : '0 4px 30px rgba(0,0,0,0.12), 0 1px 6px rgba(0,0,0,0.06)';
            _navbar.style.borderBottom = dark ? '1px solid rgba(255,255,255,0.06)' : '1px solid rgba(0,0,0,0.08)';
            _navbar.style.padding = '0.5rem 0';
            if (_innerNav) {
                if (dark) {
                    _innerNav.classList.add('navbar-dark');
                    _innerNav.classList.remove('navbar-light');
                } else {
                    _innerNav.classList.remove('navbar-dark');
                    _innerNav.classList.add('navbar-light');
                }
            }
            var links = _navbar.querySelectorAll('.navbar-nav .nav-item .nav-link');
            for (var i = 0; i < links.length; i++) {
                links[i].style.color = dark ? '#e2e8f0' : '#1e293b';
            }
            var logoImg = _navbar.querySelector('.navbar-brand img');
            if (logoImg) {
                if (dark) {
                    logoImg.style.filter = 'brightness(0) invert(1)';
                } else {
                    logoImg.style.filter = '<?= $has_custom_logo ? "none" : "brightness(0)" ?>';
                }
            }
        }

        function removeStick() {
            if (!_stuck) return;
            _stuck = false;
            if (!_navbar) return;
            _navbar.classList.remove('stick');
            _navbar.style.background = '<?= $val_header_before ?>';
            _navbar.style.boxShadow = 'none';
            _navbar.style.borderBottom = 'none';
            _navbar.style.padding = '';
            if (_innerNav) {
                _innerNav.classList.remove('navbar-light');
                _innerNav.classList.add('navbar-dark');
            }
            var links = _navbar.querySelectorAll('.navbar-nav .nav-item .nav-link');
            for (var i = 0; i < links.length; i++) {
                links[i].style.color = '';
            }
            var logoImg = _navbar.querySelector('.navbar-brand img');
            if (logoImg) logoImg.style.filter = '';
        }

        function checkScroll() {
            if (!_navbar) {
                _navbar = document.querySelector('.mp-navbar');
                if (_navbar) _innerNav = _navbar.querySelector('nav.navbar');
            }
            if (!_navbar) return;
            if (getScrollY() > 100) { applyStick(); } else { removeStick(); }
        }

        /* Method 1: window scroll */
        window.addEventListener('scroll', checkScroll, { passive: true });
        /* Method 2: document scroll (some browsers) */
        document.addEventListener('scroll', checkScroll, { passive: true });
        /* Method 3: fallback polling every 150ms — catches ALL scroll containers */
        setInterval(checkScroll, 150);
        /* Method 4: run on DOMContentLoaded in case page loads already scrolled */
        document.addEventListener('DOMContentLoaded', checkScroll);
    })();
    </script>
    <?= render_js_error_reporter() ?>
</head>

<body>

<?php
    $fbmessager_status = (array)json_decode($site_setting['fbmessager_status'],1);
    if(in_array('front', $fbmessager_status)){
        echo $site_setting['fbmessager_script'];
    }
?>

<?php if( current_url() != site_url('/login') && current_url() != site_url('/register') && current_url() != site_url('/register/vendor') && current_url() != site_url('/forget-password') && current_url() != site_url('/terms-of-use')){ ?>

    <header class="mp-navbar">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="<?= base_url('/') ?>">
                    <img src="<?= $logo ?>" <?= (!empty($theme_settings[0]) && $theme_settings[0]->custom_logo_size && (int)$theme_settings[0]->log_custom_width > 0 && (int)$theme_settings[0]->log_custom_height > 0) ? 'class="customLogoClass"' : '' ?> alt="<?= $setting['heading'] ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/login/multiple_pages/img/logo.png') ?>'">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mpNavbar" aria-controls="mpNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mpNavbar">
                    <ul class="navbar-nav ms-auto">

                        <?php
                        if (isset($header_menus) && !empty($header_menus)) {
                            foreach ($header_menus as $key => $menu) {
                        ?>

                        <?php if (empty($menu['parent_id'])==true && $menu['is_header_dropdown']==1) { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown-menu-<?= $menu['page_id'] ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?=$menu['page_type']=='editable' ? $menu['page_name'] : __('front.'.$menu['page_name']) ?>
                                </a>

                                <?php
                                    $parentSlug = $menu['page_id'];
                                    $dropdowns = array_filter($header_menus, function ($menu) use ($parentSlug) {
                                        return ($menu['parent_id'] == $parentSlug);
                                    });
                                    if (isset($dropdowns) && !empty($dropdowns)) {
                                ?>
                                <ul class="dropdown-menu" aria-labelledby="dropdown-menu-<?= $menu['page_id'] ?>">
                                    <?php foreach ($dropdowns as $key => $dropdown) { ?>
                                    <li><a class="dropdown-item" href="<?= site_url($dropdown['page_type']=='editable' ? 'p/'.$dropdown['slug'] : $dropdown['slug']) ?>"><?= $dropdown['page_type']=='editable' ? $dropdown['page_name'] : __('front.'.$dropdown['page_name']) ?></a></li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </li>

                        <?php }else if (empty($menu['parent_id'])==true){ ?>
                            <li class="nav-item <?php if(site_url(uri_string()) == site_url($menu['slug'])){ echo 'active'; } ?>">
                                <a class="nav-link" href="<?= site_url($menu['page_type']=='editable' ? 'p/'.$menu['slug'] : $menu['slug']) ?>"><?=$menu['page_type']=='editable' ? $menu['page_name'] : __('front.'.$menu['page_name']) ?></a>
                            </li>
                        <?php }}} ?>

                        <?php
                        $store_setting = $this->Product_model->getSettings('store');
                        if($store_setting['menu_on_front']){
                        ?>
                        <li class="nav-item <?php if(base_url(uri_string()) == base_url('/store')){ echo 'active'; } ?>">
                            <a class="nav-link" href="<?= base_url('/store') ?>" <?= ($store_setting['menu_on_front_blank']) ? 'target="_blank"' : ''; ?>><?= __('front.my_store') ?></a>
                        </li>
                        <?php } ?>

                        <?php if($store['language_status']){ ?>
                        <li class="nav-item dropdown">
                            <?= $LanguageHtml ?>
                        </li>
                        <?php } ?>

                    </ul>
                    <button class="front-theme-toggle me-2 my-2 my-lg-0" title="<?= __('front.toggle_dark_mode') ?? 'Toggle dark mode' ?>">
                        <i class="bi bi-moon-fill"></i>
                        <i class="bi bi-sun-fill" style="display:none"></i>
                    </button>
                    <a href="<?= base_url('/login') ?>" class="mp-btn-login my-2 my-lg-0">
                        <i class="bi bi-box-arrow-in-right"></i> <?= __('front.log_in') ?>
                    </a>
                </div>
            </nav>
        </div>
    </header>

<?php } ?>
