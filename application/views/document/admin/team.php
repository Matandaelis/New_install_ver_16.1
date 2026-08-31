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

<div class="page-intro top-content" id="admin_team">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-primary rounded-pill px-3 py-2">
         <i class="bi bi-shield-lock me-1"></i>Admin — team (read-only)
      </span>
   </div>
   <p class="text-muted mb-0">Read-only views for <strong>admin panel accounts</strong> (<code>users.type = 'admin'</code>) and <strong>admin roles</strong>, aligned with web <code>admincontrol/admin_user</code> and <code>admincontrol/admin_roles</code>. All three endpoints require the same permission as the web screens: <code>admin.admins</code> (<em>Manage Admin Users</em>). No create, update, or delete via API.</p>
</div>

<!-- Admin staff list -->
<div class="top-content mt-4" id="admin_admin_staff_list">
   <h5 class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-person-badge text-primary"></i>
      <span>Admin staff list</span>
   </h5>
   <p class="text-muted small mb-3">Paginated list of admin accounts with optional search (firstname, lastname, username, email). When <code>admin_roles</code> exists, each row includes <code>role_name</code> and <code>role_slug</code>.</p>
</div>
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
   <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
         <span class="badge bg-success px-2 py-1"><i class="bi bi-arrow-right-circle me-1"></i>GET</span>
         <code class="text-dark fs-6"><?=base_url();?>Admin_Api/admin_staff</code>
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
                        <th class="fw-semibold small">Description</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr class="border-bottom"><td><code>Authorization</code></td><td>Header</td><td>Admin JWT</td></tr>
                     <tr class="border-bottom"><td><code>start_from</code></td><td>int</td><td>Offset (default 0)</td></tr>
                     <tr class="border-bottom"><td><code>limit</code></td><td>int</td><td>Page size, max 100 (default 20)</td></tr>
                     <tr class="border-bottom"><td><code>search</code></td><td>string</td><td>Optional</td></tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-code-slash me-1"></i>Response <code>data</code></h6>
            <pre class="api-response-block"><span class="json-punct">{</span><span class="json-key">"staff"</span><span class="json-punct">:[],</span><span class="json-key">"total_count"</span><span class="json-punct">:</span><span class="json-number">0</span><span class="json-punct">,</span><span class="json-key">"start_from"</span><span class="json-punct">:</span><span class="json-number">0</span><span class="json-punct">,</span><span class="json-key">"limit"</span><span class="json-punct">:</span><span class="json-number">20</span><span class="json-punct">,</span><span class="json-key">"has_more"</span><span class="json-punct">:</span><span class="json-bool">false</span><span class="json-punct">}</span></pre>
         </div>
      </div>
   </div>
</div>

<!-- Admin staff details -->
<div class="top-content mt-4" id="admin_admin_staff_details">
   <h5 class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-person-lines-fill text-primary"></i>
      <span>Admin staff details</span>
   </h5>
   <p class="text-muted small mb-3">Single admin row (no password or token). Includes <code>admin_role_label</code>, <code>admin_permissions</code> (slug array when resolved from role), and <code>is_super_admin</code> when <code>id === 1</code>, same resolution as web helper when <code>admin_roles</code> exists.</p>
</div>
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
   <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
         <span class="badge bg-success px-2 py-1"><i class="bi bi-arrow-right-circle me-1"></i>GET</span>
         <code class="text-dark fs-6"><?=base_url();?>Admin_Api/admin_staff_details</code>
      </div>
   </div>
   <div class="card-body">
      <div class="row g-4">
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-sliders me-1"></i>Parameters</h6>
            <table class="table table-sm table-borderless">
               <tbody>
                  <tr class="border-bottom"><td><code>Authorization</code></td><td>Header — Admin JWT</td></tr>
                  <tr class="border-bottom"><td><code>user_id</code></td><td>Required — admin user id</td></tr>
               </tbody>
            </table>
         </div>
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-code-slash me-1"></i>Errors</h6>
            <p class="small text-muted mb-0"><code>status: false</code> — missing <code>user_id</code>, not found, forbidden without <code>admin.admins</code>, or not an admin account.</p>
         </div>
      </div>
   </div>
</div>

<!-- Admin roles list -->
<div class="top-content mt-4" id="admin_admin_roles_list">
   <h5 class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-diagram-3 text-primary"></i>
      <span>Admin roles list</span>
   </h5>
   <p class="text-muted small mb-3">All rows from <code>admin_roles</code> with <code>permissions</code> as a JSON array of slugs and <code>admin_user_count</code> (admins assigned to that role). If the table is missing, <code>admin_roles_table_exists</code> is <code>false</code> and <code>roles</code> is empty.</p>
</div>
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
   <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
         <span class="badge bg-success px-2 py-1"><i class="bi bi-arrow-right-circle me-1"></i>GET</span>
         <code class="text-dark fs-6"><?=base_url();?>Admin_Api/admin_roles</code>
      </div>
   </div>
   <div class="card-body">
      <div class="row g-4">
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-sliders me-1"></i>Parameters</h6>
            <p class="small text-muted mb-0"><code>Authorization</code> header only.</p>
         </div>
         <div class="col-lg-6">
            <h6 class="text-uppercase text-muted small mb-2"><i class="bi bi-code-slash me-1"></i>Response <code>data</code></h6>
            <pre class="api-response-block"><span class="json-punct">{</span><span class="json-key">"admin_roles_table_exists"</span><span class="json-punct">:</span><span class="json-bool">true</span><span class="json-punct">,</span><span class="json-key">"roles"</span><span class="json-punct">:[{</span><span class="json-key">"id"</span><span class="json-punct">:</span><span class="json-string">"1"</span><span class="json-punct">,</span><span class="json-key">"name"</span><span class="json-punct">:</span><span class="json-string">"Support"</span><span class="json-punct">,</span><span class="json-key">"slug"</span><span class="json-punct">:</span><span class="json-string">"support"</span><span class="json-punct">,</span><span class="json-key">"permissions"</span><span class="json-punct">:[</span><span class="json-string">"dashboard"</span><span class="json-punct">],</span><span class="json-key">"admin_user_count"</span><span class="json-punct">:</span><span class="json-number">2</span><span class="json-punct">,</span><span class="json-key">"created_at"</span><span class="json-punct">:</span><span class="json-string">""</span><span class="json-punct">}]}</span></pre>
         </div>
      </div>
   </div>
</div>
