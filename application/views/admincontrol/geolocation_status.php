<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-globe me-3 fs-2"></i>
                            <div>
                                <h4 class="mb-1 fw-bold"><?= __('admin.geolocation_services_status') ?: 'Geolocation Services Status' ?></h4>
                                <p class="mb-0 opacity-75"><?= __('admin.monitor_geolocation_services') ?: 'Monitor all geolocation services and their performance' ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-light btn-sm" onclick="testAllServices()" id="testBtn">
                                <i class="fas fa-play me-2"></i>
                                <?= __('admin.test_all_services') ?: 'Test All Services' ?>
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="refreshStatus()" id="refreshBtn">
                                <i class="fas fa-sync-alt me-2"></i>
                                <?= __('admin.refresh') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-8">
                            <div class="p-4">
                                <h6 class="text-muted mb-3 text-uppercase fw-semibold">
                                    <i class="bi bi-list-ul me-2"></i>
                                    <?= __('admin.service_details') ?: 'Service Details' ?>
                                </h6>
                                
                                <div id="servicesContainer">
                                    <!-- Services will be loaded here via AJAX -->
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted"><?= __('admin.loading_services') ?: 'Loading services...' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bg-light p-4 h-100">
                                <h6 class="text-muted mb-3 text-uppercase fw-semibold">
                                    <i class="fas fa-chart-pie me-2"></i>
                                    <?= __('admin.quick_stats') ?: 'Quick Stats' ?>
                                </h6>
                                
                                <div id="quickStats" class="mb-4">
                                    <!-- Stats will be loaded here -->
                                </div>
                                
                                <hr class="my-4">
                                
                                <h6 class="text-muted mb-3 text-uppercase fw-semibold">
                                    <i class="fas fa-history me-2"></i>
                                    <?= __('admin.recent_activity') ?: 'Recent Activity' ?>
                                </h6>
                                
                                <div id="recentActivity">
                                    <!-- Recent activity will be loaded here -->
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
function refreshStatus() {
    loadServicesStatus();
}

function testAllServices() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Testing...';
    btn.disabled = true;
    
    fetch('<?= base_url("admincontrol/test_geolocation_services") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', '<?= __('admin.all_services_tested') ?: 'All services tested successfully' ?>');
            loadServicesStatus();
        } else {
            showToast('error', data.message || '<?= __('admin.test_failed') ?: 'Test failed' ?>');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', '<?= __('admin.test_error') ?: 'Test error occurred' ?>');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function loadServicesStatus() {
    fetch('<?= base_url("admincontrol/get_geolocation_status") ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayServices(data.services);
            displayQuickStats(data.stats);
            displayRecentActivity(data.recent_logs);
        } else {
            document.getElementById('servicesContainer').innerHTML = 
                '<div class="alert alert-danger">' + (data.message || '<?= __('admin.failed_to_load') ?: 'Failed to load services' ?>') + '</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('servicesContainer').innerHTML = 
            '<div class="alert alert-danger"><?= __('admin.load_error') ?: 'Error loading services' ?></div>';
    });
}

