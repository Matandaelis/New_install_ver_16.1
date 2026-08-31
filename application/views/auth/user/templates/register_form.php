<?php
$is_vendor = isset($user) ? (int)$user['is_vendor'] : 0;
$is_vendor_registration = (isset($is_vendor_registration) && $is_vendor_registration) ? 1 : $is_vendor;
?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/datetimepicker/jquery.datetimepicker.min.css') ?>" />

<script src="<?php echo base_url('assets/plugins/datetimepicker/jquery.datetimepicker.full.min.js') ?>" type="text/javascript"></script>


<?php if(!isset($edit_view) && $registration_status != 0){ ?>
<div class="card aff-reg-tabs-card">
    <div class="list-group list-group-horizontal aff-reg-tabs" id="list-tab" role="tablist">
        <?php if($registration_status == 1 || $registration_status == 3): ?>
            <a class="list-group-item list-group-item-action <?= (isset($is_vendor_registration) && $is_vendor_registration) ? "" : "active"; ?>" href="<?php echo base_url('register') ?>" role="tab" data-registartion_type="aff"><?= __('front.affiliate') ?></a>
        <?php endif ?>
        
        <?php if((int)$vendor_storestatus!=0 || (int)$vendor_marketstatus!=0 ) { 
            if($registration_status == 1 || $registration_status == 2): ?>
                <a class="list-group-item list-group-item-action <?= ((isset($is_vendor_registration) && $is_vendor_registration) || $registration_status == 2) ? "active" : ""; ?>" href="<?php echo base_url('register/vendor') ?>" role="tab" data-registartion_type="ven"><?= __('front.vendor') ?></a>
            <?php endif ?>
        <?php } ?>
    </div>
</div>
<?php } ?>


