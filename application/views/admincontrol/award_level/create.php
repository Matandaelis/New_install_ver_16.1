<?php if($award_level_status){ ?>

<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/award_level/_award_nav'); ?>
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">
                        <i class="bi bi-plus-circle text-primary me-2"></i>
                        <?= __('admin.create_award_level') ?>
                    </h2>
                    <p class="text-muted mb-0"><?= __('admin.create_new_award_level_desc') ?></p>
                </div>
                <div class="d-flex gap-2">
                    <?php 
                    // Check if AI helper is enabled
                    $ai_settings = $this->Product_model->getSettings('ai_helper');
                    $ai_enabled = $ai_settings && isset($ai_settings['ai_helper_enabled']) && $ai_settings['ai_helper_enabled'];
                    if ($ai_enabled): 
                    ?>
                    <button type="button" class="btn btn-primary" id="ai-smart-fill">
                        <i class="bi bi-robot me-1"></i>
                        <?= __('admin.ai_smart_fill') ?>
                    </button>
                    <?php endif; ?>
                    <a href="<?= base_url('admincontrol/award_level') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        <?= __('admin.back') ?>
                    </a>
                </div>
            </div>

            <?php if ($ai_enabled): ?>
            <!-- AI Progress & Results (initially hidden) -->
            <div id="ai-progress" class="mb-4" style="display: none;">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-robot text-primary me-2"></i>
                            <h6 class="mb-0"><?= __('admin.ai_processing') ?></h6>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                                 role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="text-muted mt-1 d-block" id="ai-status"><?= __('admin.analyzing_existing_levels') ?></small>
                    </div>
                </div>
            </div>
            <div id="ai-results" class="mb-4"></div>
            <?php endif; ?>

            <!-- Main Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-award me-2"></i>
                        <h5 class="mb-0"><?= __('admin.award_level_details') ?></h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?= base_url('admincontrol/create_award_level') ?>" method="post">
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
                                <div class="input-group input-group-lg">
                                    <input type="text" 
                                           class="form-control" 
                                           name="level_number" 
                                           placeholder="<?= __('admin.enter_level_number') ?>">
                                </div>
                                <div class="invalid-feedback error-message"></div>
                            </div>

                            <!-- Jump Level -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-skip-end text-warning me-1"></i>
                                    <?= __('admin.jump_level') ?>
                                    <i class="bi bi-info-circle text-muted ms-1 field-description" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_jump_level_desc') ?>"></i>
                                    <i class="bi bi-info-circle text-muted ms-1 field-description default-level-description d-none" 
                                       data-bs-toggle="tooltip" 
                                       title="<?= __('admin.award_level_jump_default_level_desc') ?>"></i>
                                </label>
                                <select class="form-select form-select-lg" name="jump_level">
                                    <option value=''><?= __('admin.choose_jump_level') ?></option>
                                    <option value="0"><?= __('admin.default') ?></option>
                                    <?php foreach($award_levels as $key => $value): ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['level_number'] ?></option>
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
                                           id="defaultLevel">
                                    <label class="form-check-label fw-semibold" for="defaultLevel">
                                        <?= __('admin.set_as_default_registration_level') ?>
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
                                        <?= __('admin.required_fields_note') ?>
                                    </small>
                                    <div class="btn-group" role="group">
                                        <button type="submit" class="btn btn-success btn-lg px-4">
                                            <i class="bi bi-check-lg me-1"></i>
                                            <?= __('admin.save') ?>
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg px-4" data-redirect='true'>
                                            <i class="bi bi-check-circle me-1"></i>
                                            <?= __('admin.save_and_close') ?>
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

    // AI Smart Fill button click handler
    $('#ai-smart-fill').on('click', function() {
        const $button = $(this);
        const originalText = $button.html();
        const $progress = $('#ai-progress');
        const $status = $('#ai-status');
        const $results = $('#ai-results');
        
        // Disable button and show progress
        $button.prop('disabled', true).html('<i class="bi bi-robot me-1"></i>Generating...');
        $progress.show();
        $results.empty();
        
        // Update progress bar
        $progress.find('.progress-bar').css('width', '25%');
        $status.text('<?= __("admin.analyzing_existing_levels") ?>');
        
        // Make AJAX call to AI smart fill
        $.ajax({
            url: '<?= base_url("admincontrol/ai_smart_fill") ?>',
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $progress.find('.progress-bar').css('width', '50%');
                $status.text('<?= __("admin.generating_suggestions") ?>');
            },
            success: function(response) {
                $progress.find('.progress-bar').css('width', '100%');
                $status.text('<?= __("admin.suggestions_ready") ?>');
                
                setTimeout(function() {
                    $progress.hide();
                    
                    if (response.success && response.suggestions) {
                        // Apply suggestions to form fields
                        const suggestions = response.suggestions;
                        
                        if (suggestions.level_number) {
                            $('input[name="level_number"]').val(suggestions.level_number).addClass('is-valid');
                        }
                        
                        if (suggestions.minimum_earning) {
                            $('input[name="minimum_earning"]').val(suggestions.minimum_earning).addClass('is-valid');
                        }
                        
                        if (suggestions.commission_rate) {
                            $('input[name="sale_comission_rate"]').val(suggestions.commission_rate).addClass('is-valid');
                        }
                        
                        if (suggestions.bonus) {
                            $('input[name="bonus"]').val(suggestions.bonus).addClass('is-valid');
                        }
                        
                        if (suggestions.jump_level) {
                            $('select[name="jump_level"]').val(suggestions.jump_level).trigger('change');
                        }
                        
                        if (suggestions.default_registration_level) {
                            $('input[name="default_registration_level"]').prop('checked', suggestions.default_registration_level).addClass('is-valid');
                        }
                        
                        // Show success message with AI usage info
                        let aiInfo = '';
                        if (response.ai_used) {
                            const aiStatus = response.ai_used === 'real_ai' ? 'Real AI' : 'Fallback';
                            const aiProvider = response.ai_provider || 'Unknown';
                            const realAiSetting = response.use_real_ai_setting !== undefined ? parseInt(response.use_real_ai_setting) : 1;
                            aiInfo = `
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <strong>AI Status:</strong> ${aiStatus} | <strong>Provider:</strong> ${aiProvider}
                                        ${response.api_key_configured ? ' | <span class="text-success"><i class="bi bi-check-circle"></i> API Key Configured</span>' : ' | <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> No API Key - Using Fallback</span>'}
                                        ${realAiSetting ? ' | <span class="text-info"><i class="bi bi-gear"></i> Real AI Mode Enabled</span>' : ' | <span class="text-secondary"><i class="bi bi-gear"></i> Real AI Mode Disabled</span>'}
                                    </small>
                                </div>
                            `;
                        }
                        
                        $results.html(`
                            <div class="alert alert-success alert-dismissible fade show ai-result-alert" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong><?= __("admin.ai_suggestions_applied") ?></strong>
                                ${aiInfo}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                        
                    } else {
                        // Show error message
                        $results.html(`
                            <div class="alert alert-warning alert-dismissible fade show ai-result-alert" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong><?= __("admin.ai_suggestions_failed") ?></strong>
                                ${response.message || '<?= __("admin.please_try_again") ?>'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    }
                    
                    // Re-enable button
                    $button.prop('disabled', false).html(originalText);
                    
                }, 500);
            },
            error: function(xhr, status, error) {
                $progress.hide();
                $results.html(`
                    <div class="alert alert-danger alert-dismissible fade show ai-result-alert" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong><?= __("admin.error_occurred") ?></strong>
                        <?= __("admin.please_try_again") ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                $button.prop('disabled', false).html(originalText);
            }
        });
    });

    // AI-powered level number suggestion
    function suggestNextLevelWithAI() {
        let existingLevels = <?= json_encode(array_column($award_levels, 'level_number')) ?>;
        
        // Show loading state on button
        let $aiBtn = $('#ai-suggest-btn');
        let $levelField = $('input[name="level_number"]');
        let originalBtnHtml = $aiBtn.html();
        
        $aiBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i><span class="d-none d-md-inline ms-1"><?= __("admin.thinking") ?></span>');
        
        // Clear any existing AI hints
        $('.ai-hint-container').empty();
        
        // Prepare existing levels context for AI
        let existingLevelsText = '';
        if (existingLevels.length === 0) {
            existingLevelsText = 'No existing levels. This will be the first level.';
        } else {
            existingLevelsText = 'Existing levels: ' + existingLevels.join(', ');
        }
        
        // Call dedicated AI level suggestion endpoint
        $.ajax({
            url: '<?= base_url("admincontrol/ai_suggest_level") ?>',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                existing_levels: existingLevels
            }),
            success: function(response) {
                console.log('AI Response:', response); // Debug response
                
                // Restore button
                $aiBtn.prop('disabled', false).html(originalBtnHtml);
                
                if (response.success && response.suggestion) {
                    let aiSuggestion = response.suggestion.trim();
                    
                    // AI suggested level name - auto-fill the field
                    $levelField.val(aiSuggestion);
                    $levelField.addClass('is-valid');
                    
                    // Show helpful hint in dedicated container
                    $('.ai-hint-container').html('<small class="text-success"><i class="bi bi-robot me-1"></i>AI suggested: <strong>' + aiSuggestion + '</strong></small>');
                } else {
                    // AI failed - show error message if available
                    console.log('AI failed:', response);
                    
                    // Show helpful error message
                    if (response.message) {
                        $('.ai-hint-container').html('<small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>' + response.message + '</small>');
                    } else {
                        $('.ai-hint-container').html('<small class="text-info"><i class="bi bi-info-circle me-1"></i>Using smart logic. Enter level manually if needed.</small>');
                    }
                }
            },
            error: function(xhr, status, error) {
                // Restore button
                $aiBtn.prop('disabled', false).html(originalBtnHtml);
                
                // AI failed, show error
                console.log('AI Error:', xhr.responseText);
                $('.ai-hint-container').html('<small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>AI connection failed. Enter level manually.</small>');
            }
        });
    }

    // Universal AI field suggestion function
    function suggestFieldWithAI(fieldType, $button, $hintContainer) {
        let existingLevels = <?= json_encode($award_levels) ?>;
        let originalBtnHtml = $button.html();
        let fieldName = fieldType === 'commission_rate' ? 'sale_comission_rate' : fieldType;
        let $field = $('input[name="' + fieldName + '"]');
        
        // Show loading state
        $button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');
        $hintContainer.empty();
        
        // Call AI endpoint
        $.ajax({
            url: '<?= base_url("admincontrol/ai_suggest_field") ?>',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                field_type: fieldType,
                existing_levels: existingLevels
            }),
            success: function(response) {
                console.log('AI Field Response:', response);
                
                // Restore button
                $button.prop('disabled', false).html(originalBtnHtml);
                
                if (response.success && response.suggestion) {
                    let suggestion = response.suggestion;
                    
                    // Fill the field
                    $field.val(suggestion).addClass('is-valid');
                    
                    // Show success message
                    let fieldDisplayName = getFieldDisplayName(fieldType);
                    $hintContainer.html('<small class="text-success"><i class="bi bi-robot me-1"></i>AI suggested ' + fieldDisplayName + ': <strong>' + suggestion + '</strong></small>');
                } else {
                    // Show error - likely no AI configured
                    if (response.message) {
                        $hintContainer.html('<small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>' + response.message + '</small>');
                    } else {
                        $hintContainer.html('<small class="text-info"><i class="bi bi-info-circle me-1"></i>AI analysis required. Please configure AI Helper in payment settings.</small>');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('AI Field Error:', error);
                console.log('AI Field Response:', xhr.responseText);
                
                // Restore button
                $button.prop('disabled', false).html(originalBtnHtml);
                
                // Show error
                $hintContainer.html('<small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>AI connection failed: ' + error + '</small>');
            }
        });
    }
    
    function getFieldDisplayName(fieldType) {
        switch (fieldType) {
            case 'minimum_earning': return 'minimum earning';
            case 'commission_rate': return 'commission rate';
            case 'bonus': return 'bonus';
            default: return 'value';
        }
    }

    // Form submission handler
    $("button[type='submit']").on('click',function(e){
        e.preventDefault();

        $this = $(this);
        let form = $(this).parents('form');

        // Reset validation states
        $("input,select").removeClass('is-invalid');
        $(".error-message").text('').hide();

        // Show loading state
        let originalText = $this.html();
        $this.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>' + '<?= __("admin.saving") ?>');

        $.ajax({
            url: form.attr('action'),
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
                    
                    // CRITICAL: Re-enable button after validation errors
                    $this.prop('disabled', false).html(originalText);
                    
                    // Reset all submit buttons to prevent any getting stuck
                    $("button[type='submit']").each(function() {
                        if ($(this)[0] !== $this[0]) { // Don't double-process the clicked button
                            $(this).prop('disabled', false);
                            // Restore original text for other buttons
                            if ($(this).data('redirect')) {
                                $(this).html('<i class="bi bi-check-circle me-1"></i><?= __("admin.save_and_close") ?>');
                            } else {
                                $(this).html('<i class="bi bi-check-lg me-1"></i><?= __("admin.save") ?>');
                            }
                        }
                    });
                } else {
                    if (result.status) {
                        // Success state
                        $this.removeClass('btn-success btn-primary').addClass('btn-success')
                             .html('<i class="bi bi-check-circle-fill me-1"></i>' + '<?= __("admin.saved") ?>');
                        
                        showPrintMessage(result.message, 'success');

                        let redirect = $this.data('redirect');
                        if (redirect) {
                            setTimeout(function() {
                                window.location = '<?= base_url("admincontrol/award_level") ?>';
                            }, 1500);
                        } else {
                            // Reset form for new entry
                            setTimeout(function() {
                                form[0].reset();
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
                
                // Reset all submit buttons in case of network error
                $("button[type='submit']").each(function() {
                    if ($(this)[0] !== $this[0]) {
                        $(this).prop('disabled', false);
                        if ($(this).data('redirect')) {
                            $(this).html('<i class="bi bi-check-circle me-1"></i><?= __("admin.save_and_close") ?>');
                        } else {
                            $(this).html('<i class="bi bi-check-lg me-1"></i><?= __("admin.save") ?>');
                        }
                    }
                });
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

    // Real-time validation feedback for level number input
    $('input[name="level_number"]').on('input', function() {
        let value = $(this).val().trim();
        let existingLevels = <?= json_encode(array_column($award_levels, 'level_number')) ?>;
        
        // Clear AI hint when user starts typing their own value
        $('.ai-hint-container').empty();
        
        if(value && value.length > 0) {
            if(existingLevels.includes(value)) {
                $(this).removeClass('is-valid').addClass('is-invalid');
                if ($(this).siblings('.duplicate-error').length === 0) {
                    $(this).after('<small class="text-danger duplicate-error mt-1"><i class="bi bi-exclamation-triangle me-1"></i>This level already exists. Please choose a different name.</small>');
                }
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $(this).siblings('.duplicate-error').remove();
            }
        } else {
            $(this).removeClass('is-valid is-invalid');
            $(this).siblings('.duplicate-error').remove();
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