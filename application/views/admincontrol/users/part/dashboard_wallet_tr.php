<tr class="transaction-table-tr <?= ($class == 'child' || $class == 'child-recurring') ? 'child-row' : '' ?> wallet-id-<?= $value['id'] ?> <?= $recurring ? 'recurring recurringof-'.$recurring : '' ?>" group_id='<?= $value['group_id'] ?>' data-id="<?= $value['id'] ?>">

	<td class="ps-3">
		<span class="badge bg-secondary text-white"><?= $value['id'] ?></span>
	</td>

	<td>
		<div class="fw-medium"><?= dateFormat($value['created_at'],'d M Y') ?></div>
		<small class="text-muted"><?= dateFormat($value['created_at'],'H:i') ?></small>
	</td>

	<td>
		<?php if($value['is_vendor']){ ?>
			<span class="badge bg-info text-white">
				<i class="fas fa-store me-1"></i><?= __('admin.vendor') ?>
			</span>
		<?php } else { ?>
			<span class="badge bg-primary text-white">
				<i class="fas fa-user-shield me-1"></i><?= __('admin.admin') ?>
			</span>
		<?php } ?>
	</td>

	<td>
		<div class="fw-medium"><?php echo $value['username']; ?></div>
		<small class="badge bg-light text-dark border"><?= wallet_whos_commission($value) ?></small>
	</td>

	<td>
		<div class="d-flex flex-column">
			<?= $order_type = wallet_ex_type($value,$class) ?>
			<?php if(!$order_type){ ?>
				<?= wallet_type($value) ?>
			<?php } ?>
			
			<?php if(!$value['parent_id'] && $class != "child"){
					if(($value['type'] == 'sale_commission' || $value['type'] == 'admin_sale_commission' || $value['type'] == 'vendor_sale_commission' || $value['type'] == 'vendor_shipping_reimbursement') && ($value['comm_from'] == 'store' || $value['comm_from'] == 'ex') && !empty($value['reference_id_2'])){ ?>
					
					<button class="btn btn-outline-info btn-sm mt-1 view-tran-details" data-ref_id_1="<?= $value['reference_id'] ?>" data-ref_id_2="<?= $value['reference_id_2'] ?>" data-comm_from="<?= $value['comm_from'] ?>" title="<?= __('admin.view_details') ?>">
						<i class="fas fa-info-circle"></i>
					</button>
			<?php }
			} ?>

			<?php if($class != 'child' && $class != 'child-recurring'): ?>
				<div class="mt-1">
					<?php if($value['integration_orders_total']){ ?>
						<span class="badge bg-warning text-dark me-1"><?= c_format($value['integration_orders_total']) ?></span>
					<?php } ?>
					<?php if($value['local_orders_total']){ ?>
						<span class="badge bg-success me-1"><?= c_format($value['local_orders_total']) ?></span>
					<?php } ?>
					<?php if($value['payment_method']){ ?>
						<span class="badge bg-secondary me-1"><?= payment_method($value['payment_method']) ?></span>
					<?php } ?>
				</div>
			<?php endif ?>
		</div>
	</td>

	<td>
		<div class="d-flex align-items-center">
			<div class="dpopver-content d-none">
				<?php
					list($message, $ip_details) = parseMessage($value['comment'],$value,'admincontrol',true);
					echo "<div>". $message ."</div>";
				?>
			</div>
			<span class="badge bg-<?= is_need_to_pay($value) ? 'danger' : 'success' ?> text-white me-2">
				<?= c_format($value['amount']) ?>
			</span>
			<button class="btn btn-outline-secondary btn-sm wallet-popover" data-bs-toggle="popover" data-bs-html="true" data-bs-content="" title="<?= __('admin.details') ?>">
				<i class="fas fa-info-circle"></i>
			</button>
		</div>
	</td>

	<td>
		<span class="badge bg-light text-dark border">
			<?= wallet_type($value, 'code') ?>
		</span>
	</td>

	<td class="text-center">
		<?php
		if($value['user_id'] == 1 && $value['status'] == 1)
			$value['status'] = 3;

		if(!isset($hideStaticStatus)) { 
			$id = (!empty($child_id) && $value['amount'] < 0) ? $child_id : $value['id'];

			$req_query = $this->db->query("SELECT * from wallet_requests WHERE FIND_IN_SET($id,tran_ids)");
			$req_query = $req_query->row_array();

			if($value['amount'] < 0 && isset($req_query) && is_array($req_query) && ! sizeof($req_query) > 0) {
				$goups_res = $this->db->query("SELECT id from wallet WHERE group_id=".$value['group_id']."")->result();
				foreach ($goups_res as $res) {
					$req_query = $this->db->query("SELECT * from wallet_requests WHERE FIND_IN_SET(".$res->id.",tran_ids)");
					$req_query = $req_query->row_array();
					if(sizeof($req_query) > 0) {break;}
				}
			}

			if($req_query['status'] != ''){
				$fixed_status = array(2,3,4,5,7,8,9,10,11,12,13);

				if(in_array(intval($req_query['status']), $fixed_status, TRUE)){
					echo withdrwal_status($req_query['status']);
				} else {
					if($value['commission_status'] == 0)
				 		echo $status_icon[$value['status']];
				}	
			} else {
				if($value['commission_status'] == 0)
			 		echo $status_icon[$value['status']];
			}
		 
			echo commission_status($value['commission_status']);
		 } ?>
	</td>

	<td>
		<?php
			if($value['user_id'] == 1) {
				echo '<span class="badge bg-success text-white">' . __('admin.paid') . '</span>';
			} else {
				if(isset($req_query) && sizeof($req_query) > 0) {
					echo withdrwal_status($req_query['status']);
				} else {
					echo wallet_paid_status($value['status']);
				}
			}
		?>
	</td>
</tr>


