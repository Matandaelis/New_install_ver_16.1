<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();

	$allows = array(
		'user'   => [1 => 1, 2 => 2, 3=>3 , 4=>4, 5=>5 ,7=>7, 8=>8, 9=>9, 10=>10, 11=>11,12=>12,13=>13],
		'admin'  => [1 => 1, 2 => 2, 3=>3 , 4=>4, 5=>5 , 6=>6, 7=>7, 8=>8, 9=>9, 10=>10, 11=>11, 13=>13],
		'client' => [2 => 2, 3=>3 , 6=>6, 7=>7, 8=>8, 9=>9],
	);

	$user_level_changed = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'user_level_changed'")->row()->id;
	$allows['user'][$user_level_changed] = $user_level_changed;
	
	$vendor_create_product = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_create_product'")->row()->id;
	$new_order_for_vendor = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'new_order_for_vendor'")->row()->id;
	
	$new_vendor_deposit_request = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'new_vendor_deposit_request'")->row()->id;
	$vendor_deposit_request_updated = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_deposit_request_updated'")->row()->id;
	
	$allows['admin'][$vendor_create_product] = $vendor_create_product;
	$allows['admin'][$new_order_for_vendor] = $new_order_for_vendor;
	
	$allows['user'][$new_vendor_deposit_request] = $new_vendor_deposit_request;
	$allows['user'][$vendor_deposit_request_updated] = $vendor_deposit_request_updated;
	$allows['admin'][$new_vendor_deposit_request] = $new_vendor_deposit_request;
	$allows['admin'][$vendor_deposit_request_updated] = $vendor_deposit_request_updated;

	$vendor_product_status_0 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_product_status_0'")->row()->id;
	$vendor_product_status_1 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_product_status_1'")->row()->id;
	$vendor_product_status_2 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_product_status_2'")->row()->id;
	$vendor_product_status_3 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_product_status_3'")->row()->id;
	$vendor_order_status_complete = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_order_status_complete'")->row()->id;

	$vendor_create_form = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_create_form'")->row()->id;
	$vendor_form_status_0 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_form_status_0'")->row()->id;
	$vendor_form_status_1 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_form_status_1'")->row()->id;
	$vendor_form_status_2 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_form_status_2'")->row()->id;
	$vendor_form_status_3 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_form_status_3'")->row()->id;

	$order_on_vendor_program = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'order_on_vendor_program'")->row()->id;
	$vendor_create_ads = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_create_ads'")->row()->id;
	$vendor_ads_status_0 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_ads_status_0'")->row()->id;
	$vendor_ads_status_1 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_ads_status_1'")->row()->id;
	$vendor_ads_status_2 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_ads_status_2'")->row()->id;
	$vendor_ads_status_3 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_ads_status_3'")->row()->id;

	$vendor_create_program = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_create_program'")->row()->id;
	$vendor_program_status_0 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_program_status_0'")->row()->id;
	$vendor_program_status_1 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_program_status_1'")->row()->id;
	$vendor_program_status_2 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_program_status_2'")->row()->id;
	$vendor_program_status_3 = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'vendor_program_status_3'")->row()->id;

	$withdrwal_status_change = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'withdrwal_status_change'")->row()->id;
	$send_register_mail_api = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'send_register_mail_api'")->row()->id;
	$wallet_noti_on_hold_wallet = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'wallet_noti_on_hold_wallet'")->row()->id;

	$subscription_status_change = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'subscription_status_change'")->row()->id;
	$allows['user'][$subscription_status_change] = $subscription_status_change;

	$subscription_expire_notification = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'subscription_expire_notification'")->row()->id;
	$allows['user'][$subscription_expire_notification] = $subscription_expire_notification;

	$subscription_buy = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'subscription_buy'")->row()->id;
	$allows['admin'][$subscription_buy] = $subscription_buy;
	$allows['user'][$subscription_buy] = $subscription_buy;

	$new_user_request = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'new_user_request'")->row()->id;
	$allows['admin'][$new_user_request] = $new_user_request;
	$allows['user'][$new_user_request] = $new_user_request;
	$new_user_approved = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'new_user_approved'")->row()->id;
	$allows['admin'][$new_user_approved] = $new_user_approved;
	$allows['user'][$new_user_approved] = $new_user_approved;
	$new_user_declined = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = 'new_user_declined'")->row()->id;
	$allows['admin'][$new_user_declined] = $new_user_declined;
	$allows['user'][$new_user_declined] = $new_user_declined;
	
	$allows['admin'][$send_register_mail_api] = $send_register_mail_api;
	$allows['user'][$send_register_mail_api] = $send_register_mail_api;
	
	$allows['user'][$withdrwal_status_change] = $withdrwal_status_change;

	$allows['user'][$vendor_product_status_0] = $vendor_product_status_0;
	$allows['user'][$vendor_product_status_1] = $vendor_product_status_1;
	$allows['user'][$vendor_product_status_2] = $vendor_product_status_2;
	$allows['user'][$vendor_product_status_3] = $vendor_product_status_3;
	$allows['user'][$vendor_order_status_complete] = $vendor_order_status_complete;
	
	$allows['user'][$vendor_form_status_0] = $vendor_form_status_0;
	$allows['user'][$vendor_form_status_1] = $vendor_form_status_1;
	$allows['user'][$vendor_form_status_2] = $vendor_form_status_2;
	$allows['user'][$vendor_form_status_3] = $vendor_form_status_3;

	$allows['user'][$order_on_vendor_program] = $order_on_vendor_program;
	$allows['user'][$vendor_ads_status_0] = $vendor_ads_status_0;
	$allows['user'][$vendor_ads_status_1] = $vendor_ads_status_1;
	$allows['user'][$vendor_ads_status_2] = $vendor_ads_status_2;
	$allows['user'][$vendor_ads_status_3] = $vendor_ads_status_3;

	$allows['user'][$vendor_program_status_0] = $vendor_program_status_0;
	$allows['user'][$vendor_program_status_1] = $vendor_program_status_1;
	$allows['user'][$vendor_program_status_2] = $vendor_program_status_2;
	$allows['user'][$vendor_program_status_3] = $vendor_program_status_3;
	
	$allows['admin'][$vendor_product_status_0] = $vendor_product_status_0;
	$allows['admin'][$vendor_create_form] = $vendor_create_form;
	$allows['admin'][$vendor_create_ads] = $vendor_create_ads;
	$allows['admin'][$vendor_form_status_0] = $vendor_form_status_0;
	$allows['admin'][$vendor_create_program] = $vendor_create_program;
	$allows['admin'][$vendor_program_status_0] = $vendor_program_status_0;
	$allows['admin'][$vendor_ads_status_0] = $vendor_ads_status_0;
	
	$allows['user'][$wallet_noti_on_hold_wallet] = $wallet_noti_on_hold_wallet;

	$ticketMailTemplates = ['ticket_created_email', 'ticket_reply_email', 'ticket_status_email'];

	foreach ($ticketMailTemplates as $unique_id) {
		$templateID = (int)$this->db->query("SELECT id FROM mail_templates WHERE unique_id = '{$unique_id}'")->row()->id;
		if(!empty($templateID)) {
			$allows['admin'][$templateID] = $allows['user'][$templateID] = $templateID;
		}
	}
