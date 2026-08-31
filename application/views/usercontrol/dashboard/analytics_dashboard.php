<?php
// Get store settings for vendor (following header.php pattern)
$store_setting = null;
if (isset($userdetails['is_vendor']) && $userdetails['is_vendor'] == 1) {
    $db =& get_instance();
    $store_setting = $db->Product_model->getSettings('store');
}
?>

<!-- Analytics Dashboard Wrapper -->
<div class="container-fluid">
<!-- DASHBOARD HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-1 fw-bold">
                            <i class="bi bi-graph-up-arrow me-2"></i>
                            <?= __('user.analytics_dashboard') ?>
                        </h3>
                        <p class="mb-0 opacity-90"><?= __('user.your_complete_earnings_overview') ?></p>
                        <small class="opacity-75 mt-1 d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            <?php if ($dashboard_mode === 'affiliate'): ?>
                                <?php if (isset($userdetails['is_vendor']) && $userdetails['is_vendor'] == 1): ?>
                                <?= __('user.vendor_viewing_affiliate_description') ?>
                                <?php else: ?>
                                <?= __('user.affiliate_only_description') ?>
                                <?php endif; ?>
                            <?php else: ?>
                            <?= __('user.vendor_mode_description') ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            
                            <?php if (isset($userdetails['is_vendor']) && $userdetails['is_vendor'] == 1): ?>
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('usercontrol/analytics_dashboard?view=affiliate') ?>" class="btn btn-sm<?= ($dashboard_mode === 'affiliate') ? ' btn-light' : ' btn-outline-light' ?>">
                                    <i class="bi bi-graph-up me-1"></i><?= __('user.affiliate_dashboard') ?>
                                </a>
                                <a href="<?= base_url('usercontrol/analytics_dashboard?view=vendor') ?>" class="btn btn-sm<?= ($dashboard_mode === 'vendor') ? ' btn-light' : ' btn-outline-light' ?>">
                                    <i class="bi bi-shop me-1"></i><?= __('user.vendor_dashboard') ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================= -->
<!-- COMMON SECTIONS (ALL USERS) -->
<!-- ========================================= -->

<!-- VENDOR DEPOSIT NOTIFICATION BANNER -->
<?php if ($dashboard_mode === 'vendor' && isset($vendor_data['show_deposit_warning']) && $vendor_data['show_deposit_warning']): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-warning border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <div class="me-3 fs-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1 fw-bold"><?= __('user.deposit_notification') ?></h6>
                    <p class="mb-0"><?= $vendor_data['deposit_warning_message'] ?></p>
                </div>
                <div class="ms-3">
                    <a href="<?= base_url('usercontrol/my_deposits') ?>" class="btn btn-sm btn-warning me-2">
                        <i class="bi bi-eye me-1"></i><?= __('user.view_deposits') ?>
                    </a>
                    <button type="button" class="btn btn-sm btn-warning" onclick="dismissDepositAlert()">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- KEY METRICS ROW -->
