<?php
include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php');
$login_content = $theme_settings[0]->login_content;
$login_img = $theme_settings[0]->login_img;

if ($login_img != '' || !empty($login_img)) {
    $image_link = base_url('assets/images/theme_images/'.$login_img);
} else {
    $image_link = base_url('assets/login/multiple_pages/img/login-bg.jpg');
}
?>

<?= $hook_floating_pulse ?? '' ?>

<a href="<?= base_url('/'); ?>" class="mp-back-home front_button_color front_button_hover_color front_button_text_color">
    <i class="bi bi-arrow-left"></i> <?= __('front.back_to_homepage') ?>
</a>

<div class="mp-auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Left Banner -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="mp-auth-banner" style="background: url('<?= $image_link; ?>');">
                    <div class="mp-auth-shape mp-auth-shape-1"></div>
                    <div class="mp-auth-shape mp-auth-shape-2"></div>
                    <div class="mp-auth-shape mp-auth-shape-3"></div>
                    <div class="mp-auth-banner-content">
                        <?php if(isset($store['registration_status']) && $store['registration_status']==0) {}
                        else if( ($vendor_marketstatus["marketvendorstatus"]==1 || $vendor_storestatus['storestatus']) && $store['registration_status']!=3 ) { ?>
                            <i class="bi bi-person-plus front_theme_text_color"></i>
                            <h1><?= __('front.dont_have_an_account_yet') ?></h1>
                            <a class="mp-auth-banner-btn front_button_color front_button_hover_color front_button_text_color" href="<?= site_url('/register') ?>">
                                <?= __('front.sign_up_new') ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php } else if($store['registration_status']!=2){ ?>
                            <i class="bi bi-person-plus front_theme_text_color"></i>
                            <h1><?= __('front.dont_have_an_account_yet') ?></h1>
                            <a class="mp-auth-banner-btn front_button_color front_button_hover_color front_button_text_color" href="<?= site_url('/register') ?>">
                                <?= __('front.sign_up_new') ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php } ?>

                        <?php if(!empty($login_content)) { ?>
                        <div class="mp-auth-description">
                            <p><?= nl2br($login_content) ?></p>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Right Form -->
            <div class="col-lg-6">
                <div class="mp-auth-form-panel">
                    <div class="mp-auth-form-inner">
                        <div class="mp-auth-form-wrap">
                            <div class="mp-auth-logo">
                                <a href="<?= base_url() ?>">
                                    <img src="<?= $logo ?>" <?= (!empty($theme_settings[0]) && $theme_settings[0]->custom_logo_size && (int)$theme_settings[0]->log_custom_width > 0 && (int)$theme_settings[0]->log_custom_height > 0) ? 'class="customLogoClass"' : '' ?> alt="<?= $setting['heading'] ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/login/multiple_pages/img/logo.png') ?>'">
                                </a>
                            </div>

                            <div class="mp-auth-title">
                                <h3><?= __('front.login') ?></h3>
                                <p><?= __('front.enter_your_credentials') ?></p>
                            </div>

                            <!-- #login-form, #login_button, affiliate_login reCAPTCHA — handleFormWithRecaptcha (utility_helper). -->
                            <?php $this->load->view('usercontrol/login/components/login_form_component', [
                                'login_theme_id' => 14,
                            ]); ?>
                            <?= $hook_form_bottom ?? '' ?>

                            <div class="text-center">
                                <a href="<?= base_url('/forget-password') ?>"><?= __('front.forgot_your_password') ?></a>
                            </div>

                            <?php if(!empty($login_content)) { ?>
                            <div class="mp-auth-form-description d-lg-none">
                                <p><?= nl2br($login_content) ?></p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $hook_page_footer ?? '' ?>

<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>
