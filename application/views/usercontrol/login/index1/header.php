<!doctype html>
<html lang="en">
<head>
<?php
    if($SiteSetting['google_analytics']){ echo $SiteSetting['google_analytics']; }
    if($SiteSetting['faceboook_pixel']){ echo $SiteSetting['faceboook_pixel']; }

    $logo = $SiteSetting['front-side-themes-logo'] ? 'assets/images/site/'.$SiteSetting['front-side-themes-logo'] : 'assets/login/index1/img/logo.png';
    echo '<link rel="icon" href="'. base_url('assets/images/' . ($SiteSetting['favicon'] ? 'site/'.$SiteSetting['favicon'] : 'fav.png')) .'" type="image/*" sizes="16x16">';

    $global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
    if(in_array('front', $global_script_status)){ echo $SiteSetting['global_script']; }
    $db =& get_instance();
    $products = $db->Product_model;
    $googlerecaptcha = $db->Product_model->getSettings('googlerecaptcha');
    $front_side_font = $db->Product_model->getSettings('site','front_side_font');
    $front_side_font_value = $front_side_font['front_side_font'] ?? '';
    $cookies_menu_setting = $db->Product_model->getSettings('site');
?>

    <?= show_messenger_button($SiteSetting, 'front') ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?></title>
    <meta name="author" content="<?= $meta_author ?>">
    <meta name="keywords" content="<?= $meta_keywords ?>">
    <meta name="description" content="<?= $meta_description ?>">
    <input type="hidden" id="theme_Name" value="<?= ($login['front_template']); ?>">

    <!-- Open Graph tags -->
    <meta property="og:url" content="<?= base_url() ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $title ?>" />
    <meta property="og:description" content="<?= $meta_description ?>" />
    <meta property="og:image" content="<?= base_url($logo) ?>" />

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-toggle.min.css') ?>?v=<?= av() ?>">
    <link href="<?= base_url('assets/template/css/common.css') ?>?v=<?= av() ?>" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="<?= base_url('assets/login/index1/css/style.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/template/css/front-dark-mode-base.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <script>(function(){try{var t=localStorage.getItem('front_theme_mode');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-bs-theme',t);}catch(e){}})();</script>

    <!-- jQuery -->
    <script src="<?= base_url('assets/template/js/jquery.min.js') ?>?v=<?= av() ?>"></script>

    <?php if (is_rtl()) { ?>
        <link href="<?= base_url('assets/login/index1/css/rtl.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <?php } ?>

    <?php
    /* Dynamic CSS: admin-configured logo size + custom font (requires PHP values) */
    $dynamic_css = '';
    if($SiteSetting['front_custom_logo_size'] && (int)$SiteSetting['front_log_custom_width'] > 0 && (int)$SiteSetting['front_log_custom_height'] > 0) {
        $dynamic_css .= '.customLogoClass{width:' . (int)$SiteSetting['front_log_custom_width'] . 'px!important;height:' . (int)$SiteSetting['front_log_custom_height'] . 'px!important;max-width:none!important;max-height:none!important;object-fit:contain!important;}';
    }
    if($front_side_font_value) {
        $dynamic_css .= 'body,.idx1-card,.idx1-card *{font-family:' . $front_side_font_value . '!important;}';
    }
    if($dynamic_css) {
        echo '<style>' . $dynamic_css . '</style>';
    }
    ?>
    <?= render_js_error_reporter() ?>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg idx1-navbar fixed-top">
            <div class="container">
                <a class="navbar-brand" href="<?= base_url() ?>">
                    <img src="<?= base_url($logo) ?>"
                         <?= ($SiteSetting['front_custom_logo_size']) ? 'class="customLogoClass"' : '' ?>
                         alt="<?= __('front.logo') ?>">
                </a>
                <div class="ms-auto d-flex align-items-center">
                    <button type="button"
                            class="btn idx1-navbar-auth-btn me-2"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#idx1AuthOffcanvas"
                            aria-controls="idx1AuthOffcanvas">
                        <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                        <span class="d-none d-sm-inline"><?= __('front.idx1_nav_sign_in_register') ?></span>
                    </button>
                    <?php if($store['language_status']){ ?>
                    <div class="language-changer me-3"><?= $LanguageHtml ?></div>
                    <?php } ?>
                    <button class="front-theme-toggle" title="Toggle dark mode">
                        <i class="bi bi-moon-fill"></i>
                        <i class="bi bi-sun-fill" style="display:none"></i>
                    </button>
                </div>
            </div>
        </nav>
    </header>
