<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-money-bill-transfer me-2 text-primary"></i><?= __('admin.vendor_payouts') ?></h4>
    </div>

    <!-- Pending Payouts -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning bg-opacity-10 border-0">
            <h6 class="fw-bold mb-0"><i class="fas fa-clock me-2 text-warning"></i><?= __('admin.pending_payouts') ?> (<?= count($pending) ?>)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="border-0">Vendor</th><th class="border-0"><?= __('admin.payout_amount') ?></th><th class="border-0"><?= __('admin.payout_method') ?></th><th class="border-0">Note</th><th class="border-0"><?= __('admin.payout_requested') ?></th><th class="border-0">Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pending as $p):
                        $vendor = $this->db->get_where('users', ['id' => $p['vendor_id']])->row();
                    ?>
                        <tr>
                            <td class="fw-medium"><?= htmlspecialchars($vendor->username ?? 'Unknown') ?></td>
                            <td class="fw-bold"><?= number_format($p['amount'], 2) ?> <?= $p['currency'] ?></td>
                            <td><span class="badge bg-light text-dark"><?= ucfirst($p['method']) ?></span></td>
                            <td class="text-muted small"><?= htmlspecialchars($p['vendor_note'] ?? '') ?></td>
                            <td><?= date('M d, Y', strtotime($p['requested_at'])) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-success btn-payout-action" data-id="<?= $p['id'] ?>" data-action="approved"><i class="fas fa-check me-1"></i><?= __('admin.approve_payout') ?></button>
                                    <button class="btn btn-sm btn-danger btn-payout-action" data-id="<?= $p['id'] ?>" data-action="denied"><i class="fas fa-times me-1"></i><?= __('admin.deny_payout') ?></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pending)): ?><tr><td colspan="6" class="text-center text-muted py-4">No pending payouts</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payout History -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h6 class="fw-bold mb-0"><i class="fas fa-history me-2"></i><?= __('admin.payout_history') ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="border-0">Vendor</th><th class="border-0"><?= __('admin.payout_amount') ?></th><th class="border-0"><?= __('admin.payout_method') ?></th><th class="border-0"><?= __('admin.payout_status') ?></th><th class="border-0"><?= __('admin.admin_note') ?></th><th class="border-0"><?= __('admin.payout_processed') ?></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($history as $h):
                        $vendor = $this->db->get_where('users', ['id' => $h['vendor_id']])->row();
                        $status_colors = ['approved' => 'bg-success', 'denied' => 'bg-danger', 'paid' => 'bg-primary'];
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($vendor->username ?? 'Unknown') ?></td>
                            <td class="fw-bold"><?= number_format($h['amount'], 2) ?> <?= $h['currency'] ?></td>
                            <td><?= ucfirst($h['method']) ?></td>
                            <td><span class="badge <?= $status_colors[$h['status']] ?? 'bg-secondary' ?>"><?= ucfirst($h['status']) ?></span></td>
                            <td class="text-muted small"><?= htmlspecialchars($h['admin_note'] ?? '') ?></td>
                            <td><?= $h['processed_at'] ? date('M d, Y', strtotime($h['processed_at'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($history)): ?><tr><td colspan="6" class="text-center text-muted py-4">No payout history</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.btn-payout-action', function(){
    var btn = $(this);
    var note = prompt('<?= addslashes(__('admin.admin_note')) ?>:');
    btn.prop('disabled', true);
    $.post(window.affiliatePro.base_url + 'admincontrol/payout_action', {
        payout_id: btn.data('id'),
        action: btn.data('action'),
        admin_note: note || ''
    }, function(r){
        if (r.success) location.reload();
        else { toastr.error('Failed'); btn.prop('disabled', false); }
    }, 'json');
});
</script>
