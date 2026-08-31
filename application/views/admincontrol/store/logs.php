<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header bg-primary text-white">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="mb-0">
							<i class="fas fa-chart-line me-2"></i><?= __('admin.click_logs') ?>
						</h5>
						<div class="d-flex gap-2">
							<button class="btn btn-outline-light btn-sm" onclick="refreshLogs()" title="<?= __('admin.refresh') ?>">
								<i class="fas fa-sync-alt me-1"></i><?= __('admin.refresh') ?>
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover click-table mb-0">
							<thead class="table-dark">
								<tr>
									<th class="ps-3 text-white">#</th>
									<th class="text-white"><?= __('admin.click_id') ?></th>
									<th class="text-white"><?= __('admin.website') ?></th>
									<th class="text-white"><?= __('admin.ip') ?></th>
									<th class="text-white"><?= __('admin.created_at') ?></th>
									<th class="text-white"><?= __('admin.integration_type') ?></th>
									<th class="text-white"><?= __('admin.custom_data') ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="7" class="text-center py-5">
										<div class="d-flex justify-content-center align-items-center flex-column">
											<div class="spinner-border text-primary mb-3" role="status">
												<span class="visually-hidden"><?= __('admin.loading') ?>...</span>
											</div>
											<h5 class="text-muted mb-2"><?= __('admin.loading_logs_data_text') ?></h5>
											<p class="text-muted small mb-0"><?= __('admin.not_taking_longer') ?></p>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer bg-light border-top">
					<div class="d-flex justify-content-between align-items-center" id="pagination_info" style="display: none;">
						<div class="text-muted small">
							<i class="fas fa-info-circle me-1"></i>
							<span id="showing_info"></span>
						</div>
						<div class="pagination"></div>
					</div>
					<div class="text-center text-muted py-2" id="no_pagination_info">
						<small><?= __('admin.loading_pagination_info') ?></small>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
	$(".click-table").delegate(".toggle-child-tr","click",function(){
        $tr = $(this).parents("tr");
        $ntr = $tr.next("tr.detail-tr");

        if($ntr.css("display") == 'table-row'){
            $ntr.hide();
            $(this).find("i").attr("class","fas fa-plus");
        }else{
            $(this).find("i").attr("class","fas fa-minus");
            $ntr.show();
        }
    });
    
	function getPage(page, t) {
		$this = $(t);
		$.ajax({
			url:'<?= base_url("admincontrol/store_logs") ?>/' + page,
			type:'POST',
			dataType:'json',
			data:{page:page},
			timeout: 30000,
			beforeSend:function(){
				$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...');
				$(".click-table tbody").html(`
					<tr>
						<td colspan="7" class="text-center py-5">
							<div class="d-flex justify-content-center align-items-center flex-column">
								<div class="spinner-border text-primary mb-3" role="status">
									<span class="visually-hidden"><?= __('admin.loading') ?>...</span>
								</div>
								<h5 class="text-muted mb-2"><?= __('admin.loading_logs_data_text') ?></h5>
								<p class="text-muted small mb-0"><?= __('admin.not_taking_longer') ?></p>
							</div>
						</td>
					</tr>
				`);
				$("#no_pagination_info").show();
				$("#pagination_info").hide();
			},
			complete:function(){
				$this.prop('disabled', false).html($this.data('original-text') || '<?= __('admin.refresh') ?>');
			},
			success:function(json){
				if (json && json.html) {
					$(".click-table tbody").html(json.html);
					$("#no_pagination_info").hide();
					
					if (json.pagination) {
						$("#pagination_info").show();
						$(".pagination").html(json.pagination);
						
						if (json.pagination_summary) {
							$("#showing_info").html(json.pagination_summary);
						}
					} else {
						$("#pagination_info").hide();
						$("#no_pagination_info").show().html('<small class="text-muted"><?= __('admin.no_pagination_needed') ?></small>');
					}
					
					if (typeof showToast === 'function' && json.message) {
						showToast('<?= __('admin.success') ?>', json.message, 'success');
					}
				} else {
					throw new Error('Invalid response format');
				}
			},
			error: function(xhr, status, error){
				var errorMsg = '<?= __('admin.error_loading_data') ?>';
				if (status === 'timeout') {
					errorMsg = '<?= __('admin.request_timeout') ?>';
				} else if (xhr.responseJSON && xhr.responseJSON.message) {
					errorMsg = xhr.responseJSON.message;
				}
				
				$(".click-table tbody").html(`
					<tr>
						<td colspan="7" class="text-center text-danger py-5">
							<div class="d-flex justify-content-center align-items-center flex-column">
								<i class="fas fa-exclamation-triangle fs-1 text-danger mb-3"></i>
								<h5 class="text-danger mb-2"><?= __('admin.error') ?></h5>
								<p class="text-muted mb-3">${errorMsg}</p>
								<button class="btn btn-outline-primary btn-sm" onclick="getPage(${page}, this)">
									<i class="fas fa-redo me-1"></i><?= __('admin.retry') ?>
								</button>
							</div>
						</td>
					</tr>
				`);
				
				$("#no_pagination_info").show().html('<small class="text-danger"><?= __('admin.error_loading_pagination') ?></small>');
				$("#pagination_info").hide();
				
				if (typeof showToast === 'function') {
					showToast('<?= __('admin.error') ?>', errorMsg, 'error');
				}
			}
		});
	}

	function refreshLogs() {
		getPage(1, $(".btn:contains('<?= __('admin.refresh') ?>')")[0]);
	}

	// Pagination is handled by ajax_pagination onclick handlers
	// No need for additional click handlers

	$(document).ready(function(){
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});
		
		$(document).on('DOMNodeInserted', '.click-table tbody', function() {
			var newTooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]:not([data-bs-original-title])'));
			newTooltips.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl);
			});
		});
	});

	getPage(1);
</script>