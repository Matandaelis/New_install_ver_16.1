<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock-history me-3 fs-2"></i>
                            <div>
                                <h4 class="mb-1 fw-bold"><?= __('admin.cron_job') ?></h4>
                                <p class="mb-0 opacity-75"><?= __('admin.manage_scheduled_tasks') ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-light btn-sm" onclick="refreshCronPage()">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                <?= __('admin.refresh') ?>
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="showCronHelp()">
                                <i class="bi bi-question-circle me-2"></i>
                                <?= __('admin.help') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="cron-jobs-grid">
                                <div class="cron-job-item card mb-4 border-start border-primary border-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="cron-job-icon me-3">
                                                    <i class="bi bi-wallet2 text-primary fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= __('admin.wallet_transactions_cron_job') ?></h6>
                                                    <small class="text-muted"><?= __('admin.process_wallet_transactions') ?></small>
                                                </div>
                                            </div>
                                            <div class="cron-job-status">
                                                <span class="badge bg-primary"><?= __('admin.active') ?></span>
                                            </div>
                                        </div>
                                        <div class="cron-job-command">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg font-monospace" id="cron-trans" value="curl <?= base_url('/cronJob/transaction') ?>" readonly>
                                                <button class="btn btn-outline-primary" type="button" onclick="copyText('cron-trans')">
                                                    <i class="bi bi-clipboard me-2"></i>
                                                    <span><?= __('admin.copy') ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cron-job-item card mb-4 border-start border-success border-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="cron-job-icon me-3">
                                                    <i class="bi bi-arrow-repeat text-success fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= __('admin.auto_process_in_wallet_transaction_cron_job') ?></h6>
                                                    <small class="text-muted"><?= __('admin.auto_process_wallet_transactions') ?></small>
                                                </div>
                                            </div>
                                            <div class="cron-job-status">
                                                <span class="badge bg-success"><?= __('admin.active') ?></span>
                                            </div>
                                        </div>
                                        <div class="cron-job-command">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg font-monospace" id="cron-auto-wallet" value="curl <?= base_url('/cronJob/autoProcessInWalletTransactions') ?>" readonly>
                                                <button class="btn btn-outline-success" type="button" onclick="copyText('cron-auto-wallet')">
                                                    <i class="bi bi-clipboard me-2"></i>
                                                    <span><?= __('admin.copy') ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cron-job-item card mb-4 border-start border-warning border-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="cron-job-icon me-3">
                                                    <i class="bi bi-shield-check text-warning fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= __('admin.campaigns_validator_cron_job') ?></h6>
                                                    <small class="text-muted"><?= __('admin.validate_campaign_security') ?></small>
                                                </div>
                                            </div>
                                            <div class="cron-job-status">
                                                <span class="badge bg-warning"><?= __('admin.active') ?></span>
                                            </div>
                                        </div>
                                        <div class="cron-job-command">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg font-monospace" id="cron-camp" value="curl <?= base_url('/cronJob/check_campaign_security') ?>" readonly>
                                                <button class="btn btn-outline-warning" type="button" onclick="copyText('cron-camp')">
                                                    <i class="bi bi-clipboard me-2"></i>
                                                    <span><?= __('admin.copy') ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cron-job-item card mb-4 border-start border-info border-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="cron-job-icon me-3">
                                                    <i class="bi bi-bell text-info fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= __('admin.expire_package_notification') ?></h6>
                                                    <small class="text-muted"><?= __('admin.send_expiration_notifications') ?></small>
                                                </div>
                                            </div>
                                            <div class="cron-job-status">
                                                <span class="badge bg-info"><?= __('admin.active') ?></span>
                                            </div>
                                        </div>
                                        <div class="cron-job-command">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg font-monospace" id="cron-expi" value="curl <?= base_url('/cronJob/expire_package_notification') ?>" readonly>
                                                <button class="btn btn-outline-info" type="button" onclick="copyText('cron-expi')">
                                                    <i class="bi bi-clipboard me-2"></i>
                                                    <span><?= __('admin.copy') ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cron-job-item card mb-4 border-start border-secondary border-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="cron-job-icon me-3">
                                                    <i class="bi bi-person-check text-secondary fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= __('admin.check_vendor_limit') ?></h6>
                                                    <small class="text-muted"><?= __('admin.check_vendor_limitations') ?></small>
                                                </div>
                                            </div>
                                            <div class="cron-job-status">
                                                <span class="badge bg-secondary"><?= __('admin.active') ?></span>
                                            </div>
                                        </div>
                                        <div class="cron-job-command">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg font-monospace" id="cron-ven" value="curl <?= base_url('/cronJob/check_ven_limitation') ?>" readonly>
                                                <button class="btn btn-outline-secondary" type="button" onclick="copyText('cron-ven')">
                                                    <i class="bi bi-clipboard me-2"></i>
                                                    <span><?= __('admin.copy') ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cron-job-item card mb-4 border-start border-danger border-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="cron-job-icon me-3">
                                                    <i class="bi bi-trophy text-danger fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= __('admin.check_award_level') ?></h6>
                                                    <small class="text-muted"><?= __('admin.check_award_levels') ?></small>
                                                </div>
                                            </div>
                                            <div class="cron-job-status">
                                                <span class="badge bg-danger"><?= __('admin.active') ?></span>
                                            </div>
                                        </div>
                                        <div class="cron-job-command">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg font-monospace" id="cron-award" value="curl <?= base_url('/cronJob/check_award_level') ?>" readonly>
                                                <button class="btn btn-outline-danger" type="button" onclick="copyText('cron-award')">
                                                    <i class="bi bi-clipboard me-2"></i>
                                                    <span><?= __('admin.copy') ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cron-job-item card mb-4 border-start border-dark border-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="cron-job-icon me-3">
                                                    <i class="bi bi-heart-pulse text-danger fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= __('admin.affiliate_health_scores_cron_job') ?></h6>
                                                    <small class="text-muted"><?= __('admin.recalculate_affiliate_health_scores_cron_desc') ?></small>
                                                </div>
                                            </div>
                                            <div class="cron-job-status">
                                                <span class="badge bg-dark"><?= __('admin.active') ?></span>
                                            </div>
                                        </div>
                                        <div class="cron-job-command">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg font-monospace" id="cron-health" value="curl <?= base_url('/cronJob/recalculate_affiliate_health_scores') ?>" readonly>
                                                <button class="btn btn-outline-dark" type="button" onclick="copyText('cron-health')">
                                                    <i class="bi bi-clipboard me-2"></i>
                                                    <span><?= __('admin.copy') ?></span>
                                                </button>
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
    </div>
