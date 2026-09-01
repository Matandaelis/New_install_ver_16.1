<?php
/**
 * Default theme — Mustache.js product card template (client-side rendering)
 *
 * @contract  Store API v1 — fragment: product-list-template (injected into page as <script type="text/html">)
 * @see       Store_cart_payload::page_category()
 *
 * VARIABLES (rendered client-side by Mustache.js using JSON from store/api/v1/pages/category)
 *   {{products}}        array   Product list
 *   {{name}}            string  Product name
 *   {{price}}           string  Formatted price
 *   {{old_price}}       string  Formatted old price (empty if not on sale)
 *   {{image}}           string  Product image URL
 *   {{link}}            string  Product detail page URL
 *   {{show_dummy}}      bool    Show skeleton loading cards when true
 *
 * NOTE  This template is rendered purely client-side — PHP variables are NOT available inside.
 *       All data comes from the AJAX JSON response.
 */
?>
<script id="product-list-template" type="text/html">
	{{^products}}
	{{^show_dummy}}
	<?php for ($i=0; $i < 4; $i++) { ?>
	<div class="amz-product-wrapper">
	  <div class="amz-product-img">
	    <div class="amz-skeleton"></div>
	  </div>
	  <div class="amz-product-info">
	    <div class="amz-skeleton amz-skeleton--text" style="width:70%"></div>
	    <div class="amz-skeleton amz-skeleton--text" style="width:50%;margin-top:8px"></div>
	    <div class="amz-skeleton amz-skeleton--text" style="width:90%;margin-top:12px"></div>
	    <div class="amz-skeleton amz-skeleton--text" style="width:40%;margin-top:12px"></div>
	  </div>
	</div>
	<?php } ?>
	{{/show_dummy}}
	{{#show_dummy}}
	<div class="amz-product-wrapper amz-product-wrapper--empty">
		<div class="amz-product-info amz-product-info--center">
			<p class="amz-text-muted"><?= __('store.no_products_available') ?></p>
		</div>
	</div>
	{{/show_dummy}}
	{{/products}}

{{#products}}
<div class="amz-product-wrapper">
    <div class="amz-product-img">
        <a href="{{product_details_href}}">
            <img alt="{{product_name}}" src="{{product_image_src}}" class="amz-product-img__el" loading="lazy" onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>';"/>
        </a>
        {{#old_price}}
        <span class="amz-product-badge amz-product-badge--sale"><?= __('store.sale') ?: 'Sale' ?></span>
        {{/old_price}}
    </div>
    <div class="amz-product-info">
        <a href="{{product_details_href}}" class="amz-product-name">{{product_name}}</a>
        <div class="amz-product-price">
            {{#old_price}}<span class="amz-product-price__old">{{old_price}}</span>{{/old_price}}
            <span class="amz-product-price__current">{{product_price}}</span>
        </div>
        <div class="amz-product-rating">{{{product_avg_rating_stars}}}</div>
        {{#product_short_description}}
        <p class="amz-product-desc">{{product_short_description}}</p>
        {{/product_short_description}}
        <a href="{{product_details_href}}" class="amz-btn amz-btn-details"><?= __('store.details') ?></a>
    </div>
</div>
{{/products}}

</script>
