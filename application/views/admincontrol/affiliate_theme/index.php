<style>
/* ---- Theme Selector: Premium Admin UI ---- */
.theme-card {
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    border: 2px solid transparent;
    background: #fff;
}
.theme-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.1) !important;
}
.theme-card.active-border {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67,97,238,0.12), 0 8px 30px rgba(67,97,238,0.08) !important;
}

/* Image container: shows full theme screenshot with scroll-on-hover */
.theme-image-container {
    position: relative;
    overflow: hidden;
    aspect-ratio: 16 / 10;
    background: linear-gradient(135deg, #f0f2f8, #e8eaf6);
}
.theme-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    transition: object-position 3s ease-in-out;
}
.theme-card:hover .theme-preview {
    object-position: bottom center;
}
.theme-image-container::after {
    content: '\f065';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    bottom: 8px; right: 8px;
    width: 28px; height: 28px;
    border-radius: 6px;
    background: rgba(0,0,0,0.5);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    z-index: 1;
}
.theme-card:hover .theme-image-container::after {
    opacity: 1;
}
/* Scroll indicator overlay on hover */
.theme-image-container::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(180deg, transparent 60%, rgba(0,0,0,0.15) 100%);
    z-index: 1;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}
.theme-card:hover .theme-image-container::before {
    opacity: 1;
}

/* Active badge */
.theme-active-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    z-index: 2;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    box-shadow: 0 3px 10px rgba(16,185,129,0.35);
    animation: badgePulse 2.5s ease-in-out infinite;
}
@keyframes badgePulse {
    0%, 100% { box-shadow: 0 3px 10px rgba(16,185,129,0.35); }
    50% { box-shadow: 0 3px 18px rgba(16,185,129,0.55); }
}

/* Card body: theme name */
.theme-card .card-body {
    padding: 0.85rem 1rem 0.5rem;
    text-align: center;
}
.theme-card .theme-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.1rem;
}
.theme-card .theme-desc {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-bottom: 0;
}

/* Action buttons row */
.theme-actions {
    padding: 0.65rem 0.85rem;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    display: flex;
    gap: 0.4rem;
    align-items: center;
}

/* Activate button (primary action) */
.theme-actions .btn-ta-activate {
    flex: 1 1 auto;
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    color: #fff;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 0.45rem 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.25s ease;
    text-align: center;
}
.theme-actions .btn-ta-activate:hover {
    box-shadow: 0 4px 12px rgba(16,185,129,0.3);
    transform: translateY(-1px);
}

/* Active label (replaces activate when active) */
.theme-actions .active-label {
    flex: 1 1 auto;
    font-size: 0.78rem;
    font-weight: 700;
    color: #10b981;
    text-align: center;
    padding: 0.45rem 0.5rem;
}

/* Icon buttons (edit, preview) */
.theme-actions .btn-ta-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    font-size: 0.82rem;
    padding: 0;
    flex-shrink: 0;
    transition: all 0.25s ease;
}
.theme-actions .btn-ta-icon:hover {
    border-color: #4361ee;
    color: #4361ee;
    background: #eef2ff;
    transform: translateY(-1px);
}
.theme-actions .btn-ta-icon.btn-ta-preview:hover {
    border-color: #8b5cf6;
    color: #8b5cf6;
    background: #f5f3ff;
}

/* Active theme banner */
.active-theme-banner {
    background: linear-gradient(135deg, #eef2ff, #fdf2f8);
    border: 1px solid #e0e7ff;
    border-radius: 0.75rem;
    padding: 1rem 1.5rem;
}

/* Reorder animation */
.theme-item { transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
.theme-item.reorder-fade { opacity: 0; transform: translateY(12px) scale(0.97); }
.theme-item.reorder-enter { opacity: 1; transform: translateY(0) scale(1); }

/* ---- Edit Modal: Game-Changer ---- */
#title-and-content-form-modal .modal-content {
    border: none;
    border-radius: 1.25rem;
    overflow: hidden;
    box-shadow: 0 25px 80px rgba(0,0,0,0.18);
}
#title-and-content-form-modal .modal-header {
    background: linear-gradient(135deg, #4361ee 0%, #7c3aed 100%);
    border-bottom: none;
    padding: 1.4rem 1.75rem;
    position: relative;
    overflow: hidden;
}
#title-and-content-form-modal .modal-header::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -10%;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}
#title-and-content-form-modal .modal-header::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: 15%;
    width: 140px;
    height: 140px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
#title-and-content-form-modal .modal-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #fff;
    position: relative;
    z-index: 1;
}
#title-and-content-form-modal .btn-close-white {
    position: relative;
    z-index: 1;
}
#title-and-content-form-modal .modal-body {
    padding: 1.75rem;
    background: #fafbfd;
}

