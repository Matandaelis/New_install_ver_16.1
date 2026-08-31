<div class="page-intro top-content" id="user">
   <h2 class="page-title">User</h2>
   <p>In Affiliate, the user is the one who is allowed to access and manage the Affiliate records. These users can be defined under various track sales, clicks, and so many actions.</p>
   <p> User module in included all user activity related it's personal information data</p>
   <p>here user can add it's unique profile, update, delete, login, change password and get details</p>
</div>

<!-- start get Registration from -->
<div class="top-content" id="registration-form-details">
   <h3 class="page-title">Registration Form</h3>
   <p>The registration form can be structured dynamically by the admin at any time from settings. you can access that form's custom field from provided API.</p>
</div>
<div class="row">
   <div class="col-md-6">
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3>
         </div>
         <div class="card-body">
            <div class="mb-3">
               <span class="badge bg-success me-2">GET</span>
               <code class="text-break"><?=base_url();?>user/get_registration_form</code>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-6">
      <!-- Response Section -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Response</h3>
         </div>
         <div class="card-body">
            <pre class="response-view">{ "status": true, "data": [ { "type": "header", "label": "Firstname" }, { "type": "header", "label": "Lastname" }, { "type": "header", "label": "Email" }, { "type": "text", "label": "Mobile Phone", "placeholder": "Enter your mobile number", "className": "form-control", "name": "text-1621449816785", "mobile_validation": "true", "hide_on_registration": "true" }, { "type": "header", "label": "Username" }, { "type": "header", "label": "Password" }, { "type": "header", "label": "Confirm_password" }, { "type": "textarea", "required": "true", "label": "Text Area", "placeholder": "Your message", "className": "form-control", "name": "textarea-1623347397482" } ] }</pre>
         </div>
      </div>
   </div>
</div>
<!-- end Get Registration from -->


<div class="top-content" id="registration">
   <h3 class="page-title">Registration</h3>
   <p>Create new user with HTTP POST request.</br>During the creation you can set up all attributes describe in below</br>If given email and username doesn't exists it will be created</p>
</div>
<div class="row">
   <div class="col-md-6">
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3>
         </div>
         <div class="card-body">
            <div class="mb-3">
               <span class="badge bg-warning text-dark me-2">POST</span>
               <code class="text-break"><?=base_url();?>User/registarion</code>
            </div>
            <div class="table-responsive">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th>Parameter</th>
                  <th>Type</th>
                  <th>Required</th>
                  <th>Description</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>firstname</td>
                  <td><code>string</code></td>
                  <td><code>true</code></td>
                  <td>-</td>
               </tr>
               <tr>
                  <td>lastname</td>
                  <td><code>string</code></td>
                  <td><code>true</code></td>
                  <td>-</td>
               </tr>
               <tr>
                  <td>username</td>
                  <td><code>string</code> <code>number</code></td>
                  <td><code>true</code></td>
                  <td>your unique username</td>
               </tr>
               <tr>
                  <td>email</td>
                  <td><code>string</code></td>
                  <td><code>true</code></td>
                  <td>-</td>
               </tr>
               <tr>
                  <td>password</td>
                  <td><code>string</code> <code>number</code> <code>special character</code></td>
                  <td><code>true</code></td>
                  <td>-</td>
               </tr>
               <tr>
                  <td>cpassword</td>
                  <td><code>string</code> <code>number</code> <code>special character</code></td>
                  <td><code>true</code></td>
                  <td>password confirmation</td>
               </tr>
               <tr>
                  <td>device_type</td>
                  <td><code>number</code></td>
                  <td><code>true</code></td>
                  <td>(1 = android, 2 = ios)</td>
               </tr>
               <tr>
                  <td>device_token</td>
                  <td><code>string</code></td>
                  <td><code>true</code></td>
                  <td>-</td>
               </tr>

               <?php foreach ($registration_fields as $field) {
                  if($field['type'] != 'header' && !$field['hide_on_registration']) {
                     ?>
                     <tr>
                        <td>custom_<?= $field['name']; ?></td>
                        <td><code><?= ($field['mobile_validation']) ? 'Mobile Number' : $field['type']; ?></code></td>
                        <td><code><?= ($field['required']) ? 'true' : 'false'; ?></code></td>
                        <td><?= str_replace('Enter ', '', $field['placeholder']); ?></td>
                     </tr>
                     <?php
                  }
               } ?>

               <tr>
                  <td>terms</td>
                  <td><code>boolean</code></td>
                  <td><code>true</code></td>
                  <td>You must accept terms and conditions.</td>
               </tr>
            </tbody>
         </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-6">
      <!-- Response Section -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Response</h3>
         </div>
         <div class="card-body">
            <pre class="response-view">{"status":true,"message":"user registration successfully"}</pre>
         </div>
      </div>
   </div>
