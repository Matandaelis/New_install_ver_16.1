<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


                <div class="idx4-header-icon idx4-stagger-1">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h2 class="idx4-title idx4-stagger-1"><?= __('front.register') ?></h2>
                <p class="idx4-subtitle idx4-stagger-2"><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>

                <ul class="nav idx4-nav-pills nav-fill mt-3 mb-3 idx4-stagger-2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i><?= __('front.login') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('register') ?>">
                            <i class="bi bi-person-plus me-1"></i><?= __('front.register') ?>
                        </a>
                    </li>
                </ul>

                <?php $this->load->view('usercontrol/login/components/register_form_component', [
                    'store' => $store,
                    'vendor_storestatus' => $vendor_storestatus,
                    'vendor_marketstatus' => $vendor_marketstatus,
                    'register_fomm' => isset($register_fomm) ? $register_fomm : '',
                    'register_component_variant' => 'index4',
                ]); ?>
                <?= $hook_form_bottom ?? '' ?>

                <div class="idx4-trust-footer text-center idx4-stagger-5">
                    <small><i class="bi bi-shield-check me-1"></i><?= __('front.fast') ?> &amp; <?= __('front.trusted') ?></small>
                </div>

            </div>
        </div>
    </div>

    <div class="idx4-image-pane">
        <div class="idx4-deco idx4-deco-1"></div>
        <div class="idx4-deco idx4-deco-2"></div>
        <div class="idx4-art-brand">
            <h5><?= $title ?></h5>
            <p>&copy; <?= date('Y') ?></p>
        </div>
    </div>

</div><!-- /idx4-split -->

<?= $hook_page_footer ?? '' ?>

<?php
include_once "footer.php";
?>
