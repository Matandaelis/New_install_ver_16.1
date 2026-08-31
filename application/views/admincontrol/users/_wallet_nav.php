<?php
/**
 * Shared Wallet Sub-Navigation Bar
 * All styles live in admin-dashboard-custom.css (.wallet-subnav-*)
 */
$_ci  =& get_instance();
$_uri = $_ci->uri->uri_string();

$_wallet_nav_items = [
    ['icon' => 'bi-list-ul',           'label' => __('admin.transactions'),           'url' => base_url('admincontrol/mywallet'),                    'match' => ['mywallet']],
    ['icon' => 'bi-hand-index',        'label' => __('admin.menu_withdraw_request'),  'url' => base_url('admincontrol/wallet_requests_list'),        'match' => ['wallet_requests_list', 'wallet_requests_details']],
    ['icon' => 'bi-file-earmark-arrow-up', 'label' => __('admin.admincontrol_mass_payout'), 'url' => base_url('admincontrol/mass_payout'),          'match' => ['mass_payout']],
    ['icon' => 'bi-credit-card',       'label' => __('admin.withdrawal_payment_gateways'), 'url' => base_url('admincontrol/withdrawal_payment_gateways'), 'match' => ['withdrawal_payment_gateways']],
    ['icon' => 'bi-gear-fill',         'label' => __('admin.wallet_setting'),          'url' => base_url('admincontrol/wallet_setting'),              'match' => ['wallet_setting']],
];
?>
<div class="wallet-subnav-wrap mb-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Wallet context badge -->
        <a href="<?= base_url('admincontrol/mywallet') ?>"
           class="d-inline-flex align-items-center gap-1 text-decoration-none badge badge-emerald fs-6 py-2 px-3 rounded-pill me-1 wallet-subnav-badge">
            <i class="bi bi-wallet2"></i>
            <span class="d-none d-md-inline fw-semibold"><?= __('admin.menu_admin_wallet') ?></span>
        </a>
        <!-- Tab links -->
        <?php foreach ($_wallet_nav_items as $_wnNav):
            $_wnActive = false;
            foreach ($_wnNav['match'] as $_m) {
                if (strpos($_uri, $_m) !== false) { $_wnActive = true; break; }
            }
        ?>
        <a href="<?= $_wnNav['url'] ?>"
           class="wallet-subnav-tab<?= $_wnActive ? ' active' : '' ?> d-inline-flex align-items-center gap-1 text-decoration-none rounded-2 px-2 py-1">
            <i class="bi <?= $_wnNav['icon'] ?> wallet-subnav-icon"></i>
            <span><?= $_wnNav['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <!-- Help Tour at the far right -->
        <button type="button" onclick="if(typeof loadTourSystem==='function') loadTourSystem()"
                class="d-none d-lg-inline-flex align-items-center gap-1 btn btn-link p-0 ms-auto text-muted text-decoration-none wallet-subnav-action-link">
            <i class="bi bi-question-circle wallet-subnav-icon"></i>
            <span><?= __('admin.help_tour') ?></span>
        </button>
    </div>
</div>
