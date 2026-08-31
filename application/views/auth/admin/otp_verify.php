<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?= __('admin.admin_login') ?></title>
    <meta content="Admin Dashboard" name="description">
    <meta content="Mannatthemes" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <input type="hidden" id="theme_Name" value="admin_theme">

    <?php if($setting['favicon']){ ?>
        <link rel="icon" href="<?= base_url('assets/images/site/'.$setting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php } ?>

    <?php include(APPPATH.'views/includes/layout.php'); ?>
</head>
<body>

<?php if(isset($theme['admin_login_background_option']) && $theme['admin_login_background_option']==0) { ?>
    <?php if(isset($theme['admin-login-background-image']) && !empty(trim($theme['admin-login-background-image']))) { ?>
        <div class="min-vh-100 d-flex align-items-center justify-content-center" style="background-image:url(<?= base_url('assets/images/site/' . $theme['admin-login-background-image']) ?>); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <?php } else { ?>
        <div class="min-vh-100 d-flex align-items-center justify-content-center" style="background-image: url('<?= base_url('assets/template/images/bg-main.png') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <?php } ?>
<?php } elseif(isset($theme['admin_login_background_option']) && $theme['admin_login_background_option']==1) { ?>
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background:<?= $theme['admin_login_background_color'] ?>">
<?php } else { ?>
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background-image: url('<?= base_url('assets/template/images/bg-main.png') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
<?php } ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0" style="<?= isset($theme['admin_login_box_background_color']) ? 'background:' . $theme['admin_login_box_background_color'] . ';' : '' ?>">
                    <div class="card-body p-4 p-md-5">
                        
                        <?php
                            $logo = !empty($setting['admin-side-logo']) && (strpos($setting['admin-side-logo'], 'http') === 0)
                                ? $setting['admin-side-logo']
                                : (!empty($setting['admin-side-logo'])
                                    ? base_url('assets/images/site/' . $setting['admin-side-logo'])
                                    : base_url('assets/template/images/admin-default-logo.png'));
                        ?>
                        <div class="text-center mb-4">
                            <img src="<?= htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="max-height: 60px;" alt="<?= __('admin.logo') ?>">
                        </div>

                            <?php if ($this->session->flashdata('error')): ?>
                             <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                                 <div class="d-flex align-items-center">
                                     <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                                     <span class="fw-semibold"><?= $this->session->flashdata('error') ?></span>
                                 </div>
                                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                             </div>
                         <?php endif; ?>

                        <form method="post" action="<?= base_url('auth/admin/otp_validate') ?>" class="form-login" id="otp-form">
                            <div class="form-group mb-4 position-relative">
                                <label class="form-label fw-semibold text-dark mb-2"><?= __('admin.enter_otp_code') ?></label>
                                <input type="text" name="otp_code" class="form-control form-control-lg rounded-pill ps-5" placeholder="●●●●●●" required autofocus maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code">
                                <i class="bi bi-shield-lock position-absolute top-50 start-0 translate-middle-y text-muted ps-3"></i>
                                <div class="form-text text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <?= __('admin.otp_hint') ?>
                                </div>
                            </div>
                            <div class="form-group d-grid gap-3 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-semibold py-3">
                                    <i class="bi bi-check-circle me-2"></i><?= __('admin.verify_otp') ?>
                                </button>
                            </div>
                        </form>

                        <div class="d-flex flex-column gap-3 mt-5 pt-4 border-top">
                            <div class="d-grid">
                                <a href="<?= base_url('admin') ?>" class="btn btn-outline-secondary rounded-pill py-2">
                                    <i class="bi bi-arrow-left me-2"></i><?= __('admin.back_to_admin_login') ?>
                                </a>
                            </div>
                            
                            <div class="text-center">
                                <button type="button" class="btn btn-outline-primary rounded-pill py-2 px-4" id="resend-otp-btn" onclick="resendOtp()" disabled>
                                    <i class="bi bi-arrow-clockwise me-2"></i><?= __('admin.resend_otp') ?>
                                </button>
                                <div id="resend-timer" class="small text-muted mt-2">
                                    <?= __('admin.resend_in_seconds') ?> <span id="otp-countdown" class="badge bg-primary rounded-pill">30</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let countdown = 30;
    let countdownEl = document.getElementById('otp-countdown');
    let resendBtn = document.getElementById('resend-otp-btn');

    const timer = setInterval(() => {
        countdown--;
        countdownEl.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            document.getElementById('resend-timer').textContent = '';
        }
    }, 1000);

    function resendOtp() {
        resendBtn.disabled = true;
        resendBtn.innerText = '<?= __('admin.resending') ?>';

        fetch('<?= base_url('auth/admin/resend_otp') ?>', {
            method: 'POST'
        })
        .then(res => res.json())
        .then(data => {
            resendBtn.innerText = '🔄 <?= __('admin.resend_otp') ?>';
            if (data.status === 'success') {
                // Show enhanced success toast
                if (typeof window.showToast === 'function') {
                    window.showToast('<?= __('admin.otp_sent_successfully') ?>', '<?= __('admin.check_email_for_otp') ?>', 'success', 4000);
                }
                
                countdown = 30;
                resendBtn.disabled = true;
                document.getElementById('resend-timer').innerHTML = '<?= __('admin.resend_in_seconds') ?> <span id="otp-countdown" class="badge bg-secondary">30</span>';
                countdownEl = document.getElementById('otp-countdown');
                const newTimer = setInterval(() => {
                    countdown--;
                    countdownEl.textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(newTimer);
                        resendBtn.disabled = false;
                        document.getElementById('resend-timer').textContent = '';
                    }
                }, 1000);
            } else {
                // Show error toast
                if (typeof window.showToast === 'function') {
                    window.showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_resend_otp') ?>', 'danger', 5000);
                } else {
                    alert('<?= __('admin.failed_to_resend_otp') ?>');
                }
            }
        })
        .catch(error => {
            // Show network error toast
            if (typeof window.showToast === 'function') {
                window.showToast('<?= __('admin.network_error') ?>', '<?= __('admin.please_check_connection') ?>', 'danger', 5000);
            }
        });
    }

    // Add AJAX form submission for OTP verification
    document.getElementById('otp-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><?= __('admin.verifying') ?>...';
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            return response.text().then(text => {
                // Try to parse as JSON first
                if (text.trim().startsWith('{') && text.trim().endsWith('}')) {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                    }
                } else {
                    // Fallback to HTML parsing
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(text, 'text/html');
                    const errorAlert = doc.querySelector('.alert-danger');
                    
                    if (errorAlert) {
                        return { success: false, message: errorAlert.textContent.trim() };
                    } else {
                        return { success: true, redirect: '<?= base_url('admincontrol/dashboard') ?>' };
                    }
                }
            });
        })
        .then(data => {
            if (data.success) {
                // Show enhanced success message
                if (typeof window.showToast === 'function') {
                    window.showToast('<?= __('admin.verification_success') ?>', '<?= __('admin.welcome_to_dashboard') ?>', 'success', 3000);
                }
                
                // Add success animation to form
                form.classList.add('success-animation');
                
                // Show success alert
                const successDiv = document.createElement('div');
                successDiv.className = 'alert alert-success alert-dismissible fade show border-0 shadow-sm mt-3';
                successDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-3 fs-4"></i>
                        <div>
                            <strong><?= __('admin.verification_success') ?></strong><br>
                            <small class="text-muted"><?= __('admin.welcome_to_dashboard') ?></small>
                        </div>
                    </div>
                `;
                form.querySelector('.form-group').insertAdjacentElement('afterend', successDiv);
                
                setTimeout(() => {
                    window.location.href = data.redirect || '<?= base_url('admincontrol/dashboard') ?>';
                }, 2000);
            } else {
                // Show enhanced error toast
                if (typeof window.showToast === 'function') {
                    window.showToast('<?= __('admin.verification_failed') ?>', '<?= __('admin.otp_error_help') ?>', 'danger', 6000);
                }
                
                // Show enhanced error in form
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger alert-dismissible fade show border-0 shadow-sm mt-3';
                errorDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-exclamation text-danger me-3 fs-4"></i>
                        <div>
                            <strong><?= __('admin.otp_error_title') ?></strong><br>
                            <small class="text-muted"><?= __('admin.otp_error_help') ?></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                form.querySelector('.form-group').insertAdjacentElement('afterend', errorDiv);
                
                // Add shake animation to input
                const otpInput = form.querySelector('input[name="otp_code"]');
                otpInput.classList.add('shake-animation', 'is-invalid');
                setTimeout(() => {
                    otpInput.classList.remove('shake-animation');
                }, 500);
            }
        })
        .catch(error => {
            // Show network error toast
            if (typeof window.showToast === 'function') {
                window.showToast('<?= __('admin.network_error') ?>', '<?= __('admin.please_check_connection') ?>', 'danger', 5000);
            }
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Add CSS animations for better UX
    if (!document.getElementById('otp-animations')) {
        const style = document.createElement('style');
        style.id = 'otp-animations';
        style.textContent = `
            .shake-animation {
                animation: shake 0.5s ease-in-out;
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
            
            .success-animation {
                animation: successPulse 1s ease-in-out;
            }
            
            @keyframes successPulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.02); }
                100% { transform: scale(1); }
            }
            
            .form-control.is-invalid {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            }
            
            .invalid-feedback {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
            
            .alert {
                border-radius: 0.75rem;
            }
        `;
        document.head.appendChild(style);
    }
</script>

</body>
</html>