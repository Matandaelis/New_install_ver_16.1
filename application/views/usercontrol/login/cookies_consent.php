<?php
$db =& get_instance();
$products = $db->Product_model;
$cookies_consent = $products->getSettings('site', 'cookies_consent');
$cookies_consent_mesag = $products->getSettings('site', 'cookies_consent_mesag');
$terms_content = $theme_settings[0]->terms_content;
?>

<!-- Shared Modal Styles -->
<style>
.aff-modal .modal-content {
    border: none;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}
.aff-modal .modal-header {
    background: linear-gradient(135deg, var(--aff-primary, #4f46e5), var(--aff-primary-dark, #3730a3));
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}
.aff-modal .modal-header .modal-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: #fff;
}
.aff-modal .modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}
.aff-modal .modal-header .btn-close:hover {
    opacity: 1;
}
.aff-modal .modal-body {
    padding: 1.5rem;
    color: #334155;
    font-size: 0.95rem;
    line-height: 1.7;
    max-height: 60vh;
    overflow-y: auto;
}
.aff-modal .modal-body .modal-text {
    margin: 0;
}
.aff-modal .modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 1rem 1.5rem;
    background: #f8fafc;
}
.aff-modal .modal-footer .btn {
    border-radius: 0.5rem;
    font-weight: 600;
    padding: 0.5rem 1.5rem;
    font-size: 0.9rem;
}
.aff-modal .modal-footer .btn-close-modal {
    background: linear-gradient(135deg, var(--aff-primary, #4f46e5), var(--aff-primary-dark, #3730a3));
    border: none;
    color: #fff;
    transition: all 0.3s ease;
}
.aff-modal .modal-footer .btn-close-modal:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(var(--aff-primary-rgb, 79,70,229), 0.3);
}
/* Cookie consent bar */
#cookie-consent-popup {
    z-index: 1060 !important;
    background: rgba(15, 23, 42, 0.92) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-top: 1px solid rgba(255,255,255,0.08) !important;
    padding: 1.25rem 0 !important;
}
#cookie-consent-popup h4 {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
}
#cookie-consent-popup p {
    font-size: 0.9rem !important;
    opacity: 0.85;
}
#cookie-consent-popup .btn {
    border-radius: 0.5rem !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    padding: 0.5rem 1.25rem !important;
    transition: all 0.3s ease !important;
}
#cookie-consent-popup .btn-primary {
    background: linear-gradient(135deg, var(--aff-primary, #4f46e5), var(--aff-primary-dark, #3730a3)) !important;
    border: none !important;
}
#cookie-consent-popup .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(var(--aff-primary-rgb, 79,70,229), 0.4);
}
#cookie-consent-popup .btn-outline-light {
    border-color: rgba(255,255,255,0.25) !important;
}
#cookie-consent-popup .btn-outline-light:hover {
    background: rgba(255,255,255,0.1) !important;
    border-color: rgba(255,255,255,0.4) !important;
}
/* Cookie preferences modal */
.aff-modal .form-check-input:checked {
    background-color: var(--aff-primary, #4f46e5);
    border-color: var(--aff-primary, #4f46e5);
}
.aff-modal .form-check-label {
    font-weight: 500;
    color: #334155;
}
</style>

<!-- Policy Modal (Terms Content) -->
<div class="modal fade aff-modal" id="terms_content" tabindex="-1" aria-labelledby="termsContentTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsContentTitle">
            <i class="bi bi-file-earmark-text me-2"></i><?= __('front.terms_and_conditions') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('front.close') ?>"></button>
      </div>
      <div class="modal-body">
        <p class="modal-text"><?= nl2br($terms_content) ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">
            <i class="bi bi-check-lg me-1"></i><?= __('front.close') ?>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Terms of Use Modal -->
<div class="modal fade aff-modal" id="termOfUse" tabindex="-1" aria-labelledby="termsOfUseTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsOfUseTitle">
            <i class="bi bi-shield-check me-2"></i><?= $tnc['heading'] ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('front.close') ?>"></button>
      </div>
      <div class="modal-body">
        <p class="modal-text"><?= $tnc['content'] ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">
            <i class="bi bi-check-lg me-1"></i><?= __('front.close') ?>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- About Modal -->
<div class="modal fade aff-modal" id="about" tabindex="-1" aria-labelledby="aboutModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="aboutModalTitle">
            <i class="bi bi-info-circle me-2"></i><?= __('front.about') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('front.close') ?>"></button>
      </div>
      <div class="modal-body">
        <p class="modal-text"><?= is_array($setting) ? ($setting['about_content'] ?? '') : ($setting->about_content ?? '') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">
            <i class="bi bi-check-lg me-1"></i><?= __('front.close') ?>
        </button>
      </div>
    </div>
  </div>
</div>


<?php if (!empty($cookies_consent) && $cookies_consent['cookies_consent'] == 1) : ?>
<!-- Cookie consent popup -->
    <div id="cookie-consent-popup" class="fixed-bottom d-flex justify-content-center align-items-center py-4" style="display: none !important;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1" style="color: #fff;">
                        <i class="bi bi-shield-lock me-2"></i><?= __('front.cookie_preferences') ?>
                    </h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.8);">
                    <?php if(!empty($cookies_consent_mesag)){?>
                        <?= @$cookies_consent_mesag['cookies_consent_mesag'];?>
                    <?php }else{?>
                        <?= __('admin.cookies_consent_default_message') ?>
                    <?php }?>
                    </p>
                </div>
                <div class="col-md-4 d-flex justify-content-md-end align-items-center mt-3 mt-md-0 gap-2">
                    <button id="cookie-consent-accept" class="btn btn-primary"><?= __('front.accept') ?? 'Accept' ?></button>
                    <button id="cookie-consent-decline" class="btn btn-outline-light"><?= __('front.decline') ?? 'Decline' ?></button>
                    <button id="cookie-consent-edit" class="btn btn-outline-light"><?= __('front.edit_preferences') ?? 'Edit' ?></button>
                </div>
            </div>
        </div>
    </div>
<!-- Cookie consent popup -->

<!-- Cookie preferences modal -->
    <div class="modal fade aff-modal" id="cookie-preferences-modal" tabindex="-1" aria-labelledby="cookiePreferencesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cookiePreferencesLabel">
                        <i class="bi bi-gear me-2"></i><?= __('front.cookie_preferences') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="cookie-preferences-form">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cookie1" value="affiliate_id">
                            <label class="form-check-label" for="cookie1"><?= __('front.enable_affiliate_tracking') ?></label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i><?= __('front.close') ?>
                    </button>
                    <button type="button" class="btn btn-close-modal" id="cookie-preferences-save">
                        <i class="bi bi-check-lg me-1"></i><?= __('front.save_preferences') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- Cookie preferences modal -->

<!-- Cookie preferences script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var data = null;
        try {
            data = localStorage.getItem("selectedCookies");
            if (!data) {
                data = JSON.stringify({cookie1: true});
                localStorage.setItem("selectedCookies", data);
            }
            data = JSON.parse(data);
        } catch(e) {
            console.error('Error retrieving or parsing "selectedCookies"', e);
        }

        const cookieCheckbox = document.getElementById('cookie1');
        if(data && data['cookie1']){
            $("#affiliate_cookie").val(data['cookie1']);
            cookieCheckbox.checked = data['cookie1'];
        }

        if (!localStorage.getItem("cookieConsent")) {
            document.getElementById("cookie-consent-popup").style.display = "flex";
        }

        document.getElementById("cookie-consent-accept").addEventListener("click", function () {
            localStorage.setItem("cookieConsent", "accepted");
            const selectedCookies = { 'cookie1': true };
            localStorage.setItem("selectedCookies", JSON.stringify(selectedCookies));
            cookieCheckbox.checked = true;
            $("#cookie-consent-popup").remove();
        });

        document.getElementById("cookie-consent-decline").addEventListener("click", function () {
            localStorage.setItem("cookieConsent", "declined");
            const declinedCookies = { 'cookie1': false };
            localStorage.setItem("selectedCookies", JSON.stringify(declinedCookies));
            cookieCheckbox.checked = false;
            $("#cookie-consent-popup").remove();
            document.cookie = "cookie1=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        });

        document.getElementById("cookie-consent-edit").addEventListener("click", function () {
            const myModalEl = document.getElementById('cookie-preferences-modal');
            const myModal = new bootstrap.Modal(myModalEl);
            myModal.show();
        });

        document.getElementById("cookie-preferences-save").addEventListener("click", function () {
            const selectedCookies = {};
            selectedCookies['cookie1'] = cookieCheckbox.checked;
            localStorage.setItem("cookieConsent", "custom");
            localStorage.setItem("selectedCookies", JSON.stringify(selectedCookies));
            const myModalEl = document.getElementById('cookie-preferences-modal');
            const myModal = bootstrap.Modal.getInstance(myModalEl);
            myModal.hide();
        });
    });
</script>
<!-- Cookie preferences script -->
<?php endif; ?>