function displayServices(services) {
    // Sort services: working first, then failed/rate-limited last
    services.sort((a, b) => {
        if (a.status === 'success' && b.status !== 'success') return -1;
        if (a.status !== 'success' && b.status === 'success') return 1;
        if (a.status === 'error' && b.status === 'unknown') return 1;
        if (a.status === 'unknown' && b.status === 'error') return -1;
        return 0;
    });
    
    let html = '';
    let hasWorkingServices = false;
    let hasFailedServices = false;
    
    services.forEach((service, index) => {
        // Add header for working services
        if (service.status === 'success' && !hasWorkingServices) {
            hasWorkingServices = true;
            html += `
                <div class="row mb-3">
                    <div class="col-12">
                        <h6 class="text-success text-center mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= __('admin.working_services') ?: 'Working Services' ?>
                        </h6>
                    </div>
                </div>
            `;
        }
        if (service.status !== 'success' && hasWorkingServices && !hasFailedServices) {
            hasFailedServices = true;
            html += `
                <div class="row mb-3">
                    <div class="col-12">
                        <hr class="border-secondary">
                        <h6 class="text-muted text-center mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= __('admin.failed_services') ?: 'Failed Services' ?>
                        </h6>
                    </div>
                </div>
            `;
        }
        const statusClass = service.status === 'success' ? 'success' : 
                           service.status === 'error' ? 'danger' : 'secondary';
        const statusIcon = service.status === 'success' ? 'fas fa-check-circle' : 
                          service.status === 'error' ? 'fas fa-times-circle' : 'fas fa-question-circle';
        
        html += `
            <div class="card mb-3 border-${statusClass}">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <i class="${statusIcon} text-${statusClass} me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-1 fw-semibold">${service.name}</h6>
                                <small class="text-muted">${service.free_limit}</small>
                            </div>
                        </div>
                        <span class="badge bg-${statusClass} px-3 py-2">
                            ${service.status === 'success' ? '<?= __('admin.working') ?: 'Working' ?>' : 
                              service.status === 'error' ? '<?= __('admin.failed') ?: 'Failed' ?>' : 
                              '<?= __('admin.unknown') ?: 'Unknown' ?>'}
                        </span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block"><?= __('admin.last_used') ?: 'Last Used' ?>:</small>
                            <span class="fw-semibold">${service.last_used || '<?= __('admin.never') ?: 'Never' ?>'}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block"><?= __('admin.response_time') ?: 'Response Time' ?>:</small>
                            <span class="fw-semibold">${service.response_time ? service.response_time + 'ms' : '<?= __('admin.n_a') ?: 'N/A' ?>'}</span>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <small class="text-muted d-block"><?= __('admin.success_count') ?: 'Success Count' ?>:</small>
                            <span class="badge bg-success">${service.success_count || 0}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block"><?= __('admin.error_count') ?: 'Error Count' ?>:</small>
                            <span class="badge bg-danger">${service.error_count || 0}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block"><?= __('admin.success_rate') ?: 'Success Rate' ?>:</small>
                            <span class="badge bg-info">${service.success_rate || 0}%</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block"><?= __('admin.total_requests') ?: 'Total Requests' ?>:</small>
                            <span class="badge bg-primary">${service.total_requests || 0}</span>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <small class="text-muted d-block"><?= __('admin.daily_usage') ?: 'Daily Usage' ?>:</small>
                            <span class="badge bg-warning">${service.daily_usage || 0}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block"><?= __('admin.monthly_usage') ?: 'Monthly Usage' ?>:</small>
                            <span class="badge bg-secondary">${service.monthly_usage || 0}</span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted d-block"><?= __('admin.api_url') ?: 'API URL' ?>:</small>
                        <code class="small">${service.url}</code>
                        ${service.last_error ? `
                            <div class="mt-2">
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    ${service.last_error.includes('Rate limited') ? '<?= __('admin.rate_limited') ?>: <?= __('admin.too_many_requests') ?>' : service.last_error}
                                </small>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    document.getElementById('servicesContainer').innerHTML = html;
}

function displayQuickStats(stats) {
    const html = `
        <div class="row text-center">
            <div class="col-6 mb-3">
                <div class="bg-white rounded p-3">
                    <h4 class="text-primary mb-1">${stats.total_services}</h4>
                    <small class="text-muted"><?= __('admin.total_services') ?: 'Total Services' ?></small>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="bg-white rounded p-3">
                    <h4 class="text-success mb-1">${stats.active_services}</h4>
                    <small class="text-muted"><?= __('admin.active_services') ?: 'Active Services' ?></small>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('quickStats').innerHTML = html;
}

function displayRecentActivity(logs) {
    let html = '';
    
    if (logs && logs.length > 0) {
        logs.forEach(log => {
            const statusClass = log.success ? 'success' : 'danger';
            const statusIcon = log.success ? 'fas fa-check-circle' : 'fas fa-times-circle';
            
            html += `
                <div class="d-flex align-items-center py-2 border-bottom">
                    <i class="${statusIcon} text-${statusClass} me-2"></i>
                    <div class="flex-grow-1">
                        <div class="small fw-semibold">${log.service_name}</div>
                        <div class="small text-muted">${log.timestamp} - ${log.response_time}ms</div>
                    </div>
                </div>
            `;
        });
    } else {
        html = '<p class="text-muted small"><?= __('admin.no_recent_activity') ?: 'No recent activity' ?></p>';
    }
    
    document.getElementById('recentActivity').innerHTML = html;
}

// Load services on page load
document.addEventListener('DOMContentLoaded', function() {
    // First test all services, then load status
    testAllServicesOnLoad();
});

function testAllServicesOnLoad() {
    // Test all services automatically when page loads
    fetch('<?= base_url("admincontrol/test_geolocation_services") ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // After testing, load the updated status
            loadServicesStatus();
        } else {
            // If testing fails, still load status to show current state
            loadServicesStatus();
        }
    })
    .catch(error => {
        console.error('Error testing services on load:', error);
        // If testing fails, still load status to show current state
        loadServicesStatus();
    });
}
</script>
