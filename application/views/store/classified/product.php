<section aff-section="classified_product_page"></section>
<script aff-template="classified_product_page" type="text/html">
<section class="py-5 bg-light">
    <div class="container">
        <!-- Product Detail Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="bg-white rounded-3 shadow-sm overflow-hidden">
                    {{#product}}
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-center p-4 bg-light h-100">
                                <img src="{{product_featured_image}}" class="img-fluid rounded" alt="Product Image" style="max-height: 400px; object-fit: contain;">
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="p-4 h-100 d-flex flex-column">
                               <ul class="list-unstyled small text-muted mb-3">
                                   <li class="d-inline me-3"><i class="far fa-clock me-1"></i>{{product_updated_date}}</li>
                                   <li class="d-inline me-3"><i class="fas fa-user me-1"></i><a href="<?=base_url('store/productionstore/')?>{{product_created_by_base64}}" class="text-decoration-none">{{product_created_by_name}}</a></li>
                                   <li class="d-inline"><i class="far fa-eye me-1"></i>{{view}} views</li>
                               </ul>
                               
                               <h3 class="fw-bold text-dark mb-3">{{product_name}}</h3>
                               <p class="text-muted mb-4">{{product_description}}</p>
                               
                               <div class="mb-4">
                                   <div class="row g-3">
                                       <div class="col-sm-6">
                                           <div class="d-flex align-items-center">
                                               <span class="fw-semibold text-dark me-2"><?= __('store.price')?>:</span>
                                               <span class="h4 text-primary fw-bold mb-0">{{product_price}}</span>
                                           </div>
                                       </div>
                                       <div class="col-sm-6">
                                           <small class="text-muted d-block"><?= __('store.category')?>: {{product_category}}</small>
                                           <small class="text-muted d-block"><?= __('store.sku')?>: {{product_sku}}</small>
                                       </div>
                                   </div>
                               </div>
                               
                               <div class="mt-auto">
                                   <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center justify-content-between">
                                       <div class="d-flex gap-2">
                                           <button type="button" id="btn-add-to-wishlist" class="btn btn-outline-danger <?=$is_wishlisted_class != '' ? 'active' : ''?>">
                                               <i class="<?=$is_wishlisted_class != '' ? 'fa' : 'far'?> fa-heart me-1"></i>
                                               <span class="d-none d-md-inline"><?=$is_wishlisted_class != '' ? __('store.remove_from_favorites') : __('store.add_to_favorites')?></span>
                                           </button>
                                           <button type="button" class="btn btn-outline-secondary" data-social-share data-share-url="<?= $actual_link;?>">
                                               <i class="fas fa-share-alt me-1"></i>
                                               <span class="d-none d-md-inline"><?= __('store.share')?></span>
                                           </button>
                                       </div>
                                       <a href="{{product_url}}" class="btn btn-primary btn-lg px-4">
                                           <i class="fas fa-shopping-cart me-2"></i><?= __('store.buy_now')?>
                                       </a>
                                   </div>
                               </div>
                           </div>
                        </div>
                    </div>
                    {{/product}}
                </div>
            </div>
        </div>
        
        <!-- Related Products Section -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold text-dark mb-3"><?= __('store.related_ads')?></h2>
                    <div class="border-bottom border-primary border-3 mx-auto" style="width: 80px;"></div>
                </div>
                
                <div class="row g-4">
                    {{#related_products}}
                    {{#.}}
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="position-relative overflow-hidden">
                                <img class="card-img-top" src="{{product_featured_image}}" alt="Product" style="height: 200px; object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-2">{{product_name}}</h6>
                                <p class="h6 text-primary fw-bold mb-3">{{product_price}}</p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">{{total_sale}} sold</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{product_details_url}}" class="btn btn-outline-primary btn-sm flex-fill"><?= __('store.view')?></a>
                                        <a href="{{product_url}}" class="btn btn-primary btn-sm flex-fill"><?= __('store.buy_now')?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{/.}}
                    {{/related_products}}
                </div>
            </div>
        </div>
    </div>
</section>
<?=$social_share_modal?>
</script>

<script type="text/javascript">
      function aff_prepare_classified_product_page(data) {
         data['related_products'] = createChunks(data['related_products'], 8);
         return data;
      }

      function createChunks(array, chunk_size) {
         let chunks = [];
         while (array.length > 0)
          chunks.push(array.splice(0, chunk_size));
       return chunks;
      }
    
      $(window).on('shown.bs.modal', function(){
       $('#social-share-modal').find('.close').addClass('btn')
      });

      $(document).on('click', '#btn-add-to-wishlist', function() {
          let isWishlisted = $(this).hasClass('w-listed');
          $(this).toggleClass('w-listed');

          if (!isWishlisted) {
              $(this).html('<i class="fa fa-heart me-1"></i><span class="d-none d-md-inline"><?= __('store.remove_from_favorites') ?></span>');
          } else {
              $(this).html('<i class="far fa-heart me-1"></i><span class="d-none d-md-inline"><?= __('store.add_to_favorites') ?></span>');
          }

          $.ajax({
              url: '<?= base_url('store/toggle_wishlist') ?>',
              type: 'POST',
              dataType: 'json',
              data: { product_id: $("#product_id").val() },
              success: function(json) {
                  // Response handling logic here if needed
              },
          });
      });
</script>