</div>

<!-- start login -->
<div class="top-content" id="login">
   <h3 class="page-title">Login</h3>
   <p>
      Login user with HTTP POST request.
   </p>
   <p>
      you can access all your affiliate system using your credentials. required to enter your username and password
   </p>
   <p>
   Authentication token are required as an authentication method with Affiliate apis. By using an Authentication token you authenticate access to the specific API. Without authentication access to the API is denied.</br>
   login api will generate a unique your user token for API authentication.
</p>
   <div class="alert alert-info">
      <i class="fa fa-info-circle"></i> <strong>Note:</strong> All URLs in the response (profile_avatar, country_flag) are dynamic and will automatically use your site's domain. The example shows sample URLs for reference only.
   </div>
</div>
<div class="row">
   <div class="col-md-6">
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3>
         </div>
         <div class="card-body">
            <div class="mb-3">
               <span class="badge bg-warning text-dark me-2">POST</span>
               <code class="text-break"><?=base_url();?>User/login</code>
            </div>
            <div class="table-responsive">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>Parameter</th>
                        <th>Type</th>
                        <th>Position</th>
                        <th>Description</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>username</td>
                        <td><code>string</code> <code>number</code></td>
                        <td><code>Body</code></td>
                        <td>you can add your unique username</td>
                     </tr>
                     <tr>
                        <td>password</td>
                        <td><code>string</code> <code>number</code> <code>special character</code></td>
                        <td><code>Body</code></td>
                        <td>-</td>
                     </tr>
                     <tr>
                        <td>device_type</td>
                        <td><code>number</code></td>
                        <td><code>Body</code></td>
                        <td>(1 = android, 2 = ios)</td>
                     </tr>
                     <tr>
                        <td>device_token</td>
                        <td><code>string</code></td>
                        <td><code>Body</code></td>
                        <td>-</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
</div>
<div class="col-md-6">
   <!-- Response Section -->
   <div class="card border-0 shadow-sm mb-3">
      <div class="card-header bg-success text-white">
         <h3 class="card-title mb-0">Response</h3>
      </div>
      <div class="card-body">
         <pre class="response-view">{"status":true,"message":"user login successfully","data":{"token":"raNdom-UniQe-toKEn-wilL-be-HeRe","user_status":"membership-status-active","firstname":"aff1000","lastname":"aff1000","email":"user1@gmail.com","PhoneNumber":"+1234567890","is_vendor":"0","role":"user","profile_avatar":"https:\/\/yourdomain.com\/assets\/images\/users\/avatar.png","country_id":"75","country_code":"US","country_name":"United States","country_flag":"https:\/\/yourdomain.com\/assets\/vertical\/assets\/images\/flags\/us.png","city":"New York","pincode":"10001","currency_symbol":"$","currency_code":"USD","enable_shorten_numbers":1}}</pre>
      </div>
   </div>
</div>
</div>
<!-- end login -->



<!-- start change password -->
<div class="top-content" id="change_password">
   <h3 class="page-title">Change Password</h3>
   <p>
      Change password with HTTP POST request.
   </p>
   <p>
      API token is required for the authentication of the calling program to the API.
   </p>
   <p>
      if you want to change your pass to enter old password and after add your new password and also enter confirm password.
   </p>
</div>
<div class="row">
   <div class="col-md-6">
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3>
            <br>
            <span class="text-warning">POST</span> : <?=base_url();?>User/change_password</p>
         </div>
         <div class="card-body">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Parameter</th>
                     <th>Type</th>
                     <th>Position</th>
                     <th>Description</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>old_pass</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>-</td>
                  </tr>
                  <tr>
                     <td>password</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>-</td>
                  </tr>
                  <tr>
                     <td>conf_password</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>-</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <div class="col-md-6">
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Response</h3>
         </div>
         <div class="card-body">
            <pre class="response-view">{ "status": true, "message": "password change successfully" }</pre>
         </div>
      </div>
   </div>
</div>
<!-- end change password -->


<!-- start my profile details -->
<div class="top-content" id="get_my_profile_details">
   <h3 class="page-title">My Profile Details</h3>
   <p>My profile details with HTTP POST request.</p>
   <p>API token is required for the authentication of the calling program to the API.</p>
   <p>there is no need any parameter. we are get your data using your verify token</p>
   <div class="alert alert-info">
      <i class="fa fa-info-circle"></i> <strong>Note:</strong> All URLs in the response (profile_image, country_flag) are dynamic and will automatically use your site's domain. The example shows sample URLs for reference only.
   </div>
