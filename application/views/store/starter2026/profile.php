<?php
/**
 * Starter 2026 — Customer Profile Page
 *
 * @contract  Store API v1 — page: profile
 * @auth      required (redirect to login if guest)
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $userDetails  array   Full customer row (firstname, lastname, email, phone, address, avatar, etc.)
 *   $country      array   List of all countries for the country selector
 *   $settings     array   Store settings
 *   $errors       array   Validation errors from profile-update form submission (may be empty)
 *   $return_to    string  URL to redirect back to after profile update
 */
$avatar = ($client['avatar'] != '')
    ? base_url('assets/images/users/' . $client['avatar'])
    : base_url('assets/store/default/img/blog1.png');
?>

<div class="container">
    <?php if (!empty($return_to)): ?>
    <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
        <i class="fas fa-arrow-left me-2"></i>
        <a href="<?= htmlspecialchars($return_to) ?>" class="alert-link"><?= __('store.return_to_checkout') ?? '← Return to checkout' ?></a>
    </div>
    <?php endif; ?>
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.profile') ?? 'Profile' ?></span>
    </nav>
</div>

<!-- Account Navigation -->
<div class="container">
    <div class="s26-account-nav">
        <a href="<?= $base_url ?>profile" class="s26-account-nav__link active">
            <i class="fas fa-user"></i> <?= __('store.profile') ?? 'Profile' ?>
        </a>
        <a href="<?= $base_url ?>order" class="s26-account-nav__link">
            <i class="fas fa-gift"></i> <?= __('store.orders') ?? 'Orders' ?>
        </a>
        <a href="<?= $base_url ?>my_courses" class="s26-account-nav__link">
            <i class="fas fa-graduation-cap"></i> <?= __('store.my_courses') ?? 'My Courses' ?>
        </a>
        <a href="<?= $base_url ?>shipping" class="s26-account-nav__link">
            <i class="fas fa-truck"></i> <?= __('store.shipping') ?? 'Shipping' ?>
        </a>
        <a href="<?= $base_url ?>wishlist" class="s26-account-nav__link">
            <i class="fas fa-heart"></i> <?= __('store.wishlist') ?? 'Wishlist' ?>
        </a>
        <a href="<?= $base_url ?>logout" class="s26-account-nav__link s26-account-nav__link--danger">
            <i class="fas fa-power-off"></i> <?= __('store.logout') ?? 'Logout' ?>
        </a>
    </div>
</div>

<?php
$s26hdr_icon    = 'fas fa-user';
$s26hdr_eyebrow = __('store.home') . ' &rsaquo; ' . __('store.profile');
$s26hdr_title   = __('store.profile');
$s26hdr_sub     = __('store.edit_profile');
$s26hdr_stats   = [];
include(APPPATH.'views/store/starter2026/_account_header.php');
?>

