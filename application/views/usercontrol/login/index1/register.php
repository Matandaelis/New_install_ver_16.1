<?php include_once "header.php"; ?>

<?= $hook_floating_pulse ?? '' ?>

<?php
$idx1_hud_stats = !empty($login_live_stats_visible) && isset($login_live_stats) && is_array($login_live_stats);
$idx1_hud_earners = !empty($login_top_earners_visible) && !empty($login_top_earners) && is_array($login_top_earners);
$idx1_show_hud = $idx1_hud_stats || $idx1_hud_earners;
?>

<div class="container idx1-page-wrapper" data-idx1-auth-autoshow="1">
    <section class="idx1-saas-hero py-4 py-lg-5">
        <div class="row align-items-center justify-content-between g-4 g-xl-5">
            <div class="idx1-saas-hero-copy col-12 <?= $idx1_show_hud ? 'col-lg-7 text-center text-lg-start' : 'text-center' ?>">
                <h1 class="idx1-saas-headline display-3 fw-bold mb-0"><?= $setting['heading'] ?? '' ?></h1>
                <?php $this->load->view('usercontrol/login/index1/idx1_hero_intro'); ?>
                <button type="button"
                        class="btn idx1-btn-saas-cta btn-lg rounded-pill"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#idx1AuthOffcanvas"
                        aria-controls="idx1AuthOffcanvas">
                    <?= __('front.idx1_join_or_login') ?>
                </button>
            </div>
            <?php if ($idx1_show_hud): ?>
                <div class="col-lg-5 idx1-saas-hud-col">
                    <?php $this->load->view('usercontrol/login/index1/idx1_hero_hud'); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php
$idx1_auth_panel = 'register';
include __DIR__ . '/idx1_auth_offcanvas.php';
?>

<?php include_once "footer.php"; ?>
