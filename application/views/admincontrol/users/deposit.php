<?php if($saas_status){ ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css?v=<?= av() ?>" />

<div class="container-fluid px-4 pb-4">
	<?php $this->load->view('admincontrol/setting/_saas_nav'); ?>
	<div class="row">
		<div class="col-12">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h4 class="fw-bold mb-0"><?= __('admin.vendor_deposit') ?></h4>
				<span class="badge bg-success text-white px-3 py-2">
					<i class="fas fa-dollar-sign me-1"></i><?= __('admin.total_deposited_amount') ?>: <?php echo c_format($total_deposited); ?>
				</span>
			</div>

			<div class="card shadow-sm border-0">
				<div class="card-body p-4">
					<form method="GET" onsubmit="return deposit_filter()" id="deposit_filter">
						<input type="hidden" name="get_deposit" value="1">
						<div class="row g-3 mb-4">
							<div class="col-md-4">
								<label class="form-label fw-semibold"><?= __('admin.filter_by_vendor') ?></label>
								<select class="form-select" name="user_id">
									<option value=""><?= __('admin.all_vendors') ?></option>
									<?php foreach ($users as $key => $value) { ?>
										<option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['username'] ?></option>	
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4">
								<label class="form-label fw-semibold"><?= __('admin.filter_by_date') ?></label>
								<input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker" placeholder='<?= __('admin.select_date_range') ?>'>
							</div>
							<div class="col-md-4">
								<label class="form-label fw-semibold">&nbsp;</label>
								<div class="d-grid">
									<button type="submit" class="btn btn-primary btn-new-filter">
										<i class="fas fa-search me-2"></i><?= __('admin.filter') ?>
									</button>
								</div>
							</div>
						</div>
					</form>

					<div class="new-empty text-center d-none">
						<div class="d-flex justify-content-center align-items-center flex-column py-5">
							<i class="fas fa-exchange-alt fa-5x text-muted mb-4"></i>
							<h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
							<p class="text-muted"><?= __('admin.no_deposit_records_found') ?></p>
						</div>
					</div>

					<div class="table-responsive deposit-datatable">
						<table class="table table-hover align-middle">
							<thead class="table-light">
								<tr>
									<th class="border-0 py-3 px-4">
										<i class="fas fa-hashtag me-2 text-muted"></i><?= __('admin.id') ?>
									</th>
									<th class="border-0 py-3 px-4">
										<i class="fas fa-user me-2 text-muted"></i><?= __('admin.user') ?>
									</th>
									<th class="border-0 py-3 px-4">
										<i class="fas fa-calendar me-2 text-muted"></i><?= __('admin.date') ?>
									</th>
									<th class="border-0 py-3 px-4">
										<i class="fas fa-credit-card me-2 text-muted"></i><?= __('admin.payment_method') ?>
									</th>
									<th class="border-0 py-3 px-4">
										<i class="fas fa-receipt me-2 text-muted"></i><?= __('admin.transactions_ids') ?>
									</th>
									<th class="border-0 py-3 px-4 text-end">
										<i class="fas fa-dollar-sign me-2 text-muted"></i><?= __('admin.total') ?>
									</th>
									<th class="border-0 py-3 px-4 text-center">
										<i class="fas fa-info-circle me-2 text-muted"></i><?= __('admin.status') ?>
									</th>
									<th class="border-0 py-3 px-4 text-center">
										<i class="fas fa-cog me-2 text-muted"></i><?= __('admin.action') ?>
									</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>

					<div class="d-flex justify-content-between align-items-center mt-4">
						<div class="pagination-summary text-muted">
						</div>
						<div class="pagination-container">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		deposit_filter();
		initializeDatePicker();
	});

	function deposit_filter() {
		$.ajax({
			type:'POST',
			dataType:'json',
			data:$("#deposit_filter").serialize(),
			beforeSend:function(){
				$('.btn-new-filter').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
			},
			complete:function(){
				$('.btn-new-filter').prop('disabled', false).html('<i class="fas fa-search me-2"></i><?= __('admin.filter') ?>');
			},
			success:function(json){
				if(json['html']){
					$(".deposit-datatable tbody").html(json['html']);
					$(".new-empty").addClass('d-none');
					$(".deposit-datatable").show();
					
					// Update pagination if available
					if(json['pagination']) {
						$(".pagination-container").html(json['pagination']);
					}
					
					// Update pagination summary if available
					if(json['pagination_summary']) {
						$(".pagination-summary").html(json['pagination_summary']);
					}
				} else{
					$(".deposit-datatable").hide();
					$(".new-empty").removeClass('d-none');
					$(".pagination-container").html('');
					$(".pagination-summary").html('');
				}
			},
			error: function() {
				showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_data') ?>', 'error', 4000);
			}
		})
		return false;
	}

	function initializeDatePicker() {
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
				cancelLabel: '<?= __('admin.clear') ?>',
				format: 'DD-M-YYYY'
			}
		});
		
		$('.daterange-picker').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('DD-M-YYYY') + ' - ' + picker.endDate.format('DD-M-YYYY'));
		});
		
		$('.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});
	}

	$(document).delegate(".btn-delete-deposit",'click',function(){
		$this = $(this);

		Swal.fire({
			title: '<?= __('admin.are_you_sure') ?>',
			text: '<?= __('admin.this_action_cannot_be_undone') ?>',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '<?= __('admin.yes_delete') ?>',
			cancelButtonText: '<?= __('admin.cancel') ?>'
		}).then((result) => {
			if(result.value){
				$.ajax({
					type:'POST',
					dataType:'json',
					data:{delete_request: true,id:$this.data("id")},
					beforeSend: function() {
						$this.prop('disabled', true);
					},
					success:function(json){
						Swal.fire({
							title: json.title,
							text: json.message,
							icon: json.type,
						})

						if(json.type == 'success') {
							$this.parents("tr").fadeOut(500, function() {
								$(this).remove();
							});
							showToast('<?= __('admin.success') ?>', json.message, 'success', 3000);
						}
					},
					error: function() {
						showToast('<?= __('admin.error') ?>', '<?= __('admin.something_went_wrong') ?>', 'error', 4000);
					},
					complete: function() {
						$this.prop('disabled', false);
					}
				})
			}
		})
	});

	// Pagination handler
	$(document).delegate(".pagination a",'click',function(e){
		e.preventDefault();
		var page = $(this).data('page');
		if(page) {
			deposit_filter();
		}
	});
</script>
<?php } else { ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="alert alert-info">
				<div class="d-flex align-items-center">
					<i class="fas fa-info-circle me-3" style="font-size: 1.5rem;"></i>
					<div>
						<h5 class="mb-1"><?= __('admin.saas_module_is_off') ?></h5>
						<p class="mb-0"><?= __('admin.admin_click_here_to_activate') ?> <a href="<?= base_url('admincontrol/addons') ?>" class="alert-link"><?= __('admin.click_here') ?></a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
