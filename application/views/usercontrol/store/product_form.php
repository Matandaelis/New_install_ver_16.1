<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
?>

<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/select2/select2.min.css") ?>">
<script type="text/javascript" src="<?= base_url('assets/plugins/select2/select2.full.min.js') ?>"></script>
<script src="<?= base_url('assets/template/js/jscolor.js') ?>"></script>


<div class="container-fluid">
	<form class="needs-validation" method="post" action="" enctype="multipart/form-data" id="form_form" novalidate>
		<input type="hidden" name="product_id" value="<?php echo $product->product_id ?>">

		<!-- Product Type - FULL WIDTH AT TOP (always visible first) -->
		<div class="card shadow-sm border-0 mb-4">
			<div class="card-header bg-primary text-white py-3">
				<h4 class="card-title mb-0 fw-bold">
					<i class="fas fa-box me-2"></i>
					<?= (int)$product->product_id == 0 ? __('user.lbl_create_product') : __('user.lbl_update_product') ?>
				</h4>
			</div>
			<div class="card-body p-4">
				<label class="form-label fw-bold text-dark mb-3 d-block">
					<i class="fas fa-tag me-2 text-primary"></i><?= __('user.product_type'); ?>
				</label>
				<div class="row g-3">
								<div class="col-md-4">
									<div class="form-check">
										<input class="form-check-input invisible" type="radio" name="product_type" id="virtual_product" value="virtual" <?= ($product->product_type == 'virtual' || $product->product_type == '') ? 'checked="checked"' : '' ?>>
										<label class="form-check-label btn w-100 py-3 proType <?= ($product->product_type == 'virtual' || $product->product_type == '') ? 'btn-primary' : 'btn-outline-primary' ?>" for="virtual_product" data-value="virtual">
											<i class="fas fa-cloud me-2"></i><?= __('user.virtual_product'); ?>
										</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-check">
										<input class="form-check-input invisible" type="radio" name="product_type" id="downloadable_product" value="downloadable" <?= ($product->product_type == 'downloadable') ? 'checked="checked"' : '' ?>>
										<label class="form-check-label btn w-100 py-3 proType <?= ($product->product_type == 'downloadable') ? 'btn-primary' : 'btn-outline-primary' ?>" for="downloadable_product" data-value="downloadable">
											<i class="fas fa-download me-2"></i><?= __('user.downloadable_product'); ?>
										</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-check">
										<input class="form-check-input invisible" type="radio" name="product_type" id="video_product" value="video" <?= ($product->product_type == 'video' || $product->product_type == 'videolink') ? 'checked="checked"' : '' ?>>
										<label class="form-check-label btn w-100 py-3 proType <?= ($product->product_type == 'video' || $product->product_type == 'videolink') ? 'btn-primary' : 'btn-outline-primary' ?>" for="video_product" data-value="video">
											<i class="fas fa-play-circle me-2"></i><?= __('admin.lms_product'); ?>
										</label>
									</div>
								</div>
				</div>
			</div>
		</div>
		<script>
		$(document).ready(function() {
			$(".proType").click(function() {
				$(".proType").removeClass("btn-primary").addClass("btn-outline-primary");
				$(this).removeClass("btn-outline-primary").addClass("btn-primary");
			});
			$(".proSubType").click(function() {
				$(".proSubType").removeClass("btn-primary").addClass("btn-outline-secondary");
				$(this).removeClass("btn-outline-secondary").addClass("btn-primary");
			});
			// Shipping Information collapse (click to toggle)
			$('#btnVendorShippingInfo').on('click', function() {
				var $target = $('#vendorShippingInfoCollapse');
				var $chevron = $('#vendorShippingChevron');
				$target.toggleClass('show');
				$chevron.toggleClass('fa-chevron-down fa-chevron-up');
			});
		});
		</script>

		<div class="row g-4">
			<!-- Main Product Information -->
			<div class="col-xl-8 col-lg-7">
				<div class="card shadow-sm border-0 mb-4">
					<div class="card-body p-4">
						<!-- Product Name -->
						<div class="mb-4">
							<label class="form-label fw-semibold">
								<i class="fas fa-tag me-1"></i><?= __('user.product_name') ?>
							</label>
							<input placeholder="<?= __('user.enter_your_product_name') ?>" 
								   name="product_name" 
								   value="<?php echo $product->product_name; ?>" 
								   class="form-control form-control-lg" 
								   type="text" required>
							<div class="invalid-feedback">Please provide a product name.
</div>
						
</div>

						<div class="row">
							<!-- Left Column -->
							<div class="col-lg-8">
								<!-- Pricing Section -->
								<div class="row mb-4">
									<div class="col-md-6">
										<label class="form-label fw-semibold">
											<i class="fas fa-dollar-sign me-1"></i><?= __('user.product_sale_price') ?>
										</label>
										<div class="input-group">
											<span class="input-group-text">$</span>
											<input placeholder="MSRP" 
												   name="product_msrp" 
												   class="form-control" 
												   value="<?php echo $product->product_msrp; ?>" 
												   type="number" 
												   step="0.01">
										
</div>
										<small class="text-muted"><?= __('user.manufacturer_suggested_retail_price') ?></small>
									
</div>
									<div class="col-md-6">
										<label class="form-label fw-semibold">
											<i class="fas fa-money-bill-wave me-1"></i><?= __('user.product_price') ?>
										</label>
										<div class="input-group">
											<span class="input-group-text">$</span>
											<input placeholder="<?= __('user.enter_your_product_price') ?>" 
												   name="product_price" 
												   class="form-control" 
												   value="<?php echo $product->product_price; ?>" 
												   type="number" 
												   step="0.01" required>
										
</div>
										<div class="invalid-feedback">Please provide a valid price.
</div>
									
</div>
								
</div>

								<!-- SKU and Quantity -->
								<div class="row mb-4">
									<div class="col-md-6">
										<label class="form-label fw-semibold">
											<i class="fas fa-barcode me-1"></i><?= __('user.product_sku') ?>
										</label>
										<input placeholder="<?= __('user.enter_your_product_sku') ?>" 
											   name="product_sku" 
											   id="product_sku" 
											   class="form-control" 
											   value="<?php echo $product->product_sku; ?>" 
											   type="text">
									
</div>
									<div class="col-md-6">
										<label class="form-label fw-semibold">
											<i class="fas fa-boxes me-1"></i><?= __('user.product_quantity') ?>
										</label>
										<input placeholder="<?= __('user.enter_product_quantity') ?>" 
											   name="product_quantity" 
											   id="product_quantity" 
											   class="form-control" 
											   value="<?php echo $product->product_quantity; ?>" 
											   type="number" 
											   min="0">
									
</div>
								
</div>

								<!-- Video URL (hidden) -->
								<div class="mb-4 d-none">
									<label class="form-label fw-semibold"><?= __('user.product_video_') ?></label>
									<input placeholder="<?= __('user.enter_your_product_video_link{youtube/vimeo}') ?>" 
										   name="product_video" 
										   id="product_video" 
										   class="form-control" 
										   value="<?php echo $product->product_video; ?>" 
										   type="text">
								
</div>

								<!-- Short Description -->
								<div class="mb-4">
									<label class="form-label fw-semibold">
										<i class="fas fa-align-left me-1"></i><?= __('user.short_description') ?>
									</label>
									<textarea rows="3" 
											  placeholder="<?= __('user.enter_your_product_short_description') ?>" 
											  class="form-control" 
											  name="product_short_description"><?php echo $product->product_short_description; ?></textarea>
								
