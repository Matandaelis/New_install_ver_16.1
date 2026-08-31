<?php
	$json = file_get_contents(APPPATH.'views/admincontrol/currency/currency.json');
	$cur  = json_decode($json, true);

		$cur['BITCOIN'] = array(
			'code'           => "BITCOIN",
			'decimal_digits' => 2,
			'name'           => "Bitcoin",
			'name_plural'    => "Bitcoin",
			'rounding'       => 0,
			'symbol'         => "₿",
			'symbol_native'  => "₿",
		);

		$cur['MDZA'] = array(
			'code'           => "MDZA",
			'decimal_digits' => 2,
			'name'           => "MDZA",
			'name_plural'    => "MDZA",
			'rounding'       => 0,
			'symbol'         => "MDZA",
			'symbol_native'  => "MDZA",
		);
		
		$cur['POINTS'] = array(
			'code'           => "POINTS",
			'decimal_digits' => 0,
			'name'           => "POINTS",
			'name_plural'    => "POINTS",
			'rounding'       => 0,
			'symbol'         => "",
			'symbol_native'  => "",
		);
		
	?>

<?= performance_indicator_html('currency-edit-performance', 'badge bg-success text-white shadow-lg px-3 py-2', true) ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h2 class="mb-1">
						<?php if(isset($currencys) && $currencys['currency_id'] > 0): ?>
							<?= __('admin.edit_currency') ?>: <?= $currencys['title'] ?>
						<?php else: ?>
							<?= __('admin.add_new_currency') ?>
						<?php endif; ?>
					</h2>
					<p class="text-muted mb-0"><?= __('admin.configure_currency_settings') ?></p>
				</div>
				<div class="d-flex gap-2">
					<a href="<?= base_url('admincontrol/currency_list') ?>" class="btn btn-outline-secondary">
						<i class="bi bi-arrow-left me-2"></i><?= __('admin.back_to_list') ?>
					</a>
					<?php if(isset($currencys) && $currencys['currency_id'] > 0): ?>
						<button type="button" class="btn btn-outline-info" id="refresh-single-rate" data-currency-code="<?= $currencys['code'] ?>">
							<i class="bi bi-arrow-clockwise me-2"></i><?= __('admin.refresh_rate') ?>
						</button>
					<?php endif; ?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-8">
					<div class="card">
						<div class="card-header bg-white border-bottom">
							<div class="d-flex align-items-center">
								<i class="bi bi-currency-exchange me-2 text-primary"></i>
								<div>
									<h5 class="card-title mb-0 text-dark"><?= __('admin.currency_details') ?></h5>
									<small class="text-muted"><?= __('admin.basic_currency_information') ?></small>
								</div>
							</div>
						</div>
						<div class="card-body">
							<form method="post" id="currency_edit_form">
								<input type="hidden" value="<?= isset($currencys) ? $currencys['currency_id'] : '0' ?>" name="currency_id">

								<div class="mb-4">
									<label for="currencySelect" class="form-label fw-semibold">
										<i class="bi bi-search me-1"></i><?= __('admin.quick_select') ?>
									</label>
									<select id="currencySelect" class="form-select" name="existingTitle">
										<option value=""><?= __('admin.please_select_your_currency') ?></option>
										<?php 
										$existing_codes = array();
										if(isset($existing_currencies)) {
											foreach($existing_currencies as $existing) {
												$existing_codes[] = $existing['code'];
											}
										}
										
										foreach ($cur as $key => $c) { 
											$is_existing = in_array($key, $existing_codes);
											$is_current = (isset($currencys) && $currencys['code'] == $key);
											$is_disabled = $is_existing && !$is_current;
										?>
											<option <?= $is_current ? 'selected' : '' ?> value="<?= $c['name'] ?>" data-id="<?= $key ?>" <?= $is_disabled ? 'disabled' : '' ?>>
												<?= $c['name'] ?> (<?= $key ?>) <?= $is_disabled ? ' - ' . __('admin.already_added') : '' ?>
											</option>
										<?php } ?>
									</select>
									<div class="form-text"><?= __('admin.custom_currency_create_guide') ?></div>
								</div>

								<style>
								#currencySelect option:disabled {
									color: #6c757d !important;
									background-color: #f8f9fa !important;
									font-style: italic;
								}
								</style>

								<div class="row">
									<div class="col-md-6 mb-3">
										<label for="title" class="form-label fw-semibold">
											<i class="bi bi-tag me-1"></i><?= __('admin.currency_name') ?>
										</label>
										<input type="text" id="title" class="form-control" value="<?= isset($currencys) ? $currencys['title'] : '' ?>" name="title" placeholder="e.g., US Dollar">
									</div>
									<div class="col-md-6 mb-3">
										<label for="code" class="form-label fw-semibold">
											<i class="bi bi-code me-1"></i><?= __('admin.currency_code') ?>
										</label>
										<input type="text" id="code" class="form-control text-uppercase" value="<?= isset($currencys) ? $currencys['code'] : '' ?>" name="code" placeholder="e.g., USD" maxlength="3">
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 mb-3">
										<label for="symbol_left" class="form-label fw-semibold">
											<i class="bi bi-chevron-left me-1"></i><?= __('admin.symbol_left') ?>
										</label>
										<input type="text" id="symbol_left" class="form-control" value="<?= isset($currencys) ? $currencys['symbol_left'] : '' ?>" name="symbol_left" placeholder="e.g., $">
										<div class="form-text"><?= __('admin.symbol_before_amount') ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<label for="symbol_right" class="form-label fw-semibold">
											<i class="bi bi-chevron-right me-1"></i><?= __('admin.symbol_right') ?>
										</label>
										<input type="text" id="symbol_right" class="form-control" value="<?= isset($currencys) ? $currencys['symbol_right'] : '' ?>" name="symbol_right" placeholder="e.g., €">
										<div class="form-text"><?= __('admin.symbol_after_amount') ?></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4 mb-3">
										<label for="decimal_place" class="form-label fw-semibold">
											<i class="bi bi-123 me-1"></i><?= __('admin.decimal_places') ?>
										</label>
										<select id="decimal_place" class="form-select" name="decimal_place">
											<option value="0" <?= (isset($currencys) && $currencys['decimal_place'] == 0) ? 'selected' : '' ?>>0 (¥100)</option>
											<option value="2" <?= (isset($currencys) && $currencys['decimal_place'] == 2) ? 'selected' : '' ?>>2 ($100.00)</option>
											<option value="3" <?= (isset($currencys) && $currencys['decimal_place'] == 3) ? 'selected' : '' ?>>3 ($100.000)</option>
											<option value="4" <?= (isset($currencys) && $currencys['decimal_place'] == 4) ? 'selected' : '' ?>>4 ($100.0000)</option>
										</select>
									</div>
									<div class="col-md-4 mb-3">
										<label for="decimal_symbol" class="form-label fw-semibold">
											<i class="bi bi-dot me-1"></i><?= __('admin.decimal_symbol') ?>
										</label>
										<select id="decimal_symbol" class="form-select" name="decimal_symbol">
											<option value="." <?= (isset($currencys) && $currencys['decimal_symbol'] == '.') ? 'selected' : '' ?>>. (100.50)</option>
											<option value="," <?= (isset($currencys) && $currencys['decimal_symbol'] == ',') ? 'selected' : '' ?>>, (100,50)</option>
										</select>
									</div>
									<div class="col-md-4 mb-3">
										<label for="replace_comma_symbol" class="form-label fw-semibold">
											<i class="bi bi-list me-1"></i><?= __('admin.thousands_separator') ?>
										</label>
										<select id="replace_comma_symbol" class="form-select" name="replace_comma_symbol">
											<option value="," <?= (isset($currencys) && $currencys['replace_comma_symbol'] == ',') ? 'selected' : '' ?>>, (1,000)</option>
											<option value="." <?= (isset($currencys) && $currencys['replace_comma_symbol'] == '.') ? 'selected' : '' ?>>. (1.000)</option>
											<option value=" " <?= (isset($currencys) && $currencys['replace_comma_symbol'] == ' ') ? 'selected' : '' ?>>Space (1 000)</option>
											<option value="" <?= (isset($currencys) && $currencys['replace_comma_symbol'] == '') ? 'selected' : '' ?>><?= __('admin.none') ?> (1000)</option>
										</select>
									</div>
								</div>

								<div class="mb-4">
									<label for="value" class="form-label fw-semibold">
										<i class="bi bi-calculator me-1"></i><?= __('admin.exchange_rate') ?>
										<?php if(isset($default_currency) && $default_currency): ?>
											<small class="text-muted">(<?= __('admin.relative_to') ?> <?= $default_currency['title'] ?> - <?= $default_currency['code'] ?>)</small>
										<?php endif; ?>
									</label>
									<div class="input-group">
										<button type="button" id="fetchExchangeRate" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= __('admin.fetch_current_rate') ?>">
											<i class="bi bi-download"></i>
										</button>
										<input type="number" id="value" class="form-control" value="<?= isset($currencys) ? $currencys['value'] : '' ?>" name="value" step="0.0001" placeholder="1.0000">
										<span class="input-group-text" id="rate-unit-display">
											<?php 
											// Show the currency being edited if available, otherwise show default currency
											if(isset($currencys) && !empty($currencys['code'])) {
												echo $currencys['code'];
											} elseif(isset($default_currency) && $default_currency) {
												echo $default_currency['code'];
											} else {
												echo __('admin.rate_unit');
											}
											?>
										</span>
									</div>
									<div class="form-text">
										<?php if(isset($default_currency) && $default_currency): ?>
											<div class="d-flex align-items-start">
												<i class="bi bi-info-circle me-2 text-primary mt-1"></i>
												<div>
													<strong><?= __('admin.exchange_rate_explanation') ?>:</strong><br>
													<?= __('admin.exchange_rate_example_1') ?> <strong><?= $default_currency['code'] ?></strong> <?= __('admin.exchange_rate_example_2') ?><br>
													<div class="alert alert-info mt-2 mb-2 py-2">
														<i class="bi bi-magic me-1"></i>
														<strong><?= __('admin.automatic_rate_fetch') ?>:</strong> <?= __('admin.click_download_button') ?>
													</div>
													<small class="text-muted">
														<?= __('admin.manual_examples') ?>:<br>
														• <?= __('admin.if_1_usd_equals') ?> 0.85 EUR, <?= __('admin.enter') ?> <code>0.85</code><br>
														• <?= __('admin.if_1_usd_equals') ?> 110 JPY, <?= __('admin.enter') ?> <code>110</code><br>
														• <?= __('admin.if_adding_usd_as_default') ?>, <?= __('admin.enter') ?> <code>1</code>
													</small>
												</div>
											</div>
										<?php else: ?>
											<?= __('admin.exchange_rate_help') ?>
										<?php endif; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 mb-3">
										<div class="card bg-light border-0">
											<div class="card-body">
												<div class="d-flex justify-content-between align-items-center">
													<div>
														<h6 class="card-title mb-1">
															<i class="bi bi-toggle-on me-1 text-success"></i><?= __('admin.currency_status') ?>
														</h6>
														<small class="text-muted"><?= __('admin.enable_disable_currency') ?></small>
													</div>
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" <?= (isset($currencys) && $currencys['status'] == 1) ? 'checked' : '' ?> name="status" value="1">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6 mb-3">
										<div class="card bg-warning bg-opacity-10 border-warning border-opacity-25">
											<div class="card-body">
												<div class="d-flex justify-content-between align-items-center">
													<div>
														<h6 class="card-title mb-1">
															<i class="bi bi-star me-1 text-warning"></i><?= __('admin.default_currency') ?>
														</h6>
														<small class="text-muted"><?= __('admin.set_as_base_currency') ?></small>
													</div>
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" <?= (isset($currencys) && $currencys['is_default'] == 1) ? 'checked' : '' ?> name="is_default" value="1">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-3 border-top">
									<div>
										<?php if(isset($currencys) && $currencys['currency_id'] > 0): ?>
											<small class="text-muted">
												<i class="bi bi-clock me-1"></i><?= __('admin.last_updated') ?>: 
												<?= date('M j, Y \a\t g:i A', strtotime($currencys['date_modified'])) ?>
											</small>
										<?php endif; ?>
									</div>
									<div>
										<button type="submit" class="btn btn-primary btn-lg">
											<i class="bi bi-check-circle me-2"></i>
											<?= isset($currencys) && $currencys['currency_id'] > 0 ? __('admin.update_currency') : __('admin.create_currency') ?>
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col-lg-4">
					<div class="card mb-4">
						<div class="card-header bg-info text-white">
							<h6 class="card-title mb-0">
								<i class="bi bi-lightbulb me-2"></i><?= __('admin.formatting_preview') ?>
							</h6>
						</div>
						<div class="card-body">
							<div class="text-center">
								<div class="display-6 mb-2" id="currency-preview">$1,234.56</div>
								<small class="text-muted"><?= __('admin.live_preview') ?></small>
							</div>
						</div>
					</div>

					<?php if(isset($default_currency) && $default_currency): ?>
					<div class="card mb-4">
						<div class="card-header bg-warning text-dark">
							<h6 class="card-title mb-0">
								<i class="bi bi-calculator me-2"></i><?= __('admin.exchange_rate_calculator') ?>
							</h6>
						</div>
						<div class="card-body">
							<div class="text-center mb-3">
								<div class="h5 mb-1">
									<?= __('admin.your_base_currency') ?>: 
									<span class="badge bg-primary"><?= $default_currency['code'] ?></span>
								</div>
								<small class="text-muted"><?= $default_currency['title'] ?></small>
							</div>
							<div class="border-top pt-3">
								<h6 class="text-primary mb-2"><?= __('admin.quick_examples') ?>:</h6>
								<div class="row g-2">
									<div class="col-6">
										<div class="p-2 bg-light rounded text-center">
											<small class="d-block text-muted">EUR</small>
											<strong>0.85</strong>
										</div>
									</div>
									<div class="col-6">
										<div class="p-2 bg-light rounded text-center">
											<small class="d-block text-muted">GBP</small>
											<strong>0.75</strong>
										</div>
									</div>
									<div class="col-6">
										<div class="p-2 bg-light rounded text-center">
											<small class="d-block text-muted">JPY</small>
											<strong>110</strong>
										</div>
									</div>
									<div class="col-6">
										<div class="p-2 bg-light rounded text-center">
											<small class="d-block text-muted">CAD</small>
											<strong>1.25</strong>
										</div>
									</div>
								</div>
								<div class="mt-2">
									<small class="text-muted">
										<i class="bi bi-lightbulb me-1"></i>
										<?= __('admin.rates_based_on_1') ?> <?= $default_currency['code'] ?>
									</small>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<div class="card">
						<div class="card-header bg-success text-white">
							<h6 class="card-title mb-0">
								<i class="bi bi-info-circle me-2"></i><?= __('admin.currency_tips') ?>
							</h6>
						</div>
						<div class="card-body">
							<ul class="list-unstyled mb-0">
								<li class="mb-2">
									<i class="bi bi-check-circle text-success me-2"></i>
									<small><?= __('admin.tip_currency_code') ?></small>
								</li>
								<li class="mb-2">
									<i class="bi bi-check-circle text-success me-2"></i>
									<small><?= __('admin.tip_default_currency') ?></small>
								</li>
								<li class="mb-2">
									<i class="bi bi-check-circle text-success me-2"></i>
									<small><?= __('admin.tip_exchange_rate') ?></small>
								</li>
								<li>
									<i class="bi bi-check-circle text-success me-2"></i>
									<small><?= __('admin.tip_decimal_places') ?></small>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
