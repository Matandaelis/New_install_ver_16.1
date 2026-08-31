<div class="container-fluid backup-page">
    <div class="row">
        <div class="col-12">

            <!-- Header Card with Upload & Reset Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-hdd-fill me-2 fs-4"></i>
                        <div>
                            <h4 class="mb-0 fw-bold"><?= __('admin.backup_panel') ?></h4>
                            <small class="opacity-75"><?= __('admin.manage_backups_and_data') ?></small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <!-- Upload Backup Section -->
                        <div class="col-md-6">
                            <div class="border rounded p-4 h-100 bg-light">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-cloud-upload text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= __('admin.upload_backup_file_zip') ?></h5>
                                        <small class="text-muted"><?= __('admin.restore_from_file') ?></small>
                                    </div>
                                </div>
                                <form enctype="multipart/form-data" method="POST" action="">
                                    <div class="mb-3">
                                        <input type="file" class="form-control" name="backup_file" accept=".zip" required>
                                        <div class="form-text"><?= __('admin.accepted_format') ?>: .zip</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-upload me-2"></i><?= __('admin.upload') ?>
                                    </button>
                </form>
            </div>
        </div>

                        <!-- Reset Data Actions -->
                        <div class="col-md-6">
                            <div class="border rounded p-4 h-100 bg-light">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= __('admin.reset_all_data') ?></h5>
                                        <small class="text-muted"><?= __('admin.dangerous_operations') ?></small>
    </div>
            </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-warning open-databascommieclear">
                                        <i class="bi bi-arrow-repeat me-2"></i><?= __('admin.reset_commission_data') ?>
                        </button>
                                    <button class="btn btn-danger open-databaseclear">
                                        <i class="bi bi-trash me-2"></i><?= __('admin.reset_all_script_data') ?>
                        </button>
                                    <button onclick="window.location.href='<?= base_url("admincontrol/refactor_database"); ?>'" class="btn btn-info">
                                        <i class="bi bi-gear me-2"></i><?= __('admin.refactor_database_structure') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- Backup Files List -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <i class="bi bi-archive me-2 fs-5 text-primary"></i>
                            <h5 class="mb-0 fw-bold"><?= __('admin.backup_files') ?></h5>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button id="backup-db-btn" class="btn btn-success btn-sm">
                                <span id="backup-db-spinner" class="spinner-border spinner-border-sm me-2 d-none"></span>
                                <i id="backup-db-icon" class="bi bi-database me-1"></i><?= __('admin.get_backup') ?>
                            </button>
                            <button id="backup-script-btn" class="btn btn-warning btn-sm">
                                <span id="backup-script-spinner" class="spinner-border spinner-border-sm me-2 d-none"></span>
                                <i id="backup-script-icon" class="bi bi-file-earmark-zip me-1"></i><?= __('admin.backup_full_script') ?>
                            </button>
                        </div>
                </div>
            </div>

                <div class="card-body p-0">
                <?php if (empty($backups) && empty($script_backups)) { ?>
                        <div class="text-center py-5 backup-empty-state">
                            <i class="bi bi-folder2-open display-1 text-muted opacity-50"></i>
                            <h3 class="text-muted mt-3"><?= __('admin.no_data_found') ?></h3>
                            <p class="text-muted"><?= __('admin.create_first_backup') ?></p>
                    </div>
                <?php } else { ?>
                    <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                        <th class="ps-4"><?= __('admin.file_name') ?></th>
                                    <th><?= __('admin.date_time') ?></th>
                                    <th><?= __('admin.size') ?></th>
                                    <th><?= __('admin.type') ?></th>
                                        <th class="text-center pe-4"><?= __('admin.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backups as $backup) { ?>
                                        <tr class="backup-row">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-file-earmark-zip text-info me-2 fs-5"></i>
                                                    <strong><?= htmlspecialchars($backup['file']) ?></strong>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="bi bi-clock me-1 text-muted"></i>
                                                <?= htmlspecialchars($backup['date']) ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?= htmlspecialchars($backup['size']) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= __('admin.mysql') ?></span>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('admincontrol/backup/download?file_name=' . urlencode($backup['file'])) ?>" 
                                                       class="btn btn-outline-success" 
                                                       title="<?= __('admin.download') ?>">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <button onclick="restoreBackup('<?= htmlspecialchars(addslashes($backup['file'])) ?>')" 
                                                            class="btn btn-outline-primary" 
                                                            title="<?= __('admin.restore') ?>">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                    <button onclick="deleteBackup('<?= htmlspecialchars(addslashes($backup['file'])) ?>', 'mysql')" 
                                                            class="btn btn-outline-danger" 
                                                            title="<?= __('admin.delete') ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php foreach ($script_backups as $backup) { ?>
                                        <tr class="backup-row">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-file-earmark-code text-secondary me-2 fs-5"></i>
                                                    <strong><?= htmlspecialchars($backup['file']) ?></strong>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="bi bi-clock me-1 text-muted"></i>
                                                <?= htmlspecialchars($backup['date']) ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?= htmlspecialchars($backup['size']) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?= __('admin.script') ?></span>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('admincontrol/backup/download_script?file_name=' . urlencode($backup['file'])) ?>" 
                                                       class="btn btn-outline-success" 
                                                       title="<?= __('admin.download') ?>">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <button onclick="deleteBackup('<?= htmlspecialchars(addslashes($backup['file'])) ?>', 'script')" 
                                                            class="btn btn-outline-danger" 
                                                            title="<?= __('admin.delete') ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
            </div>

        </div>
    </div>
</div>

<!-- Reset All Data Modal -->
<div class="modal fade" id="model-databaseclear" tabindex="-1" aria-labelledby="model-databaseclear-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="content-view">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i><?= __('admin.reset_all_script_data') ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle me-2"></i>
                    <?= __('admin.reset_all_script_data_warning'); ?>                    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.cancel_action'); ?></button>
                    <button type="button" class="btn btn-danger cleandatabase"><?= __('admin.yes_reset_data'); ?></button>
                </div>
            </div>
            <div class="password-view d-none">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-shield-lock me-2"></i><?= __('admin.reset_all_script_data'); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold"><?= __('admin.enter_admin_password'); ?></label>
                        <input type="password" name="admin_password" id="admin_password" class="form-control" placeholder="<?= __('admin.password') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.cancel_action'); ?></button>
                    <button type="button" class="btn btn-danger cleandatabase"><?= __('admin.yes_reset_data'); ?></button>
                </div>
            </div>
            <div class="finalconfirm-view d-none">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-octagon me-2"></i><?= __('admin.reset_data_confirmation'); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <p class="mb-2"><strong><?= __('admin.reset_data_warning'); ?></strong></p>
                        <p class="mb-0 text-danger fw-bold"><?= __('admin.reset_data_agreed'); ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.cancel_action'); ?></button>
                    <button type="button" class="btn btn-danger final-cleandatabase"><?= __('admin.erase_all_data'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Commission Data Modal -->
<div class="modal fade" id="model-databascommieclear" tabindex="-1" aria-labelledby="model-databascommieclear-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="content-view">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i><?= __('admin.reset_commission_data'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle me-2"></i>
                    <?= __('admin.reset_commission_data_warning'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.cancel_action'); ?></button>
                    <button type="button" class="btn btn-warning databascommieclear"><?= __('admin.yes_reset_comm_data'); ?></button>
                </div>
            </div>
            <div class="password-view d-none">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-shield-lock me-2"></i><?= __('admin.reset_all_commission_data'); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold"><?= __('admin.enter_admin_password'); ?></label>
                        <input type="password" name="admin_password" class="form-control commission-password" placeholder="<?= __('admin.password') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.cancel_action'); ?></button>
                    <button type="button" class="btn btn-warning databascommieclear"><?= __('admin.yes_reset_comm_data'); ?></button>
                </div>
            </div>
            <div class="finalconfirm-view d-none">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-exclamation-octagon me-2"></i><?= __('admin.reset_data_confirmation'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <p class="mb-2"><strong><?= __('admin.reset_data_warning'); ?></strong></p>
                        <p class="mb-0 fw-bold"><?= __('admin.reset_data_agreed'); ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.cancel_action'); ?></button>
                    <button type="button" class="btn btn-warning final-databascommieclear"><?= __('admin.erase_all_comm_data'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Backup Database Button
document.getElementById("backup-db-btn").addEventListener("click", function() {
    const btn = this;
    const spinner = document.getElementById("backup-db-spinner");
    const icon = document.getElementById("backup-db-icon");
    
    spinner.classList.remove("d-none");
    icon.classList.add("d-none");
    btn.setAttribute("disabled", "disabled");
    
    if(typeof showToast === 'function') {
        showToast('<?= __('admin.processing') ?>', '<?= __('admin.creating_database_backup') ?>...', 'info', 3000);
    }
    
    setTimeout(function() {
        window.location.href = '<?= base_url("admincontrol/backup/getbackup") ?>';
    }, 500);
});

// Backup Script Button
document.getElementById("backup-script-btn").addEventListener("click", function() {
    const btn = this;
    const spinner = document.getElementById("backup-script-spinner");
    const icon = document.getElementById("backup-script-icon");
    
    spinner.classList.remove("d-none");
    icon.classList.add("d-none");
    btn.setAttribute("disabled", "disabled");
    
    if(typeof showToast === 'function') {
        showToast('<?= __('admin.processing') ?>', '<?= __('admin.creating_script_backup') ?>...', 'info', 3000);
    }
    
    setTimeout(function() {
        window.location.href = '<?= base_url("admincontrol/backup/backup_script") ?>';
    }, 500);
});

// Restore Backup Function
function restoreBackup(fileName) {
    if(typeof showToast === 'function') {
        showToast('<?= __('admin.confirm_action') ?>', '<?= __('admin.restore_file_confirm') ?>', 'warning', 5000);
    }
    
    setTimeout(function() {
        if(confirm('<?= __('admin.restore_file_confirm') ?>')) {
            window.location.href = '<?= base_url("admincontrol/backup/restore?file_name=") ?>' + encodeURIComponent(fileName);
        }
    }, 100);
}

// Delete Backup Function
function deleteBackup(fileName, type) {
    window.confirmDelete('<?= __('admin.delete_file_confirm') ?>', function() {
        const url = type === 'script' 
            ? '<?= base_url("admincontrol/backup/delete_script?file_name=") ?>' 
            : '<?= base_url("admincontrol/backup/delete?file_name=") ?>';
        
        if(typeof showToast === 'function') {
            showToast('<?= __('admin.deleting') ?>', '<?= __('admin.please_wait') ?>...', 'info', 2000);
        }
        
        setTimeout(function() {
            window.location.href = url + encodeURIComponent(fileName);
        }, 500);
    });
}

// Reset All Data Modal
$(".open-databaseclear").on("click", function() {
        $("#model-databaseclear").modal("show");
    const $container = $("#model-databaseclear");
        $container.find(".content-view").removeClass('d-none');
        $container.find(".password-view,.finalconfirm-view").addClass('d-none');
    });
    
let password_confirm = false;

$(".cleandatabase").on("click", function() {
    const $this = $(this);
    const $container = $("#model-databaseclear");
    
    if($container.find(".password-view").hasClass("d-none")) {
        $container.find(".password-view").removeClass('d-none');
        $container.find(".content-view").addClass('d-none');
        return true;
    }
    
    $.ajax({
        url: '<?= base_url("admincontrol/clear_tables") ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            admin_password: $("#admin_password").val(),
            password_confirm: true
        },
        beforeSend: function() {
            $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $this.prop('disabled', false).html($this.data('original-text') || '<?= __('admin.submit') ?>');
        },
        success: function(json) {
            if(json['success']) {
                password_confirm = true;
                $container.find(".finalconfirm-view").removeClass('d-none');
                $container.find(".content-view,.password-view").addClass('d-none');
            }
            
            $container.find(".has-error").removeClass("has-error");
            $container.find("span.text-danger").remove();
            
            if(json['status'] === 'error') {
                $('#model-databaseclear').modal('hide');
                if(typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', json['message'], 'error', 5000);
                }
            } else if(json['errors']) {
                $.each(json['errors'], function(i, j) {
                    const $ele = $container.find('[name="'+ i +'"]');
                    if($ele.length) {
                        $ele.parents(".form-group,.mb-3").addClass("has-error");
                        $ele.after("<span class='text-danger d-block mt-1'>"+ j +"</span>");
                    }
                });
            }
        }
    });
});

$(".final-cleandatabase").on("click", function() {
    const $this = $(this);
    
    if(password_confirm) {
            $.ajax({
            url: '<?= base_url("admincontrol/clear_tables") ?>',
            type: 'POST',
            dataType: 'json',
            data: {admin_password: $("#admin_password").val()},
            beforeSend: function() {
                $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
            },
            complete: function() {
                $this.prop('disabled', false).html($this.data('original-text') || '<?= __('admin.submit') ?>');
            },
            success: function(json) {
                if(json['success']) {
                        $("#model-databaseclear").modal('hide');
                        
                        if(typeof showToast === 'function') {
                            showToast('<?= __('admin.success') ?>', '<?= __("admin.data_was_deleted_successfully") ?> - <?= __("admin.auto_logout_in") ?> 3 <?= __("admin.seconds") ?>...', 'success', 4000);
                        }
                        
                        let countdown = 3;
                        const countdownInterval = setInterval(function() {
                            countdown--;
                            if(countdown < 0) {
                                clearInterval(countdownInterval);
                                $.ajax({
                                    url: '<?= base_url("admincontrol/destroy_session") ?>',
                                    type: 'POST',
                                    success: function() {
                                        localStorage.setItem('force_admin_logout', Date.now());
                                        window.location.reload();
                                    }
                                });
                            }
                        }, 1000);
                    }
            }
        });
    }
});

// Reset Commission Data Modal
$(".open-databascommieclear").on("click", function() {
        $("#model-databascommieclear").modal("show");
    const $container = $("#model-databascommieclear");
        $container.find(".content-view").removeClass('d-none');
        $container.find(".password-view,.finalconfirm-view").addClass('d-none');
    });
    
$(".databascommieclear").on("click", function() {
    const $this = $(this);
    const $container = $("#model-databascommieclear");
    
    if($container.find(".password-view").hasClass("d-none")) {
            $container.find(".password-view").removeClass('d-none');
            $container.find(".content-view").addClass('d-none');
            return true;
        }

        $.ajax({
        url: '<?= base_url("admincontrol/clear_commission_tables") ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            admin_password: $container.find(".commission-password").val(),
            password_confirm: true
        },
        beforeSend: function() {
            $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $this.prop('disabled', false).html($this.data('original-text') || '<?= __('admin.submit') ?>');
        },
        success: function(json) {
            if(json['success']) {
                password_confirm = true;
                    $container.find(".finalconfirm-view").removeClass('d-none');
                    $container.find(".content-view,.password-view").addClass('d-none');
                }
                
                $container.find(".has-error").removeClass("has-error");
                $container.find("span.text-danger").remove();

                if(json['status'] === 'error') {
                $('#model-databascommieclear').modal('hide');
                if(typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', json['message'], 'error', 5000);
                }
            } else if(json['errors']) {
                $.each(json['errors'], function(i, j) {
                    const $ele = $container.find('[name="'+ i +'"]');
                    if($ele.length) {
                        $ele.parents(".form-group,.mb-3").addClass("has-error");
                        $ele.after("<span class='text-danger d-block mt-1'>"+ j +"</span>");
                    }
                });
            }
        }
    });
});

$(".final-databascommieclear").on("click", function() {
    const $this = $(this);
    const $container = $("#model-databascommieclear");
    
    if(password_confirm) {
        $.ajax({
            url: '<?= base_url("admincontrol/clear_commission_tables") ?>',
            type: 'POST',
            dataType: 'json',
            data: {admin_password: $container.find(".commission-password").val()},
            beforeSend: function() {
                $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
            },
            complete: function() {
                $this.prop('disabled', false).html($this.data('original-text') || '<?= __('admin.submit') ?>');
            },
            success: function(json) {
                if(json['success']) {
                    if(typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', '<?= __("admin.data_was_deleted_successfully") ?>', 'success', 3000);
                    }
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            }
        });
        } 
    });
</script>