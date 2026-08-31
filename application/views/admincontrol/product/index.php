<?php
   $db =& get_instance();
   $userdetails=$db->userdetails();
   $store_setting =$db->Product_model->getSettings('store');
   $Product_model =$db->Product_model;
   ?>
<div id="overlay"></div>
<div class="popupbox" style="display: none;">
   <div class="backdrop box">
      <div class="modalpopup" style="display:block;">
         <a href="javascript:void(0)" class="close js-menu-close" onclick="closePopup();"><i class="fa fa-times"></i></a>
         <div class="modalpopup-dialog">
            <div class="modalpopup-content">
               <div class="modalpopup-body">
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="container-fluid px-4 pb-4">
    <?php get_instance()->load->view('admincontrol/store/_store_nav'); ?>
<div class="row">
<div class="col-12">

<?php if ($currentTheme=="sales" || $StoreStatus=="0"){ ?>
<div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <div><?= __('admin.cart_product_notice') ?></div>
</div>
<?php } ?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <div class="d-flex align-items-center">
            <i class="bi bi-box-seam me-2"></i>
            <h5 class="mb-0 fw-semibold"><?= __('admin.cart_mode_products') ?></h5>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-4">
            <ul class="nav nav-pills nav-fill mb-4" id="TabsNav">
                <li class="nav-item">
                    <a class="nav-link active product_tab_option d-flex align-items-center justify-content-center" href="#product_tab" data-bs-toggle="tab">
                        <i class="bi bi-box-seam me-2"></i><?= __('admin.products') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link product-part product_coupons_tab_option d-flex align-items-center justify-content-center" href="#product_coupons_tab" data-bs-toggle="tab">
                        <i class="bi bi-ticket-perforated me-2"></i><?= __('admin.coupon') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-center" href="#form_tab" data-bs-toggle="tab">
                        <i class="bi bi-file-earmark-text me-2"></i><?= __('admin.forms') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-center" href="#form_coupons_tab" data-bs-toggle="tab">
                        <i class="bi bi-receipt me-2"></i><?= __('admin.forms_coupon') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-center" href="#review_tab" data-bs-toggle="tab">
                        <i class="bi bi-star me-2"></i><?= __('admin.review') ?>
                    </a>
                </li>
            </ul>
        </div>
                  
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="product_tab">
                <div class="px-4 pb-4">
                    <div class="bg-light rounded-3 p-3 mb-4 product-filter-section">
                        <?php $is_review_mode = isset($only_review) && $only_review === 'reviews'; ?>
                        <div class="btn-group btn-group-sm mb-3" role="group">
                            <a href="<?= base_url('admincontrol/listproduct') ?>" class="btn <?= !$is_review_mode ? 'btn-primary' : 'btn-outline-primary' ?>">
                                <i class="bi bi-box-seam me-1"></i><?= __('admin.all_product') ?>
                            </a>
                            <a href="<?= base_url('admincontrol/listproduct/reviews') ?>" class="btn <?= $is_review_mode ? 'btn-warning' : 'btn-outline-warning' ?>">
                                <i class="bi bi-clock-history me-1"></i><?= __('admin.products_pending_review') ?>
                            </a>
                        </div>
                        <form id="filter-form">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-2">
                                    <label class="form-label fw-medium small">
                                        <i class="bi bi-funnel me-1"></i><?= __('admin.category') ?>
                                    </label>
                                    <select name="category_id" class="form-select form-select-sm select-category">
                                        <?php $selected = isset($_GET['category_id']) ? $_GET['category_id'] : ''; ?>
                                        <option value=""><?= __('admin.all_category') ?></option>
                                        <?php foreach ($categories as $key => $value) { ?>
                                            <option <?= $selected == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-medium small">
                                        <i class="bi bi-person-badge me-1"></i><?= __('admin.vendor') ?>
                                    </label>
                                    <select name="seller_id" class="form-select form-select-sm select-vendor">
                                        <?php $selected = isset($_GET['seller_id']) ? $_GET['seller_id'] : ''; ?>
                                        <option value=""><?= __('admin.all_vendor') ?></option>
                                        <?php foreach ($vendors as $key => $value) { ?>
                                            <option <?= $selected == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-1 flex-wrap align-items-center">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#manageBulkProducts">
                                            <i class="bi bi-upload me-1"></i><?= __('admin.manage_bulk_products') ?>
                                        </button>
                                        <a class="btn btn-primary btn-sm" href="<?php echo base_url('admincontrol/addproduct'); ?>">
                                            <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_product') ?>
                                        </a>
                                        <button type="button" id="btn-load-demo-products" class="btn btn-info btn-sm text-white">
                                            <i class="bi bi-magic me-1"></i><?= __('admin.load_demo_products') ?>
                                        </button>
                                        <button type="button" id="btn-clear-demo-products" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash3 me-1"></i><?= __('admin.clear_demo_products') ?>
                                        </button>
                                        <button type="button" style="display:none;" class="btn btn-danger btn-sm" name="deletebutton" id="deletebutton" onclick="deleteuserlistfunc('deleteAllproducts');">
                                            <i class="bi bi-trash me-1"></i><?= __('admin.delete_products') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <div id="manageBulkProducts" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                           <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                 <div class="modal-header">
                                     <h5 class="modal-title"><?= __('admin.manage_bulk_products') ?></h5>
                                     <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                                 </div>
                  <div class="modal-body">
                      <div class="row g-4">
                          <div class="col-6 text-center">
                              <button class="btn btn-lg btn-success text-center export-products-btn w-100 mb-2"><?= __('admin.export_products') ?></button>
                          </div>
                          <div class="col-6 text-center">
                              <button class="btn btn-lg btn-success text-center export-structure-btn w-100 mb-2"><?= __('admin.export_structure_only') ?></button>
                          </div>
                      </div>

                      <div class="row g-4">
                          <div class="col-6 text-center">
                              <button class="btn btn-lg btn-success text-center xml-btn export-products-xml-btn w-100"><?= __('admin.export_products_xml') ?></button>
                          </div>
                          <div class="col-6 text-center">
                              <button class="btn btn-lg btn-success text-center xml-btn export-structure-xml-btn w-100"><?= __('admin.export_structure_xml_only') ?></button>
                          </div>
                      </div>


 
         <div class="row">
            <div class="col">
               <!-- tab start -->
               <div class="tab-pane p-3" id="tab_bulkprodcut_option" role="tabpanel">
                  <div role="tabpanel">
                     <ul class="nav nav-pills flex-column flex-sm-row" id="TabsNav">
                        <li class="nav-item flex-sm-fill text-sm-center">
                           <a class="nav-link active show" href="#import_file_tab" aria-controls="import_file_tab" role="tab" data-bs-toggle="tab"><?= __('admin.import_from_file') ?></a>
                        </li>
                        <li class="nav-item flex-sm-fill text-sm-center">
                           <a class="nav-link" href="#import_link_tab" aria-controls="import_link_tab" role="tab" data-bs-toggle="tab"><?= __('admin.import_from_url') ?></a>
                        </li> 
                     </ul>
                  </div>
               </div>
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="import_file_tab">
                     <form id="bulk_products_form" class="text-center">
                        <div class="custom-file my-3">
                           <input type="file" class="custom-file-input" name="file">
                           <label class="custom-file-label" for="customFile"><?= __('admin.upload_excel_file_for_bulk_product_manage') ?></label>
                        </div>
                        <button id="bulk_products_form_btn" type="submit" class="btn btn-lg btn-default btn-success text-center"><?= __('admin.import_products') ?></button><br/><br/>
                     </form>
                  </div>
                   <div role="tabpanel" class="tab-pane" id="import_link_tab">
                       <form id="bulk_products_form_url" class="text-center">
                        <div class="custom-file my-3">
                           <input name="txt_xmlurl" id="txt_xmlurl" class="textxmlurl"  type="text" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" 
                           placeholder="<?= __('admin.enter_xml_url_for_bulk_product_manage') ?>" style="width:100%;">
                            
                        </div>
                        <button id="bulk_products_form_url_btn" type="submit" class="btn btn-lg btn-default btn-success text-center"><?= __('admin.import_products') ?></button><br/><br/>
                     </form>
                  </div>
               </div>      
               <!--tab end -->

               

                  <a class="mb-4" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#collapseInstructions" aria-expanded="false" aria-controls="collapseInstructions"><?= __('admin.click_here_for_excel_file_upload') ?></a>
                  <div class="collapse" id="collapseInstructions">
                     <div class="card card-body text-left" style="max-height: 300px; overflow-y: scroll">
                        <table class="table table-striped">
                           <thead>
                              <tr>
                                 <td><?= __('admin.column') ?></td>
                                 <td><?= __('admin.description') ?></td>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td><?= __('admin.product_id') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.optional') ?></li>
                                    <li><?= __('admin.ip_product_id_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_id_desc_2') ?></li>
                                    <li><?= __('admin.ip_product_id_desc_3') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_name') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_product_name_desc_1') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_sku') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_product_sku_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_sku_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_msrp') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.optional') ?></li>
                                    <li><?= __('admin.ip_product_msrp_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_msrp_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_price') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_product_price_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_price_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_short_desc') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_product_short_desc_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_short_desc_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_desc') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_product_desc_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_desc_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_tag') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.optional') ?></li>
                                    <li><?= __('admin.ip_product_tag_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_tag_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_type') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_product_type_disc_1') ?> "virtual", "downloadable"</li>
                                    <li><?= __('admin.ip_product_type_disc_2') ?>/</li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_variations') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.optional') ?></li>
                                    <li><?= __('admin.ip_product_variations_desc_1') ?></li>
                                    <ul>
                                    <pre style="overflow: visible;">
                                    {
                                    "colors":[
                                    {"code":"#FF0000","name":"Red","price":"10"},
                                    {"code":"#3014FF","name":"Blue","price":"15"}
                                    ],
                                    "size":[
                                    {"name":"Horizontal 56","price":"10"},
                                    {"name":"Horizontal 112","price":"15"}
                                    ]
                                    }
                                    <pre>
                                    <ul>
                                       <li><?= __('admin.ip_product_variations_desc_2') ?></li>
                                    </ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.allow_comment') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_allow_comment_desc_1') ?></li>
                                    <li><?= __('admin.ip_allow_comment_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.allow_shipping') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_allow_shipping_desc_1') ?></li>
                                    <li><?= __('admin.ip_allow_shipping_desc_2') ?>t</li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.allow_file_uplolad') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_allow_file_uplolad_desc_1') ?></li>
                                    <li><?= __('admin.ip_allow_file_uplolad_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_status') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_product_status_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_status_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.allow_on_store') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.required') ?></li>
                                    <li><?= __('admin.ip_allow_on_store_desc_1') ?>)</li>
                                    <li><?= __('admin.ip_allow_on_store_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.state_id') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.optional') ?></li>
                                    <li><?= __('admin.ip_state_id_desc_1') ?></li>
                                    <li><?= __('admin.ip_state_id_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
                              <tr>
                                 <td><?= __('admin.product_created_by') ?></td>
                                 <td>
                                    <ul>
                                    <li><?= __('admin.optional') ?></li>
                                    <li><?= __('admin.ip_product_created_by_desc_1') ?></li>
                                    <li><?= __('admin.ip_product_created_by_desc_2') ?></li>
                                    <ul>
                                 </td>
                              </tr>
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
                        <div id="manageBulkProductsConfirmation" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                           <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title"><?= __('admin.manage_bulk_product_confirmation') ?></h5>
                                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                                 </div>
                                 <div class="modal-body" style="max-height:350px; overflow-y:scroll;">
                                 </div>
                                 <div class="modal-footer">
                                    <button class="btn btn-lg btn-success text-center import-products-confirm"><?= __('admin.confirm_product_import') ?></button>
                                    <button class="btn btn-lg btn-success text-center" data-bs-dismiss="modal"><?= __('admin.cancel') ?></button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="manageBulkProductsResult" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                           <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title"><?= __('admin.manage_bulk_product_result') ?></h5>
                                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                                 </div>
                                 <div class="modal-body" style="max-height:350px; overflow-y:scroll;">
                                 </div>
                                 <div class="modal-footer">
                                    <button class="btn btn-lg btn-default btn-success text-center" id="bulkokbutton" onclick="window.location.reload()"><?= __('admin.ok') ?></button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <br>
                        <?php if ($productlist == null) {?>
                        <div class="text-center py-5 product-empty-state">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <i class="bi bi-<?= $is_review_mode ? 'clock-history' : 'box-seam' ?> display-1 text-muted mb-3"></i>
                                <h4 class="text-muted mb-2"><?= $is_review_mode ? __('admin.no_products_pending_review') : __('admin.no_data_found') ?></h4>
                                <p class="text-muted"><?= $is_review_mode ? __('admin.vendor_products_pending_will_appear_here') : __('admin.add_product_to_get_started') ?></p>
                                <?php if ($is_review_mode) { ?>
                                <a class="btn btn-outline-primary mt-3" href="<?= base_url('admincontrol/listproduct') ?>">
                                    <i class="bi bi-box-seam me-1"></i><?= __('admin.view_all_products') ?>
                                </a>
                                <?php } else { ?>
                                <a class="btn btn-primary mt-3" href="<?php echo base_url('admincontrol/addproduct'); ?>">
                                    <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_product') ?>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>

                        <form method="post" name="deleteAllproducts" id="deleteAllproducts" action="<?php echo base_url('admincontrol/deleteAllproducts'); ?>">
                            <div class="product-empty-ajax text-center py-5 empty-div d-none">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <i class="bi product-empty-icon display-1 text-muted mb-3"></i>
                                    <h4 class="text-muted mb-2 product-empty-title"></h4>
                                    <p class="text-muted product-empty-desc"></p>
                                    <a class="btn mt-3 product-empty-btn d-none" href="#"></a>
                                </div>
                            </div>
                            <div class="product-table-container table-responsive">
                                <style>
                                .product-row {
                                    border-bottom: 1px solid #e9ecef;
                                    transition: all 0.2s ease;
                                }
                                .product-row:hover {
                                    background-color: #f8f9fa;
                                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                                }
                                .product-row td {
                                    vertical-align: middle;
                                    border-top: none;
                                }
                                .table > :not(caption) > * > * {
                                    padding: 0.75rem 0.5rem;
                                }
                                </style>
                                <table id="tech-companies-1" class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 40px;">
                                            <div class="form-check">
                                                <input name="product[]" type="checkbox" class="form-check-input" value="" onclick="checkAll(this)">
                                            </div>
                                        </th>
                                        <th style="width: 300px;"><?= __('admin.product_info') ?></th>
                                        <th class="text-center" style="width: 100px;"><?= __('admin.vendor') ?></th>
                                        <th class="text-center" style="width: 90px;"><?= __('admin.price') ?></th>
                                        <th class="text-center" style="width: 100px;"><?= __('admin.sku') ?></th>
                                        <th class="text-center" style="width: 120px;"><?= __('admin.commission') ?></th>
                                        <th class="text-center" style="width: 100px;"><?= __('admin.sales') ?></th>
                                        <th class="text-center" style="width: 100px;"><?= __('admin.clicks') ?></th>
                                        <th class="text-center" style="width: 80px;"><?= __('admin.total') ?></th>
                                        <th class="text-center" style="width: 100px;"><?= __('admin.status') ?></th>
                                        <th class="text-center" style="width: 140px;"><?= __('admin.action') ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="12" class="text-center py-3">
                                            <ul class="pagination pagination-td justify-content-center mb-0"></ul>
                                        </td>
                                    </tr>
                                </tfoot>
                                </table>
                            </div>
                        </form>
                        <?php } ?>
                  </div>
               </div>
               <div role="tabpanel" class="tab-pane" id="product_coupons_tab">
                  <div class="px-4 pb-4">
                     <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="mb-0 fw-semibold text-dark">
                           <i class="bi bi-ticket-perforated me-2"></i><?= __('admin.product_coupons') ?>
                        </h6>
                        <a class="btn btn-primary btn-sm" href="<?= base_url('admincontrol/coupon_manage/')  ?>">
                           <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new'); ?>
                        </a>
                     </div>
                     <?php if ($coupons == null) {?>
                     <div class="text-center py-5">
                         <div class="d-flex justify-content-center align-items-center flex-column">
                             <i class="bi bi-ticket-perforated display-1 text-muted mb-3"></i>
                             <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                             <p class="text-muted">Create your first coupon to get started</p>
                             <a class="btn btn-primary mt-3" href="<?= base_url('admincontrol/coupon_manage/') ?>">
                                 <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new') ?>
                             </a>
                         </div>
                     </div>
                     <?php }else {?>

                     <div class="card shadow-sm">
                        <div class="card-body p-0">
                           <style>
                           .coupon-row {
                              border-bottom: 1px solid #e9ecef;
                              transition: all 0.2s ease;
                           }
                           .coupon-row:hover {
                              background-color: #f8f9fa;
                              box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                           }
                           .coupon-row td {
                              vertical-align: middle;
                              border-top: none;
                           }
                           </style>
                           <div class="table-responsive">
                              <table id="table-coupons" class="table table-hover align-middle mb-0">
                                 <thead class="table-light">
                                    <tr>
                                       <th class="fw-semibold"><?= __('admin.coupon_name'); ?></th>
                                       <th class="fw-semibold text-center"><?= __('admin.count_product_use'); ?></th>
                                       <th class="fw-semibold text-center"><?= __('admin.uses_total'); ?></th>
                                       <th class="fw-semibold"><?= __('admin.code'); ?></th>
                                       <th class="fw-semibold text-center"><?= __('admin.discount'); ?></th>
                                       <th class="fw-semibold"><?= __('admin.date_start'); ?></th>
                                       <th class="fw-semibold"><?= __('admin.date_end'); ?></th>
                                       <th class="fw-semibold text-center"><?= __("admin.status") ?></th>
                                       <th class="fw-semibold text-center"><?= __("admin.actions") ?></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php foreach($coupons as $coupon){ ?>
                                    <tr class="coupon-row">
                                       <td class="align-middle">
                                          <div class="fw-semibold text-dark"><?= $coupon['name'] ?></div>
                                       </td>
                                       <td class="text-center align-middle">
                                          <div class="fw-bold text-primary"><?= (int)$coupon['product_count'] ?></div>
                                          <small class="text-muted">/ <?= (int)$coupon['count_coupon'] ?></small>
                                       </td>
                                       <td class="text-center align-middle">
                                          <span class="badge bg-info text-white"><?= $coupon['uses_total'] ?></span>
                                       </td>
                                       <td class="align-middle">
                                          <code class="bg-light px-2 py-1 rounded"><?= $coupon['code'] ?></code>
                                       </td>
                                       <td class="text-center align-middle">
                                          <span class="fw-bold text-success">
                                             <?= $coupon['type']=="P" ? getDecimalNumberFormat($coupon['discount'],$_SESSION['userDecimalPlace']).' %' : c_format($coupon['discount']) ?>
                                          </span>
                                       </td>
                                       <td class="align-middle">
                                          <small class="text-muted"><?= dateGlobalFormat($coupon['date_start']) ?></small>
                                       </td>
                                       <td class="align-middle">
                                          <small class="text-muted"><?= dateGlobalFormat($coupon['date_end']) ?></small>
                                       </td>
                                       <td class="text-center align-middle">
                                          <?php if($coupon['status'] == '1') { ?>
                                             <span class="badge bg-success rounded-pill"><?= __("admin.enabled") ?></span>
                                          <?php } else { ?>
                                             <span class="badge bg-secondary rounded-pill"><?= __("admin.disabled") ?></span>
                                          <?php } ?>
                                       </td>
                                       <td class="text-center align-middle">
                                          <div class="btn-group btn-group-sm" role="group">
                                             <a href="<?= base_url('admincontrol/coupon_manage/'.$coupon['coupon_id'])  ?>" class="btn btn-outline-primary edit-button" id="<?= $coupon['id'] ?>">
                                                <i class="bi bi-pencil-square"></i>
                                             </a>
                                             <a href="<?= base_url('admincontrol/coupon_delete/'.$coupon['coupon_id'])  ?>" class="btn btn-outline-danger delete-button" id="<?= $coupon['id'] ?>">
                                                <i class="bi bi-trash"></i>
                                             </a>
                                          </div>
                                       </td>
                                    </tr>
                                    <?php } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
               <div role="tabpanel" class="tab-pane" id="form_tab">
                  <div class="px-4 pb-4">
                     <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="mb-0 fw-semibold text-dark">
                           <i class="bi bi-wpforms me-2"></i><?= __('admin.forms') ?>
                        </h6>
                        <div class="d-flex gap-2">
                           <button style="display:none;" type="button" class="btn btn-outline-danger btn-sm" name="deletebuttonform" id="deletebuttonform" onclick="deleteformfunc('deleteAllforms');"><?= __('admin.delete_products') ?></button>
                           <a class="btn btn-primary btn-sm" href="<?= base_url('admincontrol/form_manage/')  ?>">
                              <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new'); ?>
                           </a>
                        </div>
                     </div>
                     
                      <?php if (empty($forms)) {?>
                     <div class="text-center py-5 px-3">
                         <div class="d-flex justify-content-center align-items-center flex-column">
                             <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                 <i class="bi bi-file-earmark-post display-4 text-muted"></i>
                             </div>
                             <h5 class="fw-semibold text-dark mb-2"><?= __('admin.no_forms') ?></h5>
                             <p class="text-muted mb-4"><?= __('admin.no_data_found') ?></p>
                             <a class="btn btn-primary" href="<?= base_url('admincontrol/form_manage/') ?>">
                                 <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new') ?>
                             </a>
                         </div>
                     </div>
                           <?php } else { ?>
                        <div class="card border-0 shadow-sm overflow-hidden">
                           <div class="card-body p-0">
                              <form method="post" name="deleteAllforms" id="deleteAllforms" action="<?= base_url('admincontrol/deleteAllforms') ?>">
                                 <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 table-striped">
                                       <thead class="table-light border-bottom-0">
                                          <tr>
                                             <th class="text-center py-3" style="width: 44px;"><input name="checkbox[]" type="checkbox" class="form-check-input" value="" onclick="checkAllForm(this)" aria-label="<?= __('admin.select_all') ?>"></th>
                                             <th class="fw-semibold py-3"><?= __('admin.form_title') ?></th>
                                             <th class="fw-semibold py-3"><?= __('admin.vendor') ?></th>
                                             <th class="fw-semibold py-3"><?= __('admin.coupon_code') ?></th>
                                             <th class="fw-semibold text-center py-3"><?= __('admin.coupon_use') ?></th>
                                             <th class="fw-semibold text-center py-3"><?= __('admin.sales_commission') ?></th>
                                             <th class="fw-semibold text-center py-3"><?= __('admin.clicks_commission') ?></th>
                                             <th class="fw-semibold text-center py-3"><?= __('admin.total_commission') ?></th>
                                             <th class="fw-semibold text-center py-3"><?= __('admin.status') ?></th>
                                             <th class="fw-semibold text-center py-3" style="width: 100px;"><?= __('admin.actions') ?></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <?php $form_setting = $this->Product_model->getSettings('formsetting'); ?>
                                          <?php foreach($forms as $form){ ?>
                                             <tr class="align-middle">
                                                <td class="text-center">
                                                   <input name="checkbox[]" type="checkbox" class="form-check-input" id="check<?= $form['form_id'] ?>" value="<?= $form['form_id'] ?>" onclick="checkonly(this,'check<?= $form['form_id'] ?>')">
                                                </td>
                                                <td>
                                                   <div class="fw-semibold text-dark mb-1"><?= htmlspecialchars($form['title']) ?></div>
                                                   <a href="<?= $form['public_page'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                      <i class="bi bi-box-arrow-up-right me-1"></i><?= __('admin.public_page') ?>
                                                   </a>
                                                   <?php if(!empty($form['form_recursion_type'])){ ?>
                                                      <?php if($form['form_recursion_type']=='custom'){ ?>
                                                         <?php 
                                                            $recVal = ($form['form_recursion']??'')!='custom_time' 
                                                                ? (($form['form_recursion']??'') ? __('admin.'.($form['form_recursion'])) : __('admin.life_time')) 
                                                                : timetosting($form['recursion_custom_time']??0); 
                                                         ?>
                                                         <div class="mt-1"><small class="text-muted"><i class="bi bi-arrow-repeat me-1"></i><b><?= __("admin.recurring") ?></b>: <?= $recVal ?></small></div>
                                                      <?php } else { ?>
                                                         <?php 
                                                            $fsRec = $form_setting['form_recursion']??'';
                                                            $recVal = ($fsRec=='custom_time') 
                                                                ? timetosting($form_setting['recursion_custom_time']??0) 
                                                                : ($fsRec ? __('admin.'.$fsRec) : __('admin.life_time')); 
                                                         ?>
                                                         <div class="mt-1"><small class="text-muted"><i class="bi bi-arrow-repeat me-1"></i><b><?= __("admin.recurring") ?></b>: <?= $recVal ?></small></div>
                                                      <?php } ?>
                                                   <?php } ?>
                                                </td>
                                                <td>
                                                   <span class="badge bg-info"><?= !empty($form['firstname']) ? htmlspecialchars($form['firstname'].' '.$form['lastname']) : __("admin.admin") ?></span>
                                                </td>
                                                <td>
                                                   <?php if(!empty($form['coupon_code'])){ ?>
                                                      <code class="bg-light px-2 py-1 rounded text-dark small"><?= htmlspecialchars($form['coupon_code']) ?></code>
                                                   <?php } else { ?>
                                                      <span class="text-muted small">—</span>
                                                   <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                   <div class="fw-bold text-primary"><?= (int)$form['count_coupon'] ?></div>
                                                   <small class="text-muted d-block"><?= !empty($form['coupon_name']) ? htmlspecialchars($form['coupon_name']) : '—' ?></small>
                                                </td>
                                                <td class="text-center">
                                                   <div class="fw-bold text-success"><?= c_format($form['total_commission']) ?></div>
                                                   <small class="text-muted d-block"><?= (int)$form['count_commission'] ?> <?= __('admin.sales') ?></small>
                                                   <?php $ordercountratio = ($form['view_statistics']>0) ? ($form['count_commission']*100/$form['view_statistics']) : 0; ?>
                                                   <small class="text-muted d-block"><?= (int)$form['view_statistics'] ?> <?= __('admin.views') ?> / <?= is_float($ordercountratio) ? number_format((float)$ordercountratio, 2, '.', '') : $ordercountratio ?>%</small>
                                                </td>
                                                <td class="text-center">
                                                   <div class="fw-bold text-warning"><?= c_format($form['commition_click']) ?></div>
                                                   <small class="text-muted d-block"><?= (int)$form['commition_click_count'] ?> <?= __('admin.clicks') ?></small>
                                                   <?php $clickratio = ($form['view_statistics']>0) ? ((int)$form['commition_click_count']*100/$form['view_statistics']) : 0; ?>
                                                   <small class="text-muted d-block"><?= (int)$form['view_statistics'] ?> <?= __('admin.views') ?> / <?= is_float($clickratio) ? number_format((float)$clickratio, 2, '.', '') : $clickratio ?>%</small>
                                                </td>
                                                <td class="text-center">
                                                   <div class="fw-bold text-primary fs-5"><?= c_format($form['total_commission']+$form['commition_click']) ?></div>
                                                </td>
                                                <td class="text-center"><?= form_status($form['status']) ?></td>
                                                <td class="text-center">
                                                   <div class="btn-group btn-group-sm" role="group">
                                                      <a href="<?= base_url('admincontrol/form_manage/'.$form['form_id']) ?>" class="btn btn-outline-primary" title="<?= __('admin.edit') ?>">
                                                         <i class="bi bi-pencil-square"></i>
                                                      </a>
                                                      <button type="button" data-href="<?= base_url('admincontrol/form_delete/'.$form['form_id']) ?>" class="btn btn-outline-danger delete-form-button" title="<?= __('admin.delete') ?>">
                                                         <i class="bi bi-trash"></i>
                                                      </button>
                                                   </div>
                                                </td>
                                             </tr>
                                          <?php } ?>
                                       </tbody>
                                    </table>
                                 </div>
                              </form>
                           </div>
                        </div>
                     <?php } ?>
                  </div>
               </div>
               <div role="tabpanel" class="tab-pane" id="form_coupons_tab">
                  <div class="px-4 pb-4">
                     <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="mb-0 fw-semibold text-dark">
                           <i class="bi bi-ticket-perforated me-2"></i><?= __('admin.form_coupons') ?>
                        </h6>
                        <a class="btn btn-primary btn-sm" href="<?= base_url('admincontrol/form_coupon_manage/')  ?>">
                           <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new'); ?>
                        </a>
                     </div>
                     <?php if ($form_coupons == null) {?>
                     <div class="text-center py-5">
                         <div class="d-flex justify-content-center align-items-center flex-column">
                             <i class="bi bi-ticket-perforated display-1 text-muted mb-3"></i>
                             <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                             <p class="text-muted">Create your first form coupon to get started</p>
                             <a class="btn btn-primary mt-3" href="<?= base_url('admincontrol/form_coupon_manage/') ?>">
                                 <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new') ?>
                             </a>
                         </div>
                     </div>
                             <?php }else {?>
                                <div class="card shadow-sm">
                                   <div class="card-body p-0">
                                      <style>
                                      .form-coupon-row {
                                         border-bottom: 1px solid #e9ecef;
                                         transition: all 0.2s ease;
                                      }
                                      .form-coupon-row:hover {
                                         background-color: #f8f9fa;
                                         box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                                      }
                                      .form-coupon-row td {
                                         vertical-align: middle;
                                         border-top: none;
                                      }
                                      </style>
                                      <div class="table-responsive">
                                         <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                               <tr>
                                                  <th class="fw-semibold"><?= __('admin.form_coupon_name'); ?></th>
                                                  <th class="fw-semibold"><?= __('admin.code'); ?></th>
                                                  <th class="fw-semibold text-center"><?= __('admin.discount'); ?></th>
                                                  <th class="fw-semibold"><?= __('admin.date_start'); ?></th>
                                                  <th class="fw-semibold"><?= __('admin.date_end'); ?></th>
                                                  <th class="fw-semibold text-center"><?= __("admin.status") ?></th>
                                                  <th class="fw-semibold text-center"><?= __("admin.actions") ?></th>
                                               </tr>
                                            </thead>
                                            <tbody>
                                               <?php foreach($form_coupons as $form_coupon){ ?>
                                                  <tr class="form-coupon-row">
                                                     <td class="align-middle">
                                                        <div class="fw-semibold text-dark"><?= $form_coupon['name'] ?></div>
                                                     </td>
                                                     <td class="align-middle">
                                                        <code class="bg-light px-2 py-1 rounded"><?= $form_coupon['code'] ?></code>
                                                     </td>
                                                     <td class="text-center align-middle">
                                                        <span class="fw-bold text-success">
                                                           <?= $form_coupon['type']=="P" ? getDecimalNumberFormat($form_coupon['discount'],$_SESSION['userDecimalPlace']).' %' : c_format($form_coupon['discount']) ?>
                                                        </span>
                                                     </td>
                                                     <td class="align-middle">
                                                        <small class="text-muted"><?= dateGlobalFormat($form_coupon['date_start']) ?></small>
                                                     </td>
                                                     <td class="align-middle">
                                                        <small class="text-muted"><?= dateGlobalFormat($form_coupon['date_end']) ?></small>
                                                     </td>
                                                     <td class="text-center align-middle">
                                                        <?php if($form_coupon['status'] == '1') { ?>
                                                           <span class="badge bg-success rounded-pill"><?= __("admin.enabled") ?></span>
                                                        <?php } else { ?>
                                                           <span class="badge bg-secondary rounded-pill"><?= __("admin.disabled") ?></span>
                                                        <?php } ?>
                                                     </td>
                                                     <td class="text-center align-middle">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                           <a href="<?= base_url('admincontrol/form_coupon_manage/'.$form_coupon['form_coupon_id'])  ?>" class="btn btn-outline-primary edit-button" id="<?= $form_coupon['form_coupon_id'] ?>">
                                                              <i class="bi bi-pencil-square"></i>
                                                           </a>
                                                           <button data-href="<?= base_url('admincontrol/form_coupon_delete/'.$form_coupon['form_coupon_id'])  ?>" class="btn btn-outline-danger delete-form-button" id="<?= $form_coupon['form_coupon_id'] ?>">
                                                              <i class="bi bi-trash"></i>
                                                           </button>
                                                        </div>
                                                     </td>
                                                  </tr>
                                               <?php } ?>
                                            </tbody>
                                         </table>
                                      </div>
                                   </div>
                                </div>
                             <?php } ?>
                  </div>
               </div>
               <div role="tabpanel" class="tab-pane" id="review_tab">
                  <div class="px-4 pb-4">
                     <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="mb-0 fw-semibold text-dark">
                           <i class="bi bi-star me-2"></i><?= __('admin.reviews') ?>
                        </h6>
                        <div class="d-flex gap-2">
                           <a class="btn btn-primary btn-sm" href="<?= base_url('admincontrol/manage_review/')  ?>">
                              <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new'); ?>
                           </a>
                           <a class="btn btn-outline-primary btn-sm" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#manageBulkReviews">
                              <i class="bi bi-gear me-1"></i><?= __('user.manage_bulk_reviews') ?>
                           </a>
                        </div>
                     </div>
                     
                     <div class="card shadow-sm mb-4">
                        <div class="card-body">
                           <form id="filter-form-review">
                              <div class="row g-3 align-items-end">
                                 <div class="col-md-4">
                                    <label class="form-label fw-medium"><?= __('admin.filter') ?> <?= __('admin.product_name') ?></label>
                                    <select id="product_name_review" name="product_name_review" class="form-select">
                                       <option value=""><?= __('admin.all_product') ?></option>
                                       <?php if(isset($productlist)){ ?>
                                          <?php foreach ($productlist as $key => $product) { ?>
                                             <option value="<?= $product['product_id'] ?>" <?php echo  $review['products_id']==$product['product_id'] ? 'selected' : ''?> ><?=$product['product_name']; ?></option> 
                                          <?php } ?>
                                       <?php } ?>
                                    </select>   
                                 </div> 
                                 <div class="col-md-3">
                                    <div class="d-flex align-items-center h-100">
                                       <button type="button" class="btn btn-outline-info btn-sm">
                                          <i class="bi bi-star me-1"></i><?= __('admin.total_review') ?>: <span id="total_review" class="badge bg-info ms-1">0</span>
                                       </button>
                                    </div>
                                 </div>
                                 <div class="col-md-5">
                                 </div> 
                              </div>
                           </form>
                        </div>
                     </div>
                     <div class="card shadow-sm">
                        <div class="card-body p-0">
                           <style>
                           .review-row {
                              border-bottom: 1px solid #e9ecef;
                              transition: all 0.2s ease;
                           }
                           .review-row:hover {
                              background-color: #f8f9fa;
                              box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                           }
                           .review-row td {
                              vertical-align: middle;
                              border-top: none;
                           }
                           </style>
                           <div class="table-responsive">
                              <table id="table-review" class="table table-hover align-middle mb-0">
                                 <thead class="table-light">
                                    <tr>
                                       <th class="fw-semibold text-center" style="width: 80px;"><?= __('admin.image'); ?></th>
                                       <th class="fw-semibold"><?= __('admin.customer'); ?></th>
                                       <th class="fw-semibold"><?= __('admin.product_name'); ?></th>
                                       <th class="fw-semibold"><?= __('admin.review'); ?></th>
                                       <th class="fw-semibold text-center"><?= __('admin.rating'); ?></th>
                                       <th class="fw-semibold"><?= __('admin.datetime'); ?></th> 
                                       <th class="fw-semibold text-center"><?= __("admin.actions") ?></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    
                                 </tbody>
                                 <tfoot>
                                    <tr>
                                       <td colspan="7" class="text-center py-3">
                                          <ul class="pagination pagination-td justify-content-center mb-0"></ul>
                                       </td>
                                    </tr>
                                 </tfoot>
                              </table>
                           </div>
                        </div>
                     </div>
 
<div id="manageBulkReviews" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-gear me-2"></i><?= __('admin.manage_bulk_reviews') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <button class="btn btn-outline-success w-100 export-reviews-xml-btn">
                                    <i class="bi bi-download me-2"></i><?= __('admin.export_reviews_xml') ?>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-outline-info w-100 export-reviews-structure-xml-btn">
                                    <i class="bi bi-file-earmark-code me-2"></i><?= __('admin.export_structure_xml_only') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-upload me-2"></i><?= __('admin.import_reviews') ?>
                </h6>
                <ul class="nav nav-pills nav-fill mb-4" id="TabsNav">
                    <li class="nav-item">
                        <a class="nav-link active" id="import-review-file-tab" data-bs-toggle="tab" href="#import_review_file_tab_" role="tab" aria-controls="import_review_file_tab_" aria-selected="true">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i><?= __('admin.import_from_file') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="import-review-link-tab" data-bs-toggle="tab" href="#import_review_link_tab" role="tab" aria-controls="import_review_link_tab" aria-selected="false">
                            <i class="bi bi-link-45deg me-1"></i><?= __('admin.import_from_url') ?>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="import_review_file_tab_" role="tabpanel" aria-labelledby="import-review-file-tab">
                        <form id="bulk_reviews_form">
                            <div class="mb-3">
                                <label for="customFile" class="form-label fw-medium"><?= __('admin.upload_xml_file_for_bulk_review_manage') ?></label>
                                <input type="file" class="form-control" name="file" id="customFile" accept=".xml">
                            </div>
                            <div class="text-center">
                                <button id="bulk_reviews_form_btn" type="submit" class="btn btn-success">
                                    <i class="bi bi-upload me-1"></i><?= __('admin.import_reviews') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="import_review_link_tab" role="tabpanel" aria-labelledby="import-review-link-tab">
                        <form id="bulk_reviews_form_url">
                            <div class="mb-3">
                                <label for="txt_review_xmlurl" class="form-label fw-medium"><?= __('admin.enter_xml_url_for_bulk_review_manage') ?></label>
                                <input name="txt_review_xmlurl" id="txt_review_xmlurl" class="form-control" type="url" placeholder="https://example.com/reviews.xml">
                            </div>
                            <div class="text-center">
                                <button id="bulk_reviews_form_url_btn" type="submit" class="btn btn-success">
                                    <i class="bi bi-upload me-1"></i><?= __('admin.import_reviews') ?>
                                </button>
                            </div>
                        </form>
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
    </div>
</div>

</div>
</div>
</div>

<div id="manageBulkReviewsConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><?= __('admin.manage_bulk_reviews_confirmation') ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="max-height:350px; overflow-y:auto;">
            <!-- Content goes here -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-lg btn-success text-center import-reviews-confirm"><?= __('admin.confirm_product_image') ?></button>
            <button class="btn btn-lg btn-secondary text-center" data-bs-dismiss="modal"><?= __('admin.cancel') ?></button>
        </div>
    </div>
  </div>
</div>


<div id="manageBulkReviewsResult" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><?= __('admin.manage_bulk_reviews_result') ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="max-height:350px; overflow-y:auto;">
            <!-- Content goes here -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-lg btn-success text-center" onclick="window.location.reload()"><?= __('admin.ok') ?></button>
        </div>
    </div>
  </div>
</div>


<?= $social_share_modal ?>
<script type="text/javascript" async="">

    $('.product_tab_option').on('click', function(){
        $(".product-options").show();
    });

    $('.product_coupons_tab_option').on('click', function(){
        $(".product-options").hide();
    });

    function openProductTabFromHash(){
        var h = window.location.hash;
        if(!h) return;
        var a = document.querySelector('#TabsNav a[href="' + h + '"]');
        if(a) a.click();
    }
    window.addEventListener('load', openProductTabFromHash);
    window.addEventListener('hashchange', openProductTabFromHash);
   
    $temp_import_product_data = null;
   
    $('#bulk_products_form_btn').on('click', function(e){ 
        e.preventDefault();
        $("#bulk_products_form .alert-danger").remove();
        if($('#bulk_products_form input[name="file"]').val()) {
            $this = $(this);
            var fd = new FormData(document.getElementById("bulk_products_form"));
   
            $.ajax({
                url: '<?= base_url('admincontrol/bulkProductImport'); ?>',  
                type: 'POST',
                data: fd,
                dataType: 'html',
                beforeSend:function(){$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){ 
                    $this.prop('disabled', false).html($this.data('original-text') || 'Submit');
                    $('#manageBulkProducts').modal('hide');
                },
                success:function(response){               
                    $('#manageBulkProductsConfirmation .modal-body').html(response);
                    $('#manageBulkProductsConfirmation').modal('show');
   
                    if(! $('#manageBulkProductsConfirmation textarea[name="product_for_import"]').length > 0) {
                        $('#manageBulkProductsConfirmation .import-products-confirm').hide();  
                    } else {
                        $('#manageBulkProductsConfirmation .import-products-confirm').show();  
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });   
        } else {
           $("#bulk_products_form .custom-file").after('<div class="alert alert-danger"><?= __('admin.please_select_excel_file') ?></div>');
        }
    });

    $('#bulk_products_form_url_btn').on('click', function(e){ 
        e.preventDefault();
        $("#bulk_products_form_url .alert-danger").remove();
        if($('#txt_xmlurl').val()!="") 
        {
            $this = $(this);
            var fd = new FormData(document.getElementById("bulk_products_form_url"));
   
            $.ajax({
                url: '<?= base_url('admincontrol/bulkProductImportFromUrl'); ?>',  
                type: 'POST',
                data: fd,
                dataType: 'html',
                beforeSend:function(){$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){ 
                    $this.prop('disabled', false).html($this.data('original-text') || 'Submit');
                    $('#manageBulkProducts').modal('hide');
                },
                success:function(response){               
                    $('#manageBulkProductsConfirmation .modal-body').html(response);
                    $('#manageBulkProductsConfirmation').modal('show');
   
                    if(! $('#manageBulkProductsConfirmation textarea[name="product_for_import"]').length > 0) {
                        $('#manageBulkProductsConfirmation .import-products-confirm').hide();  
                    } else {
                        $('#manageBulkProductsConfirmation .import-products-confirm').show();  
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });   
        } else {
           $("#bulk_products_form_url .custom-file").after('<div class="alert alert-danger"><?= __('admin.please_enter_xml_url') ?></div>');
        }
    });

    $('#manageBulkProductsConfirmation .import-products-confirm').on('click', function(e){
        e.preventDefault();
        if($('#manageBulkProductsConfirmation textarea[name="product_for_import"]').val()) {
            $this = $(this);
            var data = new FormData();
            data.append( 'products', $('#manageBulkProductsConfirmation textarea[name="product_for_import"]').val());
            $.ajax({
                url: '<?= base_url('admincontrol/bulkProductImportConfirm'); ?>',  
                type: 'POST',
                data: data,
                beforeSend:function(){$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){
                    $this.prop('disabled', false).html($this.data('original-text') || 'Submit');
                    $('#manageBulkProductsConfirmation').modal('hide');
                },
                success:function(response){               
                    $('#manageBulkProductsResult .modal-body').html(response);
                    $('#manageBulkProductsResult').modal('show');
                },
                cache: false,
                contentType: false,
                processData: false
            });   
        } else {
            $("#bulk_products_form .custom-file").after('<div class="alert alert-danger"><?= __('admin.please_select_excel_file') ?></div>');
        }
    });
   
    $(".show-more").on('click',function(){
        $(this).parents("tfoot").remove();
        $("#product-list tr.d-none").hide().removeClass('d-none').fadeIn();
    });
   
    $(".delete-button").on('click',function(){
        if(!confirm("<?= __('admin.are_you_sure') ?>")) return false;
    });

    $(document).ready(function(){
      $('.delete-form-button').on('click',function(){
         var el = this;
         window.confirmDelete("<?= __("admin.delete_form_confirmation") ?>", function(){
            location = $(el).data("href");
         });
         return false;
      })
    })

    $(".toggle-child-tr").on('click',function(){
        $tr = $(this).parents("tr");
        $ntr = $tr.next("tr.detail-tr");
       
        if($ntr.css("display") == 'table-row'){
            $ntr.hide();
            $(this).find("i").attr("class","fa fa-plus");
        }else{
            $(this).find("i").attr("class","fa fa-minus");
            $ntr.show();
        }
    })
   
    function checkAll(bx) {
        var cbs = document.getElementsByTagName('input');
            if(bx.checked)
        {
            document.getElementById('deletebutton').style.display = 'block';
        } else {
            document.getElementById('deletebutton').style.display = 'none';
        }
        for(var i=0; i < cbs.length; i++) {
            if(cbs[i].type == 'checkbox') {
                cbs[i].checked = bx.checked;
            }
        }
    }
    
    function checkAllForm(bx) {
      var cbs = document.getElementsByTagName('input');
      if(bx.checked)
      {
         document.getElementById('deletebuttonform').style.display = 'block';
         } else {
         document.getElementById('deletebuttonform').style.display = 'none';
      }
      for(var i=0; i < cbs.length; i++) {
         if(cbs[i].type == 'checkbox') {
            cbs[i].checked = bx.checked;
         }
      }
    }

    function checkonly(bx,checkid) {
        if($(".list-checkbox:checked").length){
            $('#deletebutton').show();
        } else {
            $('#deletebutton').hide();
        }
    }
   
    function deleteuserlistfunc(formId){
        if(! confirm("<?= __('admin.are_you_sure') ?>")) return false;
   
        $('#'+formId).submit();
    }
   
    function deleteformfunc(formId){
      if(! confirm("<?= __('admin.are_you_sure') ?>")) return false;

      $('#'+formId).submit();
    }

    // Store the review mode for AJAX calls
    // URL format: listproduct_ajax/{only_review}/{page}
    var onlyReview = '<?= isset($only_review) && $only_review ? $only_review : "all" ?>';
    var ajaxBaseUrl = '<?= base_url("admincontrol/listproduct_ajax/") ?>' + onlyReview + '/';
    var productEmptyLabels = {
        pendingTitle: <?= json_encode(__("admin.no_products_pending_review")) ?>,
        pendingDesc: <?= json_encode(__("admin.vendor_products_pending_will_appear_here")) ?>,
        allTitle: <?= json_encode(__("admin.no_data_found")) ?>,
        allDesc: <?= json_encode(__("admin.add_product_to_get_started")) ?>,
        viewAll: <?= json_encode(__("admin.view_all_products")) ?>,
        addProduct: <?= json_encode(__("admin.add_product")) ?>
    };
    
    $("#filter-form").on("submit",function(){
        getPage(ajaxBaseUrl + '1', null);
        return false;
    })

    $(".select-category, .select-vendor").on("change",function(){
        $("#filter-form").submit();
    })
   
    var isLoadingProducts = false;
    function getPage(url, $triggerElement){
       if(isLoadingProducts) return false;
       
       var category_id = $('.select-category').find(":selected").val();
       var seller_id = $('.select-vendor').find(":selected").val();
       
       isLoadingProducts = true;
       $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            data:$("#filter-form").serialize(),
            beforeSend:function(){
                if($triggerElement && $triggerElement.length) {
                    $triggerElement.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
                }
            },
            complete:function(){
                isLoadingProducts = false;
                if($triggerElement && $triggerElement.length) {
                    $triggerElement.prop('disabled', false).html($triggerElement.data('original-text') || 'Submit');
                }
            },
            success:function(json){
               if(json['view']){
                  $("#tech-companies-1 tbody").html(json['view']);
                  $("#tech-companies-1").show();
                  $(".product-empty-ajax").addClass("d-none");
               } else {
                  var $empty = $(".product-empty-ajax");
                  var isReview = onlyReview === 'reviews';
                  $empty.find(".product-empty-icon").removeClass("bi-box-seam bi-clock-history").addClass(isReview ? "bi-clock-history" : "bi-box-seam");
                  $empty.find(".product-empty-title").text(isReview ? productEmptyLabels.pendingTitle : productEmptyLabels.allTitle);
                  $empty.find(".product-empty-desc").text(isReview ? productEmptyLabels.pendingDesc : productEmptyLabels.allDesc);
                  var $btn = $empty.find(".product-empty-btn");
                  if (isReview) {
                    $btn.removeClass("d-none btn-primary").addClass("btn-outline-primary").attr("href", "<?= base_url('admincontrol/listproduct') ?>").html('<i class="bi bi-box-seam me-1"></i>' + productEmptyLabels.viewAll);
                  } else {
                    $btn.removeClass("d-none btn-outline-primary").addClass("btn-primary").attr("href", "<?= base_url('admincontrol/addproduct') ?>").html('<i class="bi bi-plus-circle me-1"></i>' + productEmptyLabels.addProduct);
                  }
                  $empty.removeClass("d-none");
                  $("#tech-companies-1").hide();
               }
        
               $("#tech-companies-1 .pagination-td").html(json['pagination']);
            },
            error:function(xhr, status, error){
                console.error('Product loading error:', error);
                isLoadingProducts = false;
                if(typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_products') ?>', 'error', 5000);
                }
            }
       });
    }
   
    $(document).on('click', '.export-products-btn', function() {
        exportProducts($(this), 0);
    });
   
    $(document).on('click', '.export-structure-btn', function() {
        exportProducts($(this), 1);
    });

    $(document).on('click', '.export-products-xml-btn', function() {
        exportproductXML($(this), 0);
    });
   
    $(document).on('click', '.export-structure-xml-btn', function() {
        exportproductXML($(this), 1);
    });
      
   
    function exportProducts(thatBtn, structure_only  = 0) {
        $.ajax({
            url:'<?= base_url("admincontrol/exportproduct/") ?>',
            type:'POST',
            dataType:'json',
            data:{structure_only:structure_only},
                beforeSend:function(){thatBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){thatBtn.prop('disabled', false).html(thatBtn.data('original-text') || 'Submit');},
                success:function(json){
            if(json['download']){
                window.location.href = json['download'];
            }
        },
        });
    }

    function exportproductXML(thatBtn, structure_only  = 0) {
        $.ajax({
            url:'<?= base_url("admincontrol/exportproductXML/") ?>',
            type:'POST',
            dataType:'json',
            data:{structure_only:structure_only},
                beforeSend:function(){thatBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){thatBtn.prop('disabled', false).html(thatBtn.data('original-text') || 'Submit');},
                success:function(json){
            if(json['download'])
            {
               if(structure_only==0)
                  window.location.href="downloadprodcutxmlfile/"; 
               else
                 window.location.href="downloadprodcutxmlstructurefile/";   
   
            }
        },
        });
    }
     
   
    getPage(ajaxBaseUrl + '1', null);
        $("#tech-companies-1 .pagination-td").delegate("a","click",function(){
        getPage($(this).attr("href"), $(this));
        return false;
    })
   
    function closePopup(){
       $('.popupbox').hide();
       $('#overlay').hide();
    }
   function generateCode(affiliate_id){
        $('.popupbox').show();
        $('#overlay').show();
        $('.modalpopup-body').load('<?php echo base_url();?>admincontrol/generateproductcode/'+affiliate_id);
        $('.popupbox').ready(function () {
            $('.backdrop, .box').animate({
            'opacity': '.50'
            }, 300, 'linear');
            $('.box').animate({
                'opacity': '1.00'
            }, 300, 'linear');
            $('.backdrop, .box').css('display', 'block');
        });
   }
   
   $(document).delegate(".delete-product",'click',function(){
       if(! confirm("<?= __('admin.are_you_sure') ?>")) return false;
       window.location = $("#deleteAllproducts").attr("action") + "?delete_id=" + $(this).attr("data-id");
   })

   $("#filter-form-review").on("submit",function(){
      var urlreview='<?= base_url("admincontrol/listreviews_ajax/")?>';
      getReviews(urlreview, null);
        return false;
    });

   $("#product_name_review").on("change",function(){
        $("#filter-form-review").submit();
    })

    $("#table-review .pagination-td").delegate("a","click",function(){
         getReviews($(this).attr("href"), $(this));
         return false;
    })

    var isLoadingReviews = false;
    function getReviews(url, $triggerElement)
    {
       if(isLoadingReviews) return false;
       
       isLoadingReviews = true;
       $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            data:$("#product_name_review").serialize(),
            beforeSend:function(){
                if($triggerElement && $triggerElement.length) {
                    $triggerElement.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
                }
            },
            complete:function(){
                isLoadingReviews = false;
                if($triggerElement && $triggerElement.length) {
                    $triggerElement.prop('disabled', false).html($triggerElement.data('original-text') || 'Submit');
                }
            },
            success:function(json){
               if(json['view']){
                  $("#table-review tbody").html(json['view']);
                  $("#total_review").text(json['total']);
                  $("#table-review").show();
               } else {
                  $("#table-review").hide();
               }
        
               $("#table-review .pagination-td").html(json['pagination']);
            },
            error:function(xhr, status, error){
                console.error('Reviews loading error:', error);
                isLoadingReviews = false;
                if(typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_load_reviews') ?>', 'error', 5000);
                }
            }
       });
    }
    $( document ).ready(function() {
       getReviews('<?= base_url("admincontrol/listreviews_ajax/")?>', null);
    });

    $('#bulk_reviews_form_url_btn').on('click', function(e){ 
        e.preventDefault();
 
        $("#bulk_reviews_form_url .alert-danger").remove();
        if($('#txt_review_xmlurl').val()!="") 
        {
            $this = $(this);
            var fd = new FormData(document.getElementById("bulk_reviews_form_url"));
   
            $.ajax({
                url: '<?= base_url('admincontrol/bulkReviewImportFromUrl'); ?>',  
                type: 'POST',
                data: fd,
                dataType: 'html',
                beforeSend:function(){$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){ 
                    $this.prop('disabled', false).html($this.data('original-text') || 'Submit');
                    $('#manageBulkReviews').modal('hide');
                },
                success:function(response){               
                    $('#manageBulkReviewsConfirmation .modal-body').html(response);
                    $('#manageBulkReviewsConfirmation').modal('show');
   
                    if(! $('#manageBulkReviewsConfirmation textarea[name="reviews_for_import"]').length > 0) {
                        $('#manageBulkReviewsConfirmation .import-reviews-confirm').hide();  
                    } else {
                        $('#manageBulkReviewsConfirmation .import-reviews-confirm').show();  
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });   
        } else {
           $("#bulk_reviews_form_url .custom-file").after('<div class="alert alert-danger"><?= __('user.please_enter_xml_url') ?></div>');
        }
    });

    $('#bulk_reviews_form_btn').on('click', function(e){ 
        e.preventDefault();
        $("#bulk_reviews_form .alert-danger").remove();
        if($('#bulk_reviews_form input[name="file"]').val()) {
            $this = $(this);
            var fd = new FormData(document.getElementById("bulk_reviews_form"));

            $.ajax({
                url: '<?= base_url('admincontrol/bulkReviewsImport'); ?>',  
                type: 'POST',
                data: fd,
                dataType: 'html',
                beforeSend:function(){$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){
                    $this.prop('disabled', false).html($this.data('original-text') || 'Submit');
                    $('#manageBulkReviews').modal('hide');
                },
                success:function(response){               
                    $('#manageBulkReviewsConfirmation .modal-body').html(response);
                    $('#manageBulkReviewsConfirmation').modal('show');
                    
                    if(! $('#manageBulkReviewsConfirmation textarea[name="reviews_for_import"]').length > 0) {
                     $('#manageBulkReviewsConfirmation .import-reviews-confirm').hide();  
                    } else {
                      $('#manageBulkReviewsConfirmation .import-reviews-confirm').show();  
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });   
        } else {
            $("#bulk_products_form .custom-file").after('<div class="alert alert-danger">'+'<?= __('user.please_select_xml_file_before_proceed') ?>'+'</div>');
        }
    });

    $('#manageBulkReviewsConfirmation .import-reviews-confirm').on('click', function(e){
        e.preventDefault(); 
        if($('#manageBulkReviewsConfirmation textarea[name="reviews_for_import"]').val()) {
             $this = $(this);
                var data = new FormData();
                data.append('reviews', $('#manageBulkReviewsConfirmation textarea[name="reviews_for_import"]').val());
            $.ajax({
                url: '<?= base_url('admincontrol/bulkReviewImportConfirm'); ?>',  
                type: 'POST',
                data: data,
                beforeSend:function(){$this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){
                    $this.prop('disabled', false).html($this.data('original-text') || 'Submit');
                    $('#manageBulkReviewsConfirmation').modal('hide');
                },
                success:function(response){               
                    $('#manageBulkReviewsResult .modal-body').html(response);
                    $('#manageBulkReviewsResult').modal('show');
                },
                cache: false,
                contentType: false,
                processData: false
            });   
        } else {
            $("#bulk_reviews_form .custom-file").after('<div class="alert alert-danger">'+'<?= __('user.please_select_xml_file_before_proceed') ?>'+'</div>');
        }
    });

    $(document).on('click', '.export-reviews-xml-btn', function() {
         exportReviewXML($(this), 0);
    });
    
    $(document).on('click', '.export-reviews-structure-xml-btn', function() {
         exportReviewXML($(this), 1);
    });
    
    function exportReviewXML(thatBtn, structure_only  = 0) {
        $.ajax({
            url:'<?= base_url("admincontrol/exportReviewXML/") ?>',
            type:'POST',
            dataType:'json',
            data:{structure_only:structure_only},
                beforeSend:function(){thatBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');},
                complete:function(){thatBtn.prop('disabled', false).html(thatBtn.data('original-text') || 'Submit');},
                success:function(json){
            if(json['download'])
            {
               if(structure_only==0)
                  window.location.href='<?= base_url('admincontrol/downloadproductreviewxmlfile'); ?>'; 
               else
                 window.location.href='<?= base_url('admincontrol/downloadproductreviewxmlstructurefile'); ?>';   
   
            }
        },
        });
    }

    /* ── Demo Products ─────────────────────────────────────────────────── */
    $('#btn-load-demo-products').on('click', function() {
        if (!confirm('<?= __('admin.confirm_load_demo_products') ?: 'Load 4 demo products (virtual, downloadable, video & recurring)?' ?>')) return;
        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?: 'Loading...' ?>');
        $.ajax({
            url: '<?= base_url('admincontrol/importDemoProducts') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?: 'Success' ?>', res.message, 'success', 4000);
                    } else {
                        alert(res.message);
                    }
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.notice') ?: 'Notice' ?>', res.message, 'warning', 4000);
                    } else {
                        alert(res.message);
                    }
                    $btn.prop('disabled', false).html('<i class="bi bi-magic me-1"></i><?= __('admin.load_demo_products') ?>');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="bi bi-magic me-1"></i><?= __('admin.load_demo_products') ?>');
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?: 'Error' ?>', '<?= __('admin.something_went_wrong') ?: 'Something went wrong.' ?>', 'error', 4000);
                }
            }
        });
    });

    $('#btn-clear-demo-products').on('click', function() {
        if (!confirm('<?= __('admin.confirm_clear_demo_products') ?: 'Remove all demo products? This cannot be undone.' ?>')) return;
        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.loading') ?: 'Loading...' ?>');
        $.ajax({
            url: '<?= base_url('admincontrol/clearDemoProducts') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.success') ?: 'Success' ?>', res.message, 'success', 4000);
                } else {
                    alert(res.message);
                }
                setTimeout(function() { location.reload(); }, 1500);
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="bi bi-trash3 me-1"></i><?= __('admin.clear_demo_products') ?>');
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?: 'Error' ?>', '<?= __('admin.something_went_wrong') ?: 'Something went wrong.' ?>', 'error', 4000);
                }
            }
        });
    });
</script>