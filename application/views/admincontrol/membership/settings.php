<div class="container-fluid px-4 pb-4">
  <div class="row">
    <div class="col-12">

      <?php get_instance()->load->view('admincontrol/membership/_membership_nav'); ?>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><?= __('admin.membership_settings') ?></h4>
      </div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 py-3">
        <h5 class="card-title mb-0">
            <i class="fas fa-layer-group text-primary me-2"></i>
            <?= __('admin.membership_settings') ?>
        </h5>
    </div>
    <div class="card-body">
        <form method="post" action="" enctype="multipart/form-data" id="setting-form">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills flex-column flex-sm-row mb-4" id="TabsNav">
                        <li class="nav-item flex-sm-fill text-sm-center">
                            <a class="nav-link active bg-primary text-white" data-bs-toggle="pill" href="#tab-setting">
                                <i class="fas fa-cog me-2"></i><?= __('admin.settings') ?>
                            </a>
                        </li>
                        <li class="nav-item flex-sm-fill text-sm-center">
                            <a class="nav-link bg-secondary text-white" data-bs-toggle="pill" href="#tab-cron_jobs">
                                <i class="fas fa-clock me-2"></i><?= __('admin.cron_jobs') ?>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-setting">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= __('admin.status') ?></label>
                                        <select class="form-select" name="membership[status]">
                                            <option value="0" <?= ($membership['status'] == 0) ? 'selected' : '' ?>><?= __('admin.disable') ?></option>
                                            <option value="1" <?= ($membership['status'] == 1) ? 'selected' : '' ?>><?= __('admin.enable_for_all_users') ?></option>
                                            <option value="2" <?= ($membership['status'] == 2) ? 'selected' : '' ?>><?= __('admin.enable_for_all_vendors') ?></option>
                                            <option value="3" <?= ($membership['status'] == 3) ? 'selected' : '' ?>><?= __('admin.enable_for_all_affiliates') ?></option>
                                        </select>
                                        <small class="form-text text-muted"><?= __('admin.membership_status_help') ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= __('admin.show_epire_notification_interval_in_days') ?></label>
                                        <input type="number" value="<?= $membership['notificationbefore'] ?>" class="form-control" name="membership[notificationbefore]" min="1" max="365">
                                        <small class="form-text text-muted"><?= __('admin.notification_interval_help') ?></small>
                                    </div>
                                </div>
                            </div>

                            <?php
                                $default_affiliate_plan_id = $membership['default_affiliate_plan_id'] ?? $membership['default_plan_id'];
                                $default_vendor_plan_id = $membership['default_vendor_plan_id'] ?? $membership['default_plan_id'];
                            ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= __('admin.default_plan_for_new_affiliates') ?></label>
                                        <select class="form-select" name="membership[default_affiliate_plan_id]">
                                            <option value=""><?= __('admin.none') ?></option>
                                            <?php foreach ($plans as $key => $plan) {
                                                if($plan->user_type == 1) { ?>
                                                <option value="<?= $plan->id ?>" <?= $default_affiliate_plan_id == $plan->id ? 'selected' : '' ?>><?= $plan->name ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <small class="form-text text-muted"><?= __('admin.default_affiliate_plan_help') ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= __('admin.default_plan_for_new_vendors') ?></label>
                                        <select class="form-select" name="membership[default_vendor_plan_id]">
                                            <option value=""><?= __('admin.none') ?></option>
                                            <?php foreach ($plans as $key => $plan) {
                                                if($plan->user_type == 2) { ?>
                                                <option value="<?= $plan->id ?>" <?= $default_vendor_plan_id == $plan->id ? 'selected' : '' ?>><?= $plan->name ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <small class="form-text text-muted"><?= __('admin.default_vendor_plan_help') ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-cron_jobs">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <i class="fas fa-question-circle text-info me-2"></i>
                                                <?= __('admin.what_is_cron_job') ?>
                                            </h5>
                                            <p class="card-text"><?= __('admin.what_is_cron_job_answer') ?></p>

                                            <h6 class="mt-4">
                                                <i class="fas fa-list-ol text-primary me-2"></i>
                                                <?= __('admin.to_add_cron_job_steps') ?>:
                                            </h6>

                                            <ol class="mt-3">
                                                <li><?= __('admin.to_add_cron_job_step1') ?></li>
                                                <li><?= __('admin.to_add_cron_job_step2') ?></li>
                                                <li><?= __('admin.to_add_cron_job_step3') ?></li>
                                                <li><?= __('admin.to_add_cron_job_step4') ?> <span class="badge bg-warning text-dark"><?= __('admin.once_per_minute') ?>(* * * * *)</span></li>
                                                <li><?= __('admin.to_add_cron_job_step5') ?></li>
                                                <li><?= __('admin.to_add_cron_job_step6') ?></li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <i class="fas fa-terminal text-success me-2"></i>
                                                <?= __('admin.cron_command') ?>
                                            </h5>
                                            <div class="bg-dark text-light p-3 rounded">
                                                <code class="text-success">curl <?= base_url('/cronJob/expire_package_notification') ?></code>
                                            </div>
                                            <p class="mt-3 text-muted">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <?= __('admin.copy_this_command_to_cron') ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <img src="<?= base_url('assets/images/cronjob2.jpg') ?>" class="img-fluid rounded shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="fas fa-save me-2"></i><?= __('admin.save') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
    </div> <!-- End col-12 -->
  </div> <!-- End row -->
</div> <!-- End container-fluid -->


<script type="text/javascript">
	$(".btn-submit").on('click',function(evt){
	    evt.preventDefault();
	    
    	var formData = new FormData($("#setting-form")[0]);  

	    $(".btn-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
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
	            $(".btn-submit").prop('disabled', false).html($(".btn-submit").data('original-text') || 'Submit');
	            $(".alert-dismissable").remove();

	            $this.find(".has-error").removeClass("has-error");
	            $this.find(".is-invalid").removeClass("is-invalid");
	            $this.find("span.text-danger").remove();
	            
	            if(result['location']){
	                window.location = result['location'];
	            }

	            if(result['success']){
	                showPrintMessage(result['success'],'success');
	                var body = $("html, body");
					body.stop().animate({scrollTop:0}, 500, 'swing', function() { });

					$('.formsetting_error').text("");
					$('.productsetting_error').text("");
	            }

	            if(result['errors']){
	                $.each(result['errors'], function(i,j){
	                    $ele = $this.find('[name="'+ i +'"]');
	                    if(!$ele.length){ 
	                    	$ele = $this.find('.'+ i);
	                    }
	                    if($ele){
	                        $ele.addClass("is-invalid");
	                        $ele.parents(".form-group").addClass("has-error");
	                        $ele.after("<span class='d-block text-danger'>"+ j +"</span>");
	                    }
	                });

					errors = result['errors'];
					$('.formsetting_error').text(errors['formsetting_recursion_custom_time']);
					$('.productsetting_error').text(errors['productsetting_recursion_custom_time']);
	            }
	        },
	    });
	
	    return false;
	});
</script>
