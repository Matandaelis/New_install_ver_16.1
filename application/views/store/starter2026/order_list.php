<?php
/**
 * Starter 2026 — Customer Order List Page
 *
 * @contract  Store API v1 — page: order_list
 * @auth      required (redirect to login if guest)
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $buyproductlist  array   Paginated list of orders [{id, total, status, date_added, ...}]
 *   $status          array   Map of order_status_id → label
 *   $user            array   Logged-in customer (alias of $client)
 *   $settings        array   Store settings
 *   $return_to       string  URL to go back to
 */
?>

<div class="container">
    <?php if (!empty($return_to)): ?>
    <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
        <i class="fas fa-arrow-left me-2"></i>
        <a href="<?= htmlspecialchars($return_to) ?>" class="alert-link"><?= __('store.return_to_checkout') ?? '← Return to checkout' ?></a>
    </div>
    <?php endif; ?>
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.orders') ?? 'Orders' ?></span>
    </nav>
</div>

<!-- Account Navigation -->
<div class="container">
    <div class="s26-account-nav">
        <a href="<?= $base_url ?>profile" class="s26-account-nav__link">
            <i class="fas fa-user"></i> <?= __('store.profile') ?? 'Profile' ?>
        </a>
        <a href="<?= $base_url ?>order" class="s26-account-nav__link active">
            <i class="fas fa-gift"></i> <?= __('store.orders') ?? 'Orders' ?>
        </a>
        <a href="<?= $base_url ?>my_courses" class="s26-account-nav__link">
            <i class="fas fa-graduation-cap"></i> <?= __('store.my_courses') ?? 'My Courses' ?>
        </a>
        <a href="<?= $base_url ?>shipping" class="s26-account-nav__link">
            <i class="fas fa-truck"></i> <?= __('store.shipping') ?? 'Shipping' ?>
        </a>
        <a href="<?= $base_url ?>wishlist" class="s26-account-nav__link">
            <i class="fas fa-heart"></i> <?= __('store.wishlist') ?? 'Wishlist' ?>
        </a>
        <a href="<?= $base_url ?>logout" class="s26-account-nav__link s26-account-nav__link--danger">
            <i class="fas fa-power-off"></i> <?= __('store.logout') ?? 'Logout' ?>
        </a>
    </div>
</div>

<?php
$_s26_order_count = is_array($buyproductlist) ? count($buyproductlist) : 0;
$s26hdr_icon    = 'fas fa-gift';
$s26hdr_eyebrow = __('store.home') . ' &rsaquo; ' . __('store.orders');
$s26hdr_title   = __('store.orders');
$s26hdr_sub     = __('store.orders');
$s26hdr_stats   = [
    ['val' => $_s26_order_count, 'lbl' => __('store.orders'), 'color' => '#fff'],
];
include(APPPATH.'views/store/starter2026/_account_header.php');
?>

<section class="s26-orders-page">
    <div class="container">

        <h1 class="s26-page-title mb-4">
            <i class="fas fa-gift" style="color:var(--s26-primary);font-size:0.8em"></i>
            <?= __('store.orders') ?? 'My Orders' ?>
        </h1>

        <?php if(isset($buyproductlist) && $buyproductlist): ?>

        <!-- Orders Table (Desktop) -->
        <div class="s26-orders-table d-none d-md-block">
            <div class="s26-orders-table__head">
                <div class="s26-orders-col s26-orders-col--id"><?= __('store.order_id') ?? 'Order #' ?></div>
                <div class="s26-orders-col s26-orders-col--total"><?= __('store.total') ?? 'Total' ?></div>
                <div class="s26-orders-col s26-orders-col--status"><?= __('store.order_status') ?? 'Status' ?></div>
                <div class="s26-orders-col s26-orders-col--payment"><?= __('store.payment_method') ?? 'Payment' ?></div>
                <div class="s26-orders-col s26-orders-col--txn"><?= __('store.transaction') ?? 'Transaction' ?></div>
                <div class="s26-orders-col s26-orders-col--action"></div>
            </div>

            <?php
            $subtotal = 0;
            foreach($buyproductlist as $product):
                $subtotal += (float)$product['total_sum'];
                $statusText = isset($status[$product['status']]) ? $status[$product['status']] : $product['status'];
                $statusClass = 's26-status--default';
                if($product['status'] == 1) $statusClass = 's26-status--success';
                elseif($product['status'] == 0) $statusClass = 's26-status--pending';
                elseif($product['status'] == 2) $statusClass = 's26-status--danger';
            ?>
            <div class="s26-orders-row">
                <div class="s26-orders-col s26-orders-col--id">
                    <span class="s26-order-id">#<?= $product['id'] ?></span>
                </div>
                <div class="s26-orders-col s26-orders-col--total">
                    <span class="fw-bold"><?= c_format($product['total_sum']) ?></span>
                </div>
                <div class="s26-orders-col s26-orders-col--status">
                    <span class="s26-status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                </div>
                <div class="s26-orders-col s26-orders-col--payment">
                    <?= str_replace("_", " ", $product['payment_method']) ?>
                </div>
                <div class="s26-orders-col s26-orders-col--txn">
                    <small class="text-muted"><?= $product['txn_id'] ?? '—' ?></small>
                </div>
                <div class="s26-orders-col s26-orders-col--action">
                    <a href="<?= base_url('store/vieworder/' . $product['id']) ?>" class="s26-btn-outline s26-btn--sm">
                        <i class="fas fa-eye"></i> <?= __('store.details') ?? 'View' ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Totals -->
            <div class="s26-orders-footer">
                <div class="s26-orders-footer__row">
                    <span><?= __('store.total') ?? 'Grand Total' ?></span>
                    <span class="s26-orders-footer__value"><?= c_format($subtotal) ?></span>
                </div>
            </div>
        </div>

        <!-- Orders Cards (Mobile) -->
        <div class="d-md-none">
            <?php
            foreach($buyproductlist as $product):
                $statusText = isset($status[$product['status']]) ? $status[$product['status']] : $product['status'];
                $statusClass = 's26-status--default';
                if($product['status'] == 1) $statusClass = 's26-status--success';
                elseif($product['status'] == 0) $statusClass = 's26-status--pending';
                elseif($product['status'] == 2) $statusClass = 's26-status--danger';
            ?>
            <div class="s26-order-card-mobile">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="s26-order-id">#<?= $product['id'] ?></span>
                    <span class="s26-status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px"><?= str_replace("_", " ", $product['payment_method']) ?></span>
                    <span class="fw-bold"><?= c_format($product['total_sum']) ?></span>
                </div>
                <a href="<?= base_url('store/vieworder/' . $product['id']) ?>" class="s26-btn-outline s26-btn--sm w-100 justify-content-center" style="margin-top:8px">
                    <i class="fas fa-eye"></i> <?= __('store.details') ?? 'View Details' ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>

        <div class="s26-empty-state">
            <div class="s26-empty-state__icon">
                <i class="fas fa-gift"></i>
            </div>
            <h2 class="s26-empty-state__title"><?= __('store.no_order_found') ?? 'No orders yet' ?></h2>
            <p class="s26-empty-state__text"><?= __('store.no_orders_desc') ?? 'When you place an order, it will appear here.' ?></p>
            <a href="<?= $base_url ?>category" class="s26-btn-primary">
                <i class="fas fa-shopping-bag"></i>
                <?= __('store.continue_shopping') ?? 'Start Shopping' ?>
            </a>
        </div>

        <?php endif; ?>

    </div>
</section>
