<?php
include_once "header.php";
?>

            <div class="idx10-form-wrapper">
                <div class="idx10-header-icon idx10-stagger-1">
                    <i class="bi bi-key-fill"></i>
                </div>
                <h1 class="idx10-title idx10-stagger-2"><?= __('front.forget_password') ?></h1>
                <p class="idx10-subtitle mb-4 idx10-stagger-2"><?= __('front.enter_your_email_to_reset_password') ?></p>

                <form id="reset-password-form" class="reset-password-form" autocomplete="off">
                    <div class="mb-3 idx10-stagger-3">
                        <div class="form-floating idx10-input-group">
                            <input required class="form-control idx10-input" type="email" name="email" id="idx10-email" placeholder="<?= __('front.email_address') ?>">
                            <label for="idx10-email"><i class="bi bi-envelope me-1"></i> <?= __('front.email_address') ?></label>
                        </div>
                    </div>

                    <?= render_recaptcha_html('affiliate_forgot') ?>

                    <div class="idx10-info-banner mb-3 idx10-stagger-4">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle flex-shrink-0 mt-1"></i>
                            <p><?= __('front.forget_password_info') ?></p>
                        </div>
                    </div>

                    <div class="d-grid gap-2 idx10-stagger-5">
                        <button class="btn idx10-btn-primary py-3 btn-submit btn_sendmail_bg">
                            <i class="bi bi-send me-2"></i><?= __('front.send_mail') ?>
                        </button>
                        <a href="<?= base_url() ?>" class="btn idx10-btn-outline py-2">
                            <i class="bi bi-arrow-left me-1"></i> <?= __('front.back_to_login') ?>
                        </a>
                    </div>
                </form>
            </div>

<?php
include_once "footer.php";
?>
