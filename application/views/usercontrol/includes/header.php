<?php 
  $db =& get_instance();
  $userdetails = $db->Product_model->userdetails('user', true); 
  $SiteSetting = $db->Product_model->getSiteSetting();
  $MembershipSetting =$db->Product_model->getSettings('membership');
  $user_side_font = $db->Product_model->getSettings('site','user_side_font');
  $user_button_color = $db->Product_model->getSettings('theme','user_button_color'); 
  $user_button_hover_color = $db->Product_model->getSettings('theme','user_button_hover_color');
  $loginUser = $_SESSION['user'];

  // Initialize notification flags
  $vendor_data['show_vendor_status_notification'] = false;
  $vendor_data['show_commission_notification'] = false;
  $vendor_data['show_deposit_warning'] = false;
  $vendor_data['show_share_sales_notification'] = false;
  $vendor_data['deposit_warning_message'] = '';

  if (isset($loginUser['is_vendor']) && $loginUser['is_vendor']) {
      $store_setting = $db->Product_model->getSettings('store');
      $vendor_setting = $db->db->query("SELECT * FROM vendor_setting WHERE user_id = ?", [(int)$userdetails['id']])->row_array();

      // Determine if vendor status notification should be shown
      $vendor_data['show_vendor_status_notification'] = $store_setting['status'] == 1 && empty($vendor_setting['vendor_status']);

      // Proceed with commission settings check only if vendor status notification is true
      if ($vendor_data['show_vendor_status_notification']) {
          $requiredFields = ['affiliate_sale_commission_type' => '0', 'affiliate_commission_value' => 0, 
                             'affiliate_click_count' => 0, 'affiliate_click_amount' => 0];
          $missingOrInvalidCommissionSettings = array_reduce(array_keys($requiredFields), function ($carry, $field) use ($vendor_setting, $requiredFields) {
              return $carry || (empty($vendor_setting[$field]) || $vendor_setting[$field] === $requiredFields[$field]);
          }, false);

          $vendor_data['show_commission_notification'] = $missingOrInvalidCommissionSettings;
      }

      if (isset($vendor_setting['vendor_shares_sales_status']) && $vendor_setting['vendor_shares_sales_status'] == 0) {
          $vendor_data['show_share_sales_notification'] = true;
      }

      $vendoerMinDeposit = $db->Product_model->getSettings('site', 'vendor_min_deposit');
      $vendor_data['min_deposit'] = isset($vendoerMinDeposit['vendor_min_deposit']) ? $vendoerMinDeposit['vendor_min_deposit'] : 0;
      
      $db->load->model('Total_model');
      $depbalence = $db->Total_model->getUserBalance($userdetails['id']);
      
      $vendorDepositStatus = $db->Product_model->getSettings('vendor', 'depositstatus');
      $deposit_status_enabled = isset($vendorDepositStatus['depositstatus']) ? $vendorDepositStatus['depositstatus'] : 0;
      
      if ($deposit_status_enabled == 1 && $depbalence < $vendor_data['min_deposit']) {
          $vendor_data['show_deposit_warning'] = true;
          $vendor_data['deposit_warning_message'] = __('user.minimum_deposit_warning') . ' ' . c_format($vendor_data['min_deposit']) . ' ' . __('user.to_continue_vendor_activities');
      }
  }

  if($userdetails['reg_approved'] != 1 && !isset($notcheckapproval)){
    redirect('usercontrol/approval_status');die;
  }

  if($MembershipSetting['status']){
    $user = App\User::auth();
    if((int)$user->plan_id == 0){
      if(!isset($notcheckmember)){
        redirect('usercontrol/purchase_plan');die;
      }
    } else if($user->plan_id == -1){
    } else if($user){
      $plan = $user->plan();
      if(!$plan){
        if(!isset($notcheckmember)){
          redirect('usercontrol/purchase_plan');die;
        }
      } else if(!isset($notcheckmember) && $plan->status_id != 1){
          redirect('usercontrol/purchase_plan_expire');
      }else if($plan->isExpire() || !$plan->strToTimeRemains() > 0){
        $lifetime = ($plan->is_lifetime && $plan->status_id) ? true : false;
        if(!isset($notcheckmember) && !$lifetime){
          redirect('usercontrol/purchase_plan_expire');
          die;
        }
      }
    }
  }


  /*==============Side Bar File Code=====================*/
  $store_setting =$db->Product_model->getSettings('store');
  $refer_status =$db->Product_model->my_refer_status($userdetails['id']);
  $db->Product_model->ping($userdetails['id']);
  $vendor_setting = $db->Product_model->getSettings('vendor');
  $market_vendor = $db->Product_model->getSettings('market_vendor');
  $membership = $db->Product_model->getSettings('membership');
  $user_side_bar_color = $db->Product_model->getSettings('theme','user_side_bar_color');
  $user_side_bar_text_color = $db->Product_model->getSettings('theme','user_side_bar_text_color');
  $marketvendorpanelmode = $market_vendor['marketvendorpanelmode'] ?? 0;
  $userdashboard_settings = getUserDashboardSettings();

  if (isset($this->session) && $this->session->userdata('userLang') !== FALSE)
  $language_id=$this->session->userdata('userLang');
  $tutorials=$db->Tutorial_model->getAllRecords($language_id);
  /*==============Nav Bar File Code=====================*/
    $this->CI        =& get_instance(); 
    $method=$this->CI->router->fetch_method();
    $products        = $db->Product_model; 
    $Withdrawal      = $db->Withdrawal_payment_model; 
    $notifications       = $products->getnotificationnew('user',$userdetails['id'],10);
    $notifications_count = $products->getnotificationnew_count('user',$userdetails['id']);
    $paymentlist         = $products->getPaymentWarning();
    $LanguageHtml        = $products->getLanguageHtmlUser('usercontrol');
    $CurrencyHtml        = $products->getCurrencyHtmlUser('usercontrol');

    $PrimaryPaymentMethodStatus = $products->getUserPaymentMethodStatus($userdetails['id'],$userdetails['primary_payment_method']);
    if(empty($payment_methods) && ($method != 'purchase_plan' && $method !='user_reports' && $method !='buy_membership')){
      
      $payment_methods = $Withdrawal->getPaymentMethods(); 
    }

    $this->session->set_userdata('payment_methods',$payment_methods);
    
    $loginUser = $_SESSION['user'];
    if(isset($loginUser['is_vendor']) && $loginUser['is_vendor'] == 1) {
        $marketVendorStatus= $db->Product_model->getSettings('market_vendor', 'marketvendorstatus');
        $vendoerMinDeposit = $db->Product_model->getSettings('site', 'vendor_min_deposit');
        $userdepbal['vendor_min_deposit'] = isset($vendoerMinDeposit['vendor_min_deposit']) ? $vendoerMinDeposit['vendor_min_deposit'] : 0;

        $db->load->model('Total_model');
        $depbalence = $db->Total_model->getUserBalance($loginUser['id']);

        // Deposit warning variables removed - now handled in analytics dashboard

        $vendorDepositStatus = $this->Product_model->getSettings('vendor', 'depositstatus');
        $userdepbal['vendor_deposit_status'] = isset($vendorDepositStatus['depositstatus']) ? $vendorDepositStatus['depositstatus'] : 0;
     }

    $membership = $this->Product_model->getSettings('membership', 'status');
    $award_level = $this->Product_model->getSettings('award_level', 'status');
    $userPlan = App\MembershipUser::select('membership_plans.commission_sale_status','award_level.level_number','award_level.sale_comission_rate')->join('membership_plans','membership_plans.id','=','membership_user.plan_id')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('is_active',1)->where('user_id',$userdetails['id'])->first();
    $levels = $this->Product_model->getAll('award_level',false,0,'id desc');

    require APPPATH."config/breadcrumb.php";
      $pageKey = $db->Product_model->page_id();

      $user_side_bar_text_hover_color = $products->getSettings('theme','user_side_bar_text_hover_color');
      $user_top_bar_color = $products->getSettings('theme','user_top_bar_color');
      $user_side_bar_clock_text_color = $products->getSettings('theme','user_side_bar_clock_text_color');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="<?= isset($userdetails['theme_preference']) ? htmlspecialchars($userdetails['theme_preference']) : 'light' ?>">
  <script>
    (function(){var t=localStorage.getItem('v14_theme');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-bs-theme',t);})();
  </script>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?= $SiteSetting['name'] ?> - <?= $loginUser['is_vendor']== 1 ? __('user.top_title_vendor') : __('user.top_title_affiliate') ?>
    </title>

    <meta content="<?= $SiteSetting['meta_author'] ?>" name="author" />
    <meta content="<?= $SiteSetting['meta_description'] ?>" name="description" />
    <meta content="<?= $SiteSetting['meta_keywords'] ?>" name="keywords" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!--summNote jquery files-->
    <script src="<?= base_url('assets/template/summernote/jquery-3.4.1.slim.min.js'); ?>"></script>
    <!--summNote jquery files-->

    <!-- Bootstrap 5 CSS (LOCAL) -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>?v=<?= av() ?>">

    <!-- Font Awesome (LOCAL) -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/all.min.css') ?>?v=<?= av() ?>">

    <!--Plugins Css -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/jquery-confirm.min.css') ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/template/css/sweetalert2.min.css') ?>?v=<?= av() ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datetimepicker/jquery.datetimepicker.min.css') ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/select2.css') ?>"/>
    
    <!-- jQuery UI CSS (LOCAL) -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/jquery-ui.min.css') ?>?v=<?= av() ?>"/>

    <!--summNote css files-->
    <link rel="stylesheet" href="<?= base_url('assets/template/summernote/summernote-lite.min.css') ?>">
    <!--summNote css files-->

    <!-- User Mobile Navigation CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/template/css/user-mobile-navigation.css') ?>?v=<?= av() ?>">

    <!-- V14 Theme & Animations -->
    <link href="<?= base_url('assets/template/css/theme-variables.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/dark-mode-overrides.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/dashboard-animations.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/membership-pages.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/admin-integration.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/topnav-modern.css') ?>?v=<?= av() ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/template/css/user-components.css') ?>?v=<?= av() ?>" rel="stylesheet"/>

    <!-- jQuery (LOCAL) -->
    <script src="<?= base_url('assets/template/js/jquery-3.7.1.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/template/js/jquery-ui.min.js') ?>?v=<?= av() ?>"></script>
    
    <!-- Bootstrap 5 JS Bundle (LOCAL - includes Popper) -->
    <script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>?v=<?= av() ?>"></script>

    <script type="text/javascript">
      window.affiliatePro = {
        base_url:"<?= base_url() ?>"
      }
    </script>
    
    <!-- dynamic PHP -->
    <?= render_favicon_tag($SiteSetting['favicon']) ?>
    <?= render_user_panel_logo_css($SiteSetting) ?>
    <?= render_user_panel_styles($user_side_font, $user_button_color, $user_button_hover_color) ?>
    <?= render_user_facebook_messenger_script($SiteSetting) ?>
    <?= show_messenger_button($SiteSetting, 'affiliate') ?>
    <?= render_js_error_reporter() ?>
  </head>

