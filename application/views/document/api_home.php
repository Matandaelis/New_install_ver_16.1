<!doctype html>
<html lang="en">
<head>
	<title>API Documentation - AffiliatePro</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="stylesheet" href="<?=base_url();?>assets/template/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url();?>assets/template/css/bootstrap-icons.css">
	<link href="<?= base_url('assets/template/css/all.min.css?v='.av()); ?>" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/template/css/admin-dashboard-custom.css?v='.av()); ?>" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/template/images/favicon.png') ?>">
	<script src="<?= base_url('assets/template/js/jquery.min.js') ?>"></script>
	<style>
		body { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; }
		.api-hero { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); }
		.api-card {
			border: none; border-radius: 16px; transition: all 0.3s ease;
			overflow: hidden; cursor: pointer; text-decoration: none;
		}
		.api-card:hover { transform: translateY(-8px); box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important; }
		.api-card .card-icon {
			width: 80px; height: 80px; border-radius: 20px;
			display: flex; align-items: center; justify-content: center; font-size: 36px;
		}
		.api-card-user .card-icon { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: #fff; }
		.api-card-admin .card-icon { background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%); color: #fff; }
		.api-card-user:hover { border-left: 4px solid #0d6efd !important; }
		.api-card-admin:hover { border-left: 4px solid #dc3545 !important; }
		.feature-icon {
			width: 48px; height: 48px; border-radius: 12px;
			display: flex; align-items: center; justify-content: center;
		}
		.step-num {
			width: 36px; height: 36px; border-radius: 50%;
			display: inline-flex; align-items: center; justify-content: center;
			font-weight: 700; font-size: 14px;
		}
	</style>
</head>
<body>

<!-- Hero -->
<div class="api-hero text-white py-5">
	<div class="container py-4">
		<div class="row align-items-center">
			<div class="col-lg-7">
				<div class="d-flex align-items-center mb-3">
					<img src="<?=base_url();?>assets/template/images/logo.png" alt="Logo" class="img-fluid me-3" style="height: 48px; filter: brightness(0) invert(1);">
					<span class="badge bg-white bg-opacity-25 py-2 px-3 rounded-pill">API Documentation</span>
				</div>
				<h1 class="display-5 fw-bold mb-3">AffiliatePro API</h1>
				<p class="lead text-white-50 mb-4">Build powerful integrations with our REST API. Manage users, track campaigns, process withdrawals, and access analytics — all programmatically.</p>
				<div class="d-flex gap-3">
					<a href="<?=base_url();?>api-document" class="btn btn-primary btn-lg px-4">
						<i class="bi bi-person-gear me-2"></i>User API
					</a>
					<a href="<?=base_url();?>admin-api-document" class="btn btn-danger btn-lg px-4">
						<i class="bi bi-shield-lock me-2"></i>Admin API
					</a>
				</div>
			</div>
			<div class="col-lg-5 text-center d-none d-lg-block">
				<div class="bg-white bg-opacity-10 rounded-4 p-4" style="font-family: monospace; font-size: 14px; text-align: left;">
					<div class="text-info">// Quick start</div>
					<div><span class="text-warning">POST</span> /User/login</div>
					<div class="text-white-50 mb-2">→ { token: "eyJ..." }</div>
					<div><span class="text-success">GET</span> /Admin_Api/dashboard</div>
					<div class="text-white-50 mb-2">→ { total_users: 142, ... }</div>
					<div><span class="text-success">GET</span> /User/profile</div>
					<div class="text-white-50">→ { firstname: "John", ... }</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- API Cards -->
<div class="container py-5">
	<div class="row g-4 mb-5">
		<!-- User API -->
		<div class="col-lg-6">
			<a href="<?=base_url();?>api-document" class="api-card api-card-user card shadow-sm d-block h-100">
				<div class="card-body p-4">
					<div class="d-flex align-items-start mb-3">
						<div class="card-icon me-3">
							<i class="bi bi-person-gear"></i>
						</div>
						<div>
							<h3 class="fw-bold mb-1">User API</h3>
							<span class="badge bg-primary bg-opacity-10 text-primary">Full Documentation</span>
						</div>
					</div>
					<p class="text-muted mb-3">Complete REST API for user-side operations. Authentication, profile management, affiliate links, commissions, store, and more.</p>
					<div class="row g-2">
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Login & Registration
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Profile Management
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Affiliate Dashboard
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Commissions & Wallet
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Store & Products
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Notifications
							</div>
						</div>
					</div>
					<div class="mt-3 text-primary fw-medium">
						View Documentation <i class="bi bi-arrow-right ms-1"></i>
					</div>
				</div>
			</a>
		</div>

		<!-- Admin API -->
		<div class="col-lg-6">
			<a href="<?=base_url();?>admin-api-document" class="api-card api-card-admin card shadow-sm d-block h-100">
				<div class="card-body p-4">
					<div class="d-flex align-items-start mb-3">
						<div class="card-icon me-3">
							<i class="bi bi-shield-lock"></i>
						</div>
						<div>
							<h3 class="fw-bold mb-1">Admin API</h3>
							<span class="badge bg-danger bg-opacity-10 text-danger">Admin Access Only</span>
						</div>
					</div>
					<p class="text-muted mb-3">Administrative REST API for platform management. Dashboard stats, user management, withdrawal processing, analytics, and wallet.</p>
					<div class="row g-2">
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Dashboard Statistics
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>User Management
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Withdrawal Management
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Reports & Analytics
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-check-circle text-success me-2"></i>Wallet Overview
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center text-muted small">
								<i class="bi bi-play-circle text-success me-2"></i>Live Examples
							</div>
						</div>
					</div>
					<div class="mt-3 text-danger fw-medium">
						View Documentation <i class="bi bi-arrow-right ms-1"></i>
					</div>
				</div>
			</a>
		</div>
	</div>

	<!-- General Instructions -->
	<div class="row mb-5">
		<div class="col-12">
			<h2 class="fw-bold mb-4"><i class="bi bi-book me-2 text-primary"></i>Getting Started</h2>
		</div>

		<!-- Step 1 -->
		<div class="col-lg-4 mb-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-4">
					<div class="d-flex align-items-center mb-3">
						<span class="step-num bg-primary text-white me-3">1</span>
						<h5 class="fw-bold mb-0">Authenticate</h5>
					</div>
					<p class="text-muted">Call <code>POST /User/login</code> with your credentials to obtain a JWT token. This same endpoint works for both regular users and admin accounts.</p>
					<div class="bg-dark text-white rounded-3 p-3" style="font-family: monospace; font-size: 13px;">
						<span class="text-warning">POST</span> <?=base_url();?>User/login<br>
						<span class="text-muted">Body:</span> username, password,<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;device_type, device_token<br>
						<span class="text-muted">Returns:</span> <span class="text-success">{ token, role, ... }</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Step 2 -->
		<div class="col-lg-4 mb-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-4">
					<div class="d-flex align-items-center mb-3">
						<span class="step-num bg-success text-white me-3">2</span>
						<h5 class="fw-bold mb-0">Send Requests</h5>
					</div>
					<p class="text-muted">Include the JWT token in the <code>Authorization</code> header of every API request. Send the raw token directly — no "Bearer" prefix needed.</p>
					<div class="bg-dark text-white rounded-3 p-3" style="font-family: monospace; font-size: 13px;">
						<span class="text-warning">GET</span> <?=base_url();?>Admin_Api/dashboard<br>
						<span class="text-muted">Header:</span><br>
						&nbsp;Authorization: <span class="text-success">&lt;jwt-token&gt;</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Step 3 -->
		<div class="col-lg-4 mb-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-4">
					<div class="d-flex align-items-center mb-3">
						<span class="step-num bg-danger text-white me-3">3</span>
						<h5 class="fw-bold mb-0">Handle Responses</h5>
					</div>
					<p class="text-muted">All endpoints return JSON. A successful response has <code>"status": true</code> with data. Errors return status codes (401, 403, 422) with a message.</p>
					<div class="bg-dark text-white rounded-3 p-3" style="font-family: monospace; font-size: 13px;">
						<span class="text-muted">// Success</span><br>
						{ "<span class="text-success">status</span>": true, "data": { ... } }<br><br>
						<span class="text-muted">// Error</span><br>
						{ "<span class="text-danger">status</span>": false, "message": "..." }
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Key Info -->
	<div class="row g-4 mb-5">
		<div class="col-12">
			<h2 class="fw-bold mb-4"><i class="bi bi-info-circle me-2 text-primary"></i>Key Information</h2>
		</div>

		<div class="col-md-6 col-lg-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-4 text-center">
					<div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto mb-3">
						<i class="bi bi-globe fs-4"></i>
					</div>
					<h6 class="fw-bold">Base URL</h6>
					<code class="d-block small"><?=base_url();?></code>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-4 text-center">
					<div class="feature-icon bg-success bg-opacity-10 text-success mx-auto mb-3">
						<i class="bi bi-filetype-json fs-4"></i>
					</div>
					<h6 class="fw-bold">Response Format</h6>
					<span class="small text-muted">JSON (application/json)</span>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-4 text-center">
					<div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto mb-3">
						<i class="bi bi-key fs-4"></i>
					</div>
					<h6 class="fw-bold">Authentication</h6>
					<span class="small text-muted">JWT Token in header</span>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-4 text-center">
					<div class="feature-icon bg-danger bg-opacity-10 text-danger mx-auto mb-3">
						<i class="bi bi-shield-check fs-4"></i>
					</div>
					<h6 class="fw-bold">Admin Access</h6>
					<span class="small text-muted">Requires role = "admin"</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Error Codes -->
	<div class="row mb-5">
		<div class="col-12">
			<h2 class="fw-bold mb-4"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Error Codes</h2>
			<div class="card border-0 shadow-sm">
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead class="table-light">
								<tr><th>Code</th><th>Status</th><th>Description</th></tr>
							</thead>
							<tbody>
								<tr><td><span class="badge bg-success">200</span></td><td>OK</td><td>Request successful</td></tr>
								<tr><td><span class="badge bg-danger">401</span></td><td>Unauthorized</td><td>Missing or invalid JWT token</td></tr>
								<tr><td><span class="badge bg-danger">403</span></td><td>Forbidden</td><td>Valid token but insufficient permissions (e.g. user trying admin endpoint)</td></tr>
								<tr><td><span class="badge bg-warning text-dark">422</span></td><td>Validation Error</td><td>Missing or invalid request parameters</td></tr>
								<tr><td><span class="badge bg-dark">500</span></td><td>Server Error</td><td>Internal server error</td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-6">
				<p class="mb-0 text-white-50">&copy; <?=date('Y');?> AffiliatePro. All rights reserved.</p>
			</div>
			<div class="col-md-6 text-md-end">
				<a href="<?=base_url();?>api-document" class="text-white-50 text-decoration-none me-3"><i class="bi bi-person-gear me-1"></i>User API</a>
				<a href="<?=base_url();?>admin-api-document" class="text-white-50 text-decoration-none"><i class="bi bi-shield-lock me-1"></i>Admin API</a>
			</div>
		</div>
	</div>
</footer>

<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>"></script>
<script>
$(document).ready(function() {
    var $backToTop = $('<button class="btn btn-primary rounded-circle shadow d-flex align-items-center justify-content-center position-fixed" id="backToTop" style="width:46px;height:46px;bottom:30px;right:30px;z-index:1000;display:none;opacity:0;transition:opacity 0.3s;"><i class="bi bi-arrow-up fs-5"></i></button>');
    $('body').append($backToTop);
    
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            $backToTop.css({'display': 'flex', 'opacity': '1'});
        } else {
            $backToTop.css({'opacity': '0'});
            setTimeout(function() { if ($(window).scrollTop() <= 300) $backToTop.css('display', 'none'); }, 300);
        }
    });
    
    $backToTop.on('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>
</body>
</html>
