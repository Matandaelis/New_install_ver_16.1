<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
?>
<div class="container-fluid px-4 pb-4">
  <?php $this->load->view('admincontrol/users/_wallet_nav'); ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><?= __('admin.menu_admin_wallet') ?></h4>
  </div>
</div>

<div class="container-fluid px-4">
  <div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="bg-primary bg-gradient rounded-3 p-3">
                <i class="mdi mdi-wallet text-white fs-4"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="text-muted mb-1 text-uppercase fw-semibold"><?= __('admin.admin_balance') ?></h6>
              <h4 class="mb-0 fw-bold text-dark"><?= c_format($admin_totals['admin_balance']) ?></h4>
              <small class="text-muted"><?= __('admin.total_admin_balance') ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="bg-success bg-gradient rounded-3 p-3">
                <i class="mdi mdi-chart-line text-white fs-4"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="text-muted mb-1 text-uppercase fw-semibold"><?= __('admin.total_sales') ?></h6>
              <div class="row g-0">
                <div class="col-6">
                  <h5 class="mb-0 fw-bold text-dark"><?= c_format($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total']) ?></h5>
                  <small class="text-muted"><?= __('admin.admin_store') ?></small>
                </div>
                <div class="col-6">
                  <h5 class="mb-0 fw-bold text-dark"><?= c_format($admin_totals['sale_localstore_vendor_total']) ?></h5>
                  <small class="text-muted"><?= __('admin.vendor_store') ?></small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="bg-info bg-gradient rounded-3 p-3">
                <i class="mdi mdi-lightning-bolt text-white fs-4"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="text-muted mb-1 text-uppercase fw-semibold"><?= __('admin.actions') ?></h6>
              <h4 class="mb-0 fw-bold text-dark"><?= (int)$admin_totals['click_action_total'] ?></h4>
              <small class="text-muted"><?= c_format($admin_totals['click_action_commission']) ?> <?= __('admin.all_actions') ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="bg-warning bg-gradient rounded-3 p-3">
                <i class="mdi mdi-cursor-pointer text-white fs-4"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="text-muted mb-1 text-uppercase fw-semibold"><?= __('admin.clicks') ?></h6>
              <h4 class="mb-0 fw-bold text-dark"><?= (int)($admin_totals['click_localstore_total'] + $admin_totals['click_integration_total'] + $admin_totals['click_form_total']) ?></h4>
              <small class="text-muted"><?= c_format($admin_totals['click_localstore_commission'] + $admin_totals['click_integration_commission'] + $admin_totals['click_form_commission']) ?> <?= __('admin.all_clicks') ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-white border-bottom-0">
					<div class="d-flex justify-content-between align-items-center">
						<div class="d-flex align-items-center">
							<h5 class="card-title mb-0 fw-semibold text-dark">
								<i class="mdi mdi-filter-variant me-2"></i><?= __('admin.filter_transactions') ?>
							</h5>
						</div>
						<div class="d-flex gap-2">
							<button class="filter-toggle btn btn-outline-primary btn-sm">
								<i class="mdi mdi-tune me-1"></i><?= __('admin.filter') ?>
							</button>
							<button class="btn btn-outline-danger btn-sm delete-multiple d-none" type="button">
								<i class="mdi mdi-delete me-1"></i><?= __('admin.delete_selected') ?> (<span class="selected-count">0</span>)
							</button>
						</div>
					</div>
					
					<form method="GET" class="wallet-filter mt-3 d-none">
						<div class="row g-3">
							<div class="col-lg-3 col-md-6">
								<select class="form-select" name="user_id">
									<option value=""><?= __('admin.filter_by_user') ?></option>
									<?php foreach ($users as $key => $value) { ?>
										<option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>">
											<?= $value['name'] ?>
										</option>
									<?php } ?>
								</select>
							</div>
							
							<div class="col-lg-3 col-md-6">
								<input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker" placeholder='<?= __('admin.filter_by_date') ?>'>
							</div>

							<div class="col-lg-3 col-md-6">
								<select class="form-select" name="status">
									<option value=""><?= __('admin.filter_by_status') ?></option>
									<option value="0" <?= isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' ?>><?= __('admin.on_hold') ?></option>
									<option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>><?= __('admin.in_wallet') ?></option>
									<option value="2" <?= isset($_GET['status']) && $_GET['status'] == '2' ? 'selected' : '' ?>><?= __('admin.request_send') ?></option>
									<option value="3" <?= isset($_GET['status']) && $_GET['status'] == '3' ? 'selected' : '' ?>><?= __('admin.accept') ?></option>
									<option value="4" <?= isset($_GET['status']) && $_GET['status'] == '4' ? 'selected' : '' ?>><?= __('admin.reject') ?></option>
								</select>
							</div>

							<div class="col-lg-3 col-md-6">
								<select class="form-select" name="recurring">
									<option value=""><?= __('admin.filter_by_recurring_transaction') ?></option>
									<option value="0" <?= isset($_GET['recurring']) && $_GET['recurring'] == '0' ? 'selected' : '' ?>><?= __('admin.not_recurring') ?></option>
									<option value="1" <?= isset($_GET['recurring']) && $_GET['recurring'] == '1' ? 'selected' : '' ?>><?= __('admin.recurring') ?></option>
								</select>
							</div>

							<div class="col-lg-3 col-md-6">
								<select class="form-select" name="type">
									<option value=""><?= __('admin.filter_by_type') ?></option>
									<option value="actions" <?= isset($_GET['type']) && $_GET['type'] == 'actions' ? 'selected' : '' ?>><?= __('admin.actions') ?></option>
									<option value="clicks" <?= isset($_GET['type']) && $_GET['type'] == 'clicks' ? 'selected' : '' ?>><?= __('admin.clicks') ?></option>
									<option value="sale" <?= isset($_GET['type']) && $_GET['type'] == 'sale' ? 'selected' : '' ?>><?= __('admin.sale') ?></option>
									<option value="external_integration" <?= isset($_GET['type']) && $_GET['type'] == 'external_integration' ? 'selected' : '' ?>><?= __('admin.external_integration') ?></option>
									<option value="shipping_reimbursement" <?= isset($_GET['type']) && $_GET['type'] == 'shipping_reimbursement' ? 'selected' : '' ?>><?= __('admin.shipping_reimbursement') ?></option>
								</select>
							</div>

							<div class="col-lg-3 col-md-6">
								<select class="form-select" name="paid_status">
									<option value=""><?= __('admin.filter_by_paid_type') ?></option>
									<option value="paid" <?= isset($_GET['paid_status']) && $_GET['paid_status'] == 'paid' ? 'selected' : '' ?>><?= __('admin.paid') ?></option>
									<option value="unpaid" <?= isset($_GET['paid_status']) && $_GET['paid_status'] == 'unpaid' ? 'selected' : '' ?>><?= __('admin.unpaid') ?></option>
								</select>
							</div>

							<div class="col-lg-3 col-md-6">
								<select class="form-select" name="amount_sort">
									<option value=""><?= __('admin.filter_by_commission_amount') ?></option>
									<option value="high_to_low" <?= isset($_GET['amount_sort']) && $_GET['amount_sort'] == 'high_to_low' ? 'selected' : '' ?>><?= __('admin.commission_high_to_low') ?></option>
									<option value="low_to_high" <?= isset($_GET['amount_sort']) && $_GET['amount_sort'] == 'low_to_high' ? 'selected' : '' ?>><?= __('admin.commission_low_to_high') ?></option>
								</select>
							</div>

							<div class="col-lg-6 col-md-12">
								<div class="d-flex gap-2 align-items-end h-100 pb-1">
									<button type="submit" class="btn btn-primary">
										<i class="mdi mdi-magnify me-1"></i><?= __('admin.filter') ?>
									</button>
									<a href="<?= base_url('admincontrol/mywallet') ?>" class="btn btn-outline-secondary">
										<i class="mdi mdi-refresh me-1"></i><?= __('admin.clear') ?>
									</a>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="card-body p-0">
					<div class="showmessage"></div>
					<?php if ($transaction == null) { ?>
						<div class="d-flex justify-content-center align-items-center flex-column py-5">
							<div class="bg-light rounded-circle p-4 mb-3">
								<i class="mdi mdi-wallet-outline text-muted" style="font-size: 3rem;"></i>
							</div>
							<h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
							<p class="text-muted mb-0"><?= __('admin.no_transactions_found_matching_criteria') ?></p>
						</div>
					<?php } else { ?>
						<link rel="stylesheet" type="text/css" href="<?= base_url('assets/template/css/wallet.css?v='. time()) ?>">
						<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/flag/css/main.min.css?v='. time()) ?>">

						<div class="wallet-table-container">
							<div class="table-responsive">
								<table class="table table-hover transaction-table mb-0">
								<thead class="table-light wallet-transaction-thead">
									<tr>
										<th class="border-0">
											<div class="form-check">
												<input class="form-check-input selectall-wallet-checkbox" type="checkbox" id="selectAll">
												<label class="form-check-label" for="selectAll"></label>
											</div>
										</th>
										<th class="border-0"></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.id') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.date') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.owner') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.user') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.integration_type') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.commission') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.commission_owner') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.payment') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.status') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.actions') ?></th>
										<th class="border-0 fw-semibold text-dark"><?= __('admin.automation') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$group_changed = 1;
										$html = '';
										$lastRow = count($transaction) - 1;

										foreach ($transaction as $key => $value) { 
											$class = '';
											if ($current_group_id && $current_group_id == $value['group_id']) {
												$class = 'child';
											} else {
												$current_group_id = $value['group_id'];
												$group_changed = 1;
											}
											
											$value['wallet_recursion_endtime'] = (!empty($value['wallet_recursion_endtime']) && $value['wallet_recursion_endtime'] != '0000-00-00 00:00:00') ? $value['wallet_recursion_endtime'] : null;
											
											$data = [];
											$data['value'] = $value;
											$data['userdetails'] = $userdetails;
											$data['class'] = $class;
											$data['wallet_status'] = $status;
											// Check if current row is a parent by checking if next row has same group_id but is not itself
											$data['has_child'] = (isset($transaction[$key+1]) && $transaction[$key+1]['group_id'] && $transaction[$key+1]['group_id'] == $value['group_id'] && $transaction[$key+1]['id'] != $value['id']) ? 1 : 0;
											$data['child_id'] = (isset($transaction[$key+1]) && $transaction[$key+1]['group_id'] && $transaction[$key+1]['group_id'] == $value['group_id'] && $transaction[$key+1]['id'] != $value['id']) ? $transaction[$key+1]['id'] : null;
											
											$html .= $this->Product_model->getHtml('admincontrol/users/part/new_wallet_tr', $data);

											if ($group_changed || $lastRow == $key) {
												echo $html;
												$html = '';
												$group_changed = 0;
											}
										} 
									?>
								</tbody>
							</table>
							</div>
							
							<?php if (!empty($pagination_link)) { ?>
							<div class="card-footer bg-light border-top">
								<div class="d-flex justify-content-between align-items-center">
									<div class="text-muted small">
										<i class="fas fa-info-circle me-1"></i>
										<?= pagination_summary_html(($filter['page_num'] ?? ($current_page ?? 1)), ($per_page ?? ($pagination_settings['per_page'] ?? 5)), ($totals_count ?? $total_rows ?? count($transaction)), 'start', 'sm') ?>
									</div>
									<div class="pagination-wrapper">
										<?= $pagination_link ?>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-completed" tabindex="-1" aria-labelledby="modalCompletedLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 shadow">
			<div class="modal-header bg-success text-white border-0">
				<h5 class="modal-title fw-semibold" id="modalCompletedLabel">
					<i class="mdi mdi-check-circle me-2"></i><?= __('admin.payment_completed') ?>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="d-flex align-items-center">
					<div class="flex-shrink-0">
						<div class="bg-success bg-opacity-10 rounded-circle p-3">
							<i class="mdi mdi-information text-success fs-4"></i>
						</div>
					</div>
					<div class="flex-grow-1 ms-3">
						<p class="mb-0"><?= __('admin.transaction_status_can_change_revert') ?></p>
					</div>
				</div>
			</div>
			<div class="modal-footer border-0 bg-light">
				<button type="button" class="btn btn-primary" data-bs-dismiss="modal">
					<i class="mdi mdi-check me-1"></i><?= __('admin.close') ?>
				</button>
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

<div class="modal fade" id="wallet-details-model" tabindex="-1" aria-labelledby="walletDetailsLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content border-0 shadow">
			<div class="modal-header bg-primary text-white border-0">
				<h5 class="modal-title fw-semibold" id="walletDetailsLabel">
					<i class="mdi mdi-receipt me-2"></i><?= __('admin.order_details') ?>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="delete-wallet-record-modal" tabindex="-1" aria-labelledby="deleteWalletLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 shadow">
			<div class="modal-header bg-warning text-dark border-0">
				<h5 class="modal-title fw-semibold" id="deleteWalletLabel">
					<i class="mdi mdi-alert me-2"></i><?= __('admin.wallet_notification_info') ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="d-flex align-items-center">
					<div class="flex-shrink-0">
						<div class="bg-warning bg-opacity-10 rounded-circle p-3">
							<i class="mdi mdi-alert-circle text-warning fs-4"></i>
						</div>
					</div>
					<div class="flex-grow-1 ms-3">
						<h6 class="mb-1 fw-semibold"><?= __('admin.selection_required') ?></h6>
						<p class="mb-0 text-muted"><?= __('admin.please_select_at_least_one_wallet_record') ?></p>
					</div>
				</div>
			</div>
			<div class="modal-footer border-0 bg-light">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					<i class="mdi mdi-close me-1"></i><?= __('admin.close') ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css" />

<style>
	#modal-confirmstatus .modal-body h5 {
		color: #0d6efd;
		font-weight: 600;
		margin-bottom: 1rem;
	}
	#modal-confirmstatus .modal-body table {
		width: 100%;
		margin-bottom: 1rem;
	}
	#modal-confirmstatus .modal-body table th,
	#modal-confirmstatus .modal-body table td {
		padding: 0.5rem;
		border-bottom: 1px solid #dee2e6;
	}
	#modal-confirmstatus .modal-body table th {
		background-color: #f8f9fa;
		font-weight: 600;
	}
	#modal-confirmstatus .modal-body .btn {
		margin: 0.25rem;
	}
	#modal-confirmstatus .modal-body .btn-group {
		display: flex;
		gap: 0.5rem;
		justify-content: center;
		margin-top: 1rem;
	}
