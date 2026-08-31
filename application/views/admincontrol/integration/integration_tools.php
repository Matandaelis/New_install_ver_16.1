
<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/integration/_campaign_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0"><?= __('admin.menu_affiliate_marketing') ?></h4>
                <?= form_open('integration/integration_tools_load_demo', ['class' => 'd-inline']); ?>
                    <button type="submit" class="btn btn-outline-success btn-sm" title="<?= __('admin.load_demo_campaign_hint') ?>"
                            onclick="return confirm('<?= htmlspecialchars(__('admin.load_demo_campaign_confirm'), ENT_QUOTES, 'UTF-8') ?>');">
                        <i class="bi bi-magic me-1"></i><?= __('admin.load_demo_campaign') ?>
                    </button>
                <?= form_close() ?>
            </div>

            <!-- Campaign Type Cards -->
            <div class="row g-3 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card intg-type-card intg-type-card--banner h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="intg-type-icon">
                                <i class="bi bi-image"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold"><?= __('admin.banner_campaign') ?></h6>
                            </div>
                            <a href="<?= base_url('integration/integration_tools_form/banner') ?>" class="btn btn-sm">
                                <i class="bi bi-plus-lg me-1"></i><?= __('admin.create_new') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card intg-type-card intg-type-card--text h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="intg-type-icon">
                                <i class="bi bi-fonts"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold"><?= __('admin.text_campaign') ?></h6>
                            </div>
                            <a href="<?= base_url('integration/integration_tools_form/text_ads') ?>" class="btn btn-sm">
                                <i class="bi bi-plus-lg me-1"></i><?= __('admin.create_new') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card intg-type-card intg-type-card--link h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="intg-type-icon">
                                <i class="bi bi-link-45deg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold"><?= __('admin.link_campaign') ?></h6>
                            </div>
                            <a href="<?= base_url('integration/integration_tools_form/link_ads') ?>" class="btn btn-sm">
                                <i class="bi bi-plus-lg me-1"></i><?= __('admin.create_new') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card intg-type-card intg-type-card--video h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="intg-type-icon">
                                <i class="bi bi-camera-video"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold"><?= __('admin.video_campaign') ?></h6>
                            </div>
                            <a href="<?= base_url('integration/integration_tools_form/video_ads') ?>" class="btn btn-sm">
                                <i class="bi bi-plus-lg me-1"></i><?= __('admin.create_new') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm intg-filter-card mb-3">
                <div class="card-header intg-filter-header" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-funnel me-2 text-primary"></i><?= __('admin.search_filters') ?>
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filters" onclick="event.stopPropagation();">
                                <i class="bi bi-x-lg me-1"></i><?= __('admin.clear_filters') ?>
                            </button>
                            <i class="bi bi-chevron-down text-muted"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="filterCollapse">
                    <div class="card-body intg-filter-body">
                        <!-- Row 1: Main search filters -->
                        <div class="row g-2 mb-2">
                            <div class="col-xl col-lg-3 col-md-6">
                                <label class="form-label small text-muted mb-1"><?= __('admin.category') ?></label>
                                <select class="form-select form-select-sm category_id">
                                    <option value=""><?= __('admin.search_by_all_categories') ?></option>
                                    <?php 
                                    if(count($categories)>0) {
                                        $parentcategoyrid=0;
                                        foreach ($categories as $key => $value) {
                                            if($parentcategoyrid!=0 && $parentcategoyrid!=$value['pid']) { 
                                            }
                                            if($parentcategoyrid!=$value['pid']) {
                                                ?>
                                                <option value="<?= $value['value'] ?>"><?= $value['label'] ?></option>  
                                                <?php
                                            } else {
                                                ?>
                                                <option value="<?= $value['value'] ?>">--<?= $value['label'] ?></option>
                                                <?php 
                                            }
                                            $parentcategoyrid=$value['pid'];
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-xl col-lg-3 col-md-6">
                                <label class="form-label small text-muted mb-1"><?= __('admin.campaign_name') ?></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input class="form-control ads_name" placeholder="<?= __('admin.search_enter_ads_name') ?>" type="search">
                                </div>
                            </div>
                            <div class="col-xl col-lg-3 col-md-6">
                                <label class="form-label small text-muted mb-1"><?= __('admin.vendor') ?></label>
                                <select name="vendor_id" class="form-select form-select-sm vendor_id">
                                    <?php $selected = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : ''; ?>
                                    <option value=""><?= __('admin.all_campaigns') ?></option>
                                    <option value="only_admins"><?= __('admin.all_admin_campigns') ?></option>
                                    <option value="only_vendors"><?= __('admin.all_vendors_campigs') ?></option>
                                    <?php foreach ($vendors as $key => $value) { ?>
                                        <option <?= $selected == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xl col-lg-3 col-md-6">
                                <label class="form-label small text-muted mb-1"><?= __('admin.groups') ?></label>
                                <select name="groups[]" class="form-select form-select-sm select2 groups" multiple="multiple">
                                    <?php foreach ($groups as $key => $value) { ?>
                                        <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xl col-lg-3 col-md-6">
                                <label class="form-label small text-muted mb-1"><?= __('admin.status') ?></label>
                                <select class="form-select form-select-sm" name="status">
                                    <option value=""><?= __('admin.search_by_all_status') ?></option>
                                    <option value="1"><?= __('admin.public'); ?></option>
                                    <option value="2"><?= __('admin.in_review'); ?></option>
                                    <option value="0"><?= __('admin.draft'); ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- Row 2: Secondary options -->
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="form-check form-switch mb-0">
                                <input type="checkbox" class="form-check-input show_only" name="show_only" value="admin" id="adminOnlySwitch">
                                <label class="form-check-label small" for="adminOnlySwitch"><?= __('admin.show_only_admin') ?></label>
                            </div>
                            <div class="vr d-none d-lg-block"></div>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#cron-job-info-modal">
                                <i class="bi bi-clock me-1"></i><?= __('admin.cron_job_setting') ?>
                            </button>
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#perform-security-check-modal">
                                <i class="bi bi-shield-check me-1"></i><?= __('admin.perform_security_check') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table: row menus must escape .table-responsive overflow and sit above .card-footer -->
<style>
/* Popper may still compute inside a scroll parent; while open we allow overflow to show */
.table-responsive.intg-dropdown-parent-open {
	overflow: visible !important;
}
/* Footer is a sibling after .card-body — without z-index it can paint over the menu */
.intg-table-card.intg-table-card-dropdown-open > .card-body {
	position: relative;
	z-index: 2;
}
.intg-table-card.intg-table-card-dropdown-open > .card-footer {
	position: relative;
	z-index: 1;
}
.intg-table-actions-menu {
	z-index: 1080 !important;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm intg-table-card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 fw-bold">
                            <i class="bi bi-rocket-takeoff me-2 text-primary"></i><?= __('admin.integration_tools') ?>
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-muted border fw-normal" id="results-summary"><?= __('admin.loading') ?>...</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="refresh-data">
                                <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="text-center col-12 empty-div d-none py-5">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <i class="bi bi-rocket-takeoff display-4 text-muted mb-3"></i>
                            <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                            <p class="text-muted"><?= __('admin.no_campaigns_available') ?></p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-hover align-middle mb-0 intg-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="intg-th-image"><?= __('admin.image') ?></th>
                                    <th class="intg-th-name"><?= __('admin.campaign_name') ?></th>
                                    <th class="intg-th-plugin"><?= __('admin.integration_plugin_name') ?></th>
                                    <th class="text-center intg-th-view"><?= __('admin.view') ?></th>
                                    <th class="intg-th-ratio"><?= __('admin.ratio') ?></th>
                                    <th class="text-center intg-th-integration"><?= __('admin.integration_status') ?></th>
                                    <th class="text-center intg-th-status"><?= __('admin.campaign_status') ?></th>
                                    <th class="text-center intg-th-action"><?= __('admin.action') ?></th>
                                </tr>
                            </thead>
                            <tbody class="integration-product"></tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted" id="pagination-info"></small>
                        <nav aria-label="<?= __('admin.pagination') ?>">
                            <ul class="pagination pagination-sm mb-0 pagination-td"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Container -->
<div id="campaign-modals-container"></div>

        </div>
    </div>
</div>

<!-- MLM Info Modal -->
<div class="modal fade" id="integration-mlm-info" tabindex="-1" aria-labelledby="mlmInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<!-- Integration Code Modal -->
<div class="modal fade" id="integration-code" tabindex="-1" aria-labelledby="integrationCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<!-- Show Code Modal -->
<div class="modal fade" id="showcode-code" tabindex="-1" aria-labelledby="showCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<!-- Cron Job Info Modal -->
<div class="modal fade" id="cron-job-info-modal" tabindex="-1" aria-labelledby="cronJobModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable intg-modal">
        <div class="modal-content">
            <div class="intg-modal-header">
                <div class="intg-modal-header-left">
                    <div class="intg-modal-icon intg-modal-icon--primary">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <h5 class="intg-modal-title" id="cronJobModalLabel"><?= __('admin.cron_job_setting') ?></h5>
                        <p class="intg-modal-subtitle"><?= __('admin.what_is_cron_job') ?></p>
                    </div>
                </div>
                <button type="button" class="intg-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="intg-modal-card mb-3">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-info-circle text-primary mt-1"></i>
                        <div class="small"><?= __('admin.what_is_cron_job_answer') ?></div>
                    </div>
                </div>

                <div class="intg-modal-section-title"><i class="bi bi-list-ol"></i> <?= __('admin.to_add_cron_job_steps') ?></div>
                
                <div class="d-flex flex-column gap-2">
                    <?php for ($step = 1; $step <= 6; $step++) { ?>
                    <div class="intg-modal-card d-flex align-items-start gap-2">
                        <span class="badge rounded-pill bg-primary flex-shrink-0 mt-1"><?= $step ?></span>
                        <div class="small">
                            <?= __('admin.to_add_cron_job_step'.$step) ?>
                            <?php if ($step == 4) { ?>
                                <code class="ms-1 px-2 py-1 rounded"><?= __('admin.once_per_minute') ?> (* * * * *)</code>
                            <?php } ?>
                            <?php if ($step == 5) { ?>
                                <div class="mt-2">
                                    <code class="bg-dark text-light p-2 rounded d-block small">curl <?= base_url('/cronJob/check_campaign_security') ?></code>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="intg-modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Security Check Modal -->
<div class="modal fade" id="perform-security-check-modal" tabindex="-1" aria-labelledby="securityCheckModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered intg-modal">
        <div class="modal-content">
            <div class="intg-modal-header">
                <div class="intg-modal-header-left">
                    <div class="intg-modal-icon intg-modal-icon--warning">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h5 class="intg-modal-title" id="securityCheckModalLabel"><?= __('admin.perform_security_check') ?></h5>
                        <p class="intg-modal-subtitle"><?= __('admin.take_longer_depending_campaigns_available') ?></p>
                    </div>
                </div>
                <button type="button" class="intg-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="step-1">
                    <div class="intg-modal-card d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle text-warning mt-1"></i>
                        <div class="small">
                            <strong><?= __('admin.are_you_sure_perform_security_check') ?></strong>
                        </div>
                    </div>
                </div>
                
                <div class="step-2 intg-step-hidden">
                    <div class="text-center mb-3" id="admin-check-spinner-text">
                        <h6 class="mb-3">
                            <i class="bi bi-arrow-repeat intg-spin me-2 text-primary"></i><?= __('admin.wait_while_performing_security') ?>
                        </h6>
                        <div class="progress mb-3 intg-progress-bar">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="security-progress">
                                0%
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="intg-modal-card text-center postback-campaigns intg-result-card-hidden">
                                <i class="bi bi-arrow-left-right d-block mb-1 text-success intg-modal-result-icon"></i>
                                <div class="intg-modal-card-title justify-content-center"><?= __('admin.postback_campaigns') ?></div>
                                <div class="intg-modal-stat-value text-success fs-5" id="postback-count">0</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="intg-modal-card text-center approved intg-result-card-hidden" data-count="0">
                                <i class="bi bi-check-circle d-block mb-1 text-success intg-modal-result-icon"></i>
                                <div class="intg-modal-card-title justify-content-center"><?= __('admin.verified') ?></div>
                                <div class="intg-modal-stat-value text-success fs-5" id="approved-count">0</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="intg-modal-card text-center pending intg-result-card-hidden" data-count="0">
                                <i class="bi bi-clock d-block mb-1 text-info intg-modal-result-icon"></i>
                                <div class="intg-modal-card-title justify-content-center"><?= __('admin.pending') ?></div>
                                <div class="intg-modal-stat-value text-info fs-5" id="pending-count">0</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="intg-modal-card warning intg-result-card-hidden text-center mt-3">
                        <i class="bi bi-exclamation-triangle d-block mb-1 text-warning intg-modal-result-icon"></i>
                        <div class="small fw-semibold"><?= __('admin.no_campagins_available') ?></div>
                    </div>
                </div>
            </div>
            
            <div class="intg-modal-footer">
                <button type="button" class="btn btn-primary rounded-pill allow_to_perform_security_check">
                    <i class="bi bi-play me-1"></i><?= __('admin.yes_continue') ?>
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>



<?= $social_share_modal; ?>

<script type="text/javascript">
/** Integration tools table: row dropdowns inside .table-responsive + card footer stacking */
function intgBindTableDropdownFix() {
	$(document).off('show.bs.dropdown.intgFix', '#myTable .intg-action-dropdown');
	$(document).off('hide.bs.dropdown.intgFix', '#myTable .intg-action-dropdown');
	$(document).on('show.bs.dropdown.intgFix', '#myTable .intg-action-dropdown', function () {
		var $dd = $(this);
		$dd.closest('.table-responsive').addClass('intg-dropdown-parent-open');
		$dd.closest('.intg-table-card').addClass('intg-table-card-dropdown-open');
	});
	$(document).on('hide.bs.dropdown.intgFix', '#myTable .intg-action-dropdown', function () {
		var $dd = $(this);
		$dd.closest('.table-responsive').removeClass('intg-dropdown-parent-open');
		$dd.closest('.intg-table-card').removeClass('intg-table-card-dropdown-open');
	});
}

/** After AJAX injects rows, (re)apply Popper fixed — data attrs alone can be ignored if instance already exists */
function intgInitIntegrationTableDropdowns() {
	if (typeof bootstrap === 'undefined' || !bootstrap.Dropdown) {
		return;
	}
	document.querySelectorAll('#myTable .intg-action-dropdown [data-bs-toggle="dropdown"]').forEach(function (toggleEl) {
		try {
			var existing = bootstrap.Dropdown.getInstance(toggleEl);
			if (existing) {
				existing.dispose();
			}
			bootstrap.Dropdown.getOrCreateInstance(toggleEl, {
				popperConfig: {
					strategy: 'fixed',
					modifiers: [
						{ name: 'preventOverflow', options: { boundary: document.body } },
						{ name: 'flip', options: { boundary: document.body } }
					]
				}
			});
		} catch (e) {
			if (window.console && console.warn) {
				console.warn('intgInitIntegrationTableDropdowns', e);
			}
		}
	});
}

$(document).ready(function() {
	intgBindTableDropdownFix();
	$('[data-bs-toggle="tooltip"]').tooltip();
    
    $('.select2').select2({
        placeholder: '<?= __('admin.search_by_groups') ?>',
        allowClear: true
    });
    
    $('#clear-filters').on('click', function() {
        $('.category_id').val('').trigger('change');
        $('.ads_name').val('');
        $('.vendor_id').val('').trigger('change');
        $('.select2').val(null).trigger('change');
        $('.show_only').prop('checked', false);
        $('select[name="status"]').val('').trigger('change');
        getPage('<?= base_url("integration/integration_tools/1") ?>');
    });
    
    $('#refresh-data').on('click', function() {
        getPage('<?= base_url("integration/integration_tools/1") ?>');
    });
    
    getPage('<?= base_url("integration/integration_tools") ?>/1');
});

$(document).on('click','.check-campaign-with-id',function(){
    var el = $(this);
    var id = el.data('id');
    var originalHtml = el.html();
    
    el.prop('disabled', true).html('<i class="bi bi-arrow-repeat intg-spin me-1"></i><?= __('admin.checking') ?>...');
    
    $.ajax({
        type: "POST",
        url: '<?= base_url('Integration/check_campaign_security_with_id/') ?>' + id,
        dataType: "json",
        success: function(data){
            if(data.statusClass){
                el.parents('td').siblings('.security-status').find('button.badge').remove();
                el.parents('td').siblings('.security-status').find('span.badge').removeClass().addClass(data.statusClass).text(data.message);
                
                if(data.security_status == 0)
                    el.parents('td').siblings('.security-status').prepend(data.integration_code_button);
                    
                showToast('<?= __('admin.success') ?>', '<?= __('admin.security_check_completed') ?>', 'success');
            }
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.security_check_failed') ?>', 'error');
        },
        complete: function() {
            el.prop('disabled', false).html(originalHtml);
        }
    });
})

$(document).on('click', '.allow_to_perform_security_check', function(){
    $(this).hide();
    $('#perform-security-check-modal .step-1').hide();
    $('#perform-security-check-modal .step-2').removeClass('intg-step-hidden').show();
    $('#perform-security-check-modal .intg-modal-footer').hide();
    $('#perform-security-check-modal .intg-modal-close').prop('disabled', true);
    recursive_security_check();
});


let postbackCount = 0;
function recursive_security_check(index = 1) {
    $.ajax({
        type: "POST",
        url: '<?= base_url('integration/check_campaign_security') ?>',
        dataType: "json",
        data: { index: index },
        success: function(data) {
            if (data.progress_percentage) {
                const percentage = parseInt(data.progress_percentage.replace('%', ''));
                $('#security-progress').css('width', data.progress_percentage)
                    .attr('aria-valuenow', percentage)
                    .text(data.progress_percentage);
            }

            if (data.warning) {
                $('#perform-security-check-modal .step-2 .progress').hide();
                $('#perform-security-check-modal .step-2 #admin-check-spinner-text').hide();
                $('#perform-security-check-modal .step-2 .warning').removeClass('intg-result-card-hidden').show();
            } else {
                let statusElement = $('#perform-security-check-modal .step-2 .' + data.security_status);
                let existing_count = statusElement.data('count') || 0;
                statusElement.data('count', existing_count + 1);
                
                if (data.security_status === 'approved') {
                    $('#approved-count').text(existing_count + 1);
                    statusElement.removeClass('intg-result-card-hidden').show();
                } else if (data.security_status === 'pending') {
                    $('#pending-count').text(existing_count + 1);
                    statusElement.removeClass('intg-result-card-hidden').show();
                } else if (data.security_status === 'postback') {
                    postbackCount++;
                    $('#postback-count').text(postbackCount);
                    $('.postback-campaigns').removeClass('intg-result-card-hidden').show();
                }
            }

            if (data.index) {
                recursive_security_check(data.index);
            } else {
                $('#perform-security-check-modal .step-2 #admin-check-spinner-text').hide();
                $('#perform-security-check-modal .step-2 .progress').hide();
                $('#perform-security-check-modal .intg-modal-footer').show()
                    .html(
                        '<button type="button" class="btn btn-success rounded-pill" onclick="window.location.reload()">' +
                        '<i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh_page') ?></button>' +
                        '<button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">' +
                        '<i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?></button>'
                    );
                
                $('#perform-security-check-modal .intg-modal-close').prop('disabled', false);
                showToast('<?= __('admin.success') ?>', '<?= __('admin.security_check_completed') ?>', 'success');
            }
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.security_check_failed') ?>', 'error');
            $('#perform-security-check-modal .modal-footer').show();
            $('#perform-security-check-modal .btn-close').prop('disabled', false);
        }
    });
}


$(document).on('click', ".btn_lang_toggle", function(){
    let skip_change = false;
    let id = $(this).data('lang_id');
    let column = $(this).data('column');
    let status = $(this).hasClass('bi-toggle-off') ? 1 : 0;
    $(this).addClass('bi-toggle-off').removeClass('bi-toggle-on');
    $(this).css("color", "red");

    if(status) {
        $(this).addClass('bi-toggle-on').removeClass('bi-toggle-off');
        $(this).css("color", "green");
    }
});



var xhr;
var isLoading = false;

function getPage(url) {
    if (isLoading) return;
    if (xhr && xhr.readyState != 4) xhr.abort();
    
    isLoading = true;
    $('#refresh-data').prop('disabled', true).html('<i class="bi bi-arrow-repeat intg-spin me-1"></i><?= __('admin.loading') ?>...');
    
    $('#results-summary').html('<i class="bi bi-arrow-repeat intg-spin me-1"></i><?= __('admin.loading') ?>...');
    
    var requestData = {
        category_id: $(".category_id").val(),
        ads_name: $(".ads_name").val(),
        vendor_id: $(".vendor_id").val(),
        groups: $('.select2').val(),
        show_only: $(".show_only").prop("checked"),
        status: $("select[name='status']").val()
    };
    
    xhr = $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: requestData,
        success: function(json) {
            if (json) {
                $("#myTable tbody").html(json);
                $("#myTable").show();
                $(".empty-div").addClass("d-none");
                intgInitIntegrationTableDropdowns();
                
                var rowCount = $("#myTable tbody tr").length;
                $('#results-summary').html(rowCount + ' <?= __('admin.campaigns_found') ?>');
            } else {
                $(".empty-div").removeClass("d-none");
                $("#myTable").hide();
                $('#results-summary').html('<?= __('admin.no_data_found') ?>');
            }
            
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error');
            $('#results-summary').html('<?= __('admin.error_loading_data') ?>');
        }
    });
    
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: $.extend({}, requestData, {paginate: true}),
        success: function(json) {
            if (json) {
                $("#myTable .pagination-td").html(json);
            }
        },
        complete: function() {
            isLoading = false;
            $('#refresh-data').prop('disabled', false).html('<i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>');
        }
    });
}

