<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i><?= __('admin.menu_users_statistics') ?>
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-outline-light btn-sm" onclick="table.ajax.reload();" title="<?= __('admin.refresh') ?>">
                                <i class="fas fa-sync-alt me-1"></i><?= __('admin.refresh') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4" id="filter-form">
                        <input type="hidden" name="is_admin" value="1">
                        <div class="col-md-3">
                            <label class="form-label fw-medium"><?= __('admin.user') ?></label>
                            <select name="user_id" class="form-select user-autocomplete">
                                <option value=""><?= __('admin.all_users') ?></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium"><?= __('admin.date') ?></label>
                            <input autocomplete="off" type="text" name="date" value="" class="form-control daterange-picker" placeholder="<?= __('admin.select_date_range') ?>">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary" onclick="table.ajax.reload();">
                                <i class="fas fa-search me-1"></i><?= __('admin.search') ?>
                            </button>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button id="exportPdf" class="btn btn-danger">
                                <i class="fas fa-file-pdf me-1"></i><?= __('admin.download_as_pdf') ?>
                            </button>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button id="exportExcel" class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i><?= __('admin.download_as_excel') ?>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    <?= __('admin.total_users') ?>: <span class="total-affiliate fw-bold text-primary">0</span>
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <?= __('admin.data_updates_automatically') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="table-report">
                            <thead class="table-dark">
                                <tr class="align-middle text-center">
                                    <th scope="col" class="ps-3"><?= __('admin.no') ?></th>
                                    <th scope="col"><?= __('admin.full_name') ?></th>
                                    <th scope="col"><?= __('admin.username') ?></th>
                                    <th scope="col" colspan="2" class="text-center">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?= __('admin.cpc') ?></span>
                                            <small class="text-muted"><?= __('admin.click_per_commission') ?></small>
                                        </div>
                                    </th>
                                    <th scope="col" colspan="3" class="text-center">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?= __('admin.cps') ?></span>
                                            <small class="text-muted"><?= __('admin.sale_per_commission') ?></small>
                                        </div>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?= __('admin.cpa') ?></span>
                                            <small class="text-muted"><?= __('admin.action_per_commission') ?></small>
                                        </div>
                                    </th>
                                    <th scope="col" colspan="2" class="text-center">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?= __('admin.total') ?></span>
                                            <small class="text-muted"><?= __('admin.summary') ?></small>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="table-secondary text-center">
                                    <th scope="col" class="ps-3">#</th>
                                    <th scope="col"><?= __('admin.full_name') ?></th>
                                    <th scope="col"><?= __('admin.username') ?></th>
                                    <th scope="col"><?= __('admin.count') ?></th>
                                    <th scope="col"><?= __('admin.commission') ?></th>
                                    <th scope="col"><?= __('admin.count') ?></th>
                                    <th scope="col"><?= __('admin.total') ?></th>
                                    <th scope="col"><?= __('admin.commission') ?></th>
                                    <th scope="col"><?= __('admin.count_commission_cpa') ?></th>
                                    <th scope="col"><?= __('admin.income') ?></th>
                                    <th scope="col"><?= __('admin.commission') ?></th>
                                </tr>
                            </thead>
                            <tbody class="tiny-table">
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <div class="d-flex justify-content-center align-items-center flex-column">
                                            <div class="spinner-border text-primary mb-3" role="status">
                                                <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                                            </div>
                                            <h5 class="text-muted mb-2"><?= __('admin.loading_data') ?></h5>
                                            <p class="text-muted small mb-0"><?= __('admin.please_wait') ?></p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.css">

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/dataTables.bootstrap.min.css">

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>

<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css" />


