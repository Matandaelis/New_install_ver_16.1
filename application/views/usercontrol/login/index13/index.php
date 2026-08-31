<?php include_once "header.php"; ?>

<?= $hook_floating_pulse ?? '' ?>

                    <div class="glass-hero-intro-wrap glass-hero-intro-wrap--landing mt-2">
                        <div class="glass-hero-intro glass-hero-intro--collapsible">
                            <div class="glass-hero-intro-inner">
                                <p class="glass-hero-body"><?= $setting['content'] ?? '' ?></p>
                            </div>
                        </div>
                        <button type="button"
                                class="btn btn-link glass-hero-readmore-btn p-0 mt-2 text-decoration-none fw-semibold d-none"
                                data-read-more="<?= htmlspecialchars(__('front.idx1_read_more'), ENT_QUOTES, 'UTF-8') ?>"
                                data-read-less="<?= htmlspecialchars(__('front.idx1_read_less'), ENT_QUOTES, 'UTF-8') ?>">
                            <span class="glass-hero-readmore-label" style="color:rgba(255,255,255,0.7)"><?= __('front.idx1_read_more') ?></span>
                        </button>
                    </div>

                    <div class="trust-badges d-flex justify-content-center justify-content-lg-start gap-3 gap-lg-4 mt-3 flex-wrap">
                        <div class="trust-badge">
                            <i class="bi bi-lock-fill fs-4 mb-2 d-block"></i>
                            <small><?= __('front.secure') ?></small>
                        </div>
                        <div class="trust-badge">
                            <i class="bi bi-lightning-fill fs-4 mb-2 d-block"></i>
                            <small><?= __('front.fast') ?></small>
                        </div>
                        <div class="trust-badge">
                            <i class="bi bi-shield-check fs-4 mb-2 d-block"></i>
                            <small><?= __('front.trusted') ?></small>
                        </div>
                    </div>

                    <button type="button"
                            class="btn glass-btn-primary glass-cta-hero mt-3"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#glassAuthOffcanvas"
                            aria-controls="glassAuthOffcanvas">
                        <?= __('front.idx1_join_or_login') ?>
                    </button>

                </div>
            </div>

            <div class="col-12 col-lg-5 d-none d-lg-flex glass-stats-col">
                <div class="glass-stats-zone glass-stats-zone--side w-100">
                    <?= $hook_form_bottom ?? '' ?>
                </div>
            </div>

<?php include_once "footer.php"; ?>
