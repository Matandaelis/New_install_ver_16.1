<?php include_once "header.php"; ?>

<!-- Glass Reset Password Card -->
<div class="glass-card fade-in-up">

    <!-- Card Header -->
    <div class="glass-card-header text-center">
        <div class="header-icon mb-3">
            <i class="bi bi-key-fill"></i>
        </div>
        <h3 class="fw-bold mb-1"><?= __('front.reset_your_password') ?></h3>
        <p class="text-white-50 mb-0"><?= __('front.will_send_reset_link') ?></p>
    </div>

    <!-- Info Banner -->
    <div class="glass-info-banner mb-4">
        <div class="d-flex align-items-start">
            <i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
            <div>
                <h6 class="fw-bold mb-1"><?= __('front.how_it_works') ?></h6>
                <p class="mb-0 small opacity-85"><?= __('front.email_sent_instructions') ?></p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form class="reset-password-form" id="reset-password-form">

        <!-- Email -->
        <div class="form-floating glass-input-group mb-4">
            <input type="email"
                   class="form-control glass-input"
                   id="email"
                   name="email"
                   placeholder="<?= __('front.enter_email') ?>"
                   required>
            <label for="email">
                <i class="bi bi-envelope-fill me-2"></i><?= __('front.email_address') ?>
            </label>
            <div class="form-text glass-form-text mt-2">
                <i class="bi bi-shield-check me-1"></i><?= __('front.never_share_email') ?>
            </div>
        </div>

        <!-- reCAPTCHA -->
        <div class="mb-4">
            <?= render_recaptcha_html('affiliate_forgot') ?>
        </div>

        <!-- Actions -->
        <div class="d-grid gap-3">
            <button type="submit"
                    class="btn glass-btn-primary btn-lg fw-bold py-3">
                <i class="bi bi-send me-2"></i><?= __('front.send_reset_link') ?>
            </button>

            <button type="button"
                    onclick="window.location='<?= base_url() ?>'"
                    class="btn glass-btn-outline btn-lg fw-bold py-3">
                <i class="bi bi-arrow-left me-2"></i><?= __('front.back_to_login') ?>
            </button>
        </div>
    </form>

    <!-- Footer -->
    <div class="glass-card-footer text-center mt-4">
        <small>
            <i class="bi bi-clock me-1"></i><?= __('front.reset_links_expire') ?>
        </small>
    </div>
</div>

<?php include_once "footer.php"; ?>
