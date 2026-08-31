<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i><?= __('admin.menu_report_all_transactions') ?>
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-outline-light btn-sm" onclick="location.reload();" title="<?= __('admin.refresh') ?>">
                                <i class="fas fa-sync-alt me-1"></i><?= __('admin.refresh') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if ($transaction == null) { ?>
                        <div class="d-flex justify-content-center align-items-center flex-column py-5">
                            <i class="fas fa-exchange-alt fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                            <p class="text-muted small mb-0"><?= __('admin.no_transactions_available') ?></p>
                        </div>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr class="align-middle">
                                        <th scope="col" class="ps-3">#</th>
                                        <th scope="col" class="sortTr <?= sort_order('admin.username') ?>">
                                            <a href="<?= sortable_link('ReportController/admin_transaction','admin.username') ?>" class="text-white text-decoration-none">
                                                <i class="fas fa-user me-1"></i><?= __('admin.username') ?>
                                                <?php if(sort_order('admin.username') == 'asc') echo '<i class="fas fa-sort-up ms-1"></i>'; elseif(sort_order('admin.username') == 'desc') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                            </a>
                                        </th>
                                        <th scope="col" class="sortTr <?= sort_order('wallet.amount') ?>">
                                            <a href="<?= sortable_link('ReportController/admin_transaction','wallet.amount') ?>" class="text-white text-decoration-none">
                                                <i class="fas fa-dollar-sign me-1"></i><?= __('admin.commission') ?>
                                                <?php if(sort_order('wallet.amount') == 'asc') echo '<i class="fas fa-sort-up ms-1"></i>'; elseif(sort_order('wallet.amount') == 'desc') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                            </a>
                                        </th>
                                        <th scope="col" class="sortTr <?= sort_order('wallet.comm_from') ?>">
                                            <a href="<?= sortable_link('ReportController/admin_transaction','wallet.comm_from') ?>" class="text-white text-decoration-none">
                                                <i class="fas fa-arrow-right me-1"></i><?= __('admin.comm_from') ?>
                                                <?php if(sort_order('wallet.comm_from') == 'asc') echo '<i class="fas fa-sort-up ms-1"></i>'; elseif(sort_order('wallet.comm_from') == 'desc') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                            </a>
                                        </th>
                                        <th scope="col" class="sortTr <?= sort_order('wallet.type') ?>">
                                            <a href="<?= sortable_link('ReportController/admin_transaction','wallet.type') ?>" class="text-white text-decoration-none">
                                                <i class="fas fa-tag me-1"></i><?= __('admin.type') ?>
                                                <?php if(sort_order('wallet.type') == 'asc') echo '<i class="fas fa-sort-up ms-1"></i>'; elseif(sort_order('wallet.type') == 'desc') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                            </a>
                                        </th>
                                        <th scope="col">
                                            <i class="fas fa-shopping-cart me-1"></i><?= __('admin.order_total') ?>
                                        </th>
                                        <th scope="col">
                                            <i class="fas fa-credit-card me-1"></i><?= __('admin.payment_method') ?>
                                        </th>
                                        <th scope="col">
                                            <i class="fas fa-comment me-1"></i><?= __('admin.comment') ?>
                                        </th>
                                        <th scope="col" class="sortTr <?= sort_order('wallet.status') ?> text-center">
                                            <a href="<?= sortable_link('ReportController/admin_transaction','wallet.status') ?>" class="text-white text-decoration-none">
                                                <i class="fas fa-info-circle me-1"></i><?= __('admin.status') ?>
                                                <?php if(sort_order('wallet.status') == 'asc') echo '<i class="fas fa-sort-up ms-1"></i>'; elseif(sort_order('wallet.status') == 'desc') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                            </a>
                                        </th>
                                        <th scope="col" class="sortTr <?= sort_order('wallet.created_at') ?> text-center">
                                            <a href="<?= sortable_link('ReportController/admin_transaction','wallet.created_at') ?>" class="text-white text-decoration-none">
                                                <i class="fas fa-calendar me-1"></i><?= __('admin.date') ?>
                                                <?php if(sort_order('wallet.created_at') == 'asc') echo '<i class="fas fa-sort-up ms-1"></i>'; elseif(sort_order('wallet.created_at') == 'desc') echo '<i class="fas fa-sort-down ms-1"></i>'; ?>
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $start_number = (($current_page - 1) * $per_page) + 1;
                                    foreach ($transaction as $key => $value) { 
                                        $row_number = $start_number + $key;
                                    ?>
                                    <tr class="align-middle">
                                        <td class="ps-3">
                                            <span class="badge bg-secondary"><?= $row_number ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-muted me-2"></i>
                                                <span class="fw-medium"><?= htmlspecialchars($value['username']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success"><?= $value['amount'] ?></span>
                                        </td>
                                        <td>
                                            <?php if($value['comm_from'] == 'ex' && isset($value['order_script_name']) && $value['order_script_name'] == 's2s'): ?>
                                                <span class="badge bg-primary text-white"><i class="fas fa-server me-1"></i><?= __('admin.s2s_source_s2s') ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-white"><?= htmlspecialchars($value['comm_from']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= htmlspecialchars($value['dis_type']) ?></span>
                                        </td>
                                        <td>
                                            <?php if($value['integration_orders_total']){ ?>
                                                <span class="fw-bold text-primary"><?= c_format($value['integration_orders_total']) ?></span>
                                            <?php } else { ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-minus-circle me-1"></i><?= __('admin.not_available') ?>
                                                </small>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if(!empty($value['payment_method'])) { ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-credit-card me-1"></i>
                                                    <?= __('admin.'.strtolower(str_replace(' ','_',$value['payment_method']))) ?>
                                                </span>
                                            <?php } else { ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-minus-circle me-1"></i><?= __('admin.not_available') ?>
                                                </small>
                                            <?php } ?>
                                        </td>
                                        <td class="textwrap">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <?php
                                                        list($message,$ip_details) = parseMessage($value['comment'],$value,'usercontrol',true, false);
                                                        echo $message;
                                                    ?>
                                                </div>
                                                <?php if(!empty($ip_details)) { ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info ms-2" 
                                                            data-bs-toggle="popover" 
                                                            data-bs-placement="top" 
                                                            data-bs-content="<?= htmlspecialchars($ip_details) ?>"
                                                            data-bs-html="true"
                                                            title="<?= __('admin.location_info') ?>">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?= $value['status_icon'] ?>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i><?= $value['created_at'] ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if(isset($pagination) && !empty($pagination)) { ?>
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <?= $pagination_summary ?>
                                    </div>
                                    <div class="pagination-wrapper">
                                        <?= $pagination ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            html: true,
            trigger: 'click',
            placement: 'top'
        });
    });
    
    // Close popover when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('[data-bs-toggle="popover"]').length) {
            $('[data-bs-toggle="popover"]').each(function() {
                var popover = bootstrap.Popover.getInstance(this);
                if (popover) {
                    popover.hide();
                }
            });
        }
    });
    
    // Add loading state to pagination links
    $('.pagination-wrapper a.page-link').on('click', function() {
        // Show loading state
        $('.pagination-wrapper').html('<div class="d-flex justify-content-center"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    });
});
</script>