var currency_list = <?= json_encode($cur) ?>;
var existing_currencies = <?= json_encode(isset($existing_currencies) ? $existing_currencies : []) ?>;
var rightSymbolCurrencies = ['CRC', 'CZK', 'DKK', 'HUF', 'ISK', 'NOK', 'RON', 'SEK'];

$('#currencySelect').on('change',function(){
    var val = $(this).val();
    var $selectedOption = $('#currencySelect option:selected');
    var id = $selectedOption.attr("data-id");
    
    // Check if the selected option is disabled
    if($selectedOption.is(':disabled')) {
        showToast('<?= __('admin.warning') ?>', '<?= __('admin.currency_already_added_warning') ?>', 'warning', 3000);
        $(this).val(''); // Reset selection
        return;
    }
    
    // Clear all fields
    $('#title').val('');
    $('#code').val('');
    $('#symbol_left').val('');
    $('#symbol_right').val('');
    $('#replace_comma_symbol').val(',');
    $('#decimal_symbol').val(',');
    $('#decimal_place').val('');

    if(currency_list[id]){
        $('#title').val(currency_list[id]['name']);
        $('#code').val(currency_list[id]['code']);
        $('#replace_comma_symbol').val(currency_list[id]['replace_comma_symbol'] || ',');
        $('#decimal_symbol').val(currency_list[id]['decimal_symbol'] || '.');
        $('#decimal_place').val(currency_list[id]['decimal_digits']);
        
        var symbol = currency_list[id]['symbol'];
        if (rightSymbolCurrencies.includes(currency_list[id]['code'])) {
            // Symbol goes to the right for these currencies
            $('#symbol_right').val(symbol);
            $('#symbol_left').val('');
        } else {
            // Default to left if not specified
            $('#symbol_left').val(symbol);
            $('#symbol_right').val('');
        }
        
        // Update preview after filling fields
        updateCurrencyPreview();
        checkDuplicateCurrency();
    } else {
        $('#title').val('');
        $('#code').val('');
        $('#symbol_left').val('');
        $('#symbol_right').val('');
        $('#replace_comma_symbol').val(',');
        $('#decimal_symbol').val('.');
        $('#decimal_place').val('');
    }
});

