<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-money-bill-transfer me-2 text-primary"></i><?= __('user.payout_history') ?></h4>
    </div>

    <!-- Balance + Request -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <p class="small mb-1 opacity-75"><?= __('user.available_balance') ?></p>
                    <h2 class="fw-bold mb-0"><?= number_format($available_balance, 2) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-paper-plane me-2"></i><?= __('user.request_payout') ?></h6>
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small"><?= __('user.payout_amount') ?></label>
                            <input type="number" class="form-control" id="payout_amount" min="<?= $min_payout ?>" step="0.01" placeholder="<?= $min_payout ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small"><?= __('user.payout_method') ?></label>
                            <select class="form-select" id="payout_method">
                                <?php foreach ($payout_methods as $m): ?>
                                <option value="<?= trim($m) ?>"><?= ucfirst(trim($m)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small"><?= __('user.vendor_note') ?></label>
                            <input type="text" class="form-control" id="payout_note" placeholder="<?= __('user.vendor_note') ?>">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="btn-request-payout"><?= __('user.request_payout') ?></button>
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">Min: <?= number_format($min_payout, 2) ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout History -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="border-0"><?= __('user.payout_amount') ?></th><th class="border-0"><?= __('user.payout_method') ?></th><th class="border-0"><?= __('user.payout_status') ?></th><th class="border-0">Note</th><th class="border-0">Date</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $status_colors = ['pending' => 'bg-warning text-dark', 'approved' => 'bg-info', 'denied' => 'bg-danger', 'paid' => 'bg-success'];
                    foreach ($payouts as $p): ?>
                    <tr>
                        <td class="fw-bold"><?= number_format($p['amount'], 2) ?> <?= $p['currency'] ?></td>
                        <td><?= ucfirst($p['method']) ?></td>
                        <td><span class="badge <?= $status_colors[$p['status']] ?? 'bg-secondary' ?>"><?= ucfirst($p['status']) ?></span></td>
                        <td class="text-muted small">
                            <?= htmlspecialchars($p['vendor_note'] ?? '') ?>
                            <?php if(!empty($p['admin_note'])): ?><br><strong>Admin:</strong> <?= htmlspecialchars($p['admin_note']) ?><?php endif; ?>
                        </td>
                        <td><?= date('M d, Y', strtotime($p['requested_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($payouts)): ?><tr><td colspan="5" class="text-center text-muted py-4">No payouts yet</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$('#btn-request-payout').on('click', function(){
    var btn = $(this);
    btn.prop('disabled', true);
    $.post(window.affiliatePro.base_url + 'usercontrol/request_payout', {
        amount: $('#payout_amount').val(),
        method: $('#payout_method').val(),
        note: $('#payout_note').val()
    }, function(r){
        btn.prop('disabled', false);
        if (r.success) { toastr.success(r.message); location.reload(); }
        else toastr.error(r.message || 'Failed');
    }, 'json');
});
</script>
