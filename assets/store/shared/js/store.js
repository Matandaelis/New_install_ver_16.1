/**
 * V14 Store Module - Modern 2026 Enhancements
 * Instant Search, Quick View, Social Proof, Recently Viewed,
 * Sticky Cart, Recommendations, Abandoned Cart, Lazy Loading
 */
(function() {
    'use strict';

    var storeBaseUrl = (window.storeConfig && window.storeConfig.base_url) || '';
    var storeUrl = (window.storeConfig && window.storeConfig.store_url) || '';
    var currency = (window.storeConfig && window.storeConfig.currency) || '$';
    var socialProofEnabled = (window.storeConfig && window.storeConfig.social_proof) || false;

    /* ============================================================
       INSTANT SEARCH
       ============================================================ */
    function initInstantSearch() {
        var searchInput = document.querySelector('.store-search-input, input[name="search"], #store-search');
        if (!searchInput) return;

        var wrapper = searchInput.closest('.instant-search-wrapper') || searchInput.parentElement;
        wrapper.style.position = 'relative';

        var resultsDiv = document.createElement('div');
        resultsDiv.className = 'instant-search-results';
        wrapper.appendChild(resultsDiv);

        var debounceTimer;
        searchInput.addEventListener('input', function() {
            var q = this.value.trim();
            clearTimeout(debounceTimer);
            if (q.length < 2) { resultsDiv.classList.remove('show'); return; }

            debounceTimer = setTimeout(function() {
                fetch(storeUrl + '/instant_search?q=' + encodeURIComponent(q))
                    .then(function(r) { return r.json(); })
                    .then(function(products) {
                        if (products.length === 0) {
                            resultsDiv.innerHTML = '<div style="padding:20px;text-align:center;color:#999;">No results found</div>';
                        } else {
                            var html = '';
                            products.forEach(function(p) {
                                html += '<a href="' + p.url + '" class="search-result-item">' +
                                    '<img src="' + p.image + '" alt="">' +
                                    '<div class="product-info">' +
                                    '<div class="name">' + escapeHtml(p.name) + '</div>' +
                                    (p.in_stock ? '<small class="text-success">In Stock</small>' : '<small class="text-danger">Out of Stock</small>') +
                                    '</div>' +
                                    '<span class="price">' + currency + p.price + '</span>' +
                                    '</a>';
                            });
                            resultsDiv.innerHTML = html;
                        }
                        resultsDiv.classList.add('show');
                    })
                    .catch(function() { resultsDiv.classList.remove('show'); });
            }, 300);
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target)) resultsDiv.classList.remove('show');
        });
    }

    /* ============================================================
       QUICK VIEW MODAL
       ============================================================ */
    function initQuickView() {
        // Add Quick View Modal to body if not exists
        if (!document.getElementById('quickViewModal')) {
            var modal = document.createElement('div');
            modal.innerHTML = '<div class="modal fade" id="quickViewModal" tabindex="-1">' +
                '<div class="modal-dialog modal-lg modal-dialog-centered">' +
                '<div class="modal-content"><div class="modal-body" id="quickViewContent">' +
                '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>' +
                '</div></div></div></div>';
            document.body.appendChild(modal.firstChild);
        }

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.quick-view-btn');
            if (!btn) return;
            e.preventDefault();
            var productId = btn.dataset.productId;
            if (!productId) return;

            var modalEl = document.getElementById('quickViewModal');
            var content = document.getElementById('quickViewContent');
            content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';

            var modal = new bootstrap.Modal(modalEl);
            modal.show();

            fetch(storeUrl + '/quick_view/' + productId)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.success) { content.innerHTML = '<p class="text-danger">Product not found.</p>'; return; }
                    var p = data.product;
                    var img = p.featured_image || (storeBaseUrl + 'assets/store/default/img/pr-img.png');
                    var html = '<div class="row g-4">' +
                        '<div class="col-md-5"><img src="' + img + '" class="img-fluid rounded" alt=""></div>' +
                        '<div class="col-md-7">' +
                        '<h4 class="fw-bold">' + escapeHtml(p.name) + '</h4>' +
                        '<p class="fs-4 fw-bold text-primary mb-3">' + currency + parseFloat(p.price).toFixed(2) + '</p>' +
                        '<p>' + (p.description || '') + '</p>';

                    if (data.variations && Object.keys(data.variations).length > 0) {
                        html += '<div class="mb-3"><label class="form-label fw-medium">Options:</label><select class="form-select" id="qv-variation">';
                        for (var vKey in data.variations) {
                            if (data.variations.hasOwnProperty(vKey)) {
                                html += '<option value="' + vKey + '">' + escapeHtml(String(data.variations[vKey])) + '</option>';
                            }
                        }
                        html += '</select></div>';
                    }

                    html += '<div class="d-flex gap-2 mt-3">' +
                        '<a href="' + (p.url || storeUrl + '/product/' + p.slug) + '" class="btn btn-outline-primary">' +
                        '<i class="fas fa-eye me-1"></i> View Full Details</a>' +
                        '</div></div></div>';
                    content.innerHTML = html;
                })
                .catch(function() { content.innerHTML = '<p class="text-danger">Failed to load product.</p>'; });
        });
    }

    /* ============================================================
       SOCIAL PROOF NOTIFICATIONS
       ============================================================ */
    function initSocialProof() {
        if (!socialProofEnabled) return;

        var names = ['Sarah', 'John', 'Emily', 'Michael', 'Anna', 'David', 'Lisa', 'James', 'Maria', 'Robert'];
        var cities = ['New York', 'London', 'Tokyo', 'Paris', 'Berlin', 'Sydney', 'Dubai', 'Toronto', 'Mumbai', 'Singapore'];
        var productCards = document.querySelectorAll('.product-card, .product-item');
        var products = [];

        productCards.forEach(function(card) {
            var name = card.querySelector('.product-name, .card-title, h5, h6');
            var img = card.querySelector('img');
            if (name) {
                products.push({
                    name: name.textContent.trim().substring(0, 30),
                    image: img ? img.src : ''
                });
            }
        });

        if (products.length === 0) return;

        function showNotification() {
            var p = products[Math.floor(Math.random() * products.length)];
            var buyer = names[Math.floor(Math.random() * names.length)];
            var city = cities[Math.floor(Math.random() * cities.length)];
            var time = Math.floor(Math.random() * 30) + 1;

            var el = document.createElement('div');
            el.className = 'social-proof-notification';
            el.innerHTML = (p.image ? '<img src="' + p.image + '" alt="">' : '') +
                '<div class="info">' +
                '<div class="name">' + buyer + ' from ' + city + '</div>' +
                '<div class="detail">purchased <strong>' + escapeHtml(p.name) + '</strong></div>' +
                '<div class="detail" style="font-size:11px;color:#999">' + time + ' minutes ago</div>' +
                '</div>' +
                '<button class="close-btn">&times;</button>';

            document.body.appendChild(el);

            el.querySelector('.close-btn').addEventListener('click', function() {
                el.classList.add('fade-out');
                setTimeout(function() { el.remove(); }, 300);
            });

            setTimeout(function() {
                el.classList.add('fade-out');
                setTimeout(function() { el.remove(); }, 300);
            }, 5000);
        }

        // Show first notification after 10 seconds, then every 30-60 seconds
        setTimeout(showNotification, 10000);
        setInterval(showNotification, (Math.random() * 30 + 30) * 1000);
    }

    /* ============================================================
       RECENTLY VIEWED PRODUCTS
       ============================================================ */
    function initRecentlyViewed() {
        var STORAGE_KEY = 'v14_recently_viewed_v3';
        var MAX_ITEMS = 10;

        // Track current product page
        var productEl = document.querySelector('.single-product, [data-product-id]');
        if (productEl) {
            var productId = productEl.dataset.productId;
            var productName = (document.querySelector('.product-title, .single-product h1, .single-product h2') || {}).textContent;
            var productImage = productEl.dataset.productImage || (document.querySelector('.product-main-image img, .single-product img') || {}).src || '';
            var priceEl = document.querySelector('[data-price]');
            var productPrice = priceEl ? priceEl.dataset.price : ((document.querySelector('.product-price, .single-product .price') || {}).textContent || '');
            var productUrl = window.location.href;

            if (productId || productName) {
                var items = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
                items = items.filter(function(item) { return item.id !== productId && item.url !== productUrl; });
                items.unshift({ id: productId, name: productName, image: productImage, price: productPrice, url: productUrl });
                if (items.length > MAX_ITEMS) items = items.slice(0, MAX_ITEMS);
                localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
            }
        }

        // Render recently viewed section
        var container = document.getElementById('recently-viewed-container');
        if (!container) return;

        var items = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        // Filter out current page
        items = items.filter(function(item) { return item.url !== window.location.href; });

        if (items.length === 0) {
            container.style.display = 'none';
            return;
        }

        var html = '<div class="recently-viewed-scroll">';
        items.forEach(function(item) {
            html += '<a href="' + item.url + '" class="recently-viewed-item">' +
                '<img src="' + (item.image || storeBaseUrl + 'assets/store/default/img/no-image.png') + '" alt="" loading="lazy" onerror="this.onerror=null;this.src=\'' + storeBaseUrl + 'assets/store/default/img/no-image.png\'">' +
                '<div class="name">' + escapeHtml(item.name || '') + '</div>' +
                '<div class="price">' + (isNaN(parseFloat(item.price)) ? (item.price || '') : currency + parseFloat(item.price).toFixed(2)) + '</div>' +
                '</a>';
        });
        html += '</div>';
        container.innerHTML = html;
    }

    /* ============================================================
       STICKY ADD TO CART BAR
       ============================================================ */
    function initStickyCart() {
        var addToCartBtn = document.querySelector('.add-to-cart-btn, .btn-add-to-cart, [name="add_to_cart"]');
        var stickyBar = document.querySelector('.sticky-add-to-cart');
        if (!addToCartBtn || !stickyBar) return;

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    stickyBar.classList.remove('visible');
                } else {
                    stickyBar.classList.add('visible');
                }
            });
        }, { threshold: 0 });

        observer.observe(addToCartBtn);
    }

    /* ============================================================
       PRODUCT RECOMMENDATIONS
       ============================================================ */
    function initRecommendations() {
        var recContainer = document.getElementById('recommendations-container');
        if (!recContainer) return;

        var productId = recContainer.dataset.productId;
        if (!productId) return;

        fetch(storeUrl + '/recommendations/' + productId)
            .then(function(r) { return r.json(); })
            .then(function(products) {
                if (!products.length) { recContainer.style.display = 'none'; return; }

                var noImgFallback = storeBaseUrl + 'assets/store/default/img/no-image.png';
                var html = '<div class="recommendations-scroll">';
                products.forEach(function(p) {
                    var imgSrc = (p.image_url && p.image_url !== 'undefined') ? p.image_url : noImgFallback;
                    if (imgSrc !== noImgFallback && imgSrc.indexOf('http') !== 0 && imgSrc.indexOf('//') !== 0) {
                        imgSrc = (storeBaseUrl.replace(/\/$/, '') + (imgSrc.indexOf('/') === 0 ? '' : '/') + imgSrc);
                    }
                    html += '<a href="' + p.url + '" class="rec-card">' +
                        '<img src="' + imgSrc + '" alt="" loading="lazy" onerror="this.onerror=null;this.src=\'' + noImgFallback + '\'">' +
                        '<div class="rec-name">' + escapeHtml(p.product_name) + '</div>' +
                        '<div class="rec-price">' + currency + parseFloat(p.product_price).toFixed(2) + '</div>' +
                        '</a>';
                });
                html += '</div>';
                recContainer.innerHTML = html;
            })
            .catch(function() {});
    }

    /* ============================================================
       ABANDONED CART TRACKING
       ============================================================ */
    function initAbandonedCartTracking() {
        var emailInput = document.querySelector('input[name="email"], input[type="email"]');
        if (!emailInput) return;

        emailInput.addEventListener('blur', function() {
            var email = this.value.trim();
            if (email && email.indexOf('@') > 0) {
                fetch(storeUrl + '/track_cart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'email=' + encodeURIComponent(email)
                }).catch(function() {});
            }
        });
    }

    /* ============================================================
       FADE-IN ANIMATIONS (Intersection Observer)
       ============================================================ */
    function initFadeAnimations() {
        var elements = document.querySelectorAll('.store-fade-in');
        if (!elements.length || !('IntersectionObserver' in window)) {
            elements.forEach(function(el) { el.classList.add('visible'); });
            return;
        }

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        elements.forEach(function(el) { observer.observe(el); });
    }

    /* ============================================================
       LAZY LOAD IMAGES
       ============================================================ */
    function initLazyLoad() {
        document.querySelectorAll('img[loading="lazy"]').forEach(function(img) {
            if (img.complete) { img.classList.add('loaded'); return; }
            img.addEventListener('load', function() { this.classList.add('loaded'); });
        });
    }

    /* ============================================================
       UTILITY FUNCTIONS
       ============================================================ */
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /* ============================================================
       INITIALIZE ALL
       ============================================================ */
    function init() {
        initInstantSearch();
        initQuickView();
        initSocialProof();
        initRecentlyViewed();
        initStickyCart();
        initRecommendations();
        initAbandonedCartTracking();
        initFadeAnimations();
        initLazyLoad();
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
