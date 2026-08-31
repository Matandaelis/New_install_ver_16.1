<section aff-section="classified_contact_page"></section>
<script aff-template="classified_contact_page" type="text/html">
   <section class="section-padding-equal-70 bg-accent">
      <div class="container">
         <div class="contact-page-box-layout1 light-shadow-bg col-lg-10 col-sm-12 mx-auto">
            <div class="light-box-content">
               <div class="row">
                  <div class="col-md-6">
                     <div class="contact-info">
                        <h3 class="item-title"><?= __('store.information')?></h3>
                        <ul>
                           <li><i class="fas fa-paper-plane"></i>{{contact_address}}</li>
                           <li><i class="fas fa-phone-volume"></i>{{contact_number}}</li>
                           <li><i class="far fa-envelope"></i>{{contact_email}}</li>
                        </ul>
                        <hr>
                        <div class="pt-2">{{{contact_page_content}}}</div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="contact-form-box">
                        <h3 class="item-title"><?= __('store.send_us_a_message')?></h3>
                        <form id="aff-classified-contact-form">
                           <div class="form-group">
                              <input type="text" placeholder="<?= __('store.name')?>" class="form-control" name="name" required>
                              <div class="help-block with-errors"></div>
                           </div>
                           <div class="form-group">
                              <input type="email" placeholder="<?= __('store.email')?>" class="form-control" name="email" required>
                              <div class="help-block with-errors"></div>
                           </div>
                           <div class="form-group">
                              <input type="text" placeholder="<?= __('store.phone')?>" class="form-control" name="phone" required>
                              <div class="help-block with-errors"></div>
                           </div>
                           <div class="form-group">
                              <textarea placeholder="<?= __('store.message')?>" class="textarea form-control" name="message" rows="3" required></textarea>
                              <div class="help-block with-errors"></div>
                           </div>

                           <?php 
                              $db =& get_instance(); 
                              $googlerecaptcha = $db->Product_model->getSettings('googlerecaptcha');
                              $store_contact = $googlerecaptcha['store_contact'] ?? '0';
                              $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
                              $sitekey = $googlerecaptcha['sitekey'] ?? '';
                           ?>

                           <?php if ($recaptcha_version === 'v3'): ?>
                              <input type="hidden" name="g-recaptcha-response" id="recaptcha_token_store_contact">
                           <?php endif; ?>

                           <div class="form-group" id="recaptcha-container" style="display:none;"></div>

                           <div class="form-group">
                              <button type="submit" class="submit-btn"><?= __('store.submit')?></button>
                           </div>
                           <div class="form-response"></div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div class="item-review light-shadow-bg contact-map-area">
               {{{contact_page_map}}}
            </div>
         </div>
      </div>
   </section>

   <script>
      var recaptcha_config = {
         store_contact: "<?= $store_contact ?>",
         version: "<?= $recaptcha_version ?>",
         sitekey: "<?= $sitekey ?>"
      };

      if (recaptcha_config.store_contact === "1") {
         if (recaptcha_config.version === 'v2') {
            var script = document.createElement('script');
            script.src = "https://www.google.com/recaptcha/api.js";
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);

            var container = document.getElementById('recaptcha-container');
            container.style.display = 'block';
            container.innerHTML = '<div class="g-recaptcha" data-sitekey="' + recaptcha_config.sitekey + '"></div>';

         } else if (recaptcha_config.version === 'v3') {
            var script = document.createElement('script');
            script.src = "https://www.google.com/recaptcha/api.js?render=" + recaptcha_config.sitekey;
            script.onload = function () {
               grecaptcha.ready(function () {
                  grecaptcha.execute(recaptcha_config.sitekey, { action: 'store_contact' }).then(function (token) {
                     var input = document.getElementById('recaptcha_token_store_contact');
                     if (input) input.value = token;
                  });
               });
            };
            document.head.appendChild(script);
         }
      }
   </script>
</script>