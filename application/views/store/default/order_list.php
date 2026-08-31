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
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="fa fa-arrow-left me-2"></i>
        <a href="<?= htmlspecialchars($return_to) ?>" class="alert-link"><?= __('store.return_to_checkout') ?? '← Return to checkout' ?></a>
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

<section class="profile-page">
	<div class="container main-container">
		<div class="acc-single-col">
			<div class="my-orders">
				<div class="cart-wrapper">
					<ul class="cart-header">
						<li><?= __('store.order_id') ?></li>
						<li class="cart-item-price"><?= __('store.price') ?></li>
						<li><?= __('store.order_status') ?></li>
						<li><?= __('store.payment_method') ?></li>
						<li><?= __('store.transaction') ?></li>
						<li></li>
					</ul>

					<?php if($buyproductlist) {

							$subtotal = 0;
						
							foreach($buyproductlist as $product){ 

								$subtotal = $subtotal + (float)$product['total_sum'];
								
							?>
							<ul class="cart-items-row">
								<li><span class="my-orders-text"><?php echo $product['id'];?></span></li>
								<li><span class="my-orders-text"><?php echo c_format($product['total_sum']); ?></span></li>
								<li><span class="my-orders-text"><?php echo $status[$product['status']]; ?></span></li>
								<li><span class="my-orders-text text-center"><?php echo str_replace("_", " ", $product['payment_method']);?></span></li>
								<li><span class="my-orders-text"><?php echo $product['txn_id'];?></span></li>
								<li><span class="my-orders-text">
									<a href="<?= base_url('store/vieworder/'. $product['id']) ?>" class="btn btn-save-profile"><?= __('store.details') ?></a>
								</span></li>
							</ul>
						<?php } ?>
						<ul class="cart-footer-row">
							<li>
								<span><?= __('store.subtotal') ?></span>		 
								<span><?php echo c_format($subtotal); ?></span>		 
							</li>
							<li>
								<span><?= __('store.total') ?></span>		 
								<span><?php echo c_format($subtotal); ?></span>		 
							</li>
						</ul>
					<?php } else { ?>
						<ul class="cart-items-row">
							<li class="w-100"><span class="my-orders-text"><?= __('store.no_order_found') ?></span></li>		
						</ul>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>
