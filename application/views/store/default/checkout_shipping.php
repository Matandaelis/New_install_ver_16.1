<?php
/**
 * Default theme — Checkout shipping address step partial
 *
 * @contract  Store API v1 — fragment: checkout_shipping (AJAX partial rendered inside checkout.php)
 * @see       Store_cart_payload::page_checkout_shipping()
 * @see       Loaded via AJAX GET store/api/v1/pages/checkout_shipping
 *
 * VARIABLES (injected by Store controller)
 *   $countries      array   Country list [{code, name}, ...]
 *   $is_guest_flow  bool    Whether this is a guest checkout session
 *   $user           array   Prefill data: logged-in customer address fields (empty if guest)
 *   $shipping_url   string  Form action URL for address submission
 */
if(true) { ?>

<div class="shipping-warning"></div>
<?php if(!empty($is_guest_flow)) { ?>
<div class="form-row">
	<div class="mb-3">
		<label><?= __('store.first_name') ?></label>
		<input type="text" placeholder="<?= __('store.first_name') ?>" name="firstname" class="form-control" type="text" value="" required="">
	</div>
	<div class="mb-3">
		<label><?= __('store.last_name') ?></label>
		<input type="text" placeholder="<?= __('store.last_name') ?>" name="lastname" class="form-control" type="text" value="" required="">
	</div>
	<div class="mb-3">
		<label><?= __('store.email_address') ?></label>
		<input type="text" placeholder="<?= __('store.email_address') ?>" name="email" class="form-control" type="text" value="" required="">
		<input type="hidden" name="classified_checkout" value="1">	
	</div>
</div>
<?php } ?>
<div class="form-row">
	<div class="mb-3">
		<label><?= __('store.country') ?></label>
		<?php $selected =  isset($shipping) ? $shipping->country_id : '' ?>
		<?php $selected =  isset($country_id) ? $country_id : $selected ?>
		<select name="country" class="form-select">
			<option value="">Select Country</option>
			<?php foreach ($countries as $key => $value) { ?>
				<option <?= $selected == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="mb-3">
		<label><?= __('store.state') ?></label>
		<select name="state" class="form-select">
		</select>
	</div>
	<div class="mb-3">
		<label><?= __('store.city') ?></label>
		<input type="text" placeholder="<?= __('store.city') ?>" name="city" class="form-control" type="text" value="<?= isset($shipping) ? $shipping->city : '' ?>">
	</div>
</div>
								
<div class="form-row">
	<div class="mb-3">
		<label><?= __('store.postal_code') ?></label>
		<input class="form-control" name="zip_code" placeholder="<?= __('store.postal_code') ?>" type="text" value="<?= isset($shipping) ? $shipping->zip_code : '' ?>">
	</div>
	<div class="mb-3">
		<label for=""><?= __('store.phone_number') ?></label>
		<input type="hidden" id="phonenumber-input" name='PhoneNumberInput' value="" class="form-control" placeholder="<?= __('store.phone_number') ?>" />
		<div>
			<input id="phone" onkeypress="return isNumberKey(event);" class="form-control" type="text" name="phone" value="<?= isset($shipping) ? $shipping->phone : '' ?>">
		</div>
	</div>
	<script type="text/javascript">
		var tel_input = intlTelInput(document.querySelector("#phone"), {
			initialCountry: "auto",
			utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
			separateDialCode: true,
			dropdownContainer: document.body,
			placeholderNumberType: "MOBILE",
			autoPlaceholder: "aggressive",
			geoIpLookup: function(success, failure) {
				$.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
					var countryCode = (resp && resp.country) ? resp.country : "";
					success(countryCode);
				});
			},
		});
	</script>
</div>
<div class="form-row">
	<div class="mb-3">
		<label><?= __('store.full_address') ?></label>
		<textarea class="form-control" placeholder="<?= __('store.full_address') ?>" name="address"><?= isset($shipping) ? $shipping->address : '' ?></textarea>
	</div>
</div>

