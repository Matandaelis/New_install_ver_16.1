<div class="container-fluid add-client-page">
  <div class="row">
    <div class="col-12">
      
      <!-- Page Header -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h4 class="mb-0">
                <i class="bi bi-person-plus me-2"></i>
                <?= isset($client->id) ? __('admin.edit_client') : __('admin.add_client') ?>
              </h4>
              <small class="opacity-75">
                <?= isset($client->id) ? __('admin.edit_client_details') : __('admin.add_new_client_to_system') ?>
              </small>
            </div>
            <div class="d-flex gap-2">
              <a href="<?= base_url('admincontrol/listclients') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i><?= __('admin.back_to_list') ?>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Form -->
      <form id="addClientForm" method="post" action="" enctype="multipart/form-data" novalidate>
        <div class="row g-4">
          
          <!-- Personal Information Section -->
          <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                  <i class="bi bi-person me-2 text-primary"></i><?= __('admin.personal_information') ?>
                </h5>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  
                  <!-- First Name -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.first_name') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-person"></i></span>
                      <input 
                        name="firstname" 
                        value="<?= $client->firstname ?? '' ?>" 
                        class="form-control" 
                        type="text" 
                        required
                        placeholder="<?= __('admin.enter_first_name') ?>"
                      >
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <!-- Last Name -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.last_name') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-person"></i></span>
                      <input 
                        name="lastname" 
                        value="<?= $client->lastname ?? '' ?>" 
                        class="form-control" 
                        type="text" 
                        required
                        placeholder="<?= __('admin.enter_last_name') ?>"
                      >
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.email') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                      <input 
                        name="email" 
                        id="email" 
                        value="<?= $client->email ?? '' ?>" 
                        class="form-control" 
                        type="email" 
                        required
                        placeholder="<?= __('admin.enter_email_address') ?>"
                      >
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <!-- Username -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.username') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-at"></i></span>
                      <input 
                        name="username" 
                        id="username" 
                        value="<?= $client->username ?? '' ?>" 
                        class="form-control <?= isset($client->id) ? 'bg-light' : '' ?>" 
                        type="text" 
                        required
                        <?= isset($client->id) ? 'readonly' : '' ?>
                        placeholder="<?= __('admin.enter_username') ?>"
                      >
                      <div class="invalid-feedback"></div>
                    </div>
                    <?php if(isset($client->id)): ?>
                      <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i><?= __('admin.username_cannot_be_changed') ?>
                      </small>
                    <?php endif; ?>
                  </div>

                  <!-- Phone -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.phone') ?> <?= empty($client->phone) ? '<span class="text-danger">*</span>' : '' ?>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                      <input type="hidden" name="countrycode" id="countrycode" value="">
                      <input 
                        id="phone" 
                        class="form-control tel_input" 
                        name="phone" 
                        type="text" 
                        value="<?= $client->phone ?? '' ?>" 
                        <?= empty($client->phone) ? 'required' : '' ?>
                        placeholder="<?= __('admin.enter_phone_number') ?>"
                      >
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <!-- Status -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.status') ?> <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                      <select name="status" class="form-select" required>
                        <option value=""><?= __('admin.select_status') ?></option>
                        <option value="0" <?= (isset($client->status) && $client->status == '0') ? 'selected' : '' ?>>
                          <?= __('admin.disabled') ?>
                        </option>
                        <option value="1" <?= (!isset($client->status) || $client->status == '1') ? 'selected' : '' ?>>
                          <?= __('admin.active') ?>
                        </option>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <!-- Security & Actions Section -->
          <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                  <i class="bi bi-shield-lock me-2 text-warning"></i><?= __('admin.security_settings') ?>
                </h5>
              </div>
              <div class="card-body">
                
                <!-- Password -->
                <div class="mb-3">
                  <label class="form-label fw-semibold">
                    <?= __('admin.password') ?> 
                    <?= empty($client->email) ? '<span class="text-danger">*</span>' : '<small class="text-muted">('.__('admin.leave_blank_to_keep_current').')</small>' ?>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input 
                      name="password" 
                      id="password" 
                      class="form-control" 
                      type="password" 
                      <?= empty($client->email) ? 'required' : '' ?>
                      placeholder="<?= __('admin.enter_password') ?>"
                    >
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                      <i class="bi bi-eye"></i>
                    </button>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <?= __('admin.confirm_password') ?>
                    <?= empty($client->email) ? '<span class="text-danger">*</span>' : '' ?>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                    <input 
                      name="cnfrm_password" 
                      id="cnfrm_password" 
                      class="form-control" 
                      type="password" 
                      <?= empty($client->email) ? 'required' : '' ?>
                      placeholder="<?= __('admin.confirm_password') ?>"
                    >
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= isset($client->id) ? __('admin.update_client') : __('admin.create_client') ?>
                  </button>
                  <a href="<?= base_url('admincontrol/listclients') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i><?= __('admin.cancel') ?>
                  </a>
                </div>

                <?php if(isset($client->id)): ?>
                  <hr class="my-4">
                  <div class="text-center">
                    <small class="text-muted">
                      <i class="bi bi-info-circle me-1"></i>
                      <?= __('admin.client_id') ?>: <strong>#<?= $client->id ?></strong>
                    </small>
                  </div>
                <?php endif; ?>

              </div>
            </div>
          </div>

          <!-- Location Information Section -->
          <div class="col-12">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                  <i class="bi bi-geo-alt me-2 text-info"></i><?= __('admin.location_information') ?>
                </h5>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  
                  <!-- Country -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.country') ?>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-globe"></i></span>
                      <select name="country" id="country" class="form-select">
                        <option value=""><?= __('admin.select_country') ?></option>
                        <?php foreach ($countries as $country): ?>
                          <option value="<?= $country->id ?>" <?= (isset($client->ucountry) && $client->ucountry == $country->id) ? 'selected' : '' ?>>
                            <?= $country->name ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <!-- State -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.state') ?>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-map"></i></span>
                      <select name="state" id="state" class="form-select">
                        <option value=""><?= __('admin.select_state') ?></option>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <!-- City -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.city') ?>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-building"></i></span>
                      <input 
                        type="text" 
                        name="ucity" 
                        class="form-control" 
                        value="<?= $client->ucity ?? '' ?>"
                        placeholder="<?= __('admin.enter_city') ?>"
                      >
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <!-- Postal Code -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <?= __('admin.postal_code') ?>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-mailbox"></i></span>
                      <input 
                        class="form-control" 
                        name="uzip" 
                        type="text" 
                        value="<?= $client->uzip ?? '' ?>"
                        placeholder="<?= __('admin.enter_postal_code') ?>"
                      >
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                  <!-- Full Address -->
                  <div class="col-12">
                    <label class="form-label fw-semibold">
                      <?= __('admin.full_address') ?>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-house"></i></span>
                      <textarea 
                        class="form-control" 
                        name="twaddress" 
                        rows="3"
                        placeholder="<?= __('admin.enter_full_address') ?>"
                      ><?= $client->twaddress ?? '' ?></textarea>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

        </div>
      </form>

    </div>
  </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="d-none">
  <div class="loading-content">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
    </div>
    <p class="mt-3 mb-0"><?= __('admin.processing_request') ?>...</p>
  </div>
