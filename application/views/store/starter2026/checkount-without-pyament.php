<?php
/**
 * Starter 2026 — Checkout Without Payment (zero-total orders)
 *
 * @contract  Store API v1 — page: checkout_free
 * @note      Filename typo (checkount-without-pyament.php) is intentional — matches controller route.
 *            Shown when order total is 0 and no payment gateway step is needed.
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $order     array   The newly created order row
 *   $products  array   Ordered products
 *   $totals    array   Order totals (all zero or zeroed by coupon)
 */
?>

<section class="s26-thanks-page">
    <div class="container">
        <div class="s26-thanks-content">
            <div class="s26-thanks-icon">
                <div class="s26-thanks-icon__ring"></div>
                <i class="fas fa-gift"></i>
            </div>

            <h1 class="s26-thanks-title"><?= __('store.order_confirmed') ?? 'Order Confirmed!' ?></h1>
            <p class="s26-thanks-text">
                <?= __('store.order_placed_without_payment') ?? 'Your order has been placed successfully. No payment was required for this order.' ?>
            </p>

            <?php if (isset($order) && !empty($order)): ?>
            <div class="s26-free-order-card">
                <div class="s26-free-order-card__row">
                    <span><?= __('store.order_number') ?? 'Order #' ?></span>
                    <strong class="s26-free-order-card__id">#<?= isset($order['id']) ? orderId($order['id']) : ($order['id'] ?? '') ?></strong>
                </div>
                <?php if (!empty($order['total'])): ?>
                <div class="s26-free-order-card__row">
                    <span><?= __('store.total') ?? 'Total' ?></span>
                    <strong><?= c_format($order['total']) ?></strong>
                </div>
                <?php endif; ?>
                <?php if (!empty($order['status'])): ?>
                <div class="s26-free-order-card__row">
                    <span><?= __('store.status') ?? 'Status' ?></span>
                    <span class="s26-status-badge s26-status--success"><?= __('store.confirmed') ?? 'Confirmed' ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                <?php if (isset($order['id'])): ?>
                <a href="<?= base_url('store/vieworder/' . $order['id']) ?>" class="s26-btn-primary">
                    <i class="fas fa-eye"></i>
                    <?= __('store.view_order') ?? 'View Order' ?>
                </a>
                <?php endif; ?>
                <a href="<?= base_url('store/order') ?>" class="s26-btn-outline">
                    <i class="fas fa-gift"></i>
                    <?= __('store.orders') ?? 'My Orders' ?>
                </a>
                <a href="<?= base_url('store') ?>" class="s26-btn-outline">
                    <i class="fas fa-shopping-bag"></i>
                    <?= __('store.continue_shopping') ?? 'Continue Shopping' ?>
                </a>
            </div>
        </div>
    </div>
</section>
