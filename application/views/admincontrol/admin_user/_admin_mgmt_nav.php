<?php
/**
 * Shared Admin Management Sub-Navigation Bar
 * All styles live in admin-dashboard-custom.css (.adminmgmt-subnav-*)
 */
$_ci  =& get_instance();
$_uri = $_ci->uri->uri_string();

$_adminmgmt_nav_items = [
    ['icon' => 'bi-people-fill',    'label' => __('admin.admin_management'), 'url' => base_url('admincontrol/admin_user'),  'match' => ['admin_user']],
    ['icon' => 'bi-person-badge',   'label' => __('admin.manage_roles'),     'url' => base_url('admincontrol/admin_roles'), 'match' => ['admin_roles', 'admin_role']],
];
?>
<div class="adminmgmt-subnav-wrap mb-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Admin context badge -->
        <a href="<?= base_url('admincontrol/admin_user') ?>"
           class="d-inline-flex align-items-center gap-1 text-decoration-none badge badge-slate fs-6 py-2 px-3 rounded-pill me-1 adminmgmt-subnav-badge">
            <i class="bi bi-shield-lock-fill"></i>
            <span class="d-none d-md-inline fw-semibold"><?= __('admin.admin_management') ?></span>
        </a>
        <!-- Tab links -->
        <?php foreach ($_adminmgmt_nav_items as $_amNav):
            $_amActive = false;
            foreach ($_amNav['match'] as $_m) {
                if (strpos($_uri, $_m) !== false) { $_amActive = true; break; }
            }
        ?>
        <a href="<?= $_amNav['url'] ?>"
           class="adminmgmt-subnav-tab<?= $_amActive ? ' active' : '' ?> d-inline-flex align-items-center gap-1 text-decoration-none rounded-2 px-2 py-1">
            <i class="bi <?= $_amNav['icon'] ?> adminmgmt-subnav-icon"></i>
            <span><?= $_amNav['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <!-- Help Tour at the far right -->
        <button type="button" onclick="if(typeof loadTourSystem==='function') loadTourSystem()"
                class="d-none d-lg-inline-flex align-items-center gap-1 btn btn-link p-0 ms-auto text-muted text-decoration-none adminmgmt-subnav-action-link">
            <i class="bi bi-question-circle adminmgmt-subnav-icon"></i>
            <span><?= __('admin.help_tour') ?></span>
        </button>
    </div>
</div>