</div>

								<!-- Categories -->
								<div class="mb-4">
									<label class="form-label fw-semibold">
										<i class="fas fa-folder me-1"></i><?= __('user.categories') ?>
									</label>
									<div class="category-container">
										<input name="category_auto"
											   placeholder="<?= __('user.categories') ?>"
											   id="category_auto"
											   class="form-control mb-2"
											   autocomplete="off">
										<div class="category-selected-wrap <?= (!isset($categories) || empty($categories)) ? 'd-none' : '' ?>">
											<ul class="list-group category-selected list-group-flush">
												<?php if(isset($categories)){ ?>
													<?php foreach ($categories as $key => $category) { ?>
														<li class="list-group-item d-flex justify-content-between align-items-center">
															<span class="fw-medium"><?= $category['name'] ?></span>
															<input type="hidden" name="category[]" value="<?= $category['id'] ?>">
															<button type="button" class="btn btn-danger btn-sm remove-category">
																<i class="bi bi-trash"></i>
															</button>
														</li>
													<?php } ?>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>

								<!-- Shipping Information (hidden for downloadable/LMS - physical/virtual only, like admin) -->
								<?php $hideShipping = in_array($product->product_type, ['video','videolink','downloadable']); ?>
								<div class="mb-4 vendor_shipping_section <?= $hideShipping ? 'd-none' : '' ?>" id="vendor_shipping_collapse_div">
									<div class="card border shadow-sm">
										<div class="card-header bg-light d-flex align-items-center justify-content-between py-2" style="cursor:pointer;" id="btnVendorShippingInfo">
											<h6 class="card-title mb-0 fw-semibold">
												<i class="fas fa-truck me-1 text-primary"></i><?= __('user.shipping_information') ?>
											</h6>
											<i class="fas fa-chevron-down text-muted small" id="vendorShippingChevron"></i>
										</div>
										<div class="collapse" id="vendorShippingInfoCollapse">
											<div class="card-body">
												<?php if (!$product->allow_shipping): ?>
												<p class="small text-muted mb-3"><?= __('user.shipping_dimensions_note'); ?></p>
												<?php endif; ?>
												<div class="row g-3">
													<div class="col-6 col-md-3">
														<label class="form-label fw-semibold small"><?= __('user.product_weight') ?> (lbs)</label>
														<input placeholder="0.00" name="product_weight" class="form-control form-control-sm" value="<?= isset($product->product_weight) ? $product->product_weight : '0.00' ?>" type="number" step="0.01" min="0">
													</div>
													<div class="col-6 col-md-3">
														<label class="form-label fw-semibold small"><?= __('user.product_length') ?> (in)</label>
														<input placeholder="0.00" name="product_length" class="form-control form-control-sm" value="<?= isset($product->product_length) ? $product->product_length : '0.00' ?>" type="number" step="0.01" min="0">
													</div>
													<div class="col-6 col-md-3">
														<label class="form-label fw-semibold small"><?= __('user.product_width') ?> (in)</label>
														<input placeholder="0.00" name="product_width" class="form-control form-control-sm" value="<?= isset($product->product_width) ? $product->product_width : '0.00' ?>" type="number" step="0.01" min="0">
													</div>
													<div class="col-6 col-md-3">
														<label class="form-label fw-semibold small"><?= __('user.product_height') ?> (in)</label>
														<input placeholder="0.00" name="product_height" class="form-control form-control-sm" value="<?= isset($product->product_height) ? $product->product_height : '0.00' ?>" type="number" step="0.01" min="0">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							
</div>

							<!-- Right Column - Featured Image -->
							<div class="col-lg-4">
								<div class="card h-100 border">
									<div class="card-header bg-light">
										<h6 class="card-title mb-0 fw-semibold">
											<i class="fas fa-image me-1"></i><?= __('user.product_featured_image') ?>
										</h6>
									
</div>
									<div class="card-body text-center">
										<!-- Local Image Selection -->
										<div class="mb-3 d-grid">
											<button type="button" class="btn btn-outline-primary" onclick="document.getElementById('product_featured_image').click();">
												<i class="fas fa-upload me-1"></i><?= __('user.browse_local_image') ?>
											</button>
											<input id="product_featured_image" 
												   name="product_featured_image" 
												   class="d-none" 
												   type="file" 
												   onchange="readURL(this,'#featureImage');">
										
</div>
										
										<!-- S3 Image Selection -->
										<div class="mb-3 d-grid">
											<button type="button" class="btn btn-outline-info" onclick="prepareS3Modal('input[name=\'product_featured_image_s3\']', '#featureImage')">
												<i class="fab fa-aws me-1"></i><?= __('user.browse_amazon_s3_image') ?>
											</button>
											<input type="hidden" name="s3_bucket_name" value="<?= $setting['s3_bucket_name'] ?? ''; ?>" id="s3_bucket_name">
											<input type="hidden" name="s3_region" value="<?= $setting['s3_region'] ?? ''; ?>" id="s3_region">
											<input type="hidden" name="product_featured_image_s3" id="product_featured_image_s3" value="">
										
</div>
										
										<?php
										if (strpos($product->product_featured_image, 'http://') === 0 || strpos($product->product_featured_image, 'https://') === 0) {
											$product_featured_image = $product->product_featured_image;
										} else {
											$product_featured_image = !empty($product->product_featured_image) ? base_url('assets/images/product/upload/thumb/' . $product->product_featured_image) : base_url('assets/images/no_product_image.png');
										}
										?>
										<img src="<?= $product_featured_image; ?>" 
											 id="featureImage" 
											 class="img-fluid rounded border" 
											 style="max-height: 200px;">
									
</div>
								
</div>
							
</div>
						
</div>

						<!-- Product Variants -->
						<div class="mt-4">
							<div class="d-flex justify-content-between align-items-center mb-3">
								<h5 class="fw-bold mb-0">
									<i class="fas fa-palette me-1"></i>Product Variants
								</h5>
								<button type="button" class="btn btn-success btn-add-variants">
									<i class="fas fa-plus me-1"></i><?= __('user.add_variants') ?>
								</button>
							
</div>

							<?php
							if(isset($product->product_variations) && !empty($product->product_variations)) {
								$variations = json_decode($product->product_variations);
							}
							?>

							<div class="table-container">
								<table id="product-variations" class="table table-striped table-hover border">
									<?php
									foreach($variations as $key => $value) {
										if(!empty($value)) {
											?>
											<tr data-variation-type="<?= strtolower($key); ?>" class="align-middle">
												<td><strong class="text-primary"><?= ucwords(strtolower($key)); ?> :</strong></td>
												<td>
													<?php
													for ($i=0; $i < sizeof($value); $i++) { 
														$this_price = isset($value[$i]->price) ? $value[$i]->price : 0;
														if($key == 'colors') {
															echo ($i == 0) ? '<span class="badge bg-light text-dark me-1">'.ucwords(strtolower($value[$i]->name)).'</span>' : '<span class="badge bg-light text-dark me-1">'.ucwords(strtolower($value[$i]->name)).'</span>';
															echo "<input type='hidden' name='variations[".strtolower($key)."][name][]' value='".$value[$i]->name."'>";
															echo "<input type='hidden' name='variations[".strtolower($key)."][code][]' value='".$value[$i]->code."'>";
															echo "<input type='hidden' name='variations[".strtolower($key)."][price][]' value='".$this_price."'>";
														} else {
															$this_name = isset($value[$i]->name) ? $value[$i]->name : $value[$i];
															echo ($i == 0) ? '<span class="badge bg-light text-dark me-1">'.ucwords(strtolower($this_name)).'</span>' : '<span class="badge bg-light text-dark me-1">'.ucwords(strtolower($this_name)).'</span>';
															echo "<input type='hidden' name='variations[".strtolower($key)."][name][]' value='".$this_name."'>";
															echo "<input type='hidden' name='variations[".strtolower($key)."][price][]' value='".$this_price."'>";
														}
													}
													?>
												</td>
												<td class="text-end">
													<div class="btn-group btn-group-sm">
														<button type="button" data-variation-type="<?= strtolower($key); ?>" class="btn btn-outline-warning btn-edit-variants">
															<i class="fa fa-edit"></i>
														</button>
														<button type="button" data-variation-type="<?= strtolower($key); ?>" class="btn btn-outline-danger btn-delete-variants">
															<i class="fa fa-trash"></i>
														</button>
													
</div>
												</td>
											</tr>
											<?php
										}
									}													
									?>
								</table>
							
</div>
						
</div>

						<!-- Location Settings -->
						<div class="card border mt-4">
							<div class="card-header bg-light">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-map-marker-alt me-1"></i><?= __('user.country_location') ?>
								</h6>
							
</div>
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col-md-3">
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="allow_country" value="0" checked id="country_disable">
											<label class="form-check-label" for="country_disable"><?= __('user.disable'); ?></label>
										
</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="allow_country" value="1" <?= (int)$product->state_id >= 1 ? 'checked' : '' ?> id="country_enable">
											<label class="form-check-label" for="country_enable"><?= __('user.enable'); ?></label>
										
</div>
									
</div>
									<div class="col-md-9">
										<div class="country-chooser">
											<div class="row">
												<div class="col-md-6">
													<select class="form-select" name="country_id" id="country_id">
														<option value="0"><?= __('user.select_country') ?></option>
														<?php foreach ($country_list as $key => $value) { ?>
															<option <?= (isset($product_state) && $product_state && $product_state->country_id == $value->id) ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
														<?php } ?>
													</select>
												
</div>
												<div class="col-md-6">
													<select class="form-select" name="state_id" id="state_id">
														<option value=""><?= __('user.select_state') ?></option>
														<?php foreach (isset($states) ? $states : [] as $key => $value) { ?>
															<option <?= (isset($product_state) && $product_state && $product_state->id == $value->id) ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
														<?php } ?>
													</select>
												
</div>
											
</div>
										
</div>
									
</div>
								
</div>
							
</div>
						
</div>

						<!-- Product Description -->
						<div class="mt-4">
							<label class="form-label fw-semibold">
								<i class="fas fa-file-alt me-1"></i><?= __('user.product_description') ?>
							</label>
							<textarea placeholder="<?= __('user.enter_your_product_description') ?>" 
									  class="product_description form-control summernote" 
									  name="product_description" 
									  rows="8"><?php echo $product->product_description; ?></textarea>
						
</div>

						<!-- Product Tags -->
						<div class="mt-4">
							<label for="product_tags" class="form-label fw-semibold">
								<i class="fas fa-tags me-1"></i><?= __('user.product_tags') ?>
							</label>
							<select id="product_tags" name="product_tags[]" class="form-select select2" multiple="multiple">
								<?php
								if (!empty($product->product_tags)) {
									$ptags = json_decode($product->product_tags, true);
									$ptags = is_array($ptags) ? $ptags : [];
								} else {
									$ptags = [];
								}
								$tags_safe = isset($tags) && is_array($tags) ? $tags : [];
								foreach ($tags_safe as $tag) {
									$tag_val = is_array($tag) ? ($tag['name'] ?? $tag['id'] ?? '') : $tag;
									if ($tag_val !== '' && $tag_val !== null) {
										$selected = in_array($tag_val, $ptags) ? "selected" : "";
										echo '<option value="' . htmlspecialchars((string)$tag_val) . '" ' . $selected . '>' . htmlspecialchars((string)$tag_val) . '</option>';
									}
								}
								?>
							</select>
						
</div>

						<!-- Product Content (Downloadable/LMS only - hidden for Virtual/Physical) -->
						<?php $showProductContent = in_array($product->product_type, ['downloadable','video','videolink']); ?>
						<div class="card border mt-4 shadow-sm product_content_card <?= $showProductContent ? '' : 'd-none' ?>" id="product_content_card">
							<div class="card-header bg-light border-bottom">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-folder-open me-1"></i><?= __('user.product_content'); ?>
								</h6>
							</div>
							<div class="card-body bg-white">
								<!-- Placeholder when product type is Virtual (no files/videos) - not shown when card is visible -->
								<div class="product_content_placeholder py-3 text-muted text-center">
									<i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
									<p class="mb-0 small"><?= __('user.select_downloadable_or_lms_to_add_content'); ?></p>
								</div>
								<!-- Downloadable Files Section -->
								<div class="downloadable_file_div py-3" style="display: none;">
									<h6 class="fw-semibold mb-3 text-primary">
										<i class="fas fa-download me-1"></i><?= __('user.downloadable_product'); ?>
									</h6>
									<div class="d-flex gap-2 mb-3">
										<button type="button" class="btn btn-primary file-preview-button">
											<i class="fas fa-upload me-1"></i><?= __('user.downloadable_file'); ?>
											<input type="file" class="downloadable_file_input d-none" name="downloadable_files" multiple="">
										</button>
										<button type="button" class="btn btn-info" onclick="prepareS3ModalDownloadbale('input[name=\'product_multiple_image_s3[]\']', '.fileUpload-gallery')">
											<i class="fab fa-aws me-1"></i><?= __('admin.browse_amazon_s3_image') ?>
										</button>
									
</div>

									<div id="priview-table" class="table-container">
										<table class="table table-sm table-hover">
											<thead class="table-light">
												<?php foreach (isset($downloads) && is_array($downloads) ? $downloads : [] as $key => $value) { 
													if($value['type'] == 'zip'){
														?>
														<tr>
															<td width="70px"><div class="upload-priview up-<?= $value['type'] ?>">
</div></td>
															<td>
																<span class="fw-semibold"><?= $value['mask'] ?></span>
																<input type="hidden" name="keep_files[]" value="<?= $key ?>">
															</td>
															<td width="70px">
																<button type="button" class="btn btn-danger btn-sm remove-priview-server">
																	<i class="fas fa-trash"></i><?= __('admin.remove'); ?>
																</button>
															</td>
														</tr>
													<?php }elseif($value['srctype'] == 'AwsS3'){?>
														<tr>
															<td width="70px"><div class="upload-priview up-<?= $value['type'] ?>">
</div></td>
															<td>
																<a target="_blank" class="text-decoration-none fw-semibold" href="<?= $value['url'] ?>"><?= $value['mask'] ?></a>
																<input type="hidden" name="keep_files[]" value="<?= $key ?>">
															</td>
															<td width="70px">
																<button type="button" class="btn btn-danger btn-sm remove-priview-server">
																	<i class="fas fa-trash"></i><?= __('admin.remove'); ?>
																</button>
															</td>
														</tr>
													<?php }?>
												<?php } ?>
											</thead>
											<tbody></tbody>
										</table>
									
</div>
								
</div>

								<!-- Video Files Section (card-style like admin) -->
								<div class="video_file_div py-3" style="display: none;">
									<h6 class="fw-semibold mb-3 text-primary">
										<i class="fas fa-video me-1"></i><?= __('admin.lms_product'); ?>
									</h6>
									<div class="row g-2 mb-4">
										<div class="col-md-6">
											<label class="btn btn-outline-secondary text-center proSubType w-100 py-3 <?= ($product->product_type == 'video') ? 'btn-primary' : '' ?>" data-value="video" style="font-size: 15px;">
												<input type="radio" class="invisible" name="sub_product_type" value="video" <?= ($product->product_type == 'video') ? 'checked="checked"' : '' ?>>
												<i class="fas fa-file-video me-2"></i><?= __('admin.videos_product'); ?>
											</label>
										</div>
										<div class="col-md-6">
											<label class="btn btn-outline-secondary text-center proSubType w-100 py-3 <?= ($product->product_type == 'videolink') ? 'btn-primary' : '' ?>" data-value="videolink" style="font-size: 15px;">
												<input type="radio" class="invisible" name="sub_product_type" value="videolink" <?= ($product->product_type == 'videolink') ? 'checked="checked"' : '' ?>>
												<i class="fas fa-link me-2"></i><?= __('admin.video_product_link'); ?>
											</label>
										</div>
									</div>

									<!-- Video file upload (uploaded video files) -->
									<div class="video_file_uploader_div mt-4" style="display: none;">
										<button class="btn btn-success mb-3" type="button" id="add_section">
											<i class="fas fa-plus-circle me-1"></i><?= __('admin.add_section');?>
										</button>
										<div id="priview-table-video" class="table-responsive">
											<?php 
											$downloads_lms = isset($downloads) && is_array($downloads) ? $downloads : [];
											if(($product->product_type == 'video' || $product->product_type == '') && !empty($downloads_lms)) { 
												foreach ($downloads_lms as $key => $value) {
													if(isset($value['data']) && is_array($value['data'])) {
											?>
											<div class="lms-section-card border rounded p-3 mb-3 bg-light">
												<div class="lms-section-header d-flex align-items-center gap-2 mb-2 flex-wrap">
													<span class="badge bg-primary"><?= $key+1 ?></span>
													<input type="text" class="form-control form-control-sm flex-grow-1" name="section[<?= $key ?>]" value="<?= htmlspecialchars($value['title'] ?? '') ?>" placeholder="<?= __('admin.section_title') ?>">
													<label class="btn btn-outline-primary btn-sm">
														<i class="fas fa-upload me-1"></i><?= __('admin.upload_new_video') ?>
														<input class="videoFileUploadIP d-none" type="file" name="video_files[<?= $key ?>][]" multiple data-value="<?= $key ?>">
													</label>
													<button type="button" class="btn btn-outline-danger btn-sm remove-section"><i class="fas fa-trash"></i></button>
												</div>
												<div class="lms-section-body">
													<table class="table table-sm table-hover videofile-preview mb-0" id="videofile-preview<?= $key ?>">
														<thead class="table-light"><tr><th><?= __('admin.video_file') ?></th><th><?= __('admin.title') ?></th><th><?= __('admin.description') ?></th><th><?= __('admin.action') ?></th></tr></thead>
														<tbody>
														<?php foreach ($value['data'] as $innngerKey => $innerValue) { ?>
														<tr>
															<td>
																<div class="small mb-1"><i class="fas fa-video text-primary me-1"></i><?= htmlspecialchars($innerValue['mask'] ?? '') ?></div>
																<input type="hidden" name="keep_video_files[<?= $key ?>][]" value="<?= htmlspecialchars($innerValue['name'] ?? '') ?>">
																<input type="file" class="form-control form-control-sm updateVideoFile" name="updateVideoFile[]" data-main="<?= $key ?>" data-name="<?= $innngerKey ?>" data-old-name="<?= htmlspecialchars($innerValue['name'] ?? '') ?>">
																<?php $resId = $innerValue['name'] ?? 'res-'.$key.'-'.$innngerKey; ?>
																<div class="form-check mt-2">
																	<input type="checkbox" name="iszipResource[<?= $key ?>][<?= $innngerKey ?>]" value="<?= htmlspecialchars($innerValue['name'] ?? '') ?>" class="form-check-input updateResource" id="<?= htmlspecialchars($resId) ?>" <?= isset($innerValue['zip']['mask']) ? 'checked="checked"' : '' ?>>
																	<label for="<?= htmlspecialchars($resId) ?>" class="form-check-label small fw-semibold">
																		<i class="fas fa-paperclip me-1"></i><?= __('admin.lesson_resource'); ?>
																	</label>
																</div>
																<div class="resource lms-resource-box <?= isset($innerValue['zip']['mask']) ? '' : 'd-none' ?>" id="resource<?= htmlspecialchars($resId) ?>">
																	<?php if (isset($innerValue['zip']['mask'])): ?>
																		<div class="d-flex align-items-center gap-2 mb-2">
																			<i class="fas fa-file-archive text-warning"></i>
																			<span class="small fw-medium"><?= htmlspecialchars($innerValue['zip']['mask']) ?></span>
																			<span class="badge bg-light text-muted border"><?= $innerValue['zip']['size'] ?? '0' ?></span>
																		</div>
																	<?php endif ?>
																	<input type="file" class="form-control form-control-sm updateVideoFileResource mb-2" name="VideoFileZip[<?= $key ?>][]" accept=".zip" data-main="<?= $key ?>" data-name="<?= $innngerKey ?>" data-old-name="<?= htmlspecialchars($innerValue['name'] ?? '') ?>">
																	<input type="text" name="VideoFileResourceText[<?= $key ?>][<?= $innngerKey ?>]" value="<?= htmlspecialchars($innerValue['zip']['title'] ?? '') ?>" placeholder="<?= __('admin.resource_name') ?>" class="form-control form-control-sm">
																</div>
															</td>
															<td><input type="text" class="form-control form-control-sm" name="videotext[<?= $key ?>][]" value="<?= htmlspecialchars($innerValue['videotext'] ?? '') ?>" placeholder="<?= __('admin.title') ?>"></td>
															<td><input type="text" class="form-control form-control-sm" name="description[<?= $key ?>][]" value="<?= htmlspecialchars($innerValue['description'] ?? '') ?>" placeholder="<?= __('admin.description') ?>"></td>
															<td><button type="button" class="btn btn-outline-danger btn-sm remove-priview-server"><i class="fas fa-trash"></i></button></td>
														</tr>
														<?php } ?>
														</tbody>
													</table>
												</div>
											</div>
											<?php } } } ?>
										</div>
									</div>
									<!-- Video links (YouTube/Vimeo) -->
									<div class="video_link_div mt-4" style="display: none;">
										<button class="btn btn-success mb-3" type="button" id="add_section_link">
											<i class="fas fa-plus-circle me-1"></i><?= __('admin.add_section');?>
										</button>
										<div id="priview-table-video-link" class="table-responsive">
											<?php 
											if($product->product_type == 'videolink' && !empty($downloads_lms)) { 
												foreach ($downloads_lms as $key => $value) {
													if(isset($value['data']) && is_array($value['data'])) {
											?>
											<div class="lms-section-card border rounded p-3 mb-3 bg-light">
												<div class="lms-section-header d-flex align-items-center gap-2 mb-2 flex-wrap">
													<span class="badge bg-primary"><?= $key+1 ?></span>
													<input type="text" class="form-control form-control-sm flex-grow-1" name="sectionlink[<?= $key ?>]" value="<?= htmlspecialchars($value['title'] ?? '') ?>" placeholder="<?= __('admin.section_title') ?>">
													<button type="button" class="btn btn-outline-primary btn-sm addNewText" data-value="<?= $key ?>"><i class="fas fa-plus-circle me-1"></i><?= __('admin.video_product_link') ?></button>
													<button type="button" class="btn btn-outline-danger btn-sm remove-section"><i class="fas fa-trash"></i></button>
												</div>
												<div class="lms-section-body">
													<table class="table table-sm table-hover videolink-preview mb-0" id="videolink-preview<?= $key ?>">
														<thead class="table-light"><tr><th><?= __('admin.video_url') ?></th><th><?= __('admin.title') ?></th><th><?= __('admin.description') ?></th><th><?= __('admin.action') ?></th></tr></thead>
														<tbody>
														<?php foreach ($value['data'] as $innngerKey => $innerValue) { ?>
														<tr>
															<td>
																<div class="input-group input-group-sm mb-2">
																	<span class="input-group-text"><i class="fas fa-link"></i></span>
																	<input type="text" class="form-control" name="videolink[<?= $key ?>][]" value="<?= htmlspecialchars($innerValue['mask'] ?? '') ?>" placeholder="<?= __('admin.enter_your_product_video_link{youtube/vimeo}') ?>">
																</div>
																<?php $resIdLink = $innerValue['name'] ?? 'reslink-'.$key.'-'.$innngerKey; ?>
																<div class="form-check">
																	<input type="checkbox" name="iszipResource[<?= $key ?>][<?= $innngerKey ?>]" value="<?= htmlspecialchars($innerValue['name'] ?? '') ?>" class="form-check-input updateResource" id="<?= htmlspecialchars($resIdLink) ?>" <?= isset($innerValue['zip']['mask']) ? 'checked="checked"' : '' ?>>
																	<label for="<?= htmlspecialchars($resIdLink) ?>" class="form-check-label small fw-semibold">
																		<i class="fas fa-paperclip me-1"></i><?= __('admin.lesson_resource'); ?>
																	</label>
																</div>
																<div class="resource lms-resource-box <?= isset($innerValue['zip']['mask']) ? '' : 'd-none' ?>" id="resource<?= htmlspecialchars($resIdLink) ?>">
																	<?php if (isset($innerValue['zip']['mask'])): ?>
																		<div class="d-flex align-items-center gap-2 mb-2">
																			<i class="fas fa-file-archive text-warning"></i>
																			<span class="small fw-medium"><?= htmlspecialchars($innerValue['zip']['mask']) ?></span>
																			<span class="badge bg-light text-muted border"><?= $innerValue['zip']['size'] ?? '0' ?></span>
																		</div>
																	<?php endif ?>
																	<input type="file" class="form-control form-control-sm updateVideoFileResource mb-2" name="VideoFileZip[<?= $key ?>][]" accept=".zip" data-main="<?= $key ?>" data-name="<?= $innngerKey ?>" data-old-name="<?= $innngerKey ?>">
																	<input type="text" name="VideoFileResourceText[<?= $key ?>][<?= $innngerKey ?>]" value="<?= htmlspecialchars($innerValue['zip']['title'] ?? '') ?>" placeholder="<?= __('admin.resource_name') ?>" class="form-control form-control-sm">
																</div>
															</td>
															<td><input type="text" class="form-control form-control-sm" name="videotext[<?= $key ?>][]" value="<?= htmlspecialchars($innerValue['videotext'] ?? '') ?>" placeholder="<?= __('admin.title') ?>"></td>
															<td><input type="text" class="form-control form-control-sm" name="description[<?= $key ?>][]" value="<?= htmlspecialchars($innerValue['description'] ?? '') ?>" placeholder="<?= __('admin.description') ?>"></td>
															<td><button type="button" class="btn btn-outline-danger btn-sm remove-priview-server"><i class="fas fa-trash"></i></button></td>
														</tr>
														<?php } ?>
														</tbody>
													</table>
												</div>
											</div>
											<?php } } } ?>
										</div>
									</div>
								
</div>
							
</div>
						
</div>

						<!-- Product Settings -->
						<div class="row mt-4">
							<div class="col-md-3">
								<div class="card border h-100">
									<div class="card-body text-center">
										<h6 class="fw-semibold mb-3">
											<i class="fas fa-upload me-1"></i><?= __('user.allow_upload_file'); ?>
										</h6>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="allow_upload_file" value="0" checked id="upload_disable">
											<label class="form-check-label" for="upload_disable"><?= __('user.disable'); ?></label>
										
</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="allow_upload_file" value="1" <?= $product->allow_upload_file ? 'checked' : '' ?> id="upload_enable">
											<label class="form-check-label" for="upload_enable"><?= __('user.enable'); ?></label>
										
</div>
									
</div>
								
</div>
							
</div>
							<div class="col-md-3">
								<div class="card border h-100">
									<div class="card-body text-center">
										<h6 class="fw-semibold mb-3">
											<i class="fas fa-store me-1"></i><?= __('user.show_on_store'); ?>
										</h6>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="on_store" value="0" checked id="store_no">
											<label class="form-check-label" for="store_no"><?= __('user.no'); ?></label>
										
</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="on_store" value="1" <?= (int)$product->on_store ? 'checked' : '' ?> id="store_yes">
											<label class="form-check-label" for="store_yes"><?= __('user.yes'); ?></label>
										
</div>
									
</div>
								
</div>
							
</div>
							<div class="col-md-6 allow_shipping-option <?= $hideShipping ? 'd-none' : '' ?>">
								<div class="card border h-100">
									<div class="card-body text-center">
										<h6 class="fw-semibold mb-3">
											<i class="fas fa-shipping-fast me-1"></i><?= __('user.enable_shipping'); ?>
										</h6>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="allow_shipping" value="0" checked id="shipping_disable">
											<label class="form-check-label" for="shipping_disable"><?= __('user.disable'); ?></label>
										
</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="allow_shipping" value="1" <?= $product->allow_shipping ? 'checked' : '' ?> id="shipping_enable">
											<label class="form-check-label" for="shipping_enable"><?= __('user.enable'); ?></label>
										
</div>
									
</div>
								
</div>
							
</div>
						
</div>

					
</div>
				
</div>
			
</div>

			<!-- Sidebar - Commission & Settings -->
			<div class="col-xl-4 col-lg-5">
				<!-- Commission Settings -->
				<div class="card shadow-sm border-0 mb-4">
					<div class="card-header bg-success text-white py-3">
						<h4 class="card-title mb-0 fw-bold">
							<i class="fas fa-percent me-2"></i><?= __('user.commission') ?>
						</h4>
					
</div>
					<div class="card-body p-4">
						<!-- Product Status -->
						<div class="alert alert-info mb-4">
							<div class="d-flex align-items-center">
								<i class="fas fa-info-circle me-2"></i>
								<div>
									<strong><?= __('user.status'); ?>:</strong>
									<span class="ms-2"><?= product_status($product->product_status) ?></span>
								
</div>
							
</div>
						
</div>

						<!-- Affiliate Commission -->
						<div class="card border mb-4">
							<div class="card-header bg-light">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-users me-1"></i><?= __('admin.commission_for_affiliate'); ?>
								</h6>
							
</div>
							<div class="card-body">
								<!-- Click Commission -->
								<div class="mb-3">
									<label class="form-label fw-semibold"><?= __('admin.click_commission'); ?></label>
									<select name="affiliate_click_commission_type" class="form-select">
										<?php
										$commission_type = array(
											'default' => 'Default',
											'fixed' => 'Fixed',
										);
										foreach ($commission_type as $key => $value) { ?>
											<option <?= $seller->affiliate_click_commission_type == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
										<?php } ?>
									</select>

									<div class="toggle-container mt-2">
										<div class="default-value d-none">
											<div class="alert alert-light border">
												<small class="text-muted">
													<strong>Default Commission:</strong>
													<?php
													$comment_line = "";
													if($seller_setting->affiliate_click_amount && $seller_setting->affiliate_click_count){
														$comment_line .= c_format($seller_setting->affiliate_click_amount) ." Per ". (int)$seller_setting->affiliate_click_count ." Clicks";
													} else {
														$comment_line .= __('user.not_set');
													}
													echo $comment_line;
													?>
												</small>
											
</div>
										
</div>

										<div class="custom-value d-none">
											<div class="row mt-2">
												<div class="col-6">
													<div class="input-group">
														<span class="input-group-text">Clicks</span>
														<input name="affiliate_click_count" class="form-control" value="<?php echo $seller->affiliate_click_count; ?>" type="text" placeholder='Count'>
													
</div>
												
</div>
												<div class="col-6">
													<div class="input-group">
														<span class="input-group-text"><?= $CurrencySymbol ?></span>
														<input name="affiliate_click_amount" class="form-control" value="<?php echo $seller->affiliate_click_amount; ?>" type="text" placeholder='Amount'>
													
</div>
												
</div>
											
</div>
										
</div>
									
</div>
								
</div>

								<!-- Sale Commission -->
								<div class="row">
									<div class="col-md-6">
										<label class="form-label fw-semibold"><?= __('admin.sale_commission'); ?></label>
										<select name="affiliate_sale_commission_type" class="form-select">
											<?php
											$commission_type = array(
												'default' => 'Default',
												'percentage' => 'Percentage (%)',
												'fixed' => 'Fixed',
											);
											foreach ($commission_type as $key => $value) { ?>
												<option <?= $seller->affiliate_sale_commission_type == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
											<?php } ?>
										</select>
									
</div>
									<div class="col-md-6">
										<div class="toggle-container">
											<div class="default-value d-none">
												<label class="form-label"><?= __('user.default_commission') ?></label>
												<div class="alert alert-light border">
													<small class="text-muted">
														<?php
														$comment_line = "";
														if($seller_setting->affiliate_sale_commission_type == ''){
															$comment_line .= __('user.not_set');
														} else if($seller_setting->affiliate_sale_commission_type == 'percentage'){
															$comment_line .= (float)$seller_setting->affiliate_commission_value .'%';
														} else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
															$comment_line .= 'Fixed : '. c_format($seller_setting->affiliate_commission_value);
														}
														echo $comment_line;
														?>
													</small>
												
