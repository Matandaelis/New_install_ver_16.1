<?php if(!empty($getallorders) && is_array($getallorders)): ?>
    <?php foreach($getallorders as $product): ?>
        <?php if(!is_array($product) || !isset($product['id'])) continue; ?>
        <tr class="order-row" data-order-id="<?= $product['id'] ?>">
            <td class="align-middle">
                <div class="form-check">
                    <input class="form-check-input order-checkbox" type="checkbox" value="<?= $product['id'] ?>">
                </div>
            </td>
            <td class="align-middle">
                <span class="badge bg-primary">#<?= $product['id'] ?></span>
            </td>
            <td class="align-middle">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                        <i class="bi bi-person text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-semibold"><?= $product['username'] ?></div>
                        <?php if(!empty($product['user_type'])): ?>
                            <small class="text-muted"><?= __('admin.type_'. $product['user_type']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </td>
            <td class="align-middle">
                <?php if(!empty($product['user_type'])): ?>
                    <span class="badge bg-info"><?= __('admin.type_'. $product['user_type']) ?></span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= __('admin.standard') ?></span>
                <?php endif; ?>
            </td>
            <td class="align-middle text-end">
                <div class="fw-bold text-success"><?= c_format($product['total_sum']) ?></div>
            </td>
            <td class="align-middle">
                <?php
                $paymentIcon = 'bi-credit-card';
                $paymentClass = 'bg-info';
                $paymentText = str_replace("_", " ", $product['payment_method']);
                
                if ($product['payment_method'] == 'Bank Transfer') {
                    $paymentIcon = 'bi-bank';
                    $paymentClass = 'bg-primary';
                    $paymentText = __('admin.bank_transfer');
                } elseif ($product['payment_method'] == 'Cash On Delivery') {
                    $paymentIcon = 'bi-cash';
                    $paymentClass = 'bg-success';
                    $paymentText = __('admin.cash_on_delivery');
                } elseif ($product['payment_method'] == 'Paypal') {
                    $paymentIcon = 'bi-paypal';
                    $paymentClass = 'bg-warning';
                    $paymentText = __('admin.paypal');
                } elseif ($product['payment_method'] == 'Razorpay') {
                    $paymentIcon = 'bi-credit-card-2-front';
                    $paymentClass = 'bg-info';
                    $paymentText = __('admin.razorpay');
                } elseif ($product['payment_method'] == 'Flutterwave') {
                    $paymentIcon = 'bi-credit-card-fill';
                    $paymentClass = 'bg-secondary';
                    $paymentText = __('admin.flutterwave');
                }
                ?>
                <span class="badge <?= $paymentClass ?> text-white">
                    <i class="<?= $paymentIcon ?> me-1"></i><?= $paymentText ?>
                </span>
            </td>
            <td class="align-middle">
                <div class="d-flex align-items-center">
                    <?= $product['order_country_flag'] ?>
                    <small class="text-muted ms-1"><?= $product['ip'] ?? '' ?></small>
                </div>
            </td>
            <td class="align-middle">
                <?php if(!empty($product['txn_id'])): ?>
                    <code class="small"><?= substr($product['txn_id'], 0, 12) ?>...</code>
                <?php else: ?>
                    <span class="text-muted">-</span>
                <?php endif; ?>
            </td>
            <td class="align-middle text-end">
                <?php
                $ws = (int)$product['wallet_status'];
                $walletLabel = '';
                if ($product['wallet_commission_status'] == 0) {
                    if (isset($wallet_status[$ws])) {
                        if ($wallet_status[$ws] === 'ON HOLD') {
                            $walletLabel = __('admin.onhold');
                        } elseif ($wallet_status[$ws] === 'IN WALLET') {
                            $walletLabel = __('admin.inwallet');
                        } else {
                            $walletLabel = $wallet_status[$ws];
                        }
                    }
                }
                $walletBadgeClass = ((int)$product['wallet_status'] > 0) ? 'bg-success' : 'bg-warning text-dark';
                ?>
                <div class="order-commission-cell d-inline-flex text-end">
                    <div class="d-flex align-items-center justify-content-end gap-2 flex-wrap commission-meta-row">
                        <?php if ($product['wallet_commission_status'] == 0 && $walletLabel !== ''): ?>
                            <span class="badge rounded-pill <?= $walletBadgeClass ?>">
                                <?= html_escape($walletLabel) ?>
                            </span>
                        <?php elseif ($product['wallet_commission_status'] != 0): ?>
                            <?= commission_status($product['wallet_commission_status']) ?>
                        <?php endif; ?>
                        <span class="commission-amount fw-bold text-success"><?= c_format($product['commission_amount']) ?></span>
                    </div>
                </div>
            </td>
            <td class="align-middle text-center">
                <div class="order-status">
                    <?php
                    $statusClass = 'bg-secondary';
                    $statusText = $status[$product['status']];
                    
                    if ($status[$product['status']] == 'Processed') {
                        $statusClass = 'bg-info';
                        $statusText = __('admin.processed');
                    } elseif ($status[$product['status']] == 'Complete') {
                        $statusClass = 'bg-success';
                        $statusText = __('admin.complete');
                    } elseif ($product['status'] == 7) {
                        $statusClass = 'bg-warning';
                    }
                    ?>
                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                </div>
            </td>
            <td class="align-middle text-center">
                <?php
                $oid = (int)$product['id'];
                $curStatus = (int)$product['status'];
                // Status 0 and 12 both map to "waiting for payment" in Order_model — treat as one for shortcuts.
                $isWaitingPayment = ($curStatus === 0 || $curStatus === 12);
                $showQuickComplete = ($curStatus !== 1);
                $showQuickProcessed = ($curStatus !== 7);
                $showQuickWaiting = !$isWaitingPayment;
                $anyQuickShortcut = $showQuickComplete || $showQuickProcessed || $showQuickWaiting;

                $orderStatusMenuGroups = [
                    __('admin.order_status_group_flow') => ['0', '6', '12', '7', '1'],
                    __('admin.order_status_group_issues') => ['2', '3', '4', '5'],
                    __('admin.order_status_group_refunds') => ['8', '9', '10', '11'],
                ];

                $orderMenuKeepKey = static function ($k, $current) {
                    $k = (string)$k;
                    if ($k === '1' && $current === 1) {
                        return false;
                    }
                    if ($k === '7' && $current === 7) {
                        return false;
                    }
                    if (($k === '0' || $k === '12') && ($current === 0 || $current === 12)) {
                        return false;
                    }
                    return true;
                };
                ?>
                <div class="order-quick-actions d-flex flex-wrap justify-content-center align-items-center gap-1">
                    <?php if ($anyQuickShortcut): ?>
                    <div class="btn-group btn-group-sm" role="group" aria-label="<?= html_escape(__('admin.order_quick_frequent_aria')) ?>">
                        <?php if ($showQuickComplete): ?>
                        <button type="button"
                                class="btn btn-success order-quick-btn px-2"
                                data-order-id="<?= $oid ?>"
                                data-status="1"
                                data-status-label="<?= html_escape(__('admin.complete')) ?>"
                                title="<?= html_escape(__('admin.order_quick_complete')) ?>">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <?php endif; ?>
                        <?php if ($showQuickProcessed): ?>
                        <button type="button"
                                class="btn btn-info text-white order-quick-btn px-2"
                                data-order-id="<?= $oid ?>"
                                data-status="7"
                                data-status-label="<?= html_escape(__('admin.processed')) ?>"
                                title="<?= html_escape(__('admin.order_quick_processed')) ?>">
                            <i class="bi bi-arrow-right-circle"></i>
                        </button>
                        <?php endif; ?>
                        <?php if ($showQuickWaiting): ?>
                        <button type="button"
                                class="btn btn-outline-primary order-quick-btn px-2"
                                data-order-id="<?= $oid ?>"
                                data-status="12"
                                data-status-label="<?= html_escape(__('admin.waiting_for_payment')) ?>"
                                title="<?= html_escape(__('admin.order_quick_waiting_payment')) ?>">
                            <i class="bi bi-hourglass-split"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                data-bs-display="static"
                                aria-expanded="false"
                                title="<?= html_escape(__('admin.order_quick_more_statuses')) ?>">
                            <?= __('admin.order_quick_more') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm small py-0" style="min-width: 12rem; max-height: 70vh; overflow-y: auto;">
                            <?php foreach ($orderStatusMenuGroups as $groupTitle => $groupKeys): ?>
                                <?php
                                $groupKeys = array_filter($groupKeys, static function ($k) use ($status, $curStatus, $orderMenuKeepKey) {
                                    if (!isset($status[$k])) {
                                        return false;
                                    }
                                    return $orderMenuKeepKey($k, $curStatus);
                                });
                                if ($groupKeys === []) {
                                    continue;
                                }
                                ?>
                                <li><h6 class="dropdown-header text-uppercase small mb-0 py-2 px-3"><?= html_escape($groupTitle) ?></h6></li>
                                <?php foreach ($groupKeys as $k): ?>
                                    <li>
                                        <button type="button"
                                                class="dropdown-item order-quick-pick py-2 px-3"
                                                data-order-id="<?= $oid ?>"
                                                data-status="<?= html_escape($k) ?>"
                                                data-status-label="<?= html_escape($status[$k]) ?>">
                                            <?= html_escape($status[$k]) ?>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </td>
            <td class="align-middle text-center">
                <div class="btn-group btn-group-sm">
                    <a href="<?= base_url('admincontrol/vieworder/'. $product['id']) ?>" 
                       class="btn btn-outline-primary" title="<?= __('admin.view_details') ?>">
                        <i class="bi bi-eye"></i>
                    </a>
                    <button class="btn btn-outline-info" onclick="showOrderDetails(<?= $product['id'] ?>)" 
                            title="<?= __('admin.quick_view') ?>">
                        <i class="bi bi-info-circle"></i>
                    </button>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="12" class="text-center py-5">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                <h4 class="text-muted mb-2"><?= __('admin.no_orders_found') ?></h4>
                <p class="text-muted"><?= __('admin.no_orders_match_criteria') ?></p>
            </div>
        </td>
    </tr>
<?php endif; ?>