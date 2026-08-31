<div class="container-fluid px-4 pb-4 orders-page">
<?php get_instance()->load->view('admincontrol/store/_store_nav'); ?>
<div class="row">
<div class="col-12">

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-cart-check-fill me-2"></i>
                <h5 class="mb-0 fw-semibold"><?= __('admin.orders_management') ?></h5>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-light text-primary fs-6 px-3 py-2">
                    <i class="bi bi-graph-up me-1"></i><?= __('admin.total_orders') ?>: <?= $full_local_store_hold_orders ?>
                </span>
                <div class="btn-group">
                    <button class="btn btn-outline-light btn-sm" onclick="refreshOrders()">
                        <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>
                    </button>
                    <button class="btn btn-outline-light btn-sm" onclick="exportOrders()">
                        <i class="bi bi-download me-1"></i><?= __('admin.export') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Advanced Filters -->
        <div class="p-4">
            <div class="bg-light rounded-3 p-3 mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-medium small">
                            <i class="bi bi-search me-1"></i><?= __('admin.search_orders') ?>
                        </label>
                        <input type="text" id="order-search" class="form-control form-control-sm" placeholder="<?= __('admin.search_by_order_id_username') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium small">
                            <i class="bi bi-funnel me-1"></i><?= __('admin.status') ?>
                        </label>
                        <select id="status-filter" class="form-select form-select-sm">
                            <option value=""><?= __('admin.all_status') ?></option>
                            <?php foreach($status as $key => $value): ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium small">
                            <i class="bi bi-credit-card me-1"></i><?= __('admin.payment_method') ?>
                        </label>
                        <select id="payment-filter" class="form-select form-select-sm">
                            <option value=""><?= __('admin.all_methods') ?></option>
                            <option value="Paypal"><?= __('admin.paypal') ?></option>
                            <option value="Bank Transfer"><?= __('admin.bank_transfer') ?></option>
                            <option value="Cash On Delivery"><?= __('admin.cash_on_delivery') ?></option>
                            <option value="Razorpay"><?= __('admin.razorpay') ?></option>
                            <option value="Flutterwave"><?= __('admin.flutterwave') ?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium small">
                            <i class="bi bi-calendar me-1"></i><?= __('admin.date_range') ?>
                        </label>
                        <input type="date" id="date-from" class="form-control form-control-sm" title="<?= __('admin.from_date') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium small text-white">.</label>
                        <input type="date" id="date-to" class="form-control form-control-sm" title="<?= __('admin.to_date') ?>">
                    </div>
                    <div class="col-md-1">
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()" title="<?= __('admin.clear_filters') ?>">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Bulk Actions -->
                <div class="row mt-3" id="bulk-actions" style="display: none;">
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 p-2 bg-warning bg-opacity-10 rounded">
                            <span class="fw-medium text-warning">
                                <i class="bi bi-check-square me-1"></i><span id="selected-count">0</span> <?= __('admin.orders_selected') ?>
                            </span>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-success" onclick="bulkStatusChange(1)">
                                    <i class="bi bi-check-circle me-1"></i><?= __('admin.mark_complete') ?>
                                </button>
                                <button class="btn btn-outline-warning" onclick="bulkStatusChange(7)">
                                    <i class="bi bi-pause-circle me-1"></i><?= __('admin.mark_hold') ?>
                                </button>
                                <button class="btn btn-outline-info" onclick="bulkExport()">
                                    <i class="bi bi-download me-1"></i><?= __('admin.export_selected') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Empty State -->
        <section class="empty-div d-none">
            <div class="d-flex justify-content-center align-items-center flex-column py-5">
                <div class="bg-light rounded-circle p-4 mb-4">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                </div>
                <h4 class="text-dark mb-2 fw-bold"><?= __('admin.no_orders_found') ?></h4>
                <p class="text-muted mb-0"><?= __('admin.no_orders_available_moment') ?></p>
            </div>
        </section>

        <!-- Orders Table -->
        <div class="table-responsive">
            <table id="orders-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold border-0" style="width: 50px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select-all-orders">
                            </div>
                        </th>
                        <th class="fw-semibold border-0" style="width: 80px;"><?= __('admin.order_id') ?></th>
                        <th class="fw-semibold border-0"><?= __('admin.customer') ?></th>
                        <th class="fw-semibold border-0"><?= __('admin.type') ?></th>
                        <th class="fw-semibold border-0 text-end"><?= __('admin.amount') ?></th>
                        <th class="fw-semibold border-0"><?= __('admin.payment') ?></th>
                        <th class="fw-semibold border-0"><?= __('admin.location') ?></th>
                        <th class="fw-semibold border-0"><?= __('admin.transaction_id') ?></th>
                        <th class="fw-semibold border-0 text-end"><?= __('admin.commission') ?></th>
                        <th class="fw-semibold border-0 text-center"><?= __('admin.status') ?></th>
                        <th class="fw-semibold border-0 text-center"><?= __('admin.quick_action') ?></th>
                        <th class="fw-semibold border-0 text-center"><?= __('admin.actions') ?></th>
                    </tr>
                </thead>
                <tbody id="orders-tbody">
                    <tr>
                        <td colspan="12" class="text-center border-0 py-5">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status" aria-label="<?= html_escape(__('admin.loading_orders_data_text')) ?>"></div>
                                <p class="text-muted mb-0"><?= __("admin.loading_orders_data_text") ?></p>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="12" class="text-center py-3">
                            <ul class="pagination pagination-td justify-content-center mb-0"></ul>
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

