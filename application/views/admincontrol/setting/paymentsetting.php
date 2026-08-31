<?php
$activeTab = isset($paymentsetting_active_tab) ? $paymentsetting_active_tab : null;
$hubHidden = $activeTab ? true : false;
$formShown = $activeTab ? true : false;
?>
<div class="container-fluid">
    <div class="row g-3">
        <div class="col-12">

            <!-- Settings Hub / Landing View -->
            <div id="settingsHub" class="mb-4"<?= $hubHidden ? ' style="display:none;"' : '' ?>>
                <div class="d-flex align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="fas fa-sliders-h me-2 text-primary"></i><?= __('admin.nav_general_settings') ?></h4>
                        <p class="text-muted mb-0"><?= __('admin.settings_hub_desc') ?></p>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- General -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="site-settings" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-gear-fill fs-1 text-primary"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.site_settings') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_site_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- Communication -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="email-setting" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-envelope-fill fs-1 text-success"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.email_setting') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_email_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- Telegram -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="telegram-setting" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-telegram fs-1 text-info"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.telegram_setting') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_telegram_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- AI Helper -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="ai-helper" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-robot fs-1 text-purple" style="color: #7c3aed;"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.ai_helper') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_ai_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- Tracking -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="tracking" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-bar-chart-line-fill fs-1 text-warning"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.tracking') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_tracking_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- Fraud / Security -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="fraud" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-shield-lock-fill fs-1 text-danger"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.fraud') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_fraud_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- Google Ads -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="googleads-setting" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-google fs-1" style="color: #ea4335;"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.googleads') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_gads_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- reCAPTCHA -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="googlerecaptcha-setting" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-shield-check fs-1 text-secondary"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.googlerecaptcha') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_recaptcha_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- User Dashboard -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="user-dashboard-setting" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-people-fill fs-1 text-primary"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.user_dashboard') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_userdash_desc') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- Theme / Appearance -->
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 settings-hub-card" data-tab="theme" role="button">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"><i class="bi bi-palette-fill fs-1" style="color: #e91e63;"></i></div>
                                <h6 class="fw-bold mb-1"><?= __('admin.theme_design') ?></h6>
                                <small class="text-muted"><?= __('admin.settings_hub_theme_desc') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Form (hidden initially, shown when a tab is selected) -->
            <div id="settingsFormWrapper"<?= $formShown ? '' : ' style="display:none;"' ?>>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <!-- Back to Settings Hub button -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="backToSettingsHub">
                            <i class="fas fa-arrow-left me-1"></i> <?= __('admin.back_to_settings') ?>
                        </button>
                    </div>

                    <form class="form-horizontal" autocomplete="off" method="post" action="<?= base_url('admincontrol/paymentsetting') ?>"  enctype="multipart/form-data" id="setting-form">

                        <button type="button" class="btn btn-primary smart-save-btn" id="smart-save-btn">
                            <i class="bi bi-save me-2"></i><?= __('admin.save_changes') ?>
                        </button>

                        <div class="row">

            <ul class="nav nav-tabs nav-fill mb-4" role="tablist" id="TabsNav">
                <li class="nav-item">
                    <a class="nav-link px-2 <?= ($activeTab && $activeTab !== 'site-settings') ? '' : 'active' ?>" data-bs-toggle="tab" href="#site-settings" role="tab">
                        <i class="bi bi-gear-fill me-1"></i><?= __('admin.site_settings') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'email-setting' ? 'active' : '' ?>" data-bs-toggle="tab" href="#email-setting" role="tab">
                        <i class="bi bi-envelope-fill me-1"></i><?= __('admin.email_setting') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'ai-helper' ? 'active' : '' ?>" data-bs-toggle="tab" href="#ai-helper" role="tab">
                        <i class="bi bi-robot me-1"></i><?= __('admin.ai_helper') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'telegram-setting' ? 'active' : '' ?>" data-bs-toggle="tab" href="#telegram-setting" role="tab">
                        <i class="bi bi-telegram me-1"></i><?= __('admin.telegram_setting') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'tracking' ? 'active' : '' ?>" data-bs-toggle="tab" href="#tracking" role="tab">
                        <i class="bi bi-bar-chart-line-fill me-1"></i><?= __('admin.tracking') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'fraud' ? 'active' : '' ?>" data-bs-toggle="tab" href="#fraud" role="tab">
                        <i class="bi bi-shield-lock-fill me-1"></i><?= __('admin.fraud') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'googleads-setting' ? 'active' : '' ?>" data-bs-toggle="tab" href="#googleads-setting" role="tab">
                        <i class="bi bi-google me-1"></i><?= __('admin.googleads') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'googlerecaptcha-setting' ? 'active' : '' ?>" data-bs-toggle="tab" href="#googlerecaptcha-setting" role="tab">
                        <i class="bi bi-shield-check me-1"></i><?= __('admin.googlerecaptcha') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'user-dashboard-setting' ? 'active' : '' ?>" data-bs-toggle="tab" href="#user-dashboard-setting" role="tab">
                        <i class="bi bi-people-fill me-1"></i><?= __('admin.user_dashboard') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= $activeTab === 'theme' ? 'active' : '' ?>" data-bs-toggle="tab" href="#theme" role="tab">
                        <i class="bi bi-palette-fill me-1"></i><?= __('admin.theme_design') ?>
                    </a>
                </li>
            </ul>


<div class="col-sm-12">
    <div class="tab-content">

