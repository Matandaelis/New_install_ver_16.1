<?php 
$db =& get_instance();
$userdetails=$db->userdetails();
?>

<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/select2/select2.min.css") ?>">
<script type="text/javascript" src="<?= base_url('assets/plugins/select2/select2.full.min.js') ?>"></script>

<div class="container-fluid">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="form_form">
<div class="row">
<div class="col-12">
<div class="card shadow-sm">
<div class="card-header bg-primary text-white py-3">
	<div class="card-title-white pull-left m-0 fs-5 fw-bold">
		<i class="bi bi-box-seam me-2"></i><?= (int)$product->product_id == 0 ? __('admin.lbl_create_product') : __('admin.lbl_update_product') ?>
	</div>
</div>
<div class="card-body p-4">

<fieldset class="border p-4 rounded-3 mb-4 shadow-sm custom-design bg-light">
    <legend class="px-3 fs-6 fw-bold text-primary">
    	<i class="bi bi-tag-fill me-2"></i><?= __('admin.product_type'); ?>
    </legend>
    <div class="row g-3">
		<div class="col-md-4">
		    <div class="form-check">
		        <input class="form-check-input invisible" type="radio" name="product_type" id="virtual" value="virtual" <?= ($product->product_type == 'virtual' || $product->product_type == '') ? 'checked="checked"' : '' ?>>
		        <label class="form-check-label btn w-100 py-3 proType <?= ($product->product_type == 'virtual' || $product->product_type == '') ? 'btn-primary' : 'btn-outline-primary' ?>" for="virtual" data-value="virtual">
		        	<i class="bi bi-cloud me-2"></i>
		            <?= __('admin.virtual_product'); ?>
		        </label>
		    </div>
		</div>
		<div class="col-md-4">
		    <div class="form-check">
		        <input class="form-check-input invisible" type="radio" name="product_type" id="downloadable" value="downloadable" <?= ($product->product_type == 'downloadable') ? 'checked="checked"' : '' ?>>
		        <label class="form-check-label btn w-100 py-3 proType <?= ($product->product_type == 'downloadable') ? 'btn-primary' : 'btn-outline-primary' ?>" for="downloadable" data-value="downloadable">
		        	<i class="bi bi-download me-2"></i>
		            <?= __('admin.downloadable_product'); ?>
		        </label>
		    </div>
		</div>
		<div class="col-md-4">
		    <div class="form-check">
		        <input class="form-check-input invisible" type="radio" name="product_type" id="video" value="video" <?= ($product->product_type == 'video' || $product->product_type == 'videolink') ? 'checked="checked"' : '' ?>>
		        <label class="form-check-label btn w-100 py-3 proType <?= ($product->product_type == 'video' || $product->product_type == 'videolink') ? 'btn-primary' : 'btn-outline-primary' ?>" for="video" data-value="video">
		        	<i class="bi bi-play-circle me-2"></i>
		            <?= __('admin.lms_product'); ?>
		        </label>
		    </div>
		</div>
    </div>
</fieldset>

<!--Active buttons style-->
<script>
	$(document).ready(function() {
	    $(".proType").click(function() {
	        // Remove bg-primary and add bg-secondary to all proType elements
	        $(".proType").removeClass("btn-primary").addClass("btn-outline-primary");

	        // Add bg-primary and remove bg-secondary from the clicked element
	        $(this).removeClass("btn-outline-primary").addClass("btn-primary");
	    });
	});
</script>
<!--Active buttons style-->

	<input type="hidden" id="product_id" name="product_id" value="<?php echo $product->product_id ?>">

	<div class="row g-4 align-items-start">
		<div class="col-lg-8">
		<div class="form-group mb-3">
			<label class="col-form-label fw-semibold">
				<i class="bi bi-pencil-square me-2"></i><?= __('admin.product_name') ?>
			</label>
			<input placeholder="<?= __('admin.enter_your_product_name') ?>" name="product_name" value="<?php echo $product->product_name; ?>" class="form-control form-control-lg" type="text">
		</div>
			<div class="form-group mb-3">
				<label class="col-form-label fw-semibold">
					<i class="bi bi-tag me-2"></i><?= __('admin.product_msrp') ?>
				</label>
				<div>
					<div class="input-group">
						<span class="input-group-text"><?= $CurrencySymbol ?></span>
						<input placeholder="<?= __('admin.product_msrp_placeholder') ?>" name="product_msrp" class="form-control" value="<?php echo $product->product_msrp; ?>" type="number">
					</div>
				</div>
			</div>
			<div class="form-group mb-3">
				<label class="col-form-label fw-semibold">
					<i class="bi bi-currency-dollar me-2"></i><?= __('admin.product_price') ?>
				</label>
				<div>
					<div class="input-group">
						<span class="input-group-text"><?= $CurrencySymbol ?></span>
						<input placeholder="<?= __('admin.enter_your_product_price') ?>" name="product_price" class="form-control" value="<?php echo $product->product_price; ?>" type="number">
					</div>
				</div>
			</div>
			<div class="form-group mb-3">
				<label class="col-form-label fw-semibold">
					<i class="bi bi-upc me-2"></i><?= __('admin.product_sku') ?>
				</label>
				<div>
					<input placeholder="<?= __('admin.enter_your_product_sku') ?>" name="product_sku" id="product_sku" class="form-control" value="<?php echo $product->product_sku; ?>" type="text">
				</div>
			</div>

			<div class="form-group mb-3">
			    <label class="col-form-label fw-semibold">
			    	<i class="bi bi-boxes me-2"></i><?= __('admin.product_quantity') ?>
			    </label>
			    <div>
			        <input placeholder="<?= __('admin.enter_product_quantity_or_leave_blank_for_unlimited') ?>" name="product_quantity" id="product_quantity" class="form-control" value="<?php echo $product->product_quantity; ?>" type="number">
			        <div class="form-text">Leave blank for unlimited quantity</div>
			    </div>
			</div>

			<!-- Advanced Product Settings (Collapsible) -->
		<!-- Short Description (always visible) -->
		<div class="form-group mb-3">
			<div class="d-flex justify-content-between align-items-center mb-2">
				<label class="col-form-label fw-semibold mb-0">
					<i class="bi bi-text-left me-2"></i><?= __('admin.short_description') ?>
				</label>
				<?= ai_helper_button('product_short_description', 'product_short_description', ['size' => 'sm', 'text' => 'AI Generate']) ?>
			</div>
			<textarea rows="3" placeholder="<?= __('admin.enter_your_product_short_description') ?>" class="form-control" name="product_short_description" id="product_short_description"><?php echo $product->product_short_description; ?></textarea>
		</div>

		<!-- Categories (always visible) -->
		<div class="form-group mb-3">
		    <label class="col-form-label fw-semibold">
		    	<i class="bi bi-folder me-2"></i><?= __('admin.categories') ?>
		    </label>
		    <div class="category-container">
		        <input name="category_auto" placeholder="<?= __('admin.categories') ?>" id="category_auto" class="form-control mb-2" autocomplete="off">
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

		<!-- Shipping — hidden for downloadable + LMS (physical only) -->
		<?php $hideShipping = in_array($product->product_type, ['video','videolink','downloadable']); ?>
		<div class="card border-0 shadow-sm mb-3 <?= $hideShipping ? 'd-none' : '' ?>" id="shipping_collapse_btn_div">
			<div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-2"
			     style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#productAdvancedSettings" aria-expanded="false">
				<div class="d-flex align-items-center gap-2">
					<i class="bi bi-truck text-primary"></i>
					<span class="fw-semibold small"><?= __('admin.shipping_information') ?></span>
				</div>
				<i class="bi bi-chevron-down small text-muted" id="shippingChevron"></i>
			</div>
			<div class="collapse" id="productAdvancedSettings">
			<div class="card-body pt-3 pb-2">
				<div class="row g-3">
					<div class="col-6 col-md-3">
						<label class="form-label small fw-semibold text-muted"><?= __('admin.product_weight') ?> <span class="fw-normal">(lbs)</span></label>
						<input placeholder="0.00" name="product_weight" class="form-control form-control-sm" value="<?php echo $product->product_weight; ?>" type="number" step="0.01" min="0">
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label small fw-semibold text-muted"><?= __('admin.product_length') ?> <span class="fw-normal">(in)</span></label>
						<input placeholder="0.00" name="product_length" class="form-control form-control-sm" value="<?php echo $product->product_length; ?>" type="number" step="0.01" min="0">
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label small fw-semibold text-muted"><?= __('admin.product_width') ?> <span class="fw-normal">(in)</span></label>
						<input placeholder="0.00" name="product_width" class="form-control form-control-sm" value="<?php echo $product->product_width; ?>" type="number" step="0.01" min="0">
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label small fw-semibold text-muted"><?= __('admin.product_height') ?> <span class="fw-normal">(in)</span></label>
						<input placeholder="0.00" name="product_height" class="form-control form-control-sm" value="<?php echo $product->product_height; ?>" type="number" step="0.01" min="0">
					</div>
				</div>
			</div>
			</div>
		</div>
		<div class="form-group mb-3" style="display: none;">
			<label class="col-form-label fw-semibold"><?= __('admin.product_video_') ?></label>
			<input placeholder="<?= __('admin.enter_your_product_video_link{youtube/vimeo}') ?>" name="product_video" id="product_video" class="form-control" value="<?php echo $product->product_video; ?>" type="text">
		</div>

	</div><!-- /col-lg-8 -->

	<!-- Product image sidebar -->
	<div class="col-lg-4">
	<div class="prod-edit-sidebar">

		<!-- Save Actions Card -->
		<div class="card shadow-sm border-0 mb-3">
			<div class="card-body p-3">
				<div class="d-grid gap-2">
					<button type="submit" class="btn btn-success btn-submit" name="save">
						<i class="bi bi-save me-2"></i><?= __('admin.save_continue_editing') ?>
						<span class="loading-submit d-none">
							<div class="spinner-border spinner-border-sm text-light" role="status"></div>
						</span>
					</button>
					<button type="submit" class="btn btn-secondary btn-submit" name="save_close">
						<i class="bi bi-save2 me-2"></i><?= __('admin.save_and_close') ?>
						<span class="loading-submit d-none">
							<div class="spinner-border spinner-border-sm text-light" role="status"></div>
						</span>
					</button>
					<?php if($product->product_slug): ?>
					<?php $productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product->product_slug ); ?>
					<a class="btn btn-outline-primary" href="<?= $productLink ?>" target="_blank">
						<i class="bi bi-eye me-2"></i><?= __('admin.preview') ?>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Featured Image Card -->
		<div class="card shadow-sm border-0 mb-3">
			<div class="card-header bg-white d-flex align-items-center gap-2 py-2 border-bottom">
				<i class="bi bi-image text-primary fs-5"></i>
				<span class="fw-semibold"><?= __('admin.product_featured_image') ?></span>
			</div>
			<div class="card-body p-3">
				<?php
				if (strpos($product->product_featured_image, 'http://') === 0 || strpos($product->product_featured_image, 'https://') === 0) {
				    $product_featured_image = $product->product_featured_image;
				} else {
				    $product_featured_image = !empty($product->product_featured_image)
				        ? base_url('assets/images/product/upload/thumb/' . $product->product_featured_image)
				        : '';
				}
				?>
				<!-- Clickable image zone -->
				<label for="product_featured_image" class="prod-img-zone mb-3">
					<img src="<?= $product_featured_image ?: base_url('assets/images/no_product_image.png') ?>"
					     id="featureImage"
					     style="display:block; width:100%; max-height:220px; object-fit:cover; <?= $product_featured_image ? '' : 'opacity:.35;' ?>">
					<?php if (!$product_featured_image): ?>
					<div class="prod-img-placeholder" id="featureImagePlaceholder">
						<i class="bi bi-cloud-arrow-up fs-2 mb-1"></i>
						<span class="small fw-medium"><?= __('admin.choose_file') ?></span>
						<span class="x-small text-muted mt-1" style="font-size:.75rem;"><?= __('admin.product_featured_image') ?></span>
					</div>
					<?php else: ?>
					<div class="prod-img-placeholder" id="featureImagePlaceholder" style="display:none;"></div>
					<?php endif; ?>
				</label>

				<input type="file" id="product_featured_image" name="product_featured_image" class="d-none"
				       onchange="readURL(this,'#featureImage'); document.getElementById('featureImagePlaceholder').style.display='none';">

				<div class="d-grid gap-2">
					<label for="product_featured_image" class="btn btn-outline-primary btn-sm">
						<i class="bi bi-upload me-1"></i><?= __('admin.choose_file') ?>
					</label>
					<button type="button" class="btn btn-outline-secondary btn-sm"
					        onclick="prepareS3Modal('input[name=\'product_featured_image_s3\']', '#featureImage')">
						<i class="bi bi-cloud-arrow-up me-1"></i><?= __('admin.browse_amazon_s3_image') ?>
					</button>
				</div>

				<!-- S3 hidden fields -->
				<input name="s3_storage[s3_bucket_name]" value="<?= $s3_setting['s3_bucket_name']; ?>" type="hidden" id="s3_bucket_name">
				<input name="s3_storage[s3_region]" value="<?= $s3_setting['s3_region']; ?>" type="hidden" id="s3_region">
				<input type="hidden" name="product_featured_image_s3" value="<?= htmlspecialchars($product->product_featured_image, ENT_QUOTES, 'UTF-8'); ?>">
			</div>
		</div>

		<!-- Product Settings Card -->
		<div class="card shadow-sm border-0 mb-3">
			<div class="card-header bg-white d-flex align-items-center gap-2 py-2 border-bottom">
				<i class="bi bi-toggles2 text-primary fs-5"></i>
				<span class="fw-semibold"><?= __('admin.settings') ?></span>
			</div>
			<div class="card-body p-0">
				<!-- Show on Store — always shown -->
				<label class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom mb-0" style="cursor:pointer;">
					<div class="d-flex align-items-center gap-2">
						<span class="badge rounded-circle p-1 bg-success-subtle"><i class="bi bi-shop text-success fs-6"></i></span>
						<span class="small fw-semibold"><?= __('admin.show_on_store') ?></span>
					</div>
					<div class="form-check form-switch mb-0 ms-2">
						<input class="form-check-input" type="checkbox" role="switch" id="on_store" name="on_store"
						       <?= (int)$product->on_store == 1 ? 'checked' : '' ?>
						       data-setting_key="on_store" data-product_id="<?= $product->product_id ?>">
					</div>
				</label>
				<!-- Allow Upload File — hidden for LMS -->
				<label class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom mb-0 <?= ($product->product_type=='video' || $product->product_type=='videolink') ? 'd-none':'' ?>" id="allow_upload_file_div" style="cursor:pointer;">
					<div class="d-flex align-items-center gap-2">
						<span class="badge rounded-circle p-1 bg-primary-subtle"><i class="bi bi-upload text-primary fs-6"></i></span>
						<span class="small fw-semibold"><?= __('admin.allow_upload_file') ?></span>
					</div>
					<div class="form-check form-switch mb-0 ms-2">
						<input class="form-check-input" type="checkbox" role="switch" id="allow_upload_file" name="allow_upload_file"
						       <?= (int)$product->allow_upload_file == 1 ? 'checked' : '' ?>
						       data-setting_key="allow_upload_file" data-product_id="<?= $product->product_id ?>">
					</div>
				</label>
				<!-- Enable Shipping — hidden for LMS -->
				<label class="d-flex align-items-center justify-content-between px-3 py-2 mb-0 <?= ($product->product_type=='video' || $product->product_type=='videolink') ? 'd-none':'' ?>" id="allow_shipping_div" style="cursor:pointer;">
					<div class="d-flex align-items-center gap-2">
						<span class="badge rounded-circle p-1 bg-warning-subtle"><i class="bi bi-truck text-warning fs-6"></i></span>
						<span class="small fw-semibold"><?= __('admin.enable_shipping') ?></span>
					</div>
					<div class="form-check form-switch mb-0 ms-2">
						<input class="form-check-input" type="checkbox" role="switch" id="allow_shipping" name="allow_shipping"
						       <?= (int)$product->allow_shipping == 1 ? 'checked' : '' ?>
						       data-setting_key="allow_shipping" data-product_id="<?= $product->product_id ?>">
					</div>
				</label>
			</div>
		</div>

		<!-- Quick Product Info (edit mode only) -->
		<?php if ((int)$product->product_id > 0): ?>
		<div class="card border-0 bg-light mb-3">
			<div class="card-body p-3">
				<ul class="list-unstyled mb-0 small">
					<li class="d-flex justify-content-between py-1 border-bottom">
						<span class="text-muted"><?= __('admin.product_sku') ?></span>
						<strong><?= htmlspecialchars($product->product_sku ?: '—') ?></strong>
					</li>
					<li class="d-flex justify-content-between py-1 border-bottom">
						<span class="text-muted"><?= __('admin.product_type') ?></span>
						<strong class="text-capitalize"><?= $product->product_type ?: 'virtual' ?></strong>
					</li>
					<li class="d-flex justify-content-between py-1">
						<span class="text-muted">ID</span>
						<strong>#<?= $product->product_id ?></strong>
					</li>
				</ul>
			</div>
		</div>
		<?php endif; ?>

	</div><!-- /prod-edit-sidebar -->
	</div><!-- /col-lg-4 -->
	<!-- /Product image sidebar -->

