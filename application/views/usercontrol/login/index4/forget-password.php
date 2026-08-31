<?php
include_once "header.php";
?>

                <div class="idx4-header-icon idx4-stagger-1">
                    <i class="bi bi-key"></i>
                </div>
                <h2 class="idx4-title idx4-stagger-1"><?= __('front.reset_password') ?></h2>

                <div class="idx4-info-banner d-flex align-items-start gap-2 mb-4 idx4-stagger-2">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <div>
                        <h6 class="mb-0"><?= __('front.reset_password') ?></h6>
                        <p class="mb-0"><?= __('front.email_sent_instructions') ?></p>
                    </div>
                </div>

                <form id="reset-password-form">
                    <div class="idx4-input-group form-floating mb-4 idx4-stagger-3">
                        <input class="form-control idx4-input" name="email" id="idx4_email" type="email" placeholder="<?= __('front.email') ?>">
                        <label for="idx4_email"><i class="bi bi-envelope me-1"></i><?= __('front.email') ?></label>
                    </div>

                    <?= render_recaptcha_html('affiliate_forgot') ?>

                    <div class="d-grid gap-2 idx4-stagger-4">
                        <button class="btn idx4-btn-primary py-3"><?= __('front.send_reset_link') ?></button>
                        <a href="<?= base_url() ?>" class="btn idx4-btn-outline py-3">
                            <i class="bi bi-arrow-left me-1"></i><?= __('front.back_to_login') ?>
                        </a>
                    </div>
                </form>

                <div class="idx4-trust-footer text-center idx4-stagger-5">
                    <small><i class="bi bi-shield-check me-1"></i><?= __('front.fast') ?> &amp; <?= __('front.trusted') ?></small>
                </div>

            </div>
        </div>
    </div>

    <div class="idx4-image-pane">
        <div class="idx4-deco idx4-deco-1"></div>
        <div class="idx4-deco idx4-deco-2"></div>
        <div class="idx4-art-brand">
            <h5><?= $title ?></h5>
            <p>&copy; <?= date('Y') ?></p>
        </div>
    </div>

<?php
include_once "footer.php";
?>
