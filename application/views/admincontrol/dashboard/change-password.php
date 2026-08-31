<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header Section -->
            <div class="card bg-warning text-dark border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">
                                <i class="fas fa-lock me-2"></i><?= __('admin.change_password') ?>
                            </h4>
                            <p class="text-dark opacity-75 mb-0"><?= __('admin.change_password_description') ?></p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-dark text-white px-3 py-2">
                                <i class="fas fa-shield-alt me-1"></i><?= __('admin.security') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light border-0">
                            <h6 class="fw-bold mb-0">
                                <i class="fas fa-key me-2"></i><?= __('admin.update_password') ?>
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form id="password-form" method="post" novalidate>
                                
                                <!-- Current Password -->
                                <div class="mb-4">
                                    <label for="old_pass" class="form-label fw-bold">
                                        <i class="fas fa-lock me-1 text-primary"></i><?= __('admin.old_password') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input id="old_pass" type="password" class="form-control" 
                                           placeholder="<?= __('admin.old_password') ?>" 
                                           name="old_pass" required>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i><?= __('admin.enter_current_password') ?>
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="fas fa-key me-1 text-success"></i><?= __('admin.new_password') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input id="password" type="password" class="form-control" 
                                           placeholder="<?= __('admin.new_password') ?>" 
                                           name="password" required>
                                    <div class="form-text">
                                        <i class="fas fa-shield-alt me-1"></i><?= __('admin.password_requirements') ?>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="conf_password" class="form-label fw-bold">
                                        <i class="fas fa-check-circle me-1 text-info"></i><?= __('admin.confirm_password') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input id="conf_password" type="password" class="form-control" 
                                           placeholder="<?= __('admin.confirm_password') ?>" 
                                           name="conf_password" required>
                                    <div class="form-text">
                                        <i class="fas fa-repeat me-1"></i><?= __('admin.retype_new_password') ?>
                                    </div>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="mb-4">
                                    <div class="password-strength">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted"><?= __('admin.password_strength') ?></small>
                                            <small id="strength-text" class="text-muted"><?= __('admin.enter_password') ?></small>
                                        </div>
                                        <div class="progress" style="height: 4px;">
                                            <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Section -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i><?= __('admin.password_security_note') ?>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="resetPasswordForm()">
                                                    <i class="fas fa-undo me-1"></i><?= __('admin.reset') ?>
                                                </button>
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fas fa-save me-1"></i><?= __('admin.update') ?>
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
    function resetPasswordForm() {
        if (confirm('<?= __('admin.confirm_reset_password_form') ?>')) {
            document.getElementById('password-form').reset();
            updatePasswordStrength('');
        }
    }
    
    function updatePasswordStrength(password) {
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        if (!password) {
            strengthBar.style.width = '0%';
            strengthBar.className = 'progress-bar';
            strengthText.textContent = '<?= __('admin.enter_password') ?>';
            return;
        }
        
        let strength = 0;
        let strengthLabel = '';
        let strengthClass = '';
        
        if (password.length >= 6) strength++;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        if (strength <= 2) {
            strengthLabel = '<?= __('admin.weak') ?>';
            strengthClass = 'bg-danger';
        } else if (strength <= 4) {
            strengthLabel = '<?= __('admin.medium') ?>';
            strengthClass = 'bg-warning';
        } else {
            strengthLabel = '<?= __('admin.strong') ?>';
            strengthClass = 'bg-success';
        }
        
        strengthBar.style.width = (strength * 16.67) + '%';
        strengthBar.className = 'progress-bar ' + strengthClass;
        strengthText.textContent = strengthLabel;
    }
    
    // Password strength checker
    document.getElementById('password').addEventListener('input', function() {
        updatePasswordStrength(this.value);
    });
    
    // Form validation and AJAX submission
    document.getElementById('password-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><?= __('admin.updating') ?>...';
        submitBtn.disabled = true;
        
        fetch('<?= base_url('admincontrol/changePassword') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            return response.text().then(text => {
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
                showToast('success', data.message || '<?= __('admin.user_profile_updated_successfully') ?>');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast('error', data.message || '<?= __('admin.update_failed') ?>');
            }
        })
        .catch(error => {
            // Fallback to traditional form submission
            showToast('info', '<?= __('admin.switching_to_traditional_submission') ?>');
            setTimeout(() => {
                this.submit();
            }, 1000);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
</script>