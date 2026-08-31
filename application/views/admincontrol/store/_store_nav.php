<?php
/**
 * Shared Store Sub-Navigation Bar
 * Rendered as a light tab-strip so it is visually distinct from the dark main site nav.
 * All styles live in admin-dashboard-custom.css (.store-subnav-*)
 */
$_ci  =& get_instance();
$_uri = $_ci->uri->uri_string();

$_store_nav_items = [
    ['icon' => 'bi-speedometer2',   'label' => __('admin.overview'),            'url' => base_url('admincontrol/store_dashboard'), 'match' => ['store_dashboard']],
    ['icon' => 'bi-box-seam',       'label' => __('admin.store_cart_products'), 'url' => base_url('admincontrol/listproduct'),     'match' => ['listproduct']],
    ['icon' => 'bi-bag-check',      'label' => __('admin.store_sales_products'),'url' => base_url('Productsales/index'),           'match' => ['Productsales', 'productsales', 'product_campaign', 'product_campaign/form']],
    ['icon' => 'bi-cart3',          'label' => __('admin.orders'),              'url' => base_url('admincontrol/listorders'),      'match' => ['listorders']],
    ['icon' => 'bi-tags',           'label' => __('admin.categories'),          'url' => base_url('admincontrol/store_category'), 'match' => ['store_category']],
    ['icon' => 'bi-people',         'label' => __('admin.clients'),             'url' => base_url('admincontrol/listclients'),     'match' => ['listclients']],
    ['icon' => 'bi-graph-up-arrow', 'label' => __('admin.store_analytics'),     'url' => base_url('admincontrol/store_analytics'),'match' => ['store_analytics']],
    ['icon' => 'bi-boxes',          'label' => __('admin.store_inventory'),     'url' => base_url('admincontrol/store_inventory'),'match' => ['store_inventory']],
    ['icon' => 'bi-gear-fill',      'label' => __('admin.store_settings'),      'url' => base_url('admincontrol/store_setting'),  'match' => ['store_setting']],
    ['icon' => 'bi-funnel-fill',    'label' => __('admin.nav_sales_funnels'),   'url' => base_url('admincontrol/sales_funnels'),  'match' => ['sales_funnels']],
    ['icon' => 'bi-tag-fill',       'label' => __('admin.funnel_pricing'),      'url' => base_url('admincontrol/funnel_pricing'), 'match' => ['funnel_pricing']],
    ['icon' => 'bi-code-slash',     'label' => __('admin.nav_store_theme_api'), 'url' => base_url('admincontrol/store_theme_api_doc'), 'match' => ['store_theme_api_doc']],
];
?>
<div class="store-subnav-wrap mb-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Store context badge -->
        <a href="<?= base_url('admincontrol/store_dashboard') ?>"
           class="d-inline-flex align-items-center gap-1 text-decoration-none badge text-bg-primary fs-6 py-2 px-3 rounded-pill me-1 store-subnav-badge">
            <i class="bi bi-shop-window"></i>
            <span class="d-none d-md-inline fw-semibold"><?= __('admin.store') ?></span>
        </a>
        <!-- Tab links -->
        <?php foreach ($_store_nav_items as $_snNav):
            $_snActive = false;
            foreach ($_snNav['match'] as $_m) {
                if (strpos($_uri, $_m) !== false) { $_snActive = true; break; }
            }
        ?>
        <a href="<?= $_snNav['url'] ?>"
           class="store-subnav-tab<?= $_snActive ? ' active' : '' ?> d-inline-flex align-items-center gap-1 text-decoration-none rounded-2 px-2 py-1">
            <i class="bi <?= $_snNav['icon'] ?> store-subnav-icon"></i>
            <span><?= $_snNav['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <!-- Open store link at the far right -->
        <a href="<?= base_url('store') ?>" target="_blank"
           class="d-none d-lg-inline-flex align-items-center gap-1 text-decoration-none text-muted ms-auto store-subnav-view-link">
            <i class="bi bi-box-arrow-up-right"></i>
            <span><?= __('admin.view_store') ?></span>
        </a>
    </div>
</div>
