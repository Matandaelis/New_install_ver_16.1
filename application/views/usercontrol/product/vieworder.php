<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i><?= __('user.order_details') ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Product Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-box me-2"></i><?= __('user.product_information') ?>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0 py-3 px-4" style="width: 80px;"><?= __('user.image') ?></th>
                                                    <th class="border-0 py-3 px-4"><?= __('user.name') ?></th>
                                                    <th class="border-0 py-3 px-4 text-end"><?= __('user.unit_price') ?></th>
                                                    <th class="border-0 py-3 px-4 text-end"><?= __('user.variation_price') ?></th>
                                                    <th class="border-0 py-3 px-4 text-center"><?= __('user.quantity') ?></th>
                                                    <th class="border-0 py-3 px-4"><?= __('user.commission_type') ?></th>
                                                    <th class="border-0 py-3 px-4 text-end"><?= __('user.commission_amount') ?></th>
                                                    <th class="border-0 py-3 px-4 text-end"><?= __('user.total_discount') ?></th>
                                                    <th class="border-0 py-3 px-4 text-end"><?= __('user.total') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($products as $key => $product) { ?>
                                                    <tr class="border-bottom">
                                                        <td class="py-3 px-4">
                                                            <img src="<?= $product['image'] ?>" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;" onerror="this.onerror=null;this.src='<?= base_url('assets/images/no_image_available.png')?>';">
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <div>
                                                                <h6 class="fw-bold mb-1">
                                                                    <?php
                                                                    $combinationString = "";
                                                                    if(isset($product['variation']) && !empty($product['variation'])) {
                                                                        $variation = json_decode($product['variation']);
                                                                        foreach ($variation as $key => $value) {
                                                                            if($key == 'colors') {
                                                                                $combinationString .= ($combinationString == "") ? explode("-",$value)[1] : ",".explode("-",$value)[1];
                                                                            } else {
                                                                                $combinationString .= ($combinationString == "") ? $value : ",".$value;
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <?= $product['product_name'] ?> 
                                                                    <?= ($combinationString != "") ? "<small class='text-muted'>(".htmlspecialchars($combinationString).")</small>" : "" ?>
                                                                </h6>
                                                                
                                                                <?php if($product['coupon_discount'] > 0){ ?>
                                                                    <div class="mt-2">
                                                                        <span class="badge bg-success">
                                                                            <i class="fas fa-tag me-1"></i><?= __('user.code') ?> : <?= $product['coupon_code'] ?> <?= __('user.applied') ?>
                                                                        </span>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                        <td class="py-3 px-4 text-end fw-bold"><?php echo c_format($product['price']); ?></td>
                                                        <td class="py-3 px-4 text-end"><?php echo c_format(json_decode($product['variation'])->price); ?></td>
                                                        <td class="py-3 px-4 text-center">
                                                            <span class="badge bg-primary"><?php echo $product['quantity']; ?></span>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <span class="badge bg-secondary"><?php echo $product['commission_type']; ?></span>
                                                        </td>
                                                        <td class="py-3 px-4 text-end fw-bold text-success"><?php echo c_format($product['commission']); ?></td>
                                                        <td class="py-3 px-4 text-end text-danger"><?php echo c_format($product['coupon_discount']); ?></td>
                                                        <td class="py-3 px-4 text-end fw-bold fs-6"><?php echo c_format($product['total']); ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <?php foreach ($totals as $key => $total) { ?>
                                                    <tr class="table-light">
                                                        <td colspan="7"></td>
                                                        <td class="fw-bold py-3 px-4"><?= $total['text'] ?></td>
                                                        <td class="text-end fw-bold fs-5 text-primary py-3 px-4"><?php echo c_format($total['value']); ?></td>
                                                   </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-credit-card me-2"></i><?= __('user.order_payment_info') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0 py-3 px-4"><?= __('user.mode') ?></th>
                                                    <th class="border-0 py-3 px-4"><?= __('user.transaction_id') ?></th>
                                                    <th class="border-0 py-3 px-4"><?= __('user.payment_status') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if($order['status'] == 0){ ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <i class="fas fa-clock text-warning mb-2" style="font-size: 2rem;"></i>
                                                                <p class="text-muted mb-0"><?= __('user.waiting_for_payment_status') ?></p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php foreach ($payment_history as $key => $value) { ?>
                                                    <tr>
                                                        <td class="py-3 px-4">
                                                            <span class="badge bg-primary"><?php echo $value['payment_mode'];?></span>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <code class="bg-light text-dark p-1 rounded"><?php echo $order['txn_id'];?></code>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <span class="badge bg-success"><?php echo $value['paypal_status'] ?></span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <?php if($order['order_country']){ ?>
                                        <div class="mt-3">
                                            <h6 class="fw-bold"><?= __('user.order_done_from') ?></h6>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-info me-2"><?php echo $order['order_country'];?></span>
                                                <?php echo $order['order_country_flag'];?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <?php if($order['allow_shipping']){ ?>
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0 fw-semibold">
                                            <i class="fas fa-truck me-2"></i><?= __('user.shipping_details') ?>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold text-muted" style="width: 40%;"><?= __('user.address') ?></td>
                                                        <td><?php echo $order['address'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('user.country') ?></td>
                                                        <td><?php echo $order['country_name'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('user.state') ?></td>
                                                        <td><?php echo $order['state_name'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('user.city') ?></td>
                                                        <td><?php echo $order['city'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('user.postal_code') ?></td>
                                                        <td><?php echo $order['zip_code'] ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Order Status History -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-history me-2"></i><?= __('user.update_order_status') ?>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0 py-3 px-4" style="width: 50px;">#</th>
                                                    <th class="border-0 py-3 px-4"><?= __('user.status') ?></th>
                                                    <th class="border-0 py-3 px-4"><?= __('user.comment') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!$order_history){ ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem; opacity: 0.5;"></i>
                                                                <p class="text-muted mb-0"><?= __('user.no_any_order_status') ?></p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php foreach ($order_history as $key => $value) { ?>
                                                    <tr class="border-bottom">
                                                        <td class="py-3 px-4">
                                                            <span class="badge bg-primary">#<?= $key+1 ?></span>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <span class="badge bg-success"><?= $status[$value['order_status_id']] ?></span>
                                                        </td>
                                                        <td class="py-3 px-4"><?= $value['comment'] ?></td>
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
            </div>
        </div>
    </div>
</div>