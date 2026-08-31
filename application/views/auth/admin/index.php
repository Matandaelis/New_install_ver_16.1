<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?= __('admin.admin_login') ?></title>
    <meta content="Admin Dashboard" name="description">
    <meta content="Mannatthemes" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <input type="hidden" id="theme_Name" value="admin_theme">

    <?php if($setting['favicon']){ ?>
        <link rel="icon" href="<?php echo base_url('assets/images/site/'.$setting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php } ?>

    <!--Include layout.php-->
    <?php include(APPPATH.'views/includes/layout.php'); ?>
    <link rel="stylesheet" href="<?= base_url('assets/template/css/admin-login-modern.css') ?>?v=<?= av() ?>">
    <!--Include layout.php-->
  </head>
  <body>


<?php
$has_bg_image = (isset($theme['admin_login_background_option']) && $theme['admin_login_background_option']==0) || (!isset($theme['admin_login_background_option']));
$overlay_class = $has_bg_image ? ' admin-login-branding--has-bg' : ' admin-login-branding--solid';
$branding_style = '';
if (isset($theme['admin_login_background_option']) && $theme['admin_login_background_option']==1) {
    $branding_style = !empty($theme['admin_login_background_color']) ? 'background:'.$theme['admin_login_background_color'] : 'background:#1e3a8a';
} elseif (isset($theme['admin-login-background-image']) && !empty(trim($theme['admin-login-background-image']))) {
    $branding_style = "background-image:url(".base_url('assets/images/site/'.$theme['admin-login-background-image'])."); background-size: cover; background-position: center; background-repeat: no-repeat;";
} else {
    $branding_style = "background-image: url('".base_url('assets/template/images/bg-main.png')."'); background-size: cover; background-position: center; background-repeat: no-repeat;";
}
$logo = !empty($setting['admin-side-logo']) && (strpos($setting['admin-side-logo'], 'http') === 0) ?
    $setting['admin-side-logo'] :
    (!empty($setting['admin-side-logo']) ? base_url('assets/images/site/' . $setting['admin-side-logo']) : base_url('assets/template/images/admin-default-logo.png'));
?>
<div class="admin-login-page admin-login-split min-vh-100">
    <div class="admin-login-branding<?= $overlay_class ?>" style="<?= $branding_style ?>">
        <div class="admin-login-branding-inner">
            <div class="admin-login-branding-logo">
                <img src="<?= htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= __('admin.logo') ?>">
            </div>
            <h1 class="admin-login-headline"><?= __('admin.admin_login_headline') ?></h1>
            <p class="admin-login-tagline"><?= __('admin.admin_login_tagline') ?></p>
            <div class="admin-login-features">
                <div class="admin-login-feature-item">
                    <i class="bi bi-shield-check"></i>
                    <span><?= __('admin.admin_login_feature_secure') ?></span>
                </div>
                <div class="admin-login-feature-item">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span><?= __('admin.admin_login_feature_analytics') ?></span>
                </div>
                <div class="admin-login-feature-item">
                    <i class="bi bi-lightning-charge"></i>
                    <span><?= __('admin.admin_login_feature_powerful') ?></span>
                </div>
            </div>
            <div class="admin-login-branding-deco admin-login-branding-deco--1"></div>
            <div class="admin-login-branding-deco admin-login-branding-deco--2"></div>
        </div>
    </div>
    <div class="admin-login-form-panel">
        <div class="admin-login-form-wrap">
            <div class="admin-login-form-card admin-login-card" style="<?= isset($theme['admin_login_box_background_color']) ? 'background:'.$theme['admin_login_box_background_color'] : '' ?>">
                <p class="admin-login-subtitle"><?= __('admin.admin_login_subtitle') ?></p>
                <form class="form-login">
                                    <div class="form-group position-relative mb-3">
                                        <input name="type" type="hidden" value="admin"/>
                                        <input type="text" id="username-field" class="form-control form-control-lg ps-5" placeholder="<?= __('admin.username') ?>" name="username" required autocomplete="username">
                                        <i class="bi bi-person position-absolute top-50 start-0 translate-middle-y text-muted ps-3"></i>
                                        <div class="form-text text-muted small">
                                            <i class="bi bi-info-circle me-1"></i>
                                            <?= __('admin.username_hint') ?>
                                        </div>
                                    </div>

                                    <div class="form-group position-relative mb-3">
                                        <input type="password" id="password-field" class="form-control form-control-lg ps-5 pe-5" placeholder="<?= __('admin.password') ?>" name="password" required autocomplete="current-password">
                                        <i class="bi bi-lock position-absolute top-50 start-0 translate-middle-y text-muted ps-3"></i>
                                        <i id="toggle-password" class="bi bi-eye-fill position-absolute top-50 end-0 translate-middle-y text-muted pe-3 admin-login-toggle-pwd" title="<?= __('admin.show_hide_password') ?>"></i>
                                        <div class="form-text text-muted small">
                                            <i class="bi bi-shield-check me-1"></i>
                                            <?= __('admin.password_hint') ?>
                                        </div>
                                    </div>

                                    <div class="nav-item dropdown mb-3">
                                        <?= $LanguageHtml ?>
                                    </div>

                                    <?php 
                                        $db =& get_instance(); 
                                        $googlerecaptcha = $db->Product_model->getSettings('googlerecaptcha');
                                        $recaptcha_enabled = isset($googlerecaptcha['admin_login']) && $googlerecaptcha['admin_login'];
                                        $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
                                        $sitekey = $googlerecaptcha['sitekey'] ?? '';
                                    ?>

                                    <?php if ($recaptcha_enabled && $sitekey): ?>
                                        <?php if ($recaptcha_version === 'v2'): ?>
                                            <div class="captch mb-3">
                                                <div class="g-recaptcha" data-sitekey="<?= $sitekey ?>"></div>
                                                <input type="hidden" name="captch_response" id="captch_response">
                                                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                            </div>
                                            <script>
                                            var recaptchaCallback = function() {
                                                grecaptcha.render(document.querySelector('.g-recaptcha'), {
                                                    'sitekey': '<?= $sitekey ?>',
                                                    'callback': function(response) {
                                                        document.getElementById('captch_response').value = response;
                                                        document.getElementById('g-recaptcha-response').value = response;
                                                    }
                                                });
                                            };
                                            </script>
                                            <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit" async defer></script>
                                        <?php elseif ($recaptcha_version === 'v3'): ?>
                                            <script src="https://www.google.com/recaptcha/api.js?render=<?= $sitekey ?>"></script>
                                            <input type="hidden" name="captch_response" id="captch_response">
                                            <script>
                                                grecaptcha.ready(function () {
                                                    grecaptcha.execute('<?= $sitekey ?>', { action: 'admin_login' }).then(function (token) {
                                                        document.getElementById('captch_response').value = token;
                                                    });
                                                });
                                            </script>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <div class="form-group mb-3">
                                        <input type="submit" class="btn btn-primary text-white w-100" id="admin-login-btn" value="<?= __('admin.log_in') ?>">
                                    </div>
                                    <div class="text-center">
                                        <a href="javascript:void(0)" onclick="_open('forget-form')" class="text-decoration-none"><?= __('admin.forgot_your_password') ?></a>
                                    </div>
                                    <?php if (!empty($show_login_as_demo_admin) && !empty($admin_login_url)): ?>
                                    <div class="text-center mt-3">
                                        <a href="<?= htmlspecialchars($admin_login_url, ENT_QUOTES, 'UTF-8') ?>" class="admin-login-demo-admin-link"><?= __('admin.login_as_demo_admin') ?></a>
                                    </div>
                                    <?php endif; ?>
                                </form>
                                
                                <form class="forget-form" style="display:none;">
                                    <div class="form-group position-relative mb-3">
                                        <input type="email" name="email" class="form-control form-control-lg ps-5" placeholder="<?= __('admin.enter_your_email_address') ?>" required>
                                        <i class="bi bi-envelope position-absolute top-50 start-0 translate-middle-y text-muted ps-3"></i>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="submit" class="btn btn-primary text-white w-100" value="<?= __('admin.forgot_password') ?>">
                                    </div>
                                    <div class="text-center">
                                        <a href="javascript:void(0)" onclick="_open('form-login')" class="text-decoration-none"><?= __('admin.back_to_login') ?></a>
                                    </div>
                                </form>
                                
                <div id="otp-limit-warning" class="alert alert-danger mt-3 d-none text-center">
                    <?= __('admin.too_many_otp_attempts') ?> <span id="otp-countdown">60</span> <?= __('admin.seconds_suffix') ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function _open(form) {
        $(".form-login, .forget-form").hide();
        $("."+form).show();
    }


$('.form-login').on('submit', function () {
    var inputLogin = $('.form-login');
    var loginBtn = inputLogin.find('input[type="submit"]');
    var originalText = loginBtn.val();
    var isOtpEnabled = inputLogin.find('input[name="is_otp_enabled"]').val() === '1';

    // Handle reCAPTCHA if it exists
    var recaptchaContainer = $('.g-recaptcha');
    if (recaptchaContainer.length > 0 && typeof grecaptcha !== 'undefined') {
        try {
            var captch_response = grecaptcha.getResponse();
            if (!captch_response || captch_response.length === 0) {
                // Show error and prevent submission
                inputLogin.prepend("<div class='alert alert-danger mb-3'>Please complete the reCAPTCHA verification.</div>");
                return false;
            }
            // Set both field names that controller might expect
            $("#captch_response").val(captch_response);
            inputLogin.append('<input type="hidden" name="g-recaptcha-response" value="' + captch_response + '">');
        } catch (err) {
            console.error("reCAPTCHA error:", err);
            inputLogin.prepend("<div class='alert alert-danger mb-3'>reCAPTCHA error. Please refresh the page.</div>");
            return false;
        }
    }

    var loadingText = isOtpEnabled ? '🔐 <?= __('admin.sending_otp') ?>' : '🔐 <?= __('admin.logging_in') ?>';
    loginBtn.prop("disabled", true).val(loadingText);

    $.ajax({
        url: '<?= base_url('auth') ?>/login',
        type: 'POST',
        dataType: 'json',
        data: inputLogin.serialize(),
        complete: function () {
            loginBtn.prop("disabled", false).val(originalText);
        },
        success: function (json) {
            var $container = inputLogin;
            $container.find(".has-error").removeClass("has-error");
            $container.find("span.text-danger, .alert").remove();

            if (json.errors && json.errors.otp_limit) {
                var secondsLeft = parseInt(json.errors.otp_limit);
                $('#otp-countdown').text(secondsLeft);
                $('#otp-limit-warning').removeClass('d-none');

                var countdown = setInterval(function () {
                    secondsLeft--;
                    $('#otp-countdown').text(secondsLeft);
                    if (secondsLeft <= 0) {
                        clearInterval(countdown);
                        $('#otp-limit-warning').addClass('d-none');
                    }
                }, 1000);

                return;
            }

            if (json.errors) {
                // Clear previous errors first
                $container.find('.invalid-feedback').remove();
                $container.find('.form-control').removeClass('is-invalid');
                $container.find('.alert-danger').remove();
                
                // Show only ONE simple error alert using Bootstrap 5
                var firstError = Object.values(json.errors)[0];
                var generalErrorHtml = `
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong><?= __('admin.login_error') ?>:</strong> ${firstError}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                // Insert error at the top of the form
                $container.prepend(generalErrorHtml);
                
                // Show toast notification
                if (typeof window.showToast === 'function') {
                    window.showToast('<?= __('admin.login_error') ?>', firstError, 'danger', 5000);
                }
            }

            if (json.redirect) {
                // Show enhanced success message
                if (typeof window.showToast === 'function') {
                    window.showToast('<?= __('admin.login_success') ?>', '<?= __('admin.welcome_back_message') ?>', 'success', 3000);
                }
                
                // Add success animation to card
                $('.admin-login-card').addClass('success-animation');
                
                // Broadcast successful login to other tabs
                localStorage.setItem('admin_login_success', Date.now());
                setTimeout(() => {
                    window.location = json.redirect;
                }, 2000);
            }
        },
        error: function() {
            if (typeof window.showToast === 'function') {
                window.showToast('<?= __('admin.network_error') ?>', '<?= __('admin.please_check_connection') ?>', 'danger', 5000);
            }
        }
    });

    return false;
});


    $('.forget-form').on('submit',function(){
      
        $.ajax({
            url:'<?= base_url('auth') ?>/forget',
            type:'POST',
            dataType:'json',
            data: $('.forget-form').serialize(),
            beforeSend:function(){ $('.forget-form input[type="submit"]').prop("disabled",true); },
            complete:function(){ $('.forget-form input[type="submit"]').prop("disabled",false); },
            success:function(json){
                
                $container = $('.forget-form');
                $container.find(".has-error").removeClass("has-error");
                $container.find("span.text-danger,.alert").remove();
                
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        $ele = $container.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.addClass("is-invalid");
                            $ele.parent().after('<div class="invalid-feedback">' + j + '</div>');
                        }
                    })
                }

                if(json['success']){
                    $('.forget-form').prepend('<div class="alert alert-success alert-dismissible fade show mb-3" role="alert"><i class="bi bi-check-circle-fill me-2"></i><strong><?= __('admin.email_sent_successfully') ?>:</strong> <?= __('admin.check_email_instructions') ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    
                    // Show enhanced success toast
                    if (typeof window.showToast === 'function') {
                        window.showToast('<?= __('admin.email_sent_successfully') ?>', '<?= __('admin.check_email_instructions') ?>', 'success', 5000);
                    }
                }

                if(json['redirect']){
                    setTimeout(() => {
                        window.location = json['redirect'];
                    }, 1500);
                }
            },
        })
        
        return false;
    });
</script>


 <script>
    $(document).ready(function() {
    $('#toggle-password').click(function() {
        const passwordField = $('#password-field');
        const passwordFieldType = passwordField.attr('type');
        
        if(passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
            $(this).removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
        } else {
            passwordField.attr('type', 'password');
            $(this).removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
        }
    });
});

</script>


 <script>
$(document).ready(function() {
    // Listen for successful login from other tabs
    $(window).on('storage', function(e) {
        if (e.originalEvent.key === 'admin_login_success') {
            // If we're on login page, redirect to last page or dashboard
            const lastPages = Object.keys(localStorage).filter(key => key.startsWith('admin_last_page_'));
            let redirectUrl = '<?= base_url("admincontrol") ?>'; // Default dashboard
            
            if (lastPages.length > 0) {
                const latestPageKey = lastPages.sort().pop();
                redirectUrl = localStorage.getItem(latestPageKey);
                // Clean up old page storage
                lastPages.forEach(key => localStorage.removeItem(key));
            }
            
            window.location.href = redirectUrl;
        }
    });
});
</script>

</body>
</html>