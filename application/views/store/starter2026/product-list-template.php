<?php
/**
 * Starter 2026 — Mustache Product Card Template (client-side partial)
 *
 * @contract  Store API v1 — fragment: product_card_template
 * @note      This is a Mustache.js client-side template rendered by JavaScript.
 *            It is NOT a PHP view — the PHP wrapper just outputs the raw Mustache HTML.
 *            Variable names are Mustache tokens populated by the AJAX fragment endpoint.
 *
 * AJAX ENDPOINT  POST store/load_Product
 *
 * MUSTACHE TOKENS (populated by AJAX response)
 *   {{product_details_href}}         string  URL to product detail page
 *   {{product_image_src}}            string  URL of product featured image
 *   {{product_name}}                 string  Product name
 *   {{product_price}}                string  Formatted price with currency
 *   {{product_avg_rating_stars}}     string  Pre-rendered star HTML
 *   {{product_short_description}}    string  Short description (HTML stripped)
 *   {{country_code}}                 string  Seller country ISO code
 *   {{country_flag_src}}             string  URL of country flag image
 *   {{country_name}}                 string  Seller country name
 *   {{state_name}}                   string  Seller state/region name
 */
?>

<script id="product-list-template" type="text/template">
{{^products}}
{{^show_dummy}}
<div class="col-12">
    <div class="s26-empty-state" style="padding:60px 20px">
        <div class="s26-empty-state__icon" style="width:64px;height:64px;margin:0 auto 16px;background:var(--s26-light,#f1f5f9);border-radius:50%;display:flex;align-items:center;justify-content:center">
            <i class="fas fa-box-open" style="font-size:24px;color:var(--s26-text-muted,#94a3b8)"></i>
        </div>
        <p style="font-size:15px;color:var(--s26-text-muted,#94a3b8);font-weight:600;margin:0"><?= __('store.loading') ?? 'Loading...' ?></p>
    </div>
</div>
{{/show_dummy}}
{{#show_dummy}}
<div class="col-12">
    <div class="s26-empty-state" style="padding:60px 20px">
        <div class="s26-empty-state__icon" style="width:64px;height:64px;margin:0 auto 16px;background:var(--s26-danger-light,#fef2f2);border-radius:50%;display:flex;align-items:center;justify-content:center">
            <i class="fas fa-search" style="font-size:24px;color:var(--s26-danger,#ef4444)"></i>
        </div>
        <h3 style="font-size:18px;font-weight:800;color:var(--s26-dark,#0f172a);margin-bottom:6px"><?= __('store.no_products_available') ?? 'No products found' ?></h3>
        <p style="font-size:14px;color:var(--s26-text-muted,#94a3b8);margin:0"><?= __('store.try_different_search') ?? 'Try adjusting your search or filter criteria.' ?></p>
    </div>
</div>
{{/show_dummy}}
{{/products}}

{{#products}}
<div class="col-6 col-md-4 col-lg-3 s26-reveal visible">
    <div class="s26-product-card">
        <div class="card-img-wrapper">
            <a href="{{product_details_href}}">
                <img src="{{product_image_src}}"
                     alt="{{product_name}}"
                     loading="lazy"
                     onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>'">
            </a>
            <div class="s26-card-overlay">
                <a href="{{product_details_href}}" class="s26-card-overlay__btn">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="s26-stars">
                {{{product_avg_rating_stars}}}
            </div>
            <a href="{{product_details_href}}" class="product-title">{{product_name}}</a>
            <p class="product-desc">{{product_short_description}}</p>
            <div class="product-price">{{product_price}}</div>
        </div>
        <div class="s26-card-footer">
            <a href="{{product_details_href}}" class="s26-card-details-btn">
                <i class="fas fa-arrow-right"></i>
                <?= __('store.details') ?? 'Details' ?>
            </a>
        </div>
    </div>
</div>
{{/products}}
</script>

<script type="text/javascript">
// Reinitialize reveal animations for new elements
if (typeof IntersectionObserver !== 'undefined') {
    var productObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                productObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    $(document).on('DOMNodeInserted', '.s26-reveal:not(.visible)', function(){
        productObserver.observe(this);
    });
}
</script>
