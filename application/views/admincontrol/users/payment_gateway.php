<div class="container-fluid">
  <!-- Page title + Help Tour -->
  <div class="row mb-2">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <div class="page-title-dot me-2"></div>
          <h1 class="page-title mb-0"><?= __('admin.payment_gateways') ?></h1>
        </div>
        <button type="button" class="btn btn-outline-info btn-sm rounded-pill" onclick="loadTourSystem()" title="<?= __('admin.help_tour') ?>">
          <i class="fas fa-question-circle me-1"></i><?= __('admin.help_tour') ?>
        </button>
      </div>
    </div>
  </div>
  <?php get_instance()->load->view('admincontrol/users/_payment_gateway_nav'); ?>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">

			<div class="payment-gateway-uploader card mb-4 d-none">
				<div class="card-header bg-light border-0">
					<h5 class="card-title mb-0">
						<i class="bi bi-upload me-2"></i><?= __('admin.install_new_gateway') ?>
					</h5>
				</div>
				<div class="card-body">
					<div class="alert alert-info d-flex align-items-center mb-3">
						<i class="bi bi-info-circle me-2"></i>
						<div>
							<?= __('admin.payment_gateway_install_desc') ?> 
							<a href="<?= base_url('admincontrol/payment_gateway_documentation') ?>" target="_blank" class="alert-link">
								<?= __('admin.documentation') ?>
							</a>
						</div>
					</div>
					<div class="row align-items-end">
						<div class="col-md-8">
							<label for="payment-gateway-zip" class="form-label"><?= __('admin.select_gateway_file') ?></label>
							<input id="payment-gateway-zip" type="file" name="install" class="form-control" accept=".zip">
							<div class="bg-danger px-3 py-2 mt-2 text-white warning d-none rounded"></div>
						</div>
						<div class="col-md-4">
							<button id="payment-gateway-button" class="btn btn-success w-100" disabled>
								<i class="bi bi-download me-2"></i><?= __('admin.install_now') ?>
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header bg-primary text-white">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h5 class="card-title mb-0 text-white">
								<i class="bi bi-credit-card me-2"></i><?= __('admin.installed_gateways') ?>
							</h5>
							<small class="text-white opacity-75"><?= __('admin.configure_payment_methods') ?></small>
						</div>
						<div class="d-flex align-items-center">
							<span class="badge bg-light text-primary me-2"><?= count($payment_gateways) ?> <?= __('admin.gateways') ?></span>
						</div>
					</div>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead class="table-light">
								<tr>
									<th class="border-0 ps-4"></th>
									<th class="border-0"><?= __('admin.gateway_name') ?></th>
									<th class="border-0 text-center"><?= __('admin.store') ?></th>
									<th class="border-0 text-center"><?= __('admin.deposit') ?></th>
									<th class="border-0 text-center"><?= __('admin.membership') ?></th>
									<th class="border-0 text-center"><?= __('admin.integration') ?></th>
									<th class="border-0 text-center"><?= __('admin.actions') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($payment_gateways as $key => $payment_gateway){ ?>
								<tr class="<?= ($payment_gateway['name'] == 'opay' || $payment_gateway['name'] == 'paytm') ? 'table-secondary disabled-payment-gateway' : '' ?>">
									<td class="ps-4">
										<?php if($payment_gateway['icon']): ?>
											<img src="<?= base_url($payment_gateway['icon']) ?>" alt="<?= $payment_gateway['title'] ?>" class="rounded">
										<?php else: ?>
											<div class="bg-light rounded d-flex align-items-center justify-content-center">
												<i class="bi bi-credit-card text-muted"></i>
											</div>
										<?php endif; ?>
									</td>
									<td>
										<div class="d-flex flex-column">
											<span class="fw-semibold"><?= $payment_gateway['title'] ?></span>
											<?php if($payment_gateway['name'] == 'opay' || $payment_gateway['name'] == 'paytm'): ?>
												<small class="text-warning">
													<i class="bi bi-exclamation-triangle me-1"></i><?= __('admin.not_available') ?>
												</small>
											<?php endif; ?>
										</div>
									</td>

									<td class="text-center">
										<div class="d-flex align-items-center justify-content-center">
											<div class="btn-group btn-group-sm me-2" role="group">
												<input type="radio" class="btn-check status-change-rdo" 
													name="status_store_<?= $payment_gateway['name'] ?>" 
													id="status_store_off_<?= $payment_gateway['name'] ?>"
													data-config="store"
													data-method="<?= $payment_gateway['name'] ?>"
													value="0"
													<?= $payment_gateway['store']['status'] ? '' : 'checked' ?>>
												<label class="btn btn-outline-danger" for="status_store_off_<?= $payment_gateway['name'] ?>">
													<?= __('admin.off') ?>
												</label>

												<input type="radio" class="btn-check status-change-rdo"
													name="status_store_<?= $payment_gateway['name'] ?>"
													id="status_store_on_<?= $payment_gateway['name'] ?>"
													data-config="store"
													data-method="<?= $payment_gateway['name'] ?>"
													value="1"
													<?= $payment_gateway['store']['status'] ? 'checked' : '' ?>>
												<label class="btn btn-outline-success" for="status_store_on_<?= $payment_gateway['name'] ?>">
													<?= __('admin.on') ?>
												</label>
											</div>
											<?php if($payment_gateway['store']['setting_is_default']): ?>
												<span class="badge bg-primary" title="<?= __('admin.default_gateway') ?>">
													<i class="bi bi-star-fill"></i>
												</span>
											<?php else: ?>
												<button type="button" class="btn btn-link btn-sm p-0 default-button" 
													data-config="store" data-method="<?= $payment_gateway['name'] ?>"
													title="<?= __('admin.set_as_default') ?>">
													<i class="bi bi-star text-muted"></i>
												</button>
											<?php endif; ?>
										</div>
									</td>





									<td class="text-center">
										<div class="d-flex align-items-center justify-content-center">
											<div class="btn-group btn-group-sm me-2" role="group">
												<input type="radio" class="btn-check status-change-rdo" 
													name="status_deposit_<?= $payment_gateway['name'] ?>" 
													id="status_deposit_off_<?= $payment_gateway['name'] ?>"
													data-config="deposit"
													data-method="<?= $payment_gateway['name'] ?>"
													value="0"
													<?= $payment_gateway['deposit']['status'] ? '' : 'checked' ?>>
												<label class="btn btn-outline-danger" for="status_deposit_off_<?= $payment_gateway['name'] ?>">
													<?= __('admin.off') ?>
												</label>

												<input type="radio" class="btn-check status-change-rdo"
													name="status_deposit_<?= $payment_gateway['name'] ?>"
													id="status_deposit_on_<?= $payment_gateway['name'] ?>"
													data-config="deposit"
													data-method="<?= $payment_gateway['name'] ?>"
													value="1"
													<?= $payment_gateway['deposit']['status'] ? 'checked' : '' ?>>
												<label class="btn btn-outline-success" for="status_deposit_on_<?= $payment_gateway['name'] ?>">
													<?= __('admin.on') ?>
												</label>
											</div>
											<?php if($payment_gateway['deposit']['setting_is_default']): ?>
												<span class="badge bg-primary" title="<?= __('admin.default_gateway') ?>">
													<i class="bi bi-star-fill"></i>
												</span>
											<?php else: ?>
												<button type="button" class="btn btn-link btn-sm p-0 default-button" 
													data-config="deposit" data-method="<?= $payment_gateway['name'] ?>"
													title="<?= __('admin.set_as_default') ?>">
													<i class="bi bi-star text-muted"></i>
												</button>
											<?php endif; ?>
										</div>
									</td>




									<td class="text-center">
										<div class="d-flex align-items-center justify-content-center">
											<div class="btn-group btn-group-sm me-2" role="group">
												<input type="radio" class="btn-check status-change-rdo"
													name="status_membership_<?= $payment_gateway['name'] ?>"
													id="status_membership_off_<?= $payment_gateway['name'] ?>"
													data-config="membership"
													data-method="<?= $payment_gateway['name'] ?>"
													value="0"
													<?= $payment_gateway['membership']['status'] ? '' : 'checked' ?>>
												<label class="btn btn-outline-danger" for="status_membership_off_<?= $payment_gateway['name'] ?>">
													<?= __('admin.off') ?>
												</label>

												<input type="radio" class="btn-check status-change-rdo"
													name="status_membership_<?= $payment_gateway['name'] ?>"
													id="status_membership_on_<?= $payment_gateway['name'] ?>"
													data-config="membership"
													data-method="<?= $payment_gateway['name'] ?>"
													value="1"
													<?= $payment_gateway['membership']['status'] ? 'checked' : '' ?>>
												<label class="btn btn-outline-success" for="status_membership_on_<?= $payment_gateway['name'] ?>">
													<?= __('admin.on') ?>
												</label>
											</div>
											<?php if($payment_gateway['membership']['setting_is_default']): ?>
												<span class="badge bg-primary" title="<?= __('admin.default_gateway') ?>">
													<i class="bi bi-star-fill"></i>
												</span>
											<?php else: ?>
												<button type="button" class="btn btn-link btn-sm p-0 default-button" 
													data-config="membership" data-method="<?= $payment_gateway['name'] ?>"
													title="<?= __('admin.set_as_default') ?>">
													<i class="bi bi-star text-muted"></i>
												</button>
											<?php endif; ?>
										</div>
									</td>

									<td class="text-center">
										<div class="d-flex align-items-center justify-content-center">
											<div class="btn-group btn-group-sm me-2" role="group">
												<input type="radio" class="btn-check status-change-rdo"
													name="status_integration_<?= $payment_gateway['name'] ?>"
													id="status_integration_off_<?= $payment_gateway['name'] ?>"
													data-config="integration"
													data-method="<?= $payment_gateway['name'] ?>"
													value="0"
													<?= $payment_gateway['integration']['status'] ? '' : 'checked' ?>>
												<label class="btn btn-outline-danger" for="status_integration_off_<?= $payment_gateway['name'] ?>">
													<?= __('admin.off') ?>
												</label>

												<input type="radio" class="btn-check status-change-rdo"
													name="status_integration_<?= $payment_gateway['name'] ?>"
													id="status_integration_on_<?= $payment_gateway['name'] ?>"
													data-config="integration"
													data-method="<?= $payment_gateway['name'] ?>"
													value="1"
													<?= $payment_gateway['integration']['status'] ? 'checked' : '' ?>>
												<label class="btn btn-outline-success" for="status_integration_on_<?= $payment_gateway['name'] ?>">
													<?= __('admin.on') ?>
												</label>
											</div>
											<?php if($payment_gateway['integration']['setting_is_default']): ?>
												<span class="badge bg-primary" title="<?= __('admin.default_gateway') ?>">
													<i class="bi bi-star-fill"></i>
												</span>
											<?php else: ?>
												<button type="button" class="btn btn-link btn-sm p-0 default-button" 
													data-config="integration" data-method="<?= $payment_gateway['name'] ?>"
													title="<?= __('admin.set_as_default') ?>">
													<i class="bi bi-star text-muted"></i>
												</button>
											<?php endif; ?>
										</div>
									</td>

									<td class="text-center">
										<div class="btn-group btn-group-sm" role="group">
											<button type="button" class="btn btn-outline-primary btn-edit-payment-gateway" 
												data-method="<?= $payment_gateway['name'] ?>"
												data-href="<?= base_url('admincontrol/payment_gateway_edit/'.$payment_gateway['name']) ?>"
												title="<?= __('admin.edit_settings') ?>">
												<i class="bi bi-gear"></i>
											</button>
											
											<button type="button" class="btn btn-outline-<?= ($payment_gateway['is_install'] == 1) ? 'danger' : 'success' ?> btn-install-payment-gateway" 
												data-method="<?= $payment_gateway['name'] ?>"
												data-href="<?= base_url('admincontrol/payment_gateway_status_change/'. $payment_gateway['name']) ?>"
												title="<?= ($payment_gateway['is_install'] == 1) ? __('admin.uninstall') : __('admin.install') ?>">
												<i class="bi bi-power"></i>
											</button>

											<?php if (!in_array($payment_gateway['name'], $payment_method)): ?>
												<button type="button" class="btn btn-outline-danger btn-delete-gateway" 
													data-method="<?= $payment_gateway['name'] ?>"
													data-href="<?= base_url('admincontrol/delete_payment_gateway/'.$payment_gateway['name']) ?>"
													title="<?= __('admin.delete_gateway') ?>">
													<i class="bi bi-trash"></i>
												</button>
											<?php endif; ?>
										</div>
									</td>



								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= performance_indicator_html('payment-gateway-performance') ?>
<?= render_performance_indicator('payment-gateway-performance') ?>

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>

<script type="text/javascript">
	$( document ).ready(function() {
		PerformanceMonitor.start();
		let last_pill = localStorage.getItem("last_pill");
		if(last_pill){ $('[href="'+ last_pill +'"]').click() }
		PerformanceMonitor.end();
	});

	$("#toggle-uploader").on("click",function(){
		$(".payment-gateway-uploader").toggleClass('d-none');
		$(this).find('i').toggleClass('bi-plus-circle bi-x-circle');
	})

	$("#payment-gateway-zip").on("change",function(){
		if($(this).val() == ''){
			$("#payment-gateway-button").prop("disabled",1)
		} else{
			$("#payment-gateway-button").prop("disabled",0)
		}
	})

	$("#payment-gateway-button").on("click", function(evt){
		evt.preventDefault();
        $btn = $(this);

        $(".payment-gateway-uploader .warning").addClass('d-none');

        var formData = new FormData();
        formData.append("install", $("#payment-gateway-zip")[0].files[0]);
       	$btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
        
	$.ajax({
	    url: '<?= base_url("admincontrol/payment_gateway_install") ?>',
	    type: 'POST',
	    dataType: 'json',
	    cache: false,
	    contentType: false,
	    processData: false,
	    data: formData,
	    error: function(){
	        $btn.prop('disabled', false).html($btn.data('original-text') || 'Submit');
	    },
	    success: function(result){
	        if(result['status'] === 'error') {
	            showPrintMessage(result['message'], 'error');
	            return;
	        }

	        $btn.prop('disabled', false).html($btn.data('original-text') || 'Submit');
	        
	        if(result['location']){
	            window.location.reload();
	        }
	        if(result['warning']){
	            $(".payment-gateway-uploader .warning").html(result['warning']);
	            $(".payment-gateway-uploader .warning").removeClass('d-none');
	        }
	    },
	});

	})

	$(document).delegate(".btn-deletes",'click',function(){
		$this = $(this);

		Swal.fire({
			title: '<?= __('admin.are_you_sure') ?>',
			text: '<?= __('admin.comission_will_revert_back_to_user_wallet') ?>',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: '<?= __('admin.yes_revert') ?>'
		}).then((result) => {
			if (result.value) {
				var ids = $(".wallet-checkbox:checked").map(function(){ return $(this).val() }).toArray();

				$this = $(this);
				$.ajax({
					type:'POST',
					dataType:'json',
					data:{delete_request: true,id:$this.data("id")},
					beforeSend:function(){ $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...'); },
					complete:function(){ $this.prop('disabled', false).html($this.data('original-text') || 'Submit'); },
					success:function(json){
						if (json['error']) {
							Swal.fire("Error", json['error'], "error");
						}
						if (json['success']) {
							$this.parents("tr").remove();
							Swal.fire({
								title: '<?= __('admin.success') ?>',
								text: '<?= __('admin.comission_is_reverted_back_to_user_wallet') ?>',
								icon: 'success',
							}).then((result) => {
							})
						}
					},
				})
			}
		})
	});

	$(".status-change-rdo").on('change',function(){
		$this = $(this);
		$loading = $this.parents(".wallet-status-switch").find(".loading");
		
		if($this.data('method') != 'opay' && $this.data('method') != 'paytm'){
			$.ajax({
				type:'POST',
				dataType:'json',
				data:{
						action : 'status',
						config : $this.data('config'),
						method : $this.data('method'),
						value : $this.val()
					},
				beforeSend:function(){$loading.show();},
				complete:function(){$loading.hide();},
				success:function(json){
				},
			})
		} else {
			let name = $(this).attr('name');
			$('input[name="' + name + '"][value="1"]').prop('checked',false);
			$('input[name="' + name + '"][value="0"]').prop('checked',true);

			Swal.fire({
				text: '<?= __('admin.payment_method_not_available') ?>',
				icon: 'warning',
				confirmButtonColor: '#3085d6',
				confirmButtonText: '<?= __('admin.ok') ?>'
			})
		}
	});

$(".default-button").on('click', function() {
    let $this = $(this);
    if($this.data('method') != 'opay' && $this.data('method') != 'paytm'){
        let activeGateway = $this.closest('td').find('input:checked').val();
        if(activeGateway == 1 && !$this.hasClass('active')){
            $.ajax({
                type:'POST',
                dataType:'json',
                data:{
                    action : 'default',
                    config : $this.data('config'),
                    method : $this.data('method'),
                    value : 1
                },
                success:function(json){
                    if(json.result){
                        $(".default-button[data-config='"+$this.data('config')+"']").removeClass('active');
                        $this.addClass('active');
                        location.reload();
                    }
                },
            })
        }
    } else {
        $this.removeClass('active');
        Swal.fire({
            text: '<?= __('admin.payment_method_not_available') ?>',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: '<?= __('admin.ok') ?>'
        })
    }
});

$(".status-change-rdo").on('change', function() {
    let $this = $(this);
    let otherOption = $this.siblings('input.status-change-rdo');
    if ($this.is(':checked')) {
        otherOption.prop('checked', false);
    }
});


	$('.btn-edit-payment-gateway').on('click',function(e){
		e.preventDefault();
		if($(this).data('method') != 'opay' && $(this).data('method') != 'paytm'){
			window.location.href = $(this).data('href');
		} else {
			showToast('<?= __('admin.warning') ?>', '<?= __('admin.payment_method_not_available') ?>', 'warning', 3000);
		}
	})

	$('.btn-install-payment-gateway').on('click',function(e){
		e.preventDefault();
		let $this = $(this);
		if($this.data('method') != 'opay' && $this.data('method') != 'paytm'){
			if(confirm('<?= __('admin.are_you_sure') ?>')){
				window.location.href = $this.data('href');
			}
		} else {
			showToast('<?= __('admin.warning') ?>', '<?= __('admin.payment_method_not_available') ?>', 'warning', 3000);
		}
	})

	$('.btn-delete-gateway').on('click',function(e){
		e.preventDefault();
	let $this = $(this);
	if(confirm('<?= __('admin.are_you_sure') ?>')){
		window.location.href = $this.data('href');
	}
})
</script>

        </div>
    </div>
</div>