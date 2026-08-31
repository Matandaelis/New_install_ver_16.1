<?php
/**
 * Starter 2026 — Order Details (Downloadable / Course Access) Page
 *
 * @contract  Store API v1 — page: view_order_details
 * @auth      required (customer must own the order)
 * @note      Used for digital products: shows download links and course access buttons.
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $order     array   Order row (id, status, user_id, etc.)
 *   $products  array   Products with downloadable_files and course access URLs
 *   $settings  array   Store settings
 */
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <a href="<?= $base_url ?>order"><?= __('store.orders') ?? 'Orders' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.order_details') ?? 'Order Details' ?></span>
    </nav>
</div>

<section class="s26-order-details-page">
    <div class="container">

        <!-- Order Quick Info -->
        <div class="s26-order-quick-info mb-4">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="s26-order-quick-info__item">
                        <span class="s26-order-quick-info__label"><?= __('store.order_number') ?? 'Order #' ?></span>
                        <span class="s26-order-quick-info__value" style="color:var(--s26-primary)">#<?= isset($order['id']) ? orderId($order['id']) : '' ?></span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="s26-order-quick-info__item">
                        <span class="s26-order-quick-info__label"><?= __('store.status') ?? 'Status' ?></span>
                        <?php
                        $statusClass = 's26-status--default';
                        $statusText = isset($status[$order['status']]) ? $status[$order['status']] : $order['status'];
                        if($order['status'] == 1) $statusClass = 's26-status--success';
                        elseif($order['status'] == 0) $statusClass = 's26-status--pending';
                        elseif($order['status'] == 2) $statusClass = 's26-status--danger';
                        ?>
                        <span class="s26-status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="s26-order-quick-info__item">
                        <span class="s26-order-quick-info__label"><?= __('store.payment_method') ?? 'Payment' ?></span>
                        <span class="s26-order-quick-info__value"><?= !empty($order['payment_method']) ? str_replace("_", " ", ucwords($order['payment_method'])) : '—' ?></span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="s26-order-quick-info__item">
                        <span class="s26-order-quick-info__label"><?= __('store.total') ?? 'Total' ?></span>
                        <span class="s26-order-quick-info__value fw-bold"><?= isset($totals) ? c_format(end($totals)['value']) : '' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="s26-checkout-card mb-4">
            <div class="s26-checkout-card__header">
                <i class="fas fa-box-open"></i>
                <h3><?= __('store.product_info') ?? 'Items Ordered' ?></h3>
            </div>
            <div class="s26-checkout-card__body">
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
                <div class="s26-order-detail-item">
                    <div class="s26-order-detail-item__img">
                        <img src="<?= $img_src ?>" alt="" onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'">
                    </div>
                    <div class="s26-order-detail-item__info">
                        <h5><?= htmlspecialchars($product['product_name']) ?></h5>
                        <?php if ($combinationString != ""): ?>
                        <small class="text-muted"><?= htmlspecialchars($combinationString) ?></small>
                        <?php endif; ?>
                        <div class="s26-order-detail-item__meta">
                            <span><?= $product['quantity'] ?> x <?= c_format($product['price'] + $product['variation_price']) ?></span>
                        </div>
                        <?php if($order['status'] == 1 && ($product['product_type'] == 'downloadable' || $product['product_type'] == 'video' || $product['product_type'] == 'videolink') && $product['downloadable_files']) {
                            if ($product['product_type'] == 'video' || $product['product_type'] == 'videolink') { ?>
                            <a href="<?= base_url('store/vieworderdetails/') . $order['id'] . '?referance=' . $product['product_id'] ?>" class="s26-btn-primary s26-btn--sm mt-2" target="_blank">
                                <i class="fas fa-play-circle"></i> <?= __('store.start_course') ?>
                            </a>
                        <?php } else { ?>
                            <div class="mt-2 d-flex flex-wrap gap-2">
                                <?php foreach ($product['downloadable_files'] as $df) {
                                    $dl = (preg_match("/^(http|https|s3):\/\//", $df['url'])) ? $df['url'] : base_url('store/downloadable_file/' . $df['name'] . '/' . $df['mask'] . '/' . $product['order_id']) . (empty($is_guest) ? '?link=' . encryptString($order['user_id']) : '');
                                ?>
                                <a href="<?= $dl ?>" class="s26-btn-outline s26-btn--sm" target="_blank" download="<?= $df['mask'] ?>">
                                    <i class="fas fa-download"></i> <?= $df['mask'] ?>
                                </a>
                                <?php } ?>
                            </div>
                        <?php } } ?>
                    </div>
                    <div class="s26-order-detail-item__total">
                        <span class="fw-bold"><?= c_format($product['total']) ?></span>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Totals + Shipping Side by Side -->
        <div class="row g-4 mb-4">
            <?php if(isset($order['allow_shipping']) && $order['allow_shipping']): ?>
            <div class="col-md-6">
                <div class="s26-checkout-card h-100">
                    <div class="s26-checkout-card__header"><i class="fas fa-truck"></i><h3><?= __('store.shipping_details') ?></h3></div>
                    <div class="s26-checkout-card__body">
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.address') ?>:</span><span><?= !empty($order['address']) ? nl2br($order['address']) : '—' ?></span></div>
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.city') ?>:</span><span><?= $order['city'] ?? '—' ?></span></div>
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.country') ?>:</span><span><?= $order['country_name'] ?? '—' ?></span></div>
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.postal_code') ?>:</span><span><?= $order['zip_code'] ?? '—' ?></span></div>
                        <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.phone') ?>:</span><span><?= $order['phone'] ?? '—' ?></span></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="<?= (isset($order['allow_shipping']) && $order['allow_shipping']) ? 'col-md-6' : 'col-md-6 ms-auto' ?>">
                <div class="s26-checkout-card h-100">
                    <div class="s26-checkout-card__header"><i class="fas fa-calculator"></i><h3><?= __('store.order_summary') ?? 'Summary' ?></h3></div>
                    <div class="s26-checkout-card__body">
                        <?php if (isset($totals)): foreach ($totals as $total): ?>
                        <div class="d-flex justify-content-between mb-2" style="<?= (strtolower($total['text'] ?? '') == 'total') ? 'border-top:2px solid var(--s26-dark);padding-top:12px;margin-top:8px;font-size:18px;font-weight:900' : 'font-size:14px' ?>">
                            <span><?= $total['text'] ?>:</span>
                            <span class="fw-bold"><?= c_format($total['value']) ?></span>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="<?= $base_url ?>order" class="s26-btn-outline"><i class="fas fa-arrow-left"></i> <?= __('store.back_to_orders') ?? 'Back to Orders' ?></a>
        </div>

    </div>
</section>
