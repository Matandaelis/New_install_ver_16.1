<?php
/**
 * Shared Campaigns / Integration Sub-Navigation Bar
 * All styles live in admin-dashboard-custom.css (.campaign-subnav-*)
 */
$_ci  =& get_instance();
$_uri = $_ci->uri->uri_string();

$_campaign_nav_items = [
    ['icon' => 'bi-rocket-takeoff', 'label' => __('admin.marketing_tools'),    'url' => base_url('integration/integration_tools'),    'match' => ['integration_tools']],
    ['icon' => 'bi-puzzle',         'label' => __('admin.programs'),            'url' => base_url('integration/programs'),             'match' => ['integration/programs', 'programs_form']],
    ['icon' => 'bi-folder',         'label' => __('admin.integration_category'),'url' => base_url('integration/integration_category'), 'match' => ['integration_category', 'category_add']],
];
?>
<div class="campaign-subnav-wrap mb-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Campaigns context badge -->
        <a href="<?= base_url('integration/integration_tools') ?>"
           class="d-inline-flex align-items-center gap-1 text-decoration-none badge badge-rose fs-6 py-2 px-3 rounded-pill me-1 campaign-subnav-badge">
            <i class="bi bi-megaphone-fill"></i>
            <span class="d-none d-md-inline fw-semibold"><?= __('admin.campaigns') ?></span>
        </a>
        <!-- Tab links -->
        <?php foreach ($_campaign_nav_items as $_cnNav):
            $_cnActive = false;
            foreach ($_cnNav['match'] as $_m) {
                if (strpos($_uri, $_m) !== false) { $_cnActive = true; break; }
            }
        ?>
        <a href="<?= $_cnNav['url'] ?>"
           class="campaign-subnav-tab<?= $_cnActive ? ' active' : '' ?> d-inline-flex align-items-center gap-1 text-decoration-none rounded-2 px-2 py-1">
            <i class="bi <?= $_cnNav['icon'] ?> campaign-subnav-icon"></i>
            <span><?= $_cnNav['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <!-- Help Tour at the far right -->
        <button type="button" onclick="if(typeof loadTourSystem==='function') loadTourSystem()"
                class="d-none d-lg-inline-flex align-items-center gap-1 btn btn-link p-0 ms-auto text-muted text-decoration-none campaign-subnav-action-link">
            <i class="bi bi-question-circle campaign-subnav-icon"></i>
            <span><?= __('admin.help_tour') ?></span>
        </button>
    </div>
</div>
