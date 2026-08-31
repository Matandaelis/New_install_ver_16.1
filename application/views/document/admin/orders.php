<div class="top-content" id="admin_orders">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-cart-check me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Orders</h3>
   </div>
   <p class="text-muted mb-4">Read-only combined view of local store orders (<code>order</code>) and integration / external orders (<code>integration_orders</code>). List rows are lightweight; use <code>order_details</code> for line items and history.</p>

   <div class="mb-4" id="admin_orders_list">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>
         Orders List
      </h5>
      <p class="text-muted small mb-3">Each row includes <code>order_type</code>: <code>store</code> (local store) or <code>ex</code> (integration). <code>external_reference</code> is the payment / external reference (<code>txn_id</code> for store, <code>order_id</code> for integration).</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/orders</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                     <tr><td>start_from</td><td><code>integer</code></td><td><code>Query</code></td><td>Pagination offset (default: 0)</td></tr>
                     <tr><td>limit</td><td><code>integer</code></td><td><code>Query</code></td><td>Results per page, max 100 (default: 20)</td></tr>
                     <tr><td>search</td><td><code>string</code></td><td><code>Query</code></td><td>Search id, references, customer username / email / name; for <code>ex</code> also <code>base_url</code> and <code>script_name</code></td></tr>
                     <tr><td>type</td><td><code>string</code></td><td><code>Query</code></td><td><code>all</code>, <code>store</code>, or <code>ex</code> (default: all)</td></tr>
                     <tr><td>status</td><td><code>string</code></td><td><code>Query</code></td><td><code>all</code> or numeric order status code (same field on both order types)</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "orders": [
      {
        "order_type": "store",
        "id": 120,
        "status": 1,
        "user_id": 45,
        "total": "99.00",
        "currency_code": "USD",
        "payment_method": "stripe",
        "external_reference": "pi_abc123",
        "created_at": "2025-06-10 14:22:01",
        "firstname": "Jane",
        "lastname": "Doe",
        "username": "jane",
        "email": "jane@example.com"
      },
      {
        "order_type": "ex",
        "id": 88,
        "status": 1,
        "user_id": 12,
        "total": "45.00",
        "currency_code": "USD",
        "payment_method": "",
        "external_reference": "EXT-7788",
        "created_at": "2025-06-09 09:15:00",
        "firstname": "John",
        "lastname": "Smith",
        "username": "john",
        "email": "john@example.com"
      }
    ],
    "total_count": 200,
    "start_from": 0,
    "limit": 20,
    "has_more": true,
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 1
  }
}</code></pre>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_order_details">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-file-earmark-text text-primary"></i>
         Order Details
      </h5>
      <p class="text-muted small mb-3">For <code>store</code>, includes products, decoded <code>files</code> / <code>comment</code> where applicable, and payment / order history. For <code>ex</code>, includes integration fields, optional <code>campaign_name</code> and <code>program_name</code>, and decoded <code>custom_data</code>.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/order_details</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>true</code></td><td>Admin JWT token (Header)</td></tr>
                     <tr><td>order_type</td><td><code>string</code></td><td><code>true</code></td><td><code>store</code> or <code>ex</code> (must match list row)</td></tr>
                     <tr><td>id</td><td><code>integer</code></td><td><code>true</code></td><td>Primary key in <code>order</code> or <code>integration_orders</code></td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Notes</h6>
            <ul class="text-muted small mb-0">
               <li>Store status labels follow <code>Order_model::$status</code> (complete, pending, refunded, etc.).</li>
               <li>Integration orders use the same numeric <code>status</code> column; meaning aligns with your integration / wallet workflow.</li>
            </ul>
         </div>
      </div>
   </div>
</div>
