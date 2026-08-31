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
							<i class="fas fa-<?= (int)$tutorial['id'] == 0 ? 'file-medical' : 'file-edit' ?> me-3 fs-3"></i>
							<div>
								<h4 class="mb-1 fw-bold"><?= (int)$tutorial['id'] == 0 ? __('admin.add_tutorial') : __('admin.edit_tutorial') ?></h4>
								<p class="mb-0 opacity-75 small"><?= __('admin.manage_tutorial_page_details') ?></p>
							</div>
						</div>
						<a href="<?= base_url('admincontrol/tutorial') ?>" class="btn btn-light btn-sm">
							<i class="fas fa-arrow-left me-2"></i><?= __('admin.back') ?>
						</a>
					</div>
				</div>
				<div class="card-body p-4">
					<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="form_form">
						<input type="hidden" id="id" name="id" value="<?php echo $tutorial['id'] ?>">
						
						<div class="row g-4">
							<div class="col-lg-6">
								<label class="form-label fw-semibold">
									<i class="fas fa-language me-2 text-primary"></i><?= __('admin.select_language') ?>
									<span class="text-danger">*</span>
								</label>
								<select class="form-select" name="language_id" id="drpLanguage" onchange="return changeLanguage();" required>
									<?php 
									if(isset($languages))
									{
										$language_id=1;
										foreach($languages as $language)
										{?>
											<option <?= $tutorial['language_id']==$language['id'] ? 'selected' : '' ?> value="<?= $language['id'] ?>"><?= $language['name'] ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
							<div class="col-lg-6">
								<label class="form-label fw-semibold">
									<i class="fas fa-heading me-2 text-primary"></i><?= __('admin.page_title') ?>
									<span class="text-danger">*</span>
								</label>
								<input placeholder="<?= __('admin.enter_page_title') ?>" 
									   name="title" 
									   value="<?php echo $tutorial['title']; ?>" 
									   class="form-control" 
									   type="text" 
									   required>
								<div class="form-text"><?= __('admin.enter_descriptive_page_title') ?></div>
							</div>
						</div>
						
						<div class="row g-4 mt-2">
							<div class="col-lg-6">
								<label class="form-label fw-semibold">
									<i class="fas fa-folder me-2 text-primary"></i><?= __('admin.category') ?>
									<span class="text-danger">*</span>
								</label>
								<div id="category_dropdown">
									<select name="category_id" id="category_id" class="form-select" required>
										<option value=""><?= __('admin.select_category') ?></option>
									</select>
								</div>
								<div class="form-text"><?= __('admin.select_tutorial_category') ?></div>
							</div>
							<div class="col-lg-6">
								<label class="form-label fw-semibold">
									<i class="fas fa-toggle-on me-2 text-primary"></i><?= __('admin.status') ?>
									<span class="text-danger">*</span>
								</label>
								<select class="form-select" name="status" id="drpStatus" required>
									<option value="1" <?= $tutorial['status']==1 ? 'selected' : ''?>>
										<i class="fas fa-check-circle"></i> <?= __('admin.active') ?>
									</option>
									<option value="0" <?= $tutorial['status']==0 ? 'selected' : ''?>>
										<i class="fas fa-times-circle"></i> <?= __('admin.inactive') ?>
									</option>
								</select>
								<div class="form-text"><?= __('admin.set_tutorial_status') ?></div>
							</div>
						</div>
						
						<div class="row mt-4">
							<div class="col-12">
								<label class="form-label fw-semibold">
									<i class="fas fa-file-alt me-2 text-primary"></i><?= __('admin.page_content') ?>
									<span class="text-danger">*</span>
								</label>
								<textarea name="content" id="content" class="form-control summernote-img" rows="15" required><?php echo $tutorial['content']; ?></textarea>
								<div class="form-text"><?= __('admin.enter_tutorial_content') ?></div>
							</div>
						</div>
						
						<div class="row mt-4">
							<div class="col-12">
								<div class="d-flex justify-content-between align-items-center pt-4 border-top">
									<a href="<?= base_url('admincontrol/tutorial') ?>" class="btn btn-outline-secondary">
										<i class="fas fa-times me-2"></i><?= __('admin.cancel') ?>
									</a>
									<button type="submit" class="btn btn-success" name="save">
										<i class="fas fa-save me-2"></i><?= __('admin.save_tutorial') ?>
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
	$selected_category=0;
	<?php if(isset($tutorial)) { ?>
		$selected_category='<?=$tutorial['category_id']?>';
	<?php } ?>

	function changeLanguage() {
		$this = $(this);
		$.ajax({
			url: '<?= base_url("admincontrol/getTutorialCategory") ?>',
			type: 'POST',
			dataType: 'json',
			data: { language_id: $("#drpLanguage").val() },
			beforeSend: function() { 
				$("#category_dropdown").html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden"><?= __('admin.loading') ?>...</span></div></div>');
			},
			success: function(json) {
				$("#category_dropdown").html(json.html);

				if($selected_category!=0) {
					$("#category_id").val($selected_category);
					$selected_category=0;
				} else {
					$("#category_id").val('');
				}
			},
		});
		return false;
	}

	$(document).ready(function() {
		changeLanguage();
		
		$("#form_form").on('submit', function(evt) {
		    evt.preventDefault();
		    $btn = $("button[name='save']");
		    var formData = new FormData($("#form_form")[0]);
		    formData.append("action", $btn.attr("name"));
		    $this = $("#form_form");

		    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><?= __('admin.saving') ?>...');
		    
		    $.ajax({
		        url: '<?= base_url('admincontrol/manage_tutorial') ?>',
		        type: 'POST',
		        dataType: 'json',
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: formData,
		        error: function() { 
		            $btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __('admin.save_tutorial') ?>');
		            showToast('error', '<?= __('admin.error_occurred') ?>');
		        },
		        success: function(result) {
		            $btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __('admin.save_tutorial') ?>');
		            $this.find(".has-error").removeClass("has-error");
		            $this.find(".is-invalid").removeClass("is-invalid");
		            $this.find("span.text-danger").remove();
		            $this.find(".invalid-feedback").remove();

		            if(result['location']) {
		                showToast('success', '<?= __('admin.tutorial_saved_successfully') ?>');
		                setTimeout(function() {
		                    window.location = result['location'];
		                }, 1500);
		            }

		            if(result['success']) {
		                showToast('success', result['success']);
		            }

		            if(result['errors']) {
		                $.each(result['errors'], function(i, j) {
		                    $ele = $this.find('[name="'+ i +'"]');
		                    if($ele) {
		                    	if(i != 'content'){
		                        	$ele.addClass("is-invalid");
		                        	$ele.after("<div class='invalid-feedback d-block'>"+ j +"</div>");
		                    	} else {
		                    		$ele.addClass("is-invalid");
		                        	$('.note-editor').after("<div class='invalid-feedback d-block'>"+ j +"</div>");
		                    	}
		                    }
		                });
		            }
		        },
		    });
		    return false;
		});
	});
</script>
