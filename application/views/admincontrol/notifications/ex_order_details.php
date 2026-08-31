<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cart-check me-3 fs-2"></i>
                        <div>
                            <h4 class="mb-0 fw-bold"><?= __('admin.page_title_order_notification_details') ?></h4>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-4 border-bottom bg-light">
                        <h6 class="text-muted mb-2 text-uppercase fw-semibold">
                            <i class="bi bi-info-circle me-2"></i>
                            <?= __('admin.notification_details') ?>
                        </h6>
                        <div class="alert alert-info mb-0">
                            <?= nl2br(htmlspecialchars($notification_details)) ?>
                        </div>
                    </div>

                    <div class="p-4">
                        <h6 class="text-muted mb-3 text-uppercase fw-semibold">
                            <i class="bi bi-receipt me-2"></i>
                            <?= __('admin.order_information') ?>
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                                                    <i class="bi bi-tag fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-muted mb-1 small"><?= __('admin.product_ids') ?></p>
                                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($order['product_ids']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="bg-success bg-opacity-10 text-success rounded p-2">
                                                    <i class="bi bi-cash-stack fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-muted mb-1 small"><?= __('admin.total') ?></p>
                                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($order['total']) ?> <?= htmlspecialchars($order['currency']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="bg-info bg-opacity-10 text-info rounded p-2">
                                                    <i class="bi bi-percent fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-muted mb-1 small"><?= __('admin.affiliate_commission_type') ?></p>
                                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($order['commission_type']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="bg-warning bg-opacity-10 text-warning rounded p-2">
                                                    <i class="bi bi-geo-alt fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-muted mb-1 small"><?= __('admin.ip') ?></p>
                                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($order['ip']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="bg-danger bg-opacity-10 text-danger rounded p-2">
                                                    <i class="bi bi-flag fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-muted mb-1 small"><?= __('admin.country_code') ?></p>
                                                <p class="mb-0 fw-semibold d-flex align-items-center">
                                                    <span class="me-2"><?= htmlspecialchars($order['country_code']) ?></span>
                                                    <img 
                                                        title="<?= htmlspecialchars($order['country_code']) ?>" 
                                                        src="<?= base_url('assets/template/images/flags/' . strtolower($order['country_code'])) ?>.png" 
                                                        width="30" height="20" class="rounded"
                                                    >
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="bg-secondary bg-opacity-10 text-secondary rounded p-2">
                                                    <i class="bi bi-globe fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-muted mb-1 small"><?= __('admin.website') ?></p>
                                                <p class="mb-0">
                                                    <a href="<?= htmlspecialchars($order['base_url']) ?>" target="_blank" class="text-decoration-none fw-semibold">
                                                        <?= htmlspecialchars($order['base_url']) ?>
                                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if(!empty($order['script_name'])): ?>
                            <div class="col-md-12">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="bg-dark bg-opacity-10 text-dark rounded p-2">
                                                    <i class="bi bi-code-square fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-muted mb-1 small"><?= __('admin.s2s_conversion_source') ?></p>
                                                <?php if($order['script_name'] == 's2s'): ?>
                                                    <p class="mb-0"><span class="badge bg-primary"><i class="fas fa-server me-1"></i><?= __('admin.s2s_source_s2s') ?></span></p>
                                                <?php else: ?>
                                                    <p class="mb-0 fw-semibold"><?= htmlspecialchars(ucfirst($order['script_name'])) ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-4 border-top bg-light">
                        <h6 class="text-muted mb-3 text-uppercase fw-semibold">
                            <i class="bi bi-list-ul me-2"></i>
                            <?= __('admin.custom_data') ?>
                        </h6>
                        <?php
                        $custom_data = $order['custom_data'];
                        if (is_string($custom_data)) {
                            $custom_data = json_decode($custom_data, true);
                        }

                        if (!empty($custom_data) && is_array($custom_data)) {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-hover table-bordered bg-white mb-0">';
                            echo '<thead class="table-light">';
                            echo '<tr>';
                            echo '<th class="fw-semibold">' . __('admin.key') . '</th>';
                            echo '<th class="fw-semibold">' . __('admin.value') . '</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            foreach ($custom_data as $value) {
                                if (is_array($value) && isset($value['key'], $value['value'])) {
                                    echo '<tr>';
                                    echo '<td class="fw-semibold">' . htmlspecialchars($value['key']) . '</td>';
                                    echo '<td>' . htmlspecialchars($value['value']) . '</td>';
                                    echo '</tr>';
                                }
                            }
                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-secondary mb-0">';
                            echo '<i class="bi bi-info-circle me-2"></i>';
                            echo '<span>' . __('admin.no_data_found') . '</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>