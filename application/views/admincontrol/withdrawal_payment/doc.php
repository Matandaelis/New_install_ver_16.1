<link rel="stylesheet" type="text/css" href="<?= base_url('assets/integration/prism/css.css') ?>?v=<?= av() ?>">
<script type="text/javascript" src="<?= base_url('assets/integration/prism/js.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/html2canvas/html2canvas.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/html2canvas/jspdf.debug.js') ?>"></script>
<script type="text/javascript">
	function download(){
		$(".no-pdf").hide();
		$(".btn-export-pdf").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

		var HTML_Width = $("#doc-html").width();
		var HTML_Height = $("#doc-html").height();
		var top_left_margin = 15;
		var PDF_Width = HTML_Width+(top_left_margin*2);
		var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
		var canvas_image_width = HTML_Width;
		var canvas_image_height = HTML_Height;
		
		var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;

		html2canvas($("#doc-html")[0],{allowTaint:true}).then(function(canvas) {
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
		    $(".btn-export-pdf").prop('disabled', false).html($(this).data('original-text') || 'Submit');
        });
	}
</script>
<?php 
	function ___h($text,$lan){
		$text = implode("\n", $text);
		$text = htmlentities($text);
		$text = '<pre class="language-'.$lan.'"><code class="language-'.$lan.'">'.$text.'</code></pre>';
		return $text;
	}

	$base_url  = base_url();
?>
<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/users/_wallet_nav'); ?>
    <div class="row">
        <div class="col-12">
<div id="doc-html">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-semibold"><?= __('admin.how_to_create_payment_method') ?></h5>
                            <button type="button" onclick="download()" class="btn btn-outline-secondary btn-sm btn-export-pdf">
                                <i class="fas fa-download me-1"></i><?= __('admin.download_as_pdf') ?>
                            </button>
                        </div>
                    </div>
                    <div class="card-body payment-doc">
		    		<p>There are several payment methods available, but sometimes you'll find yourself in a situation where you need something different, either there is no method available for your choice of payment gateway or you want some different logic. In either case, you're left with the only option: To create a new payment method module.</p>

		    		<p>We'll assume that our custom payment method name is "custom". You need to create five files (some are optional) in order to set up a complete payment gateway. Let's check the same in detail.</p>

		    		<p>Following is the required folder structure for creating a payment gateway:</p>
		    		<div class="alert alert-info">
		    			<h6 class="fw-semibold mb-3">
		    				<i class="fas fa-folder-tree me-2"></i>Required Folder Structure:
		    			</h6>
		    			<div class="row">
		    				<div class="col-md-6">
		    					<ul class="list-group list-group-flush">
		    						<li class="list-group-item d-flex align-items-center">
		    							<i class="fas fa-file-code text-primary me-2"></i>
		    							<code>controllers/custom.php</code>
		    						</li>
		    						<li class="list-group-item d-flex align-items-center">
		    							<i class="fas fa-cog text-success me-2"></i>
		    							<code>admin_settings/custom.php</code>
		    						</li>
		    						<li class="list-group-item d-flex align-items-center">
		    							<i class="fas fa-user-cog text-warning me-2"></i>
		    							<code>user_settings/custom.php</code>
		    						</li>
		    					</ul>
		    				</div>
		    				<div class="col-md-6">
		    					<ul class="list-group list-group-flush">
		    						<li class="list-group-item d-flex align-items-center">
		    							<i class="fas fa-eye text-info me-2"></i>
		    							<code>confirm_view/custom.php</code>
		    							<span class="badge bg-secondary ms-2">Optional</span>
		    						</li>
		    						<li class="list-group-item d-flex align-items-center">
		    							<i class="fas fa-image text-dark me-2"></i>
		    							<code>logo/custom.png</code>
		    						</li>
		    					</ul>
		    				</div>
		    			</div>
		    		</div>

		    		<div class="card mb-4 border-success">
		    			<div class="card-header bg-success text-white">
		    				<h5 class="card-title mb-0 fw-semibold">
		    					<i class="fas fa-graduation-cap me-2"></i>🎓 Complete Beginner Tutorial
		    				</h5>
		    			</div>
		    			<div class="card-body">
		    				<div class="alert alert-info mb-4">
		    					<h6 class="fw-semibold mb-2">
		    						<i class="fas fa-info-circle me-2"></i>What You'll Learn
		    					</h6>
		    					<p class="mb-0">This tutorial will guide you step-by-step to create a custom payment gateway called <strong>"MyWallet"</strong> that accepts wallet addresses from users and allows admins to process payments.</p>
		    				</div>

		    				<h6 class="fw-semibold text-primary mb-3">
		    					<i class="fas fa-list-ol me-2"></i>Step-by-Step Instructions:
		    				</h6>

		    				<div class="accordion" id="tutorialAccordion">
		    					<!-- Step 1 -->
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="step1">
		    							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
		    								<i class="fas fa-folder-plus text-primary me-2"></i><strong>Step 1:</strong> Create Folder Structure
		    							</button>
		    						</h2>
		    						<div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#tutorialAccordion">
		    							<div class="accordion-body">
		    								<p>Create these folders in <code>application/withdrawal_payment/</code>:</p>
		    								<div class="bg-light p-3 rounded">
		    									<code>application/withdrawal_payment/controllers/mywallet.php</code><br>
		    									<code>application/withdrawal_payment/admin_settings/mywallet.php</code><br>
		    									<code>application/withdrawal_payment/user_settings/mywallet.php</code><br>
		    									<code>application/withdrawal_payment/confirm_view/mywallet.php</code><br>
		    									<code>application/withdrawal_payment/logo/mywallet.png</code>
		    								</div>
		    								<div class="alert alert-warning mt-2">
		    									<i class="fas fa-exclamation-triangle me-2"></i><strong>Important:</strong> Replace "mywallet" with your actual gateway name (lowercase, no spaces)
		    								</div>
		    							</div>
		    						</div>
		    					</div>

		    					<!-- Step 2 -->
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="step2">
		    							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
		    								<i class="fas fa-code text-success me-2"></i><strong>Step 2:</strong> Create Controller File
		    							</button>
		    						</h2>
		    						<div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
		    							<div class="accordion-body">
		    								<p>Copy this code to <code>controllers/mywallet.php</code>:</p>
		    								<div class="bg-dark text-light p-3 rounded">
		    									<small>