</div>

<script type="text/javascript">
function copyText(elementId) {
    var textBox = document.getElementById(elementId);
    var linkValue = textBox.value.replace(/^curl\s*/, '');
    textBox.value = linkValue;
    textBox.focus();
    textBox.select();

    try {
        document.execCommand("copy");
        var copyButton = document.querySelector("#" + elementId).nextElementSibling;
        var buttonSpan = copyButton.querySelector("span");
        var buttonIcon = copyButton.querySelector("i");
        
        buttonSpan.innerText = "<?= __('admin.copied') ?>";
        buttonIcon.className = "bi bi-check me-2";
        copyButton.classList.remove("btn-outline-primary", "btn-outline-success", "btn-outline-warning", "btn-outline-info", "btn-outline-secondary", "btn-outline-danger", "btn-outline-dark");
        copyButton.classList.add("btn-success");
        
        showToast('Success', 'Command copied to clipboard successfully', 'success', 2000);
        
        setTimeout(function(){
            textBox.value = 'curl ' + linkValue;
            textBox.setSelectionRange(0, 0);
            textBox.blur();
            buttonSpan.innerText = "<?= __('admin.copy') ?>";
            buttonIcon.className = "bi bi-clipboard me-2";
            copyButton.classList.remove("btn-success");
            
            if (elementId.includes('trans')) {
                copyButton.classList.add("btn-outline-primary");
            } else if (elementId.includes('auto-wallet')) {
                copyButton.classList.add("btn-outline-success");
            } else if (elementId.includes('camp')) {
                copyButton.classList.add("btn-outline-warning");
            } else if (elementId.includes('expi')) {
                copyButton.classList.add("btn-outline-info");
            } else if (elementId.includes('ven')) {
                copyButton.classList.add("btn-outline-secondary");
            } else if (elementId.includes('award')) {
                copyButton.classList.add("btn-outline-danger");
            } else if (elementId.includes('health')) {
                copyButton.classList.add("btn-outline-dark");
            }
        }, 2000);
    } catch (error) {
        console.error('Error copying text: ', error);
        showToast('Error', 'Failed to copy command to clipboard', 'error', 3000);
    }
}

function refreshCronPage() {
    location.reload();
}

function showCronHelp() {
    showToast('Cron Jobs Help', 'Cron jobs are scheduled tasks that run automatically. Copy the commands and add them to your server\'s cron scheduler.', 'info', 5000);
}
</script>


