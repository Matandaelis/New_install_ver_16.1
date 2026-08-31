<?php
$settings = json_decode($request['settings'], true);
$user_data = isset($settings) ? $settings : [];
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-university me-2"></i><?= __('admin.bank_transfer_payment_processing') ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong><?= __('admin.amount') ?>:</strong> <?= c_format($request['total']) ?>
            </div>
            <div class="col-md-6">
                <strong><?= __('admin.request_date') ?>:</strong> <?= date('M d, Y H:i', strtotime($request['created_at'])) ?>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <strong><?= __('admin.status') ?>:</strong> <?= withdrwal_status($request['status']) ?>
            </div>
            <div class="col-md-6">
                <strong><?= __('admin.payment_method') ?>:</strong> <span class="badge bg-success"><?= $request['prefer_method'] ?></span>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="fw-semibold"><?= __('admin.bank_account_details') ?>:</h6>
            <div class="bg-light p-3 rounded">
                <div class="row">
                    <div class="col-md-6">
                        <strong><?= __('admin.account_holder_name') ?>:</strong> <?= isset($user_data['account_holder_name']) ? $user_data['account_holder_name'] : '-' ?>
                    </div>
                    <div class="col-md-6">
                        <strong><?= __('admin.bank_name') ?>:</strong> <?= isset($user_data['bank_name']) ? $user_data['bank_name'] : '-' ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong><?= __('admin.account_number') ?>:</strong> <?= isset($user_data['account_number']) ? $user_data['account_number'] : '-' ?>
                    </div>
                    <div class="col-md-6">
                        <strong><?= __('admin.routing_number') ?>:</strong> <?= isset($user_data['routing_number']) ? $user_data['routing_number'] : '-' ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong><?= __('admin.swift_code') ?>:</strong> <?= isset($user_data['swift_code']) ? $user_data['swift_code'] : '-' ?>
                    </div>
                    <div class="col-md-6">
                        <strong><?= __('admin.bank_country') ?>:</strong> <?= isset($user_data['bank_country']) ? $user_data['bank_country'] : '-' ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i><?= __('admin.bank_transfer_instructions') ?>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold"><?= __('admin.transaction_reference') ?>:</label>
            <input type="text" class="form-control" id="transaction_ref" placeholder="<?= __('admin.enter_transaction_reference') ?>">
            <small class="text-muted"><?= __('admin.transaction_reference_help') ?></small>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <button type="button" class="btn btn-success btn-lg" onclick="processBankTransfer(<?= $request['id'] ?>, 'complete')">
                <i class="fas fa-check me-2"></i><?= __('admin.confirm_transfer_completed') ?>
            </button>
            <button type="button" class="btn btn-warning btn-lg" onclick="processBankTransfer(<?= $request['id'] ?>, 'processing')">
                <i class="fas fa-clock me-2"></i><?= __('admin.mark_as_processing') ?>
            </button>
            <button type="button" class="btn btn-danger" onclick="processBankTransfer(<?= $request['id'] ?>, 'failed')">
                <i class="fas fa-times me-2"></i><?= __('admin.mark_as_failed') ?>
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left me-2"></i><?= __('admin.go_back') ?>
            </button>
        </div>
    </div>
</div>

<script>
function processBankTransfer(requestId, action) {
    let title, text, status, comment;
    const transactionRef = document.getElementById('transaction_ref').value;
    
    switch(action) {
        case 'complete':
            title = '<?= __('admin.confirm_bank_transfer_complete') ?>';
            text = '<?= __('admin.confirm_bank_transfer_complete_text') ?>';
            status = 1;
            comment = 'Bank transfer completed' + (transactionRef ? ' - Ref: ' + transactionRef : '');
            break;
        case 'processing':
            title = '<?= __('admin.confirm_bank_transfer_processing') ?>';
            text = '<?= __('admin.confirm_bank_transfer_processing_text') ?>';
            status = 7;
            comment = 'Bank transfer is being processed' + (transactionRef ? ' - Ref: ' + transactionRef : '');
            break;
        case 'failed':
            title = '<?= __('admin.confirm_bank_transfer_failed') ?>';
            text = '<?= __('admin.confirm_bank_transfer_failed_text') ?>';
            status = 5;
            comment = 'Bank transfer failed' + (transactionRef ? ' - Ref: ' + transactionRef : '');
            break;
    }
    
    if (action === 'complete' && !transactionRef.trim()) {
        Swal.fire({
            title: '<?= __('admin.transaction_reference_required') ?>',
            text: '<?= __('admin.transaction_reference_required_text') ?>',
            icon: 'warning',
            confirmButtonText: '<?= __('admin.ok') ?>'
        });
        return;
    }
    
    Swal.fire({
        title: title,
        text: text,
        icon: action === 'complete' ? 'success' : action === 'processing' ? 'info' : 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'complete' ? '#28a745' : action === 'processing' ? '#ffc107' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<?= __('admin.yes_confirm') ?>',
        cancelButtonText: '<?= __('admin.cancel') ?>',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: '<?= base_url('admincontrol/wallet_requests_details/' . $request['id']) ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    status: status,
                    comment: comment,
                    transaction_id: transactionRef
                }
            }).then(response => {
                if (!response.success) {
                    throw new Error(response.message || '<?= __('admin.something_went_wrong') ?>');
                }
                return response;
            }).catch(error => {
                Swal.showValidationMessage('<?= __('admin.request_failed') ?>: ' + error.message);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '<?= __('admin.success') ?>',
                text: '<?= __('admin.status_updated_successfully') ?>',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        }
    });
}
</script>
