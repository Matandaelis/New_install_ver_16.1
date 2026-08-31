<div class="container-fluid">
<div class="card shadow-sm">
	<div class="card-header bg-primary text-white">
		<h5 class="mb-0">
			<i class="fas fa-chart-bar me-2"></i><?= __('user.reports_analytics') ?>
		</h5>
	</div>
	<div class="card-body p-0">
		<ul class="nav nav-pills nav-fill border-bottom">
			<li class="nav-item">
				<a class="nav-link active rounded-0" data-bs-toggle="tab" href="#tab-menu_statistics">
					<i class="fas fa-chart-pie me-1"></i><?= __('user.menu_statistics') ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link rounded-0" data-bs-toggle="tab" href="#tab-menu_report_statistics">
					<i class="fas fa-table me-1"></i><?= __('user.menu_report_statistics') ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link rounded-0" data-bs-toggle="tab" href="#tab-menu_report_store_orders">
					<i class="fas fa-shopping-cart me-1"></i><?= __('user.my_all_orders') ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link rounded-0" data-bs-toggle="tab" href="#tab-menu_report_logs">
					<i class="fas fa-list me-1"></i><?= __('user.page_title_logs') ?>
				</a>
			</li>
		</ul>

		<div class="tab-content p-3">
			<div class="tab-pane active" id="tab-menu_statistics">
				<div class="card border-0">
					<div class="card-body">
						<div class="row mb-5">
							<div class="col-sm-4 mb-5">
								<div class="card">
									<div class="card-body">
										<h5 class="text-center mb-3">
										<span class="badge bg-primary rounded-pill"><?= (int)$statistics['clicks_count'] ?></span>
										<?= __('user.click_by_country') ?>
									</h5>
										<?php if((int)$statistics['clicks_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['clicks'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="clicks-chart"></div>
										<?php } else { ?>
											<div class="empty-graph">
												<div class="text-center mt-5">
													<div class="d-flex justify-content-center align-items-center flex-column mt-5">
														 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
														 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="col-sm-4 mb-5">
								<div class="card">
									<div class="card-body">
										<h5 class="text-center mb-3">
										<span class="badge bg-info rounded-pill"><?= (int)$statistics['action_clicks_count'] ?></span>
										<?= __('user.action_click_by_country') ?>
									</h5>
										<?php if((int)$statistics['action_clicks_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['action_clicks'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="action_click-chart"></div>
										<?php } else { ?>
											<div class="empty-graph">
												<div class="text-center mt-5">
													<div class="d-flex justify-content-center align-items-center flex-column mt-5">
														 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
														 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="col-sm-4 mb-5">
								<div class="card">
									<div class="card-body">
										<h5 class="text-center mb-3">
										<span class="badge bg-success rounded-pill"><?= (int)$statistics['sale_count'] ?></span>
										<?= __('user.sale_by_country') ?>
									</h5>
										<?php if((int)$statistics['sale_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['sale'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="sale-chart"></div>
										<?php } else { ?>
											<div class="empty-graph">
												<div class="text-center mt-5">
													<div class="d-flex justify-content-center align-items-center flex-column mt-5">
														 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
														 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

						<div class="row ">
							<?php if($refer_status){ ?>
							<div class="col-sm-6 mb-5">
								<div class="card">
									<div class="card-body">
										<h5 class="text-center mb-3">
										<span class="badge bg-warning rounded-pill"><?= (int)$statistics['affiliate_user_count'] ?></span>
										<?= __('user.refered_user_by_country') ?>
									</h5>
										<?php if((int)$statistics['affiliate_user_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['affiliate_user'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="affiliate_user-chart"></div>
										<?php } else { ?>
											<div class="empty-graph">
												<div class="text-center mt-5">
													<div class="d-flex justify-content-center align-items-center flex-column mt-5">
														 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
														 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php } ?>

							<div class="col-sm-6 mb-5">
								<div class="card">
									<div class="card-body">
										<h5 class="text-center mb-3">
										<span class="badge bg-danger rounded-pill"><?= (int)$statistics['client_user_count'] ?></span>
										<?= __('user.client_by_country') ?>
									</h5>
										<?php if((int)$statistics['client_user_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['client_user'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="client_user-chart"></div>
										<?php } else { ?>
											<div class="empty-graph">
												<div class="text-center mt-5">
													<div class="d-flex justify-content-center align-items-center flex-column mt-5">
														 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
														 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="tab-menu_report_statistics">
				<div class="card border-0">
					<div class="card-body">
					    <div class="row g-3 align-items-end mb-4">
					        <div class="col-md-4">
					            <label class="form-label fw-bold">
					                <i class="fas fa-calendar me-1 text-primary"></i><?= __('user.date') ?>
					                </label>
					            <input autocomplete="off" type="text" name="date" value="" class="form-control form-control-lg daterange-picker">
					        </div>
					        <div class="col-md-8">
					            <div class="d-flex gap-2">
					                <button class="btn btn-primary btn-lg" onclick="table.ajax.reload();">
					                    <i class="fas fa-search me-1"></i><?= __('user.search') ?>
					                </button>
					                <button class="btn btn-success btn-lg export-excel">
					                    <i class="fas fa-file-excel me-1"></i><?= __('user.export_to_excel') ?>
					                </button>
					                <button class="btn btn-danger btn-lg export-pdf">
					                    <i class="fas fa-file-pdf me-1"></i><?= __('user.export_to_pdf') ?>
					                </button>
					            </div>
					        </div>
					    </div>
						<div class="table-responsive">
							<table class="table table-hover table-striped align-middle" id="table-report">
								<thead>
									<tr class="main-tr">
										<th></th>
										<th><?= __('user.affiliate') ?></th>
										
										<th colspan="2" class="text-center two-border"><?= __('user.clicks') ?></th>
										<th colspan="3" class="text-center two-border"><?= __('user.sale') ?></th>
										<th class="text-center two-border"><?= __('user.cpa') ?></th>
										
										<th colspan="2" class="text-center two-border"><?= __('user.total') ?></th>
									</tr>
									<tr class="sub-tr">
										<th>No</th>
										<th><?= __('user.affiliate_name') ?></th>

										<th -width="90px"><?= __('user.count') ?></th>
										<th -width="120px"><?= __('user.commission') ?></th>

										<th -width="90px"><?= __('user.count') ?></th>
										<th -width="90px"><?= __('user.total') ?></th>
										<th -width="120px"><?= __('user.commission') ?></th>
										<th -width="120px"><?= __('user.cpa') ?></th>
										<th -width="90px"><?= __('user.total_income') ?></th>
										<th -width="120px"><?= __('user.total_commission') ?></th>
									</tr>
								</thead>
								<tbody class="tiny-table"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="tab-menu_report_store_orders">
				<div class="card border-0">
					<div class="card-body">
						<div class="row g-3 align-items-end mb-4">
							<div class="col-md-6">
								<label class="form-label fw-bold">
									<i class="fas fa-filter me-1 text-primary"></i><?= __('user.status') ?>
								</label>
								<select class="form-select form-select-lg filter_status">
										<option value=""><?= __('user.all'); ?></option>
										<?php foreach ($status as $key => $value) { ?>
											<option value="<?= $key ?>">
												<?php   
													if ($value == 'Received') {
														echo __('user.received');
													}elseif ($value == 'Complete') {
														echo __('user.complete');
													}elseif ($value == 'Total not match') {
														echo __('user.total_not_match');
													}elseif ($value == 'Denied') {
														echo __('user.denied');
													}elseif ($value == 'Expired') {
														echo __('user.expired');
													}elseif ($value == 'Failed') {
														echo __('user.failed');
													}elseif ($value == 'Processed') {
														echo __('user.processed');
													}elseif ($value == 'Refunded') {
														echo __('user.refunded');
													}elseif ($value == 'Reversed') {
														echo __('user.reversed');
													}elseif ($value == 'Voided') {
														echo __('user.voided');
													}elseif ($value == 'Canceled Reversal') {
														echo __('user.cancel_reversal');
													}elseif ($value == 'Waiting For Payment') {
														echo __('user.waiting_for_payment');
													}elseif ($value == 'Pending') {
														echo __('user.pending');
													}else{
														echo $value;
													}
												?>
											</option>
										<?php } ?>
									</select>
							</div>
							<div class="col-md-3">
								<button class="btn btn-primary btn-lg w-100" onclick="getPage(1,this)">
									<i class="fas fa-search me-1"></i><?= __('user.search') ?>
								</button>
							</div>
						</div>
						<section class="empty-div d-none text-center py-5">
							<i class="fas fa-inbox fa-4x text-muted mb-3"></i>
							<h4 class="text-muted"><?= __('admin.no_data_found') ?></h4>
						</section>
						<div class="table-responsive">
							<table class="table table-hover table-striped align-middle orders-table">
								<thead class="table-light">
									<tr>
										<th style="width:80px">#</th>
										<th style="width:100px"><?= __('user.order_id') ?></th>
										<th><?= __('user.total') ?></th>
										<th style="width:120px"><?= __('user.country') ?></th>
										<th><?= __('user.store') ?></th>
										<th style="width:120px"><?= __('user.status') ?></th>
										<th style="width:120px"><?= __('user.commission') ?></th>
										<th style="width:150px"><?= __('user.commission_status') ?></th>
										<th style="width:180px"><?= __('user.date') ?></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						
						<style>
						.orders-table tr.detail-tr {
							display: none;
						}
						.orders-table .toggle-child-tr {
							cursor: pointer;
							padding: 0.25rem 0.5rem;
							font-size: 0.875rem;
							border-radius: 0.25rem;
							border: none;
							transition: all 0.2s ease;
						}
						.orders-table .toggle-child-tr:hover {
							transform: scale(1.1);
							box-shadow: 0 2px 4px rgba(0,0,0,0.1);
						}
						</style>
						<div class="card-footer orders text-end bg-white" style="display: none;">
							<div class="pagination"></div>
						</div>
					</div>
				</div>

				<script type="text/javascript">
					$(".orders-table").delegate(".toggle-child-tr","click",function(e){
						e.preventDefault();
						$tr = $(this).parents("tr");
						$ntr = $tr.next("tr.detail-tr");

						if($ntr.css("display") == 'table-row'){
							$ntr.hide();
							$(this).removeClass('btn-danger').addClass('btn-primary')
								   .html('<i class="fas fa-angle-down"></i>');
						}else{
							$(this).removeClass('btn-primary').addClass('btn-danger')
								   .html('<i class="fas fa-angle-up"></i>');
							$ntr.show();
						}
					})
				    
					function getPage(page,t) {
						$this = $(t);
						var data ={
							page:page, 
							filter_status:$(".filter_status").val()
						}
				  
						$.ajax({
							url:'<?= base_url("usercontrol/store_orders") ?>/' + page,
							type:'POST',
							dataType:'json',
							data:data,
							beforeSend:function(){$this.btn("loading");},
							complete:function(){$this.btn("reset");},
							success:function(json){
								if(json['html']){
				                   $(".orders-table tbody").html(json['html']);
				                    $(".orders-table").show();
				                } else {
				                    $(".empty-div").removeClass("d-none");
				                    $(".orders-table").hide();
				                }
								
								if(json['pagination']){
									$(".card-footer.orders").show();
									$(".card-footer.orders .pagination").html(json['pagination'])
								}
							},
						})
					}

					$(".card-footer.orders .pagination").delegate("a","click", function(e){
						e.preventDefault();
						getPage($(this).attr("data-ci-pagination-page"),$(this));
					})

					getPage(1)
				</script>

			</div>
			<div class="tab-pane" id="tab-menu_report_logs">
				<div class="card border-0">
					<div class="card-header bg-info bg-opacity-10">
						<h5 class="mb-0">
							<i class="fas fa-mouse-pointer me-2"></i><?= __('user.click_logs') ?>
						</h5>
					</div>
					<div class="card-body">
						<section class="empty-logs-div d-none text-center py-5">
							<i class="fas fa-mouse-pointer fa-4x text-muted mb-3"></i>
							<h4 class="text-muted"><?= __('user.no_logs_found') ?></h4>
						</section>
						<div class="loading-logs text-center py-5 d-none">
							<div class="spinner-border text-primary" role="status">
								<span class="visually-hidden"><?= __('user.loading') ?></span>
							</div>
							<p class="mt-3 text-muted"><?= __('user.loading_logs') ?></p>
						</div>
						<div class="table-responsive logs-table-wrapper">
							<table class="table table-hover table-striped align-middle click-table">
								<thead class="table-light">
									<tr>
										<th style="width:60px">#</th>
										<th style="width:100px"><?= __('user.click_id') ?></th>
										<th><?= __('user.website') ?></th>
										<th style="width:150px"><?= __('user.ip') ?></th>
										<th style="width:180px"><?= __('user.created_at') ?></th>
										<th style="width:150px"><?= __('user.integration_type') ?></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						
						<style>
						.click-table tr.detail-tr {
							display: none;
						}
						.click-table .toggle-child-tr {
							cursor: pointer;
							padding: 0.25rem 0.5rem;
							font-size: 0.875rem;
							border-radius: 0.25rem;
							border: none;
							transition: all 0.2s ease;
						}
						.click-table .toggle-child-tr:hover {
							transform: scale(1.1);
							box-shadow: 0 2px 4px rgba(0,0,0,0.1);
						}
						</style>
					</div>
					<div class="card-footer logs text-end bg-white" style="display: none;">
						<div class="pagination"></div>
					</div>
				</div>

				<script type="text/javascript">
					$(".click-table").delegate(".toggle-child-tr","click",function(e){
						e.preventDefault();
						$tr = $(this).parents("tr");
						$ntr = $tr.next("tr.detail-tr");

						if($ntr.css("display") == 'table-row'){
							$ntr.hide();
							$(this).removeClass('btn-danger').addClass('btn-primary')
								   .html('<i class="fas fa-angle-down"></i>');
						}else{
							$(this).removeClass('btn-primary').addClass('btn-danger')
								   .html('<i class="fas fa-angle-up"></i>');
							$ntr.show();
						}
					})
					
					function getLogPage(page,t) {
						$this = $(t);
						$.ajax({
							url:'<?= base_url("usercontrol/store_logs") ?>/' + page,
							type:'POST',
							dataType:'json',
							data:{page:page},
							beforeSend:function(){
								if($this.length) {
									$this.btn("loading");
								}
								$(".loading-logs").removeClass("d-none");
								$(".logs-table-wrapper").hide();
								$(".empty-logs-div").addClass("d-none");
							},
							complete:function(){
								if($this.length) {
									$this.btn("reset");
								}
								$(".loading-logs").addClass("d-none");
							},
							success:function(json){
								if(json['html'] && json['html'].trim() !== ''){
									$(".click-table tbody").html(json['html']);
									$(".logs-table-wrapper").show();
									$(".empty-logs-div").addClass("d-none");
								} else {
									$(".empty-logs-div").removeClass("d-none");
									$(".logs-table-wrapper").hide();
								}
								
								$(".card-footer.logs").hide();
								if(json['pagination']){
									$(".card-footer.logs").show();
									$(".card-footer.logs .pagination").html(json['pagination'])
								}
							},
							error: function(){
								$(".empty-logs-div").removeClass("d-none");
								$(".logs-table-wrapper").hide();
							}
						})
					}

					$(".card-footer.logs .pagination").delegate("a","click", function(e){
						e.preventDefault();
						getLogPage($(this).attr("data-ci-pagination-page"),$(this));
					})

					getLogPage(1)
				</script>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.css?v=<?= av() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/dataTables.bootstrap.min.css?v=<?= av() ?>">

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css?v=<?= av() ?>" />

<script type="text/javascript">

	// Bootstrap 5 tab event handler
	$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
		var hash = $(e.target).attr('href');
		localStorage.setItem("report_tab", hash);
		if(hash == '#tab-menu_statistics'){
			apply_chart();
		}
	});

	$(document).ready(function(){
		// Restore last visited tab
		var hash = localStorage.getItem("report_tab");
		if (hash) {
			var tabTrigger = new bootstrap.Tab(document.querySelector('.nav-link[href="' + hash + '"]'));
			if(tabTrigger) {
				tabTrigger.show();
			}
			if(hash == '#tab-menu_statistics'){
				apply_chart();
			}
		} else {
			apply_chart();
		}

		// Initialize tooltips
		$(".ip-details-flag").each(function(index) {
			$(this).tooltip({
				title: $(this).parent().find('.ip-details-flag-details').html(), 
				html: true, 
				placement: "top"
			});
		});
	});

	var colorss = ['#40a4f1', '#5b6be8', '#c1c5e2', '#e785da', '#00bcd2'];
	var is_apply = false;

	function apply_chart(){
		if(!is_apply){
			is_apply = true;
			if($("#clicks-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['clicks'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'clicks-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}

			if($("#action_click-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['action_clicks'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'action_click-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}

			if($("#sale-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['sale'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'sale-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}

			if($("#affiliate_user-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['affiliate_user'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'affiliate_user-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}

			if($("#client_user-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['client_user'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'client_user-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}
		}
	}

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
	    ajax:{
	    	url:"<?= base_url('incomereport/get_data') ?>",
	    	data: function ( d ) {
				d.date     = $(".daterange-picker").val();
		  	},
	    	dataType:'json',
	    	type:'post',
	    },
	    buttons: [],
	    bFilter: false, 
        bInfo: false,
        processing: true,
        language: {
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...'
        },
	});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Excel Export
    $(".export-excel").on('click',function(){
        $this = $(this);
        $this.btn("loading");
        window.location.href = '<?= base_url('incomereport/get_data') ?>?export=excel&date=' + $(".daterange-picker").val();
        setTimeout(function(){ $this.btn("reset"); }, 1000);
    });

    // PDF Export
    $(".export-pdf").on('click', function(){
        $this = $(this);
        $this.btn("loading");
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });
        
        $.ajax({
            url: '<?= base_url('incomereport/get_data') ?>',
            type: 'POST',
            data: {
                date: $(".daterange-picker").val()
            },
            dataType: 'json',
            success: function(response) {
                const pageWidth = doc.internal.pageSize.getWidth();
                const margin = 10;
                const availableWidth = pageWidth - (margin * 2);

                doc.autoTable({
                    head: [[
                        'No', 
                        'Affiliate Name', 
                        'Username', 
                        'Clicks', 
                        'Commission',
                        'Sales',
                        'Total',
                        'Commission',
                        'CPA',
                        'Income',
                        'Total Commission'
                    ]],
                    body: response.data.map(row => ([
                        row[0],
                        row[1].split('<img')[0].trim(),
                        row[2],
                        row[3],
                        String.fromCharCode(8369) + row[4].substring(1),
                        row[5],
                        String.fromCharCode(8369) + row[6].substring(1),
                        String.fromCharCode(8369) + row[7].substring(1),
                        row[8],
                        String.fromCharCode(8369) + row[9].substring(1),
                        String.fromCharCode(8369) + row[10].substring(1)
                    ])),
                    margin: { top: margin, right: margin, bottom: margin, left: margin },
                    columnStyles: {
                        0: { cellWidth: availableWidth * 0.05 },
                        1: { cellWidth: availableWidth * 0.13 },
                        2: { cellWidth: availableWidth * 0.12 },
                        3: { cellWidth: availableWidth * 0.07 },
                        4: { cellWidth: availableWidth * 0.09 },
                        5: { cellWidth: availableWidth * 0.07 },
                        6: { cellWidth: availableWidth * 0.09 },
                        7: { cellWidth: availableWidth * 0.09 },
                        8: { cellWidth: availableWidth * 0.09 },
                        9: { cellWidth: availableWidth * 0.09 },
                        10: { cellWidth: availableWidth * 0.11 }
                    },
                    styles: {
                        fontSize: 8,
                        cellPadding: 2,
                        overflow: 'linebreak',
                        cellWidth: 'wrap',
                        font: 'helvetica',
                        textDirection: 'ltr',
                        halign: 'left'
                    },
                    headStyles: {
                        fillColor: [71, 145, 255],
                        textColor: [255, 255, 255],
                        fontSize: 8,
                        halign: 'center'
                    }
                });
                
                doc.save('Income_Report_' + new Date().toISOString().split('T')[0] + '.pdf');
            },
            complete: function(){
                $this.btn("reset");
            }
        });
    });
});
</script>
</div>