<form action="" method="POST" role="form" class="reg_form p-3" enctype="multipart/form-data">
	<div id="preventAutoLoad" style="position: fixed; top: -100%;">
 	 <input type="password" autocomplete="password" />
	 <input type="text" autocomplete="username" />
    </div>

	<input type="hidden" name="is_vendor" value="<?= (isset($is_vendor_registration) && $is_vendor_registration) ? 1 : 0; ?>">
	<input type="hidden" name="affiliate_cookie" id="affiliate_cookie" value="-1">
	
	<script type="text/javascript">
		var tel_input = false;
	</script>
	
	<?php
		$db =& get_instance(); 
		$products = $db->Product_model; 
	    $googlerecaptcha =$db->Product_model->getSettings('googlerecaptcha');	


		$fields = array();
		$email = isset($user) ? $user['email'] : '';
		$fields['email'] = '<div class="mb-3 position-relative">
		    <input type="email" id="email" name="email" 
		        placeholder="' . __('user.email') . '" 
		        class="form-control custom_input" 
		        value="' . $email . '" 
		        autocomplete="off">
		    <ul id="email-suggestions" class="list-group position-absolute w-100 d-none overflow-auto" 
		        style="max-height: 250px; z-index: 1000;"></ul>
		</div>';

		if(isset($read_only_user_membership_plan)){
		    if($membership['status']){
		        if($userPlan['name']) {
		            $user_membership_plan_value = $userPlan['name'];
		        } else {
		            $user_membership_plan_value = 'Not available';
		        }
		    } else {
		        $user_membership_plan_value = 'Off'; 
		    }

		    $fields['user_membership_plan'] = '
		    <div class="mb-3">
		        <label for="user_membership_plan">'. __('admin.user_membership_plan') .'</label>
		        <input readonly type="text" class="form-control custom_input" id="user_membership_plan" value="'.$user_membership_plan_value.'">
		    </div>';
		}

		if (isset($allow_vendor_option)) {
		    $is_vendor = isset($user) ? (int)$user['is_vendor'] : 0;
		    $fields['is_vendor'] = '
		    <div class="mb-3">
		        <div class="row gx-5">
		            <div class="col-auto">
		                <label class="form-label">'. __('admin.vendorstatus') .'</label>
		            </div>
		            <div class="col-auto">
		                <div class="form-check form-switch">
		                    <input name="is_vendor" class="form-check-input update_all_settings" type="checkbox" '. ($is_vendor == 1 ? 'checked' : '') .'>
		                </div>
		            </div>
		        </div>
		    </div>';
		}

		$firstname = isset($user) ? $user['firstname'] : '';
		$fields['firstname'] = '
		<div class="mb-3">
		    <input type="text" 
		           name="firstname" 
		           id="firstname" 
		           class="form-control custom_input" 
		           placeholder="'. __('user.first_name') .'" 
		           value="'. $firstname .'" 
		           data-label="'. __('user.first_name') .'" 
		           data-too-short="'. __('front.too_short') .'" 
		           required>
		    <small id="firstname-hint" class="form-text mt-1"></small>
		</div>';

		$lastname = isset($user) ? $user['lastname'] : '';
		$fields['lastname'] = '
		<div class="mb-3">
		    <input type="text" 
		           name="lastname" 
		           id="lastname" 
		           class="form-control custom_input" 
		           placeholder="'. __('user.last_name') .'" 
		           value="'. $lastname .'" 
		           data-label="'. __('user.last_name') .'" 
		           data-too-short="'. __('front.too_short') .'" 
		           required>
		    <small id="lastname-hint" class="form-text mt-1"></small>
		</div>';

		$username = isset($user) ? $user['username'] : '';
		$disabled_username = isset($disable_username) ? 'disabled' : '';
		$fields['username'] = '
		<div class="mb-3 position-relative">
		    <input type="text" name="username" id="username" class="form-control custom_input" 
		        placeholder="'. __('user.username') .'" value="'. $username .'" '.$disabled_username.' autocomplete="off">
		    <small id="username-check" class="form-text mt-1"></small>
		</div>';


	$fields['password'] = '
	<div class="mb-3">
	    <div class="input-group">
	        <input type="password" name="password" id="password"
	               placeholder="'. __('user.password') .'" 
	               class="form-control custom_input" 
	               aria-describedby="password-strength">
	        <button class="btn btn-outline-secondary aff-toggle-pw" type="button" 
	                onclick="togglePassword(\'password\', this)"><i class="bi bi-eye"></i></button>
	    </div>
	    <small id="password-strength" class="form-text mt-1 d-block"></small>
	</div>';

	$fields['confirm_password'] = '
	<div class="mb-3">
	    <div class="input-group">
	        <input type="password" name="cpassword" id="cpassword"
	               placeholder="'. __('user.repeat_password') .'" 
	               class="form-control custom_input" 
	               aria-describedby="match-check">
	        <button class="btn btn-outline-secondary aff-toggle-pw" type="button" 
	                onclick="togglePassword(\'cpassword\', this)"><i class="bi bi-eye"></i></button>
	    </div>
	    <small id="match-check" class="form-text mt-1 d-block"></small>
	</div>';

		$customValue = json_decode(isset($user['value']) ? $user['value'] : '[]', 1);

		$systemPhoneInput = false;
	?>

	<?php foreach ($data as $key => $value) { 

		if((!isset($edit_view) || !$edit_view) && isset($value['hide_on_registration']) && $value['hide_on_registration']) continue;
		  
		$required    = (isset($value['required']) && $value['required'] == 'true') ? 'required="required"' : '';
		$label       = (isset($value['label']) && $value['label'] ) ? $value['label'] : '';
		$placeholder = (isset($value['placeholder']) && $value['placeholder'] ) ? $value['placeholder'] : $value['label'];
		$className   = (isset($value['className']) && $value['className'] ) ? $value['className'] : '';
		$name        = 'custom_'.((isset($value['name']) && $value['name'] ) ? $value['name'] : '');
		$ivalue      = (isset($value['value']) && $value['value'] ) ? $value['value'] : (isset($customValue[$name]) ? $customValue[$name] : '');
		$maxlength   = (isset($value['maxlength']) && $value['maxlength'] ) ? $value['maxlength'] : '';
		$min         = (isset($value['min']) && $value['min'] ) ? $value['min'] : '';
		$max         = (isset($value['max']) && $value['max'] ) ? $value['max'] : '';
		$mobile_validation         = (isset($value['mobile_validation']) && $value['mobile_validation'] ) ? $value['mobile_validation'] : '';
		$multiple_files         = (isset($value['multiple']) && $value['multiple'] ) ? 'multiple' : '';
		$_customValue = $ivalue;

		switch ($value['type']) {
			case 'header': 
				echo  $fields[strtolower($label)]; 
				if($label == 'Email' && isset($read_only_user_membership_plan)){
					echo  $fields['user_membership_plan']; 
				}
				if($label == 'Email' && isset($allow_vendor_option)){
					echo  $fields['is_vendor']; 
				}
			break;
			case 'text':
				if($mobile_validation == 'true'){ ?>
					<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
					<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>

					<?php if($systemPhoneInput === false) { ?>
						<div class="mb-3">
						    <div class="position-relative rounded reg-phone-field-wrap">
						        <input type="hidden" name="PhoneNumberInput" id="phonenumber-input" value="" class="form-control" placeholder="<?= __('store.phone_number') ?>">
						        <input 
						            onkeypress="return isNumberKey(event);" 
						            id="phone_<?= $key ?>" 
						            class="form-control tel_input <?= $className ?>" 
						            name="phone" 
						            type="text" 
						            value="<?= $user['phone'] ?>" 
						            <?= $required ?>>
						    </div>
						</div>
					<?php $systemPhoneInput = true;
						} else {
				    ?>
					<div class="form-group">
					    <div>
					        <input onkeypress="return isNumberKey(event);" id="phone_<?= $key ?>" class="form-control custom_input tel_input <?= $className ?>" placeholder="<?= $placeholder ?>"  name="<?= $name ?>" type="text" value="<?= $ivalue ?>" <?= $required ?>>
					    </div>
					</div>
					<?php } ?>

				<!--Phone JS-->
				<script type="text/javascript">
					$(document).ready(function () {
						if (typeof intlTelInput === 'undefined') return;
						window.tel_inputphone_<?= $key ?> = intlTelInput(document.querySelector("#phone_<?= $key ?>"), {
								initialCountry: "auto",
								utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
								separateDialCode: true,
								geoIpLookup: function (success, failure) {
									$.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
										var countryCode = (resp && resp.country) ? resp.country : "US";
										success(countryCode);
									});
								},
							});

							// Fix dropdown width; light mode uses white list + dark text, dark mode relies on front-dark-mode-base.css
							const styleItiDropdown_<?= $key ?> = function () {
								const $input = $('#phone_<?= $key ?>');
								const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
								$('.iti__country-list').each(function () {
									const $list = $(this);
									$list.css({
										'z-index': '1060',
										'width': $input.outerWidth() + 'px'
									});
									if (isDark) {
										$list.removeClass('bg-white text-dark').addClass('shadow');
										$list.find('.iti__country').removeClass('text-dark');
									} else {
										$list.addClass('bg-white text-dark shadow');
										$list.find('.iti__country').addClass('text-dark');
									}
								});
							};
							const dropdownObserver = new MutationObserver(styleItiDropdown_<?= $key ?>);
							document.addEventListener('front-theme-changed', styleItiDropdown_<?= $key ?>);
							dropdownObserver.observe(document.body, {
								childList: true,
								subtree: true,
							});
						});
					</script>
					<!--Phone JS-->

				<?php } else { ?>
					<div class="form-group">
						<input type="text" name='<?= $name ?>' id="<?= $name ?>" value="<?= $ivalue ?>" class="<?= $className ?>" placeholder="<?= $placeholder ?>" <?= $required ?> maxlength = '<?= $maxlength ?>' >
					</div>
				<?php }
				break;
			case 'autocomplete': ?>
				<div class="form-group">
					<input type="text" name='<?= $name ?>' id="<?= $name ?>" value="<?= $ivalue ?>" class="<?= $className ?> autocomplete" placeholder="<?= $placeholder ?>" <?= $required ?> maxlength = '<?= $maxlength ?>' >
				</div>
			<?php
			break;			
			case 'number': ?>
				<div class="form-group">
					<input type="number" name="<?= $name ?>" id="<?= $name ?>" class="<?= $className ?>" value="<?= $ivalue ?>" min="<?= $min ?>" max="<?= $max ?>"  <?= $required ?> placeholder="<?= $label ?>">
				</div>
			<?php
			break;
			case 'hidden': ?>
					<input type="hidden" name="<?= $name ?>" id="<?= $name ?>" class="<?= $className ?>" value="<?= $ivalue ?>" placeholder="<?= $label ?>">
			<?php
			break;
			case 'paragraph': ?>
			<div class="form-group">
				<textarea name="<?= $name ?>" id="<?= $name ?>" class="form-control <?= $className ?>" rows="3" <?= $required ?> maxlength = '<?= $maxlength ?>' placeholder="<?= $label ?>"><?= $ivalue ?></textarea>
			</div>
			<?php
			break;
			case 'textarea': ?>
			<div class="form-group">
				<textarea name="<?= $name ?>" id="<?= $name ?>" class="<?= $className ?>" rows="3" <?= $required ?> maxlength = '<?= $maxlength ?>' placeholder="<?= $label ?>"><?= $ivalue ?></textarea>
			</div>
			<?php
			break;
			case 'date': ?>
			 <div class="form-group">
			        <div class="input-group date" data-provide="datepicker">
					    <input type="text" class="form-control custom_input <?= $className ?> datetimepicker" name="<?= $name ?>" value="<?= $ivalue ?>" placeholder="<?= $placeholder ?>" <?= $required ?>>
					    <div class="input-group-addon">
					        <span class="glyphicon glyphicon-th"></span>
					    </div>
					</div>
	          </div>
			<?php
			break;
			case 'checkbox-group':
			if(isset($value['values'])){

				echo '<div id="'.$name.'>" class="form-group text-left"><label>'.$label.'</label><br/>';
				foreach ($value['values'] as $k => $v) {
					$label = (isset($v['label']) && $v['label'] ) ? $v['label'] : '';
					$ivalue = (isset($v['value']) && $v['value'] ) ? $v['value'] : '';
					$checked = '';
					if(isset($edit_view) && in_array($ivalue, $_customValue)) {
						$checked = "checked='checked'";
					} else if( !isset($edit_view) && isset($v['checked']) && $v['checked']){
						$checked = "checked='checked'";
					}
                ?>
    			<div class="checkbox mr-2" style="display:inline-block;">
    		        <label>
    		          <input type="checkbox" name="<?= $name ?>[]" value="<?=$ivalue;?>" class="<?= $className ?>" <?= $checked ?>>
    		          <span class="box"></span>
    		          <?= $label; ?>
    		        </label>
    	      	</div>
			<?php } ?>
			</div>
			<?php } 
			break;
			case 'radio-group':
			if(isset($value['values'])){
				echo '<div class="form-group text-left" id="'.$name.'"><label>'.$label.'</label><br/>';
				foreach ($value['values'] as $k => $v) {
					$label = (isset($v['label']) && $v['label'] ) ? $v['label'] : '';
					$ivalue = (isset($v['value']) && $v['value'] ) ? $v['value'] : '';
					$checked = '';
					if(isset($edit_view) && $_customValue == $ivalue) {
						$checked = "checked='checked'";
					} else if( !isset($edit_view) && isset($v['checked']) && $v['checked']){
						$checked = "checked='checked'";
					}
			 ?>

				  <label class="radio-inline mr-2">
					  <input type="radio" name="<?= $name ?>" value="<?= $ivalue ?>" <?= $checked ?>><span class="indicator"></span> <?= $label ?>
					</label>
			<?php } ?>
			</div>
			<?php } 
			break;
			case 'select':
			if(isset($value['values'])){ ?>
				<div class="form-group">
				 	<select name="<?= $name ?>" id="<?= $name ?>" class="form-control custom_input <?= $class ?>">
				 		<option><?= $label ?></option>
				 		<?php 
				 
				 			foreach ($value['values'] as $k => $v) {
							$label = (isset($v['label']) && $v['label'] ) ? $v['label'] : '';
							$ivalue = (isset($v['value']) && $v['value'] ) ? $v['value'] : '';
							$selected = '';
							if(isset($edit_view) && $_customValue == $ivalue) {
								$selected = "selected='selected'";
							} else if( !isset($edit_view) && isset($v['selected']) && $v['selected']){
								$selected = "selected='selected'";
							}
				 		?>
				 		<option value="<?= $ivalue ?>" <?= $selected ?>><?= $label ?></option>
						<?php } ?>
				 	</select>
				</div>
			<?php } 
			break;
			case 'file':
				?>
				<div class="form-group">
					<label class="text-left d-block"><?= $label ?></label>
					<input type="file" name='<?= $name ?><?= ($multiple_files != '') ? '[]' : ''; ?>' id="<?= $name ?>" class="<?= $className ?>" <?= $multiple_files; ?>/>
				</div>
				<?php if(isset($edit_view) && !empty($ivalue)){

					if(is_array($ivalue)) {
						?>
						<ul class="list-group list-group-flush mb-4">
							<?php foreach ($ivalue as $v) { 
								if(!empty($v)) {
								?>
							<li class="list-group-item d-flex justify-content-between align-items-center">
							    <a target="_blank" href="<?= base_url(); ?>assets/user_upload/<?= $v; ?>"><?= $v; ?></a>
							    <span class="badge bg-danger badge-pill" style="cursor: pointer;" onclick="return this.parentNode.remove();">Delete</span>
							    <input type="hidden" name="existing_<?= $name ?>[]" value="<?= $v; ?>"/>
						  	</li>
							<?php }
							} ?>
						</ul>
						<?php
					} else {
						?>
						<ul class="list-group list-group-flush mb-4">
							<li class="list-group-item d-flex justify-content-between align-items-center">
							    <a target="_blank" href="<?= base_url(); ?>assets/user_upload/<?= $ivalue; ?>"><?= $ivalue; ?></a>
							    <span class="badge bg-danger badge-pill" style="cursor: pointer;" onclick="return this.parentNode.remove();">Delete</span>
    						  	<input type="hidden" name="existing_<?= $name ?>" value="<?= $ivalue; ?>"/>
						  	</li>
						</ul>
						<?php
					}

				} ?>


				<?php
			break;
			default:
				echo $value['type'];
				break;
		} ?>
	<?php } ?>

	<?php if($vendor_storestatus) { ?>
    <div class="form-group store_fields" <?= (isset($is_vendor_registration) && $is_vendor_registration) ? "" : "style=\"display: none;\""; ?>>
        <input type="text" id="store_name" name="store_name" placeholder="<?= __('user.your_store_name') ?>" class="form-control custom_input" value="<?= (isset($user) && !empty($user['store_name'])) ? $user['store_name'] : '' ?>">  
    </div>
	<?php } ?>

	<?php if(isset($edit_view_refer)){ ?>
		<div class="form-group">
			<label class="control-label"><?= __('admin.Under_Affiliate') ?></label>
			<select class="form-control custom_input" name="refid">
				<option value="0"> <?= __('admin.none') ?> </option>
				<?php foreach ($refer_users as $key => $value) { ?>
					<option <?= (isset($user) && $user['refid'] == $value['id']) ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['username'] ?></option>
				<?php } ?>
			</select>
		</div>
	<?php } ?>
	
	<?php if(isset($edit_view_level)){ ?>
		<div class="mb-3">
			<label class="control-label"><?= __('admin.user_level') ?></label>
			<?php if($award_level['status']){ ?>
				<?php if($membership['status'] && $userPlan['commission_sale_status']){ ?>
					<?php if($userPlan['level_number']){ ?>
						<input disabled type="text"class="form-control custom_input" value="<?= $userPlan['level_number'] ?>">
					<?php } else { ?>
						<input disabled type="text"class="form-control custom_input" value="Default">
					<?php } ?>
				<?php } else { ?>
					<select class="form-control custom_input" name="level_id">
						<option value=""> <?= __('admin.none') ?> </option>
						<option <?= (isset($user) && $user['level_id'] == 0) ? 'selected' : '' ?> value="0">Default</option>
						<?php foreach ($levels as $key => $value) { ?>
							<option <?= (isset($user) && $user['level_id'] == $value['id']) ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['level_number'] ?></option>
						<?php } ?>
					</select>
				<?php } ?>
			<?php } else { ?>
				<input disabled type="text"class="form-control custom_input" value="Off">
			<?php } ?>	
		</div>
	<?php } ?>
	
	<?php if(isset($edit_view)){ ?>
		<div class="mb-3">
			<label class="control-label"><?= __('admin.country') ?></label>
			<select class="form-control custom_input" name="country_id">
				<option value="0"> <?= __('admin.none') ?> </option>
				<?php foreach ($countries as $key => $value) { ?>
					<option <?= (isset($user) && $user['ucountry'] == $value['id']) ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
				<?php } ?>
			</select>
		</div>

		<?php if(!isset($user_groups_readonly) || !$user_groups_readonly){?>
			<div class="mb-3">
				<label class="control-label"><?= __('admin.groups') ?></label>
				<select class="form-control select2" name="groups[]" multiple="multiple">
					<?php foreach ($user_groups as $key => $group) { ?>
						<option <?= (isset($user) && in_array($group->id, explode(',', $user['groups'])))? 'selected' : '' ?> value="<?= $group->id ?>"><?= $group->group_name ?></option>
					<?php } ?>
				</select>
			</div>
		<?php } else { 

			$unsbscribed = $this->db->get_where('unsubscribed_emails', ['email' => $email])->row();
			$isUnsubscribed = !empty($unsbscribed);
			?>
			<div class="form-group mb-3">
				<label class="control-label fw-semibold d-block mb-2"><?= __('admin.email_subscription') ?></label>
				<div id="emailSubscriptionBox" class="d-flex align-items-center gap-3 p-3 rounded border <?= $isUnsubscribed ? 'border-danger bg-danger bg-opacity-10' : 'border-success bg-success bg-opacity-10' ?>"
					data-enabled-classes="border-success bg-success bg-opacity-10"
					data-disabled-classes="border-danger bg-danger bg-opacity-10">
					<div class="flex-grow-1">
						<div id="emailSubscriptionStatus" class="fw-semibold <?= $isUnsubscribed ? 'text-danger' : 'text-success' ?>">
							<i id="emailSubscriptionIcon" class="bi <?= $isUnsubscribed ? 'bi-envelope-x' : 'bi-envelope-check' ?> me-1"></i>
							<span id="emailSubscriptionLabel"><?= $isUnsubscribed ? __('admin.email_subscription_disable') : __('admin.email_subscription_enable') ?></span>
						</div>
						<div id="emailSubscriptionDesc" class="small text-muted"
							data-enabled-text="<?= htmlspecialchars(__('admin.email_subscription_enabled_hint'), ENT_QUOTES, 'UTF-8') ?>"
							data-disabled-text="<?= htmlspecialchars(__('admin.email_subscription_disabled_hint'), ENT_QUOTES, 'UTF-8') ?>"
							data-enabled-label="<?= htmlspecialchars(__('admin.email_subscription_enable'), ENT_QUOTES, 'UTF-8') ?>"
							data-disabled-label="<?= htmlspecialchars(__('admin.email_subscription_disable'), ENT_QUOTES, 'UTF-8') ?>">
							<?= $isUnsubscribed ? __('admin.email_subscription_disabled_hint') : __('admin.email_subscription_enabled_hint') ?>
						</div>
					</div>
					<div class="form-check form-switch mb-0">
						<input class="form-check-input" type="checkbox" role="switch" id="emailSubscriptionToggle"
							style="width:3rem;height:1.5rem;cursor:pointer;"
							<?= $isUnsubscribed ? '' : 'checked' ?>>
					</div>
				</div>
				<input type="hidden" name="email_subscription" id="emailSubscriptionValue" value="<?= $isUnsubscribed ? '0' : '1' ?>">
				<div class="form-text small mt-1"><i class="bi bi-info-circle me-1"></i><?= __('admin.email_subscription_hint') ?></div>
			</div>
			<script>
			(function(){
				var toggle = document.getElementById('emailSubscriptionToggle');
				if (!toggle) { return; }
				var box    = document.getElementById('emailSubscriptionBox');
				var status = document.getElementById('emailSubscriptionStatus');
				var icon   = document.getElementById('emailSubscriptionIcon');
				var label  = document.getElementById('emailSubscriptionLabel');
				var desc   = document.getElementById('emailSubscriptionDesc');
				var hidden = document.getElementById('emailSubscriptionValue');
				function apply(isEnabled){
					hidden.value = isEnabled ? '1' : '0';
					if (box) {
						box.classList.remove('border-success','bg-success','bg-opacity-10','border-danger','bg-danger');
						(isEnabled ? box.dataset.enabledClasses : box.dataset.disabledClasses)
							.split(' ').forEach(function(c){ if(c) box.classList.add(c); });
					}
					if (status) {
						status.classList.remove('text-success','text-danger');
						status.classList.add(isEnabled ? 'text-success' : 'text-danger');
					}
					if (icon) {
						icon.classList.remove('bi-envelope-check','bi-envelope-x');
						icon.classList.add(isEnabled ? 'bi-envelope-check' : 'bi-envelope-x');
					}
					if (label && desc) {
						label.textContent = isEnabled ? desc.dataset.enabledLabel : desc.dataset.disabledLabel;
						desc.textContent  = isEnabled ? desc.dataset.enabledText  : desc.dataset.disabledText;
					}
				}
				toggle.addEventListener('change', function(){ apply(this.checked); });
			})();
			</script>
			<div class="form-group">
				<label class="control-label">Groups belong:</label>
				<ul class="list-group">
				<?php foreach ($user_groups as $key => $group) { 
					if(!isset($user) || !in_array($group->id, explode(',', $user['groups']))) continue;

					$hasGroupAssigned = true;
					
					$avatar = base_url('assets/images/');

					$avatar .= $group->avatar != '' ? 'site/'.$group->avatar : 'no_image_available.png' ; 
					?>

				  <li class="list-group-item">
				  	<img class="mr-2" src="<?= $avatar; ?>" height="35" width="35"/>
				  	<?= $group->group_name ?>
				  </li>
				<?php } ?>
			  <?php if(!isset($hasGroupAssigned)) { ?>
			  	<li class="list-group-item">No group assigned to you!</li>
			  <?php } ?>			
				</ul>
			</div>
		<?php } ?>
		
	<?php } ?>
	
	<?php if(!isset($edit_view)){ ?>

	<?php 
	   $db =& get_instance(); 
	   $googlerecaptcha = $db->Product_model->getSettings('googlerecaptcha');

	   if (isset($googlerecaptcha['affiliate_register']) && $googlerecaptcha['affiliate_register']) {
	      $version = isset($googlerecaptcha['version']) ? $googlerecaptcha['version'] : 'v2';
	      $sitekey = $googlerecaptcha['sitekey'];
	?>
	   <?php if ($version == 'v2') { ?>
	      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	      <div class="g-recaptcha captch mb-3 form-group" data-sitekey="<?= $sitekey ?>"></div>
	      <input type="hidden" name="captch_response" id="captch_response">
	   <?php } elseif ($version == 'v3') { ?>
	      <script src="https://www.google.com/recaptcha/api.js?render=<?= $sitekey ?>"></script>
	      <input type="hidden" name="captch_response" id="captch_response">
	      <script>
	         grecaptcha.ready(function () {
	            grecaptcha.execute('<?= $sitekey ?>', { action: 'affiliate_register' }).then(function (token) {
	               document.getElementById('captch_response').value = token;
	            });
	         });
	      </script>
	   <?php } ?>
	<?php } ?>


	<?php if(isset($template_index)) { ?>
		<div class="form-check mb-4 aff-terms-check">
			<input class="form-check-input aff-check-input border-0" type="checkbox" id="checkbox" name="terms" value="1" checked>
			<label class="form-check-label aff-check-label fw-normal" for="checkbox">
				<?= __('front.i_accept') ?> 
				<a href="#" class="aff-terms-link fw-semibold text-decoration-underline" data-bs-toggle="modal" data-bs-target="#termOfUse">
					<?= __('front.terms_and_conditions') ?>
				</a>
			</label>
		</div>
	<?php } else { ?>
		<div class="form-check mb-4 aff-terms-check">
			<input class="form-check-input aff-check-input border-0" type="checkbox" id="checkbox" name="terms" value="1" checked>
			<label class="form-check-label aff-check-label fw-normal" for="checkbox">
				<?= __('front.i_accept') ?> 
				<a href="#" class="aff-terms-link fw-semibold text-decoration-underline" data-bs-toggle="modal" data-bs-target="#terms_content">
					<?= __('front.terms_and_conditions') ?>
				</a>
			</label>
		</div>
	<?php } ?>

	<button 
		id="register_button"
		type="submit"
		class="btn btn-lg fw-bold rounded-3 py-3 shadow w-100 btn-submit aff-btn-submit">
		<span class="btn-text">
			<i class="bi bi-person-plus me-2"></i><?= __('front.create_account') ?>
		</span>
		<span class="btn-loading d-none">
			<i class="spinner-border spinner-border-sm me-2"></i><?= __('front.processing') ?>
		</span>
	</button>

	<?php } ?>
