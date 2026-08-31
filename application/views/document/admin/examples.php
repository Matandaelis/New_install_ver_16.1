<!-- Implementation Examples - Live Demos -->
<div class="top-content" id="admin_examples">
   <div class="row mb-4">
      <div class="col-12">
         <div class="bg-gradient rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
            <div class="d-flex align-items-center">
               <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                  <i class="bi bi-play-circle display-6 text-white"></i>
               </div>
               <div>
                  <h2 class="display-6 fw-bold text-white mb-0">Live Implementation Examples</h2>
                  <p class="text-white-50 mb-0">Working demos connected to your Admin API - login and see real data</p>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Connection Setup -->
   <div class="card border-0 shadow-sm mb-4" id="example_login">
      <div class="card-header bg-dark text-white">
         <h5 class="mb-0"><i class="bi bi-plug me-2"></i>Step 1: Connect to your API</h5>
      </div>
      <div class="card-body">
         <div class="row g-3 align-items-end">
            <div class="col-md-5">
               <label class="form-label fw-bold">API Base URL</label>
               <input type="text" id="exApiBase" class="form-control" value="<?=base_url();?>" placeholder="https://your-domain.com/">
            </div>
            <div class="col-md-3">
               <label class="form-label fw-bold">Admin Username</label>
               <input type="text" id="exUsername" class="form-control" placeholder="admin">
            </div>
            <div class="col-md-3">
               <label class="form-label fw-bold">Admin Password</label>
               <input type="password" id="exPassword" class="form-control" placeholder="password">
            </div>
            <div class="col-md-1">
               <button class="btn btn-success w-100" id="exConnectBtn" onclick="exConnect()">
                  <i class="bi bi-lightning-fill"></i>
               </button>
            </div>
         </div>
         <div id="exConnectionStatus" class="mt-3"></div>
      </div>
   </div>
</div>

<!-- Example 1: Dashboard Stats -->
<div class="top-content" id="example_dashboard">
   <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
         <h5 class="mb-0"><i class="bi bi-speedometer2 text-success me-2"></i>Dashboard Stats Cards</h5>
         <div>
            <span class="badge bg-success me-2" id="dashStatus">Waiting for connection</span>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#dashCode">
               <i class="bi bi-code-slash me-1"></i>View Code
            </button>
         </div>
      </div>
      <!-- Live Demo -->
      <div class="card-body">
         <!-- Admin Balance -->
         <div class="row g-3 mb-3" id="exDashCards">
            <div class="col-12">
               <div class="card border-0 bg-dark bg-gradient text-white p-3 rounded-3">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <small class="text-white-50"><i class="bi bi-wallet2 me-1"></i>Total Admin Balance</small>
                        <h2 class="mb-0 ex-dash-val" data-field="admin_balance" data-prefix="currency">--</h2>
                     </div>
                     <div class="text-end">
                        <span class="badge bg-success bg-opacity-25 text-white px-2 py-1"><i class="bi bi-arrow-up-short"></i><span class="ex-dash-val" data-field="admin_balance_growth">--</span>% this week</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Users Row -->
         <div class="row g-3 mb-3">
            <div class="col-md-3">
               <div class="card border-0 bg-primary bg-opacity-10 text-center p-3 rounded-3">
                  <i class="bi bi-people display-6 text-primary"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="total_users">--</h3>
                  <small class="text-muted">Total Users</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-secondary bg-opacity-10 text-center p-3 rounded-3">
                  <i class="bi bi-link-45deg display-6 text-secondary"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="total_affiliates">--</h3>
                  <small class="text-muted">Affiliates</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-info bg-opacity-10 text-center p-3 rounded-3">
                  <i class="bi bi-shop display-6 text-info"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="total_vendors">--</h3>
                  <small class="text-muted">Vendors</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-warning bg-opacity-10 text-center p-3 rounded-3">
                  <i class="bi bi-person-plus display-6 text-warning"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="pending_users">--</h3>
                  <small class="text-muted">Pending Users</small>
               </div>
            </div>
         </div>
         <!-- Sales & Commissions Row -->
         <div class="row g-3 mb-3">
            <div class="col-md-3">
               <div class="card border-0 bg-success bg-opacity-10 text-center p-3 rounded-3">
                  <i class="bi bi-bag-check display-6 text-success"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="store_sales" data-prefix="currency">--</h3>
                  <small class="text-muted">Store Sales</small>
                  <small class="text-muted d-block"><span class="ex-dash-val" data-field="store_orders">--</span> orders</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-primary bg-opacity-10 text-center p-3 rounded-3">
                  <i class="bi bi-shop-window display-6 text-primary"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="vendor_sales" data-prefix="currency">--</h3>
                  <small class="text-muted">Vendor Sales</small>
                  <small class="text-muted d-block"><span class="ex-dash-val" data-field="vendor_orders">--</span> orders</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-danger bg-opacity-10 text-center p-3 rounded-3">
                  <i class="bi bi-cash-stack display-6 text-danger"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="store_commission" data-prefix="currency">--</h3>
                  <small class="text-muted">Store Commissions</small>
                  <small class="text-muted d-block">(admin + affiliate)</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-secondary bg-opacity-10 text-center p-3 rounded-3">
                  <i class="bi bi-cursor-fill display-6 text-secondary"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="click_commission" data-prefix="currency">--</h3>
                  <small class="text-muted">Click Commission</small>
                  <small class="text-muted d-block"><span class="ex-dash-val" data-field="total_clicks">--</span> clicks</small>
               </div>
            </div>
         </div>
         <!-- Today & Withdrawals Row -->
         <div class="row g-3">
            <div class="col-md-3">
               <div class="card border-0 bg-light text-center p-3 rounded-3">
                  <i class="bi bi-hand-index display-6 text-success"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="today_clicks">--</h3>
                  <small class="text-muted">Today's Clicks</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-light text-center p-3 rounded-3">
                  <i class="bi bi-cart-check display-6 text-primary"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="today_sales">--</h3>
                  <small class="text-muted">Today's Sales</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-light text-center p-3 rounded-3">
                  <i class="bi bi-hourglass-split display-6 text-warning"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="pending_withdrawals">--</h3>
                  <small class="text-muted">Pending Payouts</small>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-light text-center p-3 rounded-3">
                  <i class="bi bi-cash-coin display-6 text-danger"></i>
                  <h3 class="mt-2 mb-0 ex-dash-val" data-field="total_withdrawals_amount" data-prefix="currency">--</h3>
                  <small class="text-muted">Total Withdrawals</small>
               </div>
            </div>
         </div>
      </div>
      <!-- Code (collapsed) -->
      <div class="collapse" id="dashCode">
         <div class="card-footer bg-dark p-0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2">
               <small class="text-white-50">dashboard-cards.html</small>
               <button class="btn btn-sm btn-outline-light copy-code-btn" data-target="dashCodeBlock">Copy</button>
            </div>
            <pre class="mb-0 p-3" id="dashCodeBlock" style="background:#1e1e1e;color:#d4d4d4;max-height:400px;overflow:auto;font-size:13px;"><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;Admin Dashboard&lt;/title&gt;
  &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
