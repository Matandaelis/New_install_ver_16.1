<style>
.api-response-block {
   background: #1e1e1e;
   border-radius: 0.5rem;
   padding: 1rem 1.25rem;
   margin: 0;
   overflow-x: auto;
   font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
   font-size: 0.875rem;
   line-height: 1.5;
}
.api-response-block .json-key { color: #9cdcfe; }
.api-response-block .json-string { color: #ce9178; }
.api-response-block .json-number { color: #b5cea8; }
.api-response-block .json-bool { color: #569cd6; }
.api-response-block .json-punct { color: #d4d4d4; }
</style>

<div class="page-intro top-content" id="admin_users">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-primary rounded-pill px-3 py-2">
         <i class="bi bi-people-fill me-1"></i>Admin - Users Management
      </span>
   </div>
   <p class="text-muted mb-0">Manage all users/affiliates on your platform. List users with pagination and filters, view detailed user information, and update user status (approve, block, set to pending).</p>
</div>

<!-- Users List -->
<div class="top-content mt-4" id="admin_users_list">
   <h5 class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-list-ul text-primary"></i>
      <span>Users List</span>
   </h5>
   <p class="text-muted small mb-3">Retrieve a paginated list of users with optional search and status filters.</p>
</div>
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
   <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
         <span class="badge bg-success px-2 py-1"><i class="bi bi-arrow-right-circle me-1"></i>GET</span>
         <code class="text-dark fs-6"><?=base_url();?>Admin_Api/users</code>
      </div>
   </div>
   <div class="card-body">
      <div class="row g-4">
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-sliders me-1"></i>Parameters</h6>
            <div class="table-responsive">
               <table class="table table-sm table-borderless">
                  <thead>
                     <tr class="border-bottom">
                        <th class="fw-semibold small">Parameter</th>
                        <th class="fw-semibold small">Type</th>
                        <th class="fw-semibold small">Position</th>
                        <th class="fw-semibold small">Description</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr class="border-bottom">
                        <td><code>Authorization</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>Header</code></td>
                        <td>Admin JWT token</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>start_from</code></td>
                        <td><code class="text-muted">integer</code></td>
                        <td><code>Query</code></td>
                        <td>Pagination offset (default: 0)</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>limit</code></td>
                        <td><code class="text-muted">integer</code></td>
                        <td><code>Query</code></td>
                        <td>Number of results per page (default: 20)</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>search</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>Query</code></td>
                        <td>Search by username, email, firstname, or lastname</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>status</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>Query</code></td>
                        <td><code>all</code>, <code>active</code>, <code>pending</code>, or <code>blocked</code> (default: all)</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>account</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>Query</code></td>
                        <td><code>all</code>, <code>affiliate</code> (non-vendor users), or <code>vendor</code> (<code>is_vendor=1</code>)</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-code-slash me-1"></i>Response</h6>
            <pre class="api-response-block"><span class="json-punct">{</span><span class="json-key">"status"</span><span class="json-punct">:</span><span class="json-bool">true</span><span class="json-punct">,</span><span class="json-key">"data"</span><span class="json-punct">:{</span><span class="json-key">"users"</span><span class="json-punct">:[{</span><span class="json-key">"id"</span><span class="json-punct">:</span><span class="json-string">"88"</span><span class="json-punct">,</span><span class="json-key">"firstname"</span><span class="json-punct">:</span><span class="json-string">"John"</span><span class="json-punct">,</span><span class="json-key">"lastname"</span><span class="json-punct">:</span><span class="json-string">"Doe"</span><span class="json-punct">,</span><span class="json-key">"username"</span><span class="json-punct">:</span><span class="json-string">"johndoe"</span><span class="json-punct">,</span><span class="json-key">"email"</span><span class="json-punct">:</span><span class="json-string">"john@example.com"</span><span class="json-punct">,</span><span class="json-key">"status"</span><span class="json-punct">:</span><span class="json-string">"1"</span><span class="json-punct">,</span><span class="json-key">"is_vendor"</span><span class="json-punct">:</span><span class="json-string">"0"</span><span class="json-punct">,</span><span class="json-key">"account_type"</span><span class="json-punct">:</span><span class="json-string">"affiliate"</span><span class="json-punct">,</span><span class="json-key">"balance"</span><span class="json-punct">:</span><span class="json-string">"150.00"</span><span class="json-punct">,</span><span class="json-key">"total_clicks"</span><span class="json-punct">:</span><span class="json-number">230</span><span class="json-punct">,</span><span class="json-key">"total_sales"</span><span class="json-punct">:</span><span class="json-number">15</span><span class="json-punct">}],</span><span class="json-key">"total_count"</span><span class="json-punct">:</span><span class="json-number">150</span><span class="json-punct">,</span><span class="json-key">"has_more"</span><span class="json-punct">:</span><span class="json-bool">true</span><span class="json-punct">,</span><span class="json-key">"currency_symbol"</span><span class="json-punct">:</span><span class="json-string">"$"</span><span class="json-punct">,</span><span class="json-key">"currency_code"</span><span class="json-punct">:</span><span class="json-string">"USD"</span><span class="json-punct">,</span><span class="json-key">"enable_shorten_numbers"</span><span class="json-punct">:</span><span class="json-number">1</span><span class="json-punct">}}</span></pre>
         </div>
      </div>
   </div>
</div>

<!-- User Details -->
<div class="top-content mt-4" id="admin_user_details">
   <h5 class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-person-badge text-primary"></i>
      <span>User Details</span>
   </h5>
   <p class="text-muted small mb-3">Retrieve detailed information for a specific user including balance, clicks, and sales. Response includes <code>account_type</code>: <code>vendor</code> or <code>affiliate</code>. When <code>is_vendor</code> is <code>1</code>, the payload also includes read-only <code>vendor_profile_extras</code>: <code>store</code> (name, slug, email, address, contact fields, meta), optional <code>vendor_setting</code> (commission-related columns from <code>vendor_setting</code>), and <code>counts</code> (<code>integration_programs</code>, <code>integration_tools</code>, <code>store_products</code>).</p>
</div>
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
   <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
         <span class="badge bg-success px-2 py-1"><i class="bi bi-arrow-right-circle me-1"></i>GET</span>
         <code class="text-dark fs-6"><?=base_url();?>Admin_Api/user_details</code>
      </div>
   </div>
   <div class="card-body">
      <div class="row g-4">
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-sliders me-1"></i>Parameters</h6>
            <div class="table-responsive">
               <table class="table table-sm table-borderless">
                  <thead>
                     <tr class="border-bottom">
                        <th class="fw-semibold small">Parameter</th>
                        <th class="fw-semibold small">Type</th>
                        <th class="fw-semibold small">Position</th>
                        <th class="fw-semibold small">Description</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr class="border-bottom">
                        <td><code>Authorization</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>Header</code></td>
                        <td>Admin JWT token</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>user_id</code></td>
                        <td><code class="text-muted">integer</code></td>
                        <td><code>Query</code></td>
                        <td>The ID of the user to retrieve (required)</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-code-slash me-1"></i>Response</h6>
            <pre class="api-response-block"><span class="json-punct">{</span><span class="json-key">"status"</span><span class="json-punct">:</span><span class="json-bool">true</span><span class="json-punct">,</span><span class="json-key">"data"</span><span class="json-punct">:{</span><span class="json-key">"id"</span><span class="json-punct">:</span><span class="json-string">"88"</span><span class="json-punct">,</span><span class="json-key">"firstname"</span><span class="json-punct">:</span><span class="json-string">"John"</span><span class="json-punct">,</span><span class="json-key">"lastname"</span><span class="json-punct">:</span><span class="json-string">"Doe"</span><span class="json-punct">,</span><span class="json-key">"status"</span><span class="json-punct">:</span><span class="json-string">"1"</span><span class="json-punct">,</span><span class="json-key">"is_vendor"</span><span class="json-punct">:</span><span class="json-string">"0"</span><span class="json-punct">,</span><span class="json-key">"account_type"</span><span class="json-punct">:</span><span class="json-string">"affiliate"</span><span class="json-punct">,</span><span class="json-key">"balance"</span><span class="json-punct">:</span><span class="json-string">"150.00"</span><span class="json-punct">,</span><span class="json-key">"total_clicks"</span><span class="json-punct">:</span><span class="json-number">230</span><span class="json-punct">,</span><span class="json-key">"total_sales"</span><span class="json-punct">:</span><span class="json-number">15</span><span class="json-punct">,</span><span class="json-key">"total_revenue"</span><span class="json-punct">:</span><span class="json-string">"450.00"</span><span class="json-punct">,</span><span class="json-key">"total_referrals"</span><span class="json-punct">:</span><span class="json-number">3</span><span class="json-punct">,</span><span class="json-key">"recent_clicks"</span><span class="json-punct">:[],</span><span class="json-key">"recent_sales"</span><span class="json-punct">:[],</span><span class="json-key">"currency_symbol"</span><span class="json-punct">:</span><span class="json-string">"$"</span><span class="json-punct">,</span><span class="json-key">"currency_code"</span><span class="json-punct">:</span><span class="json-string">"USD"</span><span class="json-punct">,</span><span class="json-key">"enable_shorten_numbers"</span><span class="json-punct">:</span><span class="json-number">1</span><span class="json-punct">}}</span></pre>
         </div>
      </div>
   </div>
</div>

<!-- Toggle User Status -->
<div class="top-content mt-4" id="admin_update_user_status">
   <h5 class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-toggles text-primary"></i>
      <span>Toggle User Status</span>
   </h5>
   <p class="text-muted small mb-3">Toggle a user's account status between enabled (<code>status=1</code>) and disabled (<code>status=0</code>). Works exactly like the <i class="fa fa-unlock"></i> / <i class="fa fa-lock"></i> button on the web admin Users List page. A disabled user cannot login.</p>
</div>
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
   <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
         <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-send me-1"></i>POST</span>
         <code class="text-dark fs-6"><?=base_url();?>Admin_Api/update_user_status</code>
      </div>
   </div>
   <div class="card-body">
      <div class="row g-4">
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-sliders me-1"></i>Body Parameters</h6>
            <div class="table-responsive">
               <table class="table table-sm table-borderless">
                  <thead>
                     <tr class="border-bottom">
                        <th class="fw-semibold small">Parameter</th>
                        <th class="fw-semibold small">Type</th>
                        <th class="fw-semibold small">Required</th>
                        <th class="fw-semibold small">Description</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr class="border-bottom">
                        <td><code>Authorization</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>true</code></td>
                        <td>Admin JWT token (Header)</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>user_id</code></td>
                        <td><code class="text-muted">integer</code></td>
                        <td><code>true</code></td>
                        <td>The ID of the user to toggle</td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="alert alert-info small mt-3 mb-0">
               <i class="bi bi-info-circle me-1"></i>
               <strong>Toggle behavior:</strong> If user <code>status=1</code> (enabled), it becomes <code>0</code> (disabled). If <code>status=0</code>, it becomes <code>1</code>. The response includes the <code>new_status</code> value.
            </div>
         </div>
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-code-slash me-1"></i>Response</h6>
            <pre class="api-response-block"><span class="json-punct">{</span><span class="json-key">"status"</span><span class="json-punct">:</span><span class="json-bool">true</span><span class="json-punct">,</span><span class="json-key">"message"</span><span class="json-punct">:</span><span class="json-string">"User status disabled successfully"</span><span class="json-punct">,</span><span class="json-key">"new_status"</span><span class="json-punct">:</span><span class="json-number">0</span><span class="json-punct">}</span></pre>
         </div>
      </div>
   </div>
</div>
