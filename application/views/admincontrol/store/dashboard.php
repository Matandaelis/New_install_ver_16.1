<?php
$db =& get_instance();
$userdetails = get_object_vars($db->user_info());
$store_setting = $db->Product_model->getSettings('store');
$products = $db->Product_model;
$notifications_count = $products->getnotificationnew_count('admin',null);
?>

<!-- Morris.js Chart Library -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/morris/morris.css') ?>">
<script src="<?= base_url('assets/plugins/raphael/raphael-min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/morris/morris.min.js') ?>"></script>

<div class="container-fluid px-4 pb-4">

    <?php $this->load->view('admincontrol/store/_store_nav'); ?>

    <div>

    <!-- ══════════════════════════════════════════════════════════
         KPI CARDS ROW
    ══════════════════════════════════════════════════════════ -->
    <div class="row g-3 mb-4">
        <!-- Total Balance -->
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100 store-kpi-primary">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                        <i class="bi bi-wallet2 text-primary fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-semibold small"><?= __('admin.total_balance') ?></div>
                        <div class="fw-bold fs-4 text-primary ajax-total_balance"><?= $totals['full_total_balance'] ?></div>
                        <div class="text-success small"><i class="bi bi-arrow-up me-1"></i><?= __('admin.all_time') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100 store-kpi-success">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-success bg-opacity-10">
                        <i class="bi bi-graph-up text-success fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-semibold small"><?= __('admin.total_sales') ?></div>
                        <div class="fw-bold fs-4 text-success ajax-total_balance"><?= $totals['total_sale_balance'] ?></div>
                        <div class="text-success small"><i class="bi bi-arrow-up me-1"></i><?= __('admin.revenue') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clicks & Commission -->
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100 store-kpi-warning">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                        <i class="bi bi-cursor text-warning fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-semibold small"><?= __('admin.clicks_commission') ?></div>
                        <div class="fw-bold fs-4 text-warning ajax-all_clicks_comm"><?= $totals['full_all_clicks_comm'] ?></div>
                        <div class="text-muted small"><i class="bi bi-info-circle me-1"></i><?= __('admin.clicks_and_earnings') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100 store-kpi-danger">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-danger bg-opacity-10">
                        <i class="bi bi-clock-history text-danger fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-semibold small"><?= __('admin.pending_orders') ?></div>
                        <a href="<?= base_url('admincontrol/listorders') ?>" class="text-decoration-none">
                            <div class="fw-bold fs-4 text-danger ajax-hold_orders"><?= $totals['full_local_store_hold_orders'] ?></div>
                        </a>
                        <div class="text-danger small"><i class="bi bi-exclamation-circle me-1"></i><?= __('admin.needs_attention') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         STORE MANAGEMENT HUB  (the "game changer" section)
    ══════════════════════════════════════════════════════════ -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i><?= __('admin.store_management_hub') ?>
            </h5>
            <small class="text-muted"><?= __('admin.store_hub_desc') ?></small>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">

                <!-- Store Settings -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/store_setting') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--indigo">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--indigo">
                                <i class="bi bi-gear-fill hub-card-icon-fs hub-card-text-indigo"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-indigo"><?= __('admin.store_settings') ?></div>
                            <div class="text-muted hub-card-label"><?= __('admin.store_settings_sub') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Theme & Pages -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/store_setting#theme_section') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--pink">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--pink">
                                <i class="bi bi-palette-fill hub-card-icon-fs hub-card-text-pink"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-pink"><?= __('admin.theme_pages') ?></div>
                            <div class="text-muted hub-card-label"><?= __('admin.theme_pages_sub') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Shipping & Tax -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/store_setting#shipping_setting') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--green">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--green">
                                <i class="bi bi-truck hub-card-icon-fs hub-card-text-green"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-green"><?= __('admin.shipping_tax') ?></div>
                            <div class="text-muted hub-card-label"><?= $CurrencySymbol . c_format($local_store_shipping_cost) ?> / <?= $CurrencySymbol . c_format($local_store_tax_cost) ?></div>
                        </div>
                    </a>
                </div>

                <!-- Payment Setup -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/payment_gateway') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--blue">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--blue">
                                <i class="bi bi-credit-card-fill hub-card-icon-fs hub-card-text-blue"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-blue"><?= __('admin.payment_setup') ?></div>
                            <div class="text-muted hub-card-label"><?= $payment_gateway_count ?> <?= __('admin.gateways') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Products -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/listproduct#product_tab') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--amber">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--amber">
                                <i class="bi bi-box-seam-fill hub-card-icon-fs hub-card-text-amber"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-amber"><?= __('admin.total_products') ?></div>
                            <div class="text-muted hub-card-label"><?= (int)$product_count ?> <?= __('admin.items') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Orders -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/listorders') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--red">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--red">
                                <i class="bi bi-bag-check-fill hub-card-icon-fs hub-card-text-red"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-red"><?= __('admin.orders') ?></div>
                            <div class="text-muted hub-card-label"><?= $ordercount ?> <?= __('admin.total') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Categories -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/store_category') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--sky">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--sky">
                                <i class="bi bi-tags-fill hub-card-icon-fs hub-card-text-sky"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-sky"><?= __('admin.categories') ?></div>
                            <div class="text-muted hub-card-label"><?= $category_count ?> <?= __('admin.total') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Coupons -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/listproduct#product_coupons_tab') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--purple">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--purple">
                                <i class="bi bi-ticket-perforated-fill hub-card-icon-fs hub-card-text-purple"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-purple"><?= __('admin.coupons') ?></div>
                            <div class="text-muted hub-card-label"><?= $coupon_count ?> <?= __('admin.active') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Clients -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/listclients') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--emerald">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--emerald">
                                <i class="bi bi-people-fill hub-card-icon-fs hub-card-text-emerald"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-emerald"><?= __('admin.clients_guests') ?></div>
                            <div class="text-muted hub-card-label"><?= !empty($client_count) ? count($client_count) : '0' ?> / <?= !empty($guest_count) ? count($guest_count) : '0' ?></div>
                        </div>
                    </a>
                </div>

                <!-- Vendor Requests -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/listproduct/reviews') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--orange">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--orange">
                                <i class="bi bi-star-fill hub-card-icon-fs hub-card-text-orange"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-orange"><?= __('admin.store_v_requests') ?></div>
                            <div class="text-muted hub-card-label"><?= __('admin.reviews') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Forms -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/listproduct#form_tab') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--cyan">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--cyan">
                                <i class="bi bi-file-earmark-text-fill hub-card-icon-fs hub-card-text-cyan"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-cyan"><?= __('admin.total_forms') ?></div>
                            <div class="text-muted hub-card-label"><?= $form_count ?> <?= __('admin.total') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Analytics -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/store_analytics') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--fuchsia">
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--fuchsia">
                                <i class="bi bi-bar-chart-fill hub-card-icon-fs hub-card-text-fuchsia"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-fuchsia"><?= __('admin.store_analytics') ?></div>
                            <div class="text-muted hub-card-label"><?= __('admin.reports') ?></div>
                        </div>
                    </a>
                </div>

                <!-- Theme API Reference -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <a href="<?= base_url('admincontrol/store_theme_api_doc') ?>" class="text-decoration-none">
                        <div class="card border h-100 text-center p-3 hub-card hub-card--sky position-relative">
                            <span class="hub-card-api-badge">API</span>
                            <div class="mx-auto mb-2 rounded-3 p-2 hub-card-icon hub-card-icon--sky">
                                <i class="bi bi-code-slash hub-card-icon-fs hub-card-text-sky2"></i>
                            </div>
                            <div class="fw-bold small hub-card-text-sky2"><?= __('admin.nav_store_theme_api') ?></div>
                            <div class="text-muted hub-card-label"><?= __('admin.store_theme_api_doc_subtitle_short') ?></div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         SALES FUNNELS BANNER
    ══════════════════════════════════════════════════════════ -->
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle p-2" style="background:rgba(255,255,255,.2);">
                            <i class="fas fa-bolt text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="text-white fw-bold mb-0"><?= __('admin.sales_funnels') ?></h5>
                            <p class="text-white mb-0" style="opacity:.8;font-size:.83rem;"><?= __('admin.set_exclusive_funnel_prices') ?> &amp; <?= __('admin.manage_sales_funnels') ?></p>
                        </div>
                    </div>
                    <p class="text-white mb-0 small" style="opacity:.75;">
                        <i class="fas fa-info-circle me-1"></i><?= __('admin.funnel_desc') ?>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="d-flex flex-column gap-2">
                        <a href="<?= base_url('admincontrol/sales_funnels') ?>" class="btn btn-light btn-sm fw-bold">
                            <i class="fas fa-layer-group me-2"></i><?= __('admin.manage_sales_funnels') ?>
                        </a>
                        <a href="<?= base_url('admincontrol/funnel_pricing') ?>" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-tag me-2"></i><?= __('admin.funnel_pricing') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         CHARTS ROW
    ══════════════════════════════════════════════════════════ -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-graph-up me-2 text-primary"></i><?= __('admin.sales_analytics') ?></h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm yearSelection" style="width:auto;">
                            <?php foreach($years as $year): ?>
                                <option value="<?= $year ?>" <?= $year == date('Y') ? 'selected' : '' ?>>
                                    <?= $year == 'All' ? __('admin.all_years') : $year ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-outline-primary btn-sm" onclick="renderStackedBarChart()">
                            <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="chartContainer" style="height:300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-globe me-2 text-info"></i><?= __('admin.clients_world_map') ?></h5>
                </div>
                <div class="card-body p-3">
                    <div class="world-map-users" style="height:300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         PAYMENT GATEWAYS
    ══════════════════════════════════════════════════════════ -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0"><i class="bi bi-credit-card-2-front me-2 text-success"></i><?= __('admin.supported_payment_gateways') ?></h5>
            <a href="<?= base_url('admincontrol/payment_gateway') ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-gear me-1"></i><?= __('admin.configure') ?>
            </a>
        </div>
        <div class="card-body p-3">
            <div class="row g-2">
                <?php
                $payment_gateways = [
                    ['name'=>'PayPal',          'image'=>'paypal.png'],
                    ['name'=>'Stripe',          'image'=>'stripe.png'],
                    ['name'=>'Razorpay',        'image'=>'razorpay.png'],
                    ['name'=>'Paystack',        'image'=>'paystack.png'],
                    ['name'=>'Flutterwave',     'image'=>'flutterwave.png'],
                    ['name'=>'Paytm',           'image'=>'paytm.png'],
                    ['name'=>'Skrill',          'image'=>'skrill.png'],
                    ['name'=>'YooKassa',        'image'=>'yookassa.png'],
                    ['name'=>'Xendit',          'image'=>'xendit.png'],
                    ['name'=>'Opay',            'image'=>'opay.png'],
                    ['name'=>'Bank Transfer',   'image'=>'bank-transfer.png'],
                    ['name'=>'Cash on Delivery','image'=>'cod.png'],
                ];
                foreach($payment_gateways as $gw): ?>
                <div class="col-xl-1 col-lg-2 col-md-2 col-3">
                    <div class="card bg-light border-0 text-center p-2 h-100">
                        <img src="<?= base_url('assets/payment_gateway/' . $gw['image']) ?>" alt="<?= $gw['name'] ?>" style="max-height:32px;max-width:100%;object-fit:contain;margin:auto;">
                        <div class="text-muted mt-1" style="font-size:.65rem;"><?= $gw['name'] ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="alert alert-info border-0 bg-info bg-opacity-10 mt-3 mb-0 py-2">
                <i class="bi bi-info-circle me-2"></i>
                <strong><?= __('admin.payment_gateway_note') ?></strong>
                <?= __('admin.payment_gateway_desc') ?>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         RECENT ORDERS
    ══════════════════════════════════════════════════════════ -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i><?= __('admin.recent_orders') ?></h5>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" onclick="getPage('<?= base_url("admincontrol/store_dashboard_order_list?page=1") ?>')">
                    <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>
                </button>
                <a href="<?= base_url('admincontrol/listorders') ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-eye me-1"></i><?= __('admin.view_all_orders') ?>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="store-dashboard-orders" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold border-0 ps-4"><?= __('admin.order_id') ?></th>
                            <th class="fw-bold border-0 text-end"><?= __('admin.amount') ?></th>
                            <th class="fw-bold border-0 text-center"><?= __('admin.status') ?></th>
                            <th class="fw-bold border-0"><?= __('admin.payment_method') ?></th>
                            <th class="fw-bold border-0"><?= __('admin.customer_ip') ?></th>
                            <th class="fw-bold border-0"><?= __('admin.transaction_id') ?></th>
                            <th class="fw-bold border-0 text-center"><?= __('admin.date') ?></th>
                            <th class="fw-bold border-0 text-center pe-4"><?= __('admin.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="spinner-border text-primary mb-3" role="status"></div>
                                <h6 class="text-muted fw-bold"><?= __("admin.loading_orders_data") ?></h6>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-2">
                <div class="pagination-summary text-muted small"></div>
                <div class="pagination-td"></div>
            </div>
            <div class="text-center py-5 d-none empty-div">
                <i class="bi bi-inbox text-muted" style="font-size:3rem;"></i>
                <h4 class="text-muted mt-3"><?= __('admin.no_orders_found') ?></h4>
                <a href="<?= base_url('store') ?>" target="_blank" class="btn btn-primary mt-2">
                    <i class="bi bi-shop me-2"></i><?= __('admin.visit_store') ?>
                </a>
            </div>
        </div>
    </div>

    </div><!-- /p-4 -->
</div><!-- /container-fluid -->

<style>
.hub-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.1) !important; }
.hub-card { border-radius: 12px !important; cursor: pointer; }
</style>

