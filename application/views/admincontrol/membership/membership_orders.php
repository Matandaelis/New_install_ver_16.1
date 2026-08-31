<div class="container-fluid px-4 pb-4">
  <div class="row">
    <div class="col-12">

      <?php get_instance()->load->view('admincontrol/membership/_membership_nav'); ?>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><?= __('admin.membership_dashboard') ?></h4>
      </div>

      <!-- Minimalist Summary Cards -->
      <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 bg-primary bg-opacity-10 h-100">
            <div class="card-body text-center p-3">
              <i class="fas fa-calendar-week text-primary fs-4 mb-2"></i>
              <h5 class="fw-bold text-primary mb-1"><?= $dashboard_totals->week_ago_total_orders ?></h5>
              <small class="text-muted"><?= __('admin.last_7_days') ?></small>
              <div class="mt-1">
                <small class="text-success fw-semibold"><?= c_format($dashboard_totals->week_ago_total_order_amount) ?></small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 bg-success bg-opacity-10 h-100">
            <div class="card-body text-center p-3">
              <i class="fas fa-calendar-alt text-success fs-4 mb-2"></i>
              <h5 class="fw-bold text-success mb-1"><?= $dashboard_totals->month_ago_total_orders ?></h5>
              <small class="text-muted"><?= __('admin.last_30_days') ?></small>
              <div class="mt-1">
                <small class="text-success fw-semibold"><?= c_format($dashboard_totals->month_ago_total_order_amount) ?></small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 bg-info bg-opacity-10 h-100">
            <div class="card-body text-center p-3">
              <i class="fas fa-calendar text-info fs-4 mb-2"></i>
              <h5 class="fw-bold text-info mb-1"><?= $dashboard_totals->year_ago_total_orders ?></h5>
              <small class="text-muted"><?= __('admin.this_year') ?></small>
              <div class="mt-1">
                <small class="text-success fw-semibold"><?= c_format($dashboard_totals->year_ago_total_order_amount) ?></small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 bg-warning bg-opacity-10 h-100">
            <div class="card-body text-center p-3">
              <i class="fas fa-infinity text-warning fs-4 mb-2"></i>
              <h5 class="fw-bold text-warning mb-1"><?= $dashboard_totals->all_time_total_orders ?></h5>
              <small class="text-muted"><?= __('admin.all_time') ?></small>
              <div class="mt-1">
                <small class="text-success fw-semibold"><?= c_format($dashboard_totals->all_time_total_order_amount) ?></small>
              </div>
            </div>
          </div>
        </div>
      </div>

