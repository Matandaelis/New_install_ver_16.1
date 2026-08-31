            </div>
            <!-- Content Area End -->
        </div>
        <!-- Main Content End -->

<?php 
$db =& get_instance(); 
$userdetails = $db->Product_model->userdetails('user'); 
$SiteSetting = $db->Product_model->getSiteSetting();
$global_script_status = (array) json_decode($SiteSetting['global_script_status'], true);

if (in_array('affiliate', $global_script_status)) {
    echo $SiteSetting['global_script'];
}

$user_footer_color = $db->Product_model->getSettings('theme','user_footer_color');
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.add('pb-5');
});
</script>

<!-- Footer Section Start -->
<footer class="fixed-bottom py-3 border-top shadow bg-white">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-muted small">
    
    <!-- Left side: Footer text -->
    <div class="footer-text text-center text-md-start">
      <?= $SiteSetting['footer'] ?>
    </div>

    <!-- Right side: Contact link only -->
    <?php if (isShowUserControlParts($userdashboard_settings['contact_us_page'] ?? [])): ?>
    <div class="footer-links text-center text-md-end">
      <a href="<?= base_url('usercontrol/contact-us'); ?>" class="text-muted text-decoration-none">
        <i class="fas fa-envelope me-1"></i> <?= __('user.page_title_contact_admin') ?>
      </a>
    </div>
    <?php endif; ?>
    
  </div>
</footer>
<!-- Footer Section End -->

    </div>
    <!-- User Wrapper End -->

<!-- flash message div -->
<div class="print-message">
	<?php print_message($this); ?>
</div>
<!-- flash message div -->


<!--flash message script-->
<script>
  function showPrintMessage(message, type, redirectUrl = "") {
    let messageStr = '';
    if (type === 'success') {
      messageStr = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                      <i class="bi bi-check-circle-fill me-2"></i>${message}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
    } else if (type === 'error') {
      messageStr = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <i class="bi bi-exclamation-triangle-fill me-2"></i>${message}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
    } else {
      throw new Error('Invalid message type');
    }
    $(".print-message").html(messageStr);
  }
</script>
<!--flash message script-->


<!--Remove alert message script-->
<script>
  // Using jQuery to fade and remove the alert after 5 seconds
		$(document).ready(function() {
		  setInterval(function() {
		    $(".print-message .alert").each(function() {
		      const $this = $(this);
		      if (!$this.data('polled')) {
		        $this.data('polled', true);
		        window.setTimeout(function() {
		          $this.fadeTo(500, 0).slideUp(500, function() {
		            $(this).remove();
		          });
		        }, 5000);
		      }
		    });
		  }, 500);
		});
		
		try {
		  if (typeof redirectUrl !== "undefined" && redirectUrl !== "") {
		    window.setTimeout(function() {
		      window.location.href = redirectUrl;
		    }, 4000);
		  }
		} catch (error) {
		  console.error("An error occurred: ", error);
		}
</script>
<!--Remove alert message script-->




<!-- Remember last tab script -->
<script>
    $(document).ready(function() {
        // Save active tab in localStorage when a tab is clicked
        $('a[data-bs-toggle="pill"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });

        // Retrieve active tab from localStorage and display it
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab && $('#pills-tab a[href="' + activeTab + '"]').length) {
            $('#pills-tab a[href="' + activeTab + '"]').tab('show');
        } else {
            // Show the first tab if no active tab is saved
            $('#pills-tab a[data-bs-toggle="pill"]:first').tab('show');
        }
    });
</script>
<!-- End Remember last tab script -->



<script type="text/javascript">
	if (typeof $.fn.select2 !== 'undefined') { $(".select2-input").select2(); }
</script>

  <script src="<?= base_url('assets/template/js/external.min.js') ?>"></script>
  <script src="<?= base_url('assets/template/js/dashboard.js') ?>"></script>
  <script src="<?= base_url('assets/template/js/setting.js') ?>"></script>
  <script src="<?= base_url('assets/template/js/vendor.js') ?>"></script>
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
	<script src="<?= base_url('assets/template/js/app.js'); ?>"></script>

