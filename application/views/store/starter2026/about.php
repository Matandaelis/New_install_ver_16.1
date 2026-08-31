<?php
/**
 * Starter 2026 — About Us Page
 *
 * @contract  Store API v1 — page: about
 *
 * GLOBALS  $store_setting, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $settings  array   Store settings (aboutimage pulled from $store_setting['aboutimage'])
 *   $content   array   Page content keyed by field — use $content['about_content'] for body HTML
 *   $category  array   Root categories (for header nav)
 */
$aboutimage = !empty($store_setting['aboutimage'])
    ? base_url('assets/images/site/' . $store_setting['aboutimage'])
    : base_url('assets/store/default/img/about-img.png');
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.about_us') ?? 'About Us' ?></span>
    </nav>
</div>

<section class="s26-about-page">
    <div class="container">
        <div class="row g-5 align-items-center">

            <!-- Image Column -->
            <div class="col-lg-5">
                <div class="s26-about-image-wrap s26-reveal">
                    <img src="<?= $aboutimage ?>" class="s26-about-image" alt="<?= __('store.about_us') ?? 'About Us' ?>"
                         onerror="this.src='<?= base_url('assets/store/default/img/about-img.png') ?>'">
                    <div class="s26-about-image-accent"></div>
                </div>
            </div>

            <!-- Content Column -->
            <div class="col-lg-7">
                <div class="s26-about-content s26-reveal">
                    <p class="s26-section-eyebrow"><?= __('store.our_story') ?? 'Our Story' ?></p>
                    <h1 class="s26-page-title" style="margin-bottom:24px"><?= __('store.about_us') ?? 'About Us' ?></h1>

                    <div class="s26-content-body">
                        <?= !empty($content['about_content']) ? $content['about_content'] : (__('store.about_us_if_not_exist') ?? '<p>We are dedicated to providing the best products and service to our customers.</p>'); ?>
                    </div>

                    <a href="<?= $base_url ?>contact" class="s26-btn-primary" style="margin-top:28px">
                        <i class="fas fa-envelope"></i>
                        <?= __('store.contact_us') ?? 'Contact Us' ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>