<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<div class="card shadow-sm mb-4">
			<div class="card-header bg-light">
				<?php include 'setting_menu.php'; ?>
			</div>
			<div class="card-body">
				<div class="alert alert-info d-flex align-items-start mb-4">
					<i class="fas fa-share-alt fa-2x me-3"></i>
					<div>
						<h6 class="alert-heading mb-1"><?= __('user.share_sales_info') ?></h6>
						<p class="mb-0 small"><?= __('user.share_sales_desc') ?></p>
					</div>
				</div>

				<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="setting-form">
					<div class="row">
						<div class="col-lg-8 mx-auto">
							<div class="mb-4">
								<label class="form-label fw-semibold">
									<i class="fas fa-users me-2 text-primary"></i><?= __('user.other_affilite_sell_my_items') ?>
								</label>
								<select class="form-select form-select-lg" name="vendor_shares_sales_status">
									<option value="0"><?= __('user.not_sell_anyone') ?></option>
									<option value="1" <?= (int)$setting['vendor_shares_sales_status'] == 1 ? 'selected' : '' ?>><?= __('user.sell_all_affiliates') ?></option>
									<option value="2" <?= (int)$setting['vendor_shares_sales_status'] == 2 ? 'selected' : '' ?>><?= __('user.sell_my_affiliates') ?></option>
								</select>
								<small class="text-muted d-block mt-2">
									<i class="fas fa-question-circle me-1"></i><?= __('user.share_sales_help') ?>
								</small>
							</div>
							<div class="text-end border-top pt-3">
								<button type="submit" class="btn btn-primary btn-lg btn-submit px-5">
									<i class="fas fa-save me-2"></i><?= __('user.save_settings') ?>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(".btn-submit").on('click',function(evt){
	    evt.preventDefault();
	    var formData = new FormData($("#setting-form")[0]);

	    $(".btn-submit").btn("loading");
	    formData = formDataFilter(formData);
	    $this = $("#setting-form");
	    
	    $.ajax({
	        type:'POST',
	        dataType:'json',
	        cache:false,
	        contentType: false,
	        processData: false,
	        data:formData,
	        success:function(result){
	            $(".btn-submit").btn("reset");
	            $(".alert-dismissable").remove();

	            $this.find(".has-error").removeClass("has-error");
	            $this.find("span.text-danger").remove();
	            
	            if(result['location']){
	                window.location = result['location'];
	            }

	            if(result['success']){
	            	showPrintMessage(result['success'],'success');
	                var body = $("html, body");
					body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
	            }

	            if(result['errors']){
	                $.each(result['errors'], function(i,j){
	                    $ele = $this.find('[name="'+ i +'"]');
	                    if($ele){
	                        $ele.parents(".form-group").addClass("has-error");
	                        $ele.after("<span class='d-block text-danger'>"+ j +"</span>");
	                    }
	                });
	            }
	        },
	    })
	    return false;
	});

</script>

</div>