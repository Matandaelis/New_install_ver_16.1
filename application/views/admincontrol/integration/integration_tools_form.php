<div class="container-fluid px-4 pb-4">
  <?php $this->load->view('admincontrol/integration/_campaign_nav'); ?>
  <div class="row">
    <div class="col-12">
      <?php
        $typeIcons = ['banner' => 'bi-image', 'text_ads' => 'bi-fonts', 'link_ads' => 'bi-link-45deg', 'video_ads' => 'bi-camera-video'];
        $typeColors = ['banner' => '#3b82f6', 'text_ads' => '#10b981', 'link_ads' => '#8b5cf6', 'video_ads' => '#f59e0b'];
        $typeColor = $typeColors[$type] ?? '#3b82f6';
      ?>
      <!-- Breadcrumb Back + Title Bar -->
      <div class="intg-page-bar mb-3">
        <a href="<?= base_url('integration/integration_tools') ?>" class="intg-back-link">
          <i class="bi bi-chevron-left"></i>
          <span><?= __('admin.campaigns') ?></span>
        </a>
        <div class="intg-page-title">
          <div class="intg-form-icon" data-type="<?= $type ?>">
            <i class="bi <?= $typeIcons[$type] ?? 'bi-tools' ?>"></i>
          </div>
          <div>
            <h5 class="mb-0 fw-bold"><?= isset($tool['name']) ? htmlspecialchars($tool['name']) : __('admin.new_campaign') ?></h5>
            <span class="badge intg-type-badge" data-type="<?= $type ?>"><?= __('admin.'.$type) ?></span>
          </div>
        </div>
      </div>

      <!-- Compact Notification Banner -->
      <div id="warnings_notif">
      <?php
      if (isset($tool)) {
        $marketpostback = is_array($tool['marketpostback']) ? $tool['marketpostback'] : json_decode($tool['marketpostback'], true);
        $postback_enabled = isset($marketpostback['status']) && $marketpostback['status'] === 'custom';
        $integration_method = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel';
        $skip_js_validation = in_array($integration_method, ['s2s', 's2s_direct', 'postback', 'conversion_api']);
        if (!$postback_enabled && !$skip_js_validation) {
          $security_alerts = external_integration_security_check($tool['target_link']);
          $alerts = [];
          if (!is_array($security_alerts)) {
            $alerts[] = ['type' => 'danger', 'icon' => 'bi-x-circle-fill', 'msg' => __('admin.error') . ' ' . $security_alerts . ': ' . __('admin.invalid_campaign_target_link')];
          }
          if (is_array($security_alerts) && isset($security_alerts['comment']) && $security_alerts['comment']) {
            $alerts[] = ['type' => 'danger', 'icon' => 'bi-x-circle-fill', 'msg' => __('admin.error') . ' ' . $security_alerts['comment'] . ': ' . __('admin.code_has_comment_line')];
          }
          if (is_array($security_alerts) && empty($security_alerts['common_code'])) {
            $alerts[] = ['type' => 'warning', 'icon' => 'bi-exclamation-triangle-fill', 'msg' => __('admin.warning') . ': ' . __('admin.common_integration_code_not_available_msg')];
          }
          if (is_array($security_alerts) && isset($security_alerts['website_url']) && empty($security_alerts['website_url'])) {
            $alerts[] = ['type' => 'warning', 'icon' => 'bi-exclamation-triangle-fill', 'msg' => __('admin.warning') . ': ' . __('admin.website_url_not_available_msg')];
          }
          if (!empty($alerts)) { ?>
            <div class="intg-alert-bar mb-3">
              <?php foreach ($alerts as $a) { ?>
                <div class="intg-alert intg-alert--<?= $a['type'] ?>" role="alert">
                  <i class="bi <?= $a['icon'] ?>"></i>
                  <span><?= $a['msg'] ?></span>
                  <button type="button" class="intg-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
                </div>
              <?php } ?>
            </div>
          <?php }
        }
      } ?>
      </div>

      <div class="card intg-form-header shadow-sm border-0" data-type="<?= $type ?>">
        <div class="card-body p-3">

          <form action="" method="post" id="form_tools">
            <!-- Unified Tab Navigation -->
            <div class="intg-unified-tabs mb-3">
              <ul class="nav nav-pills flex-nowrap overflow-auto" id="TabsNav" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                    <i class="bi bi-gear me-1"></i><span class="d-none d-md-inline"><?= __('admin.general_setting') ?></span><span class="d-md-none">General</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="level-tab" data-bs-toggle="tab" data-bs-target="#menu1" type="button" role="tab" aria-controls="menu1" aria-selected="false">
                    <i class="bi bi-layers me-1"></i><span class="d-none d-md-inline"><?= __('admin.level_setting') ?></span><span class="d-md-none">Levels</span>
                  </button>
                </li>
                <li class="intg-tab-divider d-none d-lg-flex"></li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="recurring-tab" data-bs-toggle="tab" data-bs-target="#menu2" type="button" role="tab" aria-controls="menu2" aria-selected="false">
                    <i class="bi bi-arrow-repeat me-1"></i><span class="d-none d-md-inline"><?= __('admin.recurring_setting') ?></span><span class="d-md-none">Recurring</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="integration-setup-tab" data-bs-toggle="tab" data-bs-target="#integration-setup" type="button" role="tab" aria-controls="integration-setup" aria-selected="false">
                    <i class="bi bi-plug me-1"></i><span class="d-none d-md-inline"><?= __('admin.integration_setup') ?></span><span class="d-md-none"><?= __('admin.integration_setup_short') ?></span>
                  </button>
                </li>
                <li class="nav-item d-none" role="presentation">
                  <button class="nav-link" id="postback-tab" data-bs-toggle="tab" data-bs-target="#postback-setting" type="button" role="tab" aria-controls="postback-setting" aria-selected="false">
                    <i class="bi bi-arrow-left-right me-1"></i><span class="d-none d-md-inline"><?= __('admin.postback') ?></span><span class="d-md-none">Postback</span>
                  </button>
                </li>
                <li class="nav-item d-none" role="presentation">
                  <button class="nav-link" id="conversion-tab" data-bs-toggle="tab" data-bs-target="#conversion_api" type="button" role="tab" aria-controls="conversion_api" aria-selected="false">
                    <i class="bi bi-code-slash me-1"></i><span class="d-none d-md-inline"><?= __('admin.conversion_api') ?></span><span class="d-md-none">API</span>
                  </button>
                </li>
              </ul>
            </div>
            <div class="tab-content">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="general-tab">
					<input type="hidden" name="type" value="<?= $type ?>">
					<input type="hidden" name="program_tool_id" id="program_tool_id" value="<?= isset($tool) ? $tool['id'] : '0' ?>">

                <!-- Main Form Content -->
                <div class="row g-3">
                  <div class="col-lg-8">
                    <!-- Campaign Name & Tool Type (Combined) -->
                    <div class="card intg-section-card">
                      <div class="card-header">
                        <i class="bi bi-tag me-2 text-primary"></i><?= __('admin.campaign_name') ?> & <?= __('admin.tool_type') ?>
                      </div>
                      <div class="card-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label for="campaign_name" class="form-label small fw-semibold"><?= __('admin.campaign_name') ?> <span class="text-danger">*</span></label>
                            <input class="form-control form-control-sm" id="campaign_name" value="<?= isset($tool) ? htmlspecialchars($tool['name']) : '' ?>" name="name" type="text" required>
                          </div>
                          <div class="col-md-6">
                            <label for="tool_type" class="form-label small fw-semibold"><?= __('admin.select_tool_type') ?> <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="tool_type" id="tool_type" required>
                              <option value=""><?= __('admin.select_tool_type') ?></option>
                              <option <?= (isset($tool) && $tool['tool_type'] == 'program') ? 'selected' : '' ?> value="program"><?= __('admin.sale_integration') ?></option>
                              <option <?= (isset($tool) && $tool['tool_type'] == 'single_action') ? 'selected' : '' ?> value="single_action"><?= __('admin.single_action_integration') ?></option>
                              <option <?= (isset($tool) && $tool['tool_type'] == 'action') ? 'selected' : '' ?> value="action"><?= __('admin.multi_action_integration') ?></option>
                              <option <?= (isset($tool) && $tool['tool_type'] == 'general_click') ? 'selected' : '' ?> value="general_click"><?= __('admin.click_integration') ?></option>
                            </select>
                          </div>
                          <div class="col-12 tool-integration-plugin for-program-tool intg-plugin-hidden">
                            <label for="tool_integration_plugin" class="form-label small fw-semibold"><?= __('admin.tool_integration_plugin') ?></label>
                            <select class="form-select form-select-sm" name="tool_integration_plugin" id="tool_integration_plugin">
                              <option value=""><?= __('admin.select_tool_integration_plugin') ?></option>
                              <?php
                              $pluginForSkipp = ['wp_user_register', 'wp_forms', 'postback', 'show_affiliate_id', 'wp_show_affiliate_id', 'affiliate_register_api', 'php_api_library'];
                              foreach ($integration_plugins as $key => $module) {
                                if (!in_array($key, $pluginForSkipp)) {
                              ?>
                                  <option <?= (isset($tool) && $tool['tool_integration_plugin'] == $key) ? 'selected' : '' ?> value="<?= $key; ?>"><?= htmlspecialchars($module['name']); ?></option>
                              <?php
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-4">
                    <?php 
                    $is_start_date = (isset($tool) && !empty($tool['start_date']) && $tool['start_date'] != null) ? true : false;
                    $is_end_date = (isset($tool) && !empty($tool['end_date']) && $tool['end_date'] != null) ? true : false;
                    $tool_period_val = 1;
                    if($is_start_date && $is_end_date) { $tool_period_val = 4; }
                    if($is_start_date && !$is_end_date) { $tool_period_val = 3; }
                    if(!$is_start_date && $is_end_date) { $tool_period_val = 2; }
                    ?>
                    
                    <!-- Tool Period & Dates -->
                    <div class="card intg-section-card">
                      <div class="card-header">
                        <i class="bi bi-clock me-2 text-primary"></i><?= __('admin.tool_period') ?>
                      </div>
                      <div class="card-body">
                        <div class="mb-2">
                          <label for="tool_period" class="form-label small fw-semibold"><?= __('admin.select_period') ?></label>
                          <select class="form-select form-select-sm" name="tool_period" id="tool_period">
                            <option value="1" <?= ($tool_period_val == '1') ? 'selected' : '' ?>><?= __('admin.always_running') ?></option>
                            <option value="2" <?= ($tool_period_val == '2') ? 'selected' : '' ?>><?= __('admin.from_today_to_custom_date') ?></option>
                            <option value="3" <?= ($tool_period_val == '3') ? 'selected' : '' ?>><?= __('admin.from_custom_date_to_lifetime') ?></option>
                            <option value="4" <?= ($tool_period_val == '4') ? 'selected' : '' ?>><?= __('admin.for_custom_period') ?></option>
                          </select>
                        </div>
                        <div id="start_date_input" class="mb-2 intg-date-hidden">
                          <label for="start_date" class="form-label small fw-semibold"><i class="bi bi-play me-1"></i><?= __('admin.start_date') ?></label>
                          <input class="form-control form-control-sm datetime-picker" id="start_date" value="<?= (isset($tool) && !empty($tool['start_date']) && $tool['start_date'] != null) ? date('d-m-Y H:i', strtotime($tool['start_date'])) : '' ?>" name="start_date" type="text" autocomplete="off">
                        </div>
                        <div id="end_date_input" class="mb-0 intg-date-hidden">
                          <label for="end_date" class="form-label small fw-semibold"><i class="bi bi-stop me-1"></i><?= __('admin.end_date') ?></label>
                          <input class="form-control form-control-sm datetime-picker" id="end_date" value="<?= (isset($tool) && !empty($tool['end_date']) && $tool['end_date'] != null) ? date('d-m-Y H:i', strtotime($tool['end_date'])) : '' ?>" name="end_date" type="text" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Target Link Section -->
                <div class="row g-3 mt-1">
                  <div class="col-12">
                    <div class="card intg-section-card" id="target-link-card">
                      <div class="card-header">
                        <i class="bi bi-link-45deg me-2 text-primary"></i><?= __('admin.campaign_target_link') ?>
                      </div>
                      <div class="card-body">
                        <div class="mb-0">
                          <label for="target_link" class="form-label small fw-semibold"><?= __('admin.target_url') ?> <span class="text-danger" id="target-required">*</span></label>
                          <input class="form-control form-control-sm" id="target_link" value="<?= isset($tool) ? htmlspecialchars($tool['target_link']) : '' ?>" name="target_link" type="url">
                          <small class="text-muted intg-stripe-help-hidden" id="stripe-target-help">
                            <i class="bi bi-info-circle me-1"></i>Payment link will be auto-generated by Stripe after saving
                          </small>
									        </div>
									    </div>
									</div>
									
								<!-- Stripe-Specific Fields -->
								<div class="card intg-section-card intg-stripe-hidden mt-3" id="stripe-fields-card">
                      <div class="card-header bg-primary bg-gradient text-white">
                        <i class="bi bi-credit-card me-2"></i>Stripe Payment Settings
                      </div>
                      <div class="card-body">
                        <div id="stripe-error-alert" class="alert alert-danger d-none mb-3" role="alert">
                          <i class="bi bi-exclamation-triangle me-2"></i>
                          <strong>Stripe Error:</strong> <span id="stripe-error-message"></span>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label for="stripe_price" class="form-label"><?= __('admin.product_price') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                              <span class="input-group-text"><?= $CurrencySymbol ?></span>
                              <input type="number" class="form-control" id="stripe_price" name="stripe_price" 
                                     value="<?= isset($tool['commission']['stripe_price']) ? $tool['commission']['stripe_price'] : '' ?>" 
                                     step="0.01" min="0.01" placeholder="99.00">
                            </div>
                            <small class="text-muted">Amount customer will pay</small>
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
                          <strong>Note:</strong> Stripe will create a secure payment link for this product. Customers will be redirected to Stripe's checkout page.
                        </div>
									    </div>
									</div>

								<?php if (isset($tool['vendor_id']) && $tool['vendor_id'] != 0): ?>
								    <div class="card intg-section-card mt-3">
								        <div class="card-header"><i class="bi bi-file-earmark-text me-2 text-primary"></i><?= __('admin.terms') ?></div>
									        <div class="card-body">
									            <div class="form-group mb-3">
									                <textarea id="terms" name="terms" class="form-control" placeholder="<?= __('admin.terms') ?>" readonly><?= isset($tool) ? $tool['terms'] : '' ?></textarea>
									            </div>
									        </div>
									    </div>
									<?php endif ?>

							<!-- Row for Category, Country, Feature Image -->
							<div class="row g-3 mt-1 mb-3">
							  <div class="col-lg-4">
							    <div class="card intg-section-card h-100">
							      <div class="card-header"><i class="bi bi-folder me-2 text-primary"></i><?= __('admin.categories') ?></div>
							      <div class="card-body py-2">
							        <input name="category_auto" placeholder="<?= __('admin.choose_categories') ?>" id="category_auto" class="form-control form-control-sm mb-2" autocomplete="off">
							        <ul class="category-selected change-color mb-2 small">
							          <?php if(isset($categories)){ ?>
							            <?php foreach ($categories as $key => $category) { ?>
							              <li>
							                <i class="bi bi-trash remove-category"></i>
							                <span><?= $category['label'] ?></span>
							                <input type="hidden" name="category[]" value="<?= $category['value'] ?>">
							              </li>
							            <?php } ?>
							          <?php } ?>
							        </ul>
							        <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#addCategory"><?= __('admin.add_category') ?></button>
							      </div>
							    </div>
							  </div>
							  <div class="col-lg-4">
							    <div class="card intg-section-card h-100">
							      <div class="card-header"><i class="bi bi-globe me-2 text-primary"></i><?= __('admin.country_location') ?></div>
							      <div class="card-body py-2">
							        <select class="form-control form-control-sm select2" name="country_auto" id="country_auto">
							          <option value=""><?= __('admin.select_country'); ?></option>
							          <?php foreach ($country_list as $country): ?>
							            <option value="<?= $country->id ?>" data-sortname="<?= $country->sortname ?>" data-phonecode="<?= $country->phonecode ?>"><?= $country->name ?></option>
							          <?php endforeach; ?>
							        </select>
							        <ul class="country-selected list-group list-group-flush mt-2 small">
							          <?php if (isset($country_name) && is_array($country_name)): ?>
							            <?php foreach ($country_name as $key => $country): ?>
							              <?php if ($country != ""): ?>
							                <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-0 border-0">
							                  <span><?= htmlspecialchars($country) ?> (<?= htmlspecialchars($country_sortname[$key]) ?>)</span>
							                  <i class="bi bi-trash remove-country intg-remove-country"></i>
							                  <input type="hidden" name="country_name[]" value="<?= htmlspecialchars($country); ?>">
							                  <input type="hidden" name="country_sortname[]" value="<?= htmlspecialchars($country_sortname[$key]); ?>">
							                </li>
							              <?php endif; ?>
							            <?php endforeach; ?>
							          <?php endif; ?>
							          <?php if (!empty($country_message)): ?>
							            <li class="list-group-item text-danger py-1 px-0 border-0"><?= htmlspecialchars($country_message) ?></li>
							          <?php endif; ?>
							        </ul>
							      </div>
							    </div>
							  </div>
							  <div class="col-lg-4">
							    <div class="card intg-section-card h-100">
							      <div class="card-header"><i class="bi bi-image me-2 text-primary"></i><?= __('admin.featured_image') ?></div>
							      <div class="card-body py-2 text-center">
							        <?php $featured_image = $tool['featured_image'] != '' ? 'assets/images/product/upload/thumb/' . $tool['featured_image'] : 'assets/images/placeholder.png'; ?>
							        <div id='featured_image' class="d-flex justify-content-center align-items-center border rounded mx-auto mb-2 intg-featured-img-wrap">
							          <?php if ($tool['featured_image']): ?>
							            <img src="<?= base_url($featured_image); ?>" class="img-thumbnail intg-featured-img">
							          <?php else: ?>
							            <i class="bi bi-image fs-4 text-muted"></i>
							          <?php endif; ?>
							        </div>
							        <div class="btn-group btn-group-sm">
							          <label for="product_featured_image" class="btn btn-outline-primary mb-0">
							            <i class="bi bi-upload me-1"></i><?= __('admin.choose_file') ?>
							            <input type="file" id="product_featured_image" name="featured_image" onchange="readURL(this,'#featured_image'); removeCampignDefaultClass('#featured_image');" class="form-control intg-file-hidden">
							          </label>
							          <button type="button" onclick="removeImage('#featured_image');" class="btn btn-outline-danger">
							            <i class="bi bi-trash"></i>
							          </button>
							        </div>
							        <input type="hidden" name="old_featured_image" value="<?= $tool['featured_image'] ?>">
							      </div>
							    </div>
							  </div>
							</div>

	<script>
	$(document).ready(function() {
	    $('.select2').select2().on('select2:open', function (e) {
	        $('.country-selected .text-danger').remove();
	    }).on('select2:select', function (e) {
	        var data = e.params.data;
	        var countryId = data.id;
	        var countryName = data.text;
	        var countrySortname = $(data.element).data('sortname');
	        $('.country-selected').append(`<li class="list-group-item d-flex justify-content-between align-items-center py-1">
	            <span>${countryName} (${countrySortname})</span>
	            <div>
	                <i class="bi bi-trash remove-country intg-remove-country" data-country_id="${countryId}"></i>
	                <input type="hidden" name="country[]" value="${countryId}">
	                <input type="hidden" name="country_name[]" value="${countryName}">
	                <input type="hidden" name="country_sortname[]" value="${countrySortname}">
	            </div>
	        </li>`);
	        $(this).val('').trigger('change');
	    });
	    $(document).on('click', '.remove-country', function() {
	        $(this).closest('li').remove();
	    });
	});
	
	function removeImage(element) {
	    $(element).html('<i class="bi bi-image fs-4 campaign-img campaign_default_image text-muted"></i>');
	    $('#product_featured_image').val('');
	    $('input[name="old_featured_image"]').val('');
	}
	</script>
	
		</div>

		<!--vendor tools-->
		<div class="col-sm-12">
			<!--vendor tools-->
			<div class="well">
				<!--Vendor Information Section-->
				<?php if((int)$tool['vendor_id']){ ?>
				    <div class="card intg-section-card">
				        <div class="card-header"><i class="bi bi-person-badge me-2 text-primary"></i><?= __('admin.vendor_info') ?></div>
					        <div class="card-body">
					            <div class="form-group">
					                <label class="control-label d-inline-block"><?= __('admin.vendor'); ?> : </label>
					                <div class="d-inline-block">
					                    <?= $tool['vendor_name'] ?> ( <?= $tool['username'] ?> )
					                    <a class="font-weight-bold" href="<?= base_url('admincontrol/addusers/'. $tool['vendor_id']) ?>" target="_blank">
					                        <i class="bi bi-link-45deg"></i>
					                    </a>
					                </div>
					            </div>
					        </div>
					    </div>
					<!--End Vendor Information Section-->

					<!-- Program Selection and Information -->
					<div class="card for-program-tool">
					    <div class="card-header">
					        <h5 class="card-title text-monospace text-secondary"><?= __('admin.program_settings') ?></h5>
					    </div>
					    <div class="card-body">
					        <div class="form-group">
					            <label class="control-label"><?= __('admin.program_name') ?></label>
					            <input type="hidden" name="program_id" value="<?= $tool['program_id'] ?>">
					            <select class="form-control" id="program_id_select" disabled>
					                <option value=""><?= __('admin.select_market_program') ?></option>
					                <?php foreach ($programs as $key => $program) { ?>
					                <option data-commission_type='<?= $program['commission_type'] ?>'
					                        data-commission_sale='<?= $program['commission_type'] == 'fixed' ? c_format($program['commission_sale']) : (int)$program['commission_sale']. "%" ?>'
					                        data-commission_number_of_click='<?= $program['commission_number_of_click'] ?>'
					                        data-commission_click_commission='<?= c_format($program['commission_click_commission']) ?>'
					                        data-click_status='<?= $program['click_status'] ?>'
					                        data-sale_status='<?= $program['sale_status'] ?>'
					                        data-admin_commission_type='<?= $program['admin_commission_type'] ?>'
					                        data-admin_commission_sale='<?= $program['admin_commission_type'] == 'fixed' ? c_format($program['admin_commission_sale']) : (int)$program['admin_commission_sale']. "%" ?>'
					                        data-admin_commission_number_of_click='<?= $program['admin_commission_number_of_click'] ?>'
					                        data-admin_commission_click_commission='<?= c_format($program['admin_commission_click_commission']) ?>'
					                        data-admin_click_status='<?= $program['admin_click_status'] ?>'
					                        data-admin_sale_status='<?= $program['admin_sale_status'] ?>'
					                        <?= (isset($tool) && $tool['program_id'] == $program['id']) ? 'selected' : '' ?> value="<?= $program['id'] ?>">
					                    <?= $program['name'] ?>
					                </option>
					                <?php } ?>
					            </select>
					        </div>

					        <div class="form-group mt-3">
					            <label class="control-label"><?= __('admin.affiliate_commission') ?></label>
					            <div class="program-oac"></div>
					        </div>
					    </div>
					</div>

					<script type="text/javascript">
					    $('#program_id_select').change(function(){
					        var data = $('#program_id_select option:selected').data();
					        var string = '';
					        if(Object.keys(data).length){
					            string += '<b><?= __('admin.click') ?> </b> : ';
					            if(data['click_status']){
					                string += data['commission_click_commission'] + ' <?= __('admin.per') ?> ' + data['commission_number_of_click'] + " <?= __('admin.clicks') ?>";
					            } else{
					                string += '<?= __('admin.disabled') ?>';
					            }

					            string += ' &nbsp; | &nbsp; <b><?= __('admin.sale') ?> </b> : ';
					            if(data['sale_status']){
					                string += data['commission_sale'];
					            } else{
					                string += '<?= __('admin.disabled') ?>';
					            }

					            string += '<br><br><b><?= __('admin.commission_for_admin') ?></b>:<br> ';
					            string += '<b><?= __('admin.click') ?> </b> : ';
					            if(data['admin_click_status']){
					                string += data['admin_commission_click_commission'] + ' <?= __('admin.per') ?> ' + data['admin_commission_number_of_click'] + " <?= __('admin.clicks') ?>";
					            } else{
					                string += '<?= __('admin.disabled') ?>';
					            }

					            string += ' &nbsp; | &nbsp; <b><?= __('admin.sale') ?> </b> : ';
					            if(data['admin_sale_status']){
					                string += data['admin_commission_sale'];
					            } else{
					                string += '<?= __('admin.disabled') ?>';
					            }
					        } else{
					            string += '<?= __('admin.program_not_selected') ?>';
					        }

					        $(".program-oac").html(string);
					    });
					    $('#program_id_select').trigger("change");
					</script>

				<!--Vendor Single-Action Commission Settings-->
				<div class="for-action-tool">
					<!-- Action Code Section -->
					<div class="card intg-section-card">
					    <div class="card-header">
					        <i class="bi bi-code-square me-2 text-primary"></i><?= __('admin.action_code') ?>
					        <span data-toggle="tooltip" data-placement="right" title="The code should be a string composed of letters and numbers only, without spaces or special characters. This code will be used in your tracking script to identify and track the specific click or action.">
					            <i class="bi bi-info-circle text-muted"></i>
					        </span>
					    </div>
						    <div class="card-body">
						        <div class="form-group">
						            <input class="form-control" name="action_code" value="<?= isset($tool) ? $tool['action_code'] : '' ?>" readonly>
						        </div>
						    </div>
						</div>
						<!-- End Action Code Section -->


					<!-- Action Admin Commission fee -->
					<div class="card intg-section-card single-action-integration">
					    <div class="card-header"><i class="bi bi-currency-dollar me-2 text-primary"></i><?= __('admin.admin_commission_fee') ?></div>
						    <div class="card-body">
						        <div class="row">
						            <div class="col-sm-6">
						                <div class="form-group">
						                    <label class="control-label"><?= __('admin.number_of_action_per_commission') ?></label>
						                    <input class="form-control" name="admin_action_click" value="<?= isset($tool) ? $tool['admin_action_click'] : '' ?>" type="number">
						                </div>
						            </div>
						            <div class="col-sm-6">
						                <div class="form-group">
						                    <label class="control-label"><?= __('admin.cost_per_action') ?> ($)</label>
						                    <input class="form-control" name="admin_action_amount" value="<?= isset($tool) ? $tool['admin_action_amount'] : '' ?>" type="number">
						                </div>
						            </div>
						        </div>
						    </div>
						</div>
						<!-- End Action Commission Settings -->


					<!-- Action Affiliate Commission fee -->
					<div class="card intg-section-card">
					    <div class="card-header"><i class="bi bi-people me-2 text-primary"></i><?= __('admin.affiliate_commission') ?></div>
						    <div class="card-body">
						        <div class="form-group">
						            <div>
						                <b>
						                <?= ($tool['action_amount'] && (int)$tool['action_click']) ? c_format($tool['action_amount'])." ".__('admin.per')." ".(int)$tool['action_click']." ".__('admin.clicks') : __('admin.not_set') ?>
						                </b>
						            </div>
						        </div>
						    </div>
						</div>
						<!-- End Action Affiliate Commission -->
					</div>
					<!--End Vendor Single-Action Commission Settings-->

				<!--Vendor General Click Commission Settings-->
				<div class="for-general_click-tool click-integration">
					<!-- General Code Section -->
					<div class="card intg-section-card">
					    <div class="card-header">
					        <i class="bi bi-code-square me-2 text-primary"></i><?= __('admin.general_code') ?>
					        <span data-toggle="tooltip" data-placement="right" title="The code should be a string composed of letters and numbers only, without spaces or special characters. This code will be used in your tracking script to identify and track the specific click or action.">
					            <i class="bi bi-info-circle text-muted"></i>
					        </span>
					    </div>
						    <div class="card-body">
						        <div class="form-group">
						            <label class="control-label"><?= __('admin.general_code') ?></label>
						            <input class="form-control" name="general_code" value="<?= isset($tool) ? $tool['general_code'] : '' ?>" readonly>
						        </div>
						    </div>
						</div>
						<!-- General Code Section -->

				<!-- Admin commission fee -->
				<div class="card intg-section-card">
					<!--General Click Commission Settings-->
						<div class="card-header">
						    <i class="bi bi-currency-dollar me-2 text-primary"></i><?= __('admin.admin_commission_fee') ?>
						</div>
							  <div class="card-body">
							    <div class="row">
							      <div class="col-sm-6">
							        <div class="form-group">
							          <label class="control-label"><?= __('admin.number_of_click') ?></label>
							          <input class="form-control" name="admin_general_click" value="<?= isset($tool) ? $tool['admin_general_click'] : '' ?>" type="number">
							        </div>
							      </div>
							      <div class="col-sm-6">
							        <div class="form-group">
							          <label class="control-label"><?= __('admin.cost_per_click') ?> ($)</label>
							          <input class="form-control" name="admin_general_amount" value="<?= isset($tool) ? $tool['admin_general_amount'] : '' ?>" type="number">
							        </div>
							      </div>
							    </div>
							  </div>
						</div>
						<!-- Admin commission fee -->

					<!-- Click Affiliate Commission fee -->
					<div class="card intg-section-card">
					    <div class="card-header"><i class="bi bi-people me-2 text-primary"></i><?= __('admin.affiliate_commission') ?></div>
						    <div class="card-body">
						        <div class="form-group">
						            <div>
						                <b>
						                <?= ($tool['general_amount'] && (int)$tool['general_click']) ? c_format($tool['general_amount'])." ".__('admin.per')." ".(int)$tool['general_click']." ".__('admin.clicks') : __('admin.not_set') ?>
						                </b>
						            </div>
						        </div>
						    </div>
						</div>
						<!-- End Click Affiliate Commission -->
					</div>
					<!--End General Click Commission Settings-->

				<!--comment-->
				<div class="card intg-section-card">
					<div class="card-header"><i class="bi bi-chat-dots me-2 text-primary"></i><?= __('admin.admin_comments') ?></div>
						<div class="card-body chat-card">
							<?php $comment = json_decode($tool['comment'],1);  ?>
							<?php if($comment){ ?>
								<ul class="comment-products">
									<?php foreach ($comment as $key => $value) { 
										if ($value['from'] == 'admin') {
											?>
											<li class="me"> <div data-id="<?= $key ?>" class="comment-content-<?= $key ?>"><?= $value['comment'] ?></div><a href="javascript:void(0)" data-id="<?= $key ?>" class="edit-comment"><i class="bi bi-pencil-square"></i></a></li>
											<?php
										}else{
											?>
											<li class="other"> <div><?= $value['comment'] ?></div>  </li>
											<?php
										}
									}
									?>
								</ul>
							<?php } else echo '<ul class="comment-products"></ul>'; ?>
							<div class="bg-white form-group m-0 p-2">
								<textarea class="form-control comment-box" placeholder="<?= __('admin.enter_message_and_save_program_to_send') ?>" name="comment"></textarea>
							</div>
						</div>
						<div class="form-group text-right d-none" id="btnUpdateArea">
							<button type="button" id="btnUpdate" class="btn btn-primary"><?php echo __('admin.top_update')?></button>
							<input type="hidden" id="updateid" value="">
						</div>
					</div>
					<!--comment-->
				
				<!--vendor tools-->

				<?php } else { ?>

		<!-- Row for All Settings -->
		<div class="row g-3">
		
		  <!--  ADMIN - Program Settings -->
		  <div class="col-lg-4 for-program-tool">
		    <div class="card intg-section-card h-100">
		      <div class="card-header"><i class="bi bi-puzzle me-2 text-primary"></i><?= __('admin.program_settings') ?></div>
		      <div class="card-body">
			        <label class="form-label"><?= __('admin.select_program') ?></label>
			        <select class="form-select form-select-sm program_id1" name="program_id" id="program_id_select">
			          <option value=""><?= __('admin.select_market_program') ?></option>
			          <?php foreach ($programs as $key => $program) { ?>
			            <option 
			            data-commission_type='<?= $program['commission_type'] ?>'
			            data-commission_sale='<?= $program['commission_type'] == 'fixed' ? c_format($program['commission_sale']) : (int)$program['commission_sale']. "%" ?>'
			            data-commission_number_of_click='<?= $program['commission_number_of_click'] ?>'
			            data-commission_click_commission='<?= c_format($program['commission_click_commission']) ?>'
			            data-click_status='<?= $program['click_status'] ?>'
			            data-sale_status='<?= $program['sale_status'] ?>'
			            value="<?= $program['id'] ?>" 
			            <?= (isset($tool) && $tool['program_id'] == $program['id']) ? 'selected' : '' ?>>
			            <?= $program['name'] ?>
			            </option>
			          <?php } ?>
			        </select>
			        <button type="button" class="btn btn-primary btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#addProgram"><?= __('admin.add_program') ?></button>
			        <div class="for-program-tool-details mt-3"></div>
			      </div>
			    </div>
			  </div>

			<script type="text/javascript">
				$(document).on('change', '.program_id1', function(){
					$('.for-program-tool-details').empty();
					if($(this).val() != "") {
						let option = $(this).find("option[value='"+$(this).val()+"']");
						let sale_status = $(option).data('sale_status');
						let sale_badge = (sale_status == 1) ? '<span class="badge bg-success">Enable</span>' : '<span class="badge bg-secondary">Disable</span>';
						let click_status = $(option).data('click_status');
						let click_badge = (click_status == 1) ? '<span class="badge bg-success">Enable</span>' : '<span class="badge bg-secondary">Disable</span>';
						let sale_com = $(option).data('commission_sale');
						let click_com = $(option).data('commission_click_commission')+" per " +$(option).data('commission_number_of_click')+" clicks";

						$('.for-program-tool-details').html(`
							<div class="alert alert-light border-0 p-2 small">
								<div class="mb-2"><strong>Sale:</strong> `+sale_badge+` <span class="badge bg-primary">`+sale_com+`</span></div>
								<div><strong>Click:</strong> `+click_badge+` <span class="badge bg-primary">`+click_com+`</span></div>
							</div>
						`);
					}
				});
				$('.program_id1').trigger('change');
			</script>

			<!-- ADMIN - Action Commission Settings Card -->
			<div class="card intg-section-card for-action-tool">
			  <div class="card-header single-action-integration">
			    <i class="bi bi-bullseye me-2 text-primary"></i><?= __('admin.action_commission_settings') ?>
			  </div>
				  <div class="card-body">
				    <div class="row">
				      <div class="col-sm-6">
				        <div class="form-group">
				          <label class="control-label"><?= __('admin.number_of_action_per_commission') ?></label>
				          <input class="form-control" name="action_click" value="<?= isset($tool) ? $tool['action_click'] : '' ?>" type="number">
				        </div>
				      </div>
				      <div class="col-sm-6">
				        <div class="form-group">
				          <label class="control-label"><?= __('admin.cost_per_action') ?> ($)</label>
				          <input class="form-control" name="action_amount" value="<?= isset($tool) ? $tool['action_amount'] : '' ?>" type="number">
				        </div>
				      </div>
				    </div>

				    <div class="row">
				      <div class="col-sm-6">
				        <div class="form-group">
				          <label class="control-label">
				            <?= __('admin.action_code') ?>
				            <span data-toggle="tooltip" data-original-title="<?= __('admin.code_of_action_comisson_title') ?>"></span>
				          </label>
				          <input class="form-control" id="action_code" name="action_code" value="<?= isset($tool) ? $tool['action_code'] : $randome_code ?>">
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
				  </div>
				</div>

			<!-- ADMIN - General Click Commission Settings Card -->
			<div class="card intg-section-card for-general_click-tool">
			  	<div class="card-header">
			    <i class="bi bi-cursor me-2 text-primary"></i><?= __('admin.general_click_commission_settings') ?>
			 	</div>
					<div class="card-body">
					    <div class="row">
					      <div class="col-sm-6">
					        <div class="form-group">
					          <label class="control-label"><?= __('admin.number_of_click') ?></label>
					          <input class="form-control" name="general_click" value="<?= isset($tool) ? $tool['general_click'] : '' ?>" type="number">
					        </div>
					      </div>
					      <div class="col-sm-6">
					        <div class="form-group">
					          <label class="control-label"><?= __('admin.cost_per_click') ?> ($)</label>
					          <input class="form-control" name="general_amount" value="<?= isset($tool) ? $tool['general_amount'] : '' ?>" type="number">
					        </div>
					      </div>
					    </div>

				    <!-- Nested Code Settings Card -->
				    <div class="card intg-section-card mt-3">
				      <div class="card-header"><i class="bi bi-code-square me-2 text-primary"></i><?= __('admin.code_settings') ?></div>
					      <div class="card-body">
					        <div class="row">
					          <div class="col-sm-6">
					            <div class="form-group">
					              <label class="control-label"><?= __('admin.general_code') ?>
					                <span data-toggle="tooltip" data-original-title="<?= __('admin.code_of_general_click_title') ?>"></span>
					              </label>
					              <input class="form-control" name="general_code" id="general_code" value="<?= isset($tool) ? $tool['general_code'] : $randome_code ?>">
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
					      </div>
					    </div>
					    <!-- Nested Code Settings Card -->
					</div>
				</div>

		  <!-- Terms Settings -->
		  <div class="col-lg-4">
		    <div class="card intg-section-card h-100">
		      <div class="card-header"><i class="bi bi-file-earmark-text me-2 text-primary"></i><?= __('admin.terms_settings') ?></div>
			      <div class="card-body">
			        <label class="form-label"><?= __('admin.terms_and_conditions') ?></label>
			        <textarea placeholder="<?= __('admin.enter_terms_and_conditions') ?>" name="terms" class="form-control form-control-sm summernote-img" rows="4" ng-model='plan.description'><?= isset($tool) ? $tool['terms'] : '' ?></textarea>
			      </div>
			    </div>
			  </div>

		  <!-- Cookies Settings -->
		  <div class="col-lg-4">
		    <div class="card intg-section-card h-100">
		      <div class="card-header"><i class="bi bi-shield-lock me-2 text-primary"></i><?= __('admin.cookies_settings') ?></div>
			      <div class="card-body">
			        <label class="form-label"><?= __('admin.cookie_tracking_mode') ?></label>
			        <select class="form-select form-select-sm cookies_type_select" name="cookies_type">
			          <option value="0" selected><?= __('admin.default') ?></option>
			          <option value="1" <?= isset($tool) && $tool['cookies_type'] == 1 ? 'selected' : '' ?>><?= __('admin.custom') ?></option>
			        </select>
			        <div class="cookies_type_input_rev alert alert-light mt-2 mb-0 py-2 small <?= !isset($tool) || $tool['cookies_type'] == 0 ? '' : 'intg-cookies-hidden' ?>" role="alert">
			          <strong><?= __('admin.default_cookies_tracker_in_days') ?>:</strong> 
			          <span class="badge bg-secondary"><?= $cookie_setting['affiliate_cookie'] ?> <?= __('admin.days') ?></span>
			        </div>
			        <div class="cookies_type_input mt-2 <?= isset($tool) && $tool['cookies_type'] == 1 ? '' : 'intg-cookies-hidden' ?>">
			          <label class="form-label small"><?= __('admin.custom_cookies_tracker_in_days') ?></label>
			          <input class="form-control form-control-sm" type="number" value="<?= isset($tool) ? $tool['custom_cookies'] : '' ?>" name="custom_cookies" min="1" placeholder="<?= __('admin.enter_days') ?>" />
			        </div>
			      </div>
			    </div>
			  </div>
			  
			</div>
			<!-- End Row -->

		  <!-- S2S settings moved to Integration Setup tab -->
		  <input type="hidden" id="s2s_enabled" name="s2s_enabled" value="<?= (isset($tool) && !empty($tool['s2s_enabled'])) ? '1' : '0' ?>">
		  <input type="hidden" id="s2s_direct_mode" name="s2s_direct_mode" value="<?= (isset($tool) && !empty($tool['s2s_direct_mode'])) ? '1' : '0' ?>">
		  <?php if(isset($tool) && !empty($tool['api_key'])): ?>
		    <input type="hidden" name="api_key" value="<?= htmlspecialchars($tool['api_key']) ?>">
		  <?php endif; ?>

				<?php } ?>
			</div>

		</div>
		<!--End right side + left side-->



		<div id="banners_section">
		<?php if($type == 'banner'){ ?>
		<!-- Banner images section -->
		<div class="card intg-section-card mt-3">
		    <div class="card-header"><i class="bi bi-images me-2 text-primary"></i><?= __('admin.banner_images') ?></div>
			    <div class="card-body">
			        <div class="form-group">
			            <div class="table-responsive">
			                <table class="table table-bordered banner-table align-middle">
			                    <thead class="table-light">
			                        <tr>
			                            <th><?= __('admin.featured_image') ?></th>
			                            <th class="text-center intg-banner-th-dim"><?= __('admin.image_dimensions') ?></th>
			                            <th class="text-center intg-banner-th-action"><?= __('admin.actions') ?></th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                        <?php foreach ($tool['ads'] as $key => $ads) { ?>
			                            <tr>
			                                <td>
			                                    <input type="hidden" name="keep_ads[]" value="<?= $ads['id'] ?>">
			                                    <input type="hidden" name="custom_banner_ext[]" value="<?= $ads['value'] ?>">
			                                    <div class="d-flex align-items-center gap-3">
			                                        <img class="img-thumbnail campaign-img integration_css-banner-thumb h-auto flex-shrink-0" src="<?= $ads['value'] ?>">
			                                        <input type="file" accept="image/*" class="form-control form-control-sm file-input" name="custom_banner[]">
			                                    </div>
			                                </td>
			                                <td class="text-center">
			                                    <span class="badge bg-primary size-display"><?= $ads['size'] ?></span>
			                                    <input type="hidden" class="size-input" value="<?= $ads['size'] ?>" name="custom_banner_size[]">
			                                </td>
			                                <td class="text-center">
			                                    <button type="button" class="btn btn-sm btn-danger remove-custom-image"><i class="bi bi-trash-fill"></i></button>
			                                </td>
			                            </tr>
			                        <?php } ?>
			                        <?php if (!isset($tool['ads']) || empty($tool['ads'])) { ?>
			                            <tr>
			                                <td>
			                                    <div class="d-flex align-items-center gap-3">
			                                        <img class="img-thumbnail campaign-img campaign_default_image integration_css-banner-thumb h-auto flex-shrink-0" src="<?= base_url('assets/images/no_product_image.png') ?>">
			                                        <input type="file" accept="image/*" class="form-control form-control-sm file-input" name="custom_banner[]">
			                                    </div>
			                                    <input type="hidden" name="custom_banner_ext[]" value="">
			                                    <input type="hidden" name="keep_ads[]" value="0">
			                                </td>
			                                <td class="text-center">
			                                    <span class="badge bg-primary size-display"></span>
			                                    <input type="hidden" class="size-input" name="custom_banner_size[]">
			                                </td>
			                                <td class="text-center">
			                                    <button type="button" class="btn btn-sm btn-danger remove-custom-image"><i class="bi bi-trash"></i></button>
			                                </td>
			                            </tr>
			                        <?php } ?>
			                    </tbody>
			                </table>
			            </div>
			            <div class="text-right">
			                <button type="button" class="btn add-banner btn-primary btn-sm"><?= __('admin.add_banner') ?></button>
			            </div>
			        </div>
			    </div>
			</div>

			<?php } else if($type == 'text_ads'){ ?>
				<?php 
				$_text_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
				?>
		<div class="card intg-section-card mt-3">
		    <div class="card-header"><i class="bi bi-fonts me-2 text-success"></i><?= __('admin.content_settings') ?></div>
		    <div class="card-body">
		        <div class="row g-3">
		          <div class="col-lg-8">
		            <label class="form-label small fw-semibold"><?= __('admin.content') ?></label>
		            <textarea class="form-control form-control-sm" rows="7" name="text_ads_content"><?= isset($_text_ads['value']) ? $_text_ads['value'] : '' ?></textarea>
		          </div>
		          <div class="col-lg-4">
		            <label class="form-label small fw-semibold"><?= __('admin.text_size_px') ?></label>
		            <input class="form-control form-control-sm mb-3" name="text_size" value="<?= isset($_text_ads['text_size']) ? $_text_ads['text_size'] : '' ?>">
		            <div class="row g-2">
		              <div class="col-4">
		                <label class="form-label small fw-semibold"><?= __('admin.text_color') ?></label>
		                <input class="form-control form-control-sm form-control-color w-100" name="text_color" type="color" value="<?= isset($_text_ads['text_color']) ? $_text_ads['text_color'] : '' ?>">
		              </div>
		              <div class="col-4">
		                <label class="form-label small fw-semibold"><?= __('admin.background_color') ?></label>
		                <input class="form-control form-control-sm form-control-color w-100" name="text_bg_color" type="color" value="<?= isset($_text_ads['text_bg_color']) ? $_text_ads['text_bg_color'] : '' ?>">
		              </div>
		              <div class="col-4">
		                <label class="form-label small fw-semibold"><?= __('admin.border_color') ?></label>
		                <input class="form-control form-control-sm form-control-color w-100" name="text_border_color" type="color" value="<?= isset($_text_ads['text_border_color']) ? $_text_ads['text_border_color'] : '' ?>">
		              </div>
		            </div>
		          </div>
		        </div>
		    </div>
		</div>

			<?php } else if($type == 'link_ads'){ ?>
			<?php 
			$link_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
			?>
		<div class="card intg-section-card mt-3">
		    <div class="card-header"><i class="bi bi-link-45deg me-2 text-purple"></i><?= __('admin.link_settings') ?></div>
		    <div class="card-body">
		        <label class="form-label small fw-semibold"><?= __('admin.link_title') ?></label>
		        <input class="form-control form-control-sm" name="link_title" value="<?= isset($link_ads['value']) ? $link_ads['value'] : '' ?>">
		    </div>
		</div>

			<?php } else if($type == 'video_ads'){ ?>
			<?php 
			$video_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
			$video_source_type = isset($video_ads['video_source_type']) ? $video_ads['video_source_type'] : 'youtube_vimeo';
			?>
		<div class="card intg-section-card mt-3">
		    <div class="card-header"><i class="bi bi-camera-video me-2 text-warning"></i><?= __('admin.video_settings') ?></div>
		    <div class="card-body">
					<div class="form-group mb-4">
						<label class="control-label fw-semibold"><?= __('admin.video_source_type') ?></label>
						<div class="row g-2 mt-2">
							<div class="col-md-4">
								<div class="form-check card border p-3 h-100">
									<input class="form-check-input video-source-radio" type="radio" name="video_source_type" id="source_youtube" value="youtube_vimeo" <?= $video_source_type == 'youtube_vimeo' ? 'checked' : '' ?>>
									<label class="form-check-label d-block text-center" for="source_youtube">
										<i class="bi bi-play-circle fs-3 text-danger mb-2"></i>
										<div class="fw-semibold"><?= __('admin.youtube_vimeo') ?></div>
										<small class="text-muted"><?= __('admin.paste_video_url') ?></small>
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-check card border p-3 h-100">
									<input class="form-check-input video-source-radio" type="radio" name="video_source_type" id="source_mp4_url" value="mp4_url" <?= $video_source_type == 'mp4_url' ? 'checked' : '' ?>>
									<label class="form-check-label d-block text-center" for="source_mp4_url">
										<i class="bi bi-link-45deg fs-3 text-primary mb-2"></i>
										<div class="fw-semibold"><?= __('admin.mp4_url') ?></div>
										<small class="text-muted"><?= __('admin.direct_mp4_link') ?></small>
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-check card border p-3 h-100">
									<input class="form-check-input video-source-radio" type="radio" name="video_source_type" id="source_mp4_upload" value="mp4_upload" <?= $video_source_type == 'mp4_upload' ? 'checked' : '' ?>>
									<label class="form-check-label d-block text-center" for="source_mp4_upload">
										<i class="bi bi-upload fs-3 text-success mb-2"></i>
										<div class="fw-semibold"><?= __('admin.upload_mp4') ?></div>
										<small class="text-muted"><?= __('admin.upload_video_file') ?></small>
									</label>
								</div>
							</div>
						</div>
					</div>

			        <div class="row">
			            <div class="col-6">
							<div id="youtube_vimeo_section" class="video-source-section mb-3 <?= $video_source_type != 'youtube_vimeo' ? 'intg-video-section-hidden' : '' ?>">
								<label class="control-label"><?= __('admin.video_link') ?></label>
								<div class="input-group">
									<span class="input-group-text bg-danger text-white"><i class="bi bi-camera-video"></i></span>
									<input class="form-control parse-video" name="video_link" value="<?= ($video_source_type == 'youtube_vimeo' && isset($video_ads['value'])) ? $video_ads['value'] : '' ?>" placeholder="<?= __('admin.enter_video_url_placeholder') ?>">
								</div>
								<small class="text-muted"><i class="bi bi-info-circle me-1"></i><?= __('admin.supports_youtube_vimeo') ?></small>
							</div>

							<div id="mp4_url_section" class="video-source-section mb-3 <?= $video_source_type != 'mp4_url' ? 'intg-video-section-hidden' : '' ?>">
								<label class="control-label"><?= __('admin.mp4_video_url') ?></label>
								<div class="input-group">
									<span class="input-group-text bg-primary text-white"><i class="bi bi-camera-video"></i></span>
									<input class="form-control parse-video-mp4" name="mp4_url" value="<?= ($video_source_type == 'mp4_url' && isset($video_ads['value'])) ? $video_ads['value'] : '' ?>" placeholder="https://example.com/video.mp4">
								</div>
								<small class="text-muted"><?= __('admin.enter_direct_mp4_url') ?></small>
							</div>

							<div id="mp4_upload_section" class="video-source-section mb-3 <?= $video_source_type != 'mp4_upload' ? 'intg-video-section-hidden' : '' ?>">
								<label class="control-label"><?= __('admin.upload_mp4_file') ?></label>
								<input type="file" class="form-control video-upload-input" name="video_file" accept="video/mp4,video/webm,video/ogg">
								<small class="text-muted"><?= __('admin.max_video_size') ?></small>
								<?php if($video_source_type == 'mp4_upload' && isset($video_ads['value']) && !empty($video_ads['value'])): ?>
									<div class="mt-2 p-2 bg-light rounded">
										<small class="text-success"><i class="bi bi-check-circle me-1"></i><?= __('admin.current_video') ?>: <?= basename($video_ads['value']) ?></small>
										<input type="hidden" name="existing_video" value="<?= htmlspecialchars($video_ads['value']) ?>">
									</div>
								<?php endif; ?>
							</div>

							<div class="form-group">
								<label class="control-label"><?= __('admin.autoplay') ?></label>
								<div>
									<label class="radio-inline">
										<input type="radio" checked="" name="autoplay" value="0"> <?= __('admin.disable') ?>
									</label>
									<label class="radio-inline">
										<input type="radio" <?= (isset($video_ads) && $video_ads['autoplay']) ? 'checked' : '' ?> name="autoplay" value="1"> <?= __('admin.enable') ?>
									</label>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-6">
									<label class="control-label"><?= __('admin.height_px') ?></label>
									<input class="form-control" name="video_height" value="<?= isset($video_ads['video_height']) ? $video_ads['video_height'] : '315' ?>">
								</div>
								<div class="col-sm-6">
									<label class="control-label"><?= __('admin.width_px') ?></label>
									<input class="form-control" name="video_width" value="<?= isset($video_ads['video_width']) ? $video_ads['video_width'] : '560' ?>">
								</div>	
							</div>

							<div class="form-group mt-3">
								<label class="control-label"><?= __('admin.button_text') ?></label>
								<input class="form-control" name="button_text" value="<?= isset($video_ads['size']) ? $video_ads['size'] : '' ?>">
							</div>
						</div>
					        
					    <div class="col-6">
					        <div class="card">
					            <div class="card-header">
					               <?= __('admin.video_preview') ?>
					            </div>
					            <div class="card-body">
					                <div class="video-preview-container intg-video-preview">
										<div class="text-center text-muted py-5">
											<i class="bi bi-camera-video fs-1 mb-3 opacity-50"></i>
											<p class="mb-0"><?= __('admin.video_preview_here') ?></p>
										</div>
									</div>
					            </div>
					        </div>
					    </div> 
					</div>
			    </div>
			</div>
			<script>
				function showVideoPlaceholder() {
					document.querySelector('.video-preview-container').innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-camera-video fs-1 mb-3 opacity-50"></i><p class="mb-0"><?= __("admin.video_preview_here") ?></p></div>';
				}

				function parseVideoURL() {
					const sourceType = document.querySelector('input[name="video_source_type"]:checked').value;
					const container = document.querySelector('.video-preview-container');
					
					if(sourceType === 'youtube_vimeo') {
						const videoURL = document.querySelector('.parse-video').value;
						if(!videoURL) { showVideoPlaceholder(); return; }

						if(videoURL.includes('youtube.com') || videoURL.includes('youtu.be')) {
							try {
								let videoID = null;
								if(videoURL.includes('youtube.com')) {
									const url = new URL(videoURL);
									videoID = url.searchParams.get('v');
								} else {
									videoID = videoURL.split('/').pop().split('?')[0];
								}
								if(videoID) {
									container.innerHTML = `<div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/${videoID}" frameborder="0" allowfullscreen></iframe></div>`;
								} else {
									container.innerHTML = '<div class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle fs-1 mb-2"></i><p class="mb-0"><?= __("admin.invalid_youtube_url") ?></p></div>';
								}
							} catch(e) {
								container.innerHTML = '<div class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle fs-1 mb-2"></i><p class="mb-0"><?= __("admin.invalid_url_format") ?></p></div>';
							}
						} else if(videoURL.includes('vimeo.com')) {
							const match = videoURL.match(/vimeo\.com\/(?:video\/)?(\d+)/);
							if(match && match[1]) {
								container.innerHTML = `<div class="ratio ratio-16x9"><iframe src="https://player.vimeo.com/video/${match[1]}" frameborder="0" allowfullscreen></iframe></div>`;
							} else {
								container.innerHTML = '<div class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle fs-1 mb-2"></i><p class="mb-0"><?= __("admin.invalid_vimeo_url") ?></p></div>';
							}
						} else if(videoURL.includes('aparat.com')) {
							const match = videoURL.match(/aparat\.com\/v\/([a-zA-Z0-9]+)/i);
							if(match && match[1]) {
								container.innerHTML = `<div class="ratio ratio-16x9"><iframe src="https://www.aparat.com/video/video/embed/videohash/${match[1]}/vt/frame" frameborder="0" allowfullscreen></iframe></div>`;
							} else {
								container.innerHTML = '<div class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle fs-1 mb-2"></i><p class="mb-0"><?= __("admin.invalid_video_url") ?></p></div>';
							}
						} else if(videoURL.includes('rutube.ru')) {
							const match = videoURL.match(/rutube\.ru\/video\/([a-zA-Z0-9]+)\//i);
							if(match && match[1]) {
								container.innerHTML = `<div class="ratio ratio-16x9"><iframe src="https://rutube.ru/play/embed/${match[1]}" frameborder="0" allowfullscreen></iframe></div>`;
							} else {
								container.innerHTML = '<div class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle fs-1 mb-2"></i><p class="mb-0"><?= __("admin.invalid_video_url") ?></p></div>';
							}
						} else if(videoURL.includes('dailymotion.com')) {
							const match = videoURL.match(/dailymotion\.com\/video\/([a-zA-Z0-9]+)/i);
							if(match && match[1]) {
								container.innerHTML = `<div class="ratio ratio-16x9"><iframe src="https://www.dailymotion.com/embed/video/${match[1]}" frameborder="0" allowfullscreen></iframe></div>`;
							} else {
								container.innerHTML = '<div class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle fs-1 mb-2"></i><p class="mb-0"><?= __("admin.invalid_video_url") ?></p></div>';
							}
						} else {
							container.innerHTML = '<div class="text-center text-warning py-5"><i class="bi bi-exclamation-triangle fs-1 mb-2"></i><p class="mb-0"><?= __("admin.unsupported_video_type") ?></p></div>';
						}
					} else if(sourceType === 'mp4_url') {
						const mp4URL = document.querySelector('.parse-video-mp4').value;
						if(!mp4URL) { showVideoPlaceholder(); return; }
						container.innerHTML = `<div class="ratio ratio-16x9"><video controls class="w-100 h-100"><source src="${mp4URL}" type="video/mp4"><?= __("admin.browser_not_support_video") ?></video></div>`;
					}
				}

				function handleVideoSourceChange() {
					const sourceType = document.querySelector('input[name="video_source_type"]:checked').value;
					document.querySelectorAll('.video-source-section').forEach(el => el.classList.add('intg-video-section-hidden'));
					document.getElementById(sourceType + '_section').classList.remove('intg-video-section-hidden');
					showVideoPlaceholder();
					parseVideoURL();
				}

				document.addEventListener("DOMContentLoaded", function() {
					document.querySelectorAll('.video-source-radio').forEach(el => {
						el.addEventListener('change', handleVideoSourceChange);
					});
					
					const parseVideoInput = document.querySelector('.parse-video');
					if(parseVideoInput) parseVideoInput.addEventListener('input', parseVideoURL);
					
					const mp4Input = document.querySelector('.parse-video-mp4');
					if(mp4Input) mp4Input.addEventListener('input', parseVideoURL);
					
					const videoUploadInput = document.querySelector('.video-upload-input');
					if(videoUploadInput) {
						videoUploadInput.addEventListener('change', function() {
							const file = this.files[0];
							if(file) {
								const url = URL.createObjectURL(file);
								document.querySelector('.video-preview-container').innerHTML = `<div class="ratio ratio-16x9"><video controls class="w-100 h-100"><source src="${url}" type="${file.type}"><?= __("admin.browser_not_support_video") ?></video></div>`;
							}
						});
					}

					parseVideoURL();
				});
			</script>

			<?php } ?>
		</div>

					</div>
              </div>

              <div class="tab-pane fade" id="menu2" role="tabpanel" aria-labelledby="recurring-tab">
                <div class="card intg-section-card mb-3">
                  <div class="card-header"><i class="bi bi-arrow-repeat me-2 text-primary"></i><?= __('admin.recursion') ?></div>
                  <div class="card-body">
				<div class="form-group mb-0">
						<?php  $recursion = $tool['recursion'];  ?>

						<select name="recursion" class="form-control" id="recursion_type">
							<option value=""><?= __('admin.select_recursion') ?></option>
							<option <?php if($recursion == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
							<option <?php if($recursion == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
							<option <?php if($recursion == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
							<option <?php if($recursion == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
							<option <?php if($recursion == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
						</select>
					</div>
					<div class="form-group custom_time <?php echo ($recursion != 'custom_time') ? 'hide' : '';  ?>">

						<?php
						$minutes = $tool['recursion_custom_time'];

						$day = floor ($minutes / 1440);
						$hour = floor (($minutes - $day * 1440) / 60);
						$minute = $minutes - ($day * 1440) - ($hour * 60);
						?>
						<input type="hidden" name="recursion_custom_time" value="<?php echo $minutes; ?>">
						<div class="row">
							<div class="col-sm-4">
								<label class="control-label"><?= __('admin.days') ?> : </label>
								<input placeholder="<?= __('admin.days') ?>" type="number" class="form-control" value="<?= $day ? $day : '' ?>" id="recur_day" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
							</div>                      
							<div class="col-sm-4">
								<label class="control-label"><?= __('admin.hours') ?> : </label>
								<select class="form-control" id="recur_hour">
									<?php 
									for ($x = 0; $x <= 23; $x++) {
										$selected = ($x == $hour ) ? 'selected="selected"' : '';
										echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
									}
									?>
								</select>
							</div>                      
							<div class="col-sm-4">
								<label class="control-label"><?= __('admin.minutes') ?> : </label>
								<select class="form-control" id="recur_minute">
									<?php 
									for ($x = 0; $x <= 59; $x++) {
										$selected = ($x == $minute ) ? 'selected="selected"' : '';
										echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
									}
									?>
								</select>
							</div>                      
						</div>                                  
					</div>

					<br>
					<div class="endtime-chooser row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label d-block"><?= __('admin.choose_custom_endtime') ?> <input <?= $tool['recursion_endtime'] ? 'checked' : '' ?>  id='setCustomTime' name='recursion_endtime_status' type="checkbox"> </label>
								<div class='custom_time_container <?= !$tool['recursion_endtime'] ? 'intg-customtime-hidden' : '' ?>'>
									<input type="text" class="form-control" value="<?= $tool['recursion_endtime'] ? date("d-m-Y H:i",strtotime($tool['recursion_endtime'])) : '' ?>" name="recursion_endtime" id="endtime" placeholder="<?= __('admin.choose_endtime') ?>" >
								</div>
							</div>
						</div>
					</div>
                  </div>
                </div>
				</div>

<!-- ==================== INTEGRATION SETUP TAB ==================== -->
              <div class="tab-pane fade" id="integration-setup" role="tabpanel" aria-labelledby="integration-setup-tab">

<?php $current_method = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel'; ?>
<input type="hidden" name="integration_method" id="integration_method" value="<?= $current_method ?>">

<!-- Method Selection Cards -->
<div class="card intg-section-card mb-3">
  <div class="card-header"><i class="bi bi-plug me-2 text-primary"></i><?= __('admin.integration_method_choose') ?></div>
  <div class="card-body">
    <p class="text-muted mb-3"><?= __('admin.integration_method_choose_desc') ?></p>
    <div class="row g-3">
      <!-- JS Pixel Card -->
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 intg-method-card <?= $current_method == 'js_pixel' ? 'border-primary shadow' : '' ?>" data-method="js_pixel" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="bi bi-code-slash fs-1 text-primary"></i></div>
            <h6 class="fw-bold mb-1"><?= __('admin.integration_method_js_pixel') ?></h6>
            <small class="text-muted"><?= __('admin.integration_method_js_pixel_desc') ?></small>
          </div>
          <?php if($current_method == 'js_pixel'): ?><div class="card-footer bg-primary text-white text-center py-1 small fw-bold"><i class="bi bi-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
      <!-- S2S API Card -->
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 intg-method-card <?= $current_method == 's2s' ? 'border-warning shadow' : '' ?>" data-method="s2s" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="bi bi-hdd-rack fs-1 text-warning"></i></div>
            <h6 class="fw-bold mb-1"><?= __('admin.integration_method_s2s') ?></h6>
            <small class="text-muted"><?= __('admin.integration_method_s2s_desc') ?></small>
          </div>
          <?php if($current_method == 's2s'): ?><div class="card-footer bg-warning text-dark text-center py-1 small fw-bold"><i class="bi bi-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
      <!-- Mobile App Card -->
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 intg-method-card <?= $current_method == 's2s_direct' ? 'border-info shadow' : '' ?>" data-method="s2s_direct" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="bi bi-phone fs-1 text-info"></i></div>
            <h6 class="fw-bold mb-1"><?= __('admin.integration_method_mobile') ?></h6>
            <small class="text-muted"><?= __('admin.integration_method_mobile_desc') ?></small>
          </div>
          <?php if($current_method == 's2s_direct'): ?><div class="card-footer bg-info text-white text-center py-1 small fw-bold"><i class="bi bi-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
      <!-- Postback Card -->
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 intg-method-card <?= $current_method == 'postback' ? 'border-secondary shadow' : '' ?>" data-method="postback" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="bi bi-arrow-left-right fs-1 text-secondary"></i></div>
            <h6 class="fw-bold mb-1"><?= __('admin.integration_method_postback') ?></h6>
            <small class="text-muted"><?= __('admin.integration_method_postback_desc') ?></small>
          </div>
          <?php if($current_method == 'postback'): ?><div class="card-footer bg-secondary text-white text-center py-1 small fw-bold"><i class="bi bi-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
      <!-- Conversion API Card -->
      <div class="col-lg col-md-6">
        <div class="card h-100 border-2 intg-method-card <?= $current_method == 'conversion_api' ? 'border-dark shadow' : '' ?>" data-method="conversion_api" role="button">
          <div class="card-body text-center p-3">
            <div class="mb-2"><i class="bi bi-braces fs-1 text-dark"></i></div>
            <h6 class="fw-bold mb-1"><?= __('admin.integration_method_conv_api') ?></h6>
            <small class="text-muted"><?= __('admin.integration_method_conv_api_desc') ?></small>
          </div>
          <?php if($current_method == 'conversion_api'): ?><div class="card-footer bg-dark text-white text-center py-1 small fw-bold"><i class="bi bi-check-circle me-1"></i><?= __('admin.active') ?></div><?php endif; ?>
        </div>
      </div>
    </div>

    <div class="alert alert-light border small mb-0 mt-3 py-2">
      <i class="bi bi-lightbulb text-warning me-1"></i> <?= __('admin.integration_multi_method_note') ?>
    </div>
  </div>
</div>

<!-- ===== METHOD CONTENT: JS PIXEL ===== -->
<div class="intg-method-content" id="method-js_pixel" style="<?= $current_method == 'js_pixel' ? '' : 'display:none' ?>">
  <div class="card intg-section-card border-start border-primary border-3">
    <div class="card-header bg-primary bg-gradient text-white"><i class="bi bi-code-slash me-2"></i><?= __('admin.integration_method_js_pixel') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="bi bi-diagram-3 me-1"></i><?= __('admin.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-primary rounded-pill px-3 py-2"><?= __('admin.flow_js_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-primary rounded-pill px-3 py-2"><?= __('admin.flow_js_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-primary rounded-pill px-3 py-2"><?= __('admin.flow_js_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-primary rounded-pill px-3 py-2"><?= __('admin.flow_js_step4') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_js_step5') ?></span>
        </div>
      </div>
      <div class="alert alert-success border-0 mb-3">
        <h6 class="alert-heading fw-bold"><i class="bi bi-check-circle me-1"></i> <?= __('admin.js_pixel_ready_title') ?></h6>
        <p class="mb-0 small"><?= __('admin.js_pixel_ready_desc') ?></p>
      </div>
      <?php if(isset($tool)): ?>
      <button type="button" class="btn btn-outline-primary btn-show-code-wizard" data-id="<?= $tool['id'] ?>">
        <i class="bi bi-code-slash me-1"></i> <?= __('admin.js_pixel_view_code') ?>
      </button>
      <?php else: ?>
      <div class="alert alert-info py-2 small mb-0"><i class="bi bi-info-circle me-1"></i> <?= __('admin.js_pixel_save_first') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ===== METHOD CONTENT: S2S API ===== -->
<div class="intg-method-content" id="method-s2s" style="<?= $current_method == 's2s' ? '' : 'display:none' ?>">
  <div class="card intg-section-card border-start border-warning border-3">
    <div class="card-header bg-warning bg-gradient text-dark"><i class="bi bi-hdd-rack me-2"></i><?= __('admin.integration_method_s2s') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="bi bi-diagram-3 me-1"></i><?= __('admin.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step4') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step5') ?></span>
        </div>
      </div>
      <div class="alert alert-info border-0 mb-3">
        <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-info-circle me-1"></i> <?= __('admin.s2s_how_it_works') ?></h6>
        <p class="mb-2 small"><?= __('admin.s2s_how_it_works_desc') ?></p>
        <ol class="mb-0 small">
          <li class="mb-1"><?= __('admin.s2s_step_1') ?></li>
          <li class="mb-1"><?= __('admin.s2s_step_2') ?></li>
          <li class="mb-1"><?= __('admin.s2s_step_3') ?></li>
          <li><?= __('admin.s2s_step_4') ?></li>
        </ol>
      </div>

      <?php if(isset($tool) && !empty($tool['api_key'])): ?>
      <!-- API Key -->
      <div class="card mb-3 border">
        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-key me-2 text-warning"></i><?= __('admin.s2s_api_key') ?></h6></div>
        <div class="card-body">
          <small class="text-muted d-block mb-2"><?= __('admin.s2s_api_key_desc') ?></small>
          <div class="input-group mb-2">
            <input type="text" class="form-control bg-light font-monospace" id="s2s_api_key_display_s2s" value="<?= htmlspecialchars($tool['api_key']) ?>" readonly>
            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('s2s_api_key_display_s2s')"><i class="bi bi-copy me-1"></i> <?= __('admin.s2s_copy_to_clipboard') ?></button>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="regenerate_api_key_s2s" name="regenerate_api_key" value="1">
            <label class="form-check-label small text-danger" for="regenerate_api_key_s2s"><i class="bi bi-exclamation-triangle me-1"></i><?= __('admin.s2s_regenerate_api_key') ?></label>
          </div>
        </div>
      </div>
      <!-- Endpoint URL -->
      <div class="card mb-3 border">
        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-link-45deg me-2 text-success"></i><?= __('admin.s2s_endpoint_url') ?></h6></div>
        <div class="card-body">
          <small class="text-muted d-block mb-2"><?= __('admin.s2s_endpoint_desc') ?></small>
          <div class="input-group">
            <span class="input-group-text bg-success text-white fw-bold">POST</span>
            <input type="text" class="form-control bg-light font-monospace" id="s2s_endpoint_s2s" value="<?= base_url('integration/s2sConvert') ?>" readonly>
            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('s2s_endpoint_s2s')"><i class="bi bi-copy me-1"></i> <?= __('admin.s2s_copy_to_clipboard') ?></button>
          </div>
        </div>
      </div>
      <!-- Documentation -->
      <div class="card mb-0 border">
        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-book me-2 text-info"></i><?= __('admin.s2s_documentation') ?></h6></div>
        <div class="card-body">
          <p class="fw-bold mb-2"><?= __('admin.s2s_required_params') ?>:</p>
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-3">
              <thead class="table-dark"><tr><th><?= __('admin.parameter') ?></th><th><?= __('admin.type') ?></th><th><?= __('admin.description') ?></th></tr></thead>
              <tbody>
                <tr><td><code class="text-danger">api_key</code></td><td>string</td><td><?= __('admin.s2s_param_api_key_desc') ?></td></tr>
                <tr><td><code class="text-danger">click_token</code></td><td>string</td><td><?= __('admin.s2s_param_click_token_desc') ?></td></tr>
                <tr><td><code class="text-danger">order_id</code></td><td>string</td><td><?= __('admin.s2s_param_order_id_desc') ?></td></tr>
                <tr><td><code class="text-danger">order_total</code></td><td>number</td><td><?= __('admin.s2s_param_order_total_desc') ?></td></tr>
                <tr><td><code class="text-danger">order_currency</code></td><td>string</td><td><?= __('admin.s2s_param_order_currency_desc') ?></td></tr>
              </tbody>
            </table>
          </div>
          <p class="fw-bold mb-2"><?= __('admin.s2s_example_curl') ?>:</p>
          <pre class="bg-dark text-light p-3 rounded mb-3 intg-api-code">curl -X POST <?= base_url('integration/s2sConvert') ?> \
  -d "api_key=<?= htmlspecialchars($tool['api_key']) ?>" \
  -d "click_token=CLICK_TOKEN_FROM_URL" \
  -d "order_id=ORD-12345" \
  -d "order_total=99.99" \
  -d "order_currency=USD"</pre>
          <p class="fw-bold mb-2"><?= __('admin.s2s_response_examples') ?>:</p>
          <div class="row">
            <div class="col-md-4 mb-2"><div class="card border-success"><div class="card-header bg-success text-white py-1 small fw-bold"><?= __('admin.success') ?></div><div class="card-body py-2"><pre class="mb-0 small">{"status":"success","message":"S2S conversion recorded successfully"}</pre></div></div></div>
            <div class="col-md-4 mb-2"><div class="card border-warning"><div class="card-header bg-warning text-dark py-1 small fw-bold"><?= __('admin.duplicate') ?></div><div class="card-body py-2"><pre class="mb-0 small">{"status":"duplicate","message":"Order already recorded"}</pre></div></div></div>
            <div class="col-md-4 mb-2"><div class="card border-danger"><div class="card-header bg-danger text-white py-1 small fw-bold"><?= __('admin.error') ?></div><div class="card-body py-2"><pre class="mb-0 small">{"status":"error","message":"Invalid API key"}</pre></div></div></div>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="alert alert-warning py-2 small mb-0"><i class="bi bi-info-circle me-1"></i> <?= __('admin.s2s_save_first') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ===== METHOD CONTENT: MOBILE APP (S2S DIRECT) ===== -->
<div class="intg-method-content" id="method-s2s_direct" style="<?= $current_method == 's2s_direct' ? '' : 'display:none' ?>">
  <div class="card intg-section-card border-start border-info border-3">
    <div class="card-header bg-info bg-gradient text-white"><i class="bi bi-phone me-2"></i><?= __('admin.integration_method_mobile') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="bi bi-diagram-3 me-1"></i><?= __('admin.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-info rounded-pill px-3 py-2"><?= __('admin.flow_mobile_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-info rounded-pill px-3 py-2"><?= __('admin.flow_mobile_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-info rounded-pill px-3 py-2"><?= __('admin.flow_mobile_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_mobile_step4') ?></span>
        </div>
      </div>
      <div class="alert alert-info border-0 mb-3">
        <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-info-circle me-1"></i> <?= __('admin.s2s_direct_mode_how') ?></h6>
        <ol class="mb-0 small">
          <li class="mb-1"><?= __('admin.s2s_direct_step_1') ?></li>
          <li class="mb-1"><?= __('admin.s2s_direct_step_2') ?></li>
          <li><?= __('admin.s2s_direct_step_3') ?></li>
        </ol>
      </div>

      <?php if(isset($tool) && !empty($tool['api_key'])): ?>
      <!-- API Key -->
      <div class="card mb-3 border">
        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-key me-2 text-warning"></i><?= __('admin.s2s_api_key') ?></h6></div>
        <div class="card-body">
          <small class="text-muted d-block mb-2"><?= __('admin.s2s_api_key_desc') ?></small>
          <div class="input-group mb-2">
            <input type="text" class="form-control bg-light font-monospace" id="s2s_api_key_display_mobile" value="<?= htmlspecialchars($tool['api_key']) ?>" readonly>
            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('s2s_api_key_display_mobile')"><i class="bi bi-copy me-1"></i> <?= __('admin.s2s_copy_to_clipboard') ?></button>
          </div>
        </div>
      </div>
      <!-- Conversion Endpoint -->
      <div class="card mb-3 border">
        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-link-45deg me-2 text-success"></i><?= __('admin.s2s_endpoint_url') ?></h6></div>
        <div class="card-body">
          <small class="text-muted d-block mb-2"><?= __('admin.s2s_endpoint_desc') ?></small>
          <div class="input-group">
            <span class="input-group-text bg-success text-white fw-bold">POST</span>
            <input type="text" class="form-control bg-light font-monospace" id="s2s_endpoint_mobile" value="<?= base_url('integration/s2sConvert') ?>" readonly>
            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('s2s_endpoint_mobile')"><i class="bi bi-copy me-1"></i> <?= __('admin.s2s_copy_to_clipboard') ?></button>
          </div>
        </div>
      </div>
      <!-- Direct Mode Docs -->
      <div class="card mb-3 border">
        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-book me-2 text-info"></i><?= __('admin.s2s_documentation') ?></h6></div>
        <div class="card-body">
          <p class="fw-bold mb-2"><?= __('admin.s2s_direct_mode_params') ?>:</p>
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-3">
              <thead class="table-dark"><tr><th><?= __('admin.parameter') ?></th><th><?= __('admin.type') ?></th><th><?= __('admin.description') ?></th></tr></thead>
              <tbody>
                <tr><td><code class="text-danger">api_key</code></td><td>string</td><td><?= __('admin.s2s_param_api_key_desc') ?></td></tr>
                <tr><td><code class="text-danger">affiliate_id</code></td><td>integer</td><td><?= __('admin.s2s_param_affiliate_id_desc') ?></td></tr>
                <tr><td><code class="text-danger">campaign_id</code></td><td>integer</td><td><?= __('admin.s2s_param_campaign_id_desc') ?></td></tr>
                <tr><td><code class="text-danger">order_id</code></td><td>string</td><td><?= __('admin.s2s_param_order_id_desc') ?></td></tr>
                <tr><td><code class="text-danger">order_total</code></td><td>number</td><td><?= __('admin.s2s_param_order_total_desc') ?></td></tr>
                <tr><td><code class="text-danger">order_currency</code></td><td>string</td><td><?= __('admin.s2s_param_order_currency_desc') ?></td></tr>
              </tbody>
            </table>
          </div>
          <p class="fw-bold mb-2"><?= __('admin.s2s_example_curl') ?>:</p>
          <pre class="bg-dark text-light p-3 rounded mb-3 intg-api-code">curl -X POST <?= base_url('integration/s2sConvert') ?> \
  -H "Content-Type: application/json" \
  -d '{"api_key":"<?= htmlspecialchars($tool['api_key']) ?>","affiliate_id":123,"campaign_id":<?= $tool['id'] ?>,"order_id":"ORD-12345","order_total":99.99,"order_currency":"USD"}'</pre>

          <hr class="my-3">
          <p class="fw-bold mb-2"><?= __('admin.s2s_register_click_endpoint') ?>:</p>
          <div class="input-group mb-2">
            <span class="input-group-text bg-info text-white fw-bold">POST</span>
            <input type="text" class="form-control bg-light font-monospace" id="s2s_register_click_mobile" value="<?= base_url('integration/s2sRegisterClick') ?>" readonly>
            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('s2s_register_click_mobile')"><i class="bi bi-copy me-1"></i> <?= __('admin.s2s_copy_to_clipboard') ?></button>
          </div>
          <pre class="bg-dark text-light p-3 rounded mb-3 intg-api-code">curl -X POST <?= base_url('integration/s2sRegisterClick') ?> \
  -H "Content-Type: application/json" \
  -d '{"api_key":"<?= htmlspecialchars($tool['api_key']) ?>","campaign_id":<?= $tool['id'] ?>,"affiliate_id":123,"visitor_id":"optional-visitor-uuid"}'</pre>

          <p class="fw-bold mb-2"><?= __('admin.s2s_response_examples') ?>:</p>
          <div class="row">
            <div class="col-md-4 mb-2"><div class="card border-success"><div class="card-header bg-success text-white py-1 small fw-bold"><?= __('admin.success') ?></div><div class="card-body py-2"><pre class="mb-0 small">{"status":"success","message":"S2S conversion recorded successfully"}</pre></div></div></div>
            <div class="col-md-4 mb-2"><div class="card border-warning"><div class="card-header bg-warning text-dark py-1 small fw-bold"><?= __('admin.duplicate') ?></div><div class="card-body py-2"><pre class="mb-0 small">{"status":"duplicate","message":"Order already recorded"}</pre></div></div></div>
            <div class="col-md-4 mb-2"><div class="card border-danger"><div class="card-header bg-danger text-white py-1 small fw-bold"><?= __('admin.error') ?></div><div class="card-body py-2"><pre class="mb-0 small">{"status":"error","message":"Invalid API key"}</pre></div></div></div>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="alert alert-warning py-2 small mb-0"><i class="bi bi-info-circle me-1"></i> <?= __('admin.s2s_save_first') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ===== METHOD CONTENT: POSTBACK ===== -->
<div class="intg-method-content" id="method-postback" style="<?= $current_method == 'postback' ? '' : 'display:none' ?>">
  <div class="card intg-section-card border-start border-secondary border-3">
    <div class="card-header bg-secondary bg-gradient text-white"><i class="bi bi-arrow-left-right me-2"></i><?= __('admin.integration_type_postback') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="bi bi-diagram-3 me-1"></i><?= __('admin.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('admin.flow_postback_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('admin.flow_postback_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('admin.flow_postback_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_postback_step4') ?></span>
        </div>
      </div>
      <div class="alert alert-info border-0 mb-3">
        <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-info-circle me-1"></i> <?= __('admin.postback_how_it_works') ?></h6>
        <p class="mb-2 small"><?= __('admin.postback_how_it_works_desc') ?></p>
        <ol class="mb-0 small">
          <li class="mb-1"><?= __('admin.postback_step_1') ?></li>
          <li class="mb-1"><?= __('admin.postback_step_2') ?></li>
          <li class="mb-1"><?= __('admin.postback_step_3') ?></li>
          <li><?= __('admin.postback_step_4') ?></li>
        </ol>
      </div>
      <div class="alert alert-light border-start border-secondary border-3 py-2 mb-3">
        <i class="bi bi-lightbulb text-warning me-1"></i> <small><?= __('admin.postback_use_case') ?></small>
      </div>
      <div class="alert alert-secondary border-0 py-2 mb-0">
        <i class="bi bi-gear me-1"></i> <small class="fw-semibold"><?= __('admin.postback_configure_below') ?></small>
      </div>
    </div>
  </div>
  <div id="method-postback-settings" class="mt-3"></div>
  <script>
  $(function(){
    if ($('#integration_method').val() === 'postback') {
      $('#postback-setting').appendTo('#method-postback-settings');
      $('#postback-setting').show().addClass('show active');
    }
  });
  </script>
</div>

<!-- ===== METHOD CONTENT: CONVERSION API ===== -->
<div class="intg-method-content" id="method-conversion_api" style="<?= $current_method == 'conversion_api' ? '' : 'display:none' ?>">
  <div class="card intg-section-card border-start border-dark border-3">
    <div class="card-header bg-dark bg-gradient text-white"><i class="bi bi-braces me-2"></i><?= __('admin.integration_method_conv_api') ?></div>
    <div class="card-body">
      <div class="p-3 bg-light rounded mb-3">
        <small class="text-muted fw-bold d-block mb-2"><i class="bi bi-diagram-3 me-1"></i><?= __('admin.flow_title') ?></small>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <span class="badge bg-dark rounded-pill px-3 py-2"><?= __('admin.flow_convapi_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-dark rounded-pill px-3 py-2"><?= __('admin.flow_convapi_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-dark rounded-pill px-3 py-2"><?= __('admin.flow_convapi_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
          <span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_convapi_step4') ?></span>
        </div>
      </div>
      <div class="alert alert-info border-0 mb-3">
        <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-info-circle me-1"></i> <?= __('admin.conv_api_how_it_works') ?></h6>
        <p class="mb-2 small"><?= __('admin.conv_api_how_it_works_desc') ?></p>
        <ol class="mb-0 small">
          <li class="mb-1"><?= __('admin.conv_api_step_1') ?></li>
          <li class="mb-1"><?= __('admin.conv_api_step_2') ?></li>
          <li class="mb-1"><?= __('admin.conv_api_step_3') ?></li>
          <li><?= __('admin.conv_api_step_4') ?></li>
        </ol>
      </div>
      <div class="alert alert-light border-start border-dark border-3 py-2 mb-3">
        <i class="bi bi-lightbulb text-warning me-1"></i> <small><?= __('admin.conv_api_use_case') ?></small>
      </div>
      <h6 class="fw-bold mt-3 mb-2"><i class="bi bi-signpost-2 me-2"></i><?= __('admin.conv_api_available_endpoints') ?></h6>
      <div class="row g-2 mb-3">
        <div class="col-md-6">
          <div class="card border h-100">
            <div class="card-body py-2">
              <div class="d-flex align-items-center mb-1">
                <span class="badge bg-warning text-dark me-2">POST</span>
                <small class="fw-bold"><?= __('admin.conv_api_click_endpoint') ?></small>
              </div>
              <code class="small d-block text-break mb-1"><?= base_url('integration/addClick') ?></code>
              <small class="text-muted"><?= __('admin.conv_api_click_endpoint_desc') ?></small>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card border h-100">
            <div class="card-body py-2">
              <div class="d-flex align-items-center mb-1">
                <span class="badge bg-success me-2">POST</span>
                <small class="fw-bold"><?= __('admin.conv_api_sale_endpoint') ?></small>
              </div>
              <code class="small d-block text-break mb-1"><?= base_url('integration/s2sConvert') ?></code>
              <small class="text-muted"><?= __('admin.conv_api_sale_endpoint_desc') ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="method-conversion_api-docs" class="mt-3"></div>
  <script>
  $(function(){
    if ($('#integration_method').val() === 'conversion_api') {
      $('#conversion_api').appendTo('#method-conversion_api-docs');
      $('#conversion_api').show().addClass('show active');
    }
  });
  </script>
</div>

              </div>
<!-- ==================== END INTEGRATION SETUP TAB ==================== -->

<!--postback url view code-->
              <div class="tab-pane fade" id="postback-setting" role="tabpanel" aria-labelledby="postback-tab">
	<?php 
	    $marketpostback = isset($tool['marketpostback']) ? $tool['marketpostback'] : ['status' => ''];
	    $marketpostback = is_string($marketpostback) ? json_decode($marketpostback, true) : $marketpostback;
	    $marketpostback = is_array($marketpostback) ? $marketpostback : ['status' => ''];

	    $default_marketpostback = isset($default_marketpostback) ? $default_marketpostback : [];
	    $default_marketpostback = is_string($default_marketpostback) ? json_decode($default_marketpostback, true) : $default_marketpostback;
	    $default_marketpostback = is_array($default_marketpostback) ? $default_marketpostback : [];

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
          <h6 class="fw-bold mb-0"><?= __('admin.postback_step1_title') ?></h6>
        </div>
        <p class="text-muted small mb-2"><?= __('admin.postback_step1_desc') ?></p>
        <select class="form-select marketpostback-status" name="marketpostback[status]">
          <option value=""><?= __('admin.disable') ?></option>
          <option value="custom" <?= isset($marketpostback['status']) && $marketpostback['status'] == 'custom' ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
        </select>
      </div>
    </div>

    <div class="marketpostback-default m-2">
      <div class="card intg-section-card">
        <div class="card-header bg-secondary bg-gradient text-white"><i class="bi bi-sliders me-2"></i><?= __('admin.default_postback_settings') ?></div>
        <div class="card-body">
          <?php 
          $marketpostback_dynamicparam = isset($default_marketpostback['dynamicparam']) ? $default_marketpostback['dynamicparam'] : array();
          $marketpostback_static = isset($default_marketpostback['static']) ? $default_marketpostback['static'] : array();
          $dynamicparam = [
              'city' => __('admin.city'), 'regionCode' => __('admin.region_code'), 'regionName' => __('admin.region_name'),
              'countryCode' => __('admin.country_code'), 'countryName' => __('admin.country_name'), 'continentName' => __('admin.continent_name'),
              'timezone' => __('admin.time_zone'), 'currencyCode' => __('admin.currency_code'), 'currencySymbol' => __('admin.currency_symbol'),
              'ip' => __('admin.ip'), 'id' => __('admin.id_sale_id_or_click_id'), 'type' => __('admin.type').' action,general_click,product_click,sale ',
          ];
          ?>
          <div class="mb-2"><strong><?= __('admin.status') ?>:</strong> <span class="badge <?= isset($default_marketpostback['status']) && (int)$default_marketpostback['status'] == 1 ? 'badge-success' : 'badge-danger' ?>"><?= isset($default_marketpostback['status']) && (int)$default_marketpostback['status'] == 1 ? __('admin.enable') : __('admin.disable') ?></span></div>
          <div class="mb-2"><strong><?= __('admin.postback_url') ?>:</strong> <code><?= isset($default_marketpostback['url']) ? $default_marketpostback['url'] : 'N/A' ?></code></div>
          <div class="mb-2"><strong><?= __('admin.dynemic_params') ?></strong>
            <ul class="list-group list-group-flush"><?php foreach ($marketpostback_dynamicparam as $key => $value) { ?><li class="list-group-item py-1 small"><?= isset($dynamicparam[$value]) ? $dynamicparam[$value] : $value ?></li><?php } ?></ul>
          </div>
          <div><strong><?= __('admin.static_params') ?></strong>
            <ul class="list-group list-group-flush"><?php foreach ($marketpostback_static as $key => $value) { ?><li class="list-group-item py-1 small"><strong><?= $value['key'] ?>:</strong> <?= $value['value'] ?></li><?php } ?></ul>
          </div>
        </div>
      </div>
    </div>

    <div class="marketpostback-custom">
      <!-- ========== STEP 2: Build Your Postback URL ========== -->
      <div class="card border-start border-primary border-3 mb-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:14px;">2</span>
            <h6 class="fw-bold mb-0"><?= __('admin.postback_step2_title') ?></h6>
          </div>
          <p class="text-muted small mb-3"><?= __('admin.postback_step2_desc') ?></p>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold small"><?= __('admin.postback_domain_location') ?></label>
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
              <label class="form-label fw-bold small text-primary mb-1"><i class="bi bi-link-45deg me-1"></i><?= __('admin.postback_generated_url') ?></label>
              <div class="input-group">
                <input type="text" name="marketpostback[url]" value="<?= isset($marketpostback['url']) ? $marketpostback['url'] : '' ?>" class="form-control form-control-lg font-monospace bg-white marketpostback-url" readonly>
                <button class="btn btn-primary" type="button" id="copy-postback-url"><i class="bi bi-clipboard me-1"></i> Copy</button>
              </div>
              <small class="text-muted d-block mt-1"><?= __('admin.postback_generated_url_desc') ?></small>
            </div>
          </div>
        </div>
      </div>

      <!-- ========== STEP 3: Choose Data Parameters ========== -->
      <div class="card border-start border-info border-3 mb-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-info text-dark rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:14px;">3</span>
            <h6 class="fw-bold mb-0"><?= __('admin.postback_step3_title') ?></h6>
          </div>
          <p class="text-muted small mb-3"><?= __('admin.postback_step3_desc') ?></p>

          <h6 class="fw-semibold small text-uppercase text-muted mb-2"><i class="bi bi-sliders me-1"></i><?= __('admin.dynemic_params') ?></h6>
          <p class="text-muted small mb-2"><?= __('admin.postback_dynamic_params_desc') ?></p>
          <div class="row g-2 mb-4">
            <?php
            $dynamicparam = [
                'city' => __('admin.city'), 'regionCode' => __('admin.region_code'), 'regionName' => __('admin.region_name'),
                'countryCode' => __('admin.country_code'), 'countryName' => __('admin.country_name'), 'continentName' => __('admin.continent_name'),
                'timezone' => __('admin.time_zone'), 'currencyCode' => __('admin.currency_code'), 'currencySymbol' => __('admin.currency_symbol'),
                'ip' => __('admin.ip'), 'type' => __('admin.type').' action,general_click,product_click,sale ', 'id' => __('admin.id_sale_id_or_click_id'),
            ];
            $marketpostback_dynamicparam = isset($marketpostback['dynamicparam']) ? $marketpostback['dynamicparam'] : array();
            ?>
            <?php foreach ($dynamicparam as $key => $value) { ?>
              <div class="col-md-4 col-sm-6">
                <label class="card border h-100 mb-0 p-2 d-flex align-items-start gap-2" style="cursor:pointer;" for="dynamic_<?= $key ?>">
                  <input type="checkbox" class="form-check-input mt-1 dynamic-param" id="dynamic_<?= $key ?>" name="marketpostback[dynamicparam][<?= $key ?>]" value="<?= $key ?>" <?= isset($marketpostback_dynamicparam[$key]) ? 'checked' : '' ?>>
                  <span><code class="fw-bold"><?= $key ?></code><br><small class="text-muted"><?= $value ?></small></span>
                </label>
              </div>
            <?php } ?>
          </div>

          <h6 class="fw-semibold small text-uppercase text-muted mb-2"><i class="bi bi-list-ul me-1"></i><?= __('admin.static_params') ?></h6>
          <p class="text-muted small mb-2"><?= __('admin.postback_static_params_desc') ?></p>
          <div class="static-params table-responsive mb-3">
            <table class="table table-sm table-bordered mb-0">
              <thead class="table-light">
                <tr><th><?= __('admin.param_key') ?></th><th><?= __('admin.param_value') ?></th><th width="50px">#</th></tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr><td colspan="3"><button class="btn btn-sm btn-primary add-static-params" type="button"><i class="bi bi-plus-lg me-1"></i><?= __('admin.add') ?></button></td></tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Demo files and instructions -->
      <div class="card border-0 bg-light mb-2">
        <div class="card-body py-3">
          <h6 class="fw-semibold small mb-2"><i class="bi bi-box-arrow-down me-1"></i><?= __('admin.demo_files_and_instructions') ?></h6>
          <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('assets/data/order_demo.zip') ?>" class="btn btn-sm btn-outline-primary" download><i class="bi bi-download me-1"></i><?= __('admin.download_order_demo') ?></a>
            <a href="<?= base_url('assets/data/postback_demo.zip') ?>" class="btn btn-sm btn-outline-secondary" download><i class="bi bi-download me-1"></i><?= __('admin.download_postback_demo') ?></a>
            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#postbackInstructionsModal"><i class="bi bi-book me-1"></i><?= __('admin.view_postback_instructions') ?></button>
          </div>
          <small class="text-muted d-block mt-1"><?= __('admin.download_demo_files_and_view_instructions_desc') ?></small>
        </div>
      </div>


			    <script type="text/javascript">
			        $(".add-static-params").click(function(){
			            addStaticParam('','');
			            updatePostbackUrl();
			        });

			        <?php 
			        $marketpostback_static = isset($marketpostback['static']) ? $marketpostback['static'] : array();
			        foreach ($marketpostback_static as $key => $value) {
			            echo "addStaticParam('". addslashes($value['key']) ."','". addslashes($value['value']) ."');";
			        }
			        ?>

			        var addStaticParamIndex = 0;
			        function addStaticParam(key,val) {
			            var html = `<tr>
			            <td>
			            <input type="text" value='${key}' name="marketpostback[static][${addStaticParamIndex}][key]" placeholder="<?= __('admin.param_key') ?>" class="form-control static-param-key">
			            </td>
			            <td>
			            <input type="text" name="marketpostback[static][${addStaticParamIndex}][value]" value='${val}' placeholder="<?= __('admin.param_value') ?>" class="form-control static-param-value">
			            </td>
			            <td>
			            <button class="btn btn-sm btn-danger remove-static-params" type="button"><i class="bi bi-trash"></i></button>
			            </td>
			            </tr>`;

			            addStaticParamIndex++;
			            $(".static-params tbody").append(html);
			        }

			        $(".static-params").delegate(".remove-static-params","click",function(){
			            $(this).parents("tr").remove();
			            updatePostbackUrl();
			        });

					function updatePostbackUrl() {
					    // Use custom domain input
					    var domain = $("#custom-domain").val() || 'http://your-domain';
					    
					    // Remove trailing slash if it exists
					    domain = domain.replace(/\/+$/, '');
					    
					    var baseUrl = domain + "/integration/";
					    var integrationType = $("#integration-type").val();
					    baseUrl += integrationType;

					    var params = [];

					    // Add dynamic params
					    $(".dynamic-param:checked").each(function() {
					        params.push($(this).val() + "=[" + $(this).val().toUpperCase() + "]");
					    });

					    // Add static params
					    $(".static-param-key").each(function(index) {
					        var key = $(this).val();
					        var value = $(".static-param-value").eq(index).val();
					        if (key && value) {
					            params.push(key + "=" + value);
					        }
					    });

					    // Add default params
					    params.push("script_name=general_integration");

					    var fullUrl = baseUrl + (params.length > 0 ? "?" + params.join("&") : "");
					    $(".marketpostback-url").val(fullUrl);
					}


			        // Update URL on checkbox change
			        $(".dynamic-param").change(updatePostbackUrl);

			        // Update URL on static param change
			        $(".static-params").on('input', '.static-param-key, .static-param-value', updatePostbackUrl);

			        // Update URL on integration type change
			        $("#integration-type").change(updatePostbackUrl);

			        // NEW: Update URL on domain change
			        $("#custom-domain").on('input', updatePostbackUrl);

			        // Initial URL update
			        updatePostbackUrl();

			        // Copy URL functionality
			        $("#copy-postback-url").click(function(){
			            $(".marketpostback-url").select();
			            document.execCommand('copy');
			            alert("<?= __('admin.postback_url_copied') ?>");
			        });
			    </script>
			</div>

            <script type="text/javascript">
                $(".marketpostback-status").change(function(){
                    var val = $(this).val();
                    $(".marketpostback-default, .marketpostback-custom").hide();

                    if(val == 'default') $(".marketpostback-default").show();
                    else if(val == 'custom') $(".marketpostback-custom").show();
                })
                $(".marketpostback-status").trigger("change");
            </script>
</div>
<!--postback url view code-->


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





<!-- JavaScript to initialize the modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('postbackInstructionsModal'), {
            keyboard: false
        });
    });
</script>

              <div class="tab-pane fade" id="conversion_api" role="tabpanel" aria-labelledby="conversion-tab">

<!-- Quick Reference Card -->
<div class="card bg-light border-0 mb-4">
  <div class="card-body">
    <h6 class="fw-bold mb-3"><i class="bi bi-bookmarks me-2"></i><?= __('admin.conv_api_quick_ref') ?></h6>
    <div class="row g-2">
      <div class="col-md-3 col-sm-6">
        <div class="card border h-100">
          <div class="card-body py-2 px-3 text-center">
            <span class="badge bg-primary rounded-pill mb-1">POST</span>
            <p class="fw-bold small mb-1"><?= __('admin.conv_api_click_title') ?></p>
            <code class="small">/integration/addClick</code>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border h-100">
          <div class="card-body py-2 px-3 text-center">
            <span class="badge bg-success rounded-pill mb-1">POST</span>
            <p class="fw-bold small mb-1"><?= __('admin.conv_api_action_title') ?></p>
            <code class="small">/integration/addClick</code>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border h-100">
          <div class="card-body py-2 px-3 text-center">
            <span class="badge bg-warning text-dark rounded-pill mb-1">POST</span>
            <p class="fw-bold small mb-1"><?= __('admin.conv_api_order_title') ?></p>
            <code class="small">/integration/addOrder</code>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border h-100">
          <div class="card-body py-2 px-3 text-center">
            <span class="badge bg-info text-dark rounded-pill mb-1">POST</span>
            <p class="fw-bold small mb-1"><?= __('admin.conv_api_order_click_title') ?></p>
            <code class="small">/integration/addClick</code>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Accordion -->
<div class="accordion" id="convApiAccordion">

  <!-- 1. Click Conversion API -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="convApiHead1">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#convApiBody1" aria-expanded="true" aria-controls="convApiBody1">
        <span class="badge bg-primary me-2">POST</span> <?= __('admin.conv_api_click_title') ?>
        <code class="ms-2 small text-muted"><?php echo base_url('integration/addClick'); ?></code>
      </button>
    </h2>
    <div id="convApiBody1" class="accordion-collapse collapse" aria-labelledby="convApiHead1" data-bs-parent="#convApiAccordion">
      <div class="accordion-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead class="table-light"><tr><th>Parameter</th><th>Type</th><th>Value</th><th>Description</th></tr></thead>
                <tbody>
                  <tr><td><code>page_name</code></td><td><code>string</code></td><td><code>admin_click</code></td><td>Get the General code from the General setting tab.</td></tr>
                  <tr><td><code>customFields</code></td><td><code>json array</code></td><td><code>[{"city":"cityName"},...]</code></td><td>optional value</td></tr>
                  <tr><td><code>base_url</code></td><td><code>string</code></td><td><code>target url</code></td><td>Target Link (base64 encoded)</td></tr>
                  <tr><td><code>current_page_url</code></td><td><code>string</code></td><td><code>page url</code></td><td>Client URL (base64 encoded)</td></tr>
                  <tr><td><code>af_id</code></td><td><code>string</code></td><td><code>affiliate id</code></td><td>Affiliate Id from external link URL</td></tr>
                  <tr><td><code>script_name</code></td><td><code>string</code></td><td><code>general_integration</code></td><td>-</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-semibold small mb-0"><i class="bi bi-code-slash me-1"></i><?= __('admin.conv_api_php_example') ?></h6>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code"><i class="bi bi-clipboard me-1"></i><?= __('admin.conv_api_copy_code') ?></button>
            </div>
            <pre class="bg-dark text-white rounded p-3 small" style="max-height:320px;overflow-y:auto;">
$page_name="admin_click";
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
    <h2 class="accordion-header" id="convApiHead2">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#convApiBody2" aria-expanded="false" aria-controls="convApiBody2">
        <span class="badge bg-success me-2">POST</span> <?= __('admin.conv_api_action_title') ?>
        <code class="ms-2 small text-muted"><?php echo base_url('integration/addClick'); ?></code>
      </button>
    </h2>
    <div id="convApiBody2" class="accordion-collapse collapse" aria-labelledby="convApiHead2" data-bs-parent="#convApiAccordion">
      <div class="accordion-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead class="table-light"><tr><th>Parameter</th><th>Type</th><th>Value</th><th>Description</th></tr></thead>
                <tbody>
                  <tr><td><code>actionCode</code></td><td><code>string</code></td><td><code>admin_action</code></td><td>Get the Action code from General setting tab.</td></tr>
                  <tr><td><code>customFields</code></td><td><code>json array</code></td><td><code>[{"city":"cityName"},...]</code></td><td>-</td></tr>
                  <tr><td><code>base_url</code></td><td><code>string</code></td><td><code>target url</code></td><td>Target Link (base64 encoded)</td></tr>
                  <tr><td><code>current_page_url</code></td><td><code>string</code></td><td><code>page url</code></td><td>Client URL (base64 encoded)</td></tr>
                  <tr><td><code>af_id</code></td><td><code>string</code></td><td><code>affiliate Id</code></td><td>Affiliate Id from external link URL</td></tr>
                  <tr><td><code>script_name</code></td><td><code>string</code></td><td><code>general_integration</code></td><td>-</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-semibold small mb-0"><i class="bi bi-code-slash me-1"></i><?= __('admin.conv_api_php_example') ?></h6>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code"><i class="bi bi-clipboard me-1"></i><?= __('admin.conv_api_copy_code') ?></button>
            </div>
            <pre class="bg-dark text-white rounded p-3 small" style="max-height:320px;overflow-y:auto;">
$actioncode="admin_action";
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
    <h2 class="accordion-header" id="convApiHead3">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#convApiBody3" aria-expanded="false" aria-controls="convApiBody3">
        <span class="badge bg-warning text-dark me-2">POST</span> <?= __('admin.conv_api_order_title') ?>
        <code class="ms-2 small text-muted"><?php echo base_url('integration/addOrder'); ?></code>
      </button>
    </h2>
    <div id="convApiBody3" class="accordion-collapse collapse" aria-labelledby="convApiHead3" data-bs-parent="#convApiAccordion">
      <div class="accordion-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead class="table-light"><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                <tbody>
                  <tr><td><code>product_ids</code></td><td><code>integer</code></td><td><code>product id</code></td><td>-</td></tr>
                  <tr><td><code>order_id</code></td><td><code>integer</code></td><td><code>order number</code></td><td>-</td></tr>
                  <tr><td><code>order_currency</code></td><td><code>string</code></td><td><code>USD, INR</code></td><td>-</td></tr>
                  <tr><td><code>order_total</code></td><td><code>decimal</code></td><td><code>order total</code></td><td>-</td></tr>
                  <tr><td><code>customFields</code></td><td><code>json array</code></td><td><code>[{"city":"cityName"},...]</code></td><td>-</td></tr>
                  <tr><td><code>base_url</code></td><td><code>string</code></td><td><code>target url</code></td><td>Target Link (base64 encoded)</td></tr>
                  <tr><td><code>current_page_url</code></td><td><code>string</code></td><td><code>page url</code></td><td>Client URL (base64 encoded)</td></tr>
                  <tr><td><code>af_id</code></td><td><code>string</code></td><td><code>affiliate Id</code></td><td>Affiliate Id from external link URL</td></tr>
                  <tr><td><code>script_name</code></td><td><code>string</code></td><td><code>general_integration</code></td><td>-</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-semibold small mb-0"><i class="bi bi-code-slash me-1"></i><?= __('admin.conv_api_php_example') ?></h6>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code"><i class="bi bi-clipboard me-1"></i><?= __('admin.conv_api_copy_code') ?></button>
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
    <h2 class="accordion-header" id="convApiHead4">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#convApiBody4" aria-expanded="false" aria-controls="convApiBody4">
        <span class="badge bg-info text-dark me-2">POST</span> <?= __('admin.conv_api_order_click_title') ?>
        <code class="ms-2 small text-muted"><?php echo base_url('integration/addClick'); ?></code>
      </button>
    </h2>
    <div id="convApiBody4" class="accordion-collapse collapse" aria-labelledby="convApiHead4" data-bs-parent="#convApiAccordion">
      <div class="accordion-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead class="table-light"><tr><th>Parameter</th><th>Type</th><th>Value</th><th>Description</th></tr></thead>
                <tbody>
                  <tr><td><code>product_id</code></td><td><code>string</code></td><td><code>ProductID</code></td><td>Pass static value "ProductID"</td></tr>
                  <tr><td><code>customFields</code></td><td><code>json array</code></td><td><code>[{"city":"cityName"},...]</code></td><td>-</td></tr>
                  <tr><td><code>base_url</code></td><td><code>string</code></td><td><code>target url</code></td><td>Target Link (base64 encoded)</td></tr>
                  <tr><td><code>current_page_url</code></td><td><code>string</code></td><td><code>page url</code></td><td>Client URL (base64 encoded)</td></tr>
                  <tr><td><code>af_id</code></td><td><code>string</code></td><td><code>affiliate Id</code></td><td>Affiliate Id from external link URL</td></tr>
                  <tr><td><code>script_name</code></td><td><code>string</code></td><td><code>general_integration</code></td><td>-</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-semibold small mb-0"><i class="bi bi-code-slash me-1"></i><?= __('admin.conv_api_php_example') ?></h6>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code"><i class="bi bi-clipboard me-1"></i><?= __('admin.conv_api_copy_code') ?></button>
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

$url='<?= base_url("integration/addClick");?>';
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
  <i class="bi bi-info-circle text-primary me-2 fs-5"></i>
  <span><?= __('admin.conv_api_accordion_hint') ?></span>
</div>

<script>
$(document).on("click", ".btn-copy-code", function(){
  var pre = $(this).closest(".col-lg-6").find("pre");
  var text = pre.text();
  if (navigator.clipboard) { navigator.clipboard.writeText(text); }
  else { var ta = $("<textarea>").val(text).appendTo("body").select(); document.execCommand("copy"); ta.remove(); }
  var btn = $(this);
  btn.html('<i class="bi bi-check me-1"></i>Copied!');
  setTimeout(function(){ btn.html('<i class="bi bi-clipboard me-1"></i><?php echo __("admin.conv_api_copy_code"); ?>'); }, 2000);
});
</script>

              </div><!-- /#conversion_api -->

              <div class="tab-pane fade" id="menu1" role="tabpanel" aria-labelledby="level-tab">
                <div class="card intg-section-card mb-3">
                  <div class="card-header"><i class="bi bi-layers me-2 text-primary"></i><?= __('admin.commission_type') ?></div>
                  <div class="card-body">
				<div class="form-group mb-0">
					<select class="form-select form-select-sm" name="commission_type">
							<option value="default" <?= (isset($tool) && $tool['commission_type'] == 'default') ? 'selected' : '' ?>><?= __('admin.default') ?></option>
							<option value="custom" <?= (isset($tool) && $tool['commission_type'] == 'custom') ? 'selected' : '' ?>>
								<?= __('admin.custom') ?></option>
								<option value="disabled" <?= (isset($tool) && $tool['commission_type'] == 'disabled') ? 'selected' : '' ?>><?= __('admin.disabled') ?></option>
						</select>
				</div>
                  </div>
                </div>
				<div class="default-mlm<?= ($tool['commission_type'] != 'custom' && $tool['commission_type'] != 'disabled') ? '' : ' intg-mlm-default-hidden' ?>">
						<div class="table-responsive">
							<table class="table intg-level-table" id="tbl_refer_level">
								<thead>
									<tr>
										<th><?= __('admin.level_mlm') ?></th>
										<?php if(!$tool['vendor_id']): ?>
											<th>
												<?= __('admin.cpr_cost') ?><br>
												<?php if ($default['referlevel']['reg_comission_type'] == 'disabled'): ?>
													<span class="form-control"><?= __('admin.select_registration_commission_plan') ?></span>
												<?php endif ?>
												<?php if ($default['referlevel']['reg_comission_type'] == 'percentage'): ?>
													<span class="form-control"><?= __('admin.membership_registration_commission_perce') ?></span>
												<?php endif ?>
												<?php if ($default['referlevel']['reg_comission_type'] == 'custom_percentage'): ?>
													<span class="form-control"><?= __('admin.registration_custom_commission_amount_perce') ?></span>
												<?php endif ?>
												<?php if ($default['referlevel']['reg_comission_type'] == 'fixed'): ?>
													<span class="form-control"><?= __('admin.registration_fixed_amount') ?></span>
												<?php endif ?>

												<span class="form-control"><?php echo isset($default['referlevel']['reg_comission_custom_amt']) ? $default['referlevel']['reg_comission_custom_amt'] : 0;?></span>
											</th>
											<?php endif ?>
											<th>
												<?= __('admin.cps_cost') ?><br>
												<?php if ($default['referlevel']['sale_type'] == 'percentage'): ?>
													<span class="form-control"><?= __('admin.percentage') ?></span>
												<?php endif ?>
												<?php if ($default['referlevel']['sale_type'] == 'fixed'): ?>
													<span class="form-control"><?= __('admin.fixed') ?></span>
												<?php endif ?>
											</th>
											<th colspan="2"><?= __('admin.clicks_count') ?> &amp; <?= __('admin.cpc_cost') ?></th>
											<th><?= __('admin.cpa_cost') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php $default_levels = isset($default['referlevel']['levels']) ? (int)$default['referlevel']['levels'] : 3;
									for ($level =1; $level <= $default_levels; $level++) { ?>
										<tr>
											<td ><?= $level ?></td>
											<?php if(!$tool['vendor_id']): ?>
												<td >
													<div class="input-group input-group-sm">
														<span class="form-control"><?php echo $default['referlevel_'. $level]['reg_commission'] ?></span>
														<span class="input-group-text refer-reg-symball"></span>
													</div>
												</td>
											<?php endif ?>
											<td >
												<div class="input-group input-group-sm">
													<span class="form-control"><?php echo $default['referlevel_'. $level]['sale_commition'] ?></span>
													<span class="input-group-text refer-symball"></span>
												</div>
											</td>
											<td><span class="form-control form-control-sm"><?php echo $default['referlevel_'. $level]['commition'] ?></span></td>
											<td >
												<div class="input-group input-group-sm">
													<span class="form-control"><?php echo $default['referlevel_'. $level]['ex_commition'] ?></span>
													<span class="input-group-text"><?= $CurrencySymbol ?></span>
												</div>
											</td>
											<td>
												<div class="input-group input-group-sm">
													<span class="form-control"><?php echo $default['referlevel_'. $level]['ex_action_commition'] ?></span>
													<span class="input-group-text"><?= $CurrencySymbol ?></span>
												</div>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="commi-cube <?= ($tool['commission_type'] == 'custom') ? '' : 'intg-mlm-default-hidden' ?>">
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
					</div>
					<div class="table-responsive">
						<table class="table intg-level-table" id="tbl_refer_level">
							<thead>
								<tr>
									<th><?= __('admin.level_mlm') ?></th>
									<?php if(!$tool['vendor_id']): ?>
										<th>
											<?= __('admin.cpr_cost') ?><br>
											<select class="form-control refer-reg-symball-select d-block w-100 mt-2" name="referlevel[reg_comission_type]">
												<option value="disabled" <?php if($referlevel['reg_comission_type'] == 'disabled') { ?> selected <?php } ?>><?= __('admin.select_registration_commission_plan') ?></option>
												<option symbal='%' <?php if($referlevel['reg_comission_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.membership_registration_commission_perce') ?></option>
												<option symbal='%' <?php if($referlevel['reg_comission_type'] == 'custom_percentage') { ?> selected <?php } ?> value="custom_percentage"><?= __('admin.registration_custom_commission_amount_perce') ?></option>
												<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['reg_comission_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed"><?= __('admin.registration_fixed_amount') ?></option>
											</select>
											<input class="w-100 mt-2" type="number" name="referlevel[reg_comission_custom_amt]" value="<?php echo isset($referlevel['reg_comission_custom_amt']) ? $referlevel['reg_comission_custom_amt'] : 0;?>" placeholder="custom commission ammount" />
										</th>
									<?php endif ?>
									<th>
										<?= __('admin.cps_cost') ?><br>
										<select class="form-control refer-symball-select w-100 mt-2" name="referlevel[sale_type]">
											<option symbal='%' <?php if($referlevel['sale_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.percentage') ?></option>
											<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['sale_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed"><?= __('admin.fixed') ?></option>
										</select>
									</th>
									<th colspan="2"><?= __('admin.clicks_count') ?> &amp; <?= __('admin.cpc_cost') ?></th>
									<th><?= __('admin.cpa_cost') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php for ($level =1; $level <= $levels; $level++) { ?>
									<tr>
										<td ><?= $level ?></td>
										<?php if(!$tool['vendor_id']): ?>
											<td >
												<div class="input-group">
													<input type="number" step="any" name="referlevel_<?= $level ?>[reg_commission]" value="<?php echo ${"referlevel_". $level}['reg_commission'] ?>" class="form-control" />
													<div class="input-group-append"><span class="input-group-text refer-reg-symball"></span></div>
												</div>
											</td>
										<?php endif ?>
										<td >
											<div class="input-group">
												<input type="number" step="any" name="referlevel_<?= $level ?>[sale_commition]" value="<?php echo ${"referlevel_". $level}['sale_commition'] ?>" class="form-control" />
												<div class="input-group-append"><span class="input-group-text refer-symball"></span></div>
											</div>
										</td>
										<td><input type="number" step="any" name="referlevel_<?= $level ?>[commition]" value="<?php echo ${"referlevel_". $level}['commition'] ?>" class="form-control" /></td>
										<td >
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
					<?php if(false){ ?>
						<div class="row">
							<div class="col-sm-3">
								<div class="comm-cube-box">
									<div class="form-group">
										<label  class="col-form-label"><?= __('admin.no_of_click_per_commission') ?></label>
										<input name="referlevel[click]" value="<?php echo $referlevel['click']; ?>" class="form-control" step="any" type="number" placeholder='<?= __('admin.no_of_click_per_commission') ?>'>
									</div>
									<?php foreach (array('1','2','3') as $key => $v) { ?>
										<fieldset>
											<legend><?= __('admin.level') ?> <?= $v ?>:</legend>

											<div class="form-group">
												<label  class="col-form-label"><?= __('admin.refer_setting_click_commission') ?> (<?= $CurrencySymbol ?></span>)</label>
												<input name="referlevel_<?php echo $v ?>[commition]" value="<?php echo ${"referlevel_$v"}['commition']; ?>" class="form-control" step="any" type="number">
											</div>
										</fieldset>
									<?php } ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="comm-cube-box">
									<div class="form-group">
										<label  class="col-form-label"><?= __('admin.fix_amount_or_per') ?></label>
										<select class="form-control refer-symball-select" name="referlevel[sale_type]">
											<option symbal='%' <?php if($referlevel['sale_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.percentage') ?>(%)</option>
											<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['sale_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed"><?= __('admin.fixed') ?></option>
										</select>
									</div>
									<?php foreach (array('1','2','3') as $key => $v) { ?>
										<fieldset>
											<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
											<div class="form-group">
												<label  class="col-form-label"><?= __('admin.refer_setting_sale_commission') ?> (<span class="refer-symball"></span>)</label>
												<input name="referlevel_<?php echo $v ?>[sale_commition]" value="<?php echo ${"referlevel_$v"}['sale_commition']; ?>" class="form-control" step="any" type="number">
											</div>
										</fieldset>
									<?php } ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="comm-cube-box">
									<div class="form-group">
										<label  class="col-form-label"><?= __('admin.external_click') ?></label>
										<input name="referlevel[ex_click]" value="<?php echo $referlevel['ex_click']; ?>" class="form-control" step="any" type="number" placeholder='External Click'>
									</div>
									<?php foreach (array('1','2','3') as $key => $v) { ?>
										<fieldset>
											<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
											<div class="form-group">
												<label  class="col-form-label"><?= __('admin.external_click_commission') ?>  (<?= $CurrencySymbol ?></span>)</label>
												<input name="referlevel_<?php echo $v ?>[ex_commition]" value="<?php echo ${"referlevel_$v"}['ex_commition']; ?>" class="form-control" step="any" type="number">
											</div>
										</fieldset>
									<?php } ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="comm-cube-box">
									<div class="form-group">
										<label  class="col-form-label"><?= __('admin.external_action_click') ?></label>
										<input name="referlevel[ex_action_click]" value="<?php echo $referlevel['ex_action_click']; ?>" class="form-control" step="any" type="number" placeholder='External Action Click'>
									</div>
									<?php foreach (array('1','2','3') as $key => $v) { ?>
										<fieldset>
											<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
											<div class="form-group">
												<label  class="col-form-label"><?= __('admin.external_action_click_Commission') ?>  (<?= $CurrencySymbol ?></span>)</label>
												<input name="referlevel_<?php echo $v ?>[ex_action_commition]" value="<?php echo ${"referlevel_$v"}['ex_action_commition']; ?>" class="form-control" step="any" type="number">
											</div>
										</fieldset>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
            </div><!-- /.tab-content -->

<?php $allow_for = array_filter(explode(",", $tool['allow_for'])); ?>
<?php $allow_groups = array_filter(explode(",", $tool['allow_groups'])); ?>

<!-- Allowed Affiliates + Status (visible on all tabs) -->
<div class="border-top mt-4 pt-3">
<div class="form-integration <?= $tool['vendor_id'] ? 'vendor-grid-campaign-group' : '' ?>">
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
                            <input class="form-check-input allow_for" type="radio" id="allowForAll" name="allow_for_radio" <?= count($allow_for) == 0 ? 'checked' : '' ?> value="0">
                            <label class="form-check-label fw-medium" for="allowForAll"><i class="bi bi-globe2 me-1 text-muted"></i><?= __('admin.all') ?></label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input allow_for" type="radio" id="allowForSelectedGroups" name="allow_for_radio" <?= $tool['is_allow_group'] == 1 ? 'checked' : '' ?> value="2">
                            <label class="form-check-label fw-medium" for="allowForSelectedGroups"><i class="bi bi-collection me-1 text-muted"></i><?= __('admin.selected_groups') ?></label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input allow_for" type="radio" id="allowForSelectedAffiliates" name="allow_for_radio" <?= count($allow_for) > 0 && $tool['is_allow_group'] != 1 ? 'checked' : '' ?> value="1">
                            <label class="form-check-label fw-medium" for="allowForSelectedAffiliates"><i class="bi bi-person-check me-1 text-muted"></i><?= __('admin.selected_affiliate') ?></label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="input-group input-group-sm" style="display:none;">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input class="form-control border-start-0" type="text" name="users_name_string" placeholder="<?= __('admin.search_users') ?>...">
                        </div>
                        <div class="input-group input-group-sm" style="display:none;">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input class="form-control border-start-0" type="text" name="group_name_string" placeholder="<?= __('admin.search_groups') ?>...">
                        </div>
                    </div>
                    <div class="show-allow_for" style="display:none;">
                        <div class="integration_users_list change-color-integration border rounded bg-light p-2" style="max-height:180px;overflow-y:auto;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <i class="bi bi-heart-pulse me-2 text-danger"></i>
                    <span class="fw-semibold"><?= __('admin.marketplace_promotion_requirements') ?></span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium"><?= __('admin.min_health_score_promote') ?></label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control" name="min_health_score" value="<?= htmlspecialchars((string)(isset($tool['min_health_score']) ? $tool['min_health_score'] : '0'), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="form-text"><?= __('admin.min_health_score_promote_hint') ?></div>
                        </div>
                        <?php if (!empty($award_level_status) && !empty($award_levels_list)) { ?>
                        <div class="col-md-8">
                            <label class="form-label fw-medium d-inline-flex align-items-center flex-wrap gap-2 mb-1">
                                <?= __('admin.min_award_level_promote') ?>
                                <button type="button" class="btn btn-link btn-sm text-secondary p-0 lh-1 border-0 align-baseline" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-container="body" title="<?= htmlspecialchars(__('admin.min_award_level_hierarchy_tooltip'), ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars(__('admin.min_award_level_hierarchy_tooltip'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="bi bi-question-circle" aria-hidden="true"></i>
                                </button>
                            </label>
                            <select class="form-select" name="min_award_level_id">
                                <option value=""><?= __('admin.min_award_level_promote_none') ?></option>
                                <?php foreach ($award_levels_list as $alv) { ?>
                                    <option value="<?= (int)$alv['id'] ?>" <?= (isset($tool) && isset($tool['min_award_level_id']) && (int)$tool['min_award_level_id'] === (int)$alv['id']) ? 'selected' : '' ?>><?= htmlspecialchars($alv['level_number'], ENT_QUOTES, 'UTF-8') ?></option>
                                <?php } ?>
                            </select>
                            <div class="form-text"><?= __('admin.min_award_level_promote_hint') ?></div>
                        </div>
                        <?php } ?>
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
                    <?php $class = $tool['vendor_id'] ? 'col-4' : 'col-6'; ?>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" value="0" id="statusDraft" <?= (int) $tool['status'] == 0 ? 'checked' : '' ?>>
                            <label class="form-check-label badge bg-warning bg-opacity-75 px-3 py-2 fs-6" for="statusDraft"><?= __('admin.draft') ?></label>
                        </div>
                        <?php if($tool['vendor_id']): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" value="2" id="statusReview" <?= (int) $tool['status'] == 2 ? 'checked' : '' ?>>
                            <label class="form-check-label badge bg-info bg-opacity-75 px-3 py-2 fs-6" for="statusReview"><?= __('admin.in_review') ?></label>
                        </div>
                        <?php endif ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" value="1" id="statusPublic" <?= (int) $tool['status'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label badge bg-success bg-opacity-75 px-3 py-2 fs-6" for="statusPublic"><?= __('admin.public') ?></label>
                        </div>
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
    $("input[name='users_name_string']").closest('.input-group').hide();
    $("input[name='group_name_string']").closest('.input-group').hide();
    if($(this).val() == '1'){
        $(".show-allow_for").show();
        $("input[name='users_name_string']").closest('.input-group').show();
        render_integration_users();
    }
    if($(this).val() == '2') {
        render_integration_groups();
        $(".show-allow_for").show();
        $("input[name='group_name_string']").closest('.input-group').show();
    }
})
$(".allow_for:checked").trigger("change");

$(document).on('keyup', "input[name='users_name_string']", function(){
    render_integration_users();
});
$(document).on('keyup', "input[name='group_name_string']", function(){
    render_integration_groups();
});

function render_integration_users() {
    var allowed_users = <?= json_encode($allow_for); ?>;
    $('.integration_users_list').html('<div class="text-center text-muted py-3"><i class="bi bi-hourglass-split me-1"></i><?= __('admin.loading') ?>...</div>');
    $.ajax({
        url:'<?= base_url('integration/get_users_for_integration') ?>',
        type:'POST',
        dataType:'json',
        data: {users_name_string : $("input[name='users_name_string']").val()},
        success:function(users){
            $('.integration_users_list').empty();
            if(!users || users.length === 0) {
                $('.integration_users_list').html('<div class="text-center text-muted py-3"><i class="bi bi-person-slash fs-5 d-block mb-1"></i><small><?= __('admin.no_affiliates_found') ?></small></div>');
                return;
            }
            for (var i = 0; i < users.length; i++) {
                let checked = allowed_users.includes(String(users[i]['id'])) ? "checked" : "";
                $('.integration_users_list').append(
                    '<label class="d-flex align-items-center gap-2 px-2 py-1 rounded' + (checked ? ' bg-primary bg-opacity-10' : '') + '" style="cursor:pointer;">' +
                    '<input type="checkbox" class="form-check-input mt-0" name="allow_for[]" value="' + users[i]['id'] + '" ' + checked + '>' +
                    '<span class="small">' + users[i]['name'] + '</span></label>'
                );
            }
        }
    });
}

function render_integration_groups() {
    var allowed_groups = <?= json_encode($allow_groups); ?>;
    $('.integration_users_list').html('<div class="text-center text-muted py-3"><i class="bi bi-hourglass-split me-1"></i><?= __('admin.loading') ?>...</div>');
    $.ajax({
        url:'<?= base_url('integration/get_groups_for_integration') ?>',
        type:'POST',
        dataType:'json',
        data: { group_name_string : $("input[name='group_name_string']").val() },
        success:function(groups){
            $('.integration_users_list').empty();
            if(!groups || groups.length === 0) {
                $('.integration_users_list').html('<div class="text-center text-muted py-3"><i class="bi bi-collection fs-5 d-block mb-1"></i><small><?= __('admin.no_groups_found') ?></small></div>');
                return;
            }
            for (var i = 0; i < groups.length; i++) {
                let checked = allowed_groups.includes(String(groups[i]['id'])) ? "checked" : "";
                $('.integration_users_list').append(
                    '<label class="d-flex align-items-center gap-2 px-2 py-1 rounded' + (checked ? ' bg-primary bg-opacity-10' : '') + '" style="cursor:pointer;">' +
                    '<input type="checkbox" class="form-check-input mt-0" name="allow_groups[]" value="' + groups[i]['id'] + '" ' + checked + '>' +
                    '<span class="small">' + groups[i]['group_name'] + '</span></label>'
                );
            }
        }
    });
}
</script>

          </form>
        </div>
        
        <!-- Form Footer -->
        <div class="card-footer intg-form-footer">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <?php if (isset($tool['id'])): ?>
                <button class="btn btn-outline-secondary btn-sm get-code" href="javascript:void(0)" data-id="<?= $tool['id'] ?>">
                  <i class="bi bi-code-slash me-1"></i><?= __('admin.get_code') ?>
                </button>
              <?php endif; ?>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary btn-save save-not-close" type="button">
                <span class="loading-submit"></span>
                <i class="bi bi-floppy me-1"></i><?= __('admin.save') ?>
              </button>
              <button class="btn btn-success btn-save" type="button">
                <span class="loading-submit"></span>
                <i class="bi bi-check-lg me-1"></i><?= __('admin.save_close') ?>
              </button>
            </div>
          </div>
        </div>
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
				<h4 class="modal-title mt-0"><?= __('admin.add_program') ?></h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="add_program_to_form" value="1">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label"><?= __('admin.program_name') ?></label>
								<input class="form-control" name="name" type="text" value="<?= isset($programs) ? $programs['name'] : '' ?>">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="custom-card card">
								<div class="card-header"><p class="text-center"><?= __('admin.sale_settings') ?></p></div>

								<div class="card-body">
									<div class="form-group">
										<label class="control-label"><?= __('admin.commission_type') ?></label>
										<select name="commission_type" class="form-control">
											<option value=""><?= __('admin.select_product_commission_type') ?></option>
											<option value="percentage"><?= __('admin.percentage') ?></option>
											<option value="fixed"><?= __('admin.fixed') ?></option>
										</select>
									</div>

									<div class="form-group">
										<label class="control-label"><?= __('admin.commission_for_sale') ?> </label>
										<input class="form-control" name="commission_sale" type="number">
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
								<div class="card-header"><p class="text-center"><?= __('admin.click_settings') ?></p></div>

								<div class="card-body">
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label class="control-label"><?= __('admin.admin_Clicks_allow') ?></label>
													<select name="click_allow" class="form-control">
														<option value="multiple"><?= __('admin.admin_allow_multi_click') ?></option>
														<option value="single"><?= __('admin.admin_allow_single_click') ?></option>
													</select>
												</div>
											</div>

											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><?= __('admin.number_of_click') ?></label>
													<input class="form-control" name="commission_number_of_click" type="number">
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label"><?= __('admin.amount_per_click') ?></label>
													<input class="form-control" name="commission_click_commission" type="number">
												</div>
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
				<button type="button" class="btn btn-primary addProgramToFrom"><?= __('admin.save_close') ?></button>
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= __('admin.footer_close') ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addCategory">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title mt-0"><?= __('admin.add_category') ?></h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="add_category_to_form" value="1">
					<div class="row">
						<div class="col-12">
							<div class="card m-b-30">
								<div class="card-body">
									<input type="hidden" name="category_id">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label"><?= __('admin.category_name') ?></label>
												<input type="text" name="name" class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label"><?= __('admin.parent_category') ?></label>
												<select name="parent_id" class="form-control">
													<option selected><?= __('admin.select_parent_category') ?></option>
													<?php foreach ($p_categories as $cat) { ?>
														<option value="<?= $cat['id']; ?>"><?= $cat['name']; ?></option>
													<?php } ?>
												</select>
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
				<button type="button" class="btn btn-primary addCategoryToFrom"><?= __('admin.save_close') ?></button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>">
</script>

<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/ui/jquery-ui.min.css") ?>">


<script type="text/javascript">
	$('#endtime').datetimepicker({
		format:'d-m-Y H:i',
		inline:true,
	});

	$('.datetime-picker').datetimepicker({
		format:'d-m-Y H:i',
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

		$('.reg_notis').hide(); 
		$('.reg_'+$(".refer-reg-symball-select").val()+'_notis').show();
	}

	$(".refer-reg-symball-select").change(chnage_teigger2)
	chnage_teigger2();

	$('[name="tool_type"]').on('change',function(e,data){
			var url = $(location).attr('href'),
			parts = url.split("/"),
			user_slug = parts[parts.length-2];

		// Initially hide all sections
    	$('.for-action-tool, .for-program-tool, .for-general_click-tool, .single-action-integration, .single-multi-action').hide();


		var click_value = "<?= isset($tool) ? $tool['action_click'] : '' ?>";
		let type = $(this).val();

		if (type == 'program') {
			$('.for-program-tool').show();
			$('.sale-integration').show();
			$('.tool-integration-plugin').show();
			$('.single-action-integration').hide();
			$('.single-multi-action').hide();

		}

		else if (type == 'single_action') {
	        $('.for-action-tool').show();
	        $('.single-action-integration').show();
			$('.single-action-integration [name="action_click"]').val(click_value).attr('readonly', true);
			$('.single-action-integration [name="admin_action_click"]').val(1).attr('readonly', true);
	        $('.for-program-tool, .sale-integration, .tool-integration-plugin').hide();
	    }

		else if(type == 'action'){
			$('.for-action-tool').show();
			$('.single-action-integration').show();
			$('.single-action-integration [name="action_click"]').val(click_value).attr('readonly', true);
			$('.for-program-tool, .sale-integration, .tool-integration-plugin').hide();

		}else if (type == 'general_click') {
			$('.for-general_click-tool').show();
			$('.click-integration').show();
			$('.for-general_click-tool [name="admin_general_click"]').val();
			$('.for-program-tool, .tool-integration-plugin, .sale-integration, .single-action-integration').hide();
		}

		else{
			$(".for-"+ $(this).val() +"-tool").show();
		}

		if(type != 'program'){
			$('[name="tool_integration_plugin"]').val("");
		}

		if(data)
			rendeCampignDefaultImages('load');
		else 
			rendeCampignDefaultImages();
	});

	$('[name="tool_integration_plugin"]').on('change',function(){
		rendeCampignDefaultImages();
	});


	function rendeCampignDefaultImages(load) {
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

		if(!load){
			$('.campaign_default_image').attr('src', '<?= base_url('assets/images/')?>'+featured_image);
			$("input[name='old_featured_image']").val('');
			$("input[name='keep_ads[]']").remove();
		}

		var image = new Image();
		image.src = '<?= base_url('assets/images/')?>'+featured_image;
		$(image).one('load',function(){
			var width = image.width;
			var height = image.height;
			$('input[name="custom_banner_size[]"]').val(width + 'x' + height);
		});
	}

	$('[name="tool_type"]').trigger("change",[{load:true}]);

	// Update default images based on campaign type on page load
	(function initDefaultImages() {
		let type = $('[name="tool_type"]').val();
		let featured_image = 'no_product_image.png';
		if(type == 'single_action' || type == 'action'){
			featured_image = 'plugins_icons/action.jpg';
		} else if(type == 'general_click') {
			featured_image = 'plugins_icons/click.jpg';
		} else if(type == 'program'){
			let program = $('[name="tool_integration_plugin"]').val();
			switch (program){
				case 'woocommerce': featured_image = 'plugins_icons/woo.png'; break;
				case 'prestashop': featured_image = 'plugins_icons/prestashop.png'; break;
				case 'opencart': featured_image = 'plugins_icons/opencart.png'; break;
				case 'magento': featured_image = 'plugins_icons/magento.png'; break;
				case 'shopify': featured_image = 'plugins_icons/shopify.png'; break;
				case 'bigcommerce': featured_image = 'plugins_icons/Big-Commerce.jpg'; break;
				case 'paypal': featured_image = 'plugins_icons/paypal.png'; break;
				case 'oscommerce': featured_image = 'plugins_icons/oscommerce.png'; break;
				case 'zencart': featured_image = 'plugins_icons/zencart.png'; break;
				case 'xcart': featured_image = 'plugins_icons/xcart.png'; break;
				case 'laravel': featured_image = 'plugins_icons/laravel.png'; break;
				case 'cakephp': featured_image = 'plugins_icons/cackphp.png'; break;
				case 'codeigniter': featured_image = 'plugins_icons/codeigniter.png'; break;
				case 'stripe': featured_image = 'plugins_icons/stripe.png'; break;
				default: featured_image = 'plugins_icons/order.jpg';
			}
		}
		$('.campaign_default_image').attr('src', '<?= base_url('assets/images/')?>'+featured_image);
		var img = new Image();
		img.onload = function() {
			var sizeText = img.width + "x" + img.height;
			$('.campaign_default_image').closest('tr').find('.size-input').val(sizeText);
			$('.campaign_default_image').closest('tr').find('.size-display').text(sizeText);
		};
		img.src = '<?= base_url('assets/images/')?>'+featured_image;
	})();

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

	$("#addProgram .addProgramToFrom").on('click',function(){
		$this = $("#addProgram form");

		$.ajax({
			url:'<?= base_url('integration/editProgram') ?>',
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			success:function(result){
				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();

				if(result['newOption']){
					$("select[name='program_id']").append(result['newOption']);
					$this[0].reset();
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

	$("#addCategory .addCategoryToFrom").on('click',function(){
		$this = $("#addCategory form");

		$.ajax({
			url:'<?= base_url('integration/integration_category_add') ?>',
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			success:function(result){
				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();

				if(result['message']){
					$("#addCategory form select[name='parent_id']").append(result['newOption']);
					$this[0].reset();

					alert(result['message']);
					$("#addCategory").modal('hide');
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

			var $newRow = $('<tr>\
				<td>\
				<div class="d-flex align-items-center gap-3">\
				<img class="img-thumbnail campaign-img campaign_default_image integration_css-banner-thumb h-auto flex-shrink-0" src="<?= base_url('assets/images/') ?>'+featured_image+'">\
				<input type="file" accept="image/*" class="form-control form-control-sm file-input" name="custom_banner[]">\
				</div>\
				<input type="hidden" name="keep_ads[]" value="0">\
				</td>\
				<td class="text-center"><span class="badge bg-primary size-display"></span><input type="hidden" class="size-input" name="custom_banner_size[]"></td>\
				<td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-custom-image"><i class="bi bi-trash-fill"></i></button></td>\
				</tr>');
			$(".banner-table tbody").append($newRow);
			
			var img = new Image();
			img.onload = function() {
				var sizeText = img.width + "x" + img.height;
				$newRow.find(".size-input").val(sizeText);
				$newRow.find(".size-display").text(sizeText);
			};
			img.src = '<?= base_url('assets/images/') ?>'+featured_image;
		}

		if($(".banner-table tbody tr").length >= 50){
			$(".add-banner").hide();
		}

		rendeCampignDefaultImages('load');
	})

	$(".banner-table tbody").delegate(".remove-custom-image","click",function(){
		if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;
		$(".add-banner").show();
		$(this).parents("tr").remove();
		if($(".banner-table tbody tr").length == 0){
			$(".add-banner").click();
		}
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
				$tr.find("img").removeClass("campaign_default_image");
				$tr.find("img").css("display", "");
				$tr.find("img").attr('src', e.target.result);
				$tr.find("[name=keep_ads]").val('0');
			}

			reader.readAsDataURL(input.files[0]);
		}
	});

	function removeCampignDefaultClass(element) {
		$(element).removeClass("campaign_default_image");
	}
	$(".btn-save").on('click',function(){
		$btn = $(this);
		$this = $("#form_tools");
		var formData = new FormData($this[0]);
		if($(this).hasClass('save-not-close')){
			formData.append("save_close",true);
		}
		formData = formDataFilter(formData);
		$btn.prop("disabled",true);
		$.ajax({
			url:'<?= base_url('integration/integration_tools_form_post') ?>',
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
				$('#stripe-error-alert').addClass('d-none');


				if(result['location']){ window.location = result['location']; }

				if(result['errors']){
					$.each(result['errors'], function(i,j){
						if(i == 'stripe') {
							$('#stripe-error-message').text(j);
							$('#stripe-error-alert').removeClass('d-none');
							$('html, body').animate({
								scrollTop: $('#stripe-error-alert').offset().top - 100
							}, 500);
						} else if(i == 'custom_banner[]') {
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
	});

	var cache ={};
	$("#category_auto").autocomplete({
		source: function( request, response ) {
			var term = request.term;
			if ( term in cache ) {response( cache[ term ] );return;}

			$.getJSON( '<?= base_url('integration/category_auto') ?>', request, function( data, status, xhr ) {
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
					<i class="bi bi-trash remove-category"></i>\
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

	var tool_vendor_id = '<?= $tool['vendor_id'] ?>';
	$('#referlevel_select').on('change',function(){
		var level =  $(this).val();

		var html = '';
		for(var i = 1; i <= level; i++){
			html += '<tr>';
			html += '<td>'+i+'</td>';
			if(!tool_vendor_id){
				html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[reg_commission]" value="'+(levels[i] ? levels[i]['reg_commission'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text refer-reg-symball"></span></div>															</div></td>';
			}
			html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[sale_commition]" value="'+(levels[i] ? levels[i]['sale_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text refer-symball"></span></div>															</div></td>';
			html += '<td><input type="number" step="any" name="referlevel_'+i+'[commition]" value="'+(levels[i] ? levels[i]['commition'] : '' )+'" class="form-control" /></td>';
			html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_commition]" value="'+(levels[i] ? levels[i]['ex_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div></div></td>';
			html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_action_commition]" value="'+(levels[i] ? levels[i]['ex_action_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div></div></td>';
			html += '</tr>';
		}

		$('#tbl_refer_level tbody').html(html);

		chnage_teigger();
		chnage_teigger2();
	});

	$(document).on('click','.edit-comment', function(){
		var id = $(this).data('id');
		var comment_content = $('.comment-content-'+id).text();
		$('.comment-box').text(comment_content);
		$('#updateid').val(id);
		$('#btnUpdateArea').removeClass('d-none');
	});
	$(document).on('click','#btnUpdate',function(){
		var comment_content = $('.comment-box').val();
		$this = $(this);
		if(comment_content.trim() !=""){
			var id = $('#updateid').val();
			$('.comment-content-'+id).text($('.comment-box').val());
			var tool_id = window.location.href.split("/").pop();

			$.ajax({
				url:'<?= base_url("integration/updateComment") ?>',
				type:'POST',
				dataType:'json',
				data:{id:id,comment:comment_content,tool_id},
				beforeSend:function(){ $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...'); },
				complete:function(){ $this.prop('disabled', false).html($this.data('original-text') || 'Submit'); },
				success:function(json){
					console.log(json)
					$('#btnUpdateArea').addClass('d-none');
					$('.comment-box').val('')
					$('#updateid').val('');
				},
			})

		} else {
			alert("<?= __('admin.cant_send_blank_message') ?>")
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
				beforeSend:function(){// $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
				 },
				complete:function(){ //$this.prop('disabled', false).html($this.data('original-text') || 'Submit'); 
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
			$('.cookies_type_input_rev').hide();
		} else {
			$('.cookies_type_input').hide();
			$('.cookies_type_input_rev').show();
		}
	});

	// Integration Setup Wizard: Method card selection
	$(document).on('click', '.intg-method-card', function() {
		var method = $(this).data('method');
		$('#integration_method').val(method);

		// Update card styles
		$('.intg-method-card').removeClass('border-primary border-warning border-info border-secondary border-dark shadow');
		$('.intg-method-card .card-footer').remove();
		var colorMap = {js_pixel:'primary', s2s:'warning', s2s_direct:'info', postback:'secondary', conversion_api:'dark'};
		$(this).addClass('border-' + colorMap[method] + ' shadow');
		$(this).append('<div class="card-footer bg-' + colorMap[method] + ' text-' + (method === 's2s' ? 'dark' : 'white') + ' text-center py-1 small fw-bold"><i class="bi bi-check-circle me-1"></i><?= __("admin.active") ?></div>');

		// Auto-set hidden s2s flags
		$('#s2s_enabled').val(method === 's2s' || method === 's2s_direct' ? '1' : '0');
		$('#s2s_direct_mode').val(method === 's2s_direct' ? '1' : '0');

		// Show/hide method content sections
		$('.intg-method-content').slideUp(200);
		$('#method-' + method).slideDown(200);

		// Move postback/conversion_api panes into wizard if selected
		if (method === 'postback') {
			$('#postback-setting').appendTo('#method-postback-settings');
			$('#postback-setting').show().addClass('show active');
		} else if (method === 'conversion_api') {
			$('#conversion_api').appendTo('#method-conversion_api-docs');
			$('#conversion_api').show().addClass('show active');
		}

	});

	// JS Pixel: Show code button inside wizard
	$(document).on('click', '.btn-show-code-wizard', function() {
		var $this = $(this);
		var originalHtml = $this.html();
		$.ajax({
			url: '<?= base_url("integration/integration_code_modal") ?>',
			type: 'POST',
			dataType: 'html',
			data: { id: $this.attr("data-id") },
			beforeSend: function() { $this.prop('disabled', true).html('<i class="bi bi-arrow-repeat intg-spin me-1"></i><?= __("admin.loading") ?>...'); },
			complete: function() { $this.prop('disabled', false).html(originalHtml); },
			success: function(html) { $("#showcode-code .modal-dialog").html(html); $("#showcode-code").modal("show"); },
			error: function() { showToast('<?= __("admin.error") ?>', '<?= __("admin.failed_to_load_code") ?>', 'error'); }
		});
	});

	// S2S Phase 3: Copy to clipboard helper
	function copyToClipboard(elementId) {
		var input = document.getElementById(elementId);
		input.select();
		input.setSelectionRange(0, 99999);
		navigator.clipboard.writeText(input.value).then(function() {
			var btn = input.nextElementSibling;
			var originalHtml = btn.innerHTML;
			btn.innerHTML = '<i class="bi bi-check-lg"></i> <?= __("admin.s2s_copied") ?>';
			btn.classList.remove('btn-outline-secondary');
			btn.classList.add('btn-success');
			setTimeout(function() {
				btn.innerHTML = originalHtml;
				btn.classList.remove('btn-success');
				btn.classList.add('btn-outline-secondary');
			}, 2000);
		});
	}

	// Tool type change handler
	$(document).on('change', '#tool_type', function() {
		var toolType = $(this).val();
		if (toolType === 'program') {
			$('.for-program-tool').show();
		} else {
			$('.for-program-tool').hide();
		}
	});
	
	// Stripe integration plugin handler
	function handleStripeFields() {
		var selectedPlugin = $('#tool_integration_plugin').val();
		
		if (selectedPlugin === 'stripe') {
			// Show Stripe fields
			$('#stripe-fields-card').slideDown();
			
			// Make target URL readonly and optional
			$('#target_link').attr('readonly', true).addClass('bg-light').removeAttr('required');
			$('#target-required').hide();
			$('#stripe-target-help').show();
			
			// Set placeholder if empty
			if (!$('#target_link').val()) {
				$('#target_link').attr('placeholder', 'Will be auto-generated by Stripe after saving...');
			}
			
			// Make Stripe fields required
			$('#stripe_price, #stripe_currency').attr('required', true);
		} else {
			// Hide Stripe fields
			$('#stripe-fields-card').slideUp();
			
			// Make target URL editable and required again
			$('#target_link').attr('required', true).attr('readonly', false).removeClass('bg-light').attr('placeholder', '');
			$('#target-required').show();
			$('#stripe-target-help').hide();
			
			// Remove Stripe field requirements
			$('#stripe_price, #stripe_currency').removeAttr('required');
		}
	}
	
	// Handle plugin change
	$(document).on('change', '#tool_integration_plugin', function() {
		handleStripeFields();
	});
	
	// Initialize on page load
	$(document).ready(function() {
		handleStripeFields();
		
		<?php if(isset($tool['tool_integration_plugin']) && $tool['tool_integration_plugin'] == 'stripe'): ?>
		$('#stripe-fields-card').show();
		<?php endif; ?>
	});

	// Tool period visibility handler
	$('#tool_period').on('change', function() {
		var period = $(this).val();
		$('#start_date_input, #end_date_input').hide();
		
		if (period == '2' || period == '4') {
			$('#end_date_input').show();
		}
		if (period == '3' || period == '4') {
			$('#start_date_input').show();
		}
	});

	// Initialize tool type visibility on page load
	$(document).ready(function() {
		if ($('#tool_type').val() === 'program') {
			$('.for-program-tool').show();
		}

		// Initialize tool period visibility
		$('#tool_period').trigger('change');

		// Auto-select correct tab based on integration method
		var currentMethod = $('#integration_method').val() || 'js_pixel';
		if (currentMethod !== 'js_pixel') {
			setTimeout(function() {
				$('#TabsNav button[data-bs-target="#integration-setup"]').tab('show');
			}, 50);
		}

		// Tab memory functionality
		var tabMemoryKey = 'integration_tools_form_active_tab_' + (<?= isset($tool['id']) ? (int)$tool['id'] : 0 ?>);

		$('#TabsNav button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
			var activeTab = $(e.target).attr('data-bs-target');
			localStorage.setItem(tabMemoryKey, activeTab);
		});

		// URL hash override
		var hash = window.location.hash;
		if (hash === '#s2s-section' || hash === '#integration-setup') {
			$('#TabsNav button[data-bs-target="#integration-setup"]').tab('show');
		}
	});
</script>

<!-- Show Code Modal (for JS Pixel method card) -->
<div class="modal fade" id="showcode-code" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>