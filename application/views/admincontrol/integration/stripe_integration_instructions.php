<div class="stripe-integration-instructions p-3">
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <h5 class="mb-1"><?= __('admin.stripe_direct_checkout_info') ?></h5>
                <p class="mb-0"><?= __('admin.stripe_direct_checkout_desc') ?></p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i><?= __('admin.campaign_details') ?></h5>
        </div>
        <div class="card-body">
            <?php 
            // Use the same data source as the form
            $stripe_price = isset($tool['commission']['stripe_price']) ? floatval($tool['commission']['stripe_price']) : 0;
            $stripe_currency = isset($tool['commission']['stripe_currency']) ? strtoupper($tool['commission']['stripe_currency']) : 'USD';
            ?>
            
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="text-center p-3 bg-light rounded">
                        <i class="fas fa-tag text-primary fa-2x mb-2"></i>
                        <div class="small text-muted"><?= __('admin.price') ?></div>
                        <div class="h4 mb-0"><?= number_format($stripe_price, 2) ?> <?= $stripe_currency ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-light rounded">
                        <i class="fas fa-money-bill-wave text-success fa-2x mb-2"></i>
                        <div class="small text-muted"><?= __('admin.currency') ?></div>
                        <div class="h4 mb-0"><?= $stripe_currency ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-light rounded">
                        <i class="fas fa-percentage text-info fa-2x mb-2"></i>
                        <div class="small text-muted"><?= __('admin.commission') ?></div>
                        <div class="h4 mb-0">
                            <?php 
                            // Check if commission data exists
                            if(isset($tool['commission_type']) && isset($tool['commission_sale']) && !empty($tool['commission_sale'])):
                                if($tool['commission_type'] == 'percentage'): 
                                    echo $tool['commission_sale'] . '%';
                                else: 
                                    echo c_format($tool['commission_sale']);
                                endif;
                            else:
                                echo '<span class="text-muted">-</span>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-route me-2"></i><?= __('admin.how_it_works') ?></h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">1</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?= __('admin.stripe_step_1_title') ?></h6>
                            <p class="text-muted small mb-0"><?= __('admin.stripe_step_1_desc') ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">2</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?= __('admin.stripe_step_2_title') ?></h6>
                            <p class="text-muted small mb-0"><?= __('admin.stripe_step_2_desc') ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">3</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?= __('admin.stripe_step_3_title') ?></h6>
                            <p class="text-muted small mb-0"><?= __('admin.stripe_step_3_desc') ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <span class="badge bg-success rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">✓</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?= __('admin.stripe_step_4_title') ?></h6>
                            <p class="text-muted small mb-0"><?= __('admin.stripe_step_4_desc') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-cog me-2"></i><?= __('admin.stripe_configuration') ?></h5>
        </div>
        <div class="card-body text-center">
            <p class="mb-3"><?= __('admin.stripe_config_desc') ?></p>
            <a href="<?= base_url('admincontrol/payment_gateway_edit/stripe') ?>" class="btn btn-primary btn-lg" target="_blank">
                <i class="fas fa-external-link-alt me-2"></i><?= __('admin.open_stripe_settings') ?>
            </a>
        </div>
    </div>
</div>

<style>
.stripe-integration-instructions .card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.stripe-integration-instructions .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.stripe-integration-instructions .badge.rounded-circle {
    font-weight: 600;
}
</style>