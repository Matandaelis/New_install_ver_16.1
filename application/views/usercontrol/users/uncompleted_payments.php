<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm all-transaction">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= __('user.uncompleted_payments') ?>
                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark me-2" id="total-count">0</span>
                            <a href="<?= base_url('usercontrol/all_transaction') ?>" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i><?= __('user.back_to_all_trans_user') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('usercontrol/uncompleted_payments') ?>" method="get" class="mb-4">
                        <div class="row g-3">
                            <div class="col-lg-6 col-md-6">
                                <label for="filter-module" class="form-label fw-semibold text-muted mb-1"><?= __('user.module') ?></label>
                                <select id="filter-module" class="form-select" name="module">
                                    <option value=""><?= __('user.all_modules') ?></option>
                                    <?php foreach($payment_module as $key => $value): ?>
                                        <option value="<?= $key ?>"><?= __('user.'.$value) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <label for="filter-date" class="form-label fw-semibold text-muted mb-1"><?= __('user.date') ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input id="filter-date" type="date" class="form-control" name="date">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="transaction-content">
                        <?= $html; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		updateTotalCount();
		
		$("select[name='module']").on('change', function() {
			callAjaxForFilter();
		});

		$("input[name='date']").on('change', function() {
			callAjaxForFilter();
		});

		$(document).on('click', '.pagination a', function(e) {
			e.preventDefault();
			let page = $(this).data('ci-pagination-page');
			callAjaxForFilter(page);
		});
	});

	function updateTotalCount() {
		var totalRows = $('.transaction-table tbody tr').length;
		$('#total-count').text(totalRows);
	}

	function callAjaxForFilter(page = 0) {
		$.ajax({
			url: "<?= base_url("usercontrol/uncompleted_payments") ?>",
			type: 'post',
			dataType: 'html',
			data: {
				module: $("select[name='module']").val(),
				date: $("input[name='date']").val(),
				page: page,
				ajax: 1
			},
			beforeSend: function() {
				$(".transaction-content").html('<div class="d-flex justify-content-center py-5"><div class="spinner-border text-primary" role="status" aria-label="Loading"><span class="visually-hidden">Loading...</span></div></div>');
			},
			success: function(html) {
				$(".transaction-content").html(html);
				updateTotalCount();
			},
			error: function() {
				$(".transaction-content").html('<div class="alert alert-danger text-center py-4"><i class="fas fa-exclamation-triangle me-2"></i><?= __("user.error_loading_data") ?></div>');
			}
		});
	}
</script>