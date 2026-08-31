<!-- Analytics Dashboard Wrapper -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- DASHBOARD HEADER -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card analytics-header-card text-white border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-1 fw-bold">
                                        <i class="bi bi-graph-up-arrow me-2"></i>
                                        <?= __('admin.business_analytics') ?>
                                    </h3>
                                    <p class="mb-0 opacity-90"><?= __('admin.real_time_performance_insights') ?></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="<?= base_url('admincontrol/dashboard') ?>" class="btn btn-light btn-sm">
                                        <i class="bi bi-arrow-left me-1"></i><?= __('admin.back_to_control_panel') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KEY METRICS ROW -->
            <div class="row g-3 mb-4">
                <!-- ADMIN BALANCE -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 analytics-metric-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="analytics-metric-icon-wrapper bg-primary bg-opacity-10 rounded-circle me-3">
                                    <i class="bi bi-wallet2 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1 analytics-metric-label"><?= __('admin.current_balance') ?></h6>
                                    <h4 class="fw-bold mb-1 analytics-metric-value"><?= $fun_c_format($admin_totals['admin_balance']) ?></h4>
                                    <div class="d-flex align-items-center">
                                        <small class="text-success me-2">
                                            <i class="bi bi-arrow-up me-1"></i><?= $admin_totals['admin_balance_growth']; ?>%
                                        </small>
                                        <div class="sparkline-mini" data-values="<?= $admin_totals['admin_balance'] * 0.8 ?>,<?= $admin_totals['admin_balance'] * 0.85 ?>,<?= $admin_totals['admin_balance'] * 0.9 ?>,<?= $admin_totals['admin_balance'] * 0.95 ?>,<?= $admin_totals['admin_balance'] ?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOTAL REVENUE -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 analytics-metric-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="analytics-metric-icon-wrapper bg-success bg-opacity-10 rounded-circle me-3">
                                    <i class="bi bi-graph-up text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1 analytics-metric-label"><?= __('admin.total_revenue') ?></h6>
                                    <h4 class="fw-bold mb-1 analytics-metric-value"><?= $fun_c_format($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) ?></h4>
                                    <div class="d-flex align-items-center">
                                        <small class="text-success me-2">
                                            <i class="bi bi-arrow-up me-1"></i><?= $admin_totals['admin_all_sales_growth']; ?>%
                                        </small>
                                        <div class="sparkline-mini" data-values="<?= ($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) * 0.7 ?>,<?= ($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) * 0.8 ?>,<?= ($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) * 0.85 ?>,<?= ($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) * 0.9 ?>,<?= $admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total'] ?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COMMISSION INCOME -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 analytics-metric-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="analytics-metric-icon-wrapper bg-warning bg-opacity-10 rounded-circle me-3">
                                    <i class="bi bi-cash-stack text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1 analytics-metric-label"><?= __('admin.commission_income') ?></h6>
                                    <h4 class="fw-bold mb-1 analytics-metric-value"><?= $fun_c_format($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission']) ?></h4>
                                    <div class="d-flex align-items-center">
                                        <small class="text-success me-2">
                                            <i class="bi bi-arrow-up me-1"></i><?= $admin_totals['all_clicks_comission_growth']; ?>%
                                        </small>
                                        <div class="sparkline-mini" data-values="<?= ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission']) * 0.75 ?>,<?= ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission']) * 0.8 ?>,<?= ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission']) * 0.85 ?>,<?= ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission']) * 0.9 ?>,<?= $admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission'] ?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NET PROFIT -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 analytics-metric-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="analytics-metric-icon-wrapper bg-info bg-opacity-10 rounded-circle me-3">
                                    <i class="bi bi-pie-chart text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1 analytics-metric-label"><?= __('admin.net_profit') ?></h6>
                                    <h4 class="fw-bold mb-1 analytics-metric-value"><?= $fun_c_format(($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) - ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission'])) ?></h4>
                                    <div class="d-flex align-items-center">
                                        <small class="text-success me-2">
                                            <i class="bi bi-check-circle me-1"></i><?= __('admin.after_expenses') ?>
                                        </small>
                                        <div class="sparkline-mini" data-values="<?= (($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) - ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission'])) * 0.8 ?>,<?= (($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) - ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission'])) * 0.85 ?>,<?= (($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) - ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission'])) * 0.9 ?>,<?= (($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) - ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission'])) * 0.95 ?>,<?= ($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total'] + $admin_totals['sale_localstore_vendor_total']) - ($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission'] + $admin_totals['click_action_commission']) ?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- REVENUE TREND CHART -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm analytics-chart-card">
                        <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-graph-up me-1"></i><?= __('admin.revenue_performance_trends') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <canvas id="revenueTrendChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SALES CHANNELS -->
            <div class="row g-3 mb-4"> 
                <!-- STORE SALES -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100 analytics-channel-card">
                        <div class="card-header bg-success text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-shop me-1"></i><?= __('admin.direct_store') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="text-center mb-3">
                                <h3 class="fw-bold text-success mb-1"><?= $fun_c_format($admin_totals['sale_localstore_total']) ?></h3>
                                <small class="text-muted"><?= (int)$admin_totals['sale_localstore_count'] ?> <?= __('admin.orders') ?></small>
                            </div>
                            <div class="row text-center">
                                <div class="col-6">
                                    <small class="text-muted d-block"><?= __('admin.commission') ?></small>
                                    <span class="fw-bold text-success"><?= $fun_c_format($admin_totals['sale_localstore_commission']) ?></span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block"><?= __('admin.growth') ?></small>
                                    <span class="fw-bold text-success"><?= $admin_totals['admin_all_sales_growth']; ?>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VENDOR SALES -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100 analytics-channel-card">
                        <div class="card-header bg-warning text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-person-badge me-1"></i><?= __('admin.vendor_channel') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="text-center mb-3">
                                <h3 class="fw-bold text-warning mb-1"><?= $fun_c_format($admin_totals['sale_localstore_vendor_total']) ?></h3>
                                <small class="text-muted"><?= (int)$admin_totals['sale_localstore_vendor_count'] ?> <?= __('admin.orders') ?></small>
                            </div>
                            <div class="row text-center">
                                <div class="col-6">
                                    <small class="text-muted d-block"><?= __('admin.commission') ?></small>
                                    <span class="fw-bold text-warning"><?= $fun_c_format($admin_totals['sale_localstore_vendor_commission']) ?></span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block"><?= __('admin.growth') ?></small>
                                    <span class="fw-bold text-warning"><?= $admin_totals['vendor_all_sales_growth']; ?>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EXTERNAL SALES -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100 analytics-channel-card">
                        <div class="card-header bg-info text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-globe me-1"></i><?= __('admin.external_partners') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="text-center mb-3">
                                <h3 class="fw-bold text-info mb-1"><?= $fun_c_format($admin_totals['order_external_total']) ?></h3>
                                <small class="text-muted"><?= (int)$admin_totals['order_external_count'] ?> <?= __('admin.orders') ?></small>
                            </div>
                            <div class="row text-center">
                                <div class="col-6">
                                    <small class="text-muted d-block"><?= __('admin.commission') ?></small>
                                    <span class="fw-bold text-info"><?= $fun_c_format($admin_totals['order_external_commission']) ?></span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block"><?= __('admin.growth') ?></small>
                                    <span class="fw-bold text-info"><?= $admin_totals['admin_all_sales_growth']; ?>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SALES BREAKDOWN CHART -->
            <div class="row g-3 mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm analytics-chart-card">
                        <div class="card-header bg-success text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-pie-chart me-1"></i><?= __('admin.sales_channel_distribution') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <canvas id="salesBreakdownChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- COMMISSION BREAKDOWN -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm analytics-chart-card">
                        <div class="card-header bg-warning text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-cash-stack me-1"></i><?= __('admin.commission_breakdown') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <?php 
                            // Get ALL commission types from the system
                            $click_localstore_commission = isset($admin_totals['click_localstore_commission']) ? $admin_totals['click_localstore_commission'] : 0;
                            $click_integration_commission = isset($admin_totals['click_integration_commission']) ? $admin_totals['click_integration_commission'] : 0;
                            $click_form_commission = isset($admin_totals['click_form_commission']) ? $admin_totals['click_form_commission'] : 0;
                            $click_action_commission = isset($admin_totals['click_action_commission']) ? $admin_totals['click_action_commission'] : 0;
                            $sale_localstore_commission = isset($admin_totals['sale_localstore_commission']) ? $admin_totals['sale_localstore_commission'] : 0;
                            $sale_localstore_vendor_commission = isset($admin_totals['sale_localstore_vendor_commission']) ? $admin_totals['sale_localstore_vendor_commission'] : 0;
                            $order_external_commission = isset($admin_totals['order_external_commission']) ? $admin_totals['order_external_commission'] : 0;
                            
                            $total_commission = $click_localstore_commission + $click_integration_commission + $click_form_commission + $click_action_commission + $sale_localstore_commission + $sale_localstore_vendor_commission + $order_external_commission;
                            
                            // Debug output
                            echo "<!-- Debug Commission Data: ";
                            echo "Click LocalStore: " . $click_localstore_commission . ", ";
                            echo "Click Integration: " . $click_integration_commission . ", ";
                            echo "Click Form: " . $click_form_commission . ", ";
                            echo "Click Action: " . $click_action_commission . ", ";
                            echo "Sale LocalStore: " . $sale_localstore_commission . ", ";
                            echo "Sale Vendor: " . $sale_localstore_vendor_commission . ", ";
                            echo "Order External: " . $order_external_commission . ", ";
                            echo "Total: " . $total_commission;
                            echo " -->";
                            ?>
                            <?php if ($total_commission > 0): ?>
                                <canvas id="commissionChart" height="100"></canvas>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-pie-chart text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2 mb-0"><?= __('admin.no_commission_data_available') ?></p>
                                    <small class="text-muted"><?= __('admin.commission_will_appear_here') ?></small>
                                    <br><small class="text-muted">Debug: Total = <?= $total_commission ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TRAFFIC & PAYMENTS -->
            <div class="row g-3 mb-4">
                <!-- TRAFFIC ANALYSIS -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100 analytics-traffic-card">
                        <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-cursor me-1"></i><?= __('admin.traffic_source_analysis') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <h6 class="text-muted mb-1"><?= __('admin.store_clicks') ?></h6>
                                    <h5 class="fw-bold text-primary mb-1"><?= (int)$admin_totals['click_localstore_total'] ?></h5>
                                    <small class="text-muted"><?= $fun_c_format($admin_totals['click_localstore_commission']) ?></small>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted mb-1"><?= __('admin.integration_clicks') ?></h6>
                                    <h5 class="fw-bold text-success mb-1"><?= (int)$admin_totals['click_integration_total'] ?></h5>
                                    <small class="text-muted"><?= $fun_c_format($admin_totals['click_integration_commission']) ?></small>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted mb-1"><?= __('admin.form_clicks') ?></h6>
                                    <h5 class="fw-bold text-warning mb-1"><?= (int)$admin_totals['click_form_total'] ?></h5>
                                    <small class="text-muted"><?= $fun_c_format($admin_totals['click_form_commission']) ?></small>
                                </div>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <h6 class="text-muted mb-1"><?= __('admin.total_traffic') ?></h6>
                                    <h4 class="fw-bold text-primary"><?= (int)($admin_totals['click_localstore_total'] + $admin_totals['click_integration_total'] + $admin_totals['click_form_total']) ?></h4>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted mb-1"><?= __('admin.total_revenue') ?></h6>
                                    <h4 class="fw-bold text-success"><?= $fun_c_format($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission']) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PAYMENT STATUS -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100 analytics-payment-card">
                        <div class="card-header bg-warning text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-wallet me-1"></i><?= __('admin.payment_pipeline') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="analytics-payment-item mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><?= __('admin.pending') ?></span>
                                    <span class="fw-bold text-warning"><?= $fun_c_format($admin_totals['wallet_unpaid_amount']) ?></span>
                                </div>
                                <small class="text-muted"><?= (int)$admin_totals['wallet_unpaid_count'] ?> users</small>
                            </div>
                            
                            <div class="analytics-payment-item mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><?= __('admin.completed') ?></span>
                                    <span class="fw-bold text-success"><?= $fun_c_format($admin_totals['wallet_accept_amount']) ?></span>
                                </div>
                                <small class="text-muted"><?= (int)$admin_totals['wallet_accept_count'] ?> users</small>
                            </div>
                            
                            <div class="analytics-payment-item mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><?= __('admin.vendor_pending') ?></span>
                                    <span class="fw-bold text-info"><?= $fun_c_format($admin_totals['vendor_wallet_unpaid_amount']) ?></span>
                                </div>
                                <small class="text-muted"><?= (int)$admin_totals['vendor_wallet_unpaid_count'] ?> vendors</small>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="text-center">
                                <h6 class="text-muted mb-1"><?= __('admin.total_pipeline') ?></h6>
                                <h5 class="fw-bold text-primary"><?= $fun_c_format($admin_totals['wallet_unpaid_amount'] + $admin_totals['vendor_wallet_unpaid_amount']) ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- BUSINESS METRICS -->
            <div class="row g-3 mb-4">
                <!-- VENDOR DEPOSITS -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 analytics-business-card">
                        <div class="card-header bg-warning text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-people me-1"></i><?= __('admin.vendor_investment') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <h6 class="text-muted mb-1"><?= __('admin.pending') ?></h6>
                                    <h5 class="fw-bold text-warning"><?= (int)$admin_totals['vendor_deposit_pending_count'] ?></h5>
                                    <small class="text-muted"><?= $fun_c_format($admin_totals['vendor_deposit_pending']) ?></small>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted mb-1"><?= __('admin.approved') ?></h6>
                                    <h5 class="fw-bold text-success"><?= (int)$admin_totals['vendor_deposit_approved_count'] ?></h5>
                                    <small class="text-muted"><?= $fun_c_format($admin_totals['vendor_deposit_approved']) ?></small>
                                </div>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="text-center">
                                <h6 class="text-muted mb-1"><?= __('admin.total_investment') ?></h6>
                                <h4 class="fw-bold text-warning"><?= $fun_c_format($admin_totals['vendor_deposit_pending'] + $admin_totals['vendor_deposit_approved']) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MEMBERSHIPS -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 analytics-business-card">
                        <div class="card-header bg-success text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-person-check me-1"></i><?= __('admin.membership_revenue') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <h6 class="text-muted mb-1"><?= __('admin.active') ?></h6>
                                    <h5 class="fw-bold text-success"><?= (int)$admin_totals['membership_count'] ?></h5>
                                    <small class="text-muted"><?= $fun_c_format($admin_totals['membership_total']) ?></small>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted mb-1"><?= __('admin.pending') ?></h6>
                                    <h5 class="fw-bold text-warning"><?= (int)$admin_totals['membership_pending_count'] ?></h5>
                                    <small class="text-muted"><?= $fun_c_format($admin_totals['membership_pending']) ?></small>
                                </div>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="text-center">
                                <h6 class="text-muted mb-1"><?= __('admin.total_revenue') ?></h6>
                                <h4 class="fw-bold text-success"><?= $fun_c_format($admin_totals['membership_total'] + $admin_totals['membership_pending']) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SYSTEM STATUS & ACTIONS -->
            <div class="row g-3 mb-4">
                <!-- SYSTEM STATUS -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100 analytics-system-card">
                        <div class="card-header bg-info text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-activity me-1"></i><?= __('admin.system_health') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row text-center">
                                <div class="col-3">
                                    <h6 class="text-muted mb-1"><?= __('admin.main_store') ?></h6>
                                    <span class="badge <?= $enable_disable['store_is_enable'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $enable_disable['store_is_enable'] ? __('admin.online') : __('admin.offline') ?>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-muted mb-1"><?= __('admin.vendor_store') ?></h6>
                                    <span class="badge <?= (int)$vendor_store_data['storestatus'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= (int)$vendor_store_data['storestatus'] ? __('admin.active') : __('admin.inactive') ?>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-muted mb-1"><?= __('admin.system_issues') ?></h6>
                                    <span class="badge <?= is_countable($serverReq) && count($serverReq) > 0 ? 'bg-danger' : 'bg-success' ?>">
                                        <?= is_countable($serverReq) && count($serverReq) > 0 ? count($serverReq) : __('admin.healthy') ?>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-muted mb-1"><?= __('admin.notifications') ?></h6>
                                    <span class="badge bg-info"><?= $notifications_count ?></span>
                                </div>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <h6 class="text-muted mb-1"><?= __('admin.total_users') ?></h6>
                                    <h5 class="fw-bold text-primary"><?= (int)$admin_totals['total_users'] ?></h5>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted mb-1"><?= __('admin.products') ?></h6>
                                    <h5 class="fw-bold text-warning"><?= (int)$admin_totals['total_products'] ?></h5>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted mb-1"><?= __('admin.total_orders') ?></h6>
                                    <h5 class="fw-bold text-success"><?= (int)($admin_totals['sale_localstore_count'] + $admin_totals['sale_localstore_vendor_count'] + $admin_totals['order_external_count']) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUICK ACTIONS -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100 analytics-actions-card">
                        <div class="card-header bg-dark text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-lightning me-1"></i><?= __('admin.quick_actions') ?></h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <a href="<?= base_url('admincontrol/all_transaction') ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-graph-down me-1"></i><?= __('admin.view_reports') ?>
                                </a>
                                <a href="<?= base_url('admincontrol/userslist') ?>" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-people-fill me-1"></i><?= __('admin.manage_users') ?>
                                </a>
                                <a href="<?= base_url('admincontrol/payment_gateway') ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-credit-card-2-front me-1"></i><?= __('admin.payment_analytics') ?>
                                </a>
                                <a href="<?= base_url('admincontrol/system_status') ?>" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-speedometer2 me-1"></i><?= __('admin.system_monitor') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- End col-12 -->
    </div> <!-- End row -->
</div> <!-- End container-fluid -->

<!-- Chart.js Library -->
<script src="<?= base_url('assets/template/js/chart.umd.min.js') ?>?v=<?= av() ?>"></script>

<script>


// Sparkline Chart Function
function createSparkline(container, values) {
    const canvas = document.createElement('canvas');
    canvas.width = 60;
    canvas.height = 20;
    container.appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    const data = values.split(',').map(v => parseFloat(v));
    const max = Math.max(...data);
    const min = Math.min(...data);
    const range = max - min;
    
    ctx.strokeStyle = data[data.length - 1] > data[0] ? '#10b981' : '#ef4444';
    ctx.lineWidth = 2;
    ctx.beginPath();
    
    data.forEach((value, index) => {
        const x = (index / (data.length - 1)) * canvas.width;
        const y = canvas.height - ((value - min) / range) * canvas.height;
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.stroke();
}

// Initialize page elements when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Create sparklines
    const sparklines = document.querySelectorAll('.sparkline-mini');
    sparklines.forEach(sparkline => {
        const values = sparkline.getAttribute('data-values');
        createSparkline(sparkline, values);
    });
});

// Revenue Trend Chart
const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: '<?= __('admin.local_store_sales') ?>',
            data: [<?= $admin_totals['sale_localstore_total'] * 0.8 ?>, <?= $admin_totals['sale_localstore_total'] * 0.85 ?>, <?= $admin_totals['sale_localstore_total'] * 0.9 ?>, <?= $admin_totals['sale_localstore_total'] * 0.95 ?>, <?= $admin_totals['sale_localstore_total'] * 0.98 ?>, <?= $admin_totals['sale_localstore_total'] ?>],
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: '<?= __('admin.vendor_sales') ?>',
            data: [<?= $admin_totals['sale_localstore_vendor_total'] * 0.7 ?>, <?= $admin_totals['sale_localstore_vendor_total'] * 0.8 ?>, <?= $admin_totals['sale_localstore_vendor_total'] * 0.85 ?>, <?= $admin_totals['sale_localstore_vendor_total'] * 0.9 ?>, <?= $admin_totals['sale_localstore_vendor_total'] * 0.95 ?>, <?= $admin_totals['sale_localstore_vendor_total'] ?>],
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: '<?= __('admin.external_orders') ?>',
            data: [<?= $admin_totals['order_external_total'] * 0.75 ?>, <?= $admin_totals['order_external_total'] * 0.8 ?>, <?= $admin_totals['order_external_total'] * 0.85 ?>, <?= $admin_totals['order_external_total'] * 0.9 ?>, <?= $admin_totals['order_external_total'] * 0.95 ?>, <?= $admin_totals['order_external_total'] ?>],
            borderColor: '#06b6d4',
            backgroundColor: 'rgba(6, 182, 212, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});

// Sales Breakdown Chart
const salesCtx = document.getElementById('salesBreakdownChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'doughnut',
    data: {
        labels: ['<?= __('admin.direct_store') ?>', '<?= __('admin.vendor_channel') ?>', '<?= __('admin.external_partners') ?>'],
        datasets: [{
            data: [<?= $admin_totals['sale_localstore_total'] ?>, <?= $admin_totals['sale_localstore_vendor_total'] ?>, <?= $admin_totals['order_external_total'] ?>],
            backgroundColor: ['#10b981', '#f59e0b', '#06b6d4'],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Commission Chart
const commissionChartElement = document.getElementById('commissionChart');
if (commissionChartElement) {
    const commissionCtx = commissionChartElement.getContext('2d');
    const commissionChart = new Chart(commissionCtx, {
    type: 'pie',
    data: {
                    labels: ['<?= __('admin.store_clicks') ?>', '<?= __('admin.integration_clicks') ?>', '<?= __('admin.form_clicks') ?>', '<?= __('admin.action_clicks') ?>', '<?= __('admin.local_store_sales') ?>', '<?= __('admin.vendor_sales') ?>', '<?= __('admin.external_orders') ?>'],
        datasets: [{
            data: [
                <?= $click_localstore_commission ?>,
                <?= $click_integration_commission ?>,
                <?= $click_form_commission ?>,
                <?= $click_action_commission ?>,
                <?= $sale_localstore_commission ?>,
                <?= $sale_localstore_vendor_commission ?>,
                <?= $order_external_commission ?>
            ],
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316'],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 10,
                    usePointStyle: true
                }
            }
        }
    }
    });
}
</script>

<style>
/* Modern Analytics Dashboard Styling */

.analytics-header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    border: none !important;
}

/* Metric Cards */
.analytics-metric-card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.analytics-metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.analytics-metric-icon-wrapper {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.analytics-metric-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
}

.analytics-metric-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

/* Sparkline Mini Charts */
.sparkline-mini {
    width: 60px;
    height: 20px;
    display: inline-block;
    position: relative;
}

.sparkline-mini canvas {
    border-radius: 2px;
}

/* Chart Cards */
.analytics-chart-card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.analytics-chart-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
}

.analytics-chart-card .card-header {
    border-radius: 12px 12px 0 0;
    border: none;
    font-weight: 600;
}

/* Channel Cards */
.analytics-channel-card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.analytics-channel-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
}

.analytics-channel-card .card-header {
    border-radius: 12px 12px 0 0;
    border: none;
    font-weight: 600;
}

/* Traffic & Payment Cards */
.analytics-traffic-card,
.analytics-payment-card,
.analytics-business-card,
.analytics-system-card,
.analytics-actions-card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.analytics-traffic-card:hover,
.analytics-payment-card:hover,
.analytics-business-card:hover,
.analytics-system-card:hover,
.analytics-actions-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
}

.analytics-traffic-card .card-header,
.analytics-payment-card .card-header,
.analytics-business-card .card-header,
.analytics-system-card .card-header,
.analytics-actions-card .card-header {
    border-radius: 12px 12px 0 0;
    border: none;
    font-weight: 600;
}

/* Payment Items */
.analytics-payment-item {
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
}

.analytics-payment-item:last-child {
    border-bottom: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .analytics-metric-value {
        font-size: 1.1rem;
    }
    
    .analytics-metric-icon-wrapper {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .card-body {
        padding: 1rem !important;
    }
}

/* Legacy Support */
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}
</style>

<script>
$(document).ready(function(){
    const startTime = PerformanceMonitor.start('analytics-dashboard-performance');
    
    setTimeout(() => {
        PerformanceMonitor.end(startTime, 'analytics-dashboard-performance');
    }, 400);
});
</script>

<?= performance_indicator_html('analytics-dashboard-performance') ?>
<?= render_performance_indicator() ?> 