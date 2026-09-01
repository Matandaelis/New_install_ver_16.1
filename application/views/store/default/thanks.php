<?php
/**
 * Default theme — Free / no-payment checkout confirmation page
 *
 * @contract  Store API v1 — page: thanks (free order confirmation)
 * @see       Store_cart_payload::page_thanks()
 * @see       SSR only — shown after successful free order
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $order      array   Completed order data {id, total, currency, items[], customer{}}
 *   $meta_title string  Page title suffix
 */
?>

<section class="amz-thanks">
    <div class="container">
        <!-- Header -->
        <div class="amz-thanks__header no-print">
            <div class="amz-thanks__icon">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
            </div>
            <h1><?= __('store.order_number') ?> #<?= orderId($order['id']) ?></h1>
            <p class="amz-thanks__subtitle"><?= __('store.thank_you_for_purchasing_an_order') ?></p>
            <?php if($order['order_country']): ?>
                <span class="amz-text-muted">
                    <?= __('store.order_done_from') ?> <?= $order['order_country'] ?> <?= $order['order_country_flag'] ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="amz-thanks__actions no-print">
            <button class="amz-btn amz-btn-primary print-btn"><?= __('store.print') ?></button>
            <a href="<?= base_url('store/order') ?>" class="amz-btn amz-btn-details"><?= __('store.back_to_dashboard') ?></a>
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
                            <th colspan="2"><?= __('store.name') ?></th>
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
                                <img class="amz-order-item-img" src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>" loading="lazy">
                            </td>
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
                                <?= $product['product_name'] ?> <?= ($combinationString != "") ? "(".$combinationString.")" : "" ?>
                                <?php if($product['coupon_discount'] > 0): ?>
                                    <div class="amz-coupon-tag">
                                        <i class="fas fa-tag" aria-hidden="true"></i>
                                        <?= $product['coupon_code'] ?> <?= __('store.applied') ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($order['status'] == 1 && $product['product_type'] == 'downloadable' && $product['downloadable_files']): ?>
                                    <div class="amz-order-link">
                                        <i class="fas fa-download" aria-hidden="true"></i>
                                        <?php foreach ($product['downloadable_files'] as $downloadable_file): ?>
                                            <a href="<?= base_url('store/downloadable_file/'. $downloadable_file['name'] . '/' . $downloadable_file['mask']) ?>" class="amz-download-link" target="_blank">
                                                <?= $downloadable_file['mask'] ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="amz-price"><?= c_format($product['price'] + $product['variation_price']) ?></td>
                            <td><?= $product['quantity'] ?></td>
                            <td><?= c_format($product['coupon_discount']) ?></td>
                            <td class="amz-price amz-price--bold"><?= c_format($product['total']) ?></td>
                        </tr>
                        <?php } ?>
                        <?php foreach ($totals as $total): ?>
                        <tr>
                            <td colspan="4"></td>
                            <td><strong><?= $total['text'] ?></strong></td>
                            <td class="amz-price amz-price--bold"><?= c_format($total['value']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment & Shipping -->
        <div class="amz-thanks__grid">
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
                            <?php if($order['status'] == 0): ?>
                            <tr>
                                <td colspan="3" class="amz-text-muted" style="text-align:center; padding:20px;">
                                    <?= __('store.waiting_for_payment_status') ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach ($payment_history as $value): ?>
                            <tr>
                                <td><?= str_replace("_", " ", $value['payment_mode']) ?></td>
                                <td><?= $order['txn_id'] ?></td>
                                <td><span class="amz-badge amz-badge--info"><?= $value['paypal_status'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($order['payment_method'] == 'bank_transfer'): ?>
                    <div class="amz-card__body">
                        <strong><?= __('store.bank_transfer_instruction') ?></strong>
                        <pre class="amz-pre"><?= $paymentsetting['bank_transfer_instruction'] ?></pre>
                    </div>
                <?php endif; ?>

                <?php if($order['comment']): ?>
                    <div class="amz-card__body">
                        <strong><?= __('store.order_view_comment') ?></strong>
                        <pre class="amz-pre"><?= $order['comment'] ?></pre>
                    </div>
                <?php endif; ?>

                <?php if($order['files']): ?>
                    <div class="amz-card__footer">
                        <strong><?= __('store.order_attechments_download') ?></strong>
                        <div class="amz-order-attachment">
                            <i class="fas fa-paperclip" aria-hidden="true"></i> <?= $order['files'] ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($order['order_country']): ?>
                    <div class="amz-card__footer">
                        <strong><?= __('store.order_done_from') ?></strong>
                        <span><?= $order['order_country'] ?> <?= $order['order_country_flag'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($order['allow_shipping']): ?>
            <!-- Shipping Info -->
            <div class="amz-card">
                <div class="amz-card__header">
                    <h5><?= __('store.shipping_details') ?></h5>
                </div>
                <div class="amz-table-wrap">
                    <table class="amz-table">
                        <tbody>
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
        </div>

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
                        <tr><td colspan="3" class="amz-text-muted" style="text-align:center; padding:20px;"><?= __('store.no_any_order_status') ?></td></tr>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.print-btn')?.addEventListener('click', function() {
        window.print();
    });
});
</script>
