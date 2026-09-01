<?php
/**
 * Default theme — View single order detail page
 *
 * @contract  Store API v1 — page: view_order
 * @see       Store_cart_payload::page_view_order()
 * @see       SSR only — no direct JSON API endpoint (requires order session/auth)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $order            array   Full order data {id, total, currency, status, items[], customer{}}
 *   $is_guest         bool    Whether viewed by guest (no account)
 *   $orderProof       string  URL to uploaded payment proof image (empty if none)
 *   $payment_history  array   Payment attempt history [{amount, status, date, method}, ...]
 *   $status           int     Order status: 0=Pending, 1=Completed, 2=Refunded, 3=Cancelled
 *   $paymentsetting   array   Payment gateway settings (for bank transfer/re-pay UI)
 *
 * NOTE  $order_id and $affiliateuser are NOT used in this view — do not add them.
 */
?>
<?php $acc_active = 'orders'; include(APPPATH.'views/store/default/_account_nav.php'); ?>

<?php
$_ord_status_map = [0 => __('store.pending'), 1 => __('store.completed'), 2 => __('store.refunded'), 3 => __('store.cancelled')];
$_ord_status_lbl = $_ord_status_map[$order['status'] ?? 0] ?? __('store.pending');
$_ord_status_colors = [0 => '#f5a623', 1 => '#27ae60', 2 => '#e74c3c', 3 => '#95a5a6'];
$_ord_color = $_ord_status_colors[$order['status'] ?? 0] ?? '#fff';
$hdr_icon  = 'fa fa-file-text';
$hdr_title = __('store.order_number') . ' #' . orderId($order['id']);
$hdr_sub   = __('store.orders') . ' &rsaquo; ' . __('store.order_number') . ' #' . orderId($order['id']);
$hdr_pills = [
    ['num' => $_ord_status_lbl, 'lbl' => __('store.order_status'), 'color' => $_ord_color],
];
include(APPPATH.'views/store/default/_account_header.php');
?>

