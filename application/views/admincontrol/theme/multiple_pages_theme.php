<style>
    /* ====== Multiple Pages Theme Admin — GAME CHANGER v2 ====== */
    :root {
        --mpa-primary: #4361ee;
        --mpa-primary-dark: #3a56d4;
        --mpa-primary-light: #6b82f7;
        --mpa-primary-rgb: 67, 97, 238;
        --mpa-accent: #f72585;
        --mpa-accent-rgb: 247, 37, 133;
        --mpa-secondary: #7c3aed;
        --mpa-success: #10b981;
        --mpa-warning: #f59e0b;
        --mpa-danger: #ef4444;
        --mpa-bg: #f8fafc;
        --mpa-card: #ffffff;
        --mpa-border: #e2e8f0;
        --mpa-text: #1e293b;
        --mpa-text-muted: #64748b;
        --mpa-radius: 1rem;
        --mpa-radius-sm: 0.625rem;
        --mpa-shadow: 0 4px 24px rgba(0,0,0,0.06);
        --mpa-shadow-lg: 0 12px 40px rgba(0,0,0,0.1);
        --mpa-transition: 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @keyframes mpaGradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes mpaFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ====== GRADIENT HEADER BAR ====== */
    .mpa-header-bar {
        background: linear-gradient(135deg, var(--mpa-primary), var(--mpa-secondary), var(--mpa-accent));
        background-size: 300% 300%;
        animation: mpaGradientShift 8s ease infinite;
        padding: 1.5rem 2rem;
        border-radius: var(--mpa-radius) var(--mpa-radius) 0 0;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .mpa-header-info { position: relative; z-index: 1; }
    .mpa-demo-actions {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.45rem;
    }
    .mpa-demo-actions .demo-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .mpa-demo-actions .demo-btns { display: flex; gap: 0.5rem; }
    .btn-demo-import {
        background: rgba(255,255,255,0.18);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,0.45);
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.38rem 0.9rem;
        border-radius: 6px;
        backdrop-filter: blur(4px);
        transition: background 0.2s, border-color 0.2s;
        white-space: nowrap;
    }
    .btn-demo-import:hover {
        background: rgba(255,255,255,0.32);
        border-color: rgba(255,255,255,0.75);
        color: #fff;
    }
    .btn-demo-clear {
        background: rgba(255,80,80,0.22);
        color: #ffe0e0;
        border: 1.5px solid rgba(255,120,120,0.5);
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.38rem 0.9rem;
        border-radius: 6px;
        backdrop-filter: blur(4px);
        transition: background 0.2s, border-color 0.2s;
        white-space: nowrap;
    }
    .btn-demo-clear:hover {
        background: rgba(255,80,80,0.42);
        border-color: rgba(255,120,120,0.85);
        color: #fff;
    }
    .mpa-header-bar::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
        pointer-events: none;
    }
    .mpa-header-bar::after {
        content: '';
        position: absolute;
        bottom: -30%; left: 15%;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
        pointer-events: none;
    }
    .mpa-header-bar h3 {
        color: #fff;
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .mpa-header-bar h3 i {
        font-size: 1.4rem;
        opacity: 0.85;
    }
    .mpa-header-bar p {
        color: rgba(255,255,255,0.7);
        font-size: 0.88rem;
        margin: 0.35rem 0 0;
    }

    /* ====== SCROLLABLE TAB NAVIGATION ====== */
    .mpa-tabs-wrapper {
        position: relative;
        background: linear-gradient(180deg, #f1f5f9 0%, #e8ecf4 100%);
        border-radius: 0 0 var(--mpa-radius) var(--mpa-radius);
        margin-bottom: 1.75rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
    .mpa-tabs-wrapper::before,
    .mpa-tabs-wrapper::after {
        content: '';
        position: absolute;
        top: 0; bottom: 0;
        width: 40px;
        z-index: 2;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .mpa-tabs-wrapper::before {
        left: 0;
        background: linear-gradient(to right, #f1f5f9 30%, transparent);
        border-radius: 0 0 0 var(--mpa-radius);
    }
    .mpa-tabs-wrapper::after {
        right: 0;
        background: linear-gradient(to left, #e8ecf4 30%, transparent);
        border-radius: 0 0 var(--mpa-radius) 0;
    }
    .mpa-tabs-wrapper.has-scroll-left::before { opacity: 1; }
    .mpa-tabs-wrapper.has-scroll-right::after { opacity: 1; }

    .mpa-tabs-arrow {
        position: absolute;
        top: 50%; transform: translateY(-50%);
        z-index: 3;
        width: 30px; height: 30px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
        background: rgba(255,255,255,0.95);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: none;
        align-items: center; justify-content: center;
        cursor: pointer;
        color: #475569;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        padding: 0;
    }
    .mpa-tabs-arrow:hover {
        background: var(--mpa-primary, #4361ee);
        color: #fff;
        border-color: var(--mpa-primary, #4361ee);
        box-shadow: 0 3px 12px rgba(67,97,238,0.3);
    }
    .mpa-tabs-arrow.arr-left { left: 6px; }
    .mpa-tabs-arrow.arr-right { right: 6px; }
    .mpa-tabs-wrapper.has-scroll-left .mpa-tabs-arrow.arr-left { display: inline-flex; }
    .mpa-tabs-wrapper.has-scroll-right .mpa-tabs-arrow.arr-right { display: inline-flex; }

    .mp-admin-tabs {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.3rem;
        padding: 0.75rem 1rem;
        margin: 0;
        list-style: none;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        scrollbar-width: none;
    }
    .mp-admin-tabs::-webkit-scrollbar { display: none; }

    .mp-admin-tabs .nav-item { flex: 0 0 auto; }

    .mp-admin-tabs .nav-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1.15rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--mpa-text-muted);
        background: rgba(255,255,255,0.5);
        border: 1px solid transparent;
        border-radius: 50px;
        transition: all var(--mpa-transition);
        white-space: nowrap;
        position: relative;
    }
    .mp-admin-tabs .nav-link:hover {
        color: var(--mpa-primary);
        background: rgba(255,255,255,0.9);
        border-color: rgba(var(--mpa-primary-rgb), 0.15);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .mp-admin-tabs .nav-link.active {
        color: #fff !important;
        background: linear-gradient(135deg, var(--mpa-primary), var(--mpa-primary-dark)) !important;
        border-color: transparent !important;
        box-shadow: 0 4px 16px rgba(var(--mpa-primary-rgb), 0.35), 0 2px 6px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }
    .mp-admin-tabs .nav-link i { font-size: 0.95rem; }

    /* ====== TAB CONTENT ====== */
    .tab-content > .tab-pane {
        padding: 1.5rem 0 !important;
        animation: mpaFadeIn 0.3s ease-out;
    }

    /* ====== ADMIN CARDS — Premium ====== */
    .mp-admin-card,
    .card.m-b-30,
    .card {
        background: var(--mpa-card);
        border: 1px solid var(--mpa-border);
        border-radius: var(--mpa-radius);
        box-shadow: var(--mpa-shadow);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: box-shadow var(--mpa-transition);
    }
    .mp-admin-card:hover,
    .card.m-b-30:hover,
    .card:hover {
        box-shadow: var(--mpa-shadow-lg);
    }

    .mp-admin-card .card-header,
    .card.m-b-30 .card-header,
    .card .card-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid var(--mpa-border);
        padding: 1rem 1.25rem;
        position: relative;
    }
    .mp-admin-card .card-header::after,
    .card.m-b-30 .card-header::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--mpa-primary), var(--mpa-accent), transparent);
        opacity: 0.3;
    }

    .mp-admin-card .card-header h4,
    .mp-admin-card .card-header .card-title,
    .card.m-b-30 .card-header h4,
    .card .card-header .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--mpa-text);
        margin: 0;
    }

    .mp-admin-card .card-body,
    .card.m-b-30 .card-body { padding: 1.25rem; }

    /* ====== TABLES — Zebra + Hover ====== */
    #tab_settings table.table > tbody > tr > td,
    #tab_settings table.table > tfoot > tr > td,
    #tab_settings table.table > thead > tr > td {
        padding: 0.6rem 0.75rem !important;
        vertical-align: middle !important;
    }

    .table {
        border-radius: var(--mpa-radius);
        overflow: hidden;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table thead th {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--mpa-text-muted);
        border-bottom: 2px solid var(--mpa-border);
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
        padding: 0.85rem 0.75rem !important;
    }
    .table tbody td {
        font-size: 0.9rem;
        color: var(--mpa-text);
        padding: 0.8rem 0.75rem !important;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        transition: background 0.2s ease;
    }
    .table tbody tr:nth-child(even) td { background: rgba(var(--mpa-primary-rgb), 0.015); }
    .table tbody tr:hover td { background: rgba(var(--mpa-primary-rgb), 0.05); }

    /* Action Buttons — Gradient micro-buttons */
    .table .btn-sm {
        padding: 0.35rem 0.75rem;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.25s ease;
    }
    .table .btn-sm:hover { transform: translateY(-1px); }

    .table .btn-primary.btn-sm {
        background: linear-gradient(135deg, var(--mpa-primary), var(--mpa-primary-dark)) !important;
        border: none !important;
        box-shadow: 0 2px 8px rgba(var(--mpa-primary-rgb), 0.2);
    }
    .table .btn-danger.btn-sm {
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
    }

    /* Loaders */
    #tab_settings .home_sections_positions_loading,
    #tab_settings .homepages_top_menu_positions_loading { margin: 0 !important; padding: 0 !important; }
    .homepage_top_menu_pages .homepages_top_menu_positions_loading {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }
    .thead-tr-loader {
        display: block; position: relative; height: 0.5rem; width: 1.5rem;
        color: var(--mpa-primary); top: 15px;
    }
    .thead-tr-loader:before { border-radius: 50%; border: 3px solid currentColor; opacity: .15; }
    .thead-tr-loader:before, .thead-tr-loader:after {
        width: 1.5rem; height: 1.5rem; margin: -1.25rem 0 0 -1.25rem;
        position: absolute; content: ''; top: 50%; left: 50%;
    }
    .thead-tr-loader:after {
        animation: loader .6s linear infinite;
        border-radius: 50%; border: 3px solid transparent;
        border-top-color: currentColor; box-shadow: 0 0 0 1px transparent;
    }

    /* ====== FIELDSETS & LEGENDS — Premium ====== */
    legend {
        background: linear-gradient(135deg, var(--mpa-primary), var(--mpa-primary-dark));
        color: white;
        padding: 0.55rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 50px;
        margin-bottom: 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 3px 12px rgba(var(--mpa-primary-rgb), 0.25);
    }
    fieldset {
        border: 1px solid var(--mpa-border);
        border-radius: var(--mpa-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: rgba(var(--mpa-primary-rgb), 0.01);
        transition: border-color 0.3s ease;
    }
    fieldset:hover { border-color: rgba(var(--mpa-primary-rgb), 0.15); }

    /* Color Picker Rows — Better grouping */
    .theme-setting-row {
        align-items: center;
        padding: 0.65rem 0.75rem;
        margin: 0 -0.75rem;
        border-radius: var(--mpa-radius-sm);
        transition: background 0.2s ease;
    }
    .theme-setting-row:hover { background: rgba(var(--mpa-primary-rgb), 0.03); }
    .theme-setting-row:last-child { border-bottom: none; }
    .theme-setting-row label {
        font-size: 0.88rem; font-weight: 500; color: var(--mpa-text);
    }
    .form-control-color {
        width: 60px; height: 40px; padding: 0.2rem;
        border-radius: 0.625rem;
        cursor: pointer;
        border: 2px solid var(--mpa-border);
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .form-control-color:hover {
        border-color: var(--mpa-primary);
        box-shadow: 0 4px 12px rgba(var(--mpa-primary-rgb), 0.15);
        transform: scale(1.05);
    }

    /* ====== BUTTONS — Gradient + Glow ====== */
    .btn-primary {
        background: linear-gradient(135deg, var(--mpa-primary), var(--mpa-primary-dark)) !important;
        border: none !important;
        border-radius: 50px !important;
        box-shadow: 0 3px 12px rgba(var(--mpa-primary-rgb), 0.25);
        font-weight: 600;
        transition: all var(--mpa-transition);
        position: relative;
        overflow: hidden;
    }
    .btn-primary::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 50%);
        border-radius: inherit;
    }
    .btn-primary:hover {
        box-shadow: 0 6px 20px rgba(var(--mpa-primary-rgb), 0.4) !important;
        transform: translateY(-2px);
    }

    .btn-success {
        border-radius: 50px !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }
    .btn-danger {
        border-radius: 50px !important;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
    }
    .btn-warning {
        border-radius: 50px !important;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
    }

    /* Badge styling */
    .badge {
        font-size: 0.73rem;
        padding: 0.4rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    /* Alert override */
    .alert { border-radius: var(--mpa-radius); border: none; }

    /* Multiple pages specific classes */
    .multiple-pages-theme { padding: 0.5rem; }
    .multiple-pages-theme fieldset { margin-bottom: 1rem; }

    /* Thumbnail images in tables */
    .table img {
        border-radius: 0.625rem;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }
    .table img:hover { transform: scale(1.05); }

    /* ====== FORM CONTROLS — Premium ====== */
    .form-control, .form-select {
        border-radius: var(--mpa-radius-sm);
        border: 1px solid var(--mpa-border);
        transition: all 0.3s ease;
        padding: 0.55rem 0.85rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--mpa-primary);
        box-shadow: 0 0 0 3px rgba(var(--mpa-primary-rgb), 0.1), 0 2px 8px rgba(var(--mpa-primary-rgb), 0.06);
    }
    .form-label {
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--mpa-text);
        margin-bottom: 0.4rem;
    }

    /* ====== MODAL — Premium ====== */
    .modal-content {
        border-radius: var(--mpa-radius) !important;
        border: none;
        box-shadow: 0 25px 80px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    .modal-header {
        background: linear-gradient(135deg, var(--mpa-primary), var(--mpa-primary-dark));
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }
    .modal-header .modal-title { color: #fff; font-weight: 700; }
    .modal-header .btn-close { filter: brightness(0) invert(1); opacity: 0.8; }
    .modal-header .btn-close:hover { opacity: 1; }
    .modal-body { padding: 1.5rem; }
    .modal-footer {
        border-top: 1px solid var(--mpa-border);
        padding: 1rem 1.5rem;
        background: #f8fafc;
    }
</style>

<span id="alertdiv_2"></span>

<div class="container-fluid">
<div class="card" style="border: none; box-shadow: none; background: transparent;">
	<div class="card-body" style="padding: 0;">
		<form class="form-horizontal" autocomplete="off" method="post" enctype="multipart/form-data" action="" id="admin-form">
			<div class="row">
				<div class="col-sm-12">
			<div class="mpa-header-bar">
				<div class="mpa-header-info">
					<h3><i class="bi bi-layers"></i> <?= __('admin.multiple_pages_theme_setting') ?></h3>
					<p><?= __('admin.theme_settings') ?> &mdash; <?= __('admin.theme') ?></p>
				</div>
				<div class="mpa-demo-actions">
					<span class="demo-label"><i class="bi bi-lightning-charge-fill me-1"></i><?= __('admin.quick_setup') ?></span>
					<div class="demo-btns">
						<button type="button" id="btn-import-full-demo" class="btn-demo-import">
							<i class="bi bi-cloud-download me-1"></i><?= __('admin.import_demo_data') ?>
						</button>
						<button type="button" id="btn-clear-full-demo" class="btn-demo-clear">
							<i class="bi bi-trash me-1"></i><?= __('admin.clear_demo_data') ?>
						</button>
					</div>
				</div>
			</div>
				<div class="mpa-tabs-wrapper" id="mpaTabsWrapper">
				<button type="button" class="mpa-tabs-arrow arr-left" id="mpaTabArrLeft" title="Scroll left"><i class="bi bi-chevron-left"></i></button>
				<button type="button" class="mpa-tabs-arrow arr-right" id="mpaTabArrRight" title="Scroll right"><i class="bi bi-chevron-right"></i></button>
				<ul class="nav mp-admin-tabs" role="tablist" id="TabsNav">
					<li class="nav-item">
						<a class="nav-link active show" href="#tab_home" data-bs-toggle="tab" role="tab">
							<i class="bi bi-house"></i> <?= __('admin.theme_home') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab_sliders" data-bs-toggle="tab" role="tab">
							<i class="bi bi-images"></i> <?= __('admin.theme_sliders') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab_home_content" data-bs-toggle="tab" role="tab">
							<i class="bi bi-file-earmark-richtext"></i> <?= __('admin.theme_home_content') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab_sections" data-bs-toggle="tab" role="tab">
							<i class="bi bi-grid-3x3-gap"></i> <?= __('admin.theme_sections') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab_home_videos" data-bs-toggle="tab" role="tab">
							<i class="bi bi-play-circle"></i> <?= __('admin.theme_home_videos') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab_recommendation" data-bs-toggle="tab" role="tab">
							<i class="bi bi-chat-quote"></i> <?= __('admin.theme_recommendation') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab_faq" data-bs-toggle="tab" role="tab">
							<i class="bi bi-question-circle"></i> <?= __('admin.theme_faq') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab_page_pages" data-bs-toggle="tab" role="tab">
							<i class="bi bi-file-earmark-text"></i> Pages & Links
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#theme_content" data-bs-toggle="tab" role="tab">
							<i class="bi bi-pencil-square"></i> <?= __('admin.theme_content') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab_settings" data-bs-toggle="tab" role="tab">
							<i class="bi bi-sliders"></i> <?= __('admin.theme_settings') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#theme_settings" data-bs-toggle="tab" role="tab">
							<i class="bi bi-palette"></i> <?= __('admin.theme') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url() ?>" target="_blank">
							<i class="bi bi-box-arrow-up-right"></i> <?= __('admin.view_site') ?>
						</a>
					</li>
				</ul>
				</div>
				</div>
			</div>

<div class="col-sm-12">

<div class="tab-content">
<div role="tabpanel" class="tab-pane p-3" id="theme_settings">
<div class="row">
<div class="col-lg-8">

<!-- ===== Toolbar: Reset All + Presets ===== -->
<div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
    <div class="d-flex flex-wrap gap-2">
        <span class="fw-semibold text-muted small me-1" style="line-height:2.2"><?= __('admin.color_preset') ?? 'Presets' ?>:</span>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill tc-preset-btn" data-preset="blue_pro"><i class="bi bi-circle-fill" style="color:#4361ee"></i> <?= __('admin.preset_blue_pro') ?? 'Blue Pro' ?></button>
        <button type="button" class="btn btn-sm btn-outline-success rounded-pill tc-preset-btn" data-preset="emerald"><i class="bi bi-circle-fill" style="color:#10b981"></i> <?= __('admin.preset_emerald') ?? 'Emerald' ?></button>
        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill tc-preset-btn" data-preset="sunset"><i class="bi bi-circle-fill" style="color:#f59e0b"></i> <?= __('admin.preset_sunset') ?? 'Sunset' ?></button>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill tc-preset-btn" data-preset="royal_purple"><i class="bi bi-circle-fill" style="color:#7c3aed"></i> <?= __('admin.preset_royal_purple') ?? 'Royal Purple' ?></button>
    </div>
    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" id="reset-all-theme-colors">
        <i class="bi bi-arrow-counterclockwise"></i> <?= __('admin.reset_all_colors') ?>
    </button>
</div>

<?php
/* ── Helper: render one color row ── */
function tc_row($key, $label, $default, $theme) {
    $val = (!empty($theme[$key]) && $theme[$key] !== '') ? $theme[$key] : $default;
    ?>
    <div class="d-flex align-items-center gap-3 py-2 px-2 tc-color-row rounded-3">
        <label class="mb-0 flex-grow-1 small fw-medium"><?= $label ?></label>
        <input class="form-control form-control-color tc-color-input" type="color"
               name="theme[<?= $key ?>]" data-key="<?= $key ?>"
               value="<?= $val ?>" style="width:52px;height:34px;padding:2px">
        <button type="button" class="btn btn-sm btn-outline-secondary default-front-theme-setting rounded-circle" value="<?= $key ?>" title="<?= __('admin.default') ?? 'Reset' ?>" style="width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center">
            <i class="bi bi-arrow-counterclockwise" style="font-size:.75rem"></i>
        </button>
        <span class="tc-saved-badge badge bg-success rounded-pill" style="display:none;font-size:.65rem">Saved</span>
    </div>
    <?php
}
?>

<!-- ===== Group 1: Header - Before Scroll ===== -->
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-2 px-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-browser-edge text-primary me-1"></i> <?= __('admin.header_before_scroll') ?? 'Header &mdash; Before Scroll' ?></h6>
    </div>
    <div class="card-body py-2 px-3">
        <?php
        tc_row('front_header_color_before_scroll', __('admin.background') ?? 'Background', 'transparent', $theme);
        tc_row('front_header_button_color_before_scroll', __('admin.button') ?? 'Button', '#4361ee', $theme);
        tc_row('front_header_button_text_color_before_scroll', __('admin.button_text') ?? 'Button Text', '#ffffff', $theme);
        tc_row('front_header_button_hover_color_before_scroll', __('admin.button_hover') ?? 'Button Hover', '#3a56d4', $theme);
        ?>
    </div>
</div>

<!-- ===== Group 2: Header - After Scroll ===== -->
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-2 px-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-down-circle text-primary me-1"></i> <?= __('admin.header_after_scroll') ?? 'Header &mdash; After Scroll' ?></h6>
    </div>
    <div class="card-body py-2 px-3">
        <?php
        tc_row('front_header_color_after_scroll', __('admin.background') ?? 'Background', '#ffffff', $theme);
        tc_row('front_header_button_color_after_scroll', __('admin.button') ?? 'Button', '#4361ee', $theme);
        tc_row('front_header_button_text_color_after_scroll', __('admin.button_text') ?? 'Button Text', '#ffffff', $theme);
        tc_row('front_header_button_hover_color_after_scroll', __('admin.button_hover') ?? 'Button Hover', '#f72585', $theme);
        ?>
    </div>
</div>

<!-- ===== Group 3: Buttons & Links ===== -->
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-2 px-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-hand-index text-primary me-1"></i> <?= __('admin.buttons_and_links') ?? 'Buttons &amp; Links' ?></h6>
    </div>
    <div class="card-body py-2 px-3">
        <?php
        tc_row('front_button_color', __('admin.button_color') ?? 'Button Color', '#4361ee', $theme);
        tc_row('front_button_hover_color', __('admin.button_hover_color') ?? 'Button Hover', '#3a56d4', $theme);
        tc_row('front_button_text_color', __('admin.button_text_color') ?? 'Button Text', '#ffffff', $theme);
        ?>
    </div>
</div>

<!-- ===== Group 4: Content & Sections ===== -->
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-2 px-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-layout-text-window text-primary me-1"></i> <?= __('admin.content_and_sections') ?? 'Content &amp; Sections' ?></h6>
    </div>
    <div class="card-body py-2 px-3">
        <?php
        tc_row('front_runner_bar_color', __('admin.runner_bar_color') ?? 'News Ticker BG', '#4361ee', $theme);
        tc_row('front_runner_bar_text_color', __('admin.runner_bar_text_color') ?? 'News Ticker Text', '#ffffff', $theme);
        tc_row('front_theme_text_color', __('admin.theme_titles_color') ?? 'Section Titles', '#4361ee', $theme);
        tc_row('front_faq_before_hover_color', __('admin.faq_before_hover_color') ?? 'FAQ Default', '#f8fafc', $theme);
        tc_row('front_faq_after_hover_color', __('admin.faq_after_hover_color') ?? 'FAQ Active / Hover', '#4361ee', $theme);
        ?>
    </div>
</div>

<!-- ===== Group 5: Footer & Banner ===== -->
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-2 px-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-layout-sidebar-reverse text-primary me-1"></i> <?= __('admin.footer_and_banner') ?? 'Footer &amp; Banner' ?></h6>
    </div>
    <div class="card-body py-2 px-3">
        <?php
        tc_row('bottom_banner_before_footer', __('admin.bottom_banner_before_footer') ?? 'CTA Banner BG', '#4361ee', $theme);
        tc_row('front_footer_color', __('admin.footer_color') ?? 'Footer BG', '#0f172a', $theme);
        tc_row('header_menu_bg_color_responsive', __('admin.header_menu_bg_color_responsive') ?? 'Mobile Menu BG', '#0f172a', $theme);
        ?>
    </div>
</div>

</div><!-- /.col-lg-8 -->

<!-- ===== Live Preview Panel ===== -->
<div class="col-lg-4">
    <div class="position-sticky" style="top:100px">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-2 px-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-eye text-primary me-1"></i> <?= __('admin.live_preview') ?? 'Live Preview' ?></h6>
            </div>
            <div class="card-body p-0" style="overflow:hidden;border-radius:0 0 var(--mpa-radius) var(--mpa-radius)">
                <!-- Navbar preview (before scroll) -->
                <div id="tc-preview-nav-before" class="px-3 py-2 d-flex align-items-center justify-content-between" style="background:transparent;transition:all .3s">
                    <span class="fw-bold small" style="color:#fff">Logo</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small" style="color:rgba(255,255,255,.8)">Home</span>
                        <span class="small" style="color:rgba(255,255,255,.8)">FAQ</span>
                        <span id="tc-preview-btn-before" class="badge rounded-pill px-2 py-1" style="font-size:.7rem;transition:all .3s">Log In</span>
                    </div>
                </div>
                <!-- Navbar preview (after scroll) -->
                <div id="tc-preview-nav-after" class="px-3 py-2 d-flex align-items-center justify-content-between border-top" style="transition:all .3s">
                    <span class="fw-bold small" id="tc-preview-logo-after">Logo</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small" id="tc-preview-link-after" style="color:#1e293b">Home</span>
                        <span class="small" style="color:#1e293b">FAQ</span>
                        <span id="tc-preview-btn-after" class="badge rounded-pill px-2 py-1" style="font-size:.7rem;transition:all .3s">Log In</span>
                    </div>
                </div>
                <!-- Content preview -->
                <div class="px-3 py-3 text-center" style="background:#f8fafc">
                    <h6 class="mb-1" id="tc-preview-title" style="transition:color .3s">Section Title</h6>
                    <p class="mb-2 small text-muted">Preview description text</p>
                    <button class="btn btn-sm rounded-pill px-3" id="tc-preview-cta" style="transition:all .3s">Get Started</button>
                </div>
                <!-- Runner bar preview -->
                <div id="tc-preview-runner" class="px-3 py-1 text-center small" style="transition:all .3s">
                    Breaking News Ticker
                </div>
                <!-- Footer preview -->
                <div id="tc-preview-footer" class="px-3 py-2 text-center small" style="color:#94a3b8;transition:all .3s">
                    Footer Area
                </div>
            </div>
        </div>
    </div>
</div><!-- /.col-lg-4 -->

</div><!-- /.row -->

<!-- ===== Auto-save + Preset + Preview JS ===== -->
<script>
$(function(){
    var base_url = '<?= base_url() ?>';
    var saveTimers = {};

    /* ---- Live Preview updater ---- */
    function updatePreview() {
        var g = function(k, fb) { return $('input[data-key="'+k+'"]').val() || fb; };
        /* Before scroll navbar */
        var nbBefore = g('front_header_color_before_scroll','transparent');
        $('#tc-preview-nav-before').css('background', nbBefore === 'transparent' ? 'linear-gradient(135deg,#4361ee,#7c3aed)' : nbBefore);
        $('#tc-preview-btn-before').css({'background': g('front_header_button_color_before_scroll','#4361ee'), 'color': g('front_header_button_text_color_before_scroll','#fff')});
        /* After scroll navbar */
        $('#tc-preview-nav-after').css('background', g('front_header_color_after_scroll','#ffffff'));
        $('#tc-preview-logo-after').css('color', '#1e293b');
        $('#tc-preview-link-after').css('color', '#1e293b');
        $('#tc-preview-btn-after').css({'background': g('front_header_button_color_after_scroll','#4361ee'), 'color': g('front_header_button_text_color_after_scroll','#fff')});
        /* CTA button */
        $('#tc-preview-cta').css({'background': g('front_button_color','#4361ee'), 'color': g('front_button_text_color','#fff')});
        /* Section title */
        $('#tc-preview-title').css('color', g('front_theme_text_color','#4361ee'));
        /* Runner */
        $('#tc-preview-runner').css({'background': g('front_runner_bar_color','#4361ee'), 'color': g('front_runner_bar_text_color','#fff')});
        /* Footer */
        $('#tc-preview-footer').css('background', g('front_footer_color','#0f172a'));
    }
    updatePreview();

    /* ---- Debounced auto-save ---- */
    $(document).on('input change', '.tc-color-input', function(){
        updatePreview();
        var $inp = $(this);
        var key = $inp.data('key');
        var val = $inp.val();
        var $badge = $inp.closest('.tc-color-row').find('.tc-saved-badge');
        clearTimeout(saveTimers[key]);
        saveTimers[key] = setTimeout(function(){
            $.ajax({
                url: base_url + 'themes/themesetting/',
                type: 'POST',
                data: { setting: key, color: val },
                success: function(){
                    $badge.fadeIn(200);
                    setTimeout(function(){ $badge.fadeOut(400); }, 1500);
                }
            });
        }, 500);
    });

    /* ---- Color Presets ---- */
    var presets = {
        blue_pro: {
            front_header_color_before_scroll:'transparent', front_header_button_color_before_scroll:'#4361ee',
            front_header_button_text_color_before_scroll:'#ffffff', front_header_button_hover_color_before_scroll:'#3a56d4',
            front_header_color_after_scroll:'#ffffff', front_header_button_color_after_scroll:'#4361ee',
            front_header_button_text_color_after_scroll:'#ffffff', front_header_button_hover_color_after_scroll:'#f72585',
            front_button_color:'#4361ee', front_button_hover_color:'#3a56d4', front_button_text_color:'#ffffff',
            front_runner_bar_color:'#4361ee', front_runner_bar_text_color:'#ffffff', front_theme_text_color:'#4361ee',
            front_faq_before_hover_color:'#f8fafc', front_faq_after_hover_color:'#4361ee',
            bottom_banner_before_footer:'#4361ee', front_footer_color:'#0f172a', header_menu_bg_color_responsive:'#0f172a'
        },
        emerald: {
            front_header_color_before_scroll:'transparent', front_header_button_color_before_scroll:'#10b981',
            front_header_button_text_color_before_scroll:'#ffffff', front_header_button_hover_color_before_scroll:'#059669',
            front_header_color_after_scroll:'#ffffff', front_header_button_color_after_scroll:'#10b981',
            front_header_button_text_color_after_scroll:'#ffffff', front_header_button_hover_color_after_scroll:'#f43f5e',
            front_button_color:'#10b981', front_button_hover_color:'#059669', front_button_text_color:'#ffffff',
            front_runner_bar_color:'#10b981', front_runner_bar_text_color:'#ffffff', front_theme_text_color:'#10b981',
            front_faq_before_hover_color:'#f0fdf4', front_faq_after_hover_color:'#10b981',
            bottom_banner_before_footer:'#059669', front_footer_color:'#064e3b', header_menu_bg_color_responsive:'#064e3b'
        },
        sunset: {
            front_header_color_before_scroll:'transparent', front_header_button_color_before_scroll:'#f59e0b',
            front_header_button_text_color_before_scroll:'#ffffff', front_header_button_hover_color_before_scroll:'#d97706',
            front_header_color_after_scroll:'#ffffff', front_header_button_color_after_scroll:'#ef4444',
            front_header_button_text_color_after_scroll:'#ffffff', front_header_button_hover_color_after_scroll:'#f59e0b',
            front_button_color:'#ef4444', front_button_hover_color:'#dc2626', front_button_text_color:'#ffffff',
            front_runner_bar_color:'#f59e0b', front_runner_bar_text_color:'#ffffff', front_theme_text_color:'#ef4444',
            front_faq_before_hover_color:'#fefce8', front_faq_after_hover_color:'#f59e0b',
            bottom_banner_before_footer:'#ef4444', front_footer_color:'#1c1917', header_menu_bg_color_responsive:'#1c1917'
        },
        royal_purple: {
            front_header_color_before_scroll:'transparent', front_header_button_color_before_scroll:'#7c3aed',
            front_header_button_text_color_before_scroll:'#ffffff', front_header_button_hover_color_before_scroll:'#6d28d9',
            front_header_color_after_scroll:'#ffffff', front_header_button_color_after_scroll:'#7c3aed',
            front_header_button_text_color_after_scroll:'#ffffff', front_header_button_hover_color_after_scroll:'#ec4899',
            front_button_color:'#7c3aed', front_button_hover_color:'#6d28d9', front_button_text_color:'#ffffff',
            front_runner_bar_color:'#7c3aed', front_runner_bar_text_color:'#ffffff', front_theme_text_color:'#7c3aed',
            front_faq_before_hover_color:'#f5f3ff', front_faq_after_hover_color:'#7c3aed',
            bottom_banner_before_footer:'#6d28d9', front_footer_color:'#1e1b4b', header_menu_bg_color_responsive:'#1e1b4b'
        }
    };

    $(document).on('click', '.tc-preset-btn', function(){
        var name = $(this).data('preset');
        var p = presets[name];
        if (!p) return;
        if (!confirm('<?= __('admin.apply_preset_confirm') ?? 'Apply this color preset? All current colors will be replaced.' ?>')) return;
        $.each(p, function(key, val) {
            var $inp = $('input[data-key="'+key+'"]');
            if ($inp.length) $inp.val(val).trigger('change');
        });
    });

    /* ---- Style for color rows ---- */
    $('<style>.tc-color-row:hover{background:rgba(var(--mpa-primary-rgb,67,97,238),.04)}.tc-color-row+.tc-color-row{border-top:1px solid rgba(0,0,0,.04)}</style>').appendTo('head');
});
</script>
</div><!-- /#theme_settings tabpanel -->

<div role="tabpanel" class="tab-pane p-3 active show" id="tab_home">

<div class="card border rounded-3 shadow-sm">
    <div class="card-body p-3">
        <div class="row g-3 align-items-start">

            <div class="col-lg-6">
                <img src="<?= base_url('assets/images/themes/multiple_pages.png') ?>"
                     alt="Multiple Pages Theme"
                     class="rounded-3 border w-100"
                     style="max-height:320px; object-fit:cover; object-position:top center;">
            </div>

            <div class="col-lg-6">
                <h6 class="fw-bold mb-3"><i class="bi bi-check2-circle text-success me-1"></i> <?= __('admin.theme_support_features') ?></h6>
                <ul class="list-unstyled mb-0" style="columns:2; column-gap:1rem;">
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-images text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_dynamic_slider') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-grid-1x2 text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_dynamic_sections') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-hand-thumbs-up text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_dynamic_recommendation') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-file-earmark-text text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_dynamic_content') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-play-circle text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_dynamic_videos') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-file-earmark-plus text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_dynamic_pages') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-arrows-move text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_drag_and_drop') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-file-text text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_terms_page') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-envelope text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_contact_us_page') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-question-circle text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_faq_dynamic_page') ?></span></li>
                    <li class="mb-2 d-flex align-items-center"><i class="bi bi-list text-primary me-2" style="font-size:0.85rem;"></i> <span style="font-size:0.84rem;"><?= __('admin.support_dynamic_bottom_menus') ?></span></li>
                </ul>
            </div>

        </div>
    </div>