<script type="text/javascript">
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

            format: 'DD-M-YYYY'

        }
    });

	$('.daterange-picker').on('apply.daterangepicker', function(ev, picker) {

        $(this).val(picker.startDate.format('DD-M-YYYY') + ' - ' + picker.endDate.format('DD-M-YYYY'));
    });

    $('.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {

        $(this).val('');
    });

	var table = $('#table-report').DataTable({
	    dom: 'Bfrtip',
	    ajax: function(data, callback){
	    	$.ajax({
		    	url: "<?= base_url('incomereport/get_data') ?>",
		    	data: {
					destination: 'admin-user-stat',
					page_no: data.start,
					is_admin: 1,
					page_lenght: 20,
					date: $(".daterange-picker").val(),
					user_id: $("select[name=user_id]").val(),
			  	},
		    	dataType: 'json',
		    	type: 'post',
		    	timeout: 30000,
		    	beforeSend: function(){
		    		$(".total-affiliate").text('...');
		    	},
		    	complete: function(){
		    		// Complete handler
		    	},
		    	success: function(json){
		    		if (json && json.data) {
		    			$(".total-affiliate").text(json.recordsTotal || json.data.length);
		    			callback(json);
		    		} else {
		    			$(".total-affiliate").text('0');
		    			callback({data: []});
		    		}
		    	},
		    	error: function(xhr, status, error){
		    		console.error('DataTable AJAX Error:', error);
		    		$(".total-affiliate").text('0');
		    		callback({data: []});
		    		
		    		// Show error in table
		    		$('#table-report tbody').html(`
		    			<tr>
		    				<td colspan="11" class="text-center py-5">
		    					<div class="d-flex justify-content-center align-items-center flex-column">
		    						<i class="fas fa-exclamation-triangle fs-1 text-danger mb-3"></i>
		    						<h5 class="text-danger mb-2"><?= __('admin.error') ?></h5>
		    						<p class="text-muted mb-3"><?= __('admin.error_loading_data') ?></p>
		    						<button class="btn btn-outline-primary btn-sm" onclick="table.ajax.reload();">
		    							<i class="fas fa-redo me-1"></i><?= __('admin.retry') ?>
		    						</button>
		    					</div>
		    				</td>
		    			</tr>
		    		`);
		    	}
		    });
		},
		pageLength: 15,
	    buttons: [],
	    bFilter: false, 
        bPaginate: true,
        pagination: true,
        bInfo: true,
        processing: true,
        serverSide: true,
        language: {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="d-flex justify-content-center align-items-center"><div class="spinner-border spinner-border-sm me-2" role="status"></div><?= __('admin.loading') ?>...</div>',
            'emptyTable': '<?= __('admin.no_data_available') ?>',
            'zeroRecords': '<?= __('admin.no_matching_records') ?>'
        },
	});

	$(".user-autocomplete").select2({
		ajax: {
			url: '<?= base_url('incomereport/user_search') ?>',
			dataType: 'json',
			delay: 300,
			data: function(params) {
				return {
					p: params.term,
					page: params.page
				};
			},
			processResults: function(data, params) {
				var data = $.map(data, function(obj) {
					obj.id = obj.id;
					obj.text = obj.name;
					return obj;
				});
				params.page = params.page || 1;
				return {
					results: data,
					pagination: {
						more: (params.page * 30) < data.total_count
					}
				};
			},
			cache: true
		},
		escapeMarkup: function(markup) {
			return markup;
		},
		allowClear: true,
		minimumInputLength: 3,
		placeholder: '<?= __('admin.search_users') ?>...',
		language: {
			noResults: function() {
				return '<?= __('admin.no_users_found') ?>';
			},
			searching: function() {
				return '<?= __('admin.searching') ?>...';
			},
			loadingMore: function() {
				return '<?= __('admin.loading_more') ?>...';
			}
		}
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to export data to PDF
    function openPDF() {
        const $btn = $('#exportPdf');
        const originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.processing') ?>...');
        
        $.ajax({
            url: '<?= base_url('incomereport/get_data') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                destination: 'admin-user-stat',
                is_admin: 1,
                date: $(".daterange-picker").val(),
                user_id: $("select[name=user_id]").val(),
            },
            timeout: 30000,
            success: function(response) {
                if (response && response.data && response.data.length > 0) {
                    try {
                        const { jsPDF } = window.jspdf;
                        const doc = new jsPDF();
                        doc.text('<?= __('admin.menu_users_statistics') ?>', 10, 10);

                        const tableData = response.data.map(row => {
                            return row.map(cell => {
                                if (typeof cell === 'string' && cell.includes('<img')) {
                                    return cell.split('<img')[0].trim();
                                }
                                return cell;
                            });
                        });

                        doc.autoTable({
                            head: [['#', '<?= __('admin.full_name') ?>', '<?= __('admin.username') ?>', '<?= __('admin.count') ?>', '<?= __('admin.commission') ?>', '<?= __('admin.count') ?>', '<?= __('admin.total') ?>', '<?= __('admin.commission') ?>', '<?= __('admin.count_commission_cpa') ?>', '<?= __('admin.income') ?>', '<?= __('admin.commission') ?>']],
                            body: tableData,
                            startY: 20,
                            styles: { fontSize: 8 },
                            headStyles: { fillColor: [66, 139, 202] }
                        });
                        
                        const fileName = '<?= __('admin.menu_users_statistics') ?>_' + new Date().toISOString().split('T')[0] + '.pdf';
                        doc.save(fileName);
                        
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.success') ?>', '<?= __('admin.pdf_exported_successfully') ?>', 'success');
                        }
                    } catch (error) {
                        console.error('PDF Export Error:', error);
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.error') ?>', '<?= __('admin.pdf_export_failed') ?>', 'error');
                        }
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.warning') ?>', '<?= __('admin.no_data_to_export') ?>', 'warning');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('PDF Export AJAX Error:', error);
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.export_failed') ?>', 'error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    // Function to export data to Excel
    function exportToExcel() {
        const $btn = $('#exportExcel');
        const originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.processing') ?>...');
        
        $.ajax({
            url: '<?= base_url('incomereport/get_data') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                destination: 'admin-user-stat',
                is_admin: 1,
                date: $(".daterange-picker").val(),
                user_id: $("select[name=user_id]").val(),
            },
            timeout: 30000,
            success: function(response) {
                if (response && response.data && response.data.length > 0) {
                    try {
                        const tableData = response.data.map(row => {
                            return row.map(cell => {
                                if (typeof cell === 'string' && cell.includes('<img')) {
                                    return cell.split('<img')[0].trim();
                                }
                                return cell;
                            });
                        });

                        const ws_name = "<?= __('admin.menu_users_statistics') ?>";
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet([
                            ['#', '<?= __('admin.full_name') ?>', '<?= __('admin.username') ?>', '<?= __('admin.count') ?>', '<?= __('admin.commission') ?>', '<?= __('admin.count') ?>', '<?= __('admin.total') ?>', '<?= __('admin.commission') ?>', '<?= __('admin.count_commission_cpa') ?>', '<?= __('admin.income') ?>', '<?= __('admin.commission') ?>'],
                            ...tableData
                        ]);
                        XLSX.utils.book_append_sheet(wb, ws, ws_name);
                        
                        const fileName = '<?= __('admin.menu_users_statistics') ?>_' + new Date().toISOString().split('T')[0] + '.xlsx';
                        XLSX.writeFile(wb, fileName);
                        
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.success') ?>', '<?= __('admin.excel_exported_successfully') ?>', 'success');
                        }
                    } catch (error) {
                        console.error('Excel Export Error:', error);
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.error') ?>', '<?= __('admin.excel_export_failed') ?>', 'error');
                        }
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.warning') ?>', '<?= __('admin.no_data_to_export') ?>', 'warning');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Excel Export AJAX Error:', error);
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.export_failed') ?>', 'error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    document.getElementById('exportPdf').addEventListener('click', openPDF);
    document.getElementById('exportExcel').addEventListener('click', exportToExcel);
});
</script>
