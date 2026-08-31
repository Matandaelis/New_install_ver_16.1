<div class="container-fluid vieworder-page">
<div class="row">
<div class="col-12">

<!-- Header Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <i class="bi bi-receipt-cutoff me-2 fs-4"></i>
                <div>
                    <h4 class="mb-0 fw-bold"><?= __('admin.order_details') ?></h4>
                    <small class="opacity-75"><?= __('admin.order') ?> #<?= orderId($order['id']) ?></small>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url('admincontrol/listorders') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i><?= __('admin.back_to_orders') ?>
                </a>
                <div class="btn-group" role="group">
                    <a href="<?php echo base_url();?>admincontrol/orderaction/<?php echo $order['id'];?>/sendemail" class="btn btn-light btn-sm" title="<?= __('admin.send_email') ?>">
                        <i class="bi bi-envelope"></i>
                    </a>
                    <a href="<?php echo base_url();?>admincontrol/orderaction/<?php echo $order['id'];?>/print" target='_blank' class="btn btn-light btn-sm" title="<?= __('admin.print_order') ?>">
                        <i class="bi bi-printer"></i>
                    </a>
                    <button data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-outline-danger btn-sm" title="<?= __('admin.delete_order') ?>">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Status & Key Info -->
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-calendar-event text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small"><?= __('admin.order_date') ?></div>
                        <div class="fw-semibold"><?= dateGlobalFormat($order['created_at']) ?></div>
                        <small class="text-muted"><?= date('H:i A', strtotime($order['created_at'])) ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-currency-dollar text-success fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small"><?= __('admin.total_amount') ?></div>
                        <div class="fw-bold text-success fs-5"><?= c_format($order['total']) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-credit-card text-info fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small"><?= __('admin.payment_method') ?></div>
                        <div class="fw-semibold"><?= $order['payment_method'] ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-flag text-warning fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small"><?= __('admin.status') ?></div>
                        <?php 
                        $statusClass = 'bg-secondary';
                        $statusIcon = 'bi-clock';
                        if($order['status'] == 1) { 
                            $statusClass = 'bg-success'; 
                            $statusIcon = 'bi-check-circle';
                        } elseif($order['status'] == 7) { 
                            $statusClass = 'bg-warning'; 
                            $statusIcon = 'bi-pause-circle';
                        }
                        ?>
                        <span class="badge <?= $statusClass ?> fs-6">
                            <i class="<?= $statusIcon ?> me-1"></i><?= $status[$order['status']] ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-info text-white py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-box-seam me-2 fs-5"></i>
                <h5 class="mb-0 fw-bold"><?= __('admin.order_products') ?></h5>
                <span class="badge bg-light text-dark ms-2"><?= count($products) ?> <?= __('admin.items') ?></span>
            </div>
            <button class="btn btn-outline-light btn-sm" onclick="toggleProductDetails()" id="toggleProductBtn">
                <i class="bi bi-eye me-1"></i><?= __('admin.show_details') ?>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 80px;"></th>
                        <th><?= __('admin.product') ?></th>
                        <th class="text-center" style="width: 100px;"><?= __('admin.quantity') ?></th>
                        <th class="text-end" style="width: 120px;"><?= __('admin.unit_price') ?></th>
                        <th class="text-end" style="width: 120px;"><?= __('admin.total') ?></th>
                        <th class="text-center" style="width: 80px;"><?= __('admin.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $key => $product) { ?>
                        <tr class="product-row">
                            <td>
                                <img src="<?= $product['image'] ?>" class="img-thumbnail rounded" style="width: 60px; height: 60px; object-fit: cover;" onerror="this.onerror=null;this.src='<?= base_url('assets/images/no_image_available.png')?>';" alt="Product Image">
                            </td>
                            <td>
                                <div class="product-info">
                                    <h6 class="fw-bold mb-1 text-truncate" style="max-width: 300px;">
                                        <?php
                                        $combinationString = "";
                                        if(isset($product['variation']) && !empty($product['variation'])) {
                                            $variation = json_decode($product['variation']);
                                            if($variation) {
                                                foreach ($variation as $vkey => $value) {
                                                    if($vkey == 'colors') {
                                                        $combinationString .= ($combinationString == "") ? explode("-",$value)[1] : ",".explode("-",$value)[1];
                                                    } else {
                                                        $combinationString .= ($combinationString == "") ? $value : ",".$value;
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        <?= $product['product_name'] ? $product['product_name'] : '<i class="text-muted">'.__('admin.product_not_available').'</i>'?> 
                                    </h6>
                                    <?= ($combinationString != "") ? "<small class='text-muted d-block mb-2'>".htmlspecialchars($combinationString)."</small>" : "" ?>
                                    
                                    <!-- Badges for quick info -->
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        <?php if($product['commission']) { ?>
                                            <span class="badge bg-info bg-opacity-75 text-dark" title="<?= __('admin.affiliate_commission') ?>">
                                                <i class="bi bi-person-check me-1"></i><?= c_format($product['commission']) ?>
                                            </span>
                                        <?php } ?>
                                        <?php if($product['admin_commission']) { ?>
                                            <span class="badge bg-success bg-opacity-75 text-dark" title="<?= __('admin.admin_commission') ?>">
                                                <i class="bi bi-gear me-1"></i><?= c_format($product['admin_commission']) ?>
                                            </span>
                                        <?php } ?>
                                        <?php if($product['coupon_discount'] > 0){ ?>
                                            <span class="badge bg-warning bg-opacity-75 text-dark" title="<?= __('admin.coupon_applied') ?>">
                                                <i class="bi bi-tag me-1"></i><?= $product['coupon_code'] ?>
                                            </span>
                                        <?php } ?>
                                        <?php if($order['status'] == 1 && $product['product_type'] == 'downloadable' && $product['downloadable_files']) { ?>
                                            <span class="badge bg-primary" title="<?= __('admin.downloadable') ?>">
                                                <i class="bi bi-download me-1"></i><?= count($product['downloadable_files']) ?> <?= __('admin.files') ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                    
                                    <!-- Detailed info (hidden by default) -->
                                    <div class="product-details d-none">
                                        <?php if(isset($venders[$product['product_id']])) { ?>
                                            <div class="border rounded p-2 mb-2 bg-light">
                                                <small>
                                                    <strong><?= __('admin.vendor_name') ?></strong> : <?php echo $venders[$product['product_id']]['firstname']." ".$venders[$product['product_id']]['lastname'] ?><br>
                                                    <strong><?= __('admin.vendor_email') ?></strong> : <?php echo $venders[$product['product_id']]['email']; ?><br>
                                                    <strong><?= __('admin.vendor_commission') ?></strong> : <?php echo c_format($venders[$product['product_id']]['vendor_commission']); ?>
                                                </small>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php if($product['commission']) { ?>
                                            <div class="border rounded p-2 mb-2 border-info bg-info bg-opacity-10">
                                                <small>
                                                    <strong><?= __('admin.affiliate_name') ?></strong> : <?php echo $product['refer_name']; ?><br>
                                                    <strong><?= __('admin.affiliate_email') ?></strong> : <?php echo $product['refer_email']; ?><br>
                                                    <strong><?= __('admin.commission_type') ?></strong> : <?php echo ($product['commission_type'] == 'fixed') ? __('admin.fixed') : $product['commission_type']; ?><br>
                                                    <strong><?= __('admin.affiliate_commission') ?></strong> : <?php echo c_format($product['commission']); ?>
                                                </small>
                                            </div>
                                        <?php } ?>

                                        <?php if($order['status'] == 1 && $product['product_type'] == 'downloadable' && $product['downloadable_files']) { ?>
                                            <div class="mt-2">   
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php foreach ($product['downloadable_files'] as $downloadable_filess) { ?>
                                                        <a href="<?php echo base_url('store/downloadable_file/'. $downloadable_filess['name'] . '/' .$downloadable_filess['mask']) ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                                            <i class="bi bi-download me-1"></i><?php echo $downloadable_filess['mask'] ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary fs-6"><?php echo $product['quantity']; ?></span>
                            </td>
                            <td class="text-end fw-bold"><?php echo c_format($product['price']); ?></td>
                            <td class="text-end fw-bold text-success fs-6"><?php echo c_format($product['total']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-outline-info btn-sm" onclick="toggleProductRow(<?= $key ?>)" title="<?= __('admin.toggle_details') ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot class="table-light border-top">
                    <?php foreach ($totals as $key => $total) { ?>
                    <tr>
                        <td colspan="4" class="text-end fw-bold"><?= $total['text'] ?></td>
                        <td class="text-end fw-bold fs-5 text-primary"><?php echo c_format($total['value']); ?></td>
                        <td></td>
                    </tr>
                    <?php } ?>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Customer & Payment Information -->
<div class="row g-4 mb-4">
    <!-- Customer Details -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-success text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-circle me-2 fs-5"></i>
                    <h5 class="mb-0 fw-bold"><?= __('admin.customer_information') ?></h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted"><?= __('admin.full_name') ?></small>
                                <div class="fw-bold"><?php echo $order['firstname'];?> <?php echo $order['lastname'];?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-envelope text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted"><?= __('admin.email') ?></small>
                                <div><a href="mailto:<?php echo $order['email'];?>" class="text-decoration-none fw-semibold"><?php echo $order['email'];?></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-telephone text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted"><?= __('admin.phone') ?></small>
                                <div><a href="tel:<?php echo $order['client_phone'];?>" class="text-decoration-none fw-semibold"><?php echo $order['client_phone'];?></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-geo-alt text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted"><?= __('admin.location') ?></small>
                                <div class="fw-semibold">
                                    <?php echo $order['client_city'];?>, <?php echo $order['client_state'];?><br>
                                    <?php echo $order['client_country'];?> - <?php echo $order['client_zipcode'];?>
                                </div>
                                <?php if($order['allow_shipping'] != 0 && !empty($order['address'])) { ?>
                                    <div class="mt-1">
                                        <small class="text-muted"><?= __('admin.address') ?>:</small>
                                        <div class="text-muted small"><?php echo $order['address'];?></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-info text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-credit-card me-2 fs-5"></i>
                    <h5 class="mb-0 fw-bold"><?= __('admin.payment_information') ?></h5>
                </div>
            </div>
            <div class="card-body">
                <?php if(!empty($payment_history)) { ?>
                    <div class="row g-3">
                        <?php foreach ($payment_history as $key => $value) { ?>
                            <div class="col-12">
                                <div class="border rounded p-3 bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <small class="text-muted"><?= __('admin.payment_method') ?></small>
                                            <div class="fw-bold">
                                                <i class="bi bi-credit-card me-1"></i>
                                                <?php
                                                if ($value['payment_mode'] == 'Bank Transfer') {
                                                    echo __('admin.bank_transfer');
                                                }elseif ($value['payment_mode'] == 'Cash On Delivery') {
                                                    echo __('admin.cash_on_delivery');
                                                }elseif ($value['payment_mode'] == 'OPay') {
                                                    echo __('admin.opay');
                                                }elseif ($value['payment_mode'] == 'Paypal') {
                                                    echo __('admin.paypal');
                                                }elseif ($value['payment_mode'] == 'Razorpay') {
                                                    echo __('admin.razorpay');
                                                }elseif ($value['payment_mode'] == 'Flutterwave') {
                                                    echo __('admin.flutterwave');
                                                }else{
                                                    echo str_replace("_", " ", $value['payment_mode']);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted"><?= __('admin.transaction_id') ?></small>
                                            <div><code class="bg-white text-dark p-1 rounded border"><?php echo $order['txn_id'] ? $order['txn_id'] : 'N/A';?></code></div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted"><?= __('admin.status') ?></small>
                                            <div>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    <?php 
                                                    if($value['paypal_status'] == 'Processed'){
                                                        echo __('admin.processed');
                                                    }else{
                                                        echo $value['paypal_status'];
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="text-center py-4">
                        <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2"><?= __('admin.no_payment_history') ?></p>
                    </div>
                <?php } ?>
                
                <!-- Additional Payment Info -->
                <?php if($order['payment_method'] == 'bank_transfer' && !empty($paymentsetting['bank_transfer_instruction'])){ ?>
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-info mb-2">
                            <i class="bi bi-bank me-1"></i><?= __('admin.bank_transfer_instruction') ?>
                        </h6>
                        <div class="alert alert-info mb-0"><?php echo $paymentsetting['bank_transfer_instruction'] ?></div>
                    </div>
                <?php } ?>

                <?php if($order['order_country']){ ?>
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-secondary mb-2">
                            <i class="bi bi-geo-alt me-1"></i><?= __('admin.order_done_from') ?>
                        </h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info me-2"><?php echo $order['order_country'];?></span>
                            <?php echo $order['order_country_flag'];?>
                        </div>
                    </div>
                <?php } ?>

                <?php if($orderProof){ ?>
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-primary mb-2">
                            <i class="bi bi-file-earmark-check me-1"></i><?= __('store.payment_proof') ?>
                        </h6>
                        <a href="<?= $orderProof->downloadLink ?>" target='_blank' class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i><?= __('store.download') ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Additional Order Information -->
<?php if($order['comment'] || $order['files']){ ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-chat-dots me-2 fs-5"></i>
                    <h5 class="mb-0 fw-bold"><?= __('admin.additional_information') ?></h5>
                </div>
            </div>
            <div class="card-body">
                <?php if($order['comment']){ ?>
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-chat-text me-1"></i><?= __('admin.order_comments') ?>
                        </h6>
                        <div class="row g-3">
                            <?php foreach ($order['comment'] as $key => $value) { ?>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="fw-bold text-dark mb-2"><?= $value['title'] ?></h6>
                                        <p class="text-muted mb-0"><?= $value['comment'] ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if($order['files']){ ?>
                    <div class="mb-0">
                        <h6 class="fw-bold text-info mb-3">
                            <i class="bi bi-paperclip me-1"></i><?= __('admin.order_attachments') ?>
                        </h6>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i><?php echo $order['files'] ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php if($order['allow_shipping']){ ?>
<!-- Shipping Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-truck me-2 fs-5"></i>
                    <h5 class="mb-0 fw-bold"><?= __('store.shipping_details') ?></h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-telephone text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted"><?= __('admin.phone') ?></small>
                                <div><a href="tel:<?php echo $order['phone'] ?>" class="text-decoration-none fw-semibold"><?php echo $order['phone'] ?></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-geo-alt text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted"><?= __('admin.shipping_address') ?></small>
                                <div class="fw-semibold">
                                    <?php echo $order['address'] ?><br>
                                    <?php echo $order['city'] ?>, <?php echo $order['state_name'] ?><br>
                                    <?php echo $order['country_name'] ?> - <?php echo $order['zip_code'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Order Status Management -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-gear me-2 fs-5"></i>
                    <h5 class="mb-0 fw-bold"><?= __('admin.order_management') ?></h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Update Status Form -->
                    <div class="col-lg-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0 fw-bold">
                                    <i class="bi bi-pencil-square me-1"></i><?= __('admin.update_status') ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="statusUpdateForm">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-flag me-1"></i><?= __('admin.order_status') ?>
                                        </label>
                                        <select name="payment_item_status" id="payment_item_status" required="required" class="form-select form-select-lg">
                                            <option value=""><?= __('admin.please_choose') ?></option>
                                            <?php foreach ($status as $key => $value) { ?>
                                                <option value="<?php echo $key ?>" <?= ($order['status'] == $key) ? 'selected' : '' ?>><?php echo $value ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-chat-text me-1"></i><?= __('admin.comment') ?>
                                        </label>
                                        <textarea name="remarks" id="remarks" class="form-control" rows="4" required="required" placeholder="<?= __('admin.enter_status_change_comment') ?>..."></textarea>
                                    </div>
                                    <button name="submit" class="btn btn-primary btn-lg w-100" type="submit">
                                        <i class="bi bi-check-circle me-2"></i><?= __('admin.update_order_status') ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status History -->
                    <div class="col-lg-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0 fw-bold">
                                    <i class="bi bi-clock-history me-1"></i><?= __('admin.status_history') ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if(!empty($order_history)) { ?>
                                    <div class="timeline-container" style="max-height: 300px; overflow-y: auto;">
                                        <?php foreach ($order_history as $key => $value) { ?>
                                            <div class="d-flex mb-3 pb-3 <?= ($key < count($order_history) - 1) ? 'border-bottom' : '' ?>">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                                        <span class="badge bg-success"><?= $key+1 ?></span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-success mb-1">
                                                        <?= $status[$value['order_status_id']] ?>
                                                    </div>
                                                    <div class="text-muted small mb-1">
                                                        <?= isset($value['created_at']) ? dateGlobalFormat($value['created_at']) : '' ?>
                                                    </div>
                                                    <?php if(!empty($value['comment'])) { ?>
                                                        <div class="text-dark small">
                                                            <i class="bi bi-chat-quote me-1"></i><?= $value['comment'] ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2"><?= __('admin.no_status_history') ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= __('admin.confirm_deletion') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="bi bi-trash text-danger" style="font-size: 4rem;"></i>
                </div>
                <h5 class="mb-3 text-danger"><?= __('admin.are_you_sure') ?></h5>
                <p class="text-muted mb-4"><?= __('admin.delete_order_warning') ?></p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong><?= __('admin.order') ?> #<?= orderId($order['id']) ?></strong><br>
                    <small><?= __('admin.this_action_cannot_be_undone') ?></small>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-2 w-100">
                    <a href="<?php echo base_url();?>admincontrol/orderaction/<?php echo $order['id'];?>/delete/0" class="btn btn-danger flex-fill">
                        <i class="bi bi-trash me-1"></i><?= __('admin.delete_order_only') ?>
                    </a>
                    <a href="<?php echo base_url();?>admincontrol/orderaction/<?php echo $order['id'];?>/delete/1" class="btn btn-outline-danger flex-fill">
                        <i class="bi bi-trash-fill me-1"></i><?= __('admin.delete_with_commissions') ?>
                    </a>
                </div>
                <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i><?= __('admin.cancel') ?>
                </button>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>

<script>
$(document).ready(function() {
    // Toggle product details functionality
    window.toggleProductDetails = function() {
        $('.product-details').toggleClass('d-none');
        const btn = $('#toggleProductBtn');
        const icon = btn.find('i');
        const isHidden = $('.product-details').hasClass('d-none');
        
        if (isHidden) {
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
            btn.html('<i class="bi bi-eye me-1"></i><?= __('admin.show_details') ?>');
        } else {
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
            btn.html('<i class="bi bi-eye-slash me-1"></i><?= __('admin.hide_details') ?>');
        }
    };

    // Toggle individual product row details
    window.toggleProductRow = function(index) {
        const row = $('.product-row').eq(index);
        const details = row.find('.product-details');
        const btn = row.find('button[onclick*="toggleProductRow"]');
        const icon = btn.find('i');
        
        details.toggleClass('d-none');
        
        if (details.hasClass('d-none')) {
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        }
    };

    // Form validation and enhancement
    $('#statusUpdateForm').on('submit', function(e) {
        const status = $('#payment_item_status').val();
        const comment = $('#remarks').val().trim();
        
        if (!status) {
            e.preventDefault();
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.please_select_status') ?>', 'error', 3000);
            } else {
                alert('<?= __('admin.please_select_status') ?>');
            }
            return false;
        }
        
        if (!comment) {
            e.preventDefault();
            if (typeof showToast === 'function') {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.please_enter_comment') ?>', 'error', 3000);
            } else {
                alert('<?= __('admin.please_enter_comment') ?>');
            }
            return false;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-2"></i><?= __('admin.updating') ?>...');
        
        // Re-enable button after a delay (in case form submission fails)
        setTimeout(function() {
            submitBtn.prop('disabled', false).html(originalText);
        }, 5000);
    });

    // Auto-resize textarea
    $('#remarks').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Smooth scroll to sections
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
});
</script>