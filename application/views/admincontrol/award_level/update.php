<?php if($award_level_status){ ?>

<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/award_level/_award_nav'); ?>
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">
                        <i class="bi bi-pencil-square text-warning me-2"></i>
                        <?= __('admin.update_award_level') ?>
                    </h2>
                    <p class="text-muted mb-0">
                        <?= __('admin.editing_level') ?> <?= $award_level['level_number'] ?> - 
                        <?= __('admin.update_award_level_desc') ?>
                    </p>
                </div>
                <a href="<?= base_url('admincontrol/award_level') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    <?= __('admin.back') ?>
                </a>
            </div>

            <!-- Current Level Info -->
            <div class="card border-warning mb-4">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <h6 class="mb-0"><?= __('admin.current_level_information') ?></h6>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted d-block"><?= __('admin.level_number') ?>:</small>
                            <span class="badge bg-primary fs-6 px-3 py-2"><?= $award_level['level_number'] ?></span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block"><?= __('admin.minimum_earning') ?>:</small>
                            <span class="fw-bold text-success"><?= c_format($award_level['minimum_earning']) ?></span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block"><?= __('admin.commission_rate') ?>:</small>
                            <span class="badge bg-info"><?= $award_level['sale_comission_rate'] ?>%</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block"><?= __('admin.bonus') ?>:</small>
                            <span class="fw-bold text-primary"><?= c_format($award_level['bonus']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-pencil-square me-2"></i>
                        <h5 class="mb-0"><?= __('admin.update_award_level_details') ?></h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form>
                        <div class="row">
                            <!-- Level Number -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-hash text-primary me-1"></i>
                                    <?= __('admin.level_number') ?>
                                    <span class="text-danger">*</span>
                                    <i class="bi bi-info-circle text-muted ms-1" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_level_number_desc') ?>"></i>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       name="level_number" 
                                       value="<?= $award_level['level_number'] ?>"
                                       placeholder="<?= __('admin.enter_level_number') ?>">
                                <div class="invalid-feedback error-message"></div>
                            </div>

                            <!-- Jump Level -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-skip-end text-warning me-1"></i>
                                    <?= __('admin.jump_level') ?>
                                    <i class="bi bi-info-circle text-muted ms-1 field-description <?= ($award_level['jump_level'] == '0') ? 'd-none' : '' ?>" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_jump_level_desc') ?>"></i>
                                    <i class="bi bi-info-circle text-muted ms-1 field-description default-level-description <?= ($award_level['jump_level'] == '0') ? '' : 'd-none' ?>" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_jump_default_level_desc') ?>"></i>
                                </label>
                                <select class="form-select form-select-lg" name="jump_level">
                                    <option value=''><?= __('admin.choose_jump_level') ?></option>
                                    <option <?= ($award_level['jump_level'] == '0') ? 'selected' : '' ?> value="0"><?= __('admin.default') ?></option>
                                    <?php foreach($award_levels as $key => $value): ?>
                                        <?php $class = ($value['id'] == $award_level['jump_level']) ? 'selected' : '' ?>
                                        <option <?= $class ?> value="<?= $value['id'] ?>"><?= $value['level_number'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="invalid-feedback error-message"></div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Minimum Earning -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-currency-dollar text-success me-1"></i>
                                    <?= __('admin.minimum_earning') ?>
                                    <span class="text-danger">*</span>
                                    <i class="bi bi-info-circle text-muted ms-1" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_minimum_earning_desc') ?>"></i>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-success text-white fw-bold">
                                        <?= $CurrencySymbol ?>
                                    </span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="minimum_earning" 
                                           min="0" 
                                           step="0.01" 
                                           value="<?= $award_level['minimum_earning'] ?>"
                                           placeholder="0.00">
                                </div>
                                <div class="invalid-feedback error-message"></div>
                            </div>

                            <!-- Commission Rate -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-percent text-info me-1"></i>
                                    <?= __('admin.sale_comission_rate') ?>
                                    <span class="text-danger">*</span>
                                    <i class="bi bi-info-circle text-muted ms-1" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_sale_comission_rate_desc') ?>"></i>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-info text-white fw-bold">%</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="sale_comission_rate" 
                                           min="0" 
                                           max="100" 
                                           step="0.01" 
                                           value="<?= $award_level['sale_comission_rate'] ?>"
                                           placeholder="0.00">
                                </div>
                                <div class="invalid-feedback error-message"></div>
                            </div>
                        </div>

                        <!-- Bonus -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-gift text-purple me-1"></i>
                                    <?= __('admin.bonus') ?>
                                    <i class="bi bi-info-circle text-muted ms-1" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_bonus_desc') ?>"></i>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-purple text-white fw-bold">
                                        <?= $CurrencySymbol ?>
                                    </span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="bonus" 
                                           min="0" 
                                           step="0.01" 
                                           value="<?= $award_level['bonus'] ?>"
                                           placeholder="0.00">
                                </div>
                                <div class="invalid-feedback error-message"></div>
                            </div>

                            <!-- Default Level Checkbox -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold d-block">
                                    <i class="bi bi-star text-warning me-1"></i>
                                    <?= __('admin.registration_settings') ?>
                                    <i class="bi bi-info-circle text-muted ms-1" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_default_desc') ?>"></i>
                                </label>
                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="default_registration_level" 
                                           value="1" 
                                           id="defaultLevel"
                                           <?= ($award_level['default_registration_level']) ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="defaultLevel">
                                        <?= __('admin.set_as_default_registration_level') ?>
                                        <?php if($award_level['default_registration_level']): ?>
                                            <span class="badge bg-success ms-2">
                                                <i class="bi bi-check-circle me-1"></i>
                                                <?= __('admin.currently_default') ?>
                                            </span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <small class="text-muted">
                                    <?= __('admin.default_level_explanation') ?>
                                </small>
                                <div class="invalid-feedback error-message"></div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <?= __('admin.required_fields_note') ?> • 
                                        <?= __('admin.editing_level_id') ?>: <?= $award_level['id'] ?>
                                        <?php if (!empty($award_level['created_at']) && $award_level['created_at'] !== '0000-00-00 00:00:00'): ?>
                                            <br><i class="bi bi-calendar-plus text-success me-1"></i>
                                            <?= __('admin.created_at') ?>: <?= date('M j, Y g:i A', strtotime($award_level['created_at'])) ?>
                                        <?php endif; ?>
                                        <?php if (!empty($award_level['updated_at']) && $award_level['updated_at'] !== '0000-00-00 00:00:00' && $award_level['updated_at'] !== $award_level['created_at']): ?>
                                            <br><i class="bi bi-calendar-check text-primary me-1"></i>
                                            <?= __('admin.last_updated') ?>: <?= date('M j, Y g:i A', strtotime($award_level['updated_at'])) ?>
                                        <?php endif; ?>
                                    </small>
                                    <div class="btn-group" role="group">
                                        <button type="submit" class="btn btn-warning btn-lg px-4">
                                            <i class="bi bi-save me-1"></i>
                                            <?= __('admin.update') ?>
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg px-4" data-redirect='true'>
                                            <i class="bi bi-check-circle me-1"></i>
                                            <?= __('admin.update_and_close') ?>
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


<script type="text/javascript">
    // Initialize Bootstrap 5 tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Store original values for change detection
    let originalValues = {
        level_number: $('input[name="level_number"]').val(),
        jump_level: $('select[name="jump_level"]').val(),
        minimum_earning: $('input[name="minimum_earning"]').val(),
        sale_comission_rate: $('input[name="sale_comission_rate"]').val(),
        bonus: $('input[name="bonus"]').val(),
        default_registration_level: $('input[name="default_registration_level"]').is(':checked')
    };

    // Form submission handler
    $("button[type='submit']").on('click',function(e){
        e.preventDefault();

        $this = $(this);
        let form = $(this).parents('form');
        let url = form.attr('action');

        // Check if any changes were made
        let hasChanges = false;
        let currentValues = {
            level_number: $('input[name="level_number"]').val(),
            jump_level: $('select[name="jump_level"]').val(),
            minimum_earning: $('input[name="minimum_earning"]').val(),
            sale_comission_rate: $('input[name="sale_comission_rate"]').val(),
            bonus: $('input[name="bonus"]').val(),
            default_registration_level: $('input[name="default_registration_level"]').is(':checked')
        };

        for(let key in originalValues) {
            if(originalValues[key] != currentValues[key]) {
                hasChanges = true;
                break;
            }
        }

        if(!hasChanges) {
            showPrintMessage('<?= __("admin.no_changes_detected") ?>', 'info');
            return;
        }

        // Reset validation states
        $("input,select").removeClass('is-invalid');
        $(".error-message").text('').hide();

        // Show loading state
        let originalText = $this.html();
        $this.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>' + '<?= __("admin.updating") ?>');

        $.ajax({
            type:'POST',
            dataType:'json',
            data:form.serialize(),
            success:function(result){
                if(result.validation){
                    // Show validation errors with Bootstrap styling
                    $.each(result.validation,function(key,value){
                        let field = $("[name='"+key+"']");
                        field.addClass('is-invalid');
                        field.siblings('.error-message').text(value).show();
                        showPrintMessage(value, 'error');
                    });
                    
                    // Scroll to first error
                    let firstError = $('.is-invalid').first();
                    if(firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }
                    
                    $this.prop('disabled', false).html(originalText);
                } else {
                    if (result.status) {
                        // Success state
                        $this.removeClass('btn-warning btn-success').addClass('btn-success')
                             .html('<i class="bi bi-check-circle-fill me-1"></i>' + '<?= __("admin.updated") ?>');
                        
                        showPrintMessage(result.message, 'success');

                        // Update original values after successful save
                        originalValues = currentValues;

                        let redirect = $this.data('redirect');
                        if (redirect) {
                            setTimeout(function() {
                                window.location = '<?= base_url("admincontrol/award_level") ?>';
                            }, 1500);
                        } else {
                            // Reset button after success
                            setTimeout(function() {
                                $this.prop('disabled', false).html(originalText);
                            }, 2000);
                        }
                    } else {
                        showPrintMessage(result.message, 'error');
                        $this.prop('disabled', false).html(originalText);
                    }
                }
            },
            error: function() {
                showPrintMessage('<?= __("admin.error_occurred") ?>', 'error');
                $this.prop('disabled', false).html(originalText);
            }
        }); 
    });

    // Jump level description toggle
    $("select[name='jump_level']").on('change',function(){
        let value = $(this).val();
        let label = $(this).siblings('label');
        
        if(value == '0'){
            label.find('.field-description:not(.default-level-description)').addClass('d-none');
            label.find('.field-description.default-level-description').removeClass('d-none');
        } else {
            label.find('.field-description:not(.default-level-description)').removeClass('d-none');
            label.find('.field-description.default-level-description').addClass('d-none');
        }
    });

    // Real-time validation feedback
    $('input[name="level_number"]').on('input', function() {
        let value = $(this).val();
        if(value && !isNaN(value) && parseInt(value) > 0) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid');
        }
    });

    $('input[name="minimum_earning"], input[name="sale_comission_rate"]').on('input', function() {
        let value = parseFloat($(this).val());
        if(!isNaN(value) && value >= 0) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid');
        }
    });

    // Commission rate validation (0-100%)
    $('input[name="sale_comission_rate"]').on('input', function() {
        let value = parseFloat($(this).val());
        if(!isNaN(value) && value >= 0 && value <= 100) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else if(value > 100) {
            $(this).addClass('is-invalid');
            $(this).siblings('.error-message').text('<?= __("admin.commission_rate_max_100") ?>').show();
        } else {
            $(this).removeClass('is-valid');
        }
    });

    // Change tracking - highlight modified fields
    $('input, select').on('change input', function() {
        let fieldName = $(this).attr('name');
        let currentValue = $(this).is(':checkbox') ? $(this).is(':checked') : $(this).val();
        
        if(originalValues[fieldName] != currentValue) {
            $(this).addClass('border-warning border-2');
        } else {
            $(this).removeClass('border-warning border-2');
        }
    });
</script>

<?php } else { ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-circle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3"><?= __('admin.award_level_module_is_off') ?></h3>
                    <p class="text-muted mb-4"><?= __('admin.module_activation_message') ?></p>
                    <a href="<?= base_url('admincontrol/addons') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-power me-2"></i>
                        <?= __('admin.admin_click_here_to_activate') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