</div>

</div>

										

<div role="tabpanel" class="tab-pane p-3" id="tab_sliders">

<div class="col-12">

	<div class="card m-b-30">

		<div class="card-header">

			<h4 class="card-title pull-left"><?= __('admin.top_slider_settings') ?></h4>

			

		</div>

		<div class="card-body">

			<table class="table">

				<tbody >

					<tr>
						<td width="200"><?= __('admin.auto_play_slider') ?></td>

						<td>

							<?php if(isset($theme_multiple_page_settings['top_slider_auto_play']) && $theme_multiple_page_settings['top_slider_auto_play'] == 1) { ?>
								<i class="fa fa-toggle-on" style="cursor: pointer;color: green;font-size: 35px;width:50px" onclick="change_theme_multiple_page(this, 'top_slider_auto_play');" id="top_slider_auto_play-1"></i>
							<?php } else { ?>
								<i class="fa fa-toggle-off" style="cursor: pointer;color: red;font-size: 35px;width:50px" onclick="change_theme_multiple_page(this, 'top_slider_auto_play');" id="top_slider_auto_play-0"></i>
							<?php } ?>

							<input class="theme_multiple_page_settings" type="hidden" name="theme_multiple_page[top_slider_auto_play]" value="<?= $theme_multiple_page_settings['top_slider_auto_play'] ?? 0 ?>">

						</td>
					</tr>

					<tr class="top_slider_auto_play_timing" <?= (isset($theme_multiple_page_settings['top_slider_auto_play']) && $theme_multiple_page_settings['top_slider_auto_play'] == 1) ? "" : 'style="display:none;"' ?> >
						<td><?= __('admin.auto_play_slider_timing') ?></td>

						<td>

							<input type="number" class="form-control theme_multiple_page_settings" name="theme_multiple_page[top_slider_auto_timing]" value="<?= $theme_multiple_page_settings['top_slider_auto_timing'] ?? 10 ?>">
							<small><?= __('admin.the_default_timing_10_sec');?></small>
						</td>
					</tr>

				</tbody>

			</table>

			<div class="row">

				<button type="button" class="btn btn-primary btn-submit-theme"> <?= __('admin.submit') ?> </button>

				<span class="loading-submit"></span>

			</div>

		</div>
		<div class="card-header">

			<h4 class="card-title pull-left"><?= __('admin.top_sliders_listing') ?></h4>

			<div class="pull-right">

				<a class="btn btn-primary" href="<?= base_url('themes/add_new_slider/')  ?>"><?= __('admin.add_slider') ?></a>

			</div>

		</div>
		<div class="card-body">
			<div class="table-responsive">

				<!-- <small class="text-muted"><?= __('admin.change_position_by_simply_drag_drop_rows') ?></small> -->

				<table class="table-hover table-striped table">

					<thead>

						<tr>

							<th><?= __('admin.title') ?></th>

							<th width="450"><?= __('admin.description') ?></th>

							<th><?= __('admin.image') ?></th>

							<th><?= __('admin.link') ?></th>

							<th><?= __('admin.button_text') ?></th>

							<th><?= __('admin.status') ?></th>

							<th><?= __('admin.language') ?></th> 

							<th><?= __('admin.action')?></th>

						</tr>

					</thead>

					<tbody data-whe_column="section_id" data-pos_column="position" data-table="theme_sections" class="sortable">

						<?php if(empty($theme_sliders)){ ?>

						<tr style="background-color:#FFF!important;">

							<td colspan="100%" class="text-center"><?= __('admin.no_sections_available') ?></td>

						</tr>

						<?php } ?>

						<?php foreach ($theme_sliders as $key => $slider) { ?>

						<tr data-id="<?= $section->section_id ?>" style="background-color:#FFF!important; cursor: move;">

							<td><?= $slider->title ?></td>

							<td width="450"><?= substr($slider->description, 0, 100); ?><?= (strlen($slider->description) > 100) ? "..." : "";?></td>

							<td><img src="<?php echo base_url("assets/images/theme_images/".$slider->image) ?>" height="50" width="auto"></td>

							<td><?= $slider->link ?></td>

							<td><?= $slider->button_text ?></td>

							<td><?= ($slider->status == 1) ?

								'<lable class="badge bg-success">'.__('admin.active').'</lable>' :

								'<lable class="badge bg-secondary">'.__('admin.inactive').'</lable>' ?>

							</td>

							<td><?= $slider->name ?></td>

							<td>

								<a class="btn btn-primary btn-sm" href="<?= base_url('themes/edit_slider/'. $slider->slider_id) ?>"><i class="fa fa-edit"></i></a>

								<a class="btn confirm btn-danger btn-sm" href="<?= base_url('themes/theme_delete/'. $slider->slider_id) ?>"><i class="fa fa-trash"></i></a>

							</td>

						</tr>

						<?php } ?>

					</tbody>

				</table>

			</div>
			
		</div>

	</div>

