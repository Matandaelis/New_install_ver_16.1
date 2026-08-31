<?php include_once "header.php"; ?>

<?= $hook_floating_pulse ?? '' ?>


    <div class="idx12-card idx12-card-wide idx12-stagger-1">
        <!-- Card Header -->
        <div class="idx12-card-header">
            <div class="idx12-icon"><i class="bi bi-person-plus-fill"></i></div>
            <h4><?= __('front.create_account') ?></h4>
            <small><?= __('front.join_us_get_started') ?></small>
        </div>

        <!-- Card Body -->
        <div class="idx12-card-body">
            <!-- Tabs -->
            <div class="idx12-tabs mb-4 idx12-stagger-2">
                <a href="<?= base_url() ?>" class="idx12-tab">
                    <i class="bi bi-box-arrow-in-right"></i><?= __('front.login') ?>
                </a>
                <a href="<?= base_url('register') ?>" class="idx12-tab active">
                    <i class="bi bi-person-plus"></i><?= __('front.register') ?>
                </a>
            </div>

            <?php $this->load->view('usercontrol/login/components/register_form_component', [
                'store' => $store,
                'vendor_storestatus' => $vendor_storestatus,
                'vendor_marketstatus' => $vendor_marketstatus,
                'register_fomm' => isset($register_fomm) ? $register_fomm : '',
                'register_component_variant' => 'index12',
            ]); ?>
                <?= $hook_form_bottom ?? '' ?>
        </div>

        <!-- Footer -->
        <div class="idx12-card-footer idx12-stagger-5">
            <small><i class="bi bi-shield-check"></i><?= __('front.your_information_secure') ?></small>
        </div>
    </div>

<?php include_once "footer.php"; ?>
