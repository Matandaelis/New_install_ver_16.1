<?php
if(!defined('SCRIPT_VERSION') || !defined('CODECANYON_LICENCE') || SCRIPT_VERSION == "" || CODECANYON_LICENCE == "") { ?>

  <script type="text/javascript">
    $("#model-adminpassword").remove();
    $.ajax({
      global: false,
      url:'<?= base_url("installversion/confirm_password") ?>',
      type:'POST',
      dataType:'json',
      data:{for:"license"},
      success:function(json){
        if(json['html']){
          $("body").append(json['html']);
          $("#model-adminpassword").modal("show");
          $('#model-adminpassword').on('hidden.bs.modal', function (){
            $("#model-adminpassword").modal("show");
          });
        }
      }
    });

  </script>
<?php } ?>

<script type="text/javascript">
  function resetNotify(){
    $.ajax({
      global: false,
      url:'<?= base_url('admincontrol/resetnotify') ?>',
      type:'POST',
      dataType:'json',
      beforeSend:function(){},
      success:function(response){
        if(response.status == 1)
          $(".ajax-notifications_count").text(0);
      },
    })
  }
</script>

<?php
$db =& get_instance(); 
$products = $db->Product_model; 
$userdetails=$db->Product_model->userdetails(); 
$SiteSetting =$db->Product_model->getSiteSetting(); 
$license = $products->getLicese();
$class_method=$db->router->fetch_method();

// Guided Tour System - Available on all admin pages
?>
<?php 
// Include modal templates
include 'welcome-modal.php';
include 'tour-modal.php';
?>

<script>
// Welcome Modal System Translations
window.welcomeTranslations = {
    demo_external_title: <?= json_encode(__('admin.demo_external_title')) ?>,
    demo_builtin_title: <?= json_encode(__('admin.demo_builtin_title')) ?>,
    welcome_demo_completed: <?= json_encode(__('admin.welcome_demo_completed')) ?>,
    redirecting_to_marketing: <?= json_encode(__('admin.redirecting_to_marketing')) ?>,
    redirecting_to_store: <?= json_encode(__('admin.redirecting_to_store')) ?>,
    welcome_explore_dashboard: <?= json_encode(__('admin.welcome_explore_dashboard')) ?>
};

// Welcome Modal Demo Steps
window.welcomeDemoSteps = {
    external: [
        {
            title: <?= json_encode(__('admin.demo_external_step1_title')) ?>,
            desc: <?= json_encode(__('admin.demo_external_step1_desc')) ?>,
            icon: 'fa-link'
        },
        {
            title: <?= json_encode(__('admin.demo_external_step2_title')) ?>,
            desc: <?= json_encode(__('admin.demo_external_step2_desc')) ?>,
            icon: 'fa-chart-line'
        },
        {
            title: <?= json_encode(__('admin.demo_external_step3_title')) ?>,
            desc: <?= json_encode(__('admin.demo_external_step3_desc')) ?>,
            icon: 'fa-users'
        },
        {
            title: <?= json_encode(__('admin.demo_external_step4_title')) ?>,
            desc: <?= json_encode(__('admin.demo_external_step4_desc')) ?>,
            icon: 'fa-rocket'
        }
    ],
    builtin: [
        {
            title: <?= json_encode(__('admin.demo_builtin_step1_title')) ?>,
            desc: <?= json_encode(__('admin.demo_builtin_step1_desc')) ?>,
            icon: 'fa-store'
        },
        {
            title: <?= json_encode(__('admin.demo_builtin_step2_title')) ?>,
            desc: <?= json_encode(__('admin.demo_builtin_step2_desc')) ?>,
            icon: 'fa-box'
        },
        {
            title: <?= json_encode(__('admin.demo_builtin_step3_title')) ?>,
            desc: <?= json_encode(__('admin.demo_builtin_step3_desc')) ?>,
            icon: 'fa-users'
        },
        {
            title: <?= json_encode(__('admin.demo_builtin_step4_title')) ?>,
            desc: <?= json_encode(__('admin.demo_builtin_step4_desc')) ?>,
            icon: 'fa-rocket'
        }
    ]
};
</script>

        </div>
    </div>
</div>

<?php

// Load admin theme colors for footer
$admin_footer_bg = $products->getSettings('theme','admin_footer_bg')['admin_footer_bg'] ?? '';
$admin_footer_text = $products->getSettings('theme','admin_footer_text')['admin_footer_text'] ?? '';

if(isset($license['is_lifetime']) && $license['is_lifetime'] == false){ ?>

  <div class="license-expire">
    <span><?= __('admin.your_license_expire_in') ?> <span class="timer"><?= $license['remianing_time'] ?></span> </span>
  </div>

  <script type="text/javascript">
    var distance = <?= (float)$license['remianing_time'] ?>;
    var x = setInterval(function() {
      var days = Math.floor(distance / (60 * 60 * 24));
      var hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60));
      var minutes = Math.floor((distance % (60 * 60)) / (60));
      var seconds = Math.floor((distance % (60)));

      var timer = '';
      if(days > 0) timer += days + "d ";
      if(hours > 0) timer += hours + "h ";

      $(".license-expire .timer").html(timer +  minutes + "m " + seconds + "s ");
      distance--;
      if(distance < 0){
        clearInterval(x);
        $(".license-expire .timer").html('<?= __('admin.expired') ?>');
        window.location.reload();
      }
    }, 1000);
  </script>

<?php } ?>

</div>
</div>

<?php
$global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
if(in_array('admin', $global_script_status))
  echo $SiteSetting['global_script'];
require APPPATH . 'views/common/setting_widzard.php';
?>

<!--common code to show progress bar for all curd functions-->
<div class="progress fixed-top" style="display: none; height: 4px;">
    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<!--common code to show progress bar for all curd functions-->

  <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
</div>

<script src="<?= base_url('assets/template/js/jquery-ui.min.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/jquery-confirm.min.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/modernizr.min.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/detect.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/fastclick.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/jquery.slimscroll.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/jquery.blockUI.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/waves.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/jquery.nicescroll.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/jquery.scrollTo.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/skycons/skycons.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/raphael/raphael-min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/morris/morris.min.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/sweetalert2.all.min.js') ?>?v=<?= av() ?>"></script>

<!--summNote js files-->
<script src="<?= base_url('assets/template/summernote/summernote-lite.min.js'); ?>"></script>
<!--summNote js files-->


<!--JS files-->
<script src="<?= base_url('assets/template/js/jscolor.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/colorsmode.js'); ?>"></script>
<script src="<?= base_url('assets/template/js/app.js'); ?>"></script>
<!--JS files-->

<!--pdf/excel/image js-->
<script src="<?= base_url('assets/template/js/xlsx.full.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/jspdf.umd.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/jspdf.plugin.autotable.min.js'); ?>?v=<?= av() ?>"></script>
<!--pdf/excel/image js-->


<!--Remember last tab script-->
<script>
    $(document).ready(function(){ 
        function manageTabClasses(target) {
            // Remove existing background and text color classes from all tabs
            $('#TabsNav .nav-link').removeClass('bg-secondary bg-primary text-white');

            // Add 'bg-secondary' and 'text-white' classes to all tabs
            $('#TabsNav .nav-link').addClass('bg-secondary text-white');

            // Add 'bg-primary' and 'text-white' classes to the target tab
            $(target).addClass('bg-primary text-white').removeClass('bg-secondary');
        }

        // Polyfill for window.location.pathname in older browsers
        var pagePath = window.location.pathname || (window.location.href.split(window.location.host)[1]);
        
        // Unique key for localStorage
        var localStorageKey = 'activeTab' + pagePath;

        // Initialize default active tab
        var defaultActiveTab = $('#TabsNav .nav-link').first();
        manageTabClasses(defaultActiveTab);

        // Event handler for tab change
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            manageTabClasses(e.target);
            if (typeof(Storage) !== "undefined") {  // Check if localStorage is supported
                localStorage.setItem(localStorageKey, $(e.target).attr('href'));
            }
        });

        // Retrieve stored tab: URL param (deep-link) takes priority over localStorage
        var activeTab = null;
        var urlParams = new URLSearchParams(window.location.search);
        var isSaasSetting = (pagePath.indexOf('saas_setting') !== -1) || (window.location.href.indexOf('saas_setting') !== -1);
        if (urlParams.get('tab') === 'deposit' && isSaasSetting) {
            activeTab = '#vendor_deposite_setting';
        }
        if (!activeTab && typeof(Storage) !== "undefined") {
            activeTab = localStorage.getItem(localStorageKey);
        }
        if (activeTab) {
            var tabTrigger = document.querySelector('#TabsNav a[href="' + activeTab + '"]');
            if (tabTrigger) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                    var tab = bootstrap.Tab.getOrCreateInstance(tabTrigger);
                    tab.show();
                } else {
                    $(tabTrigger).tab('show');
                }
                manageTabClasses($(tabTrigger));
            } else {
                manageTabClasses(defaultActiveTab);
            }
        } else {
            manageTabClasses(defaultActiveTab);
        }
    });