/* Language selector */
.edit-lang-wrap {
    background: #fff;
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
    border: 1px solid #e8ecf4;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    margin-bottom: 1.5rem;
}
.edit-lang-wrap .form-label {
    font-weight: 700;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #64748b;
    margin-bottom: 0.4rem;
}
.edit-lang-wrap .form-select {
    border-radius: 0.5rem;
    border: 1.5px solid #e2e8f0;
    font-weight: 600;
    transition: border-color 0.3s;
}
.edit-lang-wrap .form-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
}

/* Content tabs: pill style */
.edit-tabs-wrap {
    background: #fff;
    border-radius: 0.75rem;
    border: 1px solid #e8ecf4;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    overflow: hidden;
}
.edit-tabs-nav {
    background: #f8fafc;
    border-bottom: 1px solid #e8ecf4;
    padding: 0.65rem 1rem;
    display: flex;
    gap: 0.5rem;
}
.edit-tabs-nav .nav-link {
    border-radius: 50px !important;
    border: 1.5px solid transparent;
    background: transparent;
    color: #64748b;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 0.5rem 1.1rem;
    transition: all 0.3s ease;
}
.edit-tabs-nav .nav-link:hover {
    background: #eef2ff;
    color: #4361ee;
}
.edit-tabs-nav .nav-link.active {
    background: linear-gradient(135deg, #4361ee, #7c3aed) !important;
    color: #fff !important;
    border-color: transparent !important;
    box-shadow: 0 3px 12px rgba(67,97,238,0.25);
}
.edit-tab-content {
    padding: 1.5rem;
}
.edit-tab-content .form-label {
    font-weight: 700;
    font-size: 0.85rem;
    color: #334155;
    margin-bottom: 0.4rem;
}
.edit-tab-content .form-label i {
    color: #4361ee;
}
.edit-tab-content .form-control {
    border-radius: 0.5rem;
    border: 1.5px solid #e2e8f0;
    transition: all 0.3s ease;
}
.edit-tab-content .form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
}
.edit-tab-content textarea.form-control {
    min-height: 110px;
    resize: vertical;
}

