<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-ticket-detailed me-2"></i>
                        <?= __('admin.tickets') ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white border-0 h-100 shadow-sm">
                                <div class="card-body text-center p-3">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="bi bi-ticket-detailed-fill fs-1 me-3"></i>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="total_tickets">0</h3>
                                            <small class="opacity-75"><?= __('admin.total_tickets') ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white border-0 h-100 shadow-sm">
                                <div class="card-body text-center p-3">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="bi bi-unlock-fill fs-1 me-3"></i>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="total_open_tickets">0</h3>
                                            <small class="opacity-75"><?= __('admin.total_open_tickets') ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-secondary text-white border-0 h-100 shadow-sm">
                                <div class="card-body text-center p-3">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="bi bi-lock-fill fs-1 me-3"></i>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="total_close_tickets">0</h3>
                                            <small class="opacity-75"><?= __('admin.total_close_tickets') ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-info text-white border-0 h-100 shadow-sm">
                                <div class="card-body text-center p-3">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="bi bi-tag-fill fs-1 me-3"></i>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="total_tickets_subject">0</h3>
                                            <small class="opacity-75"><?= __('admin.total_tickets_subject') ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-3 mb-2 mb-md-0">
                            <a href="<?= base_url('admincontrol/ticketcreate') ?>" class="btn btn-primary d-flex align-items-center">
                                <i class="bi bi-plus-circle me-2"></i>
                                <?= __('admin.add_new_ticket') ?>
                            </a>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <a href="<?= base_url('admincontrol/ticketssubject') ?>" class="btn btn-outline-primary d-flex align-items-center">
                                <i class="bi bi-tag me-2"></i>
                                <?= __('admin.add_ticket_subject') ?>
                            </a>
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <select id="tickets_status" class="form-select">
                                <option value=""><?= __('admin.tickets_user_select_status') ?></option>
                                <?php foreach ($status as $key => $value): $isSelected = $tickets_filter_status == $key ? 'selected':''; ?>
                                    <option value="<?=$key?>" <?=$isSelected?> ><?=$value?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <select id="ticket_subject" class="form-select">
                                <option value=""><?= __('admin.ticket_subject_selection') ?></option>
                                <?php foreach ($subjects as $key => $subj): ?>
                                    <option value="<?=$subj['id']?>"><?=$subj['subject']?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input autocomplete="off" type="text" name="date" value="" id="date_filter" placeholder="<?= __('admin.date') ?>" class="form-control daterange-picker">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="tbl_tickets_listing" class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th><?= __('admin.ticket_id') ?></th>
                                    <th><?= __('admin.ticket_date') ?></th>
                                    <th><?= __('admin.ticket_client') ?></th>
                                    <th><?= __('admin.ticket_subject') ?></th>
                                    <th><?= __('admin.ticket_status') ?></th>
                                    <th><?= __('admin.ticket_last_update') ?></th>
                                    <th class="text-center"><?= __('admin.action') ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/template/css/jquery.dataTables.min.css') ?>">
<script src="<?= base_url('assets/template/js/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/datatable/moment.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/daterangepicker.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/daterangepicker.css') ?>">

