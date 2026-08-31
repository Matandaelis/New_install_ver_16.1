<div class="container-fluid">
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i><?= __('user.store_clients') ?>
                </h5>
                <div class="d-none">
                    <a id="toggle-uploader" class="btn btn-light btn-sm" href="<?php echo base_url("admincontrol/addclients") ?>">
                        <i class="fas fa-user-plus me-1"></i><?= __('user.add_client') ?>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <section class="empty-div d-none text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted"><?= __('admin.no_data_found') ?></h4>
                    <p class="text-muted"><?= __('user.no_clients_yet') ?></p>
                </section>

                <div class="table-responsive">
                    <table id="clients-table" class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i><?= __('user.id') ?></th>
                                <th><i class="fas fa-user me-1"></i><?= __('user.name') ?></th>
                                <th><i class="fas fa-user-friends me-1"></i><?= __('user.refer_user') ?></th>
                                <th><i class="fas fa-envelope me-1"></i><?= __('user.email') ?></th>
                                <th><i class="fas fa-phone me-1"></i><?= __('user.phone') ?></th>
                                <th><i class="fas fa-user-tag me-1"></i><?= __('user.username') ?></th>
                                <th><i class="fas fa-shopping-cart me-1"></i><?= __('user.sales') ?></th>
                                <th><i class="fas fa-tag me-1"></i><?= __('user.type') ?></th>
                                <th class="text-center"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="spinner-border text-primary mb-3" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <h5 class="text-muted"><?= __("user.loading_clients_data_text") ?></h5>
                                    <p class="text-muted small"><?= __("user.not_taking_longer") ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end" style="display: none;"> 
                <div class="pagination"></div> 
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ShipingDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">
            <i class="fas fa-shipping-fast me-2"></i><?= __('user.shipping_details') ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     <form id="frm_shipping_address" >
      <div class="row">
         <div class="col-md-6">
            <div class="mb-3">
               <label for="address" class="form-label"><?= __('user.address') ?></label>
               <input type="text" class="form-control" name="address" id="address" placeholder="<?= __('user.address') ?>" readonly="">

           </div>
       </div>
       <div class="col-md-6">
        <div class="mb-3">
          <label for="country_id" class="form-label"><?= __('user.country') ?></label>
          <input type="text" class="form-control" name="country_id" id="country_id" placeholder="<?= __('user.country') ?>" readonly="">
      </div>
  </div>
</div>
<div class="row">

  <div class="col-md-6">
     <div class="mb-3">
       <label for="state_id" class="form-label"><?= __('store.state') ?></label>
       <input type="text" class="form-control" name="state_id" id="state_id" placeholder="<?= __('user.state') ?>" readonly="">
   </div>
</div>
<div class="col-md-6">
  <div class="mb-3">
     <label for="city" class="form-label"><?= __('store.city') ?></label>
     <input class="form-control" name="city" type="text" id="city" readonly="" >
 </div>
</div>
</div>
<div class="row">

   <div class="col-md-6">
      <div class="mb-3">
         <label for="zip_code" class="form-label"><?= __('store.postal_code') ?></label>
         <input class="form-control" name="zip_code" type="text" id="zip_code" readonly="">

     </div>
 </div>
 <div class="col-md-6">
  <div class="mb-3">
     <label for="sphone" class="form-label"><?= __('store.phone') ?></label>
     <link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
     <script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
     <div>
         <input id="phone" class="form-control" type="text" name="phone" readonly="">
     </div>
     <script type="text/javascript">
        var tel_input = intlTelInput(document.querySelector("#phone"), {
         initialCountry: "auto",
         utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
         separateDialCode:true,
         geoIpLookup: function(success, failure) {
          $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
           var countryCode = (resp && resp.country) ? resp.country : "";
           success(countryCode);
       });
      },
  });
</script>

</div>

</div>
</div>

</form>
</div>

</div>
</div>
</div>
<script type="text/javascript" async="">
    function shareinsocialmedia(url) {
        window.open(url, 'sharein', 'toolbar=0,status=0,width=648,height=395');
        return true;
    }
     
    $(document).on('click','.viewShipping',function(e){
      $this = $(this);
      var id = $(this).data('id');
      $.ajax({
         url:'<?= base_url('usercontrol/getShippingDetails') ?>',
         type:'POST',
         dataType:'json',
         data:{id},
         beforeSend:function(){$this.prop("disabled",true);},
         complete:function(){$this.prop("disabled",false);},
         success:function(data){
            if(data.status) {
                $("#frm_shipping_address").trigger('reset');
                $("#address").val(data.data.address);
                $("#country_id").val(data.data.country_name);
                $("#state_id").val(data.data.state_name);
                $("#city").val(data.data.city);
                $("#zip_code").val(data.data.zip_code);
                tel_input.setNumber(data.data.phone);
                $("#ShipingDetailsModal").modal('show');
            } else {
               Swal.fire({
                icon: 'info',
                text:'<?= __('user.no_shipping_details_found') ?>',
            })
           }

       },
   });
  });
    

    function getclientsRows(page,t){
         $this = $(t);

        $.ajax({
            url:"<?= base_url('usercontrol/listclients'); ?>/"+ page,
            type:'POST',
            dataType:'json',
            data:{listclients:1,page:page},
            beforeSend:function(){$this.btn("loading");},
            complete:function(){$this.btn("reset");},
            success:function(json){
                if(json['html']){
                    $("#clients-table tbody").html(json['html']);
                    $("#clients-table").show();
                } else {
                    $(".empty-div").removeClass("d-none");
                    $("#clients-table").hide();
                }

                $(".card-footer").hide();


                if(json['pagination']){
                    $(".card-footer").show();
                    $(".card-footer .pagination").html(json['pagination'])
                }

            },
        })
    }

    $(".card-footer .pagination").delegate("a","click", function(e){
        e.preventDefault();
        getclientsRows($(this).attr("data-ci-pagination-page"),$(this));
    })

    $(function() {
        getclientsRows(1,$(this));
    });
</script>
</div>