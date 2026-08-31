<?php include_once "header.php"; ?>

<?= $hook_floating_pulse ?? '' ?>


                    <!-- Header -->
                    <div class="mb-4 idx2-stagger-1">
                        <div class="idx2-header-icon">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <h2 class="idx2-title"><?= __('front.create_account') ?></h2>
                        <p class="idx2-subtitle"><?= __('front.join_us_get_started') ?></p>
                    </div>

                    <!-- Nav Pills -->
                    <ul class="nav nav-pills idx2-nav-pills justify-content-center mb-4 idx2-stagger-2" role="tablist">
                        <li class="nav-item flex-fill">
                            <a class="nav-link" href="<?= base_url() ?>">
                                <i class="bi bi-box-arrow-in-right me-1"></i><?= __('front.login') ?>
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link active" href="<?= base_url('register') ?>">
                                <i class="bi bi-person-plus me-1"></i><?= __('front.register') ?>
                            </a>
                        </li>
                    </ul>

                    <?php $this->load->view('usercontrol/login/components/register_form_component', [
                        'store' => $store,
                        'vendor_storestatus' => $vendor_storestatus,
                        'vendor_marketstatus' => $vendor_marketstatus,
                        'register_fomm' => isset($register_fomm) ? $register_fomm : '',
                        'register_component_variant' => 'index2',
                    ]); ?>
                <?= $hook_form_bottom ?? '' ?>

                    <!-- Trust Footer -->
                    <div class="idx2-trust-footer text-center idx2-stagger-5">
                        <small>
                            <i class="bi bi-shield-check me-1"></i><?= __('front.your_information_secure') ?>
                        </small>
                    </div>

<?php include_once "footer.php"; ?>
