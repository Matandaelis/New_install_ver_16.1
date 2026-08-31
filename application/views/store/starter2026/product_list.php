<?php
/**
 * Starter 2026 — Product Grid (AJAX partial)
 *
 * @contract  Store API v1 — fragment: product_grid
 * @note      Returned by POST store/load_Product. Renders product card grid.
 *
 * GLOBALS  $store_setting, $base_url, $add_tocart_url
 *
 * VARIABLES
 *   $products        array   Products to display [{product_id, product_name, product_price, product_featured_image, slug, avg_star, cnt}]
 *   $product_ratings array   Map of product_id → avg_star, cnt (for rating display)
 *   $user_id         int     Affiliate user ID
 */
$currency = $store_setting['currency_sign'] ?? '$';
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.products') ?? 'Products' ?></span>
    </nav>
</div>

<section class="s26-product-list-page">
    <div class="container">

        <div class="s26-section-header">
            <div>
                <p class="s26-section-eyebrow"><?= __('store.browse') ?? 'Browse' ?></p>
                <h1 class="s26-section-title" style="font-size:clamp(1.5rem,3vw,2rem)"><?= __('store.all_products') ?? 'All Products' ?></h1>
            </div>
            <?php if (isset($products_list) && !empty($products_list)): ?>
            <span class="text-muted" style="font-size:13px;font-weight:600">
                <?= count($products_list) ?> <?= __('store.products') ?? 'products' ?>
            </span>
            <?php endif; ?>
        </div>

        <?php if (isset($products_list) && !empty($products_list)): ?>
        <div class="row g-3 g-lg-4">
            <?php
            foreach ($products_list as $p):
                $img = !empty($p['product_featured_image'])
                    ? ((strpos($p['product_featured_image'], 'http') === 0) ? $p['product_featured_image'] : base_url('assets/images/product/upload/thumb/' . $p['product_featured_image']))
                    : base_url('assets/store/default/img/pr-img.png');
                $url = base_url('store/product/' . $p['product_slug']);
                $qty = (int)($p['product_quantity'] ?? 0);
                $is_oos = ($qty == 0 && isset($p['product_quantity']) && $p['product_quantity'] !== '' && $p['product_quantity'] !== null);

                // Rating from batch-fetched controller data (no N+1 query)
                $avg = 0; $rev_cnt = 0;
                $_r = isset($product_ratings[$p['product_id']]) ? $product_ratings[$p['product_id']] : null;
                if ($_r && $_r['cnt'] > 0) { $avg = round($_r['avg_star'], 1); $rev_cnt = (int)$_r['cnt']; }
            ?>
            <div class="col-6 col-md-4 col-lg-3 s26-reveal">
                <div class="s26-product-card">
                    <div class="card-img-wrapper">
                        <a href="<?= $url ?>">
                            <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" loading="lazy"
                                 onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'">
                        </a>
                        <?php if ($is_oos): ?>
                        <span class="s26-badge s26-badge-oos"><?= __('store.out_of_stock') ?? 'Out of Stock' ?></span>
                        <?php endif; ?>
                        <?php if (!empty($p['product_msrp']) && (float)$p['product_msrp'] > (float)$p['product_price']): ?>
                        <span class="s26-badge s26-badge-sale"><?= __('store.sale') ?? 'Sale' ?></span>
                        <?php endif; ?>
                        <div class="quick-actions">
                            <button class="quick-view-btn" data-product-id="<?= $p['product_id'] ?>">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="s26-stars">
                            <?php if ($avg > 0): for ($s = 1; $s <= 5; $s++): ?>
                                <i class="<?= $s <= round($avg) ? 'fas' : 'far' ?> fa-star"></i>
                            <?php endfor; ?>
                            <small class="text-muted ms-1">(<?= $rev_cnt ?>)</small>
                            <?php endif; ?>
                        </div>
                        <a href="<?= $url ?>" class="product-title"><?= htmlspecialchars($p['product_name']) ?></a>
                        <div class="product-price">
                            <?= c_format($p['product_price']) ?>
                            <?php if (!empty($p['product_msrp']) && (float)$p['product_msrp'] > (float)$p['product_price']): ?>
                            <span class="product-price-old"><?= c_format($p['product_msrp']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="s26-card-footer">
                        <a href="<?= $url ?>" class="s26-card-details-btn">
                            <i class="fas fa-arrow-right"></i>
                            <?= __('store.details') ?? 'Details' ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>

        <div class="s26-empty-state">
            <div class="s26-empty-state__icon"><i class="fas fa-box-open"></i></div>
            <h2 class="s26-empty-state__title"><?= __('store.no_products_found') ?? 'No products found' ?></h2>
            <p class="s26-empty-state__text"><?= __('store.no_products_desc') ?? 'Check back later for new products.' ?></p>
            <a href="<?= $base_url ?>" class="s26-btn-primary">
                <i class="fas fa-home"></i> <?= __('store.go_home') ?? 'Go Home' ?>
            </a>
        </div>

        <?php endif; ?>
    </div>
</section>
