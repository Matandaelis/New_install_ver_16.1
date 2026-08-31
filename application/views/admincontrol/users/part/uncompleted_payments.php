<div class="card-body p-0">
	<?php if(empty($uncompleted_payments)): ?>
		<div class="text-center py-5">
			<div class="mb-3">
				<i class="fas fa-exclamation-triangle text-muted" style="font-size: 3rem;"></i>
			</div>
			<h5 class="text-muted"><?= __('admin.no_uncompleted_payments') ?></h5>
			<p class="text-muted"><?= __('admin.no_uncompleted_payments_desc') ?></p>
		</div>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table transaction-table table-hover mb-0">
				<thead class="table-light">
					<tr>
						<th class="border-0"><?= __('admin.id') ?></th>
						<th class="border-0"><?= __('admin.user_info') ?></th>
						<th class="border-0"><?= __('admin.contact') ?></th>
						<th class="border-0"><?= __('admin.amount') ?></th>
						<th class="border-0"><?= __('admin.module') ?></th>
						<th class="border-0"><?= __('admin.datetime') ?></th>
						<th class="border-0 text-center"><?= __('admin.actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($uncompleted_payments as $value): ?>
						<tr>
							<td class="align-middle">
								<span class="badge bg-secondary">#<?= $value['id'] ?></span>
							</td>
							<td class="align-middle">
								<div class="d-flex flex-column">
									<span class="fw-semibold"><?= $value['username'] ?></span>
									<small class="text-muted"><?= $value['firstname'] ?> <?= $value['lastname'] ?></small>
								</div>
							</td>
							<td class="align-middle">
								<div class="d-flex flex-column">
									<?php if(!empty($value['email'])): ?>
										<span class="text-truncate" style="max-width: 150px;" title="<?= $value['email'] ?>">
											<i class="fas fa-envelope me-1 text-muted"></i><?= $value['email'] ?>
										</span>
									<?php endif; ?>
									<?php if(!empty($value['phone'])): ?>
										<small class="text-muted">
											<i class="fas fa-phone me-1"></i><?= $value['phone'] ?>
										</small>
									<?php endif; ?>
									<?php if(empty($value['email']) && empty($value['phone'])): ?>
										<span class="text-muted small"><?= __('admin.not_available') ?></span>
									<?php endif; ?>
								</div>
							</td>
							<td class="align-middle">
								<span class="fw-bold text-success"><?= c_format($value['ammount']) ?></span>
							</td>
							<td class="align-middle">
								<span class="badge bg-info"><?= $payment_module[$value['payment_module']] ?></span>
							</td>
							<td class="align-middle">
								<div class="d-flex flex-column">
									<span><?= dateFormat($value['datetime'],'d M Y') ?></span>
									<small class="text-muted"><?= dateFormat($value['datetime'],'H:i') ?></small>
								</div>
							</td>
							<td class="align-middle text-center">
								<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#payment_details_<?= $value['id']; ?>">
									<i class="fas fa-eye me-1"></i><?= __('admin.details') ?>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
<?php if(!empty($uncompleted_payments) && !empty($pagination)): ?>
	<div class="card-footer bg-light">
		<div class="d-flex justify-content-between align-items-center">
			<div class="text-muted small">
				<?php 
				$start = (($current_page - 1) * $per_page) + 1;
				$end = min($start + count($uncompleted_payments) - 1, $total_rows);
				?>
				<?= __('admin.showing') ?> <span id="showing-start"><?= $start ?></span> <?= __('admin.to') ?> <span id="showing-end"><?= $end ?></span> <?= __('admin.of') ?> <span id="showing-total"><?= $total_rows ?></span> <?= __('admin.entries') ?>
			</div>
			<nav aria-label="Pagination">
				<?= $pagination ?>
			</nav>
		</div>
	</div>
<?php endif; ?>

<?php if(!empty($uncompleted_payments)): ?>
	<?php foreach($uncompleted_payments as $value): ?>
		<div class="modal fade" id="payment_details_<?= $value['id']; ?>" tabindex="-1" aria-labelledby="paymentDetailsModal<?= $value['id']; ?>" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title" id="paymentDetailsModal<?= $value['id']; ?>">
							<i class="fas fa-info-circle me-2"></i><?= __('admin.payment_details') ?>
						</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body p-4">
						<div class="row mb-4">
							<div class="col-md-6">
								<div class="card border-0 bg-light">
									<div class="card-body">
										<h6 class="card-title text-muted mb-3"><?= __('admin.user_information') ?></h6>
										<p class="mb-2"><strong><?= __('admin.username') ?>:</strong> <?= $value['username'] ?></p>
										<p class="mb-2"><strong><?= __('admin.name') ?>:</strong> <?= $value['firstname'] ?> <?= $value['lastname'] ?></p>
										<?php if(!empty($value['email'])): ?>
											<p class="mb-2"><strong><?= __('admin.email') ?>:</strong> <?= $value['email'] ?></p>
										<?php endif; ?>
										<?php if(!empty($value['phone'])): ?>
											<p class="mb-0"><strong><?= __('admin.phone') ?>:</strong> <?= $value['phone'] ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card border-0 bg-light">
									<div class="card-body">
										<h6 class="card-title text-muted mb-3"><?= __('admin.payment_information') ?></h6>
										<p class="mb-2"><strong><?= __('admin.amount') ?>:</strong> <span class="text-success fw-bold"><?= c_format($value['ammount']) ?></span></p>
										<p class="mb-2"><strong><?= __('admin.module') ?>:</strong> <span class="badge bg-info"><?= $payment_module[$value['payment_module']] ?></span></p>
										<p class="mb-0"><strong><?= __('admin.datetime') ?>:</strong> <?= dateFormat($value['datetime'],'d F Y H:i') ?></p>
									</div>
								</div>
							</div>
						</div>

						<?php
							switch ((int) $value['payment_module']) {
								case 1:
									?>
									<div class="card border-0">
										<div class="card-header bg-light">
											<h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i><?= __("admin.orders_details") ?></h6>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-4">
													<p><strong><?= __("admin.created_at") ?>:</strong><br><span class="text-muted"><?= $value['content']['order']['created_at']; ?></span></p>
												</div>
												<div class="col-md-4">
													<p><strong><?= __("admin.order_total") ?>:</strong><br><span class="text-success fw-bold"><?= c_format($value['content']['order']['total']); ?></span></p>
												</div>
												<div class="col-md-4">
													<p><strong><?= __("admin.payment_method") ?>:</strong><br><span class="text-muted"><?= $value['content']['order']['payment_method']; ?></span></p>
												</div>
											</div>
											<hr>
											<h6 class="mb-3"><?= __("admin.products_details") ?></h6>
											<div class="table-responsive">
												<table class="table table-sm">
													<thead class="table-light">
														<tr>
															<th><?= __("admin.product_name") ?></th>
															<th><?= __("admin.price") ?></th>
															<th><?= __("admin.quantity") ?></th>
															<th><?= __("admin.total") ?></th>
														</tr>
													</thead>
													<tbody>
														<?php foreach($value['content']['products'] as $products): ?>
															<tr>
																<td><?= $products['product_name']; ?></td>
																<td><?= c_format($products['price']); ?></td>
																<td><?= $products['quantity']; ?></td>
																<td class="fw-bold"><?= c_format($products['total']); ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<?php
									break;
								case 2:
									?>
									<div class="card border-0">
										<div class="card-header bg-light">
											<h6 class="mb-0"><i class="fas fa-wallet me-2"></i><?= __("admin.deposit_details") ?></h6>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-4">
													<p><strong><?= __("admin.amount") ?>:</strong><br><span class="text-success fw-bold"><?= c_format($value['content']['deposit_details']['vd_amount']); ?></span></p>
												</div>
												<div class="col-md-4">
													<p><strong><?= __("admin.status") ?>:</strong><br><span class="badge bg-warning"><?= $value['content']['deposit_details']['status_label']; ?></span></p>
												</div>
												<div class="col-md-4">
													<p><strong><?= __("admin.payment_method") ?>:</strong><br><span class="text-muted"><?= $value['content']['deposit_details']['vd_payment_method']; ?></span></p>
												</div>
											</div>
										</div>
									</div>
									<?php
									break;
								case 3:
									?>
									<div class="card border-0">
										<div class="card-header bg-light">
											<h6 class="mb-0"><i class="fas fa-crown me-2"></i><?= __("admin.membership_plan_details") ?></h6>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<p><strong><?= __("admin.name") ?>:</strong><br><span class="text-muted"><?= $value['content']['plan']['name']; ?></span></p>
													<p><strong><?= __("admin.type") ?>:</strong><br><span class="text-muted"><?= $value['content']['plan']['type']; ?></span></p>
												</div>
												<div class="col-md-6">
													<p><strong><?= __("admin.billing_period") ?>:</strong><br><span class="text-muted"><?= $value['content']['plan']['billing_period']; ?></span></p>
													<p><strong><?= __("admin.price") ?>:</strong><br><span class="text-success fw-bold"><?= c_format($value['content']['plan']['price']); ?></span></p>
													<?php if(!empty($value['content']['plan']['special'])): ?>
														<p><strong><?= __("admin.discount_price") ?>:</strong><br><span class="text-danger fw-bold"><?= c_format($value['content']['plan']['special']); ?></span></p>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
									<?php
									break;
							}
						?>

						<?php if(!empty($value['additional_info'])): ?>
							<div class="card border-0 mt-3">
								<div class="card-header bg-light">
									<h6 class="mb-0"><i class="fas fa-info-circle me-2"></i><?= __('admin.additional_info') ?></h6>
								</div>
								<div class="card-body">
									<p class="mb-0"><?= $value['additional_info']; ?></p>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
							<i class="fas fa-times me-1"></i><?= __('admin.close') ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
