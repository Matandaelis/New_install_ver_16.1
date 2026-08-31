<div class="container-fluid px-4 pb-4">
<?php $this->load->view('admincontrol/store/_store_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-sitemap me-2"></i>
                        Sales Funnels Management
                    </h3>
                    <p class="text-muted mb-0">Configure upsell sequences for your sales products</p>
                </div>
                <div class="card-body">
                    <?php if (empty($sales_products)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No sales products found. Create some sales products first to configure funnels.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-cogs me-2"></i>
                                            Configure Funnel
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="funnelConfigForm">
                                            <div class="mb-4">
                                                <label for="frontend_product_id" class="form-label fw-bold">
                                                    <i class="fas fa-shopping-cart me-1 text-primary"></i>
                                                    Frontend Product (Initial Purchase)
                                                </label>
                                                <select class="form-select form-select-lg" id="frontend_product_id" name="frontend_product_id" required>
                                                    <option value="">Select a frontend product...</option>
                                                    <?php foreach ($sales_products as $product): ?>
                                                        <option value="<?= $product->product_id ?>" 
                                                                data-price="<?= $product->product_price ?>"
                                                                data-name="<?= htmlspecialchars($product->product_name) ?>"
                                                                data-sku="<?= $product->product_sku ?>">
                                                            <?= $product->product_name ?> - $<?= $product->product_price ?> (<?= $product->product_sku ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="form-text">This is the product customers buy first in your funnel</div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-layer-group me-1 text-success"></i>
                                                    Available Products (Click to Add as Upsell)
                                                </label>
                                                <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                                                    <div id="availableProducts">
                                                        <?php foreach ($sales_products as $product): ?>
                                                            <div class="available-product p-2 mb-2 bg-white border rounded cursor-pointer" 
                                                                 data-id="<?= $product->product_id ?>"
                                                                 data-name="<?= htmlspecialchars($product->product_name) ?>"
                                                                 data-price="<?= $product->product_price ?>"
                                                                 onclick="addToUpsells(<?= $product->product_id ?>, '<?= htmlspecialchars($product->product_name) ?>', '<?= $product->product_price ?>')">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <strong><?= $product->product_name ?></strong>
                                                                        <br>
                                                                        <small class="text-muted">$<?= $product->product_price ?></small>
                                                                    </div>
                                                                    <i class="fas fa-plus-circle text-success"></i>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="form-text">Click on products to add them to the upsell sequence</div>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-save me-2"></i>
                                                    Save Funnel Configuration
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-route me-2"></i>
                                            Upsell Sequence Flow
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="funnelPreview">
                                            <div class="text-center text-muted py-5">
                                                <i class="fas fa-arrow-down fa-3x mb-3 opacity-25"></i>
                                                <p class="mb-0">Select a frontend product to start building your funnel</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-question-circle me-2"></i>
                                            How It Works
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="badge bg-primary rounded-circle me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">1</div>
                                            <div>
                                                <strong>Customer buys Frontend Product</strong>
                                                <p class="text-muted mb-0 small">This is the initial purchase that starts the funnel</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="badge bg-success rounded-circle me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">2</div>
                                            <div>
                                                <strong>Thank you page shows First Upsell</strong>
                                                <p class="text-muted mb-0 small">Customer sees the first upsell offer with exclusive pricing</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="badge bg-info rounded-circle me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">3</div>
                                            <div>
                                                <strong>Customer buys or declines</strong>
                                                <p class="text-muted mb-0 small">If they buy, it's added to their order. If they decline, move to next</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="badge bg-warning rounded-circle me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">4</div>
                                            <div>
                                                <strong>Process continues for all upsells</strong>
                                                <p class="text-muted mb-0 small">Each upsell is shown in sequence until all are presented</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-list me-2"></i>
                                            Current Funnel Configurations
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($funnel_configs)): ?>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                No funnel configurations yet. Create your first funnel above.
                                            </div>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="30%">Frontend Product</th>
                                                            <th width="60%">Upsell Sequence</th>
                                                            <th width="10%">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($funnel_configs as $frontend_id => $upsell_ids): ?>
                                                            <?php 
                                                            $frontend_product = null;
                                                            foreach ($sales_products as $product) {
                                                                if ($product->product_id == $frontend_id) {
                                                                    $frontend_product = $product;
                                                                    break;
                                                                }
                                                            }
                                                            if (!$frontend_product) continue;
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="badge bg-primary me-2">1</div>
                                                                        <div>
                                                                            <strong><?= $frontend_product->product_name ?></strong><br>
                                                                            <small class="text-muted">$<?= $frontend_product->product_price ?> (<?= $frontend_product->product_sku ?>)</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <?php if (empty($upsell_ids)): ?>
                                                                        <span class="text-muted">No upsells configured</span>
                                                                    <?php else: ?>
                                                                        <div class="d-flex flex-wrap gap-2">
                                                                            <?php foreach ($upsell_ids as $index => $upsell_id): ?>
                                                                                <?php 
                                                                                $upsell_product = null;
                                                                                foreach ($sales_products as $product) {
                                                                                    if ($product->product_id == $upsell_id) {
                                                                                        $upsell_product = $product;
                                                                                        break;
                                                                                    }
                                                                                }
                                                                                if ($upsell_product):
                                                                                ?>
                                                                                    <div class="badge bg-success p-2">
                                                                                        <span class="badge bg-white text-success me-1"><?= $index + 2 ?></span>
                                                                                        <?= $upsell_product->product_name ?> - $<?= $upsell_product->product_price ?>
                                                                                    </div>
                                                                                    <?php if ($index < count($upsell_ids) - 1): ?>
                                                                                        <i class="fas fa-arrow-right text-muted"></i>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-primary" 
                                                                            onclick="editFunnel(<?= $frontend_id ?>, <?= htmlspecialchars(json_encode($upsell_ids)) ?>)">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.available-product {
    cursor: pointer;
    transition: all 0.2s;
}
.available-product:hover {
    background-color: #e7f5ff !important;
    border-color: #0d6efd !important;
    transform: translateX(5px);
}
.funnel-step {
    position: relative;
    padding: 20px;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    margin-bottom: 15px;
    transition: all 0.3s;
}
.funnel-step:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.funnel-step.frontend {
    border-color: #0d6efd;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.funnel-step.upsell {
    border-color: #198754;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}
.step-number {
    position: absolute;
    top: -15px;
    left: 20px;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.step-arrow {
    text-align: center;
    color: #6c757d;
    font-size: 24px;
    margin: 10px 0;
}
.remove-upsell {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    background: white;
    color: #dc3545;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.remove-upsell:hover {
    background: #dc3545;
    color: white;
    transform: scale(1.1);
}
</style>

<script>
let selectedUpsells = [];

document.addEventListener('DOMContentLoaded', function() {
    const frontendSelect = document.getElementById('frontend_product_id');
    const previewDiv = document.getElementById('funnelPreview');
    const form = document.getElementById('funnelConfigForm');

    if (!frontendSelect || !previewDiv || !form) {
        console.warn('Sales funnels: no products configured yet — form elements skipped.');
        return;
    }

    frontendSelect.addEventListener('change', updatePreview);

    function updatePreview() {
        const frontendOption = frontendSelect.selectedOptions[0];
        
        if (!frontendOption || !frontendOption.value) {
            previewDiv.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-arrow-down fa-3x mb-3 opacity-25"></i>
                    <p class="mb-0">Select a frontend product to start building your funnel</p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="funnel-step frontend">
                <div class="step-number bg-primary text-white">1</div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><i class="fas fa-shopping-cart me-2"></i>Frontend Product</h5>
                        <div class="fw-bold">${frontendOption.dataset.name}</div>
                        <small>$${frontendOption.dataset.price}</small>
                    </div>
                </div>
            </div>
        `;

        if (selectedUpsells.length > 0) {
            selectedUpsells.forEach((upsell, index) => {
                html += `
                    <div class="step-arrow">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="funnel-step upsell">
                        <div class="step-number bg-success text-white">${index + 2}</div>
                        <button type="button" class="remove-upsell" onclick="removeUpsell(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        <div>
                            <h5 class="mb-1"><i class="fas fa-gift me-2"></i>Upsell ${index + 1}</h5>
                            <div class="fw-bold">${upsell.name}</div>
                            <small>$${upsell.price}</small>
                        </div>
                    </div>
                `;
            });
        } else {
            html += `
                <div class="step-arrow">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Click on products from the left to add them as upsells
                </div>
            `;
        }

        previewDiv.innerHTML = html;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const frontendId = frontendSelect.value;
        
        if (!frontendId) {
            showToast('error', 'Please select a frontend product');
            return;
        }

        const formData = new FormData();
        formData.append('frontend_product_id', frontendId);
        
        selectedUpsells.forEach(upsell => {
            formData.append('upsell_product_ids[]', upsell.id);
        });

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        submitBtn.disabled = true;

        fetch('<?= base_url('admincontrol/save_funnel_config') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast('error', data.message);
            }
        })
        .catch(error => {
            showToast('error', 'An error occurred while saving');
            console.error('Error:', error);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    updatePreview();
});

function addToUpsells(id, name, price) {
    const frontendSelect = document.getElementById('frontend_product_id');
    const frontendId = frontendSelect.value;
    
    if (!frontendId) {
        showToast('warning', 'Please select a frontend product first');
        return;
    }
    
    if (id == frontendId) {
        showToast('warning', 'Cannot add the frontend product as an upsell');
        return;
    }
    
    if (selectedUpsells.find(u => u.id == id)) {
        showToast('info', 'This product is already in the upsell sequence');
        return;
    }
    
    selectedUpsells.push({ id, name, price });
    document.getElementById('frontend_product_id').dispatchEvent(new Event('change'));
    showToast('success', 'Upsell added to sequence');
}

function removeUpsell(index) {
    selectedUpsells.splice(index, 1);
    document.getElementById('frontend_product_id').dispatchEvent(new Event('change'));
    showToast('info', 'Upsell removed from sequence');
}

function editFunnel(frontendId, upsellIds) {
    document.getElementById('frontend_product_id').value = frontendId;
    
    selectedUpsells = [];
    
    if (upsellIds && upsellIds.length > 0) {
        const availableProducts = document.querySelectorAll('.available-product');
        upsellIds.forEach(id => {
            availableProducts.forEach(product => {
                if (product.dataset.id == id) {
                    selectedUpsells.push({
                        id: product.dataset.id,
                        name: product.dataset.name,
                        price: product.dataset.price
                    });
                }
            });
        });
    }
    
    document.getElementById('frontend_product_id').dispatchEvent(new Event('change'));
    
    document.getElementById('funnelConfigForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>