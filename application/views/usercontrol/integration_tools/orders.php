<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<div class="pull-left">
					<h5 class="pull-left"><?= __('admin.integration_orders') ?></h5>
				
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
				<div class="pull-right">
					<div class="btn-group d-none btn-group-md delete-multiple-container" role="group">
				    
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

			<div class="card-body">
				<div class="table-rep-plugin">
				    <?php if ($orders ==null) {?>
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

                    	<div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table user-table  table-striped">
								<thead>
									<tr>
										<th width="50px">
											<?= __('admin.id') ?>		
										</th>
										<th width="90px"><?= __('admin.order_id') ?></th>
										<th width="180px"><?= __('admin.user_name') ?></th>
										<th><?= __('admin.product_ids') ?></th>
										<th><?= __('admin.total') ?></th>
										<th><?= __('admin.currency') ?></th>
										<th width="90px"><?= __('admin.commission_type') ?></th>
										<th><?= __('admin.commission') ?></th>
										<th><?= __('admin.ip') ?></th>
										<th><?= __('admin.country_code') ?></th>
										<th><?= __('admin.website') ?></th>
										<th><?= __('admin.script_name') ?></th>
										<th width="180px"><?= __('admin.created_at') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($orders as $key => $order) { ?>
										<tr>
											<td><?= $order['id'] ?></td>
											<td><?= $order['order_id'] ?></td>
											<td><?= $order['user_name'] ?></td>
											<td><?= $order['product_ids'] ?></td>
											<td><?= $order['total'] ?></td>
											<td><?= $order['currency'] ?></td>
											<td><?= $order['commission_type'] ?></td>
											<td><?= c_format($order['commission']) ?></td>
											<td><?= $order['ip'] ?></td>
											<td><?= $order['country_code'] ?>&nbsp;<img title="<?= $order['country_code'] ?>" src="<?= base_url('assets/template/images/flags/'. strtolower($order['country_code'])) ?>.png" width='25' height='15'></td>
											<td><a href="//<?= $order['base_url'] ?>" target='_blank'><?= $order['base_url'] ?></a></td>
											<td>
											<?php if(isset($order['script_name']) && $order['script_name'] == 's2s'): ?>
												<span class="badge bg-primary"><i class="fas fa-server me-1"></i><?= __('user.s2s_source_s2s') ?></span>
											<?php else: ?>
												<img class="img-integration" src="<?= base_url('assets/integration/small/' .$order['script_name'].'.png') ?>">
											<?php endif; ?>
										</td>
											<td><?= $order['created_at'] ?></td>
										</tr>
									<?php } ?>
									<?php } ?>
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