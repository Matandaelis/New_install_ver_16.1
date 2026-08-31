<script>window.MASS_PAYOUT_CSRF = <?= json_encode(isset($mass_payout_csrf) ? $mass_payout_csrf : '') ?>;</script>
<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/users/_wallet_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="card-title mb-0 fw-semibold"><?= __('admin.admincontrol_mass_payout') ?></h5>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#massPayoutImportModal" id="btn-mass-payout-import-global">
                        <i class="fas fa-file-upload me-1"></i><?= __('admin.mass_payout_upload_return') ?>
                    </button>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <p class="small text-muted mb-0"><?= __('admin.mass_payout_intro_one_line') ?></p>
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#massPayoutHelpCollapse" aria-expanded="false" aria-controls="massPayoutHelpCollapse">
                            <i class="fas fa-book-open me-1"></i><?= __('admin.mass_payout_help_toggle') ?>
                        </button>
                    </div>

                    <?php if (!empty($mass_payout_focus_notice)) { ?>
                    <div class="alert alert-<?= !empty($mass_payout_focus_notice_type) && $mass_payout_focus_notice_type === 'warning' ? 'warning' : 'info' ?> border-0 shadow-sm mb-3" role="status">
                        <div class="d-flex flex-wrap align-items-start gap-2">
                            <div class="flex-grow-1 small">
                                <span class="d-block"><i class="fas fa-info-circle me-1"></i><?= htmlspecialchars($mass_payout_focus_notice) ?></span>
                                <?php if (!empty($mass_payout_focus_wr_id)) { ?>
                                    <p class="mb-0 mt-2 text-body-secondary"><?= htmlspecialchars(__('admin.mass_payout_focus_next_steps')) ?></p>
                                <?php } ?>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <?php if (!empty($mass_payout_focus_wr_id)) { ?>
                                    <a class="btn btn-sm btn-dark" href="<?= base_url('admincontrol/wallet_requests_details/' . (int) $mass_payout_focus_wr_id) ?>">
                                        <i class="fas fa-external-link-alt me-1"></i><?= sprintf(__('admin.mass_payout_focus_open_request_details'), (int) $mass_payout_focus_wr_id) ?>
                                    </a>
                                <?php } ?>
                                <?php if (!empty($mass_payout_focus_batch_id)) { ?>
                                    <a class="btn btn-sm btn-outline-dark" href="<?= base_url('admincontrol/mass_payout') ?>#mass-payout-batch-<?= (int) $mass_payout_focus_batch_id ?>">
                                        <?= __('admin.mass_payout_open_batch') ?> #<?= (int) $mass_payout_focus_batch_id ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="collapse mb-3" id="massPayoutHelpCollapse">
                        <div class="border rounded bg-light p-3 small">
                            <p class="fw-semibold mb-2 text-dark"><?= __('admin.mass_payout_help_title') ?></p>
                            <ol class="mb-2 ps-3">
                                <li class="mb-1"><?= __('admin.mass_payout_help_step_1') ?></li>
                                <li class="mb-1"><?= __('admin.mass_payout_help_step_2') ?></li>
                                <li class="mb-1"><?= __('admin.mass_payout_help_step_3') ?></li>
                                <li class="mb-1"><?= __('admin.mass_payout_help_step_4') ?></li>
                                <li class="mb-0"><?= __('admin.mass_payout_help_step_5') ?></li>
                            </ol>
                            <p class="mb-2 text-muted"><i class="fas fa-info-circle me-1"></i><?= __('admin.mass_payout_help_tip') ?></p>
                            <hr class="my-2 opacity-50">
                            <p class="fw-semibold mb-1 text-dark"><?= __('admin.mass_payout_help_admin_title') ?></p>
                            <ul class="mb-0 ps-3">
                                <li class="mb-1"><?= __('admin.mass_payout_help_admin_revert') ?></li>
                                <li class="mb-1"><?= __('admin.mass_payout_help_admin_void') ?></li>
                                <li class="mb-0"><?= __('admin.mass_payout_help_admin_manual') ?></li>
                            </ul>
                        </div>
                    </div>

                    <form method="get" action="<?= base_url('admincontrol/mass_payout') ?>" class="row g-2 g-md-3 align-items-end mb-3 pb-3 border-bottom" id="mass-payout-filter">
                        <?php if (!empty($mass_payout_focus_wr_id)) { ?>
                        <input type="hidden" name="focus_wr" value="<?= (int) $mass_payout_focus_wr_id ?>">
                        <?php } ?>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><?= __('admin.filter_by_user') ?></label>
                            <select class="form-select" name="user_id">
                                <option value=""><?= __('admin.all') ?></option>
                                <?php foreach ($users as $u) { ?>
                                    <option value="<?= (int) $u['id'] ?>" <?= !empty($filter['user_id']) && (int) $filter['user_id'] === (int) $u['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($u['username']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><?= __('admin.filter_by_date') ?></label>
                            <input type="text" name="date" class="form-control daterange-picker" value="<?= isset($filter['date']) ? htmlspecialchars($filter['date']) : '' ?>" placeholder="<?= __('admin.filter_by_date') ?>" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" for="mass-payout-filter-status"><?= __('admin.mass_payout_filter_status') ?></label>
                            <select class="form-select" name="status" id="mass-payout-filter-status">
                                <?php foreach ($mass_payout_status_list as $sk => $slabel) { ?>
                                    <option value="<?= htmlspecialchars($sk) ?>" <?= isset($filter['status']) && (string) $filter['status'] === (string) $sk ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($slabel) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex flex-wrap align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i><?= __('admin.filter') ?>
                            </button>
                            <a href="<?= base_url('admincontrol/mass_payout') ?>" class="btn btn-outline-secondary"><?= __('admin.clear') ?></a>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold" for="mass-payout-processor"><?= __('admin.mass_payout_processor') ?></label>
                            <select class="form-select" id="mass-payout-processor">
                                <option value="paypal"><?= __('admin.mass_payout_processor_paypal') ?></option>
                                <option value="wise"><?= __('admin.mass_payout_processor_wise') ?></option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <span class="text-muted small d-block"><?= __('admin.currency') ?></span>
                            <span class="fw-semibold"><?= htmlspecialchars($default_currency) ?></span>
                        </div>
                        <div class="col-lg-4 col-md-12 text-lg-end">
                            <button type="button" class="btn btn-success" id="mass-payout-submit">
                                <i class="fas fa-download me-1"></i><?= __('admin.mass_payout_create_batch') ?>
                            </button>
                        </div>
                    </form>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="mass-payout-select-all">
                        <label class="form-check-label" for="mass-payout-select-all"><?= __('admin.mass_payout_select_all_page') ?></label>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mass-payout-export-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold" style="width:40px;"></th>
                                    <th class="fw-semibold"><?= __('admin.id') ?></th>
                                    <th class="fw-semibold"><?= __('admin.user') ?></th>
                                    <th class="fw-semibold"><?= __('admin.date') ?></th>
                                    <th class="fw-semibold"><?= __('admin.payment_method') ?></th>
                                    <th class="fw-semibold"><?= __('admin.total') ?></th>
                                    <th class="fw-semibold"><?= __('admin.status') ?></th>
                                    <th class="fw-semibold"><?= __('admin.mass_payout_receiver_preview_paypal') ?></th>
                                    <th class="fw-semibold"><?= __('admin.mass_payout_receiver_preview_wise') ?></th>
                                    <th class="fw-semibold text-end"><?= __('admin.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lists)) { ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4 px-2 small"><?= __('admin.mass_payout_section_table_empty') ?></td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($lists as $row) { ?>
                                        <tr id="mass-payout-wr-<?= (int) $row['id'] ?>">
                                            <td>
                                                <input class="form-check-input mass-payout-cb" type="checkbox" name="request_ids[]" value="<?= (int) $row['id'] ?>">
                                            </td>
                                            <td class="fw-semibold"><?= (int) $row['id'] ?></td>
                                            <td><?= htmlspecialchars($row['username'] ?? '') ?></td>
                                            <td><span class="text-muted small"><?= dateFormat($row['created_at'], 'd M Y H:i') ?></span></td>
                                            <td><span class="badge bg-info"><?= htmlspecialchars($row['prefer_method'] ?? 'N/A') ?></span></td>
                                            <td class="fw-bold text-success"><?= c_format($row['total']) ?></td>
                                            <td><?= withdrwal_status($row['status']) ?></td>
                                            <td class="small font-monospace"><?= $row['payout_receiver_paypal'] !== '' ? htmlspecialchars($row['payout_receiver_paypal']) : '<span class="text-danger">' . __('admin.not_specified') . '</span>' ?></td>
                                            <td class="small font-monospace"><?= $row['payout_receiver_wise'] !== '' ? htmlspecialchars($row['payout_receiver_wise']) : '<span class="text-danger">' . __('admin.not_specified') . '</span>' ?></td>
                                            <td class="text-end">
                                                <a href="<?= base_url('admincontrol/wallet_requests_details/' . (int) $row['id']) ?>" class="btn btn-sm btn-outline-primary"><?= __('admin.details') ?></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <?php
                    $total_pages = $per_page > 0 ? (int) ceil($total_rows / $per_page) : 1;
                    if ($total_pages > 1) {
                        $q = $_GET;
                        ?>
                        <nav class="mt-3" aria-label="Mass payout pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <?php for ($p = 1; $p <= $total_pages; $p++) {
                                    $q['page'] = $p;
                                    $url = base_url('admincontrol/mass_payout') . '?' . http_build_query($q);
                                    ?>
                                    <li class="page-item <?= $p === (int) $current_page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= htmlspecialchars($url) ?>"><?= $p ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    <?php } ?>

                    <hr class="my-3">

                    <h6 class="fw-semibold mb-2"><?= __('admin.mass_payout_recent_batches') ?></h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mass-payout-batches-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold"><?= __('admin.mass_payout_batch_id') ?></th>
                                    <th class="fw-semibold"><?= __('admin.date') ?></th>
                                    <th class="fw-semibold"><?= __('admin.mass_payout_processor') ?></th>
                                    <th class="fw-semibold"><?= __('admin.mass_payout_rows') ?></th>
                                    <th class="fw-semibold"><?= __('admin.mass_payout_total') ?></th>
                                    <th class="fw-semibold"><?= __('admin.currency') ?></th>
                                    <th class="fw-semibold"><?= __('admin.mass_payout_batch_column_status') ?></th>
                                    <th class="fw-semibold text-end"><?= __('admin.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent_batches)) { ?>
                                    <tr><td colspan="8" class="text-muted text-center py-3"><?= __('admin.no_data_found') ?></td></tr>
                                <?php } else { ?>
                                    <?php foreach ($recent_batches as $b) { ?>
                                        <tr id="mass-payout-batch-<?= (int) $b['id'] ?>">
                                            <td class="fw-semibold">#<?= (int) $b['id'] ?></td>
                                            <td class="small"><?= htmlspecialchars($b['created_at']) ?></td>
                                            <td><span class="badge bg-secondary text-uppercase"><?= htmlspecialchars($b['processor']) ?></span></td>
                                            <td><?= (int) $b['row_count'] ?></td>
                                            <td><?= htmlspecialchars($b['total_amount']) ?></td>
                                            <td><?= htmlspecialchars($b['currency_code']) ?></td>
                                            <td class="align-middle">
                                                <?php
                                                $pu = isset($b['progress_ui']) ? $b['progress_ui'] : array(
                                                    'badge_html' => '<span class="badge bg-secondary">—</span>',
                                                    'upload_btn_class' => 'btn-outline-secondary',
                                                    'upload_label' => __('admin.mass_payout_upload_return'),
                                                    'upload_title' => __('admin.mass_payout_upload_return'),
                                                );
                                                echo $pu['badge_html'];
                                                ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex flex-wrap justify-content-end gap-1">
                                                    <button type="button" class="btn btn-sm btn-primary mass-payout-download-btn" data-batch-id="<?= (int) $b['id'] ?>">
                                                        <i class="fas fa-download me-1"></i><?= __('admin.mass_payout_download_again') ?>
                                                    </button>
                                                    <button type="button" class="btn btn-sm <?= htmlspecialchars(isset($pu['upload_btn_class']) ? $pu['upload_btn_class'] : 'btn-outline-secondary') ?> mass-payout-open-import" data-batch-id="<?= (int) $b['id'] ?>" title="<?= htmlspecialchars(isset($pu['upload_title']) ? $pu['upload_title'] : __('admin.mass_payout_upload_return')) ?>">
                                                        <i class="fas fa-file-upload me-1"></i><?= htmlspecialchars(isset($pu['upload_label']) ? $pu['upload_label'] : __('admin.mass_payout_upload_return')) ?>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger mass-payout-void-btn" data-batch-id="<?= (int) $b['id'] ?>" title="<?= htmlspecialchars(__('admin.mass_payout_void_batch_hint')) ?>">
                                                        <i class="fas fa-unlink me-1"></i><?= __('admin.mass_payout_void_batch') ?>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="massPayoutImportModal" tabindex="-1" aria-labelledby="massPayoutImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-semibold" id="massPayoutImportModalLabel">
                    <i class="fas fa-file-upload me-2"></i><?= __('admin.mass_payout_import_modal_title') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3"><?= __('admin.mass_payout_import_help') ?></p>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="mass-payout-import-batch"><?= __('admin.mass_payout_batch_id') ?></label>
                    <select class="form-select" id="mass-payout-import-batch">
                        <?php if (!empty($recent_batches)) { ?>
                            <?php foreach ($recent_batches as $b) {
                                $pt = isset($b['progress_total']) ? (int) $b['progress_total'] : (int) $b['row_count'];
                                $pp = isset($b['progress_paid']) ? (int) $b['progress_paid'] : 0;
                                $pf = isset($b['progress_failed']) ? (int) $b['progress_failed'] : 0;
                                ?>
                                <option value="<?= (int) $b['id'] ?>">#<?= (int) $b['id'] ?> — <?= htmlspecialchars(strtoupper($b['processor'])) ?> (<?= (int) $b['row_count'] ?> <?= __('admin.mass_payout_rows') ?>, <?= $pp ?>/<?= max(1, $pt) ?> <?= __('admin.mass_payout_batch_status_paid_abbr') ?><?= $pf > 0 ? ', ' . $pf . ' ' . __('admin.mass_payout_batch_status_failed_abbr') : '' ?>)</option>
                            <?php } ?>
                        <?php } else { ?>
                            <option value=""><?= __('admin.no_data_found') ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="mass-payout-import-file"><?= __('admin.mass_payout_import_choose_file') ?></label>
                    <input type="file" class="form-control" id="mass-payout-import-file" accept=".csv,.txt,text/csv,text/plain">
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
                <button type="button" class="btn btn-primary" id="mass-payout-import-submit">
                    <i class="fas fa-check me-1"></i><?= __('admin.mass_payout_import_submit') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="massPayoutImportSummaryModal" tabindex="-1" aria-labelledby="massPayoutImportSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-semibold" id="massPayoutImportSummaryModalLabel">
                    <i class="fas fa-check-circle me-2"></i><?= __('admin.mass_payout_import_summary_title') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-2 fw-semibold" id="mass-payout-summary-line"></p>
                <div id="mass-payout-summary-errors" class="small text-danger d-none"></div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="mass-payout-summary-ok"><?= __('admin.close') ?></button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css?v=<?= av() ?>" />
<script type="text/javascript">
(function () {
    function massPayoutDownloadBatch(batchId) {
        if (!batchId || !window.MASS_PAYOUT_CSRF) {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.mass_payout_csrf_invalid') ?>', 'error', 4000);
            } else {
                alert('<?= __('admin.mass_payout_csrf_invalid') ?>');
            }
            return;
        }
        var fd = new FormData();
        fd.append('batch_id', String(batchId));
        fd.append('mass_payout_csrf', window.MASS_PAYOUT_CSRF);
        fetch('<?= base_url('admincontrol/mass_payout_download') ?>', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) {
            if (r.status === 403) {
                throw new Error('csrf');
            }
            if (!r.ok) {
                throw new Error('http');
            }
            var disp = r.headers.get('Content-Disposition');
            var fn = 'mass_payout_batch_' + batchId + '.csv';
            if (disp) {
                var m = /filename\*?=(?:UTF-8'')?["']?([^;\n]+)/i.exec(disp);
                if (m && m[1]) {
                    fn = decodeURIComponent(m[1].replace(/["']/g, '').trim());
                }
            }
            return r.blob().then(function (blob) { return { blob: blob, fn: fn }; });
        }).then(function (o) {
            var a = document.createElement('a');
            a.href = URL.createObjectURL(o.blob);
            a.download = o.fn;
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(function () { URL.revokeObjectURL(a.href); }, 2500);
        }).catch(function () {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error', 4000);
            } else {
                alert('<?= __('admin.error') ?>');
            }
        });
    }

    $(document).on('click', '.mass-payout-download-btn', function () {
        var bid = $(this).data('batch-id');
        if (bid) {
            massPayoutDownloadBatch(bid);
        }
    });

    if (typeof $ !== 'undefined' && $.fn.daterangepicker) {
        $('.daterange-picker').each(function () {
            var $el = $(this);
            if ($el.data('massPayoutDrp')) return;
            $el.daterangepicker({
                autoUpdateInput: false,
                locale: { cancelLabel: 'Clear', format: 'YYYY-MM-DD' }
            });
            $el.on('apply.daterangepicker', function (ev, picker) {
                $el.val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });
            $el.on('cancel.daterangepicker', function () { $el.val(''); });
            $el.data('massPayoutDrp', true);
        });
    }

    $('#mass-payout-select-all').on('change', function () {
        $('.mass-payout-cb').prop('checked', $(this).prop('checked'));
    });

    $('#mass-payout-submit').on('click', function () {
        var ids = $('.mass-payout-cb:checked').map(function () { return $(this).val(); }).get();
        if (!ids.length) {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.mass_payout_select_requests') ?>', 'error', 4000);
            } else {
                alert('<?= __('admin.mass_payout_select_requests') ?>');
            }
            return;
        }
        var processor = $('#mass-payout-processor').val();
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: '<?= base_url('admincontrol/mass_payout_create') ?>',
            type: 'POST',
            dataType: 'json',
            data: { request_ids: ids, processor: processor, mass_payout_csrf: window.MASS_PAYOUT_CSRF },
            success: function (json) {
                if (json && json.success && json.batch_id) {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', json.message || '<?= __('admin.mass_payout_batch_created') ?>', 'success', 3000);
                    }
                    massPayoutDownloadBatch(json.batch_id);
                    setTimeout(function () { window.location.reload(); }, 1200);
                } else {
                    var msg = (json && json.message) ? json.message : '<?= __('admin.error') ?>';
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', msg, 'error', 6000);
                    } else {
                        alert(msg);
                    }
                }
            },
            error: function () {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error', 4000);
                } else {
                    alert('<?= __('admin.error') ?>');
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

    var importModal = document.getElementById('massPayoutImportModal');
    var summaryModal = document.getElementById('massPayoutImportSummaryModal');

    $(document).on('click', '.mass-payout-open-import', function () {
        var bid = $(this).data('batch-id');
        if (bid) {
            $('#mass-payout-import-batch').val(String(bid));
        }
        if (typeof bootstrap !== 'undefined' && importModal) {
            bootstrap.Modal.getOrCreateInstance(importModal).show();
        }
    });

    $('#btn-mass-payout-import-global').on('click', function () {
        var $sel = $('#mass-payout-import-batch');
        if ($sel.find('option').length && $sel.val() === '') {
            $sel.prop('selectedIndex', 0);
        }
    });

    $('#mass-payout-import-submit').on('click', function () {
        var batchId = $('#mass-payout-import-batch').val();
        var fileInput = document.getElementById('mass-payout-import-file');
        if (!batchId || batchId === '') {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.mass_payout_import_invalid') ?>', 'error', 4000);
            } else {
                alert('<?= __('admin.mass_payout_import_invalid') ?>');
            }
            return;
        }
        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.mass_payout_import_no_file') ?>', 'error', 4000);
            } else {
                alert('<?= __('admin.mass_payout_import_no_file') ?>');
            }
            return;
        }
        var fd = new FormData();
        fd.append('batch_id', batchId);
        fd.append('mass_payout_csrf', window.MASS_PAYOUT_CSRF || '');
        fd.append('import_file', fileInput.files[0]);
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: '<?= base_url('admincontrol/mass_payout_import') ?>',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (json) {
                if (json && json.success) {
                    if (importModal && typeof bootstrap !== 'undefined') {
                        bootstrap.Modal.getOrCreateInstance(importModal).hide();
                    }
                    $('#mass-payout-summary-line').text(json.summary_line || '');
                    var $err = $('#mass-payout-summary-errors');
                    if (json.errors && json.errors.length) {
                        $err.removeClass('d-none').html('<ul class="mb-0 mt-2"><li>' + json.errors.join('</li><li>') + '</li></ul>');
                    } else {
                        $err.addClass('d-none').empty();
                    }
                    var sumHeader = summaryModal ? summaryModal.querySelector('.modal-header') : null;
                    if (sumHeader) {
                        sumHeader.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                        if (json.failed > 0 && json.paid === 0) {
                            sumHeader.classList.add('bg-danger');
                        } else if (json.failed > 0) {
                            sumHeader.classList.add('bg-warning');
                        } else {
                            sumHeader.classList.add('bg-success');
                        }
                    }
                    if (summaryModal && typeof bootstrap !== 'undefined') {
                        bootstrap.Modal.getOrCreateInstance(summaryModal).show();
                    }
                    fileInput.value = '';
                } else {
                    var msg = (json && json.message) ? json.message : '<?= __('admin.error') ?>';
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', msg, 'error', 6000);
                    } else {
                        alert(msg);
                    }
                }
            },
            error: function () {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error', 4000);
                } else {
                    alert('<?= __('admin.error') ?>');
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

    $(document).on('click', '.mass-payout-void-btn', function () {
        var bid = $(this).data('batch-id');
        if (!bid || !window.MASS_PAYOUT_CSRF) {
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.mass_payout_csrf_invalid') ?>', 'error', 4000);
            } else {
                alert('<?= __('admin.mass_payout_csrf_invalid') ?>');
            }
            return;
        }
        if (!window.confirm(<?= json_encode(__('admin.mass_payout_void_confirm')) ?>)) {
            return;
        }
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: '<?= base_url('admincontrol/mass_payout_void') ?>',
            type: 'POST',
            dataType: 'json',
            data: { batch_id: bid, mass_payout_csrf: window.MASS_PAYOUT_CSRF },
            success: function (json) {
                if (json && json.success) {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', json.message || '', 'success', 4000);
                    }
                    window.location.reload();
                } else {
                    var msg = (json && json.message) ? json.message : '<?= __('admin.error') ?>';
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', msg, 'error', 8000);
                    } else {
                        alert(msg);
                    }
                }
            },
            error: function () {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error', 4000);
                } else {
                    alert('<?= __('admin.error') ?>');
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

    $('#mass-payout-summary-ok').on('click', function () {
        window.location.reload();
    });

    (function massPayoutFocusRow() {
        var fid = <?= (int) (!empty($focus_wallet_request_id) ? $focus_wallet_request_id : 0) ?>;
        if (fid < 1) {
            return;
        }
        var el = document.getElementById('mass-payout-wr-' + fid);
        if (!el) {
            return;
        }
        setTimeout(function () {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.classList.add('table-warning');
            setTimeout(function () {
                el.classList.remove('table-warning');
            }, 2800);
        }, 300);
    })();
})();
</script>
