<div class="row g-3 mb-4">
	<div class="col-lg-3 col-md-6">
		<div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#4361ee,#3a56d4);">
			<div class="card-body d-flex align-items-center gap-3 py-3">
				<div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
					<i class="bi bi-image text-white fs-5"></i>
				</div>
				<div class="flex-grow-1">
					<h6 class="mb-0 text-white fw-bold"><?= __('admin.banners') ?></h6>
				</div>
				<a href="<?= base_url('usercontrol/integration_tools_form/banner') ?>" class="btn btn-sm btn-light">
					<i class="bi bi-plus-lg me-1"></i><?= __('admin.create_new') ?>
				</a>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#6c757d,#5a6268);">
			<div class="card-body d-flex align-items-center gap-3 py-3">
				<div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
					<i class="bi bi-fonts text-white fs-5"></i>
				</div>
				<div class="flex-grow-1">
					<h6 class="mb-0 text-white fw-bold"><?= __('admin.text_ads') ?></h6>
				</div>
				<a href="<?= base_url('usercontrol/integration_tools_form/text_ads') ?>" class="btn btn-sm btn-light">
					<i class="bi bi-plus-lg me-1"></i><?= __('admin.create_new') ?>
				</a>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#0dcaf0,#0aa8cc);">
			<div class="card-body d-flex align-items-center gap-3 py-3">
				<div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
					<i class="bi bi-link-45deg text-white fs-5"></i>
				</div>
				<div class="flex-grow-1">
					<h6 class="mb-0 text-white fw-bold"><?= __('admin.invisible_links') ?></h6>
				</div>
				<a href="<?= base_url('usercontrol/integration_tools_form/link_ads') ?>" class="btn btn-sm btn-light">
					<i class="bi bi-plus-lg me-1"></i><?= __('admin.create_new') ?>
				</a>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#ffc107,#e0a800);">
			<div class="card-body d-flex align-items-center gap-3 py-3">
				<div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
					<i class="bi bi-play-btn text-white fs-5"></i>
				</div>
				<div class="flex-grow-1">
					<h6 class="mb-0 text-white fw-bold"><?= __('admin.viral_videos') ?></h6>
				</div>
				<a href="<?= base_url('usercontrol/integration_tools_form/video_ads') ?>" class="btn btn-sm btn-light">
					<i class="bi bi-plus-lg me-1"></i><?= __('admin.create_new') ?>
				</a>
			</div>
		</div>
	</div>
</div>

<?php if(isset($campaign_count_alert)){?>
<div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
	<i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
	<div><?= $campaign_count_alert; ?></div>
</div>
<?php } ?>

<div class="card shadow-sm border-0">
	<div class="card-header bg-white border-bottom">
		<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
			<h6 class="card-title mb-0 fw-bold">
				<i class="bi bi-rocket-takeoff me-2 text-primary"></i><?= __('user.vendor_campaigns') ?>
			</h6>
			<div class="d-flex align-items-center gap-2">
				<span class="badge bg-light text-muted border fw-normal" id="vendor-results-summary"><?= __('admin.loading') ?>...</span>
				<button type="button" class="btn btn-outline-primary btn-sm" id="vendor-refresh-data">
					<i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>
				</button>
				<button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#perform-security-check-modal">
					<i class="bi bi-shield-check me-1"></i><?= __('admin.perform_security_check') ?>
				</button>
			</div>
		</div>
	</div>
	<div class="card-header bg-light border-bottom py-2">
		<div class="row g-2 align-items-center">
			<div class="col-md-3">
				<select class="form-select form-select-sm category_id">
					<option value=""><?= __('user.all_categories') ?></option>
					<?php 
					if(count($categories)>0) {
						$parentcategoyrid=0;
						foreach ($categories as $key => $value) {
							if($parentcategoyrid!=0 && $parentcategoyrid!=$value['pid']) { }
							if($parentcategoyrid!=$value['pid']) { ?>
								<option value="<?= $value['value'] ?>"><?= $value['label'] ?></option>
							<?php } else { ?>
								<option value="<?= $value['value'] ?>">-- <?= $value['label'] ?></option>
							<?php }
							$parentcategoyrid=$value['pid'];
						}
					} ?>
				</select>
			</div>
			<div class="col-md-3">
				<input class="form-control form-control-sm table-search ads_name" placeholder="<?= __('user.search') ?>..." type="search">
			</div>
			<div class="col-md-3">
				<select class="form-select form-select-sm" name="status">
					<option value=""><?= __('user.search_by_all_status') ?></option>
					<option value="1"><?= __('user.public') ?></option>
					<option value="2"><?= __('user.in_review') ?></option>
					<option value="0"><?= __('user.draft') ?></option>
				</select>
			</div>
		</div>
	</div>
	<div class="card-body p-0">
		<div class="text-center col-12 empty-div d-none py-5">
			<div class="d-flex justify-content-center align-items-center flex-column">
				<i class="bi bi-rocket-takeoff display-4 text-muted mb-3"></i>
				<h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
			</div>
		</div>
		<div class="table-responsive">
			<table id="myTable" class="table table-hover align-middle mb-0 intg-table">
				<thead class="table-light">
					<tr>
						<th class="intg-th-image"><?= __('user.image') ?></th>
						<th class="intg-th-name"><?= __('user.campaign_name') ?></th>
						<th class="intg-th-plugin"><?= __('user.integration_plugin_name') ?></th>
						<th class="text-center intg-th-view"><?= __('user.view') ?></th>
						<th class="intg-th-ratio"><?= __('user.ratio') ?></th>
						<th class="text-center intg-th-integration"><?= __('admin.integration_status') ?></th>
						<th class="text-center intg-th-status"><?= __('user.status') ?></th>
						<th class="text-center intg-th-action"><?= __('user.action') ?></th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<td colspan="12" class="text-end">
							<ul class="pagination pagination-td mb-0"></ul>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="integration-mlm-info"></div>

