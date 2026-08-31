<?php if(isset($warning)){ ?>
    <div class="modal-header bg-warning bg-opacity-10 border-bottom border-warning">
        <h5 class="modal-title fw-bold text-dark">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            <?= __('user.withdrawal_notification_info') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body p-4"> 
        <div class="alert alert-warning border-0 shadow-sm mb-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fs-4 me-3"></i>
                <div class="fs-6"><?= $warning ?></div>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i><?= __('user.close') ?>
        </button>
    </div>

<?php } 
else if(isset($danger)){ ?>
    <div class="modal-header bg-danger bg-opacity-10 border-bottom border-danger">
        <h5 class="modal-title fw-bold text-dark">
            <i class="fas fa-exclamation-circle text-danger me-2"></i>
            <?= __('user.withdrawal_notification_info') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body p-4">
        <div class="alert alert-danger border-0 shadow-sm mb-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-times-circle fs-4 me-3"></i>
                <div class="fs-6"><?= $danger ?></div>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i><?= __('user.close') ?>
        </button>
    </div>
<?php }
else { ?>

<div class="modal-header bg-primary bg-opacity-10 border-bottom border-primary">
    <h5 class="modal-title fw-bold text-dark">
        <i class="fas fa-hand-holding-usd text-primary me-2"></i>
        <?= __('user.select_payment_method') ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-0">
    <div class="alert alert-info border-0 rounded-0 mb-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fs-4 me-3"></i>
                <div>
                    <div class="fw-bold"><?= __('user.withdrawal_amount') ?></div>
                    <div class="small text-muted">
                        <?php if($ids == 'all'): ?>
                            <?= __('user.withdrawing_all_unpaid_transactions') ?>
                        <?php else: ?>
                            <?php 
                            $ids_array = explode(',', $ids);
                            $count = count($ids_array);
                            ?>
                            <?= $count ?> <?= __('user.selected_transactions') ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <div class="fs-3 fw-bold text-primary"><?= c_format($transaction_total) ?></div>
            </div>
        </div>
    </div>
    
    <div class="row g-0">
        <div class="col-md-4 border-end bg-light">
            <div class="list-group list-group-flush">
                <?php $index = 0; foreach ($payment_methods as $key => $value) { if ($value['status']==1 && $value['is_install']==1) { ?>
                    <button type="button" class="list-group-item list-group-item-action border-0 py-3 px-4 <?= $index == 0 ? 'active' : '' ?>" data-tab='collapse-<?= $value['code'] ?>'>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chevron-right me-2"></i>
                            <span class="fw-semibold"><?= $value['title'] ?></span>
                        </div>
                    </button>
                <?php $index++; } } ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="p-4">
                <?php $index = 0; foreach ($payment_methods as $key => $value) {  if ($value['status']) {  
                        ?>

                    <div id="collapse-<?= $value['code'] ?>" class="<?= $index == 0 ? '' : 'd-none'  ?>"> 

                        <div class="mb-4">
                            <h5 class="fw-bold text-primary mb-1">
                                <i class="fas fa-wallet me-2"></i><?= __('user.get_paid_with') ?> <?= $value['title'] ?>
                            </h5>
                            <p class="text-muted small mb-0"><?= __('user.complete_form_to_process_withdrawal') ?></p>
                        </div>

                        <form id="payment-form-<?= $value['code'] ?>" enctype="multipart/form-data">
                            <input type="hidden" name="ids" value="<?= $ids ?>">
                            <input type="hidden" name="code" value="<?= $value['code'] ?>">

                            <?= $value['user_setting'] ?>

                            <div class="pb-3">
                        
                        <?php
                        if($value['code'] == 'bank_transfer') {
                            if(isset($paymentlist) && isset($paymentlist[0]) && $PrimaryPaymentMethodStatus != "") {    
                            ?>
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-university text-primary me-2"></i><?= __('user.bank_details') ?>
                                        </h6>
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <small class="text-muted d-block"><?=__('user.bank_name')?>:</small>
                                                <div class="fw-semibold"><?= $paymentlist[0]['payment_bank_name']?></div>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block"><?=__('user.account_number')?>:</small>
                                                <div class="fw-semibold"><?= $paymentlist[0]['payment_account_number']?></div>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block"><?=__('user.account_name')?>:</small>
                                                <div class="fw-semibold"><?= $paymentlist[0]['payment_account_name']?></div>
                                            </div>
                                            <?php if(!empty($paymentlist[0]['payment_country'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.country') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_country'] ?></div>
                                                </div>
                                            <?php
                                            $country = $paymentlist[0]['payment_country'];

                                            if ($country == 'US' && !empty($paymentlist[0]['payment_routing_number'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.routing_number') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_routing_number'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'IN' && !empty($paymentlist[0]['payment_ifsc_code'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.ifsc_code') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_ifsc_code'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'GB' && !empty($paymentlist[0]['payment_sort_code'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.sort_code') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_sort_code'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'AU' && !empty($paymentlist[0]['payment_bsb_number'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.bsb_number') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_bsb_number'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'CA' && !empty($paymentlist[0]['payment_transit_institution_number'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.transit_institution_number') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_transit_institution_number'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'DE' && !empty($paymentlist[0]['payment_iban_bic'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.iban_bic') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_iban_bic'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'CN' && !empty($paymentlist[0]['payment_cnaps_code'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.cnaps_code') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_cnaps_code'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'SG' && !empty($paymentlist[0]['payment_swift_code'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.swift_code') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_swift_code'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'HK' && !empty($paymentlist[0]['payment_clearing_code'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.clearing_code') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_clearing_code'] ?></div>
                                                </div>
                                            <?php } elseif ($country == 'NZ' && !empty($paymentlist[0]['payment_bank_branch_number'])) { ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block"><?= __('user.bank_branch_number') ?>:</small>
                                                    <div class="fw-semibold"><?= $paymentlist[0]['payment_bank_branch_number'] ?></div>
                                                </div>
                                            <?php } ?>

                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                 
                                <input class="form-control" type="hidden" name="bank_name" value="<?= $paymentlist[0]['payment_bank_name']?>">   
                                <input class="form-control" type="hidden" name="account_number" value="<?= $paymentlist[0]['payment_account_number']?>"> 
                                <input class="form-control" type="hidden" name="account_name" value="<?= $paymentlist[0]['payment_account_name']?>">
                                <?php if(!empty($paymentlist[0]['payment_country'])) { ?>
                                    <input class="form-control" type="hidden" name="payment_country" value="<?= $paymentlist[0]['payment_country']?>">
                                    <?php if($paymentlist[0]['payment_country'] == 'US') { ?>
                                        <input class="form-control" type="hidden" name="payment_routing_number" value="<?= $paymentlist[0]['payment_routing_number']?>">
                                    <?php } ?>
                                <?php } ?>
                                    
                                <?php 
                                if($setting_exist_status == 1) {
                                    $get_custom_fiels_data = json_decode($get_custom_fiels['bt_custom_field']);
                                    $get_custom_fiels_validate = json_decode($get_custom_fiels['response_validate']);
                                ?>   
                                    <?php if(isset($get_custom_fiels['withdrawal_proof']) && in_array($get_custom_fiels['withdrawal_proof'], [1,2])) { ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-file-upload me-1"></i><?= __('user.payment_proof') ?>
                                                <?php if($get_custom_fiels['withdrawal_proof'] == 2): ?>
                                                    <span class="text-danger">*</span>
                                                <?php endif; ?>
                                            </label>
                                            <input type="file" class="form-control form-control-lg" id="payment_proof" name="payment_proof" <?= $get_custom_fiels['withdrawal_proof'] == 2 ? "required" : "" ?>/>
                                            <div class="form-text text-info">
                                                <i class="fas fa-info-circle me-1"></i><?= __('user.if_admin_asked_you_send_payment_proof') ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                </div>
                                <?php
                                $get_custom_fiels_validate = isset($get_custom_fiels_validate) && is_array($get_custom_fiels_validate) ? $get_custom_fiels_validate : []; 
                                ?>
                                <input type="hidden" name="get_custom_fiels_validate" value="<?= implode(",",$get_custom_fiels_validate);?>">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg btn-submit">
                                        <i class="fas fa-paper-plane me-2"></i><?= __('user.submit') ?>
                                    </button>
                                </div> 
                            <?php } else { ?>
        
                                <?php if(isset($paymentlist) && !isset($paymentlist[0]) && $PrimaryPaymentMethodStatus == ""){?>
                                <div class="alert alert-warning border-0 shadow-sm" role="alert">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle fs-4 me-3 mt-1"></i>
                                        <div>
                                            <p class="mb-2"><?=__('user.payouts_bank_transfer_payment_details_missing')?></p>
                                            <p class="mb-2"><?=__('user.payouts_primary_payment_not_chosen')?></p>
                                            <a href="<?=base_url('usercontrol/payment_details')?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-cog me-1"></i><?= __('user.click_here_to_enter_payment_details')?>
                                            </a>
                                        </div>
                                    </div>
                                </div> 
                            <?php }elseif(isset($paymentlist) && isset($paymentlist[0]) && $PrimaryPaymentMethodStatus != ""){?>
                                <div class="alert alert-warning border-0 shadow-sm" role="alert">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle fs-4 me-3 mt-1"></i>
                                        <div>
                                            <p class="mb-2"><?=__('user.payouts_bank_transfer_payment_details_missing')?></p>
                                            <a href="<?=base_url('usercontrol/payment_details')?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-cog me-1"></i><?= __('user.click_here_to_enter_payment_details')?>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            <?php }elseif(isset($paymentlist) && !isset($paymentlist[0]) && $PrimaryPaymentMethodStatus != ""){?>
                                <div class="alert alert-warning border-0 shadow-sm" role="alert">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle fs-4 me-3 mt-1"></i>
                                        <div>
                                            <p class="mb-2"><?=__('user.payouts_bank_transfer_payment_details_missing')?></p>
                                            <a href="<?=base_url('usercontrol/payment_details')?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-cog me-1"></i><?= __('user.click_here_to_enter_payment_details')?>
                                            </a>
                                        </div>
                                    </div>
                                </div> 
                            <?php }else{?>
                                <div class="alert alert-warning border-0 shadow-sm" role="alert">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle fs-4 me-3 mt-1"></i>
                                        <div>
                                            <p class="mb-2"><?=__('user.payouts_primary_payment_not_chosen')?></p>
                                            <a href="<?=base_url('usercontrol/payment_details')?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-cog me-1"></i><?= __('user.click_here_to_enter_payment_details')?>
                                            </a>
                                        </div>
                                    </div>
                                </div> 
                            <?php }?>
                                </div>
                                <?php
                            }
                        }
                        else  if($value['code'] == 'paypal')
                        {
                            if(isset($paypalaccounts) && isset($paypalaccounts[0])) 
                            {    
                            ?>
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fab fa-paypal text-primary me-2"></i><?= __('user.paypal_details') ?>
                                        </h6>
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <small class="text-muted d-block"><?=__('user.paypal_email')?>:</small>
                                                <div class="fw-semibold"><?=$paypalaccounts[0]['paypal_email']?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" name="paypal_email" value="<?=$paypalaccounts[0]['paypal_email']?>" >

                               </div>
                            <?php
                                $get_custom_fiels_validate = isset($get_custom_fiels_validate) && is_array($get_custom_fiels_validate) ? $get_custom_fiels_validate : []; 
                             ?>
                            <input type="hidden" name="get_custom_fiels_validate" value="<?= implode(",",$get_custom_fiels_validate);?>">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-submit">
                                    <i class="fas fa-paper-plane me-2"></i><?= __('user.submit') ?>
                                </button>
                            </div>     

                            <?php
                            }
                            else
                            {
                                ?>
                                <div class="alert alert-warning border-0 shadow-sm" role="alert">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle fs-4 me-3 mt-1"></i>
                                        <div>
                                            <p class="mb-2"><?=__('user.payouts_paypal_payment_details_missing')?></p>
                                            <a href="<?=base_url('usercontrol/payment_details')?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-cog me-1"></i><?= __('user.click_here_to_enter_payment_details')?>
                                            </a>
                                        </div>
                                    </div>
                                </div> 
                                </div>
                                <?php

                            }
                           
                        }
                        else 
                        { 
                            ?>

                            </div>
                            <?php
                                $get_custom_fiels_validate = isset($get_custom_fiels_validate) && is_array($get_custom_fiels_validate) ? $get_custom_fiels_validate : []; 
                             ?>
                            <input type="hidden" name="get_custom_fiels_validate" value="<?= implode(",",$get_custom_fiels_validate);?>">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-submit">
                                    <i class="fas fa-paper-plane me-2"></i><?= __('user.submit') ?>
                                </button>
                            </div>        

                            <?php

                        }    
                        
                        ?>  
                    </form>
                </div>
            <?php $index++; } } ?>
            
            <?php if ($index == 0) { ?>
                <div class="alert alert-danger border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle fs-4 me-3"></i>
                        <div><?= __('user.warning_no_payment_options_available_contact_assistance') ?></div>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// Payment method tab switching
$(document).on('click', '#withdrawal-payments .list-group-item[data-tab]', function(){
    $("[id^='collapse-']").addClass("d-none");
    var tab = $(this).attr("data-tab");
    $("#" + tab).removeClass("d-none");
    $("#withdrawal-payments .list-group-item.active").removeClass("active");
    $(this).addClass("active");
});

// Bootstrap 5 compatible button loading polyfill
if (!$.fn.btn) {
    $.fn.btn = function(action) {
        return this.each(function() {
            var $btn = $(this);
            if (action === 'loading') {
                $btn.data('original-html', $btn.html());
                $btn.prop('disabled', true)
                    .addClass('disabled')
                    .html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><?= __('user.processing') ?>...');
            } else if (action === 'reset') {
                var originalHtml = $btn.data('original-html');
                if (originalHtml) {
                    $btn.html(originalHtml);
                }
                $btn.prop('disabled', false).removeClass('disabled');
            }
        });
    };
}

// Add visual overlay when any withdrawal form is submitted
$(document).on('submit', '#withdrawal-payments form[id^="payment-form-"]', function(e) {
    console.log('✅ Form submitted:', $(this).attr('id'));
    
    // Add loading overlay to modal (the payment gateway handlers will handle the button)
    var $modalContent = $('#withdrawal-payments .modal-content');
    
    setTimeout(function() {
        if(!$modalContent.find('.loading-overlay').length) {
            $modalContent.append('<div class="loading-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75" style="z-index: 9999;"><div class="text-center"><div class="spinner-border text-primary mb-2" role="status"></div><div class="fw-bold text-primary"><?= __('user.processing') ?>...</div></div></div>');
        }
    }, 100);
});
</script>

<?php  } ?>