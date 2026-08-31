<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/users/_wallet_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                        <h5 class="card-title mb-0 fw-semibold"><?= __('admin.withdrawal_payment_gateways') ?></h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <button id="toggle-uploader" class="btn btn-outline-primary btn-sm" type="button">
                                <i class="fas fa-upload me-1"></i><?= __('admin.install_payment_gateway') ?>
                            </button>
                            <a href="<?= base_url('admincontrol/withdrawal_payment_gateways_doc') ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-book me-1"></i><?= __('admin.documentation') ?>
                            </a>
                            <a href="<?= base_url('assets/data/test_gateway_demo.zip') ?>" class="btn btn-outline-secondary btn-sm" download="test_gateway_demo.zip">
                                <i class="fas fa-download me-1"></i>Download Demo Gateway
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="plugin-uploader text-center mb-4 p-3 bg-light rounded d-none" id="plugin-uploader-section">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <p class="mb-3"><?= __('admin.if_have_plugin_in_zip_format_you_masy_insttall') ?><br>
                                <?= __('admin.if_want_to_creat_custom_payment_gateway') ?> <a href="<?= base_url('admincontrol/withdrawal_payment_gateways_doc') ?>" target="_blank" class="text-decoration-none"><?= __('admin.documentation') ?></a></p>
                                
                                <div class="alert alert-info text-start">
                                    <h6 class="fw-semibold mb-2">
                                        <i class="fas fa-lightbulb me-2"></i>Demo Gateway Available
                                    </h6>
                                    <p class="mb-2">We've created a complete demo payment gateway that you can download and test. It includes all required files and demonstrates the complete workflow.</p>
                                    <p class="mb-0">
                                        <strong>Features:</strong> Bank transfer simulation, admin settings, user forms, payment processing, and status management.
                                    </p>
                                </div>
                                <div class="d-flex justify-content-center gap-2">
                                    <div class="flex-grow-1">
                                        <input type="file" id="plugin-file" name="plugin" class="form-control" accept=".zip">
                                        <div class="invalid-feedback d-none" id="plugin-error"></div>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary" id="plugin-file-button" disabled>
                                            <i class="fas fa-download me-1"></i><?= __('admin.install_now') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw-semibold text-white"><?= __('admin.payment_method') ?></th>
                                    <th class="fw-semibold text-white text-center" width="80px"><?= __('admin.icon') ?></th>
                                    <th class="fw-semibold text-white text-center" width="100px"><?= __('admin.status') ?></th>
                                    <th class="fw-semibold text-white text-center" width="200px"><?= __('admin.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($payment_methods) == 0){ ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-credit-card fa-3x mb-3"></i>
                                                <h5><?= __('admin.no_payment_methods_available') ?></h5>
                                                <p class="mb-0"><?= __('admin.install_payment_gateway_to_get_started') ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($payment_methods as $key => $payment) { ?>
                                        <tr>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <img src="<?= base_url($payment['icon']) ?>" class="img-fluid" style="width: 32px; height: 32px; object-fit: contain;">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold"><?= $payment['title'] ?></h6>
                                                        <small class="text-muted"><?= $payment['code'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <img src="<?= base_url($payment['icon']) ?>" class="img-fluid" style="width: 40px; height: 40px; object-fit: contain;">
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input paymentstatus" type="checkbox"
                                                           <?= $payment['status'] == 1 ? 'checked' : '' ?>
                                                           data_code="<?=$payment['code']?>">
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group" role="group">
                                                    <?php if($payment['is_install'] == '1'){ ?>
                                                        <a href="<?= base_url('admincontrol/withdrawal_payment_gateways_edit/'. $payment['code']) ?>" 
                                                           class="btn btn-sm btn-outline-info" title="<?= __('admin.edit') ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <a onclick="return confirm('<?= __('admin.are_you_sure') ?>')" 
                                                       href="<?= base_url('admincontrol/withdrawal_payment_gateways_status_change/'. $payment['code']) ?>" 
                                                       class="btn btn-sm btn-<?= $payment['is_install'] == "1" ? "outline-danger" : "outline-success" ?>"
                                                       title="<?= $payment['is_install'] == "1" ? __('admin.un_install') : __('admin.install') ?>">
                                                        <i class="fas fa-<?= $payment['is_install'] == "1" ? "times" : "check" ?>"></i>
                                                    </a>
                                                    <a onclick="return confirm('<?= __('admin.are_you_sure') ?>')" 
                                                       href="<?= base_url('payment/delete_plugin/'.$payment['code']) ?>" 
                                                       class="btn btn-sm btn-outline-danger" title="<?= __('admin.delete') ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        console.log('Document ready - Initializing payment gateway page'); // Debug log
        
        // Test if button exists
        console.log('Toggle button exists:', $("#toggle-uploader").length > 0); // Debug log
        console.log('Uploader section exists:', $("#plugin-uploader-section").length > 0); // Debug log
        
        // Main toggle button handler (using document delegation for reliability)
        $(document).on('click', '#toggle-uploader', function(e) {
            e.preventDefault();
            console.log('Main toggle button clicked'); // Debug log
            const $section = $("#plugin-uploader-section");
            console.log('Section found:', $section.length > 0); // Debug log
            
            if ($section.hasClass('d-none')) {
                console.log('Showing uploader section'); // Debug log
                $section.removeClass('d-none').slideDown();
            } else {
                console.log('Hiding uploader section'); // Debug log
                $section.slideUp(function() {
                    $(this).addClass('d-none');
                });
            }
        });

        // File input change handler
        $("#plugin-file").on("change", function(){
            const file = this.files[0];
            const $btn = $("#plugin-file-button");
            const $error = $("#plugin-error");
            
            // Clear previous errors
            $error.addClass('d-none');
            $(this).removeClass('is-invalid');
            
            if (!file) {
                $btn.prop('disabled', true);
                return;
            }
            
            // Validate file type
            if (!file.name.toLowerCase().endsWith('.zip')) {
                $error.text('<?= __('admin.please_select_zip_file') ?>').removeClass('d-none');
                $(this).addClass('is-invalid');
                $btn.prop('disabled', true);
                return;
            }
            
            // Validate file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                $error.text('<?= __('admin.file_size_too_large') ?>').removeClass('d-none');
                $(this).addClass('is-invalid');
                $btn.prop('disabled', true);
                return;
            }
            
            $btn.prop('disabled', false);
        });

        // Plugin installation
        $("#plugin-file-button").on("click", function(evt) {
            evt.preventDefault();
            const $btn = $(this);
            const $fileInput = $("#plugin-file");
            const $error = $("#plugin-error");
            
            console.log('Install button clicked'); // Debug log
            
            if (!$fileInput[0].files[0]) {
                $error.text('<?= __('admin.please_select_file') ?>').removeClass('d-none');
                $fileInput.addClass('is-invalid');
                return;
            }
            
            // Store original text
            if (!$btn.data('original-text')) {
                $btn.data('original-text', $btn.html());
            }
            
            const formData = new FormData();
            formData.append("plugin", $fileInput[0].files[0]);
            
            console.log('Sending AJAX request...'); // Debug log
            
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.installing') ?>...');
            $error.addClass('d-none');
            
            $.ajax({
                url: '<?= base_url('payment/installPayementGateway') ?>',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(result) {
                    console.log('Installation Response:', result); // Debug log
                    
                    // Check if result is null or undefined
                    if (!result) {
                        console.error('Null or undefined response received');
                        $error.text('<?= __('admin.unexpected_response') ?>').removeClass('d-none');
                        return;
                    }
                    
                    if(result.status === 'error') {
                        $error.text(result.message || 'Unknown error occurred').removeClass('d-none');
                        $fileInput.addClass('is-invalid');
                    } else if(result.location) {
                        showToast('<?= __('admin.success') ?>', '<?= __('admin.payment_gateway_installed_successfully') ?>', 'success', 3000);
                        setTimeout(() => window.location.reload(), 1000);
                    } else if(result.warning) {
                        $error.html(result.warning).removeClass('d-none');
                    } else {
                        // Handle unexpected response
                        console.warn('Unexpected response format:', result);
                        $error.text('<?= __('admin.unexpected_response') ?>').removeClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Installation Error:', error);
                    $error.text('<?= __('admin.installation_failed') ?>').removeClass('d-none');
                    $fileInput.addClass('is-invalid');
                },
                complete: function() {
                    $btn.prop('disabled', false).html($btn.data('original-text'));
                }
            });
        });

        // Payment status toggle
        $('.paymentstatus').on('change', function() {
            const $this = $(this);
            const checked = $this.prop('checked');
            const code = $this.attr('data_code');
            const status = checked ? 1 : 0;
            
            // Store original state for rollback
            const originalState = !checked;
            
            $.ajax({
                url: '<?= base_url("admincontrol/withdrawal_payment_gateways_setting_save_ajax") ?>',
                type: 'POST',
                dataType: 'json',
                data: {"status": status, "code": code},
                beforeSend: function() {
                    $this.prop('disabled', true);
                },
                success: function(json) {
                    if (json['status'] === 'error') {
                        // Rollback on error
                        $this.prop('checked', originalState);
                        showToast('<?= __('admin.error') ?>', json['message'], 'error', 5000);
                    } else {
                        showToast('<?= __('admin.success') ?>', json.msg, 'success', 3000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Status Change Error:', error);
                    // Rollback on error
                    $this.prop('checked', originalState);
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_update_status') ?>', 'error', 5000);
                },
                complete: function() {
                    $this.prop('disabled', false);
                }
            });
        });
    });
</script>