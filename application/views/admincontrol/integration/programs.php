<div class="container-fluid px-4 pb-4">
  <?php $this->load->view('admincontrol/integration/_campaign_nav'); ?>
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><?= __('admin.integration_programs') ?></h4>
        <a href="<?= base_url('integration/programs_form') ?>" class="btn btn-success btn-sm">
          <i class="bi bi-plus-lg me-1"></i><?= __('admin.add_new') ?>
        </a>
      </div>

      <!-- Filter Section -->
      <div class="card shadow-sm intg-filter-card mb-3">
        <div class="card-header intg-filter-header" data-bs-toggle="collapse" data-bs-target="#programsFilterBody">
          <h6 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-funnel"></i> <?= __('admin.search_filters') ?>
            <i class="bi bi-chevron-down ms-auto small"></i>
          </h6>
        </div>
        <div class="collapse show" id="programsFilterBody">
          <div class="card-body intg-filter-body">
            <form method="GET" id="filterForm">
              <div class="row g-2 align-items-end">
                <div class="col-xl col-lg-3 col-md-6">
                  <label class="form-label small text-muted mb-1"><?= __('admin.name') ?></label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="name" id="progname" class="form-control" 
                           placeholder="<?= __('admin.enter_program_name') ?>"
                           autocomplete="off" value="<?= isset($_GET['name']) ? $_GET['name'] : '' ?>">
                  </div>
                </div>
                <div class="col-xl col-lg-3 col-md-6">
                  <label class="form-label small text-muted mb-1"><?= __('admin.vendor') ?></label>
                  <select class="form-select form-select-sm" name="is_admin">
                    <option value=""><?= __('admin.select_by_admin_or_vendor') ?></option>
                    <option value="0" <?= (isset($_GET['is_admin']) && $_GET['is_admin'] == '0') ? 'selected' : '' ?>><?= __('admin.admin') ?></option>
                    <option value="1" <?= (isset($_GET['is_admin']) && $_GET['is_admin'] == '1') ? 'selected' : '' ?>><?= __('admin.vendor') ?></option>
                  </select>
                </div>
                <div class="col-xl col-lg-3 col-md-6">
                  <label class="form-label small text-muted mb-1"><?= __('admin.status') ?></label>
                  <select class="form-select form-select-sm" name="status">
                    <option value=""><?= __('admin.select_status') ?></option>
                    <option value="0" <?= (isset($_GET['status']) && $_GET['status'] == '0') ? 'selected' : '' ?>><?= __('admin.in_review') ?></option>
                    <option value="1" <?= (isset($_GET['status']) && $_GET['status'] == '1') ? 'selected' : '' ?>><?= __('admin.approved') ?></option>
                    <option value="2" <?= (isset($_GET['status']) && $_GET['status'] == '2') ? 'selected' : '' ?>><?= __('admin.denied') ?></option>
                    <option value="3" <?= (isset($_GET['status']) && $_GET['status'] == '3') ? 'selected' : '' ?>><?= __('admin.ask_to_edit') ?></option>
                  </select>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2 mt-2">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="bi bi-search me-1"></i><?= __('admin.filter') ?>
                </button>
                <a href="<?= base_url('integration/programs') ?>" class="btn btn-outline-secondary btn-sm" id="clear-program-filters">
                  <i class="bi bi-x-lg me-1"></i><?= __('admin.clear_search') ?>
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Data Table -->
      <div class="card shadow-sm intg-table-card">
        <div class="card-header bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0 fw-bold">
              <i class="bi bi-puzzle me-2 text-primary"></i><?= __('admin.integration_programs') ?>
            </h6>
            <span class="badge bg-light text-muted border fw-normal"><?= isset($programs) ? count($programs) : 0 ?> <?= __('admin.programs') ?></span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <?php if (empty($programs)) { ?>
              <div class="text-center py-5">
                <i class="bi bi-puzzle display-4 text-muted d-block mb-3"></i>
                <h4 class="text-muted"><?= __('admin.no_data_found') ?></h4>
                <p class="text-muted"><?= __('admin.no_programs_found_message') ?></p>
                <a href="<?= base_url('integration/programs_form') ?>" class="btn btn-primary btn-sm rounded-pill">
                  <i class="bi bi-plus-lg me-1"></i><?= __('admin.add_first_program') ?>
                </a>
              </div>
            <?php } else { ?>
              <table class="table table-hover align-middle mb-0 intg-table">
                <thead class="table-light">
                  <tr>
                    <th width="5%"><?= __('admin.id') ?></th>
                    <th width="20%"><?= __('admin.name') ?></th>
                    <th width="15%"><?= __('admin.vendor') ?></th>
                    <th width="15%"><?= __('admin.sale_commission') ?></th>
                    <th width="15%"><?= __('admin.click_commission') ?></th>
                    <th width="10%"><?= __('admin.sale_status') ?></th>
                    <th width="10%"><?= __('admin.click_status') ?></th>
                    <th width="10%"><?= __('admin.status') ?></th>
                    <th width="10%" class="text-center"><?= __('admin.actions') ?></th>
                  </tr>
                </thead>
                <tbody id="data_list">
                  <?php foreach ($programs as $key => $program) { ?>
                    <tr id="program-row-<?= $program['id'] ?>">
                      <td><span class="badge bg-secondary"><?= $program['id'] ?></span></td>
                      <td>
                        <div class="fw-bold"><?= htmlspecialchars($program['name']) ?></div>
                      </td>
                      <td>
                        <?php if ($program['username']) { ?>
                          <span class="badge bg-info"><?= htmlspecialchars($program['username']) ?></span>
                        <?php } else { ?>
                          <span class="badge bg-primary"><?= __('admin.admin') ?></span>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ($program['vendor_id']) { ?>
                          <div class="small">
                            <div class="text-muted"><?= __('admin.admin') ?>:</div>
                            <div class="fw-bold">
                              <?php if ($program['admin_sale_status']) {
                                if ($program['admin_commission_type'] == 'percentage') {
                                  echo $program['admin_commission_sale'] . '%';
                                } else if ($program['admin_commission_type'] == 'fixed') {
                                  echo c_format($program['admin_commission_sale']);
                                } else {
                                  echo __('admin.not_set');
                                }
                              } else {
                                echo __('admin.not_set');
                              } ?>
                            </div>
                            <div class="text-muted mt-1"><?= __('admin.affiliate') ?>:</div>
                            <div class="fw-bold">
                              <?php if ($program['sale_status']) {
                                if ($program['commission_type'] == 'percentage') {
                                  echo $program['commission_sale'] . '%';
                                } else if ($program['commission_type'] == 'fixed') {
                                  echo c_format($program['commission_sale']);
                                } else {
                                  echo __('admin.not_set');
                                }
                              } else {
                                echo __('admin.not_set');
                              } ?>
                            </div>
                          </div>
                        <?php } else { ?>
                          <div class="fw-bold">
                            <?php if ($program['sale_status']) {
                              if ($program['commission_type'] == 'percentage') {
                                echo $program['commission_sale'] . '%';
                              } else if ($program['commission_type'] == 'fixed') {
                                echo c_format($program['commission_sale']);
                              } else {
                                echo __('admin.not_set');
                              }
                            } else {
                              echo __('admin.not_set');
                            } ?>
                          </div>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ($program['vendor_id']) { ?>
                          <div class="small">
                            <div class="text-muted"><?= __('admin.admin') ?>:</div>
                            <div class="fw-bold">
                              <?php if ($program['admin_click_status']) {
                                if ($program["admin_commission_click_commission"] && $program['admin_commission_number_of_click']) {
                                  echo c_format($program["admin_commission_click_commission"]) . " " . __('admin.per') . " " . $program['admin_commission_number_of_click'] . " " . __('admin.clicks');
                                } else {
                                  echo __('admin.not_set');
                                }
                              } else {
                                echo __('admin.not_set');
                              } ?>
                            </div>
                            <div class="text-muted mt-1"><?= __('admin.affiliate') ?>:</div>
                            <div class="fw-bold">
                              <?php if ($program['click_status']) {
                                echo c_format($program["commission_click_commission"]) . " " . __('admin.per') . " " . $program['commission_number_of_click'] . " " . __('admin.clicks');
                              } else {
                                echo __('admin.not_set');
                              } ?>
                            </div>
                          </div>
                        <?php } else { ?>
                          <div class="fw-bold">
                            <?php if ($program['click_status']) {
                              echo c_format($program["commission_click_commission"]) . " " . __('admin.per') . " " . $program['commission_number_of_click'] . " " . __('admin.clicks');
                            } else {
                              echo __('admin.not_set');
                            } ?>
                          </div>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ($program['vendor_id']) { ?>
                          <div class="small">
                            <div class="text-muted"><?= __('admin.admin') ?>:</div>
                            <span class="badge <?= $program['admin_sale_status'] ? 'bg-success' : 'bg-danger' ?>">
                              <?= $program['admin_sale_status'] ? __('admin.enable') : __('admin.disable') ?>
                            </span>
                            <div class="text-muted mt-1"><?= __('admin.affiliate') ?>:</div>
                            <span class="badge <?= $program['sale_status'] ? 'bg-success' : 'bg-danger' ?>">
                              <?= $program['sale_status'] ? __('admin.enable') : __('admin.disable') ?>
                            </span>
                          </div>
                        <?php } else { ?>
                          <span class="badge <?= $program['sale_status'] ? 'bg-success' : 'bg-danger' ?>">
                            <?= $program['sale_status'] ? __('admin.enable') : __('admin.disable') ?>
                          </span>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ($program['vendor_id']) { ?>
                          <div class="small">
                            <div class="text-muted"><?= __('admin.admin') ?>:</div>
                            <span class="badge <?= $program['admin_click_status'] ? 'bg-success' : 'bg-danger' ?>">
                              <?= $program['admin_click_status'] ? __('admin.enable') : __('admin.disable') ?>
                            </span>
                            <div class="text-muted mt-1"><?= __('admin.affiliate') ?>:</div>
                            <span class="badge <?= $program['click_status'] ? 'bg-success' : 'bg-danger' ?>">
                              <?= $program['click_status'] ? __('admin.enable') : __('admin.disable') ?>
                            </span>
                          </div>
                        <?php } else { ?>
                          <span class="badge <?= $program['click_status'] ? 'bg-success' : 'bg-danger' ?>">
                            <?= $program['click_status'] ? __('admin.enable') : __('admin.disable') ?>
                          </span>
                        <?php } ?>
                      </td>
                      <td><?= program_status($program['status']) ?></td>
                      <td class="text-center">
                        <div class="btn-group" role="group">
                          <a class="btn btn-outline-primary btn-sm" href="<?= base_url('integration/programs_form/' . $program['id']) ?>" title="<?= __('admin.edit') ?>">
                            <i class="bi bi-pencil-square"></i>
                          </a>
                          <button <?= $program['associate_programns'] ? 'disabled' : '' ?> 
                                  class="btn btn-outline-danger btn-sm delete-program" 
                                  data-id="<?= $program['id'] ?>" 
                                  title="<?= __('admin.delete') ?>">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              
              <?php if (isset($pagination_info) && isset($pagination)) { ?>
                <div class="d-flex justify-content-between align-items-center p-3">
                  <div class="text-muted small">
                    <?= __('admin.showing') ?> <?= $pagination_info['showing_start'] ?> <?= __('admin.to') ?> <?= $pagination_info['showing_end'] ?> 
                    <?= __('admin.of') ?> <?= $pagination_info['total_rows'] ?> <?= __('admin.entries') ?>
                  </div>
                  <div>
                    <?= $pagination['html'] ?>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered intg-modal">
    <div class="modal-content">
      <div class="intg-modal-header">
        <div class="intg-modal-header-left">
          <div class="intg-modal-icon intg-modal-icon--warning">
            <i class="bi bi-exclamation-triangle"></i>
          </div>
          <div>
            <h5 class="intg-modal-title" id="deleteModalLabel"><?= __('admin.confirm_delete') ?></h5>
            <p class="intg-modal-subtitle"><?= __('admin.this_action_cannot_be_undone') ?></p>
          </div>
        </div>
        <button type="button" class="intg-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="intg-modal-card d-flex align-items-start gap-2">
          <i class="bi bi-info-circle text-warning mt-1"></i>
          <div class="small"><?= __('admin.delete_program_warning') ?></div>
        </div>
      </div>
      <div class="intg-modal-footer">
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal"><?= __('admin.cancel') ?></button>
        <button type="button" class="btn btn-danger rounded-pill" id="confirmDelete">
          <i class="bi bi-trash me-1"></i><?= __('admin.delete') ?>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered intg-modal">
    <div class="modal-content">
      <div class="intg-modal-header">
        <div class="intg-modal-header-left">
          <div class="intg-modal-icon intg-modal-icon--primary">
            <i class="bi bi-info-circle"></i>
          </div>
          <div>
            <h5 class="intg-modal-title" id="messageModalLabel"><?= __('admin.information') ?></h5>
          </div>
        </div>
        <button type="button" class="intg-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="intg-modal-card" id="messageModalBody"></div>
      </div>
      <div class="intg-modal-footer">
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?>
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    let deleteProgramId = null;
    
    // Search functionality with debounce
    let searchTimeout;
    $('#progname').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            getDataList();
        }, 500);
    });
    
    // Filter form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        getDataList();
    });
    
    function getDataList() {
        var formData = $('#filterForm').serialize();
        
        $.ajax({
            url: '<?= base_url('integration/search_programs/') ?>',
            type: 'POST',
            dataType: 'json',
            data: formData,
            beforeSend: function() {
                $('#data_list').html('<tr><td colspan="9" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
            },
            success: function(json) {
                $('#data_list').html(json);
            },
            error: function() {
                $('#data_list').html('<tr><td colspan="9" class="text-center text-danger"><?= __('admin.error_loading_data') ?></td></tr>');
            }
        });
    }
    
    // Delete program functionality
    $(document).on('click', '.delete-program', function() {
        if ($(this).prop('disabled')) return;
        
        deleteProgramId = $(this).data('id');
        $('#deleteModal').modal('show');
    });
    
    $('#confirmDelete').on('click', function() {
        if (!deleteProgramId) return;
        
        var $btn = $(this);
        var $deleteBtn = $('.delete-program[data-id="' + deleteProgramId + '"]');
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.deleting') ?>...');
        
        $.ajax({
            url: '<?= base_url('integration/delete_programs_form/') ?>',
            type: 'POST',
            dataType: 'json',
            data: {id: deleteProgramId},
            success: function(json) {
                if (json['success']) {
                    $('#program-row-' + deleteProgramId).fadeOut(300, function() {
                        $(this).remove();
                        if ($('#data_list tr').length === 0) {
                            location.reload();
                        }
                    });
                    
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', '<?= __('admin.program_deleted_successfully') ?>', 'success');
                    }
                } else {
                    if (json['message']) {
                        $('#messageModalBody').html(json['message']);
                        $('#messageModal').modal('show');
                    }
                }
            },
            error: function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.error_deleting_program') ?>', 'error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i><?= __('admin.delete') ?>');
                $('#deleteModal').modal('hide');
                deleteProgramId = null;
            }
        });
    });
});
</script>