<?php if($mlm_status){ ?>
<div class="container-fluid px-4 pb-4">
	<div class="row">
		<div class="col-12">
			<?php get_instance()->load->view('admincontrol/setting/_mlm_nav'); ?>
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h4 class="fw-bold mb-0"><?= __('admin.mlm_levels') ?></h4>
			</div>

			<div class="card shadow-sm border-0">
				<div class="card-body">
					<form method="post" action="" enctype="multipart/form-data" id="setting-form">
						<div class="row g-4">
							<div class="col-12">
								<div class="card border-0 bg-light">
									<div class="card-body">
										<?php $levels = isset($referlevel['levels']) ? (int)$referlevel['levels'] : 3;  ?>
										<div class="row g-3">
											<div class="col-12 col-sm-8 col-md-6 col-lg-4">
												<label class="form-label fw-semibold mb-2">
													<i class="fas fa-layer-group me-1 text-primary"></i><?= __('admin.refer_level') ?>
												</label>
												<div class="mlm_css-compact-level-selector">
													<div class="d-flex flex-column flex-sm-row align-items-center">
														<div class="mlm_css-level-preview mb-2 mb-sm-0">
															<div class="mlm_css-level-number"><?= $levels ?></div>
															<div class="mlm_css-level-text"><?= $levels == 1 ? 'Level' : 'Levels' ?></div>
														</div>
														<div class="mlm_css-level-slider-container">
															<input type="range" class="form-range mlm_css-level-slider" id="referlevel_select" name="referlevel[levels]" min="1" max="20" value="<?= $levels ?>" step="1">
															<div class="mlm_css-level-labels">
																<span>1</span>
																<span>20</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12">
								<div class="card border-info mlm_css-notification-card notification-card" style="display: none;">
									<div class="card-header bg-info text-white">
										<h6 class="mb-0">
											<i class="fas fa-info-circle me-2"></i><?= __('admin.notification') ?>
										</h6>
									</div>
									<div class="card-body">
										<div class="notification_on_pages reg_notis reg_percentage_notis">
											<div class="alert alert-info d-flex align-items-center">
												<i class="fas fa-percentage me-2"></i>
												<span><?= __('admin.registration_comission_type_1_notif') ?></span>
											</div>
										</div>
										<div class="notification_on_pages reg_notis reg_custom_percentage_notis">
											<div class="alert alert-warning d-flex align-items-center">
												<i class="fas fa-cog me-2"></i>
												<span><?= __('admin.registration_comission_type_2_notif') ?></span>
											</div>
										</div>
										<div class="notification_on_pages reg_notis reg_fixed_notis">
											<div class="alert alert-success d-flex align-items-center">
												<i class="fas fa-dollar-sign me-2"></i>
												<span><?= __('admin.registration_comission_type_3_notif') ?></span>
											</div>
										</div>
									</div>
								</div>
							</div>


							<div class="col-12">
								<div class="card border-0 bg-light">
									<div class="card-header bg-transparent border-0 pb-0">
										<div class="d-flex justify-content-between align-items-center">
											<h6 class="mb-0 text-primary">
												<i class="fas fa-table me-2"></i><?= __('admin.commission_settings') ?>
											</h6>
											<button type="button" class="btn btn-outline-primary btn-sm" id="setSmartDefaults">
												<i class="fas fa-magic me-1"></i>Set Smart Defaults
											</button>
										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive mlm_css-table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
											<table class="table table-striped table-hover" id="tbl_refer_level" style="min-width: 800px;">
												<thead class="table-dark">
													<tr>
														<th class="text-center" style="min-width: 60px;"><?= __('admin.level_mlm') ?></th>
														<th class="text-center" style="min-width: 150px;">
															<div class="d-flex flex-column align-items-center">
																<span class="fw-bold small"><?= __('admin.cpr_cost') ?></span>
																<select class="form-select form-select-sm refer-reg-symball-select mt-1" name="referlevel[reg_comission_type]">
																	<option value="disabled" <?php if($referlevel['reg_comission_type'] == 'disabled') { ?> selected <?php } ?>><?= __('admin.select_registration_commission_plan') ?></option>
																	<option symbal='%' <?php if($referlevel['reg_comission_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.membership_registration_commission_perce') ?></option>
																	<option symbal='%' <?php if($referlevel['reg_comission_type'] == 'custom_percentage') { ?> selected <?php } ?> value="custom_percentage"><?= __('admin.registration_custom_commission_amount_perce') ?></option>
																	<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['reg_comission_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed"><?= __('admin.registration_fixed_amount') ?></option>
																</select>
																<input class="form-control form-control-sm mt-1" type="number" name="referlevel[reg_comission_custom_amt]" value="<?php echo isset($referlevel['reg_comission_custom_amt']) ? $referlevel['reg_comission_custom_amt'] : 0;?>" placeholder="<?= __('admin.registration_custom_commission_amount_perce') ?>" />
															</div>
														</th>
														<th class="text-center" style="min-width: 120px;">
															<div class="d-flex flex-column align-items-center">
																<span class="fw-bold small"><?= __('admin.cps_cost') ?></span>
																<select class="form-select form-select-sm refer-symball-select mt-1" name="referlevel[sale_type]">
																	<option symbal='%' <?php if($referlevel['sale_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.percentage') ?></option>
																	<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['sale_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed"><?= __('admin.fixed') ?></option>
																</select>
															</div>
														</th>
														<th class="text-center" style="min-width: 100px;">
															<div class="d-flex flex-column align-items-center">
																<span class="fw-bold small"><?= __('admin.clicks_count') ?></span>
															</div>
														</th>
														<th class="text-center" style="min-width: 120px;">
															<div class="d-flex flex-column align-items-center">
																<span class="fw-bold small"><?= __('admin.cpc_cost') ?></span>
															</div>
														</th>
														<th class="text-center" style="min-width: 120px;">
															<div class="d-flex flex-column align-items-center">
																<span class="fw-bold small"><?= __('admin.cpa_cost') ?></span>
															</div>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php for ($level =1; $level <= $levels; $level++) { ?>
														<tr>
															<td class="text-center fw-bold"><?= $level ?></td>
															<td>
																<div class="input-group input-group-sm mlm_css-input-group-sm">
																	<input type="number" step="any" name="referlevel_<?= $level ?>[reg_commission]" value="<?php echo ${"referlevel_". $level}['reg_commission'] ?>" class="form-control" placeholder="0.00" />
																	<span class="input-group-text refer-reg-symball">%</span>
																</div>
															</td>
															<td>
																<div class="input-group input-group-sm mlm_css-input-group-sm">
																	<input type="number" step="any" name="referlevel_<?= $level ?>[sale_commition]" value="<?php echo ${"referlevel_". $level}['sale_commition'] ?>" class="form-control" placeholder="0.00" />
																	<span class="input-group-text refer-symball">%</span>
																</div>
															</td>
															<td>
																<input type="number" step="any" name="referlevel_<?= $level ?>[commition]" value="<?php echo ${"referlevel_". $level}['commition'] ?>" class="form-control form-control-sm" placeholder="0" />
															</td>
															<td>
																<div class="input-group input-group-sm mlm_css-input-group-sm">
																	<input type="number" step="any" name="referlevel_<?= $level ?>[ex_commition]" value="<?php echo ${"referlevel_". $level}['ex_commition'] ?>" class="form-control" placeholder="0.00" />
																	<span class="input-group-text"><?= $CurrencySymbol ?></span>
																</div>
															</td>
															<td>
																<div class="input-group input-group-sm mlm_css-input-group-sm">
																	<input type="number" step="any" name="referlevel_<?= $level ?>[ex_action_commition]" value="<?php echo ${"referlevel_". $level}['ex_action_commition'] ?>" class="form-control" placeholder="0.00" />
																	<span class="input-group-text"><?= $CurrencySymbol ?></span>
																</div>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										
										<!-- Store Module Fixed Commission Setting -->
										<div class="mt-4 pt-3 border-top">
											<div class="alert alert-info mb-3 d-flex align-items-start">
												<i class="fas fa-store me-2 mt-1"></i>
												<div>
													<strong><?= __('admin.store_module_settings') ?></strong>
													<p class="mb-0 small"><?= __('admin.store_module_settings_desc') ?></p>
												</div>
											</div>
											<div class="row g-3">
												<div class="col-12 col-md-6">
													<label class="form-label fw-semibold mb-2">
														<i class="fas fa-shopping-cart me-1 text-success"></i><?= __('admin.fixed_commission_calculation_mode_cps') ?>
													</label>
													<select name="referlevel[fixed_commission_mode]" class="form-select">
														<option value="per_order" <?= (!isset($referlevel['fixed_commission_mode']) || $referlevel['fixed_commission_mode'] == 'per_order') ? 'selected' : '' ?>>
															<?= __('admin.per_order_once') ?>
														</option>
														<option value="per_product" <?= (isset($referlevel['fixed_commission_mode']) && $referlevel['fixed_commission_mode'] == 'per_product') ? 'selected' : '' ?>>
															<?= __('admin.per_product_multiply_by_quantity') ?>
														</option>
													</select>
													<small class="form-text text-muted d-block mt-2">
														<i class="fas fa-info-circle me-1"></i><?= __('admin.fixed_commission_calculation_mode_help_short') ?>
													</small>
												</div>
											</div>
										</div>
									</div>
								</div>
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
									<label  class="control-label"><?= __('admin.external_click_commission') ?> (<?= $CurrencySymbol ?></span>)</label>
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
						<input name="referlevel[ex_action_click]" value="<?php echo $referlevel['ex_action_click']; ?>" class="form-control" step="any" type="number" placeholder='External Action Click'>
					</div>
					<?php foreach (array('1','2','3') as $key => $v) { ?>
						<fieldset>
							<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.external_action_click_Commission') ?> (<?= $CurrencySymbol ?></span>)</label>
									<input name="referlevel_<?php echo $v ?>[ex_action_commition]" value="<?php echo ${"referlevel_$v"}['ex_action_commition']; ?>" class="form-control" step="any" type="number">
								</div>
						</fieldset>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

<script type="text/javascript">
    function chnage_teigger() {
        var symbal = $(".refer-symball-select").find("option:selected").attr("symbal");
        $(".refer-symball").html(symbal);
    }
    $(".refer-symball-select").change(chnage_teigger)
    chnage_teigger();

    function chnage_teigger2() {
        var symbal = $(".refer-reg-symball-select").find("option:selected").attr("symbal");

        if($(".refer-reg-symball-select").val() == "disabled") {
            $(".refer-reg-symball").html('#');
        } else {
            $(".refer-reg-symball").html(symbal);
        }

        if($(".refer-reg-symball-select").val() != "custom_percentage") {
            $('input[name="referlevel[reg_comission_custom_amt]"]').hide();
        } else {
            $('input[name="referlevel[reg_comission_custom_amt]"]').show();
        }

        var selectedValue = $(".refer-reg-symball-select").val();
        if (selectedValue === 'percentage' || selectedValue === 'custom_percentage' || selectedValue === 'fixed') {
            $('.reg_notis').hide();
            $('.reg_'+ selectedValue +'_notis').show();
            // Show the notification card when a valid option is selected
            $('.notification-card').show();
        } else {
            // Hide the notification card when no or invalid option is selected
            $('.notification-card').hide();
        }
    }
    $(".refer-reg-symball-select").change(chnage_teigger2)
    chnage_teigger2();
</script>


						</div>
						<div class="row mt-4">
							<div class="col-12">
								<div class="d-flex flex-column flex-sm-row justify-content-center justify-content-sm-end gap-2">
									<button type="submit" class="btn btn-primary btn-lg px-4 btn-submit w-100 w-sm-auto">
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
		function chnage_teigger() {
			var symbal = $(".refer-symball-select").find("option:selected").attr("symbal");
			$(".refer-symball").html(symbal);
		}
		$(".refer-symball-select").change(chnage_teigger);
		chnage_teigger();

		function chnage_teigger2() {
			var symbal = $(".refer-reg-symball-select").find("option:selected").attr("symbal");

			if ($(".refer-reg-symball-select").val() == "disabled") {
				$(".refer-reg-symball").html('#');
			} else {
				$(".refer-reg-symball").html(symbal);
			}

			if ($(".refer-reg-symball-select").val() != "custom_percentage") {
				$('input[name="referlevel[reg_comission_custom_amt]"]').hide();
			} else {
				$('input[name="referlevel[reg_comission_custom_amt]"]').show();
			}

			var selectedValue = $(".refer-reg-symball-select").val();
			if (selectedValue === 'percentage' || selectedValue === 'custom_percentage' || selectedValue === 'fixed') {
				$('.reg_notis').hide();
				$('.reg_' + selectedValue + '_notis').show();
				$('.notification-card').show();
			} else {
				$('.notification-card').hide();
			}
		}
		$(".refer-reg-symball-select").change(chnage_teigger2);
		chnage_teigger2();

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
			if ($(".refer-reg-symball-select").val() == "custom_percentage") {
				let reg_comission_custom_amt = $('input[name="referlevel[reg_comission_custom_amt]"]').val();
				if (reg_comission_custom_amt == '' || reg_comission_custom_amt == 0) {
					$(".refer-reg-symball-select").parent().append('<span class="text-danger d-block"><?= __('admin.custom_comission_amount_should_be_greater_than_0') ?></span>');
					return false;
				}
			}

			$(".refer-reg-symball-select").parent().find('.text-danger').remove();

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

		var levels = {};
		<?php 
		for ($i=1; $i <= 20; $i++) { 
			$v = 'referlevel_'.$i;
			if (isset(${$v})) { ?>
				levels['<?= $i ?>'] = <?= json_encode(${$v}) ?>;
			<?php }
		}
		?>
		
		// Compact level selector functionality
		$('#referlevel_select').on('input change', function() {
			var selectedLevel = $(this).val();
			
			// Update preview
			$('.mlm_css-level-preview .mlm_css-level-number').text(selectedLevel);
			$('.mlm_css-level-preview .mlm_css-level-text').text(selectedLevel == 1 ? 'Level' : 'Levels');
			
			// Update table
			updateLevelTable(selectedLevel);
		});

		// Smart defaults functionality
		$('#setSmartDefaults').on('click', function() {
			if(confirm('This will fill all levels with smart default values. Continue?')) {
				setSmartDefaults();
			}
		});

		function setSmartDefaults() {
			var currentLevels = $('#referlevel_select').val();
			
			// Smart default commission rates (progressive decrease)
			var smartRates = {
				1: 10,   // Level 1: 10%
				2: 8,    // Level 2: 8%
				3: 6,    // Level 3: 6%
				4: 4,    // Level 4: 4%
				5: 2,    // Level 5: 2%
				6: 1,    // Level 6: 1%
				7: 1,    // Level 7: 1%
				8: 1,    // Level 8: 1%
				9: 1,    // Level 9: 1%
				10: 1,   // Level 10: 1%
				11: 0.5, // Level 11: 0.5%
				12: 0.5, // Level 12: 0.5%
				13: 0.5, // Level 13: 0.5%
				14: 0.5, // Level 14: 0.5%
				15: 0.5, // Level 15: 0.5%
				16: 0.25,// Level 16: 0.25%
				17: 0.25,// Level 17: 0.25%
				18: 0.25,// Level 18: 0.25%
				19: 0.25,// Level 19: 0.25%
				20: 0.25 // Level 20: 0.25%
			};

			// Fill all visible levels with smart defaults
			for(var i = 1; i <= currentLevels; i++) {
				var rate = smartRates[i] || 0.25; // Default to 0.25% for levels beyond 20
				
				// Fill registration commission
				$('input[name="referlevel_' + i + '[reg_commission]"]').val(rate);
				
				// Fill sale commission
				$('input[name="referlevel_' + i + '[sale_commition]"]').val(rate);
				
				// Fill click count (set to 1 for all levels)
				$('input[name="referlevel_' + i + '[commition]"]').val(1);
				
				// Fill external click commission (CPC) - progressive rates for clicks
				var cpcRate = i <= 5 ? (1.0 - (i-1) * 0.15) : 0.25; // 1.0, 0.85, 0.70, 0.55, 0.40, then 0.25
				$('input[name="referlevel_' + i + '[ex_commition]"]').val(cpcRate.toFixed(2));
				
				// Fill external action commission (CPA) - lower rates for actions
				var cpaRate = i <= 3 ? (0.50 - (i-1) * 0.10) : 0.25; // 0.50, 0.40, 0.30, then 0.25
				$('input[name="referlevel_' + i + '[ex_action_commition]"]').val(cpaRate.toFixed(2));
			}
			
			// Show success message
			showToast('Smart defaults applied successfully!', 'success');
		}

		function updateLevelTable(level) {
			var html = '';
			for (var i = 1; i <= level; i++) {
				html += '<tr>';
				html += '<td class="text-center fw-bold">' + i + '</td>';
				html += '<td><div class="input-group input-group-sm mlm_css-input-group-sm"><input type="number" step="any" name="referlevel_' + i + '[reg_commission]" value="' + (levels[i] ? levels[i]['reg_commission'] : '') + '" class="form-control" placeholder="0.00" /><span class="input-group-text refer-reg-symball">%</span></div></td>';
				html += '<td><div class="input-group input-group-sm mlm_css-input-group-sm"><input type="number" step="any" name="referlevel_' + i + '[sale_commition]" value="' + (levels[i] ? levels[i]['sale_commition'] : '') + '" class="form-control" placeholder="0.00" /><span class="input-group-text refer-symball">%</span></div></td>';
				html += '<td><input type="number" step="any" name="referlevel_' + i + '[commition]" value="' + (levels[i] ? levels[i]['commition'] : '') + '" class="form-control form-control-sm" placeholder="0" /></td>';
				html += '<td><div class="input-group input-group-sm mlm_css-input-group-sm"><input type="number" step="any" name="referlevel_' + i + '[ex_commition]" value="' + (levels[i] ? levels[i]['ex_commition'] : '') + '" class="form-control" placeholder="0.00" /><span class="input-group-text"><?= $CurrencySymbol ?></span></div></td>';
				html += '<td><div class="input-group input-group-sm mlm_css-input-group-sm"><input type="number" step="any" name="referlevel_' + i + '[ex_action_commition]" value="' + (levels[i] ? levels[i]['ex_action_commition'] : '') + '" class="form-control" placeholder="0.00" /><span class="input-group-text"><?= $CurrencySymbol ?></span></div></td>';
				html += '</tr>';
			}
			$('#tbl_refer_level tbody').html(html);
			chnage_teigger();
			chnage_teigger2();
		}
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