<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<?php
$terms_content = $theme_settings[0]->terms_content;
$terms_img = $theme_settings[0]->terms_img;

if ($terms_img != '' || !empty($terms_img)) {
    $image_link = base_url('assets/images/theme_images/'.$terms_img);
} else {
    $image_link = base_url('assets/login/multiple_pages/img/term-bg.png');
}
?>

<a href="<?= base_url('/'); ?>" class="mp-back-home front_button_color front_button_hover_color front_button_text_color">
    <i class="bi bi-arrow-left"></i> <?= __('front.back_to_homepage') ?>
</a>

<div class="mp-terms-area" style="background: url('<?= $image_link; ?>') no-repeat center center / cover;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="mp-terms-content" id="scrollbar">
                    <h2 class="text-white mb-4"><?= __('front.terms_of_use') ?></h2>
                    <p><?= (!empty($terms_content)) ? nl2br($terms_content) : __('front.terms_content_if_not_exist');?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>
