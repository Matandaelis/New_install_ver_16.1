<?php
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>


                <div class="idx4-header-icon idx4-stagger-1">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
                <h2 class="idx4-title idx4-stagger-1"><?= __('front.login') ?></h2>
                <p class="idx4-subtitle idx4-stagger-2"><?= __('front.Use_your_credentials_to_login_into_account') ?></p>

                <ul class="nav idx4-nav-pills nav-fill mt-3 mb-4 idx4-stagger-2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url() ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i><?= __('front.login') ?>
                        </a>
                    </li>
                    <?php if(isset($store['registration_status']) && $store['registration_status']==0) {}
                    else if( ($vendor_marketstatus["marketvendorstatus"]==1 || $vendor_storestatus['storestatus']) && $store['registration_status']!=3 ) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('register') ?>">
                            <i class="bi bi-person-plus me-1"></i><?= __('front.register') ?>
                        </a>
                    </li>
                    <?php } else if($store['registration_status']!=2){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('register') ?>">
                            <i class="bi bi-person-plus me-1"></i><?= __('front.register') ?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>

                <?php $this->load->view('usercontrol/login/components/login_form_component', [
                    'login_theme_id' => 4,
                ]); ?>
                <?= $hook_form_bottom ?? '' ?>

                <div class="idx4-trust-footer text-center idx4-stagger-6">
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
