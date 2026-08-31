<div class="container-fluid">
<div class="row">
<div class="col-12">
<div class="card shadow-sm">
	<div class="card-header bg-primary text-white">
		<div class="d-flex justify-content-between align-items-center py-2">
			<h5 class="mb-0">
				<i class="fas fa-tools me-2"></i><?= __('admin.integration_tools') ?> 
				<span class="badge bg-light text-dark ms-2"><?= ucfirst(str_replace("_", " ", $type)) ?></span>
			</h5>
			<a class="btn btn-light btn-sm" href="<?= base_url('usercontrol/integration_tools') ?>">
				<i class="fas fa-arrow-left me-1"></i><?= __('admin.back') ?>
			</a>
		</div>
	</div>

	<div class="card-body">

		<div id="warnings_notif">
		    <?php
		    if (isset($tool)) {
		        // Decode or access marketpostback as an array
		        $marketpostback = is_array($tool['marketpostback']) ? $tool['marketpostback'] : json_decode($tool['marketpostback'], true);
		        $postback_enabled = isset($marketpostback['status']) && $marketpostback['status'] === 'custom';
		        $integration_method = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel';
		        $skip_js_validation = in_array($integration_method, ['s2s', 's2s_direct', 'postback', 'conversion_api']);

		        if (!$postback_enabled && !$skip_js_validation) {
		            $security_alerts = external_integration_security_check($tool['target_link']);

		            if (!is_array($security_alerts)) { ?>
		                <div class="alert alert-danger d-flex align-items-center mb-3">
		                    <i class="fas fa-exclamation-circle me-2"></i>
		                    <div>
		                        <strong><?= __('admin.error') . " " . $security_alerts ?>:</strong>
		                        <?= __('admin.invalid_campaign_target_link') ?>
		                    </div>
		                </div>
		            <?php }

		            if (is_array($security_alerts) && isset($security_alerts['comment']) && $security_alerts['comment']) { ?>
		                <div class="alert alert-danger d-flex align-items-center mb-3">
		                    <i class="fas fa-exclamation-circle me-2"></i>
		                    <div>
		                        <strong><?= __('admin.error') . " " . $security_alerts['comment'] ?>:</strong>
		                        <?= __('admin.code_has_comment_line') ?>
		                    </div>
		                </div>
		            <?php }

		            if (is_array($security_alerts) && empty($security_alerts['common_code'])) { ?>
		                <div class="alert alert-warning d-flex align-items-center mb-3">
		                    <i class="fas fa-exclamation-triangle me-2"></i>
		                    <div>
		                        <strong><?= __('admin.warning') ?>:</strong>
		                        <?= __('admin.common_integration_code_not_available_msg') ?>
		                    </div>
		                </div>
		            <?php }

		            if (is_array($security_alerts) && isset($security_alerts['website_url']) && empty($security_alerts['website_url'])) { ?>
		                <div class="alert alert-warning d-flex align-items-center mb-3">
		                    <i class="fas fa-exclamation-triangle me-2"></i>
		                    <div>
		                        <strong><?= __('admin.warning') ?>:</strong>
		                        <?= __('admin.website_url_not_available_msg') ?>
		                    </div>
		                </div>
		            <?php }

		            // Additional program-specific checks
		            if ($tool['tool_type'] == 'program') {
		                $program = $this->IntegrationModel->getProgramByID($tool['program_id']);

		                if ($program['sale_status'] == 1 && isset($security_alerts['sale_integration']) && empty($security_alerts['sale_integration'])) { ?>
		                    <div class="alert alert-warning d-flex align-items-center mb-3">
		                        <i class="fas fa-exclamation-triangle me-2"></i>
		                        <div>
		                            <strong><?= __('admin.warning') ?>:</strong>
		                            <?= __('admin.sale_integration_code_not_available_msg') ?>
		                        </div>
		                    </div>
		                <?php }

		                if ($program['click_status'] == 1 && isset($security_alerts['product_click_integration']) && empty($security_alerts['product_click_integration'])) { ?>
		                    <div class="alert alert-warning d-flex align-items-center mb-3">
		                        <i class="fas fa-exclamation-triangle me-2"></i>
		                        <div>
		                            <strong><?= __('admin.warning') ?>:</strong>
		                            <?= __('admin.product_click_integration_code_not_available_msg') ?>
		                        </div>
		                    </div>
		                <?php }

		                if ($program['sale_status'] == 1 && $program['click_status'] == 1 && isset($security_alerts['website_url_count']) && $security_alerts['website_url_count'] != 2) { ?>
		                    <div class="alert alert-warning d-flex align-items-center mb-3">
		                        <i class="fas fa-exclamation-triangle me-2"></i>
		                        <div>
		                            <strong><?= __('admin.warning') ?>:</strong>
		                            <?= __('admin.website_url_not_available_msg') ?>
		                        </div>
		                    </div>
		                <?php }
		            }

		            // Additional tool-specific checks for single_action, action, and general_click
		            if ($tool['tool_type'] == 'single_action' || $tool['tool_type'] == 'action') {
		                if (isset($security_alerts['action_integration']) && empty($security_alerts['action_integration'])) { ?>
		                    <h4 class="notification_on_pages">
		                        <div class="bg-danger text-white p-2 rounded">
		                            <?= __('admin.warning') ?>: <?= __('admin.action_integration_code_not_available_msg') ?>
</div>
		                    </h4>
		                <?php }
		            }

		            if ($tool['tool_type'] == 'general_click') {
		                if (isset($security_alerts['general_click_integration']) && empty($security_alerts['general_click_integration'])) { ?>
		                    <h4 class="notification_on_pages">
		                        <div class="bg-danger text-white p-2 rounded">
		                            <?= __('admin.warning') ?>: <?= __('admin.click_integration_code_not_available_msg') ?>
</div>
		                    </h4>
		                <?php }
		            }
		        } // End of if not postback_enabled
		    }
		    ?>
</div>

			<form action="" method="get" id="form_tools">

			<!-- Navigation Tabs -->
				<ul class="nav nav-tabs nav-fill mb-3 shadow-sm" id="vendor-campaign-tabs" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
							<i class="fas fa-cog me-2"></i><?= __('user.general_setting') ?>
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="menu1-tab" data-bs-toggle="tab" data-bs-target="#menu1" type="button" role="tab" aria-controls="menu1" aria-selected="false">
							<i class="fas fa-layer-group me-2"></i><?= __('user.level_setting') ?>
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="vendor-integration-setup-tab" data-bs-toggle="tab" data-bs-target="#vendor-integration-setup" type="button" role="tab" aria-controls="vendor-integration-setup" aria-selected="false">
							<i class="fas fa-plug me-2"></i><?= __('user.integration_setup') ?>
						</button>
					</li>
					<li class="nav-item d-none" role="presentation">
						<button class="nav-link" id="postback-setting-tab" data-bs-toggle="tab" data-bs-target="#postback-setting" type="button" role="tab" aria-controls="postback-setting" aria-selected="false">
							<i class="fas fa-exchange-alt me-2"></i><?= __('user.postback_setting') ?>
						</button>
					</li>
					<li class="nav-item d-none" role="presentation">
						<button class="nav-link" id="conversion_api-tab" data-bs-toggle="tab" data-bs-target="#conversion_api" type="button" role="tab" aria-controls="conversion_api" aria-selected="false">
							<i class="fas fa-code me-2"></i><?= __('user.conversion_api') ?>
						</button>
					</li>
				</ul>

				<!-- S2S settings moved to Integration Setup tab -->
				<input type="hidden" id="vendor_s2s_enabled" name="s2s_enabled" value="<?= (isset($tool) && !empty($tool['s2s_enabled'])) ? '1' : '0' ?>">
				<input type="hidden" id="vendor_s2s_direct_mode" name="s2s_direct_mode" value="<?= (isset($tool) && !empty($tool['s2s_direct_mode'])) ? '1' : '0' ?>">
				<?php if(isset($tool) && !empty($tool['api_key'])): ?>
					<input type="hidden" name="api_key" value="<?= htmlspecialchars($tool['api_key']) ?>">
				<?php endif; ?>
				<div class="tab-content">
					<div class="tab-pane col-sm-12 fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
						<input type="hidden" name="type" value="<?= $type ?>">
						<input type="hidden" name="program_tool_id" id="program_tool_id"  value="<?= isset($tool) ? $tool['id'] : '0' ?>">

						<div class="row">
							<div class="col-sm-7">
								<div class="row">
									<div class="col-sm-12">

										<div class="form-group">
											<label class="control-label"><?= __('admin.name') ?></label>
											<input class="form-control" value="<?= isset($tool) ? $tool['name'] : '' ?>" name="name" type="text">
</div>

										<div class="form-group">
											<label class="control-label"><?= __('admin.tool_type') ?></label>
											<select class="form-control" name="tool_type" id="tool_type">
												<option value=""><?= __('admin.select_tool_type') ?></option>
												<option <?= (isset($tool) && $tool['tool_type'] == 'program') ? 'selected' : '' ?> value="program"><?= __('admin.sale_integration') ?></option>
												<option <?= (isset($tool) && $tool['tool_type'] == 'single_action') ? 'selected' : '' ?> value="single_action"><?= __('admin.single_action_integration') ?></option>
												<option <?= (isset($tool) && $tool['tool_type'] == 'action') ? 'selected' : '' ?> value="action"><?= __('admin.multi_action_integration') ?></option>
												<option <?= (isset($tool) && $tool['tool_type'] == 'general_click') ? 'selected' : '' ?> value="general_click"><?= __('admin.click_integration') ?></option>
											</select>
</div>
</div>

									<div class="col-sm-12">
										<div class="form-group for-program-tool" style="display:none;">
											<label class="control-label"><?= __('admin.tool_integration_plugin') ?></label>
											<select class="form-control" name="tool_integration_plugin">
												<option value=""><?= __('admin.select_tool_integration_plugin') ?></option>
												<?php 
												$pluginForSkipp = ['wp_user_register', 'wp_forms', 'postback', 'show_affiliate_id', 'wp_show_affiliate_id', 'affiliate_register_api', 'php_api_library'];

												foreach ($integration_plugins as $key => $module) {
													if(!in_array($key, $pluginForSkipp)) {
														?>

														<option <?= (isset($tool) && $tool['tool_integration_plugin'] == $key) ? 'selected' : '' ?> value="<?= $key; ?>"> <?= $module['name']; ?> </option>

													<?php }
												} ?>
											</select>
</div>
</div>

									<?php 
									$is_start_date = (isset($tool) && !empty($tool['start_date']) && $tool['start_date'] != null) ? true : false;
									$is_end_date = (isset($tool) && !empty($tool['end_date']) && $tool['end_date'] != null) ? true : false;

									$tool_period_val = 1;

									if($is_start_date && $is_end_date) {
										$tool_period_val = 4;
									}

									if($is_start_date && !$is_end_date) {
										$tool_period_val = 3;
									}

									if(!$is_start_date && $is_end_date) {
										$tool_period_val = 2;
									}
									?>

									<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label"><?= __('admin.tool_period') ?></label>
											<select class="form-control" name="tool_period">
												<option value="1" <?= ($tool_period_val == '1') ? 'selected' : '' ?>><?= __('admin.always_running') ?></option>
												<option value="2" <?= ($tool_period_val == '2') ? 'selected' : '' ?>><?= __('admin.from_today_to_custom_date') ?></option>
												<option value="3" <?= ($tool_period_val == '3') ? 'selected' : '' ?>><?= __('admin.from_custom_date_to_lifetime') ?></option>
												<option value="4" <?= ($tool_period_val == '4') ? 'selected' : '' ?>><?= __('admin.for_custom_period') ?></option>
											</select>
</div>
</div>


									<div id="start_date_input" class="col-sm-4">
									    <div class="form-group">
									        <label class="control-label"><?= __('admin.start_date') ?></label>
									        <input class="form-control datetime-picker" value="<?= (isset($tool) && !empty($tool['start_date']) && $tool['start_date'] != null) ? date('d-m-Y H:i', strtotime($tool['start_date'])) : '' ?>" name="start_date" type="text" autocomplete="off">
</div>
</div>

									<div id="end_date_input" class="col-sm-4">
									    <div class="form-group">
									        <label class="control-label"><?= __('admin.end_date') ?></label>
									        <input class="form-control datetime-picker" value="<?= (isset($tool) && !empty($tool['end_date']) && $tool['end_date'] != null) ? date('d-m-Y H:i', strtotime($tool['end_date'])) : '' ?>" name="end_date" type="text" autocomplete="off">
</div>
</div>

</div>

								<div class="form-group">
									<label class="control-label"><?= __('admin.campaign_target_link') ?></label>
									<input class="form-control" value="<?= isset($tool) ? $tool['target_link'] : '' ?>" name="target_link" type="text">
								</div>

								<div class="card mb-4" id="stripe-fields-card" style="display:none;">
									<div class="card-header bg-primary text-white">
										<h5 class="card-title mb-0">
											<i class="fab fa-stripe me-2"></i><?= __('user.stripe_payment_settings') ?>
										</h5>
									</div>
									<div class="card-body">
										<div id="stripe-error-alert" class="alert alert-danger d-none mb-3" role="alert">
											<i class="fas fa-exclamation-triangle me-2"></i>
											<strong><?= __('user.stripe_error') ?>:</strong> <span id="stripe-error-message"></span>
										</div>
										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="stripe_price" class="form-label"><?= __('user.product_price') ?> <span class="text-danger">*</span></label>
												<div class="input-group">
													<span class="input-group-text"><?= $CurrencySymbol ?></span>
													<input type="number" class="form-control" id="stripe_price" name="stripe_price" 
														value="<?= isset($tool['commission']['stripe_price']) ? $tool['commission']['stripe_price'] : '' ?>" 
														step="0.01" min="0.01" placeholder="99.00">
												</div>
												<small class="text-muted"><?= __('user.amount_customer_will_pay') ?></small>
											</div>
											<div class="col-md-6 mb-3">
												<label for="stripe_currency" class="form-label"><?= __('admin.currency') ?> <span class="text-danger">*</span></label>
												<select class="form-select" id="stripe_currency" name="stripe_currency">
													<?php 
													$selected_currency = isset($tool['commission']['stripe_currency']) ? $tool['commission']['stripe_currency'] : 'usd';
													$currencies = ['usd' => 'USD - US Dollar', 'eur' => 'EUR - Euro', 'gbp' => 'GBP - British Pound', 'cad' => 'CAD - Canadian Dollar', 'aud' => 'AUD - Australian Dollar', 'jpy' => 'JPY - Japanese Yen'];
													foreach ($currencies as $code => $name): ?>
														<option value="<?= $code ?>" <?= $selected_currency == $code ? 'selected' : '' ?>><?= $name ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="alert alert-info mb-0">
											<i class="bi bi-info-circle me-2"></i>
											<strong><?= __('admin.note') ?>:</strong> <?= __('user.stripe_checkout_note') ?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label"><?= __('admin.terms') ?></label>
									<textarea name="terms" class="form-control" placeholder="<?= __('admin.terms') ?>"><?= isset($tool) ? $tool['terms'] : '' ?></textarea>