<div class="row g-3 mb-4">
    
    <?php if ($dashboard_mode === 'affiliate'): ?>
    <!-- AFFILIATE METRICS -->
    
    <!-- USER BALANCE -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-wallet2 text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.your_current_balance') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format(isset($user_totals['wallet_balance']) ? $user_totals['wallet_balance'] : 0); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-success me-2">
                                <i class="bi bi-check-circle me-1"></i><?= __('user.available_to_withdraw') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= (isset($user_totals['wallet_balance']) ? $user_totals['wallet_balance'] : 0) * 0.8 ?>,<?= (isset($user_totals['wallet_balance']) ? $user_totals['wallet_balance'] : 0) * 0.85 ?>,<?= (isset($user_totals['wallet_balance']) ? $user_totals['wallet_balance'] : 0) * 0.9 ?>,<?= (isset($user_totals['wallet_balance']) ? $user_totals['wallet_balance'] : 0) * 0.95 ?>,<?= isset($user_totals['wallet_balance']) ? $user_totals['wallet_balance'] : 0 ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOTAL EARNINGS -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cash-stack text-success fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.total_earnings') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format(isset($user_totals['total_earnings']) ? $user_totals['total_earnings'] : 0); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-success me-2">
                                <i class="bi bi-trophy me-1"></i><?= __('user.all_time_earnings') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= (isset($user_totals['total_earnings']) ? $user_totals['total_earnings'] : 0) * 0.7 ?>,<?= (isset($user_totals['total_earnings']) ? $user_totals['total_earnings'] : 0) * 0.8 ?>,<?= (isset($user_totals['total_earnings']) ? $user_totals['total_earnings'] : 0) * 0.85 ?>,<?= (isset($user_totals['total_earnings']) ? $user_totals['total_earnings'] : 0) * 0.9 ?>,<?= isset($user_totals['total_earnings']) ? $user_totals['total_earnings'] : 0 ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    <!-- VENDOR METRICS -->
    
    <!-- VENDOR DEPOSIT BALANCE -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-bank text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.vendor_deposit_balance') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format(isset($vendor_data['total_deposited']) ? $vendor_data['total_deposited'] : 0); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-success me-2">
                                <i class="bi bi-check-circle me-1"></i><?= __('user.approved_deposits') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= (isset($vendor_data['total_deposited']) ? $vendor_data['total_deposited'] : 0) * 0.8 ?>,<?= (isset($vendor_data['total_deposited']) ? $vendor_data['total_deposited'] : 0) * 0.85 ?>,<?= (isset($vendor_data['total_deposited']) ? $vendor_data['total_deposited'] : 0) * 0.9 ?>,<?= (isset($vendor_data['total_deposited']) ? $vendor_data['total_deposited'] : 0) * 0.95 ?>,<?= isset($vendor_data['total_deposited']) ? $vendor_data['total_deposited'] : 0 ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOTAL SALES REVENUE -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cart text-success fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.total_sales_revenue') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-success me-2">
                                <i class="bi bi-graph-up me-1"></i><?= __('user.business_revenue') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.7 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.8 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.85 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.9 ?>,<?= (isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0) ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php endif; ?>

    <?php if ($dashboard_mode === 'affiliate'): ?>
    <!-- CLICK EARNINGS (AFFILIATE MODE) -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-mouse text-warning fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.click_earnings') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format(isset($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-success me-2">
                                <i class="bi bi-arrow-up me-1"></i><?= (int)(isset($user_totals['total_clicks_count']) ? $user_totals['total_clicks_count'] : 0) ?> <?= __('user.clicks') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= (isset($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.75 ?>,<?= (isset($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.8 ?>,<?= (isset($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.85 ?>,<?= (isset($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.9 ?>,<?= isset($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0 ?>"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Expandable Click Details -->
                <div id="clickEarningsDetails" style="display: none;">
                    <div class="mt-3 p-3 bg-light border rounded" style="cursor: pointer;" onclick="toggleClickEarningsDetails()">
                        <div class="mb-2">
                            <small class="text-muted fw-bold"><?= __('user.store_clicks') ?>: <span class="text-primary"><?= $fun_c_format(isset($user_totals['click_localstore_commission']) ? $user_totals['click_localstore_commission'] : 0) ?></span></small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted fw-bold"><?= __('user.integration_clicks') ?>: <span class="text-success"><?= $fun_c_format(isset($user_totals['click_integration_commission']) ? $user_totals['click_integration_commission'] : 0) ?></span></small>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted fw-bold"><?= __('user.form_clicks') ?>: <span class="text-warning"><?= $fun_c_format(isset($user_totals['click_form_commission']) ? $user_totals['click_form_commission'] : 0) ?></span></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Clickable Bottom Area -->
            <div class="position-absolute bottom-0 start-0 w-100" style="height: 35px; cursor: pointer; background: linear-gradient(to top, rgba(255,193,7,0.1), transparent);" onclick="toggleClickEarningsDetails()">
                <!-- Toggle Icon in Bottom Right Corner -->
                <div class="position-absolute bottom-0 end-0 p-2">
                    <i class="bi bi-plus-circle text-warning fs-5" id="clickEarningsToggle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTION EARNINGS (AFFILIATE MODE) -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-target text-info fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.action_earnings') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format(isset($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-success me-2">
                                <i class="bi bi-arrow-up me-1"></i><?= (int)(isset($user_totals['click_action_total']) ? $user_totals['click_action_total'] : 0) ?> <?= __('user.actions') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= (isset($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.8 ?>,<?= (isset($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.85 ?>,<?= (isset($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.9 ?>,<?= (isset($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.95 ?>,<?= isset($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0 ?>"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Expandable Action Details -->
                <div id="actionEarningsDetails" style="display: none;">
                    <div class="mt-3 p-3 bg-light border rounded" style="cursor: pointer;" onclick="toggleActionEarningsDetails()">
                        <div class="mb-2">
                            <small class="text-muted fw-bold"><?= __('user.total_actions_done') ?>: <span class="text-info"><?= (int)(isset($user_totals['click_action_total']) ? $user_totals['click_action_total'] : 0) ?></span></small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted fw-bold"><?= __('user.per_action') ?>: <span class="text-success"><?= (isset($user_totals['click_action_total']) && $user_totals['click_action_total'] > 0) ? $fun_c_format($user_totals['click_action_commission'] / $user_totals['click_action_total']) : $fun_c_format(0) ?></span></small>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted fw-bold"><?= __('user.success_rate') ?>: <span class="text-warning"><?= (isset($user_totals['total_clicks_count']) && $user_totals['total_clicks_count'] > 0) ? number_format(($user_totals['click_action_total'] / $user_totals['total_clicks_count']) * 100, 1) : 0 ?>%</span></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Clickable Bottom Area -->
            <div class="position-absolute bottom-0 start-0 w-100" style="height: 35px; cursor: pointer; background: linear-gradient(to top, rgba(13,202,240,0.1), transparent);" onclick="toggleActionEarningsDetails()">
                <!-- Toggle Icon in Bottom Right Corner -->
                <div class="position-absolute bottom-0 end-0 p-2">
                    <i class="bi bi-plus-circle text-info fs-5" id="actionEarningsToggle"></i>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- VENDOR SALES REVENUE (VENDOR MODE) -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cart text-success fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.sales_revenue') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-success me-2">
                                <i class="bi bi-graph-up me-1"></i><?= __('user.total_sales') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.75 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.8 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.85 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.9 ?>,<?= (isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0) ?>"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Expandable Sales Details -->
                <div id="vendorSalesDetails" style="display: none;">
                    <div class="mt-3 p-3 bg-light border rounded" style="cursor: pointer;" onclick="toggleVendorSalesDetails()">
                        <div class="mb-2">
                            <small class="text-muted fw-bold"><?= __('user.local_store') ?>: <span class="text-success"><?= $fun_c_format(isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) ?></span></small>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted fw-bold"><?= __('user.external_sales') ?>: <span class="text-info"><?= $fun_c_format(isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0) ?></span></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Clickable Bottom Area -->
            <div class="position-absolute bottom-0 start-0 w-100" style="height: 35px; cursor: pointer; background: linear-gradient(to top, rgba(25,135,84,0.1), transparent);" onclick="toggleVendorSalesDetails()">
                <!-- Toggle Icon in Bottom Right Corner -->
                <div class="position-absolute bottom-0 end-0 p-2">
                    <i class="bi bi-plus-circle text-success fs-5" id="vendorSalesToggle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- SHIPPING REIMBURSEMENT (VENDOR MODE) -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-truck text-warning fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.shipping_reimbursement') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format(isset($user_totals['vendor_shipping_reimbursement_total']) ? $user_totals['vendor_shipping_reimbursement_total'] : 0); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-warning me-2">
                                <i class="bi bi-arrow-up me-1"></i><?= __('user.total_reimbursement') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= (isset($user_totals['vendor_shipping_reimbursement_total']) ? $user_totals['vendor_shipping_reimbursement_total'] : 0) * 0.8 ?>,<?= (isset($user_totals['vendor_shipping_reimbursement_total']) ? $user_totals['vendor_shipping_reimbursement_total'] : 0) * 0.85 ?>,<?= (isset($user_totals['vendor_shipping_reimbursement_total']) ? $user_totals['vendor_shipping_reimbursement_total'] : 0) * 0.9 ?>,<?= (isset($user_totals['vendor_shipping_reimbursement_total']) ? $user_totals['vendor_shipping_reimbursement_total'] : 0) * 0.95 ?>,<?= isset($user_totals['vendor_shipping_reimbursement_total']) ? $user_totals['vendor_shipping_reimbursement_total'] : 0 ?>"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Expandable Shipping Details -->
                <div id="vendorShippingDetails" style="display: none;">
                    <div class="mt-3 p-3 bg-light border rounded" style="cursor: pointer;" onclick="toggleVendorShippingDetails()">
                        <div class="mb-2">
                            <small class="text-muted fw-bold"><?= __('user.paid_shipping') ?>: <span class="text-success"><?= $fun_c_format(isset($user_totals['vendor_shipping_reimbursement_paid']) ? $user_totals['vendor_shipping_reimbursement_paid'] : 0) ?></span></small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted fw-bold"><?= __('user.requested_shipping') ?>: <span class="text-primary"><?= $fun_c_format(isset($user_totals['vendor_shipping_reimbursement_requested']) ? $user_totals['vendor_shipping_reimbursement_requested'] : 0) ?></span></small>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted fw-bold"><?= __('user.unpaid_shipping') ?>: <span class="text-warning"><?= $fun_c_format(isset($user_totals['vendor_shipping_reimbursement_unpaid']) ? $user_totals['vendor_shipping_reimbursement_unpaid'] : 0) ?></span></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Clickable Bottom Area -->
            <div class="position-absolute bottom-0 start-0 w-100" style="height: 35px; cursor: pointer; background: linear-gradient(to top, rgba(255,193,7,0.1), transparent);" onclick="toggleVendorShippingDetails()">
                <!-- Toggle Icon in Bottom Right Corner -->
                <div class="position-absolute bottom-0 end-0 p-2">
                    <i class="bi bi-plus-circle text-warning fs-5" id="vendorShippingToggle"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php if ($dashboard_mode === 'affiliate'): ?>
<!-- PERFORMANCE ANALYTICS ROW (AFFILIATE MODE ONLY) -->
<div class="row g-3 mb-4">
    
    <!-- Monthly Earnings -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-currency-dollar text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0 text-muted small"><?= __('user.monthly_earnings') ?></p>
                        <h6 class="mb-0 fw-bold"><?= $fun_c_format($analytics['monthly_earnings']) ?></h6>
                        <small class="text-<?= $analytics['monthly_growth'] >= 0 ? 'success' : 'danger' ?>">
                            <i class="bi bi-arrow-<?= $analytics['monthly_growth'] >= 0 ? 'up' : 'down' ?>"></i> 
                            <?= abs($analytics['monthly_growth']) ?>%
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Earnings -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-calendar-week text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0 text-muted small"><?= __('user.weekly_earnings') ?></p>
                        <h6 class="mb-0 fw-bold"><?= $fun_c_format($analytics['weekly_earnings']) ?></h6>
                        <small class="text-<?= $analytics['weekly_growth'] >= 0 ? 'success' : 'danger' ?>">
                            <i class="bi bi-arrow-<?= $analytics['weekly_growth'] >= 0 ? 'up' : 'down' ?>"></i> 
                            <?= abs($analytics['weekly_growth']) ?>%
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion Rate -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-graph-up text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0 text-muted small"><?= __('user.conversion_rate') ?></p>
                        <h6 class="mb-0 fw-bold"><?= $analytics['performance']['conversion_rate'] ?>%</h6>
                        <small class="text-muted">
                            <?= number_format($analytics['performance']['total_orders']) ?> / <?= number_format($analytics['performance']['total_clicks']) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

                    <!-- <?= __('user.avg_commission') ?> -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-trophy text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0 text-muted small"><?= __('user.avg_commission') ?></p>
                        <h6 class="mb-0 fw-bold"><?= $fun_c_format($analytics['performance']['avg_commission']) ?></h6>
                        <small class="text-muted"><?= __('user.per_order') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>



<?php if ($dashboard_mode === 'affiliate'): ?>
<!-- EARNINGS TREND CHART -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-2">
                <h6 class="mb-0"><i class="bi bi-graph-up me-1"></i><?= __('user.earnings_performance_trends') ?></h6>
            </div>
            <div class="card-body p-3">
                <canvas id="earningsTrendChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>



<!-- ========================================= -->
<!-- AFFILIATE ACTIVITIES (AFFILIATE MODE ONLY) -->
<!-- ========================================= -->

<div class="row g-4 mb-5">
    
    <!-- WALLET STATUS -->
    <div class="col-lg-6">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-warning text-white text-center">
                <h5 class="mb-0"><i class="bi bi-wallet me-2"></i><?= __('user.wallet_status') ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.ready_to_withdraw') ?></h6>
                        <h5 class="fw-bold text-success">
                            <?= $fun_c_format($user_totals['user_balance']) ?>
                        </h5>
                        <small class="text-muted"><?= __('user.available_now') ?></small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.pending_money') ?></h6>
                        <h5 class="fw-bold text-warning">
                            <?= $fun_c_format($user_totals['wallet_unpaid_amount']) ?>
                        </h5>
                        <small class="text-muted">
                            <?= (int)$user_totals['wallet_unpaid_count'] ?> <?= __('user.payments') ?>
                        </small>
                    </div>
                </div>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.requested') ?></h6>
                        <h5 class="fw-bold text-primary">
                            <?= $fun_c_format($user_totals['wallet_request_sent_amount']) ?>
                        </h5>
                        <small class="text-muted">
                            <?= (int)$user_totals['wallet_request_sent_count'] ?> <?= __('user.requests') ?>
                        </small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.already_paid') ?></h6>
                        <h5 class="fw-bold text-success">
                            <?= $fun_c_format($user_totals['wallet_accept_amount']) ?>
                        </h5>
                        <small class="text-muted">
                            <?= (int)$user_totals['wallet_accept_count'] ?> <?= __('user.payments') ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PERFORMANCE METRICS -->
    <div class="col-lg-6">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-info text-white text-center">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i><?= __('user.performance_metrics') ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="row text-center mb-4">
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.conversion_rate') ?></h6>
                        <h5 class="fw-bold text-success">
                            <?= $user_totals['total_clicks_count'] > 0 ? number_format(($user_totals['click_action_total'] / $user_totals['total_clicks_count']) * 100, 1) : 0 ?>%
                        </h5>
                        <small class="text-muted"><?= __('user.clicks_to_actions') ?></small>
                    </div>
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.avg_click_value') ?></h6>
                        <h5 class="fw-bold text-primary">
                            <?= $user_totals['total_clicks_count'] > 0 ? $fun_c_format($user_totals['total_clicks_commission'] / $user_totals['total_clicks_count']) : $fun_c_format(0) ?>
                        </h5>
                        <small class="text-muted"><?= __('user.per_click') ?></small>
                    </div>
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.avg_action_value') ?></h6>
                        <h5 class="fw-bold text-warning">
                            <?= $user_totals['click_action_total'] > 0 ? $fun_c_format($user_totals['click_action_commission'] / $user_totals['click_action_total']) : $fun_c_format(0) ?>
                        </h5>
                        <small class="text-muted"><?= __('user.per_action') ?></small>
                    </div>
                </div>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.total_sales') ?></h6>
                        <h5 class="fw-bold text-success">
                            <?= (int)$user_totals['sale_localstore_count'] ?>
                        </h5>
                        <small class="text-muted"><?= __('user.products_sold') ?></small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.sales_commission') ?></h6>
                        <h5 class="fw-bold text-info">
                            <?= $fun_c_format($user_totals['sale_localstore_commission']) ?>
                        </h5>
                        <small class="text-muted"><?= __('user.from_sales') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>

<?php if ($dashboard_mode === 'vendor'): ?>
<!-- VENDOR BUSINESS OVERVIEW -->
<div class="row g-4 mb-5">
    
    <!-- YOUR BUSINESS SALES -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cart text-success fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.your_business_sales') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-success me-2">
                                <i class="bi bi-graph-up me-1"></i><?= __('user.total_revenue') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.7 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.8 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.85 ?>,<?= ((isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0)) * 0.9 ?>,<?= (isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) + (isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0) ?>"></div>
                        </div>
                    </div>
                </div>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.local_store') ?></h6>
                        <span class="fw-bold text-success"><?= $fun_c_format(isset($user_totals['vendor_sale_localstore_total']) ? $user_totals['vendor_sale_localstore_total'] : 0) ?></span>
                        <small class="text-muted d-block"><?= __('user.your_own_store') ?></small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.external_sales') ?></h6>
                        <span class="fw-bold text-success"><?= $fun_c_format(isset($user_totals['vendor_order_external_total']) ? $user_totals['vendor_order_external_total'] : 0) ?></span>
                        <small class="text-muted d-block"><?= __('user.other_platforms') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AFFILIATES PROMOTING YOU -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-people text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1 small"><?= __('user.affiliates_promoting_you') ?></h6>
                        <h4 class="fw-bold mb-1"><?php echo $fun_c_format($user_totals['vendor_click_localstore_commission_pay'] + $user_totals['vendor_click_external_commission_pay'] + $user_totals['vendor_action_external_commission_pay'] + $user_totals['vendor_sale_localstore_commission_pay'] + $user_totals['vendor_order_external_commission_pay']); ?></h4>
                        <div class="d-flex align-items-center">
                            <small class="text-primary me-2">
                                <i class="bi bi-share me-1"></i><?= __('user.total_paid_to_affiliates') ?>
                            </small>
                            <div class="sparkline-mini" data-values="<?= ($user_totals['vendor_click_localstore_commission_pay'] + $user_totals['vendor_click_external_commission_pay'] + $user_totals['vendor_action_external_commission_pay'] + $user_totals['vendor_sale_localstore_commission_pay'] + $user_totals['vendor_order_external_commission_pay']) * 0.7 ?>,<?= ($user_totals['vendor_click_localstore_commission_pay'] + $user_totals['vendor_click_external_commission_pay'] + $user_totals['vendor_action_external_commission_pay'] + $user_totals['vendor_sale_localstore_commission_pay'] + $user_totals['vendor_order_external_commission_pay']) * 0.8 ?>,<?= ($user_totals['vendor_click_localstore_commission_pay'] + $user_totals['vendor_click_external_commission_pay'] + $user_totals['vendor_action_external_commission_pay'] + $user_totals['vendor_sale_localstore_commission_pay'] + $user_totals['vendor_order_external_commission_pay']) * 0.85 ?>,<?= ($user_totals['vendor_click_localstore_commission_pay'] + $user_totals['vendor_click_external_commission_pay'] + $user_totals['vendor_action_external_commission_pay'] + $user_totals['vendor_sale_localstore_commission_pay'] + $user_totals['vendor_order_external_commission_pay']) * 0.9 ?>,<?= $user_totals['vendor_click_localstore_commission_pay'] + $user_totals['vendor_click_external_commission_pay'] + $user_totals['vendor_action_external_commission_pay'] + $user_totals['vendor_sale_localstore_commission_pay'] + $user_totals['vendor_order_external_commission_pay'] ?>"></div>
                        </div>
                    </div>
                </div>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.click_commissions') ?></h6>
                        <span class="fw-bold text-primary">
                            <?= $fun_c_format($user_totals['vendor_click_localstore_commission_pay'] + $user_totals['vendor_click_external_commission_pay']) ?>
                        </span>
                        <small class="text-muted d-block"><?= __('user.for_clicks') ?></small>
                    </div>
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.action_commissions') ?></h6>
                        <span class="fw-bold text-primary"><?= $fun_c_format($user_totals['vendor_action_external_commission_pay']) ?></span>
                        <small class="text-muted d-block"><?= __('user.for_actions') ?></small>
                    </div>
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.sale_commissions') ?></h6>
                        <span class="fw-bold text-primary">
                            <?= $fun_c_format($user_totals['vendor_sale_localstore_commission_pay'] + $user_totals['vendor_order_external_commission_pay']) ?>
                        </span>
                        <small class="text-muted d-block"><?= __('user.for_sales') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<?php if (isset($store_setting) && (int)$store_setting['status'] == 1): ?>
<!-- SHIPPING REIMBURSEMENT -->
<div class="row g-4 mb-5">
    
    <!-- SHIPPING REIMBURSEMENT -->
    <div class="col-lg-6">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-warning text-white text-center">
                <h5 class="mb-0"><i class="bi bi-truck me-2"></i><?= __('user.shipping_reimbursement') ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <small class="text-muted d-block mb-2"><?= __('user.total_shipping_reimbursement') ?></small>
                    <h3 class="fw-bold text-warning mb-0">
                        <?= $fun_c_format(isset($user_totals['vendor_shipping_reimbursement_total']) ? $user_totals['vendor_shipping_reimbursement_total'] : 0) ?>
                    </h3>
                </div>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.paid_shipping') ?></h6>
                        <span class="fw-bold text-success"><?= $fun_c_format(isset($user_totals['vendor_shipping_reimbursement_paid']) ? $user_totals['vendor_shipping_reimbursement_paid'] : 0) ?></span>
                        <small class="text-muted d-block"><?= __('user.reimbursed') ?></small>
                    </div>
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.requested_shipping') ?></h6>
                        <span class="fw-bold text-primary"><?= $fun_c_format(isset($user_totals['vendor_shipping_reimbursement_requested']) ? $user_totals['vendor_shipping_reimbursement_requested'] : 0) ?></span>
                        <small class="text-muted d-block"><?= __('user.in_review') ?></small>
                    </div>
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.unpaid_shipping') ?></h6>
                        <span class="fw-bold text-warning"><?= $fun_c_format(isset($user_totals['vendor_shipping_reimbursement_unpaid']) ? $user_totals['vendor_shipping_reimbursement_unpaid'] : 0) ?></span>
                        <small class="text-muted d-block"><?= __('user.pending') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SHIPPING CHART -->
    <div class="col-lg-6">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-info text-white text-center">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i><?= __('user.shipping_visualization') ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <small class="text-muted d-block mb-2"><?= __('user.shipping_status_overview') ?></small>
                    <h3 class="fw-bold text-info mb-0">
                        <?php 
                        $total_shipping = isset($user_totals['vendor_shipping_reimbursement_total']) ? $user_totals['vendor_shipping_reimbursement_total'] : 0;
                        $paid_shipping = isset($user_totals['vendor_shipping_reimbursement_paid']) ? $user_totals['vendor_shipping_reimbursement_paid'] : 0;
                        $requested_shipping = isset($user_totals['vendor_shipping_reimbursement_requested']) ? $user_totals['vendor_shipping_reimbursement_requested'] : 0;
                        $unpaid_shipping = isset($user_totals['vendor_shipping_reimbursement_unpaid']) ? $user_totals['vendor_shipping_reimbursement_unpaid'] : 0;
                        echo $fun_c_format($total_shipping);
                        ?>
                    </h3>
                </div>
                
                <?php if ($total_shipping > 0): ?>
                <div class="text-center">
                    <canvas id="shippingProgressChart" height="80"></canvas>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                            <small class="text-muted"><?= __('user.paid') ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="bg-primary rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                            <small class="text-muted"><?= __('user.requested') ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="bg-warning rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                            <small class="text-muted"><?= __('user.pending') ?></small>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="bi bi-truck text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 mb-0"><?= __('user.no_shipping_data') ?></p>
                    <small class="text-muted"><?= __('user.shipping_will_appear_here') ?></small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>

<!-- VENDOR DEPOSITS -->
<div class="row g-4 mb-5">
    <div class="col-lg-6">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i><?= __('user.vendor_deposits') ?></h5>
            </div>
            <div class="card-body p-4">
                <!-- DEPOSIT STATUS OVERVIEW -->
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.pending') ?></h6>
                        <h5 class="fw-bold text-warning">
                            <?= isset($vendor_data['deposit_stats']) ? (int)$vendor_data['deposit_stats']->pending_count : 0 ?>
                        </h5>
                        <small class="text-muted">
                            <?= isset($vendor_data['deposit_stats']) ? $fun_c_format($vendor_data['deposit_stats']->pending_amount) : $fun_c_format(0) ?>
                        </small>
                    </div>
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.approved') ?></h6>
                        <h5 class="fw-bold text-success">
                            <?= isset($vendor_data['deposit_stats']) ? (int)$vendor_data['deposit_stats']->approved_count : 0 ?>
                        </h5>
                        <small class="text-muted">
                            <?= isset($vendor_data['deposit_stats']) ? $fun_c_format($vendor_data['deposit_stats']->approved_amount) : $fun_c_format(0) ?>
                        </small>
                    </div>
                    <div class="col-4">
                        <h6 class="text-muted mb-1"><?= __('user.denied') ?></h6>
                        <h5 class="fw-bold text-danger">
                            <?= isset($vendor_data['deposit_stats']) ? (int)$vendor_data['deposit_stats']->denied_count : 0 ?>
                        </h5>
                        <small class="text-muted">
                            <?= isset($vendor_data['deposit_stats']) ? $fun_c_format($vendor_data['deposit_stats']->denied_amount) : $fun_c_format(0) ?>
                        </small>
                    </div>
                </div>
                
                <hr>
                
                <!-- TOTAL SUMMARY -->
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.total_deposits') ?></h6>
                        <h5 class="fw-bold text-primary">
                            <?= isset($vendor_data['deposit_stats']) ? (int)$vendor_data['deposit_stats']->total_count : 0 ?>
                        </h5>
                        <small class="text-muted"><?= __('user.all_deposits') ?></small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-1"><?= __('user.total_deposited') ?></h6>
                        <h5 class="fw-bold text-info">
                            <?= isset($vendor_data['total_deposited']) ? $fun_c_format($vendor_data['total_deposited']) : $fun_c_format(0) ?>
                        </h5>
                        <small class="text-muted"><?= __('user.approved_deposits') ?></small>
                    </div>
                </div>
                
                <!-- Deposit notifications now shown in top banner for better visibility -->
                
                <!-- VIEW ALL DEPOSITS BUTTON -->
                <div class="d-grid mt-3">
                    <a href="<?= base_url('usercontrol/my_deposits') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-list-ul me-2"></i>
                        <?= __('user.view_all_deposits') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- MEMBERSHIP STATUS -->
    <div class="col-lg-6">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-success text-white text-center">
                <h5 class="mb-0"><i class="bi bi-person-check me-2"></i><?= __('user.membership_status') ?></h5>
            </div>
            <div class="card-body p-4">
                <?php if (isset($MembershipSetting) && $MembershipSetting['status']): ?>
                    <?php if ((isset($is_lifetime_plan) && $is_lifetime_plan) || !$isMembershipAccess): ?>
                        <!-- Lifetime Free Membership -->
                        <div class="text-center">
                            <div class="badge bg-success mb-3 p-3 fs-6">
                                <i class="bi bi-infinity me-2"></i>
                                <?= __('user.lifetime_free_membership') ?>
                            </div>
                            <p class="text-muted mb-0"><?= __('user.lifetime_free_membership_access_all_system_functions') ?></p>
                        </div>
                    <?php elseif (isset($plan) && $plan && $isMembershipAccess): ?>
                        <!-- Active Plan -->
                        <?php
                        $checkDay = max((int)$MembershipSetting['notificationbefore'], 1);
                        if($plan->remainDay() != 'lifetime' && $plan->remainDay() <= $checkDay && !$plan->is_lifetime):
                        ?>
                        <div class="alert alert-warning mt-3 mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= __('user.your_account_will_expire_in') ?>
                            <span class="fw-bold" data-time-remains="<?= $plan->strToTimeRemains(); ?>"><?= $plan->remainDay() ?></span>
                            <a href="<?= base_url('/usercontrol/purchase_plan/') ?>" class="alert-link"><?= __('user.click_here') ?></a>
                            <?= __('user.to_renew_plan') ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row text-center">
                            <div class="col-6">
                                <h6 class="text-muted mb-1"><?= __('user.current_plan') ?></h6>
                                <h5 class="fw-bold text-success">
                                    <?= $plan->plan ? $plan->plan->name : '' ?>
                                </h5>
                                <small class="text-muted"><?= __('user.active_plan') ?></small>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1"><?= __('user.remaining_time') ?></h6>
                                <?php if($plan->is_lifetime): ?>
                                <h5 class="fw-bold text-info">
                                    <i class="bi bi-infinity"></i> <?= __('user.lifetime') ?>
                                </h5>
                                <?php else: ?>
                                <h5 class="fw-bold text-info" data-time-remains="<?= $plan->strToTimeRemains() ?>">
                                    <?= $plan->remainDay() ?>
                                </h5>
                                <?php endif; ?>
                                <small class="text-muted">
                                    <?= dateFormat($plan->started_at, 'd F Y') ?> - 
                                    <?= ($plan->is_lifetime) ? __('user.lifetime') : dateFormat($plan->expire_at, 'd F Y h:i A') ?>
                                </small>
                            </div>
                        </div>
                        
                        <?php if(isset($payment_details)): ?>
                        <hr class="my-3">
                        <div class="row text-center">
                            <div class="col-12">
                                <h6 class="text-muted mb-1"><?= __('user.payment_status') ?></h6>
                                <span class="badge bg-success rounded-pill p-2"><?= __('user.active') ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Plan Description Section -->
                        <div class="mt-3">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-transparent border-0 py-2 d-flex justify-content-between align-items-center" style="cursor: pointer;" onclick="togglePlanDescription()">
                                    <h6 class="mb-0 text-primary">
                                        <i class="bi bi-info-circle me-2"></i><?= __('user.description') ?>
                                    </h6>
                                    <i class="bi bi-chevron-down text-primary" id="planDescriptionToggle"></i>
                                </div>
                                <div class="card-body py-3" id="planDescriptionContent" style="display: none;">
                                    <div class="plan-description-content">
                                        <?php if (isset($plan->plan) && $plan->plan && !empty($plan->plan->description)): ?>
                                            <?= $plan->plan->description ?>
                                        <?php else: ?>
                                            <div class="text-center text-muted py-3">
                                                <i class="bi bi-info-circle fs-1 mb-2 d-block"></i>
                                                <p class="mb-0"><?= __('user.no_description_available') ?></p>
                                                <small><?= __('user.contact_admin_for_details') ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- No Active Plan -->
                        <div class="text-center">
                            <div class="badge bg-warning mb-3 p-3 fs-6">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?= __('user.no_active_plan') ?>
                            </div>
                            <p class="text-muted mb-3"><?= __('user.purchase_plan_to_continue') ?></p>
                            <a href="<?= base_url('usercontrol/purchase_plan') ?>" class="btn btn-primary">
                                <i class="bi bi-cart me-2"></i>
                                <?= __('user.purchase_plan') ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Membership Disabled -->
                    <div class="text-center">
                        <div class="badge bg-secondary mb-3 p-3 fs-6">
                            <i class="bi bi-x-circle me-2"></i>
                            <?= __('user.membership_disabled') ?>
                        </div>
                        <p class="text-muted mb-0"><?= __('user.membership_system_disabled') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($dashboard_mode === 'affiliate'): ?>
<!-- AFFILIATE ANALYTICS CHARTS -->
<!-- MONTHLY PERFORMANCE COMPARISON -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm analytics-chart-card">
            <div class="card-header bg-success text-white py-2">
                <h6 class="mb-0"><i class="bi bi-bar-chart me-1"></i><?= __('user.monthly_performance_comparison') ?></h6>
            </div>
            <div class="card-body p-3">
                <canvas id="monthlyPerformanceChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- CHANNEL PERFORMANCE BREAKDOWN -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm analytics-chart-card">
            <div class="card-header bg-warning text-white py-2">
                <h6 class="mb-0"><i class="bi bi-pie-chart me-1"></i><?= __('user.channel_performance') ?></h6>
            </div>
            <div class="card-body p-3">
                <canvas id="channelPerformanceChart" height="150"></canvas>
            </div>
        </div>
    </div>
    
    <!-- GOAL PROGRESS DASHBOARD -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm analytics-chart-card">
            <div class="card-header bg-info text-white py-2">
                <h6 class="mb-0"><i class="bi bi-target me-1"></i><?= __('user.goal_progress') ?></h6>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <!-- Earnings Goal -->
                    <div class="col-6">
                        <div class="text-center mb-3">
                            <div class="progress-circle mx-auto mb-2" style="width: 80px; height: 80px; position: relative;">
                                <svg width="80" height="80" viewBox="0 0 42 42" class="progress-ring">
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#e5e7eb" stroke-width="3"/>
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#10b981" stroke-width="3" 
                                            stroke-dasharray="<?= $analytics['goals']['earnings_progress'] ?> <?= 100 - $analytics['goals']['earnings_progress'] ?>" 
                                            stroke-dashoffset="25" transform="rotate(-90 21 21)"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <small class="fw-bold"><?= round($analytics['goals']['earnings_progress']) ?>%</small>
                                </div>
                            </div>
                            <small class="text-muted d-block"><?= __('user.monthly_earnings_goal') ?></small>
                            <div class="mt-1">
                                <small class="text-success fw-bold"><?= $fun_c_format($analytics['goals']['monthly_earnings']) ?></small>
                                <span class="text-muted">/</span>
                                <small class="text-primary"><?= $fun_c_format($analytics['goals']['earnings_target']) ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Clicks Goal -->
                    <div class="col-6">
                        <div class="text-center mb-3">
                            <div class="progress-circle mx-auto mb-2" style="width: 80px; height: 80px; position: relative;">
                                <svg width="80" height="80" viewBox="0 0 42 42" class="progress-ring">
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#e5e7eb" stroke-width="3"/>
                                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#3b82f6" stroke-width="3" 
                                            stroke-dasharray="<?= $analytics['goals']['clicks_progress'] ?> <?= 100 - $analytics['goals']['clicks_progress'] ?>" 
                                            stroke-dashoffset="25" transform="rotate(-90 21 21)"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <small class="fw-bold"><?= round($analytics['goals']['clicks_progress']) ?>%</small>
                                </div>
                            </div>
                            <small class="text-muted d-block"><?= __('user.monthly_clicks_goal') ?></small>
                            <div class="mt-1">
                                <small class="text-success fw-bold"><?= number_format($analytics['goals']['monthly_clicks']) ?></small>
                                <span class="text-muted">/</span>
                                <small class="text-primary"><?= number_format($analytics['goals']['clicks_target']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- COMMON ACTIONS (ALL USERS) -->
<!-- MEMBERSHIP STATUS (FOR NON-VENDORS) -->
<?php if ($dashboard_mode === 'affiliate' && (!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1)): ?>
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-success text-white text-center">
                <h5 class="mb-0"><i class="bi bi-person-check me-2"></i><?= __('user.membership_status') ?></h5>
            </div>
            <div class="card-body p-4">
                <?php if (isset($MembershipSetting) && $MembershipSetting['status']): ?>
                    <?php if ((isset($is_lifetime_plan) && $is_lifetime_plan) || !$isMembershipAccess): ?>
                        <!-- Lifetime Free Membership -->
                        <div class="text-center">
                            <div class="badge bg-success mb-3 p-3 fs-6">
                                <i class="bi bi-infinity me-2"></i>
                                <?= __('user.lifetime_free_membership') ?>
                            </div>
                            <p class="text-muted mb-0"><?= __('user.lifetime_free_membership_access_all_system_functions') ?></p>
                        </div>
                    <?php elseif (isset($plan) && $plan && $isMembershipAccess): ?>
                        <!-- Active Plan -->
                        <?php
                        $checkDay = max((int)$MembershipSetting['notificationbefore'], 1);
                        if($plan->remainDay() != 'lifetime' && $plan->remainDay() <= $checkDay && !$plan->is_lifetime):
                        ?>
                        <div class="alert alert-warning mt-3 mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= __('user.your_account_will_expire_in') ?>
                            <span class="fw-bold" data-time-remains="<?= $plan->strToTimeRemains(); ?>"><?= $plan->remainDay() ?></span>
                            <a href="<?= base_url('/usercontrol/purchase_plan/') ?>" class="alert-link"><?= __('user.click_here') ?></a>
                            <?= __('user.to_renew_plan') ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row text-center">
                            <div class="col-6">
                                <h6 class="text-muted mb-1"><?= __('user.current_plan') ?></h6>
                                <h5 class="fw-bold text-success">
                                    <?= $plan->plan ? $plan->plan->name : '' ?>
                                </h5>
                                <small class="text-muted"><?= __('user.active_plan') ?></small>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1"><?= __('user.remaining_time') ?></h6>
                                <?php if($plan->is_lifetime): ?>
                                <h5 class="fw-bold text-info">
                                    <i class="bi bi-infinity"></i> <?= __('user.lifetime') ?>
                                </h5>
                                <?php else: ?>
                                <h5 class="fw-bold text-info" data-time-remains="<?= $plan->strToTimeRemains() ?>">
                                    <?= $plan->remainDay() ?>
                                </h5>
                                <?php endif; ?>
                                <small class="text-muted">
                                    <?= dateFormat($plan->started_at, 'd F Y') ?> - 
                                    <?= ($plan->is_lifetime) ? __('user.lifetime') : dateFormat($plan->expire_at, 'd F Y h:i A') ?>
                                </small>
                            </div>
                        </div>
                        
                        <?php if(isset($payment_details)): ?>
                        <hr class="my-3">
                        <div class="row text-center">
                            <div class="col-12">
                                <h6 class="text-muted mb-1"><?= __('user.payment_status') ?></h6>
                                <span class="badge bg-success rounded-pill p-2"><?= __('user.active') ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Plan Description Section -->
                        <div class="mt-3">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-transparent border-0 py-2 d-flex justify-content-between align-items-center" style="cursor: pointer;" onclick="togglePlanDescription()">
                                    <h6 class="mb-0 text-primary">
                                        <i class="bi bi-info-circle me-2"></i><?= __('user.description') ?>
                                    </h6>
                                    <i class="bi bi-chevron-down text-primary" id="planDescriptionToggle"></i>
                                </div>
                                <div class="card-body py-3" id="planDescriptionContent" style="display: none;">
                                    <div class="plan-description-content">
                                        <?php if (isset($plan->plan) && $plan->plan && !empty($plan->plan->description)): ?>
                                            <?= $plan->plan->description ?>
                                        <?php else: ?>
                                            <div class="text-center text-muted py-3">
                                                <i class="bi bi-info-circle fs-1 mb-2 d-block"></i>
                                                <p class="mb-0"><?= __('user.no_description_available') ?></p>
                                                <small><?= __('user.contact_admin_for_details') ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- No Active Plan -->
                        <div class="text-center">
                            <div class="badge bg-warning mb-3 p-3 fs-6">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?= __('user.no_active_plan') ?>
                            </div>
                            <p class="text-muted mb-3"><?= __('user.purchase_plan_to_continue') ?></p>
                            <a href="<?= base_url('usercontrol/purchase_plan') ?>" class="btn btn-primary">
                                <i class="bi bi-cart me-2"></i>
                                <?= __('user.purchase_plan') ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Membership Disabled -->
                    <div class="text-center">
                        <div class="badge bg-secondary mb-3 p-3 fs-6">
                            <i class="bi bi-x-circle me-2"></i>
                            <?= __('user.membership_disabled') ?>
                        </div>
                        <p class="text-muted mb-0"><?= __('user.membership_system_disabled') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- QUICK ACTIONS -->
<div class="row mb-5">
    <div class="col-12">
        <div class="modern-section-header actions-section">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="bi bi-lightning"></i>
                </div>
                <div class="section-content">
                    <h2 class="section-title"><?= __('user.quick_actions') ?></h2>
                    <p class="section-subtitle"><?= __('user.things_you_do_most') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-dark text-white text-center">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i><?= __('user.things_you_do_most') ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('usercontrol/all_transaction') ?>" class="btn btn-outline-primary btn-lg w-100 p-3">
                            <i class="bi bi-cash-stack fs-1 mb-2 d-block"></i>
                            <div class="fw-bold fs-5"><?= __('user.see_all_money') ?></div>
                            <small class="text-muted"><?= __('user.view_all_transactions') ?></small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('usercontrol/mywallet') ?>" class="btn btn-outline-success btn-lg w-100 p-3">
                            <i class="bi bi-wallet fs-1 mb-2 d-block"></i>
                            <div class="fw-bold fs-5"><?= __('user.my_wallet') ?></div>
                            <small class="text-muted"><?= __('user.manage_your_money') ?></small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('usercontrol/integration_tools') ?>" class="btn btn-outline-warning btn-lg w-100 p-3">
                            <i class="bi bi-tools fs-1 mb-2 d-block"></i>
                            <div class="fw-bold fs-5"><?= __('user.promotion_tools') ?></div>
                            <small class="text-muted"><?= __('user.get_promotion_links') ?></small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('usercontrol/my_network') ?>" class="btn btn-outline-info btn-lg w-100 p-3">
                            <i class="bi bi-people fs-1 mb-2 d-block"></i>
                            <div class="fw-bold fs-5"><?= __('user.my_network') ?></div>
                            <small class="text-muted"><?= __('user.view_referrals') ?></small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div> <!-- End container-fluid -->
</div> <!-- End analytics-dashboard-wrapper -->

<!-- Chart.js Library -->
<script src="<?= base_url('assets/template/js/chart.umd.min.js') ?>?v=<?= av() ?>"></script>

<script>
// Error handling for Chart.js
window.addEventListener('error', function(e) {
    if (e.message.includes('Chart')) {
        console.warn('Chart.js not loaded properly, charts will be disabled');
    }
});

// Check if Chart.js is available
function isChartAvailable() {
    return typeof Chart !== 'undefined' && Chart;
}





// Click Earnings Details Toggle Function
function toggleClickEarningsDetails() {
    const content = document.getElementById('clickEarningsDetails');
    const toggle = document.getElementById('clickEarningsToggle');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        toggle.className = 'bi bi-dash-circle';
    } else {
        content.style.display = 'none';
        toggle.className = 'bi bi-plus-circle';
    }
}

// Action Earnings Details Toggle Function
function toggleActionEarningsDetails() {
    const content = document.getElementById('actionEarningsDetails');
    const toggle = document.getElementById('actionEarningsToggle');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        toggle.className = 'bi bi-dash-circle';
    } else {
        content.style.display = 'none';
        toggle.className = 'bi bi-plus-circle';
    }
}

// Vendor Sales Details Toggle Function
function toggleVendorSalesDetails() {
    const content = document.getElementById('vendorSalesDetails');
    const toggle = document.getElementById('vendorSalesToggle');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        toggle.className = 'bi bi-dash-circle';
    } else {
        content.style.display = 'none';
        toggle.className = 'bi bi-plus-circle';
    }
}

// Vendor Shipping Details Toggle Function
function toggleVendorShippingDetails() {
    const content = document.getElementById('vendorShippingDetails');
    const toggle = document.getElementById('vendorShippingToggle');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        toggle.className = 'bi bi-dash-circle';
    } else {
        content.style.display = 'none';
        toggle.className = 'bi bi-plus-circle';
    }
}

// Dismiss Deposit Alert Function
function dismissDepositAlert() {
    const alert = document.querySelector('.vendor-deposit-alert');
    if (alert) {
        alert.style.animation = 'slideOutUp 0.3s ease-in forwards';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

// Plan Description Toggle Function (unique)
function togglePlanDescription() {
    const content = document.getElementById('planDescriptionContent');
    const toggle = document.getElementById('planDescriptionToggle');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        toggle.className = 'bi bi-chevron-up text-primary';
    } else {
        content.style.display = 'none';
        toggle.className = 'bi bi-chevron-down text-primary';
    }
}




// Sparkline Chart Function
function createSparkline(container, values) {
    // Validate input
    if (!values || values === '') {
        return;
    }
    
    const canvas = document.createElement('canvas');
    canvas.width = 60;
    canvas.height = 20;
    container.appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    const data = values.split(',').map(v => {
        const parsed = parseFloat(v);
        return isNaN(parsed) ? 0 : parsed;
    });
    
    // Handle case where all values are 0
    if (data.every(val => val === 0)) {
        ctx.strokeStyle = '#6b7280';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(0, canvas.height / 2);
        ctx.lineTo(canvas.width, canvas.height / 2);
        ctx.stroke();
        return;
    }
    
    const max = Math.max(...data);
    const min = Math.min(...data);
    const range = max - min || 1; // Prevent division by zero
    
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



// Earnings Trend Chart
const earningsTrendElement = document.getElementById('earningsTrendChart');
if (earningsTrendElement && isChartAvailable()) {
    const earningsCtx = earningsTrendElement.getContext('2d');
    const earningsChart = new Chart(earningsCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Click Earnings',
            data: [<?= (isset($user_totals['total_clicks_commission']) && is_numeric($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.8 ?>, <?= (isset($user_totals['total_clicks_commission']) && is_numeric($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.85 ?>, <?= (isset($user_totals['total_clicks_commission']) && is_numeric($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.9 ?>, <?= (isset($user_totals['total_clicks_commission']) && is_numeric($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.95 ?>, <?= (isset($user_totals['total_clicks_commission']) && is_numeric($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0) * 0.98 ?>, <?= isset($user_totals['total_clicks_commission']) && is_numeric($user_totals['total_clicks_commission']) ? $user_totals['total_clicks_commission'] : 0 ?>],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Action Earnings',
            data: [<?= (isset($user_totals['click_action_commission']) && is_numeric($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.7 ?>, <?= (isset($user_totals['click_action_commission']) && is_numeric($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.8 ?>, <?= (isset($user_totals['click_action_commission']) && is_numeric($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.85 ?>, <?= (isset($user_totals['click_action_commission']) && is_numeric($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.9 ?>, <?= (isset($user_totals['click_action_commission']) && is_numeric($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0) * 0.95 ?>, <?= isset($user_totals['click_action_commission']) && is_numeric($user_totals['click_action_commission']) ? $user_totals['click_action_commission'] : 0 ?>],
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
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
}



// Monthly Performance Comparison Chart
const monthlyPerformanceElement = document.getElementById('monthlyPerformanceChart');
if (monthlyPerformanceElement && isChartAvailable()) {
    const monthlyCtx = monthlyPerformanceElement.getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: [
                '<?= __('user.jan') ?>', '<?= __('user.feb') ?>', '<?= __('user.mar') ?>', 
                '<?= __('user.apr') ?>', '<?= __('user.may') ?>', '<?= __('user.jun') ?>',
                '<?= __('user.jul') ?>', '<?= __('user.aug') ?>', '<?= __('user.sep') ?>', 
                '<?= __('user.oct') ?>', '<?= __('user.nov') ?>', '<?= __('user.dec') ?>'
            ],
            datasets: [{
                label: '<?= __('user.earnings') ?>',
                data: <?= isset($chart_data['monthly_performance']['earnings']) ? json_encode($chart_data['monthly_performance']['earnings']) : '[0,0,0,0,0,0,0,0,0,0,0,0]' ?>,
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: '#10b981',
                borderWidth: 1
            }, {
                label: '<?= __('user.clicks') ?>',
                data: <?= isset($chart_data['monthly_performance']['clicks']) ? json_encode($chart_data['monthly_performance']['clicks']) : '[0,0,0,0,0,0,0,0,0,0,0,0]' ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: '#3b82f6',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
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
            }
        }
    });
}

// Channel Performance Chart
const channelPerformanceElement = document.getElementById('channelPerformanceChart');
if (channelPerformanceElement && isChartAvailable()) {
    // Prepare chart data with proper labels and non-zero values
    const channelData = <?= isset($chart_data['channel_performance']) ? json_encode($chart_data['channel_performance']) : '{}' ?>;
    const dashboardMode = '<?= $dashboard_mode ?>';
    
    const labels = [];
    const data = [];
    const colors = [];
    const colorMap = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'];
    let colorIndex = 0;
    
    // Check if we have any data at all
    const hasAnyData = Object.values(channelData).some(value => value > 0);
    
    if (hasAnyData) {
        if (dashboardMode === 'affiliate') {
            // AFFILIATE DATA ONLY
            if (channelData.click_localstore_commission > 0) {
                labels.push('<?= __('user.local_store_clicks') ?>');
                data.push(channelData.click_localstore_commission);
                colors.push(colorMap[colorIndex++]);
            }
            if (channelData.click_external_commission > 0) {
                labels.push('<?= __('user.external_clicks') ?>');
                data.push(channelData.click_external_commission);
                colors.push(colorMap[colorIndex++]);
            }
            if (channelData.click_form_commission > 0) {
                labels.push('<?= __('user.form_clicks') ?>');
                data.push(channelData.click_form_commission);
                colors.push(colorMap[colorIndex++]);
            }
            if (channelData.sale_localstore_commission > 0) {
                labels.push('<?= __('user.local_store_sales') ?>');
                data.push(channelData.sale_localstore_commission);
                colors.push(colorMap[colorIndex++]);
            }
            if (channelData.order_external_commission > 0) {
                labels.push('<?= __('user.external_sales') ?>');
                data.push(channelData.order_external_commission);
                colors.push(colorMap[colorIndex++]);
            }
        } else {
            // VENDOR DATA ONLY
            if (channelData.vendor_click_localstore_commission_pay > 0) {
                labels.push('<?= __('user.vendor_store_clicks') ?>');
                data.push(channelData.vendor_click_localstore_commission_pay);
                colors.push(colorMap[colorIndex++]);
            }
            if (channelData.vendor_click_external_commission_pay > 0) {
                labels.push('<?= __('user.vendor_external_clicks') ?>');
                data.push(channelData.vendor_click_external_commission_pay);
                colors.push(colorMap[colorIndex++]);
            }
            if (channelData.vendor_sale_localstore_commission_pay > 0) {
                labels.push('<?= __('user.vendor_store_sales') ?>');
                data.push(channelData.vendor_sale_localstore_commission_pay);
                colors.push(colorMap[colorIndex++]);
            }
            if (channelData.vendor_order_external_commission_pay > 0) {
                labels.push('<?= __('user.vendor_external_sales') ?>');
                data.push(channelData.vendor_order_external_commission_pay);
                colors.push(colorMap[colorIndex++]);
            }
        }
    } else {
        // Show "No data available" message
        labels.push('<?= __('user.no_data_available') ?>');
        data.push(1);
        colors.push('#e5e7eb');
    }
    
    // Ensure we have at least one label and data point
    if (labels.length === 0) {
        labels.push('<?= __('user.no_data_available') ?>');
        data.push(1);
        colors.push('#e5e7eb');
    }
    
    const channelCtx = channelPerformanceElement.getContext('2d');
    const channelChart = new Chart(channelCtx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
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

// Shipping Progress Chart - Modern Horizontal Stacked Bar
const shippingProgressElement = document.getElementById('shippingProgressChart');
if (shippingProgressElement && isChartAvailable()) {
    const shippingProgressCtx = shippingProgressElement.getContext('2d');
    const shippingProgressChart = new Chart(shippingProgressCtx, {
        type: 'bar',
        data: {
            labels: ['<?= __('user.shipping_status_overview') ?>'],
            datasets: [{
                label: '<?= __('user.paid') ?>',
                data: [<?= $paid_shipping ?>],
                backgroundColor: '#10b981',
                borderColor: '#10b981',
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
            }, {
                label: '<?= __('user.requested') ?>',
                data: [<?= $requested_shipping ?>],
                backgroundColor: '#3b82f6',
                borderColor: '#3b82f6',
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
            }, {
                label: '<?= __('user.pending') ?>',
                data: [<?= $unpaid_shipping ?>],
                backgroundColor: '#f59e0b',
                borderColor: '#f59e0b',
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + context.parsed.x.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    beginAtZero: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                y: {
                    stacked: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
}
</script>