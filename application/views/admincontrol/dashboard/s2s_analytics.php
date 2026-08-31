<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-server text-primary me-2"></i><?= __('admin.s2s_analytics_title') ?></h4>
            <p class="text-muted mb-0"><?= __('admin.s2s_analytics_desc') ?></p>
        </div>
        <a href="<?= base_url('integration/integration_tools') ?>" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-bullhorn me-1"></i><?= __('admin.nav_campaigns') ?>
        </a>
    </div>

    <!-- Overview Cards -->
    <div class="row g-3 mb-4">
        <!-- S2S Conversions -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                            <i class="fas fa-server text-primary"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= number_format($s2s_totals['total_orders']) ?></h3>
                            <small class="text-muted"><?= __('admin.s2s_conversions') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JS Pixel Conversions -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                            <i class="fas fa-code text-secondary"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= number_format($pixel_totals['total_orders']) ?></h3>
                            <small class="text-muted"><?= __('admin.pixel_conversions') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- S2S Revenue -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                            <i class="fas fa-dollar-sign text-success"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= $fun_c_format($s2s_totals['total_revenue']) ?></h3>
                            <small class="text-muted"><?= __('admin.s2s_revenue') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- S2S Commission -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                            <i class="fas fa-hand-holding-usd text-warning"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold"><?= $fun_c_format($s2s_totals['total_commission']) ?></h3>
                            <small class="text-muted"><?= __('admin.s2s_commission') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- S2S vs Pixel Comparison -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i><?= __('admin.s2s_vs_pixel') ?></h6>
                </div>
                <div class="card-body">
                    <canvas id="s2sVsPixelChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <!-- Campaign Adoption -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-toggle-on me-2 text-success"></i><?= __('admin.s2s_adoption') ?></h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <canvas id="s2sAdoptionChart" height="200"></canvas>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-success fw-bold mb-0"><?= $s2s_enabled_count ?></h4>
                            <small class="text-muted"><?= __('admin.s2s_enabled') ?></small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-secondary fw-bold mb-0"><?= $total_campaigns - $s2s_enabled_count ?></h4>
                            <small class="text-muted"><?= __('admin.pixel_only') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Top S2S Campaigns -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-trophy me-2 text-warning"></i><?= __('admin.top_s2s_campaigns') ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><?= __('admin.campaign') ?></th>
                                    <th class="text-center"><?= __('admin.orders') ?></th>
                                    <th class="text-end"><?= __('admin.s2s_revenue') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($s2s_top_campaigns)): ?>
                                <tr><td colspan="3" class="text-center text-muted py-4"><i class="fas fa-info-circle me-1"></i><?= __('admin.no_s2s_data_yet') ?></td></tr>
                                <?php else: ?>
                                <?php foreach($s2s_top_campaigns as $camp): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary me-1"><i class="fas fa-server"></i></span>
                                        <?= htmlspecialchars($camp['campaign_name'] ?? __('admin.campaign').' #'.$camp['ads_id']) ?>
                                    </td>
                                    <td class="text-center"><span class="badge bg-info"><?= number_format($camp['orders']) ?></span></td>
                                    <td class="text-end fw-bold"><?= $fun_c_format($camp['revenue']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent S2S Orders -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-clock me-2 text-info"></i><?= __('admin.recent_s2s_orders') ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><?= __('admin.order_id_col') ?></th>
                                    <th><?= __('admin.affiliate') ?></th>
                                    <th class="text-end"><?= __('admin.total') ?></th>
                                    <th><?= __('admin.date') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recent_s2s)): ?>
                                <tr><td colspan="4" class="text-center text-muted py-4"><i class="fas fa-info-circle me-1"></i><?= __('admin.no_s2s_data_yet') ?></td></tr>
                                <?php else: ?>
                                <?php foreach($recent_s2s as $order): ?>
                                <tr>
                                    <td><code class="small"><?= htmlspecialchars($order['order_id']) ?></code></td>
                                    <td>
                                        <?php if($order['firstname']): ?>
                                            <small><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">ID: <?= $order['user_id'] ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-bold"><?= $fun_c_format($order['total']) ?></td>
                                    <td><small class="text-muted"><?= date('M d, H:i', strtotime($order['created_at'])) ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="<?= base_url('assets/template/js/chart.umd.min.js') ?>?v=<?= av() ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare daily data for last 30 days
    var s2sDaily = <?= json_encode($s2s_daily) ?>;
    var pixelDaily = <?= json_encode($pixel_daily) ?>;
    
    // Build date range for last 30 days
    var dates = [];
    var s2sMap = {};
    var pixelMap = {};
    
    for(var i = 29; i >= 0; i--) {
        var d = new Date();
        d.setDate(d.getDate() - i);
        var key = d.toISOString().split('T')[0];
        dates.push(key);
        s2sMap[key] = 0;
        pixelMap[key] = 0;
    }
    
    s2sDaily.forEach(function(r) { s2sMap[r.date] = parseInt(r.orders); });
    pixelDaily.forEach(function(r) { pixelMap[r.date] = parseInt(r.orders); });
    
    var labels = dates.map(function(d) { return d.substring(5); }); // MM-DD
    var s2sData = dates.map(function(d) { return s2sMap[d] || 0; });
    var pixelData = dates.map(function(d) { return pixelMap[d] || 0; });

    // S2S vs Pixel chart
    new Chart(document.getElementById('s2sVsPixelChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: '<?= __('admin.s2s_source_s2s') ?>',
                    data: s2sData,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2
                },
                {
                    label: '<?= __('admin.s2s_source_pixel') ?>',
                    data: pixelData,
                    borderColor: '#6c757d',
                    backgroundColor: 'rgba(108,117,125,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { ticks: { maxTicksLimit: 10 } }
            }
        }
    });

    // Adoption donut chart
    var s2sEnabled = <?= (int)$s2s_enabled_count ?>;
    var pixelOnly = <?= (int)($total_campaigns - $s2s_enabled_count) ?>;
    
    new Chart(document.getElementById('s2sAdoptionChart'), {
        type: 'doughnut',
        data: {
            labels: ['<?= __('admin.s2s_enabled') ?>', '<?= __('admin.pixel_only') ?>'],
            datasets: [{
                data: [s2sEnabled, pixelOnly],
                backgroundColor: ['#198754', '#6c757d'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
