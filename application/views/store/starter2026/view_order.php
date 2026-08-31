<?php
/**
 * Starter 2026 — Single Order Detail Page
 *
 * @contract  Store API v1 — page: view_order
 * @auth      required
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $order           array        Full order row — use $order['id'] for the order number
 *   $products        array        Products in this order [{product_name, quantity, price, product_type, order_id, downloadable_files}]
 *   $totals          array        Order totals keyed by slug (subtotal, shipping, grand_total)
 *   $order_history   array        Status-change timeline entries
 *   $paymentsetting  array        Payment gateway settings (bank transfer instructions, etc.)
 *   $orderProof      object|null  Payment proof file (->downloadLink). null if none
 *   $payment_history array        Payment gateway history rows
 *   $status          array        Map of order_status_id → label
 *   $is_guest        array|false  Guest session data if no-account order; empty/false for logged-in
 *   $settings        array        Store settings
 *
 * NOTE  $order_id and $affiliateuser are passed by the controller but not needed in the view —
 *       use $order['id'] instead of $order_id, and $affiliateuser is display-only data not shown.
 */
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <a href="<?= $base_url ?>order"><?= __('store.orders') ?? 'Orders' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current">#<?= isset($order['id']) ? orderId($order['id']) : '' ?></span>
    </nav>
</div>

<!-- Account Navigation -->
<div class="container">
    <div class="s26-account-nav">
        <a href="<?= $base_url ?>profile" class="s26-account-nav__link"><i class="fas fa-user"></i> <?= __('store.profile') ?></a>
        <a href="<?= $base_url ?>order" class="s26-account-nav__link active"><i class="fas fa-gift"></i> <?= __('store.orders') ?></a>
        <a href="<?= $base_url ?>my_courses" class="s26-account-nav__link"><i class="fas fa-graduation-cap"></i> <?= __('store.my_courses') ?></a>
        <a href="<?= $base_url ?>shipping" class="s26-account-nav__link"><i class="fas fa-truck"></i> <?= __('store.shipping') ?></a>
        <a href="<?= $base_url ?>wishlist" class="s26-account-nav__link"><i class="fas fa-heart"></i> <?= __('store.wishlist') ?></a>
        <a href="<?= $base_url ?>logout" class="s26-account-nav__link s26-account-nav__link--danger"><i class="fas fa-power-off"></i> <?= __('store.logout') ?></a>
    </div>
</div>

<?php
$_s26ord_status_map    = [0=>'Pending',1=>'Completed',2=>'Mismatch',3=>'Denied',4=>'Expired',5=>'Failed'];
$_s26ord_status_colors = [0=>'#f59e0b',1=>'#4ade80',2=>'#f87171',3=>'#f87171',4=>'#94a3b8',5=>'#f87171'];
$_s26ord_code  = (int)($order['status'] ?? 0);
$s26hdr_icon    = 'fas fa-file-alt';
$s26hdr_eyebrow = __('store.orders') . ' &rsaquo; ' . __('store.order_number') . ' #' . orderId($order['id']);
$s26hdr_title   = __('store.order_number') . ' #' . orderId($order['id']);
$s26hdr_sub     = '';
$s26hdr_stats   = [
    ['val' => $_s26ord_status_map[$_s26ord_code] ?? 'Pending', 'lbl' => __('store.order_status'), 'color' => $_s26ord_status_colors[$_s26ord_code] ?? '#fff'],
];
include(APPPATH.'views/store/starter2026/_account_header.php');
?>

