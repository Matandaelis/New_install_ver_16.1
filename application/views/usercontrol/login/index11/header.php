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

    <meta property="og:url" content="<?= base_url() ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $title ?>" />
    <meta property="og:description" content="<?= $meta_description ?>" />
    <meta property="og:image" content="<?= base_url($logo) ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-toggle.min.css') ?>?v=<?= av() ?>">
    <link href="<?= base_url('assets/template/css/common.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/login/index11/css/style.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/template/css/front-dark-mode-base.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <script>(function(){try{var t=localStorage.getItem('front_theme_mode');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-bs-theme',t);}catch(e){}})();</script>

    <?php if (is_rtl()) { ?>
    <link href="<?= base_url('assets/login/index11/css/rtl.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <?php } ?>

    <script src="<?= base_url('assets/template/js/jquery.min.js') ?>?v=<?= av() ?>"></script>

    <style>
        img[alt="<?= __('front.logo') ?>"] {
            max-width: 200px !important; max-height: 60px !important;
            height: auto !important; object-fit: contain !important;
        }
        <?php if($SiteSetting['front_custom_logo_size'] && (int)$SiteSetting['front_log_custom_width'] > 0 && (int)$SiteSetting['front_log_custom_height'] > 0): ?>
        .customLogoClass{
            width: <?= (int) $SiteSetting['front_log_custom_width'] ?>px !important;
            height: <?= (int) $SiteSetting['front_log_custom_height'] ?>px !important;
            max-width: none !important; max-height: none !important; object-fit: contain !important;
        }
        <?php endif ?>
        <?php if(!empty($front_side_font_value)): ?>
        body, .idx11-title { font-family: <?= $front_side_font_value ?> !important; }
        <?php endif ?>
    </style>
    <?= render_js_error_reporter() ?>
</head>

<body>
<div class="idx11-wrapper">
    <div class="idx11-topbar">
        <div class="idx11-logo">
            <a href="<?= base_url() ?>">
                <img src="<?= base_url($logo) ?>" <?= ($SiteSetting['front_custom_logo_size']) ? 'class="customLogoClass"' : '' ?> alt="<?= __('front.logo') ?>">
            </a>
        </div>
        <div class="d-flex align-items-center">
            <?php if($store['language_status']){ ?>
                <div class="language-changer me-3"><?= $LanguageHtml ?></div>
            <?php } ?>
            <button class="front-theme-toggle" title="Toggle dark mode">
                <i class="bi bi-moon-fill"></i>
                <i class="bi bi-sun-fill" style="display:none"></i>
            </button>
        </div>
    </div>

    <div class="idx11-content">
