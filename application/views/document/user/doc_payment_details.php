<div class="row">
   <div class="col-md-12">
      <div class="page-intro" id="get_user_payments_details">
         <h2 class="page-title">My Payments Details</h2>
         <p>
            Manage your payment details using HTTP requests. This single endpoint handles multiple payment operations.
         </p>
         <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> <strong>Important:</strong> The same endpoint <code>user/payment_details</code> handles 4 different operations:
            <ul class="mb-0 mt-2">
               <li><strong>GET</strong> - Retrieve all payment information</li>
               <li><strong>POST with add_paypal</strong> - Add/Update PayPal account</li>
               <li><strong>POST with add_custom_gateway</strong> - Add/Update custom gateway (Stripe, Razorpay, etc.)</li>
               <li><strong>POST with add_primary_payment</strong> - Set primary payment method</li>
               <li><strong>POST with bank details</strong> - Add/Update bank account details</li>
            </ul>
         </div>
         <p>
            An API token is required for authentication. This ensures the secure exchange of information.
         </p>
      </div>
      <!-- Start bank details -->
      <div class="top-content" id="post_bank_account">
         <h3 class="page-title">1. Add/Update Bank Account</h3>
         <p>
            Update your Bank Account Details via an HTTP POST request.
         </p>
         <p>
            If <code>payment_country</code> is required (when bank transfer mode is "specific"), you must also provide the country-specific field (e.g., routing_number for US, ifsc_code for IN).
         </p>
         <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> <strong>Note:</strong> Country-specific fields are dynamic. Use <code>get_payment_details</code> API first to see which countries are available and their required fields.
         </div>
      </div>
   </div>
</div>

<!-- Start bank details -->
<div class="row">
   <div class="col-md-6">
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3><br>
            <span class="text-warning">POST</span> : <?=base_url();?>user/payment_details</p>
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
                     <td>compulsory pass this authentication token in your header also get from login api</td>
                  </tr>
                  <tr>
                     <td>payment_bank_name</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>compulsory pass bank name</td>
                  </tr>
                  <tr>
                     <td>payment_account_number</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>compulsory pass account number</td>
                  </tr>
                  <tr>
                     <td>payment_account_name</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>compulsory pass account name</td>
                  </tr>
                  <tr>
                     <td>payment_id</td>
                     <td><code>integer</code></td>
                     <td><code>Body</code></td>
                     <td>0 for new record, existing ID for update</td>
                  </tr>
                  <tr>
                     <td>payment_country</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>required if bank transfer mode is specific (e.g., US, IN, GB, AU, CA, DE, CN, SG, HK, NZ)</td>
                  </tr>
                  <tr>
                     <td>payment_routing_number</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>required if country is US</td>
                  </tr>
                  <tr>
                     <td>payment_ifsc_code</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>required if country is IN (India)</td>
                  </tr>
                  <tr>
                     <td>payment_sort_code</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>required if country is GB (UK)</td>
                  </tr>
                  <tr>
                     <td>Other country codes</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>AU: bsb_number, CA: transit_institution_number, DE: iban_bic, CN: cnaps_code, SG: swift_code, HK: clearing_code, NZ: bank_branch_number</td>
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
            <pre class="response-view">{"status":true,"message":"Payment details added successfully","errors":[]}</pre>
         </div>
      </div>
   </div>
</div>
<!-- End bank details -->

<!-- start Paypal Account -->
<div class="top-content" id="post_paypal_account">
   <h3 class="page-title">2. Add/Update PayPal Account</h3>
   <p>
      Save or update your PayPal email address for receiving payments.
   </p>
   <div class="alert alert-info">
      <i class="fa fa-info-circle"></i> <strong>Key Parameter:</strong> Include <code>add_paypal=1</code> in the POST body to trigger PayPal update operation.
   </div>
</div>
<div class="row">
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3><br>
            <span class="text-warning">POST</span> : <?=base_url();?>user/payment_details</p>
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
                  <tr>
                     <td>add_paypal</td>
                     <td><code>integer</code></td>
                     <td><code>Body</code></td>
                     <td>Set to 1 to trigger PayPal operation</td>
                  </tr>
                  <tr>
                     <td>id</td>
                     <td><code>integer</code></td>
                     <td><code>Body</code></td>
                     <td>0 for new PayPal account, existing ID for update</td>
                  </tr>
                  <tr>
                     <td>paypal_email</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>Valid PayPal email address (required)</td>
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
            <pre class="response-view">{"status":true,"message":"Paypal Account saved successfully","errors":[]}</pre>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
</div>
<!-- end Paypal Account -->

<!-- start Custom Gateway -->
<div class="top-content" id="post_custom_gateway">
   <h3 class="page-title">2b. Add/Update Custom Gateway Details</h3>
   <p>
      Save payment details for custom gateways like Stripe, Razorpay, Skrill, etc.
   </p>
   <div class="alert alert-info">
      <i class="fa fa-info-circle"></i> <strong>Key Parameter:</strong> Include <code>add_custom_gateway=1</code> and <code>gateway_code</code> to specify which gateway you're updating.
   </div>
   <div class="alert alert-warning">
      <i class="fa fa-exclamation-triangle"></i> <strong>Dynamic Fields:</strong> Field names must be prefixed with gateway code. Example: for Stripe with code "stripe", use <code>stripe_account_holder</code>, <code>stripe_account_number</code>, etc.
   </div>