</script>
<!--Remember last tab script-->


<!-- flash message div -->
<div class="print-message"><?php print_message($this); ?></div>
<!-- flash message div -->

<!-- Toast container (bottom-right) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1080">
    <div id="copyToast" class="toast text-bg-success border-0" role="alert">
        <div class="toast-body fw-bold"><?= __('admin.link_copied') ?></div>
    </div>
</div>

<!-- Script -->
<script>
/* Bootstrap toast instance (1 s auto-hide) */
const copyToast = bootstrap.Toast.getOrCreateInstance(
    document.getElementById('copyToast'),
    { delay: 1000 }          // 1000 ms = 1 s
);

/* Copy-to-clipboard for every .copy-btn */
document.addEventListener('click', e => {
    const btn = e.target.closest('.copy-btn');
    if (!btn) return;

    navigator.clipboard.writeText(btn.dataset.clipboard || '')
        .then(() => copyToast.show())
        .catch(() => alert('<?= __('admin.clipboard_failed') ?>'));
});
</script>

<!-- Toast container (bottom-right) -->

<!--flash message script-->
<script>
  function showPrintMessage(message, type, redirectUrl = "") {
    let messageStr = '';
    let icon = '';
    
    // Determine icon and alert class based on type
    switch(type) {
      case 'success':
        icon = 'bi-check-circle-fill';
        messageStr = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi ${icon} me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;
        break;
      case 'error':
        icon = 'bi-exclamation-triangle-fill';
        messageStr = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi ${icon} me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;
        break;
      case 'warning':
        icon = 'bi-exclamation-triangle-fill';
        messageStr = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi ${icon} me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;
        break;
      case 'info':
        icon = 'bi-info-circle-fill';
        messageStr = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi ${icon} me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>`;
        break;
      default:
        throw new Error('Invalid message type. Use: success, error, warning, or info');
    }
    
    // Clear any existing messages first
    $(".print-message").empty();
    
    // Add the new message
    $(".print-message").html(messageStr);

    // Auto-dismiss after 5 seconds with smooth animation
    const alertElement = $(".print-message .alert");
    setTimeout(function() {
      alertElement.fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
      });
    }, 5000);

    // Handle redirect if provided
    if (redirectUrl !== "") {
      setTimeout(function() {
        window.location.href = redirectUrl;
      }, 4000);
    }
    
    // Initialize Bootstrap 5 dismiss functionality
    const bsAlert = new bootstrap.Alert(alertElement[0]);
    
    // Handle manual close button clicks
    alertElement.find('.btn-close').on('click', function() {
      bsAlert.close();
    });
  }
</script>
<!--flash message script-->

<!--Loading spinner for all save buttons-->
<script>
   // Wait for the document to be fully loaded
  document.addEventListener("DOMContentLoaded", function() {
      
      // Select all buttons with the class 'btn-submit'
      const submitButtons = document.querySelectorAll('.btn-submit');
      
      // Attach a click event listener to each submit button
      submitButtons.forEach(button => {
          button.addEventListener('click', function() {
              
              // Select the spinner icon and remove the 'd-none' class to display it
              const spinner = button.querySelector('.loading-submit');
              
              // Check if spinner is null before proceeding
              if (spinner) {
                  spinner.classList.remove('d-none');
              }
          });
      });
  });
</script>
<!--Loading spinner for all save buttons-->

<!--confirmpopup edit script-->
<script>
  function confirmpopup(url)
    { 
       Swal.fire({
       icon: 'warning',
       text: '<?= __('admin.are_you_sure_to_edit') ?>',
       showCancelButton: true,
       cancelButtonText: 'cancel'
    }).then(function(dismiss){
        if(dismiss.value==true)
        {
          window.location=url;
          return true;
        }
        else
        {
          return false;
        }
    });
    return false;
  };
</script>
<!--confirmpopup script-->

<?php if(true || count($status) > 0){ ?>
  <script type="text/javascript">
  
    $(document).delegate(".only-number-allow","keypress",function (e) {
      if (e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
        return false;
    });

    $(document).ready(function(){
      if(getCookie('hide_welcome') != 'true')
        $("#welcome-modal").modal("show");
    })

    function setCookie(cname, cvalue, exdays){
      var d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));

      var expires = "expires="+d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function readURLAndSetValue(input,name,placeholder){
      if(input.files && input.files[0]){
        var reader = new FileReader();

        reader.onload = function(e){
          $("input[name='"+name+"']").val('image.jpg');
          $(placeholder).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function readURL(input,placeholder){
      if(input.files && input.files[0]){
        var reader = new FileReader();

        reader.onload = function(e){
          $(placeholder).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function getCookie(cname){
      var name = cname + "=";
      var ca = document.cookie.split(';');

      for(var i = 0; i < ca.length; i++){
        var c = ca[i];

        while (c.charAt(0) == ' '){
          c = c.substring(1);
        }
        if(c.indexOf(name) == 0)
          return c.substring(name.length, c.length);
      }
      return "";
    }

    $('.hide-welcome').on('click',function(){
      setCookie("hide_welcome","true", 365)
      $("#welcome-modal").modal("hide");

    })
  </script>  
<?php } ?>

<div class="modal" id="model-shorturl"></div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<script type="text/javascript">
  let leftHeight = $(".left-menu").height();
  let navbarHeight = $(".dashboard-navbar").height();
  let errorHeight = $(".server-errors").height();
  let footerHeight = $(".dashboard-footer").height();
  let elTotalheight = navbarHeight + errorHeight + footerHeight;
  let contentHeight = leftHeight - elTotalheight - 26;
  $(".content-wrapper").css('min-height',contentHeight);

  $(document).delegate(".copy-input input",'click', function(){
    $(this).select();
  })

  $(document).delegate('[copyToClipboard]',"click", function(){
    $this = $(this);
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(this).attr('copyToClipboard')).select();
    document.execCommand("copy");
    $temp.remove();
    $this.tooltip('hide').attr('data-original-title','<?= __('admin.copied') ?>').tooltip('show');
    setTimeout(function() { $this.tooltip('hide'); }, 500);
  });

  $('[copyToClipboard]').tooltip({
    trigger: 'click',
    placement: 'bottom'
  });

  (function ($) {
    $.fn.button = function (action){
      var self = $(this);
      if(action == 'loading'){
        if($(self).attr("disabled") == "disabled"){
          return this;
        }
        $(self).attr("disabled", "disabled");
        $(self).attr('data-btn-text', $(self).html());
        $(self).html('<div class="spinner-border spinner-border-sm me-2"></div>' + $(self).text());
      }
      if(action == 'reset'){
        $(self).html($(self).attr('data-btn-text'));
        $(self).removeAttr("disabled");
      }
      return this;
    }
  })(jQuery);
  </script>

<!--copyToClipboard Common Function-->
<script>
  $(document).delegate('[copyToClipboard]', "click", function(){
      $this = $(this);
      var $temp = $("<input>");

      $("body").append($temp);
      $temp.val($this.attr('copyToClipboard')).select();
      document.execCommand("copy");
      $temp.remove();

      // Get the span and update its content
      var $statusSpan = $this.find('.copy-status');
      $statusSpan.text('<?= __('admin.copied') ?>');

      // Set a timeout to clear the message after a short time
      setTimeout(function() {
          $statusSpan.text('');
      }, 1500);
  });
</script>
<!--copyToClipboard Common Function-->

  <div class="modal fade" id="ip-flag_model">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?= __('admin.all_ips_details') ?></h4>
          <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
        </div>
      </div>
    </div>
  </div>

<!--summNote without image and video-->
<script>
  $(document).ready(function() {
      function updateToolbar() {
          var toolbarOptions = [
              ['style', ['style']],
              ['font', ['bold', 'underline', 'clear']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['view', ['fullscreen', 'codeview', 'help']]
          ];

          // Adding image and link options only for screens wider than 768px
          if ($(window).width() > 768) {
              toolbarOptions.unshift(['image', ['image']], ['insert', ['link']]);
          }

          // Destroy existing Summernote instance before re-initializing
          // This is necessary to apply new toolbar options
          if ($('.summernote-img').summernote) {
              $('.summernote-img').summernote('destroy');
          }

          $('.summernote-img').summernote({
              tabsize: 2,
              height: 300,
              minHeight: null,
              maxHeight: null,
              focus: false,
              toolbar: toolbarOptions
          });
      }

      // Initialize with correct toolbar based on initial window size
      updateToolbar();

      // Adjust the toolbar if the window is resized
      $(window).resize(updateToolbar);
  });
</script>
<!--summNote without image and video-->

<!--summNote full-->
<script>
  $(document).ready(function() {
    $('.summernote').summernote({
      tabsize: 2,
      height: 300,
      minHeight: null,
      maxHeight: null,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  });
</script>
<!--summNote full-->


<script>
    $(".select2-input").select2();
    $(document).delegate(".view-all",'click',function(){
      var data = $(this).find("span").html();
      var html = '<table class="table table-hover">';

      data = JSON.parse(data);
      html += '<tr>';
      html += ' <th>'+'<?= ('admin.ip') ?>'+'</th>';
      html += ' <th width="30px">'+'<?= ('admin.country') ?>'+'</th>';
      html += '</tr>';

      $.each(data, function(i,j){
        html += '<tr>';
        html += ' <td>'+ j['ip'] +'</td>';
        var _cc = (j['country_code'] || '').toLowerCase();
        html += ' <td>' + (_cc ? '<img style="width: 20px;" src="<?= base_url('assets/template/images/flags/') ?>' + _cc + '.png">' : '<span class="text-muted">—</span>') + '</td>';

        html += '</tr>';
      })

      html += '</table>';

      $("#ip-flag_model").modal("show");

      $("#ip-flag_model .modal-body").html(html);
    })
    $('[data-toggle="tooltip"]').tooltip();   
    if($('#morris-area-chart').length > 0){
      var areaData = [
      {y: '2011', a: 10, b: 15},
      {y: '2012', a: 30, b: 35},
      {y: '2013', a: 10, b: 25},
      {y: '2014', a: 55, b: 45},
      {y: '2015', a: 30, b: 20},
      {y: '2016', a: 40, b: 35},
      {y: '2017', a: 10, b: 25},
      {y: '2018', a: 25, b: 30}
      ];

      Morris.Area({
        element: 'morris-area-chart',
        pointSize: 3,
        lineWidth: 2,
        data: areaData,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Orders', 'Sales'],
        resize: true,
        hideHover: 'auto',
        gridLineColor: '#eef0f2',
        lineColors: ['#00c292', '#03a9f3'],
        lineWidth: 0,
        fillOpacity: 0.1,
        xLabelMargin: 10,
        yLabelMargin: 10,
        grid: false,
        axes: false,
        pointSize: 0

      });
    }
    $(document).ready(function(){
      if($('#morris-donut-chart').length > 0){
        var donutData = [
        <?php $str = '';
        $country_list = isset($country_list)?$country_list:[];
        foreach($country_list as $key => $one_item){ 
          $str .= '{label: "' . $one_item->name . '", value: ' . (int)$one_item->num . '},'; 
        }
        echo rtrim($str,", ");
        ?>
        ];
        Morris.Donut({
          element: 'morris-donut-chart',
          data: donutData,
          resize: true,
          colors: ['#40a4f1', '#5b6be8', '#c1c5e2', '#e785da', '#00bcd2']
        });
      }

      if($("#boxscroll").length > 0)
        $("#boxscroll").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});

      if($("#boxscroll2").length > 0)
        $("#boxscroll2").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true}); 

      if($(".clickable-row").length > 0){
        $(".clickable-row").on('click',function(){
          window.location = $(this).data("href");
        });
      }
      if($("#Country").length > 0){
        $('#Country').on('change', function(){
          country_id = $(this).val();

          $.ajax({
            global: false,
            type: "POST",

            url: "<?= base_url();?>admincontrol/getstate",

            data:'country_id='+country_id,

            success: function(data){

              $("#StateProvince").html(data);

            }

          });

        });
      }
    });

    function shownofication(id, url) {
      // Visually remove the clicked item from the offcanvas list immediately
      var clickedItem = document.querySelector('.anp-item[onclick*="shownofication(' + id + ',"]');
      if (!clickedItem) {
        // fallback: find by matching id in the onclick attribute (single quotes)
        var items = document.querySelectorAll('.anp-item');
        items.forEach(function(el) {
          if (el.getAttribute('onclick') && el.getAttribute('onclick').indexOf('(' + id + ',') !== -1) {
            clickedItem = el;
          }
        });
      }
      if (clickedItem) {
        clickedItem.style.transition = 'opacity 0.25s, transform 0.25s';
        clickedItem.style.opacity = '0';
        clickedItem.style.transform = 'translateX(20px)';
        setTimeout(function() {
          clickedItem.remove();
          // Decrement badge
          var badge = document.querySelector('.notification-badge.ajax-notifications_count, .ajax-notifications_count');
          if (badge) {
            var count = parseInt(badge.textContent) - 1;
            if (count <= 0) { badge.style.display = 'none'; }
            else { badge.textContent = count; }
          }
          // If no items left, show empty state
          var list = document.querySelector('.anp-list');
          if (list && list.querySelectorAll('.anp-item').length === 0) {
            var empty = document.createElement('div');
            empty.className = 'anp-empty';
            empty.innerHTML = '<i class="fas fa-check-double anp-empty-icon"></i>' +
              '<div class="anp-empty-title"><?= __('admin.all_caught_up') ?></div>' +
              '<div class="anp-empty-sub"><?= __('admin.no_new_notifications') ?></div>';
            list.appendChild(empty);
          }
        }, 260);
      }

      // Mark as read on server and navigate
      $.ajax({
        global: false,
        type: "POST",
        url: "<?= base_url('admincontrol/updatenotify');?>",
        data: 'id=' + id,
        dataType: 'json',
        success: function(data) {
          window.location.href = data['location'];
        },
        error: function() {
          // Fallback: use pre-computed url passed from PHP
          if (url) { window.location.href = url; }
        }
      });
    }
</script>

<!--session script-->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var timerElement = document.querySelector(".session-timer em");
    var initialTime = <?php echo isset($timeout) ? $timeout : 60; ?>;
    var remainingTime;

    // Retrieve remainingTime from local storage
    if (localStorage.getItem('remainingTime')) {
      remainingTime = parseInt(localStorage.getItem('remainingTime'));
    } else {
      remainingTime = initialTime;
      localStorage.setItem('remainingTime', initialTime);
    }

    // Reset the timer when the page is refreshed or redirected
    window.addEventListener('load', function() {
      localStorage.setItem('remainingTime', initialTime);
      remainingTime = initialTime;
    });

    var timer = setInterval(function() {
      remainingTime--;
      localStorage.setItem('remainingTime', remainingTime);

      if (remainingTime <= 0) {
        clearInterval(timer);
        localStorage.removeItem('remainingTime');
        window.location.href = "<?php echo base_url('admincontrol/logout'); ?>";
        return;
      }

      if (timerElement) {
        var h = Math.floor(remainingTime / 3600);
        var m = Math.floor(remainingTime % 3600 / 60);
        var s = Math.floor(remainingTime % 3600 % 60);
        var timeString = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        timerElement.innerText = timeString;
      }
    }, 1000);
  });
</script>
<!--session script-->

<!--Amazon s3 products modal-->
<div class="modal fade" id="s3FilesModal" tabindex="-1" aria-labelledby="s3FilesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="s3FilesModalLabel"><?= __('admin.amazon_s3_files') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul id="s3-file-list" class="list-group"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
      </div>
    </div>
  </div>
</div>
<!--this is model that show amazon s3 products-->

<!-- Amazon S3 script-->
<script>
  let currentInputSelector = '';
  let currentImageSelector = '';
  let multiplImageSelector = '';

  function loadS3Images() {
      // Retrieve the dynamic values from the input fields
      const bucketName = document.getElementById('s3_bucket_name').value;
      const region = document.getElementById('s3_region').value;
      const url = `https://${bucketName}.s3.${region}.amazonaws.com`;

      fetch(url)
          .then(response => response.text())
          .then(str => (new window.DOMParser()).parseFromString(str, "text/xml"))
          .then(data => {
              const files = data.getElementsByTagName("Key");
              let fileList = "";
              for (let i = 0; i < files.length; i++) {
                  const fileKey = files[i].textContent;
                  const fileUrl = `${url}/${fileKey}`;
                  // Determine file type for preview
                  fileList += `<li class="list-group-item d-flex justify-content-between align-items-center">
                      <span>${fileKey}</span>
                      ${generatePreviewElement(fileKey, fileUrl)}
                      <button class="btn btn-primary btn-sm" onclick="updateS3Src('${fileUrl}');">Select</button>
                  </li>`;
              }
              document.getElementById('s3-file-list').innerHTML = fileList || '<li class="list-group-item">No files found.</li>';
              // Show the modal after populating it
              new bootstrap.Modal(document.getElementById('s3FilesModal')).show();
          })
          .catch(error => {
              console.error('Error loading S3 images:', error);
              const errorMessageHtml = `
                  <li class="list-group-item text-danger">
                      Error loading files: ${error.message}
                  </li>
                  <li class="list-group-item">
                      Please check your S3 Bucket Name and S3 Region settings to ensure they are correct.
                  </li>`;

              document.getElementById('s3-file-list').innerHTML = errorMessageHtml;
              new bootstrap.Modal(document.getElementById('s3FilesModal')).show();
          });
  }

  function generatePreviewElement(fileKey, fileUrl) {
      if (/\.(jpg|jpeg|png|gif)$/i.test(fileKey)) {
          return `<img src="${fileUrl}" style="max-width: 100px; max-height: 100px;" onerror="this.style.display='none';" />`;
      } else if (/\.(mp4|webm)$/i.test(fileKey)) {
          return `<video src="${fileUrl}" style="max-width: 100px; max-height: 100px;" controls></video>`;
      } else {
          return `<span>No preview available</span>`;
      }
  }

  function updateS3Src(fileUrl) {
      
      $(currentInputSelector).val(fileUrl); // Update the specified input's value
      if ($(currentImageSelector).is('img, video')) {
          $(currentImageSelector).attr('src', fileUrl); // Update the image/video src
      }
      $('#s3FilesModal').modal('hide'); // Close the modal
  }

  function prepareS3Modal(inputSelector, imageSelector) {
      currentInputSelector = inputSelector;
      currentImageSelector = imageSelector;
      loadS3Images(); // Now load the images
  }

  /*=================================*/

  //download s3 code functions

  function prepareS3ModalDownloadbale(inputSelector, imageSelector, multipleproduct=null) {
    
      currentInputSelector = inputSelector;
      currentImageSelector = imageSelector;
      multiplImageSelector=multipleproduct;
      loadS3ImagesDownloadbale(); // Now load the images
  }

  function getFileTypeFromUrl(url) {
      // Extract the file name from the URL
      const fileName = url.substring(url.lastIndexOf('/') + 1);
      
      // Extract the file extension from the file name
      const fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
      
      // Determine the file type based on the file extension
      switch (fileExtension) {
          case 'jpg':
          case 'jpeg':
          case 'png':
          case 'gif':
          case 'bmp':
              return 'image';
          case 'pdf':
              return 'pdf';
          case 'mp4':
          case 'mov':
          case 'avi':
          case 'wmv':
              return 'video';
          case 'zip':
          case 'rar':
          case '7z':
          case 'tar':
              return 'archive';
          // Add more cases for other file types if needed
          default:
              return 'unknown';
      }
  }
  
  async function updateS3SrcDownloadbale(fileUrl) {
    try {
    
    const fileReader = new FileReader();
    var fileName = getFileNameFromS3Url(fileUrl);
    fileReader.name = fileName;
    fileReader.type = getFileTypeFromUrl(fileUrl);
    fileReader.imageType = 's3';
    fileReader.imageurl = fileUrl;
    
    fileArray.push(fileReader);
    render_preview_downloadable();
    
  } catch (error) {
    throw error;
    console.error('Error fetching file:', error);
  }
  $('#s3FilesModal').modal('hide'); // Close the modal*/
  }

  function getFileNameFromS3Url(link) {
      
      var parts = link.split('/');
    // The last part after the last '/' will be the filename
    var fileNameWithParams = parts[parts.length - 1];
    // Extract the filename if it contains parameters
    var fileName = fileNameWithParams.split('?')[0];
    return fileName;  
  }

  function render_preview_downloadable() {
    var html = '';
    if(multiplImageSelector == null){
      $.each(fileArray, function(i,j){
      
      if(j.imageurl == ""){
        html += '<tr>';
        html += '    <td width="70px"> <div class="upload-priview up-'+ getFileTypeCssClass(j.rawData.type) +'" ></div></td>';
        html += '    <td>'+ j.name +'</td>';
        html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" data-id="'+ i +'" ><?= __('admin.remove') ?></button></td>';
        html += '</tr>';
      }else{
        html += '<tr>';
        html += '    <td width="70px"> <div class="upload-priview up-'+ getFileTypeCssClass(j.type) +'" ></div></td>';
        html += '    <td>'+ j.name +'</td>';
        html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" data-id="'+ i +'" ><?= __('admin.remove') ?></button></td>';
        html += '</tr>';
      }
    })

    $("#priview-table tbody").html(html);
    }else{
      $('div.fileUpload-gallery-s3').html('');
      $('div.fileinput-gallery-s3').html('');
      $.each(fileArray, function(i,j){
        //$($.parseHTML('<img>')).attr('src', j.imageurl).html('div.fileUpload-gallery');
        var img = $('<img>').attr('src', j.imageurl);
      
        $('div.fileUpload-gallery-s3').append(img);
        var input="<input type='hidden' name='multiple_image_s3[]' value='"+j.imageurl+"'>";
        $('div.fileinput-gallery-s3').append(input);
      })
    } 
  }

  async function loadS3ImagesDownloadbale() {
      // Retrieve the dynamic values from the input fields
      const bucketName = document.getElementById('s3_bucket_name').value;
      const region = document.getElementById('s3_region').value;
      const url = `https://${bucketName}.s3.${region}.amazonaws.com`;
      try {
            const response = await fetch(url);
            const text = await response.text();
            const data = (new window.DOMParser()).parseFromString(text, "text/xml");
            const files = data.getElementsByTagName("Key");
            let fileList = "";
              for (let i = 0; i < files.length; i++) {
                  const fileKey = files[i].textContent;
                  const fileUrl = `${url}/${fileKey}`;
                  // Determine file type for preview
                  fileList += `<li class="list-group-item d-flex justify-content-between align-items-center">
                      <span>${fileKey}</span>
                      ${generatePreviewElement(fileKey, fileUrl)}
                      <button class="btn btn-primary btn-sm" onclick="updateS3SrcDownloadbale('${fileUrl}');">Select</button>
                  </li>`;
              }
              document.getElementById('s3-file-list').innerHTML = fileList || '<li class="list-group-item">No files found.</li>';
              // Show the modal after populating it
              new bootstrap.Modal(document.getElementById('s3FilesModal')).show();
          }catch (error) {
            console.error('Error loading S3 images:', error);
            const errorMessageHtml = `
              <li class="list-group-item text-danger">
                Error loading files: ${error.message}
              </li>
              <li class="list-group-item">
                Please check your S3 Bucket Name and S3 Region settings to ensure they are correct.
              </li>`;

            document.getElementById('s3-file-list').innerHTML = errorMessageHtml;
            new bootstrap.Modal(document.getElementById('s3FilesModal')).show();
          }
  }
</script>
<!-- Amazon S3 script-->


<!-- AI Helper Modal - Global -->
<?= ai_helper_modal() ?>

<script>
$(document).ready(function() {
    let logoutInProgress = false; // Add this flag
    
    function storeCurrentPage() {
        if (!window.location.href.includes('login')) {
            localStorage.setItem('admin_last_page_' + Date.now(), window.location.href);
        }
    }
    
    function handleLogout(message) {
        if (logoutInProgress) return; // Prevent duplicate alerts
        logoutInProgress = true;
        
        storeCurrentPage();
        alert(message);
        window.location.reload();
    }
    
    // Listen for forced logout broadcast from other tabs
    $(window).on('storage', function(e) {
        if (e.originalEvent.key === 'force_admin_logout') {
            handleLogout('<?= __('admin.session_ended_redirecting') ?>');
        }
        
        if (e.originalEvent.key === 'admin_login_success') {
            if (window.location.href.includes('login') || !checkIfLoggedIn()) {
                const lastPages = Object.keys(localStorage).filter(key => key.startsWith('admin_last_page_'));
                let redirectUrl = '<?= base_url("admincontrol") ?>';
                
                if (lastPages.length > 0) {
                    const latestPageKey = lastPages.sort().pop();
                    redirectUrl = localStorage.getItem(latestPageKey);
                    lastPages.forEach(key => localStorage.removeItem(key));
                }
                
                window.location.href = redirectUrl;
            }
        }
    });
    
    function checkIfLoggedIn() {
        return document.body.classList.contains('admin-logged-in') || 
               !window.location.href.includes('login');
    }
    
    // Check session validity every 30 seconds
    setInterval(function() {
        if (logoutInProgress) return;
        
        $.ajax({
            global: false,
            url: '<?= base_url("admincontrol/check_session") ?>',
            type: 'POST',
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (!response.logged_in) {
                    handleLogout('<?= __('admin.session_expired_redirecting') ?>');
                }
            },
            error: function() {
                if (!logoutInProgress) {
                    logoutInProgress = true;
                    storeCurrentPage();
                    window.location.reload();
                }
            }
        });
    }, 30000);
});
</script>

