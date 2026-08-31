<div class="top-content" id="admin_click_logs">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-mouse2 me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Click / traffic logs</h3>
   </div>
   <p class="text-muted mb-4">Read-only access to the integration click log (<code>integration_clicks_logs</code>). Permission: <code>reports.statistics</code> (same as web admin Income Report). Super admin (id&nbsp;1) always has access.</p>

   <div class="mb-4" id="admin_click_logs_list">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>Click logs list
      </h5>
      <p class="text-muted small mb-3">Paginated log of all affiliate link clicks / actions / views. Newest-first. Each row includes visitor device and geo info joined to the affiliate user.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/click_logs</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                     <tr><td>start_from</td><td><code>int</code></td><td><code>Query</code></td><td>Offset (default 0)</td></tr>
                     <tr><td>limit</td><td><code>int</code></td><td><code>Query</code></td><td>Page size, max 100 (default 20)</td></tr>
                     <tr><td>search</td><td><code>string</code></td><td><code>Query</code></td><td>Search by user name, username, IP, or country code</td></tr>
                     <tr><td>type</td><td><code>string</code></td><td><code>Query</code></td><td><code>all</code> (default), <code>click</code>, <code>action</code>, <code>view</code></td></tr>
                     <tr><td>user_id</td><td><code>int</code></td><td><code>Query</code></td><td>Filter logs for a specific affiliate</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response (sample)</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "logs": [
      {
        "id": 501,
        "click_type": "click",
        "ip": "203.0.113.42",
        "country_code": "US",
        "browser": "Chrome",
        "is_mobile": 0,
        "mobile_name": "",
        "link": "https://example.com/product-page",
        "page_open_time": "1.23",
        "created_at": "2026-03-19 14:22:01",
        "user_id": 5,
        "user_name": "John Smith",
        "user_username": "johnsmith"
      }
    ],
    "total": 2048,
    "has_more": true
  }
}</code></pre>
         </div>
      </div>
      <div class="table-responsive">
         <table class="table table-sm table-hover">
            <thead class="table-light"><tr><th>Field</th><th>Type</th><th>Description</th></tr></thead>
            <tbody>
               <tr><td><code>id</code></td><td><code>int</code></td><td>Log entry ID</td></tr>
               <tr><td><code>click_type</code></td><td><code>string</code></td><td><code>click</code> | <code>action</code> | <code>view</code></td></tr>
               <tr><td><code>ip</code></td><td><code>string</code></td><td>Visitor IP address</td></tr>
               <tr><td><code>country_code</code></td><td><code>string</code></td><td>2-letter ISO country (empty if not resolved)</td></tr>
               <tr><td><code>browser</code></td><td><code>string</code></td><td>Browser name</td></tr>
               <tr><td><code>is_mobile</code></td><td><code>int</code></td><td><code>1</code> if visitor used a mobile device</td></tr>
               <tr><td><code>mobile_name</code></td><td><code>string</code></td><td>Mobile device name when <code>is_mobile = 1</code></td></tr>
               <tr><td><code>link</code></td><td><code>string</code></td><td>Destination URL of the click</td></tr>
               <tr><td><code>page_open_time</code></td><td><code>string</code></td><td>Time the page was open (seconds), if captured</td></tr>
               <tr><td><code>created_at</code></td><td><code>string</code></td><td>Timestamp of the click</td></tr>
               <tr><td><code>user_id</code></td><td><code>int</code></td><td>Affiliate user ID</td></tr>
               <tr><td><code>user_name</code></td><td><code>string</code></td><td>Affiliate full name</td></tr>
               <tr><td><code>user_username</code></td><td><code>string</code></td><td>Affiliate username</td></tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
