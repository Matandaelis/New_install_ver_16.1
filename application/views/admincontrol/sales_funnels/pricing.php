<div class="container-fluid px-4 pb-4">
<?php $this->load->view('admincontrol/store/_store_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-tags me-2"></i>
                        Sales Funnel Pricing
                    </h4>
                    <p class="mb-0 mt-2">Set exclusive pricing for products when they appear in sales funnels</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Regular Price</th>
                                    <th>Funnel Price</th>
                                    <th>Discount</th>
                                    <th>You Save</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sales_products)): ?>
                                    <?php foreach ($sales_products as $product): ?>
                                    <tr data-product-id="<?= $product->product_id ?>">
                                        <td>
                                            <strong><?= htmlspecialchars($product->product_name) ?></strong>
                                            <br>
                                            <small class="text-muted">SKU: <?= htmlspecialchars($product->product_sku) ?></small>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?= c_format($product->product_price) ?></span>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm" style="max-width: 150px;">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control funnel-price-input" 
                                                       value="<?= $product->funnel_price ?>" 
                                                       step="0.01" min="0"
                                                       data-regular-price="<?= $product->product_price ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success discount-badge">
                                                <?= $product->discount_percent ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold savings-amount">
                                                <?= c_format($product->product_price - $product->funnel_price) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm save-pricing-btn" 
                                                    data-product-id="<?= $product->product_id ?>">
                                                <i class="fas fa-save"></i>
                                                Save
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <br>
                                            No sales products found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.save-pricing-btn').on('click', function() {
        const productId = $(this).data('product-id');
        const row = $(this).closest('tr');
        const funnelPrice = row.find('.funnel-price-input').val();
        const btn = $(this);
        
        if (!funnelPrice || funnelPrice < 0) {
            showToast('Please enter a valid price', 'error');
            return;
        }
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: '<?= base_url('admincontrol/save_funnel_pricing') ?>',
            type: 'POST',
            data: {
                product_id: productId,
                funnel_price: funnelPrice
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save');
            },
            error: function() {
                showToast('An error occurred', 'error');
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save');
            }
        });
    });
    
    $('.funnel-price-input').on('input', function() {
        const row = $(this).closest('tr');
        const regularPrice = parseFloat($(this).data('regular-price'));
        const funnelPrice = parseFloat($(this).val()) || 0;
        
        if (regularPrice > 0 && funnelPrice >= 0) {
            const discount = regularPrice > 0 ? Math.round(((regularPrice - funnelPrice) / regularPrice) * 100) : 0;
            const savings = regularPrice - funnelPrice;
            
            row.find('.discount-badge').text(discount + '%');
            row.find('.savings-amount').text('$' + savings.toFixed(2));
        }
    });
});
</script>