$('#decimal_place').on('keyup', function(e) {
    var val = $(this).val();
    if (val === '' || val === '0') {
        $('#decimal_place_error').removeClass('d-none');
    } else {
        $('#decimal_place_error').addClass('d-none');
    }
});

$("#currency_edit_form").on('submit', function(e) {
    e.preventDefault();
    var $this = $(this);
    var $submitBtn = $this.find('button[type="submit"]');
    var originalText = $submitBtn.html();
    
    $.ajax({
        url: '',
        type: 'POST',
        dataType: 'json',
        data: $this.serialize(),
        beforeSend: function() {
            $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.saving') ?>...');
        },
        complete: function() {
            $submitBtn.prop('disabled', false).html(originalText);
        },
        success: function(json) {
            $this.find(".is-invalid").removeClass("is-invalid");
            $this.find(".invalid-feedback").remove();
            
            if (json['location']) {
                showToast('<?= __('admin.success') ?>', '<?= __('admin.currency_saved_successfully') ?>', 'success', 3000);
                setTimeout(() => {
                    window.location = json['location'];
                }, 1500);
            }
            
            if (json['errors']) {
                $.each(json['errors'], function(i, j) {
                    var $ele = $this.find('[name="' + i + '"]');
                    if ($ele.length) {
                        $ele.addClass("is-invalid");
                        $ele.after("<div class='invalid-feedback'>" + j + "</div>");
                    }
                });
                showToast('<?= __('admin.error') ?>', '<?= __('admin.please_fix_errors') ?>', 'error', 5000);
            }
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.something_went_wrong') ?>', 'error', 5000);
        }
    });
    return false;
});

