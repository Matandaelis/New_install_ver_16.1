<?php
$typeClass = $client['type'] == 'client' ? 'bg-success' : 'bg-warning';
$typeIcon = $client['type'] == 'client' ? 'bi-person-check' : 'bi-person-dash';
?>

<div class="row g-4">
  <!-- Personal Information -->
  <div class="col-md-6">
    <div class="card bg-light h-100">
      <div class="card-body">
        <h6 class="card-title text-primary mb-3">
          <i class="bi bi-person me-1"></i><?= __('admin.personal_information') ?>
        </h6>
        <div class="row g-3">
          <div class="col-6">
            <label class="form-label fw-semibold"><?= __('admin.firstname') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <?php echo !empty($client['firstname']) ? $client['firstname'] : '<i class="text-muted">'.__('admin.not_available').'</i>'; ?>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold"><?= __('admin.lastname') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <?php echo !empty($client['lastname']) ? $client['lastname'] : '<i class="text-muted">'.__('admin.not_available').'</i>'; ?>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold"><?= __('admin.username') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <code>@<?php echo $client['username']; ?></code>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold"><?= __('admin.client_type') ?></label>
            <div>
              <span class="badge <?= $typeClass ?> bg-opacity-75 text-dark fs-6">
                <i class="<?= $typeIcon ?> me-1"></i><?php echo __('admin.type_' . $client['type']); ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Information -->
  <div class="col-md-6">
    <div class="card bg-light h-100">
      <div class="card-body">
        <h6 class="card-title text-success mb-3">
          <i class="bi bi-telephone me-1"></i><?= __('admin.contact_information') ?>
        </h6>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold"><?= __('admin.email') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <a href="mailto:<?php echo $client['email']; ?>" class="text-decoration-none">
                <?php echo $client['email']; ?>
              </a>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold"><?= __('admin.phone') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <?php if(!empty($client['phone'])): ?>
                <a href="tel:<?php echo $client['phone']; ?>" class="text-decoration-none">
                  <?php echo $client['phone']; ?>
                </a>
              <?php else: ?>
                <i class="text-muted"><?= __('admin.not_available') ?></i>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold"><?= __('admin.referrer') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <?php if(!empty($client['ref_user'])): ?>
                <span class="badge bg-info bg-opacity-75 text-dark">
                  <i class="bi bi-person-check me-1"></i><?php echo $client['ref_user']; ?>
                </span>
              <?php else: ?>
                <i class="text-muted"><?= __('admin.direct_registration') ?></i>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Location Information -->
  <div class="col-12">
    <div class="card bg-light">
      <div class="card-body">
        <h6 class="card-title text-info mb-3">
          <i class="bi bi-geo-alt me-1"></i><?= __('admin.location_information') ?>
        </h6>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold"><?= __('admin.country') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <?php
              $countryName = __('admin.not_available');
              if (!empty($client['ucountry'])) {
                  foreach ($countries as $key => $value) {
                      if ($client['ucountry'] == $value->id) {
                          $countryName = $value->name;
                          break;
                      }
                  }
              }
              echo $countryName;
              ?>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold"><?= __('admin.state') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <?php echo !empty($client['state']) ? $client['state'] : '<i class="text-muted">'.__('admin.not_available').'</i>'; ?>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold"><?= __('admin.city') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <?php echo !empty($client['ucity']) ? $client['ucity'] : '<i class="text-muted">'.__('admin.not_available').'</i>'; ?>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold"><?= __('admin.postal_code') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white">
              <?php echo !empty($client['uzip']) ? $client['uzip'] : '<i class="text-muted">'.__('admin.not_available').'</i>'; ?>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold"><?= __('admin.full_address') ?></label>
            <div class="form-control-plaintext border rounded p-2 bg-white" style="min-height: 60px;">
              <?php echo !empty($client['twaddress']) ? nl2br(htmlspecialchars($client['twaddress'])) : '<i class="text-muted">'.__('admin.not_available').'</i>'; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sales Information -->
  <div class="col-12">
    <div class="card bg-light">
      <div class="card-body">
        <h6 class="card-title text-warning mb-3">
          <i class="bi bi-graph-up me-1"></i><?= __('admin.sales_information') ?>
        </h6>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="text-center">
              <div class="display-6 fw-bold text-success"><?php echo c_format($client['amount']); ?></div>
              <small class="text-muted"><?= __('admin.total_sales_amount') ?></small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="text-center">
              <div class="display-6 fw-bold text-primary"><?php echo $client['total_sale']; ?></div>
              <small class="text-muted"><?= __('admin.total_orders_count') ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
