<div class="row">
    <div class="col-12">
        <div>
            <div>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= __('user.page_title_order_notification_details') ?></h5>
                    </div>
                </a>
                <ul class="list-group">
                    <li class="list-group-item"><?= nl2br($notification_details); ?></li>
                    <li class="list-group-item"><?= __('user.product_ids') ?> : <?= htmlspecialchars($order['product_ids']) ?></li>
                    <li class="list-group-item"><?= __('user.total') ?> : <?= htmlspecialchars($order['total']) ?></li>
                    <li class="list-group-item"><?= __('user.currency') ?> : <?= htmlspecialchars($order['currency']) ?></li>
                    <li class="list-group-item"><?= __('user.commission_type') ?> : <?= htmlspecialchars($order['commission_type']) ?></li>
                    <li class="list-group-item"><?= __('user.ip') ?> : <?= htmlspecialchars($order['ip']) ?></li>

                    <li class="list-group-item">
                        <?= __('user.country_code') ?>: 
                        <?= htmlspecialchars($order['country_code']) ?>
                        &nbsp;
                        <img 
                            title="<?= htmlspecialchars($order['country_code']) ?>" 
                            src="<?= base_url('assets/template/images/flags/' . strtolower($order['country_code'])) ?>.png" 
                            width="25" 
                            height="15"
                        >
                    </li>

                    <li class="list-group-item">
                        <?= __('user.website') ?>: 
                        <a href="<?= htmlspecialchars($order['base_url']) ?>" target="_blank">
                            <?= htmlspecialchars($order['base_url']) ?>
                        </a>
                    </li>

                    <li class="list-group-item"><?= __('user.script_name') ?> : <?= htmlspecialchars(ucfirst($order['script_name'])) ?></li>

                    <li class="list-group-item">
                        <?= __('user.custom_data') ?> : <br>
                        <?php
                        $custom_data = $order['custom_data'];
                        if (is_string($custom_data)) {
                            $custom_data = json_decode($custom_data, true);
                        }

                        if (!empty($custom_data) && is_array($custom_data)) {
                            foreach ($custom_data as $value) {
                                if (is_array($value) && isset($value['key'], $value['value'])) {
                                    echo '<b>' . htmlspecialchars($value['key']) . '</b>: ' . htmlspecialchars($value['value']) . '<br>';
                                }
                            }
                        } else {
                            echo '<small class="text-muted">(' . __('user.no_data_found') . ')</small>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>