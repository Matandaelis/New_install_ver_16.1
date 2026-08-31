                </div>
                <!-- /Form Pane -->
            </div>
        </div>
    </div>

    <?= $hook_page_footer ?? '' ?>

    <!-- Footer -->
    <footer class="idx6-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent p-0">
                        <button class="navbar-toggler btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#idx6Footernav" aria-controls="idx6Footernav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="idx6Footernav">
                            <ul class="navbar-nav mx-auto mb-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#termOfUse">
                                        <?= __('front.terms_of_use') ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#about">
                                        <?= __('front.about') ?>
                                    </a>
                                </li>
                                <?php
                                $store_setting = $this->Product_model->getSettings('store');
                                if(!empty($store_setting['status']) && $store_setting['menu_on_front']){ ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url('/store') ?>" <?= ($store_setting['menu_on_front_blank']) ? 'target="_blank"' : ''; ?>><?= __('front.my_store') ?></a>
                                </li>
                                <?php } ?>

                                <?php if($cookies_menu_setting['cookies_menu']){ ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#cookie-preferences-modal"><?= __('front.cookie_preferences') ?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="col-12 text-center">
                    <span class="idx6-footer-text"><?= $footer ?></span>
                </div>
            </div>
        </div>
    </footer>
</div>

<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/login/index6/js/theme.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/main.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/front-dark-mode.js') ?>?v=<?= av() ?>"></script>

<?php include __DIR__ . "/../cookies_consent.php"; ?>

<?= render_recaptcha_scripts(['login', 'forgot']) ?>

</body>
</html>
