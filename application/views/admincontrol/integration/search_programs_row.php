<?php foreach ($programs as $key => $program) { ?>
<tr id="program-row-<?= $program['id'] ?>">
  <td><span class="badge bg-secondary"><?= $program['id'] ?></span></td>
  <td>
    <div class="fw-bold"><?= htmlspecialchars($program['name']) ?></div>
  </td>
  <td>
    <?php if ($program['username']) { ?>
      <span class="badge bg-info"><?= htmlspecialchars($program['username']) ?></span>
    <?php } else { ?>
      <span class="badge bg-primary"><?= __('admin.admin') ?></span>
    <?php } ?>
  </td>
  <td>
    <?php if ($program['vendor_id']) { ?>
      <div class="small">
        <div class="text-muted"><?= __('admin.admin') ?>:</div>
        <div class="fw-bold">
          <?php if ($program['admin_sale_status']) {
            if ($program['admin_commission_type'] == 'percentage') {
              echo $program['admin_commission_sale'] . '%';
            } else if ($program['admin_commission_type'] == 'fixed') {
              echo c_format($program['admin_commission_sale']);
            } else {
              echo __('admin.not_set');
            }
          } else {
            echo __('admin.not_set');
          } ?>
        </div>
        <div class="text-muted mt-1"><?= __('admin.affiliate') ?>:</div>
        <div class="fw-bold">
          <?php if ($program['sale_status']) {
            if ($program['commission_type'] == 'percentage') {
              echo $program['commission_sale'] . '%';
            } else if ($program['commission_type'] == 'fixed') {
              echo c_format($program['commission_sale']);
            } else {
              echo __('admin.not_set');
            }
          } else {
            echo __('admin.not_set');
          } ?>
        </div>
      </div>
    <?php } else { ?>
      <div class="fw-bold">
        <?php if ($program['sale_status']) {
          if ($program['commission_type'] == 'percentage') {
            echo $program['commission_sale'] . '%';
          } else if ($program['commission_type'] == 'fixed') {
            echo c_format($program['commission_sale']);
          } else {
            echo __('admin.not_set');
          }
        } else {
          echo __('admin.not_set');
        } ?>
      </div>
    <?php } ?>
  </td>
  <td>
    <?php if ($program['vendor_id']) { ?>
      <div class="small">
        <div class="text-muted"><?= __('admin.admin') ?>:</div>
        <div class="fw-bold">
          <?php if ($program['admin_click_status']) {
            if ($program["admin_commission_click_commission"] && $program['admin_commission_number_of_click']) {
              echo c_format($program["admin_commission_click_commission"]) . " " . __('admin.per') . " " . $program['admin_commission_number_of_click'] . " " . __('admin.clicks');
            } else {
              echo __('admin.not_set');
            }
          } else {
            echo __('admin.not_set');
          } ?>
        </div>
        <div class="text-muted mt-1"><?= __('admin.affiliate') ?>:</div>
        <div class="fw-bold">
          <?php if ($program['click_status']) {
            echo c_format($program["commission_click_commission"]) . " " . __('admin.per') . " " . $program['commission_number_of_click'] . " " . __('admin.clicks');
          } else {
            echo __('admin.not_set');
          } ?>
        </div>
      </div>
    <?php } else { ?>
      <div class="fw-bold">
        <?php if ($program['click_status']) {
          echo c_format($program["commission_click_commission"]) . " " . __('admin.per') . " " . $program['commission_number_of_click'] . " " . __('admin.clicks');
        } else {
          echo __('admin.not_set');
        } ?>
      </div>
    <?php } ?>
  </td>
  <td>
    <?php if ($program['vendor_id']) { ?>
      <div class="small">
        <div class="text-muted"><?= __('admin.admin') ?>:</div>
        <span class="badge <?= $program['admin_sale_status'] ? 'bg-success' : 'bg-danger' ?>">
          <?= $program['admin_sale_status'] ? __('admin.enable') : __('admin.disable') ?>
        </span>
        <div class="text-muted mt-1"><?= __('admin.affiliate') ?>:</div>
        <span class="badge <?= $program['sale_status'] ? 'bg-success' : 'bg-danger' ?>">
          <?= $program['sale_status'] ? __('admin.enable') : __('admin.disable') ?>
        </span>
      </div>
    <?php } else { ?>
      <span class="badge <?= $program['sale_status'] ? 'bg-success' : 'bg-danger' ?>">
        <?= $program['sale_status'] ? __('admin.enable') : __('admin.disable') ?>
      </span>
    <?php } ?>
  </td>
  <td>
    <?php if ($program['vendor_id']) { ?>
      <div class="small">
        <div class="text-muted"><?= __('admin.admin') ?>:</div>
        <span class="badge <?= $program['admin_click_status'] ? 'bg-success' : 'bg-danger' ?>">
          <?= $program['admin_click_status'] ? __('admin.enable') : __('admin.disable') ?>
        </span>
        <div class="text-muted mt-1"><?= __('admin.affiliate') ?>:</div>
        <span class="badge <?= $program['click_status'] ? 'bg-success' : 'bg-danger' ?>">
          <?= $program['click_status'] ? __('admin.enable') : __('admin.disable') ?>
        </span>
      </div>
    <?php } else { ?>
      <span class="badge <?= $program['click_status'] ? 'bg-success' : 'bg-danger' ?>">
        <?= $program['click_status'] ? __('admin.enable') : __('admin.disable') ?>
      </span>
    <?php } ?>
  </td>
  <td><?= program_status($program['status']) ?></td>
  <td class="text-center">
    <div class="btn-group" role="group">
      <a class="btn btn-outline-primary btn-sm" href="<?= base_url('integration/programs_form/' . $program['id']) ?>" title="<?= __('admin.edit') ?>">
        <i class="fas fa-edit"></i>
      </a>
      <button <?= $program['associate_programns'] ? 'disabled' : '' ?> 
              class="btn btn-outline-danger btn-sm delete-program" 
              data-id="<?= $program['id'] ?>" 
              title="<?= __('admin.delete') ?>">
        <i class="fas fa-trash"></i>
      </button>
    </div>
  </td>
</tr>
<?php } ?>