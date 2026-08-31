<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header Section -->
            <div class="card bg-primary text-white border-0 shadow-sm mb-4">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-tools fs-1 opacity-75"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-2"><?= __('admin.troubleshoot_guide') ?></h3>
                    <p class="text-white opacity-75 mb-0"><?= __('admin.troubleshoot_description') ?></p>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-funnel me-2"></i>
                        <?= __('admin.search_and_filter') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchTroubleshoot" 
                                       placeholder="<?= __('admin.search_issues_solutions') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterByType">
                                <option value=""><?= __('admin.all_types') ?></option>
                                <option value="error"><?= __('admin.errors') ?></option>
                                <option value="warning"><?= __('admin.warnings') ?></option>
                                <option value="configuration"><?= __('admin.configuration') ?></option>
                                <option value="api"><?= __('admin.api_issues') ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="bi bi-x-circle me-1"></i><?= __('admin.clear') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting Items -->
            <div class="row" id="troubleshootItems">
                
                <!-- Issue #1 -->
                <div class="col-lg-6 mb-4 troubleshoot-item" data-type="error" data-keywords="403 video links images editor modsecurity">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-danger text-white d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <span class="fw-bold"><?= __('admin.error_code') ?> 403</span>
                            </div>
                            <span class="badge bg-light text-dark">#1</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-danger mb-3">
                                <i class="bi bi-bug me-2"></i><?= __('admin.issue_video_editor') ?>
                            </h6>
                            <p class="text-muted mb-3"><?= __('admin.issue_video_editor_desc') ?></p>
                            
                            <div class="alert alert-light border-start border-4 border-info">
                                <h6 class="fw-bold text-info mb-2">
                                    <i class="bi bi-lightbulb me-2"></i><?= __('admin.solution') ?>
                                </h6>
                                <p class="mb-0"><?= __('admin.solution_video_editor') ?></p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-danger"><?= __('admin.critical') ?></span>
                                <small class="text-muted"><?= __('admin.server_configuration') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Issue #2 -->
                <div class="col-lg-6 mb-4 troubleshoot-item" data-type="warning" data-keywords="ip api curl extension warning">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-warning text-dark d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <span class="fw-bold"><?= __('admin.warning') ?>: IP API</span>
                            </div>
                            <span class="badge bg-dark text-white">#2</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-warning mb-3">
                                <i class="bi bi-wifi-off me-2"></i><?= __('admin.issue_ip_api') ?>
                            </h6>
                            <p class="text-muted mb-3"><?= __('admin.issue_ip_api_desc') ?></p>
                            
                            <div class="alert alert-light border-start border-4 border-info">
                                <h6 class="fw-bold text-info mb-2">
                                    <i class="bi bi-lightbulb me-2"></i><?= __('admin.solution') ?>
                                </h6>
                                <p class="mb-0"><?= __('admin.solution_ip_api') ?></p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-warning text-dark"><?= __('admin.temporary') ?></span>
                                <small class="text-muted"><?= __('admin.external_service') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Issue #3 -->
                <div class="col-lg-6 mb-4 troubleshoot-item" data-type="configuration" data-keywords="database connection mysql timeout">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-info text-white d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="bi bi-database-x me-2"></i>
                                <span class="fw-bold"><?= __('admin.database_connection') ?></span>
                            </div>
                            <span class="badge bg-light text-dark">#3</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-info mb-3">
                                <i class="bi bi-server me-2"></i><?= __('admin.issue_db_timeout') ?>
                            </h6>
                            <p class="text-muted mb-3"><?= __('admin.issue_db_timeout_desc') ?></p>
                            
                            <div class="alert alert-light border-start border-4 border-info">
                                <h6 class="fw-bold text-info mb-2">
                                    <i class="bi bi-lightbulb me-2"></i><?= __('admin.solution') ?>
                                </h6>
                                <p class="mb-0"><?= __('admin.solution_db_timeout') ?></p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-info"><?= __('admin.moderate') ?></span>
                                <small class="text-muted"><?= __('admin.database_config') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Issue #4 -->
                <div class="col-lg-6 mb-4 troubleshoot-item" data-type="error" data-keywords="memory limit php fatal error">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-danger text-white d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="bi bi-memory me-2"></i>
                                <span class="fw-bold"><?= __('admin.memory_limit') ?></span>
                            </div>
                            <span class="badge bg-light text-dark">#4</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-danger mb-3">
                                <i class="bi bi-cpu me-2"></i><?= __('admin.issue_memory_limit') ?>
                            </h6>
                            <p class="text-muted mb-3"><?= __('admin.issue_memory_limit_desc') ?></p>
                            
                            <div class="alert alert-light border-start border-4 border-info">
                                <h6 class="fw-bold text-info mb-2">
                                    <i class="bi bi-lightbulb me-2"></i><?= __('admin.solution') ?>
                                </h6>
                                <p class="mb-0"><?= __('admin.solution_memory_limit') ?></p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-danger"><?= __('admin.critical') ?></span>
                                <small class="text-muted"><?= __('admin.php_configuration') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Issue #5 -->
                <div class="col-lg-6 mb-4 troubleshoot-item" data-type="api" data-keywords="payment gateway api ssl certificate">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="bi bi-credit-card me-2"></i>
                                <span class="fw-bold"><?= __('admin.payment_gateway') ?></span>
                            </div>
                            <span class="badge bg-light text-dark">#5</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-primary mb-3">
                                <i class="bi bi-shield-x me-2"></i><?= __('admin.issue_ssl_certificate') ?>
                            </h6>
                            <p class="text-muted mb-3"><?= __('admin.issue_ssl_certificate_desc') ?></p>
                            
                            <div class="alert alert-light border-start border-4 border-info">
                                <h6 class="fw-bold text-info mb-2">
                                    <i class="bi bi-lightbulb me-2"></i><?= __('admin.solution') ?>
                                </h6>
                                <p class="mb-0"><?= __('admin.solution_ssl_certificate') ?></p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-primary"><?= __('admin.high') ?></span>
                                <small class="text-muted"><?= __('admin.ssl_security') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Issue #6 -->
                <div class="col-lg-6 mb-4 troubleshoot-item" data-type="configuration" data-keywords="file permissions upload directory writable">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-secondary text-white d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="bi bi-folder-x me-2"></i>
                                <span class="fw-bold"><?= __('admin.file_permissions') ?></span>
                            </div>
                            <span class="badge bg-light text-dark">#6</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-secondary mb-3">
                                <i class="bi bi-upload me-2"></i><?= __('admin.issue_file_upload') ?>
                            </h6>
                            <p class="text-muted mb-3"><?= __('admin.issue_file_upload_desc') ?></p>
                            
                            <div class="alert alert-light border-start border-4 border-info">
                                <h6 class="fw-bold text-info mb-2">
                                    <i class="bi bi-lightbulb me-2"></i><?= __('admin.solution') ?>
                                </h6>
                                <p class="mb-0"><?= __('admin.solution_file_upload') ?></p>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-secondary"><?= __('admin.moderate') ?></span>
                                <small class="text-muted"><?= __('admin.server_permissions') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- No Results Message -->
            <div class="text-center py-5 d-none" id="noResults">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                <h4 class="text-muted mt-3"><?= __('admin.no_results_found') ?></h4>
                <p class="text-muted"><?= __('admin.try_different_search') ?></p>
            </div>

            <!-- Help Section -->
            <div class="card bg-primary text-white border-0 shadow-sm mt-4">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-question-circle fs-1 opacity-75"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-white"><?= __('admin.need_more_help') ?></h5>
                    <p class="text-white opacity-75 mb-3"><?= __('admin.contact_support_desc') ?></p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="<?= base_url('debug/logs') ?>" class="btn btn-outline-light" target="_blank">
                            <i class="bi bi-file-text me-2"></i><?= __('admin.check_logs') ?>
                        </a>
                        <a href="<?= base_url('admincontrol/system_status') ?>" class="btn btn-outline-light">
                            <i class="bi bi-info-circle me-2"></i><?= __('admin.system_status') ?>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchTroubleshoot');
    const typeFilter = document.getElementById('filterByType');
    const troubleshootItems = document.querySelectorAll('.troubleshoot-item');
    const noResults = document.getElementById('noResults');

    function filterItems() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeFilter.value;
        let visibleCount = 0;

        troubleshootItems.forEach(item => {
            const keywords = item.dataset.keywords.toLowerCase();
            const type = item.dataset.type;
            const matchesSearch = !searchTerm || keywords.includes(searchTerm);
            const matchesType = !selectedType || type === selectedType;

            if (matchesSearch && matchesType) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    searchInput.addEventListener('input', filterItems);
    typeFilter.addEventListener('change', filterItems);

    window.clearFilters = function() {
        searchInput.value = '';
        typeFilter.value = '';
        filterItems();
    };
});
</script>
