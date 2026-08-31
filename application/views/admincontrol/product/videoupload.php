<div class="container-fluid">
<div class="row">
<div class="col-12">

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-camera-video me-2"></i>
                <h5 class="mb-0 fw-semibold"><?= __('admin.product_videos_management') ?></h5>
            </div>
            <a href="<?= base_url('admincontrol/listproduct') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i><?= __('admin.back_to_products') ?>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        
        <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
            <div class="row g-0">
                <!-- Existing Videos Section -->
                <div class="col-lg-8">
                    <div class="p-4 border-end">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-0 fw-semibold text-dark">
                                <i class="bi bi-collection-play me-2"></i><?= __('admin.all_videos_images') ?>
                            </h6>
                            <span class="badge bg-info"><?= count($videoimageslist) ?> <?= __('admin.videos') ?></span>
                        </div>
                        
                        <?php if (empty($videoimageslist)) { ?>
                            <div class="text-center py-5">
                                <i class="bi bi-camera-video display-1 text-muted mb-3"></i>
                                <h5 class="text-muted mb-2"><?= __('admin.no_videos_uploaded') ?></h5>
                                <p class="text-muted"><?= __('admin.add_videos_to_showcase_product') ?></p>
                            </div>
                        <?php } else { ?>
                            <div class="row g-3">
                                <?php foreach($videoimageslist as $images) { 
						if(empty($images['product_media_upload_video_image']) || $images['product_media_upload_video_image'] == 'no-image.jpg') {
							$product_media_upload_video_image = base_url('assets/template/images/no_image_yet.png');
						} else {
							$product_media_upload_video_image = base_url('assets/images/product/upload/thumb/'.$images['product_media_upload_video_image']);
						}
					 ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="video-card position-relative">
                                            <div class="video-wrapper rounded overflow-hidden shadow-sm">
                                                <div class="position-relative">
                                                    <img class="img-fluid" 
                                                         style="width: 100%; height: 200px; object-fit: cover;" 
                                                         src="<?= $product_media_upload_video_image ?>" 
                                                         alt="Video Thumbnail">
                                                    <div class="video-overlay">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="bi bi-play-circle-fill text-white display-6 mb-2"></i>
                                                            <small class="text-white"><?= __('admin.video_thumbnail') ?></small>
                                                        </div>
								</div>
                                                    <?php if (!empty($images['product_media_upload_path'])) { ?>
                                                        <a href="<?= $images['product_media_upload_path'] ?>" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-primary position-absolute bottom-0 start-0 m-2">
                                                            <i class="bi bi-play me-1"></i><?= __('admin.watch') ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle delete-video-btn" 
                                                    onclick="delete_image(<?= $images['product_media_upload_id']; ?>)" 
                                                    title="<?= __('admin.delete_video') ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>
						</div>
					<?php } ?>
				</div>
			</div> 
                
                <!-- Upload New Video Section -->
                <div class="col-lg-4">
                    <div class="p-4">
                        <h6 class="mb-4 fw-semibold text-dark">
                            <i class="bi bi-cloud-upload me-2"></i><?= __('admin.add_new_video') ?>
                        </h6>
                        
                        <!-- Video URL Section -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-link-45deg me-2"></i><?= __('admin.video_url') ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-youtube text-danger"></i>
                                </span>
                                <input type="text" 
                                       name="product_media_upload_path" 
                                       id="product_media_upload_path" 
                                       class="form-control" 
                                       placeholder="<?= __('admin.enter_youtube_vimeo_url') ?>"
                                       value="">
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                <?= __('admin.supported_platforms') ?>: YouTube, Vimeo, Dailymotion
                            </div>
                        </div>
                        
                        <!-- Video Thumbnail Section -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-image me-2"></i><?= __('admin.video_thumbnail') ?>
                            </label>
                            <div class="upload-thumbnail-area border-2 border-dashed border-secondary rounded-3 p-3 text-center">
                                <div class="thumbnail-preview mb-3">
                                    <img id="thumbnailPreview" 
                                         src="<?= base_url('assets/template/images/no_image_yet.png'); ?>" 
                                         class="img-fluid rounded shadow-sm" 
                                         style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                </div>
                                
                                <div class="d-grid">
                                    <label for="video_thumbnail_image" class="btn btn-outline-primary">
                                        <i class="bi bi-camera me-2"></i><?= __('admin.choose_thumbnail') ?>
                                    </label>
                                    <input id="video_thumbnail_image" 
                                           name="video_thumbnail_image" 
                                           class="d-none" 
                                           type="file" 
                                           accept="image/*"
                                           onchange="readVideoThumbnail(this, '#thumbnailPreview')">
                                </div>
                            </div>
                            <div class="form-text">
                                <?= __('admin.recommended_thumbnail_size') ?>: 1280x720px (16:9 ratio)
                            </div>
                        </div>
                        
                        <!-- Video Guidelines -->
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading">
                                <i class="bi bi-info-circle me-2"></i><?= __('admin.video_guidelines') ?>
                            </h6>
                            <ul class="mb-0 small">
                                <li><?= __('admin.use_public_video_urls') ?></li>
                                <li><?= __('admin.thumbnail_helps_engagement') ?></li>
                                <li><?= __('admin.test_video_link_before_submit') ?></li>
                                <li><?= __('admin.hd_quality_recommended') ?></li>
                            </ul>
		</div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button class="btn btn-success btn-lg" type="submit">
                                <i class="bi bi-plus-circle me-2"></i><?= __('admin.add_video') ?>
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

<style>
/* Video Upload Page Styles */
.video-card {
    transition: transform 0.2s ease;
}

.video-card:hover {
    transform: translateY(-2px);
}

.video-wrapper {
    position: relative;
    overflow: hidden;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.video-wrapper:hover .video-overlay {
    opacity: 1;
}

.delete-video-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.delete-video-btn:hover {
    opacity: 1;
}

.upload-thumbnail-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-thumbnail-area:hover {
    border-color: var(--bs-primary) !important;
    background-color: rgba(13, 110, 253, 0.05);
}

.thumbnail-preview img {
    transition: transform 0.2s ease;
}

.thumbnail-preview:hover img {
    transform: scale(1.02);
}

/* Video URL input styling */
.input-group .input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

#product_media_upload_path:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Video platform icons */
.bi-youtube {
    color: #ff0000 !important;
}
</style>

<script>
// Video upload page JavaScript - completely isolated
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Thumbnail preview functionality - avoid jQuery conflicts
    window.readVideoThumbnail = function(input, target) {
        if (input && input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var targetElement = document.querySelector(target);
                if (targetElement) {
                    targetElement.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Video URL validation using vanilla JavaScript
    var videoUrlInput = document.getElementById('product_media_upload_path');
    if (videoUrlInput) {
        videoUrlInput.addEventListener('input', function() {
            var url = this.value;
            var isValid = isValidVideoUrl(url);
            
            if (url && !isValid) {
                this.classList.add('is-invalid');
                var feedback = this.nextElementSibling;
                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                    var div = document.createElement('div');
                    div.className = 'invalid-feedback';
                    div.textContent = '<?= __('admin.please_enter_valid_video_url') ?>';
                    this.parentNode.insertBefore(div, this.nextSibling);
                }
            } else {
                this.classList.remove('is-invalid');
                var feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.remove();
					}
				}
			});
		}
		
    function isValidVideoUrl(url) {
        var videoPatterns = [
            /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/,
            /(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/,
            /(?:https?:\/\/)?(?:www\.)?dailymotion\.com\/video\/([a-zA-Z0-9]+)/
        ];
        
        return videoPatterns.some(function(pattern) {
            return pattern.test(url);
        });
    }

    // Delete video functionality - avoid jQuery conflicts
    window.delete_image = function(id) {
        if (confirm('<?= __('admin.do_you_want_to_delete_this_video') ?>')) {
            // Use vanilla JavaScript XMLHttpRequest to avoid jQuery conflicts
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo base_url('admincontrol/delete_image');?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.success') ?>', '<?= __('admin.video_deleted_successfully') ?>', 'success', 3000);
                            setTimeout(function() { location.reload(); }, 1000);
                        } else {
                            location.reload();
                        }
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_delete_video') ?>', 'error', 5000);
                        } else {
                            alert('<?= __('admin.failed_to_delete_video') ?>');
                        }
                    }
                }
            };
            
            xhr.send('image_id=' + encodeURIComponent(id));
        }
    };

    // Form validation using vanilla JavaScript
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            var videoUrl = videoUrlInput ? videoUrlInput.value : '';
            
            if (!videoUrl.trim()) {
                e.preventDefault();
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.validation_error') ?>', '<?= __('admin.please_enter_video_url') ?>', 'error', 5000);
                } else {
                    alert('<?= __('admin.please_enter_video_url') ?>');
                }
                if (videoUrlInput) videoUrlInput.focus();
                return false;
            }
            
            if (!isValidVideoUrl(videoUrl)) {
                e.preventDefault();
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.validation_error') ?>', '<?= __('admin.please_enter_valid_video_url') ?>', 'error', 5000);
                } else {
                    alert('<?= __('admin.please_enter_valid_video_url') ?>');
                }
                if (videoUrlInput) videoUrlInput.focus();
                return false;
            }
        });
    }

    // Thumbnail upload area click handler using vanilla JavaScript
    var uploadArea = document.querySelector('.upload-thumbnail-area');
    var fileInput = document.getElementById('video_thumbnail_image');
    
    if (uploadArea && fileInput) {
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });
    }
});
	</script>