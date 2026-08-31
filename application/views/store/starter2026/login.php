<?php
/**
 * Starter 2026 — Login & Register Page
 *
 * @contract  Store API v1 — page: login
 *
 * GLOBALS  $store_setting, $home_link, $base_url, $googlerecaptcha
 *
 * PAGE VARIABLES
 *   $redirect_url  string  URL to redirect to after successful login
 *   $settings      array   Store settings
 *   $category      array   Root categories (for header nav)
 */
?>

<section class="s26-auth-page">
    <div class="container">

        <div class="text-center mb-5">
            <h1 class="s26-page-title"><?= __('store.welcome_back') ?? 'Welcome' ?></h1>
            <p class="s26-page-subtitle"><?= __('store.login_or_create_account') ?? 'Sign in to your account or create a new one' ?></p>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- ═══════════ LOGIN FORM ═══════════ -->
            <div class="col-lg-5 col-md-6">
                <div class="s26-auth-card">
                    <div class="s26-auth-card__header">
                        <div class="s26-auth-card__icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h2><?= __('store.login_with_existing_account') ?? 'Sign In' ?></h2>
                        <p><?= __('store.login_subtitle') ?? 'Access your account' ?></p>
                    </div>

                    <form id="login-form" method="post">
                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.username') ?? 'Username' ?></label>
                            <div class="s26-input-wrap">
                                <i class="fas fa-user"></i>
                                <input class="s26-form-input" name="username" type="text" placeholder="<?= __('store.enter_username') ?? 'Enter your username' ?>" required>
                            </div>
                        </div>

                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.password') ?? 'Password' ?></label>
                            <div class="s26-input-wrap">
                                <i class="fas fa-lock"></i>
                                <input class="s26-form-input" name="password" type="password" placeholder="<?= __('store.enter_password') ?? 'Enter your password' ?>" required>
                            </div>
                        </div>

                        <?php if (!empty($googlerecaptcha['client_login']) && !empty($googlerecaptcha['sitekey'])): ?>
                            <?php
                                $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
                                $sitekey = $googlerecaptcha['sitekey'];
                            ?>
                            <?php if ($recaptcha_version === 'v2'): ?>
                                <div class="s26-form-group">
                                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                    <div class="g-recaptcha" data-sitekey="<?= $sitekey ?>"></div>
                                </div>
                            <?php elseif ($recaptcha_version === 'v3'): ?>
                                <script src="https://www.google.com/recaptcha/api.js?render=<?= $sitekey ?>"></script>
                                <script>
                                    grecaptcha.ready(function() {
                                        grecaptcha.execute('<?= $sitekey ?>', {action: 'client_login'}).then(function(token) {
                                            var input = document.getElementById('recaptcha_token');
                                            if (input) input.value = token;
                                        });
                                    });
                                </script>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="<?= base_url('store/track_order') ?>" class="s26-forgot-link">
                                <?= __('store.track_your_order') ?? 'Track your order' ?>
                            </a>
                            <a href="#forgot-password-model" data-bs-toggle="modal" class="s26-forgot-link">
                                <?= __('store.forget_password') ?? 'Forgot password' ?>?
                            </a>
                        </div>

                        <button type="submit" class="s26-btn-primary w-100 justify-content-center btn-submit">
                            <i class="fas fa-sign-in-alt"></i>
                            <?= __('store.login') ?? 'Sign In' ?>
                        </button>
                        <input type="hidden" name="g-recaptcha-response" id="recaptcha_token">
                    </form>
                </div>
            </div>

            <!-- Divider -->
            <div class="col-lg-1 d-none d-lg-flex align-items-center justify-content-center">
                <div class="s26-auth-divider">
                    <span><?= __('store.or') ?? 'OR' ?></span>
                </div>
            </div>
            <div class="d-lg-none text-center py-2">
                <span class="s26-auth-divider-mobile"><?= __('store.or') ?? 'OR' ?></span>
            </div>

            <!-- ═══════════ REGISTER FORM ═══════════ -->
            <div class="col-lg-5 col-md-6">
                <div class="s26-auth-card">
                    <div class="s26-auth-card__header">
                        <div class="s26-auth-card__icon s26-auth-card__icon--register">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h2><?= __('store.create_a_new_account') ?? 'Create Account' ?></h2>
                        <p><?= __('store.register_subtitle') ?? 'Join us today' ?></p>
                    </div>

                    <form id="register-form" method="post">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.first_name') ?? 'First Name' ?></label>
                                    <input class="s26-form-input" name="f_name" type="text" placeholder="<?= __('store.first_name') ?? 'First name' ?>" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.last_name') ?? 'Last Name' ?></label>
                                    <input class="s26-form-input" name="l_name" type="text" placeholder="<?= __('store.last_name') ?? 'Last name' ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.username') ?? 'Username' ?></label>
                            <div class="s26-input-wrap">
                                <i class="fas fa-user"></i>
                                <input class="s26-form-input" name="username" type="text" placeholder="<?= __('store.choose_username') ?? 'Choose a username' ?>" required>
                            </div>
                        </div>

                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.email') ?? 'Email' ?></label>
                            <div class="s26-input-wrap">
                                <i class="fas fa-envelope"></i>
                                <input class="s26-form-input" name="email" type="email" placeholder="<?= __('store.enter_your_email') ?? 'Enter your email' ?>" required>
                            </div>
                        </div>

                        <!-- Phone with intl-tel-input -->
                        <link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
                        <script src="<?= base_url('assets/plugins/tel/js/intlTelInput.min.js') ?>"></script>
                        <input type="hidden" name='PhoneNumberInput' id="phonenumber-input" value="">
                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.phone_number') ?? 'Phone Number' ?></label>
                            <div>
                                <input onkeypress="return isNumberKey(event);" id="phone" type="text" name="phone" value="" class="s26-form-input" placeholder="<?= __('store.phone_number') ?? 'Phone number' ?>">
                            </div>
                        </div>
                        <script type="text/javascript">
                            var tel_input = intlTelInput(document.querySelector("#phone"), {
                            dropdownContainer: document.body,
                            placeholderNumberType: "MOBILE",
                            autoPlaceholder: "aggressive",
                                initialCountry: "auto",
                                utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
                                separateDialCode: true,
                                geoIpLookup: function(success, failure) {
                                    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                                        var countryCode = (resp && resp.country) ? resp.country : "";
                                        success(countryCode);
                                    });
                                },
                            });

                            function isNumberKey(evt) {
                                var charCode = (evt.which) ? evt.which : event.keyCode;
                                if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57))
                                    return false;
                                return true;
                            }
                        </script>

                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.password') ?? 'Password' ?></label>
                            <div class="s26-input-wrap">
                                <i class="fas fa-lock"></i>
                                <input class="s26-form-input" name="password" type="password" placeholder="<?= __('store.create_password') ?? 'Create a password' ?>" required>
                            </div>
                        </div>

                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.confirm_password') ?? 'Confirm Password' ?></label>
                            <div class="s26-input-wrap">
                                <i class="fas fa-lock"></i>
                                <input class="s26-form-input" name="c_password" type="password" placeholder="<?= __('store.confirm_password') ?? 'Confirm your password' ?>" required>
                            </div>
                        </div>

                        <?php if (!empty($googlerecaptcha['client_register']) && !empty($googlerecaptcha['sitekey'])): ?>
                            <script type="text/javascript">var grecaptcha_register = 1;</script>
                            <?php
                                $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
                                $sitekey = $googlerecaptcha['sitekey'];
                            ?>
                            <?php if ($recaptcha_version === 'v2'): ?>
                                <div class="s26-form-group">
                                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                    <div class="g-recaptcha" data-sitekey="<?= $sitekey ?>"></div>
                                </div>
                            <?php elseif ($recaptcha_version === 'v3'): ?>
                                <script src="https://www.google.com/recaptcha/api.js?render=<?= $sitekey ?>"></script>
                                <script>
                                    grecaptcha.ready(function() {
                                        grecaptcha.execute('<?= $sitekey ?>', {action: 'client_register'}).then(function(token) {
                                            document.getElementById('recaptcha_token_register').value = token;
                                        });
                                    });
                                </script>
                                <input type="hidden" name="g-recaptcha-response" id="recaptcha_token_register">
                            <?php endif; ?>
                        <?php endif; ?>

                        <button type="submit" class="s26-btn-primary w-100 justify-content-center btn-submit" style="margin-top:8px;">
                            <i class="fas fa-user-plus"></i>
                            <?= __('store.register') ?? 'Create Account' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgot-password-model" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:var(--s26-radius-lg);overflow:hidden">
            <form action="store/forgot" method="post" id="forgot-password">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold"><?= __('store.forgot_password_?') ?? 'Forgot Password?' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted mb-3" style="font-size:14px"><?= __('store.forgot_password_desc') ?? 'Enter your email and we\'ll send you a reset link.' ?></p>
                    <div class="s26-form-group">
                        <div class="s26-input-wrap">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="forgot_email" class="s26-form-input" placeholder="<?= __('store.email_address') ?? 'Email address' ?>" required>
                        </div>
                        <span class="text-danger" style="font-size:13px"></span>
                    </div>
                    <button type="submit" class="s26-btn-primary w-100 justify-content-center btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        <?= __('store.submit') ?? 'Send Reset Link' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Login/Register AJAX Scripts -->
