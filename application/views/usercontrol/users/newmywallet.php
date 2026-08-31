<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css" />
<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>

<div class="container-fluid py-2">

<!-- ====== Stat Cards Row ====== -->
<?php $isVendor = ($userdetails['is_vendor'] == 1); $statCol = $isVendor ? 'col-xl-3 col-md-6' : 'col-xl-4 col-md-4'; ?>
<div class="row g-2 mb-3">
    <div class="<?= $statCol ?>">
        <div class="card border-0 rounded-3 h-100 text-white wallet-grad wallet-grad--balance">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <small class="text-uppercase fw-bold opacity-75 wallet-grad-label"><?= __('user.balance') ?></small>
                    <i class="bi bi-wallet2 fs-4 opacity-50"></i>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="opacity-75"><?= __('user.balance') ?>:</small>
                    <strong class="fs-6"><?= c_format($user_totals['user_balance']) ?></strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="opacity-75"><?= __('user.paid_balance') ?>:</small>
                    <strong class="fs-6"><?= c_format($user_totals['wallet_accept_amount']); ?></strong>
                </div>
            </div>
        </div>
    </div>
    <?php if ($isVendor) { ?>
    <div class="<?= $statCol ?>">
        <div class="card border-0 rounded-3 h-100 text-white wallet-grad wallet-grad--sales">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <small class="text-uppercase fw-bold opacity-75 wallet-grad-label"><?= __('user.total_sales') ?></small>
                    <i class="bi bi-cart-check fs-4 opacity-50"></i>
                </div>
                <h4 class="fw-bold mb-0"><?= c_format($user_totals['vendor_sale_localstore_total'] + $user_totals['vendor_order_external_total']) ?></h4>
                <small class="opacity-75"><?= __('user.vendor_store') ?></small>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="<?= $statCol ?>">
        <div class="card border-0 rounded-3 h-100 text-white wallet-grad wallet-grad--actions">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <small class="text-uppercase fw-bold opacity-75 wallet-grad-label"><?= __('user.actions') ?></small>
                    <i class="bi bi-lightning-charge fs-4 opacity-50"></i>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h4 class="fw-bold mb-0"><?= (int)$user_totals['click_action_total'] + (int)$user_totals['vendor_action_external_total'] ?></h4>
                    <span class="opacity-75 small"><?= __('user.actions') ?></span>
                </div>
                <div class="fw-semibold"><?= c_format($user_totals['click_action_commission'] + $user_totals['vendor_action_external_commission']) ?></div>
                <?php if ($isVendor) { ?>
                <div class="d-flex justify-content-between align-items-center small">
                    <span class="opacity-75"><?= __('user.vendor_pay') ?>:</span>
                    <strong><?= c_format($user_totals['vendor_action_external_commission_pay']) ?></strong>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="<?= $statCol ?>">
        <div class="card border-0 rounded-3 h-100 text-dark wallet-grad wallet-grad--clicks">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <small class="text-uppercase fw-bold opacity-75 wallet-grad-label"><?= __('user.clicks') ?></small>
                    <i class="bi bi-cursor fs-4 opacity-50"></i>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h4 class="fw-bold mb-0"><?= (int)($user_totals['total_clicks_count']) ?></h4>
                    <span class="opacity-75 small"><?= __('user.clicks') ?></span>
                </div>
                <div class="fw-semibold"><?= c_format($user_totals['total_clicks_commission']) ?></div>
                <?php if ($isVendor) { ?>
                <div class="d-flex justify-content-between align-items-center small">
                    <span class="opacity-75"><?= __('user.vendor_pay') ?>:</span>
                    <strong><?= c_format($user_totals['vendor_click_localstore_commission_pay'] + $user_totals['vendor_click_external_commission_pay']) ?></strong>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if(isset($walletauto_withdrawal) && $walletauto_withdrawal==1) { ?>
<!-- ====== Auto-Withdrawal Alert ====== -->
<div class="row mb-2">
    <div class="col-12">
        <div class="alert alert-info alert-dismissible fade show py-2 mb-0 rounded-3 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-robot me-2 text-info"></i>
                <strong class="me-2"><?= __('user.auto_withdrawal_enabled') ?>:</strong>
                <span class="mship-card-title"><?= $wallet_auto_withdrawal_message; ?></span>
                <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- ====== Transaction History Card ====== -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm wallet-main-card">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0 fw-bold mship-card-title">
                        <i class="bi bi-wallet2 me-2 text-primary"></i><?= __('user.transaction_history') ?>
                    </h6>
                    <?php if(isset($walletauto_withdrawal) && $walletauto_withdrawal==1) { ?>
                    <span class="badge bg-info rounded-pill">
                        <i class="bi bi-robot me-1"></i><?= __('user.auto_mode') ?>
                    </span>
                    <?php } ?>
                </div>

                <form action="<?= base_url('usercontrol/mywallet') ?>" method="GET">
                    <div class="row g-2 align-items-end">
                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <label class="form-label fw-semibold mship-card-subtitle wallet-filter-label">
                                <i class="bi bi-funnel me-1"></i><?= __('user.filter_by_type') ?>
                            </label>
                            <select name="type" class="form-select form-select-sm">
                                <option value=""><?= __('user.all_types') ?></option>
                                <option value="actions" <?= isset($_GET['type']) && $_GET['type'] == 'actions' ? 'selected' : '' ?>><?= __('user.actions') ?></option>
                                <option value="clicks" <?= isset($_GET['type']) && $_GET['type'] == 'clicks' ? 'selected' : '' ?>><?= __('user.clicks') ?></option>
                                <option value="sale" <?= isset($_GET['type']) && $_GET['type'] == 'sale' ? 'selected' : '' ?>><?= __('user.sale') ?></option>
                                <option value="external_integration" <?= isset($_GET['type']) && $_GET['type'] == 'external_integration' ? 'selected' : '' ?>><?= __('user.external_integration') ?></option>
                            </select>
                        </div>
                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <label class="form-label fw-semibold mship-card-subtitle wallet-filter-label">
                                <i class="bi bi-cash-coin me-1"></i><?= __('user.payment_status') ?>
                            </label>
                            <select name="paid_status" class="form-select form-select-sm">
                                <option value=""><?= __('user.all_statuses') ?></option>
                                <option value="paid" <?= isset($_GET['paid_status']) && $_GET['paid_status'] == 'paid' ? 'selected' : '' ?>><?= __('user.paid') ?></option>
                                <option value="unpaid" <?= isset($_GET['paid_status']) && $_GET['paid_status'] == 'unpaid' ? 'selected' : '' ?>><?= __('user.unpaid') ?></option>
                            </select>
                        </div>
                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <label class="form-label fw-semibold mship-card-subtitle wallet-filter-label">
                                <i class="bi bi-arrow-down-up me-1"></i><?= __('user.withdraw_status') ?>
                            </label>
                            <select name="withdraw_type" class="form-select form-select-sm">
                                <option value=""><?= __('user.all_withdraw_statuses') ?></option>
                                <option value="1" <?= isset($_GET['withdraw_type']) && $_GET['withdraw_type'] == '1' ? 'selected' : '' ?>><?= __('user.canceled') ?></option>
                                <option value="2" <?= isset($_GET['withdraw_type']) && $_GET['withdraw_type'] == '2' ? 'selected' : '' ?>><?= __('user.trashed') ?></option>
                            </select>
                        </div>
                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <label class="form-label fw-semibold mship-card-subtitle wallet-filter-label">
                                <i class="bi bi-calendar3 me-1"></i><?= __('user.date_range') ?>
                            </label>
                            <input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control form-control-sm daterange-picker" placeholder='<?= __('user.select_date_range') ?>'>
                        </div>

                        <div class="col-xl-4 col-md-12">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <button class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-search me-1"></i><?= __('user.filter') ?>
                                </button>
                                <?php if(isset($walletauto_withdrawal) && $walletauto_withdrawal==1) { ?>
                                <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-3" disabled>
                                    <i class="bi bi-robot me-1"></i><?= __('user.auto_withdrawal_active') ?>
                                </button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-success btn-sm rounded-pill px-3 withdrawal-all">
                                    <i class="bi bi-check2-square me-1"></i><?= __('user.withdrawal_selected') ?>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 withdrawal-unpaid" value="<?= $wallet_unpaid_amount ?>">
                                    <i class="bi bi-cash-stack me-1"></i><?= __('user.withdrawal_all') ?>
                                    <span class="badge bg-light text-dark ms-1"><?= c_format($wallet_unpaid_amount) ?></span>
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <?php if ($transaction == null) { ?>
                    <div class="text-center py-4">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="mb-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle wallet-empty-icon">
                                    <i class="bi bi-arrow-left-right fs-1"></i>
                                </span>
                            </div>
                            <h5 class="mship-card-title mb-2"><?= __('user.no_transactions_found') ?></h5>
                            <p class="mship-card-subtitle mb-3 small"><?= __('user.no_transactions_description') ?></p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="<?= base_url('usercontrol/dashboard') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-house me-1"></i><?= __('user.go_to_dashboard') ?>
                                </a>
                                <a href="<?= base_url('integration') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-plus-lg me-1"></i><?= __('user.start_earning') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/template/css/wallet.css?v='. time()) ?>">
                    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/flag/css/main.min.css?v='. time()) ?>">

                    <div class="table-responsive">
                        <table class="table table-hover transaction-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <?php if(isset($walletauto_withdrawal) && $walletauto_withdrawal==1) { ?>
                                    <th class="checkbox-td wallet-th-auto"></th>
                                    <?php } else { ?>
                                    <th class="checkbox-td text-center wallet-th-checkbox">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input selectall" id="selectAll">
                                            <label class="form-check-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <?php }?>
                                    <th class="text-center wallet-th-expand">+</th>
                                    <th><?= __('user.date') ?></th>
                                    <th><?= __('user.user') ?></th>
                                    <th><?= __('user.type') ?></th>
                                    <th class="text-end"><?= __('user.commission') ?></th>
                                    <th><?= __('user.integration_id') ?></th>
                                    <th><?= __('user.payment') ?></th>
                                    <th class="text-center"><?= __('user.status') ?></th>
                                    <?php if($userdetails['is_vendor']): ?>
                                        <th class="text-center"><?= __('user.actions') ?></th>
                                    <?php endif ?>
                                    <th class="text-center wallet-th-more">
                                        <i class="bi bi-three-dots-vertical opacity-50"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $group_changed = 1;
                                $html = '';
                                $lastRow = count($transaction)-1;

                                foreach ($transaction as $key => $value) {

                                    $product_user=0;
                                    if($value['from_user_id'] == ""){

                                    $product = $this->db->query('SELECT * FROM product WHERE product_id='.$value['reference_id'])->row_array();

                                    $product_user=$product['product_created_by'];
                                    }else{
                                        $integration = $this->db->query('SELECT * FROM integration_tools WHERE id='.$value['reference_id'])->row_array();
                                        if($integration['vendor_id'] == 0){
                                            $product_user=1;
                                        }else{
                                            $product_user=$integration['vendor_id'];
                                        }

                                    }

                                    $class = '';
                                    if($current_group_id && $current_group_id == $value['group_id']){
                                        $class = 'child';
                                    } else{
                                        $current_group_id = $value['group_id'];
                                        $group_changed =1;
                                    }


                                    $data = [];
                                    $data['value'] = $value;
                                    $data['product_user'] = $product_user;

                                    $data['class'] = $class;
                                    $data['wallet_status'] = $status;
                                    $data['userdetails'] = $userdetails;


                                    $data['child_id'] = $child_id = (isset($transaction[$key+1]) && $transaction[$key+1]['group_id'] && $transaction[$key+1]['has_recursion_records'] !="" &&  $transaction[$key+1]['group_id'] == $value['group_id']) ? $transaction[$key+1]['id']  : null;
                                    // Check if current row is a parent by checking if next row has same group_id but is not itself
                                    $data['has_child'] = (isset($transaction[$key+1]) && $transaction[$key+1]['group_id'] && $transaction[$key+1]['group_id'] == $value['group_id'] && $transaction[$key+1]['id'] != $value['id']) ? 1  : 0;

                                    $data['walletauto_withdrawal'] = $walletauto_withdrawal;
                                    $html .= $this->Product_model->getHtml('usercontrol/users/parts/new_wallet_tr', $data);

                                    if($group_changed || $lastRow == $key){
                                        echo $html;
                                        $html = '';
                                        $group_changed = 0;
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="100%" class="p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="mship-card-subtitle small">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <?= isset($pagination_summary) ? $pagination_summary : __('user.showing_transactions') ?>
                                            </div>
                                            <nav aria-label="Transaction pagination">
                                                <div class="pagination pagination-sm mb-0">
                                                    <?= $pagination_link; ?>
                                                </div>
                                            </nav>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

</div>
<!-- End container-fluid -->

<!-- Withdrawal Payments Modal -->
<div class="modal fade mship-modal" id="withdrawal-payments">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
        </div>
    </div>
</div>

<!-- Zero Withdrawal Alert Modal -->
<div class="modal fade mship-modal" id="0-withdrawal-payments">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-warning bg-opacity-10 border-bottom border-warning px-4 py-3">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i><?= __('user.withdrwal_alert') ?>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning border-0 shadow-sm mb-0 rounded-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle fs-4 me-3"></i>
                        <div class="fs-6"><?= __('user.withdrwal_greater_than_zero') ?></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i><?= __('user.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Completed Modal -->
<div class="modal fade mship-modal" id="modal-completed">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-success bg-opacity-10 border-bottom border-success px-4 py-3">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="bi bi-check-circle text-success me-2"></i><?= __('user.payment_completed') ?>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 shadow-sm mb-0 rounded-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle fs-4 me-3"></i>
                        <div class="fs-6"><?= __('user.transaction_status_can_change_revert') ?></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-1"></i><?= __('user.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade mship-modal" id="modal-confirm">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden"><div class="modal-body"></div></div></div>
</div>
<div class="modal fade mship-modal" id="modal-confirmstatus" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden"><div class="modal-body"></div></div></div>
</div>
<div class="modal fade mship-modal" id="modal-recursion">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden"><div class="modal-body"></div></div></div>
</div>

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>

<script type="text/javascript">

    $(document).delegate(".show-child-transaction","click",function(){

        $tr = $(this).parents("tr");
        var status = $(this).find("i").hasClass('bi-chevron-down') ? 1 : 0;
        var group_id = $tr.attr("group_id");

        if(status){
            $('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring)').show();
            $(this).find("i").removeClass('bi-chevron-down');
            $(this).find("i").addClass('bi-chevron-up');
            $(this).siblings('.child-connector-line').show();
            $tr.addClass('opened')
            $('.transaction-table [group_id='+ group_id +']').addClass('highlight');
        } else{
            $('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring)').hide();
            $(this).siblings('.child-connector-line').hide();
            $(this).find("i").removeClass('bi-chevron-up');
            $(this).find("i").addClass('bi-chevron-down');
            $tr.removeClass('opened')
            $('.transaction-table [group_id='+ group_id +']').removeClass('highlight');
        }

        $('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring):last').addClass("last-group-row");
    })

    $(".filter-toggle").on("click", function(){
        $(".wallet-filter").slideToggle('fast');
    })

    $(".show-recurring-transition").on("click",function(){
        $this = $(this);
        var id = $this.attr("data-id");
        var group_id=$this.parents("tr").attr("group_id");

        var classname=$this.parents("tr").attr('class');

        $this.find("i").toggleClass("mdi-plus mdi-minus");

        $nextAll = $this.parents("tr").nextAll("tr.recurringof-"+id);

        $this.parents("tr").nextAll("tr.recurringof-"+id+":last").addClass('last-recurring');

        if($nextAll.length){
            if($nextAll.eq(0).css("display") == 'table-row'){
                $this.parents("tr").removeClass('opened-recurring');
                $nextAll.hide();
                $('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring):last').addClass("last-group-row");
            } else {
                $this.parents("tr").addClass('opened-recurring');
                $nextAll.show();

                if(classname.includes('last-group-row')){
                    $('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring):last').removeClass("last-group-row");
                }
            }
            return false;
        }

        $this.parents("tr").nextAll("tr.recurringof-"+id).remove();

        $.ajax({
            url:'<?= base_url('usercontrol/getRecurringTransaction') ?>',
            type:'POST',
            dataType:'json',
            data:{
                id:id,
                newtr:1,
                ischild:$this.parents("tr").hasClass("child-row")
            },
            beforeSend:function(){$this.btn("loading");},
            complete:function(){$this.btn("reset");},
            success:function(json){
                if(json['table']){
                    $this.parents("tr").addClass('opened-recurring');
                    $this.parents("tr").after(json['table']);
                    $this.parents("tr").nextAll("tr.recurringof-"+id+":last").addClass('last-recurring');
                    $this.parents("tr").nextAll("tr.recurringof-"+id+"").removeAttr('style');

                    if(classname.includes('last-group-row')){
                    $('.transaction-table .child-row[group_id='+ group_id +']:not(.recurring):last').removeClass("last-group-row");
                }
                    $(".wallet-popover").each(function(){
                        new bootstrap.Popover(this, {
                            placement: 'right',
                            html: true,
                            trigger: 'click'
                        });
                    });
                }
            },
        })
    })

    $('.selectall').on('change',function(){
        $('.wallet-checkbox').prop("checked",$(this).prop("checked"));
    });

    $('.withdrawal-unpaid').on('click',function(){
        var amount = $(this).val();
        withdrawal_payments('all',$(this),amount);
    });

    $('.withdrawal-all').on('click',function(){
        var ids = $(".wallet-checkbox:checked").map(function(){ return $(this).val() }).toArray().join(",");
        withdrawal_payments(ids,$(this),null);
    });

    function withdrawal_payments(ids,_this,amount) {
        if (amount == '0') {
            $("#0-withdrawal-payments").modal("show");
        }else{
            $.ajax({
                url:'<?= base_url("usercontrol/get_withdrawal_modal") ?>',
                type:'POST',
                dataType:'json',
                data:{ids:ids},
                beforeSend:function(){_this.btn("loading");},
                complete:function(){_this.btn("reset");},
                success:function(json){
                    $("#withdrawal-payments .modal-content").html(json['html']);
                    $("#withdrawal-payments").modal("show");
                },
            })
        }
    }

    $('.send-request').on('click',function(){
        $this = $(this);
        $.ajax({
            type:'POST',
            dataType:'json',
            data:{request_payment: $this.attr("data-id")},
            beforeSend:function(){ $this.btn("loading"); },
            complete:function(){ $this.btn("reset"); },
            success:function(json){
                $this.parents("tr").remove();
            },
        })
    })

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var hash = $(e.target).attr('href');
        if (history.pushState) {
            history.pushState(null, null, hash);
        } else {
            location.hash = hash;
        }
    });

    $(document).ready(function(){
        var hash = window.location.hash;
        if (hash) { $('.nav-link[href="' + hash + '"]').tab('show'); }

    })

    $(document).ready(function(){
        $(".wallet-popover").each(function(){
            new bootstrap.Popover(this, {
                placement: 'right',
                html: true,
                trigger: 'click'
            });
        });

        $('[data-bs-toggle="popover"]').each(function(){
            new bootstrap.Popover(this, {
                html: true,
                trigger: 'click'
            });
        });
    });

    $(document).delegate('.wallet-popover','click', function(e){
        e.stopPropagation();
        var html = $(this).parents("tr").find(".dpopver-content").html();
        var popover = bootstrap.Popover.getInstance(this);
        if(popover){
            popover.setContent({
                '.popover-body': html
            });
        }
    });

    $('html').on('click', function(e) {
        if (!$(e.target).hasClass('wallet-popover') &&
            !$(e.target).parents().is('.popover') &&
            !$(e.target).closest('[data-bs-toggle="popover"]').length) {
            $('.wallet-popover').each(function(){
                var popover = bootstrap.Popover.getInstance(this);
                if(popover) popover.hide();
            });
            $('[data-bs-toggle="popover"]').each(function(){
                var popover = bootstrap.Popover.getInstance(this);
                if(popover) popover.hide();
            });
        }
    })


    $("#modal-confirm .modal-body").delegate("[delete-tran-confirm]","click",function(){
        $this = $(this);
        $.ajax({
            url: '<?php echo base_url("usercontrol/confirm_remove_tran") ?>',
            type:'POST',
            dataType:'json',
            data:{id:$this.attr("delete-tran-confirm")},
            beforeSend:function(){ $this.button("loading"); },
            complete:function(){ $this.button("reset"); },
            success:function(json){
                window.location.reload();
            },
        })
    });

    $("#modal-confirm .modal-body").delegate("[change-tran-by-commi-confirm]","click",function(){
        $this = $(this);
        var status_type  = $this.attr("status_type");
        var id = $this.attr("id");

        $.ajax({
            type: "POST",
            url: '<?php echo base_url("usercontrol/change_commission_status") ?>',
            data: {status_type:status_type,id:id},
            cache: false,
            success: function(data)
            {
                window.location.reload();
            }
        });
    });


    $('.daterange-picker').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        ranges: {
            '<?= __('user.today'); ?>': [moment(), moment()],
            '<?= __('user.yesterday'); ?>': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '<?= __('user.last_7_days'); ?>': [moment().subtract(6, 'days'), moment()],
            '<?= __('user.last_30_days'); ?>': [moment().subtract(29, 'days'), moment()],
            '<?= __('user.this_month'); ?>': [moment().startOf('month'), moment().endOf('month')],
            '<?= __('user.last_month'); ?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            cancelLabel: 'Clear',
            format: 'DD-M-YYYY'
        }
    });

    $('.daterange-picker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-M-YYYY') + ' - ' + picker.endDate.format('DD-M-YYYY'));
    });

    $('.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $(document).on('click', '.show-trans-aff-details', function() {
        $('.transaction-datails-div-hidden').toggle();
    });

    $(document).on('click', '.wallet-checkbox', function() {
        let curTR = $(this).closest('tr');

        if($(this).prop('checked')) {
            if(!$(curTR).hasClass('child-row')) {
                $("tr[group_id='"+$(curTR).attr('group_id')+"'].child-row").each(function( index ) {
                    $( this ).find('.wallet-checkbox').prop('checked', true);
                    $( this ).find('.wallet-checkbox').prop('disabled', true);
                });
            }
        } else {
            if($(curTR).hasClass('child-row')) {
                $("tr[group_id='"+$(curTR).attr('group_id')+"']:not(.child-row)").each(function( index ) {
                    $( this ).find('.wallet-checkbox').prop('checked', false);
                });
            } else {
                $("tr[group_id='"+$(curTR).attr('group_id')+"'].child-row").each(function( index ) {
                    $( this ).find('.wallet-checkbox').prop('checked', false);
                    $( this ).find('.wallet-checkbox').prop('disabled', false);
                });
            }
        }
    });

    function changeStatus(el,id,status){
        let type = el.options[el.selectedIndex].dataset.type;

        if(status == 3 && type != 'recursion'){
            $("#modal-completed").modal("show");
            return false;
        }

        switch(type){
            case 'comission':
                infoRemoveTranByComission(el.value,id);
                break;
            case 'wallet':
                walletChangeStatus(el.value,id);
                break;
            case 'remove':
                infoRemoveTransaction(id);
                break;
            case 'recursion':
                infoRecursionTransaction(id);
                break;
            default:
                return;
        }
    }

    function infoRemoveTranByComission(value,id){
        $.ajax({
            url: '<?= base_url("usercontrol/info_remove_tran_by_commission") ?>',
            type:'POST',
            dataType:'json',
            data:{id:id,status_type:value},
            success:function(json){
                $("#modal-confirm .modal-body").html(json['html']);
                $("#modal-confirm").modal("show");
            },
        })
    }

    function walletChangeStatus(value,id){
        $.ajax({
            url: '<?= base_url("usercontrol/wallet_change_status") ?>',
            type:'POST',
            dataType:'json',
            data:{id:id,val:value},
            success:function(json){
                if(json['ask_confirm']){
                    $("#modal-confirmstatus .modal-body").html(json['html']);
                    $("#modal-confirmstatus").modal('show');
                }
                if(json['success']){
                    window.location.reload();
                }
            },
        })
    }

    $("#modal-confirmstatus").delegate(".close-modal","click",function(){
        $("#modal-confirmstatus").modal("hide");
    })

    function infoRemoveTransaction(id){
        $.ajax({
            url: '<?= base_url("usercontrol/info_remove_tran") ?>',
            type:'POST',
            dataType:'json',
            data:{id:id},
            success:function(json){
                $("#modal-confirm .modal-body").html(json['html']);
                $("#modal-confirm").modal("show");
            },
        })
    }

    function infoRecursionTransaction(id){
        $.ajax({
            url: '<?= base_url("usercontrol/info_recursion_tran") ?>',
            type:'POST',
            dataType:'json',
            data:{id:id},
            success:function(json){
                $("#modal-recursion .modal-body").html(json['html']);
                $("#modal-recursion").modal("show");
                if( json['recursion_type'] == 'custom_time' ){
                    $('.custom_time').show();
                }else{
                    $('.custom_time').hide();
                }
            },
        })
    }

</script>
