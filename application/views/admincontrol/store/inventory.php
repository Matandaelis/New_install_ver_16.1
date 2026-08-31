<div class="container-fluid px-4 pb-4">
    <?php get_instance()->load->view('admincontrol/store/_store_nav'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-boxes-stacked me-2 text-primary"></i><?= __('admin.inventory_panel') ?></h4>
        </div>
    </div>

    <!-- Stock Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2 store-icon-circle"><i class="fas fa-box text-primary"></i></div>
                    <h4 class="fw-bold"><?= count($products) ?></h4>
                    <small class="text-muted">Total Products</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2 store-icon-circle"><i class="fas fa-check text-success"></i></div>
                    <h4 class="fw-bold"><?= count($products) - $out_of_stock_count - $low_stock_count ?></h4>
                    <small class="text-muted"><?= __('admin.in_stock') ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2 store-icon-circle"><i class="fas fa-exclamation-triangle text-warning"></i></div>
                    <h4 class="fw-bold"><?= $low_stock_count ?></h4>
                    <small class="text-muted"><?= __('admin.low_stock') ?> (&le;<?= $low_stock_threshold ?>)</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2 store-icon-circle"><i class="fas fa-times-circle text-danger"></i></div>
                    <h4 class="fw-bold"><?= $out_of_stock_count ?></h4>
                    <small class="text-muted"><?= __('admin.out_of_stock') ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Product</th>
                            <th class="border-0"><?= __('admin.stock_level') ?></th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Sold</th>
                            <th class="border-0">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <?php
                        $qty = (int)$p['product_quantity'];
                        if ($qty == -1) {
                            $badge = '<span class="badge bg-info">' . __('admin.unlimited_stock') . '</span>';
                        } elseif ($qty == 0) {
                            $badge = '<span class="badge bg-danger">' . __('admin.out_of_stock') . '</span>';
                        } elseif ($qty <= $low_stock_threshold) {
                            $badge = '<span class="badge bg-warning text-dark">' . __('admin.low_stock') . '</span>';
                        } else {
                            $badge = '<span class="badge bg-success">' . __('admin.in_stock') . '</span>';
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($p['product_featured_image'])): ?>
                                    <span class="position-relative d-inline-block rounded overflow-hidden store-product-thumb">
                                        <img src="<?= base_url('assets/images/product/upload/thumb/' . htmlspecialchars($p['product_featured_image'])) ?>"
                                             class="position-relative rounded store-product-thumb-img w-100 h-100"
                                             alt=""
                                             onerror="this.style.display='none'">
                                        <div class="position-absolute top-0 start-0 rounded bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-muted w-100 h-100 store-product-thumb-placeholder" title="<?= __('admin.no_product_image') ?>"><i class="fas fa-image"></i></div>
                                    </span>
                                    <?php else: ?>
                                    <div class="rounded bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-muted store-product-thumb" title="<?= __('admin.no_product_image') ?>"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                    <span class="text-truncate store-product-name"><?= htmlspecialchars($p['product_name']) ?></span>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm stock-input store-stock-input" data-product-id="<?= $p['product_id'] ?>" value="<?= $qty ?>" <?= $qty == -1 ? 'disabled' : '' ?>>
                            </td>
                            <td><?= $badge ?></td>
                            <td><?= number_format($p['total_sold']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary btn-update-stock" data-product-id="<?= $p['product_id'] ?>" <?= $qty == -1 ? 'disabled' : '' ?>><i class="fas fa-save"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.btn-update-stock', function(){
    var pid = $(this).data('product-id');
    var qty = $('.stock-input[data-product-id="' + pid + '"]').val();
    var btn = $(this);
    btn.prop('disabled', true);
    $.post(window.affiliatePro.base_url + 'admincontrol/update_stock', {
        product_id: pid, quantity: qty
    }, function(r){
        btn.prop('disabled', false);
        if (r.success) toastr.success(r.message);
        else toastr.error('Failed to update');
    }, 'json');
});
</script>