</form>

<!--first_last_name JS-->
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const regFormRoot = document.querySelector('form.reg_form');
		if (!regFormRoot) return;

		function validateField(inputId) {
			const input = regFormRoot.querySelector('#' + inputId.replace(/^#/, ''));
			if (!input) return;

			const label = input.getAttribute('data-label') || inputId;
			const tooShortText = input.getAttribute('data-too-short') || 'is too short';
			const hint = regFormRoot.querySelector('#' + inputId + '-hint');
			if (!hint) return;

			input.addEventListener('input', function () {
				if (this.value.trim().length < 2) {
					hint.textContent = `❌ ${label} ${tooShortText}`;
					hint.className = 'form-text mt-1 text-danger';
				} else {
					hint.textContent = '';
					hint.className = 'form-text mt-1';
				}
			});
		}

		validateField('firstname');
		validateField('lastname');
	});
</script>
<!--first_last_name JS-->

<!--Email JS-->
<script>
	document.addEventListener('DOMContentLoaded', function () {
	    const regFormRoot = document.querySelector('form.reg_form');
	    if (!regFormRoot) return;
	    const emailInput = regFormRoot.querySelector('#email');
	    const suggestionBox = regFormRoot.querySelector('#email-suggestions');
	    if (!emailInput) return;

	    const domains = [
	        "gmail.com", "yahoo.com", "hotmail.com", "outlook.com",
	        "aol.com", "icloud.com", "live.com", "msn.com", "protonmail.com",
	        "yandex.com", "mail.com", "gmx.com", "zoho.com", "fastmail.com",
	        "qq.com", "163.com", "126.com"
	    ];

	    emailInput.addEventListener('input', function () {
	        const value = emailInput.value;
	        const atIndex = value.indexOf('@');

	        suggestionBox.innerHTML = '';
	        suggestionBox.classList.add('d-none');

	        if (atIndex !== -1) {
	            const localPart = value.slice(0, atIndex);
	            const typedDomain = value.slice(atIndex + 1).toLowerCase();

	            const suggestions = domains
	                .filter(domain => !typedDomain || domain.startsWith(typedDomain))
	                .map(domain => `${localPart}@${domain}`);

	            if (suggestions.length > 0) {
	                suggestionBox.classList.remove('d-none');
	suggestions.forEach(suggestion => {
		const item = document.createElement('li');
		item.className = 'list-group-item list-group-item-action bg-white text-dark border border-primary';
		item.textContent = suggestion;
		item.onclick = function () {
			emailInput.value = suggestion;
			suggestionBox.classList.add('d-none');
		};
		suggestionBox.appendChild(item);
	});


	            }
	        }
	    });

	    document.addEventListener('click', function (e) {
	        if (!suggestionBox.contains(e.target) && e.target !== emailInput) {
	            suggestionBox.classList.add('d-none');
	        }
	    });

	    // Typo correction on blur
	    emailInput.addEventListener('blur', function () {
	        const val = emailInput.value.trim();
	        if (!val.includes("@")) return;

	        const [username, domain] = val.split("@");
	        const suggestion = findClosestDomain(domain);
	        if (suggestion && suggestion !== domain) {
	            if (confirm(`Did you mean ${username}@${suggestion}?`)) {
	                emailInput.value = `${username}@${suggestion}`;
	            }
	        }
	    });

	    function findClosestDomain(domain) {
	        let minDistance = Infinity;
	        let closest = null;

	        domains.forEach(d => {
	            let dist = levenshtein(domain, d);
	            if (dist < minDistance && dist <= 2) {
	                minDistance = dist;
	                closest = d;
	            }
	        });

	        return closest;
	    }

	    function levenshtein(a, b) {
	        const matrix = Array.from({ length: b.length + 1 }, (_, i) => [i]);
	        for (let j = 0; j <= a.length; j++) matrix[0][j] = j;

	        for (let i = 1; i <= b.length; i++) {
	            for (let j = 1; j <= a.length; j++) {
	                matrix[i][j] = b[i - 1] === a[j - 1]
	                    ? matrix[i - 1][j - 1]
	                    : Math.min(
	                        matrix[i - 1][j - 1] + 1,
	                        matrix[i][j - 1] + 1,
	                        matrix[i - 1][j] + 1
	                    );
	            }
	        }

	        return matrix[b.length][a.length];
	    }
	});
