            </div>
            <!-- /Hero column -->
        </div>
        <!-- /Left Panel -->

        <!-- Right Panel — Content Side -->
        <div class="idx9-right">
            <div class="idx9-deco-blob idx9-deco-blob-1"></div>
            <div class="idx9-deco-blob idx9-deco-blob-2"></div>

            <div class="idx9-right-content">
                <!-- Default: Hero content -->
                <div class="idx9-panel-section active" data-panel="home">
                    <img src="<?= base_url('assets/login/index9/img/affiliate-image.png') ?>" class="idx9-hero-img" alt="<?= __('front.image') ?>">
                    <div class="idx9-stats-zone">
                        <?= $hook_form_bottom ?? '' ?>
                    </div>
                </div>

                <!-- Terms of Use -->
                <div class="idx9-panel-section" data-panel="terms">
                    <h3><?= $tnc['heading'] ?></h3>
                    <div class="idx9-panel-text"><?= $tnc['content'] ?></div>
                </div>

                <!-- About -->
                <div class="idx9-panel-section" data-panel="about">
                    <h3><?= __('front.about') ?></h3>
                    <div class="idx9-panel-text"><?= is_array($setting) ? ($setting['about_content'] ?? '') : ($setting->about_content ?? '') ?></div>
                </div>
            </div>
        </div>
        <!-- /Right Panel -->
    </div>

    <?= $hook_page_footer ?? '' ?>

    <!-- Footer -->
    <footer class="idx9-footer">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg bg-transparent p-0">
                        <button class="navbar-toggler btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#idx9Footernav" aria-controls="idx9Footernav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="idx9Footernav">
                            <ul class="navbar-nav me-auto mb-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-panel-target="home"><?= __('front.home') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-panel-target="terms"><?= __('front.terms_of_use') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-panel-target="about"><?= __('front.about') ?></a>
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
                            <span class="idx9-footer-text"><?= $footer ?></span>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </footer>
</div>

<?php
$__idx9_offcanvas_vars = [];
if (isset($idx9_auth_panel)) {
	$__idx9_offcanvas_vars['idx9_auth_panel'] = $idx9_auth_panel;
}
if (isset($idx9_drawer_include_forgot)) {
	$__idx9_offcanvas_vars['idx9_drawer_include_forgot'] = $idx9_drawer_include_forgot;
}
$this->load->view('usercontrol/login/index9/idx9_auth_offcanvas', $__idx9_offcanvas_vars);
?>

<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/login/index9/js/theme.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/main.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/front-dark-mode.js') ?>?v=<?= av() ?>"></script>

<?php include __DIR__ . "/../cookies_consent.php"; ?>

<?= render_recaptcha_scripts(['login', 'forgot']) ?>

</body>
</html>
