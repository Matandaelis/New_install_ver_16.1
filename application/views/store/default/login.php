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
<div class="container py-5">    
	<div class="row">
		<div class="col-md-5">
	
			<form id="login-form" method="post">
			    <div class="card shadow-sm border-top-card">
			        <div class="card-body p-md-5">
			            <h5 class="sub-title display-5 mb-5"><?= __('store.login_with_existing_account') ?></h5>
		            	<div class="mb-3">
        					<label class="control-label"><?= __('store.username') ?></label>
        					<input class="form-control" name="username" type="text">
        				</div>
        				<div class="mb-3">
        					<label class="control-label"><?= __('store.password') ?></label>
        					<input class="form-control" name="password" type="password">
        				</div>

						<?php if (!empty($googlerecaptcha['client_login']) && !empty($googlerecaptcha['sitekey'])): ?>
						    <?php
						        $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
						        $sitekey = $googlerecaptcha['sitekey'];
						    ?>

						    <?php if ($recaptcha_version === 'v2'): ?>
						        <div class="captch">
						            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
						            <div class="g-recaptcha" data-sitekey="<?= $sitekey ?>"></div>
						        </div>
						    <?php elseif ($recaptcha_version === 'v3'): ?>
						        <script src="https://www.google.com/recaptcha/api.js?render=<?= $sitekey ?>"></script>
						        <script>
									grecaptcha.ready(function() {
									    grecaptcha.execute('<?= $sitekey ?>', {action: 'client_login'}).then(function(token) {
									        var input = document.getElementById('recaptcha_token');
									        if (input) {
									            input.value = token;
									        }
									    });
									});
						        </script>
						    <?php endif; ?>
						<?php endif; ?>

        				<div class="forgot mb-3 d-flex justify-content-between align-items-center">
        					<a href="<?= base_url('store/track_order') ?>" class="text-muted"><?= __('store.track_your_order') ?></a>
        					<a href="#" data-bs-toggle="modal" data-bs-target="#forgot-password-model" class="text-muted"><?= __('store.forget_password') ?>?</a>
        				</div>
        				<div class="mb-3">
        					<button class="btn btn-primary btn-submit w-100"><?= __('store.login') ?></button>
        				</div>
			        </div>
			    </div>
			    <input type="hidden" name="g-recaptcha-response" id="recaptcha_token">
			</form>
		</div>
		<div class="col-md-1 pt-md-5 text-center">
		    <h5 class="mt-5 pt-md-5 mb-5 mb-md-0"><?= __('store.or') ?></h5>
		</div>
		<div class="col-md-5">
			
			<form id="register-form">
			    <div class="card shadow-sm border-top-card">
			        <div class="card-body p-md-5">
			            <h5 class="sub-title display-5 mb-5"><?= __('store.create_a_new_account') ?></h5>
		            	<div class="mb-3">
        					<label class="control-label"><?= __('store.first_name') ?></label>
        					<input class="form-control" name="f_name" type="text">
        				</div>
        				<div class="mb-3">
        					<label class="control-label"><?= __('store.last_name') ?></label>
        					<input class="form-control" name="l_name" type="text">
        				</div>
        				<div class="mb-3">
        					<label class="control-label"><?= __('store.username') ?></label>
        					<input class="form-control" name="username" type="text">
        				</div>
        				<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
        				<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
        				<input type="hidden" name='PhoneNumberInput' id="phonenumber-input" value="" class="form-control" placeholder="<?= __('store.phone_number') ?>"  >
        				<div class="mb-3">
        					<label for=""><?= __('store.phone_number') ?></label>
        					<div>
        						<input onkeypress="return isNumberKey(event);" id="phone" type="text" name="phone" value="">
        					</div>
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

        					function isNumberKey(evt)
							{
							  var charCode = (evt.which) ? evt.which : event.keyCode;
							    if (charCode != 46 && charCode != 45 && charCode > 31
							    && (charCode < 48 || charCode > 57))
							     return false;

							  return true;
							}
        				</script>
        				<div class="mb-3">
        					<label class="control-label"><?= __('store.email') ?></label>
        					<input class="form-control" name="email" type="email">
        				</div>
        				<div class="mb-3">
        					<label class="control-label"><?= __('store.password') ?></label>
        					<input class="form-control" name="password" type="password">
        				</div>
        				<div class="mb-3">
        					<label class="control-label"><?= __('store.confirm_password') ?></label>
        					<input class="form-control" name="c_password" type="password">
        				</div>

						<?php if (!empty($googlerecaptcha['client_register']) && !empty($googlerecaptcha['sitekey'])): ?>
						    <script type="text/javascript">
						        var grecaptcha_register = 1;
						    </script>

						    <?php
						        $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
						        $sitekey = $googlerecaptcha['sitekey'];
						    ?>

						    <?php if ($recaptcha_version === 'v2'): ?>
						        <div class="captch">
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

        
        				<div class="mb-3 mt-4">
        					<button class="btn btn-primary btn-submit w-100"><?= __('store.register') ?></button>
        				</div>
			        </div>
			    </div>
			
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="forgot-password-model">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content rounded-0 border-0 shadow">
			<form action="store/forgot" method="post" id="forgot-password">
				<div class="modal-header border-0 pb-0">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body pb-5 px-5">
				    <h4 class="modal-title display-5 mb-4"><?= __('store.forgot_password_?') ?></h4>
					<div class="mb-3">
						<input type="text" name="forgot_email" class="form-control" placeholder="<?= __('store.email_address') ?>" />
						<span class="text-danger"></span>
					</div>
					<div class="text-end">
					    <button type="submit" class="btn btn-primary btn-submit"><?= __('store.submit') ?></button>
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
		beforeSend: function() {
			$this.find(".btn-submit").btn("loading");
		},
		complete: function() {
			$this.find(".btn-submit").btn("reset");
		},
		success: function(result) {
			$this.find(".has-error").removeClass("has-error");
			$this.find("span.text-danger").remove();

			if (result['success']) {
				location = '<?= $redirect_url ?>';
			}

			if (result['errors']) {
				$.each(result['errors'], function(i, j) {
					$ele = $this.find('[name="' + i + '"]');
					if ($ele.length) {
						$ele.closest(".mb-3").addClass("has-error");
						$ele.after("<span class='text-danger'>" + j + "</span>");
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
	$("#phone").closest(".mb-3").removeClass("has-error");
	$("#register-form .text-danger").remove();

	if (!is_valid) {
		$("#phone").closest(".mb-3").addClass("has-error");
		$("#phone").closest(".mb-3").find('> div').after("<span class='text-danger'>" + errorInnerHTML + "</span>");
		return false;
	}

	$.ajax({
		url: '<?= $base_url ?>ajax_register',
		type: 'POST',
		dataType: 'json',
		data: $this.serialize(),
		beforeSend: function() {
			$this.find(".btn-submit").btn("loading");
		},
		complete: function() {
			$this.find(".btn-submit").btn("reset");
		},
		success: function(result) {
			$this.find(".has-error").removeClass("has-error");
			$this.find("span.text-danger").remove();

			if (result['success']) {
				location = '<?= $redirect_url ?>';
			}

			if (result['errors']) {
				$.each(result['errors'], function(i, j) {
					$ele = $this.find('[name="' + i + '"]');
					if ($ele.length) {
						$ele.closest(".mb-3").addClass("has-error");
						$ele.after("<span class='text-danger'>" + j + "</span>");
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
		beforeSend: function() {
			$this.find(".btn-submit").btn("loading");
		},
		complete: function() {
			$this.find(".btn-submit").btn("reset");
		},
		success: function(json) {
			$this.find("span.text-danger").text('');
			if (json.success) {
				var _fp = document.getElementById('forgot-password-model');
				if (_fp && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
					bootstrap.Modal.getInstance(_fp)?.hide();
				}
				alert(json.success);
			}
			if (json.error) {
				$this.find("span.text-danger").text(json.error);
			}
		},
	})
	return false;
});
</script>
