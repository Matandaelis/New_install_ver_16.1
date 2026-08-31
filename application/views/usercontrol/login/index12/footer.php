</main>

<?= $hook_page_footer ?? '' ?>

<!-- Footer -->
<footer class="idx12-footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav class="navbar navbar-expand-lg p-0">
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#idx12footernav" aria-controls="idx12footernav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="idx12footernav">
                        <ul class="navbar-nav flex-wrap">
                            <li class="nav-item me-2 mb-1">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#termOfUse">
                                    <i class="bi bi-file-text"></i><?= __('front.terms_of_use') ?>
                                </a>
                            </li>
                            <li class="nav-item me-2 mb-1">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#about">
                                    <i class="bi bi-info-circle"></i><?= __('front.about') ?>
                                </a>
                            </li>
                            <?php
                            $store_setting = $this->Product_model->getSettings('store');
                            if(!empty($store_setting['status']) && $store_setting['menu_on_front']){ ?>
                            <li class="nav-item me-2 mb-1">
                                <a class="nav-link" href="<?= base_url('/store') ?>" <?= ($store_setting['menu_on_front_blank']) ? 'target="_blank"' : ''; ?>>
                                    <i class="bi bi-shop"></i><?= __('front.my_store') ?>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if($cookies_menu_setting['cookies_menu']){ ?>
                            <li class="nav-item me-2 mb-1">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#cookie-preferences-modal">
                                    <i class="bi bi-cookie"></i><?= __('front.cookie_preferences') ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-2 mt-lg-0">
                <span class="idx12-footer-text"><?= $footer ?></span>
            </div>
        </div>
    </div>
</footer>

<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/login/index12/js/theme.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/main.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/front-dark-mode.js') ?>?v=<?= av() ?>"></script>

<?php include __DIR__ . "/../cookies_consent.php"; ?>
<?= render_recaptcha_scripts(['login', 'forgot']) ?>

</body>
</html>
