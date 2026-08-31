<div class="top-content" id="admin_notifications">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-bell me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Notifications (admin inbox)</h3>
   </div>
   <p class="text-muted mb-4">Rows where <code>notification_viewfor = 'admin'</code> in the <code>notification</code> table. Titles and descriptions are sanitized for API clients. <code>admin_web_url</code> points at the web admin (<code>admincontrol/…</code>) when the stored link is relative.</p>

   <div class="mb-4" id="admin_notifications_list">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>
         Notifications list
      </h5>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/notifications</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>Header</code></td><td>Admin JWT</td></tr>
                     <tr><td>start_from</td><td><code>integer</code></td><td>Offset (default 0)</td></tr>
                     <tr><td>limit</td><td><code>integer</code></td><td>Page size, max 100 (default 20)</td></tr>
                     <tr><td>search</td><td><code>string</code></td><td>Title, description, or type</td></tr>
                     <tr><td>read</td><td><code>string</code></td><td><code>all</code>, <code>unread</code>, or <code>read</code></td></tr>
                     <tr><td>type</td><td><code>string</code></td><td><code>all</code> or exact <code>notification_type</code></td></tr>
                  </tbody>
               </table>
            </div>
            <p class="small text-muted mb-0 mt-3">When <code>start_from</code> is <code>0</code>, the response includes <code>counts</code>: <code>all</code>, <code>unread</code>, and <code>read</code> (same <code>search</code> / <code>type</code> scope as the list). Omitted on later pages to avoid extra aggregate queries while paginating.</p>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_notification_details">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-file-text text-primary"></i>
         Notification details
      </h5>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/notification_details?notification_id=</code>
            </div>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_notification_read">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-check2-circle text-warning"></i>
         Mark one as read
      </h5>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-warning text-dark">POST</span>
               <code class="text-break"><?=base_url();?>Admin_Api/notification_read</code>
            </div>
            <p class="small mb-0">JSON body: <code>notification_id</code> (integer).</p>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_notifications_mark_all_read">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-check-all text-warning"></i>
         Mark all as read
      </h5>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-warning text-dark">POST</span>
               <code class="text-break"><?=base_url();?>Admin_Api/notifications_mark_all_read</code>
            </div>
            <p class="small mb-0">No body. Updates every admin notification where <code>notification_is_read</code> is not <code>1</code>.</p>
         </div>
      </div>
   </div>
</div>
