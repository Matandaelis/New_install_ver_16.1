<?php 
   $db =& get_instance(); 
   $googlerecaptcha = $db->Product_model->getSettings('googlerecaptcha');
   $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
   $sitekey = $googlerecaptcha['sitekey'] ?? '';
?>
<section class="py-5 bg-light">
   <div class="container">
      <div class="row">
         <div class="col-lg-4 mx-auto col-sm-12 bg-white rounded shadow p-4">
            <div id="main" class="site-content-block">
               <div class="main-content">
                  <div id="post-8" class="post-8 page type-page status-publish">
                     <div class="rtcl">
                        <div class="row" id="rtcl-user-login-wrapper">
                           <div class="col-md-12 rtcl-login-form-wrap login-1">
                              <h2 class="mb-4"><?= __('store.sign_in')?></h2>
                              <div class="form-group mb-3">
                                 <p class="rtcl-forgot-password">
                                    <?= __('store.new_user')?><a href="<?= base_url('store/register') ?>"> <?= __('store.create_an_account')?></a>
                                 </p>
                              </div>

                              <form id="aff-classified-login-form" class="form-horizontal" method="post" novalidate data-recaptcha-version="<?= $recaptcha_version ?>">
                                 <div class="form-group mb-3">
                                    <label class="control-label form-label">
                                       <?= __('store.username')?> <strong class="rtcl-required">*</strong>
                                    </label>
                                    <input type="text" name="username" autocomplete="username" class="form-control" required>
                                 </div>
                                 <div class="form-group mb-3">
                                    <label class="control-label form-label">
                                       <?= __('store.password')?> <strong class="rtcl-required">*</strong>
                                    </label>
                                    <input type="password" name="password" autocomplete="current-password" class="form-control" required>
                                 </div>

                                 <div class="form-group mb-3">
                                    <?php if (!empty($googlerecaptcha['client_login']) && !empty($sitekey)): ?>
                                       <?php if ($recaptcha_version === 'v2'): ?>
                                          <div class="captch">
                                             <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                             <div class="g-recaptcha" data-sitekey="<?= $sitekey ?>"></div>
                                             <input type="hidden" name="captch_response" id="captch_response">
                                          </div>
                                       <?php elseif ($recaptcha_version === 'v3'): ?>
                                          <input type="hidden" name="g-recaptcha-response" id="recaptcha_token">
                                          <script src="https://www.google.com/recaptcha/api.js?render=<?= $sitekey ?>"></script>
                                          <script>
                                             grecaptcha.ready(function() {
                                                grecaptcha.execute('<?= $sitekey ?>', {action: 'client_login'}).then(function(token) {
                                                   var input = document.getElementById('recaptcha_token');
                                                   if (input) {
                                                      input.value = token;
                                                   }
                                                });
                                             });
                                          </script>
                                       <?php endif; ?>
                                    <?php endif; ?>
                                 </div>

                                 <div class="form-group mb-3 d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary submitbtn me-3"><?= __('store.submit')?></button>
                                    <div>
                                       <input type="checkbox" name="rememberme" id="rtcl-rememberme" value="forever" class="me-1">
                                       <label for="rtcl-rememberme"> <?= __('store.remember_me')?> </label>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <p class="rtcl-forgot-password">
                                       <a href="<?= base_url('store/forgot') ?>"> <?= __('store.forgot_password')?>?</a>
                                    </p>
                                 </div>
                              </form>

                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>