<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header users-page-header bg-white border-bottom py-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3 min-w-0">
                            <span class="users-page-header__icon">
                                <i class="fa fa-users"></i>
                            </span>
                            <div class="min-w-0">
                                <h5 class="card-title mb-0 text-dark fw-bold text-truncate">
                                    <?= __('admin.page_title_userslist') ?>
                                </h5>
                                <small class="text-muted"><?= __('admin.manage_all_affiliates') ?></small>
                            </div>
                        </div>
                        <div>
                            <a class="btn btn-primary fw-semibold px-3 users-cta-add" href="<?= base_url("admincontrol/addusers") ?>">
                                <i class="fa fa-plus me-1"></i><?= __('admin.add_affiliate') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="search-form">
                        <!-- Filters group -->
                        <fieldset class="users-group users-group--filters mb-3">
                            <legend class="users-group__title">
                                <i class="fa fa-filter"></i><?= __('admin.filters') ?>
                                <span class="users-group__hint"><?= __('admin.filters_hint') ?></span>
                            </legend>
                            <div class="row g-2 align-items-end">
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <label for="search-name" class="form-label fw-semibold mb-1 small">
                                        <i class="fa fa-user me-1 text-primary"></i><?= __('admin.name') ?>
                                    </label>
                                    <input type="search" id="search-name" name="name" class="form-control form-control-sm" placeholder="<?= __('admin.search_by_name') ?>">
                                </div>
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <label for="search-email" class="form-label fw-semibold mb-1 small">
                                        <i class="fa fa-envelope me-1 text-primary"></i><?= __('admin.email') ?>
                                    </label>
                                    <input type="search" id="search-email" name="email" class="form-control form-control-sm" placeholder="<?= __('admin.search_by_email') ?>">
                                </div>
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <label for="search-groups" class="form-label fw-semibold mb-1 small">
                                        <i class="fa fa-users me-1 text-primary"></i><?= __('admin.groups') ?>
                                    </label>
                                    <select id="search-groups" class="form-select form-select-sm select2" name="groups[]" multiple="multiple" data-placeholder="<?= __('admin.filter_by_groups') ?>">
                                        <?php foreach ($user_groups as $key => $group) { ?>
                                            <option value="<?= $group->id ?>">
                                                <?= $group->group_name ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-xl-2">
                                    <label for="search-health-score-band" class="form-label fw-semibold mb-1 small">
                                        <i class="fa fa-heartbeat me-1 text-danger"></i><?= __('admin.health_score_filter_label') ?>
                                    </label>
                                    <select id="search-health-score-band" name="health_score_band" class="form-select form-select-sm w-100">
                                        <option value="all"><?= htmlspecialchars(__('admin.health_score_filter_all'), ENT_QUOTES, 'UTF-8') ?></option>
                                        <option value="high"><?= htmlspecialchars(__('admin.health_score_filter_high'), ENT_QUOTES, 'UTF-8') ?></option>
                                        <option value="medium"><?= htmlspecialchars(__('admin.health_score_filter_medium'), ENT_QUOTES, 'UTF-8') ?></option>
                                        <option value="low"><?= htmlspecialchars(__('admin.health_score_filter_low'), ENT_QUOTES, 'UTF-8') ?></option>
                                        <option value="critical"><?= htmlspecialchars(__('admin.health_score_filter_critical'), ENT_QUOTES, 'UTF-8') ?></option>
                                    </select>
                                </div>
                                <div class="col-12 col-xl-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                        onclick="$('#search-form')[0].reset(); $('#search-groups').val(null).trigger('change'); getPage(1);"
                                        title="<?= __('admin.clear') ?>">
                                        <i class="fa fa-times me-1"></i><?= __('admin.clear') ?>
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </form>

                    <!-- Action groups: each panel = one clearly labeled category -->
                    <div class="row g-2 mb-3 users-action-row">

                        <!-- Bulk actions -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <fieldset class="users-group users-group--bulk h-100">
                                <legend class="users-group__title">
                                    <i class="fa fa-check-square-o"></i><?= __('admin.bulk_actions') ?>
                                </legend>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-outline-danger btn-sm delete-multiple" type="button" disabled
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="<?= htmlspecialchars(__('admin.delete_selected_hint'), ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="fa fa-trash me-1"></i><?= __('admin.delete_selected') ?> <span class="selected-count"></span>
                                    </button>
                                </div>
                            </fieldset>
                        </div>

                        <!-- Data -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <fieldset class="users-group users-group--data h-100">
                                <legend class="users-group__title">
                                    <i class="fa fa-exchange"></i><?= __('admin.data_tools') ?>
                                </legend>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-info btn-sm import-excel" data-bs-toggle="modal" data-bs-target="#importUsersModel">
                                        <i class="fa fa-file-import me-1"></i><?= __('admin.import') ?>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm export-excel">
                                        <i class="fa fa-file-export me-1"></i><?= __('admin.export') ?>
                                    </button>
                                </div>
                            </fieldset>
                        </div>

                        <?php if (function_exists('can_admin') && can_admin('settings.mails')): ?>
                        <!-- Email lists -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <fieldset class="users-group users-group--email h-100">
                                <legend class="users-group__title">
                                    <i class="fa fa-envelope"></i><?= __('admin.email_lists') ?>
                                </legend>
                                <div class="d-flex flex-wrap gap-2">
                                    <a class="btn btn-outline-success btn-sm" href="<?= base_url('admincontrol/subscriber_list') ?>"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="<?= htmlspecialchars(__('admin.subscriber_list_desc'), ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="fa fa-envelope-open-text me-1"></i><?= __('admin.subscriber_list') ?>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm" href="<?= base_url('admincontrol/unsubscribe_list') ?>"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="<?= htmlspecialchars(__('admin.unsubscribe_list_desc'), ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="fa fa-envelope-circle-check me-1"></i><?= __('admin.unsubscribe_list') ?>
                                    </a>
                                </div>
                            </fieldset>
                        </div>
                        <?php endif; ?>

                        <!-- Maintenance -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <fieldset class="users-group users-group--maintenance h-100">
                                <legend class="users-group__title">
                                    <i class="fa fa-wrench"></i><?= __('admin.users_list_maintenance') ?>
                                </legend>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-recalc-health-scores"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        data-bs-custom-class="users-health-tooltip"
                                        title="<?= htmlspecialchars(__('admin.recalculate_health_scores_help'), ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="fa fa-heartbeat text-danger me-1"></i><?= __('admin.recalculate_health_scores') ?>
                                    </button>
                                </div>
                                <div id="health-score-recalc-status" class="small text-muted text-break mt-1"></div>
                            </fieldset>
                        </div>

                    </div>
                    <!-- Selection Status -->
                    <div class="selection-message alert alert-info border-start border-4 border-info d-none" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-info-circle me-2"></i>
                            <div class="flex-grow-1">
                                <strong><?= __('admin.all') ?> <span class="selected-count"></span></strong> <?= __('admin.users_on_this_page_are_selected') ?>
                            </div>
                            <div class="btn-group ms-3" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary select-all-users">
                                    <?= __('admin.select_all') ?> <span class="total-user"></span> <?= __('admin.users') ?>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary clear-selection">
                                    <?= __('admin.clear_selection') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Loading Overlay -->
                    <div class="dimmer">
                        <div class="loader">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden"><?= __('admin.loading') ?></span>
                            </div>
                        </div>
                        <div class="dimmer-content">
                            <!-- Status Filter Tabs -->
                            <div class="table-header-menus mb-2">
                                <div class="d-flex flex-wrap align-items-center justify-content-between">
                                    <div class="user-approvals-filer">
                                        <div class="btn-group" role="group" aria-label="<?= __('admin.approval_filters') ?>">
																		<?php if ($approvals_count['total'] > 0) { ?>
                                                <button type="button" class="btn btn-sm <?= (!isset($_GET['apr']) || $_GET['apr'] == 'all') ? 'btn-primary' : 'btn-outline-primary' ?>" data-apr="all">
                                                    <i class="fa fa-users me-1"></i>
                                                    <?= __('admin.show_all_users') ?> 
                                                    <span class="badge bg-light text-dark ms-1"><?= $approvals_count['total']; ?></span>
                                                </button>
																			<?php } ?>
																			<?php if ($approvals_count['pending'] > 0 || $approvals_count['declined'] > 0) { ?>
																				<?php if ($approvals_count['approved'] > 0) { ?>
                                                    <button type="button" class="btn btn-sm <?= (isset($_GET['apr']) && $_GET['apr'] == 'approved') ? 'btn-success' : 'btn-outline-success' ?>" data-apr="approved">
                                                        <i class="fa fa-check me-1"></i>
                                                        <?= __('admin.show_approved_users') ?> 
                                                        <span class="badge bg-light text-dark ms-1"><?= $approvals_count['approved']; ?></span>
                                                    </button>
																				<?php } ?>
																				<?php if ($approvals_count['pending'] > 0) { ?>
                                                    <button type="button" class="btn btn-sm <?= (isset($_GET['apr']) && $_GET['apr'] == 'pending') ? 'btn-warning' : 'btn-outline-warning' ?>" data-apr="pending">
                                                        <i class="fa fa-clock-o me-1"></i>
                                                        <?= __('admin.show_pending_users') ?> 
                                                        <span class="badge bg-light text-dark ms-1"><?= $approvals_count['pending'] ?></span>
                                                    </button>
																				<?php } ?>
																				<?php if ($approvals_count['declined'] > 0) { ?>
                                                    <button type="button" class="btn btn-sm <?= (isset($_GET['apr']) && $_GET['apr'] == 'declined') ? 'btn-danger' : 'btn-outline-danger' ?>" data-apr="declined">
                                                        <i class="fa fa-times me-1"></i>
                                                        <?= __('admin.show_declined_users') ?> 
                                                        <span class="badge bg-light text-dark ms-1"><?= $approvals_count['declined'] ?></span>
                                                    </button>
																				<?php } ?>
																			<?php } ?>
																			</div>
									</div>
								</div>
								<div class="multi-approve-decline d-none">
									<div class="btn-group" role="group">
										<button type="button" class="btn btn-sm btn-outline-success approved-decline-action" data-action-value="1">
											<i class="fa fa-check me-1"></i><?= __('admin.approved') ?>
										</button>
										<button type="button" class="btn btn-sm btn-outline-danger approved-decline-action" data-action-value="2">
											<i class="fa fa-times me-1"></i><?= __('admin.decline') ?>
										</button>
									</div>
								</div>
							</div>
						</div>

						<div class="table-responsive">
							<table id="users-table" class="table table-hover align-middle user-table">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center" style="width: 80px;">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input selectall-wallet-checkbox" type="checkbox" id="selectAll">
                                                        <label class="form-check-label" for="selectAll">
                                                            <span class="visually-hidden"><?= __('admin.select_all') ?></span>
                                                        </label>
                                                    </div>
                                                    <i class="fa fa-arrows text-muted" style="font-size: 10px;" data-bs-toggle="tooltip" title="<?= __('admin.drag_rows_to_reorganize') ?>"></i>
                                                </div>
                                            </th>
                                            <th class="fw-semibold">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa fa-user me-2 text-primary"></i>
                                                    <div>
                                                        <div><?= __('admin.user_details') ?></div>
                                                        <small class="text-muted fw-normal"><?= __('admin.name_email_username') ?></small>
                                                    </div>
                                                    <i class="fa fa-info-circle ms-2 text-primary" data-bs-toggle="tooltip" title="<?= __('admin.drag_user_rows_to_change_hierarchy') ?>"></i>
                                                </div>
                                            </th>
                                            <th class="fw-semibold text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-level-up me-2 text-primary"></i>
                                                    <div><?= __('admin.user_level') ?></div>
                                                    <small class="text-muted fw-normal"><?= __('admin.commission_level') ?></small>
                                                </div>
                                            </th>
                                            <th class="fw-semibold text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-star me-2 text-primary"></i>
                                                    <div><?= __('admin.membership_details') ?></div>
                                                    <small class="text-muted fw-normal"><?= __('admin.plan_and_status') ?></small>
                                                </div>
                                            </th>
                                            <th class="fw-semibold text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-info-circle me-2 text-primary"></i>
                                                    <div><?= __('admin.plan_status') ?></div>
                                                    <small class="text-muted fw-normal"><?= __('admin.active_expired') ?></small>
                                                </div>
                                            </th>
                                            <th class="fw-semibold text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-line-chart me-2 text-primary"></i>
                                                    <div>Health</div>
                                                    <small class="text-muted fw-normal">0-100 index</small>
                                                </div>
                                            </th>
                                            <th class="fw-semibold text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-globe me-2 text-primary"></i>
                                                    <div><?= __('admin.country') ?></div>
                                                    <small class="text-muted fw-normal"><?= __('admin.location') ?></small>
                                                </div>
                                            </th>
                                            <th class="fw-semibold text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-users me-2 text-primary"></i>
                                                    <div><?= __('admin.groups') ?></div>
                                                    <small class="text-muted fw-normal"><?= __('admin.user_groups') ?></small>
                                                </div>
                                            </th>
                                            <th class="fw-semibold text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-store me-2 text-primary"></i>
                                                    <div><?= __('admin.vendor') ?></div>
                                                    <small class="text-muted fw-normal"><?= __('admin.vendor_status') ?></small>
                                                </div>
                                            </th>
                                            <th class="fw-semibold">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa fa-sitemap me-2 text-primary"></i>
                                                    <div>
                                                        <div><?= __('admin.referred_by') ?></div>
                                                        <small class="text-muted fw-normal"><?= __('admin.parent_affiliate') ?></small>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="fw-semibold text-center" style="width: 200px;">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fa fa-cogs me-2 text-primary"></i>
                                                    <div><?= __('admin.action') ?></div>
                                                    <small class="text-muted fw-normal"><?= __('admin.manage_user') ?></small>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="11" class="bg-light">
                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                    <div class="pagination-summary small text-muted"></div>
                                                    <nav aria-label="<?= __('admin.pagination') ?>">
                                                        <div class="pagination-container mb-0"></div>
                                                    </nav>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importUsersModel" tabindex="-1" aria-labelledby="importUsersModelLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="importUsersModelLabel"><?= __('admin.import_users') ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="import_form">
					<label class="file">
						<input name="import_control" type="file" id="import_control" aria-label="File browser example">
						<span class="file-custom"></span>
					</label>
				</form>
				<div id="import-status"></div>
				<div id="import-log"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
				<a href="" class="btn btn-secondary d-none"><?= __('admin.close') ?></a>
				<button type="button" class="btn btn-primary btn_import_data" data-bs-dismiss="modal"><?= __('admin.upload') ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-edit-plan" tabindex="-1" aria-labelledby="modal-edit-planLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="modal-edit-planLabel">
					<i class="fa fa-edit me-2"></i><?= __('admin.edit_plan') ?>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-0">
				<div class="d-flex justify-content-center align-items-center p-5" id="edit-plan-loading">
					<div class="text-center">
						<div class="spinner-border text-primary mb-3" role="status">
							<span class="visually-hidden"><?= __('admin.loading') ?></span>
						</div>
						<div class="text-muted"><?= __('admin.edit_plan') ?>...</div>
					</div>
				</div>
				<div id="edit-plan-content" class="p-3 d-none"></div>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
					<i class="fa fa-times me-1"></i><?= __('admin.close') ?>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modal-deleteLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-danger text-white">
				<h5 class="modal-title" id="modal-deleteLabel">
					<i class="fa fa-exclamation-triangle me-2"></i><?= __('admin.delete_user') ?>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="message" class="alert alert-warning border-0"></div>
				<hr>
				<div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="delete_transaction" id="delete_transaction">
						<label class="form-check-label fw-semibold" for="delete_transaction">
							<i class="fa fa-money me-1 text-warning"></i><?= __('admin.delete_all_transaction_or_commission') ?>
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
					<i class="fa fa-times me-1"></i><?= __('admin.cancel') ?>
				</button>
				<button type="button" class="btn btn-danger confirm-delete" data-id="0">
					<i class="fa fa-trash me-1"></i><?= __('admin.delete') ?>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-tree" tabindex="-1" aria-labelledby="modal-treeLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="modal-treeLabel">
					<i class="fa fa-sitemap me-2"></i><?= __('admin.quick_view_downline') ?>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-0">
				<div class="d-flex justify-content-center align-items-center p-5" id="tree-loading">
					<div class="text-center">
						<div class="spinner-border text-primary mb-3" role="status">
							<span class="visually-hidden"><?= __('admin.loading') ?></span>
						</div>
						<div class="text-muted"><?= __('admin.quick_view_downline') ?>...</div>
					</div>
				</div>
				<div id="tree-content" class="p-3 d-none"></div>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
					<i class="fa fa-times me-1"></i><?= __('admin.close') ?>
				</button>
			</div>
		</div>
	</div>
</div>



<!-- Include shared drag and drop functionality -->
<script src="<?= base_url('assets/template/js/admin-drag-drop.js'); ?>?v=<?= av() ?>"></script>

<?= render_performance_indicator() ?>

<script>
// Set up configuration and translations for shared drag and drop
window.AdminDragDropConfig = {
  baseUrl: '<?= base_url() ?>'
};

window.AdminDragDropTranslations = {
  confirm_hierarchy_change: '<?= __("admin.change_under_affiliate") ?>',
  hierarchy_change_warning: '<?= __("admin.confirm_change_under_affiliate") ?>',
  move_user_under_new_parent: '<?= __("admin.confirm_change_under_affiliate") ?>',
  user_to_move: '<?= __("admin.user") ?>',
  new_parent: '<?= __("admin.new_under_affiliate") ?>',
  cancel: '<?= __("admin.cancel") ?>',
  confirm_change: '<?= __("admin.confirm") ?>',
  success: '<?= __("admin.success") ?>',
  error: '<?= __("admin.error") ?>',
  hierarchy_changed_successfully: '<?= __("admin.hierarchy_updated") ?>',
  hierarchy_change_failed: '<?= __("admin.save_failed") ?>'
};

// Custom AJAX handler for users list (uses different endpoint)
AdminDragDrop.executeHierarchyChange = function(childId, newParentId) {
  const modal = bootstrap.Modal.getInstance(document.getElementById('dndConfirmModal'));
  modal.hide();
  
  const translations = window.AdminDragDropTranslations || {};
  
    fetch('<?= base_url('admincontrol/users_change_parent') ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: new URLSearchParams({ child_id: childId, new_parent_id: newParentId })
    })
    .then(r => r.json())
    .then(json => {
      if(json && json.success){
        if (typeof window.showToast === 'function') {
        window.showToast(translations.success, translations.hierarchy_changed_successfully, 'success', 3000);
        }
        const currentPageLink = document.querySelector('.user-table tfoot .pagination .active a');
        const page = currentPageLink ? currentPageLink.getAttribute('data-ci-pagination-page') : 1;
        if(typeof getPage === 'function') getPage(page);
      } else {
        const msg = (json && json.message) ? json.message : '<?= __('admin.invalid_move') ?>';
        if (typeof window.showToast === 'function') {
        window.showToast(translations.error, msg, 'error', 5000);
        } else {
          alert(msg);
        }
      }
    })
    .catch(() => {
      if (typeof window.showToast === 'function') {
      window.showToast(translations.error, translations.hierarchy_change_failed, 'error', 5000);
    }
  });
};

