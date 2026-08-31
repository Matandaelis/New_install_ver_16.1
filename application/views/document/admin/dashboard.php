<div class="row">
   <div class="col-md-12">
      <div class="top-content" id="admin_dashboard">
         <h3 class="page-title"><i class="bi bi-speedometer2 text-danger me-2"></i>Admin Dashboard</h3>
         <p>Retrieve admin dashboard statistics with HTTP GET request.</p>
         <p>Returns a comprehensive overview of your platform: total users, affiliates (active non-vendor users), vendors, pending items, today's activity, admin balance with growth, store &amp; vendor sales, commissions (admin + affiliate), click commissions, currency settings (symbol, code, shorten formatting), and recent user registrations. All data uses the same source as the web admin dashboard.</p>
         <div class="row">
            <div class="col-md-6">
               <div class="card border-0 shadow-sm mb-3">
                  <div class="card-header bg-primary text-white">
                     <h3 class="card-title mb-0">Request</h3>
                  </div>
                  <div class="card-body">
                     <div class="mb-3">
                        <span class="badge bg-success me-2">GET</span>
                        <code class="text-break"><?=base_url();?>Admin_Api/dashboard</code>
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
                                 <td>Authorization</td>
                                 <td><code>string</code></td>
                                 <td><code>Header</code></td>
                                 <td>Admin JWT token obtained from login API</td>
                              </tr>
                           </tbody>
                        </table>
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
                     <pre class="response-view">{"status":true,"data":{"total_users":150,"total_affiliates":120,"total_vendors":30,"pending_users":5,"pending_withdrawals":3,"total_withdrawals_amount":"1250.00","admin_balance":"3900.50","admin_balance_growth":12.5,"store_sales":"8500.00","store_commission":"850.00","store_orders":42,"vendor_sales":"3200.00","vendor_commission":"320.00","vendor_orders":18,"click_commission":"450.00","total_clicks":1250,"today_clicks":45,"today_sales":8,"currency_symbol":"$","currency_code":"USD","enable_shorten_numbers":1,"recent_users":[{"id":"88","firstname":"John","lastname":"Doe","username":"johndoe","email":"john@example.com","type":"user","status":"1","created_at":"2025-06-11 16:44:16"},{"id":"87","firstname":"Jane","lastname":"Smith","username":"janesmith","email":"jane@example.com","type":"user","status":"0","created_at":"2025-06-10 14:22:08"}]}}</pre>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
