<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="mb-0">
              <i class="fas fa-shopping-cart me-2"></i><?= __('admin.orders') ?>
            </h4>
            <div class="d-flex gap-2 flex-wrap">
              <select class="form-select filter_status" style="min-width: 180px;">
                <option value=""><?= __('admin.all_status') ?></option>
                <?php foreach ($status as $key => $value) { ?>
                  <option value="<?= $key ?>"><?= $value ?></option>
                <?php } ?>
              </select>
              <button class="btn btn-light btn-sm" onclick="getPage(1, this)" data-original-text="<?= __('admin.search') ?>">
                <i class="fas fa-search me-1"></i><?= __('admin.search') ?>
              </button>
              <button class="btn btn-outline-light btn-sm" onclick="resetFilters()" title="<?= __('admin.reset_filters') ?>">
                <i class="fas fa-undo me-1"></i><?= __('admin.reset') ?>
              </button>
            </div>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover orders-table mb-0">
              <thead class="table-dark">
                <tr>
                  <th class="ps-3 text-white"><?= __('admin.order_id') ?></th>
                  <th class="text-white"><?= __('admin.total') ?></th>
                  <th class="text-center text-white"><?= __('admin.country') ?></th>
                  <th class="text-white"><?= __('admin.store') ?></th>
                  <th class="text-white"><?= __('admin.status') ?></th>
                  <th class="text-white"><?= __('admin.commission') ?></th>
                  <th class="text-white"><?= __('admin.date') ?></th>
                  <th class="text-center pe-3 text-white"><?= __('admin.actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                      <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                      </div>
                      <h5 class="text-muted mb-2"><?= __("admin.loading_orders_data_text") ?></h5>
                      <p class="text-muted small mb-0"><?= __("admin.not_taking_longer") ?></p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="card-footer bg-light border-top">
            <div class="d-flex justify-content-between align-items-center" id="pagination_info" style="display: none;">
              <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                <span id="showing_info"></span>
              </div>
              <div id="pagination_links"></div>
            </div>
            <div class="text-center text-muted py-2" id="no_pagination_info">
              <small><?= __('admin.loading_pagination_info') ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="modal-confirm" tabindex="-1" aria-labelledby="modalConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalConfirmLabel">
          <i class="fas fa-exclamation-triangle text-warning me-2"></i><?= __('admin.confirm_delete') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="modal-order-detail" tabindex="-1" aria-labelledby="modalOrderDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalOrderDetailLabel">
          <i class="fas fa-info-circle me-2"></i><?= __('admin.order_details') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Wallet Details Modal -->
<div class="modal fade" id="wallet-details-model" tabindex="-1" aria-labelledby="walletDetailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="walletDetailsLabel">
          <i class="fas fa-wallet me-2"></i><?= __('admin.transaction_details') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>



<script type="text/javascript">
    $(document).on('click', '.order-transactions-toggle', function(){
        $this = $(this);
        
        var uniqkey = $(this).data('order_type')+'-'+$(this).data('order_id');
        var orderType = $(this).data('order_type');
        var orderId = $(this).data('order_id');
        
        if($($this).hasClass("shown-transactions")){
            $('tr.'+uniqkey).remove();
            $(this).html('<i class="fas fa-list"></i>');
            $(this).removeClass("shown-transactions");
        } else {
            // Store original content
            var originalContent = $(this).html();
            
            // Show enhanced loading state
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading_transactions') ?>...');
            
            // Add timeout warning
            var timeoutWarning = setTimeout(function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.warning') ?>', '<?= __('admin.loading_taking_longer') ?>', 'warning');
                }
            }, 3000); // Reduced to 3 seconds
            
            // Add hard timeout
            var hardTimeout = setTimeout(function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.request_timeout') ?>', 'error');
                }
                $this.prop('disabled', false).html(originalContent);
            }, 15000); // 15 seconds hard timeout
            
    		$.ajax({
    			url:'<?= base_url("admincontrol/get_orders_transactions") ?>/'+orderType+'/'+orderId+'/order_page/1',
    			type:'GET',
    			dataType:'html',
    			timeout: 30000,
    			beforeSend:function(){
    			    $(this).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading_transactions') ?>...');
    			},
    			complete:function(){
    			    clearTimeout(timeoutWarning);
    			    clearTimeout(hardTimeout);
    			    $this.prop('disabled', false).html('<i class="fas fa-eye-slash"></i>');
    			    $this.addClass("shown-transactions");
    			},
    			error: function(xhr, status, error) {
    			    clearTimeout(timeoutWarning);
    			    clearTimeout(hardTimeout);
    			    $this.prop('disabled', false).html(originalContent);
    			    
    			    var errorMsg = '<?= __('admin.error_loading_transactions') ?>';
    			    if (status === 'timeout') {
    			        errorMsg = '<?= __('admin.request_timeout') ?>';
    			    }
    			    
    			    if (typeof showToast === 'function') {
    			        showToast('<?= __('admin.error') ?>', errorMsg, 'error');
    			    }
    			},
			success:function(html){
			        $($this).closest('tr').after(html);
			        
			        // Initialize tooltips for new content
			        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
			        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			            return new bootstrap.Tooltip(tooltipTriggerEl);
			        });
			        
			        // Initialize popovers for new content
			        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
			        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
			            return new bootstrap.Popover(popoverTriggerEl);
			        });
			        
			        // Handle wallet popover clicks
			        $(document).delegate('.wallet-popover','click', function(){
                		var html = $(this).parents("tr").find(".dpopver-content").html();
                        $(this).attr('data-bs-content', html);
                	    if($('.popover').hasClass('show')){
                	        $('.popover').remove()
                	    } else {
                	        $(this).popover('show');
                	    }
                	});
                
                	$('html').on('click', function(e) {
                	  if (typeof $(e.target).data('bs-original-title') == 'undefined' &&
                	     !$(e.target).parents().is('.popover.show')) {
                	    $('[data-bs-original-title]').popover('hide');
                	  }
                	});

			},
    		});
        }
    });

	$(".orders-table").delegate(".toggle-child-tr","click",function(){
        $tr = $(this).parents("tr");
        $ntr = $tr.next("tr.detail-tr");

        if($ntr.css("display") == 'table-row'){
            $ntr.hide();
            $(this).find("i").attr("class","fa fa-plus");
        }else{
            $(this).find("i").attr("class","fa fa-minus");
            $ntr.show();
        }
    })
    
	function getPage(page, t) {
		$this = $(t);
		var data = {
			page: page,
			filter_status: $(".filter_status").val(),
			action: 'order_page',
		}
		
		$.ajax({
			url: '<?= base_url("admincontrol/store_orders") ?>/' + page,
			type: 'POST',
			dataType: 'json',
			data: data,
			timeout: 30000,
			beforeSend: function(){
				$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...');
				$(".orders-table tbody").html(`
					<tr>
						<td colspan="8" class="text-center py-5">
							<div class="d-flex justify-content-center align-items-center flex-column">
								<div class="spinner-border text-primary mb-3" role="status">
									<span class="visually-hidden"><?= __('admin.loading') ?>...</span>
								</div>
								<h5 class="text-muted mb-2"><?= __("admin.loading_orders_data_text") ?></h5>
								<p class="text-muted small mb-0"><?= __("admin.not_taking_longer") ?></p>
							</div>
						</td>
					</tr>
				`);
				$("#no_pagination_info").show();
				$("#pagination_info").hide();
			},
			complete: function(){
				$this.prop('disabled', false).html($this.data('original-text') || '<i class="fas fa-search me-1"></i><?= __('admin.search') ?>');
			},
			success: function(json){
				if (json && json.html) {
					$(".orders-table tbody").html(json.html);
					$("#no_pagination_info").hide();
					
					if (json.pagination) {
						$("#pagination_info").show();
						$("#pagination_links").html(json.pagination);
						
						if (json.start_from && json.total) {
							var endFrom = Math.min(parseInt(json.start_from) + 24, parseInt(json.total));
							$("#showing_info").text('<?= __('admin.showing') ?> ' + json.start_from + ' <?= __('admin.to') ?> ' + endFrom + ' <?= __('admin.of') ?> ' + json.total + ' <?= __('admin.entries') ?>');
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
				
				$(".orders-table tbody").html(`
					<tr>
						<td colspan="8" class="text-center text-danger py-5">
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

	$(document).on('click', '#pagination_links a', function(e){
		e.preventDefault();
		getPage($(this).attr("data-ci-pagination-page"), $(this));
	});

	function resetFilters() {
		$(".filter_status").val('');
		getPage(1, $(".btn:contains('<?= __('admin.search') ?>')")[0]);
	}

	getPage(1);

	$(document).ready(function(){
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});
		
		$(document).on('DOMNodeInserted', '.orders-table tbody', function() {
			var newTooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]:not([data-bs-original-title])'));
			newTooltips.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl);
			});
		});
	});

	$(document).delegate(".remove-order", "click", function(){
		$this = $(this);
		var orderId = $this.attr("data-order_id");
		var orderType = $this.attr("data-order_type");
		
		if (!$this.data('original-html')) {
			$this.data('original-html', $this.html());
		}
		
		$.ajax({
			url: '<?= base_url("admincontrol/info_remove_order") ?>',
			type: 'POST',
			dataType: 'json',
			data: {id: orderId, type: orderType},
			timeout: 15000,
			beforeSend: function(){ 
				$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?>...');
			},
			complete: function(){ 
				$this.prop('disabled', false).html($this.data('original-html'));
			},
			success: function(json){
				if (json && json.html) {
					$("#modal-confirm .modal-body").html(json.html);
					$("#modal-confirm").modal("show");
				} else {
					if (typeof showToast === 'function') {
						showToast('<?= __('admin.error') ?>', '<?= __('admin.invalid_response') ?>', 'error');
					}
				}
			},
			error: function(xhr, status, error){
				var errorMsg = '<?= __('admin.error_occurred') ?>';
				if (status === 'timeout') {
					errorMsg = '<?= __('admin.request_timeout') ?>';
				}
				if (typeof showToast === 'function') {
					showToast('<?= __('admin.error') ?>', errorMsg, 'error');
				}
			}
		});
	});

	$("#modal-confirm .modal-body").delegate("[delete-order-confirm]", "click", function(){
		$this = $(this);
		var orderId = $this.attr("delete-order-confirm");
		var $orderRow = $("#order-row-" + orderId);
		
		if (!$this.data('original-html')) {
			$this.data('original-html', $this.html());
		}
		
		$.ajax({
			url: '<?= base_url("admincontrol/confirm_remove_order") ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				id: orderId, 
				sale_commission: $('input[name="sale_commission"]').prop('checked'),
				order_type: $('input[name="order_type"]').val()
			},
			timeout: 15000,
			beforeSend: function(){ 
				$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.processing') ?>...');
			},
			complete: function(){ 
				$this.prop('disabled', false).html($this.data('original-html'));
			},
			success: function(json){
				$("#modal-confirm").modal("hide");
				
				if ($orderRow.length) {
					$orderRow.fadeOut(400, function(){
						$(this).remove();
						
						var remainingRows = $(".orders-table tbody tr:visible").length;
						if (remainingRows === 0) {
							$(".orders-table tbody").html(`
								<tr>
									<td colspan="8" class="text-center py-5">
										<div class="text-muted">
											<i class="fas fa-inbox fs-1 mb-3"></i>
											<h5><?= __('admin.no_orders_found') ?></h5>
											<p><?= __('admin.no_orders_match_criteria') ?></p>
										</div>
									</td>
								</tr>
							`);
						}
					});
				}
				
				if (typeof showToast === 'function') {
					showToast('<?= __('admin.success') ?>', json.message || '<?= __('admin.order_deleted_successfully') ?>', 'success');
				}
				
				setTimeout(function(){
					getPage(1, $(".btn:contains('<?= __('admin.search') ?>')")[0]);
				}, 500);
			},
			error: function(xhr, status, error){
				var errorMsg = '<?= __('admin.error_deleting_order') ?>';
				if (status === 'timeout') {
					errorMsg = '<?= __('admin.request_timeout') ?>';
				} else if (xhr.responseJSON && xhr.responseJSON.message) {
					errorMsg = xhr.responseJSON.message;
				}
				
				if (typeof showToast === 'function') {
					showToast('<?= __('admin.error') ?>', errorMsg, 'error');
				}
			}
		});
	});

	$(document).delegate(".order-detail", "click",function(){
		let order_type = $(this).data('order_type');
		let order_id = $(this).data('order_id');

		if(order_type == 'ex')
			$("#modal-order-detail .modal-dialog").removeClass('modal-xl').addClass('modal-lg');
		else 
			$("#modal-order-detail .modal-dialog").removeClass('modal-lg').addClass('modal-xl');
			
		let template = jsOrders[order_type][order_id];

		$("#modal-order-detail .modal-body").html('');
		$("#modal-order-detail .modal-body").html(template);
		$("#modal-order-detail").modal("show");
	})

	// Load More Transactions functionality
	$(document).on('click', '.load-more-transactions', function(){
	    var $btn = $(this);
	    var orderType = $btn.data('order-type');
	    var orderId = $btn.data('order-id');
	    var page = $btn.data('page') || 2;
	    
	    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
	    
	    $.ajax({
	        url:'<?= base_url("admincontrol/get_orders_transactions") ?>/'+orderType+'/'+orderId+'/order_page/'+page,
	        type:'GET',
	        dataType:'json',
	        timeout: 30000,
	        success:function(response){
	            if (response && response.html) {
	                // Insert new transactions before the Load More button
	                $btn.closest('tr').before(response.html);
	                
	                // Update button for next page or hide if no more
	                if (response.has_more) {
	                    $btn.data('page', page + 1).html('<i class="fas fa-plus me-1"></i><?= __('admin.load_more_transactions') ?>');
	                } else {
	                    $btn.hide();
	                }
	                
	                $btn.prop('disabled', false);
	                
	                // Re-initialize tooltips and popovers for new content
	                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	                    return new bootstrap.Tooltip(tooltipTriggerEl);
	                });
	                
	                var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
	                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
	                    return new bootstrap.Popover(popoverTriggerEl);
	                });
	            }
	        },
	        error: function(xhr, status, error) {
	            $btn.prop('disabled', false).html('<i class="fas fa-plus me-1"></i><?= __('admin.load_more_transactions') ?>');
	            
	            if (typeof showToast === 'function') {
	                showToast('<?= __('admin.error') ?>', '<?= __('admin.error_loading_transactions') ?>', 'error');
	            }
	        }
	    });
	});

	$(document).on('click', '.view-tran-details', function () {
	    let data = {
	        type : $(this).data('comm_from'),
	        ref1 : $(this).data('ref_id_1'),
	        ref2 : $(this).data('ref_id_2')
	    };

	    $.ajax({
	        url:'<?= base_url('admincontrol/getOrderDetails') ?>',
	        type:'POST',
	        dataType:'html',
	        data:data,
	        success:function(response){
	            $('#wallet-details-model .modal-body').html(response);
	            $('#wallet-details-model').modal('show');
	        },
	    });
	});
</script>