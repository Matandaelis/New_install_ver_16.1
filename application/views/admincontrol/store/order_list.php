<?php foreach($orders as $index => $order){ ?>
	<?php if($order['type'] == 'store'){ ?>
		<tr id="order-row-<?= $order['id'] ?>" class="align-middle">
			<td class="ps-3">
				<span class="badge bg-secondary text-white"><?= orderId($order['id']);?></span>
			</td>
			<td>
				<strong class="text-success"><?php echo c_format($order['total']); ?></strong>
			</td>
			<td class="text-center"><?php echo $order['order_country_flag'];?></td>
			<td>
				<span class="badge bg-info text-white"><?= __('admin.local_store') ?></span>
			</td>
			<td>
				<span class="badge <?= ($order['status'] == 1) ? 'bg-success text-white' : 'bg-warning text-dark' ?>">
					<?= $status[$order['status']] ?>
				</span>
			</td>
			<td>
				<div class="d-flex flex-column">
					<?php
					if($order['wallet_commission_status'] == 0) {
						?>
						<span class="badge <?php if((int)$order['wallet_status'] > 0){ ?>bg-success text-white<?php }else{ ?>bg-warning text-dark<?php } ?> mb-1"><?= $wallet_status[(int)$order['wallet_status']] ?></span>
						<?php
				 	} else {
						echo commission_status($order['wallet_commission_status']);
				 	}
					?>
					<small class="text-muted fw-medium"><?php echo c_format($order['commission_amount']); ?></small>
				</div>
			</td>
			<td>
				<small class="text-muted"><?= date("M d, Y",strtotime($order['created_at'])); ?></small>
			</td>
			<td class="text-center pe-3">
				<div class="btn-group" role="group" aria-label="<?= __('admin.order_actions') ?>">
					<button class='btn btn-outline-primary btn-sm order-detail' data-order_type="store" data-order_id="<?= $order['id']; ?>" title="<?= __('admin.order_details') ?>" data-bs-toggle="tooltip">
						<i class="fas fa-eye"></i>
					</button>
					<button class='btn btn-outline-info btn-sm order-transactions-toggle' data-order_type="store" data-order_id="<?= $order['id']; ?>" title="<?= __('admin.show_transactions') ?>" data-bs-toggle="tooltip">
						<i class="fas fa-list"></i>
					</button>
					<button class="btn btn-outline-danger btn-sm remove-order" data-order_type="store" data-order_id="<?= $order['id'] ?>" title="<?= __('admin.delete') ?>" data-bs-toggle="tooltip">
						<i class="fas fa-trash"></i>
					</button>
				</div>
			</td>
		</tr>
		<?php
			$jsOrders['store'][$order['id']] = 
				'<ul>'
					.'<li><b>'.__('admin.payment_method').' :</b> <span>'. $order['payment_method'] .'</span> </li>'
					.'<li><b>'.__('admin.transaction').' :</b> <span>'. $order['txn_id'] .'</span> </li>'
					.'<li><b>'. __('admin.ip').' :</b> <span>'. $order['ip'].'</span> </li>'
					.'<li><b>'. __('admin.country_code').' :</b> <span>'. $order['country_code'].'</span> </li>'
					.'<li><b>'. __('admin.currency_code').' :</b> <span>'. $order['currency_code'].'</span> </li>'
					.'<li><br>'
						.'<b>'. __('admin.products').'</b>'
							.'<table class="table table-white-space-normal detail-table">'
								.'<tr>'
									.'<th colspan="2">'. __('admin.name').'</th>'
									.'<th>'. __('admin.unit_price').'</th>'
									.'<th>'. __('admin.variation_price').'</th>'
									.'<th>'. __('admin.quantity').'</th>'
									.'<th>'. __('admin.commission_type').'</th>'
									.'<th>'. __('admin.total_discount').'</th>'
									.'<th>'. __('admin.total').'</th>'
								.'</tr>';
							foreach($order['products'] as $key => $product){
								$jsOrders['store'][$order['id']] .=
									'<tr>'
										.'<td><img src="'. $product['image'].'" style="width: 40px;height: 40px"></td>'
										.'<td class="max-width-150">';
										$combinationString = "";
										if(isset($product['variation']) && !empty($product['variation'])){
											$variation = json_decode($product['variation']);
											foreach($variation as $key => $value){
												if($key == 'colors')
													$combinationString .= ($combinationString == "") ? explode("-",$value)[1] : ",".explode("-",$value)[1];
												else
													$combinationString .= ($combinationString == "") ? $value : ",".$value;
											}
										}

								$jsOrders['store'][$order['id']] .= $product['product_name'];
								$jsOrders['store'][$order['id']] .= ($combinationString != "") ? "(".$combinationString.")" : "";
								if($product['coupon_discount'] > 0){
									$jsOrders['store'][$order['id']] .= 
			                            '<p class="couopn-code-text">'. __('admin.code') .' : '
			                            .'<span class="c-name">'.$product['coupon_code'] .'</span>'.__('admin.applied').'</p>';
			                    }
			                    	$jsOrders['store'][$order['id']] .= '</td>'
										.'<td>'. c_format($product['price']) .'</td>'
										.'<td>'. c_format(json_decode($product['variation'])->price) .'</td>'
										.'<td>'. $product['quantity'] .'</td>'
										.'<td>'. $product['commission_type'] .'</td>'
										.'<td>'. c_format($product['coupon_discount']) .'</td>'
										.'<td>'. c_format($product['total']) .'</td>'
									.'</tr>';
							}
							foreach($order['totals'] as $key => $total){
								$jsOrders['store'][$order['id']] .=
									'<tr>'
										.'<td colspan="5"></td>'
										.'<td>'. $total['text'].'</td>'
										.'<td>'. c_format($total['value']) .'</td>'
									.'</tr>';
							}
							$jsOrders['store'][$order['id']] .= 
							'</table>'
						.'</li>'
						.'<li>'
							.'<b>'.__('admin.payment_info').'</b>'
							.'<table class="table detail-table">'
								.'<thead>'
									.'<th class="border-top-0">'. __('admin.mode').'</th>'
									.'<th class="border-top-0">'. __('admin.transaction_id').'</th>'
									.'<th class="border-top-0">'. __('admin.payment_status').'</th>'
								.'</thead>'
								.'<tbody>';
								if($order['status'] == 0){
									$jsOrders['store'][$order['id']] .=
									'<tr>'
										.'<td colspan="100%">'
											.'<p class="text-muted text-center">'.__('admin.waiting_for_payment_status').'</p>'
										.'</td>'
									.'</tr>';
								}
								foreach($order['payment_history'] as $key => $value){
									$jsOrders['store'][$order['id']] .=
									'<tr>'
										.'<td>'. $value['payment_mode'] .'</td>'
										.'<td>'. $order['txn_id'] .'</td>'
										.'<td>'. $value['paypal_status'] .'</td>'
									.'</tr>';
								}
								$jsOrders['store'][$order['id']] .=
								'</tbody>'
							.'</table>'
						.'</li>'

						.'<li>'
							.'<b>'. __('admin.order_info'). '</b>'
							.'<table class="table detail-table">'
								.'<thead>'
									.'<tr>'
										.'<th width="50px">#</th>'
										.'<th width="150px">'. __('admin.status').'</th>'
										.'<th>'. __('admin.comment') .'</th>'
									.'</tr>'
								.'</thead>'
								.'<tbody>';
								if(!$order['order_history']){
									$jsOrders['store'][$order['id']] .=
									'<tr>'
										.'<td colspan="100%">'
											.'<p class="text-muted text-center">'. __('admin.no_any_order_status') .'</p>'
										.'</td>'
									.'</tr>';
								}
								foreach($order['order_history'] as $key => $value){
									$jsOrders['store'][$order['id']] .=
									'<tr>'
										.'<td>#'.$key.'</td>'
										.'<td>'. $status[$value['order_status_id']] .'</td>'
										.'<td style="white-space: pre-line;">'. $value['comment'] .'</td>'
									.'</tr>';
								}
								$jsOrders['store'][$order['id']] .=
								'</tbody>'
							.'</table>'
						.'</li>';
						
						if(!empty($order['comment'])){
	        			    $cmt = json_decode($order['comment'], true);
	            			 foreach($cmt as $c){
		        			    if(is_array($c))
		        			    	$jsOrders['store'][$order['id']] .=
		                			'<li><b>'.$c['title'] .' :</b> <span>'. $c['comment'] .'</span> </li>';
	        			    }
	        			}
	        			$jsOrders['store'][$order['id']] .= '</ul>';
	} else { ?>
		<tr id="order-row-<?= $order['id'] ?>" class="align-middle">
		    <td class="ps-3">
		    	<span class="badge bg-secondary fs-6"><?= orderId($order['order_id']);?></span>
		    </td>
		    <td>
		    	<strong class="text-success"><?= c_format($order['total']); ?></strong>
		    </td>
		    <td class="text-center"><?= $order['order_country_flag'];?></td>
		<td>
			<?php if(isset($order['script_name']) && $order['script_name'] == 's2s'): ?>
				<span class="badge bg-primary text-white"><i class="fas fa-server me-1"></i><?= __('admin.s2s_source_s2s') ?></span>
			<?php else: ?>
				<span class="badge bg-warning text-dark"><?= __('admin.external') ?></span>
			<?php endif; ?>
		</td>
		    <td>
		        <span class="badge <?= ($order['status'] == 1) ? 'bg-success' : 'bg-warning text-dark' ?> fs-6">
		            <?= $status[$order['status']] ?>
		        </span>
		    </td>
		    <td>
		        <div class="d-flex flex-column">
		            <?php
		            if($order['wallet_commission_status'] == 0) {
		                ?>
		                <span class="badge <?= ((int)$order['wallet_status'] > 0) ? 'bg-success text-white' : 'bg-warning text-dark' ?> mb-1">
		                    <?= $wallet_status[(int)$order['wallet_status']] ?>
		                </span>
		                <?php
		            } else {
		                echo commission_status($order['wallet_commission_status']);
		            }
		            ?>
		            <small class="text-muted fw-medium"><?= c_format($order['commission']) ?></small>
		        </div>
		    </td>
		    <td>
		    	<small class="text-muted"><?= date("M d, Y", strtotime($order['created_at'])); ?></small>
		    </td>
		    <td class="text-center pe-3">
		        <div class="btn-group" role="group" aria-label="<?= __('admin.order_actions') ?>">
		            <button class='btn btn-outline-primary btn-sm order-detail' data-order_type="ex" data-order_id="<?= $order['id'] ?>" title="<?= __('admin.order_details') ?>" data-bs-toggle="tooltip">
		                <i class="fas fa-eye"></i>
		            </button>
		            <button class='btn btn-outline-info btn-sm order-transactions-toggle' data-order_type="ex" data-order_id="<?= $order['id'] ?>" title="<?= __('admin.transaction_details') ?>" data-bs-toggle="tooltip">
		                <i class="fas fa-list"></i>
		            </button>
		            <button class="btn btn-outline-danger btn-sm remove-order" data-order_type="ex" data-order_id="<?= $order['id'] ?>" title="<?= __('admin.delete') ?>" data-bs-toggle="tooltip">
		                <i class="fas fa-trash"></i>
		            </button>
		        </div>
		    </td>
		</tr>

		<?php 
			$jsOrders['ex'][$order['id']] = 
                '<ul>'
					.'<li><b>'. __('admin.product_ids').' :</b> <span>'. $order['product_ids'].'</span></li>'
					.'<li><b>'. __('admin.total').' :</b> <span>'. $order['total'].'</span></li>'
					.'<li><b>'. __('admin.currency').' :</b> <span>'. $order['currency'].'</span></li>'
					.'<li><b>'. __('admin.commission_type').' :</b> <span>'. $order['commission_type'].'</span></li>'
					.'<li><b>'. __('admin.ip').' :</b> <span>'. $order['ip'].'</span></li>'
					.'<li><b>'. __('admin.country_code').' :</b> <span>'. $order['country_code'].'&nbsp;<img title="'. $order['country_code'].'" src="'. base_url('assets/template/images/flags/'. strtolower($order['country_code'])).'.png" width="25" height="15"</span></li>'
					.'<li><b>'. __('admin.website').' :</b> <span><a href="'. $order['base_url'].'" target="_blank">'. $order['base_url'].'</a></span></li>'
					.'<li><b>'. __('admin.s2s_conversion_source').' :</b> <span>'. ($order['script_name'] == 's2s' ? '<span class="badge bg-primary"><i class="fas fa-server me-1"></i>'. __('admin.s2s_source_s2s') .'</span>' : ucfirst($order['script_name'])) .'</span></li>';

					$custom_data_raw = $order['custom_data'] ?? [];

					if (is_string($custom_data_raw)) {
					    $custom_data = json_decode($custom_data_raw, true);
					} elseif (is_array($custom_data_raw)) {
					    $custom_data = $custom_data_raw;
					} else {
					    $custom_data = [];
					}

					if (is_array($custom_data)) {
						foreach ($custom_data as $value){
						    if (is_array($value) && isset($value['key'], $value['value'])) {
						        $jsOrders['store'][$order['id']] .=  
						            '<li class="">'
						                .'<b>' . htmlspecialchars($value['key']) . ':</b> <span>' . htmlspecialchars($value['value']) . '</span>'
						            .'</li>';
						    } elseif (is_object($value) && isset($value->key, $value->value)) {
						        $jsOrders['store'][$order['id']] .=  
						            '<li class="">'
						                .'<b>' . htmlspecialchars($value->key) . ':</b> <span>' . htmlspecialchars($value->value) . '</span>'
						            .'</li>';
						    }
						}
					}
					$jsOrders['store'][$order['id']] .= '</ul>';
			}
		} ?>

<script type="text/javascript">
	var jsOrders = <?= json_encode($jsOrders) ?>;
</script>