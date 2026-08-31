<tr class="wallet-detail-tr <?= $orderType."-".$orderId ?>">
	<td colspan="100%" class="p-0" style="border-top: none !important;">
		<div class="bg-light border rounded-3 m-3 p-4 shadow-sm">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h6 class="mb-0 text-primary">
					<i class="fas fa-wallet me-2"></i>
					<?= count($transaction) ?> <?= __('admin.wallet_transactions_generated') ?>
				</h6>
				<button class="btn btn-outline-secondary btn-sm" onclick="$(this).closest('.wallet-detail-tr').remove()" title="<?= __('admin.close') ?>">
					<i class="fas fa-times"></i>
				</button>
			</div>
			
			<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
				<table class="table table-hover mb-0">
					<thead class="table-dark sticky-top">
						<tr>
							<th class="ps-3 text-white"><?= __('admin.id') ?></th>
							<th class="text-white"><?= __('admin.date') ?></th>
							<th class="text-white"><?= __('admin.user_type') ?></th>
							<th class="text-white"><?= __('admin.user') ?></th>
							<th class="text-white"><?= __('admin.type') ?></th>
							<th class="text-white"><?= __('admin.amount') ?></th>
							<th class="text-white"><?= __('admin.commission_type') ?></th>
							<th class="text-white"><?= __('admin.status') ?></th>
							<th class="text-white"><?= __('admin.payment_status') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							if ($is_dashboard == '1' || $is_order_page == '1') {
								foreach ($transaction as $key => $value) {
									$data = [];
									$data['value'] = $value;
									$data['class'] = $class;
									$data['stop_checkbox'] = 1; 
									$data['stop_child'] = 1; 
									$data['wallet_status'] = $status;
									$data['status'] = $this->Wallet_model->status();
		                    		$data['status_icon'] = $this->Wallet_model->status_icon;
		                    		$data['status_list'] = $this->Withdrawal_payment_model->status_list;
									$data['hide_recursion_btn'] = true; 
									echo $this->Product_model->getHtml('admincontrol/users/part/dashboard_wallet_tr', $data);
								}
							}else{
								foreach ($transaction as $key => $value) {
									$data = [];
									$data['value'] = $value;
									$data['class'] = $class;
									$data['stop_checkbox'] = 1; 
									$data['stop_child'] = 1; 
									$data['wallet_status'] = $status;
									$data['status'] = $this->Wallet_model->status();
		                    		$data['status_icon'] = $this->Wallet_model->status_icon;
		                    		$data['status_list'] = $this->Withdrawal_payment_model->status_list;
									$data['hide_recursion_btn'] = true; 
									echo $this->Product_model->getHtml('admincontrol/users/part/new_wallet_tr', $data);
								}
							} 
						?>
					</tbody>
				</table>
			</div>
			
			<?php if (isset($has_more) && $has_more): ?>
			<div class="text-center mt-3 pt-3 border-top">
				<button class="btn btn-outline-primary btn-sm load-more-transactions" 
				        data-order-type="<?= $orderType ?>" 
				        data-order-id="<?= $orderId ?>" 
				        data-page="2">
					<i class="fas fa-plus me-1"></i><?= __('admin.load_more_transactions') ?>
				</button>
				<small class="text-muted d-block mt-2">
					<i class="fas fa-info-circle me-1"></i>
					<?= __('admin.showing') ?> 1-<?= count($transaction) ?> <?= __('admin.of') ?> <?= isset($total_transactions) ? $total_transactions : count($transaction) ?> <?= __('admin.transactions') ?>
				</small>
			</div>
			<?php endif; ?>
		</div>
	</td>
</tr>