var searchTimeout;
$(".category_id,.vendor_id,.select2,.show_only,select[name='status']").on("change", function() {
    getPage('<?= base_url("integration/integration_tools/1") ?>');
});

$(".ads_name").on("keyup", function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        getPage('<?= base_url("integration/integration_tools/1") ?>');
    }, 500);
});

$(document).on("click", "#myTable .pagination-td a", function(e) {
    e.preventDefault();
    if (!isLoading) {
        getPage($(this).attr("href"));
    }
    return false;
});

$(document).on('click', '.btn-show-integration-mlm-info', function() {
    var $this = $(this);
    var originalHtml = $this.html();
    
    $.ajax({
        url: '<?= base_url("integration/getIntegrationMlmInfo") ?>',
        type: 'POST',
        dataType: 'html',
        data: { id: $this.attr("data-id") },
        beforeSend: function() {
            $this.prop('disabled', true).html('<i class="bi bi-arrow-repeat intg-spin me-1"></i><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $this.prop('disabled', false).html(originalHtml);
        },
        success: function(html) {
            $("#integration-mlm-info .modal-dialog").html(html);
            $("#integration-mlm-info").modal("show");
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_mlm_info') ?>', 'error');
        }
    });
});

$(document).on('click', '.btn-show-code', function() {
    var $this = $(this);
    var originalHtml = $this.html();
    
    $.ajax({
        url: '<?= base_url("integration/integration_code_modal") ?>',
        type: 'POST',
        dataType: 'html',
        data: { id: $this.attr("data-id") },
        beforeSend: function() {
            $this.prop('disabled', true).html('<i class="bi bi-arrow-repeat intg-spin me-1"></i><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $this.prop('disabled', false).html(originalHtml);
        },
        success: function(html) {
            $("#showcode-code .modal-dialog").html(html);
            $("#showcode-code").modal("show");
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_code') ?>', 'error');
        }
    });
});

