<?php
/**
 * Starter 2026 — User Purchased Products Page
 *
 * @contract  Store API v1 — page: user_products
 * @auth      required
 * @note      Filename typo (user-proudcts.php) is intentional — matches the controller route.
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $products  array   Products purchased by this customer
 *   $user      array   Logged-in customer (alias of $client)
 *   $settings  array   Store settings
 */
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.my_products') ?? 'My Products' ?></span>
    </nav>
</div>

<!-- Account Navigation -->
<div class="container">
    <div class="s26-account-nav">
        <a href="<?= $base_url ?>profile" class="s26-account-nav__link">
            <i class="fas fa-user"></i> <?= __('store.profile') ?? 'Profile' ?>
        </a>
        <a href="<?= $base_url ?>order" class="s26-account-nav__link">
            <i class="fas fa-gift"></i> <?= __('store.orders') ?? 'Orders' ?>
        </a>
        <a href="<?= $base_url ?>shipping" class="s26-account-nav__link">
            <i class="fas fa-truck"></i> <?= __('store.shipping') ?? 'Shipping' ?>
        </a>
        <a href="<?= $base_url ?>wishlist" class="s26-account-nav__link">
            <i class="fas fa-heart"></i> <?= __('store.wishlist') ?? 'Wishlist' ?>
        </a>
        <a href="<?= $base_url ?>logout" class="s26-account-nav__link s26-account-nav__link--danger">
            <i class="fas fa-power-off"></i> <?= __('store.logout') ?? 'Logout' ?>
        </a>
    </div>
</div>

<section class="s26-user-products-page">
    <div class="container">

        <h1 class="s26-page-title mb-4">
            <i class="fas fa-box-open" style="color:var(--s26-primary);font-size:0.8em"></i>
            <?= __('store.my_products') ?? 'My Products' ?>
        </h1>

        <?php if (isset($products) && !empty($products)): ?>

        <div class="row g-3 g-lg-4">
            <?php foreach ($products as $product):
                $img = !empty($product['product_featured_image'])
                    ? ((strpos($product['product_featured_image'], 'http') === 0) ? $product['product_featured_image'] : base_url('assets/images/product/upload/thumb/' . $product['product_featured_image']))
                    : base_url('assets/store/default/img/pr-img.png');
                $url = !empty($product['product_slug'])
                    ? base_url('store/product/' . $product['product_slug'])
                    : '#';
            ?>
            <div class="col-6 col-md-4 col-lg-3 s26-reveal">
                <div class="s26-product-card s26-user-product-card">
                    <div class="card-img-wrapper">
                        <a href="<?= $url ?>">
                            <img src="<?= $img ?>" alt="<?= htmlspecialchars($product['product_name'] ?? '') ?>" loading="lazy"
                                 onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'">
                        </a>
                        <!-- Purchased badge -->
                        <span class="s26-badge s26-badge-purchased">
                            <i class="fas fa-check-circle"></i>
                            <?= __('store.purchased') ?? 'Purchased' ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <a href="<?= $url ?>" class="product-title"><?= htmlspecialchars($product['product_name'] ?? '') ?></a>
                        <?php if (!empty($product['price'])): ?>
                        <div class="product-price"><?= c_format($product['price']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($product['quantity'])): ?>
                        <small class="text-muted"><?= __('store.quantity') ?? 'Qty' ?>: <?= $product['quantity'] ?></small>
                        <?php endif; ?>

                        <!-- Quick actions for digital products -->
                        <?php if (isset($product['product_type']) && in_array($product['product_type'], ['video', 'videolink', 'downloadable'])): ?>
                        <div class="mt-2">
                            <?php if ($product['product_type'] == 'video' || $product['product_type'] == 'videolink'): ?>
                            <a href="<?= base_url('store/vieworderdetails/' . ($product['order_id'] ?? '') . '?referance=' . $product['product_id']) ?>"
                               class="s26-btn-primary s26-btn--sm w-100 justify-content-center" target="_blank">
                                <i class="fas fa-play-circle"></i> <?= __('store.start_course') ?? 'Start Course' ?>
                            </a>
                            <?php else: ?>
                            <a href="<?= base_url('store/vieworderdetails/' . ($product['order_id'] ?? '')) ?>"
                               class="s26-btn-outline s26-btn--sm w-100 justify-content-center">
                                <i class="fas fa-download"></i> <?= __('store.download') ?? 'Download' ?>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>

        <div class="s26-empty-state">
            <div class="s26-empty-state__icon"><i class="fas fa-box-open"></i></div>
            <h2 class="s26-empty-state__title"><?= __('store.no_purchased_products') ?? 'No purchased products' ?></h2>
            <p class="s26-empty-state__text"><?= __('store.no_purchased_desc') ?? 'Products you purchase will appear here for easy access.' ?></p>
            <a href="<?= $base_url ?>category" class="s26-btn-primary">
                <i class="fas fa-shopping-bag"></i> <?= __('store.continue_shopping') ?? 'Start Shopping' ?>
            </a>
        </div>

        <?php endif; ?>

    </div>
</section>
