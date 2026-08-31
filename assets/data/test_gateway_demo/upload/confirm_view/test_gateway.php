<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold"><?= __('admin.test_gateway_name_label') ?>:</label>
            <p class="form-control-plaintext"><?= isset($data['test_gateway_name']) ? $data['test_gateway_name'] : '-' ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold"><?= __('admin.test_gateway_email_label') ?>:</label>
            <p class="form-control-plaintext"><?= isset($data['test_gateway_email']) ? $data['test_gateway_email'] : '-' ?></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold"><?= __('admin.test_gateway_phone_label') ?>:</label>
            <p class="form-control-plaintext"><?= isset($data['test_gateway_phone']) ? $data['test_gateway_phone'] : '-' ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold"><?= __('admin.test_gateway_account_label') ?>:</label>
            <p class="form-control-plaintext"><?= isset($data['test_gateway_account']) ? $data['test_gateway_account'] : '-' ?></p>
        </div>
    </div>
</div>
