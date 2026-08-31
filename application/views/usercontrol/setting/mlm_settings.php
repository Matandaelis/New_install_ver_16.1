<div class="container-fluid">
	<div class="card shadow-sm">
		<div class="card-header bg-primary text-white">
			<h5 class="mb-0">
				<i class="fas fa-cog me-2"></i><?= __('user.page_title_vendor_mlm_settings') ?>
			</h5>
		</div>
		<div class="card-body">
			<?php $vendorSettingTab = 'mlm_settings'; ?>
			<?php include 'mlm_menu.php'; ?>

			<form class="form-horizontal mt-4" autocomplete="off" method="post" action="" enctype="multipart/form-data" id="setting-form">
				<div class="card mb-4">
					<div class="card-header bg-light">
						<h6 class="mb-0">
							<i class="fas fa-toggle-on me-1 text-primary"></i><?= __('admin.status') ?>
						</h6>
					</div>
					<div class="card-body">
						<div class="mb-3">
							<div class="btn-group btn-group-lg w-100" role="group">
								<input type="radio" class="btn-check referlevel_status" name="referlevel[status]" value="1" id="status_enable" <?= (int)$referlevel['status'] == 1 ? 'checked' : '' ?>>
								<label class="btn btn-outline-success" for="status_enable">
									<i class="fas fa-check-circle me-1"></i><?= __('admin.enable') ?>
								</label>

								<input type="radio" class="btn-check referlevel_status" name="referlevel[status]" value="0" id="status_disable" <?= (int)$referlevel['status'] == 0 ? 'checked' : '' ?>>
								<label class="btn btn-outline-danger" for="status_disable">
									<i class="fas fa-times-circle me-1"></i><?= __('admin.disabled') ?>
								</label>
							</div>
						</div>

						<div class="alert alert-info div-toggle status-1 mb-0">
							<i class="fas fa-info-circle me-2"></i><?= __('user.enable_for_all_users') ?>
						</div>

						<div class="alert alert-secondary div-toggle status-0 mb-0">
							<i class="fas fa-info-circle me-2"></i><?= __('user.disable_for_all_users') ?>
						</div>
					</div>
				</div>

				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-store me-1 text-primary"></i><?= __('admin.local_store_refer_sale_commission') ?>
					</label>
					<select class="form-select form-select-lg" name="referlevel[autoacceptlocalstore]">
						<option value="0"><?= __('admin.on_hold') ?></option>
						<option value="1" <?= $referlevel['autoacceptlocalstore'] ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
					</select>
				</div>

				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-external-link-alt me-1 text-primary"></i><?= __('admin.external_store_refer_sale_commission') ?>
					</label>
					<select class="form-select form-select-lg" name="referlevel[autoacceptexternalstore]">
						<option value="0"><?= __('admin.on_hold') ?></option>
						<option value="1" <?= $referlevel['autoacceptexternalstore'] ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
					</select>
				</div>

				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-hand-pointer me-1 text-primary"></i><?= __('admin.action_refer_commission') ?>
					</label>
					<select class="form-select form-select-lg" name="referlevel[autoacceptaction]">
						<option value="0"><?= __('admin.on_hold') ?></option>
						<option value="1" <?= $referlevel['autoacceptaction'] ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
					</select>
				</div>

				<?php if(false){ ?>
						<div class="commi-cube">
							<div class="row">
								<div class="col-sm-3">
									<div class="comm-cube-box">
										<div class="form-group">
											<label  class="control-label"><?= __('admin.no_of_click_per_commission') ?></label>
											<input name="referlevel[click]" value="<?php echo $referlevel['click']; ?>" class="form-control" step="any" type="number" placeholder='<?= __('admin.no_of_click_per_commission') ?>'>
										</div>
										<?php foreach (array('1','2','3') as $key => $v) { ?>
											<fieldset>
												<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
												
													<div class="form-group">
														<label  class="control-label"><?= __('admin.refer_setting_click_commission') ?> (<?= $CurrencySymbol ?></span>)</label>
														<input name="referlevel_<?php echo $v ?>[commition]" value="<?php echo ${"referlevel_$v"}['commition']; ?>" class="form-control" step="any" type="number">
													</div>
											</fieldset>
										<?php } ?>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="comm-cube-box">
										<div class="form-group">
											<label  class="control-label"><?= __('admin.fix_amount_or_per') ?></label>
											<select class="form-control refer-symball-select" name="referlevel[sale_type]">
												<option symbal='%' <?php if($referlevel['sale_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.percentage') ?></option>
												<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['sale_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed"><?= __('admin.fixed') ?></option>
											</select>
										</div>
										<?php foreach (array('1','2','3') as $key => $v) { ?>
											<fieldset>
												<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
													<div class="form-group">
														<label  class="control-label"><?= __('admin.refer_setting_sale_commission') ?> (<span class="refer-symball"></span>)</label>
														<input name="referlevel_<?php echo $v ?>[sale_commition]" value="<?php echo ${"referlevel_$v"}['sale_commition']; ?>" class="form-control" step="any" type="number">
													</div>
											</fieldset>
										<?php } ?>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="comm-cube-box">
										<div class="form-group">
											<label  class="control-label"><?= __('admin.external_click') ?></label>
											<input name="referlevel[ex_click]" value="<?php echo $referlevel['ex_click']; ?>" class="form-control" step="any" type="number" placeholder='<?= __('admin.external_click') ?>'>
										</div>
										<?php foreach (array('1','2','3') as $key => $v) { ?>
											<fieldset>
												<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
													<div class="form-group">
														<label  class="control-label"><?= __('admin.external_click_commission') ?>  (<?= $CurrencySymbol ?></span>)</label>
														<input name="referlevel_<?php echo $v ?>[ex_commition]" value="<?php echo ${"referlevel_$v"}['ex_commition']; ?>" class="form-control" step="any" type="number">
													</div>
											</fieldset>
										<?php } ?>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="comm-cube-box">
										<div class="form-group">
											<label  class="control-label"><?= __('admin.external_action_click') ?></label>
											<input name="referlevel[ex_action_click]" value="<?php echo $referlevel['ex_action_click']; ?>" class="form-control" step="any" type="number" placeholder='<?= __('admin.external_action_click') ?>'>
										</div>
										<?php foreach (array('1','2','3') as $key => $v) { ?>
											<fieldset>
												<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
													<div class="form-group">
														<label  class="control-label"><?= __('admin.external_action_click_Commission') ?>  (<?= $CurrencySymbol ?></span>)</label>
														<input name="referlevel_<?php echo $v ?>[ex_action_commition]" value="<?php echo ${"referlevel_$v"}['ex_action_commition']; ?>" class="form-control" step="any" type="number">
													</div>
											</fieldset>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
				<?php } ?>

				<div class="text-end">
					<button type="submit" class="btn btn-success btn-lg px-5 btn-submit">
						<i class="fas fa-save me-2"></i><?= __('admin.save_settings') ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.referlevel_status').on('change',function(){
		$(".div-toggle").hide();
		$(".div-toggle.status-"+ $('.referlevel_status:checked').val()).show();
	});
	$('.referlevel_status:checked').trigger('change');

	$("#setting-form").on('submit',function(){
		$("#setting-form .alert-error").remove();
		var affiliate_cookie = parseInt($(".input-affiliate_cookie").val());
		if(affiliate_cookie <= 0 || affiliate_cookie > 365){
			$(".input-affiliate_cookie").after("<div class='alert alert-danger alert-error'><?= __('admin.days_between_1_to_365') ?></div>");
		}
		if($("#setting-form .alert-error").length == 0) return true;
		return false;
	});

	$(".btn-submit").on('click',function(evt){
		evt.preventDefault();
		var formData = new FormData($("#setting-form")[0]);
		$(".btn-submit").btn("loading");
		formData = formDataFilter(formData);
		$this = $("#setting-form");

		$.ajax({
		    type:'POST',
		    dataType:'json',
		    cache:false,
		    contentType: false,
		    processData: false,
		    data:formData,
		    success:function(result){
		        $(".btn-submit").btn("reset");
		        $(".alert-dismissable").remove();
		        $this.find(".has-error").removeClass("has-error");
		        $this.find("span.text-danger").remove();
		        
		        if(result['location']){
		            window.location = result['location'];
		        }

		        if(result['success']){
		        	showToast('success', result['success']);
					$("html, body").stop().animate({scrollTop:0}, 500, 'swing');
		        }

		        if(result['errors']){
		            $.each(result['errors'], function(i,j){
		                $ele = $this.find('[name="'+ i +'"]');
		                if($ele){
		                    $ele.parents(".mb-3, .mb-4").addClass("has-error");
		                    $ele.after("<span class='d-block text-danger mt-1'>"+ j +"</span>");
		                }
		            });
		        }
		    },
		})
		return false;
	});
</script>
</div>