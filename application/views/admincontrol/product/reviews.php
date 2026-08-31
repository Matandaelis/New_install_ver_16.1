<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$store_setting =$db->Product_model->getSettings('store');
	$Product_model =$db->Product_model;
?>

<div class="container-fluid reviews-page">
<div class="row">
<div class="col-12">

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-star-fill me-2"></i>
                <h5 class="mb-0 fw-semibold"><?= __('admin.product_reviews') ?></h5>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-light btn-sm" href="<?= base_url('admincontrol/manage_review/')  ?>">
                    <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new') ?>
                </a>
                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#manageBulkReviews">
                    <i class="bi bi-gear me-1"></i><?= __('admin.manage_bulk_reviews') ?>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-4">
            <div class="bg-light rounded-3 p-3 mb-4">
                <form id="filter-form-review">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-medium small">
                                <i class="bi bi-funnel me-1"></i><?= __('admin.filter') ?> <?= __('admin.product_name') ?>
                            </label>
                            <select id="product_name_review" name="product_name_review" class="form-select form-select-sm">
                                <option value=""><?= __('admin.all_product') ?></option>
                                <?php if(isset($productlist) && $productlist): ?>
                                    <?php foreach ($productlist as $key => $product) { ?>
                                        <option value="<?= $product['product_id'] ?>"><?= $product['product_name'] ?></option> 
                                    <?php } ?>
                                <?php endif; ?>
                            </select>   
                        </div> 
                        <div class="col-md-3">
                            <div class="d-flex align-items-center h-100">
                                <div class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-star me-1"></i><?= __('admin.total') ?> <?= __('admin.reviews') ?>: 
                                    <span id="total_review" class="badge bg-info ms-1">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-success btn-sm export-reviews-btn">
                                    <i class="bi bi-download me-1"></i><?= __('admin.export') ?>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                                    <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.reset') ?>
                                </button>
                            </div>
                        </div> 
                    </div>
                </form>
            </div>
        </div>

				
				
        <div class="table-responsive">
            <table id="table-review" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold text-center" style="width: 80px;"><?= __('admin.image') ?></th>
                        <th class="fw-semibold"><?= __('admin.customer') ?></th>
                        <th class="fw-semibold"><?= __('admin.product_name') ?></th>
                        <th class="fw-semibold"><?= __('admin.review') ?></th>
                        <th class="fw-semibold text-center" style="width: 120px;"><?= __('admin.rating') ?></th>
                        <th class="fw-semibold" style="width: 140px;"><?= __('admin.datetime') ?></th> 
                        <th class="fw-semibold text-center" style="width: 140px;"><?= __('admin.actions') ?></th>
                    </tr>
                </thead>
                <tbody id="reviews-tbody">
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                                </div>
                                <p class="text-muted"><?= __('admin.loading') ?> <?= __('admin.reviews') ?>...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-center py-3">
                            <ul class="pagination pagination-td justify-content-center mb-0"></ul>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<!-- Bulk Reviews Management Modal -->
<div id="manageBulkReviews" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-gear me-2"></i><?= __('admin.manage_bulk_reviews') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <button class="btn btn-outline-success w-100 export-reviews-xml-btn">
                                    <i class="bi bi-download me-2"></i><?= __('admin.export_reviews_xml') ?>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-outline-info w-100 export-reviews-structure-xml-btn">
                                    <i class="bi bi-file-earmark-code me-2"></i><?= __('admin.export_structure_xml_only') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-upload me-2"></i><?= __('admin.import_reviews') ?>
                </h6>
                <ul class="nav nav-pills nav-fill mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" id="import-review-file-tab" data-bs-toggle="tab" href="#import_review_file_tab_" role="tab">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i><?= __('admin.import_from_file') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="import-review-link-tab" data-bs-toggle="tab" href="#import_review_link_tab" role="tab">
                            <i class="bi bi-link-45deg me-1"></i><?= __('admin.import_from_url') ?>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="import_review_file_tab_" role="tabpanel">
                        <form id="bulk_reviews_form">
                            <div class="mb-3">
                                <label for="customFile" class="form-label fw-medium"><?= __('admin.upload_xml_file_for_bulk_review_manage') ?></label>
                                <input type="file" class="form-control" name="file" id="customFile" accept=".xml">
                            </div>
                            <div class="text-center">
                                <button id="bulk_reviews_form_btn" type="submit" class="btn btn-success">
                                    <i class="bi bi-upload me-1"></i><?= __('admin.import_reviews') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="import_review_link_tab" role="tabpanel">
                        <form id="bulk_reviews_form_url">
                            <div class="mb-3">
                                <label for="txt_review_xmlurl" class="form-label fw-medium"><?= __('admin.enter_xml_url_for_bulk_review_manage') ?></label>
                                <input name="txt_review_xmlurl" id="txt_review_xmlurl" class="form-control" type="url" placeholder="https://example.com/reviews.xml">
                            </div>
                            <div class="text-center">
                                <button id="bulk_reviews_form_url_btn" type="submit" class="btn btn-success">
                                    <i class="bi bi-upload me-1"></i><?= __('admin.import_reviews') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Reviews Confirmation Modal -->