&lt;/head&gt;
&lt;body class="bg-light p-4"&gt;

  &lt;div class="row g-3" id="statsCards"&gt;&lt;/div&gt;

  &lt;script&gt;
    const API_BASE = 'https://your-domain.com/';
    const TOKEN = 'your-admin-jwt-token';

    async function loadDashboard() {
      const res = await fetch(API_BASE + 'Admin_Api/dashboard', {
        headers: { 'Authorization': TOKEN }
      });
      const json = await res.json();

      if (json.status) {
        const d = json.data;
        const cs = d.currency_symbol || '$';
        const sh = parseInt(d.enable_shorten_numbers) || 0;
        function fmt(v) {
          const n = parseFloat(v) || 0;
          if (sh === 1) {
            if (n &gt;= 1000000) return cs + (n/1000000).toFixed(1) + 'M';
            if (n &gt;= 1000) return cs + (n/1000).toFixed(1) + 'k';
          }
          return cs + n.toFixed(2);
        }
        const stats = [
          { label: 'Admin Balance',    value: fmt(d.admin_balance) + ' (' + d.admin_balance_growth + '%)', color: 'dark' },
          { label: 'Total Users',      value: d.total_users,             color: 'primary' },
          { label: 'Affiliates',       value: d.total_affiliates,        color: 'secondary' },
          { label: 'Vendors',          value: d.total_vendors,           color: 'info' },
          { label: 'Store Sales',      value: fmt(d.store_sales) + ' (' + d.store_orders + ' orders)', color: 'success' },
          { label: 'Vendor Sales',     value: fmt(d.vendor_sales) + ' (' + d.vendor_orders + ' orders)', color: 'primary' },
          { label: 'Store Commissions (admin+affiliate)', value: fmt(d.store_commission),  color: 'danger' },
          { label: 'Click Commission', value: fmt(d.click_commission) + ' (' + d.total_clicks + ' clicks)', color: 'secondary' },
          { label: 'Today Clicks',     value: d.today_clicks,            color: 'success' },
          { label: 'Today Sales',      value: d.today_sales,             color: 'primary' },
          { label: 'Pending Users',    value: d.pending_users,           color: 'warning' },
          { label: 'Withdrawals',      value: fmt(d.total_withdrawals_amount) + ' (' + d.pending_withdrawals + ' pending)', color: 'danger' },
        ];

        document.getElementById('statsCards').innerHTML = stats.map(s =&gt;
          '&lt;div class="col-md-3"&gt;' +
          '&lt;div class="card border-0 bg-' + s.color + ' bg-opacity-10 text-center p-3"&gt;' +
          '&lt;h3&gt;' + s.value + '&lt;/h3&gt;' +
          '&lt;small class="text-muted"&gt;' + s.label + '&lt;/small&gt;' +
          '&lt;/div&gt;&lt;/div&gt;'
        ).join('');
      }
    }

    loadDashboard();
  &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
         </div>
      </div>
   </div>
</div>

<!-- Example 2: Users Table -->
<div class="top-content" id="example_users">
   <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
         <h5 class="mb-0"><i class="bi bi-people text-success me-2"></i>Users Management</h5>
         <div>
            <span class="badge bg-success me-2" id="usersStatus">Waiting for connection</span>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#usersCode">
               <i class="bi bi-code-slash me-1"></i>View Code
            </button>
         </div>
      </div>
      <!-- Live Demo -->
      <div class="card-body">
         <div class="d-flex gap-2 mb-3">
            <input type="text" id="exUserSearch" class="form-control form-control-sm" placeholder="Search users..." style="width:220px" oninput="exLoadUsers()">
            <select id="exUserStatus" class="form-select form-select-sm" style="width:130px" onchange="exLoadUsers()">
               <option value="all">All</option>
               <option value="active">Active</option>
               <option value="pending">Pending</option>
               <option value="blocked">Blocked</option>
            </select>
         </div>
         <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
               <thead class="table-light">
                  <tr><th>Name</th><th>Email</th><th>Balance</th><th>Clicks</th><th>Sales</th><th>Type</th><th>Status</th><th>Action</th></tr>
               </thead>
               <tbody id="exUsersBody">
                  <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-plug me-1"></i>Connect to API first</td></tr>
               </tbody>
            </table>
         </div>
         <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted" id="exUsersCount"></small>
            <div>
               <button class="btn btn-sm btn-outline-primary" id="exUserPrev" onclick="exUserPage(-1)" disabled>Previous</button>
               <button class="btn btn-sm btn-outline-primary" id="exUserNext" onclick="exUserPage(1)" disabled>Next</button>
            </div>
         </div>
      </div>
      <!-- Code -->
      <div class="collapse" id="usersCode">
         <div class="card-footer bg-dark p-0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2">
               <small class="text-white-50">users-table.html</small>
               <button class="btn btn-sm btn-outline-light copy-code-btn" data-target="usersCodeBlock">Copy</button>
            </div>
            <pre class="mb-0 p-3" id="usersCodeBlock" style="background:#1e1e1e;color:#d4d4d4;max-height:400px;overflow:auto;font-size:13px;"><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;Users Management&lt;/title&gt;
  &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
&lt;/head&gt;
&lt;body class="bg-light p-4"&gt;

  &lt;div class="d-flex gap-2 mb-3"&gt;
    &lt;input type="text" id="search" class="form-control" placeholder="Search users..." style="width:220px" oninput="loadUsers()"&gt;
    &lt;select id="statusFilter" class="form-select" style="width:130px" onchange="loadUsers()"&gt;
      &lt;option value="all"&gt;All&lt;/option&gt;
      &lt;option value="active"&gt;Active&lt;/option&gt;
      &lt;option value="pending"&gt;Pending&lt;/option&gt;
      &lt;option value="blocked"&gt;Blocked&lt;/option&gt;
    &lt;/select&gt;
  &lt;/div&gt;

  &lt;table class="table table-hover"&gt;
    &lt;thead&gt;&lt;tr&gt;&lt;th&gt;Name&lt;/th&gt;&lt;th&gt;Email&lt;/th&gt;&lt;th&gt;Balance&lt;/th&gt;&lt;th&gt;Status&lt;/th&gt;&lt;th&gt;Action&lt;/th&gt;&lt;/tr&gt;&lt;/thead&gt;
    &lt;tbody id="usersTable"&gt;&lt;/tbody&gt;
  &lt;/table&gt;

  &lt;script&gt;
    const API_BASE = 'https://your-domain.com/';
    const TOKEN = 'your-admin-jwt-token';
    let page = 0;

    async function loadUsers() {
      const params = new URLSearchParams({
        start_from: page * 20,
        limit: 20,
        search: document.getElementById('search').value,
        status: document.getElementById('statusFilter').value
      });

      const res = await fetch(API_BASE + 'Admin_Api/users?' + params, {
        headers: { 'Authorization': TOKEN }
      });
      const json = await res.json();

      if (json.status) {
        const cs = json.data.currency_symbol || '$';
        const sh = parseInt(json.data.enable_shorten_numbers) || 0;
        function fmt(v) {
          const n = parseFloat(v) || 0;
          if (sh === 1) {
            if (n &gt;= 1000000) return cs + (n/1000000).toFixed(1) + 'M';
            if (n &gt;= 1000) return cs + (n/1000).toFixed(1) + 'k';
          }
          return cs + n.toFixed(2);
        }
        document.getElementById('usersTable').innerHTML = json.data.users.map(u =&gt; {
          const badge = u.status === '1' ? 'success' : 'danger';
          const label = u.status === '1' ? 'Active' : 'Disabled';
          return '&lt;tr&gt;' +
            '&lt;td&gt;' + u.firstname + ' ' + u.lastname + '&lt;/td&gt;' +
            '&lt;td&gt;' + u.email + '&lt;/td&gt;' +
            '&lt;td&gt;' + fmt(u.balance) + '&lt;/td&gt;' +
            '&lt;td&gt;&lt;span class="badge bg-' + badge + '"&gt;' + label + '&lt;/span&gt;&lt;/td&gt;' +
            '&lt;td&gt;&lt;button class="btn btn-sm btn-outline-primary" onclick="toggleUser(\'' + u.id + '\')"&gt;Toggle&lt;/button&gt;&lt;/td&gt;' +
            '&lt;/tr&gt;';
        }).join('');
      }
    }

    async function toggleUser(userId) {
      const fd = new FormData();
      fd.append('user_id', userId);

      await fetch(API_BASE + 'Admin_Api/update_user_status', {
        method: 'POST',
        headers: { 'Authorization': TOKEN },
        body: fd
      });
      loadUsers();
    }

    loadUsers();
  &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
         </div>
      </div>
   </div>
</div>

