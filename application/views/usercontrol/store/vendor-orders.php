<div class="container-fluid">
<div class="card shadow-sm">
	<div class="card-header bg-white">
		<div class="row g-3 align-items-end">
			<div class="col-md-5">
				<label class="form-label fw-bold">
					<i class="fas fa-filter me-1 text-primary"></i><?= __('user.filter_by_status') ?>
				</label>
				<select class="form-select filter_status">
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
				<button class="btn btn-primary w-100" onclick="getPage(1,this)">
					<i class="fas fa-search me-1"></i><?= __('user.search') ?>
				</button>
			</div>
		</div>
	</div>
	<div class="card-body p-0">
		<section class="empty-div d-none text-center py-5">
			<i class="fas fa-inbox fa-4x text-muted mb-3"></i>
			<h4 class="text-muted"><?= __('admin.no_data_found') ?></h4>
			<p class="text-muted"><?= __('user.no_orders_match_filter') ?></p>
		</section>
		<div class="table-responsive">
			<table class="table table-hover table-striped orders-table align-middle mb-0">
				<thead class="table-light">
					<tr>
						<th><i class="fas fa-hashtag me-1"></i>#</th>
						<th><i class="fas fa-receipt me-1"></i><?= __('user.order_id') ?></th>
						<th><i class="fas fa-dollar-sign me-1"></i><?= __('user.total') ?></th>
						<th><i class="fas fa-flag me-1"></i><?= __('user.country') ?></th>
						<th><i class="fas fa-store me-1"></i><?= __('user.store') ?></th>
						<th><i class="fas fa-info-circle me-1"></i><?= __('user.status') ?></th>
						<th><i class="fas fa-percentage me-1"></i><?= __('user.commission') ?></th>
						<th><i class="fas fa-check-circle me-1"></i><?= __('user.commission_status') ?></th>
						<th><i class="fas fa-calendar me-1"></i><?= __('user.date') ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
	<div class="card-footer bg-white text-end" style="display: none;"> 
		<div class="pagination"></div> 
	</div>
</div>

<style>
.orders-table tr.detail-tr {
	display: none;
}
</style>

<script type="text/javascript">
	$(".orders-table").delegate(".toggle-child-tr","click",function(){
		$tr = $(this).parents("tr");
		$ntr = $tr.next("tr.detail-tr");

		if($ntr.css("display") == 'table-row'){
			$ntr.hide();
			$(this).find("i").attr("class","bi bi-plus-circle");
		}else{
			$(this).find("i").attr("class","bi bi-dash-circle");
			$ntr.show();
		}
	})
				
	function getPage(page,t) {
		$this = $(t);
		var data = {
			page: page, 
			filter_status: $(".filter_status").val()
		}
  
		$.ajax({
			url: '<?= base_url("usercontrol/store_venodr_orders") ?>/' + page,
			type: 'POST',
			dataType: 'json',
			data: data,
			beforeSend: function(){ $this.btn("loading"); },
			complete: function(){ $this.btn("reset"); },
			success: function(json){
				if(json['html']){
					$(".orders-table tbody").html(json['html']);
					$(".orders-table").show();
					$(".empty-div").addClass("d-none");
				} else {
					$(".empty-div").removeClass("d-none");
					$(".orders-table").hide();
				}

				$(".card-footer").hide();

				if(json['pagination']){
					$(".card-footer").show();
					$(".card-footer .pagination").html(json['pagination']);
				}
			},
		})
	}

	$(".card-footer .pagination").delegate("a","click", function(e){
		e.preventDefault();
		getPage($(this).attr("data-ci-pagination-page"), $(this));
	})

	getPage(1)
</script>
</div>