<?php if(!$product_count){ ?>
<div class="alert alert-warning d-flex align-items-center gap-2">
	<i class="bi bi-exclamation-triangle-fill"></i>
	<span><strong><?= __('admin.admin_product') ?>:</strong> <?= __('admin.you_need_to_create_product') ?> <a href="<?= base_url('admincontrol/addproduct') ?>"><?= __('admin.create_first_product') ?></a></span>
</div>
<?php } ?>
<div class="row">
	<div class="col-12">
		<div class="card m-b-30">
			<div class="card-header bg-blue-payment">
				<div class="card-title-white pull-left m-0"><?= __('admin.cart_mode_forms') ?></div>
			</div>
			<div class="card-body">
				<div id="store-form-wrap">
					<div>
						<ul class="nav nav-pills orange-color-bg" role="tablist" id="TabsNav">
							<li role="presentation" class="active nav-item">
								<a class="nav-link active show" href="#form_tab" aria-controls="form_tab" role="tab" data-bs-toggle="tab"><?= __('admin.form') ?></a>
							</li>
							<li role="presentation" class="nav-item">
								<a class="nav-link" href="#form_coupons_tab" aria-controls="form_coupons_tab" role="tab" data-bs-toggle="tab"><?= __('admin.form_coupon') ?></a>
							</li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="form_tab">
								<div class="table-rep-plugin">
									<div class="pull-right mb-2">
										<a class="btn btn-primary" href="<?= base_url('admincontrol/form_manage/')  ?>"><?= __('admin.add_new'); ?></a>
									</div>
								<?php if ($forms == null) {?>
									<div class="text-center py-5">
										<i class="bi bi-file-earmark-text" style="font-size:3rem;color:#cbd5e1;"></i>
										<h5 class="mt-3 text-muted"><?= __('admin.no_data_found') ?></h5>
										<p class="text-muted small mb-3"><?= __('admin.create_your_first_form') ?></p>
										<a href="<?= base_url('admincontrol/form_manage/') ?>" class="btn btn-sm btn-outline-primary">
											<i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new') ?>
										</a>
									</div>
								<?php } else { ?>
									<div class="table-responsive b-0" data-pattern="priority-columns">
										<button style="display:none;" type="button" class="btn btn-info" name="deletebutton" id="deletebutton" value="<?= __('admin.save_exit') ?>" onclick="deleteuserlistfunc('deleteAllforms');"><?= __('admin.delete_products') ?></button>
										
										<form method="post" name="deleteAllforms" id="deleteAllforms" action="<?php echo base_url();?>admincontrol/deleteAllforms">
											<table id="tech-companies-1" class="table  table-striped">
												<thead class="blue-bg-form">
													<tr>
														<th><input name="checkbox[]" type="checkbox" value="" onclick="checkAll(this)"></th>
														<th ><?= __('admin.form_title'); ?></th>
														
														<th><?= __('admin.vendor'); ?></th>
														<th><?= __('admin.coupon_code'); ?></th>
														<th><?= __('admin.coupon_use'); ?></th>
														<th><?= __('admin.sales_commission'); ?></th>
														<th><?= __('admin.clicks_commissio'); ?>n</th>
														<th><?= __('admin.total_commission'); ?></th>
														<th><?= __('admin.status'); ?></th>
														<th><?= __('admin.action'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
														$form_setting = $this->Product_model->getSettings('formsetting');
													?>
													<?php foreach($forms as $form){ ?>
													<tr>
														<td ><input name="checkbox[]" type="checkbox" id="check<?php echo $form['form_id'];?>" value="<?php echo $form['form_id'];?>" onclick="checkonly(this,'check<?php echo $form['form_id'];?>')"></td>
														<td>
															<?= $form['title'] ?>
															<div><small>
																<a href="<?= $form['public_page'] ?>" target='_black'><?= __('admin.public_page'); ?></a>
																</small>
															</div>
															<?php
																if($form['form_recursion_type']){
															if($form['form_recursion_type'] == 'custom'){
																if($form['form_recursion'] != 'custom_time'){
																	echo '<b>'. __("admin.recurring") .'</b> : ' . $form['form_recursion'];
																} else {
																	echo '<b>'. __("admin.recurring") .'</b> : '. timetosting($form['recursion_custom_time']);
																}
															} else{
																		if($form_setting['form_recursion'] == 'custom_time' ){
																	echo '<b>'. __("admin.recurring") .'</b> : '. timetosting($form_setting['recursion_custom_time']);
																		} else {
																			echo '<b>'. __("admin.recurring") .'</b> : '. $form_setting['form_recursion'];
																		}
															}
															}
															?>
														</td>
														<td><?= $form['firstname'] ? $form['firstname'] ." ". $form['lastname'] : __("admin.admin") ?></td>
														<td><?= $form['coupon_code'] ? $form['coupon_code'] : 'N/A' ?></td>
														<td><?= ($form['coupon_name'] ? $form['coupon_name'] : 'N/A').' / '.$form['count_coupon'] ?></td>
														<td><?= (int)$form['count_commission'].' / '.c_format($form['total_commission']) ?></td>
														<td><?= (int)$form['commition_click_count'].' / '.c_format($form['commition_click']); ?></td>
														<td><?= c_format($form['total_commission']+$form['commition_click']); ?></td>
														<td><?= form_status($form['status']); ?></td>
														<td>
															<a href="<?= base_url('admincontrol/form_manage/'.$form['form_id'])  ?>" class="btn ml-0 btn-primary btn-sm edit-button" id="<?= $lang['id'] ?>"><?= __("admin.edit") ?></a>
															<button data-href="<?= base_url('admincontrol/form_delete/'.$form['form_id'])  ?>" class="btn ml-0 btn-danger btn-sm delete-button" id="<?= $lang['id'] ?>"><?= __("admin.delete") ?></button>
														</td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</form>
									</div>
									<?php } ?>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="form_coupons_tab">
								<div class="table-rep-plugin">
									<div class="pull-right mb-2">
										<a class="btn btn-primary" href="<?= base_url('admincontrol/form_coupon_manage/')  ?>"><?= __('admin.add_new'); ?></a>
									</div>
								<?php if ($form_coupons == null) {?>
									<div class="text-center py-5">
										<i class="bi bi-percent" style="font-size:3rem;color:#cbd5e1;"></i>
										<h5 class="mt-3 text-muted"><?= __('admin.no_data_found') ?></h5>
										<p class="text-muted small mb-3"><?= __('admin.no_form_coupons') ?></p>
										<a href="<?= base_url('admincontrol/form_coupon_manage/') ?>" class="btn btn-sm btn-outline-primary">
											<i class="bi bi-plus-circle me-1"></i><?= __('admin.add_new') ?>
										</a>
									</div>
								<?php }else {?>
									<div class="table-responsive b-0" data-pattern="priority-columns">
										<table id="tech-companies-1" class="table  table-striped">
											<thead class="blue-bg-store-page">
												<tr>
													<th ><?= __('admin.form_coupon_name'); ?></th>
													<th width="100px"><?= __('admin.code'); ?></th>
													<th width="100px"><?= __('admin.discount'); ?></th>
													<th width="50px"><?= __('admin.date_start'); ?></th>
													<th width="50px"><?= __('admin.date_end'); ?></th>
													<th width="50px"><?= __("admin.status") ?></th>
													<th width="180px"></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($form_coupons as $form_coupon){ ?>
												<tr>
													<td><?= $form_coupon['name'] ?></td>
													<td><?= $form_coupon['code'] ?></td>
													<td><?= $form_coupon['type']=="P" ? getDecimalNumberFormat($form_coupon['discount'],$_SESSION['userDecimalPlace']).' %' : c_format($form_coupon['discount']) ?></td>
													<td><?= $form_coupon['date_start'] ?></td>
													<td><?= $form_coupon['date_end'] ?></td>
													<td><?= $lang['status'] == '0' ? __("admin.enabled") : __("admin.disabled") ?></td>
													<td>
														<a href="<?= base_url('admincontrol/form_coupon_manage/'.$form_coupon['form_coupon_id'])  ?>" class="btn btn-primary edit-button" id="<?= $lang['id'] ?>"><?= __("admin.edit") ?></a>
														<button data-href="<?= base_url('admincontrol/form_coupon_delete/'.$form_coupon['form_coupon_id'])  ?>" class="btn btn-danger btn-sm delete-button" id="<?= $lang['id'] ?>"><?= __("admin.delete") ?></button>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
<script type="text/javascript" async="">
function copy_text() {
	var copyText = document.getElementById("store-link");
	copyText.select();
	document.execCommand("Copy");
}
function closePopup(){
		$('.popupbox').hide();
		$('#overlay').hide();
}
	function generateCode(form_id){
	$('.popupbox').show();
	$('#overlay').show();
$('.modalpopup-body').load('<?php echo base_url();?>admincontrol/generateformcode/'+form_id);
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
function deleteuserlistfunc(formId){
$('#'+formId).submit();
}
function checkonly(bx,checkid) {
if(bx.checked)
{
document.getElementById('deletebutton').style.display = 'block';
} else {
document.getElementById('deletebutton').style.display = 'none';
}
}
$(document).ready(function(){
$('.delete-button').on('click',function(){
var el = this;
window.confirmDelete("<?= __("admin.delete_form_confirmation") ?>", function(){
location = $(el).data("href");
});
return false;
})
})
</script>