<?php 
  $unique_url= base_url().'register/'.base64_encode( $userdetails['id']);
  $store_url = base_url('store/'. base64_encode($userdetails['id']));
?>
<?php foreach($clicks as $index => $order){ ?>
  <?php if($order['type'] == 'store'){ ?>
  <tr class="align-middle">
    <td class="ps-3">
      <div class="d-flex align-items-center">
        <button type="button" class="btn btn-outline-primary btn-sm me-2 toggle-child-tr" title="<?= __('admin.toggle_details') ?>">
          <i class="fas fa-plus"></i>
        </button>
        <span class="badge bg-secondary text-white"><?= $index + $start_from ?></span>
      </div>
    </td>
    <td>
      <span class="badge bg-info text-white"><?= $order['action_id'] ?></span>
    </td>
    <td>
      <div class="text-truncate" title="<?= $unique_url ?>">
        <i class="fas fa-link me-1 text-muted"></i><?= $unique_url ?>
      </div>
    </td>
    <td>
      <div class="d-flex align-items-center">
        <img class="me-2" width="20" height="15" title="<?= $order['country_code'] ?>" src="<?= base_url('assets/template/images/flags/' . strtolower($order['country_code']) . '.png') ?>">
        <div>
          <div class="fw-medium"><?= $order['ip'] ?></div>
          <small class="text-muted"><?= $order['country_code'] ?></small>
        </div>
      </div>
    </td>
    <td>
      <div class="fw-medium"><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
      <small class="text-muted"><?= date('H:i', strtotime($order['created_at'])) ?></small>
    </td>
    <td>
      <span class="badge bg-success text-white">
        <i class="fas fa-store me-1"></i><?= __('admin.store_product_click') ?>
      </span>
    </td>
    <td>
      <div class="text-truncate">
        <?php 
        if (!empty($order['custom_data']) && is_array($order['custom_data'])) {
            foreach ($order['custom_data'] as $item) {
                if (is_array($item)) {
                    if (isset($item['key'], $item['value'])) {
                        echo '<span class="badge bg-light text-dark me-1 mb-1"><b>' . htmlspecialchars($item['key']) . '</b>: ' . htmlspecialchars($item['value']) . '</span><br>';
                    } else {
                        $key = key($item);
                        $value = current($item);
                        echo '<span class="badge bg-light text-dark me-1 mb-1"><b>' . htmlspecialchars($key) . '</b>: ' . htmlspecialchars($value) . '</span><br>';
                    }
                }
            }
        }
        ?>
      </div>
    </td>
  </tr>

  <tr class="detail-tr" style="display: none;">
    <td colspan="7" class="p-0">
      <div class="bg-light border-top p-3">
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-primary mb-3">
              <i class="fas fa-info-circle me-2"></i><?= __('admin.details') ?>
            </h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <span class="badge bg-primary text-white me-2"><?= __('admin.product_id') ?></span>
                <span class="fw-medium"><?= $order['product_id'] ?></span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </td>
  </tr>

  <?php } else if($order['type'] == 'order') { ?>
  <tr class="align-middle">
    <td class="ps-3">
      <div class="d-flex align-items-center">
        <button type="button" class="btn btn-outline-primary btn-sm me-2 toggle-child-tr" title="<?= __('admin.toggle_details') ?>">
          <i class="fas fa-plus"></i>
        </button>
        <span class="badge bg-secondary text-white"><?= $index + $start_from ?></span>
      </div>
    </td>
    <td>
      <span class="badge bg-warning text-dark"><?= $order['id'] ?></span>
    </td>
    <td>
      <div class="text-truncate" title="<?= $store_url ?>">
        <i class="fas fa-shopping-cart me-1 text-muted"></i><?= $store_url ?>
      </div>
    </td>
    <td>
      <div class="d-flex align-items-center">
        <img class="me-2" width="20" height="15" title="<?= $order['country_code'] ?>" src="<?= base_url('assets/template/images/flags/' . strtolower($order['country_code']) . '.png') ?>">
        <div>
          <div class="fw-medium"><?= $order['ip'] ?></div>
          <small class="text-muted"><?= $order['country_code'] ?></small>
        </div>
      </div>
    </td>
    <td>
      <div class="fw-medium"><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
      <small class="text-muted"><?= date('H:i', strtotime($order['created_at'])) ?></small>
    </td>
    <td>
      <span class="badge bg-info text-white">
        <i class="fas fa-shopping-bag me-1"></i><?= __('admin.store_order') ?>
      </span>
    </td>
    <td>
      <div class="text-truncate">
        <?php 
        if (!empty($order['custom_data']) && is_array($order['custom_data'])) {
            foreach ($order['custom_data'] as $item) {
                if (is_array($item)) {
                    if (isset($item['key'], $item['value'])) {
                        echo '<span class="badge bg-light text-dark me-1 mb-1"><b>' . htmlspecialchars($item['key']) . '</b>: ' . htmlspecialchars($item['value']) . '</span><br>';
                    } else {
                        $key = key($item);
                        $value = current($item);
                        echo '<span class="badge bg-light text-dark me-1 mb-1"><b>' . htmlspecialchars($key) . '</b>: ' . htmlspecialchars($value) . '</span><br>';
                    }
                }
            }
        }
        ?>
      </div>
    </td>
  </tr>

  <tr class="detail-tr" style="display: none;">
    <td colspan="7" class="p-0">
      <div class="bg-light border-top p-3">
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-primary mb-3">
              <i class="fas fa-info-circle me-2"></i><?= __('admin.order_details') ?>
            </h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <span class="badge bg-primary text-white me-2"><?= __('user.payment_method') ?></span>
                <span class="fw-medium"><?= $order['payment_method'] ?></span>
              </li>
              <li class="mb-2">
                <span class="badge bg-success text-white me-2"><?= __('user.transaction') ?></span>
                <span class="fw-medium"><?= $order['txn_id'] ?></span>
              </li>
              <li class="mb-2">
                <span class="badge bg-info text-white me-2"><?= __('user.ip') ?></span>
                <span class="fw-medium"><?= $order['ip'] ?></span>
              </li>
            </ul>
          </div>
          <div class="col-md-6">
            <h6 class="text-primary mb-3">
              <i class="fas fa-globe me-2"></i><?= __('admin.location_info') ?>
            </h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <span class="badge bg-warning text-dark me-2"><?= __('user.country_code') ?></span>
                <span class="fw-medium"><?= $order['country_code'] ?></span>
              </li>
              <li class="mb-2">
                <span class="badge bg-secondary text-white me-2"><?= __('user.currency_code') ?></span>
                <span class="fw-medium"><?= $order['currency_code'] ?></span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </td>
  </tr>

  <?php } else  { ?>
  <tr class="align-middle">
    <td class="ps-3">
      <div class="d-flex align-items-center">
        <button type="button" class="btn btn-outline-primary btn-sm me-2 toggle-child-tr" title="<?= __('admin.toggle_details') ?>">
          <i class="fas fa-plus"></i>
        </button>
        <span class="badge bg-secondary text-white"><?= $index + $start_from ?></span>
      </div>
    </td>
    <td>
      <span class="badge bg-primary text-white"><?= $order['id'] ?></span>
    </td>
    <td>
      <div class="text-truncate" title="<?= $order['base_url'] ?>">
        <i class="fas fa-external-link-alt me-1 text-muted"></i><?= $order['base_url'] ?>
      </div>
    </td>
    <td>
      <div class="d-flex align-items-center">
        <img class="me-2" width="20" height="15" title="<?= $order['country_code'] ?>" src="<?= base_url('assets/template/images/flags/' . strtolower($order['country_code']) . '.png') ?>">
        <div>
          <div class="fw-medium"><?= $order['ip'] ?></div>
          <small class="text-muted"><?= $order['country_code'] ?></small>
        </div>
      </div>
    </td>
    <td>
      <div class="fw-medium"><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
      <small class="text-muted"><?= date('H:i', strtotime($order['created_at'])) ?></small>
    </td>
    <td>
      <span class="badge bg-<?= $order['click_type'] == 'Action' ? 'danger' : 'secondary' ?> text-white">
        <i class="fas fa-<?= $order['click_type'] == 'Action' ? 'mouse-pointer' : 'hand-pointer' ?> me-1"></i><?= $order['click_type'] ?>
      </span>
    </td>
    <td>
      <div class="text-truncate">
        <?php if($order['click_type'] == "Action"){ ?>
          <span class="badge bg-danger text-white me-1 mb-1">Action</span>
          <span class="text-muted"><?= $order['base_url'] ?></span><br>
        <?php } else { ?>
          <span class="badge bg-secondary text-white me-1 mb-1"><?= $order['click_type'] ?></span>
          <span class="text-muted"><?= $order['base_url'] ?></span><br>
        <?php } ?>
        <?php 
        if (!empty($order['custom_data']) && is_array($order['custom_data'])) {
            foreach ($order['custom_data'] as $item) {
                if (is_array($item)) {
                    if (isset($item['key'], $item['value'])) {
                        echo '<span class="badge bg-light text-dark me-1 mb-1"><b>' . htmlspecialchars($item['key']) . '</b>: ' . htmlspecialchars($item['value']) . '</span><br>';
                    } else {
                        $key = key($item);
                        $value = current($item);
                        echo '<span class="badge bg-light text-dark me-1 mb-1"><b>' . htmlspecialchars($key) . '</b>: ' . htmlspecialchars($value) . '</span><br>';
                    }
                }
            }
        }
        ?>
      </div>
    </td>
  </tr>

  <tr class="detail-tr" style="display: none;">
    <td colspan="7" class="p-0">
      <div class="bg-light border-top p-3">
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-primary mb-3">
              <i class="fas fa-link me-2"></i><?= __('admin.page_info') ?>
            </h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <span class="badge bg-primary text-white me-2"><?= __('user.page') ?></span>
                <span class="fw-medium"><?= $order['link'] ?></span>
              </li>
            </ul>
          </div>
          <div class="col-md-6">
            <h6 class="text-primary mb-3">
              <i class="fas fa-desktop me-2"></i><?= __('admin.browser_info') ?>
            </h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <span class="badge bg-info text-white me-2"><?= __('user.browser') ?></span>
                <span class="fw-medium"><?= $order['browserName'] ?> <small class="text-muted"><?= $order['browserVersion'] ?></small></span>
              </li>
              <li class="mb-2">
                <span class="badge bg-success text-white me-2"><?= __('user.os_platform') ?></span>
                <span class="fw-medium"><?= $order['osPlatform'] ?> <small class="text-muted"><?= __('user.version') ?>: <?= $order['osVersion'] ?></small></span>
              </li>
              <?php if($order['mobileName']): ?>
              <li class="mb-2">
                <span class="badge bg-warning text-dark me-2"><?= __('user.mobile_name') ?></span>
                <span class="fw-medium"><?= $order['mobileName'] ?></span>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
        <?php if(isset($order['time_spent']) && $order['time_spent'] !== null): ?>
        <div class="border-top pt-3 mt-2">
          <h6 class="text-primary mb-3">
            <i class="far fa-clock me-2"></i><?= __('admin.time_spent') ?>
          </h6>
          <div class="d-flex flex-wrap align-items-center gap-3 bg-white rounded border p-2">
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
        </div>
        <?php endif; ?>
      </div>
    </td>
  </tr>
  <?php } ?>
<?php } ?>