$(document).on('click', '.btn-show-setup', function() {
    var $this = $(this);
    var originalHtml = $this.html();
    $.ajax({
        url: '<?= base_url("integration/integration_setup_modal") ?>',
        type: 'POST',
        dataType: 'html',
        data: { id: $this.attr("data-id") },
        beforeSend: function() {
            $this.prop('disabled', true).html('<i class="bi bi-arrow-repeat intg-spin me-1"></i>');
        },
        complete: function() {
            $this.prop('disabled', false).html(originalHtml);
        },
        success: function(html) {
            $("#showcode-code .modal-dialog").html(html);
            $("#showcode-code").modal("show");
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_setup') ?>', 'error');
        }
    });
});

$(document).on('click', '.btn-show-terms', function() {
    var $this = $(this);
    var originalHtml = $this.html();
    
    $.ajax({
        url: '<?= base_url("integration/integration_terms_modal") ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: $this.attr("data-id") },
        beforeSend: function() {
            $this.prop('disabled', true).html('<i class="bi bi-arrow-repeat intg-spin me-1"></i><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $this.prop('disabled', false).html(originalHtml);
        },
        success: function(json) {
            if (json['html']) {
                $("#showcode-code .modal-dialog").html(json['html']);
                $("#showcode-code").modal("show");
            }
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_terms') ?>', 'error');
        }
    });
});

$(document).on('click', '.btn-campaign-detail', function() {
    var id = $(this).attr("data-id");
    $("#campaign-details-" + id).modal("show");
});

$(document).on('click', '.wallet-toggle .tog', function() {
    $(this).parents(".wallet-toggle").find("> div").toggleClass("hide");
});

$(document).on('click', '.tool-remove-link', function(e) {
    e.preventDefault();
    var $this = $(this);
    var href = $this.attr('href');
    
    if (confirm('<?= __('admin.are_you_sure') ?>')) {
        window.location.href = href;
    }
    return false;
});

$(document).on('click', '.get-code', function() {
    var $this = $(this);
    var originalHtml = $this.html();
    
    $.ajax({
        url: '<?= base_url("integration/tool_get_code") ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: $this.attr("data-id") },
        beforeSend: function() {
            $this.prop('disabled', true).html('<i class="bi bi-arrow-repeat intg-spin me-1"></i><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $this.prop('disabled', false).html(originalHtml);
        },
        success: function(json) {
            if (json['html']) {
                $("#integration-code .modal-content").html(json['html']);
                $("#integration-code").modal("show");
            }
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_get_code') ?>', 'error');
        }
    });
});

</script>

        </div>
    </div>
