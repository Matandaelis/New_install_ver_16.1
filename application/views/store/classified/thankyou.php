<link href="<?= base_url('assets/store/classified/assets/css/thankyou.css'); ?>" rel="stylesheet" />

<div class="modern-thankyou-page">
   <div class="modern-header no-print">
      <a href="<?= base_url('store/orders') ?>">
         <i class="fas fa-arrow-left me-2"></i>Back to Orders
      </a>
      <?php $logo = ($store_setting['logo']) ? base_url('assets/images/site/'.$store_setting['logo']) : base_url('assets/store/default/').'img/logo.png'; ?>
      <a href="<?= base_url('store') ?>">
         <img src="<?= $logo ?>" alt="Logo" style="max-height: 40px;">
      </a>
      <a href="<?= base_url('store/print/'.$order['id'].'?print=1') ?>">
         <i class="fas fa-print me-2"></i>Print
      </a>
   </div>

   <div class="success-hero">
      <div class="success-icon">
         <svg viewBox="0 0 24 24">
            <polyline points="20 6 9 17 4 12"></polyline>
         </svg>
      </div>
      <h1><?= __('store.thank_you_for_purchasing_an_order') ?></h1>
      <p>Your order has been successfully placed and is being processed</p>
      <div class="order-number">
         <?= __('store.order_number') ?> #<?= orderId($order['id']) ?>
      </div>
   </div>

   <div class="modern-card">
      <h2><i class="fas fa-box me-2"></i><?= __('store.product_info') ?></h2>
      <?php foreach ($products as $key => $product): ?>
         <div class="product-item">
            <img src="<?= (!empty($product['image'])) ? $product['image'] : base_url('assets/store/default/img/1.png') ?>" 
                 alt="<?= htmlspecialchars($product['product_name']) ?>" 
                 class="product-image">
            <div class="product-details">
               <div class="product-name"><?= htmlspecialchars($product['product_name']) ?></div>
               <div class="product-meta">
                  <span><strong>Price:</strong> <?= c_format($product['price'] + $product['variation_price']) ?></span>
                  <span><strong>Qty:</strong> <?= $product['quantity'] ?></span>
                  <span><strong>Total:</strong> <?= c_format($product['total']) ?></span>
               </div>
               <?php if($product['coupon_discount'] > 0): ?>
                  <div class="mt-2">
                     <span class="badge bg-success">
                        <i class="fas fa-tag me-1"></i>
                        Coupon <?= $product['coupon_code'] ?> applied
                     </span>
                  </div>
               <?php endif; ?>
               <?php if($order['status'] == 1 && in_array($product['product_type'], ['downloadable', 'video', 'videolink']) && $product['downloadable_files']): ?>
                  <div class="mt-3">
                     <?php foreach ($product['downloadable_files'] as $file): 
                        $downloadable = strpos($file['url'], 'http://') === 0 || strpos($file['url'], 'https://') === 0 ? 
                                       $file['url'] : 
                                       base_url('store/downloadable_file/' . $file['name'] . '/' . $file['mask'] . '/' . $product['order_id']);
                     ?>
                        <a href="<?= $downloadable ?>" class="btn btn-sm btn-success me-2 mb-2" target="_blank">
                           <i class="fas fa-download me-1"></i><?= $file['mask'] ?>
                        </a>
                     <?php endforeach; ?>
                  </div>
               <?php endif; ?>
            </div>
         </div>
      <?php endforeach; ?>

      <div class="order-summary">
         <?php foreach ($totals as $key => $total): ?>
            <div class="summary-row">
               <span><?= $total['text'] ?></span>
               <span><?= c_format($total['value']) ?></span>
            </div>
         <?php endforeach; ?>
      </div>
   </div>

   <div class="modern-card">
      <h2><i class="fas fa-credit-card me-2"></i><?= __('store.order_payment_info') ?></h2>
      <div class="info-grid">
         <?php if($order['status'] == 0): ?>
            <div class="info-item">
               <div class="info-value text-warning">
                  <i class="fas fa-clock me-2"></i><?= __('store.waiting_for_payment_status') ?>
               </div>
            </div>
         <?php endif; ?>
         <?php foreach ($payment_history as $key => $value): ?>
            <div class="info-item">
               <div class="info-label">Payment Mode</div>
               <div class="info-value"><?= str_replace("_", " ", $value['payment_mode']) ?></div>
            </div>
            <div class="info-item">
               <div class="info-label">Transaction ID</div>
               <div class="info-value"><?= $order['txn_id'] ?></div>
            </div>
            <div class="info-item">
               <div class="info-label">Status</div>
               <div class="info-value text-success">
                  <i class="fas fa-check-circle me-1"></i><?= $value['paypal_status'] ?>
               </div>
            </div>
         <?php endforeach; ?>
      </div>

      <?php if($order['payment_method'] == 'bank_transfer'): ?>
         <div class="alert alert-info mt-3">
            <strong><i class="fas fa-info-circle me-2"></i><?= __('store.bank_transfer_instruction') ?></strong>
            <pre class="mt-2 mb-0"><?= $paymentsetting['bank_transfer_instruction'] ?></pre>
         </div>
      <?php endif; ?>
   </div>

   <?php if($order['allow_shipping']): ?>
      <div class="modern-card">
         <h2><i class="fas fa-truck me-2"></i><?= __('store.shipping_details') ?></h2>
         <div class="info-grid">
            <div class="info-item">
               <div class="info-label">Phone</div>
               <div class="info-value"><?= $order['phone'] ?></div>
            </div>
            <div class="info-item">
               <div class="info-label">Address</div>
               <div class="info-value"><?= $order['address'] ?></div>
            </div>
            <div class="info-item">
               <div class="info-label">City</div>
               <div class="info-value"><?= $order['city'] ?></div>
            </div>
            <div class="info-item">
               <div class="info-label">State</div>
               <div class="info-value"><?= $order['state_name'] ?></div>
            </div>
            <div class="info-item">
               <div class="info-label">Country</div>
               <div class="info-value"><?= $order['country_name'] ?></div>
            </div>
            <div class="info-item">
               <div class="info-label">Postal Code</div>
               <div class="info-value"><?= $order['zip_code'] ?></div>
            </div>
         </div>
      </div>
   <?php endif; ?>

   <!-- Sales Funnel Upsells -->
   <?php if (!empty($funnel_upsells)): ?>
   <div class="modern-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
      <div class="text-center mb-4">
         <h2 class="text-white mb-2">
            <i class="fas fa-gift me-2"></i>
            <?= __('store.exclusive_offers') ?>
         </h2>
         <p class="mb-0" style="opacity: 0.95;"><?= __('store.special_discounts_available') ?></p>
      </div>
      
      <div class="funnel-upsells-container">
         <?php foreach ($funnel_upsells as $index => $upsell): ?>
            <?php
            $upsell_image = !empty($upsell['product_featured_image']) 
               ? base_url('assets/images/product/upload/thumb/' . $upsell['product_featured_image'])
               : base_url('assets/store/default/img/1.png');
            ?>
            <div class="upsell-card-modern" data-product-id="<?= $upsell['product_id'] ?>" style="display: none;">
               <div class="row align-items-center">
                  <div class="col-md-5">
                     <img src="<?= $upsell_image ?>" alt="<?= htmlspecialchars($upsell['product_name']) ?>" 
                          class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                  </div>
                  <div class="col-md-7">
                     <?php if ($upsell['discount_percent'] > 0): ?>
                        <div class="mb-3">
                           <span class="badge bg-danger fs-5 px-3 py-2">
                              <i class="fas fa-fire me-1"></i>
                              SAVE <?= $upsell['discount_percent'] ?>%
                           </span>
                        </div>
                     <?php endif; ?>
                     
                     <h3 class="text-white fw-bold mb-3"><?= htmlspecialchars($upsell['product_name']) ?></h3>
                     <p class="mb-4" style="opacity: 0.95;"><?= htmlspecialchars(substr($upsell['product_description'], 0, 200)) ?>...</p>
                     
                     <div class="pricing-modern mb-4">
                        <?php if ($upsell['discount_percent'] > 0): ?>
                           <div class="d-flex align-items-center gap-3 mb-2">
                              <span class="text-decoration-line-through" style="font-size: 1.5rem; opacity: 0.7;">
                                 <?= c_format($upsell['product_price']) ?>
                              </span>
                              <span class="fs-1 fw-bold text-white">
                                 <?= c_format($upsell['funnel_price']) ?>
                              </span>
                           </div>
                           <div>
                              <span class="badge bg-success fs-6 px-3 py-2">
                                 <i class="fas fa-check-circle me-1"></i>
                                 You Save <?= c_format($upsell['discount_amount']) ?>
                              </span>
                           </div>
                        <?php else: ?>
                           <span class="fs-1 fw-bold text-white">
                              <?= c_format($upsell['funnel_price']) ?>
                           </span>
                        <?php endif; ?>
                     </div>
                     
                     <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg fw-bold upsell-buy-btn" 
                                data-product-id="<?= $upsell['product_id'] ?>"
                                data-product-name="<?= htmlspecialchars($upsell['product_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-funnel-price="<?= $upsell['funnel_price'] ?>"
                                data-regular-price="<?= $upsell['product_price'] ?>">
                           <i class="fas fa-bolt me-2"></i>
                           <?= __('store.buy_now') ?> - <?= c_format($upsell['funnel_price']) ?>
                        </button>
                        <button class="btn btn-light btn-lg upsell-skip-btn" 
                                data-product-id="<?= $upsell['product_id'] ?>">
                           <i class="fas fa-times me-2"></i>
                           <?= __('store.no_thanks') ?>
                        </button>
                     </div>
                     
                     <div class="text-center mt-3">
                        <small style="opacity: 0.8;">
                           <i class="fas fa-lock me-1"></i>
                           One-time exclusive offer
                        </small>
                     </div>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
         
         <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
               <span class="fw-bold" style="opacity: 0.9;">
                  <i class="fas fa-layer-group me-1"></i>
                  Offer <span id="upsell-progress-text">1 of <?= count($funnel_upsells) ?></span>
               </span>
            </div>
            <div class="progress" style="height: 8px; background: rgba(255,255,255,0.3);">
               <div class="progress-bar bg-light" role="progressbar" style="width: <?= round(100 / count($funnel_upsells)) ?>%" id="upsell-progress-bar"></div>
            </div>
         </div>
      </div>
   </div>
   <?php endif; ?>

   <div class="action-buttons no-print">
      <a href="<?= base_url('store') ?>" class="btn-modern btn-primary-modern">
         <i class="fas fa-shopping-bag"></i>
         Continue Shopping
      </a>
      <a href="<?= base_url('store/orders') ?>" class="btn-modern btn-secondary-modern">
         <i class="fas fa-list"></i>
         View All Orders
      </a>
   </div>
