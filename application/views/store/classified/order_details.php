<link href="<?= base_url('assets/store/classified/'); ?>assets/css/order-details.css" rel="stylesheet" />

<section class="order-details-section py-5">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <!-- Page Header -->
            <div class="page-header mb-4">
               <div class="d-flex justify-content-between align-items-center">
                  <div>
                     <h2 class="mb-1">
                        <i class="fas fa-receipt me-2 text-primary"></i>
                        <?= __('store.order_details') ?>
                     </h2>
                     <p class="text-muted mb-0">
                        <?= __('store.order') ?> #<?php echo orderId($order['id']); ?>
                     </p>
                  </div>
                  <a href="<?= base_url('store/orders') ?>" class="btn btn-outline-primary">
                     <i class="fas fa-arrow-left me-2"></i>
                     <?= __('store.back_to_orders') ?>
                  </a>
               </div>
            </div>

            <!-- Order Products -->
            <div class="card mb-4">
               <div class="card-header bg-primary text-white">
                  <h5 class="mb-0">
                     <i class="fas fa-shopping-bag me-2"></i>
                     <?= __('store.order_items') ?>
                  </h5>
               </div>
               <div class="card-body p-0">
                  <div class="table-responsive">
                     <table class="table table-hover mb-0">
                        <thead class="table-light">
                           <tr>
                              <th><?= __('store.name') ?></th>
                              <th class="text-center"><?= __('store.unit_price') ?></th>
                              <th class="text-center"><?= __('store.quantity') ?></th>
                              <th class="text-end"><?= __('store.total') ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($products as $key => $product) { ?>
                              <tr>
                                 <td>
                                    <div class="d-flex align-items-center">
                                       <img src="<?= (!empty($product['image'])) ? $product['image'] : base_url('assets/store/default/img/1.png'); ?>" 
                                            alt="<?= $product['product_name'] ?>" 
                                            class="product-thumb me-3">
                                       <div>
                                          <div class="fw-semibold">
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
                                             <?= ($combinationString != "") ? "<span class='text-muted'>(" . $combinationString . ")</span>" : "" ?>
                                          </div>
                                          <?php if($product['coupon_discount'] > 0){ ?>
                                             <small class="badge bg-success">
                                                <i class="fas fa-tag me-1"></i>
                                                <?= $product['coupon_code'] ?> <?= __('store.applied') ?>
                                             </small>
                                          <?php } ?>
                                       </div>
                                    </div>
                                 </td>
                                 <td class="text-center align-middle">
                                    <?php echo c_format($product['price'] + $product['variation_price']); ?>
                                 </td>
                                 <td class="text-center align-middle">
                                    <span class="badge bg-secondary"><?php echo $product['quantity']; ?></span>
                                 </td>
                                 <td class="text-end align-middle fw-bold">
                                    <?php echo c_format($product['total']); ?>
                                 </td>
                              </tr>
                              <?php if($order['status'] == 1 && $product['product_type'] == 'downloadable' && $product['downloadable_files']) { ?>
                                 <tr>
                                    <td colspan="4" class="bg-light">
                                       <div class="p-2">
                                          <strong class="text-success">
                                             <i class="fas fa-download me-2"></i>
                                             <?= __('store.downloadable_files') ?>:
                                          </strong>
                                          <div class="mt-2">
                                             <?php foreach ($product['downloadable_files'] as $downloadable_filess) { 
                                                   if (strpos($downloadable_filess['url'], 'http://') === 0 || strpos($downloadable_filess['url'], 'https://') === 0) { 
                                                      $downloadable=$downloadable_filess['url'];
                                                   }else{
                                                      $downloadable=base_url('store/downloadable_file/'. $downloadable_filess['name'] . '/' .$downloadable_filess['mask'])."/".$order_id;
                                                   }
                                                   ?>
                                                <a class="btn btn-sm btn-success me-2 mb-2" href="<?php echo $downloadable; ?>" target="_blank">
                                                   <i class="fas fa-file-download me-1"></i>
                                                   <?php echo $downloadable_filess['mask'] ?>
                                                </a>
                                             <?php } ?>
                                          </div>
                                       </div>
                                    </td>
                                 </tr>
                              <?php } ?>
                           <?php } ?>
                        </tbody>
                        <tfoot class="table-light">
                           <?php foreach ($totals as $key => $total) { ?>
                              <tr>
                                 <td colspan="3" class="text-end fw-semibold"><?= $total['text'] ?></td>
                                 <td class="text-end fw-bold"><?php echo c_format($total['value']); ?></td>
                              </tr>
                           <?php } ?>
                        </tfoot>
                     </table>
                  </div>
               </div>
            </div>

            <div class="row">
               <!-- Payment Information -->
               <div class="col-lg-<?= $order['allow_shipping'] ? '6' : '12' ?> mb-4">
                  <div class="card h-100">
                     <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                           <i class="fas fa-credit-card me-2"></i>
                           <?= __('store.order_payment_info') ?>
                        </h5>
                     </div>
                     <div class="card-body">
                        <?php if($order['status'] == 0){ ?>
                           <div class="alert alert-warning">
                              <i class="fas fa-clock me-2"></i>
                              <?= __('store.waiting_for_payment_status') ?>
                           </div>
                        <?php } ?>
                        
                        <?php foreach ($payment_history as $key => $value) { ?>
                           <div class="payment-info-item mb-3 p-3 bg-light rounded">
                              <div class="row">
                                 <div class="col-md-4">
                                    <small class="text-muted d-block"><?= __('store.mode') ?></small>
                                    <strong><?php echo str_replace("_", " ", ucfirst($value['payment_mode'])) ?></strong>
                                 </div>
                                 <div class="col-md-4">
                                    <small class="text-muted d-block"><?= __('store.transaction_id') ?></small>
                                    <strong><?php echo $order['txn_id'];?></strong>
                                 </div>
                                 <div class="col-md-4">
                                    <small class="text-muted d-block"><?= __('store.payment_status') ?></small>
                                    <span class="badge bg-success"><?php echo $value['paypal_status'] ?></span>
                                 </div>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if($order['payment_method'] == 'bank_transfer'){ ?>
                           <div class="alert alert-info mt-3">
                              <h6 class="alert-heading">
                                 <i class="fas fa-university me-2"></i>
                                 <?= __('store.bank_transfer_instruction') ?>
                              </h6>
                              <pre class="mb-0 bg-white p-3 rounded"><?php echo $paymentsetting['bank_transfer_instruction'] ?></pre>
                           </div>
                        <?php } ?>

                        <?php if($orderProof){ ?>
                           <div class="alert alert-success mt-3">
                              <i class="fas fa-file-invoice me-2"></i>
                              <strong><?= __('store.payment_proof') ?>:</strong>
                              <a href="<?= $orderProof->downloadLink ?>" target='_blank' class="btn btn-sm btn-success ms-2">
                                 <i class="fas fa-download me-1"></i>
                                 <?= __('store.download') ?>
                              </a>
                           </div>
                        <?php } ?>

                        <?php if($order['order_country']){ ?>
                           <div class="mt-3 p-3 bg-light rounded">
                              <small class="text-muted d-block"><?= __('store.order_done_from') ?></small>
                              <strong>
                                 <?php echo $order['order_country'];?> 
                                 <?php echo $order['order_country_flag'];?>
                              </strong>
                           </div>
                        <?php } ?>

                        <?php if($order['files']){ ?>
                           <div class="mt-3 p-3 bg-light rounded">
                              <small class="text-muted d-block mb-2"><?= __('store.order_attechments_download') ?></small>
                              <?php echo $order['files'] ?>
                           </div>
                        <?php } ?>
                     </div>
                  </div>
               </div>

               <!-- Shipping Details -->
               <?php if($order['allow_shipping']){ ?>
                  <div class="col-lg-6 mb-4">
                     <div class="card h-100">
                        <div class="card-header bg-info text-white">
                           <h5 class="mb-0">
                              <i class="fas fa-shipping-fast me-2"></i>
                              <?= __('store.shipping_details') ?>
                           </h5>
                        </div>
                        <div class="card-body">
                           <div class="shipping-info">
                              <div class="info-item mb-3">
                                 <small class="text-muted d-block"><?= __('store.address') ?></small>
                                 <strong><?php echo $order['address'] ?></strong>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block"><?= __('store.country') ?></small>
                                    <strong><?php echo $order['country_name'] ?></strong>
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block"><?= __('store.state') ?></small>
                                    <strong><?php echo $order['state_name'] ?></strong>
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block"><?= __('store.city') ?></small>
                                    <strong><?php echo $order['city'] ?></strong>
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block"><?= __('store.postal_code') ?></small>
                                    <strong><?php echo $order['zip_code'] ?></strong>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               <?php } ?>
            </div>

            <!-- Order Comments -->
            <?php if($order['comment']){ ?>
               <div class="card mb-4">
                  <div class="card-header bg-warning text-dark">
                     <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>
                        <?= __('store.order_view_comment') ?>
                     </h5>
                  </div>
                  <div class="card-body">
                     <?php foreach ($order['comment'] as $key => $value) { ?>
                        <div class="comment-item p-3 mb-2 bg-light rounded">
                           <h6 class="mb-1"><?= $value['title'] ?></h6>
                           <p class="mb-0 text-muted"><?= $value['comment'] ?></p>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            <?php } ?>

            <!-- Order Status History -->
            <div class="card">
               <div class="card-header bg-dark text-white">
                  <h5 class="mb-0">
                     <i class="fas fa-history me-2"></i>
                     <?= __('store.update_order_status') ?>
                  </h5>
               </div>
               <div class="card-body">
                  <?php if(!$order_history){ ?>
                     <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <?= __('store.no_any_order_status') ?>
                     </div>
                  <?php } else { ?>
                     <div class="timeline">
                        <?php foreach ($order_history as $key => $value) { ?>
                           <div class="timeline-item">
                              <div class="timeline-marker">
                                 <span class="badge bg-primary rounded-circle"><?= $key + 1 ?></span>
                              </div>
                              <div class="timeline-content">
                                 <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                       <h6 class="mb-1">
                                          <span class="badge bg-success"><?= $status[$value['order_status_id']] ?></span>
                                       </h6>
                                       <p class="mb-0 text-muted"><?= $value['comment'] ?></p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        <?php } ?>
                     </div>
                  <?php } ?>
               </div>
            </div>

         </div>
      </div>
   </div>
</section>