<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header Section -->
            <div class="card bg-primary text-white border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-white mb-1">
                                <i class="fa fa-envelope me-2"></i><?= __('admin.bulk_email_users') ?>
                            </h4>
                            <p class="text-light opacity-75 mb-0"><?= __('admin.send_emails_to_multiple_users') ?></p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-primary px-3 py-2">
                                <span class="total-user">0</span> <?= __('admin.users_found') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-filter me-2"></i><?= __('admin.search_and_filter') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form action="" method="GET" id='search-form'>
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-globe me-1"></i><?= __('admin.country') ?>
                                </label>
                                <select class="form-select" name="country_id">
                                    <option value=""><?= __('admin.all_countries') ?></option>
                                    <?php foreach ($country_list as $key => $value): ?>
                                        <option value="<?= $value->id ?>" 
                                            <?= (isset($_GET['country_id']) && $_GET['country_id'] == $value->id) ? 'selected' : '' ?>>
                                            <?= $value->name ?> (<?= $value->sortname ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-lg-2 col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-user-tag me-1"></i><?= __('admin.user_type') ?>
                                </label>
                                <select class="form-select" name="user_type">
                                    <option value=""><?= __('admin.all_types') ?></option>
                                    <option value="all"><?= __('admin.all_users') ?></option>
                                    <option value="0"><?= __('admin.affiliates') ?></option>
                                    <option value="1"><?= __('user.vendor') ?></option>
                                </select>
                            </div>
                            
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-user me-1"></i><?= __('admin.name') ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control" name="name" 
                                           placeholder="<?= __('admin.search_by_name') ?>">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-envelope me-1"></i><?= __('admin.email') ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-at text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control" name="email" 
                                           placeholder="<?= __('admin.search_by_email') ?>">
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-12">
                                <label class="form-label d-block">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary flex-fill" onclick="getPage(1,this)">
                                        <i class="fa fa-search me-1"></i><?= __('admin.search') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Selection Summary -->
            <div class="selection-message d-none">
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-info-circle me-2"></i>
                            <span>
                                <strong><span class="selected-count">0</span></strong> <?= __('admin.users_selected_on_page') ?>
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary select-all-users">
                                <i class="fa fa-check-square me-1"></i><?= __('admin.select_all') ?> 
                                <span class="total-user">0</span> <?= __('admin.users') ?>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary clear-selection">
                                <i class="fa fa-times me-1"></i><?= __('admin.clear_selection') ?>
                            </button>
                            <button type="button" class="btn btn-sm btn-success email-to">
                                <i class="fa fa-envelope me-1"></i><?= __('admin.send_email') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">
                            <i class="fa fa-users me-2"></i><?= __('admin.users_list') ?>
                        </h6>
                        <button type="button" class="btn btn-success btn-sm email-to" disabled>
                            <i class="fa fa-envelope me-1"></i><?= __('admin.send_email') ?>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="message-box"></div>
                    
                    <div class="dimmer position-relative">
                        <div class="loader position-absolute top-50 start-50 translate-middle d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden"><?= __('admin.loading') ?></span>
                            </div>
                        </div>
                        
                        <div class="dimmer-content">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 user-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="fw-bold" style="width: 50px;">
                                                <div class="form-check">
                                                    <input class="form-check-input select-all" type="checkbox">
                                                </div>
                                            </th>
                                            <th class="fw-bold"><?= __('admin.user_info') ?></th>
                                            <th class="fw-bold text-center"><?= __('admin.country') ?></th>
                                            <th class="fw-bold"><?= __('admin.contact') ?></th>
                                            <?php if (!empty($data)): ?>
                                                <?php foreach ($data as $key => $value): ?>
                                                    <?php if($value['type'] == 'header') continue; ?>
                                                    <th class="fw-bold"><?= htmlspecialchars($value['label']) ?></th>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tr>  
                                    </thead> 
                                    <tbody>
                                        <tr>
                                            <td colspan="100%" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fa fa-search fs-1 mb-3"></i>
                                                    <h5><?= __('admin.search_users_to_start') ?></h5>
                                                    <p><?= __('admin.use_filters_above') ?></p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="100%">
                                                <div class="d-flex justify-content-between align-items-center p-3">
                                                    <div class="text-muted">
                                                        <small id="pagination-summary"></small>
                                                    </div>
                                                    <div class="pagination mb-0">
                                                        <?= isset($pagination) ? $pagination : '' ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="affiliateMailModel" tabindex="-1" aria-labelledby="affiliateMailModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="affiliateMailModelLabel">
                    <i class="fa fa-envelope me-2"></i><?= __('admin.compose_email') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="mail-form" method="post" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <!-- Recipients Section -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <label class="form-label fw-bold mb-0 me-2">
                                <i class="fa fa-users me-1"></i><?= __('admin.recipients') ?>
                            </label>
                            <span class="badge bg-primary px-3 py-2">
                                <span class="selected-count">0</span> <?= __('admin.users_selected') ?>
                            </span>
                        </div>
                        <div class="border rounded p-3 bg-light">
                            <textarea name="to" readonly class="form-control border-0 bg-transparent resize-none" 
                                      rows="3" placeholder="<?= __('admin.no_recipients_selected') ?>"></textarea>
                        </div>
                        <div class="form-text">
                            <i class="fa fa-info-circle me-1"></i><?= __('admin.recipients_help_text') ?>
                        </div>
                    </div>

                    <!-- Subject -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fa fa-tag me-1"></i><?= __('admin.subject') ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="subject" class="form-control form-control-lg" 
                               placeholder="<?= __('admin.enter_email_subject') ?>" required>
                    </div>

                    <!-- Message -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fa fa-file-text me-1"></i><?= __('admin.message') ?>
                            <span class="text-danger">*</span>
                        </label>
                        <textarea name="message" class="form-control summernote" 
                                  placeholder="<?= __('admin.compose_your_message') ?>" required></textarea>
                        <div class="form-text">
                            <i class="fa fa-lightbulb me-1"></i><?= __('admin.email_editor_help') ?>
                        </div>
                    </div>

                    <!-- Attachment -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fa fa-paperclip me-1"></i><?= __('admin.attachment') ?>
                            <span class="text-muted">(<?= __('admin.optional') ?>)</span>
                        </label>
                        <input type="file" class="form-control" id="attachment" name="attachment" 
                               accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif">
                        <div class="form-text">
                            <i class="fa fa-info-circle me-1"></i><?= __('admin.attachment_help') ?>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="text-muted">
                            <small>
                                <i class="fa fa-shield me-1"></i><?= __('admin.email_will_be_sent_securely') ?>
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i><?= __('admin.cancel') ?>
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg send-affiliate-email">
                                <i class="fa fa-send me-1"></i><?= __('admin.send_email') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    let selected = {};
    let all_emails = [];
    let isLoading = false;

    function updateEmailButtons() {
        const hasSelection = Object.keys(selected).length > 0;
        document.querySelectorAll('.email-to').forEach(btn => {
            btn.disabled = !hasSelection;
            btn.classList.toggle('btn-success', hasSelection);
            btn.classList.toggle('btn-outline-success', !hasSelection);
        });
    }

    function updateSelectionMessage() {
        const selectionMessage = document.querySelector('.selection-message');
        const selectedCount = Object.keys(selected).length;
        
        if (selectedCount === 0) {
            selectionMessage.classList.add('d-none');
        } else {
            selectionMessage.classList.remove('d-none');
            document.querySelectorAll('.selected-count').forEach(el => {
                el.textContent = selectedCount;
            });
        }

        const selectAllBtn = document.querySelector('.select-all-users');
        if (selectAllBtn) {
            selectAllBtn.style.display = selectedCount === all_emails.length ? 'none' : 'inline-block';
        }

        updateEmailButtons();
        updateCheckboxes();
    }

    function updateCheckboxes() {
        document.querySelectorAll('.select-single').forEach(checkbox => {
            checkbox.checked = selected.hasOwnProperty(checkbox.value);
        });

        const selectAllCheckbox = document.querySelector('.select-all');
        if (selectAllCheckbox) {
            const visibleCheckboxes = document.querySelectorAll('.select-single');
            const checkedCount = Array.from(visibleCheckboxes).filter(cb => cb.checked).length;
            selectAllCheckbox.checked = visibleCheckboxes.length > 0 && checkedCount === visibleCheckboxes.length;
        }
    }

    function clearSelection() {
        selected = {};
        updateSelectionMessage();
    }

    function showLoader(show = true) {
        const loader = document.querySelector('.dimmer .loader');
        if (loader) {
            loader.classList.toggle('d-none', !show);
        }
        isLoading = show;
    }

    // Event Listeners
    document.addEventListener('click', function(e) {
        if (e.target.matches('.clear-selection')) {
            clearSelection();
        }
        
        if (e.target.matches('.select-all-users')) {
            const btn = e.target;
            if (isLoading) return;
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...';
            
            fetch('<?= base_url("admincontrol/userslistmail") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_all_emails'
            })
            .then(response => response.json())
            .then(data => {
                if (data.emails) {
                    data.emails.forEach(email => {
                        selected[email] = email;
                    });
                    all_emails = data.emails;
                    updateSelectionMessage();
                    showToast('<?= __('admin.success') ?>', '<?= __('admin.all_users_selected') ?>', 'success', 3000);
                }
            })
            .catch(error => {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>', 'error', 4000);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-check-square me-1"></i><?= __('admin.select_all') ?> <span class="total-user">0</span> <?= __('admin.users') ?>';
            });
        }

        if (e.target.matches('.email-to')) {
            const selectedCount = Object.keys(selected).length;
            if (selectedCount === 0) {
                showToast('<?= __('admin.warning') ?>', '<?= __('admin.select_at_least_one_user_to_send_mail') ?>', 'warning', 4000);
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('affiliateMailModel'));
            const toField = document.querySelector('#affiliateMailModel textarea[name="to"]');
            const subjectField = document.querySelector('#affiliateMailModel input[name="subject"]');
            
            if (toField) toField.value = Object.keys(selected).join(', ');
            if (subjectField) subjectField.value = '';
            
            // Reset summernote if available
            const summernote = document.querySelector('#affiliateMailModel .summernote');
            if (summernote && typeof $(summernote).summernote === 'function') {
                $(summernote).summernote('reset');
            }
            
            modal.show();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.matches('.select-all')) {
            const isChecked = e.target.checked;
            document.querySelectorAll('.select-single').forEach(checkbox => {
                const email = checkbox.value;
                if (isChecked) {
                    selected[email] = email;
                } else {
                    delete selected[email];
                }
            });
            updateSelectionMessage();
        }

        if (e.target.matches('.select-single')) {
            const email = e.target.value;
            if (e.target.checked) {
                selected[email] = email;
            } else {
                delete selected[email];
            }
            updateSelectionMessage();
        }
    });

    // Form submission
    document.getElementById('mail-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('.send-affiliate-email');
        const originalText = submitBtn.innerHTML;
        
        // Clear previous errors
        this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        this.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.sending') ?>...';
        
        fetch('<?= base_url("admincontrol/sendAffiliateEmail") ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('<?= __('admin.success') ?>', data.success, 'success', 4000);
                bootstrap.Modal.getInstance(document.getElementById('affiliateMailModel')).hide();
                this.reset();
                clearSelection();
            } else if (data.status === 'error') {
                showToast('<?= __('admin.error') ?>', data.message, 'error', 4000);
            } else if (data.errors) {
                Object.entries(data.errors).forEach(([field, message]) => {
                    const input = this.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = message.replace(/<[^>]*>/g, '');
                        input.parentNode.appendChild(feedback);
                    }
                });
                
                const firstError = this.querySelector('.is-invalid');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        })
        .catch(error => {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>', 'error', 4000);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Search and pagination
    window.getPage = function(page, button) {
        if (isLoading) return;
        
        const btn = button ? $(button) : null;
        showLoader(true);
        
        if (btn) {
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...');
        }

        $.ajax({
            url: `<?= base_url("admincontrol/userslistmail") ?>?per_page=${page}`,
            type: 'POST',
            dataType: 'json',
            data: $("#search-form").serialize(),
            success: function(json) {
                $(".user-table tbody").html(json.html);
                $(".total-user").text(json.total || 0);
                $(".pagination").html(json.pagination || '');
                
                // Update pagination summary
                const summary = document.getElementById('pagination-summary');
                if (summary) {
                    if (json.pagination_summary) {
                        summary.innerHTML = json.pagination_summary;
                    } else if (json.total) {
                        const start = ((page - 1) * 10) + 1;
                        const end = Math.min(page * 10, json.total);
                        summary.textContent = `<?= __('admin.showing') ?> ${start}-${end} <?= __('admin.of') ?> ${json.total} <?= __('admin.users') ?>`;
                    }
                }
                
                clearSelection();
            },
            error: function() {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_users') ?>', 'error', 4000);
            },
            complete: function() {
                showLoader(false);
                if (btn) {
                    btn.prop('disabled', false).html(btn.data('original-text') || '<?= __('admin.search') ?>');
                }
            }
        });
    };

    // Note: Pagination click handlers are managed by the utility helper's onclick attributes

    // Initialize
    updateEmailButtons();
    
    // Load initial data
    getPage(1);
});
</script>