<div class="modal fade" id="integration-code">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>

<div class="modal fade" id="showcode-code"></div>

<div id="vendor-campaign-modals-container"></div>

<div class="modal fade" id="perform-security-check-modal" tabindex="-1" aria-labelledby="vendorSecurityCheckLabel" aria-hidden="true" data-bs-backdrop="static">
	<div class="modal-dialog modal-lg modal-dialog-centered intg-modal">
		<div class="modal-content">
			<div class="intg-modal-header">
				<div class="intg-modal-header-left">
					<div class="intg-modal-icon intg-modal-icon--warning">
						<i class="bi bi-shield-check"></i>
					</div>
					<div>
						<h5 class="intg-modal-title" id="vendorSecurityCheckLabel"><?= __('admin.perform_security_check') ?></h5>
						<p class="intg-modal-subtitle"><?= __('admin.take_longer_depending_campaigns_available') ?></p>
					</div>
				</div>
				<button type="button" class="intg-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
			</div>
			<div class="modal-body">
				<div class="step-1">
					<div class="intg-modal-card d-flex align-items-start gap-2">
						<i class="bi bi-exclamation-triangle text-warning mt-1"></i>
						<div class="small">
							<strong><?= __('admin.are_you_sure_perform_security_check') ?></strong>
						</div>
					</div>
				</div>

				<div class="step-2 intg-step-hidden">
					<div class="text-center mb-3" id="vendor-check-progress-wrap">
						<div class="progress intg-progress-bar" id="vendor-security-progressbar">
							<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
								 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
								0%
							</div>
						</div>
					</div>

					<div class="row g-2">
						<div class="col-md-4">
							<div class="intg-modal-card text-center postback-campaigns intg-result-card-hidden" data-count="0">
								<i class="bi bi-arrow-left-right d-block mb-1 text-success intg-modal-result-icon"></i>
								<div class="intg-modal-card-title justify-content-center"><?= __('admin.postback') ?></div>
								<div class="intg-modal-stat-value text-success fs-5 vendor-check-count">0</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="intg-modal-card text-center approved intg-result-card-hidden" data-count="0">
								<i class="bi bi-check-circle d-block mb-1 text-success intg-modal-result-icon"></i>
								<div class="intg-modal-card-title justify-content-center"><?= __('admin.verified') ?></div>
								<div class="intg-modal-stat-value text-success fs-5 vendor-check-count">0</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="intg-modal-card text-center pending intg-result-card-hidden" data-count="0">
								<i class="bi bi-clock d-block mb-1 text-info intg-modal-result-icon"></i>
								<div class="intg-modal-card-title justify-content-center"><?= __('admin.pending') ?></div>
								<div class="intg-modal-stat-value text-info fs-5 vendor-check-count">0</div>
							</div>
						</div>
					</div>

					<div class="intg-modal-card warning intg-result-card-hidden text-center mt-3">
						<i class="bi bi-exclamation-triangle d-block mb-1 text-warning intg-modal-result-icon"></i>
						<div class="small fw-semibold"><?= __('admin.no_campagins_available') ?></div>
					</div>
				</div>
			</div>

			<div class="intg-modal-footer">
				<button type="button" class="btn btn-primary rounded-pill allow_to_perform_security_check">
					<i class="bi bi-play me-1"></i><?= __('admin.yes_continue') ?>
				</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
					<i class="bi bi-x-lg me-1"></i><?= __('user.close') ?>
				</button>
			</div>
		</div>
	</div>
