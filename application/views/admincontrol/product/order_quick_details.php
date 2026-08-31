<?php if(!empty($order)): ?>
<div class="row g-3">
    <!-- Order Information -->
    <div class="col-md-6">
        <div class="card bg-light h-100">
            <div class="card-body">
                <h6 class="card-title text-primary mb-3">
                    <i class="bi bi-info-circle me-1"></i><?= __('admin.order_information') ?>
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <small class="text-muted"><?= __('admin.order_id') ?>:</small>
                        <div class="fw-semibold">#<?= $order['id'] ?></div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted"><?= __('admin.status') ?>:</small>
                        <div>
                            <?php 
                            $statusClass = 'bg-secondary';
                            if($order['status'] == 1) $statusClass = 'bg-success';
                            elseif($order['status'] == 7) $statusClass = 'bg-warning';
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $statuses[$order['status']] ?? 'Unknown' ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted"><?= __('admin.total_amount') ?>:</small>
                        <div class="fw-bold text-success"><?= c_format($order['total']) ?></div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted"><?= __('admin.payment_method') ?>:</small>
                        <div class="fw-semibold"><?= $order['payment_method'] ?></div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted"><?= __('admin.order_date') ?>:</small>
                        <div class="fw-semibold"><?= dateGlobalFormat($order['created_at']) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="col-md-6">
        <div class="card bg-light h-100">
            <div class="card-body">
                <h6 class="card-title text-success mb-3">
                    <i class="bi bi-person me-1"></i><?= __('admin.customer_information') ?>
                </h6>
                <div class="row g-2">
                    <div class="col-12">
                        <small class="text-muted"><?= __('admin.name') ?>:</small>
                        <div class="fw-semibold"><?= $order['firstname'] . ' ' . $order['lastname'] ?></div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted"><?= __('admin.email') ?>:</small>
                        <div><?= $order['email'] ?? 'N/A' ?></div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted"><?= __('admin.phone') ?>:</small>
                        <div><?= $order['phone'] ?? 'N/A' ?></div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted"><?= __('admin.location') ?>:</small>
                        <div class="d-flex align-items-center">
                            <?php if(!empty($order['country_code'])): ?>
                                <img src="<?= base_url('assets/template/images/flags/' . strtolower($order['country_code']) . '.png') ?>" 
                                     style="width: 16px; margin-right: 5px;" alt="<?= $order['country_code'] ?>">
                            <?php endif; ?>
                            <span><?= $order['ip'] ?? 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Products -->
    <?php if(!empty($products)): ?>
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title text-info mb-3">
                    <i class="bi bi-box-seam me-1"></i><?= __('admin.order_products') ?> (<?= count($products) ?>)
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <thead>
                            <tr class="border-bottom">
                                <th class="fw-semibold"><?= __('admin.product') ?></th>
                                <th class="fw-semibold text-center"><?= __('admin.quantity') ?></th>
                                <th class="fw-semibold text-end"><?= __('admin.price') ?></th>
                                <th class="fw-semibold text-end"><?= __('admin.total') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= $product['product_name'] ?></div>
                                    <?php if(!empty($product['product_variation'])): ?>
                                        <small class="text-muted"><?= $product['product_variation'] ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $product['quantity'] ?></td>
                                <td class="text-end"><?= c_format($product['price']) ?></td>
                                <td class="text-end fw-semibold"><?= c_format($product['total']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Additional Information -->
    <div class="col-12">
        <div class="row g-3">
            <?php if(!empty($order['shipping_cost']) && $order['shipping_cost'] > 0): ?>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-truck text-primary mb-2" style="font-size: 1.5rem;"></i>
                        <div class="fw-semibold"><?= __('admin.shipping_cost') ?></div>
                        <div class="text-success fw-bold"><?= c_format($order['shipping_cost']) ?></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($order['tax_cost']) && $order['tax_cost'] > 0): ?>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-receipt text-warning mb-2" style="font-size: 1.5rem;"></i>
                        <div class="fw-semibold"><?= __('admin.tax_cost') ?></div>
                        <div class="text-warning fw-bold"><?= c_format($order['tax_cost']) ?></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($order['txn_id'])): ?>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-credit-card text-info mb-2" style="font-size: 1.5rem;"></i>
                        <div class="fw-semibold"><?= __('admin.transaction_id') ?></div>
                        <div class="text-info fw-bold">
                            <code><?= substr($order['txn_id'], 0, 15) ?><?= strlen($order['txn_id']) > 15 ? '...' : '' ?></code>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php else: ?>
<div class="text-center py-4">
    <i class="bi bi-exclamation-triangle display-1 text-warning mb-3"></i>
    <h5 class="text-warning"><?= __('admin.order_not_found') ?></h5>
    <p class="text-muted"><?= __('admin.order_details_not_available') ?></p>
</div>
<?php endif; ?>