<!--pdf/excel/image js-->
<script src="<?= base_url('assets/template/js/xlsx.full.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/jspdf.umd.min.js'); ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/jspdf.plugin.autotable.min.js'); ?>?v=<?= av() ?>"></script>
<!--pdf/excel/image js-->

<!--summNote js files-->
<script src="<?= base_url('assets/template/summernote/summernote-lite.min.js'); ?>"></script>
<!--summNote js files-->


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

<!--Summernote - Call the toggleStyle function directly to execute-->
<script>
  function toggleStyle() {
    var styleEle = $("style#fixed");
    if (styleEle.length == 0) {
      $("<style id=\"fixed\">.note-editor .dropdown-toggle::after { all: unset; } .note-editor .note-dropdown-menu { box-sizing: content-box; } .note-editor .note-modal-footer { box-sizing: content-box; }</style>")
        .prependTo("body");
    } else {
      styleEle.remove();
    }
  }
  toggleStyle();
</script>
<!--Call the toggleStyle function directly to execute-->
	

<!--Enable tooltips everywhere-->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<!--Enable tooltips everywhere-->

<script type="text/javascript">
	$(document).delegate('[name="slug"]','keyup',function(){
		var slug = $(this).val();
		var base_url = "<?= base_url() ?>";
		$('#slugtting form').find('.slug-url input').val(base_url+slug);
	});

	$(document).delegate('.btn-model-slug,.dashboard-model-slug', 'click', function(){
		$form = $('#slugtting form');
		$form[0].reset();
		$form.find(".alert").remove();
		$form.find(".has-error").removeClass("has-error");
		$form.find("span.text-danger").remove();

		var type = $(this).attr('data-type');
		var related_id = $(this).attr('data-related-id');
		var target = $(this).attr('data-input-class');

		$this = $(this);
		$.ajax({
			url:"<?php echo base_url('usercontrol/get_slug') ?>",
			type:'POST',
			dataType:'json',
			data:{
				type: type,
				related_id: related_id
			},
			success:function(json){

				$form.find('.slug-url').hide();
				$form.find('.btn-delete-slug').hide();

				if(json['success']){
					$form.find('.slug-url a').attr('copyToClipboard',json.slug_url);
					$form.find('.slug-url input').val(json.slug_url);
					$form.find('.slug-url').show();
					$form.find('.btn-delete-slug').show();
					$('#slugtting').find('[name="slug"]').val(json['slug']);
				}

				$('#slugtting').find('[name="type"]').val(type);
				$('#slugtting').find('[name="related_id"]').val(related_id);
				$('#slugtting').find('[name="target"]').val(target);

				$('#slugtting').modal('show',{'keyboard':false} );
			},
		})
	});
	$('#slugtting').delegate('form', 'submit', function(e){
		e.preventDefault();

		$this = $(this);
		$target = $this.find('[name="target"]').val();

		$.ajax({
			url:$this.attr('action'),
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			success:function(json){
				$container = $this;
				$container.find(".has-error").removeClass("has-error");
				$container.find("span.text-danger").remove();
				$container.find(".alert").remove();

				if(json['errors']){
					$.each(json['errors'], function(i,j){
						$ele = $container.find('[name="'+ i +'"]');
						if($ele){
							$ele.parents(".form-group").addClass("has-error");
							$ele.after("<span class='text-danger'>"+ j +"</span>");
						}
					})
					$this.find('.slug-url').hide();
				}

				if(json['error']){
					$this.find('.modal-body').prepend('<div class="alert bg-danger text-white">'+json['error']+'</div>');
				}

				if(json['success']){
					$.each($('.'+$target), function(k,v){
						if($(v).attr('data-addition-url')){
							var addition_url = $(v).attr('data-addition-url');
							$(v).val(json.slug_url + addition_url);
							$(v).next('[copyToClipboard]').attr('copyToClipboard', json.slug_url + addition_url);
						}else{
							$(v).val(json.slug_url);
							$(v).next('[copyToClipboard]').attr('copyToClipboard', json.slug_url);
						}
					});

					$this.find('.slug-url').show();
					$this.find('.slug-url a').attr('copyToClipboard',json.slug_url);
					$this.find('.slug-url input').val(json.slug_url);
					$this.find('.modal-body').prepend('<div class="alert alert-success">'+json['success']+'</div>');
				}
			},
		})
	});

	$('#slugtting').delegate('.btn-delete-slug', 'click', function(){
		if(!confirm('<?= __('user.are_you_sure') ?>')) return false;

		$this = $('#slugtting form');
		$this_btn = $(this);
		$target = $this.find('[name="target"]').val();

		$.ajax({
			url: '<?php echo base_url('/usercontrol/delete_slug') ?>',
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			success:function(json){
				$container = $this;
				$container.find(".alert").remove();

				if(json['error']){
					$this.find('.modal-body').prepend('<div class="alert alert-danger">'+json['error']+'</div>');
				}

				if(json['success']){
					$.each($('.'+$target), function(k,v){
						if($(v).attr('data-addition-url')){
							var addition_url = $(v).attr('data-addition-url');
							$(v).val(json.url + addition_url);
							$(v).next('[copyToClipboard]').attr('copyToClipboard', json.url + addition_url);
						}else{
							$(v).val(json.url);
							$(v).next('[copyToClipboard]').attr('copyToClipboard', json.url);
						}
					});

					$this.find('.slug-url').hide();
					$this.find('.slug-url input').val(json.url);
					$this.find('[name="slug"]').val('');
					$this_btn.hide();
					$this.find('.modal-body').prepend('<div class="alert alert-success">'+json['success']+'</div>');
					setTimeout(function(){
						$('#slugtting').modal('hide');
					}, 2000);
				}
			},
		})
	});