<div class="row mt-4">
    <div class="col-xl-12">
		<div class="card border-0 shadow-sm">
			<div class="card-header bg-light border-0 py-3">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title mb-0">
						<i class="fas fa-history text-info me-2"></i>
						<?= __('admin.purchase_history') ?>
					</h5>
					<div class="d-flex align-items-center">
						<span class="badge bg-primary me-2"><?= count($plans) ?> <?= __('admin.total_orders') ?></span>
						<button type="button" class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
							<i class="fas fa-sync-alt me-1"></i><?= __('admin.refresh') ?>
						</button>
					</div>
				</div>
			</div>
			<div class="card-body">
                <form class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><?= __('admin.filter_by_status') ?></label>
                            <select class="form-select filter_status" name="status_id">
                                <option value=""><?= __('admin.all_statuses') ?></option>
                                <?php foreach (App\MembershipPlan::$lang_status_list as $key => $value) { ?>
                                    <option <?= (isset($_GET['status_id']) && $_GET['status_id'] != '' && $_GET['status_id'] == $key) ? 'selected' : '' ?> value="<?= $key ?>"><?= __('admin.'.$value) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><?= __('admin.filter_by_user') ?></label>
                            <select class="form-select" name="user_id">
                                <option value=""><?= __('admin.all_users') ?></option>
                                <?php foreach ($users as $key => $value) { ?>
                                <option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><?= __('admin.filter_by_date') ?></label>
                            <input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker" placeholder='<?= __('admin.select_date_range') ?>'>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary searchbtn-membership">
                                    <i class="fas fa-search me-2"></i><?= __('admin.search') ?>
                                </button>
                                <button type="button" class="btn btn-delete-multiple d-none btn-danger">
                                    <i class="fas fa-trash me-2"></i><?= __('admin.delete_selected') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
			<div class="card-body p-0">
				<form id="delete-form" method="post" action="<?= base_url('membership/odrer_plan_delete_multiple/') ?>">
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead class="table-light">
								<tr>
									<th width="50" class="border-0">
										<input type="checkbox" class="form-check-input select-all">
									</th>
									<th class="border-0 fw-semibold"><?= __('admin.id') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.username') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.plan_name') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.price') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.type') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.plan_status') ?></th> 
									<th class="border-0 fw-semibold"><?= __('admin.payment_method') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.remaining_time') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.start_date') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.end_date') ?></th>
									<th class="border-0 fw-semibold"><?= __('admin.created_at') ?></th>
									<th class="border-0 fw-semibold text-center"><?= __('admin.action') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if(count($plans) == 0){ ?>
									<tr>
										<td colspan="13" class="text-center py-5">
											<div class="text-muted">
												<i class="fas fa-shopping-cart fa-3x mb-3 d-block"></i>
												<h5><?= __('admin.no_records_found') ?></h5>
												<p><?= __('admin.no_membership_orders_found') ?></p>
											</div>
										</td>
									</tr>
								<?php } ?>
								<?php foreach ($plans as $key => $plan) { ?>
									<tr>
										<td class="align-middle">
											<?php if($plan->is_active == 0){ ?>
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="delete[]" value="<?= $plan->id ?>">
												</div>
											<?php } ?>
										</td>
										<td class="align-middle">
											<span class="badge bg-light text-dark border">#<?= $plan->id ?></span>
										</td>
										<td class="align-middle">
											<strong><?= ($plan->user ? $plan->user->username : '') ?></strong>
										</td>
										<td class="align-middle">
											<strong><?= ($plan->plan ? $plan->plan->name : '') ?></strong>
										</td>
										<td class="align-middle">
											<strong class="text-success"><?= c_format($plan->total) ?></strong>
										</td>
										<td class="align-middle">
											<?php   
												if ($plan->plan) {
													if ($plan->plan->type == 'paid') {
														echo '<span class="badge bg-primary">' . __('admin.paid') . '</span>';
													}elseif ($plan->plan->type == 'free') {
														echo '<span class="badge bg-success">' . __('admin.free') . '</span>';
													}else{
														echo '<span class="badge bg-secondary">' . $plan->plan->type . '</span>';
													}
												}else{
													echo '<span class="badge bg-secondary">-</span>';
												}
											?>
										</td>
										<td class="align-middle">
											<?= $plan->active_text ?>
										</td> 
										<td class="align-middle">
											<small class="d-block">
												<?php  
													if ($plan->payment_method == 'Free by Admin') {
														echo '<span class="badge bg-info">' . __('admin.free_by_admin') . '</span>';
													}else{
														echo '<span class="badge bg-secondary">' . $plan->payment_method . '</span>';
													}
												?>
											</small>
											<?php if($plan->payment_details){ 
												$payment_details = json_decode($plan->payment_details, true);
												if(isset($payment_details['transaction_id'])){ ?>
													<small class="d-block text-muted">
														<strong><?= __('admin.transaction_id') ?>:</strong> <?= $payment_details['transaction_id']; ?>
													</small>
												<?php } ?>

												<?php if(isset($payment_details['payment_status'])){ ?>
													<small class="d-block">
														<span class="badge <?php if(in_array(strtolower($payment_details['payment_status']), array('completed','succeeded','succcess','paid','active'))) { ?>bg-success<?php }else{ ?>bg-danger<?php } ?>">
														<?php
															if (ucfirst($payment_details['payment_status']) == 'Succeeded') {
																echo __('admin.successed');
															}elseif (ucfirst($payment_details['payment_status']) == 'Pending') {
																echo __('admin.pending');
															}else{
																echo ucfirst($payment_details['payment_status']);
															}
														?>
														</span>
													</small>
												<?php } ?>
											<?php } ?>
										</td>
										<td class="align-middle">
											<small class="text-danger"><?= $plan->remainDay() ?></small>
										</td>
										<td class="align-middle">
											<small><?= dateFormat($plan->started_at,'d/m/Y') ?></small>
										</td>
										<td class="align-middle">
											<small><?= $plan->expire_text ?></small>
										</td>
										<td class="align-middle">
											<small class="text-muted"><?= dateFormat($plan->created_at) ?></small>
										</td>
										<td class="align-middle text-center">
											<div class="btn-group" role="group">
												<a href="<?= base_url('membership/membership_purchase_edit/'. $plan->id) ?>" class="btn btn-outline-primary btn-sm" title="<?= __('admin.edit') ?>">
													<i class="fas fa-edit"></i>
												</a>
												<?php if($plan->is_active == 0){ ?>
													<button type="button" onclick="delete_confirm('<?= base_url('membership/odrer_plan_delete/'. $plan->id) ?>')" class="btn btn-outline-danger btn-sm" title="<?= __('admin.delete') ?>">
														<i class="fas fa-trash"></i>
													</button>
												<?php } ?>
											</div>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>
			</div>

			<?php if(isset($pagination) && $pagination){ ?>
				<div class="card-footer bg-light border-0">
					<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
						<div class="text-muted">
							<?= $pagination_summary ?>
						</div>
						<?= $pagination ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

    </div>
  </div>
</div>

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css" />

<script type="text/javascript">

    $(".single-check").change(buttonToggle);

    $(".select-all").change(function(){
        $(".single-check").prop("checked", $(this).prop("checked"))
        buttonToggle();
    })

    $(".btn-delete-multiple").click(function(){
        Swal.fire({
            title: '<?= __('admin.are_you_sure') ?>',
            text: "<?= __('admin.you_want_be_able_to_revert_this') ?>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<?= __('admin.yes_delete_it') ?>',
            cancelButtonText: '<?= __('admin.no_cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $("#delete-form")[0].submit();
            }
        })
    })

    function buttonToggle() {
        if($(".single-check:checked").length){
            $(".btn-delete-multiple").removeClass("d-none")
        } else {
            $(".btn-delete-multiple").addClass("d-none")
        }
    }

    function delete_confirm(url) {
        Swal.fire({
            title: '<?= __('admin.are_you_sure') ?>',
            text: "<?= __('admin.you_want_be_able_to_revert_this') ?>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<?= __('admin.yes_delete_it') ?>',
            cancelButtonText: '<?= __('admin.no_cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                window.location.href = url;
            }
        })
        return false;
    }

    $('[name="user_id"]').select2();

    $('.daterange-picker').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        ranges: {
            '<?= __('admin.today') ?>': [moment(), moment()],
            '<?= __('admin.yesterday') ?>': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '<?= __('admin.last_7_days') ?>': [moment().subtract(6, 'days'), moment()],
            '<?= __('admin.last_30_days') ?>': [moment().subtract(29, 'days'), moment()],
            '<?= __('admin.this_month') ?>': [moment().startOf('month'), moment().endOf('month')],
            '<?= __('admin.last_month') ?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
</script>

        </div>
    </div>
</div>