<div id="manageBulkReviewsConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.manage_bulk_reviews_confirmation') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:350px; overflow-y:auto;">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success import-reviews-confirm"><?= __('admin.confirm') ?></button>
                <button class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.cancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Reviews Result Modal -->
<div id="manageBulkReviewsResult" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('admin.manage_bulk_reviews_result') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:350px; overflow-y:auto;">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="window.location.reload()"><?= __('admin.ok') ?></button>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    // Initialize Select2 for product filter
    $('#product_name_review').select2({
        placeholder: '<?= __('admin.all_product') ?>',
        allowClear: true,
        width: '100%'
    });

    // Load reviews on page load
    getReviews('<?= base_url("admincontrol/listreviews_ajax/") ?>', null);

    // Filter form submission
    $("#filter-form-review").on("submit", function(e) {
        e.preventDefault();
        getReviews('<?= base_url("admincontrol/listreviews_ajax/") ?>', null);
    });

    // Product filter change
    $("#product_name_review").on("change", function() {
        $("#filter-form-review").submit();
    });

    // Pagination click handler
    $("#table-review .pagination-td").on("click", "a", function(e) {
        e.preventDefault();
        getReviews($(this).attr("href"), $(this));
    });

    // Export reviews
    $(".export-reviews-btn").on("click", function() {
        exportReviews($(this));
    });

    // Bulk import handlers
    $('#bulk_reviews_form_btn').on('click', function(e) { 
        e.preventDefault();
        handleBulkReviewsImport('file');
    });

    $('#bulk_reviews_form_url_btn').on('click', function(e) { 
        e.preventDefault();
        handleBulkReviewsImport('url');
    });

    // Bulk export handlers
    $(document).on('click', '.export-reviews-xml-btn', function() {
        exportReviewXML($(this), 0);
    });
    
    $(document).on('click', '.export-reviews-structure-xml-btn', function() {
        exportReviewXML($(this), 1);
    });

    // Confirmation handler
    $('#manageBulkReviewsConfirmation .import-reviews-confirm').on('click', function(e) {
        e.preventDefault(); 
        confirmBulkReviewsImport($(this));
    });

    // Delete review handler (delegated)
    $(document).on('click', '.delete-review-btn', function(e) {
        e.preventDefault();
        if (confirm('<?= __('admin.are_you_sure') ?>')) {
            deleteReview($(this).data('id'), $(this));
        }
    });
});

var isLoadingReviews = false;