<section class="s26-profile-page">
    <div class="container">

        <form action="<?= base_url('store/profile' . (!empty($return_to) ? '?return_to=' . urlencode($return_to) : '')) ?>" class="form-horizontal" method="post" id="profile-form" enctype="multipart/form-data">

            <div class="row g-4">
                <!-- Left: Avatar -->
                <div class="col-lg-4">
                    <div class="s26-profile-sidebar">
                        <div class="s26-profile-avatar-wrap">
                            <img id="blah" src="<?= $avatar ?>" class="s26-profile-avatar" alt="<?= __('store.profile') ?? 'Profile' ?>"
                                 onerror="this.src='<?= base_url('assets/store/default/img/blog1.png') ?>'">
                            <div class="s26-profile-avatar-overlay" onclick="$('#uploadBtn').trigger('click')">
                                <i class="fas fa-camera"></i>
                                <span><?= __('store.change_photo') ?? 'Change Photo' ?></span>
                            </div>
                        </div>
                        <input id="uploadBtn" name="avatar" class="upload" type="file" style="display:none;" accept="image/*">
                        <h4 class="s26-profile-name"><?= htmlspecialchars(($userDetails['firstname'] ?? '') . ' ' . ($userDetails['lastname'] ?? '')) ?></h4>
                        <p class="s26-profile-email"><?= htmlspecialchars($userDetails['email'] ?? '') ?></p>
                    </div>
                </div>

                <!-- Right: Form Fields -->
                <div class="col-lg-8">
                    <div class="s26-profile-card">
                        <h3 class="s26-profile-card__title d-flex align-items-center gap-2 mb-4">
                            <i class="fas fa-user-edit"></i>
                            <?= __('store.personal_information') ?? 'Personal Information' ?>
                        </h3>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.first_name') ?? 'First Name' ?> *</label>
                                    <input class="s26-form-input" name="firstname" value="<?= htmlspecialchars($userDetails['firstname'] ?? '') ?>" type="text" placeholder="<?= __('store.enter_your_first_name') ?? 'First name' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.last_name') ?? 'Last Name' ?> *</label>
                                    <input class="s26-form-input" name="lastname" value="<?= htmlspecialchars($userDetails['lastname'] ?? '') ?>" type="text" placeholder="<?= __('store.enter_your_last_name') ?? 'Last name' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.your_email') ?? 'Email' ?> *</label>
                                    <input class="s26-form-input" name="email" id="email" value="<?= htmlspecialchars($userDetails['email'] ?? '') ?>" type="email" placeholder="<?= __('store.enter_your_email_address') ?? 'Email' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.phone_number') ?? 'Phone' ?> *</label>
                                    <link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css?v='.av()) ?>">
                                    <script src="<?= base_url('assets/plugins/tel/js/intlTelInput.min.js') ?>"></script>
                                    <input type="hidden" name='PhoneNumberInput' id="phonenumber-input" value="">
                                    <input onkeypress="return isNumberKey(event);" id="PhoneNumber" class="s26-form-input" type="text" name="PhoneNumber" value="<?= htmlspecialchars($userDetails['phone'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.country') ?? 'Country' ?></label>
                                    <select name="ucountry" class="s26-form-input countries" id="ucountry">
                                        <option value=""><?= __('store.select_country') ?? 'Select Country' ?></option>
                                        <?php if(isset($country)): foreach($country as $countries): ?>
                                        <option <?php if(!empty($userDetails['ucountry']) && $userDetails['ucountry'] == $countries->id) echo 'selected'; ?> value="<?= $countries->id ?>"><?= $countries->name ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.state') ?? 'State' ?></label>
                                    <select class="s26-form-input" name="state"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.city') ?? 'City' ?> *</label>
                                    <input class="s26-form-input" name="ucity" id="ucity" value="<?= htmlspecialchars($userDetails['ucity'] ?? '') ?>" type="text" placeholder="<?= __('store.enter_your_city') ?? 'City' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.pincode') ?? 'Postal Code' ?> *</label>
                                    <input class="s26-form-input" name="uzip" id="uzip" value="<?= htmlspecialchars($userDetails['uzip'] ?? '') ?>" type="text" placeholder="<?= __('store.enter_your_pincode') ?? 'Postal code' ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.full_address') ?? 'Full Address' ?></label>
                                    <textarea class="s26-form-input" name="twaddress" rows="3" placeholder="<?= __('store.enter_your_address') ?? 'Full address' ?>"><?= htmlspecialchars($userDetails['twaddress'] ?? '') ?></textarea>
                                    <?php if(isset($errors) && $errors && isset($errors['twaddress'])): ?>
                                    <div class="text-danger" style="font-size:12px;margin-top:4px"><?= $errors['twaddress'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="s26-profile-password-section">
                            <h3 class="s26-profile-card__title d-flex align-items-center gap-2 mb-4">
                                <i class="fas fa-lock"></i>
                                <?= __('store.change_password') ?? 'Change Password' ?>
                            </h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="s26-form-group">
                                        <label class="s26-form-label"><?= __('store.enter_new_password') ?? 'New Password' ?></label>
                                        <input class="s26-form-input" name="new_password" type="password" placeholder="<?= __('store.enter_new_password') ?? 'New password' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="s26-form-group">
                                        <label class="s26-form-label"><?= __('store.confirm_password') ?? 'Confirm Password' ?></label>
                                        <input class="s26-form-input" name="c_password" type="password" placeholder="<?= __('store.confirm_password') ?? 'Confirm password' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="update-profile" class="s26-btn-primary">
                            <i class="fas fa-save"></i>
                            <?= __('store.update_profile') ?? 'Save Changes' ?>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</section>

<script type="text/javascript">
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            jQuery('#blah').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById("uploadBtn").onchange = function () {
    readURL(this);
};

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
    tel_input.setNumber('<?= $userDetails['phone'] ?? '' ?>');
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

var selected_state = '<?= isset($userDetails) ? ($userDetails['state'] ?? '') : '' ?>';
$(document).delegate('[name="ucountry"]', "change", function(){
    $this = $(this);
    $.ajax({
        url: '<?= base_url('store/getState') ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: $this.val() },
        beforeSend: function(){ $this.prop("disabled", true); },
        complete: function(){ $this.prop("disabled", false); },
        success: function(json){
            var html = '';
            $.each(json['states'], function(i, j){
                var s = '';
                if(selected_state && selected_state == j['id']){
                    s = 'selected'; selected_state = 0;
                }
                html += "<option "+ s +" value='"+ j['id'] +"'>"+ j['name'] +"</option>";
            });
            $('[name="state"]').html(html);
        },
    });
});

$('#update-profile').on('click', function(){
    $("#profile-form").submit();
});

$("#profile-form").submit(function(){
    var errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>'];
    is_valid = false;
    var errorInnerHTML = '';
    if ($("#PhoneNumber").val().trim()) {
        if (tel_input.isValidNumber()) {
            is_valid = true;
            $("#phonenumber-input").val("+"+tel_input.getSelectedCountryData().dialCode +' '+ $("#PhoneNumber").val().trim());
        } else {
            var errorCode = tel_input.getValidationError();
            errorInnerHTML = errorMap[errorCode];
        }
    } else {
        errorInnerHTML = '<?= __('store.mobile_number_is_required') ?>';
    }
    $("#PhoneNumber").parents(".s26-form-group").removeClass("has-error");
    $("#profile-form .text-danger").remove();
    if(!is_valid){
        $("#PhoneNumber").parents(".s26-form-group").addClass("has-error");
        $(".iti").after("<span class='text-danger' style='font-size:12px;display:block;margin-top:4px'>"+ errorInnerHTML +"</span>");
        return false;
    }
});

$('[name="ucountry"]').trigger("change");
</script>