<!-- USPS Shipping Methods -->
<div class="form-row" id="usps-shipping-section" style="display: none;">
	<div class="mb-3">
		<label><?= __('store.shipping_method') ?></label>
		<div id="usps-shipping-methods">
			<div class="text-muted"><?= __('store.calculating_shipping_rates') ?></div>
		</div>
	</div>
</div>

<input type="hidden" name="cookies_consent" id="cookies_consent" value="true">
<input type="hidden" name="selected_shipping_method" id="selected_shipping_method" value="">
<input type="hidden" name="shipping_cost" id="shipping_cost" value="0">


<script type="text/javascript">
	var selected_state = '<?= isset($shipping) ? $shipping->state_id : '' ?>';

	// Define renderStateAndCart function if not already defined
	if (typeof renderStateAndCart === 'undefined') {
		function renderStateAndCart(countryCode) {
			if (countryCode) {
				$.ajax({
					url: '<?= base_url("store/getState") ?>',
					type: 'POST',
					data: { id: countryCode },
					dataType: 'json',
					success: function(response) {
						var stateSelect = $('select[name="state"]');
						stateSelect.empty();
						stateSelect.append('<option value="">Select State</option>');
						
						$.each(response.states, function(index, state) {
							stateSelect.append('<option value="' + state.id + '">' + state.name + '</option>');
						});
						
						// Set selected state if available
						if (selected_state) {
							stateSelect.val(selected_state);
						}
					}
				});
			}
		}
	}

	renderStateAndCart(<?=$selected;?>);

	// Country change event
	$('select[name="country"]').on('change', function() {
		var countryId = $(this).val();
		renderStateAndCart(countryId);
		
		// Save country ID to session for shipping calculations
		if (countryId) {
			$.ajax({
				url: '<?= base_url("store/update_shipping_country") ?>',
				type: 'POST',
				data: { country_id: countryId },
				dataType: 'json',
				success: function(response) {
					if (response.status) {
						// Refresh cart totals with new country
						if (typeof getCart === 'function') {
							getCart();
						}
					}
				}
			});
		}
	});

	// Check if USPS is enabled first
	$(document).ready(function() {
		checkUSPSStatus();
	});

	// USPS Shipping Rate Calculation
	$('input[name="zip_code"]').on('blur', function() {
		var zipCode = $(this).val();
		if (zipCode && zipCode.length >= 5) {
			calculateUSPSRates(zipCode);
		}
	});

	function checkUSPSStatus() {
		$.ajax({
			url: '<?= base_url("store/calculate_usps_rates") ?>',
			type: 'POST',
			data: { zip_code: '10001' }, // Test with dummy zip code
			dataType: 'json',
			success: function(response) {
				if (response.status && response.rates) {
					// USPS is enabled, show section and calculate rates if zip exists
					$('#usps-shipping-section').show();
					var existingZip = $('input[name="zip_code"]').val();
					if (existingZip && existingZip.trim().length >= 5) {
						calculateUSPSRates(existingZip.trim());
					}
				} else {
					// USPS is disabled, hide the section completely
					$('#usps-shipping-section').hide();
				}
			},
			error: function() {
				// If there's an error, hide the USPS section to be safe
				$('#usps-shipping-section').hide();
			}
		});
	}

	function calculateUSPSRates(zipCode) {
		$('#usps-shipping-section').show();
		$('#usps-shipping-methods').html('<div class="text-muted"><?= __('store.calculating_shipping_rates') ?></div>');

		$.ajax({
			url: '<?= base_url("store/calculate_usps_rates") ?>',
			type: 'POST',
			data: {
				zip_code: zipCode
			},
			dataType: 'json',
			success: function(response) {
				if (response.status) {
					displayUSPSMethods(response.rates);
				} else {
					// Check if USPS is disabled and hide the section
					if (response.message && response.message.includes('USPS shipping is not enabled')) {
						$('#usps-shipping-section').hide();
					} else {
						$('#usps-shipping-methods').html('<div class="text-danger">' + response.message + '</div>');
					}
				}
			},
			error: function() {
				$('#usps-shipping-methods').html('<div class="text-danger"><?= __('store.shipping_calculation_error') ?></div>');
			}
		});
	}

	function displayUSPSMethods(rates) {
		var html = '';
		
		// Add flat rate option (default to 0 if no flat rate set)
		var flatRateCost = <?= (float)($flat_rate_amount ?? 0) ?>;
		
		html += '<div class="form-check mb-2">';
		html += '<input class="form-check-input" type="radio" name="shipping_method" id="flat_rate" value="flat_rate" checked data-cost="' + flatRateCost + '">';
		html += '<label class="form-check-label" for="flat_rate">';
		html += '<strong><?= __('store.flat_rate_shipping') ?></strong> - $' + flatRateCost.toFixed(2);
		html += '</label>';
		html += '</div>';

		// Add USPS options
		rates.forEach(function(rate, index) {
			html += '<div class="form-check mb-2">';
			html += '<input class="form-check-input" type="radio" name="shipping_method" id="usps_' + index + '" value="usps_' + rate.service + '" data-cost="' + rate.cost + '">';
			html += '<label class="form-check-label" for="usps_' + index + '">';
			html += '<strong>' + rate.service + '</strong> - $' + parseFloat(rate.cost).toFixed(2);
			if (rate.delivery_time) {
				html += ' <small class="text-muted">(' + rate.delivery_time + ')</small>';
			}
			html += '</label>';
			html += '</div>';
		});

		$('#usps-shipping-methods').html(html);

		// Handle shipping method selection
		$('input[name="shipping_method"]').off('change').on('change', function() {
			var method = $(this).val();
			var cost = parseFloat($(this).data('cost')) || 0;

			$('#selected_shipping_method').val(method);
			$('#shipping_cost').val(cost);

			// Update cart with new shipping cost
			updateCartWithShipping(cost);
		});

		// Trigger change on first option
		$('input[name="shipping_method"]:first').trigger('change');
	}

	function updateCartWithShipping(shippingCost) {
		$.ajax({
			url: '<?= base_url("store/update_shipping_cost") ?>',
			type: 'POST',
			data: {
				shipping_cost: shippingCost
			},
			dataType: 'json',
			success: function(response) {
				if (response.status) {
					// Show success message near shipping rates
					showShippingNotification('Shipping cost updated: $' + shippingCost.toFixed(2), 'success');
					
					// Debug: Check session data
					debugShippingSession();
					
					// Refresh cart display with a small delay to ensure session is updated
					if (typeof getCart === 'function') {
						setTimeout(function() {
							getCart();
						}, 500);
					}
					
					// Also refresh payment methods if needed
					if (typeof getPaymentMethods === 'function') {
						getPaymentMethods();
					}
				} else {
					showShippingNotification('Error updating shipping cost', 'error');
				}
			},
			error: function() {
				showShippingNotification('Error updating shipping cost', 'error');
			}
		});
	}

	function debugShippingSession() {
		$.ajax({
			url: '<?= base_url("store/debug_shipping_session") ?>',
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				console.log('Shipping Session Debug:', response);
			}
		});
	}

	function showShippingNotification(message, type) {
		// Remove any existing notifications
		$('.shipping-notification').remove();
		
		// Create notification element
		var notificationClass = type === 'success' ? 'text-success' : 'text-danger';
		var icon = type === 'success' ? '✓' : '✗';
		
		var notification = $('<div class="shipping-notification ' + notificationClass + ' mt-2 p-2 border rounded" style="background-color: #f8f9fa; border-color: ' + (type === 'success' ? '#28a745' : '#dc3545') + ' !important;">' +
			'<strong>' + icon + '</strong> ' + message +
		'</div>');
		
		// Insert after shipping methods
		$('#usps-shipping-methods').after(notification);
		
		// Auto-remove after 3 seconds
		setTimeout(function() {
			$('.shipping-notification').fadeOut(500, function() {
				$(this).remove();
			});
		}, 3000);
	}
</script>
<?php } else { ?>
	<?php if($show_blue_message){ ?>
		<div class="alert alert-info"><?= __('store.shipping_not_allows') ?></div>
	<?php } ?>
<?php } ?>