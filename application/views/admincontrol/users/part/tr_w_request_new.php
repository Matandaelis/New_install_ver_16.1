<?php foreach ($lists as $key => $value) { ?>
	<tr class="wallet-request-row">
		<td class="fw-semibold"><?= $value['id'] ?></td>
		<td>
			<div class="d-flex align-items-center">
				<div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
					<i class="fas fa-user text-primary"></i>
				</div>
				<span class="fw-medium"><?= $value['username'] ?></span>
			</div>
		</td>
		<td>
			<div class="text-muted small"><?= dateFormat($value['created_at'],'d F Y') ?></div>
			<div class="text-muted" style="font-size: 0.75rem;"><?= dateFormat($value['created_at'],'H:i') ?></div>
		</td>
		<td>
			<?php if($value['prefer_method'] != 'N/A'): ?>
				<span class="badge bg-info"><?= ucfirst(str_replace('_', ' ', $value['prefer_method'])) ?></span>
			<?php else: ?>
				<span class="badge bg-secondary"><?= __('admin.not_specified') ?></span>
			<?php endif; ?>
		</td>
		<td>
			<?php if($value['tran_ids'] != ""): ?>
				<button class="btn btn-outline-info btn-sm trans_ids" data-trans_ids="<?= $value['tran_ids'] ?>" title="<?= __('admin.view_transaction_ids') ?>">
					<i class="fas fa-eye me-1"></i><?= __('admin.view') ?>
				</button>
			<?php else: ?>
				<span class="text-muted small"><?= __('admin.no_transactions') ?></span>
			<?php endif; ?>
		</td>
		<td>
			<div class="fw-bold text-success"><?= c_format($value['total']) ?></div>
		</td>
		<td><?= withdrwal_status($value['status']) ?></td>
		<td class="text-end">
			<div class="d-flex flex-wrap justify-content-end gap-1">
				<a href="<?= base_url('admincontrol/wallet_requests_details/'. $value['id']) ?>" class="btn btn-primary btn-sm" title="<?= __('admin.view_details') ?>">
					<i class="fas fa-eye me-1"></i><?= __('admin.details') ?>
				</a>
				<?php
				$wr_status = (int) ($value['status'] ?? 0);
				$wr_batch = 0;
				if (isset($value['batch_export_id']) && $value['batch_export_id'] !== null && $value['batch_export_id'] !== '') {
					$wr_batch = (int) $value['batch_export_id'];
				}
				$mp_eligible = in_array($wr_status, array(7, 12), true);
				?>
				<?php if ($mp_eligible && $wr_batch < 1) { ?>
					<a href="<?= base_url('admincontrol/mass_payout?focus_wr=' . (int) $value['id']) ?>" class="btn btn-success btn-sm" title="<?= htmlspecialchars(__('admin.wallet_requests_row_mass_payout_add')) ?>">
						<i class="fas fa-file-export"></i>
					</a>
				<?php } elseif ($wr_batch > 0) { ?>
					<a href="<?= base_url('admincontrol/mass_payout#mass-payout-batch-' . $wr_batch) ?>" class="btn btn-outline-dark btn-sm" title="<?= htmlspecialchars(__('admin.wallet_requests_row_mass_payout_batch')) ?>">
						<i class="fas fa-layer-group"></i>
					</a>
				<?php } ?>
				<?php if($value['tran_ids'] != ""): ?>
					<button data-id="<?= $value['tran_ids'] ?>" class="btn btn-danger btn-sm btn-deletes" title="<?= __('admin.revert_to_wallet') ?>">
						<i class="fas fa-undo me-1"></i><?= __('admin.revert') ?>
					</button>
				<?php else: ?>
					<button class="btn btn-secondary btn-sm" disabled title="<?= __('admin.transaction_not_available') ?>">
						<i class="fas fa-ban me-1"></i><?= __('admin.n_a') ?>
					</button>
				<?php endif; ?>
			</div>
		</td>
	</tr>
<?php  } ?>