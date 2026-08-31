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

    <!-- Theme 13: Glass Pro CSS -->
    <link href="<?= base_url('assets/login/index13/css/style.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/template/css/front-dark-mode-base.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <script>(function(){try{var t=localStorage.getItem('front_theme_mode');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-bs-theme',t);}catch(e){}})();</script>

    <!-- jQuery -->
    <script src="<?= base_url('assets/template/js/jquery.min.js') ?>?v=<?= av() ?>"></script>

    <?php if (is_rtl()) { ?>
        <link href="<?= base_url('assets/login/index13/css/rtl.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <?php } ?>

    <?php
    /* Dynamic CSS: admin-configured logo size + custom font (requires PHP values) */
    $dynamic_css = '';
    if($SiteSetting['front_custom_logo_size'] && (int)$SiteSetting['front_log_custom_width'] > 0 && (int)$SiteSetting['front_log_custom_height'] > 0) {
        $dynamic_css .= '.customLogoClass{width:' . (int)$SiteSetting['front_log_custom_width'] . 'px!important;height:' . (int)$SiteSetting['front_log_custom_height'] . 'px!important;max-width:none!important;max-height:none!important;object-fit:contain!important;}';
    }
    if($front_side_font_value) {
        $dynamic_css .= 'body,.glass-card,.glass-card *{font-family:' . $front_side_font_value . '!important;}';
    }
    if($dynamic_css) {
        echo '<style>' . $dynamic_css . '</style>';
    }
    ?>
    <?= render_js_error_reporter() ?>
</head>

<body class="glass-pro-body">

<!-- Animated Background Shapes -->
<div class="bg-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>
    <div class="shape shape-5"></div>
</div>

<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg glass-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>">
            <img src="<?= base_url($logo) ?>"
                 <?= ($SiteSetting['front_custom_logo_size']) ? 'class="customLogoClass me-2"' : 'class="me-2"' ?>
                 alt="<?= __('front.logo') ?>">
        </a>

        <div class="d-flex align-items-center gap-2 ms-auto">
            <?php if($store['language_status']){ ?>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <?= $LanguageHtml ?>
                </li>
            </ul>
            <?php } ?>
            <button class="front-theme-toggle" title="Toggle dark mode">
                <i class="bi bi-moon-fill"></i>
                <i class="bi bi-sun-fill" style="display:none"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Main Content Wrapper -->
<div class="glass-pro-wrapper">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center">

            <!-- Left Panel: hero + trust + stats + CTA -->
            <div class="col-12 col-lg-7 d-flex left-panel">
                <div class="left-panel-content text-center text-white">
                    <div class="brand-icon-wrap mb-4">
                        <i class="bi bi-shield-lock-fill brand-icon"></i>
                    </div>
                    <h1 class="display-5 fw-bold mb-2" style="color:#fff"><?= $setting['heading'] ?? '' ?></h1>
