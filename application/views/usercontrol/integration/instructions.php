<link rel="stylesheet" type="text/css" href="<?= base_url('assets/integration/prism/css.css') ?>?v=<?= av() ?>">
<script type="text/javascript" src="<?= base_url('assets/integration/prism/js.js') ?>"></script>

<?php 
function ___h($text,$lan){
	$text = implode("\n", $text);
	$text = htmlentities($text);
	$text = '<pre class="language-'.$lan.'"><code class="language-'.$lan.'">'.$text.'</code></pre>';
	return $text;
}

$base_url  = base_url();
?>

<script type="text/javascript" src="<?= base_url('assets/plugins/html2canvas/html2canvas.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/html2canvas/jspdf.debug.js') ?>"></script>
<script type="text/javascript">
	function download(ele){
		$(".no-pdf").hide();
		$(".btn-export-pdf").btn("loading");

		var HTML_Width = $(ele).width();
		var HTML_Height = $(ele).height();

		var top_left_margin = 15;
		var PDF_Width = HTML_Width+(top_left_margin*2);
		var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
		var canvas_image_width = HTML_Width;
		var canvas_image_height = HTML_Height;

		var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;

		html2canvas($(ele)[0],{allowTaint:true}).then(function(canvas) {
			canvas.getContext('2d');
			
			var imgData = canvas.toDataURL("image/jpeg", 1.0);
			var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
		    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
			
			for (var i = 1; i <= totalPDFPages; i++) { 
				pdf.addPage(PDF_Width, PDF_Height);
				pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
			}
			
		    pdf.save("<?= __('admin.payment_api_documentation') ?>.pdf");

		    $(".no-pdf").show();
		    $(".btn-export-pdf").btn("reset");
        });
	}
</script>

