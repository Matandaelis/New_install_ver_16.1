<?php $countryFieldMap = get_country_field_map(); ?>

<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/users/_wallet_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold"><?= __('admin.payment_gateway') ?> — <?= $details['title'] ?></h5>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admincontrol/withdrawal_payment_gateways') ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i><?= __('admin.back_to_list') ?>
                            </a>
                            <button id="toggle-uploader" class="btn btn-light btn-sm">
                                <i class="fas fa-save me-1"></i><?= __('admin.save_settings') ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0 fw-semibold">
                                        <i class="fas fa-sliders-h me-2"></i><?= __('admin.gateway_settings') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="" id='form-setting'>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?= __('admin.status') ?> <span class="text-danger">*</span></label>
                                            <select name="status" class="form-select" required>
                                                <option value="0"><?= __('admin.disabled') ?></option>
                                                <option value="1" <?= $setting_data['status'] == "1" ? 'selected' : '' ?>><?= __('admin.enabled') ?></option>
                                            </select>
                                        </div>

					<?php
						if($details['code'] == "bank_transfer")
						{
							?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?= __('admin.upload_withdrawal_proof_status') ?></label>
                                            <select class="form-select" name="withdrawal_proof">
                                                <option <?= (int)$setting_data['withdrawal_proof'] == '0' ? 'selected' : '' ?> value="0"><?= __('admin.disabled') ?></option>
                                                <option <?= (int)$setting_data['withdrawal_proof'] == '1' ? 'selected' : '' ?> value="1"><?= __('admin.enabled_and_optional') ?></option>
                                                <option <?= (int)$setting_data['withdrawal_proof'] == '2' ? 'selected' : '' ?> value="2"><?= __('admin.enabled_and_required') ?></option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?= __('admin.bank_transfer_mode') ?></label>
                                            <select class="form-select" name="bank_transfer_mode" id="bank_transfer_mode">
                                                <option value="global" <?= (isset($setting_data['bank_transfer_mode']) && $setting_data['bank_transfer_mode'] == 'global') ? 'selected' : '' ?>><?= __('admin.accept_all_countries') ?></option>
                                                <option value="specific" <?= (isset($setting_data['bank_transfer_mode']) && $setting_data['bank_transfer_mode'] == 'specific') ? 'selected' : '' ?>><?= __('admin.specific_countries') ?></option>
                                            </select>
                                        </div>

                                        <div class="specific-countries mb-3" style="<?= (!isset($setting_data['bank_transfer_mode']) || $setting_data['bank_transfer_mode'] != 'specific') ? 'display:none;' : '' ?>">
                                            <label class="form-label fw-semibold"><?= __('admin.select_countries_and_requirements') ?></label>
                                            <div class="border rounded p-3 bg-light">
                                                <?php 
                                                    $countryFieldMap = get_country_field_map(); 
                                                    $bank_transfer_countries = isset($setting_data['bank_transfer_countries']) ? 
                                                        json_decode($setting_data['bank_transfer_countries'], true) : [];

                                                    foreach ($countryFieldMap as $code => $field_key) {
                                                        $country_name = __('admin.'.strtolower($code));
                                                        $field_label = __('admin.'.strtolower($field_key));
                                                ?>
                                                    <div class="form-check mb-2">
                                                        <input type="checkbox" 
                                                            class="form-check-input" 
                                                            id="country_<?= strtolower($code) ?>" 
                                                            name="bank_transfer_countries[<?= $code ?>]" 
                                                            value="1" 
                                                            <?= (isset($bank_transfer_countries[$code]) && $bank_transfer_countries[$code] == 1) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="country_<?= strtolower($code) ?>">
                                                            <strong><?= $country_name ?></strong> - <?= $field_label ?>
                                                        </label>
                                                    </div>
                                                <?php } ?>

                                                <!-- Other Countries Option -->
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" 
                                                        class="form-check-input" 
                                                        id="country_other" 
                                                        name="bank_transfer_countries[OTHER]" 
                                                        value="1" 
                                                        <?= (isset($bank_transfer_countries['OTHER']) && $bank_transfer_countries['OTHER'] == 1) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="country_other">
                                                        <strong><?= __('admin.other_countries') ?></strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

					<script>
					$(document).ready(function() {
					    $('#bank_transfer_mode').on('change', function() {
					        if($(this).val() == 'specific') {
					            $('.specific-countries').show();
					        } else {
					            $('.specific-countries').hide();
					        }
					    });
					});
					</script>
								
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?= __('admin.custom_fields') ?></label>
                                            <div id="custom-field-section">
                                                <?php
                                                    if($setting_exist_status)
                                                    {
                                                        $count = 0;
                                                        $response_validate = json_decode($get_custom_fiels['response_validate']);
                                                        $get_custom_fiels = json_decode($get_custom_fiels['bt_custom_field']);
                                                        foreach ($get_custom_fiels as $key => $cus_value) {
                                                            $cus_value_read = str_replace("_"," ",$cus_value);
                                                            $cus_value_read = ucfirst($cus_value_read);

                                                            if(111==222)
                                                            {
                                                            ?>
                                                            <div class="row mb-3 removediv">
                                                                <div class="col-md-7">
                                                                    <input name="bt_custom_field[]" class="form-control bt_custom_field" type="text" value="<?=$cus_value_read;?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label"><?= __('admin.is_required') ?></label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <select class="form-select" name="response_validate[]">
                                                                        <option value="No" <?php if($response_validate[$count] == "No"){echo "selected";}?>><?= __('admin.no') ?></option>
                                                                        <option value="Yes" <?php if($response_validate[$count] == "Yes"){echo "selected";}?>><?= __('admin.yes') ?></option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-field-btn">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            }
                                                            $count++;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <div class="row mb-3 removediv">
                                                            <div class="col-md-7">
                                                                <input name="bt_custom_field[]" class="form-control bt_custom_field" type="text" value="" placeholder="<?= __('admin.please_enter_your_field_name') ?>">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label"><?= __('admin.is_required') ?></label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <select class="form-select" name="response_validate[]">
                                                                    <option value="No"><?= __('admin.no') ?></option>
                                                                    <option value="Yes"><?= __('admin.yes') ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>

                                                <div class="text-end">
                                                    <button id="add-more-field-btn" type="button" class="btn btn-outline-primary btn-sm" style="display: none;">
                                                        <i class="fas fa-plus me-1"></i><?= __('admin.add_more_fields') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
							<?php
						}
					?>

                                        <?= $html ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="card-title mb-0 fw-semibold">
                                        <i class="fas fa-info-circle me-2"></i><?= __('admin.gateway_info') ?>
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <img src="<?= base_url('/assets/images/payment-side2.jpg') ?>" class="img-fluid rounded mb-3" alt="Payment Gateway">
                                    <h6 class="fw-semibold"><?= $details['title'] ?></h6>
                                    <p class="text-muted small mb-3"><?= __('admin.gateway_description') ?></p>
                                    <div class="d-grid gap-2">
                                        <a href="<?= base_url('admincontrol/withdrawal_payment_gateways_doc') ?>" class="btn btn-outline-info btn-sm" target="_blank">
                                            <i class="fas fa-book me-1"></i><?= __('admin.documentation') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Form submission
        $("#toggle-uploader").click(function(){
            const $this = $(this);
            const $form = $("#form-setting");
            
            // Store original text
            if (!$this.data('original-text')) {
                $this.data('original-text', $this.html());
            }
            
            // Validate form
            if (!validateForm($form)) {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.please_fill_required_fields') ?>', 'error', 3000);
                return;
            }

            $.ajax({
                url:'<?= base_url("admincontrol/withdrawal_payment_gateways_setting_save/". $details['code']) ?>',
                type:'POST',
                dataType:'json',
                data: $form.serialize(),
                beforeSend:function(){
                    $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.saving') ?>...');
                    $form.find('.is-invalid').removeClass('is-invalid');
                    $form.find('.invalid-feedback').remove();
                },
                complete:function(){
                    $this.prop('disabled', false).html($this.data('original-text'));
                },
                success:function(json){
                    if (json['redirect']) {
                        showToast('<?= __('admin.success') ?>', '<?= __('admin.settings_saved_successfully') ?>', 'success', 3000);
                        setTimeout(() => window.location.href = json['redirect'], 1000);
                    }
                    
                    if(json['errors']){
                        showToast('<?= __('admin.error') ?>', '<?= __('admin.please_fix_errors') ?>', 'error', 5000);
                        $.each(json['errors'], function(i,j){
                            const $field = $form.find('[name="'+ i +'"]');
                            if($field.length){
                                $field.addClass("is-invalid");
                                if($field.parent(".input-group").length){
                                    $field.parent(".input-group").after("<span class='invalid-feedback'>"+ j[0] +"</span>");
                                } else{
                                    $field.after("<span class='invalid-feedback'>"+ j[0] +"</span>");
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Save Error:', error);
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_save_settings') ?>', 'error', 5000);
                }
            });
        });

        // Form validation function
        function validateForm($form) {
            let isValid = true;
            $form.find('[required]').each(function() {
                const $field = $(this);
                if (!$field.val().trim()) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<span class="invalid-feedback"><?= __('admin.this_field_is_required') ?></span>');
                    }
                    isValid = false;
                } else {
                    $field.removeClass('is-invalid');
                    $field.next('.invalid-feedback').remove();
                }
            });
            return isValid;
        }
    });

        // Add more custom fields
        $(document).on('click', '#add-more-field-btn', function(){
            const count = $('#custom-field-section .removediv').length;
            
            $(this).parent().before(`
                <div class="row mb-3 removediv">
                    <div class="col-md-7">
                        <input name="bt_custom_field[]" placeholder="<?= __('admin.please_enter_your_field_name') ?>" class="form-control bt_custom_field" type="text">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><?= __('admin.is_required') ?></label>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="response_validate[]">
                            <option value="No"><?= __('admin.no') ?></option>
                            <option value="Yes"><?= __('admin.yes') ?></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-field-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `);
        });

        // Remove custom fields
        $(document).on('click', '.remove-field-btn', function(){
            const count = $('#custom-field-section .removediv').length;
            if(count > 1) {
                $(this).closest(".removediv").remove();
            } else {
                $(this).closest(".removediv").remove();
            }
        });

        // Custom field input validation
        $("body").delegate(".bt_custom_field", "keypress", function(e){
            const regex = new RegExp("^[a-zA-Z0-9 ]+$");
            const str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
</script>