<!-- Example 3: Withdrawals -->
<div class="top-content" id="example_withdrawals">
   <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
         <h5 class="mb-0"><i class="bi bi-cash-stack text-success me-2"></i>Withdrawal Requests</h5>
         <div>
            <select id="exWdFilter" class="form-select form-select-sm d-inline-block me-2" style="width:130px" onchange="exLoadWithdrawals()">
               <option value="unpaid">Pending</option>
               <option value="paid">Paid</option>
               <option value="rejected">Rejected</option>
            </select>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#wdCode">
               <i class="bi bi-code-slash me-1"></i>View Code
            </button>
         </div>
      </div>
      <!-- Live Demo -->
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
               <thead class="table-light">
                  <tr><th>User</th><th>Amount</th><th>Method</th><th>Date</th><th>Actions</th></tr>
               </thead>
               <tbody id="exWdBody">
                  <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-plug me-1"></i>Connect to API first</td></tr>
               </tbody>
            </table>
         </div>
      </div>
      <!-- Code -->
      <div class="collapse" id="wdCode">
         <div class="card-footer bg-dark p-0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2">
               <small class="text-white-50">withdrawals.html</small>
               <button class="btn btn-sm btn-outline-light copy-code-btn" data-target="wdCodeBlock">Copy</button>
            </div>
            <pre class="mb-0 p-3" id="wdCodeBlock" style="background:#1e1e1e;color:#d4d4d4;max-height:400px;overflow:auto;font-size:13px;"><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;Withdrawal Requests&lt;/title&gt;
  &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
&lt;/head&gt;
&lt;body class="bg-light p-4"&gt;

  &lt;select id="filter" class="form-select mb-3" style="width:150px" onchange="loadWd()"&gt;
    &lt;option value="unpaid"&gt;Pending&lt;/option&gt;
    &lt;option value="paid"&gt;Paid&lt;/option&gt;
    &lt;option value="rejected"&gt;Rejected&lt;/option&gt;
  &lt;/select&gt;

  &lt;table class="table table-hover"&gt;
    &lt;thead&gt;&lt;tr&gt;&lt;th&gt;User&lt;/th&gt;&lt;th&gt;Amount&lt;/th&gt;&lt;th&gt;Method&lt;/th&gt;&lt;th&gt;Date&lt;/th&gt;&lt;th&gt;Actions&lt;/th&gt;&lt;/tr&gt;&lt;/thead&gt;
    &lt;tbody id="wdTable"&gt;&lt;/tbody&gt;
  &lt;/table&gt;

  &lt;script&gt;
    const API_BASE = 'https://your-domain.com/';
    const TOKEN = 'your-admin-jwt-token';

    async function loadWd() {
      const status = document.getElementById('filter').value;
      const res = await fetch(API_BASE + 'Admin_Api/withdrawals?status=' + status, {
        headers: { 'Authorization': TOKEN }
      });
      const json = await res.json();

      if (json.status) {
        const cs = json.data.currency_symbol || '$';
        const sh = parseInt(json.data.enable_shorten_numbers) || 0;
        function fmt(v) {
          const n = parseFloat(v) || 0;
          if (sh === 1) {
            if (n &gt;= 1000000) return cs + (n/1000000).toFixed(1) + 'M';
            if (n &gt;= 1000) return cs + (n/1000).toFixed(1) + 'k';
          }
          return cs + n.toFixed(2);
        }
        document.getElementById('wdTable').innerHTML = json.data.requests.map(r =&gt; {
          const isPending = r.status === '0';
          const actions = isPending
            ? '&lt;button class="btn btn-sm btn-success me-1" onclick="wdAction(\'' + r.id + '\',\'paid\')"&gt;Approve&lt;/button&gt;' +
              '&lt;button class="btn btn-sm btn-danger" onclick="wdAction(\'' + r.id + '\',\'rejected\')"&gt;Reject&lt;/button&gt;'
            : '&lt;span class="badge bg-' + (r.status === '1' ? 'success' : 'danger') + '"&gt;' + (r.status === '1' ? 'Paid' : 'Rejected') + '&lt;/span&gt;';
          return '&lt;tr&gt;&lt;td&gt;' + (r.firstname || '') + ' ' + (r.lastname || '') + '&lt;/td&gt;' +
            '&lt;td&gt;' + fmt(r.amount) + '&lt;/td&gt;&lt;td&gt;' + (r.payment_method || '-') + '&lt;/td&gt;' +
            '&lt;td&gt;' + (r.created_at || '') + '&lt;/td&gt;&lt;td&gt;' + actions + '&lt;/td&gt;&lt;/tr&gt;';
        }).join('');
      }
    }

    async function wdAction(id, status) {
      const fd = new FormData();
      fd.append('request_id', id);
      fd.append('status', status);
      fd.append('admin_note', 'Processed via dashboard');

      await fetch(API_BASE + 'Admin_Api/update_withdrawal_status', {
        method: 'POST',
        headers: { 'Authorization': TOKEN },
        body: fd
      });
      loadWd();
    }

    loadWd();
  &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
         </div>
      </div>
   </div>
</div>

<!-- Example 4: Reports -->
<div class="top-content" id="example_reports">
   <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
         <h5 class="mb-0"><i class="bi bi-graph-up text-success me-2"></i>Reports & Analytics</h5>
         <div class="d-flex gap-2 align-items-center">
            <input type="date" id="exDateFrom" class="form-control form-control-sm" style="width:150px" onchange="exLoadReports()">
            <input type="date" id="exDateTo" class="form-control form-control-sm" style="width:150px" onchange="exLoadReports()">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#repCode">
               <i class="bi bi-code-slash me-1"></i>View Code
            </button>
         </div>
      </div>
      <!-- Live Demo -->
      <div class="card-body">
         <canvas id="exReportsChart" height="80"></canvas>
         <div class="row g-3 mt-3" id="exTopAffiliates"></div>
      </div>
      <!-- Code -->
      <div class="collapse" id="repCode">
         <div class="card-footer bg-dark p-0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2">
               <small class="text-white-50">reports-chart.html</small>
               <button class="btn btn-sm btn-outline-light copy-code-btn" data-target="repCodeBlock">Copy</button>
            </div>
            <pre class="mb-0 p-3" id="repCodeBlock" style="background:#1e1e1e;color:#d4d4d4;max-height:400px;overflow:auto;font-size:13px;"><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;Reports &amp; Analytics&lt;/title&gt;
  &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
  &lt;script src="https://cdn.jsdelivr.net/npm/chart.js"&gt;&lt;/script&gt;
&lt;/head&gt;
&lt;body class="bg-light p-4"&gt;

  &lt;div class="d-flex gap-2 mb-3"&gt;
    &lt;input type="date" id="dateFrom" class="form-control" style="width:180px" onchange="loadReports()"&gt;
    &lt;input type="date" id="dateTo" class="form-control" style="width:180px" onchange="loadReports()"&gt;
  &lt;/div&gt;
  &lt;canvas id="chart" height="100"&gt;&lt;/canvas&gt;
  &lt;div class="row g-3 mt-3" id="topAffiliates"&gt;&lt;/div&gt;

  &lt;script&gt;
    const API_BASE = 'https://your-domain.com/';
    const TOKEN = 'your-admin-jwt-token';
    let chart = null;

    // Set default date range (current month)
    const now = new Date();
    document.getElementById('dateFrom').value = now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0') + '-01';
    document.getElementById('dateTo').value = now.toISOString().split('T')[0];

    async function loadReports() {
      const params = new URLSearchParams({
        date_from: document.getElementById('dateFrom').value,
        date_to: document.getElementById('dateTo').value
      });

      const res = await fetch(API_BASE + 'Admin_Api/reports?' + params, {
        headers: { 'Authorization': TOKEN }
      });
      const json = await res.json();

      if (json.status) {
        const d = json.data;
        const labels = [...new Set([
          ...d.clicks_by_day.map(c =&gt; c.date),
          ...d.sales_by_day.map(s =&gt; s.date)
        ])].sort();

        if (chart) chart.destroy();
        chart = new Chart(document.getElementById('chart'), {
          type: 'line',
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Clicks',
                data: labels.map(l =&gt; { const f = d.clicks_by_day.find(c =&gt; c.date === l); return f ? +f.count : 0; }),
                borderColor: '#0d6efd', tension: 0.3, fill: true
              },
              {
                label: 'Sales',
                data: labels.map(l =&gt; { const f = d.sales_by_day.find(s =&gt; s.date === l); return f ? +f.count : 0; }),
                borderColor: '#198754', tension: 0.3, fill: true
              }
            ]
          },
          options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Top Affiliates
        const cs = d.currency_symbol || '$';
        const sh = parseInt(d.enable_shorten_numbers) || 0;
        function fmt(v) {
          const n = parseFloat(v) || 0;
          if (sh === 1) {
            if (n &gt;= 1000000) return cs + (n/1000000).toFixed(1) + 'M';
            if (n &gt;= 1000) return cs + (n/1000).toFixed(1) + 'k';
          }
          return cs + n.toFixed(2);
        }
        document.getElementById('topAffiliates').innerHTML = d.top_affiliates.map(a =&gt;
          '&lt;div class="col-md-4"&gt;&lt;div class="card p-2"&gt;&lt;strong&gt;' + a.firstname + ' ' + a.lastname + '&lt;/strong&gt;' +
          '&lt;small&gt;' + a.total_sales + ' sales &amp;middot; ' + fmt(a.total_revenue) + '&lt;/small&gt;&lt;/div&gt;&lt;/div&gt;'
        ).join('');
      }
    }

    loadReports();
  &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
         </div>
      </div>
   </div>
