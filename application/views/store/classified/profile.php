<section class="about-wrap-layout1 py-5 bg-light">
      <div class="container">
         <div class="col-lg-10 col-sm-12 mx-auto">
            <div class="row">
             <div class="col-lg-12">
               <div class="about-box-layout1 bg-primary text-white p-4 rounded-4 mb-4 shadow">
                  <h2 class="item-title text-center fw-bold mb-0">
                     <i class="fas fa-user-circle me-3"></i><?= __('store.profile') ?>
                  </h2>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="card border-0 shadow rounded-4">
                  <div class="card-body p-4">
                     <form id="frm_profile" method="post" action="<?php echo base_url('classified/profile') ?>" enctype="multipart/form-data">
                        <div class="row">
                           <div class="col-md-12 text-center">
                              <div class="form-group mb-4">
                                 <?php 
                                 $avatar = ($userDetails['avatar'] != '') ? base_url('assets/images/users/'.$userDetails['avatar']) : base_url('assets/store/default/img/blog1.png') ; 
                                 ?>
                                 <div class="position-relative d-inline-block mb-3">
                                    <img id="blah" src="<?= $avatar ?>" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;" alt="<?= __('store.profile') ?>">
                                    <div class="position-absolute bottom-0 end-0">
                                       <span class="badge bg-primary rounded-circle p-2">
                                          <i class="fas fa-camera"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="btn btn-outline-primary rounded-pill fileUpload">
                                    <span><i class="far fa-image me-2"></i><?= __('store.choose_file') ?></span>
                                    <input id="uploadBtn" name="avatar" class="d-none" type="file">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="mb-3">
                                    <label for="fiest_name" class="form-label fw-semibold">
                                       <i class="fas fa-user me-2 text-primary"></i><?= __('store.firstname') ?>
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="fiest_name" placeholder="<?= __('store.firstname')?>" value="<?php echo $userDetails['firstname']; ?>" name="firstname">
                                    <?php if(isset($this->session->flashdata('error')['firstname'])) { ?>
                                       <div class="text-danger mt-1">
                                          <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['firstname'] ?>
                                       </div>
                                    <?php } ?>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-3">
                                   <label for="last_name" class="form-label fw-semibold">
                                      <i class="fas fa-user me-2 text-primary"></i><?= __('store.lastname') ?>
                                   </label>
                                   <input type="text" class="form-control form-control-lg rounded-3" id="last_name" placeholder="<?= __('store.lastname') ?>" value="<?php echo $userDetails['lastname']; ?>" name="lastname">
                                   <?php if(isset($this->session->flashdata('error')['lastname'])) { ?>
                                    <div class="text-danger mt-1">
                                       <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['lastname'] ?>
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="mb-3">
                                 <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-success"></i><?= __('store.email') ?>
                                 </label>
                                 <input type="email" class="form-control form-control-lg rounded-3" id="email" placeholder="<?= __('store.email') ?>"  value="<?php echo $userDetails['email']; ?>"  name="email">
                                 <?php if(isset($this->session->flashdata('error')['email'])) { ?>
                                    <div class="text-danger mt-1">
                                       <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['email'] ?>
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="mb-3">
                                 <label for="PhoneNumber" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-2 text-success"></i><?= __('store.phone') ?>
                                 </label>
                                 <input type="hidden" name='PhoneNumberInput' id="phonenumber-input" value="" class="form-control" placeholder="<?= __('store.phone_number') ?>"  >
                                 <input type="text" class="form-control form-control-lg rounded-3" id="PhoneNumber" placeholder="<?= __('store.phone') ?>" onkeypress="return isNumberKey(event);" value="<?php echo $userDetails['PhoneNumber']; ?>" name="PhoneNumber">
                                 <?php if(isset($this->session->flashdata('error')['PhoneNumber'])) { ?>
                                    <div class="text-danger mt-1">
                                       <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['PhoneNumber'] ?>
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="mb-3">
                                 <label class="form-label fw-semibold">
                                    <i class="fas fa-globe me-2 text-info"></i><?= __('store.country') ?>
                                 </label>
                                 <select name="Country" class="form-select form-select-lg rounded-3" id="Country" >
                                    <option value="" selected="selected" ><?= __('store.select_country') ?></option>
                                    <?php foreach($country as $countries): ?>
                                       <option <?php  if( $userDetails['country'] == $countries->id) { ?> selected <?php }?> value="<?php echo $countries->id; ?>"><?php echo $countries->name; ?></option>
                                    <?php endforeach; ?> 
                                 </select>
                                 <?php if(isset($this->session->flashdata('error')['Country'])) { ?>
                                    <div class="text-danger mt-1">
                                       <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['Country'] ?>
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="mb-3">
                                <label for="StateProvince" class="form-label fw-semibold">
                                   <i class="fas fa-map-pin me-2 text-info"></i><?= __('store.state') ?>
                                </label>
                                <select class="form-select form-select-lg rounded-3" aria-label="Default select example" name="StateProvince" id="StateProvince">
                                </select>
                                <?php if(isset($this->session->flashdata('error')['StateProvince'])) { ?>
                                 <div class="text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['StateProvince'] ?>
                                 </div>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="mb-3">
                              <label for="zip_code" class="form-label fw-semibold">
                                 <i class="fas fa-mail-bulk me-2 text-warning"></i><?= __('store.postal_code') ?>
                              </label>
                              <input type="text" class="form-control form-control-lg rounded-3" id="zip_code" placeholder="<?= __('store.postal_code') ?>" name="Zip" value="<?php echo $userDetails['zip']; ?>">
                              <?php if(isset($this->session->flashdata('error')['Zip'])) { ?>
                                 <div class="text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['Zip'] ?>
                                 </div>
                              <?php } ?>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="mb-3">
                            <label for="city" class="form-label fw-semibold">
                               <i class="fas fa-city me-2 text-warning"></i><?= __('store.city') ?>
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3" id="city" placeholder="<?= __('store.city') ?>"  value="<?php echo $userDetails['city'];?>" name="City" >
                            <?php if(isset($this->session->flashdata('error')['City'])) { ?>
                              <div class="text-danger mt-1">
                                 <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['City'] ?>
                              </div>
                           <?php } ?>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                   <div class="col-md-6">
                     <div class="mb-3">
                       <label for="password" class="form-label fw-semibold">
                          <i class="fas fa-lock me-2 text-danger"></i><?= __('store.password') ?>
                       </label>
                       <input type="password" class="form-control form-control-lg rounded-3" id="password" placeholder="<?= __('store.password') ?>" name="new_password">
                       <?php if(isset($this->session->flashdata('error')['new_password'])) { ?>
                        <div class="text-danger mt-1">
                           <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['new_password'] ?>
                        </div>
                     <?php } ?>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="cpassword" class="form-label fw-semibold">
                        <i class="fas fa-lock me-2 text-danger"></i><?= __('store.confirm_password') ?>
                     </label>
                     <input type="password" class="form-control form-control-lg rounded-3" id="cpassword" placeholder="<?= __('store.confirm_password') ?>" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="mb-3">
                     <label class="form-label fw-semibold">
                        <i class="fas fa-home me-2 text-secondary"></i><?= __('store.full_address') ?> <span class="text-danger">*</span>
                     </label>
                     <textarea class="form-control form-control-lg rounded-3" rows="4" name="twaddress" placeholder="Enter your complete address"><?= isset($userDetails) ? $userDetails['twaddress'] : '' ?></textarea>
                     <?php if(isset($this->session->flashdata('error')['twaddress'])) { ?>
                        <div class="text-danger mt-1">
                           <i class="fas fa-exclamation-circle me-1"></i><?= $this->session->flashdata('error')['twaddress'] ?>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <div class="row">
              <div class="col-12 text-end">
               <div class="mt-4">
                 <button type="submit" class="btn btn-primary btn-lg px-4 rounded-pill">
                    <i class="fas fa-save me-2"></i><?= __('store.update')?>
                 </button>
              </div>
           </div>
        </div>
      </form>
   </div>
</div>

<div class="col-lg-12 mt-5">
   <div class="about-box-layout1 bg-success text-white p-4 rounded-4 mb-4 shadow">
      <h2 class="item-title text-center fw-bold mb-0">
         <i class="fas fa-shipping-fast me-3"></i><?= __('store.shipping_details') ?>
      </h2>
   </div>
</div>
<div class="col-lg-12">
   <div class="card border-0 shadow rounded-4">
      <div class="card-body p-4">
         <form id="frm_shipping_address" action="<?php echo base_url('classified/shipping');?>" method="post">
            <div class="row">
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="address" class="form-label fw-semibold">
                        <i class="fas fa-map-marker-alt me-2 text-success"></i><?= __('store.address') ?>
                     </label>
                     <input type="text" class="form-control form-control-lg rounded-3" name="address" id="address" placeholder="<?= __('store.address') ?>" value="<?= isset($shipping) ? $shipping['address'] : '' ?>">
                     <?php if($errors && isset($errors['address'])) { ?>
                        <div class="text-danger mt-1">
                           <i class="fas fa-exclamation-circle me-1"></i><?php echo $errors['address'] ?>
                        </div>
                     <?php } ?>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3">
                    <label for="country_id" class="form-label fw-semibold">
                       <i class="fas fa-globe me-2 text-success"></i><?= __('store.country') ?>
                    </label>
                    <?php $selected =  isset($shipping) ? $shipping['country_id'] : '' ?>
                    <select class="form-select form-select-lg rounded-3" name="country" id="country_id">
                     <?php foreach ($country as $key => $value) { ?>
                        <option <?= $selected == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
                     <?php } ?>
                  </select>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
               <div class="mb-3">
                 <label for="state_id" class="form-label fw-semibold">
                    <i class="fas fa-map-pin me-2 text-success"></i><?= __('store.state') ?>
                 </label>
                 <select class="form-select form-select-lg rounded-3" aria-label="Default select example" name="state" id="state_id">
                 </select>
              </div>
           </div>
           <div class="col-md-6">
            <div class="mb-3">
               <label for="city" class="form-label fw-semibold">
                  <i class="fas fa-city me-2 text-success"></i><?= __('store.city') ?>
               </label>
               <input class="form-control form-control-lg rounded-3" name="city" type="text" placeholder="Enter city" value="<?= isset($shipping) ? $shipping['city'] : '' ?>">
               <?php if($errors && isset($errors['city'])) { ?>
                  <div class="text-danger mt-1">
                     <i class="fas fa-exclamation-circle me-1"></i><?php echo $errors['city'] ?>
                  </div>
               <?php } ?>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <div class="mb-3">
               <label for="zip_code" class="form-label fw-semibold">
                  <i class="fas fa-mail-bulk me-2 text-success"></i><?= __('store.postal_code') ?>
               </label>
               <input class="form-control form-control-lg rounded-3" name="zip_code" type="text" placeholder="Enter postal code" value="<?= isset($shipping) ? $shipping['zip_code'] : '' ?>">
               <?php if($errors && isset($errors['zip_code'])) { ?>
                  <div class="text-danger mt-1">
                     <i class="fas fa-exclamation-circle me-1"></i><?php echo $errors['zip_code'] ?>
                  </div>
               <?php } ?>
            </div>
         </div>
         <div class="col-md-6">
            <div class="mb-3">
               <label for="sphone" class="form-label fw-semibold">
                  <i class="fas fa-phone me-2 text-success"></i><?= __('store.phone') ?>
               </label>
               <input type="hidden" id="phone-input" name='PhoneNumberInput' value="" class="form-control" placeholder="<?= __('store.phone_number') ?>" />
               <input onkeypress="return isNumberKey(event);" id="phone" class="form-control form-control-lg rounded-3" type="text" name="phone" placeholder="Enter phone number" value="<?= isset($shipping) ? $shipping['phone'] : '' ?>">
              <?php if($errors && isset($errors['phone'])) { ?>
               <div class="text-danger mt-1">
                  <i class="fas fa-exclamation-circle me-1"></i><?php echo $errors['phone'] ?>
               </div>
               <?php } ?>
            </div>
            <input type="hidden" name="shipping_address" value="1">  
         </div>
      </div>
      <div class="row">
         <div class="col-12 text-end">
            <div class="mt-4">
              <button type="submit" class="btn btn-success btn-lg px-4 rounded-pill">
                 <i class="fas fa-shipping-fast me-2"></i><?= __('store.update')?>
              </button>
           </div>
        </div>
      </div>
   </form>
</div>
</div>
</div>
</div>
</div>
</section>

<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css?v='.av()) ?>">
<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>

<script type="text/javascript">
   function readURL(input) {
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         
         reader.onload = function(e) {
            jQuery('#blah').attr('src', e.target.result);
         }
         
         reader.readAsDataURL(input.files[0]);
      }
   }
   
   $(document).on('click', '.fileUpload span', function(){
      $('#uploadBtn').trigger('click');
   });

   document.getElementById("uploadBtn").onchange = function () {
      readURL(this);
   };
   
   var selected_state = '<?= isset($shipping) ? $shipping['state_id'] : '' ?>';
   var state = '<?= isset($userdetails) ? $userdetails['state'] : '' ?>';
   
   $(document).delegate('#country_id',"change",function(){
      $this = $(this);
      $.ajax({
         url:'<?= base_url('classified/getState') ?>',
         type:'POST',
         dataType:'json',
         data:{id:$this.val()},
         success:function(json){
            var html = '';
            $.each(json['states'], function(i,j){
               var s = '';
               if(selected_state && selected_state == j['id']){
                  s = 'selected';selected_state = 0;
               }
               html += "<option "+ s +" value='"+ j['id'] +"'>"+ j['name'] +"</option>";
            })
            $('[name="state"]').html(html);
         },
      })
   })
   
   $(document).delegate('#Country',"change",function(){
      $this = $(this);
      $.ajax({
         url:'<?= base_url('classified/getState') ?>',
         type:'POST',
         dataType:'json',
         data:{id:$this.val()},
         success:function(json){
            var html = '';
            $.each(json['states'], function(i,j){
               var s = '';
               if(state && state == j['id']){
                  s = 'selected';state = 0;
               }
               html += "<option "+ s +" value='"+ j['id'] +"'>"+ j['name'] +"</option>";
            })
            $('#StateProvince').html(html);
         },
      })
   })

   $('[name="country"],#Country').trigger("change");

   var tel_input = intlTelInput(document.querySelector("#PhoneNumber"), {
      initialCountry: "auto",
      utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
      separateDialCode:true,
      geoIpLookup: function(success, failure) {
         $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "US";
            success(countryCode);
         });
      },
   });

   // Set phone number after initialization
   $(document).ready(function() {
      var phoneValue = $("#PhoneNumber").val();
      if(phoneValue && phoneValue.trim() !== '') {
         tel_input.setNumber(phoneValue);
      }
   });

   window.errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>', '<?= __('store.mobile_number_is_required') ?>'];

   function isNumberKey(evt) {
     var charCode = (evt.which) ? evt.which : event.keyCode;
       if (charCode != 46 && charCode != 45 && charCode > 31
       && (charCode < 48 || charCode > 57))
        return false;

     return true;
   }

   $("#frm_profile").submit(function(){
      var errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>'];
      var is_valid = false;
      var errorInnerHTML = '';
      
      if ($("#PhoneNumber").val().trim()) {
         if (tel_input.isValidNumber()) {
            is_valid = true;
            $("#phonenumber-input").val("+"+tel_input.getSelectedCountryData().dialCode +' '+ $("#PhoneNumber").val().trim());
         } else {
            var errorCode = tel_input.getValidationError();
            errorInnerHTML = errorMap[errorCode];
         }
      } else {
         errorInnerHTML = '<?= __('store.mobile_number_is_required') ?>';
      }
      
      $("#PhoneNumber").removeClass("is-invalid");
      $("#frm_profile .text-danger").remove();

      if(!is_valid){
         $("#PhoneNumber").addClass("is-invalid");
         $("#PhoneNumber").after("<div class='text-danger mt-1'><i class='fas fa-exclamation-circle me-1'></i>"+ errorInnerHTML +"</div>");
         return false;
      }
   });

   var tel_input_shipping = intlTelInput(document.querySelector("#phone"), {
          initialCountry: "auto",
          utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
          separateDialCode:true,
          geoIpLookup: function(success, failure) {
           $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            success(countryCode);
         });
      },
   });

   // Set shipping phone number after initialization
   $(document).ready(function() {
      var shippingPhoneValue = $("#phone").val();
      if(shippingPhoneValue && shippingPhoneValue.trim() !== '') {
         tel_input_shipping.setNumber(shippingPhoneValue);
      }
   });

   $("#frm_shipping_address").submit(function(e){
      var hasErrors = false;
      var errorMessage = '';
      
      // Remove previous errors
      $("#frm_shipping_address .text-danger").remove();
      $("#frm_shipping_address .is-invalid").removeClass("is-invalid");
      
      // Validate address
      if (!$("#address").val().trim()) {
         $("#address").addClass("is-invalid");
         $("#address").after("<div class='text-danger mt-1'><i class='fas fa-exclamation-circle me-1'></i>Address is required</div>");
         hasErrors = true;
      }
      
      // Validate country
      if (!$("#country_id").val()) {
         $("#country_id").addClass("is-invalid");
         $("#country_id").after("<div class='text-danger mt-1'><i class='fas fa-exclamation-circle me-1'></i>Country is required</div>");
         hasErrors = true;
      }
      
      // Validate state
      if (!$("#state_id").val()) {
         $("#state_id").addClass("is-invalid");
         $("#state_id").after("<div class='text-danger mt-1'><i class='fas fa-exclamation-circle me-1'></i>State is required</div>");
         hasErrors = true;
      }
      
      // Validate city
      if (!$("input[name='city']").val().trim()) {
         $("input[name='city']").addClass("is-invalid");
         $("input[name='city']").after("<div class='text-danger mt-1'><i class='fas fa-exclamation-circle me-1'></i>City is required</div>");
         hasErrors = true;
      }
      
      // Validate zip code
      if (!$("input[name='zip_code']").val().trim()) {
         $("input[name='zip_code']").addClass("is-invalid");
         $("input[name='zip_code']").after("<div class='text-danger mt-1'><i class='fas fa-exclamation-circle me-1'></i>Postal code is required</div>");
         hasErrors = true;
      }
      
      // Validate phone
      var phoneValid = false;
      var phoneErrorMessage = '';
      var errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>'];
      
      if ($("#phone").val().trim()) {
         if (tel_input_shipping.isValidNumber()) {
            phoneValid = true;
            $("#phone-input").val("+"+tel_input_shipping.getSelectedCountryData().dialCode +' '+ $("#phone").val().trim());
         } else {
            var errorCode = tel_input_shipping.getValidationError();
            phoneErrorMessage = errorMap[errorCode];
         }
      } else {
         phoneErrorMessage = '<?= __('store.mobile_number_is_required') ?>';
      }
      
      if (!phoneValid) {
         $("#phone").addClass("is-invalid");
         $("#phone").after("<div class='text-danger mt-1'><i class='fas fa-exclamation-circle me-1'></i>"+ phoneErrorMessage +"</div>");
         hasErrors = true;
      }
      
      // If there are errors, prevent form submission
      if (hasErrors) {
         e.preventDefault();
         // Scroll to first error
         $('html, body').animate({
            scrollTop: $(".is-invalid").first().offset().top - 100
         }, 500);
         return false;
      }
   });
</script>