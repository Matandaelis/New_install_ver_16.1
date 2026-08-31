<?php 
$db =& get_instance();
$userdetails=$db->userdetails();
?>

<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-primary text-white py-3">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center">
							<i class="fas fa-<?= (int)$category['id'] == 0 ? 'folder-plus' : 'folder-open' ?> me-3 fs-3"></i>
							<div>
								<h4 class="mb-1 fw-bold"><?= (int)$category['id'] == 0 ? __('admin.add_tutorial_category') : __('admin.edit_tutorial_category') ?></h4>
								<p class="mb-0 opacity-75 small"><?= __('admin.manage_tutorial_category_details') ?></p>
							</div>
						</div>
						<a href="<?= base_url('admincontrol/tutorial') ?>" class="btn btn-light btn-sm">
							<i class="fas fa-arrow-left me-2"></i><?= __('admin.back') ?>
						</a>
					</div>
				</div>
				<div class="card-body p-4">
					<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="form_form">
						<input type="hidden" id="id" name="id" value="<?php echo $category['id'] ?>">
						
						<div class="row g-4">
							<div class="col-lg-6">
								<label class="form-label fw-semibold">
									<i class="fas fa-language me-2 text-primary"></i><?= __('admin.select_language') ?>
									<span class="text-danger">*</span>
								</label>
								<select class="form-select" name="language_id" id="drpLanguage" required>
									<?php 
									if(isset($languages)) {
										$language_id = 1;
										foreach($languages as $language) {
											$selected = $category['language_id'] == $language['id'] ? 'selected' : '';
											?>
											<option <?php echo $selected; ?> value="<?= $language['id'] ?>"><?= $language['name'] ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
							<div class="col-lg-6">
								<label class="form-label fw-semibold">
									<i class="fas fa-tag me-2 text-primary"></i><?= __('admin.category_name') ?>
									<span class="text-danger">*</span>
								</label>
								<input placeholder="<?= __('admin.enter_tutorial_category_name') ?>" 
									   name="name" 
									   value="<?php echo $category['name']; ?>" 
									   class="form-control" 
									   type="text" 
									   required>
								<div class="form-text"><?= __('admin.enter_descriptive_category_name') ?></div>
							</div>
						</div>
						
						<div class="row mt-4">
							<div class="col-12">
								<div class="d-flex justify-content-between align-items-center pt-4 border-top">
									<a href="<?= base_url('admincontrol/tutorial') ?>" class="btn btn-outline-secondary">
										<i class="fas fa-times me-2"></i><?= __('admin.cancel') ?>
									</a>
									<button type="submit" class="btn btn-success btn-submit" name="save">
										<i class="fas fa-save me-2"></i><?= __('admin.save_category') ?>
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
	$(document).ready(function() { 
		$("#form_form").on('submit', function(evt) {
			evt.preventDefault();
			$btn = $(".btn-submit");
			var formData = new FormData($("#form_form")[0]);
			formData.append("action", $btn.attr("name"));
			$this = $("#form_form");	       

			$btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><?= __('admin.saving') ?>...');
			
			$.ajax({
				url:'<?= base_url('admincontrol/manage_tutorial_catgory') ?>',
				type:'POST',
				dataType:'json',
				cache:false,
				contentType: false,
				processData: false,
				data:formData,
				error:function(){ 
					$btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __('admin.save_category') ?>');
					showToast('error', '<?= __('admin.error_occurred') ?>');
				},
				success:function(result){            	
					$btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __('admin.save_category') ?>');
					$this.find(".has-error").removeClass("has-error");
					$this.find("span.text-danger").remove();

					if(result['location']){
						showToast('success', '<?= __('admin.category_saved_successfully') ?>');
						setTimeout(function() {
							window.location = result['location'];
						}, 1500);
					}
					if(result['success']){
						showToast('success', result['success']);
					}
					if(result['errors']){
						$.each(result['errors'], function(i, j) {
							$ele = $this.find('[name="'+ i +'"]');
							if($ele) {
								$ele.addClass("is-invalid");
								$ele.after("<div class='invalid-feedback d-block'>"+ j +"</div>");
							}
						});
					}
				},
			});
			return false;
		});
	});
</script>
