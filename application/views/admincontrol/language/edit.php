<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">

            <!-- Header -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?= base_url("admincontrol/language") ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i><?= __("admin.back_to_languages") ?>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-<?= isset($lang) ? 'edit' : 'plus-circle' ?> text-primary me-2"></i>
                        <?= isset($lang) ? __("admin.edit_language") : __("admin.add_language") ?>
                    </h4>
                    <p class="text-muted mb-0 small"><?= isset($lang) ? __("admin.modify_language_settings") : __("admin.create_new_language") ?></p>
                </div>
            </div>

            <form id="language-form" enctype="multipart/form-data" action="<?= base_url("admincontrol/update_language") ?>" method="POST">
                <input type="hidden" name="id" value="<?= isset($lang) ? $lang['id'] : '0' ?>">

                <div class="row g-4">

                    <!-- LEFT COLUMN -->
                    <div class="col-lg-5 d-flex flex-column gap-4">

                        <!-- Live Preview Card -->
                        <div class="lang-preview-card shadow">
                            <div class="section-label lang-preview-card-label"><i class="fas fa-eye me-1"></i><?= __('admin.preview') ?></div>
                            <?php
                                $pf = '';
                                if (isset($lang['flag']) && !empty($lang['flag'])) {
                                    $pf = (strncmp($lang['flag'],'./',2)===0) ? substr($lang['flag'],2) : $lang['flag'];
                                }
                            ?>
                            <div id="previewContent" class="lang-preview-content">
                            <?php if(isset($lang['name'])): ?>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="lang-preview-flag-wrap" id="previewFlagWrap">
                                        <?php if(!empty($pf)): ?>
                                            <img id="previewFlagImg" src="<?= base_url($pf) ?>" alt="">
                                        <?php else: ?>
                                            <i class="fas fa-globe fs-4 lang-preview-globe-icon"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div id="previewName" class="lang-preview-name"><?= $lang['name'] ?></div>
                                        <div id="previewCode" class="lang-preview-code mt-1"></div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div id="previewEmpty" class="lang-preview-empty">
                                    <div class="lang-preview-empty-icon"><i class="fas fa-globe"></i></div>
                                    <div>
                                        <div class="lang-preview-empty-text fw-semibold"><?= __('admin.select_a_language') ?></div>
                                        <div class="lang-preview-empty-text small lang-preview-empty-hint"><?= __('admin.preview_will_appear_here') ?></div>
                                    </div>
                                </div>
                                <div id="previewFilled" class="d-flex align-items-center gap-3 d-none">
                                    <div class="lang-preview-flag-wrap" id="previewFlagWrap"></div>
                                    <div>
                                        <div id="previewName" class="lang-preview-name"></div>
                                        <div id="previewCode" class="lang-preview-code mt-1"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            </div>
                        </div>

                        <!-- Language Name -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="section-label"><i class="fas fa-language me-1"></i><?= __("admin.language_settings") ?></div>

                                <label class="form-label fw-semibold mb-2">
                                    <?= __("admin.language_name") ?> <span class="text-danger">*</span>
                                </label>

                                <?php if(isset($lang) && isset($lang['name'])): ?>
                                    <input name="name" value="<?= $lang['name'] ?>" class="form-control bg-light fw-semibold" readonly/>
                                    <div class="form-text"><i class="fas fa-lock me-1 text-muted"></i><?= __("admin.language_name_cannot_be_changed") ?></div>
                                <?php else: ?>
                                    <?php
                                        $existing_languages = $this->db->query("SELECT name FROM language")->result_array();
                                        $existing_names = array_column($existing_languages, 'name');
                                        $total_count = count($languages);
                                        $available_count = 0;
                                        foreach ($languages as $lang_name) { if(!in_array($lang_name, $existing_names)) $available_count++; }
                                        $existing_count = $total_count - $available_count;
                                        static $langToCountryMap = [
                                            'en'=>'us','pt'=>'pt','es'=>'es','fr'=>'fr','de'=>'de','it'=>'it',
                                            'nl'=>'nl','ru'=>'ru','bg'=>'bg','ar'=>'sa','zh'=>'cn','ja'=>'jp',
                                            'ko'=>'kr','pl'=>'pl','ro'=>'ro','sk'=>'sk','cs'=>'cz','tr'=>'tr',
                                            'id'=>'id','th'=>'th','vi'=>'vn','hr'=>'hr','uk'=>'ua','sv'=>'se',
                                            'da'=>'dk','fi'=>'fi','nb'=>'no','no'=>'no','el'=>'gr','he'=>'il',
                                            'hu'=>'hu','ms'=>'my','fa'=>'ir','af'=>'za','sq'=>'al','lt'=>'lt',
                                            'lv'=>'lv','et'=>'ee','sl'=>'si','sr'=>'rs','mk'=>'mk','be'=>'by',
                                            'kk'=>'kz','az'=>'az','ka'=>'ge','hy'=>'am','sw'=>'ke','am'=>'et',
                                            'tl'=>'ph','ta'=>'in','bn'=>'bd','ur'=>'pk','hi'=>'in',
                                        ];
                                    ?>
                                    <select name="name" class="d-none" id="realLanguageSelect" required>
                                        <option value="" disabled selected></option>
                                        <?php foreach ($languages as $key => $value):
                                            $is_existing = in_array($value, $existing_names); ?>
                                            <option value="<?= $value ?>" <?= $is_existing ? 'disabled' : '' ?>><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <!-- Searchable dropdown -->
                                    <div class="dropdown position-relative mb-2">
                                        <div class="lang-select-trigger" id="languageDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" tabindex="0" role="button">
                                            <div id="selectedLanguageText" class="lang-select-value is-placeholder">
                                                <i class="fas fa-globe lang-select-globe-icon"></i>
                                                <span><?= __('admin.select_language_from_list') ?></span>
                                            </div>
                                            <i class="fas fa-chevron-down lang-select-chevron"></i>
                                        </div>
                                        <ul class="dropdown-menu w-100 shadow border-0 p-0 overflow-auto lang-dropdown-list">
                                            <li class="sticky-top bg-white border-bottom px-2 py-2">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                                    <input type="text" class="form-control border-start-0 ps-0" id="languageSearchInput" placeholder="<?= __('admin.search') ?>..." autocomplete="off">
                                                </div>
                                            </li>
                                            <?php foreach ($languages as $key => $value):
                                                $is_existing = in_array($value, $existing_names);
                                                $countryIso  = $langToCountryMap[$key] ?? null;
                                                $lang_flag_url = '';
                                                if ($countryIso && isset($flags_code[$countryIso])) {
                                                    $fv = $flags_code[$countryIso];
                                                    $lang_flag_url = base_url((strncmp($fv,'./',2)===0) ? substr($fv,2) : $fv);
                                                }
                                            ?>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3 <?= $is_existing ? 'disabled text-muted' : '' ?>"
                                                   href="#"
                                                   data-value="<?= $value ?>"
                                                   data-code="<?= $key ?>"
                                                   data-flag="<?= $lang_flag_url ?>"
                                                   data-disabled="<?= $is_existing ? 'true' : 'false' ?>"
                                                   <?= $is_existing ? 'class="pe-none"' : '' ?>>
                                                    <?php if($lang_flag_url): ?>
                                                        <img src="<?= $lang_flag_url ?>" width="26" height="18" class="rounded flex-shrink-0 <?= $is_existing ? 'opacity-50' : '' ?>" alt="">
                                                    <?php else: ?>
                                                        <span class="lang-dropdown-flag-placeholder"><i class="fas fa-globe text-muted"></i></span>
                                                    <?php endif; ?>
                                                    <span class="<?= $is_existing ? 'text-decoration-line-through' : 'fw-medium' ?> flex-grow-1 small"><?= $value ?></span>
                                                    <?php if($is_existing): ?>
                                                        <span class="badge bg-secondary lang-badge-xs"><?= __('admin.already_added') ?></span>
                                                    <?php else: ?>
                                                        <span class="font-monospace text-muted lang-code-label"><?= strtoupper($key) ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fas fa-check me-1"></i><?= $available_count ?> <?= __("admin.available") ?></span>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="fas fa-ban me-1"></i><?= $existing_count ?> <?= __("admin.already_added") ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="section-label"><i class="fas fa-sliders-h me-1"></i><?= __("admin.status") ?> &amp; <?= __("admin.settings") ?></div>
                                <div class="d-flex flex-column gap-3">

                                    <label class="setting-card d-flex align-items-center gap-3 m-0 <?= (isset($lang) && $lang['status']=='1') ? 'active' : '' ?>">
                                        <div class="setting-icon-circle green">
                                            <i class="fas fa-toggle-on text-success fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold"><?= __("admin.active") ?></div>
                                            <div class="text-muted small"><?= __("admin.enable_disable_language") ?></div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input setting-switch" type="checkbox" role="switch" id="statusSwitch" name="status" value="1"
                                                   <?= (isset($lang) && $lang['status']=='1') ? 'checked' : '' ?>>
                                        </div>
                                    </label>

                                    <label class="setting-card d-flex align-items-center gap-3 m-0 <?= (isset($lang) && $lang['is_default']=='1') ? 'active' : '' ?>">
                                        <div class="setting-icon-circle yellow">
                                            <i class="fas fa-star text-warning fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold"><?= __("admin.default_language") ?></div>
                                            <div class="text-muted small"><?= __("admin.default_language_description") ?></div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input setting-switch" type="checkbox" role="switch" id="defaultSwitch" name="is_default" value="1"
                                                   <?= (isset($lang) && $lang['is_default']=='1') ? 'checked' : '' ?>>
                                        </div>
                                    </label>

                                    <label class="setting-card d-flex align-items-center gap-3 m-0 <?= (isset($lang) && $lang['is_rtl']=='1') ? 'active' : '' ?>">
                                        <div class="setting-icon-circle red">
                                            <i class="fas fa-align-right text-danger fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold"><?= __("admin.right_to_left") ?></div>
                                            <div class="text-muted small"><?= __("admin.rtl_language_description") ?></div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input setting-switch" type="checkbox" role="switch" id="rtlSwitch" name="is_rtl" value="1"
                                                   <?= (isset($lang) && $lang['is_rtl']=='1') ? 'checked' : '' ?>>
                                        </div>
                                    </label>

                                </div>
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-semibold" id="saveBtn">
                                <i class="fas fa-save me-2"></i><?= __("admin.save_changes") ?>
                            </button>
                            <a href="<?= base_url("admincontrol/language") ?>" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-1"></i><?= __("admin.cancel") ?>
                            </a>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN — Flag Selector -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3 px-4">
                                <div class="section-label mb-0"><i class="fas fa-flag me-1 text-primary"></i><?= __("admin.language_flag") ?></div>
                                <div class="input-group input-group-sm lang-flag-search-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="flagSearchInput" placeholder="<?= __('admin.search') ?> <?= __('admin.country') ?>...">
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <?php
                                    $selected_flag = null;
                                    if (isset($lang['flag'])) {
                                        $selected_flag = (strncmp($lang['flag'], './', 2) === 0) ? substr($lang['flag'], 2) : $lang['flag'];
                                    }
                                ?>
                                <div id="flagGrid">
                                    <?php foreach ($flags_code as $key => $value):
                                        if (empty($value)) continue;
                                        $cmp = (strncmp($value,'./',2)===0) ? substr($value,2) : $value;
                                        if (empty($cmp)) continue;
                                    ?>
                                    <div class="flag-option" data-country="<?= strtolower($key) ?>">
                                        <input data-flag_code="<?= $key ?>"
                                               <?= $selected_flag === $cmp ? 'checked' : '' ?>
                                               type="radio" name="flag" value="<?= $cmp ?>"
                                               id="flag_<?= $key ?>" class="btn-check flag-radio">
                                        <label class="flag-btn" for="flag_<?= $key ?>" title="<?= ucfirst($key) ?>">
                                            <img src="<?= base_url($cmp) ?>" alt="<?= $key ?>">
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                    <div id="flagNoResults" class="flag-no-results d-none">
                                        <i class="fas fa-search me-2"></i><?= __('admin.no_results') ?>
                                    </div>
                                </div>

                                <!-- Selected flag bar -->
                                <div id="selectedFlagBar" class="mt-3 lang-flag-selected-bar <?= $selected_flag ? '' : 'd-none' ?>">
                                    <i class="fas fa-check-circle text-primary"></i>
                                    <span class="small text-muted"><?= __('admin.selected') ?>:</span>
                                    <?php if($selected_flag): ?>
                                        <img id="selectedFlagPreview" src="<?= base_url($selected_flag) ?>" width="32" height="22" class="rounded shadow-sm" alt="">
                                    <?php else: ?>
                                        <img id="selectedFlagPreview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="32" height="22" class="rounded shadow-sm d-none" alt="">
                                    <?php endif; ?>
                                    <span id="selectedFlagName" class="fw-semibold small text-primary"><?= $selected_flag ? strtoupper(pathinfo($selected_flag, PATHINFO_FILENAME)) : '' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /row -->
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let languagesData = null;
    let countriesData = null;
    const flagsData   = <?= json_encode($flags_code) ?>;
    const langToCountry = {
        'en':'us','pt':'pt','es':'es','fr':'fr','de':'de','it':'it','nl':'nl','ru':'ru','bg':'bg',
        'ar':'sa','zh':'cn','ja':'jp','ko':'kr','pl':'pl','ro':'ro','sk':'sk','cs':'cz','tr':'tr',
        'id':'id','th':'th','vi':'vn','hr':'hr','uk':'ua','sv':'se','da':'dk','fi':'fi','nb':'no',
        'no':'no','el':'gr','he':'il','hu':'hu','ms':'my','fa':'ir','af':'za','sq':'al','lt':'lt',
        'lv':'lv','et':'ee','sl':'si','sr':'rs','mk':'mk','be':'by','kk':'kz','az':'az','ka':'ge',
        'hy':'am','sw':'ke','am':'et','tl':'ph','ta':'in','bn':'bd','ur':'pk','hi':'in',
    };

    Promise.all([
        fetch('<?= base_url('assets/data/languages.json') ?>').then(r => r.json()),
        fetch('<?= base_url('assets/data/countries_with_languages.json') ?>').then(r => r.json())
    ]).then(([langs, countries]) => {
        languagesData = langs;
        countriesData = countries;
        const inp = document.querySelector('input[name="name"][readonly]');
        if (inp && inp.value) filterFlagsByLanguage(inp.value);
    });

    // ── Dropdown trigger open/close class ────────────────────────────────────
    const dropBtn = document.getElementById('languageDropdownBtn');
    if (dropBtn) {
        dropBtn.addEventListener('show.bs.dropdown',  () => dropBtn.classList.add('show'));
        dropBtn.addEventListener('hide.bs.dropdown',  () => dropBtn.classList.remove('show'));
    }

    // ── Preview helpers ──────────────────────────────────────────────────────
    function updatePreview(name, code, flagUrl) {
        const nm     = document.getElementById('previewName');
        const cd     = document.getElementById('previewCode');
        const wrap   = document.getElementById('previewFlagWrap');
        const empty  = document.getElementById('previewEmpty');
        const filled = document.getElementById('previewFilled');

        if (nm) nm.textContent = name || '';
        if (cd) cd.textContent = code ? code.toUpperCase() : '';

        if (wrap) {
            wrap.innerHTML = flagUrl
                ? '<img src="' + flagUrl + '" alt="">'
                : '<i class="fas fa-globe lang-preview-globe-icon fs-4"></i>';
        }
        if (empty)  empty.classList.toggle('d-none', !!name);
        if (filled) filled.classList.toggle('d-none', !name);
    }

    // ── Language dropdown ────────────────────────────────────────────────────
    document.querySelectorAll('.dropdown-item[data-value]').forEach(function (item) {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            if (this.dataset.disabled === 'true') return;
            const val  = this.dataset.value;
            const code = this.dataset.code;
            const flag = this.dataset.flag;
            const rs   = document.getElementById('realLanguageSelect');
            const st   = document.getElementById('selectedLanguageText');
            if (rs) rs.value = val;
            if (st) {
                st.classList.remove('is-placeholder');
                st.innerHTML = (flag
                    ? '<img src="' + flag + '" class="lang-select-img rounded" alt=""><strong class="ms-1">' + val + '</strong>'
                    : '<i class="fas fa-globe lang-select-globe-icon"></i><strong class="ms-1">' + val + '</strong>')
                    + ' <span class="font-monospace text-muted ms-1 lang-code-selected">' + code.toUpperCase() + '</span>';
            }
            updatePreview(val, code, flag);
            filterFlagsByLanguage(val);
            bootstrap.Dropdown.getInstance(dropBtn)?.hide();
        });
    });

    const searchInput = document.getElementById('languageSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.dropdown-item[data-value]').forEach(function (el) {
                const li = el.closest('li');
                if (li) li.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
        searchInput.addEventListener('click', e => e.stopPropagation());
        dropBtn?.addEventListener('show.bs.dropdown', function () {
            setTimeout(() => {
                searchInput.value = '';
                searchInput.focus();
                document.querySelectorAll('.dropdown-item[data-value]').forEach(el => { const li = el.closest('li'); if (li) li.style.display = ''; });
            }, 50);
        });
    }

    // ── Flag grid search ─────────────────────────────────────────────────────
    document.getElementById('flagSearchInput')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        let visible = 0;
        document.querySelectorAll('.flag-option').forEach(function (el) {
            const show = (q === '' || el.dataset.country.includes(q));
            el.style.display = show ? 'inline-block' : 'none';
            if (show) visible++;
        });
        const noRes = document.getElementById('flagNoResults');
        if (noRes) noRes.classList.toggle('d-none', visible > 0);
    });

    // ── Flag selection ───────────────────────────────────────────────────────
    document.querySelectorAll('.flag-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (!this.checked) return;
            const src  = this.nextElementSibling?.querySelector('img')?.src || '';
            if (!src) return;
            const code = this.dataset.flag_code;
            const bar  = document.getElementById('selectedFlagBar');
            const prev = document.getElementById('selectedFlagPreview');
            const name = document.getElementById('selectedFlagName');
            if (bar)  bar.classList.remove('d-none');
            if (prev) { prev.src = src; prev.classList.remove('d-none'); }
            if (name) name.textContent = code.toUpperCase();
            const wrap = document.getElementById('previewFlagWrap');
            if (wrap) wrap.innerHTML = '<img src="' + src + '" alt="">';
        });
    });

    // ── Settings card active state ───────────────────────────────────────────
    document.querySelectorAll('.setting-switch').forEach(function (sw) {
        sw.addEventListener('change', function () {
            this.closest('.setting-card')?.classList.toggle('active', this.checked);
        });
    });

    // ── Filter flags by language (show only related country flags) ────────────
    function filterFlagsByLanguage(langName) {
        if (!langName || !languagesData || !countriesData) return;
        const langCode = Object.keys(languagesData).find(k => languagesData[k] === langName);
        if (!langCode) return;
        const matches = countriesData.filter(c => c.languages.split(',').includes(langCode));
        document.querySelectorAll('.flag-option').forEach(el => {
            el.style.display = matches.length === 0 ? 'inline-block' : 'none';
        });
        matches.forEach(function (c, i) {
            const el = document.querySelector('.flag-option input[data-flag_code="' + c.iso_code.toLowerCase() + '"]');
            if (!el) return;
            const wrap = el.closest('.flag-option');
            if (wrap) {
                wrap.style.display = 'inline-block';
                if (i === 0 && !document.querySelector('.flag-radio:checked')) el.click();
            }
        });
        const grid = document.getElementById('flagGrid');
        if (grid) grid.scrollTop = 0;
    }

    // ── Init for edit mode (pre-selected language) ───────────────────────────
    const editInput = document.querySelector('input[name="name"][readonly]');
    if (editInput && editInput.value) {
        const tryInit = setInterval(function () {
            if (!languagesData) return;
            clearInterval(tryInit);
            const code = Object.keys(languagesData).find(k => languagesData[k] === editInput.value) || '';
            const c = langToCountry[code];
            let flagUrl = '';
            if (c && flagsData[c]) {
                const fp = flagsData[c];
                flagUrl = '<?= base_url() ?>' + (fp.startsWith('./') ? fp.slice(2) : fp);
            }
            updatePreview(editInput.value, code, flagUrl);
        }, 100);
    }

    // ── Form submit ──────────────────────────────────────────────────────────
    document.getElementById('language-form')?.addEventListener('submit', function () {
        const btn = document.getElementById('saveBtn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><?= __("admin.saving") ?>'; }
    });
});
</script>