// Initialize drag and drop when document is ready
document.addEventListener('DOMContentLoaded', function(){
  // Initialize table drag and drop using shared function
  if(typeof AdminDragDrop !== 'undefined') {
    AdminDragDrop.initTableDragDrop();
    AdminDragDrop.autoDismissDragHint('drag-hint', 8000);
  }
});
</script>
<!--footer_user_payment_details_modal-->
<div class="modal fade" id="payment-detail_modal" tabindex="-1" aria-labelledby="payment-detail_modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="payment-detail_modalLabel">
          <i class="fa fa-credit-card me-2"></i><?= __('admin.footer_user_payment_details') ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <h6 class="text-primary mb-3">
              <i class="fa fa-university me-2"></i><?= __('admin.footer_bank_details') ?>
            </h6>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th class="fw-semibold"><?= __('admin.footer_bank_name') ?></th>
                    <th class="fw-semibold"><?= __('admin.footer_account_number') ?></th>
                    <th class="fw-semibold"><?= __('admin.footer_account_name') ?></th>
                    <th class="fw-semibold"><?= __('admin.footer_country') ?></th>
                    <th class="fw-semibold"><?= __('admin.footer_country_code') ?></th>
                  </tr>
                </thead>
                <tbody class="bank-details"></tbody>
              </table>
            </div>
          </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row">
          <div class="col-12">
            <h6 class="text-primary mb-3">
              <i class="fa fa-paypal me-2"></i><?= __('admin.footer_paypal_emails') ?>
            </h6>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th class="fw-semibold">#</th>
                    <th class="fw-semibold"><?= __('admin.footer_email') ?></th>
                  </tr>
                </thead>
                <tbody class="paypal-details"></tbody>
              </table>
            </div>
          </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row">
          <div class="col-12">
            <h6 class="text-primary mb-3">
              <i class="fa fa-user me-2"></i><?= __('admin.footer_user_details') ?>
            </h6>
            <div class="table-responsive">
              <table class="table table-hover">
                <tbody class="user-details"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fa fa-times me-1"></i><?= __('admin.footer_close') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!--footer_user_payment_details_modal-->


