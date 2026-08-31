<?php
/**
 * Default theme — Checkout Thank You / Order Confirmation page
 *
 * @contract  Store API v1 — page: checkout_thankyou
 * @see       Store_cart_payload::page_checkout_thankyou()
 * @see       SSR only — no direct JSON API endpoint (requires order session)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $order             array   Order data {id, total, currency, items[], customer{}}
 *   $orderProof        string  URL to uploaded payment proof image (empty if none)
 *   $payment_history   array   Payment attempt history [{amount, status, date, method}, ...]
 *   $status            int     Order status code: 0=Pending, 1=Completed, 2=Refunded, 3=Cancelled
 *   $order_history     array   Order status change log [{status, date, note}, ...]
 *   $paymentsetting    array   Payment gateway settings (for re-pay instructions)
 *   $is_guest_track    bool    Whether the user is tracking as a guest (no account)
 *   $affiliateuser     array   Affiliate data if this order came from affiliate referral (optional)
 *   $funnel_upsells    array   Post-purchase upsell products (optional)
 *   $meta_title        string  Page <title> suffix
 *   $meta_description  string  Meta description
 *   $meta_image        string  OG image URL
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content=""/>
	<meta name="author" content=""/>
	
	<?php if(isset($meta_title)){ ?> <meta property="og:title" content="<?php echo $meta_title ?>"/><?php } ?>
	<?php if(isset($meta_description)){ ?> <meta property="og:description" content="<?php echo $meta_description ?>"/><?php } ?>
	<?php if(isset($meta_image)){ ?> <meta property="og:image" content="<?php echo $meta_image ?>"/><?php } ?>
	<?php 
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	?>
	<meta property="og:url" content="<?= $actual_link ?>"/>
	<meta name="twitter:card" content="summary_large_image"/>

	<?php if($store_setting['favicon']){ ?>
		<link rel="icon" href="<?= base_url('assets/images/site/'.$store_setting['favicon']) ?>" type="image/*" sizes="16x16">
	<?php } ?>

	<title><?= $store_setting['name'] ?>  <?= isset($meta_title) ? '- ' . $meta_title : '' ?></title>

	<link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>" />
	<link rel="stylesheet" href="<?= base_url('assets/store/default/'); ?>css/thankyou.css" />

	<script src="<?= base_url('assets/template/js/jquery-3.7.1.min.js') ?>?v=<?= av() ?>"></script>
	<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>?v=<?= av() ?>"></script>

	<script type="text/javascript">
		try {
			<?php 
			if($store_setting['google_analytics'] != ''){
				$ana = preg_replace('/<script>/', '', $store_setting['google_analytics']);
				$ana = preg_replace('/<\/script>/', '', $ana);
				echo $ana;
			} 
			?>
		} catch (error) {
			console.log(error);
		}
	</script>

	<?php 
	$global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
	if(in_array('store', $global_script_status)){
		echo $SiteSetting['global_script'];
	}
	?>

	<script type="text/javascript">
		(function ($) {
			$.fn.btn = function (action) {
				var self = $(this);
				if (action == 'loading') {
					if ($(self).attr("disabled") == "disabled") {
                  }
                  $(self).attr("disabled", "disabled");
                  $(self).attr('data-btn-text', $(self).html());
                  $(self).html('<div class="spinner-border spinner-border-sm"></div>&nbsp;' + $(self).text());
              }
              if (action == 'reset') {
              	$(self).html($(self).attr('data-btn-text'));
              	$(self).removeAttr("disabled");
              }
          }
      })(jQuery);
      var formDataFilter = function(formData) {
      	if (!(window.FormData && formData instanceof window.FormData)) return formData
      		if (!formData.keys) return formData
      			var newFormData = new window.FormData()
      		Array.from(formData.entries()).forEach(function(entry) {
      			var value = entry[1]
      			if (value instanceof window.File && value.name === '' && value.size === 0) {
      				newFormData.append(entry[0], new window.Blob([]), '')
      			} else {
      				newFormData.append(entry[0], value)
      			}
      		});
      		return newFormData;
      	}
      </script>

      <?php if (is_rtl()) { ?>
      	<!-- place here your RTL css code -->
      <?php } ?>
  </head>

<body class="bg-light">

<!-- Header Section -->
<div class="container-fluid bg-white shadow-sm mb-4">
    <div class="row align-items-center py-3">
        <div class="col-2 text-center">
            <a href="<?= !empty($is_guest_track) ? base_url('store/track_order') : base_url('store/order') ?>" class="btn btn-outline-secondary btn-sm">
            	<img src="<?= base_url('assets/store/default/img/back-button.png') ?>" class="img-fluid" alt="<?= __('store.back') ?>" style="max-height: 20px;">
            	<span class="d-none d-md-inline ms-1"><?= __('store.back') ?></span>
            </a>
        </div>
        <?php  $logo = ($store_setting['logo']) ? base_url('assets/images/site/'.$store_setting['logo']) : base_url('assets/store/default/').'img/logo.png'; ?>
        <div class="col-8 text-center">
            <a href="<?=base_url('store');?>"><img src="<?= $logo; ?>" class="img-fluid" style="max-height: 50px;"></a>
        </div>
        <div class="col-2 text-center">
            <button class="btn btn-outline-primary btn-sm no-print" onclick="window.print()">
            	<img src="<?= base_url('assets/store/default/'); ?>img/printer.png" class="img-fluid" alt="<?= __('store.print') ?>" style="max-height: 20px;">
            	<span class="d-none d-md-inline ms-1"><?= __('store.print') ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Navigation (hidden for guest order tracking) -->
<?php if (empty($is_guest_track)): ?>
<div class="container-fluid mb-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded">
		<a class="navbar-brand fw-bold text-primary" href="#">
			<?= $store_setting['name'] ?> <?= isset($meta_title) ? '- ' . $meta_title : '' ?>
		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#profilesubnav" aria-controls="profilesubnav" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		</button>
        <div class="collapse navbar-collapse" id="profilesubnav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>profile"><?= __('store.profile') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= $base_url ?>order"><?= __('store.orders') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>shipping"><?= __('store.shipping') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>wishlist"><?= __('store.wishlist') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= $base_url ?>logout"><?= __('store.logout') ?></a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<?php endif; ?>

<!-- Main Content -->
<div class="container">
	<!-- Success Alert -->
	<div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
		<div class="d-flex align-items-center">
			<div class="text-success me-3" style="font-size: 2rem;">✓</div>
			<div>
				<h4 class="alert-heading mb-1"><?= __('store.thank_you_for_purchasing_an_order') ?></h4>
				<p class="mb-1"><strong><?= __('store.order_number') ?> #<?php echo orderId($order['id']); ?></strong></p>
				<?php if($order['order_country']){ ?>
					<small class="text-muted"><strong><?= __('store.order_done_from') ?></strong> <?php echo $order['order_country'];?><?php echo $order['order_country_flag'];?></small>
				<?php  } else { ?>
					<small class="text-muted"><strong><?= __('store.order_done_from') ?></strong> localhost</small>
				<?php  } ?>
			</div>
		</div>
	</div>

	<!-- Product Information -->
	<div class="card shadow-sm mb-4">
		<div class="card-header bg-primary text-white">
			<h5 class="mb-0"><?= __('store.product_info') ?></h5>
		</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-striped mb-0">
					<thead>
						<tr>
							<th scope="col"><?= __('store.name') ?></th>
							<th scope="col" class="text-center"><?= __('store.image') ?></th>
							<th scope="col" class="text-end"><?= __('store.unit_price') ?></th>
							<th scope="col" class="text-center"><?= __('store.quantity') ?></th>
							<th scope="col" class="text-end"><?= __('store.total') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($products as $key => $product) { ?>
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
								<strong><?= $product['product_name'] ?></strong> 
								<?= ($combinationString != "") ? "<small class='text-muted'>(".htmlspecialchars($combinationString).")</small>" : "" ?>

								<?php if($product['coupon_discount'] > 0){ ?>
									<div class="mt-2">
										<span class="badge bg-success"><?= __('store.code') ?>: <?= $product['coupon_code'] ?> <?= __('store.applied') ?></span>
									</div>
								<?php } ?>

								<?php if($order['status'] == 1 && ($product['product_type'] == 'downloadable' || $product['product_type'] =='video' || $product['product_type'] =='videolink') && $product['downloadable_files']) { 
									 if ($product['product_type'] =='video' || $product['product_type'] =='videolink') { ?>
									<div class="mt-2">
										<small class="text-muted d-block"><?= __('store.course_link') ?>:</small>
										<a href="<?=base_url('store/vieworderdetails/').$order['id'].'?referance='.$product['product_id'] ?>" class="btn btn-sm btn-outline-primary" title="<?= __('store.start_course') ?>" target="_blank">
											<?= __('store.start_course') ?>
										</a>
									</div>
								<?php } else { ?>
									<div class="mt-2">
										<small class="text-muted d-block"><?= __('store.files_to_download') ?>:</small>
										<div class="d-flex flex-wrap gap-1">
											<?php foreach ($product['downloadable_files'] as $downloadable_file) {
												if (preg_match("/^(http:\/\/|https:\/\/|s3:\/\/).*/", $downloadable_file['url'])) {
													$downloadable_link = $downloadable_file['url'];
												} else {
													$downloadable_link = base_url('store/downloadable_file/' . $downloadable_file['name'] . '/' . $downloadable_file['mask'] . '/' . $product['order_id']);
													$downloadable_link .= empty($is_guest) ? '?link=' . encryptString($order['user_id']) : '';
												}
											?>
												<a href="<?php echo $downloadable_link; ?>" class="btn btn-sm btn-outline-secondary me-1 mb-1" target="_blank" download="<?php echo $downloadable_file['mask'] ?>">
													<?php echo $downloadable_file['mask'] ?>
												</a>
											<?php } ?>
										</div>
									</div>
								<?php } } ?>
							</td>
							<td class="text-center">
								<?php
								if (strpos($product['image'], 'http://') === 0 || strpos($product['image'], 'https://') === 0) {
									$image_src = $product['image'];
								} else {
									$image_src = !empty($product['image']) ? base_url('assets/images/product/upload/thumb/' . $product['image']) : base_url('assets/images/no_product_image.png');
								}
								?>
								<img class="img-thumbnail" width="60" src="<?= htmlspecialchars($image_src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= __('store.image') ?>">
							</td>
							<td class="text-end fw-bold"><?php echo c_format($product['price'] + $product['variation_price']); ?></td>
							<td class="text-center"><?php echo $product['quantity']; ?></td>
							<td class="text-end fw-bold"><?php echo c_format($product['total']); ?></td>
						</tr>
						<?php }  ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Sales Funnel Upsells -->
	<?php if (!empty($funnel_upsells)): ?>
	<div class="card shadow-sm mb-4 border-warning">
		<div class="card-header bg-warning text-dark">
			<h5 class="mb-0">
				<i class="fas fa-fire me-2"></i>
				<?= __('store.exclusive_offers') ?>
			</h5>
			<small class="text-muted"><?= __('store.special_discounts_available') ?></small>
		</div>
		<div class="card-body">
			<div class="row">
				<?php foreach ($funnel_upsells as $index => $upsell): ?>
				<div class="col-md-6 col-lg-4 mb-4">
					<div class="card h-100 border-primary upsell-card" data-product-id="<?= $upsell['product_id'] ?>">
						<div class="position-relative">
							<?php
							if (strpos($upsell['product_image'], 'http://') === 0 || strpos($upsell['product_image'], 'https://') === 0) {
								$image_src = $upsell['product_image'];
							} else {
								$image_src = !empty($upsell['product_image']) ? base_url('assets/images/product/upload/thumb/' . $upsell['product_image']) : base_url('assets/images/no_product_image.png');
							}
							?>
							<img class="card-img-top" src="<?= htmlspecialchars($image_src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($upsell['product_name'], ENT_QUOTES, 'UTF-8'); ?>" style="height: 200px; object-fit: cover;">
							<div class="position-absolute top-0 end-0 m-2">
								<span class="badge bg-danger"><?= __('store.upsell') ?> #<?= $index + 1 ?></span>
							</div>
						</div>
						<div class="card-body d-flex flex-column">
							<h6 class="card-title"><?= htmlspecialchars($upsell['product_name'], ENT_QUOTES, 'UTF-8'); ?></h6>
							<p class="card-text text-muted small flex-grow-1">
								<?= htmlspecialchars(substr($upsell['product_description'], 0, 100), ENT_QUOTES, 'UTF-8'); ?>...
							</p>
							<div class="mt-auto">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<span class="h5 text-primary mb-0"><?= c_format($upsell['product_price']) ?></span>
									<small class="text-muted"><?= $upsell['product_sku'] ?></small>
								</div>
								<button class="btn btn-success btn-sm w-100 upsell-buy-btn" 
										data-product-id="<?= $upsell['product_id'] ?>"
										data-product-name="<?= htmlspecialchars($upsell['product_name'], ENT_QUOTES, 'UTF-8'); ?>"
										data-product-price="<?= $upsell['product_price'] ?>">
									<i class="fas fa-shopping-cart me-1"></i>
									<?= __('store.buy_now') ?>
								</button>
								<button class="btn btn-outline-secondary btn-sm w-100 mt-1 upsell-skip-btn" 
										data-product-id="<?= $upsell['product_id'] ?>">
									<i class="fas fa-times me-1"></i>
									<?= __('store.no_thanks') ?>
								</button>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			
			<!-- Upsell Progress -->
			<div class="mt-3">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<small class="text-muted"><?= __('store.upsell_progress') ?></small>
					<small class="text-muted" id="upsell-progress-text">1 / <?= count($funnel_upsells) ?></small>
				</div>
				<div class="progress">
					<div class="progress-bar bg-warning" role="progressbar" style="width: 20%" id="upsell-progress-bar"></div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- Order Totals -->
	<div class="card shadow-sm mb-4">
		<div class="card-body">
			<div class="row justify-content-end">
				<div class="col-md-6 col-lg-4">
					<?php foreach ($totals as $key => $total) { ?>
					<div class="d-flex justify-content-between mb-2 <?= ($key == 'grand_total') ? 'border-top pt-2 fw-bold h5' : '' ?>">
						<span><?= $total['text'] ?>:</span>
						<span><?php echo c_format($total['value']); ?></span>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Payment Information -->
	<div class="card shadow-sm mb-4">
		<div class="card-header bg-info text-white">
			<h5 class="mb-0"><?= __('store.order_payment_info') ?></h5>
		</div>
		<div class="card-body">
			<h6 class="card-subtitle mb-3 text-muted"><?= __('store.payment_details') ?></h6>
			
			<?php if($order['status'] == 0){ ?>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.mode') ?>:</strong></div>
				<div class="col-sm-9"><span class="badge bg-warning text-dark"><?= __('store.waiting_for_payment_status') ?></span></div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.transaction_id') ?>:</strong></div>
				<div class="col-sm-9">-</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.payment_status') ?>:</strong></div>
				<div class="col-sm-9">-</div>
			</div>
			<?php } ?>

			<?php foreach ($payment_history as $key => $value) { ?>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.mode') ?>:</strong></div>
				<div class="col-sm-9"><?php echo str_replace("_", " ", ucwords($value['payment_mode'])) ?></div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.transaction_id') ?>:</strong></div>
				<div class="col-sm-9"><code><?php echo $order['txn_id'];?></code></div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.payment_status') ?>:</strong></div>
				<div class="col-sm-9"><span class="badge bg-success"><?php echo $value['paypal_status'] ?></span></div>
			</div>
			<?php } ?>

			<?php if($order['payment_method'] == 'bank_transfer'){ ?>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.mode') ?>:</strong></div>
				<div class="col-sm-9"><?= __('store.bank_transfer_instruction') ?></div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.transaction_id') ?>:</strong></div>
				<div class="col-sm-9">-</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.payment_status') ?>:</strong></div>
				<div class="col-sm-9"><span class="badge bg-warning text-dark">Pending</span></div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.instructions') ?>:</strong></div>
				<div class="col-sm-9">
					<div class="alert alert-info"><?php echo $paymentsetting['bank_transfer_instruction'] ?></div>
				</div>
			</div>
			<?php } ?>
			
			<?php if($orderProof){ ?>
			<div class="row mb-2">
				<div class="col-sm-3"><strong><?= __('store.payment_proof') ?>:</strong></div>
				<div class="col-sm-9">
					<a href="<?= $orderProof->downloadLink ?>" class="btn btn-sm btn-outline-primary" target='_blank'><?= __('store.download') ?></a>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	
	<!-- Shipping Information -->
	<?php if($order['allow_shipping']){ ?>
	<div class="card shadow-sm mb-4">
		<div class="card-header bg-success text-white">
			<h5 class="mb-0"><?= __('store.shipping_details') ?></h5>
		</div>
		<div class="card-body">
			<h6 class="card-subtitle mb-3 text-muted"><?= __('store.shipping_information') ?></h6>
			
			<div class="row">
				<div class="col-md-6">
					<div class="mb-2">
						<strong><?= __('store.phone') ?>:</strong> <?php echo $order['phone'] ?>
					</div>
					<div class="mb-2">
						<strong><?= __('store.address') ?>:</strong><br>
						<address class="mb-0"><?php echo nl2br($order['address']) ?></address>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-2">
						<strong><?= __('store.country') ?>:</strong> <?php echo $order['country_name'] ?>
					</div>
					<div class="mb-2">
						<strong><?= __('store.state') ?>:</strong> <?php echo $order['state_name'] ?>
					</div>
					<div class="mb-2">
						<strong><?= __('store.city') ?>:</strong> <?php echo $order['city'] ?>
					</div>
					<div class="mb-2">
						<strong><?= __('store.postal_code') ?>:</strong> <?php echo $order['zip_code'] ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<!-- Order Status -->
	<div class="card shadow-sm mb-4">
		<div class="card-header bg-secondary text-white">
			<h5 class="mb-0"><?= __('store.update_order_status') ?></h5>
		</div>
		<div class="card-body">
			<h6 class="card-subtitle mb-3 text-muted"><?= __('store.order_status_history') ?></h6>
			
			<?php if(!$order_history){ ?>
			<div class="text-center text-muted py-3">
				<p><?= __('store.no_any_order_status') ?></p>
			</div>
			<?php } ?>
			
			<?php foreach ($order_history as $key =>$value) { ?>
			<div class="d-flex justify-content-between align-items-start mb-3 p-3 border rounded">
				<div>
					<span class="badge bg-primary">#<?= $key + 1 ?></span>
					<span class="badge bg-info text-dark ms-2"><?= $status[$value['order_status_id']] ?></span>
					<?php if($value['comment']) { ?>
					<div class="mt-2">
						<small class="text-muted"><?= $value['comment'] ?></small>
					</div>
					<?php } ?>
				</div>
				<small class="text-muted"><?= date('M d, Y', strtotime($value['date_added'])) ?></small>
			</div>
			<?php } ?>
		</div>
	</div>

	<!-- Action Buttons -->
	<div class="text-center mb-5">
		<div class="btn-group-vertical btn-group-lg d-md-none" role="group">
			<?php if (!empty($is_guest_track)): ?>
			<a href="<?= base_url('store/track_order') ?>" class="btn btn-outline-primary"><?= __('store.track_your_order') ?></a>
			<?php else: ?>
			<a href="<?= base_url('store/order') ?>" class="btn btn-outline-primary"><?= __('store.orders') ?></a>
			<?php endif; ?>
			<a href="<?= base_url('store') ?>" class="btn btn-outline-secondary"><?= __('store.continue_shopping') ?></a>
			<button class="btn btn-outline-info" onclick="window.print()"><?= __('store.print') ?></button>
		</div>
		<div class="btn-group d-none d-md-inline-flex" role="group">
			<?php if (!empty($is_guest_track)): ?>
			<a href="<?= base_url('store/track_order') ?>" class="btn btn-outline-primary"><?= __('store.track_your_order') ?></a>
			<?php else: ?>
			<a href="<?= base_url('store/order') ?>" class="btn btn-outline-primary"><?= __('store.orders') ?></a>
			<?php endif; ?>
			<a href="<?= base_url('store') ?>" class="btn btn-outline-secondary"><?= __('store.continue_shopping') ?></a>
			<button class="btn btn-outline-info" onclick="window.print()"><?= __('store.print') ?></button>
		</div>
	</div>
</div>

<!-- Footer -->
<div class="container-fluid bg-dark text-white py-4 mt-5">
    <div class="text-center">
        <p class="mb-0"><?= ($store_setting['footer'] != '') ? $store_setting['footer'] : __('store.all_rights_reserved')." ".date('Y')."."?></p>
    </div>
</div>

<script>
	function getUrlParameter(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	if(getUrlParameter('print')==1) window.print();

	// Sales Funnel Upsells JavaScript
	<?php if (!empty($funnel_upsells)): ?>
	document.addEventListener('DOMContentLoaded', function() {
		let currentUpsellIndex = 0;
		const totalUpsells = <?= count($funnel_upsells) ?>;
		const upsellCards = document.querySelectorAll('.upsell-card');
		
		// Initialize - show only first upsell
		showUpsell(currentUpsellIndex);
		
		// Buy button handlers
		document.querySelectorAll('.upsell-buy-btn').forEach(btn => {
			btn.addEventListener('click', function() {
				const productId = this.dataset.productId;
				const productName = this.dataset.productName;
				const productPrice = this.dataset.productPrice;
				
				// Show confirmation
				if (confirm('<?= __('store.confirm_purchase') ?>: ' + productName + ' - ' + productPrice + '?')) {
					// Redirect to product purchase page
					window.location.href = '<?= base_url('store/product/') ?>' + productId;
				}
			});
		});
		
		// Skip button handlers
		document.querySelectorAll('.upsell-skip-btn').forEach(btn => {
			btn.addEventListener('click', function() {
				const productId = this.dataset.productId;
				
				// Hide current upsell card
				const currentCard = document.querySelector('.upsell-card[data-product-id="' + productId + '"]');
				if (currentCard) {
					currentCard.style.display = 'none';
				}
				
				// Show next upsell
				currentUpsellIndex++;
				if (currentUpsellIndex < totalUpsells) {
					showUpsell(currentUpsellIndex);
				} else {
					// All upsells completed
					hideUpsellSection();
				}
			});
		});
		
		function showUpsell(index) {
			// Hide all upsells
			upsellCards.forEach(card => {
				card.style.display = 'none';
			});
			
			// Show current upsell
			if (upsellCards[index]) {
				upsellCards[index].style.display = 'block';
			}
			
			// Update progress
			updateProgress(index + 1, totalUpsells);
		}
		
		function updateProgress(current, total) {
			const progressBar = document.getElementById('upsell-progress-bar');
			const progressText = document.getElementById('upsell-progress-text');
			
			const percentage = (current / total) * 100;
			progressBar.style.width = percentage + '%';
			progressText.textContent = current + ' / ' + total;
		}
		
		function hideUpsellSection() {
			const upsellSection = document.querySelector('.card.border-warning');
			if (upsellSection) {
				upsellSection.style.display = 'none';
			}
		}
	});
	<?php endif; ?>
</script>

</body>
</html>