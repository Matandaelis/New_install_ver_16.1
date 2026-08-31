<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle me-3 fs-2"></i>
                            <div>
                                <h4 class="mb-1 fw-bold"><?= __('admin.system_issues') ?></h4>
                                <p class="mb-0 opacity-75"><?= __('admin.system_health_monitoring') ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admincontrol/system_status') ?>" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-shield-check me-2"></i>
                                <?= __('admin.view_all_status') ?>
                            </a>
                            <button class="btn btn-outline-light btn-sm" onclick="refreshSystemIssues()">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                <?= __('admin.refresh') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if ($total_issues == 0): ?>
                        <div class="text-center py-5">
                            <div class="system-healthy-state">
                                <i class="bi bi-shield-check text-success mb-4" style="font-size: 5rem;"></i>
                                <h3 class="text-success mb-3"><?= __('admin.all_systems_healthy') ?></h3>
                                <p class="text-muted mb-4"><?= __('admin.no_issues_detected') ?></p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="<?= base_url('admincontrol/dashboard') ?>" class="btn btn-success">
                                        <i class="bi bi-house me-2"></i>
                                        <?= __('admin.back_to_dashboard') ?>
                                    </a>
                                    <a href="<?= base_url('admincontrol/system_status') ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-shield-check me-2"></i>
                                        <?= __('admin.view_system_status') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center">
                                <div class="system-issues-count me-3">
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                        <?= $total_issues ?>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">
                                        <?= $total_issues ?> <?= $total_issues > 1 ? __('admin.issues_found') : __('admin.issue_found') ?>
                                    </h5>
                                    <small class="text-muted"><?= __('admin.requires_attention') ?></small>
                                </div>
                            </div>
                            <a href="<?= base_url('admincontrol/system_status') ?>" class="btn btn-primary">
                                <i class="bi bi-gear me-2"></i>
                                <?= __('admin.view_detailed_status') ?>
                            </a>
                        </div>
                        
                        <div class="system-issues-list">
                            <?php foreach ($issues as $index => $issue): ?>
                                <div class="system-issue-item card mb-3 border-warning">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="system-issue-icon me-3">
                                                    <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                                                </div>
                                                <div class="system-issue-content">
                                                    <h6 class="mb-1 fw-bold text-capitalize">
                                                        <?= str_replace('_', ' ', $issue['name']) ?>
                                                    </h6>
                                                    <small class="text-muted"><?= $issue['current_value'] ?></small>
                                                    <div class="mt-1">
                                                        <span class="badge bg-warning text-dark">
                                                            <?= __('admin.requires_fix') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="system-issue-actions">
                                                <a href="<?= base_url('admincontrol/system_status') ?>" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-tools me-1"></i>
                                                    <?= __('admin.fix_issue') ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-4 p-3 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                <small class="text-muted">
                                    <?= __('admin.issues_help_text') ?>
                                </small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function refreshSystemIssues() {
    location.reload();
}
</script> 