<!--footer_user_payment_details_script-->
<script type="text/javascript">
  $(document).delegate("[payment_detail]",'click',function(e){
    e.preventDefault();
    e.stopPropagation();
    $this = $(this);
    var user_id = $this.attr("payment_detail");
    
    // Store original HTML content for icon-only buttons
    if (!$this.data('original-html')) {
        $this.data('original-html', $this.html());
    }
    
    $.ajax({
      url:'<?= base_url("admincontrol/getpaymentdetail") ?>/' + user_id,
      type:'POST',
      dataType:'json',
      beforeSend:function(){ 
        $this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>'); 
      },
      complete:function(){ 
        $this.prop('disabled', false).html($this.data('original-html')); 
      },
      success:function(json){

        $('#payment-detail_modal').modal("show");
        var html = '';
        $.each(json['paymentlist'], function(i,j){
          html += '<tr>';
          html += '<th>'+ j['payment_bank_name'] +'</th>';
          html += '<th>'+ j['payment_account_number'] +'</th>';
          html += '<th>'+ j['payment_account_name'] +'</th>';
          html += '<th>'+ j['payment_ifsc_code'] +'</th>';
          html += '</tr>';
        })  

        $('#payment-detail_modal .bank-details').html(html);

        var html = '';

        $.each(json['paypalaccounts'], function(i,j){

          html += '<tr>';

          html += '<th>'+ (i+1) +'</th>';

          html += '<th>'+ j['paypal_email'] +'</th>';

          html += '</tr>';

        })  

        $('#payment-detail_modal .paypal-details').html(html);

        var html = '';

        html += '<tr>';

        html += '<th><?= __('admin.footer_firstname') ?></th>';

        html += '<td>'+ json.user.firstname +'</td>';

        html += '</tr>';

        html += '<tr>';

        html += '<th><?= __('admin.footer_lastname') ?></th>';

        html += '<td>'+ json.user.lastname +'</td>';

        html += '</tr>';

        html += '<tr>';

        html += '<th><?= __('admin.footer_username') ?></th>';

        html += '<td>'+ json.user.username +'</td>';

        html += '</tr>';

        html += '<tr>';

        html += '<th><?= __('admin.footer_email') ?></th>';

        html += '<td>'+ json.user.email +'</td>';

        html += '</tr>';

        html += '<tr>';

        html += '<th><?= __('admin.footer_phone') ?></th>';

        html += '<td>'+ json.user.phone +'</td>';

        html += '</tr>';

        html += '<tr>';

        html += '<th><?= __('admin.footer_address') ?></th>';

        html += '<td>'+ json.user.address +'</td>';

        html += '</tr>';

        html += '<tr>';

        html += '<th><?= __('admin.footer_state') ?></th>';

        html += '<td>'+ json.user.state +'</td>';

        html += '</tr>';

        html += '<tr>';

        html += '<th><?= __('admin.footer_country') ?></th>';

        html += '<td>'+ json.user.country +'</td>';

        html += '</tr>';

        $('#payment-detail_modal .user-details').html(html);
      },  
    })
  })
