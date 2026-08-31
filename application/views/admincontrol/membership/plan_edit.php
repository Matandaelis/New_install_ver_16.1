<div class="container-fluid px-4 pb-4">
    <?php get_instance()->load->view('admincontrol/membership/_membership_nav'); ?>
    <div class="row" ng-app="affiliatePro" ng-controller="createPlan">
        <div class="col-12">
            <form id="form_plan">
            <!-- Modern Header Card -->
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-gradient-primary text-white border-0 py-3" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-edit me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0 fw-bold"><?= __('admin.edit_plan') ?></h4>
                                <small class="opacity-75">Modify membership plan details</small>
                            </div>
                        </div>
                        <a id="toggle-uploader" href="<?= base_url('membership/plans') ?>" class="btn btn-light btn-sm shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i><?= __('admin.back') ?>
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                	<input type="text" class="d-none" name="id" ng-model='plan.id'>
                    
                    <!-- Basic Information Section -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-primary fw-semibold border-bottom pb-2">
                                <i class="fas fa-info-circle me-2"></i><?= __('admin.basic_information') ?>
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.name') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg" ng-model='plan.name' placeholder="Enter plan name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.type') ?> <span class="text-danger">*</span></label>
                            <select name="type" class="form-select form-select-lg" ng-model='plan.type'>
                                <option value=""><?= __('admin.choose_plan_type') ?></option>
                                <option value="free"><?= __('admin.free') ?></option>
                                <option value="paid"><?= __('admin.paid') ?></option>
                            </select>
                            <div ng-show='plan.type == "free"' class="alert alert-warning mt-2 py-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small><?= __('admin.default_trial_package_display_warning') ?></small>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Section -->
                    <div ng-show='plan.type == "paid"' class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-success fw-semibold border-bottom pb-2">
                                <i class="fas fa-dollar-sign me-2"></i><?= __('admin.pricing') ?>
                            </h5>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.price') ?> <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light"><?= $CurrencySymbol ?></span>
                                <input type="text" name="price" class="form-control only-number-allow" ng-model='plan.price' placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.special_price') ?></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light"><?= $CurrencySymbol ?></span>
                                <input type="text" name="special" class="form-control only-number-allow" ng-model='plan.special' placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.welcome_bonus') ?></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light"><?= $CurrencySymbol ?></span>
                                <input type="text" name="bonus" class="form-control only-number-allow" ng-model='plan.bonus' placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- Commission Settings -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-warning fw-semibold border-bottom pb-2">
                                <i class="fas fa-percentage me-2"></i><?= __('admin.commission_settings') ?>
                            </h5>
                        </div>
                        <?php if($award_level['status']){ ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium"><?= __('admin.commission_sale_status') ?></label>
                                <select class="form-select form-select-lg" name="commission_sale_status" required>
                                    <option value=""><?= __('admin.choose_commission_sale_status') ?></option>
                                    <option <?= $plan->commission_sale_status == 0 ? 'selected' : '' ?> value="0"><?= __('admin.disable') ?></option>
                                    <option <?= $plan->commission_sale_status == 1 ? 'selected' : '' ?> value="1"><?= __('admin.enable') ?></option>
                                </select>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium"><?= __('admin.commission_sale_status') ?></label>
                                <select class="form-select form-select-lg">
                                    <option value=""><?= __('admin.disable') ?></option>
                                </select>
                            </div>
                        <?php } ?>

                        <?php if($award_level['status']){ ?>
                            <div class="col-md-6 mb-3 <?= $plan->commission_sale_status == 0 ? 'd-none' : '' ?>">
                                <label class="form-label fw-medium"><?= __('admin.plan_level') ?></label>
                                <select class="form-select form-select-lg" name="plan_level">
                                    <option value=""><?= __('admin.choose_plan_level') ?></option>
                                    <option <?= ($plan->level_id == 0) ? 'selected' : ''; ?> value="0"><?= __('admin.default') ?></option>
                                    <?php foreach($levels as $key => $value): ?>
                                        <?php $class = ($value['id'] == $plan->level_id) ? 'selected' : ''; ?> 
                                        <option <?= $class ?> value="<?= $value['id'] ?>"><?= $value['level_number'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Billing Settings -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-info fw-semibold border-bottom pb-2">
                                <i class="fas fa-calendar-alt me-2"></i><?= __('admin.billing_settings') ?>
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.billing_period') ?> <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" ng-model='plan.billing_period' name="billing_period">
                                <option value=""><?= __('admin.choose_billing_period') ?></option>
                                <option value="daily"><?= __('admin.daily') ?></option>
                                <option value="weekly"><?= __('admin.weekly') ?></option>
                                <option value="monthly"><?= __('admin.monthly') ?></option>
                                <option value="yearly"><?= __('admin.yearly') ?></option>
                                <option value="lifetime_free"><?= __('admin.lifetime') ?></option>
                                <option value="custom"><?= __('admin.custom') ?></option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" ng-show='plan.billing_period=="custom"'>
                            <label class="form-label fw-medium"><?= __('admin.days') ?></label>
                            <input type="text" name="custom_period" ng-model='plan.custom_period' class="form-control form-control-lg only-number-allow" placeholder="<?= __('admin.enter_custom_in_days') ?>">
                        </div>
                    </div>

                    <!-- Free Trial Settings -->
                    <div ng-show='plan.type == "paid"' class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-success fw-semibold border-bottom pb-2">
                                <i class="fas fa-gift me-2"></i><?= __('admin.free_trial_settings') ?>
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.add_free_trial') ?></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="0" ng-checked='plan.have_trail == "0"' name="have_trail" ng-model='plan.have_trail' id="trial_no">
                                    <label class="form-check-label" for="trial_no"><?= __('admin.no') ?></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="1" ng-checked='plan.have_trail == "1"' name="have_trail" ng-model='plan.have_trail' id="trial_yes">
                                    <label class="form-check-label" for="trial_yes"><?= __('admin.yes') ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" ng-show='plan.have_trail == "1"'>
                            <label class="form-label fw-medium"><?= __('admin.free_trial') ?></label>
                            <input type="text" placeholder="<?= __('admin.enter_free_trial_in_days') ?>" name="free_trail" class="form-control form-control-lg" ng-model='plan.free_trail'>
                        </div>
                    </div>
                    <!-- User Settings -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-primary fw-semibold border-bottom pb-2">
                                <i class="fas fa-users me-2"></i><?= __('admin.user_settings') ?>
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.user_type') ?> <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" name="user_type" required>
                                <option value=""><?= __('admin.choose_user_type') ?></option>
                                <option <?= $plan->user_type == "1" ? 'selected' : '' ?> value="1"><?= __('admin.affiliate') ?></option>
                                <option <?= $plan->user_type == "2" ? 'selected' : '' ?> value="2"><?= __('admin.vendor') ?></option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 <?= ($plan->user_type == "2") ? '' : 'd-none' ?>">
                            <label class="form-label fw-medium"><?= __('admin.campaign') ?></label>
                            <input type="number" name="campaign" min="0" class="form-control form-control-lg" value="<?= $plan->campaign ?>" placeholder="<?= __('admin.leave_empty_unlimited') ?>">
                        </div>
                        <div class="col-md-3 mb-3 <?= ($plan->user_type == "2") ? '' : 'd-none' ?>">
                            <label class="form-label fw-medium"><?= __('admin.product') ?></label>
                            <input type="number" name="product" min="0" class="form-control form-control-lg" value="<?= $plan->product ?>" placeholder="<?= __('admin.leave_empty_unlimited') ?>">
                        </div>
                    </div>
                    <!-- Visual Appearance -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-warning fw-semibold border-bottom pb-2">
                                <i class="fas fa-palette me-2"></i><?= __('admin.visual_appearance') ?>
                            </h5>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.label_text') ?></label>
                            <input type="text" name="label_text" class="form-control form-control-lg" ng-model='plan.label_text' placeholder="Enter label text">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.label_background') ?></label>
                            <input type="text" name="label_background" class="form-control form-control-lg" data-jscolor="{position:'right'}" value='0000ff'>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.label_color') ?></label>
                            <input type="text" name="label_color" class="form-control form-control-lg" data-jscolor="{position:'right'}" ng-model='plan.label_color'>
                        </div>
                    </div>
                    <!-- Description Section -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-info fw-semibold border-bottom pb-2">
                                <i class="fas fa-file-alt me-2"></i><?= __('admin.plan_description') ?>
                            </h5>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-medium mb-0"><?= __('admin.description') ?></label>
                                <?= ai_helper_button('plan_description', 'description', ['size' => 'sm', 'text' => 'AI Generate']) ?>
                            </div>
                            <textarea placeholder="<?= __('admin.enter_description') ?>" name="description" id="description" class="form-control summernote-img" ng-model='plan.description' rows="4"></textarea>
                            <small class="form-text text-muted mt-1">
                                <i class="fas fa-lightbulb me-1"></i>Provide a detailed description of this membership plan and its benefits
                            </small>
                        </div>
                    </div>
                    <!-- Display Settings Section -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h5 class="text-primary fw-semibold border-bottom pb-2">
                                <i class="fas fa-cog me-2"></i><?= __('admin.display_settings') ?>
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.sort_order') ?></label>
                            <input type="text" name="sort_order" class="form-control form-control-lg" ng-model='plan.sort_order' placeholder="0">
                            <small class="form-text text-muted">Lower numbers appear first in the list</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.display') ?> <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" ng-model='plan.status' name="status">
                                <option value=""><?= __('admin.is_display') ?></option>
                                <option <?= $plan->status == "1" ? 'selected' : '' ?> value="1"><?= __('admin.yes') ?></option>
                                <option <?= $plan->status == "0" ? 'selected' : '' ?> value="0"><?= __('admin.no') ?></option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.plan_icon') ?></label>
                            <input type="file" id="plan_icon" name="plan_icon" class="form-control form-control-lg" accept="image/*">
                            <small class="form-text text-muted mt-1">
                                <i class="fas fa-info-circle me-1"></i>Upload an icon for this membership plan (recommended: 160x160px)
                            </small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium"><?= __('admin.current_icon') ?></label>
                            <div>
                                <img src="<?= base_url('assets/login/multiple_pages') ?>/img/<?= $plan->plan_icon ? $plan->plan_icon : 'saturn.png' ?>" alt="plan icon" class="border rounded bg-light p-2" style="width: 80px; height: 80px; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border-top pt-4 mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Fields marked with <span class="text-danger">*</span> are required
                                </small>
                                <div class="d-flex gap-2">
                                    <button ng-click='submitForm($event,0)' class="btn btn-primary btn-lg px-4" type="submit">
                                        <i class="fas fa-save me-2"></i><?= __('admin.save') ?>
                                    </button>
                                    <button ng-click='submitForm($event,1)' class="btn btn-success btn-lg px-4" type="submit">
                                        <i class="fas fa-check me-2"></i><?= __('admin.save_and_close') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </form>
        </div>
    </div> 
</div>
<script type="text/javascript" src="<?= base_url('assets/template/js/angular.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/template/js/angular.tool.js?v='.time()) ?>"></script>

<script type="text/javascript">
	app.controller('createPlan', function($scope, $http) {
	    $scope.plan = <?= json_encode($plan) ?>;

	    $scope.submitForm = function($event, close){

            let formData = new FormData()
            let img = $('#plan_icon')[0].files[0]
            formData.append('plan_icon', img)
            
            $.ajax({
                url: "<?php echo base_url('membership/upload_plan_icon');?>",
                method: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var data = $("#form_plan").serializeArray();
                    data.push({'name':'plan_icon','value' : response})
                      
                    ngCall($http, 'membership/submit_plan_form' + '?close='+close, {postData: data}, function(json){  
                        handle_res(json)
                    },function(){
                        $($event.currentTarget).addClass("btn-loading")
                    },function(){
                        $($event.currentTarget).removeClass("btn-loading")
                    })
                }
            })
        }
	});

    $("select[name='commission_sale_status']").on('change',function(){
        let value = $(this).val();
        if(value == 1){
            $("select[name='plan_level']").parents('.form-group').removeClass('d-none');
        } else {
            $("select[name='plan_level']").val(0);
            $("select[name='plan_level']").parents('.form-group').addClass('d-none');
        }
    })

    $("select[name='user_type']").on('change',function(){
        let value = $(this).val();
        if(value == 2){
            $("input[name='campaign'],input[name='product']").parents('.form-group').removeClass('d-none');
        } else {
            $("input[name='campaign'],input[name='product']").val('');
            $("input[name='campaign'],input[name='product']").parents('.form-group').addClass('d-none');
        }
    })
</script>