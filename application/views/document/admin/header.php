<!doctype html>
<html lang="en">

<head>
	<title>Admin API Documentation - AffiliatePro</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="stylesheet" href="<?=base_url();?>assets/template/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url();?>assets/template/css/bootstrap-icons.css">
	<link href="<?= base_url('assets/template/css/all.min.css?v='.av()); ?>" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/template/css/admin-dashboard-custom.css?v='.av()); ?>" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/template/images/favicon.png') ?>">
	<link href="<?= base_url('assets/template/css/pretty-print-json.css?v='.av()); ?>" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/template/css/api-document-custom.css?v='.av()); ?>" rel="stylesheet" type="text/css">
	<script src="<?= base_url('assets/template/js/jquery.min.js') ?>"></script>
	<style>
		.admin-api-badge { background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%); }
		.admin-nav-border { border-left: 3px solid #dc3545 !important; }
	</style>
</head>

<body>
	<div id="wrapper">
		<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
			<div class="container-fluid">
				<a class="navbar-brand d-flex align-items-center" href="<?=base_url();?>admin-api-document">
					<img src="<?=base_url();?>assets/template/images/logo.png" alt="Logo" class="img-fluid me-2" style="height: 40px;">
					<span class="badge bg-danger ms-2 py-1 px-2 small">ADMIN API</span>
				</a>
				<div class="d-flex align-items-center">
					<a href="<?=base_url();?>api-home" class="btn btn-outline-light btn-sm me-2">
						<i class="bi bi-house me-1"></i>API Home
					</a>
					<a href="<?=base_url();?>api-document" class="btn btn-outline-light btn-sm me-2">
						<i class="bi bi-arrow-left-right me-1"></i>User API Docs
					</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
						<span class="navbar-toggler-icon"></span>
					</button>
				</div>
			</div>
		</nav>

		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="bg-white border-bottom shadow-sm py-2 mb-3 position-fixed w-100 top-0 start-0" style="z-index: 1020; margin-top: 56px;">
						<div class="container-fluid">
							<div class="row align-items-center">
								<div class="col-md-6">
									<nav aria-label="breadcrumb">
										<ol class="breadcrumb mb-0">
											<li class="breadcrumb-item"><a href="<?=base_url();?>" class="text-decoration-none text-primary"><i class="bi bi-house"></i></a></li>
											<li class="breadcrumb-item"><a href="<?=base_url();?>api-home" class="text-decoration-none text-primary">API Home</a></li>
											<li class="breadcrumb-item active text-dark" aria-current="page">Admin API</li>
										</ol>
									</nav>
								</div>
								<div class="col-md-6 text-end">
									<div class="d-flex align-items-center justify-content-end">
										<span class="text-muted small me-2 fw-medium">Reading Progress:</span>
										<div class="progress me-2 border" style="width: 120px; height: 8px;">
											<div class="progress-bar bg-danger" id="readingProgress" role="progressbar" style="width: 0%"></div>
										</div>
										<span class="text-danger small fw-bold" id="progressText">0%</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div style="height: 60px;"></div>
					
					<div class="row g-4">
