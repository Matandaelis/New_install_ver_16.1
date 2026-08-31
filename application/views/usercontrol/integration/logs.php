<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<div>
					<h5 class="pull-left"><?php echo __('user.integration_logs') ?></h5>
				</div>
			</div>

			<div class="card-body">

				<div class="well">
					<form>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label"><?= __('user.type') ?></label>
									<?php $selected = isset($_GET['type']) ? $_GET['type'] : ''; ?>
									<select class="form-control" name="type">
										<option value=""><?= __('user.all') ?></option>
										<option <?= $selected == 'action' ? 'selected' : '' ?> value="action"><?= __('user.action') ?></option>
										<option <?= $selected == 'integration_sale' ? 'selected' : '' ?> value="integration_sale"><?= __('user.integration_sale') ?></option>
										<option <?= $selected == 'product_click' ? 'selected' : '' ?> value="product_click"><?= __('user.product_click') ?></option>
										<option <?= $selected == 'store_sale' ? 'selected' : '' ?> value="store_sale"><?= __('user.store_sale') ?></option>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label d-block">&nbsp;</label>
									<div>
										<button class="btn btn-primary" type="submit"><?= __('user.filter') ?></button>
									</div>
								</div>
							</div>
							<div class="col-sm-3"></div>
						</div>
					</form>
				</div>
				    
				<div class="table-rep-plugin">
					<?php if ($logs == null) { ?>
						<div class="text-center mt-5">
							<div class="d-flex justify-content-center align-items-center flex-column mt-5">
								<i class="fas fa-exchange-alt fa-5x text-muted"></i>
								<h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
							</div>
						</div>
					<?php } else { ?>
						<div class="table-responsive">
							<table class="table-tiny toggle-tr w-100">
								<thead>
									<tr>
										<th width="50px" class="text-left"><?= __('user.id') ?></th>
										<th class="text-left"><?= __('user.website') ?></th>
							            <th class="text-left"><?= __('user.ip') ?></th>
							            <th class="text-left"><?= __('user.created_at') ?></th>
							            <th class="text-left"><?= __('user.click_type') ?></th>
							            <th class="text-center" width="110px"><?= __('user.time_spent') ?></th>
							            <th width="40px" class="text-center">#</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($logs as $key => $log) { ?>
										<tr class="toggler">
											<td class="text-left"><?= $log['id'] ?></td>
											<td class="text-left"><?= $log['base_url'] ?></td>
								            <td class="text-left"><?= $log['flag'] ?> <?= $log['ip'] ?> - <small><?= $log['country_code'] ?></small></td>
								            <td class="text-left"><?= $log['created_at'] ?></td>
								            <td class="text-left"><?= $log['click_type'] ?></td>
								            <td class="text-center">
								            	<?php if(isset($log['time_spent']) && $log['time_spent'] !== null): ?>
								            		<?php
								            			$ts = (int)$log['time_spent'];
								            			if($ts >= 3600){
								            				$fmt = floor($ts/3600) . 'h ' . floor(($ts%3600)/60) . 'm ' . ($ts%60) . 's';
								            			} elseif($ts >= 60){
								            				$fmt = floor($ts/60) . 'm ' . ($ts%60) . 's';
								            			} else {
								            				$fmt = $ts . 's';
								            			}
								            		?>
								            		<span class="badge bg-success bg-opacity-10 text-success fw-semibold px-2 py-1 rounded-pill"><i class="far fa-clock me-1"></i><?= $fmt ?></span>
								            	<?php else: ?>
								            		<span class="text-muted">-</span>
								            	<?php endif; ?>
								            </td>
								            <td class="text-center"><button class="btn btn-sm btn-outline-secondary rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;"><i class="fa fa-chevron-down small"></i></button></td>
										</tr>
										<tr style="display: none">
											<td></td>
											<td colspan="6">
												<div class="bg-light rounded p-3 my-1">
													<div class="row g-2">
														<div class="col-sm-3">
															<small class="fw-bold text-dark"><?= __('user.page') ?>:</small>
															<small class="text-muted ms-1"><?= $log['link'] ?></small>
														</div>
														<div class="col-sm-3">
															<small class="fw-bold text-dark"><?= __('user.browser') ?>:</small>
															<small class="text-muted ms-1"><?= $log['browserName'] ?> <?= $log['browserVersion'] ?></small>
														</div>
														<div class="col-sm-3">
															<small class="fw-bold text-dark"><?= __('user.os_platform') ?>:</small>
															<small class="text-muted ms-1"><?= $log['osPlatform'] ?> <?= $log['osVersion'] ?></small>
														</div>
														<?php if(!empty($log['mobileName'])): ?>
														<div class="col-sm-3">
															<small class="fw-bold text-dark"><?= __('user.mobile_name') ?>:</small>
															<small class="text-muted ms-1"><?= $log['mobileName'] ?></small>
														</div>
														<?php endif; ?>
													</div>
													<?php if(isset($log['time_spent']) && $log['time_spent'] !== null): ?>
													<div class="d-flex flex-wrap align-items-center gap-3 bg-white rounded border p-2 mt-2">
														<div class="d-flex align-items-center gap-2">
															<i class="fas fa-sign-in-alt text-primary"></i>
															<div>
																<small class="d-block fw-bold text-muted"><?= __('user.page_open_time') ?></small>
																<small class="text-dark"><?= $log['page_open_time'] ?></small>
															</div>
														</div>
														<div class="d-flex align-items-center gap-2">
															<i class="fas fa-sign-out-alt text-primary"></i>
															<div>
																<small class="d-block fw-bold text-muted"><?= __('user.page_close_time') ?></small>
																<small class="text-dark"><?= $log['page_close_time'] ?></small>
															</div>
														</div>
														<div class="ms-auto">
															<?php
																$ts = (int)$log['time_spent'];
																if($ts >= 3600){
																	$fmt = floor($ts/3600) . 'h ' . floor(($ts%3600)/60) . 'm ' . ($ts%60) . 's';
																} elseif($ts >= 60){
																	$fmt = floor($ts/60) . 'm ' . ($ts%60) . 's';
																} else {
																	$fmt = $ts . 's';
																}
															?>
															<span class="badge bg-primary rounded-pill px-3 py-2 fw-bold"><i class="far fa-clock me-1"></i><?= $fmt ?></span>
														</div>
													</div>
													<?php endif; ?>
												</div>
											</td>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="100%"><ul class="pagination"><?= $pagination ?></ul></td>
									</tr>
								</tfoot>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="integration-code"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script type="text/javascript">
	$(".wallet-toggle .tog").on('click',function(){
		$(this).parents(".wallet-toggle").find("> div").toggleClass("hide");
	})
	$(".tool-remove-link").on('click',function(){
		if(!confirm('<?= __('user.are_you_sure') ?>')) return false;
		return true;
	})

	$(".toggle-tr tbody tr.toggler").on('click',function(){
		var $btn = $(this).find('.btn i');
		$(this).next('tr').slideToggle('fast', function(){
			if($(this).is(':visible')){
				$btn.removeClass('fa-chevron-down').addClass('fa-chevron-up');
			} else {
				$btn.removeClass('fa-chevron-up').addClass('fa-chevron-down');
			}
		});
	})

	$(".get-code").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("integration/tool_get_code") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				if(json['html']){
					$("#integration-code .modal-content").html(json['html']);
					$("#integration-code").modal("show");
				}
			},
		})
	})
</script>