</div>

</div>

<div role="tabpanel" class="tab-pane p-1" id="tab_sections">

	<div class="col-12">

		<div class="card m-b-30">

			<div class="card-header">

				<h4 class="card-title pull-left"><?= __('admin.section') ?></h4>

				<div class="pull-right">

					<a class="btn btn-primary" href="<?= base_url('themes/add_new_section/')  ?>"><?= __('admin.add_page_section') ?></a>

				</div>

			</div>

			<div class="card-body">

				<div class="table-responsive">

					<small class="text-muted"><?= __('admin.change_position_by_simply_drag_drop_rows') ?></small>

					<table class="table-hover table-striped table">

						<thead>

							<tr>

								<th><?= __('admin.title') ?></th>

								<th width="450"><?= __('admin.description') ?></th>

								<th><?= __('admin.image') ?></th>

								<th><?= __('admin.link') ?></th>

								<th><?= __('admin.button_text') ?></th>

								<th><?= __('admin.status') ?></th>

								<th><?= __('admin.language') ?></th>

								<th><?= __('admin.action')?></th>

							</tr>

						</thead>

						<tbody data-whe_column="section_id" data-pos_column="position" data-table="theme_sections" class="sortable">

							<?php if(empty($theme_sections)){ ?>

							<tr style="background-color:#FFF!important;">

								<td colspan="100%" class="text-center"><?= __('admin.no_sections_available') ?></td>

							</tr>

							<?php } ?>

							<?php foreach ($theme_sections as $key => $section) { ?>

							<tr data-id="<?= $section->section_id ?>" style="background-color:#FFF!important; cursor: move;">

								<td><?= $section->title ?></td>

								<td width="450"><?= substr($section->description, 0, 100); ?><?= (strlen($section->description) > 100) ? "..." : "";?></td>

								<td><img src="<?php echo base_url("assets/images/theme_images/".$section->image) ?>" height="50" width="auto"></td>

								<td><?= $section->link ?></td>

								<td><?= $section->button_text ?></td>


								<td><?= ($section->status == 1) ?

									'<lable class="badge bg-success">'.__('admin.active').'</lable>' :

									'<lable class="badge bg-secondary">'.__('admin.inactive').'</lable>' ?>

								</td>
								<td><?= $section->name ?></td>

								<td>

									<a class="btn btn-primary btn-sm" href="<?= base_url('themes/edit_section/'. $section->section_id) ?>"><i class="fa fa-edit"></i></a>

									<a class="btn confirm btn-danger btn-sm" href="<?= base_url('themes/delete_section/'. $section->section_id) ?>"><i class="fa fa-trash"></i></a>

								</td>

							</tr>

							<?php } ?>

						</tbody>

					</table>

				</div>

			</div>

		</div>

	</div>