</script>

<script type="text/javascript">
	let leftHeight = $(".left-menu").height();
	let navbarHeight = $(".dashboard-navbar").height();
	let errorHeight = $(".server-errors").height();
	let footerHeight = $(".dashboard-footer").height();
	let elTotalheight = navbarHeight + errorHeight + footerHeight;
	let contentHeight = leftHeight - elTotalheight + 146;
	$(".content-wrapper").css('min-height',contentHeight);


	$(document).delegate(".only-number-allow","keypress",function (e) {
		if (e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	function readURL(input,placeholder) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$(placeholder).attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}

	function sumNote(element){

		var height = $(element).attr("data-height") ? $(element).attr("data-height") : 500;
		$(element).summernote({
			disableDragAndDrop: true,
			height: height,
			toolbar: [
			['style', ['style']],
			['font', ['bold', 'underline', 'clear']],
			['fontname', ['fontname']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'image', 'video']],
			['view', ['fullscreen', 'codeview', 'help']]
			],
			buttons: {
				image: function() {
					var ui = $.summernote.ui;
                    // create button
                    var button = ui.button({
                    	contents: '<i class="fa fa-image" />',
                    	tooltip: false,
                    	click: function () {
                    		$('#modal-image').remove();

                    		$.ajax({
                    			url: '<?= base_url("filemanager") ?>',
                    			dataType: 'html',
                    			beforeSend: function() {
                    			},complete: function() {
                    			},success: function(html) {
                    				$('body').append('<div id="modal-image" class="modal fade">' + html + '</div>');
                    				$('#modal-image').modal('show');
                    				$('#modal-image').delegate('.image-box .thumbnail','click', function(e) {
                    					e.preventDefault();
                    					$(element).summernote('insertImage', $(this).attr('href'));
                    					$('#modal-image').modal('hide');
                    				});
                    			}
                    		});                     
                    	}
                    });

                    return button.render();
                }
            }
        });
	}
	
	$(document).delegate(".view-all",'click',function(){
		var data = $(this).find("span").html();
		var html = '<table class="table table-hover">';
		data = JSON.parse(data);
		html += '<tr>';
		html += '	<th>IP</th>';
		html += '	<th width="30px">Country</th>';
		html += '</tr>';

		$.each(data, function(i,j){
			html += '<tr>';
			html += '	<td>'+ j['ip'] +'</td>';
			html += '	<td><img class="language-flag" src="<?= base_url('assets/template/images/flags/') ?>'+ j['country_code'].toLowerCase() +'.png" ></td>';
			html += '</tr>';
		})
		html += '</table>';

		$("#ip-flag_model").modal("show");
		$("#ip-flag_model .modal-body").html(html);
	})
	$(document).delegate(".copy-input input",'click', function(){
		$(this).select();
	})
	$(document).delegate('[copyToClipboard]',"click", function(){

		$this = $(this);
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(this).attr('copyToClipboard')).select();
		
		var copyText=$(this).attr('copyToClipboard');
		
		document.addEventListener('copy', function(e) {

		      e.clipboardData.setData('text/plain', copyText);
		      e.preventDefault();
		 }, true);
		
		document.execCommand("copy");
		$temp.remove();
		$this.tooltip('hide').attr('data-original-title','<?= __('user.copied') ?>').tooltip('show');
		setTimeout(function() { 
			$this.tooltip('hide');
			if(typeof($this.attr('aria-describedby')) != "undefined" && $this.attr('aria-describedby') !== null) {
				$this.click();
			}
			 }, 500);
	});
	
	/* BEGIN SVG WEATHER ICON */  
	if (typeof Skycons !== 'undefined'){
		var icons = new Skycons(
			{"color": "#fff"},
			{"resizeClear": true}
			),
		list  = [
		"clear-day", "clear-night", "partly-cloudy-day",
		"partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
		"fog"
		],
		i;
		
		for(i = list.length; i--; )
			icons.set(list[i], list[i]);
		icons.play();
	};
	
	// scroll
	$( document ).ready(function() {
		if($("#boxscroll").length > 0){
			$("#boxscroll").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});
		}
		if($("#boxscroll2").length > 0){
			$("#boxscroll2").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true}); 
		}
	});
	
	function shownofication(id, url, side) {
		// Visually remove the clicked item from the offcanvas list immediately
		var clickedItem = null;
		document.querySelectorAll('.anp-item').forEach(function(el) {
			var oc = el.getAttribute('onclick') || '';
			if (oc.indexOf('(' + id + ',') !== -1) { clickedItem = el; }
		});
		if (clickedItem) {
			clickedItem.style.transition = 'opacity 0.25s, transform 0.25s';
			clickedItem.style.opacity = '0';
			clickedItem.style.transform = 'translateX(20px)';
			setTimeout(function() {
				clickedItem.remove();
				// Decrement badge
				var badge = document.querySelector('.ajax-notifications_count');
				if (badge) {
					var count = parseInt(badge.textContent) - 1;
					if (count <= 0) { badge.style.display = 'none'; }
					else { badge.textContent = count; }
				}
				// Show empty state if no items remain
				var list = document.querySelector('.anp-list');
				if (list && list.querySelectorAll('.anp-item').length === 0) {
					var empty = document.createElement('div');
					empty.className = 'anp-empty';
					empty.innerHTML = '<i class="fas fa-check-double anp-empty-icon"></i>' +
						'<div class="anp-empty-title"><?= __("user.all_caught_up") ?></div>' +
						'<div class="anp-empty-sub"><?= __("user.no_notifications") ?></div>';
					list.appendChild(empty);
				}
			}, 260);
		}

		// Mark as read on server and navigate
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>usercontrol/updatenotify",
			data: {'id': id},
			dataType: 'json',
			success: function(data) {
				window.location.href = data['location'];
			},
			error: function() {
				if (url) { window.location.href = url; }
			}
		});
	}