</div>

<!-- Example 5: Wallet -->
<div class="top-content" id="example_wallet">
   <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
         <h5 class="mb-0"><i class="bi bi-wallet2 text-success me-2"></i>Wallet Overview</h5>
         <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#walCode">
            <i class="bi bi-code-slash me-1"></i>View Code
         </button>
      </div>
      <!-- Live Demo -->
      <div class="card-body">
         <div class="row g-3">
            <div class="col-md-4">
               <div class="card border-0 bg-success bg-opacity-10 p-3 rounded-3 text-center">
                  <small class="text-muted">Admin Balance</small>
                  <h3 class="text-success mb-0" id="exWalBalance">--</h3>
               </div>
            </div>
            <div class="col-md-4">
               <div class="card border-0 bg-primary bg-opacity-10 p-3 rounded-3 text-center">
                  <small class="text-muted">External Orders</small>
                  <h3 class="text-primary mb-0" id="exWalExternal">--</h3>
               </div>
            </div>
            <div class="col-md-4">
               <div class="card border-0 bg-info bg-opacity-10 p-3 rounded-3 text-center">
                  <small class="text-muted">Store Sales</small>
                  <h3 class="text-info mb-0" id="exWalStore">--</h3>
               </div>
            </div>
         </div>
         <div class="row g-3 mt-2">
            <div class="col-md-3">
               <div class="card border-0 bg-light p-2 rounded-3 text-center">
                  <small class="text-muted d-block">Integration Clicks</small>
                  <strong id="exWalIntClicks">--</strong>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-light p-2 rounded-3 text-center">
                  <small class="text-muted d-block">Store Clicks</small>
                  <strong id="exWalStoreClicks">--</strong>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-light p-2 rounded-3 text-center">
                  <small class="text-muted d-block">Action Clicks</small>
                  <strong id="exWalActionClicks">--</strong>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card border-0 bg-light p-2 rounded-3 text-center">
                  <small class="text-muted d-block">Form Clicks</small>
                  <strong id="exWalFormClicks">--</strong>
               </div>
            </div>
         </div>
         <h6 class="mt-3 mb-2">Recent Transactions</h6>
         <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
               <thead class="table-light"><tr><th>User</th><th>Amount</th><th>Type</th><th>Status</th><th>Date</th></tr></thead>
               <tbody id="exWalTxBody">
                  <tr><td colspan="5" class="text-center text-muted py-3"><i class="bi bi-plug me-1"></i>Connect to API first</td></tr>
               </tbody>
            </table>
         </div>
      </div>
      <!-- Code -->
      <div class="collapse" id="walCode">
         <div class="card-footer bg-dark p-0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2">
               <small class="text-white-50">wallet-overview.html</small>
               <button class="btn btn-sm btn-outline-light copy-code-btn" data-target="walCodeBlock">Copy</button>
            </div>
            <pre class="mb-0 p-3" id="walCodeBlock" style="background:#1e1e1e;color:#d4d4d4;max-height:400px;overflow:auto;font-size:13px;"><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;Wallet Overview&lt;/title&gt;
  &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
&lt;/head&gt;
&lt;body class="bg-light p-4"&gt;

  &lt;div class="row g-3" id="walletStats"&gt;&lt;/div&gt;
  &lt;h5 class="mt-4"&gt;Recent Transactions&lt;/h5&gt;
  &lt;table class="table table-hover"&gt;
    &lt;thead&gt;&lt;tr&gt;&lt;th&gt;User&lt;/th&gt;&lt;th&gt;Amount&lt;/th&gt;&lt;th&gt;Type&lt;/th&gt;&lt;th&gt;Status&lt;/th&gt;&lt;th&gt;Date&lt;/th&gt;&lt;/tr&gt;&lt;/thead&gt;
    &lt;tbody id="txTable"&gt;&lt;/tbody&gt;
  &lt;/table&gt;

  &lt;script&gt;
    const API_BASE = 'https://your-domain.com/';
    const TOKEN = 'your-admin-jwt-token';

    async function loadWallet() {
      const res = await fetch(API_BASE + 'Admin_Api/wallet', {
        headers: { 'Authorization': TOKEN }
      });
      const json = await res.json();

      if (json.status) {
        const d = json.data;
        const cs = d.currency_symbol || '$';
        const sh = parseInt(d.enable_shorten_numbers) || 0;
        function fmt(v) {
          const n = parseFloat(v) || 0;
          if (sh === 1) {
            if (n &gt;= 1000000) return cs + (n/1000000).toFixed(1) + 'M';
            if (n &gt;= 1000) return cs + (n/1000).toFixed(1) + 'k';
          }
          return cs + n.toFixed(2);
        }

        // Balance cards
        const stats = [
          { label: 'Admin Balance',     value: fmt(d.admin_balance),          color: 'success' },
          { label: 'External Orders',   value: fmt(d.order_external_total),   color: 'primary' },
          { label: 'Store Sales',       value: fmt(d.sale_localstore_total),  color: 'info' },
          { label: 'Integration Clicks', value: d.click_integration_total,     color: 'secondary' },
          { label: 'Store Clicks',      value: d.click_localstore_total,       color: 'secondary' },
          { label: 'Action Clicks',     value: d.click_action_total,           color: 'secondary' },
        ];

        document.getElementById('walletStats').innerHTML = stats.map(s =&gt;
          '&lt;div class="col-md-4"&gt;&lt;div class="card bg-' + s.color + ' bg-opacity-10 p-3 text-center"&gt;' +
          '&lt;small class="text-muted"&gt;' + s.label + '&lt;/small&gt;&lt;h3&gt;' + s.value + '&lt;/h3&gt;' +
          '&lt;/div&gt;&lt;/div&gt;'
        ).join('');

        // Transactions table
        if (d.transactions &amp;&amp; d.transactions.length) {
          document.getElementById('txTable').innerHTML = d.transactions.map(t =&gt;
            '&lt;tr&gt;&lt;td&gt;' + (t.firstname || '') + ' ' + (t.lastname || '') + '&lt;/td&gt;' +
            '&lt;td&gt;' + fmt(t.amount) + '&lt;/td&gt;' +
            '&lt;td&gt;' + (t.type || '-') + '&lt;/td&gt;' +
            '&lt;td&gt;&lt;span class="badge bg-' + (t.status === 'paid' ? 'success' : 'warning') + '"&gt;' + (t.status || '-') + '&lt;/span&gt;&lt;/td&gt;' +
            '&lt;td&gt;' + (t.created_date || '') + '&lt;/td&gt;&lt;/tr&gt;'
          ).join('');
        }
      }
    }

    loadWallet();
  &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
         </div>
      </div>
   </div>
</div>