</div>


	<?php
	$variations = [];
	if(isset($product->product_variations) && !empty($product->product_variations)) {
		$variations = json_decode($product->product_variations);
	}
	$hasVariations = !empty(array_filter((array)$variations));
	?>
	<!-- Product Variations — hidden for LMS -->
	<div class="card border-0 shadow-sm mb-3 <?= ($product->product_type=='video' || $product->product_type=='videolink') ? 'd-none' : '' ?>" id="add_variants_div">
		<div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-2">
			<div class="d-flex align-items-center gap-2">
				<i class="bi bi-diagram-3 text-primary"></i>
				<span class="fw-semibold small"><?= __('admin.add_variants') ?></span>
			</div>
			<button type="button" class="btn btn-primary btn-sm btn-add-variants">
				<i class="bi bi-plus-circle me-1"></i><?= __('admin.add_variants') ?>
			</button>
		</div>
		<div class="card-body p-0">
		<div class="variants-empty-msg py-3 text-center text-muted small" id="variants-empty-msg" <?= $hasVariations ? 'style="display:none;"' : '' ?>>
			<i class="bi bi-diagram-3 fs-4 d-block mb-1 opacity-50"></i>
			<?= __('admin.no_variants_added') ?>
		</div>
		<div class="table-responsive">
		<table id="product-variations" class="table table-sm table-hover mb-0">
		    <tbody>
		    <?php foreach((array)$variations as $key => $value): if(!empty($value)): ?>
		    <tr data-variation-type="<?= strtolower($key); ?>">
		        <td class="fw-semibold text-secondary ps-3" style="width:140px;"><?= ucwords(strtolower($key)); ?></td>
		        <td class="text-muted small">
		            <?php
		            for ($i = 0; $i < sizeof($value); $i++) {
		                $this_price = isset($value[$i]->price) ? $value[$i]->price : 0;
		                if ($key == 'colors') {
		                    echo ($i == 0) ? ucwords(strtolower($value[$i]->name)) : ", " . ucwords(strtolower($value[$i]->name));
		                    echo "<input type='hidden' name='variations[".strtolower($key)."][name][]' value='".$value[$i]->name."'>";
		                    echo "<input type='hidden' name='variations[".strtolower($key)."][code][]' value='".$value[$i]->code."'>";
		                    echo "<input type='hidden' name='variations[".strtolower($key)."][price][]' value='".$this_price."'>";
		                } else {
		                    $this_name = isset($value[$i]->name) ? $value[$i]->name : $value[$i];
		                    echo ($i == 0) ? ucwords(strtolower($this_name)) : ", " . ucwords(strtolower($this_name));
		                    echo "<input type='hidden' name='variations[".strtolower($key)."][name][]' value='".$this_name."'>";
		                    echo "<input type='hidden' name='variations[".strtolower($key)."][price][]' value='".$this_price."'>";
		                }
		            }
		            ?>
		        </td>
		        <td class="text-end pe-3" style="width:80px;">
		        	<div class="btn-group btn-group-sm" role="group">
		                <button type="button" data-variation-type="<?= strtolower($key); ?>" class="btn btn-outline-warning btn-edit-variants"><i class="bi bi-pencil"></i></button>
		                <button type="button" data-variation-type="<?= strtolower($key); ?>" class="btn btn-outline-danger btn-delete-variants"><i class="bi bi-trash"></i></button>
		            </div>
		        </td>
		    </tr>
		    <?php endif; endforeach; ?>
		    </tbody>
		</table>
		</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-3 <?= ($product->product_type=='video' || $product->product_type=='videolink') ? 'd-none' : '' ?>" id="country_location_div">
		<div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-2">
			<div class="d-flex align-items-center gap-2">
				<i class="bi bi-geo-alt text-primary"></i>
				<span class="fw-semibold small"><?= __('admin.country_location') ?></span>
			</div>
			<div class="form-check form-switch mb-0">
				<input class="form-check-input allow_country_settings" type="checkbox" role="switch" id="allow_country" name="allow_country"
				       <?= (int)$product->state_id >= 1 ? 'checked' : '' ?>
				       data-setting_key="allow_country" data-product_id="<?= $product->product_id ?>">
			</div>
		</div>
		<div class="country-chooser">
		<div class="card-body pt-3 pb-2">
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label small fw-semibold text-muted"><?= __('admin.select_country') ?></label>
					<select class="form-select form-select-sm" name="country_id" id="country_id">
						<option value="0"><?= __('admin.select_country'); ?></option>
						<?php foreach ($country_list as $key => $value) { ?>
							<option <?= $product_state->country_id == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label small fw-semibold text-muted"><?= __('admin.select_state') ?></label>
					<select class="form-select form-select-sm" name="state_id" id="state_id">
						<option value=""><?= __('admin.select_state'); ?></option>
						<?php foreach ($states as $key => $value) { ?>
							<option <?= $product_state->id == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		</div>
		<script type="text/javascript">
			$(document).on("change", ".allow_country_settings", function(){
				var checked = $(this).prop('checked');
				if (checked == true) {
					$(".country-chooser").show();
				}else{
					$(".country-chooser").hide();
				}
			})

			$( document ).ready(function() {
			    $(".allow_country_settings").trigger('change');
			});
			

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
							var html = '<option value=""><?= __('admin.select_state'); ?></option>';
							$.each(json, function(k,v){
								html += '<option value="'+v.id+'">'+v.name+'</option>';
							});
							$('#state_id').html(html);
						}
					}
				});
			});
		</script>
	</div>

	<div class="form-group mb-3">
		<div class="d-flex justify-content-between align-items-center mb-2">
			<label class="col-form-label fw-semibold mb-0">
				<i class="bi bi-file-text me-2"></i><?= __('admin.product_description') ?>
			</label>
			<?= ai_helper_button('product_description', 'product_description', ['size' => 'sm', 'text' => 'AI Generate']) ?>
		</div>
		<div>
			<textarea placeholder="<?= __('admin.enter_your_product_description') ?>" class="product_description form-control summernote" name="product_description" type="text" id="product_description"><?php echo $product->product_description; ?></textarea>
		</div>
	</div>

<div class="mb-3">
    <label for="product_tags" class="col-form-label fw-semibold">
    	<i class="bi bi-tags me-2"></i><?= __('admin.product_tag') ?>
    </label>
    <select id="product_tags" name="product_tags[]" class="form-select select2" multiple="multiple">
        <?php
        if (!empty($product->product_tags)) {
            $ptags = json_decode($product->product_tags, true);
            $ptags = is_array($ptags) ? $ptags : [];
        } else {
            $ptags = [];
        }

        foreach ($tags as $tag) {
            $selected = (in_array($tag, $ptags)) ? "selected" : "";
            echo '<option value="' . $tag . '" ' . $selected . '>' . $tag . '</option>';
        }
        ?>
    </select>
</div>


<div class="row">
<div class="col-sm-12">
<fieldset class="custom-design mb-3 border p-3 rounded bg-light">
<legend class="px-2 fs-6 fw-bold text-secondary">
	<i class="bi bi-arrow-repeat me-2"></i><?= __('admin.product_recursion'); ?>
