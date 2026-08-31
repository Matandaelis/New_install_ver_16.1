<div class="top-content" id="admin_withdrawals">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-cash-stack me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Withdrawals</h3>
   </div>
   <p class="text-muted mb-4">Manage withdrawal requests from affiliates. View pending/paid/rejected requests and approve or reject them.</p>

   <!-- Withdrawal Requests List -->
   <div class="mb-4" id="admin_withdrawals_list">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>
         Withdrawal Requests List
      </h5>
      <p class="text-muted small mb-3">Retrieve a paginated list of withdrawal requests with status filter.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/withdrawals</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                     <tr><td>start_from</td><td><code>integer</code></td><td><code>Query</code></td><td>Pagination offset (default: 0)</td></tr>
                     <tr><td>limit</td><td><code>integer</code></td><td><code>Query</code></td><td>Results per page (default: 20)</td></tr>
                     <tr><td>status</td><td><code>string</code></td><td><code>Query</code></td><td><code>unpaid</code>, <code>paid</code>, or <code>rejected</code> (default: unpaid)</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "requests": [
      {
        "id": "3",
        "tran_ids": "17",
        "amount": "37.5",
        "status": "0",
        "user_id": "4",
        "payment_method": "bank_transfer",
        "status_label": "Received",
        "payment_details": {
          "bank_name": "My Bank",
          "account_number": "123456789",
          "account_name": "John Doe"
        },
        "created_at": "2026-02-23 08:43:20",
        "username": "johndoe",
        "email": "john@example.com",
        "firstname": "John",
        "lastname": "Doe"
      }
    ],
    "total_count": 2,
    "start_from": 0,
    "limit": 20,
    "has_more": false,
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 1
  }
}</code></pre>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Status Codes</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Code</th><th>Label</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td><code>0</code></td><td>Received</td><td>Pending / waiting for admin action</td></tr>
                     <tr><td><code>1</code></td><td>Paid</td><td>Payment completed</td></tr>
                     <tr><td><code>2</code></td><td>Processing</td><td>Payment in progress</td></tr>
                     <tr><td><code>3</code></td><td>Cancelled</td><td>Request cancelled</td></tr>
                     <tr><td><code>4</code></td><td>Declined</td><td>Request declined by admin</td></tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>

   <!-- Update Withdrawal Status -->
   <div class="mb-4" id="admin_update_withdrawal_status">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-pencil-square text-primary"></i>
         Update Withdrawal Status
      </h5>
      <p class="text-muted small mb-3">Approve or reject a withdrawal request by its ID. Optionally include an admin note.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-warning text-dark">POST</span>
               <code class="text-break"><?=base_url();?>Admin_Api/update_withdrawal_status</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>true</code></td><td>Admin JWT token (Header)</td></tr>
                     <tr><td>request_id</td><td><code>integer</code></td><td><code>true</code></td><td>The ID of the withdrawal request</td></tr>
                     <tr><td>status</td><td><code>string</code></td><td><code>true</code></td><td><code>paid</code> or <code>rejected</code></td></tr>
                     <tr><td>admin_note</td><td><code>string</code></td><td><code>false</code></td><td>Optional note from admin</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{"status":true,"message":"Withdrawal request approved successfully"}</code></pre>
         </div>
      </div>
   </div>

</div>
