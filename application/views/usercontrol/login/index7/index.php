<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


<div class="idx7-form-pane">
    <div class="idx7-form-wrapper">
        <!-- Tabs -->
        <div class="idx7-tabs idx7-stagger-1">
            <a href="<?= base_url() ?>" class="idx7-tab active"><?= __('front.login') ?></a>
            <?php if(isset($store['registration_status']) && $store['registration_status']==0) {}
            else if( ($vendor_marketstatus["marketvendorstatus"]==1 || $vendor_storestatus['storestatus']) && $store['registration_status']!=3 ) { ?>
                <a href="<?= base_url('register') ?>" class="idx7-tab"><?= __('front.register') ?></a>
            <?php } else if($store['registration_status']!=2){ ?>
                <a href="<?= base_url('register') ?>" class="idx7-tab"><?= __('front.register') ?></a>
            <?php } ?>
        </div>

        <p class="idx7-subtitle mb-4 idx7-stagger-2"><?= __('front.Use_your_credentials_to_login_into_account') ?></p>

        <?php $this->load->view('usercontrol/login/components/login_form_component', [
            'login_theme_id' => 7,
        ]); ?>
                <?= $hook_form_bottom ?? '' ?>

        <div class="idx7-trust-footer text-center d-flex justify-content-center gap-3 idx7-stagger-6">
            <span><i class="bi bi-shield-check me-1"></i> <?= __('front.secure') ?></span>
            <span><i class="bi bi-lightning-charge me-1"></i> <?= __('front.fast') ?></span>
            <span><i class="bi bi-patch-check me-1"></i> <?= __('front.trusted') ?></span>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>