<script>
  // Toggle settings panel
  function toggleSettingsPanel() {
      const panel = document.getElementById('settingsPanel');
      const overlay = document.getElementById('settingsOverlay');
      
      panel.classList.toggle('open');
      overlay.classList.toggle('show');
  }



  // Handle menu item clicks
  document.querySelectorAll('.menu-link:not([data-bs-toggle])').forEach(link => {
      link.addEventListener('click', function(e) {
          e.preventDefault();
          // Remove active class from all links
          document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
          // Add active class to clicked link
          this.classList.add('active');
          
          // Close mobile sidebar after selection
          if (window.innerWidth <= 768) {
              const sidebar = document.getElementById('adminSidebar') || document.querySelector('.left-menu');
              if (sidebar) {
                  sidebar.classList.remove('open', 'active');
              }
          }
      });
  });

  // Auto-close mobile sidebar when window is resized
  window.addEventListener('resize', function() {
      if (window.innerWidth > 768) {
          const sidebar = document.getElementById('adminSidebar') || document.querySelector('.left-menu');
          if (sidebar) {
              sidebar.classList.remove('open', 'active');
          }
          const panel = document.getElementById('settingsPanel');
          const overlay = document.getElementById('settingsOverlay');
          if (panel && overlay) {
              panel.classList.remove('open');
              overlay.classList.remove('show');
          }
      }
  });