<section class="amz-order-detail">
    <div class="container main-container">

        <!-- Order Header -->
        <div class="amz-order-header">
            <div class="amz-order-header__title">
                <h4><?= __('store.order_number') ?> #<?= orderId($order['id']) ?></h4>
                <?php if($order['order_country']): ?>
                    <span class="amz-order-header__from">
                        <?= __('store.order_done_from') ?> <?= $order['order_country'] ?> <?= $order['order_country_flag'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <p class="amz-order-header__thanks"><?= __('store.thank_you_for_purchasing_an_order') ?></p>
        </div>

        <!-- Product Info -->
        <div class="amz-card">
            <div class="amz-card__header">
                <h5><?= __('store.product_info') ?></h5>
            </div>
            <div class="amz-table-wrap">
                <table class="amz-table">
                    <thead>
                        <tr>
                            <th><?= __('store.name') ?></th>
                            <th><?= __('store.image') ?></th>
                            <th><?= __('store.unit_price') ?></th>
                            <th><?= __('store.quantity') ?></th>
                            <th><?= __('store.discount') ?></th>
                            <th><?= __('store.total') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) { ?>
                        <tr>
                            <td>
                                <?php
                                    $combinationString = "";
                                    if(isset($product['variation']) && !empty($product['variation'])) {
                                        $variation = json_decode($product['variation']);
                                        foreach ($variation as $key => $value) {
                                            if($key == 'colors') {
                                                $combinationString .= ($combinationString == "") ? explode("-",$value)[1] : ",".explode("-",$value)[1];
                                            } else {
                                                $combinationString .= ($combinationString == "") ? $value : ",".$value;
                                            }
                                        }
                                    }
                                ?>
                                <?= $product['product_name'] ?>
                                <?php if($combinationString != ""): ?>
                                    <span class="amz-text-muted">(<?= $combinationString ?>)</span>
                                <?php endif; ?>

                                <?php if($product['coupon_discount'] > 0): ?>
                                    <div class="amz-coupon-tag">
                                        <i class="fas fa-tag" aria-hidden="true"></i>
                                        <?= $product['coupon_code'] ?> <?= __('store.applied') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if($order['status'] == 1 && ($product['product_type'] == 'downloadable' || $product['product_type'] =='video' || $product['product_type'] =='videolink') && $product['downloadable_files']): ?>
                                    <?php if ($product['product_type'] =='video' || $product['product_type'] =='videolink'): ?>
                                        <div class="amz-order-link">
                                            <i class="fas fa-play-circle" aria-hidden="true"></i>
                                            <a href="<?= base_url('store/vieworderdetails/').$order['id'].'?referance='.$product['product_id'] ?>" target="_blank">
                                                <?= __('store.start_course') ?>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="amz-order-link">
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                            <?= __('store.files_to_download') ?>
                                            <?php foreach ($product['downloadable_files'] as $downloadable_file):
                                                if (preg_match("/^(http:\/\/|https:\/\/|s3:\/\/).*/", $downloadable_file['url'])) {
                                                    $downloadable_link = $downloadable_file['url'];
                                                } else {
                                                    $downloadable_link = base_url('store/downloadable_file/' . $downloadable_file['name'] . '/' . $downloadable_file['mask'] . '/' . $product['order_id']);
                                                    $downloadable_link .= empty($is_guest) ? '?link=' . encryptString($order['user_id']) : '';
                                                }
                                            ?>
                                                <a href="<?= $downloadable_link ?>" target="_blank" download="<?= $downloadable_file['mask'] ?>" class="amz-download-link">
                                                    <i class="fas fa-file-download" aria-hidden="true"></i> <?= $downloadable_file['mask'] ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <img class="amz-order-item-img" width="60" src="<?= (!empty($product['image'])) ? $product['image'] : base_url('assets/store/default/img/no-image.png'); ?>" alt="<?= $product['product_name'] ?>" loading="lazy">
                            </td>
                            <td class="amz-price"><?= c_format($product['price'] + $product['variation_price']) ?></td>
                            <td><?= $product['quantity'] ?></td>
                            <td>
                                <?php if($product['coupon_discount'] > 0):
                                    echo isset($totals['discount_total']) ? c_format($totals['discount_total']['value']) : '';
                                endif; ?>
                            </td>
                            <td class="amz-price amz-price--bold"><?= c_format($product['total']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="amz-order-totals">
                <?php foreach ($totals as $total): ?>
                    <div class="amz-order-total-row">
                        <span><?= $total['text'] ?></span>
                        <span class="amz-price"><?= c_format($total['value']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="amz-card">
            <div class="amz-card__header">
                <h5><?= __('store.order_payment_info') ?></h5>
            </div>
            <div class="amz-table-wrap">
                <table class="amz-table">
                    <thead>
                        <tr>
                            <th><?= __('store.mode') ?></th>
                            <th><?= __('store.transaction_id') ?></th>
                            <th><?= __('store.payment_status') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php if($order['status'] == 0): ?>
                                <td><?= __('store.waiting_for_payment_status') ?></td>
                            <?php endif; ?>
                            <?php foreach ($payment_history as $value): ?>
                                <td><?= str_replace("_", " ", $value['payment_mode']) ?></td>
                                <td><?= $order['txn_id'] ?></td>
                                <td><span class="amz-badge amz-badge--info"><?= $value['paypal_status'] ?></span></td>
                            <?php endforeach; ?>
                            <?php if($order['payment_method'] == 'bank_transfer'): ?>
                                <td colspan="3">
                                    <div class="amz-bank-info">
                                        <strong><?= __('store.bank_transfer_instruction') ?></strong>
                                        <pre class="amz-pre"><?= $paymentsetting['bank_transfer_instruction'] ?></pre>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php if($orderProof): ?>
                <div class="amz-card__footer">
                    <span class="amz-text-muted"><strong><?= __('store.payment_proof') ?></strong></span>
                    <a href="<?= $orderProof->downloadLink ?>" target="_blank" class="amz-link">
                        <i class="fas fa-external-link-alt" aria-hidden="true"></i> <?= __('store.download') ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Shipping Info -->
        <?php if($order['allow_shipping']): ?>
        <div class="amz-card">
            <div class="amz-card__header">
                <h5><?= __('store.shipping_details') ?></h5>
            </div>
            <div class="amz-table-wrap">
                <table class="amz-table">
                    <tbody>
                        <tr><td class="amz-label"><?= __('store.phone') ?></td><td><?= $order['phone'] ?></td></tr>
                        <tr><td class="amz-label"><?= __('store.address') ?></td><td><?= $order['address'] ?></td></tr>
                        <tr><td class="amz-label"><?= __('store.country') ?></td><td><?= $order['country_name'] ?></td></tr>
                        <tr><td class="amz-label"><?= __('store.state') ?></td><td><?= $order['state_name'] ?></td></tr>
                        <tr><td class="amz-label"><?= __('store.city') ?></td><td><?= $order['city'] ?></td></tr>
                        <tr><td class="amz-label"><?= __('store.postal_code') ?></td><td><?= $order['zip_code'] ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Order Attachments -->
        <?php if($order['files']): ?>
        <div class="amz-card">
            <div class="amz-card__header">
                <h5><?= __('store.order_attechments_download') ?></h5>
            </div>
            <div class="amz-card__body">
                <div class="amz-order-attachment">
                    <i class="fas fa-paperclip" aria-hidden="true"></i>
                    <?= $order['files'] ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Order Status History -->
        <div class="amz-card">
            <div class="amz-card__header">
                <h5><?= __('store.update_order_status') ?></h5>
            </div>
            <div class="amz-table-wrap">
                <table class="amz-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= __('store.status') ?></th>
                            <th><?= __('store.comment') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$order_history): ?>
                        <tr><td colspan="3" class="amz-text-muted"><?= __('store.no_any_order_status') ?></td></tr>
                        <?php endif; ?>
                        <?php foreach ($order_history as $key => $value): ?>
                        <tr>
                            <td>#<?= $key ?></td>
                            <td><span class="amz-badge amz-badge--info"><?= $status[$value['order_status_id']] ?></span></td>
                            <td><?= $value['comment'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
