<div class="top-content" id="admin_categories">
   <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3"><i class="bi bi-folder2-open me-1"></i>Admin</span>
      <h3 class="page-title mb-0">Integration Categories</h3>
   </div>
   <p class="text-muted mb-4">Read-only list and detail for integration marketing categories (<code>integration_category</code>). Used to classify programs in the integration marketplace.</p>

   <div class="mb-4" id="admin_categories_list">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-list-ul text-primary"></i>
         Categories List
      </h5>
      <p class="text-muted small mb-3">Paginated list with optional search on category or parent name. Timestamps are returned as stored in the database (ISO-style strings).</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/categories</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Position</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>Header</code></td><td>Admin JWT token</td></tr>
                     <tr><td>start_from</td><td><code>integer</code></td><td><code>Query</code></td><td>Pagination offset (default: 0)</td></tr>
                     <tr><td>limit</td><td><code>integer</code></td><td><code>Query</code></td><td>Results per page, max 100 (default: 20)</td></tr>
                     <tr><td>search</td><td><code>string</code></td><td><code>Query</code></td><td>Search by category name or parent category name</td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "categories": [
      {
        "id": 3,
        "name": "Electronics",
        "parent_id": 0,
        "created_at": "2025-01-15 12:30:00",
        "parent_name": null
      }
    ],
    "total_count": 8,
    "start_from": 0,
    "limit": 20,
    "has_more": false,
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 1
  }
}</code></pre>
         </div>
      </div>
   </div>

   <div class="mb-4" id="admin_category_details">
      <h5 class="d-flex align-items-center gap-2 mb-2">
         <i class="bi bi-file-earmark-text text-primary"></i>
         Category Details
      </h5>
      <p class="text-muted small mb-3">Single category row plus <code>children_count</code> (direct child categories).</p>
      <div class="card border-0 shadow-sm rounded-3 mb-3">
         <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
               <span class="badge bg-success">GET</span>
               <code class="text-break"><?=base_url();?>Admin_Api/category_details</code>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mb-2">Parameters</h6>
            <div class="table-responsive">
               <table class="table table-hover table-sm mb-0">
                  <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                  <tbody>
                     <tr><td>Authorization</td><td><code>string</code></td><td><code>true</code></td><td>Admin JWT token (Header)</td></tr>
                     <tr><td>category_id</td><td><code>integer</code></td><td><code>true</code></td><td><code>integration_category.id</code></td></tr>
                  </tbody>
               </table>
            </div>
            <h6 class="text-uppercase small fw-semibold text-muted mt-3 mb-2">Response</h6>
            <pre class="p-3 rounded-3 mb-0" style="background:#1e1e1e;color:#9cdcfe;font-size:13px;overflow-x:auto;"><code>{
  "status": true,
  "data": {
    "id": 3,
    "name": "Electronics",
    "parent_id": 0,
    "created_at": "2025-01-15 12:30:00",
    "parent_name": null,
    "children_count": 2,
    "currency_symbol": "$",
    "currency_code": "USD",
    "enable_shorten_numbers": 1
  }
}</code></pre>
         </div>
      </div>
   </div>
</div>
