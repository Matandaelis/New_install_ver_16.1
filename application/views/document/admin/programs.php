<div class="top-content" id="admin_programs">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-puzzle me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Integration Programs</h3>
   </div>
   <p class="text-muted mb-4">Browse and inspect integration programs (vendor affiliate programs). Each program defines commission rules for sales and clicks that apply to its campaigns.</p>

   <div class="mb-4" id="admin_programs_list">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>
         Programs List
      </h5>
      <p class="text-muted small mb-3">Retrieve a paginated, searchable list of integration programs with optional status filter.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/programs</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                     <tr><td>start_from</td><td><code>integer</code></td><td><code>Query</code></td><td>Pagination offset (default: 0)</td></tr>
                     <tr><td>limit</td><td><code>integer</code></td><td><code>Query</code></td><td>Results per page, max 100 (default: 20)</td></tr>
                     <tr><td>search</td><td><code>string</code></td><td><code>Query</code></td><td>Search by program name or vendor username</td></tr>
                     <tr><td>status</td><td><code>string</code></td><td><code>Query</code></td><td><code>all</code>, <code>active</code>, or <code>inactive</code> (default: all)</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "programs": [
      {
        "id": "5",
        "name": "Fashion Affiliate Program",
        "status": "1",
        "vendor_id": "12",
        "username": "vendor_user",
        "commission_type": "percentage",
        "commission_sale": "10",
        "sale_status": "1",
        "click_status": "0",
        "associate_programns": "3"
      }
    ],
    "total_count": 12,
    "start_from": 0,
    "limit": 20,
    "has_more": false,
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 1
  }
}</code></pre>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Program Status Codes</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Code</th><th>Label</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td><code>0</code></td><td>In Review</td><td>Awaiting admin approval</td></tr>
                     <tr><td><code>1</code></td><td>Approved / Active</td><td>Program is live</td></tr>
                     <tr><td><code>2</code></td><td>Denied</td><td>Rejected by admin</td></tr>
                     <tr><td><code>3</code></td><td>Ask to Edit</td><td>Returned to vendor for changes</td></tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_program_details">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-file-earmark-text text-primary"></i>
         Program Details
      </h5>
      <p class="text-muted small mb-3">Retrieve full details for a single program including commission settings and vendor information.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/program_details</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>true</code></td><td>Admin JWT token (Header)</td></tr>
                     <tr><td>program_id</td><td><code>integer</code></td><td><code>true</code></td><td>The ID of the program</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "id": "5",
    "name": "Fashion Affiliate Program",
    "status": "1",
    "vendor_id": "12",
    "username": "vendor_user",
    "email": "vendor@example.com",
    "vendor_name": "John Doe",
    "firstname": "John",
    "lastname": "Doe",
    "commission_type": "percentage",
    "commission_sale": "10",
    "sale_status": "1",
    "click_status": "0",
    "tools_count": 3,
    "currency_symbol": "$",
    "currency_code": "USD"
  }
}</code></pre>
         </div>
      </div>
   </div>

</div>
