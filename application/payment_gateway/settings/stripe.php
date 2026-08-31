<!-- SECTION 1: Regular Stripe Checkout (Existing Feature) -->
<div class="card mb-4 border-primary">
	<div class="card-header bg-primary text-white">
		<h5 class="mb-0"><i class="fas fa-credit-card me-2"></i><?= __('admin.regular_stripe_checkout') ?></h5>
	</div>
	<div class="card-body">
		<p class="text-muted mb-3">
			<i class="fas fa-info-circle me-1"></i>
			<?= __('admin.regular_checkout_description') ?>
		</p>
		
		<div class="mb-3">
			<label class="form-label"><?= __('admin.store_payment_status') ?></label>
			<select class="form-select" name="store">
				<option <?= (int)$setting_data['store']['status'] === 0 ? 'selected' : '' ?> value="0"><?= __('admin.disabled') ?></option>
				<option <?= (int)$setting_data['store']['status'] === 1 ? 'selected' : '' ?> value="1"><?= __('admin.enabled') ?></option>
			</select>
		</div>

		<div class="mb-3">
			<label class="form-label"><?= __('admin.deposit_payment_status') ?></label>
			<select class="form-select" name="deposit">
				<option <?= (int)$setting_data['deposit']['status'] === 0 ? 'selected' : '' ?> value="0"><?= __('admin.disabled') ?></option>
				<option <?= (int)$setting_data['deposit']['status'] === 1 ? 'selected' : '' ?> value="1"><?= __('admin.enabled') ?></option>
			</select>
		</div>

		<div class="mb-0">
			<label class="form-label"><?= __('admin.membership_payment_status') ?></label>
			<select class="form-select" name="membership">
				<option <?= (int)$setting_data['membership']['status'] === 0 ? 'selected' : '' ?> value="0"><?= __('admin.disabled') ?></option>
				<option <?= (int)$setting_data['membership']['status'] === 1 ? 'selected' : '' ?> value="1"><?= __('admin.enabled') ?></option>
			</select>
		</div>
	</div>
</div>

<!-- SECTION 2: Stripe Direct Checkout (NEW Feature) -->
<div class="card mb-4 border-success">
	<div class="card-header bg-success text-white">
		<h5 class="mb-0"><i class="fas fa-external-link-alt me-2"></i><?= __('admin.stripe_direct_checkout') ?></h5>
	</div>
	<div class="card-body">
		<p class="text-muted mb-3">
			<i class="fas fa-info-circle me-1"></i>
			<?= __('admin.direct_checkout_description') ?>
		</p>
		
		<div class="mb-0">
			<label class="form-label"><?= __('admin.integration_payment_status') ?></label>
			<select class="form-select" name="integration">
				<option <?= (int)$setting_data['integration']['status'] === 0 ? 'selected' : '' ?> value="0"><?= __('admin.disabled') ?></option>
				<option <?= (int)$setting_data['integration']['status'] === 1 ? 'selected' : '' ?> value="1"><?= __('admin.enabled') ?></option>
			</select>
			<small class="text-muted">
				<i class="fas fa-lightbulb me-1"></i>
				<?= __('admin.enable_integration_tools_stripe') ?>
			</small>
		</div>
	</div>
</div>

