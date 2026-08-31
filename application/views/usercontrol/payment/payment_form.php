<div class="container-fluid">
<?php if(isset($payment_methods) && $payment_methods['bank_transfer']['status']==1) { ?>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-university me-2"></i><?= __('user.bank_details') ?>
        </h5>
    </div>  
    <div class="card-body">
    <form class="form-horizontal" method="post" action="<?= base_url('usercontrol/payment_details') ?>" enctype="multipart/form-data">
        <input type="hidden" name="payment_id" value="<?= $paymentlist['payment_id'] ?>">

        <?php if(isset($payment_methods['bank_transfer']['bank_transfer_mode']) && $payment_methods['bank_transfer']['bank_transfer_mode'] == 'specific') { ?>
            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-globe me-1 text-primary"></i><?= __('user.country') ?>
                </label>
                <select name="payment_country" id="payment_country" class="form-select form-select-lg" required>
                        <option value=""> <?= __('user.select_country') ?> </option>
                        <?php 
                        $countries = $payment_methods['bank_transfer']['bank_transfer_countries'];
                        foreach($countries as $country => $status) {
                            if($status == 1) {
                                $selected = ($paymentlist['payment_country'] == $country) ? 'selected' : '';
                                echo '<option value="'.$country.'" '.$selected.'>'.$country.'</option>';
                            }
                        }
                        ?>
                    </select>
            </div>
        <?php } ?>

        <div class="mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-building me-1 text-primary"></i><?= __('user.bank_name') ?>
            </label>
            <input placeholder="<?= __('user.enter_your_bank_name') ?>" name="payment_bank_name" value="<?php echo $paymentlist['payment_bank_name']; ?>" class="form-control form-control-lg" required type="text">
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-hashtag me-1 text-primary"></i><?= __('user.account_number') ?>
            </label>
            <input placeholder="<?= __('user.enter_your_account_number') ?>" name="payment_account_number" value="<?php echo $paymentlist['payment_account_number']; ?>" class="form-control form-control-lg" required type="text">
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-bold">
                <i class="fas fa-user me-1 text-primary"></i><?= __('user.account_name') ?>
            </label>
            <input placeholder="<?= __('user.enter_your_account_name') ?>" name="payment_account_name" class="form-control form-control-lg" value="<?php echo $paymentlist['payment_account_name']; ?>" required type="text">
        </div>

        <!-- Country Specific Fields with Validation and Placeholders -->
		<div id="country-fields">
		    <?php 
		    $countryFieldMap = get_country_field_map(); // from helper
		    $placeholders = [
		        'payment_routing_number'              => 'Routing Number (e.g., 123456789)',
		        'payment_ifsc_code'                   => 'IFSC Code (e.g., ABCD0123456)',
		        'payment_sort_code'                   => 'Sort Code (e.g., 12-34-56)',
		        'payment_bsb_number'                  => 'BSB Number (e.g., 123-456)',
		        'payment_transit_institution_number'  => 'Transit Institution Number (e.g., 12345-678)',
		        'payment_iban_bic'                    => 'IBAN/BIC (e.g., DE89370400440532013000)',
		        'payment_cnaps_code'                  => 'CNAPS Code (e.g., 123456789012)',
		        'payment_swift_code'                  => 'SWIFT Code (e.g., DBSSSGSGXXX)',
		        'payment_clearing_code'               => 'Clearing Code (e.g., 012-345-6789)',
		        'payment_bank_branch_number'          => 'Bank Branch Number (e.g., 12-3456-0789123-00)',
		    ];

		    foreach ($countries as $country => $status) {
		        if ($status == 1 && isset($countryFieldMap[$country])) {
		            $fieldName = $countryFieldMap[$country];
		            $fieldValue = isset($paymentlist[$fieldName]) ? $paymentlist[$fieldName] : '';
		            $placeholder = isset($placeholders[$fieldName]) ? $placeholders[$fieldName] : ucfirst(str_replace('_',' ', $fieldName));
		            
		            echo '<div class="mb-4 country-field" data-country="'.$country.'" style="display: none;">
		                    <label class="form-label fw-bold"><i class="fas fa-money-check me-1 text-info"></i>' . $country . ' ' .__('user.field').'</label>
		                    <input placeholder="'.$placeholder.'" name="'.$fieldName.'" class="form-control form-control-lg country-input" value="'.$fieldValue.'" type="text" required>
		                  </div>';
		        }
		    }
		    ?>
		</div>
		
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-primary btn-lg px-5" id="update-payment" type="submit">
                <i class="fas fa-save me-1"></i><?= __('user.submit') ?>
            </button>
        </div>
    </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#payment_country').on('change', function() {
        var selectedCountry = $(this).val();
        $('.country-field').hide();
        $('.country-field[data-country="' + selectedCountry + '"]').show();
        
        $('.country-input').prop('required', false);
        $('.country-field[data-country="' + selectedCountry + '"] .country-input').prop('required', true);
    });
    
    if($('#payment_country').val()) {
        $('#payment_country').trigger('change');
    }
});
</script>
<?php } ?>




	<?php if(isset($payment_methods) && $payment_methods['paypal']['status']==1) { ?>
	<div class="card shadow-sm mt-4">
		<div class="card-header bg-info bg-opacity-10">
			<h5 class="mb-0">
				<i class="fab fa-paypal me-2 text-info"></i><?= __('user.add_paypal_account') ?>
			</h5>
		</div>
		<div class="card-body">
			<form class="form-horizontal" method="post" action="<?= base_url('usercontrol/payment_details') ?>" enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?= $paypalaccounts['id'] ?>">
				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-envelope me-1 text-primary"></i><?= __('user.paypal_email') ?>
					</label>
					<input name="paypal_email" class="form-control form-control-lg" value="<?= $paypalaccounts['paypal_email'] ?>" required type="email" placeholder="<?= __('user.enter_paypal_email') ?>">
				</div>
				
				<div class="d-grid gap-2 d-md-flex justify-content-md-end">
					<button name="add_paypal" type="submit" class="btn btn-success btn-lg px-5">
						<i class="fas fa-save me-1"></i><?= __('user.submit') ?>
					</button>
				</div>
			</form>
		</div>
	</div>
	<?php } ?>
	
	<?php 
	// Display all custom payment gateways dynamically
	if(isset($payment_methods)) {
		foreach($payment_methods as $gateway_code => $gateway_info) {
			// Skip core gateways (bank_transfer, paypal) - they have their own sections
			if(in_array($gateway_code, ['bank_transfer', 'paypal'])) continue;
			
			// Only show enabled custom gateways
			if(isset($gateway_info['status']) && $gateway_info['status'] == 1) {
				$gateway_data = isset($custom_gateway_settings[$gateway_code]) ? $custom_gateway_settings[$gateway_code] : array();
	?>
	<div class="card shadow-sm mt-4">
		<div class="card-header bg-warning bg-opacity-10">
			<h5 class="mb-0">
				<i class="fas fa-credit-card me-2 text-warning"></i><?= $gateway_info['title'] ?>
			</h5>
		</div>
		<div class="card-body">
			<form class="form-horizontal" method="post" action="<?= base_url('usercontrol/payment_details') ?>" enctype="multipart/form-data">
				<input type="hidden" name="gateway_code" value="<?= $gateway_code ?>">
				
				<?php
				$user_settings_file = APPPATH . 'withdrawal_payment/user_settings/' . $gateway_code . '.php';
				if(file_exists($user_settings_file)) {
					$gateway_data_for_form = $gateway_data;
					include $user_settings_file;
				} else {
					echo '<div class="alert alert-warning">
							<i class="fas fa-exclamation-triangle me-1"></i>Custom form not found for this gateway.
						  </div>';
				}
				?>
				
				<div class="d-grid gap-2 d-md-flex justify-content-md-end">
					<button name="add_custom_gateway" type="submit" class="btn btn-success btn-lg px-5">
						<i class="fas fa-save me-1"></i><?= __('user.submit') ?>
					</button>
				</div>
			</form>
		</div>
	</div>
	<?php 
			}
		}
	}
	?>
	
	<div class="card shadow-sm mt-4">
		<div class="card-header bg-success bg-opacity-10">
			<h5 class="mb-0">
				<i class="fas fa-star me-2 text-success"></i><?= __('user.primay_payment_method') ?>
			</h5>
		</div>
		<div class="card-body">
			<form class="form-horizontal" method="post" action="<?= base_url('usercontrol/payment_details') ?>" enctype="multipart/form-data">
				<?php  
				$paymentmethodcount=0;
				if(isset($payment_methods) && is_array($payment_methods ) && count($payment_methods)>0 )
				{
					foreach ($payment_methods as $paymentmethod) {
						if($paymentmethod["status"]==1) {
							$paymentmethodcount++;
						}
					}
				}
				if($paymentmethodcount>0)
				{
				?>
				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-check-circle me-1 text-primary"></i><?= __('user.select_primary_method') ?>
					</label>
					<select class="form-select form-select-lg" id="primary_payment_method" name="primary_payment_method">
						<option value=""><?= __('user.select_payment_method') ?></option>	
						<?php 
						foreach ($payment_methods as $paymentmethod) {
							if($paymentmethod["status"]==1) {
								?>
								<option value="<?=$paymentmethod['code'];?>" <?= $paymentmethod['code'] == $primary_payment_method ? 'selected' : '' ?>><?=$paymentmethod['title'];?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
				<div class="d-grid gap-2 d-md-flex justify-content-md-end">
					<button name="add_primary_payment" type="submit" class="btn btn-success btn-lg px-5">
						<i class="fas fa-save me-1"></i><?= __('user.submit') ?>
					</button>
				</div>
				<?php
				 } 
				 else 
				 {?>
				<div class="alert alert-info text-center py-4">
					<i class="fas fa-info-circle fa-2x mb-2"></i>
					<h5><?= __('user.no_payment_method_available') ?></h5>
				</div>
				<?php } 
				 ?>
			</form>
		</div>
	</div>
</div>