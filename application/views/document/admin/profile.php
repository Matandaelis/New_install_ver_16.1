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

<div class="page-intro top-content" id="admin_profile">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-info rounded-pill px-3 py-2">
         <i class="bi bi-person-circle me-1"></i>Admin - Profile Management
      </span>
   </div>
   <p class="text-muted mb-0">Manage the admin user's own profile. Get full profile details including country information and available countries list, and update profile fields including avatar upload. <strong>GET profile</strong> also returns <strong>role permission slugs</strong> for sub-admins (see <a href="#admin_api_roles" class="text-decoration-none">Introduction → Sub-admin roles</a>).</p>
</div>

<!-- Get Profile -->
<div class="top-content mt-4" id="admin_profile_get">
   <h5 class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-person-badge text-info"></i>
      <span>Get Admin Profile</span>
   </h5>
   <p class="text-muted small mb-3">Retrieve the authenticated admin's full profile details. Also returns a list of all available countries for use in country selection dropdowns.</p>
</div>
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
   <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
         <span class="badge bg-success px-2 py-1"><i class="bi bi-arrow-right-circle me-1"></i>GET</span>
         <code class="text-dark fs-6"><?=base_url();?>Admin_Api/profile</code>
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
                  </tbody>
               </table>
            </div>
            <div class="mt-3">
               <div class="alert alert-info border-0 py-2 px-3 small mb-0">
                  <i class="bi bi-info-circle me-1"></i>No additional parameters required. The admin user is identified from the JWT token.
               </div>
            </div>
         </div>
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-code-slash me-1"></i>Response</h6>
            <pre class="api-response-block"><span class="json-punct">{</span>
  <span class="json-key">"status"</span><span class="json-punct">:</span> <span class="json-bool">true</span><span class="json-punct">,</span>
  <span class="json-key">"message"</span><span class="json-punct">:</span> <span class="json-string">"Admin profile loaded successfully"</span><span class="json-punct">,</span>
  <span class="json-key">"data"</span><span class="json-punct">: {</span>
    <span class="json-key">"firstname"</span><span class="json-punct">:</span> <span class="json-string">"John"</span><span class="json-punct">,</span>
    <span class="json-key">"lastname"</span><span class="json-punct">:</span> <span class="json-string">"Doe"</span><span class="json-punct">,</span>
    <span class="json-key">"email"</span><span class="json-punct">:</span> <span class="json-string">"admin@example.com"</span><span class="json-punct">,</span>
    <span class="json-key">"username"</span><span class="json-punct">:</span> <span class="json-string">"admin"</span><span class="json-punct">,</span>
    <span class="json-key">"PhoneNumber"</span><span class="json-punct">:</span> <span class="json-string">"+1 201-555-0123"</span><span class="json-punct">,</span>
    <span class="json-key">"city"</span><span class="json-punct">:</span> <span class="json-string">"New York"</span><span class="json-punct">,</span>
    <span class="json-key">"pincode"</span><span class="json-punct">:</span> <span class="json-string">"10001"</span><span class="json-punct">,</span>
    <span class="json-key">"country_id"</span><span class="json-punct">:</span> <span class="json-string">"231"</span><span class="json-punct">,</span>
    <span class="json-key">"country_code"</span><span class="json-punct">:</span> <span class="json-string">"US"</span><span class="json-punct">,</span>
    <span class="json-key">"country_name"</span><span class="json-punct">:</span> <span class="json-string">"United States"</span><span class="json-punct">,</span>
    <span class="json-key">"country_flag"</span><span class="json-punct">:</span> <span class="json-string">"https://yoursite.com/assets/template/images/flags/us.png"</span><span class="json-punct">,</span>
    <span class="json-key">"profile_avatar"</span><span class="json-punct">:</span> <span class="json-string">"https://yoursite.com/assets/images/users/avatar.jpg"</span><span class="json-punct">,</span>
    <span class="json-key">"role"</span><span class="json-punct">:</span> <span class="json-string">"admin"</span><span class="json-punct">,</span>
    <span class="json-key">"admin_full_access"</span><span class="json-punct">:</span> <span class="json-bool">false</span><span class="json-punct">,</span>
    <span class="json-key">"admin_permission_slugs"</span><span class="json-punct">: [</span> <span class="json-string">"dashboard"</span><span class="json-punct">,</span> <span class="json-string">"users"</span> <span class="json-punct">],</span>
    <span class="json-key">"admin_role_name"</span><span class="json-punct">:</span> <span class="json-string">"Support"</span><span class="json-punct">,</span>
    <span class="json-key">"countries"</span><span class="json-punct">: [</span>
      <span class="json-punct">{</span><span class="json-key">"id"</span><span class="json-punct">:</span> <span class="json-string">"1"</span><span class="json-punct">,</span> <span class="json-key">"name"</span><span class="json-punct">:</span> <span class="json-string">"Afghanistan"</span><span class="json-punct">},</span>
      <span class="json-punct">{</span><span class="json-key">"id"</span><span class="json-punct">:</span> <span class="json-string">"2"</span><span class="json-punct">,</span> <span class="json-key">"name"</span><span class="json-punct">:</span> <span class="json-string">"Albania"</span><span class="json-punct">},</span>
      <span class="json-string">"..."</span>
    <span class="json-punct">]</span>
  <span class="json-punct">}</span>
<span class="json-punct">}</span></pre>
            <div class="mt-3">
               <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-list-columns me-1"></i>Response Fields</h6>
               <div class="table-responsive">
                  <table class="table table-sm table-borderless">
                     <thead>
                        <tr class="border-bottom">
                           <th class="fw-semibold small">Field</th>
                           <th class="fw-semibold small">Type</th>
                           <th class="fw-semibold small">Description</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr class="border-bottom">
                           <td><code>firstname</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>Admin first name</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>lastname</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>Admin last name</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>email</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>Admin email address</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>username</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>Admin username</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>PhoneNumber</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>Phone number</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>city</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>City name</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>pincode</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>Postal / ZIP code</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>country_id</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>Selected country ID</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>country_code</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>ISO country code (e.g. US, GB)</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>country_name</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>Full country name</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>country_flag</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>URL to country flag image</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>profile_avatar</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>URL to profile avatar image</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>role</code></td>
                           <td><code class="text-muted">string</code></td>
                           <td>User role (always <code>admin</code>)</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>admin_full_access</code></td>
                           <td><code class="text-muted">boolean</code></td>
                           <td><code>true</code> if this account bypasses slug checks (super admin or full-access rule). See <a href="#admin_api_roles">Sub-admin roles</a>.</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>admin_permission_slugs</code></td>
                           <td><code class="text-muted">array</code></td>
                           <td>List of permission slugs for this admin (same strings as web <strong>Manage Roles</strong>).</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>admin_role_name</code></td>
                           <td><code class="text-muted">string</code> or null</td>
                           <td>Assigned role display name from <code>admin_roles</code>, if any.</td>
                        </tr>
                        <tr class="border-bottom">
                           <td><code>countries</code></td>
                           <td><code class="text-muted">array</code></td>
                           <td>List of all available countries (each with <code>id</code> and <code>name</code>)</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Update Profile -->