</div>

<div role="tabpanel" class="tab-pane p-3" id="tab_recommendation">

	<div class="col-12">

		<div class="card m-b-30">

			<div class="card-header">

				<h4 class="card-title pull-left"><?= __('admin.recommendations') ?></h4>

				

				<div class="pull-right">

					<a class="btn btn-primary" href="<?= base_url('themes/add_new_recommendation/')  ?>"><?= __('admin.add_new_recommendation') ?></a>

				</div>

			</div>

			<div class="card-body">

				<div class="table-responsive">

					<small class="text-muted"><?= __('admin.change_position_by_simply_drag_drop_rows') ?></small>

					<table class="table-hover table-striped table">

						<thead>

							<tr>

								<th><?= __('admin.title')?></th>

								<th><?= __('admin.occupation')?></th>

								<th><?= __('admin.description')?></th>

								<th><?= __('admin.image')?></th>

								<th><?= __('admin.status')?></th>

								<th><?= __('admin.language')?></th>

								<th><?= __('admin.action')?></th>

							</tr>

						</thead>

						<tbody class="sortable" data-whe_column="recommendation_id" data-pos_column="position" data-table="theme_recommendation">

							<?php if(empty($theme_recommendation)){ ?>

							<tr>

								<td colspan="100%" class="text-center"><?= __('admin.no_recommendation_available') ?></td>

							</tr>

							<?php } ?>

							<?php foreach ($theme_recommendation as $key => $recommendation) { ?>

							<tr data-id="<?= $recommendation->recommendation_id ?>" style="background-color:#FFF!important; cursor: move;">

								<td><?= $recommendation->title ?></td>

								<td><?= $recommendation->occupation ?></td>

								<td width="450"><?= substr($recommendation->description, 0, 100); ?><?= (strlen($recommendation->description) > 100) ? "..." : "";?></td>

								<td><img src="<?php echo base_url("assets/images/theme_images/".$recommendation->image) ?>" height="50" width="auto"></td>

								<td><?= ($recommendation->status == 1) ?

									'<lable class="badge bg-success">'.__('admin.active').'</lable>' :

									'<lable class="badge bg-secondary">'.__('admin.inactive').'</lable>' ?>

								</td>

								<td><?= $recommendation->name ?></td>

								<td>

									<a class="btn btn-primary btn-sm" href="<?= base_url('themes/edit_recommendation/'. $recommendation->recommendation_id ) ?>"><i class="fa fa-edit"></i></a>

									<a class="btn confirm btn-danger btn-sm" href="<?= base_url('themes/delete_recommendation/'. $recommendation->recommendation_id ) ?>"><i class="fa fa-trash"></i></a>

								</td>

							</tr>

							<?php } ?>

						</tbody>

					</table>

				</div>

			</div>

		</div>

	</div>

</div>



<div role="tabpanel" class="tab-pane p-3" id="tab_faq">

	<div class="col-12">

		<div class="card m-b-30">

			<div class="card-header">

				<h4 class="card-title pull-left"><?= __('admin.faq') ?></h4>

				

				<div class="pull-right">

					<a class="btn btn-primary" href="<?= base_url('themes/add_new_faq/')  ?>"><?= __('admin.add_new_faq') ?></a>

				</div>

			</div>

			<div class="card-body">

				<div class="table-responsive">

					<small class="text-muted"><?= __('admin.change_position_by_simply_drag_drop_rows') ?></small>

					<table class="table-hover table-striped table">

						<thead>
							<tr>

								<th><?= __('admin.question') ?></th>

								<th><?= __('admin.answer') ?></th>

								<th><?= __('admin.status') ?></th>

								<th><?= __('admin.language') ?></th>

								<th><?= __('admin.action') ?></th>

							</tr>

						</thead>

						<tbody class="sortable" data-whe_column="faq_id" data-pos_column="position" data-table="theme_faq">

							<?php if(empty($theme_faqs)){ ?>

							<tr>
								<td colspan="100%" class="text-center"><?= __('admin.no_faq_available') ?></td>
							</tr>

							<?php } ?>

							<?php foreach ($theme_faqs as $key => $faq) { ?>

							<tr data-pos="<?= $faq->position ?>" data-id="<?= $faq->faq_id ?>" style="background-color:#FFF!important; cursor: move;">

								<td><?= $faq->faq_question ?></td>

								<td width="450"><?= substr($faq->faq_answer, 0, 100); ?><?= (strlen($faq->faq_answer) > 100) ? "..." : "";?></td>

								<td><?= ($faq->status == 1) ?

									'<lable class="badge bg-success">'.__('admin.active').'</lable>' :

									'<lable class="badge bg-secondary">'.__('admin.inactive').'</lable>' ?>

								</td>
								<td><?= $faq->name ?></td>
								<td>
									<a class="btn btn-primary btn-sm" href="<?= base_url('themes/edit_faq/'. $faq->faq_id ) ?>"><i class="fa fa-edit"></i></a>

									<a class="btn confirm btn-danger btn-sm" href="<?= base_url('themes/delete_faq/'. $faq->faq_id ) ?>"><i class="fa fa-trash"></i></a>
								</td>

							</tr>

							<?php } ?>

						</tbody>

					</table>

				</div>

			</div>

		</div>

	</div>

</div>



<div role="tabpanel" class="tab-pane p-3" id="tab_home_content">

	<div class="col-12">

		<div class="card m-b-30">

			<div class="card-header">

				<h4 class="card-title pull-left"><?= __('admin.home_content_settings') ?></h4>

			</div>

			<div class="card-body">

				<table class="table">

					<tbody >

						<tr>
							<td width="200"><?= __('admin.auto_play_slider') ?></td>

							<td>

								<?php if(isset($theme_multiple_page_settings['home_content_auto_play']) && $theme_multiple_page_settings['home_content_auto_play'] == 1) { ?>
									<i class="fa fa-toggle-on" style="cursor: pointer;color: green;font-size: 35px;width:50px" onclick="change_theme_multiple_page(this, 'home_content_auto_play');" id="home_content_auto_play-1"></i>
								<?php } else { ?>
									<i class="fa fa-toggle-off" style="cursor: pointer;color: red;font-size: 35px;width:50px" onclick="change_theme_multiple_page(this, 'home_content_auto_play');" id="home_content_auto_play-0"></i>
								<?php } ?>

								<input class="theme_multiple_page_settings" type="hidden" name="theme_multiple_page[home_content_auto_play]" value="<?= $theme_multiple_page_settings['home_content_auto_play'] ?? 0 ?>">

							</td>
						</tr>

						<tr class="home_content_auto_play_timing" <?= (isset($theme_multiple_page_settings['home_content_auto_play']) && $theme_multiple_page_settings['home_content_auto_play'] == 1) ? "" : 'style="display:none;"' ?> >
							<td><?= __('admin.auto_play_slider_timing') ?></td>

							<td>

								<input type="number" class="form-control theme_multiple_page_settings" name="theme_multiple_page[home_content_auto_timing]" value="<?= $theme_multiple_page_settings['home_content_auto_timing'] ?? 10 ?>">
								<small><?= __('admin.the_default_timing_10_sec');?></small>
							</td>
						</tr>

					</tbody>

				</table>

				<div class="row">

					<button type="button" class="btn btn-primary btn-submit-theme"> <?= __('admin.submit') ?> </button>

					<span class="loading-submit"></span>

				</div>

			</div>
			<div class="card-header">

				<h4 class="card-title pull-left"><?= __('admin.home_content') ?></h4>

				

				<div class="pull-right">

					<a class="btn btn-primary" href="<?= base_url('themes/add_new_homecontent/')  ?>"><?= __('admin.add_home_content') ?></a>

				</div>

			</div>
			<div class="card-body">

				<div class="table-responsive">

					<small class="text-muted"><?= __('admin.change_position_by_simply_drag_drop_rows') ?></small>

					<table class="table-hover table-striped table">

						<thead>

							<tr>

								<th><?= __('admin.title') ?></th>

								<th><?= __('admin.description') ?></th>

								<th><?= __('admin.image') ?></th>

								<th><?= __('admin.status') ?></th>

								<th><?= __('admin.language') ?></th>

								<th><?= __('admin.action')?></th>

							</tr>

						</thead>

						<tbody class="sortable" data-whe_column="homecontent_id" data-pos_column="position" data-table="theme_homecontent">

							<?php if(empty($theme_homecontent)){ ?>

							<tr>

								<td colspan="100%" class="text-center"><?= __('admin.no_content_available') ?></td>

							</tr>

							<?php } ?>

							<?php foreach ($theme_homecontent as $key => $homecontent) { ?>

							<tr data-id="<?= $homecontent->homecontent_id ?>" style="background-color:#FFF!important; cursor: move;">

								<td width="150"><?= $homecontent->title ?></td>

								<td width="450">
									<?= substr(strip_tags($homecontent->description), 0, 100); ?>
									<?= (strlen(strip_tags($homecontent->description))> 100) ? '...' : '';?></td>

								<td><img src="<?php echo base_url("assets/images/theme_images/".$homecontent->image) ?>" height="50" width="100"></td>

								<td><?= ($homecontent->status == 1) ?

									'<lable class="badge bg-success">'.__('admin.active').'</lable>' :

									'<lable class="badge bg-secondary">'.__('admin.inactive').'</lable>' ?>

								</td>
								<td><?= $homecontent->name ?></td>
								<td>

									<a class="btn btn-primary btn-sm" href="<?= base_url('themes/edit_homecontent/'. $homecontent->homecontent_id) ?>"><i class="fa fa-edit"></i></a>

									<a class="btn confirm btn-danger btn-sm" href="<?= base_url('themes/delete_homecontent/'. $homecontent->homecontent_id) ?>"><i class="fa fa-trash"></i></a>

								</td>

							</tr>

							<?php } ?>

						</tbody>

					</table>

				</div>

			</div>

		</div>

	</div>

</div>

