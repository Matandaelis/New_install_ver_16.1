<?php 
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$pro_setting = $this->Product_model->getSettings('productsetting');
	$vendor_setting = $this->Product_model->getSettings('vendor');
?>

<?php foreach($productlist as $product){ ?>
	<?php 
		$productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product['product_slug'] );
	?>
	<tr class="product-row">
		<td class="text-center align-middle" style="width: 40px;">
			<div class="form-check">
				<input name="product[]" class="form-check-input list-checkbox" type="checkbox" id="check<?php echo $product['product_id'];?>" value="<?php echo $product['product_id'];?>" onclick="checkonly(this,'check<?php echo $product['product_id'];?>')">
			</div>
		</td>
		<td class="align-middle" style="width: 300px;">
			<div class="d-flex align-items-center p-2">
				<div class="me-3">
					<?php
					if (strpos($product['product_featured_image'], 'http://') === 0 || strpos($product['product_featured_image'], 'https://') === 0) {
						$image_src = $product['product_featured_image'];
					} else {
						$image_src = !empty($product['product_featured_image']) ? base_url('assets/images/product/upload/thumb/' . $product['product_featured_image']) : base_url('assets/images/no_product_image.png');
					}
					?>
					<img class="rounded shadow-sm" width="60px" height="60px" src="<?php echo $image_src; ?>" style="object-fit: cover;">
				</div>
				<div class="flex-grow-1">
					<h6 class="mb-1 fw-semibold text-dark"><?php echo $product['product_name'];?></h6>
					<div class="d-flex gap-1 flex-wrap mb-2">
						<a target="_blank" href="<?= $productLink.'?preview=1' ?>" class="btn btn-sm btn-outline-primary">
							<i class="bi bi-eye me-1"></i><?= __('admin.public_page') ?>
						</a>
						<?php
						$_ptype = $product['product_type'] ?? 'virtual';
						if (in_array($_ptype, ['virtual', ''])):
						?>
							<span class="badge rounded-pill" style="background:#6366f1;color:#fff;">
								<i class="bi bi-cloud me-1"></i><?= __('admin.product_type_virtual') ?>
							</span>
						<?php elseif ($_ptype == 'downloadable'): ?>
							<span class="badge rounded-pill" style="background:#0ea5e9;color:#fff;">
								<i class="bi bi-cloud-download me-1"></i><?= __('admin.product_type_downloadable') ?>
							</span>
						<?php elseif (in_array($_ptype, ['video', 'videolink'])): ?>
							<span class="badge rounded-pill" style="background:#10b981;color:#fff;">
								<i class="bi bi-play-circle me-1"></i><?= __('admin.product_type_lms') ?>
							</span>
						<?php endif; ?>
						<?php if ($product['product_recursion_type'] && $product['product_recursion_type'] !== 'default'): ?>
							<span class="badge bg-warning text-dark rounded-pill">
								<i class="bi bi-arrow-repeat me-1"></i><?= __('admin.product_type_recurring') ?>
							</span>
						<?php endif; ?>
					</div>
					<div class="d-flex align-items-center gap-2">
						<small class="text-muted"><?=$product['totalreviews'];?> <?= __('admin.reviews') ?></small>
						<div class="d-flex gap-1">
							<?php 
							$totalreviews=$product['totalreviews'];
							$totalrating=$product['totalrating'];
							if($totalreviews>0 && $totalrating>0)
								$ratingAvg = number_format(($totalrating / $totalreviews), 0);
							else
								$ratingAvg = 0;

							for($i=0;$i<5;$i++)  {
								if ($i < $ratingAvg) {
									echo '<i class="bi bi-star-fill text-warning" style="font-size: 10px;"></i>';
								} else {
									echo '<i class="bi bi-star text-muted" style="font-size: 10px;"></i>';
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 100px;">
			<div class="p-2">
				<span class="badge bg-light text-dark border"><?php echo $product['seller_username'] ? $product['seller_username'] : __('admin.admin'); ?></span>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 90px;">
			<div class="p-2">
				<span class="fw-bold text-success h6 mb-0"><?php echo c_format($product['product_price']); ?></span>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 100px;">
			<div class="p-2">
				<code class="bg-light px-2 py-1 rounded"><?php echo $product['product_sku'];?></code>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 120px;">
			<div class="commission-display p-2">
				<?php 
				if($product['seller_id']){
					$seller = $this->Product_model->getSellerFromProduct($product['product_id']);
					$seller_setting = $this->Product_model->getSellerSetting($seller->user_id);

					// Sale Commission
					$sale_commission = "";
					if($seller->affiliate_sale_commission_type == 'default'){ 
						if($seller_setting->affiliate_sale_commission_type == ''){
							$sale_commission = '<span class="text-warning">Not Set</span>';
						}
						else if($seller_setting->affiliate_sale_commission_type == 'percentage'){
							$sale_commission = (float)$seller_setting->affiliate_commission_value .'%';
						}
						else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
							$sale_commission = c_format($seller_setting->affiliate_commission_value);
						}
					} else if($seller->affiliate_sale_commission_type == 'percentage'){
						$sale_commission = (float)$seller->affiliate_commission_value .'%';
					} else if($seller->affiliate_sale_commission_type == 'fixed'){
						$sale_commission = c_format($seller->affiliate_commission_value);
					} 

					echo '<div class="mb-1">';
					echo '<div class="fw-bold text-primary small">' . $sale_commission . '</div>';
					echo '<small class="text-muted">' . __('admin.sale') . '</small>';
					echo '</div>';

					// Click Commission
					$click_commission = "";
					if($seller->affiliate_click_commission_type == 'default'){ 
						$click_commission = c_format($seller_setting->affiliate_click_amount) ." / ". (int)$seller_setting->affiliate_click_count;
					} else{
						$click_commission = c_format($seller->affiliate_click_amount) ." / ". (int)$seller->affiliate_click_count;
					} 
					echo '<div>';
					echo '<div class="fw-bold text-info small">' . $click_commission . '</div>';
					echo '<small class="text-muted">' . __('admin.click') . '</small>';
					echo '</div>';

				} else {
					// Default commission display
					$sale_commission = "";
					if($product['product_commision_type'] == 'default'){
						if($default_commition['product_commission_type'] == 'percentage'){
							$sale_commission = $default_commition['product_commission']. "%";
						} else {
							$sale_commission = c_format($default_commition['product_commission']);
						}
					} else if($product['product_commision_type'] == 'percentage'){
						$sale_commission = $product['product_commision_value']. "%";
					} else {
						$sale_commission = c_format($product['product_commision_value']);
					}
					
					echo '<div class="mb-2">';
					echo '<div class="fw-bold text-primary">' . $sale_commission . '</div>';
					echo '<small class="text-muted">' . __('admin.sale') . '</small>';
					echo '</div>';
					
					$click_commission = "";
					if($product['product_click_commision_type'] == 'default'){
						$click_commission = c_format($default_commition['product_ppc']) . " / " . $default_commition['product_noofpercommission'];
					} else {
						$click_commission = c_format($product['product_click_commision_ppc']) . " / " . $product['product_click_commision_per'];
					}
					
					echo '<div>';
					echo '<div class="fw-bold text-info">' . $click_commission . '</div>';
					echo '<small class="text-muted">' . __('admin.click') . '</small>';
					echo '</div>';
				} 
				?>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 100px;">
			<div class="p-2">
				<div class="fw-bold text-primary"><?php echo $product['order_count'];?></div>
				<div class="fw-bold text-success small"><?php echo c_format($product['commission']) ;?></div>
				<small class="text-muted"><?= __('admin.sales') ?></small>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 100px;">
			<div class="p-2">
				<div class="fw-bold text-info"><?php echo (int)$product['commition_click_count'];?></div>
				<div class="fw-bold text-success small"><?php echo c_format($product['commition_click']) ;?></div>
				<small class="text-muted"><?= __('admin.clicks') ?></small>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 80px;">
			<div class="p-2">
				<span class="fw-bold text-success h6 mb-0"><?php echo c_format((float)$product['commition_click'] + (float)$product['commission']); ?></span>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 100px;">
			<div class="p-2">
				<div class="d-flex flex-column gap-1">
					<?= product_status_on_store_admin($product['on_store'], $product['product_created_by']) ?>	
					<?= product_status($product['product_status']) ?>
				</div>
			</div>
		</td>
		<td class="text-center align-middle" style="width: 140px;">
			<div class="p-2">
				<div class="btn-group btn-group-sm" role="group">
				<a class="btn btn-outline-primary" onclick="return confirmpopup('<?=base_url();?>admincontrol/updateproduct/<?php echo $product['product_id'];?>');" href="<?php echo base_url();?>admincontrol/updateproduct/<?php echo $product['product_id'];?>" title="<?= __('admin.edit') ?>">
					<i class="bi bi-pencil-square"></i>
				</a>
				<?php if((int)$product['seller_id'] == 0 ){ ?>
					<a class="btn btn-outline-info" href="<?php echo base_url('admincontrol/duplicateProduct');?>/<?php echo $product['product_id'];?>" title="<?= __('admin.duplicate') ?>">
						<i class="bi bi-files"></i>
					</a>
				<?php } ?>
				<div class="btn-group btn-group-sm" role="group">
					<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
						<i class="bi bi-three-dots"></i>
					</button>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="<?php echo base_url('admincontrol/productupload/'. $product['product_id']);?>">
							<i class="bi bi-image me-2"></i><?= __('admin.upload_images') ?>
						</a></li>
						<li><a class="dropdown-item" href="<?php echo base_url('admincontrol/videoupload/'. $product['product_id']);?>">
							<i class="bi bi-camera-video me-2"></i><?= __('admin.upload_videos') ?>
						</a></li>
						<li><hr class="dropdown-divider"></li>
						<li><span class="dropdown-item social-share-btn" data-social-share data-share-url="<?= $productLink ?>" data-share-title="<?= $product['product_name'];?>" data-share-desc="<?= $product['product_short_description'];?>" style="cursor: pointer;">
							<i class="bi bi-share me-2"></i><?= __('admin.share') ?>
						</span></li>
						<li><hr class="dropdown-divider"></li>
						<li><button class="dropdown-item text-danger delete-product" type="button" data-id="<?= $product['product_id'] ?>">
							<i class="bi bi-trash me-2"></i><?= __('admin.delete') ?>
						</button></li>
					</ul>
				</div>
				</div>
			</div>
		</td>
	</tr>
<?php } ?>