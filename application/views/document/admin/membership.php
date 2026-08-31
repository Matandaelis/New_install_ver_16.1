<div class="top-content" id="admin_membership">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-card-membership me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Membership</h3>
   </div>
   <p class="text-muted mb-4">Read-only access to membership plans and purchase orders. Permission: <code>reports.orders</code> (same as web admin Membership Orders). Super admin (id&nbsp;1) always has access.</p>

   <!-- Plans -->
   <div class="mb-5" id="admin_membership_plans">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>Plans list
      </h5>
      <p class="text-muted small mb-3">Returns all membership plans in the database (no paging — the list is typically small). Sorted newest-first.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/membership_plans</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response (sample)</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "plans": [
      {
        "id": 2,
        "name": "Pro Monthly",
        "price": "29.00",
        "special_price": "19.00",
        "bonus": "5.00",
        "total_day": 30,
        "billing_period": "monthly",
        "billing_label": "Per Month",
        "is_lifetime": 0,
        "has_trial": 1,
        "trial_days": 7,
        "plan_level": 2,
        "status": 1
      }
    ],
    "total": 1
  }
}</code></pre>
         </div>
      </div>
      <div class="table-responsive mb-3">
         <table class="table table-sm table-hover">
            <thead class="table-light"><tr><th>Field</th><th>Type</th><th>Description</th></tr></thead>
            <tbody>
               <tr><td><code>id</code></td><td><code>int</code></td><td>Plan ID</td></tr>
               <tr><td><code>name</code></td><td><code>string</code></td><td>Plan name</td></tr>
               <tr><td><code>price</code></td><td><code>string</code></td><td>Regular price</td></tr>
               <tr><td><code>special_price</code></td><td><code>string|null</code></td><td>Discounted price when set</td></tr>
               <tr><td><code>bonus</code></td><td><code>string</code></td><td>Wallet bonus on activation</td></tr>
               <tr><td><code>total_day</code></td><td><code>int</code></td><td>Plan duration in days (0 = lifetime)</td></tr>
               <tr><td><code>billing_period</code></td><td><code>string</code></td><td>Raw period key (e.g. <code>monthly</code>, <code>yearly</code>, <code>lifetime_free</code>, <code>custom</code>)</td></tr>
               <tr><td><code>billing_label</code></td><td><code>string</code></td><td>Human-readable period label</td></tr>
               <tr><td><code>is_lifetime</code></td><td><code>int</code></td><td><code>1</code> when billing_period is <code>lifetime_free</code></td></tr>
               <tr><td><code>has_trial</code></td><td><code>int</code></td><td><code>1</code> when a trial period is configured</td></tr>
               <tr><td><code>trial_days</code></td><td><code>int</code></td><td>Number of free trial days</td></tr>
               <tr><td><code>plan_level</code></td><td><code>int|null</code></td><td>Award level number linked to this plan</td></tr>
               <tr><td><code>status</code></td><td><code>int</code></td><td>Plan status: <code>1</code> = active</td></tr>
            </tbody>
         </table>
      </div>
   </div>

   <!-- Orders -->
   <div class="mb-4" id="admin_membership_orders">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-receipt text-primary"></i>Orders list
      </h5>
      <p class="text-muted small mb-3">Paginated membership purchase history with user and plan info. Mirrors <strong>web admin → Membership → Orders</strong>.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/membership_orders</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                     <tr><td>start_from</td><td><code>int</code></td><td><code>Query</code></td><td>Offset (default 0)</td></tr>
                     <tr><td>limit</td><td><code>int</code></td><td><code>Query</code></td><td>Page size, max 100 (default 20)</td></tr>
                     <tr><td>search</td><td><code>string</code></td><td><code>Query</code></td><td>Search by user name / username</td></tr>
                     <tr><td>status</td><td><code>string</code></td><td><code>Query</code></td><td><code>all</code> (default) or a numeric status_id: <code>0</code>=pending, <code>1</code>=active, <code>3</code>=denied, <code>4</code>=expired, <code>5</code>=failed, <code>7</code>=processed, <code>8</code>=refunded</td></tr>
                     <tr><td>user_id</td><td><code>int</code></td><td><code>Query</code></td><td>Filter orders for a specific user</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response (sample)</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "orders": [
      {
        "id": 14,
        "user_id": 3,
        "user_name": "Jane Doe",
        "user_username": "jane",
        "plan_id": 2,
        "plan_name": "Pro Monthly",
        "plan_billing": "monthly",
        "total": "19.00",
        "bonus_commission": "5.00",
        "status_id": 1,
        "status_label": "active",
        "is_active": 1,
        "is_lifetime": 0,
        "total_day": 30,
        "payment_method": "stripe",
        "started_at": "2026-03-01 10:00:00",
        "expire_at": "2026-04-01 10:00:00",
        "created_at": "2026-03-01 09:59:45"
      }
    ],
    "total": 1,
    "has_more": false
  }
}</code></pre>
         </div>
      </div>
   </div>
</div>