</div>



								<div class="form-group">
									<label class="col-form-label"><?= __('admin.categories') ?></label>
									<div class="category-container">
										<input name="category_auto" placeholder="<?= __('admin.categories') ?>" id="category_auto" class="form-control" autocomplete="off">
										<ul class="category-selected">
											<?php if(isset($categories)){ ?>
												<?php foreach ($categories as $key => $category) { ?>
													<li>
														<i class="fa fa-trash remove-category"></i>
														<span><?= $category['label'] ?></span>
														<input type="hidden" name="category[]" type="" value="<?= $category['value'] ?>">
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
</div>
</div>
</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label class="control-label"><?= __('user.status'); ?> : </label>
									<?= ads_status($tool['status']) ?>	
</div>

								<div class="well">
									<div class="for-program-tool">
										<div class="form-group">
											<label class="control-label"><?= __('admin.select_program') ?> </label>
											<select class="form-control" name="program_id">
												<option value=""><?= __('admin.select_market_program') ?></option>
												<?php foreach ($programs as $key => $program) { ?>
													<option 
													data-admin_commission_type='<?= $program['admin_commission_type'] ?>'
													data-admin_commission_sale='<?= $program['admin_commission_type'] == 'fixed' ? c_format($program['admin_commission_sale']) : (int)$program['admin_commission_sale'] ."%" ?>'
													data-admin_commission_number_of_click='<?= $program['admin_commission_number_of_click'] ?>'
													data-admin_commission_click_commission='<?= c_format($program['admin_commission_click_commission']) ?>'
													data-admin_click_status='<?= $program['admin_click_status'] ?>'
													data-admin_sale_status='<?= $program['admin_sale_status'] ?>'

													data-commission_type='<?= $program['commission_type'] ?>'
													data-commission_sale='<?= $program['commission_type'] == 'fixed' ? c_format($program['commission_sale']) : (int)$program['commission_sale'] ."%" ?>'
													data-commission_number_of_click='<?= $program['commission_number_of_click'] ?>'
													data-commission_click_commission='<?= c_format($program['commission_click_commission']) ?>'
													data-click_status='<?= $program['click_status'] ?>'
													data-sale_status='<?= $program['sale_status'] ?>'
													<?= (isset($tool) && (int)$tool['program_id'] === (int)$program['id']) ? 'selected' : '' ?> value="<?= $program['id'] ?>"><?= $program['name'] ?></option>
												<?php } ?>
											</select>
</div>

										<div class="form-group program-selector" style="display:none;">
											<label class="control-label"><?= __('user.admin_commission'); ?></label>
											<div class="program-admin-comission"></div>
											<label class="control-label"><?= __('user.affiliate_commission'); ?></label>
											<div class="program-affiliate-comission"></div>
</div>

										<div class="text-right">
											<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProgram"><?= __('user.add_program') ?></button>
</div>

										<script type="text/javascript">
											$('select[name="program_id"]').change(function(){
												if($(this).val() != ""){
													$(".program-selector").css('display','block');
												}else{
													$(".program-selector").css('display','none');
												}
												var data = $('select[name="program_id"] option:selected').data();
												var adminComissionString = '';
												var affiliateComissionString = '';
												if(Object.keys(data).length){
													adminComissionString += '<b>'+'<?= __('user.click') ?>'+'</b> : ';
													if(data['admin_click_status']){
														adminComissionString += data['admin_commission_click_commission'] + ' '+'<?= __('user.per') ?>'+' ' + data['admin_commission_number_of_click'] + " "+'<?= __('user.clicks') ?>';
													} else{
														adminComissionString += '<?= __('user.disabled') ?>';
													}

													adminComissionString += ' &nbsp; | &nbsp; <b> '+'<?= __('user.sale') ?>'+' </b> : ';
													if(data['admin_sale_status']){
														adminComissionString += data['admin_commission_sale'];
													} else{
														adminComissionString += '<?= __('user.disabled') ?>';
													}

													affiliateComissionString += '<b>'+'<?= __('user.click') ?>'+'</b> : ';
													if(data['click_status']){
														affiliateComissionString += data['commission_click_commission'] + ' '+'<?= __('user.per') ?>'+' ' + data['commission_number_of_click'] + " "+'<?= __('user.clicks') ?>';
													} else{
														affiliateComissionString += '<?= __('user.disabled') ?>';
													}

													affiliateComissionString += ' &nbsp; | &nbsp; <b> '+'<?= __('user.sale') ?>'+' </b> : ';
													if(data['sale_status']){
														affiliateComissionString += data['commission_sale'];
													} else{
														affiliateComissionString += '<?= __('user.disabled') ?>';
													}
												} else{
													adminComissionString += '<?= __('user.program_not_selected') ?>';
													affiliateComissionString += '<?= __('user.program_not_selected') ?>';
												}

												$(".program-admin-comission").html(adminComissionString);
												$(".program-affiliate-comission").html(affiliateComissionString);
												
											})
											$('select[name="program_id"]').trigger("change")
										</script>
</div>

									<div class="for-action-tool">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><?= __('admin.number_of_action_per_commission') ?></label>
													<input class="form-control" name="action_click" value="<?= isset($tool) ? $tool['action_click'] : '' ?>">
</div>
</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><?= __('admin.cost_per_action') ?> ($)</label>
													<input class="form-control" name="action_amount" value="<?= isset($tool) ? $tool['action_amount'] : '' ?>">
</div>
</div>
</div>

										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">
														<?= __('admin.action_code') ?>
														<span data-toggle="tooltip" data-original-title="<?= __('user.code_of_action_commission_tracking_script_to_specify') ?>"></span>
													</label>
													<input class="form-control" name="action_code" id="action_code" value="<?= isset($tool) ? $tool['action_code'] : $randome_code ?>">
</div>
</div>
											<div class="col-sm-6">	
												<div class="form-group">
													<label class="control-label"> 
														<span><?= __('admin.generate_new_code') ?></span>
													</label>
													<button type="button" class="btn btn-primary btn-sm form-control" onclick="return GeneratenNewCode('action_code');"><?= __('admin.generate') ?></button>	
</div>
</div>	
</div>	
										
										<div class="form-group">
											<label class="control-label"><?= __('user.admin_setting') ?>: 
												<?= ($tool['admin_action_amount'] && (int)$tool['admin_action_click']) ? c_format($tool['admin_action_amount']) ." ".__('user.per')." ". (int)$tool['admin_action_click'] ." ".__('user.clicks') : __('user.not_set') ?>
											</label>
</div>
									
</div>

									<div class="for-general_click-tool">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><?= __('admin.number_of_click') ?></label>
													<input class="form-control" name="general_click" value="<?= isset($tool) ? $tool['general_click'] : '' ?>">
</div>
</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><?= __('admin.cost_per_click') ?>($)</label>
													<input class="form-control" name="general_amount" value="<?= isset($tool) ? $tool['general_amount'] : '' ?>">
</div>
</div>
</div>

										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><?= __('admin.general_code') ?>
													<span data-toggle="tooltip" data-original-title="<?= __('user.code_of_general_click_tracking_script_to_specify') ?>">
													</span>
													</label>
													<input class="form-control" name="general_code" id="general_code" value="<?= isset($tool) ? $tool['general_code'] : '' ?>">
</div>
</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"> 
														<span><?= __('admin.generate_new_code') ?></span>
													</label>
													<button type="button" class="btn btn-primary btn-sm form-control" onclick="return GeneratenNewCode('general_code');"><?= __('admin.generate') ?></button>	
</div>
</div>	
</div>

									<div class="form-group">
										<label class="control-label"><?= __('user.admin_setting') ?>:
											<?= ($tool['admin_general_amount'] && (int)$tool['admin_general_click']) ? c_format($tool['admin_general_amount']) ." ".__('user.per')." ". (int)$tool['admin_general_click'] ." ".__('user.clicks') : __('user.not_set') ?>
										</label>
</div>
</div>
</div>

							<div class="card mt-3">
								<div class="card-header "><p class="m-0"><?= __('user.vendor_commnts') ?></p></div>
								<div class="card-body chat-card">
									<?php $comment = json_decode($tool['comment'],1); ?>
									<?php if($comment){ ?>
										<ul class="comment-products">
											<?php foreach ($comment as $key => $value) { ?>
												<li class="<?= $value['from'] == 'affiliate' ? 'me' : 'other' ?>"> 
													<?php if ($value['from']=='affiliate'): ?>
														
														<div  data-id="<?= $key ?>" class="comment-content-<?= $key ?>"><?= $value['comment'] ?></div><a href="javascript:void(0)" data-id="<?= $key ?>" class="edit-comment"><i class="fa fa-pencil-square-o"></i></a> 
													<?php else: ?>
														<div><?= $value['comment'] ?></div> 
													<?php endif ?>
												</li>
											<?php } ?>
										</ul>
									<?php } else echo '<ul class="comment-products"></ul>'; ?>
									<div class="bg-white form-group m-0 p-2">
										<textarea class="form-control" id="comment-box" placeholder="<?= __('user.enter_message_and_save_program_to_send') ?>" name="comment"></textarea>
</div>
									<div class="form-group text-right d-none" id="btnUpdateArea">
										<button type="button" id="btnUpdate" class="btn btn-primary"><?php echo __('user.update')?></button>
										<input type="hidden" id="updateid" value="">
</div>
</div>
</div>

							<div class="form-group">
								<label class="control-label"><?= __('admin.cookies_type') ?></label>
								<select class="form-control cookies_type_select" name="cookies_type">
									<option value="0" selected><?= __('admin.default') ?></option>
									<option value="1" <?= isset($tool) && $tool['cookies_type'] == 1 ? 'selected' : '' ?>><?= __('admin.custom') ?></option>
								</select>
</div>

							<div class="form-group cookies_type_input" <?= isset($tool) && $tool['cookies_type'] == 1 ? '' : 'style="display:none"' ?>>
                                <label class="control-label"><?= __('admin.custom_cookies_tracker_in_days') ?></label>
                                <input class="form-control" type="number" value="<?= isset($tool) ? $tool['custom_cookies'] : '' ?>" name="custom_cookies" />
</div>
</div>
</div>

					<div class="form-group">
						<label class="control-label d-block"><?= __('admin.featured_image') ?></label>

						<div class="fileUpload btn btn-sm btn-primary">
							<span><?= __('admin.choose_file') ?></span>
							<input onchange="readURL(this,'#featured_image')" id="product_featured_image" name="featured_image" class="upload" type="file">
</div>

						<?php $featured_image = $tool['featured_image'] != '' ? 'assets/images/product/upload/thumb/' . $tool['featured_image'] : 'assets/images/no_product_image.png' ; ?>
						<?php 
						$campaign_default_image_class = $tool['featured_image'] != '' ? '' : 'campaign_default_image' ;
						?>
						<input type="hidden" name="old_featured_image" value="<?= $tool['featured_image'] ?>">
						<img src="<?php echo base_url($featured_image); ?>" id='featured_image' class="img-thumbnail campaign_default_image" border="0" width="100px">
</div>

					<?php if($type == 'banner'){ ?>
						<div class="card border mb-3">
							<div class="card-header bg-light">
								<h5 class="mb-0"><i class="fas fa-images me-2 text-primary"></i><?= __('admin.banner_images') ?></h5>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-hover banner-table align-middle">
										<thead class="table-light">
											<tr>
												<th><i class="fas fa-image me-1"></i><?= __('admin.image') ?></th>
												<th style="width: 180px;"><i class="fas fa-ruler-combined me-1"></i><?= __('admin.size') ?></th>
												<th style="width: 80px;" class="text-center"><i class="fas fa-cog me-1"></i><?= __('user.action') ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($tool['ads'] as $key => $ads) { ?>
												<?php 
													$ads_val = trim($ads['value']);
													$image_src = base_url('assets/images/no_product_image.png');
													
													if (strpos($ads_val, 'http') === 0) {
														$image_src = $ads_val;
													} elseif (strpos($ads_val, 'assets') === 0) {
														$image_src = base_url($ads_val);
													} else {
														$image_src = base_url('assets/integration/uploads/' . $tool['id'] . '/' . $ads_val);
													}
												?>
												<tr>
													<td>
														<input type="hidden" name="keep_ads[]" value="<?= $ads['id'] ?>">
														<input type="hidden" name="custom_banner_ext[]" value="<?= htmlspecialchars($ads['value']) ?>">
														<div class="d-flex align-items-center gap-3">
															<img class="img-thumbnail shadow-sm integration_css-banner-thumb h-auto flex-shrink-0" src="<?= $image_src ?>">
															<div class="flex-grow-1">
																<input type="file" accept="image/*" class="form-control form-control-sm file-input" name="custom_banner[]">
																<small class="text-muted"><?= __('user.select_image_to_replace') ?></small>
															</div>
														</div>
													</td>
													<td class="text-center">
	<span class="badge bg-secondary size-display"><?= htmlspecialchars($ads['size']) ?></span>
	<input type="hidden" class="size-input" value="<?= htmlspecialchars($ads['size']) ?>" name="custom_banner_size[]">
</td>
													<td class="text-center">
														<button type="button" class="btn btn-sm btn-danger remove-custom-image" title="<?= __('admin.delete') ?>">
															<i class="fa fa-trash"></i>
														</button>
													</td>
												</tr>
											<?php } ?>
											<?php if(!isset($tool['ads']) || empty($tool['ads'])) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center gap-3">
															<img class="img-thumbnail campaign_default_image shadow-sm integration_css-banner-thumb h-auto flex-shrink-0" src="<?= base_url('assets/images/no_product_image.png'); ?>">
															<div class="flex-grow-1">
																<input type="file" accept="image/*" class="form-control form-control-sm file-input" name="custom_banner[]">
																<small class="text-muted"><?= __('user.select_banner_image') ?></small>
															</div>
														</div>
														<input type="hidden" name="custom_banner_ext[]" value="">
														<input type="hidden" name="keep_ads[]" value="0">
													</td>
													<td class="text-center">
	<span class="badge bg-secondary size-display"></span>
	<input type="hidden" class="size-input" name="custom_banner_size[]">
</td>
													<td class="text-center">
														<button type="button" class="btn btn-sm btn-danger remove-custom-image" title="<?= __('admin.delete') ?>">
															<i class="fa fa-trash"></i>
														</button>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<div class="text-end mt-3">
									<button type="button" class="btn add-banner btn-primary">
										<i class="fas fa-plus me-2"></i><?= __('admin.add_banner') ?>
									</button>
								</div>
							</div>
						</div>
					<?php } else if($type == 'text_ads'){ ?>
						<?php 
						$_text_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
						?>
						<div class="alert alert-info d-flex align-items-start mb-3">
							<i class="fas fa-lightbulb fa-2x me-3"></i>
							<div>
								<strong><?= __('user.text_ad_tips') ?>:</strong>
								<p class="mb-0 small"><?= __('user.text_ad_tips_desc') ?></p>
							</div>
						</div>

						<div class="card border mb-3">
							<div class="card-header bg-light">
								<h5 class="mb-0"><i class="fas fa-font me-2 text-primary"></i><?= __('admin.text_ad_content') ?></h5>
							</div>
							<div class="card-body">
								<div class="mb-4">
									<label class="form-label fw-semibold"><?= __('admin.content') ?> <span class="text-danger">*</span></label>
									<textarea class="form-control" rows="10" name="text_ads_content" placeholder="<?= __('user.enter_ad_text_html_example') ?>" required><?= isset($_text_ads['value']) ? htmlspecialchars($_text_ads['value']) : '' ?></textarea>
									<small class="text-muted"><i class="fas fa-code me-1"></i><?= __('user.html_supported') ?></small>
								</div>

								<hr class="my-4">
								<h6 class="mb-3"><i class="fas fa-paint-brush me-2"></i><?= __('user.style_settings') ?></h6>

								<div class="mb-3">
									<label class="form-label fw-semibold"><i class="fas fa-text-height me-1"></i><?= __('admin.text_size_px') ?></label>
									<input class="form-control" name="text_size" type="number" value="<?= isset($_text_ads['text_size']) ? htmlspecialchars($_text_ads['text_size']) : '14' ?>" placeholder="14" min="8" max="100">
									<small class="text-muted"><?= __('user.typical_14_18') ?></small>
								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="mb-3">
											<label class="form-label fw-semibold"><i class="fas fa-palette me-1"></i><?= __('admin.text_color') ?></label>
											<input class="form-control form-control-color w-100" type="color" name="text_color" value="<?= isset($_text_ads['text_color']) ? htmlspecialchars($_text_ads['text_color']) : '#000000' ?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label class="form-label fw-semibold"><i class="fas fa-fill-drip me-1"></i><?= __('admin.background_color') ?></label>
											<input class="form-control form-control-color w-100" type="color" name="text_bg_color" value="<?= isset($_text_ads['text_bg_color']) ? htmlspecialchars($_text_ads['text_bg_color']) : '#ffffff' ?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label class="form-label fw-semibold"><i class="fas fa-border-style me-1"></i><?= __('admin.border_color') ?></label>
											<input class="form-control form-control-color w-100" type="color" name="text_border_color" value="<?= isset($_text_ads['text_border_color']) ? htmlspecialchars($_text_ads['text_border_color']) : '#cccccc' ?>">
										</div>
									</div>
								</div>
							</div>
						</div>

					<?php } else if($type == 'link_ads'){ ?>
						<?php 
						$link_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
						?>
						<div class="alert alert-info d-flex align-items-start mb-3">
							<i class="fas fa-mouse-pointer fa-2x me-3"></i>
							<div>
								<strong><?= __('user.link_ad_tips') ?>:</strong>
								<p class="mb-0 small"><?= __('user.link_ad_tips_desc') ?></p>
							</div>
						</div>

						<div class="card border mb-3">
							<div class="card-header bg-light">
								<h5 class="mb-0"><i class="fas fa-link me-2 text-primary"></i><?= __('admin.link_ad_settings') ?></h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label class="form-label fw-semibold"><?= __('admin.link_title') ?> <span class="text-danger">*</span></label>
											<input class="form-control form-control-lg link-title-input" name="link_title" value="<?= isset($link_ads['value']) ? htmlspecialchars($link_ads['value']) : '' ?>" placeholder="<?= __('user.eg_shop_now') ?>" required>
											<small class="text-muted"><i class="fas fa-info-circle me-1"></i><?= __('user.use_action_words') ?></small>
										</div>
									</div>
									<div class="col-md-6">
										<label class="form-label fw-semibold"><i class="fas fa-eye me-2"></i><?= __('user.live_preview') ?></label>
										<div class="border rounded-3 p-4 text-center bg-light">
											<a href="#" class="btn btn-primary btn-lg link-preview-text" onclick="return false;">
												<?= isset($link_ads['value']) && $link_ads['value'] ? htmlspecialchars($link_ads['value']) : __('user.your_link_here') ?>
											</a>
										</div>
										<small class="text-muted mt-2 d-block"><?= __('user.preview_updates_live') ?></small>
									</div>
								</div>
							</div>
						</div>

<script>
$(document).ready(function(){
	$('.link-title-input').on('input', function(){
		var text = $(this).val() || '<?= __("user.your_link_here") ?>';
		$('.link-preview-text').text(text);
	});
});
</script>

					<?php } else if($type == 'video_ads'){ ?>
						<?php 
						$video_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
						$video_source_type = isset($video_ads['video_source_type']) ? $video_ads['video_source_type'] : 'youtube_vimeo';
						?>
						<div class="alert alert-info d-flex align-items-start mb-3">
							<i class="fas fa-video fa-2x me-3 text-primary"></i>
							<div>
								<strong><?= __('user.video_ad_tips') ?>:</strong>
								<p class="mb-0 small"><?= __('user.video_ad_tips_desc') ?></p>
							</div>
						</div>

						<div class="card border mb-3">
							<div class="card-header bg-light">
								<h5 class="mb-0"><i class="fas fa-video me-2 text-primary"></i><?= __('admin.video_ad_settings') ?></h5>
							</div>
							<div class="card-body">
								<div class="mb-4">
									<label class="form-label fw-semibold"><i class="fas fa-film me-1"></i><?= __('user.video_source_type') ?> <span class="text-danger">*</span></label>
									<div class="row g-2">
										<div class="col-md-4">
											<div class="form-check card border p-3 h-100">
												<input class="form-check-input video-source-radio" type="radio" name="video_source_type" id="source_youtube" value="youtube_vimeo" <?= $video_source_type == 'youtube_vimeo' ? 'checked' : '' ?>>
												<label class="form-check-label d-block text-center" for="source_youtube">
													<i class="fas fa-play-circle fa-2x text-danger mb-2"></i>
													<div class="fw-semibold"><?= __('user.youtube_vimeo') ?></div>
													<small class="text-muted"><?= __('user.paste_video_url') ?></small>
												</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-check card border p-3 h-100">
												<input class="form-check-input video-source-radio" type="radio" name="video_source_type" id="source_mp4_url" value="mp4_url" <?= $video_source_type == 'mp4_url' ? 'checked' : '' ?>>
												<label class="form-check-label d-block text-center" for="source_mp4_url">
													<i class="fas fa-link fa-2x text-primary mb-2"></i>
													<div class="fw-semibold"><?= __('user.mp4_url') ?></div>
													<small class="text-muted"><?= __('user.direct_mp4_link') ?></small>
												</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-check card border p-3 h-100">
												<input class="form-check-input video-source-radio" type="radio" name="video_source_type" id="source_mp4_upload" value="mp4_upload" <?= $video_source_type == 'mp4_upload' ? 'checked' : '' ?>>
												<label class="form-check-label d-block text-center" for="source_mp4_upload">
													<i class="fas fa-upload fa-2x text-success mb-2"></i>
													<div class="fw-semibold"><?= __('user.upload_mp4') ?></div>
													<small class="text-muted"><?= __('user.upload_video_file') ?></small>
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div id="youtube_vimeo_section" class="video-source-section mb-3" style="<?= $video_source_type != 'youtube_vimeo' ? 'display:none;' : '' ?>">
											<label class="form-label fw-semibold"><i class="fas fa-video me-1"></i><?= __('user.video_link') ?> <span class="text-danger">*</span></label>
											<div class="input-group">
												<span class="input-group-text bg-danger text-white"><i class="fas fa-video"></i></span>
												<input class="form-control parse-video" name="video_link" value="<?= ($video_source_type == 'youtube_vimeo' && isset($video_ads['value'])) ? htmlspecialchars($video_ads['value']) : '' ?>" placeholder="<?= __('user.enter_video_url_placeholder') ?>">
											</div>
											<small class="text-muted"><i class="fas fa-info-circle me-1"></i><?= __('user.supports_youtube_vimeo') ?></small>
										</div>

										<div id="mp4_url_section" class="video-source-section mb-3" style="<?= $video_source_type != 'mp4_url' ? 'display:none;' : '' ?>">
											<label class="form-label fw-semibold"><i class="fas fa-link me-1"></i><?= __('user.mp4_video_url') ?> <span class="text-danger">*</span></label>
											<div class="input-group">
												<span class="input-group-text bg-primary text-white"><i class="fas fa-video"></i></span>
												<input class="form-control parse-video-mp4" name="mp4_url" value="<?= ($video_source_type == 'mp4_url' && isset($video_ads['value'])) ? htmlspecialchars($video_ads['value']) : '' ?>" placeholder="https://example.com/video.mp4">
											</div>
											<small class="text-muted"><i class="fas fa-info-circle me-1"></i><?= __('user.enter_direct_mp4_url') ?></small>
										</div>

										<div id="mp4_upload_section" class="video-source-section mb-3" style="<?= $video_source_type != 'mp4_upload' ? 'display:none;' : '' ?>">
											<label class="form-label fw-semibold"><i class="fas fa-upload me-1"></i><?= __('user.upload_mp4_file') ?> <span class="text-danger">*</span></label>
											<input type="file" class="form-control video-upload-input" name="video_file" accept="video/mp4,video/webm,video/ogg">
											<small class="text-muted"><i class="fas fa-info-circle me-1"></i><?= __('user.max_video_size') ?></small>
											<?php if($video_source_type == 'mp4_upload' && isset($video_ads['value']) && !empty($video_ads['value'])): ?>
												<div class="mt-2 p-2 bg-light rounded">
													<small class="text-success"><i class="fas fa-check-circle me-1"></i><?= __('user.current_video') ?>: <?= basename($video_ads['value']) ?></small>
													<input type="hidden" name="existing_video" value="<?= htmlspecialchars($video_ads['value']) ?>">
												</div>
											<?php endif; ?>
										</div>

										<div class="mb-3">
											<label class="form-label fw-semibold"><i class="fas fa-play me-1"></i><?= __('admin.autoplay') ?></label>
											<div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="autoplay" id="autoplay_no" value="0" checked>
													<label class="form-check-label" for="autoplay_no"><?= __('admin.disable') ?></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="autoplay" id="autoplay_yes" value="1" <?= (isset($video_ads['autoplay']) && $video_ads['autoplay']) ? 'checked' : '' ?>>
													<label class="form-check-label" for="autoplay_yes"><?= __('admin.enable') ?></label>
												</div>
											</div>
										</div>

										<hr class="my-3">
										<h6 class="mb-3"><i class="fas fa-sliders-h me-2"></i><?= __('user.video_dimensions') ?></h6>
										<div class="row">
											<div class="col-6">
												<div class="mb-3">
													<label class="form-label fw-semibold"><i class="fas fa-arrows-alt-h me-1"></i><?= __('admin.width_px') ?></label>
													<input class="form-control" name="video_width" type="number" value="<?= isset($video_ads['video_width']) ? htmlspecialchars($video_ads['video_width']) : '560' ?>" placeholder="560" min="300">
													<small class="text-muted"><?= __('user.default_560') ?></small>
												</div>
											</div>
											<div class="col-6">
												<div class="mb-3">
													<label class="form-label fw-semibold"><i class="fas fa-arrows-alt-v me-1"></i><?= __('admin.height_px') ?></label>
													<input class="form-control" name="video_height" type="number" value="<?= isset($video_ads['video_height']) ? htmlspecialchars($video_ads['video_height']) : '315' ?>" placeholder="315" min="200">
													<small class="text-muted"><?= __('user.default_315') ?></small>
												</div>
											</div>
										</div>

										<div class="mb-3">
											<label class="form-label fw-semibold"><i class="fas fa-mouse-pointer me-1"></i><?= __('admin.button_text') ?></label>
											<input class="form-control" name="button_text" value="<?= isset($video_ads['size']) ? htmlspecialchars($video_ads['size']) : '' ?>" placeholder="<?= __('user.eg_watch_now') ?>">
											<small class="text-muted"><?= __('user.optional_button_text') ?></small>
										</div>
									</div>
									<div class="col-md-6">
										<label class="form-label fw-semibold mb-3"><i class="fas fa-eye me-2"></i><?= __('user.live_video_preview') ?></label>
										<div class="video-preview-container border border-2 border-primary rounded-3 p-3 bg-white shadow" style="min-height: 350px;">
											<div class="text-center text-muted py-5 video-placeholder">
												<i class="fas fa-video fa-4x mb-3 text-primary opacity-50"></i>
												<p class="mb-2 fw-bold"><?= __('user.video_appears_here') ?></p>
												<p class="mb-0 small"><?= __('user.enter_url_to_preview') ?></p>
											</div>
										</div>
										<input type="hidden" class="form-control video-priview" readonly>
										<div class="alert alert-success mt-3 py-2">
											<small><i class="fas fa-check-circle me-1"></i><?= __('user.auto_updates_as_you_type') ?></small>
										</div>
									</div>
								</div>
							</div>
						</div>	

					<?php } ?>


					<?php $allow_for = array_filter(explode(",", $tool['allow_for'])); ?>
					<div class="border-top mt-4 pt-3">
					<div class="row g-3">
						<div class="col-lg-9">
							<div class="card shadow-sm border-0">
								<div class="card-header bg-white border-bottom d-flex align-items-center">
									<i class="bi bi-people-fill me-2 text-primary"></i>
									<span class="fw-semibold"><?= __('admin.allow_for') ?></span>
								</div>
								<div class="card-body pb-2">
									<div class="d-flex flex-wrap gap-2 mb-3">
										<div class="form-check form-check-inline mb-0">
											<input class="form-check-input allow_for" type="radio" id="vendorAllowForAll" name="allow_for_radio" <?= count($allow_for) == 0 ? 'checked' : '' ?> value="0">
											<label class="form-check-label fw-medium" for="vendorAllowForAll"><i class="bi bi-globe2 me-1 text-muted"></i><?= __('admin.all') ?></label>
										</div>
										<div class="form-check form-check-inline mb-0">
											<input class="form-check-input allow_for" type="radio" id="vendorAllowForSelected" name="allow_for_radio" <?= count($allow_for) > 0 ? 'checked' : '' ?> value="1">
											<label class="form-check-label fw-medium" for="vendorAllowForSelected"><i class="bi bi-person-check me-1 text-muted"></i><?= __('admin.selected_affiliate') ?></label>
										</div>
									</div>
									<div class="show-allow_for" style="display:none;">
										<div class="border rounded bg-light p-2" style="max-height:180px;overflow-y:auto;">
											<?php if(empty($users)): ?>
												<div class="text-center text-muted py-3"><i class="bi bi-person-slash fs-5 d-block mb-1"></i><small><?= __('admin.no_affiliates_found') ?></small></div>
											<?php else: ?>
												<?php foreach ($users as $v) { ?>
													<label class="d-flex align-items-center gap-2 px-2 py-1 rounded<?= in_array($v['id'],$allow_for) ? ' bg-primary bg-opacity-10' : '' ?>" style="cursor:pointer;">
														<input type="checkbox" class="form-check-input mt-0" <?= in_array($v['id'],$allow_for) ? 'checked' : '' ?> name="allow_for[]" value="<?= $v['id'] ?>">
														<span class="small"><?= $v['name'] ?></span>
													</label>
												<?php } ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="card shadow-sm border-0 h-100">
								<div class="card-header bg-white border-bottom d-flex align-items-center">
									<i class="bi bi-toggle-on me-2 text-primary"></i>
									<span class="fw-semibold"><?= __('admin.status') ?></span>
								</div>
								<div class="card-body d-flex align-items-center justify-content-center">
									<div class="d-flex flex-wrap gap-3 justify-content-center">
										<div class="form-check">
											<input class="form-check-input" type="radio" name="status" value="0" id="vendorStatusDraft" <?= (int) $tool['status'] == 0 ? 'checked' : '' ?>>
											<label class="form-check-label badge bg-warning bg-opacity-75 px-3 py-2 fs-6" for="vendorStatusDraft"><?= __('user.draft') ?></label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="radio" name="status" value="1" id="vendorStatusPublic" <?= (int) $tool['status'] == 1 ? 'checked' : '' ?>>
											<label class="form-check-label badge bg-success bg-opacity-75 px-3 py-2 fs-6" for="vendorStatusPublic"><?= __('user.public') ?></label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					</div>
					<script type="text/javascript">
						$(".allow_for").on('change',function(){
							$(".show-allow_for").hide();
							if($(this).val() == '1'){
								$(".show-allow_for").show();
							}
						})
						$(".allow_for:checked").trigger("change");
					</script>
</div>

				<!-- ==================== VENDOR INTEGRATION SETUP TAB ==================== -->
				<div class="tab-pane col-sm-12 fade" id="vendor-integration-setup" role="tabpanel" aria-labelledby="vendor-integration-setup-tab">
<?php $v_current_method = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel'; ?>
<input type="hidden" name="integration_method" id="vendor_integration_method" value="<?= $v_current_method ?>">

<div class="card mb-3">
  <div class="card-header"><i class="fas fa-plug me-2 text-primary"></i><?= __('user.integration_method_choose') ?></div>
  <div class="card-body">
    <p class="text-muted mb-3"><?= __('user.integration_method_choose_desc') ?></p>
    <div class="row g-3">
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 v-intg-method-card <?= $v_current_method == 'js_pixel' ? 'border-primary shadow' : '' ?>" data-method="js_pixel" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="fas fa-code fs-1 text-primary"></i></div>
            <h6 class="fw-bold mb-1"><?= __('user.integration_method_js_pixel') ?></h6>
            <small class="text-muted"><?= __('user.integration_method_js_pixel_desc') ?></small>
          </div>
          <?php if($v_current_method == 'js_pixel'): ?><div class="card-footer bg-primary text-white text-center py-1 small fw-bold"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 v-intg-method-card <?= $v_current_method == 's2s' ? 'border-warning shadow' : '' ?>" data-method="s2s" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="fas fa-server fs-1 text-warning"></i></div>
            <h6 class="fw-bold mb-1"><?= __('user.integration_method_s2s') ?></h6>
            <small class="text-muted"><?= __('user.integration_method_s2s_desc') ?></small>
          </div>
          <?php if($v_current_method == 's2s'): ?><div class="card-footer bg-warning text-dark text-center py-1 small fw-bold"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 v-intg-method-card <?= $v_current_method == 's2s_direct' ? 'border-info shadow' : '' ?>" data-method="s2s_direct" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="fas fa-mobile-alt fs-1 text-info"></i></div>
            <h6 class="fw-bold mb-1"><?= __('user.integration_method_mobile') ?></h6>
            <small class="text-muted"><?= __('user.integration_method_mobile_desc') ?></small>
          </div>
          <?php if($v_current_method == 's2s_direct'): ?><div class="card-footer bg-info text-white text-center py-1 small fw-bold"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 v-intg-method-card <?= $v_current_method == 'postback' ? 'border-secondary shadow' : '' ?>" data-method="postback" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="fas fa-exchange-alt fs-1 text-secondary"></i></div>
            <h6 class="fw-bold mb-1"><?= __('user.integration_method_postback') ?></h6>
            <small class="text-muted"><?= __('user.integration_method_postback_desc') ?></small>
          </div>
          <?php if($v_current_method == 'postback'): ?><div class="card-footer bg-secondary text-white text-center py-1 small fw-bold"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 v-intg-method-card <?= $v_current_method == 'conversion_api' ? 'border-dark shadow' : '' ?>" data-method="conversion_api" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="fas fa-cogs fs-1 text-dark"></i></div>
            <h6 class="fw-bold mb-1"><?= __('user.integration_method_conv_api') ?></h6>
            <small class="text-muted"><?= __('user.integration_method_conv_api_desc') ?></small>
          </div>
          <?php if($v_current_method == 'conversion_api'): ?><div class="card-footer bg-dark text-white text-center py-1 small fw-bold"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
    </div>
    <div class="alert alert-light border small mb-0 mt-3 py-2">
      <i class="fas fa-lightbulb text-warning me-1"></i> <?= __('user.integration_multi_method_note') ?>
    </div>
  </div>
</div>

<!-- JS Pixel content -->
<div class="v-intg-method-content" id="v-method-js_pixel" style="<?= $v_current_method == 'js_pixel' ? '' : 'display:none' ?>">
  <div class="card border-start border-primary border-3">
    <div class="card-header bg-primary bg-gradient text-white"><i class="fas fa-code me-2"></i><?= __('user.integration_method_js_pixel') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="fas fa-project-diagram me-1"></i><?= __('user.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-primary rounded-pill px-3 py-2"><?= __('user.flow_js_step1') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-primary rounded-pill px-3 py-2"><?= __('user.flow_js_step2') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-primary rounded-pill px-3 py-2"><?= __('user.flow_js_step3') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-primary rounded-pill px-3 py-2"><?= __('user.flow_js_step4') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('user.flow_js_step5') ?></span>
        </div>
      </div>
      <?php if(isset($tool)): ?>
      <button type="button" class="btn btn-outline-primary v-btn-get-code" data-id="<?= $tool['id'] ?>">
        <i class="fas fa-code me-1"></i> <?= __('user.js_pixel_view_code') ?>
      </button>
      <?php else: ?>
      <div class="alert alert-info py-2 small mb-0"><i class="fas fa-info-circle me-1"></i> <?= __('user.js_pixel_save_first') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- S2S API content -->
<div class="v-intg-method-content" id="v-method-s2s" style="<?= $v_current_method == 's2s' ? '' : 'display:none' ?>">
  <div class="card border-start border-warning border-3">
    <div class="card-header bg-warning bg-gradient text-dark"><i class="fas fa-server me-2"></i><?= __('user.integration_method_s2s') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="fas fa-project-diagram me-1"></i><?= __('user.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('user.flow_s2s_step1') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('user.flow_s2s_step2') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('user.flow_s2s_step3') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('user.flow_s2s_step4') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('user.flow_s2s_step5') ?></span>
        </div>
      </div>
      <?php if(isset($tool) && !empty($tool['api_key'])): ?>
      <div class="mb-3">
        <label class="form-label fw-semibold small text-uppercase text-muted"><?= __('user.s2s_api_key') ?></label>
        <div class="input-group"><input type="text" class="form-control font-monospace bg-light" value="<?= htmlspecialchars($tool['api_key']) ?>" id="v-s2s-api-key" readonly><button class="btn btn-outline-secondary v-copy-btn" data-target="v-s2s-api-key" type="button"><i class="fas fa-copy"></i></button></div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold small text-uppercase text-muted"><?= __('user.s2s_endpoint_url') ?></label>
        <div class="input-group"><input type="text" class="form-control font-monospace bg-light" value="<?= base_url('integration/s2sConvert') ?>" id="v-s2s-endpoint" readonly><button class="btn btn-outline-secondary v-copy-btn" data-target="v-s2s-endpoint" type="button"><i class="fas fa-copy"></i></button></div>
      </div>
      <?php else: ?>
      <div class="alert alert-warning py-2 small mb-0"><i class="fas fa-info-circle me-1"></i> <?= __('user.s2s_save_first') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Mobile App (S2S Direct) content -->
<div class="v-intg-method-content" id="v-method-s2s_direct" style="<?= $v_current_method == 's2s_direct' ? '' : 'display:none' ?>">
  <div class="card border-start border-info border-3">
    <div class="card-header bg-info bg-gradient text-white"><i class="fas fa-mobile-alt me-2"></i><?= __('user.integration_method_mobile') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="fas fa-project-diagram me-1"></i><?= __('user.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-info rounded-pill px-3 py-2"><?= __('user.flow_mobile_step1') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-info rounded-pill px-3 py-2"><?= __('user.flow_mobile_step2') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-info rounded-pill px-3 py-2"><?= __('user.flow_mobile_step3') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('user.flow_mobile_step4') ?></span>
        </div>
      </div>
      <?php if(isset($tool) && !empty($tool['api_key'])): ?>
      <div class="mb-3">
        <label class="form-label fw-semibold small text-uppercase text-muted"><?= __('user.s2s_api_key') ?></label>
        <div class="input-group"><input type="text" class="form-control font-monospace bg-light" value="<?= htmlspecialchars($tool['api_key']) ?>" id="v-mobile-api-key" readonly><button class="btn btn-outline-secondary v-copy-btn" data-target="v-mobile-api-key" type="button"><i class="fas fa-copy"></i></button></div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold small text-uppercase text-muted"><?= __('user.s2s_endpoint_url') ?></label>
        <div class="input-group"><input type="text" class="form-control font-monospace bg-light" value="<?= base_url('integration/s2sConvert') ?>" id="v-mobile-endpoint" readonly><button class="btn btn-outline-secondary v-copy-btn" data-target="v-mobile-endpoint" type="button"><i class="fas fa-copy"></i></button></div>
      </div>
      <?php else: ?>
      <div class="alert alert-warning py-2 small mb-0"><i class="fas fa-info-circle me-1"></i> <?= __('user.s2s_save_first') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Postback content -->
<div class="v-intg-method-content" id="v-method-postback" style="<?= $v_current_method == 'postback' ? '' : 'display:none' ?>">
  <div class="card border-start border-secondary border-3">
    <div class="card-header bg-secondary bg-gradient text-white"><i class="fas fa-exchange-alt me-2"></i><?= __('user.integration_method_postback') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="fas fa-project-diagram me-1"></i><?= __('user.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('user.flow_postback_step1') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('user.flow_postback_step2') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('user.flow_postback_step3') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('user.flow_postback_step4') ?></span>
        </div>
      </div>
      <div class="alert alert-info border-0 mb-3">
        <h6 class="alert-heading fw-bold mb-2"><i class="fas fa-info-circle me-1"></i> <?= __('user.postback_how_it_works') ?></h6>
        <p class="mb-2 small"><?= __('user.postback_how_it_works_desc') ?></p>
        <ol class="mb-0 small">
          <li class="mb-1"><?= __('user.postback_step_1') ?></li>
          <li class="mb-1"><?= __('user.postback_step_2') ?></li>
          <li class="mb-1"><?= __('user.postback_step_3') ?></li>
          <li><?= __('user.postback_step_4') ?></li>
        </ol>
      </div>
      <div class="alert alert-light border-start border-secondary border-3 py-2 mb-3">
        <i class="fas fa-lightbulb text-warning me-1"></i> <small><?= __('user.postback_use_case') ?></small>
      </div>
      <div class="alert alert-secondary border-0 py-2 mb-0">
        <i class="fas fa-cog me-1"></i> <small class="fw-semibold"><?= __('user.postback_configure_below') ?></small>
      </div>
    </div>
  </div>
  <div id="v-method-postback-settings" class="mt-3"></div>
  <script>$(function(){ if($('#vendor_integration_method').val()==='postback'){ $('#postback-setting').appendTo('#v-method-postback-settings').show().addClass('show active'); } });</script>
</div>

<!-- Conversion API content -->
<div class="v-intg-method-content" id="v-method-conversion_api" style="<?= $v_current_method == 'conversion_api' ? '' : 'display:none' ?>">
  <div class="card border-start border-dark border-3">
    <div class="card-header bg-dark bg-gradient text-white"><i class="fas fa-cogs me-2"></i><?= __('user.integration_method_conv_api') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="fas fa-project-diagram me-1"></i><?= __('user.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-dark rounded-pill px-3 py-2"><?= __('user.flow_convapi_step1') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-dark rounded-pill px-3 py-2"><?= __('user.flow_convapi_step2') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-dark rounded-pill px-3 py-2"><?= __('user.flow_convapi_step3') ?></span><i class="fas fa-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('user.flow_convapi_step4') ?></span>
        </div>
      </div>
      <div class="alert alert-info border-0 mb-3">
        <h6 class="alert-heading fw-bold mb-2"><i class="fas fa-info-circle me-1"></i> <?= __('user.conv_api_how_it_works') ?></h6>
        <p class="mb-2 small"><?= __('user.conv_api_how_it_works_desc') ?></p>
        <ol class="mb-0 small">
          <li class="mb-1"><?= __('user.conv_api_step_1') ?></li>
          <li class="mb-1"><?= __('user.conv_api_step_2') ?></li>
          <li class="mb-1"><?= __('user.conv_api_step_3') ?></li>
          <li><?= __('user.conv_api_step_4') ?></li>
        </ol>
      </div>
      <div class="alert alert-light border-start border-dark border-3 py-2 mb-3">
        <i class="fas fa-lightbulb text-warning me-1"></i> <small><?= __('user.conv_api_use_case') ?></small>
      </div>
      <h6 class="fw-bold mt-3 mb-2"><i class="fas fa-directions me-2"></i><?= __('user.conv_api_available_endpoints') ?></h6>
      <div class="row g-2 mb-3">
        <div class="col-md-6">
          <div class="card border h-100">
            <div class="card-body py-2">
              <div class="d-flex align-items-center mb-1">
                <span class="badge bg-warning text-dark me-2">POST</span>
                <small class="fw-bold"><?= __('user.conv_api_click_endpoint') ?></small>
              </div>
              <code class="small d-block text-break mb-1"><?= base_url('integration/addClick') ?></code>
              <small class="text-muted"><?= __('user.conv_api_click_endpoint_desc') ?></small>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card border h-100">
            <div class="card-body py-2">
              <div class="d-flex align-items-center mb-1">
                <span class="badge bg-success me-2">POST</span>
                <small class="fw-bold"><?= __('user.conv_api_sale_endpoint') ?></small>
              </div>
              <code class="small d-block text-break mb-1"><?= base_url('integration/s2sConvert') ?></code>
              <small class="text-muted"><?= __('user.conv_api_sale_endpoint_desc') ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="v-method-conversion_api-docs" class="mt-3"></div>
  <script>$(function(){ if($('#vendor_integration_method').val()==='conversion_api'){ $('#conversion_api').appendTo('#v-method-conversion_api-docs').show().addClass('show active'); } });</script>
</div>
				</div>
				<!-- ==================== END VENDOR INTEGRATION SETUP TAB ==================== -->

				<div class="tab-pane col-sm-12 fade" id="postback-setting" role="tabpanel" aria-labelledby="postback-setting-tab">
				    <?php 
				        $marketpostback = isset($tool['marketpostback']) ? json_decode($tool['marketpostback'], true) : ['status' => ''];
				        $marketpostback = is_array($marketpostback) ? $marketpostback : ['status' => ''];
				        
				        $default_marketpostback = isset($default_marketpostback) ? $default_marketpostback : [];
				        $dynamicparam = [
				            'city' => __('user.city'),
				            'regionCode' => __('user.region_code'),
				            'regionName' => __('user.region_name'),
				            'countryCode' => __('user.country_code'),
				            'countryName' => __('user.country_name'),
				            'continentName' => __('user.continent_name'),
				            'timezone' => __('user.time_zone'),
				            'currencyCode' => __('user.currency_code'),
				            'currencySymbol' => __('user.currency_symbol'),
				            'ip' => __('user.ip'),
				            'id' => __('user.id_sale_id_or_click_id'),
				            'type' => __('user.type').' action,general_click,product_click,sale'
				        ];

					    $integration_types = [
					        //'addClick' => 'Click Conversion',
					        //'addClick?actionCode' => 'Action Conversion',
					        'addOrder' => 'Order Conversion',
					        //'addClick?product_id' => 'Order Product Click Conversion'
					    ];
				    ?>

    <!-- ========== STEP 1: Enable Postback ========== -->
    <div class="card border-start border-success border-3 mb-3">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <span class="badge bg-success rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:14px;">1</span>
          <h6 class="fw-bold mb-0"><?= __('user.postback_step1_title') ?></h6>
        </div>
        <p class="text-muted small mb-2"><?= __('user.postback_step1_desc') ?></p>
        <select class="form-select marketpostback-status" name="marketpostback[status]">
          <option value=""><?= __('user.disable') ?></option>
          <option value="custom" <?= isset($marketpostback['status']) && $marketpostback['status'] == 'custom' ? 'selected' : '' ?>><?= __('user.enable') ?></option>
        </select>
      </div>
    </div>

    <div class="marketpostback-default m-2">
      <div class="card">
        <div class="card-header bg-secondary text-white"><h6 class="m-0"><?= __('user.default_postback_settings') ?></h6></div>
        <div class="card-body">
          <b><?= __('user.status') ?>:</b> <?= isset($default_marketpostback['status']) && (int)$default_marketpostback['status'] == 1 ? __('admin.enable') : __('admin.disable') ?><br>
          <b><?= __('user.postback_url') ?>:</b> <?= isset($default_marketpostback['url']) && $default_marketpostback['url'] ? $default_marketpostback['url'] : 'N/A' ?><br>
        </div>
      </div>
    </div>

    <div class="marketpostback-custom">
      <!-- ========== STEP 2: Build Your Postback URL ========== -->
      <div class="card border-start border-primary border-3 mb-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:14px;">2</span>
            <h6 class="fw-bold mb-0"><?= __('user.postback_step2_title') ?></h6>
          </div>
          <p class="text-muted small mb-3"><?= __('user.postback_step2_desc') ?></p>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold small"><?= __('user.postback_domain_location') ?></label>
              <input type="text" class="form-control" id="custom-domain" name="marketpostback[domain]" value="<?= isset($marketpostback['domain']) ? $marketpostback['domain'] : 'http://your-domain' ?>" placeholder="http://example.com">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small"><?= __('admin.integration_type') ?></label>
              <select class="form-select" id="integration-type" name="marketpostback[integration_type]">
                <?php foreach ($integration_types as $value => $label): ?>
                  <option value="<?= $value ?>" <?= (isset($marketpostback['integration_type']) && $marketpostback['integration_type'] == $value) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="card bg-light border-2 border-primary">
            <div class="card-body py-2">
              <label class="form-label fw-bold small text-primary mb-1"><i class="fas fa-link me-1"></i><?= __('user.postback_generated_url') ?></label>
              <div class="input-group">
                <input type="text" name="marketpostback[url]" value="<?= isset($marketpostback['url']) ? $marketpostback['url'] : '' ?>" class="form-control form-control-lg font-monospace bg-white marketpostback-url" readonly>
                <button class="btn btn-primary" type="button" id="copy-postback-url"><i class="fas fa-clipboard me-1"></i> Copy</button>
              </div>
              <small class="text-muted d-block mt-1"><?= __('user.postback_generated_url_desc') ?></small>
            </div>
          </div>
        </div>
      </div>

      <!-- ========== STEP 3: Choose Data Parameters ========== -->
      <div class="card border-start border-info border-3 mb-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-info text-dark rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:14px;">3</span>
            <h6 class="fw-bold mb-0"><?= __('user.postback_step3_title') ?></h6>
          </div>
          <p class="text-muted small mb-3"><?= __('user.postback_step3_desc') ?></p>

          <h6 class="fw-semibold small text-uppercase text-muted mb-2"><i class="fas fa-sliders-h me-1"></i><?= __('user.dynamic_params') ?></h6>
          <p class="text-muted small mb-2"><?= __('user.postback_dynamic_params_desc') ?></p>
          <div class="row g-2 mb-4">
            <?php foreach ($dynamicparam as $key => $value): ?>
              <div class="col-md-4 col-sm-6">
                <label class="card border h-100 mb-0 p-2 d-flex align-items-start gap-2" style="cursor:pointer;" for="v_dynamic_<?= $key ?>">
                  <input type="checkbox" class="form-check-input mt-1 dynamic-param" id="v_dynamic_<?= $key ?>" <?= isset($marketpostback['dynamicparam'][$key]) ? 'checked' : '' ?> name="marketpostback[dynamicparam][<?= $key ?>]" value="<?= $key ?>">
                  <span><code class="fw-bold"><?= $key ?></code><br><small class="text-muted"><?= $value ?></small></span>
                </label>
              </div>
            <?php endforeach; ?>
          </div>

          <h6 class="fw-semibold small text-uppercase text-muted mb-2"><i class="fas fa-list me-1"></i><?= __('user.static_params') ?></h6>
          <p class="text-muted small mb-2"><?= __('user.postback_static_params_desc') ?></p>
          <div class="static-params table-responsive mb-3">
            <table class="table table-sm table-bordered mb-0">
              <thead class="table-light"><tr><th><?= __('user.param_key') ?></th><th><?= __('user.param_value') ?></th><th width="50px">#</th></tr></thead>
              <tbody></tbody>
              <tfoot><tr><td colspan="3"><button class="btn btn-sm btn-primary add-static-params" type="button"><i class="fas fa-plus me-1"></i><?= __('user.add') ?></button></td></tr></tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Demo files and instructions -->
      <div class="card border-0 bg-light mb-2">
        <div class="card-body py-3">
          <h6 class="fw-semibold small mb-2"><i class="fas fa-download me-1"></i><?= __('admin.demo_files_and_instructions') ?></h6>
          <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('assets/data/order_demo.zip') ?>" class="btn btn-sm btn-outline-primary" download><i class="fas fa-download me-1"></i><?= __('admin.download_order_demo') ?></a>
            <a href="<?= base_url('assets/data/postback_demo.zip') ?>" class="btn btn-sm btn-outline-secondary" download><i class="fas fa-download me-1"></i><?= __('admin.download_postback_demo') ?></a>
            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#postbackInstructionsModal"><i class="fas fa-book me-1"></i><?= __('admin.view_postback_instructions') ?></button>
          </div>
          <small class="text-muted d-block mt-1"><?= __('admin.download_demo_files_and_view_instructions_desc') ?></small>
        </div>
      </div>

      <script type="text/javascript">
        $(".marketpostback-status").change(function(){
          var val = $(this).val();
          $(".marketpostback-default, .marketpostback-custom").hide();
          if(val == 'default') $(".marketpostback-default").show();
          else if(val == 'custom') $(".marketpostback-custom").show();
        }).trigger("change");

        var addStaticParamIndex = 0;
        $(".add-static-params").click(function(){ addStaticParam('', ''); });

        <?php foreach ($marketpostback['static'] ?? [] as $value): ?>
          addStaticParam('<?= addslashes($value['key']) ?>', '<?= addslashes($value['value']) ?>');
        <?php endforeach; ?>

        function addStaticParam(key, val) {
          var html = '<tr><td><input type="text" name="marketpostback[static][' + addStaticParamIndex + '][key]" value="' + key + '" class="form-control static-param-key"></td><td><input type="text" name="marketpostback[static][' + addStaticParamIndex + '][value]" value="' + val + '" class="form-control static-param-value"></td><td><button class="btn btn-sm btn-danger remove-static-params" type="button"><i class="fas fa-trash"></i></button></td></tr>';
          $(".static-params tbody").append(html);
          addStaticParamIndex++;
        }

        $(".static-params").on("click", ".remove-static-params", function(){ $(this).closest("tr").remove(); updatePostbackUrl(); });

        function updatePostbackUrl() {
          var domain = $("#custom-domain").val() || 'http://your-domain';
          domain = domain.replace(/\/+$/, '');
          var params = [];
          $(".dynamic-param:checked").each(function() { params.push($(this).val() + "=[" + $(this).val().toUpperCase() + "]"); });
          $(".static-params tbody tr").each(function() {
            var key = $(this).find(".static-param-key").val();
            var value = $(this).find(".static-param-value").val();
            if (key && value) params.push(key + "=" + value);
          });
          params.push("script_name=general_integration");
          var fullUrl = domain + "/integration/?" + params.join("&");
          $(".marketpostback-url").val(fullUrl);
        }

        $(".dynamic-param, #custom-domain").change(updatePostbackUrl);
        $(".static-params").on('input', '.static-param-key, .static-param-value', updatePostbackUrl);
        $("#copy-postback-url").click(function(){ $(".marketpostback-url").select(); document.execCommand('copy'); });
        updatePostbackUrl();
      </script>
    </div>



<!-- Modal for Postback Instructions -->
<div class="modal fade" id="postbackInstructionsModal" tabindex="-1" aria-labelledby="postbackInstructionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postbackInstructionsModalLabel"><?= __('admin.postback_campaign_setup_guide'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('admin.close'); ?>"></button>
</div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="instructionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="campaign-tab" data-bs-toggle="tab" data-bs-target="#campaign" type="button" role="tab" aria-controls="campaign" aria-selected="true"><?= __('admin.campaign_setup'); ?></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="demo-tab" data-bs-toggle="tab" data-bs-target="#demo" type="button" role="tab" aria-controls="demo" aria-selected="false"><?= __('admin.demo_setup'); ?></button>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="instructionTabsContent">
                    <!-- Campaign Setup Tab -->
                    <div class="tab-pane fade show active" id="campaign" role="tabpanel" aria-labelledby="campaign-tab">
                        <h6><?= __('admin.how_to_create_postback_campaign'); ?></h6>
                        <ol>
                            <li><?= __('admin.login_to_admin_panel'); ?></li>
                            <li><?= __('admin.navigate_to_campaigns_menu'); ?></li>
                            <li><?= __('admin.create_new_campaign'); ?></li>
                            <li><?= __('admin.configure_general_settings'); ?>
                                <ul>
                                    <li><?= __('admin.insert_campaign_name'); ?></li>
                                    <li><?= __('admin.select_tool_type_sales_integration'); ?></li>
                                    <li><?= __('admin.select_custom_order_integration'); ?></li>
                                </ul>
                            </li>
                            <li><?= __('admin.set_campaign_target_link'); ?></li>
                            <li><?= __('admin.choose_affiliate_program'); ?></li>
                            <li><?= __('admin.navigate_to_postback_tab'); ?></li>
                            <li><?= __('admin.enable_postback_status'); ?></li>
                            <li><?= __('admin.set_postback_domain_location'); ?></li>
                            <li><?= __('admin.choose_order_campaign'); ?></li>
                            <li><?= __('admin.configure_postback_url_and_params'); ?></li>
                            <li><?= __('admin.save_campaign'); ?></li>
                            <li><?= __('admin.edit_campaign_and_copy_postback_url'); ?></li>
                            <li><?= __('admin.no_integration_code_required'); ?></li>
                            <li><?= __('admin.test_campaign'); ?>
                                <ul>
                                    <li><?= __('admin.test_affiliate_link'); ?></li>
                                </ul>
                            </li>
                        </ol>
</div>

                    <!-- Demo Setup Tab -->
                    <div class="tab-pane fade" id="demo" role="tabpanel" aria-labelledby="demo-tab">
                        <h6><?= __('admin.how_to_create_postback_demo'); ?></h6>
                        <ol>
                            <li><?= __('admin.use_provided_demo_files'); ?></li>
                            <li><?= __('admin.set_demo_target_url'); ?> 
                                <code class="badge bg-primary text-wrap">https://yourdomain/postback_folder/order_demo.php</code>
                            </li>
                            <li><?= __('admin.download_demo_files'); ?>
                                <ul>
                                    <li><span class="badge bg-secondary"><?= __('admin.download_order_demo'); ?></span></li>
                                    <li><span class="badge bg-secondary"><?= __('admin.download_postback_demo'); ?></span></li>
                                </ul>
                            </li>
                            <li><?= __('admin.extract_demo_files'); ?> <span class="badge bg-warning"><?= __('admin.extract_zip_files_notice'); ?></span></li>
                            <li><?= __('admin.save_demo_campaign'); ?></li>
                            <li><?= __('admin.place_demo_files_on_server'); ?></li>
                            <li><?= __('admin.open_affiliate_link'); ?></li>
                            <li><?= __('admin.view_order_form_links'); ?>
                                <ol>
                                    <li><?= __('admin.affiliate_campaign_link_instruction'); ?></li>
                                    <li><?= __('admin.integration_base_url_instruction'); ?></li>
                                    <li><?= __('admin.campaign_postback_url_instruction'); ?></li>
                                </ol>
                            </li>
                            <li><?= __('admin.complete_setup_instructions'); ?></li>
                            <li><?= __('admin.process_postback_button'); ?></li>
                            <li><?= __('admin.verify_results'); ?>
                                <ul>
                                    <li><?= __('admin.check_wallet_transactions'); ?></li>
                                    <li><?= __('admin.view_order_details'); ?></li>
                                    <li><?= __('admin.view_logs_details'); ?></li>
                                    <li><?= __('admin.verify_response_on_demo_page'); ?>
                                        <ul>
                                            <li><span class="badge bg-success"><?= __('admin.http_status_200'); ?></span></li>
                                            <li><span class="badge bg-success"><?= __('admin.response_oks_off'); ?></span></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><?= __('admin.test_again_instructions'); ?>
                                <ul>
                                    <li><span class="badge bg-info"><?= __('admin.back_to_order_demo_button'); ?></span></li>
                                </ul>
                            </li>
                        </ol>
                        <p class="mt-4"><strong><?= __('admin.note'); ?></strong> <?= __('admin.ensure_dynamic_static_params_correct'); ?></p>
</div>
</div>
</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close'); ?></button>
</div>
</div>
</div>
</div>

				</div><!-- /#postback-setting -->

				<!-- conversion api -->
				<div class="tab-pane col-sm-12 fade" id="conversion_api" role="tabpanel" aria-labelledby="conversion_api-tab">

<!-- Quick Reference Card -->
<div class="card bg-light border-0 mb-4">
  <div class="card-body">
    <h6 class="fw-bold mb-3"><i class="fas fa-bookmarks me-2"></i><?= __('user.conv_api_quick_ref') ?></h6>
    <div class="row g-2">
      <div class="col-md-3 col-sm-6">
        <div class="card border h-100">
          <div class="card-body py-2 px-3 text-center">
            <span class="badge bg-primary rounded-pill mb-1">POST</span>
            <p class="fw-bold small mb-1"><?= __('user.conv_api_click_title') ?></p>
            <code class="small">/integration/addClick</code>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border h-100">
          <div class="card-body py-2 px-3 text-center">
            <span class="badge bg-success rounded-pill mb-1">POST</span>
            <p class="fw-bold small mb-1"><?= __('user.conv_api_action_title') ?></p>
            <code class="small">/integration/addClick</code>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border h-100">
          <div class="card-body py-2 px-3 text-center">
            <span class="badge bg-warning text-dark rounded-pill mb-1">POST</span>
            <p class="fw-bold small mb-1"><?= __('user.conv_api_order_title') ?></p>
            <code class="small">/integration/addOrder</code>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border h-100">
          <div class="card-body py-2 px-3 text-center">
            <span class="badge bg-info text-dark rounded-pill mb-1">POST</span>
            <p class="fw-bold small mb-1"><?= __('user.conv_api_order_click_title') ?></p>
            <code class="small">/integration/addClick</code>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Accordion -->
<div class="accordion" id="vConvApiAccordion">

  <!-- 1. Click Conversion API -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="vConvApiHead1">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#vConvApiBody1" aria-expanded="true" aria-controls="vConvApiBody1">
        <span class="badge bg-primary me-2">POST</span> <?= __('user.conv_api_click_title') ?>
        <code class="ms-2 small text-muted"><?php echo base_url('integration/addClick'); ?></code>
      </button>
    </h2>
    <div id="vConvApiBody1" class="accordion-collapse collapse" aria-labelledby="vConvApiHead1" data-bs-parent="#vConvApiAccordion">
      <div class="accordion-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead class="table-light"><tr><th><?= __('user.parameter') ?></th><th><?= __('user.type') ?></th><th><?= __('user.value') ?></th><th><?= __('user.description') ?></th></tr></thead>
                <tbody>
                  <tr><td><code>page_name</code></td><td><code>string</code></td><td><code>vendor_click</code></td><td><?= __('user.get_general_code_desc') ?></td></tr>
                  <tr><td><code>customFields</code></td><td><code>json array</code></td><td><code>[{"city":"cityName"},...]</code></td><td><?= __('user.optional_value') ?></td></tr>
                  <tr><td><code>base_url</code></td><td><code>string</code></td><td><code>target url</code></td><td><?= __('user.get_target_link_desc') ?></td></tr>
                  <tr><td><code>current_page_url</code></td><td><code>string</code></td><td><code>page url</code></td><td><?= __('user.get_current_page_url_desc') ?></td></tr>
                  <tr><td><code>af_id</code></td><td><code>string</code></td><td><code>affiliate id</code></td><td><?= __('user.affiliate_id_desc') ?></td></tr>
                  <tr><td><code>script_name</code></td><td><code>string</code></td><td><code>general_integration</code></td><td>-</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-semibold small mb-0"><i class="fas fa-code me-1"></i><?= __('user.conv_api_php_example') ?></h6>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code"><i class="fas fa-clipboard me-1"></i><?= __('user.conv_api_copy_code') ?></button>
            </div>
            <pre class="bg-dark text-white rounded p-3 small" style="max-height:320px;overflow-y:auto;">
$page_name="vendor_click";
$customFields= '[{"city":"cityName"},{"countryName":"countryName"}]';
$current_page_url= "http://example.com/callapi.php"; 
$base_url = "http://localhost/aff/client/site.php";
$af_id = "NzdtSnkyMklYTWlXU1hIMDhCdkcydz09-Mi0yMA==";
$script_name = "general_integration";

$postData = [];
$current_page_url = base64_encode($current_page_url);
$base_url = base64_encode($base_url);

$postData['page_name'] = $page_name; 
$postData['customFields'] = $customFields; 
$postData['current_page_url'] = $current_page_url; 
$postData['base_url'] = $base_url; 
$postData['af_id'] = $af_id; 
$postData['script_name'] = $script_name;

$url='<?php echo base_url("integration/addClick");?>';
$curl = curl_init($url);
$request = http_build_query($postData);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($curl);

// $response => "OK" if success</pre>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Action Conversion API -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="vConvApiHead2">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vConvApiBody2" aria-expanded="false" aria-controls="vConvApiBody2">
        <span class="badge bg-success me-2">POST</span> <?= __('user.conv_api_action_title') ?>
        <code class="ms-2 small text-muted"><?php echo base_url('integration/addClick'); ?></code>
      </button>
    </h2>
    <div id="vConvApiBody2" class="accordion-collapse collapse" aria-labelledby="vConvApiHead2" data-bs-parent="#vConvApiAccordion">
      <div class="accordion-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead class="table-light"><tr><th><?= __('user.parameter') ?></th><th><?= __('user.type') ?></th><th><?= __('user.value') ?></th><th><?= __('user.description') ?></th></tr></thead>
                <tbody>
                  <tr><td><code>actionCode</code></td><td><code>string</code></td><td><code>vendor_action</code></td><td><?= __('user.get_action_code_desc') ?></td></tr>
                  <tr><td><code>customFields</code></td><td><code>json array</code></td><td><code>[{"city":"cityName"},...]</code></td><td>-</td></tr>
                  <tr><td><code>base_url</code></td><td><code>string</code></td><td><code>target url</code></td><td><?= __('user.get_target_link_desc') ?></td></tr>
                  <tr><td><code>current_page_url</code></td><td><code>string</code></td><td><code>page url</code></td><td><?= __('user.get_current_page_url_desc') ?></td></tr>
                  <tr><td><code>af_id</code></td><td><code>string</code></td><td><code>affiliate Id</code></td><td><?= __('user.affiliate_id_desc') ?></td></tr>
                  <tr><td><code>script_name</code></td><td><code>string</code></td><td><code>general_integration</code></td><td>-</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-semibold small mb-0"><i class="fas fa-code me-1"></i><?= __('user.conv_api_php_example') ?></h6>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code"><i class="fas fa-clipboard me-1"></i><?= __('user.conv_api_copy_code') ?></button>
            </div>
            <pre class="bg-dark text-white rounded p-3 small" style="max-height:320px;overflow-y:auto;">
$actioncode="vendor_action";
$customFields= '[{"city":"cityName"},{"countryName":"countryName"}]';
$current_page_url= "http://example.com/callapi.php"; 
$base_url = "http://localhost/aff/client/site.php";
$af_id = "NzdtSnkyMklYTWlXU1hIMDhCdkcydz09-Mi0yMA==";
$script_name = "general_integration";

$postData = [];
$current_page_url = base64_encode($current_page_url);
$base_url = base64_encode($base_url);

$postData['actionCode'] = $actioncode; 
$postData['customFields'] = $customFields; 
$postData['current_page_url'] = $current_page_url; 
$postData['base_url'] = $base_url; 
$postData['af_id'] = $af_id; 
$postData['script_name'] = $script_name;

$url='<?php echo base_url("integration/addClick");?>';
$curl = curl_init($url);
$request = http_build_query($postData);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($curl);

// $response => "OK" if success</pre>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Order Conversion API -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="vConvApiHead3">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vConvApiBody3" aria-expanded="false" aria-controls="vConvApiBody3">
        <span class="badge bg-warning text-dark me-2">POST</span> <?= __('user.conv_api_order_title') ?>
        <code class="ms-2 small text-muted"><?php echo base_url('integration/addOrder'); ?></code>
      </button>
    </h2>
    <div id="vConvApiBody3" class="accordion-collapse collapse" aria-labelledby="vConvApiHead3" data-bs-parent="#vConvApiAccordion">
      <div class="accordion-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead class="table-light"><tr><th><?= __('user.parameter') ?></th><th><?= __('user.type') ?></th><th><?= __('user.required') ?></th><th><?= __('user.description') ?></th></tr></thead>
                <tbody>
                  <tr><td><code>product_ids</code></td><td><code>integer</code></td><td><code>product id</code></td><td>-</td></tr>
                  <tr><td><code>order_id</code></td><td><code>integer</code></td><td><code>order number</code></td><td>-</td></tr>
                  <tr><td><code>order_currency</code></td><td><code>string</code></td><td><code>USD, INR</code></td><td>-</td></tr>
                  <tr><td><code>order_total</code></td><td><code>decimal</code></td><td><code>order total</code></td><td>-</td></tr>
                  <tr><td><code>customFields</code></td><td><code>json array</code></td><td><code>[{"city":"cityName"},...]</code></td><td>-</td></tr>
                  <tr><td><code>base_url</code></td><td><code>string</code></td><td><code>target url</code></td><td><?= __('user.get_target_link_desc') ?></td></tr>
                  <tr><td><code>current_page_url</code></td><td><code>string</code></td><td><code>page url</code></td><td><?= __('user.get_current_page_url_desc') ?></td></tr>
                  <tr><td><code>af_id</code></td><td><code>string</code></td><td><code>affiliate Id</code></td><td><?= __('user.affiliate_id_desc') ?></td></tr>
                  <tr><td><code>script_name</code></td><td><code>string</code></td><td><code>general_integration</code></td><td>-</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-semibold small mb-0"><i class="fas fa-code me-1"></i><?= __('user.conv_api_php_example') ?></h6>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code"><i class="fas fa-clipboard me-1"></i><?= __('user.conv_api_copy_code') ?></button>
            </div>
            <pre class="bg-dark text-white rounded p-3 small" style="max-height:320px;overflow-y:auto;">
$customFields= '[{"city":"cityName"},{"countryName":"countryName"}]';
$current_page_url= "http://example.com/callapi.php"; 
$base_url = "http://localhost/aff/client/site.php"; 
$af_id = "NzdtSnkyMklYTWlXU1hIMDhCdkcydz09-Mi0yMA==";
$script_name = "general_integration";

$postData = [];
$current_page_url = base64_encode($current_page_url);
$base_url = base64_encode($base_url);

$postData['product_ids'] = 101; 
$postData['order_id'] = 1200; 
$postData['order_total'] = 120; 
$postData['order_currency'] = 'USD';  
$postData['customFields'] = $customFields; 
$postData['current_page_url'] = $current_page_url; 
$postData['base_url'] = $base_url; 
$postData['af_id'] = $af_id; 
$postData['script_name'] = $script_name;

$url='<?= base_url("integration/addOrder");?>';
$curl = curl_init($url);
$request = http_build_query($postData);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($curl);

// $response => "OKS-OFF" if success</pre>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Order Product Click Conversion API -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="vConvApiHead4">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vConvApiBody4" aria-expanded="false" aria-controls="vConvApiBody4">
        <span class="badge bg-info text-dark me-2">POST</span> <?= __('user.conv_api_order_click_title') ?>
        <code class="ms-2 small text-muted"><?php echo base_url('integration/addClick'); ?></code>
      </button>
    </h2>
    <div id="vConvApiBody4" class="accordion-collapse collapse" aria-labelledby="vConvApiHead4" data-bs-parent="#vConvApiAccordion">
      <div class="accordion-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead class="table-light"><tr><th><?= __('user.parameter') ?></th><th><?= __('user.type') ?></th><th><?= __('user.value') ?></th><th><?= __('user.description') ?></th></tr></thead>
                <tbody>
                  <tr><td><code>product_id</code></td><td><code>string</code></td><td><code>ProductID</code></td><td><?= __('user.pass_static_product_id') ?></td></tr>
                  <tr><td><code>customFields</code></td><td><code>json array</code></td><td><code>[{"city":"cityName"},...]</code></td><td>-</td></tr>
                  <tr><td><code>base_url</code></td><td><code>string</code></td><td><code>target url</code></td><td><?= __('user.get_target_link_desc') ?></td></tr>
                  <tr><td><code>current_page_url</code></td><td><code>string</code></td><td><code>page url</code></td><td><?= __('user.get_current_page_url_desc') ?></td></tr>
                  <tr><td><code>af_id</code></td><td><code>string</code></td><td><code>affiliate Id</code></td><td><?= __('user.affiliate_id_desc') ?></td></tr>
                  <tr><td><code>script_name</code></td><td><code>string</code></td><td><code>general_integration</code></td><td>-</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-semibold small mb-0"><i class="fas fa-code me-1"></i><?= __('user.conv_api_php_example') ?></h6>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code"><i class="fas fa-clipboard me-1"></i><?= __('user.conv_api_copy_code') ?></button>
            </div>
            <pre class="bg-dark text-white rounded p-3 small" style="max-height:320px;overflow-y:auto;">
$product_id="ProductID";
$customFields= '[{"city":"cityName"},{"countryName":"countryName"}]';
$current_page_url= "http://example.com/callapi.php"; 
$base_url = "http://localhost/aff/client/site.php";
$af_id = "NzdtSnkyMklYTWlXU1hIMDhCdkcydz09-Mi0yMA==";
$script_name = "general_integration";

$postData = [];
$current_page_url = base64_encode($current_page_url);
$base_url = base64_encode($base_url);

$postData['product_id'] = $product_id; 
$postData['customFields'] = $customFields; 
$postData['current_page_url'] = $current_page_url; 
$postData['base_url'] = $base_url; 
$postData['af_id'] = $af_id; 
$postData['script_name'] = $script_name;

$url='<?php echo base_url("integration/addClick");?>';
$curl = curl_init($url);
$request = http_build_query($postData);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($curl);

// $response => "OK" if success</pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="alert alert-light border d-flex align-items-center mt-3 mb-0" role="alert">
  <i class="fas fa-info-circle text-primary me-2 fs-5"></i>
  <span><?= __('user.conv_api_accordion_hint') ?></span>
</div>

<script>
$(document).on("click", "#vConvApiAccordion .btn-copy-code", function(){
  var pre = $(this).closest(".col-lg-6").find("pre");
  var text = pre.text();
  if (navigator.clipboard) { navigator.clipboard.writeText(text); }
  else { var ta = $("<textarea>").val(text).appendTo("body").select(); document.execCommand("copy"); ta.remove(); }
  var btn = $(this);
  btn.html('<i class="fas fa-check me-1"></i>Copied!');
  setTimeout(function(){ btn.html('<i class="fas fa-clipboard me-1"></i><?php echo __("user.conv_api_copy_code"); ?>'); }, 2000);
});
</script>

				</div><!-- /#conversion_api -->

				<div class="tab-pane col-sm-12 fade" id="menu1" role="tabpanel" aria-labelledby="menu1-tab">
					<div class="form-group">
						<label class="control-label"><?=  __('user.commission_type') ?> </label>
						<select class="form-control" name="commission_type">
							<option <?= (isset($tool) && $tool['commission_type'] == 'default') ? 'selected' : '' ?> value="default" ><?=  __('user.default') ?></option>
							<option <?= (isset($tool) && $tool['commission_type'] == 'custom') ? 'selected' : '' ?> value="custom"><?=  __('user.custom') ?></option>
							<option value="disabled" <?= (isset($tool) && $tool['commission_type'] == 'disabled') ? 'selected' : '' ?>><?= __('admin.disabled') ?></option>
						</select>
</div>

					<div class="default-mlm"  <?= ($tool['commission_type'] != 'custom' && $tool['commission_type'] != 'disabled') ? '' : 'style="display:none;"' ?>>
						<div class="table-responsive table-container">
							<table class="table" id="tbl_refer_level">
								<thead>
									<tr>
										<th style="vertical-align: top; border-right: 1px solid lightgrey;"><?= __('user.level_mlm') ?></th>
										<th style="vertical-align: top; border-right: 1px solid lightgrey; text-align: center;">
											<?= __('user.cps_cost') ?><br>
											<?php if ($default['referlevel']['sale_type'] == 'percentage'): ?>
												<span class="form-control"><?= __('user.percentage') ?></span>
											<?php endif ?>
											<?php if ($default['referlevel']['sale_type'] == 'fixed'): ?>
												<span class="form-control"><?= __('user.fixed') ?></span>
											<?php endif ?>
										</th>
										<th style="vertical-align: top; border-right: 1px solid lightgrey; text-align: center;" colspan="2"><?= __('admin.clicks_count') ?> &amp; <?= __('user.cpc_cost') ?></th>
										<th style="vertical-align: top; text-align: center;"><?= __('user.cpa_cost') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php $default_levels = isset($default['referlevel']['levels']) ? (int)$default['referlevel']['levels'] : 3;
									for ($level =1; $level <= $default_levels; $level++) { ?>
										<tr>
											<td style="border-right: 0.1px solid lightgrey;"><?= $level ?></td>
											<td style="border-right: 0.1px solid lightgrey;">
												<div class="input-group">
													<span class="form-control"><?php echo $default['referlevel_'.$level]['sale_commition'] ?></span>
													<div class="input-group-append"><span class="input-group-text refer-symball"></span></div>
</div>
											</td>
											<td><span class="form-control"><?php echo $default['referlevel_'.$level]['commition'] ?></span>
												<td style="border-right: 0.1px solid lightgrey;">
													<div class="input-group">
														<span class="form-control"><?php echo $default['referlevel_'.$level]['ex_commition'] ?></span>
														<div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
</div>
												</td>
												<td>
													<div class="input-group">
														<span class="form-control"><?php echo $default['referlevel_'.$level]['ex_action_commition'] ?></span>
														<div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
</div>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
</div>
</div>

						<div class="commi-cube" <?= ($tool['commission_type'] != 'custom') ? 'style="display:none;"' : '' ?>>
							<div class="new-comm">
								<div class="form-group">
									<label class="control-label"><?= __('admin.refer_level') ?></label>
									<select class="form-control" id="referlevel_select" name="referlevel[levels]">
										<option <?= $levels == "1" ? 'selected' : '' ?> value="1">1</option>
										<option <?= $levels == "2" ? 'selected' : '' ?> value="2">2</option>
										<option <?= $levels == "3" ? 'selected' : '' ?> value="3">3</option>
										<option <?= $levels == "4" ? 'selected' : '' ?> value="4">4</option>
										<option <?= $levels == "5" ? 'selected' : '' ?> value="5">5</option>
										<option <?= $levels == "6" ? 'selected' : '' ?> value="6">6</option>
										<option <?= $levels == "7" ? 'selected' : '' ?> value="7">7</option>
										<option <?= $levels == "8" ? 'selected' : '' ?> value="8">8</option>
										<option <?= $levels == "9" ? 'selected' : '' ?> value="9">9</option>
										<option <?= $levels == "10" ? 'selected' : '' ?> value="10">10</option>
										<option <?= $levels == "11" ? 'selected' : '' ?> value="11">11</option>
										<option <?= $levels == "12" ? 'selected' : '' ?> value="12">12</option>
										<option <?= $levels == "13" ? 'selected' : '' ?> value="13">13</option>
										<option <?= $levels == "14" ? 'selected' : '' ?> value="14">14</option>
										<option <?= $levels == "15" ? 'selected' : '' ?> value="15">15</option>
										<option <?= $levels == "16" ? 'selected' : '' ?> value="16">16</option>
										<option <?= $levels == "17" ? 'selected' : '' ?> value="17">17</option>
										<option <?= $levels == "18" ? 'selected' : '' ?> value="18">18</option>
										<option <?= $levels == "19" ? 'selected' : '' ?> value="19">19</option>
										<option <?= $levels == "20" ? 'selected' : '' ?> value="20">20</option>
									</select>
								</div>
								<div class="table-responsive table-container">
									<table class="table" id="tbl_refer_level">
										<thead>
											<tr>
												<th style="vertical-align: top; border-right: 1px solid lightgrey;"><?= __('admin.level_mlm') ?></th>
												<th style="vertical-align: top; border-right: 1px solid lightgrey; text-align: center;">
													<?= __('admin.cps_cost') ?><br>
													<select class="form-control refer-symball-select w-100 mt-2" name="referlevel[sale_type]">
														<option symbal='%' <?php if($referlevel['sale_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.percentage') ?></option>
														<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['sale_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed"><?= __('admin.fixed') ?></option>
													</select>
												</th>
												<th style="vertical-align: top; border-right: 1px solid lightgrey; text-align: center;" colspan="2"><?= __('admin.clicks_count') ?> &amp; <?= __('admin.cpc_cost') ?></th>
												<th style="vertical-align: top; text-align: center;"><?= __('admin.cpa_cost') ?></th>
											</tr>
										</thead>
										<tbody>
											<?php for ($level=1; $level <= $levels; $level++) { ?>
												<tr>
													<td><?= $level ?></td>
													<td style="border-right: 0.1px solid lightgrey;">
														<div class="input-group">
															<input type="number" step="any" name="referlevel_<?= $level ?>[sale_commition]" value="<?php echo ${"referlevel_". $level}['sale_commition'] ?>" class="form-control" />
															<div class="input-group-append"><span class="input-group-text refer-symball"></span></div>
</div>
													</td>
													<td><input type="number" step="any" name="referlevel_<?= $level ?>[commition]" value="<?php echo ${"referlevel_". $level}['commition'] ?>" class="form-control" /></td>
													<td style="border-right: 0.1px solid lightgrey;">
														<div class="input-group">
															<input type="number" step="any" name="referlevel_<?= $level ?>[ex_commition]" value="<?php echo ${"referlevel_". $level}['ex_commition'] ?>" class="form-control" />
															<div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
</div>
													</td>
													<td>
														<div class="input-group">
															<input type="number" step="any" name="referlevel_<?= $level ?>[ex_action_commition]" value="<?php echo ${"referlevel_". $level}['ex_action_commition'] ?>" class="form-control" />
															<div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
</div>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
</div>
</div>
</div>
</div>
</div>
			</form>	
</div>

		<div class="card-footer text-right">
			<?php if(isset($tool['id'])){ ?>
				<a class="get-code btn btn-info" href="javascript:void(0)" data-id="<?= $tool['id'] ?>"><?= __('user.get_code') ?></a>
			<?php } ?>
			<!-- <button class="btn btn-primary btn-save save-n-close"><span class="loading-submit"></span> <?= __('user.save') ?></button> -->
			<button class="btn btn-primary btn-save "><span class="loading-submit"></span> <?= __('user.save_and_close') ?></button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="integration-code">
<div class="modal-dialog">
	<div class="modal-content"></div>
</div>
</div>

<div class="modal fade" id="addProgram">
<div class="modal-dialog modal-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title mt-0"><?= __('user.add_program') ?></h4>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
		<div class="modal-body">
			<form action="" method="post">
				<input type="hidden" name="add_program_to_form" value="1">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label class="control-label"><?= __('admin.program_name') ?></label>
							<input class="form-control" name="name" type="text">
</div>

						<fieldset class="custom-design mb-2">
							<legend><?= __('user.admin_commission') ?></legend>
							<?php 
							$programs['admin_click_status'] = $market_vendor['click_status'];
							$programs['admin_commission_click_commission'] = $market_vendor['commission_click_commission'];
							$programs['admin_commission_number_of_click'] = $market_vendor['commission_number_of_click'];
							$programs['admin_sale_status'] = $market_vendor['sale_status'];
							$programs['admin_commission_type'] = $market_vendor['commission_type'];
							$programs['admin_commission_sale'] = $market_vendor['commission_sale'];
							?>
							<div class="row">
								<div class="col">
									<div class="form-group mb-1">
										<label class="control-label"><?= __('user.click_commission') ?> : </label> 
										<?php if($programs['admin_click_status']){ ?>
											<span><?= c_format($programs['admin_commission_click_commission']) ?> <?= __('user.per') ?> <?= (int)$programs['admin_commission_number_of_click'] ?> <?= __('user.clicks') ?></span>
										<?php } else {?>
											<span><?= __('user.disabled') ?></span>
										<?php } ?>
</div>
</div>
								<div class="col">
									<div class="form-group mb-1">
										<label class="control-label"><?= __('user.sale_commission') ?> : </label> 
										<?php if($programs['admin_sale_status']){ ?>
											<span> 
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
											<span><?= __('user.disabled') ?></span>
										<?php } ?>
</div>
</div>
</div>
						</fieldset>


</div>
					<div class="col">
						<div class="card mt-3">
							<div class="card-header "><p class="m-0"><?= __('user.vendor_commnts') ?></p></div>
							<div class="card-body chat-card">
								<div class="bg-white form-group m-0 p-2">
									<textarea class="form-control" placeholder="<?= __('user.enter_message_and_save_program_to_send') ?>" name="comment"></textarea>
</div>

</div>
</div>
</div>
</div>

				<div class="row">
					<div class="col-sm-6">
						<div class="custom-card card">
							<div class="card-header"><p class="text-center"><?= __('admin.other_affiliate_sale_settings') ?></p></div>
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?= __('user.commission_type') ?></label>
											<select name="commission_type" class="form-control">
												<option value=""><?= __('admin.select_product_commission_type') ?></option>
												<option value="percentage"><?= __('user.percentage') ?></option>
												<option value="fixed"><?= __('user.fixed') ?></option>
											</select>
</div>
</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?= __('admin.commission_for_sale') ?> </label>
											<input class="form-control only-number-allow" name="commission_sale" type="text">
</div>
</div>
</div>

								<div class="form-group">
									<label class="control-label"><?= __('admin.sale_status') ?></label>
									<div>
										<div class="radio radio-inline"> 
											<label> 
												<input type="radio" checked="" name="sale_status" value="0"> <?= __('admin.disable') ?> 
											</label> 
</div>
										<div class="radio radio-inline"> 
											<label> 
												<input type="radio" name="sale_status" value="1"> <?= __('admin.enable') ?> 
											</label> 
</div>
</div>
</div>
</div>
</div>
</div>

					<div class="col-sm-6">
						<div class="custom-card card">
							<div class="card-header"><p class="text-center"><?= __('admin.other_affiliate_click_settings') ?></p></div>
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="control-label"><?= __('user.clicks_allow') ?></label>
											<select name="click_allow" class="form-control">
												<option value="multiple"><?= __('user.allow_multi_clicks') ?></option>
												<option value="single"><?= __('user.allow_single_click') ?></option>
											</select>
</div>
</div>

									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?= __('admin.number_of_click') ?></label>
											<input class="form-control only-number-allow" name="commission_number_of_click" type="text">
</div>
</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?= __('admin.amount_per_click') ?></label>
											<input class="form-control only-number-allow" name="commission_click_commission" type="text">
</div>
</div>
</div>


								<div class="form-group">
									<label class="control-label"><?= __('admin.click_status') ?></label>
									<div>
										<div class="radio radio-inline"> 
											<label> 
												<input type="radio" checked="" name="click_status" value="0"> <?= __('admin.disable') ?>
											</label>
</div>
										<div class="radio radio-inline"> 
											<label> 
												<input type="radio" name="click_status" value="1"> <?= __('admin.enable') ?> 
											</label> 
</div>
</div>
</div>
</div>
</div>
</div>
</div>
			</form>
</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary addProgramToFrom"><?= __('user.save_close') ?></button>
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('user.footer_close') ?></button>
</div>
</div>
</div>
</div>

<script type="text/javascript">

$('.datetime-picker').datetimepicker({
	format:'d-m-Y H:i',
});

render_tool_period_inputs();

$(document).on('change', 'select[name="tool_period"]', render_tool_period_inputs);

function render_tool_period_inputs(){
	var tool_period = $('select[name="tool_period"]').val();

	if( tool_period == 1){
		$('#start_date_input').hide();
		$('#end_date_input').hide();
	}else if (tool_period == 2){
		$('#start_date_input').hide();
		$('#end_date_input').show();
	} else if (tool_period == 3) {
		$('#start_date_input').show();
		$('#end_date_input').hide();
	} else {
		$('#start_date_input').show();
		$('#end_date_input').show();			
	}

	$('#endtime').datetimepicker({
		format:'d-m-Y H:i',
		inline:true,
	});

	$('.datetime-picker').datetimepicker({
		format:'d-m-Y H:i',
	});
};

$('#endtime').datetimepicker({
	format:'d-m-Y H:i',
	inline:true,
});

$('#setCustomTime').on('change', function(){
	$(".custom_time_container").hide();
	if($(this).prop("checked")){
		$(".custom_time_container").show();
	}
});

$("select[name=commission_type]").on('change',function(){
	if($(this).val() == 'custom'){
		$(".default-mlm").hide();
		$(".commi-cube").show();
	} else if($(this).val() == 'default'){
		$(".commi-cube").hide();
		$(".default-mlm").show();
	} else {
		$(".commi-cube").hide();
		$(".default-mlm").hide();
	}
})

function chnage_teigger() {
	var symbal = $(".refer-symball-select").find("option:selected").attr("symbal");
	$(".refer-symball").html(symbal);
}
$(".refer-symball-select").change(chnage_teigger)
chnage_teigger();

$('[name="tool_type"]').on('change',function(){

	$(".for-action-tool, .for-program-tool, .for-general_click-tool").hide();
	var click_value = "<?= isset($tool) ? $tool['action_click'] : '' ?>";
	let type = $(this).val();
	if(type == 'single_action'){
		$('.for-action-tool [name="action_click"]').val(1);	
		$('.for-action-tool [name="action_click"]').attr('readonly', 'readonly');	
		$(".for-action-tool").show();	
	}else if(type == 'action'){
		$('.for-action-tool [name="action_click"]').val(click_value);	
		$('.for-action-tool [name="action_click"]').removeAttr('readonly');	
		$(".for-action-tool").show();
	}else{
		$(".for-"+ $(this).val() +"-tool").show();
	}

	if(type != 'program'){
		$('[name="tool_integration_plugin"]').val("");
	}

	rendeCampignDefaultImages();
});

$('[name="tool_integration_plugin"]').on('change',function(){
	rendeCampignDefaultImages();
	handleStripeFields();
});

function handleStripeFields() {
	var selectedPlugin = $('[name="tool_integration_plugin"]').val();
	if (selectedPlugin === 'stripe') {
		$('#stripe-fields-card').show();
		$('input[name="target_link"]').prop('readonly', true).val('#');
	} else {
		$('#stripe-fields-card').hide();
		$('input[name="target_link"]').prop('readonly', false);
		if ($('input[name="target_link"]').val() === '#') {
			$('input[name="target_link"]').val('');
		}
	}
}

function rendeCampignDefaultImages() {
	let type = $('[name="tool_type"]').val();

	let featured_image = 'no_product_image.png';

	if(type == 'single_action' || type == 'action'){
		featured_image = 'plugins_icons/action.jpg';
	} else if(type == 'general_click') {
		featured_image = 'plugins_icons/click.jpg';
	} else if(type == 'program'){

		let program = $('[name="tool_integration_plugin"]').val();
		switch (program){
			case 'woocommerce':
			featured_image = 'plugins_icons/woo.png';
			break;
			case 'prestashop':
			featured_image = 'plugins_icons/prestashop.png';
			break;
			case 'opencart':
			featured_image = 'plugins_icons/opencart.png';
			break;
			case 'magento':
			featured_image = 'plugins_icons/magento.png';
			break;
			case 'shopify':
			featured_image = 'plugins_icons/shopify.png';
			break;
			case 'bigcommerce':
			featured_image = 'plugins_icons/Big-Commerce.jpg';
			break;
			case 'paypal':
			featured_image = 'plugins_icons/paypal.png';
			break;
			case 'oscommerce':
			featured_image = 'plugins_icons/oscommerce.png';
			break;
			case 'zencart':
			featured_image = 'plugins_icons/zencart.png';
			break;
			case 'xcart':
			featured_image = 'plugins_icons/xcart.png';
			break;
			case 'laravel':
			featured_image = 'plugins_icons/laravel.png';
			break;
			case 'cakephp':
			featured_image = 'plugins_icons/cackphp.png';
			break;
		case 'codeigniter':
		featured_image = 'plugins_icons/codeigniter.png';
		break;
		case 'stripe':
		featured_image = 'plugins_icons/stripe.png';
		break;
		default:
		featured_image = 'plugins_icons/order.jpg';
	}

}

$('.campaign_default_image').attr('src', '<?= base_url('assets/images/')?>'+featured_image);

	var image = new Image();
	image.src = '<?= base_url('assets/images/')?>'+featured_image;
	$(image).one('load',function(){
		var width = image.width;
		var height = image.height;
		var sizeText = width + 'x' + height;
		$('.campaign_default_image').closest('tr').find('.size-input').val(sizeText);
		$('.campaign_default_image').closest('tr').find('.size-display').text(sizeText);
	});
}


$('[name="tool_type"]').trigger("change");
handleStripeFields();

$("#addProgram .addProgramToFrom").on('click',function(){
	$this = $("#addProgram form");

	$.ajax({
		url:'<?= base_url('usercontrol/editProgram') ?>',
		type:'POST',
		dataType:'json',
		data:$this.serialize(),
		success:function(result){
			$this.find(".has-error").removeClass("has-error");
			$this.find("span.text-danger").remove();

			if(result['message']){
				if(result['newOption'])
					$("select[name='program_id']").append(result['newOption']);

				$this[0].reset();

				alert(result['message']);
				$("#addProgram").modal('hide');
			} else {
				if(result['errors']){
					$.each(result['errors'], function(i,j){
						$ele = $this.find('[name="'+ i +'"]');
						if($ele){
							$ele.parents(".form-group").addClass("has-error");
							$ele.after("<span class='text-danger'>"+ j +"</span>");
						}
					});
				}
			}
		},
	})
})

$(".parse-video").on('keyup',function(){
	var url = $(this).val();
	url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

	if (RegExp.$3.indexOf('youtu') > -1) {
		var type = 'Youtube';
	} else if (RegExp.$3.indexOf('vimeo') > -1) {
		var type = 'Vimeo';
	}

	$(".video-priview").val(type);
})
$(".parse-video").trigger("keyup");


$(".add-banner").on('click',function(){
	if($(".banner-table tbody tr").length < 50){

		var $newRow = $('<tr>\
			<td>\
			<img class="img-thumbnail campaign_default_image integration_css-banner-thumb h-auto flex-shrink-0" src="<?= base_url('assets/images/no_product_image.png'); ?>">\
			<div class="mt-2">\
				<input type="file" accept="image/*" class="form-control form-control-sm file-input" name="custom_banner[]">\
				<small class="text-muted"><?= __('user.select_banner_image') ?></small>\
			</div>\
			<input type="hidden" name="keep_ads[]" value="0">\
			</td>\
			<td class="text-center align-middle"><span class="badge bg-secondary size-display"></span><input type="hidden" class="size-input" name="custom_banner_size[]"></td>\
			<td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-custom-image"><i class="fa fa-trash"></i></button></td>\
			</tr>');
		$(".banner-table tbody").append($newRow);
	}

	if($(".banner-table tbody tr").length >= 50){
		$(".add-banner").hide();
	}

	rendeCampignDefaultImages();
})

$(".banner-table tbody").delegate(".remove-custom-image","click",function(){
	if(!confirm('<?= __('user.are_you_sure') ?>')) return false;

	$(".add-banner").show();
	$(this).parents("tr").remove();
})

$(".banner-table tbody").delegate(".file-input","change",function(){
	var input = this;
	$this = $(this);

	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
			$tr = $this.parents("tr");
			var img = new Image;

			img.onload = function() {
				var sizeText = img.width + " x " + img.height;
				$tr.find(".size-input").val(sizeText);
				$tr.find(".size-display").text(sizeText);
			};
			img.src = e.target.result;
			$tr.find("img").attr('src', e.target.result).removeClass('campaign_default_image');
			$tr.find("[name=keep_ads]").val('0');
		}

		reader.readAsDataURL(input.files[0]);
	}
});


$(".btn-save").on('click',function(){
	$btn = $(this);
	$this = $("#form_tools");

	var formData = new FormData($this[0]);
	if($(this).hasClass('save-n-close')){
		formData.append("save_close",true);
	}
	formData = formDataFilter(formData);
	$btn.prop("disabled",true);


	$.ajax({
		url:'<?= base_url('usercontrol/integration_tools_form_post') ?>',
		type:'POST',
		dataType:'json',
		cache:false,
		contentType: false,
		processData: false,
		data:formData,
		xhr: function (){
			var jqXHR = null;

			if ( window.ActiveXObject ){
				jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
			}else {
				jqXHR = new window.XMLHttpRequest();
			}

			jqXHR.upload.addEventListener( "progress", function ( evt ){
				if ( evt.lengthComputable ){
					var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
					$btn.find('.loading-submit').text(percentComplete + "%").show();
				}
			}, false );

			jqXHR.addEventListener( "progress", function ( evt ){
				if ( evt.lengthComputable ){
					var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
					$btn.find('.loading-submit').hide();
				}
			}, false );
			return jqXHR;
		},
		error:function(){
			$btn.find('.loading-submit').hide();
			$btn.prop("disabled",false);
		},
		success:function(result){
			$btn.find('.loading-submit').hide();
			$btn.prop("disabled",false);
			$this.find(".has-error").removeClass("has-error");
			$this.find("span.text-danger").remove();

		if(result['location']){ window.location = result['location']; }

		if(result['errors'] && result['errors']['stripe']){
			$('#stripe-error-message').text(result['errors']['stripe']);
			$('#stripe-error-alert').removeClass('d-none');
		} else {
			$('#stripe-error-alert').addClass('d-none');
		}

		if(result['errors']){
			$.each(result['errors'], function(i,j){
					if(i == 'custom_banner[]') {
						$.each(j, function(key,err){
							$ele = $('input[name="'+ i +'"]').get(key.split('-')[1]);
							if($ele){
								$($ele).parent().find('.text-danger').remove();
								$($ele).parent().append("<span class='text-danger'>"+ err +"</span>");
							}
						});
					} else {
						$ele = $this.find('[name="'+ i +'"]');
						if(!$ele.length) $ele = $this.find('.'+ i)
							if($ele){
								$ele.parents(".form-group").addClass("has-error");
								$ele.after("<span class='text-danger'>"+ j +"</span>");
							}

						}
					});
			}

			if(result['error']){
				Swal.fire({
					icon: 'error',
					html: result.error,
				});
			}
		},
	})
});

$(document).on('change', '#recursion_type', function(){
	var recursion_type = $(this).val();     

	if( recursion_type == 'custom_time' ){
		$('.custom_time').show();
	}else{
		$('.custom_time').hide();
	}
});

$(document).on('change', '#recur_day, #recur_hour, #recur_minute', function(){
	var days = $('#recur_day').val();
	var hours = $('#recur_hour').val();
	var minutes = $('#recur_minute').val();
	var total_minutes;      

	total_hours = parseInt(days*24) + parseInt(hours);
	total_minutes = parseInt(total_hours*60) + parseInt(minutes);
	$('.custom_time').find('input[name="recursion_custom_time"]').val(total_minutes);

});

$(".get-code").on('click',function(){
	$this = $(this);
	$.ajax({
		url:'<?= base_url("usercontrol/tool_get_code") ?>',
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
});

var cache ={};
$("#category_auto").autocomplete({
	source: function( request, response ) {
		var term = request.term;
		if ( term in cache ) {response( cache[ term ] );return;}

		$.getJSON( '<?= base_url('usercontrol/integration_category_auto') ?>', request, function( data, status, xhr ) {
			cache[ term ] = data;
			response( data );
		});
	},
	minLength: 0,
	select: function (event, ui) {
		$("#category_auto").blur();
		event.preventDefault();
		if($(".category-selected input[value='"+ ui.item.value +"']").length == 0){
			$(".category-selected").append('\
				<li>\
				<i class="fa fa-trash remove-category"></i>\
				<span>'+ ui.item.label +'</span>\
				<input type="hidden" name="category[]" type="" value="'+ ui.item.value +'">\
				</li>\
				');
		}
	},
}).on('focus',function(){
	$(this).data("uiAutocomplete").search($(this).val());
});

$(".category-selected").delegate(".remove-category",'click', function(){
	$(this).parents("li").remove();
})

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
		html += '<td style="border-right: 1px solid lightgrey;">'+i+'</td>';
		html += '<td style="border-right: 1px solid lightgrey;"><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[sale_commition]" value="'+(levels[i] ? levels[i]['sale_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text refer-symball"></span></div></div></td>';
		html += '<td><input type="number" step="any" name="referlevel_'+i+'[commition]" value="'+(levels[i] ? levels[i]['commition'] : '' )+'" class="form-control" /></td>';
		html += '<td style="border-right: 1px solid lightgrey;"><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_commition]" value="'+(levels[i] ? levels[i]['ex_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div></div></td>';
		html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_action_commition]" value="'+(levels[i] ? levels[i]['ex_action_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div></div></td>';
		html += '</tr>';
	}

	$('#tbl_refer_level tbody').html(html);

	chnage_teigger();
});
$(document).on('click','.edit-comment', function(){
	var id = $(this).data('id');
	var comment_content = $('.comment-content-'+id).text();
	$('#comment-box').text(comment_content);
	$('#updateid').val(id);
	$('#btnUpdateArea').removeClass('d-none');
});
$(document).on('click','#btnUpdate',function(){
	var comment_content = $('#comment-box').val();
	$this = $(this);
	if(comment_content.trim() !=""){
		var id = $('#updateid').val();
		$('.comment-content-'+id).text($('#comment-box').val());
		var tool_id = window.location.href.split("/").pop();

		$.ajax({
			url:'<?= base_url("usercontrol/updateComment") ?>',
			type:'POST',
			dataType:'json',
			data:{id:id,comment:comment_content,tool_id},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				console.log(json)
				$('#btnUpdateArea').addClass('d-none');
				$('#comment-box').val('')
				$('#updateid').val('');
			},
		})

	} else {
		alert("Can't send blank message")
	}
});

function GeneratenNewCode(codeinput)
{
	$program_tool_id=$("#program_tool_id").val();
	$tool_type=$("#tool_type").val();
	$.ajax({
			url:'<?= base_url("integration/generateRandomCodeApi") ?>',
			type:'POST',
			dataType:'json',
			data:{tool_type:$tool_type,program_tool_id:$program_tool_id},
			beforeSend:function(){ 
			 },
			complete:function(){ 
			},
			success:function(json){

				 $('#'+codeinput).val(json);
			},
		})
}
</script>

<script>
$(document).on('change', 'select.cookies_type_select', function(){
	if($(this).val() == 1) {
		$('.cookies_type_input').show();
	} else {
		$('.cookies_type_input').hide();
	}
});

<?php if($type == 'video_ads'){ ?>
function showVideoPlaceholder() {
	const $container = $('.video-preview-container');
	$container.html('<div class="text-center text-muted py-5 video-placeholder"><i class="fas fa-video fa-4x mb-3 text-primary opacity-50"></i><p class="mb-2 fw-bold"><?= __("user.video_appears_here") ?></p><p class="mb-0 small"><?= __("user.enter_url_to_preview") ?></p></div>');
}

function parseVideoURL() {
	const sourceType = $('input[name="video_source_type"]:checked').val();
	const $container = $('.video-preview-container');
	
	if(sourceType === 'youtube_vimeo') {
		const videoURL = $('.parse-video').val();
		if(!videoURL) { showVideoPlaceholder(); return; }

		if(videoURL.includes('youtube.com') || videoURL.includes('youtu.be')){
			try {
				let videoID = null;
				if(videoURL.includes('youtube.com')){
					const url = new URL(videoURL);
					videoID = url.searchParams.get('v');
				} else {
					videoID = videoURL.split('/').pop().split('?')[0];
				}
				if(videoID) {
					$container.html(`<div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/${videoID}" frameborder="0" allowfullscreen></iframe></div>`);
				} else {
					$container.html('<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-3x mb-2"></i><p class="mb-0"><?= __("user.invalid_youtube_url") ?></p></div>');
				}
			} catch(e) {
				$container.html('<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-3x mb-2"></i><p class="mb-0"><?= __("user.invalid_url_format") ?></p></div>');
			}
		} else if(videoURL.includes('vimeo.com')) {
			const match = videoURL.match(/vimeo\.com\/(?:video\/)?(\d+)/);
			if(match && match[1]) {
				$container.html(`<div class="ratio ratio-16x9"><iframe src="https://player.vimeo.com/video/${match[1]}" frameborder="0" allowfullscreen></iframe></div>`);
			} else {
				$container.html('<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-3x mb-2"></i><p class="mb-0"><?= __("user.invalid_vimeo_url") ?></p></div>');
			}
		} else if(videoURL.includes('aparat.com')) {
			const match = videoURL.match(/aparat\.com\/v\/([a-zA-Z0-9]+)/i);
			if(match && match[1]) {
				$container.html(`<div class="ratio ratio-16x9"><iframe src="https://www.aparat.com/video/video/embed/videohash/${match[1]}/vt/frame" frameborder="0" allowfullscreen></iframe></div>`);
			} else {
				$container.html('<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-3x mb-2"></i><p class="mb-0"><?= __("user.invalid_video_url") ?></p></div>');
			}
		} else if(videoURL.includes('rutube.ru')) {
			const match = videoURL.match(/rutube\.ru\/video\/([a-zA-Z0-9]+)\//i);
			if(match && match[1]) {
				$container.html(`<div class="ratio ratio-16x9"><iframe src="https://rutube.ru/play/embed/${match[1]}" frameborder="0" allowfullscreen></iframe></div>`);
			} else {
				$container.html('<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-3x mb-2"></i><p class="mb-0"><?= __("user.invalid_video_url") ?></p></div>');
			}
		} else if(videoURL.includes('dailymotion.com')) {
			const match = videoURL.match(/dailymotion\.com\/video\/([a-zA-Z0-9]+)/i);
			if(match && match[1]) {
				$container.html(`<div class="ratio ratio-16x9"><iframe src="https://www.dailymotion.com/embed/video/${match[1]}" frameborder="0" allowfullscreen></iframe></div>`);
			} else {
				$container.html('<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-3x mb-2"></i><p class="mb-0"><?= __("user.invalid_video_url") ?></p></div>');
			}
		} else {
			$container.html('<div class="text-center text-warning py-5"><i class="fas fa-exclamation-triangle fa-3x mb-2"></i><p class="mb-0"><?= __("user.unsupported_video_type") ?></p></div>');
		}
	} else if(sourceType === 'mp4_url') {
		const mp4URL = $('.parse-video-mp4').val();
		if(!mp4URL) { showVideoPlaceholder(); return; }
		$container.html(`<div class="ratio ratio-16x9"><video controls class="w-100 h-100"><source src="${mp4URL}" type="video/mp4"><?= __("user.browser_not_support_video") ?></video></div>`);
	}
}

function handleVideoSourceChange() {
	const sourceType = $('input[name="video_source_type"]:checked').val();
	$('.video-source-section').hide();
	$('#' + sourceType + '_section').show();
	showVideoPlaceholder();
	parseVideoURL();
}

$(document).ready(function(){
	$('.video-source-radio').on('change', handleVideoSourceChange);
	$('.parse-video').on('input', parseVideoURL);
	$('.parse-video-mp4').on('input', parseVideoURL);
	
	$('.video-upload-input').on('change', function(){
		const file = this.files[0];
		if(file) {
			const url = URL.createObjectURL(file);
			const $container = $('.video-preview-container');
			$container.html(`<div class="ratio ratio-16x9"><video controls class="w-100 h-100"><source src="${url}" type="${file.type}"><?= __("user.browser_not_support_video") ?></video></div>`);
		}
	});

	parseVideoURL();
});
<?php } ?>

// Vendor Integration Setup Wizard: Method card selection
$(document).on('click', '.v-intg-method-card', function() {
	var method = $(this).data('method');
	$('#vendor_integration_method').val(method);

	$('.v-intg-method-card').removeClass('border-primary border-warning border-info border-secondary border-dark shadow');
	$('.v-intg-method-card .card-footer').remove();
	var colorMap = {js_pixel:'primary', s2s:'warning', s2s_direct:'info', postback:'secondary', conversion_api:'dark'};
	$(this).addClass('border-' + colorMap[method] + ' shadow');
	$(this).append('<div class="card-footer bg-' + colorMap[method] + ' text-' + (method === 's2s' ? 'dark' : 'white') + ' text-center py-1 small fw-bold"><i class="fas fa-check-circle me-1"></i><?= __("admin.active") ?></div>');

	$('#vendor_s2s_enabled').val(method === 's2s' || method === 's2s_direct' ? '1' : '0');
	$('#vendor_s2s_direct_mode').val(method === 's2s_direct' ? '1' : '0');

	$('.v-intg-method-content').slideUp(200);
	$('#v-method-' + method).slideDown(200);

	if (method === 'postback') {
		$('#postback-setting').appendTo('#v-method-postback-settings').show().addClass('show active');
	} else if (method === 'conversion_api') {
		$('#conversion_api').appendTo('#v-method-conversion_api-docs').show().addClass('show active');
	}
});

// Vendor: View code button inside wizard
$(document).on('click', '.v-btn-get-code', function() {
	var $this = $(this);
	var originalHtml = $this.html();
	$.ajax({
		url: '<?= base_url("usercontrol/tool_get_code") ?>',
		type: 'POST',
		dataType: 'json',
		data: { id: $this.attr("data-id") },
		beforeSend: function() { $this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i><?= __("user.loading") ?>...'); },
		complete: function() { $this.prop('disabled', false).html(originalHtml); },
		success: function(json) {
			if(json['html']) {
				$('#vendor-wizard-code-modal .modal-content').html(json['html']);
				$('#vendor-wizard-code-modal').modal('show');
			}
		}
	});
});

// Vendor: Copy button helper
$(document).on('click', '.v-copy-btn', function() {
	var targetId = $(this).data('target');
	var input = document.getElementById(targetId);
	input.select(); input.setSelectionRange(0, 99999);
	var $btn = $(this);
	navigator.clipboard.writeText(input.value).then(function() {
		$btn.html('<i class="fas fa-check text-success"></i>');
		setTimeout(function() { $btn.html('<i class="fas fa-copy"></i>'); }, 1500);
	}).catch(function() {
		$btn.html('<i class="fas fa-copy"></i>');
	});
});

// Auto-select Integration Setup tab for non-js_pixel campaigns
$(document).ready(function() {
	var vMethod = $('#vendor_integration_method').val() || 'js_pixel';
	if (vMethod !== 'js_pixel') {
		setTimeout(function() {
			$('#vendor-campaign-tabs button[data-bs-target="#vendor-integration-setup"]').tab('show');
		}, 50);
	}

	var hash = window.location.hash;
	if (hash === '#vendor-integration-setup') {
		$('#vendor-campaign-tabs button[data-bs-target="#vendor-integration-setup"]').tab('show');
	}
});
</script>

<!-- Vendor Wizard Code Modal -->
<div class="modal fade" id="vendor-wizard-code-modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content"></div>
	</div>
</div>
</div>