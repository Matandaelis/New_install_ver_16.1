<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
					<h5 class="mb-0 fw-semibold"><?= __('admin.integration_programs') ?></h5>
					<a class="btn btn-secondary" href="<?= base_url('usercontrol/programs') ?>">
						<i class="fas fa-arrow-left me-2"></i><?= __('admin.back') ?>
					</a>
				</div>

				<div class="card-body">
					<form action="" method="get" id="form_program">
						<div class="row">
							<div class="col-lg-6">
								<div class="mb-3">
									<label class="form-label fw-semibold"><?= __('user.status'); ?></label>
									<div><?= program_status($programs['status']) ?></div>
								</div>

								<?php if($programs['status'] == '0'){ ?>
									<div class="alert alert-warning d-flex align-items-center">
										<i class="fas fa-exclamation-triangle me-2"></i>
										<div><?= __('user.when_program_under_review') ?></div>
									</div>
								<?php } ?>

								<input name="program_id" type="hidden" value="<?= isset($programs) ? $programs['id'] : '0' ?>">
								
								<div class="mb-4">
									<label class="form-label fw-semibold"><?= __('admin.program_name') ?></label>
									<input class="form-control" name="name" type="text" value="<?= isset($programs) ? htmlspecialchars($programs['name']) : '' ?>" placeholder="<?= __('user.enter_program_name') ?>">
								</div>

								<div class="border rounded-3 p-3 mb-4 bg-light bg-opacity-50">
									<h6 class="fw-semibold mb-3 text-secondary">
										<i class="fas fa-user-shield me-2"></i><?= __('user.admin_commission') ?>
									</h6>
									<?php 
										if((int)$programs['id'] == 0){
											$programs['admin_click_status'] = $market_vendor['click_status'];
											$programs['admin_commission_click_commission'] = $market_vendor['commission_click_commission'];
											$programs['admin_commission_number_of_click'] = $market_vendor['commission_number_of_click'];
											$programs['admin_sale_status'] = $market_vendor['sale_status'];
											$programs['admin_commission_type'] = $market_vendor['commission_type'];
											$programs['admin_commission_sale'] = $market_vendor['commission_sale'];
										}
									?>
									<div class="row">
										<div class="col-md-6 mb-2">
											<label class="form-label fw-semibold small"><?= __('user.click_commission') ?></label>
											<div>
												<?php if($programs['admin_click_status']){ ?>
													<span class="badge bg-info"><?= c_format($programs['admin_commission_click_commission']) ?> / <?= (int)$programs['admin_commission_number_of_click'] ?> <?= __('user.clicks') ?></span>
												<?php } else {?>
													<span class="badge bg-secondary"><?= __('user.disabled') ?></span>
												<?php } ?>
											</div>
										</div>
										<div class="col-md-6 mb-2">
											<label class="form-label fw-semibold small"><?= __('user.sale_commission') ?></label>
											<div>
												<?php if($programs['admin_sale_status']){ ?>
													<span class="badge bg-success">
														<?php 
															if($programs['admin_commission_type'] == 'percentage'){
																echo (float)$programs['admin_commission_sale']."%";
															}
															else if($programs['admin_commission_type'] == 'fixed'){
																echo c_format($programs['admin_commission_sale']);
															} else{
																echo __('user.not_set');
															}
														?>
													</span>
												<?php } else {?>
													<span class="badge bg-secondary"><?= __('user.disabled') ?></span>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-6">
								<div class="card border">
									<div class="card-header bg-primary bg-opacity-10 border-bottom">
										<h6 class="mb-0 fw-semibold text-primary">
											<i class="fas fa-comments me-2"></i><?= __('user.vendor_commnts') ?>
										</h6>
									</div>
									<div class="card-body p-3" style="max-height: 400px; overflow-y: auto;">
										<?php $comment = json_decode($programs['comment'],1); ?>
										<?php if($comment){ ?>
											<ul class="list-unstyled mb-0">
												<?php foreach ($comment as $key => $value) { ?>
													<li class="mb-3 <?= $value['from'] == 'affiliate' ? 'text-end' : '' ?>">
														<div class="d-inline-block p-2 rounded-3 <?= $value['from'] == 'affiliate' ? 'bg-primary text-white' : 'bg-light' ?>" style="max-width: 85%;">
															<?= htmlspecialchars($value['comment']) ?>
														</div>
													</li>
												<?php } ?>
											</ul>
										<?php } else { ?>
											<div class="text-center text-muted py-4">
												<i class="fas fa-comment-slash fa-3x mb-2 opacity-50"></i>
												<p class="mb-0"><?= __('user.no_comments_yet') ?></p>
											</div>
										<?php } ?>
									</div>
									<div class="card-footer bg-white border-top p-2">
										<textarea class="form-control border-0" placeholder="<?= __('user.enter_message_and_save_program_to_send') ?>" name="comment" rows="2"></textarea>
									</div>
								</div>
							</div>
						</div>

						<!-- Advanced Settings (Collapsible) -->
						<div class="mt-4">
							<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#vendorAdvancedProgramSettings" aria-expanded="false">
								<i class="fas fa-cog me-2"></i><?= __('admin.advanced_settings') ?>
								<i class="fas fa-chevron-down ms-2"></i>
							</button>
							<div class="collapse mt-3" id="vendorAdvancedProgramSettings">
						<div class="row mt-3">
							<div class="col-lg-6">
								<div class="card border border-success border-opacity-25">
									<div class="card-header bg-success bg-opacity-10 text-center border-bottom border-success border-opacity-25">
										<h6 class="mb-0 fw-semibold text-success">
											<i class="fas fa-hand-holding-usd me-2"></i><?= __('admin.other_affiliate_sale_settings') ?>
										</h6>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6 mb-3">
												<label class="form-label fw-semibold"><?= __('admin.commission_type') ?></label>
												<select name="commission_type" class="form-select">
													<option value=""><?= __('admin.select_product_commission_type') ?></option>
													<option <?= (isset($programs) && $programs['commission_type'] == 'percentage') ? 'selected' : '' ?> value="percentage"><?= __('admin.percentage') ?></option>
													<option <?= (isset($programs) && $programs['commission_type'] == 'fixed') ? 'selected' : '' ?> value="fixed"><?= __('admin.fixed') ?></option>
												</select>
											</div>
											<div class="col-sm-6 mb-3">
												<label class="form-label fw-semibold"><?= __('admin.commission_for_sale') ?></label>
												<div class="input-group">
													<span class="input-group-text">
														<?= (isset($programs) && $programs['commission_type'] == 'percentage') ? '%'  : $CurrencySymbol ?>
													</span>
													<input class="form-control only-number-allow" name="commission_sale" type="text" value="<?= isset($programs) ? $programs['commission_sale'] : '' ?>" placeholder="0">
												</div>
											</div>
										</div>

										<div class="mb-0">
											<label class="form-label fw-semibold"><?= __('admin.sale_status') ?></label>
											<div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="sale_status" id="sale_disabled" value="0" checked>
													<label class="form-check-label" for="sale_disabled"><?= __('admin.disable') ?></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="sale_status" id="sale_enabled" value="1" <?= (isset($programs) && $programs['sale_status']) ? 'checked' : '' ?>>
													<label class="form-check-label" for="sale_enabled"><?= __('admin.enable') ?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-6">
								<div class="card border border-info border-opacity-25">
									<div class="card-header bg-info bg-opacity-10 text-center border-bottom border-info border-opacity-25">
										<h6 class="mb-0 fw-semibold text-info">
											<i class="fas fa-mouse-pointer me-2"></i><?= __('admin.other_affiliate_click_settings') ?>
										</h6>
									</div>
									<div class="card-body">
										<div class="mb-3">
											<label class="form-label fw-semibold"><?= __('user.clicks_allow') ?></label>
											<select name="click_allow" class="form-select">
												<option <?= (isset($programs) && $programs['click_allow'] == 'multiple') ? 'selected' : '' ?> value="multiple"><?= __('user.allow_multi_clicks') ?></option>
												<option <?= (isset($programs) && $programs['click_allow'] == 'single') ? 'selected' : '' ?> value="single"><?= __('user.allow_single_click') ?></option>
											</select>
										</div>

										<div class="row">
											<div class="col-sm-6 mb-3">
												<label class="form-label fw-semibold"><?= __('admin.number_of_click') ?></label>
												<input class="form-control only-number-allow" name="commission_number_of_click" type="text" value="<?= isset($programs) ? $programs['commission_number_of_click'] : '' ?>" placeholder="0">
											</div>
											<div class="col-sm-6 mb-3">
												<label class="form-label fw-semibold"><?= __('admin.amount_per_click') ?></label>
												<div class="input-group">
													<span class="input-group-text"><?= $CurrencySymbol ?></span>
													<input class="form-control only-number-allow" name="commission_click_commission" type="text" value="<?= isset($programs) ? $programs['commission_click_commission'] : '' ?>" placeholder="0">
												</div>
											</div>
										</div>

										<div class="mb-0">
											<label class="form-label fw-semibold"><?= __('admin.click_status') ?></label>
											<div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="click_status" id="click_disabled" value="0" checked>
													<label class="form-check-label" for="click_disabled"><?= __('admin.disable') ?></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="click_status" id="click_enabled" value="1" <?= (isset($programs) && $programs['click_status']) ? 'checked' : '' ?>>
													<label class="form-check-label" for="click_enabled"><?= __('admin.enable') ?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
							</div>
						</div>
					</form>	
				</div>

				<div class="card-footer bg-white border-top text-end">
					<button class="btn btn-primary btn-lg btn-save">
						<i class="fas fa-save me-2"></i><?= __('admin.save') ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("select[name='commission_type']").on('change',function(){
		if($(this).val() == 'percentage')
			$("input[name='commission_sale']").siblings('.input-group-text').text('%');
		else
			$("input[name='commission_sale']").siblings('.input-group-text').text('<?= $CurrencySymbol ?>');
	})

	$(".btn-save").on('click',function(){
		$this = $("#form_program");
		$btn = $(this);
		
		$.ajax({
			url:'<?= base_url('usercontrol/editProgram') ?>',
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			beforeSend:function(){
				$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i><?= __('user.saving') ?>');
			},
			complete:function(){
				$btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __('admin.save') ?>');
			},
			success:function(result){
				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();
				$this.find(".is-invalid").removeClass("is-invalid");
				$this.find(".invalid-feedback").remove();
				
				if(result['location']){ 
					if(typeof showToast === 'function'){
						showToast('success', '<?= __('user.program_saved_successfully') ?>');
					}
					setTimeout(function(){ window.location = result['location']; }, 1000);
				}

				if(result['errors']){
					$.each(result['errors'], function(i,j){
						$ele = $this.find('[name="'+ i +'"]');
						if($ele.length){
							$ele.addClass("is-invalid");
							$ele.after("<div class='invalid-feedback d-block'>"+ j +"</div>");
						}
					});
					if(typeof showToast === 'function'){
						showToast('error', '<?= __('user.please_fix_errors') ?>');
					}
				}

				if(result['success'] && result['message']){
					if(typeof showToast === 'function'){
						showToast('success', result['message']);
					}
				}
			},
		})
	})
</script>
