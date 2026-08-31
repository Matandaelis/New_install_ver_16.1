<?php
/**
 * Store Cart Theme — Developer & Designer Reference
 * Admin view: admincontrol/store_theme_api_doc
 */
?>
<style>
/* ── Sidebar ── */
.tapi-sidebar { position: sticky; top: 10px; max-height: calc(100vh - 24px); overflow-y: auto; scrollbar-width: thin; }
.tapi-sidebar .nav-link { color: #495057; font-size: .8rem; padding: .26rem .85rem; border-left: 3px solid transparent; border-radius: 0; transition: all .12s; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.tapi-sidebar .nav-link:hover { color: #0d6efd; border-left-color: #0d6efd; background: #f0f6ff; }
.tapi-sidebar .nav-link.active { color: #0d6efd; border-left-color: #0d6efd; background: #e8f0fe; font-weight: 600; }
.tapi-sidebar .nav-section-head { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #8b93a1; padding: .75rem .85rem .2rem; display: block; }
.tapi-sidebar .nav-link.d-none-filter { display: none !important; }
#sidebar-search { font-size: .8rem; border-radius: 0; border-left: 0; border-right: 0; border-top: 0; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
#sidebar-search:focus { box-shadow: none; background: #fff; border-bottom-color: #0d6efd; }

/* ── Section anchors ── */
.api-section { scroll-margin-top: 22px; }

/* ── Page / fragment cards ── */
.page-card { border: 0; box-shadow: 0 1px 4px rgba(0,0,0,.08); border-radius: .5rem; overflow: hidden; }
.page-card .card-header { background: linear-gradient(90deg, #f8f9fa 0%, #fff 100%); border-bottom: 1px solid #edf0f7; padding: .65rem 1rem; cursor: pointer; user-select: none; }
.page-card .card-header:hover { background: #f0f5ff; }
.page-card-pages  .card-header { border-left: 4px solid #0d6efd; }
.page-card-frags  .card-header { border-left: 4px solid #198754; }
.page-card.is-open .card-header { background: #f0f5ff; }
.page-card .collapse-icon { transition: transform .2s; }
.page-card.is-open .collapse-icon { transform: rotate(180deg); }

/* ── Variable tables ── */
.field-table { border-collapse: separate; border-spacing: 0; }
.field-table th { white-space: nowrap; font-size: .75rem; font-weight: 600; background: #f1f5f9; color: #475569; padding: .45rem .6rem; }
.field-table td { font-size: .8rem; vertical-align: top; padding: .4rem .6rem; border-color: #f1f5f9; }
.field-table tbody tr:hover { background: #f8fafc; }
.field-table tr.is-required td:first-child { border-left: 2px solid #0d6efd; }
.field-table .var-name { font-family: 'SFMono-Regular',Consolas,monospace; font-size: .78rem; color: #1e40af; font-weight: 500; }
.field-table .var-desc { color: #374151; line-height: 1.45; }
.field-table .var-ex   { font-family: 'SFMono-Regular',Consolas,monospace; font-size: .72rem; color: #6b7280; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; cursor: help; }

/* ── Type badges ── */
.t-array  { background: #dbeafe; color: #1d4ed8; }
.t-string { background: #dcfce7; color: #166534; }
.t-bool   { background: #fef3c7; color: #92400e; }
.t-int    { background: #ede9fe; color: #5b21b6; }
.t-float  { background: #fce7f3; color: #9d174d; }
.t-object { background: #f0fdf4; color: #065f46; }
.t-mixed  { background: #f1f5f9; color: #475569; }
.type-badge { font-size: .68rem; font-weight: 600; padding: .18rem .45rem; border-radius: .25rem; font-family: monospace; }

/* ── Required indicator ── */
.req-yes { font-size: .65rem; font-weight: 700; color: #059669; letter-spacing: .03em; }
.req-no  { font-size: .65rem; color: #9ca3af; }

/* ── Endpoint pill ── */
.ep-pill { font-family: 'SFMono-Regular',Consolas,monospace; font-size: .75rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: .3rem; padding: .18rem .55rem; display: inline-flex; align-items: center; gap: .3rem; }
.badge-get  { background: #2563eb !important; font-size: .68rem; }
.badge-post { background: #059669 !important; font-size: .68rem; }
.auth-badge-yes { background: #f59e0b !important; color: #000; font-size: .68rem; }
.auth-badge-no  { background: #10b981 !important; font-size: .68rem; }
.badge-ssr  { background: #64748b !important; font-size: .68rem; }

/* ── Try-it panel ── */
.try-panel { background: #0f172a; border-radius: .4rem; overflow: hidden; }
.try-panel .try-toolbar { background: #1e293b; padding: .4rem .75rem; display: flex; align-items: center; gap: .5rem; font-size: .75rem; color: #94a3b8; }
.try-panel .try-result  { padding: .5rem .75rem; }
pre.api-pre { background: #0f172a; color: #e2e8f0; border: 0; border-radius: .3rem; padding: .85rem 1rem; font-size: .76rem; max-height: 340px; overflow: auto; }
.json-key  { color: #7dd3fc; }
.json-str  { color: #86efac; }
.json-num  { color: #fca5a5; }
.json-bool { color: #fcd34d; font-weight: 600; }
.json-null { color: #94a3b8; }

/* ── Misc ── */
.section-divider { font-size: .8rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #64748b; border-bottom: 2px solid #e2e8f0; padding-bottom: .4rem; margin-bottom: 1rem; }
.page-meta-pill { font-size: .72rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 999px; padding: .15rem .55rem; color: #475569; }
</style>

<div class="container-fluid px-4 pb-4">
<?php $this->load->view('admincontrol/store/_store_nav'); ?>
    <div class="row g-4">

        <!-- ===== SIDEBAR ===== -->
        <div class="col-xl-2 col-lg-3 d-none d-lg-block">
            <div class="tapi-sidebar">
                <div class="card border-0 shadow-sm" style="border-radius:.5rem;overflow:hidden;">
                    <div class="py-2 px-3 d-flex align-items-center gap-2" style="background:linear-gradient(135deg,#1e3a5f,#0d6efd);">
                        <i class="bi bi-code-slash text-white opacity-75"></i>
                        <span class="text-white fw-bold" style="font-size:.82rem;">Theme API</span>
                        <span class="ms-auto badge bg-white bg-opacity-25 text-white" style="font-size:.65rem;"><?= count($schema['pages']) ?>p · <?= count($schema['fragments']) ?>f</span>
                    </div>
                    <div class="px-2 py-1 border-bottom bg-white">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-0 ps-1 pe-0"><i class="bi bi-search text-muted" style="font-size:.75rem;"></i></span>
                            <input id="sidebar-search" type="text" class="form-control border-0 ps-1" placeholder="Search sections…" autocomplete="off">
                        </div>
                    </div>
                    <nav class="nav flex-column py-1" id="sidebar-nav">
                        <span class="nav-section-head">Getting Started</span>
                        <a class="nav-link" href="#sec-how-it-works"><i class="bi bi-eye me-1 opacity-50"></i>Overview</a>
                        <a class="nav-link" href="#sec-new-theme"><i class="bi bi-plus-circle me-1 opacity-50"></i>Create a Theme</a>
                        <a class="nav-link ps-4" href="#sec-new-theme" onclick="setTimeout(()=>document.getElementById('tab-php-btn').click(),120)"><i class="bi bi-file-earmark-code me-1 opacity-50"></i>PHP Views</a>
                        <a class="nav-link ps-4" href="#sec-new-theme" onclick="setTimeout(()=>document.getElementById('tab-css-btn').click(),120)"><i class="bi bi-brush me-1 opacity-50"></i>CSS</a>
                        <a class="nav-link ps-4" href="#sec-new-theme" onclick="setTimeout(()=>document.getElementById('tab-js-btn').click(),120)"><i class="bi bi-lightning me-1 opacity-50"></i>JavaScript</a>
                        <a class="nav-link ps-4" href="#sec-new-theme" onclick="setTimeout(()=>document.getElementById('tab-assets-btn').click(),120)"><i class="bi bi-images me-1 opacity-50"></i>Images</a>
                        <a class="nav-link ps-4" href="#sec-new-theme" onclick="setTimeout(()=>document.getElementById('tab-register-btn').click(),120)"><i class="bi bi-file-earmark-check me-1 opacity-50"></i>Register &amp; Deploy</a>
                        <a class="nav-link ps-4" href="#sec-translations"><i class="bi bi-translate me-1 opacity-50"></i>Translations</a>
                        <a class="nav-link" href="#sec-global"><i class="bi bi-globe me-1 opacity-50"></i>Global Variables</a>

                        <span class="nav-section-head mt-1"><i class="bi bi-file-earmark me-1"></i>Pages <span class="ms-1 badge bg-primary bg-opacity-15 text-primary" style="font-size:.6rem;"><?= count($schema['pages']) ?></span></span>
                        <?php foreach ($schema['pages'] as $p): ?>
                        <a class="nav-link" href="#page-<?= $p['id'] ?>" title="<?= $p['view'] ?>">
                            <?php if ($p['auth']): ?><i class="bi bi-lock-fill opacity-25 me-1" style="font-size:.65rem;"></i><?php endif; ?>
                            <?= ucfirst(str_replace('_', ' ', $p['id'])) ?>
                            <span class="ms-auto text-muted" style="font-size:.65rem;"><?= count($p['fields']) ?>v</span>
                        </a>
                        <?php endforeach; ?>

                        <span class="nav-section-head mt-1"><i class="bi bi-puzzle me-1"></i>Fragments <span class="ms-1 badge bg-success bg-opacity-15 text-success" style="font-size:.6rem;"><?= count($schema['fragments']) ?></span></span>
                        <?php foreach ($schema['fragments'] as $f): ?>
                        <a class="nav-link" href="#frag-<?= $f['id'] ?>"><?= str_replace('_', ' ', $f['id']) ?></a>
                        <?php endforeach; ?>

                        <span class="nav-section-head mt-1"><i class="bi bi-terminal me-1"></i>API</span>
                        <a class="nav-link" href="#sec-endpoints"><i class="bi bi-list me-1 opacity-50"></i>All Routes</a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="col-xl-10 col-lg-9">

            <!-- Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body" style="background: linear-gradient(135deg,#0d6efd15,#6610f215);">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="rounded-3 p-3 bg-primary text-white fs-3"><i class="fas fa-paint-brush"></i></div>
                        <div>
                            <h4 class="fw-bold mb-1"><?= __('admin.store_theme_api_doc_title') ?></h4>
                            <p class="mb-0 text-muted"><?= __('admin.store_theme_api_doc_subtitle') ?></p>
                        </div>
                        <div class="ms-auto d-flex gap-2 flex-wrap">
                            <a href="<?= base_url('store/api/v1/theme/manifest') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-code me-1"></i>JSON Manifest
                            </a>
                            <a href="<?= base_url('admincontrol/store_setting') ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-cog me-1"></i>Store Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====== HOW IT WORKS ====== -->
            <div id="sec-how-it-works" class="api-section mb-4">
                <div class="card shadow-sm border-0" style="border-radius:.6rem;overflow:hidden;">
                    <div class="card-header d-flex align-items-center gap-2 border-0" style="background:linear-gradient(135deg,#1e3a5f,#0d6efd);padding:.85rem 1.25rem;">
                        <i class="bi bi-diagram-3-fill text-white opacity-75 fs-5"></i>
                        <strong class="text-white"><?= __('admin.store_theme_api_intro_heading') ?></strong>
                        <span class="ms-auto badge bg-white bg-opacity-25 text-white" style="font-size:.7rem;">Full workflow — Build &amp; Deploy</span>
                    </div>
                    <div class="card-body pt-4">

                        <!-- Who is this for -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="rounded-3 p-3 h-100 text-center" style="background:#fdf2f8;border:1px solid #f9a8d4;">
                                    <div class="mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#fce7f3;">
                                        <i class="bi bi-palette-fill" style="color:#db2777;font-size:1.3rem;"></i>
                                    </div>
                                    <strong style="font-size:.9rem;">Designer</strong>
                                    <p class="small text-muted mt-1 mb-0">Reads the PAGES section to know what data is available on each page, then designs HTML/CSS mockups using the variable names as placeholders.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="rounded-3 p-3 h-100 text-center" style="background:#eff6ff;border:1px solid #93c5fd;">
                                    <div class="mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#dbeafe;">
                                        <i class="bi bi-code-slash" style="color:#1d4ed8;font-size:1.3rem;"></i>
                                    </div>
                                    <strong style="font-size:.9rem;">Developer</strong>
                                    <p class="small text-muted mt-1 mb-0">Creates view files and assets in the theme folder using only the documented <code>$variable</code> names — zero backend changes needed.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="rounded-3 p-3 h-100 text-center" style="background:#f0fdf4;border:1px solid #86efac;">
                                    <div class="mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#d1fae5;">
                                        <i class="bi bi-person-gear" style="color:#059669;font-size:1.3rem;"></i>
                                    </div>
                                    <strong style="font-size:.9rem;">Store Owner</strong>
                                    <p class="small text-muted mt-1 mb-0">Receives the finished theme folder, drops it into the server, then activates it in Store Settings → Appearance — no coding required.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FLOWCHART — compact grid -->
                        <div class="rounded-3 p-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fw-bold text-uppercase" style="font-size:.75rem;color:#1e3a5f;letter-spacing:.06em;">
                                    <i class="bi bi-diagram-3 me-2 text-primary"></i>Build &amp; Deploy — 8 Steps
                                </span>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-normal" style="font-size:.7rem;">
                                    <i class="bi bi-rocket-takeoff me-1"></i>Zero backend changes needed
                                </span>
                            </div>
                            <?php
                            $flow_steps = [
                                ['color'=>'#0d6efd','icon'=>'bi-book-half',
                                 'title'=>'Read the contract',
                                 'desc' =>'Study the PAGES section — every <code>$variable</code> listed per page.'],
                                ['color'=>'#7c3aed','icon'=>'bi-folder-plus',
                                 'title'=>'Create theme folder',
                                 'desc' =>'<code>views/store/<b>mytheme</b>/</code> + <code>assets/store/<b>mytheme</b>/</code>'],
                                ['color'=>'#0891b2','icon'=>'bi-file-earmark-code',
                                 'title'=>'Build each view file',
                                 'desc' =>'One PHP file per page. Only documented variables — no DB calls.'],
                                ['color'=>'#059669','icon'=>'bi-brush',
                                 'title'=>'Add CSS, JS &amp; images',
                                 'desc' =>'Bootstrap 5 &amp; jQuery are pre-loaded — never include them again.'],
                                ['color'=>'#d97706','icon'=>'bi-translate',
                                 'title'=>'Add translation keys',
                                 'desc' =>'<code>__(\'store.key\')</code> for every string → add to both <code>store.php</code> files.'],
                                ['color'=>'#db2777','icon'=>'bi-upload',
                                 'title'=>'Deploy to server',
                                 'desc' =>'Upload the <code>views/store/mytheme/</code> and <code>assets/store/mytheme/</code> folders.'],
                                ['color'=>'#0d6efd','icon'=>'bi-file-earmark-check',
                                 'title'=>'Register theme.json',
                                 'desc' =>'Drop a <code>theme.json</code> in your folder — auto-discovered, update-safe.'],
                                ['color'=>'#059669','icon'=>'bi-check2-circle',
                                 'title'=>'Activate in Settings',
                                 'desc' =>'Store Settings → Appearance → Store Theme → select &amp; save.'],
                            ];
                            ?>
                            <div class="row g-2">
                            <?php foreach ($flow_steps as $i => $step): ?>
                                <div class="col-6 col-md-3">
                                    <div class="h-100 rounded-2 p-2" style="background:#fff;border:1px solid <?= $step['color'] ?>33;">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                                  style="width:26px;height:26px;min-width:26px;background:<?= $step['color'] ?>;font-size:.7rem;">
                                                <?= $i+1 ?>
                                            </span>
                                            <span class="fw-semibold" style="color:<?= $step['color'] ?>;font-size:.75rem;line-height:1.2;"><?= $step['title'] ?></span>
                                        </div>
                                        <p class="mb-0 text-muted ps-1" style="font-size:.7rem;line-height:1.45;"><?= $step['desc'] ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            </div>

                            <!-- Rules row -->
                            <div class="row g-2 mt-2">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-2 rounded-2 px-2 py-1" style="background:#eff6ff;font-size:.72rem;">
                                        <i class="bi bi-lightning-fill text-primary flex-shrink-0"></i>
                                        <span><strong>Zero backend changes.</strong> Data is passed automatically — just build views.</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-2 rounded-2 px-2 py-1" style="background:#f0fdf4;font-size:.72rem;">
                                        <i class="bi bi-shield-check text-success flex-shrink-0"></i>
                                        <span><strong>No DB in views.</strong> Never call <code>$this->db</code> or models from inside theme files.</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-2 rounded-2 px-2 py-1" style="background:#fff8f0;font-size:.72rem;">
                                        <i class="bi bi-eye-fill text-warning flex-shrink-0"></i>
                                        <span><strong>Test first.</strong> Use a dev environment, then activate via Store Settings.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== HOW TO CREATE A NEW THEME ====== -->
            <div id="sec-new-theme" class="api-section mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-plus-circle text-success"></i>
                        <strong><?= __('admin.store_theme_api_new_theme_heading') ?></strong>
                    </div>
                    <div class="card-body">

                        <!-- Quick steps -->
                        <div class="row g-3 mb-4">
                            <?php
                            $qsteps = [
                                ['num'=>'1','icon'=>'bi-folder-plus','color'=>'#0d6efd','bg'=>'#e8f0fe',
                                 'text'=> __('admin.store_theme_api_step_1')],
                                ['num'=>'2','icon'=>'bi-file-earmark-code','color'=>'#7c3aed','bg'=>'#ede9fe',
                                 'text'=> __('admin.store_theme_api_step_3')],
                                ['num'=>'3','icon'=>'bi-palette','color'=>'#059669','bg'=>'#d1fae5',
                                 'text'=> __('admin.store_theme_api_step_2')],
                                ['num'=>'4','icon'=>'bi-check2-circle','color'=>'#d97706','bg'=>'#fef3c7',
                                 'text'=> __('admin.store_theme_api_step_4')],
                            ];
                            ?>
                            <?php foreach ($qsteps as $qs): ?>
                            <div class="col-sm-6 col-lg-3">
                                <div class="border rounded-3 p-3 h-100" style="border-color:<?= $qs['bg'] ?>!important;background:<?= $qs['bg'] ?>22;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                             style="width:28px;height:28px;min-width:28px;background:<?= $qs['color'] ?>;font-size:.75rem;">
                                            <?= $qs['num'] ?>
                                        </div>
                                        <i class="bi <?= $qs['icon'] ?>" style="color:<?= $qs['color'] ?>;font-size:1.1rem;"></i>
                                    </div>
                                    <p class="small mb-0" style="line-height:1.5;"><?= $qs['text'] ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- 3 Golden Rules -->
                        <div class="alert alert-warning d-flex gap-3 align-items-start mb-4 py-2 px-3" style="font-size:.83rem;">
                            <i class="bi bi-exclamation-triangle-fill mt-1" style="font-size:1rem;"></i>
                            <div>
                                <strong>3 golden rules for theme developers</strong>
                                <ol class="mb-0 mt-1 ps-3">
                                    <li><?= __('admin.store_theme_api_rule_nodb') ?></li>
                                    <li><?= __('admin.store_theme_api_rule_novars') ?></li>
                                    <li><?= __('admin.store_theme_api_rule_noecho') ?></li>
                                </ol>
                            </div>
                        </div>

                        <!-- ── Tabs ── -->
                        <ul class="nav nav-tabs mb-3" id="themeGuideTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-php-btn"   data-bs-toggle="tab" data-bs-target="#tab-php"    type="button" role="tab">
                                    <i class="bi bi-file-earmark-code me-1 text-primary"></i><?= __('admin.store_theme_api_tab_phpviews') ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-css-btn"   data-bs-toggle="tab" data-bs-target="#tab-css"    type="button" role="tab">
                                    <i class="bi bi-brush me-1 text-success"></i><?= __('admin.store_theme_api_tab_css') ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-js-btn"    data-bs-toggle="tab" data-bs-target="#tab-js"     type="button" role="tab">
                                    <i class="bi bi-lightning me-1 text-warning"></i><?= __('admin.store_theme_api_tab_js') ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-assets-btn" data-bs-toggle="tab" data-bs-target="#tab-assets" type="button" role="tab">
                                    <i class="bi bi-images me-1 text-danger"></i><?= __('admin.store_theme_api_tab_assets') ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-register-btn" data-bs-toggle="tab" data-bs-target="#tab-register" type="button" role="tab">
                                    <i class="bi bi-file-earmark-check me-1" style="color:#0891b2;"></i>Register &amp; Deploy
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="themeGuideTabContent">

                            <!-- ──────── TAB: PHP FILES ──────── -->
                            <div class="tab-pane fade show active" id="tab-php" role="tabpanel">
                                <p class="text-muted small mb-3">
                                    Create each file <strong>from scratch</strong> inside <code>application/views/store/<em>mytheme</em>/</code>.
                                    Each page receives exactly the variables listed in the PAGES section below — never more, never less.
                                    Click any page in the left sidebar to see its full variable contract.
                                </p>

                                <!-- folder tree -->
                                <div class="border rounded-3 p-3 mb-3" style="background:#f8f9fa;font-family:monospace;font-size:.78rem;line-height:2;">
                                    <div><i class="bi bi-folder-fill text-warning me-1"></i><strong>application/views/store/<em>mytheme</em>/</strong></div>
                                    <?php
                                    $tree_files = [
                                        'layout.php'             => ['required'=>true,  'desc'=>'Main HTML shell — wraps all pages. Must include $content_view.'],
                                        'home.php'               => ['required'=>true,  'desc'=>'Store homepage'],
                                        'category.php'           => ['required'=>true,  'desc'=>'Category / product listing'],
                                        'product.php'            => ['required'=>true,  'desc'=>'Product detail page'],
                                        'product_list.php'       => ['required'=>true,  'desc'=>'AJAX product card partial (returned by load_Product)'],
                                        'cart.php'               => ['required'=>true,  'desc'=>'Shopping cart'],
                                        'checkout.php'           => ['required'=>true,  'desc'=>'Checkout — multi-step'],
                                        'checkout_onepage.php'   => ['required'=>false, 'desc'=>'Checkout — one-page variant (optional, falls back to checkout.php)'],
                                        'checkout_shipping.php'  => ['required'=>true,  'desc'=>'Shipping address step (partial inside checkout)'],
                                        'checkout_confirm.php'   => ['required'=>true,  'desc'=>'Order confirmation step (partial inside checkout)'],
                                        'checkout_thankyou.php'  => ['required'=>true,  'desc'=>'Thank you / order complete — standalone HTML document'],
                                        'profile.php'            => ['required'=>true,  'desc'=>'Customer profile & settings'],
                                        'order_list.php'         => ['required'=>true,  'desc'=>'Customer order history'],
                                        'view_order.php'         => ['required'=>true,  'desc'=>'Single order detail'],
                                        'wishlist.php'           => ['required'=>true,  'desc'=>'Wishlist page'],
                                        'track_order.php'        => ['required'=>true,  'desc'=>'Guest order tracker'],
                                        'my_courses.php'         => ['required'=>true,  'desc'=>'LMS: enrolled courses list'],
                                        'login.php'              => ['required'=>true,  'desc'=>'Store login & register'],
                                        'about.php'              => ['required'=>true,  'desc'=>'About page'],
                                        'contact.php'            => ['required'=>true,  'desc'=>'Contact page'],
                                        'policy.php'             => ['required'=>true,  'desc'=>'Privacy / policy page'],
                                        'custom_page.php'        => ['required'=>true,  'desc'=>'Dynamic CMS page'],
                                        'mini_cart.php'          => ['required'=>true,  'desc'=>'Mini cart dropdown (AJAX partial)'],
                                        'cart_products_table.php'=> ['required'=>true,  'desc'=>'Cart items table (AJAX partial)'],
                                        'cookies_consent.php'    => ['required'=>false, 'desc'=>'Cookie consent banner (optional)'],
                                        'lms/template-1.php'     => ['required'=>true,  'desc'=>'LMS course player — standalone HTML document'],
                                    ];
                                    foreach ($tree_files as $file => $meta):
                                    ?>
                                    <div class="d-flex align-items-baseline gap-2 ps-3">
                                        <span style="color:#6c757d;">├─</span>
                                        <code class="<?= $meta['required'] ? 'text-primary' : 'text-muted' ?>"><?= $file ?></code>
                                        <?php if ($meta['required']): ?>
                                        <span class="badge bg-danger" style="font-size:.6rem;">required</span>
                                        <?php else: ?>
                                        <span class="badge bg-light text-muted border" style="font-size:.6rem;">optional</span>
                                        <?php endif; ?>
                                        <span class="text-muted" style="font-size:.73rem;"><?= $meta['desc'] ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- How to build each view -->
                                <h6 class="fw-semibold mt-3 mb-2">How to build each view file</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100" style="border-left:3px solid #0d6efd;">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-1-circle text-primary me-1"></i>Start with the docblock</div>
                                            <p class="small text-muted mb-1">Copy the <code>@contract</code> comment header from any <code>starter2026</code> view — it lists every variable available on that page.</p>
                                            <pre class="api-pre mb-0" style="font-size:.71rem;">&lt;?php
/**
 * @contract  Store API v1 — page: home
 * GLOBALS  $store_setting, $home_link, $base_url ...
 * PAGE VARIABLES
 *   $new_products  array  Latest 8 products
 *   ...
 */</pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100" style="border-left:3px solid #7c3aed;">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-2-circle" style="color:#7c3aed;" ></i> &nbsp;Use only documented variables</div>
                                            <p class="small text-muted mb-1">Each page section (PAGES) below tells you exactly what is available. Use them with safe defaults:</p>
                                            <pre class="api-pre mb-0" style="font-size:.71rem;">&lt;?php foreach ($new_products as $p): ?&gt;
  &lt;h3&gt;&lt;?= htmlspecialchars($p['product_name']) ?&gt;&lt;/h3&gt;
  &lt;span&gt;&lt;?= c_format($p['product_price']) ?&gt;&lt;/span&gt;
&lt;?php endforeach; ?&gt;</pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100" style="border-left:3px solid #059669;">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-3-circle text-success me-1"></i>layout.php is your HTML shell</div>
                                            <p class="small text-muted mb-1"><code>layout.php</code> wraps every page. It must echo the page content variable and load your CSS/JS:</p>
                                            <pre class="api-pre mb-0" style="font-size:.71rem;">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;link rel="stylesheet" href="&lt;?= base_url('assets/store/mytheme/css/theme.css') ?&gt;"&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;!-- your header nav --&gt;
  &lt;?php $this-&gt;load-&gt;view($content_view, $data); ?&gt;
  &lt;!-- your footer --&gt;
  &lt;script src="&lt;?= base_url('assets/store/mytheme/js/theme.js') ?&gt;"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100" style="border-left:3px solid #d97706;">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-4-circle" style="color:#d97706;"></i> &nbsp;Standalone pages (no layout)</div>
                                            <p class="small text-muted mb-1">Some pages render their own full HTML document and do <strong>not</strong> use <code>layout.php</code>. They must include Bootstrap and fonts themselves:</p>
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                <code class="badge bg-secondary fw-normal">checkout_thankyou.php</code>
                                                <code class="badge bg-secondary fw-normal">lms/template-1.php</code>
                                            </div>
                                            <p class="small text-muted mt-2 mb-0">These files contain a full <code>&lt;!DOCTYPE html&gt;</code> … <code>&lt;/html&gt;</code> structure.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ──────── TAB: CSS ──────── -->
                            <div class="tab-pane fade" id="tab-css" role="tabpanel">
                                <p class="text-muted small mb-3">
                                    Your CSS lives in <code>assets/store/<em>mytheme</em>/css/</code>.
                                    Bootstrap 5 and Font Awesome are already loaded globally — do not include them again.
                                </p>

                                <!-- Pre-loaded -->
                                <h6 class="fw-semibold mb-2"><?= __('admin.store_theme_api_css_pre_loaded') ?></h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light"><tr><th>File</th><th>Provides</th><th>How to use</th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td><code>bootstrap.min.css</code> <span class="badge bg-success ms-1">v5</span></td>
                                                <td>Grid, utilities, components, forms, modals</td>
                                                <td>All <code>btn-*</code>, <code>col-*</code>, <code>d-flex</code>, <code>text-*</code> etc. are ready to use</td>
                                            </tr>
                                            <tr>
                                                <td><code>all.min.css</code> (Font Awesome)</td>
                                                <td>Icon library — <code>fas fa-*</code>, <code>far fa-*</code>, <code>bi bi-*</code> (Bootstrap Icons)</td>
                                                <td><code>&lt;i class="fas fa-cart-shopping"&gt;&lt;/i&gt;</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Your file -->
                                <h6 class="fw-semibold mb-2"><?= __('admin.store_theme_api_css_your_file') ?></h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light"><tr><th>File</th><th>Purpose</th><th>Note</th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td><code>assets/store/<em>mytheme</em>/css/theme.css</code> <span class="badge bg-danger ms-1">required</span></td>
                                                <td>All custom styles for your theme</td>
                                                <td>Load in <code>layout.php</code> &lt;head&gt; — use <code>base_url('assets/store/mytheme/css/theme.css')</code></td>
                                            </tr>
                                            <tr>
                                                <td><code>assets/store/<em>mytheme</em>/css/checkout.css</code> <span class="badge bg-secondary ms-1">optional</span></td>
                                                <td>Extra styles for checkout pages only</td>
                                                <td>Include conditionally in <code>checkout.php</code> or <code>checkout_thankyou.php</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Recommended conventions -->
                                <h6 class="fw-semibold mb-2">Recommended CSS conventions</h6>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-tag text-primary me-1"></i>Namespace your classes</div>
                                            <p class="small text-muted mb-2">Prefix all custom classes with your theme name to avoid conflicts with Bootstrap or plugins.</p>
                                            <pre class="api-pre mb-0" style="font-size:.71rem;">/* Good — namespaced */
.mytheme-header { ... }
.mytheme-product-card { ... }

/* Bad — may clash */
.header { ... }
.card { ... }  /* conflicts with BS5 */</pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-palette text-success me-1"></i>Use CSS custom properties</div>
                                            <p class="small text-muted mb-2">Define your brand colours as CSS variables in <code>:root</code> for easy customisation:</p>
                                            <pre class="api-pre mb-0" style="font-size:.71rem;">:root {
  --mt-primary:   #0d6efd;
  --mt-secondary: #6c757d;
  --mt-accent:    #f59e0b;
  --mt-bg:        #f8f9fa;
  --mt-text:      #212529;
}</pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-phone text-warning me-1"></i>Mobile-first with Bootstrap breakpoints</div>
                                            <p class="small text-muted mb-1">Extend Bootstrap's existing breakpoints — don't define your own:</p>
                                            <pre class="api-pre mb-0" style="font-size:.71rem;">/* Bootstrap 5 breakpoints */
/* xs: &lt; 576px (default)  */
/* sm: ≥ 576px             */
/* md: ≥ 768px             */
/* lg: ≥ 992px             */
/* xl: ≥ 1200px            */
/* xxl: ≥ 1400px           */

@media (min-width: 768px) {
  .mytheme-product-card { ... }
}</pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-arrow-left-right text-danger me-1"></i>RTL support</div>
                                            <p class="small text-muted mb-1">The store supports RTL languages. Check <code>$store_setting['store_direction']</code> in your layout to add the <code>dir="rtl"</code> attribute and flip margins/paddings:</p>
                                            <pre class="api-pre mb-0" style="font-size:.71rem;">&lt;html dir="&lt;?= ($store_setting['store_direction'] ?? 'ltr') ?&gt;"&gt;

/* In CSS */
[dir="rtl"] .mytheme-nav { flex-direction: row-reverse; }
[dir="rtl"] .mytheme-price { text-align: left; }</pre>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cache busting -->
                                <div class="alert alert-info py-2 px-3 small mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Cache busting:</strong> always append <code>?v=&lt;?= av() ?&gt;</code> to your CSS and JS links so browsers pick up new versions after deployment.
                                    <br>Example: <code>&lt;link rel="stylesheet" href="&lt;?= base_url('assets/store/mytheme/css/theme.css') ?&gt;?v=&lt;?= av() ?&gt;"&gt;</code>
                                </div>
                            </div>

                            <!-- ──────── TAB: JS ──────── -->
                            <div class="tab-pane fade" id="tab-js" role="tabpanel">
                                <p class="text-muted small mb-3">
                                    Your JS lives in <code>assets/store/<em>mytheme</em>/js/</code>.
                                    jQuery and Bootstrap Bundle are already loaded — use them freely.
                                </p>

                                <!-- Pre-loaded -->
                                <h6 class="fw-semibold mb-2"><?= __('admin.store_theme_api_js_pre_loaded') ?></h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light"><tr><th>Library</th><th>Global</th><th>Provides</th></tr></thead>
                                        <tbody>
                                            <tr><td><code>jquery.min.js</code></td><td><code>$</code> / <code>jQuery</code></td><td>DOM manipulation, AJAX, events</td></tr>
                                            <tr><td><code>bootstrap.bundle.min.js</code> <span class="badge bg-success ms-1">v5 + Popper</span></td><td><code>bootstrap</code></td><td>Modals, dropdowns, tooltips, tabs, offcanvas</td></tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Your file -->
                                <h6 class="fw-semibold mb-2"><?= __('admin.store_theme_api_js_your_file') ?></h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light"><tr><th>File</th><th>Purpose</th><th>Load position</th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td><code>assets/store/<em>mytheme</em>/js/theme.js</code> <span class="badge bg-secondary ms-1">optional</span></td>
                                                <td>Theme-wide JS — sliders, animations, custom cart behaviour</td>
                                                <td>Before <code>&lt;/body&gt;</code> in <code>layout.php</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Built-in AJAX endpoints -->
                                <h6 class="fw-semibold mb-2">Built-in AJAX endpoints you can call</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light"><tr><th>URL</th><th>Method</th><th>What it does</th><th>Key params</th></tr></thead>
                                        <tbody>
                                            <tr><td><code>store/add_to_cart</code></td><td><span class="badge badge-post">POST</span></td><td>Add product to cart</td><td><code>product_id</code>, <code>quantity</code>, <code>variations[]</code></td></tr>
                                            <tr><td><code>store/update_cart</code></td><td><span class="badge badge-post">POST</span></td><td>Update cart item quantity</td><td><code>cart_item_id</code>, <code>qty</code></td></tr>
                                            <tr><td><code>store/remove_cart</code></td><td><span class="badge badge-post">POST</span></td><td>Remove item from cart</td><td><code>cart_item_id</code></td></tr>
                                            <tr><td><code>store/load_Product</code></td><td><span class="badge badge-post">POST</span></td><td>Load paginated product grid</td><td><code>category_id</code>, <code>page</code>, <code>sort</code>, <code>filter[]</code></td></tr>
                                            <tr><td><code>store/minicart</code></td><td><span class="badge badge-get">GET</span></td><td>Render mini cart HTML</td><td>—</td></tr>
                                            <tr><td><code>store/instant_search</code></td><td><span class="badge badge-get">GET</span></td><td>Live search results</td><td><code>q</code> (search term)</td></tr>
                                            <tr><td><code>store/quick_view/{id}</code></td><td><span class="badge badge-get">GET</span></td><td>Quick-view product modal HTML</td><td>product id in URL</td></tr>
                                            <tr><td><code>store/add_to_wishlist</code></td><td><span class="badge badge-post">POST</span></td><td>Toggle wishlist</td><td><code>product_id</code></td></tr>
                                            <tr><td><code>store/apply_coupon</code></td><td><span class="badge badge-post">POST</span></td><td>Apply coupon code at checkout</td><td><code>coupon_code</code></td></tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- JS patterns -->
                                <h6 class="fw-semibold mb-2">Recommended JS patterns</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-cart-plus text-primary me-1"></i>Add to cart via fetch</div>
                                            <pre class="api-pre mb-0" style="font-size:.7rem;">fetch('<?= base_url('store/add_to_cart') ?>', {
  method: 'POST',
  headers: {'Content-Type':'application/x-www-form-urlencoded',
            'X-Requested-With':'XMLHttpRequest'},
  body: new URLSearchParams({
    product_id: 42,
    quantity: 1
  })
})
.then(r =&gt; r.json())
.then(data =&gt; {
  if (data.status === 'success') updateCartCount(data.count);
});</pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-search text-success me-1"></i>Instant search</div>
                                            <pre class="api-pre mb-0" style="font-size:.7rem;">const input = document.getElementById('search-input');
input.addEventListener('input', function() {
  const q = this.value.trim();
  if (q.length &lt; 2) return;
  fetch('<?= base_url('store/instant_search') ?>?q=' + encodeURIComponent(q))
    .then(r =&gt; r.json())
    .then(data =&gt; renderSuggestions(data));
});</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ──────── TAB: ASSETS ──────── -->
                            <div class="tab-pane fade" id="tab-assets" role="tabpanel">
                                <p class="text-muted small mb-3">
                                    All theme assets live in <code>assets/store/<em>mytheme</em>/</code>.
                                    Reference them using <code>base_url('assets/store/mytheme/img/logo.png')</code>.
                                </p>

                                <!-- Required images -->
                                <h6 class="fw-semibold mb-2"><?= __('admin.store_theme_api_img_required') ?></h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light"><tr><th>File path</th><th>Used for</th><th>Recommended size</th><th>Note</th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td><code>img/logo.png</code></td>
                                                <td>Fallback logo shown when no store logo is uploaded in settings</td>
                                                <td>200 × 60 px, transparent PNG</td>
                                                <td>The real logo comes from <code>$store_setting['logo']</code> — this is only a fallback</td>
                                            </tr>
                                            <tr>
                                                <td><code>img/no-image.png</code></td>
                                                <td>Placeholder when a product has no image</td>
                                                <td>400 × 400 px</td>
                                                <td>Used in <code>onerror</code> handlers: <code>onerror="this.src='...no-image.png'"</code></td>
                                            </tr>
                                            <tr>
                                                <td><code>img/pr-img.png</code></td>
                                                <td>Alternate product placeholder (used by some partials)</td>
                                                <td>400 × 400 px</td>
                                                <td>Can be the same file as <code>no-image.png</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Optional images -->
                                <h6 class="fw-semibold mb-2"><?= __('admin.store_theme_api_img_optional') ?></h6>
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light"><tr><th>File path</th><th>Used for</th></tr></thead>
                                        <tbody>
                                            <tr><td><code>img/banner-*.jpg</code></td><td>Homepage hero / slider background images</td></tr>
                                            <tr><td><code>img/favicon.png</code></td><td>Fallback favicon (real favicon set from Store Settings)</td></tr>
                                            <tr><td><code>img/payment/*.png</code></td><td>Payment gateway logos (Stripe, PayPal, Visa, etc.)</td></tr>
                                            <tr><td><code>img/social/*.png</code></td><td>Social media icons for share buttons</td></tr>
                                            <tr><td><code>fonts/</code></td><td>Self-hosted web fonts (WOFF2 preferred)</td></tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Product images note -->
                                <div class="alert alert-secondary py-2 px-3 small mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Product & user images</strong> are <em>not</em> stored in your theme folder.
                                    They live in <code>assets/images/product/upload/</code> and <code>assets/images/users/</code>
                                    and are always provided to your views as full URLs via the API variables (e.g.&nbsp;<code>$product['product_featured_image']</code>).
                                </div>

                                <!-- How to reference in PHP -->
                                <h6 class="fw-semibold mb-2">Referencing assets in PHP views</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-check-circle text-success me-1"></i>Correct — use <code>base_url()</code></div>
                                            <pre class="api-pre mb-0" style="font-size:.7rem;">&lt;!-- CSS --&gt;
&lt;link rel="stylesheet"
  href="&lt;?= base_url('assets/store/mytheme/css/theme.css') ?&gt;?v=&lt;?= av() ?&gt;"&gt;

&lt;!-- Image --&gt;
&lt;img src="&lt;?= base_url('assets/store/mytheme/img/logo.png') ?&gt;"
     onerror="this.src='&lt;?= base_url('assets/store/mytheme/img/no-image.png') ?&gt;'"&gt;

&lt;!-- JS --&gt;
&lt;script src="&lt;?= base_url('assets/store/mytheme/js/theme.js') ?&gt;?v=&lt;?= av() ?&gt;"&gt;&lt;/script&gt;</pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3">
                                            <div class="fw-semibold small mb-2"><i class="bi bi-x-circle text-danger me-1"></i>Incorrect — hardcoded / relative paths</div>
                                            <pre class="api-pre mb-0" style="font-size:.7rem;">&lt;!-- Wrong: hardcoded domain --&gt;
&lt;link href="https://mysite.com/assets/..."&gt;

&lt;!-- Wrong: relative path --&gt;
&lt;link href="../../assets/..."&gt;

&lt;!-- Wrong: no cache-busting --&gt;
&lt;script src="&lt;?= base_url('assets/store/mytheme/js/theme.js') ?&gt;"&gt;&lt;/script&gt;</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ──────── TAB: REGISTER & DEPLOY ──────── -->
                            <div class="tab-pane fade" id="tab-register" role="tabpanel">

                                <!-- Hero note -->
                                <div class="rounded-3 p-3 mb-4 d-flex align-items-start gap-3" style="background:#f0f9ff;border:1px solid #bae6fd;">
                                    <i class="bi bi-shield-check text-primary mt-1" style="font-size:1.2rem;flex-shrink:0;"></i>
                                    <div style="font-size:.83rem;">
                                        <strong>Update-safe registration.</strong> Custom themes register themselves via a <code>theme.json</code> file placed inside their own folder.
                                        The platform scans for it automatically — you never need to edit any core file. When the platform receives an update, your theme folder and its <code>theme.json</code> are completely untouched.
                                    </div>
                                </div>

                                <!-- Step 1: Create theme.json -->
                                <h6 class="fw-semibold mb-2"><span class="badge bg-primary me-2">1</span>Create <code>theme.json</code> in your theme folder</h6>
                                <p class="text-muted small mb-2">File path: <code>application/views/store/<em>yourtheme</em>/theme.json</code></p>
                                <pre class="api-pre mb-4" style="font-size:.75rem;">{
    "_readme": "Registers this theme. Do not remove. Safe to edit name/description/tags.",

    "id":          "yourtheme",          <span style="color:#6b7280;">// must match the folder name exactly</span>
    "name":        "My Custom Theme",
    "description": "A short description shown in the theme selector.",
    "version":     "1.0.0",
    "author":      "Your Name",

    "mode":        "cart",               <span style="color:#6b7280;">// "cart" or "sales"</span>
    "api_version": "v1",
    "sort":        10,                   <span style="color:#6b7280;">// display order (lower = first)</span>

    "tags": ["Bootstrap 5", "Responsive", "RTL"],

    "preview": {
        "gradient": "linear-gradient(135deg,#1e3a5f 0%,#0d6efd 100%)",
        "emoji":    "🛍",
        "label":    "My Store Layout"
    }
}</pre>

                                <!-- Step 2: Upload -->
                                <h6 class="fw-semibold mb-2"><span class="badge bg-primary me-2">2</span>Upload your theme to the server</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light"><tr><th>What to upload</th><th>Where on server</th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td>Your view files + <code>theme.json</code></td>
                                                <td><code>application/views/store/yourtheme/</code></td>
                                            </tr>
                                            <tr>
                                                <td>Your CSS, JS, images</td>
                                                <td><code>assets/store/yourtheme/</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="alert alert-success py-2 px-3 small mb-4">
                                    <i class="bi bi-check-circle me-1"></i>
                                    That's it! The platform detects the <code>theme.json</code> and adds your theme to the Store Settings theme selector automatically — no restart needed.
                                </div>

                                <!-- Step 3: Activate -->
                                <h6 class="fw-semibold mb-2"><span class="badge bg-primary me-2">3</span>Activate in Store Settings</h6>
                                <ol class="small text-muted mb-4 ps-3">
                                    <li>Open <strong>Admin Panel → Store → Settings</strong></li>
                                    <li>Click the <strong>Appearance Settings</strong> sub-tab</li>
                                    <li>In the <strong>Store Theme</strong> section, select <strong>Cart Mode</strong></li>
                                    <li>Your theme will appear as a card — click it to select it</li>
                                    <li>Click <strong>Save Settings</strong> at the bottom of the page</li>
                                </ol>

                                <!-- JSON field reference -->
                                <h6 class="fw-semibold mb-2">theme.json field reference</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered field-table mb-0">
                                        <thead class="table-light">
                                            <tr><th>Field</th><th>Type</th><th>Required</th><th>Description</th></tr>
                                        </thead>
                                        <tbody style="font-size:.78rem;">
                                            <tr class="tapi-req-row"><td><code>id</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>✓</td><td>Must match folder name exactly. Stored in DB as the active theme value.</td></tr>
                                            <tr class="tapi-req-row"><td><code>name</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>✓</td><td>Display name shown in the theme selector card.</td></tr>
                                            <tr class="tapi-req-row"><td><code>mode</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>✓</td><td><code>"cart"</code> or <code>"sales"</code>. Determines which mode tab the theme appears under.</td></tr>
                                            <tr><td><code>description</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>—</td><td>Short description shown under the theme name.</td></tr>
                                            <tr><td><code>version</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>—</td><td>Semantic version, e.g. <code>"1.0.0"</code>.</td></tr>
                                            <tr><td><code>author</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>—</td><td>Author or company name. Non-<code>"System"</code> values show a "Custom" badge.</td></tr>
                                            <tr><td><code>api_version</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>—</td><td>API version this theme targets, e.g. <code>"v1"</code>. Shown in tags.</td></tr>
                                            <tr><td><code>sort</code></td><td><span class="tapi-type-badge t-int">int</span></td><td>—</td><td>Display order among themes of the same mode. Lower = earlier. Defaults to 99.</td></tr>
                                            <tr><td><code>tags</code></td><td><span class="tapi-type-badge t-array">array</span></td><td>—</td><td>Feature tags displayed as small badges on the theme card.</td></tr>
                                            <tr><td><code>preview.gradient</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>—</td><td>CSS gradient for the preview strip, e.g. <code>"linear-gradient(...)"</code>.</td></tr>
                                            <tr><td><code>preview.emoji</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>—</td><td>Emoji displayed in the preview strip center.</td></tr>
                                            <tr><td><code>preview.label</code></td><td><span class="tapi-type-badge t-string">string</span></td><td>—</td><td>Small subtitle shown below the emoji in the preview strip.</td></tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div><!-- /tab-register -->

                        </div><!-- /tab-content -->

                        <!-- Activate banner -->
                        <div class="border rounded-3 mt-4 p-3 d-flex align-items-center gap-3" style="background:#f0fdf4;border-color:#bbf7d0!important;">
                            <i class="bi bi-check2-circle text-success fs-4"></i>
                            <div class="small">
                                <strong>Ready to activate?</strong> — <?= __('admin.store_theme_api_activate_note') ?>
                            </div>
                            <a href="<?= base_url('admincontrol/store_setting') ?>" class="btn btn-sm btn-success ms-auto">
                                <i class="bi bi-gear me-1"></i>Store Settings
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== TRANSLATIONS ====== -->
            <div id="sec-translations" class="api-section mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-translate text-primary fs-5"></i>
                        <strong>Translations &amp; Language Keys</strong>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Every string displayed in a store theme must go through the translation helper so the store owner can switch languages. Never hard-code English strings directly.</p>

                        <!-- Quick rule -->
                        <div class="alert alert-primary d-flex gap-3 align-items-start mb-4">
                            <i class="bi bi-lightbulb-fill fs-4 flex-shrink-0 mt-1"></i>
                            <div>
                                <strong>The golden rule for theme strings</strong><br>
                                Always use <code><?= htmlspecialchars("<?= __('store.your_key') ?>") ?></code> — never echo a raw English word.<br>
                                If the key does not exist yet, add it to <code>application/language/default/store.php</code> <em>and</em> <code>application/language/default/default/store.php</code>.
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs" id="i18n-tabs" role="tablist">
                            <li class="nav-item"><button id="tab-i18n-btn" class="nav-link active" data-bs-toggle="tab" data-bs-target="#i18n-how"   type="button"><i class="bi bi-book me-1"></i>How It Works</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#i18n-add"   type="button"><i class="bi bi-plus-circle me-1"></i>Adding Keys</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#i18n-files" type="button"><i class="bi bi-files me-1"></i>Language Files</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#i18n-ref"   type="button"><i class="bi bi-list-ul me-1"></i>Key Reference</button></li>
                        </ul>
                        <div class="tab-content border border-top-0 rounded-bottom p-3 mb-0">

                            <!-- How It Works -->
                            <div class="tab-pane fade show active" id="i18n-how">
                                <h6 class="fw-bold mb-3">How the translation helper works</h6>
                                <p>The system provides a global <code>__()</code> helper function available in every theme view:</p>
                                <pre class="api-pre"><?= htmlspecialchars('<?= __(\'store.your_key\') ?>          // store.php language key
<?= __(\'user.your_key\') ?>           // user.php language key
<?= __(\'admin.your_key\') ?>          // admin.php language key (admin UI only)') ?></pre>
                                <ul class="mt-3">
                                    <li>The prefix (<code>store</code>, <code>user</code>, <code>admin</code>) maps to the language file name.</li>
                                    <li>If a key is <strong>missing</strong>, the raw key string is displayed (e.g. <code>store.browse_products</code>) — this is a bug and must be fixed.</li>
                                    <li>The active language is set by the store owner in settings and loaded automatically.</li>
                                    <li>Theme view files should only use <code>store.*</code> and <code>user.*</code> keys. Never use <code>admin.*</code> in frontend views.</li>
                                </ul>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <div class="border rounded p-3 bg-success bg-opacity-10">
                                            <strong class="text-success"><i class="bi bi-check-circle me-1"></i>Correct</strong>
                                            <pre class="api-pre mt-2 mb-0"><?= htmlspecialchars('<button><?= __(\'store.add_to_cart\') ?></button>
<p><?= __(\'store.no_wishlisted_products_available\') ?></p>
<a><?= __(\'store.browse_products\') ?></a>') ?></pre>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3 bg-danger bg-opacity-10">
                                            <strong class="text-danger"><i class="bi bi-x-circle me-1"></i>Incorrect</strong>
                                            <pre class="api-pre mt-2 mb-0"><?= htmlspecialchars('<button>Add to Cart</button>
<p>No products available!</p>
<a>Browse Products</a>') ?></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Adding Keys -->
                            <div class="tab-pane fade" id="i18n-add">
                                <h6 class="fw-bold mb-3">How to add a new translation key</h6>
                                <div class="alert alert-warning small">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Always add to <strong>both</strong> language files. The system has a primary and a fallback copy.
                                </div>
                                <ol class="mb-3">
                                    <li class="mb-2">Open <code>application/language/default/store.php</code></li>
                                    <li class="mb-2">Add your key at the bottom:<br>
                                        <pre class="api-pre mt-1"><?= htmlspecialchars('$lang[\'my_new_key\'] = \'My English Value\';') ?></pre>
                                    </li>
                                    <li class="mb-2">Open <code>application/language/default/default/store.php</code></li>
                                    <li class="mb-2">Add the <strong>identical</strong> line there too.</li>
                                    <li>Use the key in your theme: <code><?= htmlspecialchars("<?= __('store.my_new_key') ?>") ?></code></li>
                                </ol>
                                <div class="alert alert-info small mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Naming convention:</strong> Use <code>snake_case</code>. Group related keys together with a comment block.
                                    Always verify the key actually renders (not just the raw string) before shipping your theme.
                                </div>
                            </div>

                            <!-- Language Files -->
                            <div class="tab-pane fade" id="i18n-files">
                                <h6 class="fw-bold mb-3">Language file locations</h6>
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr><th>File</th><th>Used for</th><th>Prefix in code</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>language/default/store.php</code><br><small class="text-muted">+ <code>default/default/store.php</code></small></td>
                                            <td>All storefront / customer-facing strings</td>
                                            <td><code>store.*</code></td>
                                        </tr>
                                        <tr>
                                            <td><code>language/default/user.php</code><br><small class="text-muted">+ <code>default/default/user.php</code></small></td>
                                            <td>User account, forms, buttons shared across user flows</td>
                                            <td><code>user.*</code></td>
                                        </tr>
                                        <tr>
                                            <td><code>language/default/admin.php</code><br><small class="text-muted">+ <code>default/default/admin.php</code></small></td>
                                            <td>Admin panel strings only — <strong>do not use in themes</strong></td>
                                            <td><code>admin.*</code></td>
                                        </tr>
                                        <tr>
                                            <td><code>language/default/front.php</code></td>
                                            <td>Public-facing marketing / landing page strings</td>
                                            <td><code>front.*</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="small text-muted mb-0">Each language has its own copy of all files in a subfolder (e.g. <code>language/arabic/store.php</code>). The <code>default/default/</code> path is the built-in fallback used when no other language is selected.</p>
                            </div>

                            <!-- Key Reference -->
                            <div class="tab-pane fade" id="i18n-ref">
                                <h6 class="fw-bold mb-3">Most-used <code>store.*</code> keys for theme developers</h6>
                                <p class="text-muted small mb-3">This is a curated reference of the keys most commonly needed when building a new theme. For the full list, open <code>application/language/default/store.php</code>.</p>
                                <div class="row g-3">
                                    <?php
                                    $i18n_groups = [
                                        'Navigation &amp; UI' => [
                                            ['home','Home'], ['store','Store'], ['categories','Categories'],
                                            ['about','About'], ['contact','Contact'], ['login','Login'],
                                            ['logout','Logout'], ['register','Register'], ['search','Search'],
                                            ['back','Back'], ['go_home','Go Home'], ['page','Page'],
                                        ],
                                        'Cart &amp; Checkout' => [
                                            ['add_to_cart','Add to Cart'], ['shopping_cart','Shopping Cart'],
                                            ['checkout','Checkout'], ['subtotal','Subtotal'], ['total','Total'],
                                            ['qty','Qty'], ['items','Items'], ['coupon_code','Coupon Code'],
                                            ['apply','Apply'], ['remove','Remove'], ['update_cart','Update Cart'],
                                            ['proceed_to_checkout','Proceed to Checkout'], ['continue_shopping','Continue Shopping'],
                                        ],
                                        'Products' => [
                                            ['product','Product'], ['price','Price'], ['all_products','All Products'],
                                            ['browse_products','Browse Products'], ['no_products_desc','No products desc'],
                                            ['related_products','Related Products'], ['in_stock','In Stock'],
                                            ['out_of_stock','Out of Stock'], ['sale','Sale'], ['new','New'],
                                            ['add_to_wishlist','Add to Wishlist'], ['remove_from_wishlist','Remove from Wishlist'],
                                        ],
                                        'Orders &amp; Account' => [
                                            ['my_orders','My Orders'], ['order','Order'], ['view_orders','View Orders'],
                                            ['order_date','Order Date'], ['status','Status'], ['tracking','Tracking'],
                                            ['pending','Pending'], ['completed','Completed'],
                                            ['refunded','Refunded'], ['cancelled','Cancelled'],
                                            ['view_order_details','View Order Details'], ['instructions','Instructions'],
                                        ],
                                        'Reviews &amp; LMS' => [
                                            ['reviews','Reviews'], ['write_a_review','Write a Review'],
                                            ['submit','Submit'], ['rating','Rating'], ['my_courses','My Courses'],
                                            ['my_products','My Products'], ['no_purchased_products','No Purchased Products'],
                                            ['no_purchased_desc','No purchased desc'],
                                        ],
                                        'Messages &amp; Errors' => [
                                            ['thank_you_message','Thank you message'], ['go_home','Go Home'],
                                            ['shipping_calculation_error','Shipping error'], ['current_address','Current Address'],
                                            ['no_wishlisted_products_available','No wishlist products'],
                                            ['no_products_desc','No products desc'],
                                        ],
                                    ];
                                    foreach ($i18n_groups as $group => $keys): ?>
                                    <div class="col-md-6">
                                        <div class="border rounded p-2">
                                            <div class="fw-bold small border-bottom pb-1 mb-2 text-primary"><?= $group ?></div>
                                            <?php foreach ($keys as $kv): ?>
                                            <div class="d-flex align-items-center justify-content-between gap-2 py-1 border-bottom border-light">
                                                <code class="small text-nowrap">store.<?= $kv[0] ?></code>
                                                <span class="text-muted small text-end"><?= htmlspecialchars($kv[1]) ?></span>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div><!-- /tab-content -->
                    </div>
                </div>
            </div>

            <!-- ====== GLOBAL VARIABLES ====== -->
            <?php
            function tapi_type_badge($type) {
                $map = ['array'=>'t-array','string'=>'t-string','bool'=>'t-bool','boolean'=>'t-bool',
                        'int'=>'t-int','integer'=>'t-int','float'=>'t-float','object'=>'t-object','mixed'=>'t-mixed'];
                $cls = $map[strtolower($type)] ?? 't-mixed';
                return '<span class="type-badge '.$cls.'">'.htmlspecialchars($type).'</span>';
            }
            ?>
            <div id="sec-global" class="api-section mb-4">
                <div class="card page-card">
                    <div class="card-header d-flex align-items-center gap-2" style="border-left:4px solid #f59e0b;">
                        <i class="bi bi-globe text-warning fs-5"></i>
                        <div>
                            <strong><?= __('admin.store_theme_api_global_heading') ?></strong>
                            <div class="text-muted" style="font-size:.73rem;"><?= $schema['global']['description'] ?></div>
                        </div>
                        <div class="ms-auto d-flex align-items-center gap-2">
                            <span class="page-meta-pill"><i class="bi bi-layers me-1"></i><?= count($schema['global']['fields']) ?> variables</span>
                            <span class="badge bg-warning text-dark" style="font-size:.68rem;">Available on every page</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm field-table mb-0">
                                <thead><tr><th style="width:22%;">Variable</th><th style="width:8%;">Type</th><th style="width:6%;">Always</th><th>Description</th><th style="width:22%;">Example</th></tr></thead>
                                <tbody>
                                    <?php foreach ($schema['global']['fields'] as [$var, $type, $always, $desc, $ex]): ?>
                                    <tr class="<?= $always ? 'is-required' : '' ?>">
                                        <td><span class="var-name"><?= htmlspecialchars($var) ?></span></td>
                                        <td><?= tapi_type_badge($type) ?></td>
                                        <td><?= $always ? '<span class="req-yes">✓ Yes</span>' : '<span class="req-no">—</span>' ?></td>
                                        <td class="var-desc"><?= strip_tags($desc, '<code><strong><em>') ?></td>
                                        <td><span class="var-ex" title="<?= htmlspecialchars($ex) ?>"><?= htmlspecialchars($ex) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====== PAGES ====== -->
            <div class="section-divider mb-3 mt-2">
                <i class="bi bi-file-earmark me-2 text-primary"></i><?= __('admin.store_theme_api_pages_heading') ?>
                <span class="ms-2 badge bg-primary bg-opacity-15 text-primary fw-normal" style="font-size:.72rem;"><?= count($schema['pages']) ?> pages</span>
            </div>

            <?php foreach ($schema['pages'] as $page):
                $ep_has_api = !empty($page['api_endpoint']);
                if ($ep_has_api) {
                    $ep_parts  = explode(' ', $page['api_endpoint'], 2);
                    $ep_method = $ep_parts[0];
                    $ep_path   = $ep_parts[1] ?? '';
                    $ep_has_param = (strpos($ep_path, '{') !== false);
                } else {
                    $ep_method = null; $ep_path = null; $ep_has_param = false;
                }
                $field_count = count($page['fields']);
            ?>
            <div id="page-<?= $page['id'] ?>" class="api-section page-card page-card-pages card mb-3">
                <div class="card-header d-flex align-items-center flex-wrap gap-2"
                     data-bs-toggle="collapse" data-bs-target="#body-page-<?= $page['id'] ?>"
                     aria-expanded="false">
                    <!-- ID + file -->
                    <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                        <span class="badge bg-primary" style="font-size:.68rem;letter-spacing:.04em;"><?= strtoupper(str_replace('_',' ',$page['id'])) ?></span>
                        <code style="font-size:.75rem;color:#475569;"><?= htmlspecialchars($page['view']) ?></code>
                    </div>
                    <!-- auth -->
                    <?php if ($page['auth']): ?>
                    <span class="badge auth-badge-yes"><i class="bi bi-lock-fill me-1"></i>Auth</span>
                    <?php else: ?>
                    <span class="badge auth-badge-no"><i class="bi bi-unlock-fill me-1"></i>Public</span>
                    <?php endif; ?>
                    <!-- endpoint -->
                    <?php if ($ep_has_api): ?>
                    <span class="ep-pill">
                        <span class="badge badge-get"><?= htmlspecialchars($ep_method) ?></span>
                        <span style="font-size:.73rem;color:#475569;"><?= htmlspecialchars($ep_path) ?></span>
                    </span>
                    <?php if ($ep_has_param): ?>
                    <span class="badge bg-warning text-dark" style="font-size:.65rem;" title="Replace {param} with a real value"><i class="bi bi-braces me-1"></i>param</span>
                    <?php else: ?>
                    <a href="<?= base_url($ep_path) ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Open live JSON" onclick="event.stopPropagation()"><i class="bi bi-box-arrow-up-right" style="font-size:.75rem;"></i></a>
                    <?php endif; ?>
                    <?php else: ?>
                    <span class="badge badge-ssr"><i class="bi bi-eye me-1"></i>SSR only</span>
                    <?php endif; ?>
                    <!-- variable count + chevron -->
                    <span class="page-meta-pill ms-1"><?= $field_count ?> vars</span>
                    <i class="bi bi-chevron-down collapse-icon text-muted ms-1" style="font-size:.8rem;"></i>
                </div>
                <div id="body-page-<?= $page['id'] ?>" class="collapse">
                    <div class="card-body pb-2">
                        <p class="text-muted small mb-3"><?= htmlspecialchars($page['description']) ?></p>
                        <div class="table-responsive">
                            <table class="table table-sm field-table mb-0">
                                <thead><tr><th style="width:22%;">Variable</th><th style="width:8%;">Type</th><th style="width:6%;">Req.</th><th>Description</th><th style="width:22%;">Example</th></tr></thead>
                                <tbody>
                                    <?php foreach ($page['fields'] as [$var, $type, $always, $desc, $ex]): ?>
                                    <tr class="<?= $always ? 'is-required' : '' ?>">
                                        <td><span class="var-name"><?= htmlspecialchars($var) ?></span></td>
                                        <td><?= tapi_type_badge($type) ?></td>
                                        <td><?= $always ? '<span class="req-yes">✓</span>' : '<span class="req-no">—</span>' ?></td>
                                        <td class="var-desc"><?= strip_tags($desc, '<code><strong><em><a>') ?></td>
                                        <td><span class="var-ex" title="<?= htmlspecialchars($ex) ?>"><?= htmlspecialchars($ex) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($ep_has_api): ?>
                        <?php
                            $try_id    = 'try-page-' . $page['id'];
                            $try_url   = base_url($ep_path);
                            $try_param = $ep_has_param ? preg_replace('/.*\{(\w+)\}.*/', '$1', $ep_path) : '';
                        ?>
                        <div class="mt-3 pt-2 border-top d-flex align-items-center gap-2 flex-wrap">
                            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1"
                                    onclick="apiTryIt('<?= $try_id ?>','<?= addslashes($try_url) ?>','<?= htmlspecialchars($try_param) ?>')">
                                <i class="bi bi-play-fill"></i> Try it live
                            </button>
                            <span class="text-muted" style="font-size:.75rem;">Live JSON response from the API endpoint</span>
                        </div>
                        <div id="<?= $try_id ?>" class="try-panel d-none mt-2">
                            <div class="try-toolbar">
                                <i class="bi bi-terminal me-1"></i>
                                <span><?= htmlspecialchars($ep_method) ?> <?= htmlspecialchars($ep_path) ?></span>
                                <?php if ($ep_has_param): ?>
                                <div class="input-group input-group-sm ms-auto" style="max-width:300px;">
                                    <span class="input-group-text bg-slate-700 border-slate-600 text-slate-300" style="background:#334155;border-color:#475569;color:#94a3b8;font-size:.73rem;"><?= htmlspecialchars($try_param) ?></span>
                                    <input type="text" class="form-control try-param-input" style="background:#1e293b;border-color:#475569;color:#e2e8f0;font-size:.73rem;" placeholder="Enter value…"
                                           onkeydown="if(event.key==='Enter') apiTryIt('<?= $try_id ?>','<?= addslashes($try_url) ?>','<?= htmlspecialchars($try_param) ?>')">
                                    <button class="btn btn-primary btn-sm" onclick="apiTryIt('<?= $try_id ?>','<?= addslashes($try_url) ?>','<?= htmlspecialchars($try_param) ?>')">Go</button>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="try-result"></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- ====== FRAGMENTS ====== -->
            <div class="section-divider mb-3 mt-4">
                <i class="bi bi-puzzle me-2 text-success"></i><?= __('admin.store_theme_api_fragments_heading') ?>
                <span class="ms-2 badge bg-success bg-opacity-15 text-success fw-normal" style="font-size:.72rem;"><?= count($schema['fragments']) ?> fragments</span>
            </div>
            <p class="text-muted small mb-3">Fragments are partial views loaded via AJAX or included inside other views. They have their own request/response contracts.</p>

            <?php foreach ($schema['fragments'] as $frag):
                $fp_parts  = explode(' ', $frag['api_path'], 2);
                $fp_method = $fp_parts[0];
                $fp_path   = $fp_parts[1] ?? $frag['api_path'];
                $fp_has_param = (strpos($fp_path, '{') !== false);
                $fp_linkable  = ($fp_method === 'GET' && !$fp_has_param);
            ?>
            <div id="frag-<?= $frag['id'] ?>" class="api-section page-card page-card-frags card mb-3">
                <div class="card-header d-flex align-items-center flex-wrap gap-2"
                     data-bs-toggle="collapse" data-bs-target="#body-frag-<?= $frag['id'] ?>"
                     aria-expanded="false">
                    <span class="badge bg-success" style="font-size:.68rem;letter-spacing:.04em;"><?= strtoupper(str_replace('_',' ',$frag['id'])) ?></span>
                    <span class="ep-pill">
                        <span class="badge <?= $fp_method === 'POST' ? 'badge-post' : 'badge-get' ?>"><?= htmlspecialchars($fp_method) ?></span>
                        <span style="font-size:.73rem;color:#475569;"><?= htmlspecialchars($fp_path) ?></span>
                    </span>
                    <?php if ($fp_linkable): ?>
                    <a href="<?= base_url($fp_path) ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Open live JSON" onclick="event.stopPropagation()"><i class="bi bi-box-arrow-up-right" style="font-size:.75rem;"></i></a>
                    <?php elseif ($fp_has_param): ?>
                    <span class="badge bg-warning text-dark" style="font-size:.65rem;"><i class="bi bi-braces me-1"></i>param</span>
                    <?php else: ?>
                    <span class="badge bg-secondary" style="font-size:.65rem;"><i class="bi bi-terminal me-1"></i>POST</span>
                    <?php endif; ?>
                    <span class="page-meta-pill ms-auto"><?= count($frag['params']) ?> params · <?= count($frag['response']) ?> response fields</span>
                    <i class="bi bi-chevron-down collapse-icon text-muted ms-1" style="font-size:.8rem;"></i>
                </div>
                <div id="body-frag-<?= $frag['id'] ?>" class="collapse">
                    <div class="card-body pb-2">
                        <p class="text-muted small mb-3"><?= htmlspecialchars($frag['description']) ?></p>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <div class="fw-semibold small mb-2 text-muted"><i class="bi bi-arrow-right-circle me-1"></i>Request params</div>
                                <div class="table-responsive">
                                    <table class="table table-sm field-table mb-0">
                                        <thead><tr><th>Param</th><th>Type</th><th>Req.</th><th>Description</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($frag['params'] as [$pname, $ptype, $preq, $pdesc]): ?>
                                            <tr class="<?= $preq ? 'is-required' : '' ?>">
                                                <td><span class="var-name"><?= htmlspecialchars($pname) ?></span></td>
                                                <td><?= tapi_type_badge($ptype) ?></td>
                                                <td><?= $preq ? '<span class="req-yes">✓</span>' : '<span class="req-no">—</span>' ?></td>
                                                <td class="var-desc"><?= strip_tags($pdesc, '<code><strong><em>') ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="fw-semibold small mb-2 text-muted"><i class="bi bi-arrow-left-circle me-1"></i>Response fields</div>
                                <div class="table-responsive">
                                    <table class="table table-sm field-table mb-0">
                                        <thead><tr><th>Field</th><th>Type</th><th>Description</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($frag['response'] as $r): ?>
                                            <tr>
                                                <td><span class="var-name" style="color:#065f46;"><?= htmlspecialchars($r[0]) ?></span></td>
                                                <td><?= tapi_type_badge($r[1]) ?></td>
                                                <td class="var-desc"><?= strip_tags($r[2], '<code><strong><em>') ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (!empty($frag['product_row_fields'])): ?>
                                <div class="fw-semibold small mb-2 mt-3 text-muted"><i class="bi bi-list-ul me-1"></i>Product row fields (each item in array)</div>
                                <div class="table-responsive">
                                    <table class="table table-sm field-table mb-0">
                                        <thead><tr><th>Field</th><th>Type</th><th>Description</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($frag['product_row_fields'] as [$fn, $ft, $fd]): ?>
                                            <tr>
                                                <td><span class="var-name" style="color:#065f46;"><?= htmlspecialchars($fn) ?></span></td>
                                                <td><?= tapi_type_badge($ft) ?></td>
                                                <td class="var-desc"><?= strip_tags($fd, '<code><strong><em>') ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($fp_method === 'GET'): ?>
                        <?php
                            $ftry_id    = 'try-frag-' . $frag['id'];
                            $ftry_url   = base_url($fp_path);
                            $ftry_param = $fp_has_param ? preg_replace('/.*\{(\w+)\}.*/', '$1', $fp_path) : '';
                        ?>
                        <div class="mt-3 pt-2 border-top d-flex align-items-center gap-2 flex-wrap">
                            <button class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                    onclick="apiTryIt('<?= $ftry_id ?>','<?= addslashes($ftry_url) ?>','<?= htmlspecialchars($ftry_param) ?>')">
                                <i class="bi bi-play-fill"></i> Try it live
                            </button>
                            <span class="text-muted" style="font-size:.75rem;"><?= $fp_has_param ? 'Enter a real '.htmlspecialchars($ftry_param).' to fetch data' : 'Live JSON response from this endpoint' ?></span>
                        </div>
                        <div id="<?= $ftry_id ?>" class="try-panel d-none mt-2">
                            <div class="try-toolbar">
                                <i class="bi bi-terminal me-1"></i>
                                <span><?= htmlspecialchars($fp_method) ?> <?= htmlspecialchars($fp_path) ?></span>
                                <?php if ($fp_has_param): ?>
                                <div class="input-group input-group-sm ms-auto" style="max-width:300px;">
                                    <span class="input-group-text" style="background:#334155;border-color:#475569;color:#94a3b8;font-size:.73rem;"><?= htmlspecialchars($ftry_param) ?></span>
                                    <input type="text" class="form-control try-param-input" style="background:#1e293b;border-color:#475569;color:#e2e8f0;font-size:.73rem;" placeholder="Enter value…"
                                           onkeydown="if(event.key==='Enter') apiTryIt('<?= $ftry_id ?>','<?= addslashes($ftry_url) ?>','<?= htmlspecialchars($ftry_param) ?>')">
                                    <button class="btn btn-success btn-sm" onclick="apiTryIt('<?= $ftry_id ?>','<?= addslashes($ftry_url) ?>','<?= htmlspecialchars($ftry_param) ?>')">Go</button>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="try-result"></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- ====== ALL API ENDPOINTS ====== -->
            <div id="sec-endpoints" class="api-section mb-4 mt-4">
                <div class="section-divider mb-3">
                    <i class="bi bi-terminal me-2"></i><?= __('admin.store_theme_api_endpoints_heading') ?>
                </div>
                <div class="card page-card">
                    <div class="card-body">
                        <p class="text-muted small mb-3"><?= __('admin.store_theme_api_endpoint_note') ?></p>
                        <div class="row g-2">
                            <?php foreach ($manifest['api_routes'] as $route): ?>
                            <?php
                                $parts   = explode(' ', $route, 2);
                                $method  = $parts[0];
                                $path    = $parts[1] ?? '';
                                $badgeCls = ($method === 'POST') ? 'badge-post' : 'badge-get';
                                $route_has_param = (strpos($path, '{') !== false);
                                $route_linkable  = ($method === 'GET' && !$route_has_param);
                            ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="ep-pill d-flex w-100" style="border-radius:.35rem;">
                                    <span class="badge <?= $badgeCls ?> flex-shrink-0"><?= $method ?></span>
                                    <code style="font-size:.72rem;color:#475569;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= htmlspecialchars($path) ?>"><?= htmlspecialchars($path) ?></code>
                                    <?php if ($route_linkable): ?>
                                    <a href="<?= base_url($path) ?>" target="_blank" class="ms-auto text-muted" title="Open live JSON" style="font-size:.8rem;"><i class="bi bi-box-arrow-up-right"></i></a>
                                    <?php elseif ($route_has_param): ?>
                                    <span class="ms-auto text-warning" title="Requires {param}" style="font-size:.8rem;"><i class="bi bi-braces"></i></span>
                                    <?php else: ?>
                                    <span class="ms-auto text-muted" title="POST only" style="font-size:.8rem;"><i class="bi bi-terminal"></i></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /col main -->
    </div><!-- /row -->
</div>

<script>
/* ── JSON syntax highlight (dark theme) ─────────────────── */
function apiSyntaxHL(raw) {
    var s = raw.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    return s.replace(/("(?:\\.|[^"\\])*"(?:\s*:)?|true|false|null|-?\d+(?:\.\d+)?(?:[eE][+\-]?\d+)?)/g, function(m){
        if (m[0] === '"') return '<span class="' + (m[m.length-1]===':' ? 'json-key' : 'json-str') + '">' + m + '</span>';
        if (m==='true'||m==='false') return '<span class="json-bool">'+m+'</span>';
        if (m==='null') return '<span class="json-null">'+m+'</span>';
        return '<span class="json-num">'+m+'</span>';
    });
}

/* ── Try-it-live ─────────────────────────────────────────── */
function apiTryIt(panelId, urlTpl, paramName) {
    var panel   = document.getElementById(panelId);
    var allBtns = document.querySelectorAll('[onclick*="apiTryIt(\''+panelId+'\'"]');
    var mainBtn = allBtns[0] || document.querySelector('[onclick*="'+panelId+'"]');
    if (!panel) return;

    if (!panel.classList.contains('d-none')) {
        panel.classList.add('d-none');
        if (mainBtn) { mainBtn.innerHTML = '<i class="bi bi-play-fill"></i> Try it live'; mainBtn.classList.remove('btn-outline-danger'); }
        var card = panel.closest('.page-card');
        if (card) card.classList.remove('is-open');
        return;
    }

    var url = urlTpl;
    if (paramName) {
        var inp = panel.querySelector('.try-param-input');
        var val = inp ? inp.value.trim() : '';
        if (!val) {
            panel.classList.remove('d-none');
            panel.querySelector('.try-result').innerHTML = '<div style="color:#94a3b8;padding:.5rem .75rem;font-size:.76rem;"><i class="bi bi-arrow-up me-1"></i>Enter a value above and press Go</div>';
            if (mainBtn) { mainBtn.innerHTML = '<i class="bi bi-x-circle"></i> Close'; }
            if (inp) inp.focus();
            return;
        }
        url = url.replace('{'+paramName+'}', encodeURIComponent(val));
    }

    panel.classList.remove('d-none');
    if (mainBtn) { mainBtn.innerHTML = '<i class="bi bi-x-circle"></i> Close'; }

    var result = panel.querySelector('.try-result');
    result.innerHTML = '<div style="color:#94a3b8;padding:.75rem;font-size:.76rem;"><i class="bi bi-hourglass-split me-1"></i>Fetching…</div>';

    fetch(url, { credentials: 'same-origin' })
        .then(function(r){ var st=r.status; return r.text().then(function(t){ return {st:st,t:t}; }); })
        .then(function(d){
            var colored;
            try { colored = apiSyntaxHL(JSON.stringify(JSON.parse(d.t), null, 2)); }
            catch(e){ colored = d.t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
            var bc = d.st < 300 ? '#10b981' : '#ef4444';
            result.innerHTML = '<div style="display:flex;align-items:center;gap:.5rem;padding:.4rem .75rem;border-bottom:1px solid #1e293b;">'
                + '<span style="background:'+bc+';color:#fff;font-size:.65rem;font-weight:700;padding:.1rem .4rem;border-radius:.2rem;">HTTP '+d.st+'</span>'
                + '<code style="font-size:.72rem;color:#64748b;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'+url+'</code>'
                + '</div><pre class="api-pre mb-0" style="border-radius:0;max-height:400px;">'+colored+'</pre>';
        })
        .catch(function(e){
            result.innerHTML = '<div style="color:#f87171;padding:.75rem;font-size:.76rem;"><i class="bi bi-exclamation-triangle me-1"></i>'+e.message+'</div>';
        });
}

/* ── Collapsible page cards ──────────────────────────────── */
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.page-card .card-header[data-bs-toggle="collapse"]').forEach(function(hdr){
        hdr.addEventListener('shown.bs.collapse', function(){
            hdr.closest('.page-card').classList.add('is-open');
        }, true);
        hdr.addEventListener('hidden.bs.collapse', function(){
            hdr.closest('.page-card').classList.remove('is-open');
        }, true);
        /* listen on the collapse target element */
        var targetId = hdr.getAttribute('data-bs-target');
        var target   = targetId ? document.querySelector(targetId) : null;
        if (target) {
            target.addEventListener('shown.bs.collapse',  function(){ hdr.closest('.page-card').classList.add('is-open'); });
            target.addEventListener('hidden.bs.collapse', function(){ hdr.closest('.page-card').classList.remove('is-open'); });
        }
    });

    /* ── Sidebar search ─────────────────────────────────── */
    var searchInput = document.getElementById('sidebar-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(){
            var q = this.value.toLowerCase().trim();
            document.querySelectorAll('#sidebar-nav .nav-link').forEach(function(link){
                if (!q) { link.classList.remove('d-none-filter'); return; }
                var text = link.textContent.toLowerCase();
                var href = (link.getAttribute('href') || '').toLowerCase();
                link.classList.toggle('d-none-filter', text.indexOf(q) === -1 && href.indexOf(q) === -1);
            });
            /* hide section heads that have no visible items */
            document.querySelectorAll('#sidebar-nav .nav-section-head').forEach(function(head){
                var next = head.nextElementSibling;
                var hasVisible = false;
                while (next && !next.classList.contains('nav-section-head')) {
                    if (!next.classList.contains('d-none-filter') && next.classList.contains('nav-link')) hasVisible = true;
                    next = next.nextElementSibling;
                }
                head.style.display = (!q || hasVisible) ? '' : 'none';
            });
        });
    }

    /* ── Sidebar scroll / spy ────────────────────────────── */
    var OFFSET = 90;
    var links  = document.querySelectorAll('#sidebar-nav .nav-link[href^="#"]');

    links.forEach(function(link){
        link.addEventListener('click', function(e){
            var id = link.getAttribute('href').split('?')[0];
            if (id.length < 2) return;
            var el = document.querySelector(id);
            if (!el) return;
            e.preventDefault();
            /* if inside a collapsed card, open it first */
            var card = el.querySelector('.collapse');
            if (card && !card.classList.contains('show')) {
                var bsCol = bootstrap.Collapse.getOrCreateInstance(card);
                bsCol.show();
            }
            setTimeout(function(){
                var top = el.getBoundingClientRect().top + window.scrollY - OFFSET;
                window.scrollTo({ top: top, behavior: 'smooth' });
                history.replaceState(null, '', id);
            }, 50);
        });
    });

    function spy(){
        var pos = window.scrollY + OFFSET + 10;
        var cur = null;
        links.forEach(function(link){
            var id = (link.getAttribute('href')||'').split('?')[0];
            var el = id && id.length > 1 ? document.querySelector(id) : null;
            if (el && el.getBoundingClientRect().top + window.scrollY <= pos) cur = link;
        });
        links.forEach(function(l){ l.classList.remove('active'); });
        if (cur) cur.classList.add('active');
    }
    window.addEventListener('scroll', spy, { passive:true });
    spy();
});
</script>
