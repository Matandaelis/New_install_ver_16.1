<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1 text-dark">
                                <?= $user['id'] > 0 ? __('admin.edit_user') : __('admin.add_user') ?>
                            </h4>
                            <small class="text-muted">
                                <?= $user['id'] > 0 ? __('admin.modify_user_details') : __('admin.create_new_affiliate_account') ?>
                            </small>
                        </div>
                        <?php if($user['id'] > 0){ ?>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">
                                    <i class="fa fa-user me-1"></i>ID: <?= $user['id'] ?>
                                </span>
                                <span class="badge bg-info">
                                    <i class="fa fa-envelope me-1"></i><?= htmlspecialchars($user['email']) ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Navigation Tabs -->
                        <ul class="nav nav-tabs nav-fill mb-4" id="TabsNav" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="user-edit-tab" data-bs-toggle="tab" data-bs-target="#user-edit" type="button" role="tab" aria-controls="user-edit" aria-selected="true">
                                    <i class="fa fa-user me-2"></i><?= __('admin.user_details') ?>
                                </button>
                            </li>
                            <?php if($user['id'] > 0){ ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="add-transaction-tab" data-bs-toggle="tab" data-bs-target="#add-transaction" type="button" role="tab" aria-controls="add-transaction" aria-selected="false">
                                        <i class="fa fa-plus-circle me-2"></i><?= __('admin.add_transaction') ?>
                                    </button>
                                </li>
                            <?php } ?>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="TabsNavContent">
                            <!-- User Details Tab -->
                            <div class="tab-pane fade show active" id="user-edit" role="tabpanel" aria-labelledby="user-edit-tab">
                                <div class="bg-light border rounded p-4">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                <i class="fa fa-user-circle me-2"></i>
                                                <?= $user['id'] > 0 ? __('admin.edit_user_information') : __('admin.user_registration_form') ?>
                                            </h5>
                                            <hr class="my-3">
                                        </div>
                                    </div>
                                    
                                    <?= $html_form ?>
                                    
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="<?= base_url('admincontrol/userslist') ?>" class="btn btn-outline-secondary">
                                                    <i class="fa fa-arrow-left me-2"></i><?= __('admin.back_to_list') ?>
                                                </a>
                                                <button class="btn btn-primary btn-lg" id="update-user">
                                                    <i class="fa fa-save me-2"></i>
                                                    <?= $user['id'] > 0 ? __('admin.update_user') : __('admin.create_user') ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if($user['id'] > 0){ ?>
                                <!-- Add Transaction Tab -->
                                <div class="tab-pane fade" id="add-transaction" role="tabpanel" aria-labelledby="add-transaction-tab">
                                    <div class="bg-light border rounded p-4">
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="text-primary mb-0">
                                                        <i class="fa fa-plus-circle me-2"></i><?= __('admin.add_new_transaction') ?>
                                                    </h5>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-success text-white">
                                                            <i class="fa fa-money me-1"></i>
                                                            <?= __('admin.total_commission') ?>: <?= c_format($totals['unpaid_commition']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <hr class="my-3">
                                            </div>
                                        </div>

                                        <input type="hidden" name="user_id" class="input-transaction" value="<?= isset($user) ? $user['id'] : '' ?>">

                                        <div class="row g-3">
                                            <!-- Amount Input -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-semibold">
                                                        <i class="fa fa-dollar me-1 text-success"></i><?= __('admin.amount') ?>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text bg-light fw-bold text-success">
                                                            <?= isset($_SESSION['userCurrency']) ? $_SESSION['userCurrency'] : 'USD' ?>
                                                        </span>
                                                        <input class="form-control form-control-lg input-transaction" 
                                                               type="number" 
                                                               name="amount" 
                                                               value="" 
                                                               min="0.01" 
                                                               step="0.01" 
                                                               placeholder="<?= __('admin.enter_amount') ?>"
                                                               oninput="validity.valid||(value='');">
                                                    </div>
                                                    <div class="form-text text-muted">
                                                        <i class="fa fa-info-circle me-1"></i><?= __('admin.minimum_amount_001') ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Comment Input -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-semibold">
                                                        <i class="fa fa-comment me-1 text-info"></i><?= __('admin.comment') ?>
                                                    </label>
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fa fa-comment text-info"></i>
                                                        </span>
                                                        <input class="form-control form-control-lg input-transaction" 
                                                               type="text" 
                                                               name="comment" 
                                                               value=""
                                                               placeholder="<?= __('admin.transaction_description') ?>">
                                                    </div>
                                                    <div class="form-text text-muted">
                                                        <i class="fa fa-info-circle me-1"></i><?= __('admin.optional_transaction_note') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-success btn-lg add-transaction">
                                                        <i class="fa fa-plus me-2"></i><?= __('admin.add_transaction') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var state_id = '<?php echo $user->state ?>';

	$("#Country").on('change',function(){
    var country = $(this).val();
    $.ajax({
        url: '<?php echo base_url('get_state') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            country_id : country
        },
        success: function (json) {
            if(json){
                var html = '';
                $.each(json, function(k,v){
                    if(v.id == state_id){
                        html += '<option value="'+v.id+'" selected="selected">'+v.name+'</option>';
                    }else{
                        html += '<option value="'+v.id+'">'+v.name+'</option>';
                    }
                });
                $('#states').html(html);
            }
        }
    });
	});
	$("#Country").trigger('change');
	$( document ).ready(function() {

	$("#update-user").on('click',function(){
		
		$this = $(".reg_form");
		var is_valid = 0;
        var need_valid = 0;

		$(".tel_input").each(function() {

			let this_is_valid = true;

		    $(this).parents(".form-group").removeClass("has-error");
		    
		    $(this).parents(".form-group").find(".text-danger").remove();

		    if(window["tel_input"+$(this).attr('id')]){
		        var errorMap = ['<?= __('user.invalid_number') ?>','<?= __('user.invalid_country_code') ?>','<?= __('user.too_short') ?>','<?= __('user.too_long') ?>','<?= __('user.invalid_number') ?>'];
		        var errorInnerHTML = '';
		        
		        if ($(this).val().trim()) {
		        	need_valid++;
		            if (window["tel_input"+$(this).attr('id')].isValidNumber()) {

						window["tel_input"+$(this).attr('id')].setNumber($(this).val().trim());

		                is_valid++;
		                this_is_valid = true;
		            } else {
		                var errorCode = window["tel_input"+$(this).attr('id')].getValidationError();
		                errorInnerHTML = errorMap[errorCode];
		                this_is_valid = false;
		            }
		        } else {
		        	if($(this).attr('required') !== undefined) {
		        		need_valid++;
		                this_is_valid = false;
			        	errorInnerHTML = 'The Mobile Number field is required.'; 
			        }
		        }

		        if(!this_is_valid){
		            $(this).parents(".form-group").addClass("has-error");
		            $(this).parents(".form-group").find('> div').after("<span class='text-danger'>"+ errorInnerHTML +"</span>");
		        }
		    }
		});

	    if(is_valid == need_valid){
	        var formData = new FormData($this[0]);
	            
            $(".tel_input").each(function() {
		        if ($(this).val().trim() && window["tel_input"+$(this).attr('id')].isValidNumber()) {
		        	country_id = window["tel_input"+$(this).attr('id')].getSelectedCountryData().dialCode;
	                formData.append($(this).attr('name')+'_afftel_input_pre', country_id);
		        }
		    });

			$.ajax({
				url:'',
				type:'post',
				dataType:'json',
				cache:false,
				contentType: false,
				processData: false,
				data:formData,
				beforeSend:function(){ 
					$("#update-user").prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i><?= __('admin.saving') ?>');
				},
				complete:function(){ 
					$("#update-user").prop('disabled', false).html('<i class="fa fa-save me-2"></i><?= $user['id'] > 0 ? __('admin.update_user') : __('admin.create_user') ?>');
				},
				success:function(json){
					if(json['location']){
						if (typeof window.showToast === 'function') {
							window.showToast('<?= __('admin.success') ?>', '<?= __('admin.user_saved_successfully') ?>', 'success', 3000);
						}
						setTimeout(function() {
							window.location = json['location'];
						}, 1000);
						return;
					}

					$this.find(".has-error").removeClass("has-error");
					$this.find("span.text-danger").remove();
					if(json['errors']){
						if (typeof window.showToast === 'function') {
							window.showToast('<?= __('admin.validation_error') ?>', '<?= __('admin.please_correct_errors') ?>', 'error', 5000);
						}
					    $.each(json['errors'], function(i,j){
					        $ele = $this.find('[name="'+ i +'"]');
					        if($ele){
					            $ele.parents(".form-group").addClass("has-error");
					            $ele.after("<span class='text-danger'>"+ j +"</span>");
					        }
					    })
					}	
				}
			})
	    }
	})
	});
	$(".add-transaction").on('click',function(){
		$this = $("#add-transaction");
		
		$.ajax({
			url:'<?= base_url("admincontrol/add_transaction") ?>',
			type:'post',
			dataType:'json',
			data:$(".input-transaction"),
			beforeSend:function(){ 
				$(".add-transaction").prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i><?= __('admin.processing') ?>');
			},
			complete:function(){ 
				$(".add-transaction").prop('disabled', false).html('<i class="fa fa-plus me-2"></i><?= __('admin.add_transaction') ?>');
			},
			success:function(json){
				if(json['location']){
					if (typeof window.showToast === 'function') {
						window.showToast('<?= __('admin.success') ?>', '<?= __('admin.transaction_added_successfully') ?>', 'success', 3000);
					}
					setTimeout(function() {
						window.location = json['location'];
					}, 1000);
					return;
				}

				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();

				if(json['errors']){
					if (typeof window.showToast === 'function') {
						window.showToast('<?= __('admin.validation_error') ?>', '<?= __('admin.please_check_transaction_details') ?>', 'error', 5000);
					}
				    $.each(json['errors'], function(i,j){
				        $ele = $this.find('#'+ i);
				        if($ele.hasClass('form-group')){
				            $ele.addClass("has-error");
				            $ele.append("<br><span class='text-danger'>"+ j +"</span>");
				        } else {
				        	$ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				} else {
					// Clear form on success
					$('.input-transaction[name="amount"]').val('');
					$('.input-transaction[name="comment"]').val('');
					if (typeof window.showToast === 'function') {
						window.showToast('<?= __('admin.success') ?>', '<?= __('admin.transaction_added_successfully') ?>', 'success', 3000);
					}
				}	
			}
		})
	})
</script>