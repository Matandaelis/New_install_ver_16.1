<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    update_option('affi_enabled_client_fields', $_POST['affi_enabled_client_fields'] ?? []);
    update_option('affi_woo_option',        isset($_POST['affi_woo_option'])       ? 1 : 0);
    update_option('affi_plugin_option',     isset($_POST['affi_plugin_option'])    ? 1 : 0);
    update_option('affi_cartflows_option',  isset($_POST['affi_cartflows_option']) ? 1 : 0);
    update_option('affi_display_enabled',   isset($_POST['affi_display_enabled'])  ? 1 : 0);
    update_option('affi_display_position',  sanitize_text_field($_POST['affi_display_position'] ?? 'bottom'));
    update_option('affi_display_text',      sanitize_text_field($_POST['affi_display_text'] ?? 'Affiliate ID is {id}'));
    
    // Admin display settings
    update_option('affi_admin_order_details', isset($_POST['affi_admin_order_details']) ? 1 : 0);
    update_option('affi_admin_orders_list', isset($_POST['affi_admin_orders_list']) ? 1 : 0);
    
    // Remember the current tab after save
    if (isset($_POST['current_tab'])) {
        update_option('affi_last_active_tab', sanitize_text_field($_POST['current_tab']));
    }
    
    $settings_saved = true; // Set flag
}

/* ------------------------------------------------------------------ */
$enabled_fields = get_option('affi_enabled_client_fields', []);
$all_fields     = [
    'client_name'=>__('Client Name','affiliateprosaas-integration'),
    'client_email'=>__('Client Email','affiliateprosaas-integration'),
    'client_phone'=>__('Client Phone','affiliateprosaas-integration'),
    'billing_address'=>__('Billing Address','affiliateprosaas-integration'),
    'billing_city'=>__('Billing City','affiliateprosaas-integration'),
    'billing_state'=>__('Billing State','affiliateprosaas-integration'),
    'billing_postcode'=>__('Billing Postcode','affiliateprosaas-integration'),
    'billing_country'=>__('Billing Country','affiliateprosaas-integration'),
    'shipping_address'=>__('Shipping Address','affiliateprosaas-integration'),
    'shipping_city'=>__('Shipping City','affiliateprosaas-integration'),
    'shipping_state'=>__('Shipping State','affiliateprosaas-integration'),
    'shipping_postcode'=>__('Shipping Postcode','affiliateprosaas-integration'),
    'shipping_country'=>__('Shipping Country','affiliateprosaas-integration'),
    'payment_method'=>__('Payment Method','affiliateprosaas-integration'),
    'customer_note'=>__('Customer Note','affiliateprosaas-integration'),
    'user_id'=>__('User ID','affiliateprosaas-integration'),
    'user_email'=>__('User Email','affiliateprosaas-integration'),
    'profile_url'=>__('Profile URL','affiliateprosaas-integration'),
];
$aff        = affi_check_connection_status();
$plugin_on  = get_option('affi_plugin_option');
$woo_on     = get_option('affi_woo_option');
$cart_on    = get_option('affi_cartflows_option');
$cnt        = count($enabled_fields);
$disp_on    = get_option('affi_display_enabled');
$disp_pos   = get_option('affi_display_position','bottom');
$disp_text  = get_option('affi_display_text','Affiliate ID is {id}');

// Admin display settings
$admin_details = get_option('affi_admin_order_details');
$admin_list = get_option('affi_admin_orders_list');

if(!function_exists('get_plugin_data')) require_once ABSPATH.'wp-admin/includes/plugin.php';
$ver = file_exists(dirname(__FILE__).'/index.php') ? get_plugin_data(dirname(__FILE__).'/index.php')['Version'] : '3.0.0';

// Get the last active tab (default to 'core' if none set)
$last_active_tab = get_option('affi_last_active_tab', 'core');