<div role="tabpanel" class="tab-pane p-3" id="tab_home_videos">

	<div class="col-12">

		<div class="card m-b-30">

			<div class="card-header">

				<h4 class="card-title pull-left"><?= __('admin.home_video') ?></h4>

				

				<div class="pull-right">

					<a class="btn btn-primary" href="<?= base_url('themes/add_new_video/')  ?>"><?= __('admin.add_new_video') ?></a>

				</div>

			</div>

			<div class="card-body">

				<div class="table-responsive">

					<small class="text-muted"><?= __('admin.change_position_by_simply_drag_drop_rows') ?></small>

					<table class="table-hover table-striped table">

						<thead>

							<tr>

								<th><?= __('admin.video_title') ?></th>

								<th><?= __('admin.video_sub_title') ?></th>

								<th><?= __('admin.video_link') ?></th>

								<th><?= __('admin.watch_video') ?></th>

								<th><?= __('admin.status') ?></th>

								<th><?= __('admin.language')?></th>

								<th><?= __('admin.action')?></th>

							</tr>

						</thead>

						<tbody class="sortable" data-whe_column="video_id" data-pos_column="position" data-table="theme_videos">

							<?php if(empty($theme_videos)){ ?>

							<tr>

								<td colspan="100%" class="text-center"><?= __('admin.no_data_available')?></td>

							</tr>

							<?php } ?>

							<?php foreach ($theme_videos as $key => $video) { ?>

							<tr data-id="<?= $video->video_id ?>" style="background-color:#FFF!important; cursor: move;">

								<td><?= $video->video_title ?></td>

								<td><?= $video->video_sub_title ?></td>

								<td><?= $video->video_link ?>

								</td>

								<td>

									<a class="btn btn-info btn-sm" href="<?= $video->video_link ?>" target="_blank" role="button"><?= __('admin.watch_video') ?></a>

								</td>

								<td>

									<?= ($video->status == 1) ?

									'<lable class="badge bg-success">'.__('admin.active').'</lable>' :

									'<lable class="badge bg-secondary">'.__('admin.inactive').'</lable>' ?>

								</td>
								<td><?= $video->name ?></td>
								<td>

									<a class="btn btn-primary btn-sm" href="<?= base_url('themes/edit_video/'. $video->video_id) ?>"><i class="fa fa-edit"></i></a>

									<a class="btn confirm btn-danger btn-sm" href="<?= base_url('themes/delete_video/'. $video->video_id) ?>"><i class="fa fa-trash"></i></a>

								</td>

							</tr>

							<?php } ?>

						</tbody>

					</table>

				</div>

			</div>

		</div>

	</div>

</div>

<div role="tabpanel" class="tab-pane p-3" id="tab_page_pages">

	<div class="col-12">

		<div class="card m-b-30">

			<div class="card-header">

				<h4 class="card-title pull-left"><?= __('admin.theme_pages') ?></h4>

				<div class="pull-right">

					<a class="btn btn-primary" href="<?= base_url('themes/add_new_page/')  ?>"><?= __('admin.add_new_page') ?></a>

				</div>

				<div class="pull-right mr-2 ml-2">
					<select class="form-control" name="search_theme_pages" id="search_theme_pages">
						<option value=""><?= __('admin.select') ?>..</option>

						<option value="header" <?php echo ($this->input->get('menu_pages') == 'header') ? 'selected' : '' ?>><?= __('admin.header_menu_pages') ?></option>

						<option value="header_dropdown" <?php echo ($this->input->get('menu_pages') == 'header_dropdown') ? 'selected' : '' ?>><?= __('admn.header_dropdown_pages') ?></option>

						<option value="footer" <?php echo ($this->input->get('menu_pages') == 'footer') ? 'selected' : '' ?>><?= __('admin.footer_menu_pages') ?></option>

						<option value="both" <?php echo ($this->input->get('menu_pages') == 'both') ? 'selected' : '' ?>><?= __('admin.header_footer_both') ?></option>
					</select>
				</div>

			</div>

			<div class="card-body">
				<div class="table-responsive homepage_top_menu_pages">

					<small class="text-muted"><?= __('admin.change_position_by_simply_drag_drop_rows') ?></small>

					<table class="table-hover table-striped table">

						<thead>

							<tr>

								<th><?= __('admin.id') ?></th>

								<th><?= __('admin.page_name') ?></th>

								<th><?= __('admin.slug_others') ?></th>

								<th><?= __('admin.top_banner_title') ?></th>

								<th><?= __('admin.top_banner_sub_title') ?></th>

								<th><?= __('admin.page_content_title') ?></th>

								<th><?= __('admin.status') ?></th>

								<th><?= __('admin.language') ?></th>

								<th><?= __('admin.action')?></th>

							</tr>

						</thead>

						<tbody class="sortable_pages_for_top_menus">

							<?php if(empty($theme_pages)){ ?>

							<tr>

								<td colspan="100%" class="text-center"><?= __('admin.no_page_available') ?></td>

							</tr>

							<?php } ?>

							<?php foreach ($theme_pages as $key => $page) { ?>

							<tr class="deleterow-<?php echo $page->page_id ?>">

								<td>
									<?= $page->page_id ?>

									<input type="hidden" name="page_id[]" value="<?= $page->page_id ?>"/>
								</td>

								<td><?= $page->page_name ?></td>

								<td>
									<div>Slug:: <span class="badge bg-secondary"><?= $page->slug ?></span></div>
									<div>isHeaderMenu:: <span class="badge bg-secondary"><?php echo $page->is_header_menu==1 ? 'True' : 'False' ?></span></div>
									<div>isDropdown:: <span class="badge bg-secondary"><?php echo $page->is_header_dropdown==1 ? 'True' : 'False' ?></span></div>
									<div>isFooterMenu:: <span class="badge bg-secondary"><?php echo $page->link_footer_section != '' ? 'True' : 'False' ?></span></div>
								</td>

								<td><?= $page->top_banner_title ?></td>

								<td><?= $page->top_banner_sub_title ?></td>

								<td><?= $page->page_content_title ?></td>

								<td>

									<?php if ($page->status ==1) { ?>

									<i class="fa fa-toggle-on" style="cursor: pointer;color: green;font-size: 35px;width:50px" onclick="change_page_status('<?= $page->page_id ?>');" id="page_status_active_<?= $page->page_id ?>"> 

									<?php } else{ ?>

									<i class="fa fa-toggle-off" style="cursor: pointer;color: red;font-size: 35px;width:50px" onclick="change_page_status('<?= $page->page_id ?>');" id="page_status_active_<?= $page->page_id ?>"> 

									<?php } ?>	

									<input type="hidden" name="page_status" id="page_status_<?= $page->page_id ?>" value="<?php echo $page->status;?>">

									</i>
								</td>
								<td><?php if($page->page_type=='editable'){ ?><?= $page->name ?><?php }?></td>

								<td>
									<?php if($page->page_type=='editable'){ ?>

									<a class="btn btn-primary btn-sm" href="<?= base_url('themes/edit_page/'. $page->page_id) ?>"><i class="fa fa-edit"></i></a>

									<a class="btn btn-danger btn-sm delete_page" data-id="<?= $page->page_id; ?>" data-href="<?= base_url('themes/delete_page/'. $page->page_id) ?>"><i class="fa fa-trash"></i></a>

									<?php } else { ?>
									<a class="btn btn-primary btn-sm" href="<?= base_url('themes/edit_page/'. $page->page_id) ?>"><i class="fa fa-edit"></i></a>

									<?php } ?>

								</td>

							</tr>

							<?php } ?>

						</tbody>

					</table>

					<span class="homepages_top_menu_positions_loading" style="display:none;">

						<div class="thead-tr-loader"></div>

					</span>

				</div>

			</div>

		</div>

	</div>

	<div class="col-12">
		<div class="card m-b-30">
			<div class="card-header">
				<h4 class="card-title pull-left"><?= __('admin.theme_links') ?></h4>
				<div class="pull-right">
					<span id="add_new_link" class="btn btn-primary text-white"><?= __('admin.add_new_link') ?></span>
				</div>
			</div>

			<div class="card-body">
				<div class="table-responsive">
					<table class="table-hover table-striped table">
						<thead>
							<tr>
								<th><?= __('admin.link_title') ?></th>
								<th><?= __('admin.link_url') ?></th>
								<th class="text-center"><?= __('admin.link_position') ?></th>
								<th><?= __('admin.status') ?></th>
								<th><?= __('admin.language') ?></th>
								<th><?= __('admin.action')?></th>
							</tr>
						</thead>

						<tbody id="links-tbody">

							<?php if(empty($theme_links)){ ?>
								<tr>
									<td colspan="100%" class="text-center"><?= __('admin.no_links_available') ?></td>
								</tr>
							<?php } ?>

							<?php foreach ($theme_links as $link) { ?>
							<tr data-tlink_id="<?= $link->tlink_id ?>" data-tlink_title="<?= $link->tlink_title ?>" data-tlink_url="<?= $link->tlink_url ?>" data-tlink_position="<?= $link->tlink_position ?>" data-tlink_status="<?= $link->tlink_status ?>" data-tlink_target_blank="<?= $link->tlink_target_blank ?>" data-language_id="<?= $link->language_id ?>">
								<td><?= $link->tlink_title ?></td>
								<td><?= $link->tlink_url ?></td>
								<td class="text-center"><?php 

									switch ($link->tlink_position) {
										case 1:
											echo __('admin.menu_a');
											break;
										case 2:
											echo __('admin.menu_b');
											break;
										case 3:
											echo __('admin.menu_c');
											break;
										case 4:
											echo __('admin.menu_d');
											break;
										default:
											echo __('admin.none');
											break;
									}
									 
								?></td>
								<td>
									<i class="btn_tlink_status_toggle fa <?= ($link->tlink_status == 1) ? 'fa-toggle-on' : 'fa-toggle-off' ?>" style="cursor: pointer; color: <?= ($link->tlink_status == 1) ? 'green' : 'red' ?>; font-size: 35px; width:50px"></i>
								</td>
								<th><?= $link->name ?></th>
								<td>
									<a class="btn btn-primary text-white btn-sm btn_edit_tlink"><i class="fa fa-edit"></i></a>
									<a class="btn btn-danger text-white btn-sm btn_delete_tlink"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
							<?php } ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>

<div role="tabpanel" class="tab-pane p-3" id="theme_content">
	<div class="col-md-12">

		<div class="card m-b-30">

			<div class="card-header">
				<h4 class="card-title pull-left"><?= __('admin.theme_content') ?></h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div role="tabpanel" class="tab-pane p-3" id="tab_setting_inner_content"> 
						<div class="col-md-4">
							<div class="form-group">
					            <label class="control-label"><?= __('admin.select_language') ?></label>
					            <select class="form-control" name="language_id" id="drpLanguage" onchange="return changeLanguage();">
					                <?php 
					                if(isset($languages))
					                {
					                    foreach($languages as $language)
					                    {?>
					                    <option <?php 

					                     if($language['is_default']==1) {echo 'selected';} ?> value="<?=$language['id']?>"><?=$language['name'] ?></option>
					                  
					                   <?php  }     
					                }?>
					                
					            </select>
					    	</div>
					    </div>
				     <div id="setting_content_html">
					    </div>
				    <br/>
				    <br/>
				    <div class="row">
						<button type="button" class="btn btn-primary btn-submit-theme"> <?= __('admin.submit') ?> 
						</button>
							<span class="loading-submit"></span>

						</div>
						<div class="invalid-form-error" style="color: red;display: none;"><?= __('admin.invalid_form_details_please_check_and_validate') ?> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>			

</div>
								
<div role="tabpanel" class="tab-pane p-3" id="tab_settings">

	<div class="col-md-12">

		<div class="card m-b-30">

			<div class="card-header">
				<h4 class="card-title pull-left"><?= __('admin.theme_settings') ?></h4>
			</div>
			<div class="card-body">
				 
					<fieldset class="mt-1">

						<legend><?= __('admin.homepage_section_management') ?></legend>

						<div class="row">

							<div class="col-md-12">

								<small class="text-muted">&nbsp;<?= __('admin.change_position_by_simply_drag_drop_rows') ?></small>

									<table class="table-hover table">

									<thead>

										<tr>

										<th style="verticle-align:middle;"><?= __('admin.enable').'/'.__('admin.disable') ?></th>

										<th style="verticle-align:middle;"><?= __('admin.section_name') ?>
											<span class="home_sections_positions_loading float-right" style="display:none;">
												<div class="thead-tr-loader"></div>
											</span>
										</th>

										</tr>

									</thead>

									<tbody class="sortable2">

										<?php foreach($home_sections_settings as $hs_setting) { ?>

										<tr style="background-color:#FFF!important; cursor: move;">

											<td style="width:100px; text-align:center;">

											<?php if ($hs_setting->sec_is_enable == 1) { ?>

												<i class="fa fa-toggle-on" style="cursor: pointer;color: green;font-size: 35px;width:50px" onclick="change_section_status(<?= $hs_setting->sec_id ?>);" id="section_status_active_<?= $hs_setting->sec_id ?>"></i> 

											<?php } else{ ?>

												<i class="fa fa-toggle-off" style="cursor: pointer;color: red;font-size: 35px;width:50px" onclick="change_section_status(<?= $hs_setting->sec_id ?>);" id="section_status_active_<?= $hs_setting->sec_id ?>"></i>

											<?php } ?>	

											<input type="hidden" name="sec_status[]" id="section_status_<?= $hs_setting->sec_id ?>" value="<?= $hs_setting->sec_is_enable ?>"/>

											<input type="hidden" name="sec_id[]" value="<?= $hs_setting->sec_id ?>"/>

											</td>

											<td><?= $hs_setting->sec_title ?></td>

										</tr>

										<?php } ?>

									</tbody>

								</table>

							</div>

						</div>

						<hr/>
						<h5 class="mt-4">
							<?= __('admin.top_banner_runner_settings') ?>
						</h5>
						<hr class="m-0" />
						<table class="table table-borderless">

						<tbody >

							<tr>
								<td width="250"><?= __('admin.auto_play_runner') ?></td>

								<td>

									<?php if(isset($theme_multiple_page_settings['home_runner_auto_play']) && $theme_multiple_page_settings['home_runner_auto_play'] == 1) { ?>
										<i class="fa fa-toggle-on" style="cursor: pointer;color: green;font-size: 35px;width:50px" onclick="change_theme_multiple_page(this, 'home_runner_auto_play');" id="home_runner_auto_play-1"></i>
									<?php } else { ?>
										<i class="fa fa-toggle-off" style="cursor: pointer;color: red;font-size: 35px;width:50px" onclick="change_theme_multiple_page(this, 'home_runner_auto_play');" id="home_runner_auto_play-0"></i>
									<?php } ?>

									<input class="theme_multiple_page_settings" type="hidden" name="theme_multiple_page[home_runner_auto_play]" value="<?= $theme_multiple_page_settings['home_runner_auto_play'] ?? 0 ?>">

								</td>
							</tr>

							<tr class="home_runner_auto_play_timing" <?= (isset($theme_multiple_page_settings['home_runner_auto_play']) && $theme_multiple_page_settings['home_runner_auto_play'] == 1) ? "" : 'style="display:none;"' ?> >
								<td><?= __('admin.auto_play_runner_timing') ?></td>

								<td>

									<input type="number" class="form-control theme_multiple_page_settings" name="theme_multiple_page[home_runner_auto_timing]" value="<?= $theme_multiple_page_settings['home_runner_auto_timing'] ?? 10 ?>">
									<small><?= __('admin.the_default_timing_10_sec');?></small>
								</td>
							</tr>

						</tbody>

					</table>

					</fieldset>
				  
				<br>
 
				<br>

				<div class="row">

					<button type="button" class="btn btn-primary btn-submit-theme"> <?= __('admin.submit') ?> </button>

					<span class="loading-submit"></span>

				</div>
				<div class="invalid-form-error" style="color: red;display: none;"><?= __('admin.invalid_form_details_please_check_and_validate') ?></div>
				</div> 
				
			</div>

		</div>

	</div>

