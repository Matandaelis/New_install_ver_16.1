<?php
/**
 * Default theme — Product detail page (Amazon wireframe)
 *
 * @contract  Store API v1 — page: product
 * @see       Store_cart_payload::page_product()
 * @see       /store/api/v1/pages/product  (requires ?id=PRODUCT_ID)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $product              array   Product data {id, name, price, old_price, description, product_featured_image, ...}
 *   $all_images           array   All product images [{image}, ...]
 *   $all_videos           array   All product videos [{video_url}, ...]
 *   $review_count         int     Total number of reviews
 *   $review_list          array   Reviews [{id, rating, comment, user_name, created_at}, ...]
 *   $allowReview          bool    Whether the current user can submit a review
 *   $is_wishlisted_class  string  CSS class 'active' if product is in wishlist, '' otherwise
 *   $add_coupon_url       string  URL to apply a coupon code
 *   $order_id             int|null Latest order ID containing this product (for review linking)
 *   $user                 array   Logged-in user data; empty array if guest (alias of $client)
 *   $login_usr            array   Alias of $user / $client
 *   $related_products     array   Related/upsell products
 *   $upsell_products      array   Upsell funnel products (optional)
 *   $store_setting        array   Store settings (alias of global)
 */
?>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/store/default/slick/') ?>slick.css"/>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/store/default/slick/') ?>slick-theme.css"/>
<script type="text/javascript" src="<?= base_url('assets/store/default/slick/') ?>slick.js"></script>

<?php 
$product_featured_image = '';
if (!empty($product['product_featured_image'])) {
    if (strpos($product['product_featured_image'], 'http://') === 0 || strpos($product['product_featured_image'], 'https://') === 0) {
        $product_featured_image = $product['product_featured_image'];
    } else {
        $product_featured_image = base_url('assets/images/product/upload/thumb/'. $product['product_featured_image']);
    }
} else {
    $product_featured_image = base_url('assets/store/default/img/pr-img.png');
}

$allimages = $all_images ?? [];
$allvideo  = $all_videos ?? [];

$inStock = ((int)($product['product_quantity'] ?? 0) > 0 || (int)($product['product_quantity'] ?? 0) == -1);
?>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": <?= json_encode($product['product_name']) ?>,
    "description": <?= json_encode(strip_tags($product['product_short_description'] ?? '')) ?>,
    "image": <?= json_encode($product_featured_image) ?>,
    "sku": <?= json_encode($product['product_id']) ?>,
    "url": <?= json_encode(current_url()) ?>,
    "offers": {
        "@type": "Offer",
        "price": <?= json_encode(number_format((float)($product['product_price'] ?? 0), 2, '.', '')) ?>,
        "priceCurrency": <?= json_encode($store_setting['currency_code'] ?? 'USD') ?>,
        "availability": "<?= $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>",
        "url": <?= json_encode(current_url()) ?>
    }
    <?php if (($review_count ?? 0) > 0): ?>,
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?= $avg_rating ?>",
        "reviewCount": "<?= $review_count ?>"
    }
    <?php endif; ?>
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": <?= json_encode(base_url('store')) ?>},
        {"@type": "ListItem", "position": 2, "name": "Products", "item": <?= json_encode(base_url('store/category')) ?>},
        {"@type": "ListItem", "position": 3, "name": <?= json_encode($product['product_name']) ?>}
    ]
}
</script>

