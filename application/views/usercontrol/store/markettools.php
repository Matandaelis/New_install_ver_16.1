<?php
$db =& get_instance();
$userdetails=$db->userdetails();
?>

<script src="<?= base_url('assets/plugins/qrcode.min.js') ?>"></script>

<div class="container-fluid">

    <!-- Search & Filter Panel -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius:12px; overflow:hidden;">
        <div class="card-header border-0 py-3 px-4" style="background:linear-gradient(135deg,#0d6efd 0%,#6610f2 100%);">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:40px;height:40px;background:rgba(255,255,255,.18);">
                        <i class="fas fa-shopping-bag text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-white"><?= __('user.market_tools') ?></h5>
                        <div class="text-white small" style="opacity:.75;"><?= __('user.search_by_campaign') ?></div>
                    </div>
                </div>
                <div class="d-none d-md-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="width:240px;">
                        <span class="input-group-text bg-white bg-opacity-25 border-0 text-white"><i class="fas fa-search"></i></span>
                        <input class="form-control ads_name border-0 text-white" placeholder="<?= __('user.search_by_campaign') ?>..."
                            type="search" style="background:rgba(255,255,255,.15);color:white !important;">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body py-3 px-4" style="background:#f8f9fb;">
            <div class="row g-2 align-items-center">
                <div class="col-12 d-md-none mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input class="form-control ads_name border-start-0" placeholder="<?= __('user.search_by_campaign') ?>..." type="search">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-primary"></i></span>
                        <select class="form-select border-start-0 user_id" style="border-radius:0 6px 6px 0;">
                            <option value=""><?= __('user.search_by_vendor') ?></option>
                            <?php foreach ($vendors_list as $key => $value): ?>
                                <option value="<?= $value['id'] ?>"><?= $value['username'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-folder text-info"></i></span>
                        <select class="form-select border-start-0 category_id" style="border-radius:0 6px 6px 0;">
                            <option value=""><?= __('user.search_by_campaign_category') ?></option>
                            <?php
                            if(count($categories) > 0) {
                                $parentcategoyrid = 0;
                                foreach($categories as $key => $value) {
                                    if($parentcategoyrid != $value['pid']) {
                                        echo '<option value="'.$value['value'].'">'.$value['label'].'</option>';
                                    } else {
                                        echo '<option value="'.$value['value'].'">-- '.$value['label'].'</option>';
                                    }
                                    $parentcategoyrid = $value['pid'];
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-tags text-warning"></i></span>
                        <select class="form-select border-start-0 market_category_id" style="border-radius:0 6px 6px 0;">
                            <option value=""><?= __('user.search_by_store_category') ?></option>
                            <?php foreach ($store_categories as $key => $value): ?>
                                <option value="<?= $value['value'] ?>"><?= $value['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading state -->
    <div class="d-none text-center py-5" id="loading-state">
        <div class="spinner-border text-primary mb-3" role="status" style="width:3rem;height:3rem;">
            <span class="visually-hidden"><?= __('user.loading') ?></span>
        </div>
        <h6 class="text-muted mb-1"><?= __('user.loading_data_please_wait') ?></h6>
        <small class="text-muted"><?= __('user.this_may_take_a_few_moments') ?></small>
    </div>

    <!-- Campaign list -->
    <div id="product-list"></div>

</div>

<div class="modal fade" id="model-codemodal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="modal-title">
                    <i class="fas fa-qrcode me-2"></i><?= __('user.scanner') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4"></div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i><?= __('user.close') ?>
                </button>
                <button type="button" class="btn btn-primary" id="download-qr-btn">
                    <i class="fas fa-download me-1"></i><?= __('user.download') ?>
                </button>
                <button type="button" class="btn btn-success" id="print-qr-btn">
                    <i class="fas fa-print me-1"></i><?= __('user.print') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="model-codeformmodal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-code me-2"></i><?= __('user.code') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i><?= __('user.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="slugtting" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= base_url('/usercontrol/create_slug') ?>" method="post">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-link me-2"></i><?= __('user.create_slug'); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-edit me-1"></i><?= __('user.slug'); ?>
                        </label>
                        <input type="text" name="slug" class="form-control" placeholder="<?= __('user.enter_slug_here') ?>">
                        <input type="hidden" name="type" />
                        <input type="hidden" name="related_id" />
                        <input type="hidden" name="target" />
                    </div>
                    <div class="slug-url">
                        <div class="input-group">
                            <input type="text" readonly="readonly" class="form-control">
                            <button class="btn btn-warning" type="button" title="<?= __('user.copied'); ?>">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i><?= __('user.create'); ?>
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i><?= __('user.close'); ?>
                    </button>
                    <button type="button" class="btn btn-danger btn-delete-slug">
                        <i class="fas fa-trash me-1"></i><?= __('user.delete'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="integration-code" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden"></div>
    </div>
</div>

<?= $social_share_modal ?>

<script type="text/javascript">
    var xhr;
    function getPage(url,page){
        $this = $(this);

        if(xhr && xhr.readyState != 4) xhr.abort();

        $("#loading-state").removeClass("d-none");
        $("#product-list").hide();

        xhr = $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            data:{
                market_category_id: $(".market_category_id").val(),
                category_id: $(".category_id").val(),
                ads_name: $(".ads_name").val(),
                vendor_id: $(".user_id").val(),
                dvl: 1,
                page:page,
                cache_buster: new Date().getTime(),
            },
            cache: false,
            beforeSend:function(){
                $(".btn-search").btn("loading");
            },
            complete:function(){
                $(".btn-search").btn("reset");
                $("#loading-state").addClass("d-none");
                $("#product-list").show();
            },
            success:function(json){
                if(json['view']){
                    $("#product-list").html(json['view']);
                    $("#product-list").show();
                    $(".empty-div").addClass("d-none");
                } else {
                    $(".empty-div").removeClass("d-none");
                    $("#product-list").hide();
                }
            },
        })
    }

    $(".user_id,.category_id,.market_category_id, .display-vendor-links").on("change",function(){
        getPage('<?= base_url("usercontrol/store_markettools/") ?>',1);
    });
    $(".ads_name").on("keyup",function(){
        getPage('<?= base_url("usercontrol/store_markettools/") ?>',1);
    });
    
    getPage('<?= base_url("usercontrol/store_markettools/") ?>',1);

    $("#product-list").delegate(".get-code",'click',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url("integration/tool_get_code/usercontrol") ?>',
            type:'POST',
            dataType:'json',
            data:{id:$this.attr("data-id")},
            beforeSend:function(){ $this.btn("loading"); },
            complete:function(){ $this.html("<i class='bi bi-code-slash'></i>"); },
            success:function(json){
                if(json['error']){
                    if (typeof Swal !== 'undefined') { Swal.fire({ icon: 'warning', text: json['error'] }); }
                    else { alert(json['error']); }
                    return;
                }
                if(json['html']){
                    $("#integration-code .modal-content").html(json['html']);
                    $("#integration-code").modal("show");
                }
            },
        })
    });

    $("#product-list").delegate(".get-terms",'click',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url("integration/tool_get_terms/usercontrol") ?>',
            type:'POST',
            dataType:'json',
            data:{id:$this.attr("data-id")},
            beforeSend:function(){ $this.btn("loading"); },
            complete:function(){ $this.html("<i class='bi bi-info-square'></i>"); },
            success:function(json){
                if(json['html']){
                    $("#integration-code .modal-content").html(json['html']);
                    $("#integration-code").modal("show");
                }
            },
        })
    });

    $("#product-list").delegate(".toggle-child-tr",'click',function(){
        $tr = $(this).parents("tr");
        $ntr = $tr.next("tr.detail-tr");

        if($ntr.css("display") == 'table-row'){
            $ntr.hide();
            $(this).find("i").attr("class","bi bi-plus-circle");
        }else{
            $(this).find("i").attr("class","bi bi-dash-circle");
            $ntr.show();
        }
    })

    $("#product-list").delegate(".show-more",'click',function(){
        $(this).parents("tfoot").remove();
        $("#product-list tr.d-none").hide().removeClass('d-none').fadeIn();
    });

    function generateCode(affiliate_id,t){
        $this = $(t);
        $.ajax({
            url:'<?php echo base_url();?>usercontrol/generateproductcode/'+affiliate_id,
            type:'POST',
            dataType:'html',
            beforeSend:function(){
                $this.btn("loading");
            },
            complete:function(){
             $this.html("<i class='fa-solid fa-code'></i>");
         },
         success:function(json){
            $("#modal-title").text('<?= __('user.copy_html')?>');
            $('#model-codemodal .modal-body').html(json);
            $("#model-codemodal").modal("show");
        },
    })
    }

    function generateCodeForm(form_id,t){ 
        $this = $(t);
        $.ajax({
            url:'<?php echo base_url();?>usercontrol/generateformcode/'+form_id,
            type:'POST',
            dataType:'html',
            beforeSend:function(){
                $this.btn("loading");
            },
            complete:function(){
                $this.html("<i class='fa-solid fa-code'></i>");
            },
            success:function(json){
                $("#modal-title").text('<?= __('user.copy_html')?>');
                $('#model-codeformmodal .modal-body').html(json);
                $("#model-codeformmodal").modal("show");
            },
        })
    }

    function downloadCode(t, id, type){
        $this = $(t);
        $.ajax({
            url:'<?php echo base_url();?>usercontrol/downloadToolCode/'+id+'/'+type,
            type:'POST',
            dataType:'html',
            beforeSend:function(){
                $this.btn("loading");
            },
            complete:function(){
                $this.html("<i class='fa-solid fa-download'></i>");
            },
            success:function(res){
                window.location.href = res;
            },
        })
    }

    $("#show_my_id").change(function(){
        if($(this).prop("checked")){
            $(".show-mega-link").removeClass("d-none");
            $(".show-tiny-link").addClass("d-none");
        } else {
            $(".show-mega-link").addClass("d-none");
            $(".show-tiny-link").removeClass("d-none");
        }
    })

    // Enhanced QR Code functionality
    let currentQRCode = null;
    let currentQRUrl = '';

    $(document).on('click', '.qrcode', function() {
        currentQRUrl = $(this).attr('data-id');
        
        // Clear previous QR code and show loading
        $('#model-codemodal .modal-body').html(`
            <div class="d-flex flex-column align-items-center py-4">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 2rem; height: 2rem;">
                    <span class="visually-hidden"><?= __('user.loading') ?></span>
                </div>
                <h6 class="text-muted mb-0"><?= __('user.generating_qr_code') ?></h6>
            </div>
        `);
        
        // Set modal title
        $("#modal-title").html('<i class="fas fa-qrcode me-2"></i><?= __('user.generate_qr_code') ?>');
        
        // Show modal
        $("#model-codemodal").modal("show");
        
        // Generate QR code after a short delay to show loading state
        setTimeout(() => {
            try {
                // Clear loading state and create container
                $('#model-codemodal .modal-body').html(`
                    <div class="text-center mb-4">
                        <div id="qr-code-container" class="d-inline-block p-3 bg-white rounded shadow-sm border"></div>
                    </div>
                    <div class="alert alert-info mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle me-2 mt-1"></i>
                            <div>
                                <strong>URL:</strong>
                                <div class="text-break small mt-1">${currentQRUrl}</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-mobile-alt me-1"></i>
                            <?= __('user.scan_with_phone_camera') ?>
                        </small>
                    </div>
                `);
                
                // Generate QR code with improved size and mobile optimization
                const qrSize = window.innerWidth < 768 ? 180 : 220; // Responsive size
                
                currentQRCode = new QRCode(document.getElementById("qr-code-container"), {
                    text: currentQRUrl,
                    width: qrSize,
                    height: qrSize,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                
            } catch(error) {
                console.error('QR Code generation failed:', error);
                $('#model-codemodal .modal-body').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= __('user.qr_generation_failed') ?>
                    </div>
                `);
            }
        }, 300); // 300ms delay to show loading state
    });

    // Download QR Code functionality
    $(document).on('click', '#download-qr-btn', function() {
        if (!currentQRCode) return;
        
        try {
            const canvas = $('#qr-code-container canvas')[0];
            if (canvas) {
                // Create download link
                const link = document.createElement('a');
                link.download = 'qrcode-' + Date.now() + '.png';
                link.href = canvas.toDataURL();
                link.click();
            }
        } catch(error) {
            console.error('QR Code download failed:', error);
            alert('<?= __('user.download_failed') ?>');
        }
    });

    // Print QR Code functionality
    $(document).on('click', '#print-qr-btn', function() {
        if (!currentQRCode) return;
        
        try {
            const canvas = $('#qr-code-container canvas')[0];
            if (canvas) {
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>QR Code - ${currentQRUrl}</title>
                            <style>
                                body { text-align: center; font-family: Arial, sans-serif; margin: 40px; }
                                .qr-container { margin: 20px 0; }
                                .url-text { margin-top: 20px; word-break: break-all; color: #666; }
                            </style>
                        </head>
                        <body>
                            <h2><?= __('user.qr_code') ?></h2>
                            <div class="qr-container">
                                <img src="${canvas.toDataURL()}" alt="QR Code" />
                            </div>
                            <div class="url-text">${currentQRUrl}</div>
                        </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.print();
            }
        } catch(error) {
            console.error('QR Code print failed:', error);
            alert('<?= __('user.print_failed') ?>');
        }
    });

    $(document).on('click','.pagination a',function(e){
        e.preventDefault();
        
        let page = $(this).data('ci-pagination-page');

        if(page) {
            $("#loading-state").removeClass("d-none");
            $("#product-list").hide();
            getPage('<?= base_url("usercontrol/store_markettools/") ?>', page);
        }
    });
</script>
</div>