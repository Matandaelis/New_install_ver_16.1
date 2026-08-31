<div class="top-content" id="admin_reports">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-graph-up me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Reports & Analytics</h3>
   </div>
   <p class="text-muted mb-4">Retrieve analytics data for your platform within a date range. Includes clicks by day, sales by day, and top-performing affiliates.</p>
   <p class="text-muted small mb-4">If no date range is provided, defaults to the current month (first day of month to today).</p>

   <!-- Reports Endpoint -->
   <div class="mb-4">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-bar-chart-line text-primary"></i>
         Reports
      </h5>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/reports</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                     <tr><td>date_from</td><td><code>string</code></td><td><code>Query</code></td><td>Start date in <code>YYYY-MM-DD</code> format (default: first day of current month)</td></tr>
                     <tr><td>date_to</td><td><code>string</code></td><td><code>Query</code></td><td>End date in <code>YYYY-MM-DD</code> format (default: today)</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{"status":true,"data":{"date_from":"2025-06-01","date_to":"2025-06-15","clicks_by_day":[{"date":"2025-06-01","count":"12"},{"date":"2025-06-02","count":"18"},{"date":"2025-06-03","count":"25"}],"sales_by_day":[{"date":"2025-06-01","count":"3","total":"450.00"},{"date":"2025-06-02","count":"5","total":"780.00"},{"date":"2025-06-03","count":"2","total":"200.00"}],"top_affiliates":[{"id":"88","firstname":"John","lastname":"Doe","username":"johndoe","total_sales":"15","total_revenue":"3500.00"},{"id":"65","firstname":"Jane","lastname":"Smith","username":"janesmith","total_sales":"12","total_revenue":"2800.00"}],"currency_symbol":"$","currency_code":"USD","enable_shorten_numbers":1}}</code></pre>
         </div>
      </div>
   </div>
</div>