</script>
<!--Email JS-->


<!--Pass JS-->
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const regFormRoot = document.querySelector('form.reg_form');
		if (!regFormRoot) return;
		const passwordInput = regFormRoot.querySelector('#password');
		const confirmInput = regFormRoot.querySelector('#cpassword');
		if (!passwordInput || !confirmInput) return;

		// Create strength element
		const strengthEl = document.createElement('small');
		strengthEl.id = 'password-strength';
		strengthEl.className = 'form-text d-block mt-1';
		passwordInput.parentNode.appendChild(strengthEl);

		// Create match status element
		const matchEl = document.createElement('small');
		matchEl.id = 'match-check';
		matchEl.className = 'form-text d-block mt-1';
		confirmInput.parentNode.appendChild(matchEl);

		// Password strength logic
		passwordInput.addEventListener('input', function () {
			const val = passwordInput.value;

			let strength = 0;
			if (val.length >= 8) strength++;
			if (/[A-Z]/.test(val)) strength++;
			if (/[0-9]/.test(val)) strength++;
			if (/[^A-Za-z0-9]/.test(val)) strength++;

			const messages = [
				"<?= __('front.password_very_weak') ?>",
				"<?= __('front.password_weak') ?>",
				"<?= __('front.password_medium') ?>",
				"<?= __('front.password_strong') ?>",
				"<?= __('front.password_very_strong') ?>"
			];
			const colors = ['text-danger', 'text-warning', 'text-info', 'text-primary', 'text-success'];

			strengthEl.className = 'form-text d-block mt-1 ' + (colors[strength] || '');
			strengthEl.textContent = val ? "<?= __('front.password_strength') ?>: " + (messages[strength] || messages[0]) : '';
		});

		// Password match logic
		confirmInput.addEventListener('input', function () {
			const pass = passwordInput.value;
			const confirm = confirmInput.value;

			if (!confirm) {
				matchEl.textContent = '';
				matchEl.className = 'form-text d-block mt-1';
			} else if (pass === confirm) {
				matchEl.textContent = '✅ <?= __('front.passwords_match') ?>';
				matchEl.className = 'form-text d-block mt-1 text-success';
			} else {
				matchEl.textContent = '❌ <?= __('front.passwords_do_not_match') ?>';
				matchEl.className = 'form-text d-block mt-1 text-danger';
			}
		});
	});

	// Toggle password visibility (scope to reg_form when present — avoids duplicate #password / #username with login form)
	function togglePassword(id, btn) {
		const form = btn && btn.closest ? btn.closest('form.reg_form') : null;
		const input = form ? form.querySelector('#' + id) : document.getElementById(id);
		if (!input) return;
		if (input.type === "password") {
			input.type = "text";
			btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
		} else {
			input.type = "password";
			btn.innerHTML = '<i class="bi bi-eye"></i>';
		}
	}
