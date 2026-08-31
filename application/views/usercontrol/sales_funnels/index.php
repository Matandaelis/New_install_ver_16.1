<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-sitemap me-2"></i>
                        <?= __('user.sales_funnels_management') ?>
                    </h3>
                    <p class="text-muted mb-0"><?= __('user.configure_upsell_sequences') ?></p>
                </div>
                <div class="card-body">
                    <?php if (empty($sales_products)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= __('user.no_sales_products_found') ?>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-cogs me-2"></i>
                                            <?= __('user.configure_funnel') ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="funnelConfigForm">
                                            <div class="mb-4">
                                                <label for="frontend_product_id" class="form-label fw-bold">
                                                    <i class="fas fa-shopping-cart me-1 text-primary"></i>
                                                    <?= __('user.frontend_product_initial_purchase') ?>
                                                </label>
                                                <select class="form-select form-select-lg" id="frontend_product_id" name="frontend_product_id" required>
                                                    <option value=""><?= __('user.select_frontend_product') ?></option>
                                                    <?php foreach ($sales_products as $product): ?>
                                                        <option value="<?= $product->product_id ?>" 
                                                                data-price="<?= $product->product_price ?>"
                                                                data-name="<?= htmlspecialchars($product->product_name) ?>"
                                                                data-sku="<?= $product->product_sku ?>">
                                                            <?= $product->product_name ?> - $<?= $product->product_price ?> (<?= $product->product_sku ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="form-text"><?= __('user.frontend_product_description') ?></div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-layer-group me-1 text-success"></i>
                                                    <?= __('user.available_products_click_to_add') ?>
                                                </label>
                                                <div class="border rounded p-3 bg-light overflow-auto" style="max-height:300px">
                                                    <div id="availableProducts">
                                                        <?php foreach ($sales_products as $product): ?>
                                                            <div class="available-product p-2 mb-2 bg-white border rounded" 
                                                                 data-id="<?= $product->product_id ?>"
                                                                 data-name="<?= htmlspecialchars($product->product_name) ?>"
                                                                 data-price="<?= $product->product_price ?>"
                                                                 onclick="addToUpsells(<?= $product->product_id ?>, '<?= htmlspecialchars($product->product_name) ?>', '<?= $product->product_price ?>')"
                                                                 style="cursor:pointer">
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
                                                <div class="form-text"><?= __('user.click_products_to_add_upsells') ?></div>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-save me-2"></i>
                                                    <?= __('user.save_funnel_configuration') ?>
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
                                            <?= __('user.upsell_sequence_flow') ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="funnelPreview">
                                            <div class="text-center text-muted py-5">
                                                <i class="fas fa-arrow-down fa-3x mb-3 opacity-25"></i>
                                                <p class="mb-0"><?= __('user.select_frontend_product_to_start') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-question-circle me-2"></i>
                                            <?= __('user.how_it_works') ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:30px;height:30px">1</div>
                                            <div>
                                                <strong><?= __('user.customer_buys_frontend_product') ?></strong>
                                                <p class="text-muted mb-0 small"><?= __('user.initial_purchase_description') ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="badge bg-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:30px;height:30px">2</div>
                                            <div>
                                                <strong><?= __('user.thank_you_page_shows_first_upsell') ?></strong>
                                                <p class="text-muted mb-0 small"><?= __('user.first_upsell_description') ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="badge bg-info rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:30px;height:30px">3</div>
                                            <div>
                                                <strong><?= __('user.customer_buys_or_declines') ?></strong>
                                                <p class="text-muted mb-0 small"><?= __('user.buy_decline_description') ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="badge bg-warning rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:30px;height:30px">4</div>
                                            <div>
                                                <strong><?= __('user.process_continues_for_all_upsells') ?></strong>
                                                <p class="text-muted mb-0 small"><?= __('user.all_upsells_description') ?></p>
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
                                            <?= __('user.current_funnel_configurations') ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($funnel_configs)): ?>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <?= __('user.no_funnel_configurations_yet') ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="30%"><?= __('user.frontend_product') ?></th>
                                                            <th width="60%"><?= __('user.upsell_sequence') ?></th>
                                                            <th width="10%"><?= __('user.actions') ?></th>
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
                                                                        <span class="text-muted"><?= __('user.no_upsells_configured') ?></span>
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

<script>
let selectedUpsells = [];

document.addEventListener('DOMContentLoaded', function() {
    const frontendSelect = document.getElementById('frontend_product_id');
    const previewDiv = document.getElementById('funnelPreview');
    const form = document.getElementById('funnelConfigForm');

    if (!frontendSelect || !previewDiv || !form) {
        console.error('Required elements not found');
        return;
    }

    frontendSelect.addEventListener('change', updatePreview);

    function updatePreview() {
        const frontendOption = frontendSelect.selectedOptions[0];
        
        if (!frontendOption || !frontendOption.value) {
            previewDiv.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-arrow-down fa-3x mb-3 opacity-25"></i>
                    <p class="mb-0"><?= __('user.select_frontend_product_to_start') ?></p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="position-relative p-4 bg-primary text-white rounded-3 shadow mb-3" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)!important">
                <div class="position-absolute bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="top:-15px;left:20px;width:35px;height:35px;box-shadow:0 2px 8px rgba(0,0,0,0.2)">1</div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><i class="fas fa-shopping-cart me-2"></i><?= __('user.frontend_product') ?></h5>
                        <div class="fw-bold">${frontendOption.dataset.name}</div>
                        <small>$${frontendOption.dataset.price}</small>
                    </div>
                </div>
            </div>
        `;

        if (selectedUpsells.length > 0) {
            selectedUpsells.forEach((upsell, index) => {
                html += `
                    <div class="text-center text-muted my-3" style="font-size:24px">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="position-relative p-4 bg-success text-white rounded-3 shadow mb-3" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%)!important">
                        <div class="position-absolute bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="top:-15px;left:20px;width:35px;height:35px;box-shadow:0 2px 8px rgba(0,0,0,0.2)">${index + 2}</div>
                        <button type="button" class="position-absolute btn btn-sm btn-light text-danger border-0 rounded-circle p-0" onclick="removeUpsell(${index})" style="top:10px;right:10px;width:30px;height:30px">
                            <i class="fas fa-times"></i>
                        </button>
                        <div>
                            <h5 class="mb-1"><i class="fas fa-gift me-2"></i><?= __('user.upsell') ?> ${index + 1}</h5>
                            <div class="fw-bold">${upsell.name}</div>
                            <small>$${upsell.price}</small>
                        </div>
                    </div>
                `;
            });
        } else {
            html += `
                <div class="text-center text-muted my-3" style="font-size:24px">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <?= __('user.click_products_to_add_upsells') ?>
                </div>
            `;
        }

        previewDiv.innerHTML = html;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const frontendId = frontendSelect.value;
        
        if (!frontendId) {
            showToast('error', '<?= __('user.please_select_frontend_product') ?>');
            return;
        }

        const formData = new FormData();
        formData.append('frontend_product_id', frontendId);
        
        selectedUpsells.forEach(upsell => {
            formData.append('upsell_product_ids[]', upsell.id);
        });

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><?= __('user.saving') ?>...';
        submitBtn.disabled = true;

        fetch('<?= base_url('usercontrol/save_funnel_config') ?>', {
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
            showToast('error', '<?= __('user.error_occurred_while_saving') ?>');
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
        showToast('warning', '<?= __('user.please_select_frontend_product_first') ?>');
        return;
    }
    
    if (id == frontendId) {
        showToast('warning', '<?= __('user.cannot_add_frontend_as_upsell') ?>');
        return;
    }
    
    if (selectedUpsells.find(u => u.id == id)) {
        showToast('info', '<?= __('user.product_already_in_sequence') ?>');
        return;
    }
    
    selectedUpsells.push({ id, name, price });
    document.getElementById('frontend_product_id').dispatchEvent(new Event('change'));
    showToast('success', '<?= __('user.upsell_added_to_sequence') ?>');
}

function removeUpsell(index) {
    selectedUpsells.splice(index, 1);
    document.getElementById('frontend_product_id').dispatchEvent(new Event('change'));
    showToast('info', '<?= __('user.upsell_removed_from_sequence') ?>');
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
