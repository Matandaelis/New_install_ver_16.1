<!doctype html>
<?php
  $db =& get_instance();
  $userdetails=$db->Product_model->userdetails(); 
  $SiteSetting =$db->Product_model->getSiteSetting();
  
  // Sidebar data for horizontal menu
  $referlevel_status = $db->Product_model->getSettings('referlevel', 'status');
  $market_vendor_marketvendorstatus = $db->Product_model->getSettings('market_vendor', 'marketvendorstatus');
  $vendor_storestatus = $db->Product_model->getSettings('vendor', 'storestatus');
  $membership_status = $db->Product_model->getSettings('membership', 'status');
  $store_status = $db->Product_model->getSettings('store', 'status');
  $market_tools_status = $db->Product_model->getSettings('market_tools', 'status');
  $award_level_status = $db->Product_model->getSettings('award_level','status');
  
  $sidebar_data = array (
      'mlm_is_enable' => isset($referlevel_status['status']) ? $referlevel_status['status'] : 0,
      'saas_is_enable' => ($market_vendor_marketvendorstatus['marketvendorstatus'] == 1 || $vendor_storestatus['storestatus'] == 1) ? 1 : 0,
      'membership_is_enable' => isset($membership_status['status']) ? $membership_status['status'] : 0,
      'store_is_enable' => isset($store_status['status']) ? $store_status['status'] : 0,
      'award_level_is_enable' => isset($award_level_status['status']) ? $award_level_status['status'] : 0,
      'market_tools_is_enable' => isset($market_tools_status['status']) ? $market_tools_status['status'] : 1,
  );
  $db->Product_model->ping($userdetails['id']);
  $products = $db->Product_model;
  $settings = $db->Setting_model;
  $notifications = $products->getnotificationnew('admin', null, 10);
  $notifications_count = $products->getnotificationnew_count('admin', null);
  $license = $products->getLicese();
  $LanguageHtml = $products->getLanguageHtml();
  $CurrencyHtml = $products->getCurrencyHtml();
  $noti_order = $products->hold_noti();
  // Load utility helper for theme functions
  $this->load->helper('utility');
  
  // Old colors (keeping for compatibility but not using)
  $admin_side_bar_color = $products->getSettings('theme','admin_side_bar_color');
  $admin_side_bar_scroll_color = $products->getSettings('theme','admin_side_bar_scroll_color');
  $admin_top_bar_color = $products->getSettings('theme','admin_top_bar_color');
  $admin_logo_color = $products->getSettings('theme','admin_logo_color');
  $admin_side_font = $products->getSettings('site','admin_side_font');
  $admin_footer_color = $products->getSettings('theme','admin_footer_color');
  $admin_button_color = $products->getSettings('theme','admin_button_color'); 
  $admin_button_hover_color = $products->getSettings('theme','admin_button_hover_color');
  $allToDo = $settings->allToDo();

  // Use existing checkReq() function from system_status
  $serverErrors = checkReq();
  $required = '';
  $validate = empty($serverErrors);
  if (!$validate) {
    $required = implode("\n", array_keys($serverErrors));
  }

  $page_id = $products->page_id();
  $serverReq = $serverErrors;
  require APPPATH."config/breadcrumb.php";
  $pageKey = $db->Product_model->page_id();
  $this->load->helper('admin_permission');
  $has_settings_access = function_exists('can_admin') && (
    can_admin('settings') || can_admin('settings.payment') || can_admin('settings.language') ||
    can_admin('settings.theme') || can_admin('settings.addons') || can_admin('settings.coupons') ||
    can_admin('settings.mails') || can_admin('settings.backup') || can_admin('settings.system')
  );
  $has_users_access = function_exists('can_admin') && (can_admin('users') || can_admin('admin.admins'));
  $has_reports_access = function_exists('can_admin') && (
    can_admin('reports') || can_admin('reports.orders') || can_admin('reports.s2s') ||
    can_admin('reports.statistics') || can_admin('reports.transactions') || can_admin('reports.wallet')
  );
  $has_marketing_access = function_exists('can_admin') && (
    can_admin('marketing') || can_admin('marketing.deposits') || can_admin('marketing.campaigns')
  );
  ?>
  <html lang="en" data-bs-theme="<?= isset($userdetails['theme_preference']) ? htmlspecialchars($userdetails['theme_preference']) : 'light' ?>">
  <script>
    (function(){var t=localStorage.getItem('v14_theme');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-bs-theme',t);})();
  </script>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $SiteSetting['name'] ?> - <?= __('admin.menu_admin_panel') ?></title>
    <meta content="<?= $SiteSetting['meta_description'] ?>" name="description" />
    <meta content="<?= $SiteSetting['meta_author'] ?>" name="author" />
    <meta content="<?= $SiteSetting['meta_keywords'] ?>" name="keywords" />
    <input type="hidden" id="theme_Name" value="admin_theme">
    <?php if($SiteSetting['favicon']){ ?>
    <link rel="icon" href="<?= base_url('assets/images/site/'.$SiteSetting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php } ?>

    <!-- CSS Files - Load First -->
    <!-- Local Fonts for Admin Panel -->
    <link rel="stylesheet" href="<?= base_url('assets/template/fonts/admin-fonts.css') ?>?v=<?= av() ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-toggle.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/all.min.css') ?>?v=<?= av() ?>">
    
    <!-- Essential Plugin CSS -->
    <link href="<?= base_url('assets/plugins/datatable/select2.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/plugins/datetimepicker/jquery.datetimepicker.min.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/jquery-confirm.min.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    
    <!-- Admin Theme CSS Variables (Generated by Helper) -->
    <?= generate_admin_theme_css() ?>
    <link href="<?= base_url('assets/template/css/topnav-modern.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/sidebar-modern.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/footer-modern.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/horizontal-nav-modern.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/admin-dashboard-custom.css') ?>?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/template/css/admin-colors-dynamic.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href='<?= base_url('assets/template/css/toast.css') ?>?v=<?= av() ?>' rel='stylesheet' type="text/css"/>
    
    <!-- V14 Theme & Animations -->
    <link href="<?= base_url('assets/template/css/theme-variables.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/dark-mode-overrides.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/dashboard-animations.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    
    <!-- Admin Integration Tools CSS -->
    <link href="<?= base_url('assets/template/css/admin-integration.css') ?>?v=<?= av() ?>" rel="stylesheet"/>

    <!-- Tabler Design System Integration -->
    <link href="<?= base_url('assets/template/css/tabler-integration.css') ?>?v=<?= av() ?>" rel="stylesheet"/>

    <!-- Tabler Dashboard Overrides -->
    <link href="<?= base_url('assets/template/css/tabler-dashboard.css') ?>?v=<?= av() ?>" rel="stylesheet"/>

    <!-- Permission Selector (Roles / Admin Users) - loads last to ensure visibility -->
    <link href="<?= base_url('assets/template/css/admin-permissions-modern.css') ?>?v=<?= av() ?>" rel="stylesheet"/>

    <?php if (!empty($admin_users_list_css)): ?>
    <link href="<?= base_url('assets/template/css/admin-users-list.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <?php endif; ?>

    <!-- Summernote CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/template/summernote/summernote-lite.min.css') ?>?v=<?= av() ?>">

    <!-- JavaScript Files - Load After CSS -->
    <!-- Single jQuery Version (3.6.0) -->
    <script src="<?= base_url('assets/template/js/jquery-3.6.0.min.js'); ?>?v=<?= av() ?>"></script>
    
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js'); ?>?v=<?= av() ?>"></script>
    
    <!-- Essential Plugin JS -->
    <script src="<?= base_url('assets/plugins/datatable/select2.min.js'); ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/plugins/datetimepicker/jquery.datetimepicker.full.min.js'); ?>?v=<?= av() ?>"></script>
    
    <!-- Navigation Positioning -->
    <script src="<?= base_url('assets/template/js/horizontal-nav-positioning.js'); ?>?v=<?= av() ?>"></script>

    <?php if($SiteSetting['google_analytics'] != '') echo $SiteSetting['google_analytics']; ?>

    <!-- Global Variables -->
    <script type="text/javascript">
      window.affiliatePro = {
        base_url:"<?= base_url() ?>"
      }
    </script>

    <!-- Essential Functions Only -->
    <script type="text/javascript">
      // Settings panel toggle
      function toggleSettingsPanel() {
        document.getElementById('settingsPanel').classList.toggle('open');
        document.getElementById('settingsOverlay').classList.toggle('show');
      }
      
      // Toggle configuration issues details
      function toggleIssues() {
        const details = document.getElementById('issuesDetails');
        if (details) {
          details.style.display = details.style.display === 'none' ? 'block' : 'none';
        }
      }
      

      // Offcanvas nav links — close panel then navigate
      document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.aup-nav-link').forEach(function(el) {
          el.addEventListener('click', function(e) {
            var href = this.getAttribute('href');
            if (!href || href === '#') return;
            e.preventDefault();
            var ocEl = document.getElementById('adminProfilePanel');
            if (ocEl) {
              var oc = bootstrap.Offcanvas.getOrCreateInstance(ocEl);
              oc.hide();
              ocEl.addEventListener('hidden.bs.offcanvas', function() {
                window.location.href = href;
              }, { once: true });
            } else {
              window.location.href = href;
            }
          });
        });
      });
      
      

    </script>

  <?php if(is_rtl()){ ?>
    <!-- RTL CSS code here -->
  <?php } ?>
