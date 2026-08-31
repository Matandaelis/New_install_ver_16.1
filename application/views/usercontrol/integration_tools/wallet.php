<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header"><?= __('user.integration_wallet') ?>
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
			<div class="card-body">
				<?php if ($transaction ==null) {?>
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
				<?php } else { ?>         
					<div class="table-container">
						<table class="table table-sortable wallet-table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col"><?= __('user.comment') ?></th>
									<th scope="col"><?= __('user.order_total') ?></th>
									<th scope="col"><?= __('user.commission') ?></th>
									<th scope="col"><?= __('user.date') ?></th>
									<th scope="col"><?= __('user.type') ?></th>
									<th scope="col"><?= __('user.status') ?></th>
								</tr>
							</thead>
							<tbody>
								<?= $table ?>
							</tbody>
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
				<?php } ?>
			
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
</style></div><br>

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