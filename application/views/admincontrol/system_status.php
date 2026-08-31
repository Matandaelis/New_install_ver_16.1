<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-check me-3 fs-2"></i>
                            <div>
                                <h4 class="mb-1 fw-bold"><?= __('admin.system_status_help_line') ?></h4>
                                <p class="mb-0 opacity-75"><?= __('admin.monitor_system_health') ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admincontrol/system_issues') ?>" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?= __('admin.view_issues') ?>
                            </a>
                            <button class="btn btn-outline-light btn-sm" onclick="refreshSystemStatus()">
                                <i class="bi bi-arrow-clockwise me-2"></i>
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
                                    <i class="bi bi-gear me-2"></i>
                                    <?= __('admin.server_requirements') ?>
                                </h6>
                                <div class="system-status-grid">
                                    <?php
                                    // Use proper checkReq() function instead of manual checks
                                    $serverReq = checkReq();
                                    
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
                                            'desc' => 'External API connectivity for geolocation services (ipapi.co, ip-api.com)',
                                            'current' => array_key_exists('ipapi', $serverReq) ? 'Failed' : 'Working',
                                            'has_issue' => array_key_exists('ipapi', $serverReq),
                                            'details' => array_key_exists('ipapi', $serverReq) ? $serverReq['ipapi'] : 'Connected to IP Geolocation APIs successfully'
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
                                                                            <li><?= __('admin.api_unavailable') ?: 'IP Geolocation APIs temporarily unavailable' ?></li>
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
                                    <?= __('admin.system_information_help_line') ?>
                                </h6>
                                <div class="system-info-list">
                                    <?php
                                    $serverInfo = [
                                        'server_php_version' => phpversion(),
                                        'server_database_version' => database_version(),
                                        'server_database_software' => database_software(),
                                        'server_system_os' => server_os(),
                                        'server_memory_limit' => check_limit(),
                                        'server_ip' => check_server_ip(),
                                        'server_max_file_upload_size' => php_max_upload_size(),
                                        'server_post_variable_size' => php_max_post_size(),
                                        'server_max_execution_time' => php_max_execution_time()
                                    ];
                                    foreach ($serverInfo as $key => $value) {
                                        ?>
                                        <div class="system-info-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-dot text-primary me-2"></i>
                                                <span class="fw-semibold text-dark"><?= __('admin.'.$key) ?></span>
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

<script type="text/javascript">
function refreshSystemStatus() {
    location.reload();
}
</script>