<!-- Example 6: Admin Profile -->
<div class="top-content" id="example_profile">
   <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
         <h5 class="mb-0"><i class="bi bi-person-circle text-success me-2"></i>Admin Profile</h5>
         <div>
            <span class="badge bg-success me-2" id="profileStatus">Waiting for connection</span>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#profileCode">
               <i class="bi bi-code-slash me-1"></i>View Code
            </button>
         </div>
      </div>
      <!-- Live Demo -->
      <div class="card-body">
         <div class="row g-4">
            <!-- Profile Card -->
            <div class="col-md-5">
               <div class="card border-0 bg-light rounded-3 overflow-hidden">
                  <div class="text-center p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                     <img id="exProfileAvatar" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Crect width='80' height='80' rx='40' fill='%23ffffff33'/%3E%3Ctext x='50%25' y='56%25' text-anchor='middle' fill='white' font-size='30'%3E?%3C/text%3E%3C/svg%3E" class="rounded-circle border border-3 border-white shadow" width="80" height="80" style="object-fit:cover;">
                     <h5 class="text-white mt-2 mb-0" id="exProfileName">--</h5>
                     <small class="text-white-50" id="exProfileEmail">--</small>
                  </div>
                  <div class="p-3">
                     <div class="d-flex justify-content-between border-bottom py-2">
                        <small class="text-muted">Username</small>
                        <strong class="small" id="exProfileUsername">--</strong>
                     </div>
                     <div class="d-flex justify-content-between border-bottom py-2">
                        <small class="text-muted">Phone</small>
                        <strong class="small" id="exProfilePhone">--</strong>
                     </div>
                     <div class="d-flex justify-content-between border-bottom py-2">
                        <small class="text-muted">Country</small>
                        <span class="d-flex align-items-center gap-1">
                           <img id="exProfileFlag" src="" width="18" height="13" class="d-none" style="object-fit:cover;border-radius:2px;">
                           <strong class="small" id="exProfileCountry">--</strong>
                        </span>
                     </div>
                     <div class="d-flex justify-content-between border-bottom py-2">
                        <small class="text-muted">City</small>
                        <strong class="small" id="exProfileCity">--</strong>
                     </div>
                     <div class="d-flex justify-content-between py-2">
                        <small class="text-muted">Pincode</small>
                        <strong class="small" id="exProfilePincode">--</strong>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Edit Form -->
            <div class="col-md-7">
               <h6 class="mb-3"><i class="bi bi-pencil-square me-1"></i>Edit Profile</h6>
               <form id="exProfileForm" onsubmit="exUpdateProfile(event)">
                  <div class="row g-2">
                     <div class="col-6">
                        <label class="form-label small fw-bold">First Name</label>
                        <input type="text" id="exProfFirstname" class="form-control form-control-sm">
                     </div>
                     <div class="col-6">
                        <label class="form-label small fw-bold">Last Name</label>
                        <input type="text" id="exProfLastname" class="form-control form-control-sm">
                     </div>
                     <div class="col-12">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" id="exProfEmail" class="form-control form-control-sm">
                     </div>
                     <div class="col-6">
                        <label class="form-label small fw-bold">Phone</label>
                        <input type="text" id="exProfPhone" class="form-control form-control-sm" placeholder="+1 201-555-0123">
                     </div>
                     <div class="col-6">
                        <label class="form-label small fw-bold">Country</label>
                        <select id="exProfCountry" class="form-select form-select-sm">
                           <option value="">Loading countries...</option>
                        </select>
                     </div>
                     <div class="col-6">
                        <label class="form-label small fw-bold">City</label>
                        <input type="text" id="exProfCity" class="form-control form-control-sm">
                     </div>
                     <div class="col-6">
                        <label class="form-label small fw-bold">Pincode</label>
                        <input type="text" id="exProfPincode" class="form-control form-control-sm">
                     </div>
                     <div class="col-12">
                        <label class="form-label small fw-bold">New Password <small class="text-muted fw-normal">(leave blank to keep)</small></label>
                        <input type="password" id="exProfPassword" class="form-control form-control-sm" placeholder="Optional">
                     </div>
                     <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-sm btn-success" id="exProfSaveBtn">
                           <i class="bi bi-check-lg me-1"></i>Save Changes
                        </button>
                        <span id="exProfSaveStatus" class="ms-2 small"></span>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <!-- Code -->
      <div class="collapse" id="profileCode">
         <div class="card-footer bg-dark p-0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2">
               <small class="text-white-50">admin-profile.html</small>
               <button class="btn btn-sm btn-outline-light copy-code-btn" data-target="profileCodeBlock">Copy</button>
            </div>
            <pre class="mb-0 p-3" id="profileCodeBlock" style="background:#1e1e1e;color:#d4d4d4;max-height:400px;overflow:auto;font-size:13px;"><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;Admin Profile&lt;/title&gt;
  &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
&lt;/head&gt;
&lt;body class="bg-light p-4"&gt;

  &lt;div class="row g-4"&gt;
    &lt;div class="col-md-5"&gt;
      &lt;div class="card p-4 text-center" id="profileCard"&gt;
        &lt;img id="avatar" src="" class="rounded-circle mx-auto" width="80" height="80"&gt;
        &lt;h5 id="fullName" class="mt-2"&gt;--&lt;/h5&gt;
        &lt;small id="email" class="text-muted"&gt;--&lt;/small&gt;
        &lt;hr&gt;
        &lt;div id="profileFields"&gt;&lt;/div&gt;
      &lt;/div&gt;
    &lt;/div&gt;
    &lt;div class="col-md-7"&gt;
      &lt;form id="editForm" onsubmit="updateProfile(event)"&gt;
        &lt;div class="row g-2"&gt;
          &lt;div class="col-6"&gt;
            &lt;label class="form-label"&gt;First Name&lt;/label&gt;
            &lt;input type="text" id="firstname" class="form-control"&gt;
          &lt;/div&gt;
          &lt;div class="col-6"&gt;
            &lt;label class="form-label"&gt;Last Name&lt;/label&gt;
            &lt;input type="text" id="lastname" class="form-control"&gt;
          &lt;/div&gt;
          &lt;div class="col-12"&gt;
            &lt;label class="form-label"&gt;Email&lt;/label&gt;
            &lt;input type="email" id="editEmail" class="form-control"&gt;
          &lt;/div&gt;
          &lt;div class="col-6"&gt;
            &lt;label class="form-label"&gt;Phone&lt;/label&gt;
            &lt;input type="text" id="phone" class="form-control"&gt;
          &lt;/div&gt;
          &lt;div class="col-6"&gt;
            &lt;label class="form-label"&gt;Country&lt;/label&gt;
            &lt;select id="country" class="form-select"&gt;&lt;/select&gt;
          &lt;/div&gt;
          &lt;div class="col-6"&gt;
            &lt;label class="form-label"&gt;City&lt;/label&gt;
            &lt;input type="text" id="city" class="form-control"&gt;
          &lt;/div&gt;
          &lt;div class="col-6"&gt;
            &lt;label class="form-label"&gt;Pincode&lt;/label&gt;
            &lt;input type="text" id="pincode" class="form-control"&gt;
          &lt;/div&gt;
          &lt;div class="col-12"&gt;
            &lt;label class="form-label"&gt;New Password &lt;small class="text-muted"&gt;(optional)&lt;/small&gt;&lt;/label&gt;
            &lt;input type="password" id="password" class="form-control"&gt;
          &lt;/div&gt;
          &lt;div class="col-12 mt-2"&gt;
            &lt;button type="submit" class="btn btn-success"&gt;Save Changes&lt;/button&gt;
          &lt;/div&gt;
        &lt;/div&gt;
      &lt;/form&gt;
    &lt;/div&gt;
  &lt;/div&gt;

  &lt;script&gt;
    const API_BASE = 'https://your-domain.com/';
    const TOKEN = 'your-admin-jwt-token';

    async function loadProfile() {
      const res = await fetch(API_BASE + 'Admin_Api/profile', {
        headers: { 'Authorization': TOKEN }
      });
      const json = await res.json();

      if (json.status) {
        const d = json.data;

        // Display card
        document.getElementById('avatar').src = d.profile_avatar;
        document.getElementById('fullName').textContent = d.firstname + ' ' + d.lastname;
        document.getElementById('email').textContent = d.email;
        document.getElementById('profileFields').innerHTML =
          field('Username', d.username) +
          field('Phone', d.PhoneNumber || 'Not set') +
          field('Country', d.country_name || 'Not set') +
          field('City', d.city || 'Not set') +
          field('Pincode', d.pincode || 'Not set');

        // Populate edit form
        document.getElementById('firstname').value = d.firstname;
        document.getElementById('lastname').value = d.lastname;
        document.getElementById('editEmail').value = d.email;
        document.getElementById('phone').value = d.PhoneNumber || '';
        document.getElementById('city').value = d.city || '';
        document.getElementById('pincode').value = d.pincode || '';

        // Populate country dropdown
        const sel = document.getElementById('country');
        sel.innerHTML = '&lt;option value=""&gt;Select Country&lt;/option&gt;' +
          d.countries.map(c =&gt;
            '&lt;option value="' + c.id + '"' +
            (c.id === d.country_id ? ' selected' : '') +
            '&gt;' + c.name + '&lt;/option&gt;'
          ).join('');
      }
    }

    function field(label, value) {
      return '&lt;div class="d-flex justify-content-between py-1"&gt;' +
        '&lt;small class="text-muted"&gt;' + label + '&lt;/small&gt;' +
        '&lt;strong class="small"&gt;' + value + '&lt;/strong&gt;&lt;/div&gt;';
    }

    async function updateProfile(e) {
      e.preventDefault();
      const fd = new FormData();
      fd.append('firstname', document.getElementById('firstname').value);
      fd.append('lastname', document.getElementById('lastname').value);
      fd.append('email', document.getElementById('editEmail').value);
      fd.append('phone', document.getElementById('phone').value);
      fd.append('country_id', document.getElementById('country').value);
      fd.append('city', document.getElementById('city').value);
      fd.append('pincode', document.getElementById('pincode').value);

      const pw = document.getElementById('password').value;
      if (pw) fd.append('password', pw);

      const res = await fetch(API_BASE + 'Admin_Api/update_profile', {
        method: 'POST',
        headers: { 'Authorization': TOKEN },
        body: fd
      });
      const json = await res.json();

      if (json.status) {
        alert('Profile updated!');
        loadProfile();
      } else {
        alert(json.message || 'Update failed');
      }
    }

    loadProfile();
  &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
         </div>
      </div>
   </div>
