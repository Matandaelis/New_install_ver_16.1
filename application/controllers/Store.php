<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'hooks/Affiliate_Hook.php';

class Store extends MY_Controller {

	function __construct($params = array()) {

		parent::__construct();
		$this->load->helper('cookie');
		$this->load->helper('utility');
		$this->load->model('Product_model');
		$this->load->model('Common_model');
		$this->load->model('Cart');

		$this->store_setting = $store_setting = $this->Product_model->getSettings('store');

		$action = end($this->uri->segment_array());
		
		$site_setting = $this->Product_model->getSettings('site');
		
		if ($site_setting['store_maintenance_mode']) {
			echo $this->load->view('common/maintenance', [], true);
			die;
		}
		

		if ($store_setting['status'] == 0) {
		    $adminData = $this->session->userdata('administrator');
		    if (empty($adminData)) {
		        header("Location: " . base_url());
		        exit;
		    }
		}

		$this->load->library("storeapp");
		$this->load->library('store_cart_payload');

		if(isset($_POST['localStorageAffiliate'])) {
			$_SESSION['localStorageAffiliate'] =  (int)$_POST['localStorageAffiliate'];
			echo "adffiliate defined..";
			die; 
		}
		
		$site_setting = $this->Product_model->getSettings('site');

		// Don't block crawlers/bots with LocalStorage checks
		$user_agent = $this->input->user_agent();
		$is_bot = (preg_match('/bot|crawl|slurp|spider|facebookexternalhit/i', $user_agent));

		// V14 AJAX endpoints must bypass the localStorage redirect
		$_v14_ajax_endpoints = ['quick_view', 'instant_search', 'recommendations', 'track_cart'];
		$_v14_is_ajax = in_array($this->uri->segment(2), $_v14_ajax_endpoints);

		if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
			if(!$is_bot && !$_v14_is_ajax && (! isset($_SESSION['localStorageAffiliate']) || isset($_SESSION['setLocalStorageAffiliate']))){
				$currentURL = current_url();
				$data = ["redirect"=>$currentURL];
				echo $this->load->view('localstorage', $data, true);
				die;
			}
		}

		___construct(1);

		$user = $this->session->userdata('client');
		
		if (isset($user['id'])) {
			$this->Product_model->ping($user['id']);
		}

		if(!isset($_SESSION['userLang'])) {
			$this->Product_model->setBrowserLanguage();
		}
	}

	public function guestCheckout() {
		$is_logged = $this->Cart->is_logged(); 
		if($this->input->server('REQUEST_METHOD') === 'POST' && !$is_logged){
			$_SESSION['guestFlow'] = true;
			echo json_encode(['status'=>true]); exit;
		}
		echo json_encode(['status'=>false]); exit;
	}

	private function getUser($user_id){
		return $this->db->query("SELECT * FROM users WHERE id=". (int)$user_id)->row_array();
	}

	public function change_language($language_id = null) {
	    if ($language_id === null) {
	        header("Location: ".base_url('store/login'));
	        die;
	    }

	    $query = $this->db->query("SELECT * FROM language WHERE id=" . $this->db->escape($language_id));
	    $language = $query->row_array();

	    if ($language) {
	        $_SESSION['userLang'] = $language_id;
	        header('Location: ' . $_SERVER['HTTP_REFERER']);
	    } else {
	        header("Location: ".base_url('store/login'));
	        die;
	    }
	}

	public function change_currency($currency_code){
		$currency = $this->db->query("SELECT * FROM currency WHERE code = '{$currency_code}' ")->row_array();
		if($currency){
			$_SESSION['userCurrency'] = $currency_code;
			$_SESSION['userDecimalPlace'] = $currency['decimal_place'];
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else { 
			header("Location: ".base_url('store/login'));
			die;
		}
	}
	
	public function by_id($product_id){
		$this->load->model('Product_model');
		$product = $this->Product_model->getProductById($product_id);
		$link = base_url("store/". base64_encode( (int)$product->refer_id )  ."/product/". $product->product_slug);
		redirect($link);
	}

	public function index($user_id = 0, $store_slug = null){

		$this->load->library('user_agent');

		if($store_slug == null) {
			$store_slug = $user_id;
		}

		$store_slug = rawurldecode($store_slug); // Decode the slug

		$user_id = base64_decode($user_id);
		
		if(isset($_GET['id']) && !empty($_GET['id'])) {
			$user_id = (int)$_GET['id'];
		}
		
		$site_setting = $this->Product_model->getSettings('site');
			
		$checkStore="";
		$productionstore=""; 

		if($store_slug=="productionstore" && is_numeric($user_id)==1 && $user_id>0)
		{

			$productionstore = $this->db->query("SELECT users.id,users.type,users.firstname,users.lastname,users.avatar,countries.sortname AS country_code,countries.name AS country_name,states.name as state_name,users.store_name,users.store_contact_us_map,users.store_address,users.store_email,users.store_contact_number,users.store_terms_condition,users.store_slug,users.store_meta, CONCAT(`firstname`,' ',`lastname`) `store_owner` FROM users LEFT JOIN countries ON countries.id = users.ucountry LEFT JOIN states ON states.id = users.state WHERE users.id=".$user_id."  AND users.status != 0")->row_array();
			 
		}
		else
		{
			$checkStore = $this->db->query("SELECT users.id,users.type,users.firstname,users.lastname,users.avatar,countries.sortname AS country_code,countries.name AS country_name,states.name as state_name,users.store_name,users.store_contact_us_map,users.store_address,users.store_email,users.store_contact_number,users.store_terms_condition,users.store_slug,users.store_meta, CONCAT(`firstname`,' ',`lastname`) `store_owner` FROM users LEFT JOIN countries ON countries.id = users.ucountry LEFT JOIN states ON states.id = users.state WHERE store_slug like '".$store_slug."' AND users.status != 0")->row_array();

		}


		if($user_id > 0)
		{
			$data = array('affiliate_id'=>$user_id);

			if(empty($checkStore) && empty($productionstore)) 
			{
				
				if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
					$store_setting = $this->Product_model->getSettings('store');
					$time = ($store_setting['affiliate_cookie'] * 24 * 60 * 60) * 1000;
					$_SESSION['setLocalStorageAffiliate'] = json_encode([$user_id, $time]);
					$this->Cart->setReferId($user_id);
				}

				if(! isset($site_setting['affiliate_tracking_place']) || ($site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2)) {

					$this->Cart->setcookieAffiliate($user_id);
				}
				redirect(base_url('store'));
			} else {

			
			if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
				$store_setting = $this->Product_model->getSettings('store');
				$time = ($store_setting['affiliate_cookie'] * 24 * 60 * 60) * 1000;
				$_SESSION['setLocalStorageAffiliateAjax'] = json_encode([$user_id, $time]);
				$this->Cart->setReferId($user_id);
 
			}

			if(! isset($site_setting['affiliate_tracking_place']) || ($site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2)) {
				$this->Cart->setcookieAffiliate($user_id);
			}
		}
	} 
	else 
	{

			$cookie_user_id = $localstorage_user_id = 0;

			if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
				$localstorage_user_id = $this->session->localStorageAffiliate;
			}

			if(! isset($site_setting['affiliate_tracking_place']) || $site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2) {
				$cookie_user_id = $this->Cart->getcookieAffiliate('affiliate_id');  
			} 

			$user_id = $localstorage_user_id <= 1 ? $cookie_user_id : $localstorage_user_id;
			$this->Cart->setReferId($user_id);

		}

		$data['user_id'] = (int)$user_id;


		if(!empty($checkStore) && isset($checkStore['id']) && !empty($checkStore['id'])) 
		{
			$data['store_details'] = $checkStore;
			 
			$this->storeapp->view("vendor_store",$data);

		} 
		else if(!empty($productionstore) && isset($productionstore['id']) && !empty($productionstore['id']))
		{	
			 
			$this->load->model('User_model');
			$data['settings'] = $this->Product_model->getSettings('store');
			$user_id=$data['user_id'];
			$usercountry = $this->User_model->getUserCountryUserId($user_id);
			$data['settings'] ['country_name']=$usercountry['name'];
			$data['settings'] ['country_code']=$usercountry['sortname'];
			$data['store_details'] = $productionstore;

			$this->storeapp->view("user-proudcts",$data);

		} 
		else {
			$data = array_merge($data, $this->store_cart_payload->page_home());
			$this->storeapp->view("home", $data);

		}
	}

	public function page($slug)
	{
		$data['storesettings'] = $data['settings'] = $this->Product_model->getSettings('store');
		$custom_pages = json_decode($data['storesettings']['custom_page']);
		foreach($custom_pages as &$page){
			if($page->slug == $slug) {
				$this->load->model('Setting_model');
				$page->content = $this->Setting_model->get_meta_content(['meta_id'=>$page->meta_id])->meta_content;
				$data['data'] = $page; 
			}
		}
		if(!isset($data['data'])) {
			redirect(base_url('store'));
			exit;
		}
		$this->storeapp->view("custom_page", $data);
	}

	public function load_Product() {
		$out = $this->store_cart_payload->handle_load_product();
		if ($out['mode'] === 'json') {
			$this->output->set_content_type('application/json', 'utf-8')->set_output($out['body']);
			return;
		}
		if ($out['mode'] === 'view') {
			echo $this->storeapp->view($out['view'], $out['data'], true, true);
			return;
		}
	}

	public function generateMustacheProductListData($products, $user_id) {
		return $this->store_cart_payload->generateMustacheProductListData($products, $user_id);
	}


	public function category($category_slug = '') {
		$this->load->library('user_agent');
		$data = $this->store_cart_payload->page_category($category_slug);
		$this->storeapp->view('category', $data);
	}


	public function make_complete() {
		// user_lms_product
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$userdetails = $this->Cart->is_logged();
			if($userdetails['id']) {
				extract($_POST,true);
				$this->db->where(['order_id'=>$order_id,'user_id'=>$userdetails['id']]);
				$q = $this->db->get('user_lms_product');
				if($q->num_rows()==0) {

					$dataInsert = [
						'lms_product'=>json_encode([
							$name=>[
								'duration'=>$duration??0,
								'isWatched'=>$action!=3 ? $action:0
							]
						]),
						'order_id'=>$order_id,
						'user_id'=>$userdetails['id']
					];
					$this->db->insert('user_lms_product',$dataInsert);
				} else {
					$lmsProduct = $q->row_array();
					$lmsProduct = json_decode($lmsProduct['lms_product'],true);
					$lmsProduct[$name] = [
						'duration'=>$duration??$lmsProduct[$name]['duration'],
						'isWatched'=>$action!=3 ? $action:$lmsProduct[$name]['isWatched']
					];
					$lmsProduct = json_encode($lmsProduct);
					$this->db->where(['order_id'=>$order_id,'user_id'=>$userdetails['id']]);
					$this->db->update('user_lms_product',['lms_product'=>$lmsProduct]);
				}
			}
		}
		exit;
	}

	public function continue_last_watch()
	{
		$userdetails = $this->Cart->is_logged();

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userdetails['id']) {

			$where = ['order_id'=>$_POST['order_id'],'user_id'=>$userdetails['id']];

			$this->db->where($where);
			$user_lms_product = $this->db->get('user_lms_product')->row_array();
			
			$lmsProductDetails = json_decode($user_lms_product['lms_product'], true);
			
			foreach($lmsProductDetails as $key => $value) {
				if($_POST['video_id'] == $key) {
					$lmsProductDetails[$key]['isPlaying'] = 1;
				} else {
					$lmsProductDetails[$key]['isPlaying'] = 0;
				}
			}

			$this->db->where($where);
			$this->db->update('user_lms_product',['lms_product'=>json_encode($lmsProductDetails)]);

			echo json_encode($lmsProductDetails);exit;
		}
	}

	public function product_ratting(){

		$json = ['status' => 0, 'message' => '', 'avg_rating' => 0];
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$userdetails = $this->Cart->is_logged();
			if (!$userdetails['id']) {
				$json['message'] = __('store.login_to_rate');
			} elseif (!empty($_POST['product_id']) && !empty($_POST['rate'])) {
				$product_id = (int)$_POST['product_id'];
				$rate = min(5, max(1, (int)$_POST['rate']));
				$where = ['products_id'=>$product_id,'rating_user_id'=>$userdetails['id']];
				$this->load->library('user_agent');
				$details = array(
					'rating_user_id'    =>  !empty($userdetails['id']) ? $userdetails['id'] : 0,
					'products_id'       =>  $product_id,
					'rating_status'     =>  1,
					'rating_number'     =>  $rate,
					'rating_name'       =>  !empty($userdetails['firstname']) ? $userdetails['firstname'] : '',
					'rating_email'      =>  !empty($userdetails['email']) ? $userdetails['email'] : '',
					'rating_comments'   =>  !empty($_POST['comment']) ? $_POST['comment'] : '',
					'rating_referrer'   =>  $this->agent->referrer(),
					'rating_user_agent' =>  $this->agent->agent_string(),
					'rating_os'         =>  $this->agent->platform(),
					'rating_browser'    =>  $this->agent->browser(),
					'rating_isp'        =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
					'rating_ipaddress'  =>  $_SERVER['REMOTE_ADDR'],
					'rating_created_by' =>  !empty($userdetails['id']) ? $userdetails['id'] : 0,
					'rating_created'    =>  date('Y-m-d H:i:s'),
				);
				$this->db->where($where);
				$q = $this->db->get('rating');
				$this->db->reset_query();
				if ( $q->num_rows() > 0 )  {
					$this->db->where($where)->update('rating', $details);
				} else {
					$this->db->set($where)->insert('rating', $details);
				}

				$this->load->model('Product_model');
				$this->Product_model->update_avg_rating($product_id);

				$this->db->select('COALESCE(product_avg_rating, 0) as avg_rating');
				$this->db->from('product');
				$this->db->where('product_id', $product_id);
				$row = $this->db->get()->row();
				$json['status'] = 1;
				$json['avg_rating'] = $row ? (int)$row->avg_rating : $rate;
			}

		}
		header('Content-Type: application/json');
		echo json_encode($json);
		exit();
	}

	public function toggle_wishlist() {
		$userdetails = $this->Cart->is_logged(); 
		if(empty($userdetails)){ 
			header("Location: ".base_url('store/login'));
			die;
		}
		$post = $this->input->post(null,true);
		$login_usr = $this->Cart->is_logged();
		$wlist = [];
		if(isset($login_usr['products_wishlist']) && !empty($login_usr['products_wishlist'])) {
			$wlist = json_decode($login_usr['products_wishlist']);
		}
		if (($key = array_search($post['product_id'], $wlist)) !== false) {
			unset($wlist[$key]);
		} else {
			if(!empty($post['product_id'])) {
				array_push($wlist, $post['product_id']);
			}
		}

		$wlist = array_values($wlist);

		$this->db->set('products_wishlist', json_encode($wlist));
		$this->db->where('id', $login_usr['id']);
		$this->db->update('users');
		$login_usr['products_wishlist'] = json_encode($wlist);
		$client = $this->session->set_userdata('client', $login_usr);
		echo true;		
	}


//This function protects the system for generating actions/clicks from vpn&proxy servers
public static function checkProxyStatus($ip) {
    // Static settings for testing
    $service1Enabled = 0; // Assume Service 1 is enabled
    $apiKeyService1 = 'gyy3K2N1jJpqHD2ZYEy2qUvJukNzBfSTBfdW3VqWIyRldX0moX'; // Hardcoded API key for Service 1
    $urlService1 = 'https://api.isproxyip.com/v1/check.php'; // Hardcoded URL for Service 1
    
    $service2Enabled = 0; // Assume Service 2 is enabled
    $apiKeyService2 = '83f1ce02e1eb4271a68ebaeab843a6a0'; // Hardcoded API key for Service 2
    $urlService2 = 'https://vpnapi.io/api'; // Hardcoded URL for Service 2

    $proxyDetected = false;

    // Check Service 1 if enabled
    if ($service1Enabled) {
        $urlService1 = "{$urlService1}?key={$apiKeyService1}&ip={$ip}&format=json";
        if (self::proxyCheckHelper($urlService1, 'service1')) {
            error_log("Proxy or VPN detected by Service 1 for IP: {$ip}");
            $proxyDetected = true;
        }
    }

    // Check Service 2 if enabled
    if ($service2Enabled) {
        $urlService2 = "{$urlService2}?ip={$ip}&key={$apiKeyService2}";
        if (self::proxyCheckHelper($urlService2, 'service2')) {
            error_log("Proxy or VPN detected by Service 2 for IP: {$ip}");
            $proxyDetected = true;
        }
    }

    return $proxyDetected;
}