<?= render_js_error_listener() ?>
</head>

<body class="body-common-class admin_side_bar_color" 
      style="font-family: <?= $admin_side_font['admin_side_font'] ?> !important;" 
      data-demo-mode="<?php echo (ENVIRONMENT === 'demo') ? 'true' : 'false'; ?>">

    <?php 
    $fbmessager_status = (array)json_decode($SiteSetting['fbmessager_status'],1);
    if(in_array('admin', $fbmessager_status))
      echo $SiteSetting['fbmessager_script'];
    ?>
    <?php show_messenger_button($SiteSetting, 'admin'); ?>

    <?php if (ENVIRONMENT === 'demo'): ?>
    <div id="demo-mode-bar" class="position-fixed top-0 start-0 w-100" style="z-index:1060;">
        <div class="demo-bar-inner">
            <span class="demo-bar-version">
                <i class="fas fa-code-branch"></i>
                v<?= defined('SCRIPT_VERSION') ? SCRIPT_VERSION : '' ?>
            </span>
            <span class="demo-bar-center">
                <span class="demo-bar-dot"></span>
                <span class="demo-bar-badge">DEMO</span>
                <span class="demo-bar-msg d-none d-md-inline"><?= __('admin.demo_mode_notice') ?></span>
            </span>
            <span class="demo-bar-right d-none d-lg-flex">
                <i class="fas fa-shield-alt"></i>
                <?= __('admin.demo_mode') ?>
            </span>
        </div>
    </div>
    <?php endif; ?>

    <div class="admin-wrapper" style="<?php if (ENVIRONMENT === 'demo') echo 'margin-top: 38px;'; ?>">
        <!-- Sidebar will be inserted here by sidebar.php -->
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation Bar -->
            <div class="admin-topbar">
                <div class="topbar-content">
                    <!-- Left Side - Dynamic Page Title -->
                    <div class="topbar-left">
                        <a href="<?= base_url('admincontrol/dashboard') ?>" class="text-decoration-none">
                            <h4 class="mb-0 d-none d-md-block text-white">
                                <?php 
                                // Show dynamic page title from breadcrumb config, or fallback
                                if (isset($pageSetting[$pageKey]['title']) && $pageSetting[$pageKey]['title']) {
                                    echo $pageSetting[$pageKey]['title'];
                                } else {
                                    echo isset($lang['dashboard_overview']) ? $lang['dashboard_overview'] : 'Dashboard Overview';
                                }
                                ?>
                            </h4>
                        </a>
                    </div>

                    <!-- Right Side -->
                    <div class="topbar-right">
                        <!-- Mobile Menu Toggle (visible only on small screens) -->
                        <button class="btn btn-outline-light d-lg-none mobile-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavMenu" aria-expanded="false" aria-controls="mobileNavMenu">
                            <i class="fas fa-bars"></i>
                        </button>

                        <!-- Dark Mode Toggle — hidden on xs, visible sm+ -->
                        <button class="theme-toggle-btn d-none d-sm-inline-flex" title="<?= __('admin.toggle_dark_mode') ?>">
                            <i class="fas fa-moon"></i>
                            <i class="fas fa-sun"></i>
                        </button>

                        <!-- Currency Dropdown — hidden on xs, visible sm+ -->
                        <div class="dropdown d-none d-sm-flex">
                            <button class="topbar-btn dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-dollar-sign"></i>
                                <span class="d-none d-md-inline" id="current-currency"><?= isset($_SESSION['userCurrency']) ? $_SESSION['userCurrency'] : 'USD' ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header"><i class="fas fa-coins me-2"></i><?= __('admin.select_currency') ?></h6></li>
                                <?php 
                                $currencies = $this->db->query("SELECT * FROM currency WHERE status=1")->result_array();
                                $currency_icons = ['fa-dollar-sign text-success', 'fa-euro-sign text-primary', 'fa-pound-sign text-warning', 'fa-yen-sign text-danger', 'fa-bitcoin text-info'];
                                foreach($currencies as $key => $curr): 
                                    $icon_class = $currency_icons[$key % count($currency_icons)];
                                ?>
                                <li><a class="dropdown-item" href="<?= base_url('Admincontrol/change_currency/'.$curr['code']) ?>">
                                    <i class="fas <?= $icon_class ?>"></i> <?= $curr['code'] ?> - <?= $curr['title'] ?>
                                </a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Language Dropdown — hidden on xs/sm, visible md+ -->
                        <div class="dropdown d-none d-md-flex">
                            <button class="topbar-btn dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-language"></i>
                                <span class="d-none d-md-inline" id="current-language"><?= isset($_SESSION['userLangName']) ? $_SESSION['userLangName'] : 'English' ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header"><i class="fas fa-globe me-2"></i><?= __('admin.select_language') ?></h6></li>
                                <?php 
                                $languages = $this->db->query("SELECT * FROM language WHERE status=1")->result_array();
                                foreach($languages as $lang): ?>
                                <li><a class="dropdown-item" href="<?= base_url('Admincontrol/change_language/'.$lang['id']) ?>">
                                    <img src="<?= base_url($lang['flag']) ?>" alt="<?= $lang['name'] ?>" class="language-flag" onerror="this.onerror=null;this.style.display='none'">
                                    <?= $lang['name'] ?>
                                </a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Hidden backend elements for compatibility -->
                        <div style="display: none;">
                          <?= $CurrencyHtml ?>
                          <?= $LanguageHtml ?>
                        </div>

                        <!-- Notifications → triggers offcanvas (no resetNotify here — that would bulk-mark all as read) -->
                        <button class="topbar-btn position-relative" data-bs-toggle="offcanvas" data-bs-target="#adminNotifPanel">
                            <i class="fas fa-bell"></i>
                            <span class="d-none d-lg-inline"><?= __('admin.notifications') ?></span>
                            <?php if($notifications_count > 0): ?>
                                <span class="notification-badge ajax-notifications_count"><?= $notifications_count; ?></span>
                            <?php endif; ?>
                        </button>

                        <!-- User Button → triggers profile offcanvas -->
                        <button class="btn d-flex align-items-center text-white border-0"
                                data-bs-toggle="offcanvas" data-bs-target="#adminProfilePanel">
                            <img src="<?= $products->getAvatar($userdetails['avatar']) ?>" class="rounded-circle me-0 me-lg-2 border border-white border-opacity-50 topbar-user-avatar" width="32" height="32">
                            <div class="text-start d-none d-lg-block">
                                <div class="topbar-admin-name"><?= htmlspecialchars($userdetails['firstname']) ?></div>
                                <span class="topbar-role-badge"><?= get_admin_role_label() ?></span>
                            </div>
                            <i class="fas fa-chevron-down ms-2 small d-none d-lg-inline"></i>
                        </button>

                        <!-- System Health Badge -->
                        <?php 
                        $missing_count = !empty($required) ? count(explode("\n", trim($required))) : 0;
                        if ($missing_count > 0): 
                        ?>
                        <div class="position-relative">
                            <a href="<?= base_url('admincontrol/system_issues') ?>" class="btn position-relative">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger topbar-issue-count">
                                    <?= $missing_count ?>
                                </span>
                            </a>
                        </div>
                         <?php endif; ?>

                        <!-- Settings Panel Toggle -->
                        <button class="btn text-white border-0" onclick="toggleSettingsPanel()">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu (Collapsible) - Full parity with desktop -->
            <div class="collapse d-lg-none" id="mobileNavMenu">
                <div class="mobile-nav-menu">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="mobile-nav-items">
                                    <!-- Dashboard -->
                                    <a href="<?= base_url('admincontrol/dashboard') ?>" class="mobile-nav-item">
                                        <i class="fas fa-home"></i>
                                        <span><?= __('admin.menu_dashboard') ?></span>
                                    </a>
                                    <?php if (function_exists('can_admin') && can_admin('analytics')): ?>
                                    <a href="<?= base_url('admincontrol/analytics_dashboard') ?>" class="mobile-nav-item">
                                        <i class="fas fa-chart-line"></i>
                                        <span><?= __('admin.analytics') ?></span>
                                    </a>
                                    <?php endif; ?>

                                    <!-- Marketing Section -->
                                    <?php if ($has_marketing_access || (can_admin('reports.orders') && $sidebar_data['membership_is_enable'] == 1)): ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMarketingCollapse" aria-expanded="false">
                                            <i class="fas fa-rocket"></i>
                                            <span><?= __('admin.nav_marketing') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileMarketingCollapse">
                                            <div class="mobile-nav-section-items">
                                                <?php if(can_admin('marketing.campaigns') && $sidebar_data['market_tools_is_enable'] == 1): ?>
                                                <a href="<?= base_url('integration/integration_tools') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-bullhorn"></i>
                                                    <span><?= __('admin.nav_campaigns') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('marketing') && $sidebar_data['store_is_enable'] == 1): ?>
                                                <a href="<?= base_url('admincontrol/store_dashboard') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-store"></i>
                                                    <span><?= __('admin.nav_store') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('reports.orders') && $sidebar_data['membership_is_enable'] == 1): ?>
                                                <a href="<?= base_url('membership/membership_orders') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-crown"></i>
                                                    <span><?= __('admin.nav_membership') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('marketing') && $sidebar_data['mlm_is_enable'] == 1): ?>
                                                <a href="<?= base_url('admincontrol/mlm_settings') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-project-diagram"></i>
                                                    <span><?= __('admin.nav_mlm') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('marketing') && $sidebar_data['award_level_is_enable'] == 1): ?>
                                                <a href="<?= base_url('admincontrol/award_level') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-award"></i>
                                                    <span><?= __('admin.nav_rewards') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('marketing') && $sidebar_data['saas_is_enable'] == 1): ?>
                                                <a href="<?= base_url('admincontrol/saas_setting') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-building"></i>
                                                    <span><?= __('admin.nav_saas') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('marketing.deposits') && $sidebar_data['saas_is_enable'] == 1): ?>
                                                <a href="<?= base_url('admincontrol/vendor_deposits') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-hand-holding-usd"></i>
                                                    <span><?= __('admin.nav_deposits') ?></span>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Users Section -->
                                    <?php if ($has_users_access): ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileUsersCollapse" aria-expanded="false">
                                            <i class="fas fa-users"></i>
                                            <span><?= __('admin.nav_users') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileUsersCollapse">
                                            <div class="mobile-nav-section-items">
                                                <a href="<?= base_url('admincontrol/userslist') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-list"></i>
                                                    <span><?= __('admin.nav_all_affiliates') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/usergroup') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-users-cog"></i>
                                                    <span><?= __('admin.nav_user_groups') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/userslisttree') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-sitemap"></i>
                                                    <span><?= __('admin.nav_referral_tree') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/userslistmail') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-envelope"></i>
                                                    <span><?= __('admin.nav_email_list') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/tickets') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-ticket-alt"></i>
                                                    <span><?= __('admin.nav_support_tickets') ?></span>
                                                </a>
                                                <?php if(can_admin('admin.admins')): ?>
                                                <a href="<?= base_url('admincontrol/admin_user') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-user-shield"></i>
                                                    <span><?= __('admin.nav_manage_admins') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/admin_roles') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-user-tag"></i>
                                                    <span><?= __('admin.nav_manage_roles') ?></span>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Reports Section -->
                                    <?php if ($has_reports_access): ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileReportsCollapse" aria-expanded="false">
                                            <i class="fas fa-chart-bar"></i>
                                            <span><?= __('admin.nav_reports') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileReportsCollapse">
                                            <div class="mobile-nav-section-items">
                                                <?php if(can_admin('reports.orders')): ?>
                                                <a href="<?= base_url('admincontrol/store_orders') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span><?= __('admin.nav_all_orders') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('reports')): ?>
                                                <a href="<?= base_url('admincontrol/store_logs') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-stream"></i>
                                                    <span><?= __('admin.nav_activity_logs') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('reports.s2s')): ?>
                                                <a href="<?= base_url('admincontrol/s2s_analytics') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-server"></i>
                                                    <span><?= __('admin.nav_s2s_analytics') ?? 'S2S Analytics' ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('reports.statistics')): ?>
                                                <a href="<?= base_url('incomereport') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-chart-bar"></i>
                                                    <span><?= __('admin.nav_user_statistics') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('reports.transactions')): ?>
                                                <a href="<?= base_url('reportController/admin_transaction') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                    <span><?= __('admin.nav_transactions') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('reports.statistics')): ?>
                                                <a href="<?= base_url('reportController/admin_statistics') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-chart-area"></i>
                                                    <span><?= __('admin.nav_graphs') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('reports.wallet')): ?>
                                                <a href="<?= base_url('admincontrol/mywallet') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-wallet"></i>
                                                    <span><?= __('admin.nav_wallet') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/wallet_requests_list') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-hand-holding-usd"></i>
                                                    <span><?= __('admin.admincontrol_wallet_requests_list') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/mass_payout') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-file-export"></i>
                                                    <span><?= __('admin.admincontrol_mass_payout') ?></span>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Settings Section -->
                                    <?php if ($has_settings_access): ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSettingsCollapse" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                            <span><?= __('admin.nav_settings') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileSettingsCollapse">
                                            <div class="mobile-nav-section-items">
                                                <?php if(can_admin('settings')): ?>
                                                <a href="<?= base_url('admincontrol/paymentsetting') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-sliders-h"></i>
                                                    <span><?= __('admin.nav_general_settings') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.payment')): ?>
                                                <a href="<?= base_url('admincontrol/payment_gateway') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-credit-card"></i>
                                                    <span><?= __('admin.nav_payment_gateway') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.addons')): ?>
                                                <a href="<?= base_url('admincontrol/addons') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-puzzle-piece"></i>
                                                    <span><?= __('admin.nav_addons') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.mails')): ?>
                                                <a href="<?= base_url('admincontrol/mails') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-envelope"></i>
                                                    <span><?= __('admin.nav_email_templates') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/subscriber_list') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-envelope-open-text"></i>
                                                    <span><?= __('admin.subscriber_list') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/unsubscribe_list') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-envelope-circle-check"></i>
                                                    <span><?= __('admin.unsubscribe_list') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.theme')): ?>
                                                <a href="<?= base_url('admincontrol/affiliate_theme') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-paint-brush"></i>
                                                    <span><?= __('admin.nav_affiliate_theme') ?></span>
                                                </a>
                                                <?php if($sidebar_data['store_is_enable'] == 1): ?>
                                                <a href="<?= base_url('admincontrol/store_theme_api_doc') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-code text-primary"></i>
                                                    <span><?= __('admin.nav_store_theme_api') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.language')): ?>
                                                <a href="<?= base_url('admincontrol/language') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-language"></i>
                                                    <span><?= __('admin.nav_language') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings')): ?>
                                                <a href="<?= base_url('admincontrol/currency_list') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-coins"></i>
                                                    <span><?= __('admin.nav_currency') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/countries_and_states') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-flag"></i>
                                                    <span><?= __('admin.nav_countries') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.system')): ?>
                                                <a href="<?= base_url('debug/sysupdatereport') ?>" class="mobile-nav-subitem" target="_blank">
                                                    <i class="fas fa-clipboard-list"></i>
                                                    <span><?= __('admin.nav_system_logs') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/system_status') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-heartbeat"></i>
                                                    <span><?= __('admin.nav_system_status') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/geolocation_status') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <span><?= __('admin.nav_geolocation') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/cron') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-clock"></i>
                                                    <span><?= __('admin.nav_cron_job') ?></span>
                                                </a>
                                                <a href="<?= base_url('admincontrol/troubleshoot') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-wrench"></i>
                                                    <span><?= __('admin.nav_troubleshoot') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.system')): ?>
                                                <a href="<?= base_url('/api-home') ?>" class="mobile-nav-subitem" target="_blank">
                                                    <i class="fas fa-book"></i>
                                                    <span><?= __('admin.nav_api_home') ?></span>
                                                </a>
                                                <a href="<?= base_url('/api-document') ?>" class="mobile-nav-subitem" target="_blank">
                                                    <i class="fas fa-code"></i>
                                                    <span><?= __('admin.nav_api_docs') ?></span>
                                                </a>
                                                <a href="<?= base_url('/admin-api-document') ?>" class="mobile-nav-subitem" target="_blank">
                                                    <i class="fas fa-shield-alt text-danger"></i>
                                                    <span><?= __('admin.nav_admin_api_docs') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.system')): ?>
                                                <a href="<?= base_url('admincontrol/todolist') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-tasks"></i>
                                                    <span><?= __('admin.nav_todo_list') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.backup')): ?>
                                                <a href="<?= base_url('admincontrol/backup') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-download"></i>
                                                    <span><?= __('admin.nav_backup') ?></span>
                                                </a>
                                                <?php endif; ?>
                                                <?php if(can_admin('settings.system')): ?>
                                                <a href="<?= base_url('admincontrol/tutorial') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-graduation-cap"></i>
                                                    <span><?= __('admin.nav_tutorial') ?></span>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area Starts Here -->
            <div class="horizontal-nav">
                <div class="container-fluid">
                    <nav class="nav-menu d-flex flex-wrap gap-2">
                        <!-- DASHBOARD -->
                        <a href="<?= base_url('admincontrol/dashboard') ?>" class="nav-item px-3 py-2 text-decoration-none rounded d-flex align-items-center">
                            <i class="fas fa-home me-2"></i>
                            <span><?= __('admin.menu_dashboard') ?></span>
                        </a>
                        
                        <?php if (function_exists('can_admin') && can_admin('analytics')): ?>
                        <a href="<?= base_url('admincontrol/analytics_dashboard') ?>" class="nav-item px-3 py-2 text-decoration-none rounded d-flex align-items-center">
                            <i class="fas fa-chart-line me-2"></i>
                            <span><?= __('admin.analytics') ?></span>
                        </a>
                        <?php endif; ?>

                        <!-- MARKETING (formerly BUSINESS) -->
                        <?php if ($has_marketing_access || (can_admin('reports.orders') && $sidebar_data['membership_is_enable'] == 1)): ?>
                        <div class="nav-dropdown dropdown">
                            <a href="#" class="nav-item px-3 py-2 text-decoration-none rounded d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-rocket me-2"></i>
                                <span><?= __('admin.nav_marketing') ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <?php if(can_admin('marketing.campaigns') && $sidebar_data['market_tools_is_enable'] == 1): ?>
                                <a class="dropdown-item" href="<?= base_url('integration/integration_tools') ?>">
                                    <i class="fas fa-bullhorn"></i> <?= __('admin.nav_campaigns') ?>
                                </a>
                                <?php endif; ?>

                                <?php if(can_admin('marketing') && $sidebar_data['store_is_enable'] == 1): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/store_dashboard') ?>">
                                    <i class="fas fa-store"></i> <?= __('admin.nav_store') ?>
                                </a>
                                <?php endif; ?>

                                <?php if(can_admin('reports.orders') && $sidebar_data['membership_is_enable'] == 1): ?>
                                <a class="dropdown-item" href="<?= base_url('membership/membership_orders') ?>">
                                    <i class="fas fa-crown"></i> <?= __('admin.nav_membership') ?>
                                </a>
                                <?php endif; ?>

                                <?php if(can_admin('marketing') && $sidebar_data['mlm_is_enable'] == 1): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/mlm_settings') ?>">
                                    <i class="fas fa-project-diagram"></i> <?= __('admin.nav_mlm') ?>
                                </a>
                                <?php endif; ?>

                                <?php if(can_admin('marketing') && $sidebar_data['award_level_is_enable'] == 1): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/award_level') ?>">
                                    <i class="fas fa-award"></i> <?= __('admin.nav_rewards') ?>
                                </a>
                                <?php endif; ?>

                                <?php if(can_admin('marketing') && $sidebar_data['saas_is_enable'] == 1): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/saas_setting') ?>">
                                    <i class="fas fa-building"></i> <?= __('admin.nav_saas') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('marketing.deposits') && $sidebar_data['saas_is_enable'] == 1): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/vendor_deposits') ?>">
                                    <i class="fas fa-hand-holding-usd"></i> <?= __('admin.nav_deposits') ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- USERS -->
                        <?php if ($has_users_access): ?>
                        <div class="nav-dropdown dropdown">
                            <a href="#" class="nav-item px-3 py-2 text-decoration-none rounded d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-users me-2"></i>
                                <span><?= __('admin.nav_users') ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= base_url('admincontrol/userslist') ?>">
                                    <i class="fas fa-list"></i> <?= __('admin.nav_all_affiliates') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/usergroup') ?>">
                                    <i class="fas fa-users-cog"></i> <?= __('admin.nav_user_groups') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/userslisttree') ?>">
                                    <i class="fas fa-sitemap"></i> <?= __('admin.nav_referral_tree') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/userslistmail') ?>">
                                    <i class="fas fa-envelope"></i> <?= __('admin.nav_email_list') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/tickets') ?>">
                                    <i class="fas fa-ticket-alt"></i> <?= __('admin.nav_support_tickets') ?>
                                </a>
                                <?php if(can_admin('admin.admins')): ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/admin_user') ?>">
                                    <i class="fas fa-user-shield"></i> <?= __('admin.nav_manage_admins') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/admin_roles') ?>">
                                    <i class="fas fa-user-tag"></i> <?= __('admin.nav_manage_roles') ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- REPORTS (consolidated: orders, logs, analytics, financial) -->
                        <?php if ($has_reports_access): ?>
                        <div class="nav-dropdown dropdown">
                            <a href="#" class="nav-item px-3 py-2 text-decoration-none rounded d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chart-bar me-2"></i>
                                <span><?= __('admin.nav_reports') ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <?php if(can_admin('reports.orders')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/store_orders') ?>">
                                    <i class="fas fa-shopping-cart"></i> <?= __('admin.nav_all_orders') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('reports')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/store_logs') ?>">
                                    <i class="fas fa-stream"></i> <?= __('admin.nav_activity_logs') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('reports.s2s')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/s2s_analytics') ?>">
                                    <i class="fas fa-server"></i> <?= __('admin.nav_s2s_analytics') ?? 'S2S Analytics' ?>
                                </a>
                                <?php endif; ?>

                                <?php if(can_admin('reports.statistics') || can_admin('reports.transactions')): ?>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header"><i class="fas fa-chart-pie me-1"></i> <?= __('admin.nav_section_analytics') ?></h6>
                                <?php if(can_admin('reports.statistics')): ?>
                                <a class="dropdown-item" href="<?= base_url('incomereport') ?>">
                                    <i class="fas fa-chart-bar"></i> <?= __('admin.nav_user_statistics') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('reports.transactions')): ?>
                                <a class="dropdown-item" href="<?= base_url('reportController/admin_transaction') ?>">
                                    <i class="fas fa-file-invoice-dollar"></i> <?= __('admin.nav_transactions') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('reports.statistics')): ?>
                                <a class="dropdown-item" href="<?= base_url('reportController/admin_statistics') ?>">
                                    <i class="fas fa-chart-area"></i> <?= __('admin.nav_graphs') ?>
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(can_admin('reports.wallet')): ?>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header"><i class="fas fa-wallet me-1"></i> <?= __('admin.nav_section_financial') ?></h6>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/mywallet') ?>">
                                    <i class="fas fa-wallet"></i> <?= __('admin.nav_wallet') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/wallet_requests_list') ?>">
                                    <i class="fas fa-hand-holding-usd"></i> <?= __('admin.admincontrol_wallet_requests_list') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/mass_payout') ?>">
                                    <i class="fas fa-file-export"></i> <?= __('admin.admincontrol_mass_payout') ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- SETTINGS (gear icon - replaces old SYSTEM with 14 items) -->
                        <?php if ($has_settings_access): ?>
                        <div class="nav-dropdown dropdown">
                            <a href="#" class="nav-item px-3 py-2 text-decoration-none rounded d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-2"></i>
                                <span><?= __('admin.nav_settings') ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <?php if(can_admin('settings')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/paymentsetting') ?>">
                                    <i class="fas fa-sliders-h"></i> <?= __('admin.nav_general_settings') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('settings.payment')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/payment_gateway') ?>">
                                    <i class="fas fa-credit-card"></i> <?= __('admin.nav_payment_gateway') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('settings.addons')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/addons') ?>">
                                    <i class="fas fa-puzzle-piece"></i> <?= __('admin.nav_addons') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('settings.mails')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/mails') ?>">
                                    <i class="fas fa-envelope"></i> <?= __('admin.nav_email_templates') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/subscriber_list') ?>">
                                    <i class="fas fa-envelope-open-text"></i> <?= __('admin.subscriber_list') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/unsubscribe_list') ?>">
                                    <i class="fas fa-envelope-circle-check"></i> <?= __('admin.unsubscribe_list') ?>
                                </a>
                                <?php endif; ?>

                                <?php if(can_admin('settings.theme')): ?>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header"><i class="fas fa-palette me-1"></i> <?= __('admin.nav_section_appearance') ?></h6>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/affiliate_theme') ?>">
                                    <i class="fas fa-paint-brush"></i> <?= __('admin.nav_affiliate_theme') ?>
                                </a>
                                <?php if($sidebar_data['store_is_enable'] == 1): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/store_theme_api_doc') ?>">
                                    <i class="fas fa-code text-primary"></i> <?= __('admin.nav_store_theme_api') ?>
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(can_admin('settings.language') || can_admin('settings')): ?>

                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header"><i class="fas fa-globe me-1"></i> <?= __('admin.nav_section_localization') ?></h6>
                                <?php if(can_admin('settings.language')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/language') ?>">
                                    <i class="fas fa-language"></i> <?= __('admin.nav_language') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('settings')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/currency_list') ?>">
                                    <i class="fas fa-coins"></i> <?= __('admin.nav_currency') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/countries_and_states') ?>">
                                    <i class="fas fa-flag"></i> <?= __('admin.nav_countries') ?>
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(can_admin('settings.system') || can_admin('settings.backup')): ?>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header"><i class="fas fa-server me-1"></i> <?= __('admin.nav_section_system') ?></h6>
                                <?php if(can_admin('settings.system')): ?>
                                <a class="dropdown-item" href="<?= base_url('debug/sysupdatereport') ?>" target="_blank">
                                    <i class="fas fa-clipboard-list"></i> <?= __('admin.nav_system_logs') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/system_status') ?>">
                                    <i class="fas fa-heartbeat"></i> <?= __('admin.nav_system_status') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/geolocation_status') ?>">
                                    <i class="fas fa-map-marker-alt"></i> <?= __('admin.nav_geolocation') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/cron') ?>">
                                    <i class="fas fa-clock"></i> <?= __('admin.nav_cron_job') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/troubleshoot') ?>">
                                    <i class="fas fa-wrench"></i> <?= __('admin.nav_troubleshoot') ?>
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(can_admin('settings.system')): ?>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header"><i class="fas fa-code me-1"></i> <?= __('admin.nav_section_developer') ?></h6>
                                <a class="dropdown-item" href="<?= base_url('/api-home') ?>" target="_blank">
                                    <i class="fas fa-book"></i> <?= __('admin.nav_api_home') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('/api-document') ?>" target="_blank">
                                    <i class="fas fa-code"></i> <?= __('admin.nav_api_docs') ?>
                                </a>
                                <a class="dropdown-item" href="<?= base_url('/admin-api-document') ?>" target="_blank">
                                    <i class="fas fa-shield-alt text-danger"></i> <?= __('admin.nav_admin_api_docs') ?>
                                </a>
                                <?php endif; ?>

                                <?php if(can_admin('settings.system') || can_admin('settings.backup')): ?>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header"><i class="fas fa-toolbox me-1"></i> <?= __('admin.nav_section_tools') ?></h6>
                                <?php if(can_admin('settings.system')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/todolist') ?>">
                                    <i class="fas fa-tasks"></i> <?= __('admin.nav_todo_list') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('settings.backup')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/backup') ?>">
                                    <i class="fas fa-download"></i> <?= __('admin.nav_backup') ?>
                                </a>
                                <?php endif; ?>
                                <?php if(can_admin('settings.system')): ?>
                                <a class="dropdown-item" href="<?= base_url('admincontrol/tutorial') ?>">
                                    <i class="fas fa-graduation-cap"></i> <?= __('admin.nav_tutorial') ?>
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>

            <!-- Settings Panel -->
            <div class="settings-panel" id="settingsPanel">
                <div class="settings-panel-header">
                    <h5 class="mb-0">
                        <i class="fas fa-robot me-2"></i><?= __('admin.admin_assistant') ?>
                    </h5>
                    <button class="btn btn-sm btn-outline-light" onclick="toggleSettingsPanel()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="settings-panel-body">
                    <!-- Dynamic Notifications -->
                    <div class="settings-section">
                        <h6 class="settings-section-title">
                            <i class="fas fa-bell me-2 text-primary"></i><?= __('admin.recent_activity') ?>
                        </h6>
                        <?php if($notifications_count > 0): ?>
                            <div class="settings-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold"><?= __('admin.unread_notifications') ?></div>
                                        <small class="text-primary">
                                            <?php if($notifications_count > 10): ?>
                                                <?= __('admin.showing') ?> 10 <?= __('admin.of') ?> <?= $notifications_count ?>
                                            <?php else: ?>
                                                <?= $notifications_count ?> <?= __('admin.new_items') ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <a href="<?= base_url('admincontrol/notification') ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i><?= __('admin.view') ?>
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="settings-item">
                                <div class="text-center text-muted">
                                    <i class="fas fa-check-circle fs-4 mb-2"></i>
                                    <div><?= __('admin.all_caught_up') ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- System Health -->
                    <div class="settings-section">
                        <h6 class="settings-section-title">
                            <i class="fas fa-heartbeat me-2 text-success"></i><?= __('admin.system_health') ?>
                        </h6>
                        <div class="settings-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= __('admin.php_version') ?></div>
                                    <small class="text-success"><?= phpversion() ?> ✓</small>
                                </div>
                                <div class="badge bg-success">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="settings-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= __('admin.database') ?></div>
                                    <small class="text-success"><?= __('admin.connected') ?> ✓</small>
                                </div>
                                <div class="badge bg-success">
                                    <i class="fas fa-database"></i>
                                </div>
                            </div>
                        </div>
                        <?php if($required): ?>
                        <div class="settings-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-bold"><?= __('admin.configuration_issues') ?></div>
                                    <small class="text-warning"><?= count(explode("\n", trim($required))) ?> <?= __('admin.settings_missing') ?></small>
                                </div>
                                <button class="btn btn-sm btn-outline-warning" onclick="toggleIssues()">
                                    <i class="fas fa-eye me-1"></i><?= __('admin.details') ?>
                                </button>
                            </div>
                            <div id="issuesDetails" style="display: none;">
                                <div class="border-top pt-2">
                                    <?php 
                                    $missing_list = explode("\n", trim($required));
                                    foreach($missing_list as $missing): 
                                        if(trim($missing)):
                                            $parts = explode(" - ", trim($missing));
                                            $section = $parts[0] ?? '';
                                            $field = $parts[1] ?? '';
                                    ?>
                                    <div class="d-flex align-items-center justify-content-between py-1">
                                        <div class="small">
                                            <span class="text-muted"><?= ucfirst($section) ?>:</span>
                                            <span class="fw-medium"><?= ucfirst(str_replace('_', ' ', $field)) ?></span>
                                        </div>
                                        <a href="<?= base_url('admincontrol/paymentsetting' . ($section ? '?tab=' . urlencode($section) : '')) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    <?php endif; endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Quick Actions -->
                    <div class="settings-section">
                        <h6 class="settings-section-title">
                            <i class="fas fa-bolt me-2 text-warning"></i><?= __('admin.quick_actions') ?>
                        </h6>
                        <div class="d-grid gap-2">
                            <?php if (can_admin('settings')): ?>
                            <a href="<?= base_url('admincontrol/paymentsetting') ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-cog me-2"></i><?= __('admin.system_settings') ?>
                            </a>
                            <?php endif; ?>
                            <?php if (can_admin('settings.backup')): ?>
                            <a href="<?= base_url('admincontrol/backup') ?>" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-download me-2"></i><?= __('admin.backup_system') ?>
                            </a>
                            <?php endif; ?>
                            <?php if (can_admin('settings.system')): ?>
                            <a href="<?= base_url('admincontrol/todolist') ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-tasks me-2"></i><?= __('admin.to_do_list') ?>
                            </a>
                            <a href="<?= base_url('admincontrol/system_status') ?>" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-server me-2"></i><?= __('admin.system_status') ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>



                    <!-- AI Assistant Placeholder -->
                    <div class="settings-section">
                        <h6 class="settings-section-title">
                            <i class="fas fa-robot me-2 text-info"></i><?= __('admin.ai_assistant') ?>
                        </h6>
                        <div class="settings-item">
                            <div class="text-center text-muted">
                                <i class="fas fa-sparkles fs-4 mb-2"></i>
                                <div class="fw-bold mb-1"><?= __('admin.coming_soon') ?></div>
                                <small><?= __('admin.ai_powered_suggestions') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Panel Overlay -->
            <div class="settings-overlay" id="settingsOverlay" onclick="toggleSettingsPanel()"></div>

            <!-- ======================================================
                 NOTIFICATIONS OFFCANVAS PANEL
                 ====================================================== -->
            <?php
            $last_id_notifications = 0;
            $notif_types_oc = [
                ['icon' => 'fas fa-user-plus',   'color' => '#28c76f', 'bg' => 'rgba(40,199,111,0.12)'],
                ['icon' => 'fas fa-shield-alt',  'color' => '#ff9f43', 'bg' => 'rgba(255,159,67,0.12)'],
                ['icon' => 'fas fa-dollar-sign', 'color' => '#00cfe8', 'bg' => 'rgba(0,207,232,0.12)'],
                ['icon' => 'fas fa-cog',         'color' => '#7367f0', 'bg' => 'rgba(115,103,240,0.12)'],
                ['icon' => 'fas fa-sync-alt',    'color' => '#ea5455', 'bg' => 'rgba(234,84,85,0.12)'],
            ];
            ?>
            <div class="offcanvas offcanvas-end anp-offcanvas" tabindex="-1" id="adminNotifPanel">

                <!-- Cover Header -->
                <div class="anp-cover">
                    <button type="button" class="aup-close-btn" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button>
                    <div class="anp-cover-icon"><i class="fas fa-bell"></i></div>
                    <div class="anp-cover-title"><?= __('admin.notification') ?></div>
                    <?php if($notifications_count > 0): ?>
                        <div class="anp-cover-badge">
                            <?php if($notifications_count > 10): ?>
                                <?= __('admin.showing') ?> 10 <?= __('admin.of') ?> <?= $notifications_count ?>
                            <?php else: ?>
                                <?= $notifications_count ?> <?= __('admin.new_items') ?>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="anp-cover-badge anp-badge-clear"><?= __('admin.all_caught_up') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Notification list -->
                <div class="anp-list">
                    <?php if(empty($notifications)): ?>
                        <div class="anp-empty">
                            <i class="fas fa-check-double anp-empty-icon"></i>
                            <div class="anp-empty-title"><?= __('admin.all_caught_up') ?></div>
                            <div class="anp-empty-sub"><?= __('admin.no_new_notifications') ?></div>
                        </div>
                    <?php else:
                        foreach ($notifications as $key => $notification):
                            if ($last_id_notifications <= $notification['notification_id']) {
                                $last_id_notifications = $notification['notification_id'];
                            }
                            $t = $notif_types_oc[$key % count($notif_types_oc)];
                            $notif_url = $notification['notification_url'];
                            if (strpos($notif_url, '/membership/') !== false) {
                                $notification_full_url = base_url() . ltrim($notif_url, '/');
                            } else {
                                $notification_full_url = base_url('admincontrol') . $notif_url;
                            }
                    ?>
                        <a class="anp-item" href="javascript:void(0)"
                           onclick="shownofication(<?= $notification['notification_id'] ?>, '<?= addslashes($notification_full_url) ?>')">
                            <div class="anp-item-icon" style="background:<?= $t['bg'] ?>;color:<?= $t['color'] ?>">
                                <i class="<?= $t['icon'] ?>"></i>
                            </div>
                            <div class="anp-item-body">
                                <div class="anp-item-title"><?= htmlspecialchars($notification['notification_title']) ?></div>
                                <div class="anp-item-desc"><?= htmlspecialchars($notification['notification_description']) ?></div>
                            </div>
                            <span class="anp-item-new">NEW</span>
                        </a>
                    <?php endforeach; endif; ?>
                    <input type="hidden" id="last_id_notifications" value="<?= $last_id_notifications ?>">
                </div>

                <!-- Footer -->
                <div class="anp-footer">
                    <a href="<?= base_url('admincontrol/notification') ?>" class="anp-footer-btn">
                        <i class="fas fa-list me-2"></i><?= __('admin.view_all_notifications') ?>
                    </a>
                </div>

            </div>
            <!-- /NOTIFICATIONS OFFCANVAS -->

            <!-- ======================================================
                 PROFILE OFFCANVAS PANEL
                 ====================================================== -->
            <div class="offcanvas offcanvas-end aup-offcanvas" tabindex="-1" id="adminProfilePanel">

                <!-- Cover / Hero -->
                <div class="aup-cover">
                    <button type="button" class="aup-close-btn" data-bs-dismiss="offcanvas">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="aup-avatar-ring">
                        <img src="<?= $products->getAvatar($userdetails['avatar']) ?>" alt="<?= htmlspecialchars($userdetails['firstname']) ?>" class="aup-avatar-img">
                    </div>
                    <div class="aup-cover-name"><?= htmlspecialchars($userdetails['firstname']) ?> <?= htmlspecialchars($userdetails['lastname']) ?></div>
                    <div class="aup-cover-role d-flex flex-wrap align-items-center gap-2">
                        <span class="aup-online-pill"><i class="fas fa-circle"></i> <?= __('admin.active') ?></span>
                        <span class="badge rounded-pill <?= function_exists('get_admin_role_badge_class') ? get_admin_role_badge_class() : 'bg-secondary text-white' ?> px-2 py-1"><i class="bi bi-shield-check me-1"></i><?= get_admin_role_label() ?></span>
                    </div>
                </div>

                <!-- Stats Strip -->
                <div class="aup-stats-strip">
                    <a href="<?= base_url('admincontrol/notification') ?>" class="aup-stat" data-bs-dismiss="offcanvas">
                        <div class="aup-stat-val"><?= $notifications_count ?: '0' ?></div>
                        <div class="aup-stat-lbl"><?= __('admin.notifications') ?></div>
                    </a>
                    <div class="aup-stat-sep"></div>
                    <div class="aup-stat">
                        <div class="aup-stat-val"><?= defined('SCRIPT_VERSION') ? SCRIPT_VERSION : 'v14' ?></div>
                        <div class="aup-stat-lbl"><?= __('admin.current_version') ?></div>
                    </div>
                    <div class="aup-stat-sep"></div>
                    <?php if (can_admin('settings.system')): ?>
                    <a href="<?= base_url('admincontrol/system_status') ?>" class="aup-stat" data-bs-dismiss="offcanvas">
                        <div class="aup-stat-val"><i class="fas fa-circle" style="font-size:.55rem;color:#28c76f;vertical-align:middle"></i></div>
                        <div class="aup-stat-lbl"><?= __('admin.system_status') ?></div>
                    </a>
                    <?php else: ?>
                    <div class="aup-stat">
                        <div class="aup-stat-val"><i class="fas fa-circle" style="font-size:.55rem;color:#28c76f;vertical-align:middle"></i></div>
                        <div class="aup-stat-lbl"><?= __('admin.system_status') ?></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Menu Items -->
                <div class="aup-menu">
                    <div class="aup-section-label"><?= __('admin.account_settings') ?></div>

                    <a href="<?= base_url('admincontrol/editProfile') ?>" class="aup-item aup-nav-link">
                        <div class="aup-item-icon" style="background:rgba(102,126,234,0.12);color:#667eea">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="aup-item-body">
                            <div class="aup-item-label"><?= __('admin.top_profile') ?></div>
                            <div class="aup-item-sub"><?= __('admin.manage_your_account') ?></div>
                        </div>
                        <i class="fas fa-chevron-right aup-item-arrow"></i>
                    </a>

                    <a href="<?= base_url('admincontrol/changePassword') ?>" class="aup-item aup-nav-link">
                        <div class="aup-item-icon" style="background:rgba(255,159,67,0.12);color:#ff9f43">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="aup-item-body">
                            <div class="aup-item-label"><?= __('admin.top_change_password') ?></div>
                            <div class="aup-item-sub"><?= __('admin.update_your_password') ?></div>
                        </div>
                        <i class="fas fa-chevron-right aup-item-arrow"></i>
                    </a>

                    <?php if (can_admin('reports.wallet')): ?>
                    <a href="<?= base_url('admincontrol/mywallet') ?>" class="aup-item aup-nav-link">
                        <div class="aup-item-icon" style="background:rgba(40,199,111,0.12);color:#28c76f">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="aup-item-body">
                            <div class="aup-item-label"><?= __('admin.top_my_wallet') ?></div>
                            <div class="aup-item-sub"><?= __('admin.balance_transactions') ?></div>
                        </div>
                        <i class="fas fa-chevron-right aup-item-arrow"></i>
                    </a>
                    <?php endif; ?>

                    <?php if (can_admin('settings')): ?>
                    <a href="<?= base_url('admincontrol/paymentsetting') ?>" class="aup-item aup-nav-link">
                        <div class="aup-item-icon" style="background:rgba(0,207,232,0.12);color:#00cfe8">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="aup-item-body">
                            <div class="aup-item-label"><?= __('admin.top_settings') ?></div>
                            <div class="aup-item-sub"><?= __('admin.system_configuration') ?></div>
                        </div>
                        <i class="fas fa-chevron-right aup-item-arrow"></i>
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Logout -->
                <div class="aup-footer">
                    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'demo'): ?>
                    <a href="<?= base_url('admincontrol/switch_admin') ?>" class="aup-switch-admin-btn aup-nav-link">
                        <i class="fas fa-user-switch me-2"></i><?= __('admin.switch_admin') ?>
                    </a>
                    <?php endif; ?>
                    <a href="<?= base_url('admincontrol/logout') ?>" class="aup-logout-btn aup-nav-link">
                        <i class="fas fa-sign-out-alt me-2"></i><?= __('admin.top_logout') ?>
                    </a>
                </div>

            </div>
            <!-- /PROFILE OFFCANVAS -->
            
            <!-- Content Area -->
            <div class="content-area">
            <?php
            $lg_warning = null;
            if (isset($GLOBALS['_license_guard_status']) && is_array($GLOBALS['_license_guard_status'])) {
                $lg_warning = $GLOBALS['_license_guard_status']['warning'];
            } elseif (function_exists('license_guard_get_cached_status')) {
                $lg_cached = license_guard_get_cached_status();
                if ($lg_cached && isset($lg_cached['status']) && $lg_cached['status'] === 'invalid') {
                    $lg_warning = 'License may not be valid for this domain. Please verify your license.';
                }
            }
            if ($lg_warning): ?>
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mx-3 mt-3 mb-0 border-0 shadow-sm" role="alert" style="border-left: 4px solid #ffc107 !important;">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <div>
                    <strong>License Notice:</strong> <?= htmlspecialchars($lg_warning) ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php
            $aff_lic = function_exists('aff_license_compliance_status') ? aff_license_compliance_status() : null;
            if ($aff_lic && ($aff_lic['should_warn'] || $aff_lic['needs_acknowledgement'])):
            ?>
            <div class="modal fade" id="affDisableMembershipConfirmModal" tabindex="-1" aria-labelledby="affDisableMembershipConfirmModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-danger-subtle border-0">
                            <h5 class="modal-title d-flex align-items-center" id="affDisableMembershipConfirmModalLabel">
                                <i class="fas fa-triangle-exclamation text-danger me-2"></i>
                                <?= __('admin.disable_membership_module') ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('admin.close') ?>"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0"><?= __('admin.disable_membership_module_confirm') ?></p>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal"><?= __('admin.cancel') ?></button>
                            <button type="button" class="btn btn-danger" id="affDisableMembershipConfirmAction">
                                <i class="fas fa-power-off me-1"></i><?= __('admin.disable_membership_module') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
            (function(){
                window.affRunDisableMembership = function(disableBtn){
                    if (!disableBtn) return;
                    var origHtml = disableBtn.innerHTML;
                    disableBtn.disabled = true;
                    disableBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>' + <?= json_encode(__('admin.processing')) ?>;
                    var done = function(){ window.location.reload(); };
                    var fail = function(){
                        disableBtn.disabled = false;
                        disableBtn.innerHTML = origHtml;
                        alert(<?= json_encode(__('admin.something_went_wrong_please_try_again')) ?>);
                    };
                    try {
                        if (typeof $ !== 'undefined') {
                            $.post('<?= base_url('membership/disableMembershipModule') ?>')
                                .done(done).fail(fail);
                        } else if (window.fetch) {
                            fetch('<?= base_url('membership/disableMembershipModule') ?>', { method: 'POST', credentials: 'same-origin' })
                                .then(function(r){ if(r.ok) done(); else fail(); })
                                .catch(fail);
                        } else { fail(); }
                    } catch(e) { fail(); }
                };

                window.affOpenDisableMembershipConfirm = function(disableBtn){
                    var modalEl = document.getElementById('affDisableMembershipConfirmModal');
                    var actionBtn = document.getElementById('affDisableMembershipConfirmAction');
                    if (!modalEl || !actionBtn || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                        if (confirm(<?= json_encode(__('admin.disable_membership_module_confirm')) ?>)) {
                            window.affRunDisableMembership(disableBtn);
                        }
                        return;
                    }
                    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    actionBtn.onclick = function(){
                        modal.hide();
                        window.affRunDisableMembership(disableBtn);
                    };
                    modal.show();
                };
            })();
            </script>
            <?php endif; ?>

            <?php if ($aff_lic && $aff_lic['should_warn'] && !$aff_lic['is_currently_dismissed']): ?>
            <div id="aff-license-banner-wrap" class="container-fluid pt-3">
                <div id="aff-regular-license-banner"
                     class="alert alert-danger alert-dismissible fade show d-flex align-items-start mb-3 border-0 shadow-sm"
                     role="alert"
                     style="border-left:4px solid #dc3545 !important;">
                    <i class="fas fa-shield-alt me-3 fs-4 text-danger"></i>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading fw-bold mb-1">
                            <?= __('admin.regular_license_membership_title') ?>
                        </h6>
                        <p class="mb-2 small">
                            <?= __('admin.regular_license_membership_body') ?>
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <a href="<?= htmlspecialchars($aff_lic['upgrade_url']) ?>" target="_blank" rel="noopener" class="btn btn-danger btn-sm">
                                <i class="fas fa-arrow-up me-1"></i><?= __('admin.upgrade_to_extended_license') ?>
                            </a>
                            <button type="button" id="aff-regular-license-disable" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-power-off me-1"></i><?= __('admin.disable_membership_module') ?>
                            </button>
                            <a href="https://codecanyon.net/licenses/standard" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i><?= __('admin.learn_more') ?>
                            </a>
                            <span class="text-muted small ms-1">
                                <i class="fas fa-info-circle me-1"></i><?= __('admin.banner_reappears_in_seven_days') ?>
                            </span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" id="aff-regular-license-dismiss" aria-label="<?= __('admin.close') ?>"></button>
                </div>
            </div>
            <script>
            (function(){
                var wrap = document.getElementById('aff-license-banner-wrap');
                var banner = document.getElementById('aff-regular-license-banner');
                var dashboardAlerts = document.querySelector('.server-errors');
                if (wrap && banner && dashboardAlerts) {
                    dashboardAlerts.insertBefore(banner, dashboardAlerts.firstChild);
                    wrap.remove();
                }

                var btn = document.getElementById('aff-regular-license-dismiss');
                if (btn) {
                    btn.addEventListener('click', function(){
                        try {
                            if (typeof $ !== 'undefined') {
                                $.post('<?= base_url('membership/dismissLicenseNotice') ?>');
                            } else if (window.fetch) {
                                fetch('<?= base_url('membership/dismissLicenseNotice') ?>', { method: 'POST', credentials: 'same-origin' });
                            }
                        } catch(e) {}
                    });
                }

                var disableBtn = document.getElementById('aff-regular-license-disable');
                if (disableBtn) {
                    disableBtn.addEventListener('click', function(){
                        if (typeof window.affOpenDisableMembershipConfirm === 'function') {
                            window.affOpenDisableMembershipConfirm(disableBtn);
                        }
                    });
                }
            })();
            </script>
            <?php endif; ?>

            <?php if ($aff_lic && $aff_lic['needs_acknowledgement']): ?>
            <div class="modal fade" id="affLicenseAckModal" tabindex="-1" aria-labelledby="affLicenseAckModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-warning-subtle border-0">
                            <h5 class="modal-title d-flex align-items-center" id="affLicenseAckModalLabel">
                                <i class="fas fa-shield-alt text-warning me-2"></i>
                                <?= __('admin.regular_license_membership_title') ?>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3"><?= __('admin.regular_license_membership_body') ?></p>
                            <div class="alert alert-light border d-flex align-items-start mb-3">
                                <i class="fas fa-info-circle text-info me-2 mt-1"></i>
                                <div class="small">
                                    <strong><?= __('admin.envato_policy_compliance') ?>.</strong>
                                    <?= __('admin.regular_license_membership_explainer') ?>
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="affLicenseAckCheck">
                                <label class="form-check-label" for="affLicenseAckCheck">
                                    <?= __('admin.regular_license_membership_ack_label') ?>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer border-0 d-flex flex-wrap gap-2">
                            <a href="<?= htmlspecialchars($aff_lic['upgrade_url']) ?>" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-arrow-up me-1"></i><?= __('admin.upgrade_to_extended_license') ?>
                            </a>
                            <button type="button" class="btn btn-outline-danger" id="affLicenseAckDisable">
                                <i class="fas fa-power-off me-1"></i><?= __('admin.disable_membership_module') ?>
                            </button>
                            <button type="button" class="btn btn-secondary ms-auto" id="affLicenseAckConfirm" disabled>
                                <?= __('admin.i_understand_continue') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
            (function(){
                function ready(fn){ if(document.readyState!='loading'){fn();} else {document.addEventListener('DOMContentLoaded',fn);} }
                ready(function(){
                    if (typeof bootstrap === 'undefined' || !bootstrap.Modal) return;
                    var el = document.getElementById('affLicenseAckModal');
                    if (!el) return;
                    var modal = new bootstrap.Modal(el);
                    modal.show();
                    var chk = document.getElementById('affLicenseAckCheck');
                    var btn = document.getElementById('affLicenseAckConfirm');
                    if (chk && btn) {
                        chk.addEventListener('change', function(){
                            btn.disabled = !chk.checked;
                            if (chk.checked) { btn.classList.remove('btn-secondary'); btn.classList.add('btn-warning'); }
                            else { btn.classList.add('btn-secondary'); btn.classList.remove('btn-warning'); }
                        });
                        btn.addEventListener('click', function(){
                            if (!chk.checked) return;
                            try {
                                if (typeof $ !== 'undefined') {
                                    $.post('<?= base_url('membership/acknowledgeLicenseNotice') ?>', function(){ modal.hide(); });
                                } else if (window.fetch) {
                                    fetch('<?= base_url('membership/acknowledgeLicenseNotice') ?>', { method: 'POST', credentials: 'same-origin' })
                                        .finally(function(){ modal.hide(); });
                                } else { modal.hide(); }
                            } catch(e) { modal.hide(); }
                        });
                    }

                    var disableBtn = document.getElementById('affLicenseAckDisable');
                    if (disableBtn) {
                        disableBtn.addEventListener('click', function(){
                            if (typeof window.affOpenDisableMembershipConfirm === 'function') {
                                window.affOpenDisableMembershipConfirm(disableBtn);
                            }
                        });
                    }
                });
            })();
            </script>
            <?php endif; ?>
