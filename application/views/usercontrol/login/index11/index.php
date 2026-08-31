<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


        <div class="idx11-card">
            <div class="idx11-image-side">
                <div class="idx11-deco-shapes">
                    <div class="idx11-deco-circle idx11-deco-c1"></div>
                    <div class="idx11-deco-circle idx11-deco-c2"></div>
                    <div class="idx11-deco-circle idx11-deco-c3"></div>
                    <div class="idx11-deco-leaf">
                        <i class="bi bi-tree"></i>
                    </div>
                </div>
                <div class="idx11-image-overlay">
                    <div class="idx11-overlay-pitch">
                        <div class="idx11-side-icon"><i class="bi bi-shield-lock-fill"></i></div>
                        <?php $this->load->view('usercontrol/login/index11/idx11_image_overlay_intro'); ?>
                    </div>
                    <?php
                    $_idx11_aux = trim((string) ($hook_brand_stats ?? '') . (string) ($hook_brand_earners ?? ''));
                    if ($_idx11_aux !== '') :
                    ?>
                    <div class="idx11-overlay-aux">
                        <?= $hook_brand_stats ?? '' ?>
                        <?= $hook_brand_earners ?? '' ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="idx11-form-side">
                <div class="idx11-form-wrapper">
                    <div class="idx11-header-icon idx11-stagger-1">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h1 class="idx11-title idx11-stagger-2"><?= __('front.login') ?></h1>
                    <p class="idx11-subtitle idx11-stagger-2"><?= __('front.Use_your_credentials_to_login_into_account') ?></p>

                    <?php $this->load->view('usercontrol/login/components/login_form_component', [
                        'login_theme_id' => 11,
                    ]); ?>

                    <?= $hook_form_bottom ?? '' ?>


                    <?php if(isset($store['registration_status']) && $store['registration_status']==0) {}
                    else if( ($vendor_marketstatus["marketvendorstatus"]==1 || $vendor_storestatus['storestatus']) && $store['registration_status']!=3 ) { ?>
                        <div class="idx11-separator my-3 idx11-stagger-6"><?= __('front.or') ?></div>
                        <div class="d-grid idx11-stagger-6">
                            <a href="<?= base_url('register') ?>" class="btn idx11-btn-outline py-2">
                                <i class="bi bi-person-plus me-1"></i> <?= __('front.create_account') ?>
                            </a>
                        </div>
                    <?php } else if($store['registration_status']!=2){ ?>
                        <div class="idx11-separator my-3 idx11-stagger-6"><?= __('front.or') ?></div>
                        <div class="d-grid idx11-stagger-6">
                            <a href="<?= base_url('register') ?>" class="btn idx11-btn-outline py-2">
                                <i class="bi bi-person-plus me-1"></i> <?= __('front.create_account') ?>
                            </a>
                        </div>
                    <?php } ?>

                    <div class="idx11-trust-footer text-center d-flex justify-content-center gap-3 idx11-stagger-6">
                        <span><i class="bi bi-shield-check me-1"></i> <?= __('front.secure') ?></span>
                        <span><i class="bi bi-lightning-charge me-1"></i> <?= __('front.fast') ?></span>
                        <span><i class="bi bi-patch-check me-1"></i> <?= __('front.trusted') ?></span>
                    </div>
                </div>
            </div>
        </div>

<?php
include_once "footer.php";
?>