function getReviews(url, $triggerElement) {
    if (isLoadingReviews) return false;
    
    isLoadingReviews = true;
    
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: $("#filter-form-review").serialize(),
        beforeSend: function() {
            if ($triggerElement && $triggerElement.length) {
                $triggerElement.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
            } else {
                $("#reviews-tbody").html(`
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                                </div>
                                <p class="text-muted"><?= __('admin.loading') ?> <?= __('admin.reviews') ?>...</p>
                            </div>
                        </td>
                    </tr>
                `);
            }
        },
        complete: function() {
            isLoadingReviews = false;
            if ($triggerElement && $triggerElement.length) {
                $triggerElement.prop('disabled', false).html($triggerElement.data('original-text') || $triggerElement.text());
            }
        },
        success: function(json) {
            if (json['view']) {
                $("#reviews-tbody").html(json['view']);
                $("#total_review").text(json['total'] || 0);
                $("#table-review").show();
            } else {
                $("#reviews-tbody").html(`
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <i class="bi bi-star display-1 text-muted mb-3"></i>
                                <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                                <p class="text-muted"><?= __('admin.no_reviews_found') ?></p>
                            </div>
                        </td>
                    </tr>
                `);
                $("#total_review").text('0');
            }
            
            $("#table-review .pagination-td").html(json['pagination'] || '');
        },
        error: function(xhr, status, error) {
            console.error('Reviews loading error:', error);
            isLoadingReviews = false;
            $("#reviews-tbody").html(`
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <i class="bi bi-exclamation-triangle display-1 text-danger mb-3"></i>
                            <h4 class="text-danger mb-2"><?= __('admin.error') ?></h4>
                            <p class="text-muted"><?= __('admin.failed_to_load_data') ?></p>
                            <button class="btn btn-primary mt-2" onclick="getReviews('<?= base_url("admincontrol/listreviews_ajax/") ?>', null)">
                                <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.retry') ?>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error', 5000);
            }
        }
    });
}

function resetFilters() {
    $('#product_name_review').val('').trigger('change');
    $("#filter-form-review").submit();
}

function exportReviews($btn) {
    $.ajax({
        url: '<?= base_url("admincontrol/exportReviews/") ?>',
        type: 'POST',
        dataType: 'json',
        beforeSend: function() {
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $btn.prop('disabled', false).html('<i class="bi bi-download me-1"></i><?= __('admin.export') ?>');
        },
        success: function(json) {
            if (json['download']) {
                window.location.href = json['download'];
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.success') ?>', '<?= __('admin.export_completed') ?>', 'success', 3000);
                }
            }
        },
        error: function() {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.export_failed') ?>', 'error', 5000);
            }
        }
    });
}

function handleBulkReviewsImport(type) {
    const formId = type === 'file' ? 'bulk_reviews_form' : 'bulk_reviews_form_url';
    const btnId = type === 'file' ? 'bulk_reviews_form_btn' : 'bulk_reviews_form_url_btn';
    const inputCheck = type === 'file' ? '#bulk_reviews_form input[name="file"]' : '#txt_review_xmlurl';
    const url = type === 'file' ? 
        '<?= base_url('admincontrol/bulkReviewsImport') ?>' : 
        '<?= base_url('admincontrol/bulkReviewImportFromUrl') ?>';
    
    $(`#${formId} .alert-danger`).remove();
    
    if (!$(inputCheck).val()) {
        const errorMsg = type === 'file' ? 
            '<?= __('admin.please_select_xml_file') ?>' : 
            '<?= __('admin.please_enter_xml_url') ?>';
        $(inputCheck).closest('.mb-3').after(`<div class="alert alert-danger">${errorMsg}</div>`);
        return;
    }
    
    const $btn = $(`#${btnId}`);
    const fd = new FormData(document.getElementById(formId));
    
    $.ajax({
        url: url,
        type: 'POST',
        data: fd,
        dataType: 'html',
        beforeSend: function() {
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
        },
        complete: function() { 
            $btn.prop('disabled', false).html($btn.data('original-text') || $btn.text());
            $('#manageBulkReviews').modal('hide');
        },
        success: function(response) {               
            $('#manageBulkReviewsConfirmation .modal-body').html(response);
            $('#manageBulkReviewsConfirmation').modal('show');
            
            if ($('#manageBulkReviewsConfirmation textarea[name="reviews_for_import"]').length > 0) {
                $('#manageBulkReviewsConfirmation .import-reviews-confirm').show();  
            } else {
                $('#manageBulkReviewsConfirmation .import-reviews-confirm').hide();  
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function() {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.import_failed') ?>', 'error', 5000);
            }
        }
    });
}

function confirmBulkReviewsImport($btn) {
    if (!$('#manageBulkReviewsConfirmation textarea[name="reviews_for_import"]').val()) {
        return;
    }
    
    const data = new FormData();
    data.append('reviews', $('#manageBulkReviewsConfirmation textarea[name="reviews_for_import"]').val());
    
    $.ajax({
        url: '<?= base_url('admincontrol/bulkReviewImportConfirm') ?>',
        type: 'POST',
        data: data,
        beforeSend: function() {
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $btn.prop('disabled', false).html($btn.data('original-text') || $btn.text());
            $('#manageBulkReviewsConfirmation').modal('hide');
        },
        success: function(response) {               
            $('#manageBulkReviewsResult .modal-body').html(response);
            $('#manageBulkReviewsResult').modal('show');
            // Refresh reviews list
            getReviews('<?= base_url("admincontrol/listreviews_ajax/") ?>', null);
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function() {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.import_failed') ?>', 'error', 5000);
            }
        }
    });
}

function exportReviewXML($btn, structure_only = 0) {
    $.ajax({
        url: '<?= base_url("admincontrol/exportReviewXML/") ?>',
        type: 'POST',
        dataType: 'json',
        data: { structure_only: structure_only },
        beforeSend: function() {
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
        },
        complete: function() {
            $btn.prop('disabled', false).html($btn.data('original-text') || $btn.text());
        },
        success: function(json) {
            if (json['download']) {
                if (structure_only == 0) {
                    window.location.href = '<?= base_url('admincontrol/downloadproductreviewxmlfile') ?>'; 
                } else {
                    window.location.href = '<?= base_url('admincontrol/downloadproductreviewxmlstructurefile') ?>';   
                }
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.success') ?>', '<?= __('admin.export_completed') ?>', 'success', 3000);
                }
            }
        },
        error: function() {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.export_failed') ?>', 'error', 5000);
            }
        }
    });
}

function deleteReview(reviewId, $btn) {
    $.ajax({
        url: '<?= base_url("admincontrol/deleteReview/") ?>' + reviewId,
        type: 'POST',
        beforeSend: function() {
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
        },
        success: function(response) {
            // Fade out the row
            $btn.closest('tr').fadeOut(300, function() {
                $(this).remove();
                // Update total count
                const currentTotal = parseInt($('#total_review').text()) || 0;
                $('#total_review').text(Math.max(0, currentTotal - 1));
            });
            
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.success') ?>', '<?= __('admin.review_deleted_successfully') ?>', 'success', 3000);
            }
        },
        error: function() {
            $btn.prop('disabled', false).html('<i class="bi bi-trash"></i>');
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.delete_failed') ?>', 'error', 5000);
            }
        }
    });
}
</script>			