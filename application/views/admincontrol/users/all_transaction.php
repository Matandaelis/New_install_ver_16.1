<div class="container-fluid">
    <?php get_instance()->load->view('admincontrol/users/_payment_gateway_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-exchange-alt me-2"></i><?= __('admin.menu_all_transactions') ?>
                        </h5>
                        <div class="btn-group" role="group">
                            <a href="<?= base_url('admincontrol/all_transaction_export_to_excel') ?>" class="btn btn-light btn-sm export-excel">
                                <i class="fas fa-file-excel me-1"></i><?= __('admin.all_transaction_export_to_excel') ?>
                            </a>
                            <a href="<?= base_url('admincontrol/all_transaction_export_to_pdf') ?>" class="btn btn-light btn-sm export-pdf">
                                <i class="fas fa-file-pdf me-1"></i><?= __('admin.all_transaction_export_to_pdf') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Filter Form -->
                    <div class="bg-light border-bottom p-4">
                        <form action="<?= base_url('admincontrol/all_transaction') ?>" method="get" id="filter-form">
                            <div class="row g-3">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label for="filter-module" class="form-label fw-semibold text-muted small"><?= __('admin.module') ?></label>
                                    <select id="filter-module" class="form-select form-select-sm" name="module">
                                        <option value=""><?= __('admin.all') ?></option>
                                        <?php foreach ($payment_module as $key => $value): ?>
                                            <option value="<?= $value ?>"><?= __('admin.'.$value) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label for="filter-user" class="form-label fw-semibold text-muted small"><?= __('admin.user') ?></label>
                                    <select id="filter-user" class="form-select form-select-sm" name="user">
                                        <option value=""><?= __('admin.all') ?></option>
                                        <?php foreach ($filter_field['user'] as $key => $value): ?>
                                            <option value="<?= $key ?>" <?= (isset($selected_user_id) && $selected_user_id == $key) ? 'selected' : '' ?>><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label for="filter-date" class="form-label fw-semibold text-muted small"><?= __('admin.date') ?></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input id="filter-date" type="text" class="form-control datepicker" name="date" placeholder="<?= __('admin.date') ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label for="filter-payment-gateway" class="form-label fw-semibold text-muted small"><?= __('admin.payment_gateway') ?></label>
                                    <select id="filter-payment-gateway" class="form-select form-select-sm" name="payment_gateway">
                                        <option value=""><?= __('admin.all') ?></option>
                                        <?php foreach ($filter_field['payment_gateway'] as $key => $value): ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label for="filter-status" class="form-label fw-semibold text-muted small"><?= __('admin.status') ?></label>
                                    <select id="filter-status" class="form-select form-select-sm" name="status">
                                        <option value=""><?= __('admin.all') ?></option>
                                        <?php foreach ($filter_field['status'] as $key => $value): ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label for="filter-transaction" class="form-label fw-semibold text-muted small"><?= __('admin.transaction_id') ?></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        <input id="filter-transaction" type="text" class="form-control" name="transaction" placeholder="<?= __('admin.transaction_id') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filters">
                                            <i class="fas fa-times me-1"></i><?= __('admin.clear_filters') ?>
                                        </button>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary btn-sm" id="apply-filters">
                                                <i class="fas fa-search me-1"></i><?= __('admin.apply_filters') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Transaction Content -->
                    <div class="transaction-content">
                        <?= $html ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		// Initialize datepicker
		$('.datepicker').datepicker({
			autoclose: false,
			todayHighlight: true,
			format: "dd-mm-yyyy",
			forceParse: false,
			keyboardNavigation: false
		});

		// Prevent immediate closing by stopping event propagation
		$('.datepicker').on('focus click', function(e) {
			e.stopPropagation();
			$(this).datepicker('show');
		});

		// Initialize existing date values
		$('.datepicker').each(function() {
			var d = $(this).val().split("-");
			if (d[0]) {
				var date = d[1] + "-" + d[2] + "-" + d[0];
				$(this).datepicker('update', new Date(date));
			} else {
				$(this).val('');
			}
		});

		// Auto-filter on change
		$("select[name='module'], select[name='user'], select[name='payment_gateway'], select[name='status']").on('change', function() {
			callAjaxForFilter();
		});

		// Date change filter
		$('.datepicker').datepicker().on('changeDate', function(ev) {
			callAjaxForFilter();
		});

		$("input[name='date']").on('keyup', function() {
			if ($(this).val().length == 0)
				callAjaxForFilter();
		});

		// Transaction ID search with debounce
		let transactionTimeout;
		$("input[name='transaction']").on('keyup', function() {
			clearTimeout(transactionTimeout);
			transactionTimeout = setTimeout(function() {
				if ($("input[name='transaction']").val().length == 0 || $("input[name='transaction']").val().length > 3)
					callAjaxForFilter();
			}, 500);
		});

		// Apply filters button
		$('#apply-filters').on('click', function() {
			callAjaxForFilter();
		});

		// Clear filters button
		$('#clear-filters').on('click', function() {
			$('#filter-form')[0].reset();
			callAjaxForFilter();
		});

		// Export functionality
		$('.export-excel, .export-pdf').on('click', function(e) {
			e.preventDefault();
			let data = getFilterData();
			let queryData = encodeQueryData(data);
			let url = $(this).attr('href') + '?' + queryData;
			window.open(url, '_blank');
		});

		// Auto-trigger filter if user is pre-selected
		<?php if(isset($selected_user_id) && $selected_user_id): ?>
			callAjaxForFilter();
		<?php endif; ?>
	});

	function callAjaxForFilter(page = 0) {
		$.ajax({
			url: $('#filter-form').attr('action'),
			type: 'post',
			dataType: 'html',
			data: {
				module: $("select[name='module']").val(),
				user: $("select[name='user']").val(),
				date: $("input[name='date']").val(),
				payment_gateway: $("select[name='payment_gateway']").val(),
				status: $("select[name='status']").val(),
				transaction: $("input[name='transaction']").val(),
				page: page
			},
			beforeSend: function(){
				$(".transaction-content").html('<div class="d-flex justify-content-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
			},
			success: function(html) {
				$(".transaction-content").html(html);
			},
			error: function() {
				$(".transaction-content").html('<div class="alert alert-danger text-center py-4"><i class="fas fa-exclamation-triangle me-2"></i>Error loading data. Please try again.</div>');
			}
		});
	}

	function getFilterData() {
		return {
			module: $("select[name='module']").val(),
			user: $("select[name='user']").val(),
			date: $("input[name='date']").val(),
			payment_gateway: $("select[name='payment_gateway']").val(),
			status: $("select[name='status']").val(),
			transaction: $("input[name='transaction']").val()
		};
	}

	function encodeQueryData(data) {
		const ret = [];
		for (let d in data)
			ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]));
		return ret.join('&');
	}
</script>
