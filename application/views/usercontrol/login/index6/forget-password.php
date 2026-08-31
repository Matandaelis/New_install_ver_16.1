<?php
include_once "header.php";
?>

<div class="idx6-form-pane">
    <div class="idx6-form-wrapper">
        <div class="idx6-header-icon idx6-stagger-1">
            <i class="bi bi-envelope-paper-fill"></i>
        </div>
        <h1 class="idx6-title idx6-stagger-2"><?= __('front.reset_password') ?></h1>
        <p class="idx6-subtitle mb-4 idx6-stagger-3">
            <?= __('front.email_sent_instructions') ?>
        </p>

        <form class="reset-password-form" id="reset-password-form" autocomplete="off">
            <div class="mb-3 idx6-stagger-3">
                <div class="form-floating idx6-input-group">
                    <input required class="form-control idx6-input" name="email" id="idx6-email" type="email" placeholder="<?= __('front.email') ?>">
                    <label for="idx6-email"><i class="bi bi-envelope me-1"></i> <?= __('front.email') ?></label>
                </div>
            </div>

            <div class="idx6-info-banner mb-3 idx6-stagger-4">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle mt-1"></i>
                    <p><?= __('front.email_sent_instructions') ?></p>
                </div>
            </div>

            <?= render_recaptcha_html('affiliate_forgot') ?>

            <div class="d-grid gap-2 idx6-stagger-5">
                <button class="btn idx6-btn-primary py-3 btn-submit">
                    <i class="bi bi-send me-2"></i><?= __('front.send_reset_link') ?>
                </button>
                <button type="button" onclick="window.location='<?= base_url() ?>'" class="btn idx6-btn-outline py-2">
                    <i class="bi bi-arrow-left me-2"></i><?= __('front.back_to_login') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
include_once "footer.php";
?>
