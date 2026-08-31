<?php
/**
 * Default theme — Customer profile / account settings page
 *
 * @contract  Store API v1 — page: profile
 * @see       Store_cart_payload::page_profile()
 * @see       /store/api/v1/pages/profile  (auth required)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data {id, firstname, lastname, email, ...}
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $user         array   Customer data (same as $client; {id, firstname, lastname, email, phone, ...})
 *   $countries    array   Country list for address form [['code'=>'US','name'=>'United States'], ...]
 *   $return_to    string  URL to redirect back to after profile update (e.g. checkout flow)
 *   $profile_url  string  Form action URL for profile save
 */
?>
<?php if (!empty($return_to)): ?>
<div class="container main-container mb-3">
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="fa fa-arrow-left me-2"></i>
        <a href="<?= htmlspecialchars($return_to) ?>" class="alert-link"><?= __('store.return_to_checkout') ?? '← Return to checkout' ?></a>
    </div>
</div>
<?php endif; ?>
<?php $acc_active = 'profile'; include(APPPATH.'views/store/default/_account_nav.php'); ?>

<?php
$hdr_icon  = 'fa fa-user';
$hdr_title = __('store.profile');
$hdr_sub   = __('store.profile') . ' &rsaquo; ' . __('store.edit_profile');
$hdr_pills = [];
include(APPPATH.'views/store/default/_account_header.php');
?>

