<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-globe me-3 fs-4"></i>
                        <h4 class="card-title mb-0 text-white"><?= __("admin.language") ?></h4>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-warning text-dark" id="translateAllMissingBtn">
                            <i class="fas fa-magic me-2"></i><?= __("admin.translate_all_missing") ?>
                        </button>
                        <a href="<?= base_url('admincontrol/update_user_langauges/all') ?>" class="btn btn-secondary">
                            <i class="fas fa-sync-alt me-2"></i><?= __("admin.update_languages") ?>
                        </a>
                        <a href="<?= base_url('admincontrol/translation_edit/0') ?>" class="btn btn-light text-primary add-new">
                            <i class="fas fa-plus me-2"></i><?= __("admin.add_new") ?>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4 text-nowrap w-auto">
                                        <i class="fas fa-flag text-muted"></i>
                                    </th>
                                    <th class="border-0 text-nowrap"><?= __("admin.name") ?></th>
                                    <th class="border-0 text-center text-nowrap" style="min-width:200px">
                                        <i class="fas fa-chart-bar text-info me-1"></i><?= __("admin.translation_coverage") ?>
                                    </th>
                                    <th class="border-0 text-center text-nowrap w-auto">
                                        <i class="fas fa-align-right me-2 text-info"></i><?= __("admin.direction") ?>
                                    </th>
                                    <th class="border-0 text-center text-nowrap w-auto"><?= __("admin.is_default") ?></th>
                                    <th class="border-0 text-center text-nowrap w-auto"><?= __("admin.status") ?></th>
                                    <th class="border-0 text-end pe-4 text-nowrap w-auto"><?= __("admin.actions") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Build code lookup: name => code, from the same languages.json used for merging
                            $_all_lang_map = [];
                            $_lj = FCPATH.'assets/data/languages.json';
                            if (file_exists($_lj)) {
                                $_all_lang_map = json_decode(file_get_contents($_lj), true) ?: [];
                            }
                            ?>
                            <?php foreach($language as $lang){ ?>
                            <?php $_lang_code = strtoupper(array_search($lang['name'], $_all_lang_map) ?: $lang['id']); ?>
                                <tr class="align-middle">
                                    <td class="ps-4">
                                        <img src="<?= base_url($lang['flag']) ?>" class="rounded" alt="<?= $lang['name'] ?>" onerror="this.onerror=null;this.style.display='none'">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold"><?= $lang['name'] ?></span>
                                            <span class="badge bg-secondary bg-opacity-75 text-white font-monospace" style="font-size:.7rem;letter-spacing:.5px"><?= htmlspecialchars($_lang_code) ?></span>
                                            <?php if($lang['is_default']){ ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-star me-1"></i><?= __('admin.default') ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <?php
                                        $total_keys      = max(1, $language_count['all']);
                                        $missing_keys    = (int)$lang['count']['missing'];
                                        $translated_keys = $total_keys - $missing_keys;
                                        $coverage_pct    = round(($translated_keys / $total_keys) * 100);
                                        $bar_class       = $coverage_pct == 100 ? 'bg-success' : ($coverage_pct >= 80 ? 'bg-info' : ($coverage_pct >= 50 ? 'bg-warning' : 'bg-danger'));
                                        $is_english      = ($lang['id'] == 1);
                                    ?>
                                    <td class="text-center" style="min-width:200px">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <div class="progress w-100" style="height:8px" title="<?= $is_english ? $total_keys : $translated_keys ?> / <?= $total_keys ?>">
                                                <div class="progress-bar bg-success" role="progressbar" style="width:<?= $is_english ? 100 : $coverage_pct ?>%" aria-valuenow="<?= $is_english ? 100 : $coverage_pct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex gap-1 align-items-center">
                                                <?php if(!$is_english && $missing_keys > 0): ?>
                                                    <span class="badge bg-warning text-dark rounded-pill"><?= $missing_keys ?> <?= __('admin.missing') ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-success rounded-pill"><i class="fas fa-check me-1"></i><?= __('admin.up_to_date') ?></span>
                                                <?php endif; ?>
                                                <small class="text-muted fw-semibold"><?= $is_english ? 100 : $coverage_pct ?>%</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if(isset($lang['rtl']) && $lang['rtl'] == '1'){ ?>
                                            <span class="badge bg-info text-white">
                                                <i class="fas fa-align-right me-1"></i><?= __('admin.rtl') ?>
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-light text-muted">
                                                <i class="fas fa-align-left me-1"></i><?= __('admin.ltr') ?>
                                            </span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input btn_default_lang btn_lang_toggle" type="checkbox" 
                                                   id="switchDefaultLang<?= $lang['id'] ?>" 
                                                   <?= ($lang['is_default'] == 1) ? "checked" : ""?> 
                                                   data-lang_id="<?= $lang['id'] ?>" 
                                                   data-column="is_default">
                                            <label class="form-check-label" for="switchDefaultLang<?= $lang['id'] ?>"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input btn_lang_toggle" type="checkbox" 
                                                   id="switchStatusLang<?= $lang['id'] ?>" 
                                                   <?= ($lang['status'] == 1) ? "checked" : ""?> 
                                                   data-lang_id="<?= $lang['id'] ?>" 
                                                   data-column="status">
                                            <label class="form-check-label" for="switchStatusLang<?= $lang['id'] ?>"></label>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-outline-info btn-sm open-details" title="<?= __('admin.import_export') ?>">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <a href="<?= base_url('admincontrol/translation_edit/'.$lang['id']) ?>" 
                                               class="btn btn-outline-primary btn-sm edit-button" 
                                               id="<?= $lang['id'] ?>" 
                                               title="<?= __("admin.edit") ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if($lang['id'] != 1){ ?>
                                                <a class="btn btn-outline-secondary btn-sm" 
                                                   href="<?= base_url('admincontrol/translation/'.$lang['id']) ?>" 
                                                   title="<?= __("admin.translation") ?>">
                                                    <i class="fas fa-language"></i>
                                                </a>
                                                <?php if($missing_keys > 0): ?>
                                                    <button class="btn btn-outline-warning btn-sm btn-translate-lang"
                                                            data-lang_id="<?= $lang['id'] ?>"
                                                            data-lang_name="<?= htmlspecialchars($lang['name']) ?>"
                                                            data-missing="<?= $missing_keys ?>"
                                                            title="<?= __('admin.auto_translate_missing') ?>">
                                                        <i class="fas fa-magic"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php } ?>
                                            <?php if (ENVIRONMENT !== 'demo'): ?>
                                                <?php if($lang['is_default'] == '0' && $lang['id'] != 1){ ?>
                                                    <button class="btn btn-outline-danger btn-sm detele-button" 
                                                            id="<?= $lang['id'] ?>" 
                                                            title="<?= __("admin.delete") ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php } ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="details-tr d-none">
                                    <td colspan="6" class="p-0">
                                        <div class="bg-light border-top">
                                            <div class="p-4">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card border-0 bg-white shadow-sm">
                                                            <div class="card-body text-center">
                                                                <h6 class="card-title text-primary mb-3">
                                                    <i class="fas fa-download me-2"></i><?= __('admin.export_language') ?>
                                                </h6>
                                                <p class="text-muted mb-3"><?= __('admin.want_to_export_language_file') ?></p>
                                                <a href="<?= base_url("admincontrol/language_export/".$lang['id']) ?>" 
                                                   target="_blank" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-file-export me-2"></i><?= __('admin.export_language') ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($lang['id'] != 1){ ?>
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-white shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title text-success mb-3">
                                                        <i class="fas fa-upload me-2"></i><?= __('admin.import_language') ?>
                                                    </h6>
                                                    <form class="form-language">
                                                        <div class="lang-message mb-3"></div>
                                                        <input class="d-none" data-lang_file="<?= $lang['id'] ?>" type="file" name="file">
                                                        <input type="hidden" name="id" value="<?= $lang['id'] ?>">
                                                    </form>
                                                    <div class="d-grid gap-2">
                                                        <button type="button" 
                                                                data-lang_id="<?= $lang['id'] ?>" 
                                                                id="language_xls_upload_btn" 
                                                                class="btn btn-success">
                                                            <i class="fas fa-file-excel me-2"></i><?= __('admin.import_excel_file') ?>
                                                        </button>
                                                        <button type="button" 
                                                                id="language_zip_upload_btn" 
                                                                class="btn btn-info">
                                                            <i class="fas fa-file-archive me-2"></i><?= __('admin.import_language_package') ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-md-6">
                                            <div class="alert alert-warning border-0 shadow-sm">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <?= __('admin.you_can_not_import_main_language') ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
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
</div>

<div class="d-none">
    <form id="language_zip_upload_form" action="<?= base_url("admincontrol/language_zip_upload") ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="file" id="language_zip_upload_input">
    </form>
</div>

<!-- Auto-Translate Modal -->
<div class="modal fade" id="translateModal" tabindex="-1" aria-labelledby="translateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="translateModalLabel">
                    <i class="fas fa-magic me-2 text-warning"></i><span id="translateModalTitle"><?= __('admin.auto_translate_missing') ?></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Key progress bar (hidden until translation starts) -->
                <div id="translateKeyBar" class="d-none bg-dark border-bottom border-secondary px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="text-warning fw-semibold small" id="translateCurrentLangLabel"></span>
                        <span class="text-white font-monospace small">
                            <span class="text-muted me-1" style="font-size:.72rem">TOTAL:</span>
                            <span id="translateKeysDone">0</span>
                            <span class="text-muted mx-1">/</span>
                            <span id="translateKeysTotal">0</span>
                            <span class="text-muted ms-1" style="font-size:.72rem"><?= strtoupper(__('admin.keys_translated')) ?></span>
                        </span>
                    </div>
                    <div class="progress" style="height:5px;border-radius:3px;background:rgba(255,255,255,.1)">
                        <div id="translateKeyProgress" class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width:0%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted" id="translateFileLabel"></small>
                        <small class="text-muted" id="translateFilePct"></small>
                    </div>
                </div>
                <div id="translateConsole" class="bg-dark text-light p-4 font-monospace rounded-0" style="min-height:200px;max-height:360px;overflow-y:auto;font-size:.85rem;line-height:1.6">
                    <span style="color:#adb5bd"><?= __('admin.click_translate_to_start') ?></span>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary bg-light">
                <div id="translateProgress" class="d-none me-auto d-flex align-items-center gap-2">
                    <div class="spinner-border spinner-border-sm text-warning"></div>
                    <span class="text-muted small" id="translateProgressText"><?= __('admin.translating_please_wait') ?></span>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
                <button type="button" class="btn btn-warning" id="translateStartBtn">
                    <i class="fas fa-magic me-2"></i><?= __('admin.translate_btn') ?>
                </button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).on('click', '#language_xls_upload_btn', function(){
        $('input[data-lang_file="'+$(this).data('lang_id')+'"]').click();
    });

    $(document).on('change', 'input[type="file"][data-lang_file]', function(){
        $(this).closest('form').submit();
    });

    $(document).on('click', '#language_zip_upload_btn', function(){
        $('#language_zip_upload_input').click();
    });

    $(document).on('change', '#language_zip_upload_input', function(){
        $('#language_zip_upload_form').submit();
    });

    $(document).on('change', ".btn_lang_toggle", function(){
        let skip_change = false;
        let id = $(this).data('lang_id');
        let column = $(this).data('column');
        let status = this.checked ? 1 : 0;

        if (column == 'is_default' && !status) {
            Swal.fire('Warning', '<?= __('admin.please_select_another_language_as_default') ?>', 'warning');
            this.checked = !this.checked;
            skip_change = true;
        } else if (column == 'is_default') {
            if(!$('.btn_lang_toggle[data-lang_id="'+id+'"][data-column="status"]').prop('checked')) {
                Swal.fire('Warning', '<?= __('admin.inactive_language_can_not_be_set_as_default') ?>', 'warning');
                this.checked = !this.checked;
                skip_change = true;
            } else {
                $('.btn_default_lang').prop('checked', false);
            }
        } else {
            if($('.btn_lang_toggle[data-lang_id="'+id+'"][data-column="is_default"]').prop('checked')) {
                Swal.fire('Warning', '<?= __('admin.default_language_can_not_be_set_as_inactive') ?>', 'warning');
                this.checked = !this.checked;
                skip_change = true;
            }
        }

        if(!skip_change) {
            if (status && column == 'is_default') { 
                $('.default-badge').remove();
                $(this).closest('tr').find('td:nth-child(2) .badge').remove();
                $(this).closest('tr').find('td:nth-child(2)').append('<span class="badge bg-success ms-2"><i class="fas fa-star me-1"></i><?= __('admin.default') ?></span>');
            }

            $.ajax({
                url: "<?= base_url('admincontrol/lang_status_toggle')?>",
                type: "POST",
                dataType: "json",
                data: {
                    id:id,
                    status:status,
                    column:column
                },
                success: function (response) {    
                    if(response.reload) {
                        window.location.reload();
                    }
                    if(response.status) {
                        $('.notification-list.language').html(response.languages);
                    }
                }
            });
        }
    });

    $(".open-details").on('click', function(){
        $tr = $(this).parents("tr").next(".details-tr");
        if($tr.hasClass('d-none')){
            $tr.removeClass('d-none').hide().fadeIn(300);
        } else {
            $tr.fadeOut(300, function(){
                $(this).addClass('d-none');
            });
        }
    });

    $(".detele-button").on('click', function(){
        if(!confirm('<?= __('admin.are_you_sure') ?>')) return false;

        $this = $(this);
        $.ajax({
            url: '<?= base_url("admincontrol/delete_update_language") ?>',
            type: 'POST',
            dataType: 'json',
            data: {id: $this.attr("id")},
            beforeSend: function(){
                $this.prop("disabled", true);
            },
            complete: function(){
                $this.prop("disabled", false);
            },
            success: function(json){
                showToast('<?= __('admin.success') ?>', '<?= __('admin.language_deleted_successfully') ?>', 'success', 3000);
                $this.closest('tr').fadeOut(300, function() {
                    $(this).remove();
                    reindexTable();
                });
            },
        });
    });

    function reindexTable() {
        $('table tbody tr:not(.details-tr)').each(function(index) {
            $(this).find('.btn_lang_toggle').each(function() {
                let oldId = $(this).attr('id');
                let newId = oldId.replace(/\d+$/, index + 1);
                $(this).attr('id', newId);
                $(this).next('label').attr('for', newId);
            });
        });
    }

    // ── Auto-Translate Logic ──────────────────────────────────────────────────
    let _translateLangId   = null;
    let _translateLangName = null;
    let _translateQueue    = [];
    let _translateQueueIdx = 0;

    // Open modal for a single language row
    $(document).on('click', '.btn-translate-lang', function () {
        _translateLangId   = $(this).data('lang_id');
        _translateLangName = $(this).data('lang_name');
        _translateQueue    = [];
        $('#translateModalTitle').text('<?= __('admin.auto_translate_missing') ?>: ' + _translateLangName);
        resetTranslateModal();
        $('#translateModal').modal('show');
    });

    // "Translate All Missing" button — queue all languages with missing > 0
    $('#translateAllMissingBtn').on('click', function () {
        const langs = [];
        $('.btn-translate-lang').each(function () {
            langs.push({ id: $(this).data('lang_id'), name: $(this).data('lang_name'), missing: $(this).data('missing') });
        });
        if (langs.length === 0) {
            showToast('<?= __('admin.success') ?>', '<?= __('admin.translation_already_up_to_date') ?>', 'success', 3000);
            return;
        }
        _translateLangId   = null;
        _translateQueue    = langs;
        _translateQueueIdx = 0;
        $('#translateModalTitle').text('<?= __('admin.translate_all_missing') ?> (' + langs.length + ' <?= __('admin.languages') ?>)');
        resetTranslateModal();
        $('#translateModal').modal('show');
    });

    function resetTranslateModal() {
        $('#translateConsole').html('<span style="color:#adb5bd"><?= __('admin.click_translate_to_start') ?></span>');
        $('#translateProgress').addClass('d-none');
        $('#translateKeyBar').addClass('d-none');
        $('#translateKeyProgress').css('width', '0%');
        $('#translateKeysDone').text('0');
        $('#translateKeysTotal').text('0');
        $('#translateFileLabel').text('');
        $('#translateFilePct').text('');
        $('#translateStartBtn').prop('disabled', false)
            .html('<i class="fas fa-magic me-2"></i><?= __('admin.translate_btn') ?>');
    }

    function appendConsole(html) {
        const $c = $('#translateConsole');
        $c.append(html);
        $c.scrollTop($c[0].scrollHeight);
    }

    // Start button
    $('#translateStartBtn').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true)
            .html('<i class="fas fa-magic me-2"></i><?= __('admin.translate_btn') ?>');
        $('#translateProgress').removeClass('d-none');
        $('#translateConsole').html('');

        if (_translateQueue.length > 0) {
            // Queue mode — translate all missing languages one by one
            _translateQueueIdx = 0;
            processNextInQueue($btn);
        } else {
            // Single language mode
            runTranslate(_translateLangId, _translateLangName, $btn, true);
        }
    });

    function processNextInQueue($btn) {
        if (_translateQueueIdx >= _translateQueue.length) {
            $btn.prop('disabled', false)
                .html('<i class="fas fa-magic me-2"></i><?= __('admin.translate_btn') ?>');
            $('#translateProgress').addClass('d-none');
            appendConsole('<div class="text-success mt-2 fw-bold">✓ <?= __('admin.all_languages_translated') ?></div>');
            setTimeout(() => window.location.reload(), 2000);
            return;
        }
        const item = _translateQueue[_translateQueueIdx++];
        runTranslate(item.id, item.name, $btn, false);
    }

    function runTranslate(langId, langName, $btn, isSingle) {
        const files = ['admin.php', 'client.php', 'store.php', 'user.php', 'front.php', 'template_simple.php'];
        let fileIdx         = 0;
        let totalTranslated = 0;
        let totalMissing    = 0;
        let fileCounts      = {};
        const allErrors     = [];

        appendConsole('<div class="text-warning mb-1">┌─ [' + langName + ']</div>');
        $('#translateProgressText').text('<?= __('admin.translating') ?>: ' + langName);
        $('#translateCurrentLangLabel').text(langName);

        // ── Step 1: fetch missing counts, then start ─────────────────────────
        $.ajax({
            url:      '<?= base_url('admincontrol/language_missing_counts') ?>/' + langId,
            type:     'GET',
            dataType: 'json',
            global:   false,
            timeout:  30000,
            success: function(r) {
                if (r && r.success) {
                    fileCounts   = r.counts || {};
                    totalMissing = r.total  || 0;
                    $('#translateKeysTotal').text(totalMissing.toLocaleString());
                    $('#translateKeyBar').removeClass('d-none');
                }
                processNextFile();
            },
            error: function() { processNextFile(); }
        });

        // ── Update the key-progress bar ──────────────────────────────────────
        function updateKeyBar() {
            if (totalMissing <= 0) return;
            const pct = Math.min(100, Math.round((totalTranslated / totalMissing) * 100));
            $('#translateKeysDone').text(totalTranslated.toLocaleString());
            $('#translateKeyProgress').css('width', pct + '%');
            $('#translateFilePct').text(pct + '%');
        }

        // ── File-level loop ──────────────────────────────────────────────────
        function processNextFile() {
            if (fileIdx >= files.length) {
                finishAll();
                return;
            }
            const file     = files[fileIdx++];
            const fileBase = file.replace('.php', '');
            const fileMiss = fileCounts[fileBase] || 0;

            if (fileMiss === 0) {
                // Nothing to translate in this file — skip immediately
                appendConsole('<div style="color:#adb5bd">│  — ' + file + ': <?= __('admin.up_to_date') ?></div>');
                processNextFile();
                return;
            }

            // Show the file line with its total missing count
            const $fileLine = $('<div style="color:#adb5bd">│  ⏳ ' + file +
                ' <span style="color:#ffc107">(' + fileMiss.toLocaleString() + ' missing)</span>' +
                ' — <span class="file-translated-count">0</span> / ' + fileMiss.toLocaleString() +
                ' <span style="font-size:.75em;opacity:.7">this file</span>…</div>');
            $('#translateConsole').append($fileLine);
            $('#translateConsole').scrollTop($('#translateConsole')[0].scrollHeight);

            $('#translateFileLabel').text(file + ' (' + fileMiss.toLocaleString() + ' keys)');

            let fileTranslated = 0;

            // ── Batch loop for this file ─────────────────────────────────────
            function nextBatch() {
                $.ajax({
                    url:      '<?= base_url('admincontrol/translate_language_batch') ?>/' + langId,
                    type:     'POST',
                    dataType: 'json',
                    global:   false,
                    timeout:  90000,
                    data:     { file: file },
                    success: function(res) {
                        if (!res.success) {
                            allErrors.push(file + ': ' + (res.message || 'error'));
                            markFileDone($fileLine, file, fileTranslated, true);
                            processNextFile();
                            return;
                        }
                        if (res.translated > 0) {
                            fileTranslated  += res.translated;
                            totalTranslated += res.translated;
                            // Update inline file counter
                            $fileLine.find('.file-translated-count').text(fileTranslated.toLocaleString());
                            updateKeyBar();
                        }
                        if (res.is_done) {
                            markFileDone($fileLine, file, fileTranslated, false);
                            processNextFile();
                        } else {
                            nextBatch(); // still more batches in this file
                        }
                    },
                    error: function(xhr, status) {
                        const msg = status === 'timeout' ? 'timed out' : 'request failed';
                        allErrors.push(file + ' ' + msg);
                        markFileDone($fileLine, file, fileTranslated, true);
                        processNextFile();
                    }
                });
            }
            nextBatch();
        }

        function markFileDone($line, file, count, isError) {
            if (isError) {
                $line.css('color', '#dc3545')
                    .html('│  ✗ ' + file + ': <span style="color:#ffc107">' + count.toLocaleString() + ' translated then failed</span>');
            } else if (count > 0) {
                $line.css('color', '#198754')
                    .html('│  ✓ ' + file + ': <strong>' + count.toLocaleString() + '</strong> <?= __('admin.keys_translated') ?>');
            } else {
                $line.css('color', '#adb5bd')
                    .html('│  — ' + file + ': <?= __('admin.up_to_date') ?>');
            }
        }

        function finishAll() {
            $('#translateKeyProgress')
                .removeClass('progress-bar-animated progress-bar-striped')
                .addClass(totalTranslated > 0 ? 'bg-success' : 'bg-secondary');
            $('#translateFilePct').text('100%');
            $('#translateFileLabel').text('');
            if (totalMissing > 0) {
                $('#translateKeysDone').text(totalTranslated.toLocaleString());
                $('#translateKeyProgress').css('width', '100%');
            }

            appendConsole('<div class="text-warning">└─ <?= __('admin.done') ?></div>');
            if (allErrors.length) {
                $.each(allErrors, function(i, err) {
                    appendConsole('<div class="ms-2" style="color:#ffc107">⚠ ' + err + '</div>');
                });
            }
            const summaryHtml = totalTranslated > 0
                ? '<div class="text-success mt-2 fw-bold">✓ ' + totalTranslated.toLocaleString() + ' <?= __('admin.keys_translated') ?></div>'
                : '<div style="color:#adb5bd" class="mt-2">— <?= __('admin.translation_already_up_to_date') ?></div>';
            appendConsole(summaryHtml);

            if (isSingle) {
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-magic me-2"></i><?= __('admin.translate_btn') ?>');
                $('#translateProgress').addClass('d-none');
                if (totalTranslated > 0) setTimeout(() => window.location.reload(), 2000);
            } else {
                processNextInQueue($btn);
            }
        }
    }

    $(".form-language").submit(function(evt){
        evt.preventDefault();
        var formData = new FormData($(this)[0]);
        formData = formDataFilter(formData);
        $this = $(this);

        $this.find('.btn-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
        $.ajax({
            url: '<?= base_url('admincontrol/language_import') ?>',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            error: function(){
                $this.find('.btn-submit').prop('disabled', false).html($this.find('.btn-submit').data('original-text') || 'Submit');
            },
            success: function(json){
                $this.find('.btn-submit').prop('disabled', false).html($this.find('.btn-submit').data('original-text') || 'Submit');
                $this.find(".lang-message").html('');

                if(json['success']){
                    showToast('<?= __('admin.success') ?>', json['success'], 'success', 4000);
                    $this[0].reset();
                }
                if(json['warning']){
                    showToast('<?= __('admin.warning') ?>', json['warning'], 'warning', 4000);
                }
            },
        });
        return false;
    });
</script>