?>

<div class="container-fluid mail-editor-page pb-5">
    <div class="row">
        <div class="col-12">

            <!-- Header -->
            <div class="card shadow-sm border-0 mb-4 mail-editor-header-card">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <div class="mail-editor-icon-wrapper me-3">
                                <i class="bi bi-envelope-at fs-3"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold"><?= __('admin.mail_editor') ?></h4>
                                <small class="opacity-75 d-block mt-1"><?= htmlspecialchars($templates['name']) ?></small>
                            </div>
                        </div>
                        <a href="<?= base_url('admincontrol/mails') ?>" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i><?= __('admin.back') ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Split-Pane Editor + Live Preview -->
            <div class="row g-4">
                <!-- Left: Editor -->
                <div class="col-xl-6">
            <div class="card shadow-sm border-0 h-100 mail-editor-card">
                <div class="card-header bg-white border-bottom py-3">
                    <i class="bi bi-pencil-square me-2 text-primary"></i><strong><?= __('admin.mail_editor') ?></strong>
                </div>
                <div class="card-body">
                    <form action="" method="POST" role="form" id="mail_template_form">
                        <input type="hidden" id="template_id" name="id" value="<?= $templates['id'] ?>">
                        
                        <!-- Tab Navigation -->
                        <ul class="nav nav-pills nav-fill mb-4" role="tablist" id="myTab">
                            <?php if($allows['user'][$templates['id']]){ ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="user-tab" data-bs-toggle="pill" data-bs-target="#for-user" type="button" role="tab">
                                        <i class="bi bi-person me-1"></i><?= __('admin.user') ?>
                                    </button>
                                </li>
                            <?php } ?>
                            <?php if($allows['admin'][$templates['id']]){ ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link<?= !$allows['user'][$templates['id']] ? ' active' : '' ?>" id="admin-tab" data-bs-toggle="pill" data-bs-target="#for-admin" type="button" role="tab">
                                        <i class="bi bi-shield-check me-1"></i><?= __('admin.admin') ?>
                                    </button>
                                </li>
                            <?php } ?>
                            <?php if($allows['client'][$templates['id']]){ ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link<?= (!$allows['user'][$templates['id']] && !$allows['admin'][$templates['id']]) ? ' active' : '' ?>" id="client-tab" data-bs-toggle="pill" data-bs-target="#for-client" type="button" role="tab">
                                        <i class="bi bi-people me-1"></i><?= __('admin.client') ?>
                                    </button>
                                </li>
                            <?php } ?>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <?php if($allows['user'][$templates['id']]){ ?>
                                <div class="tab-pane fade show active template" id="for-user" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><?= __('admin.subject') ?></label>
                                        <input type="text" class="form-control" name="subject" value="<?= htmlspecialchars($templates['subject']) ?>" placeholder="<?= __('admin.enter_subject') ?>">
                                    </div>
                                    <?php if (!empty($templates['shortcode'])) { ?>
                                    <div class="mail-shortcode-bar mb-2">
                                        <span class="text-muted small me-2"><?= __('admin.insert') ?>:</span>
                                        <div class="mail-shortcode-strip">
                                            <?php foreach (explode(",", $templates['shortcode']) as $v) {
                                                $sc = '[['. trim($v) .']]';
                                                ?><button type="button" class="mail-shortcode-btn shortcode-insert" data-shortcode="<?= htmlspecialchars($sc) ?>"><?= htmlspecialchars($sc) ?></button><?php
                                            } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold"><?= __('admin.email_content') ?></label>
                                        <textarea class="form-control summernote-img" name="text" rows="10"><?= $templates['text'] ?></textarea>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <?php if($allows['admin'][$templates['id']]){ ?>
                                <div class="tab-pane fade<?= !$allows['user'][$templates['id']] ? ' show active' : '' ?> template" id="for-admin" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><?= __('admin.subject') ?></label>
                                        <input type="text" class="form-control" name="admin_subject" value="<?= htmlspecialchars($templates['admin_subject']) ?>" placeholder="<?= __('admin.enter_subject') ?>">
                                    </div>
                                    <?php if (!empty($templates['shortcode'])) { ?>
                                    <div class="mail-shortcode-bar mb-2">
                                        <span class="text-muted small me-2"><?= __('admin.insert') ?>:</span>
                                        <div class="mail-shortcode-strip">
                                            <?php foreach (explode(",", $templates['shortcode']) as $v) {
                                                $sc = '[['. trim($v) .']]';
                                                ?><button type="button" class="mail-shortcode-btn shortcode-insert" data-shortcode="<?= htmlspecialchars($sc) ?>"><?= htmlspecialchars($sc) ?></button><?php
                                            } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold"><?= __('admin.email_content') ?></label>
                                        <textarea class="form-control summernote-img" name="admin_text" rows="10"><?= $templates['admin_text'] ?></textarea>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <?php if($allows['client'][$templates['id']]){ ?>
                                <div class="tab-pane fade<?= (!$allows['user'][$templates['id']] && !$allows['admin'][$templates['id']]) ? ' show active' : '' ?> template" id="for-client" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><?= __('admin.subject') ?></label>
                                        <input type="text" class="form-control" name="client_subject" value="<?= htmlspecialchars($templates['client_subject']) ?>" placeholder="<?= __('admin.enter_subject') ?>">
                                    </div>
                                    <?php if (!empty($templates['shortcode'])) { ?>
                                    <div class="mail-shortcode-bar mb-2">
                                        <span class="text-muted small me-2"><?= __('admin.insert') ?>:</span>
                                        <div class="mail-shortcode-strip">
                                            <?php foreach (explode(",", $templates['shortcode']) as $v) {
                                                $sc = '[['. trim($v) .']]';
                                                ?><button type="button" class="mail-shortcode-btn shortcode-insert" data-shortcode="<?= htmlspecialchars($sc) ?>"><?= htmlspecialchars($sc) ?></button><?php
                                            } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold"><?= __('admin.email_content') ?></label>
                                        <textarea class="form-control summernote-img" name="client_text" rows="10"><?= $templates['client_text'] ?></textarea>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-3 align-items-end mail-editor-actions">
                            <div class="col-md-5">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i><?= __('admin.submit') ?>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-reset-template">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i><?= __('admin.reset_to_default') ?>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="email" id="test_email" class="form-control" name="test_email" placeholder="<?= __('admin.enter_test_email') ?>">
                                    <button type="button" class="btn btn-success send-test">
                                        <i class="bi bi-send me-1"></i><?= __('admin.send_test') ?>
                                    </button>
                                </div>
                                <div id="test-result" class="mt-2"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                </div>
                <!-- Right: Live Preview Pane -->
                <div class="col-xl-6">
                    <div class="card shadow-sm border-0 h-100 sticky-top mail-preview-card" style="top: 1rem; z-index: 1;">
                        <div class="card-header bg-success bg-opacity-10 border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <span><i class="bi bi-eye me-2 text-success"></i><strong><?= __('admin.live_preview') ?></strong><span class="badge bg-success bg-opacity-25 text-success ms-2" title="<?= __('admin.preview_with_sample_data') ?>"><?= __('admin.sample_data') ?></span></span>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="previewViewport" id="previewDesktop" value="desktop" checked>
                                <label class="btn btn-outline-success" for="previewDesktop" title="<?= __('admin.preview_desktop') ?>"><i class="bi bi-display"></i></label>
                                <input type="radio" class="btn-check" name="previewViewport" id="previewTablet" value="tablet">
                                <label class="btn btn-outline-success" for="previewTablet" title="<?= __('admin.preview_tablet') ?>"><i class="bi bi-tablet"></i></label>
                                <input type="radio" class="btn-check" name="previewViewport" id="previewMobile" value="mobile">
                                <label class="btn btn-outline-success" for="previewMobile" title="<?= __('admin.preview_mobile') ?>"><i class="bi bi-phone"></i></label>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm py-0 px-2 btn-expand-preview" title="<?= __('admin.live_preview') ?>">
                                <i class="bi bi-fullscreen"></i>
                            </button>
                        </div>
                        <div class="card-body p-0 position-relative mail-preview-frame-wrap" style="min-height: 450px;">
                            <div class="mail-preview-viewport" data-viewport="desktop">
                                <iframe id="split-preview-iframe" style="width:100%;min-height:450px;border:none;" title="<?= __('admin.live_preview') ?>"></iframe>
                            </div>
                            <div id="preview-loading" class="position-absolute top-50 start-50 translate-middle text-muted" style="display:none;">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div><?= __('admin.loading') ?>...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Floating Action Bar (mobile) -->
            <div class="mail-editor-sticky-bar d-lg-none">
                <div class="mail-editor-sticky-bar-inner">
                    <button type="submit" form="mail_template_form" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bi bi-save me-1"></i><?= __('admin.submit') ?>
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm btn-reset-template">
                        <i class="bi bi-arrow-counterclockwise me-1"></i><?= __('admin.reset_to_default') ?>
                    </button>
                </div>
            </div>

            <div class="d-lg-none" style="height: 65px;"></div>

        </div>
    </div>
</div>

<!-- Preview Modal (fallback for smaller screens) -->
<div class="modal fade" id="previewMailModal" tabindex="-1" aria-labelledby="previewMailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewMailModalLabel"><i class="bi bi-eye me-2"></i><?= __('admin.live_preview') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="preview-mail-iframe" style="width:100%;min-height:500px;border:none;"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- Hidden trigger for Bootstrap 5 modal (data-bs-toggle) -->
<button type="button" id="previewMailModalTrigger" class="d-none" data-bs-toggle="modal" data-bs-target="#previewMailModal"></button>

<script>
$(document).ready(function() {
    $('#myTab button:first').tab('show');

    /* Unsaved changes warning */
    var formModified = false;
    $('#mail_template_form').on('input change', 'input, textarea, select', function() { formModified = true; });
    $(document).on('summernote.change', '.summernote-img', function() { formModified = true; });
    $('#mail_template_form').on('submit', function() { formModified = false; });
    $(window).on('beforeunload', function() {
        if (formModified) return '<?= addslashes(__('admin.mail_editor_unsaved_changes')) ?>';
    });

    /* Ctrl+S to save */
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $('#mail_template_form').submit();
        }
    });

    /* Split-pane live preview (debounced) */
    var previewDebounce, previewUrl = '<?= base_url("admincontrol/preview_mail_html/".$templates['id']) ?>';
    function triggerPreview() {
        if (previewDebounce) clearTimeout(previewDebounce);
        previewDebounce = setTimeout(function() {
            $('.summernote-img').each(function() {
                var $ed = $(this);
                if (typeof $ed.summernote === 'function') { try { $ed.val($ed.summernote('code')); } catch(e) {} }
            });
            var postData = $('#mail_template_form').serializeArray();
            var testFor = $('.tab-pane.active.template').attr('id') || 'for-user';
            postData.push({name:'test_for', value: testFor}, {name:'test_email', value: 'preview@example.com'});
            $('#preview-loading').show();
            $.ajax({
                type: 'POST', url: previewUrl, data: $.param(postData),
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                success: function(html) {
                    $('#split-preview-iframe').attr('srcdoc', html);
                },
                complete: function() { $('#preview-loading').hide(); }
            });
        }, 500);
    }
    setTimeout(triggerPreview, 800);
    $('#myTab button').on('shown.bs.tab', function() { triggerPreview(); });
    $('#mail_template_form input[name="subject"], #mail_template_form input[name="admin_subject"], #mail_template_form input[name="client_subject"]').on('input change', triggerPreview);
    $(document).on('summernote.change', '.summernote-img', triggerPreview);

    /* Preview viewport toggle (Desktop / Tablet / Mobile) */
    var viewportWidths = { desktop: '100%', tablet: '768px', mobile: '375px' };
    $('input[name="previewViewport"]').on('change', function() {
        var v = $(this).val();
        $('.mail-preview-viewport').attr('data-viewport', v).css('max-width', viewportWidths[v] || '100%');
    });

    /* Expand preview to modal */
    $('.btn-expand-preview').on('click', function() {
        var html = $('#split-preview-iframe').attr('srcdoc');
        if (html) {
            $('#preview-mail-iframe').attr('srcdoc', html);
            var $modal = $('#previewMailModal');
            if (typeof $modal.modal === 'function') $modal.modal('show');
            else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var el = document.getElementById('previewMailModal');
                if (el) new bootstrap.Modal(el).show();
            } else $('#previewMailModalTrigger').trigger('click');
        }
    });

    /* Insert shortcode into active editor (badges + dropdown) */
    function insertShortcode(shortcode) {
        var $editor = $('.tab-pane.active .summernote-img');
        if ($editor.length && typeof $editor.summernote === 'function') {
            try { $editor.summernote('editor.insertText', shortcode); } catch(e) { $editor.val($editor.val() + shortcode); }
        } else {
            var $ta = $('.tab-pane.active textarea[name="text"], .tab-pane.active textarea[name="admin_text"], .tab-pane.active textarea[name="client_text"]');
            if ($ta.length) $ta.val($ta.val() + shortcode);
        }
        formModified = true;
    }
    $('.shortcode-insert').on('click', function() { insertShortcode($(this).data('shortcode')); });


    /* Reset to default */
    $('.btn-reset-template').on('click', function() {
        if (!confirm('<?= __('admin.reset_template_confirm') ?>')) return;
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: '<?= base_url("admincontrol/reset_mail_template/".$templates['id']) ?>',
            dataType: 'json',
            success: function(json) {
                if (json.success) {
                    if(typeof showToast === 'function') showToast('<?= __('admin.success') ?>', json.success, 'success', 3000);
                    setTimeout(function() { location.reload(); }, 500);
                } else if (json.error) {
                    if(typeof showToast === 'function') showToast('<?= __('admin.error') ?>', json.error, 'error', 5000);
                }
            },
            complete: function() { $btn.prop('disabled', false); }
        });
    });

    $('.send-test').on('click', function() {
        const $this = $(this);
        const testEmail = $('#test_email').val();
        
        if (!testEmail || !testEmail.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            if(typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.invalid_email_format') ?>', 'error', 3000);
            } else {
                $('#test-result').html('<div class="alert alert-danger"><?= __('admin.invalid_email_format') ?></div>');
            }
            return;
        }
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: $('.tab-pane.active').find('input, select, textarea').serialize() + 
                  "&id=" + $("#template_id").val() + 
                  "&test_email=" + testEmail + 
                  "&send_test=true&test_for=" + $('.tab-pane.active.template').attr("id"),
            beforeSend: function() {
                $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.sending') ?>...');
            },
            complete: function() {
                $this.prop('disabled', false).html('<i class="bi bi-send me-1"></i><?= __('admin.send_test') ?>');
            },
            success: function(json) {
                if(json['error']) {
                    if(typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', json['error'], 'error', 5000);
                    } else {
                        $('#test-result').html('<div class="alert alert-danger">' + json['error'] + '</div>');
                    }
                }
                if(json['success']) {
                    if(typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', json['success'], 'success', 3000);
                    } else {
                        $('#test-result').html('<div class="alert alert-success">' + json['success'] + '</div>');
                    }
                    $('#test_email').val('');
                }
            }
        });
    });
});
</script>