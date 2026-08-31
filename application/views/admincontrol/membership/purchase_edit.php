<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			
			<!-- Page Header -->
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h1 class="h3 mb-1 text-gray-800">
						<i class="fas fa-edit text-primary me-2"></i>
						<?= __('admin.edit_membership_purchase') ?>
					</h1>
					<p class="text-muted mb-0"><?= __('admin.edit_membership_purchase_desc') ?></p>
				</div>
				<div>
					<a href="<?= base_url('membership/membership_orders') ?>" class="btn btn-outline-secondary">
						<i class="fas fa-arrow-left me-2"></i><?= __('admin.back_to_orders') ?>
					</a>
				</div>
			</div>

			<div class="row g-4">
		<div class="col-lg-6">
			<div class="card border-0 shadow-sm mb-4">
				<div class="card-header bg-light border-0 py-3">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">
							<i class="fas fa-receipt text-primary me-2"></i>
							<?= __('admin.purchase_details') ?>
						</h5>
						<span class="badge bg-primary">#<?= $plan->id ?></span>
					</div>
				</div>

				<div class="card-body">
					<div class="row g-3">
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.id') ?></small>
								<strong class="fs-5"><?= $plan->id ?></strong>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.plan_name') ?></small>
								<strong><?= ($plan->plan ? $plan->plan->name : '') ?></strong>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.price') ?></small>
								<strong class="text-success"><?= c_format(($plan->plan ? $plan->plan->price : 0)) ?></strong>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.special_price') ?></small>
								<strong class="text-warning"><?= c_format(($plan->plan ? $plan->plan->special : 0)) ?></strong>
							</div>
						</div>

					<?php 
						$bonus = $plan->bonusData();
					?>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.bonus') ?></small>
								<strong class="text-info">
									<?php if($bonus) { ?>
										<?= c_format($bonus->amount) ?>
									<?php } else { ?>
										<?= __('admin.no_bonus') ?>
									<?php } ?>
								</strong>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.type') ?></small>
								<strong>
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
								</strong>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.plan_status') ?></small>
								<strong><?= $plan->active_text ?></strong>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.payment_status') ?></small>
								<strong><?= $plan->status_text ?></strong>
							</div>
						</div>


					<?php if($plan->status_id == 1) { ?>
						<?php if(!$plan->is_lifetime) { ?>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.remaining_time') ?></small>
								<strong class="text-danger"><?= $plan->remainDay(); ?></strong>
							</div>
						</div>
						<?php } ?>
						
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.started_on') ?></small>
								<strong><?= dateFormat($plan->started_at,'d F Y, h:i A'); ?></strong>
							</div>
						</div>
						
						<?php if(!$plan->is_lifetime) { ?>
						<div class="col-sm-6">
							<div class="border rounded p-3 bg-light">
								<small class="text-muted d-block"><?= __('admin.ending_on') ?></small>
								<strong><?= dateFormat($plan->expire_at,'d F Y, h:i A'); ?></strong>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
					
					<?php if(!empty($plan->payment_details) && $plan->payment_details != "[]") {
						$payment_details = json_decode($plan->payment_details);
						foreach($payment_details as $key => $value) {
							if($key == 'payment_proof') {
								?>
								<div class="col-12">
									<div class="border rounded p-3 bg-light">
										<small class="text-muted d-block"><?= __('admin.payment_proof') ?></small>
										<a target="_blank" href="<?php echo base_url('assets/user_upload/'.$value) ?>" class="btn btn-outline-primary btn-sm">
											<i class="fas fa-download me-1"></i><?php echo $value; ?>
										</a>
									</div>
								</div>
								<?php
							}
						}
					}?>
					
					<div class="col-12">
						<div class="border rounded p-3 bg-light">
							<small class="text-muted d-block"><?= __('admin.created_at') ?></small>
							<strong><?= dateFormat($plan->created_at, 'd F Y, h:i A') ?></strong>
						</div>
					</div>
				</div>
			</div>

		</div>

		<?php if(!$plan->is_lifetime && $plan->status_id == 1) { ?>
		<div class="card border-0 shadow-sm mb-4">
			<div class="card-header bg-light border-0 py-3">
				<h5 class="card-title mb-0">
					<i class="fas fa-edit text-warning me-2"></i>
					<?= __('admin.edit_plan') ?>
				</h5>
			</div>
			<div class="card-body">
				<form id="plan-form">
					<div class="mb-3">
						<label class="form-label fw-semibold"><?= __('admin.expire_on') ?></label>
						<div class="input-group">
							<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
							<input type="text" value="<?= dateFormat($plan->expire_at,'d-m-Y H:i') ?>" name="expire_at" class='form-control datepicker' placeholder="<?= __('admin.select_date_time') ?>">
						</div>
						<small class="form-text text-muted"><?= __('admin.set_expiration_date') ?></small>
					</div>
				</form>
			</div>
			<div class="card-footer bg-transparent border-0 d-flex justify-content-end">
				<button class="btn btn-save-plan btn-primary">
					<i class="fas fa-save me-2"></i><?= __('admin.save_plan') ?>
				</button>
			</div>
		</div>
		<?php } ?>

	</div>

	<div class="col-lg-6">
		<div class="card border-0 shadow-sm mb-4">
			<div class="card-header bg-light border-0 py-3">
				<h5 class="card-title mb-0">
					<i class="fas fa-clipboard-list text-info me-2"></i>
					<?= __('admin.plan_details') ?>
				</h5>
			</div>
			<div class="card-body">
				<div class="row g-3 mb-4">
					<div class="col-sm-6">
						<div class="border rounded p-3 bg-light">
							<small class="text-muted d-block"><?= __('admin.name') ?></small>
							<strong><?= $plan->plan->name ?></strong>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="border rounded p-3 bg-light">
							<small class="text-muted d-block"><?= __('admin.type') ?></small>
							<strong>
								<?php  
			                		if ($plan->plan->type == 'paid') {
			                            echo '<span class="badge bg-primary">' . __('admin.paid') . '</span>';
			                        }elseif ($plan->plan->type == 'free') {
			                            echo '<span class="badge bg-success">' . __('admin.free') . '</span>';
			                        }else{
			                            echo '<span class="badge bg-secondary">' . $plan->plan->type . '</span>';
			                        }
			                	?>
							</strong>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="border rounded p-3 bg-light">
							<small class="text-muted d-block"><?= __('admin.price') ?></small>
							<strong class="text-success"><?= c_format($plan->plan->price) ?></strong>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="border rounded p-3 bg-light">
							<small class="text-muted d-block"><?= __('admin.special_price') ?></small>
							<strong class="text-warning"><?= c_format(($plan->plan ? $plan->plan->special : 0)) ?></strong>
						</div>
					</div>
				</div>
				
				<div class="border rounded p-3 bg-light">
					<small class="text-muted d-block mb-2"><?= __('admin.description') ?></small>
					<div class="text-dark"><?= $plan->plan->description ?></div>
				</div>
			</div>
		</div>



		<div class="card border-0 shadow-sm">
			<div class="card-header bg-light border-0 py-3">
				<h5 class="card-title mb-0">
					<i class="fas fa-history text-secondary me-2"></i>
					<?= __('admin.status_history') ?>
				</h5>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover mb-0">
						<thead class="table-light">
							<tr>
								<th width="150px" class="border-0"><?= __('admin.status') ?></th>
								<th class="border-0"><?= __('admin.note') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($history as $key => $value) { ?>
								<tr>
									<td class="align-middle">
										<span class="badge bg-info"><?= $value->status_text ?></span>
									</td>
									<td class="align-middle"><?= $value->comment ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>

				<div class="card-body border-top bg-light">
					<h6 class="mb-3 text-secondary">
						<i class="fas fa-plus-circle me-2"></i><?= __('admin.update_status') ?>
					</h6>
					<div class="add-history">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label fw-semibold"><?= __('admin.status') ?></label>
								<select class="form-select" name="status_id">
									<option value=""><?= __('admin.select_status') ?></option>
									<?php foreach (App\MembershipPlan::$status_list as $key => $value) { ?>
										<option value="<?= $key ?>">
											<?php   
												if ($value == 'Received') {
													echo __('admin.received');
												}elseif ($value == 'Complete') {
													echo __('admin.complete');
												}elseif ($value == 'Total not match') {
													echo __('admin.total_not_match');
												}elseif ($value == 'Denied') {
													echo __('admin.denied');
												}elseif ($value == 'Expired') {
													echo __('admin.expired');
												}elseif ($value == 'Failed') {
													echo __('admin.failed');
												}elseif ($value == 'Processed') {
													echo __('admin.processed');
												}elseif ($value == 'Refunded') {
													echo __('admin.refunded');
												}elseif ($value == 'Reversed') {
													echo __('admin.reversed');
												}elseif ($value == 'Voided') {
													echo __('admin.voided');
												}elseif ($value == 'Canceled Reversal') {
													echo __('admin.cancel_reversal');
												}elseif ($value == 'Waiting For Payment') {
													echo __('admin.waiting_for_payment');
												}elseif ($value == 'Pending') {
													echo __('admin.pending');
												}elseif ($value == 'Active') {
													echo __('admin.active');
												}else{
													echo $value;
												}
											?>
										</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-6">
								<label class="form-label fw-semibold"><?= __('admin.comment') ?></label>
								<textarea class="form-control" name="comment" rows="3" placeholder="<?= __('admin.add_status_comment') ?>"></textarea>
							</div>
						</div>
						<div class="d-flex justify-content-end mt-3">
							<button type="button" class="btn-add-commnet btn btn-primary">
								<i class="fas fa-save me-2"></i><?= __('admin.update_membership_status') ?>
							</button>
						</div>
					</div>
			</div>
		</div>
	</div> <!-- End row g-4 -->
		</div> <!-- End col-12 -->
	</div> <!-- End row -->
</div> <!-- End container-fluid -->

<link href="<?php echo base_url('assets/template/css/datepicker.css'); ?>" rel="stylesheet" type="text/css" />

<script src="<?php echo base_url('assets/template/js/bootstrap-datepicker.js'); ?>"></script>



<script type="text/javascript">

	$(".datepicker").datetimepicker({ 

        autoclose: true, 

        todayHighlight: true,

		showSecond: true,

        format:"d-m-Y H:m"

    })



	$(".btn-save-plan").click(function(){

		$this = $(this);

		$.ajax({

			url:'<?= base_url("membership/submit_plan_update/". $plan->id) ?>',

			type:'POST',

			dataType:'json',

			data:$("#plan-form").serialize(),

			beforeSend:function(){$this.button("loading");},

			complete:function(){$this.button("reset");},

			success:function(json){

				$container = $("#plan-form");

				$container.find(".is-invalid").removeClass("is-invalid");

				$container.find("span.invalid-feedback").remove();

		

				if (json['location']) {

					window.location.href= json['location'];

				}

				

				if(json['errors']){

				    $.each(json['errors'], function(i,j){

				        $ele = $container.find('[name="'+ i +'"]');

				        if($ele){

				            $ele.addClass("is-invalid");

				            if($ele.parent(".input-group").length){

				                $ele.parent(".input-group").after("<span class='invalid-feedback'>"+ j +"</span>");

				            } else{

				                $ele.after("<span class='invalid-feedback'>"+ j +"</span>");

				            }

				        }

				    })

				}

			},

		})

	})



	$(".btn-add-commnet").click(function(){

		$this = $(this);
		
		// Prevent multiple clicks
		if($this.attr("disabled") == "disabled"){
			return false;
		}

		$.ajax({

			url:'?addhistory=true',

			type:'POST',

			dataType:'json',

			data:$(".add-history :input"),

			beforeSend:function(){
				$this.button("loading");
				$this.prop("disabled", true);
			},

			complete:function(){
				$this.button("reset");
				$this.prop("disabled", false);
			},

			success:function(json){

				$container = $(".add-history");

				$container.find(".is-invalid").removeClass("is-invalid");

				$container.find("span.invalid-feedback").remove();

		

				if (json['reload']) {

					window.location.reload();

				}

				

				if(json['errors']){

				    $.each(json['errors'], function(i,j){

				        $ele = $container.find('[name="'+ i +'"]');

				        if($ele){

				            $ele.addClass("is-invalid");

				            if($ele.parent(".input-group").length){

				                $ele.parent(".input-group").after("<span class='invalid-feedback'>"+ j +"</span>");

				            } else{

				                $ele.after("<span class='invalid-feedback'>"+ j +"</span>");

				            }

				        }

				    })

				}

			},

		})

	})



</script>