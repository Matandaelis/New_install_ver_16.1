<?php foreach ($clientslist as $clients) { ?>
  <tr class="client-row" data-client-id="<?= $clients['id'] ?>">
    <td class="fw-bold text-primary">#<?php echo $clients['id']; ?></td>
    <td>
      <div class="d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
          <i class="bi bi-person text-primary"></i>
        </div>
        <div>
          <div class="fw-semibold">
            <?php
            $clientFullName = trim($clients['firstname'] . " " . $clients['lastname']);
            echo !empty($clientFullName) ? $clientFullName : '<i class="text-muted">'.__('admin.no_name').'</i>';
            ?>
          </div>
          <small class="text-muted">@<?php echo $clients['username']; ?></small>
        </div>
      </div>
    </td>
    <td>
      <div class="contact-info">
        <div class="mb-1">
          <i class="bi bi-envelope text-info me-1"></i>
          <a href="mailto:<?php echo $clients['email']; ?>" class="text-decoration-none small">
            <?php echo strlen($clients['email']) > 25 ? substr($clients['email'], 0, 25) . '...' : $clients['email']; ?>
          </a>
        </div>
        <?php if(!empty($clients['phone'])): ?>
        <div>
          <i class="bi bi-telephone text-success me-1"></i>
          <a href="tel:<?php echo $clients['phone']; ?>" class="text-decoration-none small">
            <?php echo $clients['phone']; ?>
          </a>
        </div>
        <?php endif; ?>
      </div>
    </td>
    <td>
      <?php if(!empty($clients['ref_user'])): ?>
        <span class="badge bg-info bg-opacity-75 text-dark">
          <i class="bi bi-person-check me-1"></i><?php echo $clients['ref_user']; ?>
        </span>
      <?php else: ?>
        <span class="text-muted small">
          <i class="bi bi-dash-circle me-1"></i><?= __('admin.direct') ?>
        </span>
      <?php endif; ?>
    </td>
    <td class="text-center">
      <div class="sales-info">
        <div class="fw-bold text-success"><?php echo c_format($clients['amount']); ?></div>
        <small class="text-muted"><?php echo $clients['total_sale']; ?> <?= __('admin.orders') ?></small>
      </div>
    </td>
    <td class="text-center">
      <?php 
      $typeClass = $clients['type'] == 'client' ? 'bg-success' : 'bg-warning';
      $typeIcon = $clients['type'] == 'client' ? 'bi-person-check' : 'bi-person-dash';
      ?>
      <span class="badge <?= $typeClass ?> bg-opacity-75 text-dark">
        <i class="<?= $typeIcon ?> me-1"></i><?php echo __('admin.type_' . $clients['type']); ?>
      </span>
    </td>
    <td class="text-center">
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-info btn-sm viewClientDetails" data-id="<?= $clients['id'] ?>" title="<?= __('admin.view_details') ?>">
          <i class="bi bi-info-circle"></i>
        </button>

        <button class="btn btn-outline-success btn-sm viewShipping" data-id="<?php echo $clients['id']; ?>" title="<?= __('admin.view_shipping_details') ?>">
          <i class="bi bi-truck"></i>
        </button>
        <a class="btn btn-outline-primary btn-sm" href="<?php echo base_url(); ?>admincontrol/addclients/<?php echo $clients['id']; ?>" title="<?= __('admin.edit_client') ?>">
          <i class="bi bi-pencil"></i>
        </a>
        <button class="btn btn-outline-danger btn-sm deleteuser" 
                data-url="<?php echo base_url(); ?>admincontrol/deleteusers/<?php echo $clients['id']; ?>/<?php echo $clients['type']; ?>" 
                data-name="<?= htmlspecialchars($clientFullName) ?>"
                title="<?= __('admin.delete_client') ?>">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    </td>
  </tr>
<?php } ?>