private static function proxyCheckHelper($url, $serviceType) {
    // Simulate API responses for testing without making real HTTP requests
    // Assume responses based on the serviceType for manual testing
    $simulatedResponses = [
        'service1' => json_encode([
            'status' => 'success',
            'proxy' => 1  // Simulate a detected proxy
        ]),
        'service2' => json_encode([
            'security' => [
                'proxy' => true,  // Simulate a detected proxy
                'vpn' => false,
                'tor' => false
            ]
        ])
    ];

    // Select the simulated response based on the service type
    $response = $simulatedResponses[$serviceType] ?? false;

    if ($response === false) {
        error_log("Error: No simulated response set for $serviceType.");
        return false;
    }

    // Decode the simulated response
    $data = json_decode($response, true);

    // Check for proxy based on the service type and the simulated data
    if ($serviceType === 'service1' && isset($data['status']) && $data['status'] == 'success' && $data['proxy'] == 1) {
        return true;
    } elseif ($serviceType === 'service2' && isset($data['security']) && ($data['security']['proxy'] || $data['security']['vpn'] || $data['security']['tor'])) {
        return true;
    }

    return false;
}


	public function product($affiliate_id = 0, $product_slug = null) {
		// Handle clean product URLs without affiliate tracking (for SEO)
		if ($product_slug === null) {
			$product_slug = $affiliate_id;
			$affiliate_id = 0;
		} 

		$data = [];
		$this->load->helper('share');
		$this->load->library('user_agent');
 
		$data['user_id'] = $affiliate_id = (int)base64_decode($affiliate_id);
		
		$restricted_vendors = $this->get_restricted_vendors($affiliate_id, $product_slug);


		$site_setting = $this->Product_model->getSettings('site');

		if($affiliate_id > 0 && ! in_array($affiliate_id, $restricted_vendors)){
			if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
				$store_setting = $this->Product_model->getSettings('store');
				$time = ($store_setting['affiliate_cookie'] * 24 * 60 * 60) * 1000;
				$_SESSION['setLocalStorageAffiliateAjax'] = json_encode([$affiliate_id, $time]);
				$this->Cart->setReferId($affiliate_id);
			}

			if(! isset($site_setting['affiliate_tracking_place']) || ($site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2)) {
				$this->Cart->setcookieAffiliate($affiliate_id);
			}
		} else {
			$cookie_user_id = $localstorage_user_id = 0;
			if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
				$localstorage_user_id = $this->session->localStorageAffiliate;
			}

			if(! isset($site_setting['affiliate_tracking_place']) || $site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2) {
				$cookie_user_id = $this->Cart->getcookieAffiliate('affiliate_id');                
			} 

			$data['user_id'] = $affiliate_id = $localstorage_user_id == 1 ? $cookie_user_id : $localstorage_user_id;
		}

		if($affiliate_id > 0 && ! in_array($affiliate_id, $restricted_vendors)){
			$data['user'] = $this->Product_model->getUserDetails($affiliate_id);
			
			if($data['user']['type'] == 'user'){
				$this->Cart->setReferId($affiliate_id);
			}
		}

		$setting = array();
		
		$data['session'] = $this->session->userdata('client') ? $this->session->userdata('client') : '';
		
		if($product_slug){
			
			$seller = false;
			
			if(isset($_GET['preview'])){
				$u = $this->session->userdata('user');
				$admin = $this->session->userdata('administrator');

				if(!$u && !isset($admin['id'])){ 
					header("Location: ".base_url('store/login'));
					die;
				}

				$data['product'] = $this->db->query("SELECT * FROM product WHERE product_slug like '". $product_slug ."' ")->row_array();

				$seller = $this->db->query("SELECT pa.*, u.is_vendor FROM product_affiliate AS pa
					LEFT JOIN users as u on (pa.user_id = u.id) 
					WHERE pa.product_id=". (int)$data['product']['product_id'] ." 
					")->row_array();

				if($seller['user_id'] != $u['id'] && !isset($admin['id'])){ 
					header("Location: ".base_url('store/login'));
					die;
				}


			} else {
				 
				$data['product'] = $this->db->query("
					SELECT *, CONCAT(users.firstname, ' ', users.lastname) AS product_created_by_name FROM product 
					LEFT JOIN users  on users.id = product.product_created_by 
					WHERE on_store = 1 
					AND product_status = 1 
					AND product_slug LIKE '". $product_slug ."'")->row_array();
				
			}


			if(!$data['product']){
				header("Location: ".base_url('store'));
				die;
			} else {

				$data['product_slug'] = $product_slug;

				if(!$seller){
					$seller = $this->db->query("SELECT pa.*, u.is_vendor FROM product_affiliate AS pa
						LEFT JOIN users as u on (pa.user_id = u.id) 
						WHERE pa.product_id=". (int)$data['product']['product_id'] ." 
						")->row_array();
				}

				if($seller && (!isset($seller['is_vendor']) || !$seller['is_vendor']) ){
					
				header("Location: ".base_url('store'));
					die;
				}

				 
				if(isset($seller['user_id']) && in_array($seller['user_id'], $restricted_vendors)) 
				{
					header("Location: ".base_url('store'));
					die;
				}

				if (!empty($data['product']['is_campaign_product']) && (int) $data['user_id'] > 0) {
					if (!$this->Product_model->user_can_promote_market_campaign_for_user_id((int) $data['user_id'], $data['product'])) {
						header("Location: ".base_url('store'));
						die;
					}
				}

				$data['product']['seller'] = $seller;

			$data['categories'] =$this->Product_model->getProductCategory($data['product']['product_id']);
			$data['category_tree'] = $this->Product_model->getCategoryTree();
			$data['category'] = [];

				$data['meta_image'] = base_url('assets/images/product/upload/thumb/'.$data['product']['product_featured_image']);
				$data['add_tocart_url'] = $this->Cart->getStoreUrl('add_to_cart');
				$data['add_coupon_url'] = $this->Cart->getStoreUrl('add_coupon');
				$data['meta_title'] = $data['product']['product_name'];
				$data['meta_description'] = $data['product']['product_short_description'];
				$this->load->library("socialshare");				
				$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

				$data['product_slug'] = $product_slug;
				
				if(!isset($_GET['preview'])){

					if(isset($_COOKIE))
						$session_id = $_COOKIE['product_view_ck'];
					else
						$session_id=null;
					$Affiliate_Hook = new Affiliate_Hook; 
 
					if(!isset($session_id))
					{
						$session_id = uniqid().rand();
						$time = (60 * 60);
						setcookie("product_view_ck", $session_id, time()+$time, "/" ); 
					}
 					

					// Get the user's IP address at the start
	    			$ip = $this->input->ip_address();

	    			//debug('click done here'); exit;

// Check if the IP is from a proxy
if ($this->checkProxyStatus($userIp)) {
	require_once APPPATH . 'views/errors/html/error_proxy.php';
    exit;
}



					$viewData = array(
			            'user_id'  => (int)$data['user_id'],
			            'product_id'  => (int)$data['product']['product_id'],
			            'link' => '', 
			            'ip'      =>  $ip,
			            'session_id'      =>  $session_id, 
			            'created_at'      =>  date('Y-m-d H:m:s'),

			        );

					$viewcheck = spam_protect_and_log($viewData, 'product');

					if($viewcheck!=2)
					{

						$this->db->set('view_statistics', 'view_statistics+1', FALSE);
						$this->db->where('product_id', $data['product']['product_id']);
						$this->db->update('product');
					}  

					$this->db->set('view', 'view+1', FALSE);
					$this->db->where('product_id', $data['product']['product_id']);
					$this->db->update('product');
				}
				
				$data['setting'] 	= $this->Product_model->getSettings('paymentsetting');
				$data['ratings'] = $this->Product_model->getReview($data['product']['product_id']);
				$this->db->select('avg(rating.rating_number) as avg_rating');
				$this->db->order_by('rating_created', 'desc');
				$this->db->from('rating');
				$this->db->where('products_id', $data['product']['product_id']);
				$this->db->join('product', 'product.product_id = rating.products_id');
				$data['avg_rating'] = $this->db->get()->row_array()['avg_rating'];

				$data['allowReview'] = false;
				$data['login_usr'] = $this->Cart->is_logged();
				$t= $this->Cart->has_purchase($data['product']['product_id'],1);
				if($data['login_usr'] &&  $t->total  > 0){
					$data['allowReview'] = true;
					$data['order_id'] = $t->order_id;
				}

				$client_id = 0;
				
				if($this->session->userdata('client') != false) {
					$client_id = $this->session->userdata('client')['id'];
				}

				$isVendor = $data['user']['is_vendor'] == 1;

 

				if (
					$this->allowCommissionFromVendorPanelMode($data['product']['product_created_by'], $isVendor) &&
					$this->session->userdata('administrator') == false && 
					$this->session->userdata('user') == false && 
					$affiliate_id && 
					$client_id != $affiliate_id &&
					$data['user']['type'] == 'user' &&
					! in_array($affiliate_id, $restricted_vendors)
				) {
  
					$Affiliate_Hook = new Affiliate_Hook;

					$is_suspicious_click = $Affiliate_Hook->is_suspicious_click($affiliate_id);
					$is_localhost_click = $Affiliate_Hook->is_localhost();

					//Frequency Exceeded
					$this->market_ClickFrequencyExceeded($ip);
										 
					if(!$is_suspicious_click && !$is_localhost_click['shouldBlock']) {
						$match = $this->Product_model->getProductAction(
							$data['product']['product_id'],
							$affiliate_id
						);

						if ($match == 0){
							$this->Product_model->setClicks($data['product']['product_id'],$affiliate_id);
						} else {
							$this->Product_model->getProductActionIncrese($data['product']['product_id'], $affiliate_id);
						}
						
						$wallet_group_id = time().rand(10,100);
 						 
 						$data['product']['product_slug']=$product_slug;
						$transaction_id = $this->Product_model->giveClickCommition($data['product'], $affiliate_id, 0, $wallet_group_id);

						$vendor_setting = $this->Product_model->getSettings('vendor');
						

						if((int)$transaction_id > 0) {
							if ($vendor_setting['admin_click_status'] == 1) {
							
								$this->Product_model->giveAdminClickCommition($data['product'], $wallet_group_id);
							}
							

							$this->Product_model->referClick($data['product'], $affiliate_id, 0, $wallet_group_id);
							
						} 
					} 
				}
				
				$wlist = [];
				
				if(isset($data['login_usr']['products_wishlist']) && !empty($data['login_usr']['products_wishlist'])) {
					$wlist = json_decode($data['login_usr']['products_wishlist']);
				}
				
				if (($key = array_search($data['product']['product_id'], $wlist)) !== false) {
					$data['is_wishlisted_class'] = 'w-listed';
				} else {
					$data['is_wishlisted_class'] = '';
				}
 
				$data['settings'] = $this->Product_model->getSettings('store');

			$vendormanagereviewimage=$this->db->query("SELECT * FROM setting WHERE  setting_key='vendormanagereviewimage' and setting_type='market_vendor'")->row();
			$data['vendormanagereviewimage'] = isset($vendormanagereviewimage) && $vendormanagereviewimage->setting_value==1 ? 1 : 0;

			$data['all_images'] = $this->Product_model->getAllImages($data['product']['product_id']);
			$data['all_videos'] = $this->Product_model->getAllVideos($data['product']['product_id']);
			$review_agg = $this->db->select('AVG(rating_number) as avg_star, COUNT(*) as cnt')
				->where('products_id', $data['product']['product_id'])
				->where('rating_status', 1)
				->get('rating')->row();
			$data['review_count'] = ($review_agg && $review_agg->cnt > 0) ? (int)$review_agg->cnt : 0;
			if ($review_agg && $review_agg->cnt > 0) {
				$data['avg_rating'] = round($review_agg->avg_star, 1);
			}
			$data['review_list'] = $this->db
				->where('products_id', $data['product']['product_id'])
				->where('rating_status', 1)
				->order_by('rating_created', 'DESC')
				->get('rating')->result_array();
			
			$this->storeapp->view("product",$data);
			}
		}
	}

	public function market_ClickFrequencyExceeded($ip) {

	    // Check if the click limit is enabled
	    $clickLimitEnabledSetting = $this->db->get_where('setting', [
	        'setting_key' => 'enable_max_clicks',
	        'setting_type' => 'site'
	    ])->row();

	    // If click limit is not enabled, return false immediately
	    if (empty($clickLimitEnabledSetting) || $clickLimitEnabledSetting->setting_value != '1') {
	        return false; // Click limit check is disabled
	    }

	    // Get the Max Clicks and Frequency Unit from the settings
	    $maxClicksSetting = $this->db->get_where('setting', array(
	        'setting_key' => 'max_clicks',
	        'setting_type' => 'site'
	    ))->row();

	    $frequencyUnitSetting = $this->db->get_where('setting', array(
	        'setting_key' => 'frequency_unit',
	        'setting_type' => 'site'
	    ))->row();

	    // Initialize defaults if settings are not found
	    $maxClicks = $maxClicksSetting ? (int)$maxClicksSetting->setting_value : 3;
	    $frequencyUnit = $frequencyUnitSetting ? $frequencyUnitSetting->setting_value : 'minute';

	    // Create the date range based on the frequency unit
	    $currentDateTime = new DateTime();
	    $pastDateTime = clone $currentDateTime;

	    // Modify the pastDateTime to be one frequency unit before the current time
	    $pastDateTime->modify("-1 {$frequencyUnit}");



	    // Count the number of clicks from this IP in the given time frame
	    $this->db->where('user_ip', $ip);
	    $this->db->where('created_at >=', $pastDateTime->format('Y-m-d H:i:s'));
	    $this->db->where('created_at <=', $currentDateTime->format('Y-m-d H:i:s'));
	    $clickCount = $this->db->count_all_results('product_action');

	    // If the click frequency is exceeded, echo the message and stop script execution
	    if ($clickCount >= $maxClicks) {
	        echo 'Click limit exceeded. Please try again later.';
	        exit;  // This stops the script execution
	    }

	    // If the click frequency is not exceeded, simply return true
	    return true;
	}

	public function insertproductlogs($postData = null){
		if(!empty($postData)){
			$data['custom'] = $this->Product_model->create_data('payment_log', $postData);
		}
	}

	public function about(){
		$language_id=$this->Common_model->getDefaultLanaguage();
		if (isset($this->session) && $this->session->userdata('userLang') !== FALSE)
			$language_id=$this->session->userdata('userLang');

		$data['settings'] = $this->Product_model->getSettings('store');
		$data['content'] = $this->Product_model->getSettingsWithLanaguage('store',$language_id);
		$data['category_tree'] = $this->Product_model->getCategoryTree();
		$data['category'] = [];
		$this->storeapp->view("about", $data);
	}

	public function cart(){
		$post = $this->input->post(null,true);
		$get = $this->input->get(null,true);

		if (isset($get['remove'])){
			$this->Cart->remove($get['remove']);
			$this->session->set_flashdata('error', __('store.product_remove_successfully'));
			if(isset($get['checkout_page'])){ echo json_encode(array("success"=>true));die; }
			redirect($this->Cart->getStoreUrl('cart'));
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$quantity = $this->input->post('quantity', true);
			$json = [];

			foreach ($quantity as $cart_id => $requested_qty) {
				$requested_qty = (int) $requested_qty;
				if ($requested_qty < 1) $requested_qty = 1;

				$cart_detail = $this->db->get_where('cart', ['cart_id' => $cart_id])->row_array();
				if (empty($cart_detail)) continue;

				$product = $this->db->get_where('product', ['product_id' => $cart_detail['product_id']])->row_array();

				// -1 means unlimited stock — skip the check entirely
				if (!empty($product) && (int)$product['product_quantity'] !== -1 && $requested_qty > (int)$product['product_quantity']) {
					$current_in_cart = isset($cart_detail['quantity']) ? (int) $cart_detail['quantity'] : 0;
					$available_to_add = max(0, (int) $product['product_quantity'] - $current_in_cart);
					$json['error'] = __('store.stock_levels_too_low_to_meet_your_requested_quantity', ['quantity' => $available_to_add]);
				} else {
					$this->Cart->update($cart_id, $requested_qty);
				}
			}

	        
			if(isset($post['checkout_page'])){
				if(empty($json)){
					echo json_encode(array("success"=>true));
					die; 
				}else{
					echo json_encode(array("success"=>false,'message'=> $json['error']));
					die; 
				}
				
			} 
		}

		$data = $this->store_cart_payload->page_cart();

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$store_settings = $this->Product_model->getSettings('store');
			$theme = isset($store_settings['theme']) && $store_settings['theme'] !== '' && $store_settings['theme'] !== '0'
				? $store_settings['theme'] : 'default';
			$cart_table_view = 'store/' . $theme . '/cart_products_table';
			if (!file_exists(APPPATH . 'views/' . $cart_table_view . '.php')) {
				$cart_table_view = 'store/default/cart_products_table';
			}
			$html = $this->load->view($cart_table_view, $data, true);
			$response = [
				'success' => empty($json),
				'html'    => $html
			];
			if (!empty($json['error'])) {
				$response['message'] = $json['error'];
			}
			echo json_encode($response);
			die;
		} else {
			$this->storeapp->view("cart",$data);
		}
	}

	public function getState(){
		$data['states'] = array();
		$post = $this->input->post(null,true);
		if($post['id']){
			$data['states'] = $this->db->query('SELECT name,id FROM states WHERE country_id = '. $post['id'])->result();
		}

		if(isset($post['checkShipping']))
			$this->session->set_userdata('shipping_country', (int)$post['id']);

		echo json_encode($data);
	}

	public function contact(){
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$post = $this->input->post(null,true);

			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'Phone', 'required');
			$this->form_validation->set_rules('message', 'Message', 'required');
			$this->form_validation->set_rules('terms', 'Terms', 'required');
			if ($this->form_validation->run()){
				$this->load->model('Mail_model');
				$this->load->model('Product_model');
				$this->Mail_model->send_store_contact_mail($post);
				redirect($_SERVER['HTTP_REFERER']);
			}
		}

		$language_id=$this->Common_model->getDefaultLanaguage();
		if (isset($this->session) && $this->session->userdata('userLang') !== FALSE)
			$language_id=$this->session->userdata('userLang');

		$data['content'] = $this->Product_model->getSettingsWithLanaguage('store',$language_id);
		$data['storesettings'] = $data['settings'] = $this->Product_model->getSettings('store');
		$data['category_tree'] = $this->Product_model->getCategoryTree();
		$data['category'] = [];

		$this->storeapp->view("contact", $data);
	}

	public function vendor_contact(){
		$result['status'] = 0;
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$post = $this->input->post(null,true);

			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', __('store.vendor_store_contact_form_name'), 'required');
			$this->form_validation->set_rules('email', __('store.vendor_store_contact_form_email'), 'required|valid_email');
			$this->form_validation->set_rules('phone', __('store.vendor_store_contact_form_phone'), 'required');
			$this->form_validation->set_rules('message', __('store.vendor_store_contact_form_message'), 'required');
			$this->form_validation->set_rules('terms', __('store.vendor_store_contact_form_terms'), 'required');
			$this->form_validation->set_rules('vendoremail', __('store.vendor_store_contact_form_vendor_email'), 'required');

			if($this->form_validation->run()){

				$mailBody = '<table width="100%">';
				$mailBody .= '<tr><td>'.__('store.vendor_store_contact_form_name').'</td><td>'.$post['name'].'</td></tr>';
				$mailBody .= '<tr><td>'.__('store.vendor_store_contact_form_email').'</td><td>'.$post['email'].'</td></tr>';
				$mailBody .= '<tr><td>'.__('store.vendor_store_contact_form_phone').'</td><td>'.$post['phone'].'</td></tr>';
				$mailBody .= '<tr><td>'.__('store.vendor_store_contact_form_message').'</td><td>'.$post['message'].'</td></tr>';
				$mailBody .= '</table>';

				$this->load->model('Mail_model');
				$send = $this->Mail_model->send_store_vendor_contact_mail($post['vendoremail'], $mailBody,$post['vendor'],$post['message']);
				if($send){
					$result['status'] = 1;
					$result['message'] = __('store.vendor_contact_message_send');
				} else {
					$result['message'] = __('store.vendor_contact_message_not_send');
				}
			} else {
				$result['validation'] = $this->form_validation->error_array();
			}
		}

		echo json_encode($result);
		die;
	}

	public function policy(){
		$language_id=$this->Common_model->getDefaultLanaguage();
		if (isset($this->session) && $this->session->userdata('userLang') !== FALSE)
			$language_id=$this->session->userdata('userLang');

		$data['content'] = $this->Product_model->getSettingsWithLanaguage('store',$language_id);
		$data['category_tree'] = $this->Product_model->getCategoryTree();
		$data['category'] = [];
		$this->storeapp->view("policy",$data);
	}

	public function checkout() {
		$payload = $this->store_cart_payload->page_checkout();
		if (!empty($payload['_error'])) {
			redirect($this->Cart->getStoreUrl('cart'));
			return;
		}
		$data = $payload;

			// Detect page refresh after confirm_order — restore confirm/payment step
			$data['confirm_html'] = '';
			$uncompleted_id = $this->session->userdata('uncompleted_id');
			if ($uncompleted_id) {
				$uncompletedData = $this->Product_model->getByField('uncompleted_payment', 'id', $uncompleted_id);
				if (!empty($uncompletedData) && empty($uncompletedData['completed_id'])) {
					$paymentGateway  = $this->session->userdata('payment_gateway');
					$paymentGateways = $this->session->userdata('payment_gateways');
					if ($paymentGateway && !empty($paymentGateways) && isset($paymentGateways[$paymentGateway])) {
						try {
							$content  = explode(' || ', $uncompletedData['content']);
							$order    = unserialize($content[0]);
							$products = unserialize($content[1]);
							$resumeUser = $this->Cart->is_logged();
							if (!$resumeUser && !empty($uncompletedData['user_id'])) {
								$resumeUser = $this->db->get_where('users', ['id' => $uncompletedData['user_id']])->row_array();
							}
							// Always fetch fresh settings from DB
							$settingData         = $this->Product_model->getSettings('payment_gateway_' . $paymentGateway);
							$settingData['title'] = isset($paymentGateways[$paymentGateway]['title']) ? $paymentGateways[$paymentGateway]['title'] : $object->title;
							$settingData['icon']  = isset($paymentGateways[$paymentGateway]['icon'])  ? $paymentGateways[$paymentGateway]['icon']  : $object->icon;
							$settingData['name']  = $paymentGateway;
							$stateRow            = $this->Product_model->getByField('states', 'id', $order['state_id'] ?? 0);
							$order['state_name'] = $stateRow['name'] ?? '';
							require_once APPPATH . '/payment_gateway/module/store/view.php';
							$gatewayData = prepareDataForView($paymentGateway, $uncompleted_id, $resumeUser, $order, $products);
							$orderCommentSetting = $this->Product_model->getSettings('order_comment');
							$orderCommentSetting['title'] = json_decode($orderCommentSetting['title'], true);
							$confirmData = $data;
							$confirmData['order_comment_setting'] = $orderCommentSetting;
							$confirm_html = $this->storeapp->view("checkout_confirm", $confirmData, true, true);
							require_once APPPATH . '/payment_gateway/controllers/' . $paymentGateway . '.php';
							$object = new $paymentGateway($this);
							ob_start();
							$object->getPaymentGatewayView($settingData, $gatewayData);
							$confirm_html .= ob_get_clean();
							$data['confirm_html'] = $confirm_html;
						} catch (Exception $e) {
							// If rebuild fails, just show normal checkout
						}
					}
				}
			}

		// V14: Check checkout mode (one-page vs multi-step)
		$checkout_mode = $data['settings']['checkout_mode'] ?? 'multi-step';
		if ($checkout_mode === 'one-page') {
			// One-page: treat non-logged as guest so checkout_shipping shows guest fields
			if (!$data['is_logged']) {
				$_SESSION['guestFlow'] = 1;
			}
			$data['is_guest_flow'] = isset($_SESSION['guestFlow']);
			$this->storeapp->view("checkout_onepage", $data);
		} else {
			$data['is_guest_flow'] = isset($_SESSION['guestFlow']);
			$this->storeapp->view("checkout", $data);
		}
	}

	public function add_to_cart() {
	    $product_id = $this->input->post("product_id", true);
	    $product = $this->db->get_where('product', ['product_id' => $product_id])->row_array();
	    $json = [];

		if ($product) {
		    if ($product['product_quantity'] > 0 || $product['product_quantity'] == -1) {
		        $requested_quantity = max($this->input->post("quantity", true), 1);
		        $current_cart_quantity = 0;

		        $cart_products = $this->Cart->getProducts();
		        foreach ($cart_products as $cart_product) {
		            if ($cart_product['product_id'] == $product_id) {
		                $current_cart_quantity += $cart_product['quantity'];
		            }
		        }

		        if ($product['product_quantity'] == -1 || $requested_quantity + $current_cart_quantity <= $product['product_quantity']) {
		            $variation = json_encode($this->input->post("variation", true));
		            $variation = str_replace('\\', '\\\\', $variation);

		            $json['data'] = $this->Cart->add($product_id, $requested_quantity, $variation, $this->Cart->getReferId(), $product);
		            $json['location'] = $this->Cart->getStoreUrl('cart');
		        } else {
		            $available_to_add = $product['product_quantity'] - $current_cart_quantity;
		            $this->session->set_flashdata('error', __('store.stock_levels_too_low_to_meet_your_requested_quantity', ['quantity' => $available_to_add]));
		            $json['error'] = __('store.stock_levels_too_low_to_meet_your_requested_quantity', ['quantity' => $available_to_add]);
		        }
		    } else {
		        $this->session->set_flashdata('error', __('store.out_of_stock'));
		        $json['error'] = __('store.out_of_stock');
		    }
		} else {
		    $this->session->set_flashdata('error', __('store.product_not_found'));
		    $json['error'] = __('store.product_not_found');
		}
		
	    echo json_encode($json);
	}

	public function checkoutCart(){
		$data['products'] = $this->Cart->getProducts();
		$data['is_logged'] = $this->Cart->is_logged();
		if($data['products']){
			$data['base_url'] = $this->Cart->getStoreUrl();
			$data['cart_url'] = $this->Cart->getStoreUrl('cart');
			
			// Get country ID for shipping calculation
			$country_id = null;
			if($this->session->userdata('shipping_country')) {
				$country_id = $this->session->userdata('shipping_country');
			} elseif($data['is_logged']) {
				// Get from user's shipping address
				$shipping_address = $this->db->query("SELECT country_id FROM shipping_address WHERE user_id = " . $data['is_logged']['id'])->row();
				if($shipping_address) {
					$country_id = $shipping_address->country_id;
				}
			}
			
			// Session is automatically handled by CodeIgniter
			
		$data['totals'] = $this->Cart->getTotals($country_id);
		$data['allow_shipping'] = $this->Cart->allow_shipping;
		$data['applied_coupon'] = $this->session->userdata('coupon');
		
		}
		$this->storeapp->view("checkout_cart",$data,true);
	}

	public function checkout_shipping($country_id = null) {
		$data = $this->store_cart_payload->page_checkout_shipping($country_id);
		$this->storeapp->view('checkout_shipping', $data, true);
	}

	public function checkout_confirm() {
		/* This page is a checkout-flow partial (rendered inline by checkout.php via AJAX).
		   Direct browser access only makes sense when a payment session is already in
		   progress — otherwise send the user to the checkout start page. */
		$uncompleted_id = $this->session->userdata('uncompleted_id');
		if (empty($uncompleted_id)) {
			redirect($this->Cart->getStoreUrl('checkout'));
			return;
		}
		$data = $this->store_cart_payload->page_checkout_confirm();
		$this->storeapp->view('checkout_confirm', $data, true);
	}

	public function add_coupon() {
		$coupon_code = $this->input->post("coupon_code",true);
		$product_id = $this->input->post("product_id",true);
		$this->load->model("Coupon_model");
		$json = array();
		$coupon = $this->Coupon_model->getByCode($coupon_code);
		if($coupon){
			if($coupon['allow_for'] == "S"){
				$product_ids = explode(",", $coupon['products']);
				if (!in_array($product_id, $product_ids)) {
					$json['error'] = "Invalid Coupon Code";
				}
			}
			$logged_user = $this->Cart->is_logged();
			if ($logged_user) {
				$total_use = $this->Coupon_model->getUses($logged_user['id'],$coupon_code);

				if($total_use >= $coupon['uses_total']){
					$json['error'] = "Coupon is expired or reached its usage limit!";
				}
			}

			// check Product owner and vendor are same or not  
			if($coupon['vendor_id']!=0){
				$this->load->model('Product_model');
				$product_details = $this->Product_model->getProductById($product_id);
				if($product_details->product_created_by!=$coupon['vendor_id']) {
					$json['error'] = "Invalid Coupon Code";
				}
			}
			
			if (!isset($json['error'])) {
				$json['success'] = 'Coupon Code Apply Successfully.!';
				$this->Cart->addCoupon($product_id,$coupon);
			}
		} else {
			$json['error'] = "Invalid Coupon Code";
		}
		echo json_encode($json);
	}

	public function clear_coupon() {
		$this->session->unset_userdata('coupon');
		$this->Cart->reloadCart();
		echo json_encode(['success' => true]);
	}

