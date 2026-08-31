<?php
/**
 * Shared Membership Sub-Navigation Bar
 * All styles live in admin-dashboard-custom.css (.membership-subnav-*)
 */
$_ci  =& get_instance();
$_uri = $_ci->uri->uri_string();

$_membership_nav_items = [
    ['icon' => 'bi-speedometer2',    'label' => __('admin.membership_dashboard'), 'url' => base_url('membership/membership_orders'), 'match' => ['membership_orders']],
    ['icon' => 'bi-card-list',       'label' => __('admin.membership_plans'),     'url' => base_url('membership/plans'),              'match' => ['membership/plans', 'plan_create', 'plan_edit']],
    ['icon' => 'bi-gear-fill',       'label' => __('admin.membership_settings'),  'url' => base_url('membership/settings'),           'match' => ['membership/settings']],
];
?>
<div class="membership-subnav-wrap mb-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Membership context badge -->
        <a href="<?= base_url('membership/membership_orders') ?>"
           class="d-inline-flex align-items-center gap-1 text-decoration-none badge badge-purple fs-6 py-2 px-3 rounded-pill me-1 membership-subnav-badge">
            <i class="bi bi-person-fill-check"></i>
            <span class="d-none d-md-inline fw-semibold"><?= __('admin.membership') ?></span>
        </a>
        <!-- Tab links -->
        <?php foreach ($_membership_nav_items as $_mnNav):
            $_mnActive = false;
            foreach ($_mnNav['match'] as $_m) {
                if (strpos($_uri, $_m) !== false) { $_mnActive = true; break; }
            }
        ?>
        <a href="<?= $_mnNav['url'] ?>"
           class="membership-subnav-tab<?= $_mnActive ? ' active' : '' ?> d-inline-flex align-items-center gap-1 text-decoration-none rounded-2 px-2 py-1">
            <i class="bi <?= $_mnNav['icon'] ?> membership-subnav-icon"></i>
            <span><?= $_mnNav['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <!-- Help Tour at the far right -->
        <button type="button" onclick="if(typeof loadTourSystem==='function') loadTourSystem()"
                class="d-none d-lg-inline-flex align-items-center gap-1 btn btn-link p-0 ms-auto text-muted text-decoration-none membership-subnav-action-link">
            <i class="bi bi-question-circle membership-subnav-icon"></i>
            <span><?= __('admin.help_tour') ?></span>
        </button>
    </div>
</div>