<div class="tab-pane fade p-3 <?= $activeTab === 'theme' ? 'show active' : '' ?>" id="theme" role="tabpanel">
    <div class="row">
        <div class="col-sm-6">
            <fieldset class="border rounded-3 shadow-sm p-3">
                <legend class="w-auto px-2"><?= __('admin.colors') ?></legend>

                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-6">
                            <h5 class="text-primary fw-semibold border-bottom pb-2 mt-3"><?= __('admin.admin_interface_colors') ?></h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button type="button" id="reset-default-colors" class="btn btn-outline-secondary btn-sm mt-2 me-2">
                                <i class="bi bi-arrow-counterclockwise me-1"></i><?= __('admin.reset_to_default') ?>
                            </button>
                            <button type="button" id="auto-style-generator" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="bi bi-palette me-1"></i><?= __('admin.auto_style_generator') ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Compact Admin Colors Section -->
                <div class="accordion mb-4" id="adminColorsAccordion">
                    <!-- 1️⃣ TOP NAVIGATION BAR -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-primary text-white fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#topNavColors">
                                <i class="fas fa-tachometer-alt me-2"></i><?= __('admin.top_navigation_colors') ?>
                                <small class="ms-2 opacity-75">(<?= __('admin.dashboard_header') ?>)</small>
                            </button>
                        </h2>
                        <div id="topNavColors" class="accordion-collapse collapse" data-bs-parent="#adminColorsAccordion">
                            <div class="accordion-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-paint-brush text-primary me-1"></i><?= __('admin.background_color') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_topbar_bg]" value="<?= $theme['admin_topbar_bg'] != '' ? $theme['admin_topbar_bg'] : '#34495e' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_topbar_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-secondary me-1"></i><?= __('admin.text_color') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_topbar_text]" value="<?= $theme['admin_topbar_text'] != '' ? $theme['admin_topbar_text'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_topbar_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2️⃣ TOP DROPDOWN MENUS -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-info text-white fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#dropdownColors">
                                <i class="fas fa-chevron-down me-2"></i><?= __('admin.dropdown_colors') ?>
                                <small class="ms-2 opacity-75">(<?= __('admin.currency_language_notifications') ?>)</small>
                            </button>
                        </h2>
                        <div id="dropdownColors" class="accordion-collapse collapse" data-bs-parent="#adminColorsAccordion">
                            <div class="accordion-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-paint-brush text-primary me-1"></i><?= __('admin.dropdown_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_dropdown_bg]" value="<?= $theme['admin_dropdown_bg'] != '' ? $theme['admin_dropdown_bg'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_dropdown_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-secondary me-1"></i><?= __('admin.dropdown_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_dropdown_text]" value="<?= $theme['admin_dropdown_text'] != '' ? $theme['admin_dropdown_text'] : '#212529' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_dropdown_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-mouse-pointer text-warning me-1"></i><?= __('admin.hover_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_dropdown_hover_bg]" value="<?= $theme['admin_dropdown_hover_bg'] != '' ? $theme['admin_dropdown_hover_bg'] : '#e3f2fd' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_dropdown_hover_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-mouse-pointer text-warning me-1"></i><?= __('admin.hover_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_dropdown_hover_text]" value="<?= $theme['admin_dropdown_hover_text'] != '' ? $theme['admin_dropdown_hover_text'] : '#1976d2' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_dropdown_hover_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3️⃣ HORIZONTAL MENU -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-success text-white fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuColors">
                                <i class="fas fa-bars me-2"></i><?= __('admin.horizontal_menu') ?>
                                <small class="ms-2 opacity-75">(<?= __('admin.navigation_menu_bar') ?>)</small>
                            </button>
                        </h2>
                        <div id="menuColors" class="accordion-collapse collapse" data-bs-parent="#adminColorsAccordion">
                            <div class="accordion-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-paint-brush text-primary me-1"></i><?= __('admin.menu_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_menu_bg]" value="<?= $theme['admin_menu_bg'] != '' ? $theme['admin_menu_bg'] : '#f8f9fa' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_menu_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-secondary me-1"></i><?= __('admin.menu_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_menu_text]" value="<?= $theme['admin_menu_text'] != '' ? $theme['admin_menu_text'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_menu_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-check-circle text-info me-1"></i><?= __('admin.active_item') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_menu_active]" value="<?= $theme['admin_menu_active'] != '' ? $theme['admin_menu_active'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_menu_active" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-mouse-pointer text-warning me-1"></i><?= __('admin.hover_color') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_menu_hover]" value="<?= $theme['admin_menu_hover'] != '' ? $theme['admin_menu_hover'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_menu_hover" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Horizontal Menu Dropdown Colors -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-paint-brush text-info me-1"></i><?= __('admin.horizontal_dropdown_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_horizontal_dropdown_bg]" value="<?= $theme['admin_horizontal_dropdown_bg'] != '' ? $theme['admin_horizontal_dropdown_bg'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_horizontal_dropdown_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-info me-1"></i><?= __('admin.horizontal_dropdown_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_horizontal_dropdown_text]" value="<?= $theme['admin_horizontal_dropdown_text'] != '' ? $theme['admin_horizontal_dropdown_text'] : '#212529' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_horizontal_dropdown_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-mouse-pointer text-info me-1"></i><?= __('admin.horizontal_dropdown_hover_bg') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_horizontal_dropdown_hover_bg]" value="<?= $theme['admin_horizontal_dropdown_hover_bg'] != '' ? $theme['admin_horizontal_dropdown_hover_bg'] : '#e3f2fd' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_horizontal_dropdown_hover_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-mouse-pointer text-info me-1"></i><?= __('admin.horizontal_dropdown_hover_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_horizontal_dropdown_hover_text]" value="<?= $theme['admin_horizontal_dropdown_hover_text'] != '' ? $theme['admin_horizontal_dropdown_hover_text'] : '#1976d2' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_horizontal_dropdown_hover_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4️⃣ OTHER ADMIN COLORS -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-secondary text-white fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#otherColors">
                                <i class="fas fa-cogs me-2"></i><?= __('admin.other_elements') ?>
                                <small class="ms-2 opacity-75">(<?= __('admin.scrollbar_footer') ?>)</small>
                            </button>
                        </h2>
                        <div id="otherColors" class="accordion-collapse collapse" data-bs-parent="#adminColorsAccordion">
                            <div class="accordion-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-scroll text-muted me-1"></i><?= __('admin.dropdown_scrollbar') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_dropdown_scrollbar]" value="<?= $theme['admin_dropdown_scrollbar'] != '' ? $theme['admin_dropdown_scrollbar'] : '#666666' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_dropdown_scrollbar" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-window-minimize text-dark me-1"></i>Footer Background
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_footer_bg]" value="<?= $theme['admin_footer_bg'] != '' ? $theme['admin_footer_bg'] : '#1a252f' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_footer_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-light me-1"></i><?= __('admin.footer_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_footer_text]" value="<?= $theme['admin_footer_text'] != '' ? $theme['admin_footer_text'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_footer_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- END OF ADMIN COLORS ACCORDION -->

                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-6">
                            <h5 class="text-primary fw-semibold border-bottom pb-2 mt-3"><?= __('admin.user_interface_colors') ?></h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button type="button" id="reset-user-default-colors" class="btn btn-outline-secondary btn-sm mt-2">
                                <i class="bi bi-arrow-counterclockwise me-1"></i><?= __('admin.reset_to_default') ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Compact User Colors Section -->
                <div class="accordion mb-4" id="userColorsAccordion">
                    <!-- 1️⃣ TOP NAVBAR -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-primary text-white fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#userTopNavColors">
                                <i class="fas fa-tachometer-alt me-2"></i><?= __('admin.top_navbar_colors') ?>
                                <small class="ms-2 opacity-75">(<?= __('admin.logo_currency_language') ?>)</small>
                            </button>
                        </h2>
                        <div id="userTopNavColors" class="accordion-collapse collapse" data-bs-parent="#userColorsAccordion">
                            <div class="accordion-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-paint-brush text-primary me-1"></i><?= __('admin.background_color') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_top_navbar_bg]" value="<?= $theme['user_top_navbar_bg'] != '' ? $theme['user_top_navbar_bg'] : '#0d6efd' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_top_navbar_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-secondary me-1"></i><?= __('admin.text_color') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_top_navbar_text]" value="<?= $theme['user_top_navbar_text'] != '' ? $theme['user_top_navbar_text'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_top_navbar_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-square text-info me-1"></i><?= __('admin.button_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_top_navbar_button_bg]" value="<?= $theme['user_top_navbar_button_bg'] != '' ? $theme['user_top_navbar_button_bg'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_top_navbar_button_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-info me-1"></i><?= __('admin.button_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_top_navbar_button_text]" value="<?= $theme['user_top_navbar_button_text'] != '' ? $theme['user_top_navbar_button_text'] : '#212529' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_top_navbar_button_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2️⃣ HORIZONTAL MENU -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-success text-white fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#userMenuColors">
                                <i class="fas fa-bars me-2"></i><?= __('admin.horizontal_menu') ?>
                                <small class="ms-2 opacity-75">(<?= __('admin.navigation_menu_bar') ?>)</small>
                            </button>
                        </h2>
                        <div id="userMenuColors" class="accordion-collapse collapse" data-bs-parent="#userColorsAccordion">
                            <div class="accordion-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-paint-brush text-primary me-1"></i><?= __('admin.menu_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_horizontal_menu_bg]" value="<?= $theme['user_horizontal_menu_bg'] != '' ? $theme['user_horizontal_menu_bg'] : '#212529' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_horizontal_menu_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-secondary me-1"></i><?= __('admin.menu_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_horizontal_menu_text]" value="<?= $theme['user_horizontal_menu_text'] != '' ? $theme['user_horizontal_menu_text'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_horizontal_menu_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-mouse-pointer text-warning me-1"></i><?= __('admin.hover_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_horizontal_menu_hover_bg]" value="<?= $theme['user_horizontal_menu_hover_bg'] != '' ? $theme['user_horizontal_menu_hover_bg'] : '#0b5ed7' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_horizontal_menu_hover_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-mouse-pointer text-warning me-1"></i><?= __('admin.hover_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_horizontal_menu_hover_text]" value="<?= $theme['user_horizontal_menu_hover_text'] != '' ? $theme['user_horizontal_menu_hover_text'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_horizontal_menu_hover_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3️⃣ DROPDOWN MENUS -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-info text-white fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#userDropdownColors">
                                <i class="fas fa-chevron-down me-2"></i><?= __('admin.dropdown_colors') ?>
                                <small class="ms-2 opacity-75">(<?= __('admin.menu_dropdowns') ?>)</small>
                            </button>
                        </h2>
                        <div id="userDropdownColors" class="accordion-collapse collapse" data-bs-parent="#userColorsAccordion">
                            <div class="accordion-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-paint-brush text-primary me-1"></i><?= __('admin.dropdown_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_dropdown_bg]" value="<?= $theme['user_dropdown_bg'] != '' ? $theme['user_dropdown_bg'] : '#ffffff' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_dropdown_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-secondary me-1"></i><?= __('admin.dropdown_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_dropdown_text]" value="<?= $theme['user_dropdown_text'] != '' ? $theme['user_dropdown_text'] : '#212529' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_dropdown_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4️⃣ FOOTER & BUTTONS -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-secondary text-white fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#userFooterColors">
                                <i class="fas fa-ellipsis-h me-2"></i><?= __('admin.other_elements') ?>
                                <small class="ms-2 opacity-75">(<?= __('admin.footer_buttons') ?>)</small>
                            </button>
                        </h2>
                        <div id="userFooterColors" class="accordion-collapse collapse" data-bs-parent="#userColorsAccordion">
                            <div class="accordion-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-paint-brush text-primary me-1"></i><?= __('admin.footer_background') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_footer_bg]" value="<?= $theme['user_footer_bg'] != '' ? $theme['user_footer_bg'] : '#f8f9fa' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_footer_bg" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-font text-secondary me-1"></i><?= __('admin.footer_text') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_footer_text]" value="<?= $theme['user_footer_text'] != '' ? $theme['user_footer_text'] : '#6c757d' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_footer_text" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-hand-pointer text-info me-1"></i><?= __('admin.button_color') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_button_color]" value="<?= $theme['user_button_color'] != '' ? $theme['user_button_color'] : '#0d6efd' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_button_color" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="fas fa-hand-pointer text-info me-1"></i><?= __('admin.button_hover_color') ?>
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-color" type="color" name="theme[user_button_hover_color]" value="<?= $theme['user_button_hover_color'] != '' ? $theme['user_button_hover_color'] : '#0b5ed7' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="user_button_hover_color" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END OF USER COLORS ACCORDION -->
            </fieldset>
        </div>

        <div class="col-sm-6">
            <fieldset class="border rounded-3 shadow-sm p-3">
                <legend class="w-auto px-2"><?= __('admin.fonts') ?></legend>
                <div class="form-group">
                    <div class="container">
                        <div class="row justify-content-center">
                            <h5 class="text-primary fw-semibold border-bottom pb-2 mt-3"><?= __('admin.font_family') ?></h5>
                        </div>
                    </div>

                        <div class="row g-2 align-items-center font-style-main mb-3">
                            <div class="col-md-4 text-md-end pe-md-2">
                                <label for="admin_side_font" class="form-label fw-semibold mb-1"><?= __('admin.admin_side') ?></label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select id="admin_side_font" class="form-select class_admin_side_font" name="site[admin_side_font]">
                                    <?php foreach ($font_families as $key => $value) { 
                                        if ($site['admin_side_font'] != '') {
                                            ?>
                                                <option value="<?= $value ?>" <?= $site['admin_side_font'] == $value ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        } else {
                                            ?>
                                                <option value="<?= $value ?>" <?= $value == 'PT Sans' ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        }
                                    } ?>
                                </select>
                                    <button class="btn btn-outline-secondary default-font-setting" type="button" value="admin_side_font" title="<?= __('admin.default') ?>">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-items-center font-style-main mb-3">
                            <div class="col-md-4 text-md-end pe-md-2">
                                <label for="user_side_font" class="form-label fw-semibold mb-1"><?= __('admin.user_side') ?></label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select id="user_side_font" class="form-select class_user_side_font" name="site[user_side_font]">
                                    <option value="Poppins" <?= $site['user_side_font'] == 'Poppins' ? 'selected' : '' ?>>Poppins</option>
                                    <?php foreach ($font_families as $key => $value) { 
                                        if ($site['user_side_font'] != '') {
                                            ?>
                                                <option value="<?= $value ?>" <?= $site['user_side_font'] == $value ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        } else {
                                            ?>
                                                <option value="<?= $value ?>" <?= $value == 'Poppins' ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        }
                                    } ?>
                                </select>
                                    <button class="btn btn-outline-secondary default-font-setting" type="button" value="user_side_font" title="<?= __('admin.default') ?>">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-items-center font-style-main mb-3">
                            <div class="col-md-4 text-md-end pe-md-2">
                                <label for="front_side_font" class="form-label fw-semibold mb-1"><?= __('admin.front_side') ?></label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select id="front_side_font" class="form-select class_front_side_font" name="site[front_side_font]">
                                    <?php foreach ($font_families as $key => $value) { 
                                        if ($site['front_side_font'] != '') {
                                            ?>
                                                <option value="<?= $value ?>" <?= $site['front_side_font'] == $value ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        } else {
                                            ?>
                                                <option value="<?= $value ?>" <?= $value == 'sans-serif' ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        }
                                    } ?>
                                </select>
                                    <button class="btn btn-outline-secondary default-font-setting" type="button" value="front_side_font" title="<?= __('admin.default') ?>">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-items-center font-style-main mb-3">
                            <div class="col-md-4 text-md-end pe-md-2">
                                <label for="cart_store_side_font" class="form-label fw-semibold mb-1"><?= __('admin.cart_store_side') ?></label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select id="cart_store_side_font" class="form-select class_cart_store_side_font" name="site[cart_store_side_font]">
                                    <option value="Jost" <?= $site['cart_store_side_font'] == 'Jost' ? 'selected' : '' ?>>Jost</option>
                                    <?php foreach ($font_families as $key => $value) { 
                                        if ($site['cart_store_side_font'] != '') {
                                            ?>
                                                <option value="<?= $value ?>" <?= $site['cart_store_side_font'] == $value ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        } else {
                                            ?>
                                                <option value="<?= $value ?>" <?= $value == 'Jost' ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        }
                                    } ?>
                                </select>
                                    <button class="btn btn-outline-secondary default-font-setting" type="button" value="cart_store_side_font" title="<?= __('admin.default') ?>">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 align-items-center font-style-main mb-3">
                            <div class="col-md-4 text-md-end pe-md-2">
                                <label for="sales_store_side_font" class="form-label fw-semibold mb-1"><?= __('admin.sales_store_side') ?></label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select id="sales_store_side_font" class="form-select class_sales_store_side_font" name="site[sales_store_side_font]">
                                    <?php foreach ($font_families as $key => $value) { 
                                        if ($site['sales_store_side_font'] != '') {
                                            ?>
                                                <option value="<?= $value ?>" <?= $site['sales_store_side_font'] == $value ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        } else {
                                            ?>
                                                <option value="<?= $value ?>" <?= $value == 'Roboto' ? 'selected' : '' ?> > <?= $key ?></option>
                                            <?php
                                        }
                                    } ?>
                                </select>
                                    <button class="btn btn-outline-secondary default-font-setting" type="button" value="sales_store_side_font" title="<?= __('admin.default') ?>">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>
            </fieldset>
            <br>

            <fieldset class="border rounded-3 shadow-sm p-3">
                <legend class="w-auto px-2"><?= __('admin.admin_login_page') ?></legend>

                <div class="row g-2 align-items-center theme-setting-row mb-3">
                    <div class="col-md-5 text-md-end pe-md-2">
                        <label class="form-label fw-semibold mb-1"><?= __('admin.admin_login_box_background_color') ?></label>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input class="form-control form-control-color" type="color" name="theme[admin_login_box_background_color]" value="<?= $theme['admin_login_box_background_color'] != '' ? $theme['admin_login_box_background_color'] : '#7a90a8' ?>">
                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_login_box_background_color" title="<?= __('admin.default') ?>">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><?= __('admin.choose_background_option') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-start">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" id="set_image_as_background" <?= (int)$theme['admin_login_background_option'] == 0 ? 'checked' : '' ?> name="theme[admin_login_background_option]" value="0">
                                        <label class="form-check-label" for="set_image_as_background"><?= __('admin.set_image_as_background') ?></label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="set_color_as_background" <?= (int)$theme['admin_login_background_option'] == 1 ? 'checked' : '' ?> name="theme[admin_login_background_option]" value="1">
                                        <label class="form-check-label" for="set_color_as_background"><?= __('admin.set_color_as_background') ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="row g-2 align-items-center theme-setting-row mb-3">
                                    <div class="col-md-5 text-md-end pe-md-2">
                                        <label class="form-label fw-semibold mb-1"><?= __('admin.admin_login_background_color') ?></label>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <input class="form-control form-control-color" type="color" name="theme[admin_login_background_color]" value="<?= $theme['admin_login_background_color'] != '' ? $theme['admin_login_background_color'] : '#5e7590' ?>">
                                            <button class="btn btn-outline-secondary default-theme-setting" type="button" value="admin_login_background_color" title="<?= __('admin.default') ?>">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 g-3">
                                    <div class="col-md-12">
                                        <h5 class="fw-semibold"><?= __('admin.admin_login_background_image') ?></h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-3 d-grid">
                                        <label for="theme_admin-login-background-image" class="btn btn-primary"><?= __('admin.choose_file') ?></label>
                                        <input id="theme_admin-login-background-image" class="form-control form-control-file d-none" type="file" name="theme_admin-login-background-image" onchange="readURLAndSetValue(this,'theme[admin-login-background-image]','#admin-login-background-image')">
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <input type="hidden" name="theme[admin-login-background-image]" value="<?= $theme['admin-login-background-image'] ?>">
                                        <?php $admin_login_background_image = $theme['admin-login-background-image'] ? base_url('assets/images/site/'. $theme['admin-login-background-image']) : base_url('assets/template/images/bg-main.png'); ?>
                                        <img id="admin-login-background-image" class="img-fluid mt-3 img-thumbnail w-25" src="<?= $admin_login_background_image ?>">
                                    </div>
                                    <div class="col-md-3 d-grid">
                                        <?php if($theme['admin-login-background-image']) { ?>
                                        <button class="btn btn-danger btn-delete-image mt-3" data-img_input="theme[admin-login-background-image]" data-img_ele="admin-login-background-image" data-img_placeholder="<?= base_url('assets/template/images/bg-main.png');?>"><i class="bi bi-trash-fill"></i></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>
    </div>
</div>

<div class="tab-pane fade p-3 <?= $activeTab === 'user-dashboard-setting' ? 'show active' : '' ?>" id="user-dashboard-setting" role="tabpanel">
    <fieldset>
        <legend><?= __('admin.user_dashboard_notice') ?></legend>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="toggle_invitation_link_id" <?= $userdashboard['invitation_link_id'] == 1 ? 'checked' : '' ?> data-setting_key="invitation_link_id" data-setting_type="userdashboard">
                    <label class="form-check-label" for="toggle_invitation_link_id"><?= __('admin.invitation_link_id') ?></label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="toggle_top_affiliate" <?= $userdashboard['top_affiliate'] == 1 ? 'checked' : '' ?> data-setting_key="top_affiliate" data-setting_type="userdashboard">
                    <label class="form-check-label" for="toggle_top_affiliate"><?= __('admin.top_affiliate') ?></label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="toggle_contact_us_page" <?= $userdashboard['contact_us_page'] == 1 ? 'checked' : '' ?> data-setting_key="contact_us_page" data-setting_type="userdashboard">
                    <label class="form-check-label" for="toggle_contact_us_page"><?= __('admin.contact_us_page') ?></label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="toggle_tickets_page" <?= $userdashboard['tickets_page'] == 1 ? 'checked' : '' ?> data-setting_key="tickets_page" data-setting_type="userdashboard">
                    <label class="form-check-label" for="toggle_tickets_page"><?= __('admin.tickets_page') ?></label>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="mt-4">
        <legend><?= __('admin.display_welcome_popup') ?></legend>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="form-label"><?= __('admin.popup_display') ?></label>
                    <select class="form-control" name="welcome[show_popup]">
                        <option value="enable" <?= $welcome['show_popup'] == 'enable' ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
                        <option value="disable" <?= $welcome['show_popup'] == 'disable' ? 'selected' : '' ?>><?= __('admin.disable') ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= __('admin.popup_title') ?></label>
                    <input placeholder="<?= __('admin.enter_page_title') ?>" id="welcome[heading]" name="welcome[heading]" value="<?php echo $welcome['heading']; ?>" class="form-control" type="text">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label"><?= __('admin.popup_video_link') ?> <span data-html="true" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-container="body" title="<h6>we support all links like:</h6> <ul><li>https://www.youtube.com/watch?v=R1StjWM_LOE&feature=youtu.be</li><li>https://www.youtu.com/R1StjWM_LOE</li><li>https://www.youtube.com/embed/R1StjWM_LOE</li></ul>"></span></label>
                    <input placeholder="<?= __('admin.enter_url_link_video') ?>" name="welcome[video_link]" id="videolink" class="form-control" value="<?php echo $welcome['video_link']; ?>" type="text">
                    <span class="text-danger" id="linkError"></span>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= __('admin.popup_content') ?></label>
                    <textarea name="welcome[content]" id="welcome[content]" class="form-control" rows="4"><?php echo $welcome['content']; ?></textarea>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group mt-3">
                    <?php
                    $video_link = $welcome['video_link'];

                    if (strpos(strtolower($video_link), 'youtube') !== false && strpos($video_link, 'embed') == false) {
                        $id = explode("v=", $video_link);
                        $video_link = 'https://www.youtube.com/embed/' . $id[1];
                    }
                    if (strpos(strtolower($video_link), 'youtu.be') !== false && strpos($video_link, 'embed') == false) {
                        $id = explode("/", $video_link);
                        $video_link = 'https://www.youtube.com/embed/' . $id[3];
                    }
                    ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="<?php echo $video_link ?>" allowfullscreen id="ifrm_videoid"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>

<div class="tab-pane fade p-3 <?= (!$activeTab || $activeTab === 'site-settings') ? 'show active' : '' ?>" id="site-settings" role="tabpanel">
    <div class="accordion" id="settingsAccordion">

    <!-- Site Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSite">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSite" aria-expanded="true" aria-controls="collapseSite">
                <i class="bi bi-gear-fill me-2"></i> <?= __('admin.site_settings') ?>
            </button>
        </h2>
        <div id="collapseSite" class="accordion-collapse collapse show" aria-labelledby="headingSite" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <!-- Maintenance Mode -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="maintenance_mode" <?= $site['maintenance_mode']==1 ? 'checked' : '' ?> data-setting_key="maintenance_mode" data-setting_type="site">
                    <label class="form-check-label" for="maintenance_mode"><?= __('admin.front_site_maintainance_mode') ?></label>
                </div>

                <!-- Store Maintenance Mode -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="store_maintenance_mode" <?= $site['store_maintenance_mode']==1 ? 'checked' : '' ?> data-setting_key="store_maintenance_mode" data-setting_type="site">
                    <label class="form-check-label" for="store_maintenance_mode"><?= __('admin.store_maintenance_mode') ?></label>
                </div>

                <!-- Enable Shorten Numbers -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings"
                           type="checkbox"
                           role="switch"
                           id="enable_shorten_numbers"
                           <?= isset($site['enable_shorten_numbers']) && $site['enable_shorten_numbers'] == 1 ? 'checked' : '' ?>
                           data-setting_key="enable_shorten_numbers"
                           data-setting_type="site"
                           data-bs-toggle="tooltip"
                           title="<?= __('admin.before_after_example') ?>">
                    <label class="form-check-label"
                           for="enable_shorten_numbers"
                           data-bs-toggle="tooltip"
                           title="<?= __('admin.before_after_example') ?>">
                        <?= __('admin.enable_shorten_numbers') ?>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Information & Meta Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSiteMeta">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSiteMeta" aria-expanded="false" aria-controls="collapseSiteMeta">
                <i class="bi bi-info-circle-fill me-2"></i><?= __('admin.site_information_and_meta') ?>
            </button>
        </h2>
        <div id="collapseSiteMeta" class="accordion-collapse collapse" aria-labelledby="headingSiteMeta" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">

                <!-- Website Name and Footer -->
                <fieldset class="mb-4">
                    <legend class="fw-bold"><?= __('admin.site_information') ?></legend>

                    <div class="mb-3">
                        <label for="websiteName" class="form-label"><?= __('admin.website_name') ?></label>
                        <input name="site[name]" id="websiteName" value="<?= $site['name']; ?>" class="form-control" type="text">
                    </div>

                    <div class="mb-3">
                        <label for="footerText" class="form-label"><?= __('admin.footer_text') ?></label>
                        <input name="site[footer]" id="footerText" value="<?= $site['footer']; ?>" class="form-control" type="text">
                    </div>
                </fieldset>

                <!-- Meta Tag Info -->
                <fieldset>
                    <legend class="fw-bold"><?= __('admin.meta_tag') ?></legend>

                    <div class="mb-3">
                        <label for="description" class="form-label"><?= __('admin.description') ?></label>
                        <input name="site[meta_description]" id="description" value="<?= $site['meta_description']; ?>" class="form-control" type="text">
                    </div>

                    <div class="mb-3">
                        <label for="keywords" class="form-label"><?= __('admin.keywords') ?></label>
                        <input name="site[meta_keywords]" id="keywords" value="<?= $site['meta_keywords']; ?>" class="form-control" type="text">
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label"><?= __('admin.author') ?></label>
                        <input name="site[meta_author]" id="author" value="<?= $site['meta_author']; ?>" class="form-control" type="text">
                    </div>
                </fieldset>

            </div>
        </div>
    </div>

    <!-- Logo Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingLogoSettings">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLogoSettings" aria-expanded="false" aria-controls="collapseLogoSettings">
                <i class="bi bi-image me-2"></i> <?= __('admin.logo_settings') ?>
            </button>
        </h2>
        <div id="collapseLogoSettings" class="accordion-collapse collapse" aria-labelledby="headingLogoSettings" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">

                <!-- Admin Side Logo -->
                <fieldset class="border rounded-3 shadow-sm p-3 mb-4">
                    <legend class="float-none w-auto px-2"><?= __('admin.admin_side_logo') ?></legend>
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-4 d-grid gap-2">
                            <button type="button" class="btn btn-primary" onclick="prepareS3Modal('input[name=\'site[admin-side-logo]\']', '#admin-side-logo')">
                                <?= __('admin.browse_amazon_s3_image') ?>
                            </button>
                            <div class="btn btn-primary">
                                <label class="m-0">
                                    <?= __('admin.browse_local_image') ?>
                                    <input type="file" name="site_admin-side-logo" class="upload d-none" onchange="readURLAndSetValue(this,'site[admin-side-logo]','#admin-side-logo')">
                                </label>
                            </div>
                            <small class="text-muted"><?= __('admin.admin_side_logo_recommended_size') ?></small>
                        </div>
                        <div class="col-sm-8 text-center">
                            <input type="hidden" name="site[admin-side-logo]" value="<?= htmlspecialchars($site['admin-side-logo'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php
                            if (strpos($site['admin-side-logo'], 'http://') === 0 || strpos($site['admin-side-logo'], 'https://') === 0) {
                                $admin_side_logo = $site['admin-side-logo'];
                            } else {
                                $admin_side_logo = !empty($site['admin-side-logo']) ? base_url('assets/images/site/' . $site['admin-side-logo']) : base_url('assets/template/images/no_image_yet.png');
                            }
                            ?>
                            <div class="position-relative d-inline-block">
                                <img id="admin-side-logo" src="<?= htmlspecialchars($admin_side_logo, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded img-thumbnail w-25">
                                <?php if (!empty($site['admin-side-logo'])) : ?>
                                    <span class="btn btn-danger btn-sm btn-delete-image position-absolute top-0 start-100 translate-middle badge rounded-pill" 
                                        data-img_input="site[admin-side-logo]" 
                                        data-img_ele="admin-side-logo" 
                                        data-img_placeholder="<?= base_url('assets/template/images/no_image_yet.png'); ?>">
                                        <i class="fa fa-trash"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold"><?= __('admin.site_setting_logo_custom_size') ?></label>
                            <select name="site[custom_logo_size]" class="form-select">
                                <option value="0"><?= __('admin.disable') ?></option>
                                <option value="1" <?= $site['custom_logo_size'] == 1 ? 'selected' : '' ?>><?= __('admin.user_dashboard') ?></option>
                            </select>
                            <small class="text-muted"><?= __('admin.enable_custom_logo_size_for_user_dashboard') ?></small>
                        </div>
                        <div class="col-sm-4 logo_cust_size_inp <?= ($site['custom_logo_size'] != 1) ? 'd-none' : '' ?>">
                            <label class="form-label"><?= __('admin.site_setting_logo_width') ?></label>
                            <input name="site[log_custom_width]" id="user_logo_width" value="<?= $site['log_custom_width']; ?>" class="form-control" type="number" placeholder="167" min="1">
                            <small class="text-muted"><?= __('admin.recommended') ?>: 167px</small>
                        </div>
                        <div class="col-sm-4 logo_cust_size_inp <?= ($site['custom_logo_size'] != 1) ? 'd-none' : '' ?>">
                            <label class="form-label"><?= __('admin.site_setting_logo_height') ?></label>
                            <input name="site[log_custom_height]" id="user_logo_height" value="<?= $site['log_custom_height']; ?>" class="form-control" type="number" placeholder="34" min="1">
                            <small class="text-muted"><?= __('admin.recommended') ?>: 34px</small>
                        </div>
                    </div>
                    <div class="row g-3 mt-2 logo_cust_size_inp <?= ($site['custom_logo_size'] != 1) ? 'd-none' : '' ?>">
                        <div class="col-sm-12 text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetUserLogoSize()">
                                <i class="fas fa-undo me-1"></i><?= __('admin.reset_to_default') ?>
                            </button>
                        </div>
                    </div>
                </fieldset>

                <!-- Front Side Themes Logo -->
                <fieldset class="border rounded-3 shadow-sm p-3 mb-4">
                    <legend class="float-none w-auto px-2"><?= __('admin.front_side_themes_logo') ?></legend>
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-2 d-grid gap-2">
                            <div class="btn btn-primary">
                                <label class="m-0">
                                    <?= __('admin.choose_file') ?>
                                    <input type="file" name="site_front-side-themes-logo" class="upload d-none" onchange="readURLAndSetValue(this,'site[front-side-themes-logo]','#front-side-themes-logo')">
                                </label>
                            </div>
                            <small class="text-muted"><?= __('admin.front_side_themes_logo_recommended_size') ?></small>
                        </div>
                        <div class="col-sm-10 text-center">
                            <input type="hidden" name="site[front-side-themes-logo]" value="<?= $site['front-side-themes-logo']; ?>">
                            <?php $front_side_themes_logo = $site['front-side-themes-logo'] ? base_url('assets/images/site/' . $site['front-side-themes-logo']) : base_url('assets/template/images/no_image_yet.png'); ?>
                            <div class="position-relative d-inline-block">
                                <img id="front-side-themes-logo" src="<?= $front_side_themes_logo ?>" class="img-fluid rounded img-thumbnail w-25">
                                <?php if (!empty($site['front-side-themes-logo'])) : ?>
                                    <span class="btn btn-danger btn-sm btn-delete-image position-absolute top-0 start-100 translate-middle badge rounded-pill" 
                                        data-img_input="site[front-side-themes-logo]" 
                                        data-img_ele="front-side-themes-logo" 
                                        data-img_placeholder="<?= base_url('assets/template/images/no_image_yet.png'); ?>">
                                        <i class="fa fa-trash"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold"><?= __('admin.site_setting_logo_custom_size') ?></label>
                            <select name="site[front_custom_logo_size]" class="form-select">
                                <option value="0"><?= __('admin.disable') ?></option>
                                <option value="1" <?= $site['front_custom_logo_size'] == 1 ? 'selected' : '' ?>><?= __('admin.front_side_themes') ?></option>
                            </select>
                            <small class="text-muted"><?= __('admin.enable_custom_logo_size_for_front_themes') ?></small>
                        </div>
                        <div class="col-sm-4 front_logo_cust_size_inp <?= ($site['front_custom_logo_size'] != 1) ? 'd-none' : '' ?>">
                            <label class="form-label"><?= __('admin.site_setting_logo_width') ?></label>
                            <input name="site[front_log_custom_width]" id="front_logo_width" value="<?= $site['front_log_custom_width']; ?>" class="form-control" type="number" placeholder="200" min="1">
                            <small class="text-muted"><?= __('admin.recommended') ?>: 200px</small>
                        </div>
                        <div class="col-sm-4 front_logo_cust_size_inp <?= ($site['front_custom_logo_size'] != 1) ? 'd-none' : '' ?>">
                            <label class="form-label"><?= __('admin.site_setting_logo_height') ?></label>
                            <input name="site[front_log_custom_height]" id="front_logo_height" value="<?= $site['front_log_custom_height']; ?>" class="form-control" type="number" placeholder="60" min="1">
                            <small class="text-muted"><?= __('admin.recommended') ?>: 60px</small>
                        </div>
                    </div>
                    <div class="row g-3 mt-2 front_logo_cust_size_inp <?= ($site['front_custom_logo_size'] != 1) ? 'd-none' : '' ?>">
                        <div class="col-sm-12 text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetFrontLogoSize()">
                                <i class="fas fa-undo me-1"></i><?= __('admin.reset_to_default') ?>
                            </button>
                        </div>
                    </div>
                </fieldset>

                <!-- Website Favicon -->
                <fieldset class="border rounded-3 shadow-sm p-3">
                    <legend class="float-none w-auto px-2"><?= __('admin.website_favicon') ?></legend>
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-2 d-grid gap-2">
                            <div class="btn btn-primary">
                                <label class="m-0">
                                    <?= __('admin.choose_file') ?>
                                    <input type="file" name="site_favicon" class="upload d-none" onchange="readURLAndSetValue(this,'site[favicon]','#site-favicon')">
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-10 text-center">
                            <input type="hidden" name="site[favicon]" value="<?= $site['favicon']; ?>">
                            <?php $img = $site['favicon'] ? base_url('assets/images/site/' . $site['favicon']) : base_url('assets/template/images/no_image_yet.png'); ?>
                            <div class="position-relative d-inline-block">
                                <img id="site-favicon" src="<?= $img ?>" class="img-fluid rounded img-thumbnail w-25">
                                <?php if (!empty($site['favicon'])) : ?>
                                    <span class="btn btn-danger btn-sm btn-delete-image position-absolute top-0 start-100 translate-middle badge rounded-pill" 
                                        data-img_input="site[favicon]" 
                                        data-img_ele="site-favicon" 
                                        data-img_placeholder="<?= base_url('assets/template/images/no_image_yet.png'); ?>">
                                        <i class="fa fa-trash"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </fieldset>

            </div>
        </div>
    </div>

    <!-- Dynamic show/hide custom logo size -->
    <script type="text/javascript">
    $(document).on('change', 'select[name="site[custom_logo_size]"]', function() {
        if($(this).val() == 1) {
            $('.logo_cust_size_inp').removeClass('d-none').slideDown(200);
        } else {
            $('.logo_cust_size_inp').slideUp(200, function() {
                $(this).addClass('d-none');
            });
        }
    });
    
    $(document).on('change', 'select[name="site[front_custom_logo_size]"]', function() {
        if($(this).val() == 1) {
            $('.front_logo_cust_size_inp').removeClass('d-none').slideDown(200);
        } else {
            $('.front_logo_cust_size_inp').slideUp(200, function() {
                $(this).addClass('d-none');
            });
        }
    });
    
    function resetUserLogoSize() {
        $('#user_logo_width').val(167);
        $('#user_logo_height').val(34);
        showToast('<?= __('admin.success') ?>', '<?= __('admin.reset_to_default_success') ?>', 'success');
    }
    
    function resetFrontLogoSize() {
        $('#front_logo_width').val(200);
        $('#front_logo_height').val(60);
        showToast('<?= __('admin.success') ?>', '<?= __('admin.reset_to_default_success') ?>', 'success');
    }
    </script>


    <!-- Site Scripts Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingScripts">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseScripts" aria-expanded="false" aria-controls="collapseScripts">
                <i class="bi bi-file-code-fill me-2"></i> <?= __('admin.scripts_settings') ?>
            </button>
        </h2>
        <div id="collapseScripts" class="accordion-collapse collapse" aria-labelledby="headingScripts" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">

                <!-- Global Script -->
                <fieldset class="mb-4">
                    <legend><?= __('admin.global_script') ?></legend>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <textarea rows="8" name="site[global_script]" class="form-control site-global_script" placeholder="<?= __('admin.insert_script_here') ?>"><?= $site['global_script']; ?></textarea>
                        </div>
                        <div class="col-sm-6">
                            <?php $global_script_status = (array)json_decode($site['global_script_status'], 1); ?>
                            <label class="form-label"><?= __('admin.show_global_script') ?></label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" <?= in_array('admin', $global_script_status) ? 'checked' : '' ?> name="site[global_script_status][]" value="admin" id="global_admin">
                                <label class="form-check-label" for="global_admin"><?= __('admin.option_admin_side') ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" <?= in_array('affiliate', $global_script_status) ? 'checked' : '' ?> name="site[global_script_status][]" value="affiliate" id="global_affiliate">
                                <label class="form-check-label" for="global_affiliate"><?= __('admin.option_affiliate_side') ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" <?= in_array('front', $global_script_status) ? 'checked' : '' ?> name="site[global_script_status][]" value="front" id="global_front">
                                <label class="form-check-label" for="global_front"><?= __('admin.option_front_side') ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" <?= in_array('store', $global_script_status) ? 'checked' : '' ?> name="site[global_script_status][]" value="store" id="global_store">
                                <label class="form-check-label" for="global_store"><?= __('admin.option_store_side') ?></label>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- Google Analytics -->
                <fieldset class="mb-4">
                    <legend><?= __('admin.google_analytics_for_site_page') ?></legend>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <textarea rows="8" name="site[google_analytics]" class="form-control site-google_analytics" placeholder="<?= __('admin.insert_script_here') ?>"><?= $site['google_analytics']; ?></textarea>
                            <a class="d-block mt-2" href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank"><?= __('admin.get_analytics_code') ?></a>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label"><?= __('admin.example') ?></label>
                            <img class="img-fluid w-100" src="<?= base_url('assets/images/google_analytics.png') ?>">
                        </div>
                    </div>
                </fieldset>

                <!-- Facebook Pixel -->
                <fieldset class="mb-4">
                    <legend><?= __('admin.faceboook_pixel_for_site_page') ?></legend>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label"><?= __('admin.faceboook_pixel_for_site_page') ?></label>
                            <textarea rows="8" name="site[faceboook_pixel]" class="form-control site-faceboook_pixel" placeholder="<?= __('admin.insert_script_here') ?>"><?= $site['faceboook_pixel']; ?></textarea>
                            <a class="d-block mt-2" href="https://developers.facebook.com/docs/facebook-pixel/implementation" target="_blank"><?= __('admin.get_facebook_pixel_code') ?></a>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label"><?= __('admin.example') ?></label>
                            <img class="img-fluid w-100" src="<?= base_url('assets/images/faceboook_pixel.png') ?>">
                        </div>
                    </div>
                </fieldset>

                <!-- Facebook Messenger Chat Plugin (Deprecated) -->
                <fieldset class="mb-4 border border-warning p-3 rounded">
                    <legend class="text-warning">
                        ⚠️ <?= __('admin.facebook_chat_plugin_script') ?> (<?= __('admin.deprecated') ?>)
                    </legend>

                    <div class="bg-warning bg-opacity-25 text-warning p-3 rounded mb-3 small">
                        <?= __('admin.facebook_chat_plugin_warning') ?><br><br>
                        <strong><?= __('admin.facebook_chat_plugin_action_required') ?></strong><br>
                        <?= __('admin.remove_old_script_instruction') ?>
                    </div>

                    <?php $fbmessager_status = (array)json_decode($site['fbmessager_status'], 1); ?>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?= __('admin.insert_script_here') ?></label>
                                <textarea rows="8" name="site[fbmessager_script]" class="form-control site-fbmessager_script" placeholder="<?= __('admin.insert_script_here') ?>"><?= $site['fbmessager_script']; ?></textarea>
                            </div>

                            <div class="form-group mt-3">
                                <label class="form-label"><?= __('admin.show_facebook_chat_code') ?> (<?= __('admin.applies_to_both') ?>)</label>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" <?= in_array('admin', $fbmessager_status) ? 'checked' : '' ?> name="site[fbmessager_status][]" value="admin" id="fbmessenger_admin">
                                    <label class="form-check-label" for="fbmessenger_admin"><?= __('admin.option_admin_side') ?></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" <?= in_array('affiliate', $fbmessager_status) ? 'checked' : '' ?> name="site[fbmessager_status][]" value="affiliate" id="fbmessenger_affiliate">
                                    <label class="form-check-label" for="fbmessenger_affiliate"><?= __('admin.option_affiliate_side') ?></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" <?= in_array('front', $fbmessager_status) ? 'checked' : '' ?> name="site[fbmessager_status][]" value="front" id="fbmessenger_front">
                                    <label class="form-check-label" for="fbmessenger_front"><?= __('admin.option_front_side') ?></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" <?= in_array('store', $fbmessager_status) ? 'checked' : '' ?> name="site[fbmessager_status][]" value="store" id="fbmessenger_store">
                                    <label class="form-check-label" for="fbmessenger_store"><?= __('admin.option_store_side') ?></label>
                                </div>

                                <small class="text-danger d-block mt-2"><?= __('admin.uncheck_all_instruction') ?></small>
                            </div>

                            <a class="d-block mt-2" href="https://developers.facebook.com/docs/messenger-platform/discovery/facebook-chat-plugin/#setup_tool" target="_blank"><?= __('admin.get_facebook_chat_code') ?></a>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label"><?= __('admin.example') ?></label>
                            <img class="img-fluid w-100 opacity-50" src="<?= base_url('assets/images/fb_chat_script.png') ?>">
                        </div>
                    </div>
                </fieldset>

                <!-- Messenger Chat Link (Recommended New Way) -->
                <fieldset class="mb-4 border p-3 rounded">
                    <legend class="text-success">
                        <?= __('admin.messenger_chat_link') ?> (<?= __('admin.recommended') ?>)
                    </legend>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?= __('admin.enter_messenger_page_link') ?></label>
                                <input type="text" name="site[messenger_chat_link]" class="form-control" placeholder="https://m.me/YOUR_PAGE_ID" value="<?= isset($site['messenger_chat_link']) ? $site['messenger_chat_link'] : '' ?>">
                                <small class="form-text text-muted mt-1"><?= __('admin.example') ?>: https://m.me/yourpage</small>
                                <a class="d-block mt-2" href="https://www.facebook.com/help/325807937506242" target="_blank"><?= __('admin.learn_about_messenger_links') ?></a>
                            </div>

                            <div class="form-group mt-4">
                                <label class="form-label"><?= __('admin.messenger_button_position') ?></label>
                                <select name="site[messenger_button_position]" class="form-select">
                                    <option value="bottom-right" <?= (isset($site['messenger_button_position']) && $site['messenger_button_position'] == 'bottom-right') ? 'selected' : '' ?>>Bottom Right</option>
                                    <option value="bottom-left" <?= (isset($site['messenger_button_position']) && $site['messenger_button_position'] == 'bottom-left') ? 'selected' : '' ?>>Bottom Left</option>
                                    <option value="top-right" <?= (isset($site['messenger_button_position']) && $site['messenger_button_position'] == 'top-right') ? 'selected' : '' ?>>Top Right</option>
                                    <option value="top-left" <?= (isset($site['messenger_button_position']) && $site['messenger_button_position'] == 'top-left') ? 'selected' : '' ?>>Top Left</option>
                                </select>
                            </div>

                            <div class="form-group mt-4">
                                <label class="form-label"><?= __('admin.messenger_icon_style') ?></label>
                                <select name="site[messenger_icon_style]" class="form-select" id="messenger_icon_style_select" onchange="updateMessengerIconPreview()">
                                    <option value="icon1" <?= (isset($site['messenger_icon_style']) && $site['messenger_icon_style'] == 'icon1') ? 'selected' : '' ?>>Messenger Icon 1</option>
                                    <option value="icon2" <?= (isset($site['messenger_icon_style']) && $site['messenger_icon_style'] == 'icon2') ? 'selected' : '' ?>>Messenger Icon 2</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 text-center">
                            <label class="form-label"><?= __('admin.preview') ?></label>
                            <div class="d-inline-block p-2 border rounded bg-light mt-2">
                                <?php if (!empty($site['messenger_chat_link'])): ?>
                                    <a href="<?= $site['messenger_chat_link'] ?>" target="_blank" id="messengerPreviewButton" class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary shadow p-3">
                                        <img id="messengerPreviewIcon" src="<?= base_url('assets/images/' . (isset($site['messenger_icon_style']) && $site['messenger_icon_style'] == 'icon2' ? 'messenger-icon2.png' : 'messenger-icon1.png')) ?>" alt="Messenger" class="img-fluid w-50">
                                    </a>
                                <?php else: ?>
                                    <div class="text-muted small"><?= __('admin.no_chat_link_defined') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <script>
                function updateMessengerIconPreview() {
                    var selectedIcon = document.getElementById('messenger_icon_style_select').value;
                    var iconSrc = '';
                    if (selectedIcon === 'icon2') {
                        iconSrc = '<?= base_url('assets/images/messenger-icon2.png') ?>';
                    } else {
                        iconSrc = '<?= base_url('assets/images/messenger-icon1.png') ?>';
                    }
                    document.getElementById('messengerPreviewIcon').src = iconSrc;
                }
                </script>

            </div>
        </div>
    </div>


    <!-- Security Settings -->
    <?php   
    $site_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REDIRECT_URL'];
    $root = rtrim($site_url, 'admincontrol/paymentsetting/');
    ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSecurity">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSecurity" aria-expanded="false" aria-controls="collapseSecurity">
                <i class="bi bi-shield-lock-fill me-2"></i> <?= __('admin.security_settings') ?>
            </button>
        </h2>
        <div id="collapseSecurity" class="accordion-collapse collapse" aria-labelledby="headingSecurity" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">

                <!-- Change Admin URL -->
                <div class="mb-4">
                    <label class="form-label"><?= __('admin.change_admin_url') ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?php echo $root.'/' ?></span>
                        </div>
                        <input type="text" class="form-control" name="security[admin_url]" value="<?= $security['admin_url'] ?? 'admin'; ?>">
                        <span class="input-group-text">
                            <a href="javascript:void(0)" class="set-default-admin-url"><?= __('admin.set_default') ?></a>
                        </span>
                    </div>
                </div>

                <!-- Change Front URL -->
                <div class="mb-4">
                    <label class="form-label"><?= __('admin.change_front_url') ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?php echo $root.'/' ?></span>
                        </div>
                        <input type="text" class="form-control" name="security[front_url]" value="<?= $security['front_url']; ?>">
                        <span class="input-group-text">
                            <a href="javascript:void(0)" class="set-default-front-url"><?= __('admin.set_default') ?></a>
                        </span>
                    </div>
                </div>

                <!-- Force SSL -->
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="force_ssl" <?= $security['force_ssl'] == 1 ? 'checked' : '' ?> data-setting_key="force_ssl" data-setting_type="security">
                    <label class="form-check-label" for="force_ssl"><?= __('admin.force_ssl') ?></label>
                </div>

                <!-- Admin OTP Settings -->
                <div class="card mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-key-fill me-1"></i> <?= __('admin.admin_otp_settings') ?>
                        </h5>
                    </div>
                    <div class="card-body">

                        <!-- Enable OTP -->
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input update_all_settings"
                                   type="checkbox"
                                   role="switch"
                                   id="otp_admin_login"
                                   <?= isset($security['otp_admin_login']) && $security['otp_admin_login'] == 1 ? 'checked' : '' ?>
                                   data-setting_key="otp_admin_login"
                                   data-setting_type="security">
                            <label class="form-check-label" for="otp_admin_login"><?= __('admin.enable_otp_for_admin_login') ?></label>
                        </div>

                        <!-- Max OTP Attempts -->
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.otp_admin_max_attempts') ?></label>
                            <input 
                                type="number" 
                                class="form-control update_all_settings" 
                                name="security[otp_admin_max_attempts]"
                                min="1" max="10"
                                step="1"
                                value="<?= isset($security['otp_admin_max_attempts']) ? (int)$security['otp_admin_max_attempts'] : 3 ?>"
                                data-setting_key="otp_admin_max_attempts" 
                                data-setting_type="security"
                            >
                        </div>

                        <!-- OTP Cooldown Time -->
                        <div class="mb-3">
                            <label class="form-label">
                                <?= __('admin.otp_admin_cooldown_seconds') ?> 
                                <small class="text-muted">(<?= __('admin.in_seconds') ?>)</small>
                            </label>
                            <input 
                                type="number" 
                                class="form-control update_all_settings" 
                                name="security[otp_admin_cooldown_seconds]"
                                min="30" max="600"
                                step="1"
                                value="<?= isset($security['otp_admin_cooldown_seconds']) ? (int)$security['otp_admin_cooldown_seconds'] : 180 ?>"
                                data-setting_key="otp_admin_cooldown_seconds" 
                                data-setting_type="security"
                            >
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- AI Suggestions Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingAISuggestions">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAISuggestions" aria-expanded="false" aria-controls="collapseAISuggestions">
                <i class="bi bi-cpu me-2"></i> <?= __('admin.ai_suggestions_settings') ?>
            </button>
        </h2>
        <div id="collapseAISuggestions" class="accordion-collapse collapse" aria-labelledby="headingAISuggestions" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">

                <!-- Enable AI Suggestions -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="ai_suggestion_enabled"
                        <?= isset($userdashboard['ai_suggestion_enabled']) && $userdashboard['ai_suggestion_enabled'] == 1 ? 'checked' : '' ?>
                        data-setting_key="ai_suggestion_enabled"
                        data-setting_type="userdashboard">
                    <label class="form-check-label" for="ai_suggestion_enabled"><?= __('admin.enable_ai_suggestions') ?></label>
                </div>
                <small class="form-text text-muted d-block mb-3"><?= __('admin.ai_suggestions_desc') ?></small>

            </div>
        </div>
    </div>

    <!-- Terms & Condition Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTerms">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTerms" aria-expanded="false" aria-controls="collapseTerms">
                <i class="bi bi-file-text me-2"></i> <?= __('admin.terms_and_condition') ?>
            </button>
        </h2>
        <div id="collapseTerms" class="accordion-collapse collapse" aria-labelledby="headingTerms" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">

                <!-- Language Selection -->
                <div class="mb-3">
                    <label class="form-label"><?= __('admin.select_language') ?></label>
                    <select class="form-select" name="tnc[language_id]" id="drpLanguage" onchange="return changeLanguage();">
                        <?php 
                        if(isset($languages))
                        {
                            $language_id=1;
                            foreach($languages as $language)
                            {?>
                            <option <?php 
                            if($language['is_default']==1) {echo 'selected';} ?> value="<?=$language['id']?>"><?=$language['name'] ?></option>
                          
                        <?php  }     
                        }?>
                    </select>
                </div>

                <!-- Page Title -->
                <div class="mb-3">
                    <label class="form-label"><?= __('admin.page_title') ?></label>
                    <input placeholder="<?= __('admin.enter_page_title') ?>" name="tnc[heading]" value="<?php echo $tnc['heading']; ?>" class="form-control" type="text">
                </div>

                <!-- Page Content -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-medium"><?= __('admin.page_content') ?></label>
                        <?= ai_helper_button('terms_content', 'tnc[content]', ['size' => 'sm', 'text' => 'AI Generate']) ?>
                    </div>
                    <textarea name="tnc[content]" id="tnc[content]" class="form-control summernote-img"><?php echo $tnc['content']; ?></textarea>
                </div>

            </div>
        </div>
    </div>


    <!-- Amazon S3 Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingStore">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStore" aria-expanded="false" aria-controls="collapseStore">
                <i class="bi bi-cloud-upload-fill me-2"></i> <?= __('admin.amazon_s3_storage') ?>
            </button>
        </h2>
        <div id="collapseStore" class="accordion-collapse collapse" aria-labelledby="headingStore" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <!-- S3 Bucket Name -->
                <div class="mb-3">
                    <label for="s3_bucket_name" class="form-label"><?= __('admin.s3_bucket_name') ?></label>
                    <input name="s3_storage[s3_bucket_name]" value="<?= $s3_storage['s3_bucket_name']; ?>" class="form-control" id="s3_bucket_name" placeholder="<?= __('admin.enter_your_s3_bucket_name') ?>" type="text">
                </div>

                <!-- S3 Region -->
                <div class="mb-3">
                    <label for="s3_region" class="form-label"><?= __('admin.s3_region') ?></label>
                    <input name="s3_storage[s3_region]" value="<?= $s3_storage['s3_region']; ?>" class="form-control" id="s3_region" placeholder="<?= __('admin.enter_your_s3_region') ?>" type="text">
                </div>
            </div>
        </div>
    </div>

    <!-- Language Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingLanguage">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLanguage" aria-expanded="false" aria-controls="collapseLanguage">
                <i class="bi bi-translate me-2"></i> <?= __('admin.language_settings') ?>
            </button>
        </h2>
        <div id="collapseLanguage" class="accordion-collapse collapse" aria-labelledby="headingLanguage" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <!-- Show Language Dropdown -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input update_all_settings" type="checkbox" role="switch" id="language_status" <?= $store['language_status']==1 ? 'checked' : '' ?> data-setting_key="language_status" data-setting_type="store">
                    <label class="form-check-label" for="language_status"><?= __('admin.show_language_dropdown') ?></label>
                </div>
            </div>
        </div>
    </div>

    <!-- Currency Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingCurrency">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCurrency" aria-expanded="false" aria-controls="collapseCurrency">
                <i class="bi bi-currency-exchange me-2"></i> <?= __('admin.currency_settings') ?>
            </button>
        </h2>
        <div id="collapseCurrency" class="accordion-collapse collapse" aria-labelledby="headingCurrency" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <!-- Hide Currency From -->
                <p class="text-muted small mb-3"><?= __('admin.hide_currency_from') ?></p>
                <?php
                    $hcf = isset($site['hide_currency_from']) && !empty($site['hide_currency_from']) ? explode(',', $site['hide_currency_from']) : [];
                ?>
                <div class="d-flex flex-column gap-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input hcf-toggle" type="checkbox" role="switch"
                               id="hide_currency_from_admin" name="site[hide_currency_from][]" value="admin"
                               <?= in_array('admin', $hcf) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="hide_currency_from_admin">
                            <i class="bi bi-speedometer2 me-1"></i><?= __('admin.admin_dashboard') ?>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input hcf-toggle" type="checkbox" role="switch"
                               id="hide_currency_from_user" name="site[hide_currency_from][]" value="user"
                               <?= in_array('user', $hcf) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="hide_currency_from_user">
                            <i class="bi bi-person me-1"></i><?= __('admin.user_dashboard') ?>
                        </label>
                    </div>
                </div>
                <script>
                (function() {
                    function syncHideCurrency() {
                        var vals = [];
                        document.querySelectorAll('.hcf-toggle:checked').forEach(function(el) { vals.push(el.value); });
                        $.ajax({
                            url: '<?= base_url("admincontrol/update_all_settings") ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: { action: 'update_all_settings', status: vals.join(','), setting_key: 'hide_currency_from', setting_type: 'site' },
                            success: function(json) {
                                if (json.success) { showPrintMessage(json.success, 'success'); }
                            }
                        });
                    }
                    document.querySelectorAll('.hcf-toggle').forEach(function(el) {
                        el.addEventListener('change', syncHideCurrency);
                    });
                })();
                </script>
            </div>
        </div>
    </div>

    <?php
        $zones_array = array();
        $timestamp = time();
        foreach(timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$zone] = date('P', $timestamp) . " {$zone} ";
        }
     ?>

    <!-- Time Zone Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTimeZone">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTimeZone" aria-expanded="false" aria-controls="collapseTimeZone">
                <i class="bi bi-clock-history me-2"></i> <?= __('admin.time_zone') ?>
            </button>
        </h2>
        <div id="collapseTimeZone" class="accordion-collapse collapse" aria-labelledby="headingTimeZone" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <label for="time_zone" class="form-label"><?= __('admin.select_time_zone') ?></label>
                <select class="form-select" name="site[time_zone]" id="time_zone">
                    <?php foreach ($zones_array as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $site['time_zone'] == $key ? 'selected' : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Registration Form Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingRegistrationForm">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRegistrationForm" aria-expanded="false" aria-controls="collapseRegistrationForm">
                <i class="bi bi-person-plus-fill me-2"></i> <?= __('admin.registration_form') ?>
            </button>
        </h2>
        <div id="collapseRegistrationForm" class="accordion-collapse collapse" aria-labelledby="headingRegistrationForm" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <p class="text-muted small mb-3"><?= __('admin.select_registration_status') ?></p>
                <div class="d-flex flex-column gap-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input reg-toggle" type="checkbox" role="switch"
                               id="reg_toggle_affiliate"
                               <?= in_array((int)$store['registration_status'], [1, 3]) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="reg_toggle_affiliate">
                            <i class="bi bi-people me-1"></i><?= __('admin.affiliate_registration') ?>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input reg-toggle" type="checkbox" role="switch"
                               id="reg_toggle_vendor"
                               <?= in_array((int)$store['registration_status'], [1, 2]) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="reg_toggle_vendor">
                            <i class="bi bi-shop me-1"></i><?= __('admin.vendor_registration') ?>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="store[registration_status]" id="registration_status"
                       value="<?= (int)$store['registration_status'] ?>">
                <script>
                (function() {
                    function syncRegStatus() {
                        var aff = document.getElementById('reg_toggle_affiliate').checked;
                        var ven = document.getElementById('reg_toggle_vendor').checked;
                        var val = aff && ven ? 1 : !aff && ven ? 2 : aff && !ven ? 3 : 0;
                        document.getElementById('registration_status').value = val;
                        $.ajax({
                            url: '<?= base_url("admincontrol/update_all_settings") ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: { action: 'update_all_settings', status: val, setting_key: 'registration_status', setting_type: 'store' },
                            success: function(json) {
                                if (json.success) { showPrintMessage(json.success, 'success'); }
                            }
                        });
                    }
                    document.getElementById('reg_toggle_affiliate').addEventListener('change', syncRegStatus);
                    document.getElementById('reg_toggle_vendor').addEventListener('change', syncRegStatus);
                })();
                </script>
            </div>
        </div>
    </div>

    <!-- Notification Email Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingNotificationEmail">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNotificationEmail" aria-expanded="false" aria-controls="collapseNotificationEmail">
                <i class="bi bi-envelope-fill me-2"></i> <?= __('admin.notification_email') ?>
            </button>
        </h2>
        <div id="collapseNotificationEmail" class="accordion-collapse collapse" aria-labelledby="headingNotificationEmail" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <label for="notify_email" class="form-label"><?= __('admin.enter_notification_email') ?></label>
                <input name="site[notify_email]" value="<?php echo $site['notify_email']; ?>" class="form-control" id="notify_email" type="email">
            </div>
        </div>
    </div>

    <!-- Session Timeout Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSessionTimeout">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSessionTimeout" aria-expanded="false" aria-controls="collapseSessionTimeout">
                <i class="bi bi-hourglass-split me-2"></i> <?= __('admin.admin_session_timeout') ?>
            </button>
        </h2>
        <div id="collapseSessionTimeout" class="accordion-collapse collapse" aria-labelledby="headingSessionTimeout" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <label for="session_timeout" class="form-label"><?= __('admin.session_timeout_timing_in_seconds') ?></label>
                <input name="site[session_timeout]" value="<?php echo $site['session_timeout']; ?>" class="form-control" id="session_timeout" placeholder="<?= __('admin.default_timeout_is_1800_seconds') ?>" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" type="number" maxlength="6" min = "1" max = "999999">
            </div>
        </div>
    </div>

    <!-- User Session Timeout Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingUserSessionTimeout">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUserSessionTimeout" aria-expanded="false" aria-controls="collapseUserSessionTimeout">
                <i class="bi bi-hourglass-bottom me-2"></i> <?= __('admin.user_session_timeout') ?>
            </button>
        </h2>
        <div id="collapseUserSessionTimeout" class="accordion-collapse collapse" aria-labelledby="headingUserSessionTimeout" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <label for="user_session_timeout" class="form-label"><?= __('admin.user_session_timeout_timing_in_seconds') ?></label>
                <input name="site[user_session_timeout]" value="<?php echo $site['user_session_timeout']; ?>" class="form-control" id="user_session_timeout" placeholder="<?= __('admin.default_timeout_is_1800_seconds') ?>" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" type="number" maxlength="6" min = "1" max = "999999">
            </div>
        </div>
    </div>

    <!-- Notification Sound Settings -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingNotificationSound">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNotificationSound" aria-expanded="false" aria-controls="collapseNotificationSound">
                <i class="bi bi-volume-up-fill me-2"></i> <?= __('admin.notification_sound') ?>
            </button>
        </h2>
        <div id="collapseNotificationSound" class="accordion-collapse collapse" aria-labelledby="headingNotificationSound" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
                <?php 
                    $arrFiles = array();
                    $handle = opendir('assets/notify');
                    if ($handle) {
                        while (($entry = readdir($handle)) !== FALSE) {
                            $arrFiles[] = $entry;
                        }
                    }
                    
                    foreach ($arrFiles as $file) {
                        $allowed = array('mp3', 'mp4');
                        $filename = $file;
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $checked = '';
                    
                        if ($audio_sound != '') {
                            if ($filename == $audio_sound) {
                                $checked = 'checked';
                            }
                        }
                    
                        if (in_array($ext, $allowed)) {
                            ?>
                            <div class="sound-main mb-2">
                                <input type="radio" name="site[notification_sound]" value="<?= $file ?>" <?= $checked ?>>
                                <div class="audio-file">
                                    <?php  echo $file; ?>
                                </div>
                                <audio class="audio-control" controls>
                                    <source src="<?= base_url('/assets/notify/'.$file) ?>" type="audio/mpeg">
                                </audio>
                            </div>
                            <?php   
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    </div>
</div>

<div class="tab-pane fade p-3 <?= $activeTab === 'email-setting' ? 'show active' : '' ?>" id="email-setting" role="tabpanel">

<!-- Registration Settings on top -->
<fieldset class="mb-4">
    <legend class="fw-bold"><?= __('admin.registration_settings') ?></legend>

    <!-- User Account Mail Verification -->
    <div class="form-check form-switch mb-3">
        <input class="form-check-input update_all_settings" type="checkbox" id="mail_verifiy" <?= $store['mail_verifiy']==1 ? 'checked' : '' ?> data-setting_key="mail_verifiy" data-setting_type="store">
        <label class="form-check-label" for="mail_verifiy"><?= __('admin.user_account_mail_verification') ?></label>
    </div>

    <!-- Approval For Registration -->
    <div class="form-check form-switch mb-4">
        <input class="form-check-input update_all_settings regisapproval" type="checkbox" id="registration_approval" <?= $store['registration_approval']==1 ? 'checked' : '' ?> data-setting_key="registration_approval" data-setting_type="store">
        <label class="form-check-label" for="registration_approval"><?= __('admin.approval_for_registration') ?></label>
    </div>

    <button type="button" class="btn btn-outline-secondary btn-sm float-end ms-2" data-bs-toggle="modal" data-bs-target="#smtpCpanelHelpModal">
        <i class="fas fa-server text-dark me-1"></i> <?= __('admin.smtp_cpanel_setup_guide') ?>
    </button>
    <button type="button" class="btn btn-outline-secondary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#smtpGmailHelpModal">
        <i class="fab fa-google text-danger me-1"></i> <?= __('admin.smtp_gmail_setup_guide') ?>
    </button>
</fieldset>

            <!-- Email Settings -->
            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.send_email') ?></label>
                <select class="form-control" name="email[mail_send_option]">
                    <option value="enable" <?= $email['mail_send_option'] == 'enable' ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
                    <option value="disable" <?= $email['mail_send_option'] == 'disable' ? 'selected' : '' ?>><?= __('admin.disable') ?></option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.mail_type') ?></label>
                <select class="form-control" name="email[mail_type]">
                    <option value="smtp" <?= $email['mail_type'] == 'smtp' ? 'selected' : '' ?>><?= __('admin.smtp') ?></option>
                    <option value="php_mailer" <?= $email['mail_type'] == 'php_mailer' ? 'selected' : '' ?>><?= __('admin.php_mailer') ?></option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.from_email') ?></label>
                <input name="email[from_email]" value="<?= $email['from_email']; ?>" class="form-control" type="text">
            </div>

            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.from_name') ?></label>
                <input name="email[from_name]" value="<?= $email['from_name']; ?>" class="form-control" type="text">
            </div>

            <div class="form-group mb-3 for-smtp-mail">
                <label class="control-label"><?= __('admin.smtp_hostname') ?></label>
                <input name="email[smtp_hostname]" value="<?= $email['smtp_hostname']; ?>" class="form-control" type="text">
            </div>

            <div class="form-group mb-3 for-smtp-mail">
                <label class="control-label"><?= __('admin.smtp_username') ?></label>
                <input name="email[smtp_username]" value="<?= $email['smtp_username']; ?>" class="form-control" type="text">
            </div>

            <div class="form-group mb-3 for-smtp-mail">
                <label class="control-label"><?= __('admin.smtp_password') ?></label>
                <div class="input-group password-group">
                    <input readonly="" onfocus="this.removeAttribute('readonly');" onblur="this.setAttribute('readonly','readonly');" autocomplete="off" type="password" class="form-control" name="email[smtp_password]" value="<?= $email['smtp_password']; ?>">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></button>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3 for-smtp-mail">
                <label class="control-label"><?= __('admin.smtp_port') ?></label>
                <input name="email[smtp_port]" value="<?= $email['smtp_port']; ?>" class="form-control" type="text">
            </div>

            <div class="form-group mb-3 for-smtp-mail">
                <label class="control-label"><?= __('admin.smtp_crypto') ?></label>
                <select class="form-control" name="email[smtp_crypto]">
                    <option value=""><?= __('admin.none') ?></option>
                    <option value="tls" <?= $email['smtp_crypto'] == 'tls' ? 'selected' : '' ?>><?= __('admin.tls') ?></option>
                    <option value="ssl" <?= $email['smtp_crypto'] == 'ssl' ? 'selected' : '' ?>><?= __('admin.ssl') ?></option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.unsubscribed_page_title') ?></label>
                <input name="email[unsubscribed_page_title]" value="<?= $email['unsubscribed_page_title']; ?>" class="form-control" type="text">
            </div>

            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.unsubscribed_page_message') ?></label>
                <textarea name="email[unsubscribed_page_message]" class="form-control"><?= $email['unsubscribed_page_message']; ?></textarea>
            </div>

            <fieldset class="border-top pt-3">
                <legend class="fw-bold"><?= __('admin.testing') ?></legend>
                <div class="input-group mb-3">
                    <input type="text" class="form-control testingemail" placeholder="<?= __('admin.test_email_send_on') ?>">
                    <div class="input-group-append cp">
                        <span class="btn btn-primary input-group-text send-test-mail"><?= __('admin.send_test_mail') ?></span>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="tab-pane fade p-3 <?= $activeTab === 'ai-helper' ? 'show active' : '' ?>" id="ai-helper" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-robot me-2"></i><?= __('admin.ai_helper_management') ?>
                            </h5>
                            <small class="text-light"><?= __('admin.ai_helper_management_desc') ?></small>
                        </div>
                        <div class="card-body">
                            <!-- Header with Status -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h6 class="text-primary mb-1">
                                        <i class="bi bi-robot me-2"></i> <?= __('admin.my_ai_usage') ?>
                                        <span class="badge bg-info ms-2" id="current-ai-provider">
                                            <?= isset($ai_helper['ai_provider']) ? ucfirst($ai_helper['ai_provider']) : 'OpenAI' ?>
                                        </span>
                                    </h6>
                                    <p class="text-muted mb-0 small">
                                        <?= __('admin.my_ai_usage_desc') ?> 
                                        <span class="text-info">• Currently using <strong id="provider-display"><?= isset($ai_helper['ai_provider']) ? ucfirst($ai_helper['ai_provider']) : 'OpenAI' ?></strong></span>
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input update_all_settings" 
                                               type="checkbox" 
                                               id="ai_helper_enabled_quick"
                                               data-setting_key="ai_helper_enabled" 
                                               data-setting_type="ai_helper"
                                               <?= isset($ai_helper['ai_helper_enabled']) && $ai_helper['ai_helper_enabled'] == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-medium small" for="ai_helper_enabled_quick">
                                            <?= __('admin.enable_ai_helper') ?>
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input update_all_settings" 
                                               type="checkbox" 
                                               id="use_real_ai_quick"
                                               data-setting_key="use_real_ai" 
                                               data-setting_type="ai_helper"
                                               <?= isset($ai_helper['use_real_ai']) ? ($ai_helper['use_real_ai'] == 1 ? 'checked' : '') : 'checked' ?>>
                                        <label class="form-check-label fw-medium small" for="use_real_ai_quick">
                                            <?= __('admin.use_real_ai') ?>
                                        </label>
                                    </div>
                                    <button type="button" id="refresh-usage-data" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-arrow-repeat me-1"></i> <?= __('admin.refresh_usage_data') ?>
                                    </button>
                                </div>
                            </div>

                            <!-- Main Dashboard Layout -->
                            <div class="row mb-4">
                                <!-- Left Column - Usage Overview -->
                                <div class="col-lg-8">
                                    <!-- Quick Stats Cards -->
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                                            <i class="bi bi-lightning-charge-fill text-primary fs-4"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="mb-0" id="admin-requests-today">-</h3>
                                                            <small class="text-muted"><?= __('admin.my_requests_today') ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                                            <i class="bi bi-calendar-month text-success fs-4"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="mb-0" id="admin-requests-month">-</h3>
                                                            <small class="text-muted"><?= __('admin.my_requests_month') ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Usage Progress -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-transparent border-bottom-0 pb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0">
                                                    <i class="bi bi-speedometer2 me-2"></i> <?= __('admin.usage_limits') ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-info-circle me-1"></i>Combined across all providers
                                                </small>
                                            </div>
                                        </div>
                                        <div class="card-body pt-3">
                                            <!-- Daily Usage -->
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-medium"><?= __('admin.daily_usage') ?></span>
                                                    <span class="text-muted small" id="daily-usage-text">- / -</span>
                                                </div>
                                                <div class="progress" role="progressbar">
                                                    <div class="progress-bar bg-primary no-animation rounded" 
                                                         role="progressbar" 
                                                         id="daily-progress-bar"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Monthly Usage -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-medium"><?= __('admin.monthly_usage') ?></span>
                                                    <span class="text-muted small" id="monthly-usage-text">- / -</span>
                                                </div>
                                                <div class="progress" role="progressbar">
                                                    <div class="progress-bar bg-success no-animation rounded" 
                                                         role="progressbar" 
                                                         id="monthly-progress-bar"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column - Chart & Quick Actions -->
                                <div class="col-lg-4">
                                    <!-- Usage Chart -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-transparent border-bottom-0 pb-0">
                                            <h6 class="fw-semibold mb-0">
                                                <i class="bi bi-graph-up me-2"></i> <?= __('admin.usage_trend_7_days') ?>
                                            </h6>
                                        </div>
                                        <div class="card-body pt-3">
                                            <div id="ai-usage-chart" class="min-vh-25"></div>
                                        </div>
                                    </div>

                                    <!-- Cost Summary -->
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-transparent border-bottom-0 pb-0">
                                            <h6 class="fw-semibold mb-0">
                                                <i class="bi bi-currency-dollar me-2"></i> <?= __('admin.cost_summary') ?>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                For tracking purposes only - no money is charged by this system
                                            </small>
                                        </div>
                                        <div class="card-body pt-3">
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <div class="border-end">
                                                        <h5 class="text-primary mb-1" id="admin-cost-today">$0.00</h5>
                                                        <small class="text-muted"><?= __('admin.cost_today') ?></small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="text-success mb-1" id="admin-cost-month">$0.00</h5>
                                                    <small class="text-muted"><?= __('admin.cost_month') ?></small>
                                                </div>
                                            </div>
                                            
                                            <!-- Remaining Quotas -->
                                            <div class="mt-3 pt-3 border-top">
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <div class="border-end">
                                                            <h6 class="text-info mb-1" id="admin-remaining-daily">-</h6>
                                                            <small class="text-muted"><?= __('admin.remaining_today') ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="text-warning mb-1" id="admin-remaining-monthly">-</h6>
                                                        <small class="text-muted"><?= __('admin.remaining_month') ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- AI Settings - Collapsible Section -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-primary text-white border-bottom-0 position-relative"
                                     data-bs-toggle="collapse" 
                                     data-bs-target="#aiSettingsCollapse" 
                                     aria-expanded="false">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-gear-fill me-2 fs-5"></i>
                                            <h6 class="fw-bold mb-0 text-white">
                                                <?= __('admin.ai_helper_settings') ?>
                                            </h6>
                                            <span class="badge bg-warning bg-opacity-90 text-dark ms-2 small">
                                                <i class="bi bi-arrow-down-circle me-1"></i>Click to Configure
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <small class="text-white-50 me-2">API Keys, Limits, Premium Plans</small>
                                            <i class="bi bi-chevron-down fs-5 text-white" id="aiSettingsChevron"></i>
                                        </div>
                                    </div>
                                    <!-- Subtle animation effect -->
                                    <div class="position-absolute bottom-0 start-0 w-100 opacity-50 border-top"></div>
                                </div>
                                <div class="collapse" id="aiSettingsCollapse">
                                    <div class="card-body pt-3">
                                        <!-- AI Provider Settings Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-cloud me-1"></i> <?= __('admin.ai_provider_settings') ?>
                                    </h6>
                                </div>
                            </div>

                            <!-- AI Provider Selection -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= __('admin.ai_provider') ?></label>
                                    <small class="form-text text-muted d-block"><?= __('admin.ai_provider_desc') ?></small>
                                </div>
                                                                        <div class="col-md-6">
                                            <select class="form-control update_all_settings" 
                                                    id="ai-provider-select"
                                                    name="ai_helper[ai_provider]"
                                                    data-setting_key="ai_provider" 
                                                    data-setting_type="ai_helper">
                                                <option value="openai" <?= isset($ai_helper['ai_provider']) && $ai_helper['ai_provider'] == 'openai' ? 'selected' : '' ?>><?= __('admin.openai') ?></option>
                                                <option value="claude" <?= isset($ai_helper['ai_provider']) && $ai_helper['ai_provider'] == 'claude' ? 'selected' : '' ?>><?= __('admin.claude_anthropic') ?></option>
                                                <option value="gemini" <?= isset($ai_helper['ai_provider']) && $ai_helper['ai_provider'] == 'gemini' ? 'selected' : '' ?>><?= __('admin.google_gemini') ?></option>
                                            </select>
                                        </div>
                            </div>

                            <!-- OpenAI Settings -->
                            <div id="openai-settings" class="ai-provider-settings" data-provider="openai">
                                <!-- OpenAI API Key -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('admin.openai_api_key') ?></label>
                                        <small class="form-text text-muted d-block"><?= __('admin.openai_api_key_desc') ?></small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="password" 
                                                   name="ai_helper[openai_api_key]"
                                                   class="form-control update_all_settings" 
                                                   value="<?= isset($ai_helper['openai_api_key']) ? $ai_helper['openai_api_key'] : '' ?>"
                                                   placeholder="<?= isset($ai_helper['openai_api_key']) && !empty($ai_helper['openai_api_key']) ? __('admin.api_key_configured') : 'sk-...' ?>"
                                                   data-setting_key="openai_api_key" 
                                                   data-setting_type="ai_helper"
                                                   autocomplete="new-password"
                                                   data-lpignore="true"
                                                   data-form-type="other">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility(this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <?php if (isset($ai_helper['openai_api_key']) && !empty($ai_helper['openai_api_key'])): ?>
                                            <small class="text-success"><i class="bi bi-check-circle me-1"></i><?= __('admin.api_key_configured') ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- OpenAI Model -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('admin.openai_model') ?></label>
                                        <small class="form-text text-muted d-block"><?= __('admin.openai_model_desc') ?></small>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control update_all_settings" 
                                                name="ai_helper[openai_model]"
                                                data-setting_key="openai_model" 
                                                data-setting_type="ai_helper">
                                            <option value="gpt-3.5-turbo" <?= isset($ai_helper['openai_model']) && $ai_helper['openai_model'] == 'gpt-3.5-turbo' ? 'selected' : '' ?>>GPT-3.5 Turbo</option>
                                            <option value="gpt-4" <?= isset($ai_helper['openai_model']) && $ai_helper['openai_model'] == 'gpt-4' ? 'selected' : '' ?>>GPT-4</option>
                                            <option value="gpt-4-turbo" <?= isset($ai_helper['openai_model']) && $ai_helper['openai_model'] == 'gpt-4-turbo' ? 'selected' : '' ?>>GPT-4 Turbo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Claude Settings -->
                            <div id="claude-settings" class="ai-provider-settings" data-provider="claude" style="display: none;">
                                <!-- Claude API Key -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('admin.claude_api_key') ?></label>
                                        <small class="form-text text-muted d-block"><?= __('admin.claude_api_key_desc') ?></small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="password" 
                                                   name="ai_helper[claude_api_key]"
                                                   class="form-control update_all_settings" 
                                                   value="<?= isset($ai_helper['claude_api_key']) ? $ai_helper['claude_api_key'] : '' ?>"
                                                   placeholder="<?= isset($ai_helper['claude_api_key']) && !empty($ai_helper['claude_api_key']) ? __('admin.api_key_configured') : 'sk-ant-...' ?>"
                                                   data-setting_key="claude_api_key" 
                                                   data-setting_type="ai_helper"
                                                   autocomplete="new-password"
                                                   data-lpignore="true"
                                                   data-form-type="other">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility(this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <?php if (isset($ai_helper['claude_api_key']) && !empty($ai_helper['claude_api_key'])): ?>
                                            <small class="text-success"><i class="bi bi-check-circle me-1"></i><?= __('admin.api_key_configured') ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Claude Model -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('admin.claude_model') ?></label>
                                        <small class="form-text text-muted d-block"><?= __('admin.claude_model_desc') ?></small>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control update_all_settings" 
                                                name="ai_helper[claude_model]"
                                                data-setting_key="claude_model" 
                                                data-setting_type="ai_helper">
                                            <option value="claude-3-haiku-20240307" <?= isset($ai_helper['claude_model']) && $ai_helper['claude_model'] == 'claude-3-haiku-20240307' ? 'selected' : '' ?>>Claude 3 Haiku</option>
                                            <option value="claude-3-sonnet-20240229" <?= isset($ai_helper['claude_model']) && $ai_helper['claude_model'] == 'claude-3-sonnet-20240229' ? 'selected' : '' ?>>Claude 3 Sonnet</option>
                                            <option value="claude-3-opus-20240229" <?= isset($ai_helper['claude_model']) && $ai_helper['claude_model'] == 'claude-3-opus-20240229' ? 'selected' : '' ?>>Claude 3 Opus</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Gemini Settings -->
                            <div id="gemini-settings" class="ai-provider-settings" data-provider="gemini" style="display: none;">
                                <!-- Gemini API Key -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('admin.gemini_api_key') ?></label>
                                        <small class="form-text text-muted d-block"><?= __('admin.gemini_api_key_desc') ?></small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="password" 
                                                   name="ai_helper[gemini_api_key]"
                                                   class="form-control update_all_settings" 
                                                   value="<?= isset($ai_helper['gemini_api_key']) ? $ai_helper['gemini_api_key'] : '' ?>"
                                                   placeholder="<?= isset($ai_helper['gemini_api_key']) && !empty($ai_helper['gemini_api_key']) ? __('admin.api_key_configured') : 'AIza...' ?>"
                                                   data-setting_key="gemini_api_key" 
                                                   data-setting_type="ai_helper"
                                                   autocomplete="new-password"
                                                   data-lpignore="true"
                                                   data-form-type="other">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility(this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <?php if (isset($ai_helper['gemini_api_key']) && !empty($ai_helper['gemini_api_key'])): ?>
                                            <small class="text-success"><i class="bi bi-check-circle me-1"></i><?= __('admin.api_key_configured') ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Gemini Model -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('admin.gemini_model') ?></label>
                                        <small class="form-text text-muted d-block"><?= __('admin.gemini_model_desc') ?></small>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control update_all_settings" 
                                                name="ai_helper[gemini_model]"
                                                data-setting_key="gemini_model" 
                                                data-setting_type="ai_helper">
                                            <option value="gemini-pro" <?= isset($ai_helper['gemini_model']) && $ai_helper['gemini_model'] == 'gemini-pro' ? 'selected' : '' ?>>Gemini Pro</option>
                                            <option value="gemini-pro-vision" <?= isset($ai_helper['gemini_model']) && $ai_helper['gemini_model'] == 'gemini-pro-vision' ? 'selected' : '' ?>>Gemini Pro Vision</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Usage & Billing Settings Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-speedometer2 me-1"></i> <?= __('admin.usage_billing_settings') ?>
                                    </h6>
                                </div>
                            </div>

                            <!-- Enable Usage Limits -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= __('admin.enable_usage_limits') ?></label>
                                    <small class="form-text text-muted d-block"><?= __('admin.enable_usage_limits_desc') ?></small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-switch">
                                        <input class="form-check-input update_all_settings" type="checkbox"
                                            <?= isset($ai_helper['enable_usage_limits']) && $ai_helper['enable_usage_limits'] == 1 ? 'checked' : '' ?>
                                            data-setting_key="enable_usage_limits" 
                                            data-setting_type="ai_helper">
                                    </div>
                                </div>
                            </div>

                            <!-- Daily Limit Per User -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= __('admin.daily_limit_per_user') ?></label>
                                    <small class="form-text text-muted d-block"><?= __('admin.daily_limit_per_user_desc') ?></small>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" name="ai_helper[daily_limit_per_user]" 
                                               value="<?= isset($ai_helper['daily_limit_per_user']) ? $ai_helper['daily_limit_per_user'] : '50' ?>" 
                                               class="form-control update_all_settings" 
                                               placeholder="50" 
                                               min="1" max="1000"
                                               data-setting_key="daily_limit_per_user" 
                                               data-setting_type="ai_helper">
                                        <span class="input-group-text"><?= __('admin.requests_day') ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Monthly Limit Per User -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= __('admin.monthly_limit_per_user') ?></label>
                                    <small class="form-text text-muted d-block"><?= __('admin.monthly_limit_per_user_desc') ?></small>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" name="ai_helper[monthly_limit_per_user]" 
                                               value="<?= isset($ai_helper['monthly_limit_per_user']) ? $ai_helper['monthly_limit_per_user'] : '1000' ?>" 
                                               class="form-control update_all_settings" 
                                               placeholder="1000" 
                                               min="1" max="10000"
                                               data-setting_key="monthly_limit_per_user" 
                                               data-setting_type="ai_helper">
                                        <span class="input-group-text"><?= __('admin.requests_month') ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Cost Per Request -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= __('admin.cost_per_request') ?></label>
                                    <small class="form-text text-muted d-block"><?= __('admin.cost_per_request_desc') ?></small>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="ai_helper[cost_per_request]" 
                                               value="<?= isset($ai_helper['cost_per_request']) ? $ai_helper['cost_per_request'] : '0.02' ?>" 
                                               class="form-control update_all_settings" 
                                               placeholder="0.02" 
                                               step="0.001" min="0" max="1"
                                               data-setting_key="cost_per_request" 
                                               data-setting_type="ai_helper">
                                    </div>
                                    <small class="text-warning mt-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <strong>Note:</strong> This is for estimation only - no real charges are made by this system
                                    </small>
                                </div>
                            </div>

                            <!-- Premium AI Plans Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-success border-bottom pb-2 mb-3">
                                        <i class="bi bi-star me-1"></i> <?= __('admin.premium_ai_plans') ?>
                                    </h6>
                                </div>
                            </div>

                            <!-- Enable Premium AI Plans -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= __('admin.enable_premium_ai_plans') ?></label>
                                    <small class="form-text text-muted d-block"><?= __('admin.enable_premium_ai_plans_desc') ?></small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-switch">
                                        <input class="form-check-input update_all_settings" type="checkbox"
                                            <?= isset($ai_helper['enable_premium_ai_plans']) && $ai_helper['enable_premium_ai_plans'] == 1 ? 'checked' : '' ?>
                                            data-setting_key="enable_premium_ai_plans" 
                                            data-setting_type="ai_helper">
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Plan Daily Limit -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= __('admin.basic_plan_daily_limit') ?></label>
                                    <small class="form-text text-muted d-block"><?= __('admin.basic_plan_daily_limit_desc') ?></small>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" name="ai_helper[basic_plan_daily_limit]" 
                                               value="<?= isset($ai_helper['basic_plan_daily_limit']) ? $ai_helper['basic_plan_daily_limit'] : '10' ?>" 
                                               class="form-control update_all_settings" 
                                               placeholder="10" 
                                               min="1" max="100"
                                               data-setting_key="basic_plan_daily_limit" 
                                               data-setting_type="ai_helper">
                                        <span class="input-group-text"><?= __('admin.requests_day') ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Premium Plan Daily Limit -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><?= __('admin.premium_plan_daily_limit') ?></label>
                                    <small class="form-text text-muted d-block"><?= __('admin.premium_plan_daily_limit_desc') ?></small>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" name="ai_helper[premium_plan_daily_limit]" 
                                               value="<?= isset($ai_helper['premium_plan_daily_limit']) ? $ai_helper['premium_plan_daily_limit'] : '100' ?>" 
                                               class="form-control update_all_settings" 
                                               placeholder="100" 
                                               min="1" max="1000"
                                               data-setting_key="premium_plan_daily_limit" 
                                               data-setting_type="ai_helper">
                                        <span class="input-group-text"><?= __('admin.requests_day') ?></span>
                                    </div>
                                </div>
                            </div>

                                        <!-- Test Connection -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <div class="alert alert-info d-flex align-items-center">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <div class="flex-grow-1">
                                                        <?= __('admin.ai_test_connection_info') ?>
                                                    </div>
                                                    <button type="button" id="test-ai-connection" class="btn btn-primary btn-sm ms-2">
                                                        <i class="bi bi-check-circle me-1"></i> <?= __('admin.test_connection') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Settings Enhancement Script -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Handle AI Settings collapse animation
                    const aiSettingsCollapse = document.getElementById('aiSettingsCollapse');
                    const aiSettingsChevron = document.getElementById('aiSettingsChevron');
                    const aiSettingsHeader = document.querySelector('[data-bs-target="#aiSettingsCollapse"]');
                    
                    if (aiSettingsCollapse && aiSettingsChevron && aiSettingsHeader) {
                        // Add hover effect
                        aiSettingsHeader.addEventListener('mouseenter', function() {
                            this.style.transform = 'translateY(-1px)';
                            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.15)';
                            this.style.transition = 'all 0.2s ease';
                        });
                        
                        aiSettingsHeader.addEventListener('mouseleave', function() {
                            this.style.transform = 'translateY(0)';
                            this.style.boxShadow = '';
                        });
                        
                        // Handle chevron rotation on collapse/expand
                        aiSettingsCollapse.addEventListener('show.bs.collapse', function() {
                            aiSettingsChevron.style.transform = 'rotate(180deg)';
                            aiSettingsChevron.style.transition = 'transform 0.3s ease';
                        });
                        
                        aiSettingsCollapse.addEventListener('hide.bs.collapse', function() {
                            aiSettingsChevron.style.transform = 'rotate(0deg)';
                            aiSettingsChevron.style.transition = 'transform 0.3s ease';
                        });
                    }
                    
                    // Handle AI Provider change to update current provider display
                    const providerSelect = document.getElementById('ai-provider-select');
                    if (providerSelect) {
                        providerSelect.addEventListener('change', function() {
                            const selectedProvider = this.value;
                            const providerName = selectedProvider.charAt(0).toUpperCase() + selectedProvider.slice(1);
                            
                            // Update provider badge and display
                            const providerBadge = document.getElementById('current-ai-provider');
                            const providerDisplay = document.getElementById('provider-display');
                            
                            if (providerBadge) {
                                providerBadge.textContent = providerName;
                            }
                            if (providerDisplay) {
                                providerDisplay.textContent = providerName;
                            }
                            
                            // Show relevant provider settings
                            showProviderSettings(selectedProvider);
                        });
                    }
                    
                    // Load AI usage data on page load
                    if (typeof loadAdminAIUsage === 'function') {
                        loadAdminAIUsage();
                    }
                });
                
                // Function to show/hide provider-specific settings
                function showProviderSettings(provider) {
                    // Hide all provider settings
                    const allProviderSettings = document.querySelectorAll('.ai-provider-settings');
                    allProviderSettings.forEach(function(setting) {
                        setting.style.display = 'none';
                    });
                    
                    // Show selected provider settings
                    const selectedSettings = document.getElementById(provider + '-settings');
                    if (selectedSettings) {
                        selectedSettings.style.display = 'block';
                    }
                }
            </script>

            <!-- Progress Bar Animation Fix -->
            <style>
                .no-animation, #daily-progress-bar, #monthly-progress-bar {
                    transition: none !important;
                    animation: none !important;
                }
                
                /* AI Settings Header Enhancement */
                .card-header[data-bs-target="#aiSettingsCollapse"]:hover {
                    background: linear-gradient(135deg, #0056b3 0%, #004085 100%) !important;
                }
                
                /* Badge pulse animation */
                .badge.bg-warning {
                    animation: badgePulse 2s infinite;
                }
                
                @keyframes badgePulse {
                    0%, 100% { opacity: 0.9; }
                    50% { opacity: 1; }
                }
                
                /* Ensure Save Button is Always Clickable */
                #securitform, .btn-submit {
                    position: relative !important;
                    z-index: 10000 !important;
                    pointer-events: auto !important;
                    display: inline-block !important;
                }
                
                /* Save Button Container */
                .col-sm-12.text-end {
                    position: relative !important;
                    z-index: 9999 !important;
                }
                
                /* Prevent AI Settings from overlapping save button */
                #aiSettingsCollapse {
                    position: relative;
                    z-index: 1;
                }
                
                /* Ensure all form elements work properly */
                .card-body {
                    position: relative;
                    z-index: 1;
                }
                
                /* Smart Save Button - Modern UX */
                .smart-save-btn {
                    position: fixed;
                    bottom: 30px;
                    right: 30px;
                    z-index: 99999;
                    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
                    border-radius: 50px;
                    padding: 15px 25px;
                    font-weight: 600;
                    font-size: 1rem;
                    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    border: none;
                    min-width: 160px;
                    opacity: 0;
                    visibility: hidden;
                    transform: translateY(20px) scale(0.9);
                }
                
                .smart-save-btn.show {
                    opacity: 1;
                    visibility: visible;
                    transform: translateY(0) scale(1);
                }
                
                .smart-save-btn:hover {
                    transform: translateY(-3px) scale(1.02);
                    box-shadow: 0 12px 35px rgba(0, 123, 255, 0.4);
                }
                
                .smart-save-btn:active {
                    transform: translateY(-1px) scale(0.98);
                }
                
                .smart-save-btn:disabled {
                    transform: none;
                    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
                }
                
                /* Mobile responsive for smart button */
                @media (max-width: 768px) {
                    .smart-save-btn {
                        bottom: 20px;
                        right: 20px;
                        padding: 12px 20px;
                        font-size: 0.9rem;
                        min-width: 140px;
                    }
                }
                
                /* Hide original save button on mobile to avoid confusion */
                @media (max-width: 576px) {
                    .original-save-btn {
                        display: none !important;
                    }
                }
            </style>
        </div>

        <div class="tab-pane fade p-3 <?= $activeTab === 'telegram-setting' ? 'show active' : '' ?>" id="telegram-setting" role="tabpanel">

            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.telegram_enable') ?></label>
                <select class="form-control" name="site[telegram_enable]">
                    <option value="1" <?= isset($site['telegram_enable']) && $site['telegram_enable'] == 1 ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
                    <option value="0" <?= empty($site['telegram_enable']) ? 'selected' : '' ?>><?= __('admin.disable') ?></option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.telegram_bot_token') ?></label>
                <input type="text" class="form-control" name="site[telegram_bot_token]" value="<?= isset($site['telegram_bot_token']) ? $site['telegram_bot_token'] : '' ?>">
            </div>

            <div class="form-group mb-3">
                <label class="control-label"><?= __('admin.telegram_chat_id') ?></label>
                <input type="text" class="form-control" name="site[telegram_chat_id]" value="<?= isset($site['telegram_chat_id']) ? $site['telegram_chat_id'] : '' ?>">
            </div>

            <fieldset class="border-top pt-3">
                <legend class="fw-bold"><?= __('admin.telegram_event_triggers') ?></legend>

                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="checkbox" id="tg_event_user_register" name="site[telegram_event_user_register]" value="1"
                        <?= !empty($site['telegram_event_user_register']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="tg_event_user_register">🧑‍💻 <?= __('admin.telegram_event_user_register') ?></label>
                </div>

                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="checkbox" id="tg_event_new_order" name="site[telegram_event_new_external_order]" value="1"
                        <?= !empty($site['telegram_event_new_external_order']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="tg_event_new_order">📦 <?= __('admin.telegram_event_new_external_order') ?></label>
                </div>

                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="checkbox" id="tg_event_new_store_order" name="site[telegram_event_new_store_order]" value="1"
                        <?= !empty($site['telegram_event_new_store_order']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="tg_event_new_store_order">🏬 <?= __('admin.telegram_event_new_store_order') ?></label>
                </div>
            </fieldset>

            <fieldset class="mt-4">
                <legend class="fw-bold"><?= __('admin.testing') ?></legend>
                <div class="text-start">
                    <button type="button" class="btn btn-primary btn-submit-telegram">
                        <i class="bi bi-telegram"></i> <?= __('admin.send_test_telegram_message') ?>
                    </button>
                </div>
            </fieldset>

            <script>
                $(".btn-submit-telegram").on('click', function(evt){
                    evt.preventDefault();

                    var formData = new FormData();
                    formData.append('test_telegram', 1);

                    var $btn = $(".btn-submit-telegram");
                    var originalText = $btn.html();
                    $btn.html('<i class="bi bi-hourglass-split me-2"></i><?= __('admin.sending') ?>...').prop('disabled', true);

                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url("admincontrol/paymentsetting") ?>',
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(result){
                            $btn.html(originalText).prop('disabled', false);
                            $(".alert-dismissable").remove();

                            if(result['success']){
                                showPrintMessage(result['success'], 'success');
                            }
                            if(result['message']){
                                showPrintMessage(result['message'], 'error');
                            }
                        },
                        error: function() {
                            $btn.html(originalText).prop('disabled', false);
                            showPrintMessage('Request failed. Please try again.', 'error');
                        }
                    });

                    return false;
                });
            </script>
        </div>


<div class="tab-pane fade p-3 <?= $activeTab === 'tracking' ? 'show active' : '' ?>" id="tracking" role="tabpanel">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= __('admin.affiliate_tracking') ?></h5>
            <div class="form-group mt-3">
                <label class="form-label"><?= __('admin.select_tracking_method') ?></label>
                <select class="form-select" name="site[affiliate_tracking_place]">
                    <option value="0" selected><?= __('admin.use_cookies') ?></option>
                    <option <?= $site['affiliate_tracking_place'] == 1 ? 'selected' : ''; ?> value="1"><?= __('admin.use_local_storage') ?></option>
                    <option <?= $site['affiliate_tracking_place'] == 2 ? 'selected' : ''; ?> value="2"><?= __('admin.use_cookies_and_local_storage_both') ?></option>
                </select>
            </div>
            <div class="form-group">
                <label  class="form-label"><?= __('admin.affiliate_cookie') ?></label>
                <input class="form-control" type="number" value="<?= $store['affiliate_cookie'] ?>" name="store[affiliate_cookie]">
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= __('admin.cookie_preferences') ?></h5>
            <div class="form-group mt-3">
                <label class="form-label"><?= __('admin.show_on_menu') ?></label>
                <div class="form-check form-switch">
                    <input class="form-check-input update_all_settings" type="checkbox" <?= $site['cookies_menu']==1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="cookies_menu" data-setting_type="site">
                </div>
            </div>
            <div class="form-group mt-3">
                <label class="form-label"><?= __('admin.cookies_consent') ?></label>
                <div class="form-check form-switch">
                    <input class="form-check-input update_all_settings" type="checkbox" <?= $site['cookies_consent']==1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="cookies_consent" data-setting_type="site">
                </div>
            </div>
            <div class="form-group mt-3">
                <label  class="form-label"><?= __('admin.cookies_consent_messg_setting') ?></label>
                <input class="form-control" type="text" value="<?= (isset($site['cookies_consent_mesag']))? $site['cookies_consent_mesag']:__('admin.cookies_consent_default_message'); ?>" name="site[cookies_consent_mesag]">
            </div>
        </div>
    </div>
</div>


<div class="tab-pane fade p-3 <?= $activeTab === 'fraud' ? 'show active' : '' ?>" id="fraud" role="tabpanel">
    
<!-- Proxy & VPN -->
<div class="row">
    <div class="col-md-6">
        <!-- isproxyip Proxy & VPN Blocker Setting -->
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">
                        <span class="badge bg-secondary fs-6">
                            <?= __('admin.proxy_vpn_isproxyip_service') ?>
                        </span>
                    </label>
                    <p class="small text-muted fs-6">
                        <?= __('admin.isproxyip_service_api_key') ?>
                        <a href="https://isproxyip.com/" target="_blank" class="btn btn-link text-decoration-none">
                            <?= __('admin.register_here') ?>
                        </a>
                        <br>
                        <span class="badge bg-info text-dark">
                            <?= __('admin.free_plan') ?>: 1,000 requests per day, $0.00 / Monthly
                        </span>
                    </p>
                    <input type="text" 
                           class="form-control update_all_settings" 
                           placeholder="Enter API Key" 
                           value="<?= isset($proxy_services['service1_api_key']) ? $proxy_services['service1_api_key'] : '' ?>" 
                           name="proxy_services[service1_api_key]"
                           data-setting_key="service1_api_key" 
                           data-setting_type="proxy_services">
                    <input type="text" 
                           class="form-control mt-2 update_all_settings" 
                           placeholder="Enter Service URL" 
                           value="<?= isset($proxy_services['service1_url']) ? $proxy_services['service1_url'] : '' ?>" 
                           name="proxy_services[service1_url]"
                           data-setting_key="service1_url" 
                           data-setting_type="proxy_services">
                </div>
                <div class="form-group mt-3">
                    <p class="small text-muted fs-6"><?= __('admin.enable_isproxyip_service') ?></p>
                    <div class="form-check form-switch">
                        <input class="form-check-input update_all_settings" 
                               type="checkbox" 
                               <?= $proxy_services['service1_enabled'] == 1 ? 'checked' : '' ?>
                               data-bs-toggle="toggle" 
                               data-bs-size="normal" 
                               data-bs-on="<?= __('admin.status_on') ?>" 
                               data-bs-off="<?= __('admin.status_off') ?>" 
                               data-setting_key="service1_enabled" 
                               data-setting_type="proxy_services">
                    </div>
                </div>
            </div>
        </div>
        <!-- End Proxy & VPN Blocker Setting -->
    </div>
    <div class="col-md-6">
        <!-- vpnapi Proxy & VPN Blocker Setting -->
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">
                        <span class="badge bg-secondary fs-6">
                            <?= __('admin.proxy_vpn_vpnapi_service') ?>
                        </span>
                    </label>
                    <p class="small text-muted fs-6">
                        <?= __('admin.vpnapi_service_api_key') ?>
                        <a href="https://vpnapi.io/" target="_blank" class="btn btn-link text-decoration-none">
                            <?= __('admin.register_here') ?>
                        </a>
                        <br>
                        <span class="badge bg-info text-dark">
                            <?= __('admin.free_plan') ?>: 1,000 requests per month, $0.00 / Monthly
                        </span>
                    </p>
                    <input type="text" 
                           class="form-control update_all_settings" 
                           placeholder="Enter API Key" 
                           value="<?= isset($proxy_services['service2_api_key']) ? $proxy_services['service2_api_key'] : '' ?>" 
                           name="proxy_services[service2_api_key]"
                           data-setting_key="service2_api_key" 
                           data-setting_type="proxy_services">
    
                    <input type="text" 
                           class="form-control mt-2 update_all_settings" 
                           placeholder="Enter Service URL" 
                           value="<?= isset($proxy_services['service2_url']) ? $proxy_services['service2_url'] : '' ?>" 
                           name="proxy_services[service2_url]"
                           data-setting_key="service2_url" 
                           data-setting_type="proxy_services">
                </div>
                <div class="form-group mt-3">
                    <p class="small text-muted fs-6"><?= __('admin.enable_vpnapi_service') ?></p>
                    <div class="form-check form-switch">
                        <input class="form-check-input update_all_settings" 
                               type="checkbox" 
                               <?= $proxy_services['service2_enabled'] == 1 ? 'checked' : '' ?>
                               data-bs-toggle="toggle" 
                               data-bs-size="normal" 
                               data-bs-on="<?= __('admin.status_on') ?>" 
                               data-bs-off="<?= __('admin.status_off') ?>" 
                               data-setting_key="service2_enabled" 
                               data-setting_type="proxy_services">
                    </div>
                </div>
            </div>
        </div>
        <!-- vpnapi End Proxy & VPN Blocker Setting -->
    </div>
</div>
<!-- Proxy & VPN -->



    <div class="card mb-4">
        <div class="card-body">
            <label class="form-lable">
                <span class="badge bg-secondary fs-6">
                    <?= __('admin.fraud_preferences') ?>
                </span>
            </label>
            <div class="form-group">
                <p class="small text-muted fs-6"><?= __('admin.prevent_affiliate_self_fraud_title') ?></p>
                <div class="form-check form-switch">
                    <input class="form-check-input update_all_settings" type="checkbox" <?= $site['block_click_across_browser'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="block_click_across_browser" data-setting_type="site">
                </div>
            </div>



            <div class="form-group mt-3">
                <p class="small text-muted fs-6"><?= __('admin.send_fraud_alert_emails_info') ?></p>
                <div class="form-check form-switch">
                    <input class="form-check-input update_all_settings" type="checkbox" <?= $site['send_fraud_alert_email'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="send_fraud_alert_email" data-setting_type="site">
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <label class="form-lable">
                <span class="badge bg-secondary fs-6">
                    <?= __('admin.fraud_localhost') ?>
                </span>
            </label>
            <div class="form-group">
                <p class="small text-muted fs-6"><?= __('admin.enable_localhost_protection_title') ?></p>
                <div class="form-check form-switch">
                    <input class="form-check-input update_all_settings" type="checkbox" <?= $site['enable_localhost_protection']==1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="enable_localhost_protection" data-setting_type="site">
                </div>
            </div>
        </div>
    </div>

    <!-- Clicks Per Frequency Setting -->
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">
                    <span class="badge bg-secondary fs-6">
                        <?= __('admin.market_max_clicks_per_frequency') ?>
                    </span>
                </label>
                <p class="small text-muted fs-6"><?= __('admin.max_clicks_per_frequency_title') ?></p>
                <input type="number" 
                       class="form-control update_all_settings" 
                       min="1" 
                       placeholder="Enter Max Clicks" 
                       value="<?= isset($site['max_clicks']) ? $site['max_clicks'] : '' ?>" 
                       name="site[max_clicks]"
                       data-setting_key="max_clicks" 
                       data-setting_type="site">
                
                <select class="form-control mt-2 update_all_settings" 
                        name="site[frequency_unit_clicks]"
                        data-setting_key="frequency_unit_clicks" 
                        data-setting_type="site">
                    <option value="minutes" <?= isset($site['frequency_unit_clicks']) && $site['frequency_unit_clicks'] == 'minutes' ? 'selected' : '' ?>><?= __('admin.per_minute') ?></option>
                    <option value="hours" <?= isset($site['frequency_unit_clicks']) && $site['frequency_unit_clicks'] == 'hours' ? 'selected' : '' ?>><?= __('admin.per_hour') ?></option>
                    <option value="days" <?= isset($site['frequency_unit_clicks']) && $site['frequency_unit_clicks'] == 'days' ? 'selected' : '' ?>><?= __('admin.per_day') ?></option>
                </select>
            </div>
            
            <!-- Enable/Disable Max Clicks Per Frequency Setting -->
            <div class="form-group mt-3">
                <p class="small text-muted fs-6"><?= __('admin.enable_max_clicks_per_frequency_title') ?></p>
                <div class="form-check form-switch">
                    <input class="form-check-input update_all_settings" 
                           type="checkbox" 
                           id="enableClickControl" 
                           <?= isset($site['enable_click_control']) && $site['enable_click_control'] == '1' ? 'checked' : '' ?> 
                           data-bs-toggle="toggle" 
                           data-bs-size="normal" 
                           data-bs-on="<?= __('admin.status_on') ?>" 
                           data-bs-off="<?= __('admin.status_off') ?>" 
                           data-setting_key="enable_click_control" 
                           data-setting_type="site">
                    <label class="form-check-label" for="enableClickControl"><?= __('admin.status') ?></label>
                </div>
            </div>
        </div>
    </div>
    <!-- Clicks Per Frequency Setting -->



    <!-- Actions Per Frequency Setting -->
    <div class="card">
        <div class="card-body">
            <!-- Max Clicks Per Frequency Setting -->
            <div class="form-group">
                <label class="form-label">
                    <span class="badge bg-secondary fs-6">
                        <?= __('admin.market_max_actions_per_frequency') ?>
                    </span>
                </label>
                <p class="small text-muted fs-6"><?= __('admin.max_actions_per_frequency_title') ?></p>
                <input type="number" 
                       class="form-control update_all_settings" 
                       min="1" 
                       placeholder="Enter Max Actions" 
                       value="<?= isset($site['max_actions']) ? $site['max_actions'] : '' ?>" 
                       name="site[max_actions]"
                       data-setting_key="max_actions" 
                       data-setting_type="site">
                
                <select class="form-control mt-2 update_all_settings" 
                        name="site[frequency_unit_actions]"
                        data-setting_key="frequency_unit_actions" 
                        data-setting_type="site">
                    <option value="minutes" <?= isset($site['frequency_unit_actions']) && $site['frequency_unit_actions'] == 'minutes' ? 'selected' : '' ?>><?= __('admin.per_minute') ?></option>
                    <option value="hours" <?= isset($site['frequency_unit_actions']) && $site['frequency_unit_actions'] == 'hours' ? 'selected' : '' ?>><?= __('admin.per_hour') ?></option>
                    <option value="days" <?= isset($site['frequency_unit_actions']) && $site['frequency_unit_actions'] == 'days' ? 'selected' : '' ?>><?= __('admin.per_day') ?></option>
                </select>
            </div>
            
            <!-- Enable/Disable Max Actions Per Frequency Setting -->
            <div class="form-group mt-3">
                <p class="small text-muted fs-6"><?= __('admin.enable_max_actions_per_frequency_title') ?></p>
                <div class="form-check form-switch">
                    <input class="form-check-input update_all_settings" 
                           type="checkbox" 
                           id="enableActionControl" 
                           <?= isset($site['enable_action_control']) && $site['enable_action_control'] == '1' ? 'checked' : '' ?> 
                           data-bs-toggle="toggle" 
                           data-bs-size="normal" 
                           data-bs-on="<?= __('admin.status_on') ?>" 
                           data-bs-off="<?= __('admin.status_off') ?>" 
                           data-setting_key="enable_action_control" 
                           data-setting_type="site">
                    <label class="form-check-label" for="enableActionControl"><?= __('admin.status') ?></label>
                </div>
            </div>
        </div>
    </div>
    <!-- Actions Per Frequency Setting -->
</div>



<div class="tab-pane fade py-3 <?= $activeTab === 'googleads-setting' ? 'show active' : '' ?>" id="googleads-setting" role="tabpanel">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= __('admin.google_ads_setting_key_section') ?></h5>
                    <div class="row">
                        <?php 
                            $adsSections = [1 => 'side_bar_ads', 3 => 'footer_ads', 4 => 'right_side_ads', 5 => 'center_page_ads'];
                            foreach($adsSections as $id => $section): 
                                $googleAds = $this->Setting_model->getGoogleAds($id);
                        ?>
                        <div class="col">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="googleadsStatus[<?= $id ?>]" type="checkbox" <?= $googleAds[0]['status'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="invitation_link_id" data-setting_type="userdashboard">
                                <label class="form-label"><?= __("admin.$section") ?></label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= __('admin.client_ads_key') ?></h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap">
                                <div class="form-group me-3">
                                    <label class="form-label"><?= __('admin.client_ads_key') ?></label>
                                    <input placeholder="" id="googleads[client_key]" name="googleads[client_key]" value="" class="form-control" type="text">
                                </div>
                                <div class="form-group me-3">
                                    <label class="form-label"><?= __('admin.ads_unit_key') ?></label>
                                    <input placeholder="" id="googleads[unit_key]" name="googleads[unit_key]" value="" class="form-control" type="text">
                                </div>
                                <div class="form-group me-3">
                                    <label class="form-label"><?= __('admin.ads_section') ?></label>
                                    <select class="form-select" id="googleads[ad_section]" name="googleads[ad_section]">
                                        <option value="1"><?= __('admin.side_bar_top') ?></option>
                                        <option value="2"><?= __('admin.side_bar_bottom') ?></option>
                                        <option value="3"><?= __('admin.footer') ?></option>
                                        <option value="4"><?= __('admin.right_side') ?></option>
                                        <option value="5"><?= __('admin.center_page') ?></option>
                                    </select>
                                </div>
                                <input type="hidden" name="googleads[id]" id="googleads[id]" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= __('admin.ads_list') ?></h5>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped ads-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= __('admin.client_key') ?></th>
                                    <th><?= __('admin.unit_key') ?></th>
                                    <th><?= __('admin.ad_section') ?></th>
                                    <th><?= __('admin.action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($googleads as $rows) { ?>
                                    <tr id="row_<?= $rows['id']; ?>">
                                        <td><?= $i; ?></td>
                                        <td><?= $rows['client_key']; ?></td>
                                        <td><?= $rows['unit_key']; ?></td>
                                        <td><?= ads_google_status($rows['ad_section']); ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="javascript:void(0)" onclick="editAds(<?= $rows['id']; ?>,'<?= $rows['client_key']; ?>','<?= $rows['unit_key']; ?>',<?= $rows['ad_section']; ?>)">
                                                <i class="fa fa-edit cursors" aria-hidden="true"></i>
                                            </a>
                                            <button data-id="<?= $rows['id']; ?>" data-bs-toggle="tooltip" data-bs-original-title="<?= __('admin.delete') ?>" class="btn btn-sm btn-danger btn-delete2">
                                                <i class="fa fa-trash-o cursors" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php $i++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="tab-pane fade p-3 <?= $activeTab === 'googlerecaptcha-setting' ? 'show active' : '' ?>" id="googlerecaptcha-setting" role="tabpanel">
    <div class="row">
        <div class="col-lg-6">
            <!-- Card for Site Key, Secret Key, and Version -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= __('admin.text_site_key') ?></h5>
                    <input class="form-control mb-3" type="text" value="<?= $googlerecaptcha['sitekey'] ?>" name="googlerecaptcha[sitekey]" id="site_key">

                    <h5 class="card-title"><?= __('admin.text_secret_key') ?></h5>
                    <input class="form-control mb-3" type="text" value="<?= $googlerecaptcha['secretkey'] ?>" name="googlerecaptcha[secretkey]" id="secret_key">

                    <h5 class="card-title">reCAPTCHA Version</h5>
                    <select class="form-control" name="googlerecaptcha[version]">
                        <option value="v2" <?= (isset($googlerecaptcha['version']) && $googlerecaptcha['version'] == 'v2') ? 'selected' : '' ?>>reCAPTCHA v2 (Checkbox)</option>
                        <option value="v3" <?= (isset($googlerecaptcha['version']) && $googlerecaptcha['version'] == 'v3') ? 'selected' : '' ?>>reCAPTCHA v3 (Invisible, Score Based)</option>
                    </select>
                </div>
            </div>

            <!-- Card for Login and Register Settings -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= __('admin.login_register_settings') ?></h5>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input update_all_settings" type="checkbox" <?= $googlerecaptcha['admin_login'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="admin_login" data-setting_type="googlerecaptcha">
                        <label class="form-check-label"><?= __('admin.admin_login') ?></label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input update_all_settings" type="checkbox" <?= $googlerecaptcha['affiliate_login'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="affiliate_login" data-setting_type="googlerecaptcha">
                        <label class="form-check-label"><?= __('admin.affiliate_login') ?></label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input update_all_settings" type="checkbox" <?= $googlerecaptcha['affiliate_register'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="affiliate_register" data-setting_type="googlerecaptcha">
                        <label class="form-check-label"><?= __('admin.affiliate_register') ?></label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input update_all_settings" type="checkbox" <?= $googlerecaptcha['client_login'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="client_login" data-setting_type="googlerecaptcha">
                        <label class="form-check-label"><?= __('admin.client_login') ?></label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input update_all_settings" type="checkbox" <?= $googlerecaptcha['client_register'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="client_register" data-setting_type="googlerecaptcha">
                        <label class="form-check-label"><?= __('admin.client_register') ?></label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input update_all_settings" type="checkbox" <?= $googlerecaptcha['store_contact'] == 1 ? 'checked' : '' ?> data-bs-toggle="toggle" data-bs-size="normal" data-bs-on="<?= __('admin.status_on') ?>" data-bs-off="<?= __('admin.status_off') ?>" data-setting_key="store_contact" data-setting_type="googlerecaptcha">
                        <label class="form-check-label"><?= __('admin.store_contact') ?></label>
                    </div>
                </div>
            </div>
        </div>

<div class="col-lg-6">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h4 class="card-title mb-3">
                <i class="bi bi-shield-lock me-1"></i> <?= __('admin.how_to_get_site_key_secret_key') ?>
            </h4>

            <div class="accordion" id="recaptchaGuide">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#step1" aria-expanded="true">
                            <?= __('admin.step_1_title') ?>
                        </button>
                    </h2>
                    <div id="step1" class="accordion-collapse collapse show" data-bs-parent="#recaptchaGuide">
                        <div class="accordion-body">
                            <?= __('admin.step_1_content') ?>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step2">
                            <?= __('admin.step_2_title') ?>
                        </button>
                    </h2>
                    <div id="step2" class="accordion-collapse collapse" data-bs-parent="#recaptchaGuide">
                        <div class="accordion-body">
                            <?= __('admin.step_2_content') ?>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step3">
                            <?= __('admin.step_3_title') ?>
                        </button>
                    </h2>
                    <div id="step3" class="accordion-collapse collapse" data-bs-parent="#recaptchaGuide">
                        <div class="accordion-body">
                            <?= __('admin.step_3_content') ?>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step4">
                            <?= __('admin.step_4_title') ?>
                        </button>
                    </h2>
                    <div id="step4" class="accordion-collapse collapse" data-bs-parent="#recaptchaGuide">
                        <div class="accordion-body">
                            <?= __('admin.step_4_content') ?>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step5">
                            <?= __('admin.step_5_title') ?>
                        </button>
                    </h2>
                    <div id="step5" class="accordion-collapse collapse" data-bs-parent="#recaptchaGuide">
                        <div class="accordion-body">
                            <?= __('admin.step_5_content') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 bg-light border rounded">
                <div class="card-body py-3">
                    <p class="mb-0">
                        <?= __('admin.recaptcha_note') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>
</div>

                    </div>
                </div>

                <div class="col-sm-12 text-end mt-3 position-relative">
                    <button type="submit" id="securitform" class="btn btn-lg btn-primary btn-submit position-relative original-save-btn">
                        <i class="bi bi-save me-2"></i><?= __('admin.save_changes') ?>
                    </button>
                </div>

                        </div>
                    </form>
                </div>
            </div>
            </div> <!-- End settingsFormWrapper -->
        </div> <!-- End col-12 -->
    </div> <!-- End row -->
</div> <!-- End container-fluid -->

<script>
    function change_force_ssl() {
        var security_force_ssl = $("#security_force_ssl").val();
        if(security_force_ssl == 0) {
            $("#toggle_change_force_ssl").removeClass('fa-toggle-off')
            $("#toggle_change_force_ssl").addClass('fa-toggle-on')
            $("#security_force_ssl").val(1)
        } else {
            $("#toggle_change_force_ssl").removeClass('fa-toggle-on')
            $("#toggle_change_force_ssl").addClass('fa-toggle-off')
            $("#security_force_ssl").val(0)
        }
        $("#securitform").trigger('click');
    }
    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }
    
    function isNumeric (evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode (key);
        var regex = /[0-9]|\./;
        if ( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>

<script type="text/javascript">
    // Form data filter function
    function formDataFilter(formData) {
        // Remove empty values and clean up form data
        var filteredData = new FormData();
        for (var pair of formData.entries()) {
            // Special handling for image fields that need to be cleared
            if (pair[0].includes('admin-login-background-image') || 
                pair[0].includes('user-login-background-image') ||
                pair[0].includes('admin-side-logo') ||
                pair[0].includes('user-side-logo') ||
                pair[0].includes('favicon')) {
                // Always include these fields, even if empty (to allow clearing)
                filteredData.append(pair[0], pair[1]);
            } else if (pair[1] !== '' && pair[1] !== null && pair[1] !== undefined) {
                // For other fields, only include non-empty values
                filteredData.append(pair[0], pair[1]);
            }
        }
        return filteredData;
    }
    
    // Change trigger function for refer level symbols
    function chnage_teigger() {
        var symbol = $(".refer-symball-select").val();
        $(".refer-symball").text(symbol);
    }
    
    $('select[name="email[mail_type]"]').on('change', function(){
        if($(this).val() == 'smtp') {
            $('.for-smtp-mail').show();
        } else {
            $('.for-smtp-mail').hide();
        }
    });
    
    $('select[name="email[mail_type]"]').trigger('change');
    
    $("#setting-form").on('submit',function(e){
        e.preventDefault();
        $("#setting-form .alert-error").remove();
        var affiliate_cookie = parseInt($(".input-affiliate_cookie").val());
        if(affiliate_cookie <= 0 || affiliate_cookie > 365){
            $(".input-affiliate_cookie").after("<div class='alert alert-danger alert-error'><?= __('admin.days_between_1_and_365') ?></div>");
        }
        if($("#setting-form .alert-error").length == 0) return true;
        return false;
    })
    $(".items-holder").delegate(".remove-items",'click',function(){
        $(this).parent(".input-group").remove();
    })
    $(".add-items").on('click',function(){
        $(".items-holder").append('\
            <div class="input-group mb-3">\
            <input type="text" name="login[text_list][]" class="form-control" placeholder="<?= __('admin.list_items') ?>" >\
            <div class="input-group-append remove-items">\
            <span class="input-group-text"><i class="fa fa-trash"></i></span>\
            </div>\
            </div>\
            ');
    })
    $(document).on('ready',function() 
    {
        if($("#mail_verifiy").parent().hasClass('off'))
        {
            $("#registration_approval_group").show();
        } 
        else
             $("#registration_approval_group").hide();
    });
    
    $('.send-test-mail').on('click',function(){ 
        $(".alert-dismissable").remove();
        $this = $(this);
        var originalText = $this.html();
        $this.html('<i class="bi bi-hourglass-split me-2"></i><?= __('admin.sending') ?>...').prop('disabled', true);
        $.ajax({
            type:'POST',
            dataType:'json',
            data:{send_test_mail:$(".testingemail").val()},
            success:function(json){
            $this.html(originalText).prop('disabled', false);
            $(".tab-content").prepend('<div class="alert mt-4 alert-info alert-dismissable">'+ json['message'] +'</div>');
            var body = $("html, body");
            body.stop().animate({scrollTop:0}, 500, 'swing', function() { }); 

             },
            error: function() {
                $this.html(originalText).prop('disabled', false);
            }
        });
    })
    $(".ads-table").delegate(".btn-delete2",'click',function(e){
        
        e.preventDefault();
        e.stopPropagation();
        $this = $(this);
        
        Swal.fire({
           icon: 'warning',
           title: 'Delete Ads!',
           text: 'Are you sure?',
           showCancelButton: true,
           cancelButtonText: 'cancel'
        }).then(function(dismiss){
            if(dismiss.value==true)
            {
                var Adsid=$this.attr("data-id");
                var originalText = $this.html();
                $this.html('<i class="bi bi-hourglass-split me-2"></i><?= __('admin.deleting') ?>...').prop('disabled', true);
                $.ajax({
                    url: '<?php echo base_url("admincontrol/deleteGoogleAds") ?>',
                    type:'POST',
                    dataType:'json',
                    data:{id:$this.attr("data-id")},
                    success:function(result){
                        $this.html(originalText).prop('disabled', false);

                        if(result['success']){
                            showPrintMessage(result['success'],'success');
                            var body = $("html, body");
                            body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
                            $("#row_"+Adsid).remove();
                            refreshAdsGoogle();
                            
                        }
                        if(result['errors']){
                            showPrintMessage(result['errors'],'errors');
                            var body = $("html, body");
                            body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
                            refreshAdsGoogle();
                            
                        }
                    },
                });
            }
            else
            {
              return false;
            }
        });
        /*delete user popup*/
    });

    function refreshAdsGoogle(){
        $("#googleads\\[id\\]").val('');
        $("#googleads\\[client_key\\]").val('');
        $("#googleads\\[unit_key\\]").val('');
        $("#googleads\\[ad_section\\]").val('');
        $.ajax({
                url: '<?php echo base_url("admincontrol/refreshGoogleAds") ?>',
                type:'POST',
                dataType:'json',
                data:{},
                success:function(result){
                    $(".ads-table tbody").html(result['adsList']);
                },
            });
       
    }
    // Smart save button functionality
    var $smartBtn = $("#smart-save-btn");
    var $originalBtn = $(".original-save-btn");
    var originalBtnOffset = $originalBtn.offset();
    var isSmartBtnVisible = false;
    
    // Show smart button when user scrolls away from original button
    function checkSmartButtonVisibility() {
        if (window.innerWidth <= 576) {
            // On mobile, always show smart button
            if (!isSmartBtnVisible) {
                $smartBtn.addClass('show');
                isSmartBtnVisible = true;
            }
            return;
        }
        
        var scrollTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        var originalBtnTop = originalBtnOffset ? originalBtnOffset.top : 0;
        
        // Show smart button if original button is not visible
        var shouldShow = scrollTop + windowHeight < originalBtnTop - 100 || scrollTop > originalBtnTop + 100;
        
        if (shouldShow && !isSmartBtnVisible) {
            $smartBtn.addClass('show');
            isSmartBtnVisible = true;
        } else if (!shouldShow && isSmartBtnVisible) {
            $smartBtn.removeClass('show');
            isSmartBtnVisible = false;
        }
    }
    
    // Initialize smart button behavior
    $(document).ready(function() {
        // Check on scroll
        $(window).on('scroll', checkSmartButtonVisibility);
        
        // Check on resize
        $(window).on('resize', function() {
            originalBtnOffset = $originalBtn.offset();
            checkSmartButtonVisibility();
        });
        
        // Initial check
        checkSmartButtonVisibility();
    });
    
    // Smart button click handler
    $smartBtn.on('click', function(evt) {
        evt.preventDefault();
        // Trigger the same save logic as the original button
        $(".btn-submit").trigger('click');
    });
    
    $(".btn-submit").on('click',function(evt){
        evt.preventDefault();
        
        $(".site-global_script").val( window.btoa(unescape(encodeURIComponent($(".site-global_script").val() ))) );
        $(".site-fbmessager_script").val( window.btoa(unescape(encodeURIComponent($(".site-fbmessager_script").val() ))) );
        $(".site-faceboook_pixel").val( window.btoa(unescape(encodeURIComponent($(".site-faceboook_pixel").val() ))) );
        $(".site-google_analytics").val( window.btoa(unescape(encodeURIComponent($(".site-google_analytics").val() ))) );
    
        var formData = new FormData($("#setting-form")[0]);
    
        $(".site-global_script").val( decodeURIComponent(escape(window.atob( $(".site-global_script").val() ))) );
        $(".site-fbmessager_script").val( decodeURIComponent(escape(window.atob( $(".site-fbmessager_script").val() ))) );
        $(".site-faceboook_pixel").val( decodeURIComponent(escape(window.atob( $(".site-faceboook_pixel").val() ))) );
        $(".site-google_analytics").val( decodeURIComponent(escape(window.atob( $(".site-google_analytics").val() ))) );
    
        var $btn = $(".btn-submit");
        var $smartBtn = $("#smart-save-btn");
        var originalText = $btn.html();
        var originalSmartText = $smartBtn.html();
        
        $btn.html('<i class="bi bi-hourglass-split me-2"></i><?= __('admin.saving') ?>...').prop('disabled', true);
        $smartBtn.html('<i class="bi bi-hourglass-split me-2"></i><?= __('admin.saving') ?>...').prop('disabled', true);
        
        formData = formDataFilter(formData);
        
        $this = $("#setting-form");
    
        $.ajax({
            type:'POST',
            dataType:'json',
            cache:false,
            contentType: false,
            processData: false,
            data:formData,
            success:function(result){
                $btn.html(originalText).prop('disabled', false);
                $smartBtn.html(originalSmartText).prop('disabled', false);
                $(".alert-dismissable").remove();
    
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
    
                if(result['location']){
                    window.location = result['location'];
                }
    
                if(result['success']){
                    showPrintMessage(result['success'],'success');
                    var body = $("html, body");
                    body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
                    refreshAdsGoogle();
                    
                }
                if(result['message']){
                    showPrintMessage(result['message'],'error');
                    var body = $("html, body");
                    body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
                    
                }
    
                if(result['errors']){
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='d-block text-danger'>"+ j +"</span>");
                        }
                    });
                }
            },
            error: function() {
                $btn.html(originalText).prop('disabled', false);
                $smartBtn.html(originalSmartText).prop('disabled', false);
                showPrintMessage('<?= __('admin.connection_error') ?>', 'error');
            }
        })
        return false;
    });
    var levels = {};
    
    <?php 
        for ($i=1; $i <= 10; $i++) { 
            $v = 'referlevel_'.$i;
            if (isset($$v)) { ?>
            levels['<?= $i ?>'] = <?= json_encode($$v) ?>;
        <?php }
        }
        ?>
    $('#referlevel_select').on('change',function(){
        var level =  $(this).val();
    
        var html = '';
        for(var i = 1; i <= level; i++){
            html += '<tr>';
            html += '<td>'+i+'</td>';
            html += '<td><input type="number" step="any" name="referlevel_'+i+'[commition]" value="'+(levels[i] ? levels[i]['commition'] : '' )+'" class="form-control" /></td>';
            html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[sale_commition]" value="'+(levels[i] ? levels[i]['sale_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text refer-symball"></span></div>                                                         </div></td>';
            html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_commition]" value="'+(levels[i] ? levels[i]['ex_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div></div></td>';
            html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_action_commition]" value="'+(levels[i] ? levels[i]['ex_action_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div></div></td>';
            html += '</tr>';
        }
        $('#tbl_refer_level tbody').html(html);
    
        chnage_teigger();
    });
    
    $(document).on('click','.btn-delete-image', function(){
        let input_name = $(this).data('img_input');
        $('input[name="'+input_name+'"]').val('');
        

    
        let image_ele_id = $(this).data('img_ele');
        let placeholder_image = $(this).data('img_placeholder');
        $('#'+image_ele_id).attr('src', placeholder_image);
    
        $(this).remove()
    });
    
    $(document).on('click','.set-default-admin-url', function(){
        $.ajax({
            url:'<?= base_url("admincontrol/set_default_admin_url") ?>',
            type:'POST',
            dataType:'json',
            data:{'action':'set_default_admin_url'},
            success:function(json){
                window.location.reload();
            },
        })
    });
    
    $(document).on('click','.set-default-front-url', function(){
        $.ajax({
            url:'<?= base_url("admincontrol/set_default_front_url") ?>',
            type:'POST',
            dataType:'json',
            data:{'action':'set_default_front_url'},
            success:function(json){
                window.location.reload();
            },
        })
    });
    
    $('.update_all_settings').on('change', function()
        {
            var checked = $(this).prop('checked') ? 1 : 0; // Convert boolean to 1 or 0
            var setting_key = $(this).data('setting_key');
            var setting_type = $(this).data('setting_type');
         
            $.ajax({
                url: '<?= base_url("admincontrol/update_all_settings") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    'action': 'update_all_settings',
                    'status': checked,  // Fixed this line
                    'setting_key': setting_key,
                    'setting_type': setting_type
                },
                success: function(json)
                {
                    if(json.success) {
                        showPrintMessage(json.success, 'success');
                    }
                },
            });
        });


    function editAds(id,client_key,unit_key,ad_section){
        $("#googleads\\[id\\]").val(id);
        $("#googleads\\[client_key\\]").val(client_key);
        $("#googleads\\[unit_key\\]").val(unit_key);
        $('#googleads\\[ad_section\\] option[value="'+ad_section+'"]').prop('selected', true);

    }

     function updateRegistrationAproval()
    {
        var status = 0; 
        var setting_key = "registration_approval";
        var setting_type = "store";

        $.ajax({
            url:'<?= base_url("admincontrol/update_all_settings") ?>',
            type:'POST',
            dataType:'json',
            data:{'action':'update_all_settings', status:status, setting_key:setting_key, setting_type:setting_type},
            success:function(json)
            {
            },
        });

    }

    //function to show button on large screens
    $(document).ready(function() {
        var $securitForm = $('#securitform');
        var isFixed = false;

        var handleScroll = function() {
            var scrollTop = $(window).scrollTop();
            var bottom_gap = ($(window).scrollTop() + $(window).height()) - $(document).height();
            var pageHeightExceedsWindow = $(document).height() > $(window).height();

            if (pageHeightExceedsWindow && scrollTop > 200 && bottom_gap < -50) {
                if (!isFixed) {
                    isFixed = true;
                    $securitForm.addClass('position-fixed bottom-0 end-0 m-3').hide().fadeIn(300);
                }
            } else {
                isFixed = false;
                $securitForm.removeClass('position-fixed bottom-0 end-0 m-3');
            }
        };

        // Initial check and event bindings
        handleScroll();
        $(window).on('scroll resize', handleScroll);
    });
    //function to show button on large screens

    $(document).ready(function(){
        $.ajax({
            url:'<?= base_url("admincontrol/set_default_theme_color_settings") ?>',
            type:'POST',
            dataType:'json',
            data:{'action':'set_default_theme_color_settings', 'setting_type':'theme'},
            success:function(json){

            },
        });

        $.ajax({
            url:'<?= base_url("admincontrol/set_default_theme_font_settings") ?>',
            type:'POST',
            dataType:'json',
            data:{'action':'set_default_theme_font_settings', 'setting_type':'site'},
            success:function(json){

            },
        });
    });

    function changeLanguage()
    {
       $(".alert-dismissable").remove();
        $this = $(this);
        var originalText = $this.html();
        $this.html('<i class="bi bi-hourglass-split me-2"></i><?= __('admin.loading') ?>...').prop('disabled', true);
        $.ajax({
            url:'<?= base_url("admincontrol/getTermAndCondition") ?>',
            type:'POST',
            dataType:'json',
            data:{language_id:$("#drpLanguage").val()},
            success:function(json){
                $this.html(originalText).prop('disabled', false);
                 if(json.error){
                 }
                 else
                 {
                    $("input[name='tnc[heading]']").val(json.heading);
                    $('.summernote-img').summernote('code', '')
                    $('.summernote-img').html(escape($('.summernote-img').summernote('code', json.content)))
                 }
             
             },
            error: function() {
                $this.html(originalText).prop('disabled', false);
            }
        });
        
       return false;
        
    }

    // Admin AI Usage Dashboard JavaScript
    $(document).ready(function() {
        // Add CSS to disable all progress bar animations
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                .no-animation,
                .no-animation *,
                #daily-progress-bar,
                #monthly-progress-bar {
                    -webkit-transition: none !important;
                    -moz-transition: none !important;
                    -o-transition: none !important;
                    transition: none !important;
                    -webkit-animation: none !important;
                    -moz-animation: none !important;
                    -o-animation: none !important;
                    animation: none !important;
                }
                .progress-bar.no-animation {
                    transition-duration: 0s !important;
                    animation-duration: 0s !important;
                }
            `)
            .appendTo('head');
            
        loadAdminAIUsage();
        
        // Auto-refresh every 60 seconds
        setInterval(loadAdminAIUsage, 60000);
        
        // Manual refresh button
        $('#refresh-usage-data').on('click', function() {
            loadAdminAIUsage();
        });
    });

    function loadAdminAIUsage() {
        // Show loading state
        $('#refresh-usage-data').html('<i class="bi bi-hourglass-split me-2"></i> <?= __('admin.loading') ?>...');
        
        $.ajax({
            global: false,
            url: '<?= base_url("admincontrol/admin_ai_usage") ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update overview cards
                $('#admin-requests-today').text(data.usage.today || '0');
                $('#admin-requests-month').text(data.usage.month || '0');
                $('#admin-remaining-daily').text(data.remaining.daily || '0');
                $('#admin-remaining-monthly').text(data.remaining.monthly || '0');
                
                // Update progress bars
                updateProgressBars(data.limits, data.usage);
                
                // Update cost information
                $('#admin-cost-today').text('$' + (data.costs.today || '0.00'));
                $('#admin-cost-month').text('$' + (data.costs.month || '0.00'));
                
                // Update usage chart
                updateAdminUsageChart(data.chart_data || []);
                
                // Reset button text
                $('#refresh-usage-data').html('<i class="bi bi-arrow-repeat me-2"></i> <?= __('admin.refresh_usage_data') ?>');
            },
            error: function() {
                // Show error state
                $('#admin-requests-today, #admin-requests-month, #admin-remaining-daily, #admin-remaining-monthly').text('Error');
                $('#admin-cost-today, #admin-cost-month').text('Error');
                $('#refresh-usage-data').html('<i class="bi bi-arrow-repeat me-2"></i> <?= __('admin.refresh_usage_data') ?>');
            }
        });
    }

    function updateProgressBars(limits, usage) {
        // Daily progress
        var dailyPercent = limits.daily > 0 ? Math.min(100, Math.round((usage.today / limits.daily) * 100)) : 0;
        
        // Get daily progress bar element
        var dailyBar = $('#daily-progress-bar');
        
        // Completely stop all animations and transitions
        dailyBar.stop(true, true)
                .off('transitionend webkitTransitionEnd oTransitionEnd')
                .removeClass('bg-primary bg-warning bg-danger')
                .addClass('no-animation')
                .css({
                    'transition': 'none',
                    'animation': 'none',
                    '-webkit-transition': 'none',
                    '-webkit-animation': 'none'
                });
        
        // Set color based on usage
        if (dailyPercent >= 90) {
            dailyBar.addClass('bg-danger');
        } else if (dailyPercent >= 70) {
            dailyBar.addClass('bg-warning');
        } else {
            dailyBar.addClass('bg-primary');
        }
        
        // Force immediate width change without any transition
        dailyBar.css('width', dailyPercent + '%');
        $('#daily-usage-text').text(usage.today + ' / ' + limits.daily);
        
        // Monthly progress
        var monthlyPercent = limits.monthly > 0 ? Math.min(100, Math.round((usage.month / limits.monthly) * 100)) : 0;
        
        // Get monthly progress bar element
        var monthlyBar = $('#monthly-progress-bar');
        
        // Completely stop all animations and transitions
        monthlyBar.stop(true, true)
                  .off('transitionend webkitTransitionEnd oTransitionEnd')
                  .removeClass('bg-success bg-warning bg-danger')
                  .addClass('no-animation')
                  .css({
                      'transition': 'none',
                      'animation': 'none',
                      '-webkit-transition': 'none',
                      '-webkit-animation': 'none'
                  });
        
        // Set color based on usage
        if (monthlyPercent >= 90) {
            monthlyBar.addClass('bg-danger');
        } else if (monthlyPercent >= 70) {
            monthlyBar.addClass('bg-warning');
        } else {
            monthlyBar.addClass('bg-success');
        }
        
        // Force immediate width change without any transition
        monthlyBar.css('width', monthlyPercent + '%');
        $('#monthly-usage-text').text(usage.month + ' / ' + limits.monthly);
        
        // Force a repaint to ensure changes are applied immediately
        dailyBar[0].offsetHeight;
        monthlyBar[0].offsetHeight;
    }

    function updateAdminUsageChart(chartData) {
        try {
            // Clear the chart container
            $('#ai-usage-chart').empty();
            
            if (chartData && chartData.length > 0) {
                // Validate and format data for Morris.js
                var validData = [];
                
                chartData.forEach(function(item, index) {
                    if (item.date && typeof item.requests !== 'undefined') {
                        var requests = parseInt(item.requests);
                        if (isNaN(requests)) {
                            requests = 0;
                        }
                        
                        validData.push({
                            day: index + 1, // Use simple numeric index instead of date
                            date: item.date, // Keep original date for display
                            requests: requests
                        });
                    }
                });
                
                console.log('Chart data:', validData); // Debug log
                
                if (validData.length >= 2) { // Morris needs at least 2 data points
                    // Create new Morris.js chart with numeric x-axis
                    window.adminUsageChart = Morris.Line({
                        element: 'ai-usage-chart',
                        data: validData,
                        xkey: 'day', // Use numeric key instead of date
                        ykeys: ['requests'],
                        labels: ['<?= __('admin.requests') ?>'],
                        lineColors: ['#007bff'],
                        pointSize: 4,
                        lineWidth: 2,
                        gridTextColor: '#666',
                        gridTextSize: 11,
                        resize: true,
                        hideHover: 'auto',
                        xLabelFormat: function(x) {
                            // Get the corresponding date for this day index
                            var dataPoint = validData[x.x];
                            return dataPoint ? dataPoint.date : 'Day ' + (x.x + 1);
                        },
                        hoverCallback: function(index, options, content, row) {
                            return '<div class="morris-hover-row-label">' + row.date + '</div>' +
                                   '<div class="morris-hover-point">' + 
                                   '<?= __('admin.requests') ?>: ' + row.requests + 
                                   '</div>';
                        }
                    });
                } else {
                    $('#ai-usage-chart').html('<div class="text-center text-muted py-4"><i class="bi bi-info-circle me-2"></i>Insufficient data for chart (need at least 2 days)</div>');
                }
            } else {
                $('#ai-usage-chart').html('<div class="text-center text-muted py-4"><i class="bi bi-info-circle me-2"></i>No usage data available</div>');
            }
        } catch (error) {
            console.error('Chart error:', error);
            $('#ai-usage-chart').html('<div class="text-center text-danger py-4"><i class="bi bi-exclamation-triangle me-2"></i>Error loading chart: ' + error.message + '</div>');
        }
    }

    $("#videolink").change(function(){
        var url = $('#videolink').val();
        if(url.toLowerCase().includes("youtube") && !url.toLowerCase().includes("embed")){
            $id = url.split("v=");
            url = 'https://www.youtube.com/embed/'+$id[1];
        } else if(url.toLowerCase().includes("youtu") && !url.toLowerCase().includes("embed")){
            $id = url.split("/");
            url = 'https://www.youtube.com/embed/'+$id[3];
        }
        loadIframe('ifrm_videoid',url);
    });

      function loadIframe(iframeName, url) {
        var $iframe = $('#' + iframeName);
        if ( $iframe.length ) {
            $iframe.attr('src',url);   
            return false;
        }
        return true;
    }
</script>


        <!--Models-->
        <!-- Gmail Modal -->
        <div class="modal fade" id="smtpGmailHelpModal" tabindex="-1" aria-labelledby="smtpGmailHelpModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="smtpGmailHelpModalLabel"><i class="fab fa-google me-2"></i> <?= __('admin.smtp_gmail_setup_guide') ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body p-4">
                <ol class="small mb-0">
                  <li><a href="https://myaccount.google.com/security" target="_blank"><?= __('admin.smtp_gmail_step_1') ?></a></li>
                  <li><a href="https://myaccount.google.com/apppasswords" target="_blank"><?= __('admin.smtp_gmail_step_2') ?></a></li>
                  <li><?= __('admin.smtp_gmail_step_3') ?>
                    <ul>
                      <li><strong><?= __('admin.smtp_host') ?>:</strong> smtp.gmail.com</li>
                      <li><strong><?= __('admin.smtp_port') ?>:</strong> 587</li>
                      <li><strong><?= __('admin.smtp_encryption') ?>:</strong> TLS</li>
                      <li><strong><?= __('admin.smtp_username') ?>:</strong> <?= __('admin.your_gmail_address') ?></li>
                      <li><strong><?= __('admin.smtp_password') ?>:</strong> <?= __('admin.generated_app_password') ?></li>
                    </ul>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <!-- cPanel/Plesk Modal -->
        <div class="modal fade" id="smtpCpanelHelpModal" tabindex="-1" aria-labelledby="smtpCpanelHelpModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
              <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="smtpCpanelHelpModalLabel"><i class="fas fa-server me-2"></i> <?= __('admin.smtp_cpanel_setup_guide') ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body p-4">
                <ol class="small mb-0">
                  <li><?= __('admin.smtp_cpanel_step_1') ?></li>
                  <li><?= __('admin.smtp_cpanel_step_2') ?></li>
                  <li><?= __('admin.smtp_cpanel_step_3') ?>
                    <ul>
                      <li><strong><?= __('admin.smtp_host') ?>:</strong> mail.yourdomain.com</li>
                      <li><strong><?= __('admin.smtp_port') ?>:</strong> 465 (SSL) or 587 (TLS)</li>
                      <li><strong><?= __('admin.smtp_encryption') ?>:</strong> SSL or TLS</li>
                      <li><strong><?= __('admin.smtp_username') ?>:</strong> <?= __('admin.your_email_address') ?></li>
                      <li><strong><?= __('admin.smtp_password') ?>:</strong> <?= __('admin.your_email_password') ?></li>
                    </ul>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--Models-->

<script>
// AI Helper Settings JavaScript
$(document).ready(function() {
    // Handle AI provider switching
    $('#ai-provider-select').on('change', function() {
        var selectedProvider = $(this).val();
        showProviderSettings(selectedProvider);
    });
    
    // Function to show/hide provider settings
    function showProviderSettings(provider) {
        // Hide all provider settings first
        $('.ai-provider-settings').hide();
        
        // Show the selected provider's settings
        if (provider) {
            $('.ai-provider-settings[data-provider="' + provider + '"]').show();
        }
    }
    
    // Initialize provider display on page load
    var currentProvider = $('#ai-provider-select').val() || 'openai';
    showProviderSettings(currentProvider);
    
    // Password toggle functionality
    $('.toggle-password').on('click', function() {
        var input = $(this).closest('.input-group').find('input');
        var icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
    
    // Test AI Connection
    $('#test-ai-connection').on('click', function() {
        var btn = $(this);
        var originalText = btn.html();
        
        btn.html('<i class="bi bi-hourglass-split me-1"></i> <?= __('admin.testing') ?>...').prop('disabled', true);
        
        $.ajax({
            url: '<?= base_url('admincontrol/test_ai_connection') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    btn.html('<i class="bi bi-check-circle me-1"></i> <?= __('admin.connection_successful') ?>').removeClass('btn-outline-primary').addClass('btn-success');
                    setTimeout(function() {
                        btn.html(originalText).removeClass('btn-success').addClass('btn-outline-primary').prop('disabled', false);
                    }, 3000);
                } else {
                    btn.html('<i class="bi bi-x-circle me-1"></i> <?= __('admin.connection_failed') ?>').removeClass('btn-outline-primary').addClass('btn-danger');
                    alert('<?= __('admin.connection_error') ?>: ' + (response.message || '<?= __('admin.unknown_error') ?>'));
                    setTimeout(function() {
                        btn.html(originalText).removeClass('btn-danger').addClass('btn-outline-primary').prop('disabled', false);
                    }, 3000);
                }
            },
            error: function() {
                btn.html('<i class="bi bi-x-circle me-1"></i> <?= __('admin.connection_failed') ?>').removeClass('btn-outline-primary').addClass('btn-danger');
                alert('<?= __('admin.connection_error') ?>: <?= __('admin.network_error') ?>');
                setTimeout(function() {
                    btn.html(originalText).removeClass('btn-danger').addClass('btn-outline-primary').prop('disabled', false);
                }, 3000);
            }
        });
    });

    // Reset to Default Colors Button
    $('#reset-default-colors').click(function() {
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="spinner-border spinner-border-sm me-1"></i>Resetting...').prop('disabled', true);
        
        // Call the existing controller method to reset colors properly
        $.ajax({
            url:'<?= base_url("admincontrol/set_default_theme_color_settings") ?>',
            type:'POST',
            dataType:'json',
            data:{'action':'set_default_theme_color_settings', 'setting_type':'theme'},
            success:function(json){
                // Get defaults from controller to avoid duplication
                $.get('<?= base_url("admincontrol/get_default_theme_colors") ?>', function(defaults) {
                    defaults = JSON.parse(defaults);
                    
                    // Apply all default colors dynamically
                    for (let key in defaults) {
                        $('input[name="theme[' + key + ']"]').val(defaults[key]);
                    }
                    
                    showToast('<?= __("admin.reset_complete") ?>', 'All admin interface colors reset to defaults', 'success', 3000);
                    btn.html(originalText).prop('disabled', false);
                }).fail(function() {
                    showToast('<?= __("admin.error") ?>', 'Failed to load default colors', 'error', 3000);
                    btn.html(originalText).prop('disabled', false);
                });
            },
            error:function(){
                showToast('<?= __("admin.error") ?>', 'Failed to reset colors', 'error', 3000);
                btn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Reset User Side Colors to Default
    $('#reset-user-default-colors').click(function() {
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="spinner-border spinner-border-sm me-1"></i><?= __("admin.resetting") ?>...').prop('disabled', true);
        
        // User side default colors (Original Bootstrap 5 Blue Design)
        var userDefaults = {
            'user_top_navbar_bg': '#0d6efd',
            'user_top_navbar_text': '#ffffff',
            'user_top_navbar_button_bg': '#ffffff',
            'user_top_navbar_button_text': '#212529',
            'user_horizontal_menu_bg': '#212529',
            'user_horizontal_menu_text': '#ffffff',
            'user_horizontal_menu_hover_bg': '#0b5ed7',
            'user_horizontal_menu_hover_text': '#ffffff',
            'user_dropdown_bg': '#ffffff',
            'user_dropdown_text': '#212529',
            'user_footer_bg': '#f8f9fa',
            'user_footer_text': '#6c757d',
            'user_button_color': '#0d6efd',
            'user_button_hover_color': '#0b5ed7'
        };
        
        // Apply all user side default colors
        for (let key in userDefaults) {
            $('input[name="theme[' + key + ']"]').val(userDefaults[key]);
        }
        
        showToast('<?= __("admin.reset_complete") ?>', '<?= __("admin.user_side_colors_reset") ?>', 'success', 3000);
        btn.html(originalText).prop('disabled', false);
    });

    // Individual Default Theme Setting Buttons
    $('.default-theme-setting').click(function() {
        var btn = $(this);
        var settingName = btn.val();
        var colorInput = btn.closest('.input-group').find('input[type="color"]');
        
        // Default values for all theme settings (matching the new Bootstrap 5 design)
        var defaultValues = {
            // Admin Side
            'admin_topbar_bg': '#ffffff',
            'admin_topbar_text': '#ffffff',
            'admin_dropdown_bg': '#ffffff',
            'admin_dropdown_text': '#212529',
            'admin_dropdown_hover_bg': '#e3f2fd',
            'admin_dropdown_hover_text': '#1976d2',
            'admin_menu_bg': '#f8f9fa',
            'admin_menu_text': '#ffffff',
            'admin_menu_hover_bg': '#e3f2fd',
            'admin_menu_hover_text': '#1976d2',
            'admin_submenu_bg': '#ffffff',
            'admin_submenu_text': '#212529',
            'admin_submenu_hover_bg': '#e3f2fd',
            'admin_submenu_hover_text': '#1976d2',
            'admin_breadcrumb_bg': '#f8f9fa',
            'admin_breadcrumb_text': '#6c757d',
            'admin_footer_bg': '#f8f9fa',
            'admin_footer_text': '#6c757d',
            'admin_button_color': '#0d6efd',
            'admin_button_hover_color': '#0b5ed7',
            
            // User Side (Original Bootstrap 5 Blue Design)
            'user_top_navbar_bg': '#0d6efd',
            'user_top_navbar_text': '#ffffff',
            'user_top_navbar_button_bg': '#ffffff',
            'user_top_navbar_button_text': '#212529',
            'user_horizontal_menu_bg': '#212529',
            'user_horizontal_menu_text': '#ffffff',
            'user_horizontal_menu_hover_bg': '#0b5ed7',
            'user_horizontal_menu_hover_text': '#ffffff',
            'user_dropdown_bg': '#ffffff',
            'user_dropdown_text': '#212529',
            'user_footer_bg': '#f8f9fa',
            'user_footer_text': '#6c757d',
            'user_button_color': '#0d6efd',
            'user_button_hover_color': '#0b5ed7',
            
            // Legacy keys (keeping for backward compatibility)
            'user_side_bar_color': '#ffffff',
            'user_side_bar_text_color': '#212529',
            'user_side_bar_clock_text_color': '#0d6efd',
            'user_side_bar_text_hover_color': '#0b5ed7',
            'user_top_bar_color': '#ffffff',
            'user_footer_color': '#f8f9fa'
        };
        
        // Set the default value
        if (defaultValues[settingName]) {
            colorInput.val(defaultValues[settingName]);
            
            // Add a visual feedback
            var icon = btn.find('i');
            icon.removeClass('bi-arrow-counterclockwise').addClass('bi-check-lg');
            setTimeout(function() {
                icon.removeClass('bi-check-lg').addClass('bi-arrow-counterclockwise');
            }, 1000);
        }
    });
});
</script>

<!-- Settings Hub Navigation Script -->
<script>
$(document).ready(function() {
    // Settings hub card click -> open that tab
    $('.settings-hub-card').on('click', function() {
        var tabId = $(this).data('tab');
        $('#settingsHub').slideUp(200);
        $('#settingsFormWrapper').slideDown(200, function() {
            // Activate the correct tab using Bootstrap 5 native API
            var tabEl = document.querySelector('#TabsNav a[href="#' + tabId + '"]');
            if(tabEl) {
                var tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        });
    });

    // Back to hub
    $('#backToSettingsHub').on('click', function() {
        $('#settingsFormWrapper').slideUp(200);
        $('#settingsHub').slideDown(200);
        // Deactivate all tabs
        $('#TabsNav a').removeClass('active show');
        $('.tab-pane').removeClass('active show');
    });

    // Handle direct URL hash (e.g., #email-setting) - run immediately and again after a tick to beat any race
    function applyHashTab() {
        var hash = window.location.hash;
        if (!hash || !hash.startsWith('#') || !$(hash).length) return;
        var hashTabEl = document.querySelector('#TabsNav a[href="' + hash + '"]');
        if (!hashTabEl) return;
        $('#TabsNav a.nav-link').removeClass('active');
        $('.tab-content .tab-pane').removeClass('active show');
        $(hashTabEl).addClass('active');
        $(hash).addClass('active show');
        $('.tab-content .tab-pane').not(hash).hide();
        $(hash).show();
        $('#settingsHub').hide();
        $('#settingsFormWrapper').show();
    }
    var hash = window.location.hash;
    if (hash && hash.startsWith('#') && $(hash).length) {
        applyHashTab();
        setTimeout(applyHashTab, 0);
        setTimeout(applyHashTab, 100);
    }

    // When user clicks a tab, clear inline display so Bootstrap controls visibility
    $(document).on('shown.bs.tab', '#TabsNav a[data-bs-toggle="tab"]', function() {
        $('.tab-content .tab-pane').css('display', '');
    });

    // Hover effect for hub cards
    $('.settings-hub-card').hover(
        function() { $(this).addClass('shadow border-primary'); },
        function() { $(this).removeClass('shadow border-primary'); }
    );
});
</script>