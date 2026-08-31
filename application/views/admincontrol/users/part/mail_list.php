<?php if (!empty($userslist)): ?>
    <?php foreach($userslist as $users): ?>
        <?php
            if(empty($users['amount'])){ $users['amount'] = 0; }
            if(empty($users['click'])){ $users['click'] = 0; }
            if(empty($users['af_click'])){ $users['af_click'] = 0; }
        ?>
        <tr class="user-row">
            <td>
                <div class="form-check">
                    <input class="form-check-input select-single" type="checkbox" value="<?= htmlspecialchars($users['email']) ?>">
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
                            <span class="fw-bold">
                                <?= strtoupper(substr($users['firstname'], 0, 1) . substr($users['lastname'], 0, 1)) ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">
                            <?= htmlspecialchars($users['firstname'] . ' ' . $users['lastname']) ?>
                        </h6>
                        <small class="text-muted">@<?= htmlspecialchars($users['username']) ?></small>
                    </div>
                </div>
            </td>
            <td class="text-center">
                <?php
                    $flag_path = '';
                    if (!empty($users['Country']) && !empty($users['sortname'])) {
                        $flag_path = 'assets/template/images/flags/' . strtolower($users['sortname']) . '.png';
                    }
                ?>
                <div class="d-flex align-items-center justify-content-center">
                    <?php if ($flag_path && file_exists(FCPATH . $flag_path)): ?>
                        <img class="rounded me-2" 
                             src="<?= base_url($flag_path) ?>"
                             alt="<?= htmlspecialchars($users['sortname']) ?>" 
                             width="24" height="18">
                    <?php else: ?>
                        <i class="fa fa-globe text-muted me-2"></i>
                    <?php endif; ?>
                    <span class="badge bg-light text-dark">
                        <?= htmlspecialchars($users['sortname'] ?: 'N/A') ?>
                    </span>
                </div>
                <?php if (!empty($users['Country'])): ?>
                    <small class="text-muted d-block mt-1"><?= htmlspecialchars($users['Country']) ?></small>
                <?php endif; ?>
            </td>
            <td>
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="fa fa-envelope me-2 text-muted"></i>
                        <a href="mailto:<?= htmlspecialchars($users['email']) ?>" class="text-decoration-none">
                            <?= htmlspecialchars($users['email']) ?>
                        </a>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa fa-user me-2 text-muted"></i>
                        <span class="text-muted"><?= htmlspecialchars($users['username']) ?></span>
                    </div>
                </div>
            </td>
            <?php if (!empty($data)): ?>
                <?php 
                $custom_values = json_decode($users['value'] ?? '{}', true) ?: [];
                foreach ($data as $key => $field): 
                    if($field['type'] == 'header') continue; 
                ?>
                    <td class="text-center">
                        <?php 
                        $field_value = $custom_values['custom_'.$field['name']] ?? '';
                        if (!empty($field_value)): 
                        ?>
                            <span class="badge bg-info text-white">
                                <?= htmlspecialchars($field_value) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="100%" class="text-center py-5">
            <div class="text-muted">
                <i class="fa fa-inbox fs-1 mb-3"></i>
                <h5><?= __('admin.no_users_found') ?></h5>
                <p><?= __('admin.try_adjusting_filters') ?></p>
            </div>
        </td>
    </tr>
<?php endif; ?>