public function ajax_login(){
	$this->load->model('user_model', 'user');
	$this->load->model("Product_model");

	$username = $this->input->post('username');
	$password = $this->input->post('password');	
	$json['errors'] = array();
	$post = $this->input->post(null,true);

	$googlerecaptcha = $this->Product_model->getSettings('googlerecaptcha');

	if (!empty($googlerecaptcha['client_login'])) {
	    // Support both v2 and v3 inputs
	    $recaptcha_response = $post['captch_response'] ?? $post['g-recaptcha-response'] ?? '';

	    if (empty($recaptcha_response)) {
	        $json['errors']['captch_response'] = 'Captcha is required.';
	    } else {
	        $recaptcha_result = validate_recaptcha(
	            $recaptcha_response,
	            $googlerecaptcha['secretkey'],
	            'client_login', // action for v3
	            0.5,            // min score for v3
	            isset($googlerecaptcha['version']) && $googlerecaptcha['version'] === 'v3' // is v3?
	        );

	        if (!$recaptcha_result['success']) {
	            $json['errors']['captch_response'] = $recaptcha_result['error'];
	        }
	    }
	}

	if(count($json['errors']) == 0){
		$user_details_array = $this->user->login($username);
		if(!empty($user_details_array['username']) && sha1($password)==$user_details_array['password']){
			if($user_details_array['type'] == 'client' || $user_details_array['type'] == 'user'){
				$this->user->update_user_login($user_details_array['id']);

				if($user_details_array['type'] == 'user' && $user_details_array['reg_approved'] == 1) {
					$this->session->set_userdata(array('client'=>$user_details_array));
					$json['success'] = true;
				} else if($user_details_array['type'] == 'client'){
					$this->session->set_userdata(array('client'=>$user_details_array));
					$json['success'] = true;
				} else {
					$json['errors']['password'] = __('user.registration_approval_pending');
					echo json_encode($json); exit;
				}
				
				if($user_details_array['type'] == 'user') {
					$this->session->set_userdata(array('user'=>$user_details_array));
					$this->Cart->setcookieAffiliate('');
					$this->Cart->updateCartRefereId($user_details_array['id']);
				}
				
				$this->Cart->syncCart();
			}
		}

		if(!isset($json['success'])){
			$json['errors']['password'] = __('user.invalid_credentials');
		}
	}

	echo json_encode($json);
}


	public function confirm_order(){

		$data = $this->input->post(null,true);
		
		$this->load->library('form_validation');
		$this->Cart->reloadCart();
 
		$totals = $this->Cart->getTotals();
		$check_total_for_skip_payment=1;
		foreach ($totals as $key => $value) 
		{  
		 	$check_total_for_skip_payment=$value['amount']; 
		}		 
		 
		if($check_total_for_skip_payment>0)
		$this->form_validation->set_rules('payment_gateway', 'Payment Gateway', 'required|trim');
		$this->form_validation->set_rules('agree', 'Agree', 'required|trim');


		$allow_shipping = $this->Cart->allow_shipping;

		if($allow_shipping=="")
		{
			$cart_product = $this->Cart->getProducts();
			

			$allow_shipping = false;
			foreach ($cart_product as $key => $value) 
			{
				if($value['product_type'] != 'downloadable' && $value['allow_shipping']==1) 
				$allow_shipping =true;
			}	
		}
  
		if($allow_shipping){
			$this->form_validation->set_rules('address', 'Address', 'required|trim');
			$this->form_validation->set_rules('country', 'Country', 'required|trim');
			$this->form_validation->set_rules('state', 'State', 'required|trim');
			$this->form_validation->set_rules('city', 'City', 'required|trim');
			$this->form_validation->set_rules('zip_code', 'Postal Code', 'required|trim');
			$this->form_validation->set_rules('phone', 'Phone Number', 'required|trim');
		}

		if($data['classified_checkout'] == 1) {
			$this->form_validation->set_rules('firstname', 'FirstName', 'required|trim');
			$this->form_validation->set_rules('lastname', 'LastName', 'required|trim');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		}

		$json = [];

		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();
			if(isset($json['errors']['agree']) && !isset($data['classified_checkout']) && $data['classified_checkout'] != 1){
				$json['error'] = $json['errors']['agree'];
				unset($json['errors']['agree']);
			}

			if(isset($json['errors']['payment_gateway'])){
				$json['error'] = $json['errors']['payment_gateway'];
				unset($json['errors']['payment_gateway']);
			}

			if(isset($data['comment']) && is_array($data['comment'])){
				foreach ($data['comment'] as $key => $value) {
					if(empty($value['comment'])){
						$json['errors']['comment'][$key] = __('store.comment_can_not_be_blank');
					}
				}
			}

			echo json_encode($json);
			die();
		}

		if(isset($data['classified_checkout']) && $data['classified_checkout'] == 1) {
			$user = $this->db->get_where('users', array('email' => $data['email']))->row_array();


			if(isset($_SESSION['guestFlow'])) {
				$_SESSION['guestFlowClassified'] = 1;
			} else {
				$store_setting = $this->Product_model->getSettings('store', 'store_mode');
				$_SESSION['guestFlowClassified'] = $store_setting['store_mode'] == 'sales' ? 1 : 0;
			}

			if(empty($user)) 
			{
				$geo = $this->ip_info();
				
				$userData = array(
					'firstname'                 => $data['firstname'],
					'lastname'                  => $data['lastname'],
					'email'                     => $data['email'],
					'type'                      => 'guest',
					'uzip'                   	=> $data['zip_code'],
					'ucity'                     => (string)$geo['city'],
					'phone'                     => $data['PhoneNumberInput'],
					'PhoneNumber'               => $data['PhoneNumberInput'],
					'ucity'                     => $data['city'],
					'ucountry'                  => $data['country'],
					'state'                     => $data['state'],
					'City'						=> $geo['city'],
					'Country'					=> $geo['id'],
					'StateProvince'				=> $geo['state'],
					'Zip'						=> $data['zip_code'],
					'created_at'                => date("Y-m-d H:i:s"),
					'updated_at'                => date("Y-m-d H:i:s"),
				);

				$this->db->insert('users', $userData);

				$user = $this->db->get_where('users', array('id' => $this->db->insert_id()))->row_array();
			}

			$_SESSION['guest_user'] = $user;
		} else {
			$json['user'] = $user = $this->Cart->is_logged();
		}

		if(!empty($user) && !isset($json['errors']) && !isset($json['error'])){
			$_SESSION['guest'] = base64_encode($user['id']);

			if(!empty($_FILES['downloadable_file'])){
				$files = $_FILES['downloadable_file'];
				$count_file = count($_FILES['downloadable_file']['name']);
				$this->load->helper('string');

				for($i=0; $i<$count_file; $i++){
					$FILES['downloadable_files']['name'] = md5(random_string('alnum', 10));
					$FILES['downloadable_files']['type'] = $files['type'][$i];
					$FILES['downloadable_files']['tmp_name'] = $files['tmp_name'][$i];
					$FILES['downloadable_files']['error'] = $files['error'][$i];
					$FILES['downloadable_files']['size'] = $files['size'][$i];    

					$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
					if(in_array($extension, array('jpeg','jpg','pdf','gif','doc','docx','png','zip','tar'))){
						move_uploaded_file($FILES['downloadable_files']['tmp_name'], APPPATH.'/downloads_order/'. $FILES['downloadable_files']['name']);

						$downloadable_files[] = array(
							'type' => $FILES['downloadable_files']['type'],
							'name' => $FILES['downloadable_files']['name'],
							'mask' => $files['name'][$i],
						);
					}else{
						$json['error'] = "File type {$extension} not allow";
					}
				}
			}

			if(isset($data['comment']) && is_array($data['comment'])){
				foreach ($data['comment'] as $key => $value) {
					if(empty($value['comment'])){
						$json['errors']['comment'][$key] = __('store.comment_can_not_be_blank');
					}
				}
			}

			if((int)$data['country'] > 0){

				$country = $this->Product_model->getShippingCountry();

				if(is_array($country)){
					if(!isset($country[(int)$data['country']])){
						$shipping_setting = $this->Product_model->getSettings('shipping_setting');
						$json['error'] =  $shipping_setting['shipping_error_message'];
					}
				}
			}

			if ($data['payment_gateway'] == 'paystack' && !in_array($this->session->userdata('userCurrency'), ['GHS', 'NGN', 'USD', 'ZAR', 'KES'])) {
			    $json['error'] = __('store.paystack_accept_only_currency');
			}

			if ($data['payment_gateway'] == 'xendit' && 
			    !in_array($this->session->userdata('userCurrency'), ['IDR', 'PHP', 'USD', 'VND', 'THB', 'MYR', 'SGD'])) {
			    $json['error'] = __('store.xendit_accept_only_currency');
			}

			if($data['payment_gateway'] == 'yookassa' && $this->session->userdata('userCurrency') != 'RUB'){
				$json['error'] = __('store.yookassa_accept_only_currency');
			}


			if(!isset($json['errors']) && !isset($json['error'])){

				$this->load->model('Product_model');
				$cart_product = $this->Cart->getProducts();

				$is_form = (isset($data['is_form']) && $data['is_form']) ? 1 : 0;

				if($allow_shipping){
					$check = $this->db->query("SELECT id FROM shipping_address WHERE user_id =  ". $user['id'])->row();

					$shipping = array(
						'user_id'    => $user['id'],
						'address'    => $data['address'],
						'country_id' => (int)$data['country'],
						'state_id'   => (int)$data['state'],
						'city'       => $data['city'],
						'zip_code'   => $data['zip_code'],
						'phone'      => $data['PhoneNumberInput'],
					);
					if(empty($user['ucountry'])) {
						$userdetailsinfo=[
							'ucountry' => (int)$data['country'],
							'state'   => (int)$data['state'],
							'ucity'  => $data['city'],
							'uzip'   => $data['zip_code'],
						];
						$this->db->update("users",$userdetailsinfo,['id' => $user['id']]);
					}
					if($check){
						$this->db->update("shipping_address",$shipping,['id' => $check->id]);
					}else{
						$this->db->insert("shipping_address",$shipping);
					}
					
					$this->db->update("users", ['phone' => $data['PhoneNumberInput']], ['id' => $user['id']]);
					$_SESSION['client']['phone'] = $data['PhoneNumberInput'];
				} else {
					// Save phone even when shipping is not allowed
					if(isset($data['PhoneNumberInput']) && !empty($data['PhoneNumberInput'])) {
						$this->db->update("users", ['phone' => $data['PhoneNumberInput']], ['id' => $user['id']]);
						$_SESSION['client']['phone'] = $data['PhoneNumberInput'];
					}
				}

				$ipInformatiom = $this->Product_model->ip_info();

				$discount = 0;
				
				if($is_form){
					if($this->session->userdata('form_coupon_discount')){
						$discount = $this->session->userdata('form_coupon_discount');
					}
					$sub_total =  $this->Cart->finalTotal();
				} else {
					$sub_total =  $this->Cart->subTotal();
				}



				$order = array(
					'user_id'         => isset($user['id']) ? $user['id'] : '',
					'address'         => isset($data['address']) ? $data['address'] : '',
					'country_id'      => isset($data['country']) ? (int)$data['country'] : 0,
					'state_id'        => isset($data['state']) ? (int)$data['state'] : 0,
					'city'            => isset($data['city']) ? $data['city'] : '',
					'zip_code'        => isset($data['zip_code']) ? $data['zip_code'] : '',
					'phone'           => isset($data['PhoneNumberInput']) ? $data['PhoneNumberInput'] : '',
					'allow_shipping'  => $allow_shipping,
					'coupon_discount' => $discount,
					'shipping_cost'   => 0,
					'actual_shipping_cost' => 0,
					'tax_cost'   	  => 0,
					'total'           => $sub_total,
					'total_commition' => 0,
					'shipping_charge' => 0,
					'currency_code'   => $_SESSION['userCurrency'],
					'created_at'      => date("Y-m-d H:i:s"),
					'ip'              => @$ipInformatiom['ip'],
					'country_code'    => @$ipInformatiom['country_code'],
					'files'           => isset($downloadable_files) ? json_encode($downloadable_files) : '[]',
					'comment'         => isset($data['comment']) ? json_encode($data['comment']) : json_encode(array()),
				);


				// all price calculation of cart propduct shipping / tax / price
				$totals = $this->Cart->getTotals($data['country']);
				$order['shipping_cost'] = isset($totals['shipping_cost']) ? (float)$totals['shipping_cost']['amount'] : 0;
				// Store actual shipping cost for vendor reimbursement (same as customer pays for now)
				$order['actual_shipping_cost'] = isset($totals['shipping_cost']) ? (float)$totals['shipping_cost']['amount'] : 0;
				$order['tax_cost'] = isset($totals['tax']) ? (float)$totals['tax']['amount'] : 0;
				$order['total'] = (float)$totals['total']['amount'];

				$restricted_vendors = $this->get_restricted_vendors();


				$form_id = $this->session->userdata('form_id');
				$formDetails = $this->db->query("SELECT * FROM `form` WHERE form_id = '". $form_id . "'")->row();

				$setting = $referlevelSettings = $this->Product_model->getSettings('referlevel');

				$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;

				$refer_status = true;
				
				$disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);

				if((int)$referlevelSettings['status'] == 0) $refer_status = false;

				// Lifetime Commission: load setting once before the loop
				$_store_setting = $this->Product_model->getSettings('store');
				$_lifetime_commission_enabled = isset($_store_setting['lifetime_commission']) && $_store_setting['lifetime_commission'] == 1;

				foreach ($cart_product as $key => $product) {

					$_product = $this->Product_model->getProductDetails((int)$product['product_id']);
					
					// Lifetime Commission: if cart product has no refer_id, check buyer's stored refid
					if((int)$product['refer_id'] == 0 && $_lifetime_commission_enabled && $user && isset($user['id'])) {
						$_buyer_refid = $this->db->query("SELECT refid FROM users WHERE id = " . (int)$user['id'])->row();
						if($_buyer_refid && (int)$_buyer_refid->refid > 0) {
							$product['refer_id'] = (int)$_buyer_refid->refid;
						}
					}

					$_user = $this->Product_model->getUserDetails((int)$product['refer_id']);
 
					$is_vendor = $_user['is_vendor'] == 1;

					if(! $this->allowCommissionFromVendorPanelMode($_product['product_created_by'], $is_vendor)) {
						$_refer_id = 0;
					} else {
						$_refer_id = ($_user && $_user['type'] == 'user') ? (int)$_user['id'] : 0;
					}


					if($_refer_id > 0 && $product['vendor_id']){
						if(in_array($product['vendor_id'], $restricted_vendors)) {
							continue;
						}else{
							$restricted_vendors=array();
						}
						$setting = $referlevelSettings = $this->Product_model->getVendorSettings($setting['vendor_id'], 'referlevel');
						if((int)$referlevelSettings['status'] == 0) $refer_status = false;	
					}

 			
					$commission = false;
						
					
					if($_refer_id > 0) 
					{
						
						if($is_form && $_user && $_user['type'] == 'user')
						{
							
							$commission = $this->Product_model->formcalcCommitions($product, 'sale', $_user, $formDetails); 

						} else 
						{
							
							$commission = $this->Product_model->calcCommitions($product, 'sale', $_user); 
						}

											
					}
					else
					{
						if($user && ( $user['type'] == 'user'))
						{
							
							$product['refer_id']=$user['id'];
							$_refer_id=$user['id'];
							$commission = $this->Product_model->calcCommitions($product, 'sale', $user); 
						}
					}
					
					
					if(((int)$referlevelSettings['status'] == 2 && in_array($product['refer_id'], $disabled_for)) || in_array($product['refer_id'], $restricted_vendors) || $_refer_id > 0)
						$refer_status = false; 


 					
					if($product['refer_id'] > 0 && $refer_status)
					{
						
						$level = $this->Product_model->getMyLevel($product['refer_id']);	
						
						$getSettingsFor = [];
						for ($l=1; $l <= $max_level ; $l++)
							$getSettingsFor[] = 'referlevel_'. $l;

						if(isset($product['vendor_id']) && $product['vendor_id'])
							$referlevelSettings = $this->Product_model->getVendorSettingsWhereIn($product['vendor_id'], $getSettingsFor);
						else
							$referlevelSettings = $this->Product_model->getSettingsWhereIn($getSettingsFor);

						for ($l=1; $l <= $max_level ; $l++) 
						{ 

							$s = $referlevelSettings['referlevel_'. $l];
							$levelUser = (int)$level['level'. $l];
							
							if($product['vendor_id'] == $levelUser)
								continue;

							if($s && $levelUser > 0)
							{
								if($setting['sale_type'] == 'percentage')
									$_giveAmount = ((((int)$product['quantity'] * (float)$product['product_price']) * (float)$s['sale_commition']) / 100);
								else 
									$_giveAmount = (float)$s['sale_commition'];

								if(isset($commission['vendor_commission']))
									$commission['vendor_commission'] -= $_giveAmount;
							}


						}

					} 
					
					// Calculate actual shipping cost per product (proportional to product total)
					$actual_shipping_cost_per_product = 0;
					if($order['actual_shipping_cost'] > 0 && $sub_total > 0) {
						$actual_shipping_cost_per_product = (float)(($product['total'] / $sub_total) * $order['actual_shipping_cost']);
					}

					$_product = array(
						'product_id'             => $product['product_id'],
						'msrp'      			 => $product['product_msrp'],
						'variation'      		 => $product['variation'],
						'refer_id'               => $_refer_id,
						'price'                  => (float) $product['product_price'],
						'total'                  => (float) $product['total'],
						'quantity'               => (int)$product['quantity'],
						'commission'             => ($commission) ? $commission['commission'] : 0,
						'commission_type'        => ($commission) ? $commission['type'] : '',
						'coupon_code'            => $product['coupon_code'],
						'coupon_name'            => $product['coupon_name'],
						'coupon_discount'        => $product['coupon_discount'],
						'allow_shipping'         => $product['allow_shipping'],
						'actual_shipping_cost'   => $actual_shipping_cost_per_product,
						'form_id'                => 0,
						'vendor_id'              => $product['vendor_id'],
						'admin_commission'       => isset($commission['admin_commission']) ? (double)$commission['admin_commission'] : 0,
						'admin_commission_type'  => isset($commission['admin_commission_type']) ? $commission['admin_commission_type'] : '',
						'vendor_commission'      => isset($commission['vendor_commission']) ? (double)$commission['vendor_commission'] : 0,
						'vendor_commission_type' => isset($commission['vendor_commission_type']) ? $commission['vendor_commission_type'] : '',
					);


					if($is_form)
						$_product['form_id'] = $this->session->userdata('form_id');

					$products[] = $_product;
				}

 

				if($check_total_for_skip_payment>0)
				{
					$paymentGateways = $this->session->userdata('payment_gateways');

					if($paymentGateways && isset($paymentGateways[$data['payment_gateway']]))
					{
						require APPPATH."/payment_gateway/controllers/". $data['payment_gateway'] .".php";

						$paymentGateway = $data['payment_gateway'];
						$this->session->set_userdata('payment_gateway',$paymentGateway);

						$object = new $paymentGateway($this);
						$order['payment_method'] = $object->title;

						$insert['payment_module'] = 1;
						$insert['user_id'] = $order['user_id'];
						$insert['content']= serialize($order).' || '.serialize($products);
						$insert['datetime'] = date('Y-m-d H:i:s');
						$this->db->insert('uncompleted_payment',$insert);

						$uncompleted_id = $this->db->insert_id();

						$this->session->set_userdata('uncompleted_id',$uncompleted_id);

						$this->load->library('Uagent');
						$this->uagent->init();

					

						if(!empty($this->uagent->string)) 
						{
							$this->session->set_userdata('uncompleted_uagent', [
								'agent'          => $this->uagent->string,
								'browserName'    => $this->uagent->browserName,
								'browserVersion' => $this->uagent->browserVersion,
								'systemString'   => $this->uagent->systemString,
								'osPlatform'     => $this->uagent->osPlatform,
								'osVersion'      => $this->uagent->osVersion,
								'osShortVersion' => $this->uagent->osShortVersion,
								'mobileName'     => $this->uagent->mobileName,
								'osArch'         => $this->uagent->osArch,
								'isIntel'        => (int)$this->uagent->isIntel,
								'isMobile'       => (int)$this->uagent->isMobile,
								'isAMD'          => (int)$this->uagent->isAMD,
								'isPPC'          => (int)$this->uagent->isPPC,
							]);
						}

						// Always fetch fresh settings from DB so admin changes take effect immediately
						$settingData = $this->Product_model->getSettings('payment_gateway_' . $paymentGateway);
						$settingData['title'] = isset($paymentGateways[$paymentGateway]['title']) ? $paymentGateways[$paymentGateway]['title'] : $object->title;
						$settingData['icon']  = isset($paymentGateways[$paymentGateway]['icon'])  ? $paymentGateways[$paymentGateway]['icon']  : $object->icon;
						$settingData['name']  = $paymentGateway;
						$state = $this->Product_model->getByField('states','id',$order['state_id']);
						$order['state_name'] = $state['name'];

						require APPPATH.'/payment_gateway/module/store/view.php';
						$gatewayData = prepareDataForView($paymentGateway,$uncompleted_id,$user,$order,$products);
						$data['order_comment_setting'] = $this->Product_model->getSettings('order_comment');
						$data['order_comment_setting']['title'] = json_decode($data['order_comment_setting']['title'], true);

						$json['confirm'] = $this->storeapp->view("checkout_confirm",$data, true, true);

						ob_start();
						$object->getPaymentGatewayView($settingData,$gatewayData);
						$json['confirm'] .= ob_get_clean();


					}
				}
				else
				{
					 	$paymentGateways = $this->session->userdata('payment_gateways');
					 	if(!isset($paymentGateways))
					 	{
					 		$paymentGateways=array();
					 	}

					 	if (array_key_exists("cod",$paymentGateways))
					 	{

					 	}
					 	else 
					 	{
					 		$codIcon = resolve_payment_gateway_icon_path('assets/payment_gateway/cod.png');
					 		$cod=array("is_install"=>1,
					 			"title"=>"Cash On Delivery",
					 			"icon"=>$codIcon,
					 			"name"=>"cod");
					 		$paymentGateways["cod"]=$cod;
					 	}


						$this->session->set_userdata('payment_gateway','cod');
						$this->session->set_userdata('payment_gateways',$paymentGateways);
						
						$order['payment_method'] = 'Zero Payment';

						$insert['payment_module'] = 1;
						$insert['user_id'] = $order['user_id'];
						$insert['content']= serialize($order).' || '.serialize($products);
						$insert['datetime'] = date('Y-m-d H:i:s');
						$this->db->insert('uncompleted_payment',$insert);

						$uncompleted_id = $this->db->insert_id();

						$this->session->set_userdata('uncompleted_id',$uncompleted_id);

						$state = $this->Product_model->getByField('states','id',$order['state_id']);
						$order['state_name'] = $state['name'];
						$data['order_comment_setting'] = $this->Product_model->getSettings('order_comment');


						$data['order_comment_setting']['title'] = json_decode($data['order_comment_setting']['title'], true);

						$data['payment_confirmation'] = 'payment_confirmation'; 
						$data['confirm_payment'] = 'confirm_payment'; 
						 
						$data['payment_mode'] = 'no_payment';

						$data['redirect'] = base_url('store/thankyou/'.$uncompleted_id);

						$checkout_view=$this->storeapp->view("checkout_confirm",$data, true, true);
						$checkout_view.=$this->storeapp->view("checkount-without-pyament",$data, true, true);

						$json['confirm'] = $checkout_view;

						ob_start();
						$json['confirm'] .= ob_get_clean();
				}
				 
				

			}
		} 
		else if((! isset($data['classified_checkout']) || $data['classified_checkout'] != 1) && empty($user)) {
			$json['error'] = __('store.user_not_logged_in');
		}
		
		echo json_encode($json);
		die();
	}

	public function payment_confirmation()
	{
	    $json = array();
	    $post = $this->input->post(null,true);
	    
	    $ordercommentarr= $this->Product_model->getSettings('order_comment');

	    if( is_array($ordercommentarr) && $ordercommentarr["status"]==1 && isset($post['comment']) && is_array($post['comment']))
	    {
	        foreach($post['comment'] as $key => $value)
	        {
	            if(empty($value['comment']))
	            {
	                $json['errors']['comment'][$key] = __('store.comment_can_not_be_blank');
	            }
	        }
	    }

	    // Note: payment_proof file is NOT sent in this request (only comments are).
	    // File upload validation is handled entirely in confirm_payment().
	    // The JS pre-check on the frontend handles UX blocking for proof=2.

	    if(!$json['errors'])
	    {
	        $uncompletedData = $this->Product_model->getByField('uncompleted_payment','id',$this->session->userdata('uncompleted_id'));
	        $content = explode(' || ',$uncompletedData['content']);
	        $order = unserialize($content[0]);
	        $order['comment'] = json_encode($post['comment']);
	        $products = unserialize($content[1]);

	        // Decrease product quantity and handle out of stock
	        foreach ($products as &$product) {
	            $product_info = $this->db->get_where('product', ['product_id' => $product['product_id']])->row();
	            if ($product_info && $product_info->product_quantity >= 0) {
	                $new_quantity = max($product_info->product_quantity - $product['quantity'], 0);
	                $this->db->where('product_id', $product['product_id']);
	                $this->db->update('product', ['product_quantity' => $new_quantity]);
	                // V14: Log stock change
	                $this->db->insert('stock_history', [
	                    'product_id' => $product['product_id'],
	                    'quantity_change' => -(int)$product['quantity'],
	                    'quantity_after' => $new_quantity,
	                    'reason' => 'order_placed',
	                    'reference_id' => 0,
	                    'created_at' => date('Y-m-d H:i:s')
	                ]);
	            }
	        }

	        $uncompleted['content'] = serialize($order).' || '.serialize($products);

	        $this->db->update('uncompleted_payment',$uncompleted,array('id' => $this->session->userdata('uncompleted_id')));

	        $json['success'] = true;
	    }else{
	        $json['success'] = false;
	    }

		// Telegram - Local Store - Call to action //
		$message = buildTelegramNewStoreOrderMessage($order, $products);
		sendTelegramNotification($message, 'new_store_order');
		// Telegram - Local Store - Call to action //

	    echo json_encode($json);
	    die;
	}

	public function getSettings($key){
		return $this->Product_model->getSettings($key);
	}

	public function get_payment_mothods(){
		$files = array();
		foreach (glob(APPPATH."/payment_gateway/controllers/*.php") as $file)
			$files[] = $file;

		$allPaymentGateways = array_unique($files);
		$activePaymentGateways = [];
		$defaultPaymntGateway = [];
		foreach($allPaymentGateways as $key => $filename){
			$paymentGateway = basename($filename,".php");

			$result = $this->Product_model->getSettings('payment_gateway_store_'.$paymentGateway,'status');
			$install = $this->Product_model->getSettings('payment_gateway_'.$paymentGateway,'is_install');
			if(isset($result['status']) && $result['status'] && $install['is_install']){
				require $filename;

				$object = new $paymentGateway($this);

				$activePaymentGateways[$paymentGateway] = $this->Product_model->getSettings('payment_gateway_'.$paymentGateway);
				
				$activePaymentGateways[$paymentGateway]['title'] = $object->title;
				$activePaymentGateways[$paymentGateway]['icon'] = resolve_payment_gateway_icon_path($object->icon ?? '');
				$activePaymentGateways[$paymentGateway]['name']  = $paymentGateway;
				
				$where = array('setting_key'=>'status','setting_type'=>'payment_gateway_store_'.$paymentGateway,'setting_is_default'=>1);
				$is_default = $this->Common_model->get_total_rows('setting',$where);
				if($is_default){
					$defaultPaymntGateway[$paymentGateway] = $activePaymentGateways[$paymentGateway];
					unset($activePaymentGateways[$paymentGateway]);
				}
			}
		}
		$data['payment_gateways'] = array_merge($defaultPaymntGateway,$activePaymentGateways);

		$this->session->set_userdata('payment_gateways',$data['payment_gateways']);

		$json['html'] = $this->storeapp->view("payment_methods",$data,true,true);
		echo json_encode($json);
	}

	public function confirm_payment(){
		
		$comment = $this->input->post('comment',true);
		$paymentGateways = $this->session->userdata('payment_gateways');
		$paymentGateway = $this->session->userdata('payment_gateway');

		
		if($paymentGateways)
		{
			$uncompletedData = $this->Product_model->getByField('uncompleted_payment','id',$this->session->userdata('uncompleted_id'));
			$content = explode(' || ',$uncompletedData['content']);
			$order = unserialize($content[0]);
			$products = unserialize($content[1]);

			$file = isset($_FILES['payment_proof']) ? $_FILES['payment_proof'] : false;
			
			// Validate proof upload only when gateway is bank_transfer and proof=2 (Required)
		if ($paymentGateway == 'bank_transfer') {
			$gwSettings  = $this->Product_model->getSettings('payment_gateway_bank_transfer');
			$proofStatus = (int)($gwSettings['proof'] ?? 0);
			if ($proofStatus == 2 && (!$file || empty($file['name']))) {
				$json['errors']['payment_proof'] = __('store.payment_proof_can_not_be_blank');
			}
		} 
			else if($file)
			{
				$extension = pathinfo($file["name"], PATHINFO_EXTENSION);
				$allow_ext = ['pdf', 'doc', 'docs', 'jpg', 'jpeg', 'png'];
				if(in_array(strtolower($extension), $allow_ext)){
					$name = 'pp-'.time().$file['name'];
					move_uploaded_file($file['tmp_name'], FCPATH.'/assets/user_upload/'.$name);

					$uncompleted['content'] = serialize($order).' || '.serialize($products).' || '.serialize($name);
					$this->db->update('uncompleted_payment',$uncompleted,array('id' => $this->session->userdata('uncompleted_id')));
				} else {
					$json['errors']['payment_proof'] = 'Allow only pdf | doc | docs | jpg | jpeg | png';
				}
			}

			if($this->input->post('bank_method') != ''){
				$this->session->set_userdata('bank_method_index', $this->input->post('bank_method'));
			}

			if(!isset($json['errors']))
			{
				
				if($this->session->userdata('uncompleted_id'))
				{ 


					require APPPATH."/payment_gateway/controllers/". $paymentGateway .".php";
					$object = new $paymentGateway($this);

					// Always fetch fresh settings from DB
					$settingData = $this->Product_model->getSettings('payment_gateway_' . $paymentGateway);
					$settingData['title'] = isset($paymentGateways[$paymentGateway]['title']) ? $paymentGateways[$paymentGateway]['title'] : $object->title;
					$settingData['icon']  = isset($paymentGateways[$paymentGateway]['icon'])  ? $paymentGateways[$paymentGateway]['icon']  : $object->icon;
					$settingData['name']  = $paymentGateway;

					$user = $this->Cart->is_logged();

					if(empty($user)) {
						$user = $_SESSION['guest_user'];
					}

					require APPPATH.'/payment_gateway/module/store/request.php';

					$gatewayData = prepareDataForRequest($paymentGateway,$this->session->userdata('uncompleted_id'),$user,$order,$products);

					$json = $object->setPaymentGatewayRequest($settingData,$gatewayData);

				} 
				else 
				{
					
					$json['redirect'] = base_url('store/checkout');
				}
			}
		}

		echo json_encode($json);
		die;
	}

	public function paymentGateway($paymentGateway, $method, $uncompleted_id = '', $action = ''){
		if(is_file(APPPATH.'/payment_gateway/controllers/'.$paymentGateway.'.php')){
			require APPPATH.'/payment_gateway/controllers/'.$paymentGateway.'.php';

			$object = new $paymentGateway($this);

			$settingData = $this->Product_model->getSettings('payment_gateway_'.$paymentGateway);
			
			require APPPATH.'/payment_gateway/module/store/callback.php';
			$gatewayData = prepareDataForCallback($paymentGateway,$method,$uncompleted_id,$action);

			$object->$method($settingData,$gatewayData);
		}
	}

	public function confirmPaymentGateway($uncompleted_id, $status, $transaction_id = '', $comment = ''){

		$this->load->model("Order_model");
		$this->load->model('Mail_model');
		$this->load->model('IntegrationModel');
		$this->load->model("Form_model");
		$vendors = $this->Product_model->getSettings('vendor');

		$ex = new Exception();
		$trace = $ex->getTrace(); 
		if(!isset($trace[1]['class'])){ return false; }

		$paymentGateway = $trace[1]['class']; 
		$filename = APPPATH."/payment_gateway/controllers/{$paymentGateway}.php";
		require_once $filename;

		$cookies_consent=$this->input->post('cookies_consent');
		$object = new $paymentGateway($this);
		if($object->title){

			$uncompletedData = $this->Product_model->getByField('uncompleted_payment','id',$uncompleted_id);
			$content = explode(' || ',$uncompletedData['content']);


			$order = unserialize($content[0]);
			
			$this->db->insert('order',$order);
			$order_id = $this->db->insert_id();

			$products = unserialize($content[1]);
			
			$refrarPost=0;
			$restricted_vendors = $this->get_restricted_vendors();

			foreach($products as $product) {
				if($cookies_consent == 'false'){
					unset($product['refer_id']);
				}

				$productDetail = $this->db->query('SELECT * FROM product WHERE product_id='.$product['product_id'])->row_array();
				if($product['refer_id'] == $productDetail['product_created_by']){
					$refrarPost=$product['refer_id'];
				}
				
				$product['order_id'] = $order_id;
				$this->db->insert('order_products',$product);

				$product_stock = $this->db->get_where('product_meta', array(
					'meta_key' => 'product_quantity',
					'related_product_id' => $product['product_id']
				))->row_array();

				if(! empty($product_stock['meta_value'])) {
					$product_stock_updated['meta_value'] = (int) $product_stock['meta_value'] - $product['quantity'];
					$this->db->update('product_meta',$product_stock_updated, array('product_meta_id' => $product_stock['product_meta_id']));
				}
			}
			if(!in_array($refrarPost, $restricted_vendors) ){
				$restricted_vendors=array();
			}
			

			$proof = unserialize($content[2]);
			if($proof){
				$this->Product_model->insertOrDelete(
					[
						'order_id' => $order_id,
						'proof'    => $proof,
					],
					[	
						'order_id' => $order_id
					]
				);
			}

			$uncompleted['completed_id'] = $order_id;
			$this->db->update('uncompleted_payment',$uncompleted,array('id' => $uncompleted_id));
			
			$status_text = $this->Order_model->status();

			$order_info = $this->Order_model->getOrder($order_id, 'store');

			if($order_info){
				foreach($products as $key => $product) {
					if($cookies_consent == 'false'){
						unset($product['refer_id']);
					}

					if($product['vendor_id'])
						$setting = $referlevelSettings = $this->Product_model->getVendorSettings($product['vendor_id'],'referlevel');
					else
						$setting = $referlevelSettings = $this->Product_model->getSettings('referlevel');

					$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;

					$update1['status'] = (int)$status;
					$update1['txn_id'] = $transaction_id;
					$this->Product_model->update_data( 'order', $update1,array('id' => $order_id) );

					$logs_user = [];
					$logs_vendor = [];
					$logsData = [];
					
					$recursion_data_batch = [];
					
					$products = $this->Order_model->getProducts($order_id);

					$pro_setting = null;
					$form_setting = null;

					$refer_status = true;

					$disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
					
					if((int)$referlevelSettings['status'] == 0){ 
						$refer_status = false; 
					} else if(((int)$referlevelSettings['status'] == 2 && in_array($product['refer_id'], $disabled_for)) ||
						in_array($product['refer_id'], $restricted_vendors)){ 
						$refer_status = false; 
					}

					$wallet_group_id = time().rand(10,100);

					$comission_data_batch = [];

					$is_recurrsive = false;

					if($product['form_id'] == 0){
						$orignal_pro = $this->Product_model->getProductById($product['product_id']);
						$product_recursion_type = $orignal_pro->product_recursion_type;
						if($product_recursion_type){
							$is_recurrsive = true;

							if($product_recursion_type == 'default'){
								if($pro_setting == null) {
									$pro_setting = $this->Product_model->getSettings('productsetting');
								}
								$recursion = $pro_setting['product_recursion'];
								$recursion_endtime = $pro_setting['recursion_endtime'];
								$recursion_custom_time = ($recursion == 'custom_time' ) ? $pro_setting['recursion_custom_time'] : 0;
							}else{
								$recursion = $orignal_pro->product_recursion;
								$recursion_endtime = $orignal_pro->recursion_endtime;
								$recursion_custom_time = ($recursion == 'custom_time' ) ? $orignal_pro->recursion_custom_time : 0;
							}
						}
					} else {
						$orignal_form = $this->Form_model->getForm($product['form_id']);
						$form_recursion_type = $orignal_form['form_recursion_type'];
						if($form_recursion_type){
							$is_recurrsive = true;
							if($form_recursion_type == 'default'){
								if($form_setting == null) {
									$form_setting = $this->Product_model->getSettings('formsetting');
								}
								$recursion = $form_setting['form_recursion'];
								$recursion_custom_time = ($recursion == 'custom_time' ) ? $form_setting['recursion_custom_time'] : 0;
								$recursion_endtime = $form_setting['recursion_endtime'];
							}else{
								$recursion = $orignal_form['form_recursion'];
								$recursion_custom_time = ($recursion == 'custom_time' ) ? $orignal_form['recursion_custom_time'] : 0;
								$recursion_endtime = $orignal_form['recursion_endtime'];
							}
						}
					}
 					


					if($product['refer_id'] > 0 && !in_array($product['refer_id'], $restricted_vendors)){

						if($product['commission'] > 0){
							$comission_data_batch[] = array(
								'status'       => (int)$status == 1 ? 1 : 0,
								'user_id'      => $product['refer_id'],
								'amount'       => $product['commission'],
								'comment'      => 'Commission for order Id order_id='. $order_id .' | Order By : '. $order_info['firstname'] ." " .$order_info['lastname'] ." <br> Sale done from ip_message" ,
								'type'         => 'sale_commission',
								'reference_id' => $product['product_id'],
								'reference_id_2' => $order_id,
								'group_id'     => $wallet_group_id,
								'is_vendor'    => $product['vendor_id'] > 0 ? 1 : 0,
							);
						}
						
					}
 
					//Checking admin sale commission status

					if($product['admin_commission'] > 0 && $vendors['admin_sale_status'] == 1){
						$comission_data_batch[] = array(
							'status'       => (int)$status == 1 ? 1 : 0,
							'user_id'      => 1,
							'amount'       => $product['admin_commission'],
							'comment'      => 'Admin Commission for order Id order_id='. $order_id .' | Order By : '. $order_info['firstname'] ." " .$order_info['lastname'] ." <br> Sale done from ip_message" ,
							'type'         => 'admin_sale_commission',
							'reference_id' => $product['product_id'],
							'reference_id_2' => $order_id,
							'group_id'     => $wallet_group_id,
							'is_vendor'    => 1,
						);
					}
					
					if($product['refer_id'] > 0 && !in_array($product['refer_id'], $restricted_vendors)){

						if($refer_status) {
							$level = $this->Product_model->getMyLevel($product['refer_id']);	

							$getSettingsFor = [];

							for ($l=1; $l <= $max_level ; $l++) {
								$getSettingsFor[] = 'referlevel_'. $l;
							}

							if($product['vendor_id'])
								$referlevelSettings = $this->Product_model->getVendorSettingsWhereIn($product['vendor_id'], $getSettingsFor);
							else 
								$referlevelSettings = $this->Product_model->getSettingsWhereIn($getSettingsFor);

							for ($l=1; $l <= $max_level ; $l++) { 
								$s = $referlevelSettings['referlevel_'. $l];
								$levelUser = (int)$level['level'. $l];
								
								if ($product['vendor_id'] == $levelUser) {
									continue;
								}

								 
								if($s && $levelUser > 0 && !in_array($levelUser, $restricted_vendors)){

									if($setting['sale_type'] == 'percentage')
									{
										$total=$product["total"];
										$_giveAmount = (($total * (float)$s['sale_commition']) / 100);
									} else{
										$commission_mode = isset($setting['fixed_commission_mode']) ? $setting['fixed_commission_mode'] : 'per_order';
										if($commission_mode == 'per_product'){
											$_giveAmount = (float)$s['sale_commition'] * (int)$product['quantity'];
										} else {
											$_giveAmount = (float)$s['sale_commition'];
										}
									}

									if($_giveAmount > 0){
										$comission_data_batch[] = array(
											'status'       => (int)$status == 1 ? 1 : (int)$setting['autoacceptlocalstore'],
											'user_id'      => $levelUser,
											'amount'       => $_giveAmount,
											'comment'      => "Level {$l} : ".'Commission for order Id order_id='. $order_id .' | User : '. $order_info['firstname'] ." " .$order_info['lastname'],
											'type'         => 'refer_sale_commission',
											'reference_id' => $product['product_id'],
											'reference_id_2' => $order_id,
											'group_id' => $wallet_group_id,
											'is_vendor'    => $product['vendor_id'] > 0 ? 1 : 0,
										);
									}

								}
							}
						}


						if(!in_array((int)$product['refer_id'], $logs_user) && !in_array($product['refer_id'], $restricted_vendors)){
							$logs_user[] = (int)$product['refer_id'];
							$logsData[] = array(
								'ip'           => $order_info['ip'],
								'country_code' => $order_info['country_code'],
								'click_id'     => $order_info['id'],
								'domain_name'  => base_url('store'),
								'link'         => base_url('store'),
								'click_type'   => 'store_sale',
								'user_id'      => (int)$product['refer_id'],
							);
						}
					}

					if($product['vendor_commission'] > 0 &&  !in_array($product['vendor_id'], $restricted_vendors)){
						$comission_data_batch[] = array(
							'status'       => (int)$status == 1 ? 1 : 0,
							'user_id'      => $product['vendor_id'],
							'amount'       => $product['vendor_commission'],
							'comment'      => 'Vendor Sell Earning Commission for order Id order_id='. $order_id .' | Order By : '. $order_info['firstname'] ." " .$order_info['lastname'] ." <br> Sale done from ip_message" ,
							'type'         => 'vendor_sale_commission',
							'reference_id' => $product['product_id'],
							'reference_id_2' => $order_id,
							'group_id' => $wallet_group_id,
							'is_vendor'    => 1,
						);
					}

					// Add shipping reimbursement transaction
					if($product['actual_shipping_cost'] > 0) {
						if(empty($product['vendor_id']) || $product['vendor_id'] == 0) {
							// Admin product - create admin_shipping_reimbursement
							$comission_data_batch[] = array(
								'status'       => (int)$status == 1 ? 1 : 0,
								'user_id'      => 1, // Admin user ID
								'amount'       => $product['actual_shipping_cost'],
								'comment'      => 'Admin Shipping Reimbursement for order Id order_id='. $order_id .' | Order By : '. $order_info['firstname'] ." " .$order_info['lastname'] ." | Product: " . $product['product_name'] ." <br> Shipping cost reimbursed to admin" ,
								'type'         => 'admin_shipping_reimbursement',
								'reference_id' => $product['product_id'],
								'reference_id_2' => $order_id,
								'group_id' => $wallet_group_id,
								'is_vendor'    => 0,
							);
						} else if(!in_array($product['vendor_id'], $restricted_vendors)) {
							// Vendor product - create vendor_shipping_reimbursement
							$comission_data_batch[] = array(
								'status'       => (int)$status == 1 ? 1 : 0,
								'user_id'      => $product['vendor_id'],
								'amount'       => $product['actual_shipping_cost'],
								'comment'      => 'Vendor Shipping Reimbursement for order Id order_id='. $order_id .' | Order By : '. $order_info['firstname'] ." " .$order_info['lastname'] ." | Product: " . $product['product_name'] ." <br> Shipping cost reimbursed to vendor" ,
								'type'         => 'vendor_shipping_reimbursement',
								'reference_id' => $product['product_id'],
								'reference_id_2' => $order_id,
								'group_id' => $wallet_group_id,
								'is_vendor'    => 1,
							);
						}
					}

					if((int)$product['vendor_id'] > 0  &&  !in_array($product['vendor_id'], $restricted_vendors)){
						if(!in_array((int)$product['vendor_id'], $logs_vendor)){
							$logs_vendor[] = (int)$product['vendor_id'];
							$logsData[] = array(
								'ip'           => $order_info['ip'],
								'country_code' => $order_info['country_code'],
								'click_id'     => $order_info['id'],
								'domain_name'  => base_url('store'),
								'link'         => base_url('store'),
								'click_type'   => 'store_sale_vendor',
								'user_id'      => (int)$product['vendor_id'],
							);
						}
					}
  
 
					if(!empty($comission_data_batch)) {
						$this->Wallet_model->addTransactionBatch($comission_data_batch);

						$last_id = $this->db->insert_id();

						if($is_recurrsive){
							$recursion_data = array(
								'type'                    => $recursion,
								'custom_time'             => $recursion_custom_time,
								'force_recursion_endtime' => $recursion_endtime,
							);

							for ($i=$last_id; $i < $last_id+sizeof($comission_data_batch); $i++) { 
								$recursion_data['transaction_id'] = $i;
								$recursion_data_batch[] = $recursion_data;
							}
						}
					}
				}

				
				if(!empty($recursion_data_batch)) {
					$this->Wallet_model->addTransactionRecursionBatch($recursion_data_batch);
				}

				 

				$this->load->library('Uagent');
                $this->uagent->init();
				if(!empty($logsData) && !empty($this->uagent->string)) {
                    $this->IntegrationModel->addLogBatch($logsData);
				}


				if(!empty($logsData)) {
					$this->IntegrationModel->addLogBatch($logsData);
				}


				if($order["total"]>0)
					$payment_mode=$object->title;
				else
					$payment_mode="Zero Payment";
 

				$historyData = array(
					'order_id'        => $order_id,
					'payment_mode'    => $payment_mode,
					'history_type'    => 'payment',
					'paypal_status'   => $status_text[(int)$status],
					'comment'         => '[]',
					'created_at'      => date("Y-m-d H:i:s"),
					'order_status_id' => $status,
				);

				$this->db->insert('orders_history',$historyData);

				if(trim($comment)){
					$historyData = array(
						'order_id'        => $order_id,
						'payment_mode'    => $object->title,
						'history_type'    => 'order',
						'paypal_status'   => $status_text[(int)$status],
						'comment'         => $comment,
						'created_at'      => date("Y-m-d H:i:s"),
						'order_status_id' => $status,
					);

					$this->db->insert('orders_history',$historyData);
				}
				
				$this->sendOrderNoti($order_info,$products);
				$this->Mail_model->send_new_order_mail($order_id, $status);
				$this->Cart->clearCart();
				return true;
			}
		}

		return false;
	}

	public function sendOrderNoti($order_info,$products){
		$userDetail = $this->Product_model->getUserDetails($order_info['user_id']);
		$cdate = date('Y-m-d H:i:s');
		$notificationData = array(
			'notification_url'          => '/vieworder/'.$order_info['id'],
			'notification_type'         =>  'order',
			'notification_title'        =>  'New Order Generated by '.$userDetail['username'],
			'notification_viewfor'      =>  'admin',
			'notification_actionID'     =>  $order_info['id'],
			'notification_description'  =>  $userDetail['firstname'].' '.$userDetail['lastname'].' created a new order at affiliate Program on '.date('Y-m-d H:i:s'),
			'notification_is_read'      =>  '0',
			'notification_created_date' =>  $cdate,
			'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
		);
		$this->insertnotification($notificationData);
		$notificationData = array(
			'notification_url'          => '/vieworder/'.$order_info['id'],
			'notification_type'         =>  'order',
			'notification_title'        =>  'Your Order has been place',
			'notification_viewfor'      =>  'client',
			'notification_view_user_id' =>  $userDetail['id'],
			'notification_actionID'     =>  $order_info['id'],
			'notification_description'  =>  'Your Order has been place',
			'notification_is_read'      =>  '0',
			'notification_created_date' =>  $cdate,
			'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
		);
		$this->insertnotification($notificationData);
		foreach ($products as $key => $product) {
			if($product['refer_id'] > 0){
				$notificationData = array(
					'notification_url'          => '/vieworder/'.$order_info['id'],
					'notification_type'         =>  'order',
					'notification_title'        =>  'New Order Generated by '.$userDetail['username'],
					'notification_viewfor'      =>  'user',
					'notification_view_user_id' =>  $product['refer_id'],
					'notification_actionID'     =>  $order_info['id'],
					'notification_description'  =>  $userDetail['firstname'].' '.$userDetail['lastname'].' created a new order which you refered to him at affiliate Program on '.date('Y-m-d H:i:s'),
					'notification_is_read'      =>  '0',
					'notification_created_date' =>  $cdate,
					'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
				);
				$this->insertnotification($notificationData);
			}

			if($product['vendor_id'] > 0){
				$notificationData = array(
					'notification_url'          => '/vieworder/'.$order_info['id'],
					'notification_type'         =>  'order',
					'notification_title'        =>  'New Order Generated With Your Vendor Product by '.$userDetail['username'],
					'notification_viewfor'      =>  'user',
					'notification_view_user_id' =>  $product['vendor_id'],
					'notification_actionID'     =>  $order_info['id'],
					'notification_description'  =>  $userDetail['firstname'].' '.$userDetail['lastname'].' created a new order with your vendor product at '.date('Y-m-d H:i:s'),
					'notification_is_read'      =>  '0',
					'notification_created_date' =>  $cdate,
					'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
				);
				$this->insertnotification($notificationData);
			}
		}
	}

	private function insertnotification($postData = null){
		if(!empty($postData)){
			$this->Product_model->create_data('notification', $postData);
		}
	}

	public function ajax_register(){
		$post = $this->input->post(null,true);

		$googlerecaptcha = $this->Product_model->getSettings('googlerecaptcha');

		if (!empty($googlerecaptcha['client_register'])) {
		    $recaptcha_response = $post['captch_response'] ?? $post['g-recaptcha-response'] ?? '';

		    if (empty($recaptcha_response)) {
		        $json['errors']['captch_response'] = 'Captcha is required.';
		    } else {
		        $recaptcha_result = validate_recaptcha(
		            $recaptcha_response,
		            $googlerecaptcha['secretkey'],
		            'client_register', // action
		            0.5, // min score
		            isset($googlerecaptcha['version']) && $googlerecaptcha['version'] === 'v3' // use v3?
		        );

		        if (!$recaptcha_result['success']) {
		            $json['errors']['captch_response'] = $recaptcha_result['error'];
		        }
		    }
		}

		if(!isset($json) || (isset($json['errors']) && count($json['errors']) == 0)){
			$post = $this->input->post(null,true);

			$this->load->model('user_model', 'user');
			$this->load->model('Product_model');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('f_name', 'First Name', 'required|trim');
			$this->form_validation->set_rules('l_name', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('username', 'Username', 'required|trim');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
			$this->form_validation->set_rules('phone', 'Phone', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('c_password', 'Confirm Password', 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('c_password', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
			if ($this->form_validation->run() == FALSE) 
			{
				$json['errors'] = $this->form_validation->error_array();
 
			} 
			else 
			{
				 

				$checkEmail = $this->db->query("SELECT id, type FROM users WHERE email like ". $this->db->escape($this->input->post('email',true)) ." AND type!='guest'")->row_array();
				if(!empty($checkEmail)){ $json['errors']['email'] = "Email Already Exist"; }
				
				$checkUsername = $this->db->query("SELECT * FROM users WHERE username like ". $this->db->escape($this->input->post('username',true)) ." AND type!='guest'")->num_rows();
				if($checkUsername > 0){ $json['errors']['username'] = "Username Already Exist"; }
				
				$checkPhone = $this->db->query("SELECT id, type FROM users WHERE phone like ". $this->db->escape($this->input->post('PhoneNumberInput',true)) ." ")->row_array();
				if(!empty($checkPhone)){ $json['errors']['phone'] = "Phone Number Already Exist"; }
 

				if(!isset($json['errors'])){
					$geo = $this->ip_info();	

					$userData = array(
						'firstname'                 => $this->input->post('f_name',true),
						'lastname'                  => $this->input->post('l_name',true),
						'email'                     => $this->input->post('email',true),
						'username'                  => $this->input->post('username',true),
						'password'                  => sha1($this->input->post('password',true)),
						'refid'                     => $this->Cart->getReferId(),
						'type'                      => 'client',
						'Country'                   => (int)$geo['id'],
						'City'                      => (string)$geo['city'],
						'phone'                     => $this->input->post('PhoneNumberInput',true),
						'twaddress'                 => '',
						'address1'                  => '',
						'address2'                  => '',
						'ucity'                     => '',
						'ucountry'                  => '',
						'state'                     => '',
						'uzip'                      => '',
						'avatar'                    => '',
						'online'                    => '0',
						'unique_url'                => '',
						'bitly_unique_url'          => '',
						'created_at'                => date("Y-m-d H:i:s"),
						'updated_at'                => date("Y-m-d H:i:s"),
						'google_id'                 => '',
						'facebook_id'               => '',
						'twitter_id'                => '',
						'umode'                     => '',
						'PhoneNumber'               => $this->input->post('PhoneNumberInput',true),
						'Addressone'                => '',
						'Addresstwo'                => '',
						'StateProvince'             => '',
						'Zip'                       => '',
						'f_link'                    => '',
						't_link'                    => '',
						'l_link'                    => '',
						'product_commission'        => '0',
						'affiliate_commission'      => '0',
						'product_commission_paid'   => '0',
						'affiliate_commission_paid' => '0',
						'product_total_click'       => '0',
						'product_total_sale'        => '0',
						'affiliate_total_click'     => '0',
						'sale_commission'           => '0',
						'sale_commission_paid'      => '0',
						'status'                    => '1'
					);

					if(empty($checkEmail)) {
						$this->user->insert($userData);
						$last_user_id = $data = $this->db->insert_id();
					} else if(!empty($checkEmail) && $checkEmail['type'] == 'guest') {
						$this->db->update('users', $userData, array('id' => $checkEmail['id']));
						$last_user_id = $data = $checkEmail['id'];
					} else {
						die("Something went wrong!");
					}

					$json['success'] =  __('user.youve_successfully_registered');
					$user_details_array = $this->user->login( $this->input->post('username',true) );
					$this->session->set_userdata(array('client'=>$user_details_array));

					$notificationData = array(
						'notification_url'          => '/listclients/'.$data,
						'notification_type'         =>  'client',
						'notification_title'        =>  __('user.new_client_registration'),
						'notification_viewfor'      =>  'admin',
						'notification_actionID'     =>  $data,
						'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' register as a client on affiliate Program on '.date('Y-m-d H:i:s'),
						'notification_is_read'      =>  '0',
						'notification_created_date' =>  date('Y-m-d H:i:s'),
						'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
					);
					$this->Product_model->create_data('notification', $notificationData);
					
					$this->load->model('Mail_model');
					$post['user_type'] = 'client';
					$post['firstname'] = $this->input->post('f_name',true);
					$post['lastname'] = $this->input->post('l_name',true);
					$this->Mail_model->send_register_mail($post,__('user.welcome_to_new_client_registration'));
				}
			}
		}
		echo json_encode($json);
	}

	public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
		$this->load->helper('geolocation');
		$geolocation = new Geolocation_helper();
		return $geolocation->ip_info($ip, $purpose, $deep_detect);
	}

	public function thankyou($uncompleted_id)
	{
		$_SESSION['guest_user'] = null;
		if(isset($_SESSION['guestFlow'])) unset($_SESSION['guestFlow']);
		if(isset($_SESSION['guestFlowClassified'])) unset($_SESSION['guestFlowClassified']);
		// Clear payment session so refreshing /store/checkout shows fresh checkout
		$this->session->unset_userdata('uncompleted_id');
		$this->session->unset_userdata('payment_gateway');
		$this->session->unset_userdata('payment_gateways');
		$uncompletedData = $this->Product_model->getByField('uncompleted_payment','id',$uncompleted_id);

		if (empty($uncompletedData)) {
			redirect(base_url('store'));
			return;
		}
		$order_id = $uncompletedData['completed_id'];

		$this->load->model('Order_model');
		$this->load->model('Product_model');
		$this->load->model('Form_model');
		$user = $this->session->userdata('client') ? $this->session->userdata('client') : '';
		$data['client_loged'] = $this->session->userdata('client') ? true : false;
		$data['is_guest'] = $user != '' ? $user : [];

		if($user != '')
			$filternew['user_id']=$user['id'];
		else
			$filternew['user_id']=null;
		$data['order'] = $this->Order_model->getOrder($order_id, 'store',$filternew);
		 

		$data['products'] = $this->Order_model->getProducts($order_id);
		$data['totals'] = $this->Order_model->getTotals($data['products'], $data['order']);

		$this->load->model('User_model');
		$admin_info = $this->User_model->get_user_by_type('admin');
		$data['store_name'] =  $admin_info['firstname'].' '.$admin_info['lastname'];
		$data['store_email'] =  $admin_info['email'];

		$form_details = $this->Form_model->getForm($data['products'][0]['form_id']);
		$data['product_type'] = '';
		$data['downloadable_files'] = '';

		$data['orderProof'] = $this->Order_model->getPaymentProof($order_id);

		if($data['order']['status'] == 1){
			$data['product_type'] = $form_details['product_type'];
			$data['downloadable_files'] = $this->Product_model->parseDownloads($form_details['downloadable_files']);
		}
		
		
		$this->Cart->clearCart();
  
		if((( isset($data['order'])  && array_key_exists("user_id",$data['order'])>0 && isset($user) && is_array($user)==true  && $data['order']['user_id'] == $user['id'])) || (( isset($data['order']) && array_key_exists("user_id",$data['order'])>0 && $data['order']['user_id'] == base64_decode($_SESSION['guest'])))){
			$data['settings'] = $this->Product_model->getSettings('store');
			$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
			$data['payment_history'] = $this->Order_model->getHistory($order_id);
			$data['status'] = $this->Order_model->status();
			$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
			$data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');
			$this->storeapp->view('checkout_thankyou', $data, true);
		} else {
		    $heading = __('store.permission_denied');
		    $message = __('store.are_not_allow_to_see');
		    $data = [
		        'heading' => $heading,
		        'message' => $message
		    ];
		    $this->load->view('errors/html/error_message', $data);
		}
	}

	public function profile(){
		$user = $this->Cart->is_logged();
		
		if(!$user) redirect(base_url('store/login'));

		$this->load->model('Product_model');

		$data = array();
		$post = $this->input->post(null,true);

		if($post){
			$this->load->model('user_model', 'user');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
			$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
			$this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'required|trim');
			$this->form_validation->set_rules('ucity', 'ucity', 'required|trim');
			$this->form_validation->set_rules('uzip', 'uzip', 'required|trim');
			$this->form_validation->set_rules('twaddress', 'twaddress', 'required|trim');

			if($post['new_password'] != ''){
				$this->form_validation->set_rules('new_password', 'New Password', 'required|trim');
				$this->form_validation->set_rules('c_password', 'Confirm Password', 'required|matches[new_password]');
			}

			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('error', __('store.missing_required_data'));
				$rt = $this->input->get('return_to');
				redirect('store/profile' . ($rt ? '?return_to=' . urlencode($rt) : ''));
			}

			if(!empty($_FILES['avatar']['name'])){
				$upload_response = $this->upload_photo('avatar','assets/images/users');
				if($upload_response['success']){
					$details['avatar'] = $upload_response['upload_data']['file_name'];
				} else {
					$this->session->set_flashdata('error',$upload_response['msg']);
					$rt = $this->input->get('return_to');
					redirect('store/profile' . ($rt ? '?return_to=' . urlencode($rt) : ''));
				}
			}

			$checkEmail = $this->db->query("SELECT * FROM users WHERE id != ".$user['id']." AND email like ". $this->db->escape($this->input->post('email',true)) ." ")->num_rows();
			if($checkEmail > 0){
				$error['email'] = __('store.email_already_exist');
				$this->session->set_flashdata('error',$error['email']);
				$rt = $this->input->get('return_to');
				redirect('store/profile' . ($rt ? '?return_to=' . urlencode($rt) : '')); 
			}

			$data = array(
				'firstname' 	=> $this->input->post('firstname',true),
				'lastname'  	=> $this->input->post('lastname',true),
				'email'     	=> $this->input->post('email',true),
				'phone'			=> $this->input->post('PhoneNumberInput',true),
				'PhoneNumber'   => $this->input->post('PhoneNumberInput',true),
				'ucountry'   	=> $this->input->post('ucountry',true),
				'state'   	=> $this->input->post('state',true),
				'ucity'      	=> $this->input->post('ucity',true),
				'uzip'      		=> $this->input->post('uzip',true),
				'twaddress'     => $this->input->post('twaddress',true),
			);

			if($post['new_password'] != '')
				$data['password']  = sha1($this->input->post('new_password',true));
			
			if(isset($details['avatar']))
				$data['avatar'] = $details['avatar'];

			$this->db->update("users", $data, array('id' => $user['id']));

			$userArray = $this->db->query("SELECT * FROM users WHERE id = ".$user['id'])->row_array();

			$this->session->set_userdata(array('client'=>$userArray));

			$this->session->set_flashdata('success',__('user.youve_successfully_updated'));
			$rt = $this->input->get('return_to');
			redirect('store/profile' . ($rt ? '?return_to=' . urlencode($rt) : ''));
		}
		
		$data = $this->store_cart_payload->page_profile();
		$rt = $this->input->get('return_to');
		$data['return_to'] = (!empty($rt) && (strpos($rt, rtrim(base_url(), '/')) === 0 || (strpos($rt, '/') === 0 && strpos($rt, '//') !== 0))) ? $rt : '';
		$this->storeapp->view('profile', $data);
	}

	public function upload_photo($fieldname,$path) {
		$config['upload_path'] = $path;	
		$config['allowed_types'] = 'png|gif|jpeg|jpg';
		$this->load->helper('string');
		$config['file_name']  = random_string('alnum', 32);

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload($fieldname)) {
			$data = array('success' => false, 'msg' => $this->upload->display_errors());
		} else { 
			$upload_details = $this->upload->data();

			$config1 = array(
				'source_image' => $upload_details['full_path'],
				'new_image' => $path.'/thumb',
				'maintain_ratio' => true,
				'width' => 300,
				'height' => 300
			);
			$this->load->library('image_lib', $config1);
			$this->image_lib->resize();
			$data = array('success' => true, 'upload_data' => $upload_details, 'msg' => "Upload success!");
		}
		return $data;
	}

	public function my_courses() {
		$data = $this->store_cart_payload->page_my_courses();
		if (!empty($data['_error'])) {
			header('Location: ' . base_url('store/login'));
			die;
		}
		$this->storeapp->view('my_courses', $data);
	}

	public function order() {
		$data = $this->store_cart_payload->page_orders();
		if (!empty($data['_error'])) {
			header('Location: ' . base_url('store/login'));
			die;
		}
		$rt = $this->input->get('return_to');
		$data['return_to'] = (!empty($rt) && (strpos($rt, rtrim(base_url(), '/')) === 0 || (strpos($rt, '/') === 0 && strpos($rt, '//') !== 0))) ? $rt : '';
		$this->storeapp->view('order_list', $data);
	}

	public function wishlist(){
		$userdetails = $this->Cart->is_logged(); 
		if(empty($userdetails)){ 
			
			header("Location: ".base_url('store/login'));
			die;
		} else {
			$this->load->library('user_agent');
			$this->load->model('Product_model');
		$user_id = base64_decode($this->input->get('ref', true) ?? '');

		$site_setting = $this->Product_model->getSettings('site');
			
			if($user_id > 0){
				if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
					$store_setting = $this->Product_model->getSettings('store');
					$time = ($store_setting['affiliate_cookie'] * 24 * 60 * 60) * 1000;
					$_SESSION['setLocalStorageAffiliate'] = json_encode([$user_id, $time]);
					$this->Cart->setReferId($user_id);
				}

				if(! isset($site_setting['affiliate_tracking_place']) || ($site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2)) {
					$this->Cart->setcookieAffiliate($user_id);
				}
				redirect(base_url('store'));
			}else{
				$cookie_user_id = $localstorage_user_id = 0;
				if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
					$localstorage_user_id = $this->session->localStorageAffiliate;
				}

				if(! isset($site_setting['affiliate_tracking_place']) || $site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2) {
					$cookie_user_id = $this->Cart->getcookieAffiliate('affiliate_id');                
				} 

				$user_id = $localstorage_user_id == 1 ? $cookie_user_id : $localstorage_user_id;
			}
			$data = $this->store_cart_payload->page_wishlist();
			$this->storeapp->view('wishlist', $data);
		}
	}

	public function vieworder($order_id){
		$user =  $this->Cart->is_logged(); 
		if(empty($user)){ 
			header("Location: ".base_url('store/login'));
			die;
		}
		$this->load->model('Order_model');
		$this->load->model('Product_model');
		$this->load->model('Form_model');
		$data['order_id'] = $order_id;
		$data['category_tree'] = $this->Product_model->getCategoryTree();
		$data['category'] = [];
		$data['order'] = $this->Order_model->getOrder($order_id,'store');
		if($data['order']['user_id'] == $user['id']){
			$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
			$data['payment_history'] = $this->Order_model->getHistory($order_id);
			$data['status'] = $this->Order_model->status();
			$data['products'] = $this->Order_model->getProducts($order_id);
			$data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);
			$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
			$data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');
			$data['orderProof'] = $this->Order_model->getPaymentProof($order_id);
			$data['settings'] = $this->Product_model->getSettings('store');
			$this->storeapp->view("view_order", $data);
		}else {
		    $heading = __('store.permission_denied');
		    $message = __('store.are_not_allow_to_see');
		    $data = [
		        'heading' => $heading,
		        'message' => $message
		    ];
		    $this->load->view('errors/html/error_message', $data);
		}
	}

	public function track_order() {
		$this->load->model('Order_model');
		$this->load->model('Product_model');
		$data = $this->store_cart_payload->page_track_order();

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$order_number = trim($this->input->post('order_number'));
			$email = trim($this->input->post('email'));
			$data['track_form_values'] = [
				'order_number' => htmlspecialchars($order_number),
				'email'        => htmlspecialchars($email),
			];

			if (empty($order_number) || empty($email)) {
				$data['error'] = __('store.track_order_required');
				$this->storeapp->view("track_order", $data);
				return;
			}

			$order_id = (int) $order_number;
			if ($order_id < 1) {
				$data['error'] = __('store.order_not_found');
				$this->storeapp->view("track_order", $data);
				return;
			}

			$order = $this->Order_model->getOrder($order_id, 'store');
			if (empty($order) || empty($order['email'])) {
				$data['error'] = __('store.order_not_found');
				$this->storeapp->view("track_order", $data);
				return;
			}

			if (strtolower(trim($order['email'])) !== strtolower($email)) {
				$data['error'] = __('store.order_not_found');
				$this->storeapp->view("track_order", $data);
				return;
			}

			$data['order_id'] = $order_id;
			$data['order'] = $order;
			$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
			$data['payment_history'] = $this->Order_model->getHistory($order_id);
			$data['status'] = $this->Order_model->status();
			$data['products'] = $this->Order_model->getProducts($order_id);
			$data['totals'] = $this->Order_model->getTotals($data['products'], $data['order']);
			$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
			$data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');
			$data['orderProof'] = $this->Order_model->getPaymentProof($order_id);
			$data['settings'] = $this->Product_model->getSettings('store');
			$data['is_guest_track'] = true;
			$data['is_guest'] = true;
			$this->storeapp->view('checkout_thankyou', $data, true);
			return;
		}

		$this->storeapp->view("track_order", $data);
	}

	public function vieworderdetails($order_id){
		$user =  $this->Cart->is_logged(); 

		if(empty($user)){ 
			header("Location: ".base_url('store/login'));
			die;
		}
		$product_id =strip_tags($_GET['referance']);
		if(empty($product_id)) {
			header("Location: ".base_url('store/login'));
			die;	
		}
		if (filter_var($product_id, FILTER_VALIDATE_INT) === false ) {
			header("Location: ".base_url('store/login'));
			die;
		}
		$product_id = (int)$product_id;
		$this->load->model('Order_model');
		$this->load->model('Product_model');
		$this->load->model('Form_model');
		$data['order_id'] = $order_id;
		$data['category_tree'] = $this->Product_model->getCategoryTree();
		$data['category'] = [];
		$data['order'] = $this->Order_model->getOrder($order_id,'store');
		if($data['order']['user_id'] == $user['id']){

			if((int)$data['order']['status'] !== 1){
				$heading = __('store.access_denied');
				$message = __('store.course_not_paid');
				$data_err = [
					'heading' => $heading,
					'message' => $message,
				];
				$this->load->view('errors/html/error_message', $data_err);
				return;
			}

			$data['products'] = $this->Order_model->getProducts($order_id,['product_id'=>$product_id]," ,product_description,product_avg_rating,product_slug",$user['id']);
			$lmaProductStatus = $this->db->where(['order_id'=>$order_id,'user_id'=>$user['id']])->get('user_lms_product')->row_array();
			$data['videoStatus'] = !empty($lmaProductStatus) ? json_decode($lmaProductStatus['lms_product'],true) : [];
			
			if($data['products'][0]['product_type'] == 'video' || $data['products'][0]['product_type'] =='videolink') {
				$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
				$data['payment_history'] = $this->Order_model->getHistory($order_id);
				$data['status'] = $this->Order_model->status();
				$data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);
				$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
				$data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');
				$data['orderProof'] = $this->Order_model->getPaymentProof($order_id);
				$data['settings'] = $this->Product_model->getSettings('store');

				$site_setting = $this->Product_model->getSettings('site');

				$cookie_user_id = $localstorage_user_id =0;
				if(isset($site_setting['affiliate_tracking_place']) && ($site_setting['affiliate_tracking_place'] == 1 || $site_setting['affiliate_tracking_place'] == 2)) {
					$localstorage_user_id = $this->session->localStorageAffiliate;
				}
				if(! isset($site_setting['affiliate_tracking_place']) || $site_setting['affiliate_tracking_place'] == 0 || $site_setting['affiliate_tracking_place'] == 2) {
					$cookie_user_id = $this->Cart->getcookieAffiliate('affiliate_id');                
				} 
				$data['user_id'] = $localstorage_user_id == 1 ? $cookie_user_id : $localstorage_user_id;

				$this->load->library("socialshare");				
				$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();
				$this->storeapp->view("lms/template-1", $data,'lms');
			} else {
				redirect(base_url('store/order'),'refresh');
			}
		}else {
		    $heading = __('store.permission_denied');
		    $message = __('store.are_not_allow_to_see');
		    $data = [
		        'heading' => $heading,
		        'message' => $message
		    ];
		    $this->load->view('errors/html/error_message', $data);
		}
	}

	public function downloadable_file($filename, $mask, $order_id){
		$this->load->model('Order_model');
		$basePaths = [
			rtrim(APPPATH, '/\\') . DIRECTORY_SEPARATOR . 'downloads' . DIRECTORY_SEPARATOR,
			rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'downloads' . DIRECTORY_SEPARATOR,
		];
		$basePath = $basePaths[0];
		$file = $basePath . $filename;
		$isPlay = !empty($_GET['play']);
		if(!empty($_GET['resource']) || $isPlay)
			$file = $basePath . $mask;
		// Fallbacks for resource=1 or play=1 when primary path not found
		if((!empty($_GET['resource']) || $isPlay) && !file_exists($file)){
			$namesToTry = array_filter([$mask, $filename, (strpos($filename??'', '.') === false ? $filename . '.zip' : null)]);
			foreach($basePaths as $bp){
				foreach($namesToTry as $name){
					if(!$name) continue;
					$c = $bp . $name;
					if(file_exists($c)){ $file = $c; break 2; }
				}
			}
			// Last resort: resolve from order product data (handles legacy/alternate storage)
			if(!file_exists($file)){
				$products = $this->Order_model->getProducts($order_id);
				if(!empty($products)){
					foreach($products as $prod){
						if(empty($prod['downloadable_files'])) continue;
						foreach($prod['downloadable_files'] as $sect){
							if(empty($sect['data'])) continue;
							foreach($sect['data'] as $item){
								if(empty($item['zip']['mask']) && empty($item['mask'])) continue;
								if($item['name'] == $filename || (isset($item['zip']['name']) && $item['zip']['name'] == $filename) || (isset($item['mask']) && $item['mask'] == $mask)){
									foreach($basePaths as $bp){
										$tryMask = isset($item['mask']) ? $bp . $item['mask'] : null;
										if($tryMask && file_exists($tryMask)){ $file = $tryMask; break 4; }
										$try = isset($item['zip']['mask']) ? $bp . $item['zip']['mask'] : null;
										if($try && file_exists($try)){ $file = $try; break 4; }
										$tryName = isset($item['zip']['name']) ? $bp . $item['zip']['name'] . '.zip' : null;
										if($tryName && file_exists($tryName)){ $file = $tryName; break 4; }
									}
								}
							}
						}
					}
				}
			}
		}
		$order = $this->Order_model->getOrder($order_id,'store');
		$userid = decryptString($_GET['link']??'TEST');
		$sessionUser = $this->Cart->is_logged();

		if(isset($_GET['link']) && $userid==null){
			if(empty($sessionUser)){
				header("Location: ".base_url('store/login'));
				die;
			}
		}
		// Use session user when on LMS page (no valid link) - must match order owner
		if(empty($userid) && !empty($sessionUser['id']) && (int)$sessionUser['id'] === (int)$order['user_id']){
			$userid = $sessionUser['id'];
		}
		if((int)($userid ?? 0) !== (int)$order['user_id']){
			header('HTTP/1.1 403 Forbidden');
			exit('Access denied');
		}

		if (!headers_sent()) {
			if (file_exists($file)) {
				$contentType = 'application/octet-stream';
				$disposition = 'attachment';
				if($isPlay){
					$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$videoTypes = ['mp4'=>'video/mp4','webm'=>'video/webm','ogg'=>'video/ogg','ogv'=>'video/ogg','m4v'=>'video/x-m4v'];
					if(isset($videoTypes[$ext])) $contentType = $videoTypes[$ext];
					$disposition = 'inline';
				}
				header('Content-Type: ' . $contentType);
				header('Content-Disposition: ' . $disposition . '; filename="' . ($mask ? $mask : basename($file)) . '"');
				header('Accept-Ranges: bytes');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');

				if (ob_get_level()) ob_end_clean();

				// Range support for video streaming (seek/play)
				$size = filesize($file);
				$range = null;
				if($isPlay && !empty($_SERVER['HTTP_RANGE'])){
					$range = $_SERVER['HTTP_RANGE'];
					if(preg_match('/bytes=(\d+)-(\d*)/', $range, $m)){
						$start = (int)$m[1];
						$end = isset($m[2]) && $m[2] !== '' ? (int)$m[2] : $size - 1;
						$end = min($end, $size - 1);
						$length = $end - $start + 1;
						header('HTTP/1.1 206 Partial Content');
						header('Content-Length: ' . $length);
						header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
						$fp = fopen($file, 'rb');
						fseek($fp, $start);
						echo fread($fp, $length);
						fclose($fp);
						exit();
					}
				}
				header('Content-Length: ' . $size);
				readfile($file, 'rb');
				exit();
			} else {
				exit('Error: Could not find file ' . $file . '!');
			}
		} else {
			exit('Error: Headers already sent out!');
		}
	}

	public function shipping(){
		$data = array();
		$this->load->model('Product_model');
		$user = $this->Cart->is_logged();
		$data['category_tree'] = $this->Product_model->getCategoryTree();
		$data['category'] = [];

		$post = $this->input->post(null,true);

		if($post){
			$this->load->model('user_model', 'user');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('address', 'Address', 'required|trim');
			$this->form_validation->set_rules('country', 'Country', 'required|trim');
			$this->form_validation->set_rules('state', 'State', 'required|trim');
			$this->form_validation->set_rules('city', 'City', 'required|trim');
			$this->form_validation->set_rules('zip_code', 'Postal Code', 'required|trim');
			$this->form_validation->set_rules('phone', 'Phone Number', 'required|trim');
			if ($this->form_validation->run() == FALSE) {
				$data['errors'] = $this->form_validation->error_array();
			} else {
				$check = $this->db->query("SELECT id FROM shipping_address WHERE user_id =  ". $user['id'])->row();
				$shipping = array(
					'user_id'    => $user['id'],
					'address'    => $this->input->post('address',true),
					'country_id' => $this->input->post('country',true),
					'state_id'   => $this->input->post('state',true),
					'city'       => $this->input->post('city',true),
					'zip_code'   => $this->input->post('zip_code',true),
					'phone'      => $this->input->post('PhoneNumberInput',true),
				);
				if($check){
					$this->db->update("shipping_address", $shipping, ['id' => $check->id]);
				}else{
					$this->db->insert("shipping_address", $shipping);
				}				
				$this->session->set_flashdata('success', __('user.youve_successfully_updated'));
				redirect('store/shipping');
			}
		}
		if(!$user){
			
			header("Location: ".base_url('store/login'));
			die;
		}else{
			$check = $this->db->query("SELECT * FROM shipping_address WHERE user_id =  ". $user['id'])->row();
			$data['shipping'] = array();
			if($check){
				$data['shipping'] = array(
					'user_id'    => $user['id'],
					'address'    => $check->address,
					'country_id' => $check->country_id,
					'state_id'   => $check->state_id,
					'city'       => $check->city,
					'zip_code'   => $check->zip_code,
					'phone'      => $check->phone,
				);
			}
			$data['country'] = $this->Product_model->getcountry();
			$data['settings'] = $this->Product_model->getSettings('store');
			$this->storeapp->view("shipping", $data);
		}
	}

	public function login(){
		if(isset($_SESSION['guestFlow'])) unset($_SESSION['guestFlow']);
		$redirect_url = $this->Cart->getStoreUrl(base64_encode($this->session->userdata("refer_id")));
		if($this->Cart->is_logged()) redirect($redirect_url);
		$data = array();
		$data['redirect_url'] = $redirect_url;
		$data['settings'] = $this->Product_model->getSettings('store');
		$data['category_tree'] = $this->Product_model->getCategoryTree();
		$data['category'] = [];
		$this->storeapp->view("login",$data);
	}

	public function forgot(){
		$email = $this->input->post('forgot_email',true);

		if(empty($email)) {
			$json['error'] = __('user.email_address_required');
		} else {
			$data = $this->db->query("SELECT * FROM users WHERE email like '{$email}' ")->row();
			if ($data) {
				$token = md5(uniqid(rand(), true));
				$resetlink = base_url('resetpassword/'. $token);

				$this->db->query("DELETE  FROM password_resets WHERE email like '{$email}' ");
				$this->db->query("INSERT INTO password_resets SET 
					email = '{$email}',
					token = '{$token}'
					");
				$this->load->model('Mail_model');
				$this->Mail_model->send_forget_mail($data, $resetlink);
				$json['success'] = __('user.password_reset_instructions_will_be_sent_to_your_registered_email_address');
			}else{
				$json['error'] = __('user.email_address_not_found');
			}
		}

		echo json_encode($json);
	}

	public function logout(){
		$this->session->unset_userdata('client');
		$this->session->unset_userdata('user');
		if($this->session->userdata('refer_id')){
			redirect('store/'. base64_encode($this->session->userdata('refer_id')));
		}else{
			redirect('store/login');
		}
		$this->session->sess_destroy();
	}

	public function mini_cart(){
		$data['products'] = $this->Cart->getProducts();
		$data['is_logged'] = $this->Cart->is_logged();	
		$data['base_url'] = $this->Cart->getStoreUrl();
		$data['sub_total'] = $data['total'] = $this->Cart->subTotal();
		$json['cart'] = $this->storeapp->view("mini_cart",$data,true,true);
		$json['sub_total'] = c_format($data['sub_total']);
		$json['total'] = count($data['products']);
		$json['products'] = $data['products'];
		echo json_encode($json);
	}

	public function order_attechment($filename,$mask){
		$file = APPPATH .'/downloads_order/'. $filename;
		$mask = basename($mask);

		if (!headers_sent()) {
			if (file_exists($file)) {
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));

				if (ob_get_level()) {
					ob_end_clean();
				}

				readfile($file, 'rb');

				exit();
			} else {
				exit('Error: Could not find file ' . $file . '!');
			}
		} else {
			exit('Error: Headers already sent out!');
		}
	}

	public function play() {
		$videoId = $this->input->get('track');
		$orderId = $this->input->get('orderId');
		if($videoId) {	
			$this->load->model('Order_model');
			$products = $this->Order_model->getProducts($orderId);
			
			$video =[];
			foreach($products as $key=>$product) {
				foreach($product['downloadable_files'] as $innerKey=>$innerValue) {
					foreach ($innerValue['data'] as $dataKey => $value) {
						if($value['name'] == $videoId) {
							$video= $value;
							break;
						}
					}
				}
			}
			if($video) {
				include_once(APPPATH.'/libraries/VideoStream.php');
				$stream = new VideoStream(APPPATH."/downloads/".$video['mask']);
				$stream->start();
				exit();
			}
			// Track not found for the given order
			show_404();
			return;
		}
		// No track parameter — this is a streaming endpoint, not a browsable page
		show_404();
	}

	/**
	 * Calculate USPS shipping rates for checkout
	 */
	public function calculate_usps_rates() {
		$post = $this->input->post(null, true);
		$json = array();
		
		error_log("USPS Debug: POST data received: " . print_r($post, true));
		
		if (!empty($post) && isset($post['zip_code'])) {
			$zip_code = $post['zip_code'];
			
			// Use the correct cart method
			$products = $this->Cart->getProducts();
			error_log("USPS Debug: Cart products: " . print_r($products, true));
			
			$total_weight = 0;
			
			foreach ($products as $product) {
				// Use correct array keys for the cart
				$product_weight = $this->Product_model->getProductWeight($product['product_id']);
				$total_weight += ($product_weight * $product['quantity']);
			}
			
			error_log("USPS Debug: Total weight calculated: " . $total_weight);
			
			if ($total_weight <= 0) {
				$total_weight = 1.0;
				error_log("USPS Debug: Using default weight: " . $total_weight);
			}
			
			// Calculate total dimensions for all products in cart
			$dimensions = $this->Product_model->calculateTotalDimensions($products);
			$usps_rates = $this->Product_model->getUSPSMultipleRates($zip_code, $total_weight, $dimensions['length'], $dimensions['width'], $dimensions['height']);
			error_log("USPS Debug: USPS rates response: " . print_r($usps_rates, true));
			
			if ($usps_rates['status']) {
				$json['status'] = true;
				$json['rates'] = $usps_rates['rates'];
			} else {
				$json['status'] = false;
				$json['message'] = $usps_rates['message'];
			}
		} else {
			$json['status'] = false;
			$json['message'] = 'Invalid ZIP code provided';
		}
		
		error_log("USPS Debug: Final JSON response: " . print_r($json, true));
		echo json_encode($json);
	}

	/**
	 * Update shipping cost in cart
	 */
	public function update_shipping_cost() {
		$shipping_cost = $this->input->post('shipping_cost', true);
		
		if ($shipping_cost !== null) {
			// Save shipping cost to session
			$this->session->set_userdata('shipping_cost', (float)$shipping_cost);
			
			// Log for debugging
			error_log("USPS Shipping Cost Updated: " . (float)$shipping_cost);
			
			$response = array(
				'status' => true,
				'message' => 'Shipping cost updated successfully',
				'shipping_cost' => (float)$shipping_cost
			);
		} else {
			$response = array(
				'status' => false,
				'message' => 'Invalid shipping cost'
			);
		}
		
		echo json_encode($response);
	}

	public function update_shipping_country() {
		$country_id = $this->input->post('country_id', true);
		
		if ($country_id !== null) {
			// Save country ID to session for shipping calculations
			$this->session->set_userdata('shipping_country', (int)$country_id);
			
			$response = array(
				'status' => true,
				'message' => 'Shipping country updated successfully',
				'country_id' => (int)$country_id
			);
		} else {
			$response = array(
				'status' => false,
				'message' => 'Invalid country ID'
			);
		}
		
		echo json_encode($response);
	}

	public function debug_shipping_session() {
		$shipping_cost = $this->session->userdata('shipping_cost');
		$shipping_country = $this->session->userdata('shipping_country');
		
		$response = array(
			'shipping_cost' => $shipping_cost,
			'shipping_country' => $shipping_country,
			'session_id' => $this->session->session_id
		);
		
		echo json_encode($response);
	}

	/**
	 * V14: Track abandoned cart - called via AJAX when cart is updated
	 */
	public function track_cart() {
		$store = $this->Product_model->getSettings('store');
		if (empty($store['abandoned_cart_enabled'])) {
			echo json_encode(['status' => 'disabled']); return;
		}

		$this->load->model('Abandoned_cart_model');
		$email = $this->input->post('email');
		$user_id = $this->session->userdata('store_user_id');
		$session_id = $this->session->session_id;

		// Get current cart items
		$this->load->model('Cart');
		$cart_items = $this->Cart->getProducts();
		if (empty($cart_items)) {
			echo json_encode(['status' => 'empty']); return;
		}

		$this->Abandoned_cart_model->saveCart([
			'user_id' => $user_id ?: null,
			'session_id' => $session_id,
			'email' => $email,
			'cart_data' => json_encode($cart_items),
			'status' => 'abandoned'
		]);

		echo json_encode(['status' => 'tracked']);
	}

	/**
	 * V14: Recover abandoned cart from email link
	 */
	public function recover_cart($token = '') {
		if (empty($token)) { redirect(base_url('store')); return; }

		$this->load->model('Abandoned_cart_model');
		$cart = $this->Abandoned_cart_model->getByToken($token);

		if (!$cart) {
			$this->session->set_flashdata('error', 'Cart has expired or already recovered.');
			redirect(base_url('store'));
			return;
		}

		// Restore cart items
		$items = json_decode($cart->cart_data, true);
		if (!empty($items)) {
			$this->load->model('Cart');
			foreach ($items as $item) {
				// Re-add items to cart
				$product_id = $item['product_id'] ?? null;
				if ($product_id) {
					$this->Cart->add($product_id, $item['quantity'] ?? 1, $item['variation_id'] ?? 0);
				}
			}
		}

		$this->Abandoned_cart_model->markRecovered($token);
		redirect(base_url('store/cart'));
	}

	/**
	 * V14: Save order tracking information (AJAX)
	 */
	public function save_tracking() {
		$order_id = (int) $this->input->post('order_id');
		$tracking_number = $this->input->post('tracking_number', true);
		$carrier = $this->input->post('carrier', true);
		$action = $this->input->post('action', true);

		if (!$order_id) {
			echo json_encode(['success' => false, 'message' => 'Invalid order']); return;
		}

		$update = [];
		if ($tracking_number !== null) $update['shipping_tracking_number'] = $tracking_number;
		if ($carrier !== null) $update['shipping_carrier'] = $carrier;

		if ($action === 'shipped') {
			$update['shipped_at'] = date('Y-m-d H:i:s');
		} elseif ($action === 'delivered') {
			$update['delivered_at'] = date('Y-m-d H:i:s');
		}

		if (!empty($update)) {
			$this->db->where('id', $order_id);
			$this->db->update('order', $update);
		}

		echo json_encode(['success' => true, 'message' => __('admin.tracking_saved')]);
	}

	/**
	 * V14: AJAX instant product search
	 */
	public function instant_search() {
		$q = trim($this->input->get('q', true));
		$this->output->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($this->store_cart_payload->fragment_instant_search($q)));
	}

	/**
	 * V14: AJAX product filter
	 */
	public function ajax_filter() {
		$min_price = $this->input->get('min_price');
		$max_price = $this->input->get('max_price');
		$rating = $this->input->get('rating');
		$in_stock = $this->input->get('in_stock');
		$sort = $this->input->get('sort', true);
		$category = $this->input->get('category');
		$page = max(1, (int) $this->input->get('page'));
		$per_page = 12;

		$this->db->select('p.*, COALESCE(AVG(r.rating_number), 0) as avg_rating, COUNT(DISTINCT r.rating_id) as review_count');
		$this->db->from('product p');
		$this->db->join('rating r', 'r.products_id = p.product_id AND r.rating_status = 1', 'left');
		$this->db->where('p.on_store', 1);
		$this->db->where('p.product_status', 1);
		$this->db->where('p.is_campaign_product', 0);

		if ($category) {
			$this->db->join('product_categories pc', 'pc.product_id = p.product_id');
			$this->db->where('pc.category_id', $category);
		}
		if ($min_price !== null && $min_price !== '') {
			$this->db->where('p.product_price >=', (float) $min_price);
		}
		if ($max_price !== null && $max_price !== '') {
			$this->db->where('p.product_price <=', (float) $max_price);
		}
		if ($in_stock) {
			$this->db->group_start();
			$this->db->where('p.product_quantity >', 0);
			$this->db->or_where('p.product_quantity', -1);
			$this->db->group_end();
		}

		$this->db->group_by('p.product_id');

		if ($rating) {
			$this->db->having('avg_rating >=', (float) $rating);
		}

		switch ($sort) {
			case 'price_low': $this->db->order_by('p.product_price', 'ASC'); break;
			case 'price_high': $this->db->order_by('p.product_price', 'DESC'); break;
			case 'popular': $this->db->order_by('p.product_sales_count', 'DESC'); break;
			case 'rating': $this->db->order_by('avg_rating', 'DESC'); break;
			default: $this->db->order_by('p.product_id', 'DESC'); break;
		}

		$this->db->limit($per_page, ($page - 1) * $per_page);
		$results = $this->db->get()->result_array();

		echo json_encode(['products' => $results, 'page' => $page]);
	}

	/**
	 * V14: AJAX quick view product data
	 */
	public function quick_view($product_id = 0) {
		$payload = $this->store_cart_payload->fragment_quick_view((int) $product_id);
		$this->output->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($payload));
	}

	/**
	 * V14: Get product recommendations (co-purchase based)
	 */
	public function recommendations($product_id = 0) {
		$items = $this->store_cart_payload->fragment_recommendations((int) $product_id);
		$this->output->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($items));
	}

	/**
	 * V14: Get order messages (AJAX)
	 */
	public function order_messages($order_id = 0) {
		$order_id = (int) $order_id;
		if (!$order_id) { echo json_encode([]); return; }

		$messages = $this->db->order_by('created_at', 'ASC')
			->get_where('store_messages', ['order_id' => $order_id])
			->result_array();

		echo json_encode($messages);
	}

	/**
	 * V14: Send order message (AJAX)
	 */
	public function send_order_message() {
		$order_id = (int) $this->input->post('order_id');
		$message = trim($this->input->post('message', true));
		$sender_type = $this->input->post('sender_type', true) ?: 'customer';

		if (!$order_id || empty($message)) {
			echo json_encode(['success' => false]); return;
		}

		$user_id = $this->session->userdata('store_user_id') ?: $this->session->userdata('userid');

		$this->db->insert('store_messages', [
			'order_id' => $order_id,
			'sender_id' => (int) $user_id,
			'sender_type' => $sender_type,
			'message' => $message,
			'created_at' => date('Y-m-d H:i:s')
		]);

		echo json_encode(['success' => true]);
	}

}
