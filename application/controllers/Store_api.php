<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cart-mode store JSON API + theme manifest (default / starter2026 / LMS contract).
 * Classified theme is out of scope.
 */
class Store_api extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Cart');
		$this->load->library('store_cart_payload');
	}

	protected function json_out($payload, $code = 200) {
		$this->output
			->set_status_header($code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	}

	protected function json_error($message, $code = 'error', $http = 400) {
		$this->json_out(['error' => ['code' => $code, 'message' => $message]], $http);
	}

	/** GET store/api/v1/theme/manifest */
	public function theme_manifest() {
		$this->json_out($this->store_cart_payload->get_manifest_v1());
	}

	/** GET store/api/v1/theme/examples/(resource) — dev-oriented samples; no live PII */
	public function theme_example($resource = '') {
		$sample = $this->store_cart_payload->get_example_payload($resource);
		if ($sample === null) {
			$this->json_error('Unknown resource', 'not_found', 404);
			return;
		}
		$this->json_out($sample);
	}

	/** GET store/api/v1/pages/home */
	public function page_home() {
		$this->json_out(['version' => 1, 'page' => 'home', 'data' => $this->store_cart_payload->page_home()]);
	}

	/** GET store/api/v1/pages/category/(slug) */
	public function page_category($slug = '') {
		$this->json_out(['version' => 1, 'page' => 'category', 'data' => $this->store_cart_payload->page_category($slug)]);
	}

	/** GET store/api/v1/pages/product/(slug) */
	public function page_product($slug = '') {
		$payload = $this->store_cart_payload->page_product_summary($slug);
		if ($payload === null) {
			$this->json_error('Product not found', 'not_found', 404);
			return;
		}
		$this->json_out(['version' => 1, 'page' => 'product', 'data' => $payload]);
	}

	/** POST store/api/v1/fragments/product-grid — same body as Store/load_Product */
	public function fragment_product_grid() {
		if ($this->input->method() !== 'post') {
			$this->json_error('Method not allowed', 'method_not_allowed', 405);
			return;
		}
		$_POST = $this->input->post(NULL, false) ?: [];
		$out = $this->store_cart_payload->handle_load_product();
		if ($out['mode'] === 'json') {
			$this->output->set_content_type('application/json', 'utf-8')->set_output($out['body']);
			return;
		}
		$this->json_error('Invalid fragment response', 'server_error', 500);
	}

	/** GET store/api/v1/fragments/instant_search?q= */
	public function fragment_instant_search() {
		$q = $this->input->get('q', true) ?: '';
		$items = $this->store_cart_payload->fragment_instant_search($q);
		$this->json_out(['version' => 1, 'fragment' => 'instant_search', 'items' => $items]);
	}

	/** GET store/api/v1/fragments/quick_view/(id) */
	public function fragment_quick_view($product_id = 0) {
		$payload = $this->store_cart_payload->fragment_quick_view((int) $product_id);
		if (empty($payload['success'])) {
			$this->json_error('Not found', 'not_found', 404);
			return;
		}
		$this->json_out(array_merge(['version' => 1, 'fragment' => 'quick_view'], $payload));
	}

	/** GET store/api/v1/fragments/recommendations/(id) */
	public function fragment_recommendations($product_id = 0) {
		$items = $this->store_cart_payload->fragment_recommendations((int) $product_id);
		$this->json_out(['version' => 1, 'fragment' => 'recommendations', 'items' => $items]);
	}

	/** GET store/api/v1/pages/cart */
	public function page_cart() {
		$this->json_out(['version' => 1, 'page' => 'cart', 'data' => $this->store_cart_payload->page_cart()]);
	}

	/** GET store/api/v1/pages/checkout */
	public function page_checkout() {
		$data = $this->store_cart_payload->page_checkout();
		if (!empty($data['_error'])) {
			$this->json_error('Cart is empty', $data['_error'], 400);
			return;
		}
		$this->json_out(['version' => 1, 'page' => 'checkout', 'data' => $data]);
	}

	/** GET store/api/v1/pages/checkout_shipping */
	public function page_checkout_shipping() {
		$this->json_out(['version' => 1, 'page' => 'checkout_shipping', 'data' => $this->store_cart_payload->page_checkout_shipping()]);
	}

	/** GET store/api/v1/pages/checkout_confirm */
	public function page_checkout_confirm() {
		$this->json_out(['version' => 1, 'page' => 'checkout_confirm', 'data' => $this->store_cart_payload->page_checkout_confirm()]);
	}

	/** GET store/api/v1/pages/profile */
	public function page_profile() {
		$data = $this->store_cart_payload->page_profile();
		if (!empty($data['_error']) && $data['_error'] === 'unauthorized') {
			$this->json_error($data['message'] ?? 'Login required', 'unauthorized', 401);
			return;
		}
		$this->json_out(['version' => 1, 'page' => 'profile', 'data' => $data]);
	}

	/** GET store/api/v1/pages/orders */
	public function page_orders() {
		$data = $this->store_cart_payload->page_orders();
		if (!empty($data['_error']) && $data['_error'] === 'unauthorized') {
			$this->json_error('Login required', 'unauthorized', 401);
			return;
		}
		$this->json_out(['version' => 1, 'page' => 'orders', 'data' => $data]);
	}

	/** GET store/api/v1/pages/wishlist */
	public function page_wishlist() {
		$data = $this->store_cart_payload->page_wishlist();
		if (!empty($data['_error']) && $data['_error'] === 'unauthorized') {
			$this->json_error('Login required', 'unauthorized', 401);
			return;
		}
		$this->json_out(['version' => 1, 'page' => 'wishlist', 'data' => $data]);
	}

	/** GET store/api/v1/pages/track_order */
	public function page_track_order() {
		$this->json_out(['version' => 1, 'page' => 'track_order', 'data' => $this->store_cart_payload->page_track_order()]);
	}

	/** GET store/api/v1/pages/my_courses */
	public function page_my_courses() {
		$data = $this->store_cart_payload->page_my_courses();
		if (!empty($data['_error']) && $data['_error'] === 'unauthorized') {
			$this->json_error('Login required', 'unauthorized', 401);
			return;
		}
		$this->json_out(['version' => 1, 'page' => 'my_courses', 'data' => $data]);
	}

	/** GET store/api/v1/pages/lms_play?track=&orderId= — auth check only; stream stays on store/play */
	public function page_lms_play() {
		$user = $this->Cart->is_logged();
		if (empty($user['id'])) {
			$this->json_error('Login required', 'unauthorized', 401);
			return;
		}
		$track = $this->input->get('track', true);
		$orderId = $this->input->get('orderId', true);
		$data = $this->store_cart_payload->page_lms_play_meta($track, $orderId, $user['id']);
		if (!empty($data['_error'])) {
			$code = $data['_error'];
			$http = ($code === 'forbidden') ? 403 : 400;
			$this->json_error('Invalid LMS play request', $code, $http);
			return;
		}
		$this->json_out(['version' => 1, 'page' => 'lms_play', 'data' => $data]);
	}
}