</style>

<script type="text/javascript">

	$(document).on('click', '.view-tran-details', function () {
		
		let data = {
			type : $(this).data('comm_from'),
			ref1 : $(this).data('ref_id_1'),
			ref2 : $(this).data('ref_id_2')
		};

		$.ajax({
			url:'<?= base_url('admincontrol/getOrderDetails') ?>',
			type:'POST',
			dataType:'html',
			data:data,
			success:function(response){
				$('#wallet-details-model .modal-body').html(response);
				$('#wallet-details-model').modal('show');
			},
		});
	});


	$(document).delegate(".show-child-transaction","click",function(){

		$tr = $(this).parents("tr");
		var status = $(this).find("i").hasClass('fa-angle-down') ? 1 : 0;
		var group_id = $tr.attr("group_id");
		
		if(status){
			$('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring)').show();
			$(this).find("i").removeClass('fa-angle-down');
			$(this).find("i").addClass('fa-angle-up');
			$(this).siblings('.child-connector-line').show();
			$tr.addClass('opened')
			$('.transaction-table [group_id='+ group_id +']').addClass('highlight');
		} else{
			$('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring)').hide();
			$(this).siblings('.child-connector-line').hide();
			$(this).find("i").removeClass('fa-angle-up');
			$(this).find("i").addClass('fa-angle-down');
			$tr.removeClass('opened')
			$('.transaction-table [group_id='+ group_id +']').removeClass('highlight');
		}

		$('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring):last').addClass("last-group-row");
	});

	$(document).delegate(".show-recurring-transition","click",function(){
		$this = $(this);
		var id = $this.attr("data-id");
		$this.find("i").toggleClass("mdi-plus mdi-minus")
		$nextAll = $this.parents("tr").nextAll("tr.recurringof-"+id);

		$this.parents("tr").nextAll("tr.recurringof-"+id+":last").addClass('last-recurring');

		if($nextAll.length){
			if($nextAll.eq(0).css("display") == 'table-row'){
				$this.parents("tr").removeClass('opened-recurring');
				$nextAll.hide();
			} else {
				$this.parents("tr").addClass('opened-recurring');
				$nextAll.show();
			}
			return false;
		}

		$this.parents("tr").nextAll("tr.recurringof-"+id).remove();
		
		$.ajax({
			url:'<?= base_url('admincontrol/getRecurringTransaction') ?>',
			type:'POST',
			dataType:'json',
			data:{
				id:id,
				newtr:1,
				ischild:$this.parents("tr").hasClass("child-row")
			},
			beforeSend:function(){$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
			complete:function(){$this.prop('disabled', false).html('<i class="mdi mdi-check me-1"></i><?= __('admin.complete') ?>');},
			success:function(json){
				if(json['table']){
					$this.parents("tr").addClass('opened-recurring');
					$this.parents("tr").after(json['table']);
					$this.parents("tr").nextAll("tr.recurringof-"+id+":last").addClass('last-recurring');
					$(".wallet-popover").popover({
						placement : 'right',
						html : true,
					});
				}
			},
		})
	});

	$(".filter-toggle").on("click", function(){
		const $filter = $(".wallet-filter");
		const $icon = $(this).find("i");
		
		$filter.toggleClass('d-none');
		$icon.toggleClass('mdi-tune mdi-tune-vertical');
	});

	$(document).on('change', '.selectall-wallet-checkbox', function(){
		const isChecked = $(this).prop("checked");
		$(".wallet-checkbox").prop("checked", isChecked).trigger("change");
	});

	$(document).on('change', ".wallet-checkbox", function(){
		const checkedCount = $(".wallet-checkbox:checked").length;
		const $deleteBtn = $(".delete-multiple");
		const $selectedCount = $(".selected-count");
		
		if(checkedCount === 0){
			$deleteBtn.addClass('d-none');
		} else {
			$deleteBtn.removeClass('d-none');
			$selectedCount.text(checkedCount);
		}
	});

	$(".delete-multiple").on('click', function(){
		const ids = $(".wallet-checkbox:checked").map(function(){ return $(this).val(); }).toArray().join(",");
		
		if(ids){
			const $this = $(this);
			$.ajax({
				url: '<?php echo base_url("admincontrol/info_remove_tran_multiple") ?>',
				type: 'POST',
				dataType: 'json',
				data: {ids: ids},
				beforeSend: function(){ 
					$this.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i><?= __('admin.processing') ?>...'); 
				},
				complete: function(){ 
					$this.prop('disabled', false).html('<i class="mdi mdi-delete me-1"></i><?= __('admin.delete_selected') ?> (<span class="selected-count">' + $(".wallet-checkbox:checked").length + '</span>)'); 
				},
				success: function(json){
					$("#modal-confirm .modal-body").html(json['html']);
					$("#modal-confirm").modal("show");
				},
				error: function(){
					showToast('Error', '<?= __('admin.failed_to_process_delete_request') ?>', 'error', 3000);
				}
			});
		} else {
			$("#delete-wallet-record-modal").modal("show");
		}
	});

	$(document).on('click', "[delete-mmultiple-confirm]", function(){
		const $this = $(this);
		const ids = $this.attr("delete-mmultiple-confirm");
		
		$.ajax({
			url: '<?php echo base_url("admincontrol/confirm_remove_tran_multi") ?>',
			type: 'POST',
			dataType: 'json',
			data: {id: ids},
			beforeSend: function(){ 
				$this.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i><?= __('admin.deleting') ?>...'); 
			},
			success: function(json){
				$("#modal-confirm").modal("hide");
				showToast('Success', '<?= __('admin.selected_transactions_deleted_successfully') ?>', 'success', 3000);
				
				setTimeout(() => {
					window.location.reload();
				}, 1500);
			},
			error: function(){
				$this.prop('disabled', false);
				showToast('Error', '<?= __('admin.failed_to_delete_transactions') ?>', 'error', 3000);
			}
		});
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

	$(document).on('click', '.show-trans-aff-details', function() {
		$('.transaction-datails-div-hidden').toggle();
	});

	$(document).on('click', '.wallet-checkbox', function() {
		let curTR = $(this).closest('tr');
		
		if($(this).prop('checked')) {
			if(!$(curTR).hasClass('child-row')) {
				$("tr[group_id='"+$(curTR).attr('group_id')+"'].child-row").each(function( index ) {
					$( this ).find('.wallet-checkbox').prop('checked', true);
					$( this ).find('.wallet-checkbox').prop('disabled', true);
				});
			}
		} else {
			if($(curTR).hasClass('child-row')) {
				$("tr[group_id='"+$(curTR).attr('group_id')+"']:not(.child-row)").each(function( index ) {
					$( this ).find('.wallet-checkbox').prop('checked', false);
				});
			} else {
				$("tr[group_id='"+$(curTR).attr('group_id')+"'].child-row").each(function( index ) {
					$( this ).find('.wallet-checkbox').prop('checked', false);
					$( this ).find('.wallet-checkbox').prop('disabled', false);
				});
			}
		}
	});
	

	$(document).on('click', "[delete-tran-confirm]", function(){
		const $this = $(this);
		const id = $this.attr("delete-tran-confirm");
		
		$.ajax({
			url: '<?php echo base_url("admincontrol/confirm_remove_tran") ?>',
			type: 'POST',
			dataType: 'json',
			data: {id: id},
			beforeSend: function(){ 
				$this.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i><?= __('admin.deleting') ?>...'); 
			},
			success: function(json){
				$("#modal-confirm").modal("hide");
				showToast('Success', '<?= __('admin.transaction_deleted_successfully') ?>', 'success', 3000);
				
				setTimeout(() => {
					window.location.reload();
				}, 1500);
			},
			error: function(){
				$this.prop('disabled', false);
				showToast('Error', '<?= __('admin.failed_to_delete_transaction') ?>', 'error', 3000);
			}
		});
	});

	$(document).on('click', "[change-tran-by-commi-confirm]", function(){
		const $this = $(this);
		const status_type = $this.attr("status_type");
		const id = $this.attr("id");

		$.ajax({
			type: "POST",
			url: '<?php echo base_url("admincontrol/change_commission_status") ?>',
			data: {status_type: status_type, id: id},
			dataType: 'json',
			beforeSend: function(){ 
				$this.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i><?= __('admin.updating') ?>...'); 
			},
			success: function(data) {
				$("#modal-confirm").modal("hide");
				showToast('Success', '<?= __('admin.commission_status_updated_successfully') ?>', 'success', 3000);
				
				setTimeout(() => {
					window.location.reload();
				}, 1500);
			},
			error: function(){
				$this.prop('disabled', false);
				showToast('Error', '<?= __('admin.failed_to_update_commission_status') ?>', 'error', 3000);
			}
		});
	});
	
	$('[name="user_id"]').select2();


	/*open and close popover tooltip*/
	$(function () {
	  // Initialize the popover
	  var popover = $('[data-bs-toggle="popover"]').popover({
	    html: true,
	    trigger: 'click',
	    placement: 'top',
	    container: 'body'
	  });

	  // Add a click event listener to the document object
	  $(document).on('click', function (e) {
	    // Check if the clicked element is inside the popover or not
	    if (!$(e.target).closest('[data-bs-toggle="popover"]').length && !$(e.target).closest('.popover').length) {
	      // If it is not, hide the popover
	      $('[data-bs-toggle="popover"]').popover('hide');
	    }
	  });

	  // Add a click event listener to the popover
	  $('[data-bs-toggle="popover"]').on('click', function (e) {
	    e.preventDefault();
	    // Hide all other popovers except the current one
	    $('[data-bs-toggle="popover"]').not(this).popover('hide');
	    // Toggle current popover
	    $(this).popover('toggle');
	  });
	});

	/*open and close popover tooltip*/
	function changeStatus(el,id,status){
		let type = el.options[el.selectedIndex].dataset.type;

		if(status == 3 && type != 'recursion'){
			$("#modal-completed").modal("show");
			return false;
		}

		switch(type){
		  	case 'comission':
		    	infoRemoveTranByComission(el.value,id);
		    	break;
		  	case 'wallet':
		    	walletChangeStatus(el.value,id);
		    	break;
		    case 'remove':
		    	infoRemoveTransaction(id);
		    	break;
	    	case 'recursion':
	    		infoRecursionTransaction(id);
	    		break;
		  	default:
		    	return;
		}	
	}

	function infoRemoveTranByComission(value,id){
		$.ajax({
			url: '<?= base_url("admincontrol/info_remove_tran_by_commission") ?>',
			type:'POST',
			dataType:'json',
			data:{id:id,status_type:value},
			success:function(json){
				$("#modal-confirm .modal-body").html(json['html']);
				$("#modal-confirm").modal("show");
			},
		})
	}

	function walletChangeStatus(value, id){
		$.ajax({
			url: '<?= base_url("admincontrol/wallet_change_status") ?>',
			type: 'POST',
			dataType: 'json',
			data: {id: id, val: value},
			beforeSend: function(){
				showToast('<?= __('admin.processing') ?>', '<?= __('admin.updating_wallet_status') ?>', 'info', 2000);
			},
			success: function(json){
				if(json['ask_confirm']){
					// Just use the server-generated HTML as-is, but enhance the modal header
					$("#modal-confirmstatus .modal-body").html(json['html']);
					$("#modal-confirmstatus").modal('show');
				}
				if(json['success']){
					showToast('Success', '<?= __('admin.wallet_status_updated_successfully') ?>', 'success', 3000);
					setTimeout(() => {
						window.location.reload();
					}, 1500);
				}
			},
			error: function(){
				showToast('Error', '<?= __('admin.failed_to_update_wallet_status') ?>', 'error', 3000);
			}
		});
	}

	$("#modal-confirmstatus").delegate(".close-modal","click",function(){
		$("#modal-confirmstatus").modal("hide");
	});

	// Initialize tooltips for dynamically loaded content
	$(document).ready(function() {
		$('[data-bs-toggle="tooltip"]').tooltip();
	});

	// Re-initialize tooltips after AJAX content loads
	$(document).on('DOMNodeInserted', function() {
		$('[data-bs-toggle="tooltip"]').tooltip();
	});

	function infoRemoveTransaction(id){
		$.ajax({
			url: '<?= base_url("admincontrol/info_remove_tran") ?>',
			type:'POST',
			dataType:'json',
			data:{id:id},
			success:function(json){
				$("#modal-confirm .modal-body").html(json['html']);
				$("#modal-confirm").modal("show");
			},
		});
	}

	function infoRecursionTransaction(id){
		$.ajax({
			url: '<?= base_url("admincontrol/info_recursion_tran") ?>',
			type:'POST',
			dataType:'json',
			data:{id:id},
			success:function(json){
				$("#modal-recursion .modal-body").html(json['html']);
				$("#modal-recursion").modal("show");
				if( json['recursion_type'] == 'custom_time' ){
					$('.custom_time').show();
				}else{
					$('.custom_time').hide();
				}
			},
		})
	}


	
	$(document).ready(function(){
		const startTime = PerformanceMonitor.start('wallet-performance');
		
		setTimeout(() => {
			PerformanceMonitor.end(startTime, 'wallet-performance');
		}, 100);
	});
</script>

<?= performance_indicator_html('wallet-performance') ?>

<?= render_performance_indicator() ?>

        </div>
    </div>
</div>