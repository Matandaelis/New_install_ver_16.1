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

<div class="container-fluid">
	<?php get_instance()->load->view('admincontrol/users/_payment_gateway_nav'); ?>
	<div class="row">
		<div class="col-12">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h2 class="mb-1"><?= __('admin.how_to_create_payment_method') ?></h2>
					<p class="text-muted mb-0"><?= __('admin.documentation') ?></p>
				</div>
				<div class="d-flex gap-2">
					<a href="<?= base_url('admincontrol/payment_gateway') ?>" class="btn btn-outline-secondary">
						<i class="bi bi-arrow-left me-2"></i><?= __('admin.back_to_control_panel') ?>
					</a>
					<a href="<?= base_url('admincontrol/payment_gateway_sample_data_to_pdf') ?>" class="btn btn-outline-primary" target="_blank">
						<i class="bi bi-file-code me-2"></i><?= __('admin.payment_gateway_doc_sample_data') ?>
					</a>
					<a href="<?= base_url('admincontrol/payment_gateway_documentation_to_pdf') ?>" class="btn btn-primary" target="_blank">
						<i class="bi bi-file-pdf me-2"></i><?= __('admin.download_as_pdf') ?>
					</a>
				</div>
			</div>

			<div id="doc-html">
				<div class="card">
					<div class="card-header bg-white border-bottom">
						<div class="d-flex align-items-center">
							<i class="bi bi-book me-2 text-primary"></i>
							<div>
								<h5 class="card-title mb-0 text-dark"><?= __('admin.steps_guide') ?></h5>
								<small class="text-muted"><?= __('admin.payment_gateway_doc_info_p1') ?></small>
							</div>
						</div>
					</div>

					<div class="card-body">
						<div class="alert alert-info d-flex align-items-start mb-4">
							<i class="bi bi-info-circle me-3 mt-1"></i>
							<div>
								<h6 class="alert-heading mb-2"><?= __('admin.overview') ?></h6>
								<p class="mb-2"><?= __('admin.payment_gateway_doc_info_p1') ?></p>
								<p class="mb-2"><?= __('admin.payment_gateway_doc_info_p2') ?></p>
								<p class="mb-0"><?= __('admin.payment_gateway_doc_info_p3') ?></p>
							</div>
						</div>

						<div class="row mb-4">
							<div class="col-md-6">
								<div class="card bg-light border-0 h-100">
									<div class="card-body">
										<h6 class="card-title text-primary">
											<i class="bi bi-file-code me-2"></i><?= __('admin.required') ?>
										</h6>
										<ul class="list-unstyled mb-0">
											<li class="d-flex align-items-center mb-2">
												<span class="badge bg-primary me-2">1</span>
												<code>controller/custom.php</code>
											</li>
											<li class="d-flex align-items-center mb-2">
												<span class="badge bg-primary me-2">2</span>
												<code>setting/custom.php</code>
											</li>
											<li class="d-flex align-items-center">
												<span class="badge bg-primary me-2">3</span>
												<code>view/custom.php</code>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card bg-light border-0 h-100">
									<div class="card-body">
										<h6 class="card-title text-success">
											<i class="bi bi-plus-circle me-2"></i><?= __('admin.optional') ?>
										</h6>
										<p class="text-muted small mb-3"><?= __('admin.payment_gateway_doc_info_p4') ?></p>
										<ul class="list-unstyled mb-0">
											<li class="d-flex align-items-center mb-2">
												<span class="badge bg-success me-2">4</span>
												<code>library/custom/</code>
											</li>
											<li class="d-flex align-items-center">
												<span class="badge bg-success me-2">5</span>
												<code>logo/custom.png</code>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>

						<div id="wpg-doc">
							<div class="card mb-4">
								<div class="card-header bg-primary text-white">
									<div class="d-flex align-items-center">
										<span class="badge bg-white text-primary me-3">1</span>
										<div>
											<h5 class="mb-0"><?= __('admin.payment_gateway_doc_controller_folder') ?></h5>
											<small class="opacity-75">Main gateway logic</small>
										</div>
									</div>
								</div>
	    					<div class="card-body">
	    						<p>
	    							<?= __('admin.payment_gateway_doc_controller_info').' '.__('admin.payment_gateway_doc_controller_info_following_url').'.' ?>
	    						</p>
	    						<p><?= __('admin.payment_gateway_doc_sample_data') ?></p>
	    						<ul>
	    							<?php foreach($sample_data as $key => $value): ?>
	    								<li>
		    								<a href="<?= base_url('admincontrol/payment_gateway_documentation_sample_data/'.$value) ?>" target="_blank"><?= $value; ?></a>
		    							</li>
	    							<?php endforeach ?>
	    						</ul>
	    						<p><?= __('admin.payment_gateway_doc_folder_structure') ?></p>
	    						<ul>
	    							<li><strong>custom/controller/custom.php</strong></li>
	    						</ul>
	    						<h6><?= __('admin.payment_gateway_doc_example') ?></h6>
	    						<?php
									$code = array();
									$code[] = '<?php';
									$code[] = '	class custom {';
									$code[] = '		public $title = \'Custom Payment Gateway\';';
									$code[] = '		public $icon = \'assets/payment_gateway/custom.png\';';
									$code[] = '		public $website = \'\';';
									$code[] = '		';
									$code[] = '		function __construct($api){ $this->api = $api; }';
									$code[] = '		';
									$code[] = '		public function getPaymentGatewayView($settingData,$gatewayData){';
									$code[] = '			$view = APPPATH."payment_gateway/views/custom.php";';
									$code[] = '		';
									$code[] = '			require $view;';
									$code[] = '		}';
									$code[] = '		';
									$code[] = '		public function setPaymentGatewayRequest($settingData,$gatewayData){}';
									$code[] = '		';
									$code[] = '		public function customCallbackFunction($settingData,$gatewayData){}';
									$code[] = '	}';
									echo ___h($code,'php');
								?>
								<h6><?= __('admin.payment_gateway_doc_file_explanation') ?>:</h6>
								<div>
									<div>
										<b>Class Name</b> Class name <?= __('admin.payment_gateway_doc_controller_explanation_1') ?>
									</div>
									<div>
										<b>Public Property Title</b> <?= __('admin.payment_gateway_doc_controller_explanation_2') ?>
									</div>
									<div>
										<b>Constructor</b> <?= __('admin.payment_gateway_doc_controller_explanation_3') ?>
									</div>
									<div>
										<b>public function getPaymentGatewayView</b> 
										<?= __('admin.payment_gateway_doc_controller_explanation_4') ?>
									</div>
									<div>
										<b>public function setPaymentGatewayRequest</b> 
										<?= __('admin.payment_gateway_doc_controller_explanation_5') ?>
									</div>
									<div>
										<b>public function customCallbackFunction</b> 
										<?= __('admin.payment_gateway_doc_controller_explanation_6') ?>
									</div>
								</div>
	    					</div>
		    			</div>

							<div class="card mb-4">
								<div class="card-header bg-success text-white">
									<div class="d-flex align-items-center">
										<span class="badge bg-white text-success me-3">2</span>
										<div>
											<h5 class="mb-0"><?= __('admin.payment_gateway_doc_setting_folder') ?></h5>
											<small class="opacity-75">Gateway configuration form</small>
										</div>
									</div>
								</div>
	    					<div class="card-body">
	    						<?= __('admin.payment_gateway_doc_setting_info') ?>
	    						<ul>
	    							<li><strong>custom/setting/custom.php</strong></li>
	    						</ul>
	    						<h6><?= __('admin.payment_gateway_doc_example') ?></h6>
	    						<?php
									$code = array();
									$code[] = '<div class="form-group">';
									$code[] = '	<label class="form-control-label">Some Setting</label>';
									$code[] = '	<input class="form-control" name="name" value="<?= $setting_data["name"] ?>" >';
									$code[] = '</div>';
									$code[] = '';
									$code[] = '<div class="form-group">';
									$code[] = '	<label class="control-label" for="input-completed-status">Completed Status</label>';
									$code[] = '	<select name="completed_status_id" id="input-completed-status" class="form-control">';
									$code[] = '	  <?php foreach ($order_status as $order_status_id => $name){';
									$code[] = '	  		if(isset($setting_data["completed_status_id"]))';
									$code[] = '	    		$selected = ($order_status_id == $setting_data["completed_status_id"]) ? "selected" : "";';
									$code[] = '	  		else';
									$code[] = '     			$selected = ($order_status_id == 1) ? "selected" : ""; ?>';
									$code[] = '     ';
									$code[] = '     		<option <?= $selected ?>  value="<?= $order_status_id; ?>"><?= $name ?></option>';
									$code[] = '	  <?php } ?>';
									$code[] = '	</select>';
									$code[] = '</div>';
									echo ___h($code,'php');
								?>
								<h6><?= __('admin.payment_gateway_doc_file_explanation') ?>:</h6>
								<p><?= __('admin.payment_gateway_doc_setting_explanation') ?></p>
	    					</div>
		    			</div>

							<div class="card mb-4">
								<div class="card-header bg-info text-white">
									<div class="d-flex align-items-center">
										<span class="badge bg-white text-info me-3">3</span>
										<div>
											<h5 class="mb-0"><?= __('admin.payment_gateway_doc_view_folder') ?></h5>
											<small class="opacity-75">Payment interface</small>
										</div>
									</div>
								</div>
	    					<div class="card-body">
	    						<?= __('admin.payment_gateway_doc_view_info') ?>
	    						<ul>
	    							<li><strong>custom/view/custom.php</strong></li>
	    						</ul>
	    						<h6><?= __('admin.payment_gateway_doc_example') ?></h6>
	    						<?php
									$code = array();
									$code[] = '<div class="payment-button-group">';
									$code[] = '	<button type="button" class="btn btn-default" onclick="backCheckout()">Back</button>';
									$code[] = '	<button id="button-confirm" class="btn btn-primary">Confirm</button>';
									$code[] = '</div>';
									$code[] = '<script type="text/javascript">';
									$code[] = '	$("#button-confirm").click(function(){';
									$code[] = '	 $this = $(this);';
									$code[] = '	 $this.prop("disabled",true);';
									$code[] = '		';
									$code[] = '	 $.ajax({';
									$code[] = '		url:"<?= $gatewayData["payment_confirmation"] ?>",';
									$code[] = '		type:"POST",';
									$code[] = '		dataType:"json",';
									$code[] = '		data:$("[name^="comment"]").serialize(),';
									$code[] = '		beforeSend:function(){$("#button-confirm").prop("disabled", true).html("<span class=\"spinner-border spinner-border-sm me-2\"></span>Loading...");},';
									$code[] = '		complete:function(){$("#button-confirm").prop("disabled", false).html($("#button-confirm").data("original-text") || "Confirm");},';
									$code[] = '		success:function(json){';
									$code[] = '			$container = $("#checkout-confirm");';
									$code[] = '			$container.find(".has-error").removeClass("has-error");';
									$code[] = '			$container.find("span.text-danger").remove()';
									$code[] = '		';
									$code[] = '			if(json["errors"]){';
									$code[] = '				$.each(json["errors"]["comment"], function(ii,jj){';
									$code[] = '					$ele = $container.find("#comment_textarea"+ ii);';
									$code[] = '					if($ele){';
									$code[] = '						$ele.parents(".form-group").addClass("has-error");';
									$code[] = '						$ele.after("<span class="text-danger">"+ jj +"</span>");';
									$code[] = '					}';
									$code[] = '				});';
									$code[] = '			}';
									$code[] = '			if(json["success"]){';
									$code[] = '				$.ajax({';
									$code[] = '					url:"<?= $gatewayData["confirm_payment"] ?>",';
									$code[] = '					type:"POST",';
									$code[] = '					dataType:"json",';
									$code[] = '					data:{';
									$code[] = '						payment_gateway: $("input[name="payment_gateway"]:checked").val()';
									$code[] = '					},';
									$code[] = '					beforeSend:function(){$this.prop("disabled", true).html("<span class=\"spinner-border spinner-border-sm me-2\"></span>Loading...");},';
									$code[] = '					complete:function(){$this.prop("disabled", false).html($this.data("original-text") || "Submit");},';
									$code[] = '					success:function(json){';
									$code[] = '						if(json["redirect"])';
									$code[] = '							window.location = json["redirect"];';
									$code[] = '		';
									$code[] = '						if(json["warning"])';
									$code[] = '							alert(json["warning"])';
									$code[] = '					},';
									$code[] = '				});';
									$code[] = '			}';
									$code[] = '	 	},';
									$code[] = '	 });';
									$code[] = '	})';
									$code[] = '</script>';
									echo ___h($code,'php');
								?>
								<h6><?= __('admin.payment_gateway_doc_file_explanation') ?>:</h6>
								<p><?= __('admin.payment_gateway_doc_view_explanation') ?></p>
	    					</div>
		    			</div>

							<div class="row mb-4">
								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header bg-warning text-dark">
											<div class="d-flex align-items-center">
												<span class="badge bg-dark text-warning me-3">4</span>
												<div>
													<h6 class="mb-0"><?= __('admin.payment_gateway_doc_library_folder') ?></h6>
													<small class="opacity-75"><?= __('admin.optional') ?></small>
												</div>
											</div>
										</div>
										<div class="card-body">
											<p class="mb-2"><?= __('admin.payment_gateway_doc_library_info') ?></p>
											<code class="d-block bg-light p-2 rounded">custom/library/custom/</code>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header bg-secondary text-white">
											<div class="d-flex align-items-center">
												<span class="badge bg-white text-secondary me-3">5</span>
												<div>
													<h6 class="mb-0"><?= __('admin.payment_gateway_doc_logo_folder') ?></h6>
													<small class="opacity-75">Gateway branding</small>
												</div>
											</div>
										</div>
										<div class="card-body">
											<p class="mb-2"><?= __('admin.payment_gateway_doc_logo_info') ?></p>
											<code class="d-block bg-light p-2 rounded">custom/logo/custom.png (150x100)</code>
										</div>
									</div>
								</div>
							</div>

							<div class="card mb-4">
								<div class="card-header bg-dark text-white">
									<div class="d-flex align-items-center">
										<i class="bi bi-code-slash me-2"></i>
										<h5 class="mb-0"><?= __('admin.payment_gateway_doc_payment_confirmation') ?></h5>
									</div>
								</div>
								<div class="card-body">
									<div class="bg-light p-3 rounded mb-3">
										<code class="text-dark">$this->api->confirmPaymentGateway($gatewayData['id'],$status_id,$transaction_id,$payment_status);</code>
									</div>
									<p class="mb-0"><?= __('admin.payment_gateway_doc_payment_confirmation_info') ?></p>
								</div>
							</div>

							<div class="card mb-4">
								<div class="card-header bg-primary text-white">
									<div class="d-flex align-items-center">
										<i class="bi bi-list-check me-2"></i>
										<h5 class="mb-0"><?= __('admin.payment_gateway_doc_status_id_and_title') ?></h5>
									</div>
								</div>
								<div class="card-body p-0">
									<div class="table-responsive">
										<table class="table table-striped mb-0">
											<thead class="table-dark">
												<tr>
													<th><?= __('admin.status_id') ?></th> 
													<th><?= __('admin.title') ?></th>
												</tr>
											</thead>
											<tbody>
												<tr><td><span class="badge bg-secondary">0</span></td><td><?= __('admin.received') ?></td></tr>
												<tr><td><span class="badge bg-success">1</span></td><td><?= __('admin.complete') ?></td></tr>
												<tr><td><span class="badge bg-warning">2</span></td><td><?= __('admin.total_not_match') ?></td></tr>
												<tr><td><span class="badge bg-danger">3</span></td><td><?= __('admin.denied') ?></td></tr>
												<tr><td><span class="badge bg-danger">4</span></td><td><?= __('admin.expired') ?></td></tr>
												<tr><td><span class="badge bg-danger">5</span></td><td><?= __('admin.failed') ?></td></tr>
												<tr><td><span class="badge bg-info">7</span></td><td><?= __('admin.processed') ?></td></tr>
												<tr><td><span class="badge bg-warning">8</span></td><td><?= __('admin.refunded') ?></td></tr>
												<tr><td><span class="badge bg-warning">9</span></td><td><?= __('admin.reversed') ?></td></tr>
												<tr><td><span class="badge bg-secondary">10</span></td><td><?= __('admin.voided') ?></td></tr>
												<tr><td><span class="badge bg-info">11</span></td><td><?= __('admin.cancel_reversal') ?></td></tr>
												<tr><td><span class="badge bg-warning">12</span></td><td><?= __('admin.waiting_for_payment') ?></td></tr>
												<tr><td><span class="badge bg-secondary">13</span></td><td><?= __('admin.pending') ?></td></tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<div class="card mb-4">
								<div class="card-header bg-success text-white">
									<div class="d-flex align-items-center">
										<i class="bi bi-file-zip me-2"></i>
										<h5 class="mb-0"><?= __('admin.payment_gateway_doc_zip_file') ?></h5>
									</div>
								</div>
								<div class="card-body">
									<p class="mb-3"><?= __('admin.payment_gateway_doc_zip_info') ?></p>
									<div class="alert alert-success d-flex align-items-start">
										<i class="bi bi-check-circle me-3 mt-1"></i>
										<div>
											<h6 class="alert-heading mb-2">Final Structure</h6>
											<ul class="list-unstyled mb-0">
												<li><i class="bi bi-file-code me-2 text-primary"></i><code>custom/controller/custom.php</code></li>
												<li><i class="bi bi-gear me-2 text-success"></i><code>custom/setting/custom.php</code></li>
												<li><i class="bi bi-eye me-2 text-info"></i><code>custom/view/custom.php</code></li>
												<li><i class="bi bi-folder me-2 text-warning"></i><code>custom/library/custom/</code></li>
												<li><i class="bi bi-image me-2 text-secondary"></i><code>custom/logo/custom.png</code></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= performance_indicator_html('gateway-docs-performance') ?>
<?= render_performance_indicator('gateway-docs-performance') ?>

<script type="text/javascript">
	$(document).ready(function() {
		PerformanceMonitor.start();
		PerformanceMonitor.end();
	});
</script>