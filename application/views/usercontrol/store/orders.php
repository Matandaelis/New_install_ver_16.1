<div class="container-fluid">
<div class="card shadow-sm">
	<div class="card-header bg-white">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label"><?= __('user.status') ?></label>
					<select class="form-control filter_status">
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
					 
				
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
			
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label d-block">&nbsp;</label>
					<button class="btn btn-primary" onclick="getPage(1,this)"><?= __('user.search') ?></button>
				
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
			
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
		
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
	
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
	<div class="card-body p-0">
		<div class="table-responsive table-container">
			<section class="empty-div d-none">
				<div class="text-center mt-5">
					<div class="d-flex justify-content-center align-items-center flex-column mt-5">
						 <i class="fas fa-exchange-alt fa-5x text-muted"></i>
						 <h3 class="text-muted"><?= __('admin.no_data_found') ?></h3>
					
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
				
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
            </section>
			<table class="table table-hover table-striped orders-table align-middle mb-0">
				<thead class="table-light">
					<tr>
						<th width="80px">#</th>
						<th width="80px"><?= __('user.order_id') ?></th>
						<th><?= __('user.total') ?></th>
						<th><?= __('user.country') ?></th>
						<th><?= __('user.store') ?></th>
						<th><?= __('user.status') ?></th>
						<th><?= __('user.commission') ?></th>
						<th><?= __('user.commission_status') ?></th>
						<th><?= __('user.date') ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
	
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>
	<div class="card-footer bg-white text-end" style="display: none;"> <div class="pagination">
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div> 
<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>

<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>

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
					$(".card-footer").show();
					$(".card-footer .pagination").html(json['pagination'])
				}
			},
		})
	}

	$(".card-footer .pagination").delegate("a","click", function(e){
		e.preventDefault();
		getPage($(this).attr("data-ci-pagination-page"),$(this));
	})

	getPage(1)
</script>

<style>
/* Fix for table overflow without horizontal scrollbar */
.table-container {
    width: 100%;
    overflow: hidden;
}

.table-container table {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    table-layout: auto;
}

/* Ensure table cells don't overflow */
.table-container th,
.table-container td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Make table responsive without horizontal scroll */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        white-space: normal;
        max-width: none;
    }
}
</style></div>