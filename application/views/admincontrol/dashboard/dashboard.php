<?php
$db =& get_instance();
$userdetails = get_object_vars($db->user_info());
$products = $db->Product_model;
$serverReq = checkReq();
$notifications_count = $products->getnotificationnew_count('admin',null);

// Use variables passed from controller instead of direct model access
$enable_disable = array ('store_is_enable' => $store_is_enable);
?>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/flag/css/main.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/table/datatables.min.css") ?>">


<!-- System notifications -->
<div id="notificationDiv"></div>

<!-- Main Dashboard Container -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- System Errors & Warnings -->
            <div class="server-errors"> 
                <?php
                    // Use variables passed from controller instead of direct model access
                    $setting_market_vendor_status = isset($vendor_market_data['marketvendorstatus']) ? $vendor_market_data['marketvendorstatus'] : 0;
                    $setting_vendor_min_deposit = isset($site['vendor_min_deposit']) ? $site['vendor_min_deposit'] : 0;
                    $setting_vendor_deposit_status = isset($vendor_store_data['depositstatus']) ? $vendor_store_data['depositstatus'] : 0;

                    if($setting_market_vendor_status == 1 && $setting_vendor_min_deposit == 0 && $setting_vendor_deposit_status == 1){
                        echo "<div class='alert alert-danger border-0 shadow-sm'>";
                        echo "<div class='d-flex align-items-center'>";
                        echo "<i class='fas fa-exclamation-triangle me-3 fs-4'></i>";
                        echo "<div>";
                        echo "<strong>" . __('admin.vendor_min_deposit_alert') . "</strong><br>";
                        echo "<a href='" . base_url('/admincontrol/saas_setting') . "' class='alert-link'>" . __('admin.set_here') . "</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
                

            </div>

            <!-- Security Status Logic -->
            <?php
                // Simple Security Status Detection
                $security_status = 'secure'; // secure, active, warning, critical
                $total_recent_threats = 0;
                
                if (isset($spam_monitoring)) {
                    // Check for recent activity (last hour)
                    $one_hour_ago = date('Y-m-d H:i:s', strtotime('-1 hour'));
                    
                    // Count recent spam attempts
                    if (isset($spam_monitoring['recent_spam'])) {
                        foreach ($spam_monitoring['recent_spam'] as $spam) {
                            if (isset($spam['last_attempt_timestamp']) && $spam['last_attempt_timestamp'] >= $one_hour_ago) {
                                $total_recent_threats += $spam['count'];
                            }
                        }
                    }
                    
                    // Count recent fraud attempts
                    if (isset($spam_monitoring['recent_fraud_attempts'])) {
                        foreach ($spam_monitoring['recent_fraud_attempts'] as $fraud) {
                            if ($fraud['created_at'] >= $one_hour_ago) {
                                $total_recent_threats++;
                            }
                        }
                    }
                    
                    // Determine security status
                    if ($total_recent_threats >= 50) {
                        $security_status = 'critical';
                    } elseif ($total_recent_threats >= 20) {
                        $security_status = 'warning';
                    } elseif ($total_recent_threats >= 5) {
                        $security_status = 'active';
                    }
                }
            ?>

            <!-- Dashboard Header -->
            <?php
                $hour = (int)date('H');
                if ($hour < 12) $greeting = __('admin.good_morning');
                elseif ($hour < 18) $greeting = __('admin.good_afternoon');
                else $greeting = __('admin.good_evening');

                $sec_tile_class = 'qa-security-safe';
                if ($total_recent_threats >= 20)     $sec_tile_class = 'qa-security-danger';
                elseif ($total_recent_threats >= 5)  $sec_tile_class = 'qa-security-warn';
            ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dash-hero">

                        <!-- Top row: greeting + status indicators -->
                        <div class="dash-hero-top">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <div class="dash-hero-avatar">
                                    <img src="<?= $products->getAvatar($userdetails['avatar']); ?>"
                                         alt="<?= htmlspecialchars($userdetails['firstname']) ?>"
                                         class="rounded-circle">
                                    <span class="dash-hero-status-dot"></span>
                                </div>
                                <div class="dash-hero-info">
                                    <h4 class="dash-hero-greeting"><?= $greeting ?>, <?= htmlspecialchars($userdetails['firstname']) ?></h4>
                                    <div class="dash-hero-meta">
                                        <?php if (function_exists('get_admin_role_label') && function_exists('get_admin_role_badge_class')): ?>
                                        <span class="dash-hero-tag <?= get_admin_role_badge_class() ?>">
                                            <i class="bi bi-shield-check"></i> <?= get_admin_role_label() ?>
                                        </span>
                                        <?php endif; ?>
                                        <span class="dash-hero-tag dash-hero-tag-outline d-none d-sm-inline-flex">
                                            <i class="fas fa-clock"></i> <span id="session-countdown">--:--</span>
                                        </span>
                                        <span class="dash-hero-tag dash-hero-tag-outline d-none d-md-inline-flex">
                                            v<?= SCRIPT_VERSION ?>
                                        </span>
                                        <span class="dash-hero-tag dash-hero-tag-outline d-none d-lg-inline-flex">
                                            <i class="fas fa-globe"></i>
                                            <?php if ($geolocation_status['status'] === 'working'): ?>
                                                <?= $geolocation_status['working_services'] ?>/<?= $geolocation_status['total_services'] ?> <?= __('admin.active') ?>
                                            <?php else: ?>
                                                <a href="<?= base_url('admincontrol/geolocation_status') ?>" class="text-danger text-decoration-none" target="_blank"><?= __('admin.failed') ?></a>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick-action tiles -->
                        <div class="dash-hero-actions">
                            <a href="<?= base_url('admincontrol/security_monitor') ?>" class="dash-hero-tile <?= $sec_tile_class ?>" target="_blank" data-bs-toggle="tooltip" title="<?= __('admin.security_monitor') ?>">
                                <span class="dash-hero-tile-ico dash-tile-security"><i class="fas fa-shield-alt"></i></span>
                                <span class="dash-hero-tile-lbl"><?= __('admin.security') ?></span>
                                <?php if ($total_recent_threats > 0): ?>
                                    <span class="dash-hero-tile-badge"><?= $total_recent_threats ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="<?= base_url('admincontrol/analytics_dashboard') ?>" class="dash-hero-tile" target="_blank" data-bs-toggle="tooltip" title="<?= __('admin.analytics') ?>">
                                <span class="dash-hero-tile-ico dash-tile-analytics"><i class="fas fa-chart-line"></i></span>
                                <span class="dash-hero-tile-lbl"><?= __('admin.analytics') ?></span>
                            </a>
                            <a href="<?= base_url('admincontrol/s2s_analytics') ?>" class="dash-hero-tile" data-bs-toggle="tooltip" title="<?= __('admin.nav_s2s_analytics') ?>">
                                <span class="dash-hero-tile-ico dash-tile-s2s"><i class="fas fa-server"></i></span>
                                <span class="dash-hero-tile-lbl">S2S</span>
                            </a>
                            <button type="button" class="dash-hero-tile" onclick="loadWelcomeSystem()" data-bs-toggle="tooltip" title="<?= __('admin.welcome_guide_tooltip') ?>">
                                <span class="dash-hero-tile-ico dash-tile-guide"><i class="fas fa-graduation-cap"></i></span>
                                <span class="dash-hero-tile-lbl"><?= __('admin.guide') ?></span>
                            </button>
                            <a href="<?= base_url() ?>" class="dash-hero-tile" target="_blank" data-bs-toggle="tooltip" title="<?= __('admin.view_frontend') ?>">
                                <span class="dash-hero-tile-ico dash-tile-site"><i class="fas fa-globe"></i></span>
                                <span class="dash-hero-tile-lbl"><?= __('admin.view_site') ?></span>
                            </a>
                            <?php if(isset($store_is_enable) && $store_is_enable == 1): ?>
                            <a href="<?= base_url('store') ?>" class="dash-hero-tile" target="_blank" data-bs-toggle="tooltip" title="<?= __('admin.view_store') ?>">
                                <span class="dash-hero-tile-ico dash-tile-store"><i class="fas fa-store"></i></span>
                                <span class="dash-hero-tile-lbl"><?= __('admin.store') ?></span>
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Quick Create -->
                        <div class="dash-hero-qc">
                            <div class="dash-hero-qc-label">
                                <i class="fas fa-bolt"></i>
                                <?= __('admin.nav_quick_create') ?>
                            </div>
                            <div class="dash-hero-qc-items">
                                <?php if(isset($market_tools_is_enable) && $market_tools_is_enable == 1): ?>
                                <a href="<?= base_url('integration/integration_tools_form') ?>" class="dash-qb-btn" style="--qb-accent:#6366f1">
                                    <span class="dash-qb-ico" style="background:rgba(99,102,241,.12);color:#6366f1"><i class="fas fa-bullhorn"></i></span>
                                    <span><?= __('admin.nav_create_campaign') ?></span>
                                </a>
                                <?php endif; ?>
                                <?php if(isset($store_is_enable) && $store_is_enable == 1): ?>
                                <a href="<?= base_url('admincontrol/addproduct') ?>" class="dash-qb-btn" style="--qb-accent:#10b981">
                                    <span class="dash-qb-ico" style="background:rgba(16,185,129,.12);color:#10b981"><i class="fas fa-plus-circle"></i></span>
                                    <span><?= __('admin.nav_add_product') ?></span>
                                </a>
                                <?php endif; ?>
                                <a href="<?= base_url('admincontrol/userslist') ?>" class="dash-qb-btn" style="--qb-accent:#f59e0b">
                                    <span class="dash-qb-ico" style="background:rgba(245,158,11,.12);color:#f59e0b"><i class="fas fa-user-plus"></i></span>
                                    <span><?= __('admin.nav_add_affiliate') ?></span>
                                </a>
                                <a href="<?= base_url('admincontrol/store_orders') ?>" class="dash-qb-btn" style="--qb-accent:#3b82f6">
                                    <span class="dash-qb-ico" style="background:rgba(59,130,246,.12);color:#3b82f6"><i class="fas fa-shopping-cart"></i></span>
                                    <span><?= __('admin.nav_all_orders') ?></span>
                                </a>
                                <?php if (function_exists('can_admin') && (can_admin('settings') || can_admin('settings.payment'))): ?>
                                <a href="<?= base_url('admincontrol/paymentsetting') ?>" class="dash-qb-btn" style="--qb-accent:#64748b">
                                    <span class="dash-qb-ico" style="background:rgba(100,116,139,.12);color:#64748b"><i class="fas fa-cog"></i></span>
                                    <span><?= __('admin.nav_settings') ?></span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Mobile App Status -->
                        <?php if (isset($mobile_app_connections)): ?>
                        <div class="dash-hero-app-status">
                            <?php if ($mobile_app_connections['connected']): ?>
                            <span class="dash-hero-app-dot connected"></span>
                            <i class="fas fa-mobile-alt"></i>
                            <span><?= $mobile_app_connections['active_users'] ?> <?= __('admin.active') ?></span>
                            <span class="dash-hero-app-detail d-none d-sm-inline">
                                / <?= $mobile_app_connections['total_users'] ?> <?= __('admin.users') ?>
                            </span>
                            <?php else: ?>
                            <span class="dash-hero-app-dot"></span>
                            <i class="fas fa-mobile-alt"></i>
                            <span><?= __('admin.mobile_app_not_active') ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Key Metrics Row -->
            <div class="row mb-4">
                <!-- Admin Balance -->
                <div class="col-6 col-md-6 col-lg-3 mb-3 card-animate" data-animate-index="0">
                    <div class="dash-stat-card tblr-card h-100" style="--sc-accent:#3b82f6">
                        <div class="dash-sc-top">
                            <div class="dash-sc-icon" style="background:rgba(59,130,246,.12);color:#3b82f6">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="dash-sc-meta">
                                <div class="dash-sc-label"><?= __('admin.total_admin_balance') ?></div>
                                <div class="dash-sc-value"><?= $fun_c_format($admin_totals['admin_balance']) ?></div>
                            </div>
                        </div>
                        <div class="dash-sc-chips">
                            <div class="dash-sc-chip">
                                <i class="fas fa-arrow-up text-success me-1"></i>
                                <span class="dash-sc-chip-val"><?= $admin_totals['admin_balance_growth'] ?>%</span>
                                <span class="dash-sc-chip-lbl"><?= __('admin.growth') ?></span>
                            </div>
                            <div class="dash-sc-chip">
                                <i class="fas fa-calendar-day text-warning me-1"></i>
                                <span class="dash-sc-chip-val"><?= isset($today_transactions_count) ? $today_transactions_count : 0 ?></span>
                                <span class="dash-sc-chip-lbl"><?= __('admin.txn') ?> <?= __('admin.today') ?></span>
                            </div>
                        </div>
                        <div class="sparkline-container px-3 pb-2"><canvas id="sparkRevenue"></canvas></div>
                        <div class="dash-sc-footer">
                            <a href="<?= base_url('admincontrol/mywallet') ?>" class="dash-sc-link" target="_blank">
                                <i class="fas fa-eye me-1"></i><?= __('admin.view_wallet') ?>
                            </a>
                            <a href="<?= base_url('admincontrol/all_transaction') ?>" class="dash-sc-btn" target="_blank">
                                <i class="fas fa-exchange-alt me-1"></i><?= __('admin.transactions') ?>
                                <?php if(isset($today_transactions_count) && $today_transactions_count > 0): ?>
                                    <span class="dash-sc-badge"><?= $today_transactions_count ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Online Users -->
                <div class="col-6 col-md-6 col-lg-3 mb-3 card-animate" data-animate-index="1">
                    <div class="dash-stat-card tblr-card h-100" style="--sc-accent:#10b981">
                        <div class="dash-sc-top">
                            <div class="dash-sc-icon" style="background:rgba(16,185,129,.12);color:#10b981">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="dash-sc-meta">
                                <div class="dash-sc-label"><?= __('admin.online_users') ?></div>
                                <div class="dash-sc-value" style="font-size:1.6rem">
                                    <?= (int)$online_count['admin']['online'] + (int)$online_count['vendor']['online'] + (int)$online_count['user']['online'] + (int)$online_count['client']['online'] ?>
                                    <span class="d-none d-sm-inline" style="font-size:.9rem;font-weight:500;color:#94a3b8;margin-left:4px"><?= __('admin.online') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="dash-sc-online-grid">
                            <div class="dash-sc-online-item" style="border-color:#6366f1">
                                <span class="dash-sc-online-num ajax-online-admin" style="color:#6366f1"><?= (int)$online_count['admin']['online'] ?></span>
                                <span class="dash-sc-online-lbl"><?= __('admin.admin_admin') ?></span>
                            </div>
                            <div class="dash-sc-online-item" style="border-color:#10b981">
                                <span class="dash-sc-online-num ajax-online-vendor" style="color:#10b981"><?= (int)$online_count['vendor']['online'] ?></span>
                                <span class="dash-sc-online-lbl"><?= __('admin.admin_vendor') ?></span>
                            </div>
                            <div class="dash-sc-online-item" style="border-color:#f59e0b">
                                <span class="dash-sc-online-num ajax-online-affiliate" style="color:#f59e0b"><?= (int)$online_count['user']['online'] ?></span>
                                <span class="dash-sc-online-lbl"><?= __('admin.admin_affiliate') ?></span>
                            </div>
                            <div class="dash-sc-online-item" style="border-color:#3b82f6">
                                <span class="dash-sc-online-num ajax-online-client" style="color:#3b82f6"><?= (int)$online_count['client']['online'] ?></span>
                                <span class="dash-sc-online-lbl"><?= __('admin.admin_client') ?></span>
                            </div>
                        </div>
                        <div class="dash-sc-footer">
                            <button type="button" class="dash-sc-link" data-bs-toggle="modal" data-bs-target="#invitationLinksModal">
                                <i class="fas fa-share me-1"></i><?= __('admin.admin_invitation') ?>
                            </button>
                            <a href="<?= base_url('admincontrol/userslist') ?>" class="dash-sc-btn" target="_blank">
                                <i class="fas fa-users me-1"></i><?= __('admin.users') ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="col-6 col-md-6 col-lg-3 mb-3 card-animate" data-animate-index="2">
                    <?php
                        $last_db_backup_time = 0;
                        if (!empty($backups) && isset($backups[0]['file'])) {
                            $filename = $backups[0]['file'];
                            if (preg_match('/_(\d{10,})\.zip$/', $filename, $matches)) {
                                $last_db_backup_time = (int)$matches[1];
                            }
                        }

                        $last_script_backup_time = 0;
                        if (!empty($script_backups) && isset($script_backups[0]['file'])) {
                            $filename = $script_backups[0]['file'];
                            if (preg_match('/_(\d{8}_\d{6})\.zip$/', $filename, $matches)) {
                                $date = DateTime::createFromFormat('Ymd_His', $matches[1]);
                                if ($date) {
                                    $last_script_backup_time = $date->getTimestamp();
                                }
                            }
                        }

                        $last_backup_time = max($last_db_backup_time, $last_script_backup_time);
                        
                        $has_any_backups = (!empty($backups) || !empty($script_backups));
                    ?>

                    <?php if (!empty($backup_warnings)): ?>
                        <div class="dash-stat-card tblr-card h-100" style="--sc-accent:#f59e0b">
                            <div class="dash-sc-top">
                                <div class="dash-sc-icon" style="background:rgba(245,158,11,.12);color:#f59e0b">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="dash-sc-meta">
                                    <div class="dash-sc-label"><?= __('admin.backup_status') ?></div>
                                    <div class="dash-sc-value" style="color:#f59e0b">
                                        <i class="fas fa-exclamation-triangle me-1"></i><?= __('admin.backup_warnings') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 px-sm-3 pb-2 flex-grow-1">
                                <?php foreach ($backup_warnings as $warning): ?>
                                    <div class="dash-sc-warn-item">
                                        <i class="fas fa-dot-circle text-warning me-1 flex-shrink-0" style="font-size:.5rem"></i>
                                        <small><?= $warning ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="dash-sc-footer">
                                <a href="<?= base_url('admincontrol/backup') ?>" class="dash-sc-btn w-100" target="_blank">
                                    <i class="fas fa-tools me-1"></i><?= __('admin.go_to_backup_page') ?>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php
                        $total_size = 0;
                        $backup_dir = APPPATH . 'backup/mysql/';
                        foreach ($backups as $backup) {
                            $file_path = $backup_dir . $backup['file'];
                            if (file_exists($file_path)) $total_size += filesize($file_path);
                        }
                        $script_backup_dir = APPPATH . 'backup/script/';
                        foreach ($script_backups as $backup) {
                            $file_path = $script_backup_dir . $backup['file'];
                            if (file_exists($file_path)) $total_size += filesize($file_path);
                        }
                        $sc_bkp_color = $has_any_backups ? '#10b981' : '#f59e0b';
                        ?>
                        <div class="dash-stat-card tblr-card h-100" style="--sc-accent:<?= $sc_bkp_color ?>">
                            <div class="dash-sc-top">
                                <div class="dash-sc-icon" style="background:<?= $has_any_backups ? 'rgba(16,185,129,.12)' : 'rgba(245,158,11,.12)' ?>;color:<?= $sc_bkp_color ?>">
                                    <i class="fas fa-<?= $has_any_backups ? 'shield-alt' : 'exclamation-triangle' ?>"></i>
                                </div>
                                <div class="dash-sc-meta">
                                    <div class="dash-sc-label"><?= __('admin.backup_status') ?></div>
                                    <div class="dash-sc-value" style="font-size:1rem;color:<?= $sc_bkp_color ?>">
                                        <?php if ($has_any_backups): ?>
                                            <?php if ($last_backup_time > 0): ?>
                                                <i class="fas fa-check-circle me-1"></i><?= date('M d, H:i', $last_backup_time) ?>
                                            <?php else: ?>
                                                <i class="fas fa-check-circle me-1"></i><?= __('admin.backups_available') ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <i class="fas fa-exclamation-triangle me-1"></i><?= __('admin.no_backup_found') ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="dash-sc-chips">
                                <div class="dash-sc-chip <?= empty($backups) ? 'dash-sc-chip-warn' : '' ?>">
                                    <i class="fas fa-database me-1" style="color:<?= empty($backups) ? '#f59e0b' : '#3b82f6' ?>"></i>
                                    <span class="dash-sc-chip-val"><?= empty($backups) ? '0' : count($backups) ?></span>
                                    <span class="dash-sc-chip-lbl"><?= __('admin.database') ?></span>
                                </div>
                                <div class="dash-sc-chip <?= empty($script_backups) ? 'dash-sc-chip-warn' : '' ?>">
                                    <i class="fas fa-code me-1" style="color:<?= empty($script_backups) ? '#f59e0b' : '#6366f1' ?>"></i>
                                    <span class="dash-sc-chip-val"><?= empty($script_backups) ? '0' : count($script_backups) ?></span>
                                    <span class="dash-sc-chip-lbl"><?= __('admin.script') ?></span>
                                </div>
                                <?php if ($total_size > 0): ?>
                                <div class="dash-sc-chip" style="flex:1 1 100%">
                                    <i class="fas fa-hdd me-1 text-muted"></i>
                                    <span class="dash-sc-chip-val">
                                        <?php
                                        if ($total_size < 1024) echo number_format($total_size, 2) . ' B';
                                        elseif ($total_size < 1048576) echo number_format($total_size / 1024, 2) . ' KB';
                                        else echo number_format($total_size / 1048576, 2) . ' MB';
                                        ?>
                                    </span>
                                    <span class="dash-sc-chip-lbl"><?= __('admin.total_size') ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="dash-sc-footer">
                                <a href="<?= base_url('admincontrol/backup') ?>" class="dash-sc-btn w-100" target="_blank">
                                    <i class="fas fa-cog me-1"></i><?= __('admin.my_backups') ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- System Info -->
                <div class="col-6 col-md-6 col-lg-3 mb-3 card-animate" data-animate-index="3">
                    <?php 
                    $license_data = function_exists('license_easy_get_local_data') ? license_easy_get_local_data() : [];
                    $license_type = isset($license_data['license_type']) ? $license_data['license_type'] : '';
                    $is_extended = stripos($license_type, 'extended') !== false;
                    $mysql_version = $this->db->query('SELECT VERSION() as version')->row()->version;
                    ?>
                    <div class="dash-stat-card tblr-card h-100" style="--sc-accent:#8b5cf6">
                        <div class="dash-sc-top">
                            <div class="dash-sc-icon" style="background:rgba(139,92,246,.12);color:#8b5cf6">
                                <i class="fas fa-server"></i>
                            </div>
                            <div class="dash-sc-meta">
                                <div class="dash-sc-label"><?= __('admin.system_info') ?></div>
                                <div class="dash-sc-value" style="font-size:1.1rem">
                                    <span style="color:#8b5cf6">v<?= $current_version ?></span>
                                    <?php if ($license_type): ?>
                                        <span class="badge d-none d-sm-inline-block" style="font-size:.65rem;background:<?= $is_extended ? '#f59e0b' : '#6366f1' ?>;padding:3px 8px;border-radius:20px">
                                            <i class="fas fa-<?= $is_extended ? 'crown' : 'star' ?> me-1"></i><?= htmlspecialchars($license_type) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="dash-sc-chips">
                            <div class="dash-sc-chip">
                                <i class="fab fa-php me-1" style="color:#6366f1"></i>
                                <span class="dash-sc-chip-val"><?= phpversion() ?></span>
                                <span class="dash-sc-chip-lbl"><?= __('admin.php') ?></span>
                            </div>
                            <div class="dash-sc-chip">
                                <i class="fas fa-database me-1" style="color:#3b82f6"></i>
                                <span class="dash-sc-chip-val"><?= explode('-', $mysql_version)[0] ?></span>
                                <span class="dash-sc-chip-lbl"><?= __('admin.database') ?></span>
                            </div>
                        </div>
                        <div class="dash-sc-footer">
                            <a href="<?= base_url('admincontrol/script_details') ?>" class="dash-sc-btn w-100" target="_blank">
                                <i class="fas fa-key me-1"></i><?= __('admin.license_details') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- V14: Performance Overview with Ring Chart & Sparklines -->
            <?php if(isset($trends)): ?>
            <div class="row mb-4 card-animate" data-animate-index="5">
                <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                    <div class="card tblr-card rounded-4 shadow-sm border-0 p-4 h-100 card-hover-lift performance-overview-card">
                        <h5 class="fw-bold mb-3"><i class="fas fa-chart-pie text-purple me-2" style="color:#8b5cf6"></i><?= __('admin.performance_overview') ?></h5>
                        <div class="ring-chart-container mx-auto">
                            <canvas id="adminRingChart"></canvas>
                            <div class="ring-chart-center-text">
                                <div class="ring-value" style="color:var(--v14-text-primary)"><?= number_format(($total_external_orders ?? 0) + ($total_store_orders ?? 0) + ($s2s_summary['total_s2s_orders'] ?? 0)) ?></div>
                                <div class="ring-label text-muted"><?= __('admin.total_orders') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card tblr-card rounded-4 shadow-sm border-0 p-4 h-100 card-hover-lift">
                        <h5 class="fw-bold mb-3"><i class="fas fa-chart-area text-primary me-2"></i><?= __('admin.seven_day_trends') ?></h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background:var(--v14-stat-blue)">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="fw-semibold text-primary"><?= __('admin.orders') ?></small>
                                        <small class="fw-bold"><?= array_sum($trends['orders']) ?></small>
                                    </div>
                                    <div class="sparkline-container"><canvas id="sparkOrders"></canvas></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background:var(--v14-stat-green)">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="fw-semibold text-success"><?= __('admin.commissions') ?></small>
                                        <small class="fw-bold"><?= $fun_c_format(array_sum($trends['commissions'])) ?></small>
                                    </div>
                                    <div class="sparkline-container"><canvas id="sparkCommissions"></canvas></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background:var(--v14-stat-purple)">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="fw-semibold" style="color:#8b5cf6"><?= __('admin.clicks') ?></small>
                                        <small class="fw-bold"><?= number_format(array_sum($trends['clicks'])) ?></small>
                                    </div>
                                    <div class="sparkline-container"><canvas id="sparkClicks"></canvas></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background:var(--v14-stat-orange)">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="fw-semibold text-warning"><?= __('admin.store_orders') ?></small>
                                        <small class="fw-bold"><?= array_sum($trends['store_orders']) ?></small>
                                    </div>
                                    <div class="sparkline-container"><canvas id="sparkStoreOrders"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- S2S Tracking Overview -->
            <div class="row mb-4 card-animate" data-animate-index="6">
                <div class="col-12">
                    <div class="bg-white tblr-card rounded-4 shadow-sm border-0 p-4 card-hover-lift">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0"><i class="fas fa-server text-primary me-2"></i><?= __('admin.s2s_tracking_overview') ?></h5>
                            <a href="<?= base_url('admincontrol/s2s_analytics') ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="fas fa-chart-line me-1"></i><?= __('admin.view_full_analytics') ?>
                            </a>
                        </div>
                        <div class="row g-3">
                            <div class="col-6 col-lg-3">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-primary mb-0"><?= number_format($s2s_summary['total_s2s_orders'] ?? 0) ?></h3>
                                    <small class="text-muted fw-semibold"><?= __('admin.s2s_conversions') ?></small>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-success mb-0"><?= $fun_c_format($s2s_summary['s2s_revenue'] ?? 0) ?></h3>
                                    <small class="text-muted fw-semibold"><?= __('admin.s2s_revenue') ?></small>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-info mb-0"><?= number_format($s2s_today ?? 0) ?></h3>
                                    <small class="text-muted fw-semibold"><?= __('admin.s2s_today') ?></small>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="bg-secondary bg-opacity-10 rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-secondary mb-0"><?= number_format($pixel_total_orders ?? 0) ?></h3>
                                    <small class="text-muted fw-semibold"><?= __('admin.pixel_conversions') ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 d-flex flex-wrap gap-3 align-items-center">
                            <span class="badge bg-primary rounded-pill px-3 py-2"><i class="fas fa-toggle-on me-1"></i><?= $s2s_enabled_campaigns ?? 0 ?> <?= __('admin.s2s_campaigns_enabled') ?></span>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-hand-holding-usd me-1"></i><?= __('admin.s2s_commission') ?>: <?= $fun_c_format($s2s_summary['s2s_commission'] ?? 0) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Translation Command Center -->
            <?php if(isset($translation_health) && !empty($translation_health)): ?>
            <?php
                $total_langs = count($translation_health);
                $total_keys_all = 0;
                $total_translated_all = 0;
                $incomplete_langs = [];
                $complete_langs = [];
                foreach($translation_health as $tl) {
                    $total_keys_all += $tl['count']['all'];
                    $total_translated_all += ($tl['count']['all'] - $tl['count']['missing']);
                    if($tl['count']['missing'] > 0) { $incomplete_langs[] = $tl; } else { $complete_langs[] = $tl; }
                }
                $overall_pct = $total_keys_all > 0 ? round(($total_translated_all / $total_keys_all) * 100) : 100;
                $ring_color = $overall_pct == 100 ? '#198754' : ($overall_pct > 60 ? '#0dcaf0' : ($overall_pct > 30 ? '#ffc107' : '#dc3545'));
                $ring_offset = 251.2 - (251.2 * $overall_pct / 100);
            ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded-4 shadow-sm border-0 overflow-hidden">
                        <div class="d-flex flex-wrap align-items-center justify-content-between p-3 px-4" style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative" style="width:52px;height:52px;">
                                    <svg viewBox="0 0 100 100" style="width:52px;height:52px;transform:rotate(-90deg)">
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="#e9ecef" stroke-width="8"/>
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="<?= $ring_color ?>" stroke-width="8" stroke-linecap="round" stroke-dasharray="251.2" stroke-dashoffset="<?= $ring_offset ?>" style="transition:stroke-dashoffset .6s ease"/>
                                    </svg>
                                    <span class="position-absolute top-50 start-50 translate-middle fw-bold" style="font-size:12px;"><?= $overall_pct ?>%</span>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?= __('admin.translation_health') ?></h6>
                                    <small class="text-muted"><?= $total_langs ?> <?= mb_strtolower(__('admin.nav_language')) ?> &middot; <?= number_format($default_lang_count['all']) ?> <?= mb_strtolower(__('admin.total_keys')) ?></small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-2 py-1"><i class="fas fa-check-circle me-1"></i><?= $languages_complete ?> <?= __('admin.complete') ?></span>
                                <?php if($languages_need_attention > 0): ?>
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-dark px-2 py-1"><i class="fas fa-exclamation-circle me-1"></i><?= $languages_need_attention ?> <?= __('admin.languages_need_attention') ?></span>
                                <?php endif; ?>
                                <a href="<?= base_url('admincontrol/language') ?>" class="btn btn-info btn-sm rounded-pill ms-1 text-white"><i class="fas fa-cog me-1"></i><?= __('admin.manage_translations') ?></a>
                            </div>
                        </div>

                        <?php if($languages_need_attention > 0): ?>
                        <div class="p-3 px-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div class="btn-group btn-group-sm" role="group" id="tlFilterGroup">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill rounded-end-0 active" data-tl-filter="need-work"><i class="fas fa-exclamation-triangle me-1"></i><?= __('admin.languages_need_attention') ?> (<?= count($incomplete_langs) ?>)</button>
                                    <button type="button" class="btn btn-outline-secondary" data-tl-filter="complete"><i class="fas fa-check me-1"></i><?= __('admin.complete') ?> (<?= count($complete_langs) ?>)</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill rounded-start-0" data-tl-filter="all"><?= __('admin.show_all') ?> (<?= $total_langs ?>)</button>
                                </div>
                                <small class="text-muted"><i class="fas fa-lightbulb text-warning me-1"></i><?= __('admin.translation_tip') ?> <a href="<?= base_url('admincontrol/language') ?>" class="fw-semibold"><?= __('admin.language_manager') ?></a></small>
                            </div>
                            <div id="tlCardWrapper" style="max-height:130px;overflow:hidden;transition:max-height .35s ease;position:relative;">
                                <div class="row g-2" id="tlCardGrid">
                                    <?php foreach($incomplete_langs as $tl): ?>
                                    <?php $pct = $tl['count']['all'] > 0 ? round((($tl['count']['all'] - $tl['count']['missing']) / $tl['count']['all']) * 100) : 100; ?>
                                    <div class="col-xl-3 col-lg-4 col-md-6 tl-card" data-tl-type="need-work">
                                        <div class="border rounded-3 p-3 h-100 position-relative" style="border-left: 3px solid <?= $pct > 50 ? '#ffc107' : '#dc3545' ?> !important;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <?php if(!empty($tl['flag'])): ?>
                                                <img src="<?= base_url($tl['flag']) ?>" width="24" height="16" class="rounded shadow-sm" style="object-fit:cover;" alt="" onerror="this.onerror=null;this.style.display='none'">
                                                <?php else: ?>
                                                <i class="fas fa-globe text-muted"></i>
                                                <?php endif; ?>
                                                <span class="fw-semibold text-truncate"><?= htmlspecialchars($tl['name']) ?></span>
                                                <?php if($tl['is_default']): ?><span class="badge bg-primary" style="font-size:9px;"><?= __('admin.default_label') ?></span><?php endif; ?>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <div class="progress flex-grow-1" style="height:6px;">
                                                    <div class="progress-bar <?= $pct > 50 ? 'bg-warning' : 'bg-danger' ?>" role="progressbar" style="width:<?= $pct ?>%;"></div>
                                                </div>
                                                <small class="fw-semibold <?= $pct > 50 ? 'text-warning' : 'text-danger' ?>" style="min-width:34px;"><?= $pct ?>%</small>
                                            </div>
                                            <small class="text-muted"><?= number_format($tl['count']['missing']) ?> <?= __('admin.missing_translations') ?></small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php foreach($complete_langs as $tl): ?>
                                    <div class="col-xl-3 col-lg-4 col-md-6 tl-card d-none" data-tl-type="complete">
                                        <div class="border rounded-3 p-3 h-100 position-relative" style="border-left: 3px solid #198754 !important;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <?php if(!empty($tl['flag'])): ?>
                                                <img src="<?= base_url($tl['flag']) ?>" width="24" height="16" class="rounded shadow-sm" style="object-fit:cover;" alt="" onerror="this.onerror=null;this.style.display='none'">
                                                <?php else: ?>
                                                <i class="fas fa-globe text-muted"></i>
                                                <?php endif; ?>
                                                <span class="fw-semibold text-truncate"><?= htmlspecialchars($tl['name']) ?></span>
                                                <?php if($tl['is_default']): ?><span class="badge bg-primary" style="font-size:9px;"><?= __('admin.default_label') ?></span><?php endif; ?>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <div class="progress flex-grow-1" style="height:6px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:100%;"></div>
                                                </div>
                                                <small class="fw-semibold text-success" style="min-width:34px;">100%</small>
                                            </div>
                                            <small class="text-success"><i class="fas fa-check-circle me-1"></i><?= __('admin.complete') ?></small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div id="tlFadeOverlay" style="position:absolute;bottom:0;left:0;right:0;height:40px;background:linear-gradient(transparent,#fff);pointer-events:none;transition:opacity .2s;"></div>
                            </div>
                            <?php if($total_langs > 4): ?>
                            <div class="text-center mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="tlExpandBtn">
                                    <i class="fas fa-chevron-down me-1"></i><?= __('admin.show_all') ?> (<?= count($incomplete_langs) ?>)
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="p-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-2" style="width:48px;height:48px;">
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            </div>
                            <p class="mb-0 fw-semibold text-success"><?= __('admin.all_caught_up') ?></p>
                            <small class="text-muted"><?= __('admin.languages_complete') ?> &mdash; <?= $total_langs ?> <?= mb_strtolower(__('admin.nav_language')) ?></small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <script>
            (function(){
                var fg = document.getElementById('tlFilterGroup');
                var wrapper = document.getElementById('tlCardWrapper');
                var expandBtn = document.getElementById('tlExpandBtn');
                var fadeOverlay = document.getElementById('tlFadeOverlay');
                var expanded = false;
                var showAll = '<?= __('admin.show_all') ?>';
                var showLess = '<?= __('admin.show_less') ?>';

                function getVisibleCount(filter) {
                    var cards = document.querySelectorAll('.tl-card');
                    var count = 0;
                    cards.forEach(function(c) {
                        var type = c.getAttribute('data-tl-type');
                        if(filter === 'all' || type === filter) count++;
                    });
                    return count;
                }

                function collapse() {
                    expanded = false;
                    wrapper.style.maxHeight = '130px';
                    if(fadeOverlay) fadeOverlay.style.opacity = '1';
                }

                function updateExpandBtn(filter) {
                    if(!expandBtn) return;
                    var count = getVisibleCount(filter);
                    if(count <= 4) {
                        expandBtn.parentElement.style.display = 'none';
                        wrapper.style.maxHeight = 'none';
                        if(fadeOverlay) fadeOverlay.style.opacity = '0';
                    } else {
                        expandBtn.parentElement.style.display = '';
                        if(!expanded) { collapse(); }
                        expandBtn.innerHTML = expanded
                            ? '<i class="fas fa-chevron-up me-1"></i>' + showLess
                            : '<i class="fas fa-chevron-down me-1"></i>' + showAll + ' (' + count + ')';
                    }
                }

                if(expandBtn) {
                    expandBtn.addEventListener('click', function(){
                        if(expanded) {
                            collapse();
                        } else {
                            expanded = true;
                            wrapper.style.maxHeight = wrapper.scrollHeight + 'px';
                            if(fadeOverlay) fadeOverlay.style.opacity = '0';
                        }
                        var activeFilter = fg ? fg.querySelector('.active') : null;
                        var filter = activeFilter ? activeFilter.getAttribute('data-tl-filter') : 'need-work';
                        updateExpandBtn(filter);
                    });
                }

                if(fg) {
                    fg.addEventListener('click', function(e){
                        var btn = e.target.closest('[data-tl-filter]');
                        if(!btn) return;
                        fg.querySelectorAll('.btn').forEach(function(b){ b.classList.remove('active'); });
                        btn.classList.add('active');
                        var filter = btn.getAttribute('data-tl-filter');
                        document.querySelectorAll('.tl-card').forEach(function(c){
                            var type = c.getAttribute('data-tl-type');
                            if(filter === 'all' || type === filter) { c.classList.remove('d-none'); } else { c.classList.add('d-none'); }
                        });
                        expanded = false;
                        updateExpandBtn(filter);
                    });
                }

                updateExpandBtn('need-work');
            })();
            </script>
            <?php endif; ?>

            <!-- System & Security Section (Collapsible) -->
            <?php if (function_exists('can_admin') && (can_admin('settings') || can_admin('settings.payment') || can_admin('settings.system'))): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <button class="btn btn-light w-100 d-flex align-items-center justify-content-between p-3 rounded-4 shadow-sm border-0" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardAdvancedSection" aria-expanded="false">
                        <span class="fw-bold text-muted">
                            <i class="fas fa-cogs me-2"></i><?= __('admin.system_settings') ?> &amp; <?= __('admin.admin_fraud') ?>
                        </span>
                        <i class="fas fa-chevron-down text-muted"></i>
                    </button>
                </div>
            </div>
            <div class="collapse" id="dashboardAdvancedSection">

            <!-- System Settings Row -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded-4 shadow-sm border-0 p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-cog text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-1"><?= __('admin.system_settings') ?></h4>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <?= __('admin.system_settings_desc') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <a href="<?= base_url('admincontrol/paymentsetting') ?>" class="btn btn-info px-3 py-2 rounded-pill">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    <?= __('admin.open_settings') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Row -->
            <div class="row mb-4">
                <!-- Security Settings -->
                <div class="col-lg-6 mb-4">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100 d-flex flex-column">
                        <div class="card-header bg-transparent border-0 p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-shield-alt text-danger fs-4"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0"><?= __('admin.admin_fraud') ?></h4>
                                </div>
                                <?php
                                    // Calculate protection level based on enabled security settings
                                    $security_settings = [
                                        'block_click_across_browser' => isset($site['block_click_across_browser']) ? $site['block_click_across_browser'] : 0,
                                        'enable_localhost_protection' => isset($site['enable_localhost_protection']) ? $site['enable_localhost_protection'] : 0,
                                        'enable_click_control' => isset($site['enable_click_control']) ? $site['enable_click_control'] : 0,
                                        'enable_action_control' => isset($site['enable_action_control']) ? $site['enable_action_control'] : 0,
                                    ];
                                    
                                    $enabled_count = array_sum($security_settings);
                                    $total_settings = count($security_settings);
                                    $security_percentage = round(($enabled_count / $total_settings) * 100);
                                    
                                    if ($security_percentage == 0) {
                                        $badge_class = 'bg-danger';
                                        $badge_icon = 'fas fa-times-circle';
                                    } elseif ($security_percentage <= 25) {
                                        $badge_class = 'bg-warning';
                                        $badge_icon = 'fas fa-exclamation-triangle';
                                    } elseif ($security_percentage <= 50) {
                                        $badge_class = 'bg-info';
                                        $badge_icon = 'fas fa-shield-alt';
                                    } elseif ($security_percentage <= 75) {
                                        $badge_class = 'bg-primary';
                                        $badge_icon = 'fas fa-shield-alt';
                                    } else {
                                        $badge_class = 'bg-success';
                                        $badge_icon = 'fas fa-check-circle';
                                    }
                                ?>
                                <span id="protection-badge" class="badge <?= $badge_class ?> rounded-pill px-3 py-2" data-protection-level="<?= $security_percentage ?>">
                                    <i class="<?= $badge_icon ?> me-1"></i>
                                    <?= $security_percentage ?>% <?= __('admin.secure') ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4 flex-grow-1">
                            <div class="row g-2">
                                <?php
                                    $settings = [
                                        'block_click_across_browser' => ['title' => __('admin.prevent_affiliate_self_fraud'), 'icon' => 'fas fa-ban', 'color' => 'danger'],
                                        'enable_localhost_protection' => ['title' => __('admin.enable_localhost_protection'), 'icon' => 'fas fa-home', 'color' => 'warning'],
                                        'enable_click_control' => ['title' => __('admin.enable_click_protection'), 'icon' => 'fas fa-mouse-pointer', 'color' => 'info'],
                                        'enable_action_control' => ['title' => __('admin.enable_action_protection'), 'icon' => 'fas fa-cog', 'color' => 'success'],
                                    ];
                                ?>
                                <?php foreach ($settings as $key => $setting): ?>
                                    <div class="col-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-<?= $setting['color'] ?> bg-opacity-10 rounded-circle p-2 me-2">
                                                        <i class="<?= $setting['icon'] ?> text-<?= $setting['color'] ?> fs-5"></i>
                                                    </div>
                                                    <h6 class="fw-semibold mb-0 small"><?= $setting['title'] ?></h6>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input 
                                                        type="checkbox" 
                                                        class="form-check-input update_all_settings" 
                                                        id="<?= $key ?>" 
                                                        data-setting_key="<?= $key ?>" 
                                                        data-setting_type="site"
                                                        <?= isset($site[$key]) && $site[$key] == 1 ? 'checked' : '' ?>
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top-0 p-0 mt-2 rounded-bottom-4">
                            <div class="p-2">
                                <div class="d-grid">
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm rounded-pill shadow-sm text-white fw-bold py-1 px-3 d-flex align-items-center justify-content-center gap-2" 
                                       onclick="var newTab = window.open('<?= base_url('admincontrol/paymentsetting') ?>', '_blank'); setTimeout(function(){ try { newTab.document.querySelector('a[href=&quot;#fraud&quot;]').click(); } catch(e) { setTimeout(function(){ try { newTab.document.querySelector('a[href=&quot;#fraud&quot;]').click(); } catch(e2) {} }, 2000); } }, 1500);">
                                        <i class="fas fa-shield-alt"></i>
                                        <span><?= __('admin.security_settings') ?></span>
                                        <i class="fas fa-arrow-right ms-1 small"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Modules Control Panel -->
                <div class="col-lg-6 mb-4">
                    <div class="bg-white rounded-4 shadow-sm border-0 h-100">
                        <div class="card-header bg-transparent border-0 p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-cogs text-primary fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0"><?= __('admin.system_modules') ?></h4>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-2">
                                    <?php
                                        $modules = [
                                            'ai_helper' => [
                                                'title' => __('admin.ai_helper'), 
                                                'icon' => 'fas fa-robot', 
                                                'color' => 'primary',
                                                'special' => true // Special handling for AI Helper
                                            ],
                                            'mlm_admin' => [
                                                'title' => __('admin.mlm_admin'), 
                                                'icon' => 'fas fa-sitemap', 
                                                'color' => 'info',
                                                'setting_type' => 'referlevel',
                                                'setting_key' => 'status',
                                                'sidebar' => 'mlm',
                                                'enabled' => $mlm_admin_is_enable ?? 0
                                            ],
                                            'saas_module' => [
                                                'title' => __('admin.saas_module'), 
                                                'icon' => 'fas fa-cloud', 
                                                'color' => 'success',
                                                'setting_type' => 'market_vendor',
                                                'setting_key' => 'marketvendorstatus',
                                                'sidebar' => 'saas',
                                                'enabled' => $saas_is_enable ?? 0
                                            ],
                                            'local_store' => [
                                                'title' => __('admin.local_store'), 
                                                'icon' => 'fas fa-store', 
                                                'color' => 'warning',
                                                'setting_type' => 'store',
                                                'setting_key' => 'status',
                                                'sidebar' => 'store',
                                                'enabled' => $store_is_enable ?? 0
                                            ],
                                            'membership_module' => [
                                                'title' => __('admin.membership_module'), 
                                                'icon' => 'fas fa-crown', 
                                                'color' => 'danger',
                                                'setting_type' => 'membership',
                                                'setting_key' => 'status',
                                                'sidebar' => 'membership',
                                                'enabled' => $membership_is_enable ?? 0
                                            ],
                                            'mlm_vendor' => [
                                                'title' => __('admin.mlm_vendor'), 
                                                'icon' => 'fas fa-users-cog', 
                                                'color' => 'secondary',
                                                'setting_type' => 'market_vendor',
                                                'setting_key' => 'vendormlmmodule',
                                                'sidebar' => 'vendormlmmodule',
                                                'enabled' => $mlm_vendor_is_enable ?? 0
                                            ],
                                            'vendor_deposit' => [
                                                'title' => __('admin.vendor_deposit'), 
                                                'icon' => 'fas fa-piggy-bank', 
                                                'color' => 'dark',
                                                'setting_type' => 'vendor',
                                                'setting_key' => 'depositstatus',
                                                'sidebar' => 'vendor',
                                                'enabled' => $vendor_deposit_is_enable ?? 0
                                            ],
                                            'award_level' => [
                                                'title' => __('admin.award_level'), 
                                                'icon' => 'fas fa-trophy', 
                                                'color' => 'warning',
                                                'setting_type' => 'award_level',
                                                'setting_key' => 'status',
                                                'sidebar' => 'award_level',
                                                'enabled' => $award_level_is_enable ?? 0
                                            ]
                                        ];
                                    ?>
                                    <?php foreach ($modules as $module_key => $module): ?>
                                        <div class="col-6">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-<?= $module['color'] ?> bg-opacity-10 rounded-circle p-2 me-2">
                                                            <i class="<?= $module['icon'] ?> text-<?= $module['color'] ?> fs-5"></i>
                                                        </div>
                                                        <h6 class="fw-semibold mb-0 small"><?= $module['title'] ?></h6>
                                                    </div>
                                                    
                                                    <?php if (isset($module['special']) && $module['special']): ?>
                                                        <!-- AI Helper Special Switch -->
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input update_all_settings" 
                                                                   type="checkbox" 
                                                                   id="ai_helper_enabled_dashboard"
                                                                   data-setting_key="ai_helper_enabled" 
                                                                   data-setting_type="ai_helper"
                                                                   <?= isset($ai_helper['ai_helper_enabled']) && $ai_helper['ai_helper_enabled'] == 1 ? 'checked' : '' ?>>
                                                        </div>
                                                    <?php else: ?>
                                                        <!-- Regular Module Switch -->
                                                        <div class="form-check form-switch">
                                                            <input 
                                                                type="checkbox" 
                                                                class="form-check-input addon-toggle" 
                                                                id="<?= $module_key ?>" 
                                                                data-setting_type="<?= $module['setting_type'] ?>"
                                                                data-setting_key="<?= $module['setting_key'] ?>"
                                                                data-sidebar="<?= $module['sidebar'] ?>"
                                                                <?= ((int)$module['enabled'] > 0) ? 'checked' : '' ?>
                                                            >
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div> <!-- End #dashboardAdvancedSection collapse -->
            <?php endif; ?>

            <!-- Top Affiliates Row -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded-4 shadow-sm border-0 p-4 card-hover-lift">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 flex-shrink-0">
                                    <i class="fas fa-trophy text-warning fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1"><?= __('admin.top_affiliates') ?></h5>
                                    <p class="text-muted mb-0 small">
                                        <i class="fas fa-users me-1"></i>
                                        <?= __('admin.view_manage_top_affiliates') ?>
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-auto">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill" type="button" id="timeFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-filter small me-1"></i>
                                        <span id="selectedTimeFilter"><?= __('admin.all_time') ?></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="timeFilterDropdown">
                                        <li><a class="dropdown-item small" href="#" data-value="All"><?= __('admin.all_time') ?></a></li>
                                        <li><a class="dropdown-item small" href="#" data-value="Week"><?= __('admin.this_week') ?></a></li>
                                        <li><a class="dropdown-item small" href="#" data-value="Month"><?= __('admin.this_month') ?></a></li>
                                        <li><a class="dropdown-item small" href="#" data-value="Year"><?= __('admin.this_year') ?></a></li>
                                    </ul>
                                </div>
                                <a href="<?= base_url('admincontrol/userslist') ?>" class="btn btn-warning btn-sm rounded-pill px-3">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    <?= __('admin.view_all_users') ?>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Content Area -->
                        <?php if (empty($populer_users)): ?>
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 empty-state-icon">
                                    <i class="fas fa-users text-muted fs-1"></i>
                                </div>
                                <?php if ($total_users_count == 0): ?>
                                    <h5 class="text-muted mb-2"><?= __('admin.no_users_yet') ?></h5>
                                    <p class="text-muted mb-3"><?= __('admin.no_users_description') ?></p>
                                    <a href="<?= base_url('admincontrol/userslist') ?>" class="btn btn-primary rounded-pill px-4">
                                        <i class="fas fa-user-plus me-1"></i>
                                        <?= __('admin.manage_users') ?>
                                    </a>
                                <?php else: ?>
                                    <h5 class="text-muted mb-2"><?= __('admin.no_affiliate_commissions_yet') ?></h5>
                                    <p class="text-muted mb-3"><?= __('admin.no_affiliate_commissions_description') ?></p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <span class="badge bg-info px-3 py-2">
                                            <i class="fas fa-users me-1"></i>
                                            <?= $total_users_count ?> Users Registered
                                        </span>
                                    </div>
                                    <div class="mt-3">
                                        <a href="<?= base_url('admincontrol/userslist') ?>" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-users me-1"></i>
                                            <?= __('admin.manage_users') ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="popular-affiliates-container">
                                <?php $this->load->view('admincontrol/dashboard/popular_affiliates_list', array('populer_users' => $populer_users, 'fun_c_format' => $fun_c_format)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container for notifications -->
<div class="toast-container" id="toastContainer"></div>

<?= $social_share_modal ?>

<?php
    // Last ID variables are now passed from controller - no fallback needed
    // $last_id_integration_logs, $last_id_newuser, $last_id_notifications
?>

<script type="text/javascript" src="<?= base_url("assets/plugins/table/datatables.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/plugins/table/dataTables.responsive.min.js") ?>"></script>
                <script type="text/javascript">
            // Use the improved toast system from footer
            // The showToast function is now defined in the footer and available globally

            // Fallback showToast function in case footer one isn't loaded
            function fallbackShowToast(title, message, type = 'success', duration = 4000) {
                // Create toast element
                var toast = document.createElement('div');
                toast.className = 'toast ' + type;
                
                // Add icon based on type
                var icon = '';
                switch(type) {
                    case 'success':
                        icon = '<i class="fas fa-check-circle me-2"></i>';
                        break;
                    case 'danger':
                        icon = '<i class="fas fa-times-circle me-2"></i>';
                        break;
                    case 'warning':
                        icon = '<i class="fas fa-exclamation-triangle me-2"></i>';
                        break;
                    case 'info':
                        icon = '<i class="fas fa-info-circle me-2"></i>';
                        break;
                    default:
                        icon = '<i class="fas fa-bell me-2"></i>';
                }
                
                toast.innerHTML = `
                    <div class="toast-header">
                        <h6 class="toast-title">${icon}${title}</h6>
                        <button type="button" class="toast-close" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <p class="toast-message">${message}</p>
                `;
                
                // Add to container
                var container = document.getElementById('toastContainer');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toastContainer';
                    container.className = 'toast-container';
                    document.body.appendChild(container);
                }
                
                container.appendChild(toast);
                
                // --- FORCE VISIBILITY FOR DEBUGGING ---
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
                toast.style.zIndex = '9999999';
                toast.style.position = 'fixed';
                toast.style.top = '40px';
                toast.style.right = '40px';
                toast.style.background = '#333';
                toast.style.color = '#fff';
                toast.style.display = 'block';
                
                // Auto remove
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 300);
                }, duration);
            }



    // Simplified dashboard - no live refresh needed
    // Basic online user count refresh every 30 seconds
    function refreshOnlineUsers() {
        $.ajax({
            global: false,
            url: '<?= base_url('admincontrol/ajax_dashboard') ?>',
            type: 'POST',
            dataType: 'json',
            data: {},
            success: function(json) {
                // Update online users only
                if(json['online_count']) {
                    if (json['online_count']['admin'] && json['online_count']['admin']['online']) {
                        $(".ajax-online-admin").html(json['online_count']['admin']['online']);
                    }
                    if (json['online_count']['user'] && json['online_count']['user']['online']) {
                        $(".ajax-online-affiliate").html(json['online_count']['user']['online']);
                    }
                    if (json['online_count']['vendor'] && json['online_count']['vendor']['online']) {
                        $(".ajax-online-vendor").html(json['online_count']['vendor']['online']);
                    }
                    if (json['online_count']['client'] && json['online_count']['client']['online']) {
                        $(".ajax-online-client").html(json['online_count']['client']['online']);
                    }
                }
            }
        });
    }

    // Session countdown functionality
    let sessionTimeout = <?= $timeout ?> * 1000; // Convert to milliseconds
    let sessionStart = <?= $this->session->userdata('timestamp') ?> * 1000; // Convert to milliseconds
    
    function resetSessionTimer() {
        sessionStart = Date.now();
        $('#session-countdown').removeClass('text-warning text-danger');
    }
    
    function updateSessionCountdown() {
        let currentTime = Date.now();
        let elapsedTime = currentTime - sessionStart;
        let remainingTime = sessionTimeout - elapsedTime;
        
        if (remainingTime <= 0) {
            $('#session-countdown').text('00:00:00');
            $('#session-countdown').addClass('text-danger');
            return;
        }
        
        let hours = Math.floor(remainingTime / 3600000);
        let minutes = Math.floor((remainingTime % 3600000) / 60000);
        let seconds = Math.floor((remainingTime % 60000) / 1000);
        
        let display;
        if (hours > 0) {
            display = String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        } else {
            display = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }
        
        $('#session-countdown').text(display);
        
        // Reset color classes first
        $('#session-countdown').removeClass('text-warning text-danger');
        
        // Change colors based on remaining time
        if (remainingTime < 60000) { // Less than 1 minute
            $('#session-countdown').addClass('text-danger');
        } else if (remainingTime < 300000) { // Less than 5 minutes
            $('#session-countdown').addClass('text-warning');
        }
    }
    
    // Reset session timer on user activity
    $(document).on('click keypress mousemove', function() {
        resetSessionTimer();
    });
    
    // Update session countdown every second
    updateSessionCountdown(); // Initial call
    setInterval(updateSessionCountdown, 1000);

    // Refresh online users every 30 seconds
    setInterval(refreshOnlineUsers, 30000);

    // Top Affiliates time filter dropdown
    $(document).on('click', '.dropdown-item[data-value]', function(e) {
        e.preventDefault();
        var value = $(this).data('value');
        var text = $(this).text();
        
        // Update dropdown button text
        $('#selectedTimeFilter').text(text);
        
        // Close dropdown
        $('#timeFilterDropdown').dropdown('hide');
        
        // Store current scroll position relative to the affiliates container
        var container = $('.popular-affiliates-container');
        var containerOffset = container.length && container.offset() ? container.offset().top : 0;
        var scrollPosition = $(window).scrollTop();
        var relativePosition = scrollPosition - containerOffset;
        
        // Add smooth fade effect
        if (container.length) {
            container.fadeTo(300, 0.3);
        }
        
        // Make AJAX request
        $.ajax({
            url: '<?= base_url('admincontrol/ajax_dashboard') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                'type': 'popular_affiliates_sorting',
                'value': value
            },
            success: function(json) {
                if(json['popular_affiliates']) {
                    // Update content
                    container.html(json['view']);
                    
                    // Restore scroll position relative to container
                    setTimeout(function() {
                        if (container.length && container.offset()) {
                            var newContainerOffset = container.offset().top;
                            var newScrollPosition = newContainerOffset + relativePosition;
                            
                            // Smooth scroll to preserved position
                            $('html, body').animate({
                                scrollTop: Math.max(0, newScrollPosition)
                            }, 200);
                        }
                        
                        // Fade back in
                        if (container.length) {
                            container.fadeTo(200, 1);
                        }
                        
                        // Add subtle entrance animation for cards (no delay conflicts)
                        $('.affiliate-card').css({
                            'opacity': '0',
                            'transform': 'translateY(10px)'
                        }).each(function(index) {
                            $(this).delay(index * 50).animate({
                                'opacity': '1'
                            }, 300).css('transform', 'translateY(0px)');
                        });
                    }, 50);
                }
            },
            error: function() {
                container.html('<div class="text-center py-5"><i class="fas fa-exclamation-triangle text-warning fs-1 mb-3"></i><p class="text-muted">Error loading data. Please try again.</p></div>');
                container.fadeTo(200, 1);
            }
        });
    });

    // Send message function
    function sendMessage(userId) {
        // Open ticket creation page to send message to user
        var ticketUrl = '<?= base_url('admincontrol/ticketcreate?user_id=') ?>' + userId;
        window.open(ticketUrl, '_blank');
    }

    // Legacy support for old select element
    $(document).on('change', '#popular_affiliates_sorting', function() {
        var value = $(this).val();
        var type = "popular_affiliates_sorting";
        
        // Store scroll position for legacy select too
        var container = $('.popular-affiliates-container');
        var containerOffset = container.length && container.offset() ? container.offset().top : 0;
        var scrollPosition = $(window).scrollTop();
        var relativePosition = scrollPosition - containerOffset;
        
        if (container.length) {
            container.fadeTo(300, 0.3);
        }
        
        $.ajax({
            url: '<?= base_url('admincontrol/ajax_dashboard') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                'type': type,
                'value': value
            },
            success: function(json) {
                if(json['popular_affiliates']) {
                    container.html(json['view']);
                    
                    // Restore scroll position and fade in
                    setTimeout(function() {
                        if (container.length && container.offset()) {
                            var newContainerOffset = container.offset().top;
                            var newScrollPosition = newContainerOffset + relativePosition;
                            
                            $('html, body').animate({
                                scrollTop: Math.max(0, newScrollPosition)
                            }, 200);
                        }
                        
                        if (container.length) {
                            container.fadeTo(200, 1);
                        }
                    }, 50);
                }
            }
        });
    });

    // Function to update protection badge based on security settings
    function updateProtectionBadge() {
        var securitySettings = [
            'block_click_across_browser',
            'enable_localhost_protection', 
            'enable_click_control',
            'enable_action_control'
        ];
        
        var enabledCount = 0;
        securitySettings.forEach(function(setting) {
            if ($('#' + setting).is(':checked')) {
                enabledCount++;
            }
        });
        
        var totalSettings = securitySettings.length;
        var securityPercentage = Math.round((enabledCount / totalSettings) * 100);
        
        var badge = $('#protection-badge');
        var icon = badge.find('i');
        
        if (securityPercentage == 0) {
            badge.removeClass('bg-success bg-warning bg-info bg-primary bg-danger').addClass('bg-danger');
            icon.removeClass('fas fa-check-circle fas fa-exclamation-triangle fas fa-shield-alt').addClass('fas fa-times-circle');
        } else if (securityPercentage <= 25) {
            badge.removeClass('bg-success bg-warning bg-info bg-primary bg-danger').addClass('bg-warning');
            icon.removeClass('fas fa-check-circle fas fa-exclamation-triangle fas fa-shield-alt').addClass('fas fa-exclamation-triangle');
        } else if (securityPercentage <= 50) {
            badge.removeClass('bg-success bg-warning bg-info bg-primary bg-danger').addClass('bg-info');
            icon.removeClass('fas fa-check-circle fas fa-exclamation-triangle fas fa-shield-alt').addClass('fas fa-shield-alt');
        } else if (securityPercentage <= 75) {
            badge.removeClass('bg-success bg-warning bg-info bg-primary bg-danger').addClass('bg-primary');
            icon.removeClass('fas fa-check-circle fas fa-exclamation-triangle fas fa-shield-alt').addClass('fas fa-shield-alt');
        } else {
            badge.removeClass('bg-success bg-warning bg-info bg-primary bg-danger').addClass('bg-success');
            icon.removeClass('fas fa-check-circle fas fa-exclamation-triangle fas fa-shield-alt').addClass('fas fa-check-circle');
        }
        
        // Update badge text with percentage
        badge.text(securityPercentage + '% <?= __('admin.secure') ?>');
        
        // Add icon back to the badge
        badge.prepend(icon);
        
        // Update data attribute
        badge.attr('data-protection-level', securityPercentage);
    }

    // Settings update
    $('.update_all_settings').on('change', function() {
        var checked = $(this).prop('checked') ? 1 : 0;
        var setting_key = $(this).data('setting_key');
        var setting_type = $(this).data('setting_type');
        
        // Update status label
        var label = $('.label_' + setting_key);
        label.text(checked ? '<?= __('admin.on') ?>' : '<?= __('admin.off') ?>');
        label.removeClass('bg-success bg-danger').addClass(checked ? 'bg-success' : 'bg-danger');
        
        // Update protection badge if this is a security setting
        if (['block_click_across_browser', 'enable_localhost_protection', 'enable_click_control', 'enable_action_control'].includes(setting_key)) {
            updateProtectionBadge();
        }
     
        $.ajax({
            url: '<?= base_url("admincontrol/update_all_settings") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                'action': 'update_all_settings',
                'status': checked,
                'setting_key': setting_key,
                'setting_type': setting_type
            },
            success: function(json) {
                if (json.success) {
                    if (typeof window.showToast === 'function') {
                        window.showToast('', '<?= __('admin.setting_updated_successfully') ?>', 'success', 3000);
                    }
                } else {
                    if (typeof window.showToast === 'function') {
                        window.showToast('<?= __('admin.error_updating_setting_title') ?>', '<?= __('admin.error_updating_setting') ?>', 'danger', 5000);
                    }
                }
            },
            error: function() {
                if (typeof window.showToast === 'function') {
                    window.showToast('<?= __('admin.error_updating_setting_title') ?>', '<?= __('admin.error_updating_setting') ?>', 'danger', 5000);
                }
            }
        });
    });

    // Toggle function for update frequency cards
    window.toggleUpdateFrequency = function(checkboxId) {
        const checkbox = document.getElementById(checkboxId);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            $(checkbox).trigger('change');
        }
    };

    // Add hover effects for update frequency cards
    $('.update-frequency-card').hover(
        function() {
            $(this).css('transform', 'translateY(-2px)');
            $(this).css('box-shadow', '0 4px 15px rgba(0,0,0,0.2)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
            $(this).css('box-shadow', 'none');
        }
    );

    // Module toggles - Use existing addon system
    $(document).on('change', '.addon-toggle', function(){
        let setting_type = $(this).data('setting_type');
        let setting_key = $(this).data('setting_key');
        let val = $(this).prop('checked') ? 1 : 0;
        let menu = $(this).data('sidebar');
        let $toggle = $(this);

        // Visual feedback
        if(val) {
            $('#sidebar_'+menu).show();
            $toggle.closest('.border').addClass('border-success');
        } else {
            $('#sidebar_'+menu).hide();
            $toggle.closest('.border').removeClass('border-success');
        }

        // Special handling for vendor deposit warnings
        if(setting_key === 'depositstatus') {
            // Function to show toast with retry mechanism
            function showVendorDepositToast(title, message, type) {
                if (typeof window.showToast === 'function') {
                    window.showToast(title, message, type, 6000);
                } else {
                    fallbackShowToast(title, message, type, 6000);
                }
            }
            
            if(val) {
                showVendorDepositToast('', '<?= __('admin.vendor_deposit') ?> <?= __('admin.module_enabled') ?>', 'info');
            } else {
                showVendorDepositToast('', '<?= __('admin.vendor_deposit') ?> <?= __('admin.module_disabled') ?>', 'warning');
            }
        }

        $.ajax({
            url: '<?= base_url("admincontrol/addons") ?>',
            type: "POST",
            data: {
                action: 'change_status', 
                setting_type: setting_type, 
                setting_key : setting_key, 
                val : val
            },
            success: function(res){
                if(res === 'success') {
                    // Don't show the general success toast for vendor deposit module since it has its own special toast
                    if (typeof window.showToast === 'function' && setting_key !== 'depositstatus') {
                        window.showToast('', '<?= __('admin.module_status_updated') ?>', 'success', 4000);
                    }
                } else {
                    // Revert on unexpected response
                    $toggle.prop('checked', !val);
                    $toggle.closest('.border').toggleClass('border-success');
                    if (typeof window.showToast === 'function') {
                        window.showToast('<?= __('admin.error_updating_module_title') ?>', '<?= __('admin.error_updating_module') ?>', 'danger', 5000);
                    }
                }
            },
            error: function() {
                // Revert on error
                $toggle.prop('checked', !val);
                $toggle.closest('.border').toggleClass('border-success');
                if (typeof window.showToast === 'function') {
                    window.showToast('<?= __('admin.error_updating_module_title') ?>', '<?= __('admin.error_updating_module') ?>', 'danger', 5000);
                }
            }
        });
    });

</script>

<!-- Invitation Links Modal -->
<div class="modal fade" id="invitationLinksModal" tabindex="-1" aria-labelledby="invitationLinksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-4">
                <h5 class="modal-title fw-bold" id="invitationLinksModalLabel">
                    <i class="fas fa-share-alt me-2"></i>
                    <?= __('admin.admin_invitation') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Affiliate Link Card -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 invitation-card">
                            <div class="card-header bg-primary text-white py-2 border-0">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-user-plus me-2"></i>
                                    <?= __('admin.register_new_affiliate_account_link') ?>
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-link text-primary"></i>
                                    </span>
                                    <?php $affiliate_share_url = base_url('register/' . base64_encode($userdetails['id'])); ?>
                                    <input id="unique_re_link_affiliate" type="text" class="form-control border-start-0" readonly value="<?= $affiliate_share_url ?>">
                                    <button type="button" copyToClipboard="<?= $affiliate_share_url ?>" class="btn btn-primary" title="<?= __('admin.copy_link') ?>">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <div class="d-grid">
                                    <button type="button" data-social-share data-share-url="<?= $affiliate_share_url ?>" class="btn btn-primary rounded-pill fw-bold">
                                        <i class="fas fa-share-alt me-2"></i>
                                        <?= __('admin.share_affiliate_link') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vendor Link Card -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 invitation-card">
                            <div class="card-header bg-success text-white py-2 border-0">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-store me-2"></i>
                                    <?= __('admin.register_new_vendor_account_link') ?>
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-link text-success"></i>
                                    </span>
                                    <?php $vendor_share_url = base_url('register/vendor'); ?>
                                    <input id="unique_re_link_vendor" type="text" class="form-control border-start-0" readonly value="<?= $vendor_share_url ?>">
                                    <button type="button" copyToClipboard="<?= $vendor_share_url ?>" class="btn btn-success" title="<?= __('admin.copy_link') ?>">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <div class="d-grid">
                                    <button type="button" data-social-share data-share-url="<?= $vendor_share_url ?>" class="btn btn-success rounded-pill fw-bold">
                                        <i class="fas fa-share-alt me-2"></i>
                                        <?= __('admin.share_vendor_link') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Info Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-info me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1"><?= __('admin.invitation_tips') ?></h6>
                                    <p class="mb-0 text-muted"><?= __('admin.invitation_tips_description') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    <?= __('admin.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Simple Security Status Script -->
<script>
    // Refresh security status every 3 minutes (very quiet background check)
    function refreshSecurityStatus() {
        // Simple silent refresh without notifications or sounds
        setTimeout(() => {
            window.location.reload();
        }, 180000); // 3 minutes
    }
    
    // Only refresh if user is inactive for security updates
    let lastActivity = Date.now();
    
    document.addEventListener('mousemove', () => lastActivity = Date.now());
    document.addEventListener('keypress', () => lastActivity = Date.now());
    
    // Check if user is inactive and refresh security status
    setInterval(() => {
        if (Date.now() - lastActivity > 300000) { // 5 minutes inactive
            refreshSecurityStatus();
        }
    }, 60000); // Check every minute

    $(document).ready(function(){
        const startTime = PerformanceMonitor.start('dashboard-performance');
        
        loadDashboardData();
        
        setTimeout(() => {
            PerformanceMonitor.end(startTime, 'dashboard-performance');
        }, 200);
    });

    function loadDashboardData() {
        $.ajax({
            url: '<?= base_url("admincontrol/api_dashboard_data") ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateDashboardStats(response.admin_totals);
                    updateOnlineCount(response.online_count);
                }
            },
            error: function() {
            }
        });
        // Same tick as first request so both overlap → one overlay session (not ajaxStop then ajaxStart again).
        loadHeavyDashboardData();
    }

    function loadHeavyDashboardData() {
        $.ajax({
            url: '<?= base_url("admincontrol/api_dashboard_heavy_data") ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateBackupStatus(response);
                    updateVersionStatus(response);
                }
            }
        });
    }

    function updateDashboardStats(admin_totals) {
        if (admin_totals && admin_totals.admin_balance !== undefined) {
            $('.admin-balance').text(admin_totals.admin_balance);
        }
    }

    function updateOnlineCount(count) {
        $('.online-count').text(count || 0);
    }

    function updateBackupStatus(data) {
        if (data.backup_warnings && data.backup_warnings.length > 0) {
            $('.backup-warnings').show();
        }
    }

    function updateVersionStatus(data) {
        if (data.show_update) {
            $('.version-update-notice').show();
        }
    }