function updateCurrencyPreview() {
    var symbolLeft = $('#symbol_left').val() || '';
    var symbolRight = $('#symbol_right').val() || '';
    var decimalPlaces = parseInt($('#decimal_place').val()) || 2;
    var decimalSymbol = $('#decimal_symbol').val() || '.';
    var thousandsSep = $('#replace_comma_symbol').val() || ',';
    
    var amount = 1234.56;
    var formattedAmount = amount.toFixed(decimalPlaces);
    
    if (thousandsSep && formattedAmount.length > 4) {
        var parts = formattedAmount.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);
        formattedAmount = parts.join(decimalSymbol);
    } else {
        formattedAmount = formattedAmount.replace('.', decimalSymbol);
    }
    
    var preview = symbolLeft + formattedAmount + symbolRight;
    $('#currency-preview').text(preview);
}

$('#symbol_left, #symbol_right, #decimal_place, #decimal_symbol, #replace_comma_symbol').on('input change', updateCurrencyPreview);

$('#refresh-single-rate').on('click', function() {
    var $btn = $(this);
    var currencyCode = $btn.data('currency-code');
    var originalText = $btn.html();
    
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.refreshing') ?>...');
    
    $.ajax({
        url: '<?= base_url('admincontrol/currency_refresh_single') ?>',
        type: 'POST',
        dataType: 'json',
        data: { currency_code: currencyCode },
        success: function(response) {
            if (response.success && response.rate) {
                $('#value').val(response.rate);
                showToast('<?= __('admin.success') ?>', '<?= __('admin.rate_updated') ?>: ' + response.rate, 'success', 3000);
            } else {
                showToast('<?= __('admin.error') ?>', response.message || '<?= __('admin.failed_to_refresh_rate') ?>', 'error', 5000);
            }
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_refresh_rate') ?>', 'error', 5000);
        },
        complete: function() {
            $btn.prop('disabled', false).html(originalText);
        }
    });
});

