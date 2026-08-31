<?php 
$order = $orders[0]; 
?>

<?php if ($order['type'] == 'store') { ?>
<div>
    <ul>
        <?php 
        if (
            ($order['wallet_type'] == 'sale_commission' || $order['wallet_type'] == 'admin_sale_commission' || 
            $order['wallet_type'] == 'vendor_sale_commission') && 
            $order['wallet_comm_from'] == 'store' && 
            !empty($order['wallet_reference_id_2'])
        ) {
            $product = $this->db->query('SELECT product_id,product_name, product_slug,is_campaign_product FROM product WHERE product_id=' . $order['wallet_reference_id_2'])->row();

            $productLink = $product->is_campaign_product == 1 
                ? base_url('store/product/' . $product->product_id) 
                : base_url('store/' . base64_encode($userdetails['id']) . '/product/' . $product->product_slug);

            echo "<li><b>" . wallet_ex_type($trans) . " -</b> <a target=\"_blank\" href=\"{$productLink}?preview=1\">" . ucwords($product->product_name) . "</a></li>";
        }
        ?>
        <li><b><?= __('admin.payment_method') ?> :</b> <span><?= $order['payment_method']; ?></span></li>
        <li><b><?= __('admin.transaction') ?> :</b> <span><?= $order['txn_id'] ?></span></li>
        <li><b><?= __('admin.ip') ?> :</b> <span><?= $order['ip'] ?></span></li>
        <li><b><?= __('admin.country_code') ?> :</b> <span><?= $order['country_code'] ?></span></li>
        <li><b><?= __('admin.currency_code') ?> :</b> <span><?= $order['currency_code'] ?></span></li>

        <li><br><b><?= __('admin.products') ?></b>
            <table class="table table-bordered table-striped">
                <tr>
                    <th colspan="2"><?= __('admin.name') ?></th>
                    <th><?= __('admin.unit_price') ?></th>
                    <th><?= __('admin.quantity') ?></th>
                    <th><?= __('admin.commission_type') ?></th>
                    <th><?= __('admin.total_discount') ?></th>
                    <th><?= __('admin.total') ?></th>
                </tr>
                <?php foreach ($order['products'] as $product) { ?>
                <tr>
                    <td><img src="<?= $product['image'] ?>" style="width: 40px;height: 40px"></td>
                    <td>
                        <?php
                        $combinationString = "";
                        if (isset($product['variation']) && !empty($product['variation'])) {
                            $variation = json_decode($product['variation'], true);
                            foreach ($variation as $key => $value) {
                                $combinationString .= ($combinationString ? "," : "") . ($key == 'colors' ? explode("-", $value)[1] : $value);
                            }
                        }
                        ?>
                        <?= $product['product_name'] ?><?= $combinationString ? " ({$combinationString})" : "" ?>
                        <?php if ($product['coupon_discount'] > 0) { ?>
                            <p class="couopn-code-text">
                                <?= __('admin.code') ?> : <span class="c-name"><?= $product['coupon_code'] ?></span> <?= __('admin.applied') ?>
                            </p>
                        <?php } ?>
                    </td>
                    <td><?= c_format($product['price']); ?></td>
                    <td><?= $product['quantity']; ?></td>
                    <td><?= $product['commission_type']; ?></td>
                    <td><?= c_format($product['coupon_discount']); ?></td>
                    <td><?= c_format($product['total']); ?></td>
                </tr>
                <?php } ?>
                <?php foreach ($order['totals'] as $total) { ?>
                <tr>
                    <td colspan="5"></td>
                    <td><?= $total['text'] ?></td>
                    <td><?= c_format($total['value']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </li>

        <li><b><?= __('admin.payment_info') ?></b>
            <table class="table table-bordered table-striped">
                <thead>
                    <th><?= __('admin.mode') ?></th>
                    <th><?= __('admin.transaction_id') ?></th>
                    <th><?= __('admin.payment_status') ?></th>
                </thead>
                <tbody>
                    <?php if ($order['status'] == 0) { ?>
                        <tr><td colspan="3" class="text-center text-muted"><?= __('admin.waiting_for_payment_status') ?></td></tr>
                    <?php } ?>
                    <?php foreach ($order['payment_history'] as $value) { ?>
                    <tr>
                        <td><?= $value['payment_mode']; ?></td>
                        <td><?= $order['txn_id']; ?></td>
                        <td><?= $value['paypal_status'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </li>

        <li><b><?= __('admin.order_info') ?></b>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('admin.status') ?></th>
                        <th><?= __('admin.comment') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$order['order_history']) { ?>
                        <tr><td colspan="3" class="text-center text-muted"><?= __('admin.no_any_order_status') ?></td></tr>
                    <?php } ?>
                    <?php foreach ($order['order_history'] as $key => $value) { ?>
                    <tr>
                        <td>#<?= $key ?></td>
                        <td><?= $status[$value['order_status_id']] ?></td>
                        <td style="white-space: pre-line;"><?= $value['comment'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </li>

        <?php 
        if (!empty($order['comment'])) {
            $comments = json_decode($order['comment'], true);
            foreach ($comments as $c) {
                if (is_array($c)) {
                    echo "<li><b>{$c['title']} :</b> <span>{$c['comment']}</span></li>";
                }
            }
        }
        ?>
        <li><b><?= __('admin.order_created_at') ?> :</b> <span><?= $order['created_at'] ?></span></li>
    </ul>

    <!-- V14: Order Fulfillment Tracking -->
    <div class="card mt-3 border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-shipping-fast me-2 text-primary"></i><?= __('admin.shipping_tracking') ?></h6>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label small fw-medium"><?= __('admin.tracking_number') ?></label>
                    <input type="text" class="form-control form-control-sm" id="tracking_number_<?= $order['id'] ?>" value="<?= htmlspecialchars($order['shipping_tracking_number'] ?? '') ?>" placeholder="<?= __('admin.enter_tracking_number') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-medium"><?= __('admin.shipping_carrier') ?></label>
                    <select class="form-select form-select-sm" id="tracking_carrier_<?= $order['id'] ?>">
                        <option value=""><?= __('admin.select_carrier') ?></option>
                        <option value="UPS" <?= (($order['shipping_carrier'] ?? '') == 'UPS') ? 'selected' : '' ?>><?= __('admin.carrier_ups') ?></option>
                        <option value="FedEx" <?= (($order['shipping_carrier'] ?? '') == 'FedEx') ? 'selected' : '' ?>><?= __('admin.carrier_fedex') ?></option>
                        <option value="USPS" <?= (($order['shipping_carrier'] ?? '') == 'USPS') ? 'selected' : '' ?>><?= __('admin.carrier_usps') ?></option>
                        <option value="DHL" <?= (($order['shipping_carrier'] ?? '') == 'DHL') ? 'selected' : '' ?>><?= __('admin.carrier_dhl') ?></option>
                        <option value="Other" <?= (($order['shipping_carrier'] ?? '') == 'Other') ? 'selected' : '' ?>><?= __('admin.carrier_other') ?></option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-1">
                    <button class="btn btn-sm btn-outline-primary flex-fill" onclick="saveTracking(<?= $order['id'] ?>)"><i class="fas fa-save me-1"></i><?= __('admin.save') ?></button>
                </div>
            </div>
            <div class="d-flex gap-2">
                <?php if (empty($order['shipped_at'])): ?>
                    <button class="btn btn-sm btn-warning" onclick="markShipped(<?= $order['id'] ?>)"><i class="fas fa-truck me-1"></i><?= __('admin.mark_as_shipped') ?></button>
                <?php else: ?>
                    <span class="badge bg-info"><i class="fas fa-truck me-1"></i><?= __('admin.shipped_at') ?>: <?= $order['shipped_at'] ?></span>
                <?php endif; ?>
                <?php if (!empty($order['shipped_at']) && empty($order['delivered_at'])): ?>
                    <button class="btn btn-sm btn-success" onclick="markDelivered(<?= $order['id'] ?>)"><i class="fas fa-check-circle me-1"></i><?= __('admin.mark_as_delivered') ?></button>
                <?php elseif (!empty($order['delivered_at'])): ?>
                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i><?= __('admin.delivered_at') ?>: <?= $order['delivered_at'] ?></span>
                <?php endif; ?>
            </div>
            <!-- Order Timeline -->
            <?php if (!empty($order['shipped_at']) || !empty($order['delivered_at'])): ?>
            <div class="mt-3 pt-3 border-top">
                <h6 class="small fw-medium mb-2"><i class="fas fa-timeline me-1"></i><?= __('admin.order_timeline') ?></h6>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary rounded-circle p-1"><i class="fas fa-shopping-cart fa-xs"></i></span>
                        <small><?= __('admin.order_placed') ?>: <?= $order['created_at'] ?></small>
                    </div>
                    <?php if (!empty($order['shipped_at'])): ?>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-info rounded-circle p-1"><i class="fas fa-truck fa-xs"></i></span>
                        <small><?= __('admin.order_shipped') ?>: <?= $order['shipped_at'] ?></small>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($order['delivered_at'])): ?>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success rounded-circle p-1"><i class="fas fa-check fa-xs"></i></span>
                        <small><?= __('admin.order_delivered') ?>: <?= $order['delivered_at'] ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- V14: Order Messages -->
    <div class="card mt-3 border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-comments me-2 text-info"></i><?= __('admin.order_messages') ?></h6>
        </div>
        <div class="card-body">
            <div id="order_messages_<?= $order['id'] ?>" class="mb-2" style="max-height:200px;overflow-y:auto;"></div>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="msg_input_<?= $order['id'] ?>" placeholder="<?= __('admin.type_message') ?>">
                <button class="btn btn-primary" onclick="sendOrderMessage(<?= $order['id'] ?>, 'admin')"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script>
    function saveTracking(orderId) {
        $.post(window.affiliatePro.base_url + 'store/save_tracking', {
            order_id: orderId,
            tracking_number: $('#tracking_number_' + orderId).val(),
            carrier: $('#tracking_carrier_' + orderId).val()
        }, function(r) {
            if (r.success) toastr.success(r.message);
        }, 'json');
    }
    function markShipped(orderId) {
        $.post(window.affiliatePro.base_url + 'store/save_tracking', {
            order_id: orderId, action: 'shipped',
            tracking_number: $('#tracking_number_' + orderId).val(),
            carrier: $('#tracking_carrier_' + orderId).val()
        }, function(r) {
            if (r.success) { toastr.success('<?= addslashes(__('admin.order_shipped')) ?>'); location.reload(); }
        }, 'json');
    }
    function markDelivered(orderId) {
        $.post(window.affiliatePro.base_url + 'store/save_tracking', {
            order_id: orderId, action: 'delivered'
        }, function(r) {
            if (r.success) { toastr.success('<?= addslashes(__('admin.order_delivered')) ?>'); location.reload(); }
        }, 'json');
    }
    function loadOrderMessages(orderId) {
        $.get(window.affiliatePro.base_url + 'store/order_messages/' + orderId, function(msgs) {
            var html = '';
            if (msgs.length === 0) { html = '<p class="text-muted small"><?= addslashes(__('admin.no_messages')) ?></p>'; }
            else {
                msgs.forEach(function(m) {
                    var align = m.sender_type === 'admin' ? 'text-end' : 'text-start';
                    var bg = m.sender_type === 'admin' ? 'bg-primary text-white' : 'bg-light';
                    html += '<div class="' + align + ' mb-1"><span class="badge ' + bg + ' fw-normal px-2 py-1">' + m.message + '</span><br><small class="text-muted">' + m.sender_type + ' &bull; ' + m.created_at + '</small></div>';
                });
            }
            $('#order_messages_' + orderId).html(html);
        }, 'json');
    }
    function sendOrderMessage(orderId, senderType) {
        var msg = $('#msg_input_' + orderId).val().trim();
        if (!msg) return;
        $.post(window.affiliatePro.base_url + 'store/send_order_message', {
            order_id: orderId, message: msg, sender_type: senderType
        }, function(r) {
            if (r.success) { $('#msg_input_' + orderId).val(''); loadOrderMessages(orderId); }
        }, 'json');
    }
    loadOrderMessages(<?= $order['id'] ?>);
    </script>
</div>
<?php } else { ?>
<div>
    <ul>
        <li><b><?= __('admin.product_ids') ?> :</b> <span><?= $order['product_ids'] ?></span></li>
        <li><b><?= __('admin.total') ?> :</b> <span><?= $order['total'] ?></span></li>
        <li><b><?= __('admin.currency') ?> :</b> <span><?= $order['currency'] ?></span></li>
        <li><b><?= __('admin.commission_type') ?> :</b> <span><?= $order['commission_type'] ?></span></li>
        <li><b><?= __('admin.ip') ?> :</b> <span><?= $order['ip'] ?></span></li>
        <li><b><?= __('admin.country_code') ?> :</b> <span><?= $order['country_code'] ?>&nbsp;<img src="<?= base_url('assets/template/images/flags/' . strtolower($order['country_code'])) ?>.png" width="25" height="15"></span></li>
        <li><b><?= __('admin.website') ?> :</b> <span><a href="//<?= $order['base_url'] ?>" target="_blank"><?= $order['base_url'] ?></a></span></li>
        <li><b><?= __('admin.s2s_conversion_source') ?> :</b> <span><?php if($order['script_name'] == 's2s'): ?><span class="badge bg-primary"><i class="fas fa-server me-1"></i><?= __('admin.s2s_source_s2s') ?></span><?php else: ?><?= ucfirst($order['script_name']) ?><?php endif; ?></span></li>

        <?php 
        $custom_data = $order['custom_data'];
        if (is_string($custom_data)) {
            $custom_data = json_decode($custom_data, true);
        }
        if (!empty($custom_data)) {
            foreach ($custom_data as $data) {
                if (is_array($data) && isset($data['key']) && isset($data['value'])) {
                    echo "<li><b>" . htmlspecialchars($data['key']) . ":</b> <span>" . htmlspecialchars($data['value']) . "</span></li>";
                } else if (is_array($custom_data)) {
                    foreach ($custom_data as $k => $v) {
                        echo "<li><b>" . htmlspecialchars($k) . ":</b> <span>" . htmlspecialchars($v) . "</span></li>";
                    }
                    break;
                }
            }
        }
        ?>

        <li><b><?= __('admin.order_created_at') ?> :</b> <span><?= $order['created_at'] ?></span></li>
    </ul>
</div>
<?php } ?>