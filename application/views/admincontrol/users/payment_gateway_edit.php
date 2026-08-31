<?php
	$db = & get_instance();
	$userdetails = $db->userdetails();
	$store_setting = $db->Product_model->getSettings('store');
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card bg-primary text-white border-0 shadow-sm mb-4">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h2 class="mb-1 text-white"><?= __('admin.payment_settings') ?></h2>
							<p class="text-white opacity-75 mb-0"><?= __('admin.configure_payments') ?>: <strong><?= $payment_gateway['title'] ?></strong></p>
						</div>
						<a href="<?= base_url('admincontrol/payment_gateway') ?>" class="btn btn-outline-light">
							<i class="bi bi-arrow-left me-2"></i><?= __('admin.back_to_control_panel') ?>
						</a>
					</div>
				</div>
			</div>

			<form id="setting-form">
				<div class="card">
					<div class="card-header bg-primary text-white">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<h5 class="card-title mb-0 text-white">
									<i class="bi bi-gear me-2"></i><?= $payment_gateway['title'] ?> <?= __('admin.general_settings') ?>
								</h5>
								<small class="text-white opacity-75"><?= __('admin.configure_general_settings') ?></small>
							</div>
							<button class="btn btn-outline-light btn-submit" type="submit">
								<i class="bi bi-check-circle me-2"></i><?= __('admin.save_settings') ?>
							</button>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-8">
								<?php if($payment_gateway['code'] == 'paystack'){ ?>
									<div class="alert alert-info d-flex align-items-center mb-4">
										<i class="bi bi-info-circle me-2"></i>
										<div><?= __('admin.paystack_accept_only_currency'); ?></div>
									</div>
								<?php } ?>
								<?php if($payment_gateway['code'] == 'xendit'){ ?>
									<div class="alert alert-info d-flex align-items-center mb-4">
										<i class="bi bi-info-circle me-2"></i>
										<div><?= __('admin.xendit_accept_only_currency'); ?></div>
									</div>
								<?php } ?>
								<?php if($payment_gateway['code'] == 'yookassa'){ ?>
									<div class="alert alert-info d-flex align-items-center mb-4">
										<i class="bi bi-info-circle me-2"></i>
										<div><?= __('admin.yookassa_accept_only_currency'); ?></div>
									</div>
								<?php } ?>
								
								<div class="gateway-settings">
									<?= $payment_gateway['setting'] ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="card bg-light border-0">
									<div class="card-body text-center">
										<i class="bi bi-shield-check text-primary mb-3 fs-1"></i>
										<h6 class="card-title"><?= __('admin.secure') ?></h6>
										<p class="card-text text-muted small">
											<?= __('admin.maximum_protection') ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?= performance_indicator_html('gateway-edit-performance') ?>
<?= render_performance_indicator('gateway-edit-performance') ?>

<script type="text/javascript">
	$(document).ready(function() {
		PerformanceMonitor.start();
		PerformanceMonitor.end();
	});
</script>

<script type="text/javascript">
	$("#setting-form").on('submit',function(){
		let isDirtyForm = false;

		$( ".form-control.required" ).each(function() {
			if($(this).val() == "" || $(this).val() == null){
			  	$(this).parent().addClass('has-error');
			  	$(this).after('<span class="text-danger">'+'<?= __('admin.this_field_is_required') ?>'+'</span>');
			  	isDirtyForm = true;
			}
		});

		if(!isDirtyForm){
			$this = $(this);
			let $submitBtn = $this.find('.btn-submit');
			let originalText = $submitBtn.html();
			
			$.ajax({
				type:'POST',
				dataType:'json',
				data:$this.serialize(),
				beforeSend:function(){ $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...'); },
				complete:function(){ $submitBtn.prop('disabled', false).html(originalText); },
				success:function(json){
					if(json['success']) {
						showToast('<?= __('admin.success') ?>', json['success'], 'success', 3000);
					}
					if(json['error']) {
						showToast('<?= __('admin.error') ?>', json['error'], 'error', 5000);
					}
					if(json['redirect']) {
						setTimeout(function() {
							window.location.href = json['redirect'];
						}, 1500);
					}
				},
			});
		}

		return false;
	})
</script>
