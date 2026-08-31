<div class="container-fluid px-4 pb-4">
<?php get_instance()->load->view('admincontrol/store/_store_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form class="form-horizontal" autocomplete="off" method="post" action="<?= base_url('admincontrol/store_setting') ?>" enctype="multipart/form-data" id="setting-form">

                        <div class="row">

            <ul class="nav nav-tabs nav-fill mb-4" role="tablist" id="TabsNav">
                <li class="nav-item">
                    <a class="nav-link active show px-2" data-bs-toggle="tab" href="#store_main" role="tab">
                        <i class="bi bi-gear-fill me-1"></i><?= __('admin.store_settings') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2" data-bs-toggle="tab" href="#product_setting" role="tab">
                        <i class="bi bi-percent me-1"></i><?= __('admin.store_commission') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2" data-bs-toggle="tab" href="#shipping_setting" role="tab">
                        <i class="bi bi-truck me-1"></i><?= __('admin.shipping_settings') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2" data-bs-toggle="tab" href="#tax_setting" role="tab">
                        <i class="bi bi-calculator me-1"></i><?= __('admin.tax_settings') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2" data-bs-toggle="tab" href="#order_comment" role="tab">
                        <i class="bi bi-chat-text me-1"></i><?= __('admin.order_comment') ?>
                    </a>
                </li>
                <?php  
                    if ((! isset($store['store_mode'])) || $store['store_mode'] == 'cart') {
                        $cart_theme_settings_display = '';
                        $sales_theme_settings_display = 'd-none';
                    } else {
                        $cart_theme_settings_display = 'd-none';
                        $sales_theme_settings_display = '';
                    }
                ?>
                <li class="nav-item cart_theme_settings <?= $cart_theme_settings_display ?>">
                    <a class="nav-link px-2" data-bs-toggle="tab" href="#theme_section" role="tab">
                        <i class="bi bi-palette me-1"></i><?= __('admin.theme_section') ?>
                    </a>
                </li>
                <li class="nav-item cart_theme_settings <?= $cart_theme_settings_display ?>">
                    <a class="nav-link px-2" data-bs-toggle="tab" href="#static_pages_section" role="tab">
                        <i class="bi bi-file-text me-1"></i><?= __('admin.static_pages_section') ?>
                    </a>
                </li>
                <li class="nav-item cart_theme_settings <?= $cart_theme_settings_display ?>">
                    <a class="nav-link px-2" data-bs-toggle="tab" href="#pages_menu_section" role="tab">
                        <i class="bi bi-menu-button-wide me-1"></i><?= __('admin.pages_and_menu') ?>
                    </a>
                </li>
                <li class="nav-item sales_theme_settings <?= $sales_theme_settings_display ?>">
                    <a class="nav-link px-2" data-bs-toggle="tab" href="#pages_menu_section" role="tab">
                        <i class="bi bi-palette me-1"></i><?= __('admin.theme_section') ?>
                    </a>
                </li>
            </ul>

<div class="col-sm-12">
    <div class="tab-content">
        <div class="tab-pane fade p-3" id="shipping_setting" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-truck me-2"></i><?= __('admin.shipping_charge') ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= __('admin.allow_shipping_in_all_country') ?></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input type="radio" <?= (int)$shipping_setting['shipping_in_limited'] == 0 ? 'checked' : '' ?> class="form-check-input shipping_in_limited" name="shipping_setting[shipping_in_limited]" value="0" id="shipping_all"> 
                                        <label class="form-check-label" for="shipping_all">
                                            <?= __('admin.yes_all_country') ?>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" <?= (int)$shipping_setting['shipping_in_limited'] == 1 ? 'checked' : '' ?> class="form-check-input shipping_in_limited" name="shipping_setting[shipping_in_limited]" value="1" id="shipping_custom"> 
                                        <label class="form-check-label" for="shipping_custom">
                                            <?= __('admin.no_custom_country') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= __('admin.shipping_error_message') ?></label>
                                <input type="text" value="<?= $shipping_setting['shipping_error_message'] ?>" name="shipping_setting[shipping_error_message]" class="form-control">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle shipping-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-medium"><?= __('admin.country') ?></th>
                                            <th class="fw-medium" width="180px"><?= __('admin.shipping_cost') ?></th>
                                            <th width="50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <button class="btn btn-primary btn-sm btn-shipping-rule" type="button">
                                                    <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new_rule') ?>
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-gear me-2"></i><?= __('admin.usps_api_configuration') ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= __('admin.enable_usps_shipping') ?></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input type="radio" <?= (int)$shipping_setting['usps_enabled'] == 1 ? 'checked' : '' ?> name="shipping_setting[usps_enabled]" value="1" class="form-check-input"> 
                                        <label class="form-check-label"><?= __('admin.yes') ?></label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" <?= (int)$shipping_setting['usps_enabled'] == 0 ? 'checked' : '' ?> name="shipping_setting[usps_enabled]" value="0" class="form-check-input"> 
                                        <label class="form-check-label"><?= __('admin.no') ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= __('admin.usps_api_key') ?></label>
                                <input type="text" value="<?= isset($shipping_setting['usps_api_key']) ? $shipping_setting['usps_api_key'] : '' ?>" name="shipping_setting[usps_api_key]" class="form-control" placeholder="Enter USPS API Key">
                                <small class="form-text text-muted">
                                    <strong>How to get USPS API Key:</strong><br>
                                    1. Visit <a href="https://www.usps.com/business/web-tools-apis/" target="_blank">USPS Web Tools</a><br>
                                    2. Register for a business account<br>
                                    3. Request API access<br>
                                    4. Wait for approval (1-3 business days)<br>
                                    5. Receive your API key via email<br><br>
                                    <strong>Demo API Key (for testing):</strong> <code>9400100000000000000000</code><br>
                                    <strong>Note:</strong> USPS API is completely free with no rate limits
                                </small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= __('admin.usps_origin_zip') ?></label>
                                <input type="text" value="<?= isset($shipping_setting['usps_origin_zip']) ? $shipping_setting['usps_origin_zip'] : '' ?>" name="shipping_setting[usps_origin_zip]" class="form-control" placeholder="Enter Origin ZIP Code">
                                <small class="form-text text-muted">
                                    <strong>Origin ZIP Code:</strong> This is where your products will be shipped from (your warehouse/store location)<br>
                                    <strong>Example:</strong> <code>10001</code> (New York) or <code>90210</code> (Beverly Hills)<br>
                                    <strong>Note:</strong> Must be a valid US ZIP code (5 digits)
                                </small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= __('admin.usps_rate_cache_time') ?> (seconds)</label>
                                <input type="number" value="<?= isset($shipping_setting['usps_rate_cache_time']) ? $shipping_setting['usps_rate_cache_time'] : '900' ?>" name="shipping_setting[usps_rate_cache_time]" class="form-control" placeholder="900" min="300" max="3600">
                                <small class="form-text text-muted">
                                    <strong>Rate Cache Time:</strong> How long to cache USPS rates before requesting new ones<br>
                                    <strong>Recommended:</strong> 900 seconds (15 minutes)<br>
                                    <strong>Range:</strong> 300-3600 seconds (5 minutes to 1 hour)<br>
                                    <strong>Note:</strong> Lower values = more accurate rates but more API calls
                                </small>
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-info" onclick="testUSPSConnection()"><?= __('admin.test_usps_connection') ?></button>
                                <div id="usps-test-result" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

			<script type="text/javascript">
				var shipping_index = 0;
				<?php 
				$country_options = '';
				foreach ($country as $key => $value) { 
					$country_options .= '<option value="'. $value->id .'">'. str_replace("'", '', $value->name) .'</option>';
				} 
				?>
				$(".shipping_in_limited").on("change",function(){
					var val = $(this).val();
					$(".shipping-country-table").hide();
					if(val == 1){
						$(".shipping-country-table").show();
					}
				})
				$(".shipping_in_limited:checked").trigger("change");
				function addShippingCountry(country) {
					var shipping_html = '';
					shipping_html += '<tr>';
					shipping_html += '	<td>';
					shipping_html += '		<select name="shipping_setting[allow_country][]" class="form-control">';
					shipping_html += '			<option value=""><?= __('admin.choose_country') ?></option>';
					shipping_html += '			<?= $country_options ?>';
					shipping_html += '		</select>';
					shipping_html += '	</td>';
					shipping_html += '	<td>';
					shipping_html += '		<button class="btn btn-danger remove-tr" type="button"><i class="fa fa-trash"></i></button>';
					shipping_html += '	</td>';
					shipping_html += '</tr>';
					$ship = $(shipping_html);
					$ship.find("select").val(country);
					$ship.appendTo(".shipping-country-table tbody");
					shipping_index++;
				}
				$(".btn-shipping-country").click(function(){
					addShippingCountry('');
				})
				$(".shipping-country-table, .shipping-table").delegate(".remove-tr","click", function(){
					$(this).parents("tr").remove();
				})
				$(".btn-shipping-rule").click(function(){
					addShippingRule('',0);
				})
				function addShippingRule(country,cost) {
					var shipping_html = '';
					shipping_html += '<tr>';
					shipping_html += '	<td>';
					shipping_html += '		<select name="shipping_setting[cost]['+ shipping_index +'][country]" class="form-control taxc-'+ shipping_index +'">';
					shipping_html += '			<option value=""><?= __('admin.choose_country') ?></option>';
					shipping_html += '			<?= $country_options ?>';
					shipping_html += '		</select>';
					shipping_html += '	</td>';
					shipping_html += '	<td><input type="" name="shipping_setting[cost]['+ shipping_index +'][cost]" onkeydown="if(event.key===\'.\'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,\'\');" class="form-control ssv-'+ shipping_index +'"></td>';
					shipping_html += '	<td>';
					shipping_html += '		<button class="btn btn-danger  remove-tr" type="button"><i class="fa fa-trash"></i></button>';
					shipping_html += '	</td>';
					shipping_html += '</tr>';
					$ship = $(shipping_html);
					$ship.find("select").val(country);
					$ship.find("input").val(cost);
					$ship.appendTo(".shipping-table tbody");
					shipping_index++;
				}
				<?php 
				$allow_country = (array)(isset($shipping_setting['allow_country']) ? json_decode($shipping_setting['allow_country'],1) : []);
				foreach (array_unique($allow_country) as $key => $value) {
					echo "addShippingCountry('". (int)$value ."');";
				}
				$cost = (array)(isset($shipping_setting['cost']) ? json_decode($shipping_setting['cost'],1) : []);
				foreach ($cost as $key => $value) {
					echo "addShippingRule('". (int)$value['country'] ."','". (float)$value['cost'] ."');";
				}
				?>
			</script>

			<script type="text/javascript">
				function testUSPSConnection() {
					var apiKey = $('input[name="shipping_setting[usps_api_key]"]').val();
					var originZip = $('input[name="shipping_setting[usps_origin_zip]"]').val();
					
					if (!apiKey || !originZip) {
						$('#usps-test-result').html('<div class="alert alert-warning">Please enter API key and origin ZIP code first.</div>');
						return;
					}
					
					$('#usps-test-result').html('<div class="alert alert-info">Testing connection...</div>');
					
					$.ajax({
						url: '<?= base_url("admincontrol/test_usps_connection") ?>',
						type: 'POST',
						data: {
							usps_api_key: apiKey,
							usps_origin_zip: originZip
						},
						dataType: 'json',
						success: function(response) {
							if (response.status) {
								$('#usps-test-result').html('<div class="alert alert-success">' + response.message + '</div>');
							} else {
								$('#usps-test-result').html('<div class="alert alert-danger">' + response.message + '</div>');
							}
						},
						error: function() {
							$('#usps-test-result').html('<div class="alert alert-danger">Connection test failed. Please check your settings.</div>');
						}
					});
				}
			</script>
		</div>

<!--tax_setting-->			
        <div class="tab-pane fade p-3" id="tax_setting" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-calculator me-2"></i><?= __('admin.tax_charge') ?></h6>
                </div>
                <div class="card-body">
            <div class="mb-3">
                <label class="form-label"><?= __('admin.allow_tax_in_all_country') ?></label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input tax_status" type="radio" id="taxDisable" name="tax_setting[tax_status]" value="0" <?= (int)$tax_setting['tax_status'] == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="taxDisable"><?= __('admin.tax_disable') ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input tax_status" type="radio" id="taxAllCountry" name="tax_setting[tax_status]" value="1" <?= (int)$tax_setting['tax_status'] == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="taxAllCountry"><?= __('admin.yes_all_country') ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input tax_status" type="radio" id="taxCustomCountry" name="tax_setting[tax_status]" value="2" <?= (int)$tax_setting['tax_status'] == 2 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="taxCustomCountry"><?= __('admin.no_custom_country') ?></label>
                    </div>
                </div>
            </div>
            <div class="mb-3 common_tax_percentage_inp">
                <label class="form-label"><?= __('admin.tax_cost') ?></label>
                <input type="text" name="tax_setting[common_tax_percentage]" onkeydown="if(event.key==='.'){event.preventDefault();}" oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');" class="form-control common_tax_percentage" value="<?= $tax_setting['common_tax_percentage']; ?>"/>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle tax-table">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-medium"><?= __('admin.country') ?></th>
                            <th class="fw-medium" width="180px"><?= __('admin.tax_cost') ?></th>
                            <th width="50px"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end">
                                <button class="btn btn-primary btn-sm btn-tax-rule" type="button">
                                    <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new_rule') ?>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var tax_index = 0;
        <?php 
        $country_options = '';
        foreach ($country as $key => $value) { 
            $country_options .= '<option value="'. $value->id .'">'. str_replace("'", '', $value->name) .'</option>';
        } 
        ?>

        $(".tax_status").on("change",function(){
            var val = $(this).val();
            $(".common_tax_percentage_inp").hide();
            $(".tax-table").hide();
            if(val == 1){
                $(".common_tax_percentage_inp").show();
            }
            if(val == 2){
                $(".tax-table").show();
            }
        })

        $(".tax_status:checked").trigger("change");

        $(".tax-table").delegate(".remove-tr","click", function(){
            $(this).parents("tr").remove();
        })
        $(".btn-tax-rule").click(function(){
            addtaxRule('',0);
        })
        function addtaxRule(country,cost) {
            var tax_html = '';
            tax_html += '<tr>';
            tax_html += '    <td>';
            tax_html += '        <select name="tax_setting[cost]['+ tax_index +'][country]" class="form-control taxc-'+ tax_index +'">';
            tax_html += '            <option value=""><?= __('admin.choose_country') ?></option>';
            tax_html += '            <?= $country_options ?>';
            tax_html += '        </select>';
            tax_html += '    </td>';
            tax_html += '    <td><input type="" name="tax_setting[cost]['+ tax_index +'][cost]" onkeydown="if(event.key===\'.\'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,\'\');" class="form-control taxv-'+ tax_index +'"></td>';
            tax_html += '    <td>';
            tax_html += '        <button class="btn btn-danger  remove-tr" type="button"><i class="bi bi-trash"></i></button>';
            tax_html += '    </td>';
            tax_html += '</tr>';
            $ship = $(tax_html);
            $ship.find("select").val(country);
            $ship.find("input").val(cost);
            $ship.appendTo(".tax-table tbody");
            tax_index++;
        }
        <?php 
        $allow_country = (array)(isset($tax_setting['allow_country']) ? json_decode($tax_setting['allow_country'],1) : []);

        $cost = (array)(isset($tax_setting['cost']) ? json_decode($tax_setting['cost'],1) : []);
        foreach ($cost as $key => $value) {
            echo "addtaxRule('". (int)$value['country'] ."','". (float)$value['cost'] ."');";
        }
        ?>
    </script>
</div>
<!--tax_setting-->


        <div class="tab-pane fade show active p-3" id="store_main" role="tabpanel">

	<!-- Sub-navigation for Store Settings -->
	<div class="mb-4">
		<ul class="nav nav-tabs nav-justified bg-light rounded p-2" role="tablist" id="storeSubTabs">
			<li class="nav-item">
				<a class="nav-link active px-3 py-2" data-bs-toggle="tab" href="#basic_settings" role="tab">
					<i class="bi bi-gear me-1"></i><?= __('admin.basic_settings') ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link px-3 py-2" data-bs-toggle="tab" href="#appearance_settings" role="tab">
					<i class="bi bi-palette me-1"></i><?= __('admin.appearance_settings') ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link px-3 py-2" data-bs-toggle="tab" href="#contact_analytics" role="tab">
					<i class="bi bi-graph-up me-1"></i><?= __('admin.contact_analytics') ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link px-3 py-2" data-bs-toggle="tab" href="#smart_commerce_settings" role="tab">
					<i class="bi bi-lightning me-1"></i><?= __('admin.smart_commerce') ?>
				</a>
			</li>
		</ul>
	</div>

	<!-- Sub-tab Content -->
	<div class="tab-content">
		<!-- Basic Settings Sub-tab -->
		<div class="tab-pane fade show active" id="basic_settings" role="tabpanel">

	<!-- Store Name Section -->
	<div class="mb-4">
		<div class="bg-white rounded shadow-sm border overflow-hidden">
			<div class="bg-primary bg-opacity-10 border-bottom p-3">
				<h6 class="text-dark mb-0 fw-medium">
					<i class="fas fa-store me-2 text-primary"></i><?= __('admin.store_name') ?>
				</h6>
			</div>
			<div class="p-3">
				<div class="form-floating">
					<input name="store[name]" value="<?php echo $store['name']; ?>" class="form-control" type="text" id="storeName" placeholder="<?= __('admin.store_name') ?>">
					<label for="storeName" class="text-muted"><?= __('admin.store_name') ?></label>
				</div>
			</div>
		</div>
	</div>

	<!-- Store Settings Grid -->
	<div class="mb-4">
		<h6 class="fw-medium text-dark mb-3"><?= __('admin.store_configuration') ?></h6>
		<div class="row g-3">
			<div class="col-md-4">
				<div class="bg-white rounded shadow-sm border p-3 h-100 position-relative overflow-hidden">
					<div class="position-absolute top-0 start-0 w-100" style="height: 3px; background-color: #dc3545;"></div>
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center">
							<div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3">
								<i class="fas fa-power-off text-danger"></i>
							</div>
							<div>
								<h6 class="mb-0 fw-medium"><?= __('admin.store_status') ?></h6>
								<small class="text-muted"><?= __('admin.enable_disable_store') ?></small>
							</div>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input store_status" type="checkbox" <?= $store['status']==1 ? 'checked' : '' ?> id="store_status_switch" data-bs-on="On" data-bs-off="Off">
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="bg-white rounded shadow-sm border p-3 h-100 position-relative overflow-hidden">
					<div class="position-absolute top-0 start-0 w-100" style="height: 3px; background-color: #0d6efd;"></div>
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center">
							<div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
								<i class="fas fa-bars text-primary"></i>
							</div>
							<div>
								<h6 class="mb-0 fw-medium"><?= __('admin.display_store_menu_on_front_side') ?></h6>
								<small class="text-muted"><?= __('admin.show_menu_frontend') ?></small>
							</div>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input menu_on_front" type="checkbox" <?= $store['menu_on_front']==1 ? 'checked' : '' ?> id="menu_front_switch" data-bs-on="On" data-bs-off="Off">
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="bg-white rounded shadow-sm border p-3 h-100 position-relative overflow-hidden">
					<div class="position-absolute top-0 start-0 w-100" style="height: 3px; background-color: #20c997;"></div>
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center">
							<div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
								<i class="fas fa-external-link-alt text-success"></i>
							</div>
							<div>
								<h6 class="mb-0 fw-medium"><?= __('admin.open_in_new_tab') ?></h6>
								<small class="text-muted"><?= __('admin.open_links_new_tab') ?></small>
							</div>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input menu_on_front_blank" type="checkbox" <?= $store['menu_on_front_blank']==1 ? 'checked' : '' ?> id="new_tab_switch" data-bs-on="On" data-bs-off="Off">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		</div>

		<!-- Appearance Settings Sub-tab -->
		<div class="tab-pane fade" id="appearance_settings" role="tabpanel">

	<!-- Theme Mode Section — Redesigned -->
	<div class="mb-4">
		<div class="bg-white rounded-3 shadow-sm border-0 overflow-hidden" style="box-shadow:0 2px 12px rgba(0,0,0,.07)!important;">

			<!-- Section header -->
			<div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);">
				<div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:#f59e0b;">
					<i class="fas fa-palette text-white"></i>
				</div>
				<div>
					<h6 class="mb-0 fw-semibold"><?= __('admin.store_theme') ?></h6>
					<div class="text-muted" style="font-size:.78rem;">Choose your store mode and activate a theme design</div>
				</div>
				<a href="<?= base_url('admincontrol/store_theme_api_doc') ?>" class="btn btn-sm ms-auto" style="background:#1e3a5f;color:#fff;font-size:.77rem;" target="_blank">
					<i class="bi bi-code-slash me-1"></i>Theme API Reference
				</a>
			</div>

			<div class="p-4 theme-config-section">
				<?php if($store['status']!=1): ?>
				<div class="alert alert-warning d-flex align-items-center gap-2 mb-4 py-2">
					<i class="fas fa-exclamation-triangle store-disabled-warning-icon"></i>
					<span class="small store-disabled-warning"><?= __('admin.store_disabled') ?>: <?= __('admin.enable_store_configure_theme') ?></span>
				</div>
				<?php endif; ?>

				<div class="row g-4">

					<!-- LEFT: Mode selector -->
					<div class="col-lg-5">
						<div class="fw-semibold mb-3" style="font-size:.85rem;color:#374151;">
							<i class="bi bi-toggles me-1 text-primary"></i><?= __('admin.select_theme_mode') ?>
						</div>
						<div class="d-flex flex-column gap-3">

						<!-- Hidden field — keeps current mode in the main form so Save Settings always persists it -->
						<input type="hidden" name="store[store_mode]" id="store_mode_hidden"
						       value="<?= ((! isset($store['store_mode'])) || $store['store_mode']=='cart') ? 'cart' : 'sales' ?>">

						<!-- Cart Mode -->
						<input class="form-check-input store_mode d-none" type="radio" name="store_mode_radio"
						       value="cart" id="cart_mode"
						       <?= ((! isset($store['store_mode'])) || $store['store_mode']=='cart') ? 'checked' : '' ?>>
							<label class="tss-mode-card d-flex align-items-center gap-3 p-3 <?= ((! isset($store['store_mode'])) || $store['store_mode']=='cart') ? 'tss-active-cart' : '' ?>" for="cart_mode">
								<div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;background:#dbeafe;">
									<i class="fas fa-shopping-cart text-primary fs-4"></i>
								</div>
								<div class="flex-grow-1">
									<div class="fw-semibold mb-1"><?= __('admin.cart_mode') ?></div>
									<div class="text-muted" style="font-size:.77rem;">Full e-commerce store with cart, checkout, orders, LMS &amp; more</div>
									<div class="d-flex flex-wrap gap-1 mt-2">
										<span class="badge bg-primary bg-opacity-10 text-primary fw-normal" style="font-size:.65rem;">Cart &amp; Checkout</span>
										<span class="badge bg-primary bg-opacity-10 text-primary fw-normal" style="font-size:.65rem;">Products</span>
										<span class="badge bg-primary bg-opacity-10 text-primary fw-normal" style="font-size:.65rem;">LMS</span>
										<span class="badge bg-primary bg-opacity-10 text-primary fw-normal" style="font-size:.65rem;">Wishlist</span>
									</div>
								</div>
								<?php if((! isset($store['store_mode'])) || $store['store_mode']=='cart'): ?>
								<span class="badge bg-primary flex-shrink-0"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></span>
								<?php else: ?>
								<span class="badge bg-light text-muted border flex-shrink-0" style="font-size:.7rem;"><i class="fas fa-mouse-pointer me-1"></i><?= __('admin.click_to_activate') ?></span>
								<?php endif; ?>
							</label>

							<!-- Sales Mode -->
							<input class="form-check-input store_mode d-none" type="radio" name="store_mode_radio"
							       value="classified" id="sales_mode"
							       <?= (isset($store['store_mode']) && $store['store_mode']!='cart') ? 'checked' : '' ?>>
							<label class="tss-mode-card d-flex align-items-center gap-3 p-3 <?= (isset($store['store_mode']) && $store['store_mode']!='cart') ? 'tss-active-sales' : '' ?>" for="sales_mode">
								<div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;background:#d1fae5;">
									<i class="fas fa-store text-success fs-4"></i>
								</div>
								<div class="flex-grow-1">
									<div class="fw-semibold mb-1"><?= __('admin.sales_mode') ?></div>
									<div class="text-muted" style="font-size:.77rem;">Modern classified ads layout — ideal for selling without a full cart</div>
									<div class="d-flex flex-wrap gap-1 mt-2">
										<span class="badge bg-success bg-opacity-10 text-success fw-normal" style="font-size:.65rem;">Classified</span>
										<span class="badge bg-success bg-opacity-10 text-success fw-normal" style="font-size:.65rem;">Listings</span>
										<span class="badge bg-success bg-opacity-10 text-success fw-normal" style="font-size:.65rem;">Sales Pages</span>
									</div>
								</div>
								<?php if(isset($store['store_mode']) && $store['store_mode']!='cart'): ?>
								<span class="badge bg-success flex-shrink-0"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></span>
								<?php else: ?>
								<span class="badge bg-light text-muted border flex-shrink-0" style="font-size:.7rem;"><i class="fas fa-mouse-pointer me-1"></i><?= __('admin.click_to_activate') ?></span>
								<?php endif; ?>
							</label>

						</div>
					</div>

					<!-- RIGHT: Theme selector -->
					<div class="col-lg-7" id="sel_store_theme">
						<div class="fw-semibold mb-3" style="font-size:.85rem;color:#374151;">
							<i class="bi bi-grid-1x2 me-1 text-primary"></i><?= __('admin.active_theme') ?>
							<span class="text-muted fw-normal ms-1" style="font-size:.75rem;" id="current_mode_badge">
								— <?php if((! isset($store['store_mode'])) || $store['store_mode']=='cart'): ?><?= __('admin.cart_mode') ?><?php else: ?><?= __('admin.sales_mode') ?><?php endif; ?>
							</span>
						</div>

						<?php
						$cur_theme   = $store['theme'] ?? '0';
						$is_cart     = (! isset($store['store_mode'])) || $store['store_mode']=='cart';
						$is_default  = ($cur_theme === '0' || $cur_theme === '');
						$is_s26      = ($cur_theme === 'starter2026');
						$is_clsfd    = ($cur_theme === 'classified');
						?>

						<?php
						/* ── Theme list ──────────────────────────────────────────────────────────
						 * Built-in themes are always rendered (hardcoded — never disappear).
						 * Custom themes auto-discover by dropping a theme.json in their folder.
						 * ─────────────────────────────────────────────────────────────────────── */

						/* Built-in themes — always present */
						$builtin_cart  = [
							[
								'id'          => '0',
								'name'        => __('admin.theme_1'),
								'description' => 'Clean, traditional cart store. Solid foundation, fully documented, Bootstrap 5.',
								'version'     => '1.0.0',
								'author'      => 'System',
								'mode'        => 'cart',
								'sort'        => 1,
								'tags'        => ['Bootstrap 5', 'Responsive', 'Free'],
								'preview'     => ['gradient'=>'linear-gradient(135deg,#1e3a5f 0%,#0d6efd 100%)', 'emoji'=>'🛒', 'label'=>'Traditional E-commerce'],
							],
							[
								'id'          => 'starter2026',
								'name'        => 'Starter 2026',
								'description' => 'Modern premium design, LMS support, dark-mode ready, RTL compatible.',
								'version'     => '1.0.0',
								'author'      => 'System',
								'mode'        => 'cart',
								'sort'        => 2,
								'tags'        => ['Bootstrap 5', 'RTL', 'LMS', 'API v1', 'Premium'],
								'preview'     => ['gradient'=>'linear-gradient(135deg,#0f172a 0%,#7c3aed 60%,#f59e0b 100%)', 'emoji'=>'⚡', 'label'=>'Modern Premium Design'],
							],
						];
						$builtin_sales = [
							[
								'id'          => 'classified',
								'name'        => __('admin.classified'),
								'description' => 'Modern classified ads layout — ideal for selling without a full cart.',
								'version'     => '1.0.0',
								'author'      => 'System',
								'mode'        => 'sales',
								'sort'        => 1,
								'tags'        => ['Classified', 'Listings', 'Sales Pages'],
								'preview'     => ['gradient'=>'linear-gradient(135deg,#064e3b 0%,#10b981 100%)', 'emoji'=>'🏪', 'label'=>'Classified Sales Layout'],
							],
						];

						/* Scan for custom (third-party) themes via theme.json discovery */
						$custom_cart  = [];
						$custom_sales = [];

						/* Try multiple path strategies for maximum compatibility */
						$_store_path = null;
						$_candidates = [
							/* Strategy 1: relative to this view file's real path */
							realpath(dirname(__FILE__) . '/../../store'),
							/* Strategy 2: APPPATH constant */
							realpath(rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, APPPATH), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'store'),
							/* Strategy 3: absolute known path as fallback */
							realpath(FCPATH . '../application/views/store'),
						];
						foreach ($_candidates as $_c) {
							if ($_c && is_dir($_c)) { $_store_path = $_c . DIRECTORY_SEPARATOR; break; }
						}

						$_builtin_ids = ['0', 'default', 'starter2026', 'classified'];
						if ($_store_path) {
							foreach (scandir($_store_path) as $_entry) {
								if ($_entry === '.' || $_entry === '..') continue;
								$_dir = $_store_path . $_entry;
								if (!is_dir($_dir)) continue;
								$_mf = $_dir . DIRECTORY_SEPARATOR . 'theme.json';
								if (!file_exists($_mf)) continue;
								$_m = @json_decode(file_get_contents($_mf), true);
								if (!is_array($_m) || empty($_m['id'])) continue;
								/* Skip built-in themes — already in $builtin_cart / $builtin_sales */
								if (in_array($_m['id'], $_builtin_ids) || ($_m['author'] ?? '') === 'System') continue;
								if (($_m['mode'] ?? 'cart') === 'sales') {
									$custom_sales[] = $_m;
								} else {
									$custom_cart[] = $_m;
								}
							}
						}

						/* Merge: built-ins first, then custom sorted by sort field */
						usort($custom_cart,  fn($a,$b) => ($a['sort'] ?? 99) <=> ($b['sort'] ?? 99));
						usort($custom_sales, fn($a,$b) => ($a['sort'] ?? 99) <=> ($b['sort'] ?? 99));
						$all_cart  = array_merge($builtin_cart,  $custom_cart);
						$all_sales = array_merge($builtin_sales, $custom_sales);
						?>

						<!-- Instruction banner -->
						<div class="d-flex align-items-center gap-2 mb-3 px-3 py-2 rounded-2" style="background:#fffbeb;border:1px solid #fde68a;">
							<i class="bi bi-hand-index-thumb text-warning" style="font-size:1rem;"></i>
							<span style="font-size:.8rem;color:#92400e;">
								<strong>Click a theme card below</strong> to select it, then click <strong>Save Settings</strong> to apply.
							</span>
						</div>

						<!-- Cart-mode theme cards (auto-discovered) -->
						<div id="cart-theme-cards" class="<?= $is_cart ? '' : 'd-none' ?>">
							<div class="row g-3">

								<?php foreach ($all_cart as $tm):
									$tm_id      = $tm['id'];
									$is_active  = ($cur_theme === $tm_id) || ($is_default && $tm_id === '0');
									$gradient   = $tm['preview']['gradient'] ?? 'linear-gradient(135deg,#1e3a5f,#0d6efd)';
									$emoji      = $tm['preview']['emoji']    ?? '🛒';
									$plabel     = $tm['preview']['label']    ?? '';
									$tags       = $tm['tags']               ?? [];
									$is_sys     = ($tm['author'] ?? '') === 'System';
								?>
								<div class="col-md-6">
								<div class="tss-theme-card h-100 <?= ($is_cart && $is_active) ? 'tss-theme-active' : '' ?>"
								     onclick="tsSelectTheme(this,'<?= htmlspecialchars($tm_id) ?>')"
								     data-theme-val="<?= htmlspecialchars($tm_id) ?>"
								     title="Click to select this theme">
								<span class="tss-badge-selected"><i class="fas fa-check-circle me-1"></i>Active Theme</span>
								<span class="tss-badge-hover"><i class="bi bi-cursor me-1"></i>Select</span>
								<div class="tss-theme-preview" style="background:<?= $gradient ?>;">
									<div class="tss-active-overlay"><i class="fas fa-check-circle"></i></div>
									<div class="text-center text-white px-3">
										<div style="font-size:2rem;margin-bottom:.3rem;filter:drop-shadow(0 2px 4px rgba(0,0,0,.3));"><?= $emoji ?></div>
										<div style="font-size:.72rem;opacity:.9;letter-spacing:.03em;"><?= htmlspecialchars($plabel) ?></div>
									</div>
								</div>
									<div class="p-3">
										<div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
											<span class="fw-semibold" style="font-size:.88rem;"><?= htmlspecialchars($tm['name']) ?></span>
											<?php if ($is_sys): ?>
											<span class="badge bg-secondary bg-opacity-15 text-secondary fw-normal" style="font-size:.62rem;">Built-in</span>
											<?php else: ?>
											<span class="badge bg-success bg-opacity-15 text-success fw-normal" style="font-size:.62rem;"><i class="bi bi-person-check me-1"></i>Custom</span>
											<?php endif; ?>
											<?php if (!empty($tm['version'])): ?>
											<span class="text-muted ms-auto" style="font-size:.65rem;">v<?= htmlspecialchars($tm['version']) ?></span>
											<?php endif; ?>
										</div>
										<div class="text-muted mb-2" style="font-size:.75rem;line-height:1.4;"><?= htmlspecialchars($tm['description'] ?? '') ?></div>
										<div class="d-flex flex-wrap gap-1 align-items-center">
											<?php foreach ($tags as $tag): ?>
											<span class="badge bg-light border text-muted fw-normal" style="font-size:.62rem;"><?= htmlspecialchars($tag) ?></span>
											<?php endforeach; ?>
											<?php if (!$is_sys): ?>
											<button type="button"
											        class="btn btn-sm btn-outline-danger ms-auto tss-delete-theme-btn"
											        data-theme-id="<?= htmlspecialchars($tm_id) ?>"
											        data-theme-name="<?= htmlspecialchars($tm['name']) ?>"
											        onclick="event.stopPropagation(); tsConfirmDeleteTheme(this)"
											        style="font-size:.65rem;padding:.2rem .5rem;"
											        title="Delete this theme permanently">
											  <i class="bi bi-trash3"></i>
											</button>
											<?php endif; ?>
									</div>
								</div>
								<div class="tss-theme-active-footer"><i class="fas fa-check-circle me-1"></i><?= __('admin.active_theme') ?></div>
								</div>
							</div>
						<?php endforeach; ?>
						<!-- Create / add custom theme -->
								<div class="col-12">
									<div class="rounded-3 p-3 d-flex align-items-center gap-3 flex-wrap" style="background:#f0f5ff;border:1px dashed #93c5fd;">
										<i class="bi bi-plus-circle-fill text-primary flex-shrink-0" style="font-size:1.3rem;"></i>
										<div class="flex-grow-1">
											<div class="fw-semibold" style="font-size:.83rem;">Want a new theme?</div>
											<div class="text-muted" style="font-size:.75rem;">
												Auto-generate a ready-to-customize starter theme, or read the API docs to build your own.
											</div>
										</div>
										<div class="d-flex gap-2 flex-shrink-0">
											<button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createThemeModal">
												<i class="bi bi-magic me-1"></i>Generate Theme
											</button>
											<a href="<?= base_url('admincontrol/store_theme_api_doc') ?>" class="btn btn-sm btn-outline-primary" target="_blank">
												<i class="bi bi-book me-1"></i>API Docs
											</a>
										</div>
									</div>
								</div>

							</div>
						</div>

						<!-- Sales-mode themes -->
						<div id="sales-theme-info" class="<?= $is_cart ? 'd-none' : '' ?> store_mode_info">
							<div class="row g-3">
						<?php foreach ($all_sales as $tm):
							$tm_id     = $tm['id'];
							$is_active = !$is_cart && ($cur_theme === $tm_id);
							$gradient  = $tm['preview']['gradient'] ?? 'linear-gradient(135deg,#064e3b,#10b981)';
							$emoji     = $tm['preview']['emoji']    ?? '🏪';
							$plabel    = $tm['preview']['label']    ?? '';
							$tags      = $tm['tags']               ?? [];
							$is_sys_s  = ($tm['author'] ?? '') === 'System';
						?>
						<div class="col-md-6">
							<div class="tss-theme-card h-100 <?= $is_active ? 'tss-theme-active' : '' ?>"
							     onclick="tsSelectTheme(this,'<?= htmlspecialchars($tm_id) ?>')"
							     data-theme-val="<?= htmlspecialchars($tm_id) ?>"
							     title="Click to select this theme">
								<span class="tss-badge-selected"><i class="fas fa-check-circle me-1"></i>Active Theme</span>
								<span class="tss-badge-hover"><i class="bi bi-cursor me-1"></i>Select</span>
								<div class="tss-theme-preview" style="background:<?= $gradient ?>;">
									<div class="tss-active-overlay"><i class="fas fa-check-circle"></i></div>
									<div class="text-center text-white px-3">
										<div style="font-size:2rem;margin-bottom:.3rem;"><?= $emoji ?></div>
										<div style="font-size:.72rem;opacity:.9;"><?= htmlspecialchars($plabel) ?></div>
									</div>
								</div>
								<div class="p-3">
									<div class="d-flex align-items-center gap-2 mb-1">
										<span class="fw-semibold" style="font-size:.88rem;"><?= htmlspecialchars($tm['name']) ?></span>
										<?php if ($is_sys_s): ?>
										<span class="badge bg-secondary bg-opacity-15 text-secondary fw-normal" style="font-size:.62rem;">Built-in</span>
										<?php else: ?>
										<span class="badge bg-success bg-opacity-15 text-success fw-normal" style="font-size:.62rem;"><i class="bi bi-person-check me-1"></i>Custom</span>
										<?php endif; ?>
									</div>
									<div class="text-muted mb-2" style="font-size:.75rem;"><?= htmlspecialchars($tm['description'] ?? '') ?></div>
									<div class="d-flex flex-wrap gap-1 align-items-center">
										<?php foreach ($tags as $tag): ?>
										<span class="badge bg-light border text-muted fw-normal" style="font-size:.62rem;"><?= htmlspecialchars($tag) ?></span>
										<?php endforeach; ?>
										<?php if (!$is_sys_s): ?>
										<button type="button"
										        class="btn btn-sm btn-outline-danger ms-auto tss-delete-theme-btn"
										        data-theme-id="<?= htmlspecialchars($tm_id) ?>"
										        data-theme-name="<?= htmlspecialchars($tm['name']) ?>"
										        onclick="event.stopPropagation(); tsConfirmDeleteTheme(this)"
										        style="font-size:.65rem;padding:.2rem .5rem;"
										        title="Delete this theme permanently">
										  <i class="bi bi-trash3"></i>
										</button>
										<?php endif; ?>
									</div>
								</div>
								<div class="tss-theme-active-footer"><i class="fas fa-check-circle me-1"></i><?= __('admin.active_theme') ?></div>
							</div>
						</div>
						<?php endforeach; ?>
							</div>
						</div>

						<!-- Hidden select (form submission, updated by JS via tsSelectTheme) -->
						<select class="d-none" name="store[theme]" id="theme_name">
							<?php
							$opts_pool = $is_cart ? $all_cart : $all_sales;
							foreach ($opts_pool as $tm_opt):
								$sel = ($cur_theme === $tm_opt['id'] || ($is_default && $tm_opt['id'] === '0')) ? ' selected' : '';
							?>
							<option value="<?= htmlspecialchars($tm_opt['id']) ?>"<?= $sel ?>><?= htmlspecialchars($tm_opt['name']) ?></option>
							<?php endforeach; ?>
						</select>

					</div><!-- /col theme -->
				</div><!-- /row -->

				<div id="theme-change-message" class="mt-4"></div>
			</div>
		</div>
	</div>

	<script>
	/* Theme visual card selection — CSS drives all visual state via .tss-theme-active */
	function tsSelectTheme(card, val) {
		var group = card.closest('#cart-theme-cards, #sales-theme-info');
		if (group) {
			group.querySelectorAll('.tss-theme-card').forEach(function(c){
				c.classList.remove('tss-theme-active');
			});
		}
		card.classList.add('tss-theme-active');
		document.getElementById('theme_name').value = val;
	}
	/* Mode card visual update */
	$(document).on('change', '.store_mode', function(){
		var val = $(this).val();
		$('.tss-mode-card').removeClass('tss-active-cart tss-active-sales');
		$('.tss-mode-card .badge.bg-primary, .tss-mode-card .badge.bg-success').remove();
		if (val === 'cart') {
			$('label[for="cart_mode"]').addClass('tss-active-cart');
			$('#cart-theme-cards').removeClass('d-none');
			$('#sales-theme-info').addClass('d-none');
			$('#current_mode_badge').text('— <?= __('admin.cart_mode') ?>');
		} else {
			$('label[for="sales_mode"]').addClass('tss-active-sales');
			$('#cart-theme-cards').addClass('d-none');
			$('#sales-theme-info').removeClass('d-none');
			$('#current_mode_badge').text('— <?= __('admin.sales_mode') ?>');
		}
	});
	</script>

	<script type="text/javascript">
		// Store status change handler
		$(document).ready(function() {
			$('.store_status').on('change', function(){
				var isEnabled = $(this).prop('checked');
				var themeSection = $('.theme-config-section');
				
				if (isEnabled) {
					// Enable theme configuration
					themeSection.css({'opacity': '1', 'pointer-events': 'auto'});
					$('#cart_mode, #sales_mode, #theme_name').prop('disabled', false);
					$('.store-disabled-warning').closest('.alert').fadeOut();
				} else {
					// Disable theme configuration
					themeSection.css({'opacity': '0.5', 'pointer-events': 'none'});
					$('#cart_mode, #sales_mode, #theme_name').prop('disabled', true);
					
					// Show warning message only if it doesn't exist
					if ($('.store-disabled-warning').length === 0) {
						var warningMessage = `
							<div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3">
								<i class="fas fa-exclamation-triangle"></i>
								<span class="small store-disabled-warning"><?= __('admin.store_disabled') ?>: <?= __('admin.enable_store_configure_theme') ?></span>
							</div>
						`;
						$('.theme-config-section').prepend(warningMessage);
					} else {
						$('.store-disabled-warning').closest('.alert').fadeIn();
					}
				}
				
				// Update other checkboxes via AJAX
				$.ajax({
					url:'<?= base_url("admincontrol/update_store_status") ?>',
					type:'POST',
					dataType:'json',
					data:{'action':'update_store_status', status: isEnabled ? 1 : 0},
					success:function(json){
						// Show success message with custom styling
						var message = isEnabled ? 'Store enabled successfully!' : 'Store disabled successfully!';
						var bgClass = isEnabled ? 'bg-success' : 'bg-secondary';
						
						var successToast = `<div class="${bgClass} bg-opacity-10 border border-${isEnabled ? 'success' : 'secondary'} rounded p-3 mb-3 store-status-message">
							<i class="fas fa-check-circle me-2 text-${isEnabled ? 'success' : 'secondary'}"></i>
							<strong>Status Updated:</strong> ${message}
						</div>`;
						
						// Remove any existing status messages
						$('.store-status-message').remove();
						
						// Add new message at the top of store configuration
						$('.mb-4:first').after(successToast);
						
						// Auto-hide after 3 seconds
						setTimeout(function() {
							$('.store-status-message').fadeOut();
						}, 3000);
					},
				});
			});
		});

		// Store mode switching — AJAX + select update (visual handled by inline tss script)
		$(document).ready(function() {

		/* Build #theme_name <option> list from the correct mode's theme cards */
		function rebuildThemeOptions(forceMode) {
			var isCart = (forceMode !== undefined) ? (forceMode === 'cart') : ($('#cart_mode').prop('checked'));
			var cards  = isCart ? '#cart-theme-cards .tss-theme-card' : '#sales-theme-info .tss-theme-card';
			var opts   = '';
			$(cards).each(function(){
				var val  = $(this).data('theme-val');
				var name = $(this).find('.fw-semibold').first().text().trim();
				var sel  = $(this).hasClass('tss-theme-active') ? ' selected' : '';
				opts += `<option value="${val}"${sel}>${name}</option>`;
			});
			if (opts) $('#theme_name').html(opts);
		}

		$('.store_mode').off('change').on('change', function(){
			if (!$('.store_status').prop('checked')) return false;

			var selectedValue = $(this).val();
			var mode, theme;

			if (selectedValue == 'cart') {
				mode  = 'cart';
				/* default to first discovered cart theme */
				theme = $('#cart-theme-cards .tss-theme-card').first().data('theme-val') || '0';
				$(".cart_theme_settings").removeClass('d-none');
				$(".sales_theme_settings").addClass('d-none');
			} else {
				mode  = 'sales';
				/* default to first discovered sales theme */
				theme = $('#sales-theme-info .tss-theme-card').first().data('theme-val') || 'classified';
				$(".cart_theme_settings").addClass('d-none');
				$(".sales_theme_settings").removeClass('d-none');
			}

			$('#store_mode_hidden').val(mode);
			rebuildThemeOptions(mode);   /* rebuild options first (uses correct mode, not DOM visibility) */
			$('#theme_name').val(theme); /* then select the right option after options are refreshed */

				$.ajax({
					url: '<?= base_url("admincontrol/update_store_mode") ?>',
					type: 'POST',
					dataType: 'json',
					data: { action: 'update_store_mode', mode: mode, theme: theme },
					success: function(json) {
						var isCart   = (mode === 'cart');
						var colorKey = isCart ? 'primary' : 'success';
						var msg      = isCart ? 'Cart Mode activated!' : 'Sales Mode activated!';
						$('#theme-change-message').html(`
							<div class="alert alert-${colorKey} d-flex align-items-center gap-2 py-2">
								<i class="fas fa-check-circle"></i>
								<span><strong>Mode Updated:</strong> ${msg}</span>
							</div>`).show();
						setTimeout(function(){ $('#theme-change-message').fadeOut(); }, 4000);
					},
				});
			});
		});
	</script>
		</div>

		<!-- ── Create Theme Modal ──────────────────────────────────────────── -->
		<div class="modal fade" id="createThemeModal" tabindex="-1" aria-labelledby="createThemeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content border-0 shadow">
					<div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#1e3a5f,#0d6efd);border-radius:.5rem .5rem 0 0;">
						<div class="d-flex align-items-center gap-3">
							<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,.15);">
								<i class="bi bi-magic text-white fs-5"></i>
							</div>
							<div>
								<h5 class="modal-title fw-bold text-white mb-0" id="createThemeModalLabel">Generate New Store Theme</h5>
								<div class="text-white opacity-75" style="font-size:.78rem;">Auto-scaffold all view files + assets, ready to customise</div>
							</div>
						</div>
						<button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body p-4">

					<!-- What gets created -->
					<div class="rounded-3 p-3 mb-4 d-flex gap-3 align-items-start" style="background:#f0f9ff;border:1px solid #bae6fd;">
						<i class="bi bi-info-circle-fill text-primary mt-1 flex-shrink-0"></i>
						<div style="font-size:.82rem;">
							The generator will create <strong>16 PHP view files</strong> (all required pages + fragments + LMS template),
							a <strong>theme.json</strong> manifest, a starter <strong>CSS file</strong>, and a starter <strong>JS file</strong> — all following the Store API v1 contract.
							The theme will appear in the selector above immediately after creation.
						</div>
					</div>

					<div class="row g-4">
						<!-- Form column -->
						<div class="col-lg-7">
							<div id="ctm-form">
								<div class="row g-3">

									<!-- Display Name — master field that drives all auto-fills -->
									<div class="col-12">
										<div class="d-flex align-items-center justify-content-between mb-1">
											<label class="form-label fw-semibold mb-0">Theme Display Name <span class="text-danger">*</span></label>
											<button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2"
											        id="ctm-suggest-btn" title="Auto-suggest a random theme name"
											        style="font-size:.72rem;">
												<i class="bi bi-shuffle me-1"></i>Suggest
											</button>
										</div>
										<input type="text" id="ctm_name" class="form-control" placeholder="e.g. My Boutique Theme" maxlength="64"
										       onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('ctm-submit-btn').click();}">
										<div class="form-text"><i class="bi bi-arrow-down-short text-primary"></i> Typing here <strong>auto-fills</strong> the Theme ID and Description below.</div>
									</div>

									<!-- Theme ID — auto-filled, editable -->
									<div class="col-12">
										<label class="form-label fw-semibold d-flex align-items-center gap-2">
											Theme ID <span class="text-danger">*</span>
											<span class="badge bg-primary bg-opacity-10 text-primary fw-normal" style="font-size:.62rem;">
												<i class="bi bi-magic me-1"></i>auto-fills from name
											</span>
										</label>
										<div class="input-group">
											<span class="input-group-text text-muted" style="font-size:.8rem;">store/</span>
											<input type="text" id="ctm_id" class="form-control font-monospace" placeholder="my-boutique-theme" maxlength="32"
											       onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('ctm-submit-btn').click();}">
											<span class="input-group-text" id="ctm-id-status" style="font-size:.75rem;min-width:28px;"></span>
										</div>
										<div class="form-text">Lowercase letters, numbers, hyphens — the folder name. You can edit it manually.</div>
									</div>

									<!-- Description — auto-filled, editable -->
									<div class="col-12">
										<label class="form-label fw-semibold d-flex align-items-center gap-2">
											Short Description
											<span class="badge bg-primary bg-opacity-10 text-primary fw-normal" style="font-size:.62rem;">
												<i class="bi bi-magic me-1"></i>auto-fills from name
											</span>
										</label>
										<input type="text" id="ctm_desc" class="form-control" placeholder="Will auto-fill once you type the name above…" maxlength="160"
										       onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('ctm-submit-btn').click();}">
										<div class="form-text">Shown on the theme card. Edit any time to customise.</div>
									</div>

									<!-- Author — pre-filled from session -->
									<div class="col-12">
										<label class="form-label fw-semibold d-flex align-items-center gap-2">
											Author / Your Name
											<span class="badge bg-success bg-opacity-10 text-success fw-normal" style="font-size:.62rem;">
												<i class="bi bi-person-check me-1"></i>pre-filled
											</span>
										</label>
										<div class="input-group">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" id="ctm_author" class="form-control"
											       placeholder="e.g. John Doe" maxlength="64"
											       value="<?= htmlspecialchars($admin_full_name ?? '') ?>"
											       onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('ctm-submit-btn').click();}">
										</div>
										<div class="form-text">Taken from your admin account. Edit if needed.</div>
									</div>

								</div>
							</div>
						</div>

						<!-- Live preview column -->
						<div class="col-lg-5">
							<label class="form-label fw-semibold d-block mb-2">Live Preview</label>
							<div id="ctm-preview-card" class="tss-theme-card" style="pointer-events:none;opacity:.95;">
								<div id="ctm-preview-gradient" class="tss-theme-preview"
								     style="background:linear-gradient(135deg,#1e293b 0%,#3b82f6 100%);">
									<div class="text-center text-white px-3">
										<div id="ctm-preview-emoji" style="font-size:2rem;margin-bottom:.3rem;">🛍</div>
										<div id="ctm-preview-label" style="font-size:.72rem;opacity:.9;">Your Theme</div>
									</div>
								</div>
								<div class="p-3">
									<div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
										<span id="ctm-preview-name" class="fw-semibold" style="font-size:.88rem;">Theme Name</span>
										<span class="badge bg-success bg-opacity-15 text-success fw-normal" style="font-size:.62rem;"><i class="bi bi-person-check me-1"></i>Custom</span>
										<span class="text-muted ms-auto" style="font-size:.65rem;">v1.0.0</span>
									</div>
									<div id="ctm-preview-desc" class="text-muted mb-2" style="font-size:.75rem;line-height:1.4;">Your theme description will appear here.</div>
									<div class="d-flex flex-wrap gap-1">
										<span class="badge bg-light border text-muted fw-normal" style="font-size:.62rem;">Bootstrap 5</span>
										<span class="badge bg-light border text-muted fw-normal" style="font-size:.62rem;">Custom</span>
										<span class="badge bg-light border text-muted fw-normal" style="font-size:.62rem;">API v1</span>
									</div>
								</div>
							</div>
							<!-- Gradient palette -->
							<div class="mt-2">
								<div class="form-text mb-1">Pick a preview colour:</div>
								<div class="d-flex flex-wrap gap-1" id="ctm-gradient-palette">
									<button type="button" class="ctm-grad-btn rounded" data-grad="linear-gradient(135deg,#1e293b 0%,#3b82f6 100%)" style="width:28px;height:28px;background:linear-gradient(135deg,#1e293b,#3b82f6);border:2px solid #3b82f6;" title="Blue"></button>
									<button type="button" class="ctm-grad-btn rounded" data-grad="linear-gradient(135deg,#064e3b 0%,#10b981 100%)" style="width:28px;height:28px;background:linear-gradient(135deg,#064e3b,#10b981);border:2px solid transparent;" title="Green"></button>
									<button type="button" class="ctm-grad-btn rounded" data-grad="linear-gradient(135deg,#4c1d95 0%,#8b5cf6 100%)" style="width:28px;height:28px;background:linear-gradient(135deg,#4c1d95,#8b5cf6);border:2px solid transparent;" title="Purple"></button>
									<button type="button" class="ctm-grad-btn rounded" data-grad="linear-gradient(135deg,#7f1d1d 0%,#ef4444 100%)" style="width:28px;height:28px;background:linear-gradient(135deg,#7f1d1d,#ef4444);border:2px solid transparent;" title="Red"></button>
									<button type="button" class="ctm-grad-btn rounded" data-grad="linear-gradient(135deg,#78350f 0%,#f59e0b 100%)" style="width:28px;height:28px;background:linear-gradient(135deg,#78350f,#f59e0b);border:2px solid transparent;" title="Amber"></button>
									<button type="button" class="ctm-grad-btn rounded" data-grad="linear-gradient(135deg,#0c4a6e 0%,#06b6d4 100%)" style="width:28px;height:28px;background:linear-gradient(135deg,#0c4a6e,#06b6d4);border:2px solid transparent;" title="Cyan"></button>
									<button type="button" class="ctm-grad-btn rounded" data-grad="linear-gradient(135deg,#1a1a2e 0%,#e94560 100%)" style="width:28px;height:28px;background:linear-gradient(135deg,#1a1a2e,#e94560);border:2px solid transparent;" title="Dark Rose"></button>
									<button type="button" class="ctm-grad-btn rounded" data-grad="linear-gradient(135deg,#0f2027 0%,#203a43 50%,#2c5364 100%)" style="width:28px;height:28px;background:linear-gradient(135deg,#0f2027,#2c5364);border:2px solid transparent;" title="Dark Teal"></button>
								</div>
							</div>
						</div>
					</div>

					<!-- Result panel (hidden until generation) -->
					<div id="ctm-result" class="d-none mt-3"></div>

				</div>
					<div class="modal-footer border-0 pt-0">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-success" id="ctm-submit-btn">
							<i class="bi bi-magic me-1"></i>Generate Theme
						</button>
					</div>
				</div>
			</div>
		</div>

	<script>
	var _ctmGradient = 'linear-gradient(135deg,#1e293b 0%,#3b82f6 100%)';

	/* ── Block Enter from submitting the parent settings form ──────────── */
	document.getElementById('createThemeModal').addEventListener('keydown', function(e){
		if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
			e.stopPropagation();
			if (e.target.closest('#ctm-form')) {
				e.preventDefault();
				document.getElementById('ctm-submit-btn').click();
			}
		}
	}, true);

	/* ── Live preview update helper ───────────────────────────────────── */
	function ctmUpdatePreview() {
		var name = document.getElementById('ctm_name').value.trim() || 'Theme Name';
		var desc = document.getElementById('ctm_desc').value.trim() || 'Your theme description will appear here.';
		document.getElementById('ctm-preview-name').textContent  = name;
		document.getElementById('ctm-preview-label').textContent = name;
		document.getElementById('ctm-preview-desc').textContent  = desc;
		document.getElementById('ctm-preview-gradient').style.background = _ctmGradient;
	}

	/* ── Theme name suggestions for the Suggest button ──────────────── */
	var _ctmSuggestions = [
		'My Boutique Theme','Modern Shop','Clean Store','Dark Commerce',
		'Elegant Market','Fresh Retail','Bold Storefront','Minimal Cart',
		'Premium Shop','Urban Style Store','Cozy Shop','Sleek Commerce'
	];
	var _ctmSuggestIdx = 0;

	document.getElementById('ctm-suggest-btn').addEventListener('click', function(){
		var name = _ctmSuggestions[_ctmSuggestIdx % _ctmSuggestions.length];
		_ctmSuggestIdx++;
		document.getElementById('ctm_name').value = name;
		/* Trigger all auto-fills */
		document.getElementById('ctm_name').dispatchEvent(new Event('input'));
	});

	/* ── Auto-generate Theme ID + description from name ──────────────── */
	document.getElementById('ctm_name').addEventListener('input', function(){
		var raw  = this.value;
		var slug = raw.toLowerCase()
			.replace(/[^a-z0-9\s-]/g,'')
			.trim().replace(/\s+/g,'-').replace(/-+/g,'-').substring(0,32);

		var idEl = document.getElementById('ctm_id');
		if (!idEl.dataset.manuallyEdited) {
			idEl.value = slug;
		}
		ctmValidateId();

		/* Auto-fill description only while it hasn't been manually edited */
		var descEl = document.getElementById('ctm_desc');
		if (!descEl.dataset.manuallyEdited) {
			var nm = raw.trim();
			descEl.value = nm ? 'A custom Bootstrap 5 store theme — ' + nm + '.' : '';
		}
		ctmUpdatePreview();
	});

	/* ── Validate Theme ID and show tick / cross ──────────────────────── */
	function ctmValidateId() {
		var val    = document.getElementById('ctm_id').value.trim();
		var status = document.getElementById('ctm-id-status');
		var reserved = ['default','0','classified','starter2026','lms','common','shared'];
		if (!val) {
			status.innerHTML = '';
			status.className = 'input-group-text';
		} else if (!/^[a-z0-9][a-z0-9_-]{1,30}$/.test(val) || reserved.indexOf(val) !== -1) {
			status.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
			status.className = 'input-group-text bg-danger bg-opacity-10';
			status.title     = reserved.indexOf(val) !== -1 ? '"' + val + '" is reserved.' : 'Invalid format.';
		} else {
			status.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
			status.className = 'input-group-text bg-success bg-opacity-10';
			status.title     = 'Looks good!';
		}
	}

	/* Mark ID / description as manually edited if user types in them */
	document.getElementById('ctm_id').addEventListener('input', function(){
		this.dataset.manuallyEdited = '1';
		ctmValidateId();
	});

	/* ── Mark description as manually edited if user types in it ─────── */
	document.getElementById('ctm_desc').addEventListener('input', function(){
		this.dataset.manuallyEdited = '1';
		ctmUpdatePreview();
	});

	/* ── Gradient palette ─────────────────────────────────────────────── */
	document.querySelectorAll('.ctm-grad-btn').forEach(function(btn){
		btn.addEventListener('click', function(){
			_ctmGradient = this.getAttribute('data-grad');
			document.querySelectorAll('.ctm-grad-btn').forEach(function(b){
				b.style.borderColor = 'transparent';
			});
			this.style.borderColor = '#0d6efd';
			ctmUpdatePreview();
		});
	});

	/* ── Reset form when modal opens ─────────────────────────────────── */
	document.getElementById('createThemeModal').addEventListener('show.bs.modal', function(){
		var nameEl   = document.getElementById('ctm_name');
		var idEl     = document.getElementById('ctm_id');
		var descEl   = document.getElementById('ctm_desc');
		var statusEl = document.getElementById('ctm-id-status');
		nameEl.value = '';
		idEl.value   = '';
		descEl.value = '';
		delete idEl.dataset.manuallyEdited;
		delete descEl.dataset.manuallyEdited;
		statusEl.innerHTML = '';
		statusEl.className = 'input-group-text';
		document.getElementById('ctm-result').className = 'd-none mt-3';
		document.getElementById('ctm-result').innerHTML = '';
		var submitBtn = document.getElementById('ctm-submit-btn');
		submitBtn.disabled = false;
		submitBtn.innerHTML = '<i class="bi bi-magic me-1"></i>Generate Theme';
		/* Reset gradient selection */
		_ctmGradient = 'linear-gradient(135deg,#1e293b 0%,#3b82f6 100%)';
		document.querySelectorAll('.ctm-grad-btn').forEach(function(b,i){
			b.style.borderColor = i === 0 ? '#0d6efd' : 'transparent';
		});
		_ctmSuggestIdx = 0;
		ctmUpdatePreview();
		/* Focus name field after modal finishes opening */
		setTimeout(function(){ nameEl.focus(); }, 400);
	});

		/* ── Submit ─────────────────────────────────────────────────────── */
		document.getElementById('ctm-submit-btn').addEventListener('click', function(){
			var btn    = this;
			var name   = document.getElementById('ctm_name').value.trim();
			var id     = document.getElementById('ctm_id').value.trim();
			var desc   = document.getElementById('ctm_desc').value.trim();
			var author = document.getElementById('ctm_author').value.trim() || 'Custom';
			var result = document.getElementById('ctm-result');

			if (!name) { document.getElementById('ctm_name').focus(); return; }
			if (!id)   { document.getElementById('ctm_id').focus();   return; }

			btn.disabled = true;
			btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Generating…';
			result.className = 'd-none mt-3';

			$.ajax({
				url:      '<?= base_url('admincontrol/create_store_theme') ?>',
				method:   'POST',
				dataType: 'json',
				data: { theme_name: name, theme_id: id, theme_description: desc, author_name: author, theme_gradient: _ctmGradient },
				success: function(res) {
					btn.disabled = false;
					btn.innerHTML = '<i class="bi bi-magic me-1"></i>Generate Theme';
					if (res.success) {
						result.className = 'mt-3';
						result.innerHTML =
							'<div class="alert alert-success border-0 rounded-3">' +
							'<h6 class="fw-bold mb-2"><i class="bi bi-check-circle-fill me-1"></i>Theme created! (' + res.files + ' files)</h6>' +
							'<p class="mb-2 small">Your theme <strong>' + $('<span>').text(res.theme_name).html() + '</strong> is ready. ' +
							'Close this dialog, find the new card in the selector, click it and hit <strong>Save Settings</strong> to go live.</p>' +
							'<div class="d-flex gap-2">' +
'<button type="button" class="btn btn-sm btn-success" onclick="$(\'#createThemeModal\').modal(\'hide\'); window.location.href = window.location.pathname;">' +
					'<i class="bi bi-arrow-clockwise me-1"></i>Reload to see new theme</button>' +
							'<a href="<?= base_url('admincontrol/store_theme_api_doc') ?>" target="_blank" class="btn btn-sm btn-outline-primary">' +
							'<i class="bi bi-book me-1"></i>API Docs</a>' +
							'</div></div>';
					} else {
						result.className = 'mt-3';
						result.innerHTML = '<div class="alert alert-danger border-0 rounded-3"><i class="bi bi-x-circle me-1"></i>' + $('<span>').text(res.message).html() + '</div>';
					}
				},
			error: function() {
				btn.disabled = false;
				btn.innerHTML = '<i class="bi bi-magic me-1"></i>Generate Theme';
				result.className = 'mt-3';
				result.innerHTML = '<div class="alert alert-danger border-0 rounded-3">Server error. Please try again.</div>';
			}
		});
	});
	</script>

	<!-- ── Delete Theme Modal ──────────────────────────────────────────── -->
	<div class="modal fade" id="deleteThemeModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header border-0" style="background:linear-gradient(135deg,#7f1d1d,#dc2626);">
					<div class="d-flex align-items-center gap-2">
						<i class="bi bi-trash3-fill text-white fs-5"></i>
						<h5 class="modal-title fw-bold text-white mb-0">Delete Theme</h5>
					</div>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body p-4">
					<div class="text-center mb-3">
						<div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 mb-3" style="width:56px;height:56px;">
							<i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
						</div>
						<p class="fw-semibold mb-1">You are about to permanently delete:</p>
						<p class="fs-5 fw-bold text-danger mb-3" id="dtm-theme-name">—</p>
						<div class="rounded-3 p-3 text-start small text-muted mb-3" style="background:#fef2f2;border:1px solid #fecaca;">
							<div class="mb-1"><i class="bi bi-folder-x text-danger me-1"></i> <code id="dtm-views-path">application/views/store/…</code></div>
							<div><i class="bi bi-folder-x text-danger me-1"></i> <code id="dtm-assets-path">assets/store/…</code></div>
						</div>
						<p class="text-danger fw-semibold small mb-0"><i class="bi bi-exclamation-circle me-1"></i>This action cannot be undone.</p>
					</div>
					<div id="dtm-result" class="mt-2"></div>
				</div>
				<div class="modal-footer border-0 pt-0">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-danger fw-semibold" id="dtm-confirm-btn" onclick="tsExecuteDeleteTheme()">
						<i class="bi bi-trash3 me-1"></i>Yes, Delete Permanently
					</button>
				</div>
			</div>
		</div>
	</div>

	<script>
	var _dtmThemeId   = '';
	var _dtmThemeName = '';

	function tsConfirmDeleteTheme(btn) {
		_dtmThemeId   = btn.getAttribute('data-theme-id');
		_dtmThemeName = btn.getAttribute('data-theme-name');
		document.getElementById('dtm-theme-name').textContent  = '"' + _dtmThemeName + '"';
		document.getElementById('dtm-views-path').textContent  = 'application/views/store/' + _dtmThemeId + '/';
		document.getElementById('dtm-assets-path').textContent = 'assets/store/' + _dtmThemeId + '/';
		document.getElementById('dtm-result').innerHTML = '';
		var confirmBtn = document.getElementById('dtm-confirm-btn');
		confirmBtn.disabled = false;
		confirmBtn.innerHTML = '<i class="bi bi-trash3 me-1"></i>Yes, Delete Permanently';
		var modal = new bootstrap.Modal(document.getElementById('deleteThemeModal'));
		modal.show();
	}

	function tsExecuteDeleteTheme() {
		if (!_dtmThemeId) return;
		var btn    = document.getElementById('dtm-confirm-btn');
		var result = document.getElementById('dtm-result');
		btn.disabled = true;
		btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Deleting…';
		result.innerHTML = '';
		$.ajax({
			url:  '<?= base_url('admincontrol/delete_store_theme') ?>',
			type: 'POST',
			data: { theme_id: _dtmThemeId },
			dataType: 'json',
			success: function(res) {
				if (res.success) {
					result.innerHTML = '<div class="alert alert-success border-0 py-2 small">' +
						'<i class="bi bi-check-circle me-1"></i>' + res.message + '</div>';
					btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Deleted';
					setTimeout(function() {
				bootstrap.Modal.getInstance(document.getElementById('deleteThemeModal')).hide();
					window.location.href = window.location.pathname;
					}, 1200);
				} else {
					btn.disabled = false;
					btn.innerHTML = '<i class="bi bi-trash3 me-1"></i>Yes, Delete Permanently';
					result.innerHTML = '<div class="alert alert-danger border-0 py-2 small">' +
						'<i class="bi bi-x-circle me-1"></i>' +
						$('<span>').text(res.message).html() + '</div>';
				}
			},
			error: function() {
				btn.disabled = false;
				btn.innerHTML = '<i class="bi bi-trash3 me-1"></i>Yes, Delete Permanently';
				result.innerHTML = '<div class="alert alert-danger border-0 py-2 small">Server error. Please try again.</div>';
			}
		});
	}
	</script>

	<!-- Contact & Analytics Sub-tab -->
		<div class="tab-pane fade" id="contact_analytics" role="tabpanel">

