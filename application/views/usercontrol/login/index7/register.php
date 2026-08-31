<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


<div class="idx7-form-pane">
    <div class="idx7-form-wrapper idx7-register-container">
        <!-- Tabs -->
        <div class="idx7-tabs idx7-stagger-1">
            <a href="<?= base_url() ?>" class="idx7-tab"><?= __('front.login') ?></a>
            <a href="<?= base_url('register') ?>" class="idx7-tab active"><?= __('front.register') ?></a>
        </div>

        <p class="idx7-subtitle mb-4 idx7-stagger-2">
            <?= __('front.enter_your_information_to_setup_a_new_account') ?>
        </p>

        <?php $this->load->view('usercontrol/login/components/register_form_component', [
            'store' => $store,
            'vendor_storestatus' => $vendor_storestatus,
            'vendor_marketstatus' => $vendor_marketstatus,
            'register_fomm' => isset($register_fomm) ? $register_fomm : '',
            'register_component_variant' => 'index7',
        ]); ?>
                <?= $hook_form_bottom ?? '' ?>
    </div>
</div>

<?php
include_once "footer.php";
?>