$('#fetchExchangeRate').on('click', function() {
    var currencyCode = $('#code').val().toUpperCase();
    var $btn = $(this);
    var originalText = $btn.html();
    
    // Hide tooltip immediately when clicked
    $btn.tooltip('hide');
    
    if (!currencyCode || currencyCode.length !== 3) {
        showToast('<?= __('admin.warning') ?>', '<?= __('admin.please_enter_currency_code_first') ?>', 'warning', 3000);
        $('#code').focus();
        return;
    }
    
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.fetching') ?>...');
    
    $.ajax({
        url: '<?= base_url('admincontrol/currency_refresh_single') ?>',
        type: 'POST',
        dataType: 'json',
        data: { currency_code: currencyCode },
        success: function(response) {
            if (response.success && response.rate) {
                $('#value').val(response.rate);
                // Focus on the value field to show the result clearly
                $('#value').focus().select();
                showToast('<?= __('admin.success') ?>', '<?= __('admin.rate_fetched_successfully') ?>: ' + response.rate + ' ' + response.base_currency, 'success', 4000);
            } else {
                showToast('<?= __('admin.error') ?>', response.message || '<?= __('admin.failed_to_fetch_rate') ?>', 'error', 5000);
            }
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_fetch_rate') ?>', 'error', 5000);
        },
        complete: function() {
            $btn.prop('disabled', false).html(originalText);
            // Ensure tooltip is hidden after operation
            $btn.tooltip('hide');
        }
    });
});

