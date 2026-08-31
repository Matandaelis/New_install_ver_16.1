<div class="container-fluid px-4 pb-4 clients-page">
<?php get_instance()->load->view('admincontrol/store/_store_nav'); ?>
<div class="row">
  <div class="col-12">

<!-- Header Section -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-header bg-primary text-white py-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
      <div class="d-flex align-items-center mb-2 mb-md-0">
        <i class="bi bi-people-fill me-2 fs-4"></i>
        <div>
          <h4 class="mb-0 fw-bold"><?= __('admin.clients_management') ?></h4>
          <small class="opacity-75"><?= __('admin.manage_store_clients') ?></small>
        </div>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-light btn-sm" onclick="refreshClients()" title="<?= __('admin.refresh') ?>">
          <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>
        </button>
        <button class="btn btn-outline-light btn-sm" onclick="exportClients()" title="<?= __('admin.export_clients') ?>">
          <i class="bi bi-download me-1"></i><?= __('admin.export') ?>
        </button>
        <a class="btn btn-light" href="<?php echo base_url("admincontrol/addclients") ?>">
          <i class="bi bi-person-plus me-1"></i><?= __('admin.add_client') ?>
        </a>
      </div>
    </div>
  </div>
  
  <!-- Stats Summary -->
  <div class="card-body bg-light border-bottom">
    <div class="row g-3" id="client-stats">
      <div class="col-md-3">
        <div class="d-flex align-items-center">
          <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
            <i class="bi bi-people text-success fs-5"></i>
          </div>
          <div>
            <div class="text-muted small"><?= __('admin.total_clients') ?></div>
            <div class="fw-bold fs-5" id="total-clients">-</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="d-flex align-items-center">
          <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
            <i class="bi bi-cart-check text-info fs-5"></i>
          </div>
          <div>
            <div class="text-muted small"><?= __('admin.active_clients') ?></div>
            <div class="fw-bold fs-5" id="active-clients">-</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="d-flex align-items-center">
          <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
            <i class="bi bi-currency-dollar text-warning fs-5"></i>
            </div>
          <div>
            <div class="text-muted small"><?= __('admin.total_sales') ?></div>
            <div class="fw-bold fs-5" id="total-sales">-</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="d-flex align-items-center">
          <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
            <i class="bi bi-graph-up text-danger fs-5"></i>
            </div>
          <div>
            <div class="text-muted small"><?= __('admin.avg_order_value') ?></div>
            <div class="fw-bold fs-5" id="avg-order-value">-</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filters Section -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-header bg-info text-white py-2">
    <div class="d-flex align-items-center">
      <i class="bi bi-funnel me-2"></i>
      <h6 class="mb-0 fw-bold"><?= __('admin.filters_search') ?></h6>
    </div>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold"><?= __('admin.search_clients') ?></label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" class="form-control" id="client-search" placeholder="<?= __('admin.search_by_name_email_phone') ?>">
        </div>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold"><?= __('admin.client_type') ?></label>
        <select class="form-select" id="type-filter">
          <option value=""><?= __('admin.all_types') ?></option>
          <option value="client"><?= __('admin.type_client') ?></option>
          <option value="guest"><?= __('admin.type_guest') ?></option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold"><?= __('admin.country') ?></label>
        <select class="form-select" id="country-filter">
          <option value=""><?= __('admin.all_countries') ?></option>
          <?php foreach($countries as $country): ?>
            <option value="<?= $country->id ?>"><?= $country->name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold"><?= __('admin.sort_by') ?></label>
        <select class="form-select" id="sort-filter">
          <option value="id_desc"><?= __('admin.newest_first') ?></option>
          <option value="id_asc"><?= __('admin.oldest_first') ?></option>
          <option value="name_asc"><?= __('admin.name_a_z') ?></option>
          <option value="sales_desc"><?= __('admin.highest_sales') ?></option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">&nbsp;</label>
        <div class="d-grid gap-2">
          <button class="btn btn-outline-secondary" onclick="clearFilters()">
            <i class="bi bi-x-circle me-1"></i><?= __('admin.clear') ?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Clients Table Section -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-dark text-white py-3">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <i class="bi bi-table me-2 fs-5"></i>
        <h5 class="mb-0 fw-bold"><?= __('admin.clients_list') ?></h5>
        <span class="badge bg-light text-dark ms-2" id="clients-count">0</span>
      </div>
      <div class="d-flex gap-2">
        <select class="form-select form-select-sm" id="per-page" style="width: auto;">
          <option value="25">25 <?= __('admin.per_page') ?></option>
          <option value="50" selected>50 <?= __('admin.per_page') ?></option>
          <option value="100">100 <?= __('admin.per_page') ?></option>
        </select>
      </div>
    </div>
  </div>
  <div class="card-body p-0">
    <!-- Loading State -->
    <div id="loading-state" class="text-center py-5">
      <div class="spinner-border text-primary mb-3" role="status">
        <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
      </div>
      <h5 class="text-muted"><?= __("admin.loading_clients_data_text") ?></h5>
      <p class="text-muted"><?= __("admin.not_taking_longer") ?></p>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="d-none text-center py-5">
      <i class="bi bi-people display-1 text-muted mb-3"></i>
      <h4 class="text-muted"><?= __('admin.no_clients_found') ?></h4>
      <p class="text-muted"><?= __('admin.no_clients_message') ?></p>
      <a href="<?php echo base_url("admincontrol/addclients") ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i><?= __('admin.add_first_client') ?>
      </a>
    </div>

    <!-- Clients Table -->
    <div class="table-responsive" id="clients-table-container" style="display: none;">
      <table class="table table-hover mb-0" id="clients-table">
        <thead class="table-dark">
          <tr>
            <th style="width: 80px;"><?= __('admin.id') ?></th>
            <th><?= __('admin.client') ?></th>
            <th><?= __('admin.contact') ?></th>
            <th><?= __('admin.referrer') ?></th>
            <th class="text-center"><?= __('admin.sales') ?></th>
            <th class="text-center"><?= __('admin.type') ?></th>
            <th class="text-center" style="width: 200px;"><?= __('admin.actions') ?></th>
          </tr>
        </thead>
        <tbody id="clients-tbody">
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- Pagination Footer -->
  <div class="card-footer bg-light" id="pagination-footer" style="display: none;">
    <div class="d-flex justify-content-between align-items-center">
      <div class="text-muted small" id="pagination-info">
        <?= __('admin.showing') ?> <span id="showing-from">0</span> <?= __('admin.to') ?> <span id="showing-to">0</span> <?= __('admin.of') ?> <span id="total-records">0</span> <?= __('admin.entries') ?>
      </div>
      <div id="pagination-links"></div>
    </div>
  </div>
