<?php
include_once "header.php";
?>

        <div class="idx11-card">
            <div class="idx11-image-side">
                <div class="idx11-deco-shapes">
                    <div class="idx11-deco-circle idx11-deco-c1"></div>
                    <div class="idx11-deco-circle idx11-deco-c2"></div>
                    <div class="idx11-deco-circle idx11-deco-c3"></div>
                    <div class="idx11-deco-leaf">
                        <i class="bi bi-key-fill"></i>
                    </div>
                </div>
                <div class="idx11-image-overlay">
                    <div class="idx11-side-icon"><i class="bi bi-key-fill"></i></div>
                    <h3><?= __('front.reset_password') ?></h3>
                    <p><?= __('front.enter_your_email_to_reset_password') ?></p>
                </div>
            </div>
            <div class="idx11-form-side">
                <div class="idx11-form-wrapper">
                    <div class="idx11-header-icon idx11-stagger-1">
                        <i class="bi bi-key-fill"></i>
                    </div>
                    <h1 class="idx11-title idx11-stagger-2"><?= __('front.forget_password') ?></h1>
                    <p class="idx11-subtitle idx11-stagger-2"><?= __('front.enter_your_email_to_reset_password') ?></p>

                    <form id="reset-password-form" class="reset-password-form" autocomplete="off">
                        <div class="mb-3 idx11-stagger-3">
                            <div class="form-floating idx11-input-group">
                                <input required class="form-control idx11-input" type="email" name="email" id="idx11-email" placeholder="<?= __('front.email_address') ?>">
                                <label for="idx11-email"><i class="bi bi-envelope me-1"></i> <?= __('front.email_address') ?></label>
                            </div>
                        </div>

                        <?= render_recaptcha_html('affiliate_forgot') ?>

                        <div class="idx11-info-banner mb-3 idx11-stagger-4">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle flex-shrink-0 mt-1"></i>
                                <p><?= __('front.forget_password_info') ?></p>
                            </div>
                        </div>

                        <div class="d-grid gap-2 idx11-stagger-5">
                            <button class="btn idx11-btn-primary py-3 btn-submit btn_sendmail_bg">
                                <i class="bi bi-send me-2"></i><?= __('front.send_mail') ?>
                            </button>
                            <a href="<?= base_url() ?>" class="btn idx11-btn-outline py-2">
                                <i class="bi bi-arrow-left me-1"></i> <?= __('front.back_to_login') ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<?php
include_once "footer.php";
?>
