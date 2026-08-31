<div class="table-responsive">
	<table class="table table-hover mb-0">
		<thead class="table-light">
			<tr>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-cube me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('user.module') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-hashtag me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('user.id') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4 text-end">
					<div class="d-flex align-items-center justify-content-end">
						<i class="fas fa-dollar-sign me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('user.price') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-credit-card me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('user.payment_gateway') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-receipt me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('user.transaction_id') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-info-circle me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('user.status') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4">
					<div class="d-flex align-items-center">
						<i class="fas fa-calendar me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('user.date') ?></span>
					</div>
				</th>
				<th class="border-0 py-3 px-4 text-center">
					<div class="d-flex align-items-center justify-content-center">
						<i class="fas fa-cog me-2 text-muted"></i>
						<span class="fw-semibold"><?= __('user.actions') ?></span>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($all_transaction)): ?>
				<tr>
					<td colspan="8" class="text-center py-5">
						<div class="d-flex flex-column align-items-center">
							<i class="fas fa-inbox text-muted mb-3 fs-1"></i>
							<h5 class="text-muted mb-2"><?= __('user.no_transactions_found') ?></h5>
							<p class="text-muted mb-0"><?= __('user.no_transactions_found_matching_criteria') ?></p>
						</div>
					</td>
				</tr>
			<?php else: ?>
				<?php foreach($all_transaction as $key => $value){
					switch($value['module']){
					    case 'deposit':
					   		$payment_gateway = __('user.'.$value['payment_gateway']);
					        $transaction_id = $value['payment_detail'];
					        $status_text = withdrwal_status($value['status_id']);
					        $url = base_url('usercontrol/deposit_details/'.$value['id']);
					        $module_badge = 'bg-info';
					        break;
					    case 'membership':
					    	$payment_gateway = $value['payment_gateway'];
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
					        $url = base_url('usercontrol/membership_purchase_details/'.$value['id']);
					        $module_badge = 'bg-success';
					        break;
					    case 'store':
					    	$payment_gateway = $value['payment_gateway'];
					        $transaction_id = $value['payment_detail'];
					        $status_text = store_withdrwal_status($value['status_id']);
					        $url = base_url('usercontrol/vieworder/'.$value['id']);
					        $module_badge = 'bg-warning';
					        break;
					} ?>
					<tr class="border-bottom">
						<td class="py-3 px-4">
							<span class="badge <?= $module_badge ?> text-white">
								<?php
									if ($value['module'] == 'store') {
										echo __('user.store');
									}elseif ($value['module'] == 'membership') {
										echo __('user.membership');
									}elseif ($value['module'] == 'deposit') {
										echo __('user.deposit');
									}
								?>
							</span>
						</td>
						<td class="py-3 px-4">
							<span class="fw-medium text-muted">#<?= $value['id'] ?></span>
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
								<span class="text-muted small"><?= __('user.not_applicable') ?></span>
							<?php else: ?>
								<span class="text-muted small"><?= __('user.no_transaction_id') ?></span>
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
								<i class="fas fa-eye me-1"></i><?= __('user.details') ?>
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
	<div class="d-flex justify-content-end">
		<?= $pagination ?>
	</div>
</div>
<?php endif; ?>