<section class="profile-page">
    <div class="container main-container">
        <form action="<?= base_url('store/profile' . (!empty($return_to) ? '?return_to=' . urlencode($return_to) : '')) ?>"
              class="form-horizontal" method="post" id="profile-frm" enctype="multipart/form-data">
            <div class="row">

                <!-- ── Left Sidebar ───────────────────────── -->
                <div class="col-lg-3 mb-4">
                    <div class="acc-profile-card text-center">
                        <?php
                        $avatar = ($client['avatar'] != '')
                            ? base_url('assets/images/users/' . $client['avatar'])
                            : base_url('assets/store/default/img/blog1.png');
                        ?>
                        <div class="acc-avatar-wrap">
                            <img id="blah" src="<?= $avatar ?>" class="acc-avatar" alt="<?= __('store.profile') ?>">
                            <label class="acc-avatar-edit" for="uploadBtn" title="<?= __('store.choose_file') ?>">
                                <i class="fa fa-camera"></i>
                            </label>
                        </div>
                        <input id="uploadBtn" name="avatar" class="upload d-none" type="file">
                        <h6 class="acc-profile-name mt-3 mb-0">
                            <?= htmlspecialchars($userDetails['firstname'] . ' ' . $userDetails['lastname']) ?>
                        </h6>
                        <p class="acc-profile-email"><?= htmlspecialchars($userDetails['email']) ?></p>
                        <button type="submit" class="btn acc-btn-save">
                            <i class="fa fa-check"></i><?= __('store.update_profile') ?>
                        </button>
                    </div>
                </div>

                <!-- ── Right: Form Sections ───────────────── -->
                <div class="col-lg-9">

                    <!-- Personal Information -->
                    <div class="acc-form-card mb-4">
                        <div class="acc-form-card__header">
                            <i class="fa fa-id-card me-2"></i><?= __('store.personal_information') ?? __('store.profile') ?>
                        </div>
                        <div class="acc-form-card__body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.first_name') ?> <span class="text-danger">*</span></label>
                                    <input placeholder="<?= __('store.enter_your_first_name') ?>" name="firstname"
                                           value="<?= htmlspecialchars($userDetails['firstname']) ?>"
                                           class="form-control acc-input" type="text" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.last_name') ?> <span class="text-danger">*</span></label>
                                    <input placeholder="<?= __('store.enter_your_last_name') ?>" name="lastname"
                                           value="<?= htmlspecialchars($userDetails['lastname']) ?>"
                                           class="form-control acc-input" type="text" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.your_email') ?> <span class="text-danger">*</span></label>
                                    <input placeholder="<?= __('store.enter_your_email_address') ?>" name="email" id="email"
                                           value="<?= htmlspecialchars($userDetails['email']) ?>"
                                           class="form-control acc-input" type="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.phone_number') ?> <span class="text-danger">*</span></label>
                                    <link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css?v='.av()) ?>">
                                    <script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
                                    <input type="hidden" name="PhoneNumberInput" id="phonenumber-input" value="">
                                    <input onkeypress="return isNumberKey(event);" id="PhoneNumber"
                                           class="form-control acc-input" type="text" name="PhoneNumber"
                                           value="<?= htmlspecialchars($userDetails['phone']) ?>">
                                    <script>
                                    var tel_input = intlTelInput(document.querySelector("#PhoneNumber"), {
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
                                    $(document).ready(function() {
                                        tel_input.setNumber('<?= $userDetails['phone'] ?>');
                                    });
                                    function isNumberKey(evt) {
                                        var charCode = (evt.which) ? evt.which : event.keyCode;
                                        if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57))
                                            return false;
                                        return true;
                                    }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="acc-form-card mb-4">
                        <div class="acc-form-card__header">
                            <i class="fa fa-map-marker me-2"></i><?= __('store.address') ?? __('store.full_address') ?>
                        </div>
                        <div class="acc-form-card__body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.country') ?></label>
                                    <select name="ucountry" class="form-control acc-input" id="ucountry">
                                        <option value=""><?= __('store.select_country') ?></option>
                                        <?php foreach ($country as $c): ?>
                                        <option value="<?= $c->id ?>" <?= (!empty($userDetails['ucountry']) && $userDetails['ucountry'] == $c->id) ? 'selected' : '' ?>>
                                            <?= $c->name ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.state') ?></label>
                                    <select class="form-control acc-input" name="state"></select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.city') ?> <span class="text-danger">*</span></label>
                                    <input class="form-control acc-input" placeholder="<?= __('store.enter_your_city') ?>"
                                           name="ucity" id="ucity" value="<?= htmlspecialchars($userDetails['ucity']) ?>"
                                           type="text" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.pincode') ?> <span class="text-danger">*</span></label>
                                    <input class="form-control acc-input" placeholder="<?= __('store.enter_your_pincode') ?>"
                                           name="uzip" id="uzip" value="<?= htmlspecialchars($userDetails['uzip']) ?>"
                                           type="text" required>
                                </div>
                                <div class="col-12">
                                    <label class="acc-label"><?= __('store.full_address') ?></label>
                                    <textarea class="form-control acc-input" name="twaddress" rows="3" required><?= isset($userDetails) ? htmlspecialchars($userDetails['twaddress']) : '' ?></textarea>
                                    <?php if ($errors && isset($errors['twaddress'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['twaddress'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="acc-form-card mb-4">
                        <div class="acc-form-card__header">
                            <i class="fa fa-lock me-2"></i><?= __('store.change_password') ?>
                        </div>
                        <div class="acc-form-card__body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.enter_new_password') ?></label>
                                    <input class="form-control acc-input" name="new_password" value="" type="password"
                                           placeholder="••••••••">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.confirm_password') ?></label>
                                    <input class="form-control acc-input" name="c_password" value="" type="password"
                                           placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Save Button -->
                    <div class="d-lg-none text-end mb-4">
                        <button type="submit" class="btn acc-btn-save" style="width:auto;padding:0 32px">
                            <i class="fa fa-check"></i><?= __('store.update_profile') ?>
                        </button>
                    </div>

                </div><!-- /col-lg-9 -->
            </div><!-- /row -->
        </form>
    </div>
</section>

<script>
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { jQuery('#blah').attr('src', e.target.result); };
        reader.readAsDataURL(input.files[0]);
    }
}
document.getElementById("uploadBtn").onchange = function() { readURL(this); };

var selected_state = '<?= isset($userDetails) ? $userDetails['state'] : '' ?>';
$(document).delegate('[name="ucountry"]', "change", function() {
    var $this = $(this);
    $.ajax({
        url: '<?= base_url('store/getState') ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: $this.val() },
        beforeSend: function() { $this.prop("disabled", true); },
        complete:   function() { $this.prop("disabled", false); },
        success: function(json) {
            var html = '';
            $.each(json['states'], function(i, j) {
                var s = (selected_state && selected_state == j['id']) ? 'selected' : '';
                if (s) selected_state = 0;
                html += "<option " + s + " value='" + j['id'] + "'>" + j['name'] + "</option>";
            });
            $('[name="state"]').html(html);
        },
    });
});

$("#profile-frm").submit(function() {
    var errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>'];
    var is_valid = false;
    var errorInnerHTML = '';
    if ($("#PhoneNumber").val().trim()) {
        if (tel_input.isValidNumber()) {
            is_valid = true;
            $("#phonenumber-input").val("+" + tel_input.getSelectedCountryData().dialCode + ' ' + $("#PhoneNumber").val().trim());
        } else {
            errorInnerHTML = errorMap[tel_input.getValidationError()];
        }
    } else {
        errorInnerHTML = '<?= __('store.mobile_number_is_required') ?>';
    }
    $("#PhoneNumber").parents(".acc-input").removeClass("is-invalid");
    $("#profile-frm .phone-error").remove();
    if (!is_valid) {
        $("#PhoneNumber").addClass("is-invalid");
        $(".iti").after("<span class='phone-error text-danger small d-block mt-1'>" + errorInnerHTML + "</span>");
        return false;
    }
});

$('[name="ucountry"]').trigger("change");
</script>