</script>

<script>
  function showToast(title, message, type = 'success', duration = 4000) {
    // Remove any existing toast
    var existing = document.getElementById('modernToast');
    if (existing) existing.remove();
    
    // Create toast container if it doesn't exist  
    var container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    // Create toast
    var toast = document.createElement('div');
    toast.id = 'modernToast';
    toast.className = 'toast ' + type;
    
    toast.innerHTML = `
        <div class="toast-icon"></div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="removeToast(this.parentElement)">×</button>
        <div class="toast-progress"></div>
    `;
    
    // Add to container
    container.appendChild(toast);

    // Add copy button for error toasts
    if (type === 'error') {
        var actions = document.createElement('div');
        actions.className = 'toast-actions';
        var copyBtn = document.createElement('button');
        copyBtn.type = 'button';
        copyBtn.className = 'toast-copy btn btn-sm btn-secondary';
        copyBtn.textContent = '<?= __('admin.copy') ?>';
        actions.appendChild(copyBtn);
        var closeBtn = toast.querySelector('.toast-close');
        if (closeBtn) {
            toast.insertBefore(actions, closeBtn);
        } else {
            toast.appendChild(actions);
        }

        var messageEl = toast.querySelector('.toast-message');
        if (messageEl) {
            copyBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                var text = messageEl.textContent || messageEl.innerText || '';
                function onCopied() {
                    var original = copyBtn.textContent;
                    copyBtn.disabled = true;
                    copyBtn.textContent = '<?= __('admin.copied') ?>';
                    setTimeout(function(){
                        copyBtn.textContent = original;
                        copyBtn.disabled = false;
                    }, 1500);
                }
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(onCopied).catch(function(){
                        var ta = document.createElement('textarea');
                        ta.value = text;
                        document.body.appendChild(ta);
                        ta.select();
                        try { document.execCommand('copy'); } catch (err) {}
                        document.body.removeChild(ta);
                        onCopied();
                    });
                } else {
                    var ta = document.createElement('textarea');
                    ta.value = text;
                    document.body.appendChild(ta);
                    ta.select();
                    try { document.execCommand('copy'); } catch (err) {}
                    document.body.removeChild(ta);
                    onCopied();
                }
            });
        }
    }
    
    // Trigger slide-in animation with CSS keyframes
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0) scale(1)';
    }, 10);
    
    // Auto remove
    setTimeout(() => {
        removeToast(toast);
    }, duration);
    
    // Click to dismiss (excluding close button)
    toast.addEventListener('click', function(e) {
        if (!e.target.classList.contains('toast-close')) {
            removeToast(this);
        }
    });
  }

  function removeToast(toast) {
      if (toast && toast.parentElement) {
          toast.classList.add('removing');
          setTimeout(() => {
              if (toast.parentElement) {
                  toast.remove();
              }
          }, 300);
      }
  }

  window.showToast = showToast;

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 2025 Horizontal Navigation Active State
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        item.classList.remove('active');
        
        // Check for direct link match
        if (item.href && item.href !== '#' && currentPath.includes(item.getAttribute('href').split('/').pop())) {
            item.classList.add('active');
        }
        
        // Check for dropdown items
        const dropdown = item.closest('.nav-dropdown');
        if (dropdown) {
            const dropdownItems = dropdown.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(dropItem => {
                if (dropItem.href && currentPath.includes(dropItem.getAttribute('href').split('/').pop())) {
                    item.classList.add('active');
                }
            });
        }
    });
});
</script>

        </div> <!-- Close content-area -->
        
        <!-- Footer -->
        <?php
        $db =& get_instance();
        $SiteSetting = $db->Product_model->getSiteSetting();
        ?>
        <div class="admin-footer">
            <div class="container-fluid">
                <div class="admin-footer-inner">
                    <div class="admin-footer-left">
                        <span id="js-err-footer-slot"></span>
                        <span class="admin-footer-copy"><?= $SiteSetting['footer'] ?></span>
                    </div>
                    <div class="admin-footer-right">
                        <a href="<?= base_url('admincontrol/script_details') ?>" class="admin-footer-version">
                            <i class="fas fa-code-branch"></i>
                            <?= __('admin.script_version') ?> <?= SCRIPT_VERSION ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Close main-content -->
