<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header Section -->
            <div class="card bg-primary text-white border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-white mb-1">
                                <i class="fa fa-users me-2"></i>
                                <?= !empty($group) ? __('admin.edit_group') : __('admin.create_new_group') ?>
                            </h4>
                            <p class="text-light opacity-75 mb-0">
                                <?= !empty($group) ? __('admin.update_group_details') : __('admin.create_group_desc') ?>
                            </p>
                        </div>
                        <a class="btn btn-light" href="<?= base_url('admincontrol/usergroup/') ?>">
                            <i class="fa fa-arrow-left me-2"></i><?= __('admin.back_to_groups') ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light border-0">
                            <h6 class="fw-bold mb-0">
                                <i class="fa fa-edit me-2"></i><?= __('admin.group_information') ?>
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form id="admin-form" novalidate>
                                <input type="hidden" name="group_id" value="<?= (!empty($group) ? (int)$group->id : '') ?>">
                                
                                <!-- Group Name -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fa fa-tag me-2"></i><?= __('admin.group_name') ?>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="group_name" 
                                           class="form-control form-control-lg" 
                                           placeholder="<?= __('admin.enter_your_group_name') ?>"
                                           value="<?= !empty($group) ? htmlspecialchars($group->group_name) : ''; ?>"
                                           required>
                                    <div class="form-text"><?= __('admin.group_name_help') ?></div>
                                </div>

                                <!-- Group Description -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fa fa-file-text me-2"></i><?= __('admin.group_description') ?>
                                    </label>
                                    <textarea name="group_description" 
                                              class="form-control" 
                                              rows="5"
                                              placeholder="<?= __('admin.enter_group_description') ?>"><?= !empty($group) ? htmlspecialchars($group->group_description) : ''; ?></textarea>
                                    <div class="form-text"><?= __('admin.group_description_help') ?></div>
                                </div>

                                <!-- Group Image -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fa fa-image me-2"></i><?= __('admin.group_image') ?>
                                    </label>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <input class="form-control" 
                                                       id="uploadBtn" 
                                                       name="avatar" 
                                                       type="file"
                                                       accept="image/*">
                                                <div class="form-text"><?= __('admin.group_image_help') ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <div class="position-relative d-inline-block">
                                                    <?php $avatar = (!empty($group) && $group->avatar != '') ? 'site/'.$group->avatar : 'no_image_available.png'; ?>
                                                    <img src="<?= base_url('assets/images/' . $avatar) ?>" 
                                                         id="group_img" 
                                                         class="img-thumbnail border-3" 
                                                         width="150" 
                                                         height="150"
                                                         style="object-fit: cover;">
                                                    <div class="position-absolute top-0 start-100 translate-middle">
                                                        <span class="badge bg-primary rounded-pill">
                                                            <i class="fa fa-camera"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-muted"><?= __('admin.current_image') ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="oldfile" value="<?= !empty($group) ? $group->avatar : ''; ?>">
                                </div>

                                <!-- Submit Section -->
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div class="loading-submit d-none">
                                        <div class="d-flex align-items-center text-primary">
                                            <div class="spinner-border spinner-border-sm me-2" role="status">
                                                <span class="visually-hidden"><?= __('admin.loading') ?></span>
                                            </div>
                                            <span class="loading-text"><?= __('admin.saving') ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="ms-auto">
                                        <a href="<?= base_url('admincontrol/usergroup/') ?>" class="btn btn-outline-secondary me-2">
                                            <i class="fa fa-times me-2"></i><?= __('admin.cancel') ?>
                                        </a>
                                        <button type="button" class="btn btn-primary btn-lg btn-submit">
                                            <i class="fa fa-check me-2"></i>
                                            <?= !empty($group) ? __('admin.update_group') : __('admin.create_group') ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Success/Error Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="messageModalHeader">
                <h5 class="modal-title" id="messageModalLabel">
                    <i class="fa fa-check-circle me-2" id="messageModalIcon"></i>
                    <span id="messageModalTitle"><?= __('admin.success') ?></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="messageModalText" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fa fa-check me-1"></i><?= __('admin.ok') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const uploadBtn = document.getElementById('uploadBtn');
    const groupImg = document.getElementById('group_img');
    const submitBtn = document.querySelector('.btn-submit');
    const loadingSubmit = document.querySelector('.loading-submit');
    const form = document.getElementById('admin-form');

    uploadBtn.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            if (file.size > 5 * 1024 * 1024) {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.file_too_large') ?>', 'error', 4000);
                this.value = '';
                return;
            }

            if (!file.type.startsWith('image/')) {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.invalid_file_type') ?>', 'error', 4000);
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                groupImg.src = e.target.result;
                groupImg.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    groupImg.style.transform = 'scale(1)';
                }, 200);
            };
            reader.readAsDataURL(file);
        }
    });

    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        clearErrors();
        
        if (!validateForm()) {
            return;
        }

        const formData = new FormData(form);
        
        setLoadingState(true);

        fetch('<?= base_url('admincontrol/admin_group_form') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            setLoadingState(false);
            
            if (result.location) {
                showToast('<?= __('admin.success') ?>', '<?= __('admin.group_saved_successfully') ?>', 'success', 2000);
                setTimeout(() => {
                    window.location = result.location;
                }, 1000);
            } else if (result.errors) {
                displayErrors(result.errors);
            } else if (result.error) {
                showToast('<?= __('admin.error') ?>', result.error, 'error', 4000);
            }
        })
        .catch(error => {
            setLoadingState(false);
            showMessageModal('error', '<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>');
        });
    });

    function validateForm() {
        const groupName = form.querySelector('[name="group_name"]');
        
        if (!groupName.value.trim()) {
            showFieldError(groupName, '<?= __('admin.group_name_required') ?>');
            groupName.focus();
            return false;
        }

        if (groupName.value.trim().length < 2) {
            showFieldError(groupName, '<?= __('admin.group_name_too_short') ?>');
            groupName.focus();
            return false;
        }

        return true;
    }

    function setLoadingState(loading) {
        if (loading) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i><?= __('admin.saving') ?>...';
            loadingSubmit.classList.remove('d-none');
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa fa-check me-2"></i><?= !empty($group) ? __('admin.update_group') : __('admin.create_group') ?>';
            loadingSubmit.classList.add('d-none');
        }
    }

    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        
        field.parentNode.appendChild(feedback);
    }

    function displayErrors(errors) {
        Object.entries(errors).forEach(([fieldName, message]) => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = message;
                const plainText = tempDiv.textContent || tempDiv.innerText;
                showFieldError(field, plainText);
            }
        });

        const firstError = form.querySelector('.is-invalid');
        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function showMessageModal(type, title, message) {
        const modal = document.getElementById('messageModal');
        const header = document.getElementById('messageModalHeader');
        const icon = document.getElementById('messageModalIcon');
        const titleElement = document.getElementById('messageModalTitle');
        const textElement = document.getElementById('messageModalText');
        
        // Reset classes
        header.className = 'modal-header';
        icon.className = 'fa me-2';
        
        if (type === 'success') {
            header.classList.add('bg-success', 'text-white');
            icon.classList.add('fa-check-circle');
        } else if (type === 'error') {
            header.classList.add('bg-danger', 'text-white');
            icon.classList.add('fa-exclamation-triangle');
        } else if (type === 'warning') {
            header.classList.add('bg-warning', 'text-dark');
            icon.classList.add('fa-warning');
        } else {
            header.classList.add('bg-info', 'text-white');
            icon.classList.add('fa-info-circle');
        }
        
        titleElement.textContent = title;
        textElement.textContent = message;
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
});
</script>