$pos=[
    'top left'=>'📍 Top-Left','top'=>'⬆️ Top-Center','top right'=>'📍 Top-Right',
    'left'=>'⬅️ Left','right'=>'➡️ Right',
    'bottom left'=>'📍 Bottom-Left','bottom'=>'⬇️ Bottom-Center','bottom right'=>'📍 Bottom-Right'
];
?>

<link rel="stylesheet" href="<?= plugin_dir_url(__FILE__) ?>assets/css/style.css">

<script>
// Set translations, current tab, and flags for external JS
window.affiTranslations = {
    settingsSaved: '<?= esc_js(__("Settings saved successfully!", "affiliateprosaas-integration")) ?>',
    wooRequired: '<?= esc_js(__("WooCommerce has been automatically enabled as it\'s required for CartFlows.", "affiliateprosaas-integration")) ?>',
    wooRequiredDisable: '<?= esc_js(__("WooCommerce cannot be disabled while CartFlows is enabled.", "affiliateprosaas-integration")) ?>'
};
window.affiCurrentTab = '<?= esc_js($last_active_tab) ?>';
<?php if (isset($settings_saved) && $settings_saved): ?>
window.affiSettingsSaved = true;
<?php endif; ?>
</script>
<script src="<?= plugin_dir_url(__FILE__) ?>assets/js/admin.js"></script>


