<div class="top-content" id="admin_settings">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-gear me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Settings summary</h3>
   </div>
   <p class="text-muted mb-4">Read-only overview of public-safe configuration (no SMTP secrets, payment keys, or Telegram tokens). Intended for the mobile admin app. Access: valid admin JWT and admin user type — same as other Admin API GET endpoints.</p>

   <div class="mb-4" id="admin_settings_summary">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-info-circle text-primary"></i>
         Settings summary
      </h5>
      <p class="text-muted small mb-3">Returns grouped <code>site</code>, <code>store</code>, <code>modules</code>, and default <code>language</code>, merged with the same currency helpers as other admin endpoints: <code>currency_symbol</code>, <code>currency_code</code>, <code>enable_shorten_numbers</code>.</p>
      <p class="small text-muted mb-3">Most module and store flags are string <code>"0"</code> / <code>"1"</code> as stored in settings — clients should map them to labels (off/on) if needed. <code>site.favicon_url</code> is only present when a favicon is configured.</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/settings_summary</code>
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
    "summary": {
      "site": {
        "name": "My Store",
        "time_zone": "UTC",
        "session_timeout": "1800",
        "hide_currency_from": "0",
        "block_click_across_browser": "0",
        "cookies_consent": "0",
        "notify_email": "admin@example.com",
        "telegram_enable": "0",
        "favicon_url": "https://example.com/assets/images/site/favicon.ico"
      },
      "store": {
        "status": "1",
        "theme": "0",
        "store_mode": "cart",
        "affiliate_cookie": "30",
        "registration_status": "1",
        "registration_approval": "0",
        "mail_verifiy": "0"
      },
      "modules": {
        "market_tools": "1",
        "referlevel": "1",
        "membership": "0",
        "award_level": "0",
        "vendor_store": "1",
        "vendor_deposit": "0",
        "market_vendor_status": "1",
        "saas_enable": "1"
      },
      "language": { "id": "1", "name": "English", "code": "en" }
    },
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 0
  }
}</code></pre>
            <p class="small text-muted mt-3 mb-0"><strong>Language object:</strong> <code>id</code> is the internal database primary key for the default language row (for integrations/debug only). End-user apps often show only <code>name</code> and, when present, <code>code</code> (included if your <code>language</code> table has a <code>code</code> column).</p>
         </div>
      </div>
   </div>
</div>
