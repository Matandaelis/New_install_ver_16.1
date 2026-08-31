<div class="container-fluid">
<div class="row">
	<div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i><?= __('user.deposit_requests_details') ?> #<?= $request['vd_id'] ?>
                        </h5>
                        <a class="btn btn-light btn-sm" href="<?= base_url('usercontrol/my_deposits') ?>">
                            <i class="fas fa-arrow-left me-1"></i><?= __('admin.back') ?>
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Request Details -->
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-info-circle me-2"></i><?= __('user.request_details') ?>
                                    </h6>
                                </div>
			<div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-semibold text-muted" style="width: 40%;"><?= __('user.id') ?></td>
                                                    <td><span class="badge bg-primary">#<?= $request['vd_id'] ?></span></td>
							</tr>
							<tr>
                                                    <td class="fw-semibold text-muted"><?= __('user.user') ?></td>
                                                    <td class="fw-medium"><?= $request['username'] ?></td>
							</tr>
							<tr>
                                                    <td class="fw-semibold text-muted"><?= __('user.amount_deposited') ?></td>
                                                    <td class="fw-bold text-success fs-5"><?= c_format($request['vd_amount']) ?></td>
							</tr>
							<tr>
                                                    <td class="fw-semibold text-muted"><?= __('user.payment_method') ?></td>
                                                    <td><span class="badge bg-secondary"><?= __('user.'.$request['vd_payment_method']) ?></span></td>
							</tr>
							<tr>
                                                    <td class="fw-semibold text-muted"><?= __('user.payment_status') ?></td>
                                                    <td><?= withdrwal_status($request['vd_status']) ?></td>
							</tr>
                                            </tbody>
						</table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-file-alt me-2"></i><?= __('user.submited_details') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
							<?php
									$data = json_decode($request['vd_meta'],1);
                                                $hasDetails = false;
									foreach ($data as $key => $value) {
										if(!empty($value)) {
											$hasDetails = true;
										if($key == 'payment_proof') {
                                                            echo '<tr>
                                                                <td class="fw-semibold text-muted text-capitalize">'.str_replace("_", " ", $key) .'</td>
                                                                <td><a target="_blank" href="'.base_url('assets/user_upload/'.$value).'" class="btn btn-outline-primary btn-sm"><i class="fas fa-download me-1"></i>'.$value.'</a></td>
											</tr>';
										continue;
										}
									 ?>
									<tr>
                                                    <td class="fw-semibold text-muted text-capitalize" style="width: 40%;"><?= str_replace("_", " ", $key) ?></td>
										<td><?= $value ?></td>
									</tr>
								<?php }} ?>

                                                <?php if(!$hasDetails) { ?>
									<tr>
                                                        <td colspan="2" class="text-center text-muted py-3">
                                                            <i class="fas fa-inbox mb-2 d-block" style="font-size: 2rem; opacity: 0.5;"></i>
                                                            <?= __('user.no_additional_details') ?>
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

                    <!-- Status History -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-history me-2"></i><?= __('user.status_history') ?>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-info-circle me-2 text-muted"></i><?= __('user.status') ?>
                                                    </th>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-comment me-2 text-muted"></i><?= __('user.comment') ?>
                                                    </th>
								</tr>
							</thead>
                                            <tbody id="history_container">
                                                <tr>
                                                    <td colspan="2" class="text-center py-4">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </td>
                                                </tr>
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
    </div>
</div>

<script type="text/javascript">
	function getHistory() {
		$this = $(this);
		$.ajax({
            url: '<?= base_url('admincontrol/get_vendor_deposit_history/'. $request['vd_id']) ?>',
            type: 'POST',
            dataType: 'json',
            beforeSend: function(){
                $("#history_container").html('<tr><td colspan="2" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
            },
            success: function(json){
				$("#history_container").html(json['html']);
			},
		})
	}

	getHistory()
</script>