</div>
											
</div>
											<div class="percentage-value d-none">
												<label class="form-label"><?= __('user.sale_commission') ?></label>
												<input name="affiliate_commission_value" id="affiliate_commission_value" class="form-control" value="<?php echo $seller->affiliate_commission_value; ?>" type="text" placeholder='Commission Value'>
											
</div>
										
</div>
									
</div>
								
</div>
							
</div>
						
</div>

						<!-- Admin Commission -->
						<?php
						$defulat_admin_click_count = 0;
						$defulat_admin_click_amount = 0;
						$defulat_admin_sale_commission_type = "percentage";
						$defulat_admin_commission_value = 0;

						if($seller->admin_commission_value > 0) {
							$defulat_admin_sale_commission_type = $seller->admin_sale_commission_type;
							$defulat_admin_commission_value = $seller->admin_commission_value;
						} else if($vendor_setting['admin_sale_status'] == 1) {
							$defulat_admin_sale_commission_type = $vendor_setting['admin_sale_commission_type'];
							$defulat_admin_commission_value = $vendor_setting['admin_commission_value'];
						}

						if($seller->admin_click_amount > 0) {
							$defulat_admin_click_count = $seller->admin_click_count;
							$defulat_admin_click_amount = $seller->admin_click_amount;
						} else if($vendor_setting['admin_click_status'] == 1) {
							$defulat_admin_click_count = $vendor_setting['admin_click_count'];
							$defulat_admin_click_amount = $vendor_setting['admin_click_amount'];
						}
						?>

						<div class="card border mb-4" <?php if($vendor_setting['admin_click_status']==0 && $vendor_setting['admin_sale_status']==0) echo 'style="display: none;"'; else if($vendor_setting['admin_click_amount']==0 && $vendor_setting['admin_commission_value']==0) echo 'style="display: none;"'; ?>>
							<div class="card-header bg-light">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-user-shield me-1"></i><?= __('admin.commission_for_admin'); ?>
								</h6>
							
