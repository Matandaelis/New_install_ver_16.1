<?php
include_once "header.php";
?>
                <div class="col-lg-5 col-xl-5">
                    <div class="idx5-card">
                        <div class="idx5-header-icon idx5-stagger-1">
                            <i class="bi bi-key"></i>
                        </div>
                        <h2 class="idx5-title idx5-stagger-1"><?= __('front.reset_password') ?></h2>

                        <div class="idx5-info-banner d-flex align-items-start gap-2 mb-3 idx5-stagger-2">
                            <i class="bi bi-info-circle-fill mt-1"></i>
                            <p><?= __('front.email_sent_instructions') ?></p>
                        </div>

                        <form id="reset-password-form" class="reset-password-form" autocomplete="off">
                            <div class="idx5-input-group form-floating mb-4 idx5-stagger-3">
                                <input class="form-control idx5-input" name="email" id="idx5_email" type="email" placeholder="<?= __('front.email') ?>">
                                <label for="idx5_email"><i class="bi bi-envelope me-1"></i><?= __('front.email') ?></label>
                            </div>

                            <?= render_recaptcha_html('affiliate_forgot') ?>

                            <div class="d-grid gap-2 idx5-stagger-4">
                                <button class="btn idx5-btn-primary py-3"><?= __('front.send_reset_link') ?></button>
                                <a href="<?= base_url() ?>" class="btn idx5-btn-outline py-3">
                                    <i class="bi bi-arrow-left me-1"></i><?= __('front.back_to_login') ?>
                                </a>
                            </div>
                        </form>

                        <div class="idx5-trust-footer text-center idx5-stagger-5">
                            <small><i class="bi bi-shield-check me-1"></i><?= __('front.fast') ?> &amp; <?= __('front.trusted') ?></small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-xl-5 d-none d-lg-block idx5-description-pane">
                    <div class="idx5-description-inner idx5-stagger-2">
                        <h3><?= $setting['heading'] ?></h3>
                        <?= $setting['content'] ?>
                    </div>
                </div>

<?php
include_once "footer.php";
?>
