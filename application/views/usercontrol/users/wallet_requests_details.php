<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-primary text-white border-0">
					<h5 class="card-title mb-0 fw-bold">
						<i class="fas fa-file-invoice-dollar me-2"></i><?= __('user.withdraw_requests_details') ?> #<?= $request['id'] ?>
					</h5>
				</div>
				<div class="card-body">
					<div class="row g-4 mb-4">
						<div class="col-lg-4">
							<div class="card border shadow-sm h-100">
								<div class="card-header bg-info text-white border-0">
									<h6 class="mb-0 fw-bold">
										<i class="fas fa-info-circle me-2"></i><?= __('user.request_details') ?>
									</h6>
								</div>
								<div class="list-group list-group-flush">
									<div class="list-group-item d-flex justify-content-between align-items-center">
										<span class="text-muted fw-semibold"><?= __('user.id') ?></span>
										<span class="badge bg-primary rounded-pill"><?= $request['id'] ?></span>
									</div>
									<div class="list-group-item d-flex justify-content-between align-items-center">
										<span class="text-muted fw-semibold"><?= __('user.balance') ?></span>
										<span class="fw-bold text-success"><?= c_format($request['total']) ?></span>
									</div>
									<div class="list-group-item d-flex justify-content-between align-items-center">
										<span class="text-muted fw-semibold"><?= __('user.payment_method') ?></span>
										<span class="fw-bold text-dark"><?= $request['prefer_method'] ?></span>
									</div>
									<div class="list-group-item d-flex justify-content-between align-items-center">
										<span class="text-muted fw-semibold"><?= __('user.payment_status') ?></span>
										<span><?= withdrwal_status($request['status']) ?></span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-4">
							<div class="card border shadow-sm h-100">
								<div class="card-header bg-success text-white border-0">
									<h6 class="mb-0 fw-bold">
										<i class="fas fa-clipboard-check me-2"></i><?= __('user.submited_details') ?>
									</h6>
								</div>
								<div class="list-group list-group-flush">
									<?php
										$data = json_decode($request['settings'],1);
										$payment_proof = '';
										foreach ($data as $key => $value) {
											if($key == 'payment_proof') {
												$payment_proof = $value;
												continue;
											}
									?>
										<div class="list-group-item d-flex justify-content-between align-items-start">
											<span class="text-muted fw-semibold text-capitalize"><?= str_replace("_", " ", $key) ?></span>
											<span class="text-dark text-end text-break" style="max-width: 60%;"><?= $value ?></span>
										</div>
									<?php } ?>
									<?php if($payment_proof) { ?>
										<div class="list-group-item d-flex justify-content-between align-items-center">
											<span class="text-muted fw-semibold"><?= __('user.payment_proof') ?></span>
											<a target="_blank" href="<?= base_url('assets/user_upload/'.$payment_proof) ?>" class="btn btn-sm btn-outline-primary">
												<i class="fas fa-download me-1"></i><?= $payment_proof ?>
											</a>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>

						<div class="col-lg-4">
							<div class="card border shadow-sm h-100">
								<div class="card-header bg-warning text-dark border-0">
									<h6 class="mb-0 fw-bold">
										<i class="fas fa-history me-2"></i><?= __('user.status_history') ?>
									</h6>
								</div>
								<div class="card-body p-0">
									<div class="table-responsive">
										<table class="table table-sm table-hover mb-0 align-middle">
											<thead class="table-light">
												<tr>
													<th class="text-nowrap"><i class="fas fa-info-circle me-2"></i><?= __('user.status') ?></th>
													<th class="text-nowrap"><i class="fas fa-comment me-2"></i><?= __('user.comment') ?></th>
												</tr>
											</thead>
											<tbody id="history_container">
												<tr>
													<td colspan="2" class="text-center py-3">
														<div class="spinner-border spinner-border-sm text-primary me-2" role="status">
															<span class="visually-hidden">Loading...</span>
														</div>
														<?= __('user.loading') ?>...
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="card border shadow-sm">
						<div class="card-header bg-secondary text-white border-0">
							<h6 class="mb-0 fw-bold">
								<i class="fas fa-list me-2"></i><?= __('user.transactions') ?>
							</h6>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-hover transaction-table align-middle mb-0">
									<thead class="table-light">
										<tr>
											<th class="text-nowrap"><i class="fas fa-calendar me-2"></i><?= __('user.date') ?></th>
											<th class="text-nowrap"><i class="fas fa-user me-2"></i><?= __('user.user') ?></th>
											<th class="text-nowrap"><i class="fas fa-shopping-bag me-2"></i><?= __('user.order') ?></th>
											<th class="text-nowrap"><i class="fas fa-dollar-sign me-2"></i><?= __('user.commission') ?></th>
											<th class="text-nowrap"><i class="fas fa-tag me-2"></i><?= __('user.type') ?></th>
											<th class="text-nowrap"><i class="fas fa-info-circle me-2"></i><?= __('user.status') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($transaction as $key => $value) {
												$data = [];
												$data['value'] = $value;
												$data['class'] = $class;
												$data['stop_checkbox'] = 1; 
												$data['stop_child'] = 1; 
												$data['wallet_status'] = $status; 
												echo $this->Product_model->getHtml('usercontrol/users/parts/new_wallet_tr', $data);
											} 
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).delegate('.wallet-popover','click', function(){
		var html = $(this).parents("tr").find(".dpopver-content").html();
        $(this).attr('data-content',html);
	    if($('.popover').hasClass('show')){
	        $('.popover').remove()
	    } else {
	        $(this).popover('show');
	    }
	});

	$('html').on('click', function(e) {
	  if (typeof $(e.target).data('original-title') == 'undefined' &&
	     !$(e.target).parents().is('.popover.in')) {
	    $('[data-original-title]').popover('hide');
	  }
	});

	$(document).ready(function(){
		$(".wallet-popover").popover({
	        placement : 'right',
		    html : true,
	    });
	})

	function getHistory() {
		$.ajax({
			url:'<?= base_url('usercontrol/get_withdrwal_history/'. $request['id']) ?>',
			type:'POST',
			dataType:'json',
			beforeSend:function(){
				$("#history_container").html("<tr><td colspan='2' class='text-center py-3'><div class='spinner-border spinner-border-sm text-primary me-2' role='status'><span class='visually-hidden'>Loading...</span></div><?= __('user.loading') ?>...</td></tr>");
			},
			success:function(json){
				$("#history_container").html(json['html']);
			},
		})
	}

	getHistory()
</script>