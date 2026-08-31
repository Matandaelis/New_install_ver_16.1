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
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                </div>
                <div class="idx11-image-overlay">
                    <div class="idx11-overlay-pitch">
                        <div class="idx11-side-icon"><i class="bi bi-person-plus-fill"></i></div>
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
                <div class="idx11-form-wrapper idx11-register-container">
                    <div class="idx11-header-icon idx11-stagger-1">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h1 class="idx11-title idx11-stagger-2"><?= __('front.register') ?></h1>
                    <p class="idx11-subtitle idx11-stagger-2"><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>

                    <?php $this->load->view('usercontrol/login/components/register_form_component', [
                        'store' => $store,
                        'vendor_storestatus' => $vendor_storestatus,
                        'vendor_marketstatus' => $vendor_marketstatus,
                        'register_fomm' => isset($register_fomm) ? $register_fomm : '',
                        'register_component_variant' => 'index11',
                    ]); ?>
                <?= $hook_form_bottom ?? '' ?>

                    <div class="text-center mt-3 idx11-stagger-4">
                        <span class="idx11-signup-text"><?= __('front.already_have_an_account') ?> <a href="<?= base_url() ?>"><?= __('front.login') ?></a></span>
                    </div>
                </div>
            </div>
        </div>

<?php
include_once "footer.php";
?>