</div> <!-- Close admin-wrapper -->

<!-- Tour System CSS -->
<style>
/* Enhanced Side Panel Tour System */
.tour-side-panel {
    position: fixed;
    top: 0;
    right: -400px;
    width: 400px;
    height: 100vh;
    background: #ffffff;
    box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
    z-index: 1060;
    transition: right 0.3s ease;
    border-left: 1px solid #e9ecef;
    display: flex;
    flex-direction: column;
    opacity: 0;
    visibility: hidden;
}

.tour-side-panel.show {
    right: 0;
    opacity: 1;
    visibility: visible;
}

.tour-panel-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
    flex-shrink: 0;
}

.tour-panel-title {
    color: #495057;
    font-size: 1.1rem;
}

.tour-progress-info .progress {
    border-radius: 10px;
}

.tour-panel-body {
    flex: 1;
    padding: 1.25rem;
    overflow-y: auto;
}

.tour-step-content {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.tour-step-header {
    display: flex;
    justify-content-between;
    align-items-flex-start;
}

.tour-step-title {
    color: #212529;
    font-weight: 600;
    flex: 1;
    margin-right: 1rem;
}

.tour-step-number .badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.tour-message-content {
    color: #6c757d;
    line-height: 1.6;
}

.tour-panel-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
    flex-shrink: 0;
}

.tour-panel-footer .btn {
    font-size: 0.875rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tour-side-panel {
        width: 100%;
        right: -100%;
    }
}
</style>

<!-- Tour System JavaScript Functions -->
<script>
// Global functions for loading tour and welcome systems
function loadWelcomeSystem() {
    if (window.welcomeSystemInstance) {
        window.welcomeSystemInstance.reopenWelcome();
        return;
    }
    
    const script = document.createElement('script');
    script.src = '<?= base_url('assets/template/js/welcome-modal-system.js') ?>?v=<?= SCRIPT_VERSION ?>';
    script.onload = function() {
        if (window.WelcomeModalSystem) {
            window.welcomeSystemInstance = new window.WelcomeModalSystem();
            window.welcomeSystemInstance.init().then(() => {
                window.welcomeSystemInstance.reopenWelcome();
            });
        }
    };
    document.head.appendChild(script);
}

// Tour System Class - Inline Implementation
class AdminTour {
    constructor() {
        this.currentStep = 0;
        this.currentTour = null;
        this.tours = {};
        this.isInitialized = false;
        this.isActive = false;
    }
    
    async init() {
        if (window.tourData) {
            this.tours = window.tourData;
            this.isInitialized = true;
            this.setupEventListeners();
            return true;
        }
        return false;
    }

    setupEventListeners() {
        $(document).on('click', '#tourNext', () => this.nextStep());
        $(document).on('click', '#tourPrevious', () => this.previousStep());
        $(document).on('click', '#tourSkip', () => this.skipTour());
        $(document).on('click', '#tourFinish', () => this.finishTour());
        $(document).on('click', '#tourCloseBtn', () => this.closeTour());
    }

