<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Admin Permission Groups & Slugs
|--------------------------------------------------------------------------
| Grouped for clear UI. Assign to roles via Admin Panel → Users → Manage Roles.
| admin_role_id = NULL → full access | user id = 1 → always full access
| Group keys map to admin.perm_group_* translation keys when present.
*/
$config['admin_permission_group_keys'] = [
    'Dashboard & Overview'     => 'perm_group_dashboard_overview',
    'Users & Team'             => 'perm_group_users_team',
    'Reports & Financial'      => 'perm_group_reports_financial',
    'Marketing & Sales'        => 'perm_group_marketing_sales',
    'Settings & Configuration' => 'perm_group_settings_config',
    'System & Tools'           => 'perm_group_system_tools',
];
// Slug => admin.perm_label_* translation key (replace dots with underscores)
$config['admin_permission_label_keys'] = [
    'dashboard'            => 'perm_label_dashboard',
    'analytics'            => 'perm_label_analytics',
    'users'                => 'perm_label_users',
    'admin.admins'         => 'perm_label_admin_admins',
    'reports'              => 'perm_label_reports',
    'reports.orders'       => 'perm_label_reports_orders',
    'reports.s2s'          => 'perm_label_reports_s2s',
    'reports.statistics'   => 'perm_label_reports_statistics',
    'reports.transactions'=> 'perm_label_reports_transactions',
    'reports.wallet'       => 'perm_label_reports_wallet',
    'marketing'            => 'perm_label_marketing',
    'marketing.deposits'   => 'perm_label_marketing_deposits',
    'marketing.campaigns'  => 'perm_label_marketing_campaigns',
    'settings'             => 'perm_label_settings',
    'settings.payment'     => 'perm_label_settings_payment',
    'settings.language'   => 'perm_label_settings_language',
    'settings.theme'      => 'perm_label_settings_theme',
    'settings.addons'     => 'perm_label_settings_addons',
    'settings.coupons'    => 'perm_label_settings_coupons',
    'settings.mails'      => 'perm_label_settings_mails',
    'settings.backup'     => 'perm_label_settings_backup',
    'settings.system'     => 'perm_label_settings_system',
];
$config['admin_permission_groups'] = [
    'Dashboard & Overview' => [
        'dashboard' => 'Dashboard',
        'analytics' => 'Analytics',
    ],
    'Users & Team' => [
        'users'              => 'Users & Affiliates',
        'admin.admins'       => 'Manage Admin Users',
    ],
    'Reports & Financial' => [
        'reports'            => 'Reports',
        'reports.orders'     => 'Store/Membership Orders',
        'reports.s2s'        => 'S2S Analytics',
        'reports.statistics'  => 'User Statistics & Graphs',
        'reports.transactions'=> 'Transactions',
        'reports.wallet'     => 'Wallet & Withdrawals',
    ],
    'Marketing & Sales' => [
        'marketing'          => 'Marketing (Store, MLM, SaaS)',
        'marketing.deposits' => 'Vendor Deposits',
        'marketing.campaigns'=> 'Campaigns & Integration Tools',
    ],
    'Settings & Configuration' => [
        'settings'           => 'General Settings',
        'settings.payment'   => 'Payment Gateways',
        'settings.language'  => 'Languages & Translations',
        'settings.theme'     => 'Affiliate Theme',
        'settings.addons'    => 'Addons',
        'settings.coupons'   => 'Coupons',
        'settings.mails'     => 'Email Templates',
    ],
    'System & Tools' => [
        'settings.backup'    => 'Backup',
        'settings.system'   => 'System Status & Tools',
    ],
];

// Flatten for backward compatibility and role labels
$config['admin_permission_slugs'] = [];
foreach ($config['admin_permission_groups'] as $perms) {
    $config['admin_permission_slugs'] = array_merge($config['admin_permission_slugs'], $perms);
}

