<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('admin.system_status') ?: 'System Status' ?> - <?= __('admin.server_analyzer') ?: 'Server Analyzer' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/admin-dashboard-custom.css') ?>">
    
    <style>
    .language-flag {
        border-radius: 2px;
        object-fit: cover;
    }
    .dropdown-menu {
        min-width: 200px;
    }
    .dropdown-item {
        padding: 0.5rem 1rem;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-check me-3 fs-2"></i>
                            <div>
                                <h4 class="mb-1 fw-bold"><?= __('admin.system_status_help_line') ?: 'SYSTEM STATUS...' ?></h4>
                                <p class="mb-0 opacity-75"><?= __('admin.monitor_system_health') ?: 'Monitor system health and requirements' ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Language Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-translate me-2"></i>
                                    <span id="current-language"><?= $current_lang_name ?></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><h6 class="dropdown-header"><i class="bi bi-globe me-2"></i><?= __('admin.select_language') ?: 'Select Language' ?></h6></li>
                                    <?php foreach($languages as $lang): ?>
                                    <li><a class="dropdown-item" href="<?= base_url('systemcheck/change_language/' . $lang['id']) ?>">
                                        <?php if ($lang['flag']): ?>
                                            <img src="<?= base_url($lang['flag']) ?>" alt="<?= $lang['name'] ?>" class="language-flag me-2" style="width: 16px; height: 12px;">
                                        <?php endif; ?>
                                        <?= $lang['name'] ?>
                                    </a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <button class="btn btn-outline-light btn-sm" onclick="refreshSystemStatus()">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                <?= __('admin.refresh') ?: 'Refresh' ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-8">
                            <div class="p-4">
                                <h6 class="text-muted mb-3 text-uppercase fw-semibold">
                                    <i class="bi bi-gear me-2"></i>
                                    <?= __('admin.server_requirements') ?: 'Server Requirements' ?>
                                </h6>
                                <div class="system-status-grid">
                                    <?php
                                    $requirements = [
                                        'php' => [
                                            'name' => 'PHP Version',
                                            'desc' => 'Minimum version 7.4',
                                            'current' => phpversion(),
                                            'has_issue' => array_key_exists('php', $serverReq)
                                        ],
                                        'curl' => [
                                            'name' => 'Curl Extension',
                                            'desc' => 'Extension php_curl',
                                            'current' => extension_loaded('curl') ? 'Available' : 'Missing',
                                            'has_issue' => array_key_exists('curl', $serverReq)
                                        ],
                                        'openssl_encrypt' => [
                                            'name' => 'OpenSSL Encrypt',
                                            'desc' => 'Extension openssl_encrypt',
                                            'current' => function_exists('openssl_encrypt') ? 'Available' : 'Missing',
                                            'has_issue' => array_key_exists('openssl_encrypt', $serverReq)
                                        ],
                                        'mysqli' => [
                                            'name' => 'MySQLi Extension',
                                            'desc' => 'Extension mysqli',
                                            'current' => extension_loaded('mysqli') ? 'Available' : 'Missing',
                                            'has_issue' => array_key_exists('mysqli', $serverReq)
                                        ],
                                        'sourceguardian' => [
                                            'name' => 'Source Guardian',
                                            'desc' => 'Extension sourceguardian',
                                            'current' => extension_loaded('sourceguardian') ? 'Available' : 'Missing',
                                            'has_issue' => array_key_exists('sourceguardian', $serverReq)
                                        ],
                                        'ipapi' => [
                                            'name' => 'IP Geolocation API',
                                            'desc' => 'External API connectivity for geolocation services',
                                            'current' => array_key_exists('ipapi', $serverReq) ? 'Failed' : 'Working',
                                            'has_issue' => array_key_exists('ipapi', $serverReq),
                                            'details' => array_key_exists('ipapi', $serverReq) ? $serverReq['ipapi'] : 'Connected to GeoPlugin API successfully'
                                        ],
                                        'ziparchive' => [
                                            'name' => 'ZipArchive',
                                            'desc' => 'Extension zip',
                                            'current' => extension_loaded('zip') ? 'Available' : 'Missing',
                                            'has_issue' => array_key_exists('ziparchive', $serverReq)
                                        ],
                                        'allow_url_fopen' => [
                                            'name' => 'allow_url_fopen',
                                            'desc' => 'PHP INI setting',
                                            'current' => ini_get('allow_url_fopen') ? 'Enabled' : 'Disabled',
                                            'has_issue' => isset($serverReq['allow_url_fopen'])
                                        ],
                                        'ssl' => [
                                            'name' => is_ssl() ? 'SSL Certificate' : 'Non SSL',
                                            'desc' => 'SSL Certificate',
                                            'current' => is_ssl() ? 'Enabled' : 'Disabled',
                                            'has_issue' => !is_ssl()
                                        ],
                                        'gd' => [
                                            'name' => extension_loaded('gd') ? 'GD Library Installed' : 'No GD Library Installed',
                                            'desc' => 'GD Library',
                                            'current' => extension_loaded('gd') ? 'Installed' : 'Missing',
                                            'has_issue' => !extension_loaded('gd')
                                        ]
                                    ];

                                    foreach ($requirements as $key => $req) {
                                        $isInstalled = !$req['has_issue'];
                                        $iconClass = $isInstalled ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger';
                                        $badgeClass = $isInstalled ? 'bg-success' : 'bg-danger';
                                        $cardClass = $isInstalled ? 'border-success' : 'border-danger';
                                        ?>
                                        <div class="system-status-item card mb-3 <?= $cardClass ?>">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="<?= $iconClass ?> me-3 fs-4"></i>
                                                        <div>
                                                            <h6 class="mb-1 fw-semibold"><?= $req['name'] ?></h6>
                                                            <small class="text-muted"><?= $req['desc'] ?></small>
                                                            <?php if (isset($req['details']) && !empty($req['details'])): ?>
                                                                <br><small class="text-info"><i class="bi bi-info-circle me-1"></i><?= htmlspecialchars($req['details']) ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <span class="badge <?= $badgeClass ?> px-3 py-2">
                                                        <?= $req['current'] ?>
                                                    </span>
                                                </div>
                                                
                                                <?php if ($key === 'ipapi' && $req['has_issue']): ?>
                                                <div class="mt-3 p-3 bg-light rounded">
                                                    <h6 class="mb-2"><i class="bi bi-exclamation-triangle text-warning me-2"></i><?= __('admin.ip_api_connectivity_issue') ?: 'IP API Connectivity Issue' ?></h6>
                                                    <p class="mb-2 text-muted"><?= __('admin.affects_geolocation_not_core') ?: 'This affects geolocation features but does not impact core functionality.' ?></p>
                                                    
                                                    <div class="accordion" id="ipapiAccordion">
                                                        <div class="accordion-item border-0">
                                                            <h6 class="accordion-header">
                                                                <button class="accordion-button collapsed bg-transparent border-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#ipapiCollapse">
                                                                    <small><i class="bi bi-chevron-right me-2"></i><?= __('admin.view_troubleshooting_steps') ?: 'View troubleshooting steps' ?></small>
                                                                </button>
                                                            </h6>
                                                            <div id="ipapiCollapse" class="accordion-collapse collapse" data-bs-parent="#ipapiAccordion">
                                                                <div class="accordion-body p-0 pt-2">
                                                                    <div class="small text-muted">
                                                                        <strong><?= __('admin.possible_causes') ?: 'Possible causes:' ?></strong>
                                                                        <ul class="mb-2 mt-1">
                                                                            <li><?= __('admin.firewall_blocking') ?: 'Firewall blocking external API calls' ?></li>
                                                                            <li><?= __('admin.proxy_configuration') ?: 'Proxy server configuration issues' ?></li>
                                                                            <li><?= __('admin.api_unavailable') ?: 'GeoPlugin API temporarily unavailable' ?></li>
                                                                            <li><?= __('admin.network_connectivity') ?: 'Network connectivity problems' ?></li>
                                                                        </ul>
                                                                        
                                                                        <strong><?= __('admin.solutions') ?: 'Solutions:' ?></strong>
                                                                        <ul class="mb-2">
                                                                            <li><strong><?= __('admin.contact_hosting_provider_firewall') ?: 'Contact hosting provider to check firewall settings' ?></strong></li>
                                                                            <li><strong><?= __('admin.verify_internet_connectivity') ?: 'Verify internet connectivity from your server' ?></strong></li>
                                                                            <li><strong><?= __('admin.check_proxy_settings') ?: 'Check proxy settings if applicable' ?></strong></li>
                                                                            <li><strong><?= __('admin.wait_and_retry') ?: 'Wait and retry - API services may be temporarily down' ?></strong></li>
                                                                        </ul>
                                                                        
                                                                        <div class="alert alert-info alert-sm mb-0">
                                                                            <i class="bi bi-info-circle me-1"></i>
                                                                            <strong><?= __('admin.non_critical_warning') ?: 'Note: This is a non-critical warning. Your application will work normally without IP geolocation features.' ?></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bg-light p-4 h-100">
                                <h6 class="text-muted mb-3 text-uppercase fw-semibold">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <?= __('admin.system_information_help_line') ?: 'YOUR SERVER DETAILS' ?>
                                </h6>
                                <div class="system-info-list">
                                    <?php
                                    $serverInfo = [
                                        'server_php_version' => phpversion(),
                                        'server_database_version' => $con ? database_version($con) : 'Error',
                                        'server_database_software' => $con ? database_software($con) : 'Error',
                                        'server_system_os' => server_os(),
                                        'server_memory_limit' => check_limit(),
                                        'server_ip' => check_server_ip(),
                                        'server_max_file_upload_size' => php_max_upload_size(),
                                        'server_post_variable_size' => php_max_post_size(),
                                        'server_max_execution_time' => php_max_execution_time()
                                    ];
                                    
                                    $infoLabels = [
                                        'server_php_version' => 'PHP Version',
                                        'server_database_version' => 'Database Version',
                                        'server_database_software' => 'Database Software',
                                        'server_system_os' => 'System OS',
                                        'server_memory_limit' => 'Memory Limit',
                                        'server_ip' => 'Server IP',
                                        'server_max_file_upload_size' => 'Max File Upload Size',
                                        'server_post_variable_size' => 'Post Variable Size',
                                        'server_max_execution_time' => 'Max Execution Time'
                                    ];
                                    
                                    foreach ($serverInfo as $key => $value) {
                                        ?>
                                        <div class="system-info-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-dot text-primary me-2"></i>
                                                <span class="fw-semibold text-dark"><?= $infoLabels[$key] ?></span>
                                            </div>
                                            <span class="badge bg-light text-dark"><?= $value ?></span>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Summary Section -->
            <div class="row">
                <div class="col-12">
                    <?php 
                    $issueCount = count($serverReq);
                    if ($issueCount > 0): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                            <div>
                                <strong><?= __('admin.action_required') ?: 'Action Required' ?>:</strong> <?= sprintf(__('admin.found_system_issues') ?: 'Found %d system issue(s) that need attention.', $issueCount) ?> <?= __('admin.contact_hosting_provider') ?: 'Please contact your hosting provider to resolve these issues.' ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                            <div>
                                <strong><?= __('admin.all_good') ?: 'All Good' ?>!</strong> <?= __('admin.server_meets_requirements') ?: 'Your server meets all the system requirements. The application should run smoothly.' ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Files -->
<script src="<?= base_url('assets/template/js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>"></script>

<script type="text/javascript">
function refreshSystemStatus() {
    // Show loading state
    const refreshBtn = document.querySelector('button[onclick="refreshSystemStatus()"]');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i><?= __('admin.refreshing') ?: 'Refreshing' ?>';
    refreshBtn.disabled = true;
    
    // Reload page after short delay
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Auto-refresh every 30 seconds (optional)
// setInterval(refreshSystemStatus, 30000);

// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Add click animations to status cards
    const statusCards = document.querySelectorAll('.system-status-item');
    statusCards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    // Add hover effects
    statusCards.forEach(card => {
        card.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
});
</script>
</body>
</html>