</div>
							<div class="card-body">
								<div class="mb-2" <?php if($vendor_setting['admin_click_status']==0) echo 'style="display: none;"'; else if($vendor_setting['admin_click_amount']<=0) echo 'style="display: none;"'; ?>>
									<div class="d-flex justify-content-between align-items-center">
										<span class="fw-semibold"><?= __('admin.click_commission'); ?>:</span>
										<span class="badge bg-primary">
											<?php 
											if((int)$product->product_id == 0 || $seller->admin_click_commission_type == '' || $seller->admin_click_commission_type == 'default'){
												echo c_format($vendor_setting['admin_click_amount']) ." Per ". (int)$vendor_setting['admin_click_count'] ." Clicks";
											} else{ 
												echo c_format($seller->admin_click_amount) ." Per ". (int)$seller->admin_click_count ." Clicks";
											} 
											?>
										</span>
									
</div>
								
</div>

								<div class="mb-2" <?php if($vendor_setting['admin_sale_status']==0) echo 'style="display: none;"'; else if ($vendor_setting['admin_commission_value']<=0) echo 'style="display: none;"';?>>
									<div class="d-flex justify-content-between align-items-center">
										<span class="fw-semibold"><?= __('admin.sale_commission'); ?>:</span>
										<span class="badge bg-success">
											<?php 
											$comment_line = "";
											if((int)$product->product_id == 0 || $seller->admin_sale_commission_type == '' || $seller->admin_sale_commission_type == 'default'){ 
												if($vendor_setting['admin_sale_commission_type'] == ''){
													$comment_line .= __('user.not_set');
												} else if($vendor_setting['admin_sale_commission_type'] == 'percentage'){
													$comment_line .= (float)$vendor_setting['admin_commission_value'] .'%';
												} else if($vendor_setting['admin_sale_commission_type'] == 'fixed'){
													$comment_line .= 'Fixed : '. c_format($vendor_setting['admin_commission_value']);
												}
											} else if($seller->admin_sale_commission_type == 'percentage'){
												$comment_line .= 'Percentage : '. (float)$seller->admin_commission_value .'%';
											} else if($seller->admin_sale_commission_type == 'fixed'){
												$comment_line .= 'Fixed : '. c_format($seller->admin_commission_value);
											} else {
												$comment_line .= __('user.warning') . ":" . __('user.commission_not_set');
											} 
											echo $comment_line;
											?>
										</span>
									
