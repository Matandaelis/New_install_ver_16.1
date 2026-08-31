<div class="row">
    <div class="col-12">
        <div>
            <div>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= __('user.page_title_click_or_action_notification_details') ?></h5>
                    </div>
                </a>
                <ul class="list-group">
                    <li class="list-group-item"><?= nl2br($notification_details); ?></li>
                    <li class="list-group-item"><?= __('user.click_id') ?> : <?= htmlspecialchars($order['click_id']) ?></li>
                    <li class="list-group-item"><?= __('user.website') ?> : <?= htmlspecialchars($order['base_url']) ?></li>
                    <li class="list-group-item"><?= __('user.ip') ?> : <?= htmlspecialchars($order['flag']) ?> <?= htmlspecialchars($order['ip']) ?> - <?= htmlspecialchars($order['country_code']) ?></li>
                    <li class="list-group-item"><?= __('user.created_at') ?> : <?= htmlspecialchars($order['created_at']) ?></li>
                    <li class="list-group-item"><?= __('user.click_type') ?> : <?= htmlspecialchars($order['click_type']) ?></li>
                    <li class="list-group-item"><?= __('user.page') ?> : <?= htmlspecialchars($order['link']) ?></li>
                    <li class="list-group-item"><?= __('user.browser') ?> : <?= htmlspecialchars($order['browserName']) ?> - <?= htmlspecialchars($order['browserVersion']) ?></li>
                    <li class="list-group-item"><?= __('user.os_platform') ?> : <?= htmlspecialchars($order['osPlatform']) ?> - <?= __('user.version') ?> : <?= htmlspecialchars($order['osVersion']) ?></li>
                    <li class="list-group-item"><?= __('user.mobile_name') ?> : <?= htmlspecialchars($order['mobileName']) ?></li>

                    <li class="list-group-item">
                        <?= __('user.custom_data') ?>:<br>
                        <?php
                        $custom_data = $order['custom_data'];
                        if (is_string($custom_data)) {
                            $custom_data = json_decode($custom_data, true);
                        }

                        if (!empty($custom_data) && is_array($custom_data)) {
                            foreach ($custom_data as $item) {
                                if (is_array($item) && isset($item['key'], $item['value'])) {
                                    echo '<b>' . htmlspecialchars($item['key']) . '</b>: ' . htmlspecialchars($item['value']) . '<br>';
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