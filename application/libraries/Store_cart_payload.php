<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cart-mode storefront payloads: single source for SSR (Store.php) and JSON (Store_api).
 * Classified / sales-mode theme is out of scope.
 */
class Store_cart_payload {

	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model('Product_model');
		$this->CI->load->model('Cart');
	}

	/** @return array Manifest v1 for designers / theme developers */
	public function get_manifest_v1() {
		return [
			'version' => 1,
			'scope' => 'cart_mode_store',
			'pages' => [
				// ── Public pages ──────────────────────────────────────────────
				['id' => 'home',     'method' => 'page_home',     'description' => 'Store home page',
				 'keys' => ['settings','category_tags','category_tree','category','new_products','best_sellers','product_ratings']],

				['id' => 'category', 'method' => 'page_category', 'description' => 'Category listing + filters (SSR)',
				 'keys' => ['colors','tags','user_id','category_tree','category','settings',
				            'products','products_total','products_page','products_per_page','products_search','products_sort']],

				['id' => 'product',  'method' => 'page_product_summary', 'description' => 'Product detail page',
				 'keys' => ['product','categories','meta_title','meta_description','meta_image',
				            'add_tocart_url','add_coupon_url','all_images','all_videos',
				            'review_list','review_count','avg_rating','allowReview','is_wishlisted_class',
				            'settings','category_tree','category']],

				['id' => 'about',   'method' => 'page_about',   'description' => 'About page — CMS content',
				 'keys' => ['settings','content','category_tree','category'],
				 'content_keys' => ['about_content']],

				['id' => 'policy',  'method' => 'page_policy',  'description' => 'Privacy / refund policy page — CMS content',
				 'keys' => ['content','category_tree','category'],
				 'content_keys' => ['policy_content']],

				['id' => 'contact', 'method' => 'page_contact', 'description' => 'Contact form (POST: name,email,phone,message,terms required)',
				 'keys' => ['settings','content','category_tree','category'],
				 'content_keys' => ['contact_content'],
				 'form' => ['action' => 'store/contact', 'method' => 'POST', 'required' => ['name','email','phone','message','terms']]],

				['id' => 'custom_page', 'method' => 'page_custom_page', 'description' => 'Dynamic CMS page by slug',
				 'keys' => ['settings','data'],
				 'note' => '$data is an object with: slug, title, content properties'],

				// ── Cart / checkout ───────────────────────────────────────────
				['id' => 'cart',     'method' => 'page_cart',    'description' => 'Shopping cart',
				 'keys' => ['products','sub_total','total','settings','base_url','cart_url'],
				 'note' => 'Update: POST quantity[{cart_id}]=qty → JSON {success,html}. Remove: GET ?remove={cart_id}'],

			['id' => 'checkout', 'method' => 'page_checkout', 'description' => 'Checkout (multi-step / one-page)',
			 'keys' => ['products','totals','sub_total','total','is_logged','settings','paymentsetting',
			            'allow_shipping','allow_upload_file','allow_comment','confirm_html',
			            'countries','checkout_url','cart_update_url','is_guest_flow','order_comment_setting'],
			 'notes' => 'totals rows: {title,amount}. products rows: {product_name,product_price,quantity,total,product_featured_image(URL),cart_id}. countries: objects with ->id and ->name (only when allow_shipping=true). is_logged: user array {firstname,lastname,email,phone,twaddress,ucity,uzip,ucountry} or false.'],

				['id' => 'checkout_shipping', 'method' => 'page_checkout_shipping', 'description' => 'Shipping step fragment',
				 'keys' => ['allow_shipping','countries','shipping','flat_rate_amount','is_guest_flow','country_id']],

				['id' => 'checkout_confirm',  'method' => 'page_checkout_confirm',  'description' => 'Confirm step fragment',
				 'keys' => ['allow_comment','allow_upload_file']],

				['id' => 'checkout_thankyou', 'method' => 'page_checkout_thankyou', 'description' => 'Order confirmation / thank-you page',
				 'keys' => ['order','products','totals','client_loged','is_guest',
				            'order_history','status','paymentsetting','settings']],

				// ── Account pages (require auth) ──────────────────────────────
				['id' => 'login',    'method' => 'page_login',   'description' => 'Login & Register page (AJAX endpoints)',
				 'keys' => ['redirect_url','settings','category_tree','category'],
				 'note' => 'Login: AJAX POST store/ajax_login {username,password} → {success:true}|{errors:{}}. Register: AJAX POST store/ajax_register.'],

				['id' => 'profile',  'method' => 'page_profile', 'description' => 'Client profile / edit form',
				 'keys' => ['userDetails','country','settings','category_tree','return_to'],
				 'userDetails_keys' => ['firstname','lastname','email','username','phone','ucountry','state','ucity','uzip','avatar','twaddress']],

				['id' => 'orders',   'method' => 'page_orders',  'description' => 'Order list',
				 'keys' => ['buyproductlist','status','user','settings','return_to'],
				 'note' => '$status is map [int => label]. $buyproductlist rows have: id, created_at, status(int), total_sum'],

				['id' => 'view_order', 'method' => 'page_view_order', 'description' => 'Single order detail (auth required)',
				 'keys' => ['order_id','order','products','totals','order_history','paymentsetting','settings','status','category_tree','category'],
				 'totals_shape' => 'each: [text => string, value => float]',
				 'order_history_shape' => 'each: [created_at => string, comment => string]'],

				['id' => 'wishlist', 'method' => 'page_wishlist', 'description' => 'Wishlist (auth required)',
				 'keys' => ['products','user_id','category_tree'],
				 'products_shape' => 'each: product_id, product_name, product_slug, product_featured_image(filename), product_price'],

				['id' => 'track_order', 'method' => 'page_track_order', 'description' => 'Guest order tracking form',
				 'keys' => ['track_form_values','category_tree','category'],
				 'note' => 'On POST: controller adds $error(string) on failure, or renders checkout_thankyou view on success'],

				['id' => 'my_courses', 'method' => 'page_my_courses', 'description' => 'LMS courses list (auth required)',
				 'keys' => ['courses','user','settings','category_tree','category']],

				['id' => 'lms_play', 'method' => 'page_lms_play_meta', 'description' => 'LMS stream authorization (no file paths)',
				 'keys' => ['video_id','order_id','authorized']],
			],
			'fragments' => [
				['id' => 'product_grid', 'method' => 'POST', 'path' => 'store/load_Product', 'description' => 'Paginated grids: home, category, related'],
				['id' => 'instant_search', 'method' => 'GET', 'path' => 'store/instant_search', 'query' => ['q']],
				['id' => 'quick_view', 'method' => 'GET', 'path' => 'store/quick_view/{id}'],
				['id' => 'recommendations', 'method' => 'GET', 'path' => 'store/recommendations/{id}'],
			],
			'api_routes' => [
				'GET store/api/v1/theme/manifest',
				'GET store/api/v1/theme/examples/{resource}',
				'GET store/api/v1/pages/home',
				'GET store/api/v1/pages/category/{slug}',
				'GET store/api/v1/pages/product/{slug}',
				'POST store/api/v1/fragments/product-grid',
				'GET store/api/v1/fragments/instant_search',
				'GET store/api/v1/fragments/quick_view/{id}',
				'GET store/api/v1/fragments/recommendations/{id}',
				'GET store/api/v1/pages/cart',
				'GET store/api/v1/pages/checkout',
				'GET store/api/v1/pages/checkout_shipping',
				'GET store/api/v1/pages/checkout_confirm',
				'GET store/api/v1/pages/profile',
				'GET store/api/v1/pages/orders',
				'GET store/api/v1/pages/wishlist',
				'GET store/api/v1/pages/track_order',
				'GET store/api/v1/pages/my_courses',
				'GET store/api/v1/pages/lms_play',
			],
		];
	}

	/**
	 * Full schema used by the admin documentation page.
	 * Each field: [ name, type, always (bool), description, example ]
	 */
	public function get_full_schema() {
		return [
			'global' => [
				'description' => 'These variables are injected by Storeapp::view() into EVERY page automatically.',
				'fields' => [
					['$store_setting',    'array',   true,  'All store settings (name, logo, theme, currency, etc.)',                      '["name"=>"My Shop","currency_code"=>"USD",...]'],
					['$SiteSetting',      'array',   true,  'Global site settings (affiliate tracking, maintenance mode, etc.)',           '["affiliate_tracking_place"=>"1",...]'],
					['$client',           'array',   false, 'Logged-in customer data. Empty if guest.',                                   '["id"=>"12","firstname"=>"Jane",...]'],
					['$base_url',         'string',  true,  'Full base URL of the site.',                                                 '"https://example.com/"'],
					['$store_currency',   'string',  true,  'Active currency symbol (from currency.symbol_left or store currency_sign setting).',  '"$"'],
					['$LanguageHtml',     'string',  true,  'Rendered language switcher HTML.',                                           '"<select>...</select>"'],
					['$CurrencyHtml',     'string',  true,  'Rendered currency switcher HTML.',                                           '"<select>...</select>"'],
				['$home_link',        'string',  true,  'URL of the store homepage. Use for logo links and "Home" breadcrumb.',       '"https://example.com/store/"'],
				['$category_tree',    'array',   true,  'Full nested category tree for navigation menus.',                            '[["id"=>1,"name"=>"Clothing","children"=>[...]],...]'],
				['$googlerecaptcha',  'array',   true,  'reCAPTCHA settings (site key, enable flag).',                                '["enable_login"=>"1","site_key"=>"..."]'],
				['$add_tocart_url',   'string',  true,  'POST URL for the add-to-cart action.',                                       '"https://example.com/store/add_to_cart"'],
				],
			],
			'pages' => [
				[
					'id' => 'home',
					'view' => 'home.php',
					'description' => 'Main store homepage: featured categories, new arrivals, best sellers.',
					'auth' => false,
					'api_endpoint' => 'GET store/api/v1/pages/home',
					'fields' => [
						['$settings',        'array',  true,  'Store settings (same as $store_setting global).',                           '["name"=>"My Shop",...]'],
						['$category_tags',   'array',  true,  'Top N categories marked as "featured" tags.',                              '[["id"=>3,"name"=>"Electronics","image"=>"..."],...]'],
						['$category_tree',   'array',  true,  'Full category tree.',                                                       '[["id"=>1,"name"=>"...", "children"=>[...]]]'],
						['$category',        'array',  true,  'Root categories (parent_id = 0).',                                          '[["id"=>1,"name"=>"Clothing"],...]'],
						['$new_products',    'array',  true,  'Latest 8 products (id, name, price, image, slug).',                         '[["product_id"=>5,"product_name"=>"Shirt",...]...]'],
						['$best_sellers',    'array',  true,  'Top 4 best-selling products.',                                              '[["product_id"=>2,"product_name"=>"Jeans",...]...]'],
						['$product_ratings', 'array',  true,  'Map of product_id → avg_star, cnt.',                                        '[5=>["avg_star"=>"4.5","cnt"=>"12"]]'],
					],
				],
				[
					'id' => 'category',
					'view' => 'category.php',
					'description' => 'Category listing page with filters. AJAX product grid loaded via fragment product_grid.',
					'auth' => false,
					'api_endpoint' => 'GET store/api/v1/pages/category/{slug}',
					'fields' => [
						['$settings',      'array',  true,  'Store settings.',                                                             '["name"=>"My Shop",...]'],
						['$category',      'array',  true,  'Current category row (id, name, slug, image). Empty array for "all".',       '["id"=>3,"name"=>"Electronics","slug"=>"electronics"]'],
						['$category_tree', 'array',  true,  'Full category tree for sidebar.',                                             '[...]'],
						['$colors',        'array',  true,  'All distinct variation colours across products.',                             '[["color"=>"Red"],["color"=>"Blue"]]'],
						['$tags',          'array',  true,  'All distinct product tags.',                                                  '[["tag"=>"sale"],["tag"=>"new"]]'],
						['$user_id',       'int',    true,  'Affiliate user ID (0 if none). Pass as add_to_cart ref param.',              '0 or 14'],
					],
				],
				[
					'id' => 'product',
					'view' => 'product.php',
					'description' => 'Full product detail page.',
					'auth' => false,
					'api_endpoint' => 'GET store/api/v1/pages/product/{slug}',
					'fields' => [
						['$product',           'array',  true,  'Product row with all columns + seller sub-array.',                        '["product_id"=>5,"product_name"=>"T-Shirt","product_price"=>"29.99",...]'],
						['$categories',        'array',  true,  'Categories this product belongs to.',                                    '[["id"=>2,"name"=>"Clothing"]]'],
						['$category_tree',     'array',  true,  'Full category tree for header.',                                          '[...]'],
					['$meta_title',        'string', true,  'Page title for the &lt;title&gt; tag.',                                   '"Slim Fit T-Shirt"'],
					['$meta_description',  'string', true,  'Meta description for SEO.',                                               '"High quality cotton..."'],
					['$meta_image',        'string', true,  'Absolute URL of product featured image.',                                 '"https://example.com/assets/.../shirt.jpg"'],
					['$ratings',              'array',  true,  'All approved ratings for this product.',                               '[["rating_number"=>"5","rating_name"=>"John",...]]'],
					['$avg_rating',           'float',  true,  'Average rating (0–5).',                                               '"4.3"'],
					['$review_count',         'int',    true,  'Total number of approved reviews.',                                   '12'],
					['$review_list',          'array',  true,  'Full list of reviews with user details (same set as $ratings).',      '[["rating_number"=>"5","rating_name"=>"Jane","rating_review"=>"Great!",...]]'],
					['$all_images',           'array',  true,  'Additional product gallery images (from product_media table).',       '[["product_media_upload_path"=>"gallery1.jpg",...]]'],
					['$all_videos',           'array',  true,  'Product gallery videos (from product_media table).',                  '[["product_media_upload_path"=>"https://youtube.com/...","product_media_upload_video_image"=>"thumb.jpg"]]'],
					['$allowReview',          'bool',   true,  'true if the logged-in user has purchased this product and may leave a review.',  'false'],
					['$login_usr',            'array',  false, 'Logged-in customer session array. false if guest (alias of $client global).',    '["id"=>"12","firstname"=>"Jane","products_wishlist"=>"[5,8]"]'],
					['$is_wishlisted_class',  'string', true,  '"w-listed" if this product is in the current user\'s wishlist, else "".',        '"w-listed"'],
					['$setting',              'array',  true,  'Payment settings (used to show payment icons).',                     '["stripe_enable"=>"1",...]'],
					['$social_share_modal',   'string', false, 'Rendered HTML for social share buttons modal.',                      '"<div class=\'modal\'...>"'],
					['$user_id',              'int',    true,  'Affiliate user ID.',                                                  '0'],
					['$add_tocart_url',       'string', true,  'POST endpoint for adding this product to cart.',                     '"https://example.com/store/add_to_cart"'],
					['$add_coupon_url',       'string', true,  'POST endpoint for applying a coupon code.',                          '"https://example.com/store/add_coupon"'],
					['$order_id',             'int',    false, 'Existing order ID if user already purchased this digital product. If set, show "Start Course" instead of "Add to Cart".', 'null or 7'],
					['$user',                 'array',  false, 'Affiliate/vendor user [{username, store_slug}] shown in "Promoted by" banner. null if no affiliate.', 'null or ["username"=>"JohnD","store_slug"=>"johnd-shop"]'],
					],
				],
				[
					'id' => 'cart',
					'view' => 'cart.php',
					'description' => 'Shopping cart page.',
					'auth' => false,
					'api_endpoint' => 'GET store/api/v1/pages/cart',
					'fields' => [
						['$products',   'array',  true,  'All items currently in cart.',                                                   '[["product_id"=>5,"product_name"=>"...","quantity"=>2,"price"=>"29.99"]]'],
						['$sub_total',  'string', true,  'Formatted cart subtotal.',                                                       '"59.98"'],
						['$total',      'string', true,  'Formatted cart total (before shipping).',                                        '"59.98"'],
						['$settings',   'array',  true,  'Store settings.',                                                                '["name"=>"My Shop",...]'],
						['$base_url',   'string', true,  'Base URL.',                                                                      '"https://example.com/"'],
						['$cart_url',   'string', true,  'URL of the cart page.',                                                          '"https://example.com/store/cart"'],
					],
				],
				[
					'id' => 'checkout',
					'view' => 'checkout.php',
					'description' => 'Checkout page (multi-step or one-page depending on store setting).',
					'auth' => false,
					'api_endpoint' => 'GET store/api/v1/pages/checkout',
					'fields' => [
						['$products',             'array',  true,  'Cart items.',                                                           '[...]'],
						['$totals',               'array',  true,  'Subtotal, shipping, coupon discount, grand total.',                     '["subtotal"=>"59.98","shipping"=>"5.00","total"=>"64.98"]'],
						['$is_logged',            'array',  false, 'Logged-in customer array. false if guest.',                            '["id"=>"12","firstname"=>"Jane"]'],
						['$settings',             'array',  true,  'Store settings.',                                                       '[...]'],
						['$paymentsetting',       'array',  true,  'Raw payment settings (stripe_enable, paypal_enable, etc.).',            '["stripe_enable"=>"1","paypal_enable"=>"0"]'],
					['$payment_gateways',     'array',  true,  'Processed gateway list for payment_methods.php partial (via AJAX).', '[["name"=>"stripe","title"=>"Credit Card","icon"=>"assets/..."]]'],
						['$allow_shipping',       'bool',   true,  'Whether shipping is required for this order.',                         'true'],
						['$allow_upload_file',    'bool',   true,  'Whether proof-of-payment upload is enabled.',                          'false'],
						['$shipping_error_message','string',false, 'Error to show if shipping is not available to user country.',          '"Sorry, we don\'t ship to your country"'],
						['$show_blue_message',    'bool',   true,  'Show a notice about shipping restrictions.',                           'false'],
						['$is_guest_flow',        'bool',   true,  'Whether this is a guest checkout session.',                            'false'],
					['$confirm_html',         'string', true,  'Pre-rendered payment confirmation step HTML (if resuming payment).',  '""'],
					['$order_comment_setting','array',  true,  'Order comment/note configuration.',                                     '["enable"=>"1","title"=>[...]]'],
					['$checkout_url',         'string', true,  'Form action URL.',                                                      '"https://example.com/store/checkout"'],
					['$cart_update_url',      'string', true,  'URL for cart quantity-update AJAX calls.',                              '"https://example.com/store/cart"'],
					['$sub_total',            'string', true,  'Formatted cart subtotal (before shipping/tax).',                        '"59.98"'],
					['$total',                'string', true,  'Formatted cart total (alias of sub_total at checkout stage).',          '"59.98"'],
					],
				],
				[
					'id' => 'checkout_shipping',
					'view' => 'checkout_shipping.php',
					'description' => 'Shipping address step (rendered as AJAX partial inside checkout).',
					'auth' => false,
					'api_endpoint' => 'GET store/api/v1/pages/checkout_shipping',
					'fields' => [
						['$allow_shipping',  'bool',   true,  'Whether shipping applies.',                                                 'true'],
						['$countries',       'array',  true,  'Allowed shipping countries.',                                               '[{"id":"1","name":"United States"},...]'],
						['$shipping',        'object', false, 'Saved shipping address for logged-in user.',                               '{"country_id":"1","city":"NY",...}'],
						['$flat_rate_amount','float',  true,  'Flat shipping rate for selected country (0 if none).',                      '5.00'],
						['$is_guest_flow',   'bool',   true,  'Guest checkout flag.',                                                      'false'],
					],
				],
				[
					'id' => 'checkout_confirm',
					'view' => 'checkout_confirm.php',
					'description' => 'Confirmation step partial — payment gateway selector and order note.',
					'auth' => false,
					'api_endpoint' => 'GET store/api/v1/pages/checkout_confirm',
					'fields' => [
						['$allow_comment',     'bool', true, 'Whether the customer can leave an order note.',  'true'],
						['$allow_upload_file', 'bool', true, 'Whether file upload (payment proof) is enabled.','false'],
					],
				],
				[
					'id' => 'profile',
					'view' => 'profile.php',
					'description' => 'Customer profile / account settings page.',
					'auth' => true,
					'api_endpoint' => 'GET store/api/v1/pages/profile',
					'fields' => [
						['$userDetails',   'array',  true, 'Customer fields: firstname, lastname, email, phone, address, avatar, etc.',   '["firstname"=>"Jane","email"=>"jane@example.com",...]'],
						['$country',       'array',  true, 'All countries for the country dropdown.',                                     '[["id"=>"1","name"=>"United States"],...]'],
						['$settings',      'array',  true, 'Store settings.',                                                             '[...]'],
						['$category_tree', 'array',  true, 'Category tree for header nav.',                                              '[...]'],
						['$return_to',     'string', true, 'URL to redirect back to after save (empty string = default).',               '""'],
					],
				],
				[
					'id' => 'orders',
					'view' => 'order_list.php',
					'description' => 'Customer order history list.',
					'auth' => true,
					'api_endpoint' => 'GET store/api/v1/pages/orders',
					'fields' => [
						['$buyproductlist', 'array',  true, 'Array of orders for the logged-in user.',                                    '[["id"=>"55","status"=>"1","total"=>"64.98",...]]'],
						['$status',         'array',  true, 'Order status labels map.',                                                   '[1=>"Pending",2=>"Completed",...]'],
						['$user',           'array',  true, 'Current logged-in user array.',                                             '["id"=>"12","firstname"=>"Jane"]'],
						['$settings',       'array',  true, 'Store settings.',                                                            '[...]'],
						['$category_tree',  'array',  true, 'Category tree for header nav.',                                             '[...]'],
						['$return_to',      'string', true, 'Back-link URL.',                                                             '""'],
					],
				],
				[
					'id' => 'wishlist',
					'view' => 'wishlist.php',
					'description' => 'Customer wishlist page.',
					'auth' => true,
					'api_endpoint' => 'GET store/api/v1/pages/wishlist',
					'fields' => [
						['$products',      'array', true, 'Wishlist products with full product data.',                                   '[["product_id"=>5,"product_name"=>"Shirt",...]]'],
						['$user_id',       'int',   true, 'Affiliate user ID (for add-to-cart href).',                                   '0'],
						['$category_tree', 'array', true, 'Category tree.',                                                              '[...]'],
					],
				],
				[
					'id' => 'track_order',
					'view' => 'track_order.php',
					'description' => 'Guest order tracking form.',
					'auth' => false,
					'api_endpoint' => 'GET store/api/v1/pages/track_order',
					'fields' => [
						['$track_form_values', 'array',  true, 'Repopulate form after error: ["order_number"=>"","email"=>""].',          '["order_number"=>"","email"=>""]'],
						['$category_tree',     'array',  true, 'Category tree.',                                                          '[...]'],
					],
				],
				[
					'id' => 'my_courses',
					'view' => 'my_courses.php  (LMS)',
					'description' => 'LMS — list of purchased courses for the logged-in user.',
					'auth' => true,
					'api_endpoint' => 'GET store/api/v1/pages/my_courses',
					'fields' => [
						['$courses',       'array', true, 'Purchased LMS courses with video/lesson data.',                               '[["order_id"=>"33","product_name"=>"PHP Course",...]]'],
						['$user',          'array', true, 'Logged-in user.',                                                             '["id"=>"12","firstname"=>"Jane"]'],
						['$settings',      'array', true, 'Store settings.',                                                             '[...]'],
						['$category_tree', 'array', true, 'Category tree.',                                                              '[...]'],
					],
				],
			[
				'id' => 'lms_play',
				'view' => 'lms/template-1.php  (LMS)',
				'description' => 'LMS video player. Loaded when Storeapp::view() is called with $skip_layout = "lms".',
				'auth' => true,
				'api_endpoint' => 'GET store/api/v1/pages/lms_play?track=&orderId=',
				'fields' => [
					['$video_id',  'string', true, 'Internal video identifier (name/mask).',                                         '"intro-module-1"'],
					['$order_id',  'int',    true, 'Order ID the course belongs to.',                                                '33'],
					['$authorized','bool',   true, 'Whether the user is authorized to watch (API only).',                            'true'],
				],
			],
			[
				'id' => 'login',
				'view' => 'login.php',
				'description' => 'Store login form. Skipped automatically if user is already logged in.',
				'auth' => false,
				'api_endpoint' => null,
				'fields' => [
					['$redirect_url', 'string', true,  'URL to redirect to after successful login.',                                  '"https://example.com/store/profile"'],
					['$settings',     'array',  true,  'Store settings.',                                                             '["name"=>"My Shop",...]'],
					['$category_tree','array',  true,  'Category tree for nav.',                                                      '[...]'],
					['$category',     'array',  true,  'Root categories.',                                                            '[...]'],
				],
			],
			[
				'id' => 'about',
				'view' => 'about.php',
				'description' => 'About page. Content comes from the localized store CMS settings.',
				'auth' => false,
				'api_endpoint' => null,
				'fields' => [
					['$settings',     'array',  true,  'Store settings.',                                                             '["name"=>"My Shop",...]'],
					['$content',      'array',  true,  'Localized CMS fields from store settings (about_title, about_body, etc.).',   '["about_title"=>"About us","about_body"=>"<p>...</p>"]'],
					['$category_tree','array',  true,  'Category tree for nav.',                                                      '[...]'],
					['$category',     'array',  true,  'Root categories (parent_id = 0).',                                            '[...]'],
				],
			],
			[
				'id' => 'contact',
				'view' => 'contact.php',
				'description' => 'Contact form page. POST to same URL submits the form and sends an email.',
				'auth' => false,
				'api_endpoint' => null,
				'fields' => [
					['$settings',     'array',  true,  'Store settings.',                                                             '["name"=>"My Shop",...]'],
					['$content',      'array',  true,  'Localized CMS fields (contact_title, contact_email, etc.).',                  '["contact_title"=>"Contact us","contact_email"=>"shop@example.com"]'],
					['$category_tree','array',  true,  'Category tree for nav.',                                                      '[...]'],
					['$category',     'array',  true,  'Root categories (parent_id = 0).',                                            '[...]'],
				],
			],
			[
				'id' => 'policy',
				'view' => 'policy.php',
				'description' => 'Privacy / refund policy page. Content from localized store CMS settings.',
				'auth' => false,
				'api_endpoint' => null,
				'fields' => [
					['$content',      'array',  true,  'Localized CMS fields (policy_title, policy_body, etc.).',                    '["policy_title"=>"Privacy Policy","policy_body"=>"<p>...</p>"]'],
					['$category_tree','array',  true,  'Category tree for nav.',                                                      '[...]'],
					['$category',     'array',  true,  'Root categories (parent_id = 0).',                                            '[...]'],
				],
			],
			[
				'id' => 'custom_page',
				'view' => 'custom_page.php',
				'description' => 'Dynamic CMS page. Resolved by slug from Store Settings → Custom Pages.',
				'auth' => false,
				'api_endpoint' => null,
				'fields' => [
					['$settings',     'array',  true,  'Store settings.',                                                             '["name"=>"My Shop",...]'],
					['$data',         'object', true,  'The custom page object: slug, title, content (HTML), meta_id.',              '{"slug":"faq","title":"FAQ","content":"<p>...</p>"}'],
				],
			],
			[
				'id' => 'checkout_thankyou',
				'view' => 'checkout_thankyou.php  (standalone layout — does not extend theme layout)',
				'description' => 'Order confirmation / thank-you page. Rendered after payment is confirmed. Has its own full HTML document.',
				'auth' => false,
				'api_endpoint' => null,
			'fields' => [
				['$order',           'array',  true,  'Full order row (id, total, status, shipping fields, txn_id, etc.).',       '["id"=>"88","total"=>"64.98","status"=>"1","payment_method"=>"stripe",...]'],
				['$products',        'array',  true,  'Ordered products (product_name, quantity, price, image, downloadable_files, etc.).',  '[["product_name"=>"Shirt","quantity"=>2,"price"=>"29.99","product_type"=>"physical"]]'],
				['$totals',          'array',  true,  'Order totals keyed by slug (subtotal, shipping, coupon, grand_total).',    '["subtotal"=>["text"=>"Subtotal","value"=>"59.98"],"grand_total"=>[...]]'],
				['$client_loged',    'bool',   true,  'true if a registered customer placed the order.',                         'true'],
				['$is_guest',        'array',  false, 'Guest user session array if order placed without account.',               '["email"=>"guest@ex.com"]'],
				['$orderProof',      'object', false, 'Payment proof file object with downloadLink property. null if none.',     '(object)["downloadLink"=>"https://..."]'],
				['$payment_history', 'array',  true,  'Payment gateway history entries (payment_mode, paypal_status, etc.).',    '[["payment_mode"=>"stripe","paypal_status"=>"Completed"]]'],
				['$status',          'array',  true,  'Map of order_status_id → label for timeline display.',                    '[1=>"Processing", 2=>"Shipped", 3=>"Delivered"]'],
				['$order_history',   'array',  true,  'Ordered status-change entries (order_status_id, comment, date_added).',   '[["order_status_id"=>"2","comment"=>"Shipped","date_added"=>"2024-01-11"]]'],
				['$paymentsetting',  'array',  true,  'Payment settings including bank transfer instructions.',                  '["bank_transfer_instruction"=>"Transfer to account..."]'],
				['$is_guest_track',  'bool',   false, 'true when this thank-you is shown to a guest who just tracked their order (no login).', 'false'],
				['$funnel_upsells',  'array',  false, 'Optional: post-purchase upsell products injected by funnel plugins.',     '[["product_name"=>"Case","product_price"=>"9.99","product_image"=>"..."]]'],
			],
		],
		[
			'id' => 'view_order',
				'view' => 'view_order.php',
				'description' => 'Single order detail for the logged-in customer. Redirects to login if unauthenticated.',
				'auth' => true,
				'api_endpoint' => null,
				'fields' => [
				['$order',           'array', true,  'Full order row. Use $order[\'id\'] for the order number.',              '["id"=>"88","status"=>"1","total"=>"64.98",...]'],
				['$products',        'array', true,  'Products in this order (includes order_id, downloadable_files keys).',    '[["product_name"=>"Shirt","quantity"=>2,"order_id"=>88]]'],
				['$totals',          'array', true,  'Order totals keyed by slug (subtotal, shipping, grand_total).',           '["grand_total"=>["text"=>"Total","value"=>"64.98"]]'],
				['$order_history',   'array', true,  'Status change timeline entries.',                                         '[["order_status_id"=>"2","comment"=>"Shipped","date_added"=>"..."]'],
				['$paymentsetting',  'array', true,  'Payment gateway settings (for bank transfer instructions, re-pay).',      '[...]'],
					['$orderProof',      'array', false, 'Payment proof upload (if applicable).',                                    '["file"=>"proof.jpg"]'],
					['$status',          'array', true,  'All available order status definitions.',                                  '[["id"=>"1","name"=>"Processing"]]'],
				['$is_guest',        'array', false, 'Guest session data if order was placed without an account. Empty/false for logged-in orders.', '[] or ["email"=>"g@ex.com"]'],
				['$orderProof',      'object', false, 'Payment proof file (->downloadLink). null if none.',                     'null'],
				['$payment_history', 'array', true,  'Payment history rows for this order.',                                    '[["payment_mode"=>"stripe","paypal_status"=>"Completed"]]'],
				['$status',          'array', true,  'Map of order_status_id → label.',                                        '[1=>"Processing",2=>"Shipped"]'],
				['$settings',        'array', true,  'Store settings.',                                                         '["name"=>"My Shop",...]'],
				],
			],
		],
			'fragments' => [
				[
					'id'          => 'product_grid',
					'method'      => 'POST',
					'path'        => 'store/load_Product',
					'api_path'    => 'POST store/api/v1/fragments/product-grid',
					'description' => 'Paginated product grid. Used on home (trending/new), category, and product-details (related).',
					'auth'        => false,
					'params'      => [
						['request_page',         'string', true,  '"home" | "category" | "product-details"'],
						['next_page',             'int',    false, 'Page number (default: 1)'],
						['limit',                 'int',    false, 'Items per page (default: 12)'],
						['request_page_section',  'string', false, '"trending" | "new" (home only)'],
						['category_slug',         'string', false, 'Slug of category to filter by'],
						['created_by',            'int',    false, 'Filter by vendor user ID'],
						['search',                'string', false, 'Full-text search term'],
						['colors',                'array',  false, 'Array of colour strings to filter variations'],
						['tags',                  'array',  false, 'Array of tag strings'],
						['order_by',              'string', false, '"low-to-high" | "high-to-low" | "latest"'],
						['min_price',             'float',  false, 'Minimum price filter'],
						['max_price',             'float',  false, 'Maximum price filter'],
						['product_avg_rating',    'int',    false, 'Exact rating to filter (1–5)'],
						['category_id',           'int',    false, 'Category ID (product-details page for related)'],
						['product_id',            'int',    false, 'Exclude this product from related list'],
					],
					'response' => [
						['trendings.products',  'array',  'Trending product rows (home)'],
						['new.products',        'array',  'New-arrival product rows (home)'],
						['related.products',    'array',  'Related product rows (product-details)'],
						['category.products',   'array',  'Filtered product rows (category page)'],
						['*.total_count',       'int',    'Total matching products (for pagination)'],
						['*.number_of_page',    'int',    'Total pages'],
						['*.next_page',         'int',    'Next page number'],
						['*.is_last_page',      'bool',   'Whether current page is the last one'],
					],
					'product_row_fields' => [
						['product_id',                 'int',    'Unique product ID'],
						['product_name',               'string', 'Truncated to 35 chars'],
						['product_short_description',  'string', 'Truncated to 70 chars'],
						['product_price',              'string', 'Formatted price (c_format)'],
						['product_details_href',       'string', 'Full URL to the product page (with affiliate ref)'],
						['product_image_src',          'string', 'Absolute URL of the product image'],
						['product_avg_rating',         'int',    'Average rating 0–5'],
						['product_avg_rating_stars',   'string', 'HTML star images'],
						['country_flag_src',           'string', 'Flag image URL (if seller country available)'],
					],
				],
				[
					'id'          => 'instant_search',
					'method'      => 'GET',
					'path'        => 'store/instant_search',
					'api_path'    => 'GET store/api/v1/fragments/instant_search',
					'description' => 'Live search-as-you-type results (min 2 chars). Returns array of up to 6 products.',
					'auth'        => false,
					'params'      => [
						['q', 'string', true, 'Search query (min 2 characters)'],
					],
					'response' => [
						['[].id',       'int',    'Product ID'],
						['[].name',     'string', 'Product name'],
						['[].price',    'string', 'Formatted price'],
						['[].image',    'string', 'Absolute image URL'],
						['[].url',      'string', 'Product page URL'],
						['[].in_stock', 'bool',   'True if quantity > 0 or unlimited (-1)'],
					],
				],
				[
					'id'          => 'quick_view',
					'method'      => 'GET',
					'path'        => 'store/quick_view/{id}',
					'api_path'    => 'GET store/api/v1/fragments/quick_view/{id}',
					'description' => 'Quick-view modal data for a product (no page reload needed).',
					'auth'        => false,
					'params'      => [
						['id', 'int', true, 'Product ID'],
					],
					'response' => [
						['success',                  'bool',   'true on found, false on missing'],
						['product.id',               'int',    'Product ID'],
						['product.name',             'string', 'Product name'],
						['product.price',            'string', 'Raw price'],
						['product.description',      'string', 'Short description'],
						['product.featured_image',   'string', 'Absolute URL of featured image'],
						['product.slug',             'string', 'Product slug for URL'],
						['product.quantity',         'int',    'Stock quantity (-1 = unlimited)'],
						['product.url',              'string', 'Full product page URL'],
						['images',                   'array',  'All media upload image URLs'],
						['variations',               'array',  'Variation options (colour, size, etc.)'],
					],
				],
				[
					'id'          => 'recommendations',
					'method'      => 'GET',
					'path'        => 'store/recommendations/{id}',
					'api_path'    => 'GET store/api/v1/fragments/recommendations/{id}',
					'description' => 'Co-purchased or same-category product recommendations.',
					'auth'        => false,
					'params'      => [
						['id', 'int', true, 'Product ID to base recommendations on'],
					],
					'response' => [
						['[].product_id',           'int',    'Product ID'],
						['[].product_name',         'string', 'Product name'],
						['[].product_price',        'string', 'Raw price'],
						['[].image_url',            'string', 'Absolute image URL'],
						['[].url',                  'string', 'Product page URL'],
					],
				],
			],
		];
	}

	public function get_example_payload($resource) {
		$samples = [
			'home' => ['settings' => ['name' => 'Demo Store'], 'new_products' => [], 'best_sellers' => []],
			'product_grid' => ['trendings' => ['products' => []], 'new' => ['products' => []]],
			'instant_search' => [['id' => 1, 'name' => 'Sample', 'price' => '9.99', 'url' => base_url('store'), 'in_stock' => true]],
		];
		return $samples[$resource] ?? null;
	}

	public function page_home() {
		$PM = $this->CI->Product_model;
		$data = [];
		$data['settings'] = $PM->getSettings('store');
		$lim = (isset($data['settings']['top_tags_limit']) && !empty($data['settings']['top_tags_limit'])) ? $data['settings']['top_tags_limit'] : 10;
		$data['category_tags'] = $PM->getCategoriesHavingCartProducts($lim);
		$data['category_tree'] = $PM->getCategoryTree();
		$data['category'] = $this->CI->db->query("SELECT * FROM `categories` WHERE `parent_id` = 0")->result_array();
		$data['new_products'] = $this->CI->db->query("
			SELECT p.* FROM product p
			WHERE p.is_campaign_product = 0 AND p.product_status = 1 AND p.on_store = 1
			GROUP BY p.product_id ORDER BY p.product_id DESC LIMIT 8
		")->result_array();
		$data['best_sellers'] = $this->CI->db->query("
			SELECT p.* FROM product p
			WHERE p.is_campaign_product = 0 AND p.product_status = 1 AND p.on_store = 1 AND p.product_sales_count > 0
			GROUP BY p.product_id ORDER BY p.product_sales_count DESC LIMIT 4
		")->result_array();
		$_home_ids = array_unique(array_merge(
			array_column($data['new_products'], 'product_id'),
			array_column($data['best_sellers'], 'product_id')
		));
		if (!empty($_home_ids)) {
			$_ids_str = implode(',', array_map('intval', $_home_ids));
			$_ratings = $this->CI->db->query("
				SELECT products_id, AVG(rating_number) as avg_star, COUNT(*) as cnt
				FROM rating WHERE products_id IN ($_ids_str) AND rating_status = 1
				GROUP BY products_id
			")->result_array();
			$data['product_ratings'] = array_column($_ratings, null, 'products_id');
		} else {
			$data['product_ratings'] = [];
		}
		return $data;
	}

	public function page_category($category_slug) {
		$PM = $this->CI->Product_model;
		$category = [];
		if ($category_slug) {
			$category = $this->CI->db->query("SELECT * FROM categories WHERE slug = " . $this->CI->db->escape($category_slug))->row_array();
		}

		// ── SSR product listing for simple (non-AJAX) themes ──────────────
		// AJAX themes (starter2026 etc.) ignore these; simple themes use them directly.
		$_page       = max(1, (int)($this->CI->input->get('p') ?: 1));
		$_per_page   = 12;
		$_offset     = ($_page - 1) * $_per_page;
		$_search     = trim($this->CI->input->get('search', true) ?: '');
		$_order      = $this->CI->input->get('sort') ?: 'newest';

		$_base = "FROM product p
		          LEFT JOIN product_categories pc ON pc.product_id = p.product_id
		          WHERE p.is_campaign_product = 0 AND p.product_status = 1 AND p.on_store = 1";

		if (!empty($category['id'])) {
			$_base .= " AND pc.category_id = " . (int)$category['id'];
		}
		if ($_search !== '') {
			$_base .= " AND p.product_name LIKE " . $this->CI->db->escape('%' . $_search . '%');
		}

		$_order_sql = match ($_order) {
			'price_asc'  => 'p.product_price ASC',
			'price_desc' => 'p.product_price DESC',
			'popular'    => 'p.view DESC',
			default      => 'p.product_id DESC',
		};

		$_total    = (int)$this->CI->db->query("SELECT COUNT(DISTINCT p.product_id) AS cnt " . $_base)->row()->cnt;
		$_products = $this->CI->db->query(
			"SELECT DISTINCT p.* " . $_base . " ORDER BY {$_order_sql} LIMIT {$_per_page} OFFSET {$_offset}"
		)->result_array();

		return [
			'colors'           => $PM->getAllColors(),
			'tags'             => $PM->getAllTags(),
			'category_tree'    => $PM->getCategoryTree(),
			'category'         => $category,
			'settings'         => $PM->getSettings('store'),
			'user_id'          => $this->_affiliate_user_id_for_listing(),
			// ── SSR product fields (simple themes) ──
			'products'         => $_products,
			'products_total'   => $_total,
			'products_page'    => $_page,
			'products_per_page'=> $_per_page,
			'products_search'  => $_search,
			'products_sort'    => $_order,
		];
	}

	protected function _affiliate_user_id_for_listing() {
		$site_setting = $this->CI->Product_model->getSettings('site');
		$cookie_user_id = $localstorage_user_id = 0;
		if (isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
			$localstorage_user_id = $this->CI->session->localStorageAffiliate;
		}
		if (!isset($site_setting['affiliate_tracking_place']) || $site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2) {
			$cookie_user_id = $this->CI->Cart->getcookieAffiliate('affiliate_id');
		}
		// Match Store::category — localStorage placeholder 1 means “use cookie”
		return ($localstorage_user_id == 1) ? $cookie_user_id : $localstorage_user_id;
	}

	/**
	 * Product detail page — mirrors the full variable set that Store::product() passes to the view.
	 * NOTE: affiliate click-tracking side-effects are intentionally omitted here (API read-only).
	 *
	 * Returns:
	 *   product            array   Full product row + product_created_by_name
	 *   categories         array   Categories this product belongs to
	 *   meta_title         string  Page <title> (= product_name)
	 *   meta_description   string  Meta description (= product_short_description)
	 *   meta_image         string  Full URL to featured image (for OG tags)
	 *   add_tocart_url     string  URL for the add-to-cart AJAX endpoint
	 *   add_coupon_url     string  URL for the coupon AJAX endpoint
	 *   all_images         array   Extra product images [{product_media_upload_path, …}]
	 *   all_videos         array   Product videos [{product_media_upload_path, …}]
	 *   review_list        array   Approved reviews [{rating_number, rating_content, created_by_name, …}]
	 *   review_count       int     Total approved reviews
	 *   avg_rating         float   Average star rating (0 if none)
	 *   allowReview        bool    True when logged-in user has a completed purchase of this product
	 *   is_wishlisted_class string 'w-listed' if in client's wishlist, '' otherwise
	 *   settings           array   Store settings
	 *   category_tree      array   Full category tree
	 *   category           array   Always [] for product pages
	 */
	public function page_product_summary($product_slug) {
		if ((string)$product_slug === '') {
			return ['_error' => 'not_found'];
		}
		$row = $this->CI->db->query("
			SELECT p.*, CONCAT(u.firstname, ' ', u.lastname) AS product_created_by_name
			FROM product p
			LEFT JOIN users u ON u.id = p.product_created_by
			WHERE p.on_store = 1 AND p.product_status = 1
			  AND p.product_slug LIKE " . $this->CI->db->escape($product_slug) . "
			LIMIT 1
		")->row_array();
		if (empty($row)) {
			return ['_error' => 'not_found'];
		}
		$pid = (int)$row['product_id'];

		/* ── reviews ─────────────────────────────────────────────────── */
		$review_agg = $this->CI->db
			->select('AVG(rating_number) as avg_star, COUNT(*) as cnt')
			->where('products_id', $pid)
			->where('rating_status', 1)
			->get('rating')->row();
		$review_count = ($review_agg && $review_agg->cnt > 0) ? (int)$review_agg->cnt : 0;
		$avg_rating   = ($review_count > 0) ? round((float)$review_agg->avg_star, 1) : 0;
		$review_list  = $this->CI->db
			->where('products_id', $pid)
			->where('rating_status', 1)
			->order_by('rating_created', 'DESC')
			->get('rating')->result_array();

		/* ── wishlist state ───────────────────────────────────────────── */
		$is_wishlisted_class = '';
		$login_usr = $this->CI->Cart->is_logged();
		if ($login_usr && !empty($login_usr['products_wishlist'])) {
			$wlist = json_decode($login_usr['products_wishlist'], true) ?: [];
			if (in_array($pid, $wlist)) {
				$is_wishlisted_class = 'w-listed';
			}
		}

		/* ── purchase / review permission ────────────────────────────── */
		$allowReview = false;
		if ($login_usr) {
			$t = $this->CI->Cart->has_purchase($pid, 1);
			if ($t && (int)$t->total > 0) {
				$allowReview = true;
			}
		}

		/* ── featured image URL ───────────────────────────────────────── */
		$fi = $row['product_featured_image'] ?? '';
		if ($fi && (strpos($fi, 'http://') === 0 || strpos($fi, 'https://') === 0)) {
			$meta_image = $fi;
		} else {
			$meta_image = $fi ? base_url('assets/images/product/upload/thumb/' . $fi) : base_url('assets/images/no_image_available.png');
		}

		return [
			'product'            => $row,
			'categories'         => $this->CI->Product_model->getProductCategory($pid),
			'meta_title'         => $row['product_name'] ?? '',
			'meta_description'   => $row['product_short_description'] ?? '',
			'meta_image'         => $meta_image,
			'add_tocart_url'     => $this->CI->Cart->getStoreUrl('add_to_cart'),
			'add_coupon_url'     => $this->CI->Cart->getStoreUrl('add_coupon'),
			'all_images'         => $this->CI->Product_model->getAllImages($pid),
			'all_videos'         => $this->CI->Product_model->getAllVideos($pid),
			'review_list'        => $review_list,
			'review_count'       => $review_count,
			'avg_rating'         => $avg_rating,
			'allowReview'        => $allowReview,
			'is_wishlisted_class'=> $is_wishlisted_class,
			'settings'           => $this->CI->Product_model->getSettings('store'),
			'category_tree'      => $this->CI->Product_model->getCategoryTree(),
			'category'           => [],
		];
	}

	public function generateMustacheProductListData($products, $user_id) {
		$newProducts = [];
		$need_media = [];
		foreach ($products as $p) {
			if (empty($p['product_featured_image'])) {
				$need_media[] = (int) $p['product_id'];
			}
		}
		$media_map = [];
		if (!empty($need_media)) {
			$ph = implode(',', array_fill(0, count($need_media), '?'));
			$rows = $this->CI->db->query("SELECT pm.product_id, pm.product_media_upload_path, pm.product_media_upload_video_image FROM product_media_upload pm INNER JOIN (SELECT product_id, MIN(product_media_upload_id) m FROM product_media_upload WHERE product_id IN ($ph) GROUP BY product_id) s ON pm.product_media_upload_id=s.m", $need_media)->result_array();
			foreach ($rows as $r) {
				$path = $r['product_media_upload_path'] ?? '';
				$vthumb = $r['product_media_upload_video_image'] ?? '';
				$use = '';
				$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
				if (!empty($path) && !in_array($ext, ['mp4', 'webm', 'ogg', 'avi', 'mov'])) {
					$use = $path;
				}
				if (empty($use) && !empty($vthumb)) {
					$use = $vthumb;
				}
				if (!empty($use)) {
					$media_map[$r['product_id']] = (strpos($use, 'http') === 0 || strpos($use, 'https') === 0) ? $use : base_url('assets/images/product/upload/thumb/' . $use);
				}
			}
		}
		foreach ($products as &$product) {
			$product['product_details_href'] = base_url("store/" . base64_encode($user_id) . "/product/" . $product['product_slug']);
			$feat = $product['product_featured_image'] ?? '';
			if (!empty($feat) && (strpos($feat, 'http://') === 0 || strpos($feat, 'https://') === 0)) {
				$product['product_image_src'] = $feat;
			} elseif (!empty($feat)) {
				$product['product_image_src'] = base_url('assets/images/product/upload/thumb/' . $feat);
			} elseif (!empty($media_map[$product['product_id']])) {
				$product['product_image_src'] = $media_map[$product['product_id']];
			} else {
				$product['product_image_src'] = base_url('assets/store/default/img/no-image.png');
			}
			if ($product['country_code']) {
				$product['country_flag_src'] = getFlag($product['country_code']);
			}
			$product['product_avg_rating_stars'] = "";
			for ($i = 0; $i < $product['product_avg_rating']; $i++) {
				$product['product_avg_rating_stars'] .= '<img alt="image" src="' . base_url('assets/store/default/img/st.png') . '">';
			}
			while ($product['product_avg_rating'] < 5) {
				$product['product_avg_rating_stars'] .= '<img alt="image" src="' . base_url('assets/store/default/img/st1.png') . '">';
				$product['product_avg_rating']++;
			}
			$product['product_price'] = (!empty($product['product_price'])) ? c_format($product['product_price']) : c_format($product['0']);
			$title_suffix = (strlen($product['product_name']) > 35) ? "..." : "";
			$product['product_name'] = mb_substr($product['product_name'], 0, 35) . $title_suffix;
			$desc_suffix = (strlen($product['product_short_description']) > 70) ? "..." : "";
			$product['product_short_description'] = mb_substr($product['product_short_description'], 0, 70) . $desc_suffix;
			$newProducts[] = $product;
		}
		return $newProducts;
	}

	/** Same shape as legacy Store::instant_search JSON (array of product hits). */
	public function fragment_instant_search($q) {
		$q = trim($q);
		if (strlen($q) < 2) {
			return [];
		}
		$results = $this->CI->db->query("
			SELECT p.product_id, p.product_name, p.product_price, p.product_featured_image, p.product_slug, p.product_quantity
			FROM product p
			WHERE p.on_store = 1 AND p.product_status = 1 AND p.is_campaign_product = 0
			AND (p.product_name LIKE ? OR p.product_short_description LIKE ?)
			GROUP BY p.product_id
			LIMIT 6
		", ['%' . $q . '%', '%' . $q . '%'])->result_array();
		$products = [];
		foreach ($results as $r) {
			$image = !empty($r['product_featured_image']) ? base_url('assets/images/product/upload/thumb/' . $r['product_featured_image']) : base_url('assets/store/default/img/pr-img.png');
			$products[] = [
				'id' => $r['product_id'],
				'name' => $r['product_name'],
				'price' => number_format((float) $r['product_price'], 2),
				'image' => $image,
				'url' => base_url('store/product/' . $r['product_slug']),
				'in_stock' => ($r['product_quantity'] == -1 || $r['product_quantity'] > 0),
			];
		}
		return $products;
	}

	public function fragment_quick_view($product_id) {
		if (!$product_id) {
			return ['success' => false];
		}
		$product = $this->CI->db->get_where('product', ['product_id' => $product_id, 'on_store' => 1, 'product_status' => 1])->row_array();
		if (!$product) {
			return ['success' => false];
		}
		$images = $this->CI->db->get_where('product_media_upload', ['product_id' => $product_id])->result_array();
		$image_urls = [];
		foreach ($images as $img) {
			if (!empty($img['product_media_upload_path'])) {
				$path = $img['product_media_upload_path'];
				$image_urls[] = (strpos($path, 'http') === 0) ? $path : base_url('assets/images/product/upload/thumb/' . $path);
			}
		}
		$featured = !empty($product['product_featured_image'])
			? base_url('assets/images/product/upload/thumb/' . $product['product_featured_image'])
			: base_url('assets/store/default/img/pr-img.png');
		$variations = !empty($product['product_variations']) ? json_decode($product['product_variations'], true) : [];
		return [
			'success' => true,
			'product' => [
				'id' => $product['product_id'],
				'name' => $product['product_name'],
				'price' => $product['product_price'],
				'description' => $product['product_short_description'],
				'featured_image' => $featured,
				'slug' => $product['product_slug'],
				'quantity' => (int) $product['product_quantity'],
				'url' => base_url('store/product/' . $product['product_slug']),
			],
			'images' => $image_urls,
			'variations' => $variations ?: [],
		];
	}

	/** Same shape as legacy Store::recommendations JSON (array of rows with image_url, url, …). */
	public function fragment_recommendations($product_id) {
		if (!$product_id) {
			return [];
		}
		$query = $this->CI->db->query("
			SELECT op2.product_id, p.product_name, p.product_price, p.product_featured_image, p.product_slug,
				COUNT(*) as co_count
			FROM order_products op1
			INNER JOIN order_products op2 ON op1.order_id = op2.order_id AND op1.product_id != op2.product_id
			INNER JOIN product p ON op2.product_id = p.product_id
			WHERE op1.product_id = ?
			  AND p.on_store = 1 AND p.product_status = 1
			GROUP BY op2.product_id
			ORDER BY co_count DESC
			LIMIT 6
		", [$product_id]);
		$results = $query->result_array();
		if (empty($results)) {
			$query = $this->CI->db->query("
				SELECT p.product_id, p.product_name, p.product_price, p.product_featured_image, p.product_slug
				FROM product p
				INNER JOIN product_categories pc ON p.product_id = pc.product_id
				WHERE pc.category_id IN (SELECT category_id FROM product_categories WHERE product_id = ?)
				  AND p.product_id != ?
				  AND p.on_store = 1 AND p.product_status = 1
				ORDER BY p.product_sales_count DESC
				LIMIT 6
			", [$product_id, $product_id]);
			$results = $query->result_array();
		}
		$need_media = [];
		foreach ($results as $r) {
			if (empty($r['product_featured_image'])) {
				$need_media[] = (int) $r['product_id'];
			}
		}
		$media_map = [];
		if (!empty($need_media)) {
			$ph = implode(',', array_fill(0, count($need_media), '?'));
			$rows = $this->CI->db->query("SELECT pm.product_id, pm.product_media_upload_path, pm.product_media_upload_video_image FROM product_media_upload pm INNER JOIN (SELECT product_id, MIN(product_media_upload_id) m FROM product_media_upload WHERE product_id IN ($ph) GROUP BY product_id) s ON pm.product_media_upload_id=s.m", $need_media)->result_array();
			foreach ($rows as $r) {
				$path = $r['product_media_upload_path'] ?? '';
				$vthumb = $r['product_media_upload_video_image'] ?? '';
				$use = '';
				$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
				if (!empty($path) && !in_array($ext, ['mp4', 'webm', 'ogg', 'avi', 'mov'])) {
					$use = $path;
				}
				if (empty($use) && !empty($vthumb)) {
					$use = $vthumb;
				}
				if (!empty($use)) {
					$media_map[$r['product_id']] = (strpos($use, 'http') === 0 || strpos($use, 'https') === 0) ? $use : base_url('assets/images/product/upload/thumb/' . $use);
				}
			}
		}
		foreach ($results as &$r) {
			$feat = $r['product_featured_image'] ?? '';
			if (!empty($feat) && (strpos($feat, 'http://') === 0 || strpos($feat, 'https://') === 0)) {
				$r['image_url'] = $feat;
			} elseif (!empty($feat)) {
				$r['image_url'] = base_url('assets/images/product/upload/thumb/' . $feat);
			} elseif (!empty($media_map[$r['product_id']])) {
				$r['image_url'] = $media_map[$r['product_id']];
			} else {
				$r['image_url'] = base_url('assets/store/default/img/no-image.png');
			}
			$r['url'] = base_url('store/product/' . $r['product_slug']);
		}
		return $results;
	}

	public function page_cart() {
		return [
			'base_url' => $this->CI->Cart->getStoreUrl(),
			'cart_url' => $this->CI->Cart->getStoreUrl('cart'),
			'products' => $this->CI->Cart->getProducts(),
			'sub_total' => $this->CI->Cart->subTotal(),
			'total' => $this->CI->Cart->subTotal(),
			'settings' => $this->CI->Product_model->getSettings('store'),
		];
	}

	public function page_checkout() {
		$products = $this->CI->Cart->getProducts();
		if (!$products) {
			return ['_error' => 'empty_cart'];
		}
		$downloadable = 0;
		foreach ($products as $value) {
			if ($value['product_type'] == 'downloadable') {
				$downloadable++;
			}
		}
		$all_is_download_product = (count($products) == $downloadable) ? 1 : 0;
		$user = $this->CI->Cart->is_logged();
		$shipping_setting = $this->CI->Product_model->getSettings('shipping_setting');
		$allow_shipping = $this->CI->Cart->allow_shipping;
		if ($all_is_download_product) {
			$allow_shipping = false;
		}
		$show_blue_message = ((int) ($shipping_setting['shipping_in_limited'] ?? 0) == 1);
		$shipping_error_message = null;
		$country = $this->CI->Product_model->getShippingCountry();
		if (is_array($country)) {
			if (is_array($user)) {
				$userArray = $this->CI->db->query("SELECT * FROM shipping_address WHERE user_id = " . (int) $user['id'])->row_array();
				if (!empty($userArray) && !isset($country[$userArray['country_id']])) {
					$shipping_error_message = $shipping_setting['shipping_error_message'] ?? '';
				} else {
					$show_blue_message = false;
				}
			}
		}
		if ($shipping_error_message !== null && $shipping_error_message !== '') {
			$show_blue_message = false;
		}
		$orderCommentRaw = $this->CI->Product_model->getSettings('order_comment');
		$orderCommentRaw['title'] = json_decode($orderCommentRaw['title'], true) ?: [];
		/* ── countries list for SSR checkout form ───────────────────────── */
		$countries_sql = 'SELECT * FROM countries WHERE 1';
		if (is_array($country)) {
			$countries_sql .= count($country) > 0
				? ' AND id IN (' . implode(',', array_keys($country)) . ')'
				: ' AND id IN (0)';
		}
		$countries_list = $allow_shipping ? $this->CI->db->query($countries_sql)->result() : [];

		$out = [
			'products'              => $products,
			'category_tree'         => $this->CI->Product_model->getCategoryTree(),
			'category'              => [],
			'checkout_url'          => $this->CI->Cart->getStoreUrl('checkout'),
			'cart_update_url'       => $this->CI->Cart->getStoreUrl('cart'),
			'is_logged'             => $user,
			'base_url'              => $this->CI->Cart->getStoreUrl(),
			'sub_total'             => $this->CI->Cart->subTotal(),
			'total'                 => $this->CI->Cart->subTotal(),
			'totals'                => $this->CI->Cart->getTotals(),
			'countries'             => $countries_list,  // objects with ->id, ->name (SSR form)
			'paymentsetting'        => $this->CI->Product_model->getSettings('paymentsetting'),
			'allow_shipping'        => $allow_shipping,
			'allow_upload_file'     => $this->CI->Cart->allow_upload_file,
			'allow_comment'         => $this->CI->Cart->allow_comment,
			'shipping_error_message'=> $shipping_setting['shipping_error_message'] ?? '',
			'show_blue_message'     => $show_blue_message,
			'settings'              => $this->CI->Product_model->getSettings('store'),
			'confirm_html'          => '',
			'is_guest_flow'         => isset($_SESSION['guestFlow']),
			'order_comment_setting' => $orderCommentRaw,
		];
		if ($shipping_error_message !== null) {
			$out['shipping_error_message'] = $shipping_error_message;
		}
		return $out;
	}

	public function page_checkout_shipping($country_id = null) {
		$is_logged = $this->CI->Cart->is_logged();
		$this->CI->Cart->reloadCart();
		$data = [
			'allow_shipping' => $this->CI->Cart->allow_shipping,
			'is_guest_flow' => isset($_SESSION['guestFlow']),
			'countries' => [],
			'shipping' => null,
			'country_id' => null,
			'flat_rate_amount' => 0,
		];
		if ($data['is_guest_flow'] || $data['allow_shipping']) {
			if ($is_logged) {
				if ($country_id != null) {
					$data['country_id'] = $country_id;
					$data['shipping'] = $this->CI->db->query("SELECT * FROM shipping_address WHERE user_id =  " . (int) $is_logged['id'] . " AND country_id=" . (int) $country_id)->row();
				} else {
					$data['shipping'] = $this->CI->db->query("SELECT * FROM shipping_address WHERE user_id =  " . (int) $is_logged['id'])->row();
				}
			}
			$countries_sql = 'SELECT * FROM countries WHERE 1';
			$country = $this->CI->Product_model->getShippingCountry();
			if (is_array($country)) {
				if (count($country) == 0) {
					$countries_sql .= ' AND id IN (0) ';
				} else {
					$countries_sql .= ' AND id IN (' . implode(",", array_keys($country)) . ') ';
				}
			}
			$data['countries'] = $this->CI->db->query($countries_sql)->result();
		}
		$selected_country = $country_id;
		if (!$selected_country && !empty($data['shipping'])) {
			$selected_country = $data['shipping']->country_id ?? null;
		}
		$data['flat_rate_amount'] = $selected_country ? ($this->CI->Product_model->getShippingRate($selected_country) ?: 0) : 0;
		return $data;
	}

	public function page_checkout_confirm() {
		$this->CI->Cart->reloadCart();
		return [
			'allow_comment' => $this->CI->Cart->allow_comment,
			'allow_upload_file' => $this->CI->Cart->allow_upload_file,
		];
	}

	public function page_profile() {
		$user = $this->CI->Cart->is_logged();
		if (!$user) {
			return ['_error' => 'unauthorized', 'message' => 'Login required'];
		}
		$userDetails = $this->CI->db->query("SELECT * FROM users WHERE id=" . (int) $user['id'])->row_array();
		return [
			'userDetails' => [
				'type' => $userDetails['type'],
				'firstname' => $userDetails['firstname'],
				'lastname' => $userDetails['lastname'],
				'email' => $userDetails['email'],
				'username' => $userDetails['username'],
				'phone' => $userDetails['phone'],
				'PhoneNumber' => $userDetails['PhoneNumber'],
				'ucountry' => $userDetails['ucountry'],
				'state' => $userDetails['state'],
				'ucity' => $userDetails['ucity'],
				'uzip' => $userDetails['uzip'],
				'avatar' => $userDetails['avatar'],
				'twaddress' => $userDetails['twaddress'],
			],
			'country' => $this->CI->Product_model->getcountry(),
			'settings' => $this->CI->Product_model->getSettings('store'),
			'category_tree' => $this->CI->Product_model->getCategoryTree(),
			'category' => [],
			'return_to' => '',
		];
	}

	public function page_orders() {
		$user = $this->CI->Cart->is_logged();
		if (!$user) {
			return ['_error' => 'unauthorized'];
		}
		$this->CI->load->model('Order_model');
		return [
			'buyproductlist' => $this->CI->Order_model->getOrders(['user_id' => $user['id']]),
			'status' => $this->CI->Order_model->status(),
			'user' => $user,
			'settings' => $this->CI->Product_model->getSettings('store'),
			'category_tree' => $this->CI->Product_model->getCategoryTree(),
			'category' => [],
			'return_to' => '',
		];
	}

	public function page_wishlist() {
		$user = $this->CI->Cart->is_logged();
		if (!$user) {
			return ['_error' => 'unauthorized'];
		}
		$site_setting = $this->CI->Product_model->getSettings('site');
		$cookie_user_id = $localstorage_user_id = 0;
		if (isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
			$localstorage_user_id = $this->CI->session->localStorageAffiliate;
		}
		if (!isset($site_setting['affiliate_tracking_place']) || $site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2) {
			$cookie_user_id = $this->CI->Cart->getcookieAffiliate('affiliate_id');
		}
		$aff_id = $localstorage_user_id == 1 ? $cookie_user_id : $localstorage_user_id;
		$wraw = $user['products_wishlist'] ?? '';
		$wlist = json_decode($wraw ?: '[]');
		if (!is_array($wlist)) {
			$wlist = [];
		}
		return [
			'user_id' => (int) $aff_id,
			'products' => $this->CI->Product_model->get_wishlist_products($wlist),
			'category_tree' => $this->CI->Product_model->getCategoryTree(),
			'category' => [],
		];
	}

	public function page_track_order() {
		return [
			'category_tree' => $this->CI->Product_model->getCategoryTree(),
			'category' => [],
			'track_form_values' => ['order_number' => '', 'email' => ''],
		];
	}

	public function page_my_courses() {
		$user = $this->CI->Cart->is_logged();
		if (!$user) {
			return ['_error' => 'unauthorized'];
		}
		$this->CI->load->model('Order_model');
		return [
			'courses' => $this->CI->Order_model->getMyCourses($user['id']),
			'user' => $user,
			'settings' => $this->CI->Product_model->getSettings('store'),
			'category_tree' => $this->CI->Product_model->getCategoryTree(),
			'category' => [],
		];
	}

	/**
	 * Login page — mirrors Store::login() $data.
	 *
	 * Returns:
	 *   redirect_url   string  URL to go to after successful login (base64-encoded ref)
	 *   settings       array   Store settings
	 *   category_tree  array   Full category tree
	 *   category       array   Always []
	 *
	 * NOTE: login is handled by AJAX endpoint store/ajax_login (returns JSON).
	 *       register is handled by AJAX endpoint store/ajax_register (returns JSON).
	 *       These are NOT form POSTs to the login page.
	 */
	public function page_login() {
		$redirect_url = $this->CI->Cart->getStoreUrl(
			base64_encode($this->CI->session->userdata('refer_id') ?: '')
		);
		return [
			'redirect_url' => $redirect_url,
			'settings'     => $this->CI->Product_model->getSettings('store'),
			'category_tree'=> $this->CI->Product_model->getCategoryTree(),
			'category'     => [],
		];
	}

	/**
	 * About page — mirrors Store::about() $data.
	 *
	 * Returns:
	 *   settings       array   Store settings
	 *   content        array   Language-aware store settings (key: about_content for CMS body HTML)
	 *   category_tree  array   Full category tree
	 *   category       array   Always []
	 *
	 * Key display fields: $content['about_content'], $store_setting['aboutimage'],
	 *   $store_setting['email'], $store_setting['contact_number'], $store_setting['address'],
	 *   $store_setting['contact_us_map']
	 */
	public function page_about() {
		$this->CI->load->model('Common_model');
		$language_id = $this->CI->Common_model->getDefaultLanaguage();
		if ($this->CI->session->userdata('userLang') !== false) {
			$language_id = $this->CI->session->userdata('userLang') ?: $language_id;
		}
		return [
			'settings'     => $this->CI->Product_model->getSettings('store'),
			'content'      => $this->CI->Product_model->getSettingsWithLanaguage('store', $language_id),
			'category_tree'=> $this->CI->Product_model->getCategoryTree(),
			'category'     => [],
		];
	}

	/**
	 * Policy page — mirrors Store::policy() $data.
	 *
	 * Returns:
	 *   content        array   Language-aware store settings (key: policy_content for CMS body HTML)
	 *   category_tree  array   Full category tree
	 *   category       array   Always []
	 */
	public function page_policy() {
		$this->CI->load->model('Common_model');
		$language_id = $this->CI->Common_model->getDefaultLanaguage();
		if ($this->CI->session->userdata('userLang') !== false) {
			$language_id = $this->CI->session->userdata('userLang') ?: $language_id;
		}
		return [
			'content'      => $this->CI->Product_model->getSettingsWithLanaguage('store', $language_id),
			'category_tree'=> $this->CI->Product_model->getCategoryTree(),
			'category'     => [],
		];
	}

	/**
	 * Contact page — mirrors Store::contact() $data.
	 *
	 * Returns:
	 *   settings       array   Store settings (also aliased as storesettings in controller)
	 *   content        array   Language-aware store settings (key: contact_content for CMS body HTML)
	 *   category_tree  array   Full category tree
	 *   category       array   Always []
	 *
	 * Form POSTs to store/contact with fields: name, email, phone, message, terms (all required).
	 * On validation failure the view is re-rendered; use form_error('field') for inline errors.
	 * On success the controller redirects back to HTTP_REFERER.
	 */
	public function page_contact() {
		$this->CI->load->model('Common_model');
		$language_id = $this->CI->Common_model->getDefaultLanaguage();
		if ($this->CI->session->userdata('userLang') !== false) {
			$language_id = $this->CI->session->userdata('userLang') ?: $language_id;
		}
		return [
			'settings'     => $this->CI->Product_model->getSettings('store'),
			'content'      => $this->CI->Product_model->getSettingsWithLanaguage('store', $language_id),
			'category_tree'=> $this->CI->Product_model->getCategoryTree(),
			'category'     => [],
		];
	}

	/**
	 * Custom CMS page by slug — mirrors Store::page($slug) $data.
	 *
	 * Returns:
	 *   settings       array   Store settings (also as storesettings)
	 *   data           object  CMS page object: {slug, title, content, meta_id, …}
	 *                          or '_error' => 'not_found' if slug does not exist
	 */
	public function page_custom_page($slug) {
		$settings     = $this->CI->Product_model->getSettings('store');
		$custom_pages = json_decode($settings['custom_page'] ?? '[]');
		$page_obj     = null;
		if (is_array($custom_pages)) {
			foreach ($custom_pages as &$page) {
				if ($page->slug == $slug) {
					$this->CI->load->model('Setting_model');
					$page->content = $this->CI->Setting_model
						->get_meta_content(['meta_id' => $page->meta_id])->meta_content ?? '';
					$page_obj = $page;
					break;
				}
			}
		}
		if ($page_obj === null) {
			return ['_error' => 'not_found'];
		}
		return [
			'settings'    => $settings,
			'storesettings'=> $settings,
			'data'        => $page_obj,
		];
	}

	/**
	 * Single order detail for logged-in customer — mirrors Store::vieworder() $data.
	 *
	 * Returns:
	 *   order_id        int     The order ID
	 *   order           array   Full order row (id, status(int), created_at, email, user_id, …)
	 *   products        array   Order line items — each row:
	 *                           product_name, quantity, price, total, product_featured_image (full URL),
	 *                           product_type, downloadable_files, …
	 *   totals          array   Keyed summary rows — each: ['text'=>string, 'value'=>float]
	 *                           Keys: total, discount_total, shipping_cost, tax_cost, grand_total
	 *   order_history   array   History rows — each: ['created_at'=>string, 'comment'=>string, …]
	 *   paymentsetting  array   Payment gateway settings
	 *   settings        array   Store settings
	 *   status          array   Status map ['0'=>'Waiting for payment','1'=>'Complete',…]
	 *   category_tree   array   Full category tree
	 *   category        array   Always []
	 *
	 * Returns '_error' => 'unauthorized' if user not logged in.
	 * Returns '_error' => 'forbidden'   if order does not belong to this user.
	 */
	public function page_view_order($order_id) {
		$order_id = (int)$order_id;
		$user     = $this->CI->Cart->is_logged();
		if (!$user) {
			return ['_error' => 'unauthorized'];
		}
		$this->CI->load->model('Order_model');
		$order = $this->CI->Order_model->getOrder($order_id, 'store');
		if (empty($order) || (int)$order['user_id'] !== (int)$user['id']) {
			return ['_error' => 'forbidden'];
		}
		$products = $this->CI->Order_model->getProducts($order_id);
		return [
			'order_id'      => $order_id,
			'order'         => $order,
			'products'      => $products,
			'totals'        => $this->CI->Order_model->getTotals($products, $order),
			'order_history' => $this->CI->Order_model->getHistory($order_id, 'order'),
			'paymentsetting'=> $this->CI->Product_model->getSettings('paymentsetting'),
			'settings'      => $this->CI->Product_model->getSettings('store'),
			'status'        => $this->CI->Order_model->status(),
			'category_tree' => $this->CI->Product_model->getCategoryTree(),
			'category'      => [],
		];
	}

	/**
	 * Order confirmation / thank-you page — mirrors Store::thankyou($uncompleted_id) $data.
	 * Also used by Store::track_order() POST success path.
	 *
	 * Returns:
	 *   order            array   Full order row
	 *   products         array   Order line items (same shape as page_view_order)
	 *   totals           array   Same shape as page_view_order totals
	 *   client_loged     bool    True when a client session exists
	 *   is_guest         mixed   Client array if guest checkout, [] otherwise
	 *   order_history    array   Payment/order history rows
	 *   status           array   Status map
	 *   paymentsetting   array   Payment gateway settings
	 *   settings         array   Store settings
	 *
	 * Returns '_error' => 'not_found' if order_id is invalid.
	 */
	public function page_checkout_thankyou($order_id) {
		$order_id = (int)$order_id;
		if ($order_id < 1) {
			return ['_error' => 'not_found'];
		}
		$this->CI->load->model('Order_model');
		$order = $this->CI->Order_model->getOrder($order_id, 'store');
		if (empty($order)) {
			return ['_error' => 'not_found'];
		}
		$products  = $this->CI->Order_model->getProducts($order_id);
		$user      = $this->CI->session->userdata('client');
		return [
			'order'         => $order,
			'products'      => $products,
			'totals'        => $this->CI->Order_model->getTotals($products, $order),
			'client_loged'  => (bool)$user,
			'is_guest'      => is_array($user) ? $user : [],
			'order_history' => $this->CI->Order_model->getHistory($order_id, 'order'),
			'status'        => $this->CI->Order_model->status(),
			'paymentsetting'=> $this->CI->Product_model->getSettings('paymentsetting'),
			'settings'      => $this->CI->Product_model->getSettings('store'),
		];
	}

	/**
	 * Whether the logged-in user may stream this LMS asset for the order (matches Store::play lookup; no paths returned).
	 */
	public function page_lms_play_meta($video_id, $order_id, $user_id) {
		$video_id = trim((string) $video_id);
		$order_id = (int) $order_id;
		$user_id = (int) $user_id;
		if ($video_id === '' || $order_id < 1 || $user_id < 1) {
			return ['_error' => 'bad_request'];
		}
		$this->CI->load->model('Order_model');
		$order = $this->CI->Order_model->getOrder($order_id, 'store', ['user_id' => $user_id]);
		if (empty($order)) {
			return ['_error' => 'forbidden'];
		}
		$products = $this->CI->Order_model->getProducts($order_id);
		$found = false;
		foreach ($products as $product) {
			if (empty($product['downloadable_files']) || !is_array($product['downloadable_files'])) {
				continue;
			}
			foreach ($product['downloadable_files'] as $innerValue) {
				if (empty($innerValue['data']) || !is_array($innerValue['data'])) {
					continue;
				}
				foreach ($innerValue['data'] as $value) {
					if (!empty($value['name']) && $value['name'] === $video_id) {
						$found = true;
						break 3;
					}
				}
			}
		}
		return [
			'video_id' => $video_id,
			'order_id' => $order_id,
			'authorized' => $found,
		];
	}

	public function handle_load_product() {
		try {

			$restricted_vendors = $this->CI->get_restricted_vendors();

			$postData = $this->CI->input->post(NULL, false);
			if (empty($postData)) {
				$postData = $_POST;
			}

			$sql = "
			SELECT p.*, c.sortname AS country_code, s.name AS state_name, c.name AS country_name, pc.category_id AS p_catecategory_id
			FROM product p
			LEFT JOIN product_affiliate pa ON pa.product_id = p.product_id
			LEFT JOIN users as seller ON pa.user_id = seller.id
			LEFT JOIN states s ON s.id = p.state_id
			LEFT JOIN countries c ON c.id = s.country_id
			LEFT JOIN product_categories pc ON pc.product_id = p.product_id
			WHERE is_campaign_product = 0 AND product_status = 1 AND on_store = 1 AND (seller.is_vendor = 1 OR seller.type IS NULL) AND ( pa.id IS NULL OR seller.id > 0)
			";

			//Start share sale filter
			$escapevendors = $this->CI->db->query('SELECT user_id,vendor_shares_sales_status,vendor_status FROM vendor_setting ')->result_array();

			$allVendors = $this->CI->db->query('SELECT id FROM users WHERE is_vendor=1')->result_array();

		$userdetails = $this->CI->Cart->is_logged() ?: [];
		$userrefid = $userdetails['refid'] ?? 0;

		$allowVendors = [];

			foreach($escapevendors as $esc) 
			{
				if($esc['vendor_shares_sales_status']==1)
					$allowVendors[] = $esc['user_id'];
				else if($esc['vendor_shares_sales_status']==2 && $esc['user_id']==$userrefid)
					$allowVendors[] = $esc['user_id'];
				else if($esc['vendor_status'] !=0 )
					$allowVendors[] = $esc['user_id'];

			}
			
			$escapeUsers = [];
			foreach($allVendors as $v) {
				if(!in_array($v['id'], $allowVendors)){
					
					$escapeUsers[] = $v['id'];
				}
			}
			$restricted_vendors=array_unique(array_merge($restricted_vendors, $escapeUsers));
			//End share sale filter

			if(isset($postData['created_by']) && !empty($postData['created_by'])){
				$sql .= " AND p.product_created_by = " .$postData['created_by']. " ";
			}

			if(isset($postData['search']) && !empty($postData['search'])){
				$searchValue = $postData['search'];
				$sql .= " AND p.product_name LIKE '%" . $searchValue . "%' ";
			}


			if(isset($postData['category_slug']) && !empty($postData['category_slug'])){
				$categorySlug = $postData['category_slug'];
				$categoryInfo = $this->CI->Product_model->categoryInfo($categorySlug);
				$categoryId = $categoryInfo[0]->id;
				if($categoryId!="")
				$sql .= " AND pc.category_id =" . $categoryId;
			}
			 

			$site_setting = $this->CI->Product_model->getSettings('site');

			$cookie_user_id = $localstorage_user_id =0;
			if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
				$localstorage_user_id = $this->CI->session->localStorageAffiliate;
			}
			if(! isset($site_setting['affiliate_tracking_place']) || $site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2) {
				$cookie_user_id = $this->CI->Cart->getcookieAffiliate('affiliate_id');                
			} 

			$data['user_id'] = $localstorage_user_id <= 1 ? $cookie_user_id : $localstorage_user_id;


			$vendor = $this->CI->Product_model->getSettings('vendor');
			if((int)$vendor['storestatus'] == 0){
				$sql .= " AND( seller.id=0 OR seller.id IS NULL )";
			}
 
			 
			if (isset($restricted_vendors) && !empty($restricted_vendors)) {
				$tempvq = "";

				foreach ($restricted_vendors as $vid) {
					if($tempvq != "") {
						$tempvq .= " AND (seller.id IS NULL OR seller.id != ".(int)$vid.") ";
					} else {
						$tempvq .= " (seller.id IS NULL OR seller.id != ".(int)$vid.") ";
					}

				}

				if($tempvq != "") {
					$sql .= " AND ( ".$tempvq." ) ";
				}
			}

			$data['add_tocart_url'] = $this->CI->Cart->getStoreUrl('add_to_cart');
			$json = [];
			if(isset($postData['request_page'])) {
				$page_number = isset($postData['next_page']) ? $postData['next_page'] : 1;  
				$results_per_page = isset($postData['limit']) ? $postData['limit'] : 12;  
				$page_first_result = ($page_number-1) * $results_per_page; 
 
				switch ($postData['request_page']) 
				{
					case 'home':
					$json['category'] = [];
					$all_categories = $this->CI->db->query("SELECT * FROM categories")->result_array();
					$json['category']['all'] = "";

					 
					if(!isset($postData['request_page_section']) || $postData['request_page_section'] == 'trending') 
					{

						$trending_sql = $sql;
						if(!empty($all_categories)) 
						{
							$trend_cat = false;
							$first = true;
							for ($i=0; $i < sizeof($all_categories); $i++) 
							{ 
								if($all_categories[$i]['tag'] == 1) 
								{
									$trend_cat = true;
									$json['category']['all'] .= '<a href="'.base_url('store/category/'.$all_categories[$i]['slug']).'" class="category-home"><img alt="image" src="'.base_url('assets/images/product/upload/thumb/').$all_categories[$i]['image'].'"><h3>'.$all_categories[$i]['name'].'</h3></a>';
									if($first == false) {
										$trending_sql .= " OR ";
									} else {
										$trending_sql .= " AND (";
									}
									$trending_sql .= " pc.category_id = ". $all_categories[$i]['id'];
									$first = false;
								}
							}
							if($trend_cat == true) {
								$trending_sql .= ") ";
							}
						}
 
						$json['trendings'] = [];
						$number_of_result = $json['trendings']['total_count'] = $this->CI->db->query($trending_sql)->num_rows();
						$json['trendings']['number_of_page'] = ceil($number_of_result / $results_per_page);  
						$json['trendings']['is_last_page'] = ($page_number < $json['trendings']['number_of_page']) ? false : true;
						$trending_sql .= " GROUP BY p.product_id ORDER BY p.view DESC LIMIT ".$results_per_page." OFFSET ".$page_first_result;
						$json['trendings']['next_page'] = (!$json['trendings']['is_last_page']) ? ($page_number + 1) : 1;			

					 
						$products = $this->CI->db->query($trending_sql)->result_array();

						$json['trendings']['products'] = $this->generateMustacheProductListData($products, $data['user_id']);

						$json['trendings']['show_dummy'] = $this->CI->db->query('SELECT product_id FROM product limit 1')->row();
					}

					if(!isset($postData['request_page_section']) || $postData['request_page_section'] == 'new') 
					{
						$new_sql = $sql;
						$json['category']['new'] = "";
						if(!empty($all_categories)) 
						{
							
							$new_cat = false;
							$first = true;
							for ($i=0; $i < sizeof($all_categories); $i++) 
							{ 
								if($all_categories[$i]['tag'] == 1) {
									$new_cat = true;
									if($first == false) {
										$new_sql .= " AND ";
									} else {
										$new_sql .= " AND (";
									}
									$new_sql .= " pc.category_id != ". $all_categories[$i]['id'];
									$first = false;
								} else {
									$json['category']['all'] .= '<a href="'.base_url('store/category/'. $all_categories[$i]['slug']).'" class="category-home"><img alt="image" src="'.base_url('assets/images/product/upload/thumb/').$all_categories[$i]['image'].'"><h3>'.$all_categories[$i]['name'].'</h3></a>';
									$json['category']['new'] .= '<li><a href="'.base_url('store/category/'. $all_categories[$i]['slug']).'">'.$all_categories[$i]['name'].'</a></li>';
								}
							}
							if($new_cat == true) {
								$new_sql .= ") ";
							}
						}

						$json['new'] = [];
						$number_of_result = $json['new']['total_count'] = $this->CI->db->query($new_sql)->num_rows();

						$json['new']['number_of_page'] = ceil($number_of_result / $results_per_page);  
						$json['new']['is_last_page'] = ($page_number < $json['new']['number_of_page']) ? false : true;
						$new_sql .= " GROUP BY p.product_id ORDER BY p.product_created_date DESC LIMIT ".$results_per_page." OFFSET ".$page_first_result;
						$json['new']['next_page'] = (!$json['new']['is_last_page']) ? ($page_number + 1) : 1;
						 
						$products = $this->CI->db->query($new_sql)->result_array();
						$json['new']['products'] = $this->generateMustacheProductListData($products, $data['user_id']);


						$json['new']['show_dummy'] = $this->CI->db->query('SELECT product_id FROM product limit 1')->row();

					}

					break;
					case 'product-details':
					$relatde_sql = $sql;
					$relatde_sql .= " AND pc.category_id = ". $postData['category_id']." AND p.product_id != ". $postData['product_id'];
					$json['related'] = [];
					$number_of_result = $json['related']['total_count'] = $this->CI->db->query($relatde_sql)->num_rows();
					$json['related']['number_of_page'] = ceil($number_of_result / $results_per_page);  
					$json['related']['is_last_page'] = ($page_number < $json['related']['number_of_page']) ? false : true;
					$relatde_sql .= " GROUP BY p.product_id ORDER BY p.view DESC LIMIT ".$results_per_page." OFFSET ".$page_first_result;
					$json['related']['next_page'] = (!$json['related']['is_last_page']) ? ($page_number + 1) : 1;
					$products = $this->CI->db->query($relatde_sql)->result_array();
					$json['related']['products'] = $this->generateMustacheProductListData($products, $data['user_id']);
					$json['related']['show_dummy'] = $this->CI->db->query('SELECT product_id FROM product limit 1')->row();
					break;
					case 'category':
					$category_sql = $sql;
					if($postData['category_slug']){
						$category = $this->CI->db->query("SELECT * FROM categories WHERE slug = ". $this->CI->db->escape($postData['category_slug']))->row_array();
						if(is_array($category) && isset($category['id'])){
							$category_sql .= " AND pc.category_id = ". $category['id'];
						}
					}
					$json['category'] = [];


					if(isset($postData['colors']) && !empty($postData['colors'])){
						$category_sql .= " AND (";
						for ($i=0; $i < sizeOf($postData['colors']); $i++) { 
							if($i != 0) {
								$category_sql .= " OR ";
							}
							$category_sql .= " p.product_variations LIKE '%" . $postData['colors'][$i] . "%' ";
						}
						$category_sql .= " ) ";
					}

					if(isset($postData['tags']) && !empty($postData['tags'])){
						$category_sql .= " AND (";
						for ($i=0; $i < sizeOf($postData['tags']); $i++) { 
							if($i != 0) {
								$category_sql .= " OR ";
							}
							$category_sql .= " p.product_tags LIKE '%" . $postData['tags'][$i] . "%' ";
						}
						$category_sql .= " ) ";
					}

					if(isset($postData['product_avg_rating'])) {
						$category_sql .= " AND p.product_avg_rating = ".$postData['product_avg_rating'];
					}

				$_max_price = isset($postData['max_price']) && $postData['max_price'] !== '' ? (float)$postData['max_price'] : 10000;
				$_min_price = isset($postData['min_price']) && $postData['min_price'] !== '' ? (float)$postData['min_price'] : 0;

				if ($_max_price < 10000) {
					$category_sql .= " AND p.product_price <= " . $_max_price;
				}
				if ($_min_price > 0) {
					$category_sql .= " AND p.product_price >= " . $_min_price;
				}

					$number_of_result = $json['category']['total_count'] = $this->CI->db->query($category_sql)->num_rows();
					$json['category']['number_of_page'] = ceil($number_of_result / $results_per_page);  
					$json['category']['is_last_page'] = ($page_number < $json['category']['number_of_page']) ? false : true;

					$order_by = "p.view";

					if(isset($postData['order_by'])) {
						switch ($postData['order_by']) {
							case 'low-to-high':
							$order_by = "p.product_price ASC";
							break;
							case 'high-to-low':
							$order_by = "p.product_price DESC";
							break;
							case 'latest':
							$order_by = "p.product_created_date DESC";
							break;
							default:
							$order_by = "p.view DESC";
							break;
						}
					}

					$category_sql .= " GROUP BY p.product_id ORDER BY ".$order_by." LIMIT ".$results_per_page." OFFSET ".$page_first_result;
					$json['category']['category_sql'] = $category_sql;
					$json['category']['next_page'] = (!$json['category']['is_last_page']) ? ($page_number + 1) : 1;
					$products = $this->CI->db->query($category_sql)->result_array();

					$json['category']['count'] = sizeof($products);
					$json['category']['products'] = $this->generateMustacheProductListData($products, $data['user_id']);
					$json['category']['show_dummy'] = $this->CI->db->query('SELECT product_id FROM product limit 1')->row();
					break;
					default:
					return ['mode' => 'json', 'body' => json_encode(['status' => false, 'details' => 'Unknown Requsted Page!'])];
					break;
				}	

				return ['mode' => 'json', 'body' => json_encode($json)];
			} else {
				$data['products_list'] = $this->CI->db->query($sql)->result_array();

				// Batch-fetch ratings for listed products (avoids N+1 queries in view)
				$_list_ids = array_column($data['products_list'], 'product_id');
				if (!empty($_list_ids)) {
					$_ids_str = implode(',', array_map('intval', $_list_ids));
					$_ratings = $this->CI->db->query("
						SELECT products_id, AVG(rating_number) as avg_star, COUNT(*) as cnt
						FROM rating WHERE products_id IN ($_ids_str) AND rating_status = 1
						GROUP BY products_id
					")->result_array();
					$data['product_ratings'] = array_column($_ratings, null, 'products_id');
				} else {
					$data['product_ratings'] = [];
				}

				return ['mode' => 'view', 'view' => 'product_list', 'data' => $data];
			}
		} catch (\Throwable $th) {
			return ['mode' => 'json', 'body' => json_encode(['status' => false, 'details' => $th->getMessage()])];
		}

	
	}
}