</script>

<script>
	function start_plan_expiration_timer() {
		if($('span[data-time-remains]').length) {
			let countdown = $('span[data-time-remains]').data('time-remains');
			if(countdown > 0) {
				 
				var d = new Date();
				d.setTime(countdown);
				Window.GlobaleCountDownDate = d;

				var GlobaleCountDownDateInterval = setInterval(function() {
				 
					var distance = Window.GlobaleCountDownDate--;

					var days        = Math.floor(distance/24/60/60);
					var hoursLeft   = Math.floor((distance) - (days*86400));
					var hours       = Math.floor(hoursLeft/3600);
					var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
					var minutes     = Math.floor(minutesLeft/60);
					var remainingSeconds = distance % 60;

					let string = "";
 

					string += (days > 0) ? (''+days)+" days " : ""; 

					string += (hours > 0) ? ('0'+hours).slice(-2)+" Hours " : ""; 

					string += (minutes > 0) ? ('0'+minutes).slice(-2)+" Minutes " : ""; 

					string += (remainingSeconds > 0) ? ('0'+remainingSeconds).slice(-2)+" Seconds " : "00 Seconds"; 

					$('span[data-time-remains]').text(string);
					if (distance <= 0) {
						$('span[data-time-remains]').text('Plan Has Expired');
						window.location.reload();
						clearInterval(GlobaleCountDownDateInterval);
					}
				}, 1000);
			}
		}	
	}