</div>

<!-- Chart.js CDN for reports -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Live Examples Engine -->
<script>
let exToken = localStorage.getItem('adminApiToken') || '';
let exBase = localStorage.getItem('adminApiBase') || '';
let exChart = null;
let exUserPageNum = 0;
let exCurrencySymbol = '$';
let exShortenNumbers = 0;

function exFormatCurrency(amount) {
   const raw = parseFloat(amount) || 0;
   if (exShortenNumbers === 1) {
      if (raw >= 1000000) return exCurrencySymbol + (raw / 1000000).toFixed(1) + 'M';
      if (raw >= 1000) return exCurrencySymbol + (raw / 1000).toFixed(1) + 'k';
   }
   return exCurrencySymbol + raw.toFixed(2);
}

function exSaveSession() {
   localStorage.setItem('adminApiToken', exToken);
   localStorage.setItem('adminApiBase', exBase);
}

function exClearSession() {
   exToken = '';
   localStorage.removeItem('adminApiToken');
   localStorage.removeItem('adminApiBase');
}

function exApi(path, opts = {}) {
   return fetch(exBase + path, {
      ...opts,
      headers: { 'Authorization': exToken, ...(opts.headers || {}) }
   }).then(r => {
      if (!r.ok && r.status === 401) {
         exClearSession();
         document.getElementById('exConnectionStatus').innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="bi bi-shield-x me-1"></i>Session expired. Please login again.</div>';
         document.getElementById('exConnectBtn').classList.replace('btn-outline-success', 'btn-success');
         document.getElementById('exConnectBtn').innerHTML = '<i class="bi bi-lightning-fill"></i>';
      }
      var contentType = r.headers.get('content-type') || '';
      if (!contentType.includes('application/json')) {
         throw new Error('Server returned non-JSON response (HTTP ' + r.status + '). Check your API Base URL.');
      }
      return r.json();
   });
}

async function exShowConnected(adminName) {
   var statusEl = document.getElementById('exConnectionStatus');
   statusEl.innerHTML = '<div class="alert alert-success py-2 mb-0">' +
      '<div class="d-flex align-items-center justify-content-between">' +
      '<div><i class="bi bi-shield-check me-1"></i>Admin authenticated' + (adminName ? ': <strong>' + adminName + '</strong>' : '') + '</div>' +
      '<div><button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="exLogout()">Logout</button> <div class="spinner-border spinner-border-sm text-success ms-2" id="exLoadingSpinner"></div></div>' +
      '</div>' +
      '<small class="text-muted">Loading live data into all sections below...</small></div>';
   document.getElementById('exConnectBtn').classList.replace('btn-success', 'btn-outline-success');
   document.getElementById('exConnectBtn').innerHTML = '<i class="bi bi-check-lg"></i>';

   await exLoadAll();

   var spinner = document.getElementById('exLoadingSpinner');
   if (spinner) spinner.style.display = 'none';
}

function exLogout() {
   exClearSession();
   document.getElementById('exConnectionStatus').innerHTML = '<div class="alert alert-info py-2 mb-0"><i class="bi bi-box-arrow-right me-1"></i>Logged out. Enter credentials to reconnect.</div>';
   document.getElementById('exConnectBtn').classList.replace('btn-outline-success', 'btn-success');
   document.getElementById('exConnectBtn').innerHTML = '<i class="bi bi-lightning-fill"></i>';
   document.getElementById('exConnectBtn').disabled = false;
}

async function exConnect() {
   exBase = document.getElementById('exApiBase').value.replace(/\/$/, '') + '/';
   const username = document.getElementById('exUsername').value;
   const password = document.getElementById('exPassword').value;
   const statusEl = document.getElementById('exConnectionStatus');

   if (!username || !password) {
      statusEl.innerHTML = '<div class="alert alert-warning py-2 mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Enter admin username and password</div>';
      return;
   }

   statusEl.innerHTML = '<div class="d-flex align-items-center text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Authenticating as admin...</div>';
   document.getElementById('exConnectBtn').disabled = true;

   try {
      const fd = new FormData();
      fd.append('username', username);
      fd.append('password', password);
      fd.append('device_type', '3');
      fd.append('device_token', 'admin-api-docs-' + Date.now());

      const res = await fetch(exBase + 'User/login', { method: 'POST', body: fd });
      var ct = res.headers.get('content-type') || '';
      if (!ct.includes('application/json')) {
         var text = await res.text();
         throw new Error('Server returned HTML instead of JSON (HTTP ' + res.status + '). Check your API Base URL.');
      }
      const json = await res.json();

      if (json.status && json.data) {
         if (json.data.role !== 'admin') {
            statusEl.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="bi bi-shield-x me-1"></i><strong>Access Denied:</strong> The account "<strong>' + username + '</strong>" is not an admin account (role: ' + json.data.role + '). Please use admin credentials.</div>';
            document.getElementById('exConnectBtn').disabled = false;
            return;
         }

         exToken = json.data.token;
         exSaveSession();
         exShowConnected(json.data.firstname + ' ' + json.data.lastname);
      } else {
         statusEl.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="bi bi-x-circle me-1"></i>' + (json.message || 'Login failed. Check credentials.') + '</div>';
      }
   } catch (err) {
      statusEl.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="bi bi-x-circle me-1"></i>Connection failed: ' + err.message + '<br><small class="text-muted">Make sure the API Base URL is correct and the server is running.</small></div>';
   }
   document.getElementById('exConnectBtn').disabled = false;
}

