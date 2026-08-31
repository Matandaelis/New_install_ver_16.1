                </div><!-- /.idx3-form-wrapper -->
            </div><!-- /.idx3-form-content -->

        </div><!-- /.idx3-form-pane -->

        <!-- Right Pane: Decorative Image -->
        <div class="idx3-image-pane" aria-hidden="true">
            <div class="idx3-deco idx3-deco-1"></div>
            <div class="idx3-deco idx3-deco-2"></div>
            <div class="idx3-deco idx3-deco-3"></div>
            <img class="idx3-bg-image"
                 src="<?= base_url('assets/login/index3/image/group.png') ?>"
                 alt="Illustration">
            <div class="idx3-art-brand">
                <h5><?= $title ?></h5>
                <p><?= __('front.your_information_secure') ?></p>
            </div>
        </div>

    </div><!-- /.idx3-split -->

<?= $hook_page_footer ?? '' ?>

            <footer class="idx3-footer text-light w-100">
                <div class="container-fluid px-3">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <nav class="navbar navbar-expand-lg p-0">
                                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#footernav" aria-controls="footernav" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="footernav">
                                    <ul class="navbar-nav flex-wrap">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#termOfUse">
                                                <i class="bi bi-file-text me-1"></i><?= __('front.terms_of_use') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#about">
                                                <i class="bi bi-info-circle me-1"></i><?= __('front.about') ?>
                                            </a>
                                        </li>
                                        <?php
                                        $store_setting = $this->Product_model->getSettings('store');
                                        if(!empty($store_setting['status']) && $store_setting['menu_on_front']){ ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= base_url('/store') ?>" <?= ($store_setting['menu_on_front_blank']) ? 'target="_blank"' : ''; ?>>
                                                <i class="bi bi-shop me-1"></i><?= __('front.my_store') ?>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if($cookies_menu_setting['cookies_menu']){ ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#cookie-preferences-modal">
                                                <i class="bi bi-cookie me-1"></i><?= __('front.cookie_preferences') ?>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div class="col-lg-4 text-center text-lg-end mt-1 mt-lg-0">
                            <div class="idx3-footer-text"><?= $footer ?></div>
                        </div>
                    </div>
                </div>
            </footer>

<script src="<?= base_url('assets/template/js/jquery-3.6.0.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/main.js') ?>?v=<?= av() ?>"></script>

<?php include __DIR__ . "/../cookies_consent.php"; ?>

<?= render_recaptcha_scripts(['login', 'forgot']) ?>

<script src="<?= base_url('assets/login/index3/js/theme.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/front-dark-mode.js') ?>?v=<?= av() ?>"></script>

</body>
</html>