</script>
<!--footer_user_payment_details_script-->


<script type="text/javascript" async="">
	$(document).on('click', '.btn-login-aff', function(){
		$.post('<?= base_url('admincontrol/doLoginAff') ?>', {id:$(this).data('id')}, function(result) {
			$res=$.trim(result);
			if($res == 'success') {
				window.open('<?= base_url('usercontrol/dashboard') ?>', '_blank');
			}
		})
	})

	$(document).on('click', '.user-approvals-filer button', function(){
	    $('.user-approvals-filer button.btn-primary').removeClass('btn-primary').addClass('btn-outline-primary');
	    $('.user-approvals-filer button.btn-success').removeClass('btn-success').addClass('btn-outline-success');
	    $('.user-approvals-filer button.btn-warning').removeClass('btn-warning').addClass('btn-outline-warning');
	    $('.user-approvals-filer button.btn-danger').removeClass('btn-danger').addClass('btn-outline-danger');
	    
	    let apr = $(this).data('apr');
	    if(apr === 'all') {
	        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
	    } else if(apr === 'approved') {
	        $(this).removeClass('btn-outline-success').addClass('btn-success');
	    } else if(apr === 'pending') {
	        $(this).removeClass('btn-outline-warning').addClass('btn-warning');
	    } else if(apr === 'declined') {
	        $(this).removeClass('btn-outline-danger').addClass('btn-danger');
	    }
	    getPage(1);
	});

	$(document).on('click', 'button[data-approval-change]', function(){
	
		// HTML entities for spinner (ensure you have FontAwesome or similar included if you use an icon)
		var processing = '<span class="text-success"><i class="fa fa-spinner fa-spin"></i> <?= __('admin.processing') ?></span>';

		// Update the parent HTML of the clicked button to show the spinner and processing text
		$(this).closest('.btn-group').html(processing);

		if(xhr && xhr.readyState != 4) xhr.abort();
		data = {};
		let status = ($(this).data('approval-change') == 1) ? 'approve_users' : 'decline_users'
		data['action'] = "process_approval";
		data[status] = [$(this).data('user-id')];
		xhr = $.ajax({
			type:'POST',
			dataType:'json',
			data: data,
			beforeSend:function(){
				$(".dimmer").addClass("active");
			},
			complete:function(){
				$(".dimmer").removeClass("active");
			},
			success:function(response){
				if(response.approvals_status.status) {
					$('.approvals-status-alert').removeClass('alert-danger');
					$('.approvals-status-alert').addClass('alert-success');
					$('.approvals-status-alert').text(response.approvals_status.message);
				} else {
					$('.approvals-status-alert').addClass('alert-danger');
					$('.approvals-status-alert').removeClass('alert-success');
					$('.approvals-status-alert').text(response.approvals_status.message);
				}
				$('.approvals-status-alert').show();
				setTimeout(function(){ $('.approvals-status-alert').hide(); }, 3000);
				reloadApprovalFilter(response.approvals_count)
				getPage(1, response.approvals_count);
			}
		});
	});

	var selected = {};

	var all_ids = [];

	$('.clear-selection').on('click',function(){

		selected = {};

		$(".selection-message").addClass('d-none');

		$('.selectall-wallet-checkbox').prop("checked",0);

		changeViews();

	});

	function changeViews() {

		$(".wallet-checkbox").prop("checked",  false);

		if(Object.keys(selected).length == 0){

			$(".selection-message").addClass('d-none');

		} else {

			$(".selection-message").removeClass('d-none');

			$(".selected-count").text(Object.keys(selected).length);

		}

		$(".select-all-users").show();

		if(Object.keys(selected).length == all_ids.length){

			$(".select-all-users").hide();

		}

		$.each(selected, function(i,j){

			$('.wallet-checkbox[value="'+ j +'"]').prop("checked",true);

		})

		if(Object.keys(selected).length == 0){

			$(".delete-multiple").prop('disabled', true).removeClass('btn-outline-danger').addClass('btn-outline-secondary');
			$(".multi-approve-decline").addClass('d-none');

		} else {

			$(".delete-multiple").prop('disabled', false).removeClass('btn-outline-secondary').addClass('btn-outline-danger');
			$(".multi-approve-decline").removeClass('d-none');

		}

	}

	$('.selectall-wallet-checkbox').on('change',function(){

		$(".wallet-checkbox").prop("checked",  $(this).prop("checked"));

		$('.wallet-checkbox').each(function(){

			var val = $(this).val();

			if($(this).prop("checked")){ selected[val]=val; } 

			else { delete selected[val]; }

		})

		changeViews();
	})

	jQuery('.select2').select2({
		placeholder : "<?= __('admin.filter_by_groups') ?>"
	});

	$(".user-table").delegate(".wallet-checkbox","change",function(){

		var status = $(this).prop("checked");

		if(!status) delete selected[$(this).val()]

			else selected[$(this).val()] = $(this).val();

		changeViews();

	})

	$(".select-all-users").on('click',function(){

		$this = $(this);

		$.ajax({
			type:'POST',
			dataType:'json',
			data:{action:'get_all_ids'},
			beforeSend:function(){ 
				$this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>');
			},
			complete:function(){ 
				$this.prop('disabled', false).html('<?= __('admin.select_all') ?> <span class="total-user"></span> <?= __('admin.users') ?>');
			},
			success:function(json){
				$.each(json['ids'],function(i,id){
					selected[id]= id;
				})
				$(".selected-count").text(Object.keys(selected).length);
				all_ids = json['ids'];
				changeViews();
			},
		});

	})

	$(".user-table").delegate(".checkbox-label","click",function(e){

		e.stopPropagation();

	});

	$(document).delegate("[edit-plan-user]","click",function(e){

		e.stopPropagation();

		var user_id = $(this).attr('edit-plan-user');
		
		var is_vendor = $(this).attr('edit-plan-user-type');

		$("#membershipuser-image").remove();


		$this = $(this);

		$.ajax({

			url:'<?= base_url("membership/user_plan_modal") ?>',

			dataType:'html',

			data:{user_id:user_id, is_vendor:is_vendor},

			beforeSend:function(){
				if (!$this.data('original-html')) { $this.data('original-html', $this.html()); }
				$this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>');
				$("#edit-plan-loading").removeClass('d-none').css('display','flex');
				$("#edit-plan-content").addClass('d-none').css('display','none').empty();
				$("#modal-edit-plan").modal("show");
			},
			complete:function(){
				$this.prop('disabled', false).html($this.data('original-html'));
			},

			success:function(html){
				$("#edit-plan-loading").addClass('d-none').css('display','none');
				$("#edit-plan-content").removeClass('d-none').css('display','block').html(html);
			},
		})
	});



	$(".delete-multiple").on('click',function(e){

		$this = $(this);

		var ids = Object.keys(selected).join(",");

		e.preventDefault();

		e.stopPropagation();

		if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

		if (!$this.data('original-html')) {
			$this.data('original-html', $this.html());
		}

		$.ajax({

			url: '<?php echo base_url("admincontrol/deleteAllusersMultiple") ?>',

			type:'POST',

			dataType:'json',

			data:{ids:ids},

			beforeSend:function(){ 
				$this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>');
			},
			complete:function(){ 
				$this.prop('disabled', false).html($this.data('original-html'));
			},

			success:function(json){

				if(json['status'] === 'error') {
					showPrintMessage(json['message'], 'error');
					return;
				}

				$("#modal-delete #message").html(json['message']);

				$("#modal-delete .confirm-delete").attr("data-id",ids);
				
				$(".confirm-delete").prop('disabled', false).html('<i class="fa fa-trash me-1"></i><?= __('admin.delete') ?>');

				$("#modal-delete").modal("show");

			},

		})

	})

	var xhr;

	function getPage(page, existingCounts = null) {

		$this = $(this);

		if(xhr && xhr.readyState != 4) xhr.abort();

		let reg_approval_filter = $('.user-approvals-filer button.btn-primary, .user-approvals-filer button.btn-success, .user-approvals-filer button.btn-warning, .user-approvals-filer button.btn-danger').data('apr');

		if((reg_approval_filter == 'pending' || reg_approval_filter == 'approved' || reg_approval_filter == 'declined') && existingCounts != null && existingCounts[reg_approval_filter] == 0) {
			reg_approval_filter = 'all';
		}

 
		let data = $("#search-form").serialize();

		xhr = $.ajax({

			type:'POST',

			dataType:'json',

			data: data + "&apr="+reg_approval_filter+"&page="+page,

			beforeSend:function(){

				$(".dimmer").addClass("active");

			},

			complete:function(){

				$(".dimmer").removeClass("active");

			},

			success:function(json){

				if(typeof json['table'] !== 'undefined'){

					$('.selectall-wallet-checkbox').prop("checked",false);

					$(".user-table tbody").html(json['table']);

					reloadApprovalFilter(json['approvals_count'] || {});
					changeViews();
					showDndTipOnce();

				} else {
					$(".user-table tbody").empty();
					changeViews();
				}

				if(typeof json['pagination'] !== 'undefined'){
					$(".user-table tfoot .pagination-container").html(json['pagination']);
				} else {
					$(".user-table tfoot .pagination-container").empty();
				}
				if(typeof json['pagination_summary'] !== 'undefined'){
					$(".user-table tfoot .pagination-summary").html(json['pagination_summary']);
				} else {
					$(".user-table tfoot .pagination-summary").empty();
				}

			},

		})

	}

	function reloadApprovalFilter(data) {
		if($('.user-approvals-filer').length == 0) {
			$('.dimmer .table-responsive').append('<div class="table-header-menus mb-3"><div class="d-flex flex-wrap align-items-center justify-content-between"><div class="user-approvals-filer"><div class="btn-group" role="group"></div></div></div></div>')
		}
		
		let filterGroup = $('.user-approvals-filer .btn-group');
		
		if(data['total'] > 0) {
		    if($('.user-approvals-filer button[data-apr="all"]').length <= 0) {
		        filterGroup.append('<button type="button" class="btn btn-outline-primary" data-apr="all"><i class="fa fa-users me-1"></i><?= __('admin.show_all_users') ?> <span class="badge bg-light text-dark ms-1">'+data['total']+'</span></button>');
		    } else {
		        $('.user-approvals-filer button[data-apr="all"] .badge').text(data['total']);
		    }
		} else {
			$('.user-approvals-filer button[data-apr="all"]').remove();
		}
		if(data['approved'] > 0) { 
		    if($('.user-approvals-filer button[data-apr="approved"]').length <= 0) {
		        filterGroup.append('<button type="button" class="btn btn-outline-success" data-apr="approved"><i class="fa fa-check me-1"></i><?= __('admin.show_approved_users') ?> <span class="badge bg-light text-dark ms-1">'+data['approved']+'</span></button>');
		    } else {
		        $('.user-approvals-filer button[data-apr="approved"] .badge').text(data['approved']);
		    }
		} else {
			$('.user-approvals-filer button[data-apr="approved"]').remove();
		}
		if(data['pending'] > 0) {
		    if($('.user-approvals-filer button[data-apr="pending"]').length <= 0) {
		        filterGroup.append('<button type="button" class="btn btn-outline-warning" data-apr="pending"><i class="fa fa-clock-o me-1"></i><?= __('admin.show_pending_users') ?> <span class="badge bg-light text-dark ms-1">'+data['pending']+'</span></button>');
		    } else {
		        $('.user-approvals-filer button[data-apr="pending"] .badge').text(data['pending']);
		    } 
		} else {
			$('.user-approvals-filer button[data-apr="pending"]').remove();
		}
		if(data['declined'] > 0) {
		    if($('.user-approvals-filer button[data-apr="declined"]').length <= 0) {
		        filterGroup.append('<button type="button" class="btn btn-outline-danger" data-apr="declined"><i class="fa fa-times me-1"></i><?= __('admin.show_declined_users') ?> <span class="badge bg-light text-dark ms-1">'+data['declined']+'</span></button>');
		    } else {
		        $('.user-approvals-filer button[data-apr="declined"] .badge').text(data['declined']);
		    }
		} else {
		    $('.user-approvals-filer button[data-apr="declined"]').remove();
		}
		if(data['pending'] == 0 && data['declined'] == 0) { 
			$('.user-approvals-filer button[data-apr="approved"]').remove();
			$('.user-approvals-filer button[data-apr="pending"]').remove();
			$('.user-approvals-filer button[data-apr="declined"]').remove();
		} 

		// Set active button
		if($('.user-approvals-filer button.btn-primary, .user-approvals-filer button.btn-success, .user-approvals-filer button.btn-warning, .user-approvals-filer button.btn-danger').length == 0) {
		    $('.user-approvals-filer button[data-apr="all"]').removeClass('btn-outline-primary').addClass('btn-primary');
		}
	}

	(function(){
		var debounceTimer;
		$("#search-form input").off('keyup').on('input', function(){
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function(){ getPage(1); }, 400);
		});
	})();

	$("#search-form select").on('change',function(){
		getPage(1);
	})

	$(".user-table tfoot .pagination").delegate("a","click", function(e){
		e.preventDefault();
		getPage($(this).attr("data-ci-pagination-page"));
	})

	$(".dimmer").removeClass("active");
	
	$(document).on('click', 'button, a', function() {
		if (!$(this).data('original-text')) {
			$(this).data('original-text', $(this).html());
		}
	});

	function initTooltips(){
		if (window.bootstrap) {
			var els = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
			els.forEach(function (el) {
				if (!bootstrap.Tooltip.getInstance(el)) new bootstrap.Tooltip(el);
			});
		}
	}
	initTooltips();
	$(document).ajaxComplete(function(){ initTooltips(); });

	// Auto-dismiss drag hint after 8 seconds
	setTimeout(function(){
		const dragHint = document.getElementById('drag-hint');
		if(dragHint && dragHint.classList.contains('show')){
			const bsAlert = bootstrap.Alert.getOrCreateInstance(dragHint);
			bsAlert.close();
		}
	}, 8000);
	
	$(document).ready(function(){
		const startTime = PerformanceMonitor.start('userslist-performance');
		
		setTimeout(() => {
			PerformanceMonitor.end(startTime, 'userslist-performance');
		}, 100);
	});

	getPage(1);

	$(".user-table").delegate(".btn-remove",'click',function(e){
		if(!confirm('<?= __('admin.are_you_sure') ?>')) e.preventDefault();
		return true;
	});

	$(".user-table").delegate(".show-tree",'click',function(e){
		e.preventDefault();
		e.stopPropagation();

		$this = $(this);
		
		// Store original HTML content for icon-only buttons
		if (!$this.data('original-html')) {
			$this.data('original-html', $this.html());
		}
		
		$.ajax({
			url: '<?php echo base_url("admincontrol/showTree") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ 
				$this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>');
				// Show modal with loading state
				$("#tree-loading").removeClass('d-none').css('display','flex');
				$("#tree-content").addClass('d-none').css('display','none').empty();
				$("#modal-tree").modal("show");
			},
			complete:function(){ 
				$this.prop('disabled', false).html($this.data('original-html'));
			},
			success:function(json){
				// Debug log
				console.log('Tree data received:', json);
				
				// Hide loading and show content
				$("#tree-loading").addClass('d-none').css('display','none');
				$("#tree-content").removeClass('d-none').css('display','block').html(json['html']);
			},
			error:function(xhr, status, error){
				// Handle error state
				$("#tree-loading").addClass('d-none').css('display','none');
				$("#tree-content").removeClass('d-none').css('display','block').html('<div class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle me-2"></i><?= __('admin.error_occurred') ?></div>');
			}
		});
	});

	// Reset modal state when hidden
	$('#modal-tree').on('hidden.bs.modal', function () {
		$("#tree-loading").removeClass('d-none');
		$("#tree-content").addClass('d-none').empty();
	});

	$('#modal-edit-plan').on('hidden.bs.modal', function () {
		$("#edit-plan-loading").removeClass('d-none').css('display','flex');
		$("#edit-plan-content").addClass('d-none').css('display','none').empty();
	});

	$('#modal-delete').on('hidden.bs.modal', function () {
		$("#delete_transaction").prop('checked', false);
		$(".confirm-delete").prop('disabled', false).html('<i class="fa fa-trash me-1"></i><?= __('admin.delete') ?>');
		$(".confirm-delete").data('original-html', '<i class="fa fa-trash me-1"></i><?= __('admin.delete') ?>');
	});

	$(".user-table").delegate(".btn-delete2",'click',function(e){
		e.preventDefault();
		e.stopPropagation();
		$this = $(this);
		
		// Store original HTML content for icon-only buttons
		if (!$this.data('original-html')) {
			$this.data('original-html', $this.html());
		}
		
		/*delete user popup*/
		Swal.fire({
		   icon: 'warning',
		   title: '<?= __('admin.delete_user') ?>',
	       text: '<?= __('admin.are_you_sure') ?>',
	       showCancelButton: true,
	       cancelButtonText: 'cancel'
		}).then(function(dismiss){
	        if(dismiss.value==true)
	        {
				$.ajax({
					url: '<?php echo base_url("admincontrol/deleteAllusers") ?>',
					type:'POST',
					dataType:'json',
					data:{id:$this.attr("data-id")},
					beforeSend:function(){ $this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>'); },
					complete:function(){ $this.prop('disabled', false).html($this.data('original-html')); },
					success:function(json){
						$("#modal-delete #message").html(json['message']);
						$("#modal-delete .confirm-delete").attr("data-id",$this.attr("data-id"));
						$("#modal-delete").modal("show");
					},
				});
	        }
	        else
	        {
	          return false;
	        }
	    });
	    /*delete user popup*/
	});

	$(document).delegate(".confirm-delete",'click',function(e){
		e.preventDefault();
		e.stopPropagation();
		$this = $(this);
		
		if (!$this.data('original-html')) {
			$this.data('original-html', $this.html());
		}
		
		$.ajax({
			url: '<?php echo base_url("admincontrol/deleteUsersConfirm") ?>',
			type:'POST',
			dataType:'json',
			data:{
				id:$this.attr("data-id"),
				delete_transaction:$("#delete_transaction").prop("checked")
			},
			beforeSend:function(){ $this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>'); },
			complete:function(){ $this.prop('disabled', false).html($this.data('original-html')); },
		success:function(json){

			if(json['status'] === 'error') {
				$("#modal-delete").modal("hide");
			  showPrintMessage(json['message'], 'error');
			  return;
			}

			$("#modal-delete").modal("hide");
			
			if(json['status'] === 'success' && json['message']) {
				showPrintMessage(json['message'], 'success');
			}

			selected = {};
			changeViews();

			var currentPageLink = document.querySelector('.user-table tfoot .pagination .active a');
			var page = currentPageLink ? currentPageLink.getAttribute('data-ci-pagination-page') : 1;
			getPage(page);
		},
		})
	})

	$(".export-excel").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url('admincontrol/get_user_data') ?>',
			type:'POST',
			dataType:'json',
			data: {
				action:'export',
			},

			beforeSend:function(){
				$this.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>');
			},

			complete:function(){
				$this.prop('disabled', false).html($this.data('original-text') || $this.text().replace('<?= __('admin.loading') ?>', '').trim());
			},

			success:function(json){

      if(json['status'] === 'error') {
          showPrintMessage(json['message'], 'error');
          return;
      }

				console.log(json);
				if (json['download']) {

					window.location.href = json['download'];
				}
			},
		})
	})

	$(".btn_import_data").on('click',function(){

		$this = $("#import_form");

		var formData = new FormData($this[0]);

		formData.append("action",'import');

		formData = formDataFilter(formData);

		$(".btn_import_data").prop("disabled",true);

		$.ajax({
			url:'<?= base_url('admincontrol/get_user_data') ?>',
			type:'POST',
			dataType:'json',
			cache:false,
			contentType: false,
			processData: false,
			data:formData,
			xhr: function (){
			var jqXHR = null;

			if ( window.ActiveXObject ){
				jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
			}else {
				jqXHR = new window.XMLHttpRequest();

			}
				jqXHR.upload.addEventListener( "progress", function ( evt ){
					if ( evt.lengthComputable ){
						var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
						$('#import-status').text('<?= __('admin.uploading') ?>'+" - " + percentComplete + "%").show();
					}

				}, false );

				jqXHR.addEventListener( "progress", function ( evt ){

					if ( evt.lengthComputable ){

						var percentComplete = Math.round( (evt.loaded * 100) / evt.total );

						$('#import-status').html('<?= __('admin.import_users') ?>'+'...');

					}

				}, false );

				return jqXHR;

			},

			error:function(){

				$(".btn_import_data").prop("disabled",false);

			},

			success:function(result){

				$(".btn_import_data").prop("disabled",false);

		    if(result['status'] === 'error') {
		        showPrintMessage(result['message'], 'error');
		        return;
		    }

				if(result['location']){ window.location = result['location']; }

				$(".hidden-close").removeClass("d-none");

				$(".btn-close").remove();

				$("#import-log").html('');

				$("#import-status").html('');

				if(result['errors']){

					showPrintMessage(result['errors'], 'error');

					$("#import-log").html(result['errors']);

				}
			},
		})

	});	

	$('.accordian-body').on('show.bs.collapse', function () {

		$(this).closest("table")

		.find(".collapse.in")

		.not(this)

		.collapse('toggle')

	})

	$(".approved-decline-action").on('click',function(e){
		var ids = Object.keys(selected).join(",");
		var data = {};
		let status = ($(this).data('action-value') == 1) ? 'approve_users' : 'decline_users'

		data['ids'] = ids;
		data[status] = ids;

		$.ajax({
			url: '<?php echo base_url("admincontrol/multiApproveDecline") ?>',
			type:'POST',
			dataType:'json',
			data:data,
			beforeSend:function(){
				$(".dimmer").addClass("active");
			},
			complete:function(){
				$(".dimmer").removeClass("active");
			},
			success:function(response){
				if (response.approvals_status.status != 'NULL') {
					if(response.approvals_status.status) {
						$('.approvals-status-alert').removeClass('alert-danger');
						$('.approvals-status-alert').addClass('alert-success');
						$('.approvals-status-alert').text(response.approvals_status.message);
					} else {
						$('.approvals-status-alert').addClass('alert-danger');
						$('.approvals-status-alert').removeClass('alert-success');
						$('.approvals-status-alert').text(response.approvals_status.message);
					}

					$('.approvals-status-alert').show();
					setTimeout(function(){ $('.approvals-status-alert').hide(); }, 3000);
				}
				
				reloadApprovalFilter(response.approvals_count)
				getPage(1, response.approvals_count);
			}
		})
	});

	function showDndTipOnce(){
		try{
			if(localStorage.getItem('userslist_dnd_tip_shown')==='1') return;
			localStorage.setItem('userslist_dnd_tip_shown','1');
			if(typeof window.showToast === 'function'){
				window.showToast('Tip', '<?= __('admin.drag_user_rows_to_change_hierarchy') ?>', 'success', 6000);
			}
			var handle = document.querySelector('.user-table tbody .drag-handle');
			if(handle && window.bootstrap){
				var tip = bootstrap.Tooltip.getOrCreateInstance(handle);
				tip.show();
				setTimeout(function(){ try{ tip.hide(); }catch(e){} }, 3000);
			}
		}catch(e){}
	}

	$('#btn-recalc-health-scores').on('click', function(){
		var $btn = $(this), $out = $('#health-score-recalc-status');
		$out.text(<?= json_encode(__('admin.health_scores_recalculating')) ?>).removeClass('text-success text-danger').addClass('text-muted');
		$btn.prop('disabled', true);
		$.ajax({
			url: '<?= base_url('admincontrol/affiliate_health_scores_recalculate') ?>',
			type: 'POST',
			dataType: 'json',
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			success: function(data){
				$out.text(data.message || (data.success ? 'Done.' : 'Error.'));
				$out.removeClass('text-muted').addClass(data.success ? 'text-success' : 'text-danger');
				if (data.success && typeof getPage === 'function') {
					getPage(1);
				}
			},
			error: function(){
				$out.text(<?= json_encode(__('admin.health_scores_request_failed')) ?>).removeClass('text-muted').addClass('text-danger');
			},
			complete: function(){ $btn.prop('disabled', false); }
		});
	});

</script>

<?= generate_universal_pagination_script() ?>

<?= performance_indicator_html('userslist-performance') ?>

