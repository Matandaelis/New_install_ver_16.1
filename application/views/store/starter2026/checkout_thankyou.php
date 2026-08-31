<?php
/**
 * Starter 2026 — Thank You / Order Complete Page
 *
 * @contract  Store API v1 — page: checkout_thankyou
 * @note      Standalone layout — does NOT extend layout.php (contains its own <html>)
 *
 * GLOBALS  $store_setting  (only this global is available — no layout wrapping)
 *
 * PAGE VARIABLES
 *   $order            array        Full order row (id, total, status, payment_method, txn_id, allow_shipping, etc.)
 *   $products         array        Ordered products (product_name, quantity, price, product_type, downloadable_files, etc.)
 *   $totals           array        Order totals keyed by slug (subtotal, shipping, coupon, grand_total)
 *   $client_loged     bool         true if a registered customer placed the order
 *   $is_guest         array|false  Guest session array if no account; false/empty if logged-in customer
 *   $orderProof       object|null  Payment proof file object with ->downloadLink. null if none uploaded
 *   $payment_history  array        Payment gateway history [{payment_mode, paypal_status, ...}]
 *   $status           array        Map of order_status_id → label for timeline
 *   $order_history    array        Status-change entries [{order_status_id, comment, date_added}]
 *   $paymentsetting   array        Payment settings (bank_transfer_instruction, etc.)
 *   $is_guest_track   bool         true when shown to guest who tracked order (no login required)
 *   $funnel_upsells   array        Optional post-purchase upsell products (plugin-injected, may be empty)
 */
$currency = $store_setting['currency_sign'] ?? '$';
$logo = (!empty($store_setting['logo']))
    ? base_url('assets/images/site/' . $store_setting['logo'])
    : base_url('assets/store/default/img/logo.png');
?>
<!DOCTYPE html>
<html lang="<?= $store_setting['store_language'] ?? 'en' ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($store_setting['name'] ?? '') ?> - <?= __('store.thank_you') ?? 'Thank You' ?></title>
    <?php if (!empty($store_setting['favicon'])): ?>
    <link rel="icon" href="<?= base_url('assets/images/site/' . $store_setting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/shared/fontawesome/css/all.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/store/starter2026/css/theme.css') ?>?v=<?= av() ?>">
    <script src="<?= base_url('assets/store/shared/js/jquery.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>?v=<?= av() ?>"></script>
</head>
<body class="s26-thankyou-page" style="background:var(--s26-light);">

<!-- Header -->
<div class="s26-thankyou-header">
    <div class="container d-flex align-items-center justify-content-between py-3">
        <a href="<?= !empty($is_guest_track) ? base_url('store/track_order') : base_url('store/order') ?>" class="s26-btn-outline s26-btn--sm">
            <i class="fas fa-arrow-left"></i>
            <?= !empty($is_guest_track) ? (__('store.track_your_order') ?? 'Track order') : (__('store.orders') ?? 'Orders') ?>
        </a>
        <a href="<?= base_url('store') ?>">
            <img src="<?= $logo ?>" alt="<?= htmlspecialchars($store_setting['name'] ?? '') ?>" height="36"
                 onerror="this.src='<?= base_url('assets/store/default/img/logo.png') ?>'">
        </a>
        <button class="s26-btn-outline s26-btn--sm no-print" onclick="window.print()">
            <i class="fas fa-print"></i>
            <?= __('store.print') ?? 'Print' ?>
        </button>
    </div>
</div>

