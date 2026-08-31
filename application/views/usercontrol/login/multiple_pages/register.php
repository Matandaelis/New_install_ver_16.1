<?php
include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php');
$reg_content = $theme_settings[0]->reg_content;
$reg_img = $theme_settings[0]->reg_img;

if ($reg_img != '' || !empty($reg_img)) {
    $image_link = base_url('assets/images/theme_images/'.$reg_img);
} else {
    $image_link = base_url('assets/login/multiple_pages/img/register-bg.png');
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
                        <i class="bi bi-person-check front_theme_text_color"></i>
                        <h1><?= __('front.i_am_already_a_member') ?></h1>
                        <a class="mp-auth-banner-btn front_button_color front_button_hover_color front_button_text_color" href="<?= site_url('/login') ?>">
                            <?= __('front.log_in') ?> <i class="bi bi-arrow-right"></i>
                        </a>

                        <?php if(!empty($reg_content)) { ?>
                        <div class="mp-auth-description">
                            <p><?= nl2br($reg_content) ?></p>
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
                                    <img src="<?= $logo ?>" <?= (!empty($theme_settings[0]) && $theme_settings[0]->custom_logo_size && (int)$theme_settings[0]->log_custom_width > 0 && (int)$theme_settings[0]->log_custom_height > 0) ? 'class="customLogoClass"' : '' ?> alt="<?= $setting['heading'] ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/login/multiple_pages/img/logo.png') ?>'"  >
                                </a>
                            </div>

                            <!-- Dynamic builder outputs .reg_form; AJAX unchanged (pagebuilder/register). -->
                            <div class="mp-auth-form register-form">
                                <?php $this->load->view('usercontrol/login/components/register_form_component', [
                                    'store' => $store,
                                    'vendor_storestatus' => $vendor_storestatus,
                                    'vendor_marketstatus' => $vendor_marketstatus,
                                    'register_fomm' => isset($register_fomm) ? $register_fomm : '',
                                    'register_component_variant' => 'minimal',
                                ]); ?>
                                <?= $hook_form_bottom ?? '' ?>
                            </div>

                            <?php if(!empty($reg_content)) { ?>
                            <div class="mp-auth-form-description d-lg-none">
                                <p><?= nl2br($reg_content) ?></p>
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
<script type="text/javascript">
    (function ($) {
        $.fn.btn = function (action) {
            var self = $(this);
            if (action == 'loading') { $(self).addClass("btn-loading"); }
            if (action == 'reset') { $(self).removeClass("btn-loading"); }
        }
    })(jQuery);
</script>