<section class="amz-product" data-product-id="<?= $product['product_id'] ?>" data-product-image="<?= htmlspecialchars($product_featured_image) ?>">
  <div class="container">

    <nav class="amz-breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="<?= $home_link ?>"><?= __('store.home') ?></a></li>
        <li><a href="<?= $base_url ?>category"><?= __('store.categories') ?></a></li>
        <?php if ($categories): ?>
          <?php foreach ($categories as $value): ?>
            <li><a href="<?= base_url('store/category/'. $value['slug']) ?>"><?= $value['name'] ?></a></li>
          <?php endforeach; ?>
        <?php endif; ?>
        <li class="active"><?= htmlspecialchars($product['product_name'] ?? 'Product') ?></li>
      </ol>
    </nav>

    <div class="amz-product-main">

      <!-- LEFT: Image Gallery -->
      <div class="amz-product-gallery">
        <div class="amz-gallery-thumbs" id="amzThumbs">
          <div class="amz-thumb active" data-index="0">
            <img src="<?= $product_featured_image ?>" alt="<?= __('store.featured_image') ?>" onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>'">
          </div>
          <?php $i = 1; foreach ($allimages as $images): ?>
            <?php
              $imgPath = $images['product_media_upload_path'];
              $img = (preg_match("/^(http:\/\/|https:\/\/|s3:\/\/).*/", $imgPath)) ? $imgPath : base_url('assets/images/product/upload/thumb/'.$imgPath);
            ?>
            <div class="amz-thumb" data-index="<?= $i ?>">
              <img src="<?= $img ?>" alt="<?= __('store.product_image') ?> <?= $i ?>" onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>'">
            </div>
          <?php $i++; endforeach; ?>
          <?php foreach ($allvideo as $videos): ?>
            <?php $thumbImg = !empty($videos['product_media_upload_video_image']) ? base_url('assets/images/product/upload/thumb/'.$videos['product_media_upload_video_image']) : base_url('assets/store/default/img/pr-img.png'); ?>
            <div class="amz-thumb" data-index="<?= $i ?>" data-video="<?= $videos['product_media_upload_path'] ?>">
              <img src="<?= $thumbImg ?>" alt="<?= __('store.product_video_image') ?> <?= $i ?>" onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>'">
            </div>
          <?php $i++; endforeach; ?>
        </div>

        <div class="amz-gallery-main">
          <div class="amz-main-image" id="amzMainImage">
            <img src="<?= $product_featured_image ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>'">
          </div>
        </div>
      </div>

      <!-- RIGHT: Product Info -->
      <div class="amz-product-info">

        <h1 class="amz-product-title"><?= htmlspecialchars($product['product_name'] ?? 'Product') ?></h1>

        <div class="amz-product-meta">
          <?php
            $ratingAvg = 0;
            $totalRating = 0;
            $numberOfRatings = 0;
            if (!empty($ratings)):
              foreach ($ratings as $rating):
                $totalRating += (int)$rating['rating_number'];
                $numberOfRatings++;
              endforeach;
            endif;
            if ($totalRating > 0 && $numberOfRatings > 0):
              $ratingAvg = number_format(($totalRating / $numberOfRatings), 0);
            endif;
          ?>
          <?php if ($numberOfRatings > 0): ?>
          <div class="amz-rating">
            <div class="amz-stars">
              <?php for ($i = 0; $i < $ratingAvg; $i++): ?>
                <svg class="star filled" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              <?php endfor; ?>
              <?php for ($i = $ratingAvg; $i < 5; $i++): ?>
                <svg class="star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              <?php endfor; ?>
            </div>
            <a href="#amz-reviews" class="amz-rating-count"><?= $numberOfRatings ?> <?= __('store.customer_reviews') ?></a>
          </div>
          <?php endif; ?>

          <div class="amz-sku-row">
            <span><?= __('store.sku') ?>: <?= htmlspecialchars($product['product_sku'] ?? 'N/A') ?></span>
            <span class="amz-sep">|</span>
            <span><i class="fas fa-user"></i> <a href="<?= base_url('store/productionstore/'.base64_encode($product['product_created_by'])) ?>"><?= $product['product_created_by_name'] ?></a></span>
            <span class="amz-sep">|</span>
            <span><?= __('store.promoted_by') ?>:
              <?php if (!empty($user['store_slug']) && !empty($user['username'])): ?>
                <a href="<?= base_url('store/'.$user['store_slug']) ?>"><?= $user['username'] ?></a>
              <?php else: ?>
                <?= !empty($user['username']) ? $user['username'] : __('store.admin') ?>
              <?php endif; ?>
            </span>
            <span class="amz-sep">|</span>
            <span><?= __('store.category') ?>:
              <?php if ($categories): ?>
                <?php foreach ($categories as $value): ?>
                  <a href="<?= base_url('store/category/'. $value['slug']) ?>" class="amz-category-link"><?= $value['name'] ?></a>
                <?php endforeach; ?>
              <?php else: ?>
                <?= __('store.not_available') ?>
              <?php endif; ?>
            </span>
          </div>
        </div>

        <div class="amz-divider"></div>

        <p class="amz-short-desc"><?= !empty($product['product_short_description']) ? $product['product_short_description'] : __('store.product_short_description_if_not_exist') ?></p>

        <?php
          $variations = [];
          if (isset($product['product_variations']) && !empty($product['product_variations'])):
            $variations = json_decode($product['product_variations']);
        ?>
          <?php foreach ($variations as $key => $value): ?>
            <div class="amz-variation-row">
              <span class="amz-var-label"><?= ucwords(strtolower($key)) ?></span>
              <div class="amz-var-options">
                <?php foreach ($value as $opt):
                  $this_price = isset($opt->price) ? $opt->price : 0;
                  $this_name = isset($opt->name) ? $opt->name : $opt;
                ?>
                  <?php if ($key === 'colors'): ?>
                    <span class="amz-var-swatch" data-variation-type="<?= $key ?>" data-variation-price="<?= $this_price ?>" data-variation-code="<?= $opt->code ?>" data-variation-name="<?= $opt->name ?>" style="--swatch-color: <?= $opt->code ?>; <?= ($opt->code === '#FFFFFF') ? 'border-color:#999;' : '' ?>">
                      <span class="amz-swatch-inner"></span>
                      <span class="amz-swatch-name"><?= $opt->name ?></span>
                    </span>
                  <?php else: ?>
                    <span class="amz-var-option" data-variation-type="<?= $key ?>" data-variation-price="<?= $this_price ?>" data-variation-option="<?= $this_name ?>"><?= $this_name ?></span>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <div class="amz-divider"></div>

        <div class="amz-price-block">
          <?php if (!empty($product['product_msrp'])): ?>
            <span class="amz-list-price" data-price="<?= $product['product_msrp'] ?>"><?= c_format($product['product_msrp']) ?></span>
          <?php endif; ?>
          <span class="amz-price" data-price="<?= $product['product_price'] ?>"><?= !empty($product['product_price']) ? c_format($product['product_price']) : '' ?></span>
        </div>

        <div class="amz-qty-row">
          <label><?= __('store.quantity') ?? 'Qty' ?></label>
          <div class="amz-qty-selector">
            <button type="button" class="amz-qty-btn amz-qty-sub" <?= ($product['product_type'] == 'video' || $product['product_type'] == 'videolink' || $product['product_type'] == 'downloadable') ? 'disabled' : '' ?>><i class="fa fa-minus"></i></button>
            <input type="text" id="product-quantity" name="quantity" value="1" min="1" <?= ($product['product_type'] == 'video' || $product['product_type'] == 'videolink' || $product['product_type'] == 'downloadable') ? 'disabled' : '' ?>>
            <button type="button" class="amz-qty-btn amz-qty-add" <?= ($product['product_type'] == 'video' || $product['product_type'] == 'videolink' || $product['product_type'] == 'downloadable') ? 'disabled' : '' ?>><i class="fa fa-plus"></i></button>
          </div>
        </div>

        <div class="amz-action-row">
          <?php if ($order_id && ($product['product_type'] == 'video' || $product['product_type'] == 'videolink')): ?>
            <a href="<?= base_url('store/vieworderdetails/'.$order_id."?referance=".$product['product_id']) ?>" class="amz-btn amz-btn-cart"><?= __('store.start_course') ?></a>
          <?php else: ?>
            <?php if ($inStock): ?>
              <button data-product_id="<?= $product['product_id'] ?>" data-product_name="<?= htmlspecialchars($product['product_name'] ?? 'Product') ?>" class="amz-btn amz-btn-cart btn-cart">
                <?= __('store.add_to_cart') ?>
                <?php if ($product['product_quantity'] > 0): ?>
                  <span class="amz-stock-count">(<?= $product['product_quantity'] ?>)</span>
                <?php endif; ?>
              </button>
            <?php else: ?>
              <button class="amz-btn amz-btn-cart" disabled><?= __('store.out_of_stock') ?></button>
            <?php endif; ?>
          <?php endif; ?>
        </div>

        <div class="amz-coupon-row">
          <div class="amz-coupon-input-group">
            <input class="amz-coupon-input coupon-code" type="text" name="coupon" placeholder="<?= __('store.enter_coupon_code') ?>">
            <button class="amz-btn amz-btn-coupon btn-apply-coupon" title="<?= __('store.apply_coupon_code') ?>"><?= __('store.apply') ?></button>
          </div>
          <div class="coupon-msg mt-1"></div>
        </div>

        <?php $product_tags_arr = parse_product_tags($product['product_tags'] ?? ''); ?>
        <?php if (!empty($product_tags_arr)): ?>
          <div class="amz-tags-row">
            <i class="fas fa-tags"></i>
            <?php foreach ($product_tags_arr as $tag): ?>
              <a href="<?= base_url('store/category?tag=' . urlencode($tag)) ?>" class="amz-tag"><?= htmlspecialchars($tag) ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="amz-divider"></div>

        <div class="amz-wishlist-row">
          <span id="btn-add-to-wishlist" class="amz-wishlist-btn <?= $is_wishlisted_class ?>">
            <i class="fa fa-heart"></i> <?= __('store.add_to_wishlist') ?? 'Add to Wishlist' ?>
          </span>
          <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
          <span class="amz-share-btn" data-social-share data-share-url="<?= $actual_link ?>">
            <i class="fas fa-share-alt"></i> <?= __('store.share') ?? 'Share' ?>
          </span>
        </div>

      </div>
    </div>
  </div>