</script>

<!--Auto scroll menu-->
<script type="text/javascript">
$(document).ready(function() {
  let activeMenuItem = $('.sidebar-list ul > li > .sub-nav a.active');
  if (activeMenuItem.length > 0) {
    activeMenuItem.parents('.sub-nav').addClass('show');
    $('.sidebar-thumb').animate({
      scrollTop: activeMenuItem.offset().top - ($('.sidebar-thumb').height() / 2) + (activeMenuItem.outerHeight() / 2)
    }, 1800);
  }

  $('.sidebar-list .nav-link').on('click', function() {
    if ($(this).parent().hasClass('sub-nav')) {
      $(this).parents('.sub-nav').addClass('show');
    }
    $('.sidebar-thumb').animate({
      scrollTop: $(this).offset().top - ($('.sidebar-thumb').height() / 2) + ($(this).outerHeight() / 2)
    }, 1800);
  });

  let sidebarHeight = $('.sidebar').height();
  let windowHeight = $(window).height();
  if (sidebarHeight > windowHeight) {
    $('.scrollbar-track-y').css('display', 'block');
  }
});
</script>
<!--Auto scroll menu-->


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
        window.location.href = "<?php echo base_url('usercontrol/logout'); ?>";
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

<!--this is model that show amazon s3 products-->
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
          return `<img src="${fileUrl}" class="preview-thumbnail" onerror="this.classList.add('d-none');" />`;
      } else if (/\.(mp4|webm)$/i.test(fileKey)) {
          return `<video src="${fileUrl}" class="preview-thumbnail" controls></video>`;
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


<!-- Toast container (bottom-right) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
    <div id="copyToast" class="toast text-bg-success border-0" role="alert">
        <div class="toast-body fw-bold"><?= __('user.link_copied') ?></div>
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
        .catch(() => alert('<?= __('user.clipboard_failed') ?>'));
});
</script>
<!-- Toast container (bottom-right) -->

<!-- Modern Toast Notification System -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;" id="toastContainer"></div>

