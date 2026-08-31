<?php if($mlm_status){ ?>
<div class="container-fluid px-4 pb-4">
	<div class="row">
		<div class="col-12">
			<?php get_instance()->load->view('admincontrol/setting/_mlm_nav'); ?>
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h4 class="fw-bold mb-0"><?= __('admin.mlm_settings') ?></h4>
			</div>

			<div class="card shadow-sm border-0">
				<div class="card-body">
					<form method="post" action="" enctype="multipart/form-data" id="setting-form">
						<div class="row g-4">
							<div class="col-12">
								<div class="card border-0 bg-gradient-light shadow-sm">
									<div class="card-header bg-transparent border-0 pb-2">
										<div class="d-flex align-items-center">
											<div class="bg-primary rounded-circle p-2 me-3">
												<i class="fas fa-toggle-on text-white"></i>
											</div>
											<div>
												<h5 class="mb-0 text-primary fw-bold"><?= __('admin.status') ?></h5>
												<small class="text-muted"><?= __('admin.refer_level_status') ?></small>
											</div>
										</div>
									</div>
									<div class="card-body pt-0">
										<div class="row g-4">
											<div class="col-lg-4 col-md-6">
												<div class="form-check mlm_css-form-check-card">
													<input class="form-check-input referlevel_status" type="radio" name="referlevel[status]" id="enable" value="1" <?= (int)$referlevel['status'] == 1 ? 'checked' : '' ?>>
													<label class="form-check-label w-100" for="enable">
														<div class="card border-2 h-100 position-relative overflow-hidden">
															<div class="position-absolute top-0 end-0 bg-success opacity-10" style="width: 60px; height: 60px; border-radius: 0 0 0 100%;"></div>
															<div class="card-body text-center position-relative">
																<div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
																	<i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
																</div>
																<h6 class="card-title fw-bold text-dark"><?= __('admin.enable') ?></h6>
																<p class="text-muted small mb-0"><?= __('admin.enable_for_all_users') ?></p>
															</div>
														</div>
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-6">
												<div class="form-check mlm_css-form-check-card">
													<input class="form-check-input referlevel_status" type="radio" name="referlevel[status]" id="disabled" value="0" <?= (int)$referlevel['status'] == 0 ? 'checked' : '' ?>>
													<label class="form-check-label w-100" for="disabled">
														<div class="card border-2 h-100 position-relative overflow-hidden">
															<div class="position-absolute top-0 end-0 bg-danger opacity-10" style="width: 60px; height: 60px; border-radius: 0 0 0 100%;"></div>
															<div class="card-body text-center position-relative">
																<div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
																	<i class="fas fa-times-circle text-danger" style="font-size: 1.5rem;"></i>
																</div>
																<h6 class="card-title fw-bold text-dark"><?= __('admin.disabled') ?></h6>
																<p class="text-muted small mb-0"><?= __('admin.disable_for_all_users') ?></p>
															</div>
														</div>
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-6">
												<div class="form-check mlm_css-form-check-card">
													<input class="form-check-input referlevel_status" type="radio" name="referlevel[status]" id="disable_only_for_selected_users" value="2" <?= (int)$referlevel['status'] == 2 ? 'checked' : '' ?>>
													<label class="form-check-label w-100" for="disable_only_for_selected_users">
														<div class="card border-2 h-100 position-relative overflow-hidden">
															<div class="position-absolute top-0 end-0 bg-warning opacity-10" style="width: 60px; height: 60px; border-radius: 0 0 0 100%;"></div>
															<div class="card-body text-center position-relative">
																<div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
																	<i class="fas fa-user-slash text-warning" style="font-size: 1.5rem;"></i>
																</div>
																<h6 class="card-title fw-bold text-dark"><?= __('admin.disable_only_for_selected_users') ?></h6>
																<p class="text-muted small mb-0"><?= __('admin.disable_only_for_selected_users') ?></p>
															</div>
														</div>
													</label>
												</div>
											</div>
										</div>



										<div class="notification_on_pages div-toggle status-1 mt-3">
											<div class="alert alert-info d-flex align-items-center">
												<i class="fas fa-info-circle me-2"></i>
												<span><?= __('admin.enable_for_all_users') ?></span>
											</div>
										</div>
										<div class="notification_on_pages div-toggle status-0 mt-3">
											<div class="alert alert-secondary d-flex align-items-center">
												<i class="fas fa-ban me-2"></i>
												<span><?= __('admin.disable_for_all_users') ?></span>
											</div>
										</div>
										<div class="notification_on_pages div-toggle status-2 mt-3">
											<div class="card border-warning">
												<div class="card-header bg-warning text-dark">
													<h6 class="mb-0">
														<i class="fas fa-user-slash me-2"></i><?= __('admin.disable_only_for_selected_users') ?>
													</h6>
												</div>
												<div class="card-body">
													<?php
														$_selected = json_decode( (isset($referlevel['disabled_for']) ? $referlevel['disabled_for'] : '[]') , 1);
													?>
													<div class="overflow-auto" style="max-height: 200px;">
														<div class="row g-2">
															<?php foreach ($users_list as $key => $value) { ?>
																<div class="col-md-6">
																	<div class="form-check">
																		<input class="form-check-input" <?= in_array($value['id'], $_selected) ? 'checked' : '' ?> type="checkbox" name="referlevel[disabled_for][]" value="<?= $value['id'] ?>" id="disabledFor<?= $value['id'] ?>">
																		<label class="form-check-label" for="disabledFor<?= $value['id'] ?>">
																			<?= $value['name'] ?>
																		</label>
																	</div>
																</div>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

								<script type="text/javascript">
									$('.referlevel_status').on('change',function(){
										$(".div-toggle").hide();
										$(".div-toggle.status-"+ $('.referlevel_status:checked').val()).show();
									})

									$('.referlevel_status:checked').trigger('change')
								</script>
							</div>

							<div class="col-12">
								<div class="card border-0 bg-light">
									<div class="card-header bg-transparent border-0 pb-0">
										<h6 class="mb-0 text-primary">
											<i class="fas fa-cogs me-2"></i><?= __('admin.commission_settings') ?>
										</h6>
									</div>
									<div class="card-body">
										<div class="row g-3">
											<div class="col-md-6">
												<label class="form-label fw-semibold">
													<i class="fas fa-store me-1 text-primary"></i><?= __('admin.local_store_refer_sale_commission') ?>
												</label>
												<select class="form-select" name="referlevel[autoacceptlocalstore]">
													<option value="0"><?= __('admin.on_hold') ?></option>
													<option value="1" <?= $referlevel['autoacceptlocalstore'] ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
												</select>
											</div>
											<div class="col-md-6">
												<label class="form-label fw-semibold">
													<i class="fas fa-external-link-alt me-1 text-primary"></i><?= __('admin.external_store_refer_sale_commission') ?>
												</label>
												<select class="form-select" name="referlevel[autoacceptexternalstore]">
													<option value="0"><?= __('admin.on_hold') ?></option>
													<option value="1" <?= $referlevel['autoacceptexternalstore'] ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
												</select>
											</div>
											<div class="col-md-6">
												<label class="form-label fw-semibold">
													<i class="fas fa-hand-holding-usd me-1 text-primary"></i><?= __('admin.action_refer_commission') ?>
												</label>
												<select class="form-select" name="referlevel[autoacceptaction]">
													<option value="0"><?= __('admin.on_hold') ?></option>
													<option value="1" <?= $referlevel['autoacceptaction'] ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
												</select>
											</div>
											<div class="col-md-6">
												<label class="form-label fw-semibold">
													<i class="fas fa-eye me-1 text-primary"></i><?= __('admin.show_sponser') ?>
												</label>
												<select class="form-select" name="referlevel[show_sponser]">
													<option value=""><?= __('admin.show_admin_as_sponser') ?></option>
													<option <?= $referlevel['show_sponser'] == 'none' ? 'selected' : '' ?> value="none"><?= __('admin.not_show') ?></option>
													<option <?= $referlevel['show_sponser'] == 'real_sponser' ? 'selected' : '' ?> value="real_sponser"><?= __('admin.real_sponser') ?></option>
												</select>
											</div>
											<div class="col-12">
												<label class="form-label fw-semibold">
													<i class="fas fa-user-tag me-1 text-primary"></i><?= __('admin.sponser_name') ?>
												</label>
												<input name="referlevel[sponser_name]" value="<?= $referlevel['sponser_name']; ?>" class="form-control" type="text" placeholder="<?= __('admin.sponser_name') ?>">
											</div>
										</div>
									</div>
								</div>
							</div>


<?php if(false){ ?>
	<div class="row g-3">
		<div class="col-lg-3">
			<div class="card p-3">
				<div class="mb-3">
					<label class="form-label"><?= __('admin.no_of_click_per_commission') ?></label>
					<input name="referlevel[click]" value="<?= $referlevel['click']; ?>" class="form-control" step="any" type="number" placeholder='<?= __('admin.no_of_click_per_commission') ?>'>
				</div>
				<?php foreach (array('1','2','3') as $key => $v) { ?>
					<fieldset>
						<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
						<div class="mb-3">
							<label class="form-label"><?= __('admin.refer_setting_click_commission') ?> (<?= $CurrencySymbol ?>)</label>
							<input name="referlevel_<?= $v ?>[commition]" value="<?= ${"referlevel_$v"}['commition']; ?>" class="form-control" step="any" type="number">
						</div>
					</fieldset>
				<?php } ?>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card p-3">
				<div class="mb-3">
					<label class="form-label"><?= __('admin.fix_amount_or_per') ?></label>
					<select class="form-select refer-symball-select" name="referlevel[sale_type]">
						<option symbal='%' <?= $referlevel['sale_type'] == 'percentage' ? 'selected' : '' ?> value="percentage"><?= __('admin.percentage') ?></option>
						<option symbal='<?= $CurrencySymbol ?>' <?= $referlevel['sale_type'] == 'fixed' ? 'selected' : '' ?>  value="fixed"><?= __('admin.fixed') ?></option>
					</select>
				</div>
				<?php foreach (array('1','2','3') as $key => $v) { ?>
					<fieldset>
						<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
						<div class="mb-3">
							<label class="form-label"><?= __('admin.refer_setting_sale_commission') ?> (<span class="refer-symball"></span>)</label>
							<input name="referlevel_<?= $v ?>[sale_commition]" value="<?= ${"referlevel_$v"}['sale_commition']; ?>" class="form-control" step="any" type="number">
						</div>
					</fieldset>
				<?php } ?>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card p-3">
				<div class="mb-3">
					<label class="form-label"><?= __('admin.external_click') ?></label>
					<input name="referlevel[ex_click]" value="<?= $referlevel['ex_click']; ?>" class="form-control" step="any" type="number" placeholder='<?= __('admin.external_click') ?>'>
				</div>
				<?php foreach (array('1','2','3') as $key => $v) { ?>
					<fieldset>
						<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
						<div class="mb-3">
							<label  class="form-label"><?= __('admin.external_click_commission') ?>  (<?= $CurrencySymbol ?>)</label>
							<input name="referlevel_<?= $v ?>[ex_commition]" value="<?= ${"referlevel_$v"}['ex_commition']; ?>" class="form-control" step="any" type="number">
						</div>
					</fieldset>
				<?php } ?>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card p-3">
				<div class="mb-3">
					<label class="form-label"><?= __('admin.external_action_click') ?></label>
					<input name="referlevel[ex_action_click]" value="<?= $referlevel['ex_action_click']; ?>" class="form-control" step="any" type="number" placeholder='<?= __('admin.external_action_click') ?>'>
				</div>
				<?php foreach (array('1','2','3') as $key => $v) { ?>
					<fieldset>
						<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
						<div class="mb-3">
							<label  class="form-label"><?= __('admin.external_action_click_Commission') ?>  (<?= $CurrencySymbol ?>)</label>
							<input name="referlevel_<?= $v ?>[ex_action_commition]" value="<?= ${"referlevel_$v"}['ex_action_commition']; ?>" class="form-control" step="any" type="number">
						</div>
					</fieldset>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>


						</div>
						<div class="row mt-4">
							<div class="col-12">
								<div class="d-flex justify-content-end">
									<button type="submit" class="btn btn-primary btn-lg px-4 btn-submit">
										<i class="fas fa-save me-2"></i><?= __('admin.save_settings') ?>
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.referlevel_status').on('change', function() {
			$(".div-toggle").hide();
			$(".div-toggle.status-" + $('.referlevel_status:checked').val()).show();
		});
		$('.referlevel_status:checked').trigger('change');

		$("#setting-form").on('submit', function() {
			$("#setting-form .alert-error").remove();
			var affiliate_cookie = parseInt($(".input-affiliate_cookie").val());
			if (affiliate_cookie <= 0 || affiliate_cookie > 365) {
				$(".input-affiliate_cookie").after("<div class='alert alert-danger alert-error'><?= __('admin.days_between_1_to_365') ?></div>");
			}
			if ($("#setting-form .alert-error").length == 0) return true;
			return false;
		});

		$(".btn-submit").on('click', function(evt) {
			evt.preventDefault();
			var formData = new FormData($("#setting-form")[0]);
			$(".btn-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.saving') ?>...');
			formData = formDataFilter(formData);
			$this = $("#setting-form");

			$.ajax({
				type: 'POST',
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				success: function(result) {
					$(".btn-submit").prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __('admin.save_settings') ?>');
					$(".alert-dismissable").remove();

					$this.find(".has-error").removeClass("has-error");
					$this.find("span.text-danger").remove();

					if (result['location']) {
						window.location = result['location'];
					}

					if (result['success']) {
						showToast('<?= __('admin.success') ?>', result['success'], 'success');
						var body = $("html, body");
						body.stop().animate({ scrollTop: 0 }, 500, 'swing', function() {});
					}
					if (result['errors']) {
						$.each(result['errors'], function(i, j) {
							$ele = $this.find('[name="' + i + '"]');
							if ($ele) {
								$ele.parents(".form-group").addClass("has-error");
								$ele.after("<span class='d-block text-danger'>" + j + "</span>");
							}
						});
					}
				},
			});
			return false;
		});
	});
</script>

<?php } else { ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="alert alert-info d-flex align-items-center">
				<i class="fas fa-info-circle me-3" style="font-size: 2rem;"></i>
				<div>
					<h5 class="alert-heading mb-1"><?= __('admin.mlm_module_is_off') ?></h5>
					<p class="mb-0"><?= __('admin.mlm_module_is_off') ?></p>
					<a href="<?= base_url('admincontrol/addons') ?>" class="btn btn-info mt-2">
						<i class="fas fa-cog me-2"></i><?= __('admin.admin_click_here_to_activate') ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>

