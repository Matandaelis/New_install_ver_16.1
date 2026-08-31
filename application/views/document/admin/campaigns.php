<div class="top-content" id="admin_campaigns">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-megaphone me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Integration Campaigns</h3>
   </div>
   <p class="text-muted mb-4">Browse and inspect integration campaigns (marketing tools / ads). Each campaign belongs to a program and inherits commission settings from that program.</p>

   <div class="mb-4" id="admin_campaigns_list">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>
         Campaigns List
      </h5>
      <p class="text-muted small mb-3">Retrieve a paginated, searchable list of integration campaigns (<code>integration_tools</code>) with optional status filter.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/campaigns</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                     <tr><td>start_from</td><td><code>integer</code></td><td><code>Query</code></td><td>Pagination offset (default: 0)</td></tr>
                     <tr><td>limit</td><td><code>integer</code></td><td><code>Query</code></td><td>Results per page, max 100 (default: 20)</td></tr>
                     <tr><td>search</td><td><code>string</code></td><td><code>Query</code></td><td>Search by campaign name, program name, or vendor username</td></tr>
                     <tr><td>status</td><td><code>string</code></td><td><code>Query</code></td><td><code>all</code>, <code>active</code>, or <code>inactive</code> (default: all)</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "campaigns": [
      {
        "id": "18",
        "program_id": "5",
        "vendor_id": "12",
        "name": "Summer Sale Banner",
        "type": "banner_ads",
        "tool_type": "banner",
        "status": "1",
        "created_at": "2025-06-01 10:00:00",
        "program_name": "Fashion Affiliate Program",
        "vendor_username": "vendor_user",
        "stat_sales_count": "12",
        "stat_clicks_count": "340",
        "stat_orders_total": "1250.50"
      }
    ],
    "total_count": 45,
    "start_from": 0,
    "limit": 20,
    "has_more": true,
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 1
  }
}</code></pre>
            <p class="text-muted small mt-2 mb-0">List rows include <code>stat_sales_count</code> (rows in <code>integration_orders</code>), <code>stat_clicks_count</code> (<code>_af_product_click</code> in <code>integration_clicks_action</code>), and <code>stat_orders_total</code> (sum of <code>integration_orders.total</code>).</p>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_campaign_details">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-file-earmark-text text-primary"></i>
         Campaign Details
      </h5>
      <p class="text-muted small mb-3">Retrieve full details for a single campaign including ad creatives, program commission fields, and vendor info.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/campaign_details</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>true</code></td><td>Admin JWT token (Header)</td></tr>
                     <tr><td>campaign_id</td><td><code>integer</code></td><td><code>true</code></td><td>The ID of the campaign (<code>integration_tools.id</code>)</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "id": "18",
    "name": "Summer Sale Banner",
    "type": "banner_ads",
    "tool_type": "banner",
    "status": "1",
    "program_id": "5",
    "program_name": "Fashion Affiliate Program",
    "vendor_id": "12",
    "vendor_name": "John Doe",
    "username": "vendor_user",
    "commission_type": "percentage",
    "commission_sale": "10",
    "sale_status": "1",
    "commission": {},
    "ads": [],
    "created_at": "2025-06-01 10:00:00",
    "currency_symbol": "$",
    "currency_code": "USD"
  }
}</code></pre>
         </div>
      </div>
   </div>

</div>