</div>
								
</div>
							
</div>
						
</div>

						<!-- Finalize Commission -->
						<div class="card border">
							<div class="card-header bg-light">
								<h6 class="card-title mb-0 fw-semibold">
									<i class="fas fa-calculator me-1"></i><?= __('user.finalize_commission'); ?>
								</h6>
							
</div>
							<div class="card-body">
								<div class="row text-center">
									<div class="col-4">
										<label class="form-label fw-semibold text-primary"><?= __('user.vendor') ?></label>
										<input type="text" readonly="" value="0" class="form-control text-center" id="ipt-vendor_commission">
									
</div>
									<div class="col-4">
										<label class="form-label fw-semibold text-success"><?= __('user.admin') ?></label>
										<input type="text" readonly="" value="0" class="form-control text-center" id="ipt-admin_sale_com">
									
</div>
									<div class="col-4">
										<label class="form-label fw-semibold text-info"><?= __('user.affiliate') ?></label>
										<input type="text" readonly="" value="0" class="form-control text-center" id="ipt-affiliate_sale_com">
									
</div>
								
</div>
							
</div>
						
</div>
					
</div>
				
</div>

				<!-- Admin Comments -->
				<div class="card shadow-sm border-0">
					<div class="card-header bg-info text-white py-3">
						<h4 class="card-title mb-0 fw-bold">
							<i class="fas fa-comments me-2"></i><?= __('user.admin_comments') ?>
						</h4>
					
</div>
					<div class="card-body p-0">
						<?php $comment = json_decode($seller->comment, 1); ?>
						<?php if($comment){ ?>
							<div class="chat-messages p-3" style="max-height: 300px; overflow-y: auto;">
								<?php foreach ($comment as $key => $value) { ?>
									<div class="d-flex mb-3 <?= $value['from'] == 'affiliate' ? 'justify-content-end' : '' ?>">
										<div class="message-bubble <?= $value['from'] == 'affiliate' ? 'bg-primary text-white ms-5' : 'bg-light me-5' ?> p-3 rounded">
											<small class="text-muted d-block"><?= $value['from'] == 'affiliate' ? 'You' : 'Admin' ?></small>
											<?= $value['comment'] ?>
										
</div>
									
</div>
								<?php } ?>
							
</div>
						<?php } ?>
						<div class="p-3 border-top">
							<textarea class="form-control" rows="3" placeholder="Enter message and save product to send" name="admin_comment"></textarea>
						
</div>
					
</div>
					<div class="card-footer bg-light">
						<div class="d-flex justify-content-end gap-2">
							<span class="loading-submit"></span>
							<?php
							$needs_approval = (isset($needs_product_approval) && ($needs_product_approval === 1 || $needs_product_approval === '1')) ? 1 : 0;
							if((int)$product->product_id > 0){ ?>
								<?php if($needs_approval){ ?>
								<button type="submit" class="btn btn-warning btn-submit" name="ask_to_review">
									<i class="fas fa-eye me-1"></i><?= __('user.send_to_review') ?>
								</button>
								<?php } ?>
								<button type="submit" class="btn btn-info btn-submit" name="save_continue">
									<i class="fas fa-save me-1"></i><?= __('user.save_continue_editing') ?>
								</button>

							<?php } ?>
							<button type="submit" class="btn btn-success btn-submit" name="save">
								<i class="fas fa-save me-1"></i>
								<?php
								if((int)$product->product_id == 0) {
									echo $needs_approval ? __('user.save_and_submit_for_review') : __('user.save_and_publish');
								} else {
									echo __('user.save');
								}
								?>
							</button>
						
</div>
					
</div>
				
</div>
			
</div>
		
</div>
	</form>

</div>



<!-- Include the original JavaScript -->
<script type="text/javascript">
	// Translation variables
	var txtVideoProductLink = <?= json_encode(__('admin.video_product_link')) ?>;
	var txtAddVideoLink = <?= json_encode(__('admin.add_video_link')) ?>;
	var txtVideoUrl = <?= json_encode(__('admin.video_url')) ?>;
	
	$("input[name=allow_country]").change(function(){
		if($("input[name=allow_country]:checked").val() == "0"){
			$(".country-chooser").hide();
		} else {
			$(".country-chooser").show();
		}
	})
	$("input[name=allow_country]:checked").trigger('change');

	$("#country_id").on('change',function(){
		var country = $(this).val();
		$('#state_id').prop("disabled",true)
		$.ajax({
			url: '<?php echo base_url('get_state') ?>',
			type: 'post',
			dataType: 'json',
			data: {country_id : country},
			success: function (json) {
				$('#state_id').prop("disabled",false)
				if(json){
					var html = '<option value="">Select State</option>';
					$.each(json, function(k,v){
						html += '<option value="'+v.id+'">'+v.name+'</option>';
					});
					$('#state_id').html(html);
				}
			}
		});
	});

	$("input[name=allow_shipping]").on("change", function() {
		if ($(this).val() == '1') {
			$(".shipping-info-section").show();
		} else {
			$(".shipping-info-section").hide();
		}
	});

	$("select[name=affiliate_click_commission_type]").on("change",function(){
		$con = $(this).parents(".mb-3");
		$con.find(".toggle-container .percentage-value, .toggle-container .custom-value").addClass('d-none');

		if($(this).val() == 'default'){
			$con.find(".toggle-container .default-value").removeClass("d-none");
		}else{
			$con.find(".toggle-container .custom-value").removeClass("d-none");
		}
	})
	$("select[name=affiliate_click_commission_type]").trigger("change");

	$("select[name=affiliate_sale_commission_type]").on("change",function(){
		$con = $(this).parents(".row");
		$con.find(".toggle-container .percentage-value, .toggle-container .default-value").addClass('d-none');

		if($(this).val() == 'default'){
			$con.find(".toggle-container .default-value").removeClass("d-none");
		}else{
			$con.find(".toggle-container .percentage-value").removeClass("d-none");
		}
	})
	$("select[name=affiliate_sale_commission_type]").trigger("change");
</script>

<div id="modal-variants" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= __('user.add_varaition') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      
</div>
      <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
		<div class="row">
			<div class="col-5">
				<div class="form-group">
					<label for="variation_type"><?= __('user.varaition_type') ?></label>
					<select class="form-control" id="variation_type">
						<option value="colors"><?= __('user.color') ?></option>
						<option value="other"><?= __('user.other_variation') ?></option>
					</select>
				
</div>
			
</div>
			<div class="col-7">
				<div class="form-group other_variation_title_input" style="display:none">
					<label for="other_variation_title"><?= __('user.varaition_title') ?></label>
					<input type="text" class="form-control" id="other_variation_title" maxlength="25" placeholder="Variation Title">
				
</div>
			
</div>
		
</div>
		<div class="colors-list">
			
		
</div>
		<div class="features-list" style="display:none">
			
		
</div>
      
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary add-variation-to-form"><?= __('user.add_variants') ?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('user.close') ?></button>
      
</div>
    
</div>
  
</div>