</div>

</div>

</div>

</div>

</form>

</div>

</div>

				<div id="link_form_modal" class="modal" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?= __('admin.add_new_link') ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form id="link_form">
								<input name="tlink_id" type="hidden" value="0"/>
								<div class="row">
									<div class="col-12">
										<div class="form-group">
								            <label class="control-label"><?= __('admin.select_language') ?></label>
								            <select class="form-control" name="language_id" id="drpLanguage" onchange="return changeLanguage();">
								                <?php 
								                if(isset($languages))
								                {
								                    foreach($languages as $language)
								                    {?>
								                    <option <?php 

								                    if($tutorial['language_id']==$language['id'])
								                    {
								                    	echo 'selected';
								                    }
								                    else if(!isset($tutorial) && $language['is_default']==1) {echo 'selected';} ?> value="<?=$language['id']?>"><?=$language['name'] ?></option>
								                  
								                   <?php  }     
								                }?>
								                
								            </select>
								    	</div>
								    </div>
									<div class="col-12">
										<div class="form-group">
											<label><?= __('admin.link_title') ?></label>
											<input name="tlink_title" type="text" class="form-control" placeholder="<?= __('admin.link_title_to_display') ?>">
										</div>
									</div>
									<div class="col-12">
										<div class="form-group">
											<label><?= __('admin.link_url') ?></label>
											<input name="tlink_url" type="text" class="form-control" placeholder="<?= __('admin.link_url_to_open') ?>">
											<span class="text-danger tlink_url_error"></span>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label><?= __('admin.link_position') ?></label>
											<select name="tlink_position" class="form-control">
												<option value="0"><?= __('admin.none') ?></option>
												<option value="1"><?= __('admin.footer_menu_a') ?></option>
												<option value="2"><?= __('admin.footer_menu_b') ?></option>
												<option value="3"><?= __('admin.footer_menu_c') ?></option>
												<option value="4"><?= __('admin.footer_menu_d') ?></option>
											</select>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label><?= __('admin.link_status') ?></label>
											<select name="tlink_status" class="form-control">
												<option value="1"><?= __('admin.enable') ?></option>
												<option value="0"><?= __('admin.disabled') ?></option>
											</select>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label><?= __('admin.is_open_in_new_tab') ?></label>
											<select name="tlink_target_blank" class="form-control">
												<option value="1"><?= __('admin.yes') ?></option>
												<option value="0"><?= __('admin.no') ?></option>
											</select>
										</div>
									</div>
								</div>
								
								
							</form>
						</div>
							<div class="modal-footer">
								<button id="link_form_submit" type="button" class="btn btn-primary"><?= __('admin.save_changes') ?></button>
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
							</div>
						</div>
				</div>
					<script type="text/javascript">

						$("#link_form_submit").on('click',function(evt){

							$(".tlink_url_error").empty();

							$("#link_form_submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

							var res = $('input[name="tlink_url"]').val();
							if(res != "") {
								var result = res.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
								if(result == null && !res.includes("http://localhost") && !res.includes("https://localhost")) {
									$(".tlink_url_error").text('<?= __('admin.please_enter_valid_link') ?>');
									$("#link_form_submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
									return false;
								}
							}
							
							evt.preventDefault();

							$.ajax({
								url:'<?= base_url('themes/store_link') ?>',
								type:'POST',
								dataType:'json',
								data:$("#link_form").serializeArray(),
								complete:function(result){
									$("#link_form_submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
									$('#link_form_modal').modal('hide');
								},
								success:function(response){
									let swalIcon = response.status ? 'success' : 'error';
									if(response.status) {
										let linksBody = "";

										if(response.data.length == 0) {
											linksBody = `<tr><td colspan="100%" class="text-center">`+'<?= __('admin.no_links_available') ?>'+`</td></tr>`;
										}

										for (let index = 0; index < response.data.length; index++) {
											const element = response.data[index];

											let link_pos = '<?= __('admin.none') ?>';
											switch (element['tlink_position']) {
												case "1":
													link_pos = '<?= __('admin.menu_a') ?>';
													break;
												case "2":
													link_pos = '<?= __('admin.menu_b') ?>';
													break;
												case "3":
													link_pos = '<?= __('admin.menu_c') ?>';
													break;
												case "4":
													link_pos = '<?= __('admin.menu_d') ?>';
													break;
												default:
													link_pos = '<?= __('admin.none') ?>';
													break;
											}

											console.log(link_pos, element['tlink_position'])

											let link_class = (element['tlink_status'] == 1) ? 'fa-toggle-on' : 'fa-toggle-off';
											let link_color = (element['tlink_status'] == 1) ? 'green' : 'red';

											linksBody += `<tr data-tlink_id="`+ element['tlink_id'] +`" data-tlink_title="`+ element['tlink_title'] +`" data-tlink_url="`+ element['tlink_url'] +`" data-tlink_position="`+ element['tlink_position'] +`" data-tlink_status="`+ element['tlink_status'] +`" data-tlink_target_blank="`+ element['tlink_target_blank'] +`"  
												data-language_id="`+ element['language_id'] +`"
												>
												<td>`+ element['tlink_title'] +`</td>
												<td>`+ element['tlink_url'] +`</td>
												<td class="text-center">`+link_pos+`</td>
												<td><i class="btn_tlink_status_toggle fa `+ link_class +`" style="cursor: pointer; color: `+ link_color +`; font-size: 35px; width:50px"></i></td>
												<td>`+ element['name'] +`</td>
												<td>
													<a class="btn btn-primary text-white btn-sm btn_edit_tlink"><i class="fa fa-edit"></i></a>
													<a class="btn btn-danger text-white btn-sm btn_delete_tlink"><i class="fa fa-trash"></i></a>
												</td>
											</tr>`
										}
										$("#links-tbody").html(linksBody);
									}
									Swal.fire({
										icon: swalIcon,
										text: response.message,
									});
								}
							});
							return false;
						});

						$(document).on('click', '.btn_delete_tlink', function(){
							Swal.fire({
								title: '<?= __('admin.are_you_sure') ?>',
								text: '<?= __('admin.you_not_be_able_to_revert_this') ?>',
								icon: 'warning',
								showCancelButton: true,
								confirmButtonColor: '#3085d6',
								cancelButtonColor: '#d33',
								confirmButtonText: '<?= __('admin.yes_delete_it') ?>'
							}).then((result) => {
								if (result.value) {
									let thatBtn = $(this);
									thatBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
									$.ajax({
										url:'<?= base_url('themes/delete_link') ?>',
										type:'POST',
										dataType:'json',
										data:{tlink_id:$(this).closest('tr').data('tlink_id')},
										complete:function(res){
											thatBtn.closest("tr").remove();
											Swal.fire('Deleted!', '<?= __('admin.your_link_has_been_deleted') ?>', 'success');
										}
									});
								}
							});
						});

						$(document).on('click', '.btn_edit_tlink', function(){
							let dataRow = $(this).closest('tr');
							$('#link_form_modal input[name="tlink_id"]').val(dataRow.data('tlink_id'));
							$('#link_form_modal input[name="tlink_title"]').val(dataRow.data('tlink_title'));
							$('#link_form_modal input[name="tlink_url"]').val(dataRow.data('tlink_url'));
							$('#link_form_modal select[name="tlink_position"]').val(dataRow.data('tlink_position'));
							$('#link_form_modal select[name="tlink_status"]').val(dataRow.data('tlink_status'));
							$('#link_form_modal select[name="tlink_target_blank"]').val(dataRow.data('tlink_target_blank'));
							$('#link_form_modal select[name="language_id"]').val(dataRow.data('language_id'));
							$('#link_form_modal').modal('show');
						});

						$(document).on('click', '#add_new_link', function(){
							$('#link_form_modal input[name="tlink_id"]').val('');
							$('#link_form_modal input[name="tlink_title"]').val('');
							$('#link_form_modal input[name="tlink_url"]').val('');
							$('#link_form_modal select[name="language_id"]').val(1);
							$('#link_form_modal').modal('show');
						});

						$(document).on('change', '#slider_link_type', function(){

							$('#slider-link').val($(this).val());

						});

						$(document).on('click', ".btn_tlink_status_toggle", function(){
							let tlink_id = $(this).closest('tr').data('tlink_id');
							let tlink_status = $(this).hasClass('fa-toggle-off') ? 1 : 0;
							if(tlink_status) {
								$(this).addClass('fa-toggle-on').removeClass('fa-toggle-off');
								$(this).css("color", "green");
							} else {
								$(this).addClass('fa-toggle-off').removeClass('fa-toggle-on');
								$(this).css("color", "red");
							}

							$.ajax({
								url: "<?= base_url('themes/tlink_status_toggle') ?>",
								type: "POST",
								dataType: "json",
								data: {
									tlink_id:tlink_id,
									tlink_status:tlink_status,
								},
								success: function (response) {	
								}
							});
						});	

						$(function() {

							$( ".sortable2" ).sortable({

								update: function( event, ui ) {

									update_homepage_sections_table();

								}

							});

							$( ".sortable2" ).disableSelection();

						});

						$(function() {

							$( ".sortable_pages_for_top_menus" ).sortable({

								update: function( event, ui ) {

									update_homepage_top_menu_position();

								}

							});

							$( ".sortable_pages_for_top_menus" ).disableSelection();

						});

						$(function() {

							$( ".sortable" ).sortable({

								update: function( event, ui ) {

									let positions = [];

									$(this).children('tr').each(function () {

										if($(this).data('id') != null) {

											positions.push($(this).data('id'));

										}

									});

									$.ajax({

										url: "<?= base_url('themes/change_positions')  ?>",

										type: "POST",

										dataType: "json",

										data: {table:$(this).data('table'), whe_column:$(this).data('whe_column'), pos_column:$(this).data('pos_column'),positions:JSON.stringify(positions)},

										success: function (response) {	
										}

									});

								}

							});

							$( ".sortable" ).disableSelection();

						});

					</script>



					<script type="text/javascript">

						

						var loadFile = function(event) {

							var image = document.getElementById('output');

							image.src = URL.createObjectURL(event.target.files[0]);

						};



						$(document).on('click', '.remove-runner-btn', function(){

							$(this).parent().remove();

							$('#runners-section .col-md-12').each(function( index ) {

								$(this).find('.control-label').text('<?= __('admin.runner') ?>'+(index+1));

							});

							let count = $('#runners-section .col-md-12').length;

							if (count == 1) {

								$('#runners-section').prepend(`

								<div class="col-md-12">

									<div class="form-group">

										<label class="control-label">`+'<?= __('admin.runner') ?>'+` `+count+`</label>

										<input name="top_banner_slider[]" class="form-control" type="text">

									</div>

									<button type="button" class="btn btn-danger btn-md remove-runner-btn" style="position: absolute; top: 30px; right: 11px;"><i class="fa fa-trash"></i></button>

								</div>`);

							}

						});





						$(document).on('click', '#add-more-runner-btn', function(){

							let count = $('#runners-section .col-md-12').length;

							$(this).parent().before(`

							<div class="col-md-12">

								<div class="form-group">

									<label class="control-label">`+'<?= __('admin.runner') ?>'+` `+count+`</label>

									<input name="top_banner_slider[]" class="form-control" type="text">

								</div>

								<button type="button" class="btn btn-danger btn-md remove-runner-btn" style="position: absolute; top: 30px; right: 11px;"><i class="fa fa-trash"></i></button>

							</div>`);

						});



						// $(".btn-slider-submit").on('click',function(evt){

						// 	$("#linkError").empty();

						// 	$this = $("#admin-form");

						// 	$(".btn-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

						// 	$('.loading-submit').show();

						// 	var res = $('#slider-link').val();

						// 	if(res != "") {

						// 		var result = res.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);

						// 		if(result == null && !res.includes("http://localhost") && !res.includes("https://localhost"))

						// 		{

						// 			$("#linkError").append('<?= __('admin.please_enter_valid_link') ?>');

						// 			$(".btn-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');

						// 			return false;

						// 		}
						// 	}

							

						// 	evt.preventDefault();

						// 	var formData = new FormData($("#admin-form")[0]);



						// 	formData = formDataFilter(formData);

							

						// 	$.ajax({

						// 		url:'<?= base_url('themes/update_slider') ?>',

						// 		type:'POST',

						// 		dataType:'json',

						// 		cache:false,

						// 		contentType: false,

						// 		processData: false,

						// 		data:formData,

						// 		xhr: function (){

						// 			var jqXHR = null;



						// 			if ( window.ActiveXObject ){

						// 				jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );

						// 			}else {

						// 				jqXHR = new window.XMLHttpRequest();

						// 			}

									

						// 			jqXHR.upload.addEventListener( "progress", function ( evt ){

						// 				if ( evt.lengthComputable ){

						// 					var percentComplete = Math.round( (evt.loaded * 100) / evt.total );

						// 					$('.loading-submit').text(percentComplete + "% "+'<?= __('admin.loading') ?>');

						// 				}

						// 			}, false );



						// 			jqXHR.addEventListener( "progress", function ( evt ){

						// 				if ( evt.lengthComputable ){

						// 					var percentComplete = Math.round( (evt.loaded * 100) / evt.total );

						// 					$('.loading-submit').text('<?= __('admin.save') ?>');

						// 				}

						// 			}, false );

						// 			return jqXHR;

						// 		},

						// 		complete:function(result){

						// 			$(".btn-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');

						// 		},

						// 		success:function(result){

						// 			$('.loading-submit').hide();

						// 			$this.find(".has-error").removeClass("has-error");

						// 			$this.find("span.text-danger").remove();

						// 			if(result['location'])
						// 				window.location = result['location'];

						// 			if(result['errors']){
						// 				$.each(result['errors'], function(i,j){
						// 					$ele = $this.find('[name="'+ i +'"]');
						// 					$ele.parents(".form-group").addClass("has-error");
						// 					if(i == 'avatar')
						// 						$ele.parent().parent().append("<span class='text-danger'>"+ j +"</span>");
						// 					else
						// 						$ele.after("<span class='text-danger'>"+ j +"</span>");
						// 				});
						// 			}

						// 		},

						// 	})

						// 	return false;

						// });

					</script>



<script>
function read_url(input,name,display_id) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
    	$("input[name='"+name+"']").val('image.jpg');
      	$('#'+display_id).attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}


$(document).ready(function() {



$(".delete_page").click(function(e){
	if(!confirm("Are your sure ?")) return false;
	var href = $(this).attr("data-href");
	var id = $(this).attr("data-id");
	$.ajax({
		url: href,
		type: "GET",
		success: function (data) {
			$(".deleterow-" + id).remove();
			var alert_div = '<div class="alert alert-success alert-dismissable" ><button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">&times;</button>'+
				'<span id="alert_msg_2">'+'<?= __('admin.item_has_been_successfully_deleted') ?>'+'</span></div>';
			$("#alertdiv_2").append(alert_div);
			$("#alertdiv_2").show();
			setTimeout( function(){
			$("#alertdiv_2").fadeOut();
			}  , 2000 );
		}
	});
});

	$('#summernote').summernote({
	    minHeight: 300,
		toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']]
		]
	});
});

</script>

<script type="text/javascript">

$(".confirm").on('click',function(){

if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

		return 1;

	})

</script>

<script type="text/javascript">

function validURL(str) {
	let pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
		'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
		'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
		'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
		'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
		'(\\#[-a-z\\d_]*)?$','i'); // fragment locator

	return !!pattern.test(str) || str.match(/^https?:\/\/\w+(\.\w+)*(:[0-9]+)?(\/.*)?$/g) !== null;
}