$("input[name='is_default']").on('change', function() {
    if ($(this).is(':checked'))
        $("input[name='value']").val(1);
    else
        $("input[name='value']").val('');
});

</script>


<script>
function checkDuplicateCurrency() {
    var enteredCode = $('#code').val().toUpperCase();
    var enteredTitle = $('#title').val();
    var currentCurrencyId = $('input[name="currency_id"]').val();
    
    var isDuplicateCode = false;
    var isDuplicateTitle = false;
    
    if(existing_currencies && existing_currencies.length > 0) {
        existing_currencies.forEach(function(currency) {
            if(currency.code === enteredCode && currentCurrencyId == 0) {
                isDuplicateCode = true;
            }
            if(currency.title === enteredTitle && currentCurrencyId == 0) {
                isDuplicateTitle = true;
            }
        });
    }
    
    $('#code').removeClass('is-invalid');
    $('#title').removeClass('is-invalid');
    $('.duplicate-warning').remove();
    
    if(isDuplicateCode) {
        $('#code').addClass('is-invalid');
        $('#code').after('<div class="invalid-feedback duplicate-warning"><i class="bi bi-exclamation-triangle me-1"></i><?= __('admin.currency_code_already_exists') ?></div>');
    }
    
    if(isDuplicateTitle) {
        $('#title').addClass('is-invalid');
        $('#title').after('<div class="invalid-feedback duplicate-warning"><i class="bi bi-exclamation-triangle me-1"></i><?= __('admin.currency_name_already_exists') ?></div>');
    }
    
    return !isDuplicateCode && !isDuplicateTitle;
}

$('#code, #title').on('input blur', function() {
    setTimeout(checkDuplicateCurrency, 300);
});

$('#code').on('input', function() {
    var currencyCode = $(this).val().toUpperCase();
    if (currencyCode.length === 3) {
        $('#rate-unit-display').text(currencyCode);
    } else if (currencyCode.length === 0) {
        <?php if(isset($default_currency) && $default_currency): ?>
            $('#rate-unit-display').text('<?= $default_currency['code'] ?>');
        <?php else: ?>
            $('#rate-unit-display').text('<?= __('admin.rate_unit') ?>');
        <?php endif; ?>
    }
});

$(document).ready(function() {
    updateCurrencyPreview();
    checkDuplicateCurrency();
    
    // Initialize tooltips but hide them on click
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Hide all tooltips when clicking anywhere on the page
    $(document).on('click', function() {
        $('[data-bs-toggle="tooltip"]').tooltip('hide');
    });
});
</script>

<?= render_performance_indicator('currency-edit-performance') ?>
