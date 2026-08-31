<div class="container-fluid px-4 pb-4">
    <?php get_instance()->load->view('admincontrol/store/_store_nav'); ?>
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-chart-line me-2 text-primary"></i><?= __('admin.store_analytics') ?></h4>
            <p class="text-muted mb-0"><?= __('admin.today') ?>: <?= date('M d, Y') ?></p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1"><?= __('admin.today') ?> <?= __('admin.revenue') ?></p>
                            <h3 class="fw-bold mb-0"><?= number_format($today_revenue, 2) ?></h3>
                            <small class="text-muted"><?= $today_orders ?> <?= __('admin.orders') ?></small>
                        </div>
                        <div class="rounded-3 bg-primary bg-opacity-10 p-3"><i class="fas fa-dollar-sign text-primary fa-lg"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">30-<?= __('admin.day') ?> <?= __('admin.revenue') ?></p>
                            <h3 class="fw-bold mb-0"><?= number_format($month_revenue, 2) ?></h3>
                            <?php $rev_change = $prev_month_revenue > 0 ? round((($month_revenue - $prev_month_revenue) / $prev_month_revenue) * 100, 1) : 0; ?>
                            <small class="<?= $rev_change >= 0 ? 'text-success' : 'text-danger' ?>">
                                <i class="fas fa-arrow-<?= $rev_change >= 0 ? 'up' : 'down' ?> me-1"></i><?= abs($rev_change) ?>% <?= __('admin.vs_previous') ?>
                            </small>
                        </div>
                        <div class="rounded-3 bg-success bg-opacity-10 p-3"><i class="fas fa-chart-line text-success fa-lg"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1"><?= __('admin.avg_order_value') ?></p>
                            <h3 class="fw-bold mb-0"><?= number_format($avg_order_value, 2) ?></h3>
                            <small class="text-muted"><?= $month_orders ?> <?= __('admin.orders') ?></small>
                        </div>
                        <div class="rounded-3 bg-warning bg-opacity-10 p-3"><i class="fas fa-receipt text-warning fa-lg"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1"><?= __('admin.abandoned_cart_stats') ?></p>
                            <h3 class="fw-bold mb-0"><?= $cart_stats['rate'] ?>%</h3>
                            <small class="text-muted"><?= $cart_stats['recovered'] ?>/<?= $cart_stats['total'] ?> <?= __('admin.carts_recovered') ?></small>
                        </div>
                        <div class="rounded-3 bg-info bg-opacity-10 p-3"><i class="fas fa-cart-arrow-down text-info fa-lg"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold"><?= __('admin.total_revenue') ?> (30 <?= __('admin.day') ?>)</h6>
                </div>
                <div class="card-body">
                    <div class="store-chart-revenue">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold"><?= __('admin.revenue_by_payment') ?></h6>
                </div>
                <div class="card-body">
                    <div class="store-chart-doughnut">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products & Top Vendors -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold"><i class="fas fa-trophy me-2 text-warning"></i><?= __('admin.top_products') ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th class="border-0">#</th><th class="border-0"><?= __('admin.product_name') ?></th><th class="border-0"><?= __('admin.units_sold') ?></th><th class="border-0"><?= __('admin.revenue') ?></th></tr></thead>
                            <tbody>
                            <?php foreach ($top_products as $i => $tp): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <span class="text-truncate d-block store-product-name"><?= htmlspecialchars($tp['product_name']) ?></span>
                                    </td>
                                    <td><?= number_format($tp['units']) ?></td>
                                    <td class="fw-medium"><?= number_format($tp['revenue'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($top_products)): ?><tr><td colspan="4" class="text-center text-muted py-4">No data</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold"><i class="fas fa-store me-2 text-info"></i><?= __('admin.top_vendors') ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th class="border-0">#</th><th class="border-0">Vendor</th><th class="border-0"><?= __('admin.orders') ?></th><th class="border-0"><?= __('admin.revenue') ?></th></tr></thead>
                            <tbody>
                            <?php foreach ($top_vendors as $i => $tv): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($tv['username']) ?></td>
                                    <td><?= number_format($tv['orders']) ?></td>
                                    <td class="fw-medium"><?= number_format($tv['revenue'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($top_vendors)): ?><tr><td colspan="4" class="text-center text-muted py-4">No data</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/template/js/chart.umd.min.js') ?>?v=<?= av() ?>"></script>
<script>
(function(){
    var dailyData = <?= json_encode($daily_revenue) ?>;
    var labels = dailyData.map(function(d){ return d.d; });
    var revData = dailyData.map(function(d){ return parseFloat(d.revenue); });
    var ordData = dailyData.map(function(d){ return parseInt(d.orders); });

    var ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '<?= addslashes(__('admin.revenue')) ?>',
                    data: revData,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3
                }, {
                    label: '<?= addslashes(__('admin.orders')) ?>',
                    data: ordData,
                    borderColor: '#198754',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 3,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: '<?= addslashes(__('admin.revenue')) ?>' } },
                    y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, title: { display: true, text: '<?= addslashes(__('admin.orders')) ?>' } }
                }
            }
        });
    }

    var paymentData = <?= json_encode($revenue_by_payment) ?>;
    var pCtx = document.getElementById('paymentChart');
    if (pCtx && paymentData.length) {
        var colors = ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#0dcaf0','#fd7e14','#20c997'];
        new Chart(pCtx, {
            type: 'doughnut',
            data: {
                labels: paymentData.map(function(d){ return d.payment_method || 'N/A'; }),
                datasets: [{
                    data: paymentData.map(function(d){ return parseFloat(d.revenue); }),
                    backgroundColor: colors.slice(0, paymentData.length)
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }
})();
</script>