</legend>
<div class="form-group mb-3">
	<div>
		<?php
		$product_recursion_type = $product->product_recursion_type;
		$product_recursion = $product->product_recursion;
		?>
		<select name="product_recursion_type" class="form-select">
			<option <?= '' == $product_recursion_type ? 'selected' : '' ?> value=""><?=  __('admin.none') ?></option>
			<option <?= 'default' == $product_recursion_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
			<option <?= 'custom' == $product_recursion_type ? 'selected' : '' ?> value="custom"><?= __('admin.custom') ?></option>								
		</select>							
	</div>
	<div class="toggle-container mt-3">
		<div class="d-none default-value">
			<div class="alert alert-info">
				<i class="bi bi-info-circle me-2"></i>
				<?php
				if($setting['product_recursion'] == 'custom_time'){
					if ($setting['recursion_endtime'] == NULL || $setting['recursion_endtime'] == '') {
						echo __('admin.default_recursion'). " : " . timetosting($setting['recursion_custom_time']). " | ".__('admin.endtime').": ".__('admin.life_time');
					}else{
						echo __('admin.default_recursion'). " : " . timetosting($setting['recursion_custom_time']). " | ".__('admin.endtime')." : " . dateFormat($setting['recursion_endtime']);
					}
				}else{
					if ($setting['recursion_endtime'] == NULL || $setting['recursion_endtime'] == '') {
						echo __('admin.default_recursion'). " : " . __('admin.'.$setting['product_recursion']) . " | ".__('admin.endtime')." : ".__('admin.life_time');
					}else{
						echo __('admin.default_recursion'). " : " . __('admin.'.$setting['product_recursion']) . " | ".__('admin.endtime')." : " . dateFormat($setting['recursion_endtime']);
					}
				}
				?>
			</div>
		</div>

		<div class="d-none custom-value">
			<div class="custom_recursion">
				<div class="form-group mb-3">
					<select name="product_recursion" class="form-select" id="recursion_type">
						<option value=""><?= __('admin.select_recursion'); ?></option>
						<option <?php if($product_recursion == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
						<option <?php if($product_recursion == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
						<option <?php if($product_recursion == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
						<option <?php if($product_recursion == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
						<option <?php if($product_recursion == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
					</select>
				</div>

				<div class="form-group custom_time">
					<?php
					$minutes = $product->recursion_custom_time;
					$day = floor ($minutes / 1440);
					$hour = floor (($minutes - $day * 1440) / 60);
					$minute = $minutes - ($day * 1440) - ($hour * 60);
					?>

					<input type="hidden" name="recursion_custom_time" value="<?php echo $minutes; ?>">
					<div class="row">
						<div class="col-sm-4">
							<label class="control-label fw-semibold"><?= __('admin.days'); ?> : </label>
							<input placeholder="Days" type="number" class="form-control" value="<?= $day ? $day : '' ?>" id="recur_day" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
						</div>						
						<div class="col-sm-4">
							<label class="control-label fw-semibold"><?= __('admin.hours'); ?> : </label>
							<select class="form-select" id="recur_hour">
								<?php for ($x = 0; $x <= 23; $x++) {
									$selected = ($x == $hour ) ? 'selected="selected"' : '';
									echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
								} ?>
							</select>
						</div>						
						<div class="col-sm-4">
							<label class="control-label fw-semibold"><?= __('admin.minutes'); ?> : </label>
							<select class="form-select" id="recur_minute">
								<?php for ($x = 0; $x <= 59; $x++) {
									$selected = ($x == $minute ) ? 'selected="selected"' : '';
									echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
								} ?>
							</select>
						</div>						
					</div>									
				</div>
				<br>
				<div class="endtime-chooser row">
					<div class="col-sm-12">
						<div class="form-group">
							<div class="form-check mb-3">
								<input <?= $product->recursion_endtime ? 'checked' : '' ?> class="form-check-input" id='setCustomTime' name='recursion_endtime_status' type="checkbox">
								<label class="control-label form-check-label fw-semibold" for="setCustomTime"><?= __('admin.choose_custom_endtime') ?></label>
							</div>
							<div style="<?= !$product->recursion_endtime ? 'display:none' : '' ?>" class='custom_time_container'>
								<input type="text" class="form-control" value="<?= $product->recursion_endtime ? date("d-m-Y H:i",strtotime($product->recursion_endtime)) : '' ?>" name="recursion_endtime" id="endtime" placeholder="<?= __('admin.choose_endtime'); ?>" >
							</div>
						</div>
					</div>
				</div>
			</div>								
		</div>
	</div>

	<script type="text/javascript">
		$("select[name=product_recursion_type]").on("change",function(){
			$con = $(this).parents(".form-group");
			$con.find(".toggle-container .custom-value, .toggle-container .default-value").addClass('d-none');

			if($(this).val() == 'default'){
				$con.find(".toggle-container .default-value").removeClass("d-none");
			}else if($(this).val() == 'custom'){
				$con.find(".toggle-container .custom-value").removeClass("d-none");
			}
		})
		$("select[name=product_recursion_type]").trigger("change");


		$("select[name=product_recursion]").on("change",function(){
			$con = $(this).parents(".custom_recursion");
			$con.find(".custom_time").addClass('d-none');

			if($(this).val() == 'custom_time'){
				$con.find(".custom_time").removeClass("d-none");
			}
		})
		$("select[name=product_recursion]").trigger("change");
	</script>
</div>
</fieldset>
</div>
</div>
			</div> <!-- End #productAdvancedSettings collapse -->
	<?php $proType = isset($product->product_type) ?  ($product->product_type=='virtual' ? 'd-none':'') :'d-none'; ?>
	<?php $proType1 = $product->product_type=='downloadable' ? __('admin.downloadable_product_section'): __('admin.lms_product'); ?>
<fieldset class="custom-design mb-3 border p-3 rounded bg-light <?=$proType;?>">
	<legend id="product_type_title" class="px-2 fs-6 fw-bold text-secondary">
		<i class="bi bi-download me-2"></i><?=$proType1;?>
	</legend>
	<div class="form-group">
		<div>
			<label class="radio-inline invisible">
				<input type="radio" name="product_type" value="virtual" <?= ($product->product_type == 'virtual' || $product->product_type == '') ? 'checked="checked"' : '' ?> > <?= __('admin.virtual_product'); ?>
			</label>
			&nbsp;
			<label class="radio-inline invisible">
				<input type="radio" name="product_type" value="downloadable" <?= ($product->product_type == 'downloadable') ? 'checked="checked"' : '' ?> > <?= __('admin.downloadable_product'); ?>
			</label>
			&nbsp;
			<label class="radio-inline invisible">
				<input type="radio" name="product_type" value="video" <?= ($product->product_type == 'video' || $product->product_type == 'videolink') ? 'checked="checked"' : '' ?> > <?= __('admin.lms_product'); ?>
			</label>
			<div class="form-group downloadable_file_div well" style="display: none;">
				<div class="row mb-3">
					<div class="col-md-6">
						<div class="file-preview-button btn btn-primary">
							<i class="bi bi-upload me-2"></i><?= __('admin.downloadable_file'); ?>
							<input type="file" class="downloadable_file_input" name="downloadable_file" multiple="multiple">
						</div>
					</div>
					<div class="col-md-6">
						<!--download s3 code-->
						<button type="button" class="btn btn-success" onclick="prepareS3ModalDownloadbale('input[name=\'product_multiple_image_s3[]\']', '.fileUpload-gallery')">
							<i class="bi bi-cloud-arrow-up me-2"></i><?= __('admin.browse_amazon_s3_image') ?>
						</button>
					</div>
				</div>
				
				<div id="priview-table" class="table-responsive">
					<table class="table table-hover">
						<thead class="table-light">
							<?php if($product->product_type == 'downloadable') { 
								foreach ($downloads as $key => $value) {
									if($value['type'] == 'zip'){
									?>
									<tr>
										<td width="70px"> 
											<div class="upload-priview up-<?= $value['type'] ?>" ></div>
										</td>
										<td>
											<span class="fw-medium"><?= $value['mask'] ?></span>
											<input type="hidden" name="keep_files[]" value="<?= $key ?>">
										</td>
										<td width="70px">
											<button type="button" class="btn btn-danger btn-sm remove-priview-server" data-id="'+ i +'">
												<i class="bi bi-trash me-1"></i><?= __('admin.remove'); ?>
											</button>
										</td>
									</tr>
								<?php }elseif($value['srctype'] == 'AwsS3'){?>
									<tr>
										<td width="70px"> 
											<div class="upload-priview up-<?= $value['type'] ?>" ></div>
										</td>
										<td>
											<a target="_blank" class="text-decoration-none fw-medium" href="<?= $value['url'] ?>"><?= $value['mask'] ?></a>
											<input type="hidden" name="keep_files[]" value="<?= $key ?>">
										</td>
										<td width="70px">
											<button type="button" class="btn btn-danger btn-sm remove-priview-server" data-id="'+ i +'">
												<i class="bi bi-trash me-1"></i><?= __('admin.remove'); ?>
											</button>
										</td>
									</tr>
								<?php }?>
								<?php } } ?>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

<!-- video link section start from here -->

<div class="form-group video_file_div well" style="display: none;">

<div class="row g-2 mb-4">
	<div class="col-md-6">
		<label class="btn btn-outline-secondary text-center proSubType w-100 py-3 <?= $product->product_type == 'video' ? 'btn-primary' : '' ?>" data-value="" style="font-size: 15px;">
		  <input type="radio" class="invisible" name="sub_product_type" value="video" <?= ($product->product_type == 'video') ? 'checked="checked"' : '' ?> > 
		  <i class="bi bi-camera-video me-2"></i><span><?= __('admin.videos_product'); ?></span>
		</label>
	</div>
	<div class="col-md-6">
		<label class="btn btn-outline-secondary text-center proSubType w-100 py-3 <?=$product->product_type == 'videolink' ? 'btn-primary' : '' ?>" data-value="videolink" value="" style="font-size: 15px;">
		  <input type="radio" class="invisible" name="sub_product_type" value="videolink" <?= ($product->product_type == 'videolink') ? 'checked="checked"' : '' ?> > 
		  <i class="bi bi-link-45deg me-2"></i><?= __('admin.video_product_link'); ?>
		</label>
	</div>
</div>

<script>
$(".proSubType").click(function() {
  $(".proSubType").removeClass("btn-primary").addClass("btn-outline-secondary");
  $(this).removeClass("btn-outline-secondary").addClass("btn-primary");
});
</script>


<div class="video_file_uploader_div mt-4" style="display: none;">
	<button class="btn btn-success mb-3" id="add_section"> 
		<i class="bi bi-plus-circle me-2"></i><?= __('admin.add_section');?>
	</button>

<div id="priview-table-video" class="table-responsive ">
<?php if($product->product_type == 'video') { 
foreach ($downloads as $key => $value) {
?>
<div class="lms-section-card">
    <div class="lms-section-header">
        <span class="lms-section-badge"><?=$key+1?></span>
        <input type="text" class="lms-section-title-input form-control" name="section[<?=$key?>]" value="<?=$value['title'] ?>" placeholder="<?=__('admin.lms_section_title_placeholder')?>" title="<?=__('admin.lms_section_title_placeholder')?>">
        <label class="btn btn-outline-primary btn-sm text-nowrap ms-auto">
            <i class="bi bi-upload me-1"></i><?= __('admin.upload_new_video') ?>
            <input class="videoFileUploadIP" type="file" name="video_files[<?=$key?>][]" multiple="multiple" data-value="<?=$key?>" style="display: none;">
        </label>
        <button class="btn btn-outline-danger btn-sm remove-section" type="button">
            <i class="bi bi-trash"></i>
        </button>
    </div>
    <div class="lms-section-body">
    <table class="table table-sm table-hover videofile-preview mb-0" id="videofile-preview<?=$key?>">
        <thead class="table-light">
            <tr>
                <th><?= __('admin.video_file'); ?></th>
                <th width="20%"><?= __('admin.title'); ?></th>
                <th width="25%"><?= __('admin.description'); ?></th>
                <th width="60px"><?= __('admin.action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($value['data'] as $innngerKey => $innerValue) { ?>
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-camera-video text-primary"></i>
                        <span class="fw-medium small"><?= $innerValue['mask'] ?></span>
                        <span class="badge bg-light text-muted border ms-1"><?= $innerValue['size']; ?></span>
                    </div>
                    <input type="hidden" name="keep_video_files[<?= $key ?>][]" value="<?= $innerValue['name'] ?>">
                    <input type="file" class="form-control form-control-sm updateVideoFile" name="updateVideoFile[]" data-main="<?=$key?>" data-name="<?=$innngerKey?>" data-old-name="<?= $innerValue['name'] ?>">
                    <div class="form-check mt-2">
                        <input type="checkbox" name="iszipResource[<?=$key?>][<?=$innngerKey?>]" value="<?= $innerValue['name'] ?>" class="form-check-input updateResource" id="<?= $innerValue['name'] ?>" <?= isset($innerValue['zip']['mask']) ? 'checked="checked"':''; ?>>
                        <label for="<?= $innerValue['name'] ?>" class="form-check-label small fw-semibold">
                            <i class="bi bi-paperclip me-1"></i><?= __('admin.lesson_resource'); ?>
                        </label>
                    </div>
                    <div class="resource lms-resource-box <?= isset($innerValue['zip']['mask']) ? '':'d-none'; ?>" id="resource<?= $innerValue['name'] ?>">
                        <?php if (isset($innerValue['zip']['mask'])): ?>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-file-zip text-warning"></i>
                                <span class="small fw-medium"><?= $innerValue['zip']['mask'] ?></span>
                                <span class="badge bg-light text-muted border"><?= $innerValue['zip']['size']??'0'; ?></span>
                            </div>
                        <?php endif ?>
                        <input type="file" class="form-control form-control-sm updateVideoFileResource mb-2" name="VideoFileZip[<?= $key ?>][]" accept=".zip" data-main="<?= $key ?>" data-name="<?= $innngerKey ?>" data-old-name="<?= $innerValue['name'] ?>">
                        <input type="text" name="VideoFileResourceText[<?=$key?>][]" value="<?= $innerValue['zip']['title']?>" placeholder="Resource Name" class="form-control form-control-sm">
                    </div>
                </td>
                <td><input type="text" class="form-control form-control-sm" name="videotext[<?= $key ?>][]" value="<?= $innerValue['videotext'] ?>" placeholder="<?= __('admin.title') ?>"></td>
                <td><input type="text" class="form-control form-control-sm" name="description[<?= $key ?>][]" value="<?=$innerValue['description']?>" placeholder="<?= __('admin.description') ?>"></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-priview-server">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
</div>
<?php } } ?>
</div>
</div>
<div class="video_link_div mt-4" style="display: none;">

<button class="btn btn-success mb-3" id="add_section_link"> 
	<i class="bi bi-plus-circle me-2"></i><?= __('admin.add_section');?>
</button>

<div id="priview-table-video-link" class="table-responsive ">
<?php if($product->product_type == 'videolink') { 
foreach ($downloads as $key => $value) {
?>
<div class="lms-section-card">
    <div class="lms-section-header">
        <span class="lms-section-badge"><?=$key+1?></span>
        <input type="text" class="lms-section-title-input form-control" name="sectionlink[<?=$key?>]" value="<?=$value['title'] ?>" placeholder="<?=__('admin.lms_section_title_placeholder')?>" title="<?=__('admin.lms_section_title_placeholder')?>">
        <button type="button" class="btn btn-outline-primary btn-sm addNewText text-nowrap ms-auto" data-value="<?=$key?>">
            <i class="bi bi-plus-circle me-1"></i><?= __('admin.video_product_link'); ?>
        </button>
        <button class="btn btn-outline-danger btn-sm remove-section" type="button">
            <i class="bi bi-trash"></i>
        </button>
    </div>
    <div class="lms-section-body">
    <table class="table table-sm table-hover videolink-preview mb-0" id="videolink-preview<?=$key?>">
        <thead class="table-light">
            <tr>
                <th><?= __('admin.video_file'); ?></th>
                <th width="20%"><?= __('admin.title'); ?></th>
                <th width="25%"><?= __('admin.description'); ?></th>
                <th width="60px"><?= __('admin.action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($value['data'] as $innngerKey => $innerValue) { ?>
            <tr>
                <td>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                        <input type="text" class="form-control" name="videolink[<?= $key ?>][]" value="<?= $innerValue['mask'] ?>" placeholder="<?= __('admin.enter_your_product_video_link{youtube/vimeo}') ?>">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="iszipResource[<?=$key?>][<?=$innngerKey?>]" value="<?= $innerValue['name'] ?>" class="form-check-input updateResource" id="<?= $innerValue['name'] ?>" <?= isset($innerValue['zip']['mask']) ? 'checked="checked"':''; ?>>
                        <label for="<?= $innerValue['name'] ?>" class="form-check-label small fw-semibold">
                            <i class="bi bi-paperclip me-1"></i><?= __('admin.lesson_resource'); ?>
                        </label>
                    </div>
                    <div class="resource lms-resource-box <?= isset($innerValue['zip']['mask']) ? '':'d-none'; ?>" id="resource<?= $innerValue['name'] ?>">
                        <?php if (isset($innerValue['zip']['mask'])): ?>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-file-zip text-warning"></i>
                                <span class="small fw-medium"><?= $innerValue['zip']['mask'] ?></span>
                                <span class="badge bg-light text-muted border"><?= $innerValue['zip']['size']??'0'; ?></span>
                            </div>
                        <?php endif ?>
                        <input type="file" class="form-control form-control-sm updateVideoFileResource mb-2" name="VideoFileZip[<?= $key ?>][]" accept=".zip" data-main="<?= $key ?>" data-name="<?= $innngerKey ?>" data-old-name="<?= $innerValue['name'] ?>">
                        <input type="text" name="VideoFileResourceText[<?=$key?>][]" value="<?= $innerValue['zip']['title']?>" placeholder="Resource Name" class="form-control form-control-sm">
                    </div>
                </td>
                <td><input type="text" class="form-control form-control-sm" name="videotext[<?= $key ?>][]" value="<?= $innerValue['videotext'] ?>" placeholder="<?= __('admin.title') ?>"></td>
                <td><input type="text" class="form-control form-control-sm" name="description[<?= $key ?>][]" value="<?=$innerValue['description']?>" placeholder="<?= __('admin.description') ?>"></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-priview-server">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
</div>
<?php } } ?>
</div>
</div>
</div>

</div>
</div>

</fieldset>

</div>
</div>
</div>
<div class="col-sm-12">
<div class="card shadow-sm">
	<div class="card-header bg-success text-white py-3">
		<h5 class="mb-0">
			<i class="bi bi-percent me-2"></i><?= __('admin.commission_settings'); ?>
		</h5>
	</div>

	<div class="card-body p-4">
		<?php if($seller){ ?>
			<div class="mb-4">
			    <label class="form-label fw-bold"><?= __('admin.product_status'); ?></label>
			    <div class="row g-3">
			        <div class="col-md-3">
			            <div class="form-check">
			                <input class="form-check-input" type="radio" name="product_status" id="status_review" value="0" checked>
			                <label class="form-check-label" for="status_review">
			                    <span class="badge bg-warning fs-6"><?= __('admin.in_review'); ?></span>
			                </label>
			            </div>
			        </div>
			        <div class="col-md-3">
			            <div class="form-check">
			                <input class="form-check-input" type="radio" name="product_status" id="status_approved" value="1" <?= (int)$product->product_status == 1 ? 'checked' : '' ?>>
			                <label class="form-check-label" for="status_approved">
			                    <span class="badge bg-success fs-6"><?= __('admin.approved'); ?></span>
			                </label>
			            </div>
			        </div>
			        <div class="col-md-3">
			            <div class="form-check">
			                <input class="form-check-input" type="radio" name="product_status" id="status_denied" value="2" <?= (int)$product->product_status == 2 ? 'checked' : '' ?>>
			                <label class="form-check-label" for="status_denied">
			                    <span class="badge bg-danger fs-6"><?= __('admin.denied'); ?></span>
			                </label>
			            </div>
			        </div>
			        <div class="col-md-3">
			            <div class="form-check">
			                <input class="form-check-input" type="radio" name="product_status" id="status_edit" value="3" <?= (int)$product->product_status == 3 ? 'checked' : '' ?>>
			                <label class="form-check-label" for="status_edit">
			                    <span class="badge bg-warning fs-6"><?= __('admin.ask_to_edit'); ?></span>
			                </label>
			            </div>
			        </div>
			    </div>
			</div>


			<div class="commission-setting">
				<fieldset class="custom-design mb-3 border p-3 rounded bg-light">
					<legend class="px-2 fs-6 fw-bold text-primary">
						<i class="bi bi-person-gear me-2"></i><?= __('admin.commission_for_admin'); ?>
					</legend>
					<div class="form-group mb-3">
						<label class="control-label fw-semibold"><?= __('admin.click_commission'); ?></label>
						<div>
							<?php
							$commission_type= array(
								'default'    => __('admin.default'),
								'fixed'      => __('admin.fixed'),
							);
							?>
							<select name="admin_click_commission_type" class="form-select">
								<?php foreach ($commission_type as $key => $value) { ?>
									<option <?= $seller->admin_click_commission_type == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="toggle-container mt-3">
							<div class="default-value d-none">
								<div class="alert alert-info">
									<i class="bi bi-info-circle me-2"></i>
									<strong><?= __('admin.default_commission'); ?>:</strong>
									<?php
									$commnent_line = "";
									if($vendor_setting['admin_click_amount'] && $vendor_setting['admin_click_count']){
										$commnent_line .= c_format($vendor_setting['admin_click_amount']) ." ".__('admin.per')." ". (int)$vendor_setting['admin_click_count'] ." ".__('admin.clicks');
									}
									else if($setting['product_commission_type'] == 'Fixed'){
										$commnent_line .= __('admin.default_commission_warning');
									}
									echo $commnent_line;
									?>
								</div>
							</div>
							<div class="custom-value d-none">										
								<div class="form-group">
									<div class="comm-group">
										<div class="row g-2">
											<div class="col-md-6">
												<div class="input-group mt-2">
													<div class="input-group-prepend"><span class="input-group-text"><?= __('admin.click'); ?></span></div>
													<input name="admin_click_count"  class="form-control" value="<?php echo $seller->admin_click_count; ?>" type="text" placeholder='<?= __('admin.clicks') ?>'>
												</div>
											</div>
											<div class="col-md-6">
												<div class="input-group mt-2">
													<div class="input-group-prepend"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
													<input name="admin_click_amount" class="form-control" value="<?php echo $seller->admin_click_amount; ?>" type="text" placeholder='<?= __('admin.amount') ?>'>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<script type="text/javascript">
							$("select[name=admin_click_commission_type]").on("change",function(){
								$con = $(this).parents(".form-group");
								$con.find(".toggle-container .percentage-value, .toggle-container .custom-value").addClass('d-none');

								if($(this).val() == 'default'){
									$con.find(".toggle-container .default-value").removeClass("d-none");
								}else{
									$con.find(".toggle-container .custom-value").removeClass("d-none");
								}
							})
							$("select[name=admin_click_commission_type]").trigger("change");
						</script>
					</div>

					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label fw-semibold"><?= __('admin.sale_commission'); ?></label>
								<div>
									<?php
									$commission_type= array(
										'default'    => __('admin.default'),
										'percentage' => __('admin.percentage'),
										'fixed'      => __('admin.fixed'),
									);
									?>
									<select name="admin_sale_commission_type" class="form-select">
										<?php foreach ($commission_type as $key => $value) { ?>
											<option <?= $seller->admin_sale_commission_type == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="toggle-container">
									<div class="default-value d-none">
										<label class="control-label fw-semibold d-block"><?= __('admin.default_commission') ?></label>
										<div class="alert alert-info">
											<?php
											$commnent_line = "";
											if($vendor_setting['admin_sale_commission_type'] == ''){
												$commnent_line .= __('admin.default_commission_warning');
											}
											else if($vendor_setting['admin_sale_commission_type'] == 'percentage'){
												$commnent_line .= __('admin.percentage').' : '. (float)$vendor_setting['admin_commission_value'] .'%';
											}
											else if($vendor_setting['admin_sale_commission_type'] == 'Fixed'){
												$commnent_line .= __('admin.fixed').' : '. c_format($vendor_setting['admin_commission_value']);
											}
											echo $commnent_line;
											?>
										</div>
									</div>
									<div class="percentage-value d-none">										
										<div class="form-group">
											<label class="control-label fw-semibold m-0"><?= __('admin.sale_commission'); ?></label>
											<input name="admin_commission_value" id="admin_commission_value" class="form-control mt-2" value="<?php echo $seller->admin_commission_value; ?>" type="text" placeholder='<?= __('admin.sale') ?>'>
										</div>
									</div>
								</div>
							</div>
						</div>

						<script type="text/javascript">
							$("select[name=admin_sale_commission_type]").on("change",function(){
								$con = $(this).parents(".form-group");
								$con.find(".toggle-container .percentage-value, .toggle-container .default-value").addClass('d-none');

								if($(this).val() == 'default'){
									$con.find(".toggle-container .default-value").removeClass("d-none");
								}else{
									$con.find(".toggle-container .percentage-value").removeClass("d-none");
								}
							})
							$("select[name=admin_sale_commission_type]").trigger("change");
						</script>
					</div>
				</fieldset>

				<fieldset class="custom-design mb-3 border p-3 rounded bg-light">
					<legend class="px-2 fs-6 fw-bold text-info">
						<i class="bi bi-people me-2"></i><?= __('admin.commission_for_affiliate'); ?>
					</legend>

					<div class="form-group mb-1">
						<div class="d-flex align-items-center mb-2">
							<i class="bi bi-cursor-fill text-primary me-2"></i>
							<label class="control-label fw-semibold"><?= __('admin.click_commission'); ?> : </label> 
						</div>
						<div class="alert alert-light">
							<?php 
							if($seller->affiliate_click_commission_type == 'default'){
								echo c_format($seller_setting->affiliate_click_amount) ." ".__('admin.per')." ". (int)$seller_setting->affiliate_click_count ."".__('admin.clicks');
							} else{ 
								echo c_format($seller->affiliate_click_amount) ." ".__('admin.per')." ". (int)$seller->affiliate_click_count ."".__('admin.clicks');
							} 
							?>
						</div>
					</div>

					<div class="form-group mb-1">
						<div class="d-flex align-items-center mb-2">
							<i class="bi bi-cart-check text-success me-2"></i>
							<label class="control-label fw-semibold"><?= __('admin.sale_commission'); ?> : </label> 
						</div>
						<div class="alert alert-light">
							<?php 
							$commnent_line = "";
							if($seller->affiliate_sale_commission_type == 'default'){ 
								if($seller_setting->affiliate_sale_commission_type == ''){
									$commnent_line .= __('admin.warning_default_commission_not_set');
								}
								else if($seller_setting->affiliate_sale_commission_type == 'percentage'){
									$commnent_line .= __('admin.percentage').' : '. (float)$seller_setting->affiliate_commission_value .'%';
								}
								else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
									$commnent_line .= __('admin.fixed').' : '. c_format($seller_setting->affiliate_commission_value);
								}
							} else if($seller->affiliate_sale_commission_type == 'percentage'){
								$commnent_line .= __('admin.percentage').' : '. (float)$seller->affiliate_commission_value .'%';
							} else if($seller->affiliate_sale_commission_type == 'fixed'){
								$commnent_line .= __('admin.fixed').' : '. c_format($seller->affiliate_commission_value);
							} 

							echo $commnent_line;

							?>
						</div>
					</div>

					<!-- -->

					<div class="percentage-value d-none">		

						<div class="form-group">
							<label class="control-label fw-semibold"><?= __('user.sale_commission'); ?></label>
							<div>
								<?php
									$commission_type= array(
										'default'    => 'Default',
										'percentage' => 'Percentage (%)',
										'fixed'      => 'Fixed',
									);
								?>
								<select name="affiliate_sale_commission_type" class="form-select">
									<?php foreach ($commission_type as $key => $value) { ?>
										<option <?= $seller->affiliate_sale_commission_type == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label fw-semibold"><?= __('user.click_commission'); ?></label>
							<div>
								<?php
									$commission_type= array(
										'default'    => 'Default',
										'fixed'      => 'Fixed',
									);
								?>
								<select name="affiliate_click_commission_type" class="form-select">
									<?php foreach ($commission_type as $key => $value) { ?>
										<option <?= $seller->affiliate_click_commission_type == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
									<?php } ?>
								</select>
							</div> 	
						</div>

						<div class="form-group">
							<label class="control-label fw-semibold m-0"><?= __('user.sale_commission') ?></label>
							<input name="affiliate_commission_value" id="affiliate_commission_value" class="form-control mt-2" value="<?php echo $seller->affiliate_commission_value; ?>" type="text" placeholder='Sale'>
						</div>

						<div class="form-group">
							<div class="comm-group">
								<div class="row g-2">
									<div class="col-md-6">
										<div class="input-group mt-2">
										  	<div class="input-group-prepend"><span class="input-group-text"><?= __('user.click') ?></span></div>
											<input name="affiliate_click_count"  class="form-control" value="<?php echo $seller->affiliate_click_count; ?>" type="text" placeholder='Clicks'>
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mt-2">
										  	<div class="input-group-prepend"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
											<input name="affiliate_click_amount" class="form-control" value="<?php echo $seller->affiliate_click_amount; ?>" type="text" placeholder='Amount'>
										</div>
									</div>
								</div>
							</div>
						</div>	
					</div>
				</fieldset>

				<fieldset class="custom-design mb-3 border p-3 rounded bg-light">
					<legend class="px-2 fs-6 fw-bold text-success">
						<i class="bi bi-calculator me-2"></i><?= __('admin.finalize_commission'); ?>
					</legend>
					<div class="row">
						<div class="col-sm-4">
							<label class="control-label fw-semibold">
								<i class="bi bi-person me-1"></i><?= __('admin.vendor') ?>  
								<i class="bi bi-info-circle text-muted ms-1" data-toggle='tooltip' title="<?= __('admin.info_lbl_product_owner') ?>"></i>
							</label>
							<input type="text" readonly="" value="0" class="form-control" id="ipt-vendor_commission">
						</div>
						<div class="col-sm-4">
							<label class="control-label fw-semibold">
								<i class="bi bi-shield-check me-1"></i><?= __('admin.admin') ?> 
								<i class="bi bi-info-circle text-muted ms-1" data-toggle='tooltip' title="<?= __('admin.info_lbl_admin') ?>"></i>
							</label>
							<input type="text" readonly="" value="0" class="form-control" id="ipt-admin_sale_com">
						</div>
						<div class="col-sm-4">
							<label class="control-label fw-semibold">
								<i class="bi bi-people me-1"></i><?= __('admin.affiliate') ?> 
								<i class="bi bi-info-circle text-muted ms-1" data-toggle='tooltip' title="<?= __('admin.info_lbl_product_other_affiliate') ?>"></i>
							</label>
							<input type="text" readonly="" value="0" class="form-control" id="ipt-affiliate_sale_com">
						</div>
					</div>
				</fieldset>
			</div>

		<?php } else { ?>	
			<div class="text-center py-4">
				<i class="bi bi-exclamation-triangle-fill text-warning fs-1 mb-3"></i>
				<h5 class="text-warning"><?= __('admin.no_any_vendor_on_this_product'); ?></h5>
			</div>

			<fieldset class="custom-design mb-3 border p-3 rounded bg-light">
				<legend class="px-2 fs-6 fw-bold text-info">
					<i class="bi bi-people me-2"></i><?= __('admin.commission_for_affiliate'); ?>
				</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<?php
							$selected_commition_type = $product->product_commision_type;
							$selected_commision_value = $product->product_commision_value;
							$commission_type= array(
								'default'    => __('admin.default'),
								'percentage' => __('admin.percentage').' (%)',
								'fixed'      => __('admin.fixed'),
							);
							?>
							<div class="form-group">
								<label class="control-label fw-semibold"><?= __('admin.sale_commission') ?></label>
								<select name="product_commision_type" class="form-select">
									<?php foreach ($commission_type as $key => $value) { ?>
										<option <?= $key == $selected_commition_type ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="toggle-container">
								<div class="default-value d-none">
								    <div class="form-group mb-4">
								        <label for="defaultCommission" class="form-label fw-semibold"><?= __('admin.default_commission') ?></label>
								        <div id="defaultCommission" class="alert alert-info">
								            <?php
								                $commnent_line = __('admin.default_commission_warning');
								                
								                if ($setting['product_commission_type'] == 'percentage') {
								                    $commnent_line = __('admin.default_commission') . ' : ' . $setting['product_commission'] . '%';
								                }
								                else if ($setting['product_commission_type'] == 'Fixed') {
								                    $commnent_line = __('admin.default_commission') . ' : ' . $setting['product_commission'];
								                }
								                echo $commnent_line;
								            ?>
								        </div>
								    </div>
								</div>
								<div class="percentage-value d-none">
									<div class="form-group">
										<label class="control-label fw-semibold"><?= __('admin.sale_commission') ?></label>
										<input placeholder="<?= __('admin.enter_product_sale_commission_value') ?>" name="product_commision_value" id="product_commision_value" class="form-control" value="<?php echo $selected_commision_value; ?>" type="text">
									</div>
								</div>
							</div>
						</div>
					</div>

					<script type="text/javascript">
						$("select[name=product_commision_type]").on("change",function(){
							$con = $(this).parents(".form-group");
							$con.find(".toggle-container .percentage-value, .toggle-container .default-value").addClass('d-none');

							if($(this).val() == 'default'){
								$con.find(".toggle-container .default-value").removeClass("d-none");
							}else{
								$con.find(".toggle-container .percentage-value").removeClass("d-none");
							}
						})
						$("select[name=product_commision_type]").trigger("change");
					</script>
				</div>


				<div class="form-group">
					<label class="control-label fw-semibold"><?= __('admin.product_click_commission') ?></label>
					<div>
						<?php
						$selected_commition_type = $product->product_click_commision_type;
						$product_click_commision_ppc = $product->product_click_commision_ppc;
						$product_click_commision_per = $product->product_click_commision_per;
						?>
						<select name="product_click_commision_type" class="form-select">
							<option <?= 'default' == $selected_commition_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
							<option <?= 'custom' == $selected_commition_type ? 'selected' : '' ?> value="custom"><?= __('admin.custom') ?></option>
						</select>
					</div>
					<div class="toggle-container mt-3">
						<div class="d-none default-value">
							<div class="alert alert-info">
								<?php
								if($setting['product_noofpercommission'] != '' && $setting['product_ppc'] != ''){
									echo __('admin.default_commission')." : ".c_format($setting['product_ppc']). " ".__('admin.per')." " . $setting['product_noofpercommission'] . " ".__('admin.clicks');
								} else {
									echo __('admin.default_commission_warning');
								}
								?>
							</div>
						</div>
						<div class="d-none custom-value">
							<div class="comm-group">
								<div class="row g-2">
									<div class="col-md-6">
										<div class="input-group mt-2">
											<div class="input-group-prepend"><span class="input-group-text"><?= __('admin.click') ?></span></div>
											<input placeholder="<?= __('admin.number_of_clicks_per_commission') ?>" name="product_click_commision_per" id="product_click_commision_value" class="form-control" value="<?php echo $product_click_commision_per; ?>" type="text">
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group mt-2">
											<div class="input-group-prepend"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
											<input placeholder="<?= __('admin.commission_amount') ?>" name="product_click_commision_ppc" id="product_click_commision_ppc" class="form-control" value="<?php echo $product_click_commision_ppc; ?>" type="text">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<script type="text/javascript">
						$("select[name=product_click_commision_type]").on("change",function(){
							$con = $(this).parents(".form-group");
							$con.find(".toggle-container .custom-value, .toggle-container .default-value").addClass('d-none');

							if($(this).val() == 'default'){
								$con.find(".toggle-container .default-value").removeClass("d-none");
							}else{
								$con.find(".toggle-container .custom-value").removeClass("d-none");
							}
						})
						$("select[name=product_click_commision_type]").trigger("change");
					</script>
				</div>
			</fieldset>

		<?php } ?>
	</div>
</div>
			<?php if($seller){ ?>
				<div class="card mt-3 shadow-sm">
					<div class="card-header bg-warning text-dark py-3">
						<h4 class="header-title mb-0">
							<i class="bi bi-chat-dots me-2"></i><?= __('admin.admin_comments') ?>
						</h4>
					</div>
					<div class="card-body chat-card p-0">
						<?php $comment = json_decode($seller->comment,1); ?>
						<?php if($comment){ ?>
							<div class="p-3" style="max-height: 300px; overflow-y: auto;">
								<?php foreach ($comment as $key => $value) { ?>
									<div class="d-flex mb-3 <?= $value['from'] == 'admin' ? 'justify-content-end' : 'justify-content-start' ?>">
										<div class="card border-0 <?= $value['from'] == 'admin' ? 'bg-primary text-white' : 'bg-light' ?>" style="max-width: 70%;">
											<div class="card-body py-2 px-3">
												<p class="mb-1"><?= $value['comment'] ?></p>
												<small class="<?= $value['from'] == 'admin' ? 'text-white-50' : 'text-muted' ?>"><?= $value['from'] == 'admin' ? 'Admin' : 'Vendor' ?></small>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
						<div class="p-3 border-top bg-light">
							<textarea class="form-control" placeholder="<?= __('admin.enter_message_and_save_product_to_send') ?>" name="admin_comment" rows="3"></textarea>
						</div>
					</div>
				</div>
			<?php } ?>

		<!--Save buttons (hidden – now in sticky sidebar)-->
		<div class="mt-4 mb-4 d-none">
		    <div class="d-flex justify-content-end gap-3">
			        <!-- Preview Button -->
			        <?php if($product->product_slug) { ?>
			            <?php $productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product->product_slug ); ?>
			            <a class="btn btn-outline-success btn-lg" href="<?php echo $productLink ?>" target='_blank'>
			                <i class="bi bi-eye me-2"></i> <?= __('admin.preview') ?>
			            </a>
			        <?php } ?>

			        <!-- Save and Close Button -->
			        <button type="submit" class="btn btn-secondary btn-lg btn-submit" name="save_close">
			            <i class="bi bi-save2 me-2"></i> <?= __('admin.save_and_close') ?>
			            <!-- Spinner icon that shows during loading -->
			            <span class="loading-submit d-none">
			                <div class="spinner-border spinner-border-sm text-light" role="status"></div>
			            </span>
			        </button>

			        <!-- Save Button -->
			        <button type="submit" class="btn btn-success btn-lg btn-submit" name="save">
			            <i class="bi bi-save me-2"></i> <?= __('admin.save_continue_editing') ?>
			            <!-- Spinner icon that shows during loading -->
			            <span class="loading-submit d-none">
			                <div class="spinner-border spinner-border-sm text-light" role="status"></div>
			            </span>
			        </button>
			    </div>
			</div>
			<!--Save buttons-->

		</div>
	</div>
</div>
</div>
</form>
</div>

<div id="modal-variants" class="modal fade" tabindex="-1" aria-labelledby="modal-variantsLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius:16px;">

			<!-- Header -->
			<div class="modal-header border-0 py-3 px-4" style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);">
				<div class="d-flex align-items-center gap-3">
					<div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
						style="width:42px;height:42px;background:rgba(255,255,255,.18);">
						<i class="bi bi-sliders2 text-white fs-5"></i>
					</div>
					<div>
						<h5 class="modal-title mb-0 fw-bold text-white" id="modal-variantsLabel"><?= __('admin.add_variation') ?></h5>
						<div class="text-white small" style="opacity:.75;"><?= __('admin.variation_type') ?></div>
					</div>
				</div>
				<button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<!-- Body -->
			<div class="modal-body p-4" style="max-height:72vh;overflow-y:auto;background:#f8f9fb;">

				<!-- Type toggle cards -->
				<div class="mb-4">
					<div class="form-label fw-semibold text-uppercase text-secondary mb-2" style="font-size:.7rem;letter-spacing:.06em;"><?= __('admin.variation_type') ?></div>
					<div class="row g-2" id="variant-type-btns">
						<div class="col-6">
							<button type="button" class="btn variant-type-btn w-100 py-3 text-start px-3 bg-white border rounded-3 active" data-type="other">
								<div class="d-flex align-items-center gap-2">
									<span class="rounded-2 d-inline-flex align-items-center justify-content-center flex-shrink-0"
										style="width:36px;height:36px;background:#ede9fe;">
										<i class="bi bi-tag-fill" style="color:#7c3aed;"></i>
									</span>
									<div>
										<div class="fw-semibold small"><?= __('admin.other_variation') ?></div>
										<div class="text-muted" style="font-size:.72rem;">Size, Weight, Style…</div>
									</div>
								</div>
							</button>
						</div>
						<div class="col-6">
							<button type="button" class="btn variant-type-btn w-100 py-3 text-start px-3 bg-white border rounded-3" data-type="colors">
								<div class="d-flex align-items-center gap-2">
									<span class="rounded-2 d-inline-flex align-items-center justify-content-center flex-shrink-0"
										style="width:36px;height:36px;background:#fef3c7;">
										<i class="bi bi-palette-fill" style="color:#f59e0b;"></i>
									</span>
									<div>
										<div class="fw-semibold small"><?= __('admin.color') ?></div>
										<div class="text-muted" style="font-size:.72rem;">Hex color picker</div>
									</div>
								</div>
							</button>
						</div>
					</div>
					<!-- Hidden select keeps all JS logic intact -->
					<select id="variation_type" style="display:none!important;">
						<option value="colors"><?= __('admin.color') ?></option>
						<option value="other"><?= __('admin.other_variation') ?></option>
					</select>
				</div>

				<!-- Variation title (other type only) -->
				<div class="other_variation_title_input mb-4 bg-white rounded-3 p-3 border" style="display:none;">
					<div class="form-label fw-semibold text-uppercase text-secondary mb-2" style="font-size:.7rem;letter-spacing:.06em;"><?= __('admin.variation_title') ?></div>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="bi bi-pencil-fill text-muted small"></i></span>
						<input type="text" class="form-control border-start-0 ps-1" id="other_variation_title" maxlength="25"
							placeholder="e.g. Size, Material, Style…">
					</div>
					<div class="form-text mt-1"><i class="bi bi-info-circle me-1"></i>This label groups all options under one variation.</div>
				</div>

				<!-- Options area -->
				<div>
					<div class="form-label fw-semibold text-uppercase text-secondary mb-2" style="font-size:.7rem;letter-spacing:.06em;"><?= __('admin.variation_option') ?>s</div>
					<div class="colors-list"></div>
					<div class="features-list" style="display:none;"></div>
				</div>

			</div>

			<!-- Footer -->
			<div class="modal-footer bg-white border-top px-4 py-3">
				<button type="button" class="btn btn-light border" data-bs-dismiss="modal">
					<i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?>
				</button>
				<button type="button" class="btn btn-primary px-4 fw-semibold add-variation-to-form">
					<i class="bi bi-check2-circle me-2"></i><?= __('admin.add_variants') ?>
				</button>
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
		if ($('#product-variations tbody tr').length === 0) {
			$('#variants-empty-msg').show();
		}
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
		// Reset modal state when opening for a new variant (not editing)
		if(options === null) {
			$('#modal-variants #variation_type').val('other').trigger('change');
			$('#modal-variants #other_variation_title').val('');
		}
		let colors = "";
		let features = "";
		if(options != null) {
			for (let index = 0; index < options.length; index++) {
				if(options[index].code) {
					colors += `<div class="variant-option-row d-flex align-items-center gap-2 mb-2 p-2 bg-white rounded-2 border">
						<input type="text" class="jscolor color-code" data-jscolor value="`+options[index].code+`"
							style="width:38px;height:38px;border-radius:6px;border:2px solid #e2e8f0;cursor:pointer;padding:2px;flex-shrink:0;">
						<input type="text" class="form-control form-control-sm color-name flex-fill" value="`+options[index].name+`" placeholder="Color name">
						<div class="input-group input-group-sm flex-shrink-0" style="max-width:120px;">
							<span class="input-group-text">+$</span>
							<input type="number" class="form-control color-price" value="`+options[index].price+`" min="0" step="0.01">
						</div>
						<button type="button" class="btn btn-sm btn-outline-danger btn-remove-variation flex-shrink-0"><i class="bi bi-trash3"></i></button>
					</div>`;
				} else {
					let features_name = options[index].name ? options[index].name : options[index];
					let features_price = options[index].price ? options[index].price : 0;
					features += `<div class="variant-option-row d-flex align-items-center gap-2 mb-2 p-2 bg-white rounded-2 border">
						<input type="text" class="form-control form-control-sm variation-option flex-fill" value="`+features_name+`" placeholder="Option value">
						<div class="input-group input-group-sm flex-shrink-0" style="max-width:120px;">
							<span class="input-group-text">+$</span>
							<input type="number" class="form-control variation-price" value="`+features_price+`" min="0" step="0.01">
						</div>
						<button type="button" class="btn btn-sm btn-outline-danger btn-remove-variation flex-shrink-0"><i class="bi bi-trash3"></i></button>
					</div>`;
				}			
			}
		}

		$('#modal-variants .colors-list').html(colors+`
			<div class="variant-option-row d-flex align-items-center gap-2 mb-2 p-2 bg-white rounded-2 border">
				<input type="text" class="jscolor color-code" data-jscolor value="#FFFFFF"
					style="width:38px;height:38px;border-radius:6px;border:2px solid #e2e8f0;cursor:pointer;padding:2px;flex-shrink:0;">
				<input type="text" class="form-control form-control-sm color-name flex-fill" placeholder="Color name">
				<div class="input-group input-group-sm flex-shrink-0" style="max-width:120px;">
					<span class="input-group-text">+$</span>
					<input type="number" class="form-control color-price" value="0" min="0" step="0.01">
				</div>
				<button type="button" class="btn btn-sm btn-outline-danger btn-remove-variation flex-shrink-0"><i class="bi bi-trash3"></i></button>
			</div>
			<button type="button" class="btn btn-sm btn-outline-primary btn-add-color w-100 mt-1" style="border-style:dashed;">
				<i class="bi bi-plus-circle me-1"></i><?= __('admin.color') ?>
			</button>`);

		$('#modal-variants .features-list').html(features+`
			<div class="variant-option-row d-flex align-items-center gap-2 mb-2 p-2 bg-white rounded-2 border">
				<input type="text" class="form-control form-control-sm variation-option flex-fill" placeholder="Option value (e.g. Large)">
				<div class="input-group input-group-sm flex-shrink-0" style="max-width:120px;">
					<span class="input-group-text">+$</span>
					<input type="number" class="form-control variation-price" value="0" min="0" step="0.01">
				</div>
				<button type="button" class="btn btn-sm btn-outline-danger btn-remove-variation flex-shrink-0"><i class="bi bi-trash3"></i></button>
			</div>
			<button type="button" class="btn btn-sm btn-outline-success btn-add-feature w-100 mt-1" style="border-style:dashed;">
				<i class="bi bi-plus-circle me-1"></i><?= __('admin.variation_option') ?>
			</button>`);
		jscolor.install();
		$('#modal-variants').modal('show');
	}

	$(document).on('click', '.add-variation-to-form', function(){
		let variation = {
			name : null,
			options : [],
			isEdit : false
		}
		if($('#modal-variants #variation_type').val() == 'colors') {
			variation.name = 'colors';
			variation.options = getOptions("#modal-variants .color-code", "#modal-variants .color-name", "#modal-variants .color-price");
		} else {
			variation.name = $('#modal-variants #other_variation_title').val().trim();
			variation.name = variation.name.replace(/\s+/g, '-').toLowerCase();
			variation.options = getOptions("#modal-variants .variation-option", "#modal-variants .variation-price");
		}
		// Check if this is a new add or an edit
		variation.isEdit = ($('#product-variations tbody tr[data-variation-type="'+variation.name+'"]').length > 0);
		if(!variation.name) {
			alert('<?= __('admin.variation_title') ?>');
			return false;
		}

		if(variation.name != null && variation.name != "" && variation.options.length > 0) {
			let optionsHtml = ``;
			for (let index = 0; index < variation.options.length; index++) {
				if(variation.name == 'colors') {
					optionsHtml += (index == 0) ? toTitleCase(variation.options[index]['name']) : ", "+toTitleCase(variation.options[index]['name']);
					optionsHtml += `<input type='hidden' name='variations[`+variation.name+`][name][]' value='`+variation.options[index]['name']+`'>`;
					optionsHtml += `<input type='hidden' name='variations[`+variation.name+`][code][]' value='`+variation.options[index]['code']+`'>`;
					optionsHtml += `<input type='hidden' name='variations[`+variation.name+`][price][]' value='`+variation.options[index]['price']+`'>`;
				} else {
					optionsHtml += (index == 0) ? toTitleCase(variation.options[index]['name']) : ", "+toTitleCase(variation.options[index]['name']);
					optionsHtml += `<input type='hidden' name='variations[`+variation.name+`][name][]' value='`+variation.options[index]['name']+`'>`;
					optionsHtml += `<input type='hidden' name='variations[`+variation.name+`][price][]' value='`+variation.options[index]['price']+`'>`;
				}
			}
			let row = `<td class="fw-semibold text-secondary ps-3" style="width:140px;">`+toTitleCase(variation.name)+`</td>
			<td class="text-muted small">`+optionsHtml+`</td>
			<td class="text-end pe-3" style="width:80px;">
				<div class="btn-group btn-group-sm" role="group">
					<button type="button" data-variation-type="`+variation.name+`" class="btn btn-outline-warning btn-edit-variants"><i class="bi bi-pencil"></i></button>
					<button type="button" data-variation-type="`+variation.name+`" class="btn btn-outline-danger btn-delete-variants"><i class="bi bi-trash"></i></button>
				</div>
			</td>`;

			if($('#product-variations tbody tr[data-variation-type="'+variation.name+'"]').length != 0){
				$('#product-variations tbody tr[data-variation-type="'+variation.name+'"]').html(row);
			} else {
				$('#product-variations tbody').append(`<tr data-variation-type="`+variation.name+`">`+row+`</tr>`);
			}
			$('#variants-empty-msg').hide();
		}

		$('#modal-variants').modal('hide');
	});

	$(document).on('click', '.btn-add-color', function(){
		$(this).before(`
			<div class="variant-option-row d-flex align-items-center gap-2 mb-2 p-2 bg-white rounded-2 border">
				<input type="text" class="jscolor color-code" data-jscolor value="#FFFFFF"
					style="width:38px;height:38px;border-radius:6px;border:2px solid #e2e8f0;cursor:pointer;padding:2px;flex-shrink:0;">
				<input type="text" class="form-control form-control-sm color-name flex-fill" placeholder="Color name">
				<div class="input-group input-group-sm flex-shrink-0" style="max-width:120px;">
					<span class="input-group-text">+$</span>
					<input type="number" class="form-control color-price" value="0" min="0" step="0.01">
				</div>
				<button type="button" class="btn btn-sm btn-outline-danger btn-remove-variation flex-shrink-0"><i class="bi bi-trash3"></i></button>
			</div>`);
		jscolor.install();
	});

	$(document).on('click', '.btn-add-feature', function(){
		$(this).before(`
			<div class="variant-option-row d-flex align-items-center gap-2 mb-2 p-2 bg-white rounded-2 border">
				<input type="text" class="form-control form-control-sm variation-option flex-fill" placeholder="Option value (e.g. Large)">
				<div class="input-group input-group-sm flex-shrink-0" style="max-width:120px;">
					<span class="input-group-text">+$</span>
					<input type="number" class="form-control variation-price" value="0" min="0" step="0.01">
				</div>
				<button type="button" class="btn btn-sm btn-outline-danger btn-remove-variation flex-shrink-0"><i class="bi bi-trash3"></i></button>
			</div>`);
	});

	$(document).on('click', '.btn-remove-variation', function(){
		$(this).closest('.variant-option-row').remove();
	});

	$(document).on('keypress', '#other_variation_title', function (event) {
		var regex = new RegExp("^[a-zA-Z0-9 ]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
			event.preventDefault();
			return false;
		}
	});

	$(document).on('click', '.variant-type-btn', function(){
		let type = $(this).data('type');
		$('#variant-type-btns .variant-type-btn').removeClass('active');
		$(this).addClass('active');
		$('#variation_type').val(type).trigger('change');
	});

	$(document).on('change', "#variation_type", function(){
		let val = $(this).val();
		$('#variant-type-btns .variant-type-btn').removeClass('active');
		$('#variant-type-btns .variant-type-btn[data-type="'+val+'"]').addClass('active');
		if(val == 'other') {
			$('.other_variation_title_input').show();
			$('.colors-list').hide();
			$('.features-list').show();
		} else {
			$('.other_variation_title_input').hide();
			$('.colors-list').show();
			$('.features-list').hide();
		}
	});

	// ── Auto color-name from hex picker ────────────────────────────────
	function getColorName(hex) {
		hex = hex.replace('#','').toLowerCase();
		if(hex.length === 3) hex = hex.split('').map(c => c+c).join('');
		if(hex.length !== 6) return '';
		let r = parseInt(hex.substr(0,2),16);
		let g = parseInt(hex.substr(2,2),16);
		let b = parseInt(hex.substr(4,2),16);
		let palette = [
			['Black','000000'],['White','ffffff'],['Red','ff0000'],['Lime','00ff00'],
			['Blue','0000ff'],['Yellow','ffff00'],['Cyan','00ffff'],['Magenta','ff00ff'],
			['Silver','c0c0c0'],['Gray','808080'],['Maroon','800000'],['Olive','808000'],
			['Green','008000'],['Purple','800080'],['Teal','008080'],['Navy','000080'],
			['Orange','ffa500'],['Pink','ffc0cb'],['Gold','ffd700'],['Coral','ff7f50'],
			['Salmon','fa8072'],['Khaki','f0e68c'],['Indigo','4b0082'],['Violet','ee82ee'],
			['Crimson','dc143c'],['Turquoise','40e0d0'],['Tan','d2b48c'],['Brown','a52a2a'],
			['Beige','f5f5dc'],['Ivory','fffff0'],['Lavender','e6e6fa'],['Mint','98ff98'],
			['Peach','ffcba4'],['Rose','ff007f'],['Sky Blue','87ceeb'],['Steel Blue','4682b4'],
			['Sea Green','2e8b57'],['Slate Gray','708090'],['Chocolate','d2691e'],
			['Tomato','ff6347'],['Hot Pink','ff69b4'],['Deep Pink','ff1493'],
			['Dark Red','8b0000'],['Dark Blue','00008b'],['Dark Green','006400'],
			['Dark Orange','ff8c00'],['Light Blue','add8e6'],['Light Green','90ee90'],
			['Light Pink','ffb6c1'],['Light Yellow','ffffe0'],['Light Gray','d3d3d3'],
			['Light Coral','f08080'],['Dark Gray','a9a9a9'],['Wheat','f5deb3'],
			['Peru','cd853f'],['Sienna','a0522d'],['Lime Green','32cd32'],
			['Forest Green','228b22'],['Yellow Green','9acd32'],['Chartreuse','7fff00'],
			['Spring Green','00ff7f'],['Royal Blue','4169e1'],['Dodger Blue','1e90ff'],
			['Deep Sky Blue','00bfff'],['Powder Blue','b0e0e6'],['Pale Green','98fb98'],
			['Medium Purple','9370db'],['Medium Blue','0000cd'],['Olive Green','6b8e23'],
			['Sand','c2b280'],['Charcoal','36454f'],['Off White','faf9f6'],
		];
		let minDist = Infinity, name = '';
		for(let [n, h] of palette) {
			let cr = parseInt(h.substr(0,2),16);
			let cg = parseInt(h.substr(2,2),16);
			let cb = parseInt(h.substr(4,2),16);
			let dist = (r-cr)**2 + (g-cg)**2 + (b-cb)**2;
			if(dist < minDist) { minDist = dist; name = n; }
		}
		return name;
	}

	// Auto-fill color name when picker changes (respects manual entries)
	$(document).on('input change', '.color-code', function() {
		let row = $(this).closest('.variant-option-row');
		let nameInput = row.find('.color-name');
		if(nameInput.val() === '' || nameInput.data('autoFilled') === true) {
			let name = getColorName($(this).val());
			if(name) {
				nameInput.val(name).data('autoFilled', true);
			}
		}
	});

	// If user types manually, stop overriding
	$(document).on('input', '.color-name', function() {
		$(this).data('autoFilled', false);
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
			url:'<?= base_url('admincontrol/calc_commission') ?>',
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
	}

	calcCommission();

	// Initialize autocomplete with proper error handling
	$(document).ready(function() {
		// Wait a bit to ensure jQuery UI is loaded
		setTimeout(function() {
			if (typeof $.fn.autocomplete !== 'undefined') {
				$("#category_auto").autocomplete({
					source: function( request, response ) {
						var term = request.term;
						if ( term in cache ) {response( cache[ term ] );return;}

						$.getJSON( '<?= base_url('admincontrol/category_auto') ?>', request, function( data, status, xhr ) {
							cache[ term ] = data;
							response( data );
						});
					},
					minLength: 0,
				select: function (event, ui) {
			    $("#category_auto").blur();
			    event.preventDefault();
			    if ($(".category-selected input[value='" + ui.item.value + "']").length == 0) {
			        $(".category-selected").append(`
			            <li class="list-group-item d-flex justify-content-between align-items-center">
			                <span>${ui.item.label}</span>
			                <input type="hidden" name="category[]" value="${ui.item.value}">
			                <button type="button" class="btn btn-danger btn-sm remove-category">
			                <i class="bi bi-trash"></i>
			                </button>
			            </li>
			        `);
			        $(".category-selected-wrap").removeClass('d-none');
			    }
			},
				}).on('focus',function(){
					if ($(this).data("uiAutocomplete")) {
						$(this).data("uiAutocomplete").search($(this).val());
					}
				});
				console.log('Autocomplete initialized successfully');
			} else {
				console.warn('jQuery UI autocomplete not available - using fallback');
				// Fallback: simple dropdown or search functionality
				$("#category_auto").on('focus', function() {
					// Load categories on focus if autocomplete is not available
					$.getJSON('<?= base_url('admincontrol/category_auto') ?>', {term: ''}, function(data) {
						// Create a simple dropdown or handle differently
						console.log('Categories loaded:', data);
					});
				});
			}
		}, 500); // Wait 500ms for jQuery UI to load
	});

	$(".category-selected").delegate(".remove-category",'click', function(){
		$(this).parents("li").remove();
		if ($(".category-selected li").length === 0) {
			$(".category-selected-wrap").addClass('d-none');
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
	var video_fileArr=[];
	var video_fileZipArr=[];
	$(".btn-submit").on('click',function(evt){
		evt.preventDefault();
		$btn = $(this);
		
				// Ensure toast system is available
		if (typeof showToast === 'undefined') {
			console.error('showToast function is not available!');
			alert('Toast system not loaded. Please refresh the page.');
			return false;
		}
		
		// Ensure toast container exists and is positioned correctly
		var toastContainer = document.getElementById('toastContainer');
		if (!toastContainer) {
			console.log('Toast container not found, creating it...');
			// Create toast container if it doesn't exist (same as footer logic)
			toastContainer = document.createElement('div');
			toastContainer.id = 'toastContainer';
			toastContainer.className = 'toast-container';
			document.body.appendChild(toastContainer);
		}
		
		// Force toast container positioning to ensure visibility
		toastContainer.style.cssText = 'position: fixed !important; bottom: 20px !important; right: 20px !important; z-index: 9999 !important; pointer-events: none !important;';
		var formData = new FormData($("#form_form")[0]);

		var allow_upload_file_checked = $('input[name=allow_upload_file]').prop('checked');
		if (allow_upload_file_checked == true) {
			formData.append("allow_upload_file", "1");
		}else{
			formData.append("allow_upload_file", "0");
		}

		var on_store_checked = $('input[name=on_store]').prop('checked');
		if (on_store_checked == true) {
			formData.append("on_store", "1");
		}else{
			formData.append("on_store", "0");
		}

		var allow_shipping_checked = $('input[name=allow_shipping]').prop('checked');
		if (allow_shipping_checked == true) {
			formData.append("allow_shipping", "1");
		}else{
			formData.append("allow_shipping", "0");
		}
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
			}
			 );
		
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

		formData.append("action", $(this).attr("name"));

		formData = formDataFilter(formData);
		$this = $("#form_form");	       

		$btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
		$.ajax({
			url:'<?= base_url('admincontrol/editProduct') ?>',
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
						$('.loading-submit').text(percentComplete + "% "+'<?= __('admin.loading') ?>');
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
		success:function(result){            	
			$btn.prop('disabled', false).html($btn.data('original-text') || 'Submit');
			$('.loading-submit').hide();
			$this.find(".has-error").removeClass("has-error");
			$this.find("span.text-danger").remove();
			$('.downloadable_file_div').removeClass('border border-danger rounded p-2').find('.alert-danger').remove();
			$('.video_file_div').removeClass('border border-danger rounded p-2').find('.alert-danger').remove();
			$('.proSubType').parent().removeClass('border border-danger rounded p-2').parent().find('.alert-danger').remove();
			$('.video_file_uploader_div').removeClass('border border-danger rounded p-2').find('.alert-danger').remove();
			$('.video_link_div').removeClass('border border-danger rounded p-2').find('.alert-danger').remove();

				if(result['location'] && !result['errors']){
					// Show success toast notification
					showToast('<?= __('admin.saved_successfully') ?>', '<?= __('admin.changes_saved') ?>', 'success');
					
					// Force toast visibility with inline styles to ensure it shows
					setTimeout(function() {
						var toasts = document.querySelectorAll('.toast.success');
						toasts.forEach(function(toast) {
							toast.style.cssText = 'position: relative !important; transform: translateX(0) !important; opacity: 1 !important; background: white !important; border-radius: 6px !important; box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important; padding: 12px 16px !important; margin-bottom: 8px !important; min-width: 250px !important; max-width: 320px !important; border-left: 3px solid #28a745 !important; z-index: 9999 !important; color: #333 !important;';
							
							// Also ensure the title and message text are black
							var title = toast.querySelector('.toast-title');
							var message = toast.querySelector('.toast-message');
							if (title) title.style.color = '#333 !important';
							if (message) message.style.color = '#666 !important';
						});
					}, 100);
					
					// Only redirect if "save_close" action
					if($btn.attr('name') == 'save_close'){
						window.location = result['location'];
					}
			} else if(result['errors']){
				var errorCount = Object.keys(result['errors']).length;
				var firstErrorField = null;
				var errorFields = [];
				var addedFields = {};
				
			var fieldNameMap = {
				'category[]': 'Category',
				'category_auto': 'Category',
				'product_name': 'Name',
				'product_description': 'Description',
				'product_short_description': 'Short Description',
				'product_price': 'Price',
				'product_sku': 'SKU',
				'product_msrp': 'MSRP',
				'product_featured_image': 'Featured Image',
				'downloadable_file': 'Downloadable Files',
				'sub_product_type': 'LMS Product Type',
				'video_files': 'Video Files',
				'video_links': 'Video Links'
			};
				
			$.each(result['errors'], function(i,j){
				$ele = $this.find('[name="'+ i +'"]');
				
				if(i === 'downloadable_file') {
					$('.downloadable_file_div').addClass('border border-danger rounded p-2');
					$('.downloadable_file_div').prepend("<div class='alert alert-danger mt-2 mb-2'><i class='fas fa-exclamation-triangle me-2'></i>"+ j +"</div>");
					if(!firstErrorField) {
						firstErrorField = $('.downloadable_file_div');
					}
				} else if(i === 'sub_product_type') {
					$('.proSubType').parent().addClass('border border-danger rounded p-2');
					$('.proSubType').parent().parent().prepend("<div class='alert alert-danger mb-2'><i class='fas fa-exclamation-triangle me-2'></i>"+ j +"</div>");
					if(!firstErrorField) {
						firstErrorField = $('.proSubType').first();
					}
				} else if(i === 'video_files') {
					$('.video_file_uploader_div').addClass('border border-danger rounded p-2');
					$('.video_file_uploader_div').prepend("<div class='alert alert-danger mt-2 mb-2'><i class='fas fa-exclamation-triangle me-2'></i>"+ j +"</div>");
					if(!firstErrorField) {
						firstErrorField = $('.video_file_uploader_div');
					}
				} else if(i === 'video_links') {
					$('.video_link_div').addClass('border border-danger rounded p-2');
					$('.video_link_div').prepend("<div class='alert alert-danger mt-2 mb-2'><i class='fas fa-exclamation-triangle me-2'></i>"+ j +"</div>");
					if(!firstErrorField) {
						firstErrorField = $('.video_link_div');
					}
				} else if($ele.length > 0){
					$ele.parents(".form-group").addClass("has-error");
					$ele.after("<span class='text-danger'>"+ j +"</span>");
					
					if(!firstErrorField) {
						firstErrorField = $ele;
					}
				}
				
				var fieldLabel = fieldNameMap[i] || ($ele.length > 0 ? $ele.closest('.form-group').find('label').first().text().replace('*', '').trim() : fieldNameMap[i]) || i;
				fieldLabel = fieldLabel.replace(/\s+/g, ' ').trim();
				
				if(fieldLabel && !addedFields[fieldLabel.toLowerCase()]) {
					errorFields.push(fieldLabel);
					addedFields[fieldLabel.toLowerCase()] = true;
				}
			});
			
		var actualErrorCount = errorFields.length > 0 ? errorFields.length : errorCount;
		var productType = $('input[name="product_type"]:checked').parent('label').text().trim();
		if(!productType) {
			productType = $('#product_type_title').text().trim() || 'Product';
		}
		var errorMessage = (errorFields.length > 0 ? errorFields.join(', ') : actualErrorCount + ' <?= __('admin.fields') ?>');
		var title = productType + ': ' + actualErrorCount + ' <?= __('admin.required_fields_missing') ?>';
		showToast(title, errorMessage, 'error');
				
				if(firstErrorField) {
					$('html, body').animate({
						scrollTop: firstErrorField.offset().top - 100
					}, 500);
					setTimeout(function() {
						firstErrorField.focus();
					}, 600);
				}
			}
			},
			error: function(xhr, status, error) {
				$btn.prop('disabled', false).html($btn.data('original-text') || 'Submit');
				$('.loading-submit').hide();
				showToast('<?= __('admin.error') ?>', '<?= __('admin.save_error') ?>', 'error');
			}
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
if($('#endtime').length) {

	$('#endtime').datetimepicker({
		format:'d-m-Y H:i',
		inline:true,
	});
}

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
});

var fileArray = [];

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
	if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

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
				$('.proSubType').removeClass('btn_active');
				$(this).parent().addClass('btn_active');
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
			var isLMS        = (val == 'video' || val == 'videolink');
			var isPhysical   = (val == 'virtual');        // only physical/virtual needs shipping
			var isDigital    = (val == 'downloadable');

			// Toggle LMS-specific elements
			$('.video_file_div').toggle(isLMS);

			// Shipping: physical only
			$('#shipping_collapse_btn_div').toggleClass('d-none', !isPhysical);

			// Variants + Country: physical OR downloadable (not LMS)
			$('#add_variants_div, #country_location_div').toggleClass('d-none', isLMS);

			if(isLMS || isDigital) {
				if(isLMS) { $('.downloadable_file_div').hide(); }
				else       { $('.downloadable_file_div').show(); }
				$('.allow_shipping-option').hide();
				$('input[name="allow_shipping"]').parent().addClass('off');
				$('input[name="allow_shipping"]').val(0).change();
				$('input[name="allow_shipping"]').attr("disabled","disabled");
				$("#allow_shipping_div,#allow_upload_file_div").addClass('d-none');
			} else {
				$('.downloadable_file_div').hide();
				$('.allow_shipping-option').show();
				$('input[name="allow_shipping"]').removeAttr("disabled");
				$("#allow_shipping_div,#allow_upload_file_div").removeClass('d-none');
			}
		});

			$('.update_product_settings').on('change', function(){
				var checked = $(this).prop('checked');
				var setting_key = $(this).data('setting_key');
				var product_id = $(this).data('product_id');

				if (checked == true) {
					var status = 1;
				}else{
					var status = 0;
				}

				$.ajax({
					url:'<?= base_url("admincontrol/update_product_settings") ?>',
					type:'POST',
					dataType:'json',
					data:{'action':'update_all_settings', status:status, setting_key:setting_key, product_id:product_id},
					success:function(json){
					},
				})
			});
var totalSection = 0;
var totalSectionlink = 0;
$(document).ready(function() {
    totalSection = $("#priview-table-video").find(".lms-section-card, fieldset").length;
    totalSectionlink = $("#priview-table-video-link").find(".lms-section-card, fieldset").length;

    // Function to add new sections
    $("#add_section").on("click", function(e) {
        e.preventDefault();
        var secNum = totalSection + 1;
        var html = `<div class="lms-section-card">
                    <div class="lms-section-header">
                        <span class="lms-section-badge">` + secNum + `</span>
                        <input type="text" class="lms-section-title-input form-control" name="section[` + totalSection + `]" placeholder="<?= __('admin.lms_section_title_placeholder') ?>">
                        <label class="btn btn-outline-primary btn-sm text-nowrap ms-auto">
                            <i class="bi bi-upload me-1"></i><?= __('admin.upload_new_video') ?>
                            <input type="file" class="videoFileUploadIP" name="video_files[` + totalSection + `][]" multiple="multiple" data-value="` + totalSection + `" style="display:none;">
                        </label>
                        <button class="btn btn-outline-danger btn-sm remove-section" type="button">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="lms-section-body">
                    <table class="table table-sm table-hover videofile-preview mb-0" id="videofile-preview` + totalSection + `">
                        <thead class="table-light">
                            <tr>
                                <th><?= __('admin.video_file') ?></th>
                                <th width="20%"><?= __('admin.title') ?></th>
                                <th width="25%"><?= __('admin.description') ?></th>
                                <th width="60px"><?= __('admin.action') ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </div>
                    </div>`;
        $("#priview-table-video").append(html);
        totalSection++;
    });
});

		$(document).on("click",".remove-section",function(){
			if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

			totalSection--;
			totalSectionlink--;
			var $section = $(this).closest('.lms-section-card');
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
			$section.remove();
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
					<td>
						<div class="d-flex align-items-center gap-2 mb-1">
							<i class="bi bi-camera-video text-primary"></i>
							<span class="fw-medium small">`+e.target.files[i].name+`</span>
							<span class="badge bg-light text-muted border ms-1"><strong>`+fileSize+`</strong></span>
						</div>
						<div class="form-check mt-2">
							<input type="checkbox" name="iszipResource[`+id+`][]" value="0" class="form-check-input isResource" id="`+rsID+`">
							<label for="resource`+rsID+`" class="form-check-label small fw-semibold">
								<i class="bi bi-paperclip me-1"></i><?= __('admin.lesson_resource') ?>
							</label>
						</div>
						<div class="resource lms-resource-box d-none" id="resource`+rsID+`">
							<input type="file" data-main="`+id+`" class="form-control form-control-sm VideoFileResource mb-2" name="VideoFileZip[`+id+`][]" accept=".zip">
							<input type="text" name="VideoFileResourceText[`+id+`][]" value="" placeholder="Resource Name" class="form-control form-control-sm">
						</div>
					</td>
					<td><input type="text" class="form-control form-control-sm" name="videotext[`+id+`][]" value="" placeholder="<?= __('admin.title') ?>"></td>
					<td><input type="text" class="form-control form-control-sm" name="description[`+id+`][]" value="" placeholder="<?= __('admin.description') ?>"></td>
					<td><button type="button" class="btn btn-outline-danger btn-sm remove-local-uploaded" data-name="`+e.target.files[i].name+`" data-main="`+id+`"><i class="bi bi-trash"></i></button></td>
					</tr>`;
					}


					console.log(video_fileArr);
					$("#priview-table-video").find("#videofile-preview"+id+" tbody").append(newRow);
				});
			});

			$(document).on("click",".remove-priview-video",function(){
				if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

				$(this).parent().parent().remove();
			});

$(document).on("click","#add_section_link",function(e){
    e.preventDefault();
    var videoLink = '<?=__("admin.video_product_link")?>';
    var secNum = totalSectionlink + 1;
    var html = `<div class="lms-section-card">
                    <div class="lms-section-header">
                        <span class="lms-section-badge">` + secNum + `</span>
                        <input type="text" class="lms-section-title-input form-control" name="sectionlink[` + totalSectionlink + `]" placeholder="<?= __('admin.lms_section_title_placeholder') ?>">
                        <button type="button" class="btn btn-outline-primary btn-sm addNewText text-nowrap ms-auto" data-value="` + totalSectionlink + `">
                            <i class="bi bi-plus-circle me-1"></i>` + videoLink + `
                        </button>
                        <button class="btn btn-outline-danger btn-sm remove-section" type="button">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="lms-section-body">
                    <table class="table table-sm table-hover videolink-preview mb-0" id="videolink-preview` + totalSectionlink + `">
                        <thead class="table-light">
                            <tr>
                                <th><?= __('admin.video_file') ?></th>
                                <th width="20%"><?= __('admin.title') ?></th>
                                <th width="25%"><?= __('admin.description') ?></th>
                                <th width="60px"><?= __('admin.action') ?></th>
                            </tr>
                        </thead>
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
					<div class="input-group input-group-sm mb-2">
						<span class="input-group-text bg-light"><i class="bi bi-link-45deg text-primary"></i></span>
						<input type="text" class="form-control" name="videolink[`+id+`][]" placeholder="<?= __('admin.video_product_link') ?>">
					</div>
					<div class="form-check mt-1">
						<input type="checkbox" name="iszipResource[`+id+`][]" value="0" class="form-check-input isResource" id="`+rsID+`">
						<label for="resource`+rsID+`" class="form-check-label small fw-semibold">
							<i class="bi bi-paperclip me-1"></i><?= __('admin.lesson_resource') ?>
						</label>
					</div>
					<div class="resource lms-resource-box d-none" id="resource`+rsID+`">
						<input type="file" data-main="`+id+`" class="form-control form-control-sm VideoFileResource mb-2" name="VideoFileZip[`+id+`][`+currentEl+`]" data-current="`+currentEl+`" accept=".zip">
						<input type="text" name="VideoFileResourceText[`+id+`][]" value="" placeholder="Resource Name" class="form-control form-control-sm">
					</div>
				</td>
				<td><input type="text" class="form-control form-control-sm" name="videotext[`+id+`][]" placeholder="<?= __('admin.title') ?>"></td>
				<td><input type="text" class="form-control form-control-sm" name="description[`+id+`][]" value="" placeholder="<?= __('admin.description') ?>"></td>
				<td><button type="button" class="btn btn-outline-danger btn-sm remove-priview-video"><i class="bi bi-trash"></i></button></td>
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
				    Swal.fire({
				        icon: 'warning',
				        text: '<?= __('admin.zip_only') ?>'
				    });
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
					alert('<?= __('admin.only_allow_zip_file') ?>');
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
					if(confirm('Are you sure ?')) {
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
			$(document).on('click',".proType",function(e){
				e.preventDefault();
				var value =$(this).data('value');
				$(".proType").removeClass('btn_active');
				$(this).addClass('btn_active');
				$(`input[name="product_type"][value="`+value+`"]`).prop('checked',true).change();
				var title = value=='downloadable' ?'<?= __('admin.downloadable_product')?>': (value=='video' ? '<?= __('admin.lms_product')?>':'');
				if(title=='')
					$("#product_type_title").parent().addClass('d-none')
				else		
					$("#product_type_title").parent().removeClass('d-none')		
				$("#product_type_title").html(title)
			});
			// for Update  REs
		</script>
