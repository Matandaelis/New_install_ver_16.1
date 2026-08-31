<?php include_once "header.php"; ?>

    <div class="idx12-card idx12-stagger-1">
        <!-- Header -->
        <div class="idx12-card-header">
            <div class="idx12-icon"><i class="bi bi-key-fill"></i></div>
            <h4><?= __('front.reset_your_password') ?></h4>
            <small><?= __('front.will_send_reset_link') ?></small>
        </div>

        <!-- Body -->
        <div class="idx12-card-body">
            <!-- Info -->
            <div class="idx12-info-alert mb-4 idx12-stagger-2">
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
                    <div>
                        <h6><?= __('front.how_it_works') ?></h6>
                        <p><?= __('front.forget_password_info') ?></p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form class="reset-password-form" id="reset-password-form" autocomplete="off">
                <div class="mb-4 idx12-stagger-3">
                    <div class="form-floating idx12-input-group">
                        <input type="email" class="form-control idx12-input" id="idx12-email" name="email"
                               placeholder="<?= __('front.email_address') ?>" required>
                        <label for="idx12-email">
                            <i class="bi bi-envelope-fill me-2" style="color:var(--idx12-primary-light)"></i><?= __('front.email_address') ?>
                        </label>
                    </div>
                </div>

                <div class="mb-4 idx12-stagger-3">
                    <?= render_recaptcha_html('affiliate_forgot') ?>
                </div>

                <div class="d-grid gap-3 idx12-stagger-4">
                    <button type="submit" class="btn idx12-btn-warning btn-lg py-3 btn-submit btn_sendmail_bg">
                        <i class="bi bi-send me-2"></i><?= __('front.send_mail') ?>
                    </button>
                    <a href="<?= base_url() ?>" class="btn idx12-btn-outline btn-lg py-3">
                        <i class="bi bi-arrow-left me-2"></i><?= __('front.back_to_login') ?>
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="idx12-card-footer idx12-stagger-5">
            <small><i class="bi bi-clock me-2"></i><?= __('front.reset_links_expire') ?></small>
        </div>
    </div>

<?php include_once "footer.php"; ?>
