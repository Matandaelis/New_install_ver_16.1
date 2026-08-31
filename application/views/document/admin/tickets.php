<div class="top-content" id="admin_tickets">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-headset me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Support tickets</h3>
   </div>
   <p class="text-muted mb-4">List and manage support tickets (<code>tickets</code>, <code>tickets_reply</code>, <code>tickets_subject</code>). <strong>GET</strong> endpoints are read-only; <strong>POST</strong> <code>ticket_reply</code> and <code>ticket_status</code> mirror web admin behaviour (email + in-app notifications where configured).</p>

   <div class="mb-4" id="admin_ticket_subjects">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-tags text-primary"></i>
         Ticket subjects
      </h5>
      <p class="text-muted small mb-3">Lookup list for filter dropdowns (id + subject label).</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/ticket_subjects</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Response (excerpt)</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "subjects": [ { "id": 1, "subject": "Billing" } ],
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 1
  }
}</code></pre>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_tickets_list">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>
         Tickets list
      </h5>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/tickets</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>Header</code></td><td>Admin JWT</td></tr>
                     <tr><td>start_from</td><td><code>integer</code></td><td>Offset (default 0)</td></tr>
                     <tr><td>limit</td><td><code>integer</code></td><td>Page size, max 100 (default 20)</td></tr>
                     <tr><td>search</td><td><code>string</code></td><td>Ticket id, user name, email, username, subject text</td></tr>
                     <tr><td>status</td><td><code>string</code></td><td><code>all</code> or <code>1</code> open, <code>2</code> pending, <code>3</code> closed</td></tr>
                     <tr><td>subject_id</td><td><code>string</code></td><td><code>all</code> or numeric <code>tickets_subject.id</code></td></tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_ticket_details">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-chat-dots text-primary"></i>
         Ticket details &amp; thread
      </h5>
      <p class="text-muted small mb-3">Returns <code>ticket</code> (header row) and <code>replies</code> in chronological order. <code>user_type</code> on a reply: <code>1</code> = admin/staff, <code>2</code> = end user. <code>attachments</code> exposes safe filenames + absolute <code>url</code> (web uploads).</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/ticket_details?ticket_id=</code>
            </div>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_ticket_reply">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-reply-fill text-warning"></i>
         Admin reply
      </h5>
      <p class="text-muted small mb-3">Plain-text body only (no multipart upload). Re-opens a closed ticket to <strong>open</strong> when the admin replies, same as web admin.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-warning text-dark">POST</span>
               <code class="text-break"><?=base_url();?>Admin_Api/ticket_reply</code>
            </div>
            <p class="small mb-0">JSON body: <code>ticket_id</code> (string), <code>message</code> (string).</p>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_ticket_status">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-toggle2-on text-warning"></i>
         Ticket status
      </h5>
      <p class="text-muted small mb-3">Sets <code>tickets.status</code> and triggers the same mail / notification hooks as the web admin.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-warning text-dark">POST</span>
               <code class="text-break"><?=base_url();?>Admin_Api/ticket_status</code>
            </div>
            <p class="small mb-0">JSON body: <code>ticket_id</code> (string), <code>status</code> — <code>1</code> open, <code>2</code> pending, <code>3</code> closed.</p>
         </div>
      </div>
   </div>
</div>
