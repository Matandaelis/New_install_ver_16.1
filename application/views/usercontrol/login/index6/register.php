<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


<div class="idx6-form-pane">
    <div class="idx6-form-wrapper idx6-register-container">
        <div class="idx6-header-icon idx6-stagger-1">
            <i class="bi bi-person-plus-fill"></i>
        </div>
        <h1 class="idx6-title idx6-stagger-2"><?= __('front.sign_up') ?></h1>
        <p class="idx6-subtitle mb-4 idx6-stagger-3">
            <?= __('front.enter_your_information_to_setup_a_new_account') ?>
        </p>

        <?php $this->load->view('usercontrol/login/components/register_form_component', [
            'store' => $store,
            'vendor_storestatus' => $vendor_storestatus,
            'vendor_marketstatus' => $vendor_marketstatus,
            'register_fomm' => isset($register_fomm) ? $register_fomm : '',
            'register_component_variant' => 'index6',
        ]); ?>
                <?= $hook_form_bottom ?? '' ?>

        <div class="text-center mt-3 idx6-stagger-5">
            <a href="<?= base_url() ?>" class="btn idx6-btn-outline w-100 py-2">
                <i class="bi bi-arrow-left me-2"></i><?= __('front.back_to_login') ?>
            </a>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>
