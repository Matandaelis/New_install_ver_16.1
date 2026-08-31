<tr class="transaction-table-tr <?= ($class == 'child' || $class == 'child-recurring') ? 'child-row' : '' ?> wallet-id-<?= $value['id'] ?> <?= $recurring ? 'recurring recurringof-'.$recurring : '' ?>" group_id='<?= $value['group_id'] ?>' data-id="<?= $value['id'] ?>">
	

	<?php if($recurring){ ?>
		<td class="escape-middle">
			<div class="checkbox-td">
				<label>
					<input type="checkbox" class="wallet-checkbox" value="<?= $value['id'] ?>">
				</label>
			</div>
		</td>
		<td class="text-center p-relative child-arrow-rec escape-middle">
		</td>
	<?php } else { ?>
		<td class="escape-middle">
			<div class="checkbox-td">
				<label>
					<input type="checkbox" class="wallet-checkbox" value="<?= $value['id'] ?>">
				</label>
			</div>
		</td>

	<td class="escape-middle text-center position-relative align-middle <?= $force_class ?>">
		<?php if($has_child && $class != 'child'){ ?>
			<button class="btn btn-primary rounded-circle show-child-transaction" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="<?= __('admin.show_transactions') ?>">
				<i class="fas fa-angle-down"></i>
			</button>
			<div class="child-connector-line" style="position: absolute; left: 50%; top: 100%; width: 3px; height: 20px; background: #0d6efd; transform: translateX(-50%); display: none;"></div>
		<?php } elseif($class == 'child'){ ?>
			<i class="fas fa-level-up-alt fa-rotate-90 text-primary"></i>
		<?php } ?>
	</td>

	<?php }  ?>

<!--Transaction ID td-->
<td class="ps-3">
    <span class="badge bg-secondary text-white"><?= $value['id'] ?></span>
</td>
<!--Transaction ID td-->

<!--Transaction Date td-->
<td>
    <div class="fw-medium"><?= dateFormat($value['created_at'],'d M Y') ?></div>
    <small class="text-muted"><?= dateFormat($value['created_at'],'H:i') ?></small>
</td>
<!--Transaction Date td-->

<!--User Type td-->
<td>
    <?php if($value['is_vendor']) { ?>
        <span class="badge bg-info text-white">
            <i class="fas fa-store me-1"></i>
            <?= __('admin.vendor') ?>
        </span>
    <?php } else { ?>
        <span class="badge bg-primary text-white">
            <i class="fas fa-user-shield me-1"></i>
            <?= __('admin.admin') ?>
        </span>
    <?php } ?>
</td>
<!--User Type td-->

<!--User td-->
<td>
    <div class="fw-medium">
        <?php if($class == 'child' && in_array($value['type'], ['refer_sale_commission', 'refer_action_commission', 'refer_click_commission'])): ?>
            <i class="fas fa-arrow-down text-success me-1" title="<?= __('admin.earned_from_downline') ?>"></i>
        <?php endif; ?>
        <?php echo $value['username']; ?>
    </div>
    <small class="badge <?= $class == 'child' ? 'bg-success' : 'bg-light' ?> text-<?= $class == 'child' ? 'white' : 'dark' ?> <?= $class != 'child' ? 'border' : '' ?>"><?= wallet_whos_commission($value) ?></small>
</td>
<!--User td-->


<!--Campaign td-->
<td>
    <div class="d-flex align-items-center flex-wrap gap-1">
        <span class="badge bg-primary text-white">
            <?= $order_type = wallet_ex_type($value, $class) ?>

            <?php if (!$order_type) { ?>
                <?= wallet_type($value) ?>
            <?php } ?>
        </span>
        
        <?php if (!$value['parent_id'] && $class != "child") {
            if (in_array($value['type'], ['sale_commission', 'admin_sale_commission', 'vendor_sale_commission', 'vendor_shipping_reimbursement']) && in_array($value['comm_from'], ['store', 'ex']) && !empty($value['reference_id_2'])) { ?>
                
                <?php if ($value['integration_orders_total']) { ?>
                    <span class="badge bg-info text-white"><?= c_format($value['integration_orders_total']) ?></span>
                <?php } ?>
                <?php if ($value['local_orders_total']) { ?>
                    <span class="badge bg-success text-white"><?= c_format($value['local_orders_total']) ?></span>
                <?php } ?>
                <?php if ($value['payment_method']) { ?>
                    <span class="badge bg-secondary text-white"><?= payment_method($value['payment_method']) ?></span>
                <?php } ?>
                
				<button class="btn btn-outline-primary btn-sm view-tran-details" data-ref_id_1="<?= $value['reference_id'] ?>" data-ref_id_2="<?= $value['reference_id_2'] ?>" data-comm_from="<?= $value['comm_from'] ?>" title="<?= __('admin.view_details') ?>">
				    <i class="fas fa-info-circle"></i>
				</button>

            <?php }
        } ?>
    </div>