<div class="container s26-thankyou-main py-4 py-md-5 pb-5" style="max-width:900px;">

    <!-- Success Banner -->
    <div class="s26-thankyou-banner">
        <div class="s26-thankyou-banner__icon">
            <div class="s26-thankyou-banner__ring"></div>
            <i class="fas fa-check"></i>
        </div>
        <h1 class="s26-thankyou-banner__title"><?= __('store.thank_you_for_purchasing_an_order') ?? 'Thank you for your order!' ?></h1>
        <p class="s26-thankyou-banner__order">
            <?= __('store.order') ?? 'Order' ?>
            <strong>#<?= isset($order['id']) ? orderId($order['id']) : '' ?></strong>
        </p>
        <?php if (!empty($order['order_country'])): ?>
        <p class="s26-thankyou-banner__from">
            <i class="fas fa-globe"></i>
            <?= __('store.order_done_from') ?? 'Order from' ?>
            <?= $order['order_country'] ?> <?= $order['order_country_flag'] ?? '' ?>
        </p>
        <?php endif; ?>
    </div>

    <!-- Order Items -->
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
                            <th><?= __('store.name') ?? 'Product' ?></th>
                            <th class="text-center"><?= __('store.image') ?? 'Image' ?></th>
                            <th class="text-end"><?= __('store.unit_price') ?? 'Price' ?></th>
                            <th class="text-center"><?= __('store.quantity') ?? 'Qty' ?></th>
                            <th class="text-end"><?= __('store.total') ?? 'Total' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($products) && !empty($products)): foreach ($products as $product):
                            $combinationString = "";
                            if(isset($product['variation']) && !empty($product['variation'])) {
                                $variation = json_decode($product['variation']);
                                foreach ($variation as $key => $value) {
                                    if($key == 'colors') $combinationString .= ($combinationString == "") ? explode("-",$value)[1] : ", ".explode("-",$value)[1];
                                    else $combinationString .= ($combinationString == "") ? $value : ", ".$value;
                                }
                            }
                            $image_src = !empty($product['image'])
                                ? ((strpos($product['image'], 'http') === 0) ? $product['image'] : base_url('assets/images/product/upload/thumb/' . $product['image']))
                                : base_url('assets/store/default/img/pr-img.png');
                        ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                                <?= ($combinationString != "") ? "<br><small class='text-muted'>(" . htmlspecialchars($combinationString) . ")</small>" : "" ?>
                                <?php if(!empty($product['coupon_discount']) && $product['coupon_discount'] > 0): ?>
                                <div class="mt-1"><span class="s26-status-badge s26-status--success"><?= __('store.code') ?>: <?= $product['coupon_code'] ?> <?= __('store.applied') ?></span></div>
                                <?php endif; ?>
                                <?php if($order['status'] == 1 && ($product['product_type'] == 'downloadable' || $product['product_type'] == 'video' || $product['product_type'] == 'videolink') && $product['downloadable_files']) {
                                    if ($product['product_type'] == 'video' || $product['product_type'] == 'videolink') { ?>
                                    <div class="mt-2">
                                        <a href="<?= base_url('store/vieworderdetails/') . $order['id'] . '?referance=' . $product['product_id'] ?>" class="s26-btn-outline s26-btn--sm" target="_blank">
                                            <i class="fas fa-play-circle"></i> <?= __('store.start_course') ?>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class="mt-2">
                                        <small class="text-muted d-block mb-1"><?= __('store.files_to_download') ?>:</small>
                                        <?php foreach ($product['downloadable_files'] as $downloadable_file) {
                                            if (preg_match("/^(http:\/\/|https:\/\/|s3:\/\/).*/", $downloadable_file['url'])) {
                                                $downloadable_link = $downloadable_file['url'];
                                            } else {
                                                $downloadable_link = base_url('store/downloadable_file/' . $downloadable_file['name'] . '/' . $downloadable_file['mask'] . '/' . $product['order_id']);
                                                $downloadable_link .= empty($is_guest) ? '?link=' . encryptString($order['user_id']) : '';
                                            }
                                        ?>
                                        <a href="<?= $downloadable_link ?>" class="s26-btn-outline s26-btn--sm me-1 mb-1" target="_blank" download="<?= $downloadable_file['mask'] ?>">
                                            <i class="fas fa-download"></i> <?= $downloadable_file['mask'] ?>
                                        </a>
                                        <?php } ?>
                                    </div>
                                <?php } } ?>
                            </td>
                            <td class="text-center">
                                <img src="<?= $image_src ?>" alt="" class="s26-order-thumb" onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'">
                            </td>
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
    <?php if (isset($totals) && !empty($totals)): ?>
    <div class="s26-checkout-card mb-4">
        <div class="s26-checkout-card__body">
            <div class="row justify-content-end">
                <div class="col-md-5">
                    <?php foreach ($totals as $tkey => $total): ?>
                    <div class="d-flex justify-content-between mb-2 <?= ($tkey == 'grand_total') ? 'border-top pt-3 mt-2' : '' ?>" style="<?= ($tkey == 'grand_total') ? 'font-size:18px;font-weight:900' : 'font-size:14px' ?>">
                        <span><?= $total['text'] ?>:</span>
                        <span class="fw-bold"><?= c_format($total['value']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Payment Info -->
    <div class="s26-checkout-card mb-4">
        <div class="s26-checkout-card__header">
            <i class="fas fa-credit-card"></i>
            <h3><?= __('store.order_payment_info') ?? 'Payment Information' ?></h3>
        </div>
        <div class="s26-checkout-card__body">
            <?php if($order['status'] == 0): ?>
            <div class="s26-info-row">
                <span class="s26-info-row__label"><?= __('store.payment_status') ?>:</span>
                <span class="s26-status-badge s26-status--pending"><?= __('store.waiting_for_payment_status') ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($payment_history)): foreach ($payment_history as $value): ?>
            <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.mode') ?>:</span><span><?= str_replace("_", " ", ucwords($value['payment_mode'])) ?></span></div>
            <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.transaction_id') ?>:</span><code><?= $order['txn_id'] ?? '—' ?></code></div>
            <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.payment_status') ?>:</span><span class="s26-status-badge s26-status--success"><?= $value['paypal_status'] ?></span></div>
            <?php endforeach; else: ?>
            <?php if (!empty($order['payment_method'])): ?>
            <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.mode') ?>:</span><span><?= htmlspecialchars(payment_method($order['payment_method'])) ?></span></div>
            <?php endif; ?>
            <?php if (!empty($order['txn_id'])): ?>
            <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.transaction_id') ?>:</span><code><?= htmlspecialchars($order['txn_id']) ?></code></div>
            <?php endif; ?>
            <?php if ((int)($order['status'] ?? 0) > 0 && empty($order['txn_id']) && empty($order['payment_method'])): ?>
            <p class="text-muted small mb-0"><?= __('store.thank_you_message') ?></p>
            <?php endif; ?>
            <?php endif; ?>
            <?php if(isset($order['payment_method']) && $order['payment_method'] == 'bank_transfer' && isset($paymentsetting)): ?>
            <div class="alert alert-info mt-3 s26-alert"><i class="fas fa-university me-2"></i> <?= $paymentsetting['bank_transfer_instruction'] ?? '' ?></div>
            <?php endif; ?>
            <?php if(isset($orderProof) && $orderProof): ?>
            <div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.payment_proof') ?>:</span><a href="<?= $orderProof->downloadLink ?>" class="s26-btn-outline s26-btn--sm" target="_blank"><i class="fas fa-download"></i> <?= __('store.download') ?></a></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Shipping Info -->
    <?php if(isset($order['allow_shipping']) && $order['allow_shipping']): ?>
    <div class="s26-checkout-card mb-4">
        <div class="s26-checkout-card__header">
            <i class="fas fa-truck"></i>
            <h3><?= __('store.shipping_details') ?? 'Shipping Details' ?></h3>
        </div>
        <div class="s26-checkout-card__body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?php if (!empty($order['phone'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.phone') ?>:</span><span><?= $order['phone'] ?></span></div><?php endif; ?>
                    <?php if (!empty($order['address'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.address') ?>:</span><span><?= nl2br($order['address']) ?></span></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if (!empty($order['country_name'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.country') ?>:</span><span><?= $order['country_name'] ?></span></div><?php endif; ?>
                    <?php if (!empty($order['state_name'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.state') ?>:</span><span><?= $order['state_name'] ?></span></div><?php endif; ?>
                    <?php if (!empty($order['city'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.city') ?>:</span><span><?= $order['city'] ?></span></div><?php endif; ?>
                    <?php if (!empty($order['zip_code'])): ?><div class="s26-info-row"><span class="s26-info-row__label"><?= __('store.postal_code') ?>:</span><span><?= $order['zip_code'] ?></span></div><?php endif; ?>
                </div>
            </div>
            <?php if (!empty($order['shipping_tracking_number'])): ?>
            <div class="s26-tracking-box mt-3">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <span class="fw-bold"><?= __('store.tracking') ?? 'Tracking' ?>:</span>
                    <?= $order['shipping_tracking_number'] ?>
                    <?php if (!empty($order['shipping_carrier'])): ?> (<?= $order['shipping_carrier'] ?>)<?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Order Status History -->
    <?php if (isset($order_history) && !empty($order_history)): ?>
    <div class="s26-checkout-card mb-4">
        <div class="s26-checkout-card__header">
            <i class="fas fa-history"></i>
            <h3><?= __('store.update_order_status') ?? 'Order Status' ?></h3>
        </div>
        <div class="s26-checkout-card__body">
            <?php foreach ($order_history as $key => $value): ?>
            <div class="s26-status-timeline-item">
                <div class="s26-status-timeline-dot"></div>
                <div class="s26-status-timeline-content">
                    <span class="s26-status-badge s26-status--default"><?= isset($status[$value['order_status_id']]) ? $status[$value['order_status_id']] : '' ?></span>
                    <?php if (!empty($value['comment'])): ?><p class="mb-0 mt-1 text-muted" style="font-size:13px"><?= $value['comment'] ?></p><?php endif; ?>
                    <?php if (!empty($value['date_added'])): ?><small class="text-muted"><?= date('M d, Y', strtotime($value['date_added'])) ?></small><?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="text-center mt-4">
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <?php if (!empty($is_guest_track)): ?>
            <a href="<?= base_url('store/track_order') ?>" class="s26-btn-primary">
                <i class="fas fa-search"></i> <?= __('store.track_your_order') ?? 'Track another order' ?>
            </a>
            <?php else: ?>
            <a href="<?= base_url('store/order') ?>" class="s26-btn-primary">
                <i class="fas fa-gift"></i> <?= __('store.orders') ?? 'My Orders' ?>
            </a>
            <?php endif; ?>
            <a href="<?= base_url('store') ?>" class="s26-btn-outline">
                <i class="fas fa-shopping-bag"></i> <?= __('store.continue_shopping') ?? 'Continue Shopping' ?>
            </a>
            <button class="s26-btn-outline no-print" onclick="window.print()">
                <i class="fas fa-print"></i> <?= __('store.print') ?? 'Print' ?>
            </button>
        </div>
    </div>

    <!-- Upsells -->
    <?php if (!empty($funnel_upsells)): ?>
    <div class="s26-checkout-card mt-4" style="border:2px solid var(--s26-warning-light)">
        <div class="s26-checkout-card__header" style="background:var(--s26-warning-light)">
            <i class="fas fa-fire" style="color:#b45309"></i>
            <h3 style="color:#b45309"><?= __('store.exclusive_offers') ?? 'Exclusive Offers' ?></h3>
        </div>
        <div class="s26-checkout-card__body">
            <div class="row g-3">
                <?php foreach ($funnel_upsells as $upsell): ?>
                <div class="col-md-4">
                    <div class="s26-upsell-card">
                        <?php
                        $uimg = !empty($upsell['product_image'])
                            ? ((strpos($upsell['product_image'], 'http') === 0) ? $upsell['product_image'] : base_url('assets/images/product/upload/thumb/' . $upsell['product_image']))
                            : base_url('assets/store/default/img/pr-img.png');
                        ?>
                        <img src="<?= $uimg ?>" alt="<?= htmlspecialchars($upsell['product_name']) ?>">
                        <div class="s26-upsell-card__info">
                            <h6><?= htmlspecialchars($upsell['product_name']) ?></h6>
                            <span class="fw-bold" style="color:var(--s26-primary)"><?= c_format($upsell['product_price']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
function getUrlParameter(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"), results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
if(getUrlParameter('print') == 1) window.print();
</script>
</body>
</html>
