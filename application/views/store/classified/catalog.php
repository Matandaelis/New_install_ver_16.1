<section aff-section="classified_catalog_page"></section>
<script aff-template="classified_catalog_page" type="text/html">
   <section class="py-5 bg-light">
      <div class="container">
         <div class="row g-4">
            {{#filter}}
            <div class="col-xl-3 col-lg-4">
               <div class="bg-white rounded-3 shadow-sm p-4 sticky-top" style="top: 100px;">
                  <h3 class="h5 fw-bold text-dark mb-4 pb-2 border-bottom"><?= __('store.filter')?></h3>
                  <form id="filter-form" action="">
                     <div class="accordion" id="accordion">
                        {{#categories_filter.status}}
                        <div class="accordion-item border-0 mb-3">
                           <h2 class="accordion-header" id="headingCategories">
                              <button class="accordion-button bg-light fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                 <i class="fas fa-tags me-2 text-primary"></i><?= __('store.category')?>
                              </button>
                           </h2>
                           <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingCategories" data-bs-parent="#accordion">
                              <div class="accordion-body p-0">
                                 <div class="accordion" id="accordion2">
                                    {{#categories_filter.data}}
                                    <div class="accordion-item border-0">
                                       <h2 class="accordion-header" id="heading-category-{{id}}">
                                          <button class="accordion-button collapsed bg-transparent border-0 py-2 small" type="button" data-bs-toggle="collapse" data-bs-target="#category-{{id}}" aria-expanded="false" aria-controls="category-{{id}}">
                                             <img src="{{image}}" class="rounded me-2" style="width: 20px; height: 20px; object-fit: cover;">
                                             <span class="fw-medium">{{name}}</span>
                                             <span class="badge bg-primary rounded-pill ms-auto">{{product_count}}</span>
                                          </button>
                                       </h2>
                                       <div id="category-{{id}}" class="accordion-collapse collapse" aria-labelledby="heading-category-{{id}}" data-bs-parent="#accordion2">
                                          <div class="accordion-body py-2">
                                             <ul class="list-unstyled mb-0 ps-3">
                                                <li class="mb-1">
                                                   <a href="javascript:void(0);" class="filter-and-sort-products text-decoration-none text-muted d-flex justify-content-between align-items-center py-1" data-sort-key="aff_filter_category" data-sort-value="{{id}}">
                                                      <span>{{name}}</span>
                                                      <small class="text-muted">({{product_count}})</small>
                                                   </a>
                                                </li>
                                                {{#childs}}
                                                <li class="mb-1">
                                                   <a href="javascript:void(0);" class="filter-and-sort-products text-decoration-none text-muted d-flex justify-content-between align-items-center py-1" data-sort-key="aff_filter_category" data-sort-value="{{id}}">
                                                      <span>{{name}}</span>
                                                      <small class="text-muted">({{product_count}})</small>
                                                   </a>
                                                </li>
                                                {{/childs}}
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    {{/categories_filter.data}}
                                 </div>
                              </div>
                           </div>
                        </div>
                        {{/categories_filter.status}}
                        
                        {{#location_filter.status}}
                        <div class="accordion-item border-0 mb-3">
                           <h2 class="accordion-header" id="headingLocation">
                              <button class="accordion-button bg-light fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                 <i class="fas fa-map-marker-alt me-2 text-success"></i><?= __('store.location')?>
                              </button>
                           </h2>
                           <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingLocation" data-bs-parent="#accordion">
                              <div class="accordion-body p-0">
                                 <div class="accordion" id="accordion3">
                                    {{#location_filter.data}}
                                    <div class="accordion-item border-0">
                                       <h2 class="accordion-header" id="heading-location-{{id}}">
                                          <button class="accordion-button collapsed bg-transparent border-0 py-2 small" type="button" data-bs-toggle="collapse" data-bs-target="#location{{id}}" aria-expanded="false" aria-controls="location{{id}}">
                                             <span class="fw-medium">{{name}}</span>
                                             <span class="badge bg-success rounded-pill ms-auto">{{product_count}}</span>
                                          </button>
                                       </h2>
                                       <div id="location{{id}}" class="accordion-collapse collapse" aria-labelledby="heading-location-{{id}}" data-bs-parent="#accordion3">
                                          <div class="accordion-body py-2">
                                             <ul class="list-unstyled mb-0 ps-3">
                                                {{#states}}
                                                <li class="mb-1">
                                                   <a href="javascript:void(0);" class="filter-and-sort-products text-decoration-none text-muted d-flex justify-content-between align-items-center py-1" data-sort-key="aff_filter_location" data-sort-value="{{id}}">
                                                      <span>{{name}}</span>
                                                      <small class="text-muted">({{product_count}})</small>
                                                   </a>
                                                </li>
                                                {{/states}}
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    {{/location_filter.data}}
                                 </div>
                              </div>
                           </div>
                        </div>
                        {{/location_filter.status}}
                        
                        <div class="accordion-item border-0 mb-3">
                           <h2 class="accordion-header" id="headingPrice">
                              <button class="accordion-button bg-light fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                 <i class="fas fa-dollar-sign me-2 text-warning"></i><?= __('store.price_range')?>
                              </button>
                           </h2>
                           <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingPrice" data-bs-parent="#accordion">
                              <div class="accordion-body">
                                 <div class="row g-2 mb-3">
                                    <div class="col-6">
                                       <input type="number" name="aff_filter_price_min" class="form-control form-control-sm" placeholder="Min" value="<?= $_GET['aff_filter_price_min']; ?>"/>
                                    </div>
                                    <div class="col-6">
                                       <input type="number" name="aff_filter_price_max" class="form-control form-control-sm" placeholder="Max" value="<?= $_GET['aff_filter_price_max']; ?>"/>
                                    </div>
                                 </div>
                                 <input type="hidden" name="aff_filter_category" value="<?= $_GET['aff_filter_category']; ?>" />
                                 <input type="hidden" name="aff_filter_location" value="<?= $_GET['aff_filter_location']; ?>" />
                                 <input type="hidden" name="aff_sort_by" value="<?= $_GET['aff_sort_by']; ?>" />
                                 <button type="submit" class="btn btn-primary btn-sm w-100 mb-2"><?= __('store.apply_filters')?></button>
                                 <button type="button" class="btn btn-outline-secondary btn-sm w-100 btn-clear-filter"><?= __('store.clear_filters')?></button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            {{/filter}}
            
            <div class="col-xl-9 col-lg-8">
               {{#results_status}}
               <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                  <div class="row align-items-center">
                     <div class="col-md-6">
                        <h2 class="h4 fw-bold text-dark mb-0">{{results_status}}</h2>
                     </div>
                     <div class="col-md-6 d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                        <div class="d-flex align-items-center gap-3">
                           <div class="dropdown">
                              <button class="btn btn-outline-primary dropdown-toggle btn-sm" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                 <i class="fas fa-sort me-1"></i>Sort By
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="sortDropdown">
                                 <li><a class="dropdown-item filter-and-sort-products" data-sort-key="aff_sort_by" data-sort-value="1" href="javascript:void(0);"><?= __('store.a_to_z_title')?></a></li>
                                 <li><a class="dropdown-item filter-and-sort-products" data-sort-key="aff_sort_by" data-sort-value="2" href="javascript:void(0);"><?= __('store.z_to_a_title')?></a></li>
                                 <li><a class="dropdown-item filter-and-sort-products" data-sort-key="aff_sort_by" data-sort-value="3" href="javascript:void(0);"><?= __('store.data_added_newest')?></a></li>
                                 <li><a class="dropdown-item filter-and-sort-products" data-sort-key="aff_sort_by" data-sort-value="4" href="javascript:void(0);"><?= __('store.data_added_oldest')?></a></li>
                                 <li><a class="dropdown-item filter-and-sort-products" data-sort-key="aff_sort_by" data-sort-value="5" href="javascript:void(0);"><?= __('store.most_viewed')?></a></li>
                                 <li><a class="dropdown-item filter-and-sort-products" data-sort-key="aff_sort_by" data-sort-value="6" href="javascript:void(0);"><?= __('store.less_viewed')?></a></li>
                                 <li><a class="dropdown-item filter-and-sort-products" data-sort-key="aff_sort_by" data-sort-value="7" href="javascript:void(0);"><?= __('store.price_low_to_high')?></a></li>
                                 <li><a class="dropdown-item filter-and-sort-products" data-sort-key="aff_sort_by" data-sort-value="8" href="javascript:void(0);"><?= __('store.price_high_to_low')?></a></li>
                              </ul>
                           </div>
                           
                           <div class="btn-group btn-group-sm" role="group" aria-label="View toggle">
                              <button type="button" class="btn btn-outline-secondary active product-view-trigger" data-type="product-box-list">
                                 <i class="fas fa-th-list"></i>
                              </button>
                              <button type="button" class="btn btn-outline-secondary product-view-trigger" data-type="product-box-grid">
                                 <i class="fas fa-th-large"></i>
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               {{/results_status}}
               
               <div id="product-view" class="product-box-grid">
                  <div class="row g-3">
                     {{#products}}
                     <div class="col-xl-4 col-md-6 d-none">
                        <!-- Grid View -->
                        <div class="product-grid-view">
                           <div class="card h-100 shadow-sm border-0">
                              <div class="position-relative overflow-hidden">
                                 <a href="{{product_details_url}}" class="{{#product_sale_is_on}}position-relative{{/product_sale_is_on}}">
                                    <img src="{{product_featured_image}}" alt="Product" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    {{#product_sale_is_on}}
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                                    {{/product_sale_is_on}}
                                 </a>
                              </div>
                              <div class="card-body">
                                 <h5 class="card-title mb-2"><a href="{{product_details_url}}" class="text-decoration-none text-dark">{{product_name}}</a></h5>
                                 <ul class="list-unstyled small text-muted mb-3">
                                    <li><i class="fa fa-tags me-1"></i>{{total_sale}} <?= __('store.sold')?></li>
                                    <li><i class="fas fa-user me-1"></i>{{product_created_by_name}}</li>
                                 </ul>
                                 <div class="d-flex justify-content-between align-items-center">
                                    <span class="h6 text-primary fw-bold mb-0">{{product_price}}</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        
                        <!-- List View -->
                        <div class="product-list-view">
                           <div class="d-flex bg-white rounded shadow-sm p-3 mb-3">
                              <div class="flex-shrink-0 me-3">
                                 <a href="{{product_details_url}}" class="{{#product_sale_is_on}}item-trending{{/product_sale_is_on}}">
                                    <img src="{{product_featured_image}}" alt="Product" class="rounded" style="width: 150px; height: 120px; object-fit: cover;">
                                 </a>
                              </div>
                              <div class="flex-grow-1 d-flex flex-column">
                                 <div class="flex-grow-1">
                                    <h3 class="h5 mb-2"><a href="{{product_details_url}}" class="text-decoration-none text-dark">{{product_name}}</a></h3>
                                    <ul class="list-unstyled small mb-2">
                                       <li><span class="fw-semibold"><?= __('store.category')?>:</span> {{product_category}}</li>
                                       <li><span class="fw-semibold"><?= __('store.sku')?>:</span> {{product_sku}}</li>
                                    </ul>
                                    <p class="text-muted small mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do dolore magna aliqua. In eu mi bibendum neque egestas…</p>
                                    <ul class="list-unstyled small text-muted mb-0">
                                       <li class="d-inline me-3"><i class="fa fa-tags me-1"></i>{{total_sale}} sold</li>
                                       <li class="d-inline me-3"><i class="fas fa-user me-1"></i>{{product_created_by_name}}</li>
                                       <li class="d-inline"><i class="far fa-eye me-1"></i>{{total_views}} Views</li>
                                    </ul>
                                 </div>
                              </div>
                              <div class="text-end d-flex flex-column justify-content-between">
                                 <div class="h5 text-primary fw-bold mb-0">{{product_price}}</div>
                                 <a href="{{product_details_url}}" class="btn btn-primary btn-sm"><?= __('store.details')?></a>
                              </div>
                           </div>
                        </div>
                     </div>
                     {{/products}}
                     {{^products}}
                        <div class="col-12">
                           <div class="alert alert-warning text-center py-5">
                              <i class="fas fa-search fa-3x text-muted mb-3"></i>
                              <h5 class="mb-2"><?= __('store.no_produts_found')?></h5>
                              <p class="text-muted mb-0">Try adjusting your filters or search terms</p>
                           </div>
                        </div>
                     {{/products}}
                  </div>
               </div>
               
               {{#pagination}}
               <nav aria-label="Product pagination" class="mt-4">
                  <div class="d-flex justify-content-between align-items-center">
                     {{#previous}}
                     <a href="{{previous}}" class="btn btn-outline-primary">
                        <i class="fas fa-angle-double-left me-1"></i><?= __('store.previous')?>
                     </a>
                     {{/previous}}
                     {{^previous}}
                     <button class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-angle-double-left me-1"></i><?= __('store.previous')?>
                     </button>
                     {{/previous}}
                     
                     <ul class="pagination pagination-sm mb-0">
                        {{#left_links}}
                        <li class="page-item"><a class="page-link" href="{{link}}">{{index}}</a></li>
                        {{/left_links}}
                        {{#current}}
                        <li class="page-item active"><a class="page-link" href="{{link}}">{{index}}</a></li>
                        {{/current}}
                        {{#right_links}}
                        <li class="page-item"><a class="page-link" href="{{link}}">{{index}}</a></li>
                        {{/right_links}}
                     </ul>

                     {{#next}}
                     <a href="{{next}}" class="btn btn-outline-primary">
                        <?= __('store.next')?><i class="fas fa-angle-double-right ms-1"></i>
                     </a>
                     {{/next}}
                     {{^next}}
                     <button class="btn btn-outline-secondary" disabled>
                        <?= __('store.next')?><i class="fas fa-angle-double-right ms-1"></i>
                     </button>
                     {{/next}}
                  </div>
               </nav>
               {{/pagination}}
            </div>
         </div>
      </div>
   </section>


</script>

<script type="text/javascript">
   $(document).on('submit', '#filter-form', function () {
      $(this)
        .find('input[name]')
        .filter(function () {
            return !this.value;
        })
        .prop('name', '');
   });
</script>