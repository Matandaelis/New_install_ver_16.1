<?php foreach ($orders as $index => $order) { ?>

    <?php if ($order['type'] == 'store') { ?>
        <tr>
            <td>
                <button type="button" class="btn btn-sm btn-primary toggle-child-tr">
                    <i class="bi bi-plus-circle"></i>
                </button>
                <?= $start_from + $index ?>
            </td>
            <td><?= $order['id'] ?></td>
            <td><?= c_format($order['total_sum']) ?></td>
            <td><?= $order['order_country_flag'] ?></td>
            <td><?= __('user.local_store') ?></td>
            <?php 
                $icon = strtolower(str_replace(" ", "_", $status[$order['status']])) . '.png';
            ?>
            <td>
                <img width="30" height="30" title="<?= $status[$order['status']] ?>" alt="<?= $icon ?>" src="<?= base_url('assets/images/wallet-icon/' . $icon) ?>">
            </td>
            <td>
                <?= c_format($order['commission_amount']) ?><br>
                <?php if ($order['wallet_commission_status'] == 0) { ?>
                    <span class="badge <?= ((int)$order['wallet_status'] > 0) ? 'bg-success' : 'bg-warning' ?>">
                        <?= $wallet_status[(int)$order['wallet_status']] ?>
                    </span>
                <?php } else {
                    echo commission_status($order['wallet_commission_status']);
                } ?>
            </td>
            <td><?= wallet_paid_status($order['wallet_status']) ?></td>
            <td class="text-center"><?= date("d-m-Y h:i A", strtotime($order['created_at'])) ?></td>
        </tr>

        <tr class="detail-tr">
            <td colspan="100%">
                <div class="container-fluid">
                    <ul>
                        <li><b><?= __('user.payment_method') ?>:</b> <span><?= $order['payment_method'] ?></span></li>
                        <li><b><?= __('user.transaction') ?>:</b> <span><?= $order['txn_id'] ?></span></li>
                        <li><b><?= __('user.ip') ?>:</b> <span><?= $order['ip'] ?></span></li>
                        <li><b><?= __('user.country_code') ?>:</b> <span><?= $order['country_code'] ?></span></li>
                        <li><b><?= __('user.currency_code') ?>:</b> <span><?= $order['currency_code'] ?></span></li>

                        <li>
                            <b><?= __('user.products') ?></b>
                            <table class="detail-table table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= __('user.name') ?></th>
                                        <th><?= __('user.unit_price') ?></th>
                                        <th><?= __('user.variation_price') ?></th>
                                        <th><?= __('user.quantity') ?></th>
                                        <th><?= __('user.commission_type') ?></th>
                                        <th><?= __('user.total_discount') ?></th>
                                        <th><?= __('user.total') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['products'] as $product) { ?>
                                        <tr>
                                            <td>
                                                <div class="media">
                                                    <img src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>" style="width: 40px; height: 40px;" class="mr-3">
                                                    <div class="media-body">
                                                        <?= $product['product_name'] ?>
                                                        <?php
                                                        $combinationString = '';
                                                        if (!empty($product['variation'])) {
                                                            $variation = json_decode($product['variation'], true);
                                                            foreach ($variation as $k => $v) {
                                                                $val = ($k == 'colors') ? explode("-", $v)[1] : $v;
                                                                $combinationString .= ($combinationString == '') ? $val : ", $val";
                                                            }
                                                        }
                                                        if ($product['coupon_discount'] > 0) {
                                                            echo '<p class="couopn-code-text">' . __('user.code') . ': <span class="c-name">' . $product['coupon_code'] . '</span> ' . __('user.applied') . '</p>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= c_format($product['price']) ?></td>
                                            <td><?= c_format(@$variation['price'] ?? 0) ?></td>
                                            <td><?= $product['quantity'] ?></td>
                                            <td><?= $product['commission_type'] ?></td>
                                            <td><?= c_format($product['coupon_discount']) ?></td>
                                            <td><?= c_format($product['total']) ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php foreach ($order['totals'] as $total) { ?>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td><?= $total['text'] ?></td>
                                            <td><?= c_format($total['value']) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </li>

                        <li>
                            <b><?= __('user.payment_info') ?></b>
                            <table class="detail-table table table-bordered">
                                <thead>
                                    <th><?= __('user.mode') ?></th>
                                    <th><?= __('user.transaction_id') ?></th>
                                    <th><?= __('user.payment_status') ?></th>
                                </thead>
                                <tbody>
                                    <?php if ($order['status'] == 0) { ?>
                                        <tr><td colspan="100%" class="text-center text-muted"><?= __('user.waiting_for_payment_status') ?></td></tr>
                                    <?php } ?>
                                    <?php foreach ($order['payment_history'] as $history) { ?>
                                        <tr>
                                            <td><?= $history['payment_mode'] ?></td>
                                            <td><?= $order['txn_id'] ?></td>
                                            <td><?= $history['paypal_status'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </li>

                        <li>
                            <b><?= __('user.order_info') ?></b>
                            <table class="detail-table table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= __('user.status') ?></th>
                                        <th><?= __('user.comment') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($order['order_history'])) { ?>
                                        <tr><td colspan="3" class="text-center text-muted"><?= __('user.no_any_order_status') ?></td></tr>
                                    <?php } ?>
                                    <?php foreach ($order['order_history'] as $k => $history) { ?>
                                        <tr>
                                            <td>#<?= $k ?></td>
                                            <td><?= $status[$history['order_status_id']] ?></td>
                                            <td style="white-space: pre-line;"><?= htmlspecialchars($history['comment']) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </li>
                            <?php 
                                if (!empty($order['custom_data'])) {
                                    $raw = $order['custom_data'];
                                    $custom_data = is_string($raw) ? json_decode($raw, true) : $raw;

                                    if (is_array($custom_data)) {
                                        foreach ($custom_data as $cd) {
                                            if (is_array($cd) && isset($cd['key'], $cd['value'])) {
                                                echo '<li class="list-group-item"><strong>' . htmlspecialchars($cd['key']) . '</strong>: ' . htmlspecialchars($cd['value']) . '</li>';
                                            } elseif (is_object($cd) && isset($cd->key, $cd->value)) {
                                                echo '<li class="list-group-item"><strong>' . htmlspecialchars($cd->key) . '</strong>: ' . htmlspecialchars($cd->value) . '</li>';
                                            }
                                        }
                                    }
                                }
                            ?>
                    </ul>
                </div>
            </td>
        </tr>

    <?php } else { ?>
        <tr>
            <td><button type="button" class="btn btn-sm btn-primary toggle-child-tr"><i class="bi bi-plus-circle"></i></button> <?= $start_from + $index ?></td>
            <td><?= $order['id'] ?></td>
            <td><?= c_format($order['total']) ?></td>
            <td><?= $order['order_country_flag'] ?></td>
            <td>
            	<?php if(isset($order['script_name']) && $order['script_name'] == 's2s'): ?>
            		<span class="badge bg-primary text-white"><i class="fas fa-server me-1"></i><?= __('user.s2s_source_s2s') ?></span>
            	<?php else: ?>
            		<?= __('user.external') ?>
            	<?php endif; ?>
            </td>
            <td><?= __('user.complete') ?></td>
            <td>
                <?= c_format($order['commission']) ?><br>
                <span class="badge <?= ((int)$order['wallet_status'] > 0) ? 'bg-success' : 'bg-warning' ?>">
                    <?= $wallet_status[(int)$order['wallet_status']] ?>
                </span>
            </td>
            <td><?= wallet_paid_status($order['wallet_status']) ?></td>
            <td class="text-center"><?= date("d-m-Y h:i A", strtotime($order['created_at'])) ?></td>
        </tr>

        <tr class="detail-tr">
            <td colspan="100%">
                <div class="container-fluid">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong><?= __('user.product_ids') ?>:</strong> <?= $order['product_ids'] ?></li>
                        <li class="list-group-item"><strong><?= __('user.total') ?>:</strong> <?= $order['total'] ?></li>
                        <li class="list-group-item"><strong><?= __('user.currency') ?>:</strong> <?= $order['currency'] ?></li>
                        <li class="list-group-item"><strong><?= __('user.commission_type') ?>:</strong> <?= $order['commission_type'] ?></li>
                        <li class="list-group-item"><strong><?= __('user.ip') ?>:</strong> <?= $order['ip'] ?></li>
                        <li class="list-group-item"><strong><?= __('user.country_code') ?>:</strong> <?= $order['country_code'] ?> <img src="<?= base_url('assets/template/images/flags/' . strtolower($order['country_code'])) ?>.png" width="25" height="15"></li>
                        <li class="list-group-item"><strong><?= __('user.website') ?>:</strong> <a href="//<?= $order['base_url'] ?>" target="_blank"><?= $order['base_url'] ?></a></li>
                        <li class="list-group-item"><strong><?= __('user.s2s_conversion_source') ?>:</strong> <?php if($order['script_name'] == 's2s'): ?><span class="badge bg-primary"><i class="fas fa-server me-1"></i><?= __('user.s2s_source_s2s') ?></span><?php else: ?><?= ucfirst($order['script_name']) ?><?php endif; ?></li>

                            <?php 
                                if (!empty($order['custom_data'])) {
                                    $raw = $order['custom_data'];
                                    $custom_data = is_string($raw) ? json_decode($raw, true) : $raw;

                                    if (is_array($custom_data)) {
                                        foreach ($custom_data as $cd) {
                                            if (is_array($cd) && isset($cd['key'], $cd['value'])) {
                                                echo '<li class="list-group-item"><strong>' . htmlspecialchars($cd['key']) . '</strong>: ' . htmlspecialchars($cd['value']) . '</li>';
                                            } elseif (is_object($cd) && isset($cd->key, $cd->value)) {
                                                echo '<li class="list-group-item"><strong>' . htmlspecialchars($cd->key) . '</strong>: ' . htmlspecialchars($cd->value) . '</li>';
                                            }
                                        }
                                    }
                                }
                            ?>

                    </ul>
                </div>
            </td>
        </tr>
    <?php } ?>

<?php } ?>