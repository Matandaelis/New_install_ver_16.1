<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/users/_wallet_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm wallet-details-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold"><?= __('admin.withdraw_requests_details') ?> #<?= $request['id'] ?></h5>
                        <a href="<?= base_url('admincontrol/wallet_requests_list') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i><?= __('admin.back_to_list') ?>
                        </a>
                    </div>
                </div>
                <div class="card-body">
				<?php $wallet_details_flash_error = $this->session->flashdata('error'); ?>
				<?php if ($wallet_details_flash_error) { ?>
				<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
					<i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars((string) $wallet_details_flash_error) ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				<?php } ?>
				<?php if (!empty($mass_payout_paid_info) && !empty($mass_payout_paid_info['batch_id'])) { ?>
				<div class="alert alert-success border-0 shadow-sm mb-4" role="status">
					<div class="d-flex flex-wrap gap-2 align-items-start">
						<i class="fas fa-check-circle fa-lg text-success mt-1"></i>
						<div class="flex-grow-1 small">
							<strong class="d-block mb-1"><?= __('admin.wallet_details_mass_paid_title') ?></strong>
							<p class="mb-2"><?= sprintf(
								__('admin.wallet_details_mass_paid_body'),
								(int) $mass_payout_paid_info['batch_id'],
								htmlspecialchars(strtoupper((string) ($mass_payout_paid_info['processor'] ?? '')))
							) ?></p>
							<?php if (!empty($mass_payout_paid_info['reconciliation_at'])) { ?>
								<p class="mb-1 text-body-secondary"><span class="fw-semibold"><?= __('admin.wallet_details_mass_paid_reconciled_at') ?></span> <?= htmlspecialchars((string) $mass_payout_paid_info['reconciliation_at']) ?></p>
							<?php } ?>
							<?php if (!empty($mass_payout_paid_info['provider_txn_id'])) { ?>
								<p class="mb-2 text-body-secondary"><span class="fw-semibold"><?= __('admin.wallet_details_mass_paid_provider_txn') ?></span> <?= htmlspecialchars((string) $mass_payout_paid_info['provider_txn_id']) ?></p>
							<?php } ?>
							<a href="<?= base_url('admincontrol/mass_payout') ?>#mass-payout-batch-<?= (int) $mass_payout_paid_info['batch_id'] ?>" class="btn btn-sm btn-outline-success">
								<i class="fas fa-file-export me-1"></i><?= __('admin.mass_payout_open_page') ?>
							</a>
						</div>
					</div>
				</div>
				<?php } elseif (!empty($request['batch_export_id'])) { ?>
				<div class="alert alert-warning border-0 shadow-sm mb-4" role="status">
					<div class="d-flex flex-wrap gap-2 align-items-start">
						<i class="fas fa-layer-group fa-lg text-secondary mt-1"></i>
						<div class="flex-grow-1 small">
							<strong class="d-block mb-1"><?= __('admin.wallet_details_mass_batch_title') ?> #<?= (int) $request['batch_export_id'] ?></strong>
							<p class="mb-2"><?= __('admin.wallet_details_mass_batch_body') ?></p>
							<ul class="mb-3 ps-3">
								<li><?= __('admin.wallet_details_mass_batch_li1') ?></li>
								<li><?= __('admin.wallet_details_mass_batch_li2') ?></li>
								<li><?= __('admin.wallet_details_mass_batch_li3') ?></li>
							</ul>
							<a href="<?= base_url('admincontrol/mass_payout') ?>#mass-payout-batch-<?= (int) $request['batch_export_id'] ?>" class="btn btn-sm btn-primary">
								<i class="fas fa-external-link-alt me-1"></i><?= __('admin.mass_payout_open_page') ?>
							</a>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="row g-3 mb-4">
					<div class="col-lg-4 col-md-6">
						<div class="card h-100">
							<div class="card-header bg-info text-white">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-info-circle me-2"></i><?= __('admin.request_details') ?>
								</h6>
							</div>
							<div class="card-body">
								<table class="table table-borderless mb-0">
									<tbody>
										<tr>
											<td class="fw-semibold text-muted"><?= __('admin.id') ?></td>
											<td class="text-end"><?= $request['id'] ?></td>
										</tr>
										<tr>
											<td class="fw-semibold text-muted"><?= __('admin.total') ?></td>
											<td class="text-end fw-bold text-success"><?= c_format($request['total']) ?></td>
										</tr>
										<tr>
											<td class="fw-semibold text-muted"><?= __('admin.payment_method') ?></td>
											<td class="text-end">
												<span class="badge bg-primary"><?= $request['prefer_method'] ?></span>
											</td>
										</tr>
										<tr>
											<td class="fw-semibold text-muted"><?= __('admin.payment_status') ?></td>
											<td class="text-end"><?= withdrwal_status($request['status']) ?></td>
										</tr>
										<?php if (!empty($request['batch_export_id']) && !empty($payout_batch)) { ?>
										<tr>
											<td class="fw-semibold text-muted"><?= __('admin.mass_payout_linked_batch') ?></td>
											<td class="text-end">
												<span class="badge bg-dark me-1">#<?= (int) $request['batch_export_id'] ?></span>
												<span class="badge bg-secondary text-uppercase"><?= htmlspecialchars($payout_batch['processor']) ?></span>
												<a href="<?= base_url('admincontrol/mass_payout') ?>#mass-payout-batch-<?= (int) $request['batch_export_id'] ?>" class="btn btn-sm btn-outline-primary ms-1" title="<?= htmlspecialchars(__('admin.mass_payout_go_mass_page_hint')) ?>">
													<i class="fas fa-file-export me-1"></i><?= __('admin.mass_payout_open_page') ?>
												</a>
											</td>
										</tr>
										<?php } elseif (!empty($request['batch_export_id'])) { ?>
										<tr>
											<td class="fw-semibold text-muted"><?= __('admin.mass_payout_linked_batch') ?></td>
											<td class="text-end">
												<span class="badge bg-dark me-1">#<?= (int) $request['batch_export_id'] ?></span>
												<a href="<?= base_url('admincontrol/mass_payout') ?>#mass-payout-batch-<?= (int) $request['batch_export_id'] ?>" class="btn btn-sm btn-outline-primary"><?= __('admin.mass_payout_open_page') ?></a>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="card h-100">
							<div class="card-header bg-success text-white">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-user-check me-2"></i><?= __('admin.submitted_details') ?>
								</h6>
							</div>
							<div class="card-body">
								<table class="table table-borderless mb-0">
									<tbody>
										<?php
											$data = json_decode($request['settings'],1);
											foreach ($data as $key => $value) {
												if($key == 'payment_proof') {
													$payment_proof = '<tr>
													<td class="fw-semibold text-muted text-capitalize">'.str_replace("_", " ", $key) .'</td>
													<td class="text-end"> <a target="_blank" href="'.base_url('assets/user_upload/'.$value).'" class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i>'.$value.'</a></td>
												</tr>';
												continue;
												}
										 ?>
												<tr>
												<td class="fw-semibold text-muted text-capitalize"><?= str_replace("_", " ", $key) ?></td>
												<td class="text-end">
													<?php 
													if(is_array($value)) {
														if($key == 'payment_details') {
															// For payment details, show a summary
															echo count($value) . ' fields provided';
														} else {
															echo htmlspecialchars(json_encode($value));
														}
													} else {
														echo htmlspecialchars($value);
													}
													?>
												</td>
											</tr>
										<?php } ?>

										<?php if(isset($payment_proof)) echo $payment_proof; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-12">
						<div class="card h-100">
							<div class="card-header bg-warning text-dark">
								<div class="d-flex justify-content-between align-items-center">
									<h6 class="card-title mb-0 fw-semibold">
										<i class="fas fa-history me-2"></i><?= __('admin.status_history') ?>
									</h6>
									<button class="btn btn-outline-dark btn-sm" onclick="getHistory()" title="<?= __('admin.refresh') ?>">
										<i class="fas fa-sync-alt"></i>
									</button>
								</div>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table table-hover mb-0">
										<thead class="table-light">
											<tr>
												<th class="fw-semibold"><?= __('admin.status') ?></th>
												<th class="fw-semibold"><?= __('admin.comment') ?></th>
											</tr>
										</thead>
										<tbody id="history_container">
											<tr>
												<td colspan="2" class="text-center text-muted py-3">
													<i class="fas fa-spinner fa-spin me-2"></i><?= __('admin.loading') ?>...
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				

				<div class="row mt-4">
					<div class="col-12">
						<div class="card border shadow-sm">
							<div class="card-header bg-light text-dark border-bottom py-3">
								<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
									<h6 class="card-title mb-0 fw-semibold">
										<i class="fas fa-list me-2 text-primary"></i><?= __('admin.transactions') ?>
									</h6>
									<div class="d-flex align-items-center gap-3">
										<div class="text-muted small" id="transactions-summary">
											<?= pagination_summary_html(1, 15, count($transaction), 'start', 'sm') ?>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table table-hover mb-0 wallet-details-table" id="transactions-table">
										<thead class="table-light wallet-details-thead">
											<tr>
												<th class="fw-semibold"><?= __('admin.date') ?></th>
												<th class="fw-semibold"><?= __('admin.user') ?></th>
												<th class="fw-semibold"><?= __('admin.order') ?></th>
												<th class="fw-semibold text-end" width="150px"><?= __('admin.commission') ?></th>
												<th class="fw-semibold"><?= __('admin.type') ?></th>
												<th class="fw-semibold"><?= __('admin.payment_status') ?></th>
												<th class="fw-semibold"><?= __('admin.commission_status') ?></th>
												<th class="fw-semibold text-center" width="60px"><?= __('admin.ip') ?></th>
												<th class="fw-semibold text-center" width="80px"><?= __('admin.actions') ?></th>
											</tr>
										</thead>
										<tbody id="transactions-tbody">
											<?php
												foreach ($transaction as $key => $value) {
													$data = [];
													$data['value'] = $value;
													$data['class'] = $class;
													$data['stop_checkbox'] = 1; 
													$data['stop_child'] = 1; 
													$data['wallet_status'] = $status; 
													$data['hide_recursion_btn'] = true; 
													echo $this->Product_model->getHtml('usercontrol/users/parts/new_wallet_tr', $data);
												} 
											?>
										</tbody>
									</table>
								</div>
								<?php if(count($transaction) > 15): ?>
								<div class="card-footer bg-light wallet-details-pagination">
									<div class="d-flex justify-content-between align-items-center">
										<div class="text-muted small" id="pagination-summary">
											<?= pagination_summary_html(1, 15, count($transaction), 'start', 'sm') ?>
										</div>
										<div id="pagination-links">
											<?= easy_pagination(base_url('admincontrol/wallet_requests_details/' . $request['id']), count($transaction), 0, ['per_page' => 15, 'size' => 'sm']) ?>
										</div>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<?php
				$wallet_mass_strip_hidden = (!empty($mass_payout_paid_info) && !empty($mass_payout_paid_info['batch_id']))
					|| !empty($request['batch_export_id']);
				if (!$wallet_mass_strip_hidden && (!empty($wallet_mass_prepare_available) || !empty($wallet_mass_go_export_only))) {
					$this->load->view('admincontrol/users/part/wallet_mass_payout_strip', array(
						'request' => $request,
						'wallet_mass_prepare_csrf' => isset($wallet_mass_prepare_csrf) ? $wallet_mass_prepare_csrf : '',
						'wallet_mass_prepare_available' => !empty($wallet_mass_prepare_available),
						'wallet_mass_go_export_only' => !empty($wallet_mass_go_export_only),
					));
				}
				?>

				<?php 
				// Display custom gateway payment details if available
				$settings = json_decode($request['settings'], true);
				if(isset($settings['payment_details']) && !empty($settings['payment_details']) && isset($request['prefer_method'])) {
					$payment_details = $settings['payment_details'];
					$gateway_code = $request['prefer_method'];
				?>
				<div class="row mt-4">
					<div class="col-12">
						<div class="card">
							<div class="card-header bg-warning text-dark">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-credit-card me-2"></i><?= __('admin.payment_details') ?> - <?= ucfirst(str_replace('_', ' ', $gateway_code)) ?>
								</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<?php foreach($payment_details as $field => $value): ?>
									<div class="col-md-6 mb-3">
										<label class="form-label fw-semibold text-muted"><?= ucfirst(str_replace('_', ' ', $field)) ?>:</label>
										<p class="form-control-plaintext bg-light p-2 rounded">
											<?php 
											if(is_array($value)) {
												echo htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT));
											} else {
												echo htmlspecialchars($value);
											}
											?>
										</p>
									</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="row mt-4">
					<div class="col-lg-8 col-md-12">
						<div class="card">
							<div class="card-header bg-info text-white">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-cogs me-2"></i><?= __('admin.actions') ?>
								</h6>
							</div>
							<div class="card-body">
								<?= $confirm ?>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-12">
						<div class="card border-0 shadow-sm h-100 wallet-details-status-card">
							<div class="card-header bg-secondary text-white">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-plus-circle me-2"></i><?= __('admin.add_custom_status_history') ?>
								</h6>
							</div>
							<div class="card-body">
								<p class="small text-muted mb-3"><?= __('admin.wallet_details_status_update_hint') ?></p>
								<form class="add-history-form wallet-details-form" id="status-history-form">
									<div class="mb-3">
										<label class="form-label fw-semibold" for="wallet-status-select"><?= __('admin.status') ?> <span class="text-danger">*</span></label>
										<select class="form-select" id="wallet-status-select" name="status" required>
											<option value=""><?= __('admin.select_status') ?></option>
											<?php
											$status_groups = array(
												'withdrawal_status_group_flow' => array('0', '13', '12', '7'),
												'withdrawal_status_group_outcome' => array('1', '8', '9', '10', '11'),
												'withdrawal_status_group_issue' => array('2', '3', '4', '5'),
											);
											foreach ($status_groups as $group_lang_key => $ids) {
												$opts = array();
												foreach ($ids as $sid) {
													if (isset($status_list[$sid])) {
														$opts[$sid] = $status_list[$sid];
													}
												}
												if (count($opts) < 1) {
													continue;
												}
												?>
												<optgroup label="<?= htmlspecialchars(__('admin.' . $group_lang_key)) ?>">
													<?php foreach ($opts as $key => $value) { ?>
														<option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($value) ?></option>
													<?php } ?>
												</optgroup>
											<?php } ?>
										</select>
									</div>
									<div class="mb-3">
										<label class="form-label fw-semibold" for="wallet-status-comment"><?= __('admin.comment') ?> <span class="text-danger">*</span></label>
										<textarea id="wallet-status-comment" name="comment" class="form-control" rows="3" required placeholder="<?= __('admin.add_status_comment') ?>"></textarea>
									</div>
									<div class="d-grid gap-2">
										<button type="button" class="btn btn-primary btn-add-status" data-close="true">
											<i class="fas fa-check me-1"></i><?= __('admin.add_status_and_close') ?>
										</button>
										<button type="button" class="btn btn-outline-primary btn-add-status">
											<i class="fas fa-plus me-1"></i><?= __('admin.add_status') ?>
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		// Initialize form validation
		$('#status-history-form').on('submit', function(e) {
			e.preventDefault();
			$('.btn-add-status[data-close="true"]').click();
		});

		// Add status button handlers
		$(".btn-add-status").on("click", function(){
			const $el = $(this);
			const $form = $('#status-history-form');
			
			// Validate form
			if (!validateForm($form)) {
				showToast('<?= __('admin.error') ?>', '<?= __('admin.please_fill_required_fields') ?>', 'error', 3000);
				return;
			}
			
			// Store original text
			if (!$el.data('original-text')) {
				$el.data('original-text', $el.html());
			}
			
			$.ajax({
				url: '<?= base_url('admincontrol/wallet_requests_details/' . $request['id']) ?>',
				type:'POST',
				dataType:'json',
				data: $form.serialize(),
				beforeSend:function(){
					$el.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
					$form.find('.is-invalid').removeClass('is-invalid');
					$form.find('.invalid-feedback').remove();
				},
				complete:function(){
					$el.prop('disabled', false).html($el.data('original-text'));
				},
				success:function(json){
					if (json['success']){
						showToast('<?= __('admin.success') ?>', '<?= __('admin.status_added_successfully') ?>', 'success', 3000);
						
						if($el.data('close')){
							setTimeout(function() {
								window.location.href = '<?= base_url('admincontrol/wallet_requests_list') ?>';
							}, 1000);
						} else {
							if($form.find('select[name=status]').val() == "1"){
								window.location.reload();
							} else{
								getHistory();
							}
							$form[0].reset();
						}
					}
					
					if(json['errors']){
						showToast('<?= __('admin.error') ?>', '<?= __('admin.please_fix_errors') ?>', 'error', 5000);
						$.each(json['errors'], function(i,j){
							const $field = $form.find('[name="'+ i +'"]');
							if($field.length){
								$field.addClass("is-invalid");
								if($field.parent(".input-group").length){
									$field.parent(".input-group").after("<span class='invalid-feedback'>"+ j +"</span>");
								} else{
									$field.after("<span class='invalid-feedback'>"+ j +"</span>");
								}
							}
						});
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', error);
					showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_add_status') ?>', 'error', 5000);
				}
			});
		});
	});

	// Form validation function
	function validateForm($form) {
		let isValid = true;
		$form.find('[required]').each(function() {
			const $field = $(this);
			if (!$field.val().trim()) {
				$field.addClass('is-invalid');
				if (!$field.next('.invalid-feedback').length) {
					$field.after('<span class="invalid-feedback"><?= __('admin.this_field_is_required') ?></span>');
				}
				isValid = false;
			} else {
				$field.removeClass('is-invalid');
				$field.next('.invalid-feedback').remove();
			}
		});
		return isValid;
	}

	function getHistory() {
		$.ajax({
			url:'<?= base_url('admincontrol/get_withdrwal_history/'. $request['id']) ?>',
			type:'POST',
			dataType:'json',
			beforeSend:function(){
				$("#history_container").html("<tr><td colspan='2' class='text-center text-muted py-3'><i class='fas fa-spinner fa-spin me-2'></i><?= __('admin.loading') ?>...</td></tr>");
			},
			success:function(json){
				if(json['html']) {
					$("#history_container").html(json['html']);
				} else {
					$("#history_container").html("<tr><td colspan='2' class='text-center text-muted py-3'><i class='fas fa-info-circle me-2'></i><?= __('admin.no_status_history') ?></td></tr>");
				}
			},
			error: function(xhr, status, error) {
				console.error('History Load Error:', error);
				$("#history_container").html("<tr><td colspan='2' class='text-center text-danger py-3'><i class='fas fa-exclamation-triangle me-2'></i><?= __('admin.failed_to_load_history') ?></td></tr>");
				showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_history') ?>', 'error', 3000);
			}
		});
	}

	getHistory()

	// Initialize popovers with Bootstrap 5
	$(document).delegate('.wallet-popover','click', function(){
		const html = $(this).parents("tr").find(".dpopver-content").html();
        $(this).attr('data-bs-content', html);
        
        // Hide any existing popovers
        $('.popover').remove();
        
        // Initialize Bootstrap 5 popover
        const popover = new bootstrap.Popover(this, {
            placement: 'right',
            html: true,
            trigger: 'manual'
        });
        
        popover.show();
	});

	// Mobile responsiveness improvements
	$(window).on('resize', function() {
		// Adjust table responsiveness on mobile
		if ($(window).width() < 768) {
			$('#transactions-table').addClass('table-sm');
		} else {
			$('#transactions-table').removeClass('table-sm');
		}
	});

	// Initialize on page load
	$(document).ready(function(){
		// Initialize popovers with Bootstrap 5
		$(".wallet-popover").each(function() {
			const html = $(this).parents("tr").find(".dpopver-content").html();
			$(this).attr('data-bs-content', html);
		});

		// Trigger resize event to set initial mobile state
		$(window).trigger('resize');

		// Add loading states to buttons
		$('.btn-add-status').on('mouseenter', function() {
			if (!$(this).prop('disabled')) {
				$(this).addClass('shadow-sm');
			}
		}).on('mouseleave', function() {
			$(this).removeClass('shadow-sm');
		});
	});
</script>