    startTour() {
        if (!this.isInitialized) {
            return;
        }
        
        const currentPath = window.location.pathname;
        const appBase = '<?= rtrim(parse_url(base_url(), PHP_URL_PATH), '/') ?>/';
        let pathKey = currentPath.startsWith(appBase)
            ? currentPath.slice(appBase.length)
            : currentPath.replace(/^\//, '');
        pathKey = pathKey.replace(/\/$/, '');

        this.currentTour = this.tours[pathKey];
        
        if (!this.currentTour) {
            if (typeof showToast === 'function') {
                showToast('<?= addslashes(__('admin.no_tour_available')) ?>', '<?= addslashes(__('admin.no_tour_available_desc')) ?>', 'info', 3000);
            }
            return;
        }

        this.currentStep = 0;
        this.isActive = true;
        this.showSidePanel();
        this.showStep();
    }

    showSidePanel() {
        $('#tourSidePanel').css({
            'display': 'flex',
            'opacity': '1',
            'visibility': 'visible'
        }).addClass('show');
        $('body').addClass('tour-active');
    }

    hideSidePanel() {
        $('#tourSidePanel').removeClass('show');
        setTimeout(() => {
            $('#tourSidePanel').css({
                'display': 'none',
                'opacity': '0',
                'visibility': 'hidden'
            });
        }, 300);
        $('body').removeClass('tour-active');
    }

    showStep() {
        const step = this.currentTour[this.currentStep];
        if (!step) return;

        $('#tourStepTitle').text(step.title || `Step ${this.currentStep + 1}`);
        $('#tourStepNumber').text(this.currentStep + 1);
        $('#tourMessage').html(step.message);
        
        this.updateProgress();
        this.updateButtons();
        
        if (step.tip) {
            $('#tourTipText').html(step.tip);
            $('#tourTips').show();
        } else {
            $('#tourTips').hide();
        }
        
        $('#tourHighlightInfo').hide();
    }

    updateProgress() {
        const totalSteps = this.currentTour.length;
        const progress = ((this.currentStep + 1) / totalSteps) * 100;
        $('#tourProgressBar').css('width', progress + '%');
        $('#tourProgressText').text(`Step ${this.currentStep + 1} of ${totalSteps}`);
    }

    updateButtons() {
        const totalSteps = this.currentTour.length;
        $('#tourPrevious').toggle(this.currentStep > 0);
        $('#tourNext').toggle(this.currentStep < totalSteps - 1);
        $('#tourFinish').toggle(this.currentStep === totalSteps - 1);
    }

    nextStep() {
        const totalSteps = this.currentTour.length;
        if (this.currentStep < totalSteps - 1) {
            this.currentStep++;
            this.showStep();
        }
    }

    previousStep() {
        if (this.currentStep > 0) {
            this.currentStep--;
            this.showStep();
        }
    }

    skipTour() {
        this.closeTour();
    }

    finishTour() {
        this.closeTour();
        setTimeout(() => {
            if (typeof showToast === 'function') {
                showToast('<?= addslashes(__('admin.tour_completed')) ?>', '<?= addslashes(__('admin.tour_completed_desc')) ?>', 'success', 3000);
            }
        }, 100);
    }

    closeTour() {
        this.isActive = false;
        this.hideSidePanel();
    }
}

// Make AdminTour globally available
window.AdminTour = AdminTour;

function loadTourSystem() {
    if (window.tourInstance) {
        window.tourInstance.startTour();
        return;
    }
    
    window.tourInstance = new AdminTour();
    window.tourInstance.init().then(() => {
        window.tourInstance.startTour();
    });
}

function safeShowToast(type, message) {
    if (typeof showToast === 'function') {
        showToast('Notification', message, type, 3000);
    } else {
        console.log(message);
    }
}

// Define tour data for JavaScript
window.tourData = {

    // ── Store ─────────────────────────────────────────────────────────────
    'admincontrol/store_dashboard': [
        { title: "Welcome to Store Management",       message: "<?= addslashes(__('admin.tour_store_dashboard_s1')) ?>", tip: "Bookmark this page as your daily starting point — it gives you the fastest snapshot of your store's health." },
        { title: "KPI Overview Cards",                message: "<?= addslashes(__('admin.tour_store_dashboard_s2')) ?>", tip: "Click any KPI card to drill into the underlying report for that metric." },
        { title: "Quick-Access Hub Cards",            message: "<?= addslashes(__('admin.tour_store_dashboard_s3')) ?>", tip: "These cards link directly to each store section, saving you from navigating through menus." },
        { title: "Store Sub-Navigation",              message: "<?= addslashes(__('admin.tour_store_dashboard_s4')) ?>", tip: "The sub-nav is always visible on every store page — use it instead of the browser back button." }
    ],
    'admincontrol/listproduct': [
        { title: "Your Product Catalogue",            message: "<?= addslashes(__('admin.tour_store_products_s1')) ?>", tip: "Use the search and filter bar to quickly locate a specific product by name, SKU, or category." },
        { title: "Adding a New Product",              message: "<?= addslashes(__('admin.tour_store_products_s2')) ?>", tip: "Fill in all fields including SEO meta title and description to improve your store's search visibility." },
        { title: "Inventory & Variants",              message: "<?= addslashes(__('admin.tour_store_products_s3')) ?>", tip: "Enable inventory tracking per variant so the system automatically shows 'Out of Stock' when levels hit zero." }
    ],
    'admincontrol/listorders': [
        { title: "Store Orders",                      message: "<?= addslashes(__('admin.tour_store_orders_s1')) ?>", tip: "New orders appear at the top. Set up email notifications so you're alerted immediately for every new purchase." },
        { title: "Managing an Order",                 message: "<?= addslashes(__('admin.tour_store_orders_s2')) ?>", tip: "Always add a tracking number when fulfilling an order — it triggers an automatic shipping notification to the customer." },
        { title: "Filtering & Exporting",             message: "<?= addslashes(__('admin.tour_store_orders_s3')) ?>", tip: "Export filtered orders monthly for your accounting records. Use the status filter to find orders that need action." }
    ],

    // ── Membership ────────────────────────────────────────────────────────
    'membership/membership_orders': [
        { title: "Membership Dashboard",              message: "<?= addslashes(__('admin.tour_membership_orders_s1')) ?>", tip: "Check this page weekly to monitor churn — a spike in cancellations is an early warning sign that needs attention." },
        { title: "Reading the Subscription List",     message: "<?= addslashes(__('admin.tour_membership_orders_s2')) ?>", tip: "Filter by 'Expiring Soon' to proactively reach out to members before their renewal date to reduce churn." },
        { title: "Navigating Membership Modules",     message: "<?= addslashes(__('admin.tour_membership_orders_s3')) ?>", tip: "Always configure Plans before promoting membership — members need a plan to subscribe to." }
    ],
    'membership/plans': [
        { title: "Membership Plans",                  message: "<?= addslashes(__('admin.tour_membership_plans_s1')) ?>", tip: "Keep plan names short and benefit-focused. 'Pro — Unlimited Campaigns' converts better than 'Plan B'." },
        { title: "Creating a Plan",                   message: "<?= addslashes(__('admin.tour_membership_plans_s2')) ?>", tip: "Offer both monthly and annual billing for the same tier — annual plans typically reduce churn significantly." },
        { title: "Multiple Plan Tiers",               message: "<?= addslashes(__('admin.tour_membership_plans_s3')) ?>", tip: "A 3-tier structure (Basic / Pro / Business) is the industry standard. It anchors perception of value on the middle tier." }
    ],
    'membership/settings': [
        { title: "Membership Settings",               message: "<?= addslashes(__('admin.tour_membership_settings_s1')) ?>", tip: "Set a 3-7 day grace period for failed payments — most card failures are temporary and auto-resolve." },
        { title: "Billing & Notification Config",     message: "<?= addslashes(__('admin.tour_membership_settings_s2')) ?>", tip: "Enable renewal reminder emails 7 days and 1 day before expiry — this significantly reduces involuntary churn." }
    ],

    // ── MLM ───────────────────────────────────────────────────────────────
    'admincontrol/mlm_settings': [
        { title: "MLM Network Settings",              message: "<?= addslashes(__('admin.tour_mlm_settings_s1')) ?>", tip: "Start with 2-3 levels. Deep pyramids can be complex to explain to affiliates and may raise compliance concerns." },
        { title: "Setting Commission Rates",          message: "<?= addslashes(__('admin.tour_mlm_settings_s2')) ?>", tip: "A typical split is 10% Level 1, 5% Level 2, 2% Level 3. Higher rates for deeper levels rarely make financial sense." },
        { title: "Enabling or Disabling MLM",         message: "<?= addslashes(__('admin.tour_mlm_settings_s3')) ?>", tip: "Disabling MLM is non-destructive — all historical commission data is preserved and can be re-activated anytime." }
    ],
    'admincontrol/mlm_levels': [
        { title: "MLM Level Hierarchy",               message: "<?= addslashes(__('admin.tour_mlm_levels_s1')) ?>", tip: "Level numbers are not visible to end users — use the description field to explain each tier in plain language." },
        { title: "Adding Levels",                     message: "<?= addslashes(__('admin.tour_mlm_levels_s2')) ?>", tip: "Adding levels takes effect immediately for new commissions. Existing earnings are not recalculated retroactively." },
        { title: "Jump Level Thresholds",             message: "<?= addslashes(__('admin.tour_mlm_levels_s3')) ?>", tip: "Setting jump thresholds too high can discourage new affiliates. Start low and raise thresholds as the network matures." }
    ],

    // ── Award Level ───────────────────────────────────────────────────────
    'admincontrol/award_level': [
        { title: "Award Level System",                message: "<?= addslashes(__('admin.tour_award_level_s1')) ?>", tip: "Display level badges on affiliate profiles to create social proof and friendly competition within your network." },
        { title: "Creating a Level",                  message: "<?= addslashes(__('admin.tour_award_level_s2')) ?>", tip: "Name levels with aspirational titles like 'Silver Partner', 'Gold Partner', 'Platinum Partner' for motivation." },
        { title: "Level Progression",                 message: "<?= addslashes(__('admin.tour_award_level_s3')) ?>", tip: "Review level thresholds quarterly — as your affiliate base grows, adjust thresholds to maintain the pyramid shape." }
    ],
    'admincontrol/level_analysis': [
        { title: "Level Analysis Overview",           message: "<?= addslashes(__('admin.tour_level_analysis_s1')) ?>", tip: "Run this analysis monthly alongside your payout cycle to identify who deserves recognition or outreach." },
        { title: "Affiliate Distribution",            message: "<?= addslashes(__('admin.tour_level_analysis_s2')) ?>", tip: "If too many affiliates are clustered at the bottom level, consider lowering the threshold for Level 2 to improve morale." },
        { title: "Acting on the Data",                message: "<?= addslashes(__('admin.tour_level_analysis_s3')) ?>", tip: "A personal congratulatory email when an affiliate levels up has a measurable impact on long-term retention." }
    ],

    // ── SaaS ──────────────────────────────────────────────────────────────
    'admincontrol/saas_setting': [
        { title: "SaaS Platform Settings",            message: "<?= addslashes(__('admin.tour_saas_settings_s1')) ?>", tip: "Keep your admin fee competitive with market rates — too high and vendors will look for alternative platforms." },
        { title: "Vendor Deposit Configuration",      message: "<?= addslashes(__('admin.tour_saas_settings_s2')) ?>", tip: "Offer at least 2 deposit methods (e.g. Bank Transfer + PayPal) to reduce friction for vendors in different regions." },
        { title: "Vendor Permissions",                message: "<?= addslashes(__('admin.tour_saas_settings_s3')) ?>", tip: "Start with conservative permissions and grant more access as vendors build a track record on your platform." }
    ],
    'admincontrol/vendor_deposits': [
        { title: "Vendor Deposit Transactions",       message: "<?= addslashes(__('admin.tour_vendor_deposits_s1')) ?>", tip: "Process pending deposits within 24 hours — slow approval times are a common vendor complaint." },
        { title: "Approving a Deposit",               message: "<?= addslashes(__('admin.tour_vendor_deposits_s2')) ?>", tip: "Always verify the transaction ID against your bank statement or payment dashboard before approving." },
        { title: "Filtering Deposits",                message: "<?= addslashes(__('admin.tour_vendor_deposits_s3')) ?>", tip: "Export filtered deposits monthly to reconcile vendor balances with your own accounting records." }
    ],

    // ── Campaigns ─────────────────────────────────────────────────────────
    'integration/integration_tools': [
        { title: "Welcome to Marketing Tools",        message: "<?= addslashes(__('admin.tour_marketing_welcome')) ?>",    tip: "This is your central hub for creating and managing affiliate marketing campaigns. Everything you need to drive traffic and track conversions is here." },
        { title: "Choose Your Campaign Type",         message: "Select the campaign type that fits your goal — Banner for visual impact, Text for SEO, Link for deep-linking, and Video for rich media promotion.", tip: "Start with a Link campaign to test tracking, then expand to Banner campaigns once your creative assets are ready." },
        { title: "Manage All Campaigns",              message: "Every campaign you create appears in the list below with live performance stats: views, clicks, conversions, and conversion rate.", tip: "Sort by conversion rate to identify your top-performing campaigns and allocate more affiliate traffic to them." },
        { title: "Integration & Tracking Tools",      message: "Use the Programs and Categories tabs in the sub-navigation to organise your campaigns and define commission structures for each.", tip: "Well-organised categories and programs make it easier for affiliates to find relevant campaigns and promote them effectively." }
    ],
    'integration/programs': [
        { title: "Marketing Programs",                message: "<?= addslashes(__('admin.tour_campaigns_programs_s1')) ?>", tip: "Create separate programs for different product lines or affiliate tiers — it gives you granular control over commission costs." },
        { title: "Creating a Program",                message: "<?= addslashes(__('admin.tour_campaigns_programs_s2')) ?>", tip: "Percentage-based commissions scale with order value and are easier to manage than flat fees for variable-price products." },
        { title: "Programs and Affiliates",           message: "<?= addslashes(__('admin.tour_campaigns_programs_s3')) ?>", tip: "Review program performance monthly — low-converting programs may need a higher commission rate to attract top affiliates." }
    ],
    'integration/integration_category': [
        { title: "Campaign Categories",               message: "<?= addslashes(__('admin.tour_campaigns_categories_s1')) ?>", tip: "Keep category names broad enough to cover multiple campaigns but specific enough to be meaningful to affiliates." },
        { title: "Using Categories Effectively",      message: "<?= addslashes(__('admin.tour_campaigns_categories_s2')) ?>", tip: "Archive inactive categories rather than deleting them — historical campaign data stays intact." }
    ],

    // ── Admin Management ──────────────────────────────────────────────────
    'admincontrol/admin_user': [
        { title: "Administrator Accounts",            message: "<?= addslashes(__('admin.tour_admin_user_s1')) ?>", tip: "Keep the number of Super Administrator accounts minimal — use scoped Roles for day-to-day team members." },
        { title: "Roles vs Custom Permissions",       message: "<?= addslashes(__('admin.tour_admin_user_s2')) ?>", tip: "Roles are the most scalable approach for teams. Reserve Custom permissions only for one-off exceptions." },
        { title: "Adding an Admin",                   message: "<?= addslashes(__('admin.tour_admin_user_s3')) ?>", tip: "Assign the most restrictive role that still lets the admin do their job — you can always expand permissions later." }
    ],
    'admincontrol/admin_roles': [
        { title: "Admin Roles",                       message: "<?= addslashes(__('admin.tour_admin_roles_s1')) ?>", tip: "Name roles after job functions (Finance Admin, Support Admin) not people — roles should outlast any individual." },
        { title: "Configuring Permissions",           message: "<?= addslashes(__('admin.tour_admin_roles_s2')) ?>", tip: "Review role permissions whenever you add a new module — new features often default to unrestricted access." },
        { title: "Creating a Role",                   message: "<?= addslashes(__('admin.tour_admin_roles_s3')) ?>", tip: "Use 'Import Demo Roles' as a starting template then customise — it's faster than building from scratch." }
    ],

    // ── Wallet ────────────────────────────────────────────────────────────
    'admincontrol/mywallet': [
        { title: "Wallet Transactions",               message: "<?= addslashes(__('admin.tour_wallet_transactions_s1')) ?>", tip: "Reconcile this list with your payment processor records at the end of each payout cycle." },
        { title: "Finding Transactions",              message: "<?= addslashes(__('admin.tour_wallet_transactions_s2')) ?>", tip: "Save a filtered URL for your most common search (e.g. Unpaid commissions this month) for quick daily access." },
        { title: "Understanding Statuses",            message: "<?= addslashes(__('admin.tour_wallet_transactions_s3')) ?>", tip: "Regularly audit 'In Wallet' entries — entries staying there too long may indicate affiliates who need payout outreach." }
    ],
    'admincontrol/wallet_requests_list': [
        { title: "Withdrawal Requests",               message: "<?= addslashes(__('admin.tour_wallet_requests_s1')) ?>", tip: "Set a fixed weekly payout day and communicate it to affiliates — it reduces the volume of 'where is my payment?' support tickets." },
        { title: "Processing a Request",              message: "<?= addslashes(__('admin.tour_wallet_requests_s2')) ?>", tip: "Always add the payment transaction ID when approving — affiliates can verify it and it resolves most payment disputes." },
        { title: "Batch Payouts",                     message: "<?= addslashes(__('admin.tour_wallet_requests_s3')) ?>", tip: "For networks with 20+ weekly withdrawal requests, Mass Payout Export reduces processing time from hours to minutes." }
    ],
    'admincontrol/mass_payout': [
        { title: "Mass Payout Export",                message: "<?= addslashes(__('admin.tour_mass_payout_s1')) ?>", tip: "Only pending requests are included in the export — approve or reject requests before exporting to keep the batch clean." },
        { title: "The Export & Pay Process",          message: "<?= addslashes(__('admin.tour_mass_payout_s2')) ?>", tip: "Keep the downloaded CSV — you will need it to map transaction IDs when uploading the return file." },
        { title: "Importing the Return File",         message: "<?= addslashes(__('admin.tour_mass_payout_s3')) ?>", tip: "Upload the return file on the same day you process payments to keep your records in sync." }
    ],
    'admincontrol/withdrawal_payment_gateways': [
        { title: "Withdrawal Gateways",               message: "<?= addslashes(__('admin.tour_withdrawal_gateways_s1')) ?>", tip: "Offer regional payment methods — affiliates in different countries prefer different payout options." },
        { title: "Installing a Gateway",              message: "<?= addslashes(__('admin.tour_withdrawal_gateways_s2')) ?>", tip: "Download the Demo Gateway ZIP first to understand the required file structure before building a custom gateway." },
        { title: "Configuring a Gateway",             message: "<?= addslashes(__('admin.tour_withdrawal_gateways_s3')) ?>", tip: "Set a minimum payout amount per gateway — this discourages micro-withdrawals that create unnecessary processing fees." }
    ],
    'admincontrol/wallet_setting': [
        { title: "Wallet Settings",                   message: "<?= addslashes(__('admin.tour_wallet_settings_s1')) ?>", tip: "Review these settings before your first payout cycle — getting them right early prevents costly process changes later." },
        { title: "Minimum Withdrawal Threshold",      message: "<?= addslashes(__('admin.tour_wallet_settings_s2')) ?>", tip: "A $20-$50 minimum threshold is standard for most affiliate programs. Too high and affiliates lose motivation." },
        { title: "Auto-Withdrawal via Cron",          message: "<?= addslashes(__('admin.tour_wallet_settings_s3')) ?>", tip: "Only enable auto-withdrawal once you have validated your payment gateway integration thoroughly in a test environment." }
    ]
};

// Pre-alias tour keys using the application base path so lookups always work
(function() {
    const appBase = '<?= rtrim(parse_url(base_url(), PHP_URL_PATH), '/') ?>/';
    const currentPath = window.location.pathname;
    const pathKey = currentPath.startsWith(appBase)
        ? currentPath.slice(appBase.length).replace(/\/$/, '')
        : currentPath.replace(/^\//, '').replace(/\/$/, '');
    if (pathKey && !window.tourData[pathKey]) {
        // Try stripping any leading segment (legacy installs with different sub-paths)
        const fallback = pathKey.replace(/^.*?\/(?=admincontrol|membership|integration)/, '');
        if (fallback !== pathKey && window.tourData[fallback]) {
            window.tourData[pathKey] = window.tourData[fallback];
        }
    }
})();
</script>

<!-- Global AJAX Loading Overlay (light veil — avoids “second full page” after HTML already painted) -->
<div id="globalLoadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.35); z-index:9999; justify-content:center; align-items:center; opacity:0; transition:opacity 0.12s ease;">
    <div class="text-center">
        <div class="spinner-border text-primary" role="status" style="width:3rem; height:3rem;"></div>
        <div class="mt-2 fw-bold text-muted" id="globalLoadingText"><?= __('admin.loading') ?? 'Loading...' ?></div>
    </div>
</div>

<!-- Standardized Delete Confirmation Modal -->
<div class="modal fade" id="globalDeleteConfirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h6 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i><?= __('admin.confirm_delete') ?? 'Confirm Delete' ?></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-0" id="globalDeleteMessage"><?= __('admin.are_you_sure_delete') ?? 'Are you sure you want to delete this item?' ?></p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><?= __('admin.cancel') ?? 'Cancel' ?></button>
                <button type="button" class="btn btn-danger btn-sm" id="globalDeleteConfirmBtn"><i class="fas fa-trash me-1"></i><?= __('admin.delete') ?? 'Delete' ?></button>
            </div>
        </div>
    </div>
</div>

<script>
// Global loading helpers
window.showLoading = function(text) {
    if (text) $('#globalLoadingText').text(text);
    clearTimeout(window._ajaxGlobalOverlayFadeTimer);
    var el = document.getElementById('globalLoadingOverlay');
    if (!el) return;
    el.style.display = 'flex';
    window.requestAnimationFrame(function() {
        el.style.opacity = '1';
    });
};
window.hideLoading = function() {
    var el = document.getElementById('globalLoadingOverlay');
    if (!el) return;
    clearTimeout(window._ajaxGlobalOverlayFadeTimer);
    el.style.opacity = '0';
    window._ajaxGlobalOverlayFadeTimer = setTimeout(function() {
        if (window._ajaxGlobalActive === 0) {
            el.style.display = 'none';
        }
    }, 130);
};

// Global AJAX → full-screen overlay (shared by every admin page).
//
// jQuery ajaxStart/ajaxStop caused double flicker: they mean "first request of a
// burst started" and "all requests finished". Two calls that run one-after-another
// (very common after each menu navigation: init + table + widget, etc.) produce
// ajaxStop → hide, then ajaxStart again → show — looks like loading twice.
//
// Fix: count in-flight *global* requests (ajaxSend/ajaxComplete). Short show delay:
// a long delay (e.g. 500ms) made the HTML paint first, then the overlay — felt like
// a second refresh. ~70ms keeps “still loading” tied to the same navigation beat.
// Debounced hide avoids off→on flicker when chained requests gap slightly.
window._ajaxGlobalActive = 0;
window._ajaxGlobalShowDelayMs = 70;
$(document).ajaxSend(function(_e, _jqXHR, settings) {
    if (settings && settings.global === false) return;
    window._ajaxGlobalActive++;
    clearTimeout(window._ajaxGlobalHideTimer);
    if (window._ajaxGlobalActive === 1) {
        window._ajaxGlobalShowTimer = setTimeout(function() {
            if (window._ajaxGlobalActive > 0) window.showLoading();
        }, window._ajaxGlobalShowDelayMs);
    }
}).ajaxComplete(function(_e, _jqXHR, settings) {
    if (settings && settings.global === false) return;
    window._ajaxGlobalActive = Math.max(0, window._ajaxGlobalActive - 1);
    if (window._ajaxGlobalActive === 0) {
        clearTimeout(window._ajaxGlobalShowTimer);
        window._ajaxGlobalHideTimer = setTimeout(function() {
            if (window._ajaxGlobalActive === 0) window.hideLoading();
        }, 120);
    }
});

// Global delete confirmation
window.confirmDelete = function(message, callback) {
    if (message) $('#globalDeleteMessage').text(message);
    var modal = new bootstrap.Modal(document.getElementById('globalDeleteConfirm'));
    $('#globalDeleteConfirmBtn').off('click').on('click', function() {
        modal.hide();
        if (typeof callback === 'function') callback();
    });
    modal.show();
};
</script>

<!-- Global FormData Filter Function -->
<script>
// Global formDataFilter function used across all admin forms
if (typeof formDataFilter === 'undefined') {
    function formDataFilter(formData) {
        // Remove empty values and clean up form data
        var filteredData = new FormData();
        for (var pair of formData.entries()) {
            // Always include fields that need to support being cleared to empty:
            // image/media fields, and rich-text / textarea content fields.
            var key = pair[0];
            var alwaysInclude = (
                key.includes('image') ||
                key.includes('logo') ||
                key.includes('banner') ||
                key.includes('icon') ||
                key.includes('favicon') ||
                key.includes('thumbnail') ||
                key.includes('gallery') ||
                key.includes('[content]') ||       // WYSIWYG / section content fields
                key.includes('_content') ||        // about_content, contact_content, policy_content
                key.endsWith('[content]')
            );
            if (alwaysInclude) {
                filteredData.append(key, pair[1]);
            } else if (pair[1] !== '' && pair[1] !== null && pair[1] !== undefined) {
                filteredData.append(key, pair[1]);
            }
        }
        return filteredData;
    }
}
</script>

<!-- V14 Theme Toggle & Dashboard Animations -->
<script src="<?= base_url('assets/template/js/theme-toggle.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/dashboard-animations.js') ?>?v=<?= av() ?>"></script>

</body>
</html>