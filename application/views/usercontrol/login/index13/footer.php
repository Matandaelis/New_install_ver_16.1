        </div>
        <!-- /row -->
    </div>
</div>
<!-- End Glass Pro Wrapper -->

<?php
$__glass_offcanvas_vars = [];
if (isset($glass_auth_panel)) {
	$__glass_offcanvas_vars['glass_auth_panel'] = $glass_auth_panel;
}
if (isset($glass_drawer_include_forgot)) {
	$__glass_offcanvas_vars['glass_drawer_include_forgot'] = $glass_drawer_include_forgot;
}
$this->load->view('usercontrol/login/index13/glass_auth_offcanvas', $__glass_offcanvas_vars);
?>

<?= $hook_page_footer ?? '' ?>

<!-- Footer -->
<footer class="glass-footer">
    <div class="container">
        <div class="row align-items-center">

            <!-- Footer Navigation -->
            <div class="col-lg-8">
                <nav class="navbar navbar-expand-lg p-0">
                    <button class="navbar-toggler border-0 text-white" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#footernav" 
                            aria-controls="footernav" 
                            aria-expanded="false" 
                            aria-label="Toggle navigation">
                        <i class="bi bi-three-dots text-white fs-4"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="footernav">
                        <ul class="navbar-nav flex-wrap">

                            <li class="nav-item me-2 mb-2">
                                <a class="nav-link glass-footer-link px-3 py-2 rounded-pill"
                                   href="#"
                                   data-bs-toggle="modal"
                                   data-bs-target="#termOfUse">
                                    <i class="bi bi-file-text me-1"></i><?= __('front.terms_of_use') ?>
                                </a>
                            </li>

                            <li class="nav-item me-2 mb-2">
                                <a class="nav-link glass-footer-link px-3 py-2 rounded-pill"
                                   href="#"
                                   data-bs-toggle="modal"
                                   data-bs-target="#about">
                                    <i class="bi bi-info-circle me-1"></i><?= __('front.about') ?>
                                </a>
                            </li>

                            <?php
                            $store_setting = $this->Product_model->getSettings('store');
                            if(!empty($store_setting['status']) && $store_setting['menu_on_front']){ ?>
                                <li class="nav-item me-2 mb-2 <?php if(base_url(uri_string()) == base_url('/store')){ echo 'active'; } ?>">
                                    <a class="nav-link glass-footer-link px-3 py-2 rounded-pill"
                                       href="<?= base_url('/store') ?>"
                                       <?= ($store_setting['menu_on_front_blank']) ? 'target="_blank"' : ''; ?>>
                                        <i class="bi bi-shop me-1"></i><?= __('front.my_store') ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if($cookies_menu_setting['cookies_menu']){ ?>
                                <li class="nav-item me-2 mb-2">
                                    <a class="nav-link glass-footer-link px-3 py-2 rounded-pill"
                                       href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#cookie-preferences-modal">
                                        <i class="bi bi-cookie me-1"></i><?= __('front.cookie_preferences') ?>
                                    </a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                </nav>
            </div>

            <!-- Footer Text -->
            <div class="col-lg-4 text-center text-lg-end mt-3 mt-lg-0">
                <div class="glass-footer-text">
                    <?= $footer ?>
                </div>
            </div>

        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Files -->
<script src="<?= base_url('assets/template/js/jquery-3.6.0.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js'); ?>?v=<?= av() ?>"></script>

<script src="<?= base_url('assets/template/js/main.js') ?>?v=<?= av() ?>"></script>

<?php include __DIR__ . "/../cookies_consent.php"; ?>

<!-- reCAPTCHA -->
<?= render_recaptcha_scripts(['login', 'forgot']) ?>

<!-- Theme 13: Glass Pro JS -->
<script src="<?= base_url('assets/login/index13/js/theme.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/front-dark-mode.js') ?>?v=<?= av() ?>"></script>

</body>
</html>