async function exLoadAll() {
   exLoadDashboard();
   exLoadUsers();
   exLoadWithdrawals();
   exLoadReports();
   exLoadWallet();
   exLoadProfile();
}

async function exLoadDashboard() {
   try {
      document.getElementById('dashStatus').textContent = 'Loading...';
      document.getElementById('dashStatus').className = 'badge bg-secondary me-2';
      const json = await exApi('Admin_Api/dashboard');
      if (json.status === true) {
         if (json.data.currency_symbol) exCurrencySymbol = json.data.currency_symbol;
         if (json.data.enable_shorten_numbers !== undefined) exShortenNumbers = parseInt(json.data.enable_shorten_numbers) || 0;
         document.querySelectorAll('.ex-dash-val').forEach(el => {
            const field = el.dataset.field;
            if (json.data[field] !== undefined) {
               el.textContent = el.dataset.prefix === 'currency' ? exFormatCurrency(json.data[field]) : json.data[field];
            }
         });
         document.getElementById('dashStatus').textContent = 'Live';
         document.getElementById('dashStatus').className = 'badge bg-success me-2';
      } else {
         document.getElementById('dashStatus').textContent = json.message || 'Failed';
         document.getElementById('dashStatus').className = 'badge bg-danger me-2';
      }
   } catch (e) {
      document.getElementById('dashStatus').textContent = 'Error: ' + e.message;
      document.getElementById('dashStatus').className = 'badge bg-danger me-2';
   }
}

async function exLoadUsers() {
   if (!exToken) return;
   const search = document.getElementById('exUserSearch').value;
   const status = document.getElementById('exUserStatus').value;

   try {
      document.getElementById('usersStatus').textContent = 'Loading...';
      document.getElementById('usersStatus').className = 'badge bg-secondary me-2';
      const params = new URLSearchParams({ start_from: exUserPageNum * 20, limit: 20, search: search, status: status });
      const json = await exApi('Admin_Api/users?' + params);

      if (json.status === true) {
         const tbody = document.getElementById('exUsersBody');
         if (!json.data.users || json.data.users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-3">No users found</td></tr>';
         } else {
            tbody.innerHTML = json.data.users.map(u => {
               const sBadge = u.status === '1' ? '<span class="badge bg-success">Active</span>'
                  : '<span class="badge bg-danger">Disabled</span>';
               const typeBadge = u.is_vendor === '1' ? '<span class="badge bg-info text-dark">Vendor</span>' : '<span class="badge bg-light text-dark">Affiliate</span>';
               return '<tr>' +
                  '<td>' + (u.firstname || '') + ' ' + (u.lastname || '') + '</td>' +
                  '<td><small>' + (u.email || '') + '</small></td>' +
                  '<td>' + exFormatCurrency(u.balance) + '</td>' +
                  '<td>' + (u.total_clicks || '0') + '</td>' +
                  '<td>' + (u.total_sales || '0') + '</td>' +
                  '<td>' + typeBadge + '</td>' +
                  '<td>' + sBadge + '</td>' +
                  '<td><button class="btn btn-sm btn-outline-primary py-0" onclick="exToggleUser(\'' + u.id + '\')">Toggle</button></td>' +
                  '</tr>';
            }).join('');
         }
         document.getElementById('exUsersCount').textContent = 'Total: ' + json.data.total_count;
         document.getElementById('exUserNext').disabled = !json.data.has_more;
         document.getElementById('exUserPrev').disabled = exUserPageNum === 0;
         document.getElementById('usersStatus').textContent = json.data.total_count + ' users';
         document.getElementById('usersStatus').className = 'badge bg-success me-2';
      } else {
         document.getElementById('usersStatus').textContent = json.message || 'Failed';
         document.getElementById('usersStatus').className = 'badge bg-danger me-2';
      }
   } catch (e) {
      document.getElementById('usersStatus').textContent = 'Error: ' + e.message;
      document.getElementById('usersStatus').className = 'badge bg-danger me-2';
   }
}

function exUserPage(dir) {
   exUserPageNum += dir;
   if (exUserPageNum < 0) exUserPageNum = 0;
   exLoadUsers();
}

async function exToggleUser(userId) {
   const fd = new FormData();
   fd.append('user_id', userId);
   try {
      const json = await exApi('Admin_Api/update_user_status', { method: 'POST', body: fd });
      if (json.status === true) exLoadUsers();
      else alert(json.message || 'Failed to toggle user status');
   } catch (e) {
      alert('Error: ' + e.message);
   }
}

async function exLoadWithdrawals() {
   if (!exToken) return;
   const status = document.getElementById('exWdFilter').value;

   try {
      const json = await exApi('Admin_Api/withdrawals?status=' + status);
      if (json.status === true) {
         const tbody = document.getElementById('exWdBody');
         const requests = json.data.requests || [];
         if (requests.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No ' + status + ' requests</td></tr>';
         } else {
            tbody.innerHTML = requests.map(r => {
               const isPending = r.status === '0' || r.status === 'unpaid';
               const actions = isPending
                  ? '<button class="btn btn-sm btn-success py-0 me-1" onclick="exWdAction(\'' + r.id + '\',\'paid\')">Approve</button>' +
                    '<button class="btn btn-sm btn-danger py-0" onclick="exWdAction(\'' + r.id + '\',\'rejected\')">Reject</button>'
                  : '<span class="badge bg-' + (r.status === '1' || r.status === 'paid' ? 'success' : 'danger') + '">' + (r.status === '1' || r.status === 'paid' ? 'Paid' : 'Rejected') + '</span>';
               return '<tr>' +
                  '<td>' + (r.firstname || '') + ' ' + (r.lastname || '') + '</td>' +
                  '<td>' + exFormatCurrency(r.amount) + '</td>' +
                  '<td>' + (r.payment_method || '-') + '</td>' +
                  '<td><small>' + (r.created_at || '') + '</small></td>' +
                  '<td>' + actions + '</td></tr>';
            }).join('');
         }
      } else {
         document.getElementById('exWdBody').innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">' + (json.message || 'Failed to load') + '</td></tr>';
      }
   } catch (e) {
      document.getElementById('exWdBody').innerHTML = '<tr><td colspan="5" class="text-center text-danger py-3">Error: ' + e.message + '</td></tr>';
   }
}

async function exWdAction(id, status) {
   const fd = new FormData();
   fd.append('request_id', id);
   fd.append('status', status);
   try {
      const json = await exApi('Admin_Api/update_withdrawal_status', { method: 'POST', body: fd });
      if (json.status === true) exLoadWithdrawals();
      else alert(json.message || 'Failed to update withdrawal');
   } catch (e) {
      alert('Error: ' + e.message);
   }
}

async function exLoadReports() {
   if (!exToken) return;
   const dateFrom = document.getElementById('exDateFrom').value;
   const dateTo = document.getElementById('exDateTo').value;

   try {
      const params = new URLSearchParams();
      if (dateFrom) params.append('date_from', dateFrom);
      if (dateTo) params.append('date_to', dateTo);

      const json = await exApi('Admin_Api/reports?' + params);
      if (json.status === true) {
         const d = json.data;
         const clickLabels = d.clicks_by_day.map(c => c.date);
         const saleLabels = d.sales_by_day.map(s => s.date);
         const allLabels = [...new Set([...clickLabels, ...saleLabels])].sort();

         if (exChart) exChart.destroy();
         exChart = new Chart(document.getElementById('exReportsChart'), {
            type: 'line',
            data: {
               labels: allLabels,
               datasets: [
                  {
                     label: 'Clicks',
                     data: allLabels.map(l => { const f = d.clicks_by_day.find(c => c.date === l); return f ? parseInt(f.count) : 0; }),
                     borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.1)', tension: 0.3, fill: true
                  },
                  {
                     label: 'Sales',
                     data: allLabels.map(l => { const f = d.sales_by_day.find(s => s.date === l); return f ? parseInt(f.count) : 0; }),
                     borderColor: '#198754', backgroundColor: 'rgba(25,135,84,0.1)', tension: 0.3, fill: true
                  }
               ]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
         });

         const container = document.getElementById('exTopAffiliates');
         if (d.top_affiliates.length > 0) {
            container.innerHTML = '<div class="col-12"><h6>Top Affiliates</h6></div>' +
               d.top_affiliates.slice(0, 6).map(a =>
                  '<div class="col-md-4"><div class="card border-0 bg-light p-2 rounded-3">' +
                  '<strong>' + a.firstname + ' ' + a.lastname + '</strong>' +
                  '<small class="text-muted">' + a.total_sales + ' sales &middot; ' + exFormatCurrency(a.total_revenue) + '</small>' +
                  '</div></div>'
               ).join('');
         } else {
            container.innerHTML = '<div class="col-12"><small class="text-muted">No sales data in this period</small></div>';
         }
      }
   } catch (e) {}
}