<!-- Analytics & Custom Scripts Section -->
<div class="mb-4">
	<div class="bg-white rounded shadow-sm border overflow-hidden">
		<div class="bg-primary bg-opacity-10 border-bottom p-3">
			<h6 class="text-dark mb-0 fw-medium">
				<i class="fas fa-chart-line me-2 text-primary"></i><?= __('admin.analytics_custom_scripts') ?>
			</h6>
		</div>
		<div class="p-4">
			
			<!-- Google Analytics -->
			<div class="row g-4 mb-4">
				<div class="col-12">
					<h6 class="fw-medium text-dark mb-3">
						<i class="fab fa-google me-2 text-warning"></i><?= __('admin.google_analytics_for_store_page') ?>
					</h6>
					<div class="form-floating">
						<textarea name="store[google_analytics]" class="form-control" id="google-analytics" style="min-height: 120px;" placeholder="<?= __('admin.google_analytics_placeholder') ?>"><?php echo $store['google_analytics']; ?></textarea>
						<label for="google-analytics"><?= __('admin.google_analytics_code') ?></label>
					</div>
					<div class="bg-info bg-opacity-10 rounded p-3 mt-2">
						<small class="text-dark">
							<i class="fas fa-info-circle text-info me-1"></i>
							<strong><?= __('admin.example') ?>:</strong> gtag('config', 'GA_MEASUREMENT_ID'); - <?= __('admin.google_analytics_help') ?>
						</small>
					</div>
				</div>
			</div>

			<hr class="my-4">

			<!-- Custom Scripts Instructions -->
			<div class="row g-4 mb-4">
				<div class="col-12">
					<div class="bg-light rounded p-3">
						<h6 class="fw-medium text-dark mb-3">
							<i class="fas fa-graduation-cap me-2 text-primary"></i><?= __('admin.how_to_use_custom_scripts') ?>
						</h6>
						<div class="row g-3">
							<div class="col-md-4">
								<div class="border rounded p-3 h-100">
									<h6 class="text-success mb-2">
										<i class="fas fa-palette me-1"></i><?= __('admin.css_styles') ?>
									</h6>
									<small class="text-muted"><?= __('admin.css_example') ?>:</small>
									<code class="d-block bg-white p-2 rounded mt-1 small">