/*
|--------------------------------------------------------------------------
| Controller Method → Permission Slug Mapping
|--------------------------------------------------------------------------
*/
$config['admin_permission_map'] = [
    'admincontrol' => [
        'dashboard'                 => 'dashboard',
        'ajax_dashboard'            => 'dashboard',
        'analytics_dashboard'       => 'analytics',
        'analytics'                 => 'analytics',
        'userslist'                 => 'users',
        'userslisttree'             => 'users',
        'userslistmail'             => 'users',
        'usergroup'                 => 'users',
        'userview'                  => 'users',
        'tickets'                   => 'users',
        'admin_user'                => 'admin.admins',
        'admin_roles'               => 'admin.admins',
        'admin_role_form'           => 'admin.admins',
        'admin_role_delete'         => 'admin.admins',
        'import_demo_roles'         => 'admin.admins',
        'store_orders'              => 'reports.orders',
        'store_logs'                => 'reports',
        'membership_orders'         => 'reports.orders',
        's2s_analytics'             => 'reports.s2s',
        'mywallet'                  => 'reports.wallet',
        'wallet_withdraw'           => 'reports.wallet',
        'wallet_withdraw_detail'    => 'reports.wallet',
        'mass_payout'               => 'reports.wallet',
        'mass_payout_create'        => 'reports.wallet',
        'mass_payout_download'      => 'reports.wallet',
        'mass_payout_import'        => 'reports.wallet',
        'mass_payout_void'          => 'reports.wallet',
        'wallet_request_ready_mass_payout' => 'reports.wallet',
        'payment_gateway'           => 'settings.payment',
        'paymentsetting'            => 'settings',
        'language'                  => 'settings.language',
        'translation'               => 'settings.language',
        'translation_edit'          => 'settings.language',
        'update_language'           => 'settings.language',
        'language_import'           => 'settings.language',
        'language_export'           => 'settings.language',
        'language_zip_upload'       => 'settings.language',
        'get_translation'           => 'settings.language',
        'save_translation'          => 'settings.language',
        'affiliate_theme'           => 'settings.theme',
        'save_login_top_earners_block_settings' => 'settings.theme',
        'save_login_stats_block_settings'       => 'settings.theme',
        'save_login_video_block_settings'        => 'settings.theme',
        'save_login_live_pulse_block_settings'   => 'settings.theme',
        'save_login_features_block_settings'     => 'settings.theme',
        'save_login_faq_block_settings'          => 'settings.theme',
        'addons'                    => 'settings.addons',
        'coupon'                    => 'settings.coupons',
        'coupon_manage'             => 'settings.coupons',
        'save_coupon'               => 'settings.coupons',
        'coupon_delete'             => 'settings.coupons',
        'mails'                     => 'settings.mails',
        'mails_edit'                => 'settings.mails',
        'unsubscribe_list'          => 'settings.mails',
        'subscriber_list'           => 'settings.mails',
        'backup'                    => 'settings.backup',
        'system_status'             => 'settings.system',
        'affiliate_health_scores_recalculate' => 'users',
        'system_issues'             => 'settings.system',
        'geolocation_status'       => 'settings.system',
        'cron'                      => 'settings.system',
        'troubleshoot'              => 'settings.system',
        'todolist'                  => 'settings.system',
        'tutorial'                 => 'settings.system',
        'setting'                   => 'settings',
        'getSiteSetting'            => 'settings',
        'update_all_settings'       => 'settings',
        'store_setting'             => 'marketing',
        'currency_list'             => 'settings',
        'countries_and_states'      => 'settings',
        'saas_setting'              => 'marketing',
        'vendor_deposits'           => 'marketing.deposits',
        'store_dashboard'           => 'marketing',
        'mlm_settings'              => 'marketing',
        'award_level'               => 'marketing',
        'update_product_settings'   => 'marketing',
        'default_theme_settings'    => 'settings.theme',
        'default_font_settings'     => 'settings.theme',
    ],
    'integration' => [
        'integration_tools'         => 'marketing.campaigns',
        'integration_tools_form'    => 'marketing.campaigns',
        'integration_tools_form_post' => 'marketing.campaigns',
        'integration_tools_delete'  => 'marketing.campaigns',
        'integration_tools_duplicate' => 'marketing.campaigns',
        'integration_tools_load_demo' => 'marketing.campaigns',
    ],
    'membership' => [
        'membership_orders'         => 'reports.orders',
    ],
    'incomereport' => [
        'index'                     => 'reports.statistics',
    ],
    'reportcontroller' => [
        'admin_transaction'         => 'reports.transactions',
        'admin_statistics'          => 'reports.statistics',
    ],
];

/*
|--------------------------------------------------------------------------
| Admin_Api (REST) method → permission slug
|--------------------------------------------------------------------------
| Keys are PHP method names on Admin_Api. Aligns with admin_roles JSON and
| admin_permission_map (e.g. tickets → users, wallet → reports.wallet).
| profile_get is intentionally omitted so the app can always load permissions.
*/
$config['admin_api_method_permissions'] = [
    'dashboard_get'                    => 'dashboard',
    'users_get'                        => 'users',
    'user_details_get'                 => 'users',
    'update_user_status_post'          => 'users',
    'withdrawals_get'                  => 'reports.wallet',
    'update_withdrawal_status_post'    => 'reports.wallet',
    'admin_staff_get'                  => 'admin.admins',
    'admin_staff_details_get'          => 'admin.admins',
    'admin_roles_get'                  => 'admin.admins',
    'reports_get'                      => 'reports.statistics',
    'wallet_get'                       => 'reports.wallet',
    'update_profile_post'              => 'settings',
    'programs_get'                     => 'marketing.campaigns',
    'program_details_get'              => 'marketing.campaigns',
    'campaigns_get'                    => 'marketing.campaigns',
    'campaign_details_get'             => 'marketing.campaigns',
    'categories_get'                   => 'marketing.campaigns',
    'category_details_get'             => 'marketing.campaigns',
    'orders_get'                       => 'reports.orders',
    'order_details_get'                => 'reports.orders',
    'settings_summary_get'             => 'settings',
    'notifications_get'                => 'dashboard',
    'notification_details_get'         => 'dashboard',
    'notification_read_post'           => 'dashboard',
    'notifications_mark_all_read_post' => 'dashboard',
    'ticket_subjects_get'              => 'users',
    'tickets_get'                      => 'users',
    'ticket_details_get'               => 'users',
    'ticket_reply_post'                => 'users',
    'ticket_status_post'               => 'users',
    'membership_plans_get'             => 'reports.orders',
    'membership_orders_get'            => 'reports.orders',
    'click_logs_get'                   => 'reports.statistics',
];
