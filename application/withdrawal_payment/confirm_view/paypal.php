<?php if (isset($request)) { 
	$settings = json_decode($request['settings'], true);
	$paypal_email = isset($settings['paypal_email']) ? $settings['paypal_email'] : '';
?>
	<div class="card">
		<div class="card-header bg-primary text-white">
			<h5 class="card-title mb-0">
				<i class="fab fa-paypal me-2"></i><?= __('admin.paypal_payment_processing') ?>
			</h5>
		</div>
		<div class="card-body">
			<div class="row mb-3">
				<div class="col-md-6">
					<strong><?= __('admin.amount') ?>:</strong> <?= c_format($request['total']) ?>
				</div>
				<div class="col-md-6">
					<strong><?= __('admin.paypal_email') ?>:</strong> <?= $paypal_email ?>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-md-6">
					<strong><?= __('admin.request_date') ?>:</strong> <?= date('M d, Y H:i', strtotime($request['created_at'])) ?>
				</div>
				<div class="col-md-6">
					<strong><?= __('admin.status') ?>:</strong> <?= withdrwal_status($request['status']) ?>
				</div>
			</div>
			
			<?php if(empty($paypal_email)): ?>
				<div class="alert alert-warning">
					<i class="fas fa-exclamation-triangle me-2"></i><?= __('admin.paypal_email_missing') ?>
				</div>
				<div class="d-flex flex-wrap align-items-center gap-2">
					<button type="button" class="btn btn-secondary" onclick="window.history.back()">
						<i class="fas fa-arrow-left me-2"></i><?= __('admin.go_back') ?>
					</button>
				</div>
			<?php else: ?>
				<div class="d-flex flex-wrap align-items-center gap-2">
					<button onclick="payWithPaypal()" type="button" class="btn btn-success btn-lg">
						<i class="fab fa-paypal me-2"></i><?= __('admin.process_paypal_payment') ?>
					</button>
					<button type="button" class="btn btn-secondary" onclick="window.history.back()">
						<i class="fas fa-arrow-left me-2"></i><?= __('admin.go_back') ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<script type="text/javascript">
		function payWithPaypal(){
			Swal.fire({
				title: '<?= __('admin.confirm_paypal_payment') ?>',
				html: '<?= __('admin.confirm_paypal_payment_text') ?><br><strong><?= $paypal_email ?></strong><br><?= __('admin.amount') ?>: <strong><?= c_format($request['total']) ?></strong>',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#6c757d',
				confirmButtonText: '<i class="fab fa-paypal me-2"></i><?= __('admin.yes_process_payment') ?>',
				cancelButtonText: '<?= __('admin.cancel') ?>',
				showLoaderOnConfirm: true,
				preConfirm: () => {
					return new Promise((resolve) => {
						window.location.href='<?= base_url('payment/call_payment_function/paypal/doPayment/'. $request['id']) ?>';
						resolve();
					});
				}
			});
		}
	</script>
<?php } ?>