</script>


<!-- Chart.js for Sparklines & Ring Charts -->
<script src="<?= base_url('assets/template/js/chart.umd.min.js') ?>?v=<?= av() ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if(isset($trends)): ?>
    // Sparkline Charts
    var trendData = <?= json_encode($trends) ?>;
    createSparkline('sparkRevenue', trendData.revenue, '#0d6efd', 'rgba(13,110,253,0.15)');
    createSparkline('sparkOrders', trendData.orders, '#0d6efd', 'rgba(13,110,253,0.15)');
    createSparkline('sparkCommissions', trendData.commissions, '#198754', 'rgba(25,135,84,0.15)');
    createSparkline('sparkClicks', trendData.clicks, '#8b5cf6', 'rgba(139,92,246,0.15)');
    createSparkline('sparkStoreOrders', trendData.store_orders, '#f97316', 'rgba(249,115,22,0.15)');

    // Ring Chart
    var extOrders = <?= (int)($total_external_orders ?? 0) ?>;
    var storeOrders = <?= (int)($total_store_orders ?? 0) ?>;
    var s2sOrders = <?= (int)($s2s_summary['total_s2s_orders'] ?? 0) ?>;
    createRingChart('adminRingChart',
        ['<?= addslashes(__('admin.external_orders')) ?>', '<?= addslashes(__('admin.store_orders')) ?>', '<?= addslashes(__('admin.s2s_conversions')) ?>'],
        [extOrders, storeOrders, s2sOrders],
        ['#3b82f6', '#22c55e', '#f97316']
    );
    <?php endif; ?>
});
</script>

<?= performance_indicator_html('dashboard-performance') ?>
<?= render_performance_indicator() ?>