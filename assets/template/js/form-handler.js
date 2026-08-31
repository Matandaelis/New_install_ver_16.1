function handleFormWithRecaptcha(formSelector, submitUrl, recaptchaAction, extraData = '') {
    var $form = $(formSelector);
    var sitekey = window.recaptchaSiteKey || '';
    var recaptcha_version = window.recaptchaVersion || 'v2';

    function doRequest() {
        $.ajax({
            url: submitUrl,
            type: 'POST',
            dataType: 'json',
            data: $form.serialize() + extraData,
            success: function (json) {
                // Clear previous validation state
                $form.find(".is-invalid").removeClass("is-invalid");
                $form.find(".invalid-feedback, .success-msg").remove();

                if (json.success) {
                    $form.find('[name="email"]').after(
                        "<div class='alert success-msg alert-success'>" + json.success + "</div>"
                    );
                }

                if (json.errors) {
                    $.each(json.errors, function (fieldName, msg) {
                        if (
                            fieldName === 'captch_response' &&
                            typeof grecaptcha !== 'undefined' &&
                            recaptcha_version === 'v2'
                        ) {
                            grecaptcha.reset();
                            return;
                        }

                        var $el = $form.find('[name="' + fieldName + '"]');
                        if ($el.length) {
                            // If inside an input-group, attach error after the entire group
                            var $group = $el.closest('.input-group');
                            if ($group.length) {
                                $group.find('.form-control').addClass('is-invalid');
                                $group.after('<div class="invalid-feedback d-block">' + msg + '</div>');
                            } else {
                                $el.addClass('is-invalid');
                                $el.after('<div class="invalid-feedback d-block">' + msg + '</div>');
                            }
                        }
                    });
                }

                if (json.redirect) {
                    window.location = json.redirect;
                }
            }
        });
    }

    $form.on('submit', function (e) {
        e.preventDefault();
        if (recaptcha_version === 'v3' && typeof grecaptcha !== 'undefined') {
            grecaptcha.execute(sitekey, { action: recaptchaAction }).then(function (token) {
                $('#recaptcha_token').val(token);
                doRequest();
            });
        } else {
            doRequest();
        }
    });
}