<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-chart-line me-2 text-primary"></i><?= __('user.vendor_analytics') ?></h4>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">30-Day Revenue</p>
                    <h3 class="fw-bold mb-0"><?= number_format($month_revenue, 2) ?></h3>
                    <?php $change = $prev_revenue > 0 ? round((($month_revenue - $prev_revenue) / $prev_revenue) * 100, 1) : 0; ?>
                    <small class="<?= $change >= 0 ? 'text-success' : 'text-danger' ?>">
                        <i class="fas fa-arrow-<?= $change >= 0 ? 'up' : 'down' ?> me-1"></i><?= abs($change) ?>%
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1"><?= __('admin.orders') ?></p>
                    <h3 class="fw-bold mb-0"><?= number_format($month_orders) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1"><?= __('user.avg_order_value') ?></p>
                    <h3 class="fw-bold mb-0"><?= number_format($avg_order_value, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1"><?= __('user.repeat_customers') ?></p>
                    <h3 class="fw-bold mb-0"><?= number_format($repeat_customers) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart + Top Products -->
    <div class="row g-3 mb-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold"><?= __('user.revenue_chart') ?></h6>
                </div>
                <div class="card-body">
                    <canvas id="vendorRevenueChart" height="280"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold"><i class="fas fa-trophy me-2 text-warning"></i><?= __('user.top_selling_products') ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th class="border-0">Product</th><th class="border-0">Sold</th><th class="border-0">Revenue</th></tr></thead>
                            <tbody>
                            <?php foreach ($top_products as $tp): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= !empty($tp['product_image']) ? base_url('assets/images/product/upload/thumb/'.$tp['product_image']) : base_url('assets/images/no-image.png') ?>" class="rounded" width="30" height="30" style="object-fit:cover">
                                        <span class="small text-truncate" style="max-width:150px"><?= $tp['product_name'] ?></span>
                                    </div>
                                </td>
                                <td><?= $tp['units'] ?></td>
                                <td class="fw-medium"><?= number_format($tp['revenue'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($top_products)): ?><tr><td colspan="3" class="text-center text-muted py-3">No data yet</td></tr><?php endif; ?>
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
    var data = <?= json_encode($daily_revenue) ?>;
    var ctx = document.getElementById('vendorRevenueChart');
    if (ctx && data.length) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(function(d){ return d.d; }),
                datasets: [{
                    label: 'Revenue',
                    data: data.map(function(d){ return parseFloat(d.revenue); }),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }
})();
</script>
