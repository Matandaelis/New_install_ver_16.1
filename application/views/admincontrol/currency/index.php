<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h2 class="mb-1"><?= __('admin.currencies') ?></h2>
					<p class="text-muted mb-0"><?= __('admin.manage_system_currencies') ?></p>
				</div>
				<div class="d-flex gap-2">
					<button type="button" class="btn btn-outline-success" id="refresh-rates">
						<i class="bi bi-arrow-clockwise me-2"></i><?= __('admin.refresh_rates') ?>
					</button>
					<a href="<?= base_url('admincontrol/currency_edit/') ?>" class="btn btn-primary">
						<i class="bi bi-plus-circle me-2"></i><?= __("admin.add_new") ?>
					</a>
				</div>
			</div>

			<div class="card">
				<div class="card-header bg-white border-bottom">
					<div class="row align-items-center g-2">
						<div class="col-md-8">
							<h5 class="card-title mb-0 text-dark"><?= __('admin.currency_management') ?></h5>
							<small class="text-muted d-block"><span id="total-currencies-count"><?= count($currencys) ?></span> <?= __('admin.total_currencies') ?></small>
							<?php if(!empty($rates_last_updated)): ?>
								<div class="small text-muted mt-1">
									<i class="bi bi-clock me-1"></i>
									<?= __('admin.currency_rates_last_updated') ?>: <?= date('M j, Y \a\t g:i A', strtotime($rates_last_updated)) ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="col-md-4"></div>
					</div>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead class="table-light">
								<tr>
									<th class="border-0"><?= __('admin.currency') ?></th>
									<th class="border-0 text-center"><?= __('admin.code') ?></th>
									<th class="border-0 text-center"><?= __('admin.symbols') ?></th>
									<th class="border-0 text-center"><?= __('admin.format') ?></th>
									<th class="border-0 text-center"><?= __('admin.value') ?></th>
									<th class="border-0 text-center"><?= __('admin.status') ?></th>
									<th class="border-0 text-center"><?= __('admin.last_updated') ?></th>
									<th class="border-0 text-center"><?= __('admin.actions') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($currencys as $currency){ ?>
									<tr data-currency-id="<?= $currency['currency_id'] ?>" <?= $currency['is_default'] ? 'class="table-warning bg-opacity-25 border-warning border-opacity-50"' : '' ?>>
										<td>
											<div class="d-flex align-items-center">
												<?php if($currency['is_default']): ?>
													<i class="bi bi-star-fill text-warning me-2" title="<?= __('admin.default_currency') ?>"></i>
												<?php endif; ?>
												<div>
													<div class="fw-semibold"><?= $currency['title'] ?></div>
													<?php if($currency['is_default']): ?>
														<small class="text-warning"><?= __('admin.default') ?></small>
													<?php endif; ?>
												</div>
											</div>
										</td>
										<td class="text-center">
											<span class="badge bg-primary"><?= $currency['code'] ?></span>
										</td>
										<td class="text-center">
											<div class="d-flex justify-content-center gap-2">
												<?php if($currency['symbol_left']): ?>
													<span class="badge bg-light text-dark"><?= $currency['symbol_left'] ?></span>
												<?php endif; ?>
												<?php if($currency['symbol_right']): ?>
													<span class="badge bg-light text-dark"><?= $currency['symbol_right'] ?></span>
												<?php endif; ?>
												<?php if(!$currency['symbol_left'] && !$currency['symbol_right']): ?>
													<small class="text-muted">-</small>
												<?php endif; ?>
											</div>
										</td>
										<td class="text-center">
											<small class="text-muted">
												<?= $currency['decimal_place'] ?> <?= __('admin.decimal_places') ?><br>
												<span class="text-primary"><?= $currency['decimal_symbol'] ?></span> | 
												<span class="text-success"><?= $currency['replace_comma_symbol'] ?></span>
											</small>
										</td>
										<td class="text-center">
											<span class="fw-semibold"><?= number_format($currency['value'], 4) ?></span>
										</td>
										<td class="text-center">
											<?php if($currency['status']): ?>
												<span class="badge bg-success"><?= __('admin.active') ?></span>
											<?php else: ?>
												<span class="badge bg-secondary"><?= __('admin.inactive') ?></span>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<small class="text-muted"><?= date('M j, Y', strtotime($currency['date_modified'])) ?></small>
										</td>
										<td class="text-center">
											<div class="btn-group btn-group-sm">
												<a href="<?= base_url('admincontrol/currency_edit/'. $currency['currency_id']) ?>" 
												   class="btn btn-outline-primary" 
												   title="<?= __('admin.edit') ?>">
													<i class="bi bi-pencil"></i>
												</a>
												<?php if(!$currency['is_default']): ?>
													<button type="button" 
															class="btn btn-outline-warning btn-set-default" 
															data-currency-id="<?= $currency['currency_id'] ?>"
															data-currency-name="<?= $currency['title'] ?>"
															data-currency-code="<?= $currency['code'] ?>"
															title="<?= __('admin.set_as_default') ?>">
														<i class="bi bi-star"></i>
													</button>
													<button type="button" 
															class="btn btn-outline-danger btn-delete" 
															data-currency-id="<?= $currency['currency_id'] ?>"
															data-currency-name="<?= $currency['title'] ?>"
															title="<?= __('admin.delete') ?>">
														<i class="bi bi-trash"></i>
													</button>
												<?php else: ?>
													<button type="button" 
															class="btn btn-warning" 
															disabled
															title="<?= __('admin.current_default_currency') ?>">
														<i class="bi bi-star-fill"></i>
													</button>
													<button type="button" 
															class="btn btn-outline-secondary" 
															disabled
															title="<?= __('admin.cannot_delete_default') ?>">
														<i class="bi bi-shield-check"></i>
													</button>
												<?php endif; ?>
											</div>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	// Function to perform the set default AJAX call
	function performSetDefault(currencyId, currencyName) {
		
		$.ajax({
			url: '<?= base_url('admincontrol/currency_set_default') ?>',
			type: 'POST',
			dataType: 'json',
			data: { currency_id: currencyId },
			success: function(response) {
				if (response.success) {
					showToast('<?= __('admin.success') ?>', response.message, 'success', 3000);
					setTimeout(() => {
						window.location.reload();
					}, 1500);
				} else {
					showToast('<?= __('admin.error') ?>', response.message, 'error', 5000);
				}
			},
			error: function() {
				showToast('<?= __('admin.error') ?>', '<?= __('admin.something_went_wrong') ?>', 'error', 5000);
			}
		});
	}
	
	// Use event delegation to handle dynamically loaded content
	$(document).on('click', '.btn-set-default', function(e) {
		e.preventDefault();
		const currencyId = $(this).data('currency-id');
		const currencyName = $(this).data('currency-name');
		const currencyCode = $(this).data('currency-code');
		
		// Check if SweetAlert is available
		if (typeof Swal === 'undefined') {
			// Fallback to native confirm
			if (confirm('Are you sure you want to set ' + currencyName + ' (' + currencyCode + ') as the default currency?')) {
				// Proceed with AJAX call
				performSetDefault(currencyId, currencyName);
			}
			return;
		}
		
		Swal.fire({
			title: '<?= __('admin.set_default_currency') ?>',
			text: '<?= __('admin.set_default_currency_warning') ?>'.replace('%s', currencyName + ' (' + currencyCode + ')'),
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#ffc107',
			cancelButtonColor: '#6c757d',
			confirmButtonText: '<?= __('admin.yes_set_default') ?>',
			cancelButtonText: '<?= __('admin.cancel') ?>'
		}).then((result) => {
			// Handle both SweetAlert v1 (value: true) and v2+ (isConfirmed: true)
			if (result.isConfirmed || result.value === true) {
				performSetDefault(currencyId, currencyName);
			}
		});
	});
	
	$('.btn-delete').on('click', function(e) {
		e.preventDefault();
		
		const currencyId = $(this).data('currency-id');
		const currencyName = $(this).data('currency-name');
		const $row = $(this).closest('tr');
		
		Swal.fire({
			title: '<?= __('admin.are_you_sure') ?>',
			text: '<?= __('admin.delete_currency_warning') ?>'.replace('%s', currencyName),
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#dc3545',
			cancelButtonColor: '#6c757d',
			confirmButtonText: '<?= __('admin.yes_delete') ?>',
			cancelButtonText: '<?= __('admin.cancel') ?>'
		}).then((result) => {
			if (result.isConfirmed || result.value === true) {
				$.ajax({
					url: '<?= base_url('admincontrol/currency_delete_ajax') ?>',
					type: 'POST',
					dataType: 'json',
					data: { currency_id: currencyId },
					success: function(response) {
						if (response.success) {
							$row.fadeOut(400, function() {
								$(this).remove();
								showToast('<?= __('admin.success') ?>', response.message, 'success', 3000);
								var $count = $('#total-currencies-count');
								var current = parseInt($count.text(), 10) || 0;
								if (current > 0) { $count.text(current - 1); }
							});
						} else {
							showToast('<?= __('admin.error') ?>', response.message, 'error', 5000);
						}
					},
					error: function() {
						showToast('<?= __('admin.error') ?>', '<?= __('admin.something_went_wrong') ?>', 'error', 5000);
					}
				});
			}
		});
	});
	
	$('#refresh-rates').on('click', function() {
		const $btn = $(this);
		const originalText = $btn.html();
		
		$btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.refreshing') ?>...');
		
		$.ajax({
			url: '<?= base_url('admincontrol/currency_refresh') ?>',
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					let message = response.message;
					if (response.updated_count) {
						message += ` (${response.updated_count} currencies updated)`;
					}
					if (response.base_currency) {
						message += ` Base: ${response.base_currency}`;
					}
					showToast('<?= __('admin.success') ?>', message, 'success', 4000);
					setTimeout(() => {
						window.location.reload();
					}, 2000);
				} else {
					showToast('<?= __('admin.error') ?>', response.message || '<?= __('admin.failed_to_refresh_rates') ?>', 'error', 5000);
				}
			},
			error: function() {
				showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_refresh_rates') ?>', 'error', 5000);
			},
			complete: function() {
				$btn.prop('disabled', false).html(originalText);
			}
		});
	});
});
</script>