var imageArrays = [
					'homepage_video_section_bg',
					'logo',
					'faq_banner_image',
					'contact_banner_image',
					'avatar_login',
					'avatar_registration',
					'avatar_terms'
				];
 
$(".btn-submit-theme").on('click',function(evt){

	evt.preventDefault();

	$this = $("#admin-form");

	$(".btn-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

	$('.loading-submit').show();

	

	let is_invalid_form = false;

	let links_array = ["youtube_link", "facebook_link", "twitter_link", "instegram_link", "banner_button_link"]

	$.each(links_array, function( index, value ) {
		$("#"+value+"_error").empty();
		let link = $('#'+value).val();
		if(link != "") {
			if(!validURL(link)) {
				is_invalid_form = true;
				$("#"+value+"_error").append('<?= __('admin.please_enter_valid_link') ?>');
			}
		}
	});

	

	$("#whatsapp_number_error").empty();

	let whatsapp_number = $("input[name='whatsapp_number']").val();	

	if(whatsapp_number != "") {
		let whatsapp_number_is_valid = whatsapp_number.match(/^\+[1-9]{1}[0-9]{3,14}$/g);

		if(whatsapp_number_is_valid == null) {

			is_invalid_form = true;

			$("#whatsapp_number_error").append('<?= __('admin.please_enter_valid_mobile_number') ?>');

		}
	
	}


	$("#contact_us_phone_error").empty();

	let contact_us_phone_number = $("input[name='contact_us_phone']").val();	

	if(contact_us_phone_number != "") {
		let contact_us_phone_is_valid = contact_us_phone_number.match(/^\+[1-9]{1}[0-9]{3,14}$/g);

		if(contact_us_phone_is_valid == null) {

			is_invalid_form = true;

			$("#contact_us_phone_error").append('<?= __('admin.please_enter_valid_mobile_number') ?>');

		}
	}

	let contact_us_email = $("input[name='contact_us_email']").val();
	if(contact_us_email != "") {	
		if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test($("input[name='contact_us_email']").val())) {
			is_invalid_form = true;
			$("#contact_us_email_error").append('<?= __('admin.please_enter_valid_email_address') ?>');
		}
	}


	if(is_invalid_form) {

		$(".btn-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');

		$(".invalid-form-error").show();

		return false;

	}else{
		$(".invalid-form-error").hide();
	}



var formData = new FormData($("#admin-form")[0]);

formData = formDataFilter(formData);


$.ajax({

url:'<?= base_url('themes/update_settings') ?>',

type:'POST',

dataType:'json',

cache:false,

contentType: false,

processData: false,

data:formData,

xhr: function (){

var jqXHR = null;

if ( window.ActiveXObject ){

jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );

}else {

jqXHR = new window.XMLHttpRequest();

}

jqXHR.upload.addEventListener( "progress", function ( evt ){

if ( evt.lengthComputable ){

var percentComplete = Math.round( (evt.loaded * 100) / evt.total );

$('.loading-submit').text(percentComplete + "% "+'<?= __('admin.loading') ?>');

}

}, false );

jqXHR.addEventListener( "progress", function ( evt ){

if ( evt.lengthComputable ){

var percentComplete = Math.round( (evt.loaded * 100) / evt.total );

$('.loading-submit').text('<?= __('admin.save') ?>');

}

}, false );

return jqXHR;

},

complete:function(result){

$(".btn-submit-theme").prop('disabled', false).html($(this).data('original-text') || 'Submit');

},

success:function(result){

$('.loading-submit').hide();

$this.find(".has-error").removeClass("has-error");

$this.find("span.text-danger").remove();

if(result['location']){

	window.location = result['location'];

}

if(result['errors']){

$.each(result['errors'], function(i,j){

	$ele = $this.find('[name="'+ i +'"]');
	$ele.parents(".form-group").addClass("has-error");
	if(imageArrays.includes(i))
		$ele.parent().parent().append("<span class='text-danger'>"+ j +"</span>");
	else
		$ele.after("<span class='text-danger'>"+ j +"</span>");

});

}

},

})

return false;

});

$(".alert").fadeTo(2000, 500).slideUp(500, function(){

$(".alert").alert('close');

});

function update_homepage_top_menu_position() {
	$('.homepages_top_menu_positions_loading').show();

	let page_id = $('input[name="page_id[]"]').map(function(){ 

		return this.value; 

	}).get();

	$.ajax({

		url: "<?= base_url('themes/change_homepage_top_menu_positions')  ?>",

		type: "POST",

		data: { 'page_id[]': page_id},

		xhr: function (){

			var jqXHR = null;

			if ( window.ActiveXObject ){

				jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );

			}else {

				jqXHR = new window.XMLHttpRequest();

			}

			jqXHR.upload.addEventListener( "progress", function ( evt ){

				if ( evt.lengthComputable ){

					var percentComplete = Math.round( (evt.loaded * 100) / evt.total );

				}

			}, false );

			jqXHR.addEventListener( "progress", function ( evt ){

				if ( evt.lengthComputable ){

					var percentComplete = Math.round( (evt.loaded * 100) / evt.total );

				}

			}, false );

			return jqXHR;

		},

		complete: function(){

			setTimeout(function(){ $('.homepages_top_menu_positions_loading').hide(); }, 500);

		}

	});
}


function update_homepage_sections_table(){

	$('.home_sections_positions_loading').show();



	let sec_id = $('input[name="sec_id[]"]').map(function(){ 

		return this.value; 

	}).get();



	let sec_status = $('input[name="sec_status[]"]').map(function(){ 

		return this.value; 

	}).get();



	$.ajax({

		url: "<?= base_url('themes/change_home_sections_positions')  ?>",

		type: "POST",

		data: { 'sec_id[]': sec_id, 'sec_status[]': sec_status},

		xhr: function (){

			var jqXHR = null;

			if ( window.ActiveXObject ){

				jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );

			}else {

				jqXHR = new window.XMLHttpRequest();

			}

			jqXHR.upload.addEventListener( "progress", function ( evt ){

				if ( evt.lengthComputable ){

					var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
					// $('.home_sections_positions_loading').text(percentComplete + "% "+'<?= __('admin.completed') ?>');

				}

			}, false );

			jqXHR.addEventListener( "progress", function ( evt ){

				if ( evt.lengthComputable ){

					var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
					// $('.home_sections_positions_loading').text(percentComplete + "%"+'<?= __('admin.completed') ?>');

				}

			}, false );

			return jqXHR;

		},

		complete: function(){

			// $('.home_sections_positions_loading').text('<?= __('admin.records_updated_successfully') ?>');

			setTimeout(function(){ $('.home_sections_positions_loading').hide(); }, 500);

		}

	});

}


$(document).on('click', '.theme_multiple_page_settings_save', function(){
	let postData = {};

	$('.theme_multiple_page_settings').each(function( index ) {
	  postData[$(this).attr('name')] = $(this).val();
	});

	$.ajax({

		url: "<?= base_url('themes/store_theme_multiple_page_settings')  ?>",

		type: "POST",

		data: postData,

		success: function (response) {	

			console.log(response);	

		}
	});
});


function change_theme_multiple_page(that, type){

	let value = $(that).hasClass('fa-toggle-off') ? 1 : 0;

	$('input[name="theme_multiple_page['+type+']"]').val(value);

	if ( value == 0 ) {

		$(that).addClass('fa-toggle-off');

		$(that).removeClass('fa-toggle-on');

		$(that).css("color", "red");

		$('.'+type+'_timing').css('display', 'none');

	} else {

		$(that).addClass('fa-toggle-on');

		$(that).removeClass('fa-toggle-off');

		$(that).css("color", "green");

		$('.'+type+'_timing').css('display', '');
	}
}

function change_section_status(id){

	let status = $('#section_status_'+id).val();

	if ( status == 1 ) {

		$('#section_status_'+id).val(0);

		$('#section_status_active_'+id).addClass('fa-toggle-off');

		$('#section_status_active_'+id).removeClass('fa-toggle-on');

		$('#section_status_active_'+id).css("color", "red");

	} else {

		$('#section_status_'+id).val(1);

		$('#section_status_active_'+id).addClass('fa-toggle-on');

		$('#section_status_active_'+id).removeClass('fa-toggle-off');

		$('#section_status_active_'+id).css("color", "green");

	}

	update_homepage_sections_table();

}

function change_page_status(id){

	var page_status = $('#page_status_'+id).val();

	if (page_status== 1) {

		var status = 0;

		var msg = '<?= __('admin.page_inactive_successfully') ?>';

	}else{

		var status = 1;

		var msg = '<?= __('admin.page_active_successfully') ?>';

	}

	$.ajax({

	url: "<?= base_url('themes/update_page_status/')  ?>",

	type: "POST",

	dataType: "json",

	data: {id:id,status:status},

	success: function (data)

	{	

		if (page_status == 1) {

			$('#page_status_active_'+id).addClass('fa-toggle-off');

			$('#page_status_active_'+id).removeClass('fa-toggle-on');

			$('#page_status_active_'+id).css("color", "red");

			$('#page_status_'+id).val(0);

		}

		if (page_status == 0) {

			$('#page_status_active_'+id).addClass('fa-toggle-on');

			$('#page_status_active_'+id).removeClass('fa-toggle-off');

			$('#page_status_active_'+id).css("color", "green");

			$('#page_status_'+id).val(1);

		}

	}
	});
}


$(document).on('click', '.btn-delete-image', function(){
	let input_name = $(this).data('img_input');
	let image_ele_id = $(this).data('img_ele');
	let placeholder_image = $(this).data('img_placeholder');
	$('input[name="'+input_name+'"]').val('');
	$('#'+image_ele_id).attr('src', placeholder_image);
	$(this).remove()
});

$(document).on('change', '#search_theme_pages', function(){
	let menu_name 				= $(this).val();
	let current_url 			= $(location).attr('href');
	
	var url = new URL(current_url);
	url.searchParams.set("menu_pages", menu_name);

	window.location.href = url.href; 
});