<div class="affi-container">
    <!-- Banner -->
    <div class="affi-banner">
        <h1>AffiliateProSaaS Integration</h1>
        <p><?= __('Configure & monitor your affiliate integration','affiliateprosaas-integration'); ?></p>
    </div>

    <!-- Stats -->
    <div class="affi-stats">
        <div class="affi-stat <?= $plugin_on ? 'success' : 'danger' ?>">
            <div class="affi-stat-icon"><?= $plugin_on ? '✅' : '❌' ?></div>
            <div class="affi-stat-value"><?= $plugin_on ? __('Active','affiliateprosaas-integration') : __('Inactive','affiliateprosaas-integration') ?></div>
            <div class="affi-stat-label">Plugin Core</div>
        </div>
        <div class="affi-stat primary">
            <div class="affi-stat-icon">📊</div>
            <div class="affi-stat-value"><?= $cnt ?></div>
            <div class="affi-stat-label">Synced Fields</div>
        </div>
        <div class="affi-stat info">
            <div class="affi-stat-icon">🔗</div>
            <div class="affi-stat-value"><?= $woo_on + $cart_on ?></div>
            <div class="affi-stat-label">Integrations</div>
        </div>
        <div class="affi-stat <?= $aff['connected'] ? 'success' : 'danger' ?>">
            <div class="affi-stat-icon"><?= $aff['connected'] ? '🟢' : '🔴' ?></div>
            <div class="affi-stat-value"><?= $aff['connected'] ? __('Connected','affiliateprosaas-integration') : __('Disconnected','affiliateprosaas-integration') ?></div>
            <div class="affi-stat-label">Status</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="affi-tabs">
        <button class="affi-tab <?= $last_active_tab === 'core' ? 'active' : '' ?>" onclick="showTab('core')" data-tab="core">⚙️ <?= __('Module','affiliateprosaas-integration') ?></button>
        <button class="affi-tab <?= $last_active_tab === 'sync' ? 'active' : '' ?>" onclick="showTab('sync')" data-tab="sync">📊 <?= __('Data Sync','affiliateprosaas-integration') ?></button>
        <button class="affi-tab <?= $last_active_tab === 'disp' ? 'active' : '' ?>" onclick="showTab('disp')" data-tab="disp">👁️ <?= __('ID Display','affiliateprosaas-integration') ?></button>
        <button class="affi-tab <?= $last_active_tab === 'info' ? 'active' : '' ?>" onclick="showTab('info')" data-tab="info">📋 <?= __('Status','affiliateprosaas-integration') ?></button>
    </div>

    <form method="post" id="affi-form">
        <!-- Hidden field to track current tab -->
        <input type="hidden" name="current_tab" id="current_tab" value="<?= esc_attr($last_active_tab) ?>">
        
        <!-- MODULE TAB -->
        <div class="affi-content <?= $last_active_tab === 'core' ? 'active' : '' ?>" id="core">
            <?php 
            $modules = [
                ['affi_plugin_option','🔧',__('Plugin Core','affiliateprosaas-integration'),$plugin_on,__('Enable main functionality','affiliateprosaas-integration')],
                ['affi_woo_option','🛒',__('WooCommerce','affiliateprosaas-integration'),$woo_on,__('Enable for WooCommerce orders (required for CartFlows)','affiliateprosaas-integration')],
                ['affi_cartflows_option','🔄',__('CartFlows','affiliateprosaas-integration'),$cart_on,__('Enable for CartFlows funnel checkout (also enable WooCommerce)','affiliateprosaas-integration')],
            ];
            foreach($modules as [$opt,$icon,$title,$on,$hint]): ?>
                <div class="affi-module <?= $on ? 'active' : '' ?>">
                    <div class="affi-module-info">
                        <span class="affi-module-icon"><?= $icon ?></span>
                        <div>
                            <div class="affi-module-title"><?= $title ?></div>
                            <div class="affi-module-hint"><?= $hint ?></div>
                        </div>
                    </div>
                    <label class="affi-switch">
                        <input type="checkbox" name="<?= $opt ?>" value="1" <?= $on ? 'checked' : '' ?>>
                        <span class="affi-slider"></span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- DATA SYNC TAB -->
        <div class="affi-content <?= $last_active_tab === 'sync' ? 'active' : '' ?>" id="sync">
            <div class="affi-sync-header">
                <h3>📊 <?= __('Data Synchronization Fields','affiliateprosaas-integration') ?></h3>
                <div>
                    <button type="button" class="affi-btn affi-btn-primary" onclick="selAll()">All</button>
                    <button type="button" class="affi-btn affi-btn-secondary" onclick="selNone()">None</button>
                </div>
            </div>
            <div class="affi-sync-badge"><?= __('Selected','affiliateprosaas-integration') ?>: <span id="cnt"><?= $cnt ?></span> / <?= count($all_fields) ?></div>
            <div class="affi-sync-spacer"></div>
            <div class="affi-sync-grid">
                <?php foreach($all_fields as $k => $lbl): ?>
                    <label class="affi-sync-field">
                        <input type="checkbox" name="affi_enabled_client_fields[]" value="<?= $k ?>" <?= in_array($k, $enabled_fields) ? 'checked' : '' ?>>
                        <?= $lbl ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- DISPLAY TAB -->
        <div class="affi-content <?= $last_active_tab === 'disp' ? 'active' : '' ?>" id="disp">
            <div class="affi-display-header">
                <div class="affi-display-header-content">
                    <h2 class="affi-display-title">
                        <span class="affi-display-title-icon">👁️</span>
                        <?= __('Affiliate ID Display Settings','affiliateprosaas-integration') ?>
                    </h2>
                    <div class="affi-display-badge">
                        <?= $disp_on ? '✅ Active' : '⭕ Inactive' ?>
                    </div>
                </div>
            </div>

            <div class="affi-display-grid">
                <!-- Enable -->
                <div class="affi-display-card">
                    <div class="affi-display-step">1</div>
                    <h3>👁️ <?= __('Enable Badge','affiliateprosaas-integration') ?></h3>
                    <label class="affi-switch">
                        <input type="checkbox" name="affi_display_enabled" value="1" <?= $disp_on ? 'checked' : '' ?>>
                        <span class="affi-slider"></span>
                    </label>
                    <p class="affi-display-card-hint">
                        <?= __('Shows only for visitors via affiliate links','affiliateprosaas-integration') ?>
                    </p>
                </div>

                <!-- Position -->
                <div class="affi-display-card">
                    <div class="affi-display-step success">2</div>
                    <h3>🎯 <?= __('Position','affiliateprosaas-integration') ?></h3>
                    <select name="affi_display_position" class="affi-select">
                        <?php foreach($pos as $v => $l): ?>
                            <option value="<?= $v ?>" <?= $disp_pos === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="affi-display-card-hint">
                        <?= __('Choose display position on your website','affiliateprosaas-integration') ?>
                    </p>
                </div>

                <!-- Text -->
                <div class="affi-display-card warning">
                    <div class="affi-display-step warning">3</div>
                    <h3>💬 <?= __('Custom Text','affiliateprosaas-integration') ?></h3>
                    <div class="affi-input-group">
                        <span class="affi-input-group-text">💬</span>
                        <input type="text" class="affi-input" name="affi_display_text" value="<?= esc_attr($disp_text) ?>" placeholder="Affiliate ID is {id}">
                    </div>
                    <p class="affi-display-card-code-hint">
                        <?= __('Use','affiliateprosaas-integration') ?> <code class="affi-code-highlight">{id}</code> <?= __('for placement','affiliateprosaas-integration') ?>
                    </p>
                </div>

                <!-- Admin Display -->
                <div class="affi-display-card info">
                    <div class="affi-display-step info">4</div>
                    <h3>📋 <?= __('Admin Display','affiliateprosaas-integration') ?></h3>
                    
                    <div style="margin: 10px 0;">
                        <label style="display: flex; align-items: center; margin-bottom: 8px;">
                            <input type="checkbox" name="affi_admin_order_details" value="1" <?= $admin_details ? 'checked' : '' ?> style="margin-right: 8px;">
                            <?= __('Show in Order Details','affiliateprosaas-integration') ?>
                        </label>
                        
                        <label style="display: flex; align-items: center; margin-bottom: 8px;">
                            <input type="checkbox" name="affi_admin_orders_list" value="1" <?= $admin_list ? 'checked' : '' ?> style="margin-right: 8px;">
                            <?= __('Show in Orders List','affiliateprosaas-integration') ?>
                        </label>
                    </div>
                    
                    <p class="affi-display-card-hint">
                        <?= __('Control how affiliate IDs appear in WooCommerce admin','affiliateprosaas-integration') ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- INFO TAB -->
        <div class="affi-content <?= $last_active_tab === 'info' ? 'active' : '' ?>" id="info">
            <h3>📋 <?= __('System Info','affiliateprosaas-integration') ?></h3>
            <div class="affi-info-table">
                <div class="affi-info-row">
                    <span>WordPress</span>
                    <span class="affi-info-value"><?= get_bloginfo('version') ?></span>
                </div>
                <div class="affi-info-row">
                    <span>Plugin</span>
                    <span class="affi-info-value"><?= $ver ?></span>
                </div>
                <div class="affi-info-row affi-info-row-last">
                    <span><?= __('Connection','affiliateprosaas-integration') ?></span>
                    <span class="affi-info-value <?= $aff['connected'] ? 'connected' : 'disconnected' ?>">
                        <?= $aff['connected'] ? 'Connected' : 'Disconnected' ?>
                    </span>
                </div>
            </div>
            <div class="affi-info-status <?= $aff['connected'] ? 'connected' : 'warning' ?>">
                <strong><?= $aff['connected'] ? '✅' : '⚠️' ?></strong> <?= esc_html($aff['message']) ?>
                <?php if(!empty($aff['base_url'])): ?>
                    <div class="affi-info-url">
                        <?= __('Base URL:','affiliateprosaas-integration') ?> <code><?= esc_html($aff['base_url']) ?></code>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Floating Action Button (FAB) -->
        <button type="submit" class="affi-fab" title="<?= esc_attr(__('Save All Settings','affiliateprosaas-integration')) ?>">
            <span class="affi-fab-icon">💾</span>
            <span class="affi-fab-text"><?= __('Save','affiliateprosaas-integration') ?></span>
        </button>
    </form>
</div>