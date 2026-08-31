<?php
/**
 * Shared Award Level Sub-Navigation Bar
 * All styles live in admin-dashboard-custom.css (.award-subnav-*)
 */
$_ci  =& get_instance();
$_uri = $_ci->uri->uri_string();

$_award_nav_items = [
    ['icon' => 'bi-trophy',       'label' => __('admin.award_level'),   'url' => base_url('admincontrol/award_level'),   'match' => ['award_level', 'create_award_level', 'update_award_level']],
    ['icon' => 'bi-bar-chart-line','label' => __('admin.level_analysis'),'url' => base_url('admincontrol/level_analysis'),'match' => ['level_analysis']],
];
?>
<div class="award-subnav-wrap mb-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Award context badge -->
        <a href="<?= base_url('admincontrol/award_level') ?>"
           class="d-inline-flex align-items-center gap-1 text-decoration-none badge badge-amber fs-6 py-2 px-3 rounded-pill me-1 award-subnav-badge">
            <i class="bi bi-trophy-fill"></i>
            <span class="d-none d-md-inline fw-semibold"><?= __('admin.award_level') ?></span>
        </a>
        <!-- Tab links -->
        <?php foreach ($_award_nav_items as $_anNav):
            $_anActive = false;
            foreach ($_anNav['match'] as $_m) {
                if (strpos($_uri, $_m) !== false) { $_anActive = true; break; }
            }
        ?>
        <a href="<?= $_anNav['url'] ?>"
           class="award-subnav-tab<?= $_anActive ? ' active' : '' ?> d-inline-flex align-items-center gap-1 text-decoration-none rounded-2 px-2 py-1">
            <i class="bi <?= $_anNav['icon'] ?> award-subnav-icon"></i>
            <span><?= $_anNav['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <!-- Help Tour at the far right -->
        <button type="button" onclick="if(typeof loadTourSystem==='function') loadTourSystem()"
                class="d-none d-lg-inline-flex align-items-center gap-1 btn btn-link p-0 ms-auto text-muted text-decoration-none award-subnav-action-link">
            <i class="bi bi-question-circle award-subnav-icon"></i>
            <span><?= __('admin.help_tour') ?></span>
        </button>
    </div>
</div>
