<div class="container-fluid">
<div class="row">
<div class="col-12">

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-images me-2"></i>
                <h5 class="mb-0 fw-semibold"><?= __('admin.product_images_management') ?></h5>
            </div>
            <a href="<?= base_url('admincontrol/listproduct') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i><?= __('admin.back_to_products') ?>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        
        <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
            <div class="row g-0">
                <!-- Existing Images Section -->
                <div class="col-lg-8">
                    <div class="p-4 border-end">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-0 fw-semibold text-dark">
                                <i class="bi bi-collection me-2"></i><?= __('admin.all_product_images') ?>
                            </h6>
                            <span class="badge bg-info"><?= count($imageslist) ?> <?= __('admin.images') ?></span>
                        </div>
                        
                        <?php if (empty($imageslist)) { ?>
                            <div class="text-center py-5">
                                <i class="bi bi-image display-1 text-muted mb-3"></i>
                                <h5 class="text-muted mb-2"><?= __('admin.no_images_uploaded') ?></h5>
                                <p class="text-muted"><?= __('admin.upload_images_to_showcase_product') ?></p>
                            </div>
                        <?php } else { ?>
                            <div class="row g-3">
                                <?php foreach ($imageslist as $images) { ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="image-card position-relative">
                                            <div class="image-wrapper rounded overflow-hidden shadow-sm">
                                                <a href="<?= strpos($images['product_media_upload_path'], 'http://') === 0 || strpos($images['product_media_upload_path'], 'https://') === 0 ? $images['product_media_upload_path'] : base_url('assets/images/product/upload/thumb/' . $images['product_media_upload_path']); ?>" 
                                                   class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                                                   onclick="showImageModal(this.href)">
                                                    <img class="img-fluid" 
                                                         style="width: 100%; height: 200px; object-fit: cover;" 
                                                         src="<?= strpos($images['product_media_upload_path'], 'http://') === 0 || strpos($images['product_media_upload_path'], 'https://') === 0 ? $images['product_media_upload_path'] : base_url('assets/images/product/upload/thumb/' . $images['product_media_upload_path']); ?>" 
                                                         alt="Product Image">
                                                    <div class="image-overlay">
                                                        <i class="bi bi-eye text-white"></i>
                                                    </div>
                                                </a>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle delete-image-btn" 
                                                    onclick="delete_image(<?= $images['product_media_upload_id']; ?>)" 
                                                    title="<?= __('admin.delete_image') ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <!-- Upload New Images Section -->
                <div class="col-lg-4">
                    <div class="p-4">
                        <h6 class="mb-4 fw-semibold text-dark">
                            <i class="bi bi-cloud-upload me-2"></i><?= __('admin.upload_new_images') ?>
                        </h6>
                        
                        <!-- File Upload Section -->
                        <div class="upload-section mb-4">
                            <div class="border-2 border-dashed border-primary rounded-3 p-4 text-center upload-area">
                                <i class="bi bi-cloud-upload display-6 text-primary mb-3"></i>
                                <h6 class="text-dark mb-2"><?= __('admin.drag_drop_images') ?></h6>
                                <p class="text-muted small mb-3"><?= __('admin.or_click_to_browse') ?></p>
                                
                                <div class="d-grid gap-2">
                                    <label for="gallery-photo-add" class="btn btn-primary">
                                        <i class="bi bi-folder2-open me-2"></i><?= __('admin.choose_files') ?>
                                    </label>
                                    <input id="gallery-photo-add" name="product_multiple_image[]" 
                                           class="d-none" type="file" multiple accept="image/*">
                                    
                                    <?php if (!empty($s3_setting['s3_bucket_name'])) { ?>
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="prepareS3ModalDownloadbale('input[name=\'product_multiple_image_s3[]\']', '.fileUpload-gallery','multipleProduct')">
                                            <i class="bi bi-cloud me-2"></i><?= __('admin.browse_amazon_s3') ?>
                                        </button>
                                    <?php } ?>
                                </div>
                                
                                <!-- Hidden S3 inputs -->
                                <input name="s3_storage[s3_bucket_name]" value="<?= $s3_setting['s3_bucket_name']; ?>" type="hidden">
                                <input name="s3_storage[s3_region]" value="<?= $s3_setting['s3_region']; ?>" type="hidden">
                                <input type="hidden" name="product_multiple_image_s3[]" value="">
                            </div>
                        </div>
                        
                        <!-- Preview Section -->
                        <div class="preview-section mb-4">
                            <div class="fileUpload-gallery"></div>
                            <div class="fileUpload-gallery-s3"></div>
                        </div>
                        
                        <!-- Upload Guidelines -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-info-circle me-2"></i><?= __('admin.upload_guidelines') ?>
                            </h6>
                            <ul class="mb-0 small">
                                <li><?= __('admin.max_file_size_5mb') ?></li>
                                <li><?= __('admin.supported_formats_jpg_png_gif') ?></li>
                                <li><?= __('admin.recommended_size_800x600') ?></li>
                                <li><?= __('admin.multiple_images_allowed') ?></li>
                            </ul>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button class="btn btn-success btn-lg" type="submit">
                                <i class="bi bi-upload me-2"></i><?= __('admin.upload_images') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

</div>
</div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.product_image') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" class="img-fluid rounded" src="" alt="Product Image">
            </div>
        </div>
    </div>
</div>

<style>
/* Product Upload Page Styles */
.image-card {
    transition: transform 0.2s ease;
}

.image-card:hover {
    transform: translateY(-2px);
}

.image-wrapper {
    position: relative;
    overflow: hidden;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-wrapper:hover .image-overlay {
    opacity: 1;
}

.delete-image-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.delete-image-btn:hover {
    opacity: 1;
}

.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: var(--bs-primary) !important;
    background-color: rgba(13, 110, 253, 0.05);
}

.fileUpload-gallery img,
.fileUpload-gallery-s3 img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.375rem;
    border: 2px solid #dee2e6;
    margin: 0.25rem;
    transition: transform 0.2s ease;
}

.fileUpload-gallery img:hover,
.fileUpload-gallery-s3 img:hover {
    transform: scale(1.05);
    border-color: var(--bs-primary);
}
</style>

<script>
// Product upload page JavaScript - completely isolated using vanilla JavaScript
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Image preview functionality - avoid jQuery conflicts
    function imagesPreview(input, placeToInsertImagePreview) {
        var container = document.querySelector(placeToInsertImagePreview);
        if (container) {
            container.innerHTML = ''; // Clear existing images
        }
        
        if (input.files && container) {
            var filesAmount = input.files.length;
            for (var i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var img = document.createElement('img');
                    img.src = event.target.result;
                    container.appendChild(img);
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    }

    // File input change handler
    var fileInput = document.getElementById('gallery-photo-add');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            imagesPreview(this, '.fileUpload-gallery');
        });
    }

    // Image modal functionality - avoid jQuery conflicts
    window.showImageModal = function(imageSrc) {
        event.preventDefault();
        var modalImage = document.getElementById('modalImage');
        if (modalImage) {
            modalImage.src = imageSrc;
        }
    };

    // Delete image functionality - avoid jQuery conflicts
    window.delete_image = function(id) {
        if (confirm('<?= __('admin.do_you_want_to_delete_this_image') ?>')) {
            // Use vanilla JavaScript XMLHttpRequest to avoid jQuery conflicts
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo base_url('admincontrol/delete_image');?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.success') ?>', '<?= __('admin.image_deleted_successfully') ?>', 'success', 3000);
                            setTimeout(function() { location.reload(); }, 1000);
                        } else {
                            location.reload();
                        }
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_delete_image') ?>', 'error', 5000);
                        } else {
                            alert('<?= __('admin.failed_to_delete_image') ?>');
                        }
                    }
                }
            };
            
            xhr.send('image_id=' + encodeURIComponent(id));
        }
    };

    // Drag and drop functionality using vanilla JavaScript
    var uploadArea = document.querySelector('.upload-area');
    var fileInputElement = document.getElementById('gallery-photo-add');
    
    if (uploadArea && fileInputElement) {
        // Click to upload
        uploadArea.addEventListener('click', function() {
            fileInputElement.click();
        });
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(eventName) {
            uploadArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(function(eventName) {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(function(eventName) {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle dropped files
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight(e) {
            uploadArea.classList.add('border-success', 'bg-light');
        }
        
        function unhighlight(e) {
            uploadArea.classList.remove('border-success', 'bg-light');
        }
        
        function handleDrop(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            fileInputElement.files = files;
            imagesPreview(fileInputElement, '.fileUpload-gallery');
        }
    }
});
</script>