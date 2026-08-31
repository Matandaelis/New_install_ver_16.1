<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-ticket-alt me-2"></i><?= __('admin.coupon') ?>
                        </h5>
                        <a class="btn btn-light btn-sm" href="<?= base_url('usercontrol/store_coupon_manage/') ?>">
                            <i class="fas fa-plus me-1"></i><?= __('admin.add_new') ?>
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <?php if (empty($coupons)) { ?>
                        <div class="text-center py-5">
                            <i class="fas fa-ticket-alt fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                            <h5 class="text-muted"><?= __('admin.no_data_found') ?></h5>
                            <p class="text-muted mb-4"><?= __('admin.no_coupons') ?></p>
                            <a href="<?= base_url('usercontrol/store_coupon_manage/') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i><?= __('admin.add_new') ?> <?= __('admin.coupon') ?>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-semibold">
                                            <i class="fas fa-tag text-muted me-1"></i><?= __('admin.coupon_name') ?>
                                        </th>
                                        <th class="fw-semibold" style="min-width: 120px;">
                                            <i class="fas fa-box text-muted me-1"></i><?= __('admin.count_product_use') ?>
                                        </th>
                                        <th class="fw-semibold" style="min-width: 100px;">
                                            <i class="fas fa-users text-muted me-1"></i><?= __('admin.uses_total') ?>
                                        </th>
                                        <th class="fw-semibold" style="min-width: 100px;">
                                            <i class="fas fa-barcode text-muted me-1"></i><?= __('admin.code') ?>
                                        </th>
                                        <th class="fw-semibold" style="min-width: 100px;">
                                            <i class="fas fa-percentage text-muted me-1"></i><?= __('admin.discount') ?>
                                        </th>
                                        <th class="fw-semibold text-nowrap" style="min-width: 100px;">
                                            <i class="fas fa-calendar-alt text-muted me-1"></i><?= __('admin.date_start') ?>
                                        </th>
                                        <th class="fw-semibold text-nowrap" style="min-width: 100px;">
                                            <i class="fas fa-calendar-check text-muted me-1"></i><?= __('admin.date_end') ?>
                                        </th>
                                        <th class="fw-semibold" style="min-width: 80px;">
                                            <i class="fas fa-toggle-on text-muted me-1"></i><?= __('admin.status') ?>
                                        </th>
                                        <th class="fw-semibold text-end" style="min-width: 140px;">
                                            <i class="fas fa-cog text-muted me-1"></i><?= __('admin.actions') ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($coupons as $coupon) { ?>
                                        <tr>
                                            <td class="fw-medium"><?= htmlspecialchars($coupon['name']) ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= (int)$coupon['product_count'] ?> / <?= (int)$coupon['count_coupon'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= $coupon['uses_total'] - $coupon['count_coupon'] ?>
                                                </span>
                                            </td>
                                            <td><code class="bg-light px-2 py-1 rounded"><?= htmlspecialchars($coupon['code']) ?></code></td>
                                            <td class="fw-semibold text-success">
                                                <?= $coupon['type']=="P" ? getDecimalNumberFormat($coupon['discount'],$_SESSION['userDecimalPlace']).'%' : c_format($coupon['discount']) ?>
                                            </td>
                                            <td class="text-nowrap"><?= dateGlobalFormat($coupon['date_start']) ?></td>
                                            <td class="text-nowrap"><?= dateGlobalFormat($coupon['date_end']) ?></td>
                                            <td>
                                                <?php if($coupon['status'] == '1') { ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i><?= __('admin.enabled') ?>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-times-circle me-1"></i><?= __('admin.disabled') ?>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?= base_url('usercontrol/store_coupon_manage/'.$coupon['coupon_id']) ?>" class="btn btn-outline-primary" title="<?= __('admin.edit') ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url('usercontrol/store_coupon_delete/'.$coupon['coupon_id']) ?>" class="btn btn-outline-danger delete-button" title="<?= __('admin.delete') ?>">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $(".delete-button").on('click', function(e) {
        if (!confirm('<?= __('user.are_you_sure') ?>')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>