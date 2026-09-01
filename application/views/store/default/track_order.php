<?php
/**
 * Default theme — Guest order tracking page
 *
 * @contract  Store API v1 — page: track_order
 * @see       Store_cart_payload::page_track_order()
 * @see       /store/api/v1/pages/track_order
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $track_url    string  Form action URL to submit tracking lookup
 *   $track_order  array   Tracked order data (only present after lookup) {id, status, items[], ...}
 *                         Empty array if no lookup performed yet
 */
?>
<section class="amz-auth" style="padding:30px 0;">
<div class="container py-5">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="amz-card card shadow-sm border-top-card" style="border-radius:8px;">
				<div class="amz-card__body card-body p-md-5">
					<h5 class="amz-auth__title sub-title display-5 mb-4"><?= __('store.track_your_order') ?></h5>
					<p class="text-muted mb-4"><?= __('store.track_order_description') ?></p>

					<?php if (!empty($error)): ?>
						<div class="alert alert-danger" role="alert">
							<?= htmlspecialchars($error) ?>
						</div>
					<?php endif; ?>

					<form method="post" action="<?= base_url('store/track_order') ?>">
						<div class="mb-3">
							<label for="order_number" class="form-label"><?= __('store.order_number') ?></label>
							<input type="text" class="form-control" id="order_number" name="order_number" placeholder="<?= __('store.enter_order_number') ?>" value="<?= $track_form_values['order_number'] ?? '' ?>" required>
						</div>
						<div class="mb-3">
							<label for="email" class="form-label"><?= __('store.email_address') ?></label>
							<input type="email" class="form-control" id="email" name="email" placeholder="<?= __('store.enter_email_address') ?>" value="<?= $track_form_values['email'] ?? '' ?>" required>
						</div>
						<button type="submit" class="amz-btn amz-btn-primary w-100 btn btn-primary"><?= __('store.view_order') ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
