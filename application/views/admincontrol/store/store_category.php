<div class="container-fluid px-4 pb-4 store-category-page">
<?php get_instance()->load->view('admincontrol/store/_store_nav'); ?>
<div class="row">
<div class="col-12">

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-tags-fill me-2"></i>
                <h5 class="mb-0 fw-semibold"><?= __('admin.store_categories') ?></h5>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-light btn-sm" onclick="refreshCategories()">
                    <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>
                </button>
                <a class="btn btn-light btn-sm" href="<?php echo base_url("admincontrol/store_category_add") ?>">
                    <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_category') ?>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-4">
            <div class="bg-light rounded-3 p-3 mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-medium small">
                            <i class="bi bi-search me-1"></i><?= __('admin.search_categories') ?>
                        </label>
                        <input type="text" id="category-search" class="form-control form-control-sm" placeholder="<?= __('admin.search_by_name') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium small">
                            <i class="bi bi-funnel me-1"></i><?= __('admin.filter_by_parent') ?>
                        </label>
                        <select id="parent-filter" class="form-select form-select-sm">
                            <option value=""><?= __('admin.all_categories') ?></option>
                            <option value="0"><?= __('admin.root_categories') ?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center h-100">
                            <div class="btn btn-outline-info btn-sm">
                                <i class="bi bi-tags me-1"></i><?= __('admin.total') ?>: 
                                <span id="total_categories" class="badge bg-info ms-1">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-1 justify-content-end">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                <i class="bi bi-x-circle me-1"></i><?= __('admin.clear') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="categories-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold text-center" style="width: 80px;"><?= __('admin.image') ?></th>
                        <th class="fw-semibold" style="width: 60px;"><?= __('admin.id') ?></th>
                        <th class="fw-semibold"><?= __('admin.category_name') ?></th>
                        <th class="fw-semibold"><?= __('admin.parent_category') ?></th>
                        <th class="fw-semibold text-center" style="width: 120px;"><?= __('admin.products') ?></th>
                        <th class="fw-semibold" style="width: 140px;"><?= __('admin.created_date') ?></th>
                        <th class="fw-semibold text-center" style="width: 140px;"><?= __('admin.actions') ?></th>
                    </tr>
                </thead>
                <tbody id="categories-tbody">
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                                </div>
                                <p class="text-muted"><?= __('admin.loading') ?> <?= __('admin.categories') ?>...</p>
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

<!-- Product List Modal -->
<div id="productListModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-box-seam me-2"></i><?= __('admin.category_products') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <div class="text-center py-4">
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