<script type="text/javascript">
$(document).ready(function() {
    // Check if DataTables is available
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables is not loaded');
        return;
    }
    
    function ticketslistingDatables() {
        $("#tbl_tickets_listing").DataTable({
            pageLength: 25,
            lengthMenu: [[25, 50, -1], [25, 50, "All"]],
            processing: true,
            serverSide: true,
            scrollY: false,
            serverMethod: "post",
            oLanguage: {
                sProcessing: '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Loading...',
            },
            ajax: {
                url: '<?=base_url()?>' + "tickets/getAlltickets",
                type: "POST",
                data: {
                    range: $("#date_filter").val(),
                    status: $('#tickets_status').val(),
                    subject: $('#ticket_subject').val(),
                },
                cache: true,
            },
            order: [[5, "DESC"]],
            columns: [
                { data: "ticket_id", targets: 0 },
                { data: "created_at", targets: 1 },
                { data: "username", targets: 2 },
                { data: "subjectName", targets: 3 },
                { data: "status_ids", targets: 4 },
                { data: "updated_at", targets: 5 },
                { data: "action", targets: 6, orderable: false, className: 'text-center' }
            ],
            language: {
                "decimal": "",
                "emptyTable": "<?php echo __('admin.no_data_available_in_table'); ?>",
                "info": "<?php echo __('admin.showing'); ?> _START_ to _END_ of _TOTAL_ <?php echo __('admin.entries'); ?>",
                "infoEmpty": "<?php echo __('admin.showing'); ?> 0 to 0 of 0 <?php echo __('admin.entries'); ?>",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "<?php echo __('admin.show'); ?> _MENU_ <?php echo __('admin.entries'); ?>",
                "loadingRecords": "<?php echo __('admin.loading'); ?>",
                "processing": "<?php echo __('admin.processing'); ?>",
                "search": "<?php echo __('admin.search'); ?>",
                "zeroRecords": "<?php echo __('admin.no_records_found'); ?>",
                "paginate": {
                    "first": "<?php echo __('admin.first'); ?>",
                    "last": "<?php echo __('admin.last_p'); ?>",
                    "next": "<?php echo __('admin.next'); ?>",
                    "previous": "<?php echo __('admin.previous'); ?>"
                },
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            drawCallback: function(settings) {
                if (settings.json && settings.json.data) {
                    updateStatistics(settings.json.data);
                }
            }
        });
    }

    function updateStatistics(data) {
        let total = data.length;
        let openCount = 0;
        let closeCount = 0;
        
        data.forEach(function(item) {
            if (item.status_ids && item.status_ids.includes('Open')) {
                openCount++;
            } else if (item.status_ids && item.status_ids.includes('Close')) {
                closeCount++;
            }
        });
        
        $("#total_tickets").text(total);
        $("#total_open_tickets").text(openCount);
        $("#total_close_tickets").text(closeCount);
    }

    function getStaticData() {
        $.ajax({
            url: '<?= base_url('tickets/getStaticeData') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.length != 0) {
                    $("#total_tickets").text(data.total);
                    $("#total_open_tickets").text(data.totalopen);
                    $("#total_close_tickets").text(data.totalclose);
                    $("#total_tickets_subject").text(data.totalsubject);
                }
            },
            error: function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_statistics') ?>', 'error', 3000);
                }
            }
        });
    }

    ticketslistingDatables();
    getStaticData();

    $("#tickets_status, #ticket_subject").change(function() {
        if ($.fn.DataTable.isDataTable('#tbl_tickets_listing')) {
            $("#tbl_tickets_listing").DataTable().destroy();
        }
        ticketslistingDatables();
    });

    $('.daterange-picker').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            cancelLabel: 'Clear',
            format: 'DD-MM-YYYY'
        }
    });

    $('.daterange-picker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
        if ($.fn.DataTable.isDataTable('#tbl_tickets_listing')) {
            $("#tbl_tickets_listing").DataTable().destroy();
        }
        ticketslistingDatables();
    });

    $('.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        if ($.fn.DataTable.isDataTable('#tbl_tickets_listing')) {
            $("#tbl_tickets_listing").DataTable().destroy();
        }
        ticketslistingDatables();
    });

    $(document).on('click', '.btnremove', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var row = $(this).closest('tr');
        
        if (confirm('<?=__('admin.are_you_sure')?>')) {
            $.ajax({
                url: '<?= base_url('tickets/deleteTicketStatus') ?>',
                type: 'POST',
                dataType: 'json',
                data: { ticket_id: id },
                success: function(data) {
                    if (data.status) {
                        row.fadeOut(400, function() {
                            row.remove();
                            if ($.fn.DataTable.isDataTable('#tbl_tickets_listing')) {
                                $("#tbl_tickets_listing").DataTable().ajax.reload();
                            }
                            if (typeof showToast === 'function') {
                                showToast('<?= __('admin.success') ?>', '<?= __('admin.ticket_deleted_successfully') ?>', 'success', 3000);
                            }
                        });
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_delete_ticket') ?>', 'error', 3000);
                        }
                    }
                },
                error: function() {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_delete_ticket') ?>', 'error', 3000);
                    }
                }
            });
        }
    });
});
</script>
