<div class="table-responsive">
	<table class="table table-hover mb-0">
		<thead class="table-light">
			<tr>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-cube me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.module') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-hashtag me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.id') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-user me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.user') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4 text-end">
					<div class="d-flex align-items-center justify-content-end">
						<i class="fas fa-dollar-sign me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.price') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-credit-card me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.payment_gateway') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-receipt me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.transaction_id') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-info-circle me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.status') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-calendar me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.date') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4 text-center">
					<div class="d-flex align-items-center justify-content-center">
						<i class="fas fa-cog me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('admin.actions') ?></span>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($all_transaction)): ?>
				<tr>
					<td colspan="9" class="text-center py-5">
						<div class="d-flex flex-column align-items-center">
							<i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
							<h5 class="text-muted mb-2"><?= __('admin.no_transactions_found') ?></h5>
							<p class="text-muted mb-0"><?= __('admin.no_transactions_found_matching_criteria') ?></p>
						</div>
					</td>
				</tr>
			<?php else: ?>
				<?php foreach($all_transaction as $key => $value){
					$payment_method = strtolower(trim(str_replace("_", " ",$value['payment_gateway'])));
					switch($value['module']){
						case 'deposit':
						$payment_gateway =  __('admin.'.array_search($payment_method,array_map('strtolower',$payment_methods)));
						$transaction_id = $value['payment_detail'];
						$status_text = withdrwal_status($value['status_id']);
						$url = base_url('admincontrol/vendor_deposit_details/'.$value['id']);
						$module_badge = 'bg-info';
						break;
						case 'membership':
						$payment_gateway =  __('admin.'.array_search($payment_method ,array_map('strtolower',$payment_methods)));
						$payment_detail = json_decode($value['payment_detail'], true);
						if (is_array($payment_detail) && isset($payment_detail['transaction_id'])) {
							$transaction_id = $payment_detail['transaction_id'];
						} elseif (is_array($payment_detail) && isset($payment_detail['payment_status'])) {
							$transaction_id = 'N/A';
						} elseif (!empty($value['payment_detail']) && $value['payment_detail'] != '""' && $value['payment_detail'] != '[]') {
							$transaction_id = $value['payment_detail'];
						} else {
							$transaction_id = '';
						}
						$status_text = membership_withdrwal_status($value['status_id']);
						$url = base_url('membership/membership_purchase_edit/'.$value['id']);
						$module_badge = 'bg-success';
						break;
						case 'store':
						$payment_gateway = __('admin.'.array_search($payment_method,array_map('strtolower',$payment_methods)));
						$transaction_id = $value['payment_detail'];
						$status_text = store_withdrwal_status($value['status_id']);
						$url = base_url('admincontrol/vieworder/'.$value['id']);
						$module_badge = 'bg-warning';
						break;
					} ?>
					<tr class="border-bottom">
						<td class="py-3 px-4">
							<span class="badge <?= $module_badge ?> text-white">
								<?= __('admin.'.$value['module']) ?>
							</span>
						</td>
						<td class="py-3 px-4">
							<span class="fw-medium text-muted">#<?= $value['id'] ?></span>
						</td>
						<td class="py-3 px-4">
							<div class="d-flex align-items-center">
								<div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
									<i class="fas fa-user text-muted"></i>
								</div>
								<span class="fw-medium"><?= $value['username'] ?></span>
							</div>
						</td>
						<td class="py-3 px-4 text-end">
							<span class="fw-bold text-success"><?= c_format($value['price']) ?></span>
						</td>
						<td class="py-3 px-4">
							<span class="text-muted"><?= $payment_gateway ?></span>
						</td>
						<td class="py-3 px-4">
							<?php if(!empty($transaction_id) && $transaction_id != 'N/A'): ?>
								<code class="bg-light px-2 py-1 rounded"><?= $transaction_id ?></code>
							<?php elseif($transaction_id == 'N/A'): ?>
								<span class="text-muted small"><?= __('admin.not_applicable') ?></span>
							<?php else: ?>
								<span class="text-muted small"><?= __('admin.no_transaction_id') ?></span>
							<?php endif; ?>
						</td>
						<td class="py-3 px-4">
							<?= $status_text ?>
						</td>
						<td class="py-3 px-4">
							<span class="text-muted small"><?= dateFormat($value['datetime'],'d M Y H:i'); ?></span>
						</td>
						<td class="py-3 px-4 text-center">
							<a href="<?= $url ?>" class="btn btn-outline-primary btn-sm" target="_blank">
								<i class="fas fa-eye me-1"></i><?= __('admin.details') ?>
							</a>
						</td>
					</tr>
				<?php } ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php if(!empty($all_transaction)): ?>
<div class="card-footer bg-light border-top-0 px-4 py-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<?= pagination_summary_html($current_page ?? 1, $per_page ?? ($pagination_settings['per_page'] ?? 5), $total_rows ?? 0, 'start', 'sm') ?>
		</div>
		<div class="col-md-6">
			<div class="d-flex justify-content-end">
				<?= $pagination ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>