<script>
function showToast(title, message, type = 'success', duration = 4000) {
	// Convert duration to number if it's a string
	duration = parseInt(duration) || 4000;
	
	const icons = {
		'success': '<i class="fas fa-check-circle me-2"></i>',
		'error': '<i class="fas fa-times-circle me-2"></i>',
		'warning': '<i class="fas fa-exclamation-triangle me-2"></i>',
		'info': '<i class="fas fa-info-circle me-2"></i>'
	};
	
	const colors = {
		'success': 'text-bg-success',
		'error': 'text-bg-danger',
		'warning': 'text-bg-warning',
		'info': 'text-bg-info'
	};
	
	const toastId = 'toast-' + Date.now();
	const bgClass = colors[type] || colors['info'];
	const icon = icons[type] || icons['info'];
	
	const toastHTML = `
		<div id="${toastId}" class="toast align-items-center ${bgClass} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header ${bgClass}">
				${icon}
				<strong class="me-auto">${title}</strong>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body text-white">
				${message}
			</div>
		</div>
	`;
	
	document.getElementById('toastContainer').insertAdjacentHTML('beforeend', toastHTML);
	
	const toastElement = document.getElementById(toastId);
	const bsToast = new bootstrap.Toast(toastElement, {
		autohide: true,
		delay: duration
	});
	
	bsToast.show();
	
	toastElement.addEventListener('hidden.bs.toast', function () {
		toastElement.remove();
	});
}
</script>
<!-- Modern Toast Notification System -->

<!-- Global JavaScript Error Handler -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 11000;" id="errorToastContainer"></div>
<script>
const jsErrorText = <?= json_encode(__('user.javascript_error')) ?>;
const promiseRejectionText = <?= json_encode(__('user.promise_rejection')) ?>;

window.addEventListener('error', function(event) {
	const errorMsg = `Error: ${event.message}\nFile: ${event.filename}\nLine: ${event.lineno}:${event.colno}`;
	const errorId = 'error-' + Date.now();
	
	const toastHTML = `
		<div class="toast show border-0 shadow-lg mb-3" role="alert" id="${errorId}">
			<div class="toast-header bg-danger text-white border-0">
				<i class="fas fa-exclamation-triangle me-2"></i>
				<strong class="me-auto">${jsErrorText}</strong>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
			</div>
			<div class="toast-body bg-white">
				<div class="text-dark mb-2">
					<p class="mb-1"><strong class="text-danger">Message:</strong> <span class="text-break">${event.message}</span></p>
					<p class="mb-1 small"><strong class="text-danger">File:</strong> <span class="text-break">${event.filename}</span></p>
					<p class="mb-2 small"><strong class="text-danger">Line:</strong> ${event.lineno}:${event.colno}</p>
				</div>
				<button class="btn btn-sm btn-danger w-100" onclick="copyErrorToClipboard('${errorId}', \`${errorMsg.replace(/`/g, '\\`')}\`)">
					<i class="fas fa-copy me-1"></i>Copy Error Details
				</button>
			</div>
		</div>
	`;
	
	document.getElementById('errorToastContainer').insertAdjacentHTML('beforeend', toastHTML);
	
	setTimeout(() => {
		const toastEl = document.getElementById(errorId);
		if (toastEl) {
			toastEl.classList.remove('show');
			setTimeout(() => toastEl.remove(), 300);
		}
	}, 15000);
	
	console.error('Global Error Handler:', event);
});

function copyErrorToClipboard(toastId, errorText) {
	if (navigator.clipboard && navigator.clipboard.writeText) {
		navigator.clipboard.writeText(errorText).then(() => {
			const toast = document.getElementById(toastId);
			const btn = toast.querySelector('button.btn-danger');
			const originalHTML = btn.innerHTML;
			
			btn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
			btn.classList.remove('btn-danger');
			btn.classList.add('btn-success');
			
			setTimeout(() => {
				btn.innerHTML = originalHTML;
				btn.classList.remove('btn-success');
				btn.classList.add('btn-danger');
			}, 2000);
		}).catch(() => {
			showToast('error', '<?= __('user.failed_to_copy') ?>');
		});
	} else {
		const textArea = document.createElement('textarea');
		textArea.value = errorText;
		textArea.className = 'position-absolute';
		textArea.style.left = '-9999px';
		document.body.appendChild(textArea);
		textArea.select();
		try {
			document.execCommand('copy');
			const toast = document.getElementById(toastId);
			const btn = toast.querySelector('button.btn-danger');
			const originalHTML = btn.innerHTML;
			
			btn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
			btn.classList.remove('btn-danger');
			btn.classList.add('btn-success');
			
			setTimeout(() => {
				btn.innerHTML = originalHTML;
				btn.classList.remove('btn-success');
				btn.classList.add('btn-danger');
			}, 2000);
		} catch {
			showToast('error', '<?= __('user.failed_to_copy') ?>');
		}
		document.body.removeChild(textArea);
	}
}

