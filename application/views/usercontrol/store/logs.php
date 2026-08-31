<div class="container-fluid">
<div class="clearfix">
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
<br>
<div class="card shadow-sm">
	<div class="card-header bg-white">
		<h4 class="card-title">Click Logs</h4>
	
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
			<table class="table table-hover table-striped click-table align-middle mb-0">
				<thead class="table-light">
					<tr>
						<th width="80px">#</th>
						<th width="80px"><?= __('user.click_id') ?></th>
						<th><?= __('user.website') ?></th>
						<th><?= __('user.ip') ?></th>
						<th><?= __('user.created_at') ?></th>
						<th><?= __('user.click_type') ?></th>
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
	 $(".click-table").delegate(".toggle-child-tr","click",function(){
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
		$.ajax({
			url:'<?= base_url("usercontrol/store_logs") ?>/' + page,
			type:'POST',
			dataType:'json',
			data:{page:page},
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				$(".click-table tbody").html(json['html']);
				$(".card-footer").hide();
				
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