<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


            <div class="idx10-form-wrapper">
                <div class="idx10-header-icon idx10-stagger-1">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h1 class="idx10-title idx10-stagger-2"><?= __('front.login') ?></h1>
                <p class="idx10-subtitle mb-4 idx10-stagger-2"><?= __('front.Use_your_credentials_to_login_into_account') ?></p>

                <?php $this->load->view('usercontrol/login/components/login_form_component', [
                    'login_theme_id' => 10,
                ]); ?>

                <?= $hook_form_bottom ?? '' ?>


                <?php if(isset($store['registration_status']) && $store['registration_status']==0) {}
                else if( ($vendor_marketstatus["marketvendorstatus"]==1 || $vendor_storestatus['storestatus']) && $store['registration_status']!=3 ) { ?>
                    <div class="text-center mt-3 idx10-stagger-6">
                        <span class="idx10-signup-text"><?= __('front.dont_have_an_account') ?> <a href="<?= base_url('register') ?>"><?= __('front.sign_up') ?></a></span>
                    </div>
                <?php } else if($store['registration_status']!=2){ ?>
                    <div class="text-center mt-3 idx10-stagger-6">
                        <span class="idx10-signup-text"><?= __('front.dont_have_an_account') ?> <a href="<?= base_url('register') ?>"><?= __('front.sign_up') ?></a></span>
                    </div>
                <?php } ?>

                <div class="idx10-trust-footer text-center d-flex justify-content-center gap-3 idx10-stagger-6">
                    <span><i class="bi bi-shield-check me-1"></i> <?= __('front.secure') ?></span>
                    <span><i class="bi bi-lightning-charge me-1"></i> <?= __('front.fast') ?></span>
                    <span><i class="bi bi-patch-check me-1"></i> <?= __('front.trusted') ?></span>
                </div>
            </div>

<?php
include_once "footer.php";
?>
