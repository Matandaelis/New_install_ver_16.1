<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-credit-card me-2"></i><?= __('admin.new_test_gateway_payment_confirmation') ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <strong><?= __('admin.new_test_gateway_amount') ?>:</strong> $<?= number_format($request_details['total'] ?? 0, 2) ?>
            </div>
            <div class="col-md-6">
                <strong><?= __('admin.new_test_gateway_request_date') ?>:</strong> <?= date('M d, Y H:i', strtotime($request_details['created_at'] ?? 'now')) ?>
            </div>
        </div>

        <?php if (isset($request_details['settings']) && !empty($request_details['settings'])): ?>
        <div class="mb-3">
            <h6 class="fw-semibold"><?= __('admin.new_test_gateway_user_details') ?>:</h6>
            <div class="bg-light p-3 rounded">
                <div class="row">
                    <div class="col-md-6">
                        <strong><?= __('admin.new_test_gateway_email') ?>:</strong> <?= $request_details['settings']['email'] ?? 'N/A' ?>
                    </div>
                    <div class="col-md-6">
                        <strong><?= __('admin.new_test_gateway_phone') ?>:</strong> <?= $request_details['settings']['phone'] ?? 'N/A' ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" onclick="processNewTestPayment(<?= $request_details['id'] ?? 0 ?>)">
                <i class="fas fa-check me-2"></i><?= __('admin.new_test_gateway_process_payment') ?>
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left me-2"></i><?= __('admin.new_test_gateway_go_back') ?>
            </button>
        </div>
    </div>
</div>

<script>
function processNewTestPayment(requestId) {
    // Simulate payment processing
    Swal.fire({
        title: '<?= __('admin.new_test_gateway_processing') ?>',
        text: '<?= __('admin.new_test_gateway_processing_message') ?>',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false
    });
    
    // Simulate API call
    setTimeout(() => {
        Swal.fire({
            title: '<?= __('admin.new_test_gateway_success') ?>',
            text: '<?= __('admin.new_test_gateway_success_message') ?>',
            icon: 'success'
        });
    }, 2000);
}
</script>
