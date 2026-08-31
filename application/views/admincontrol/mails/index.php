<?php
$CI =& get_instance();
$CI->config->load('mail_template_categories');
$categoryConfig = $CI->config->item('categories');
$uniqueToCategory = [];
if ($categoryConfig) {
    foreach ($categoryConfig as $catKey => $uniqueIds) {
        foreach ($uniqueIds as $uid) {
            $uniqueToCategory[$uid] = $catKey;
        }
    }
}
$categoryIcons = [
    'user_management' => 'bi-person-gear',
    'subscription' => 'bi-credit-card-2-front',
    'withdrawal' => 'bi-cash-stack',
    'wallet' => 'bi-wallet2',
    'vendor_deposit' => 'bi-bank',
    'vendor_products' => 'bi-box-seam',
    'vendor_forms' => 'bi-ui-checks',
    'vendor_ads' => 'bi-megaphone',
    'vendor_programs' => 'bi-diagram-3',
    'support' => 'bi-headset',
    'other' => 'bi-envelope',
];
$grouped = [];
foreach ($templates as $t) {
    $uid = isset($t['unique_id']) ? $t['unique_id'] : '';
    $cat = isset($uniqueToCategory[$uid]) ? $uniqueToCategory[$uid] : 'other';
    if (!isset($grouped[$cat])) $grouped[$cat] = [];
    $grouped[$cat][] = $t;
}
$displayOrder = ['user_management','subscription','withdrawal','wallet','vendor_deposit','vendor_products','vendor_forms','vendor_ads','vendor_programs','support','other'];
$categoryOrder = [];
foreach ($displayOrder as $k) {
    if (isset($grouped[$k]) && !empty($grouped[$k])) $categoryOrder[] = $k;
}
?>
<div class="container-fluid mails-page overflow-hidden py-3">
    <div class="row">
        <div class="col-12">

            <!-- Email Settings - Premium collapsible bar -->
            <div class="card border-0 mb-4 email-settings-card shadow-sm">
                <div class="card-header email-settings-header py-3 px-4 d-flex align-items-center justify-content-between">
                    <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#email-setting-body" aria-expanded="false" aria-controls="email-setting-body">
                        <div class="email-settings-icon-wrap rounded-3 p-2 me-3">
                            <i class="bi bi-gear-fill text-primary"></i>
                        </div>
                        <div>
                            <span class="fw-bold d-block text-primary"><?= __('admin.email_settings') ?></span>
                            <span class="small text-muted"><?= __('admin.footer_and_logo') ?></span>
                        </div>
                        <i class="bi bi-chevron-down collapse-icon ms-auto"></i>
                    </button>
                </div>

                <div class="collapse" id="email-setting-body">
                    <div class="card-body p-4 border-top bg-light bg-opacity-50">
                        <form action="" method="POST" enctype="multipart/form-data" id="email-settings-form">
                                <div class="row g-4 align-items-start">
                                <!-- Footer text editor -->
                                <div class="col-lg-8">
                                    <div class="card border bg-white shadow-sm">
                                        <div class="card-body p-3">
                                            <label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.footer_text') ?></label>
                                            <div class="mail-shortcode-bar mb-2">
                                                <span class="text-muted small me-2"><?= __('admin.insert') ?>:</span>
                                                <div class="mail-shortcode-strip">
                                                    <button type="button" class="mail-shortcode-btn email-setting-shortcode" data-shortcode="[[website_name]]">[[website_name]]</button>
                                                    <button type="button" class="mail-shortcode-btn email-setting-shortcode" data-shortcode="[[website_url]]">[[website_url]]</button>
                                                    <button type="button" class="mail-shortcode-btn email-setting-shortcode" data-shortcode="[[base_url]]">[[base_url]]</button>
                                                    <button type="button" class="mail-shortcode-btn email-setting-shortcode" data-shortcode="[[website_logo]]">[[website_logo]]</button>
                                                </div>
                                            </div>
                                            <div class="email-footer-editor-wrap">
                                                <textarea name="emailsetting[footer]" class="form-control summernote-img" id="email-setting-footer" rows="4"><?= $emailsetting['footer']; ?></textarea>
                                            </div>
                                            <div class="form-text small mt-1"><?= __('admin.email_footer_description') ?></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Header logo upload -->
                                <div class="col-lg-4">
                                    <div class="card border bg-white shadow-sm">
                                        <div class="card-body p-3">
                                            <label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.header_logo') ?></label>
                                            <div class="email-logo-upload border rounded p-3 bg-white text-center mb-3">
                                                <?php $img = $emailsetting['logo'] ? base_url('assets/images/site/'. $emailsetting['logo']) : base_url('assets/template/images/avatar-1.jpg'); ?>
                                                <img id="email-logo-preview" src="<?= $img ?>" class="rounded img-fluid" style="max-width: 120px; max-height: 80px; object-fit: contain;" alt="Logo">
                                                <p class="small text-muted mt-2 mb-0"><?= __('admin.recommended_size') ?>: 144×80</p>
                                            </div>
                                            <label for="email-logo-input" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                                <i class="bi bi-upload me-1"></i><?= __('admin.choose_file') ?>
                                            </label>
                                            <input type="file" name="emailsetting_logo" id="email-logo-input" class="d-none" accept="image/*">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-check-lg me-1"></i><?= __('admin.save') ?>
                                            </button>
                                            <!-- Test Unsubscribe helper -->
                                            <div class="border-top pt-3 mt-3">
                                                <label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.test_unsubscribe') ?></label>
                                                <p class="small text-muted mb-2"><?= __('admin.test_unsubscribe_desc') ?></p>
                                                <div class="input-group input-group-sm mb-2">
                                                    <input type="email" id="test-unsub-email" class="form-control" placeholder="<?= __('admin.enter_test_email') ?>" value="">
                                                    <button type="button" class="btn btn-outline-secondary" id="btn-generate-unsub-url" title="<?= __('admin.generate_unsubscribe_url') ?>"><i class="bi bi-link-45deg"></i></button>
                                                </div>
                                                <div class="d-flex gap-1 flex-wrap">
                                                    <button type="button" class="btn btn-outline-success btn-sm" id="btn-copy-unsub-url" disabled title="<?= __('admin.copy_link') ?>"><i class="bi bi-clipboard me-1"></i><?= __('admin.copy') ?></button>
                                                    <a href="#" id="btn-open-unsub-url" class="btn btn-outline-info btn-sm text-decoration-none" target="_blank" rel="noopener" style="display:none;" title="<?= __('admin.open_in_new_tab') ?>"><i class="bi bi-box-arrow-up-right me-1"></i><?= __('admin.open_in_new_tab') ?></a>
                                                </div>
                                                <input type="hidden" id="generated-unsub-url" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Unsubscribe page editor -->
                                <div class="col-12 mt-2">
                                    <div class="card border bg-white shadow-sm">
                                        <div class="card-body p-3">
                                            <label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.unsubscribed_page_title') ?> &amp; <?= __('admin.unsubscribed_page_message') ?></label>
                                            <div class="row g-2">
                                                <div class="col-md-12">
                                                    <input type="text" name="email[unsubscribed_page_title]" class="form-control form-control-sm" value="<?= isset($email['unsubscribed_page_title']) ? htmlspecialchars($email['unsubscribed_page_title']) : '' ?>" placeholder="<?= __('admin.unsubscribed_page_title') ?>">
                                                </div>
                                                <div class="col-md-12">
                                                    <textarea name="email[unsubscribed_page_message]" class="form-control form-control-sm" rows="2" placeholder="<?= __('admin.unsubscribed_page_message') ?>"><?= isset($email['unsubscribed_page_message']) ? htmlspecialchars($email['unsubscribed_page_message']) : '' ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-3">
                    <a href="<?= base_url('admincontrol/subscriber_list') ?>" class="card border-0 shadow-sm text-decoration-none h-100 quick-action-card">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="quick-action-icon bg-success bg-opacity-10 text-success rounded-3 p-3 fs-4 flex-shrink-0"><i class="bi bi-envelope-check"></i></div>
                            <div>
                                <div class="fw-bold text-dark"><?= __('admin.subscriber_list') ?></div>
                                <div class="small text-muted"><?= __('admin.manage_subscribed') ?></div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-muted"></i>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a href="<?= base_url('admincontrol/unsubscribe_list') ?>" class="card border-0 shadow-sm text-decoration-none h-100 quick-action-card">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="quick-action-icon bg-danger bg-opacity-10 text-danger rounded-3 p-3 fs-4 flex-shrink-0"><i class="bi bi-envelope-x"></i></div>
                            <div>
                                <div class="fw-bold text-dark"><?= __('admin.unsubscribe_list') ?></div>
                                <div class="small text-muted"><?= __('admin.manage_unsubscribed') ?></div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-muted"></i>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a href="<?= base_url('admincontrol/paymentsetting?tab=email-setting') ?>" class="card border-0 shadow-sm text-decoration-none h-100 quick-action-card">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="quick-action-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 fs-4 flex-shrink-0"><i class="bi bi-gear"></i></div>
                            <div>
                                <div class="fw-bold text-dark"><?= __('admin.email_settings') ?></div>
                                <div class="small text-muted"><?= __('admin.configure_unsubscribe_page') ?></div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-muted"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Mail Templates -->
            <div class="card border-0 overflow-hidden mails-templates-card">
                <div class="mails-templates-header">
                    <div class="mails-header-inner">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-4">
                            <div class="d-flex align-items-center">
                                <div class="mails-header-icon">
                                    <i class="bi bi-envelope-heart"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold text-white"><?= __('admin.mail_templates') ?></h4>
                                    <small class="text-white opacity-90"><?= __('admin.manage_email_templates') ?> · <a href="<?= base_url('admincontrol/paymentsetting?tab=email-setting') ?>" class="text-white text-decoration-underline opacity-90"><i class="bi bi-link-45deg me-1"></i><?= __('admin.configure_unsubscribe_page') ?></a></small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <a href="<?= base_url('admincontrol/unsubscribe_list') ?>" class="btn btn-outline-light btn-sm flex-shrink-0">
                                    <i class="bi bi-envelope-x me-1"></i><?= __('admin.unsubscribe_list') ?>
                                </a>
                                <div class="mails-search-wrap">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" id="template-search" class="form-control" placeholder="<?= __('admin.search_templates') ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <?php if (empty($templates)) { ?>
                        <div class="mails-empty-state text-center py-5">
                            <i class="bi bi-envelope-x"></i>
                            <h4 class="mt-3"><?= __('admin.no_templates_found') ?></h4>
                        </div>
                    <?php } else { ?>
                        <?php /* Filter tabs in one row - click to show that category's content */ ?>
                        <div class="mails-filters-bar">
                            <div class="d-flex flex-nowrap gap-2 align-items-center overflow-x-auto pb-1" id="category-filters">
                                <button type="button" class="btn btn-sm category-filter active flex-shrink-0" data-cat=""><?= __('admin.all') ?> <span class="badge bg-secondary bg-opacity-50 ms-1"><?= count($templates) ?></span></button>
                                <?php foreach ($categoryOrder as $catKey) {
                                    if (!isset($grouped[$catKey]) || empty($grouped[$catKey])) continue;
                                    $icon = isset($categoryIcons[$catKey]) ? $categoryIcons[$catKey] : 'bi-envelope';
                                    $catLabel = __('admin.template_category_'.$catKey);
                                ?>
                                <button type="button" class="btn btn-sm category-filter flex-shrink-0" data-cat="<?= htmlspecialchars($catKey) ?>">
                                    <i class="bi <?= $icon ?> me-1"></i><?= $catLabel ?> <span class="badge bg-secondary bg-opacity-50 ms-1"><?= count($grouped[$catKey]) ?></span>
                                </button>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="mails-tab-content">
                            <?php foreach ($categoryOrder as $catKey) {
                                if (!isset($grouped[$catKey]) || empty($grouped[$catKey])) continue;
                                $items = $grouped[$catKey];
                                $icon = isset($categoryIcons[$catKey]) ? $categoryIcons[$catKey] : 'bi-envelope';
                                $catLabel = __('admin.template_category_'.$catKey);
                            ?>
                            <div class="template-category mb-4" data-category="<?= htmlspecialchars($catKey) ?>">
                                <div class="template-category-header d-flex align-items-center mb-3">
                                    <div class="template-cat-icon"><i class="bi <?= $icon ?>"></i></div>
                                    <h5 class="mb-0 fw-bold"><?= $catLabel ?></h5>
                                    <span class="template-cat-count"><?= count($items) ?></span>
                                </div>
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4 template-cards">
                                    <?php foreach ($items as $value) {
                                        $hasUser = !empty($value['subject']);
                                        $hasAdmin = !empty($value['admin_subject']);
                                        $hasClient = !empty($value['client_subject']);
                                    ?>
                                    <div class="col template-card" data-name="<?= htmlspecialchars(strtolower($value['name'])) ?>" data-category="<?= htmlspecialchars($catKey) ?>">
                                        <div class="card h-100 template-card-inner">
                                            <div class="card-body p-4 d-flex flex-column">
                                                <div class="d-flex align-items-start mb-3 flex-grow-1 min-w-0">
                                                    <div class="template-card-icon"><i class="bi bi-envelope-paper"></i></div>
                                                    <div class="min-w-0 flex-grow-1">
                                                        <h6 class="mb-1 text-truncate fw-semibold" title="<?= htmlspecialchars($value['name']) ?>"><?= htmlspecialchars($value['name']) ?></h6>
                                                        <small class="text-muted text-truncate d-block" title="<?= htmlspecialchars($value['subject']) ?>"><?= htmlspecialchars($value['subject']) ?></small>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-wrap gap-1 mb-3">
                                                    <?php if ($hasUser) { ?><span class="badge rounded-pill template-badge template-badge-user">User</span><?php } ?>
                                                    <?php if ($hasAdmin) { ?><span class="badge rounded-pill template-badge template-badge-admin">Admin</span><?php } ?>
                                                    <?php if ($hasClient) { ?><span class="badge rounded-pill template-badge template-badge-client">Client</span><?php } ?>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    <a href="<?= base_url('admincontrol/mails_edit/'. $value['id']) ?>" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-pencil-square me-1"></i><?= __('admin.edit') ?></a>
                                                    <button type="button" class="btn btn-outline-primary btn-sm btn-send-test" data-id="<?= (int)$value['id'] ?>" data-name="<?= htmlspecialchars($value['name']) ?>" data-has-user="<?= $hasUser ? '1' : '0' ?>" data-has-admin="<?= $hasAdmin ? '1' : '0' ?>" data-has-client="<?= $hasClient ? '1' : '0' ?>" title="<?= __('admin.send_test') ?>"><i class="bi bi-send"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="mails-no-results text-center py-5 d-none"><i class="bi bi-search"></i><p class="mt-2 mb-0"><?= __('admin.no_templates_found') ?></p></div>
                        </div>

                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Send Test Mail Modal -->
<div class="modal fade" id="sendTestModal" tabindex="-1" aria-labelledby="sendTestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendTestModalLabel"><i class="bi bi-send me-2"></i><?= __('admin.send_test') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3" id="send-test-template-name"></p>
                <div class="mb-3">
                    <label class="form-label"><?= __('admin.enter_test_email') ?></label>
                    <input type="email" id="send-test-email" class="form-control" placeholder="admin@example.com">
                    <div class="invalid-feedback" id="send-test-email-error"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= __('admin.send_test') ?> (<?= __('admin.for_user') ?>/<?= __('admin.for_admin') ?>/<?= __('admin.for_client') ?>)</label>
                    <select id="send-test-for" class="form-select">
                        <option value="for-user"><?= __('admin.for_user') ?></option>
                        <option value="for-admin"><?= __('admin.for_admin') ?></option>
                        <option value="for-client"><?= __('admin.for_client') ?></option>
                    </select>
                </div>
                <div id="send-test-result" class="alert d-none" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
                <button type="button" class="btn btn-primary" id="btn-send-test-submit"><i class="bi bi-send me-1"></i><?= __('admin.send_test') ?></button>
            </div>
        </div>
    </div>
</div>

<style>
/* Mails page - super premium layout */
.mails-page .email-settings-icon-wrap { background: rgba(255,255,255,0.95); box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.mails-templates-header { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 35%, #084298 100%); border: none; position: relative; overflow: hidden; }
.mails-templates-header::before { content: ''; position: absolute; top: 0; right: 0; width: 40%; height: 100%; background: radial-gradient(circle at 100% 50%, rgba(255,255,255,0.08) 0%, transparent 70%); pointer-events: none; }
.mails-header-inner { position: relative; z-index: 1; padding: 1.25rem 1.5rem; }
.mails-header-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); border-radius: 0.75rem; font-size: 1.5rem; color: #fff; }
.mails-search-wrap { min-width: 200px; max-width: 100%; }
.mails-search-wrap .input-group { border-radius: 0.5rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.12); }
.mails-search-wrap .input-group-text { background: #fff; border: none; color: #6c757d; padding: 0.5rem 1rem; }
.mails-search-wrap .form-control { border: none; padding: 0.5rem 1rem; }
.mails-filters-bar { padding: 0.75rem 0; margin-bottom: 1rem; border-bottom: 1px solid #e9ecef; }
.mails-filters-bar .category-filter { border-radius: 2rem; padding: 0.35rem 1rem; font-size: 0.8rem; border: 1px solid #dee2e6; background: #fff; color: #495057; white-space: nowrap; transition: all 0.2s ease; }
.mails-filters-bar .category-filter:focus-visible { outline: 2px solid #0d6efd; outline-offset: 2px; }
.mails-filters-bar .category-filter:hover { border-color: #0d6efd; color: #0d6efd; background: rgba(13,110,253,0.04); }
.mails-filters-bar .category-filter.active { background: #0d6efd !important; color: #fff !important; border-color: #0d6efd !important; }
.mails-filters-bar .category-filter.active .badge { background: rgba(255,255,255,0.35) !important; color: #fff !important; }
.mails-tab-content .template-category { transition: opacity 0.2s ease; }
.mails-no-results { background: linear-gradient(180deg, #f8f9fa 0%, #fff 100%); border-radius: 0.5rem; border: 2px dashed #dee2e6; }
.mails-no-results i { font-size: 3rem; color: #adb5bd; }
.template-category-header { gap: 0.75rem; }
.template-cat-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(13,110,253,0.15) 0%, rgba(13,110,253,0.08) 100%); border-radius: 0.5rem; color: #0d6efd; font-size: 1.25rem; }
.template-cat-count { background: #e9ecef; color: #6c757d; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 0.25rem; margin-left: 0.5rem; font-weight: 600; }
.template-card-inner { border: 1px solid #e9ecef; border-radius: 0.75rem; transition: all 0.25s ease; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
.template-card-inner:hover { border-color: rgba(13,110,253,0.4); box-shadow: 0 8px 24px rgba(13,110,253,0.12); transform: translateY(-3px); }
.template-card-icon { width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(13,110,253,0.1) 0%, rgba(13,110,253,0.05) 100%); border-radius: 0.5rem; color: #0d6efd; font-size: 1.25rem; flex-shrink: 0; margin-right: 0.75rem; }
.template-badge { font-size: 0.7rem; font-weight: 500; padding: 0.25rem 0.5rem; }
.template-badge-user { background: rgba(13,202,240,0.12); color: #0aa2c0; }
.template-badge-admin { background: rgba(255,193,7,0.2); color: #997404; }
.template-badge-client { background: rgba(25,135,84,0.12); color: #198754; }
.mails-empty-state { background: linear-gradient(180deg, #f8f9fa 0%, #fff 100%); border-radius: 0.75rem; border: 2px dashed #dee2e6; }
.mails-empty-state i { font-size: 4rem; color: #adb5bd; }
.mails-page .template-category { clear: both; }
.mails-page .template-cards { flex-wrap: wrap; }
.mails-page .template-card .card-body { min-width: 0; overflow: hidden; position: relative; }
.mails-page .template-card .card-body h6 { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.mails-page .template-card .card-body small { overflow: hidden; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
.quick-action-card { transition: all 0.2s ease; border: 1px solid #e9ecef !important; }
.quick-action-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1) !important; border-color: #0d6efd !important; }
.quick-action-icon { width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; }
</style>
<script>
$(document).ready(function() {
    var currentFilter = '';
    function applyFilters() {
        var q = $('#template-search').val().toLowerCase().trim();
        var anyVisible = false;
        $('.template-category').each(function() {
            var cat = $(this).data('category') || '';
            var catMatch = !currentFilter || cat === currentFilter;
            var matchCount = 0;
            $(this).find('.template-card').each(function() {
                var name = $(this).data('name') || '';
                var nameMatch = !q || name.indexOf(q) >= 0;
                var match = catMatch && nameMatch;
                $(this).toggle(match);
                if (match) matchCount++;
            });
            var showCategory = matchCount > 0;
            $(this).toggle(showCategory);
            if (showCategory) anyVisible = true;
        });
        $('.mails-no-results').toggleClass('d-none', anyVisible || (q === '' && currentFilter === ''));
    }
    $('#template-search').on('input', applyFilters);
    $('.category-filter').on('click', function() {
        currentFilter = $(this).data('cat') || '';
        $('.category-filter').removeClass('active');
        $(this).addClass('active');
        applyFilters();
    });
    applyFilters();

    /* Email settings: shortcode insert into footer editor */
    $('.email-setting-shortcode').on('click', function() {
        var sc = $(this).data('shortcode');
        var $ed = $('#email-setting-footer');
        if ($ed.length && typeof $ed.summernote === 'function') {
            try { $ed.summernote('editor.insertText', sc); } catch(e) { $ed.val($ed.val() + sc); }
        } else { $ed.val($ed.val() + sc); }
    });

    /* Email settings: logo preview before save */
    $('#email-logo-input').on('change', function() {
        var f = this.files[0];
        if (f && f.type.match('image.*')) {
            var r = new FileReader();
            r.onload = function() { $('#email-logo-preview').attr('src', r.result); };
            r.readAsDataURL(f);
        }
    });

    /* Email settings: chevron icon when collapse toggle */
    $('#email-setting-body').on('hide.bs.collapse', function() {
        $('.email-settings-card .collapse-icon').removeClass('bi-chevron-up').addClass('bi-chevron-down');
    }).on('show.bs.collapse', function() {
        $('.email-settings-card .collapse-icon').removeClass('bi-chevron-down').addClass('bi-chevron-up');
    });

    /* Test Unsubscribe: generate URL, copy, open */
    var baseUrl = '<?= base_url() ?>';
    $('#btn-generate-unsub-url').on('click', function() {
        var email = $('#test-unsub-email').val().trim();
        if (!email || !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            $('#test-unsub-email').addClass('is-invalid');
            return;
        }
        $('#test-unsub-email').removeClass('is-invalid');
        var encoded = btoa(unescape(encodeURIComponent(email)));
        var url = baseUrl + 'unsubscribe/' + encoded;
        $('#generated-unsub-url').val(url);
        $('#btn-copy-unsub-url').prop('disabled', false);
        $('#btn-open-unsub-url').attr('href', url).show();
    });
    $('#btn-copy-unsub-url').on('click', function() {
        var url = $('#generated-unsub-url').val();
        if (!url) return;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function() {
                if (typeof showToast === 'function') showToast('<?= __('admin.success') ?>', '<?= __('admin.url_copied_to_clipboard') ?>', 'success', 2000);
                else alert('<?= __('admin.url_copied_to_clipboard') ?>');
            });
        } else {
            var $tmp = $('<input>').val(url).appendTo('body').select();
            document.execCommand('copy');
            $tmp.remove();
            if (typeof showToast === 'function') showToast('<?= __('admin.success') ?>', '<?= __('admin.url_copied_to_clipboard') ?>', 'success', 2000);
            else alert('<?= __('admin.url_copied_to_clipboard') ?>');
        }
    });

    /* Send Test: open modal, submit */
    var sendTestModal, sendTestTemplateId;
    $('.btn-send-test').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        sendTestTemplateId = $btn.data('id');
        $('#send-test-template-name').text($btn.data('name'));
        $('#send-test-email').val('');
        $('#send-test-for').val('for-user');
        $('#send-test-result').addClass('d-none').removeClass('alert-success alert-danger');
        var modalEl = document.getElementById('sendTestModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var m = bootstrap.Modal.getOrCreateInstance(modalEl);
            m.show();
        } else if ($.fn.modal) {
            $(modalEl).modal('show');
        }
    });
    $('#btn-send-test-submit').on('click', function() {
        var email = $('#send-test-email').val().trim();
        var testFor = $('#send-test-for').val();
        if (!email || !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            $('#send-test-email').addClass('is-invalid');
            return;
        }
        $('#send-test-email').removeClass('is-invalid');
        var $btn = $('#btn-send-test-submit');
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.send_test') ?>');
        $('#send-test-result').addClass('d-none');
        $.ajax({
            type: 'POST',
            url: '<?= base_url('admincontrol/send_test_mail_template/') ?>' + sendTestTemplateId,
            data: { test_email: email, test_for: testFor },
            dataType: 'json',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        }).done(function(json) {
            var $res = $('#send-test-result');
            $res.removeClass('d-none');
            if (json.success) {
                $res.addClass('alert-success').text(json.success);
                if (typeof showToast === 'function') showToast('<?= __('admin.success') ?>', json.success, 'success', 3000);
            } else if (json.error) {
                $res.addClass('alert-danger').text(json.error);
            }
        }).fail(function() {
            $('#send-test-result').removeClass('d-none').addClass('alert-danger').text('<?= __('admin.something_wrong_try_again') ?>');
        }).always(function() {
            $btn.prop('disabled', false).html(origHtml);
        });
    });
});
</script>