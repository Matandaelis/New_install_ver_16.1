<?php
$ci =& get_instance();
$method = $ci->router->fetch_method();
$active = [
	'gateways'      => ($method === 'payment_gateway'),
	'transactions'  => ($method === 'all_transaction'),
	'uncompleted'   => ($method === 'uncompleted_payments'),
	'documentation' => ($method === 'payment_gateway_documentation')
];
?>
<div class="card bg-light border-0 mb-3">
	<div class="card-body py-2 px-3">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<nav class="nav nav-pills flex-grow-1" role="tablist">
				<a href="<?= base_url('admincontrol/payment_gateway') ?>" class="nav-link rounded-pill py-1 px-3 <?= $active['gateways'] ? 'active' : '' ?>">
					<i class="fas fa-credit-card me-1"></i><?= __('admin.nav_gateways') ?>
				</a>
				<a href="<?= base_url('admincontrol/all_transaction') ?>" class="nav-link rounded-pill py-1 px-3 <?= $active['transactions'] ? 'active' : '' ?>">
					<i class="fas fa-exchange-alt me-1"></i><?= __('admin.nav_transactions') ?>
				</a>
				<a href="<?= base_url('admincontrol/uncompleted_payments') ?>" class="nav-link rounded-pill py-1 px-3 <?= $active['uncompleted'] ? 'active' : '' ?>">
					<i class="fas fa-exclamation-triangle me-1"></i><?= __('admin.menu_uncompleted_payments') ?>
				</a>
				<a href="<?= base_url('admincontrol/payment_gateway_documentation') ?>" class="nav-link rounded-pill py-1 px-3 <?= $active['documentation'] ? 'active' : '' ?>">
					<i class="fas fa-book me-1"></i><?= __('admin.documentation') ?>
				</a>
			</nav>
			<?php if ($method === 'payment_gateway'): ?>
			<button id="toggle-uploader" class="btn btn-success btn-sm">
				<i class="fas fa-plus me-1"></i><?= __('admin.install_payment_gateway') ?>
			</button>
			<?php endif; ?>
		</div>
	</div>
</div>