<script type="text/javascript">
function getPage(urlOrPage) {
    let url;
    if (typeof urlOrPage === 'number' || (typeof urlOrPage === 'string' && /^\d+$/.test(urlOrPage))) {
        url = '<?= base_url("admincontrol/store_dashboard_order_list?page=") ?>' + urlOrPage;
    } else {
        url = urlOrPage;
    }
    const loadingRow = `<tr><td colspan="8" class="text-center py-5"><div class="spinner-border text-primary mb-3" role="status"></div><h6 class="text-muted fw-bold"><?= __("admin.loading_orders_data") ?></h6></td></tr>`;
    $("#store-dashboard-orders tbody").html(loadingRow);
    $("#store-dashboard-orders").show();
    $(".empty-div").addClass("d-none");
    $.ajax({
        global: false,
        url: url, type: 'POST', dataType: 'json',
        data: $("#filter-form").serialize(),
        success: function(json) {
            if (json['view']) {
                $("#store-dashboard-orders tbody").html(json['view']);
                $("#store-dashboard-orders").show();
                $(".empty-div").addClass("d-none");
                $(".pagination-td").html(json['pagination'] || '');
                if (json['pagination_summary']) {
                    $(".pagination-summary").html(json['pagination_summary']);
                } else if (json['total']) {
                    $(".pagination-summary").html('<?= __('admin.showing') ?> ' + json['total'] + ' <?= __('admin.orders') ?>');
                }
            } else {
                $(".empty-div").removeClass("d-none");
                $("#store-dashboard-orders").hide();
                $(".pagination-td").html('');
                $(".pagination-summary").html('');
            }
        },
        error: function() {
            $("#store-dashboard-orders tbody").html(`<tr><td colspan="8" class="text-center py-5"><i class="bi bi-exclamation-triangle fs-1 text-danger mb-3"></i><h5><?= __('admin.error_loading_orders') ?></h5><button class="btn btn-primary btn-sm" onclick="getPage('<?= base_url("admincontrol/store_dashboard_order_list?page=1") ?>')"><i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.retry') ?></button></td></tr>`);
        }
    });
}
$(document).ready(function() {
    getPage('<?= base_url("admincontrol/store_dashboard_order_list?page=1") ?>');
    setInterval(function() { getPage('<?= base_url("admincontrol/store_dashboard_order_list?page=1") ?>'); }, 300000);
});
</script>