</div>

<script>
$(document).ready(function() {
   <?php if (!empty($funnel_upsells)): ?>
   let currentUpsellIndex = 0;
   const totalUpsells = <?= count($funnel_upsells) ?>;
   const upsellCards = $('.upsell-card-modern');
   
   showUpsell(currentUpsellIndex);
   
   $('.upsell-buy-btn').on('click', function() {
      const productId = $(this).data('product-id');
      const funnelPrice = $(this).data('funnel-price');
      const btn = $(this);
      const card = btn.closest('.upsell-card-modern');
      
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');
      
      $.ajax({
         url: '<?= base_url('classified/funnel_add_to_cart') ?>',
         type: 'POST',
         data: {
            product_id: productId,
            funnel_price: funnelPrice
         },
         dataType: 'json',
         success: function(response) {
            if (response.success) {
               btn.html('<i class="fas fa-check-circle me-2"></i>Added!');
               
               setTimeout(function() {
                  card.fadeOut(400, function() {
                     currentUpsellIndex++;
                     if (currentUpsellIndex < totalUpsells) {
                        showUpsell(currentUpsellIndex);
                     } else {
                        showCheckoutRedirect();
                     }
                  });
               }, 600);
            } else {
               btn.prop('disabled', false).html('<i class="fas fa-bolt me-2"></i><?= __('store.buy_now') ?> - $' + funnelPrice);
               alert(response.message || 'Failed to add product');
            }
         },
         error: function() {
            btn.prop('disabled', false).html('<i class="fas fa-bolt me-2"></i><?= __('store.buy_now') ?> - $' + funnelPrice);
            alert('Network error. Please try again.');
         }
      });
   });
   
   $('.upsell-skip-btn').on('click', function() {
      const card = $(this).closest('.upsell-card-modern');
      
      card.fadeOut(400, function() {
         currentUpsellIndex++;
         if (currentUpsellIndex < totalUpsells) {
            showUpsell(currentUpsellIndex);
         } else {
            $('.funnel-upsells-container').parent().fadeOut(600);
         }
      });
   });
   
   function showUpsell(index) {
      upsellCards.hide();
      if (upsellCards.eq(index).length) {
         upsellCards.eq(index).fadeIn(400);
      }
      updateProgress(index + 1, totalUpsells);
   }
   
   function updateProgress(current, total) {
      const percentage = (current / total) * 100;
      $('#upsell-progress-bar').css('width', percentage + '%');
      $('#upsell-progress-text').text(current + ' of ' + total);
   }
   
   function showCheckoutRedirect() {
      const html = `
         <div class="text-center py-5">
            <div class="mb-4">
               <i class="fas fa-check-circle" style="font-size: 80px;"></i>
            </div>
            <h3 class="fw-bold mb-3">Perfect!</h3>
            <p class="mb-4">Redirecting to checkout...</p>
            <div class="spinner-border" role="status"></div>
         </div>
      `;
      $('.funnel-upsells-container').html(html);
      setTimeout(function() {
         window.location.href = '<?= base_url('store/checkout') ?>';
      }, 1500);
   }
   <?php endif; ?>
});
</script>