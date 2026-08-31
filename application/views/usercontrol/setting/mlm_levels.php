<div class="container-fluid">
	<div class="card shadow-sm">
		<div class="card-header bg-primary text-white">
			<h5 class="mb-0">
				<i class="fas fa-sitemap me-2"></i><?= __('user.page_title_vendor_mlm_levels') ?>
			</h5>
		</div>
		<div class="card-body">
			<?php $vendorSettingTab = 'mlm_levels'; ?>
			<?php include 'mlm_menu.php'; ?>

			<form class="form-horizontal mt-4" autocomplete="off" method="post" action="" enctype="multipart/form-data" id="setting-form">
				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-layer-group me-1 text-primary"></i><?= __('admin.refer_level') ?>
					</label>
					<?php $levels = isset($referlevel['levels']) ? (int)$referlevel['levels'] : 3;  ?>
					<select class="form-select form-select-lg" id="referlevel_select" name="referlevel[levels]">
						<?php for ($i = 1; $i <= 20; $i++) { ?>
							<option <?= $levels == $i ? 'selected': '' ?> value="<?= $i ?>"><?= $i ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover align-middle" id="tbl_refer_level">
						<thead class="table-light">
							<tr>
								<th class="border-end"><?= __('admin.level_mlm') ?></th>
								<th class="border-end text-center">
									<?= __('admin.cps_cost') ?><br>
									<select class="form-select mt-2 refer-symball-select" name="referlevel[sale_type]">
										<option symbal='%' <?php if($referlevel['sale_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.percentage') ?></option>
										<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['sale_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed"><?= __('admin.fixed') ?></option>
									</select>
								</th>
								<th class="border-end text-center" colspan="2"><?= __('admin.clicks_count') ?> &amp; <?= __('admin.cpc_cost') ?></th>
								<th class="text-center"><?= __('admin.cpa_cost') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php for ($level =1; $level <= $levels; $level++) { ?>
								<tr>
									<td class="border-end fw-bold"><?= $level ?></td>
									<td class="border-end">
										<div class="input-group">
											<input type="number" step="any" name="referlevel_<?= $level ?>[sale_commition]" value="<?php echo ${"referlevel_". $level}['sale_commition'] ?>" class="form-control" />
											<span class="input-group-text refer-symball"></span>
										</div>
									</td>
									<td>
										<input type="number" step="any" name="referlevel_<?= $level ?>[commition]" value="<?php echo ${"referlevel_". $level}['commition'] ?>" class="form-control" />
									</td>
									<td class="border-end">
										<div class="input-group">
											<input type="number" step="any" name="referlevel_<?= $level ?>[ex_commition]" value="<?php echo ${"referlevel_". $level}['ex_commition'] ?>" class="form-control" />
											<span class="input-group-text"><?= $CurrencySymbol ?></span>
										</div>
									</td>
									<td>
										<div class="input-group">
											<input type="number" step="any" name="referlevel_<?= $level ?>[ex_action_commition]" value="<?php echo ${"referlevel_". $level}['ex_action_commition'] ?>" class="form-control" />
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
					<div class="alert alert-info d-flex align-items-start">
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

				<div class="text-end mt-4">
					<button type="submit" class="btn btn-success btn-lg px-5 btn-submit">
						<i class="fas fa-save me-2"></i><?= __('admin.save_settings') ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	function chnage_teigger() {
		var symbal = $(".refer-symball-select").find("option:selected").attr("symbal");
		$(".refer-symball").html(symbal);
	}
	$(".refer-symball-select").change(chnage_teigger)
	chnage_teigger();

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

	var levels = {};
	<?php 
	for ($i=1; $i <= 20; $i++) { 
		$v = 'referlevel_'.$i;
		if (isset(${$v})) { ?>
				levels['<?= $i ?>'] = <?= json_encode(${$v}) ?>;
		<?php }
	}
	?>

	$('#referlevel_select').on('change',function(){
		var level =  $(this).val();
		var html = '';
		for(var i = 1; i <= level; i++){
			html += '<tr>';
				html += '<td class="border-end fw-bold">'+i+'</td>';
				html += '<td class="border-end"><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[sale_commition]" value="'+(levels[i] ? levels[i]['sale_commition'] : '' )+'" class="form-control" /><span class="input-group-text refer-symball"></span></div></td>';
				html += '<td><input type="number" step="any" name="referlevel_'+i+'[commition]" value="'+(levels[i] ? levels[i]['commition'] : '' )+'" class="form-control" /></td>';
				html += '<td class="border-end"><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_commition]" value="'+(levels[i] ? levels[i]['ex_commition'] : '' )+'" class="form-control" /><span class="input-group-text"><?= $CurrencySymbol ?></span></div></td>';
				html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_action_commition]" value="'+(levels[i] ? levels[i]['ex_action_commition'] : '' )+'" class="form-control" /><span class="input-group-text"><?= $CurrencySymbol ?></span></div></td>';
			html += '</tr>';
		}
		$('#tbl_refer_level tbody').html(html);
		chnage_teigger();
	});
</script>