<script>
function renderStackedBarChart(group) {
    var group = group ? group : 'month';
    var selectedyear = $('.yearSelection').val();
    $.ajax({
        global: false,
        type: 'POST', dataType: 'json',
        data: {renderChart: group, selectedyear: selectedyear},
        success: function(json) { loadChartData(json); }
    });
}
function toArray(myObj) {
    return $.map(myObj, function(value) { return [value]; });
}
$(document).ready(function() { renderStackedBarChart(); });
function loadChartData(json) {
    var saleHigh        = toArray(json['series_new']['sale']);
    var orderHigh       = toArray(json['series_new']['order']);
    var commissionsHigh = toArray(json['series_new']['commissions']);
    var months = ['','<?= substr(__('admin.january'),0,3) ?>','<?= substr(__('admin.february'),0,3) ?>','<?= substr(__('admin.march'),0,3) ?>','<?= substr(__('admin.april'),0,3) ?>','<?= substr(__('admin.may'),0,3) ?>','<?= substr(__('admin.june'),0,3) ?>','<?= substr(__('admin.july'),0,3) ?>','<?= substr(__('admin.august'),0,3) ?>','<?= substr(__('admin.september'),0,3) ?>','<?= substr(__('admin.october'),0,3) ?>','<?= substr(__('admin.november'),0,3) ?>','<?= substr(__('admin.december'),0,3) ?>'];
    var dataPoints = [];
    for (var j = 1; j <= 12; j++) {
        dataPoints.push({y:j, a:saleHigh[j], b:orderHigh[j], c:commissionsHigh[j]});
    }
    Morris.Line({
        element: 'chartContainer',
        lineColors: ['#fc836e','#3d5674','#764ba2'],
        data: dataPoints,
        parseTime: false,
        xkey: 'y',
        ykeys: ['a','b','c'],
        xLabelFormat: function(x) { return months[parseInt(x.src.y)]; },
        labels: ['<?= __("admin.sales") ?> (<?=$CurrencySymbol?>)', '<?= __("admin.orders") ?>', '<?= __("admin.commission") ?> (<?=$CurrencySymbol?>)']
    });
}
</script>

<script src="<?= base_url('assets/plugins/jmap/jquery-jvectormap-2.0.3.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jmap/jquery-jvectormap-world-mill.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/jmap/css.css') ?>">
<script>
function load_userworldmap(_data) {
    $('.world-map-users').html('<div class="map"><div id="world-map-users" class="map-content"></div></div>');
    var data = {};
    $.each(_data, function(i,j) { data[j['code']] = j['total']; });
    $('.world-map-users #world-map-users').vectorMap({
        map: 'world_mill', zoomButtons:1, zoomOnScroll:false, panOnDrag:1,
        backgroundColor: 'transparent',
        onRegionTipShow: function(e, el, code) {
            el.html(el.html() + (data[code] ? ': <small>'+data[code]+'</small>' : ''));
        },
        series: { regions: [{ values:data, scale:['#007BFF'], normalizeFunction:'polynomial' }] },
        regionStyle: { initial:{ fill:'#e9ecef' }, hover:{ 'fill-opacity':0.8 } },
        markers: false
    });
}
load_userworldmap(<?= json_encode($userworldmap) ?>);
</script>