/* Footer buttons */
#title-and-content-form-modal .modal-footer {
    background: #fff;
    border-top: 1px solid #f1f5f9;
    padding: 1rem 1.75rem;
    gap: 0.5rem;
}
#title-and-content-form-modal .modal-footer .btn {
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 0.55rem 1.5rem;
    transition: all 0.3s ease;
}
#title-and-content-form-modal .modal-footer .btn-secondary {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #64748b;
}
#title-and-content-form-modal .modal-footer .btn-secondary:hover {
    background: #e2e8f0;
    color: #334155;
}
#title-and-content-form-modal .modal-footer .btn-primary {
    background: linear-gradient(135deg, #4361ee, #7c3aed);
    border: none;
    box-shadow: 0 4px 14px rgba(67,97,238,0.3);
}
#title-and-content-form-modal .modal-footer .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(67,97,238,0.4);
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 text-dark fw-bold">
                        <i class="fas fa-palette text-primary me-2"></i>
                        <?= __('admin.affiliate_theme') ?>
                    </h4>
                    <p class="text-muted mb-0"><?= __('admin.theme_customization') ?></p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn aff-customize-content-btn position-relative" data-bs-toggle="offcanvas" data-bs-target="#affiliate-page-content-offcanvas" aria-controls="affiliate-page-content-offcanvas">
                        <i class="fas fa-magic me-2"></i><?= __('admin.affiliate_theme_customize_content') ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark shadow-sm" style="font-size:.6rem"><?= __('admin.pro_badge') ?></span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#theme-help-modal">
                        <i class="fas fa-question-circle me-1"></i><?= __('admin.help') ?>
                    </button>
                </div>
            </div>

            <!-- Active Theme Banner -->
            <?php if (isset($login['front_template'])): ?>
            <div class="active-theme-banner d-flex align-items-center mb-4">
                <i class="fas fa-star text-warning me-3 fs-4"></i>
                <div>
                    <h6 class="mb-0 fw-bold text-dark"><?= __('admin.active_theme') ?></h6>
                    <small class="text-muted"><?= __('admin.current_active_theme') ?></small>
                </div>
            </div>
            <?php endif; ?>

            <!-- Theme Grid -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4 theme-row">
                <?php
                    $editable_themes = ['Index 1','Index 2','Index 3','Index 4','Index 5','Index 6','Index 7','Index 8','Index 9','Index 10','Index 11','Index 12','Index 13'];
                    foreach ($front_themes as $theme):
                    $is_active = ($login['front_template'] == $theme['id']);
                    $is_editable = in_array($theme['name'], $editable_themes);
                    $is_multiple = (!empty($theme['id']) && $theme['id'] == 'multiple_pages');
                    // Extract sort number from theme name for reordering
                    $sort_num = 999;
                    if(preg_match('/(\d+)/', $theme['name'], $m)) $sort_num = (int)$m[1];
                    $aff_theme_thumb = !empty($theme['image'])
                        ? base_url('assets/images/themes/' . basename($theme['image']))
                        : base_url('assets/template/images/no_image_yet.png');
                ?>
                    <div class="col theme-item <?php if($is_active) echo 'active-theme'; ?>" data-sort="<?= $sort_num ?>" data-theme-id="<?= $theme['id'] ?>" data-theme-name="<?= htmlspecialchars($theme['name']) ?>">
                        <div class="card h-100 border-0 shadow-sm theme-card <?php if($is_active) echo 'active-border'; ?>">

                            <!-- Theme Image (click for full preview) -->
                            <div class="theme-image-container" role="button"
                                 onclick="openScreenshotPreview(<?= json_encode($aff_theme_thumb) ?>, <?= json_encode($theme['name']) ?>)"
                                 title="<?= __('admin.click_to_preview') ?>">
                                <img class="theme-preview"
                                     src="<?= htmlspecialchars($aff_theme_thumb, ENT_QUOTES, 'UTF-8') ?>"
                                     alt="<?= htmlspecialchars($theme['name'], ENT_QUOTES, 'UTF-8') ?>"
                                     loading="lazy">

                                <?php if($is_active): ?>
                                <div class="theme-active-badge">
                                    <i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Theme Name -->
                            <div class="card-body">
                                <div class="theme-name"><?= $theme['name'] ?></div>
                                <p class="theme-desc"><?= __('admin.theme_description') ?></p>
                            </div>

                            <!-- Action Buttons (always visible) -->
                            <div class="theme-actions">
                                <?php if($is_active): ?>
                                    <span class="active-label">
                                        <i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?>
                                    </span>
                                <?php else: ?>
                                    <button type="button"
                                            data-id="<?= $theme['id'] ?>"
                                            class="btn btn-ta-activate btn-theme-active">
                                        <i class="fas fa-play me-1"></i><?= __('admin.activate') ?>
                                    </button>
                                <?php endif; ?>

                                <?php if($is_multiple): ?>
                                    <a class="btn btn-ta-icon"
                                       href="<?= base_url('themes/multiple_theme') ?>"
                                       title="<?= __('admin.customize_theme') ?>">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if($is_editable): ?>
                                    <button type="button"
                                            data-id="<?= $theme['id'] ?>"
                                            class="btn btn-ta-icon theme-edit-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#title-and-content-form-modal"
                                            title="<?= __('admin.edit_content') ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                <?php endif; ?>

                                <a href="<?= base_url('?tmp_theme='. $theme['id']) ?>"
                                   target="_blank"
                                   class="btn btn-ta-icon btn-ta-preview"
                                   title="<?= __('admin.preview_theme') ?>">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (isset($login) && is_array($login)): ?>
            <?php $this->load->view('admincontrol/affiliate_theme/login_page_blocks_panel', ['login' => $login]); ?>
            <?php endif; ?>

            <!-- Full Screenshot Preview Modal -->
            <div class="modal fade" id="screenshotPreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0" style="border-radius:1rem;overflow:hidden;">
                        <div class="modal-header py-2 px-3" style="background:#1e293b;border:none;">
                            <h6 class="modal-title text-white mb-0" id="screenshotPreviewTitle"></h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0" style="background:#0f172a;max-height:80vh;overflow-y:auto;">
                            <img id="screenshotPreviewImg" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="" style="width:100%;height:auto;display:block;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Themes Message -->
            <?php if (empty($front_themes)): ?>
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-palette fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted"><?= __('admin.no_themes_available') ?></h5>
                <p class="text-muted"><?= __('admin.no_themes_description') ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Content Edit Modal -->