</td>
<!--Campaign td-->


<!-- Commission td -->
<td>
    <div class="d-flex align-items-center">
        <div class="dpopver-content d-none">
            <?php
                list($message, $ip_details) = parseMessage($value['comment'], $value, 'admincontrol', true);
            ?>
        </div>
        <span class="badge bg-<?= is_need_to_pay($value) ? 'danger' : 'success' ?> text-white me-2">
            <?= c_format($value['amount']) ?>
        </span>
        <button class="btn btn-outline-info btn-sm wallet-popover" data-bs-toggle="popover" data-bs-html="true" data-bs-content="<?= htmlspecialchars($message . $ip_details, ENT_QUOTES, 'UTF-8') ?>" title="<?= __('admin.details') ?>">
            <i class="fas fa-info-circle"></i>
        </button>
    </div>
</td>
<!-- Commission td -->



<!-- Commission Type td -->
<td>
	<span class="badge bg-light text-dark border">
		<?= wallet_type($value, 'code') ?>
	</span>
</td>
<!-- Commission Type td -->

<!-- Payment Status td -->
<td>
	<?php
		if($value['user_id'] == 1) {
			echo '<span class="badge bg-success text-white">'.__('admin.paid').'</span>';
		} else {
			if(isset($req_query) && sizeof($req_query) > 0) {
				echo withdrwal_status($req_query['status']);
			} else {
				echo wallet_paid_status($value['status']);
			}
		}
	?>
</td>
<!-- Payment Status td -->

<!-- Status td -->
<td class="text-center">
	<?php
	if($value['user_id'] == 1 && $value['status'] == 1)
		$value['status'] = 3;

	if(!isset($hideStaticStatus)) { 
		$id = (!empty($child_id) && $value['amount'] < 0) ? $child_id : $value['id'];

		$req_query = $this->db->query("SELECT * from wallet_requests WHERE FIND_IN_SET($id,tran_ids)");
		$req_query = $req_query->row_array();

		if($value['amount'] < 0 && ! sizeof($req_query) > 0) {
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
<!-- Status td -->


<!-- Actions td -->
<td>
	<select id="tran-<?=$value['id']?>" class="form-select form-select-sm change-status" 
		onchange="changeStatus(this,'<?=$value['id']?>','<?= $value['status'] ?>');" >

		<option value="" disabled selected><?= __('admin.select_status') ?></option>
		
		<option <?= ($req_query['status'] == 1) ? "disabled" : "" ?>
				value="1" data-type="comission"><?= __('admin.cancel') ?></option>

		<option <?= ($req_query['status'] == 1) ? "disabled" : "" ?>  
				value="2" data-type="comission"><?= __('admin.trash') ?></option>

		<option value="0" data-type="wallet"><?= __('admin.on_hold') ?></option>

		<option value="1" data-type="wallet"><?= __('admin.in_wallet') ?></option>

		<option value="" data-type="remove"><?= __('admin.remove') ?></option>

		<?php if(!$value['parent_id'] && $class != 'child' && $value['is_vendor'] == 0): ?>
			<option value="" data-type="recursion"><?= __('admin.recursion') ?></option>
		<?php endif ?>		
	</select>
</td>
<!-- Actions td -->

<!-- Automation td -->
<td>
	<div class="text-center actions no-wrap">
		<?php if($value['wallet_recursion_id']){ ?>
            <?php if($class != "child"){  ?>
				<button type="button" class="btn btn-outline-secondary btn-sm" title="<?= cycle_details($value['total_recurring'], $value['wallet_recursion_next_transaction'], $value['wallet_recursion_endtime'], $value['total_recurring_amount']) ?>" data-bs-toggle="tooltip" data-id="<?= $value['id'] ?>">
				  <i class="fas fa-cog"></i>
				</button>
			<?php } ?>

		<?php } ?>
		
		 <?php if($value['has_recursion_records'] > 0) { ?>
			<button data-bs-toggle="tooltip" title="<?= __('admin.show_recurring_transition') ?>" class="btn btn-outline-warning btn-sm show-recurring-transition" data-id="<?= $value['id'] ?>">
				<span class="plus">
					<i class="fas fa-plus-circle"></i>
				</span>
				<span class="minus d-none">
					<i class="fas fa-minus-circle"></i>
				</span>
			</button>
	    <?php } ?>
	</div>
</td>
<!-- Automation td -->
</tr>