<?php
/**
 * Starter 2026 — Checkout Shipping Form (partial)
 *
 * @contract  Store API v1 — fragment: checkout_shipping (rendered inside checkout.php)
 *
 * VARIABLES (inherited from checkout.php parent scope)
 *   $allow_shipping    bool   Whether shipping is required for this order
 *   $countries         array  All countries for address selector [{id, name, code}]
 *   $shipping          array  Available shipping methods and their rates
 *   $flat_rate_amount  string Flat-rate shipping amount if flat-rate mode is active
 *   $is_guest_flow     bool   Whether this is a guest checkout session
 */
?>

<?php if(true) { ?>

<div class="shipping-warning"></div>

<?php if(!empty($is_guest_flow)) { ?>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="form-group">
            <label class="s26-form-label"><?= __('store.first_name') ?></label>
            <input type="text" placeholder="<?= __('store.first_name') ?>" name="firstname" class="s26-form-control" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="s26-form-label"><?= __('store.last_name') ?></label>
            <input type="text" placeholder="<?= __('store.last_name') ?>" name="lastname" class="s26-form-control" required>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="s26-form-label"><?= __('store.email_address') ?></label>
            <input type="email" placeholder="<?= __('store.email_address') ?>" name="email" class="s26-form-control" required>
            <input type="hidden" name="classified_checkout" value="1">
        </div>
    </div>
</div>
<?php } ?>

<!-- Saved Addresses -->
<?php if (isset($shipping_address) && !empty($shipping_address) && is_array($shipping_address)): ?>
<div class="s26-saved-addresses mb-4">
    <label class="s26-form-label mb-3">
        <i class="fas fa-bookmark"></i>
        <?= __('store.saved_addresses') ?? 'Saved Addresses' ?>
    </label>
    <div class="row g-3">
        <?php foreach ($shipping_address as $idx => $addr): ?>
        <div class="col-md-6">
            <label class="s26-address-card <?= ($idx == 0) ? 's26-address-card--selected' : '' ?>">
                <input type="radio" name="saved_address" value="<?= $addr['id'] ?? $idx ?>" <?= ($idx == 0) ? 'checked' : '' ?> style="display:none">
                <div class="s26-address-card__check"><i class="fas fa-check-circle"></i></div>
                <div class="s26-address-card__body">
                    <?php if (!empty($addr['address'])): ?>
                    <p class="mb-1 fw-bold" style="font-size:13px"><?= htmlspecialchars($addr['address']) ?></p>
                    <?php endif; ?>
                    <p class="mb-0 text-muted" style="font-size:12px">
                        <?= htmlspecialchars($addr['city'] ?? '') ?><?= !empty($addr['state_name']) ? ', ' . $addr['state_name'] : '' ?>
                        <?= !empty($addr['country_name']) ? ', ' . $addr['country_name'] : '' ?>
                        <?= !empty($addr['zip_code']) ? ' - ' . $addr['zip_code'] : '' ?>
                    </p>
                    <?php if (!empty($addr['phone'])): ?>
                    <p class="mb-0 text-muted" style="font-size:12px"><i class="fas fa-phone" style="font-size:10px"></i> <?= $addr['phone'] ?></p>
                    <?php endif; ?>
                </div>
            </label>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- New Address Form -->
<div class="s26-new-address-form">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="form-group">
                <label class="s26-form-label"><?= __('store.country') ?> <span class="text-danger">*</span></label>
                <?php $selected = isset($shipping) ? $shipping->country_id : '' ?>
                <?php $selected = isset($country_id) ? $country_id : $selected ?>
                <select name="country" class="s26-form-control">
                    <option value=""><?= __('store.select_country') ?? 'Select Country' ?></option>
                    <?php if (isset($countries)): foreach ($countries as $key => $value) { ?>
                        <option <?= $selected == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
                    <?php } endif; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="s26-form-label"><?= __('store.state') ?> <span class="text-danger">*</span></label>
                <select name="state" class="s26-form-control">
                    <option value=""><?= __('store.select_state') ?? 'Select State' ?></option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="s26-form-label"><?= __('store.city') ?> <span class="text-danger">*</span></label>
                <input type="text" placeholder="<?= __('store.city') ?>" name="city" class="s26-form-control" value="<?= isset($shipping) ? $shipping->city : '' ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="s26-form-label"><?= __('store.postal_code') ?></label>
                <input class="s26-form-control" name="zip_code" placeholder="<?= __('store.postal_code') ?>" type="text" value="<?= isset($shipping) ? $shipping->zip_code : '' ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="s26-form-label"><?= __('store.phone_number') ?></label>
                <input type="hidden" id="phonenumber-input" name='PhoneNumberInput' value="">
                <div>
                    <input id="phone" onkeypress="return isNumberKey(event);" class="s26-form-control" type="text" name="phone" value="<?= isset($shipping) ? $shipping->phone : '' ?>">
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
        <div class="col-12">
            <div class="form-group">
                <label class="s26-form-label"><?= __('store.full_address') ?> <span class="text-danger">*</span></label>
                <textarea class="s26-form-control" placeholder="<?= __('store.full_address') ?>" name="address" rows="3"><?= isset($shipping) ? $shipping->address : '' ?></textarea>
            </div>
        </div>
    </div>
</div>

<!-- USPS Shipping Methods -->
<div class="row g-3 mt-2" id="usps-shipping-section" style="display:none;">
    <div class="col-12">
        <div class="form-group">
            <label class="s26-form-label"><i class="fas fa-shipping-fast me-1"></i> <?= __('store.shipping_method') ?? 'Shipping Method' ?></label>
            <div id="usps-shipping-methods" class="s26-shipping-methods">
                <div class="text-muted"><?= __('store.calculating_shipping_rates') ?? 'Calculating rates...' ?></div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="cookies_consent" id="cookies_consent" value="true">
<input type="hidden" name="selected_shipping_method" id="selected_shipping_method" value="">
<input type="hidden" name="shipping_cost" id="shipping_cost" value="0">

<script type="text/javascript">
var selected_state = '<?= isset($shipping) ? $shipping->state_id : '' ?>';

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
                    stateSelect.empty().append('<option value=""><?= __('store.select_state') ?? 'Select State' ?></option>');
                    $.each(response.states, function(index, state) {
                        var sel = (selected_state && selected_state == state.id) ? ' selected' : '';
                        stateSelect.append('<option value="' + state.id + '"' + sel + '>' + state.name + '</option>');
                    });
                }
            });
        }
    }
}

renderStateAndCart(<?= $selected ?>);

$('select[name="country"]').on('change', function() {
    renderStateAndCart($(this).val());
});

// Saved address selection
$(document).on('change', 'input[name="saved_address"]', function(){
    $('.s26-address-card').removeClass('s26-address-card--selected');
    $(this).closest('.s26-address-card').addClass('s26-address-card--selected');
});
$(document).on('click', '.s26-address-card', function(){
    $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}
</script>

<?php } else { ?>
    <?php if(isset($show_blue_message) && $show_blue_message){ ?>
        <div class="alert alert-info s26-alert"><i class="fas fa-info-circle"></i> <?= __('store.shipping_not_allows') ?></div>
    <?php } ?>
<?php } ?>