<div class="container-fluid">
<div class="row" id="page-doc">
	<div class="col-12">
		<div class="card shadow-sm">
			<div class="card-header bg-primary text-white">
				<div class="d-flex justify-content-between align-items-center py-2">
					<h5 class="mb-0">
						<i class="fas fa-plug me-2"></i><?= __( 'admin.integration_of' ) ?><?= $module['name'] ?>
					</h5>
					<div>
						<a href="<?= base_url('usercontrol/integration') ?>" class="btn btn-light btn-sm me-2">
							<i class="fas fa-arrow-left me-1"></i><?= __('admin.back') ?>
						</a>
						<?php if($module_key == 'affiliate_register_api'){ ?>
		    				<button type="button" onclick="download('#page-doc')" class="btn btn-export-pdf btn-warning btn-sm">
		    					<i class="fas fa-file-pdf me-1"></i><?= __( 'admin.download_as_pdf' ) ?>
		    				</button>
		    			<?php } ?>
		    		</div>
				</div>
			</div>

			<div class="card-body">
				<div class="integration-modules-ins">
					<?= $views ?>

					<?php if($module_key == 'affiliate_register_api'){ ?>
						<div id="affiliate_register_api">
							<p><?= __('user.api_explain_reg'); ?></p>

							<p class="text-info"><?= __('user.download_postman_example'); ?><a target="_blank" href="<?= base_url('assets/Affiliate-Pro.postman_collection.json') ?>">Affiliate-Pro.postman_collection.json</a>. <?= __('user.how_to_import_postman_date_file'); ?> <a href="https://learning.postman.com/docs/running-collections/working-with-data-files/" target="_target" ><i class="fa fa-external-link"></i></a></p>
							<p class="text-info"><?= __('user.download_php_boot_example'); ?><a download target="_blank" href="<?= base_url('assets/register-api-example.zip') ?>"> <?= __('user.download'); ?> </a></p>


							<h5 class="mt-5"><?= __('user.get_custom_fiels_for_registration'); ?></h5>
							<hr>
							<p><?= __('user.to_get_custom_field_you_need_to_call_this_api'); ?></p>


							<h6><?= __('user.uri') ?></h6>
							<?php
								$code = array();
								$code[] = 'GET '. base_url('/api/register_custom_field');
								echo ___h($code,'html');
							?>

							<h6 class="mt-3"><?= __('user.example_response'); ?></h6>
							<?php
								$code = array();
								$code[] = '{';
								$code[] = '    "fields": [';
								$code[] = '        {';
								$code[] = '            "type": "select",';
								$code[] = '            "required": false,';
								$code[] = '            "label": "Select",';
								$code[] = '            "className": "form-control",';
								$code[] = '            "name": "custom_select-1594271473044",';
								$code[] = '            "min": "",';
								$code[] = '            "max": "",';
								$code[] = '            "maxlength": "",';
								$code[] = '            "values": [';
								$code[] = '                {';
								$code[] = '                    "label": "Option 1",';
								$code[] = '                    "value": "option-1",';
								$code[] = '                    "selected": "true"';
								$code[] = '                },';
								$code[] = '                {';
								$code[] = '                    "label": "Option 2",';
								$code[] = '                    "value": "option-2"';
								$code[] = '                },';
								$code[] = '                {';
								$code[] = '                    "label": "Option 3",';
								$code[] = '                    "value": "option-3"';
								$code[] = '                }';
								$code[] = '            ],';
								$code[] = '            "mobile_validation": false';
								$code[] = '        },';
								$code[] = '        {';
								$code[] = '            "type": "text",';
								$code[] = '            "required": true,';
								$code[] = '            "label": "Custom FIeld",';
								$code[] = '            "className": "form-control",';
								$code[] = '            "name": "custom_text-1594269069679",';
								$code[] = '            "min": "",';
								$code[] = '            "max": "",';
								$code[] = '            "maxlength": "",';
								$code[] = '            "values": null,';
								$code[] = '            "mobile_validation": false';
								$code[] = '        }';
								$code[] = '    ]';
								$code[] = '}';
								echo ___h($code,'javascript');
							?>



							<h5 class="mt-5"><?= __('admin.create_an_affiliate_registration') ?></h5>
							<hr>
							<p>This API is used to create a new Affiliate User and a Affiliate Registration in a single request. This is useful if for example you have a main website that User’s create their account on initially. The User is technically creating their global User object and a User Registration for that website (i.e. that Affiliate Script). In this case, you will want to create the Affiliate and the Affiliate Registration in a single step. This is the API to use for that.</p>


							<h6>URI</h6>
							<?php
								$code = array();
								$code[] = 'POST '. base_url('/api/register');
								echo ___h($code,'html');
							?>

							<h6 class="mt-5">Request Body</h6>
							<table class="table-inverse table">
								<thead>
									<tr>
										<th width="200px">Field</th>
										<th width="100px">Type</th>
										<th>Description</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>firstname</th>
										<th>string</th>
										<td>The first name of the User.</td>
									</tr>

									<tr>
										<th>lastname</th>
										<th>string</th>
										<td>The User’s last name.</td>
									</tr>

									<tr>
										<th>email</th>
										<th>email</th>
										<td>The User’s email address. An email address is a unique in Affiliate Pro and stored in lower case.</td>
									</tr>

									<tr>
										<th>username</th>
										<th>string</th>
										<td>The username of the User for this Application only.</td>
									</tr>

									<tr>
										<th>password</th>
										<th>string</th>
										<td>The User’s plain texts password. This password will be hashed and the provided value will never be stored and cannot be retrieved.</td>
									</tr>
									<?php 
										foreach ($customField as $key => $value) { 
											if($value['type'] == 'header') continue; 
									?>
										<tr>
											<th><?= $value['name'] ?></th>
											<th>custom field</th>
											<td>The Custom Fields <b><?= $value['label'] ?></b></td>
										</tr>
									<?php } ?>

									<tr>
										<th>terms</th>
										<th>boolean</th>
										<td>Accept Terms & Condition</td>
									</tr>
								</tbody>
							</table>


							<h6 class="mt-5">Example Request JSON</h6>

							<?php
								$code = array();
								$code[] = "{";
								$code[] = "	'firstname':'Keri',";
								$code[] = "	'lastname':'Taylor',";
								$code[] = "	'username':'taylor.keri',";
								$code[] = "	'email':'taylor.keri@gmail.com',";
								$code[] = "	'password':'password',";
								$code[] = "	'terms':'true',";
								$code[] = "}";
								echo ___h($code,'javascript');
							?>

							<h6 class="mt-5">Response</h6>
							<p>The response for this API contains the User and the User Registration that were created. Security sensitive fields will not be returned in the response.</p>

							<b class="mt-4">errors</b>
							<p>Error object return the all error in object key is a field name and value is error title</p>

							<b class="mt-4">success</b>
							<p>If user created successfully them success message will be returned..</p>

						</div>
					<?php } ?>

					<?php if($module_key == 'wp_forms'){ ?>
						<div class="alert alert-info d-flex align-items-start mb-4">
							<i class="fab fa-wordpress fa-3x me-3 text-primary"></i>
							<div>
								<h5 class="alert-heading mb-2"><i class="fas fa-wpforms me-2"></i><?= __('user.wpforms_integration') ?></h5>
								<p class="mb-0"><?= __('user.wpforms_integration_desc') ?></p>
							</div>
						</div>

						<div class="card border-primary mb-3">
							<div class="card-header bg-primary bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?= __('user.installation_steps') ?></h6>
							</div>
							<div class="card-body">
								<ol class="list-group list-group-numbered">
									<li class="list-group-item border-0 py-3">
										<i class="fab fa-wordpress text-primary me-2"></i>
										<?= __('user.login_wordpress_dashboard') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-puzzle-piece text-primary me-2"></i>
										<?= __('user.goto_plugins_page') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-download text-primary me-2"></i>
										<?= __('user.install_header_footer_scripts_plugin') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-file-alt text-primary me-2"></i>
										<?= __('user.create_thank_you_page') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-wpforms text-primary me-2"></i>
										<?= __('user.configure_wpforms_confirmation') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-ad text-primary me-2"></i>
										<?= __('user.create_banner_affiliate_admin') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-code text-primary me-2"></i>
										<?= __('user.copy_action_code_to_thank_you') ?>
										<button class="btn btn-sm btn-info ms-2" data-bs-toggle="modal" data-bs-target="#myModal">
											<i class="fa fa-info-circle"></i> <?= __('user.view_example') ?>
										</button>
									</li>
									<li class="list-group-item border-0 py-3 bg-success bg-opacity-10">
										<i class="fas fa-check-circle text-success me-2"></i>
										<strong><?= __('user.integration_complete') ?></strong>
									</li>
								</ol>
							</div>
						</div>

						<div class="card border mb-4">
							<div class="card-header bg-light">
								<h6 class="mb-0"><i class="fas fa-code me-2"></i><?= __('user.tracking_code') ?></h6>
							</div>
							<div class="card-body">

							<?php
							$code = array();
							$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
							$code[] = '<script type="text/javascript">';
							$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
							$code[] = '	AffTracker.createAction( "actionCode" )';
							$code[] = '</script>';
							
							echo ___h($code,'html');
							?>
							</div>
						</div>

						<div class="card border-info mb-3">
							<div class="card-header bg-info bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-info-circle me-2"></i><?= __('user.tracking_parameters') ?></h6>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-bordered table-hover">
										<thead class="table-light">
											<tr>
												<th width="30%"><?= __('user.parameter') ?></th>
												<th><?= __('user.description') ?></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><code class="text-danger">WebsiteUrl</code></td>
												<td><?= __('user.website_root_url') ?></td>
											</tr>
											<tr>
												<td><code class="text-danger">actionCode</code></td>
												<td><?= __('user.action_code_from_banner_tool') ?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="card border-success mb-3">
							<div class="card-header bg-success bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-list me-2"></i><?= __('user.available_action_codes') ?></h6>
							</div>
							<div class="card-body">
								<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
									<?php foreach ($action_codes as $key => $value) { ?>
										<div class="col">
											<div class="badge bg-primary w-100 text-start py-2">
												<i class="fas fa-code me-1"></i><?= $value['action_code'] ?>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
					
					<?php if($module_key == 'woocommerce'){ ?>
						<p>Integrate affiliate script into WooCommerce. download WooCommerce plugin from here <a href="<?= base_url('integration/download_plugin/woocommerce') ?>">WordPress Plugin</a> and follow following step.</p>
						<hr>

						<ol class="installed-step">
							<li>Log into your WordPress dashboard.</li>
							<li>Go To "plugins" page on WordPress left menu.</li>
							<li>Upload new plugin zip file that you download from Affiliate script.</li>
							<li>Install the plugin and wait until installation will be finish.</li>
							<li>Activate Plugin, and now you completed "Affiliate" plugin installation successfully.</li>
						</ol>
					<?php } ?>

					<?php if($module_key == 'wp_show_affiliate_id'){ ?>
						<div class="alert alert-primary d-flex align-items-start mb-4">
							<i class="fab fa-wordpress fa-3x me-3"></i>
							<div>
								<h5 class="alert-heading mb-2"><?= __('user.wp_show_affiliate_id') ?></h5>
								<p class="mb-2"><?= __('user.wp_show_affiliate_id_desc') ?></p>
								<a href="<?= base_url('integration/download_plugin/show_affiliate_id') ?>" class="btn btn-primary btn-sm">
									<i class="fas fa-download me-1"></i><?= __('user.download_plugin') ?>
								</a>
							</div>
						</div>

						<div class="card border-primary mb-3">
							<div class="card-header bg-primary bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?= __('user.quick_installation') ?></h6>
							</div>
							<div class="card-body">
								<ol class="list-group list-group-numbered">
									<li class="list-group-item border-0 py-3">
										<i class="fab fa-wordpress text-primary me-2"></i>
										<?= __('user.login_wordpress_dashboard') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-puzzle-piece text-primary me-2"></i>
										<?= __('user.goto_plugins_page') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-upload text-primary me-2"></i>
										<?= __('user.upload_plugin_zip') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-spinner text-primary me-2"></i>
										<?= __('user.wait_installation_finish') ?>
									</li>
									<li class="list-group-item border-0 py-3 bg-success bg-opacity-10">
										<i class="fas fa-check-circle text-success me-2"></i>
										<strong><?= __('user.show_affiliate_id_installation_complete') ?></strong>
									</li>
								</ol>
							</div>
						</div>
					<?php } ?>

					<?php if($module_key == 'show_affiliate_id'){ ?>
						<div class="alert alert-info d-flex align-items-start mb-4">
							<i class="fas fa-id-badge fa-3x me-3"></i>
							<div>
								<h5 class="alert-heading mb-2"><?= __('user.show_affiliate_id_title') ?></h5>
								<p class="mb-0"><?= __('user.show_affiliate_id_desc') ?></p>
							</div>
						</div>

						<div class="card border mb-4">
							<div class="card-header bg-light">
								<h6 class="mb-0"><i class="fas fa-code me-2"></i><?= __('user.integration_code') ?></h6>
							</div>
							<div class="card-body">
								<?php
									$code = array();
									$code[] = '<script type="text/javascript" src="'. base_url('integration/show_affiliate_id') .'"></script>';
									$code[] = '<script type="text/javascript">';
									$code[] = '	var af_df_setting = {';
									$code[] = '	  position:\'bottom\',';
									$code[] = '	  text:\'Affiliate ID is {id}\',';
									$code[] = '	}';
									$code[] = '</script>';
									echo ___h($code,'html');
								?>
							</div>
						</div>

						<div class="card border-info mb-3">
							<div class="card-header bg-info bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-sliders-h me-2"></i><?= __('user.configuration_options') ?></h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<h6 class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i><?= __('user.position_options') ?>:</h6>
										<div class="list-group">
											<div class="list-group-item"><code>bottom</code> <?= __('user.default') ?></div>
											<div class="list-group-item"><code>top</code></div>
											<div class="list-group-item"><code>left</code></div>
											<div class="list-group-item"><code>right</code></div>
											<div class="list-group-item"><code>top-left</code></div>
											<div class="list-group-item"><code>top-right</code></div>
											<div class="list-group-item"><code>bottom-left</code></div>
											<div class="list-group-item"><code>bottom-right</code></div>
										</div>
									</div>
									<div class="col-md-6">
										<h6 class="fw-bold"><i class="fas fa-font me-2 text-primary"></i><?= __('user.text_customization') ?>:</h6>
										<div class="alert alert-light">
											<p class="mb-2"><?= __('user.customize_display_text') ?></p>
											<p class="mb-0"><strong><?= __('user.use_placeholder') ?>:</strong> <code>{id}</code> <?= __('user.will_be_replaced_with_affiliate_id') ?></p>
										</div>
										<div class="card bg-primary text-white">
											<div class="card-body">
												<h6 class="card-title"><i class="fas fa-lightbulb me-2"></i><?= __('user.example') ?>:</h6>
												<code class="text-white">"Affiliate ID is {id}"</code>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<?php if($module_key == 'postback'){ ?>
						<div class="alert alert-warning d-flex align-items-start mb-4">
							<i class="fas fa-cookie-bite fa-3x me-3"></i>
							<div>
								<h5 class="alert-heading mb-2"><?= __('user.postback_tracking') ?></h5>
								<p class="mb-0"><?= __('user.postback_tracking_desc') ?></p>
							</div>
						</div>

						<div class="card border mb-4">
							<div class="card-header bg-light">
								<h6 class="mb-0"><i class="fas fa-link me-2"></i><?= __('user.url_structure') ?></h6>
							</div>
							<div class="card-body">
								<p class="text-muted mb-3"><?= __('user.advertiser_destination_url_sample') ?>:</p>
								<?php
									$code = array();
									$code[] = 'https://www.domain.co.nz/?city={city}&regionCode={regionCode}&regionName={regionName}&countryCode={countryCode}&countryName={countryName}&continentName={continenName}&timezone={timezone}&currencyCode={currencyCode}&currencySymbol={currencySymbol}&ip={ip}&type={type}&id={id}&custom_field1={custom_field1}&custom_field2={custom_field2}';
									echo ___h($code,'html');
								?>
							</div>
						</div>

						<div class="card border-info mb-4">
							<div class="card-header bg-info bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-tags me-2"></i><?= __('user.available_parameters') ?></h6>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-hover table-sm">
										<thead class="table-light">
											<tr>
												<th width="30%"><?= __('user.parameter') ?></th>
												<th><?= __('user.description') ?></th>
											</tr>
										</thead>
										<tbody>
											<tr><td><code class="text-danger">{city}</code></td><td><?= __('user.city_name') ?></td></tr>
											<tr><td><code class="text-danger">{regionCode}</code></td><td><?= __('user.region_code') ?></td></tr>
											<tr><td><code class="text-danger">{regionName}</code></td><td><?= __('user.region_name') ?></td></tr>
											<tr><td><code class="text-danger">{countryCode}</code></td><td><?= __('user.country_code') ?></td></tr>
											<tr><td><code class="text-danger">{countryName}</code></td><td><?= __('user.country_name') ?></td></tr>
											<tr><td><code class="text-danger">{continentName}</code></td><td><?= __('user.continent_name') ?></td></tr>
											<tr><td><code class="text-danger">{timezone}</code></td><td><?= __('user.timezone') ?></td></tr>
											<tr><td><code class="text-danger">{currencyCode}</code></td><td><?= __('user.currency_code') ?></td></tr>
											<tr><td><code class="text-danger">{currencySymbol}</code></td><td><?= __('user.currency_symbol') ?></td></tr>
											<tr><td><code class="text-danger">{ip}</code></td><td><?= __('user.ip_address') ?></td></tr>
											<tr><td><code class="text-danger">{type}</code></td><td><?= __('user.type_action_general_click_product_click_sale') ?></td></tr>
											<tr><td><code class="text-danger">{id}</code></td><td><?= __('user.sale_id_or_click_id') ?></td></tr>
											<tr><td><code class="text-danger">{custom_field1}</code></td><td><?= __('user.custom_field_1') ?></td></tr>
											<tr><td><code class="text-danger">{custom_field2}</code></td><td><?= __('user.custom_field_2') ?></td></tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="card border-success mb-3">
							<div class="card-header bg-success bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-check-circle me-2"></i><?= __('user.example_url') ?></h6>
							</div>
							<div class="card-body">
								<?php
									$code = array();
									$code[] = 'https://www.domain.co.nz/?city=New York&regionCode=NY&regionName=New York&countryCode=US&countryName=United States&continentName=NA&timezone=North America&currencyCode=$&currencySymbol=USD&ip=170.171.1.24&type=general_click&id=1542';
									echo ___h($code,'html');
								?>
							</div>
						</div>
					<?php } ?>

					<?php if($module_key == 'php_api_library'){ ?>
						<div class="alert alert-primary d-flex align-items-start mb-4">
							<i class="fab fa-php fa-3x me-3"></i>
							<div>
								<h5 class="alert-heading mb-2"><?= __('user.php_api_library') ?></h5>
								<p class="mb-2"><?= __('user.php_api_library_desc') ?></p>
								<a href="<?= base_url('integration/download_plugin/php_api_library') ?>'" class="btn btn-primary btn-sm">
									<i class="fas fa-download me-1"></i><?= __('user.download_library') ?>
								</a>
							</div>
						</div>

						<div class="card border-info mb-4">
							<div class="card-header bg-info bg-opacity-10">
								<h5 class="mb-0"><i class="fas fa-step-forward me-2"></i><?= __('user.step_1_common_tracking_script') ?></h5>
							</div>
							<div class="card-body">
								<p class="text-muted"><?= __('user.add_script_all_pages') ?>:</p>
								<?php
								$code = array();
								$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
								echo ___h($code,'html');
								?>
							</div>
						</div>

						<div class="card border-success mb-4">
							<div class="card-header bg-success bg-opacity-10">
								<h5 class="mb-0"><i class="fas fa-step-forward me-2"></i><?= __('user.step_2_order_tracking') ?></h5>
							</div>
							<div class="card-body">
								<p class="text-muted mb-3"><?= __('user.add_code_order_success_page') ?></p>
						<?php
							$code = array();
							$code[] = '<?php';
							$code[] = 'require "affiliatepro.php";';
							$code[] = '';
							$code[] = '$tracking = new AffiliatePro();';
							$code[] = '$tracking->orderId = "OrderId";';
							$code[] = '$tracking->orderCurrency = "OrderCurrency";';
							$code[] = '$tracking->orderTotal = "OrderTotal";';
							$code[] = '$tracking->productIds = array("product_id1", "product_id1", "...");';
							$code[] = '$tracking->websiteUrl = "https://www.abc.com";';
							$code[] = '';
							$code[] = '//set custom value';
							$code[] = '$tracking->setData("custom_data_1","value");';
							$code[] = '$tracking->setData("custom_data_2","value");';
							$code[] = '$tracking->setData("custom_data_...","value");';
							$code[] = '';
							$code[] = '//place order in affiliate script';
							$code[] = '$tracking->placeOrder();';
							echo ___h($code,'php');
						?>
							</div>
						</div>

						<div class="card border-warning mb-3">
							<div class="card-header bg-warning bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-info-circle me-2"></i><?= __('user.parameters_reference') ?></h6>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-sm table-hover">
										<thead class="table-light">
											<tr>
												<th width="30%"><?= __('user.parameter') ?></th>
												<th><?= __('user.description') ?></th>
											</tr>
										</thead>
										<tbody>
											<tr><td><code class="text-danger">websiteUrl</code></td><td><?= __('user.website_root_url') ?></td></tr>
											<tr><td><code class="text-danger">orderId</code></td><td><?= __('user.unique_order_id') ?></td></tr>
											<tr><td><code class="text-danger">orderCurrency</code></td><td><?= __('user.currency_symbol_order') ?></td></tr>
											<tr><td><code class="text-danger">orderTotal</code></td><td><?= __('user.total_amount_order') ?></td></tr>
											<tr><td><code class="text-danger">productIds</code></td><td><?= __('user.product_ids_comma_separated') ?></td></tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					<?php } ?>

					<?php if($module_key == 'wp_user_register'){ ?>
						<div class="alert alert-info d-flex align-items-start mb-4">
							<i class="fab fa-wordpress fa-3x me-3 text-primary"></i>
							<div>
								<h5 class="alert-heading mb-2"><?= __('user.wp_registration_bridge') ?></h5>
								<p class="mb-2"><?= __('user.wp_registration_bridge_desc') ?></p>
								<a href="<?= base_url('integration/download_plugin/wp_user_register') ?>" class="btn btn-primary btn-sm">
									<i class="fas fa-download me-1"></i><?= __('user.download_plugin') ?>
								</a>
							</div>
						</div>

						<div class="card border-primary mb-3">
							<div class="card-header bg-primary bg-opacity-10">
								<h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?= __('user.installation_steps') ?></h6>
							</div>
							<div class="card-body">
								<ol class="list-group list-group-numbered">
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-download text-primary me-2"></i>
										<?= __('user.download_wordpress_plugin_from_above') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fab fa-wordpress text-primary me-2"></i>
										<?= __('user.login_wordpress_dashboard') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-puzzle-piece text-primary me-2"></i>
										<?= __('user.goto_plugins_page') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-upload text-primary me-2"></i>
										<?= __('user.upload_plugin_zip') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-spinner text-primary me-2"></i>
										<?= __('user.wait_installation_finish') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-check-circle text-success me-2"></i>
										<?= __('user.activate_plugin_complete') ?>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-cog text-primary me-2"></i>
										<?= __('user.goto_wordpress_settings_menu') ?>
										<button class="btn btn-sm btn-info ms-2" data-bs-toggle="modal" data-bs-target="#myModal_bridge">
											<i class="fa fa-info-circle"></i> <?= __('user.view_example') ?>
										</button>
									</li>
									<li class="list-group-item border-0 py-3">
										<i class="fas fa-user-cog text-primary me-2"></i>
										<?= __('user.configure_wordpress_woocommerce_registration') ?>
									</li>
								</ol>
							</div>
						</div>
					<?php } ?>

							<?php if($module_key == 'bigcommerce'){ ?>
								
								<p>Integrate affiliate script into Big Commerce. Install our "Affiliate Pro" module in to your store
									<hr>

									<ol class="installed-step">
										<li>Log into your Big Commerce dashboard.</li>
										<li>From The Left Side Panel Open <code class="code_">Store Front -> Script Manager</code></li>
										<li>
											Create a new Script

											<ol class="installed-step">
												<div class="step"><b>Name of script : </b>  Affiliate Script </div>
												<div class="step"><b>Description : </b>  Affiliate Tracking Code </div>
												<div class="step"><b>Location on page : </b>  footer </div>
												<div class="step"><b>Select pages where script will be added : </b> All pages </div>
												<div class="step"><b>Script type : </b> Script </div>
												<div class="step"><b>Script contents : </b> </div>

												<?php
												$code = array();
												$code[] = '<script type="text/javascript">';
												$code[] = '	if("{{ page_type }}" == "product"){';
												$code[] = '		{{ inject "data" product }}';
												$code[] = '		var productData = JSON.parse({{jsContext}});';
												$code[] = '		AffTracker.setWebsiteUrl(window.location.hostname);';
												$code[] = '		AffTracker.productClick( productData["data"]["id"] );';
												$code[] = '	}';
												$code[] = '	';
												$code[] = '	if("{{ page_type }}" == "orderconfirmation"){';
												$code[] = '		fetch("/api/storefront/order/{{checkout.order.id}}", {credentials: "include"})';
												$code[] = '		.then(function(response) {';
												$code[] = '			return response.json();';
												$code[] = '		})';
												$code[] = '		.then(function(orderDetails) {';
												$code[] = '			var product_ids = "";';
												$code[] = '			orderDetails.lineItems.physicalItems.forEach(function(j){';
												$code[] = '			 	product_ids += product_ids ? "," + j["productId"] : j["productId"]';
												$code[] = '			})';
												$code[] = '			AffTracker.setWebsiteUrl(window.location.hostname);';
												$code[] = '			';
												$code[] = '			AffTracker.setData( "custom_data_1", "value" );';
												$code[] = '			AffTracker.setData( "custom_data_2", "value" );';
												$code[] = '			AffTracker.setData( "custom_data_...", "value" );';
												$code[] = '			';
												$code[] = '			AffTracker.add_order({';
												$code[] = '				order_id       : "{{checkout.order.id}}",';
												$code[] = '				order_currency : orderDetails.currency.code,';
												$code[] = '				order_total    : orderDetails.orderAmount,';
												$code[] = '				product_ids    : product_ids,';
												$code[] = '			})';
												$code[] = '		});';
												$code[] = '	}';
												$code[] = '</script>';
												
												echo ___h($code,'html');
												?>
											</ol>
										</li>

										<li>
											Create a new Script

											<ol class="installed-step">
												<div class="step"><b>Name of script : </b>  Affiliate Script </div>
												<div class="step"><b>Description : </b>  Affiliate Tracking Link </div>
												<div class="step"><b>Location on page : </b>  Head </div>
												<div class="step"><b>Select pages where script will be added : </b> All pages </div>
												<div class="step"><b>Script type : </b> URL </div>
												<div class="step"><b>Load method : </b> Default </div>
												<div class="step"><b>Script URL : </b> </div>

												<?php
												$code = array();
												$code[] = $base_url .'bigcommerce.js';
												echo ___h($code,'html');
												?>
											</ol>
										</li>
										<li>congratulations you have successfully installed Affiliate Pro</li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'prestashop'){ ?>
									<p>Integrate affiliate script into prestashop. download prestashop module from here <a href="<?= base_url('integration/download_plugin/prestashop') ?>">Prestashop Module</a> and follow following step.</p>
									
									<ol class="installed-step">
										<li>Log into your PrestaShop dashboard.</li>
										<li>Using the left menu bar, Open the Modules tab and select the "Modules and Services" option</li>
										<li>From here you will see the the normal list of the available modules for your store. To upload your third party module, look to the upper right corner of the screen and click on the Add New Module button. </li>
										<li>Using the Browse button, locate the module from your local computer. Once selected, click the Upload This Module button underneath the Module File field. This will upload the module to your PrestaShop Module API. Once you see the successful message, you know the module is added correctly.</li>
										<li>Continue the installation by scrolling down the modules list until you find the one you installed. We installed the "Affiliate Pro" module. Once you find the module, click on the Install button located to the right side of the module row.</li>
										<li>Once the module runs its install program, you should see a message indicating it was completed.</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'xcart'){ ?>
									<p>Integrate affiliate script into Xcart. get backup of your website and follow following step.</p>

									<ol class="installed-step">
										<li>
											Open file <code class="code_">/skins/customer/header/parts/script_config.twig</code> and add following code at the end of file

											<?php
												$code = array();
												$code[] = '<script type="text/javascript" src="'. base_url('integration/xcart') .'"></script>';
												echo ___h($code,'html');
											?>
											
										</li>
										<li>
											Open file <code class="code_">/classes/XLite/Controller/Customer/Product.php</code> and add following code before the <code class="code_">parent::handleRequest();</code> line
											
											<?php
											$code = array();
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '	$ipaddress = "";';
											$code[] = '	if (getenv("HTTP_CLIENT_IP")) $ipaddress           = getenv("HTTP_CLIENT_IP");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED")) $ipaddress     = getenv("HTTP_X_FORWARDED");';
											$code[] = '	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress   = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_FORWARDED")) $ipaddress       = getenv("HTTP_FORWARDED");';
											$code[] = '	else if(getenv("REMOTE_ADDR")) $ipaddress          = getenv("REMOTE_ADDR");';
											$code[] = '	else $ipaddress                                    = "UNKNOWN";';
											$code[] = '	$affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '	$protocol = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http");';
											$code[] = '	$base_url = $protocol . "://" . $_SERVER["HTTP_HOST"];';
											$code[] = '	$complete_url =   $base_url . $_SERVER["REQUEST_URI"];';
											$code[] = '	$affiliateData = array(';
											$code[] = '	    "product_id"       => $this->getProduct()->getId(),';
											$code[] = '	    "af_id"            => $affliate_cookie,';
											$code[] = '	    "ip"               => $ipaddress,';
											$code[] = '	    "base_url"         => base64_encode($base_url),';
											$code[] = '	    "script_name"      => "xcart",';
											$code[] = '	    "current_page_url" => base64_encode($complete_url),';
											$code[] = '	);';
											$code[] = '	$context_options = stream_context_create(array(';
											$code[] = '	    "http"=>array(';
											$code[] = '	        "method"=>"GET",';
											$code[] = '	        "header"=> "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '	    )';
											$code[] = '	)); ';
											$code[] = '	file_get_contents("'. base_url('integration/addClick') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											
											echo ___h($code,'php');
											?>
											
										</li>
										<li>
											Open file <code class="code_">/classes/XLite/Controller/Customer/CheckoutSuccess.php</code> add following code before <code class="code_">parent::handleRequest();</code> line
											
											<?php
											$code = array();
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '    $ipaddress = "";';
											$code[] = '';
											$code[] = '    if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");';
											$code[] = '    else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '    else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");';
											$code[] = '    else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '    else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");';
											$code[] = '    else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");';
											$code[] = '    else $ipaddress = "UNKNOWN";';
											$code[] = '';
											$code[] = '    $protocol = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http");';
											$code[] = '    $base_url = $protocol . "://" . $_SERVER["HTTP_HOST"];';
											$code[] = '    $affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '';
											$code[] = '    $affiliateData = array(';
											$code[] = '        "order_id"       => $this->getOrder()->getOrderNumber(),';
											$code[] = '        "order_currency" => $this->getOrder()->getCurrency()->getCurrencySymbol(false),';
											$code[] = '        "order_total"    => $this->getOrder()->getPaidTotal(),';
											$code[] = '        "product_ids"    => array(),';
											$code[] = '        "af_id"          => $affliate_cookie,';
											$code[] = '        "ip"             => $ipaddress,';
											$code[] = '        "base_url"       => base64_encode($base_url),';
											$code[] = '        "script_name"    => "xcart",';
											$code[] = '    );';
											$code[] = '';
											$code[] = '    foreach ($this->getOrder()->getItems() as $item) { $affiliateData["product_ids"][] = $item->getItemId(); }';
											$code[] = '';
											$code[] = '    $context_options = stream_context_create(array(';
											$code[] = '        "http" => array(';
											$code[] = '          "method" => "GET",';
											$code[] = '          "header" => "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '        )';
											$code[] = '    ));';
											$code[] = '';
											$code[] = '    file_get_contents("'. base_url('integration/addOrder') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											
											echo ___h($code,'php');
											?>
											
										</li>
										<li> Clear Files Cache
											<ol class="installed-step">
												<li>Goto Admin Dashboard</li>
												<li>Click on <b>System Tool</b> from left menu</li>
												<li>Click On <b>Cache Management</b> in System Tool Menu</li>
												<li>Click on Start Button in <b>Re-deploy the store</b> section</li>
											</ol>
										</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'zencart'){ ?>
									<p>Integrate affiliate script into zen cart. get backup of your website and follow following step.</p>


									<div class="alert alert-info">
										For Find Your Template Directory Name Go <code class="code_">Admin>Tools>Template Selection</code> you can see your template directory name. use this name insted of <b>your_template_directory</b>
									</div>
									<ol class="installed-step">
										<li>
											Open file <code class="code_">/includes/templates/your_template_directory/common/html_header.php</code> and add following code at the end of file

											<?php
											$code = array();
											$code[] = '<script type="text/javascript" src="'. base_url('integration/zencart') .'"></script>';
											echo ___h($code,'html');
											?>
											
										</li>
										<li>
											Open file <code class="code_">/includes/templates/your_template_directory/templates/tpl_product_info_display.php</code> add following code after at the end of file
											
											<?php
											$code = array();
											$code[] = '<?php';
											$code[] = '	/* AFFILIATE PRO integration */';
											$code[] = '	$ipaddress = "";';
											$code[] = '	if (getenv("HTTP_CLIENT_IP")) $ipaddress           = getenv("HTTP_CLIENT_IP");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED")) $ipaddress     = getenv("HTTP_X_FORWARDED");';
											$code[] = '	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress   = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_FORWARDED")) $ipaddress       = getenv("HTTP_FORWARDED");';
											$code[] = '	else if(getenv("REMOTE_ADDR")) $ipaddress          = getenv("REMOTE_ADDR");';
											$code[] = '	else $ipaddress                                    = "UNKNOWN";';
											$code[] = '';
											$code[] = '	$affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '	$protocol = ((isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] == "on") ? "https" : "http");';
											$code[] = '	$base_url = $protocol . "://" . $_SERVER[\'HTTP_HOST\'];';
											$code[] = '	$complete_url =   $base_url . $_SERVER["REQUEST_URI"];';
											$code[] = '';
											$code[] = '	$affiliateData = array(';
											$code[] = '		"product_id"       => $products_id_current,';
											$code[] = '		"af_id"            => $affliate_cookie,';
											$code[] = '		"ip"               => $ipaddress,';
											$code[] = '		"base_url"         => base64_encode(HTTP_SERVER.DIR_WS_CATALOG),';
											$code[] = '		"script_name"      => "zencart",';
											$code[] = '		"current_page_url" => base64_encode($complete_url),';
											$code[] = '	);';
											$code[] = '';
											$code[] = '	$context_options = stream_context_create(array(';
											$code[] = '		"http"=>array(';
											$code[] = '			"method"=>"GET",';
											$code[] = '			"header"=> "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '		)';
											$code[] = '	)); ';
											$code[] = '	';
											$code[] = '	file_get_contents("'. base_url('integration/addClick') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '	/* end of AFFILIATE PRO integration */';
											$code[] = '?>';
											echo ___h($code,'php');
											?>
											
										</li>
										<li>
											Open file <code class="code_">/includes/templates/your_template_directory/templates/tpl_checkout_success_default.php</code> add following code at the end of file

											<div class="alert alert-info">If you can't find file than search inside <b>template_default</b> folder</div>


											
											<?php
											$code = array();
											$code[] = '<?php';
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '	$ipaddress = "";';
											$code[] = '	if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");';
											$code[] = '	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");';
											$code[] = '	else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");';
											$code[] = '	else $ipaddress = "UNKNOWN";';
											$code[] = '';
											$code[] = '	$protocol = ((isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] == "on") ? "https" : "http");';
											$code[] = '	$base_url = $protocol . "://" . $_SERVER[\'HTTP_HOST\'];';
											$code[] = '	$complete_url =   $base_url . $_SERVER["REQUEST_URI"];';
											$code[] = '';
											$code[] = '	$affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '';
											$code[] = '	$affiliateData = array(';
											$code[] = '		"order_id"       => $orders->fields[\'orders_id\'],';
											$code[] = '		"order_currency" => $order->info[\'currency\'],';
											$code[] = '		"order_total"    => $order->info[\'total\'],';
											$code[] = '		"product_ids"    => array(),';
											$code[] = '		"af_id"          => $affliate_cookie,';
											$code[] = '		"ip"             => $ipaddress,';
											$code[] = '		"base_url"       => base64_encode($base_url),';
											$code[] = '		"script_name"    => "zencart",';
											$code[] = '	);';
											$code[] = '';
											$code[] = '	foreach ($order->products as $item) { $affiliateData["product_ids"][] = $item["id"]; }';
											$code[] = '	';
											$code[] = '    $context_options = stream_context_create(array(';
											$code[] = '        "http" => array(';
											$code[] = '          "method" => "GET",';
											$code[] = '          "header" => "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '        )';
											$code[] = '    ));';
											$code[] = '	';
											$code[] = '    file_get_contents("'. base_url('integration/addOrder') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											$code[] = '?>';
											echo ___h($code,'php');
											?>
											
										</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'oscommerce'){ ?>
									<p>Integrate affiliate script into oscommerce. get backup of your website and follow following step.</p>

									<ol class="installed-step">
										<li>
											Open file <code class="code_">/includes/template_top.php</code> and add following code at the end of file
											
											<?php
											$code = array();
											$code[] = '<script type="text/javascript" src="'. base_url('integration/oscommerce') .'"></script>';
											echo ___h($code,'html');
											?>
											
										</li>
										<li>
											Open file <code class="code_">product_info.php</code> and add following code after <code class="code_">$product_info = tep_db_fetch_array($product_info_query);</code> this line (around 42 line)
											
											<?php
											$code = array();
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '	$ipaddress = "";';
											$code[] = '	if (getenv("HTTP_CLIENT_IP")) $ipaddress           = getenv("HTTP_CLIENT_IP");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED")) $ipaddress     = getenv("HTTP_X_FORWARDED");';
											$code[] = '	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress   = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_FORWARDED")) $ipaddress       = getenv("HTTP_FORWARDED");';
											$code[] = '	else if(getenv("REMOTE_ADDR")) $ipaddress          = getenv("REMOTE_ADDR");';
											$code[] = '	else $ipaddress                                    = "UNKNOWN";';
											$code[] = '	';
											$code[] = '	$affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '	$current_url = tep_href_link(FILENAME_PRODUCT_INFO, "products_id=" . $product_info["products_id"]);';
											$code[] = '	';
											$code[] = '	$affiliateData = array(';
											$code[] = '		"product_id"       => $product_info["product_id"],';
											$code[] = '		"af_id"            => $affliate_cookie,';
											$code[] = '		"ip"               => $ipaddress,';
											$code[] = '		"base_url"         => base64_encode(tep_href_link(FILENAME_DEFAULT)),';
											$code[] = '		"script_name"      => "oscommerce",';
											$code[] = '		"current_page_url" => base64_encode($current_url),';
											$code[] = '	);';
											$code[] = '	';
											$code[] = '	$context_options = stream_context_create(array(';
											$code[] = '		"http"=>array(';
											$code[] = '			"method"=>"GET",';
											$code[] = '			"header"=> "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '		)';
											$code[] = '	)); ';
											$code[] = '	';
											$code[] = '	file_get_contents("'. base_url('integration/addClick') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											echo ___h($code,'php');
											?>
											
										</li>
										<li>
											Open file <code class="code_">checkout_success.php</code> and add following code after <code class="code_">$orders = tep_db_fetch_array($orders_query);</code> this line (around 27 line)
											
											<?php
											$code = array();
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '    require(DIR_WS_CLASSES . "order.php");';
											$code[] = '    $_order = new order($orders["orders_id"]);';
											$code[] = '	';
											$code[] = '    $ipaddress = "";';
											$code[] = '    if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");';
											$code[] = '    else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '    else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");';
											$code[] = '    else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '    else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");';
											$code[] = '    else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");';
											$code[] = '    else $ipaddress = "UNKNOWN";';
											$code[] = '	';
											$code[] = '    $affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '    $affiliateData = array(';
											$code[] = '      "order_id"       => $orders["orders_id"],';
											$code[] = '      "order_currency" => $_order->info["currency"],';
											$code[] = '      "order_total"    => preg_replace(\'/[^\d\.]/\', "", $_order->info["total"]),';
											$code[] = '      "product_ids"    => array(),';
											$code[] = '      "af_id"          => $affliate_cookie,';
											$code[] = '      "ip"             => $ipaddress,';
											$code[] = '      "base_url"       => base64_encode(tep_href_link(FILENAME_DEFAULT)),';
											$code[] = '      "script_name"    => "oscommerce",';
											$code[] = '    );';
											$code[] = '	';
											$code[] = '    foreach ($_order->products as $item) { $affiliateData["product_ids"][] = $item["id"]; }';
											$code[] = '	';
											$code[] = '    $context_options = stream_context_create(array(';
											$code[] = '        "http" => array(';
											$code[] = '          "method" => "GET",';
											$code[] = '          "header" => "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '        )';
											$code[] = '    ));';
											$code[] = '	';
											$code[] = ' file_get_contents("'. base_url('integration/addOrder') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											echo ___h($code,'php');
											?>
											
										</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'paypal'){ ?>
									<p>PayPal Express Checkout integrates using IPN callback even if the callback is used by other system (e.g. shopping cart).</p>
									
									<ol class="installed-step">
										<li>
											PayPal button
											<ol>
												<li>Now add the following code into EVERY PayPal button form</li>
												<li>
													<?php
													$code = array();
													$code[] = '<input type="hidden" name="custom" value="custom=your_custom_value_here&af_id=<?= $_COOKIE[\'af_id\'] ?>" />';
													echo ___h($code,'html');
													?>
													
												</li>
											</ol>
										</li>
										<li>
											Integration 
											<ol>
												<li>Now the IPN callback is pointed to your script. This callback has to be forwarded also to PAP script, In case, your paypal processing script is in PHP, you can use following code to accomplish that. You can place it at the beginning of your processing file.</li>
												<li>
													<?php
													$code = array();

													$code[] = '/* AFFILIATE PRO integration */';
													$code[] = '	parse_str($_POST["custom"],$_CUSTOM);';
													$code[] = '	$_POST["custom"] = $_CUSTOM["custom"];';
													$code[] = '	$ch = curl_init();';
													$code[] = '	curl_setopt($ch, CURLOPT_URL, "'. base_url('integration/addOrderPaypal') .'");';
													$code[] = '	curl_setopt($ch, CURLOPT_POST, 1);';
													$code[] = '	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);';
													$code[] = '	curl_setopt($ch, CURLOPT_POSTFIELDS, array(';
													$code[] = '		"post"           => json_encode($_POST),';
													$code[] = '		"af_id"          => $_CUSTOM["af_id"],';
													$code[] = '		"order_id"       => "YOUR_ORDER_ID",';
													$code[] = '		"product_ids"    => "PRODUCTS_ID",';
													$code[] = '		"base_url"       => base64_encode("YOUR_WEBSITE_URL"),';
													$code[] = '	));';
													$code[] = '	curl_exec($ch);';
													$code[] = '/* end of AFFILIATE PRO integration */';

													echo ___h($code,'php');
													?>
													
													<p>
														<h6>All possible tracking parameters</h6>
														<div class="well">
															<strong>YOUR_WEBSITE_URL</strong> : Website root URL <br>
															<strong>YOUR_ORDER_ID</strong>    : Unique Order ID <br>
															<strong>PRODUCTS_ID</strong>      : product ids of order, comma separated string <br>
														</div>
													</p>
												</li>
											</ol>
										</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'magento'){ ?>
									<div role="tabpanel">
										<!-- Nav tabs -->
										<ul class="nav nav-pills" role="tablist">
											<li role="presentation" class="nav-item">
												<a href="#magento-1" class="nav-link active" aria-controls="magento-1" role="tab" data-toggle="tab">Magento 1</a>
											</li>
											<li role="presentation" class="nav-item">
												<a href="#magento2" class="nav-link" aria-controls="magento2" role="tab" data-toggle="tab">Magento 2</a>
											</li>
										</ul>
										
										<br>
										<div class="tab-content">
											<div role="tabpanel" class="tab-pane active" id="magento-1">
												<p>Integrate affiliate script into magento 1. download magento module from here <a href="<?= base_url('integration/download_plugin/magento/1') ?>">Magento Module</a> and follow following step. or check <a target='_blank' href="https://docs.mageplaza.com/kb/installation.html">Official document</a></p>
												
												<ol class="installed-step">
													<li>Extract download zip file</li>
													<li>Upload "app" folder to root folder of your magento store</li>
													<li>Check that you have a current backup of your site or create one by going into  <code class="code_">system->tools->backup</code>. This will be useful in case anything goes wrong</li>
													<li>Disable compilations via  <code class="code_">system->tools->Compilations</code></li>
													<li>Clear cache by going into  <code class="code_">System->Cache Management</code>, selecting all the files in the list, choosing the refresh option in the dropdown menu, and finally clicking Submit.</li>
													<li>
														Activate the extension  <code class="code_">System-> Configuration </code> 
														<p>click on <b>Advanced</b> menu from left panel</p>
														<p>Find <b>AffiliatePro_Magento1</b> and enable it</p>
													</li>
												</ol>
											</div>
											<div role="tabpanel" class="tab-pane" id="magento2">
												<p>Integrate affiliate script into magento. download magento module from here <a href="<?= base_url('integration/download_plugin/magento') ?>">Magento Module</a> and follow following step. or check <a target='_blank' href="https://docs.mageplaza.com/kb/installation.html">Official document</a></p>
												
												<ol class="installed-step">
													<li>Extract download zip file</li>
													<li>Upload "AffiliatePro" folder to <code class="code_">/app/code/</code> folder in your magento store</li>
													<li>
														<b> Run Command using php</b>
														<ul>
															<li>Create <code class="code_">cmd.php</code> file into magento root folder </li>
															<li>
																Add following content to cmd.php file

																<?php
																$code = array();
																$code[] = '<?php';
																$code[] = '	exec("php bin/magento setup:upgrade",$o);';
																$code[] = '	exec("php bin/magento setup:static-content:deploy",$o);';
																$code[] = '	echo "Module installed successfully";';
																
																echo ___h($code,'php');
																?>
															</li>
															<li>
																Open cmd.php file into browser using following url
																<code class="code_">http://url_of_magento_store/cmd.php</code>
															</li>
														</ul>
													</li>
												</ol>
											</div>
										</div>
									</div>
								<?php } ?>

								<?php if($module_key == 'opencart'){ ?>
									<p>Integrate affiliate script into opencart. download opencart extentsion from below links and follow following step.</p>

									<br>
									<table class="ml-4">
										<tr>
											<td>For Opencart Version 1564 To 2200 </td>
											<td><a href="<?= base_url('integration/download_plugin/opencart/1') ?>">Download</a></td>
										</tr>
										<tr>
											<td>For Opencart Version 2300 To 3011 </td>
											<td><a href="<?= base_url('integration/download_plugin/opencart/2') ?>">Download</a></td>
										</tr>
									</table>

									<br>
									
									<ol class="installed-step">
										<li>Lets start by logging into your store admin panel. Navigate to <code class="code_">Extensions > Extension installer</code></li>
										<li>Click on the upload button. A dialog box should open.</li>
										<li>Locate the installation zip file of the extension you are going to install and select it.</li>
										<li>After clicking “OK” your extension will be uploaded and a “success” message should appear.</li>
										<li>Now your module should be visible in <code class="code_">Extensions > Modules</code>. After locating it in the Module list just click the install button (“ + ” sign).</li>
										<li>The final step of the installation process is to apply the changes we have just made. In order to do so, go to <code class="code_">Extensions > Modifications</code> and click the Refresh sign at the upper right corner of the page.</li>
									</ol>
								<?php } ?>

								
								<?php if(in_array($module_key, array('general_integration','laravel','codeigniter','cakephp'))){ ?>
									<h2>Common Tracking Script</h2>
									<div>
										<p>Add following script to all pages of your website. include in common file like header or footer</p>
										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										echo ___h($code,'html');
										?>
									</div>
									<br><hr>
									
									<h2>General Click Tracking</h2>
									<div>
										<p>
											Use Following code to track genreal clicks of website.
										</p>

										
										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										$code[] = '<script type="text/javascript">';
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	AffTracker.generalClick( "general_code" );';
										$code[] = '</script>';
										
										echo ___h($code,'html');
										?>

										<p>
											<h6>All possible tracking parameters</h6>
											<div class="well">
												<strong>WebsiteUrl</strong>       : Website root URL <br>
												<strong>general_code</strong> : Unique code of general click like (home,about,contact-us) without any space or special charector.
											</div>
										</p>

										<h6>Avilabel General Click Code is here</h6>
										<ul>
											<?php foreach ($general_codes as $key => $value) { ?>
												<li> <?= $value['general_code'] ?> </li>
											<?php } ?>
										</ul>

									</div>
									
									<br><hr>
									
									
									<h2>CPA - COST PER ACTION</h2>
									<div>
										<p>Any Action like Registration / leads / contuct Form Sent / And any other action, will be on this section per action commissions.</p>
										<p>Under Integrations>>Integration Tools >> Create new Ads [Banner/Text/Link/Video].</p>
										<p>Last Step Is To Insert the JavaScript tracking code to the page that should trigger the action.</p>
										<p>For Example: In Case of "Registration" Action, it should be a page that is displayed after the user register.</p>

										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										$code[] = '<script type="text/javascript">';
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	AffTracker.createAction( "actionCode" )';
										$code[] = '</script>';
										
										echo ___h($code,'html');
										?>

										<p>
											<h6>All possible tracking parameters</h6>
											<div class="well">
												<strong>WebsiteUrl</strong>       : Website root URL <br>
												<strong>actionCode</strong>       : Action code you have added when create a new program tool like Banner Ads/Text Ads/Link Ads/Video Ads<br>
											</div>
										</p>

										<h6>Avilabel Action Code is here</h6>
										<ul>
											<?php foreach ($action_codes as $key => $value) { ?>
												<li> <?= $value['action_code'] ?> </li>
											<?php } ?>
										</ul>
									</div>


									<br><hr>
									
									<h2>Order Tracking</h2>
									<div>
										<p>
											To track whole order,  add following code to your thank you page or order success page
										</p>

										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										$code[] = '<script type="text/javascript">';
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	';
										$code[] = '	AffTracker.setData( "custom_data_1", "value" );';
										$code[] = '	AffTracker.setData( "custom_data_2", "value" );';
										$code[] = '	AffTracker.setData( "custom_data_...", "value" );';
										$code[] = '	';
										$code[] = '	AffTracker.add_order({';
										$code[] = '	    order_id 		: "OrderId",';
										$code[] = '	    order_currency 	: "OrderCurrency",';
										$code[] = '	    order_total		: "OrderTotal",';
										$code[] = '	    product_ids 	: "ProductIDs"';
										$code[] = '	})';
										$code[] = '</script>';
										
										echo ___h($code,'html');
										?>

										<p>
											<h6>All possible tracking parameters</h6>
											<div class="well">
												<strong>WebsiteUrl</strong>       : Website root URL <br>
												<strong>OrderId</strong>       : Unique Order ID <br>
												<strong>OrderCurrency</strong> : Currency Symball of Order <br>
												<strong>OrderTotal</strong>    : Total amount of order <br>
												<strong>ProductIDs</strong>    : product ids of order, comma separated string <br>
											</div>

											<div class="alert alert-info">
												<strong>Script Tag</strong> Script tag is optional if you already added in your header or footer. but header and footer must be include on checkout thank you page
											</div>
										</p>

										<h6>PHP Example</h6>
										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										$code[] = '<script type="text/javascript">';
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	';
										$code[] = '	AffTracker.setData( "custom_data_1", "value" );';
										$code[] = '	AffTracker.setData( "custom_data_2", "value" );';
										$code[] = '	AffTracker.setData( "custom_data_...", "value" );';
										$code[] = '	';
										$code[] = '	AffTracker.add_order({';
										$code[] = '	    order_id 		: "<?php echo $variable_OrderId ?>",';
										$code[] = '	    order_currency 	: "<?php echo $variable_OrderCurrency ?>",';
										$code[] = '	    order_total		: "<?php echo $variable_OrderTotal ?>",';
										$code[] = '	    product_ids 	: "<?php echo $variable_ProductIDs ?>"';
										$code[] = '	})';
										$code[] = '</script>';
										
										echo ___h($code,'html');
										?>
									</br>
								</div>


								<br><hr>

								<h2>Stop recurring payments of order</h2>
									<div>
										<p>
											To stop recurring payments of order,  add following code to stop recurring page for example "stop-membership.php"
										</p>

										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										$code[] = '<script type="text/javascript">';
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	AffTracker.stop_recurring("$variable_OrderId ")';
										$code[] = '</script>';
										
										echo ___h($code,'html');
										?>

										<p>
											<h6>All possible tracking parameters</h6>
											<div class="well">
												<strong>WebsiteUrl</strong> : Website root URL <br>
												<strong>variable_OrderId </strong>    : Unique Order ID <br>
											</div>

											<div class="alert alert-info">
												<strong>OrderId</strong> variable_OrderId  is must match with "Order Tracking" param variable_OrderId 
											</div>
										</p>
									</br>
								</div>



								<br><hr>
								<h2>Product Click Tracking</h2>
								<div>
									<p>
										Use Following code to your product details page so system can track click of products.
									</p>

									
									<?php
									$code = array();
									$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
									$code[] = '<script type="text/javascript">';
									$code[] = '	AffTracker.setWebsiteUrl( "website_url" );';
									$code[] = '	AffTracker.productClick( "ProductID" );';
									$code[] = '</script>';
									
									echo ___h($code,'html');
									?>

									<p>
										<h6>All possible tracking parameters</h6>
										<div class="well">
											<strong>WebsiteUrl</strong>       : Website root URL <br>
											<strong>ProductID</strong> : Unique Product id.
										</div>
									</p>

								</div>
							</br>
						<?php } ?>

						<?php if($module_key == 'shopify'){ ?>
							<p><?= __('admin.integrate_affiliate_script_into_shopify') ?></p>

							<ol class="installed-step">
								<li><?= __('admin.login_goto_shopify_admin_dashboard') ?> </li>

								<li><?= __('admin.go_to') ?> <code class="code_"><?= __('admin.online_store_themes_current_theme_action_edit_code') ?></code>
									<ul class="list-unstyled">
										<li><b>✅ Required Step:</b> <?= __('admin.on_left_side_in') ?> <b><?= __('admin.sections') ?></b> <?= __('admin.section_click_on') ?> <b>header.liquid</b> <?= __('admin.file') ?>.<br>
										Paste the following code at the top of the file:

										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/shopify"></script>';
										echo ___h($code,'html');
										?>
										</li>

										<li>If you're using <strong>Online Store 2.0</strong>, go to <strong>Sections</strong> and locate the <strong>main-product.liquid</strong> file<br>
										If you're using an <strong>older theme</strong>, go to <strong>Templates</strong> and locate the <strong>product.liquid</strong> file

										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/shopify"></script>';
										$code[] = '<script type="text/javascript">';
										$code[] = '  AffTracker.setWebsiteUrl("{{ shop.url }}");';
										$code[] = '  AffTracker.productClick("{{ product.id }}");';
										$code[] = '</script>';
										echo ___h($code,'html');
										?>
										</li>
									</ul>
								</li>

								<li><b>ℹ️ Shopify Basic Plan (No access to Additional Scripts)</b>: You must use <strong>Custom Pixel</strong> to track orders. Follow the steps below:</li>
								<ul>
									<li>Go to <strong>Settings → Customer events → Manage Pixels → Add Custom Pixel</strong>.</li>
									<li>Add this full code:</li>

									<?php
									$code = array();
									$code[] = 'analytics.subscribe("checkout_completed", (event) => {';
									$code[] = '  const orderData = event.data.checkout;';
									$code[] = '  let script = document.createElement("script");';
									$code[] = '  script.src = "'. $base_url .'integration/shopify";';
									$code[] = '  script.onload = function () {';
									$code[] = '    AffTracker.setWebsiteUrl(window.Shopify?.shop || location.hostname);';
									$code[] = '    ';
									$code[] = '    //AffTracker.setData("custom_data_1", "value");';
									$code[] = '    //AffTracker.setData("custom_data_2", "value");';
									$code[] = '    //AffTracker.setData("custom_data_...", "value");';
									$code[] = '    ';
									$code[] = '    AffTracker.add_order({';
									$code[] = '      order_id: orderData.order?.id || orderData.id,';
									$code[] = '      order_currency: orderData.totalPrice?.currencyCode,';
									$code[] = '      order_total: orderData.totalPrice?.amount,';
									$code[] = '      product_ids: (orderData.lineItems || []).map(item => item.product?.id || item.id).join(","),';
									$code[] = '      script_name: "Shopify"';
									$code[] = '    });';
									$code[] = '  };';
									$code[] = '  document.head.appendChild(script);';
									$code[] = '});';
									echo ___h($code,'javascript');
									?>
								</ul>
							</ol>
						<?php } ?>


					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">WPForms Integration</h4>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('user.close') ?></button>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item">Setting a "Thank You" page on wordpress site and connect it to "WPForms" plugin.</br>
							<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/wpform1.png" alt="" style="width:100%;height:100%; margin-right:0; margin-left:0;">
						</li>
						<li class="list-group-item">Adding the integration code to the "Thank You" page.</br>
							<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/thank_you_page_code.png" alt="" style="width:100%;height:100%; margin-right:0; margin-left:0;">
						</li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('user.close') ?></button>
				</div>
			</div>
			
		</div>
	</div>
	
	<!-- Modal bridge Info -->
	<div class="modal fade" id="myModal_bridge" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">WordPress/Woocommerce Bridge Plugin</h5>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('user.close') ?></button>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item">You can set register only wordpress regular registration users or Register only Woocommerce user or both.</br>
							<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/wp_bride_plugin.png" alt="" style="width:100%;height:100%; margin-right:0; margin-left:0;">
						</li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('user.close') ?></button>
				</div>
			</div>
			
		</div>
	</div>