<script type="text/javascript">
$(document).ready(function() {
    let searchTimeout;
    let isLoadingCategories = false;
    let currentPage = 1;
    let totalCategories = 0;

    // Load categories on page load
    loadCategories(1);

    // Search functionality with debounce
    $('#category-search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            currentPage = 1;
            loadCategories(1);
        }, 500);
    });

    // Parent filter change
    $('#parent-filter').on('change', function() {
        currentPage = 1;
        loadCategories(1);
    });

    // Pagination click handler
    $("#categories-table .pagination-td").on("click", "a", function(e) {
        e.preventDefault();
        const page = $(this).attr("data-ci-pagination-page") || 1;
        loadCategories(page, $(this));
    });

    // Product category click handler (delegated)
    $(document).on('click', '[product-category]', function(e) {
        e.preventDefault();
        const $this = $(this);
        const categoryId = $this.attr("product-category");
        const categoryName = $this.data('category-name') || 'Category';
        
        showProductList(categoryId, categoryName, $this);
    });

    // Delete category handler (delegated)
    $(document).on('click', '.delete-category-btn', function(e) {
        e.preventDefault();
        if (confirm('<?= __('admin.are_you_sure') ?>')) {
            const categoryId = $(this).data('id');
            deleteCategory(categoryId, $(this));
        }
    });

    // Copy category handler (delegated)
    $(document).on('click', '.copy-category-btn', function(e) {
        e.preventDefault();
        const $btn      = $(this);
        const categoryId   = $btn.data('id');
        const categoryName = $btn.data('name');
        if (!confirm('<?= __('admin.copy_category_confirm') ?>\n"' + categoryName + '"?')) return;
        copyCategory(categoryId, $btn);
    });

    function loadCategories(page = 1, $triggerElement = null) {
        if (isLoadingCategories) return false;
        
        isLoadingCategories = true;
        currentPage = page;
        
        const searchTerm = $('#category-search').val();
        const parentFilter = $('#parent-filter').val();
        
        const data = {
            page: page,
            search: searchTerm,
            parent_id: parentFilter
        };
        
        $.ajax({
            url: '<?= base_url("admincontrol/store_category") ?>/' + page,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                if ($triggerElement && $triggerElement.length) {
                    $triggerElement.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
                } else {
                    $("#categories-tbody").html(`
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="spinner-border text-primary mb-3" role="status">
                                        <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                                    </div>
                                    <p class="text-muted"><?= __('admin.loading') ?> <?= __('admin.categories') ?>...</p>
                                </div>
                            </td>
                        </tr>
                    `);
                }
            },
            complete: function() {
                isLoadingCategories = false;
                if ($triggerElement && $triggerElement.length) {
                    $triggerElement.prop('disabled', false).html($triggerElement.data('original-text') || $triggerElement.text());
                }
            },
            success: function(json) {
                if (json['html']) {
                    $("#categories-tbody").html(json['html']);
                    totalCategories = json['total'] || 0;
                    $("#total_categories").text(totalCategories);
                    $("#categories-table").show();
                } else {
                    $("#categories-tbody").html(`
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <i class="bi bi-tags display-1 text-muted mb-3"></i>
                                    <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                                    <p class="text-muted"><?= __('admin.no_categories_found') ?></p>
                                    <a href="<?= base_url('admincontrol/store_category_add') ?>" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_category') ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `);
                    $("#total_categories").text('0');
                }
                
                $("#categories-table .pagination-td").html(json['pagination'] || '');
            },
            error: function(xhr, status, error) {
                console.error('Categories loading error:', error);
                isLoadingCategories = false;
                $("#categories-tbody").html(`
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <i class="bi bi-exclamation-triangle display-1 text-danger mb-3"></i>
                                <h4 class="text-danger mb-2"><?= __('admin.error') ?></h4>
                                <p class="text-muted"><?= __('admin.failed_to_load_data') ?></p>
                                <button class="btn btn-primary mt-2" onclick="loadCategories(${currentPage})">
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

    function showProductList(categoryId, categoryName, $triggerElement) {
        const data = { category_id: categoryId };
        
        $.ajax({
            url: '<?= base_url('admincontrol/product_logs') ?>',
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                $triggerElement.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
                $('#productListModal .modal-title').html(`<i class="bi bi-box-seam me-2"></i>${categoryName} - <?= __('admin.products') ?>`);
                $('#productListModal .modal-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                        </div>
                        <p class="text-muted mt-2"><?= __('admin.loading') ?> <?= __('admin.products') ?>...</p>
                    </div>
                `);
                $('#productListModal').modal('show');
            },
            complete: function() {
                $triggerElement.prop('disabled', false).html($triggerElement.data('original-text') || $triggerElement.text());
            },
            success: function(json) {
                if (json['html']) {
                    $('#productListModal .modal-body').html(json['html']);
                } else {
                    $('#productListModal .modal-body').html(`
                        <div class="text-center py-4">
                            <i class="bi bi-box-seam display-1 text-muted mb-3"></i>
                            <h5 class="text-muted"><?= __('admin.no_products_found') ?></h5>
                            <p class="text-muted"><?= __('admin.no_products_in_category') ?></p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#productListModal .modal-body').html(`
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle display-1 text-danger mb-3"></i>
                        <h5 class="text-danger"><?= __('admin.error') ?></h5>
                        <p class="text-muted"><?= __('admin.failed_to_load_products') ?></p>
                    </div>
                `);
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_products') ?>', 'error', 5000);
                }
            }
        });
    }

    function copyCategory(categoryId, $btn) {
        const originalHtml = $btn.html();
        $.ajax({
            url: '<?= base_url("admincontrol/store_category_copy/") ?>' + categoryId,
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            },
            success: function(res) {
                if (res.success) {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', res.message || '<?= __('admin.category_copied_successfully') ?>', 'success', 3000);
                    }
                    // Update total count and reload table to show the new copy
                    const currentTotal = parseInt($('#total_categories').text()) || 0;
                    $('#total_categories').text(currentTotal + 1);
                    loadCategories(currentPage);
                } else {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', res.error || '<?= __('admin.copy_failed') ?>', 'error', 5000);
                    }
                }
            },
            error: function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.copy_failed') ?>', 'error', 5000);
                }
            }
        });
    }

    function deleteCategory(categoryId, $btn) {
        $.ajax({
            url: '<?= base_url("admincontrol/store_category_delete/") ?>' + categoryId,
            type: 'GET',
            beforeSend: function() {
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            },
            success: function(response) {
                // Fade out the row
                $btn.closest('tr').fadeOut(300, function() {
                    $(this).remove();
                    // Update total count
                    const currentTotal = parseInt($('#total_categories').text()) || 0;
                    $('#total_categories').text(Math.max(0, currentTotal - 1));
                });
                
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.success') ?>', '<?= __('admin.category_deleted_successfully') ?>', 'success', 3000);
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

    // Global functions
    window.refreshCategories = function() {
        loadCategories(currentPage);
    };

    window.clearFilters = function() {
        $('#category-search').val('');
        $('#parent-filter').val('');
        currentPage = 1;
        loadCategories(1);
    };

    window.loadCategories = loadCategories;
});
</script>