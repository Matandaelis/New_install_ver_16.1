<?php
/**
 * Default theme — Customer login / registration page
 *
 * @contract  Store API v1 — page: login
 * @see       Store_cart_payload::page_login()
 * @see       /store/api/v1/pages/login
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $login_url       string  Form action URL for login POST
 *   $register_url    string  Form action URL for registration POST
 *   $googlerecaptcha string  Google reCAPTCHA site key (empty if disabled)
 *   $return_to       string  URL to redirect to after login (from ?return_to= param; empty if none)
 */
?>
<section class="amz-auth">
    <div class="amz-auth__container">
        <div class="amz-auth__grid">

            <!-- Login Form -->
            <div class="amz-auth__card">
                <h2 class="amz-auth__title"><?= __('store.login_with_existing_account') ?></h2>
                <form id="login-form" method="post" class="amz-form">
                    <div class="amz-form__group">
                        <label class="amz-form__label"><?= __('store.username') ?></label>
                        <input class="amz-form__input" name="username" type="text" autocomplete="username" required>
                    </div>
                    <div class="amz-form__group">
                        <label class="amz-form__label"><?= __('store.password') ?></label>
                        <input class="amz-form__input" name="password" type="password" autocomplete="current-password" required>
                    </div>

                    <?php if (!empty($googlerecaptcha['client_login']) && !empty($googlerecaptcha['sitekey'])): ?>
                        <?php
                            $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
                            $sitekey = $googlerecaptcha['sitekey'];
                        ?>
                        <?php if ($recaptcha_version === 'v2'): ?>
                            <div class="amz-form__group">
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

                    <div class="amz-form__row">
                        <a href="<?= base_url('store/track_order') ?>" class="amz-link"><?= __('store.track_your_order') ?></a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#forgot-password-model" class="amz-link"><?= __('store.forget_password') ?>?</a>
                    </div>
                    <div class="amz-form__group">
                        <button type="submit" class="amz-btn amz-btn-primary amz-btn--full btn-submit"><?= __('store.login') ?></button>
                    </div>
                </form>
                <input type="hidden" name="g-recaptcha-response" id="recaptcha_token">
            </div>

            <div class="amz-auth__divider"><span><?= __('store.or') ?></span></div>

            <!-- Registration Form -->
            <div class="amz-auth__card">
                <h2 class="amz-auth__title"><?= __('store.create_a_new_account') ?></h2>
                <form id="register-form" class="amz-form">
                    <div class="amz-form__row">
                        <div class="amz-form__group">
                            <label class="amz-form__label"><?= __('store.first_name') ?></label>
                            <input class="amz-form__input" name="f_name" type="text" autocomplete="given-name">
                        </div>
                        <div class="amz-form__group">
                            <label class="amz-form__label"><?= __('store.last_name') ?></label>
                            <input class="amz-form__input" name="l_name" type="text" autocomplete="family-name">
                        </div>
                    </div>
                    <div class="amz-form__group">
                        <label class="amz-form__label"><?= __('store.username') ?></label>
                        <input class="amz-form__input" name="username" type="text" autocomplete="username">
                    </div>
                    <link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
                    <script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
                    <input type="hidden" name="PhoneNumberInput" id="phonenumber-input" value="">
                    <div class="amz-form__group">
                        <label class="amz-form__label"><?= __('store.phone_number') ?></label>
                        <input onkeypress="return isNumberKey(event);" id="phone" type="text" name="phone" value="" autocomplete="tel">
                    </div>
                    <script type="text/javascript">
                        var tel_input = intlTelInput(document.querySelector("#phone"), {
                            initialCountry: "auto",
                            utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
                            separateDialCode: true,
                            dropdownContainer: document.body,
                            placeholderNumberType: "MOBILE",
                            autoPlaceholder: "aggressive",
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
                    <div class="amz-form__group">
                        <label class="amz-form__label"><?= __('store.email') ?></label>
                        <input class="amz-form__input" name="email" type="email" autocomplete="email" required>
                    </div>
                    <div class="amz-form__group">
                        <label class="amz-form__label"><?= __('store.password') ?></label>
                        <input class="amz-form__input" name="password" type="password" autocomplete="new-password" required>
                    </div>
                    <div class="amz-form__group">
                        <label class="amz-form__label"><?= __('store.confirm_password') ?></label>
                        <input class="amz-form__input" name="c_password" type="password" autocomplete="new-password" required>
                    </div>

                    <?php if (!empty($googlerecaptcha['client_register']) && !empty($googlerecaptcha['sitekey'])): ?>
                        <script type="text/javascript">var grecaptcha_register = 1;</script>
                        <?php
                            $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
                            $sitekey = $googlerecaptcha['sitekey'];
                        ?>
                        <?php if ($recaptcha_version === 'v2'): ?>
                            <div class="amz-form__group">
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

                    <div class="amz-form__group">
                        <button type="submit" class="amz-btn amz-btn-primary amz-btn--full btn-submit"><?= __('store.register') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgot-password-model">
    <div class="modal-dialog modal-dialog-centered">
        <div class="amz-modal">
            <form action="store/forgot" method="post" id="forgot-password">
                <div class="amz-modal__header">
                    <h4 class="amz-modal__title"><?= __('store.forgot_password_?') ?></h4>
                    <button type="button" class="amz-modal__close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="amz-modal__body">
                    <div class="amz-form__group">
                        <input type="text" name="forgot_email" class="amz-form__input" placeholder="<?= __('store.email_address') ?>" />
                        <span class="amz-form__error"></span>
                    </div>
                    <div class="amz-form__actions">
                        <button type="submit" class="amz-btn amz-btn-primary btn-submit"><?= __('store.submit') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
            if (result['success']) location = '<?= $redirect_url ?>';
            if (result['errors']) {
                $.each(result['errors'], function(i, j) {
                    $ele = $this.find('[name="' + i + '"]');
                    if ($ele.length) {
                        $ele.closest(".amz-form__group").addClass("has-error");
                        $ele.after("<span class='amz-form__error-text'>" + j + "</span>");
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
    $("#phone").closest(".amz-form__group").removeClass("has-error");
    $("#register-form .amz-form__error-text").remove();
    if (!is_valid) {
        $("#phone").closest(".amz-form__group").addClass("has-error");
        $("#phone").closest(".amz-form__group").append("<span class='amz-form__error-text'>" + errorInnerHTML + "</span>");
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
            $this.find("span.amz-form__error-text").remove();
            if (result['success']) location = '<?= $redirect_url ?>';
            if (result['errors']) {
                $.each(result['errors'], function(i, j) {
                    $ele = $this.find('[name="' + i + '"]');
                    if ($ele.length) {
                        $ele.closest(".amz-form__group").addClass("has-error");
                        $ele.after("<span class='amz-form__error-text'>" + j + "</span>");
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
            $this.find("span.amz-form__error-text").text('');
            if (json.success) {
                var _fp = document.getElementById('forgot-password-model');
                if (_fp && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    bootstrap.Modal.getInstance(_fp)?.hide();
                }
                alert(json.success);
            }
            if (json.error) {
                $this.find("span.amz-form__error-text").text(json.error);
            }
        },
    });
    return false;
});
</script>