async function exLoadWallet() {
   if (!exToken) return;
   try {
      const json = await exApi('Admin_Api/wallet');
      if (json.status === true && json.data) {
         const d = json.data;
         if (d.currency_symbol) exCurrencySymbol = d.currency_symbol;
         if (d.enable_shorten_numbers !== undefined) exShortenNumbers = parseInt(d.enable_shorten_numbers) || 0;
         document.getElementById('exWalBalance').textContent = exFormatCurrency(d.admin_balance);
         document.getElementById('exWalExternal').textContent = exFormatCurrency(d.order_external_total);
         document.getElementById('exWalStore').textContent = exFormatCurrency(d.sale_localstore_total);
         document.getElementById('exWalIntClicks').textContent = d.click_integration_total || '0';
         document.getElementById('exWalStoreClicks').textContent = d.click_localstore_total || '0';
         document.getElementById('exWalActionClicks').textContent = d.click_action_total || '0';
         document.getElementById('exWalFormClicks').textContent = d.click_form_total || '0';

         const tbody = document.getElementById('exWalTxBody');
         if (d.transactions && d.transactions.length > 0) {
            tbody.innerHTML = d.transactions.slice(0, 10).map(t =>
               '<tr><td>' + (t.firstname || '') + ' ' + (t.lastname || '') + '</td>' +
               '<td>' + exFormatCurrency(t.amount) + '</td>' +
               '<td><span class="badge bg-light text-dark">' + (t.type || '-') + '</span></td>' +
               '<td><span class="badge bg-' + (t.status === 'paid' ? 'success' : 'warning text-dark') + '">' + (t.status || '-') + '</span></td>' +
               '<td><small>' + (t.created_date || '') + '</small></td></tr>'
            ).join('');
         } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No transactions</td></tr>';
         }
      } else {
         document.getElementById('exWalTxBody').innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">' + (json.message || 'Failed to load wallet') + '</td></tr>';
      }
   } catch (e) {
      document.getElementById('exWalTxBody').innerHTML = '<tr><td colspan="5" class="text-center text-danger py-3">Error: ' + e.message + '</td></tr>';
   }
}

async function exLoadProfile() {
   if (!exToken) return;
   try {
      document.getElementById('profileStatus').textContent = 'Loading...';
      document.getElementById('profileStatus').className = 'badge bg-secondary me-2';
      const json = await exApi('Admin_Api/profile');
      if (json.status === true && json.data) {
         const d = json.data;

         document.getElementById('exProfileAvatar').src = d.profile_avatar || '';
         document.getElementById('exProfileName').textContent = (d.firstname || '') + ' ' + (d.lastname || '');
         document.getElementById('exProfileEmail').textContent = d.email || '';
         document.getElementById('exProfileUsername').textContent = d.username || '--';
         document.getElementById('exProfilePhone').textContent = d.PhoneNumber || 'Not set';
         document.getElementById('exProfileCountry').textContent = d.country_name || 'Not set';
         document.getElementById('exProfileCity').textContent = d.city || 'Not set';
         document.getElementById('exProfilePincode').textContent = d.pincode || 'Not set';

         var flagEl = document.getElementById('exProfileFlag');
         if (d.country_flag) {
            flagEl.src = d.country_flag;
            flagEl.classList.remove('d-none');
         } else {
            flagEl.classList.add('d-none');
         }

         document.getElementById('exProfFirstname').value = d.firstname || '';
         document.getElementById('exProfLastname').value = d.lastname || '';
         document.getElementById('exProfEmail').value = d.email || '';
         document.getElementById('exProfPhone').value = d.PhoneNumber || '';
         document.getElementById('exProfCity').value = d.city || '';
         document.getElementById('exProfPincode').value = d.pincode || '';

         var countrySelect = document.getElementById('exProfCountry');
         if (d.countries && d.countries.length > 0) {
            countrySelect.innerHTML = '<option value="">Select Country</option>' +
               d.countries.map(function(c) {
                  return '<option value="' + c.id + '"' + (c.id === d.country_id ? ' selected' : '') + '>' + c.name + '</option>';
               }).join('');
         } else {
            countrySelect.innerHTML = '<option value="">No countries available</option>';
         }

         document.getElementById('profileStatus').textContent = 'Live';
         document.getElementById('profileStatus').className = 'badge bg-success me-2';
      } else {
         document.getElementById('profileStatus').textContent = json.message || 'Failed';
         document.getElementById('profileStatus').className = 'badge bg-danger me-2';
      }
   } catch (e) {
      document.getElementById('profileStatus').textContent = 'Error: ' + e.message;
      document.getElementById('profileStatus').className = 'badge bg-danger me-2';
   }
}

async function exUpdateProfile(e) {
   e.preventDefault();
   if (!exToken) return;
   var btn = document.getElementById('exProfSaveBtn');
   var statusEl = document.getElementById('exProfSaveStatus');
   btn.disabled = true;
   statusEl.innerHTML = '<span class="text-muted"><i class="spinner-border spinner-border-sm me-1"></i>Saving...</span>';

   try {
      var fd = new FormData();
      fd.append('firstname', document.getElementById('exProfFirstname').value);
      fd.append('lastname', document.getElementById('exProfLastname').value);
      fd.append('email', document.getElementById('exProfEmail').value);
      fd.append('phone', document.getElementById('exProfPhone').value);
      fd.append('country_id', document.getElementById('exProfCountry').value);
      fd.append('city', document.getElementById('exProfCity').value);
      fd.append('pincode', document.getElementById('exProfPincode').value);

      var pw = document.getElementById('exProfPassword').value;
      if (pw) fd.append('password', pw);

      var json = await exApi('Admin_Api/update_profile', { method: 'POST', body: fd });
      if (json.status === true) {
         statusEl.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Saved!</span>';
         document.getElementById('exProfPassword').value = '';
         exLoadProfile();
      } else {
         statusEl.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>' + (json.message || 'Failed') + '</span>';
      }
   } catch (e) {
      statusEl.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Error: ' + e.message + '</span>';
   }
   btn.disabled = false;
   setTimeout(function() { statusEl.innerHTML = ''; }, 4000);
}

// Set default date range (current month)
(function() {
   const now = new Date();
   document.getElementById('exDateFrom').value = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-01';
   document.getElementById('exDateTo').value = now.toISOString().split('T')[0];
})();

// Auto-restore session on page load
(function() {
   var pageBase = document.getElementById('exApiBase').value.replace(/\/$/, '') + '/';
   if (exToken && exBase) {
      if (exBase !== pageBase) {
         exClearSession();
      } else {
         document.getElementById('exApiBase').value = exBase.replace(/\/$/, '');
         exShowConnected(null);
      }
   }
})();

// Copy code button
$(document).on('click', '.copy-code-btn', function() {
   const targetId = $(this).data('target');
   const text = document.getElementById(targetId).textContent;
   const $btn = $(this);
   navigator.clipboard.writeText(text).then(function() {
      $btn.html('<i class="bi bi-check me-1"></i>Copied!').addClass('btn-success').removeClass('btn-outline-light');
      setTimeout(() => { $btn.html('<i class="bi bi-clipboard me-1"></i>Copy').removeClass('btn-success').addClass('btn-outline-light'); }, 2000);
   });
});
</script>