</div>

</div>
</div>
</div>

<!-- Client Details Modal -->
<div class="modal fade" id="clientDetailsModal" tabindex="-1" aria-labelledby="clientDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="clientDetailsModalLabel">
          <i class="bi bi-person-circle me-2"></i><?= __('admin.client_details') ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="clientDetailsContent">
        <div class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i><?= __('admin.close') ?>
        </button>
        <a href="#" class="btn btn-primary" id="editClientBtn">
          <i class="bi bi-pencil me-1"></i><?= __('admin.edit_client') ?>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Shipping Details Modal -->
<div class="modal fade" id="ShipingDetailsModal" tabindex="-1" aria-labelledby="shippingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="shippingModalLabel">
          <i class="bi bi-truck me-2"></i><?= __('admin.shipping_details') ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4" id="shipping-content">
            <div class="col-md-6">
            <div class="card bg-light h-100">
              <div class="card-body">
                <h6 class="card-title text-primary mb-3">
                  <i class="bi bi-geo-alt me-1"></i><?= __('admin.address_information') ?>
                </h6>
              <div class="mb-3">
                  <label class="form-label fw-semibold"><?= __('admin.address') ?></label>
                  <div class="form-control-plaintext border rounded p-2 bg-white" id="address">-</div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <label class="form-label fw-semibold"><?= __('admin.city') ?></label>
                    <div class="form-control-plaintext border rounded p-2 bg-white" id="city">-</div>
                  </div>
                  <div class="col-6">
                    <label class="form-label fw-semibold"><?= __('admin.postal_code') ?></label>
                    <div class="form-control-plaintext border rounded p-2 bg-white" id="zip_code">-</div>
              </div>
            </div>
              </div>
            </div>
          </div>
            <div class="col-md-6">
            <div class="card bg-light h-100">
              <div class="card-body">
                <h6 class="card-title text-success mb-3">
                  <i class="bi bi-globe me-1"></i><?= __('admin.location_information') ?>
                </h6>
              <div class="mb-3">
                  <label class="form-label fw-semibold"><?= __('admin.country') ?></label>
                  <div class="form-control-plaintext border rounded p-2 bg-white" id="country_id">-</div>
            </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold"><?= __('admin.state') ?></label>
                  <div class="form-control-plaintext border rounded p-2 bg-white" id="state_id">-</div>
          </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold"><?= __('admin.phone') ?></label>
                  <div class="form-control-plaintext border rounded p-2 bg-white" id="phone">
                    <a href="#" id="phone-link" class="text-decoration-none">-</a>
              </div>
            </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i><?= __('admin.close') ?>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    let currentPage = 1;
    let currentFilters = {};
    let searchTimeout;

    // Load clients function
    function loadClients(page = 1, filters = {}) {
        currentPage = page;
        currentFilters = filters;
        
        const data = {
            listclients: 1,
            page: page,
            limit: $('#per-page').val() || 50,
            ...filters
        };

        $.ajax({
            url: "<?= base_url('admincontrol/listclients'); ?>/" + page,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                showLoading();
            },
            success: function(response) {
                hideLoading();
                
                if (response.html && response.html.trim() !== '') {
                    $('#clients-tbody').html(response.html);
                    $('#clients-table-container').show();
                    $('#empty-state').addClass('d-none');
                    
                    // Update pagination
                    if (response.pagination) {
                        $('#pagination-links').html(response.pagination);
                        $('#pagination-footer').show();
                        updatePaginationInfo(page, data.limit, response.total || 0);
                    } else {
                        $('#pagination-footer').hide();
                    }
                    
                    // Update clients count
                    $('#clients-count').text(response.total || 0);
                    updateStats(response.stats || {});
                } else {
                    showEmptyState();
                }
            },
            error: function() {
                hideLoading();
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_clients') ?>', 'error', 3000);
                }
            }
        });
    }

    // Show loading state
    function showLoading() {
        $('#loading-state').show();
        $('#clients-table-container').hide();
        $('#empty-state').addClass('d-none');
        $('#pagination-footer').hide();
    }

    // Hide loading state
    function hideLoading() {
        $('#loading-state').hide();
    }

    // Show empty state
    function showEmptyState() {
        $('#clients-table-container').hide();
        $('#empty-state').removeClass('d-none');
        $('#pagination-footer').hide();
        $('#clients-count').text('0');
    }

    // Update pagination info
    function updatePaginationInfo(page, perPage, total) {
        const from = ((page - 1) * perPage) + 1;
        const to = Math.min(page * perPage, total);
        
        $('#showing-from').text(from);
        $('#showing-to').text(to);
        $('#total-records').text(total);
    }

    // Update stats
    function updateStats(stats) {
        $('#total-clients').text(stats.total_clients || '-');
        $('#active-clients').text(stats.active_clients || '-');
        $('#total-sales').text(stats.total_sales || '-');
        $('#avg-order-value').text(stats.avg_order_value || '-');
    }

    // Search functionality with debounce
    $('#client-search').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();
        
        searchTimeout = setTimeout(function() {
            const filters = getFilters();
            filters.search = searchTerm;
            loadClients(1, filters);
        }, 500);
    });

    // Filter change handlers
    $('#type-filter, #country-filter, #sort-filter').on('change', function() {
        const filters = getFilters();
        loadClients(1, filters);
    });

    // Per page change handler
    $('#per-page').on('change', function() {
        const filters = getFilters();
        loadClients(1, filters);
    });

    // Get current filters
    function getFilters() {
        return {
            search: $('#client-search').val(),
            type: $('#type-filter').val(),
            country: $('#country-filter').val(),
            sort: $('#sort-filter').val()
        };
    }

    // Clear filters
    window.clearFilters = function() {
        $('#client-search').val('');
        $('#type-filter').val('');
        $('#country-filter').val('');
        $('#sort-filter').val('id_desc');
        loadClients(1, {});
    };

    // Refresh clients
    window.refreshClients = function() {
        const filters = getFilters();
        loadClients(currentPage, filters);
        if (typeof showToast === 'function') {
            showToast('<?= __('admin.success') ?>', '<?= __('admin.clients_refreshed') ?>', 'success', 2000);
        }
    };

    // Export clients
    window.exportClients = function() {
        if (typeof showToast === 'function') {
            showToast('<?= __('admin.info') ?>', '<?= __('admin.export_feature_coming_soon') ?>', 'info', 3000);
        }
    };

    // Pagination click handler
    $(document).on('click', '#pagination-links a', function(e) {
        e.preventDefault();
        const page = $(this).attr('data-ci-pagination-page');
        if (page) {
            const filters = getFilters();
            loadClients(page, filters);
        }
    });

    // Delete user handler
    $(document).on('click', '.deleteuser', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).data('url');
        const clientName = $(this).data('name') || '<?= __('admin.this_client') ?>';
        
    Swal.fire({
    icon: 'warning',
            title: '<?= __('admin.confirm_deletion') ?>',
            html: '<?= __('admin.delete_client_warning') ?><br><strong>' + clientName + '</strong>',
    showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: '<?= __('admin.yes_delete') ?>',
            cancelButtonText: '<?= __('admin.cancel') ?>',
            reverseButtons: true
    }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
    });
    });

    // View shipping details handler
    $(document).on('click', '.viewShipping', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const clientId = $btn.data('id');
        
    $.ajax({
    url: '<?= base_url('admincontrol/getShippingDetails') ?>',
    type: 'POST',
    dataType: 'json',
            data: { id: clientId },
    beforeSend: function() {
                $btn.prop("disabled", true).html('<i class="bi bi-hourglass-split"></i>');
    },
    complete: function() {
                $btn.prop("disabled", false).html('<i class="bi bi-truck"></i>');
    },
    success: function(data) {
                if (data.status && data.data) {
                    const shippingData = data.data;
                    
                    $('#address').text(shippingData.address || '<?= __('admin.not_available') ?>');
                    $('#country_id').text(shippingData.country_name || '<?= __('admin.not_available') ?>');
                    $('#state_id').text(shippingData.state_name || '<?= __('admin.not_available') ?>');
                    $('#city').text(shippingData.city || '<?= __('admin.not_available') ?>');
                    $('#zip_code').text(shippingData.zip_code || '<?= __('admin.not_available') ?>');
                    
                    if (shippingData.phone) {
                        $('#phone-link').text(shippingData.phone).attr('href', 'tel:' + shippingData.phone);
                    } else {
                        $('#phone-link').text('<?= __('admin.not_available') ?>').removeAttr('href');
                    }
                    
                    $('#ShipingDetailsModal').modal('show');
      } else {
        Swal.fire({
          icon: 'info',
                        title: '<?= __('admin.no_shipping_details') ?>',
          text: '<?= __('admin.no_shipping_details_found') ?>',
                        confirmButtonText: '<?= __('admin.ok') ?>'
                    });
      }
    },
            error: function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_shipping_details') ?>', 'error', 3000);
                }
            }
    });
    });

    // View client details handler
    $(document).on('click', '.viewClientDetails', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const clientId = $btn.data('id');
        
        // Show loading in modal
        $('#clientDetailsContent').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                </div>
            </div>
        `);
        
        // Show modal immediately
        $('#clientDetailsModal').modal('show');
        
        $.ajax({
            url: '<?= base_url('admincontrol/getClientDetails') ?>',
            type: 'POST',
            dataType: 'json',
            data: { id: clientId },
            success: function(data) {
                if (data.status && data.html) {
                    $('#clientDetailsContent').html(data.html);
                    $('#editClientBtn').attr('href', '<?= base_url('admincontrol/addclients') ?>/' + clientId);
                } else {
                    $('#clientDetailsContent').html(`
                        <div class="alert alert-warning text-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= __('admin.client_details_not_found') ?>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#clientDetailsContent').html(`
                    <div class="alert alert-danger text-center">
                        <i class="bi bi-x-circle me-2"></i>
                        <?= __('admin.failed_to_load_client_details') ?>
                    </div>
                `);
            }
        });
    });

    // Initialize page
    loadClients(1, {});
    });
</script>