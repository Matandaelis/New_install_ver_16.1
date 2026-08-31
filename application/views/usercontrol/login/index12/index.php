<?php include_once "header.php"; ?>

<?= $hook_floating_pulse ?? '' ?>


    <div class="idx12-card idx12-stagger-1">
        <!-- Card Header -->
        <div class="idx12-card-header">
            <div class="idx12-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <h4><?= $title ?></h4>
            <small><?= __('front.welcome_back_sign_in') ?></small>
        </div>

        <!-- Card Body -->
        <div class="idx12-card-body">
            <!-- Tabs -->
            <div class="idx12-tabs mb-4 idx12-stagger-2">
                <a href="<?= base_url() ?>" class="idx12-tab active">
                    <i class="bi bi-box-arrow-in-right"></i><?= __('front.login') ?>
                </a>
                <?php
                    $reg_status    = isset($store['registration_status']) ? (int)$store['registration_status'] : 0;
                    $vendor_active = ($vendor_marketstatus["marketvendorstatus"] == 1 || !empty($vendor_storestatus['storestatus']));
                    if ($reg_status == 1 || $reg_status == 3 || ($reg_status == 2 && $vendor_active)):
                ?>
                    <a href="<?= base_url('register') ?>" class="idx12-tab"><i class="bi bi-person-plus"></i><?= __('front.register') ?></a>
                <?php endif; ?>
            </div>

            <?php $this->load->view('usercontrol/login/components/login_form_component', [
                'login_theme_id' => 12,
            ]); ?>
                <?= $hook_form_bottom ?? '' ?>
        </div>

        <!-- Card Footer -->
        <div class="idx12-card-footer idx12-stagger-6">
            <small><i class="bi bi-shield-check"></i><?= __('front.your_information_secure') ?></small>
        </div>
    </div>

<?php include_once "footer.php"; ?>