</div>
<script type="text/javascript">

	$("#product_tags").select2({
		tags: true,
		tokenSeparators: [',']
	})

	$(document).on('click', '.btn-add-variants', function(){
		prepareVariationModal();
	});

	
	$(document).on('click', '.btn-delete-variants', function(){
		$(this).closest('tr').remove();
	});

	$(document).on('click', '.btn-edit-variants', function(){
		let options;
		let row = $(this).closest('tr');
		let vType = $(this).data('variation-type');
		if(vType == "colors") {
			$("#variation_type").val('colors');
			options = getOptions("tr[data-variation-type='"+vType+"'] input[name='variations["+vType+"][code][]']","tr[data-variation-type='"+vType+"'] input[name='variations["+vType+"][name][]']", "tr[data-variation-type='"+vType+"'] input[name='variations["+vType+"][price][]']");
		} else {
			$("#variation_type").val('other');
			$('#other_variation_title').val(vType);
			options = getOptions("tr[data-variation-type='"+vType+"'] input[name='variations["+vType+"][name][]']", "tr[data-variation-type='"+vType+"'] input[name='variations["+vType+"][price][]']");
		}
		prepareVariationModal(options);
		$("#variation_type").trigger('change');
	});

	function prepareVariationModal(options = null){
		let colors = "";
		let features = "";
		if(options != null) {
			for (let index = 0; index < options.length; index++) {
				if(options[index].code) {
					colors += `<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label  class="control-label">Color</label>
								<input value="`+options[index].code+`" class="form-control jscolor color-code" data-jscolor type="text">
							
</div>
						
</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Color Name</label>
								<input value="`+options[index].name+`" class="form-control color-name" type="text">
							
</div>
						
</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Additional Price</label>
								<input value="`+options[index].price+`" class="form-control color-price" type="number">
							
</div>
						
</div>
						<div class="col-md-1 pt-4"><span class="btn btn-danger btn-remove-variation" style="margin-top:6px;"><i class="fa fa-trash"></i></span>
</div>
					
</div>`;
				} else {
					let features_name = options[index].name ? options[index].name : options[index];
					let features_price = options[index].price ? options[index].price : 0;
					features += `<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label  class="control-label">Variation Option</label>
								<input value="`+features_name+`" class="form-control variation-option" type="text">
							
</div>
						
</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Additional Price</label>
								<input value="`+features_price+`" class="form-control variation-price" type="number">
							
</div>
						
</div>
						<div class="col-md-1 pt-4"><span class="btn btn-danger btn-remove-variation" style="margin-top:6px;"><i class="fa fa-trash"></i></span>
</div>
					
</div>`;
				}			
			}
		}

		$('#modal-variants .colors-list').html(colors+`<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label  class="control-label">Color</label>
					<input value="#FFFFFF" class="form-control jscolor color-code" data-jscolor type="text">
				
</div>
			
</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Color Name</label>
					<input value="" class="form-control color-name" type="text">
				
</div>
			
</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Additional Price</label>
					<input value="" class="form-control color-price" type="number">
				
</div>
			
</div>
			<div class="col-md-1 pt-4"><span class="btn btn-primary btn-add-color" style="margin-top:6px;"><i class="bi bi-plus-circle"></i></span>
</div>
		
</div>`);

		$('#modal-variants .features-list').html(features+`<div class="row">
			<div class="col-md-8">
				<div class="form-group">
					<label  class="control-label">Variation Option</label>
					<input value="" class="form-control variation-option" type="text">
				
</div>
			
</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Additional Price</label>
					<input value="" class="form-control variation-price" type="number">
				
</div>
			
</div>
			<div class="col-md-1 pt-4"><span class="btn btn-primary btn-add-feature" style="margin-top:6px;"><i class="bi bi-plus-circle"></i></span>
</div>
		
</div>`);
		jscolor.install();
		$('#modal-variants').modal('show');
	}

	$(document).on('click', '.add-variation-to-form', function(){
		let variation = {
			name : null,
			options : []
		}
		if($('#modal-variants #variation_type').val() == 'colors') {
			variation.name = 'colors';
			variation.options = getOptions("#modal-variants .color-code", "#modal-variants .color-name", "#modal-variants .color-price");
		} else {
			variation.name = $('#modal-variants #other_variation_title').val();
			variation.name = variation.name.replace(/\s+/g, '-').toLowerCase();
			variation.options = getOptions("#modal-variants .variation-option", "#modal-variants .variation-price");
		}

		if(variation.name != null && variation.name != "" && variation.options.length > 0) {
			let row = `<td><strong>`+toTitleCase(variation.name)+` :</strong></td><td>`;
			for (let index = 0; index < variation.options.length; index++) {
				if(variation.name == 'colors') {
					row += (index == 0) ? toTitleCase(variation.options[index]['name']) : ", "+toTitleCase(variation.options[index]['name']);
					row += `<input type='hidden' name='variations[`+variation.name+`][name][]' value='`+variation.options[index]['name']+`'>`;
					row += `<input type='hidden' name='variations[`+variation.name+`][code][]' value='`+variation.options[index]['code']+`'>`;
					row += `<input type='hidden' name='variations[`+variation.name+`][price][]' value='`+variation.options[index]['price']+`'>`;
				} else {
					row += (index == 0) ? toTitleCase(variation.options[index]['name']) : ", "+toTitleCase(variation.options[index]['name']);
					row += `<input type='hidden' name='variations[`+variation.name+`][name][]' value='`+variation.options[index]['name']+`'>`;
					row += `<input type='hidden' name='variations[`+variation.name+`][price][]' value='`+variation.options[index]['price']+`'>`;
				}
			}
			row += `</td>
			<td>
				<span data-variation-type="`+variation.name+`" class="btn btn-md btn-warning btn-edit-variants"><i class="fa fa-edit"></i></span>
				<span class="btn btn-md btn-danger btn-delete-variants"><i class="fa fa-trash"></i></span>
			</td>`;

			if($('#product-variations tr[data-variation-type="'+variation.name+'"]').length != 0){
				$('#product-variations tr[data-variation-type="'+variation.name+'"]').html(row);
			} else {
				$('#product-variations').append(`<tr data-variation-type="`+variation.name+`">`+row+`</tr>`);
			}
		}

		$('#modal-variants').modal('hide');
	});

	$(document).on('click', '.btn-add-color', function(){
		$(this).before(`<span class="btn btn-danger btn-remove-variation" style="margin-top:6px;"><i class="fa fa-trash"></i></span>`);
		$(this).remove();
		$('.colors-list').append(`
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label  class="control-label">Color</label>
						<input value="#FFFFFF" class="form-control jscolor color-code" data-jscolor type="text">
					
</div>
				
</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Color Name</label>
						<input value="" class="form-control color-name" type="text">
					
</div>
				
</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Additional Price</label>
						<input value="" class="form-control color-price" type="number">
					
</div>
				
</div>
				<div class="col-md-1 pt-4"><span class="btn btn-primary btn-add-color" style="margin-top:6px;"><i class="bi bi-plus-circle"></i></span>
</div>
			
</div>
		`);
		jscolor.install();
	});

	$(document).on('click', '.btn-add-feature', function(){
		$(this).before(`<span class="btn btn-danger btn-remove-variation" style="margin-top:6px;"><i class="fa fa-trash"></i></span>`);
		$(this).remove();
		$('.features-list').append(`
			<div class="row">
				<div class="col-md-8">
					<div class="form-group">
						<label  class="control-label">Variation Option</label>
						<input value="" class="form-control variation-option" type="text">
					
</div>
				
</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Additional Price</label>
						<input value="" class="form-control variation-price" type="number">
					
</div>
				
</div>
				<div class="col-md-1 pt-4"><span class="btn btn-primary btn-add-feature" style="margin-top:6px;"><i class="bi bi-plus-circle"></i></span>
</div>
			
</div>
		`)
	});

	$(document).on('click', '.btn-remove-variation', function(){
		$(this).closest(`.row`).remove();
	});

	$(document).on('keypress', '#other_variation_title', function (event) {
		var regex = new RegExp("^[a-zA-Z0-9 ]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
		event.preventDefault();
		return false;
		}
	});

	$(document).on('change', "#variation_type", function(){
		if($(this).val() == 'other') {
			$('.other_variation_title_input').show();
			$('.colors-list').hide();
			$('.features-list').show();
		} else {
			$('.other_variation_title_input').hide();
			$('.colors-list').show();
			$('.features-list').hide();
		}
	});

	function getOptions(element1, element2, element3 = null) {
		let options = [];
		if(element3 != null) {
			let codes = []
			$(element1).each(function() {
				codes.push($(this).val());
			});
			let names = []
			$(element2).each(function() {
				names.push($(this).val());
			});
			let price = []
			$(element3).each(function() {
				price.push($(this).val());
			});
			for (let index = 0; index < codes.length; index++) {
				if(codes[index] != null && codes[index] != "" && names[index] != null && names[index] != "") {
					options.push({
						code : codes[index],
						name : names[index],
						price : price[index]
					});
				}
			}
		} else {
			let names = []
			$(element1).each(function() {
				names.push($(this).val());
			});
			let price = []
			$(element2).each(function() {
				price.push($(this).val());
			});
			for (let index = 0; index < names.length; index++) {
				if(names[index] != null && names[index] != "") {
					options.push({
						name : names[index],
						price : price[index]
					});
				}
			}
		}
		return options;
	}

	function toTitleCase(str) {
		return str.replace(/(?:^|\s)\w/g, function(match) {
			return match.toUpperCase();
		});
	}


	var cache = {};

	$(".comment-products").animate({ scrollTop: $('.comment-products').prop("scrollHeight")}, 1000);

	$(".commission-setting :input, input[name=product_price]").on("change",calcCommission);
	var xhrCommission;
	function calcCommission(){
		$this = $(this);
		if(xhrCommission && xhrCommission.readyState != 4){
			xhrCommission.abort()
		}

		xhrCommission = $.ajax({
			url:'<?= base_url('usercontrol/calc_commission') ?>',
			type:'POST',
			dataType:'json',
			data:$(".commission-setting :input, input[name=product_price], input[name=product_id]"),
			success:function(json){
				if(json['success']){
					$("#ipt-vendor_commission").val(json['commission']['vendor_commission']);
					$("#ipt-admin_sale_com").val(json['commission']['admin_sale_com']);
					$("#ipt-affiliate_sale_com").val(json['commission']['affiliate_sale_com']);
				}
			},
		})
	}calcCommission();

	$("#category_auto").autocomplete({
		source: function(request, response) {
			var term = request.term;
			if (term in cache) { response(cache[term]); return; }
			$.getJSON('<?= base_url('usercontrol/category_auto') ?>', request, function(data) {
				cache[term] = data;
				response(data);
			});
		},
		minLength: 0,
		select: function(event, ui) {
			event.preventDefault();
			$("#category_auto").val('').blur();
			if ($(".category-selected input[value='" + ui.item.value + "']").length === 0) {
				$(".category-selected").append(
					'<li class="list-group-item d-flex justify-content-between align-items-center">' +
					'<span class="fw-medium">' + $('<span>').text(ui.item.label).html() + '</span>' +
					'<input type="hidden" name="category[]" value="' + ui.item.value + '">' +
					'<button type="button" class="btn btn-danger btn-sm remove-category"><i class="bi bi-trash"></i></button>' +
					'</li>'
				);
				$(".category-selected-wrap").removeClass('d-none');
			}
		},
	}).on('focus', function() {
		$(this).autocomplete('search', $(this).val());
	});

	$(document).on('click', '.remove-category', function() {
		$(this).closest('li').remove();
		if ($('.category-selected li').length === 0) {
			$('.category-selected-wrap').addClass('d-none');
		}
	});

	function readURLBanner(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#bannerImage').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$(".btn-submit").on('click',function(evt){

        evt.preventDefault();
        $btn = $(this);
        var formData = new FormData($("#form_form")[0]);
		var is_product_type = $('input[name="product_type"]:checked').val();
		var mergeFiles = [];
		mergeFiles  = is_product_type=="video" ? ($('input[name="sub_product_type"]:checked').val() =='videolink' ? fileArrayVideoText : fileArrayVideo) : fileArray;
		if(mergeFiles.length!=0)
			var s3Files = [];
			$.each(mergeFiles, function(i,j){ 
				
				var fileData = {};
				if(j.imageurl == ''){
				 formData.append("downloadable_file[]", j.rawData); 
				}else{
					
					
					fileData.imageSrcType = j.imageType;
			        fileData.name = j.name;
			        fileData.type = j.type;
			        fileData.imageurl = j.imageurl;
			        s3Files.push(fileData);
					
					formData.append("downloadable_file_s3", JSON.stringify(s3Files));
				
				}

			});
		
		if(video_fileArr.length !=0) {
			$.each(video_fileArr, function(i,j){ 
				var index = j[0];
				console.log(j[1]);
				if(j[2] !== undefined) {
					formData.append("lms_videos_files_update["+index+"]["+j[2]+"]", j[1]);
					formData.append("lms_videos_files_update_duration["+index+"]["+j[2]+"]", j[1].duration);
					if(video_fileZipArr.length !=0){

						if(typeof(video_fileZipArr[i][2])!=='undefined'){
							video_fileZipArr[i][2] = video_fileZipArr[i][2] =='' ?  j[2] : video_fileZipArr[i][2]; 
							formData.append("lms_videos_files_zip_update["+index+"]["+video_fileZipArr[i][2]+"]", video_fileZipArr[i][1]);
						}
					}
				}else {
					formData.append("lms_videos_files["+index+"][]", j[1]);
					formData.append("lms_videos_files_duration["+index+"][]", j[1].duration);
					if(video_fileZipArr[i]!==undefined){
						formData.append("lms_videos_files_zip["+index+"][]", video_fileZipArr[i][1]);
					}
				}
			});
		} else {
			$.each(video_fileZipArr, function(i,j){ 
				var index = j[0];

				if(video_fileZipArr[i][2]!==undefined){ 
					formData.append("lms_videos_files_zip_update["+index+"]["+video_fileZipArr[i][2]+"]", video_fileZipArr[i][1]);
				} else {
					formData.append("lms_videos_files_zip["+index+"][]", video_fileZipArr[i][1]);
				}
			});
		}
 
        $.each(fileArray, function(i,j){ formData.append("downloadable_file[]", j.rawData); });
        formData.append("action", $(this).attr("name"));
		
        formData = formDataFilter(formData);
        $this = $("#form_form");	       
        
       	$btn.btn("loading");
        $.ajax({
            url:'<?= base_url('usercontrol/store_save_product') ?>',
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
                        console.log( 'Uploaded percent', percentComplete );
                        $('.loading-submit').text(percentComplete + "% Loading");
                    }
                }, false );

                jqXHR.addEventListener( "progress", function ( evt ){
                    if ( evt.lengthComputable ){
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        $('.loading-submit').text("Save");
                    }
                }, false );
                return jqXHR;
            },
            error:function(){
                $btn.btn("reset");
                $('.loading-submit').hide().text('');
            },
            complete:function(){
                $btn.btn("reset");
                $('.loading-submit').hide().text('');
            },
            success:function(result){
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                // Check if this is a save_continue action
                var action = $btn.attr("name");
                if(action == 'save_continue' && result['success']){
                    // Show modern toast notification
                    showToast('<?= __('user.saved_successfully') ?>', '<?= __('user.changes_saved') ?>', 'success');
                    return;
                }
                
                if(result['location']){
                    window.location = result['location'];
                }
                if(result['errors']){
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');

                        if($ele.length){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>"+ j +"</span>");
                        } else {
                        	showToast('<?= __('user.error') ?>', j, 'error');
                        }	
                    });
                }
            },
        });
	    
        return false;
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

    $( document ).ready(function() {
        $('input[name="product_type"]:checked').trigger('change');
        $('input[name="sub_product_type"]:checked').trigger('change');
        $('[name="allow_for"]').trigger("change");
        sumNote($('.summernote-img'));
    });

    var fileArray = [];
    var video_fileArr=[];
	var video_fileZipArr=[];
	// Trigger file input when Downloadable File button is clicked (input is hidden with d-none)
	$(document).on('click', '.file-preview-button', function(e) {
		var $input = $(this).find('input[type="file"]');
		if ($input.length && !$(e.target).is('input[type="file"]')) {
			e.preventDefault();
			$input.trigger('click');
		}
	});
    $('.downloadable_file_input').change(function(e){
        $.each(e.target.files, function(index, value){
            var fileReader = new FileReader(); 
            fileReader.readAsDataURL(value);
            fileReader.name = value.name;
            fileReader.rawData = value;
            fileReader.imageType = 'local';
			fileReader.imageurl = '';
            fileArray.push(fileReader);
        });

        render_priview();
    });

    var getFileTypeCssClass = function(filetype) {
        var fileTypeCssClass;
        fileTypeCssClass = (function() {
            switch (true) {
                case /image/.test(filetype): return 'image';
                case /video/.test(filetype): return 'video';
                case /audio/.test(filetype): return 'audio';
                case /pdf/.test(filetype): return 'pdf';
                case /csv|excel/.test(filetype): return 'spreadsheet';
                case /powerpoint/.test(filetype): return 'powerpoint';
                case /msword|text/.test(filetype): return 'document';
                case /zip/.test(filetype): return 'zip';
                case /rar/.test(filetype): return 'rar';
                default: return 'default-filetype';
            }
        })();
        return fileTypeCssClass;
    };

    function render_priview() {
        var html = '';
        
        $.each(fileArray, function(i,j){
		
		if(j.imageurl == ""){
			html += '<tr>';
			html += '    <td width="70px"> <div class="upload-priview up-'+ getFileTypeCssClass(j.rawData.type) +'" ></div></td>';
			html += '    <td>'+ j.name +'</td>';
			html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" data-id="'+ i +'" ><?= __('admin.remove') ?></button></td>';
			html += '</tr>';
		}else{
			html += '<tr>';
			html += '    <td width="70px"> <div class="upload-priview up-'+ getFileTypeCssClass(j.type) +'" ></div></td>';
			html += '    <td>'+ j.name +'</td>';
			html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" data-id="'+ i +'" ><?= __('admin.remove') ?></button></td>';
			html += '</tr>';
		}
		
	})

        $("#priview-table tbody").html(html);
    }

    $("#priview-table").delegate('.remove-priview','click', function(){
        if(!confirm('<?= __('user.are_you_sure') ?>')) return false;

        var index = $(this).attr("data-id");
        fileArray.splice(index,1);
        render_priview()
    })
    $("#priview-table-video").delegate('.remove-priview','click', function(){
	if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

	var index = $(this).attr("data-id");
	fileArrayVideo.splice(index,1);
	render_priview()
})

    $(".remove-priview-server").on('click',function(){
	if(!confirm("<?= __('admin.are_you_sure') ?>")) return false;

	var attr = $(this).attr('data-main');

	if (typeof attr !== 'undefined' && attr !== false) {
		var index = $(this).attr("data-main");
		var name = $(this).attr("data-name");
		for (var i = 0; i < video_fileArr.length; i++) {
			if(video_fileArr[i][0] == index && video_fileArr[i][1].name ==name) {
				console.log("Value Matched and Deletedt at :",i);
				video_fileArr.splice(i,1);
			}
		}
	}
	$(this).parents("tr").remove();
})

    //Video File Uplaod
			var fileArrayVideo = [];
			$('.downloadable_file_input_video').change(function(e){
				$.each(e.target.files, function(index, value){
					var fileReader = new FileReader(); 
					fileReader.readAsDataURL(value);
					fileReader.name = value.name;
					fileReader.rawData = value;
					fileArrayVideo.push(fileReader);
				});

				render_priview_video();
			});

		function render_priview_video() {
				var html = '';

			$.each(fileArrayVideo, function(i,j){
				html += '<tr>';
				html += '    <td width="70px"> <div class="upload-priview up-'+ getFileTypeCssClass(j.rawData.type) +'" ></div></td>';
				html += '    <td>'+ j.name +'</td>';
				html += '    <td><input type="text" class="form-control" name="videotext[]" value="" placeholder="Add Video Title"></td>';
				html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" data-id="'+ i +'" ><?= __('admin.remove') ?></button></td>';
				html += '</tr>';
			})

				$("#priview-table-video tbody").html(html);
			}

			var fileArrayVideoText = [];
			function render_priview_video_link() {
				var html = '';

				$.each(fileArrayVideoText, function(i,j){
					html += '<tr>';
					html += '    <td width="70px"><input type="text" placeholder="Video Link"  name="videolink[]" class="form-control" ></td>';
					html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" data-id="'+ i +'" ><?= __('admin.remove') ?></button></td>';
					html += '</tr>';
				})

				$("#priview-table-video-link tbody").html(html);
			}

			$("#priview-table-video").delegate('.remove-priview','click', function(){
				if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

				var index = $(this).attr("data-id");
				fileArray.splice(index,1);
				render_priview_video()
			})

			$(document).on("click",".remove-local-uploaded",function(e){
				e.preventDefault();
				if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

				var index = $(this).attr("data-main");
				var name = $(this).attr("data-name");
				var zipname = $(this).attr("data-zip");
				for (var i = 0; i < video_fileArr.length; i++) {
					if(video_fileArr[i][0] == index && video_fileArr[i][1].name ==name) {
						video_fileArr.splice(i,1);
					}
				}
				for (var i = 0; i < video_fileZipArr.length; i++) {
					if(video_fileZipArr[i][0] == index && video_fileZipArr[i][1].name ==zipname) {
						video_fileZipArr.splice(i,1);
					}
				}
				$(this).parent().parent().remove();

			})

			$("#addMoreLinktext").click(function(event) {


				fileArrayVideoText.push(new Date());
				render_priview_video_link();
			});

    $('input[name="sub_product_type"]').on('change',function(){
				var val = $(this).val();
				$('.proSubType').removeClass('btn-primary').addClass('btn-outline-secondary');
				$(this).closest('.proSubType').removeClass('btn-outline-secondary').addClass('btn-primary');
				if(val== 'video') {
					$('.video_file_uploader_div').show();
					$('.video_link_div').hide();

				} else {
					$('.video_file_uploader_div').hide();
					$('.video_link_div').show();

				}
			});

    $('input[name="product_type"]').on('change',function(){
        var val = $(this).val();
        var isPhysical = (val == 'virtual' || val == '');
        var isDownloadableOrLms = (val == 'downloadable' || val == 'video');
        $('#vendor_shipping_collapse_div').toggleClass('d-none', !isPhysical);
        $('.allow_shipping-option').toggleClass('d-none', !isPhysical);
        $('#product_content_card').toggleClass('d-none', !isDownloadableOrLms);
        if(val == 'downloadable'){ 
        	$('.downloadable_file_div').show();
        	$('.video_file_div').hide();
        	$('.product_content_placeholder').hide();
        }else if(val=="video") {
        	$('.video_file_div').show();
        	$('.downloadable_file_div').hide();
        	$('.product_content_placeholder').hide();
        }else{ 
        	$('.downloadable_file_div').hide();
        	$('.video_file_div').hide(); 
        	$('.product_content_placeholder').show();
        }
    });
    $('input[name="product_type"]:checked').trigger('change');

    $('input[name="sub_product_type"]:checked').trigger('change');

    var totalSection = $("#priview-table-video").find(".lms-section-card, fieldset").length;
			var txtUploadVideo = <?= json_encode(__('admin.upload_new_video')) ?>;
			$("#add_section").on("click",function(e){
				e.preventDefault();
				var html =`<div class="lms-section-card border rounded p-3 mb-3 bg-light">
					<div class="lms-section-header d-flex align-items-center gap-2 mb-2 flex-wrap">
						<span class="badge bg-primary">`+(totalSection+1)+`</span>
						<input type="text" class="form-control form-control-sm flex-grow-1" name="section[`+totalSection+`]" value="" placeholder="<?= __('admin.section_title') ?>">
						<label class="btn btn-outline-primary btn-sm mb-0">
							<i class="fas fa-upload me-1"></i>`+txtUploadVideo+`
							<input class="videoFileUploadIP d-none" type="file" name="video_files[`+totalSection+`][]" multiple data-value="`+totalSection+`">
						</label>
						<button type="button" class="btn btn-outline-danger btn-sm remove-section"><i class="fas fa-trash"></i></button>
					</div>
					<div class="lms-section-body">
						<table class="table table-sm table-hover videofile-preview mb-0" id="videofile-preview`+totalSection+`">
							<thead class="table-light"><tr><th><?= __('admin.video_file') ?></th><th><?= __('admin.title') ?></th><th><?= __('admin.description') ?></th><th><?= __('admin.action') ?></th></tr></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>`;
				$("#priview-table-video").append(html); 
				totalSection++; 
			});
			$(document).on("click",".remove-section",function(){
				if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;
				var $section = $(this).closest('.lms-section-card, fieldset');
				if($section.closest('#priview-table-video-link').length) totalSectionlink--;
				else totalSection--;
				var localVideoElements = $section.find('table').find('.remove-local-uploaded,.remove-priview-server');
				localVideoElements.each(function(index, el) {
					var attr = $(this).attr('data-main');
					if (typeof attr !== 'undefined' && attr !== false) {

						var index = $(this).attr("data-main");
						var name = $(this).attr("data-name");
						var zipname = $(this).attr("data-zip");
						for (var i = 0; i < video_fileArr.length; i++) {
							if(video_fileArr[i][0] == index && video_fileArr[i][1].name ==name) {
								video_fileArr.splice(i,1);
							}
						}

						for (var i = 0; i < video_fileZipArr.length; i++) {
							if(video_fileZipArr[i][0] == index && video_fileZipArr[i][1].name ==zipname) {
								video_fileZipArr.splice(i,1);
							}
						}
					}
				});
				$(this).closest('.lms-section-card, fieldset').remove();
				console.log(video_fileArr);
			});
			function getFileSize(_size) {
				var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
				i=0;while(_size>900){_size/=1024;i++;}
				var exactSize = (Math.round(_size*100)/100)+' '+fSExt[i];
				return exactSize;
			}


			var uploadedVideosDurations = 0;

			window.URL = window.URL || window.webkitURL;

			async function setFileInfo(that, _callback) {
			  var files = that.files;
			  uploadedVideosDurations = 0
			  var video = document.createElement('video');
			  video.preload = 'metadata';

			  video.onloadedmetadata = await function() {
			    window.URL.revokeObjectURL(video.src);
			    var duration = video.duration;
			    uploadedVideosDurations = duration;

			    _callback();
			  }

			  video.src = URL.createObjectURL(files[0]);;
			}


			$(document).on("change",".videoFileUploadIP", async function(e){
				
				that = $(this);

				await setFileInfo(this, function() {
					var id =$(that).data('value');
					
					var newRow ="";
					for (var i = 0; i < e.target.files.length; i++) {
						var rsID = Math.floor((Math.random() * 100000000) + 1);
						e.target.files[i];

						let updatedFile = e.target.files[i];
						updatedFile.duration = uploadedVideosDurations

						video_fileArr.push([id, updatedFile]);
						var  fileSize = getFileSize(e.target.files[i].size);
						newRow +=`<tr>
						<td>`+e.target.files[i].name+`( <strong>`+fileSize+`</strong> )`+`
						<div class="mt-3">
						<input type="checkbox" name="iszipResource[`+id+`][]" value="0" class="isResource" id="`+rsID+`">
						<label for="resource`+rsID+`" class="ml-1 form-check-label mb-3">
						Lesson Resource
						</label>
						
</div>
						<div class="resource d-none" id="resource`+rsID+`">
						<p></p><input type="file" data-main="`+id+`" class="VideoFileResource" name="VideoFileZip[`+id+`][]">
						<p></p>
						<input type="text" name="VideoFileResourceText[`+id+`][]" value="" placeholder="Resource Name" class="form-control mt-3">
						
</div>
						</td>
						<td><input type="text" class="form-control" name="videotext[`+id+`][]" value="" placeholder="Add Video Title"></td>
						<td><input type="text" class="form-control" name="description[`+id+`][]" value="" placeholder="Add Video Description"></td>
						<td width="70px"><button type="button" class="btn btn-danger btn-sm remove-local-uploaded" data-name="`+e.target.files[i].name+`" data-main="`+id+`"><?= __('admin.remove') ?></button></td></tr>`;
					}


					console.log(video_fileArr);
					$("#priview-table-video").find("#videofile-preview"+id+" tbody").append(newRow);
				});
			});

			$(document).on("click",".remove-priview-video",function(){
				if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

				$(this).parent().parent().remove();
			});
		var totalSectionlink = $("#priview-table-video-link").find(".lms-section-card, fieldset").length;
		$(document).on("click","#add_section_link",function(e){
			e.preventDefault();
			var html =`<div class="lms-section-card border rounded p-3 mb-3 bg-light">
				<div class="lms-section-header d-flex align-items-center gap-2 mb-2 flex-wrap">
					<span class="badge bg-primary">`+(totalSectionlink+1)+`</span>
					<input type="text" class="form-control form-control-sm flex-grow-1" name="sectionlink[`+totalSectionlink+`]" value="" placeholder="<?= __('admin.section_title') ?>">
					<button type="button" class="btn btn-outline-primary btn-sm addNewText" data-value="`+totalSectionlink+`"><i class="fas fa-plus-circle me-1"></i>`+txtAddVideoLink+`</button>
					<button type="button" class="btn btn-outline-danger btn-sm remove-section"><i class="fas fa-trash"></i></button>
				</div>
				<div class="lms-section-body">
					<table class="table table-sm table-hover videolink-preview mb-0" id="videolink-preview`+totalSectionlink+`">
						<thead class="table-light"><tr><th>`+txtVideoUrl+`</th><th><?= __('admin.title') ?></th><th><?= __('admin.description') ?></th><th><?= __('admin.action') ?></th></tr></thead>
						<tbody></tbody>
					</table>
				</div>
			</div>`;
			$("#priview-table-video-link").append(html); 
			totalSectionlink++;
		});

			$(document).on("click",".addNewText",function(e){
				e.preventDefault();
				var id =$(this).data('value');
				var rsID = Math.floor((Math.random() * 100000000) + 1);
				
				var currentEl =$("input[name='VideoFileZip["+id+"][]']").length;
				
				var newRow =`<tr>
				<td>
				<input type="text" class="form-control" name="videolink[`+id+`][]" placeholder="Enter Video Link">
				<div class="mt-3">
				<input type="checkbox" name="iszipResource[`+id+`][]" value="0" class="isResource" id="`+rsID+`">
				<label for="resource`+rsID+`" class="ml-1 form-check-label mb-3">
				Lesson Resource
				</label>
				
</div>
				<div class="resource d-none" id="resource`+rsID+`">
				<p></p><input type="file" data-main="`+id+`" class="VideoFileResource" name="VideoFileZip[`+id+`][`+currentEl+`]" data-current="`+currentEl+`">
				<input type="text" name="VideoFileResourceText[`+id+`][]" value="" placeholder="Resource Name" class="form-control mt-3">
				
</div>
				</td>
				<td><input type="text" class="form-control" name="videotext[`+id+`][]"  placeholder="Add Video Title"></td>
				<td><input type="text" class="form-control" name="description[`+id+`][]" value="" placeholder="Add Video Description"></td>
				<td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview-video">Remove</button></td>
				</tr>`;

				$("#priview-table-video-link").find("#videolink-preview"+id+" tbody").append(newRow);
			});

			$(".updateVideoFile").change(async function(e){

				let that = $(this);
				let that_e = e;
				await setFileInfo(this, function() {
					var id =$(that).data('main');
					var name =$(that).data('name');
					var oldname =$(that).data('old-name')
					e.target.files[0].duration = uploadedVideosDurations;
					video_fileArr.push([id,that_e.target.files[0],oldname]);

					$(that).parent().find("p").html(that_e.target.files[0].name + " (<strong>"+ getFileSize(that_e.target.files[0].size) +"</strong>)")
					$(that).parent().parent().find('button').attr('data-name',that_e.target.files[0].name)
					$(that).parent().parent().find('button').attr('data-main',id)
				});
				
			});
			$(".updateVideoFileResource").change(function(e){
				var ext = $(this).val().split('.').pop().toLowerCase();
				if('zip' != ext) {
					$(this).val('');
					alert('<?= __('user.only_allow_zip_file') ?>');
					return false;
				}
				var id =$(this).data('main');
				var name =$(this).data('name');
				var oldname =$(this).data('old-name');
				video_fileZipArr.push([id,e.target.files[0],oldname]);

				$(this).parent().find("p").html(e.target.files[0].name + " (<strong>"+ getFileSize(e.target.files[0].size) +"</strong>)")
				$(this).parent().parent().find('button').attr('data-name',e.target.files[0].name)
				$(this).parent().parent().find('button').attr('data-main',id)
				
				console.log(video_fileZipArr);
			});

			$(document).on("change",".VideoFileResource",function(e){
				var ext = $(this).val().split('.').pop().toLowerCase();
				if('zip' != ext) {
					$(this).val('');
					alert('<?= __('user.only_allow_zip_file') ?>');
					return false;
				}
				var id =$(this).data('main');
				if($("input[name='sub_product_type']:checked").val() == "videolink") {
					var current =$(this).data('current');
					video_fileZipArr.push([id,e.target.files[0],current]);
				} else {

					video_fileZipArr.push([id,e.target.files[0]]);
				}
				$(this).parent().find("p").html(e.target.files[0].name + " (<strong>"+ getFileSize(e.target.files[0].size) +"</strong>)")
				$(this).parent().parent().find('button').attr('data-zip',e.target.files[0].name);
				console.log(video_fileZipArr);
			});

			$(document).on('change','.isResource',function(){
				var id = $(this).attr('id');
				if($(this).is(':checked')) {
					$('#resource'+id).removeClass('d-none')
					$(this).val(1);
				} else {
					$(this).val(0);
					$('#resource'+id).addClass('d-none')
					$(document).find('#resource'+id).find('p').html('')
					$(document).find('#resource'+id).find('.updateVideoFileResource').val('')

				}
			});
			$(document).on('change','.updateResource',function(){
				var id = $(this).attr('id');
				if($(this).is(':checked')) {
					$('#resource'+id).removeClass('d-none')
					$(this).val(1);
				} else {
					if(confirm('<?= __('user.are_you_sure') ?>')) {
						$(this).val(0);
						
						$.ajax({
							url:'<?= base_url("admincontrol/lmsResourceupdate") ?>',
							type:'POST',
							dataType:'json',
							data:{ product_id:$("#product_id").val(),id:$(this).attr('id')},
							success:function(json){
							},
						})

						$('#resource'+id).addClass('d-none')
						$(document).find('#resource'+id).find('p').html('')
						$(document).find('#resource'+id).find('.updateVideoFileResource').val('')
					} else {
						$(this).val(1);
						$(this).click();
						return false;
					}


				}
			});
</script>
				