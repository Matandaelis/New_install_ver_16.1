<div class="row">
	<div class="col-12">
        <div>
            <div>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1"><?= __('admin.page_title_action_notification_details') ?></h5>
                    </div>
                  </a>
                <ul class="list-group">
                    <li class="list-group-item"><?= nl2br($notification_details);?></li>
                    <li class="list-group-item"><?= __('admin.click_id') ?> : <?= $order['click_id'] ?></li>
                    <li class="list-group-item"><?= __('admin.website') ?> : <?= $order['base_url'] ?></li>
                    <li class="list-group-item"><?= __('admin.ip') ?> : <?= $order['flag'] ?> <?= $order['ip'] ?> - <?= $order['country_code'] ?></li>
                    <li class="list-group-item"><?= __('admin.created_at') ?> : <?= $order['created_at'] ?></li>
                    <li class="list-group-item"><?= __('admin.click_type') ?> : <?= $order['click_type'] ?></li>
                    <li class="list-group-item"><?= __('user.page') ?> : <?= $order['link'] ?></li>
                    <li class="list-group-item"><?= __('user.browser') ?> : <?= $order['browserName'] ?> - <?= $order['browserVersion'] ?></li>
                    <li class="list-group-item"><?= __('user.os_platform') ?> : <?= $order['osPlatform'] ?> - <?= __('user.version') ?> : <?= $order['osVersion'] ?></li>
                    <li class="list-group-item"><?= __('user.mobile_name') ?> : <?= $order['mobileName'] ?></li>
                    <?php if(isset($order['time_spent']) && $order['time_spent'] !== null): ?>
                    <li class="list-group-item">
                        <div class="mb-2">
                            <small class="fw-bold text-muted text-uppercase"><i class="far fa-clock me-1"></i><?= __('admin.time_spent') ?></small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-3 bg-light rounded border p-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-sign-in-alt text-primary"></i>
                                <div>
                                    <small class="d-block fw-bold text-muted"><?= __('admin.page_open_time') ?></small>
                                    <small class="text-dark"><?= !empty($order['page_open_time']) ? date("d-m-Y h:i A", strtotime($order['page_open_time'])) : '-' ?></small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-sign-out-alt text-primary"></i>
                                <div>
                                    <small class="d-block fw-bold text-muted"><?= __('admin.page_close_time') ?></small>
                                    <small class="text-dark"><?= !empty($order['page_close_time']) ? date("d-m-Y h:i A", strtotime($order['page_close_time'])) : '-' ?></small>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <?php
                                    $ts = (int)$order['time_spent'];
                                    if($ts >= 3600){
                                        $fmt = floor($ts/3600) . 'h ' . floor(($ts%3600)/60) . 'm ' . ($ts%60) . 's';
                                    } elseif($ts >= 60){
                                        $fmt = floor($ts/60) . 'm ' . ($ts%60) . 's';
                                    } else {
                                        $fmt = $ts . 's';
                                    }
                                ?>
                                <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold"><i class="far fa-clock me-1"></i><?= $fmt ?></span>
                            </div>
                        </div>
                    </li>
                    <?php endif; ?>
                    <li class="list-group-item">
                        <?= __('admin.custom_data') ?>:<br>
                        <?php 
                        $custom_data = $order['custom_data'] ?? '';

                        // Decode if it's a JSON string
                        if (is_string($custom_data)) {
                            $decoded = json_decode($custom_data);
                            $custom_data = is_array($decoded) ? $decoded : [];
                        }

                        if (is_array($custom_data)) {
                            foreach ($custom_data as $value) {
                                if (is_array($value) && isset($value['key'], $value['value'])) {
                                    echo '<b>' . htmlspecialchars($value['key']) . '</b>: ' . htmlspecialchars($value['value']) . '<br>';
                                } elseif (is_object($value) && isset($value->key, $value->value)) {
                                    echo '<b>' . htmlspecialchars($value->key) . '</b>: ' . htmlspecialchars($value->value) . '<br>';
                                }
                            }
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>