<!-- Status Change Modal -->
<div class="modal fade" id="model-confirmodal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold mb-0" id="statusModalLabel">
                    <i class="bi bi-arrow-repeat me-2"></i><?= __('admin.change_order_status') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="complete-text">
                    <div class="alert alert-info border-0 d-flex align-items-start" role="alert">
                        <div class="bg-info bg-opacity-25 rounded-circle p-2 me-3 flex-shrink-0">
                            <i class="bi bi-info-circle text-info"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading fw-bold mb-2"><?= __('admin.status_change_info') ?></h6> 
                            <p class="mb-0 small"><?= __('admin.status_change_description') ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <div class="bg-primary bg-opacity-25 rounded-circle p-3 d-inline-flex mb-3">
                        <i class="bi bi-question-circle text-primary display-6"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2"><?= __('admin.confirm_status_change') ?></h6>
                    <p class="text-muted small mb-1"><?= __('admin.status_change_confirmation') ?></p>
                    <p class="small mb-0 text-dark" id="order-status-pending-summary" aria-live="polite"></p>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 justify-content-center">
                <div class="modal-buttons d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i><?= __('admin.cancel') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-check-square me-2"></i><?= __('admin.bulk_action') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="bg-warning bg-opacity-25 rounded-circle p-3 d-inline-flex mb-3">
                        <i class="bi bi-exclamation-triangle text-warning display-6"></i>
                    </div>
                    <h6 class="fw-bold mb-2"><?= __('admin.confirm_bulk_action') ?></h6>
                    <p class="text-muted" id="bulk-action-message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i><?= __('admin.cancel') ?>
                </button>
                <button type="button" class="btn btn-warning" id="confirm-bulk-action">
                    <i class="bi bi-check-circle me-1"></i><?= __('admin.confirm') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    let searchTimeout;
    let isLoadingOrders = false;
    let currentPage = 1;
    let selectedOrders = [];

    // Load orders on page load
    loadOrders(1);

    // Search functionality with debounce
    $('#order-search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            currentPage = 1;
            loadOrders(1);
        }, 500);
    });

    // Filter changes
    $('#status-filter, #payment-filter, #date-from, #date-to').on('change', function() {
        currentPage = 1;
        loadOrders(1);
    });

    // Select all orders checkbox
    $('#select-all-orders').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.order-checkbox').prop('checked', isChecked);
        updateSelectedOrders();
    });

    // Individual order checkbox
    $(document).on('change', '.order-checkbox', function() {
        updateSelectedOrders();
        
        // Update select all checkbox
        const totalCheckboxes = $('.order-checkbox').length;
        const checkedCheckboxes = $('.order-checkbox:checked').length;
        $('#select-all-orders').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    function openOrderStatusModal(id, val, statusLabel) {
        $("#model-confirmodal .btn-status-change").remove();

        const $btn = $('<button type="button" class="btn btn-status-change btn-primary"><i class="bi bi-check me-1"></i><?= __('admin.yes_confirm') ?></button>');
        $btn.on('click', function() {
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span><?= __('admin.processing') ?>...');
            changeStatus(id, val);
        });
        $btn.prependTo(".modal-buttons");

        if (String(val) === '1') {
            $("#model-confirmodal .complete-text").show();
        } else {
            $("#model-confirmodal .complete-text").hide();
        }

        const label = (statusLabel && String(statusLabel).trim()) ? String(statusLabel).trim() : '';
        const $sum = $("#order-status-pending-summary");
        if (label) {
            const statusPrefix = <?= json_encode(__('admin.order_status_will_be_set_to')) ?>;
            $sum.html(statusPrefix + ' <span class="fw-semibold">' + $('<div/>').text(label).html() + '</span>');
        } else {
            $sum.empty();
        }

        $("#model-confirmodal").modal("show");
    }

    $(document).on('click', '.order-quick-btn, .order-quick-pick', function(e) {
        e.preventDefault();
        const $el = $(this);
        const id = $el.data('order-id');
        const val = $el.data('status');
        const statusLabel = $el.data('status-label');
        if (id == null || val == null || val === '') {
            return;
        }
        openOrderStatusModal(id, val, statusLabel);
    });

    function loadOrders(page = 1, $triggerElement = null) {
        if (isLoadingOrders) return false;
        
        isLoadingOrders = true;
        currentPage = page;
        
        const searchTerm = $('#order-search').val();
        const statusFilter = $('#status-filter').val();
        const paymentFilter = $('#payment-filter').val();
        const dateFrom = $('#date-from').val();
        const dateTo = $('#date-to').val();
        
        const data = {
            getOrdersRows: 1,
            page: page,
            search: searchTerm,
            status: statusFilter,
            payment_method: paymentFilter,
            date_from: dateFrom,
            date_to: dateTo
        };
        
        $.ajax({
            url: "<?= base_url('admincontrol/listorders'); ?>",
            type: 'POST',
            dataType: 'json',
            data: data,
            global: false,
            beforeSend: function() {
                if ($triggerElement && $triggerElement.length) {
                    $triggerElement.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
                } else {
                    $("#orders-tbody").html(`
                        <tr>
                            <td colspan="12" class="text-center border-0 py-5">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status" aria-label="<?= html_escape(__('admin.loading_orders_data_text')) ?>"></div>
                                    <p class="text-muted mb-0"><?= __("admin.loading_orders_data_text") ?></p>
                                </div>
                            </td>
                        </tr>
                    `);
                }
            },
            complete: function() {
                isLoadingOrders = false;
                if ($triggerElement && $triggerElement.length) {
                    $triggerElement.prop('disabled', false).html($triggerElement.data('original-text') || $triggerElement.text());
                }
            },
            success: function(json) {
                if (json['view']) {
                    $("#orders-tbody").html(json['view']);
                    $("#orders-table").show();
                    $(".empty-div").addClass("d-none");
                } else {
                    $(".empty-div").removeClass("d-none");
                    $("#orders-table").hide();
                }

                $("#orders-table .pagination-td").html(json['pagination'] || '');
                
                // Reset selections
                selectedOrders = [];
                updateSelectedOrders();
            },
            error: function(xhr, status, error) {
                console.error('Orders loading error:', error);
                isLoadingOrders = false;
                $("#orders-tbody").html(`
                    <tr>
                        <td colspan="12" class="text-center border-0 py-5">
                            <div class="text-center">
                                <div class="bg-danger bg-opacity-25 rounded-circle p-3 d-inline-flex mb-3">
                                    <i class="bi bi-exclamation-triangle text-danger display-6"></i>
                                </div>
                                <h5 class="text-danger mb-2 fw-bold"><?= __('admin.error_loading_data') ?></h5>
                                <p class="text-muted small mb-3"><?= __('admin.unable_to_load_orders') ?></p>
                                <button class="btn btn-primary btn-sm" onclick="loadOrders(${currentPage})">
                                    <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.retry') ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                `);
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_orders') ?>', 'error', 5000);
                }
            }
        });
    }

    function changeStatus(id, val) {
        $.ajax({
            url: '<?= base_url("admincontrol/order_change_status") ?>',
            type: 'POST',
            dataType: 'json',
            data: { id: id, val: val },
            global: false,
            success: function(json) {
                $("#model-confirmodal").modal("hide");
                $("#model-confirmodal .btn-status-change").prop('disabled', false).html('<i class="bi bi-check me-1"></i><?= __('admin.yes_confirm') ?>');
                if (json['status']) {
                    // Update the row instead of reloading
                    const $row = $(`tr[data-order-id="${id}"]`);
                    if ($row.length) {
                        $row.find('.order-status').html(json['status']);
                        $row.addClass('table-success').delay(2000).queue(function() {
                            $(this).removeClass('table-success').dequeue();
                        });
                    }
                    
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', '<?= __('admin.order_status_updated') ?>', 'success', 3000);
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.warning') ?>', '<?= __('admin.order_status_not_change') ?>', 'warning', 5000);
                    }
                }
            },
            error: function() {
                $("#model-confirmodal").modal("hide");
                $("#model-confirmodal .btn-status-change").prop('disabled', false).html('<i class="bi bi-check me-1"></i><?= __('admin.yes_confirm') ?>');
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.status_change_failed') ?>', 'error', 5000);
                }
            }
        });
    }

    function updateSelectedOrders() {
        selectedOrders = [];
        $('.order-checkbox:checked').each(function() {
            selectedOrders.push($(this).val());
        });
        
        $('#selected-count').text(selectedOrders.length);
        
        if (selectedOrders.length > 0) {
            $('#bulk-actions').show();
        } else {
            $('#bulk-actions').hide();
        }
    }

    // Global functions
    window.refreshOrders = function() {
        loadOrders(currentPage);
    };

    window.clearFilters = function() {
        $('#order-search').val('');
        $('#status-filter').val('');
        $('#payment-filter').val('');
        $('#date-from').val('');
        $('#date-to').val('');
        currentPage = 1;
        loadOrders(1);
    };

    window.exportOrders = function() {
        if (typeof showToast === 'function') {
            showToast('<?= __('admin.info') ?>', '<?= __('admin.export_feature_coming_soon') ?>', 'info', 3000);
        }
    };

    window.bulkStatusChange = function(status) {
        if (selectedOrders.length === 0) {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.warning') ?>', '<?= __('admin.no_orders_selected') ?>', 'warning', 3000);
            }
            return;
        }

        const statusText = status === 1 ? '<?= __('admin.complete') ?>' : '<?= __('admin.hold') ?>';
        $('#bulk-action-message').text(`<?= __('admin.change_status_for') ?> ${selectedOrders.length} <?= __('admin.orders_to') ?> ${statusText}?`);
        
        $('#confirm-bulk-action').off('click').on('click', function() {
            // Implement bulk status change
            $('#bulkActionModal').modal('hide');
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.info') ?>', '<?= __('admin.bulk_action_feature_coming_soon') ?>', 'info', 3000);
            }
        });
        
        $('#bulkActionModal').modal('show');
    };

    window.bulkExport = function() {
        if (selectedOrders.length === 0) {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.warning') ?>', '<?= __('admin.no_orders_selected') ?>', 'warning', 3000);
            }
            return;
        }
        
        if (typeof showToast === 'function') {
            showToast('<?= __('admin.info') ?>', '<?= __('admin.bulk_export_feature_coming_soon') ?>', 'info', 3000);
        }
    };

    window.loadOrders = loadOrders;

    // Show order details function
    window.showOrderDetails = function(orderId) {
        if (!orderId) {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.invalid_order_id') ?>', 'error', 3000);
            }
            return;
        }

        // Create and show modal with order details
        const modalId = 'orderDetailsModal';
        
        // Remove existing modal if any
        $('#' + modalId).remove();
        
        const modalHtml = `
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-info-circle me-2"></i><?= __('admin.order_details') ?> #${orderId}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                                </div>
                                <p class="text-muted mt-2"><?= __('admin.loading_order_details') ?>...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i><?= __('admin.close') ?>
                            </button>
                            <a href="<?= base_url('admincontrol/vieworder/') ?>${orderId}" class="btn btn-primary" target="_blank">
                                <i class="bi bi-eye me-1"></i><?= __('admin.view_full_details') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to body and show it
        $('body').append(modalHtml);
        $('#' + modalId).modal('show');
        
        // Load order details via AJAX
        $.ajax({
            url: '<?= base_url("admincontrol/get_order_quick_details") ?>',
            type: 'POST',
            dataType: 'json',
            data: { order_id: orderId },
            global: false,
            success: function(response) {
                if (response.success && response.html) {
                    $('#' + modalId + ' .modal-body').html(response.html);
                } else {
                    $('#' + modalId + ' .modal-body').html(`
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle display-1 text-warning mb-3"></i>
                            <h5 class="text-warning"><?= __('admin.no_details_available') ?></h5>
                            <p class="text-muted"><?= __('admin.order_details_not_found') ?></p>
                        </div>
                    `);
                }
            },
            error: function() {
                // Fallback: Show basic order info from the current row
                const $row = $(`tr[data-order-id="${orderId}"]`);
                if ($row.length) {
                    const customerName = $row.find('td:nth-child(3) .fw-semibold').text();
                    const amount = $row.find('td:nth-child(5) .fw-bold').text();
                    const status = $row.find('td:nth-child(10) .badge').text();
                    const payment = $row.find('td:nth-child(6) .badge').text();
                    
                    const basicInfo = `
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="bi bi-person me-1"></i><?= __('admin.customer') ?>
                                        </h6>
                                        <p class="card-text fw-semibold">${customerName}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">
                                            <i class="bi bi-currency-dollar me-1"></i><?= __('admin.amount') ?>
                                        </h6>
                                        <p class="card-text fw-semibold text-success">${amount}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-info">
                                            <i class="bi bi-credit-card me-1"></i><?= __('admin.payment_method') ?>
                                        </h6>
                                        <p class="card-text">${payment}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">
                                            <i class="bi bi-flag me-1"></i><?= __('admin.status') ?>
                                        </h6>
                                        <p class="card-text">${status}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <?= __('admin.quick_view_limited_info') ?>
                        </div>
                    `;
                    
                    $('#' + modalId + ' .modal-body').html(basicInfo);
                } else {
                    $('#' + modalId + ' .modal-body').html(`
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle display-1 text-danger mb-3"></i>
                            <h5 class="text-danger"><?= __('admin.error') ?></h5>
                            <p class="text-muted"><?= __('admin.failed_to_load_order_details') ?></p>
                        </div>
                    `);
                }
            }
        });
        
        // Clean up modal when hidden
        $('#' + modalId).on('hidden.bs.modal', function() {
            $(this).remove();
        });
    };
});
</script>