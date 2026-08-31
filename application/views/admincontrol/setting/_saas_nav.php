<?php
/**
 * Shared SaaS Sub-Navigation Bar
 * All styles live in admin-dashboard-custom.css (.saas-subnav-*)
 */
$_ci  =& get_instance();
$_uri = $_ci->uri->uri_string();

$_saas_nav_items = [
    ['icon' => 'bi-gear-fill',  'label' => __('admin.saas_settings'),  'url' => base_url('admincontrol/saas_setting'),    'match' => ['saas_setting']],
    ['icon' => 'bi-wallet2',    'label' => __('admin.nav_deposits'),    'url' => base_url('admincontrol/vendor_deposits'),  'match' => ['vendor_deposit']],
];
?>
<div class="saas-subnav-wrap mb-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- SaaS context badge -->
        <a href="<?= base_url('admincontrol/saas_setting') ?>"
           class="d-inline-flex align-items-center gap-1 text-decoration-none badge badge-cyan fs-6 py-2 px-3 rounded-pill me-1 saas-subnav-badge">
            <i class="bi bi-clouds-fill"></i>
            <span class="d-none d-md-inline fw-semibold"><?= __('admin.saas_module') ?></span>
        </a>
        <!-- Tab links -->
        <?php foreach ($_saas_nav_items as $_snNav):
            $_snActive = false;
            foreach ($_snNav['match'] as $_m) {
                if (strpos($_uri, $_m) !== false) { $_snActive = true; break; }
            }
        ?>
        <a href="<?= $_snNav['url'] ?>"
           class="saas-subnav-tab<?= $_snActive ? ' active' : '' ?> d-inline-flex align-items-center gap-1 text-decoration-none rounded-2 px-2 py-1">
            <i class="bi <?= $_snNav['icon'] ?> saas-subnav-icon"></i>
            <span><?= $_snNav['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <!-- Help Tour at the far right -->
        <button type="button" onclick="if(typeof loadTourSystem==='function') loadTourSystem()"
                class="d-none d-lg-inline-flex align-items-center gap-1 btn btn-link p-0 ms-auto text-muted text-decoration-none saas-subnav-action-link">
            <i class="bi bi-question-circle saas-subnav-icon"></i>
            <span><?= __('admin.help_tour') ?></span>
        </button>
    </div>
</div>