</div>
<div class="row">
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3><br>
            <span class="text-success">GET</span> : <?=base_url();?>User/get_my_profile_details</p>
         </div>
         <div class="card-body">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Parameter</th>
                     <th>Type</th>
                     <th>Position</th>
                     <th>Description</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>Authorization</td>
                     <td><code>string</code></td>
                     <td><code>Header</code></td>
                     <td>compolsory pass this authentication token in your header also get from login api</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Response</h3>
         </div>
         <div class="card-body">
            <pre class="response-view">{"status":true,"message":"user details get successfully","data":{"user_status":"membership-status-refunded","firstname":"aff1000","lastname":"aff1000","email":"user1@gmail.com","PhoneNumber":"+1234567890","is_vendor":"0","profile_image":"https:\/\/yourdomain.com\/assets\/images\/users\/avatar.png","country_id":"75","country_code":"US","country_name":"United States","country_flag":"https:\/\/yourdomain.com\/assets\/vertical\/assets\/images\/flags\/us.png"}}</pre>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
</div>
<!-- end my profile details -->



<!-- start update my profile -->
<div class="top-content" id="update_my_profile">
   <h3 class="page-title">Update My Profile</h3>
   <p>Update my profile with HTTP POST request. with formate is Content-Type : multipart/form-data</p>
   <p>API token is required for the authentication of the calling program to the API.</p>
   <p>Here you can update your record and pass all parameter. uploads image set in file formate  also updated data return in your update response. country_id data get using get countrie list api.</p>
   <div class="alert alert-info">
      <i class="fa fa-info-circle"></i> <strong>Note:</strong> All URLs in the response (profile_image, country_flag) are dynamic and will automatically use your site's domain. The example shows sample URLs for reference only.
   </div>
</div>
<div class="row">
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3><br>
            <span class="text-warning">POST</span> : <?=base_url();?>User/update_my_profile</p>
         </div>
         <div class="card-body">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Parameter</th>
                     <th>Type</th>
                     <th>Required</th>
                     <th>Description</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>firstname</td>
                     <td><code>string</code></td>
                     <td><code>true</code></td>
                     <td>-</td>
                  </tr>
                  <tr>
                     <td>lastname</td>
                     <td><code>string</code></td>
                     <td><code>true</code></td>
                     <td>-</td>
                  </tr>
                  <tr>
                     <td>username</td>
                     <td><code>string</code> <code>number</code></td>
                     <td><code>true</code></td>
                     <td>your unique username</td>
                  </tr>
                  <tr>
                     <td>email</td>
                     <td><code>string</code></td>
                     <td><code>true</code></td>
                     <td>-</td>
                  </tr>
                  <tr>
                     <td>password</td>
                     <td><code>string</code> <code>number</code> <code>special character</code></td>
                     <td><code>true</code></td>
                     <td>-</td>
                  </tr>
                  <tr>
                     <td>cpassword</td>
                     <td><code>string</code> <code>number</code> <code>special character</code></td>
                     <td><code>true</code></td>
                     <td>password confirmation</td>
                  </tr>
                  <tr>
                     <td>device_type</td>
                     <td><code>number</code></td>
                     <td><code>true</code></td>
                     <td>(1 = android, 2 = ios)</td>
                  </tr>
                  <tr>
                     <td>device_token</td>
                     <td><code>string</code></td>
                     <td><code>true</code></td>
                     <td>-</td>
                  </tr>
                  <tr>
                     <td>avatar</td>
                     <td><code>string</code> <code>File</code></td>
                     <td><code>true</code></td>
                     <td>-</td>
                  </tr>

                  <?php foreach ($registration_fields as $field) {
                     if($field['type'] != 'header') {
                        ?>
                        <tr>
                           <td>custom_<?= $field['name']; ?></td>
                           <td><code><?= ($field['mobile_validation']) ? 'Mobile Number' : $field['type']; ?></code></td>
                           <td><code><?= ($field['required']) ? 'true' : 'false'; ?></code></td>
                           <td><?= str_replace('Enter ', '', $field['placeholder']); ?></td>
                        </tr>
                        <?php
                     }
                  } ?>
               </tbody>
            </table>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Response</h3>
         </div>
         <div class="card-body">
            <pre class="response-view">{"status":true,"message":"user details update successfully","data":{"user_status":"membership-status-active","firstname":"aff1","lastname":"aff1","email":"user11@natanphp.com","PhoneNumber":"+1 201-555-0124","is_vendor":"0","profile_image":"https:\/\/yourdomain.com\/assets\/images\/users\/avatar.png","country_id":"75","country_code":"US","country_name":"United States","country_flag":"https:\/\/yourdomain.com\/assets\/vertical\/assets\/images\/flags\/us.png"}}</pre>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
</div>
<!-- end update my profile -->
