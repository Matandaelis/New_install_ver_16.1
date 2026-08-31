<div class="container-fluid px-4 pb-4">
<?php get_instance()->load->view('admincontrol/store/_store_nav'); ?>
<div class="row">
<div class="col-12">

<?php if ($currentTheme=="cart" ||$StoreStatus=="0"){?>
<div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <div><?= __('admin.sales_product_notice') ?></div>
</div>
<?php } ?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-graph-up me-2"></i>
                <h5 class="mb-0 fw-semibold"><?= __('admin.sales_mode_products') ?></h5>
            </div>
            <a href="<?php echo base_url('Productsales/create') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_sale_page_product') ?>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        
        <!-- Filter Section -->
        <div class="bg-light border-bottom p-4">
            <form id="filter-form">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-medium"><?= __('admin.vendor') ?></label>
                        <select name="seller_id" class="form-select">
                            <?php $selected = isset($_GET['seller_id']) ? $_GET['seller_id'] : ''; ?>
                            <option value=""><?= __('admin.all_vendor') ?></option>
                            <?php foreach ($vendors as $key => $value) { ?>
                                <option <?= $selected == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i><?= __('admin.search') ?>
                        </button>
                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="resetFilters()">
                            <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.reset') ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="p-3 border-bottom bg-white">
            <button style="display:none;" type="button" class="btn btn-danger btn-sm" name="deletebutton" id="deletebutton" onclick="deleteuserlistfunc('deleteAllproducts');">
                <i class="bi bi-trash me-1"></i><?= __('admin.delete_products') ?>
            </button>
        </div>

        <!-- Content Area -->
        <div class="p-4">
            <!-- Empty State -->
            <div class="empty-state text-center py-5" style="display: none;">
                <div class="mb-4">
                    <i class="bi bi-graph-up text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                <p class="text-muted mb-4"><?= __('admin.no_products_message') ?></p>
                <a href="<?php echo base_url('Productsales/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_sale_page_product') ?>
                </a>
            </div>

            <!-- Products Table -->
            <div class="table-responsive">
                <form method="post" name="deleteAllproducts" id="deleteAllproducts" action="<?php echo base_url('Productsales/delete'); ?>">
                    <table id="campaign-products-table" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" name="product[]" type="checkbox" value="" onclick="checkAll(this)" id="selectAll">
                                        <label class="form-check-label" for="selectAll"></label>
                                    </div>
                                </th>
                                <th width="300" class="fw-medium"><?= __('admin.product_info') ?></th>
                                <th width="100" class="fw-medium"><?= __('admin.vendor') ?></th>
                                <th width="90" class="fw-medium"><?= __('admin.price') ?></th>
                                <th width="100" class="fw-medium"><?= __('admin.sku') ?></th>
                                <th width="200" class="fw-medium"><?= __('admin.commission') ?></th>
                                <th width="120" class="fw-medium"><?= __('admin.sales') ?></th>
                                <th width="120" class="fw-medium"><?= __('admin.clicks') ?></th>
                                <th width="100" class="fw-medium"><?= __('admin.total') ?></th>
                                <th width="120" class="fw-medium"><?= __('admin.status') ?></th>
                                <th width="140" class="fw-medium"><?= __('admin.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="products-tbody">
                            <!-- Products will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </form>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light">
                <div class="pagination-summary text-muted small">
                    <!-- Pagination summary will be loaded here -->
                </div>
                <div class="pagination-wrapper">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>

<!-- Integration Code Modal -->
<div class="modal fade" id="showcode-code" tabindex="-1" aria-labelledby="showcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>

<!-- Custom Popup (Legacy Support) -->
<div id="overlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" style="display: none; z-index: 9998;"></div>
<div class="popupbox position-fixed top-50 start-50 translate-middle" style="display: none; z-index: 9999; width: 90%; max-width: 600px;">
    <div class="backdrop box">
        <div class="modalpopup bg-white rounded shadow">
            <div class="modal-header border-bottom p-3">
                <h5 class="modal-title"><?= __('admin.integration_code') ?></h5>
                <button type="button" class="btn-close" onclick="closePopup()" aria-label="Close"></button>
            </div>
            <div class="modalpopup-body p-3">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">


$(document).ready(function() {
    let isLoading = false;

    // Check all functionality
    window.checkAll = function(bx) {
        const checkboxes = document.querySelectorAll('.list-checkbox');
        const deleteBtn = document.getElementById('deletebutton');
        
        checkboxes.forEach(cb => cb.checked = bx.checked);
        
        if (bx.checked && checkboxes.length > 0) {
            deleteBtn.style.display = 'block';
        } else {
            deleteBtn.style.display = 'none';
        }
    };

    // Individual checkbox check
    window.checkonly = function(bx, checkid) {
        const checkedBoxes = document.querySelectorAll('.list-checkbox:checked');
        const deleteBtn = document.getElementById('deletebutton');
        
        if (checkedBoxes.length > 0) {
            deleteBtn.style.display = 'block';
        } else {
            deleteBtn.style.display = 'none';
        }
    };

    // Delete function
    window.deleteuserlistfunc = function(formId) {
        if (!confirm("<?= __('admin.are_you_sure') ?>")) return false;
        document.getElementById(formId).submit();
    };

    // Reset filters function
    window.resetFilters = function() {
        document.getElementById('filter-form').reset();
        loadProducts('<?= base_url("Productsales/listproduct_ajax/") ?>/1');
    };

    // Load products function
    window.loadProducts = function(url) {
        if (isLoading) return;
        
        isLoading = true;
        const submitBtn = document.querySelector('#filter-form button[type="submit"]');
        
        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...';
        }

        // Clear and show table loading
        const tbody = document.getElementById('products-tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="11" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                    </div>
                    <div class="mt-2 text-muted"><?= __('admin.loading_products') ?>...</div>
                </td>
            </tr>
        `;

        // Clear existing content first
        $('#products-tbody').empty();

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $("#filter-form").serialize(),
            success: function(json) {
                // Clear any existing content first
                $('#products-tbody').empty();
                
                if (json.view && json.view.trim() !== '') {
                    // Show table and hide empty state
                    $('.table-responsive').show();
                    $('.empty-state').hide();
                    $('#products-tbody').html(json.view);
                    
                    // Update pagination
                    if (json.pagination) {
                        $('.pagination-wrapper').html(json.pagination);
                    }
                    
                    // Update pagination summary
                    if (json.pagination_summary) {
                        $('.pagination-summary').html(json.pagination_summary);
                    }
                } else {
                    // Show empty state and hide table
                    $('.table-responsive').hide();
                    $('.empty-state').show();
                    $('.pagination-wrapper').html('');
                    $('.pagination-summary').html('');
                }
            },
            error: function() {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error', 4000);
                
                // Clear and show error in table
                $('#products-tbody').empty();
                $('#products-tbody').html(`
                    <tr>
                        <td colspan="11" class="text-center py-5 text-danger">
                            <i class="bi bi-exclamation-triangle fs-1 mb-3"></i>
                            <div><?= __('admin.failed_to_load_data') ?></div>
                            <button class="btn btn-outline-primary btn-sm mt-2" onclick="loadProducts('<?= base_url("Productsales/listproduct_ajax/") ?>/1')">
                                <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.retry') ?>
                            </button>
                        </td>
                    </tr>
                `);
            },
            complete: function() {
                isLoading = false;
                
                // Reset button state
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-search me-1"></i><?= __('admin.search') ?>';
                }
            }
        });
    };

    // Form submission
    $("#filter-form").on("submit", function(e) {
        e.preventDefault();
        loadProducts('<?= base_url("Productsales/listproduct_ajax/") ?>/1');
    });

    // Pagination click handler
    $(document).on('click', '.pagination-wrapper a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            loadProducts(url);
        }
    });

    // Delete product handler
    $(document).on('click', '.delete-product', function(e) {
        e.preventDefault();
        if (!confirm("<?= __('admin.are_you_sure') ?>")) return false;
        
        const productId = $(this).data('id');
        const deleteUrl = $("#deleteAllproducts").attr("action") + "?delete_id=" + productId;
        
        // Show loading state
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
        
        window.location.href = deleteUrl;
    });

    // Integration code modal handler
    $(document).on('click', '.btn-show-code', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const productId = $btn.data('id');
        
        $.ajax({
            url: '<?= base_url("Productsales/integration_code_modal") ?>',
            type: 'POST',
            dataType: 'html',
            data: { id: productId },
            beforeSend: function() {
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...');
            },
            success: function(html) {
                $("#showcode-code").html(html);
                $("#showcode-code").modal("show");
            },
            error: function() {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_code') ?>', 'error', 3000);
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="bi bi-code-slash"></i>');
            }
        });
    });

    // Legacy popup functions
    window.closePopup = function() {
        $('.popupbox').hide();
        $('#overlay').hide();
    };

    window.generateCode = function(affiliate_id) {
        $('.popupbox').show();
        $('#overlay').show();
        $('.modalpopup-body').load('<?php echo base_url();?>admincontrol/generateproductcode/' + affiliate_id);
    };

    // Duplicate product functionality
    $(document).on('click', '.duplicate-product', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const productId = $(this).data('id');
        const button = $(this);
        
        // Prevent multiple clicks
        if (button.prop('disabled') || button.hasClass('processing')) {
            return false;
        }
        
        if (confirm('<?= __('admin.confirm_duplicate_product') ?>')) {
            // Show loading state and prevent multiple clicks
            button.prop('disabled', true);
            button.addClass('processing');
            button.html('<i class="bi bi-spinner-border spinner-border-sm"></i>');
            
            $.ajax({
                url: '<?= base_url('Productsales/duplicate_product/') ?>' + productId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        // Reload the products list
                        setTimeout(function() {
                            loadProducts('<?= base_url("Productsales/listproduct_ajax/") ?>/1');
                        }, 1000);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', '<?= __('admin.error_occurred') ?>');
                },
                complete: function() {
                    // Reset button state
                    button.prop('disabled', false);
                    button.removeClass('processing');
                    button.html('<i class="bi bi-files"></i>');
                }
            });
        }
    });

    // Load initial data
    loadProducts('<?= base_url("Productsales/listproduct_ajax/") ?>/1');
});
			</script>			