</script>
<!--Pass JS-->

<!--Username JS-->
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const regFormRoot = document.querySelector('form.reg_form');
		if (!regFormRoot) return;
		const usernameInput = regFormRoot.querySelector('#username');
		if (!usernameInput) return;

		const usernameHint = document.createElement('small');
		usernameHint.className = 'form-text mt-1';
		usernameHint.id = 'username-hint';
		usernameInput.parentNode.appendChild(usernameHint);

		let usernameTimeout = null;

		usernameInput.addEventListener('input', function () {
			clearTimeout(usernameTimeout);

			const val = this.value.trim();
			if (val.length < 3) {
				usernameHint.textContent = '';
				usernameHint.className = 'form-text mt-1';
				return;
			}

			usernameHint.textContent = '⏳ Checking...';
			usernameHint.className = 'form-text mt-1 text-muted';

			usernameTimeout = setTimeout(() => {
				fetch('<?= base_url("pagebuilder/check_username?username=") ?>' + encodeURIComponent(val))
					.then(response => response.json())
					.then(data => {
						if (data.status === 'error') {
							usernameHint.textContent = '❌ ' + data.message;
							usernameHint.className = 'form-text mt-1 text-danger';
						} else if (data.status === 'success') {
							usernameHint.textContent = '✅ ' + data.message;
							usernameHint.className = 'form-text mt-1 text-success';
						}
					})
					.catch(() => {
						usernameHint.textContent = '⚠️ Could not check username';
						usernameHint.className = 'form-text mt-1 text-warning';
					});
			}, 500);
		});
	});