</div>


<?= $social_share_modal ?>

<script type="text/javascript">

	$(document).on('click','.check-campaign-with-id',function(){
		var el = $(this);
		var id = el.data('id');
		$.ajax({
	      type:"POST",
	      url: '<?= base_url('usercontrol/check_campaign_security_with_id/') ?>' + id,
	      dataType:"json",
	      success: function(data){
	      	if(data.statusClass){
	      		el.parents('td').siblings('.security-status').find('button.badge').remove();

	      		el.parents('td').siblings('.security-status').find('span.badge').removeClass().addClass(data.statusClass).text(data.message);

	      		if(data.security_status == 0)
	      			el.parents('td').siblings('.security-status').prepend(data.integration_code_button);
	      	}
		    }
	    });
	})

	$(document).on('click', '.allow_to_perform_security_check', function(){
		$(this).hide();
		$('#perform-security-check-modal .step-1').hide();
		$('#perform-security-check-modal .step-2').removeClass('intg-step-hidden').show();
		$('#perform-security-check-modal .intg-modal-footer').hide();
		$('#perform-security-check-modal .intg-modal-close').prop('disabled', true);
		recursive_security_check();
	});

let postbackCount = 0;

function recursive_security_check(index = 1) {
  $.ajax({
    type: "POST",
    url: '<?= base_url('usercontrol/check_campaign_security') ?>',
    dataType: "json",
    data: { index: index },
    success: function(data) {
      if (data.progress_percentage) {
        var pctStr = (data.progress_percentage || '0').toString().replace('%', '');
        var pctNum = Math.min(100, Math.max(0, parseFloat(pctStr) || 0));
        var pctRounded = Math.round(pctNum * 10) / 10;
        var pctDisplay = pctRounded + '%';
        $('#vendor-check-progress-wrap').show();
        $('#vendor-security-progressbar').show();
        $('#vendor-security-progressbar > div').css('width', pctDisplay).attr('aria-valuenow', Math.round(pctNum)).text(pctDisplay);
      } else {
        $('#vendor-check-progress-wrap').hide();
      }

      if (data.warning) {
        $('#vendor-check-progress-wrap').hide();
        $('#perform-security-check-modal .step-2 .warning').removeClass('intg-result-card-hidden').show();
      } else {
        let statusElement = $('#perform-security-check-modal .step-2 .' + data.security_status);
        let existing_count = statusElement.data('count') || 0;
        statusElement.data('count', existing_count + 1);

        if (data.security_status === 'approved') {
          statusElement.find('.vendor-check-count').text(existing_count + 1);
          statusElement.removeClass('intg-result-card-hidden').show();
        } else if (data.security_status === 'pending') {
          statusElement.find('.vendor-check-count').text(existing_count + 1);
          statusElement.removeClass('intg-result-card-hidden').show();
        } else if (data.security_status === 'postback') {
          postbackCount++;
          statusElement.find('.vendor-check-count').text(postbackCount);
          statusElement.removeClass('intg-result-card-hidden').show();
        }
      }

      if (data.index) {
        recursive_security_check(data.index);
      } else {
        $('#vendor-check-progress-wrap').hide();
        $('#perform-security-check-modal .intg-modal-close').prop('disabled', false);
        $('#perform-security-check-modal .intg-modal-footer').show()
          .html(
            '<button type="button" class="btn btn-success rounded-pill" onclick="window.location.reload()"><i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh_page') ?></button>' +
            '<button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?></button>'
          );
      }
    }
  });
}


	var xhr;
	function getPage(url){
		$this = $(this);

		if(xhr && xhr.readyState != 4) xhr.abort();

		xhr = $.ajax({
			url:url,
			type:'POST',
			dataType:'html',
			data:{
				category_id: $(".category_id").val(),
				ads_name: $(".ads_name").val(),
				status: $("select[name='status']").val(),
			},
			beforeSend:function(){$(".btn-search").btn("loading");},
			complete:function(){$(".btn-search").btn("reset");},
		success:function(json){
			if(json){
				$("#myTable tbody").html(json);
				$("#myTable").show();
				$(".empty-div").addClass("d-none");
				var rowCount = $("#myTable tbody tr").length;
				$('#vendor-results-summary').html(rowCount + ' <?= __("admin.campaigns_found") ?>');
			} else {
				$(".empty-div").removeClass("d-none");
				$("#myTable").hide();
				$('#vendor-results-summary').html('<?= __("admin.no_data_found") ?>');
			}
			$('[data-bs-toggle="tooltip"]').tooltip();
		},
		})

		xhr = $.ajax({
			url:url,
			type:'POST',
			dataType:'html',
			data:{
				category_id: $(".category_id").val(),
				ads_name: $(".ads_name").val(),
				status: $("select[name='status']").val(),
				paginate: true,
			},
			beforeSend:function(){$(".btn-search").btn("loading");},
			complete:function(){$(".btn-search").btn("reset");},
			success:function(json){
				$("#myTable .pagination-td").html(json);
			},
		})
	}

	$(".category_id,select[name='status']").on("change",function(){
		getPage('<?= base_url("usercontrol/integration_tools/") ?>/1');
	});
	$(".ads_name").on("keyup",function(){
		getPage('<?= base_url("usercontrol/integration_tools/") ?>/1');
	});
	
	getPage('<?= base_url("usercontrol/integration_tools") ?>/1');

	$('#vendor-refresh-data').on('click', function(){
		getPage('<?= base_url("usercontrol/integration_tools") ?>/1');
	});

	$("#myTable .pagination-td").delegate("a","click",function(e){
		e.preventDefault();
		getPage($(this).attr("href"));
		return false;
	})

	$("#myTable").delegate(".btn-show-integration-mlm-info",'click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("usercontrol/getIntegrationMlmInfo") ?>',
			type:'POST',
			dataType:'html',
			data:{
				id: $this.attr("data-id"),
			},
			beforeSend:function(){
				$this.btn("loading");
			},
			complete:function(){
				$this.btn("reset");
			},
			success:function(html){
				$("#integration-mlm-info").html(html);
				$("#integration-mlm-info").modal("show");
			},
		})
	});

	$("#myTable").delegate(".btn-show-code",'click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("usercontrol/integration_code_modal_new") ?>',
			type:'POST',
			dataType:'json',
			data:{id: $this.attr("data-id")},
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				if(json['html']){
					$("#showcode-code").html(json['html']);
					$("#showcode-code").modal("show");
				}
			},
		})
	})

	$("#myTable").delegate(".btn-show-setup",'click',function(){
		$this = $(this);
		var originalHtml = $this.html();
		$.ajax({
			url:'<?= base_url("usercontrol/integration_setup_modal") ?>',
			type:'POST',
			dataType:'html',
			data:{id: $this.attr("data-id")},
			beforeSend:function(){ $this.prop('disabled', true).html('<i class="bi bi-arrow-repeat spin-icon"></i>'); },
			complete:function(){ $this.prop('disabled', false).html(originalHtml); },
			success:function(html){
				$("#showcode-code").html(html);
				$("#showcode-code").modal("show");
			},
			error:function(){
				if(typeof showToast === 'function') showToast('<?= __('admin.error') ?>', '<?= __('user.failed_to_load_setup') ?>', 'error');
			}
		})
	})

	$("#myTable").delegate(".btn-show-terms",'click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("usercontrol/integration_terms_modal") ?>',
			type:'POST',
			dataType:'json',
			data:{
				id: $this.attr("data-id"),
			},
			beforeSend:function(){
				$this.btn("loading");
			},
			complete:function(){
				$this.btn("reset");
			},
			success:function(json){
				if(json['html']){
					$("#showcode-code").html(json['html']);
					$("#showcode-code").modal("show");
				}
			},
		})
	})

	$("#myTable").delegate(".wallet-toggle .tog",'click',function(){
		$(this).parents(".wallet-toggle").find("> div").toggleClass("hide");
	})
	$("#myTable").delegate(".tool-remove-link",'click',function(){
		if(!confirm('<?= __('user.are_you_sure') ?>')) return false;
		return true;
	})

	$("#myTable").delegate(".get-code",'click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("usercontrol/tool_get_code") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
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
	})

	$(".not-show-alert").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("usercontrol/setCookie") ?>',
			type:'POST',
			dataType:'json',
			data:{
				name: 'campaign_count_alert',
			},
			success:function(result){
				if(result)
					$this.parents('.row').remove();
			},
		})
	})
</script>