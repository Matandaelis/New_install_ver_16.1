<?php $k=1; foreach($userslist as $users){  ?>

	<?php 
		$hasFiles = false;
		$valueStored = json_decode($users['value']); 
		$fieldsShown = [];
	?>

	<?php foreach ($data as $key => $value) { if($value['type'] == 'header') continue; ?>
	<?php 

	

	if( $value['type'] == 'file') {
		if(is_array($valueStored->{'custom_'.$value['name']})) {
			foreach ($valueStored->{'custom_'.$value['name']} as $fileName) {
				if(!empty($fileName)) {
					$hasFiles = true;
				}
			}
		} else {
			if(!empty($valueStored->{'custom_'.$value['name']})) {
				$hasFiles = true;
			}
		}

		if(isset($valueStored->{'custom_'.$value['name']})) {
			array_push($fieldsShown, 'custom_'.$value['name']);
		}
	}
	?>

	<?php } ?>

	<?php

	foreach ($valueStored as $key => $value) {
		if(!in_array($key, $fieldsShown)) {

			if(str_contains($key,'existing') || str_contains($key,'hidden') || !str_contains($key, 'custom')) continue;

			$filecheck = ['png', 'gif', 'jpeg', 'jpg', 'PNG', 'GIF', 'JPEG', 'JPG', 'ICO', 'ico', 'pdf', 'docx', 'doc', 'ppt', 'xls', 'txt'];

			if(is_array($value)) {
				foreach ($value as $v) {
					$v_explode = explode('.', $v);
					if(sizeof($v_explode) == 2 && in_array($v_explode[1], $filecheck)) {
						$hasFiles = true;
					}
				}
			} else {
				$v_explode = explode('.', $value);
				if(sizeof($v_explode) == 2 && in_array($v_explode[1], $filecheck)) {
					$hasFiles = true;
				}
			}
		}
	}


	?>

    <?php
        if(empty($users['amount'])){
            $users['amount'] = 0;
        }

        if(empty($users['click'])){
            $users['click'] = 0;
        }

        if(empty($users['af_click'])){
            $users['af_click'] = 0;
        }
    ?>

	<tr data-user-id="<?= $users['id'] ?>" data-user-display="<?= htmlspecialchars($users['firstname'].' '.$users['lastname'].' ('.$users['username'].') #'.$users['id'], ENT_QUOTES) ?>" draggable="true" class="user-row">

	    <td class="text-center user-drag-cell">
	    	<div class="d-flex flex-column align-items-center justify-content-center gap-1">
	    		<div class="drag-handle" style="cursor: grab;" data-bs-toggle="tooltip" title="<?= __('admin.drag_to_change_parent') ?>">
	    			<i class="fa fa-grip-vertical text-muted opacity-50"></i>
	    		</div>
				<div class="form-check">
					<input class="form-check-input wallet-checkbox" type="checkbox" value="<?= $users['id'] ?>" id="user-<?= $users['id'] ?>">
					<label class="form-check-label visually-hidden" for="user-<?= $users['id'] ?>">
						<?= __('admin.select_user') ?>
					</label>
				</div>
				<small class="text-muted small" style="font-size: 10px;">#<?= $users['id'] ?></small>
				<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#user-details-<?= $k; ?>" aria-expanded="false" aria-controls="user-details-<?= $k; ?>" data-bs-toggle="tooltip" title="<?= __('admin.detailed_information') ?>">
					<i class="fa fa-chevron-down"></i>
				</button>
			</div>
	    </td>

			<td class="align-middle">
		<?php
			$ud_fullname = htmlspecialchars(trim($users['firstname'] . ' ' . $users['lastname']), ENT_QUOTES, 'UTF-8');
			$ud_username = htmlspecialchars((string)$users['username'], ENT_QUOTES, 'UTF-8');
			$ud_email = htmlspecialchars((string)$users['email'], ENT_QUOTES, 'UTF-8');
		?>
		<div class="user-cell-details d-flex align-items-center gap-2 py-1 px-2 rounded-2 border bg-white shadow-sm">
			<div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 align-self-center user-cell-details__avatar">
				<?php
					$first = isset($users['firstname']) ? trim((string)$users['firstname']) : '';
					$last = isset($users['lastname']) ? trim((string)$users['lastname']) : '';
					$username = isset($users['username']) ? trim((string)$users['username']) : '';
					$email = isset($users['email']) ? trim((string)$users['email']) : '';

					$sub = function($s){ return function_exists('mb_substr') ? mb_substr($s,0,1,'UTF-8') : substr($s,0,1); };
					$fi = $first !== '' ? $sub($first) : '';
					$li = $last !== '' ? $sub($last) : '';

					if ($fi === '' && $li === '') {
						$fi = $username !== '' ? $sub($username) : ($email !== '' ? $sub($email) : '?');
						$li = '';
					}

					$initials = strtoupper($fi . ($li !== '' && $li !== $fi ? $li : ''));
					echo htmlspecialchars($initials);
				?>
			</div>
			<div class="min-w-0 flex-grow-1">
				<div class="fw-semibold text-dark text-truncate user-cell-details__name" title="<?= $ud_fullname ?>"><?= $ud_fullname ?></div>
				<div class="user-cell-details__meta d-flex flex-wrap align-items-center gap-1">
					<span class="badge rounded-pill bg-light text-secondary border font-monospace">@<?= $ud_username ?></span>
					<span class="d-inline-flex align-items-center gap-1 min-w-0">
						<i class="fa fa-envelope text-secondary opacity-75 flex-shrink-0" aria-hidden="true"></i>
						<a href="mailto:<?= $ud_email ?>" class="user-cell-details__email text-decoration-none text-secondary text-truncate" title="<?= $ud_email ?>"><?= $ud_email ?></a>
					</span>
				</div>
			</div>
		</div>
	</td>

			<td class="text-center">
		<?php
			if($award_level['status']){
				if($membership['status'] && $users['user_plan_comission_sale_status']){
					if($users['user_plan_level']) {
						echo '<span class="badge bg-primary">' . htmlspecialchars($users['user_plan_level']) . '</span>';
					} else {
						echo '<span class="badge bg-secondary">' . __('admin.default') . '</span>';
					}
				} elseif($users['user_level']){
					echo '<span class="badge bg-info">' . htmlspecialchars($users['user_level']) . '</span>';
				} else {
					echo '<span class="badge bg-secondary">' . __('admin.default') . '</span>';
				}
			} else {
				echo '<span class="badge bg-warning">' . __('admin.award_off') . '</span>';
			}
		?>
	</td>

			<td class="text-center align-middle">
		<div class="user-cell-membership d-flex flex-column align-items-stretch rounded-2 border bg-white shadow-sm text-center">
		<?php
			if(((int)$users['is_vendor'] == 1 && (int)$membership['status'] == 3) || ((int)$users['is_vendor'] != 1 && (int)$membership['status'] == 2)) {
				echo '<span class="badge bg-light text-dark border">'.__('admin.not_available').'</span>';
			} else if($membership['status']){
				$plan = false;

				if($users['reg_approved'] == 0) {
					echo '<div class="badge bg-warning text-dark rounded-pill px-2 py-1"><i class="fa fa-clock-o me-1"></i>'.__('admin.approval_pending').'</div>';
				} else if($users['reg_approved'] == 2) {
					echo '<div class="badge bg-danger rounded-pill px-2 py-1"><i class="fa fa-times me-1"></i>'.__('admin.approval_declined').'</div>';
				} else {
					if($users['membership_plan']) {
						echo '<div class="badge bg-success rounded-pill px-2 py-1 fw-semibold text-wrap lh-sm">' . htmlspecialchars($users['membership_plan']) . '</div>';
						if((int)$users['membership_plan_id'] > 0){ 
							$plan = App\MembershipUser::find($users['membership_plan_id']);
							if($plan){
								echo '<div class="user-cell-membership__ttl text-secondary text-wrap border rounded-2 bg-light">' . htmlspecialchars($plan->remainDay()) . '</div>'; 
							}
						} 
					} else {
						echo '<div class="badge bg-secondary rounded-pill px-2 py-1 text-wrap lh-sm"><i class="fa fa-exclamation-triangle me-1"></i>'.__('admin.plan_not_purchased').'</div>';						
					}
				}

				if($users['reg_approved'] != 0 && $users['reg_approved'] != 2){ ?>
					<div class="pt-1 border-top mt-0">
						<button type="button" class="btn btn-sm btn-primary w-100 shadow-sm" edit-plan-user='<?= $users['id'] ?>' edit-plan-user-type='<?= $users['is_vendor'] ?>'>
							<i class="fa fa-edit me-1"></i><?= __('admin.edit_plan') ?>
						</button>
					</div>
				<?php } else { ?>
					<div class="pt-1 border-top mt-0">
						<div class="btn-group btn-group-sm w-100" role="group">
							<button type="button" class="btn btn-sm btn-outline-success" data-approval-change="1" data-user-id='<?= $users['id'] ?>'>
								<i class="fa fa-check me-1"></i><?= __('admin.approved') ?>
							</button>
							<?php if($users['reg_approved'] != 2) { ?>
								<button type="button" class="btn btn-sm btn-outline-danger" data-approval-change="2" data-user-id='<?= $users['id'] ?>'>
									<i class="fa fa-times me-1"></i><?= __('admin.decline') ?>
								</button>
							<?php } ?>
						</div>
					</div>
				<?php }
			} else {
				echo '<div class="badge bg-warning text-dark rounded-pill px-2 py-1">'.__('admin.membership_off').'</div>';
				$plan = false;

				if($users['reg_approved'] == 0) {
					echo '<div class="badge bg-warning text-dark rounded-pill px-2 py-1"><i class="fa fa-clock-o me-1"></i>'.__('admin.approval_pending').'</div>';
				} else if($users['reg_approved'] == 2) {
					echo '<div class="badge bg-danger rounded-pill px-2 py-1"><i class="fa fa-times me-1"></i>'.__('admin.approval_declined').'</div>';
				}

				if($users['reg_approved'] != 0 && $users['reg_approved'] != 2){ ?>
					
				<?php } else { ?>
					<div class="pt-1 border-top mt-0">
						<div class="btn-group btn-group-sm w-100" role="group">
							<button type="button" class="btn btn-sm btn-outline-success" data-approval-change="1" data-user-id='<?= $users['id'] ?>'>
								<i class="fa fa-check me-1"></i><?= __('admin.approved') ?>
							</button>
							<?php if($users['reg_approved'] != 2) { ?>
								<button type="button" class="btn btn-sm btn-outline-danger" data-approval-change="2" data-user-id='<?= $users['id'] ?>'>
									<i class="fa fa-times me-1"></i><?= __('admin.decline') ?>
								</button>
							<?php } ?>
						</div>
					</div>
				<?php }
			}
		?>
		</div>
	</td>

			<td class="text-center">
		<?php
			if ((!isset($plan) || empty($plan)) || ((int)$users['is_vendor'] == 1 && (int)$membership['status'] == 3) || ((int)$users['is_vendor'] != 1 && (int)$membership['status'] == 2)) {
				echo '<span class="badge bg-light text-dark">'.__('admin.not_available').'</span>';
			} else {
				$status_class = 'bg-secondary';
				if(stripos($plan->status_text, 'active') !== false) $status_class = 'bg-success';
				elseif(stripos($plan->status_text, 'expired') !== false) $status_class = 'bg-danger';
				elseif(stripos($plan->status_text, 'pending') !== false) $status_class = 'bg-warning text-dark';
				$status_text_clean = trim(strip_tags((string)$plan->status_text));
				echo '<span class="badge ' . $status_class . '">' . htmlspecialchars($status_text_clean) . '</span>';
			}  
		?>
	</td>

			<td class="text-center">
		<?php
			$hs = isset($users['health_score']) && $users['health_score'] !== null && $users['health_score'] !== '' ? (float)$users['health_score'] : null;
			$hsTitle = htmlspecialchars(__('admin.health_score_badge_title'), ENT_QUOTES, 'UTF-8');
			if ($hs === null) {
				echo '<span class="badge bg-light text-muted border" title="' . $hsTitle . '">' . __('admin.health_score_not_calculated_label') . '</span>';
			} else {
				if ($hs >= 80) {
					echo '<span class="badge bg-success fw-semibold" title="' . $hsTitle . '">' . number_format($hs, 1) . '</span>';
				} elseif ($hs >= 50) {
					echo '<span class="badge bg-warning text-dark fw-semibold" title="' . $hsTitle . '">' . number_format($hs, 1) . '</span>';
				} else {
					echo '<span class="badge bg-danger fw-semibold" title="' . $hsTitle . '">' . number_format($hs, 1) . '</span>';
				}
			}
		?>
	</td>

			<td class="text-center">
		<?php
			if ($users['Country'] != '') {
				$flag = 'flags/' . strtolower($users['sortname']) . '.png';
				$country_name = htmlspecialchars($users['Country']);
			} else {
				$flag = 'avatar-1.jpg';
				$country_name = __('admin.not_specified');
			}
		?>
		<div class="d-flex align-items-center justify-content-center">
			<img class="rounded-circle border" src="<?php echo base_url(); ?>assets/template/images/<?php echo $flag; ?>" style="width:32px;height: 32px" alt="<?= $country_name ?>" title="<?= $country_name ?>">
			<?php if($users['Country'] != '') { ?>
				<span class="ms-2 small text-muted d-none d-lg-inline"><?= $country_name ?></span>
			<?php } ?>
		</div>
        </td>

        <td class="text-center">
    		<?php 
    		$groups = []; 
    		foreach($users['groups_name'] as $grp) {
    			$groups[] = $grp['group_name'];
    		}
    		if(!empty($groups)) {
    			echo '<div class="d-flex flex-wrap justify-content-center gap-1">';
    			foreach($groups as $group) {
    				echo '<span class="badge bg-info text-dark">' . htmlspecialchars($group) . '</span>';
    			}
    			echo '</div>';
    		} else {
    			echo '<span class="badge bg-light text-muted">'.__('admin.no_group_assigned').'</span>';
    		}
    		?>
        </td>

			<td class="text-center">
		<?php if($users['is_vendor']){ ?>
			<span class="badge bg-success">
				<i class="fa fa-check me-1"></i><?= __('admin.yes') ?>
			</span>
		<?php }else{ ?>
			<span class="badge bg-secondary">
				<i class="fa fa-times me-1"></i><?= __('admin.no') ?>
			</span>
		<?php } ?>
	</td>

			<td>
		<div class="d-flex align-items-center">
			<?php if(!empty($users['under_affiliate'])) { ?>
				<div class="me-2">
					<i class="fa fa-level-up text-success" style="transform: rotate(90deg);" data-bs-toggle="tooltip" title="<?= __('admin.under_affiliate') ?>"></i>
				</div>
				<div>
					<div class="fw-semibold text-success"><?= htmlspecialchars($users['under_affiliate']) ?></div>
					<small class="text-muted"><?= __('admin.parent_affiliate') ?></small>
				</div>
			<?php } else { ?>
				<div class="me-2">
					<i class="fa fa-crown text-warning" data-bs-toggle="tooltip" title="<?= __('admin.top_level_user') ?>"></i>
				</div>
				<div>
					<div class="fw-bold text-primary"><?= __('admin.admin') ?></div>
					<small class="text-muted"><?= __('admin.direct_admin') ?></small>
				</div>
			<?php } ?>
		</div>
	</td>

		<td>
			<div class="d-flex flex-wrap gap-1 justify-content-center">
				<!-- File attachment indicator -->
				<?php if($hasFiles) { ?>
					<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#user-details-<?= $k; ?>" title="<?= __('admin.has_attachments') ?>">
						<i class="fa fa-paperclip"></i>
					</button>
				<?php } ?>

				<!-- Primary Actions -->
				<div class="btn-group" role="group">
					<a href="<?= base_url('admincontrol/downline/'. $users['id']) ?>" 
					   class="btn btn-sm btn-primary" 
					   data-bs-toggle="tooltip" 
					   title="<?= __('admin.view_downline') ?>">
						<i class="fa fa-eye"></i>
					</a>
					<button data-id="<?php echo $users['id'] ?>" 
							class="btn show-tree btn-sm btn-info" 
							data-bs-toggle="tooltip" 
							title="<?= __('admin.quick_view_downline') ?>">
						<i class="fa fa-sitemap"></i>
					</button>
					<a class="btn btn-sm btn-warning" 
					   onclick="return confirmpopup('<?php echo base_url();?>admincontrol/addusers/<?php echo $users['id'];?>');" 
					   href="javascript:void(0)"
					   data-bs-toggle="tooltip" 
					   title="<?= __('admin.edit') ?>">
						<i class="fa fa-edit"></i>
					</a>
				</div>

				<!-- Secondary Actions -->
				<div class="btn-group" role="group">
					<button payment_detail="<?php echo $users['id'] ?>" 
							class="btn btn-sm btn-outline-info" 
							data-bs-toggle="tooltip" 
							title="<?= __('admin.payment_details') ?>">
						<i class="fa fa-credit-card"></i>
					</button>
					<button class="btn btn-sm btn-outline-success btn-login-aff" 
							data-id="<?php echo $users['id'] ?>" 
							data-bs-toggle="tooltip" 
							title="<?= __('admin.login') ?>">
						<i class="fa fa-sign-in"></i>
					</button>
					<?php if($users['status']){ ?>
						<a href="<?= base_url('admincontrol/u_status_toggle/' . $users["id"]) ?>" 
						   class="btn btn-remove btn-sm btn-outline-secondary" 
						   data-bs-toggle="tooltip" 
						   title="<?= __('admin.disable_status') ?>">
							<i class="fa fa-lock"></i>
						</a>
					<?php } else { ?>
						<a href="<?= base_url('admincontrol/u_status_toggle/' . $users["id"]) ?>" 
						   class="btn btn-remove btn-sm btn-outline-success" 
						   data-bs-toggle="tooltip" 
						   title="<?= __('admin.enable_status') ?>">
							<i class="fa fa-unlock"></i>
						</a>
					<?php } ?>
					<button class="btn btn-sm btn-outline-danger btn-delete2" 
							data-id="<?php echo $users['id'] ?>" 
							data-bs-toggle="tooltip" 
							title="<?= __('admin.delete') ?>">
						<i class="fa fa-trash"></i>
					</button>
				</div>
			</div>
		</td>

	</tr>

	<tr class="collapse" id="user-details-<?= $k; ?>">
		<td colspan="11" class="bg-light">
			<div class="card border-0 shadow-sm mx-3 my-2">
				<div class="card-header bg-white border-bottom">
					<h6 class="card-title mb-0 text-primary">
						<i class="fa fa-user-circle me-2"></i>
						<?= __('admin.detailed_information') ?> - <?= htmlspecialchars($users['firstname'] . ' ' . $users['lastname']) ?>
					</h6>
				</div>
				<div class="card-body">
					<!-- Statistics Row -->
					<div class="row mb-4">
						<div class="col-md-3">
							<div class="stat-card bg-primary bg-opacity-10 p-3 rounded">
								<div class="d-flex align-items-center">
									<i class="fa fa-mouse-pointer text-primary me-3 fs-4"></i>
									<div>
										<div class="fw-bold text-primary"><?= number_format((int)$users['click'] + (int)$users['external_click'] + (int)$users['form_click']+ (int)$users['aff_click']) ?></div>
										<small class="text-muted"><?= __('admin.clicks') ?> / <?= c_format($users['click_commission']) ?></small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stat-card bg-info bg-opacity-10 p-3 rounded">
								<div class="d-flex align-items-center">
									<i class="fa fa-hand-pointer-o text-info me-3 fs-4"></i>
									<div>
										<div class="fw-bold text-info"><?= number_format((int)$users['external_action_click']) ?></div>
										<small class="text-muted"><?= __('admin.action_click') ?> / <?= c_format($users['action_click_commission']) ?></small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stat-card bg-success bg-opacity-10 p-3 rounded">
								<div class="d-flex align-items-center">
									<i class="fa fa-dollar text-success me-3 fs-4"></i>
									<div>
										<div class="fw-bold text-success"><?= c_format($users['amount'] + $users['external_sale_amount']) ?></div>
										<small class="text-muted"><?= __('admin.sales_commissions') ?> / <?= c_format($users['sale_commission']) ?></small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stat-card bg-warning bg-opacity-10 p-3 rounded">
								<div class="d-flex align-items-center">
									<i class="fa fa-money text-warning me-3 fs-4"></i>
									<div>
										<div class="fw-bold text-warning"><?= c_format($users['all_commition']) ?></div>
										<small class="text-muted"><?= __('admin.total') ?> <?= __('admin.commissions') ?></small>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Commission Details -->
					<div class="row mb-4">
						<div class="col-md-6">
							<div class="small-stat bg-light p-2 rounded text-center">
								<div class="fw-semibold text-success"><?= c_format($users['paid_commition']) ?></div>
								<small class="text-muted"><?= __('admin.paid_comm') ?></small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="small-stat bg-light p-2 rounded text-center">
								<div class="fw-semibold text-warning"><?= c_format($users['in_request_commiton']) ?></div>
								<small class="text-muted"><?= __('admin.in_request') ?></small>
							</div>
						</div>
					</div>

					<!-- Custom Fields -->
					<div class="row g-3">



					<?php $fieldsShown = [];  ?>

					<?php 
						$mobile_validation_done = false;

						foreach ($data as $key => $value) { 
						
						if($value['type'] == 'header') continue; 


							$mobile_validation = (isset($value['mobile_validation']) && $value['mobile_validation'] ) ? $value['mobile_validation'] : '';

							if($mobile_validation == 'true' && $mobile_validation_done == false) {
								$printableValue = $users['phone'];
								$mobile_validation_done = true;
							} else {
								$printableValue = isset($valueStored->{'custom_'.$value['name']}) ? $valueStored->{'custom_'.$value['name']} : null;
							}



						?>
						<div class="col-sm-6 col-md-4 col-lg-3">
							<div class="custom-field-item p-2 border rounded bg-white">
								<div class="fw-semibold text-primary mb-1"><?= htmlspecialchars($value['label']) ?>:</div>
								<div class="text-muted"><?php 
							
							if( $value['type'] == 'file') {
								if(is_array($valueStored->{'custom_'.$value['name']})) {
									foreach ($valueStored->{'custom_'.$value['name']} as $fileName) {
										echo '<a target="_blank" href="'.base_url().'assets/user_upload/'.$fileName.'">'.$fileName.'</a>';
									}
								} else {
									echo '<a target="_blank" href="'.base_url().'assets/user_upload/'.$valueStored->{'custom_'.$value['name']}.'">'.$valueStored->{'custom_'.$value['name']}.'</a>';
								}

								if(!isset($valueStored->{'custom_'.$value['name']}) || (empty($valueStored->{'custom_'.$value['name']}) && $valueStored->{'custom_'.$value['name']} != 0)) {
									echo __('admin.no_files_uploaded');
								}
							} else {
								if(empty($printableValue) && $printableValue !== 0) {
									echo __('admin.not_available');
								} else {
									echo $printableValue; 
								}
							}
							?>
								</div>
							</div>
						</div>


					<?php 
					if(isset($valueStored->{'custom_'.$value['name']})) {
						array_push($fieldsShown, 'custom_'.$value['name']);
					}

				} 

				?>


				<?php

				foreach ($valueStored as $key => $value) {
					if(!in_array($key, $fieldsShown)) {

						if(str_contains($key,'existing') || str_contains($key,'hidden') || !str_contains($key, 'custom')) continue;

						echo '<div class="col-sm-6 col-md-4 col-lg-3"><div class="custom-field-item p-2 border rounded bg-white"><div class="fw-semibold text-primary mb-1">' . htmlspecialchars(explode('-', $key)[0]) . ':</div><div class="text-muted">';

						$filecheck = ['png', 'gif', 'jpeg', 'jpg', 'PNG', 'GIF', 'JPEG', 'JPG', 'ICO', 'ico', 'pdf', 'docx', 'doc', 'ppt', 'xls', 'txt'];

						if(is_array($value)) {
							foreach ($value as $v) {

								$v_explode = explode('.', $v);

								if(sizeof($v_explode) == 2 && in_array($v_explode[1], $filecheck)) {
									echo '<a target="_blank" href="'.base_url().'assets/user_upload/'.$v.'">'.$v.'</a>';
								} else {
									echo $v;
								}

								
							}
						} else {
							$v_explode = explode('.', $value);

							if(sizeof($v_explode) == 2 && in_array($v_explode[1], $filecheck)) {
								echo '<a target="_blank" href="'.base_url().'assets/user_upload/'.$value.'">'.$value.'</a>';
							} else {
								echo $value;
							}
						}

						if(empty($value) && $value != 0) {
							echo __('admin.no_files_uploaded');
						}

						echo "</div></div></div>";
					}
				}


				?>

				</div>

			</div>

		</td>

	</tr>

<?php $k++; } ?>