<?php include_once "header.php"; ?>

                    <div class="mb-4 idx3-stagger-1">
                        <div class="idx3-header-icon">
                            <i class="bi bi-key-fill"></i>
                        </div>
                        <h2 class="idx3-title"><?= __('front.reset_your_password') ?></h2>
                        <p class="idx3-subtitle"><?= __('front.will_send_reset_link') ?></p>
                    </div>

                    <div class="idx3-info-banner mb-4 idx3-stagger-2">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
                            <div>
                                <h6 class="fw-bold mb-1"><?= __('front.how_it_works') ?></h6>
                                <p class="mb-0 small"><?= __('front.email_sent_instructions') ?></p>
                            </div>
                        </div>
                    </div>

                    <form class="reset-password-form" id="reset-password-form">
                        <div class="form-floating idx3-input-group mb-4 idx3-stagger-3">
                            <input type="email" class="form-control idx3-input" id="email" name="email"
                                   placeholder="<?= __('front.enter_email') ?>" required>
                            <label for="email"><i class="bi bi-envelope-fill me-2"></i><?= __('front.email_address') ?></label>
                            <div class="idx3-form-text mt-2">
                                <i class="bi bi-shield-check me-1"></i><?= __('front.never_share_email') ?>
                            </div>
                        </div>

                        <div class="mb-4 idx3-stagger-4"><?= render_recaptcha_html('affiliate_forgot') ?></div>

                        <div class="d-grid gap-3 idx3-stagger-5">
                            <button type="submit" class="btn idx3-btn-primary btn-lg py-3">
                                <i class="bi bi-send me-2"></i><?= __('front.send_reset_link') ?>
                            </button>
                            <button type="button" onclick="window.location='<?= base_url() ?>'" class="btn idx3-btn-outline btn-lg py-3">
                                <i class="bi bi-arrow-left me-2"></i><?= __('front.back_to_login') ?>
                            </button>
                        </div>
                    </form>

                    <div class="idx3-trust-footer text-center idx3-stagger-6">
                        <small><i class="bi bi-clock me-1"></i><?= __('front.reset_links_expire') ?></small>
                    </div>

<?php include_once "footer.php"; ?>
