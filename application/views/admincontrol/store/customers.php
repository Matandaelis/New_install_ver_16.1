<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-users me-2 text-primary"></i><?= __('admin.customer_insights') ?></h4>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px"><i class="fas fa-users text-primary"></i></div>
                    <h4 class="fw-bold"><?= number_format($total_customers) ?></h4>
                    <small class="text-muted"><?= __('admin.total_customers') ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px"><i class="fas fa-user-plus text-success"></i></div>
                    <h4 class="fw-bold"><?= number_format($new_customers_30d) ?></h4>
                    <small class="text-muted"><?= __('admin.new_customers') ?> (30d)</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px"><i class="fas fa-redo text-info"></i></div>
                    <h4 class="fw-bold"><?= number_format($repeat_customers) ?></h4>
                    <small class="text-muted"><?= __('admin.repeat_customers') ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px"><i class="fas fa-gem text-warning"></i></div>
                    <?php $avg_ltv = count($customers) > 0 ? array_sum(array_column($customers, 'lifetime_value')) / count($customers) : 0; ?>
                    <h4 class="fw-bold"><?= number_format($avg_ltv, 2) ?></h4>
                    <small class="text-muted">Avg <?= __('admin.lifetime_value') ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h6 class="fw-bold mb-0"><i class="fas fa-trophy me-2 text-warning"></i><?= __('admin.top_customers') ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="border-0">#</th><th class="border-0">Customer</th><th class="border-0">Email</th><th class="border-0"><?= __('admin.orders') ?></th><th class="border-0"><?= __('admin.lifetime_value') ?></th><th class="border-0">Last Order</th><th class="border-0">Joined</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($customers as $i => $c): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="fw-medium"><?= htmlspecialchars($c['full_name'] ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><span class="badge bg-primary"><?= $c['order_count'] ?></span></td>
                            <td class="fw-bold"><?= number_format($c['lifetime_value'], 2) ?></td>
                            <td><?= $c['last_order'] ? date('M d, Y', strtotime($c['last_order'])) : '-' ?></td>
                            <td><?= date('M d, Y', strtotime($c['joined'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($customers)): ?><tr><td colspan="7" class="text-center text-muted py-4">No customers found</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