window.addEventListener('unhandledrejection', function(event) {
	const errorMsg = `Unhandled Promise Rejection: ${event.reason}`;
	const errorId = 'error-' + Date.now();
	
	const toastHTML = `
		<div class="toast show border-0 shadow-lg mb-3" role="alert" id="${errorId}">
			<div class="toast-header bg-warning text-dark border-0">
				<i class="fas fa-exclamation-circle me-2"></i>
				<strong class="me-auto">${promiseRejectionText}</strong>
				<button type="button" class="btn-close" data-bs-dismiss="toast"></button>
			</div>
			<div class="toast-body bg-white">
				<div class="text-dark mb-2">
					<p class="mb-2 text-break">${event.reason}</p>
				</div>
				<button class="btn btn-sm btn-warning w-100" onclick="copyErrorToClipboard('${errorId}', \`${errorMsg.replace(/`/g, '\\`')}\`)">
					<i class="fas fa-copy me-1"></i>Copy Error Details
				</button>
			</div>
		</div>
	`;
	
	document.getElementById('errorToastContainer').insertAdjacentHTML('beforeend', toastHTML);
	
	setTimeout(() => {
		const toastEl = document.getElementById(errorId);
		if (toastEl) {
			toastEl.classList.remove('show');
			setTimeout(() => toastEl.remove(), 300);
		}
	}, 15000);
	
	console.error('Unhandled Promise Rejection:', event);
});

console.log('%c✅ Global Error Handler Active', 'color: green; font-weight: bold; font-size: 14px;');
</script>
<!-- Global JavaScript Error Handler -->

<!-- Global AJAX Loading Overlay (Vendor) -->
<div id="globalLoadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:9999; justify-content:center; align-items:center;">
    <div class="text-center">
        <div class="spinner-border text-primary" role="status" style="width:3rem; height:3rem;"></div>
        <div class="mt-2 fw-bold text-muted" id="globalLoadingText"><?= __('user.loading') ?></div>
    </div>
</div>

<!-- Standardized Delete Confirmation Modal (Vendor) -->
<div class="modal fade" id="globalDeleteConfirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h6 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i><?= __('user.confirm_delete') ?></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-0" id="globalDeleteMessage"><?= __('user.are_you_sure_delete') ?></p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><?= __('user.cancel') ?></button>
                <button type="button" class="btn btn-danger btn-sm" id="globalDeleteConfirmBtn"><i class="fas fa-trash me-1"></i><?= __('user.delete') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
// Global loading helpers
window.showLoading = function(text) {
    if (text) $('#globalLoadingText').text(text);
    $('#globalLoadingOverlay').css('display','flex');
};
window.hideLoading = function() {
    $('#globalLoadingOverlay').hide();
};

// Intercept all AJAX requests for loading state
$(document).ajaxStart(function() {
    window._ajaxTimer = setTimeout(function() { window.showLoading(); }, 500);
}).ajaxStop(function() {
    clearTimeout(window._ajaxTimer);
    window.hideLoading();
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

<!-- V14 Theme Toggle & Dashboard Animations -->
<script src="<?= base_url('assets/template/js/theme-toggle.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/dashboard-animations.js') ?>?v=<?= av() ?>"></script>

 </body>
</html>