<div class="top-content mt-4" id="admin_profile_update">
   <h5 class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-pencil-square text-info"></i>
      <span>Update Admin Profile</span>
   </h5>
   <p class="text-muted small mb-3">Update the authenticated admin's profile information. Supports text fields and avatar image upload. Only provided fields will be updated. Requires the <strong><code>settings</code></strong> permission slug for sub-admins (same as general settings access on the web).</p>
</div>
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
   <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
         <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-send me-1"></i>POST</span>
         <code class="text-dark fs-6"><?=base_url();?>Admin_Api/update_profile</code>
      </div>
      <span class="badge bg-light text-dark border"><i class="bi bi-file-earmark-arrow-up me-1"></i>multipart/form-data</span>
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
                        <td><code>firstname</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>true</code></td>
                        <td>First name</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>lastname</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>true</code></td>
                        <td>Last name</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>email</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>true</code></td>
                        <td>Email address</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>phone</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>false</code></td>
                        <td>Phone number</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>country_id</code></td>
                        <td><code class="text-muted">integer</code></td>
                        <td><code>false</code></td>
                        <td>Country ID from the countries list</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>city</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>false</code></td>
                        <td>City name</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>pincode</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>false</code></td>
                        <td>Postal / ZIP code</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>password</code></td>
                        <td><code class="text-muted">string</code></td>
                        <td><code>false</code></td>
                        <td>New password (leave empty to keep current)</td>
                     </tr>
                     <tr class="border-bottom">
                        <td><code>avatar</code></td>
                        <td><code class="text-muted">file</code></td>
                        <td><code>false</code></td>
                        <td>Profile image (png, gif, jpeg, jpg &mdash; max 2MB)</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-code-slash me-1"></i>Response</h6>
            <pre class="api-response-block"><span class="json-punct">{</span>
  <span class="json-key">"status"</span><span class="json-punct">:</span> <span class="json-bool">true</span><span class="json-punct">,</span>
  <span class="json-key">"message"</span><span class="json-punct">:</span> <span class="json-string">"Profile updated successfully"</span><span class="json-punct">,</span>
  <span class="json-key">"data"</span><span class="json-punct">: {</span>
    <span class="json-key">"firstname"</span><span class="json-punct">:</span> <span class="json-string">"John"</span><span class="json-punct">,</span>
    <span class="json-key">"lastname"</span><span class="json-punct">:</span> <span class="json-string">"Doe"</span><span class="json-punct">,</span>
    <span class="json-key">"email"</span><span class="json-punct">:</span> <span class="json-string">"admin@example.com"</span><span class="json-punct">,</span>
    <span class="json-key">"username"</span><span class="json-punct">:</span> <span class="json-string">"admin"</span><span class="json-punct">,</span>
    <span class="json-key">"PhoneNumber"</span><span class="json-punct">:</span> <span class="json-string">"+1 201-555-0123"</span><span class="json-punct">,</span>
    <span class="json-key">"city"</span><span class="json-punct">:</span> <span class="json-string">"New York"</span><span class="json-punct">,</span>
    <span class="json-key">"pincode"</span><span class="json-punct">:</span> <span class="json-string">"10001"</span><span class="json-punct">,</span>
    <span class="json-key">"country_id"</span><span class="json-punct">:</span> <span class="json-string">"231"</span><span class="json-punct">,</span>
    <span class="json-key">"country_code"</span><span class="json-punct">:</span> <span class="json-string">"US"</span><span class="json-punct">,</span>
    <span class="json-key">"country_name"</span><span class="json-punct">:</span> <span class="json-string">"United States"</span><span class="json-punct">,</span>
    <span class="json-key">"country_flag"</span><span class="json-punct">:</span> <span class="json-string">"https://yoursite.com/assets/template/images/flags/us.png"</span><span class="json-punct">,</span>
    <span class="json-key">"profile_avatar"</span><span class="json-punct">:</span> <span class="json-string">"https://yoursite.com/assets/images/users/avatar.jpg"</span><span class="json-punct">,</span>
    <span class="json-key">"role"</span><span class="json-punct">:</span> <span class="json-string">"admin"</span>
  <span class="json-punct">}</span>
<span class="json-punct">}</span></pre>
            <div class="mt-3">
               <div class="alert alert-warning border-0 py-2 px-3 small mb-0">
                  <i class="bi bi-exclamation-triangle me-1"></i><strong>Note:</strong> When sending avatar, use <code>multipart/form-data</code> content type. Fields <code>firstname</code>, <code>lastname</code>, and <code>email</code> are required; all other fields are optional.
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
