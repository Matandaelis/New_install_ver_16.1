<?php
/**
 * Starter 2026 — Privacy Policy Page
 *
 * @contract  Store API v1 — page: policy
 *
 * GLOBALS  $store_setting, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $content   array   Page content keyed by field — use $content['policy_content'] for body HTML
 *   $category  array   Root categories (for header nav)
 *
 * NOTE  Policy image is read from $store_setting['policyimage']
 */
$policyimage = !empty($store_setting['policyimage'])
    ? base_url('assets/images/site/' . $store_setting['policyimage'])
    : base_url('assets/store/default/img/about-img.png');
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.privacy_policy') ?? 'Privacy Policy' ?></span>
    </nav>
</div>

<section class="s26-about-page">
    <div class="container">
        <div class="row g-5 align-items-center">

            <!-- Image Column -->
            <div class="col-lg-5">
                <div class="s26-about-image-wrap s26-reveal">
                    <img src="<?= $policyimage ?>" class="s26-about-image" alt="<?= __('store.privacy_policy') ?? 'Privacy Policy' ?>"
                         onerror="this.src='<?= base_url('assets/store/default/img/about-img.png') ?>'">
                    <div class="s26-about-image-accent"></div>
                </div>
            </div>

            <!-- Content Column -->
            <div class="col-lg-7">
                <div class="s26-about-content s26-reveal">
                    <p class="s26-section-eyebrow"><i class="fas fa-shield-alt"></i> <?= __('store.legal') ?? 'Legal' ?></p>
                    <h1 class="s26-page-title" style="margin-bottom:24px"><?= __('store.privacy_policy') ?? 'Privacy Policy' ?></h1>

                    <div class="s26-content-body">
                        <?= !empty($content['policy_content']) ? $content['policy_content'] : (__('store.privacy_if_not_exist') ?? '<p>Our privacy policy outlines how we handle and protect your data.</p>'); ?>
                    </div>

                    <a href="<?= $base_url ?>contact" class="s26-btn-outline" style="margin-top:28px">
                        <i class="fas fa-envelope"></i>
                        <?= __('store.contact_us') ?? 'Contact Us' ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>