<div class="modal fade" id="title-and-content-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    <?= __('admin.update_home_about_policy') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Language Selection -->
                <div class="edit-lang-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div style="width:38px;height:38px;border-radius:0.5rem;background:linear-gradient(135deg,#4361ee,#7c3aed);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-language text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label mb-1"><?= __('admin.select_language') ?></label>
                            <select class="form-select" name="language_id" id="drpLanguage" onchange="changeLanguage();">
                                <?php if(isset($languages)): ?>
                                    <?php foreach($languages as $language): ?>
                                    <option value="<?= $language['id'] ?>" <?= ($language['is_default'] == 1) ? 'selected' : '' ?>>
                                        <?= $language['name'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Content Tabs -->
                <div class="edit-tabs-wrap">
                    <div class="edit-tabs-nav" id="contentTabs" role="tablist">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab">
                            <i class="fas fa-home me-1"></i><?= __('admin.home_content') ?>
                        </button>
                        <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab">
                            <i class="fas fa-info-circle me-1"></i><?= __('admin.about_content') ?>
                        </button>
                        <button class="nav-link" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy" type="button" role="tab">
                            <i class="fas fa-shield-alt me-1"></i><?= __('admin.policy_content') ?>
                        </button>
                    </div>

                    <div class="tab-content edit-tab-content" id="contentTabsContent">
                        <!-- Home Content Tab -->
                        <div class="tab-pane fade show active" id="home" role="tabpanel">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <p class="small text-secondary mb-0 flex-grow-1"><?= htmlspecialchars(__('admin.aff_theme_demo_hint_home'), ENT_QUOTES, 'UTF-8') ?></p>
                                <button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" id="aff-theme-demo-btn-home" title="<?= htmlspecialchars(__('admin.aff_theme_demo_hint_home'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="fas fa-magic me-1"></i><?= htmlspecialchars(__('admin.aff_theme_demo_btn'), ENT_QUOTES, 'UTF-8') ?>
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-heading me-1"></i><?= __('admin.home_heading') ?>
                                </label>
                                <input id="loginclient_heading" type="text" class="form-control"
                                       value="<?= (isset($loginclient['heading'])) ? $loginclient['heading'] : ""; ?>"
                                       placeholder="<?= __('admin.enter_home_heading') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-align-left me-1"></i><?= __('admin.home_content') ?>
                                </label>
                                <textarea id="loginclient_content" class="form-control" rows="5"
                                          placeholder="<?= __('admin.enter_home_content') ?>"><?= (isset($loginclient['content'])) ? $loginclient['content'] : ""; ?></textarea>
                            </div>
                        </div>

                        <!-- About Content Tab -->
                        <div class="tab-pane fade" id="about" role="tabpanel">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <p class="small text-secondary mb-0 flex-grow-1"><?= htmlspecialchars(__('admin.aff_theme_demo_hint_about'), ENT_QUOTES, 'UTF-8') ?></p>
                                <button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" id="aff-theme-demo-btn-about" title="<?= htmlspecialchars(__('admin.aff_theme_demo_hint_about'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="fas fa-magic me-1"></i><?= htmlspecialchars(__('admin.aff_theme_demo_btn'), ENT_QUOTES, 'UTF-8') ?>
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-info-circle me-1"></i><?= __('admin.about_content') ?>
                                </label>
                                <textarea id="loginclient_about_content" class="form-control" rows="8"
                                          placeholder="<?= __('admin.enter_about_content') ?>"><?= (isset($loginclient['about_content'])) ? $loginclient['about_content'] : ""; ?></textarea>
                            </div>
                        </div>

                        <!-- Policy Content Tab -->
                        <div class="tab-pane fade" id="policy" role="tabpanel">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <p class="small text-secondary mb-0 flex-grow-1"><?= htmlspecialchars(__('admin.aff_theme_demo_hint_policy'), ENT_QUOTES, 'UTF-8') ?></p>
                                <button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" id="aff-theme-demo-btn-policy" title="<?= htmlspecialchars(__('admin.aff_theme_demo_hint_policy'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="fas fa-magic me-1"></i><?= htmlspecialchars(__('admin.aff_theme_demo_btn'), ENT_QUOTES, 'UTF-8') ?>
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-shield-alt me-1"></i><?= __('admin.policy_heading') ?>
                                </label>
                                <input name="policy_heading" id="policy_heading" type="text" class="form-control"
                                       value="<?= (isset($tnc['heading'])) ? $tnc['heading'] : ""; ?>"
                                       placeholder="<?= __('admin.enter_policy_heading') ?>" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-file-alt me-1"></i><?= __('admin.policy_content') ?>
                                </label>
                                <textarea name="policy_content" id="policy_content" class="form-control summernote"
                                          placeholder="<?= __('admin.enter_policy_content') ?>"><?= (isset($tnc['content'])) ? $tnc['content'] : ""; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $aff_theme_content_demo_enc = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
                $aff_theme_content_demo_payload = [
                    'home_heading' => __('admin.aff_theme_demo_home_heading'),
                    'home_content' => __('admin.aff_theme_demo_home_content'),
                    'about_content' => __('admin.aff_theme_demo_about_content'),
                    'policy_heading' => __('admin.aff_theme_demo_policy_heading'),
                    'policy_html' => __('admin.aff_theme_demo_policy_html'),
                ];
                ?>
                <script type="application/json" id="aff-theme-content-demo-json"><?= json_encode($aff_theme_content_demo_payload, $aff_theme_content_demo_enc) ?></script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i><?= __('admin.close') ?>
                </button>
                <button id="loginclient_details_submit" type="button" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i><?= __('admin.save_changes') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Theme Help Modal -->
<div class="modal fade" id="theme-help-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title m-0 fw-bold">
                    <i class="fas fa-question-circle me-2"></i>
                    <?= __('admin.theme_help') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-palette text-primary me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2"><?= __('admin.choose_theme') ?></h6>
                                <p class="text-muted small mb-0"><?= __('admin.choose_theme_desc') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-eye text-success me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2"><?= __('admin.preview_theme') ?></h6>
                                <p class="text-muted small mb-0"><?= __('admin.preview_theme_desc') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-edit text-warning me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2"><?= __('admin.customize_content') ?></h6>
                                <p class="text-muted small mb-0"><?= __('admin.customize_content_desc') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2"><?= __('admin.activate_theme') ?></h6>
                                <p class="text-muted small mb-0"><?= __('admin.activate_theme_desc') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check me-1"></i>
                    <?= __('admin.got_it') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
$(document).ready(function(){

    /**
     * Reorder all theme cards: active theme first, rest sorted numerically.
     * Uses fade-out / DOM reorder / fade-in for a smooth visual transition.
     */
    function reorderThemeCards() {
        var $row = $(".theme-row");
        var $items = $row.children(".theme-item");

        // Fade all items out
        $items.addClass('reorder-fade');

        setTimeout(function() {
            var itemsArr = $items.toArray();

            itemsArr.sort(function(a, b) {
                var aActive = $(a).hasClass('active-theme') ? 0 : 1;
                var bActive = $(b).hasClass('active-theme') ? 0 : 1;
                if (aActive !== bActive) return aActive - bActive;
                var aSort = parseInt($(a).attr('data-sort')) || 999;
                var bSort = parseInt($(b).attr('data-sort')) || 999;
                return aSort - bSort;
            });

            // Detach and reappend in new order
            $.each(itemsArr, function(i, el) {
                $row.append(el);
            });

            // Fade all items back in with stagger
            setTimeout(function() {
                $row.children(".theme-item").each(function(idx) {
                    var $el = $(this);
                    setTimeout(function() {
                        $el.removeClass('reorder-fade').addClass('reorder-enter');
                    }, idx * 50);
                });
                // Clean up class after animation
                setTimeout(function() {
                    $row.children(".theme-item").removeClass('reorder-enter');
                }, itemsArr.length * 50 + 500);
            }, 30);
        }, 350);
    }

    function affReadThemeContentDemo() {
        var el = document.getElementById('aff-theme-content-demo-json');
        if (!el) { return null; }
        try { return JSON.parse(String(el.textContent).trim()); } catch (e) { return null; }
    }
    function affThemeDemoSetPolicyHtml(html) {
        var h = html || '';
        var $pc = $('#policy_content');
        $pc.val(h);
        if (typeof $.fn.summernote !== 'undefined' && $pc.length && $pc.hasClass('summernote')) {
            try { $pc.summernote('code', h); } catch (e1) { /* editor may not be ready */ }
        }
    }
    $('#aff-theme-demo-btn-home').on('click', function() {
        var o = affReadThemeContentDemo();
        if (!o) { return; }
        $('#loginclient_heading').val(o.home_heading || '');
        $('#loginclient_content').val(o.home_content || '');
    });
    $('#aff-theme-demo-btn-about').on('click', function() {
        var o = affReadThemeContentDemo();
        if (!o) { return; }
        $('#loginclient_about_content').val(o.about_content || '');
    });
    $('#aff-theme-demo-btn-policy').on('click', function() {
        var o = affReadThemeContentDemo();
        if (!o) { return; }
        $('#policy_heading').val(o.policy_heading || '');
        affThemeDemoSetPolicyHtml(o.policy_html || '');
    });

    // Theme activation functionality
    $(document).on("click", ".btn-theme-active", function() {
        var id = $(this).data("id");
        var $col = $(this).closest(".theme-item");

        $col.find('.btn-theme-active').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i><?= __('admin.activating') ?>');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                action: 'active_theme',
            },
            success: function(result) {
                if(result.success) {
                    // Remove active state from all
                    $(".theme-item").removeClass('active-theme');
                    $(".theme-card").removeClass('active-border');
                    $(".theme-active-badge").remove();

                    // Restore all active labels back to activate buttons
                    $(".theme-actions .active-label").each(function(){
                        var themeId = $(this).closest('.theme-item').find('[data-id]').first().data('id');
                        if (!themeId) {
                            themeId = $(this).closest('.theme-item').attr('data-theme-id');
                        }
                        $(this).replaceWith(
                            '<button type="button" data-id="'+themeId+'" class="btn btn-ta-activate btn-theme-active"><i class="fas fa-play me-1"></i><?= __('admin.activate') ?></button>'
                        );
                    });

                    // Set new active
                    $col.addClass('active-theme');
                    $col.find('.theme-card').addClass('active-border');
                    $col.find('.theme-image-container').append('<div class="theme-active-badge"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></div>');
                    $col.find('.btn-ta-activate').replaceWith('<span class="active-label"><i class="fas fa-check-circle me-1"></i><?= __('admin.active') ?></span>');

                    showToast(<?= json_encode(__('admin.success')) ?>, result.success, 'success', 4000);

                    // Scroll to top and reorder all cards with animation
                    $("html, body").stop().animate({scrollTop: 0}, 400, 'swing', function() {
                        reorderThemeCards();
                    });
                } else {
                    showToast(<?= json_encode(__('admin.error')) ?>, result.message || <?= json_encode(__('admin.activation_failed')) ?>, 'error', 5000);
                }
            },
            error: function() {
                showToast(<?= json_encode(__('admin.error')) ?>, <?= json_encode(__('admin.activation_failed')) ?>, 'error', 5000);
            },
            complete: function() {
                $col.find('.btn-theme-active').prop('disabled', false).html('<i class="fas fa-play me-1"></i><?= __('admin.activate') ?>');
            }
        });
    });

    // Form validation and submission
    $('#loginclient_details_submit').on('click', function(){
        var $this = $(this);
        var data = {
            loginclient: true,
            tnc: true,
            language_id: $('#drpLanguage').val(),
            heading: $('#loginclient_heading').val(),
            content: $('#loginclient_content').val(),
            about_content: $('#loginclient_about_content').val(),
            policy_heading: $('#policy_heading').val(),
            policy_content: $('#policy_content').val()
        };

        $('.has-error').removeClass('has-error');
        $('.text-danger').remove();

        var hasErrors = false;
        if(data.heading.trim() == '') { error_function('loginclient_heading', '<?= __('admin.home_heading_is_required') ?>'); hasErrors = true; }
        if(data.content.trim() == '') { error_function('loginclient_content', '<?= __('admin.home_content_is_required') ?>'); hasErrors = true; }
        if(data.about_content.trim() == '') { error_function('loginclient_about_content', '<?= __('admin.about_content_is_required') ?>'); hasErrors = true; }
        if(data.policy_heading.trim() == '') { error_function('policy_heading', '<?= __('admin.policy_heading_is_required') ?>'); hasErrors = true; }
        if(data.policy_content.trim() == '') { error_function('policy_content', '<?= __('admin.policy_content_is_required') ?>'); hasErrors = true; }

        if(hasErrors) {
            showToast(<?= json_encode(__('admin.validation_error')) ?>, <?= json_encode(__('admin.please_fill_required_fields')) ?>, 'error', 5000);
            return;
        }

        $this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i><?= __('admin.saving') ?>');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(response) {
                if(response.success) {
                    $('#title-and-content-form-modal').modal('hide');
                    $('.modal-backdrop').remove();
                    showToast(<?= json_encode(__('admin.success')) ?>, response.success, 'success', 4000);
                } else {
                    showToast(<?= json_encode(__('admin.error')) ?>, response.message || <?= json_encode(__('admin.save_failed')) ?>, 'error', 5000);
                }
            },
            error: function() {
                showToast(<?= json_encode(__('admin.error')) ?>, <?= json_encode(__('admin.save_failed')) ?>, 'error', 5000);
            },
            complete: function() {
                $this.prop('disabled', false).html('<i class="fas fa-save me-1"></i><?= __('admin.save_changes') ?>');
            }
        });
    });
});

function openScreenshotPreview(imgSrc, themeName) {
    document.getElementById('screenshotPreviewImg').src = imgSrc;
    document.getElementById('screenshotPreviewTitle').textContent = themeName;
    var modal = new bootstrap.Modal(document.getElementById('screenshotPreviewModal'));
    modal.show();
}

function error_function(cname, msg) {
    var $ele = $("#" + cname);
    $ele.parents(".form-group, .mb-3").addClass("has-error");
    $ele.after("<span class='text-danger small'>" + msg + "</span>");
}

function changeLanguage() {
    getContent('<?= base_url("admincontrol/getLoginContent_ajax") ?>');
    return false;
}

function getContent(url) {
    $("#loginclient_heading").val('');
    $("#loginclient_content").val('');
    $("#loginclient_about_content").val('');
    $("#policy_heading").val('');
    $("#policy_content").val('');
    $('.summernote').summernote('code', '');

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: { loginclient: true, tnc: true, language_id: $('#drpLanguage').val() },
        beforeSend: function() {
            $('#loginclient_details_submit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i><?= __('admin.loading') ?>');
        },
        complete: function() {
            $('#loginclient_details_submit').prop('disabled', false).html('<i class="fas fa-save me-1"></i><?= __('admin.save_changes') ?>');
        },
        success: function(json) {
            if(json) {
                $("#loginclient_heading").val(json['home_heading'] || '');
                $("#loginclient_content").val(json['home_content'] || '');
                $("#loginclient_about_content").val(json['about_content'] || '');
                $("#policy_heading").val(json['policy_heading'] || '');
                $("#policy_content").val(json['policy_content'] || '');
                if($('.summernote').length) { $('.summernote').summernote('code', json.policy_content || ''); }
            }
        },
        error: function() {
            showToast(<?= json_encode(__('admin.error')) ?>, <?= json_encode(__('admin.failed_to_load_content')) ?>, 'error', 5000);
        }
    });
}
</script>
