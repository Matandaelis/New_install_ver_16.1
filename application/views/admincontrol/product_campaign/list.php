<?php foreach($productlist as $product){ ?>
	<?php 
		$productLink = base_url('capmaign/'. base64_encode($userdetails['id']) .'/product/'.$product['product_slug'] );
	?>
	<tr class="product-row">
		<td class="p-3">
			<div class="form-check">
				<input class="form-check-input list-checkbox" name="product[]" type="checkbox" id="check<?php echo $product['product_id'];?>" value="<?php echo $product['product_id'];?>" onclick="checkonly(this,'check<?php echo $product['product_id'];?>')">
				<label class="form-check-label" for="check<?php echo $product['product_id'];?>"></label>
			</div>
		</td>
		<td class="p-3" style="width: 300px;">
			<div class="d-flex align-items-center">
				<div class="me-3 position-relative">
					<?php
					if (strpos($product['product_featured_image'], 'http://') === 0 || strpos($product['product_featured_image'], 'https://') === 0) {
						$product_featured_image = $product['product_featured_image'];
					} else {
						$product_featured_image = !empty($product['product_featured_image']) ? base_url('assets/images/product/upload/thumb/' . $product['product_featured_image']) : base_url('assets/images/no_product_image.png');
					}
					?>
					<img class="rounded border" width="60" height="60" src="<?php echo $product_featured_image; ?>" alt="Product Image">
					<?php if($product['product_type'] == 'downloadable'){ ?>
						<span class="position-absolute top-0 start-100 translate-middle badge bg-primary bg-gradient text-white rounded-pill shadow-sm">
							<i class="bi bi-cloud-download"></i>
						</span>
					<?php } ?>
				</div>
				<div class="flex-grow-1">
					<div class="fw-medium text-dark mb-1" style="line-height: 1.3;"><?php echo htmlspecialchars($product['product_name']); ?></div>
					<div class="d-flex flex-wrap gap-1 mb-2">
						<a href="<?php echo $productLink; ?>" target="_blank" class="btn btn-outline-primary btn-sm">
							<i class="bi bi-eye me-1"></i><?= __('admin.public_page') ?>
						</a>
						<?php if($product['product_recursion_type']){ ?>
							<span class="badge bg-info bg-gradient text-white">
								<i class="bi bi-arrow-repeat me-1"></i><?= __('admin.recurring') ?>
							</span>
						<?php } ?>
					</div>
					<?php if(isset($product['reviews_count']) && $product['reviews_count'] > 0): ?>
						<div class="d-flex align-items-center text-muted small">
							<div class="me-2">
								<?php for($i = 1; $i <= 5; $i++): ?>
									<i class="bi bi-star<?= $i <= $product['average_rating'] ? '-fill text-warning' : ' text-muted' ?>" style="font-size: 10px;"></i>
								<?php endfor; ?>
							</div>
							<span>(<?= $product['reviews_count'] ?> <?= __('admin.reviews') ?>)</span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</td>
		<td class="p-3 text-center">
			<span class="badge bg-secondary bg-gradient">
				<?php echo $product['seller_username'] ? htmlspecialchars($product['seller_username']) : __('admin.admin'); ?>
			</span>
		</td>
		<td class="p-3 text-center">
			<h6 class="mb-0 text-success fw-bold"><?php echo c_format($product['product_price']); ?></h6>
		</td>
		<td class="p-3 text-center">
			<code class="bg-light px-2 py-1 rounded"><?php echo htmlspecialchars($product['product_sku']); ?></code>
		</td>
		<td class="p-3">
			<div class="small">
				<?php 
				// Simplified commission display
				if($product['seller_id']){
					$seller = $this->Product_model->getSellerFromProduct($product['product_id']);
					$seller_setting = $this->Product_model->getSellerSetting($seller->user_id);

					// Sale Commission
					echo '<div class="mb-1"><span class="badge bg-success bg-gradient me-1">' . __('admin.sale') . '</span>';
					if($seller->affiliate_sale_commission_type == 'default'){ 
						if($seller_setting->affiliate_sale_commission_type == 'percentage'){
							echo (float)$seller_setting->affiliate_commission_value .'%';
						} else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
							echo c_format($seller_setting->affiliate_commission_value);
						} else {
							echo '<span class="text-warning">' . __('admin.not_set') . '</span>';
						}
					} else if($seller->affiliate_sale_commission_type == 'percentage'){
						echo (float)$seller->affiliate_commission_value .'%';
					} else if($seller->affiliate_sale_commission_type == 'fixed'){
						echo c_format($seller->affiliate_commission_value);
					}
					echo '</div>';

					// Click Commission
					echo '<div><span class="badge bg-info bg-gradient me-1">' . __('admin.click') . '</span>';
					if($seller->affiliate_click_commission_type == 'default'){ 
						echo c_format($seller_setting->affiliate_click_amount) .' / '. (int)$seller_setting->affiliate_click_count;
					} else{
						echo c_format($seller->affiliate_click_amount) .' / '. (int)$seller->affiliate_click_count;
					}
					echo '</div>';
				} else {
					// Default commission
					echo '<div class="mb-1"><span class="badge bg-success bg-gradient me-1">' . __('admin.sale') . '</span>';
					if($product['product_commision_type'] == 'default'){
						if($default_commition['product_commission_type'] == 'percentage'){
							echo $default_commition['product_commission']. "%";
						} else {
							echo c_format($default_commition['product_commission']);
						}
					} else if($product['product_commision_type'] == 'percentage'){
						echo $product['product_commision_value']. "%";
					} else {
						echo c_format($product['product_commision_value']);
					}
					echo '</div>';

					echo '<div><span class="badge bg-info bg-gradient me-1">' . __('admin.click') . '</span>';
					if($product['product_click_commision_type'] == 'default'){
						echo c_format($default_commition['product_ppc']) .' / '. $default_commition['product_noofpercommission'];
					} else {
						echo c_format($product['product_click_commision_ppc']) .' / '. $product['product_click_commision_per'];
					}
					echo '</div>';
				}
				?>
			</div>
		</td>
		<td class="p-3 text-center">
			<div class="fw-bold text-primary"><?php echo $product['order_count'];?></div>
			<div class="small text-success"><?php echo c_format($product['commission']); ?></div>
			<div class="small text-muted mt-1">
				<?php 
				$ordercountratio = 0;
				if($product['view_statistics'] > 0)
					$ordercountratio = $product['order_count'] * 100 / $product['view_statistics'];
				$ordercountratio = is_float($ordercountratio) ? number_format((float)$ordercountratio, 1) : $ordercountratio;
				echo $product['view_statistics'] . ' ' . __('admin.views') . ' / ' . $ordercountratio . '%';
				?>
			</div>
		</td>
		<td class="p-3 text-center">
			<div class="fw-bold text-info"><?php echo (int)$product['commition_click_count'] + (int)$product['commition_click_count_admin']; ?></div>
			<div class="small text-success"><?php echo c_format($product['commition_click']); ?></div>
			<div class="small text-muted mt-1">
				<?php 
				$ordercountratio = 0;
				$comissionclickcount = (int)$product['commition_click_count'] + (int)$product['commition_click_count_admin'];
				if($product['view_statistics'] > 0)
					$ordercountratio = $comissionclickcount * 100 / $product['view_statistics'];
				$ordercountratio = is_float($ordercountratio) ? number_format((float)$ordercountratio, 1) : $ordercountratio;
				echo $product['view_statistics'] . ' ' . __('admin.views') . ' / ' . $ordercountratio . '%';
				?>
			</div>
		</td>
		<td class="p-3 text-center">
			<h6 class="mb-0 text-success fw-bold">
				<?php echo c_format((float)$product['commition_click'] + (float)$product['commission']); ?>
			</h6>
		</td>
		<td class="p-3 text-center">
			<div class="mb-2">
				<?= product_status_on_store_admin($product['on_store'], $product['product_created_by']) ?>
			</div>
			<div>
				<?= product_status($product['product_status']) ?>
			</div>
		</td>
		<td class="p-3 text-center">
			<div class="btn-group-sm" role="group">
				<button class="btn btn-outline-primary btn-sm btn-show-code" data-bs-toggle="tooltip" title="<?php echo __('admin.integration_code') ?>" data-id='<?= $product['product_id'] ?>'>
					<i class="bi bi-code-slash"></i>
				</button>
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo base_url();?>Productsales/update/<?php echo $product['product_id'];?>" data-bs-toggle="tooltip" title="<?php echo __('admin.edit') ?>">
					<i class="bi bi-pencil"></i>
				</a>
				<button class="btn btn-outline-success btn-sm duplicate-product" type="button" data-id="<?= $product['product_id'] ?>" data-bs-toggle="tooltip" title="<?php echo __('admin.duplicate') ?>">
					<i class="bi bi-files"></i>
				</button>
				<button class="btn btn-outline-danger btn-sm delete-product" type="button" data-id="<?= $product['product_id'] ?>" data-bs-toggle="tooltip" title="<?php echo __('admin.delete') ?>">
					<i class="bi bi-trash"></i>
				</button>
			</div>
		</td>
	</tr>

<?php } ?>
 