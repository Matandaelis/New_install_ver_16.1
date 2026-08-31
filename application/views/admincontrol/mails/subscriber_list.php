<?php
$baseUrl   = base_url('admincontrol/subscriber_list');
$exportUrl = base_url('admincontrol/subscriber_export');
$searchVal = htmlspecialchars($search ?? '');
$totalPages = $total > 0 ? ceil($total / $perPage) : 1;
?>

<div class="container-fluid pb-5">

    <!-- Page Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header py-3 text-white" style="background: linear-gradient(135deg,#198754,#0f5132);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-white bg-opacity-25" style="width:48px;height:48px;">
                        <i class="bi bi-envelope-check fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= __('admin.subscriber_list') ?></h4>
                        <small class="opacity-75"><?= __('admin.subscriber_list_desc') ?></small>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= $exportUrl ?>?search=<?= urlencode($search ?? '') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-download me-1"></i><?= __('admin.export_subscriber_list') ?>
                    </a>
                    <a href="<?= base_url('admincontrol/unsubscribe_list') ?>" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-envelope-x me-1"></i><?= __('admin.unsubscribe_list') ?>
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
            <div class="card border-0 shadow-sm text-center p-3 h-100">
                <div class="fs-2 fw-bold text-success"><?= number_format($stats['total_subscribed']) ?></div>
                <div class="text-muted small"><?= __('admin.total_subscribed') ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 h-100">
                <div class="fs-2 fw-bold text-primary"><?= number_format($stats['total_users']) ?></div>
                <div class="text-muted small"><?= __('admin.total_users') ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 h-100">
                <div class="fs-2 fw-bold text-danger"><?= number_format($stats['total_unsubscribed']) ?></div>
                <div class="text-muted small"><?= __('admin.total_unsubscribed') ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 h-100">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                    <div class="fs-2 fw-bold text-info"><?= $stats['rate'] ?>%</div>
                </div>
                <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-info" style="width:<?= $stats['rate'] ?>%"></div>
                </div>
                <div class="text-muted small mt-1"><?= __('admin.subscription_rate') ?></div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="<?= $baseUrl ?>">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="<?= __('admin.search_by_email_or_name') ?>" value="<?= $searchVal ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i><?= __('admin.filter') ?></button>
                    </div>
                    <?php if (!empty($searchVal)) { ?>
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
                    <h5 class="text-muted"><?= __('admin.subscriber_list_empty') ?></h5>
                </div>
            <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th><i class="bi bi-person me-1"></i><?= __('admin.name') ?></th>
                            <th><i class="bi bi-envelope me-1"></i><?= __('admin.email') ?></th>
                            <th><?= __('admin.username') ?></th>
                            <th><?= __('admin.joined_at') ?></th>
                            <th class="text-end pe-4"><?= __('admin.action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $i => $row) {
                            $name    = trim(htmlspecialchars($row['firstname'] . ' ' . $row['lastname']));
                            $joinedAt = !empty($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : '-';
                            $initials = strtoupper(substr($row['firstname'], 0, 1) . substr($row['lastname'], 0, 1));
                        ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= ($page - 1) * $perPage + $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:36px;height:36px;font-size:0.8rem;">
                                        <?= $initials ?: '?' ?>
                                    </div>
                                    <span class="fw-semibold"><?= $name ?: '-' ?></span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($row['username']) ?></td>
                            <td class="text-muted small"><?= $joinedAt ?></td>
                            <td class="text-end pe-4">
                                <a href="<?= base_url('admincontrol/addusers/' . (int)$row['id']) ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i><?= __('admin.view') ?>
                                </a>
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
                            <a class="page-link" href="<?= $baseUrl ?>?page=<?= $p ?>&search=<?= urlencode($search ?? '') ?>"><?= $p ?></a>
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
