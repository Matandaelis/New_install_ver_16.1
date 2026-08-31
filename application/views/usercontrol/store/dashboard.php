<?php
    $db =& get_instance();
    $userdetails=$db->userdetails();
    $unique_url= base_url().'register/'.base64_encode( $userdetails['id']);
    $ShareUrl = urlencode($unique_url);
    $store_setting =$db->Product_model->getSettings('store');
    $products = $db->Product_model;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="d-flex align-items-center">
                <div class="page-title-dot me-2"></div>
                <h1 class="page-title mb-0"><?= __('user.store_dashboard') ?></h1>
              </div>
              
              <div class="d-flex align-items-center gap-2">
                
                <div class="btn-group btn-group-sm" role="group">
                  <a href="<?= base_url('usercontrol/store_dashboard') ?>" class="btn btn-primary btn-sm" title="<?= __('user.store_dashboard') ?>">
                    <i class="fas fa-home"></i><span class="d-none d-lg-inline ms-1"><?= __('user.dashboard') ?></span>
                  </a>
                  <?php if($store_setting['store_mode'] == 'cart') {?>
                  <a href="<?= base_url('usercontrol/store_products') ?>" class="btn btn-outline-primary btn-sm" title="<?= __('user.products') ?>">
                    <i class="fas fa-box"></i><span class="d-none d-xl-inline ms-1"><?= __('user.products') ?></span>
                  </a>
                  <?php } else { ?>
                  <a href="<?= base_url('usercontrol/sales_products') ?>" class="btn btn-outline-primary btn-sm" title="<?= __('user.products') ?>">
                    <i class="fas fa-box"></i><span class="d-none d-xl-inline ms-1"><?= __('user.products') ?></span>
                  </a>
                  <?php } ?>
                  <a href="<?= base_url('usercontrol/store_venodr_orders') ?>" class="btn btn-outline-primary btn-sm" title="<?= __('user.orders') ?>">
                    <i class="fas fa-shopping-cart"></i><span class="d-none d-xl-inline ms-1"><?= __('user.orders') ?></span>
                  </a>
                </div>
                
                <div class="dropdown">
                  <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" title="<?= __('user.more_options') ?>">
                    <i class="fas fa-ellipsis-h"></i><span class="d-none d-md-inline ms-1"><?= __('user.more') ?></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <?php if($store_setting['store_mode'] == 'cart') {?>
                    <li><a class="dropdown-item" href="<?= base_url('usercontrol/store_coupon') ?>">
                      <i class="fas fa-ticket-alt me-2"></i><?= __('user.store_coupons') ?>
                    </a></li>
                    <?php } ?>
                    <li><a class="dropdown-item" href="<?= base_url('usercontrol/listclients') ?>">
                      <i class="fas fa-users me-2"></i><?= __('user.store_clients') ?>
                    </a></li>
                    <?php if($store_setting['store_mode'] == 'sales') {?>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header text-primary"><i class="fas fa-bolt me-1"></i><?= __('user.sales_funnels') ?></h6></li>
                    <li><a class="dropdown-item" href="<?= base_url('usercontrol/sales_funnels') ?>">
                      <i class="fas fa-layer-group me-2"></i><?= __('user.manage_funnels') ?>
                    </a></li>
                    <li><a class="dropdown-item" href="<?= base_url('usercontrol/funnel_pricing') ?>">
                      <i class="fas fa-tag me-2"></i><?= __('user.funnel_pricing') ?>
                    </a></li>
                    <?php } ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url('usercontrol/store_setting') ?>">
                      <i class="fas fa-cog me-2"></i><?= __('user.store_settings') ?>
                    </a></li>
                  </ul>
                </div>
                
                <?php $store_url = base_url('store/'.$userdetails['store_slug']); ?>
                <a href="<?= $store_url ?>" target="_blank" class="btn btn-success btn-sm">
                  <i class="fas fa-external-link-alt me-1"></i><span class="d-none d-lg-inline"><?= __('user.view_store') ?></span>
                </a>
              </div>
            </div>

        </div>
    </div>

    <?php if($store_setting['store_mode'] == 'sales') {?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                    <i class="fas fa-bolt text-white fs-2"></i>
                                </div>
                                <div>
                                    <h4 class="text-white fw-bold mb-1">
                                        <i class="fas fa-layer-group me-2"></i><?= __('user.sales_funnels') ?>
                                    </h4>
                                    <p class="text-white mb-0 opacity-75">
                                        <?= __('user.set_exclusive_funnel_prices_manage') ?>
                                    </p>
                                </div>
                            </div>
                            <p class="text-white mb-0 small opacity-90">
                                <i class="fas fa-info-circle me-1"></i>
                                <?= __('user.create_sequential_upsell_flows') ?>
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-flex flex-column gap-2">
                                <a href="<?= base_url('usercontrol/sales_funnels') ?>" class="btn btn-light btn-lg fw-bold">
                                    <i class="fas fa-layer-group me-2"></i><?= __('user.manage_funnels') ?>
                                </a>
                                <a href="<?= base_url('usercontrol/funnel_pricing') ?>" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-tag me-2"></i><?= __('user.funnel_pricing') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

<div class="row g-3">
  <div class="col-sm-6 col-lg-3">
    <div class="card border-primary border-2 shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
            <i class="fas fa-dollar-sign text-primary fs-4"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="text-muted mb-1 small text-uppercase"><?= __( 'admin.total_sale') ?></h6>
            <h4 class="mb-0 fw-bold text-primary"><?= c_format($vendor_store_statistic['total_sale']) ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-sm-6 col-lg-3">
    <div class="card border-info border-2 shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
            <i class="fas fa-mouse-pointer text-info fs-4"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="text-muted mb-1 small text-uppercase"><?= __('admin.clicks_statistic') ?></h6>
            <h4 class="mb-0 fw-bold text-info"><?= number_format($vendor_store_statistic['count_click']) ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-sm-6 col-lg-3">
    <div class="card border-success border-2 shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
            <i class="fas fa-box text-success fs-4"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="text-muted mb-1 small text-uppercase"><?= __( 'admin.total_products') ?></h6>
            <h4 class="mb-0 fw-bold text-success"><?= number_format($vendor_store_statistic['count_product']) ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-sm-6 col-lg-3">
    <div class="card border-warning border-2 shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
            <i class="fas fa-ticket-alt text-warning fs-4"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="text-muted mb-1 small text-uppercase"><?= __( 'admin.total_products_coupons') ?></h6>
            <h4 class="mb-0 fw-bold text-warning"><?= number_format($vendor_store_statistic['count_coupon']) ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-bag me-2 text-primary"></i><?= __('user.recent_orders') ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="store-dashboard-orders" class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i><?= __('admin.order_id') ?></th>
                                <th><i class="fas fa-dollar-sign me-1"></i><?= __('admin.price') ?></th>
                                <th class="text-center"><i class="fas fa-info-circle me-1"></i><?= __('admin.order_status') ?></th>
                                <th><i class="fas fa-credit-card me-1"></i><?= __('admin.payment_method') ?></th>
                                <th><i class="fas fa-globe me-1"></i><?= __('admin.ip') ?></th>
                                <th><i class="fas fa-receipt me-1"></i><?= __('admin.transaction') ?></th>
                                <th><i class="fas fa-check-circle me-1"></i><?= __('admin.status') ?></th>
                                <th class="text-center"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <script type="text/javascript">
    function getPage(url){
        $this = $(this);

        $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            data:$("#filter-form").serialize(),
            beforeSend:function(){$this.btn("loading");},
            complete:function(){$this.btn("reset");},
            success:function(json){
                if(json['view']){
                    $("#store-dashboard-orders tbody").html(json['view']);
                    $("#store-dashboard-orders").show();
                } else {
                    $(".empty-div").removeClass("d-none");
                    $("#store-dashboard-orders").hide();
                }
                
                $("#store-dashboard-orders .pagination-td").html(json['pagination']);
            },
        })
    }

    getPage('<?= base_url("usercontrol/store_dashboard_order_list?page=1") ?>');
    $("#store-dashboard-orders").delegate(".pagination-td a","click",function(e){
        e.preventDefault();
        getPage($(this).attr("href"));
        return false;
    })
    </script>
</div>
</div>