&lt;?php<br>
class Mywallet {<br>
&nbsp;&nbsp;&nbsp;&nbsp;public $title = "My Wallet Gateway";<br>
&nbsp;&nbsp;&nbsp;&nbsp;public $api;<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;function __construct(){<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->api = &get_instance();<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;public function onInstall(){<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return true;<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;public function onUnInstall(){<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return true;<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;public function saveUserSubmit(){<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$data = $this->api->input->post(null,true);<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$json = array();<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Validation<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (!isset($data['mywallet_address']) || trim($data['mywallet_address']) == '') {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$json['errors']['mywallet_address'] = 'Wallet address is required';<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (empty($json['errors'])) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Save payment details<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$payment_details = array();<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;foreach ($data as $key => $value) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (strpos($key, 'mywallet_') === 0) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$field_name = substr($key, strlen('mywallet_'));<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$payment_details[$field_name] = $value;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$saveSetting = ['payment_details' => $payment_details];<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->api->load->model('Withdrawal_payment_model');<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$status = $this->api->Withdrawal_payment_model->apiAddWithdrwalRequest($data['code'], $data['ids'], $saveSetting);<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if ((int)$status['status'] == 1) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$json['success'] = 1;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;} else {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$json['errors']['general'] = $status['error_message'];<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return $json;<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br>
}<br>
?&gt;
		    									</small>
		    								</div>
		    							</div>
		    						</div>
		    					</div>

		    					<!-- Step 3 -->
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="step3">
		    							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
		    								<i class="fas fa-cog text-warning me-2"></i><strong>Step 3:</strong> Create Admin Settings
		    							</button>
		    						</h2>
		    						<div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
		    							<div class="accordion-body">
		    								<p>Copy this code to <code>admin_settings/mywallet.php</code>:</p>
		    								<div class="bg-dark text-light p-3 rounded">
		    									<small>
&lt;div class="form-group"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;label class="form-control-label"&gt;Gateway Status&lt;/label&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;select class="form-control" name="status"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;option value="1" &lt;?= ($setting_data["status"] == "1") ? "selected" : "" ?&gt;&gt;Active&lt;/option&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;option value="0" &lt;?= ($setting_data["status"] == "0") ? "selected" : "" ?&gt;&gt;Inactive&lt;/option&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/select&gt;<br>
&lt;/div&gt;<br><br>
&lt;div class="form-group"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;label class="form-control-label"&gt;Gateway Instructions&lt;/label&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;textarea class="form-control" name="instructions" rows="3"&gt;&lt;?= $setting_data["instructions"] ?&gt;&lt;/textarea&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;small class="form-text text-muted"&gt;Instructions shown to users&lt;/small&gt;<br>
&lt;/div&gt;
		    									</small>
		    								</div>
		    							</div>
		    						</div>
		    					</div>

		    					<!-- Step 4 -->
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="step4">
		    							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
		    								<i class="fas fa-user text-info me-2"></i><strong>Step 4:</strong> Create User Settings Form
		    							</button>
		    						</h2>
		    						<div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
		    							<div class="accordion-body">
		    								<p>Copy this code to <code>user_settings/mywallet.php</code>:</p>
		    								<div class="bg-dark text-light p-3 rounded">
		    									<small>
&lt;div class="form-group"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;label class="form-control-label"&gt;Wallet Address *&lt;/label&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;input class="form-control" name="mywallet_address" value="&lt;?= $user_data['address'] ?? '' ?&gt;" placeholder="Enter your wallet address"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;small class="form-text text-muted"&gt;Enter your wallet address for receiving payments&lt;/small&gt;<br>
&lt;/div&gt;<br><br>
&lt;div class="form-group"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;label class="form-control-label"&gt;Wallet Type&lt;/label&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;select class="form-control" name="mywallet_type"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;option value="bitcoin" &lt;?= ($user_data['type'] == 'bitcoin') ? 'selected' : '' ?&gt;&gt;Bitcoin&lt;/option&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;option value="ethereum" &lt;?= ($user_data['type'] == 'ethereum') ? 'selected' : '' ?&gt;&gt;Ethereum&lt;/option&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;option value="other" &lt;?= ($user_data['type'] == 'other') ? 'selected' : '' ?&gt;&gt;Other&lt;/option&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/select&gt;<br>
&lt;/div&gt;
		    									</small>
		    								</div>
		    								<div class="alert alert-info mt-2">
		    									<i class="fas fa-lightbulb me-2"></i><strong>Note:</strong> Form field names MUST start with your gateway code (mywallet_)
		    								</div>
		    							</div>
		    						</div>
		    					</div>

		    					<!-- Step 5 -->
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="step5">
		    							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
		    								<i class="fas fa-eye text-danger me-2"></i><strong>Step 5:</strong> Create Actions Interface (Optional)
		    							</button>
		    						</h2>
		    						<div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
		    							<div class="accordion-body">
		    								<p>Copy this code to <code>confirm_view/mywallet.php</code>:</p>
		    								<div class="bg-dark text-light p-3 rounded">
		    									<small>
&lt;?php if (isset($request)) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;$settings = json_decode($request['settings'], true);<br>
&nbsp;&nbsp;&nbsp;&nbsp;$user_data = isset($settings['payment_details']) ? $settings['payment_details'] : [];<br>
&nbsp;&nbsp;&nbsp;&nbsp;$request_id = $request['id'];<br>
?&gt;<br>
&lt;div class="card"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="card-header bg-primary text-white"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;h5 class="card-title mb-0"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class="fas fa-wallet me-2"&gt;&lt;/i&gt;My Wallet Payment Processing<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/h5&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="card-body"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="row mb-3"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="col-md-6"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;strong&gt;Amount:&lt;/strong&gt; &lt;?= c_format($request['total']) ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="col-md-6"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;strong&gt;Wallet Address:&lt;/strong&gt; &lt;?= $user_data['address'] ?? 'N/A' ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="d-flex gap-2"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;button type="button" class="btn btn-success" onclick="updateStatus(&lt;?= $request_id ?&gt;, 1)"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class="fas fa-check me-2"&gt;&lt;/i&gt;Mark as Completed<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/button&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;button type="button" class="btn btn-danger" onclick="updateStatus(&lt;?= $request_id ?&gt;, 5)"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class="fas fa-times me-2"&gt;&lt;/i&gt;Mark as Failed<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/button&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
&lt;/div&gt;<br>
&lt;script&gt;<br>
function updateStatus(requestId, statusId) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;$.ajax({<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;url: '&lt;?= base_url("admincontrol/wallet_requests_details/") ?&gt;' + requestId,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;type: 'POST',<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;data: { status: statusId, comment: 'Updated via My Wallet actions' },<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;success: function(response) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (response.success) window.location.reload();<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>
&nbsp;&nbsp;&nbsp;&nbsp;});<br>
}<br>
&lt;/script&gt;<br>
&lt;?php } ?&gt;
		    									</small>
		    								</div>
		    							</div>
		    						</div>
		    					</div>

		    					<!-- Step 6 -->
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="step6">
		    							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
		    								<i class="fas fa-upload text-dark me-2"></i><strong>Step 6:</strong> Install Your Gateway
		    							</button>
		    						</h2>
		    						<div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
		    							<div class="accordion-body">
		    								<ol>
		    									<li><strong>Add a logo:</strong> Place a PNG image in <code>logo/mywallet.png</code></li>
		    									<li><strong>Test locally:</strong> Check all files are in correct locations</li>
		    									<li><strong>Go to Admin Panel:</strong> Navigate to Payment Gateway Settings</li>
		    									<li><strong>Enable your gateway:</strong> Find "My Wallet Gateway" and enable it</li>
		    									<li><strong>Configure settings:</strong> Set status to Active and add instructions</li>
		    									<li><strong>Test as user:</strong> Go to user panel and test the payment form</li>
		    								</ol>
		    								<div class="alert alert-success">
		    									<i class="fas fa-check-circle me-2"></i><strong>Congratulations!</strong> Your custom payment gateway is now ready!
		    								</div>
		    							</div>
		    						</div>
		    					</div>
		    				</div>
		    			</div>
		    		</div>

		    		<div class="alert alert-success mb-4">
		    			<h6 class="fw-semibold mb-2">
		    				<i class="fas fa-lightbulb me-2"></i>Quick Start Guide
		    			</h6>
		    			<ol class="mb-0">
		    				<li><strong>Download the demo gateway</strong> to see a working example</li>
		    				<li><strong>Study the code structure</strong> in each file</li>
		    				<li><strong>Replace "custom"</strong> with your gateway name throughout all files</li>
		    				<li><strong>Modify the form fields</strong> in admin_settings and user_settings</li>
		    				<li><strong>Follow the unified data storage system</strong> (see Data Storage section below)</li>
		    				<li><strong>Add translation keys</strong> for all user-facing text (see Translation Guidelines below)</li>
		    				<li><strong>Test thoroughly</strong> before deploying</li>
		    			</ol>
		    		</div>

		    		<div class="card mb-4">
		    			<div class="card-header bg-success text-white">
		    				<h5 class="card-title mb-0 fw-semibold">
		    					<i class="fas fa-database me-2"></i>🆕 Unified Data Storage System
		    				</h5>
		    			</div>
		    			<div class="card-body">
		    				<p class="mb-3">All custom payment gateways now use a unified data storage system that automatically handles saving and loading user payment data.</p>
		    				
		    				<div class="alert alert-info">
		    					<h6 class="fw-semibold mb-2">
		    						<i class="fas fa-magic me-2"></i>Automatic Data Handling
		    					</h6>
		    					<p class="mb-2">The system automatically saves and loads user payment data for any custom gateway. You don't need to write custom database code!</p>
		    					<p class="mb-0"><strong>How it works:</strong> The system uses the <code>user_payment_details</code> table to store all custom gateway data in a flexible key-value format.</p>
		    				</div>

		    				<h6 class="fw-semibold mb-3">Form Field Naming Convention</h6>
		    				<div class="alert alert-warning">
		    					<p class="mb-2"><strong>Important:</strong> All form fields must be named using this pattern:</p>
		    					<code>{gateway_code}_{field_name}</code>
		    				</div>
		    				
		    				<div class="row">
		    					<div class="col-md-6">
		    						<div class="alert alert-light">
		    							<h6 class="fw-semibold mb-2">✅ Correct Examples:</h6>
		    							<code>stripe_api_key</code><br>
		    							<code>stripe_secret_key</code><br>
		    							<code>paypal_client_id</code><br>
		    							<code>crypto_wallet_address</code>
		    						</div>
		    					</div>
		    					<div class="col-md-6">
		    						<div class="alert alert-light">
		    							<h6 class="fw-semibold mb-2">❌ Wrong Examples:</h6>
		    							<code>api_key</code><br>
		    							<code>stripe-api-key</code><br>
		    							<code>stripe api key</code><br>
		    							<code>API_KEY</code>
		    						</div>
		    					</div>
		    				</div>

		    				<h6 class="fw-semibold mb-3">User Settings Form Example</h6>
		    				<div class="alert alert-light">
		    					<pre><code>&lt;div class="form-group row"&gt;
    &lt;label class="col-sm-2 col-form-label"&gt;&lt;?= __('admin.stripe_api_key_label') ?&gt;&lt;/label&gt;
    &lt;div class="col-sm-10"&gt;
        &lt;input name="stripe_api_key" class="form-control" 
               value="&lt;?= isset($gateway_data_for_form['api_key']) ? $gateway_data_for_form['api_key'] : '' ?&gt;" 
               placeholder="&lt;?= __('admin.stripe_api_key_placeholder') ?&gt;" required&gt;
    &lt;/div&gt;
&lt;/div&gt;</code></pre>
		    				</div>

		    				<h6 class="fw-semibold mb-3">Controller Requirements</h6>
		    				<div class="alert alert-light">
		    					<p class="mb-2">Your gateway controller must include a <code>savePaymentDetails()</code> method:</p>
		    					<pre><code>public function savePaymentDetails() {
    $data = $this->api->input->post(null, true);
    $userdetails = $this->api->userdetails();
    
    // Basic validation (customize as needed)
    if (empty($data['api_key'])) {
        return ['error' => 1, 'message' => __('admin.stripe_api_key_required')];
    }
    
    // The system automatically saves data - no custom code needed!
    return ['success' => 1, 'message' => 'Payment details saved successfully'];
}</code></pre>
		    				</div>

		    				<div class="alert alert-success">
		    					<h6 class="fw-semibold mb-2">
		    						<i class="fas fa-check-circle me-2"></i>Benefits of Unified System
		    					</h6>
		    					<ul class="mb-0">
		    						<li><strong>Consistent:</strong> All custom gateways use the same data storage</li>
		    						<li><strong>Automatic:</strong> No custom database code required</li>
		    						<li><strong>Flexible:</strong> Supports any number of fields per gateway</li>
		    						<li><strong>Scalable:</strong> Easy to add new gateways</li>
		    						<li><strong>Future-proof:</strong> Works with any payment method</li>
		    					</ul>
		    				</div>
		    			</div>
		    		</div>

		    		<div class="card mb-4">
		    			<div class="card-header bg-warning text-dark">
		    				<h5 class="card-title mb-0 fw-semibold">
		    					<i class="fas fa-language me-2"></i>Translation Guidelines
		    				</h5>
		    			</div>
		    			<div class="card-body">
		    				<p class="mb-3">To ensure your gateway works with multiple languages, follow these translation guidelines:</p>
		    				
		    				<div class="alert alert-info">
		    					<h6 class="fw-semibold mb-2">
		    						<i class="fas fa-magic me-2"></i>Automatic Translation Integration
		    					</h6>
		    					<p class="mb-2">The system automatically detects and adds translation keys when you install a gateway. You don't need to manually add translations to language files!</p>
		    					<p class="mb-0"><strong>How it works:</strong> When you install a gateway, the system scans all gateway files for translation keys and automatically adds them to all active language files with default values.</p>
		    				</div>

		    				<h6 class="fw-semibold mb-3">Translation Key Format</h6>
		    				<div class="row">
		    					<div class="col-md-6">
		    						<div class="alert alert-light">
		    							<h6 class="fw-semibold mb-2">✅ Correct Format:</h6>
		    							<code>__('admin.your_gateway_key_name')</code><br>
		    							<code>__('client.your_gateway_key_name')</code><br>
		    							<code>__('user.your_gateway_key_name')</code>
		    						</div>
		    					</div>
		    					<div class="col-md-6">
		    						<div class="alert alert-light">
		    							<h6 class="fw-semibold mb-2">❌ Wrong Format:</h6>
		    							<code>__('your_gateway_key_name')</code><br>
		    							<code>__('admin.your gateway key')</code><br>
		    							<code>__('admin.your-gateway-key')</code>
		    						</div>
		    					</div>
		    				</div>

		    				<h6 class="fw-semibold mb-3">Naming Convention</h6>
		    				<div class="table-responsive">
		    					<table class="table table-bordered">
		    						<thead class="table-dark">
		    							<tr>
		    								<th>Key Type</th>
		    								<th>Format</th>
		    								<th>Example</th>
		    								<th>Description</th>
		    							</tr>
		    						</thead>
		    						<tbody>
		    							<tr>
		    								<td>Gateway Prefix</td>
		    								<td><code>your_gateway_</code></td>
		    								<td><code>stripe_</code></td>
		    								<td>Always prefix with your gateway name</td>
		    							</tr>
		    							<tr>
		    								<td>Labels</td>
		    								<td><code>_label</code></td>
		    								<td><code>stripe_api_key_label</code></td>
		    								<td>Form field labels</td>
		    							</tr>
		    							<tr>
		    								<td>Placeholders</td>
		    								<td><code>_placeholder</code></td>
		    								<td><code>stripe_api_key_placeholder</code></td>
		    								<td>Input placeholders</td>
		    							</tr>
		    							<tr>
		    								<td>Help Text</td>
		    								<td><code>_help</code></td>
		    								<td><code>stripe_api_key_help</code></td>
		    								<td>Help text and descriptions</td>
		    							</tr>
		    							<tr>
		    								<td>Errors</td>
		    								<td><code>_required</code>, <code>_invalid</code></td>
		    								<td><code>stripe_api_key_required</code></td>
		    								<td>Validation error messages</td>
		    							</tr>
		    						</tbody>
		    					</table>
		    				</div>

		    				<h6 class="fw-semibold mb-3">Example Implementation</h6>
		    				<div class="row">
		    					<div class="col-md-6">
		    						<div class="card">
		    							<div class="card-header bg-light">
		    								<h6 class="mb-0">Admin Settings (admin_settings/custom.php)</h6>
		    							</div>
		    							<div class="card-body">
		    								<pre class="mb-0"><code>&lt;div class="form-group"&gt;
    &lt;label class="form-control-label"&gt;
        &lt;?= __('admin.custom_api_key_label') ?&gt;
    &lt;/label&gt;
    &lt;input class="form-control" 
           name="api_key" 
           placeholder="&lt;?= __('admin.custom_api_key_placeholder') ?&gt;"&gt;
    &lt;small class="form-text text-muted"&gt;
        &lt;?= __('admin.custom_api_key_help') ?&gt;
    &lt;/small&gt;
&lt;/div&gt;</code></pre>
		    							</div>
		    						</div>
		    					</div>
		    					<div class="col-md-6">
		    						<div class="card">
		    							<div class="card-header bg-light">
		    								<h6 class="mb-0">Controller (controllers/custom.php)</h6>
		    							</div>
		    							<div class="card-body">
		    								<pre class="mb-0"><code>if (!isset($data['api_key']) || trim($data['api_key']) == '') {
    $json['errors']['api_key'] = __('admin.custom_api_key_required');
}

if (!isset($data['secret_key']) || trim($data['secret_key']) == '') {
    $json['errors']['secret_key'] = __('admin.custom_secret_key_required');
}</code></pre>
		    							</div>
		    						</div>
		    					</div>
		    				</div>

		    				<div class="alert alert-success mt-3">
		    					<h6 class="fw-semibold mb-2">
		    						<i class="fas fa-check-circle me-2"></i>What Happens When You Install
		    					</h6>
		    					<ol class="mb-0">
		    						<li>System scans all your gateway files for translation keys</li>
		    						<li>Automatically adds missing keys to all active language files</li>
		    						<li>Generates default values like "[custom] Api Key Label"</li>
		    						<li>Admin can then edit translations in the language management section</li>
		    						<li>When uninstalling, translations are automatically removed</li>
		    		</ol>
		    				</div>
		    		</div>

		    		<div id="wpg-doc">
		    			<div class="card mb-3">
		    				<div class="card-header bg-info text-white">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-folder me-2"></i>#1 Controllers Folder
		    					</h5>
		    				</div>
	    					<div class="card-body">
	    						This folder contains the custom.php file.<br>
	    						This file contains all the logic of your payment gateway. Some functions are required in this file as listed below.

	    						<h6 class="fw-semibold text-primary mb-3">
	    							<i class="fas fa-code me-2"></i>Example for custom.php
	    						</h6>
	    						<?php
									$code = array();
									$code[] = '<?php';
									$code[] = '	class custom {';
									$code[] = '		public $title = \'Custom Payment Gateway\';';
									$code[] = '		public $website = \'\';';
									$code[] = '		';
									$code[] = '		function __construct($api){ $this->api = $api; }';
									$code[] = '		';
									$code[] = '		public function onInstall() {}';
									$code[] = '		public function onUnInstall() {}';
									$code[] = '		public function savePaymentDetails() {';
									$code[] = '			$data = $this->api->input->post(null,true);';
									$code[] = '			$userdetails = $this->api->userdetails();';
									$code[] = '			';
									$code[] = '			// Basic validation (customize as needed)';
									$code[] = '			if (empty($data[\'custom_name\'])) {';
									$code[] = '				return [\'error\' => 1, \'message\' => __(\'admin.custom_name_required\')];';
									$code[] = '			}';
									$code[] = '			';
									$code[] = '			// The system automatically saves data using the unified storage system';
									$code[] = '			// No custom database code needed!';
									$code[] = '			return [\'success\' => 1, \'message\' => \'Payment details saved successfully\'];';
									$code[] = '		}';
									$code[] = '		';
									$code[] = '		// Required: Handle withdrawal form submission';
									$code[] = '		public function saveUserSubmit() {';
									$code[] = '			$data = $this->api->input->post(null, true);';
									$code[] = '			$json = [];';
									$code[] = '			';
									$code[] = '			// Basic validation';
									$code[] = '			if (empty($data[\'custom_name\'])) {';
									$code[] = '				$json[\'errors\'][\'custom_name\'] = __(\'admin.custom_name_required\');';
									$code[] = '			}';
									$code[] = '			';
									$code[] = '			if (!isset($json[\'errors\'])) {';
									$code[] = '				// Prepare data for withdrawal request';
									$code[] = '				$payment_details = [];';
									$code[] = '				foreach ($data as $key => $value) {';
									$code[] = '					if ($key != \'code\' && $key != \'ids\') {';
									$code[] = '						$payment_details[$key] = $value;';
									$code[] = '					}';
									$code[] = '				}';
									$code[] = '				';
									$code[] = '				// Wrap payment details in settings structure';
									$code[] = '				$saveSetting = [\'payment_details\' => $payment_details];';
									$code[] = '				';
									$code[] = '				// Create withdrawal request';
									$code[] = '				$this->api->load->model(\'Withdrawal_payment_model\');';
									$code[] = '				$status = $this->api->Withdrawal_payment_model->apiAddWithdrwalRequest($data[\'code\'], $data[\'ids\'], $saveSetting);';
									$code[] = '				';
									$code[] = '				if ((int)$status[\'status\'] == 1) {';
									$code[] = '					$json[\'success\'] = 1;';
									$code[] = '				} else {';
									$code[] = '					$json[\'errors\'][\'custom_details\'] = $status[\'error_message\'];';
									$code[] = '				}';
									$code[] = '			}';
									$code[] = '			';
									$code[] = '			return $json;';
									$code[] = '		}';
									$code[] = '		';
									$code[] = '		// Optional: Custom withdrawal processing logic';
									$code[] = '		public function processWithdrawal($amount, $user_id) {';
									$code[] = '			// Add your custom withdrawal processing logic here';
									$code[] = '			return [\'success\' => 1, \'transaction_id\' => \'TXN123456\'];';
									$code[] = '		}';
									$code[] = '	}';
									echo ___h($code,'php');
								?>

								<h6 class="fw-semibold text-success mb-3">
									<i class="fas fa-info-circle me-2"></i>Explanation of file:
								</h6>
								<div class="row">
									<div class="col-md-6 mb-2">
										<div class="alert alert-light border-start border-primary border-3">
											<strong>The class name</strong> must be file name
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="alert alert-light border-start border-success border-3">
											<strong>Public Property Title</strong> Name of payment gateway
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="alert alert-light border-start border-info border-3">
											<strong>The constructor</strong> must be as it is. API variable contains this object of CI. you can use the functionality of CI
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="alert alert-light border-start border-warning border-3">
											<strong>Public Function onInstall</strong> will be called when the plugin is installed.
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="alert alert-light border-start border-danger border-3">
											<strong>Public Function onUnInstall</strong> will be called when the plugin is uninstalled.
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="alert alert-light border-start border-dark border-3">
											<strong>Required function: savePaymentDetails</strong> handles user payment data saving. The system automatically saves data using the unified storage system. No custom database code needed!
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="alert alert-light border-start border-success border-3">
											<strong>Required function: saveUserSubmit</strong> handles withdrawal form submission from the user interface. This method validates data and creates withdrawal requests.
										</div>
									</div>
									<div class="col-md-6 mb-2">
										<div class="alert alert-light border-start border-info border-3">
											<strong>Optional function: processWithdrawal</strong> handles actual withdrawal processing. Add your custom payment gateway API calls here.
										</div>
									</div>
								</div>
	    					</div>
		    			</div>


		    			<div class="card mb-3">
		    				<div class="card-header bg-success text-white">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-cog me-2"></i>#2 Admin Settings Folder
		    					</h5>
		    				</div>
	    					<div class="card-body">
	    						This folder contains the custom.php file.<br>
	    						The custom.php file is used for setting up admin configurations. Sometimes you need to ask for information from the admin, for example, credentials of the payment gateway or other settings.<br>
	    						You just need to create an input-output system that will auto-create a setting page and save setting data.<br>
	    						In this container, the $setting_data variable contains all saved settings of your payment gateway.

	    						<h6 class="fw-semibold text-primary mb-3">
	    							<i class="fas fa-code me-2"></i>Example for custom.php
	    						</h6>
	    						<?php
									$code = array();
									$code[] = '<div class="form-group">';
									$code[] = '	<label class="form-control-label">Some Setting</label>';
									$code[] = '	<input class="form-control" name="name" value="<?= $setting_data["name"] ?>" >';
									$code[] = '</div>';
									echo ___h($code,'php');
								?>
	    					</div>
		    			</div>

		    			<div class="card mb-3">
		    				<div class="card-header bg-warning text-dark">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-user-cog me-2"></i>#3 User Settings Folder
		    					</h5>
		    				</div>
	    					<div class="card-body">
	    						This folder contains the custom.php file.<br>
	    						The custom.php file is used for user settings. Sometimes you need to ask for information from users, for example, PayPal email, bank details, or other information.<br>
	    						<strong>Important:</strong> All form fields must be named using the pattern <code>{gateway_code}_{field_name}</code> (e.g., <code>custom_name</code>, <code>custom_email</code>).<br>
	    						<strong>Required:</strong> Each custom gateway must include its own JavaScript form handler to avoid conflicts with core payment gateways (bank_transfer, paypal).

	    						<h6 class="fw-semibold text-primary mb-3">
	    							<i class="fas fa-code me-2"></i>Example for custom.php
	    						</h6>
	    						<?php
									$code = array();
									$code[] = '<div class="form-group row">';
									$code[] = '	<label class="col-sm-2 col-form-label"><?= __(\'admin.custom_name_label\') ?></label>';
									$code[] = '	<div class="col-sm-10">';
									$code[] = '		<input name="custom_name" class="form-control" ';
									$code[] = '			value="<?= isset($gateway_data_for_form[\'name\']) ? $gateway_data_for_form[\'name\'] : \'\' ?>" ';
									$code[] = '			placeholder="<?= __(\'admin.custom_name_placeholder\') ?>" required>';
									$code[] = '	</div>';
									$code[] = '</div>';
									$code[] = '';
									$code[] = '<div class="form-group row">';
									$code[] = '	<label class="col-sm-2 col-form-label"><?= __(\'admin.custom_email_label\') ?></label>';
									$code[] = '	<div class="col-sm-10">';
									$code[] = '		<input name="custom_email" class="form-control" type="email" ';
									$code[] = '			value="<?= isset($gateway_data_for_form[\'email\']) ? $gateway_data_for_form[\'email\'] : \'\' ?>" ';
									$code[] = '			placeholder="<?= __(\'admin.custom_email_placeholder\') ?>" required>';
									$code[] = '	</div>';
									$code[] = '</div>';
									$code[] = '';
									$code[] = '';
									$code[] = '<script type="text/javascript">';
									$code[] = '	$("#payment-form-custom").submit(function(){';
									$code[] = '		$this = $(this);';
									$code[] = '		var data = new FormData(this);';
									$code[] = '		$.ajax({';
									$code[] = '			url:\'<?= base_url(\'payment/call_payment_function/custom/saveUserSubmit\') ?>\',';
									$code[] = '			type:\'POST\',';
									$code[] = '			dataType:\'json\',';
									$code[] = '			data: data,';
									$code[] = '			cache: false,';
									$code[] = '			contentType: false,';
									$code[] = '			processData: false,';
									$code[] = '			beforeSend:function(){';
									$code[] = '				$this.find(\'.btn-submit\').btn("loading");';
									$code[] = '				$this.find(\'.btn-submit\').attr("disabled","disabled");';
									$code[] = '			},';
									$code[] = '			complete:function(){';
									$code[] = '				$this.find(\'.btn-submit\').btn("reset");';
									$code[] = '				$this.find(\'.btn-submit\').removeAttr("disabled");';
									$code[] = '			},';
									$code[] = '			success:function(json){';
									$code[] = '				$container = $this;';
									$code[] = '				$container.find(".is-invalid").removeClass("is-invalid");';
									$code[] = '				$container.find("span.invalid-feedback").remove();';
									$code[] = '				$this.find(\'.btn-submit\').removeAttr("disabled");';
									$code[] = '';
									$code[] = '				if (json[\'success\']) {';
									$code[] = '					$("#withdrawal-payments").modal("hide");';
									$code[] = '';
									$code[] = '					Swal.fire({';
									$code[] = '						title: \'<?= __("admin.success") ?>\',';
									$code[] = '						text: "<?= __("admin.withdrawal_request_sent_successfully") ?>",';
									$code[] = '						confirmButtonText: \'<?= __("admin.ok") ?>\',';
									$code[] = '						icon: \'success\',';
									$code[] = '					}).then((result) => {';
									$code[] = '						window.location.reload();';
									$code[] = '					})';
									$code[] = '				}';
									$code[] = '				';
									$code[] = '				if(json[\'errors\']){';
									$code[] = '				    $.each(json[\'errors\'], function(i,j){';
									$code[] = '				        $ele = $container.find(\'[name="\' + i + \'"]\');';
									$code[] = '				        if($ele){';
									$code[] = '				            $ele.addClass("is-invalid");';
									$code[] = '				            if($ele.parent(".input-group").length){';
									$code[] = '				                $ele.parent(".input-group").after("<span class=\'invalid-feedback\'>" + j + "</span>");';
									$code[] = '				            } else{';
									$code[] = '				                $ele.after("<small class=\'text-danger\'>" + j + "</small>");';
									$code[] = '				            }';
									$code[] = '				        }';
									$code[] = '				    })';
									$code[] = '				}';
									$code[] = '			},';
									$code[] = '		})';
									$code[] = '		return false;';
									$code[] = '	})';
									$code[] = '</script>';
									echo ___h($code,'php');
								?>
	    					</div>
		    			</div>

		    			<div class="card mb-3">
		    				<div class="card-header bg-info text-white">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-magic me-2"></i>🆕 Automatic Payment Details Integration
		    					</h5>
		    				</div>
	    					<div class="card-body">
	    						<div class="alert alert-success border-start border-success border-3">
	    							<h6 class="fw-semibold text-success mb-2">
	    								<i class="fas fa-check-circle me-2"></i>Great News!
	    							</h6>
	    							<p class="mb-0">Your gateway will <strong>automatically appear</strong> in the user's Payment Details page once installed and enabled. No additional coding required!</p>
	    						</div>

	    						<h6 class="fw-semibold text-primary mb-3">
	    							<i class="fas fa-cog me-2"></i>How It Works:
	    						</h6>
	    						<ul class="list-group list-group-flush mb-3">
	    							<li class="list-group-item d-flex align-items-start">
	    								<i class="fas fa-1 text-primary me-3 mt-1"></i>
	    								<div>
	    									<strong>Automatic Detection:</strong> System scans all enabled gateways
	    								</div>
	    							</li>
	    							<li class="list-group-item d-flex align-items-start">
	    								<i class="fas fa-2 text-success me-3 mt-1"></i>
	    								<div>
	    									<strong>Dynamic Form:</strong> Uses your <code>user_settings/custom.php</code> file to create the form
	    								</div>
	    							</li>
	    							<li class="list-group-item d-flex align-items-start">
	    								<i class="fas fa-3 text-warning me-3 mt-1"></i>
	    								<div>
	    									<strong>Auto-Save:</strong> Form submissions are handled automatically
	    								</div>
	    							</li>
	    							<li class="list-group-item d-flex align-items-start">
	    								<i class="fas fa-4 text-info me-3 mt-1"></i>
	    								<div>
	    									<strong>Data Storage:</strong> User data saved to settings table automatically
	    								</div>
	    							</li>
	    						</ul>

	    						<h6 class="fw-semibold text-warning mb-3">
	    							<i class="fas fa-lightbulb me-2"></i>Optional: Custom Handler
	    						</h6>
	    						<p>If you need custom validation or processing, add a <code>savePaymentDetails()</code> method to your controller:</p>
	    						<?php
									$code = [];
									$code[] = 'public function savePaymentDetails() {';
									$code[] = '    $data = $this->api->input->post(null,true);';
									$code[] = '    $userdetails = $this->api->userdetails();';
									$code[] = '    ';
									$code[] = '    // Your custom validation here';
									$code[] = '    if (!isset($data[\'email\']) || trim($data[\'email\']) == \'\') {';
									$code[] = '        return [\'error\' => 1, \'message\' => \'Email required\'];';
									$code[] = '    }';
									$code[] = '    ';
									$code[] = '    // Save data using existing system';
									$code[] = '    $this->api->load->model(\'Setting_model\');';
									$code[] = '    $this->api->Setting_model->save(\'user_payment_custom_\' . $userdetails[\'id\'], $data);';
									$code[] = '    ';
									$code[] = '    return [\'success\' => 1, \'message\' => \'Saved successfully\'];';
									$code[] = '}';
									echo ___h($code,'php');
								?>
	    					</div>
		    			</div>

		    			<div class="card mb-3">
		    				<div class="card-header bg-secondary text-white">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-eye me-2"></i>#4 Confirm View Folder (Actions Section)
		    					</h5>
		    				</div>
	    					<div class="card-body">
	    						<p>This folder contains the <code>custom.php</code> file that creates the <strong>"Actions"</strong> section on the admin withdrawal request details page.</p>
	    						
	    						<div class="alert alert-info border-start border-info border-3">
	    							<h6 class="fw-semibold text-info mb-2">
	    								<i class="fas fa-info-circle me-2"></i>What is the Actions Section?
	    							</h6>
	    							<p class="mb-0">The Actions section appears when an admin views a withdrawal request details page. It provides payment gateway-specific controls for processing payments directly from the admin panel.</p>
	    						</div>

	    						<h6 class="fw-semibold text-primary mb-3">
	    							<i class="fas fa-cog me-2"></i>Features You Can Implement:
	    						</h6>
	    						<ul class="list-group list-group-flush mb-3">
	    							<li class="list-group-item d-flex align-items-start">
	    								<i class="fas fa-credit-card text-success me-3 mt-1"></i>
	    								<div>
	    									<strong>Direct Payment Processing:</strong> Add buttons to process payments through your gateway's API
	    								</div>
	    							</li>
	    							<li class="list-group-item d-flex align-items-start">
	    								<i class="fas fa-eye text-info me-3 mt-1"></i>
	    								<div>
	    									<strong>Display User Payment Details:</strong> Show the user's saved payment information (bank details, wallet addresses, etc.)
	    								</div>
	    							</li>
	    							<li class="list-group-item d-flex align-items-start">
	    								<i class="fas fa-tasks text-warning me-3 mt-1"></i>
	    								<div>
	    									<strong>Status Management:</strong> Add buttons to update request status (Complete, Processing, Failed)
	    								</div>
	    							</li>
	    							<li class="list-group-item d-flex align-items-start">
	    								<i class="fas fa-receipt text-primary me-3 mt-1"></i>
	    								<div>
	    									<strong>Transaction References:</strong> Input fields for transaction IDs or reference numbers
	    								</div>
	    							</li>
	    						</ul>

	    						<h6 class="fw-semibold text-success mb-3">
	    							<i class="fas fa-code me-2"></i>Example Implementation:
	    						</h6>
	    						<?php
									$code = array();
									$code[] = '<?php if (isset($request)) {';
									$code[] = '    $settings = json_decode($request[\'settings\'], true);';
									$code[] = '    $user_data = isset($settings[\'payment_details\']) ? $settings[\'payment_details\'] : [];';
									$code[] = '    $request_id = $request[\'id\'];';
									$code[] = '?>';
									$code[] = '<div class="card">';
									$code[] = '    <div class="card-header bg-primary text-white">';
									$code[] = '        <h5 class="card-title mb-0">';
									$code[] = '            <i class="fas fa-credit-card me-2"></i>Custom Gateway Payment Processing';
									$code[] = '        </h5>';
									$code[] = '    </div>';
									$code[] = '    <div class="card-body">';
									$code[] = '        <div class="row mb-3">';
									$code[] = '            <div class="col-md-6">';
									$code[] = '                <strong>Amount:</strong> <?= c_format($request[\'total\']) ?>';
									$code[] = '            </div>';
									$code[] = '            <div class="col-md-6">';
									$code[] = '                <strong>User Email:</strong> <?= $user_data[\'email\'] ?? \'N/A\' ?>';
									$code[] = '            </div>';
									$code[] = '        </div>';
									$code[] = '        ';
									$code[] = '        <div class="d-flex gap-2">';
									$code[] = '            <button type="button" class="btn btn-success" onclick="processPayment(<?= $request_id ?>, 1)">';
									$code[] = '                <i class="fas fa-check me-2"></i>Mark as Completed';
									$code[] = '            </button>';
									$code[] = '            <button type="button" class="btn btn-primary" onclick="processPayment(<?= $request_id ?>, 7)">';
									$code[] = '                <i class="fas fa-sync-alt me-2"></i>Mark as Processing';
									$code[] = '            </button>';
									$code[] = '            <button type="button" class="btn btn-danger" onclick="processPayment(<?= $request_id ?>, 5)">';
									$code[] = '                <i class="fas fa-times me-2"></i>Mark as Failed';
									$code[] = '            </button>';
									$code[] = '        </div>';
									$code[] = '    </div>';
									$code[] = '</div>';
									$code[] = '';
									$code[] = '<script>';
									$code[] = 'function processPayment(requestId, statusId) {';
									$code[] = '    // AJAX call to update status';
									$code[] = '    $.ajax({';
									$code[] = '        url: \'<?= base_url("admincontrol/wallet_requests_details/") ?>\' + requestId,';
									$code[] = '        type: \'POST\',';
									$code[] = '        data: { status: statusId, comment: \'Updated via Custom Gateway actions\' },';
									$code[] = '        success: function(response) {';
									$code[] = '            if (response.success) {';
									$code[] = '                window.location.reload();';
									$code[] = '            }';
									$code[] = '        }';
									$code[] = '    });';
									$code[] = '}';
									$code[] = '</script>';
									$code[] = '<?php } ?>';
									echo ___h($code,'php');
								?>

	    						<div class="alert alert-success border-start border-success border-3 mt-3">
	    							<h6 class="fw-semibold text-success mb-2">
	    								<i class="fas fa-lightbulb me-2"></i>Live Examples Available:
	    							</h6>
	    							<p class="mb-2">Check out these working examples in your system:</p>
	    							<ul class="mb-0">
	    								<li><strong>Test Gateway:</strong> <code>application/withdrawal_payment/confirm_view/test_gateway.php</code></li>
	    								<li><strong>PayPal:</strong> <code>application/withdrawal_payment/confirm_view/paypal.php</code></li>
	    								<li><strong>Bank Transfer:</strong> <code>application/withdrawal_payment/confirm_view/bank_transfer.php</code></li>
	    							</ul>
	    						</div>

	    						<div class="alert alert-warning border-start border-warning border-3 mt-3">
	    							<h6 class="fw-semibold text-warning mb-2">
	    								<i class="fas fa-exclamation-triangle me-2"></i>Important Notes:
	    							</h6>
	    							<ul class="mb-0">
	    								<li>The <code>$request</code> variable contains all withdrawal request data</li>
	    								<li>User payment details are stored in <code>$request['settings']</code> as JSON</li>
	    								<li>Use AJAX calls to update request status without page reload</li>
	    								<li>This file is <strong>optional</strong> - if not present, no Actions section will show</li>
	    							</ul>
	    						</div>
	    					</div>
		    			</div>

		    			<div class="card mb-3">
		    				<div class="card-header bg-dark text-white">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-image me-2"></i>#5 Logo Folder
		    					</h5>
		    				</div>
	    					<div class="card-body">
	    						This folder contains the payment gateway logo image file.<br>
	    						It should be a unique name like custom.png, custom1.png, custom2.png.<br>
	    						The logo image file name must not be the same as our default payment gateways, otherwise it will override the latest image.  
	    					</div>
		    			</div>

		    			<div class="card mb-3">
		    				<div class="card-header bg-primary text-white">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-database me-2"></i>Model Functions (Withdrawal_payment_model)
		    					</h5>
		    				</div>
	    					<div class="card-body">
	    						<code>getRequestDetails($request_id)</code>
	    						<p>You can get Withdrawal Request Details</p>

	    						<br><br>
	    						<code>apiAddWithdrwalRequestHistory($req_id, $data = array())</code>
	    						<p>You can add request history using this function in data you need to pass following</p>
	    						<ul>
									<li>status</li>
									<li>comment</li>
									<li>transaction_id</li>
	    						</ul>

	    						<br><br>
	    						<code>apiAddWithdrwalRequest($code,$ids,$setting = array())</code>
	    						<p>You can create a new withdrawal request using this function.<br>In the setting, you need to pass your setting data that can be used later in the controller file</p>
	    					</div>
		    			</div>

		    			<div class="card mb-3">
		    				<div class="card-header bg-info text-white">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-list me-2"></i>Status ID and Titles
		    					</h5>
		    				</div>
	    					<div class="card-body">
		    						<div class="table-responsive">
		    							<table class="table table-striped table-hover table-sm">
									<tr><th width="90px">Status ID</th><th>Title</th></tr>
									<tr><td>0</td><td>Received</td></tr>
							        <tr><td>1</td><td>Complete</td></tr>
							        <tr><td>2</td><td>Total not match</td></tr>
							        <tr><td>3</td><td>Denied</td></tr>
							        <tr><td>4</td><td>Expired</td></tr>
							        <tr><td>5</td><td>Failed</td></tr>
							        <tr><td>7</td><td>Processed</td></tr>
							        <tr><td>8</td><td>Refunded</td></tr>
							        <tr><td>9</td><td>Reversed</td></tr>
							        <tr><td>10</td><td>Voided</td></tr>
							        <tr><td>11</td><td>Canceled Reversal</td></tr>
							        <tr><td>12</td><td>Waiting For Payment</td></tr>
							        <tr><td>13</td><td>Pending</td></tr>
								</table>
		    						</div>
	    					</div>
		    			</div>

		    			<div class="card mb-3">
		    				<div class="card-header bg-success text-white">
		    					<h5 class="card-title mb-0 fw-semibold">
		    						<i class="fas fa-file-archive me-2"></i>How to Create a ZIP File
		    					</h5>
		    				</div>
	    					<div class="card-body">
	    						The ZIP file contains the root folder as "upload" inside the upload folder with all module files like this:
	    						<div class="alert alert-warning mt-3">
	    							<h6 class="fw-semibold mb-3">
	    								<i class="fas fa-archive me-2"></i>ZIP File Structure:
	    							</h6>
	    							<div class="row">
	    								<div class="col-md-6">
	    									<ul class="list-group list-group-flush">
	    										<li class="list-group-item d-flex align-items-center">
	    											<i class="fas fa-folder text-primary me-2"></i>
	    											<code>/upload/controllers/</code>
	    										</li>
	    										<li class="list-group-item d-flex align-items-center">
	    											<i class="fas fa-folder text-success me-2"></i>
	    											<code>/upload/admin_settings/</code>
	    										</li>
	    										<li class="list-group-item d-flex align-items-center">
	    											<i class="fas fa-folder text-warning me-2"></i>
	    											<code>/upload/user_settings/</code>
	    										</li>
	    									</ul>
	    								</div>
	    								<div class="col-md-6">
	    									<ul class="list-group list-group-flush">
	    										<li class="list-group-item d-flex align-items-center">
	    											<i class="fas fa-folder text-info me-2"></i>
	    											<code>/upload/confirm_view/</code>
	    											<span class="badge bg-secondary ms-2">Optional</span>
	    										</li>
	    										<li class="list-group-item d-flex align-items-center">
	    											<i class="fas fa-folder text-dark me-2"></i>
	    											<code>/upload/logo/</code>
	    										</li>
	    						</ul>
	    								</div>
	    							</div>
	    						</div>
	    					</div>
		    			</div>
		    		</div>

		    		<div class="card mb-4 border-warning">
		    			<div class="card-header bg-warning text-dark">
		    				<h5 class="card-title mb-0 fw-semibold">
		    					<i class="fas fa-exclamation-triangle me-2"></i>🚨 Common Beginner Mistakes
		    				</h5>
		    			</div>
		    			<div class="card-body">
		    				<div class="row">
		    					<div class="col-md-6">
		    						<h6 class="fw-semibold text-danger mb-3">❌ Don't Do This:</h6>
		    						<ul class="list-group list-group-flush">
		    							<li class="list-group-item border-danger">
		    								<strong>Wrong file names:</strong> Using spaces or uppercase letters in file names
		    								<br><small class="text-muted">❌ "My Wallet.php" or "MyWallet.php"</small>
		    							</li>
		    							<li class="list-group-item border-danger">
		    								<strong>Wrong field names:</strong> Not using gateway prefix
		    								<br><small class="text-muted">❌ name="address" instead of name="mywallet_address"</small>
		    							</li>
		    							<li class="list-group-item border-danger">
		    								<strong>Missing class name:</strong> Class name doesn't match file name
		    								<br><small class="text-muted">❌ File: mywallet.php, Class: MyWallet</small>
		    							</li>
		    							<li class="list-group-item border-danger">
		    								<strong>Wrong folder location:</strong> Putting files in wrong directories
		    								<br><small class="text-muted">❌ Putting controller in admin_settings folder</small>
		    							</li>
		    						</ul>
		    					</div>
		    					<div class="col-md-6">
		    						<h6 class="fw-semibold text-success mb-3">✅ Do This Instead:</h6>
		    						<ul class="list-group list-group-flush">
		    							<li class="list-group-item border-success">
		    								<strong>Correct file names:</strong> Lowercase, no spaces
		    								<br><small class="text-muted">✅ "mywallet.php"</small>
		    							</li>
		    							<li class="list-group-item border-success">
		    								<strong>Correct field names:</strong> Always use gateway prefix
		    								<br><small class="text-muted">✅ name="mywallet_address"</small>
		    							</li>
		    							<li class="list-group-item border-success">
		    								<strong>Matching class name:</strong> Exact match with file name
		    								<br><small class="text-muted">✅ File: mywallet.php, Class: Mywallet</small>
		    							</li>
		    							<li class="list-group-item border-success">
		    								<strong>Correct folder structure:</strong> Follow the exact structure
		    								<br><small class="text-muted">✅ controllers/mywallet.php</small>
		    							</li>
		    						</ul>
		    					</div>
		    				</div>
		    			</div>
		    		</div>

		    		<div class="card mb-4 border-info">
		    			<div class="card-header bg-info text-white">
		    				<h5 class="card-title mb-0 fw-semibold">
		    					<i class="fas fa-tools me-2"></i>🔧 Troubleshooting Guide
		    				</h5>
		    			</div>
		    			<div class="card-body">
		    				<div class="accordion" id="troubleshootingAccordion">
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="trouble1">
		    							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#troubleCollapse1">
		    								<i class="fas fa-question-circle text-danger me-2"></i>Gateway doesn't appear in admin settings
		    							</button>
		    						</h2>
		    						<div id="troubleCollapse1" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
		    							<div class="accordion-body">
		    								<strong>Possible causes:</strong>
		    								<ul>
		    									<li>File name doesn't match class name</li>
		    									<li>PHP syntax errors in controller file</li>
		    									<li>Missing required functions (onInstall, onUnInstall, saveUserSubmit)</li>
		    									<li>File not in correct folder (controllers/)</li>
		    								</ul>
		    								<strong>Solution:</strong> Check PHP error logs and verify file structure.
		    							</div>
		    						</div>
		    					</div>
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="trouble2">
		    							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#troubleCollapse2">
		    								<i class="fas fa-question-circle text-warning me-2"></i>User form doesn't save data
		    							</button>
		    						</h2>
		    						<div id="troubleCollapse2" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
		    							<div class="accordion-body">
		    								<strong>Possible causes:</strong>
		    								<ul>
		    									<li>Form field names don't start with gateway code</li>
		    									<li>Missing validation in saveUserSubmit() function</li>
		    									<li>Incorrect data processing logic</li>
		    								</ul>
		    								<strong>Solution:</strong> Ensure all form fields use prefix (e.g., mywallet_address).
		    							</div>
		    						</div>
		    					</div>
		    					<div class="accordion-item">
		    						<h2 class="accordion-header" id="trouble3">
		    							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#troubleCollapse3">
		    								<i class="fas fa-question-circle text-success me-2"></i>Actions section doesn't show
		    							</button>
		    						</h2>
		    						<div id="troubleCollapse3" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
		    							<div class="accordion-body">
		    								<strong>Possible causes:</strong>
		    								<ul>
		    									<li>confirm_view file doesn't exist</li>
		    									<li>PHP syntax errors in confirm_view file</li>
		    									<li>File name doesn't match gateway code</li>
		    								</ul>
		    								<strong>Solution:</strong> Create confirm_view/mywallet.php with correct syntax.
		    							</div>
		    						</div>
		    					</div>
		    				</div>
		    			</div>
		    		</div>

		    		<div class="card mb-4 border-primary">
		    			<div class="card-header bg-primary text-white">
		    				<h5 class="card-title mb-0 fw-semibold">
		    					<i class="fas fa-graduation-cap me-2"></i>🎯 Testing Checklist
		    				</h5>
		    			</div>
		    			<div class="card-body">
		    				<p class="mb-3">Before deploying your gateway, test these features:</p>
		    				<div class="row">
		    					<div class="col-md-6">
		    						<h6 class="fw-semibold mb-3">Admin Panel Tests:</h6>
		    						<div class="form-check">
		    							<input class="form-check-input" type="checkbox" id="test1">
		    							<label class="form-check-label" for="test1">Gateway appears in payment settings</label>
		    						</div>
		    						<div class="form-check">
		    							<input class="form-check-input" type="checkbox" id="test2">
		    							<label class="form-check-label" for="test2">Admin settings form works</label>
		    						</div>
		    						<div class="form-check">
		    							<input class="form-check-input" type="checkbox" id="test3">
		    							<label class="form-check-label" for="test3">Gateway can be enabled/disabled</label>
		    						</div>
		    						<div class="form-check">
		    							<input class="form-check-input" type="checkbox" id="test4">
		    							<label class="form-check-label" for="test4">Actions section shows on withdrawal details</label>
		    						</div>
		    					</div>
		    					<div class="col-md-6">
		    						<h6 class="fw-semibold mb-3">User Panel Tests:</h6>
		    						<div class="form-check">
		    							<input class="form-check-input" type="checkbox" id="test5">
		    							<label class="form-check-label" for="test5">Gateway appears in payment details</label>
		    						</div>
		    						<div class="form-check">
		    							<input class="form-check-input" type="checkbox" id="test6">
		    							<label class="form-check-label" for="test6">User form saves data correctly</label>
		    						</div>
		    						<div class="form-check">
		    							<input class="form-check-input" type="checkbox" id="test7">
		    							<label class="form-check-label" for="test7">Validation works for required fields</label>
		    						</div>
		    						<div class="form-check">
		    							<input class="form-check-input" type="checkbox" id="test8">
		    							<label class="form-check-label" for="test8">Withdrawal request creates successfully</label>
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