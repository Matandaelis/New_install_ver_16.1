<div class="container-fluid store-category-add-page">
<div class="row">
<div class="col-12">

<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="category-form">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-tag-fill me-2"></i>
                    <h5 class="mb-0 fw-semibold">
                        <?= isset($category['id']) && $category['id'] ? __('admin.edit_category') : __('admin.add_category') ?>
                    </h5>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('admincontrol/store_category') ?>" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i><?= __('admin.back') ?>
                    </a>
                    <button type="submit" class="btn-submit btn btn-light btn-sm">
                        <i class="bi bi-check-circle me-1"></i><?= __('admin.save') ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <input type="hidden" name="category_id" value="<?= isset($category['id']) ? $category['id'] : '' ?>">
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium">
                                <i class="bi bi-tag me-1"></i><?= __('admin.category_name') ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?= isset($category['name']) ? $category['name'] : '' ?>"
                                   placeholder="<?= __('admin.enter_category_name') ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">
                                <i class="bi bi-diagram-3 me-1"></i><?= __('admin.parent_category') ?>
                            </label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- <?= __('admin.none') ?> (<?= __('admin.root_category') ?>) --</option>
                                <?php foreach ($categories as $key => $value) { ?>
                                    <option value="<?= $value['id'] ?>" <?php echo (isset($category['parent_id']) && $category['parent_id'] == $value['id']) ? 'selected' : ''; ?>>
                                        <?= $value['name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="form-text"><?= __('admin.select_parent_category_help') ?></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">
                                <i class="bi bi-text-paragraph me-1"></i><?= __('admin.description') ?>
                                <span class="text-danger">*</span>
                            </label>
                            <textarea data-height='300' class="form-control summernote-img" name="description" rows="6"
                                      placeholder="<?= __('admin.enter_category_description') ?>"><?= isset($category['description']) ? $category['description'] : '' ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="bi bi-palette me-1"></i><?= __('admin.heading_text_color') ?>
                            </label>
                            <input name="color" value="<?= isset($category['color']) ? $category['color'] : '#000000' ?>" 
                                   class="form-control jscolor" data-jscolor type="text">
                            <div class="form-text"><?= __('admin.color_picker_help') ?></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="bi bi-tags me-1"></i><?= __('admin.display_as_tag') ?>
                            </label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tag" id="tag_enable" value="1" 
                                           <?php if(!isset($category['tag']) || $category['tag'] == 1){ ?>checked<?php } ?>>
                                    <label class="form-check-label" for="tag_enable">
                                        <i class="bi bi-check-circle text-success me-1"></i><?= __('admin.enable') ?>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tag" id="tag_disable" value="0" 
                                           <?php if(isset($category['tag']) && $category['tag'] == 0){ ?>checked<?php } ?>>
                                    <label class="form-check-label" for="tag_disable">
                                        <i class="bi bi-x-circle text-danger me-1"></i><?= __('admin.disable') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-text"><?= __('admin.display_as_tag_help') ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium">
                                <i class="bi bi-image me-1"></i><?= __('admin.category_image') ?>
                                <span class="badge bg-secondary ms-1 fw-normal" style="font-size:.7rem"><?= __('admin.optional') ?></span>
                            </label>
                            <div class="text-center">
                                <?php $category_image = (!empty($category['image'])) ? 'assets/images/product/upload/thumb/' . $category['image'] : 'assets/images/no_image_available.png'; ?>
                                <img src="<?= base_url($category_image) ?>" id="featureImage"
                                     class="img-thumbnail mb-3" style="max-width:200px;max-height:200px;object-fit:cover;"
                                     onerror="this.onerror=null;this.src='<?= base_url('assets/images/no_image_available.png') ?>'">
                                <div>
                                    <label for="category_image" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-cloud-upload me-1"></i><?= __('admin.choose_file') ?>
                                    </label>
                                    <input id="category_image" name="category_image" class="d-none" type="file" accept="image/jpeg,image/png,image/gif">
                                </div>
                                <div class="form-text mt-2"><?= __('admin.image_upload_help') ?></div>
                                <div id="category_image_error" class="text-danger small mt-1 d-none"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">
                                <i class="bi bi-image-fill me-1"></i><?= __('admin.background_image') ?>
                                <span class="badge bg-secondary ms-1 fw-normal" style="font-size:.7rem"><?= __('admin.optional') ?></span>
                            </label>
                            <div class="text-center">
                                <?php $category_background_image = (!empty($category['background_image'])) ? 'assets/images/product/upload/thumb/' . $category['background_image'] : 'assets/images/no_image_available.png'; ?>
                                <img src="<?= base_url($category_background_image) ?>" id="featureBackgroundImage"
                                     class="img-thumbnail mb-3" style="max-width:200px;max-height:200px;object-fit:cover;"
                                     onerror="this.onerror=null;this.src='<?= base_url('assets/images/no_image_available.png') ?>'">
                                <div>
                                    <label for="category_background_image" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-cloud-upload me-1"></i><?= __('admin.choose_file') ?>
                                    </label>
                                    <input id="category_background_image" name="category_background_image" class="d-none" type="file" accept="image/jpeg,image/png,image/gif">
                                </div>
                                <div class="form-text mt-2"><?= __('admin.background_image_help') ?></div>
                                <div id="category_background_image_error" class="text-danger small mt-1 d-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

</div>
</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Image preview functionality
    function readURL(input, targetId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $(targetId).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Category image preview
    document.getElementById("category_image").onchange = function() { 
        readURL(this, '#featureImage'); 
    };

    // Background image preview
    document.getElementById("category_background_image").onchange = function() { 
        readURL(this, '#featureBackgroundImage'); 
    };

    // Form submission
    $(".btn-submit").on('click', function(evt) {
        evt.preventDefault();
        
        const formData = new FormData($("#category-form")[0]);
        const $this = $("#category-form");
        const $btn = $(this);
        
        // Clear previous errors
        $this.find(".is-invalid").removeClass("is-invalid");
        $this.find(".invalid-feedback").remove();
        $('#category_image_error, #category_background_image_error').addClass('d-none').text('');
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: function() {
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.saving') ?>...');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i><?= __('admin.save') ?>');
            },
            success: function(result) {
                if (result['location']) { 
                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', '<?= __('admin.category_saved_successfully') ?>', 'success', 2000);
                    }
                    
                    // Redirect after short delay
                    setTimeout(function() {
                        window.location = result['location'];
                    }, 1000);
                }

                if (result['errors']) {
                    // Hide all image error divs first
                    $('#category_image_error, #category_background_image_error').addClass('d-none').text('');

                    $.each(result['errors'], function(fieldName, errorMessage) {
                        // File inputs are hidden — show in dedicated error divs
                        const $errDiv = $('#' + fieldName + '_error');
                        if ($errDiv.length) {
                            $errDiv.removeClass('d-none').text(errorMessage);
                        } else {
                            const $field = $this.find('[name="' + fieldName + '"]');
                            if ($field.length) {
                                $field.addClass("is-invalid");
                                $field.closest('.mb-3, .col-12').find('.invalid-feedback').remove();
                                $field.after('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                            }
                        }
                    });

                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', '<?= __('admin.please_fix_errors') ?>', 'error', 5000);
                    }

                    const firstVisible = $this.find('.is-invalid, .text-danger:not(.d-none)').first();
                    if (firstVisible.length) {
                        $('html, body').animate({ scrollTop: firstVisible.offset().top - 100 }, 500);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Form submission error:', error);
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.form_submission_failed') ?>', 'error', 5000);
                }
            }
        });
        
        return false;
    });

    // Initialize Summernote if available
    if (typeof $.fn.summernote !== 'undefined') {
        $('.summernote-img').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    }

    // Initialize color picker if available
    if (typeof jscolor !== 'undefined') {
        jscolor.install();
    }
});
</script>
				