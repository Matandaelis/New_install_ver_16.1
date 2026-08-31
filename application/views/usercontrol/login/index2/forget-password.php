<?php include_once "header.php"; ?>

                    <!-- Header -->
                    <div class="mb-4 idx2-stagger-1">
                        <div class="idx2-header-icon">
                            <i class="bi bi-key-fill"></i>
                        </div>
                        <h2 class="idx2-title"><?= __('front.reset_your_password') ?></h2>
                        <p class="idx2-subtitle"><?= __('front.will_send_reset_link') ?></p>
                    </div>

                    <!-- Info Banner -->
                    <div class="idx2-info-banner mb-4 idx2-stagger-2">
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
                        <div class="form-floating idx2-input-group mb-4 idx2-stagger-3">
                            <input type="email"
                                   class="form-control idx2-input"
                                   id="email"
                                   name="email"
                                   placeholder="<?= __('front.enter_email') ?>"
                                   required>
                            <label for="email">
                                <i class="bi bi-envelope-fill me-2"></i><?= __('front.email_address') ?>
                            </label>
                            <div class="idx2-form-text mt-2">
                                <i class="bi bi-shield-check me-1"></i><?= __('front.never_share_email') ?>
                            </div>
                        </div>

                        <!-- reCAPTCHA -->
                        <div class="mb-4 idx2-stagger-4">
                            <?= render_recaptcha_html('affiliate_forgot') ?>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-3 idx2-stagger-5">
                            <button type="submit"
                                    class="btn idx2-btn-primary btn-lg py-3">
                                <i class="bi bi-send me-2"></i><?= __('front.send_reset_link') ?>
                            </button>

                            <button type="button"
                                    onclick="window.location='<?= base_url() ?>'"
                                    class="btn idx2-btn-outline btn-lg py-3">
                                <i class="bi bi-arrow-left me-2"></i><?= __('front.back_to_login') ?>
                            </button>
                        </div>
                    </form>

                    <!-- Trust Footer -->
                    <div class="idx2-trust-footer text-center idx2-stagger-6">
                        <small>
                            <i class="bi bi-clock me-1"></i><?= __('front.reset_links_expire') ?>
                        </small>
                    </div>

<?php include_once "footer.php"; ?>