<section class="s26-view-order-page">
    <div class="container">

        <!-- Order Header -->
        <div class="s26-order-header">
            <div>
                <h1 class="s26-page-title">
                    <?= __('store.order_number') ?? 'Order' ?> #<?= isset($order['id']) ? orderId($order['id']) : '' ?>
                </h1>
                <?php if (!empty($order['order_country'])): ?>
                <p class="text-muted mb-0" style="font-size:13px">
                    <i class="fas fa-globe me-1"></i>
                    <?= __('store.order_done_from') ?> <?= $order['order_country'] ?> <?= $order['order_country_flag'] ?? '' ?>
                </p>
                <?php endif; ?>
            </div>
            <div class="d-flex gap-2">
                <?php
                $statusClass = 's26-status--default';
                $statusText = isset($status[$order['status']]) ? $status[$order['status']] : $order['status'];
                if($order['status'] == 1) $statusClass = 's26-status--success';
                elseif($order['status'] == 0) $statusClass = 's26-status--pending';
                elseif($order['status'] == 2) $statusClass = 's26-status--danger';
                ?>
                <span class="s26-status-badge <?= $statusClass ?>" style="font-size:13px;padding:8px 18px"><?= $statusText ?></span>
            </div>
        </div>

        <!-- Order Status Timeline -->
        <?php if (isset($order_history) && !empty($order_history)): ?>
        <div class="s26-checkout-card mb-4">
            <div class="s26-checkout-card__header">
                <i class="fas fa-route"></i>
                <h3><?= __('store.order_status') ?? 'Order Status' ?></h3>
            </div>
            <div class="s26-checkout-card__body">
                <div class="s26-order-timeline">
                    <?php foreach ($order_history as $key => $value): ?>
                    <div class="s26-order-timeline__item">
                        <div class="s26-order-timeline__dot"></div>
                        <div class="s26-order-timeline__content">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <span class="s26-status-badge s26-status--default"><?= isset($status[$value['order_status_id']]) ? $status[$value['order_status_id']] : '' ?></span>
                                <?php if (!empty($value['date_added'])): ?>
                                <small class="text-muted"><?= date('M d, Y h:i A', strtotime($value['date_added'])) ?></small>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($value['comment'])): ?>
                            <p class="mb-0 mt-2 text-muted" style="font-size:13px"><?= $value['comment'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tracking Info -->
        <?php if (!empty($order['shipping_tracking_number'])): ?>
        <div class="s26-tracking-banner mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="s26-tracking-banner__icon"><i class="fas fa-shipping-fast"></i></div>
                <div>
                    <p class="mb-0 fw-bold"><?= __('store.tracking_number') ?? 'Tracking Number' ?></p>
                    <p class="mb-0" style="font-size:18px;font-weight:900;color:var(--s26-primary)"><?= $order['shipping_tracking_number'] ?></p>
                    <?php if (!empty($order['shipping_carrier'])): ?>
                    <small class="text-muted"><?= __('store.carrier') ?? 'Carrier' ?>: <?= $order['shipping_carrier'] ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Order Products -->
        <div class="s26-checkout-card mb-4">
            <div class="s26-checkout-card__header">
                <i class="fas fa-box"></i>
                <h3><?= __('store.product_info') ?? 'Order Items' ?></h3>
            </div>
            <div class="s26-checkout-card__body p-0">
                <div class="table-responsive">
                    <table class="s26-order-table">
                        <thead>
                            <tr>
                                <th><?= __('store.name') ?></th>
                                <th class="text-center"><?= __('store.image') ?></th>
                                <th class="text-end"><?= __('store.unit_price') ?></th>
                                <th class="text-center"><?= __('store.quantity') ?></th>
                                <th class="text-end"><?= __('store.total') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($products)): foreach ($products as $product):
                                $combinationString = "";
                                if(isset($product['variation']) && !empty($product['variation'])) {
                                    $variation = json_decode($product['variation']);
                                    foreach ($variation as $key => $value) {
                                        if($key == 'colors') $combinationString .= ($combinationString == "") ? explode("-",$value)[1] : ", ".explode("-",$value)[1];
                                        else $combinationString .= ($combinationString == "") ? $value : ", ".$value;
                                    }
                                }
                                $img_src = (!empty($product['image']))
                                    ? ((strpos($product['image'], 'http') === 0) ? $product['image'] : base_url('assets/images/product/upload/thumb/' . $product['image']))
                                    : base_url('assets/store/default/img/pr-img.png');
                            ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                                    <?= ($combinationString != "") ? "<br><small class='text-muted'>(" . htmlspecialchars($combinationString) . ")</small>" : "" ?>
                                    <?php if(!empty($product['coupon_discount']) && $product['coupon_discount'] > 0): ?>
                                    <div class="mt-1"><span class="s26-status-badge s26-status--success"><?= __('store.code') ?>: <?= isset($product['coupon_code']) ? $product['coupon_code'] : '' ?> <?= __('store.applied') ?></span></div>
                                    <?php endif; ?>
                                    <?php if($order['status'] == 1 && ($product['product_type'] == 'downloadable' || $product['product_type'] == 'video' || $product['product_type'] == 'videolink') && $product['downloadable_files']) {
                                        if ($product['product_type'] == 'video' || $product['product_type'] == 'videolink') { ?>
                                        <div class="mt-2"><a href="<?= base_url('store/vieworderdetails/') . $order['id'] . '?referance=' . $product['product_id'] ?>" class="s26-btn-outline s26-btn--sm" target="_blank"><i class="fas fa-play-circle"></i> <?= __('store.start_course') ?></a></div>
                                    <?php } else { ?>
                                        <div class="mt-2">
                                            <small class="text-muted d-block mb-1"><?= __('store.files_to_download') ?>:</small>
                                            <?php foreach ($product['downloadable_files'] as $downloadable_file) {
                                                if (preg_match("/^(http:\/\/|https:\/\/|s3:\/\/).*/", $downloadable_file['url'])) { $dl = $downloadable_file['url']; }
                                                else { $dl = base_url('store/downloadable_file/' . $downloadable_file['name'] . '/' . $downloadable_file['mask'] . '/' . $product['order_id']); $dl .= empty($is_guest) ? '?link=' . encryptString($order['user_id']) : ''; }
                                            ?>
                                            <a href="<?= $dl ?>" class="s26-btn-outline s26-btn--sm me-1 mb-1" target="_blank" download="<?= $downloadable_file['mask'] ?>"><i class="fas fa-download"></i> <?= $downloadable_file['mask'] ?></a>
                                            <?php } ?>
                                        </div>
                                    <?php } } ?>
                                </td>
                                <td class="text-center"><img src="<?= $img_src ?>" alt="" class="s26-order-thumb" onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'"></td>
                                <td class="text-end fw-bold"><?= c_format($product['price'] + $product['variation_price']) ?></td>
                                <td class="text-center"><?= $product['quantity'] ?></td>
                                <td class="text-end fw-bold"><?= c_format($product['total']) ?></td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Totals -->
        <?php if (isset($totals)): ?>
        <div class="s26-checkout-card mb-4">
            <div class="s26-checkout-card__body">
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <?php foreach ($totals as $total): ?>
                        <div class="d-flex justify-content-between mb-2" style="<?= (strtolower($total['text'] ?? '') == 'total') ? 'border-top:2px solid var(--s26-dark);padding-top:12px;margin-top:8px;font-size:18px;font-weight:900' : 'font-size:14px' ?>">
                            <span><?= $total['text'] ?>:</span>
                            <span class="fw-bold"><?= c_format($total['value']) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Payment Info -->
            <div class="col-md-6">
                <div class="s26-checkout-card">
                    <div class="s26-checkout-card__header">
                        <i class="fas fa-credit-card"></i>
                        <h3><?= __('store.order_payment_info') ?></h3>
                    </div>
                    <div class="s26-checkout-card__body">
                        <?php if($order['status'] == 0): ?>
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.status') ?>:</span><span class="s26-status-badge s26-status--pending"><?= __('store.waiting_for_payment_status') ?></span></div>
                        <?php endif; ?>
                        <?php if (isset($payment_history)): foreach ($payment_history as $value): ?>
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.mode') ?>:</span><span><?= str_replace("_", " ", ucwords($value['payment_mode'])) ?></span></div>
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.transaction_id') ?>:</span><code style="font-size:12px"><?= $order['txn_id'] ?? '—' ?></code></div>
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.payment_status') ?>:</span><span class="s26-status-badge s26-status--success"><?= $value['paypal_status'] ?></span></div>
                        <?php endforeach; endif; ?>
                        <?php if(isset($order['payment_method']) && $order['payment_method'] == 'bank_transfer' && isset($paymentsetting)): ?>
                        <div class="alert alert-info mt-3 s26-alert" style="font-size:13px"><i class="fas fa-university me-2"></i><?= $paymentsetting['bank_transfer_instruction'] ?? '' ?></div>
                        <?php endif; ?>
                        <?php if(isset($orderProof) && $orderProof): ?>
                        <div class="s26-info-row mt-2"><span class="s26-info-row__label"><?= __('store.payment_proof') ?>:</span><a href="<?= $orderProof->downloadLink ?>" class="s26-btn-outline s26-btn--sm" target="_blank"><i class="fas fa-download"></i> <?= __('store.download') ?></a></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <?php if(isset($order['allow_shipping']) && $order['allow_shipping']): ?>
            <div class="col-md-6">
                <div class="s26-checkout-card">
                    <div class="s26-checkout-card__header">
                        <i class="fas fa-truck"></i>
                        <h3><?= __('store.shipping_details') ?></h3>
                    </div>
                    <div class="s26-checkout-card__body">
                        <?php if (!empty($order['phone'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.phone') ?>:</span><span><?= $order['phone'] ?></span></div><?php endif; ?>
                        <?php if (!empty($order['address'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.address') ?>:</span><span><?= nl2br($order['address']) ?></span></div><?php endif; ?>
                        <?php if (!empty($order['country_name'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.country') ?>:</span><span><?= $order['country_name'] ?></span></div><?php endif; ?>
                        <?php if (!empty($order['state_name'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.state') ?>:</span><span><?= $order['state_name'] ?></span></div><?php endif; ?>
                        <?php if (!empty($order['city'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.city') ?>:</span><span><?= $order['city'] ?></span></div><?php endif; ?>
                        <?php if (!empty($order['zip_code'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.postal_code') ?>:</span><span><?= $order['zip_code'] ?></span></div><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Order Files -->
        <?php if(!empty($order['files'])): ?>
        <div class="s26-checkout-card mt-4">
            <div class="s26-checkout-card__header"><i class="fas fa-paperclip"></i><h3><?= __('store.order_attechments_download') ?></h3></div>
            <div class="s26-checkout-card__body"><p class="text-muted mb-0"><?= $order['files'] ?></p></div>
        </div>
        <?php endif; ?>

    </div>
</section>


