<div class="form-group row">
    <label class="col-sm-2 col-form-label"><?= __('admin.test_gateway_name_label') ?></label>
    <div class="col-sm-10">
        <input name="test_gateway_name" class="form-control" 
               value="<?= isset($gateway_data_for_form['name']) ? $gateway_data_for_form['name'] : '' ?>" 
               placeholder="<?= __('admin.test_gateway_name_placeholder') ?>" required>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label"><?= __('admin.test_gateway_email_label') ?></label>
    <div class="col-sm-10">
        <input name="test_gateway_email" class="form-control" type="email"
               value="<?= isset($gateway_data_for_form['email']) ? $gateway_data_for_form['email'] : '' ?>" 
               placeholder="<?= __('admin.test_gateway_email_placeholder') ?>" required>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label"><?= __('admin.test_gateway_phone_label') ?></label>
    <div class="col-sm-10">
        <input name="test_gateway_phone" class="form-control" type="text"
               value="<?= isset($gateway_data_for_form['phone']) ? $gateway_data_for_form['phone'] : '' ?>" 
               placeholder="<?= __('admin.test_gateway_phone_placeholder') ?>">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label"><?= __('admin.test_gateway_account_label') ?></label>
    <div class="col-sm-10">
        <input name="test_gateway_account" class="form-control" type="text"
               value="<?= isset($gateway_data_for_form['account']) ? $gateway_data_for_form['account'] : '' ?>" 
               placeholder="<?= __('admin.test_gateway_account_placeholder') ?>">
        <small class="form-text text-muted"><?= __('admin.test_gateway_account_help') ?></small>
    </div>
</div>

<script type="text/javascript">
	$("#payment-form-test_gateway").submit(function(){
		$this = $(this);
		var data = new FormData(this);
		$.ajax({
			url:'<?= base_url('payment/call_payment_function/test_gateway/saveUserSubmit') ?>',
			type:'POST',
			dataType:'json',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend:function(){
				$this.find('.btn-submit').btn("loading");
				$this.find('.btn-submit').attr("disabled","disabled");
			},
			complete:function(){
				$this.find('.btn-submit').btn("reset");
				$this.find('.btn-submit').removeAttr("disabled");
			},
			success:function(json){
				$container = $this;
				$container.find(".is-invalid").removeClass("is-invalid");
				$container.find("span.invalid-feedback").remove();
				$this.find('.btn-submit').removeAttr("disabled");

				if (json['success']) {
					$("#withdrawal-payments").modal("hide");

					Swal.fire({
						title: '<?= __('admin.success') ?>',
						text: "<?= __('admin.withdrawal_request_sent_successfully') ?>",
						confirmButtonText: '<?= __('admin.ok') ?>',
						icon: 'success',
					}).then((result) => {
						window.location.reload();
					})
				}
				
				if(json['errors']){
				    $.each(json['errors'], function(i,j){
				        $ele = $container.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.addClass("is-invalid");
				            if($ele.parent(".input-group").length){
				                $ele.parent(".input-group").after("<span class='invalid-feedback'>"+ j +"</span>");
				            } else{
				                $ele.after("<small class='text-danger'>"+ j +"</small>");
				            }
				        }
				    })
				}
			},
		})
		return false;
	})
</script>
