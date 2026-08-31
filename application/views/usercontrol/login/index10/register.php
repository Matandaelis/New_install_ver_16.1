<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


            <div class="idx10-form-wrapper idx10-register-container">
                <div class="idx10-header-icon idx10-stagger-1">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h1 class="idx10-title idx10-stagger-2"><?= __('front.register') ?></h1>
                <p class="idx10-subtitle mb-4 idx10-stagger-2"><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>

                <?php $this->load->view('usercontrol/login/components/register_form_component', [
                    'store' => $store,
                    'vendor_storestatus' => $vendor_storestatus,
                    'vendor_marketstatus' => $vendor_marketstatus,
                    'register_fomm' => isset($register_fomm) ? $register_fomm : '',
                    'register_component_variant' => 'index10',
                ]); ?>
                <?= $hook_form_bottom ?? '' ?>

                <div class="text-center mt-3 idx10-stagger-4">
                    <span class="idx10-signup-text"><?= __('front.already_have_an_account') ?> <a href="<?= base_url() ?>"><?= __('front.login') ?></a></span>
                </div>
            </div>

<?php
include_once "footer.php";
?>
