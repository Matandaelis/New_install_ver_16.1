<?php
$baseUrl   = base_url('admincontrol/unsubscribe_list');
$exportUrl = base_url('admincontrol/unsubscribe_export');
$searchVal = htmlspecialchars($search ?? '');
$sourceVal = htmlspecialchars($source ?? '');
$totalPages = $total > 0 ? ceil($total / $perPage) : 1;

$sourceLabels = [
    'email_link'   => __('admin.unsubscribe_source_email_link'),
    'profile_page' => __('admin.unsubscribe_source_profile_page'),
    'manual'       => __('admin.unsubscribe_source_manual'),
];
$sourceBadge = [
    'email_link'   => 'bg-info',
    'profile_page' => 'bg-warning text-dark',
    'manual'       => 'bg-secondary',
];
?>

<div class="container-fluid pb-5">

    <!-- Page Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header py-3 text-white" style="background: linear-gradient(135deg,#0d6efd,#084298);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-white bg-opacity-25" style="width:48px;height:48px;">
                        <i class="bi bi-envelope-x fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= __('admin.unsubscribe_list') ?></h4>
                        <small class="opacity-75"><?= __('admin.unsubscribe_list_desc') ?></small>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addUnsubscribeModal">
                        <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_to_unsubscribe') ?>
                    </button>
                    <a href="<?= $exportUrl ?>?search=<?= urlencode($search ?? '') ?>&source=<?= urlencode($source ?? '') ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-download me-1"></i><?= __('admin.export_unsubscribe_list') ?>
                    </a>
                    <a href="<?= base_url('admincontrol/mails') ?>" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i><?= __('admin.back') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-primary"><?= number_format($stats['total']) ?></div>
                <div class="text-muted small"><?= __('admin.total_unsubscribed') ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-warning"><?= number_format($stats['this_month']) ?></div>
                <div class="text-muted small"><?= __('admin.this_month') ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-info"><?= number_format($stats['email_link']) ?></div>
                <div class="text-muted small"><?= __('admin.unsubscribe_source_email_link') ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-secondary"><?= number_format($stats['profile_page'] + $stats['manual']) ?></div>
                <div class="text-muted small"><?= __('admin.unsubscribe_source_profile_page') ?> / <?= __('admin.unsubscribe_source_manual') ?></div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="<?= $baseUrl ?>">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="<?= __('admin.search_by_email') ?>" value="<?= $searchVal ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="source" class="form-select">
                            <option value=""><?= __('admin.all_sources') ?></option>
                            <option value="email_link" <?= $sourceVal === 'email_link' ? 'selected' : '' ?>><?= __('admin.unsubscribe_source_email_link') ?></option>
                            <option value="profile_page" <?= $sourceVal === 'profile_page' ? 'selected' : '' ?>><?= __('admin.unsubscribe_source_profile_page') ?></option>
                            <option value="manual" <?= $sourceVal === 'manual' ? 'selected' : '' ?>><?= __('admin.unsubscribe_source_manual') ?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i><?= __('admin.filter') ?></button>
                    </div>
                    <?php if (!empty($searchVal) || !empty($sourceVal)) { ?>
                    <div class="col-md-2">
                        <a href="<?= $baseUrl ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i><?= __('admin.clear') ?></a>
                    </div>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($list)) { ?>
                <div class="text-center py-5">
                    <i class="bi bi-envelope-check fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted"><?= __('admin.unsubscribe_list_empty') ?></h5>
                </div>
            <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th><i class="bi bi-envelope me-1"></i><?= __('admin.email') ?></th>
                            <th><?= __('admin.unsubscribe_source') ?></th>
                            <th><?= __('admin.unsubscribed_at') ?></th>
                            <th class="text-end pe-4"><?= __('admin.action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $i => $row) {
                            $src   = $row['source'] ?? 'email_link';
                            $label = $sourceLabels[$src] ?? $src;
                            $badge = $sourceBadge[$src] ?? 'bg-secondary';
                            $date  = !empty($row['unsubscribed_at']) ? date('d M Y, H:i', strtotime($row['unsubscribed_at'])) : '-';
                        ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= ($page - 1) * $perPage + $i + 1 ?></td>
                            <td>
                                <span class="fw-semibold"><?= htmlspecialchars($row['email']) ?></span>
                            </td>
                            <td><span class="badge <?= $badge ?>"><?= $label ?></span></td>
                            <td class="text-muted small"><?= $date ?></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-outline-success btn-sm btn-resubscribe"
                                    data-id="<?= (int)$row['id'] ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    title="<?= __('admin.resubscribe') ?>">
                                    <i class="bi bi-envelope-check me-1"></i><?= __('admin.resubscribe') ?>
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1) { ?>
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                <small class="text-muted"><?= __('admin.showing') ?> <?= ($page - 1) * $perPage + 1 ?>–<?= min($page * $perPage, $total) ?> <?= __('admin.of') ?> <?= $total ?></small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <?php for ($p = 1; $p <= $totalPages; $p++) { ?>
                        <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $baseUrl ?>?page=<?= $p ?>&search=<?= urlencode($search ?? '') ?>&source=<?= urlencode($source ?? '') ?>"><?= $p ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
            <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Add to Unsubscribe Modal -->
<div class="modal fade" id="addUnsubscribeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-envelope-plus me-2"></i><?= __('admin.add_to_unsubscribe') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3"><?= __('admin.add_to_unsubscribe_desc') ?></p>
                <div class="mb-3">
                    <label class="form-label fw-semibold"><?= __('admin.email') ?></label>
                    <input type="email" id="new-unsub-email" class="form-control" placeholder="email@example.com">
                </div>
                <div id="add-unsub-result" class="d-none alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
                <button type="button" class="btn btn-primary" id="btn-add-unsub"><i class="bi bi-plus-circle me-1"></i><?= __('admin.add') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    // Re-subscribe
    $(document).on('click', '.btn-resubscribe', function () {
        var $btn   = $(this);
        var id     = $btn.data('id');
        var email  = $btn.data('email');
        if (!confirm('<?= addslashes(__('admin.resubscribe_confirm')) ?>')) return;
        $btn.prop('disabled', true);
        $.ajax({
            url: '<?= base_url('admincontrol/unsubscribe_resubscribe/') ?>' + id,
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            dataType: 'json',
            success: function (json) {
                if (json.success) {
                    $btn.closest('tr').fadeOut(400, function () { $(this).remove(); });
                    if (typeof showToast === 'function') showToast('<?= __('admin.success') ?>', json.success, 'success', 3000);
                } else if (json.error) {
                    if (typeof showToast === 'function') showToast('<?= __('admin.error') ?>', json.error, 'error', 4000);
                }
            },
            complete: function () { $btn.prop('disabled', false); }
        });
    });

    // Add to unsubscribe list
    $('#btn-add-unsub').on('click', function () {
        var $btn   = $(this);
        var email  = $('#new-unsub-email').val().trim();
        var $res   = $('#add-unsub-result');
        $res.addClass('d-none').removeClass('alert-success alert-danger');
        if (!email) { $res.removeClass('d-none').addClass('alert-danger').text('<?= __('admin.enter_test_email') ?>'); return; }
        $btn.prop('disabled', true);
        $.ajax({
            url: '<?= base_url('admincontrol/unsubscribe_add') ?>',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: { email: email },
            dataType: 'json',
            success: function (json) {
                if (json.success) {
                    $res.removeClass('d-none').addClass('alert-success').text(json.success);
                    $('#new-unsub-email').val('');
                    setTimeout(function () { location.reload(); }, 1200);
                } else if (json.error) {
                    $res.removeClass('d-none').addClass('alert-danger').text(json.error);
                }
            },
            complete: function () { $btn.prop('disabled', false); }
        });
    });

});
</script>
