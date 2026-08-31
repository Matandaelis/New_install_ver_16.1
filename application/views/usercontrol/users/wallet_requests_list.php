<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-white border-0">
					<h5 class="card-title mb-0 fw-bold text-primary">
						<i class="fas fa-file-invoice-dollar me-2"></i><?= __('user.withdraw_requests_list') ?>
					</h5>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead class="table-light">
								<tr>
									<th class="text-nowrap"><i class="fas fa-hashtag me-2"></i><?= __('user.id') ?></th>
									<th class="text-nowrap"><i class="fas fa-calendar me-2"></i><?= __('user.date') ?></th>
									<th class="text-nowrap text-center"><i class="fas fa-eye me-2"></i><?= __('user.transactions_ids') ?></th>
									<th class="text-nowrap"><i class="fas fa-dollar-sign me-2"></i><?= __('user.total') ?></th>
									<th class="text-nowrap"><i class="fas fa-info-circle me-2"></i><?= __('user.status') ?></th>
									<th class="text-nowrap text-end"><?= __('user.actions') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if(empty($lists)){ ?>
									<tr>
										<td colspan="6" class="text-center py-5">
											<div class="text-muted">
												<i class="fas fa-inbox fs-1 mb-3 d-block"></i>
												<p class="mb-0"><?= __('user.no_records_found') ?></p>
											</div>
										</td>
									</tr>
								<?php } ?>
								<?php foreach ($lists as $key => $value) { ?>
									<tr>
										<td><span class="badge bg-primary"><?= $value['id'] ?></span></td>
										<td class="text-nowrap"><?= dateFormat($value['created_at'],'d F Y') ?></td>
										<td class="text-center">
											<button type="button" class="btn btn-sm btn-outline-primary rounded-circle trans_ids" data-trans_ids="<?= $value['tran_ids'] ?>">
												<i class="fas fa-eye"></i>
											</button>
										</td>
										<td class="fw-bold text-success"><?= c_format($value['total']) ?></td>
										<td><?= withdrwal_status($value['status']) ?></td>
										<td class="text-end">
											<div class="btn-group" role="group">
												<a href="<?= base_url('usercontrol/wallet_requests_details/'. $value['id']) ?>" class="btn btn-sm btn-primary">
													<i class="fas fa-info-circle me-1"></i><?= __('user.details') ?>
												</a>
												<?php if($value['status'] != 1){ ?>
													<button type="button" id="<?= $value['id'] ?>" class="btn btn-sm btn-danger btn-deletes">
														<i class="fas fa-trash me-1"></i><?= __('user.delete') ?>
													</button>
												<?php } ?>
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

<div class="modal fade" id="transIds" tabindex="-1" aria-labelledby="transIdsLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 shadow">
			<div class="modal-header bg-primary text-white border-0">
				<h5 class="modal-title" id="transIdsLabel">
					<i class="fas fa-list-ol me-2"></i><?= __('user.transactions_ids') ?>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-info border-0 mb-0">
					<div class="d-flex align-items-start">
						<i class="fas fa-info-circle me-2 mt-1"></i>
						<p class="mb-0 text-wrap text-break"></p>
					</div>
				</div>
			</div>
			<div class="modal-footer border-0 bg-light">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					<i class="fas fa-times me-1"></i><?= __('user.close') ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".trans_ids").click(function(){
		var trans_ids = $(this).data('trans_ids');
		$("#transIds .modal-body p").text(trans_ids);
		$("#transIds").modal('show');
	});

	$(document).delegate(".btn-deletes",'click',function(){
		$this = $(this);

		Swal.fire({
			title: '<?= __('user.are_you_sure') ?>',
			text: '<?= __('user.you_not_be_able_to_revert_this') ?>',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: '<?= __('user.yes_delete') ?>'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type:'POST',
					dataType:'json',
					data:{delete_request: true,id:$this.attr("id")},
					beforeSend:function(){ $this.btn("loading"); },
					complete:function(){ $this.btn("reset"); },
					success:function(json){
						if (json['error']) {
							showToast('error', json['error']);
						}
						if (json['success']) {
							$this.parents("tr").fadeOut(300, function(){
								$(this).remove();
							});
							showToast('success', '<?= __('user.request_deleted_successfully_transactions_in_wallet') ?>');
						}
					},
				})
			}
		})
	});
</script>
