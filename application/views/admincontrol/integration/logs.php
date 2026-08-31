<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<div>
					<h4 class="mt-0 header-title pull-left"><?php echo __('admin.integration_logs') ?></h4>
					<div class="pull-right">
						<button class="btn btn-danger btn-sm delete-selected"><?php echo __('admin.delete_selected') ?></button>
					</div>
				</div>
			</div>

			<div class="card-body">

				<div class="well">
					<form>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label"><?php echo __('admin.type') ?></label>
									<?php $selected = isset($_GET['type']) ? $_GET['type'] : ''; ?>
									<select class="form-control" name="type">
										<option value=""><?= __('admin.all') ?></option>
										<option <?= $selected == 'action' ? 'selected' : '' ?> value="action"><?= __('admin.action') ?></option>
										<option <?= $selected == 'integration_sale' ? 'selected' : '' ?> value="integration_sale"><?= __('admin.integration_sale') ?></option>
										<option <?= $selected == 'product_click' ? 'selected' : '' ?> value="product_click"><?= __('admin.product_click') ?></option>
										<option <?= $selected == 'store_sale' ? 'selected' : '' ?> value="store_sale"><?= __('admin.store_sale') ?></option>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label d-block">&nbsp;</label>
									<div>
										<button class="btn btn-primary" type="submit"><?php echo __('admin.filter') ?></button>
									</div>
								</div>
							</div>
							<div class="col-sm-3"></div>
						</div>
					</form>
				</div>
				<div class="table-rep-plugin">
				    
				    <div class="text-center">
                        <?php if ($logs ==null) {?>
							<div class="text-center mt-5">
							 <div class="d-flex justify-content-center align-items-center flex-column mt-5">
								 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
								 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
							 </div>
							</div>
                        <?php } else { ?>
	                        <div class="table-responsive b-0" data-pattern="priority-columns">
	                            <table id="tech-companies-1" class="table-tiny toggle-tr w-100">
									<thead>
										<tr>
											<th class="text-left" width="20px"><input type="checkbox" class="select-all"></th>
											<th class="text-left" width="50px"><?= __('admin.id') ?></th>
											<th class="text-left" width="200px"><?= __('admin.user_name') ?></th>
											<th class="text-left"><?= __('admin.website') ?></th>
								            <th class="text-left" width="190px"><?= __('admin.ip') ?></th>
								            <th class="text-left" width="180px"><?= __('admin.created_at') ?></th>
								            <th class="text-left" width="140px"><?= __('admin.click_type') ?></th>
								            <th class="text-center" width="110px"><?= __('admin.time_spent') ?></th>
								            <th class="text-center" width="40px"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($logs as $key => $log) { ?>
											<tr class="toggler">
												<td><input type="checkbox" name="ids[]" value="<?= $log['id'] ?>" class="select-single"></td>
												<td class="text-left"><?= $log['id'] ?></td>
												<td class="text-left"><?= $log['username'] ?></td>
												<td class="text-left"><?= $log['base_url'] ?></td>
									            <td class="text-left"><?= $log['flag'] ?> <?= $log['ip'] ?> - <small><?= $log['country_code'] ?></small></td>
									            <td class="text-left"><?= $log['created_at'] ?></td>
									            <td class="text-left"><?= $log['click_type'] ?></td>
									            <td class="text-center">
									            	<?php if($log['time_spent'] !== null): ?>
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
												<td colspan="8">
													<div class="bg-light rounded p-3 my-1">
														<div class="mb-2 pb-2 border-bottom">
															<small class="fw-bold text-muted text-uppercase"><i class="fas fa-globe me-1"></i><?= __('admin.page') ?> & <?= __('admin.browser') ?></small>
														</div>
														<div class="row g-2">
															<div class="col-sm-3">
																<small class="fw-bold text-dark"><?= __('admin.page') ?>:</small>
																<small class="text-muted ms-1"><?= $log['link'] ?></small>
															</div>
															<div class="col-sm-3">
																<small class="fw-bold text-dark"><?= __('admin.browser') ?>:</small>
																<small class="text-muted ms-1"><?= $log['browserName'] ?> <?= $log['browserVersion'] ?></small>
															</div>
															<div class="col-sm-3">
																<small class="fw-bold text-dark"><?= __('admin.os_platform') ?>:</small>
																<small class="text-muted ms-1"><?= $log['osPlatform'] ?> <?= $log['osVersion'] ?></small>
															</div>
															<?php if(!empty($log['mobileName'])): ?>
															<div class="col-sm-3">
																<small class="fw-bold text-dark"><?= __('admin.mobile_name') ?>:</small>
																<small class="text-muted ms-1"><?= $log['mobileName'] ?></small>
															</div>
															<?php endif; ?>
														</div>
														<?php if($log['time_spent'] !== null): ?>
														<div class="mt-3 mb-1 pb-2 border-bottom">
															<small class="fw-bold text-muted text-uppercase"><i class="far fa-clock me-1"></i><?= __('admin.time_spent') ?> <?= __('admin.details') ?></small>
														</div>
														<div class="d-flex flex-wrap align-items-center gap-3 bg-white rounded border p-2">
															<div class="d-flex align-items-center gap-2">
																<i class="fas fa-sign-in-alt text-primary"></i>
																<div>
																	<small class="d-block fw-bold text-muted"><?= __('admin.page_open_time') ?></small>
																	<small class="text-dark"><?= $log['page_open_time'] ?></small>
																</div>
															</div>
															<div class="d-flex align-items-center gap-2">
																<i class="fas fa-sign-out-alt text-primary"></i>
																<div>
																	<small class="d-block fw-bold text-muted"><?= __('admin.page_close_time') ?></small>
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
						<?PHP } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="integration-code"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script type="text/javascript">
	$(".select-all").on('change',function(){
		$(".select-single").prop("checked", $(this).prop("checked")).trigger("change");
	});

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

	$(".select-single").on('change',function(){
		if($(".select-single:checked").length == 0){
			$(".delete-selected").hide();
		} else {
			$(".delete-selected").show();
		}
	})

	$(".delete-selected").on('click',function(){
		if(!confirm("<?= __('admin.are_you_sure') ?>")) return false;

		$this = $(this);
		$.ajax({
			url:'<?= base_url("integration/delete_log") ?>',
			type:'POST',
			dataType:'json',
			data:$(".select-single:checked"),
			beforeSend:function(){ $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...'); },
			complete:function(){ $this.prop('disabled', false).html($this.data('original-text') || 'Submit'); },
			success:function(json){
				window.location.reload();
			},
		})
	})

	$(".wallet-toggle .tog").on('click',function(){
		$(this).parents(".wallet-toggle").find("> div").toggleClass("hide");
	})
	$(".tool-remove-link").on('click',function(){
		if(!confirm("<?= __('admin.are_you_sure') ?>")) return false;
		return true;
	})

	$(".get-code").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("integration/tool_get_code") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...'); },
			complete:function(){ $this.prop('disabled', false).html($this.data('original-text') || 'Submit'); },
			success:function(json){
				if(json['html']){
					$("#integration-code .modal-content").html(json['html']);
					$("#integration-code").modal("show");
				}
			},
		})
	})
</script>