<script type="text/javascript">
$("#login-form").on('submit', function() {
    $this = $(this);
    $.ajax({
        url: '<?= $base_url ?>ajax_login',
        type: 'POST',
        dataType: 'json',
        data: $this.serialize(),
        beforeSend: function() { $this.find(".btn-submit").btn("loading"); },
        complete: function() { $this.find(".btn-submit").btn("reset"); },
        success: function(result) {
            $this.find(".has-error").removeClass("has-error");
            $this.find("span.text-danger").remove();
            if (result['success']) { location = '<?= $redirect_url ?>'; }
            if (result['errors']) {
                $.each(result['errors'], function(i, j) {
                    $ele = $this.find('[name="' + i + '"]');
                    if ($ele.length) {
                        $ele.parents(".s26-form-group, .form-group").addClass("has-error");
                        $ele.after("<span class='text-danger' style='font-size:12px;display:block;margin-top:4px'>" + j + "</span>");
                    }
                });
            }
        },
    });
    return false;
});

$("#register-form").on('submit', function() {
    $this = $(this);
    var errorMap = [
        '<?= __('store.invalid_number') ?>',
        '<?= __('store.invalid_country_code') ?>',
        '<?= __('store.too_short') ?>',
        '<?= __('store.too_long') ?>',
        '<?= __('store.invalid_number') ?>'
    ];
    is_valid = false;
    var errorInnerHTML = '';
    if ($("#phone").val().trim()) {
        if (tel_input.isValidNumber()) {
            is_valid = true;
            tel_input.setNumber($("#phone").val().trim());
            $("#phonenumber-input").val("+" + tel_input.getSelectedCountryData().dialCode + ' ' + $("#phone").val().trim());
        } else {
            var errorCode = tel_input.getValidationError();
            errorInnerHTML = errorMap[errorCode];
        }
    } else {
        errorInnerHTML = '<?= __('store.mobile_number_is_required') ?>';
    }
    $("#phone").parents(".s26-form-group, .form-group").removeClass("has-error");
    $("#register-form .text-danger").remove();

    if (!is_valid) {
        $("#phone").parents(".s26-form-group, .form-group").addClass("has-error");
        $("#phone").parents(".s26-form-group, .form-group").find('> div').after("<span class='text-danger' style='font-size:12px;display:block;margin-top:4px'>" + errorInnerHTML + "</span>");
        return false;
    }

    $.ajax({
        url: '<?= $base_url ?>ajax_register',
        type: 'POST',
        dataType: 'json',
        data: $this.serialize(),
        beforeSend: function() { $this.find(".btn-submit").btn("loading"); },
        complete: function() { $this.find(".btn-submit").btn("reset"); },
        success: function(result) {
            $this.find(".has-error").removeClass("has-error");
            $this.find("span.text-danger").remove();
            if (result['success']) { location = '<?= $redirect_url ?>'; }
            if (result['errors']) {
                $.each(result['errors'], function(i, j) {
                    $ele = $this.find('[name="' + i + '"]');
                    if ($ele.length) {
                        $ele.parents(".s26-form-group, .form-group").addClass("has-error");
                        $ele.after("<span class='text-danger' style='font-size:12px;display:block;margin-top:4px'>" + j + "</span>");
                    }
                });
            }
        },
    });
    return false;
});

$("#forgot-password").on('submit', function() {
    $this = $(this);
    $.ajax({
        url: '<?= $base_url ?>forgot',
        type: 'POST',
        dataType: 'json',
        data: $this.serialize(),
        beforeSend: function() { $this.find(".btn-submit").btn("loading"); },
        complete: function() { $this.find(".btn-submit").btn("reset"); },
        success: function(json) {
            $this.find("span.text-danger").text('');
            if (json.success) {
                $('#forgot-password-model').modal('hide');
                Swal.fire({ icon: 'success', title: json.success, confirmButtonColor: 'var(--s26-primary)' });
            }
            if (json.error) {
                $this.find("span.text-danger").text(json.error);
            }
        },
    });
    return false;
});
</script>
