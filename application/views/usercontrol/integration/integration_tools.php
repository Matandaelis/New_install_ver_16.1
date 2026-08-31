<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header bg-white border-bottom py-3">
					<div class="d-flex justify-content-between align-items-center">
						<h4 class="mb-0 fw-semibold"><?= __('user.integration_tools') ?></h4>
						<div class="input-group" style="max-width: 300px;">
							<span class="input-group-text bg-white">
								<i class="fas fa-search text-muted"></i>
							</span>
							<input class="form-control border-start-0" id="txt_name" onkeyup="myFunction()" placeholder="<?= __('user.search') ?>" type="search">
						</div>
					</div>
				</div>

				<div class="card-body">
					<?php if ($tools == null) {?>
						<div class="text-center py-5">
							<i class="fas fa-exchange-alt fa-5x text-muted mb-3 opacity-50"></i>
							<h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
							<p class="text-muted"><?= __('user.no_tools_yet') ?></p>
						</div>
					<?php } else {?>
						<div class="table-responsive">
							<table id="myTable" class="table table-striped table-hover align-middle mb-0">
								<thead class="table-light">
									<tr>
										<th><?= __('user.id') ?></th>
										<th style="min-width: 200px;"><?= __('user.name') ?></th>
										<th><?= __('user.type') ?></th>
										<th><?= __('user.program_name') ?> / <?= __('user.type') ?></th>
										<th><?= __('user.sale_commisssion') ?></th>
										<th><?= __('user.product_click') ?></th>
										<th><?= __('user.general_click') ?></th>
										<th><?= __('user.action_click') ?></th>
										<th style="min-width: 180px;"><?= __('user.created_date') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($tools as $key => $tool) { ?>
										<tr>
											<td class="fw-semibold">#<?= $tool['id'] ?></td>
											<td>
												<div class="mb-1 fw-semibold"><?= htmlspecialchars($tool['name']) ?></div>
												<a class="get-code btn btn-sm btn-outline-primary" href="javascript:void(0)" data-id="<?= $tool['id'] ?>">
													<i class="fas fa-code me-1"></i><?= __('user.get_code') ?>
												</a>
											</td>
											<td><span class="badge bg-primary bg-opacity-10 text-primary"><?= $tool['type'] ?></span></td>
											<td>
												<?php if($tool['program_name']){ ?>
													<span class="badge bg-secondary bg-opacity-10 text-secondary"><?= $tool['program_name'] ?></span>
												<?php } ?>
												<span class="badge bg-info bg-opacity-10 text-info"><?= $tool['tool_type'] ?></span>
											</td>
											<td>
												<?php if($tool['_tool_type'] == 'program' && $tool['sale_status']){ ?>
													<div class="position-relative">
														<button class="btn btn-sm btn-link text-decoration-none tog-btn p-0" type="button">
															<i class="fas fa-eye text-primary"></i>
														</button>
														<div class="tog-content d-none mt-2 p-2 border rounded-3 bg-light small">
															<?php 
															$comm = '';
															if($tool['commission_type'] == 'percentage'){ $comm = $tool['commission_sale'].'%'; }
															else if($tool['commission_type'] == 'fixed'){ $comm = c_format($tool['commission_sale']); }
															?>
															<div class="mb-1"><strong><?= __('user.you_will_get') ?>:</strong> <span class="badge bg-success"><?= $comm ?></span></div>
															<div class="mb-1"><strong><?= __('user.count') ?>:</strong> <?= (int)$tool['total_sale_count'] ?></div>
															<div><strong><?= __('user.amount') ?>:</strong> <?= $tool['total_sale_amount'] ?></div>
														</div>
													</div>
												<?php } else { ?>
													<span class="text-muted">-</span>
												<?php } ?>
											</td>
											<td>
												<?php if($tool['_tool_type'] == 'program' && $tool['click_status']){ ?>
													<div class="position-relative">
														<button class="btn btn-sm btn-link text-decoration-none tog-btn p-0" type="button">
															<i class="fas fa-eye text-primary"></i>
														</button>
														<div class="tog-content d-none mt-2 p-2 border rounded-3 bg-light small">
															<div class="mb-1"><strong><?= __('user.you_will_get') ?>:</strong> <span class="badge bg-info"><?= c_format($tool["commission_click_commission"]). " / ". $tool['commission_number_of_click'] ?> <?= __('user.clicks') ?></span></div>
															<div class="mb-1"><strong><?= __('user.count') ?>:</strong> <?= (int)$tool['total_click_count'] ?></div>
															<div><strong><?= __('user.amount') ?>:</strong> <?= $tool['total_click_amount'] ?></div>
														</div>
													</div>
												<?php } else { ?>
													<span class="text-muted">-</span>
												<?php } ?>
											</td>
											<td>
												<?php if($tool['_tool_type'] == 'general_click'){ ?>
													<div class="position-relative">
														<button class="btn btn-sm btn-link text-decoration-none tog-btn p-0" type="button">
															<i class="fas fa-eye text-primary"></i>
														</button>
														<div class="tog-content d-none mt-2 p-2 border rounded-3 bg-light small">
															<div class="mb-1"><strong><?= __('user.you_will_get') ?>:</strong> <span class="badge bg-warning text-dark"><?= c_format($tool["general_amount"]). " / ". $tool['general_click'] ?> <?= __('user.clicks') ?></span></div>
															<div class="mb-1"><strong><?= __('user.count') ?>:</strong> <?= (int)$tool['total_general_click_count'] ?></div>
															<div><strong><?= __('user.amount') ?>:</strong> <?= $tool['total_general_click_amount'] ?></div>
														</div>
													</div>
												<?php } else { ?>
													<span class="text-muted">-</span>
												<?php } ?>
											</td>
											<td>
												<?php if($tool['_tool_type'] == 'action'){ ?>
													<div class="position-relative">
														<button class="btn btn-sm btn-link text-decoration-none tog-btn p-0" type="button">
															<i class="fas fa-eye text-primary"></i>
														</button>
														<div class="tog-content d-none mt-2 p-2 border rounded-3 bg-light small">
															<div class="mb-1"><strong><?= __('user.you_will_get') ?>:</strong> <span class="badge bg-danger"><?= c_format($tool["action_amount"]). " / ". $tool['action_click'] ?> <?= __('user.actions') ?></span></div>
															<div class="mb-1"><strong><?= __('user.count') ?>:</strong> <?= (int)$tool['total_action_click_count'] ?></div>
															<div><strong><?= __('user.amount') ?>:</strong> <?= $tool['total_action_click_amount'] ?></div>
														</div>
													</div>
												<?php } else { ?>
													<span class="text-muted">-</span>
												<?php } ?>
											</td>
											<td><small class="text-muted"><?= $tool['created_at'] ?></small></td>
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

<div class="modal fade" id="integration-code" tabindex="-1" aria-labelledby="integrationCodeLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content"></div>
	</div>
</div>

<script type="text/javascript">
	function myFunction() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("txt_name");
		filter = input.value.toUpperCase();
		table = document.getElementById("myTable");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[1];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}       
		}
	}

	$(document).on('click', '.tog-btn', function(e){
		e.preventDefault();
		e.stopPropagation();
		var $content = $(this).siblings('.tog-content');
		$('.tog-content').not($content).addClass('d-none');
		$content.toggleClass('d-none');
	});

	$(document).on('click', function(e){
		if(!$(e.target).closest('.tog-btn, .tog-content').length){
			$('.tog-content').addClass('d-none');
		}
	});

	$(".tool-remove-link").on('click',function(){
		if(!confirm('<?= __('user.are_you_sure') ?>')) return false;
		return true;
	})

	$(".get-code").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("integration/tool_get_code/usercontrol") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ 
				$this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i><?= __('user.loading') ?>');
			},
			complete:function(){ 
				$this.prop('disabled', false).html('<i class="fas fa-code me-1"></i><?= __('user.get_code') ?>');
			},
			success:function(json){
				if(json['html']){
					$("#integration-code .modal-content").html(json['html']);
					$("#integration-code").modal("show");
				}
				if(json['error']){
					if(typeof showToast === 'function'){
						showToast('error', json['error']);
					}
				}
			},
		})
	})
</script>
