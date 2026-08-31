<!-- Admin API Introduction -->
<div class="top-content" id="admin_api">
   <!-- Hero Section -->
   <div class="row mb-4">
      <div class="col-12">
         <div class="rounded-3 p-5 mb-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
            <div class="d-flex align-items-center flex-wrap gap-3 mb-3">
               <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                  <i class="bi bi-shield-lock display-5 text-danger"></i>
               </div>
               <div>
                  <h1 class="display-6 fw-bold text-white mb-1">Admin API</h1>
                  <p class="text-white-50 mb-0">Administrative REST API for managing your AffiliatePro platform</p>
               </div>
               <span class="badge fs-6 px-3 py-2 ms-auto" style="background: rgba(220,53,69,0.3); color: #ff6b7a;">
                  <i class="bi bi-shield-check me-1"></i>Admin Only
               </span>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3">
               <span class="badge fs-6 px-3 py-2" style="background: rgba(25,135,84,0.2); color: #4ade80;">
                  <i class="bi bi-lightning me-1"></i>RESTful Design
               </span>
               <span class="badge fs-6 px-3 py-2" style="background: rgba(13,202,240,0.2); color: #67e8f9;">
                  <i class="bi bi-code-slash me-1"></i>JSON Responses
               </span>
            </div>
         </div>
      </div>
   </div>

   <div class="row g-4">
      <!-- Authentication Info Card -->
      <div class="col-lg-6">
         <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
               <div class="d-flex align-items-start">
                  <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                     <i class="bi bi-key-fill text-danger fs-5"></i>
                  </div>
                  <div class="flex-grow-1">
                     <h5 class="fw-bold mb-3">Authentication</h5>
                     <p class="text-muted small mb-3">All endpoints require a valid admin JWT token. Use the raw token — <strong>do not</strong> include a <code>Bearer</code> prefix.</p>
                     <div class="p-3 rounded-2 font-monospace small" style="background: #1e1e1e; color: #d4d4d4; border-left: 4px solid #dc3545;">
                        Authorization: &lt;your-admin-jwt-token&gt;
                     </div>
                     <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i>Obtain a token via the <a href="<?=base_url();?>api-document#login" class="text-decoration-none">User Login API</a> with admin credentials.</small>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Base URL Info Card -->
      <div class="col-lg-6">
         <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
               <div class="d-flex align-items-start">
                  <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                     <i class="bi bi-link-45deg text-primary fs-5"></i>
                  </div>
                  <div class="flex-grow-1">
                     <h5 class="fw-bold mb-3">Base URL</h5>
                     <p class="text-muted small mb-3">All Admin API endpoints are relative to this base URL.</p>
                     <div class="p-3 rounded-2 font-monospace small" style="background: #1e1e1e; color: #4ec9b0; border-left: 4px solid #0d6efd;">
                        <?=base_url();?>Admin_Api/
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Error Codes Reference -->
      <div class="col-12">
         <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
               <div class="d-flex align-items-center mb-4">
                  <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                     <i class="bi bi-list-ol text-warning fs-5"></i>
                  </div>
                  <h5 class="fw-bold mb-0">Error Codes Reference</h5>
               </div>
               <div class="row g-3">
                  <div class="col-md-6 col-lg-3">
                     <div class="d-flex align-items-center p-3 rounded-3" style="background: rgba(25,135,84,0.08); border-left: 4px solid #198754;">
                        <span class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-success me-3" style="width: 36px; height: 36px;">200</span>
                        <div>
                           <span class="fw-semibold small">Success</span>
                           <p class="text-muted small mb-0">Request completed successfully</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                     <div class="d-flex align-items-center p-3 rounded-3" style="background: rgba(220,53,69,0.08); border-left: 4px solid #dc3545;">
                        <span class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-danger me-3" style="width: 36px; height: 36px;">401</span>
                        <div>
                           <span class="fw-semibold small">Unauthorized</span>
                           <p class="text-muted small mb-0">Invalid or missing token</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                     <div class="d-flex align-items-center p-3 rounded-3" style="background: rgba(253,126,20,0.08); border-left: 4px solid #fd7e14;">
                        <span class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-warning me-3" style="width: 36px; height: 36px;">403</span>
                        <div>
                           <span class="fw-semibold small">Forbidden</span>
                           <p class="text-muted small mb-0">Valid token but not admin</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                     <div class="d-flex align-items-center p-3 rounded-3" style="background: rgba(111,66,193,0.08); border-left: 4px solid #6f42c1;">
                        <span class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold me-3" style="width: 36px; height: 36px;">422</span>
                        <div>
                           <span class="fw-semibold small">Unprocessable</span>
                           <p class="text-muted small mb-0">Validation failed</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Quick Start Steps -->
      <div class="col-12">
         <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
               <div class="d-flex align-items-center mb-4">
                  <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                     <i class="bi bi-rocket-takeoff text-info fs-5"></i>
                  </div>
                  <h5 class="fw-bold mb-0">Quick Start</h5>
               </div>
               <div class="row g-3">
                  <div class="col-md-6 col-lg-4">
                     <div class="d-flex align-items-start p-3 rounded-3 h-100" style="background: #f8f9fa;">
                        <span class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-primary me-3 flex-shrink-0" style="width: 36px; height: 36px;">1</span>
                        <div>
                           <h6 class="fw-semibold mb-1">Login as Admin</h6>
                           <p class="text-muted small mb-0">Use the User Login API with admin credentials to obtain a JWT token.</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                     <div class="d-flex align-items-start p-3 rounded-3 h-100" style="background: #f8f9fa;">
                        <span class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-primary me-3 flex-shrink-0" style="width: 36px; height: 36px;">2</span>
                        <div>
                           <h6 class="fw-semibold mb-1">Add Authorization Header</h6>
                           <p class="text-muted small mb-0">Include the raw JWT in the <code>Authorization</code> header (no Bearer prefix).</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                     <div class="d-flex align-items-start p-3 rounded-3 h-100" style="background: #f8f9fa;">
                        <span class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-primary me-3 flex-shrink-0" style="width: 36px; height: 36px;">3</span>
                        <div>
                           <h6 class="fw-semibold mb-1">Call Endpoints</h6>
                           <p class="text-muted small mb-0">Send requests to <code><?=base_url();?>Admin_Api/</code> + endpoint path.</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Sub-admin roles (same as web Manage Roles) -->
      <div class="col-12" id="admin_api_roles">
         <div class="card border-0 shadow-sm rounded-3 border-start border-4 border-secondary">
            <div class="card-body p-4">
               <div class="d-flex align-items-center mb-3">
                  <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                     <i class="bi bi-person-badge text-secondary fs-5"></i>
                  </div>
                  <h5 class="fw-bold mb-0">Sub-admin roles &amp; permissions</h5>
               </div>
               <p class="text-muted small mb-3">The Admin API uses the <strong>same permission slugs</strong> as the website (<strong>Admin panel → Manage Roles</strong>). There is no separate mobile permission list.</p>
               <ul class="small text-muted mb-3">
                  <li><strong>Super admin</strong> (<code>users.id = 1</code>) can call every endpoint.</li>
                  <li>Admins with an <strong>assigned role</strong> may only call endpoints whose required slug appears in that role&rsquo;s JSON (<code>admin_roles.permissions</code>).</li>
                  <li>Admins with <strong>no role</strong> and <strong>no custom</strong> <code>users.admin_permissions</code> JSON behave as <strong>full access</strong> (same as the web panel).</li>
                  <li>Admins with <strong>custom perms</strong> on the user row are limited to those slugs.</li>
               </ul>
               <p class="text-muted small mb-2"><strong><code>GET Admin_Api/profile</code></strong> is always allowed for a valid admin JWT so clients can load the account. Its <code>data</code> includes:</p>
               <div class="table-responsive mb-3">
                  <table class="table table-sm table-hover mb-0">
                     <thead class="table-light">
                        <tr>
                           <th>Field</th>
                           <th>Type</th>
                           <th>Description</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td><code>admin_full_access</code></td>
                           <td><code>bool</code></td>
                           <td><code>true</code> when this account is treated as unrestricted (super admin or legacy full-access rule above).</td>
                        </tr>
                        <tr>
                           <td><code>admin_permission_slugs</code></td>
                           <td><code>array</code> of <code>string</code></td>
                           <td>Explicit slugs for this admin (sorted). When <code>admin_full_access</code> is <code>true</code>, this lists all defined slugs for UI convenience.</td>
                        </tr>
                        <tr>
                           <td><code>admin_role_name</code></td>
                           <td><code>string</code> or omitted</td>
                           <td>Human-readable role name from <code>admin_roles.name</code> when a role is assigned.</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <p class="text-muted small mb-2">All <strong>other</strong> Admin API methods enforce one required slug per action. If the caller lacks it, the HTTP status is still <strong>200</strong> with JSON like:</p>
               <pre class="p-3 rounded-2 font-monospace small mb-3" style="background: #1e1e1e; color: #d4d4d4; border-left: 4px solid #6c757d;">{
  "status": false,
  "message": "Access Denied! Insufficient permissions.",
  "required_permission": "users"
}</pre>
               <p class="text-muted small mb-0"><strong>Developer reference:</strong> the mapping from PHP method name → slug is in <code>application/config/admin_permissions.php</code> under <code>$config['admin_api_method_permissions']</code>, aligned with the web <code>admin_permission_map</code> where the feature exists on both sides.</p>
            </div>
         </div>
      </div>

      <!-- Currency Settings Info -->
      <div class="col-12">
         <div class="card border-0 shadow-sm rounded-3 border-start border-4 border-info">
            <div class="card-body p-4">
               <div class="d-flex align-items-center mb-3">
                  <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                     <i class="bi bi-currency-exchange text-info fs-5"></i>
                  </div>
                  <h5 class="fw-bold mb-0">Currency Settings</h5>
               </div>
               <p class="text-muted small mb-3">Every API endpoint that returns monetary values automatically includes the platform's currency settings. Use these to format amounts correctly in your app instead of hardcoding <code>$</code>.</p>
               <div class="table-responsive">
                  <table class="table table-sm table-hover mb-0">
                     <thead class="table-light">
                        <tr>
                           <th>Field</th>
                           <th>Type</th>
                           <th>Example</th>
                           <th>Description</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td><code>currency_symbol</code></td>
                           <td><code>string</code></td>
                           <td><code>$</code></td>
                           <td>The default currency symbol from the platform settings</td>
                        </tr>
                        <tr>
                           <td><code>currency_code</code></td>
                           <td><code>string</code></td>
                           <td><code>USD</code></td>
                           <td>ISO 4217 currency code</td>
                        </tr>
                        <tr>
                           <td><code>enable_shorten_numbers</code></td>
                           <td><code>integer</code></td>
                           <td><code>1</code></td>
                           <td>If <code>1</code>, format large amounts as <code>$3.9k</code> / <code>$1.2M</code>. If <code>0</code>, show full numbers like <code>$3900.00</code></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <div class="alert alert-light border mt-3 mb-0 small">
                  <i class="bi bi-lightbulb text-warning me-1"></i>
                  <strong>Tip:</strong> These fields are included in: <code>dashboard</code>, <code>users</code>, <code>user_details</code>, <code>withdrawals</code>, <code>wallet</code>, <code>reports</code>, and the <code>login</code> response.
                  Never hardcode a currency symbol — always read it from the API response.
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