</script>
<!--Username JS-->


<script type="text/javascript">

	function isNumberKey(evt)
	{
	  var charCode = (evt.which) ? evt.which : event.keyCode;
	    if (charCode != 46 && charCode != 45 && charCode > 31
	    && (charCode < 48 || charCode > 57))
	     return false;

	  return true;
	}

	if (typeof jQuery !== 'undefined' && jQuery.fn.datetimepicker) {
		jQuery('.datetimepicker').datetimepicker({
			timepicker:false,
			format:'d.m.Y'
		});
	}

	<?php if(isset($edit_view)){ ?>
		if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
			jQuery('.select2').select2({
				placeholder : '<?= (isset($user_groups_readonly) && $user_groups_readonly) ? __('admin.no_groups_assigned') : __('admin.assign_user_groups'); ?>'
			});
		}
	<?php } ?>
	$(document).on('submit', '.reg_form', function (e) {
	    e.preventDefault();

	    var form = $(this);
	    var has_recaptcha = form.find('.g-recaptcha').length > 0;

	    var is_valid = 0;
	    var need_valid = 0;

	    // Reset errors
	    form.find(".has-error").removeClass("has-error");
	    form.find(".text-danger").remove();
	    form.find(".invalid-feedback").remove();

	    // Phone validation
	    $(".tel_input").each(function () {
	        let this_is_valid = true;
	        let $input = $(this);
	        let id = $input.attr("id");
	        let instance = window["tel_input" + id];

	        if (instance) {
	            let errorMap = [
	                "<?= __('user.invalid_number') ?>",
	                "<?= __('user.invalid_country_code') ?>",
	                "<?= __('user.too_short') ?>",
	                "<?= __('user.too_long') ?>",
	                "<?= __('user.invalid_number') ?>"
	            ];
	            let errorText = "";

	            if ($input.val().trim()) {
	                need_valid++;
	                if (instance.isValidNumber()) {
	                    instance.setNumber($input.val().trim());
	                    is_valid++;
	                } else {
	                    let errorCode = instance.getValidationError();
	                    errorText = errorMap[errorCode];
	                    this_is_valid = false;
	                }
	            } else {
	                if ($input.attr("required") !== undefined) {
	                    need_valid++;
	                    errorText = "The Mobile Number field is required.";
	                    this_is_valid = false;
	                }
	            }

	            if (!this_is_valid) {
	                $input.parents(".form-group").addClass("has-error");
	                $input.after("<span class='text-danger'>" + errorText + "</span>");
	            }
	        }
	    });

	    // If phone valid
	    if (need_valid !== is_valid) return false;

	    // Handle reCAPTCHA v2 - only if it exists on the page
	    if (has_recaptcha) {
	        try {
	            if (typeof grecaptcha !== "undefined" && grecaptcha.getResponse) {
	                let response = grecaptcha.getResponse();
	                if (!response || response.length === 0) {
	                    alert("Please complete the reCAPTCHA.");
	                    return false;
	                }
	                form.find('input[name="captch_response"]').val(response);
	            } else {
	                console.warn("reCAPTCHA not loaded yet");
	                alert("Please wait for reCAPTCHA to load and try again.");
	                return false;
	            }
	        } catch (err) {
	            console.error("reCAPTCHA v2 error:", err);
	            alert("reCAPTCHA error. Please refresh the page and try again.");
	            return false;
	        }
	    }

	    var data = new FormData(this);

	    // Add phone prefixes
	    $(".tel_input").each(function () {
	        if ($(this).val().trim() && window["tel_input" + $(this).attr("id")].isValidNumber()) {
	            let country_id = window["tel_input" + $(this).attr("id")].getSelectedCountryData().dialCode;
	            data.append($(this).attr("name") + "_afftel_input_pre", country_id);
	        }
	    });

		$.ajax({
			url: '<?= base_url("pagebuilder/register") ?>',
			type: 'POST',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: data,
			beforeSend: function () {
				form.find(".btn-submit").attr("disabled", true);

				// Show spinner + hide text
				const btnText = form.find(".btn-submit .btn-text");
				const btnLoading = form.find(".btn-submit .btn-loading");

				if (btnText.length && btnLoading.length) {
					btnText.addClass("d-none");
					btnLoading.removeClass("d-none");
				}
			},
			complete: function () {
				form.find(".btn-submit").removeAttr("disabled");

				// Restore button text
				const btnText = form.find(".btn-submit .btn-text");
				const btnLoading = form.find(".btn-submit .btn-loading");

				if (btnText.length && btnLoading.length) {
					btnText.removeClass("d-none");
					btnLoading.addClass("d-none");
				}
			},
			success: function (json) {
				if (json['redirect']) return window.location.href = json['redirect'];
				if (json['warning']) alert(json['warning']);

				if (json['errors']) {
					$.each(json['errors'], function (i, msg) {
						if (i === 'captch_response' && has_recaptcha && typeof grecaptcha !== "undefined" && grecaptcha.reset) {
							try {
								grecaptcha.reset();
							} catch (e) {
								console.warn("Could not reset reCAPTCHA:", e);
							}
						}

						let $el = form.find('[name="' + i + '"]');
						if ($el.length) {
							$el.parents(".form-group").addClass("has-error");
							$el.after("<span class='text-danger'>" + msg + "</span>");
						}
					});
				}
			}
		});

	    return false;
	});
</script>