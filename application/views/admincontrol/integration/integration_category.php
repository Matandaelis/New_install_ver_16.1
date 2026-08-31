<div class="container-fluid px-4 pb-4">
  <?php $this->load->view('admincontrol/integration/_campaign_nav'); ?>
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><?= __('admin.integration_category') ?></h4>
        <a href="<?= base_url('integration/integration_category_add') ?>" class="btn btn-success btn-sm">
          <i class="bi bi-plus-lg me-1"></i><?= __('admin.add_category') ?>
        </a>
      </div>

      <div class="card shadow-sm intg-table-card">
        <div class="card-header bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0 fw-bold">
              <i class="bi bi-folder me-2 text-primary"></i><?= __('admin.integration_category') ?>
            </h6>
            <span class="badge bg-light text-muted border fw-normal" id="category-count"><?= __('admin.loading') ?>...</span>
          </div>
        </div>
        
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 intg-table">
              <thead class="table-light">
                <tr>
                  <th width="10%"><?= __('admin.id') ?></th>
                  <th width="30%"><?= __('admin.name') ?></th>
                  <th width="25%"><?= __('admin.parent') ?></th>
                  <th width="20%"><?= __('admin.date') ?></th>
                  <th width="15%" class="text-center"><?= __('admin.actions') ?></th>
                </tr>
              </thead>
              <tbody id="category_list">
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="d-flex justify-content-between align-items-center p-3" id="pagination_info" style="display: none;">
            <div class="text-muted small">
              <span id="showing_info"></span>
            </div>
            <div id="pagination_links"></div>
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
          <div class="small"><?= __('admin.delete_category_warning') ?></div>
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

<script type="text/javascript">
$(document).ready(function() {
    let deleteCategoryId = null;
    let currentPage = 1;
    
    function getPage(page, element = null) {
        if (element) {
            $(element).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
        }
        
        var data = {
            page: page,
            filter_status: $(".filter_status").val() || ''
        };
        
        $.ajax({
            url: '<?= base_url("integration/integration_category") ?>/' + page,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                $('#category_list').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"><?= __('admin.loading') ?>...</span></div></td></tr>');
            },
            success: function(json) {
                $('#category_list').html(json['html']);
                $('#pagination_info').hide();
                
                var rowCount = $('#category_list tr').length;
                if(rowCount > 0 && !$('#category_list tr td[colspan]').length) {
                    $('#category-count').text(json['total'] ? json['total'] + ' <?= __('admin.entries') ?>' : rowCount + ' <?= __('admin.entries') ?>');
                } else {
                    $('#category-count').text('<?= __('admin.no_data_found') ?>');
                }
                
                if (json['pagination']) {
                    $('#pagination_info').show();
                    $('#pagination_links').html(json['pagination']);
                    
                    if (json['start_from'] && json['total']) {
                        var endFrom = Math.min(parseInt(json['start_from']) + 99, parseInt(json['total']));
                        $('#showing_info').text('<?= __('admin.showing') ?> ' + json['start_from'] + ' <?= __('admin.to') ?> ' + endFrom + ' <?= __('admin.of') ?> ' + json['total'] + ' <?= __('admin.entries') ?>');
                    }
                }
                
                currentPage = page;
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            error: function() {
                $('#category_list').html('<tr><td colspan="5" class="text-center text-danger"><?= __('admin.error_loading_data') ?></td></tr>');
            },
            complete: function() {
                if (element) {
                    $(element).prop('disabled', false).html($(element).data('original-text') || '<?= __('admin.submit') ?>');
                }
            }
        });
    }
    
    $(document).on('click', '#pagination_links a', function(e) {
        e.preventDefault();
        var page = $(this).attr('data-ci-pagination-page');
        if (page) {
            getPage(page, this);
        }
    });
    
    $(document).on('click', '.delete-category', function(e) {
        e.preventDefault();
        deleteCategoryId = $(this).data('id');
        $('#deleteModal').modal('show');
    });
    
    $('#confirmDelete').on('click', function() {
        if (!deleteCategoryId) return;
        
        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.deleting') ?>...');
        
        $.ajax({
            url: '<?= base_url('integration/integration_category_delete/') ?>' + deleteCategoryId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#deleteModal').modal('hide');
                
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.success') ?>', '<?= __('admin.category_deleted_successfully') ?>', 'success');
                }
                
                getPage(currentPage);
            },
            error: function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.error_deleting_category') ?>', 'error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i><?= __('admin.delete') ?>');
                deleteCategoryId = null;
            }
        });
    });
    
    getPage(1);
});
</script>
