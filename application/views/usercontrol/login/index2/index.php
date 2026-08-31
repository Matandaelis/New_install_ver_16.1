<?php include_once "header.php"; ?>

<?= $hook_floating_pulse ?? '' ?>


                    <!-- Header -->
                    <div class="mb-4 idx2-stagger-1">
                        <div class="idx2-header-icon">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <h2 class="idx2-title"><?= __('front.login') ?></h2>
                        <p class="idx2-subtitle"><?= __('front.welcome_back_sign_in') ?></p>
                    </div>

                    <!-- Nav Pills -->
                    <ul class="nav nav-pills idx2-nav-pills justify-content-center mb-4 idx2-stagger-2" role="tablist">
                        <li class="nav-item flex-fill">
                            <a class="nav-link active" href="<?= base_url() ?>">
                                <i class="bi bi-box-arrow-in-right me-1"></i><?= __('front.login') ?>
                            </a>
                        </li>
                        <?php
                            $reg_status    = isset($store['registration_status']) ? (int)$store['registration_status'] : 0;
                            $vendor_active = ($vendor_marketstatus["marketvendorstatus"] == 1 || !empty($vendor_storestatus['storestatus']));
                            if ($reg_status == 1 || $reg_status == 3 || ($reg_status == 2 && $vendor_active)):
                        ?>
                            <li class="nav-item flex-fill">
                                <a class="nav-link" href="<?= base_url('register') ?>">
                                    <i class="bi bi-person-plus me-1"></i><?= __('front.register') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <!-- Login Form (usercontrol/login/components/login_form_component) -->
                    <?php $this->load->view('usercontrol/login/components/login_form_component', [
                        'login_theme_id' => 2,
                    ]); ?>
                <?= $hook_form_bottom ?? '' ?>

                    <!-- Trust Footer -->
                    <div class="idx2-trust-footer text-center idx2-stagger-6">
                        <small>
                            <i class="bi bi-shield-check me-1"></i><?= __('front.your_information_secure') ?>
                        </small>
                    </div>

<?php include_once "footer.php"; ?>
