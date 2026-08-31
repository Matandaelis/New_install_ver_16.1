<div class="container-fluid">
	<div class="card shadow-sm mb-4">
		<div class="card-body p-0">
			<ul class="nav nav-pills nav-fill" id="myWalletTabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active rounded-0" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#tab-wallet" type="button" role="tab" aria-controls="tab-wallet" aria-selected="true">
						<i class="fas fa-wallet me-2"></i><?= __('user.my_wallet') ?>
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link rounded-0" id="payout-tab" data-bs-toggle="tab" data-bs-target="#tab-payout" type="button" role="tab" aria-controls="tab-payout" aria-selected="false">
						<i class="fas fa-hand-holding-usd me-2"></i><?= __('user.my_payout') ?>
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link rounded-0" id="paymentdetails-tab" data-bs-toggle="tab" data-bs-target="#tab-paymentdetails" type="button" role="tab" aria-controls="tab-paymentdetails" aria-selected="false">
						<i class="fas fa-credit-card me-2"></i><?= __('user.payment_details') ?>
					</button>
				</li>
			</ul>
		</div>
	</div>

		<div class="tab-content" id="myWalletTabsContent">
			<div class="tab-pane fade show active" id="tab-wallet" role="tabpanel" aria-labelledby="wallet-tab" tabindex="0">
				<div class="card border-0 shadow-sm">
					<?php 
						$allow_with = false;
						if( (float)$totals['wallet_unpaid_amount'] >= (float)$site_setting['wallet_min_amount']){
							$allow_with = true;
						}
					?>
						<div class="card-header bg-light">
						<div class="row g-3">
							<div class="col-xl-3 col-md-6">
								<div class="card border-0 shadow-sm h-100 bg-primary bg-gradient text-white">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<div class="fs-5 fw-bold"><?= __('user.total_click_commition') ?></div>
											<i class="fas fa-mouse-pointer fa-2x opacity-50"></i>
										</div>
										<div class="d-flex align-items-baseline gap-2">
											<h2 class="mb-0 fw-bold"><?php echo $totals['all_clicks'] ?></h2>
											<span class="opacity-75"><?= __('user.clicks') ?></span>
										</div>
										<div class="mt-2 fs-4 fw-semibold"><?php echo c_format($totals['all_clicks_comm']) ?></div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-6">
								<div class="card border-0 shadow-sm h-100 bg-success bg-gradient text-white">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<div class="fs-5 fw-bold"><?= __('user.sale_ommition') ?></div>
											<i class="fas fa-shopping-cart fa-2x opacity-50"></i>
										</div>
										<div class="d-flex align-items-baseline gap-2">
											<h2 class="mb-0 fw-bold"><?php echo $totals['total_sale_count']; ?></h2>
											<span class="opacity-75"><?= __('user.sales') ?></span>
										</div>
										<div class="mt-2 fs-4 fw-semibold"><?php echo c_format($totals['all_sale_comm']); ?></div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-6">
								<div class="card border-0 shadow-sm h-100 bg-warning bg-gradient text-dark">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<div class="fs-5 fw-bold"><?= __('user.paid_request_unpaid') ?></div>
											<i class="fas fa-wallet fa-2x opacity-50"></i>
										</div>
										<div class="row g-2 mt-1">
											<div class="col-12">
												<div class="d-flex justify-content-between">
													<small class="opacity-75"><?= __('user.paid') ?>:</small>
													<strong><?php echo c_format($totals['wallet_accept_amount']); ?></strong>
												</div>
											</div>
											<div class="col-12">
												<div class="d-flex justify-content-between">
													<small class="opacity-75"><?= __('user.requested') ?>:</small>
													<strong><?php echo c_format($totals['wallet_request_sent_amount']); ?></strong>
												</div>
											</div>
											<div class="col-12">
												<div class="d-flex justify-content-between">
													<small class="opacity-75"><?= __('user.unpaid') ?>:</small>
													<strong><?php echo c_format($totals['unpaid_commition']); ?></strong>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-6">
								<div class="card border-0 shadow-sm h-100 bg-info bg-gradient text-white">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<div class="fs-5 fw-bold"><?= __('user.total_action_amount') ?></div>
											<i class="fas fa-hand-pointer fa-2x opacity-50"></i>
										</div>
										<div class="d-flex align-items-baseline gap-2">
											<h2 class="mb-0 fw-bold"><?= (int)$totals['integration']['action_count'] ?></h2>
											<span class="opacity-75"><?= __('user.actions') ?></span>
										</div>
										<div class="mt-2 fs-4 fw-semibold"><?= c_format($totals['integration']['action_amount']) ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				
				
							<form method="GET">
								<div class="row g-3 align-items-end">
									<div class="col-md-3">
										<label class="form-label fw-bold">
											<i class="fas fa-filter me-1 text-primary"></i><?= __('user.type') ?>
										</label>
										<select name="type" class="form-select form-select-lg">
											<option value=""><?= __('user.all') ?></option>
											<option value="actions" <?= isset($_GET['type']) && $_GET['type'] == 'actions' ? 'selected' : '' ?>><?= __('user.actions') ?></option>
											<option value="clicks" <?= isset($_GET['type']) && $_GET['type'] == 'clicks' ? 'selected' : '' ?>><?= __('user.clicks') ?></option>
											<option value="sale" <?= isset($_GET['type']) && $_GET['type'] == 'sale' ? 'selected' : '' ?>><?= __('user.sale') ?></option>
											<option value="external_integration" <?= isset($_GET['type']) && $_GET['type'] == 'external_integration' ? 'selected' : '' ?>><?= __('user.external_integration') ?></option>
											<option value="shipping_reimbursement" <?= isset($_GET['type']) && $_GET['type'] == 'shipping_reimbursement' ? 'selected' : '' ?>><?= __('user.shipping_reimbursement') ?></option>
										</select>
									</div>
									<div class="col-md-9 d-flex gap-2">
										<button class="btn btn-primary btn-lg">
											<i class="fas fa-search me-1"></i><?= __('user.filter') ?>
										</button>
										<?php if($allow_with){ ?>
											<button type="button" class="btn btn-success btn-lg withdrawal-all">
												<i class="fas fa-money-bill-wave me-2"></i><?= __('user.withdrawal_all') ?> 
												<span class="badge bg-light text-success ms-2"><?= c_format($totals['wallet_unpaid_amount']) ?></span>
											</button>
										<?php } else { ?>
											<button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" href='#withdrawal-limit'>
												<i class="fas fa-money-bill-wave me-2"></i><?= __('user.withdrawal_all') ?> 
												<span class="badge bg-light text-success ms-2"><?= c_format($totals['wallet_unpaid_amount']) ?></span>
											</button>
										<?php } ?>
									</div>
								</div>
							</form>
						</div>
						<div class="card-body">
							<?php if ($transaction ==null) {?>
								<div class="text-center mt-5">
								 <div class="d-flex justify-content-center align-items-center flex-column mt-5">
									 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
									 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
								 </div>
								</div>
				            <?php } else { ?>         
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<thead class="table-dark">
											<tr>
												<th scope="col">#</th>
												<th scope="col"></th>
												<th scope="col"><?= __('user.order_total') ?></th>
												<th class="sortTr <?= sort_order('wallet.amount') ?>" scope="col"><a href="<?= sortable_link('usercontrol/mywallet/','wallet.amount') ?>" class="text-white"><?= __('user.commission') ?></a></th>
												<th scope="col"><?= __('user.payment_method') ?></th>
												<th class="sortTr <?= sort_order('wallet.created_at') ?>" scope="col"><a href="<?= sortable_link('usercontrol/mywallet/','wallet.created_at') ?>" class="text-white"><?= __('user.date') ?></a></th>
												<th class="sortTr <?= sort_order('wallet.comm_from') ?>" scope="col"><a href="<?= sortable_link('admincontrol/mywallet','wallet.comm_from') ?>" class="text-white"><?= __('user.comm_from') ?></a></th>
												<th class="sortTr <?= sort_order('wallet.type') ?>" scope="col"><a href="<?= sortable_link('usercontrol/mywallet/','wallet.type') ?>" class="text-white"><?= __('user.type') ?></a></th>
												<th class="sortTr <?= sort_order('wallet.status') ?>" scope="col"><a href="<?= sortable_link('usercontrol/mywallet/','wallet.status') ?>" class="text-white"><?= __('user.status') ?></a></th>
											</tr>
										</thead>
										<tbody>
											<?= $table ?>
										</tbody>
									</table>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="tab-payout" role="tabpanel" aria-labelledby="payout-tab" tabindex="0">
				<div class="card border-0 shadow-sm">
					<div class="card-header bg-white">
						<h4 class="card-title mb-0"><?= __('user.my_payout') ?></h4>
					</div>
					<div class="card-body">
						
						<?php if ($payout_transaction ==null) {?>
							<div class="text-center mt-5">
							 <div class="d-flex justify-content-center align-items-center flex-column mt-5">
								 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
								 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
							 </div>
							</div>
                        <?php } else { ?>
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<thead class="table-dark">
										<tr>
											<th scope="col">#</th>
											<th scope="col"><?= __('user.amount') ?></th>
											<th scope="col"><?= __('user.comment') ?></th>
											<th scope="col"><?= __('user.date') ?></th>
											<th scope="col"><?= __('user.type') ?></th>
											<th scope="col"><?= __('user.status') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($payout_transaction as $key => $value) { ?>
										<tr>
											<td><?= $key + 1 ?></td>
											<td><?= c_format($value['amount']) ?></td>
											<td><?= parseMessage($value['comment'],$value,'usercontrol') ?></td>
											<td><?= $value['created_at'] ?></td>
											<td><?= wallet_type($value) ?></td>
											<td><?= $status[$value['status']] ?></td>
										</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="100%" class="text-right">
												<div class="pagination">
													<?= $pagination_link; ?>
												</div>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="tab-paymentdetails" role="tabpanel" aria-labelledby="paymentdetails-tab" tabindex="0">
			    <div class="card border-0 shadow-sm">
			        <div class="card-header bg-white">
			            <h4 class="card-title mb-0"><?= __('user.my_payment_details') ?></h4>
			        </div>
			        <div class="card-body">
			            
			            <form class="form-horizontal" method="post" action="<?= base_url('usercontrol/addpayment') ?>"  enctype="multipart/form-data">
			                <input type="hidden" name="payment_id" value="<?= $paymentlist['payment_id'] ?>">
			                <div class="form-group row">
			                    <label class="col-sm-3 col-form-label"><?= __('user.bank_name') ?> </label>
			                    <div class="col-sm-9">
			                        <input placeholder="<?= __('user.enter_your_bank_name') ?>" name="payment_bank_name" value="<?php echo $paymentlist['payment_bank_name']; ?>" class="form-control" required="required" type="text">
			                    </div>
			                </div>
			                <div class="form-group row">
			                    <label class="col-sm-3 col-form-label"><?= __('user.account_number') ?></label>
			                    <div class="col-sm-9">
			                        <input placeholder="<?= __('user.enter_your_account_number') ?>" name="payment_account_number" value="<?php echo $paymentlist['payment_account_number']; ?>" class="form-control" required="required" type="text">
			                    </div>
			                </div>
			                
			                <div class="form-group row">
			                    <label class="col-sm-3 col-form-label"><?= __('user.account_name') ?></label>
			                    <div class="col-sm-9">
			                        <input placeholder="<?= __('user.enter_your_account_name') ?>" name="payment_account_name" class="form-control" value="<?php echo $paymentlist['payment_account_name']; ?>" required="required" type="text">
			                    </div>
			                </div>
			                <div class="form-group row">
			                    <label class="col-sm-3 col-form-label"><?= __('user.country') ?></label>
			                    <div class="col-sm-9">
			                        <select name="payment_country" id="payment_country" class="form-control" required="required">
			                            <option value=""><?= __('user.select_country') ?></option>
			                            <?php 
			                            $countries = $payment_methods['bank_transfer']['bank_transfer_countries'];
			                            foreach($countries as $country => $status) {
			                                if($status == 1) {
			                                    $selected = ($paymentlist['payment_country'] == $country) ? 'selected' : '';
			                                    echo '<option value="'.$country.'" '.$selected.'>'.__('user.'.$country).'</option>';
			                                }
			                            }
			                            ?>
			                        </select>
			                    </div>
			                </div>
			                
			                <!-- Country Specific Fields -->
			                <div id="country-fields">
			                    <?php 
			                    foreach($countries as $country => $status) {
			                        if($status == 1) {
			                            echo '<div class="form-group row country-field" data-country="'.$country.'" style="display: none;">
			                                    <label class="col-sm-3 col-form-label">'.__('user.'.$country.'_field').'</label>
			                                    <div class="col-sm-9">
			                                        <input placeholder="'.__('user.enter_'.$country.'_field').'" name="payment_'.$country.'_field" class="form-control" type="text">
			                                    </div>
			                                  </div>';
			                        }
			                    }
			                    ?>
			                </div>
			                
			                <div class="form-group text-end">
			                    <button class="btn btn-success" id="update-payment"  type="submit"><i class="fa fa-save"></i> <?= __('user.submit') ?></button>
			                </div>
			            </form>
			        </div>
			    </div>
			</div>

			<script>
			$(document).ready(function() {
			    $('#payment_country').on('change', function() {
			        var selectedCountry = $(this).val();
			        $('.country-field').hide();
			        $('.country-field[data-country="' + selectedCountry + '"]').show();
			        $('.country-field input').prop('required', false);
			        $('.country-field[data-country="' + selectedCountry + '"] input').prop('required', true);
			    });
			    
			    if($('#payment_country').val()) {
			        $('#payment_country').trigger('change');
			    }
			});
			</script>
		</div>
</div>
</div>


<div class="modal fade" id="withdrawal-limit" tabindex="-1" aria-labelledby="withdrawalLimitLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 shadow">
			<div class="modal-header bg-warning text-dark border-0">
				<h5 class="modal-title fw-bold" id="withdrawalLimitLabel">
					<i class="fas fa-exclamation-triangle me-2"></i><?= __('user.withdrawal_limit') ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-warning border-0 mb-0">
					<div class="d-flex align-items-start">
						<i class="fas fa-info-circle me-2 mt-1 fs-4"></i>
						<div>
							<?= $site_setting['wallet_min_message'] ?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer border-0 bg-light">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					<i class="fas fa-times me-1"></i><?= __('user.close') ?>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="clearfix"></div><br>
<script type="text/javascript">
	$(".show-recurring-transition").on("click",function(){
		$this = $(this);
		var id = $this.attr("data-id");

		$this.find("i").toggleClass("mdi-plus mdi-minus")

		$nextAll = $this.parents("tr").nextAll("tr.recurringof-"+id);
		if($nextAll.length){
			if($nextAll.eq(0).css("display") == 'table-row'){
				$nextAll.hide();
			} else {
				$nextAll.show();
			}
			return false;
		}

		$this.parents("tr").nextAll("tr.recurringof-"+id).remove();

		$.ajax({
			url:'<?= base_url('usercontrol/getRecurringTransaction') ?>',
			type:'POST',
			dataType:'json',
			data:{
				id:id,
			},
			beforeSend:function(){
				$this.btn("loading");
			},
			complete:function(){
				$this.btn("reset");
			},
			success:function(json){
				if(json['table']){
					$this.parents("tr").after(json['table'])
				}
			},
		})
	})

	$('.withdrawal-all').on('click',function(){

		if(!confirm('<?= __('user.are_you_sure') ?>')) return false;

		$this = $(this);
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{request_payment_all: true},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				window.location.reload();
			},
		})
	});

	$('.send-request').on('click',function(){
		$this = $(this);
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{request_payment: $this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$this.parents("tr").remove();
			},
		})
	})

	$('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
		var hash = $(e.target).attr('data-bs-target');
		if (history.pushState) {
		    history.pushState(null, null, hash);
		} else {
		    location.hash = hash;
		}
	});

	$(document).ready(function(){
		var hash = window.location.hash;
		if (hash) { 
			$('button[data-bs-target="' + hash + '"]').tab('show'); 
		}
	})


	$(document).ready(function(){
		$(".wallet-popover").each(function(){
			new bootstrap.Popover(this, {
				placement: 'right',
				html: true,
				trigger: 'click'
			});
		});
	});

	$(document).delegate('.wallet-popover','click', function(e){
		e.stopPropagation();
		var html = $(this).parents("tr").find(".dpopver-content").html();
		var popover = bootstrap.Popover.getInstance(this);
		if(popover){
			popover.setContent({
				'.popover-body': html
			});
		}
	});

	$('html').on('click', function(e) {
		if (!$(e.target).hasClass('wallet-popover') && 
			!$(e.target).parents().is('.popover')) {
			$('.wallet-popover').each(function(){
				var popover = bootstrap.Popover.getInstance(this);
				if(popover) popover.hide();
			});
		}
	})
</script>



</div>