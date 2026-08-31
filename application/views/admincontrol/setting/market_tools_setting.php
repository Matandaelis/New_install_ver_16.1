<div class="container-fluid market-tools-setting-page">
<div class="row">
<div class="col-12">

<div class="card shadow-sm border-0 mb-4">
	<div class="card-header bg-primary text-white py-3">
		<div class="d-flex align-items-center">
			<i class="bi bi-gear-fill me-2 fs-4"></i>
			<h4 class="mb-0 fw-bold"><?= __('admin.marketing_tools_settings') ?></h4>
		</div>
	</div>
	<div class="card-body p-4">
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="setting-form">
			<div class="row">
				<div class="col-12">
					<ul class="nav nav-pills mb-4" role="tablist" id="TabsNav">
						<li class="nav-item">
							<a class="nav-link active" data-bs-toggle="tab" href="#marketpostback-setting" role="tab"><?= __('admin.marketpostback') ?></a>
						</li>
					</ul>
				</div>
				<div class="col-12">
					<div class="tab-content">
						<div class="tab-pane active" id="marketpostback-setting" role="tabpanel">
							<div class="row">
								<div class="col-12">
									<div class="mb-3">
										<label class="form-label fw-bold"><?= __('admin.postback_status') ?></label>
										<select class="form-select" name="marketpostback[status]">
											<option value="0"><?= __('admin.disable') ?></option>
											<option value="1" <?= $marketpostback['status'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
										</select>
									</div>
									
									<div class="mb-3">
										<label class="form-label fw-bold"><?= __('admin.postback_url') ?></label>
										<input type="text" name="marketpostback[url]" value="<?= $marketpostback['url'] ?>" class="form-control" placeholder="https://example.com/postback">
									</div>
									<div class="mb-4">
										<label class="form-label fw-bold"><?= __('admin.dynemic_params') ?></label>
										<div class="border rounded p-3 bg-light">
											<?php
												$dynamicparam = [
													'city' => __('admin.city'),
													'regionCode' => __('admin.region_code'),
													'regionName' => __('admin.region_name'),
													'countryCode' => __('admin.country_code'),
													'countryName' => __('admin.country_name'),
													'continentName' => __('admin.continent_name'),
													'timezone' => __('admin.time_zone'),
													'currencyCode' => __('admin.currency_code'),
													'currencySymbol' => __('admin.currency_symbol'),
													'ip' => __('admin.ip'),
													'type' => __('admin.type').' action,general_click,product_click,sale ',
													'id' => __('admin.id_sale_id_or_click_id'),
												];
												$marketpostback_dynamicparam = json_decode($marketpostback['dynamicparam'],1);
												$marketpostback_static = json_decode($marketpostback['static'],1);
											?>
											<div class="row g-3">
												<?php foreach ($dynamicparam as $key => $value) { ?>
													<div class="col-md-6 col-lg-4">
														<div class="form-check">
															<input class="form-check-input" type="checkbox" <?= isset($marketpostback_dynamicparam[$key]) ? 'checked' : '' ?> name="marketpostback[dynamicparam][<?= $key ?>]" value="<?= $key ?>" id="param_<?= $key ?>">
															<label class="form-check-label" for="param_<?= $key ?>">
																<span class="fw-bold text-primary"><?= $key ?></span> - <span class="text-muted small"><?= $value ?></span>
															</label>
														</div>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>

									<div class="card border mb-3">
										<div class="card-header bg-light">
											<h6 class="mb-0 fw-bold"><?= __('admin.static_params') ?></h6>
										</div>
										<div class="card-body p-0">
											<div class="static-params table-responsive">
												<table class="table table-hover align-middle mb-0">
													<thead class="table-light">
														<tr>
															<th><?= __('admin.param_key') ?></th>
															<th><?= __('admin.param_value') ?></th>
															<th width="80px" class="text-center"><?= __('admin.actions') ?></th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
										</div>
										<div class="card-footer bg-light">
											<button class="btn btn-sm btn-primary add-static-params" type="button"><i class="bi bi-plus-circle me-1"></i><?= __('admin.add') ?></button>
										</div>
									</div>

									<script type="text/javascript">
										$(".add-static-params").click(function(){
											addStaticParam('','');
										})

										<?php foreach ($marketpostback_static as $key => $value) {
											echo "addStaticParam('". $value['key'] ."','". $value['value'] ."');";
										} ?>

										var addStaticParamIndex = 0;
										function addStaticParam(key,val) {
							var html = `<tr>
									<td>
										<input type="text" value='${key}' name="marketpostback[static][${addStaticParamIndex}][key]" placeholder="<?= __('admin.param_key') ?>" class="form-control form-control-sm">
									</td>
									<td>
										<input type="text" name="marketpostback[static][${addStaticParamIndex}][value]" value='${val}' placeholder="<?= __('admin.param_value') ?>" class="form-control form-control-sm">
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-danger remove-static-params" type="button"><i class="bi bi-trash"></i></button>
									</td>
								</tr>`;

											addStaticParamIndex++;
											$(".static-params tbody").append(html);
										}

										$(".static-params").delegate(".remove-static-params","click",function(){
											$(this).parents("tr").remove();
										})
									</script>
								

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="text-end mt-3">
						<button type="submit" class="btn btn-primary btn-submit">
							<span class="spinner-border spinner-border-sm me-2 d-none"></span>
							<i class="bi bi-save me-1"></i><?= __('admin.save_settings') ?>
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
$("#setting-form").on('submit',function(){
	$("#setting-form .alert-error").remove();
	var affiliate_cookie = parseInt($(".input-affiliate_cookie").val());
	if(affiliate_cookie <= 0 || affiliate_cookie > 365){
		$(".input-affiliate_cookie").after("<div class='alert alert-danger alert-error'>"+'<?= __('admin.days_between_1_to_365') ?>'+"</div>");
	}
	if($("#setting-form .alert-error").length == 0) return true;
	return false;
})

$(".btn-submit").on('click',function(evt){
    evt.preventDefault();
    var formData = new FormData($("#setting-form")[0]);
    var $btn = $(this);
    var $spinner = $btn.find('.spinner-border');
    var $icon = $btn.find('.bi-save');

    $btn.prop('disabled', true);
    $spinner.removeClass('d-none');
    $icon.addClass('d-none');
    
    formData = formDataFilter(formData);
    $this = $("#setting-form");
    
    $.ajax({
        type:'POST',
        dataType:'json',
        cache:false,
        contentType: false,
        processData: false,
        data:formData,
        complete:function(){
            $btn.prop('disabled', false);
            $spinner.addClass('d-none');
            $icon.removeClass('d-none');
        },
        success:function(result){
            $this.find(".is-invalid").removeClass("is-invalid");
            $this.find(".invalid-feedback").remove();
            
            if(result['location']){
                window.location = result['location'];
            }

            if(result['success']){
                if(typeof showToast === 'function') {
                    showToast('<?= __("admin.success") ?>', result['success'], 'success', 3000);
                } else {
                    showPrintMessage(result['success'],'success');
                }
                var body = $("html, body");
				body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
            }

            if(result['errors']){
                $.each(result['errors'], function(i,j){
                    $ele = $this.find('[name="'+ i +'"]');
                    if($ele.length){
                        $ele.addClass("is-invalid");
                        $ele.after("<div class='invalid-feedback d-block'>"+ j +"</div>");
                    }
                });
                if(typeof showToast === 'function') {
                    showToast('<?= __("admin.error") ?>', '<?= __("admin.please_check_form_errors") ?>', 'error', 3000);
                }
            }
        },
        error:function(){
            if(typeof showToast === 'function') {
                showToast('<?= __("admin.error") ?>', '<?= __("admin.something_went_wrong") ?>', 'error', 3000);
            }
        }
    })
    return false;
});

</script>

