<?php 
   $db =& get_instance(); 
   $googlerecaptcha = $db->Product_model->getSettings('googlerecaptcha');
   $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
   $sitekey = $googlerecaptcha['sitekey'] ?? '';
?>

<link href="<?php echo base_url('assets/store/classified/classified-checkout/checkout-1/') ?>css/checkout-1.css" rel="stylesheet">
<link href="<?php echo base_url('assets/store/classified/classified-checkout/checkout-1/') ?>css/responsive.css" rel="stylesheet">
<link href="<?php echo base_url('assets/store/classified/classified-checkout/checkout-1/') ?>css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script src="<?php echo base_url('assets/store/classified/classified-checkout/checkout-1/') ?>js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">

   <?php if(is_rtl()) { ?>
      <!-- place here your RTL css code -->
      <link rel="stylesheet" href="<?php echo base_url('assets/store/classified/classified-checkout/checkout-1/') ?>css/rtl.css?v=
      <?= av() ?>">
   <?php } ?>

<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
<script type="text/javascript">
   var ischeckout =true;
</script>

<section aff-section="classified_checkout_language_and_currency" class="d-flex flex-row-reverse mt-3 w-100 pe-3" style="position: absolute;"></section>

<script aff-template="classified_checkout_language_and_currency" type="text/html">
   {{#SelectedLanguage}}
   <div class="dropdown currency-dropdown">
     <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
       {{SelectedLanguage}}
     </button>
     <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
         {{#LanguageHtml}}
           <li><a class="dropdown-item" href="{{href}}">{{name}}</a></li>
         {{/LanguageHtml}}
     </ul>
   </div>
   {{/SelectedLanguage}}

   {{#SelectedCurrency}}
   <div class="dropdown currency-dropdown">
     <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
       {{SelectedCurrency}}
     </button>
     <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
         {{#CurrencyHtml}}
           <li><a class="dropdown-item" href="{{href}}">{{code}}</a></li>
         {{/CurrencyHtml}}
     </ul>
   </div>
   {{/SelectedCurrency}}      
</script>

<section aff-section="classified_checkout_page"></section>
<script aff-template="classified_checkout_page" type="text/html">
   <main class="checkout-modern">
      <div class="container">
         <div class="checkout-wrapper">
            <div class="secure-header">
               <div class="secure-badge">
                  <i class="fas fa-shield-alt"></i>
                  <span><?php echo __('store.safe_secure_checkout');?></span>
               </div>
            </div>

            {{#alert_message}}
            <div class="alert alert-danger">
               <i class="fas fa-exclamation-circle"></i>
               {{alert_message}}
            </div>
            {{/alert_message}}

            <div class="checkout-grid">
               <div class="checkout-main">
                  {{#show_product_form}}
                  <div class="checkout-card">
                     <div class="card-header">
                        <div class="header-left">
                           <span class="step-number">1</span>
                           <h3><?php echo $allow_shipping == 1 ? __('store.billing_shipping_address') : __('store.billing_address');?></h3>
                        </div>
                        <div class="header-right">
                           <?php if(!$is_logged) { ?>
                              <a href="#loginModal" class="login-link" data-bs-toggle="modal" data-bs-target="#loginModal">
                                 <i class="fas fa-user"></i>
                                 <?php echo __('store.already_user_login');?>
                              </a>
                           <?php } else { ?>
                              <div class="welcome-user">
                                 <i class="fas fa-check-circle"></i>
                                 <?php echo __('store.welcome');?> <?php echo !empty($userdetails['username'])? $userdetails['username'] :$userdetails['firstname'];?>
                              </div>
                           <?php } ?>
                        </div>
                     </div>

                     <section aff-section="classified_checkout_form">
                        <form class="needs-validation modern-form" novalidate>
                           <div class="form-grid">
                              <div class="form-group <?php echo $is_logged==true && $allow_shipping ? 'd-none':'';?>">
                                 <label><i class="fas fa-user"></i> <?php echo __('store.enter_your_first_name')?></label>
                                 <input type="text" class="form-control" name="firstname" placeholder="<?php echo __('store.enter_your_first_name')?>" value="<?php echo $is_logged==true ? $userdetails['firstname']:''?>"/>
                              </div>

                              <div class="form-group <?php echo $is_logged==true && $allow_shipping ? 'd-none':'';?>">
                                 <label><i class="fas fa-user"></i> <?php echo __('store.enter_your_last_name')?></label>
                                 <input type="text" class="form-control" name="lastname" placeholder="<?php echo __('store.enter_your_last_name')?>" value="<?php echo $is_logged==true ? $userdetails['lastname']:''?>"/>
                              </div>

                              <div class="form-group <?php echo $is_logged==true && $allow_shipping ? 'd-none':'';?>">
                                 <label><i class="fas fa-envelope"></i> <?php echo __('store.enter_your_email_address')?></label>
                                 <input type="email" class="form-control" name="email" placeholder="<?php echo __('store.enter_your_email_address')?>" value="<?php echo $is_logged==true ? $userdetails['email']:''?>"/>
                              </div>

                              <?php if (!$allow_shipping): ?>
                                 <input type="hidden" id="phonenumber-input" name='PhoneNumberInput' value=""/>
                                 <div class="form-group <?php echo $is_logged==true && $allow_shipping ? 'd-none':'';?>">
                                    <label><i class="fas fa-phone"></i> <?php echo __('store.phone')?></label>
                                    <input onkeypress="return isNumberKey(event);" type="text" id="phoneguest" class="form-control" name="phone" placeholder="<?php echo __('store.phone')?>" value="<?php echo $is_logged==true ? $userdetails['phone']:''?>"/>
                                 </div>
                              <?php endif ?>

                              {{#allow_shipping}}
                              <input type="hidden" id="phonenumber-input" name='PhoneNumberInput' value=""/>
                              <div class="form-group">
                                 <label><i class="fas fa-phone"></i> <?php echo __('store.phone')?></label>
                                 <input onkeypress="return isNumberKey(event);" type="text" class="form-control" name="phone" id="phone" value="<?php echo $is_logged==true ? $shipping->phone:''?>" placeholder="<?php echo __('store.phone')?>"/>
                              </div>

                              <div class="form-group">
                                 <label><i class="fas fa-globe"></i> <?php echo __('store.select_country');?></label>
                                 <select class="form-select" name="country" id="country_id">
                                    <option disabled selected><?php echo __('store.select_country');?></option>
                                 </select>
                              </div>

                              <div class="form-group">
                                 <label><i class="fas fa-map-marker-alt"></i> <?php echo __('store.select_state');?></label>
                                 <select class="form-select" name="state" id="state_id">
                                    <option disabled selected><?php echo __('store.select_state');?></option>
                                 </select>
                              </div>

                              <div class="form-group">
                                 <label><i class="fas fa-city"></i> <?php echo __('store.city');?></label>
                                 <input type="text" class="form-control" value="<?php echo $is_logged==true ? $shipping->city:''?>" name="city" placeholder="<?php echo __('store.city');?>"/>
                              </div>

                              <div class="form-group">
                                 <label><i class="fas fa-mail-bulk"></i> <?php echo __('store.postal_code');?></label>
                                 <input type="text" value="<?php echo $is_logged==true ? $shipping->zip_code:''?>" class="form-control" name="zip_code" placeholder="<?php echo __('store.postal_code');?>"/>
                              </div>

                              <div class="form-group full-width">
                                 <label><i class="fas fa-home"></i> <?php echo __('store.address')?></label>
                                 <input type="text" class="form-control" name="address" placeholder="<?php echo __('store.address')?>" value="<?php echo $is_logged==true ? $shipping->address:''?>"/>
                              </div>
                              {{/allow_shipping}}
                           </div>
                        </form>
                     </section>
                  </div>

                  <div class="checkout-card">
                     <div class="card-header">
                        <span class="step-number">2</span>
                        <h3><?php echo __('store.payment')?></h3>
                     </div>

                     <div class="payment-methods">
                        {{^payment_gateways}}
                        <div class="alert alert-info">
                           <i class="fas fa-info-circle"></i>
                           <?= __('store.no_payment_options') ?>
                        </div>
                        {{/payment_gateways}}

                        {{#payment_gateways}}
                        <div class="payment-option">
                           {{#active}}
                           <input id="{{name}}" name="payment_gateway" type="radio" class="payment-radio" value="{{name}}" checked/>
                           {{/active}}
                           {{^active}}
                           <input id="{{name}}" name="payment_gateway" type="radio" class="payment-radio" value="{{name}}"/>
                           {{/active}}
                           <label for="{{name}}" class="payment-label">
                              <img src="{{icon}}" alt="{{display_name}}"/>
                              <span class="payment-name">{{display_name}}</span>
                              <span class="checkmark"><i class="fas fa-check"></i></span>
                           </label>
                        </div>
                        {{/payment_gateways}}
                     </div>
                  </div>

                  <div class="checkout-card">
                     <div class="card-header">
                        <span class="step-number">3</span>
                        <h3><?php echo __('store.review_your_order')?></h3>
                     </div>

                     <div class="agreement-box">
                        <input type="checkbox" id="chkAgree" class="agreement-checkbox" value="1" name="agree"/>
                        <label for="chkAgree" class="agreement-label">
                           <span class="checkbox-custom"></span>
                           <?= __('store.agree_text') ?>
                        </label>
                     </div>

                     <div class="checkout-action">
                        <button type="button" class="checkout-btn-main" aff-button="classified_checkout_form">
                           <i class="fas fa-lock"></i>
                           <?php echo __('store.continue_to_checkout')?>
                        </button>
                        <input type="hidden" name="checkout_page" value="true"/>
                        <p class="text-danger" style="display: none;" id="isErrorAgree"><?php echo __('store.the_agree_field_is_required');?></p>
                     </div>
                  </div>

                  <section aff-section="confirm_classified_checkout_form"></section>
                  {{/show_product_form}}

                  {{#product}}
                  <div class="product-review-section">
                     <div class="product-info-card">
                        <div class="product-header">
                           <h4>{{product_name}}</h4>
                           <div class="product-price">{{product_total_price}}</div>
                        </div>
                        <div class="product-content">
                           <img src="{{product_featured_image}}" alt="{{product_name}}" class="product-image"/>
                           <div class="product-description">
                              <p>{{product_description}}</p>
                           </div>
                        </div>
                     </div>

                     {{#product_reviews}}
                     <div class="review-card">
                        <p class="review-text">{{comment}}</p>
                        <p class="review-author"><i class="fas fa-user-circle"></i> {{name}}</p>
                     </div>
                     {{/product_reviews}}
                  </div>
                  {{/product}}
               </div>

               <div class="checkout-sidebar">
                  <div class="sidebar-sticky">
                     {{#show_product_form}}
                     <div class="order-summary">
                        <h4><i class="fas fa-shopping-cart"></i> <?php echo __('admin.your_cart')?></h4>

                        <div class="cart-items">
                           {{#product}}
                           <div class="cart-item">
                              <img src="{{product_featured_image}}" alt="{{product_name}}"/>
                              <div class="item-details">
                                 <h6>{{product_name}}</h6>
                                 <span class="item-price">{{product_total_price}}</span>
                              </div>
                           </div>
                           {{/product}}
                        </div>

                        <div class="order-totals">
                           {{#totals}}
                           <div class="total-row">
                              <span>{{title}}</span>
                              <span>{{amount}}</span>
                           </div>
                           {{/totals}}
                        </div>
                     </div>
                     {{/show_product_form}}

                     {{#product.product_checkout_terms}}
                     <div class="terms-box">
                        {{{product.product_checkout_terms}}}
                     </div>
                     {{/product.product_checkout_terms}}

                     <div class="trust-badges">
                        <div class="trust-item">
                           <i class="fas fa-shield-alt"></i>
                           <span><?php echo __('store.secure_assured');?></span>
                        </div>
                        <div class="trust-item">
                           <i class="fas fa-undo-alt"></i>
                           <span><?php echo __('store.money_back_guaranteed');?></span>
                        </div>
                     </div>

                     <div class="payment-badges">
                        <img src="<?= base_url('assets/store/classified/classified-checkout/checkout-1/'); ?>img/b1.png" alt="Payment"/>
                        <img src="<?= base_url('assets/store/classified/classified-checkout/checkout-1/'); ?>img/b2.png" alt="Payment"/>
                        <img src="<?= base_url('assets/store/classified/classified-checkout/checkout-1/'); ?>img/b3.png" alt="Payment"/>
                        <img src="<?= base_url('assets/store/classified/classified-checkout/checkout-1/'); ?>img/b4.png" alt="Payment"/>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </main>
</script> 


<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold" id="loginModalLabel">
               <i class="fas fa-sign-in-alt me-2 text-primary"></i>
               <?php echo __('store.sign_in');?>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body px-4 py-3">
            <form id="aff-classified-login-form" method="post" novalidate 
               data-recaptcha-version="<?= $recaptcha_version ?>" 
               data-recaptcha-sitekey="<?= $sitekey ?>">
               
               <div class="mb-3">
                  <label class="form-label fw-semibold">
                     <i class="fas fa-user me-1"></i>
                     <?php echo __('store.username');?> <span class="text-danger">*</span>
                  </label>
                  <input type="text" name="username" autocomplete="username" value="" class="form-control form-control-lg" placeholder="<?php echo __('store.enter_username');?>" required>
               </div>

               <div class="mb-3">
                  <label class="form-label fw-semibold">
                     <i class="fas fa-lock me-1"></i>
                     <?php echo __('store.password');?> <span class="text-danger">*</span>
                  </label>
                  <input type="password" name="password" autocomplete="current-password" class="form-control form-control-lg" placeholder="<?php echo __('store.enter_password');?>" required>
               </div>

               <div class="mb-3 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                     <input type="checkbox" name="rememberme" id="rtcl-rememberme" value="forever" class="form-check-input">
                     <label class="form-check-label" for="rtcl-rememberme">
                        <?php echo __('store.remember_me');?>
                     </label>
                  </div>
                  <a href="<?= base_url('store/forgot') ?>" class="text-decoration-none small">
                     <?php echo __('store.forgot_password_?');?>
                  </a>
               </div>

               <?php if (!empty($googlerecaptcha['client_login']) && !empty($sitekey)): ?>
                  <?php if ($recaptcha_version === 'v2'): ?>
                     <div class="mb-3">
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                        <div class="g-recaptcha" data-sitekey="<?= $sitekey ?>"></div>
                        <input type="hidden" name="captch_response" id="captch_response">
                     </div>
                  <?php elseif ($recaptcha_version === 'v3'): ?>
                     <input type="hidden" name="captch_response" id="recaptcha_token">
                     <script src="https://www.google.com/recaptcha/api.js?render=<?= $sitekey ?>"></script>
                     <script>
                        grecaptcha.ready(function() {
                           grecaptcha.execute('<?= $sitekey ?>', {action: 'client_login'}).then(function(token) {
                              var input = document.getElementById('recaptcha_token');
                              if (input) input.value = token;
                           });
                        });
                     </script>
                  <?php endif; ?>
               <?php endif; ?>

               <div class="d-grid mb-3">
                  <button type="submit" class="btn btn-primary btn-lg">
                     <i class="fas fa-sign-in-alt me-2"></i>
                     <?php echo __('store.login');?>
                  </button>
               </div>

               <div class="text-center">
                  <p class="mb-0 text-muted">
                     <?php echo __('store.new_user');?>
                     <a href="<?= base_url('store/register?url=checkout') ?>" class="text-decoration-none fw-semibold">
                        <?php echo __('store.create_a_new_account');?>
                     </a>
                  </p>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>


<!-- Register Modal -->

<div class="modal fade" id="linkModal" tabindex="-1" aria-labelledby="linkModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold" id="linktitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body px-4 py-3">
            <div id="linkdata"></div>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript">
   var country_id = '<?php echo $is_logged==true ? $shipping->country_id:""?>';
   var state_id = '<?php echo $is_logged==true ? $shipping->state_id:""?>';
   var allow_shipping = '<?=$allow_shipping?>';
   setTimeout(function(){
      if(country_id!=""){
         $("#country_id").val(country_id);
         $("#country_id").trigger('change')
      }
   },500);
   setTimeout(function(){
      if(state_id!="")
         $("#state_id").val(state_id)
   },1000)
   $(document).ready(function() {


      setTimeout(function(){
         window.tel_input = intlTelInput(document.querySelector("#"+(allow_shipping ? 'phone' :'phoneguest')), {
            initialCountry: "auto",
            utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
            separateDialCode:true,
            geoIpLookup: function(success, failure) {
               $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                success(countryCode);
             });
            },
         })
      },1000);


      $(document).on('click','.viewData',function(e){
         e.preventDefault();
         $("#linkModal").find('#linktitle').html('');
         $("#linkModal").find('#linkdata').html('');
         $("#linkModal").find('#linktitle').html($(this).text());
         $("#linkModal").find('#linkdata').html($(this).attr('data-value'));
         $("#linkModal").modal('show');
      });
   });


   function isNumberKey(evt) {
     var charCode = (evt.which) ? evt.which : event.keyCode;
       if (charCode != 46 && charCode != 45 && charCode > 31
       && (charCode < 48 || charCode > 57))
        return false;

     return true;
   }

   window.errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>', '<?= __('store.mobile_number_is_required') ?>'];
</script>