<body class="body-common-class user_side_bar_color" 
      style="font-family: <?= $user_side_font['user_side_font'] ?> !important;">

  <script src="<?= base_url('assets/plugins/datetimepicker/jquery.datetimepicker.full.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatable/select2.min.js') ?>"></script>  
  <script src="<?= base_url('assets/template/js/sweetalert2.all.min.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/plugins/qrcode.min.js') ?>"></script>

    <script type="text/javascript">
        (function ($) {
            $.fn.btn = function (action) {
                var self = $(this);
                var tagName = self.prop("tagName");
                if(tagName == 'A'){
                    if (action == 'loading') {
                        $(self).attr('data-text',$(self).text());
                        $(self).text("Loading..");
                    }
                    if (action == 'reset') { $(self).text($(self).attr('data-text')); }
                }
                else {
                    if (action == 'loading') { $(self).addClass("btn-loading"); }
                    if (action == 'reset') { $(self).removeClass("btn-loading"); }
                }
            }
        })(jQuery);

        var formDataFilter = function(formData) {
            if (!(window.FormData && formData instanceof window.FormData)) return formData
            if (!formData.keys) return formData
            var newFormData = new window.FormData()
            Array.from(formData.entries()).forEach(function(entry) {
                var value = entry[1]
                if (value instanceof window.File && value.name === '' && value.size === 0) {
                    newFormData.append(entry[0], new window.Blob([]), '')
                } else {
                    newFormData.append(entry[0], value)
                }
            })
            return newFormData
        }
        
    </script>

    <!-- SIDEBAR DISABLED - Using Horizontal Navigation Instead -->
    <aside class="sidebar d-none">
          
          <!-- Sidebar Menu Start -->
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="<?= base_url('usercontrol/dashboard'); ?>">
                <i class="fa-sharp fa-solid fa-list-ul icon pt-1"></i>
                <span class="item-name"><?= __('user.page_title_dashboard') ?></span>
              </a>
            </li>
            <li class="nav-item">
                              <a class="nav-link" href="<?= base_url('usercontrol/analytics_dashboard'); ?>">
                <i class="fa-solid fa-tachometer-alt icon pt-1"></i>
                                  <span class="item-name"><?= __('user.page_title_analytics_dashboard') ?></span>
              </a>
            </li>
            <li>
              <hr class="hr-horizontal">
            </li>
          <!-------User tutorial------->
          <?php 
          if(isset($SiteSetting["tutorial_module_status"]) && $SiteSetting["tutorial_module_status"]==1) 
          {
            if(isset($tutorials) && is_array($tutorials) && count($tutorials)>0)
            {
              ?>
              <!--Main Category-->
              <li class="nav-item  submenu-sidebar">
                  <a class="nav-link collapsed text-truncate" href="#submenu5" data-bs-toggle="collapse"  data-bs-target="#submenu5"><i class="fa fa-table"></i> <span class="d-sm-inline" ><?= __('user.page_title_tutorial') ?></span><i class="fas fa-chevron-right right-icon"></i></a>
               
                      <ul class="flex-column pl-2 sub-nav collapse" id="submenu5" aria-expanded="false">
                          <?php
                            $tutorialCategoryId=0;
                              $pageString="";
                              $previousCateroyName="";

                              foreach($tutorials as $tutorial )
                              { 
                                if($tutorialCategoryId!=$tutorial['category_id'])
                                {
                                  if($tutorialCategoryId!="" && $tutorialCategoryId!=0)
                                  {
                                    //first category pages
                                    $pageString='<div class="collapse" id="submenu1'.$tutorialCategoryId.'" aria-expanded="false">
                                          <ul class="flex-column sub-nav pl-4">'.$pageString.'</ul>
                                        </div></li>';
                                      echo $pageString;
                                      $pageString="";
                                  }
                                ?>
                            
                          <!--sub-category-->
                          <li class="nav-item">
                              <a class="nav-link  text-truncate collapsed py-1" href="#submenu1<?=$tutorial['category_id']?>" data-bs-toggle="collapse" data-bs-target="#submenu1<?=$tutorial['category_id']?>"> <i class="fa-solid fa-circle icon"></i>
                                <i class="sidenav-mini-icon"> B </i>
                                <span class="item-name"><?=$tutorial['name'] ?></span><i class="fas fa-chevron-right right-icon"></i></a>
                              
                                <?php
                                    }
                                      $pageString.='<li class="nav-item">
                                                <a class="nav-link p-1 text-truncate" href="'. base_url('usercontrol/tutorial/'.$tutorial['id']).'">
                                                  <i class="fa-solid fa-square"></i> '.$tutorial['title'].' </a>
                                                </li>';

                                      $tutorialCategoryId=$tutorial['category_id'];  
                                }
                                //other category pages
                                if($pageString!="")
                                echo '<div class="collapse" id="submenu1'.$tutorialCategoryId.'" aria-expanded="false">
                                    <ul class="sub-nav">'.$pageString.'</ul>
                                    </div></li>';
                                //other category pages
                                ?>
                                <!--sub-category-->  
                      </ul>    
              </li>
              <?php }
          }
        ?>
          <!-------User tutorial------->


          <!-------Vendor Overview (vendor only) ------->
          <?php if(isset($userdetails['is_vendor']) && $userdetails['is_vendor']){ ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('usercontrol/my_vendor_panel');?>">
                <i class="fas fa-tachometer-alt icon"></i>
                <span class="item-name"><?= __('user.vendor_panel') ?? 'Vendor Panel' ?></span>
              </a>
            </li>
          <?php } ?>

          <!-------Vendor Store (vendor only) ------->
          <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1){ ?>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#vendor-store" role="button" aria-expanded="false" aria-controls="vendor-store">
                <i class="fas fa-store icon"></i>
                <span class="item-name"><?= __('user.nav_my_store') ?></span>
                <i class="fas fa-chevron-right right-icon"></i>
              </a>
              <ul class="sub-nav collapse" id="vendor-store" data-bs-parent="#sidebar-menu">
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/store_dashboard');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_store_dashboard') ?></span>
                  </a>
                </li>
                <?php if($store_setting['store_mode'] == 'cart') {?>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/store_products');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_common_title_store_products') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/store_coupon');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_store_coupons') ?></span>
                  </a>
                </li>
                <?php } ?>
                <?php if($store_setting['store_mode'] == 'sales') {?>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/sales_products');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_common_title_store_products') ?></span>
                  </a>
                </li>
                <?php } ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/store_venodr_orders');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.vendor_orders_small') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/listclients');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.store_clients') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/vendor_store_analytics');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.vendor_analytics') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/vendor_payouts');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.payout_history') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/store_setting');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_store_setting') ?></span>
                  </a>
                </li>
              </ul>
            </li>
          <?php } ?>

          <!-------Marketing (vendor only) ------->
          <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && ((int)$market_vendor['marketvendorstatus'] == 1 )) { ?>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#vendor-markrt" role="button" aria-expanded="false" aria-controls="vendor-markrt">
                <i class="fas fa-bullhorn icon"></i>
                <span class="item-name"><?= __('user.page_title_marketing') ?></span>
                <i class="fas fa-chevron-right right-icon"></i>
              </a>
              <ul class="sub-nav collapse" id="vendor-markrt" data-bs-parent="#sidebar-menu">
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/programs');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.ven_programs') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/integration_tools');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_campaigns') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/integration');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_plugins') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/wallet_setting');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_vendor_setting') ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('usercontrol/external_vendor_orders');?>">
                    <i class="fa-solid fa-circle icon"></i>
                    <span class="item-name"><?= __('user.page_title_my_orders') ?></span>
                  </a>
                </li>
              </ul>
            </li>
          <?php } ?>

          <!-------Affiliate Links (non-vendor or dual-mode) ------->
          <?php if($userdetails['is_vendor']==0 || ($userdetails['is_vendor']==1  && $market_vendor['marketvendorpanelmode'] ==0 )) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('usercontrol/store_markettools'); ?>">
                <i class="fas fa-link icon"></i>
                <span class="item-name"><?= __('user.nav_affiliate_links') ?></span>
              </a>
            </li>
          <?php } ?>

          <!------- Earnings ------->
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-earnings" role="button" aria-expanded="false" aria-controls="sidebar-earnings">
              <i class="fas fa-wallet icon"></i>
              <span class="item-name"><?= __('user.nav_earnings') ?></span>
              <i class="fas fa-chevron-right right-icon"></i>
            </a>
            <ul class="sub-nav collapse" id="sidebar-earnings" data-bs-parent="#sidebar-menu">
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/mywallet');?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.page_title_my_wallet') ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/all_transaction');?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.page_title_all_trans_user') ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/wallet_requests_list'); ?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.usercontrol_wallet_requests_list') ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('ReportController/user_reports');?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.page_title_user_reports') ?></span>
                </a>
              </li>
              <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) == 1){ ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/my_deposits');?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.page_title_my_deposits') ?></span>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>

          <!------- My Network ------->
          <?php if($refer_status) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('usercontrol/my_network');?>">
                <i class="fas fa-project-diagram icon"></i>
                <span class="item-name"><?= __('user.page_title_my_network') ?></span>
              </a>
            </li>
          <?php } ?>

          <!------- MLM (vendor only) ------->
          <?php if(isset($market_vendor) && $market_vendor['vendormlmmodule'] == 1) {
           if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && ((int)$market_vendor['marketvendorstatus'] == 1 ) || (isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('usercontrol/mlm_levels');?>">
                <i class="fas fa-sitemap icon"></i>
                <span class="item-name"><?= __('user.page_title_mlm_setting') ?></span>
              </a>
            </li>
          <?php } } ?>

          <!------- Account ------->
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-account" role="button" aria-expanded="false" aria-controls="sidebar-account">
              <i class="fas fa-user-circle icon"></i>
              <span class="item-name"><?= __('user.useful_links') ?></span>
              <i class="fas fa-chevron-right right-icon"></i>
            </a>
            <ul class="sub-nav collapse" id="sidebar-account" data-bs-parent="#sidebar-menu">
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/editProfile'); ?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.profile_details') ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/payment_details');?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.payment_details') ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/changePassword');?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.page_title_changePassword') ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url($loginUser['is_vendor'] == 1 ? 'usercontrol/settings_hub' : 'usercontrol/vendor_settings') ?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.settings') ?></span>
                </a>
              </li>
              <?php if(isShowUserControlParts($userdashboard_settings['tickets_page'])){ ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/tickets');?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.page_title_tickets') ?></span>
                </a>
              </li>
              <?php } ?>
              <?php if(($membership['status'] == 1) || (($membership['status'] == 2) && ($userdetails['is_vendor'] == 1)) || (($membership['status'] == 3) && ($userdetails['is_vendor'] == 0))){ ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('usercontrol/purchase_plan');?>">
                  <i class="fa-solid fa-circle icon"></i>
                  <span class="item-name"><?= __('user.page_title_membership') ?></span>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>

          <!--User contact us page-->
          <?php if(isShowUserControlParts($userdashboard_settings['contact_us_page'])){ ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('usercontrol/contact-us'); ?>">
              <i class="fas fa-envelope icon"></i>
              <span class="item-name"><?= __('user.page_title_contact_admin') ?></span>
            </a>
          </li>
          <?php } ?>
          <!--User contact us page-->
          </ul>
          
        </div>

        <?php 
        $googleAdsTop = $this->Setting_model->getGoogleAds(1,1);
        $googleAdsbottom = $this->Setting_model->getGoogleAds(2,1);
        if(!empty($googleAdsTop) || !empty($googleAdsbottom)): ?>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"
            crossorigin="anonymous"
            data-ad-client="ca-pub-2012969363648405"></script>
        <?php endif; ?>
        <?php if(!empty($googleAdsTop)){
        ?>

        <div class="sidebar-footer pt-2">
          <div class="googleadd-bg text-center py-5">
            <?= __('user.google_ad_placeholder') ?>
            <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="<?= @$googleAdsTop[0]['client_key']?>"
             data-ad-slot="<?= @$googleAdsTop[0]['unit_key']?>"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
            <script>
                 (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
          </div>
        </div>

      <?php } if(!empty($googleAdsbottom)){?>
        <div class="googleadd-bg text-center py-5 mt-3"> 
          <?= __('user.google_ad_placeholder') ?>
          <ins class="adsbygoogle"
           style="display:block"
           data-ad-client="<?= @$googleAdsbottom[0]['client_key']?>"
           data-ad-slot="<?= @$googleAdsbottom[0]['unit_key']?>"
           data-ad-format="auto"
           data-full-width-responsive="true"></ins>
          <script>
               (adsbygoogle = window.adsbygoogle || []).push({});
          </script> 
        </div>
      <?php }?>

      </div>
      </div>
    </aside>
 
    <div class="user-wrapper d-flex flex-column">
        <div class="main-content flex-grow-1 d-flex flex-column">
            <nav class="navbar navbar-expand-lg shadow-sm bg-primary">
                <div class="container-fluid px-3">
                    <a href="<?= base_url('usercontrol/dashboard') ?>" class="navbar-brand d-flex align-items-center text-white">
            <?php
            if (!empty($SiteSetting['admin-side-logo']) && isset($SiteSetting['custom_logo_size']) && $SiteSetting['custom_logo_size'] == 1) {
                $logo = (strpos($SiteSetting['admin-side-logo'], 'http') === 0) ?
                        $SiteSetting['admin-side-logo'] : 
                        base_url('assets/images/site/' . $SiteSetting['admin-side-logo']);
                $logoClass = ((int)$SiteSetting['log_custom_width'] > 0 && (int)$SiteSetting['log_custom_height'] > 0) ? 'customLogoClass' : 'topbar-logo';
            ?>
                        <img src="<?= htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" alt="logo" class="me-3 <?= $logoClass ?>">
            <?php } ?>
                        <span class="fw-bold d-none d-md-inline"><?= $loginUser['is_vendor'] == 1 ? __('user.vendor_dashboard') : __('user.affiliate_dashboard') ?></span>
                    </a>

                    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavMenu">
                        <i class="fas fa-bars fs-5"></i>
                    </button>

                    <div class="d-none d-lg-flex align-items-center gap-2">
                        <!-- Dark Mode Toggle -->
                        <button class="theme-toggle-btn" title="<?= __('user.toggle_dark_mode') ?>">
                            <i class="fas fa-moon"></i>
                            <i class="fas fa-sun"></i>
                        </button>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                                <i class="fas fa-dollar-sign"></i>
                                <span class="small"><?= isset($_SESSION['userCurrency']) ? $_SESSION['userCurrency'] : 'USD' ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><h6 class="dropdown-header"><i class="fas fa-coins me-2"></i><?= __('user.select_currency') ?></h6></li>
                                <?php
                                $currencies = $this->db->query("SELECT * FROM currency WHERE status=1")->result_array();
                                $currency_icons = ['fa-dollar-sign text-success', 'fa-euro-sign text-primary', 'fa-pound-sign text-warning', 'fa-yen-sign text-danger', 'fa-bitcoin text-info'];
                                foreach($currencies as $key => $curr): 
                                    $icon_class = $currency_icons[$key % count($currency_icons)];
                                ?>
                                <li><a class="dropdown-item" href="<?= base_url('usercontrol/change_currency/'.$curr['code']) ?>">
                                    <i class="fas <?= $icon_class ?> me-2"></i><?= $curr['code'] ?>
                                </a></li>
                                <?php endforeach; ?>
                            </ul>
            </div>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                                <i class="fas fa-language"></i>
                                <span class="small"><?= isset($_SESSION['userLangName']) ? $_SESSION['userLangName'] : __('user.language') ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><h6 class="dropdown-header"><i class="fas fa-globe me-2"></i><?= __('user.select_language') ?></h6></li>
                                <?php 
                                $languages = $this->db->query("SELECT * FROM language WHERE status=1")->result_array();
                                foreach($languages as $lang_item): ?>
                                <li><a class="dropdown-item d-flex align-items-center gap-2" href="<?= base_url('usercontrol/change_language/'.$lang_item['id']) ?>">
                                    <img src="<?= base_url($lang_item['flag']) ?>" alt="<?= $lang_item['name'] ?>" width="20" class="rounded-1 flex-shrink-0">
                                    <?= $lang_item['name'] ?>
                                </a></li>
                                <?php endforeach; ?>
                            </ul>
            </div>

                        <div class="d-none">
                            <?= $CurrencyHtml ?>
                            <?= $LanguageHtml ?>
                        </div>

                        <!-- Notifications → triggers offcanvas -->
                        <button class="btn btn-outline-light rounded-circle p-2 position-relative d-flex align-items-center justify-content-center"
                                data-bs-toggle="offcanvas" data-bs-target="#userNotifPanel"
                                style="width: 45px; height: 45px;">
                            <i class="fas fa-bell text-white"></i>
                            <?php if($notifications_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger ajax-notifications_count">
                                    <?= $notifications_count > 99 ? '99+' : $notifications_count; ?>
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            <?php endif; ?></button>

                        <!-- User Profile → triggers offcanvas -->
                        <button class="btn btn-sm btn-light d-flex align-items-center gap-2 rounded-pill px-3"
                                data-bs-toggle="offcanvas" data-bs-target="#userProfilePanel">
                            <img src="<?= $products->getAvatar($userdetails['avatar']) ?>"
                                 class="rounded-circle border border-2 border-primary"
                                 width="28" height="28" style="object-fit:cover">
                            <div class="text-start d-none d-lg-block">
                                <div class="fw-bold small"><?= htmlspecialchars($userdetails['firstname']) ?></div>
                                <small class="text-muted" style="font-size:0.7rem"><?= $loginUser['is_vendor'] == 1 ? __('user.vendor') : __('user.affiliate') ?></small>
                            </div>
                            <i class="fas fa-chevron-down small text-muted"></i>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- ======================================================
                 USER NOTIFICATIONS OFFCANVAS PANEL
                 ====================================================== -->
            <?php
            $u_last_id_notifications = 0;
            $u_notif_types = [
                ['icon' => 'fas fa-user-plus',   'color' => '#28c76f', 'bg' => 'rgba(40,199,111,0.12)'],
                ['icon' => 'fas fa-shield-alt',  'color' => '#ff9f43', 'bg' => 'rgba(255,159,67,0.12)'],
                ['icon' => 'fas fa-dollar-sign', 'color' => '#00cfe8', 'bg' => 'rgba(0,207,232,0.12)'],
                ['icon' => 'fas fa-cog',         'color' => '#7367f0', 'bg' => 'rgba(115,103,240,0.12)'],
                ['icon' => 'fas fa-sync-alt',    'color' => '#ea5455', 'bg' => 'rgba(234,84,85,0.12)'],
            ];
            ?>
            <div class="offcanvas offcanvas-end anp-offcanvas" tabindex="-1" id="userNotifPanel">

                <!-- Cover Header -->
                <div class="anp-cover" style="background: linear-gradient(145deg, #43e97b 0%, #38f9d7 40%, #4facfe 100%);">
                    <button type="button" class="aup-close-btn" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button>
                    <div class="anp-cover-icon"><i class="fas fa-bell"></i></div>
                    <div class="anp-cover-title"><?= __('user.notification') ?></div>
                    <?php if($notifications_count > 0): ?>
                        <div class="anp-cover-badge">
                            <?php if($notifications_count > 10): ?>
                                <?= __('user.showing') ?> 10 <?= __('user.of') ?> <?= $notifications_count ?>
                            <?php else: ?>
                                <?= $notifications_count ?> <?= __('user.new_items') ?>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="anp-cover-badge anp-badge-clear"><?= __('user.all_caught_up') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Notification list -->
                <div class="anp-list">
                    <?php if(empty($notifications)): ?>
                        <div class="anp-empty">
                            <i class="fas fa-check-double anp-empty-icon"></i>
                            <div class="anp-empty-title"><?= __('user.all_caught_up') ?></div>
                            <div class="anp-empty-sub"><?= __('user.no_notifications') ?></div>
                        </div>
                    <?php else:
                        foreach ($notifications as $key => $notification):
                            if ($u_last_id_notifications <= $notification['notification_id']) {
                                $u_last_id_notifications = $notification['notification_id'];
                            }
                            $t = $u_notif_types[$key % count($u_notif_types)];
                            $notification_full_url = base_url('usercontrol') . $notification['notification_url'];
                    ?>
                        <a class="anp-item" href="javascript:void(0)"
                           onclick="shownofication(<?= $notification['notification_id'] ?>, '<?= addslashes($notification_full_url) ?>', 'user')">
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
                    <input type="hidden" id="last_id_notifications" value="<?= $u_last_id_notifications ?>">
                </div>

                <!-- Footer — always visible -->
                <div class="anp-footer">
                    <a href="<?= base_url('usercontrol/notification') ?>" class="anp-footer-btn">
                        <i class="fas fa-list me-2"></i><?= __('user.view_all') ?>
                    </a>
                </div>

            </div>
            <!-- /USER NOTIFICATIONS OFFCANVAS -->

            <!-- ======================================================
                 USER PROFILE OFFCANVAS PANEL
                 ====================================================== -->
            <?php
            $u_role        = $loginUser['is_vendor'] == 1 ? __('user.vendor') : __('user.affiliate');
            $u_role_color  = $loginUser['is_vendor'] == 1 ? '#ff9f43' : '#28c76f';
            $u_avatar_url  = $products->getAvatar($userdetails['avatar']);
            ?>
            <div class="offcanvas offcanvas-end aup-offcanvas" tabindex="-1" id="userProfilePanel">

                <!-- Cover -->
                <div class="aup-cover" style="background: linear-gradient(145deg,#43e97b 0%,#38f9d7 45%,#4facfe 100%);">
                    <button type="button" class="aup-close-btn" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button>
                    <div class="aup-avatar-ring">
                        <img src="<?= $u_avatar_url ?>" class="aup-avatar-img" alt="avatar">
                    </div>
                    <div class="aup-user-name"><?= htmlspecialchars($userdetails['firstname'] . ' ' . $userdetails['lastname']) ?></div>
                    <div class="aup-user-email"><?= htmlspecialchars($userdetails['email']) ?></div>
                    <span class="aup-status-pill" style="background:rgba(255,255,255,0.2);border-color:rgba(255,255,255,0.4)">
                        <span class="aup-status-dot" style="background:#fff"></span>
                        <?= $u_role ?>
                    </span>
                </div>

                <!-- Stats Strip -->
                <div class="aup-stats-strip">
                    <div class="aup-stat">
                        <div class="aup-stat-val"><?= $notifications_count > 0 ? $notifications_count : '0' ?></div>
                        <div class="aup-stat-label"><?= __('user.notification') ?></div>
                    </div>
                    <div class="aup-stat">
                        <div class="aup-stat-val"><?= isset($userdetails['balance']) ? '$'.number_format($userdetails['balance'],2) : '$0.00' ?></div>
                        <div class="aup-stat-label"><?= __('user.page_title_my_wallet') ?></div>
                    </div>
                    <div class="aup-stat">
                        <div class="aup-stat-val" style="color:#28c76f">●</div>
                        <div class="aup-stat-label"><?= __('user.active') ?></div>
                    </div>
                </div>

                <!-- Menu -->
                <div class="aup-menu">
                    <div class="aup-section-label"><?= __('user.account_settings') ?></div>

                    <a href="<?= base_url('usercontrol/editProfile') ?>" class="aup-item u-aup-nav-link">
                        <div class="aup-item-icon" style="background:rgba(79,172,254,0.12);color:#4facfe">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="aup-item-body">
                            <div class="aup-item-label"><?= __('user.profile') ?></div>
                            <div class="aup-item-sub"><?= __('admin.manage_your_account') ?></div>
                        </div>
                        <i class="fas fa-chevron-right aup-item-arrow"></i>
                    </a>

                    <a href="<?= base_url('usercontrol/changePassword') ?>" class="aup-item u-aup-nav-link">
                        <div class="aup-item-icon" style="background:rgba(255,159,67,0.12);color:#ff9f43">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="aup-item-body">
                            <div class="aup-item-label"><?= __('user.page_title_changePassword') ?></div>
                            <div class="aup-item-sub"><?= __('admin.update_your_password') ?></div>
                        </div>
                        <i class="fas fa-chevron-right aup-item-arrow"></i>
                    </a>

                    <a href="<?= base_url('usercontrol/mywallet') ?>" class="aup-item u-aup-nav-link">
                        <div class="aup-item-icon" style="background:rgba(40,199,111,0.12);color:#28c76f">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="aup-item-body">
                            <div class="aup-item-label"><?= __('user.page_title_my_wallet') ?></div>
                            <div class="aup-item-sub"><?= __('admin.balance_transactions') ?></div>
                        </div>
                        <i class="fas fa-chevron-right aup-item-arrow"></i>
                    </a>

                    <a href="<?= base_url($loginUser['is_vendor'] == 1 ? 'usercontrol/settings_hub' : 'usercontrol/vendor_settings') ?>" class="aup-item u-aup-nav-link">
                        <div class="aup-item-icon" style="background:rgba(0,207,232,0.12);color:#00cfe8">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="aup-item-body">
                            <div class="aup-item-label"><?= __('user.settings') ?></div>
                            <div class="aup-item-sub"><?= $loginUser['is_vendor'] == 1 ? __('user.settings_hub_desc_short') : __('user.s3_settings_desc') ?></div>
                        </div>
                        <i class="fas fa-chevron-right aup-item-arrow"></i>
                    </a>
                </div>

                <!-- Logout -->
                <div class="aup-footer">
                    <a href="<?= base_url('usercontrol/logout') ?>" class="aup-logout-btn u-aup-nav-link">
                        <i class="fas fa-sign-out-alt me-2"></i><?= __('user.logout') ?>
                    </a>
                </div>

            </div>
            <!-- /USER PROFILE OFFCANVAS -->

            <!-- JS: close offcanvas then navigate for user profile links -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.u-aup-nav-link').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        var href = this.getAttribute('href');
                        if (!href || href === '#') return;
                        e.preventDefault();
                        var ocEl = document.getElementById('userProfilePanel');
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

            <!-- Mobile Navigation Menu (Collapsible) -->
            <div class="collapse d-lg-none" id="mobileNavMenu">
                <div class="mobile-nav-menu">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="mobile-nav-items">
                                    <!-- Core Items -->
                                    <a href="<?= base_url('usercontrol/dashboard') ?>" class="mobile-nav-item">
                                        <i class="fas fa-home"></i>
                                        <span><?= __('user.page_title_dashboard') ?></span>
                                    </a>
                                    
                                    <a href="<?= base_url('usercontrol/analytics_dashboard') ?>" class="mobile-nav-item">
                                        <i class="fas fa-chart-line"></i>
                                        <span><?= __('user.analytics') ?></span>
                                    </a>

                                    <!-- Business Section -->
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileBusinessCollapse" aria-expanded="false" aria-controls="mobileBusinessCollapse">
                                            <i class="fas fa-briefcase"></i>
                                            <span><?= __('user.my_business') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileBusinessCollapse">
                                            <div class="mobile-nav-section-items">
                                                <a href="<?= base_url('usercontrol/mywallet') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-wallet"></i>
                                                    <span><?= __('user.page_title_my_wallet') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/all_transaction') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span><?= __('user.page_title_all_trans_user') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/wallet_requests_list') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-list-alt"></i>
                                                    <span><?= __('user.usercontrol_wallet_requests_list') ?></span>
                                                </a>
                                                <?php if($userdetails['is_vendor']==0 || ($userdetails['is_vendor']==1 && $market_vendor['marketvendorpanelmode'] ==0 )) { ?>
                                                <a href="<?= base_url('usercontrol/store_markettools') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-link"></i>
                                                    <span><?= __('user.page_title_my_links') ?></span>
                                                </a>
                                                <?php } ?>
                                                <?php if($refer_status) { ?>
                                                <a href="<?= base_url('usercontrol/my_network') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-network-wired"></i>
                                                    <span><?= __('user.page_title_my_network') ?></span>
                                                </a>
                                                <?php } ?>
                      </div>
                    </div>
                  </div>

                                    <!-- Marketing Section (Vendor Only) -->
                                    <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && ((int)$market_vendor['marketvendorstatus'] == 1 )) { ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMarketingCollapse" aria-expanded="false" aria-controls="mobileMarketingCollapse">
                                            <i class="fas fa-rocket"></i>
                                            <span><?= __('user.page_title_marketing') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileMarketingCollapse">
                                            <div class="mobile-nav-section-items">
                                                <a href="<?= base_url('usercontrol/programs') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-tasks"></i>
                                                    <span><?= __('user.ven_programs') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/integration_tools') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-bullhorn"></i>
                                                    <span><?= __('user.page_title_campaigns') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/integration') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-plug"></i>
                                                    <span><?= __('user.page_title_plugins') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/wallet_setting') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-cog"></i>
                                                    <span><?= __('user.page_title_vendor_setting') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/external_vendor_orders') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-box"></i>
                                                    <span><?= __('user.page_title_my_orders') ?></span>
                                                </a>
                      </div>
                          </div>
                            </div>
                                    <?php } ?>

                                    <!-- Vendor Panel Link (Vendor Only) -->
                                    <?php if(isset($userdetails['is_vendor']) && $userdetails['is_vendor']){ ?>
                                    <a href="<?= base_url('usercontrol/my_vendor_panel') ?>" class="mobile-nav-item">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span><?= __('user.vendor_panel') ?? 'Vendor Panel' ?></span>
                                    </a>
                                    <?php } ?>

                                    <!-- Store Section (Vendor Only) -->
                                    <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1){ ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileStoreCollapse" aria-expanded="false" aria-controls="mobileStoreCollapse">
                                            <i class="fas fa-store"></i>
                                            <span><?= __('user.page_title_vendor_store') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileStoreCollapse">
                                            <div class="mobile-nav-section-items">
                                                <a href="<?= base_url('usercontrol/store_dashboard') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-tachometer-alt"></i>
                                                    <span><?= __('user.page_title_store_dashboard') ?></span>
                                                </a>
                                                <?php if($store_setting['store_mode'] == 'cart') {?>
                                                <a href="<?= base_url('usercontrol/store_products') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-box"></i>
                                                    <span><?= __('user.page_title_common_title_store_products') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/store_coupon') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-ticket-alt"></i>
                                                    <span><?= __('user.page_title_store_coupons') ?></span>
                                                </a>
                                                <?php } ?>
                                                <?php if($store_setting['store_mode'] == 'sales') {?>
                                                <a href="<?= base_url('usercontrol/sales_products') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-box"></i>
                                                    <span><?= __('user.page_title_common_title_store_products') ?></span>
                                                </a>
                                                <?php } ?>
                                                <a href="<?= base_url('usercontrol/store_venodr_orders') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-shopping-bag"></i>
                                                    <span><?= __('user.vendor_orders_small') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/listclients') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-users"></i>
                                                    <span><?= __('user.store_clients') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/vendor_store_analytics') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-chart-line"></i>
                                                    <span><?= __('user.vendor_analytics') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/vendor_payouts') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-money-bill-transfer"></i>
                                                    <span><?= __('user.payout_history') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/store_setting') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-cog"></i>
                                                    <span><?= __('user.page_title_store_setting') ?></span>
                                                </a>
                          </div>
                        </div>
                                    </div>
                                    <?php } ?>

                                    <!-- Settings Section -->
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSettingsCollapse" aria-expanded="false" aria-controls="mobileSettingsCollapse">
                                            <i class="fas fa-cog"></i>
                                            <span><?= __('user.settings') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileSettingsCollapse">
                                            <div class="mobile-nav-section-items">
                                                <a href="<?= base_url('usercontrol/editProfile') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-user"></i>
                                                    <span><?= __('user.profile_details') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/changePassword') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-lock"></i>
                                                    <span><?= __('user.page_title_changePassword') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/payment_details') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-credit-card"></i>
                                                    <span><?= __('user.payment_details') ?></span>
                                                </a>
                                                <a href="<?= base_url($loginUser['is_vendor'] == 1 ? 'usercontrol/settings_hub' : 'usercontrol/vendor_settings') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-sliders-h"></i>
                                                    <span><?= __('user.settings') ?></span>
                                                </a>
                                                <a href="<?= base_url('ReportController/user_reports') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-chart-bar"></i>
                                                    <span><?= __('user.page_title_user_reports') ?></span>
                                                </a>
                                                <?php if(isShowUserControlParts($userdashboard_settings['tickets_page'])){ ?>
                                                <a href="<?= base_url('usercontrol/tickets') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-ticket-alt"></i>
                                                    <span><?= __('user.page_title_tickets') ?></span>
                                                </a>
                                                <?php } ?>
                                                <?php if(isShowUserControlParts($userdashboard_settings['contact_us_page'])){ ?>
                                                <a href="<?= base_url('usercontrol/contact-us') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-envelope"></i>
                                                    <span><?= __('user.page_title_contact_admin') ?></span>
                                                </a>
                                                <?php } ?>
                      </div>
                    </div>
                  </div>

                                    <!-- Membership Section (Conditional) -->
                                    <?php if(($membership['status'] == 1) || (($membership['status'] == 2) && ($userdetails['is_vendor'] == 1)) || (($membership['status'] == 3) && ($userdetails['is_vendor'] == 0))){ ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMembershipCollapse" aria-expanded="false" aria-controls="mobileMembershipCollapse">
                                            <i class="fas fa-crown"></i>
                                            <span><?= __('user.page_title_membership') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileMembershipCollapse">
                                            <div class="mobile-nav-section-items">
                                                <a href="<?= base_url('usercontrol/purchase_plan') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span><?= __('user.page_title_buy_membership') ?></span>
                                                </a>
                                                <a href="<?= base_url('usercontrol/purchase_history') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-history"></i>
                                                    <span><?= __('user.page_title_purchase_history') ?></span>
                                                </a>
                    </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <!-- Tutorials Section (Conditional) -->
                                    <?php if(isset($SiteSetting["tutorial_module_status"]) && $SiteSetting["tutorial_module_status"]==1 && isset($tutorials) && is_array($tutorials) && count($tutorials)>0) { ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileTutorialsCollapse" aria-expanded="false" aria-controls="mobileTutorialsCollapse">
                                            <i class="fas fa-graduation-cap"></i>
                                            <span><?= __('user.page_title_tutorial') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileTutorialsCollapse">
                                            <div class="mobile-nav-section-items" style="max-height: 300px; overflow-y: auto;">
                                                <?php
                                                $tutorialCategoryId=0;
                                                foreach($tutorials as $tutorial) { 
                                                    if($tutorialCategoryId!=$tutorial['category_id']) {
                                                        if($tutorialCategoryId!=0) {
                                                            echo '</div></div>';
                                                        }
                                                        echo '<div class="mobile-nav-subitem fw-bold text-primary ps-3">' . $tutorial['name'] . '</div>';
                                                        echo '<div class="tutorial-mobile-group">';
                                                        $tutorialCategoryId=$tutorial['category_id'];
                                                    }
                                                    ?>
                                                    <a href="<?= base_url('usercontrol/tutorial/'.$tutorial['id']) ?>" class="mobile-nav-subitem ps-4">
                                                        <i class="fas fa-file-alt"></i>
                                                        <span><?= $tutorial['title'] ?></span>
                                                    </a>
                                                <?php 
                                                }
                                                if($tutorialCategoryId!=0) {
                                                    echo '</div>';
                                                }
                                                ?>
                              </div>
                            </div>
                          </div>
                                    <?php } ?>

                                    <!-- Additional Features (MLM, Deposits) -->
                                    <?php 
                                    $hasAdditionalFeatures = false;
                                    if(isset($market_vendor) && $market_vendor['vendormlmmodule'] == 1) {
                                        if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && ((int)$market_vendor['marketvendorstatus'] == 1 ) || (isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1) {
                                            $hasAdditionalFeatures = true;
                                        }
                                    }
                                    if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) == 1) {
                                        $hasAdditionalFeatures = true;
                                    }
                                    
                                    if($hasAdditionalFeatures) { ?>
                                    <div class="mobile-nav-section">
                                        <button class="mobile-nav-section-header" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMoreCollapse" aria-expanded="false" aria-controls="mobileMoreCollapse">
                                            <i class="fas fa-star"></i>
                                            <span><?= __('user.more') ?></span>
                                        </button>
                                        <div class="collapse" id="mobileMoreCollapse">
                                            <div class="mobile-nav-section-items">
                                                <?php if(isset($market_vendor) && $market_vendor['vendormlmmodule'] == 1) {
                                                    if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && ((int)$market_vendor['marketvendorstatus'] == 1 ) || (isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1) { ?>
                                                <a href="<?= base_url('usercontrol/mlm_levels') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-sitemap"></i>
                                                    <span><?= __('user.page_title_mlm_setting') ?></span>
                                                </a>
                                                <?php } } ?>
                                                <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) == 1){ ?>
                                                <a href="<?= base_url('usercontrol/my_deposits') ?>" class="mobile-nav-subitem">
                                                    <i class="fas fa-money-bill"></i>
                                                    <span><?= __('user.page_title_my_deposits') ?></span>
                                                </a>
                                                <?php } ?>
                      </div>
                          </div>
                            </div>
                                    <?php } ?>
                                    
                                    <!-- Logout Button -->
                                    <a href="<?= base_url('usercontrol/logout'); ?>" class="mobile-nav-item text-danger mt-3">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span><?= __('user.logout') ?></span>
                                    </a>
                          </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="navbar navbar-expand-lg border-bottom d-none d-lg-block bg-dark">
                <div class="container-fluid px-3">
                    <ul class="navbar-nav d-flex flex-row gap-1">
                        <li class="nav-item">
                            <a href="<?= base_url('usercontrol/dashboard') ?>" class="nav-link text-white px-3 py-2 rounded">
                                <i class="fas fa-home me-2"></i><?= __('user.page_title_dashboard') ?>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url('usercontrol/analytics_dashboard') ?>" class="nav-link text-white px-3 py-2 rounded">
                                <i class="fas fa-chart-line me-2"></i><?= __('user.analytics') ?>
                            </a>
                </li>

                <li class="nav-item dropdown">
                            <a href="#" class="nav-link text-white px-3 py-2 rounded dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-briefcase me-2"></i><?= __('user.my_business') ?>
                            </a>
                            <ul class="dropdown-menu shadow">
                                <li><h6 class="dropdown-header"><i class="fas fa-coins me-2"></i><?= __('user.financial') ?></h6></li>
                                <li>
                        <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/mywallet') ?>">
                                            <i class="fas fa-wallet me-2"></i><?= __('user.page_title_my_wallet') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/mywallet') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                          </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/all_transaction') ?>">
                                            <i class="fas fa-shopping-cart me-2"></i><?= __('user.page_title_all_trans_user') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/all_transaction') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/wallet_requests_list') ?>">
                                            <i class="fas fa-list-alt me-2"></i><?= __('user.usercontrol_wallet_requests_list') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/wallet_requests_list') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                          </div>
                                </li>
                                <?php if($userdetails['is_vendor']==0 || ($userdetails['is_vendor']==1 && $market_vendor['marketvendorpanelmode'] ==0 )) { ?>
                    <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header"><i class="fas fa-link me-2"></i><?= __('user.links') ?></h6></li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/store_markettools') ?>">
                                            <i class="fas fa-link me-2"></i><?= __('user.page_title_my_links') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/store_markettools') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                                </li>
                                <?php } ?>
                                <?php if($refer_status) { ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header"><i class="fas fa-network-wired me-2"></i><?= __('user.network') ?></h6></li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/my_network') ?>">
                                            <i class="fas fa-network-wired me-2"></i><?= __('user.page_title_my_network') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/my_network') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                      </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>

                        <!-- Vendor Panel Link (new-sidebar) -->
                        <?php if(isset($userdetails['is_vendor']) && $userdetails['is_vendor']){ ?>
                        <li class="nav-item">
                            <a href="<?= base_url('usercontrol/my_vendor_panel') ?>" class="nav-link text-white px-3 py-2 rounded">
                                <i class="fas fa-tachometer-alt me-2"></i><?= __('user.vendor_panel') ?? 'Vendor Panel' ?>
                            </a>
                        </li>
                        <?php } ?>

                        <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && ((int)$market_vendor['marketvendorstatus'] == 1 )) { ?>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link text-white px-3 py-2 rounded dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-rocket me-2"></i><?= __('user.page_title_marketing') ?>
                            </a>
                            <ul class="dropdown-menu shadow">
                                <li><h6 class="dropdown-header"><i class="fas fa-bullhorn me-2"></i><?= __('user.campaigns') ?></h6></li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/programs') ?>">
                                            <i class="fas fa-tasks me-2"></i><?= __('user.ven_programs') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/programs') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/integration_tools') ?>">
                                            <i class="fas fa-bullhorn me-2"></i><?= __('user.page_title_campaigns') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/integration_tools') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                  </div>
                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/integration') ?>">
                                            <i class="fas fa-plug me-2"></i><?= __('user.page_title_plugins') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/integration') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header"><i class="fas fa-cog me-2"></i><?= __('user.management') ?></h6></li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/wallet_setting') ?>">
                                            <i class="fas fa-cog me-2"></i><?= __('user.page_title_vendor_setting') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/wallet_setting') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/external_vendor_orders') ?>">
                                            <i class="fas fa-box me-2"></i><?= __('user.page_title_my_orders') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/external_vendor_orders') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                            </ul>
                    </li>
                        <?php } ?>

                        <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1){ ?>
                <li class="nav-item dropdown">
                            <a href="#" class="nav-link text-white px-3 py-2 rounded dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-store me-2"></i><?= __('user.page_title_vendor_store') ?>
                            </a>
                            <ul class="dropdown-menu shadow">
                                <li><h6 class="dropdown-header"><i class="fas fa-tachometer-alt me-2"></i><?= __('user.overview') ?></h6></li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/store_dashboard') ?>">
                                            <i class="fas fa-tachometer-alt me-2"></i><?= __('user.page_title_store_dashboard') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/store_dashboard') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header"><i class="fas fa-box me-2"></i><?= __('user.products') ?></h6></li>
                                <?php if($store_setting['store_mode'] == 'cart') {?>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/store_products') ?>">
                                            <i class="fas fa-box me-2"></i><?= __('user.page_title_common_title_store_products') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/store_products') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/store_coupon') ?>">
                                            <i class="fas fa-ticket-alt me-2"></i><?= __('user.page_title_store_coupons') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/store_coupon') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                    </li>
                                <?php } ?>
                                <?php if($store_setting['store_mode'] == 'sales') {?>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/sales_products') ?>">
                                            <i class="fas fa-box me-2"></i><?= __('user.page_title_common_title_store_products') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/sales_products') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                    </li>
                                <?php } ?>
                    <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header"><i class="fas fa-shopping-bag me-2"></i><?= __('user.management') ?></h6></li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/store_venodr_orders') ?>">
                                            <i class="fas fa-shopping-bag me-2"></i><?= __('user.vendor_orders_small') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/store_venodr_orders') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/listclients') ?>">
                                            <i class="fas fa-users me-2"></i><?= __('user.store_clients') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/listclients') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/store_setting') ?>">
                                            <i class="fas fa-cog me-2"></i><?= __('user.page_title_store_setting') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/store_setting') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                    </li>
                  </ul>
                </li>
                        <?php } ?>

                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link text-white px-3 py-2 rounded dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-2"></i><?= __('user.settings') ?>
                            </a>
                            <ul class="dropdown-menu shadow">
                                <li><h6 class="dropdown-header"><i class="fas fa-user me-2"></i><?= __('user.account') ?></h6></li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/editProfile') ?>">
                                            <i class="fas fa-user me-2"></i><?= __('user.profile_details') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/editProfile') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
            </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/changePassword') ?>">
                                            <i class="fas fa-lock me-2"></i><?= __('user.page_title_changePassword') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/changePassword') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/payment_details') ?>">
                                            <i class="fas fa-credit-card me-2"></i><?= __('user.payment_details') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/payment_details') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                    <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header"><i class="fas fa-sliders-h me-2"></i><?= __('user.preferences') ?></h6></li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url($loginUser['is_vendor'] == 1 ? 'usercontrol/settings_hub' : 'usercontrol/vendor_settings') ?>">
                                            <i class="fas fa-sliders-h me-2"></i><?= __('user.settings') ?>
                                        </a>
                                        <a href="<?= base_url($loginUser['is_vendor'] == 1 ? 'usercontrol/settings_hub' : 'usercontrol/vendor_settings') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('ReportController/user_reports') ?>">
                                            <i class="fas fa-chart-bar me-2"></i><?= __('user.page_title_user_reports') ?>
                                        </a>
                                        <a href="<?= base_url('ReportController/user_reports') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <?php if(isShowUserControlParts($userdashboard_settings['tickets_page'])){ ?> 
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/tickets') ?>">
                                            <i class="fas fa-ticket-alt me-2"></i><?= __('user.page_title_tickets') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/tickets') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <?php } ?>
                                <?php if(isShowUserControlParts($userdashboard_settings['contact_us_page'])){ ?>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/contact-us') ?>">
                                            <i class="fas fa-envelope me-2"></i><?= __('user.page_title_contact_admin') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/contact-us') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <?php } ?>
              </ul>
                        </li>

                        <?php if(($membership['status'] == 1) || (($membership['status'] == 2) && ($userdetails['is_vendor'] == 1)) || (($membership['status'] == 3) && ($userdetails['is_vendor'] == 0))){ ?>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link text-white px-3 py-2 rounded dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-crown me-2"></i><?= __('user.page_title_membership') ?>
                            </a>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/purchase_plan') ?>">
                                            <i class="fas fa-shopping-cart me-2"></i><?= __('user.page_title_buy_membership') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/purchase_plan') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
            </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/purchase_history') ?>">
                                            <i class="fas fa-history me-2"></i><?= __('user.page_title_purchase_history') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/purchase_history') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                            </ul>
                    </li>
                        <?php } ?>

                        <?php if(isset($SiteSetting["tutorial_module_status"]) && $SiteSetting["tutorial_module_status"]==1 && isset($tutorials) && is_array($tutorials) && count($tutorials)>0) { ?>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link text-white px-3 py-2 rounded dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-graduation-cap me-2"></i><?= __('user.page_title_tutorial') ?>
                            </a>
                            <ul class="dropdown-menu shadow tutorial-dropdown">
                                <?php
                                $tutorialCategoryId=0;
                                $prevCategory = '';
                                foreach($tutorials as $tutorial) { 
                                    if($tutorialCategoryId!=$tutorial['category_id']) {
                                        if($tutorialCategoryId!=0) {
                                            echo '</div>';
                                        }
                                        echo '<li><h6 class="dropdown-header">' . $tutorial['name'] . '</h6></li>';
                                        $tutorialCategoryId=$tutorial['category_id'];
                                        echo '<div class="tutorial-category-group">';
                                    }
                                    ?>
                                    <li><a class="dropdown-item" href="<?= base_url('usercontrol/tutorial/'.$tutorial['id']) ?>">
                                        <i class="fas fa-file-alt me-2"></i><?= $tutorial['title'] ?>
                                    </a></li>
                                <?php 
                                }
                                if($tutorialCategoryId!=0) {
                                    echo '</div>';
                                }
                                ?>
                  </ul>
                </li>
                        <?php } ?>

                        <?php 
                        $hasAdditionalFeatures = false;
                        if(isset($market_vendor) && $market_vendor['vendormlmmodule'] == 1) {
                            if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && ((int)$market_vendor['marketvendorstatus'] == 1 ) || (isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1) {
                                $hasAdditionalFeatures = true;
                            }
                        }
                        if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) == 1) {
                            $hasAdditionalFeatures = true;
                        }
                        
                        if($hasAdditionalFeatures) { ?>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link text-white px-3 py-2 rounded dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-star me-2"></i><?= __('user.more') ?>
                            </a>
                            <ul class="dropdown-menu shadow">
                                <?php if(isset($market_vendor) && $market_vendor['vendormlmmodule'] == 1) {
                                    if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && ((int)$market_vendor['marketvendorstatus'] == 1 ) || (isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1) { ?>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/mlm_levels') ?>">
                                            <i class="fas fa-sitemap me-2"></i><?= __('user.page_title_mlm_setting') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/mlm_levels') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <?php } } ?>
                                <?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) == 1){ ?>
                                <li>
                                    <div class="d-flex align-items-center">
                                        <a class="dropdown-item flex-grow-1" href="<?= base_url('usercontrol/my_deposits') ?>">
                                            <i class="fas fa-money-bill me-2"></i><?= __('user.page_title_my_deposits') ?>
                                        </a>
                                        <a href="<?= base_url('usercontrol/my_deposits') ?>" target="_blank" class="btn btn-sm btn-link text-muted px-2" title="<?= __('user.open_in_new_tab') ?>"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
              </ul>
          </div>
        </nav>
            
            <div class="container-fluid py-4 bg-light">

        <?php if($method == "dashboard"){ ?>
        <!-- Nav Header Component Start -->
        <div class="container-fluid">
          <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
              <div class="d-flex align-items-center mb-2 mb-md-0">
                <div>
                  <h5 class="mb-1"><?= __('user.hello'); ?> <span class="text-primary fw-bold"><?= $loginUser['username'] ?></span></h5>
                  <small class="text-muted">
                    <?= __('user.user_level_commission_plan') ?>:
                    <span class="badge bg-secondary">
                      <?php
                        $user_level = '';
                        $user_sale_comission_value = '';
                        if ($award_level['status']) {
                          if ($membership['status'] && isset($userPlan) && $userPlan && $userPlan->commission_sale_status) {
                            if ($userPlan->level_number) {
                              $user_level = $userPlan->level_number;
                              $user_sale_comission_value = '[' . $userPlan->sale_comission_rate . '%]';
                            } else {
                              $user_level = __('admin.default');
                            }
                          } else if ($userdetails['level_id']) {
                            foreach ($levels as $key => $value) {
                              if ($userdetails['level_id'] == $value['id']) {
                                $user_level = $value['level_number'];
                                $user_sale_comission_value = '[' . $value['sale_comission_rate'] . '%]';
                              }
                            }
                          } else {
                            $user_level = __('admin.default');
                          }
                        } else {
                          $user_level = __('admin.default');
                        }
                        echo $user_level . ' ' . $user_sale_comission_value;
                      ?>
                    </span>
                  </small>
                </div>
              </div>
              <div class="d-flex align-items-center">
                <i class="fas fa-clock text-primary me-2"></i>
                <small class="text-muted me-1"><?= __('admin.session_timeout') ?>:</small>
                <strong class="text-dark session-timer">
                  <em>
                    <?php
                      $hours = floor($timeout / 3600);
                      $minutes = floor(($timeout % 3600) / 60);
                      $seconds = ($timeout % 3600) % 60;
                      echo sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                    ?>
                  </em>
                </strong>
              </div>
            </div>
          </div>
        </div>
        <!-- Nav Header Component End -->
        <?php } ?>

        <!-- Alerts -->
        <div class="container-fluid">
          <div class="row row-cols-1 g-2">
            <?php if (!empty($vendor_data['show_vendor_status_notification'])): ?>
            <div class="col">
              <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-ban me-2"></i>
                <?=__('user.view_products_on_store_is_disabled');?>
                <a href="<?= base_url('usercontrol/store_setting'); ?>" class="ms-1 fw-bold text-white" target="_blank">
                  <?=__('user.click_here');?>
                </a>
                <?=__('user.display_vendor_products_message');?>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($vendor_data['show_commission_notification'])): ?>
            <div class="col">
              <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-percent me-2"></i>
                <?=__('user.store_commissions_not_set');?>
                <a href="<?= base_url('usercontrol/store_setting'); ?>" class="ms-1 fw-bold text-dark" target="_blank">
                  <?=__('user.click_here');?>
                </a>
                <?=__('user.to_set_commissions');?>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($vendor_data['show_share_sales_notification'])): ?>
            <div class="col">
              <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-eye-slash me-2"></i>
                <?= __('user.campaigns_not_visible_to_affiliates'); ?>
                <a href="<?= base_url('usercontrol/share_sales_setting'); ?>" class="ms-1 fw-bold">
                  <?= __('user.click_here'); ?>
                </a>&nbsp;<?= __('user.to_update_sharing_settings'); ?>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($vendor_data['show_deposit_warning'])): ?>
            <div class="col">
              <div class="alert alert-warning border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                  <div class="me-3 fs-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1 fw-bold"><?= __('user.deposit_notification') ?></h6>
                    <p class="mb-0"><?= $vendor_data['deposit_warning_message'] ?></p>
                  </div>
                  <div class="ms-3">
                    <a href="<?= base_url('usercontrol/my_deposits') ?>" class="btn btn-sm btn-warning">
                      <i class="bi bi-eye me-1"></i><?= __('user.view_deposits') ?>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <?php if ($userdetails['reg_approved'] == 1): ?>
              <?php $paymentDetailsSet = false; ?>
              <?php if ($PrimaryPaymentMethodStatus == 0): ?>
              <div class="col">
                <div class="alert alert-warning" role="alert">
                  <i class="fas fa-money-check me-2"></i>
                  <?= __('user.payment_details_not_set_message'); ?>
                  <a href="<?= base_url('usercontrol/payment_details') ?>" class="ms-1 fw-bold text-dark"><?= __('user.click_here'); ?></a>
                </div>
              </div>
              <?php $paymentDetailsSet = true; ?>
              <?php endif; ?>

              <?php if (!$paymentDetailsSet && $userdetails['primary_payment_method'] == 'bank_transfer' && isset($payment_methods) && is_array($payment_methods['bank_transfer']) && $payment_methods['bank_transfer']['status'] == 1): ?>
                <?php 
                $missingDetails = false;
                if ($paymentlist['payment_account_number'] == '' || 
                    $paymentlist['payment_bank_name'] == '' || 
                    $paymentlist['payment_account_name'] == '') {
                    $missingDetails = true;
                }
                if (isset($payment_methods['bank_transfer']['bank_transfer_mode']) && 
                    $payment_methods['bank_transfer']['bank_transfer_mode'] == 'specific') {
                  if (empty($paymentlist['payment_country'])) {
                      $missingDetails = true;
                  } else {
                      switch($paymentlist['payment_country']) {
                          case 'US':
                              if (empty($paymentlist['payment_routing_number'])) {
                                  $missingDetails = true;
                              }
                              break;
                      }
                  }
                }
                if ($missingDetails): ?>
                <div class="col">
                  <div class="alert alert-warning" role="alert">
                    <i class="fas fa-university me-2"></i>
                    <?= __('user.payment_details_not_set_message'); ?>
                    <a href="<?= base_url('usercontrol/payment_details') ?>" class="ms-1 fw-bold text-dark"><?= __('user.click_here'); ?></a>
                  </div>
                </div>
                <?php $paymentDetailsSet = true; ?>
                <?php endif; ?>
              <?php endif; ?>

              <?php if (!$paymentDetailsSet && $userdetails['primary_payment_method'] == 'paypal' && isset($payment_methods['paypal']) && is_array($payment_methods['paypal']) && $payment_methods['paypal']['status'] == 1): ?>
                <?php if ($paymentlist['paypalaccounts']['paypal_email'] == ''): ?>
                <div class="col">
                  <div class="alert alert-warning" role="alert">
                    <i class="fab fa-paypal me-2"></i>
                    <?= __('user.payment_details_not_set_message'); ?>
                    <a href="<?= base_url('usercontrol/payment_details') ?>" class="ms-1 fw-bold text-dark"><?= __('user.click_here'); ?></a>
                  </div>
                </div>
                <?php endif; ?>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
        <!-- Alerts End -->

<?php 
if(isset($pageSetting[$pageKey]['breadcrumb']) && count($pageSetting[$pageKey]['breadcrumb']) > 0) { 
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded-3 p-3 mb-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <?php 
                    $count = count($pageSetting[$pageKey]['breadcrumb']) - 1;
                    foreach($pageSetting[$pageKey]['breadcrumb'] as $key => $value) { ?> 
                        <?php if($count == $key) { ?>
                            <?php if($key > 0) { ?>
                                <i class="fas fa-chevron-right text-muted me-2"></i>
                            <?php } ?>
                            <span class="fw-bold text-dark">
                                <?php if($key == 0) { ?><i class="fas fa-home me-1"></i><?php } ?><?= $value['title'] ?>
                            </span>
                        <?php } else { ?>
                            <?php if($key > 0) { ?>
                                <i class="fas fa-chevron-right text-muted me-2"></i>
                            <?php } ?>
                            <a href="<?= $value['link'] ?>" class="text-decoration-none me-2">
                                <?php if($key == 0) { ?><i class="fas fa-home me-1"></i><?php } ?><?= $value['title'] ?>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>