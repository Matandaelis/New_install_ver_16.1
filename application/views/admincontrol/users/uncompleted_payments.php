<div class="container-fluid">
    <?php get_instance()->load->view('admincontrol/users/_payment_gateway_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm all-transaction">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= __('admin.menu_uncompleted_payments') ?>
                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark me-2" id="total-count">0</span>
                            <button type="button" class="btn btn-light btn-sm" onclick="resetFilters()">
                                <i class="fas fa-refresh me-1"></i><?= __('admin.reset') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('admincontrol/uncompleted_payments') ?>" method="get" class="mb-4">
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <label for="filter-module" class="form-label fw-semibold text-muted mb-1"><?= __('admin.module') ?></label>
                                <select id="filter-module" class="form-select" name="module">
                                    <option value=""><?= __('admin.all_modules') ?></option>
                                    <?php foreach ($payment_module as $key => $value): ?>
                                        <option value="<?= $key ?>"><?= __('admin.'.$value) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for="filter-user" class="form-label fw-semibold text-muted mb-1"><?= __('admin.user') ?></label>
                                <select id="filter-user" class="form-select" name="user">
                                    <option value=""><?= __('admin.all_users') ?></option>
                                    <?php foreach ($users as $value): ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['username'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for="filter-date" class="form-label fw-semibold text-muted mb-1"><?= __('admin.date') ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input id="filter-date" type="text" class="form-control datepicker" name="date" placeholder="<?= __('admin.select_date') ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i><?= __('admin.filter') ?>
                                </button>
                            </div>
                        </div>
                    </form>
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
		console.log('Initializing uncompleted payments page...');
		console.log('jQuery version:', $.fn.jquery);
		console.log('jQuery Datetimepicker available:', typeof $.fn.datetimepicker !== 'undefined');
		
		// Wait a bit for all scripts to load
		setTimeout(function() {
			initializeDatepicker();
			initializeFilters();
			updateTotalCount();
		}, 100);
	});

	function initializeDatepicker() {
		// Check if datetimepicker is available
		if (typeof $.fn.datetimepicker === 'undefined') {
			console.error('jQuery Datetimepicker is not loaded');
			return;
		}

		console.log('Initializing jQuery Datetimepicker...');

		$('.datepicker').off('change').datetimepicker('destroy').datetimepicker({
			timepicker: false,
			format: 'd-m-Y',
			formatDate: 'd-m-Y',
			closeOnDateSelect: true,
			scrollInput: false,
			validateOnBlur: false,
			allowBlank: true,
			yearStart: 2020,
			yearEnd: 2030,
			lang: 'en'
		});

		$('.datepicker').each(function() {
			var d = $(this).val().split("-");
			if (d[0] && d[1] && d[2]) {
				var date = d[1] + "/" + d[0] + "/" + d[2];
				$(this).val(d[0] + "-" + d[1] + "-" + d[2]);
			} else {
				$(this).val('');
			}
		});

		$('.datepicker').on('change', function() {
			callAjaxForFilter();
		});
	}

	function initializeFilters() {
		$("select[name='module'], select[name='user']").on('change', function() {
			callAjaxForFilter();
		});

		$("input[name='date']").on('keyup', function() {
			if ($(this).val().length == 0) {
				callAjaxForFilter();
			}
		});

		$(document).on('click', '.pagination a', function(e) {
			e.preventDefault();
			let href = $(this).attr('href');
			let page = 1;
			
			// Extract page number from URL like /admincontrol/uncompleted_payments/2
			let pageMatch = href.match(/\/(\d+)(?:\?|$)/);
			if(pageMatch && pageMatch[1]) {
				page = parseInt(pageMatch[1]);
			} else {
				// Fallback: check for ?page= parameter
				let pageParam = href.match(/[?&]page=(\d+)/);
				if(pageParam && pageParam[1]) {
					page = parseInt(pageParam[1]);
				}
			}
			
			callAjaxForFilter(page);
		});
	}

	function resetFilters() {
		$("select[name='module'], select[name='user']").val('').trigger('change');
		$("input[name='date']").val('');
		callAjaxForFilter();
	}

	function updateTotalCount() {
		var totalRows = $('.transaction-table tbody tr').length;
		$('#total-count').text(totalRows);
	}

	function callAjaxForFilter(page = 1) {
		$.ajax({
			url: "<?= base_url("admincontrol/uncompleted_payments") ?>",
			type: 'post',
			dataType: 'html',
			data: {
				module: $("select[name='module']").val(),
				user: $("select[name='user']").val(),
				date: $("input[name='date']").val(),
				page: page,
				ajax: 1
			},
			beforeSend: function() {
				$(".transaction-content").html('<div class="d-flex justify-content-center py-5"><div class="spinner-border text-primary" role="status" aria-label="Loading"><span class="visually-hidden">Loading...</span></div></div>');
			},
			success: function(html) {
				$(".transaction-content").html(html);
				initializeDatepicker();
				updateTotalCount();
			},
			error: function() {
				$(".transaction-content").html('<div class="alert alert-danger text-center py-4"><i class="fas fa-exclamation-triangle me-2"></i><?= __("admin.error_loading_data") ?></div>');
			}
		});
	}
</script>
