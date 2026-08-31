<?php
$db =& get_instance();
$userdetails = get_object_vars($db->user_info());
$products = $db->Product_model;
$serverReq = checkReq();
$notifications_count = $products->getnotificationnew_count('admin',null);
?>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/flag/css/main.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/table/datatables.min.css") ?>">

<!-- System notifications -->
<div id="notificationDiv"></div>

<!-- Main Security Monitor Container -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Security Monitor Header -->
            <?php
                // Determine header color based on security status - using only Bootstrap 5 colors
                $header_class = 'bg-success';
                $status_text = __('admin.system_secure');
                $status_icon = 'fas fa-shield-alt';
                
                // Count recent threats for status
                $recent_threat_count = 0;
                if (isset($spam_monitoring)) {
                    $one_hour_ago = date('Y-m-d H:i:s', strtotime('-1 hour'));
                    
                    // Count recent spam attempts
                    if (isset($spam_monitoring['recent_spam'])) {
                        foreach ($spam_monitoring['recent_spam'] as $spam) {
                            if (isset($spam['last_attempt_timestamp']) && $spam['last_attempt_timestamp'] >= $one_hour_ago) {
                                $recent_threat_count += $spam['count'];
                            }
                        }
                    }
                    
                    // Count recent fraud attempts
                    if (isset($spam_monitoring['recent_fraud_attempts'])) {
                        foreach ($spam_monitoring['recent_fraud_attempts'] as $fraud) {
                            if ($fraud['created_at'] >= $one_hour_ago) {
                                $recent_threat_count++;
                            }
                        }
                    }
                }
                
                // Set header style based on threat level - using only Bootstrap 5 colors
                if ($recent_threat_count > 0) {
                    $header_class = 'bg-danger';
                    $status_text = __('admin.security_issues_detected');
                    $status_icon = 'fas fa-exclamation-triangle';
                } else {
                    $header_class = 'bg-success';
                    $status_text = __('admin.system_secure');
                    $status_icon = 'fas fa-shield-alt';
                }
            ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="text-white rounded-4 p-4 shadow-sm <?= $header_class ?>">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center">
                                    <div class="border border-white border-opacity-50 rounded-circle p-3 me-4 shadow-sm bg-white bg-opacity-10">
                                        <i class="<?= $status_icon ?> text-white fs-2"></i>
                                    </div>
                                    <div>
                                        <h1 class="text-white fw-bold mb-1 fs-2">
                                            <?= __('admin.security_monitor') ?>
                                        </h1>
                                        <p class="text-white mb-0" style="opacity: 0.9;">
                                            <i class="fas fa-eye me-2 text-white"></i>
                                            <span class="text-white"><?= $status_text ?> - <?= $recent_threat_count ?> <?= __('admin.threats_in_last_hour') ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="<?= base_url('admincontrol/dashboard') ?>" class="btn btn-light btn-lg px-4 py-2 rounded-pill text-dark fw-semibold">
                                        <i class="fas fa-arrow-left me-2 text-dark"></i>
                                        <?= __('admin.back_to_dashboard') ?>
                                    </a>
                                    <button class="btn btn-outline-light btn-lg px-4 py-2 rounded-pill text-white fw-semibold" onclick="location.reload()">
                                        <i class="fas fa-sync-alt me-2 text-white"></i>
                                        <?= __('admin.refresh') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spam Protection Statistics Row -->
            <div class="row mb-4">
                <!-- Total Spam Attempts -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-muted"><?= __('admin.total_spam_attempts') ?></h6>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-1" id="total-spam-attempts"><?= $spam_monitoring['spam_stats']['total_attempts'] ?? 0 ?></h3>
                            <small class="text-muted"><?= __('admin.last_24_hours') ?></small>
                        </div>
                    </div>
                </div>

                <!-- Unique Users -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-users text-warning fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-muted"><?= __('admin.unique_users') ?></h6>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-1" id="unique-spam-users"><?= $spam_monitoring['spam_stats']['unique_users'] ?? 0 ?></h3>
                            <small class="text-muted"><?= __('admin.attempted_spam') ?></small>
                        </div>
                    </div>
                </div>

                <!-- Unique IPs -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-globe text-info fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-muted"><?= __('admin.unique_ips') ?></h6>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-1" id="unique-spam-ips"><?= $spam_monitoring['spam_stats']['unique_ips'] ?? 0 ?></h3>
                            <small class="text-muted"><?= __('admin.different_sources') ?></small>
                        </div>
                    </div>
                </div>

                <!-- Days with Spam -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-calendar-check text-success fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-muted"><?= __('admin.days_with_spam') ?></h6>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-1" id="days-with-spam"><?= $spam_monitoring['spam_stats']['days_with_spam'] ?? 0 ?></h3>
                            <small class="text-muted"><?= __('admin.recent_activity') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fraud Detection Statistics Row -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-ban me-2 text-danger"></i>
                        <?= __('admin.fraud_detection_system') ?> (<?= __('admin.last_7_days') ?>)
                    </h5>
                </div>
                
                <!-- Total Fraud Attempts -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-ban text-danger fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-muted"><?= __('admin.total_fraud_attempts') ?></h6>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-1" id="total-fraud-attempts"><?= $spam_monitoring['fraud_stats']['total_fraud_attempts'] ?? 0 ?></h3>
                            <small class="text-muted"><?= __('admin.blocked_clicks') ?></small>
                        </div>
                    </div>
                </div>

                <!-- Unique Fraud Users -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-user-times text-warning fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-muted"><?= __('admin.fraud_users') ?></h6>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-1" id="unique-fraud-users"><?= $spam_monitoring['fraud_stats']['unique_fraud_users'] ?? 0 ?></h3>
                            <small class="text-muted"><?= __('admin.unique_offenders') ?></small>
                        </div>
                    </div>
                </div>

                <!-- Unique Fraud IPs -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-network-wired text-info fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-muted"><?= __('admin.fraud_ip_sources') ?></h6>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-1" id="unique-fraud-ips"><?= $spam_monitoring['fraud_stats']['unique_fraud_ips'] ?? 0 ?></h3>
                            <small class="text-muted"><?= __('admin.different_origins') ?></small>
                        </div>
                    </div>
                </div>

                <!-- Localhost Blocks -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-home text-secondary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-muted"><?= __('admin.localhost_blocks') ?></h6>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-1" id="localhost-blocks"><?= $spam_monitoring['fraud_stats']['blocked_localhost'] ?? 0 ?></h3>
                            <small class="text-muted"><?= __('admin.dev_environment') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analysis Row -->
            <div class="row mb-4">
                <!-- Spam by Campaign Type Chart -->
                <div class="col-lg-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-chart-pie me-2 text-primary"></i>
                                <?= __('admin.spam_by_campaign_type') ?>
                            </h6>
                            <div class="position-relative" style="height: 300px;">
                                <?php if(isset($spam_monitoring['spam_by_type']) && count($spam_monitoring['spam_by_type']) > 0): ?>
                                    <canvas id="spamByTypeChart"></canvas>
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-chart-pie fs-1 mb-3 opacity-50"></i>
                                            <p class="mb-0"><?= __('admin.no_spam_data_available') ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Spam Users -->
                <div class="col-lg-6 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-user-times me-2 text-danger"></i>
                                <?= __('admin.top_spam_users') ?>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="small"><?= __('admin.user') ?></th>
                                            <th class="small"><?= __('admin.email') ?></th>
                                            <th class="small text-center"><?= __('admin.count') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($spam_monitoring['top_spam_users']) && count($spam_monitoring['top_spam_users']) > 0): ?>
                                            <?php foreach($spam_monitoring['top_spam_users'] as $user): ?>
                                            <tr>
                                                <td class="small"><?= $user['firstname'] . ' ' . $user['lastname'] ?></td>
                                                <td class="small"><?= $user['email'] ?></td>
                                                <td class="small text-center">
                                                    <span class="badge bg-danger"><?= $user['spam_count'] ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted"><?= __('admin.no_spam_users_found') ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fraud Analysis Charts Row -->
            <div class="row mb-4">
                <!-- Fraud by Country Chart -->
                <div class="col-lg-4 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-globe me-2 text-success"></i>
                                <?= __('admin.fraud_by_country') ?>
                            </h6>
                            <div class="position-relative" style="height: 250px;">
                                <?php if(isset($spam_monitoring['fraud_by_country']) && count($spam_monitoring['fraud_by_country']) > 0): ?>
                                    <canvas id="fraudByCountryChart"></canvas>
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-globe fs-1 mb-3 opacity-50"></i>
                                            <p class="mb-0"><?= __('admin.no_fraud_data_available') ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fraud by Browser Chart -->
                <div class="col-lg-4 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-browser me-2 text-info"></i>
                                <?= __('admin.fraud_by_browser') ?>
                            </h6>
                            <div class="position-relative" style="height: 250px;">
                                <?php if(isset($spam_monitoring['fraud_by_browser']) && count($spam_monitoring['fraud_by_browser']) > 0): ?>
                                    <canvas id="fraudByBrowserChart"></canvas>
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-browser fs-1 mb-3 opacity-50"></i>
                                            <p class="mb-0"><?= __('admin.no_fraud_data_available') ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Fraud Users -->
                <div class="col-lg-4 mb-3">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-user-shield me-2 text-warning"></i>
                                <?= __('admin.top_fraud_users') ?>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="small"><?= __('admin.user') ?></th>
                                            <th class="small"><?= __('admin.attempts') ?></th>
                                            <th class="small"><?= __('admin.ips') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($spam_monitoring['top_fraud_users']) && count($spam_monitoring['top_fraud_users']) > 0): ?>
                                            <?php foreach(array_slice($spam_monitoring['top_fraud_users'], 0, 6) as $user): ?>
                                            <tr>
                                                <td class="small">
                                                    <?= $user['firstname'] . ' ' . $user['lastname'] ?>
                                                    <br><small class="text-muted"><?= $user['email'] ?></small>
                                                </td>
                                                <td class="small text-center">
                                                    <span class="badge bg-danger"><?= $user['fraud_count'] ?></span>
                                                </td>
                                                <td class="small text-center">
                                                    <span class="badge bg-info"><?= $user['unique_ips'] ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted"><?= __('admin.no_fraud_users_found') ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Spam Attempts Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">
                                    <i class="fas fa-list-alt me-2 text-primary"></i>
                                    <?= __('admin.recent_spam_attempts') ?>
                                </h6>
                                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    <?= __('admin.refresh') ?>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <!-- Filters Section -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label small"><?= __('admin.filter_by_user') ?>:</label>
                                    <select id="userFilter" class="form-select form-select-sm">
                                        <option value=""><?= __('admin.all_users') ?></option>
                                        <?php 
                                        $users = [];
                                        foreach($spam_monitoring['recent_spam'] as $spam) {
                                            $user_key = $spam['data']['user_id'];
                                            if (!isset($users[$user_key])) {
                                                $users[$user_key] = $spam['data']['firstname'] . ' ' . $spam['data']['lastname'];
                                            }
                                        }
                                        foreach($users as $user_id => $user_name): ?>
                                            <option value="<?= $user_id ?>"><?= $user_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small"><?= __('admin.filter_by_campaign_type') ?>:</label>
                                    <select id="campaignTypeFilter" class="form-select form-select-sm">
                                        <option value=""><?= __('admin.all_types') ?></option>
                                        <option value="Form"><?= __('admin.forms') ?></option>
                                        <option value="Product"><?= __('admin.products') ?></option>
                                        <option value="Tool"><?= __('admin.tools') ?></option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small"><?= __('admin.filter_by_ip_count') ?>:</label>
                                    <select id="ipFilter" class="form-select form-select-sm">
                                        <option value=""><?= __('admin.all_ips') ?></option>
                                        <option value="single"><?= __('admin.single_ip') ?></option>
                                        <option value="multiple"><?= __('admin.multiple_ips') ?></option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small"><?= __('admin.show_entries') ?>:</label>
                                    <select id="entriesPerPage" class="form-select form-select-sm">
                                        <option value="10">10 <?= __('admin.per_page') ?></option>
                                        <option value="20" selected>20 <?= __('admin.per_page') ?></option>
                                        <option value="50">50 <?= __('admin.per_page') ?></option>
                                        <option value="100">100 <?= __('admin.per_page') ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Search Bar -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="searchSpam" class="form-control" placeholder="<?= __('admin.search_by_user_name_email_campaign_name_or_ip') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button id="clearFilters" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times me-1"></i>
                                        <?= __('admin.clear_filters') ?>
                                    </button>
                                    <span id="filterResults" class="ms-3 text-muted small"></span>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th class="small"><?= __('admin.time') ?></th>
                                            <th class="small"><?= __('admin.user') ?></th>
                                            <th class="small"><?= __('admin.campaign') ?></th>
                                            <th class="small"><?= __('admin.ip_address') ?></th>
                                            <th class="small"><?= __('admin.status') ?></th>
                                            <th class="small text-center"><?= __('admin.fraud_score') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="spamTableBody">
                                        <?php foreach($spam_monitoring['recent_spam'] as $spam): ?>
                                        <tr>
                                            <td class="small">
                                                <?php if($spam['count'] > 1): ?>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold text-danger"><?= $spam['count'] ?>x</span>
                                                        <small><?= $spam['time'] ?></small>
                                                    </div>
                                                <?php else: ?>
                                                    <?= $spam['time'] ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="small">
                                                <?= $spam['data']['firstname'] . ' ' . $spam['data']['lastname'] ?>
                                                <br><small class="text-muted"><?= $spam['data']['email'] ?></small>
                                            </td>
                                            <td class="small">
                                                <span class="badge bg-primary"><?= $spam['data']['campaign_type'] ?></span>
                                                <br>
                                                <?php 
                                                $campaign_url = '';
                                                $campaign_name = $spam['data']['campaign_name'];
                                                
                                                if($spam['data']['form_id']) {
                                                    $campaign_url = base_url('admincontrol/form_manage/' . $spam['data']['form_id']);
                                                } elseif($spam['data']['product_id']) {
                                                    $campaign_url = base_url('admincontrol/updateproduct/' . $spam['data']['product_id']);
                                                } elseif($spam['data']['tools_id']) {
                                                    $tool_type = $spam['data']['tool_type'] ?? 'banner';
                                                    $campaign_url = base_url('integration/integration_tools_form/' . $tool_type . '/' . $spam['data']['tools_id']);
                                                }
                                                
                                                if($campaign_url): ?>
                                                    <a href="<?= $campaign_url ?>" class="text-decoration-none" target="_blank">
                                                        <small class="text-primary"><?= $campaign_name ?></small>
                                                        <i class="fas fa-external-link-alt ms-1 small"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <small><?= $campaign_name ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td class="small">
                                                <?php if(isset($spam['data']['ip_count']) && $spam['data']['ip_count'] > 1): ?>
                                                    <span class="badge bg-warning mb-1"><?= $spam['data']['ip_count'] ?> <?= __('admin.ips') ?></span>
                                                    <br>
                                                    <div style="max-width: 150px; word-break: break-all;">
                                                        <code class="small"><?= $spam['data']['ip'] ?></code>
                                                    </div>
                                                <?php else: ?>
                                                    <code class="small"><?= $spam['data']['ip'] ?></code>
                                                <?php endif; ?>
                                            </td>
                                            <td class="small">
                                                <span class="badge bg-danger"><?= __('admin.blocked') ?></span>
                                            </td>
                                            <?php
                                                // [v15] Fraud score badge — sourced from integration_clicks_action.fraud_score
                                                $fs_val = $spam['data']['fraud_score'] ?? null;
                                                if (is_null($fs_val)) {
                                                    $fs_badge = 'secondary';
                                                    $fs_label = '&ndash;';
                                                    $fs_title = __('admin.score_not_available');
                                                } elseif ($fs_val <= 30) {
                                                    $fs_badge = 'success';
                                                    $fs_label = (int)$fs_val;
                                                    $fs_title = __('admin.score_green');
                                                } elseif ($fs_val <= 70) {
                                                    $fs_badge = 'warning';
                                                    $fs_label = (int)$fs_val;
                                                    $fs_title = __('admin.score_amber');
                                                } else {
                                                    $fs_badge = 'danger';
                                                    $fs_label = (int)$fs_val;
                                                    $fs_title = __('admin.score_red');
                                                }
                                            ?>
                                            <td class="small text-center">
                                                <span class="badge bg-<?= $fs_badge ?>" title="<?= $fs_title ?>">
                                                    <?= $fs_label ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination Controls -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    <?= __('admin.showing') ?> <span id="showingStart">1</span> <?= __('admin.to') ?> <span id="showingEnd">20</span> <?= __('admin.of') ?> <span id="totalEntries">0</span> <?= __('admin.entries') ?>
                                </div>
                                <nav aria-label="Spam attempts pagination">
                                    <ul class="pagination pagination-sm mb-0" id="paginationControls">
                                        <!-- Pagination buttons will be generated by JavaScript -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container for notifications -->