&lt;style&gt;<br>
.home-trend-top {<br>
&nbsp;&nbsp;background: red;<br>
&nbsp;&nbsp;color: white;<br>
}<br>
&lt;/style&gt;
									</code>
								</div>
							</div>
							<div class="col-md-4">
								<div class="border rounded p-3 h-100">
									<h6 class="text-warning mb-2">
										<i class="fab fa-js me-1"></i><?= __('admin.javascript_code') ?>
									</h6>
									<small class="text-muted"><?= __('admin.javascript_example') ?>:</small>
									<code class="d-block bg-white p-2 rounded mt-1 small">
&lt;script&gt;<br>
console.log('Hello');<br>
&lt;/script&gt;
									</code>
								</div>
							</div>
							<div class="col-md-4">
								<div class="border rounded p-3 h-100">
									<h6 class="text-info mb-2">
										<i class="fas fa-code me-1"></i><?= __('admin.html_content') ?>
									</h6>
									<small class="text-muted"><?= __('admin.html_example') ?>:</small>
									<code class="d-block bg-white p-2 rounded mt-1 small">
&lt;div class="banner"&gt;<br>
&nbsp;&nbsp;Special Offer!<br>
&lt;/div&gt;
									</code>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Custom Scripts -->
			<div class="row g-4">
				<div class="col-12">
					<h6 class="fw-medium text-dark mb-3">
						<i class="fas fa-code me-2 text-success"></i><?= __('admin.custom_scripts') ?>
					</h6>
					
					<div class="per-task-parent">
						<?php
						$per_tasks = isset($store['per_task']) && !empty($store['per_task']) ? json_decode($store['per_task'],true) : [];
						$tcount = 1;
						if(!empty($per_tasks)) {
							foreach($per_tasks as $per_task){
								?>
								<div class="row g-3 mb-3 task-item">
									<div class="col-md-11">
										<div class="form-floating">
											<textarea name="store[per_task][]" class="form-control" style="min-height: 100px;" placeholder="<?= __('admin.script_placeholder') ?>"><?= $per_task?></textarea>
											<label><?= __('admin.script') ?> <?=$tcount?></label>
										</div>
										<div class="mt-1">
											<small class="text-muted">
												<i class="fas fa-lightbulb text-warning me-1"></i>
												<?= __('admin.script_tip') ?>
											</small>
										</div>
									</div>
									<div class="col-md-1 d-flex align-items-center">
										<button type="button" class="btn btn-outline-danger btn-sm remove-per-task-btn" title="<?= __('admin.remove_script') ?>">
											<i class="fas fa-trash"></i>
										</button>
									</div>
								</div>
								<?php 
								$tcount++;
							}
						}
						?>
					</div>
					
					<!-- Empty State -->
					<?php if(empty($per_tasks)): ?>
					<div class="text-center py-4 mb-3 empty-state">
						<i class="fas fa-code text-muted display-4 mb-2"></i>
						<h6 class="text-muted"><?= __('admin.no_scripts_added') ?></h6>
						<small class="text-muted"><?= __('admin.click_add_script_below') ?></small>
					</div>
					<?php endif; ?>
					
					<div class="col-12">
						<button id="add-more-per-task-btn" type="button" class="btn btn-outline-primary">
							<i class="fas fa-plus me-1"></i> <?= __('admin.add_script') ?>
						</button>
						<span class="script-count d-none" data-value="<?=$tcount?>"></span>
					</div>
					
					<!-- Warning Notice -->
					<div class="bg-warning bg-opacity-10 rounded p-3 mt-3">
						<div class="d-flex">
							<i class="fas fa-exclamation-triangle text-warning me-2 mt-1 flex-shrink-0"></i>
							<div class="small text-dark">
								<strong><?= __('admin.important') ?>:</strong> <?= __('admin.script_warning') ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	function toggleEmptyState() {
		if ($('.task-item').length === 0) {
			$('.empty-state').show();
		} else {
			$('.empty-state').hide();
		}
	}

	$(document).on('click', '#add-more-per-task-btn', function(){
		let countt = parseInt($('.script-count').attr('data-value'));
		$('.per-task-parent').append(`
			<div class="row g-3 mb-3 task-item">
				<div class="col-md-11">
					<div class="form-floating">
						<textarea name="store[per_task][]" class="form-control" style="min-height: 100px;" placeholder="<?= __('admin.script_placeholder') ?>"></textarea>
						<label><?= __('admin.script') ?> `+(countt)+`</label>
					</div>
					<div class="mt-1">
						<small class="text-muted">
							<i class="fas fa-lightbulb text-warning me-1"></i>
							<?= __('admin.script_tip') ?>
						</small>
					</div>
				</div>
				<div class="col-md-1 d-flex align-items-center">
					<button type="button" class="btn btn-outline-danger btn-sm remove-per-task-btn" title="<?= __('admin.remove_script') ?>">
						<i class="fas fa-trash"></i>
					</button>
				</div>
			</div>`);
		$('.script-count').attr('data-value',(countt+1));
		toggleEmptyState();
	});

	$(document).on('click', '.remove-per-task-btn', function(){
		$(this).closest('.task-item').remove();
		toggleEmptyState();
	});

	// Initialize empty state
	toggleEmptyState();
});
</script>


	<!-- Store Images & Branding Section -->
	<div class="mb-4">
		<div class="bg-white rounded shadow-sm border overflow-hidden">
			<div class="bg-success bg-opacity-10 border-bottom p-3">
				<h6 class="text-dark mb-0 fw-medium">
					<i class="fas fa-images me-2 text-success"></i><?= __('admin.store_images_branding') ?>
				</h6>
			</div>
			<div class="p-4">
				
				<!-- Store Logo -->
				<div class="row g-4 mb-5">
					<div class="col-12">
						<h6 class="fw-medium text-dark mb-3">
							<i class="fas fa-image me-2 text-primary"></i><?= __('admin.store_logo') ?>
						</h6>
					</div>
					<div class="col-md-3">
						<div class="bg-light rounded p-3 text-center border">
							<input type="hidden" name="store[logo]" value="<?= $store['logo'] ?>">
							<?php $img = $store['logo'] ? base_url('assets/images/site/'. $store['logo']) : base_url('assets/images/no_image_available.png'); ?>
							<img id="store-logo-image" style="max-width: 120px; max-height: 80px; object-fit: contain;" src="<?= $img ?>" class='img-fluid mb-2'>
							<?php if($store['logo']) { ?>
							<div>
								<button type="button" class="btn btn-sm btn-outline-danger btn-delete-image" data-img_input="store[logo]" data-img_ele="store-logo-image" data-img_placeholder="<?= base_url('assets/images/no_image_available.png');?>">
									<i class="bi bi-trash me-1"></i><?= __('admin.remove') ?>
								</button>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-floating mb-3">
							<input type="file" name="store_logo" class="form-control" id="logoUpload" accept="image/*">
							<label for="logoUpload"><?= __('admin.upload_store_logo') ?></label>
						</div>
						<small class="text-muted">
							<i class="fas fa-info-circle me-1"></i>
							<?= __('admin.recommended_size_is') ?> 100x36 px
						</small>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
							<select name="store[store_custom_logo_size]" class="form-select" id="logoCustomSize">
								<option value="0"><?= __('admin.disable') ?></option>
								<option <?php echo ($store['store_custom_logo_size'] == 1) ? "selected" :""; ?> value="1"><?= __('admin.store_logo_custom_size') ?></option>
							</select>
							<label for="logoCustomSize"><?= __('admin.site_setting_logo_custom_size')?></label>
						</div>
					</div>
					
					<!-- Custom Logo Size Inputs -->
					<div class="col-12 front_logo_cust_size_inp" <?php echo ($store['store_custom_logo_size'] != 1) ? 'style="display:none;"' :""; ?>>
						<div class="row g-3">
							<div class="col-md-6">
								<div class="form-floating">
									<input name="store[store_logo_custom_width]" value="<?php echo $store['store_logo_custom_width']; ?>" class="form-control" type="number" id="logoWidth" placeholder="<?= __('admin.width') ?>">
									<label for="logoWidth"><?= __('admin.site_setting_logo_width') ?> (px)</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-floating">
									<input name="store[store_logo_custom_height]" value="<?php echo $store['store_logo_custom_height']; ?>" class="form-control" type="number" id="logoHeight" placeholder="<?= __('admin.height') ?>">
									<label for="logoHeight"><?= __('admin.site_setting_logo_height') ?> (px)</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<hr class="my-4">

				<!-- Cart Image & Favicon Row -->
				<div class="row g-4">
					<!-- Store Cart Image -->
					<div class="col-md-6">
						<h6 class="fw-medium text-dark mb-3">
							<i class="fas fa-shopping-cart me-2 text-warning"></i><?= __('admin.store_cart_image') ?>
						</h6>
						<div class="row g-3 align-items-center">
							<div class="col-4">
								<div class="bg-light rounded p-3 text-center border">
									<input type="hidden" name="store[cartimage]" value="<?= $store['cartimage'] ?>">
									<?php $img = $store['cartimage'] ? base_url('assets/images/site/'. $store['cartimage']) : base_url('assets/store/default/img/cart-icon-empty.png'); ?> 
									<img id="store-cart-image" style="width: 50px; height: 50px; object-fit: contain;" src="<?= $img ?>" class='img-fluid mb-2'>
									<?php if($store['cartimage']) { ?>
									<div>
										<button type="button" class="btn btn-sm btn-outline-danger btn-delete-image" data-img_input="store[cartimage]" data-img_ele="store-cart-image" data-img_placeholder="<?= base_url('assets/store/default/img/cart-icon-empty.png');?>">
											<i class="bi bi-trash"></i>
										</button>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="col-8">
								<div class="form-floating mb-2">
									<input type="file" name="store_cartimage" class="form-control" id="cartUpload" accept="image/*">
									<label for="cartUpload"><?= __('admin.upload_cart_icon') ?></label>
								</div>
								<small class="text-muted">
									<i class="fas fa-info-circle me-1"></i>
									<?= __('admin.recommended_size_is') ?> 50x50 px
								</small>
							</div>
						</div>
					</div>

					<!-- Store Favicon -->
					<div class="col-md-6">
						<h6 class="fw-medium text-dark mb-3">
							<i class="fas fa-star me-2 text-info"></i><?= __('admin.store_favicon_icon') ?>
						</h6>
						<div class="row g-3 align-items-center">
							<div class="col-4">
								<div class="bg-light rounded p-3 text-center border">
									<input type="hidden" name="store[favicon]" value="<?= $store['favicon'] ?>">
									<?php $img = $store['favicon'] ? base_url('assets/images/site/'. $store['favicon']) : base_url('assets/images/no_image_available.png'); ?>
									<img id="store-favicon-image" style="width: 50px; height: 50px; object-fit: contain;" src="<?= $img ?>" class='img-fluid mb-2'>
									<?php if($store['favicon']) { ?>
									<div>
										<button type="button" class="btn btn-sm btn-outline-danger btn-delete-image" data-img_input="store[favicon]" data-img_ele="store-favicon-image" data-img_placeholder="<?= base_url('assets/images/no_image_available.png');?>">
											<i class="bi bi-trash"></i>
										</button>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="col-8">
								<div class="form-floating mb-2">
									<input type="file" name="store_favicon" class="form-control" id="faviconUpload" accept="image/*">
									<label for="faviconUpload"><?= __('admin.upload_favicon') ?></label>
								</div>
								<small class="text-muted">
									<i class="fas fa-info-circle me-1"></i>
									<?= __('admin.favicon_format_recommendation') ?>
								</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Contact Information Section -->
	<div class="mb-4">
		<div class="bg-white rounded shadow-sm border overflow-hidden">
			<div class="bg-info bg-opacity-10 border-bottom p-3">
				<h6 class="text-dark mb-0 fw-medium">
					<i class="fas fa-address-book me-2 text-info"></i><?= __('admin.contact_information') ?>
				</h6>
			</div>
			<div class="p-4">
				<div class="row g-4">
					<div class="col-12">
						<div class="form-floating">
							<input id="store-footer" name="store[footer]" value="<?= $store['footer'] ?>" class="form-control" type="text" placeholder="<?= __('admin.footer_text') ?>">
							<label for="store-footer">
								<i class="fas fa-align-center me-2 text-muted"></i><?= __('admin.footer_text') ?>
							</label>
						</div>
					</div>

					<div class="col-12">
						<div class="form-floating">
							<textarea id="contact-us-map" name="store[contact_us_map]" class="form-control" style="height: 120px" placeholder="<?= __('admin.contact_us_map') ?>"><?= $store['contact_us_map'] ?></textarea>
							<label for="contact-us-map">
								<i class="fas fa-map-marker-alt me-2 text-muted"></i><?= __('admin.contact_us_map') ?>
							</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-floating">
							<textarea id="store-address" name="store[address]" class="form-control" style="height: 100px" placeholder="<?= __('admin.store_address') ?>"><?= $store['address'] ?></textarea>
							<label for="store-address">
								<i class="fas fa-home me-2 text-muted"></i><?= __('admin.store_address') ?>
							</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="row g-3">
							<div class="col-12">
								<div class="form-floating">
									<input id="store-email" name="store[email]" value="<?= $store['email'] ?>" class="form-control" type="email" placeholder="<?= __('admin.store_email') ?>"> 
									<label for="store-email">
										<i class="fas fa-envelope me-2 text-muted"></i><?= __('admin.store_email') ?>
									</label>
								</div>
							</div>
							<div class="col-12">
								<div class="form-floating">
									<input id="contact-number" name="store[contact_number]" value="<?= $store['contact_number'] ?>" class="form-control" type="text" placeholder="<?= __('admin.store_mobile_phone_number') ?>"> 
									<label for="contact-number">
										<i class="fas fa-phone me-2 text-muted"></i><?= __('admin.store_mobile_phone_number') ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div><!-- /contact_analytics tab-pane -->

	<!-- V14: Smart Commerce Sub-tab -->
	<div class="tab-pane fade" id="smart_commerce_settings" role="tabpanel">
		<div class="mb-4">
			<div class="bg-white rounded shadow-sm border overflow-hidden">
				<div class="p-3 bg-light border-bottom d-flex align-items-center">
					<h6 class="text-dark mb-0 fw-medium">
						<i class="fas fa-boxes-stacked me-2 text-primary"></i><?= __('admin.inventory_management') ?>
					</h6>
				</div>
				<div class="p-4">
					<div class="row g-4">
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.auto_stock_management') ?></label>
							<select name="store[auto_stock_management]" class="form-select">
								<option value="0" <?= (isset($store['auto_stock_management']) && $store['auto_stock_management'] == 0) ? 'selected' : '' ?>><?= __('admin.disabled') ?></option>
								<option value="1" <?= (isset($store['auto_stock_management']) && $store['auto_stock_management'] == 1) ? 'selected' : '' ?>><?= __('admin.enabled') ?></option>
							</select>
							<small class="text-muted mt-1 d-block"><?= __('admin.auto_stock_management_desc') ?></small>
						</div>
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.low_stock_threshold') ?></label>
							<input type="number" name="store[low_stock_threshold]" class="form-control" value="<?= $store['low_stock_threshold'] ?? 5 ?>" min="0">
							<small class="text-muted mt-1 d-block"><?= __('admin.low_stock_threshold_desc') ?></small>
						</div>
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.out_of_stock_visibility') ?></label>
							<select name="store[out_of_stock_visibility]" class="form-select">
								<option value="show" <?= (isset($store['out_of_stock_visibility']) && $store['out_of_stock_visibility'] == 'show') ? 'selected' : '' ?>><?= __('admin.show_with_badge') ?></option>
								<option value="hide" <?= (isset($store['out_of_stock_visibility']) && $store['out_of_stock_visibility'] == 'hide') ? 'selected' : '' ?>><?= __('admin.hide_product') ?></option>
							</select>
							<small class="text-muted mt-1 d-block"><?= __('admin.out_of_stock_visibility_desc') ?></small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="mb-4">
			<div class="bg-white rounded shadow-sm border overflow-hidden">
				<div class="p-3 bg-light border-bottom d-flex align-items-center">
					<h6 class="text-dark mb-0 fw-medium">
						<i class="fas fa-cart-arrow-down me-2 text-warning"></i><?= __('admin.abandoned_cart_recovery') ?>
					</h6>
				</div>
				<div class="p-4">
					<div class="row g-4">
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.abandoned_cart_enabled') ?></label>
							<select name="store[abandoned_cart_enabled]" class="form-select">
								<option value="0" <?= (isset($store['abandoned_cart_enabled']) && $store['abandoned_cart_enabled'] == 0) ? 'selected' : '' ?>><?= __('admin.disabled') ?></option>
								<option value="1" <?= (isset($store['abandoned_cart_enabled']) && $store['abandoned_cart_enabled'] == 1) ? 'selected' : '' ?>><?= __('admin.enabled') ?></option>
							</select>
							<small class="text-muted mt-1 d-block"><?= __('admin.abandoned_cart_enabled_desc') ?></small>
						</div>
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.abandoned_cart_delay_hours') ?></label>
							<input type="number" name="store[abandoned_cart_delay_hours]" class="form-control" value="<?= $store['abandoned_cart_delay_hours'] ?? 1 ?>" min="1" max="72">
							<small class="text-muted mt-1 d-block"><?= __('admin.abandoned_cart_delay_desc') ?></small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="mb-4">
			<div class="bg-white rounded shadow-sm border overflow-hidden">
				<div class="p-3 bg-light border-bottom d-flex align-items-center">
					<h6 class="text-dark mb-0 fw-medium">
						<i class="fas fa-cash-register me-2 text-success"></i><?= __('admin.checkout_settings') ?>
					</h6>
				</div>
				<div class="p-4">
					<div class="row g-4">
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.checkout_mode') ?></label>
							<select name="store[checkout_mode]" class="form-select">
								<option value="multi-step" <?= (isset($store['checkout_mode']) && $store['checkout_mode'] == 'multi-step') ? 'selected' : '' ?>><?= __('admin.multi_step_checkout') ?></option>
								<option value="one-page" <?= (isset($store['checkout_mode']) && $store['checkout_mode'] == 'one-page') ? 'selected' : '' ?>><?= __('admin.one_page_checkout') ?></option>
							</select>
							<small class="text-muted mt-1 d-block"><?= __('admin.checkout_mode_desc') ?></small>
						</div>
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.auto_complete_digital') ?></label>
							<select name="store[auto_complete_digital]" class="form-select">
								<option value="0" <?= (isset($store['auto_complete_digital']) && $store['auto_complete_digital'] == 0) ? 'selected' : '' ?>><?= __('admin.disabled') ?></option>
								<option value="1" <?= (isset($store['auto_complete_digital']) && $store['auto_complete_digital'] == 1) ? 'selected' : '' ?>><?= __('admin.enabled') ?></option>
							</select>
							<small class="text-muted mt-1 d-block"><?= __('admin.auto_complete_digital_desc') ?></small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="mb-4">
			<div class="bg-white rounded shadow-sm border overflow-hidden">
				<div class="p-3 bg-light border-bottom d-flex align-items-center">
					<h6 class="text-dark mb-0 fw-medium">
						<i class="fas fa-fire me-2 text-danger"></i><?= __('admin.social_proof_settings') ?>
					</h6>
				</div>
				<div class="p-4">
					<div class="row g-4">
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.social_proof_enabled') ?></label>
							<select name="store[social_proof_enabled]" class="form-select">
								<option value="0" <?= (isset($store['social_proof_enabled']) && $store['social_proof_enabled'] == 0) ? 'selected' : '' ?>><?= __('admin.disabled') ?></option>
								<option value="1" <?= (isset($store['social_proof_enabled']) && $store['social_proof_enabled'] == 1) ? 'selected' : '' ?>><?= __('admin.enabled') ?></option>
							</select>
							<small class="text-muted mt-1 d-block"><?= __('admin.social_proof_desc') ?></small>
						</div>
						<div class="col-md-6">
							<label class="form-label fw-medium"><?= __('admin.store_messaging_enabled') ?></label>
							<select name="store[messaging_enabled]" class="form-select">
								<option value="0" <?= (isset($store['messaging_enabled']) && $store['messaging_enabled'] == 0) ? 'selected' : '' ?>><?= __('admin.disabled') ?></option>
								<option value="1" <?= (isset($store['messaging_enabled']) && $store['messaging_enabled'] == 1) ? 'selected' : '' ?>><?= __('admin.enabled') ?></option>
							</select>
							<small class="text-muted mt-1 d-block"><?= __('admin.store_messaging_desc') ?></small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="mb-4">
			<div class="bg-white rounded shadow-sm border overflow-hidden">
				<div class="p-3 bg-light border-bottom d-flex align-items-center">
					<h6 class="text-dark mb-0 fw-medium">
						<i class="fas fa-money-bill-transfer me-2 text-info"></i><?= __('admin.vendor_payout_settings') ?>
					</h6>
				</div>
				<div class="p-4">
					<div class="row g-4">
						<div class="col-md-4">
							<label class="form-label fw-medium"><?= __('admin.vendor_payout_enabled') ?></label>
							<select name="store[payout_enabled]" class="form-select">
								<option value="0" <?= (isset($store['payout_enabled']) && $store['payout_enabled'] == 0) ? 'selected' : '' ?>><?= __('admin.disabled') ?></option>
								<option value="1" <?= (isset($store['payout_enabled']) && $store['payout_enabled'] == 1) ? 'selected' : '' ?>><?= __('admin.enabled') ?></option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label fw-medium"><?= __('admin.min_payout_amount') ?></label>
							<input type="number" name="store[min_payout_amount]" class="form-control" value="<?= $store['min_payout_amount'] ?? 50 ?>" min="0" step="0.01">
						</div>
						<div class="col-md-4">
							<label class="form-label fw-medium"><?= __('admin.payout_methods') ?></label>
							<input type="text" name="store[payout_methods]" class="form-control" value="<?= $store['payout_methods'] ?? 'bank,paypal' ?>" placeholder="bank,paypal,stripe">
							<small class="text-muted mt-1 d-block"><?= __('admin.payout_methods_desc') ?></small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	$(document).on('change', 'select[name="store[store_custom_logo_size]"]', function() {
		if($(this).val() == 1) {
			$('.front_logo_cust_size_inp').show();
		} else {
			$('.front_logo_cust_size_inp').hide();
		}
	});

	// Sub-tab navigation active state management with memory
	$(document).ready(function() {
		// Check for saved sub-tab in localStorage
		var savedSubTab = localStorage.getItem('activeStoreSubTab');
		
		// Function to activate a specific sub-tab
		function activateSubTab(targetTab) {
			// Remove active class from ALL sub-tab links
			$('#storeSubTabs .nav-link').removeClass('active');
			
			// Remove show active from ALL sub-tab content
			$('#basic_settings, #appearance_settings, #contact_analytics, #smart_commerce_settings').removeClass('show active');
			
			// Add active class ONLY to the target tab link
			$('#storeSubTabs a[href="' + targetTab + '"]').addClass('active');
			
			// Show ONLY the target tab content
			$(targetTab).addClass('show active');
		}
		
		// Handle sub-tab clicks
		$('#storeSubTabs a[data-bs-toggle="tab"]').on('click', function(e) {
			e.preventDefault();
			
			var targetTab = $(this).attr('href');
			
			// Save current sub-tab to localStorage
			localStorage.setItem('activeStoreSubTab', targetTab);
			
			// Activate the clicked tab
			activateSubTab(targetTab);
		});
		
		// Restore saved sub-tab or default to first tab
		if (savedSubTab && $(savedSubTab).length) {
			// Activate saved sub-tab
			activateSubTab(savedSubTab);
		} else {
			// Default to first tab
			activateSubTab('#basic_settings');
		}
	});
	</script>
	</div><!-- /tab-content -->
</div><!-- /store_main -->


<!--product_commission-->
        <div class="tab-pane fade p-3" id="product_setting" role="tabpanel">

	<!-- Affiliate Tracking -->
	<div class="bg-white rounded shadow-sm border overflow-hidden mb-4">
		<div class="bg-primary bg-opacity-10 border-bottom p-3">
			<h6 class="text-dark mb-0 fw-medium">
				<i class="fas fa-crosshairs me-2 text-primary"></i><?= __('admin.affiliate_tracking') ?>
			</h6>
		</div>
		<div class="p-4">
			<div class="row">
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-medium"><?= __('admin.select_tracking_method') ?></label>
						<select class="form-select" name="store[affiliate_tracking_place]">
							<option value="0" <?= (!isset($store_setting['affiliate_tracking_place']) || $store_setting['affiliate_tracking_place'] == 0) ? 'selected' : '' ?>><?= __('admin.use_cookies') ?></option>
							<option value="1" <?= (isset($store_setting['affiliate_tracking_place']) && $store_setting['affiliate_tracking_place'] == 1) ? 'selected' : '' ?>><?= __('admin.use_local_storage') ?></option>
							<option value="2" <?= (isset($store_setting['affiliate_tracking_place']) && $store_setting['affiliate_tracking_place'] == 2) ? 'selected' : '' ?>><?= __('admin.use_cookies_and_local_storage_both') ?></option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-medium"><?= __('admin.affiliate_cookie') ?></label>
						<input class="form-control" type="number" value="<?= isset($store_setting['affiliate_cookie']) ? $store_setting['affiliate_cookie'] : 30 ?>" name="store[affiliate_cookie]">
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Lifetime Commission -->
	<div class="bg-white rounded shadow-sm border overflow-hidden mb-4">
		<div class="bg-info bg-opacity-10 border-bottom p-3">
			<h6 class="text-dark mb-0 fw-medium">
				<i class="fas fa-infinity me-2 text-info"></i><?= __('admin.lifetime_commission') ?>
			</h6>
		</div>
		<div class="p-4">
			<p class="text-muted small mb-3"><?= __('admin.lifetime_commission_desc') ?></p>
			<div class="form-check form-switch">
				<input class="form-check-input update_all_settings" type="checkbox" <?= (isset($store_setting['lifetime_commission']) && $store_setting['lifetime_commission']==1) ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="lifetime_commission" data-setting_type="store">
				<label class="form-check-label"><?= __('admin.lifetime_commission') ?></label>
			</div>
		</div>
	</div>

	<div class="col-12 commission_settings">
		<div class="row">
			<div class="col-md-6 commission_product_settings">
			<div class="bg-white rounded shadow-sm border overflow-hidden mb-4">
				<div class="bg-success bg-opacity-10 border-bottom p-3">
					<h6 class="text-dark mb-0 fw-medium">
						<i class="fas fa-shopping-bag me-2 text-success"></i><?= __('admin.store_p_commission') ?>
					</h6>
				</div>
				<div class="p-4">
					<fieldset>
						<legend class="product_commission_title h4" style="display: none;">
							<?= __('admin.store_p_commission') ?>
						</legend>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.click_allow') ?></label>
							<select name="productsetting[click_allow]" class="form-select">
								<option <?php if($productsetting['click_allow'] == 'single') { ?> selected <?php } ?> value="single"><?= __('admin.allow_single_click') ?></option>
								<option <?php if($productsetting['click_allow'] == 'multiple') { ?> selected <?php } ?> value="multiple"><?= __('admin.allow_multi_click') ?></option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.commission_type') ?></label>
							<select name="productsetting[product_commission_type]" class="form-select">
								<option value=""><?= __('admin.select_product_commission_type') ?></option>
								<option <?php if($productsetting['product_commission_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.percentage%') ?></option>
								<option <?php if($productsetting['product_commission_type'] == 'Fixed') { ?> selected <?php } ?> value="Fixed"><?= __('admin.fixed') ?></option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.commission_for_sale') ?></label>
							<div class="input-group">
								<span class="input-group-text currency-symbol">
									<?= ($productsetting['product_commission_type'] == 'percentage') ? '%'  : $CurrencySymbol ?>
								</span>
								<input name="productsetting[product_commission]" value="<?php echo $productsetting['product_commission']; ?>" class="form-control" type="number">
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.commission_for_ppc_visits_view') ?> (<?= $CurrencySymbol ?>)</label>
							<input  name="productsetting[product_ppc]" value="<?php echo $productsetting['product_ppc']; ?>" class="form-control" type="number">
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.number_of_clicks_per_commission') ?></label>
							<input  name="productsetting[product_noofpercommission]" value="<?php echo $productsetting['product_noofpercommission']; ?>" class="form-control" type="number">
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.recurring_cycle_setting') ?></label>							
							<select name="productsetting[product_recursion]" class="form-select" id="recursion_type">
								<option value=""><?= __('admin.select_recursion') ?></option>
								<option <?php if($productsetting['product_recursion'] == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
								<option <?php if($productsetting['product_recursion'] == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
								<option <?php if($productsetting['product_recursion'] == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
								<option <?php if($productsetting['product_recursion'] == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
								<option <?php if($productsetting['product_recursion'] == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
							</select>
							<div class="custom_time" <?php echo ($productsetting['product_recursion'] != 'custom_time') ? 'style="display:none"' : '';  ?>>
								<?php
									$minutes = $productsetting['recursion_custom_time'];
									$day = floor ($minutes / 1440);
									$hour = floor (($minutes - $day * 1440) / 60);
									$minute = $minutes - ($day * 1440) - ($hour * 60);
								?>
								<input type="hidden" name="productsetting[recursion_custom_time]" value="<?php echo $minutes; ?>" class="recursion_custom_time">
								<div class="row">
									<div class="col-sm-4">
										<label class="form-label"><?= __('admin.days') ?> : </label>
										<input placeholder="Days" type="number" class="form-control recur_day" value="<?= $day ? $day : '' ?>" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
									</div>
									<div class="col-sm-4">
										<label class="form-label"><?= __('admin.hours') ?> : </label>
										<select class="form-select recur_hour">
											<?php 
											for ($x = 0; $x <= 23; $x++) {
												$selected = ($x == $hour ) ? 'selected="selected"' : '';
												echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
											}
											?>
										</select>
									</div>
									<div class="col-sm-4">
										<label class="form-label"><?= __('admin.minutes') ?> : </label>
										<select class="form-select recur_minute">
											<?php 
											for ($x = 0; $x <= 59; $x++) {
												$selected = ($x == $minute ) ? 'selected="selected"' : '';
												echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
											}
											?>
										</select>
									</div>					
								</div>
								<small class="text-danger productsetting_error"></small>
							</div>
							<div class="row mt-3">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="form-label d-block"><?= __('admin.choose_custom_endtime') ?> <input <?= $productsetting['recursion_endtime'] ? 'checked' : '' ?>  class='setCustomTime' name='recursion_endtime_status' type="checkbox"> </label>
										<div style="<?= !$productsetting['recursion_endtime'] ? 'display:none' : '' ?>" class='custom_time_container'>
											<input type="text" class="form-control" value="<?= $productsetting['recursion_endtime'] ? date("d-m-Y H:i",strtotime($productsetting['recursion_endtime'])) : '' ?>" name="productsetting[recursion_endtime]" id="endtime" placeholder="<?= __('admin.choose_endtime') ?>" >
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			</div>

			<div class="col-md-6 commission_form_settings">
			<div class="bg-white rounded shadow-sm border overflow-hidden mb-4">
				<div class="bg-warning bg-opacity-10 border-bottom p-3">
					<h6 class="text-dark mb-0 fw-medium">
						<i class="fas fa-wpforms me-2 text-warning"></i><?= __('admin.store_f_commission') ?>
					</h6>
				</div>
				<div class="p-4">
					<fieldset>
						<legend class="form_commission_title h4" style="display: none;">
							<?= __('admin.store_f_commission') ?>
						</legend>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.commission_type') ?></label>
							<select name="formsetting[product_commission_type]" class="form-select">
								<option value=""><?= __('admin.select_product_commission_type') ?></option>
								<option <?php if($formsetting['product_commission_type'] == 'percentage') { ?> selected <?php } ?> value="percentage"><?= __('admin.percentage%') ?></option>
								<option <?php if($formsetting['product_commission_type'] == 'Fixed') { ?> selected <?php } ?>  value="Fixed"><?= __('admin.fixed') ?></option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.commission_for_sale') ?></label>
							<div class="input-group">
								<span class="input-group-text currency-symbol">
									<?= ($formsetting['product_commission_type'] == 'percentage') ? '%'  : $CurrencySymbol ?>
								</span>
								<input name="formsetting[product_commission]" value="<?php echo $formsetting['product_commission']; ?>" class="form-control"  type="number">
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.commission_for_ppc_visits_view') ?> (<?= $CurrencySymbol ?>)</label>
							<input name="formsetting[product_ppc]" value="<?php echo $formsetting['product_ppc']; ?>" class="form-control"  type="number">
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.number_of_clicks_per_commission') ?></label>
							<input name="formsetting[product_noofpercommission]" value="<?php echo $formsetting['product_noofpercommission']; ?>" class="form-control"  type="number">
						</div>
						<div class="mb-3">
							<label class="form-label fw-medium"><?= __('admin.form_recursion') ?></label>                            
							<select name="formsetting[form_recursion]" class="form-select mb-3" id="form_recursion_type">
								<option value=""><?= __('admin.select_recursion') ?></option>
								<option <?php if($formsetting['form_recursion'] == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
								<option <?php if($formsetting['form_recursion'] == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
								<option <?php if($formsetting['form_recursion'] == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
								<option <?php if($formsetting['form_recursion'] == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
								<option <?php if($formsetting['form_recursion'] == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
							</select>
							<div class="custom_time" <?php echo ($formsetting['form_recursion'] != 'custom_time') ? 'style="display:none"' : '';  ?>>
								<?php
								$form_minutes = $formsetting['recursion_custom_time'];
								$f_day = floor ($form_minutes / 1440);
								$f_hour = floor (($form_minutes - $f_day * 1440) / 60);
								$f_minute = $form_minutes - ($f_day * 1440) - ($f_hour * 60);
								?>
								<input type="hidden" name="formsetting[recursion_custom_time]" value="<?php echo $form_minutes; ?>" class="recursion_custom_time">
								<div class="row">
									<div class="col-sm-4">
										<label class="form-label"><?=  __('admin.days') ?> : </label>
										<input placeholder="<?=  __('admin.days') ?>" type="number" class="form-control recur_day" value="<?= $f_day ? $f_day : '' ?>" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
									</div>                      
									<div class="col-sm-4">
										<label class="form-label"><?=  __('admin.hours') ?> : </label>
										<select class="form-select recur_hour">
											<?php 
											for ($x = 0; $x <= 23; $x++) {
												$selected = ($x == $f_hour ) ? 'selected="selected"' : '';
												echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
											}
											?>
										</select>
									</div>                      
									<div class="col-sm-4">
										<label class="form-label"><?=  __('admin.minutes') ?> : </label>
										<select class="form-select recur_minute">
											<?php 
											for ($x = 0; $x <= 59; $x++) {
												$selected = ($x == $f_minute ) ? 'selected="selected"' : '';
												echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
											}
											?>
										</select>
									</div>                      
								</div>
								<div class="form-text formsetting_error"></div>
							</div>
							<br>
							<div class="endtime-chooser row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label d-block"><?= __('admin.choose_custom_endtime') ?> 
											<input <?= $formsetting['recursion_endtime'] ? 'checked' : '' ?> class='setCustomTime' name='recursion_endtime_form_status' type="checkbox"> 
										</label>
										<div style="<?= !$formsetting['recursion_endtime'] ? 'display:none' : '' ?>" class='custom_time_container'>
											<input type="text" class="form-control datetime-picker" value="<?= $formsetting['recursion_endtime'] ? date("d-m-Y H:i",strtotime($formsetting['recursion_endtime'])) : '' ?>" name="formsetting[recursion_endtime]" id="endtime" placeholder="<?= __('admin.choose_endtime') ?>">
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			</div>
		</div>

	</div>
</div>
<!--product_commission-->


<!--order_comment-->
        <div class="tab-pane fade p-3" id="order_comment" role="tabpanel">
    <div class="mb-3">
        <label class="form-label"><?= __('admin.enable_comment') ?></label>
        <select class="form-select" name="order_comment[status]">
            <option value="0"><?= __('admin.disable') ?></option>
            <option value="1" <?= $order_comment['status'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
        </select>
    </div>
    <div class="comment-titles">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th><?= __('admin.title') ?></th>
                    <th class="text-end"><?= __('admin.action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_comment['title'] as $key => $value) { ?>
                    <tr>
                        <td>
                            <input type="text" name="order_comment[title][<?= $key ?>]" value="<?= $value ?>" class="form-control" placeholder="<?= __('admin.comment_title') ?>" aria-describedby="title-<?= $key ?>" />
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-danger" onclick="$(this).closest('tr').remove()"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end">
                        <button type="button" class="btn btn-primary btn-add-comment"><i class="bi bi-plus"></i></button>
                    </td>
                </tr>
            </tfoot>  
        </table>
    </div>
</div>
<!--order_comment-->



<!--theme_section-->
        <div class="tab-pane fade p-3" id="theme_section" role="tabpanel">

            <!-- ══ Global Demo / Clear toolbar ══════════════════════════════ -->
            <div class="d-flex align-items-center justify-content-between bg-white border rounded px-4 py-3 mb-4 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-magic fs-4 text-primary"></i>
                    <div>
                        <h6 class="mb-0 fw-semibold"><?= __('admin.demo_data_toolbar_title') ?></h6>
                        <small class="text-muted"><?= __('admin.demo_data_toolbar_desc') ?></small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-primary btn-demo-all">
                        <i class="bi bi-magic me-1"></i><?= __('admin.load_demo_data') ?>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-clear-all">
                        <i class="bi bi-trash3 me-1"></i><?= __('admin.clear_all_demo') ?>
                    </button>
                </div>
            </div>
            <!-- ════════════════════════════════════════════════════════════ -->

            <!-- ①  General Display Settings -->
            <div class="card shadow-sm border-0 border-start border-4 border-primary mb-4">
                <div class="card-header bg-primary bg-opacity-10 py-3 d-flex align-items-center justify-content-between border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ts-section-icon bg-primary bg-opacity-25">
                            <i class="bi bi-sliders2 text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold d-flex align-items-center gap-2">
                                <?= __('admin.categories_color_box_filter') ?> &amp; <?= __('admin.top_tags_limit') ?>
                                <span class="badge bg-primary fw-normal ts-step-badge">1</span>
                            </h6>
                            <small class="text-muted"><?= __('admin.section_general_display_desc') ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><?= __('admin.categories_color_box_filter') ?></label>
                            <select class="form-select" name="store[is_variation_filter]">
                                <option value="0"><?= __('admin.disable') ?></option>
                                <option value="1" <?= $store_setting['is_variation_filter'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
                            </select>
                            <div class="form-text"><?= __('admin.categories_color_box_filter') ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><?= __('admin.top_tags_limit') ?></label>
                            <input type="number" name="store[top_tags_limit]" class="form-control" value="<?= $store_setting['top_tags_limit'] ? $store_setting['top_tags_limit'] : 10 ?>" min="1" max="100">
                            <div class="form-text"><?= __('admin.top_tags_limit') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ②  Store Notification Bar -->
            <div class="card shadow-sm border-0 border-start border-4 border-warning mb-4">
                <div class="card-header bg-warning bg-opacity-10 py-3 d-flex align-items-center justify-content-between border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ts-section-icon bg-warning bg-opacity-25">
                            <i class="bi bi-megaphone text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold d-flex align-items-center gap-2">
                                <?= __('admin.store_notifications') ?>
                                <span class="badge bg-warning text-dark fw-normal ts-step-badge">2</span>
                            </h6>
                            <small class="text-muted"><?= __('admin.section_notifications_desc') ?></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-warning text-white btn-add-more">
                            <i class="bi bi-plus-lg me-1"></i><?= __('admin.add_more') ?>
                        </button>
                        <div class="d-flex align-items-center gap-1">
                            <input type="hidden" name="store[notification_enabled]" value="0">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="store[notification_enabled]" value="1"
                                       id="toggle_notification_enabled"
                                       <?= (($store_setting['notification_enabled'] ?? '1') !== '0') ? 'checked' : '' ?>>
                                <label class="form-check-label small" for="toggle_notification_enabled"><?= __('admin.enabled') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="notifications-list">
                        <?php
                            $notis = json_decode($store_setting['notification']);
                            if (isset($notis)) {
                                for ($i = 0; $i < sizeOf($notis); $i++) {
                        ?>
                        <div class="row align-items-center mb-2">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label class="control-label"><?= __('admin.notification') ?> <?= ($i + 1) ?></label>
                                    <input name="store[notification][]" class="form-control" type="text" value="<?= htmlspecialchars($notis[$i]) ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-md remove-notification-btn"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <?php
                                }
                            }
                        ?>
                    </div>
                    <?php if (empty($notis)): ?>
                    <p class="text-muted text-center py-2 mb-0">
                        <i class="bi bi-megaphone me-1"></i><?= __('admin.store_notifications') ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ③  Homepage Slider -->
            <div class="card shadow-sm border-0 border-start border-4 border-info mb-4">
                <div class="card-header bg-info bg-opacity-10 py-3 d-flex align-items-center justify-content-between border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ts-section-icon bg-info bg-opacity-25">
                            <i class="bi bi-images text-info fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold d-flex align-items-center gap-2">
                                <?= __('admin.homepage_slider') ?>
                                <span class="badge bg-info text-dark fw-normal ts-step-badge">3</span>
                            </h6>
                            <small class="text-muted"><?= __('admin.section_slider_desc') ?></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-info text-white btn-slider-form-modal">
                            <i class="bi bi-plus-lg me-1"></i><?= __('admin.add_more') ?>
                        </button>
                        <div class="d-flex align-items-center gap-1">
                            <input type="hidden" name="store[homepage_slider_enabled]" value="0">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="store[homepage_slider_enabled]" value="1"
                                       id="toggle_slider_enabled"
                                       <?= (($store_setting['homepage_slider_enabled'] ?? '1') !== '0') ? 'checked' : '' ?>>
                                <label class="form-check-label small" for="toggle_slider_enabled"><?= __('admin.enabled') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="ts-table-wrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 ts-col-idx">#</th>
                                <th><?= __('admin.banner_title') ?></th>
                                <th class="d-none d-md-table-cell ts-col-thumb"><?= __('admin.banner_bottom_image') ?></th>
                                <th class="ts-col-act"></th>
                            </tr>
                        </thead>
                        <tbody id="homepage_sliders_list">
                        <?php
                        $homepage_slider = json_decode($store_setting['homepage_slider']);
                        if (!sizeof($homepage_slider) > 0):
                            echo "<tr class='empty'><td colspan='4' class='text-center py-4 text-muted'><i class='bi bi-image d-block fs-2 mb-1 opacity-25'></i><small>".__('admin.sliders_not_available')."</small></td></tr>";
                        endif;
                        foreach ($homepage_slider as $hs): ?>
                        <tr>
                            <td class="ps-3"><?= $hs->index ?></td>
                            <td class="ts-title-cell">
                                <span class="ts-title"><?= htmlspecialchars($hs->title) ?></span>
                                <small class="ts-subtitle"><?= htmlspecialchars($hs->sub_title) ?></small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <?php $img = (!empty($hs->slider_background_image)) ? base_url('assets/images/site/' . $hs->slider_background_image) : base_url('assets/store/default/img/banner.png'); ?>
                                <img src="<?= $img ?>" class="ts-slider-img">
                            </td>
                            <td class="ts-action-cell">
                                <input type="hidden" name="store[homepage_slider][edited][]" value="0">
                                <input type="hidden" name="store[homepage_slider][index][]" value="<?= $hs->index ?>">
                                <input type="hidden" name="store[homepage_slider][title][]" value="<?= $hs->title ?>">
                                <input type="hidden" name="store[homepage_slider][sub_title][]" value="<?= $hs->sub_title ?>">
                                <textarea name="store[homepage_slider][content][]" class="ts-data-textarea"><?= $hs->content ?></textarea>
                                <input type="hidden" name="store[homepage_slider][slider_background_image][]" value="<?= $hs->slider_background_image ?>">
                                <input type="hidden" name="store[homepage_slider][button_text][]" value="<?= $hs->button_text ?>">
                                <input type="hidden" name="store[homepage_slider][button_link][]" value="<?= $hs->button_link ?>">
                                <input type="hidden" name="store[homepage_slider][slider_text_color][]" value="<?= $hs->slider_text_color ?>">
                                <input type="hidden" name="store[homepage_slider][button_text_color][]" value="<?= $hs->button_text_color ?>">
                                <input type="hidden" name="store[homepage_slider][button_bg_color][]" value="<?= $hs->button_bg_color ?>">
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-slider-form-modal-edit me-1" title="<?= __('admin.edit') ?>"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-slider-btn" title="<?= __('admin.delete') ?>"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <!-- ④  Homepage Features Bar -->
            <div class="card shadow-sm border-0 border-start border-4 border-success mb-4">
                <div class="card-header bg-success bg-opacity-10 py-3 d-flex align-items-center justify-content-between border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ts-section-icon bg-success bg-opacity-25">
                            <i class="bi bi-grid-3x3-gap text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold d-flex align-items-center gap-2">
                                <?= __('admin.homepage_features') ?>
                                <span class="badge bg-success fw-normal ts-step-badge">4</span>
                            </h6>
                            <small class="text-muted"><?= __('admin.section_features_desc') ?></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-success text-white btn-features-form-modal">
                            <i class="bi bi-plus-lg me-1"></i><?= __('admin.add_more') ?>
                        </button>
                        <div class="d-flex align-items-center gap-1">
                            <input type="hidden" name="store[homepage_features_enabled]" value="0">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="store[homepage_features_enabled]" value="1"
                                       id="toggle_features_enabled"
                                       <?= (($store_setting['homepage_features_enabled'] ?? '1') !== '0') ? 'checked' : '' ?>>
                                <label class="form-check-label small" for="toggle_features_enabled"><?= __('admin.enabled') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="ts-table-wrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 ts-col-idx">#</th>
                                <th><?= __('admin.banner_title') ?></th>
                                <th class="d-none d-md-table-cell ts-col-thumb"><?= __('admin.banner_bottom_image') ?></th>
                                <th class="ts-col-act"></th>
                            </tr>
                        </thead>
                        <tbody id="homepage_features_list">
                        <?php
                        $homepage_features = json_decode($store_setting['homepage_features']);
                        if (!sizeof($homepage_features) > 0):
                            echo "<tr class='empty'><td colspan='4' class='text-center py-4 text-muted'><i class='bi bi-grid-3x3-gap d-block fs-2 mb-1 opacity-25'></i><small>".__('admin.features_not_available')."</small></td></tr>";
                        endif;
                        foreach ($homepage_features as $hf): ?>
                        <tr>
                            <td class="ps-3"><?= $hf->index ?></td>
                            <td class="ts-title-cell">
                                <span class="ts-title"><?= htmlspecialchars($hf->title) ?></span>
                                <small class="ts-subtitle"><?= htmlspecialchars($hf->sub_title) ?></small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <?php $img = (!empty($hf->feature_image)) ? base_url('assets/images/site/' . $hf->feature_image) : base_url('assets/store/default/img/banner.png'); ?>
                                <img src="<?= $img ?>" class="ts-feature-img">
                            </td>
                            <td class="ts-action-cell">
                                <input type="hidden" name="store[homepage_features][edited][]" value="0">
                                <input type="hidden" name="store[homepage_features][index][]" value="<?= $hf->index ?>">
                                <input type="hidden" name="store[homepage_features][title][]" value="<?= $hf->title ?>">
                                <input type="hidden" name="store[homepage_features][sub_title][]" value="<?= $hf->sub_title ?>">
                                <input type="hidden" name="store[homepage_features][feature_image][]" value="<?= $hf->feature_image ?>">
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-features-form-modal-edit me-1" title="<?= __('admin.edit') ?>"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-features-btn" title="<?= __('admin.delete') ?>"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <!-- ⑤  Homepage Bottom Banner -->
            <?php $homepage_banner = json_decode($store_setting['homepage_banner']); ?>
            <div class="card shadow-sm border-0 border-start border-4 border-purple mb-4">
                <div class="card-header bg-purple-subtle py-3 d-flex align-items-center justify-content-between border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ts-section-icon bg-purple-soft">
                            <i class="bi bi-badge-ad text-purple fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold d-flex align-items-center gap-2">
                                <?= __('admin.homepage_bottom_banner') ?>
                                <span class="badge badge-purple fw-normal ts-step-badge">5</span>
                            </h6>
                            <small class="text-muted"><?= __('admin.section_banner_desc') ?></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-1">
                            <input type="hidden" name="store[homepage_banner_enabled]" value="0">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="store[homepage_banner_enabled]" value="1"
                                       id="toggle_banner_enabled"
                                       <?= (($store_setting['homepage_banner_enabled'] ?? '1') !== '0') ? 'checked' : '' ?>>
                                <label class="form-check-label small" for="toggle_banner_enabled"><?= __('admin.enabled') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium"><?= __('admin.banner_title') ?></label>
                            <input name="store[homepage_banner][title]" value="<?= htmlspecialchars($homepage_banner->title) ?>" class="form-control" type="text">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium"><?= __('admin.banner_content') ?></label>
                            <textarea name="store[homepage_banner][content]" class="form-control" rows="3"><?= htmlspecialchars($homepage_banner->content) ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium"><?= __('admin.banner_bottom_text') ?></label>
                            <input name="store[homepage_banner][button_text]" value="<?= htmlspecialchars($homepage_banner->button_text) ?>" class="form-control" type="text">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-medium"><?= __('admin.banner_bottom_link') ?></label>
                            <input name="store[homepage_banner][button_link]" value="<?= htmlspecialchars($homepage_banner->button_link) ?>" class="form-control" type="text">
                        </div>
                        <div class="col-md-6">
                            <label for="store_hbanimage" class="form-label fw-medium"><?= __('admin.banner_bottom_image') ?></label>
                            <input class="form-control" type="file" id="store_hbanimage" name="store_hbanimage">
                            <input type="hidden" id="homepage_bootom_banner_image" name="store[hbanimage]" value="<?= $store['hbanimage'] ?>">
                        </div>
                        <div class="col-md-4 d-flex flex-column justify-content-end">
                            <?php if (!empty($store['hbanimage'])): ?>
                            <img id="store_hbanimage_container"
                                 src="<?= base_url('assets/images/site/' . $store['hbanimage']) ?>"
                                 class="ts-banner-preview">
                            <?php else: ?>
                            <div id="store_hbanimage_container" class="ts-banner-preview ts-banner-preview--empty d-flex flex-column align-items-center justify-content-center text-muted small bg-light rounded border">
                                <i class="bi bi-image fs-3 opacity-25 mb-1"></i>
                                <span class="opacity-50"><?= __('admin.no_image_selected') ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <?php if ($store['hbanimage'] != ''): ?>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-bottom-ban-image w-100"
                                    data-img_input="store['hbanimage']"
                                    data-img_ele="store_hbanimage_container"
                                    data-img_placeholder="<?= base_url('assets/store/default/img/ad-bg.jpg') ?>">
                                <i class="bi bi-trash me-1"></i><?= __('admin.delete') ?>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ⑥  Homepage Bottom Section -->
            <div class="card shadow-sm border-0 border-start border-4 border-danger mb-4">
                <div class="card-header bg-danger bg-opacity-10 py-3 d-flex align-items-center justify-content-between border-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ts-section-icon bg-danger bg-opacity-25">
                            <i class="bi bi-layout-three-columns text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold d-flex align-items-center gap-2">
                                <?= __('admin.homepage_bottom_section') ?>
                                <span class="badge bg-danger fw-normal ts-step-badge">6</span>
                            </h6>
                            <small class="text-muted"><?= __('admin.section_bottom_desc') ?></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-danger text-white btn-bs-cards-form-modal">
                            <i class="bi bi-plus-lg me-1"></i><?= __('admin.add_more') ?>
                        </button>
                        <div class="d-flex align-items-center gap-1">
                            <input type="hidden" name="store[homepage_bottom_section_enabled]" value="0">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="store[homepage_bottom_section_enabled]" value="1"
                                       id="toggle_bottom_section_enabled"
                                       <?= (($store_setting['homepage_bottom_section_enabled'] ?? '1') !== '0') ? 'checked' : '' ?>>
                                <label class="form-check-label small" for="toggle_bottom_section_enabled"><?= __('admin.enabled') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="ts-table-wrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 ts-col-idx">#</th>
                                <th><?= __('admin.banner_title') ?></th>
                                <th class="d-none d-md-table-cell ts-col-thumb"><?= __('admin.banner_bottom_image') ?></th>
                                <th class="ts-col-act"></th>
                            </tr>
                        </thead>
                        <tbody id="bs_cards_list">
                        <?php
                        $bs_cards = json_decode($store_setting['bs_cards']);
                        if (!sizeof($bs_cards) > 0):
                            echo "<tr class='empty'><td colspan='4' class='text-center py-4 text-muted'><i class='bi bi-layout-three-columns d-block fs-2 mb-1 opacity-25'></i><small>".__('admin.cards_not_available')."</small></td></tr>";
                        endif;
                        foreach ($bs_cards as $bsc): ?>
                        <tr>
                            <td class="ps-3"><?= $bsc->index ?></td>
                            <td class="ts-title-cell">
                                <span class="ts-title"><?= htmlspecialchars($bsc->title) ?></span>
                                <small class="ts-subtitle"><?= htmlspecialchars($bsc->sub_title) ?></small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <?php $img = (!empty($bsc->feature_image)) ? base_url('assets/images/site/' . $bsc->feature_image) : base_url('assets/store/default/img/banner.png'); ?>
                                <img src="<?= $img ?>" class="ts-card-img">
                            </td>
                            <td class="ts-action-cell">
                                <input type="hidden" name="store[bs_cards][edited][]" value="0">
                                <input type="hidden" name="store[bs_cards][index][]" value="<?= $bsc->index ?>">
                                <input type="hidden" name="store[bs_cards][title][]" value="<?= $bsc->title ?>">
                                <input type="hidden" name="store[bs_cards][sub_title][]" value="<?= $bsc->sub_title ?>">
                                <input type="hidden" name="store[bs_cards][feature_image][]" value="<?= $bsc->feature_image ?>">
                                <input type="hidden" name="store[bs_cards][bg_color][]" value="<?= $bsc->bg_color ?>">
                                <input type="hidden" name="store[bs_cards][button_link][]" value="<?= $bsc->button_link ?>">
                                <input type="hidden" name="store[bs_cards][link_target][]" value="<?= $bsc->link_target ?>">
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-bs-cards-form-modal-edit me-1" title="<?= __('admin.edit') ?>"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-bs-cards-btn" title="<?= __('admin.delete') ?>"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top p-3">
                    <label class="form-label fw-medium"><?= __('admin.section_content') ?></label>
                    <textarea id="bs_section_content_editor" name="store[homepage_bottom_section][content]" class="form-control summernote"><?= json_decode($store_setting['homepage_bottom_section'])->content ?></textarea>
                </div>
            </div>

        </div>
<!--theme_section-->



<!--static_pages_section-->
        <div class="tab-pane fade p-3" id="static_pages_section" role="tabpanel">
    <div class="mb-3">
        <label class="form-label"><?= __('admin.select_language') ?></label>
        <select class="form-select" name="language_id" id="drpLanguage" onchange="return changeLanguage();">
            <?php 
            if(isset($languages))
            {
            $language_id=1;
            foreach($languages as $language)
            {?>
            <option <?php 
            if($language['is_default']==1) {echo 'selected';} ?> value="<?=$language['id']?>"><?=$language['name'] ?></option>
            <?php  }     
            }?>
        </select>
    </div>

    <div class="mb-3">
        <label  class="form-label"><?= __('admin.about_page_content') ?></label>
        <textarea name="store[about_content]" id="about_content" class="form-control summernote"><?php echo $store['about_content']; ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="mb-3">
                <label  class="form-label"><?= __('admin.about_page_image') ?></label>
                <input class="form-control" type="file" name="store_aboutimage">
            </div>
        </div>

        <div class="col-md-4">
            <?php
            if(!empty($store['aboutimage']))
            {
            ?>
            <img id="store_aboutimage_container"  style="width: 150px;" src="<?= base_url('assets/images/site/'.$store['aboutimage'].''); ?>" class='img-fluid'>
            <?php    
            }
            else
            {
            ?>
            <img id="store_aboutimage_container"  style="width: 150px;" src="<?= base_url('assets/images/no_image_available.png'); ?>" class='img-fluid'>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="mb-3">
        <label  class="form-label"><?= __('admin.contact_page_content') ?></label>
        <textarea name="store[contact_content]" id="contact_content" class="form-control summernote"><?php echo $store['contact_content']; ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="mb-3">
                <label  class="form-label"><?= __('admin.contact_page_image') ?></label>
                <input class="form-control" type="file" name="store_contactimage">
            </div>
        </div>

        <div class="col-md-4">
            <?php
            if(!empty($store['contactimage']))
            {
            ?>
            <img id="store_contactimage_container"  style="width: 150px;" src="<?= base_url('assets/images/site/'.$store['contactimage'].''); ?>" class='img-fluid'>
            <?php    
            }
            else
            {
            ?>
            <img id="store_contactimage_container"  style="width: 150px;" src="<?= base_url('assets/images/no_image_available.png'); ?>" class='img-fluid'>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="mb-3">
        <label  class="form-label"><?= __('admin.policy_page_content') ?></label>
        <textarea name="store[policy_content]" id="policy_content" class="form-control summernote"><?php echo $store['policy_content']; ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="mb-3">
                <label  class="form-label"><?= __('admin.policy_page_image') ?></label>
                <input class="form-control" type="file" name="store_policyimage">
            </div>
        </div>

        <div class="col-md-4">
            <?php
            if(!empty($store['policyimage']))
            {
            ?>
            <img id="store_policyimage_container"  style="width: 150px;" src="<?= base_url('assets/images/site/'.$store['policyimage'].''); ?>" class='img-fluid'>
            <?php    
            }
            else
            {
            ?>
            <img id="store_policyimage_container"  style="width: 150px;" src="<?= base_url('assets/images/no_image_available.png'); ?>" class='img-fluid'>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!--static_pages_section-->



<!--pages_menu_section-->
        <div class="tab-pane fade p-3" id="pages_menu_section" role="tabpanel">

	<fieldset class="mb-3 cart_theme_settings <?= $cart_theme_settings_display ?>">
		<legend class="bg-light px-2"><?= __('admin.footer_menu_sections') ?></legend>
		<div class="row">
		<div class="col-12">
		<table class="table align-middle">
		<tbody id="footer_menu_list">
		<?php
		$footer_menu = json_decode($store_setting['footer_menu']);
		if(!sizeof($footer_menu) > 0) {
		echo "<tr class='empty'><td colspan='1oo%'><h6 class='text-center text-muted'>".__('admin.menu_not_available')."</h6></td></tr>";
		}
		foreach($footer_menu as $fm){
		$letpreIndex = $fm->index - 1;
		?>
		<tr>
		<td scope="row"><?= $fm->index; ?></td>
		<td scope="row"><?= $fm->title; ?></td>
		<td scope="row">
		<?php
		if(!sizeof($fm->links) > 0) {
		$text = "<i class='muted'>".__('admin.not_available')."</i>";
		} else {
		$text = "";
		}
		for ($i=0; $i < sizeOf($fm->links); $i++) { 
		$text .= ($i == 0) ? $fm->links[$i]->title : ", ".$fm->links[$i]->title;
		}
		echo $text;
		?>
		</td>
		<td style="width: 87px; padding: 5px 0px !important;">
		<input type="hidden" name="store[footer_menu][index][<?= $letpreIndex ?>]" value="<?= $fm->index; ?>">
		<input type="hidden" name="store[footer_menu][title][<?= $letpreIndex ?>]" value="<?= $fm->title; ?>">
		<?php
		for ($i=0; $i < sizeOf($fm->links); $i++) { 
		$text .= ($i == 0) ? $fm->links[$i]->title : ", ".$fm->links[$i]->title;
		?>
		<input type="hidden" name="store[footer_menu][links][<?= $letpreIndex ?>][title][]" value="<?= $fm->links[$i]->title; ?>">
		<input type="hidden" name="store[footer_menu][links][<?= $letpreIndex ?>][url][]" value="<?= $fm->links[$i]->url; ?>">
		<input type="hidden" name="store[footer_menu][links][<?= $letpreIndex ?>][type][]" value="<?= $fm->links[$i]->type; ?>">
		<?php
		}
		?>
		<button data-letpreindex="<?= $letpreIndex ?>" type="button" class="btn btn-primary btn-footer-menu-form-modal-edit"><i class="fa fa-pencil"></i></button>
		<button type="button" class="btn btn-danger remove-footer-menu"><i class="fa fa-trash"></i></button>
		</td>
		</tr>
		<?php	
		}
		?>
		</tbody>
		</table>
		</div>
		</div>
		<button type="button" class="btn btn-md btn-primary btn-footer-menu-form-modal"><?= __('admin.add_more') ?></button>
		</fieldset>

		<fieldset class="mb-3 cart_theme_settings <?= $cart_theme_settings_display ?>">
		<legend class="bg-light px-2"><?= __('admin.manage_custom_pages') ?></legend>
		<div class="row">
		<div class="col-12">
		<table class="table align-middle">
		<tbody id="custom_page_list">
		<?php
		$custom_page = json_decode($store_setting['custom_page']);
		if(!sizeof($custom_page) > 0) {
		echo "<tr class='empty'><td colspan='1oo%'><h6 class='text-center text-muted'>".__('admin.pages_not_available')."</h6></td></tr>";
		}
		foreach($custom_page as $page){
		?>
		<tr>
		<td scope="row"><?= $page->title; ?></td>
		<td scope="row"><?= $page->slug; ?></td>
		<?php $img = (!empty($page->image)) ? base_url('assets/images/site/'. $page->image) : base_url('assets/store/default/img/banner.png'); ?>
		<td style="width: 200px;"><img style="width: 100px; height: 50px;" src="<?= $img; ?>" class='img-responsive'></td>
		<td style="width: 87px; padding: 5px 0px !important;">
		<input type="hidden" name="store[custom_page][index][]" value="<?= $page->index; ?>">
		<input type="hidden" name="store[custom_page][edited][]" value="0">
		<input type="hidden" name="store[custom_page][title][]" value="<?= $page->title; ?>">
		<input type="hidden" name="store[custom_page][slug][]" value="<?= $page->slug; ?>">
		<input type="hidden" name="store[custom_page][image][]" value="<?= $page->image; ?>">
		<input type="hidden" name="store[custom_page][meta_id][]" value="<?= $page->meta_id; ?>">
		<textarea name="store[custom_page][content][]" style="display:none"><?= $page->content; ?></textarea>
		<button type="button" class="btn btn-primary btn-custom-page-modal-form-edit"><i class="fa fa-pencil"></i></button>
		<button type="button" class="btn btn-danger remove-custom-page"><i class="fa fa-trash"></i></button>
		</td>
		</tr>
		<?php	
		}
		?>
		</tbody>
		</table>
		</div>
		</div>
		<button type="button" class="btn btn-md btn-primary btn-custom-page-modal-form"><?= __('admin.add_more') ?></button>
		</fieldset>

		<fieldset class="mb-3 cart_theme_settings <?= $cart_theme_settings_display ?>">
		<legend class="bg-light px-2"><?= __('admin.manage_social_links') ?></legend>
		<div class="row">
		<div class="col-12">
		<table class="table align-middle">
		<tbody id="social_links_list">
		<?php
		$social_links = json_decode($store_setting['social_links']);
		if(!sizeof($social_links) > 0) {
		echo "<tr class='empty'><td colspan='1oo%'><h6 class='text-center text-muted'>".__('admin.links_not_available')."</h6></td></tr>";
		}
		foreach($social_links as $link){
		?>
		<tr>
		<td scope="row"><?= $link->title; ?></td>
		<td scope="row"><?= $link->url; ?></td>
		<?php $img = (!empty($link->image)) ? base_url('assets/images/site/'. $link->image) : base_url('assets/store/default/img/banner.png'); ?>
		<td style="width: 200px; text-align:right;">
		<img style="width: 50px; height: 50px; background-color:grey;" src="<?= $img; ?>" class='img-responsive'>
		</td>
		<td style="width: 87px; padding: 5px 0px !important;">
		<input type="hidden" name="store[social_links][index][]" value="<?= $link->index; ?>">
		<input type="hidden" name="store[social_links][edited][]" value="0">
		<input type="hidden" name="store[social_links][title][]" value="<?= $link->title; ?>">
		<input type="hidden" name="store[social_links][url][]" value="<?= $link->url; ?>">
		<input type="hidden" name="store[social_links][image][]" value="<?= $link->image; ?>">
		<button type="button" class="btn btn-primary btn-social-links-form-edit"><i class="fa fa-pencil"></i></button>
		<button type="button" class="btn btn-danger remove-social-links"><i class="fa fa-trash"></i></button>
		</td>
		</tr>
		<?php	
		}
		?>
		</tbody>
		</table>
		</div>
		</div>
		<button type="button" class="btn btn-md btn-primary btn-social-links-form"><?= __('admin.add_more') ?></button>
	</fieldset>

	<fieldset class="mb-3 sales_theme_settings <?= $sales_theme_settings_display ?>">
	    <legend class="bg-light px-2"><?= __('admin.homepage_banner') ?></legend>
	    <div class="row">
	        <div class="col-12">
	            <div class="form-group">
	                <label  class="control-label"><?= __('admin.banner_title') ?></label>
	                <input  name="store[classified_banner_title]" value="<?php echo $store['classified_banner_title']; ?>" class="form-control"  type="text">
	            </div>
	        </div>

	        <div class="col-12">
	            <div class="form-group">
	                <label  class="control-label"><?= __('admin.banner_subtitle') ?></label>
	                <input  name="store[classified_banner_subtitle]" value="<?php echo $store['classified_banner_subtitle']; ?>" class="form-control"  type="text">
	            </div>
	        </div>
	        
	        <div class="col-md-12">
	            <div class="form-group">
	                <label  class="control-label"><?= __('admin.banner_image') ?></label>
	                <br/>
	                <input type="file" name="store_classifiedbannerimg">
	            </div>
	            <?php
	            if(isset($store['classifiedbannerimg']) && !empty($store['classifiedbannerimg']))
	            {
	                ?>
	                <img id="store_classifiedbannerimg_container"  style="width: 150px;" src="<?= base_url('assets/images/site/'.$store['classifiedbannerimg'].''); ?>" class='img-fluid'>
	                <?php   
	            }
	            else
	            {
	                ?>
	                <img id="store_classifiedbannerimg_container"  style="width: 150px;" src="<?= base_url('assets/images/no_image_available.png'); ?>" class='img-fluid'>
	                <?php
	            }
	            ?>
	        </div>
	    </div>
	</fieldset>
</div>
<!--pages_menu_section-->


</div>
</div>	
</div>
</div>

<div class="card-footer text-end">
    <!-- Simple save button that works -->
    <input type="button" value="<?= __('admin.save_settings') ?>" onclick="submit_store_setting();" style="background: #0d6efd; color: white; padding: 8px 16px; border: none; cursor: pointer; border-radius: 4px; z-index: 999999; position: relative;">
</div>

</div>
</div>
</div>

<!-- modal slider-->
<div class="modal fade slider-form-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.manage_homepage_slide') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                <input id="hs_index" value="" class="form-control" type="hidden">
                <input type="hidden" id="hs_slider_background_image" value="">

                <!-- ── Best-practice tips ── -->
                <div class="alert alert-primary border-0 py-2 px-3 mb-3 small">
                    <div class="fw-semibold mb-1"><i class="bi bi-stars me-1"></i><?= __('admin.slider_best_practices_title') ?></div>
                    <ul class="mb-0 ps-3">
                        <li><strong><?= __('admin.slider_tip_image') ?></strong> <?= __('admin.slider_tip_image_desc') ?></li>
                        <li><strong><?= __('admin.slider_tip_title') ?></strong> <?= __('admin.slider_tip_title_desc') ?></li>
                        <li><strong><?= __('admin.slider_tip_subtitle') ?></strong> <?= __('admin.slider_tip_subtitle_desc') ?></li>
                        <li><strong><?= __('admin.slider_tip_desc') ?></strong> <?= __('admin.slider_tip_desc_desc') ?></li>
                        <li><strong><?= __('admin.slider_tip_button') ?></strong> <?= __('admin.slider_tip_button_desc') ?></li>
                    </ul>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold"><?= __('admin.slider_title') ?> <span class="text-muted fw-normal small">(<?= __('admin.recommended') ?>: 4–8 <?= __('admin.words') ?>)</span></label>
                    <input id="hs_title" value="" class="form-control" type="text"
                           placeholder="e.g. Discover Our New Collection"
                           maxlength="70">
                    <div class="form-text"><?= __('admin.slider_title_hint') ?></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold"><?= __('admin.slider_sub_title') ?> <span class="text-muted fw-normal small">(<?= __('admin.recommended') ?>: 8–12 <?= __('admin.words') ?>)</span></label>
                    <input id="hs_sub_title" value="" class="form-control" type="text"
                           placeholder="e.g. Premium quality products, unbeatable prices"
                           maxlength="100">
                    <div class="form-text"><?= __('admin.slider_subtitle_hint') ?></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold"><?= __('admin.slider_content') ?> <span class="text-muted fw-normal small">(<?= __('admin.optional') ?>, max 120 <?= __('admin.chars') ?>)</span></label>
                    <textarea id="hs_content" class="form-control" rows="2"
                              placeholder="e.g. Free shipping on all orders over $50. Limited time offer."
                              maxlength="120"></textarea>
                    <div class="form-text"><?= __('admin.slider_content_hint') ?></div>
                </div>
                
                <div class="row border rounded p-2 mx-1 mb-3 bg-light">
                    <div class="col-md-7">
                        <div class="mb-2 mb-md-0">
                            <label class="form-label fw-semibold"><?= __('admin.slider_background_image') ?></label>
                            <input type="file" name="store_hsbackgroundimage" class="form-control" accept="image/*">
                            <div class="form-text"><i class="bi bi-image me-1"></i><?= __('admin.slider_image_tip') ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <img id="store_hsbackgroundimage_container" style="width:100%;height:90px;object-fit:cover;border-radius:6px;" src="<?= base_url('assets/store/default/img/banner.png'); ?>" class="img-responsive">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><?= __('admin.slider_button_text') ?> <span class="text-muted fw-normal small">(2–4 <?= __('admin.words') ?>)</span></label>
                            <input id="hs_button_text" value="" class="form-control" type="text"
                                   placeholder="e.g. Shop Now" maxlength="30">
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><?= __('admin.slider_button_link') ?></label>
                            <input id="hs_button_link" value="" class="form-control" type="text"
                                   placeholder="https://... or leave empty for store category">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.slider_text_color') ?></label>
                            <input id="hs_text_color" value="#FFFFFF" class="form-control jscolor" data-jscolor type="text">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.slider_button_text_color') ?></label>
                            <input id="hs_button_text_color" value="#000000" class="form-control jscolor" data-jscolor type="text">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.slider_button_background_color') ?></label>
                            <input id="hs_button_bg_color" value="#FFFFFF" class="form-control jscolor" data-jscolor type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="slider-form-submit" class="btn btn-primary"><?= __('admin.save_settings') ?></span>
            </div>
        </div>
    </div>
</div>
<!-- modal slider-->


			
<!-- modal -->
<div class="modal fade features-form-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.manage_homepage_features') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                <input type="hidden" id="hf_index" value="">
                <input type="hidden" id="hf_feature_image" value="">
                
                <div class="mb-3">
                    <label class="form-label"><?= __('admin.feature_title') ?></label>
                    <input id="hf_title" value="" class="form-control" type="text">
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><?= __('admin.feature_subtitle') ?></label>
                    <input id="hf_sub_title" value="" class="form-control" type="text">
                </div>
                
                <div class="row border p-2 m-1">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.feature_image') ?></label>
                            <input type="file" name="store_hfimage" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img id="store_hfimage_container" style="width:100%;height:100px;display:none;" class="img-responsive">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="features-form-submit" class="btn btn-primary"><?= __('admin.save_settings') ?></span>
            </div>
        </div>
    </div>
</div>
<!-- modal -->


<!-- modal -->
<div class="modal fade bs-cards-form-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.manage_bottom_sections_cards') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                <input type="hidden" id="bsc_index" value="">
                <input type="hidden" id="bsc_feature_image" value="">
                
                <div class="mb-3">
                    <label class="form-label"><?= __('admin.feature_title') ?></label>
                    <input id="bsc_title" value="" class="form-control" type="text">
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><?= __('admin.feature_subtitle') ?></label>
                    <input id="bsc_sub_title" value="" class="form-control" type="text">
                </div>
                
                <div class="row border p-2 m-1">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.feature_image') ?></label>
                            <input type="file" name="store_bscimage" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img id="store_bscimage_container" style="width:100%;height:100px;display:none;" class="img-responsive">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= __('admin.slider_button_background_color') ?></label>
                    <input id="bsc_bg_color" value="#FFFFFF" class="form-control jscolor" data-jscolor type="text">
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= __('admin.banner_bottom_link') ?></label>
                    <input id="bsc_button_link" value="" class="form-control" type="text">
                </div>
                
                <div class="col-4">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.open-link-in-new-window') ?></label>
                        <div class="form-check form-switch">
                            <input id="bsc_link_target" class="form-check-input" type="checkbox" data-bs-on="On" data-bs-off="Off">
                            <label class="form-check-label" for="bsc_link_target">Toggle Switch</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <span id="bs-cards-form-submit" class="btn btn-primary"><?= __('admin.save_settings') ?></span>
            </div>
        </div>
    </div>
</div>
<!-- modal -->


<!-- modal custom page -->
<div class="modal fade custom-page-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.manage_custom_pages') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                <input type="hidden" id="cp_index" value="">
                <input type="hidden" id="cp_image" value="">
                <input type="hidden" id="cp_meta_id" value="">

                <div class="mb-3">
                    <label class="form-label"><?= __('admin.page_title') ?></label>
                    <input id="cp_title" value="" class="form-control" type="text">
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= __('admin.page_content') ?></label>
                    <textarea id="cp_content" class="form-control summernote"></textarea>
                </div>

                <div class="row border p-2 m-1">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.page_image') ?></label>
                            <input type="file" name="store_cpimage" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img id="store_cpimage_container" style="width:100%;height:100px;display:none;" class="img-responsive">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="custom-page-submit" class="btn btn-primary"><?= __('admin.save_settings') ?></span>
            </div>
        </div>
    </div>
</div>
<!-- modal custom page -->


<!-- modal social links -->
<div class="modal fade social-links-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.manage_social_links') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                <input type="hidden" id="sl_index" value="">
                <input type="hidden" id="sl_image" value="">

                <div class="mb-3">
                    <label class="form-label"><?= __('admin.link_title') ?></label>
                    <input id="sl_title" value="" class="form-control" type="text">
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= __('admin.link_url') ?></label>
                    <input id="sl_url" value="" class="form-control" type="text">
                </div>

                <div class="row border p-2 m-1">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.link_icon') ?></label>
                            <input type="file" name="store_slicon" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img id="store_slicon_container" style="width:100%;height:100px;display:none;" class="img-responsive">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="social-links-submit" class="btn btn-primary"><?= __('admin.save_settings') ?></span>
            </div>
        </div>
    </div>
</div>
<!-- modal social links -->


</form>


<!-- modal footer menu section -->
<div class="modal fade footer-menu-form-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.manage_footer_menu') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                <form class="form-horizontal" method="post" action="" id="footer-menu-form">
                    <input type="hidden" name="fm_index" value="">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.menu_title') ?></label>
                        <input name="fm_title" value="" class="form-control" type="text">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><?= __('admin.menu_type') ?></label>
                                <select class="form-control" name="fm_type">
                                    <option value="custom"><?= __('admin.custom_menu') ?></option>
                                    <option value="page"><?= __('admin.pages') ?></option>
                                    <option value="category"><?= __('admin.categories') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><?= __('admin.available_links') ?></label>
							<select class="form-control" name="fml_type" disabled>
								<option value="" disabled><?= __('admin.select_link') ?></option>
								<?php 
								echo '<option value="'.base_url('store/category/').'" data-fm_type="category" style="display:none">All Categories</option>';
								foreach($categories as $cat) {
									echo '<option value="'.base_url('store/category/').$cat['slug'].'" data-fm_type="category" style="display:none">'.$cat['name'].'</option>';
								}
								?>
								<?php 
								foreach($pages as $page) {
									echo '<option value="'.base_url('store/').$page['slug'].'" data-fm_type="page" style="display:none">'.$page['name'].'</option>';
								}
								?>
							</select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><?= __('admin.link_title') ?></label>
                                <input name="fml_title" value="" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <label class="form-label"><?= __('admin.link_url') ?></label>
                                <input name="fml_url" value="" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-md-1 pt-4">
                            <span class="btn btn-primary btn-add-link" style="margin-top:6px;">+ <?= __('admin.add') ?></span>
                        </div>
                    </div>
                    						<table id="menu_items_list" class="table w-100 align-middle">
                        <thead>
                            <tr>
                                <th><?= __('admin.title') ?></th>
                                <th><?= __('admin.url') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <span id="footer-menu-form-submit" class="btn btn-primary"><?= __('admin.save_settings') ?></span>
            </div>
        </div>
    </div>
</div>
<!-- modal footer menu section -->

		
		<script type="text/javascript">
			$('.store_status').on('change', function(){
				var checked = $(this).prop('checked');
				var menu_on_front = $('.menu_on_front').closest('.toggle');
				var menu_on_front_blank = $('.menu_on_front_blank').closest('.toggle');

				if (checked == true) {
					var status = 1;
					menu_on_front.css('pointer-events', '');
					menu_on_front_blank.css('pointer-events', '');
				}else{
					var status = 0;
        			menu_on_front.addClass('off');
        			menu_on_front.removeClass('btn-primary');
        			menu_on_front.addClass('btn-default');
        			menu_on_front.css('pointer-events', 'none');
        			menu_on_front_blank.addClass('off');
        			menu_on_front_blank.removeClass('btn-primary');
        			menu_on_front_blank.addClass('btn-default');
        			menu_on_front_blank.css('pointer-events', 'none');
				}

				$.ajax({
					url:'<?= base_url("admincontrol/update_store_status") ?>',
					type:'POST',
					dataType:'json',
					data:{'action':'update_store_status', status:status},
					success:function(json){
					},
				})
			});

			$('.menu_on_front').on('change', function(){
				var checked = $(this).prop('checked');

				if (checked == true) {
					var status = 1;
				}else{
					var status = 0;
				}

				$.ajax({
					url:'<?= base_url("admincontrol/update_store_menu_on_front") ?>',
					type:'POST',
					dataType:'json',
					data:{'action':'update_store_menu_on_front', status:status},
					success:function(json){
					},
				})
			});

			$('.menu_on_front_blank').on('change', function(){
				var checked = $(this).prop('checked');

				if (checked == true) {
					var status = 1;
				}else{
					var status = 0;
				}

				$.ajax({
					url:'<?= base_url("admincontrol/update_store_menu_on_front_blank") ?>',
					type:'POST',
					dataType:'json',
					data:{'action':'update_store_menu_on_front_blank', status:status},
					success:function(json){
					},
				})
			});


			$('#endtime,.datetime-picker').datetimepicker({
				format:'d-m-Y H:i',
				inline:true,
			});
			$('.setCustomTime').on('change', function(){
				$parents = $(this).parents(".form-group");
				$parents.find(".custom_time_container").hide();
				if($(this).prop("checked")){
					$parents.find(".custom_time_container").show();
				}
			});
			// Force remove any backdrops
			$('.modal-backdrop').remove();
			$('body').removeClass('modal-open');
			
			$(".btn-submit").on('click',function(evt){
				evt.preventDefault();	
				submit_store_setting();
			});


// Function to check if the input contains Google Maps embed code
function isValidGoogleMapsCode(inputCode) {
    if (inputCode === "") return true;  // Allowing empty textarea
    return inputCode.includes('https://www.google.com/maps/embed?') && inputCode.includes('<iframe');
}

// Function to validate email
function isValidEmail(email) {
    if (email === "") return true;  // Allowing empty email
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
}

// Main function to submit the form
function submit_store_setting(data = false) {
    let do_not_submit = false;
    let messages = [];

    // Existing validation logic for product commission
    let ps_product_commission_type = $('select[name="productsetting[product_commission_type]"]').val();
    let ps_product_commission = $('input[name="productsetting[product_commission]"]').val();
    let ps_product_ppc = $('input[name="productsetting[product_ppc]"]').val();
    let ps_product_noofpercommission = $('input[name="productsetting[product_noofpercommission]"]').val();

    if (ps_product_commission != "" && ps_product_ppc != "" && ps_product_noofpercommission != "") {
        if (ps_product_commission_type == "") {
            do_not_submit = true;
            messages.push('Product commission type should not be empty.');
        }
    }

    // Existing validation logic for form commission
    let fs_product_commission_type = $('select[name="formsetting[product_commission_type]"]').val();
    let fs_product_commission = $('input[name="formsetting[product_commission]"]').val();
    let fs_product_ppc = $('input[name="formsetting[product_ppc]"]').val();
    let fs_product_noofpercommission = $('input[name="formsetting[product_noofpercommission]"]').val();

    if (fs_product_commission != "" && fs_product_ppc != "" && fs_product_noofpercommission != "") {
        if (fs_product_commission_type == "") {
            do_not_submit = true;
            messages.push('Form commission type should not be empty.');
        }
    }

    // Google Maps Validation
    const mapCodeElement = document.getElementById('contact-us-map');
    const mapCode = mapCodeElement.value;

    if (mapCode && !isValidGoogleMapsCode(mapCode)) {
        do_not_submit = true;
        messages.push('Invalid Google Maps code.');
    }

    // Email Validation
    const emailElement = document.getElementById('store-email');
    const emailValue = emailElement.value;
    
    if (emailValue && !isValidEmail(emailValue)) {
        do_not_submit = true;
        messages.push('Invalid Email Address.');
    }

    if (do_not_submit) {
        const compiledMessages = messages.join('<br />');
        showPrintMessage(compiledMessages, 'error');
        return; // Stop the function execution
    }

    // If you reach this point, all validations have passed

    var formData = new FormData($("#setting-form")[0]);
    $(".btn-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

    // Override every Summernote field in FormData with the live editor content.
    // We use formData.set() AFTER building FormData so we bypass the hidden
    // textarea entirely — this is the only reliable way to capture an empty
    // (fully cleared) Summernote editor, because Summernote does not always
    // keep the underlying <textarea> in sync with the live .note-editable div.
    $('.summernote').each(function() {
        var fieldName = $(this).attr('name');
        if (!fieldName) return;
        var content = '';
        try { content = $(this).summernote('code') || ''; } catch(e) {}
        // Summernote returns <p><br></p> for a visually-empty editor — treat as ''
        if (/^(<p>(<br>|<br\s?\/>)<\/p>\s*)+$/.test(content.trim())) content = '';
        formData.set(fieldName, content);
    });

    if (data != null) {
        formData.append(data.name, data.value);
    }

    formData = formDataFilter(formData);

    $this = $("#setting-form");
    $.ajax({
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        success: function (result) {
            $(".btn-submit").prop('disabled', false).html($(".btn-submit").data('original-text') || 'Submit');
            $(".alert-dismissable").remove();
            $this.find(".has-error").removeClass("has-error");
            $this.find(".is-invalid").removeClass("is-invalid");
            $this.find("span.text-danger").remove();

            if (result['success']) {
                re_render_listings(result);
                showPrintMessage(result['success'], 'success');

                $('.formsetting_error').text("");
                $('.productsetting_error').text("");
            }
            
            if (result['errors']) {
                $.each(result['errors'], function (i, j) {
                    $ele = $this.find('[name="' + i + '"]');
                    if (!$ele.length) {
                        $ele = $this.find('.' + i);
                    }
                    if ($ele) {
                        $ele.addClass("is-invalid");
                        $ele.parents(".form-group").addClass("has-error");
                        $ele.after("<span class='d-block text-danger'>" + j + "</span>");
                    }
                });

                errors = result['errors'];
                $('.formsetting_error').text(errors['formsetting_recursion_custom_time']);
                $('.productsetting_error').text(errors['productsetting_recursion_custom_time']);
            }
        },
    });
}




			function re_render_listings(result) {
				if(result['homepage_slider']) {
					$('#homepage_sliders_list').empty();
					if(result['homepage_slider'].length > 0) {
						for (let index = 0; index < result['homepage_slider'].length; index++) {
							let element = result['homepage_slider'][index];
							let image_src = (element.slider_background_image != null && element.slider_background_image != "") ? '<?= base_url('assets/images/site/') ?>'+element.slider_background_image : '<?= base_url('assets/store/default/img/banner.png'); ?>';
						let new_row = `<tr>
						<td class="ps-3">`+element.index+`</td>
						<td class="ts-title-cell">
						<span class="ts-title">`+element.title+`</span>
						<small class="ts-subtitle">`+element.sub_title+`</small>
						</td>
						<td class="d-none d-md-table-cell">
						<img src="`+image_src+`" class="ts-slider-img">
						</td>
						<td class="ts-action-cell">
						<input type="hidden" name="store[homepage_slider][edited][]" value="0">
						<input type="hidden" name="store[homepage_slider][index][]" value="`+element.index+`">
						<input type="hidden" name="store[homepage_slider][title][]" value="`+element.title+`">
						<input type="hidden" name="store[homepage_slider][sub_title][]" value="`+element.sub_title+`">
						<textarea name="store[homepage_slider][content][]" class="ts-data-textarea">`+element.content+`</textarea>
						<input type="hidden" name="store[homepage_slider][slider_background_image][]" value="`+element.slider_background_image+`">
						<input type="hidden" name="store[homepage_slider][button_text][]" value="`+element.button_text+`">
						<input type="hidden" name="store[homepage_slider][button_link][]" value="`+element.button_link+`">
						<input type="hidden" name="store[homepage_slider][slider_text_color][]" value="`+element.slider_text_color+`">
						<input type="hidden" name="store[homepage_slider][button_text_color][]" value="`+element.button_text_color+`">
						<input type="hidden" name="store[homepage_slider][button_bg_color][]" value="`+element.button_bg_color+`">
						<button type="button" class="btn btn-sm btn-outline-secondary btn-slider-form-modal-edit me-1"><i class="bi bi-pencil"></i></button>
						<button type="button" class="btn btn-sm btn-outline-danger remove-slider-btn"><i class="bi bi-trash"></i></button>
						</td>
						</tr>`;
							$('#homepage_sliders_list').append(new_row);
						}
					} else {
						$('#homepage_sliders_list').append(`<h6 class='text-center text-muted'>`+'<?= __('admin.sliders_not_available') ?>'+`</h6>`);
					}
					$('.slider-form-modal').modal('hide');
					$("#slider-form-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
				}

				if(result['homepage_features']) {
					$('#homepage_features_list').empty();
					if(result['homepage_features'].length > 0) {
						for (let index = 0; index < result['homepage_features'].length; index++) {
							let element = result['homepage_features'][index];
							let image_src = (element.feature_image != null && element.feature_image != "") ? '<?= base_url('assets/images/site/') ?>'+element.feature_image : '<?= base_url('assets/store/default/img/banner.png'); ?>';
						let new_row = `<tr>
						<td class="ps-3">`+element.index+`</td>
						<td class="ts-title-cell">
						<span class="ts-title">`+element.title+`</span>
						<small class="ts-subtitle">`+element.sub_title+`</small>
						</td>
						<td class="d-none d-md-table-cell">
						<img src="`+image_src+`" class="ts-feature-img">
						</td>
						<td class="ts-action-cell">
						<input type="hidden" name="store[homepage_features][edited][]" value="0">
						<input type="hidden" name="store[homepage_features][index][]" value="`+element.index+`">
						<input type="hidden" name="store[homepage_features][title][]" value="`+element.title+`">
						<input type="hidden" name="store[homepage_features][sub_title][]" value="`+element.sub_title+`">
						<input type="hidden" name="store[homepage_features][feature_image][]" value="`+element.feature_image+`">
						<button type="button" class="btn btn-sm btn-outline-secondary btn-features-form-modal-edit me-1"><i class="bi bi-pencil"></i></button>
						<button type="button" class="btn btn-sm btn-outline-danger remove-features-btn"><i class="bi bi-trash"></i></button>
						</td>
						</tr>`;
							$('#homepage_features_list').append(new_row);
						}
					} else {
						$('#homepage_features_list').append(`<h6 class='text-center text-muted'>`+'<?= __('admin.features_not_available') ?>'+`</h6>`);
					}
					$('.features-form-modal').modal('hide');
					$("#features-form-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
				}
				if(result['hbanimage']){
					
					$('.homepage-bottom-sec-form-modal').modal('hide');
					$("#homepage-bottom-baner-form-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
					$("#hbs_bottom_banner_image").val('<?= base_url('assets/images/site/') ?>'+result['hbanimage']);
				}
				

				if(result['bs_cards']) {
					$('#bs_cards_list').empty();
					if(result['bs_cards'].length > 0) {
						for (let index = 0; index < result['bs_cards'].length; index++) {
							let element = result['bs_cards'][index];
							let image_src = (element.feature_image != null && element.feature_image != "") ? '<?= base_url('assets/images/site/') ?>'+element.feature_image : '<?= base_url('assets/store/default/img/banner.png'); ?>';
						let new_row = `<tr>
						<td class="ps-3">`+element.index+`</td>
						<td class="ts-title-cell">
						<span class="ts-title">`+element.title+`</span>
						<small class="ts-subtitle">`+element.sub_title+`</small>
						</td>
						<td class="d-none d-md-table-cell">
						<img src="`+image_src+`" class="ts-card-img">
						</td>
						<td class="ts-action-cell">
						<input type="hidden" name="store[bs_cards][edited][]" value="0">
						<input type="hidden" name="store[bs_cards][index][]" value="`+element.index+`">
						<input type="hidden" name="store[bs_cards][title][]" value="`+element.title+`">
						<input type="hidden" name="store[bs_cards][sub_title][]" value="`+element.sub_title+`">
						<input type="hidden" name="store[bs_cards][feature_image][]" value="`+element.feature_image+`">
						<input type="hidden" name="store[bs_cards][bg_color][]" value="`+element.bg_color+`">
						<input type="hidden" name="store[bs_cards][button_link][]" value="`+element.button_link+`">
						<input type="hidden" name="store[bs_cards][link_target][]" value="`+element.link_target+`">
						<button type="button" class="btn btn-sm btn-outline-secondary btn-bs-cards-form-modal-edit me-1"><i class="bi bi-pencil"></i></button>
						<button type="button" class="btn btn-sm btn-outline-danger remove-bs-cards-btn"><i class="bi bi-trash"></i></button>
						</td>
						</tr>`;
							$('#bs_cards_list').append(new_row);
						}
					} else {
						$('#bs_cards_list').append(`<h6 class='text-center text-muted'>`+'<?= __('admin.cards_not_available') ?>'+`</h6>`);
					}
					$('.bs-cards-form-modal').modal('hide');
					$("#bs-cards-form-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
				}

				if(result['footer_menu']) {
					$('#footer_menu_list').empty();
					if(result['footer_menu'].length > 0) {
						for (let index = 0; index < result['footer_menu'].length; index++) {
							let element = result['footer_menu'][index];

							let linksInputs = "";
							let linksTitle = "";

							let letpreIndex = element.index - 1;

							element.links.forEach(link => {
								linksTitle += (linksInputs == "") ? link.title : ", "+link.title;
								linksInputs += `<input type="hidden" name="store[footer_menu][links][`+letpreIndex+`][title][]" value="`+link.title+`"><input type="hidden" name="store[footer_menu][links][`+letpreIndex+`][url][]" value="`+link.url+`"><input type="hidden" name="store[footer_menu][links][`+letpreIndex+`][type][]" value="`+link.type+`">`;
							});

							let new_row = `<tr><td scope="row">`+element.index+`</td><td scope="row">`+element.title+`</td><td scope="row">`+linksTitle+`</td><td style="width: 87px; padding: 5px 0px !important;"><input type="hidden" name="store[footer_menu][index][`+letpreIndex+`]" value="`+element.index+`"><input type="hidden" name="store[footer_menu][title][`+letpreIndex+`]" value="`+element.title+`">`;
							new_row += linksInputs;
							new_row += `<button data-letpreindex="`+letpreIndex+`" type="button" class="btn btn-primary btn-footer-menu-form-modal-edit"><i class="fa fa-pencil"></i></button>
							<button type="button" class="btn btn-danger remove-footer-menu"><i class="fa fa-trash"></i></button>
							</td>
							</tr>`;

							$('#footer_menu_list').append(new_row);
						}
					} else {
						$('#footer_menu_list').append(`<h6 class='text-center text-muted'>`+'<?= __('admin.menu_not_available') ?>'+`</h6>`);
					}
					$('.footer-menu-form-modal').modal('hide');
					$("#footer-menu-form-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
				}

				if(result['custom_page']) {
					$('#custom_page_list').empty();
					if(result['custom_page'].length > 0) {
						for (let index = 0; index < result['custom_page'].length; index++) {
							let element = result['custom_page'][index];
							let image_src = (element.image != null && element.image != "") ? '<?= base_url('assets/images/site/') ?>'+element.image : '<?= base_url('assets/store/default/img/banner.png'); ?>';

							let new_row = `<tr>
							<td scope="row">`+element.title+`</td>
							<td scope="row">`+element.slug+`</td>
							<td style="width: 200px;"><img style="width: 100px; height: 50px;" src="`+image_src+`" class='img-responsive'></td>
							<td style="width: 87px; padding: 5px 0px !important;">
							<input type="hidden" name="store[custom_page][edited][]" value="0">
							<input type="hidden" name="store[custom_page][index][]" value="`+element.index+`">
							<input type="hidden" name="store[custom_page][title][]" value="`+element.title+`">
							<input type="hidden" name="store[custom_page][slug][]" value="`+element.slug+`">
							<input type="hidden" name="store[custom_page][image][]" value="`+element.image+`">
							<input type="hidden" name="store[custom_page][meta_id][]" value="`+element.meta_id+`">
							<textarea name="store[custom_page][content][]" style="display:none">`+element.content+`</textarea>
							<button type="button" class="btn btn-primary btn-custom-page-modal-form-edit"><i class="fa fa-pencil"></i></button>
							<button type="button" class="btn btn-danger remove-custom-page"><i class="fa fa-trash"></i></button>
							</td>
							</tr>`;

							$('#custom_page_list').append(new_row);
						}
					} else {
						$('#custom_page_list').append(`<h6 class='text-center text-muted'>`+'<?= __('admin.pages_not_available') ?>'+`</h6>`);
					}
					$('.custom-page-modal').modal('hide');
					$("#custom-page-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
				}

				if(result['social_links']) {
					$('#social_links_list').empty();
					if(result['social_links'].length > 0) {
						for (let index = 0; index < result['social_links'].length; index++) {
							let element = result['social_links'][index];
							let image_src = (element.image != null && element.image != "") ? '<?= base_url('assets/images/site/') ?>'+element.image : '<?= base_url('assets/store/default/img/banner.png'); ?>';

							let new_row = `<tr>
							<td scope="row">`+element.title+`</td>
							<td scope="row">`+element.url+`</td>
							<td style="width: 200px; text-align:right;">
							<img style="width: 50px; height: 50px; background-color:grey;" src="`+image_src+`" class='img-responsive'>
							</td>
							<td style="width: 87px; padding: 5px 0px !important;">
							<input type="hidden" name="store[social_links][index][]" value="`+element.index+`">
							<input type="hidden" name="store[social_links][edited][]" value="0">
							<input type="hidden" name="store[social_links][title][]" value="`+element.title+`">
							<input type="hidden" name="store[social_links][url][]" value="`+element.url+`">
							<input type="hidden" name="store[social_links][image][]" value="`+element.image+`">
							<button type="button" class="btn btn-primary btn-social-links-form-edit"><i class="fa fa-pencil"></i></button>
							<button type="button" class="btn btn-danger remove-social-links"><i class="fa fa-trash"></i></button>
							</td>
							</tr>
							`;

							$('#social_links_list').append(new_row);
						}
					} else {
						$('#social_links_list').append(`<h6 class='text-center text-muted'>`+'<?= __('admin.pages_not_available') ?>'+`</h6>`);
					}
					$('.social-links-modal').modal('hide');
					$("#social-links-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
				}

				if(result['custom_page_for_menu']) {
					$('select[name="fml_type"] option[data-fm_type="page"]').remove();
					for (let index = 0; index < result['custom_page_for_menu'].length; index++) {
						const element = result['custom_page_for_menu'][index];
						$('select[name="fml_type"]').append(`<option value="<?= base_url('store/') ?>`+result['custom_page_for_menu'][index]['slug']+`" data-fm_type="page" style="display:none">`+result['custom_page_for_menu'][index]['name']+`</option>`);
					}
				}
			}

			$(document).on('change', '#recursion_type, #form_recursion_type', function(){
				var recursion_type = $(this).val();
				if( recursion_type == 'custom_time' ){
					$(this).parent().find('.custom_time').show();
				}else{
					$(this).parent().find('.custom_time').hide();
				}
			});

			$(document).on('change', '.recur_day, .recur_hour, .recur_minute', function(){
				var days = $(this).parents('.custom_time').find('.recur_day').val();
				var hours = $(this).parents('.custom_time').find('.recur_hour').val();
				var minutes = $(this).parents('.custom_time').find('.recur_minute').val();
				var total_minutes;	

				total_hours = parseInt(days*24) + parseInt(hours);
				total_minutes = parseInt(total_hours*60) + parseInt(minutes);
				$(this).parents('.custom_time').find('.recursion_custom_time').val(total_minutes);
			});

			$(document).on('click', '.btn-add-comment', function(){
				var comment_row_count = $(".comment-titles table tbody tr").length;

				var html ='';
				html += '<tr>';
				html += '<td>';
				html += '<input type="text" name="order_comment[title]['+(comment_row_count + 1)+']" class="form-control" placeholder="<?= __('admin.comment_title') ?>" />';
				html += '</td>';
				html += '<td class="text-right">';
				html += '<button type="button" class="btn btn-danger" onclick="$(this).closest(\'tr\').remove()"><i class="fa fa-trash"></i></button>';
				html += '</td>';
				html += '</tr>';

				$('.comment-titles table tbody').append(html);
			});
		</script>

<script>
	$(document).on('click', '.btn-add-more', function(){
	    let count = $('#notifications-list .row').length;
	    $('#notifications-list').append(`
	        <div class="row align-items-center mb-2">
	            <div class="col-md-11">
	                <div class="form-group">
	                    <label class="control-label">Notification `+(count+1)+`</label>
	                    <input name="store[notification][]" class="form-control" type="text" value="">
	                </div>
	            </div>
	            <div class="col-md-1">
	                <button type="button" class="btn btn-danger btn-md remove-notification-btn"><i class="fa fa-trash"></i></button>
	            </div>
	        </div>
	    `);
	});


	$(document).on('click', '.remove-notification-btn', function(){
	    $(this).closest('.row').remove();

	    $('#notifications-list .row').each(function( index ) {
	        $(this).find('.control-label').text('Notification '+(index+1));
	    });

	    let count = $('#notifications-list .row').length;

	    if (count == 0) {
	        $('#notifications-list').append(`
	            <div class="row align-items-center mb-2">
	                <div class="col-md-11">
	                    <div class="form-group">
	                        <label class="control-label">Notification `+(count+1)+`</label>
	                        <input name="store[notification][]" class="form-control" type="text" value="">
	                    </div>
	                </div>
	                <div class="col-md-1">
	                    <button type="button" class="btn btn-danger btn-md remove-notification-btn"><i class="fa fa-trash"></i></button>
	                </div>
	            </div>
	        `);
	    }
	});


		$(document).on('click', '.remove-slider-btn', function(){
			$(this).parent().parent().remove();
			$('#homepage_sliders_list tr').each(function( index ) {
				$(this).find('td:first-child').text((index+1));
				$(this).find('input[name="store[homepage_slider][index][]"]').val((index+1));
			});
		});

		$(document).on('click', '.remove-features-btn', function(){
			$(this).parent().parent().remove();
			$('#homepage_features_list tr').each(function( index ) {
				$(this).find('td:first-child').text((index+1));
				$(this).find('input[name="store[homepage_features][index][]"]').val((index+1));
			});
		});

		$(document).on('click', '.remove-bs-cards-btn', function(){
			$(this).parent().parent().remove();
			$('#bs_cards_list tr').each(function( index ) {
				$(this).find('td:first-child').text((index+1));
				$(this).find('input[name="store[bs_cards][index][]"]').val((index+1));
			});
		});

		$(document).on('click', '.btn-slider-form-modal', function(){
			let index = $('#homepage_sliders_list tr:not(.empty)').length + 1;
			let modal = $('.slider-form-modal');
			modal.find('#hs_index').val(index);
			modal.find('#hs_title').val('');
			modal.find('#hs_sub_title').val('');
			modal.find('#hs_content').val('');
			modal.find('#hs_button_text').val('');
			modal.find('#hs_button_link').val('');
			modal.find('#hs_text_color').val('#FFFFFF');
			modal.find('#hs_button_text_color').val('#000000');
			modal.find('#hs_button_bg_color').val('#FFFFFF');
			document.querySelector('#hs_text_color').jscolor.fromString('#FFFFFF');
			document.querySelector('#hs_button_text_color').jscolor.fromString('#000000');
			document.querySelector('#hs_button_bg_color').jscolor.fromString('#FFFFFF');
			modal.find('#hs_slider_background_image').val('');
			modal.find('input[name="store_hsbackgroundimage"]').val('');
			modal.find('#store_hsbackgroundimage_container').attr('src', '<?= base_url('assets/store/default/img/banner.png'); ?>');
			modal.modal('show');
			modal.modal('show');
		});

		$(document).on('click', '.btn-slider-form-modal-edit', function(){
			let data = $(this).parent();
			let modal = $('.slider-form-modal');
			modal.find('#hs_index').val(data.find('input[name="store[homepage_slider][index][]"]').val());
			modal.find('#hs_title').val(data.find('input[name="store[homepage_slider][title][]"]').val());
			modal.find('#hs_sub_title').val(data.find('input[name="store[homepage_slider][sub_title][]"]').val());
			modal.find('#hs_content').val(data.find('textarea[name="store[homepage_slider][content][]"]').val());
			modal.find('#hs_button_text').val(data.find('input[name="store[homepage_slider][button_text][]"]').val());
			modal.find('#hs_button_link').val(data.find('input[name="store[homepage_slider][button_link][]"]').val());
			modal.find('#hs_text_color').val(data.find('input[name="store[homepage_slider][slider_text_color][]"]').val());
			modal.find('#hs_button_text_color').val(data.find('input[name="store[homepage_slider][button_text_color][]"]').val());
			modal.find('#hs_button_bg_color').val(data.find('input[name="store[homepage_slider][button_bg_color][]"]').val());
			document.querySelector('#hs_text_color').jscolor.fromString(data.find('input[name="store[homepage_slider][slider_text_color][]"]').val());
			document.querySelector('#hs_button_text_color').jscolor.fromString(data.find('input[name="store[homepage_slider][button_text_color][]"]').val());
			document.querySelector('#hs_button_bg_color').jscolor.fromString(data.find('input[name="store[homepage_slider][button_bg_color][]"]').val());
			modal.find('input[name="store_hsbackgroundimage"]').val('');
			modal.find('#hs_slider_background_image').val(data.find('input[name="store[homepage_slider][slider_background_image][]"]').val());
			modal.find('#store_hsbackgroundimage_container').attr('src', $(this).parent().parent().find('img').attr('src'));
			modal.modal('show');
		});

		$(document).on('click', '#slider-form-submit', function(){
			let modal = $('.slider-form-modal');
			let image_src = $('#store_hsbackgroundimage_container').attr('src');

			if(modal.find('#hs_title').val() == null || modal.find('#hs_title').val() == "") {
				alert('<?= __('admin.title_should_not_be_empty') ?>');
			} else {

				$("input[name='store[homepage_slider][edited][]']").each(function( index ) {
					$(this).val(0);
				});


			let new_row = `<tr>
			<td class="ps-3">`+modal.find('#hs_index').val()+`</td>
			<td class="ts-title-cell">
			<span class="ts-title">`+modal.find('#hs_title').val()+`</span>
			<small class="ts-subtitle">`+modal.find('#hs_sub_title').val()+`</small>
			</td>
			<td class="d-none d-md-table-cell">
			<img src="`+image_src+`" class="ts-slider-img">
			</td>
			<td class="ts-action-cell">
			<input type="hidden" name="store[homepage_slider][edited][]" value="1">
			<input type="hidden" name="store[homepage_slider][index][]" value="`+modal.find('#hs_index').val()+`">
			<input type="hidden" name="store[homepage_slider][title][]" value="`+modal.find('#hs_title').val()+`">
			<input type="hidden" name="store[homepage_slider][sub_title][]" value="`+modal.find('#hs_sub_title').val()+`">
			<textarea name="store[homepage_slider][content][]" class="ts-data-textarea">`+modal.find('#hs_content').val()+`</textarea>
			<input type="hidden" name="store[homepage_slider][slider_background_image][]" value="`+modal.find('#hs_slider_background_image').val()+`">
			<input type="hidden" name="store[homepage_slider][button_text][]" value="`+modal.find('#hs_button_text').val()+`">
			<input type="hidden" name="store[homepage_slider][button_link][]" value="`+modal.find('#hs_button_link').val()+`">
			<input type="hidden" name="store[homepage_slider][slider_text_color][]" value="`+modal.find('#hs_text_color').val()+`">
			<input type="hidden" name="store[homepage_slider][button_text_color][]" value="`+modal.find('#hs_button_text_color').val()+`">
			<input type="hidden" name="store[homepage_slider][button_bg_color][]" value="`+modal.find('#hs_button_bg_color').val()+`">
			<button type="button" class="btn btn-sm btn-outline-secondary btn-slider-form-modal-edit me-1"><i class="bi bi-pencil"></i></button>
			<button type="button" class="btn btn-sm btn-outline-danger remove-slider-btn"><i class="bi bi-trash"></i></button>
			</td>
			</tr>`;

				$('#homepage_sliders_list tr:nth-child('+(modal.find('#hs_index').val())+')').remove();

				if(modal.find('#hs_index').val() > $('#homepage_sliders_list tr:not(.empty)').length) {
					$('#homepage_sliders_list').append(new_row);
				} else {
					$('#homepage_sliders_list tr:nth-child('+(modal.find('#hs_index').val())+')').before(new_row);
				}
				submit_store_setting({name:'return', value:'slider'});
				$("#slider-form-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
			}
		});

		$(document).on('click', '.btn-homepage-bottom-sec-form-modal', function(){
			let index = $('#homepage_features_list tr:not(.empty)').length + 1;
			let modal = $('.homepage-bottom-sec-form-modal');
			let bannerimage = $('#hbs_bottom_banner_image').val();;
			modal.find('#hbs_index').val(index);
			modal.find('#hbs_bottom_banner_image').val('');
			modal.find('input[name="store_hbanimage"]').val('');
			if(bannerimage == ""){
				modal.find('#store_hbanimage_container').attr('src', '<?= base_url('assets/store/default/img/banner.png'); ?>');
			}else{
				modal.find('#store_hbanimage_container').attr('src', bannerimage);
			}
			
			modal.modal('show');
		});

		$(document).on('click', '.btn-features-form-modal', function(){
			let index = $('#homepage_features_list tr:not(.empty)').length + 1;
			let modal = $('.features-form-modal');
			modal.find('#hf_index').val(index);
			modal.find('#hf_title').val('');
			modal.find('#hf_sub_title').val('');
			modal.find('#hf_feature_image').val('');
			modal.find('input[name="store_hfimage"]').val('');
			modal.find('#store_hfimage_container').attr('src', '<?= base_url('assets/store/default/img/banner.png'); ?>').show();
			modal.modal('show');
		});

		$(document).on('click', '.btn-features-form-modal-edit', function(){
			let data = $(this).parent();
			let modal = $('.features-form-modal');
			modal.find('#hf_index').val(data.find('input[name="store[homepage_features][index][]"]').val());
			modal.find('#hf_title').val(data.find('input[name="store[homepage_features][title][]"]').val());
			modal.find('#hf_sub_title').val(data.find('input[name="store[homepage_features][sub_title][]"]').val());
			modal.find('#hf_feature_image').val(data.find('input[name="store[homepage_features][feature_image][]"]').val());
			modal.find('input[name="store_hfimage"]').val('');
			modal.find('#store_hfimage_container').attr('src', $(this).parent().parent().find('img').attr('src')).show();
			modal.modal('show');
		});

		$(document).on('click', '#homepage-bottom-baner-form-submit', function(){
			let modal = $('.homepage-bottom-sec-form-modal');
			let image_src = $('#store_hbanimage_container').attr('src');

				submit_store_setting({name:'return', value:'hbanimage'});
				$("#homepage-bottom-baner-form-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
			
		});

		$(document).on('click', '#features-form-submit', function(){
			let modal = $('.features-form-modal');
			let image_src = $('#store_hfimage_container').attr('src');

			if(modal.find('#hf_title').val() == null || modal.find('#hf_title').val() == "") {
				alert('<?= __('admin.title_should_not_be_empty') ?>');
			} else {

				$("input[name='store[homepage_features][edited][]']").each(function( index ) {
					$(this).val(0);
				});

			let new_row = `
			<tr>
			<td class="ps-3">`+modal.find('#hf_index').val()+`</td>
			<td class="ts-title-cell">
			<span class="ts-title">`+modal.find('#hf_title').val()+`</span>
			<small class="ts-subtitle">`+modal.find('#hf_sub_title').val()+`</small>
			</td>
			<td class="d-none d-md-table-cell">
			<img src="`+image_src+`" class="ts-feature-img">
			</td>
			<td class="ts-action-cell">
			<input type="hidden" name="store[homepage_features][edited][]" value="1">
			<input type="hidden" name="store[homepage_features][index][]" value="`+modal.find('#hf_index').val()+`">
			<input type="hidden" name="store[homepage_features][title][]" value="`+modal.find('#hf_title').val()+`">
			<input type="hidden" name="store[homepage_features][sub_title][]" value="`+modal.find('#hf_sub_title').val()+`">
			<input type="hidden" name="store[homepage_features][feature_image][]" value="`+modal.find('#hf_feature_image').val()+`">
			<button type="button" class="btn btn-sm btn-outline-secondary btn-features-form-modal-edit me-1"><i class="bi bi-pencil"></i></button>
			<button type="button" class="btn btn-sm btn-outline-danger remove-features-btn"><i class="bi bi-trash"></i></button>
			</td>
			</tr>
			`;

				$('#homepage_features_list tr:nth-child('+(modal.find('#hf_index').val())+')').remove();

				if(modal.find('#hf_index').val() > $('#homepage_features_list tr:not(.empty)').length) {
					$('#homepage_features_list').append(new_row);
				} else {
					$('#homepage_features_list tr:nth-child('+(modal.find('#hf_index').val())+')').before(new_row);
				}

				submit_store_setting({name:'return', value:'features'});
				$("#features-form-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
			}
		});

		$(document).on('click', '.btn-bs-cards-form-modal', function(){
			let index = $('#bs_cards_list tr:not(.empty)').length + 1;
			let modal = $('.bs-cards-form-modal');
			modal.find('#bsc_index').val(index);
			modal.find('#bsc_title').val('');
			modal.find('#bsc_sub_title').val('');
			modal.find('#bsc_feature_image').val('');
			modal.find('#bsc_bg_color').val('#FFFFFF');
			modal.find('#bsc_button_link').val('');
			//modal.find('#bsc_link_target').bootstrapToggle('off'); 
			document.querySelector('#bsc_bg_color').jscolor.fromString('#FFFFFF');
			modal.find('#store_bscimage').val('');
			modal.find('#store_bscimage_container').attr('src', '<?= base_url('assets/store/default/img/banner.png'); ?>').show();
			modal.modal('show');
			$('.bs-cards-form-modal').modal('show');
		});

		$(document).on('click', '.btn-bs-cards-form-modal-edit', function(){
			let data = $(this).parent();
			let modal = $('.bs-cards-form-modal');
			modal.find('#bsc_index').val(data.find('input[name="store[bs_cards][index][]"]').val());
			modal.find('#bsc_title').val(data.find('input[name="store[bs_cards][title][]"]').val());
			modal.find('#bsc_sub_title').val(data.find('input[name="store[bs_cards][sub_title][]"]').val());
			modal.find('#bsc_button_link').val(data.find('input[name="store[bs_cards][button_link][]"]').val());

			if(data.find('input[name="store[bs_cards][link_target][]"]').val()=='true') 
			{
				$('#bsc_link_target').prop('checked', true);
			}
		 	else
				$('#bsc_link_target').prop('checked', false);

			
			modal.find('#bsc_feature_image').val(data.find('input[name="store[bs_cards][feature_image][]"]').val());
			modal.find('#bsc_bg_color').val(data.find('input[name="store[bs_cards][bg_color][]"]').val());
			document.querySelector('#bsc_bg_color').jscolor.fromString(data.find('input[name="store[bs_cards][bg_color][]"]').val());
			modal.find('#store_bscimage').val('');
			modal.find('#store_bscimage_container').attr('src', $(this).parent().parent().find('img').attr('src')).show();
			modal.modal('show');
		});

		$(document).on('click', '#bs-cards-form-submit', function(){
			let modal = $('.bs-cards-form-modal');
			let image_src = $('#store_bscimage_container').attr('src');

			if(modal.find('#bsc_title').val() == null || modal.find('#hs_title').val() == "") {
				alert('<?= __('admin.title_should_not_be_empty') ?>');
			} else {

				$("input[name='store[bs_cards][edited][]']").each(function( index ) {
					$(this).val(0);
				});

			let new_row = `
			<tr>
			<td class="ps-3">`+modal.find('#bsc_index').val()+`</td>
			<td class="ts-title-cell">
			<span class="ts-title">`+modal.find('#bsc_title').val()+`</span>
			<small class="ts-subtitle">`+modal.find('#bsc_sub_title').val()+`</small>
			</td>
			<td class="d-none d-md-table-cell">
			<img src="`+image_src+`" class="ts-card-img">
			</td>
			<td class="ts-action-cell">
			<input type="hidden" name="store[bs_cards][edited][]" value="1">
			<input type="hidden" name="store[bs_cards][index][]" value="`+modal.find('#bsc_index').val()+`">
			<input type="hidden" name="store[bs_cards][title][]" value="`+modal.find('#bsc_title').val()+`">
			<input type="hidden" name="store[bs_cards][sub_title][]" value="`+modal.find('#bsc_sub_title').val()+`">
			<input type="hidden" name="store[bs_cards][feature_image][]" value="`+modal.find('#bsc_feature_image').val()+`">
			<input type="hidden" name="store[bs_cards][bg_color][]" value="`+modal.find('#bsc_bg_color').val()+`">
			<input type="hidden" name="store[bs_cards][button_link][]" value="`+modal.find('#bsc_button_link').val()+`">
			<input type="hidden" name="store[bs_cards][link_target][]" value="`+modal.find('#bsc_link_target').prop('checked')+`">
			<button type="button" class="btn btn-sm btn-outline-secondary btn-bs-cards-form-modal-edit me-1"><i class="bi bi-pencil"></i></button>
			<button type="button" class="btn btn-sm btn-outline-danger remove-bs-cards-btn"><i class="bi bi-trash"></i></button>
			</td>
			</tr>
			`;
				 
				 
				$('#bs_cards_list tr:nth-child('+(modal.find('#bsc_index').val())+')').remove();

				if(modal.find('#bsc_index').val() > $('#bs_cards_list tr:not(.empty)').length) {
					$('#bs_cards_list').append(new_row);
				} else {
					$('#bs_cards_list tr:nth-child('+(modal.find('#bsc_index').val())+')').before(new_row);
				}

				submit_store_setting({name:'return', value:'bs_cards'});
				$("#bs-cards-form-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
				
			}
		});


		$(document).on('change', '.slider-form-modal input[name="store_hsbackgroundimage"]', function() {
			read_url(this,'store_hsbackgroundimage_container');
		});

		$(document).on('change', '.features-form-modal input[name="store_hfimage"]', function() {
			read_url(this,'store_hfimage_container');
		});

		$(document).on('change', 'input[name="store_hbanimage"]', function() {
			read_url(this,'store_hbanimage_container');
		});
		$(document).on('change', '.bs-cards-form-modal input[name="store_bscimage"]', function() {
			read_url(this,'store_bscimage_container');
		});

		$(document).on('change', '#pages_menu_section input[name="store_aboutimage"]', function() {
			read_url(this,'store_aboutimage_container');
		});

		$(document).on('change', '#pages_menu_section input[name="store_classifiedbannerimg"]', function() {
			read_url(this,'store_classifiedbannerimg_container');
		});

		$(document).on('change', '#pages_menu_section input[name="store_contactimage"]', function() {
			read_url(this,'store_contactimage_container');
		});

		$(document).on('change', '#pages_menu_section input[name="store_policyimage"]', function() {
			read_url(this,'store_policyimage_container');
		});

		$(document).on('change', 'select[name="fm_type"]', function(){
			let selectBox = $(this);
			if(selectBox.val() == 'custom') {
				$('select[name="fml_type"] option[data-fm_type="category"]').hide();
				$('select[name="fml_type"] option[data-fm_type="page"]').hide();
				$('select[name="fml_type"]').val('');
				$('select[name="fml_type"]').attr('disabled', true);
			} else if(selectBox.val() == 'category') {
				$('select[name="fml_type"] option[data-fm_type="category"]').show();
				$('select[name="fml_type"] option[data-fm_type="page"]').hide();
				$('select[name="fml_type"]').val('');
				$('select[name="fml_type"]').attr('disabled', false);
			} else {
				$('select[name="fml_type"] option[data-fm_type="category"]').hide();
				$('select[name="fml_type"] option[data-fm_type="page"]').show();
				$('select[name="fml_type"]').val('');
				$('select[name="fml_type"]').attr('disabled', false);
			}

			$('.footer-menu-form-modal input[name="fml_title"]').val('');
			$('.footer-menu-form-modal input[name="fml_url"]').val('');
		});

		$(document).on('change', 'select[name="fml_type"]', function(){
			let selectBox = $(this);
			if(selectBox.val() != "") {
				$('.footer-menu-form-modal input[name="fml_title"]').val(selectBox.find('option:selected').text());
				$('.footer-menu-form-modal input[name="fml_url"]').val(selectBox.find('option:selected').val());
			}
		});

		$(document).on('click', '.btn-add-link', function(){
			let link_type = $('.footer-menu-form-modal select[name="fm_type"]').val();
			let link_title = $('.footer-menu-form-modal input[name="fml_title"]').val();
			let link_url = $('.footer-menu-form-modal input[name="fml_url"]').val();
			if(link_title != "" && link_title != null && link_url != null && link_url != null) {
				$('#menu_items_list tbody').append(`<tr>
					<td scope="row">`+link_title+`</td>
					<td scope="row">`+link_url+`</td>
					<td style="width: 50px;">
					<input type="hidden" name="fm_link[title][]" value="`+link_title+`">
					<input type="hidden" name="fm_link[url][]" value="`+link_url+`">
					<input type="hidden" name="fm_link[type][]" value="`+link_type+`">
					<button type="button" class="btn btn-sm btn-danger remove-menu-item"><i class="fa fa-trash"></i></button>
					</td>
					</tr>
					`);
			} else {
				Swal.fire({
					icon: 'warning',
					text: '<?= __('admin.link_title_and_url_are_mandatory') ?>'
				});
			}
		});

		$(document).on('click', '.remove-menu-item', function(){
			$(this).parent().parent().remove();
		});

		$(document).on('click', '.remove-footer-menu', function(){
			$(this).parent().parent().remove();
		});

		$(document).on('click', '.remove-custom-page', function(){
			$(this).parent().parent().remove();
			$("#setting-form").append('<input type="hidden" name="return" value="footer_menu"/>');
		});

		$(document).on('click', '.btn-custom-page-modal-form', function(){
			let modal = $('.custom-page-modal');
			modal.find('#cp_index').val(($('#custom_page_list tr:not(.empty)').length+1));
			modal.find('#cp_title').val('');
			modal.find('#cp_image').val('');
			modal.find('#cp_meta_id').val('');
			modal.find('input[name="store_cpimage"]').val('');
			modal.find('#store_cpimage_container').attr('src', '<?= base_url('assets/store/default/img/banner.png'); ?>').show();
			modal.find('#cp_content').val('');
			modal.find('.summernote').summernote('code', '');
			modal.modal('show');
		});

		$(document).on('click', '.btn-custom-page-modal-form-edit', function(){
			let data = $(this).parent();
			let modal = $('.custom-page-modal');
			modal.find('#cp_index').val(data.find('input[name="store[custom_page][index][]"]').val());
			modal.find('#cp_title').val(data.find('input[name="store[custom_page][title][]"]').val());
			modal.find('#cp_image').val(data.find('input[name="store[custom_page][image][]"]').val());
			modal.find('#cp_meta_id').val(data.find('input[name="store[custom_page][meta_id][]"]').val());
			modal.find('input[name="store_cpimage"]').val('');
			modal.find('#store_cpimage_container').attr('src', $(this).parent().parent().find('img').attr('src')).show();
			modal.find('#cp_content').val(data.find('input[name="store[custom_page][content][]"]').val());
			modal.find('.summernote').summernote('code', data.find('textarea[name="store[custom_page][content][]"]').val());
			modal.modal('show');
		});

		$(document).on('change', '.custom-page-modal input[name="store_cpimage"]', function() {
			read_url(this,'store_cpimage_container');
		});

		$(document).on('click', '#custom-page-submit', function(){
			let modal = $('.custom-page-modal');
			let image_src = $('#store_cpimage_container').attr('src');

			if(modal.find('#cp_title').val() == null || modal.find('#cp_title').val() == "") {
				Swal.fire({
					icon: 'warning',
					text: '<?= __('admin.page_title_should_not_be_empty') ?>'
				});
			} else {
				let page_slug = convertToSlug(modal.find('#cp_title').val());
				let pagelist = $('#custom_page_list');
				let duplicateSlug = false;

				pagelist.find('tr').each(function(index){
					$(this).find("input[name='store[custom_page][edited][]']").val(0);

					if($(this).find("input[name='store[custom_page][slug][]']").val() == page_slug && $(this).find("input[name='store[custom_page][index][]']").val() != modal.find('#cp_index').val()) {
						Swal.fire({
							icon: 'warning',
							text: '<?= __('admin.duplicate_title_name_available_please_change_it') ?>'
						});
						duplicateSlug = true;
					};
				});

				if(duplicateSlug == false) {
					let new_row = `<tr>
					<td scope="row">`+modal.find('#cp_title').val()+`</td>
					<td scope="row">`+page_slug+`</td>
					<td style="width: 200px;"><img style="width: 100px; height: 50px;" src="`+image_src+`" class='img-responsive'></td>
					<td style="width: 87px; padding: 5px 0px !important;">
					<input type="hidden" name="store[custom_page][edited][]" value="1">
					<input type="hidden" name="store[custom_page][index][]" value="`+modal.find('#cp_index').val()+`">
					<input type="hidden" name="store[custom_page][title][]" value="`+modal.find('#cp_title').val()+`">
					<input type="hidden" name="store[custom_page][slug][]" value="`+page_slug+`">
					<input type="hidden" name="store[custom_page][image][]" value="`+modal.find('#cp_image').val()+`">
					<input type="hidden" name="store[custom_page][meta_id][]" value="`+modal.find('#cp_meta_id').val()+`">
					<textarea name="store[custom_page][content][]" style="display:none">`+modal.find('.summernote').summernote('code')+`</textarea>
					<button type="button" class="btn btn-primary btn-custom-page-modal-form-edit"><i class="fa fa-pencil"></i></button>
					<button type="button" class="btn btn-danger remove-custom-page"><i class="fa fa-trash"></i></button>
					</td>
					</tr>`;

					$('#custom_page_list tr:nth-child('+(modal.find('#cp_index').val())+')').remove();

					if(modal.find('#cp_index').val() > $('#custom_page_list tr:not(.empty)').length) {
						$('#custom_page_list').append(new_row);
					} else {
						$('#custom_page_list tr:nth-child('+(modal.find('#cp_index').val())+')').before(new_row);
					}
					submit_store_setting({name:'return', value:'custom_page'});
					$("#custom-page-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
				}
			}
		});

		$(document).on('click', '.btn-footer-menu-form-modal', function(){
			let modal = $('.footer-menu-form-modal');
			modal.find('input[name="fm_index"]').val(($('#footer_menu_list tr:not(.empty)').length+1));
			modal.find('input[name="fm_title"]').val('');
			modal.find('input[name="fm_type"]').val('').trigger('change');
			$('#menu_items_list tbody').empty();
			modal.modal('show');
		});

		$(document).on('click', '.btn-footer-menu-form-modal-edit', function(){
			let data = $(this).parent();
			let letpreIndex = $(this).data('letpreindex');

			let modal = $('.footer-menu-form-modal');
			modal.find('input[name="fm_index"]').val(data.find('input[name="store[footer_menu][index]['+letpreIndex+']"]').val());
			modal.find('input[name="fm_title"]').val(data.find('input[name="store[footer_menu][title]['+letpreIndex+']"]').val());

			titles = [];
			data.find('input[name="store[footer_menu][links]['+letpreIndex+'][title][]"]').each(function(index) {
				titles.push($(this).val());
			});

			urls = [];
			data.find('input[name="store[footer_menu][links]['+letpreIndex+'][url][]"]').each(function(index) {
				urls.push($(this).val());
			});

			types = [];
			data.find('input[name="store[footer_menu][links]['+letpreIndex+'][type][]"]').each(function(index) {
				types.push($(this).val());
			});

			$('#menu_items_list tbody').empty();

			for (let index = 0; index < titles.length; index++) {
				$('#menu_items_list tbody').append(`<tr>
					<td scope="row">`+titles[index]+`</td>
					<td scope="row">`+urls[index]+`</td>
					<td style="width: 50px;">
					<input type="hidden" name="fm_link[title][]" value="`+titles[index]+`">
					<input type="hidden" name="fm_link[url][]" value="`+urls[index]+`">
					<input type="hidden" name="fm_link[type][]" value="`+types[index]+`">
					<button type="button" class="btn btn-sm btn-danger remove-menu-item"><i class="fa fa-trash"></i></button>
					</td>
					</tr>
					`);
			}
			modal.modal('show');
		});

		$(document).on('click', '#footer-menu-form-submit', function(){
			let modal = $('.footer-menu-form-modal');
			let menu_title = modal.find('input[name="fm_title"]').val();
			if(menu_title != "" && menu_title != null) {
				let titles = [];
				modal.find('input[name="fm_link[title][]"]').each(function(index) {
					titles.push($(this).val());
				});

				letpreIndex = modal.find('input[name="fm_index"]').val() - 1;

				let new_row = `<tr><td scope="row">`+modal.find('input[name="fm_index"]').val()+`</td><td scope="row">`+modal.find('input[name="fm_title"]').val()+`</td><td scope="row">`+titles.join()+`</td><td style="width: 87px; padding: 5px 0px !important;"><input type="hidden" name="store[footer_menu][index][`+letpreIndex+`]" value="`+modal.find('input[name="fm_index"]').val()+`"><input type="hidden" name="store[footer_menu][title][`+letpreIndex+`]" value="`+modal.find('input[name="fm_title"]').val()+`">`;

				modal.find('input[name="fm_link[title][]"]').each(function( index ) {
					new_row += `<input type="hidden" name="store[footer_menu][links][`+letpreIndex+`][title][]" value="`+$(this).val()+`">`;
				});

				modal.find('input[name="fm_link[url][]"]').each(function( index ) {
					new_row += `<input type="hidden" name="store[footer_menu][links][`+letpreIndex+`][url][]" value="`+$(this).val()+`">`;
				});

				modal.find('input[name="fm_link[type][]"]').each(function( index ) {
					new_row += `<input type="hidden" name="store[footer_menu][links][`+letpreIndex+`][type][]" value="`+$(this).val()+`">`;
				});

				new_row += `<button data-letpreindex="`+letpreIndex+`" type="button" class="btn btn-primary btn-footer-menu-form-modal-edit"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-danger remove-footer-menu"><i class="fa fa-trash"></i></button>
				</td>
				</tr>`;

				$('#footer_menu_list tr:nth-child('+(modal.find('input[name="fm_index"]').val())+')').remove();

				if(modal.find('input[name="fm_index"]').val() > $('#footer_menu_list tr:not(.empty)').length) {
					$('#footer_menu_list').append(new_row);
				} else {
					$('#footer_menu_list tr:nth-child('+(modal.find('input[name="fm_index"]').val())+')').before(new_row);
				}

				submit_store_setting({name:'return', value:'footer_menu'});
				$("#footer-menu-form-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
			} else {
				Swal.fire({
					icon: 'warning',
					text: '<?= __('admin.menu_title_should_not_be_empty') ?>'
				});
			}
		});

		$(document).on('click', '.btn-social-links-form', function(){
			let modal = $('.social-links-modal');
			modal.find('#sl_index').val(($('#social_links_list tr:not(.empty)').length+1));
			modal.find('#sl_title').val('');
			modal.find('#sl_url').val('');
			modal.find('#sl_image').val('');
			modal.find('input[name="store_slicon"]').val('');
			modal.find('#store_slicon_container').attr('src', '<?= base_url('assets/store/default/img/banner.png'); ?>').show();
			modal.modal('show');
		});

		$(document).on('click', '.btn-social-links-form-edit', function(){
			let data = $(this).parent();
			let modal = $('.social-links-modal');
			modal.find('#sl_index').val(data.find('input[name="store[social_links][index][]"]').val());
			modal.find('#sl_title').val(data.find('input[name="store[social_links][title][]"]').val());
			modal.find('#sl_url').val(data.find('input[name="store[social_links][url][]"]').val());
			modal.find('#sl_image').val(data.find('input[name="store[social_links][image][]"]').val());
			modal.find('input[name="store_slicon"]').val('');
			modal.find('#store_slicon_container').attr('src', $(this).parent().parent().find('img').attr('src')).show();
			modal.modal('show');
		});

		$(document).on('click', '#social-links-submit', function(){
			let modal = $('.social-links-modal');
			let image_src = $('#store_slicon_container').attr('src');

			if(modal.find('#sl_title').val() == null || modal.find('#sl_title').val() == "") {
				Swal.fire({
					icon: 'warning',
					text: '<?= __('admin.link_title_should_not_be_empty') ?>'
				});
			} else {
				let list = $('#social_links_list');

				list.find('tr').each(function(index){
					$(this).find("input[name='store[social_links][edited][]']").val(0);
				});

				let new_row = `<tr>
				<td scope="row">`+modal.find('#sl_title').val()+`</td>
				<td scope="row">`+modal.find('#sl_url').val()+`</td>
				<td style="width: 200px; text-align:right;">
				<img style="width: 50px; height: 50px; background-color:grey;" src="`+image_src+`" class='img-responsive'>
				</td>
				<td style="width: 87px; padding: 5px 0px !important;">
				<input type="hidden" name="store[social_links][index][]" value="`+modal.find('#sl_index').val()+`">
				<input type="hidden" name="store[social_links][edited][]" value="1">
				<input type="hidden" name="store[social_links][title][]" value="`+modal.find('#sl_title').val()+`">
				<input type="hidden" name="store[social_links][url][]" value="`+modal.find('#sl_url').val()+`">
				<input type="hidden" name="store[social_links][image][]" value="`+modal.find('#sl_image').val()+`">
				<button type="button" class="btn btn-primary btn-social-links-form-edit"><i class="fa fa-pencil"></i></button>
				<button type="button" class="btn btn-danger remove-social-links"><i class="fa fa-trash"></i></button>
				</td>
				</tr>
				`;

				$('#social_links_list tr:nth-child('+(modal.find('#sl_index').val())+')').remove();

				if(modal.find('#sl_index').val() > $('#social_links_list tr:not(.empty)').length) {
					$('#social_links_list').append(new_row);
				} else {
					$('#social_links_list tr:nth-child('+(modal.find('#sl_index').val())+')').before(new_row);
				}
				submit_store_setting({name:'return', value:'social_links'});
				$("#social-links-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
			}
		});

		$(document).on('change', '.social-links-modal input[name="store_slicon"]', function() {
			read_url(this,'store_slicon_container');
		});

		$(document).on('click', '.remove-social-links', function(){
			$(this).parent().parent().remove();
			$("#setting-form").append('<input type="hidden" name="return" value="footer_menu"/>');
		});

	function read_url(input,display_id) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#'+display_id).attr('src', e.target.result).show();
			}

			reader.readAsDataURL(input.files[0]);
		}
	}

		function convertToSlug(Text) {
			return Text.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
		}
		setTimeout(function(){
			$("#store_mode").trigger('change');
		},500);

		$(document).on('change', 'select[name="productsetting[product_commission_type]"]', function() {
		    if($(this).val() == 'percentage') {
		        $("input[name='productsetting[product_commission]']")
		            .siblings('.input-group-text.currency-symbol')
		            .text('%');
		    } else {
		        $("input[name='productsetting[product_commission]']")
		            .siblings('.input-group-text.currency-symbol')
		            .text('<?= $CurrencySymbol ?>');
		    }
		});


		$(document).on('change', 'select[name="formsetting[product_commission_type]"]', function() {
		    if($(this).val() == 'percentage') {
		        $("input[name='formsetting[product_commission]']")
		            .siblings('.input-group-text.currency-symbol')
		            .text('%');
		    } else {
		        $("input[name='formsetting[product_commission]']")
		            .siblings('.input-group-text.currency-symbol')
		            .text('<?= $CurrencySymbol ?>');
		    }
		});


		$(document).on('click','.btn-delete-image', function(){
		        let input_name = $(this).data('img_input');
		        $('input[name="'+input_name+'"]').val('');
		    
		        let image_ele_id = $(this).data('img_ele');
		        let placeholder_image = $(this).data('img_placeholder');
		        $('#'+image_ele_id).attr('src', placeholder_image);
		    
		        $(this).remove()
		    });

		$(document).on('click','.btn-delete-bottom-ban-image', function(){
		        let input_name = $(this).data('img_input');
		        $('input[name="'+input_name+'"]').val('');
		    
		        let image_ele_id = $(this).data('img_ele');
		        let placeholder_image = $(this).data('img_placeholder');
		        $('#'+image_ele_id).attr('src', placeholder_image);
		        $('#homepage_bootom_banner_image').val('');
		    
		        $(this).remove()
		    });



	function changeLanguage()
    {
      
       $(".alert-dismissable").remove();
        $this = $(this);
       $.ajax({
            url:'<?= base_url("admincontrol/getStaticPages") ?>',
            type:'POST',
            dataType:'json',
            data:{language_id:$("#drpLanguage").val()},
            beforeSend:function(){ $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...'); },
            complete:function(){$this.prop('disabled', false).html($(this).data('original-text') || 'Submit'); },
            success:function(json){

                 if(json.error){
                 }
                 else if(json!="")
                 {
                     
                    $('#about_content.summernote').summernote('code', '');
                    $('#about_content.summernote').html(escape($('#about_content.summernote').summernote('code', json.about_content)));

                    $('#contact_content.summernote').summernote('code', '');
                    $('#contact_content.summernote').html(escape($('#contact_content.summernote').summernote('code', json.contact_content)));

                    $('#policy_content.summernote').summernote('code', '');
                    $('#policy_content.summernote').html(escape($('#policy_content.summernote').summernote('code', json.policy_content)));

  
                 }
                 else
                 {
                 	$('#about_content.summernote').summernote('code', '');
                 	 $('#contact_content.summernote').summernote('code', '');
                 	 $('#policy_content.summernote').summernote('code', '');

                 }
             
             },
        });
        
       return false;  
    }

    // Lifetime Commission toggle handler
    $('.update_all_settings').on('change', function() {
        var checked = $(this).prop('checked') ? 1 : 0;
        var setting_key = $(this).data('setting_key');
        var setting_type = $(this).data('setting_type');
        $.ajax({
            url: '<?= base_url("admincontrol/update_all_settings") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                'action': 'update_all_settings',
                'status': checked,
                'setting_key': setting_key,
                'setting_type': setting_type
            },
            success: function(json) {
                if(json.success) {
                    showPrintMessage(json.success, 'success');
                }
            },
        });
    });

/* ════════════════════════════════════════════════════════════════
   DEMO DATA LOADER  –  global Load Demo / Clear All
   ════════════════════════════════════════════════════════════════ */
(function($) {

    var BASE_IMG  = '<?= base_url('assets/images/site/') ?>';
    var BASE_SITE = '<?= base_url() ?>';

    /* ── Shared strings ─────────────────────────────────────────── */
    var CONFIRM_LOAD  = '<?= __('admin.demo_confirm') ?>';
    var CONFIRM_CLEAR = '<?= __('admin.clear_demo_confirm') ?>';
    var MSG_LOADED    = '<?= __('admin.demo_loaded') ?>';
    var MSG_CLEARED   = '<?= __('admin.demo_cleared') ?>';

    var EMPTY_SLIDER   = '<tr class="empty"><td colspan="4" class="text-center py-4 text-muted"><i class="bi bi-image d-block fs-2 mb-1 opacity-25"></i><small><?= __('admin.sliders_not_available') ?></small></td></tr>';
    var EMPTY_FEATURES = '<tr class="empty"><td colspan="4" class="text-center py-4 text-muted"><i class="bi bi-grid-3x3-gap d-block fs-2 mb-1 opacity-25"></i><small><?= __('admin.features_not_available') ?></small></td></tr>';
    var EMPTY_CARDS    = '<tr class="empty"><td colspan="4" class="text-center py-4 text-muted"><i class="bi bi-layout-three-columns d-block fs-2 mb-1 opacity-25"></i><small><?= __('admin.cards_not_available') ?></small></td></tr>';

    /* ── Row builders ────────────────────────────────────────────── */
    function sliderRow(s) {
        var img = s.slider_background_image ? BASE_IMG + s.slider_background_image : '<?= base_url('assets/store/default/img/banner.png') ?>';
        return `<tr>
            <td class="ps-3">${s.index}</td>
            <td class="ts-title-cell"><span class="ts-title">${s.title}</span><small class="ts-subtitle">${s.sub_title}</small></td>
            <td class="d-none d-md-table-cell"><img src="${img}" class="ts-slider-img"></td>
            <td class="ts-action-cell">
                <input type="hidden" name="store[homepage_slider][edited][]" value="0">
                <input type="hidden" name="store[homepage_slider][index][]" value="${s.index}">
                <input type="hidden" name="store[homepage_slider][title][]" value="${s.title}">
                <input type="hidden" name="store[homepage_slider][sub_title][]" value="${s.sub_title}">
                <textarea name="store[homepage_slider][content][]" class="ts-data-textarea">${s.content}</textarea>
                <input type="hidden" name="store[homepage_slider][slider_background_image][]" value="${s.slider_background_image}">
                <input type="hidden" name="store[homepage_slider][button_text][]" value="${s.button_text}">
                <input type="hidden" name="store[homepage_slider][button_link][]" value="${s.button_link}">
                <input type="hidden" name="store[homepage_slider][slider_text_color][]" value="${s.slider_text_color}">
                <input type="hidden" name="store[homepage_slider][button_text_color][]" value="${s.button_text_color}">
                <input type="hidden" name="store[homepage_slider][button_bg_color][]" value="${s.button_bg_color}">
                <button type="button" class="btn btn-sm btn-outline-secondary btn-slider-form-modal-edit me-1" title="<?= __('admin.edit') ?>"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger remove-slider-btn" title="<?= __('admin.delete') ?>"><i class="bi bi-trash"></i></button>
            </td></tr>`;
    }

    function featureRow(f) {
        var imgCell = f.feature_image ? `<img src="${BASE_IMG + f.feature_image}" class="ts-feature-img">` : `<span class="text-muted opacity-50"><i class="bi bi-grid-3x3-gap fs-3"></i></span>`;
        return `<tr>
            <td class="ps-3">${f.index}</td>
            <td class="ts-title-cell"><span class="ts-title">${f.title}</span><small class="ts-subtitle">${f.sub_title}</small></td>
            <td class="d-none d-md-table-cell">${imgCell}</td>
            <td class="ts-action-cell">
                <input type="hidden" name="store[homepage_features][edited][]" value="0">
                <input type="hidden" name="store[homepage_features][index][]" value="${f.index}">
                <input type="hidden" name="store[homepage_features][title][]" value="${f.title}">
                <input type="hidden" name="store[homepage_features][sub_title][]" value="${f.sub_title}">
                <input type="hidden" name="store[homepage_features][feature_image][]" value="${f.feature_image}">
                <button type="button" class="btn btn-sm btn-outline-secondary btn-features-form-modal-edit me-1" title="<?= __('admin.edit') ?>"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger remove-features-btn" title="<?= __('admin.delete') ?>"><i class="bi bi-trash"></i></button>
            </td></tr>`;
    }

    function bsCardRow(c) {
        var imgCell = c.feature_image ? `<img src="${BASE_IMG + c.feature_image}" class="ts-card-img">` : `<span class="text-muted opacity-50"><i class="bi bi-layout-three-columns fs-3"></i></span>`;
        return `<tr>
            <td class="ps-3">${c.index}</td>
            <td class="ts-title-cell"><span class="ts-title">${c.title}</span><small class="ts-subtitle">${c.sub_title}</small></td>
            <td class="d-none d-md-table-cell">${imgCell}</td>
            <td class="ts-action-cell">
                <input type="hidden" name="store[bs_cards][edited][]" value="0">
                <input type="hidden" name="store[bs_cards][index][]" value="${c.index}">
                <input type="hidden" name="store[bs_cards][title][]" value="${c.title}">
                <input type="hidden" name="store[bs_cards][sub_title][]" value="${c.sub_title}">
                <input type="hidden" name="store[bs_cards][feature_image][]" value="${c.feature_image}">
                <input type="hidden" name="store[bs_cards][bg_color][]" value="${c.bg_color}">
                <input type="hidden" name="store[bs_cards][button_link][]" value="${c.button_link}">
                <input type="hidden" name="store[bs_cards][link_target][]" value="${c.link_target}">
                <button type="button" class="btn btn-sm btn-outline-secondary btn-bs-cards-form-modal-edit me-1" title="<?= __('admin.edit') ?>"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger remove-bs-cards-btn" title="<?= __('admin.delete') ?>"><i class="bi bi-trash"></i></button>
            </td></tr>`;
    }

    /* ── Helper: build a notification row ───────────────────────── */
    function notifRow(idx, text) {
        return `<div class="row align-items-center mb-2">
            <div class="col-md-11">
                <div class="form-group">
                    <label class="control-label"><?= __('admin.notification') ?> ${idx}</label>
                    <input name="store[notification][]" class="form-control" type="text" value="${text}">
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-md remove-notification-btn"><i class="fa fa-trash"></i></button>
            </div>
        </div>`;
    }

    /* ── Per-section load functions (called by global button) ────── */
    function loadNotifications() {
        var $list = $('#notifications-list');
        var existing = $list.find('input[name="store[notification][]"]').length;
        if (existing === 0) $list.empty();
        var demos = [
            '🚚 Free shipping on all orders over $50!',
            '🎉 Limited-time offer: Use code SAVE20 for 20% off your first order.',
            '⭐ New arrivals added every week — check out the latest products!',
        ];
        demos.forEach(function(text, i) {
            $list.append(notifRow(existing + i + 1, text));
        });
        /* Make sure the enabled toggle is on */
        var $toggle = $('#toggle_notification_enabled');
        if ($toggle.length && !$toggle.is(':checked')) $toggle.prop('checked', true);
    }

    function loadSlider() {
        var n = $('#homepage_sliders_list tr:not(.empty)').length + 1;
        var slides = [
            { index: n,   title: 'Discover Our New Collection', sub_title: 'Premium quality at unbeatable prices',        content: 'Shop now and enjoy free shipping on orders over $50.',                slider_background_image: 'demo-slide-1.jpg', button_text: 'Shop Now',    button_link: BASE_SITE + 'store/category', slider_text_color: '#ffffff', button_bg_color: '#ffffff', button_text_color: '#222222' },
            { index: n+1, title: 'Summer Sale — Up to 50% Off', sub_title: 'Limited-time deals on top-rated items',       content: 'Grab the best prices before the offer ends. New deals every day.', slider_background_image: 'demo-slide-2.jpg', button_text: 'View Deals',  button_link: BASE_SITE + 'store/category', slider_text_color: '#ffffff', button_bg_color: '#f59e0b', button_text_color: '#ffffff' },
            { index: n+2, title: 'New Arrivals Every Week',     sub_title: 'Fresh styles for every lifestyle',            content: 'Browse our latest curated selection of premium products.',           slider_background_image: 'demo-slide-1.jpg', button_text: 'Explore Now', button_link: BASE_SITE + 'store/category', slider_text_color: '#ffffff', button_bg_color: '#ffffff', button_text_color: '#222222' },
        ];
        $('#homepage_sliders_list tr.empty').remove();
        slides.forEach(function(s) { $('#homepage_sliders_list').append(sliderRow(s)); });
    }

    function loadFeatures() {
        var n = $('#homepage_features_list tr:not(.empty)').length + 1;
        var features = [
            { index: n,   title: '<?= __('store.free_shipping')  ?? 'Free Shipping' ?>',  sub_title: '<?= __('store.on_orders_over_50') ?? 'On orders over $50' ?>',      feature_image: 'demo-card-1.jpg' },
            { index: n+1, title: '<?= __('store.secure_payment') ?? 'Secure Payment' ?>', sub_title: '<?= __('store.100_protected')     ?? '100% protected checkout' ?>', feature_image: 'demo-card-2.jpg' },
            { index: n+2, title: '<?= __('store.easy_returns')   ?? 'Easy Returns' ?>',   sub_title: '<?= __('store.30_day_policy')     ?? '30-day return policy' ?>',    feature_image: 'demo-card-3.jpg' },
            { index: n+3, title: '<?= __('store.247_support')    ?? '24/7 Support' ?>',   sub_title: '<?= __('store.always_here')       ?? 'Always here to help' ?>',     feature_image: 'demo-card-1.jpg' },
        ];
        $('#homepage_features_list tr.empty').remove();
        features.forEach(function(f) { $('#homepage_features_list').append(featureRow(f)); });
    }

    function loadBanner() {
        $('input[name="store[homepage_banner][title]"]').val('Special Offer Just for You');
        $('textarea[name="store[homepage_banner][content]"]').val('Get 20% off your first order when you subscribe to our newsletter. Limited time only!');
        $('input[name="store[homepage_banner][button_text]"]').val('Claim Your Offer');
        $('input[name="store[homepage_banner][button_link]"]').val(BASE_SITE + 'store/category');
        var demoImg = 'demo-banner-ad.jpg';
        var $p = $('#store_hbanimage_container');
        $('#homepage_bootom_banner_image').val(demoImg);
        if ($p.is('img')) {
            $p.attr('src', BASE_IMG + demoImg + '?t=' + Date.now());
        } else {
            $p.replaceWith('<img id="store_hbanimage_container" src="' + BASE_IMG + demoImg + '?t=' + Date.now() + '" class="ts-banner-preview">');
        }
    }

    function loadBsCards() {
        var n = $('#bs_cards_list tr:not(.empty)').length + 1;
        var cards = [
            { index: n,   title: 'Quality Guaranteed', sub_title: 'Every product vetted by our expert team.', feature_image: 'demo-card-1.jpg', bg_color: '', button_link: BASE_SITE + 'store/category', link_target: '_self' },
            { index: n+1, title: 'Fast Delivery',      sub_title: 'Orders shipped within 24 hours.',          feature_image: 'demo-card-2.jpg', bg_color: '', button_link: BASE_SITE + 'store/category', link_target: '_self' },
            { index: n+2, title: 'Best Prices',        sub_title: 'Price match guarantee on all items.',      feature_image: 'demo-card-3.jpg', bg_color: '', button_link: BASE_SITE + 'store/category', link_target: '_self' },
        ];
        $('#bs_cards_list tr.empty').remove();
        cards.forEach(function(c) { $('#bs_cards_list').append(bsCardRow(c)); });

        /* Fill Section Content only if editor is currently empty */
        var $sn = $('#bs_section_content_editor');
        var currentContent = '';
        try { currentContent = ($sn.summernote('code') || '').replace(/<p><br\s*\/?><\/p>/gi,'').trim(); } catch(e) {}
        if (!currentContent) {
            var demoText = '<p><strong>Welcome to our store!</strong> We are committed to offering premium products at unbeatable prices, with fast and reliable delivery. Browse our curated selection and discover great deals every day.</p>';
            try { $sn.summernote('code', demoText); } catch(e) { $sn.val(demoText); }
        }
    }

    /* ── Per-section clear functions (called by global button) ────── */
    function clearNotifications() {
        $('#notifications-list').empty();
    }

    function clearSlider() {
        $('#homepage_sliders_list').empty().append(EMPTY_SLIDER);
    }

    function clearFeatures() {
        $('#homepage_features_list').empty().append(EMPTY_FEATURES);
    }

    function clearBanner() {
        $('input[name="store[homepage_banner][title]"]').val('');
        $('textarea[name="store[homepage_banner][content]"]').val('');
        $('input[name="store[homepage_banner][button_text]"]').val('');
        $('input[name="store[homepage_banner][button_link]"]').val('');
        $('#homepage_bootom_banner_image').val('');
        $('#store_hbanimage_container').replaceWith(
            '<div id="store_hbanimage_container" class="ts-banner-preview ts-banner-preview--empty d-flex flex-column align-items-center justify-content-center text-muted small bg-light rounded border">' +
            '<i class="bi bi-image fs-3 opacity-25 mb-1"></i>' +
            '<span class="opacity-50"><?= __('admin.no_image_selected') ?></span>' +
            '</div>'
        );
    }

    function clearBsCards() {
        $('#bs_cards_list').empty().append(EMPTY_CARDS);
        var $sn = $('#bs_section_content_editor');
        try { $sn.summernote('code', ''); } catch(e) {}
        $sn.val('');
        var $ed = $sn.prev('.note-editor, .note-frame').find('.note-editable');
        if (!$ed.length) $ed = $sn.siblings('.note-editor, .note-frame').find('.note-editable');
        if (!$ed.length) $ed = $sn.closest('.card-footer').find('.note-editable');
        $ed.html('');
    }

    /* ════════════════════════════════════════════════════════════════
       GLOBAL HANDLERS  –  single Load Demo / Clear All button
       ════════════════════════════════════════════════════════════════ */
    $(document).on('click', '.btn-demo-all', function() {
        if (!confirm(CONFIRM_LOAD)) return;
        loadNotifications();
        loadSlider();
        loadFeatures();
        loadBanner();
        loadBsCards();
        showPrintMessage(MSG_LOADED, 'success');
    });

    $(document).on('click', '.btn-clear-all', function() {
        if (!confirm(CONFIRM_CLEAR)) return;
        clearNotifications();
        clearSlider();
        clearFeatures();
        clearBanner();
        clearBsCards();
        showPrintMessage(MSG_CLEARED, 'warning');
    });

})(jQuery);

</script>

<script>
    function openStoreSettingTabFromHash(){
        var h = window.location.hash;
        if(!h) return;
        var a = document.querySelector('#TabsNav a[href="' + h + '"]');
        if(a) a.click();
    }
    window.addEventListener('load', openStoreSettingTabFromHash);
    window.addEventListener('hashchange', openStoreSettingTabFromHash);
</script>

    </div>
</div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>