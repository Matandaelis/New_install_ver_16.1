<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/users/_wallet_nav'); ?>
    <div class="row">
        <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <h5 class="card-title mb-0 fw-semibold"><?= __('admin.withdraw_requests_list') ?></h5>
                                <button type="button" class="filter-toggle btn btn-outline-primary btn-sm">
                                    <i class="fas fa-filter me-1"></i><?= __('admin.filter') ?>
                                </button>
                            </div>
                            
                            <form method="GET" class="mt-3 d-none" id="new_filter">
                        <input type="hidden" name="get_new" value="1">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <select class="form-select" name="user_id">
                                    <option value=""><?= __('admin.filter_by_user') ?></option>
                                    <?php foreach ($users as $key => $value) { ?>
                                        <option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>">
                                            <?= $value['username'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-lg-4 col-md-6">
                                <input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker" placeholder='<?= __('admin.filter_by_date') ?>'>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <div class="d-flex gap-2 align-items-end h-100 pb-1">
                                    <button type="submit" class="btn btn-primary btn-new-filter">
                                        <i class="fas fa-search me-1"></i><?= __('admin.filter') ?>
                                    </button>
                                    <a href="<?= base_url('admincontrol/wallet_requests_list') ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh me-1"></i><?= __('admin.clear') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                        <div class="card-body">
                            <div class="showmessage"></div>
                            <div class="new-empty text-center d-none py-5">
                                <div class="bg-light rounded-circle p-4 mb-3 d-inline-block">
                                    <i class="fas fa-exchange-alt text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                                <p class="text-muted mb-0"><?= __('admin.no_withdraw_requests_found_matching_criteria') ?></p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover mb-0 withdraw-requests-list-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold"><?= __('admin.id') ?></th>
                                    <th class="fw-semibold"><?= __('admin.user') ?></th>
                                    <th class="fw-semibold"><?= __('admin.date') ?></th>
                                    <th class="fw-semibold"><?= __('admin.payment_method') ?></th>
                                    <th class="fw-semibold"><?= __('admin.transactions_ids') ?></th>
                                    <th class="fw-semibold"><?= __('admin.total') ?></th>
                                    <th class="fw-semibold"><?= __('admin.status') ?></th>
                                    <th class="fw-semibold text-end"><?= __('admin.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    
                            <div class="mt-3">
                                <div id="pagination-container"></div>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-confirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= __('admin.confirmation_required') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-confirmstatus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-cog me-2"></i><?= __('admin.status_update') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-recursion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-repeat me-2"></i><?= __('admin.recurring_settings') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="transIds" tabindex="-1" aria-labelledby="transIdsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-semibold" id="transIdsLabel">
                    <i class="fas fa-list me-2"></i><?= __('admin.transactions_ids') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-receipt text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1 fw-semibold"><?= __('admin.transaction_details') ?></h6>
                        <p class="mb-0 text-muted transaction-ids-content"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i><?= __('admin.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css?v=<?= av() ?>" />
<script type="text/javascript">

	let currentPage = 1;
	let totalRows = 0;
	let perPage = 15;

	function new_filter(page = 1) {
		currentPage = page;
		const formData = new FormData(document.getElementById('new_filter'));
		formData.append('page', page);
		formData.append('per_page', perPage);
		
		$.ajax({
			type:'POST',
			dataType:'json',
			data: Object.fromEntries(formData),
			beforeSend:function(){
				$('.btn-new-filter').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
			},
			complete:function(){
				$('.btn-new-filter').prop('disabled', false).html('<i class="fas fa-search me-1"></i><?= __('admin.filter') ?>');
			},
                success:function(json){
                    if(json['html']){
                        $(".table tbody").html(json['html']);
                        $(".new-empty").addClass('d-none');
                        $(".table").show();
                        
                        if(json['total_rows']) {
                            totalRows = parseInt(json['total_rows']);
                            updatePagination();
                        }
                    } else{
                        $(".table").hide();
                        $(".new-empty").removeClass('d-none');
                        totalRows = 0;
                        updatePagination();
                    }
                },
			error: function() {
				showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error', 3000);
			}
		})

		return false;
	}

	function updatePagination() {
		if (totalRows <= perPage) {
			$('#pagination-container').html('<div class="text-muted small text-center py-2"><?= __('admin.total') ?>: ' + totalRows + ' <?= __('admin.items') ?> (<?= __('admin.page') ?> 1 <?= __('admin.of') ?> 1)</div>');
			return;
		}
		
		const totalPages = Math.ceil(totalRows / perPage);
		const paginationHtml = generatePaginationHtml(currentPage, totalPages);
		$('#pagination-container').html(paginationHtml);
	}

	function generatePaginationHtml(currentPage, totalPages) {
		let html = '<nav aria-label="Pagination"><ul class="pagination pagination-sm justify-content-center">';
		
		if (currentPage > 1) {
			html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="new_filter(${currentPage - 1})"><i class="fas fa-chevron-left"></i></a></li>`;
		}
		
		const startPage = Math.max(1, currentPage - 2);
		const endPage = Math.min(totalPages, currentPage + 2);
		
		if (startPage > 1) {
			html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="new_filter(1)">1</a></li>`;
			if (startPage > 2) {
				html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
			}
		}
		
		for (let i = startPage; i <= endPage; i++) {
			const activeClass = i === currentPage ? 'active' : '';
			html += `<li class="page-item ${activeClass}"><a class="page-link" href="javascript:void(0)" onclick="new_filter(${i})">${i}</a></li>`;
		}
		
		if (endPage < totalPages) {
			if (endPage < totalPages - 1) {
				html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
			}
			html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="new_filter(${totalPages})">${totalPages}</a></li>`;
		}
		
		if (currentPage < totalPages) {
			html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="new_filter(${currentPage + 1})"><i class="fas fa-chevron-right"></i></a></li>`;
		}
		
		html += '</ul></nav>';
		html += `<div class="text-muted small text-center mt-2"><?= __('admin.showing') ?> ${((currentPage - 1) * perPage) + 1} <?= __('admin.to') ?> ${Math.min(currentPage * perPage, totalRows)} <?= __('admin.of') ?> ${totalRows} <?= __('admin.entries') ?></div>`;
		
		return html;
	}

	new_filter();


	function old_filter() {
		$.ajax({
			type:'POST',
			dataType:'json',
			data:$("#old_filter").serialize(),
			beforeSend:function(){
				$('.btn-old-filter').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
			},
			complete:function(){
				$('.btn-old-filter').prop('disabled', false).html($(this).data('original-text') || 'Submit');
			},
			success:function(json){
				if(json['html']){
					$(".old-datatable tbody").html(json['html']);
					$(".old-empty").addClass('d-none');
					$(".old-datatable").show();
				} else{
					$(".old-datatable").hide();
					$(".old-empty").removeClass('d-none');
				}
			},
		})

		return false;
	} old_filter();

        $(".filter-toggle").on("click", function(){
            const $filter = $("#new_filter");
            const $icon = $(this).find("i");
            
            $filter.toggleClass('d-none');
            $icon.toggleClass('fa-filter fa-filter-circle-xmark');
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
            format: 'DD-M-YYYY'
        }
    });
	
	$('.daterange-picker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-M-YYYY') + ' - ' + picker.endDate.format('DD-M-YYYY'));
    });
    
    $('.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $(document).on("click", ".trans_ids", function(){
    	var trans_ids = $(this).data('trans_ids')
    	$("#transIds .transaction-ids-content").text(trans_ids);
    	$("#transIds").modal('show');
    })

	$(document).delegate(".btn-deletes",'click',function(){
		const $this = $(this);
		const requestId = $this.data("id");

		Swal.fire({
			title: '<?= __('admin.are_you_sure') ?>',
			text: '<?= __('admin.comission_will_revert_back_to_user_wallet') ?>',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: '<?= __('admin.yes_revert') ?>',
			cancelButtonText: '<?= __('admin.cancel') ?>'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					type:'POST',
					dataType:'json',
					data:{delete_request: true, id: requestId},
					beforeSend:function(){ 
						$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.processing') ?>...'); 
					},
					complete:function(){ 
						$this.prop('disabled', false).html('<i class="fas fa-undo me-1"></i><?= __('admin.revert_to_wallet') ?>'); 
					},
					success:function(json){
						if (json['error']) {
							showToast('<?= __('admin.error') ?>', json['error'], 'error', 5000);
						}
						if (json['success']) {
							$this.parents("tr").fadeOut(300, function() {
								$(this).remove();
								updatePagination();
							});
							showToast('<?= __('admin.success') ?>', '<?= __('admin.comission_is_reverted_back_to_user_wallet') ?>', 'success', 3000);
						}
					},
					error: function() {
						showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_process_request') ?>', 'error', 5000);
					}
				})
			}
		})
	});

</script>