<div class="toast-container" id="toastContainer"></div>

<script type="text/javascript" src="<?= base_url("assets/plugins/table/datatables.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/plugins/table/dataTables.responsive.min.js") ?>"></script>
<script src="<?= base_url('assets/plugins/chart/chart.min.js') ?>"></script>
<script type="text/javascript">
    // Spam Monitoring Chart
    let spamChart = null;
    
    function initializeSpamChart() {
        const ctx = document.getElementById('spamByTypeChart');
        if (ctx && !spamChart) {
            const spamData = <?= json_encode($spam_monitoring['spam_by_type']) ?>;
            
            // Only create chart if we have data
            if (spamData && spamData.length > 0) {
                const labels = spamData.map(item => item.campaign_type);
                const data = spamData.map(item => parseInt(item.spam_count));
                
                spamChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
            }
        }
    }

    // Fraud Charts
    let fraudCountryChart = null;
    let fraudBrowserChart = null;
    
    function initializeFraudCountryChart() {
        const ctx = document.getElementById('fraudByCountryChart');
        if (ctx && !fraudCountryChart) {
            const fraudData = <?= json_encode($spam_monitoring['fraud_by_country'] ?? []) ?>;
            
            if (fraudData && Object.keys(fraudData).length > 0) {
                const labels = Object.keys(fraudData);
                const data = Object.values(fraudData);
                
                fraudCountryChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }
    
    function initializeFraudBrowserChart() {
        const ctx = document.getElementById('fraudByBrowserChart');
        if (ctx && !fraudBrowserChart) {
            const fraudData = <?= json_encode($spam_monitoring['fraud_by_browser'] ?? []) ?>;
            
            if (fraudData && Object.keys(fraudData).length > 0) {
                const labels = Object.keys(fraudData);
                const data = Object.values(fraudData);
                
                fraudBrowserChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: '<?= __('admin.fraud_attempts') ?>',
                            data: data,
                            backgroundColor: '#36A2EB',
                            borderColor: '#1E88E5',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        }
                    }
                });
            }
        }
    }

    // Initialize charts when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') {
            /* chart.min.js failed to load — charts will not render this session */
            console.warn('Security monitor: Chart.js not available. Charts skipped.');
            return;
        }
        setTimeout(function() {
            initializeSpamChart();
            initializeFraudCountryChart();
            initializeFraudBrowserChart();
        }, 100);
    });

    // Spam Table Filtering and Pagination (same as dashboard)
    let spamData = <?= json_encode($spam_monitoring['recent_spam']) ?>;
    let filteredData = [...spamData];
    let currentPage = 1;
    let entriesPerPage = 20;

    function filterSpamData() {
        const userFilter = document.getElementById('userFilter').value;
        const campaignTypeFilter = document.getElementById('campaignTypeFilter').value;
        const ipFilter = document.getElementById('ipFilter').value;
        const searchTerm = document.getElementById('searchSpam').value.toLowerCase();

        filteredData = spamData.filter(spam => {
            // User filter
            if (userFilter && spam.data.user_id != userFilter) return false;
            
            // Campaign type filter
            if (campaignTypeFilter && spam.data.campaign_type !== campaignTypeFilter) return false;
            
            // IP filter
            if (ipFilter === 'single' && (spam.data.ip_count > 1)) return false;
            if (ipFilter === 'multiple' && (spam.data.ip_count <= 1)) return false;
            
            // Search filter
            if (searchTerm) {
                const searchText = [
                    spam.data.firstname,
                    spam.data.lastname,
                    spam.data.email,
                    spam.data.campaign_name,
                    spam.data.ip
                ].join(' ').toLowerCase();
                
                if (!searchText.includes(searchTerm)) return false;
            }
            
            return true;
        });

        currentPage = 1;
        updateTable();
        updatePagination();
    }

    function updateTable() {
        const tbody = document.getElementById('spamTableBody');
        const startIndex = (currentPage - 1) * entriesPerPage;
        const endIndex = startIndex + entriesPerPage;
        const pageData = filteredData.slice(startIndex, endIndex);

        if (pageData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted"><?= __('admin.no_spam_attempts_found') ?></td></tr>';
            return;
        }

        tbody.innerHTML = pageData.map(spam => {
            let timeDisplay = '';
            if (spam.count > 1) {
                timeDisplay = `<div class="d-flex flex-column">
                    <span class="fw-bold text-danger">${spam.count}x</span>
                    <small>${spam.time}</small>
                </div>`;
            } else {
                timeDisplay = spam.time;
            }

            let ipDisplay = '';
            if (spam.data.ip_count > 1) {
                ipDisplay = `<span class="badge bg-warning mb-1">${spam.data.ip_count} <?= __('admin.ips') ?></span><br>
                    <div style="max-width: 150px; word-break: break-all;">
                        <code class="small">${spam.data.ip}</code>
                    </div>`;
            } else {
                ipDisplay = `<code class="small">${spam.data.ip}</code>`;
            }

            let campaignUrl = '';
            if (spam.data.form_id) {
                campaignUrl = `<?= base_url('admincontrol/form_manage/') ?>${spam.data.form_id}`;
            } else if (spam.data.product_id) {
                campaignUrl = `<?= base_url('admincontrol/updateproduct/') ?>${spam.data.product_id}`;
            } else if (spam.data.tools_id) {
                campaignUrl = `<?= base_url('integration/integration_tools_form/') ?>${spam.data.tool_type || 'banner'}/${spam.data.tools_id}`;
            }

            // Fraud score badge
            let fsVal = spam.data.fraud_score != null ? spam.data.fraud_score : null;
            let fsBadge = 'secondary';
            let fsLabel = '&ndash;';
            if (fsVal !== null) {
                fsVal = parseInt(fsVal);
                fsLabel = fsVal;
                if (fsVal <= 30)       fsBadge = 'success';
                else if (fsVal <= 70)  fsBadge = 'warning';
                else                   fsBadge = 'danger';
            }

            return `<tr>
                <td class="small">${timeDisplay}</td>
                <td class="small">
                    ${spam.data.firstname} ${spam.data.lastname}
                    <br><small class="text-muted">${spam.data.email}</small>
                </td>
                <td class="small">
                    <span class="badge bg-primary">${spam.data.campaign_type}</span><br>
                    ${campaignUrl ? 
                        `<a href="${campaignUrl}" class="text-decoration-none" target="_blank">
                            <small class="text-primary">${spam.data.campaign_name}</small>
                            <i class="fas fa-external-link-alt ms-1 small"></i>
                        </a>` : 
                        `<small>${spam.data.campaign_name}</small>`
                    }
                </td>
                <td class="small">${ipDisplay}</td>
                <td class="small">
                    <span class="badge bg-danger"><?= __('admin.blocked') ?></span>
                </td>
                <td class="small text-center">
                    <span class="badge bg-${fsBadge}">${fsLabel}</span>
                </td>
            </tr>`;
        }).join('');

        // Update entry count display
        const totalEntries = filteredData.length;
        const showingStart = totalEntries > 0 ? startIndex + 1 : 0;
        const showingEnd = Math.min(endIndex, totalEntries);
        
        document.getElementById('showingStart').textContent = showingStart;
        document.getElementById('showingEnd').textContent = showingEnd;
        document.getElementById('totalEntries').textContent = totalEntries;
        document.getElementById('filterResults').textContent = 
            filteredData.length !== spamData.length ? `(filtered from ${spamData.length} total)` : '';
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredData.length / entriesPerPage);
        const pagination = document.getElementById('paginationControls');

        if (totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let paginationHTML = '';
        
        // Previous button
        paginationHTML += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;"><?= __('admin.previous') ?></a>
        </li>`;

        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>`;
        }

        // Next button
        paginationHTML += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;"><?= __('admin.next') ?></a>
        </li>`;

        pagination.innerHTML = paginationHTML;
    }

    function changePage(page) {
        const totalPages = Math.ceil(filteredData.length / entriesPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            updateTable();
            updatePagination();
        }
    }

    function clearFilters() {
        document.getElementById('userFilter').value = '';
        document.getElementById('campaignTypeFilter').value = '';
        document.getElementById('ipFilter').value = '';
        document.getElementById('searchSpam').value = '';
        document.getElementById('entriesPerPage').value = '20';
        entriesPerPage = 20;
        filterSpamData();
    }

    // Event listeners
    document.getElementById('userFilter').addEventListener('change', filterSpamData);
    document.getElementById('campaignTypeFilter').addEventListener('change', filterSpamData);
    document.getElementById('ipFilter').addEventListener('change', filterSpamData);
    document.getElementById('searchSpam').addEventListener('input', filterSpamData);
    document.getElementById('entriesPerPage').addEventListener('change', function() {
        entriesPerPage = parseInt(this.value);
        currentPage = 1;
        updateTable();
        updatePagination();
    });
    document.getElementById('clearFilters').addEventListener('click', clearFilters);

    // Initialize table
    document.addEventListener('DOMContentLoaded', function() {
        updateTable();
        updatePagination();
        
        // Start auto-refresh after initial load
        setTimeout(initAutoRefresh, 5000); // Wait 5 seconds after page load
    });

    // Auto-refresh functionality for Security Monitor
    function initAutoRefresh() {
        // Auto-refresh security data every 30 seconds
        function refreshSecurityData() {
            fetch('<?= base_url('admincontrol/get_security_data_ajax') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update header status
                        updateHeaderStatus(data.total_recent_threats);
                        
                        // Update statistics cards
                        updateStatistics(data.spam_stats, data.fraud_stats);
                        
                        // Update charts
                        updateCharts(data.spam_by_type, data.fraud_by_country, data.fraud_by_browser);
                        
                        // Update tables
                        updateRecentData(data.recent_spam, data.recent_fraud_attempts);
                        
                        console.log('Security Monitor updated at:', data.timestamp);
                    } else {
                        console.warn('Failed to refresh security data:', data.error);
                    }
                })
                .catch(error => {
                    console.warn('Security Monitor refresh error:', error);
                });
        }
        
        // Start auto-refresh timer
        setInterval(refreshSecurityData, 30000); // Every 30 seconds
    }
    
    // Function to update header status
    function updateHeaderStatus(threatCount) {
        const headerDiv = document.querySelector('.row.mb-4 .col-12 > div');
        const statusText = document.querySelector('.row.mb-4 .col-12 .text-white span');
        
        if (headerDiv && statusText) {
            if (threatCount > 0) {
                // Red header for threats
                headerDiv.className = headerDiv.className.replace('bg-success', 'bg-danger');
                statusText.innerHTML = `<?= __('admin.security_issues_detected') ?> - ${threatCount} <?= __('admin.threats_in_last_hour') ?>`;
            } else {
                // Green header for secure
                headerDiv.className = headerDiv.className.replace('bg-danger', 'bg-success');
                statusText.innerHTML = `<?= __('admin.system_secure') ?> - 0 <?= __('admin.threats_in_last_hour') ?>`;
            }
        }
    }
    
    // Function to update statistics cards
    function updateStatistics(spamStats, fraudStats) {
        // Update spam statistics
        if (spamStats) {
            updateStatCard('total-spam-attempts', spamStats.total_attempts || 0);
            updateStatCard('unique-spam-users', spamStats.unique_users || 0);
            updateStatCard('unique-spam-ips', spamStats.unique_ips || 0);
            updateStatCard('days-with-spam', spamStats.days_with_spam || 0);
        }
        
        // Update fraud statistics
        if (fraudStats) {
            updateStatCard('total-fraud-attempts', fraudStats.total_attempts || 0);
            updateStatCard('unique-fraud-users', fraudStats.unique_users || 0);
            updateStatCard('unique-fraud-ips', fraudStats.unique_ips || 0);
            updateStatCard('localhost-blocks', fraudStats.localhost_blocks || 0);
        }
    }
    
    // Helper function to update individual stat cards
    function updateStatCard(cardId, value) {
        const element = document.getElementById(cardId);
        if (element) {
            element.textContent = value.toLocaleString();
        }
    }
    
    // Function to update charts (simplified - just data refresh)
    function updateCharts(spamByType, fraudByCountry, fraudByBrowser) {
        // Note: Chart.js updates can be complex, so we'll do a simple refresh for now
        // In a full implementation, you'd update the chart data objects
        console.log('Chart data updated:', { spamByType, fraudByCountry, fraudByBrowser });
    }
    
    // Function to update recent data tables
    function updateRecentData(recentSpam, recentFraud) {
        // Update spam table data
        if (recentSpam && Array.isArray(recentSpam)) {
            spamData = recentSpam; // Update global spamData variable
            updateTable(); // Refresh the displayed table
        }
        
        // Note: Fraud table update would be similar if we had it as a separate table
        console.log('Table data updated:', { spamCount: recentSpam ? recentSpam.length : 0 });
    }

    $(document).ready(function(){
        const startTime = PerformanceMonitor.start('security-monitor-performance');
        
        setTimeout(() => {
            PerformanceMonitor.end(startTime, 'security-monitor-performance');
        }, 300);
    });
</script>

<?= performance_indicator_html('security-monitor-performance') ?>
<?= render_performance_indicator() ?>