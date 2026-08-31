<?php
/**
 * Default theme — Order items detail partial (embedded inside view_order.php)
 *
 * @contract  Store API v1 — fragment: view_order_details (partial used by view_order.php)
 * @see       Store_cart_payload::page_view_order()
 *
 * VARIABLES (inherited from view_order.php scope)
 *   $order   array   Full order data {id, total, currency, status, items[], customer{}, ...}
 *   $status  int     Order status code: 0=Pending, 1=Completed, 2=Refunded, 3=Cancelled
 */
?>
<section class="profile-page">
	<div class="container">
		<div class="profile-page-wrapper">
			<div class="profile-sidebar">
				<h3><?= __('store.user_menu') ?></h3>
				<ul>
					<li><a href="<?= $base_url ?>profile"><?= __('store.profile') ?></a></li>
				<li><a class="active" href="<?= $base_url ?>order"><?= __('store.order') ?></a></li>
				<li><a href="<?= $base_url ?>my_courses"><?= __('store.my_courses') ?></a></li>
				<li><a href="<?= $base_url ?>shipping"><?= __('store.shipping') ?></a></li>
					<li><a href="<?= $base_url ?>wishlist"><?= __('store.wishlist') ?></a></li>
					<li><a href="<?= $base_url ?>logout"><?= __('store.logout') ?></a></li>
				</ul>
			</div>
			<div class="profile-main">
				<h2><?= $products[0]['product_name'] ?></h2>
				<div class="row">
					<div class="cart-wrapper order-details-bottom-left my-orders col-7" id="video_div">
					</div>
					<div class="cart-wrapper order-details-bottom-right col-5">
						<div>
							<h2><?= __('store.video_playlist') ?></h2>
						</div>
						<div  style="max-height: 320px; overflow-y: scroll;">
							<?php foreach ($products as $key => $product) { 
								foreach ($product['downloadable_files'] as $downloadable_filess) {
						$imageURL = $Title = $type = $video_id = "";
								if ($product['product_type'] === 'videolink') {
									$videoInfo = get_video_info($downloadable_filess['videotext']);
									$imageURL  = $videoInfo['imageURL'];
									$Title     = $videoInfo['title'];
									$type      = $videoInfo['type'];
									$video_id  = $videoInfo['video_id'];
								}
									if($product['product_type'] =='video') {
										$imageURL = base_url('application/downloads/').$downloadable_filess['thumb'];
										$Title = $downloadable_filess['videotext'];
										$video_id = $downloadable_filess['name'];
										$type = 'video';
									} if ($video_id !=""): ?>

									<a href="" title="<?=$Title?>" class="playvideo" data-type="<?=$type?>" data-value="<?=$video_id?>">
										<ul class="cart-items-row ">
											<li>
											<img src="<?=$imageURL ?>" alt="<?=$Title?>" style="width: 50px;height: 50px;">
											</li>
											<li style="width: 100%;">
												<p><?=$Title?></p>
											</li>
										</ul>
									</a>
								<?php endif ?>
							<?php  } } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	   
</section>

<script type="text/javascript">
	var orderId = '<?= $order['id']?>';
	$(document).ready(function() {
		$(document).on('click',".playvideo",function(e){
			e.preventDefault();
			var type = $(this).data('type');
			var videoId = $(this).data('value');
			if(type=="youtube") {
				$("#video_div").html('<iframe width="620" height="400" src="https://www.youtube.com/embed/'+videoId+'"></iframe>');
			}
			if(type=="video") {
				var  base_url = '<?=base_url("store/play?track=")?>'+videoId+'&orderId='+orderId;
				$("#video_div").html('<video controls preload="auto" src="'+base_url+'"  width="100%" controlsList="nodownload"  oncontextmenu="return false;" height="400px" autoplay></video>');
			}
			if( type=="vimeo") {
				$("#video_div").html('<iframe src="https://player.vimeo.com/video/'+videoId+'" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>');
			}
		});
		$(".playvideo")[0].click();
	});
</script>