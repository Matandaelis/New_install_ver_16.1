<?php
$total_perms = $total_perms ?? 22;
$role_count  = count($roles ?? []);
$total_assigned = 0;
foreach (($roles ?? []) as $r) {
    $p = is_string($r->permissions) ? json_decode($r->permissions, true) : (array)($r->permissions ?? []);
    $total_assigned += is_array($p) ? count($p) : 0;
}
$palette = ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#6366f1','#ef4444','#ec4899','#06b6d4'];
$i = 0;
?>
<div class="container-fluid px-4 pb-4 admin-roles-page">
    <?php $this->load->view('admincontrol/admin_user/_admin_mgmt_nav'); ?>

    <!-- ── Page Header ─────────────────────────────────────── -->
    <div class="roles-page-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 px-1">
        <div>
            <h4 class="fw-bold mb-0"><?= __('admin.manage_admin_roles') ?></h4>
            <p class="text-muted small mb-0"><?= __('admin.manage_admin_roles_desc') ?></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('admincontrol/import_demo_roles') ?>" class="btn btn-outline-info btn-sm"
               onclick="return confirm('<?= htmlspecialchars(__('admin.import_demo_roles_confirm'), ENT_QUOTES, 'UTF-8') ?>');">
                <i class="bi bi-download me-2"></i><?= __('admin.import_demo_roles') ?>
            </a>
            <a href="<?= base_url('admincontrol/admin_role_form/') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-2"></i><?= __('admin.add_new_role') ?>
            </a>
        </div>
    </div>

    <!-- ── Stats Bar ───────────────────────────────────────── -->
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="roles-stat-card">
                <div class="roles-stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-person-badge-fill"></i></div>
                <div>
                    <div class="roles-stat-value"><?= $role_count ?></div>
                    <div class="roles-stat-label"><?= __('admin.admin_roles_list') ?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="roles-stat-card">
                <div class="roles-stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-shield-fill-check"></i></div>
                <div>
                    <div class="roles-stat-value"><?= $total_perms ?></div>
                    <div class="roles-stat-label"><?= __('admin.permissions') ?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="roles-stat-card">
                <div class="roles-stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-check2-all"></i></div>
                <div>
                    <div class="roles-stat-value"><?= $total_assigned ?></div>
                    <div class="roles-stat-label"><?= __('admin.permissions_assigned') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Roles Grid ──────────────────────────────────────── -->
    <?php if (empty($roles)): ?>
    <div class="roles-empty-state">
        <div class="roles-empty-icon"><i class="bi bi-person-badge"></i></div>
        <h4 class="fw-bold mt-3 mb-1"><?= __('admin.no_roles_found') ?></h4>
        <p class="text-muted mb-4"><?= __('admin.create_first_role') ?></p>
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="<?= base_url('admincontrol/import_demo_roles') ?>" class="btn btn-outline-info btn-lg"
               onclick="return confirm('<?= htmlspecialchars(__('admin.import_demo_roles_confirm'), ENT_QUOTES, 'UTF-8') ?>');">
                <i class="bi bi-download me-2"></i><?= __('admin.import_demo_roles') ?>
            </a>
            <a href="<?= base_url('admincontrol/admin_role_form/') ?>" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-plus-circle me-2"></i><?= __('admin.add_new_role') ?>
            </a>
        </div>
    </div>
    <?php else: ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
        <?php
            $role_user_counts = $role_user_counts ?? [];
            foreach ($roles as $r):
            $perms = is_string($r->permissions) ? json_decode($r->permissions, true) : (array)($r->permissions ?? []);
            $perm_count = is_array($perms) ? count($perms) : 0;
            $pct = $total_perms > 0 ? round(($perm_count / $total_perms) * 100) : 0;
            $color = $palette[$i % count($palette)];
            $initial = mb_strtoupper(mb_substr($r->name, 0, 1));
            $subadmin_count = $role_user_counts[(int)$r->id] ?? 0;
            $i++;
        ?>
        <div class="col">
            <div class="role-card" style="--role-color:<?= $color ?>">
                <div class="role-card-top">
                    <div class="role-avatar"><?= htmlspecialchars($initial) ?></div>
                    <div class="role-card-actions">
                        <a href="<?= base_url('admincontrol/admin_role_form/' . $r->id) ?>" class="btn btn-sm btn-outline-primary rounded-pill" title="<?= __('admin.edit') ?>">
                            <i class="bi bi-pencil me-1"></i><?= __('admin.edit') ?>
                        </a>
                        <a href="<?= base_url('admincontrol/admin_role_delete/' . $r->id) ?>"
                           class="btn btn-sm btn-outline-danger rounded-pill"
                           onclick="return confirm('<?= __('admin.confirm_delete_role') ?>');"
                           title="<?= __('admin.delete') ?>">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
                <div class="role-card-body">
                    <h5 class="role-name"><?= htmlspecialchars($r->name) ?></h5>
                    <code class="role-slug"><?= htmlspecialchars($r->slug ?? '') ?></code>
                    <div class="role-subadmin-count text-muted small mt-1">
                        <i class="bi bi-person-fill me-1"></i><?= sprintf(__('admin.subadmins_assigned'), $subadmin_count) ?>
                    </div>
                </div>
                <div class="role-card-footer">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="role-perm-label"><?= __('admin.permissions') ?></span>
                        <span class="role-perm-count"><?= $perm_count ?> / <?= $total_perms ?></span>
                    </div>
                    <div class="role-perm-bar">
                        <div class="role-perm-bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Add new role card -->
        <div class="col">
            <a href="<?= base_url('admincontrol/admin_role_form/') ?>" class="role-card-add text-decoration-none d-block h-100">
                <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-2 py-4">
                    <span class="role-add-icon"><i class="bi bi-plus-lg"></i></span>
                    <span class="fw-semibold text-muted"><?= __('admin.add_new_role') ?></span>
                </div>
            </a>
        </div>
    </div>
    <?php endif; ?>

</div>