</section>

<section class="amz-product-tabs" id="amz-reviews">
  <div class="container">
    <div class="amz-tabs-header">
      <a href="javascript:void(0);" class="amz-tab active dbtn"><?= __('store.description') ?></a>
      <a href="javascript:void(0);" class="amz-tab rbtn"><?= __('store.reviews') ?></a>
    </div>

    <div class="amz-tab-content discription-reviews-content">
      <?= !empty($product['product_description']) ? html_entity_decode($product['product_description']) : '<p class="text-muted text-center p-4">'.__('store.product_description_not_available').'</p>' ?>
    </div>

    <div class="amz-tab-content product-reviews-all" style="display:none;">
      <?php if (!empty($ratings)): ?>
        <?php foreach ($ratings as $rating): ?>
          <div class="amz-review-card">
            <div class="amz-review-header">
              <?php if ($vendormanagereviewimage == 1): ?>
                <div class="amz-review-avatar">
                  <?php if (!empty($rating['avatar'])): ?>
                    <img src="<?= base_url('assets/images/users/'.$rating['avatar']) ?>" onerror="this.src='<?= base_url('assets/images/no-user_image.jpg') ?>'">
                  <?php else: ?>
                    <img src="<?= base_url('assets/images/no-user_image.jpg') ?>" alt="<?= __('store.user') ?>">
                  <?php endif; ?>
                </div>
              <?php endif; ?>
              <div class="amz-review-meta">
                <h4><?= $rating['firstname']." ".$rating['lastname'] ?></h4>
                <div class="amz-review-stars">
                  <?php
                    $rc = (int)$rating['rating_number'];
                    for ($i = 0; $i < $rc; $i++): ?>
                      <svg class="star filled" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                  <?php endfor; ?>
                  <?php while ($rc < 5): $rc++; ?>
                    <svg class="star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                  <?php endwhile; ?>
                </div>
                <span class="amz-review-date"><?= !empty($rating['rating_created']) ? $rating['rating_created'] : '' ?></span>
              </div>
            </div>
            <p class="amz-review-body"><?= !empty($rating['rating_comments']) ? $rating['rating_comments'] : '' ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-muted text-center p-4"><?= __('store.there_are_no_reviews_for_this_product') ?></p>
      <?php endif; ?>

      <?php if ($allowReview): ?>
        <div class="amz-review-form">
          <h3><?= __('store.write_a_review') ?></h3>
          <input name="user_id" id="user_id" type="hidden" value="<?= !empty($session) ? $session['id'] : '' ?>">
          <input name="product_id" value="<?= $product['product_id'] ?>" id="product_id" type="hidden">
          <div class="amz-form-group">
            <label><?= __('store.your_review') ?></label>
            <textarea name="comment" id="comment" placeholder="<?= __('store.enter_your_review') ?>" class="amz-form-control" rows="4"></textarea>
            <small class="text-danger"><?= __('store.note') ?> <?= __('store.html_is_not_translated') ?></small>
          </div>
          <div class="amz-form-group">
            <label><?= __('store.email') ?></label>
            <input name="email" id="post_email" placeholder="<?= __('store.enter_your_email') ?>" type="email" class="amz-form-control">
          </div>
          <div class="amz-form-group">
            <label><?= __('store.rating') ?></label>
            <div class="give-rating"></div>
            <input name="rating" value="0" id="rating_star" type="hidden">
          </div>
          <button class="amz-btn amz-btn-submit" name="submit" id="submit" onclick="processRating()"><?= __('store.leave_a_review') ?></button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="amz-related">
  <div class="container">
    <h2 class="amz-section-title"><?= __('store.similar_products') ?></h2>
    <div class="product-row product-list-related"></div>
    <a href="javascript:void(0);" class="see-more see-more-related" data-next_page="1">
      <?= __('store.show_more') ?>
    </a>
  </div>