</div>

<!-- Include Required Libraries -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>

<script>
$(document).ready(function() {
    let telInput;
    
    // Initialize International Telephone Input
    function initTelInput() {
        const phoneInput = document.querySelector("#phone");
        if (phoneInput) {
            telInput = window.intlTelInput(phoneInput, {
                initialCountry: "auto",
                utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
                separateDialCode: true,
                geoIpLookup: function(success, failure) {
                    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                        const countryCode = (resp && resp.country) ? resp.country : "US";
                        success(countryCode);
                    });
                }
            });
        }
    }

    // Initialize phone input
    initTelInput();

    // Password visibility toggle
    $('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });

    // Country/State dropdown handling
    function loadStates(countryId, selectedState = '') {
        if (!countryId) {
            $('#state').html('<option value=""><?= __('admin.select_state') ?></option>');
            return;
        }

        $.ajax({
            url: '<?= base_url('admincontrol/getState') ?>',
            type: 'POST',
            data: { country_id: countryId, isId: true },
            beforeSend: function() {
                $('#state').prop('disabled', true).html('<option value=""><?= __('admin.loading') ?>...</option>');
            },
            success: function(html) {
                $('#state').html(html);
                if (selectedState) {
                    $('#state').val(selectedState);
                }
            },
            error: function() {
                $('#state').html('<option value=""><?= __('admin.error_loading_states') ?></option>');
                showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_states') ?>', 'error');
            },
            complete: function() {
                $('#state').prop('disabled', false);
            }
        });
    }

    // Load states on page load if country is selected
    const initialCountry = $('#country').val();
    const initialState = '<?= $client->state ?? '' ?>';
    if (initialCountry) {
        loadStates(initialCountry, initialState);
    }

    // Country change handler
    $('#country').on('change', function() {
        loadStates($(this).val());
    });

    // Form validation and submission
    $('#addClientForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return false;
        }

        // Show loading
        showLoading();
        
        // Submit form
        this.submit();
    });

    // Real-time validation
    $('input, select, textarea').on('blur change', function() {
        validateField($(this));
    });

    // Password confirmation validation
    $('#cnfrm_password').on('input', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        
        if (confirmPassword && password !== confirmPassword) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('<?= __('admin.passwords_do_not_match') ?>');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Email format validation
    $('#email').on('blur', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('<?= __('admin.invalid_email_format') ?>');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Username validation
    $('#username').on('blur', function() {
        const username = $(this).val();
        const usernameRegex = /^[a-zA-Z0-9_]+$/;
        
        if (username && !usernameRegex.test(username)) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('<?= __('admin.username_invalid_characters') ?>');
        } else if (username && username.length < 3) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('<?= __('admin.username_too_short') ?>');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Form validation function
    function validateForm() {
        let isValid = true;
        
        // Clear previous validation
        $('.is-invalid').removeClass('is-invalid');
        
        // Required field validation
        $('input[required], select[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('<?= __('admin.this_field_is_required') ?>');
                isValid = false;
            }
        });

        // Phone validation
        if (telInput && $('#phone').val().trim()) {
            if (!telInput.isValidNumber()) {
                $('#phone').addClass('is-invalid');
                $('#phone').siblings('.invalid-feedback').text('<?= __('admin.invalid_phone_number') ?>');
                isValid = false;
            } else {
                // Set country code
                const countryData = telInput.getSelectedCountryData();
                $('#countrycode').val(countryData.dialCode);
            }
        }

        // Password confirmation
        const password = $('#password').val();
        const confirmPassword = $('#cnfrm_password').val();
        if (password && password !== confirmPassword) {
            $('#cnfrm_password').addClass('is-invalid');
            $('#cnfrm_password').siblings('.invalid-feedback').text('<?= __('admin.passwords_do_not_match') ?>');
            isValid = false;
        }

        // Scroll to first error
        if (!isValid) {
            const firstError = $('.is-invalid').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
                firstError.focus();
            }
        }

        return isValid;
    }

    // Individual field validation
    function validateField($field) {
        const value = $field.val().trim();
        const isRequired = $field.attr('required') !== undefined;
        
        $field.removeClass('is-invalid');
        
        if (isRequired && !value) {
            $field.addClass('is-invalid');
            $field.siblings('.invalid-feedback').text('<?= __('admin.this_field_is_required') ?>');
            return false;
        }
        
        return true;
    }

    // Loading functions
    function showLoading() {
        $('#loadingOverlay').removeClass('d-none');
        $('#submitBtn').prop('disabled', true).html('<i class="bi bi-hourglass-split me-2"></i><?= __('admin.processing') ?>...');
    }

    function hideLoading() {
        $('#loadingOverlay').addClass('d-none');
        $('#submitBtn').prop('disabled', false).html('<i class="bi bi-check-circle me-2"></i><?= isset($client->id) ? __('admin.update_client') : __('admin.create_client') ?>');
    }

    // Number input validation
    window.isNumberKey = function(evt) {
        const charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    };

});
</script>

<style>
.add-client-page .card {
    transition: all 0.2s ease;
}

.add-client-page .card:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1) !important;
}

.add-client-page .input-group-text {
    background-color: var(--bs-light);
    border-color: var(--bs-border-color);
    color: var(--bs-primary);
}

.add-client-page .form-control:focus,
.add-client-page .form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
}

.add-client-page .btn {
    transition: all 0.2s ease;
}

.add-client-page .btn:hover {
    transform: translateY(-1px);
}

#loadingOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-content {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    text-align: center;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.add-client-page .is-invalid {
    border-color: var(--bs-danger);
}

.add-client-page .invalid-feedback {
    display: block;
    font-size: 0.875rem;
    color: var(--bs-danger);
}

@media (max-width: 768px) {
    .add-client-page .card-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    .add-client-page .input-group-text {
        min-width: 45px;
        justify-content: center;
    }
}
</style>