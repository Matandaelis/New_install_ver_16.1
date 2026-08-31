<?php foreach($orders as $order){ ?>
	<tr>
		<td class="ps-4"><?php echo $order['id'];?></td>
		<td class="text-end"><?php echo c_format($order['total_sum']); ?></td>
		<td class="text-center">
			<span class="badge rounded-pill <?= ($order['status'] == 1) ? 'bg-success text-white' : 'bg-warning text-dark' ?>">
			    <?= $status[$order['status']] ?>
			</span>
		</td>
		<td><?php echo __('admin.'.array_search(str_replace("_", " ", $order['payment_method']),$payment_methods)); ?></td>
		<td>
			<?php if(!empty($order['country_code'])): ?>
				<img class="me-2" style="width: 20px;" src="<?= base_url('assets/template/images/flags/'. strtolower($order['country_code'])).'.png' ?>" alt="<?= $order['country_code'] ?>" /> 
			<?php endif; ?>
			<?= $order['ip'] ?>
		</td>
		<td><?php echo $order['txn_id'] ?: '-';?></td>
		<td class="text-center">
			<small class="text-muted"><?= date('M d, Y', strtotime($order['created_at'] ?? 'now')) ?></small>
		</td>
		<td class="text-center pe-4">
			<a href="<?= base_url('admincontrol/vieworder/'. $order['id']) ?>" class="btn btn-outline-primary btn-sm">
				<i class="bi bi-eye me-1"></i><?= __('admin.view') ?>
			</a>
		</td>
	</tr>
<?php } ?>
