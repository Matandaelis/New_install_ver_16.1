<div class="top-content" id="admin_wallet">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-wallet2 me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Wallet Overview</h3>
   </div>
   <p class="text-muted mb-4">Retrieve the admin wallet overview including balance, sale totals, click totals, commissions across all channels, and recent transactions.</p>

   <!-- Wallet Endpoint -->
   <div class="mb-4">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-piggy-bank text-primary"></i>
         Wallet
      </h5>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/wallet</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{"status":true,"data":{"admin_balance":"12500.00","sale_localstore_total":"8500.00","sale_localstore_vendor_total":"3200.00","order_external_total":"4500.00","click_action_total":120,"click_action_commission":"360.00","click_localstore_total":450,"click_integration_total":280,"click_form_total":95,"click_localstore_commission":"225.00","click_integration_commission":"140.00","click_form_commission":"47.50","transactions":[{"id":"234","user_id":"88","amount":"150.00","type":"sale","status":"paid","created_date":"2025-06-11 14:30:00","firstname":"John","lastname":"Doe"},{"id":"233","user_id":"65","amount":"75.00","type":"click","status":"unpaid","created_date":"2025-06-10 09:15:00","firstname":"Jane","lastname":"Smith"}],"currency_symbol":"$","currency_code":"USD","enable_shorten_numbers":1}}</code></pre>
         </div>
      </div>

      <!-- Response Fields -->
      <div class="card border-0 shadow-sm rounded-3">
         <div class="card-header bg-light border-0 py-3">
            <h6 class="card-title mb-0 d-flex align-items-center gap-2">
               <i class="bi bi-list-ul text-primary"></i>
               Response Fields
            </h6>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Field</th><th>Type</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td><code>admin_balance</code></td><td>string</td><td>Total admin balance</td></tr>
                     <tr><td><code>sale_localstore_total</code></td><td>string</td><td>Total local store sales amount</td></tr>
                     <tr><td><code>sale_localstore_vendor_total</code></td><td>string</td><td>Total local store vendor sales amount</td></tr>
                     <tr><td><code>order_external_total</code></td><td>string</td><td>Total external order amount (integration campaigns)</td></tr>
                     <tr><td><code>click_action_total</code></td><td>integer</td><td>Total action/lead clicks</td></tr>
                     <tr><td><code>click_action_commission</code></td><td>string</td><td>Total commission from action clicks</td></tr>
                     <tr><td><code>click_localstore_total</code></td><td>integer</td><td>Total local store clicks</td></tr>
                     <tr><td><code>click_integration_total</code></td><td>integer</td><td>Total integration/external clicks</td></tr>
                     <tr><td><code>click_form_total</code></td><td>integer</td><td>Total form/lead clicks</td></tr>
                     <tr><td><code>click_localstore_commission</code></td><td>string</td><td>Total commission from local store clicks</td></tr>
                     <tr><td><code>click_integration_commission</code></td><td>string</td><td>Total commission from integration clicks</td></tr>
                     <tr><td><code>click_form_commission</code></td><td>string</td><td>Total commission from form clicks</td></tr>
                     <tr><td><code>transactions</code></td><td>array</td><td>Recent wallet transactions (up to 50, includes user details)</td></tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