$(".save-theme-settings").on('click',function(evt){
    evt.preventDefault();

    var front_header_color_before_scroll = $('.front_header_color_before_scroll').val();
    if (front_header_color_before_scroll == '#000000') {
    	front_header_color_before_scroll = 'transparent';
    }
    var front_header_button_color_before_scroll = $('.front_header_button_color_before_scroll').val();
    var front_header_button_text_color_before_scroll = $('.front_header_button_text_color_before_scroll').val();
    var front_header_button_hover_color_before_scroll = $('.front_header_button_hover_color_before_scroll').val();
    var front_header_color_after_scroll = $('.front_header_color_after_scroll').val();
    var front_header_button_color_after_scroll = $('.front_header_button_color_after_scroll').val();
    var front_header_button_text_color_after_scroll = $('.front_header_button_text_color_after_scroll').val();
    var front_header_button_hover_color_after_scroll = $('.front_header_button_hover_color_after_scroll').val();
    var front_button_color = $('.front_button_color').val();
    var front_button_hover_color = $('.front_button_hover_color').val();
    var front_button_text_color = $('.front_button_text_color').val();
    var front_runner_bar_color = $('.front_runner_bar_color').val();
    var front_runner_bar_text_color = $('.front_runner_bar_text_color').val();
    var front_theme_text_color = $('.front_theme_text_color').val();
    var front_faq_before_hover_color = $('.front_faq_before_hover_color').val();
    var front_faq_after_hover_color = $('.front_faq_after_hover_color').val();
    var bottom_banner_before_footer = $('.bottom_banner_before_footer').val();
    var front_footer_color = $('.front_footer_color').val();
    var header_menu_bg_color_responsive = $('.header_menu_bg_color_responsive').val();
    

    var data = {
    	'theme[front_header_color_before_scroll]':front_header_color_before_scroll,
    	'theme[front_header_button_color_before_scroll]':front_header_button_color_before_scroll,
    	'theme[front_header_button_text_color_before_scroll]':front_header_button_text_color_before_scroll,
    	'theme[front_header_button_hover_color_before_scroll]':front_header_button_hover_color_before_scroll,
    	'theme[front_header_color_after_scroll]':front_header_color_after_scroll,
    	'theme[front_header_button_color_after_scroll]':front_header_button_color_after_scroll,
    	'theme[front_header_button_text_color_after_scroll]':front_header_button_text_color_after_scroll,
    	'theme[front_header_button_hover_color_after_scroll]':front_header_button_hover_color_after_scroll,
    	'theme[front_button_color]':front_button_color,
    	'theme[front_button_hover_color]':front_button_hover_color,
    	'theme[front_button_text_color]':front_button_text_color,
    	'theme[front_runner_bar_color]':front_runner_bar_color,
    	'theme[front_runner_bar_text_color]':front_runner_bar_text_color,
    	'theme[front_theme_text_color]':front_theme_text_color,
    	'theme[front_faq_before_hover_color]':front_faq_before_hover_color,
    	'theme[front_faq_after_hover_color]':front_faq_after_hover_color,
    	'theme[bottom_banner_before_footer]':bottom_banner_before_footer,
    	'theme[front_footer_color]':front_footer_color,
    	'theme[header_menu_bg_color_responsive]':header_menu_bg_color_responsive
    }

    $.ajax({
    	url:'<?= base_url('themes/themesetting/')  ?>',
        type:'POST',
        dataType:'json',
        cache:false,
        data:data,
        success:function(result){
        	
            $(".save-theme-settings").prop('disabled', false).html($(this).data('original-text') || 'Submit');
            $(".alert-dismissable").remove(); 
            if(result['location']){
                //window.location = result['location'];
            }

            if(result['success']){
                showPrintMessage(result['success'],'success');
                var body = $("html, body");
                body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
            }

            if(result['errors']){
                $.each(result['errors'], function(i,j){
                    $ele = $this.find('[name="'+ i +'"]');
                    if($ele){
                        $ele.parents(".form-group").addClass("has-error");
                        $ele.after("<span class='d-block text-danger'>"+ j +"</span>");
                    }
                });
            }
        },
    })
    return false;
});

$(".default-front-theme-setting").on("click", function(){
    var setting = $(this).val();
    var color = '';

    if (setting == "front_header_color_before_scroll") {
        color = "transparent";
        $("input[name='theme[front_header_color_before_scroll]']").val(color);
    }else if (setting == 'front_header_button_color_before_scroll') {
        color = "#4361ee";
        $("input[name='theme[front_header_button_color_before_scroll]']").val(color);
    }else if (setting == 'front_header_button_text_color_before_scroll') {
        color = "#ffffff";
        $("input[name='theme[front_header_button_text_color_before_scroll]']").val(color);
    }else if (setting == 'front_header_button_hover_color_before_scroll') {
        color = "#3a56d4";
        $("input[name='theme[front_header_button_hover_color_before_scroll]']").val(color);
    }else if (setting == 'front_header_color_after_scroll') {
        color = "#ffffff";
        $("input[name='theme[front_header_color_after_scroll]']").val(color);
    }else if (setting == 'front_header_button_color_after_scroll') {
        color = "#4361ee";
        $("input[name='theme[front_header_button_color_after_scroll]']").val(color);
    }else if (setting == 'front_header_button_text_color_after_scroll') {
        color = "#ffffff";
        $("input[name='theme[front_header_button_text_color_after_scroll]']").val(color);
    }else if (setting == 'front_header_button_hover_color_after_scroll') {
        color = "#f72585";
        $("input[name='theme[front_header_button_hover_color_after_scroll]']").val(color);
    }else if (setting == 'front_button_color') {
        color = "#4361ee";
        $("input[name='theme[front_button_color]']").val(color);
    }else if (setting == 'front_button_hover_color') {
        color = "#3a56d4";
        $("input[name='theme[front_button_hover_color]']").val(color);
    }else if (setting == 'front_button_text_color') {
        color = "#ffffff";
        $("input[name='theme[front_button_text_color]']").val(color);
    }else if (setting == 'front_runner_bar_color') {
        color = "#4361ee";
        $("input[name='theme[front_runner_bar_color]']").val(color);
    }else if (setting == 'front_runner_bar_text_color') {
        color = "#ffffff";
        $("input[name='theme[front_runner_bar_text_color]']").val(color);
    }else if (setting == 'front_theme_text_color') {
        color = "#4361ee";
        $("input[name='theme[front_theme_text_color]']").val(color);
    }else if (setting == 'front_faq_before_hover_color') {
        color = "#f8fafc";
        $("input[name='theme[front_faq_before_hover_color]']").val(color);
    }else if (setting == 'front_faq_after_hover_color') {
        color = "#4361ee";
        $("input[name='theme[front_faq_after_hover_color]']").val(color);
    }else if (setting == 'front_footer_color') {
        color = "#0f172a";
        $("input[name='theme[front_footer_color]']").val(color);
    }else if (setting == 'bottom_banner_before_footer') {
        color = "#4361ee";
        $("input[name='theme[bottom_banner_before_footer]']").val(color);
    }else if (setting == 'header_menu_bg_color_responsive') {
        color = "#0f172a";
        $("input[name='theme[header_menu_bg_color_responsive]']").val(color);
    }

 

    if(color != '') {
        $.ajax({
            url:base_url+'themes/default_front_theme_settings',
            type:'POST',
            dataType:'json',
            data:{'action':'default_front_theme_settings', setting:setting, color:color},
            success:function(json){
            },
        });
    }
});

$("#reset-all-theme-colors").on("click", function(){
    if(!confirm("<?= __('admin.reset_all_colors_confirm') ?>")) return;

    var btn = $(this);
    btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> ...');

    $.ajax({
        url: base_url + 'themes/reset_all_front_theme_colors',
        type: 'POST',
        dataType: 'json',
        data: {'action': 'reset_all_front_theme_colors'},
        success: function(json){
            location.reload();
        },
        error: function(){
            location.reload();
        }
    });
});

$(document).ready(function(){
    $.ajax({
        url:'<?= base_url("themes/set_default_front_theme_settings") ?>',
        type:'POST',
        dataType:'json',
        data:{'action':'set_default_front_theme_settings', 'setting_type':'theme'},
        success:function(json){

        },
    });
	
	changeLanguage();
});

function getSettingTabContent($language_id)
{
    $.ajax({
            url:'<?= base_url("themes/getSettingTabContent") ?>',
            type:'POST',
            dataType:'json',
            data:{'language_id':$language_id, 'setting_type':'theme'},
            beforeSend:function(){},
            complete:function(){},
            success:function(json){  
               if(json['html']){
                  $("#setting_content_html").html(json['html']);  
               } else {
                 
               } 
            },
       });
}
function changeLanguage()
{
	$language_id=$("#drpLanguage").val();
	getSettingTabContent($language_id);
}

$('#btn-import-full-demo').on('click', function() {
	var $btn = $(this);
	$btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...');
	$.ajax({
		url: '<?= base_url("themes/load_full_demo") ?>',
		type: 'POST',
		dataType: 'json',
		data: { language_id: $('#drpLanguage').val() || 1 },
		success: function(res) {
			if (res.already_loaded) {
				alert('<?= __('admin.demo_already_loaded') ?>');
			} else if (res.success) {
				window.location.reload();
			}
		},
		complete: function() {
			$btn.prop('disabled', false).html('<i class="bi bi-cloud-download me-1"></i><?= __('admin.import_demo_data') ?>');
		}
	});
});

$('#btn-clear-full-demo').on('click', function() {
	if (!confirm('<?= __('admin.clear_demo_data_confirm') ?>')) return;
	var $btn = $(this);
	$btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...');
	$.ajax({
		url: '<?= base_url("themes/clear_full_demo") ?>',
		type: 'POST',
		dataType: 'json',
		data: { language_id: $('#drpLanguage').val() || 1 },
		success: function(res) {
			if (res.success) {
				window.location.reload();
			}
		},
		complete: function() {
			$btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i><?= __('admin.clear_demo_data') ?>');
		}
	});
});

/* === Scrollable Tab Bar — Gradient fade + auto-scroll active tab + drag & wheel === */
$(function(){
    var wrapper = document.getElementById('mpaTabsWrapper');
    var tabsUl = wrapper ? wrapper.querySelector('.mp-admin-tabs') : null;
    if(!wrapper || !tabsUl) return;

    function updateScrollIndicators(){
        var sl = tabsUl.scrollLeft;
        var maxScroll = tabsUl.scrollWidth - tabsUl.clientWidth;
        if(sl > 8) { wrapper.classList.add('has-scroll-left'); } else { wrapper.classList.remove('has-scroll-left'); }
        if(sl < maxScroll - 8) { wrapper.classList.add('has-scroll-right'); } else { wrapper.classList.remove('has-scroll-right'); }
    }

    function scrollActiveIntoView(){
        var active = tabsUl.querySelector('.nav-link.active');
        if(!active) return;
        var li = active.closest('.nav-item');
        if(!li) return;
        var tabsRect = tabsUl.getBoundingClientRect();
        var liRect = li.getBoundingClientRect();
        if(liRect.left < tabsRect.left + 30) {
            tabsUl.scrollLeft -= (tabsRect.left + 30 - liRect.left + 40);
        } else if(liRect.right > tabsRect.right - 30) {
            tabsUl.scrollLeft += (liRect.right - tabsRect.right + 70);
        }
    }

    /* Mouse wheel horizontal scroll */
    tabsUl.addEventListener('wheel', function(e){
        if(Math.abs(e.deltaY) > Math.abs(e.deltaX)){
            e.preventDefault();
            tabsUl.scrollLeft += e.deltaY;
        }
    }, {passive: false});

    /* Drag-to-scroll with mouse */
    var isDragging = false, startX = 0, scrollStart = 0, hasMoved = false;
    tabsUl.addEventListener('mousedown', function(e){
        if(e.button !== 0) return;
        isDragging = true;
        hasMoved = false;
        startX = e.pageX;
        scrollStart = tabsUl.scrollLeft;
        tabsUl.style.cursor = 'grabbing';
        tabsUl.style.userSelect = 'none';
    });
    document.addEventListener('mousemove', function(e){
        if(!isDragging) return;
        var dx = e.pageX - startX;
        if(Math.abs(dx) > 3) hasMoved = true;
        tabsUl.scrollLeft = scrollStart - dx;
    });
    document.addEventListener('mouseup', function(){
        if(!isDragging) return;
        isDragging = false;
        tabsUl.style.cursor = '';
        tabsUl.style.userSelect = '';
    });
    tabsUl.addEventListener('click', function(e){
        if(hasMoved) { e.preventDefault(); e.stopPropagation(); }
    }, true);

    tabsUl.style.cursor = 'grab';

    /* Arrow button navigation */
    var arrLeft = document.getElementById('mpaTabArrLeft');
    var arrRight = document.getElementById('mpaTabArrRight');
    if(arrLeft) arrLeft.addEventListener('click', function(){ tabsUl.scrollLeft -= 200; });
    if(arrRight) arrRight.addEventListener('click', function(){ tabsUl.scrollLeft += 200; });

    tabsUl.addEventListener('scroll', updateScrollIndicators);
    window.addEventListener('resize', updateScrollIndicators);

    updateScrollIndicators();
    setTimeout(scrollActiveIntoView, 100);

    $(tabsUl).on('shown.bs.tab', '.nav-link', function(){
        setTimeout(function(){ scrollActiveIntoView(); updateScrollIndicators(); }, 50);
    });
});
</script>
</div><!-- /.container-fluid -->