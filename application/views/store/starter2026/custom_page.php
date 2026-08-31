<?php
/**
 * Starter 2026 — Custom Page
 *
 * @contract  Store API v1 — page: custom_page
 *
 * GLOBALS  $store_setting, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $settings  array   Store settings
 *   $data      object  The custom page record (title, content, slug, seo_title, seo_description)
 */
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?? base_url('store') ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= htmlspecialchars($data->title ?? (__('store.page') ?? 'Page')) ?></span>
    </nav>
</div>

<section class="s26-custom-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="s26-custom-page__card">
                    <?php if(!empty($data->image)): ?>
                    <div class="s26-custom-page__hero">
                        <img src="<?= base_url('assets/images/site/' . $data->image) ?>" alt="<?= htmlspecialchars($data->title ?? '') ?>"
                             onerror="this.style.display='none'">
                    </div>
                    <?php endif; ?>

                    <div class="s26-custom-page__body">
                        <h1 class="s26-page-title" style="margin-bottom:24px"><?= htmlspecialchars($data->title ?? '') ?></h1>
                        <div class="s26-content-body">
                            <?= $data->content ?? '' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
