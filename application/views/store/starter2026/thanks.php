<?php
/**
 * Starter 2026 — Generic Thank You Page
 *
 * @contract  Store API v1 — page: thanks (simple success acknowledgement)
 * @note      Shown for non-order actions (e.g. after contact form submission).
 *
 * GLOBALS  $store_setting, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $message  string  (optional) Custom thank-you message to display
 */
?>

<section class="s26-thanks-page">
    <div class="container">
        <div class="s26-thanks-content">
            <div class="s26-thanks-icon">
                <div class="s26-thanks-icon__ring"></div>
                <i class="fas fa-check"></i>
            </div>
            <h1 class="s26-thanks-title"><?= __('store.thank_you') ?? 'Thank You!' ?></h1>
            <p class="s26-thanks-text"><?= __('store.thank_you_message') ?? 'Your action has been completed successfully. We appreciate your trust in us.' ?></p>
            <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                <a href="<?= base_url('store/order') ?>" class="s26-btn-primary">
                    <i class="fas fa-gift"></i>
                    <?= __('store.view_orders') ?? 'View Orders' ?>
                </a>
                <a href="<?= base_url('store') ?>" class="s26-btn-outline">
                    <i class="fas fa-shopping-bag"></i>
                    <?= __('store.continue_shopping') ?? 'Continue Shopping' ?>
                </a>
            </div>
        </div>
    </div>
</section>