</section>

<?= $social_share_modal; ?>

<?php include 'product-list-template.php'; ?>

<script type="text/javascript">
$(document).ready(function() {

  load_Product(null, {
    product_id : $('#product-category').data('product_id') || $('[data-product-id]').data('product-id'),
    category_id : $('#product-category').data('category_id')
  });

  // Thumbnail click -> swap main image
  $(document).on('click', '.amz-thumb', function() {
    var $t = $(this);
    $('.amz-thumb').removeClass('active');
    $t.addClass('active');
    var src = $t.find('img').attr('src');
    var videoUrl = $t.data('video');
    if (videoUrl) {
      var embedUrl = videoUrl.replace('watch?v=', 'embed/');
      $('#amzMainImage').html('<iframe width="100%" height="100%" src="' + embedUrl + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
    } else {
      $('#amzMainImage').html('<img src="' + src + '" alt="Product">');
    }
  });

  // Wishlist
  $(document).on('click', '#btn-add-to-wishlist', function() {
    <?php if ($login_usr == false): ?>
      window.location.replace('<?= base_url('store/login') ?>');
    <?php else: ?>
      var $el = $(this);
      $el.toggleClass('active');
      $.ajax({
        url: '<?= base_url('Store/toggle_wishlist') ?>',
        type: 'POST',
        dataType: 'json',
        data: { product_id: <?= $product['product_id'] ?> }
      });
    <?php endif; ?>
  });

  // Tabs
  $('.amz-tabs-header .dbtn').click(function() {
    $('.amz-tab').removeClass('active');
    $(this).addClass('active');
    $('.discription-reviews-content').show();
    $('.product-reviews-all').hide();
  });
  $('.amz-tabs-header .rbtn').click(function() {
    $('.amz-tab').removeClass('active');
    $(this).addClass('active');
    $('.discription-reviews-content').hide();
    $('.product-reviews-all').show();
  });

  // Star rating
  if ($('.give-rating').length) {
    $('.give-rating').starRating({
      initialRating: 0,
      starSize: 20,
      readOnly: false,
      disableAfterRate: false,
      callback: function(currentRating, $el) {
        $('#rating_star').val(currentRating);
      }
    });
  }

  // Load related
  $(document).on('click', '.see-more', function() {
    load_Product(null, {
      next_page: $(this).data('next_page'),
      product_id: $('#product-category').data('product_id') || $('[data-product-id]').data('product-id'),
      category_id: $('#product-category').data('category_id')
    });
  });

  // Coupon
  $(document).on('click', '.btn-apply-coupon', function() {
    var coupon_code = $('.amz-coupon-input').val();
    var product_id = '<?= $product['product_id'] ?>';
    if (coupon_code != '') {
      $this = $(this);
      $.ajax({
        url: '<?= $add_coupon_url ?>',
        type: 'POST',
        dataType: 'json',
        data: { product_id: product_id, coupon_code: coupon_code },
        beforeSend: function() { $this.btn('loading'); },
        complete: function() { $this.btn('reset'); },
        success: function(json) {
          $('.coupon-msg').html('');
          if (json['success']) $(".coupon-msg").html("<div class='amz-alert amz-alert-success'>" + json['success'] + "</div>");
          if (json['error']) $(".coupon-msg").html("<div class='amz-alert amz-alert-danger'>" + json['error'] + "</div>");
        }
      });
    }
  });

  // Qty buttons
  $(document).on('click', '.amz-qty-add', function() {
    var $input = $(this).siblings('input');
    if (parseInt($input.val()) < 350) $input.val(parseInt($input.val()) + 1);
  });
  $(document).on('click', '.amz-qty-sub', function() {
    var $input = $(this).siblings('input');
    if (parseInt($input.val()) > 1) $input.val(parseInt($input.val()) - 1);
  });

  // Variations
  $(document).on('click', '.amz-var-option, .amz-var-swatch', function() {
    $(this).parent().find('.active').removeClass('active');
    $(this).addClass('active');
    display_price_changes();
  });
});

function processRating() {
  var comment = $('#comment').val();
  var rating_star = $('#rating_star').val();
  if (comment != '' && rating_star != 0) {
    $('#submit').prop('disabled', true);
    $.ajax({
      type: 'POST',
      url: '<?= base_url() ?>product/rating',
      data: {
        product_id: $('#product_id').val(),
        user_id: $('#user_id').val(),
        comment: comment,
        email: $('#post_email').val(),
        number: rating_star
      },
      success: function() { window.location.reload(); }
    });
  } else {
    alert('<?= __('store.please_write_some_comment') ?>');
  }
}

function load_Product(search, postData) {
  var data = postData || {};
  data.search = search;
  data.request_page = 'product-details';
  var ajaxReq = $.ajax({
    url: '<?= base_url() ?>Store/load_Product',
    type: 'POST',
    dataType: 'JSON',
    data: data,
    success: function(res) {
      if (res.related) {
        if (postData.next_page && postData.next_page > 1) {
          $('.product-list-related').append(Mustache.render($('#product-list-template').html(), res.related));
        } else {
          $('.product-list-related').html(Mustache.render($('#product-list-template').html(), res.related));
        }
        $('.see-more-related').data('next_page', res.related.next_page);
        if (res.related.is_last_page) $('.see-more-related').hide();
      }
    }
  });
}

function display_price_changes() {
  var variationSelectedPrice = 0;
  $('.amz-var-options').each(function() {
    var $active = $(this).find('.active');
    if ($active.length) variationSelectedPrice += parseFloat($active.data('variation-price')) || 0;
  });
  var currencyRatio = '<?= str_replace(',', '', c_format(1, false)) ?>';
  var currency = $('a[data-currency-symbol]').data('currency-symbol');
  var product_regular_price = $('.amz-list-price').attr('data-price');
  var product_sale_price = $('.amz-price').attr('data-price');
  $.ajax({
    type: 'POST',
    url: '<?= base_url() ?>product/displayprice',
    data: {
      currencyRatio: currencyRatio,
      currency: currency,
      variationSelectedPrice: variationSelectedPrice,
      product_regular_price: product_regular_price,
      product_sale_price: product_sale_price
    },
    success: function(response) {
      var obj = jQuery.parseJSON(response);
      $('.amz-price').text(obj.value1);
      $('.amz-list-price').text(obj.value2);
    }
  });
}
</script>

<div class="container amz-cross-sell">
  <h5 class="amz-section-title"><i class="fas fa-fire me-2 text-warning"></i><?= __('store.customers_also_bought') ?></h5>
  <div id="recommendations-container" data-product-id="<?= $product['product_id'] ?>"></div>
</div>

<div class="amz-sticky-bar">
  <div class="container">
    <div class="amz-sticky-inner">
      <div class="amz-sticky-left">
        <img src="<?= $product_featured_image ?>" alt="">
        <span class="amz-sticky-name d-none d-md-inline"><?= htmlspecialchars($product['product_name']) ?></span>
      </div>
      <div class="amz-sticky-right">
        <span class="amz-sticky-price"><?= c_format($product['product_price'] ?? 0) ?></span>
        <button type="button" class="amz-btn amz-btn-cart-sm" onclick="document.querySelector('.amz-btn-cart, .btn-cart, [name=add_to_cart]')?.click()">
          <i class="fas fa-cart-plus me-1"></i><?= __('store.sticky_add_to_cart') ?>
        </button>
      </div>
    </div>
  </div>
</div>