</div>
<div class="row">
   <div class="col-md-6">
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3><br>
            <span class="text-warning">POST</span> : <?=base_url();?>user/payment_details</p>
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
                     <td>Authentication token from login API</td>
                  </tr>
                  <tr>
                     <td>add_custom_gateway</td>
                     <td><code>integer</code></td>
                     <td><code>Body</code></td>
                     <td>Set to 1 to trigger custom gateway operation</td>
                  </tr>
                  <tr>
                     <td>gateway_code</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>Gateway identifier (e.g., stripe, razorpay, skrill)</td>
                  </tr>
                  <tr>
                     <td>{gateway_code}_{field_name}</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>Dynamic fields prefixed with gateway code (e.g., stripe_account_holder, razorpay_upi_id)</td>
                  </tr>
               </tbody>
            </table>
            <div class="alert alert-secondary mt-3">
               <strong>Example for Stripe:</strong><br>
               <code>add_custom_gateway=1</code><br>
               <code>gateway_code=stripe</code><br>
               <code>stripe_account_holder=John Doe</code><br>
               <code>stripe_account_number=4242424242424242</code>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-6">
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Response</h3>
         </div>
         <div class="card-body">
            <pre class="response-view">{"status":true,"message":"Payment details saved successfully","errors":[]}</pre>
         </div>
      </div>
   </div>
</div>
<!-- end Custom Gateway -->

<!-- start Primary Payment method -->
<div class="top-content" id="post_primary_method">
   <h3 class="page-title">3. Set Primary Payment Method</h3>
   <p>
      Set your default payment method for receiving withdrawals (bank_transfer, paypal, stripe, etc.).
   </p>
   <div class="alert alert-info">
      <i class="fa fa-info-circle"></i> <strong>Key Parameter:</strong> Include <code>add_primary_payment=1</code> in the POST body to trigger this operation.
   </div>
</div>
<div class="row">
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3><br>
            <span class="text-warning">POST</span> : <?=base_url();?>user/payment_details</p>
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
                  <tr>
                     <td>add_primary_payment</td>
                     <td><code>integer</code></td>
                     <td><code>Body</code></td>
                     <td>Set to 1 to trigger primary payment method operation</td>
                  </tr>
                  <tr>
                     <td>primary_payment_method</td>
                     <td><code>string</code></td>
                     <td><code>Body</code></td>
                     <td>Gateway code: bank_transfer, paypal, stripe, razorpay, etc. (required)</td>
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
            <pre class="response-view">{"status":true,"message":"Primary payment method saved successfully","errors":[]}</pre>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
</div>
<!-- end Primary Payment method -->

<!-- start Get Payment Detail -->
<div class="top-content" id="get_payment_information">
   <h3 class="page-title">4. Get Payment Information (GET)</h3>
   <p>
      Retrieve all saved payment information using an HTTP GET request.
   </p>
   <div class="alert alert-success">
      <i class="fa fa-check-circle"></i> <strong>Response Includes:</strong>
      <ul class="mb-0 mt-2">
         <li><code>payment_methods</code> - All enabled payment gateways and their configuration</li>
         <li><code>available_countries</code> - Allowed countries for bank transfer (if specific mode)</li>
         <li><code>primary_payment_method</code> - User's default payment method</li>
         <li><code>paymentlist</code> - Saved bank details with all country-specific fields</li>
         <li><code>paypalaccounts</code> - Saved PayPal email</li>
         <li><code>custom_gateway_settings</code> - Saved details for Stripe, Razorpay, etc.</li>
      </ul>
   </div>
</div>
<div class="row">
   <div class="col-md-6">
      <!-- TABLE HOVER -->
      <div class="card border-0 shadow-sm mb-3">
         <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Request</h3><br>
            <span class="text-success">GET</span> : <?=base_url();?>user/get_payment_details</p>
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
                     <td>Compulsory: Pass this authentication token in your header. Get it from the login API.</td>
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
            <pre class="response-view">{"status":true,"data":{"payment_methods":{"bank_transfer":{"status":"1","bank_transfer_mode":"specific","bank_transfer_countries":{"US":"1","IN":"1","GB":"1"}},"paypal":{"status":"1"}},"available_countries":["US","IN","GB"],"primary_payment_method":"bank_transfer","paymentlist":{"payment_id":"1","payment_bank_name":"Bank of America","payment_account_number":"123456789","payment_account_name":"John Doe","payment_country":"US","payment_routing_number":"123456789","payment_ifsc_code":"","payment_sort_code":"","payment_bsb_number":"","payment_transit_institution_number":"","payment_iban_bic":"","payment_cnaps_code":"","payment_swift_code":"","payment_clearing_code":"","payment_bank_branch_number":"","paypalaccounts":{"paypal_email":"user@example.com","id":"1"}},"paypalaccounts":{"paypal_email":"user@example.com","id":"1"},"custom_gateway_settings":{"stripe":{"account_holder":"John Doe"},"razorpay":{"upi_id":"user@upi"}}}}</pre>
         </div>
      </div>
      <!-- END TABLE HOVER -->
   </div>
</div>
<!-- end Get Payment Detail -->

