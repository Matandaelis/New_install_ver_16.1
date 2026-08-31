<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header Section -->
            <div class="card bg-primary text-white border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-white mb-1">
                                <i class="fas fa-user-edit me-2"></i><?= __('admin.edit_profile') ?>
                            </h4>
                            <p class="text-light opacity-75 mb-0"><?= __('admin.update_your_profile_information') ?></p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-primary px-3 py-2">
                                <i class="fas fa-user-circle me-1"></i><?= __('admin.profile') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light border-0">
                            <h6 class="fw-bold mb-0">
                                <i class="fas fa-user-cog me-2"></i><?= __('admin.profile_information') ?>
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form id="profile-form" method="post" enctype="multipart/form-data" novalidate>
                                
                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-user me-2"></i><?= __('admin.personal_information') ?>
                                    </h6>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="firstname" class="form-label fw-bold">
                                                <i class="fas fa-user me-1 text-primary"></i><?= __('admin.first_name') ?>
                                            </label>
                                            <input id="firstname" type="text" class="form-control" 
                                                   placeholder="<?= __('admin.enter_your_first_name') ?>" 
                                                   name="firstname" value="<?= htmlspecialchars($user->firstname) ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="lastname" class="form-label fw-bold">
                                                <i class="fas fa-user me-1 text-primary"></i><?= __('admin.last_name') ?>
                                            </label>
                                            <input id="lastname" type="text" class="form-control" 
                                                   placeholder="<?= __('admin.enter_your_last_name') ?>" 
                                                   name="lastname" value="<?= htmlspecialchars($user->lastname) ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="username" class="form-label fw-bold">
                                                <i class="fas fa-at me-1 text-primary"></i><?= __('admin.username') ?>
                                            </label>
                                            <input id="username" type="text" class="form-control bg-light" 
                                                   placeholder="<?= __('admin.username') ?>" 
                                                   name="username" value="<?= htmlspecialchars($user->username) ?>" readonly>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i><?= __('admin.username_cannot_be_changed') ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label fw-bold">
                                                <i class="fas fa-envelope me-1 text-primary"></i><?= __('admin.your_email') ?>
                                            </label>
                                            <input id="email" type="email" class="form-control" 
                                                   placeholder="<?= __('admin.enter_your_email_address') ?>" 
                                                   name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-phone me-2"></i><?= __('admin.contact_information') ?>
                                    </h6>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="phonenumber" class="form-label fw-bold">
                                                <i class="fas fa-mobile-alt me-1 text-primary"></i><?= __('admin.phone_number') ?>
                                            </label>
                                            <input id="phonenumber" type="tel" class="form-control" 
                                                   placeholder="<?= __('admin.enter_your_mobile_number') ?>" 
                                                   name="PhoneNumber" value="<?= htmlspecialchars($user->PhoneNumber) ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Country" class="form-label fw-bold">
                                                <i class="fas fa-flag me-1 text-primary"></i><?= __('admin.country') ?>
                                            </label>
                                            <select id="Country" class="form-select" name="Country">
                                                <option value=""><?= __('admin.select_country') ?></option>
                                                <?php foreach ($country as $countries): ?>
                                                <option value="<?= $countries->id; ?>" <?= !empty($user->Country) && $user->Country == $countries->id ? 'selected' : ''; ?>><?= htmlspecialchars($countries->name); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="City" class="form-label fw-bold">
                                                <i class="fas fa-city me-1 text-primary"></i><?= __('admin.city') ?>
                                            </label>
                                            <input id="City" type="text" class="form-control" 
                                                   placeholder="<?= __('admin.enter_your_city') ?>" 
                                                   name="City" value="<?= htmlspecialchars($user->City) ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Zip" class="form-label fw-bold">
                                                <i class="fas fa-mail-bulk me-1 text-primary"></i><?= __('admin.pincode') ?>
                                            </label>
                                            <input id="Zip" type="text" class="form-control" 
                                                   placeholder="<?= __('admin.enter_your_pincode') ?>" 
                                                   name="Zip" value="<?= htmlspecialchars($user->Zip) ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Profile Image -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-image me-2"></i><?= __('admin.member_image') ?>
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="uploadBtn" class="form-label fw-bold">
                                                <i class="fas fa-upload me-1 text-primary"></i><?= __('admin.choose_image') ?>
                                            </label>
                                            <input id="uploadBtn" type="file" class="form-control" 
                                                   name="avatar" accept="image/*" onchange="readURL(this);">
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i><?= __('admin.image_upload_help') ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-eye me-1 text-primary"></i><?= __('admin.current_image') ?>
                                            </label>
                                            <div class="text-center">
                                                <?php $avatar = $user->avatar != '' ? 'assets/images/users/'.$user->avatar : 'assets/template/images/no_image_yet.png'; ?>
                                                <img src="<?= base_url($avatar); ?>" id="blah" 
                                                     class="img-thumbnail border-3 border-primary" 
                                                     width="150" height="150" style="object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Section -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted">
                                                <i class="fas fa-shield-alt me-1"></i><?= __('admin.profile_secure_note') ?>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                                    <i class="fas fa-undo me-1"></i><?= __('admin.reset') ?>
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-save me-1"></i><?= __('admin.update_profile') ?>
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
    </div>
</div>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('blah').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function resetForm() {
        if (confirm('<?= __('admin.confirm_reset_form') ?>')) {
            document.getElementById('profile-form').reset();
            <?php $avatar = $user->avatar != '' ? 'assets/images/users/'.$user->avatar : 'assets/template/images/no_image_yet.png'; ?>
            document.getElementById('blah').src = '<?= base_url($avatar); ?>';
        }
    }
    
    // Form validation and AJAX submission
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><?= __('admin.updating') ?>...';
        submitBtn.disabled = true;
        
        // Check if fetch is available (modern browsers)
        if (typeof fetch !== 'undefined') {
            fetch('<?= base_url('admincontrol/editProfile') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                // Try to parse as JSON regardless of content-type header
                return response.text().then(text => {
                    // Check if response looks like JSON
                    if (text.trim().startsWith('{') && text.trim().endsWith('}')) {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                        }
                    } else {
                        throw new Error('Non-JSON response: ' + text.substring(0, 100));
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    showToast('success', data.message || '<?= __('admin.profile_updated_successfully') ?>');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast('error', data.message || data.errors || '<?= __('admin.update_failed') ?>');
                }
            })
            .catch(error => {
                // Fallback to traditional form submission
                showToast('info', 'Switching to traditional form submission...');
                setTimeout(() => {
                    this.submit();
                }, 1000);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        } else {
            // Fallback for older browsers
            showToast('info', 'Using traditional form submission...');
            setTimeout(() => {
                this.submit();
            }, 500);
        }
    });
</script>