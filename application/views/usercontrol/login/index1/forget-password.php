<?php include_once "header.php"; ?>

<div class="container idx1-page-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="idx1-card fade-in-up">

                <!-- Card Header -->
                <div class="text-center mb-4 idx1-stagger-1">
                    <div class="idx1-header-icon">
                        <i class="bi bi-key-fill"></i>
                    </div>
                    <h3 class="idx1-card-title"><?= __('front.reset_your_password') ?></h3>
                    <p class="idx1-card-subtitle"><?= __('front.will_send_reset_link') ?></p>
                </div>

                <!-- Info Banner -->
                <div class="idx1-info-banner mb-4 idx1-stagger-2">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
                        <div>
                            <h6 class="fw-bold mb-1"><?= __('front.how_it_works') ?></h6>
                            <p class="mb-0 small"><?= __('front.email_sent_instructions') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form class="reset-password-form" id="reset-password-form">

                    <!-- Email -->
                    <div class="form-floating idx1-input-group mb-4 idx1-stagger-3">
                        <input type="email"
                               class="form-control idx1-input"
                               id="email"
                               name="email"
                               placeholder="<?= __('front.enter_email') ?>"
                               required>
                        <label for="email">
                            <i class="bi bi-envelope-fill me-2"></i><?= __('front.email_address') ?>
                        </label>
                        <div class="idx1-form-text mt-2">
                            <i class="bi bi-shield-check me-1"></i><?= __('front.never_share_email') ?>
                        </div>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="mb-4 idx1-stagger-4">
                        <?= render_recaptcha_html('affiliate_forgot') ?>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-3 idx1-stagger-5">
                        <button type="submit"
                                class="btn idx1-btn-primary btn-lg py-3">
                            <i class="bi bi-send me-2"></i><?= __('front.send_reset_link') ?>
                        </button>

                        <button type="button"
                                onclick="window.location='<?= base_url() ?>'"
                                class="btn idx1-btn-outline btn-lg py-3">
                            <i class="bi bi-arrow-left me-2"></i><?= __('front.back_to_login') ?>
                        </button>
                    </div>
                </form>

                <!-- Trust Footer -->
                <div class="idx1-card-footer text-center mt-4 idx1-stagger-6">
                    <small>
                        <i class="bi bi-clock me-1"></i><?= __('front.reset_links_expire') ?>
                    </small>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$idx1_auth_panel = 'login';
$idx1_drawer_include_forgot = false;
include __DIR__ . '/idx1_auth_offcanvas.php';
?>

<?php include_once "footer.php"; ?>
