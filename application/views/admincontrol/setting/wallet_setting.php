<div class="container-fluid px-4 pb-4">
	<?php $this->load->view('admincontrol/users/_wallet_nav'); ?>
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="mb-0 fw-bold"><?= __('admin.wallet_settings') ?></h5>
						<small class="text-muted"><?= __('admin.configure_wallet_behavior') ?></small>
					</div>
				</div>
				<div class="card-body">
					<form method="post" action="" enctype="multipart/form-data" id="setting-form">
						<div class="row g-4">
							<!-- Withdrawal Settings Section -->
							<div class="col-12">
								<div class="card border-0 bg-light">
									<div class="card-header bg-transparent border-0 pb-0">
										<h6 class="mb-0 text-primary">
											<i class="fas fa-cogs me-2"></i><?= __('admin.withdrawal_settings') ?>
										</h6>
									</div>
									<div class="card-body">
										<div class="withdrawal_to_wallet_panel">
											<div class="mb-4">
												<label class="form-label fw-semibold">
													<i class="fas fa-hand-holding-usd me-1 text-primary"></i><?= __('admin.withdrawal_to_wallet') ?>
												</label>
												<select class="form-select" id="wallet_auto_withdrawal_select" name="site[wallet_auto_withdrawal]">
													<option value="0" <?= (int)$site['wallet_auto_withdrawal'] == 0 ? 'selected' : '' ?>><?= __('admin.manually_withdrawal') ?></option>
													<option value="1" <?= $site['wallet_auto_withdrawal'] == 1 ? 'selected' : '' ?>><?= __('admin.auto_withdrawal_using_cron_job') ?></option>
												</select>
												<div class="form-text"><?= __('admin.choose_withdrawal_method') ?></div>
											</div>
											
											<div class="withdrawal_to_wallet_sub_panel" style="display: none;">
												<div class="row g-3">
													<div class="col-md-6">
														<label class="form-label fw-semibold">
															<i class="fas fa-calendar-days me-1 text-info"></i><?= __('admin.days_records_old_from_today') ?>
														</label>
														<input name="site[wallet_auto_withdrawal_days]" value="<?= $site['wallet_auto_withdrawal_days']; ?>" class="form-control" type="number" min="1" placeholder="<?= __('admin.enter_days') ?>">
														<div class="form-text"><?= __('admin.days_old_records_help') ?></div>
													</div>
													<div class="col-md-6">
														<label class="form-label fw-semibold">
															<i class="fas fa-list-ol me-1 text-info"></i><?= __('admin.limit_of_record_auto_withdrawal_per_time') ?>
														</label>
														<input name="site[wallet_auto_withdrawal_limit]" value="<?= $site['wallet_auto_withdrawal_limit']; ?>" class="form-control" type="number" min="1" max="1000000" placeholder="<?= __('admin.enter_limit') ?>">
														<div class="form-text"><?= __('admin.auto_withdrawal_limit_help') ?></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Default Status Settings Section -->
							<div class="col-12">
								<div class="card border-0 bg-light">
									<div class="card-header bg-transparent border-0 pb-0">
										<h6 class="mb-0 text-primary">
											<i class="fas fa-toggle-on me-2"></i><?= __('admin.default_status_settings') ?>
										</h6>
									</div>
									<div class="card-body">
										<div class="row g-3">
											<div class="col-md-6">
												<label class="form-label fw-semibold">
													<i class="fas fa-user-check me-1 text-success"></i><?= __('admin.default_action_status') ?>
												</label>
												<select class="form-select" name="referlevel[default_action_status]">
													<option value="0" <?= (int)$referlevel['default_action_status'] == 0 ? 'selected' : '' ?>><?= __('admin.on_hold') ?></option>
													<option value="1" <?= (int)$referlevel['default_action_status'] == 1 ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
												</select>
												<div class="form-text"><?= __('admin.default_action_status_help') ?></div>
											</div>
											<div class="col-md-6">
												<label class="form-label fw-semibold">
													<i class="fas fa-external-link-alt me-1 text-warning"></i><?= __('admin.default_external_order_status') ?>
												</label>
												<select class="form-select" name="referlevel[default_external_order_status]">
													<option value="0" <?= (int)$referlevel['default_external_order_status'] == 0 ? 'selected' : '' ?>><?= __('admin.on_hold') ?></option>
													<option value="1" <?= (int)$referlevel['default_external_order_status'] == 1 ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
												</select>
												<div class="form-text"><?= __('admin.default_external_order_status_help') ?></div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Amount Limits Section -->
							<div class="col-12">
								<div class="card border-0 bg-light">
									<div class="card-header bg-transparent border-0 pb-0">
										<h6 class="mb-0 text-primary">
											<i class="fas fa-dollar-sign me-2"></i><?= __('admin.amount_limits') ?>
										</h6>
									</div>
									<div class="card-body">
										<div class="row g-3">
											<div class="col-md-6">
												<label class="form-label fw-semibold">
													<i class="fas fa-arrow-down me-1 text-danger"></i><?= __('admin.minimum_withdraw') ?>
													<small class="text-muted">(<?= __('admin.set_to_zero_or_empty_to_Disable') ?>)</small>
												</label>
												<input name="site[wallet_min_amount]" value="<?= $site['wallet_min_amount']; ?>" class="form-control" type="number" min="0" step="0.01" onblur="return onWallentMinamountChange()" id="txt_wallet_min_amount" placeholder="<?= __('admin.enter_minimum_amount') ?>">
												<div class="form-text"><?= __('admin.minimum_withdraw_help') ?></div>
											</div>
											<div class="col-md-6">
												<label class="form-label fw-semibold">
													<i class="fas fa-arrow-up me-1 text-success"></i><?= __('admin.maximum_withdraw_amount') ?>
													<small class="text-muted">(<?= __('admin.set_to_zero_or_empty_to_Disable') ?>)</small>
												</label>
												<input name="site[wallet_max_amount]" value="<?= $site['wallet_max_amount']; ?>" class="form-control" type="number" min="0" step="0.01" onblur="return onWallentMaxamountChange()" id="txt_wallet_max_amount" placeholder="<?= __('admin.enter_maximum_amount') ?>">
												<div class="form-text"><?= __('admin.maximum_withdraw_help') ?></div>
											</div>
										</div>
										
										<div class="mb-3" id="wallet_min_message_panel">
											<label class="form-label fw-semibold">
												<i class="fas fa-comment me-1 text-info"></i><?= __('admin.minimum_withdraw_message') ?>
											</label>
											<input name="site[wallet_min_message_new]" class="form-control" value="<?= $site['wallet_min_message_new'] != '' ? $site['wallet_min_message_new'] : __('admin.the_minimum_limit_is'); ?>" placeholder="<?= __('admin.enter_custom_message') ?>" />
											<div class="form-text"><?= __('admin.minimum_withdraw_message_help') ?></div>
										</div>
									</div>
								</div>
							</div>

							<!-- Action Buttons -->
							<div class="col-12">
								<div class="d-flex justify-content-end gap-2">
									<button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
										<i class="fas fa-undo me-1"></i><?= __('admin.reset') ?>
									</button>
									<button type="submit" class="btn btn-primary btn-submit">
										<i class="fas fa-save me-1"></i><?= __('admin.save_settings') ?>
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
	$(document).ready(function() {
		ShowHideAutoWithdrawalPanel();
		initializeFormValidation();
	});

	function ShowHideAutoWithdrawalPanel() {
		const autoWithdrawal = $("#wallet_auto_withdrawal_select").val();
		const subPanel = $(".withdrawal_to_wallet_sub_panel");
		const messagePanel = $("#wallet_min_message_panel");
		
		if (parseInt(autoWithdrawal) > 0) {
			subPanel.slideDown(300);
			messagePanel.slideUp(300);
		} else {
			subPanel.slideUp(300);
			messagePanel.slideDown(300);
		}
	}

	function initializeFormValidation() {
		$("#wallet_auto_withdrawal_select").on('change', function() {
			ShowHideAutoWithdrawalPanel();
		});

		$("#setting-form").on('submit', function(evt) {
			evt.preventDefault();
			submitForm();
		});

		$("#txt_wallet_min_amount, #txt_wallet_max_amount").on('blur', function() {
			validateAmountLimits();
		});
	}

	function validateAmountLimits() {
		const minAmount = parseFloat($("#txt_wallet_min_amount").val()) || 0;
		const maxAmount = parseFloat($("#txt_wallet_max_amount").val()) || 0;
		
		if (minAmount > 0 && maxAmount > 0 && minAmount >= maxAmount) {
			showToast('<?= __('admin.warning') ?>', '<?= __('admin.minimum_amount_cannot_be_greater_than_maximum') ?>', 'warning', 5000);
		}
	}

	function resetForm() {
		if (confirm('<?= __('admin.are_you_sure_to_reset_form') ?>')) {
			$("#setting-form")[0].reset();
			ShowHideAutoWithdrawalPanel();
			showToast('<?= __('admin.success') ?>', '<?= __('admin.form_reset_successfully') ?>', 'success', 3000);
		}
	}

	function submitForm() {
		const submitBtn = $(".btn-submit");
		const originalText = submitBtn.html();
		
		submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.saving') ?>...');
		
		const formData = new FormData($("#setting-form")[0]);
		const filteredData = formDataFilter(formData);
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: filteredData,
			success: function(result) {
				submitBtn.prop('disabled', false).html(originalText);
				$(".alert-dismissable").remove();
				$("#setting-form").find(".has-error").removeClass("has-error");
				$("#setting-form").find("span.text-danger").remove();
				
				if (result['location']) {
					window.location = result['location'];
					return;
				}

				if (result['success']) {
					showToast('<?= __('admin.success') ?>', result['success'], 'success', 4000);
					$("html, body").animate({scrollTop: 0}, 500);
				}

				if (result['errors']) {
					showToast('<?= __('admin.error') ?>', result['errors'], 'error', 6000);
				}
			},
			error: function(xhr, status, error) {
				submitBtn.prop('disabled', false).html(originalText);
				showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_save_settings') ?>', 'error', 5000);
			}
		});
	}

	function onWallentMinamountChange() {
		const minAmount = $("#txt_wallet_min_amount").val();
		if (minAmount && parseFloat(minAmount) > 0) {
			$("#wallet_min_message_panel").show();
		}
	}

	function onWallentMaxamountChange() {
		validateAmountLimits();
	}
</script>