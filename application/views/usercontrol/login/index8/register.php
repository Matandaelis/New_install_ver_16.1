<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


<div class="idx8-form-pane">
    <div class="idx8-form-wrapper idx8-register-container">
        <!-- Tabs -->
        <div class="idx8-tabs idx8-stagger-1">
            <a href="<?= base_url() ?>" class="idx8-tab"><?= __('front.login') ?></a>
            <a href="<?= base_url('register') ?>" class="idx8-tab active"><?= __('front.register') ?></a>
        </div>

        <p class="idx8-subtitle mb-4 idx8-stagger-2">
            <?= __('front.enter_your_information_to_setup_a_new_account') ?>
        </p>

        <?php $this->load->view('usercontrol/login/components/register_form_component', [
            'store' => $store,
            'vendor_storestatus' => $vendor_storestatus,
            'vendor_marketstatus' => $vendor_marketstatus,
            'register_fomm' => isset($register_fomm) ? $register_fomm : '',
            'register_component_variant' => 'index8',
        ]); ?>
                <?= $hook_form_bottom ?? '' ?>
    </div>
</div>

<?php
include_once "footer.php";
?>
