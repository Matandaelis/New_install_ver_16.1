<?php
/**
 * Default theme — Customer order list page
 *
 * @contract  Store API v1 — page: order_list
 * @see       Store_cart_payload::page_order_list()
 * @see       /store/api/v1/pages/order_list  (auth required)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $orders     array   Order list [{id, total, status, created_at, items_count, link}, ...]
 *   $return_to  string  URL to redirect back to (e.g. checkout flow; empty if none)
 */
?>
<?php if (!empty($return_to)): ?>
<div class="container main-container mb-3">
    <div class="amz-alert amz-alert--info" role="alert">
        <i class="fa fa-arrow-left me-2"></i>
        <a href="<?= htmlspecialchars($return_to) ?>"><?= __('store.return_to_checkout') ?? 'Return to checkout' ?></a>
    </div>
</div>
<?php endif; ?>
<?php $acc_active = 'orders'; include(APPPATH.'views/store/default/_account_nav.php'); ?>

<?php
$_order_count = is_array($buyproductlist) ? count($buyproductlist) : 0;
$hdr_icon  = 'fa fa-gift';
$hdr_title = __('store.orders');
$hdr_sub   = __('store.profile') . ' &rsaquo; ' . __('store.orders');
$hdr_pills = [
    ['num' => $_order_count, 'lbl' => __('store.orders'), 'color' => '#fff'],
];
include(APPPATH.'views/store/default/_account_header.php');
?>

<section class="amz-orders">
    <div class="container main-container">
        <div class="amz-table-wrap">
            <table class="amz-table">
                <thead>
                    <tr>
                        <th><?= __('store.order_id') ?></th>
                        <th><?= __('store.price') ?></th>
                        <th><?= __('store.order_status') ?></th>
                        <th><?= __('store.payment_method') ?></th>
                        <th><?= __('store.transaction') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($buyproductlist) {
                        foreach($buyproductlist as $product) {
                            $_status_map = [0 => 'amz-badge--pending', 1 => 'amz-badge--success', 2 => 'amz-badge--warning', 3 => 'amz-badge--muted'];
                            $_badge = $_status_map[$product['status']] ?? 'amz-badge--muted';
                    ?>
                    <tr>
                        <td><span class="amz-order-id">#<?= $product['id'] ?></span></td>
                        <td class="amz-price"><?= c_format($product['total_sum']) ?></td>
                        <td><span class="amz-badge <?= $_badge ?>"><?= $status[$product['status']] ?></span></td>
                        <td><?= str_replace("_", " ", $product['payment_method']) ?></td>
                        <td class="amz-text-muted"><?= $product['txn_id'] ?></td>
                        <td>
                            <a href="<?= base_url('store/vieworder/'. $product['id']) ?>" class="amz-btn amz-btn-sm"><?= __('store.details') ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
        <div class="amz-empty-state">
            <i class="fa fa-receipt" aria-hidden="true"></i>
            <p><?= __('store.no_order_found') ?></p>
        </div>
        <?php } ?>
    </div>
</section>
