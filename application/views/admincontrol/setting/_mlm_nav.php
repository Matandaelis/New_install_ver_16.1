<?php
/**
 * Shared MLM Sub-Navigation Bar
 * All styles live in admin-dashboard-custom.css (.mlm-subnav-*)
 */
$_ci  =& get_instance();
$_uri = $_ci->uri->uri_string();

$_mlm_nav_items = [
    ['icon' => 'bi-gear-fill',    'label' => __('admin.mlm_settings'), 'url' => base_url('admincontrol/mlm_settings'), 'match' => ['mlm_settings']],
    ['icon' => 'bi-diagram-3',    'label' => __('admin.mlm_levels'),   'url' => base_url('admincontrol/mlm_levels'),   'match' => ['mlm_levels']],
];
?>
<div class="mlm-subnav-wrap mb-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- MLM context badge -->
        <a href="<?= base_url('admincontrol/mlm_settings') ?>"
           class="d-inline-flex align-items-center gap-1 text-decoration-none badge badge-teal fs-6 py-2 px-3 rounded-pill me-1 mlm-subnav-badge">
            <i class="bi bi-diagram-3-fill"></i>
            <span class="d-none d-md-inline fw-semibold"><?= __('admin.mlm') ?></span>
        </a>
        <!-- Tab links -->
        <?php foreach ($_mlm_nav_items as $_mlmNav):
            $_mlmActive = false;
            foreach ($_mlmNav['match'] as $_m) {
                if (strpos($_uri, $_m) !== false) { $_mlmActive = true; break; }
            }
        ?>
        <a href="<?= $_mlmNav['url'] ?>"
           class="mlm-subnav-tab<?= $_mlmActive ? ' active' : '' ?> d-inline-flex align-items-center gap-1 text-decoration-none rounded-2 px-2 py-1">
            <i class="bi <?= $_mlmNav['icon'] ?> mlm-subnav-icon"></i>
            <span><?= $_mlmNav['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <!-- Help Tour at the far right -->
        <button type="button" onclick="if(typeof loadTourSystem==='function') loadTourSystem()"
                class="d-none d-lg-inline-flex align-items-center gap-1 btn btn-link p-0 ms-auto text-muted text-decoration-none mlm-subnav-action-link">
            <i class="bi bi-question-circle mlm-subnav-icon"></i>
            <span><?= __('admin.help_tour') ?></span>
        </button>
    </div>
</div>
