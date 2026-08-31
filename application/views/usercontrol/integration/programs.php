<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
					<h4 class="mb-0 fw-semibold"><?= __('admin.integration_programs') ?></h4>
					<a class="btn btn-primary" href="<?= base_url('usercontrol/programs_form') ?>">
						<i class="fas fa-plus me-2"></i><?= __('admin.add_new') ?>
					</a>
				</div>

				<div class="card-body">
					<?php if ($programs == null) { ?>
						<div class="text-center py-5">
							<i class="fas fa-exchange-alt fa-5x text-muted mb-3 opacity-50"></i>
							<h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
							<p class="text-muted"><?= __('user.no_programs_yet') ?></p>
							<a href="<?= base_url('usercontrol/programs_form') ?>" class="btn btn-primary mt-3">
								<i class="fas fa-plus me-2"></i><?= __('admin.add_new') ?>
							</a>
						</div>
					<?php } else { ?>
						<div class="table-responsive">
							<table class="table table-hover table-striped align-middle mb-0">
								<thead class="table-light">
									<tr>
										<th><?= __('admin.id') ?></th>
										<th><?= __('admin.name') ?></th>
										<th><?= __('admin.sale_commission') ?></th>
										<th><?= __('admin.click_commission') ?></th>
										<th><?= __('admin.sale_status') ?></th>
										<th><?= __('admin.click_status') ?></th>
										<th><?= __('admin.status') ?></th>
										<th class="text-end"><?= __('user.action') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($programs as $key => $program) { ?>
										<tr id="program-row-<?= $program['id'] ?>">
											<td class="fw-semibold">#<?= $program['id'] ?></td>
											<td><?= htmlspecialchars($program['name']) ?></td>
											<td>
												<?php 
													if($program['vendor_id']){
														echo '<div class="mb-1"><span class="badge bg-secondary bg-opacity-10 text-secondary me-2">'.__('user.admin').'</span>';
														if($program['admin_sale_status']){
															if($program['admin_commission_type'] == 'percentage'){ 
																echo '<span class="badge bg-success">'.$program['admin_commission_sale'].'%</span>'; 
															} else if($program['admin_commission_type'] == 'fixed'){ 
																echo '<span class="badge bg-success">'.c_format($program['admin_commission_sale']).'</span>'; 
															} else { 
																echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>'; 
															}
														} else{
															echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>';
														}
														echo '</div>';

														echo '<div><span class="badge bg-primary bg-opacity-10 text-primary me-2">'.__('user.affiliate').'</span>';
														if($program['sale_status']){
															if($program['commission_type'] == 'percentage'){ 
																echo '<span class="badge bg-success">'.$program['commission_sale'].'%</span>'; 
															} else if($program['commission_type'] == 'fixed'){ 
																echo '<span class="badge bg-success">'.c_format($program['commission_sale']).'</span>'; 
															} else { 
																echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>'; 
															}
														} else{
															echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>';
														}
														echo '</div>';
													} else{
														if($program['sale_status']){
															if($program['commission_type'] == 'percentage'){ 
																echo '<span class="badge bg-success">'.$program['commission_sale'].'%</span>'; 
															} else if($program['commission_type'] == 'fixed'){ 
																echo '<span class="badge bg-success">'.c_format($program['commission_sale']).'</span>'; 
															} else { 
																echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>'; 
															}
														} else{
															echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>';
														}
													}
												?>
											</td>
											<td>
												<?php
													if($program['vendor_id']){
														echo '<div class="mb-1"><span class="badge bg-secondary bg-opacity-10 text-secondary me-2">'.__('user.admin').'</span>';
														if($program['admin_click_status']){
															if($program["admin_commission_click_commission"] && $program['admin_commission_number_of_click']){
																echo '<span class="badge bg-info">'.c_format($program["admin_commission_click_commission"]). " / ". $program['admin_commission_number_of_click'] ." ".__('user.clicks').'</span>';
															} else { 
																echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>'; 
															}
														} else{
															echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>';
														}
														echo '</div>';

														echo '<div><span class="badge bg-primary bg-opacity-10 text-primary me-2">'.__('user.affiliate').'</span>';
														if($program['click_status']){
															echo '<span class="badge bg-info">'.c_format($program["commission_click_commission"]). " / ". $program['commission_number_of_click'] ." ".__('user.clicks').'</span>';
														} else{
															echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>';
														}
														echo '</div>';
													} else{
														if($program['click_status']){
															echo '<span class="badge bg-info">'.c_format($program["commission_click_commission"]). " / ". $program['commission_number_of_click'] ." ".__('user.clicks').'</span>';
														} else{
															echo '<span class="badge bg-secondary">'.__('user.not_set').'</span>';
														}
													}
												?>
											</td>
											<td>
												<?php
													if($program['vendor_id']){
														echo '<div class="mb-1"><span class="badge bg-secondary bg-opacity-10 text-secondary me-2">'.__('user.admin').'</span>';
														echo (int)$program['admin_sale_status'] ? '<span class="badge bg-success">'.__('user.enable').'</span>' : '<span class="badge bg-danger">'.__('user.disable').'</span>';
														echo '</div>';
														echo '<div><span class="badge bg-primary bg-opacity-10 text-primary me-2">'.__('user.affiliate').'</span>';
														echo (int)$program['sale_status'] ? '<span class="badge bg-success">'.__('user.enable').'</span>' : '<span class="badge bg-danger">'.__('user.disable').'</span>';
														echo '</div>';
													} else {
														echo (int)$program['sale_status'] ? '<span class="badge bg-success">'.__('user.enable').'</span>' : '<span class="badge bg-danger">'.__('user.disable').'</span>';
													}
												?>
											</td>
											<td>
												<?php
													if($program['vendor_id']){
														echo '<div class="mb-1"><span class="badge bg-secondary bg-opacity-10 text-secondary me-2">'.__('user.admin').'</span>';
														echo (int)$program['admin_click_status'] ? '<span class="badge bg-success">'.__('user.enable').'</span>' : '<span class="badge bg-danger">'.__('user.disable').'</span>';
														echo '</div>';
														echo '<div><span class="badge bg-primary bg-opacity-10 text-primary me-2">'.__('user.affiliate').'</span>';
														echo (int)$program['click_status'] ? '<span class="badge bg-success">'.__('user.enable').'</span>' : '<span class="badge bg-danger">'.__('user.disable').'</span>';
														echo '</div>';
													} else {
														echo (int)$program['click_status'] ? '<span class="badge bg-success">'.__('user.enable').'</span>' : '<span class="badge bg-danger">'.__('user.disable').'</span>';
													}
												?>	
											</td>
											<td><?= program_status($program['status']) ?></td>
											<td class="text-end">
												<div class="btn-group" role="group">
													<a class="btn btn-sm btn-primary" href="<?= base_url('usercontrol/programs_form/'. $program['id']) ?>">
														<i class="fas fa-edit me-1"></i><?= __('admin.edit') ?>
													</a>
													<button <?= $program['associate_programns'] ? 'disabled' : '' ?> class="btn btn-sm btn-danger delete-program" data-id="<?= $program['id'] ?>">
														<i class="fas fa-trash me-1"></i><?= __('admin.delete') ?>
													</button>
												</div>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="message-model" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="messageModalLabel">
					<i class="fas fa-info-circle me-2"></i><?= __('user.notification') ?>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center py-4"></div>
			<div class="modal-footer border-top-0">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					<i class="fas fa-times me-2"></i><?= __('user.close') ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".delete-program").on('click',function(){
		$this = $(this);
		if(!confirm('<?= __('user.are_you_sure') ?>')) return false;
		$.ajax({
			url:'<?= base_url('usercontrol/delete_programs_form/') ?>',
			type:'POST',
			dataType:'json',
			data:{id: $this.attr("data-id")},
			beforeSend:function(){
				$this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i><?= __('user.deleting') ?>');
			},
			complete:function(){
				$this.prop('disabled', false).html('<i class="fas fa-trash me-1"></i><?= __('admin.delete') ?>');
			},
			success:function(json){
				if(json['success']){
					$("#program-row-" + $this.attr("data-id")).fadeOut(400, function(){
						$(this).remove();
						if($("table tbody tr").length === 0){
							location.reload();
						}
					});
					if(typeof showToast === 'function'){
						showToast('success', json['message'] || '<?= __('user.program_deleted_successfully') ?>');
					}
				}
				if(json['message'] && !json['success']){
					$("#message-model .modal-body").html(json['message']);
					$("#message-model").modal("show");
				}
			},
		})
	})
</script>
