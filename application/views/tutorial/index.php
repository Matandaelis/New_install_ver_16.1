<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$store_setting =$db->Product_model->getSettings('store');
	$Product_model =$db->Product_model;
?>

<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<!-- Page Header Card -->
			<div class="card border-0 shadow-sm mb-4">
				<div class="card-header bg-primary text-white py-3">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center">
							<i class="fas fa-graduation-cap me-3 fs-3"></i>
							<div>
								<h4 class="mb-1 fw-bold"><?= __('admin.tutorial') ?></h4>
								<p class="mb-0 opacity-75 small"><?= __('admin.manage_tutorial_categories_and_pages') ?></p>
							</div>
						</div>
						<div class="d-flex align-items-center bg-light rounded px-3 py-2">
							<div class="form-check form-switch mb-0 me-3">
								<input class="form-check-input form-check-input-lg update_all_settings" 
									   type="checkbox" 
									   id="tutorialModuleStatus"
									   <?= $site['tutorial_module_status']==1 ? 'checked' : '' ?> 
									   data-setting_key="tutorial_module_status" 
									   data-setting_type="site"
									   role="switch"
									   style="cursor: pointer;">
								<label class="form-check-label ms-2 text-muted small" for="tutorialModuleStatus" style="cursor: pointer;">
									<?= __('admin.module_status') ?>
								</label>
							</div>
							<span class="badge <?= $site['tutorial_module_status']==1 ? 'bg-success' : 'bg-danger' ?> fs-6 px-3 py-2" id="statusBadge">
								<i class="fas fa-<?= $site['tutorial_module_status']==1 ? 'check-circle' : 'times-circle' ?> me-2"></i>
								<span id="statusText"><?= $site['tutorial_module_status']==1 ? __('admin.enabled') : __('admin.disabled') ?></span>
							</span>
						</div>
					</div>
				</div>
			</div>

			<!-- Tabs Navigation Card -->
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-light p-0">
					<ul class="nav nav-tabs nav-fill border-0" id="tutorialTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active py-3" 
									id="category-tab" 
									data-bs-toggle="tab" 
									data-bs-target="#category_tab" 
									type="button" 
									role="tab" 
									aria-controls="category_tab" 
									aria-selected="true">
								<i class="fas fa-folder me-2"></i>
								<span class="fw-semibold"><?= __('admin.category') ?></span>
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link py-3" 
									id="pages-tab" 
									data-bs-toggle="tab" 
									data-bs-target="#tutorial_tab_option" 
									type="button" 
									role="tab" 
									aria-controls="tutorial_tab_option" 
									aria-selected="false">
								<i class="fas fa-file-alt me-2"></i>
								<span class="fw-semibold"><?= __('admin.pages') ?></span>
							</button>
						</li>
					</ul>
				</div>

				<div class="card-body p-4">
					<div class="tab-content" id="tutorialTabContent">
						<!-- Category Tab -->
						<div class="tab-pane fade show active" id="category_tab" role="tabpanel" aria-labelledby="category-tab">
							<div class="row g-3 mb-4">
								<div class="col-md-6">
									<label class="form-label fw-semibold">
										<i class="fas fa-language me-2"></i><?= __('admin.select_language') ?>
									</label>
									<select class="form-select form-select-lg" name="language_id2" id="drpLanguage2" onchange="return changeLanguage2();">
										<?php 
										if(isset($languages))
										{
											$language_id=1;
											foreach($languages as $language)
											{?>
												<option <?= isset($userlangid) && $userlangid==$language['id'] ? 'selected' : '' ?> value="<?= $language['id'] ?>"><?= $language['name'] ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
								<div class="col-md-6">
									<label class="form-label fw-semibold opacity-0 d-block">Action</label>
									<a class="btn btn-success w-100" href="<?= base_url('admincontrol/manage_tutorial_catgory/') ?>">
										<i class="fas fa-plus-circle me-2"></i>
										<?= __('admin.add_new_category'); ?>
									</a>
								</div>
							</div>

							<div id="table-category">
								<div class="text-center py-5">
									<div class="spinner-border text-primary" role="status">
										<span class="visually-hidden"><?= __('admin.loading') ?>...</span>
									</div>
								</div>
							</div>
						</div>

						<!-- Tutorial Pages Tab -->
						<div class="tab-pane fade" id="tutorial_tab_option" role="tabpanel" aria-labelledby="pages-tab">
							<div class="row g-3 mb-4">
								<div class="col-md-6">
									<label class="form-label fw-semibold">
										<i class="fas fa-language me-2"></i><?= __('admin.select_language') ?>
									</label>
									<select class="form-select form-select-lg" name="language_id" id="drpLanguage" onchange="return changeLanguage();">
										<?php 
										if(isset($languages))
										{
											$language_id=1;
											foreach($languages as $language)
											{?>
												<option <?= isset($userlangid) && $userlangid==$language['id'] ? 'selected' : '' ?> value="<?= $language['id'] ?>"><?= $language['name'] ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
								<div class="col-md-6">
									<label class="form-label fw-semibold opacity-0 d-block">Action</label>
									<a class="btn btn-success w-100" href="<?= base_url('admincontrol/manage_tutorial/') ?>">
										<i class="fas fa-plus-circle me-2"></i>
										<?= __('admin.add_new_page'); ?>
									</a>
								</div>
							</div>

							<div id="table-tutorial">
								<div class="text-center py-5">
									<div class="spinner-border text-primary" role="status">
										<span class="visually-hidden"><?= __('admin.loading') ?>...</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Bootstrap 5 Modal -->
<div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="tutorialModalLabel">
					<i class="fas fa-graduation-cap me-2"></i><?= __('admin.tutorial') ?>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="<?= __('admin.close') ?>"></button>
			</div>
			<div class="modal-body">
				<!-- Modal content will be loaded here -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function changeLanguage() {
		getTutorials('<?= base_url("admincontrol/listTutorals_ajax")?>');
		return false;
	}

	$("#table-tutorial").on("click", ".pagination-td a", function(e) {
		e.preventDefault();
		getTutorials($(this).attr("href"));
		return false;
	});

	function getTutorials(url) {
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'json',
			data: $("#form1").serialize(),
			beforeSend: function() {
				$("#table-tutorial").html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"><?= __('admin.loading') ?>...</span></div></div>');
			},
			success: function(json) {
				if (json['view']) {
					$("#table-tutorial").html(json['view']);
				} else {
					$("#table-tutorial").html('<div class="text-center py-5"><div class="text-muted"><i class="fas fa-inbox fs-1 d-block mb-3"></i><h5><?= __('admin.no_data_found') ?></h5></div></div>');
				}
				if(json['pagination']) {
					$("#table-tutorial .pagination-td").html(json['pagination']);
				}
			},
			error: function() {
				$("#table-tutorial").html('<div class="text-center py-5"><div class="text-danger"><i class="fas fa-exclamation-triangle fs-1 d-block mb-3"></i><h5><?= __('admin.error_loading_data') ?></h5></div></div>');
			}
		});
	}

	$(document).ready(function() {
		getTutorials('<?= base_url("admincontrol/listTutorals_ajax")?>');
	});

	function changeLanguage2() {
		getCategory('<?= base_url("admincontrol/listTutorialCategory_ajax")?>');
		return false;
	}

	$("#table-category").on("click", ".pagination-td a", function(e) {
		e.preventDefault();
		getCategory($(this).attr("href"));
		return false;
	});

	function getCategory(url) {
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'json',
			data: $("#form2").serialize(),
			beforeSend: function() {
				$("#table-category").html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"><?= __('admin.loading') ?>...</span></div></div>');
			},
			success: function(json) {
				if (json['view']) {
					$("#table-category").html(json['view']);
				} else {
					$("#table-category").html('<div class="text-center py-5"><div class="text-muted"><i class="fas fa-inbox fs-1 d-block mb-3"></i><h5><?= __('admin.no_data_found') ?></h5></div></div>');
				}
				if(json['pagination']) {
					$("#table-category .pagination-td").html(json['pagination']);
				}
			},
			error: function() {
				$("#table-category").html('<div class="text-center py-5"><div class="text-danger"><i class="fas fa-exclamation-triangle fs-1 d-block mb-3"></i><h5><?= __('admin.error_loading_data') ?></h5></div></div>');
			}
		});
	}

	$(document).ready(function() {
		getCategory('<?= base_url("admincontrol/listTutorialCategory_ajax")?>');
	});

	$('.update_all_settings').on('change', function() {
		var checked = $(this).prop('checked');
		var setting_key = $(this).data('setting_key');
		var setting_type = $(this).data('setting_type');
		var status = checked ? 1 : 0;

		$.ajax({
			url: '<?= base_url("admincontrol/update_all_settings") ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				'action': 'update_all_settings',
				'status': status,
				'setting_key': setting_key,
				'setting_type': setting_type
			},
			success: function(json) {
				if (status == 1) {
					showToast('success', '<?= __('admin.tutorial_module_enabled') ?>');
					$('#statusBadge').removeClass('bg-danger').addClass('bg-success');
					$('#statusBadge i').removeClass('fa-times-circle').addClass('fa-check-circle');
					$('#statusText').text('<?= __('admin.enabled') ?>');
				} else {
					showToast('success', '<?= __('admin.tutorial_module_disabled') ?>');
					$('#statusBadge').removeClass('bg-success').addClass('bg-danger');
					$('#statusBadge i').removeClass('fa-check-circle').addClass('fa-times-circle');
					$('#statusText').text('<?= __('admin.disabled') ?>');
				}
			},
			error: function() {
				showToast('error', '<?= __('admin.error_updating_setting') ?>');
			}
		});
	});
</script>