<!-- SECTION 3: API Keys Configuration -->
<div class="card mb-4 border-warning">
	<div class="card-header bg-warning">
		<h5 class="mb-0"><i class="fas fa-key me-2"></i><?= __('admin.stripe_api_keys_shared') ?></h5>
	</div>
	<div class="card-body">
		<div class="alert alert-info mb-3">
			<h6 class="alert-heading mb-2"><i class="fas fa-info-circle me-2"></i><?= __('admin.get_stripe_api_keys') ?></h6>
			<ol class="mb-0 ps-3 small">
				<li><?= __('admin.stripe_api_step_1') ?></li>
				<li><?= __('admin.stripe_api_step_2') ?></li>
				<li><?= __('admin.stripe_api_step_3') ?></li>
			</ol>
		</div>

		<div class="mb-3">
			<label class="form-label"><?= __('admin.environment') ?></label>
			<select class="form-select" name="environment">
				<option <?= (int)$setting_data['environment'] === 0 ? 'selected' : '' ?> value="0"><?= __('admin.test_mode') ?></option>
				<option <?= (int)$setting_data['environment'] === 1 ? 'selected' : '' ?> value="1"><?= __('admin.live_mode') ?></option>
			</select>
			<small class="text-muted"><i class="fas fa-lightbulb me-1"></i><?= __('admin.start_test_mode_tip') ?></small>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="mb-3">
					<label class="form-label">
						<?= __('admin.test_public_key') ?> 
						<span class="badge bg-primary"><?= __('admin.for_regular_checkout') ?></span>
					</label>
					<input class="form-control" name="test_public_key" value="<?= $setting_data['test_public_key'] ?>" placeholder="pk_test_xxxxx">
					<small class="text-muted"><?= __('admin.required_for_store_deposit_membership') ?></small>
				</div>
			</div>
			<div class="col-md-6">
				<div class="mb-3">
					<label class="form-label">
						<?= __('admin.test_secret_key') ?> 
						<span class="badge bg-danger"><?= __('admin.required_for_both') ?></span>
					</label>
					<input class="form-control" name="test_secret_key" value="<?= $setting_data['test_secret_key'] ?>" placeholder="sk_test_xxxxx">
					<small class="text-muted"><?= __('admin.required_for_all_features') ?></small>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="mb-3">
					<label class="form-label">
						<?= __('admin.live_public_key') ?> 
						<span class="badge bg-primary"><?= __('admin.for_regular_checkout') ?></span>
					</label>
					<input class="form-control" name="live_public_key" value="<?= $setting_data['live_public_key'] ?>" placeholder="pk_live_xxxxx">
					<small class="text-muted"><?= __('admin.required_for_store_deposit_membership') ?></small>
				</div>
			</div>
			<div class="col-md-6">
				<div class="mb-0">
					<label class="form-label">
						<?= __('admin.live_secret_key') ?> 
						<span class="badge bg-warning text-dark"><?= __('admin.for_production') ?></span>
					</label>
					<input class="form-control" name="live_secret_key" value="<?= $setting_data['live_secret_key'] ?>" placeholder="sk_live_xxxxx">
					<small class="text-muted"><?= __('admin.required_for_production_all') ?></small>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- SECTION 4: Webhook Configuration (Only for Integration Tools) -->
<div class="card mb-4 border-success">
	<div class="card-header bg-success text-white">
		<h5 class="mb-0"><i class="fas fa-webhook me-2"></i><?= __('admin.webhook_configuration') ?></h5>
	</div>
	<div class="card-body">
		<p class="text-muted mb-3">
			<i class="fas fa-info-circle me-1"></i>
			<?= __('admin.webhook_only_for_integration') ?>
		</p>

		<div class="mb-3">
			<label class="form-label"><?= __('admin.webhook_secret') ?> <span class="badge bg-success"><?= __('admin.for_integration_tools_only') ?></span></label>
			<input class="form-control" name="webhook_secret" value="<?= $setting_data['webhook_secret'] ?? '' ?>" placeholder="whsec_xxxxx">
		</div>

		<div class="alert alert-warning mb-0">
			<h6 class="alert-heading mb-2"><i class="fas fa-cog me-2"></i><?= __('admin.webhook_setup_instructions') ?></h6>
			<ol class="mb-2 ps-3 small">
				<li><?= __('admin.webhook_step_1') ?></li>
				<li><?= __('admin.webhook_step_2') ?></li>
				<li><?= __('admin.webhook_step_3') ?> <code class="bg-white text-dark px-2 py-1"><?= base_url('stripe_integration/webhook') ?></code> 
					<button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="navigator.clipboard.writeText('<?= base_url('stripe_integration/webhook') ?>'); this.innerHTML='<i class=\'fas fa-check\'></i> <?= __('admin.copied') ?>'">
						<i class="fas fa-copy"></i> <?= __('admin.copy') ?>
					</button>
				</li>
				<li><?= __('admin.webhook_step_4') ?></li>
				<li><?= __('admin.webhook_step_5') ?></li>
				<li><?= __('admin.webhook_step_6') ?></li>
				<li><?= __('admin.webhook_step_7') ?></li>
			</ol>
			<p class="mb-0 small">
				<i class="fas fa-exclamation-triangle me-1"></i>
				<strong><?= __('admin.important') ?>:</strong> <?= __('admin.webhook_localhost_note') ?>
			</p>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label">Order Success Status</label>
	<select name="order_success_status" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name){ 
				if(isset($setting_data['order_success_status']))
					$selected = ($order_status_id == $setting_data['order_success_status']) ? 'selected' : '';
				else 
					$selected = ($order_status_id == 1) ? 'selected' : ''; ?>
				
				<option <?= $selected ?>  value="<?= $order_status_id; ?>"><?= $name ?></option>
		<?php } ?>
	</select>
</div>

<div class="form-group">
	<label class="control-label">Order Failed Status</label>
	<select name="order_failed_status" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name){ 
				if(isset($setting_data['order_failed_status']))
					$selected = ($order_status_id == $setting_data['order_failed_status']) ? 'selected' : '';
				else 
					$selected = ($order_status_id == 5) ? 'selected' : ''; ?>
				
				<option <?= $selected ?>  value="<?= $order_status_id; ?>"><?= $name ?></option>
		<?php } ?>
	</select>
</div>