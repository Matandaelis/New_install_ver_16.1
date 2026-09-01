<?php
/**
 * Default theme — Order items detail partial (video/course viewer)
 *
 * @contract  Store API v1 — fragment: view_order_details (partial used by view_order.php)
 * @see       Store_cart_payload::page_view_order()
 *
 * VARIABLES (inherited from view_order.php scope)
 *   $order    array   Full order data {id, total, currency, status, items[], customer{}, ...}
 *   $products array   Order products with downloadable_files
 *   $status   int     Order status code: 0=Pending, 1=Completed, 2=Refunded, 3=Cancelled
 */
?>
<section class="amz-order-details">
    <div class="container">
        <h2 class="amz-order-details__title"><?= $products[0]['product_name'] ?></h2>
        <div class="amz-order-details__layout">
            <!-- Video Player -->
            <div class="amz-order-details__player" id="video_div">
            </div>
            <!-- Playlist -->
            <div class="amz-order-details__sidebar">
                <h5 class="amz-order-details__sidebar-title"><?= __('store.video_playlist') ?></h5>
                <div class="amz-order-details__playlist">
                    <?php foreach ($products as $product): foreach ($product['downloadable_files'] as $downloadable_file):
                            $imageURL = $Title = $type = $video_id = "";
                            if ($product['product_type'] === 'videolink') {
                                $videoInfo = get_video_info($downloadable_file['videotext']);
                                $imageURL  = $videoInfo['imageURL'];
                                $Title     = $videoInfo['title'];
                                $type      = $videoInfo['type'];
                                $video_id  = $videoInfo['video_id'];
                            }
                            if ($product['product_type'] == 'video') {
                                $imageURL = base_url('application/downloads/').$downloadable_file['thumb'];
                                $Title = $downloadable_file['videotext'];
                                $video_id = $downloadable_file['name'];
                                $type = 'video';
                            }
                            if ($video_id != ""): ?>
                            <a href="#" title="<?= $Title ?>" class="amz-playlist-item playvideo" data-type="<?= $type ?>" data-value="<?= $video_id ?>">
                                <img src="<?= $imageURL ?>" alt="<?= $Title ?>" class="amz-playlist-item__thumb" loading="lazy">
                                <span class="amz-playlist-item__title"><?= $Title ?></span>
                            </a>
                            <?php endif; endforeach; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var orderId = '<?= $order['id'] ?>';
    $(document).ready(function() {
        $(document).on('click', '.playvideo', function(e) {
            e.preventDefault();
            var type = $(this).data('type');
            var videoId = $(this).data('value');
            if (type == "youtube") {
                $("#video_div").html('<iframe width="100%" height="400" src="https://www.youtube.com/embed/' + videoId + '" allowfullscreen></iframe>');
            }
            if (type == "video") {
                var url = '<?= base_url("store/play?track=") ?>' + videoId + '&orderId=' + orderId;
                $("#video_div").html('<video controls preload="auto" src="' + url + '" width="100%" controlsList="nodownload" oncontextmenu="return false;" height="400" autoplay></video>');
            }
            if (type == "vimeo") {
                $("#video_div").html('<iframe src="https://player.vimeo.com/video/' + videoId + '" width="100%" height="400" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>');
            }
        });
        $(".playvideo").first().trigger('click');
    });
</script>
