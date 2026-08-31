<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use App\MembershipPlan;
use App\MembershipUser;
use App\Slug;
use App\User;
class Usercontrol extends MY_Controller {
    function __construct()
    {
        parent::__construct();

        // =============================== 
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        
        // Handle OPTIONS preflight request immediately
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // No further processing needed
        }
        // ===============================
        
        $this->load->model('user_model', 'user');
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->load->model('Withdrawal_payment_model');
        $this->load->helper('share');
        $this->load->helper('url');
        $this->load->library('user_agent');
        $this->load->model('IntegrationModel');
        $this->load->model('Common_model');
        $this->load->model('Tutorial_model');
        $this->load->driver('cache', array('adapter' => 'file'));


        $session = $this->session->userdata('user_session'); // Check if a user session exists
        $path_info = (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : (!empty($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : '');

        if (!$session && $this->router->class != 'usercontrol' && $this->router->method != 'index') {
            redirect('usercontrol');
        } else if ($session && ($path_info == '/usercontrol' || $path_info == '/usercontrol/')) {
            redirect('usercontrol/dashboard');
        }
        
        // Timeout logic here
        $site_setting_timeout = $this->Product_model->getSettings('site', 'user_session_timeout');
        $timeout = (isset($site_setting_timeout['user_session_timeout']) && is_numeric($site_setting_timeout['user_session_timeout']) && ((int)$site_setting_timeout['user_session_timeout']) >= 60) ? (int)$site_setting_timeout['user_session_timeout'] : 1800;

        $this->load->vars(array('timeout' => $timeout));

        if ($this->session->has_userdata('timestamp') && (time() - $this->session->userdata('timestamp')) > $timeout) {
            $this->session->sess_destroy();
            redirect('usercontrol/dashboard');
        } else {
            $this->session->set_userdata('timestamp', time());
        }
    }

	public function my_vendor_panel() {
		$userdetails = $this->userdetails();
		
		if(empty($userdetails)) redirect('usercontrol/dashboard');
		
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || ((int)$market_vendor['marketvendorstatus'] != 1 && ((int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1)))
			redirect('usercontrol/dashboard');
		
		$data['userdetails'] = $userdetails;
		$data['store_setting'] = $store_setting = $this->Product_model->getSettings('store');
		$data['vendor_setting'] = $vendor_setting = $this->Product_model->getSettings('vendor');
		$data['market_vendor'] = $market_vendor;

		$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
		$data['hcurrency'] = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'user'));
		if($data['hcurrency'])
			$data['fun_c_format'] =$fun_c_format = 'c_format_nosym';
		else
			$data['fun_c_format'] =$fun_c_format = 'c_format';

		$data['store_details'] = $this->db->query('SELECT store_name, store_slug, store_meta FROM users WHERE id='.$data['userdetails']['id'])->row_array();

		$this->load->model('Total_model');
		$data['user_totals'] = $this->Total_model->getUserTotals((int)$data['userdetails']['id']);
		$data['total_deposited'] = $this->db->query('SELECT SUM(vd_amount) as total FROM vendor_deposit WHERE vd_status=1 AND vd_user_id='.$data['userdetails']['id'])->row()->total;

		// S2S Tracking Summary for vendor dashboard
		$vendor_id = (int)$data['userdetails']['id'];
		$data['vendor_s2s_summary'] = $this->db->query("
			SELECT 
				COUNT(*) as total_s2s_orders,
				COALESCE(SUM(io.total), 0) as s2s_revenue,
				COALESCE(SUM(io.commission), 0) as s2s_commission
			FROM integration_orders io
			INNER JOIN integration_tools it ON io.ads_id = it.id
			WHERE io.script_name = 's2s' AND io.status > 0 AND it.vendor_id = {$vendor_id}
		")->row_array();
		$data['vendor_s2s_campaigns'] = $this->db->query("
			SELECT COUNT(*) as cnt FROM integration_tools WHERE s2s_enabled = 1 AND vendor_id = {$vendor_id}
		")->row()->cnt;
		$data['vendor_pixel_orders'] = $this->db->query("
			SELECT COUNT(*) as cnt FROM integration_orders io
			INNER JOIN integration_tools it ON io.ads_id = it.id
			WHERE io.script_name != 's2s' AND io.status > 0 AND it.vendor_id = {$vendor_id}
		")->row()->cnt;

		// V14: 7-day trend data for sparkline charts
		$data['trends'] = $this->Total_model->get7DayTrends(null, (int)$vendor_id);

		$this->view($data, 'vendor/index','usercontrol');
	}

	public function approval_status() {
		$data['userdetails'] = $this->Product_model->userdetails('user', true); 
		$data['userdashboard_settings'] = getUserDashboardSettings();
		if($userdetails['reg_approved'] == 1) {
			redirect('usercontrol/dashboard');die;
		} else {
			$data['notcheckapproval'] = 1;
			$data['notcheckmember'] = 1;
			$this->view($data,"users/approval_status",'usercontrol');
		}
	}

public function duplicateProduct($product_id) {
    $userdetails = $this->userdetails();

    // Redirect if user is not authenticated
    if(empty($userdetails)) {
        redirect('usercontrol/dashboard');
        return;
    }

    // Get the user's current plan details
    $userPlan = App\MembershipUser::with("plan")
                                  ->where('is_active', 1)
                                  ->where('user_id', $userdetails['id'])
                                  ->first();

    $plan_product_count = $userPlan->plan->product;
    $vendor_product_count = $this->Product_model->countByField('product_affiliate', 'user_id', $userdetails['id']);

    // Check if the user has reached the maximum allowed product count
    if ($this->hasReachedPlanLimit($plan_product_count, $vendor_product_count)) {
        $this->session->set_flashdata('error', __('user.reached_maximum_limit_package_upgrade') . '<a href="' . base_url('usercontrol/purchase_plan') . '"> ' . __('user.here') . '</a>');
    } else {
        // Duplicate the product
        $status_review = $this->shouldReviewBeforePublishing();
        $this->Product_model->duplicateProduct($product_id, $status_review);
        $this->session->set_flashdata('success', __('user.product_duplicate_successfully'));
    }

    // Redirect to the store products page
    redirect(base_url('usercontrol/store_products'));
}

private function hasReachedPlanLimit($plan_product_count, $vendor_product_count) {
    return !empty($plan_product_count) && $vendor_product_count >= $plan_product_count;
}

private function shouldReviewBeforePublishing() {
    $market_vendor = $this->Product_model->getSettings('market_vendor', 'marketaddnewstoreproduct');
    return $market_vendor['marketaddnewstoreproduct'] ? 1 : 0;
}


	public function integration(){
		$userdetails = $this->userdetails();

		if(empty($userdetails)) redirect('usercontrol/dashboard');

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$market_vendor['marketvendorstatus'] != 1)
			redirect('usercontrol/dashboard');

		$data['integration_modules'] = $this->modules_list('addons');

		$this->view($data, 'integration/index','usercontrol');
	}

	private function modules_list($requestingFor = null){
		
		if($requestingFor == null) {
			
			$integration_modules['general_integration'] = array(
				'name' => "General Integration",
				'image' => base_url('assets/integration/general_integration-logo.png'),
			);
			

			$integration_modules['woocommerce'] = array(
				'name' => "WooCommerce",
				'image' => base_url('assets/integration/woocommerce-logo.png'),
			);

			$integration_modules['prestashop'] = array(
				'name' => "PrestaShop",
				'image' => base_url('assets/integration/prestashop-logo.png'),
			);

			$integration_modules['opencart'] = array(
				'name' => "Opencart",
				'image' => base_url('assets/integration/opencart-logo.png'),
			);

			$integration_modules['magento'] = array(
				'name' => "Magento",
				'image' => base_url('assets/integration/magento-logo.png'),
			);

			$integration_modules['shopify'] = array(
				'name' => "Shopify",
				'image' => base_url('assets/integration/shopify-logo.png'),
			);

			$integration_modules['bigcommerce'] = array(
				'name' => "Big Commerce",
				'image' => base_url('assets/integration/big-commerce.png'),
			);

			$integration_modules['paypal'] = array(
				'name' => "Paypal",
				'image' => base_url('assets/integration/paypal.jpg'),
			);

			$integration_modules['oscommerce'] = array(
				'name' => "osCommerce",
				'image' => base_url('assets/integration/oscommerce.jpg'),
			);

			$integration_modules['zencart'] = array(
				'name' => "Zen Cart",
				'image' => base_url('assets/integration/zencart.png'),
			);

			$integration_modules['xcart'] = array(
				'name' => "XCART",
				'image' => base_url('assets/integration/xcart.jpg'),
			);

			$integration_modules['laravel'] = array(
				'name' => "Laravel",
				'image' => base_url('assets/integration/laravel.png'),
			);

			$integration_modules['cakephp'] = array(
				'name' => "Cake PHP",
				'image' => base_url('assets/integration/cakephp.png'),
			);

			$integration_modules['codeigniter'] = array(
				'name' => "CodeIgniter",
				'image' => base_url('assets/integration/codeIgniter.png'),
			);
		}

		$integration_modules['wp_user_register'] = array(
			'name' => "Wordpress/Woocommerce registration bridge",
			'image' => base_url('assets/integration/WordpressWoocommerceRegistrationBridge.png'),
		);
		

		$integration_modules['wp_forms'] = array(
			'name' => "WordPress Forms",
			'image' => base_url('assets/integration/wpforms.png'),
		);
		$integration_modules['postback'] = array(
			'name' => "Postback URL",
			'image' => base_url('assets/integration/postback.png'),
		);

		$integration_modules['show_affiliate_id'] = array(
			'name' => "Show Affiliate ID",
			'image' => base_url('assets/integration/show-affiliate-id.png'),
		);

		$integration_modules['wp_show_affiliate_id'] = array(
			'name' => "Wordpress Show Affiliate ID",
			'image' => base_url('assets/integration/wp-show-affiliate-id.jpg'),
		);

		$integration_modules['affiliate_register_api'] = array(
			'name' => "Affiliate Register API",
			'image' => base_url('assets/integration/affiliate_register_api.jpg'),
		);

		$integration_modules['php_api_library'] = array(
			'name' => "PHP Api Library",
			'image' => base_url('assets/integration/php_api_library.jpg'),
		);

		return $integration_modules;
	}

	public function instructions($module_key){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$data['integration_modules'] = $this->modules_list();
		$data['module_key'] = $module_key;

		

		$data['action_codes'] = $this->db->select('integration_tools.action_code')
		->from('integration_tools')
		->where("tool_type",'action')
		->where("status",1)
		->get()
		->result_array();

		$data['general_codes'] = $this->db->select('integration_tools.general_code')
		->from('integration_tools')
		->where("tool_type",'general_click')
		->where("status",1)
		->get()
		->result_array();
		$data['module'] = $data['integration_modules'][$module_key];

		$this->view($data, 'integration/instructions','usercontrol');
	}

public function change_language($language_id = null) {
    if(empty($language_id)) {
        show_404();
        return;
    }

    if(!is_numeric($language_id)) {
        show_404();
        return;
    }

    $this->db->where('id', $language_id);
    $query = $this->db->get('language');
    $language = $query->row_array();

    if($language) {
        $_SESSION['userLang'] = $language_id;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        show_404();
    }
}


public function change_currency($currency_code = null){
    if(empty($currency_code)) {
        show_404();
        return;
    }

    $this->db->where('code', $currency_code);
    $query = $this->db->get('currency');
    $currency = $query->row_array();

    if($currency) {
        $_SESSION['userCurrency'] = $currency_code;
        $_SESSION['userDecimalPlace'] = $currency['decimal_place'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        show_404();
    }
}


	// V14: Save user theme preference (dark/light mode)
	public function save_theme() {
		$this->output->set_content_type('application/json');
		$userdetails = $this->userdetails();
		if (!$userdetails) {
			echo json_encode(['status' => 0, 'message' => 'Unauthorized']);
			return;
		}
		$theme = $this->input->post('theme', true);
		if (!in_array($theme, ['light', 'dark'])) {
			$theme = 'light';
		}
		$this->db->where('id', $userdetails['id']);
		$this->db->update('users', ['theme_preference' => $theme]);
		echo json_encode(['status' => 1, 'theme' => $theme]);
	}

	public function getSiteSetting(){return $this->Product_model->getSettings('site');}

	public $loginUser = [];

	public function userdetails(){
	     
	 	 	if (isset($this->session) && $this->session->userdata('client') !== FALSE && $this->session->userdata('client')['type']=='user') 
			{  
				$this->session->unset_userdata('administrator');
				$this->loginUser = $this->db->query("SELECT * FROM users WHERE id=". $this->session->userdata('client')['id'])->row_array();
				
				$this->session->set_userdata(array('user'=>$this->loginUser));
				return $this->loginUser;

			}
 			else if (isset($this->session) && $this->session->userdata('user_type') !== FALSE && $this->session->userdata('user_type')=='admin') 
			{
				if(!$this->loginUser){
					$u = $this->session->userdata('user');

					if($u){
						$this->loginUser = $this->db->query("SELECT * FROM users WHERE id=". $u['id'])->row_array();
					}
				}
				$this->session->set_userdata(array('user'=>$this->loginUser));
				return $this->loginUser;
			}
			else { 

				if(!$this->loginUser){
					$u = $this->session->userdata('user');

					if($u){
						$this->loginUser = $this->db->query("SELECT * FROM users WHERE id=". $u['id'])->row_array();
					}
				}
				$this->session->set_userdata(array('user'=>$this->loginUser));
				return $this->loginUser;
 
  			}  
 
	}

	public function myreferal_ajax(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$data = $this->Product_model->getMyUnder($userdetails['id']);

		echo json_encode($data);die;
	}

	public function resetpassword($token = ''){
		$token = trim((string) $token);
		if ($token === '') {
			$token = trim((string) $this->input->get('token'));
			if ($token === '') {
				foreach ($_GET as $k => $v) {
					$candidate = strtolower(preg_replace('/[^a-f0-9]/', '', trim((string) $v)));
					if (strlen($candidate) === 32) {
						$token = $candidate;
						break;
					}
				}
			}
		}
		$hexOnly = strtolower(preg_replace('/[^a-f0-9]/', '', $token));
		if (strlen($hexOnly) === 32) {
			$token = $hexOnly;
		}

		$post = $this->input->post(null,true);
		$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');

		if (defined('MAIL_PREVIEW_RESET_TOKEN') && MAIL_PREVIEW_RESET_TOKEN !== '' && $token === MAIL_PREVIEW_RESET_TOKEN) {
			$data['SiteSetting'] = $this->Product_model->getSettings('site');
			$data['user_type'] = 'user';
			$data['redirect_url'] = base_url('login');
			if (isset($post['conf_password'])) {
				$this->session->set_flashdata('success', __('user.mail_preview_reset_notice'));
				redirect(base_url('login'));
				return;
			}
			$this->load->view('resetpassword', $data);
			return;
		}

		$tok  = $this->db->query("SELECT * FROM password_resets WHERE token = ?", [$token])->row();

		if($tok){
			$data['SiteSetting'] =$this->Product_model->getSettings('site');
			$userRow = $this->db->query("SELECT type FROM `users` WHERE email = ?", [$tok->email])->row();
			$data['user_type'] = $userRow ? $userRow->type : 'user';
			if($data['user_type'] == 'client'){
				$data['redirect_url'] = base_url('store/login');
			} else if($data['user_type'] == 'admin'){
				$data['redirect_url'] = base_url('admin');
			} else {
				$data['redirect_url'] = base_url('login');
			}
			if (isset($post['conf_password'])) {
				$password = $this->input->post('password', FALSE);
				$conf_password = $this->input->post('conf_password', FALSE);
				if($password === $conf_password){
					$res = array('password'=>sha1($password));
					$this->db->where('email',$tok->email);
					$this->db->update('users',$res);
					$this->db->query("DELETE FROM password_resets WHERE email = ?", [$tok->email]);
					$this->session->set_flashdata('success' , __('user.password_reset_successfully_successfully'));
					redirect($data['redirect_url']);
				} else {
					$this->session->set_flashdata('error',__('user.confirm_password_not_match'));
					redirect(base_url('resetpassword/' . $token));
				}
			}
			$this->load->view('resetpassword', $data);
		} else {
			$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
			$data['SiteSetting'] = $this->Product_model->getSettings('site');
			$data['error_message'] = __('user.reset_token_expired');
			$this->load->view('resetpassword_expired', $data);
		}
	}
	public function index(){ redirect('/', 'refresh'); }

	public function notification(){
		if(!$this->userdetails()){ redirect('/login', 'refresh'); }
		$this->load->helper('utility');
		$post = $this->input->post(null,true);

		if (isset($post['delete_ids'])) {
			$delete_ids_array = array_filter(array_map('intval', $post['delete_ids']));
			if (!empty($delete_ids_array)) {
				$placeholders = implode(',', array_fill(0, count($delete_ids_array), '?'));
				$this->db->query("DELETE FROM notification WHERE notification_id IN ({$placeholders})", $delete_ids_array);
			}
			echo json_encode(array());
			die;
		}
		
		$data['title'] = 'Notification';
		$per_page = 10;
		$page = ($this->uri->segment(3)) ? (int)$this->uri->segment(3) : 1;
		$offset = ($page - 1) * $per_page;
		
		$notification = $this->user->getAllNotificationPaging('user', $this->userdetails()['id'], $per_page, $offset);
		$total_rows = $notification['total'];
		
		$pagination_result = easy_pagination(
			base_url('usercontrol/notification'),
			$total_rows,
			$offset,
			['per_page' => $per_page, 'alignment' => 'end', 'size' => 'sm']
		);
		
		$data['pagination'] = $pagination_result['html'];
		$data['notifications'] = $notification['notifications'];
		$data['user_id'] = $this->userdetails()['id'];
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/dashboard/notification', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
		$this->load->helper('geolocation');
		$geolocation = new Geolocation_helper();
		return $geolocation->ip_info($ip, $purpose, $deep_detect);
	}
	public function getState(){
		$this->load->model('User_model');
		$country_id = $this->input->post('country_id',true);
		$states = $this->User_model->getState($country_id);
		echo json_encode($states);
		die;
	}
	public function check_ven_product_limit($vendor_id = 0){
		$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$vendor_id)->first();
		$plan_product_count = $userPlan->plan->product;
		if(! empty($plan_product_count)) {
		$vendor_product_count = $this->Product_model->countByField('product_affiliate','user_id',$vendor_id);

		if ($vendor_id != 0) {
			$productlist = $this->Product_model->getAllVendorProducts($vendor_id, 'vendor');
			$i = 0;
			foreach ($productlist as $product) {
				if ($product['seller_id'] == $vendor_id) {
					$i++;
					if ($i > $plan_product_count) {
						$sql = "UPDATE `product` SET `on_store` = '0', `product_status` = '2' WHERE `product_id` = '".$product['product_id']."'";

						$query = $this->db->query($sql);
					}
				}	
			}	

			return "1";
			}else{
			echo "Vendor ID required!";
			}
		}
		
	}


	public function check_ven_campaign_limit($vendor_id = 0){
		$this->load->model('IntegrationModel');
		$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$vendor_id)->first();
		$plan_campaign_count = $userPlan->plan->campaign;
		if(! empty($plan_campaign_count)) {
			$vendor_campaign_count = $this->Product_model->countByField('integration_tools','vendor_id',$vendor_id);

			if ($vendor_id != 0) {
				$toollist = $this->IntegrationModel->getVendorProgramTools($vendor_id);
				$i = 0;
				foreach ($toollist as $tool) {
					if ($tool['vendor_id'] == $vendor_id) {
						$i++;
						if ($i > $plan_campaign_count) {
							$sql = "UPDATE `integration_tools` SET `status` = '0' WHERE `id` = '".$tool['id']."'";

							$query = $this->db->query($sql);
						}
					}	
				}

				return "1";
			}else{
				echo "Vendor ID required!";
			}
		}
	}

	public function auth($action){
		$json = array();
		$post = $this->input->post(null,true);

		if ($action == 'login') 
		{
			$username = $this->input->post('username',true);
			$password = $this->input->post('password',true);

			$checking_key = (isset($post['type']) && $post['type'] == 'admin') ? 'admin_login' : 'affiliate_login';

			$googlerecaptcha = $this->Product_model->getSettings('googlerecaptcha');

			if (!empty($googlerecaptcha[$checking_key])) {
			    // Try both possible field names
			    $recaptcha_response = $post['g-recaptcha-response'] ?? $post['captch_response'] ?? '';
			    
			    if (empty($recaptcha_response)) {
			        $json['errors']['captch_response'] = 'Please complete the reCAPTCHA verification.';
			    } else {
			        $result = validate_recaptcha(
			            $recaptcha_response,
			            $googlerecaptcha['secretkey']
			        );

			        if (!$result['success']) {
			            $json['errors']['captch_response'] = $result['error'];
			        }
			    }
			}

			$post = $this->input->post(null,true);
			if(count($json)==0 || count($json['errors']) == 0 ){
				if($this->authentication->login($username, $password)){
					$user_details_array=$this->user->login($username);
					if(!empty($user_details_array['username']) && sha1($password) == $user_details_array['password']){

						if($user_details_array['status']){
							if($user_details_array['type'] == 'user' && !isset($post['type'])){
								$this->user->update_user_login($user_details_array['id']);
								$this->session->set_userdata(array('user'=>$user_details_array));
								
								if($user_details_array['reg_approved'] == 1) {
									$this->session->set_userdata(array('client'=>$user_details_array));
								}

								$this->Product_model->checkJumpedUserWithId($user_details_array['id']);
								if ($user_details_array['is_vendor'] == '1') {
									$this->check_ven_product_limit($user_details_array['id']);
									$this->check_ven_campaign_limit($user_details_array['id']);
								}
								$json['redirect'] = base_url('usercontrol/dashboard');

							}

							// Admin login + OTP
							else if ($user_details_array['type'] == 'admin' && isset($post['type']) && $post['type'] == 'admin') {

								$otp_setting = $this->Product_model->getSettings('security');
								$otp_enabled = isset($otp_setting['otp_admin_login']) && $otp_setting['otp_admin_login'] == '1';
								$max_attempts = isset($otp_setting['otp_admin_max_attempts']) ? (int)$otp_setting['otp_admin_max_attempts'] : 3;
								$cooldown_seconds = isset($otp_setting['otp_admin_cooldown_seconds']) ? (int)$otp_setting['otp_admin_cooldown_seconds'] : 180;

								if ($otp_enabled) {
								    $otp_last_time = $this->session->userdata('otp_last_time') ?? time();
								    $otp_attempts = $this->session->userdata('otp_attempts') ?? 0;

								    $elapsed = time() - $otp_last_time;

								    if ($elapsed > $cooldown_seconds) {
							            $this->session->unset_userdata('otp_attempts');
							            $this->session->unset_userdata('otp_last_time');
							            $otp_attempts = 0;
							        }

									if ($otp_attempts >= $max_attempts) {
									    $remaining_seconds = max(0, $cooldown_seconds - (time() - $otp_last_time));
									    $json['errors'] = ['otp_limit' => $remaining_seconds];
									    echo json_encode($json);
									    return;
									}


							        // Proceed
							        $otp_code = rand(100000, 999999);
							        $this->session->set_userdata('otp_user', $user_details_array);
							        $this->session->set_userdata('otp_code', $otp_code);
							        $this->session->set_userdata('otp_attempts', $otp_attempts + 1);
							        $this->session->set_userdata('otp_last_time', time());

							        $this->load->model('Mail_model');
							        $this->Mail_model->send_otp($user_details_array['email'], $otp_code);

							        $json['redirect'] = base_url('auth/admin/otp_verify');
							    } else {
							        $this->user->update_user_login($user_details_array['id']);
							        $user_details_array = admin_user_with_permissions($user_details_array);
							        $this->session->set_userdata(['administrator' => $user_details_array]);
							        $json['redirect'] = base_url('admincontrol/dashboard');
							    }
							}

							else if($user_details_array['type'] == 'client' && !isset($post['type'])){
								$this->user->update_user_login($user_details_array['id']);
								$this->session->set_userdata(array('client'=>$user_details_array));
								$l = $this->session->userdata('login_data');
								if($l['refid'] && $l['product_slug'] && $l['user_id']){
									$json['redirect'] = base_url('product/payment/'. $l['product_slug'].'/'.$l['user_id']);
								}else if($this->session->userdata('refer_id')){
									$json['redirect'] = base_url('store/'. base64_encode($this->session->userdata('refer_id')));
								}else{
									$json['redirect'] = base_url('store/profile/');
								}
							}else {
								$json['errors']['username'] = __('user.invalid_valid_user');
							}
						} else{
							$json['errors']['username'] = __('user.account_block_message');
						}
					}
				} else {
					$json['errors']['password'] = __('user.invalid_credentials');
				}
			}

		} else if ($action == 'register') {
			$refid = isset($post['refid']) ? $post['refid'] : '';
			$post['affiliate_id'] = !empty($refid) ? base64_decode($refid) : 0;
			if($this->userdetails()){
				$json['redirect'] = base_url('usercontrol/dashboard');
			} else {

				$this->load->library('form_validation');
				$this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
				$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
				$this->form_validation->set_rules('username', 'Username', 'required|trim');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
				$this->form_validation->set_rules('terms', 'Terms and Condition', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim', array('required' => '%s is required'));
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
				$this->form_validation->set_rules('address', 'Address', 'required|trim|xss_clean', array('required' => '%s is required'));
				$this->form_validation->set_rules('state', 'State', 'required', array('required' => '%s is required'));
				$this->form_validation->set_rules('paypal_email', 'Payal Email', 'required|valid_email|xss_clean', array('required' => '%s is required'));
				$this->form_validation->set_rules('phone_number', 'Phone Number', 'required|regex_match[/^[0-9]{10}$/]', array('required' => '%s is required'));
				$this->form_validation->set_rules('alternate_phone_number', 'Alternate Phone Number', 'required|regex_match[/^[0-9]{10}$/]', array('required' => '%s is required'));
				if ($this->form_validation->run() == FALSE) {
					$json['errors'] = $this->form_validation->error_array();
				} else {
					$checkEmail = $this->db->query("SELECT id FROM users WHERE email like ". $this->db->escape($this->input->post('email',true)) ." ")->num_rows();
					if($checkEmail > 0){ $json['errors']['email'] = "Email Already Exist"; }
					$checkUsername = $this->db->query("SELECT id FROM users WHERE username like ". $this->db->escape($this->input->post('username',true)) ." ")->num_rows();
					if($checkUsername > 0){ $json['errors']['username'] = "Username Already Exist"; }

					if(!isset($post['terms'])){
						$json['warning'] = __('user.accept_our_affiliate_policy');
					}

					if(!isset($json['errors'])){

						$user_type = 'user';
						$geo = $this->ip_info();

						$refid = !empty($refid) ? base64_decode($refid) : 0;
						$commition_setting = $this->Product_model->getSettings('referlevel');

						$disabled_for = json_decode( (isset($commition_setting['disabled_for']) ? $commition_setting['disabled_for'] : '[]'),1); 
						if((int)$commition_setting['status'] == 0){ $refid  = 0; }
						else if((int)$commition_setting['status'] == 2 && in_array($refid, $disabled_for)){ $refid = 0; }

						$data = $this->user->insert(array(
							'firstname'                 => $this->input->post('firstname',true),
							'lastname'                  => $this->input->post('lastname',true),
							'email'                     => $this->input->post('email',true),
							'username'                  => $this->input->post('username',true),
							'password'                  => sha1($this->input->post('password',true)),
							'refid'                     => $refid,
							'type'                      => $user_type,
							'Country'                   => $this->input->post('country',true),
							'City'                      => (string)$geo['city'],
							'phone'                     => $this->input->post('phone_number',true),
							'twaddress'                 => $this->input->post('address',true),
							'address1'                  => '',
							'address2'                  => '',
							'ucity'                     => '',
							'ucountry'                  => '',
							'state'                     => $this->input->post('state',true),
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
							'PhoneNumber'               => $this->input->post('alternate_phone_number',true),
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
						));

						$this->db->insert("paypal_accounts", array(
							'paypal_email' => $this->input->post('paypal_email',true),
							'user_id' => $data,
						));
						$post['refid'] = !empty($refid) ? base64_decode($refid) : 0;
						if(!empty($data) && $user_type == 'user'){
							$notificationData = array(
								'notification_url'          => '/userslist/'.$data,
								'notification_type'         =>  'user',
								'notification_title'        =>  __('user.new_user_registration'),
								'notification_viewfor'      =>  'admin',
								'notification_actionID'     =>  $data,
								'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' register as a '. $this->input->post("affliate_type",true) . ' on affiliate Program on '.date('Y-m-d H:i:s'),
								'notification_is_read'      =>  '0',
								'notification_created_date' =>  date('Y-m-d H:i:s'),
								'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
							);
							$this->insertnotification($notificationData);
							if ($post['affiliate_id'] > 0) {
								$notificationData = array(
									'notification_url'          => '/managereferenceusers',
									'notification_type'         =>  'user',
									'notification_title'        =>  __('user.new_user_registration_under_your'),
									'notification_viewfor'      =>  'user',
									'notification_view_user_id' =>  $post['affiliate_id'],
									'notification_actionID'     =>  $data,
									'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' has been register under you on '.date('Y-m-d H:i:s'),
									'notification_is_read'      =>  '0',
									'notification_created_date' =>  date('Y-m-d H:i:s'),
									'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
								);
								$this->insertnotification($notificationData);
							}
							$json['success']  =  __('user.youve_successfully_registered');
							$user_details_array=$this->user->login($this->input->post('username',true));
							$this->load->model('Mail_model');
							
							$this->user->update_user_login($user_details_array['id']);
							$this->Mail_model->send_register_mail($post,__('user.welcome_to_new_user_registration'));
							if ($user_type == 'user') {
								$this->session->set_userdata(array('user'=>$user_details_array));
								$json['redirect'] = base_url('usercontrol/dashboard');
							} else {
								$this->session->set_userdata(array('client'=>$user_details_array));
								$json['redirect'] = base_url('clientcontrol/dashboard');
							}
						}
					}
				}
			}
		}

		else if ($action == 'forget') {
			$email = $this->input->post('email',true); 
			if(isset($email) && $email!="" )
			{
				$data = $this->db->query("SELECT * FROM users WHERE email LIKE ?", [$email])->row();
				if ($data) {
					$token = md5(uniqid(rand(), true));
					$resetlink = base_url('resetpassword/'. $token);
					$this->db->query("DELETE FROM password_resets WHERE email LIKE ?", [$email]);
					$this->db->query("INSERT INTO password_resets SET email = ?, token = ?", [$email, $token]);
					$this->load->model('Mail_model');
					$this->Mail_model->send_forget_mail($data, $resetlink);
					$json['success'] = __('user.password_reset_instructions_will_be_sent_to_your_registered_email_address');
				}
				else
				{
					$json['errors']['email'] = __('user.email_address_not_found');
				}
			}
			else
				$json['errors']['email'] = __('user.email_address_not_found');	
		}

		echo json_encode($json);
	}

	public function insertnotification($postData = null){
		if(!empty($postData)){
			$data['custom'] = $this->Product_model->create_data('notification', $postData);
		}
	}
	public function changePassword(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$post = $this->input->post(null,true);

		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('old_pass', __('user.old_password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('password', __('user.new_password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
			} else {
				$admin = $this->db->from('users')->where('id',$userdetails['id'])->get()->row_array();
				if($admin['password'] == sha1($post['old_pass'])){
					$res = array('password'=>sha1($post['password']));
					$this->db->where('id',$admin['id']);
					$this->db->update('users',$res);
					$this->session->set_flashdata(array('flash' => array('success' => __('user.user_profile_updated_successfully'))));
					redirect('usercontrol/changePassword', 'refresh');
				}else{
					$this->session->set_flashdata(array('flash' => array('error' => __('user.old_password_not_matched'))));
					redirect('usercontrol/changePassword');
				}
			}
		}
		

		$data['title'] = __('user.change_password');
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/dashboard/change-password', $data);
		$this->load->view('usercontrol/includes/footer', $data);
		

	}

	
	public function dashboardlist(){
		$data['title'] = __('user.user_dashboard');
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/dashboard/dashboardlist', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}


	public function dashboard(){
		$userdetails = $this->userdetails();

		if(empty($userdetails)){ redirect('/login'); }

		$this->load->model('Total_model');

		// Currency formatting
		$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
		$data['hcurrency'] = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'user'));
		if($data['hcurrency']) {
			$data['fun_c_format'] = $fun_c_format = 'c_format_nosym';
		} else {
			$data['fun_c_format'] = $fun_c_format = 'c_format';
		}

		// Essential data for dashboard
		$data['title'] = __('user.user_dashboard');
		$_userdash = getUserDashboardSettings();
		$data['populer_users'] = isShowUserControlParts($_userdash['top_affiliate'] ?? []) ? $this->Product_model->getPopulerUsers(array("limit" => 10)) : [];
		$data['user_totals'] = $this->Total_model->getUserTotals((int)$userdetails['id']);

		// Referral settings
		$referlevelSettings = $this->Product_model->getSettings('referlevel');
		$data['refer_status'] = reh_fetchReffererStatus($referlevelSettings, $userdetails['id']);

		// Store settings
		$data['store'] = $this->Product_model->getSettings('store');

	// User slugs
	$data['register_slug'] = $this->db->select('slug')->from('slugs')->where('user_id', (int)$userdetails['id'])->where('type', 'register')->get()->row()->slug ?? '';
	$data['store_slug'] = $this->db->select('slug')->from('slugs')->where('user_id', (int)$userdetails['id'])->where('type', 'store')->get()->row()->slug ?? '';
	$vendor_store_slug_from_table = $this->db->select('slug')->from('slugs')->where('user_id', (int)$userdetails['id'])->where('type', 'vendor_store')->get()->row()->slug ?? '';
	$data['vendor_store_slug'] = !empty($vendor_store_slug_from_table) ? $vendor_store_slug_from_table : ($userdetails['store_slug'] ?? '');

		// Dashboard settings
		$data['userdashboard_settings'] = $this->Common_model->getUserDashboardSettings();
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		$data['marketvendorpanelmode'] = $market_vendor['marketvendorpanelmode'] ?? 0;

		// Welcome popup settings
		$data['welcome'] = $this->Product_model->getSettings('welcome');

		// Load social share modal
		$this->load->library("socialshare");
		$data['social_share_modal'] = $this->socialshare->get_dynamic_social_share_btns();

		// V14: 7-day trend data for sparkline charts
		$data['trends'] = $this->Total_model->get7DayTrends((int)$userdetails['id']);

		// V15: Recent wallet activity for dashboard feed (5 rows, no joins)
		$data['recent_wallet'] = $this->db->query(
			'SELECT amount, type, status, created_at FROM wallet WHERE user_id = ? AND amount > 0 ORDER BY id DESC LIMIT 5',
			[(int)$userdetails['id']]
		)->result_array();

		$this->view($data,'dashboard/dashboard', 'usercontrol');
	}

	public function set_welcome_shown() {
		$this->session->set_userdata('dashboard_welcome_shown', true);
		echo json_encode(['success' => true]);
	}

	public function reset_welcome() {
		$this->session->unset_userdata('dashboard_welcome_shown');
		echo json_encode(['success' => true, 'message' => 'Welcome popup has been reset']);
	}

	/**
	 * Validate and sanitize user totals for enhanced accuracy
	 */
	private function validateAndSanitizeUserTotals($user_totals) {
		$validated = [];
		foreach ($user_totals as $key => $value) {
			// Ensure numeric values are properly typed
			if (is_numeric($value)) {
				$validated[$key] = (float)$value;
			} else {
				$validated[$key] = $value;
			}
		}
		return $validated;
	}

	/**
	 * Get wallet total by status codes
	 */
	private function getWalletTotalByStatus($user_id, $status_codes) {
		$status_list = implode(',', $status_codes);
		$result = $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND status IN ({$status_list}) AND amount > 0", [$user_id])->row();
		return (float)($result->total ?? 0);
	}

	/**
	 * Get wallet transaction count
	 */
	private function getWalletTransactionCount($user_id) {
		$result = $this->db->query("SELECT COUNT(*) as count FROM wallet WHERE user_id = ?", [$user_id])->row();
		return (int)($result->count ?? 0);
	}

	/**
	 * Calculate conversion rate
	 */
	private function calculateConversionRate($user_totals) {
		$total_clicks = (int)($user_totals['total_clicks_count'] ?? 0);
		$total_orders = (int)($user_totals['sale_localstore_count'] ?? 0);
		
		if ($total_clicks > 0) {
			return round(($total_orders / $total_clicks) * 100, 2);
		}
		return 0.0;
	}

	/**
	 * Calculate average commission
	 */
	private function calculateAverageCommission($user_id) {
		$result = $this->db->query("SELECT COALESCE(AVG(amount), 0) as avg_commission FROM wallet WHERE user_id = ? AND amount > 0 AND type IN ('sale_commission', 'click_commission', 'form_click_commission')", [$user_id])->row();
		return (float)($result->avg_commission ?? 0);
	}

	/**
	 * Get today's earnings
	 */
	private function getTodayEarnings($user_id) {
		$result = $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND DATE(created_at) = CURDATE() AND amount > 0", [$user_id])->row();
		return (float)($result->total ?? 0);
	}

	/**
	 * Get yesterday's earnings
	 */
	private function getYesterdayEarnings($user_id) {
		$result = $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND amount > 0", [$user_id])->row();
		return (float)($result->total ?? 0);
	}

	/**
	 * Get detailed wallet transactions
	 */
	private function getDetailedWalletTransactions($user_id) {
		$transactions = $this->db->query("
			SELECT 
				w.*,
				u.username,
				u.email,
				CASE 
					WHEN w.status = 0 THEN 'Pending'
					WHEN w.status = 1 THEN 'Approved'
					WHEN w.status = 2 THEN 'Requested'
					WHEN w.status = 3 THEN 'Paid'
					WHEN w.status = 4 THEN 'Cancelled'
					ELSE 'Unknown'
				END as status_text,
				CASE 
					WHEN w.type = 'sale_commission' THEN 'Sale Commission'
					WHEN w.type = 'click_commission' THEN 'Click Commission'
					WHEN w.type = 'form_click_commission' THEN 'Form Click Commission'
					WHEN w.type = 'membership_plan_bonus' THEN 'Membership Bonus'
					WHEN w.type = 'welcome_bonus' THEN 'Welcome Bonus'
					WHEN w.type = 'refer_registration_commission' THEN 'Referral Commission'
					ELSE w.type
				END as type_text
			FROM wallet w
			LEFT JOIN users u ON w.user_id = u.id
			WHERE w.user_id = ?
			ORDER BY w.created_at DESC
			LIMIT 20
		", [$user_id])->result_array();

		return array_map(function($transaction) {
			return [
				'id' => (int)$transaction['id'],
				'user_id' => (int)$transaction['user_id'],
				'amount' => (float)$transaction['amount'],
				'type' => $transaction['type'],
				'type_text' => $transaction['type_text'],
				'status' => (int)$transaction['status'],
				'status_text' => $transaction['status_text'],
				'comment' => $transaction['comment'],
				'datetime' => $transaction['datetime'],
				'reference_id' => $transaction['reference_id'],
				'reference_id_2' => $transaction['reference_id_2'],
				'group_id' => $transaction['group_id'],
				'commission_status' => (int)$transaction['commission_status'],
				'is_action' => (int)$transaction['is_action'],
				'comm_from' => $transaction['comm_from'],
				'username' => $transaction['username'],
				'created_at' => $transaction['created_at']
			];
		}, $transactions);
	}

	/**
	 * Get transaction breakdown by type
	 */
	private function getTransactionBreakdownByType($user_id) {
		$result = $this->db->query("
			SELECT 
				type,
				COUNT(*) as count,
				COALESCE(SUM(amount), 0) as total_amount,
				COALESCE(AVG(amount), 0) as avg_amount
			FROM wallet 
			WHERE user_id = ? AND amount > 0
			GROUP BY type
			ORDER BY total_amount DESC
		", [$user_id])->result_array();

		return array_map(function($row) {
			return [
				'type' => $row['type'],
				'count' => (int)$row['count'],
				'total_amount' => (float)$row['total_amount'],
				'avg_amount' => (float)$row['avg_amount']
			];
		}, $result);
	}

	/**
	 * Get transaction breakdown by status
	 */
	private function getTransactionBreakdownByStatus($user_id) {
		$result = $this->db->query("
			SELECT 
				status,
				COUNT(*) as count,
				COALESCE(SUM(amount), 0) as total_amount
			FROM wallet 
			WHERE user_id = ? AND amount > 0
			GROUP BY status
			ORDER BY status
		", [$user_id])->result_array();

		return array_map(function($row) {
			return [
				'status' => (int)$row['status'],
				'count' => (int)$row['count'],
				'total_amount' => (float)$row['total_amount']
			];
		}, $result);
	}

	/**
	 * Get transaction breakdown by month
	 */
	private function getTransactionBreakdownByMonth($user_id) {
		$result = $this->db->query("
			SELECT 
				YEAR(created_at) as year,
				MONTH(created_at) as month,
				COUNT(*) as count,
				COALESCE(SUM(amount), 0) as total_amount
			FROM wallet 
			WHERE user_id = ? AND amount > 0
			GROUP BY YEAR(created_at), MONTH(created_at)
			ORDER BY year DESC, month DESC
			LIMIT 12
		", [$user_id])->result_array();

		return array_map(function($row) {
			return [
				'year' => (int)$row['year'],
				'month' => (int)$row['month'],
				'count' => (int)$row['count'],
				'total_amount' => (float)$row['total_amount']
			];
		}, $result);
	}

	/**
	 * Get recent activity
	 */
	private function getRecentActivity($user_id) {
		$result = $this->db->query("
			SELECT 
				created_at,
				type,
				amount,
				status
			FROM wallet 
			WHERE user_id = ? AND amount > 0
			ORDER BY created_at DESC
			LIMIT 10
		", [$user_id])->result_array();

		return array_map(function($row) {
			return [
				'created_at' => $row['created_at'],
				'type' => $row['type'],
				'amount' => (float)$row['amount'],
				'status' => (int)$row['status']
			];
		}, $result);
	}

	/**
	 * Calculate days remaining for membership
	 */
	private function calculateDaysRemaining($expire_at) {
		if (empty($expire_at)) return null;
		
		$expire_date = new DateTime($expire_at);
		$current_date = new DateTime();
		$interval = $current_date->diff($expire_date);
		
		return $interval->invert ? 0 : $interval->days;
	}

	/**
	 * Check if membership is expired
	 */
	private function isMembershipExpired($expire_at) {
		if (empty($expire_at)) return true;
		
		$expire_date = new DateTime($expire_at);
		$current_date = new DateTime();
		
		return $current_date > $expire_date;
	}

	/**
	 * Validate data integrity
	 */
	private function validateDataIntegrity($user_id, $user_totals) {
		$checks = [];
		
		// Check if wallet balance matches sum of approved transactions
		$wallet_sum = $this->getWalletTotalByStatus($user_id, [1, 2, 3]);
		$balance_match = abs($wallet_sum - ($user_totals['wallet_balance'] ?? 0)) < 0.01;
		$checks['wallet_balance_match'] = $balance_match;
		
		// Check if total earnings includes all transactions
		$all_transactions_sum = $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND amount > 0", [$user_id])->row()->total;
		$earnings_match = abs($all_transactions_sum - ($user_totals['total_earnings'] ?? 0)) < 0.01;
		$checks['total_earnings_match'] = $earnings_match;
		
		// Check for negative amounts (should not exist)
		$negative_check = $this->db->query("SELECT COUNT(*) as count FROM wallet WHERE user_id = ? AND amount < 0", [$user_id])->row()->count;
		$checks['no_negative_amounts'] = $negative_check == 0;
		
		return $checks;
	}

	/**
	 * Validate calculation accuracy
	 */
	private function validateCalculationAccuracy($user_id, $user_totals) {
		$checks = [];
		
		// Validate monthly earnings calculation
		$monthly_sum = $this->db->query("
			SELECT COALESCE(SUM(amount), 0) as total 
			FROM wallet 
			WHERE user_id = ? AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND amount > 0
		", [$user_id])->row()->total;
		$checks['monthly_earnings_accurate'] = abs($monthly_sum - ($user_totals['monthly_earnings'] ?? 0)) < 0.01;
		
		// Validate weekly earnings calculation
		$weekly_sum = $this->db->query("
			SELECT COALESCE(SUM(amount), 0) as total 
			FROM wallet 
			WHERE user_id = ? AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) AND amount > 0
		", [$user_id])->row()->total;
		$checks['weekly_earnings_accurate'] = abs($weekly_sum - ($user_totals['weekly_earnings'] ?? 0)) < 0.01;
		
		return $checks;
	}

	/**
	 * Perform consistency checks
	 */
	private function performConsistencyChecks($user_id) {
		$checks = [];
		
		// Check for orphaned transactions
		$orphaned = $this->db->query("
			SELECT COUNT(*) as count 
			FROM wallet w 
			LEFT JOIN users u ON w.user_id = u.id 
			WHERE w.user_id = ? AND u.id IS NULL
		", [$user_id])->row()->count;
		$checks['no_orphaned_transactions'] = $orphaned == 0;
		
		// Check for duplicate group_ids
		$duplicates = $this->db->query("
			SELECT group_id, COUNT(*) as count 
			FROM wallet 
			WHERE user_id = ? AND group_id IS NOT NULL 
			GROUP BY group_id 
			HAVING COUNT(*) > 1
		", [$user_id])->result_array();
		$checks['no_duplicate_group_ids'] = empty($duplicates);
		
		// Check for valid status codes
		$invalid_status = $this->db->query("
			SELECT COUNT(*) as count 
			FROM wallet 
			WHERE user_id = ? AND status NOT IN (0, 1, 2, 3, 4)
		", [$user_id])->row()->count;
		$checks['valid_status_codes'] = $invalid_status == 0;
		
		return $checks;
	}

	public function analytics_dashboard(){
		$userdetails = $this->userdetails();

		if(empty($userdetails)){ redirect('/login'); }
		
		// Load required models
		$this->load->model('Total_model');
		$this->load->model('Product_model');
		$this->load->model('Deposit_payment_model');
		
		// Get currency format
		$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
		$data['hcurrency'] = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'user'));
		
		if($data['hcurrency']) {
			$data['fun_c_format'] = $fun_c_format = 'c_format_nosym';
		} else {
			$data['fun_c_format'] = $fun_c_format = 'c_format';
		}
		
		// Get user totals with caching for performance
		$cache_key = 'user_totals_' . $userdetails['id'];
		
		// Clear cache to ensure fresh data after fixes
		$this->cache->delete($cache_key);
		$data['user_totals'] = $this->Total_model->getUserTotals($userdetails['id']);
		$this->cache->save($cache_key, $data['user_totals'], 300);
		
		// Calculate analytics data (BUSINESS LOGIC)
		$data['analytics'] = $this->calculateAnalyticsData($userdetails, $data['user_totals']);
		
		// Calculate chart data for affiliate dashboard
		$dashboard_mode = 'affiliate';
		if (isset($_GET['view']) && $_GET['view'] === 'vendor' && isset($userdetails['is_vendor']) && $userdetails['is_vendor'] == 1) {
			$dashboard_mode = 'vendor';
		}
		$data['dashboard_mode'] = $dashboard_mode;
		
				// Calculate chart data based on dashboard mode with caching
		$chart_cache_key = 'chart_data_' . $userdetails['id'] . '_' . date('Y-m') . '_' . $dashboard_mode;
		
		// Clear chart cache to ensure fresh data after fixes
		$this->cache->delete($chart_cache_key);
		
		// Calculate chart data based on mode
		if ($dashboard_mode === 'affiliate') {
			$data['chart_data'] = $this->calculateAffiliateChartData($userdetails['id'], $data['user_totals']);
		} else {
			$data['chart_data'] = $this->calculateVendorChartData($userdetails['id'], $data['user_totals']);
		}
		// Cache for 10 minutes since chart data changes less frequently
		$this->cache->save($chart_cache_key, $data['chart_data'], 600);
		
		// Get site settings
		$data['site'] = $this->Product_model->getSettings('site');
		
		// Get membership settings
		$data['MembershipSetting'] = $this->Product_model->getSettings('membership');
		$data['isMembershipAccess'] = $this->Product_model->isMembershipAccess();
		
		// Get user plan details
		$user = App\User::auth();
		if((int)$user->plan_id != 0){
			if($user->plan_id == -1){
				$data['is_lifetime_plan'] = 1;
			} else if ($user) {
				$plan = $user->plan();
				if($plan){
					$data['plan'] = $plan;
					$data['payment_details'] = json_decode($plan->payment_details);
				}
			}
		}
		
		// Get vendor-specific data if user is vendor
		if(isset($userdetails['is_vendor']) && $userdetails['is_vendor'] == 1) {
			$data['vendor_data'] = $this->calculateVendorAnalytics($userdetails);
		}
		
		// Pass userdetails to view
		$data['userdetails'] = $userdetails;
		
		// Use the template system instead of standalone page
		$this->view($data, 'dashboard/analytics_dashboard', 'usercontrol');
	}

	/**
	 * Calculate analytics data for both affiliate and vendor users
	 */
	private function calculateAnalyticsData($userdetails, $user_totals) {
		$analytics = [];
		
		try {
			// Get time-based data
			$analytics['monthly_earnings'] = (float)($this->Total_model->getUserBalance($userdetails['id'], ['month' => 1]) ?: 0);
			$analytics['weekly_earnings'] = (float)($this->Total_model->getUserBalance($userdetails['id'], ['week' => 1]) ?: 0);
			$analytics['yearly_earnings'] = (float)($this->Total_model->getUserBalance($userdetails['id'], ['year' => 1]) ?: 0);
			
			// Calculate growth percentages
			$analytics['weekly_growth'] = $this->calculateGrowthPercentage($userdetails['id'], 'week');
			$analytics['monthly_growth'] = $this->calculateGrowthPercentage($userdetails['id'], 'month');
			$analytics['yearly_growth'] = $this->calculateGrowthPercentage($userdetails['id'], 'year');
			
			// Goal calculations
			$analytics['goals'] = $this->calculateGoalProgress($userdetails['id'], $user_totals, $analytics['monthly_earnings']);
			
			// Performance metrics
			$analytics['performance'] = $this->calculatePerformanceMetrics($userdetails['id'], $user_totals);
			
			
			
		} catch (Exception $e) {
			// If any calculation fails, provide safe defaults
			$analytics = $this->getDefaultAnalytics();
			log_message('error', 'Analytics calculation failed: ' . $e->getMessage());
		}
		
		return $analytics;
	}

	/**
	 * Calculate goal progress data
	 */
	private function calculateGoalProgress($user_id, $user_totals, $monthly_earnings) {
		$goals = [];
		
		try {
			// Calculate monthly clicks estimate
			$total_clicks = isset($user_totals['total_clicks_count']) ? (int)$user_totals['total_clicks_count'] : 0;
			$total_earnings = isset($user_totals['wallet_accept_amount']) ? (float)$user_totals['wallet_accept_amount'] : 0;
			
			if ($total_earnings > 0 && $total_clicks > 0) {
				$monthly_clicks = round(($total_clicks * $monthly_earnings) / $total_earnings);
			} else {
				$monthly_clicks = 0;
			}
			
			// Calculate targets (20% growth for earnings, 25% for clicks)
			$earnings_target = max($monthly_earnings * 1.2, 100);
			$clicks_target = max($monthly_clicks * 1.25, 50);
			
			// Calculate progress percentages
			$earnings_progress = $earnings_target > 0 ? min(($monthly_earnings / $earnings_target) * 100, 100) : 0;
			$clicks_progress = $clicks_target > 0 ? min(($monthly_clicks / $clicks_target) * 100, 100) : 0;
			
			$goals = [
				'monthly_earnings' => $monthly_earnings,
				'earnings_target' => $earnings_target,
				'earnings_progress' => $earnings_progress,
				'monthly_clicks' => $monthly_clicks,
				'clicks_target' => $clicks_target,
				'clicks_progress' => $clicks_progress
			];
			
		} catch (Exception $e) {
			$goals = [
				'monthly_earnings' => 0,
				'earnings_target' => 100,
				'earnings_progress' => 0,
				'monthly_clicks' => 0,
				'clicks_target' => 50,
				'clicks_progress' => 0
			];
		}
		
		return $goals;
	}

	/**
	 * Calculate performance metrics
	 */
	private function calculatePerformanceMetrics($user_id, $user_totals) {
		$performance = [];
		
		try {
			// Conversion rates
			$total_clicks = isset($user_totals['total_clicks_count']) ? (int)$user_totals['total_clicks_count'] : 0;
			$total_orders = isset($user_totals['sale_localstore_count']) ? (int)$user_totals['sale_localstore_count'] : 0;
			$total_orders += isset($user_totals['order_external_count']) ? (int)$user_totals['order_external_count'] : 0;
			
			$conversion_rate = $total_clicks > 0 ? round(($total_orders / $total_clicks) * 100, 2) : 0;
			
			// Average order value
			$total_commission = isset($user_totals['wallet_accept_amount']) ? (float)$user_totals['wallet_accept_amount'] : 0;
			$avg_commission = $total_orders > 0 ? round($total_commission / $total_orders, 2) : 0;
			
			$performance = [
				'conversion_rate' => $conversion_rate,
				'avg_commission' => $avg_commission,
				'total_orders' => $total_orders,
				'total_clicks' => $total_clicks
			];
			
		} catch (Exception $e) {
			$performance = [
				'conversion_rate' => 0,
				'avg_commission' => 0,
				'total_orders' => 0,
				'total_clicks' => 0
			];
		}
		
		return $performance;
	}

	/**
	 * Calculate vendor-specific analytics
	 */
	private function calculateVendorAnalytics($userdetails) {
		$vendor_data = [];
		
		try {
			// Get vendor deposits
			$vendor_data['deposits'] = $this->Deposit_payment_model->getDeposits([
				'user_id' => $userdetails['id'],
				'limit' => 10
			]);
			
			$vendor_data['total_deposited'] = $this->db->query('SELECT SUM(vd_amount) as total FROM vendor_deposit WHERE vd_status=1 AND vd_user_id='.$userdetails['id'])->row()->total ?: 0;
			
			// Get vendor settings
			$vendoerMinDeposit = $this->Product_model->getSettings('site', 'vendor_min_deposit');
			$vendor_data['min_deposit'] = isset($vendoerMinDeposit['vendor_min_deposit']) ? $vendoerMinDeposit['vendor_min_deposit'] : 0;
			
			$depbalence = $this->Total_model->getUserBalance($userdetails['id']);
			$vendor_data['show_deposit_warning'] = ($depbalence < $vendor_data['min_deposit']) ? 1 : 0;
			
			// Create complete deposit warning message with amount
			if ($vendor_data['show_deposit_warning']) {
				$vendor_data['deposit_warning_message'] = __('user.minimum_deposit_warning') . ' ' . c_format($vendor_data['min_deposit']) . ' ' . __('user.to_continue_vendor_activities');
			} else {
				$vendor_data['deposit_warning_message'] = '';
			}
			
			$vendorDepositStatus = $this->Product_model->getSettings('vendor', 'depositstatus');
			$vendor_data['deposit_status'] = isset($vendorDepositStatus['depositstatus']) ? $vendorDepositStatus['depositstatus'] : 0;
			
			// Get deposit statistics
			$deposit_stats = $this->db->query("SELECT 
				SUM(CASE WHEN vd_status = 0 THEN 1 ELSE 0 END) as pending_count,
				SUM(CASE WHEN vd_status = 1 THEN 1 ELSE 0 END) as approved_count,
				SUM(CASE WHEN vd_status = 3 THEN 1 ELSE 0 END) as denied_count,
				SUM(CASE WHEN vd_status = 0 THEN vd_amount ELSE 0 END) as pending_amount,
				SUM(CASE WHEN vd_status = 1 THEN vd_amount ELSE 0 END) as approved_amount,
				SUM(CASE WHEN vd_status = 3 THEN vd_amount ELSE 0 END) as denied_amount,
				COUNT(*) as total_count,
				SUM(vd_amount) as total_amount
				FROM vendor_deposit WHERE vd_user_id=".$userdetails['id'])->row();
			
			$vendor_data['deposit_stats'] = $deposit_stats;
			
			// Get deposit status information
			$vendor_data['status_list'] = $this->Deposit_payment_model->status_list;
			$vendor_data['status_icon'] = $this->Deposit_payment_model->status_icon;
			
		} catch (Exception $e) {
			$vendor_data = $this->getDefaultVendorData();
			log_message('error', 'Vendor analytics calculation failed: ' . $e->getMessage());
		}
		
		return $vendor_data;
	}



	/**
	 * Calculate growth percentage
	 */
	private function calculateGrowthPercentage($user_id, $period) {
		try {
			$current = (float)($this->Total_model->getUserBalance($user_id, [$period => 1]) ?: 0);
			$previous = (float)($this->Total_model->vendor_user_totals_week_growth($user_id, [$period => 1], $current) ?: 0);
			
			return $this->Total_model->getGrowthPercentage($current, $previous);
		} catch (Exception $e) {
			return 0;
		}
	}

	/**
	 * Default analytics data in case of errors
	 */
	private function getDefaultAnalytics() {
		return [
			'monthly_earnings' => 0,
			'weekly_earnings' => 0,
			'yearly_earnings' => 0,
			'weekly_growth' => 0,
			'monthly_growth' => 0,
			'yearly_growth' => 0,
			'goals' => [
				'monthly_earnings' => 0,
				'earnings_target' => 100,
				'earnings_progress' => 0,
				'monthly_clicks' => 0,
				'clicks_target' => 50,
				'clicks_progress' => 0
			],
			'performance' => [
				'conversion_rate' => 0,
				'avg_commission' => 0,
				'total_orders' => 0,
				'total_clicks' => 0
			],

		];
	}

	/**
	 * Default vendor data in case of errors
	 */
	private function getDefaultVendorData() {
		return [
			'deposits' => [],
			'total_deposited' => 0,
			'min_deposit' => 0,
			'show_deposit_warning' => 0,
			'deposit_warning_message' => '',
			'deposit_status' => 0,
			'deposit_stats' => (object)[
				'pending_count' => 0,
				'approved_count' => 0,
				'denied_count' => 0,
				'pending_amount' => 0,
				'approved_amount' => 0,
				'denied_amount' => 0,
				'total_count' => 0,
				'total_amount' => 0
			],
			'status_list' => [],
			'status_icon' => []
		];
	}

	/**
	 * Calculate chart data for affiliate dashboard visualizations with REAL data
	 */
	private function calculateAffiliateChartData($user_id, $user_totals) {
		$chart_data = [];
		
		try {
			// Get REAL monthly performance data for the current year
			$current_year = date('Y');
			$chart_data['monthly_performance'] = [
				'earnings' => [],
				'clicks' => []
			];
			
			// Get real monthly data for each month (1-12)
			for ($month = 1; $month <= 12; $month++) {
				// Get real monthly earnings from wallet table (status 2=requested, 3=paid) - AFFILIATE ONLY
				$monthly_earnings = $this->db->query("
					SELECT COALESCE(SUM(amount), 0) as total 
					FROM wallet 
					WHERE user_id = ? 
					AND status IN (2, 3) 
					AND YEAR(created_at) = ? 
					AND MONTH(created_at) = ?
					AND type IN (
						'sale_commission', 'click_commission', 'form_click_commission', 
						'affiliate_click_commission', 'external_click_commission', 
						'membership_plan_bonus', 'welcome_bonus', 'refer_registration_commission'
					)
				", [$user_id, $current_year, $month])->row();
				
				// Get real monthly clicks from wallet table (click commission types)
				$monthly_clicks = $this->db->query("
					SELECT COALESCE(COUNT(*), 0) as total 
					FROM wallet 
					WHERE user_id = ? 
					AND type IN ('click_commission', 'form_click_commission', 'affiliate_click_commission', 'external_click_commission')
					AND YEAR(created_at) = ? 
					AND MONTH(created_at) = ?
				", [$user_id, $current_year, $month])->row();
				
				$chart_data['monthly_performance']['earnings'][] = (float)($monthly_earnings->total ?? 0);
				$chart_data['monthly_performance']['clicks'][] = (int)($monthly_clicks->total ?? 0);
			}
			
			// Channel performance data (real data) - AFFILIATE ONLY - Include ALL transactions (approved + pending)
			$chart_data['channel_performance'] = [
				// Click Commissions (Affiliate Only) - Include ALL transactions
				'click_localstore_commission' => $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND type IN ('click_commission','refer_click_commission') AND amount > 0 AND commission_status = 0", [$user_id])->row()->total,
				'click_external_commission' => $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND type IN ('external_click_commission') AND is_action = 0", [$user_id])->row()->total,
				'click_form_commission' => $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND type = 'form_click_commission' AND commission_status = 0", [$user_id])->row()->total,
				// Sales Commissions (Affiliate Only) - Include ALL transactions
				'sale_localstore_commission' => $this->db->query("SELECT COALESCE(SUM(w.amount), 0) as total FROM wallet w INNER JOIN `order` o ON o.id = w.reference_id_2 WHERE w.user_id = ? AND w.comm_from != 'ex' AND w.type IN ('sale_commission','refer_sale_commission')", [$user_id])->row()->total,
				'order_external_commission' => $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND comm_from = 'ex' AND type IN ('sale_commission','refer_sale_commission') AND commission_status = 0", [$user_id])->row()->total
			];
			
		} catch (Exception $e) {
			// Provide safe defaults if database queries fail
			$chart_data = [
				'monthly_performance' => [
					'earnings' => array_fill(0, 12, 0),
					'clicks' => array_fill(0, 12, 0)
				],
				'channel_performance' => [
					// Click Commissions (Affiliate Only)
					'click_localstore_commission' => 0,
					'click_external_commission' => 0,
					'click_form_commission' => 0,
					// Sales Commissions (Affiliate Only)
					'sale_localstore_commission' => 0,
					'order_external_commission' => 0
				]
			];
			log_message('error', 'Affiliate chart data calculation failed: ' . $e->getMessage());
		}
		
		return $chart_data;
	}

	/**
	 * Calculate chart data for vendor dashboard visualizations with REAL data
	 */
	private function calculateVendorChartData($user_id, $user_totals) {
		$chart_data = [];
		
		try {
			// Get REAL monthly performance data for the current year
			$current_year = date('Y');
			$chart_data['monthly_performance'] = [
				'earnings' => [],
				'clicks' => []
			];
			
			// Get real monthly data for each month (1-12)
			for ($month = 1; $month <= 12; $month++) {
				// Get real monthly earnings from wallet table (status 2=requested, 3=paid) - VENDOR ONLY
				$monthly_earnings = $this->db->query("
					SELECT COALESCE(SUM(amount), 0) as total 
					FROM wallet 
					WHERE user_id = ? 
					AND status IN (2, 3) 
					AND YEAR(created_at) = ? 
					AND MONTH(created_at) = ?
					AND type IN (
						'vendor_sale_commission', 'vendor_shipping_reimbursement', 'admin_shipping_reimbursement'
					)
				", [$user_id, $current_year, $month])->row();
				
				// Get real monthly clicks from wallet table (vendor click commission types)
				$monthly_clicks = $this->db->query("
					SELECT COALESCE(COUNT(*), 0) as total 
					FROM wallet 
					WHERE user_id = ? 
					AND type IN ('vendor_click_commission', 'external_click_comm_pay')
					AND YEAR(created_at) = ? 
					AND MONTH(created_at) = ?
				", [$user_id, $current_year, $month])->row();
				
				$chart_data['monthly_performance']['earnings'][] = (float)($monthly_earnings->total ?? 0);
				$chart_data['monthly_performance']['clicks'][] = (int)($monthly_clicks->total ?? 0);
			}
			
			// Channel performance data (real data) - VENDOR ONLY - Include ALL transactions for accuracy
			$chart_data['channel_performance'] = [
				// Vendor Click Commissions (what vendor pays to affiliates) - Include ALL transactions
				'vendor_click_localstore_commission_pay' => $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND type IN ('click_commission','refer_click_commission') AND amount < 0", [$user_id])->row()->total,
				'vendor_click_external_commission_pay' => $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND type IN ('external_click_comm_pay')", [$user_id])->row()->total,
				// Vendor Sales Commissions (what vendor pays to affiliates) - Include ALL transactions
				'vendor_sale_localstore_commission_pay' => $this->db->query("SELECT COALESCE(SUM(w.amount), 0) as total FROM wallet w INNER JOIN `order` o ON o.id = w.reference_id_2 WHERE w.user_id = ? AND w.type IN ('sale_commission','admin_sale_commission') AND w.amount < 0", [$user_id])->row()->total,
				'vendor_order_external_commission_pay' => $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM wallet WHERE user_id = ? AND type IN ('sale_commission_vendor_pay','admin_sale_commission_v_pay')", [$user_id])->row()->total
			];
			
		} catch (Exception $e) {
			// Provide safe defaults if database queries fail
			$chart_data = [
				'monthly_performance' => [
					'earnings' => array_fill(0, 12, 0),
					'clicks' => array_fill(0, 12, 0)
				],
				'channel_performance' => [
					// Vendor Click Commissions (what vendor pays to affiliates)
					'vendor_click_localstore_commission_pay' => 0,
					'vendor_click_external_commission_pay' => 0,
					// Vendor Sales Commissions (what vendor pays to affiliates)
					'vendor_sale_localstore_commission_pay' => 0,
					'vendor_order_external_commission_pay' => 0
				]
			];
			log_message('error', 'Vendor chart data calculation failed: ' . $e->getMessage());
		}
		
		return $chart_data;
	}


	public function ai_suggestion() {
	    $userdetails = $this->userdetails();
	    if(empty($userdetails)){ 
	        echo json_encode(['error' => 'User not logged in']); 
	        die;
	    }

	    $this->load->model('Total_model');
	    $this->load->model("Form_model");
	    $this->load->model('IntegrationModel');

	    $hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
	    $fun_c_format = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'user')) ? 'c_format_nosym' : 'c_format';

	    $data = array();
	    $data['user_totals'] = $this->Total_model->getUserTotals((int)$userdetails['id']);

	    $data['user_totals_week'] = $fun_c_format($this->Total_model->getUserBalance((int)$userdetails['id'], ['week' => 1]));
	    $user_total_week = $this->Total_model->getUserBalance((int)$userdetails['id']);
	    $user_last_week_growth = $this->Total_model->vendor_user_totals_week_growth((int)$userdetails['id'], ['week' => 1], $user_total_week);
	    $data['user_totals_week_growth'] = $this->Total_model->getGrowthPercentage((int)$user_total_week, (int)$user_last_week_growth);

	    $data['user_totals_month'] = $fun_c_format($this->Total_model->getUserBalance((int)$userdetails['id'], ['month' => 1]));
	    $user_total_month = $this->Total_model->getUserBalance((int)$userdetails['id']);
	    $user_last_month_growth = $this->Total_model->vendor_user_totals_week_growth((int)$userdetails['id'], ['month' => 1], $user_total_month);
	    $data['user_totals_month_growth'] = $this->Total_model->getGrowthPercentage((int)$user_total_month, (int)$user_last_month_growth);

	    $data['user_totals_year'] = $fun_c_format($this->Total_model->getUserBalance((int)$userdetails['id'], ['year' => 1]));
	    $user_total_year = $this->Total_model->getUserBalance((int)$userdetails['id']);
	    $user_last_year_growth = $this->Total_model->vendor_user_totals_week_growth((int)$userdetails['id'], ['year' => 1], $user_total_year);
	    $data['user_totals_year_growth'] = $this->Total_model->getGrowthPercentage((int)$user_total_year, (int)$user_last_year_growth);

	    $data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);

	    $referlevelSettings = $this->Product_model->getSettings('referlevel');
	    $data['refer_status'] = reh_fetchReffererStatus($referlevelSettings, $userdetails['id']);

	    $data['register_slug'] = $this->db->query("SELECT slug FROM slugs WHERE user_id = '".(int)$userdetails['id']."' AND type = 'register'")->row()->slug ?? '';
	    $data['store_slug'] = $this->db->query("SELECT slug FROM slugs WHERE user_id = '".(int)$userdetails['id']."' AND type = 'store'")->row()->slug ?? '';

	    $post = $this->input->post(null, true);
	    $action = isset($post['action']) ? $post['action'] : '';

		$settings = $this->Product_model->getSettings('userdashboard');
		$ai_enabled = isset($settings['ai_suggestion_enabled']) ? $settings['ai_suggestion_enabled'] : '1';

		if ($ai_enabled !== '1') {
		    echo json_encode(['error' => 'AI suggestions are disabled']);
		    die;
		}

	    switch($action) {
	        case 'get':
	            echo json_encode($this->getAISuggestion($data));
	            break;

	        case 'refresh':
	            $currentIndex = isset($post['current_index']) ? (int)$post['current_index'] : -1;
	            echo json_encode($this->refreshAISuggestion($data, $currentIndex));
	            break;

	        case 'dismiss':
	            $this->session->set_userdata('hide_ai_box', true);
	            echo json_encode(['status' => 'success']);
	            break;

	        case 'hide_forever':
	            $this->session->set_userdata('hide_ai_box_forever', true);
	            $this->session->set_userdata('hide_ai_box', true);
	            echo json_encode(['status' => 'success']);
	            break;

	        default:
	            echo json_encode(['error' => 'Invalid action']);
	    }
	    die;
	}

	private function getAllAISuggestions($data) {
	    $totals = $data['user_totals'];
	    $insights = [];

	    $balance = floatval(preg_replace('/[^\d.]/', '', $totals['user_balance'] ?? 0));
	    $currency = '$';

	    if (!empty($totals['wallet_unpaid_amount']) && $totals['wallet_unpaid_count']) {
	        $wallet = floatval($totals['wallet_unpaid_amount']);
	        $count = (int)$totals['wallet_unpaid_count'];

	        if ($balance > 0 && abs($balance - $wallet) > 0.01) {
	            $insights[] = [
	                'text' => sprintf(__('user.ai_you_have_available_pending'), $currency . $balance, $currency . $wallet, $count),
	                'priority' => 5
	            ];
	        } elseif ($balance > 0) {
	            $insights[] = [
	                'text' => sprintf(__('user.ai_you_have_available_only'), $currency . $balance),
	                'priority' => 4
	            ];
	        } else {
	            $insights[] = [
	                'text' => sprintf(__('user.ai_pending_commissions_only'), $count, $currency . $wallet),
	                'priority' => 4
	            ];
	        }
	    }

	    if (($data['user_totals_week_growth'] ?? 0) > 10) {
	        $growth = $data['user_totals_week_growth'];
	        $insights[] = [
	            'text' => sprintf(__('user.ai_weekly_growth'), $growth),
	            'priority' => 4
	        ];
	    }

	    $clicks = $totals['total_clicks_count'] ?? 0;
	    $sales = $totals['sale_localstore_count'] ?? 0;
	    if ($clicks >= 50) {
	        if ($sales > 0) {
	            $rate = number_format(($sales / $clicks) * 100, 2);
	            $insights[] = [
	                'text' => sprintf(__('user.ai_conversion_rate'), $rate, $sales, $clicks),
	                'priority' => 3
	            ];
	        } else {
	            $insights[] = [
	                'text' => sprintf(__('user.ai_clicks_no_sales'), $clicks),
	                'priority' => 4
	            ];
	        }
	    }

	    if (empty($data['store_slug'])) {
	        $insights[] = [
	            'text' => __('user.ai_no_store_url'),
	            'priority' => 3
	        ];
	    }

	    if (empty($data['refer_status'])) {
	        $insights[] = [
	            'text' => __('user.ai_referral_disabled'),
	            'priority' => 3
	        ];
	    } elseif (empty($data['register_slug'])) {
	        $insights[] = [
	            'text' => __('user.ai_referral_no_link'),
	            'priority' => 3
	        ];
	    }

	    $refer_clicks = (int)($data['refer_total']['total_ganeral_click']['total_clicks'] ?? 0)
	                  + (int)($data['refer_total']['total_product_click']['clicks'] ?? 0);
	    $refer_sales = (int)($data['refer_total']['total_product_sale']['counts'] ?? 0);

	    if ($refer_clicks > 0 || $refer_sales > 0) {
	        if ($refer_sales > 0) {
	            $insights[] = [
	                'text' => sprintf(__('user.ai_referral_clicks_sales'), $refer_clicks, $refer_sales),
	                'priority' => 4
	            ];
	        } else {
	            $insights[] = [
	                'text' => sprintf(__('user.ai_referral_clicks_only'), $refer_clicks),
	                'priority' => 3
	            ];
	        }
	    }

	    if (count($insights) < 2) {
	        $insights[] = [
	            'text' => __('user.ai_start_promoting'),
	            'priority' => 1
	        ];
	    }

	    usort($insights, fn($a, $b) => $b['priority'] <=> $a['priority']);
	    return $insights;
	}

	private function getAISuggestion($data) {
	    $suggestions = $this->getAllAISuggestions($data);
	    $index = $this->session->userdata('current_suggestion_index') ?? rand(0, count($suggestions) - 1);
	    $this->session->set_userdata('current_suggestion_index', $index);

	    return [
	        'suggestion' => $suggestions[$index]['text'],
	        'index' => $index,
	        'total' => count($suggestions)
	    ];
	}

	private function refreshAISuggestion($data, $currentIndex) {
	    $suggestions = $this->getAllAISuggestions($data);
	    $newIndex = $currentIndex;
	    $attempts = 0;

	    if (count($suggestions) > 1) {
	        while ($newIndex === $currentIndex && $attempts < 10) {
	            $newIndex = rand(0, count($suggestions) - 1);
	            $attempts++;
	        }
	    }

	    $this->session->set_userdata('current_suggestion_index', $newIndex);

	    return [
	        'suggestion' => $suggestions[$newIndex]['text'],
	        'index' => $newIndex,
	        'total' => count($suggestions)
	    ];
	}

	public function reset_ai_suggestion() {
	    $this->session->unset_userdata('hide_ai_box_forever');
	    $this->session->unset_userdata('hide_ai_box');
	    $this->session->unset_userdata('current_suggestion_index');
	    
	    $this->session->set_flashdata('success', 'AI Assistant suggestions have been restored.');
	    redirect('usercontrol/dashboard');
	}

	public function toggle_ai_suggestion_setting() {
	    $user = $this->userdetails();
	    if (!$user) {
	        echo json_encode(['error' => 'Unauthorized']);
	        return;
	    }

	    $enabled = $this->input->post('enabled') == '1' ? '1' : '0';

	    $this->Product_model->updateSettings('user_panel', 'ai_suggestion_enabled', $enabled);

	    echo json_encode(['status' => 'success', 'enabled' => $enabled]);
	}



	public function get_integartion_data($return  = false){
		$post = $this->input->post();
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$json = array();

		if($post['integration_data_year'] && $post['integration_data_month']){
			$integration_filters = array(
				'integration_data_year' => $post['integration_data_year'],
				'integration_data_month' => $post['integration_data_month'],
			);
		}else{
			$integration_filters = array();
		}

		$integration_filters['user_id'] = $userdetails['id'];

		$totals = $this->Wallet_model->getTotals($integration_filters, true);
		if($totals){
			$html = '';
			if ($totals['integration']['all'] ==null) {
				
			$html .= '<div class="text-center mt-5">
			    <div class="d-flex justify-content-center align-items-center flex-column mt-5">
			        <i class="fas fa-exchange-alt fa-5x text-muted"></i>
			        <h3 class="text-muted">'. __('admin.no_data_found') .'</h3>
			    </div>
			</div>';

			} else {
				$html .= '<div role="tabpanel" class="tab-pane" id="site-all" style="display: block">
				<ul class="list-group p-t-10" style="min-height:360px">
				<li class="list-group-item">
				'. __( 'user.total_balance' ) .'
				<span class="badge bg-light font-14 pull-right">
				'. c_format($totals['integration']['balance']) .'        
				</span>
				</li>
				<li class="list-group-item">
				'. __( 'user.total_sales' ) .'
				<span class="badge bg-light font-14 pull-right">
				'. c_format($totals['integration']['balance']) .' / '. c_format($totals['integration']['sale']) .'        
				</span>
				</li>
				<li class="list-group-item">
				'. __( 'user.total_clicks' ) .'
				<span class="badge bg-light font-14 pull-right">
				'. (int)$totals['integration']['click_count'] .' / '. c_format($totals['integration']['click_amount']) .'
				</span>
				</li>
				<li class="list-group-item">
				'. __('user.total_actions') .'
				<span class="badge bg-light font-14 pull-right">
				'. (int)$totals['integration']['action_count'] .' / '. c_format($totals['integration']['action_amount']) .'
				</span>
				</li>
				<li class="list-group-item">
				'. __( 'user.total_commission' ) .'
				<span class="badge bg-light font-14 pull-right">
				'. c_format($totals['integration']['total_commission']) .' 
				</span>
				</li>
				<li class="list-group-item">
				'. __( 'user.total_orders' ) .'
				<span class="badge bg-light font-14 pull-right">
				'. (int)$totals['integration']['total_orders'] .' 
				</span>
				</li>
				</ul>
				</div>';
				$index = 0; 
				foreach ($totals['integration']['all'] as $website => $value) {
					$html .= '<div role="tabpanel" class="tab-pane" id="site-'. ++$index .'" style="height:360px;display: none;">
					<ul class="list-group p-t-10" >
					<li class="list-group-item">
					'. __( 'user.total_balance' ) .'
					<span class="badge bg-light font-14 pull-right">
					'. c_format($value['balance']) .'
					</span>
					</li>
					<li class="list-group-item">
					'. __( 'user.total_sales' ) .'
					<span class="badge bg-light font-14 pull-right">
					'. c_format($value['balance']) .' / '. c_format($value['sale']) .'        
					</span>
					</li>
					<li class="list-group-item">
					'. __( 'user.total_clicks' ) .'
					<span class="badge bg-light font-14 pull-right">
					'. (int)$value['click_count'] .' / '. c_format($value['click_amount']) .'
					</span>
					</li>
					<li class="list-group-item">
					'. __('user.total_actions') .'
					<span class="badge bg-light font-14 pull-right">
					'. (int)$value['action_count'] .' / '. c_format($value['action_amount']) .'
					</span>
					</li>
					<li class="list-group-item">
					'. __( 'user.total_commission' ) .'
					<span class="badge bg-light font-14 pull-right">
					'. c_format($value['click_amount'] + $value['sale'] + $value['action_amount']) .' 
					</span>
					</li>
					<li class="list-group-item">
					'. __( 'user.total_orders' ) .'
					<span class="badge bg-light font-14 pull-right">
					'. (int)$value['total_orders'] .' 
					</span>
					</li>
					<li class="list-group-item">
					<a class="btn btn-lg btn-default btn-success" href="http://'. $website .'" target="_blank">'. __( 'user.preview_store' ) .'</a>
					</li>
					</ul>
					</div>';
				}
			}

			$integration_data_selected = 'all';
			if(isset($post['integration_data_selected']) && $post['integration_data_selected'] != '') $integration_data_selected = $post['integration_data_selected'];

			$newHTML = "<div class='p-3'>
			<select name='integration-chart-type' id='integration-chart-type' class='form-control show-tabs select2-input'>
			<option value='all' data-id='all' data-website='all'>All</option>";
			$index = 0;
			foreach ($totals['integration']['all'] as $website => $value) {
				$k = base64_encode($website); 
				$newHTML .= "<option ". ( $integration_data_selected == $k ? 'selected' : '' ) ." value='". $k ."' data-id='". ++$index ."' data-website='". $website ."' >". $website ."</option>";
			}
			$newHTML .= "</select>
			</div>
			<div class='tab-content'>
			{$html}
			</div>";


			$json['html'] = $newHTML;


			$type = isset($post['integration_data_website_selected']) && $post['integration_data_website_selected'] != '' ?  $post['integration_data_website_selected'] : 'all';

			if($type == 'all'){
				$data = array(
					'balance'				=>	(float)$totals['integration']['balance'],
					'total_orders_amount'	=>	(float)$totals['integration']['total_orders_amount'],
					'sale'					=>	(float)$totals['integration']['sale'],
					'click_count'			=>	(float)$totals['integration']['click_count'],
					'click_amount'			=>	(float)$totals['integration']['click_amount'],
					'action_count'			=>	(float)$totals['integration']['action_count'],
					'action_amount'			=>	(float)$totals['integration']['action_amount'],
					'total_commission'		=>	(float)$totals['integration']['total_commission'],
					'total_orders'			=>	(float)$totals['integration']['total_orders'],
				);
			}else{
				$integration = $totals['integration']['all'];
				if(isset($integration[$type])){
					$data = array(
						'balance'				=>	isset($integration[$type]['balance']) ? (float)$integration[$type]['balance'] : 0,
						'total_orders_amount'	=>	isset($integration[$type]['total_orders_amount']) ? (float)$integration[$type]['total_orders_amount'] : 0,
						'sale'					=>	isset($integration[$type]['sale']) ? (float)$integration[$type]['sale'] : 0,
						'click_count'			=>	isset($integration[$type]['click_count']) ? (float)$integration[$type]['click_count'] : 0,
						'click_amount'			=>	isset($integration[$type]['click_amount']) ? (float)$integration[$type]['click_amount'] : 0,
						'action_count'			=>	isset($integration[$type]['action_count']) ? (float)$integration[$type]['action_count'] : 0,
						'action_amount'			=>	isset($integration[$type]['action_amount']) ? (float)$integration[$type]['action_amount'] : 0,
						'total_commission'		=>	isset($integration[$type]['total_commission']) ? (float)$integration[$type]['total_commission'] : 0,
						'total_orders'			=>	isset($integration[$type]['total_orders']) ? (float)$integration[$type]['total_orders'] : 0,
					);
				}
			}

			$json['chart'][$post['integration_data_year']] = $data;
		}else{
			$json['html'] = false;
		}



		if($return) return $json;
		echo json_encode($json);die;
	}
	public function logs(){
		$data = array();
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$input = $this->input->post(null,true);
		

		$filter = array(
			'user_id' => $userdetails['id'],
		);

		$data['status'] = $this->Wallet_model->status();
		$data['status_icon'] = $this->Wallet_model->status_icon;
		if($input['type'] == 'sale'){
			$data['title'] = "Sales Logs";

			$filter['type_in'] = "'sale_commission','vendor_sale_commission'";
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'hold_orders'){
			$data['title'] = "Hold Orders Logs";

			$filter['type'] = "sale_commission";
			$filter['status'] = 0;
			
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'click'){
			$data['title'] = "Clicks Logs";
			$filter['click_log'] = 1;
			$data['data'] = $this->Wallet_model->getTransaction($filter);

			$data['title2'] = "Clicks Logs";
			$record = array();

			$where = ' AND user_id = '. $userdetails['id'];

			$record[] = $this->db->query('SELECT country_code,created_at,ip  as user_ip,commission as pay_commition,"Integration Click" as type FROM integration_clicks_action WHERE is_action=0'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,pay_commition,"Product Click" as type  FROM product_action WHERE  1'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,pay_commition,"Form Click" as type  FROM form_action WHERE 1'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,commission as pay_commition,"Affiliate Click" as type FROM affiliate_action WHERE 1'.$where)->result_array();

			$record[] = $this->db->query('SELECT pa.country_code,pa.created_at,pa.user_ip,pa.pay_commition,"Store Product Click (Other Affiliate)" as type  FROM product_action pa LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id)  WHERE paff.user_id=  '. (int)$userdetails['id'] .' ')->result_array();


			$_record = array();
			foreach ($record as $key => $re) {
				foreach ($re as $_key => $value) {
					$_record[] = array(
						'created_at' => $value['created_at'],
						'comment' => 'Click from ip_message ',
						'status' => $value['type'],
						'country_code' => $value['country_code'],
						'user_ip' => $value['user_ip'],
					);
				}
			}

			usort($_record, array('Admincontrol', 'date_compare') ); 
			$data['data2'] = $_record;
		}
		else if($input['type'] == 'orders'){
			$order_status = $this->Order_model->status();
			$data['title'] = "Digital Orders";
			$record = $this->db->query('SELECT o.* FROM `order_products` op LEFT JOIN `order` AS o ON o.id = op.order_id WHERE o.status > 0 AND op.refer_id='. (int)$userdetails['id'])->result_array();

			$_record = array();
			foreach ($record as $_key => $value) {
				$_record[] = array(
					'created_at'   => $value['created_at'],
					'comment'      => 'Order from ip_message ',
					'status'       => $order_status[$value['status']],
					'country_code' => $value['country_code'],
					'user_ip'      => $value['ip'],
					'amount'       => $value['total'],
				);
			}

			$data['data'] = $_record;

		}
		else if($input['type'] == 'ex_orders'){
			$data['title'] = "External Orders";
			$record = $this->db->query('SELECT * FROM `integration_orders` WHERE user_id='. (int)$userdetails['id'])->result_array();
			
			$_record = array();
			foreach ($record as $_key => $value) {
				$_record[] = array(
					'created_at'   => $value['created_at'],
					'comment'      => 'Order from ip_message ',
					'status'       => 'Complete',
					'country_code' => $value['country_code'],
					'user_ip'      => $value['ip'],
					'amount'       => $value['total'],
				);
			}

			$data['data'] = $_record;

		}
		else if($input['type'] == 'action'){
			$data['title'] = "Actions Logs";
			

			$filter['type'] = "external_click_commission";
			$filter['is_action'] = 1;
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'hold_actions'){
			$data['title'] = "Hold Action Logs";
			

			$filter['type'] = "external_click_commission";
			$filter['is_action'] = 1;
			$filter['status'] = 0;
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'vendor_click'){
			$data['title'] = "Clicks Logs";
			$data['data'] = $this->Wallet_model->getVendorClick($userdetails['id']);
		}
		

		$data['html'] = $this->load->view("common/log_model",$data,true);

		echo json_encode($data);die;
	}
	public function logout(){
		$this->session->unset_userdata('user');
		$this->session->unset_userdata('client');
		$this->session->unset_userdata('guestFlow');
		$redirect = $this->input->get('redirect');
		if (!empty($redirect)) {
			$redirect = urldecode($redirect);
			$base = rtrim(base_url(), '/');
			if (strpos($redirect, $base) === 0) {
				redirect($redirect);
				return;
			}
			if (strpos($redirect, '/') === 0 && strpos($redirect, '//') !== 0) {
				redirect($redirect);
				return;
			}
		}
		redirect('/login');
	}
	public function deleteUser($id){
		$data['users'] = $this->admin_model->deleteUser($id);
		$this->session->set_flashdata('success', __('user.user_deleted_successfullly'));
		redirect('usercontrol/manageUsers');
	}
	public function addComission(){
		$post = $this->input->post(null,true);
		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('buyid', 'BuyId', 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('amount', 'Amount', 'required|trim', array('required' => '%s is required.')
		);
			$this->form_validation->set_rules('qty', 'Qty', 'required|trim', array('required' => '%s is required.')
		);
			

			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
			} else {
				$db = new MY_Controller();
				$userdetails=$db->userdetails();
				$kirim = array('RefiD'=>$userdetails['refid'],'buyiD'=>$post['buyid'],'userID'=>$userdetails['id'],'worlbit_qty'=>$post['qty'],'Amount'=>$post['amount']);
				

				$res = $this->commisioninfo->set_commission($kirim);
				$this->session->set_flashdata(array('flash' => array('success' => __('user.comission_added_successfully!'))));
				redirect('usercontrol/addComission', 'refresh');
			}
		}
		$data['title'] = 'Add Comission';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/dashboard/addComission', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function addUser(){
		$post = $this->input->post(null,true);
		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('firstname', __('user.first_name'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('lastname', __('user.last_name'), 'required|trim', array('required' => '%s is required.'));
			$this->form_validation->set_rules('username', __('user.username'), 'required|trim|is_unique[users.username]', array('required' => '%s is required'));
			$this->form_validation->set_rules('email', __('user.email'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('password', __('user.password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('conf_password', __('user.confirm_password'), 'required|trim|matches[password]', array('required' => '%s is required'));
			

			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
			} else {
				

				$res = array('firstname'=>$post['firstname'],'lastname'=>$post['lastname'],'email'=>$post['email'],'username'=>$post['username'],'password'=>sha1($post['password']),'updated_at'=>date('Y-m-d H:i:s'));
				

				$this->db->insert('users',$res);
				$this->session->set_flashdata(array('flash' => array('success' => __('user.user_added_successfully'))));
				redirect('usercontrol/manageUsers', 'refresh');
			}
		}
		$data['title'] = 'Add User';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/dashboard/addUser', $data);
		$this->load->view('usercontrol/includes/footer', $data);

	}
	public function editUser($id){
		$post = $this->input->post(null,true);
		if(isset($post['id']) && !empty($post['id'])){
			$res = array('firstname'=>$post['firstname'],'lastname'=>$post['lastname'],'updated_at'=>date('Y-m-d H:i:s'));
			$this->db->where('id',$post['id']);
			$this->db->update('users',$res);
			$this->session->set_flashdata(array('flash' => array('success' => __('user.user_profile_updated_successfully'))));
			redirect('usercontrol/manageUsers', 'refresh');
		}
		

		

		$data['users'] = $this->admin_model->getUsers($id);
		$data['title'] = 'Edit User';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/dashboard/edit-user', $data);
		$this->load->view('usercontrol/includes/footer', $data);

	}
	public function messages(){
		$data['title'] = 'Message';
		$this->load->model('Admin_model', 'admin_model');
		$data['users'] = $this->admin_model->getUsers($id=null);
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/dashboard/message', $data);
		$this->load->view('usercontrol/includes/footer', $data);

	}
	public function chatmessage(){
		$this->load->helper('smiley');
		$data['title'] = 'Message';
		$data['users'] = $this->admin_model->getUsers($id=null);
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('chat', $data);
		$this->load->view('usercontrol/includes/footer', $data); 	

	}

	public function google_login(){
		$get = $this->input->get(null,true);
		$clientId = '163214076002-9o582d2urnpc10nebsd032sgadhcgvmf.apps.googleusercontent.com'; //Google client ID
		$clientSecret = 'Ent8s37alsTYf6Ai8Z7y0Z6l'; //Google client secret
		$redirectURL = base_url() . 'admin/google_login/';
		

		//Call Google API
		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($clientId);
		$gClient->setClientSecret($clientSecret);
		$gClient->setRedirectUri($redirectURL);
		$google_oauthV2 = new Google_Oauth2Service($gClient);
		

		if(isset($get['code']))
		{
			$gClient->authenticate($get['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
		}
		

		if (isset($_SESSION['token']))
		{
			$gClient->setAccessToken($_SESSION['token']);
		}
		

		if ($gClient->getAccessToken()) {
			$userProfile = $google_oauthV2->userinfo->get();
			echo "<pre>";
			print_r($userProfile);
			die;
		}
		else
		{
			$url = $gClient->createAuthUrl();
			header("Location: $url");
			exit;
		}
	}

	public function store_orders($page = 1){
		$userdetails = $this->userdetails();
 
		if(empty($userdetails)){ redirect('/login'); }
		$data['status'] = $this->Order_model->status();

		unset($data['status']['0']);

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$page = max((int)$page,1);
			$post = $this->input->post(null,true);
			$filter = array(
				'limit' => 100,
				'page' => $page,
				'user_id' => $userdetails['id'],
				'is_vendor' => $userdetails['is_vendor'],
				'o_status_gt' => 1,
			);

			if(isset($post['filter_status']) && $post['filter_status'] != ''){
				$filter['o_status'] = $this->input->post('filter_status',true);
			}

			list($data['orders'],$total) = $this->Order_model->getAllOrders($filter);

			$data['start_from'] = (($page-1) * $filter['limit'])+1;
			$data['wallet_status'] = $this->Wallet_model->status();
			$json['html'] = $this->load->view("usercontrol/store/order_list.php",$data,true);

			$this->load->library('pagination');
			$config['base_url'] = base_url('usercontrol/store_orders/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);

			$json['pagination'] = $this->pagination->create_links();

			echo json_encode($json);die;
		}
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/store/orders', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function store_venodr_orders($page = 1){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$vendor_setting = $this->Product_model->getSettings('vendor');
		$store_setting = $this->Product_model->getSettings('store');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1) redirect('usercontrol/dashboard');

		$data['status'] = $this->Order_model->status();
		$data['myorder'] = 1;

		unset($data['status']['0']);


		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{

			$page = max((int)$page,1);
			$post = $this->input->post(null,true);
			$filter = array(
				'limit' => 100,
				'page' => $page,
				'user_id' => $userdetails['id'],
				'o_status_gt' => 1,
				'myorder'=>1
			);
 

			if(isset($post['filter_status']) && $post['filter_status'] != ''){
				$filter['o_status'] = $this->input->post('filter_status',true);
			}

			list($data['orders'],$total) = $this->Order_model->getAllOrders($filter);

			$data['start_from'] = (($page-1) * $filter['limit'])+1;
			$data['wallet_status'] = $this->Wallet_model->status();
			$json['html'] = $this->load->view("usercontrol/store/order_list.php",$data,true);

			$this->load->library('pagination');
			$config['base_url'] = base_url('usercontrol/store_venodr_orders/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);

			$json['pagination'] = $this->pagination->create_links();

			echo json_encode($json);die;
		}
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/store/vendor-orders', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function external_vendor_orders($page = 1){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$market_vendor['marketvendorstatus'] != 1) redirect('usercontrol/dashboard');

		$data['status'] = $this->Order_model->status();
		$data['myorder'] = 1;

		unset($data['status']['0']);

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$page = max((int)$page,1);
			$post = $this->input->post(null,true);
			$filter = array(
				'limit' => 100,
				'page' => $page,
				'user_id' => $userdetails['id'],
				'o_status_gt' => 1,
				'external_orders'=>1
			);
 
			if(isset($post['filter_status']) && $post['filter_status'] != ''){
				$filter['o_status'] = $this->input->post('filter_status',true);
			}

			list($data['orders'],$total) = $this->Order_model->getAllOrders($filter);

			$data['start_from'] = (($page-1) * $filter['limit'])+1;
			$data['wallet_status'] = $this->Wallet_model->status();
			$json['html'] = $this->load->view("usercontrol/store/order_list.php",$data,true);

			$this->load->library('pagination');
			$config['base_url'] = base_url('usercontrol/external_vendor_orders/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);

			$json['pagination'] = $this->pagination->create_links();

			echo json_encode($json);die;
		}
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/store/vendor-external-orders', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}


	public function store_logs($page = 0){

		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$page = max((int)$page,1);
			

			$filter = array(
				'limit'   => 100,
				'page'    => $page,
				'user_id' => $userdetails['id'],
			);
			$data['userdetails'] = $userdetails;

			list($data['clicks'],$total) = $this->Order_model->getAllClickLogs($filter);
			$data['start_from'] = (($page-1) * $filter['limit'])+1;

			$json['html'] = $this->load->view("usercontrol/store/log_list.php",$data,true);

			$this->load->library('pagination');
			$config['base_url'] = base_url('usercontrol/store_logs/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);


			$json['pagination'] = $this->pagination->create_links();
			echo json_encode($json);die;
		}
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/store/logs', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function store_markettools(){
		$userdetails = $this->userdetails();
		
		if(empty($userdetails)) redirect('login');

		$market_vendor = $this->Product_model->getSettings('market_vendor');

		if(! allowMarketVendorPanelSections($market_vendor['marketvendorpanelmode'], $userdetails['is_vendor'])) {
			redirect('login');
		}

		$restricted_vendors = $this->get_restricted_vendors();
		
		$this->load->model('Form_model');
		$this->load->model('Report_model');
		$this->load->model('Wallet_model');
		$this->load->model('IntegrationModel');


		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$escapevendors = $this->db->query('SELECT user_id,vendor_shares_sales_status,vendor_status FROM vendor_setting ')->result_array();

			$userrefid=$userdetails['refid']; 
			$allVendors = $this->db->query('SELECT id FROM users WHERE is_vendor=1')->result_array();
			$allowVendors = [];
			foreach($escapevendors as $esc) 
			{
				// Only proceed if vendor is active
				if($esc['vendor_status'] == 1) {
				if($esc['vendor_shares_sales_status']==1)
					$allowVendors[] = $esc['user_id'];
				else if($esc['vendor_shares_sales_status']==2 && $esc['user_id']==$userrefid)
					$allowVendors[] = $esc['user_id'];
				}
			}
 			
			$escapeUsers = [];
			foreach($allVendors as $v) {
				if(!in_array($v['id'], $allowVendors)){
					
					$escapeUsers[] = $v['id'];
				}
			}

  			$restricted_vendors=array_unique(array_merge($restricted_vendors, $escapeUsers));

			$post = $this->input->post(null,true);
			$get = $this->input->get(null,true);
			$json['form_default_commission'] = $this->Product_model->getSettings('formsetting');
			$json['default_commition']       = $this->Product_model->getSettings('productsetting');

			$filter = [
				'user_id'          => $userdetails['id'],
				'status'           => 1,
				'redirectLocation' => 1,
				'restrict'         => $userdetails['id'],
				'restrict_vendors' => $restricted_vendors,
				'not_show_my'	   => $userdetails['id'],
				'userdetails' => $userdetails
			];


			if (isset($post['category_id'])) {
				$filter['category_id'] = $post['category_id'];
			}
			if (isset($post['ads_name'])) {
				$filter['ads_name'] = $post['ads_name'];
			}

			if (isset($post['ads_name'])) {
				$filter['ads_name'] = $post['ads_name'];
			}

			 if($userdetails['is_vendor']==1)  // for vend
			 {	
				 if ($market_vendor['marketvendorstatus'] == 0) {
					$filter['marketvendorstatus'] = 1;
					if (isset($post['vendor_id'])) {
						$filter['vendor_id'] = $post['vendor_id'];
					}
				 }else{
				 	$filter['marketvendorstatus'] = 0;
				 }
			}
			else
			{
				// for aff
				if ($market_vendor['marketvendorstatus'] == 0) {
					$filter['marketvendorstatus'] = 0;
					if (isset($post['vendor_id'])) {
						$filter['vendor_id'] = $post['vendor_id'];
					}
				 }else{
				 	$filter['marketvendorstatus'] = 1;
				 }
			}

			$filter["is_vendor"]=$userdetails['is_vendor'];

			//filter for campaign
			$filtertools=$filter;
			if (isset($post['vendor_id'])) 
			{
				$filtertools['vendor_id'] = $post['vendor_id'];
			}
			
			$json['tools'] = $this->IntegrationModel->getProgramTools($filtertools);
			
			// Add user-specific view statistics for tools
			foreach ($json['tools'] as $key => $tool) {
				$json['tools'][$key]['my_view_statistics'] = $this->db->query("SELECT COUNT(*) as view_count FROM product_view_logs WHERE tools_id = '".(int)$tool['id']."' AND user_id = '".(int)$userdetails['id']."'")->row()->view_count;
			}
			


			//reject the marketTools banners on user banners page when external mode is disable
			$market_tools_status = $this->Product_model->getSettings('market_tools', 'status');
			if ($market_tools_status === null || !isset($market_tools_status['status'])) {
			    $market_tools_status['status'] = 1;
			}
			if (!$market_tools_status['status']) {
			    $json['tools'] = [];
			}
			//reject the marketTools banners on user banners page when external mode is disable

			
			$userrefid=$userdetails['refid']; 

			$allVendors = $this->db->query('SELECT id FROM users WHERE is_vendor=1')->result_array();
  
			$allowVendors = [];
			foreach($escapevendors as $esc) 
			{
				// Only proceed if vendor is active
				if($esc['vendor_status'] == 1) {
					if($esc['vendor_shares_sales_status']==1)
					$allowVendors[] = $esc['user_id'];
					else if($esc['vendor_shares_sales_status']==2 && $esc['user_id']==$userrefid)
					$allowVendors[] = $esc['user_id'];
				}
			}
 
			$escapeUsers = [];
			foreach($allVendors as $v) {
				if(!in_array($v['id'], $allowVendors))
					$escapeUsers[] = $v['id'];
			}
 	
			$products = [];
			
			$filter = ['product_status' => 1,'is_campaign_product'=> 0, 'restrict_vendors' => array_unique(array_merge($restricted_vendors, $escapeUsers))];
			
			if (isset($post['market_category_id'])) {
				$filter['category_id'] = $post['market_category_id'];
			}
			
			if (isset($post['ads_name'])) {
				$filter['ads_name'] = $post['ads_name'];
			}

			if (isset($post['vendor_id'])) {
				$filter['vendor_id'] = $post['vendor_id'];
			}

			$filter['not_show_my'] = $userdetails['id'];

			$store_setting = $this->Product_model->getSettings('store', 'store_mode');

			$filter['is_campaign_product'] = $store_setting['store_mode'] == 'sales' ? 1 : 0;

			if($store_setting['status']){ 
				$products = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);
			}

			$filter['show_to_affiliates'] = 1;
			$productsCampaign = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);
			
			$products = array_unique(array_merge($products, $productsCampaign),SORT_REGULAR);

			foreach ($products as $key => $value) {
				$slug_query = $this->db->query("SELECT slug FROM slugs WHERE type = 'product' AND related_id = '".(int)$value['product_id']."' AND user_id = '".(int)$userdetails['id']."'")->row();
				$products[$key]['slug'] = $slug_query ? $slug_query->slug : '';
				$products[$key]['is_product'] = 1;
				
				// Add user-specific view statistics for products
				$products[$key]['my_view_statistics'] = $this->db->query("SELECT COUNT(*) as view_count FROM product_view_logs WHERE product_id = '".(int)$value['product_id']."' AND user_id = '".(int)$userdetails['id']."'")->row()->view_count;
			}

			$filterform = [];

			if (isset($post['ads_name']) && !empty($post['ads_name'])) {
				$filterform['ads_name'] = $post['ads_name'];
			}
 
			if (isset($post['vendor_id'])) {
				$filterform['vendor_id'] = $post['vendor_id'];
			}

			$forms = $store_setting['store_mode'] != 'sales' ? $this->Form_model->getForms($userdetails['id'], $filterform) : [];

			//reject the store products and forms on user banners page when store is disable
			$store_status = $this->Product_model->getSettings('store', 'status');
			if ($store_status === null || !isset($store_status['status'])) {
			    $store_status['status'] = 1;
			}
			if (!$store_status['status']) {
			    $forms = [];
			}
			if (!$store_status['status']) {
			    $products = [];
			}
			//reject the store products and forms on user banners page when store is disable

			foreach ($forms as $key => $value) {
				$slug_query = $this->db->query("SELECT slug FROM slugs WHERE type = 'form' AND related_id = '".(int)$value['form_id']."' AND user_id = '".(int)$userdetails['id']."'")->row();
				$forms[$key]['slug'] = $slug_query ? $slug_query->slug : '';
				$forms[$key]['coupon_name']          = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
				$forms[$key]['public_page']          = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
				$forms[$key]['count_coupon']         = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
				$forms[$key]['seo']                  = str_replace('_', ' ', $value['seo']);
				$forms[$key]['is_form']              = 1;
				$forms[$key]['product_created_date'] = $value['created_at'];
				$forms[$key]['fevi_icon'] = $value['fevi_icon'] ? 'assets/images/form/favi/'.$value['fevi_icon'] : 'assets/images/no_image_available.png';
				
				// Add user-specific view statistics for forms
				$forms[$key]['my_view_statistics'] = $this->db->query("SELECT COUNT(*) as view_count FROM product_view_logs WHERE form_id = '".(int)$value['form_id']."' AND user_id = '".(int)$userdetails['id']."'")->row()->view_count;
				
				if($value['coupon']){
					$forms[$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
				}
			}
 			 
			$data_list = array_merge($products,$forms,$json['tools']);

			usort($data_list,function($a,$b){
				$ad = isset($a['product_created_date']) ? strtotime($a['product_created_date']) : strtotime($a['created_at']);
				$bd = isset($b['product_created_date']) ? strtotime($b['product_created_date']) : strtotime($b['created_at']);;
				return ($ad-$bd);
			});		
			
			$json['data_list'] = $data_slice=array_reverse($data_list);

			$award_level = $this->Product_model->getSettings('award_level', 'status');
			$json['award_level_status'] = $award_level['status'];

			$comission_sale_status = 0;
			$sale_comission_rate = 0;
			$userPlan = App\MembershipUser::select('membership_plans.commission_sale_status','award_level.sale_comission_rate')->join('membership_plans','membership_plans.id','=','membership_user.plan_id')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('is_active',1)->where('user_id',$userdetails['id'])->first();
			if($userPlan->commission_sale_status){
				$comission_sale_status = $userPlan->commission_sale_status;
				$sale_comission_rate = $userPlan->sale_comission_rate;
			} else {
				$userLevel = $this->Product_model->getByField('award_level','id',$userdetails['level_id']);
				if($userLevel){
					$comission_sale_status = 1;
					$sale_comission_rate = $userLevel['sale_comission_rate'];
				}
			}
			$json['userComission']['status'] = $comission_sale_status;
			$json['userComission']['value'] = $sale_comission_rate;

			/*=============== Pagination======================*/
			
			$perpage = 25;
			$data['page'] = isset($post['page']) ? (int)$post['page'] : 1;
			$data['data_list'] = array_slice($data_slice,($data['page']-1)*$perpage,$perpage);
			$json['data_list'] = array_slice($data_slice,($data['page']-1)*$perpage,$perpage);
			
			$this->load->library('pagination');
			
			$config['base_url'] = base_url('usercontrol/store_markettools');
			$config['cur_page'] = $data['page'];
			$config['per_page'] = $perpage;
			$config['total_rows'] = count($data_slice);
			$config['use_page_numbers'] = TRUE;
			

			$config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination">';        
		    $config['full_tag_close'] = '</ul></nav>';        
		    $config['first_link'] = 'First';        
		    $config['last_link'] = 'Last';        
		    $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';        
		    $config['first_tag_close'] = '</span></li>';        
		    $config['prev_link'] = '&laquo';        
		    $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';        
		    $config['prev_tag_close'] = '</span></li>';        
		    $config['next_link'] = '&raquo';        
		    $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';        
		    $config['next_tag_close'] = '</span></li>';        
		    $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';        
		    $config['last_tag_close'] = '</span></li>';        
		    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';        
		    $config['cur_tag_close'] = '</a></li>';        
		    $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';        
		    $config['num_tag_close'] = '</span></li>';

			$this->pagination->initialize($config);
			$json['pagination'] = $this->pagination->create_links();

			$json['view'] = $this->load->view("usercontrol/store/markettools_list",$json,true);

			echo json_encode($json);die;
		}

		if(isset($restricted_vendors) && count($restricted_vendors)>0) 
		{
			$restricted_vendors_str=implode(",",$restricted_vendors);
 			$data['vendors_list'] = $this->db->query("SELECT id,username FROM users WHERE type = 'user' AND is_vendor=1 AND id!=".$userdetails['id'] . " and id not in ($restricted_vendors_str) ")->result_array();
		}
 		else
 		{
 			$data['vendors_list'] = $this->db->query("SELECT id,username FROM users WHERE type = 'user' AND is_vendor=1 AND id!=".$userdetails['id'])->result_array();
 		}


		$data['categories'] = $this->db->query("SELECT DISTINCT integration_category.id  as value ,integration_category.name as label, CASE WHEN integration_category.parent_id=0 THEN integration_category.id ELSE integration_category.parent_id END AS pid FROM `integration_category`
			 inner JOIN integration_tools on integration_tools.category=	 integration_category.id 
		 order by pid,integration_category.id")->result_array();
		
		$data['store_categories'] = $this->db->query("SELECT id as value,name as label FROM categories WHERE 1")->result_array();

		$this->load->library("socialshare");	

		
		$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();


		$this->view($data, 'store/markettools', 'usercontrol');
	}

	public function listproduct_ajax($page = 1){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/'); }
		$store_setting =$this->Product_model->getSettings('store');
		if(!$store_setting['status']){redirect('/usercontrol/dashboard');}

		$get = $this->input->get(null,true);
		$post = $this->input->post(null,true);
		$filter = array(
			'page' => isset($get['page']) ? $get['page'] : $page,
			'limit' => 20,
			'only_admin_product' => 1,
		);

		$record = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);
		$data['productlist'] = $record['data'];
		$data['store_setting'] = $store_setting;
		$data['default_commition'] =$this->Product_model->getSettings('productsetting');

		$json['view'] = $this->load->view("usercontrol/product/product_list", $data, true);

		$this->load->library('pagination');
		$this->pagination->cur_page = $filter['page'];

		$config['base_url'] = base_url('usercontrol/listproduct_ajax');
		$config['per_page'] = $filter['limit'];
		$config['total_rows'] = $record['total'];
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['enable_query_strings'] = TRUE;
		$_GET['page'] = $filter['page'];
		$config['query_string_segment'] = 'page';
		$this->pagination->initialize($config);
		$json['pagination'] = $this->pagination->create_links();

		echo json_encode($json);

	}
	public function listproduct(){
		$userdetails = $this->userdetails();
		

		if(empty($userdetails)){ redirect('/login'); }

		$store_setting =$this->Product_model->getSettings('store');
		if(!$store_setting['status']){
			redirect('/usercontrol/dashboard');		

		}
		$this->load->model('Form_model');

		$data['totals'] = $this->Wallet_model->getTotals(array('user_id' => $userdetails['id']), true);

		$data['ordercount'] =$this->db->query('SELECT COUNT(op.id) AS total FROM `order_products` op LEFT JOIN `order` AS o ON o.id = op.order_id WHERE o.status > 0 AND op.`refer_id` = '. (int)$userdetails['id'] )->row()->total;

		$data['user'] = $userdetails;
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/product/index', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function managereferenceusers(){redirect('usercontrol/my_network');}
	
	public function my_network(){

		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$referlevelSettings = $this->Product_model->getSettings('referlevel');
		$disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
		$refer_status = true;

		if((int)$referlevelSettings['status'] == 0){ show_404(); }
		else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }


		$userlist = $this->Product_model->getAllUsersTreeV3(array(),$userdetails['id']);
		
		$userDetailsWithPing = $this->getAllUsersWithOnlineStatus();


		$site_setting = $this->Product_model->getSettings('site');
		$referlevel_setting = $this->Product_model->getSettings('referlevel');
		
		if($referlevel_setting['show_sponser'] == 'none'){
			$data['userslist'] = $userlist;
		}
		else if($referlevel_setting['show_sponser'] == 'real_sponser'){

			$userdetails['refid'] = $userdetails['refid'] > 0 ? $userdetails['refid'] : 1;

			$admin_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE  id= " . (int)$userdetails['refid'])->row_array();

			if($admin_result){
				$_children = [];
				$_children[] = array(
					'id'    => $admin_result['id'],
					'name'  => $admin_result['name'] ."<img class='user-avtar-tree' src='". $this->Product_model->getAvatar($admin_result['avatar']) ."'>",
					'children' => $userlist,
				);

				$data['userslist'] = $_children;
			}

			if(!isset($data['userslist'])){
				$data['userslist'] = $userlist;
			}
			
		} else{
			$admin_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='admin'")->row_array();

			
			$_children = [];
			$_children[] = array(
				'id'    => $admin_result['id'],
				'name'  => ($referlevel_setting['sponser_name'] ? $referlevel_setting['sponser_name'] : $admin_result['name']) ."<img class='user-avtar-tree' src='". $this->Product_model->getAvatar($admin_result['avatar']) ."'>",
				'children' => $userlist,
			);
			
			$data['userslist'] = $_children;
		}

		$data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);
		$data['userslistDetail'] = $userDetailsWithPing; // Pass user details with ping data
		$data['userdetails'] = $userdetails; // Pass current user details to view
		

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/users/my_network', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	
	public function addpayment($id = null){
	    $userdetails = $this->userdetails();
	    if(empty($userdetails)){ redirect('/login'); }
	    $post = $this->input->post(null,true);
	    if (isset($post['add_paypal'])) {
	        $email = $this->input->post('paypal_email',true);
	        if ((int)$post['id'] > 0) {
	            $this->db->update("paypal_accounts", array(
	                'paypal_email' => $email,
	                'user_id' => $userdetails['id'],
	            ),
	            array(
	                'id' => $post['id']
	            ));
	        }
	        else {
	            $this->db->insert("paypal_accounts", array(
	                'paypal_email' => $email,
	                'user_id' => $userdetails['id'],
	            ));
	        }
	        $this->session->set_flashdata('success', __('user.paypal_account_saved_successfully'));
	        redirect('usercontrol/mywallet/#tab-paymentdetails');
	    } else if(!empty($post)){
	        $this->load->helper(array('form', 'url'));
	        
	        $this->load->library('form_validation');
	        
	        $this->form_validation->set_rules('payment_account_number', __('user.account_number'), 'required');
	        $this->form_validation->set_rules('payment_account_name', __('user.account_name'), 'required');
	        $this->form_validation->set_rules('payment_bank_name', __('user.bank_name'), 'required');
	        
	        // Add country and its specific code validation if mode is specific
	        $payment_methods = $this->Withdrawal_payment_model->getEnabledPaymentMethods();
	        if(isset($payment_methods['bank_transfer']['bank_transfer_mode']) && 
	           $payment_methods['bank_transfer']['bank_transfer_mode'] == 'specific') {
	            $this->form_validation->set_rules('payment_country', __('user.country'), 'required');
	            if($post['payment_country'] == 'US') {
	                $this->form_validation->set_rules('payment_routing_number', __('user.routing_number'), 'required');
	            }
	        }

	        if($this->form_validation->run()) {
	            $errors = array();
	            
	            $details = array(
	                'payment_bank_name'      => $this->input->post('payment_bank_name',true),
	                'payment_account_number' => $this->input->post('payment_account_number',true),
	                'payment_account_name'   => $this->input->post('payment_account_name',true),
	                'payment_status'         => 1,
	                'payment_ipaddress'      => $_SERVER['REMOTE_ADDR'],
	            );

	            // Add country and its specific code if set
	            if(isset($post['payment_country'])) {
	                $details['payment_country'] = $post['payment_country'];
	                if($post['payment_country'] == 'US' && isset($post['payment_routing_number'])) {
	                    $details['payment_routing_number'] = $post['payment_routing_number'];
	                }
	            }

	            if(empty($errors)){
	                if( (int)$post['payment_id'] > 0 ){
	                    $this->session->set_flashdata('success', __('user.payment_updated_successfully'));
	                    $details['payment_updated_by'] = $userdetails['id'];
	                    $details['payment_updated_date'] = date('Y-m-d H:i:s');
	                    $this->Product_model->update_data('payment_detail', $details, array('payment_id' => (int)$post['payment_id']));
	                }
	                else {
	                    $this->session->set_flashdata('success', __('user.payment_added_successfully'));
	                    $details['payment_created_by'] = $userdetails['id'];
	                    $details['payment_created_date'] = date('Y-m-d H:i:s');
	                    $this->Product_model->create_data('payment_detail', $details);
	                }
	                redirect('usercontrol/mywallet/#tab-paymentdetails');
	            } else {
	                if(!empty($id)){
	                    $this->session->set_flashdata('error', $errors['avatar_error']);
	                    redirect('usercontrol/mywallet/#tab-paymentdetails');
	                } else {
	                    $this->session->set_flashdata('error', $errors['avatar_error']);
	                    redirect('usercontrol/mywallet/#tab-paymentdetails');
	                }
	            }
	        } else {
	            $this->session->set_flashdata('error', __('user.form_validation_error'));
	            redirect('usercontrol/addpayment');
	        }
	        
	    } else {
	        redirect('usercontrol/mywallet/#tab-paymentdetails');
	    }           
	}

	public function generateproductcode($affiliateads_id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		else {
			if($affiliateads_id){
				$data['getProduct'] = $this->Product_model->getProductByIdArray($affiliateads_id);
				if (empty($data['getProduct']) || !$this->Product_model->user_can_promote_market_campaign($userdetails, $data['getProduct'])) {
					show_404();
				}
				$data['product_id'] = $affiliateads_id;	

				$data['user_id'] = $userdetails['id'];	

				$this->load->view('usercontrol/product/generatecode', $data);

			}
		}
	}

	public function listbuyproduct(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$data['buyproductlist'] = $this->Product_model->getAllBuyProduct($userdetails['id']);
		

		$data['user'] = $userdetails;
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/product/listofallbuyproduct', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function listbuyaffiproduct(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$store_setting = $this->Product_model->getSettings('store');
		if(!$store_setting['status']){ show_404(); }

		$filter = array( 'affiliate_id' => $userdetails['id'] );

		$data['buyproductlist'] = $this->Order_model->getOrders($filter);
		foreach ($data['buyproductlist'] as $key => $value) {
			$p = $this->Order_model->getProducts($value['id'],['refer_id' => $userdetails['id']]);
			$t = $this->Order_model->getTotals($p,array());
			$data['buyproductlist'][$key]['total'] = $t['total']['value'];
		}

		$data['status'] = $this->Order_model->status();
		$data['user'] = $userdetails;

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/product/listbuyaffiproduct', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function editProfile(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else { $id =  $userdetails['id']; }

		$this->load->model('PagebuilderModel');
		$this->load->model('User_model');
		if ($this->input->post()) {

			$this->load->library('form_validation');
			$this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
			$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
			$this->form_validation->set_rules('country_id', 'Country', 'required');
			$post = $this->input->post(null,true);

			if($post['password'] != ''){
				$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim', array('required' => '%s is required'));
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
			}

			$json['errors'] = array();

			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			if($register_form){
				$customField = json_decode($register_form['registration_builder'],1);

				$filesAttached = [];

				$this->load->helper('string');

				$mobile_validation_done = false;

				foreach ($customField as $_key => $_value) {

					$mobile_validation         = (isset($_value['mobile_validation']) && $_value['mobile_validation'] ) ? $_value['mobile_validation'] : '';

					if($mobile_validation == 'true' && $mobile_validation_done == false) {
						$field_name = 'phone';
						$mobile_validation_done = true;
					} else {
						$field_name = 'custom_'. $_value['name'];
					}

					$config['upload_path'] = "assets/user_upload/";
					$config['allowed_types'] = 'png|gif|jpeg|jpg|PNG|GIF|JPEG|JPG|ICO|ico|pdf|docx|doc|ppt|xls|txt';
					$config['max_size']      = 2048;

					if($_value['type'] == 'file') {
						if(isset($post['existing_'.$field_name])){
							if(is_array($post['existing_'.$field_name])) {
								$attahced_multi_azkja = $post['existing_'.$field_name];
							} else {
								$attahced_multi_azkja = [$post['existing_'.$field_name]];
							}
						} else {
							$attahced_multi_azkja = [];
						}
						if(is_array($_FILES[$field_name]['name'])) {
							if(isset($_FILES[$field_name]['name'][0]) && !empty($_FILES[$field_name]['name'][0])) {
								
								foreach ($_FILES[$field_name]['name'] as $key => $image) {
									$_FILES['attahced_multi_azkja']['name']= $_FILES[$field_name]['name'][$key];
									$_FILES['attahced_multi_azkja']['type']= $_FILES[$field_name]['type'][$key];
									$_FILES['attahced_multi_azkja']['tmp_name']= $_FILES[$field_name]['tmp_name'][$key];
									$_FILES['attahced_multi_azkja']['error']= $_FILES[$field_name]['error'][$key];
									$_FILES['attahced_multi_azkja']['size']= $_FILES[$field_name]['size'][$key];

									$config['file_name']  = random_string('alnum', 32);
									
									$this->load->library('upload', $config);
									
									$this->upload->initialize($config);

									if (!$this->upload->do_upload('attahced_multi_azkja')) {
										$error = $this->upload->display_errors();
										if(!str_contains($error, 'select a file')){
											$json['errors'][$field_name] = $error;
											break;
										} else {
											if((!isset($filesAttached[$field_name]) || empty($filesAttached[$field_name])) && isset($_value['required']) && $_value['required']) {
												$json['errors'][$field_name] = $error;
												break;
											}
										}
									} else {
										$ext = explode('.', $_FILES[$field_name]['name'][$key]);
										$attahced_multi_azkja[] = $config['file_name'].".".$ext[sizeof($ext)-1];
									}
								}
							} 

							
						} else {
							if(isset($_FILES[$field_name]['name']) && !empty($_FILES[$field_name]['name'])) {
								
								$config['file_name']  = random_string('alnum', 32);
								
								$this->load->library('upload', $config);
								
								$this->upload->initialize($config);

								if (!$this->upload->do_upload($field_name)) {
									$error = $this->upload->display_errors();
									if(!str_contains($error, 'select a file')){
										$json['errors'][$field_name] = $error;
										break;
									} else {
										if((!isset($filesAttached[$field_name]) || empty($filesAttached[$field_name])) && isset($_value['required']) && $_value['required']) {
											$json['errors'][$field_name] = $error;
											break;
										}
									}
								} else {
									$ext = explode('.', $_FILES[$field_name]['name']);
									$attahced_multi_azkja = [$config['file_name'].".".$ext[sizeof($ext)-1]];
								}
							}
						}

						$filesAttached[$field_name] = $attahced_multi_azkja;
						
						if(isset($_value['required']) && $_value['required'] && (!isset($filesAttached[$field_name]) || empty($filesAttached[$field_name]))) {
							$json['errors'][$field_name] = "Please select file for upload!";
							break;
						}
					} else {
						if($_value['required'] == 'true'){
							if(!isset($post[$field_name]) || $post[$field_name] == ''){
								$json['errors'][$field_name] = $_value['label'] ." is required.!";
							}
						}

						if(!isset($json['errors'][$field_name]) && (int)$_value['maxlength'] > 0){
							if(strlen( $post[$field_name] ) > (int)$_value['maxlength']){
								$json['errors'][$field_name] = $_value['label'] ." Maximum length is ". (int)$_value['maxlength'];
							}
						}

						if(!isset($json['errors'][$field_name]) && (int)$_value['minlength'] > 0){
							if(strlen( $post[$field_name] ) > (int)$_value['minlength']){
								$json['errors'][$field_name] = $_value['label'] ." Minimum length is ". (int)$_value['minlength'];
							}
						}
					}

				}

			}
			
			if ($this->form_validation->run() == FALSE) {
				$json['errors'] = array_merge($this->form_validation->error_array(), $json['errors']);
			}
			if( count($json['errors']) == 0){
				$checkmail = $this->Product_model->checkmail($this->input->post('email',true),$id);

				if(!empty($checkmail)){ $json['errors']['email'] = "Email Already Exist"; }

				if(count($json['errors']) == 0){

					$custom_fields = array();
					$post = $this->input->post(null,true);
					foreach ($this->input->post() as $key => $value) {
						if(!in_array($key, array('id', 'plan_id', 'refid', 'level_id', 'type', 'firstname', 'lastname', 'email', 'username', 'password', 'phone', 'twaddress', 'address1', 'address2', 'ucity', 'ucountry', 'state', 'uzip', 'avatar', 'online', 'unique_url', 'bitly_unique_url', 'updated_at', 'google_id', 'facebook_id', 'twitter_id', 'umode', 'PhoneNumber', 'Addressone', 'Addresstwo', 'City', 'Country', 'StateProvince', 'Zip', 'f_link', 't_link', 'l_link', 'products_wishlist', 'product_commission', 'affiliate_commission', 'product_commission_paid', 'affiliate_commission_paid', 'product_total_click', 'product_total_sale', 'affiliate_total_click', 'sale_commission', 'sale_commission_paid', 'status', 'reg_approved', 'is_vendor', 'store_meta', 'store_slug', 'store_name', 'store_contact_us_map', 'store_address', 'store_email', 'store_contact_number', 'store_terms_condition', 'value', 'last_ping', 'install_location_details', 'token', 'created_at', 'device_type', 'device_token', 'groups', 'email_subscription', 'cpassword','country_id','action')) && !strpos($key, "_afftel_input_pre")){
							if(isset($post[$key."_afftel_input_pre"]) && ! empty($post[$key."_afftel_input_pre"]) && ! empty($value)) {
	                    		$custom_fields[$key] = "+".$post[$key."_afftel_input_pre"]." ".$value;
                    		} else {
	                    		$custom_fields[$key] = $value;
	                    	}
						}
					}

					$phone = $this->input->post('phone',true);

					$phone_afftel_input_pre = $this->input->post('phone_afftel_input_pre',true);
                	
                	if(! empty($phone_afftel_input_pre) && ! empty($phone)) {
                		$phone = "+".$phone_afftel_input_pre." ".$phone;
                	}
                	
					$userArray = array(
						'firstname'                 => $this->input->post('firstname',true),
						'lastname'                  => $this->input->post('lastname',true),
						'email'                     => $this->input->post('email',true),
						'ucountry'                 	=> $this->input->post('country_id',true),
						'Country'                 	=> $this->input->post('country_id',true),
						'phone'                     => $phone,
						'value'                    	=> json_encode(array_merge($custom_fields, $filesAttached)),
					);

					if($post['password'] != ''){
						$userArray['password'] = sha1( $post['password'] );
					}

					if(!empty($_FILES['avatar']['name'])){
						$upload_response = $this->upload_photo('avatar','assets/images/users');

						if($upload_response['success']){
							$userArray['avatar'] = $upload_response['upload_data']['file_name'];
						}
					}

					$this->user->update_user($id, $userArray);
					$userArray = $this->db->query("SELECT * FROM users WHERE id = ". (int)$id)->row_array();
					$this->session->set_userdata(array('user'=>$userArray));


					$this->session->set_flashdata('success', 'Profile Updated Successfully');
					$json['location'] = base_url('usercontrol/editProfile/');
				}
			}

		if (isset($_POST['email_subscription'])) {
			$subEmail = $this->input->post('email', true);
			if (!empty($subEmail) && filter_var($subEmail, FILTER_VALIDATE_EMAIL)) {
				if ((int)$_POST['email_subscription'] === 1) {
					$this->db->delete('unsubscribed_emails', ['email' => $subEmail]);
				} else {
					$unsbscribed = $this->db->get_where('unsubscribed_emails', ['email' => $subEmail])->row();
					if (empty($unsbscribed)) {
						$this->db->insert('unsubscribed_emails', [
							'email'           => $subEmail,
							'unsubscribed_at' => date('Y-m-d H:i:s'),
							'source'          => 'profile_page',
						]);
					}
				}
			}
		}


			echo json_encode($json);die;
		} else {
			$data['user']  = (array)$this->user->get($id);
			$data['countries'] = $this->User_model->getCountries();
			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			$data['data'] = json_decode($register_form['registration_builder'],1);
			$data['edit_view'] = true;
			$data['user_groups'] = $this->User_model->getgrouplist();
			$data['user_groups_readonly'] = true;
			$data['disable_username'] = true;
			$data['html_form'] = $this->load->view('auth/user/templates/register_form',$data, true);

			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('usercontrol/users/edit_profile', $data);
			$this->load->view('usercontrol/includes/footer', $data);
		}
		

		function getstate($country_id = null) {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				redirect('usercontrol');
			}
			else {
				$states = $this->Product_model->getAllstate($country_id);
				echo '<option selected="selected">Select State</option>';
				if(!empty($states)){
					foreach($states as $state){
						echo '<option value="'.$state['name'].'">'.$state['name'].'</option>';
					}
				}
				die;
				

			}
		}
	}

	public function friendly_seo_string($vp_string){
		$vp_string = trim($vp_string);
		$vp_string = html_entity_decode($vp_string);	

		$vp_string = strip_tags($vp_string);
		$vp_string = strtolower($vp_string);	

		$vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
		$vp_string = preg_replace('~ ~', '-', $vp_string);

		$vp_string = preg_replace('~-+~', '-', $vp_string);
		return $vp_string;
	}

	public function upload_photo($fieldname,$path) {
		

		$config['upload_path'] = $path;
		$config['allowed_types'] = 'png|gif|jpeg|jpg';
		

		$this->load->helper('string');
		$config['file_name']  = random_string('alnum', 32);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		

		if (!$this->upload->do_upload($fieldname)) {
			echo $this->upload->display_errors();
			die;
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

	public function updatenotify($country_id = null) {
		$userdetails = $this->userdetails();
		$post = $this->input->post(null,true);

		if(empty($userdetails)){ redirect('/login'); }
		else {
			if(!empty($post['id'])){
				$noti = $this->db->query("SELECT * FROM notification WHERE notification_id= ". $post['id'])->row();
				

				if($noti->notification_type == 'integration_click'){
					$json['location'] = base_url('usercontrol/'.$noti->notification_url);
				}
				else if($noti->notification_type == 'integration_orders'){
					$json['location'] = base_url('usercontrol/'.$noti->notification_url);
				} else{
					$json['location'] = base_url('usercontrol/'.$noti->notification_url);
				}
				

				$this->Product_model->update_data('notification', array('notification_is_read' => 1),array('notification_id' => $post['id']));
			}
		}

		echo json_encode($json);
	}

	public function getnotificationnew() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotificationnew('user', $userdetails['id']);
			echo trim(count($notifications));
		}
	}
	public function getnotificationall() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotificationall('user', $userdetails['id']);
			echo trim(count($notifications));
		}
	}
	public function delete_image($image_id = null){
		$userdetails = $this->userdetails();
		$post = $this->input->post(null,true);

		if(empty($userdetails)){ redirect('/login'); }
		else {
			if(!empty($post['image_id'])){
				$this->Product_model->deleteImage($post['image_id']);
			}
		}
	}
	public function getnotification() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotification('user', $userdetails['id']);
			if(!empty($notifications)){
				foreach($notifications as $notification){
					if($notification['notification_type'] == 'order'){
						if($notification['notification_view_user_id'] == $userdetails['id']){
							echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
							<div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
							<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
							</a>';
						}
					}
					

					if($notification['notification_type'] == 'client'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account-circle"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					

					if($notification['notification_type'] == 'paymentrequest'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account-circle"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					

					if($notification['notification_type'] == 'user'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					

					if($notification['notification_type'] == 'product'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-basket"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					

					if($notification['notification_type'] == 'commission'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-basket"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					

					if($notification['notification_type'] == 'commissionrequest'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-cash-usd"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					

				}
			}
			die;
			

		}
	}
	public function vieworder($order_id){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$this->load->model('Form_model');
		$data['order'] = $this->Order_model->getOrder($order_id);
		$data['products'] = $this->Order_model->getProducts($order_id,['vendor_or_refer_id' => $userdetails['id']]);
		if ($data['products']) {
			$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
			$data['payment_history'] = $this->Order_model->getHistory($order_id);
			$data['status'] = $this->Order_model->status();
			$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
			$data['totals'] = $this->Order_model->getTotals($data['products'], $data['order']);

			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('usercontrol/product/vieworder', $data);
			$this->load->view('usercontrol/includes/footer', $data);
		} else {
			redirect('usercontrol/dashboard');
		}
	}

	public function all_transaction(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)) redirect('/login');

		$filter = $this->input->post(null,true);

		$this->load->helper('utility');
		
		$settings = get_pagination_settings(['per_page' => 10]);
		$per_page = $settings['per_page'];
		$current_page = isset($filter['page']) ? (int)$filter['page'] : 1;

		$all_transactions_full = $this->Wallet_model->getAllTransaction($userdetails,$filter,false);
		$total_rows = count($all_transactions_full);

		$pagination_data = ajax_pagination($total_rows, $current_page, [
			'per_page' => $per_page,
			'js_function' => 'callAjaxForFilter'
		]);

		$view['pagination'] = $pagination_data['html'];
		$view['all_transaction'] = $this->Wallet_model->getAllTransaction($userdetails,$filter,$per_page);
		
		$html = $this->load->view("usercontrol/users/parts/all_transaction",$view,true);
		if($filter){
			echo $html;
			die();
		}

		$data['html'] = $html;
		
		$this->load->config('payment_gateway');

		$data['payment_module'] =  config_item('payment_module');

		$data['filter_field'] =  $this->Wallet_model->getAllTransactionFilter($userdetails);

		$this->view($data,'users/all_transaction','usercontrol');
	}

	public function all_transaction_export_to_excel(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)) redirect('/login');

		$filter = $this->input->get(null,true);

		$this->load->helper('all_transaction');
		$all_transaction = $this->Wallet_model->getAllTransaction($userdetails,$filter,false);
		exportToExcel($all_transaction);
	}

	public function all_transaction_export_to_pdf(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)) redirect('/login');

		$filter = $this->input->get(null,true);

		$this->load->helper('all_transaction');
		$all_transaction = $this->Wallet_model->getAllTransaction($userdetails,$filter,false);
		exportToPdf($userdetails['admin'],$all_transaction);
	}

	public function wallet_requests_details($id){
		$userdetails = $this->userdetails();
		if (empty($userdetails)) { redirect('/login'); }

		$id = (int)$id;

		$data['request'] = $this->db->query("SELECT * FROM wallet_requests WHERE id={$id} AND user_id={$userdetails['id']}")->row_array();
		if (!$data['request']) {
			show_404();
		}

		// decode settings
		$settings = json_decode($data['request']['settings'], true);
		if (!is_array($settings)) $settings = [];

		$countryFieldMap = get_country_field_map();

		$selectedCountry = isset($settings['payment_country']) ? $settings['payment_country'] : '';

		// if country field is missing, pull it from DB
		if ($selectedCountry && isset($countryFieldMap[$selectedCountry])) {
			$field = $countryFieldMap[$selectedCountry];
			if (!isset($settings[$field])) {
				$paymentData = $this->Product_model->getAllPayment($userdetails['id']);
				if (!empty($paymentData[0][$field])) {
					$settings[$field] = $paymentData[0][$field];
				}
			}
		}

		// save only correct settings into the request
		$data['request']['settings'] = json_encode($settings);

		$filter = [
			'user_id' => $userdetails['id'],
			'id_in' => $data['request']['tran_ids'],
		];
		$data['transaction'] = $this->Wallet_model->getTransaction($filter);
		$data['status'] = $this->Wallet_model->status();
		$data['status_icon'] = $this->Wallet_model->status_icon;

		$this->view($data, 'users/wallet_requests_details', 'usercontrol');
	}


	public function wallet_requests_list(){
		$userdetails = $this->userdetails();
		$get = $this->input->get(null,true);
		$post = $this->input->post(null,true);

		if(empty($userdetails)){ redirect('/login'); }

		if (isset($post['delete_request'])) {

			$id= (int)$post['id'];

			$req = $this->db->query("SELECT * FROM wallet_requests WHERE id={$id}")->row();

			if($req){

				$this->load->model('Payout_batch_model');
				$this->Payout_batch_model->detach_wallet_request_from_batch((int) $id);

				if($req->tran_ids){

					$this->db->query("UPDATE wallet SET status=1 WHERE id in (". $req->tran_ids .") ");

				}

				$this->db->query("DELETE FROM wallet_requests WHERE id= {$id}");

				$this->db->query("DELETE FROM wallet_requests_history WHERE id= {$id}");

			}

			$json['success'] = 1;

			echo json_encode($json);die;

		}

		$data['lists'] = $this->db->query("SELECT * FROM wallet_requests WHERE user_id=". $userdetails['id']. " ORDER BY id DESC")->result_array();

		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 2,
			'old_with' => 1,
		);

		$data['status'] = $this->Wallet_model->status();
		$data['status_icon'] = $this->Wallet_model->status_icon;
		$data['payout_transaction'] = $this->Wallet_model->getTransaction($filter);
		$data['notcheckmember'] = 1;
		$this->view($data,'users/wallet_requests_list','usercontrol');
	}


	public function my_deposits(){
		$userdetails = $this->userdetails();
		
		if(empty($userdetails)) redirect('usercontrol/dashboard');

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1)
			redirect('usercontrol/dashboard');

		$get = $this->input->get(null,true);
		$post = $this->input->post(null,true);

		$this->load->model('Deposit_payment_model');

		$data['total_deposited'] = $this->db->query("SELECT SUM(vd_amount) as total FROM vendor_deposit WHERE vd_status=1 AND vd_user_id={$userdetails['id']} {$deposit_where} ")->row()->total;


		if(empty($data['total_deposited'])) {
			$data['total_deposited'] = 0;
		}

		$data['status']= $status = $this->Deposit_payment_model->status;
		
		$data['status_icon']= $status_icon = $this->Deposit_payment_model->status_icon;

		if(isset($get['vd'])){
			$this->session->set_flashdata('success', __('user.deposit_payment_success'));
			redirect(base_url('usercontrol/my_deposits'));
		}
		
		if (isset($post['get_deposit'])) {
			$get = $this->input->post(null,true);
			$filter = array(
				'user_id' => $userdetails['id']
			);

			if (isset($post['date'])) {
				$filter['date'] = $post['date'];
				$data['date'] = $filter['date'];
			}

			$this->load->model('Deposit_payment_model');

			$lists = $this->Deposit_payment_model->getDeposits($filter);

			$json['html'] = "";

			foreach ($lists as $key => $value) { 
				$json['html'] .= '<tr>
				<td>'. $value['vd_id'] .'</td>
				<td>'. $value['username'] .'</td>
				<td>'. dateFormat($value['vd_created_on'],'d F Y') .'</td>
				<td>'. $value['vd_payment_method'] .'</td>
				<td>'. $value['vd_txn_id'] .'</td>
				<td>'. c_format($value['vd_amount']) .'</td>
				<td>'. withdrwal_status($value['vd_status']) .'</td>
				<td class="text-right">
				<a href="'. base_url('usercontrol/deposit_details/'. $value['vd_id']) .'" class="btn btn-primary btn-sm">Details</a>
				</td>
				</tr>';
			}

			echo json_encode($json);die;
		}
		
		$data['vendorDepositStatus'] = $this->Product_model->getSettings('vendor', 'depositstatus');
		$data['vendorMinDepositAmt'] = $this->Product_model->getSettings('site', 'vendor_min_deposit');
		
		$this->view($data,'users/deposit_requests_list','usercontrol');
	}

	public function deposit_details($id)
	{
		$userdetails = $this->userdetails();

		$id=(int)$id;

		if(empty($userdetails)){ redirect('/login'); }


		$this->load->model('Deposit_payment_model');

		$data['request'] = $this->Deposit_payment_model->getDeposits(['vd_id'=>$id]);

		if(!$data['request']){
			show_404();
		}

		$data['status_list'] = $this->Deposit_payment_model->status_list;

		$this->view($data,'users/deposit_details','usercontrol');
	}

	public function mywallet(){

		$userdetails = $this->userdetails();

		$get = $this->input->get(null,true);

		if(empty($userdetails)){ redirect('/login'); }
		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 0,
 			'parent_id' => 0,
		);

		if ( isset($get['type']) && $get['type'] ) {
			$filter['types'] = $get['type'];
		}

		if (isset($get['paid_status']) && $get['paid_status']) {
			$filter['paid_status'] = $get['paid_status'];
		}

		if (isset($get['withdraw_type']) && !empty($get['withdraw_type'])) {
			$filter['withdraw_type'] = $get['withdraw_type'];
		}


		if (isset($get['date'])) {
			$filter['date'] = $get['date'];
		}
 
		$site_setting = $this->Product_model->getSettings('site');
		$data['site_setting'] = $site_setting;
		$data['userdetails']=$userdetails;

		$this->load->model('Total_model');

		$data['user_totals'] = $this->Total_model->getUserTotals((int)$userdetails['id']);
		
		$post = $this->input->post(null,true);
		$get = $this->input->get(null,true);
		

		if (isset($post['request_payment_all'])) {
			$json = array();
			$ids = (array)$post['ids'];

			$transactions = $this->db->query("SELECT * FROM wallet WHERE id IN (". implode(",", $ids) .")")->result();

				$request = [
					'tran_ids' => implode(",", $ids),
					'status' => 0,
					'user_id' => (int)$userdetails['id'],
					'total' => 0,
					'created_at' => date("Y-m-d H:i:s"),
				];

				// Store payment method and details
				$settings = array();
				if(isset($post['code']) && !empty($post['code'])) {
					// Store payment method in both prefer_method column and settings
					$request['prefer_method'] = $post['code'];
					$settings['prefer_method'] = $post['code'];
					
					// Add payment details for custom gateways
					if(!in_array($post['code'], ['bank_transfer', 'paypal'])) {
						$payment_details = array();
						foreach ($post as $key => $value) {
							if (strpos($key, $post['code'] . '_') === 0) {
								$field_name = substr($key, strlen($post['code'] . '_'));
								$payment_details[$field_name] = $value;
							}
						}
						if(!empty($payment_details)) {
							$settings['payment_details'] = $payment_details;
						}
					}
				}
				
				// Add settings to request
				if(!empty($settings)) {
					$request['settings'] = json_encode($settings);
				}

				foreach ($transactions as $key => $value) {
					$request['total'] += (float)$value->amount;
				}

				if($request['total'] > 0){
					$this->db->query("UPDATE wallet SET status=2 WHERE id IN (". implode(",", $ids) .") ");
					$this->db->insert("wallet_requests", $request);
					$this->load->model('Mail_model');
					$this->Mail_model->send_wallet_withdrawal_req($request['total'], $userdetails);
					$json['success'] = 1;
				} else{
					$json['error'] = __('user.withdrwal_total_must_be_greater_than_zero');
				}

					echo json_encode($json);die;
				}

				$data['wallet_unpaid_amount'] = (float)$this->db->query("SELECT SUM(amount) as total FROM wallet WHERE status=1 AND amount > 0 AND commission_status=0 AND user_id=". (int)$userdetails['id'])->row()->total;

				$filter['sortBy'] = isset($get['sortby']) ? $get['sortby'] : '';
				$filter['orderBy'] = isset($get['order']) ? $get['order'] : '';

				$data['request_status'] = $this->Wallet_model->request_status;
				$data['status'] = $this->Wallet_model->status();
				$data['status_icon'] = $this->Wallet_model->status_icon;


				$total_rows = $this->Wallet_model->getTransaction($filter, true, 'ONLY_PARENTS');
				$pagination_settings = get_pagination_settings();
				$per_page = $pagination_settings['per_page'];
				$current_page = ($this->uri->segment(3)) ? (int)$this->uri->segment(3) : 1;
				$current_page = max(1, $current_page);
				
				$filter['page_num'] = $current_page;
				$filter['offset'] = ($current_page - 1) * $per_page;
				$filter['per_page'] = $per_page;

				$data['transaction'] = $this->Wallet_model->getTransaction($filter, false, 'ONLY_PARENTS');

				if($userdetails['is_vendor'] == 1)
				unset($filter['user_id']);
				unset($filter['per_page']);
				unset($filter['offset']);
				unset($filter['page_num']);
				
				$transactionSorted = [];

				$child_transaction=array();

					
				$filter['not_negative_balence']=true;
				
				for ($i=0; $i < sizeof($data['transaction']); $i++) {



					$filter['group_id'] = $data['transaction'][$i]['group_id'];

					$filter['not_tran_id'] = $data['transaction'][$i]['id'];
					
					if($userdetails['is_vendor'] != 1) {
						
						$child_transaction = $this->Wallet_model->getTransaction($filter);

						if($userdetails['is_vendor'] != 1)
						$child_transaction[]  = $data['transaction'][$i];

						$child_transaction = array_reverse($child_transaction);
					}else{
					

						if (!empty($data['transaction'][$i]['from_user_id'])) {
							$child_transaction = $this->Wallet_model->getTransaction($filter);

							if($userdetails['is_vendor'] != 1)
							$child_transaction[]  = $data['transaction'][$i];

							$child_transaction = array_reverse($child_transaction);
						}else if($data['transaction'][$i]['is_vendor'] == 1 && $data['transaction'][$i]['comm_from'] =='store'){

							$filter['amount']=1;
							// For vendor transactions from store, always include them (fixes mixed order issue)
							$child_transaction = $this->Wallet_model->getTransaction($filter);
						
							if($userdetails['is_vendor'] == 1 && $data['transaction'][$i]['amount'] > 0)
							$child_transaction[]  = $data['transaction'][$i];

							$child_transaction = array_reverse($child_transaction);
							


						}else if($data['transaction'][$i]['is_vendor'] == 0 && $data['transaction'][$i]['comm_from'] =='store'){

							$filter['amount']=1;
							// Always include store transactions (fixes mixed order issue)
							$vendor=$data['transaction'][$i];
						
							array_push($child_transaction,$vendor);

							$child_transaction = array_reverse($child_transaction);
						}
						else{

							

							$vendor=$data['transaction'][$i];
							
							array_push($child_transaction,$vendor);

							$child_transaction = array_reverse($child_transaction);
							
						}
						
					}
					
					
				$child_transaction_sorted = $child_transaction;
				$parent_transaction = null;

				foreach($child_transaction as $key => $ch) {
					$moveFirst = false;

					if(strpos($ch['type'], 'refer') === false) {
						if(in_array($ch['type'], ['vendor_sale_commission', 'sale_commission', 'external_sale_commission', 'click_comission']) && $ch['parent_id'] == 0) {
							$moveFirst = true;
						} else if(strpos($ch['type'], 'click') !== false && $ch['parent_id'] == 0) {
							$moveFirst = true;
						}
					}

					if($moveFirst) {
						$parent_transaction = $ch;
						unset($child_transaction_sorted[$key]);
					}
				}
				
				usort($child_transaction_sorted, function($a, $b) {
					if (strpos($a['comment'], 'Level') !== false && strpos($b['comment'], 'Level') !== false) {
						preg_match('/Level (\d+)/', $a['comment'], $matchA);
						preg_match('/Level (\d+)/', $b['comment'], $matchB);
						$levelA = isset($matchA[1]) ? (int)$matchA[1] : 999;
						$levelB = isset($matchB[1]) ? (int)$matchB[1] : 999;
						return $levelA - $levelB;
					}
					return 0;
				});
				
				if($parent_transaction) {
					array_unshift($child_transaction_sorted, $parent_transaction);
				}
					
					if($child_transaction_sorted[0]['status'] > 1) {
						$re_child_transaction_sorted = $child_transaction_sorted;
						foreach($child_transaction_sorted as $key => $ch) {
							if($ch['status'] == 0 || $ch['status'] == 1) {
								$moveFirst = true;
							}
							
							if($moveFirst) {
								unset($re_child_transaction_sorted[$key]);
								array_unshift($re_child_transaction_sorted , $ch);
							}
						}
						$child_transaction_sorted = $re_child_transaction_sorted;
					}

					
					if($userdetails['is_vendor'] != 1) {
						$transactionSorted = array_merge($transactionSorted, $child_transaction_sorted);
					}else{
						if (!empty($data['transaction'][$i]['from_user_id'])) {
							$transactionSorted = array_merge($transactionSorted, $child_transaction_sorted);
						}else if($data['transaction'][$i]['is_vendor'] == 1 && $data['transaction'][$i]['comm_from'] =='store'){
							$transactionSorted = array_merge($transactionSorted, $child_transaction_sorted);
						
						}else if($data['transaction'][$i]['is_vendor'] == 0 && $data['transaction'][$i]['comm_from'] =='store'){
							$transactionSorted = array_merge($transactionSorted, $child_transaction_sorted);
						}else{
							$transactionSorted = array_merge($transactionSorted, $child_transaction_sorted);
						}
					}
					
				}

				
				$transactionSorted=array_values(array_column($transactionSorted, null, 'id'));

				
				
				if(isset($site_setting) && is_array($site_setting))
				{ 
					if($site_setting["wallet_auto_withdrawal"]==1)
					{
						$wallet_auto_withdrawal_days=$site_setting["wallet_auto_withdrawal_days"];
						$wallet_auto_withdrawal_limit=$site_setting["wallet_auto_withdrawal_limit"];
						$AutoWithdrawaltotal=$this->Wallet_model->getHoldTransactionsByUserId($wallet_auto_withdrawal_days,$wallet_auto_withdrawal_limit,(int)$userdetails['id']);

						$data['walletauto_withdrawal']=1;
						$data['wallet_auto_withdrawal_days']=$wallet_auto_withdrawal_days;
						$data['wallet_auto_withdrawal_total']=$AutoWithdrawaltotal;
						
						$message=__('user.your_eligible_approved_commission_amount_is')." [".c_format($AutoWithdrawaltotal)."]"." | ".__('user.and_it_will_be_available_for_payment_in')." ".$wallet_auto_withdrawal_days." ".__('user.days')." | " ." ". "Eligible minimum withdrawal commission:"." ".c_format($site_setting["wallet_min_amount"]);
						$data['wallet_auto_withdrawal_message']=$message;
					}
				}
				
			$data['transaction'] = $transactionSorted;

			$pagination_data = easy_pagination(base_url('usercontrol/mywallet/'), $total_rows, ($current_page - 1) * $per_page);
			$data['pagination_link'] = $pagination_data['html'];
			$data['pagination_summary'] = pagination_summary_html($current_page, $per_page, $total_rows);

				$data['Wallet_model'] = $this->Wallet_model;

				$data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);
				
				$data['site_setting'] = $this->Product_model->getSettings('site');

				$data['userdetails'] = $this->userdetails();

				if($data['userdetails']['is_vendor'])
					$data['market_vendor'] = $this->Product_model->getSettings('market_vendor');

				$data['notcheckmember'] = 1;
 				

				$this->view($data,'users/newmywallet','usercontrol');
			}

			public function info_remove_tran_by_commission(){
				$userdetails = $this->userdetails();
				if(empty($userdetails) || empty($userdetails['is_vendor'])) redirect('usercontrol/dashboard');

				$id = (int) $this->input->post("id",true);
				$wallet = $this->Wallet_model->getbyId($id);

				$market_vendor = $this->Product_model->getSettings('market_vendor');
				if(($wallet->comm_from == 'ex' && empty($wallet->is_action) 
					&& $wallet->reference_id_2 != '__general_click__'  && $market_vendor['marketvendorexternalordercampaign'])
					||  ($wallet->is_action && $market_vendor['marketvendoractionscampaign'])
					||  ($wallet->reference_id_2 == '__general_click__' && $market_vendor['marketvendorclickcampaign'])){

					$dataCollection = $this->Wallet_model->getDeleteData($id);

				$status_type = $this->input->post("status_type",true);

				$delete_id = $this->input->post("id",true);

				$this->db->query("DELETE FROM wallet_requests WHERE FIND_IN_SET($delete_id,tran_ids)");


				$html = '<h6 class="text-center">'.__('user.important_this_action_can_not_be_undo').'</h6><hr>';

				$html .= '<p> '.__('user.once_you_change_status_trash_or_cancel').' </p>';
				$html .= '<hr>';

				$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('user.transaction_id')."</td><td class='text-center'>".__('user.username')."</td><td class='text-center'> ".__('user.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";
				
				$amountTotal = 0;
				
				foreach ($dataCollection as $data) {

					$amountTotal += ($data['amount'] > 0) ? $data['amount'] : 0;

					$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $data['id'] .'</td><td class="text-center">'. $data['name'] .'</td><td class="text-center">'. c_format($data['amount']) .'</td></tr>';
				}

				$table .= "</tbody></table></div>";

				$html .= "<p><strong>".count($dataCollection)."</strong> ".__('user.transactions_having_total_amount')." <strong>".c_format($amountTotal)."</strong> ".__('user.will_get_affected')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('user.see_details')."</a></p>";

				$html .= $table;

				$html .= "<br><div class='row'> <div class='col-sm-6'><button data-bs-dismiss='modal' class='btn btn-primary btn-block'>".__('user.cancel')."</button></div> <div class='col-sm-6'><button class='btn btn-danger  btn-block' id='". $id ."' status_type='". $status_type ."' change-tran-by-commi-confirm>".__('user.yes_confirm')."</button></div> </div>";


				$json['html'] = $html;

				echo json_encode($json);
			}
		}

		public function change_commission_status(){
			$userdetails = $this->userdetails();
			if(empty($userdetails) || empty($userdetails['is_vendor'])) redirect('usercontrol/dashboard');

			$id = (int) $this->input->post("id",true);
			$wallet = $this->Wallet_model->getbyId($id);

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if(($wallet->comm_from == 'ex' && empty($wallet->is_action) 
				&& $wallet->reference_id_2 != '__general_click__'  && $market_vendor['marketvendorexternalordercampaign'])
				||  ($wallet->is_action && $market_vendor['marketvendoractionscampaign'])
				||  ($wallet->reference_id_2 == '__general_click__' && $market_vendor['marketvendorclickcampaign'])){
				
				$status_type = $this->input->post('status_type');

			$delete_id = $this->input->post("id",true);
			
			$dataCollection = $this->Wallet_model->getDeleteData($id);
			
			foreach ($dataCollection as $tran) {
				if(!empty($tran['id'])) {
					$where = array('id'=>$tran['id']);
					$data = array('commission_status'=>$status_type);
					$update = $this->Common_model->update('wallet', $where, $data);
					if($update)
					{
						$where_request = array('tran_ids'=>$tran['id']);
						
						$data = array('status'=>0);
						
						$update = $this->Common_model->update('wallet', $where, $data);
						
						$where_request = array('tran_ids'=>$tran['id']);
						
						$update_request = $this->Common_model->update('wallet_requests', $where_request, $data);

						$json['message'] = "status change successfully";
						$json['status'] = 1;
					}
					else
					{
						$json['message'] = "status change failed";
						$json['status'] = 0;
					}
				}

			}
			
			echo json_encode($json);
		}
	}

	public function wallet_change_status(){
		$userdetails = $this->userdetails();
		if(empty($userdetails) || empty($userdetails['is_vendor'])) redirect('usercontrol/dashboard');

		$id = (int) $this->input->post("id",true);
		$wallet = $this->Wallet_model->getbyId($id);

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if(($wallet->comm_from == 'ex' && empty($wallet->is_action) 
			&& $wallet->reference_id_2 != '__general_click__'  && $market_vendor['marketvendorexternalordercampaign'])
			||  ($wallet->is_action && $market_vendor['marketvendoractionscampaign'])
			||  ($wallet->reference_id_2 == '__general_click__' && $market_vendor['marketvendorclickcampaign'])){

			$val = (int)$this->input->post("val",true);

		$confirm = $this->input->post("confirm",true);
		
		$tran = $this->db->query("
			
			SELECT w.*,u.firstname,u.lastname,u.email,wallet_recursion.id as wallet_recursion_id,
			
			(SELECT SUM(amount) FROM `wallet` ww WHERE ww.parent_id=w.id) as total_recurring_amount
			
			FROM wallet w 
			
			LEFT JOIN users u ON u.id=w.user_id  
			
			LEFT JOIN  wallet_recursion ON wallet_recursion.transaction_id = w.id
			
			WHERE w.id= {$id}
			
			")->row();

		$json = [];
		
		if($tran->comm_from != "ex" && ($tran->type == 'sale_commission' || $tran->type == 'vendor_sale_commission' && $tran->comm_from == 'store' && $val != 0)){
			$order_status = $this->db->query("select order_status_id from orders_history where order_id=". $tran->reference_id." order by id DESC")->row_array();
		}
		
		if(isset($order_status) && $order_status['order_status_id'] != 1) {
			$data['invalid_order_status'] = true;
			$data['id'] = $id;

			$json['ask_confirm'] = $tran;

			$json['html'] = $this->load->view("admincontrol/users/part/confirmstatus",$data,true);
		} else if(!$confirm) {
			$dataCollection = $this->Wallet_model->getDeleteData((int)$id);
			
			$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('user.transaction_id')."</td><td class='text-center'>".__('user.username')."</td><td class='text-center'>".__('user.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";
			
			$amountTotal = 0;
			
			foreach ($dataCollection as $datas) {
				
				$amountTotal += ($datas['amount'] > 0) ? $datas['amount'] : 0; 
				
				$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $datas['id'] .'</td><td class="text-center">'. $datas['name'] .'</td><td class="text-center">'. c_format($datas['amount']) .'</td></tr>';
			}
			
			$table .= "</tbody></table></div>";
			
			$html .= "<p><strong>".__('user.status_for')." ".count($dataCollection)."</strong> ".__('user.transactions_having_total_amount')." <strong>".c_format($amountTotal)."</strong> ".__('user.wil_be_updated')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('user.see_details')."</a></p>";
			
			$html .= $table;
			
			$data['transactions_details'] = $html;
			$json['ask_confirm'] = $tran;
			$data['status'] = $val;
			$data['tran'] = $tran;
			$data['id'] = $id;
			$json['html'] = $this->load->view("admincontrol/users/part/confirmstatus",$data,true);
		} else {

			if($tran->type == 'sale_commission' && $tran->comm_from == 'ex'){
				$this->db->query("UPDATE integration_orders SET status = {$val} WHERE id=". $tran->reference_id_2 );
			}
			
			
			if($val == 1){
				$tran->comment = str_replace('Clicked done from ip_message', '', $tran->comment);
				
				$notificationData = array(
					
					'notification_url'          => 'mywallet',
					
					'notification_type'         => 'wallet',
					
					'notification_title'        => c_format($tran->amount) ." Credited in your wallet",
					
					'notification_view_user_id' => $tran->user_id,
					
					'notification_viewfor'      => 'user',
					
					'notification_actionID'     => $tran->id,
					
					'notification_description'  => $tran->comment,
					
					'notification_is_read'      => '0',
					
					'notification_created_date' => date('Y-m-d H:i:s'),
					
					'notification_ipaddress'    => $_SERVER['REMOTE_ADDR']
				);
				
				$this->load->model('Mail_model');
				
				$this->Mail_model->wallet_noti_in_wallet($tran);
				
				$this->insertnotification($notificationData);
				
			} else {
				
				$notificationData = array(
					
					'notification_url'          => 'mywallet',
					
					'notification_type'         => 'wallet',
					
					'notification_title'        => "Transactions status changed",
					
					'notification_view_user_id' => $tran->user_id,
					
					'notification_viewfor'      => 'user',
					
					'notification_actionID'     => $tran->id,
					
					'notification_description'  => "Transactions #{$id} status changed to ". ($val == 1 ? 'In Wallet' : 'On Hold') .". Amount is " . c_format($tran->amount),
					
					'notification_is_read'      => '0',
					
					'notification_created_date' => date('Y-m-d H:i:s'),
					
					'notification_ipaddress'    => $_SERVER['REMOTE_ADDR']
				);
				
				$this->insertnotification($notificationData);
				
				$this->load->model('Mail_model');
				
				$this->Mail_model->wallet_noti_on_hold_wallet($tran);
				
			}
			
			$this->db->query("UPDATE wallet SET status = {$val},commission_status = 0 WHERE group_id =". $tran->group_id);
			
			$json['success'] = true;
		}

		echo json_encode($json);
	}
}

public function info_remove_tran(){
	$userdetails = $this->userdetails();
	if(empty($userdetails) || empty($userdetails['is_vendor'])) redirect('usercontrol/dashboard');

	$id = (int) $this->input->post("id",true);
	$wallet = $this->Wallet_model->getbyId($id);

	$market_vendor = $this->Product_model->getSettings('market_vendor');
	if(($wallet->comm_from == 'ex' && empty($wallet->is_action) 
		&& $wallet->reference_id_2 != '__general_click__'  && $market_vendor['marketvendorexternalordercampaign'])
		||  ($wallet->is_action && $market_vendor['marketvendoractionscampaign'])
		||  ($wallet->reference_id_2 == '__general_click__' && $market_vendor['marketvendorclickcampaign'])){

		$delete_id = (int) $this->input->post("id",true);
	
	$dataCollection = $this->Wallet_model->getDeleteData($delete_id);



	$html = "";

	$html = '<h6 class="text-center">'.__('user.important_this_action_can_not_be_undo').'</h6>';

	$html .= '<hr>';

	$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('user.transaction_id')."</td><td class='text-center'>".__('user.username')."</td><td class='text-center'> ".__('user.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";
	
	$amountTotal = 0;
	
	foreach ($dataCollection as $data) {

		$amountTotal += ($data['amount'] > 0) ? $data['amount'] : 0; 

		$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $data['id'] .'</td><td class="text-center">'. $data['name'] .'</td><td class="text-center">'. c_format($data['amount']) .'</td></tr>';
	}

	$table .= "</tbody></table></div>";

	$html .= "<p><strong>".count($dataCollection)."</strong> ".__('user.transactions_having_total_amount')." <strong>".c_format($amountTotal)."</strong> ".__('user.will_get_deleted')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('user.see_details')."</a></p>";

	$html .= $table;

	$html .= "<br><div class='row'> <div class='col-sm-6'><button data-bs-dismiss='modal' class='btn btn-primary btn-block'>".__('user.cancel')."</button></div> <div class='col-sm-6'><button class='btn btn-danger  btn-block' delete-tran-confirm='". $delete_id ."'>".__('user.yes_confirm')."</button></div> </div>";


	$json['html'] = $html;

	echo json_encode($json);
}
}

public function confirm_remove_tran(){
	$userdetails = $this->userdetails();
	if(empty($userdetails) || empty($userdetails['is_vendor'])) redirect('usercontrol/dashboard');

	$id = (int) $this->input->post("id",true);
	$wallet = $this->Wallet_model->getbyId($id);

	$market_vendor = $this->Product_model->getSettings('market_vendor');
	if(($wallet->comm_from == 'ex' && empty($wallet->is_action) 
		&& $wallet->reference_id_2 != '__general_click__'  && $market_vendor['marketvendorexternalordercampaign'])
		||  ($wallet->is_action && $market_vendor['marketvendoractionscampaign'])
		||  ($wallet->reference_id_2 == '__general_click__' && $market_vendor['marketvendorclickcampaign'])){
		
		$json['dataCollection'] = $dataCollection = $this->Wallet_model->getDeleteData($id);

	$this->load->model('Payout_batch_model');

	foreach ($dataCollection as $data) {

		foreach ($data['removed'] as $key => $value) {
			if(isset($value['query']) && $value['query']) $this->db->query($value['query']);
		}

		if(isset($data['details']) && ! empty($data['details'])) {
			$this->load->model('Product_model');
			$this->Product_model->delete_wallet_integration_clicks_action($data['details']);
		}

		if(isset($data['id']) && !empty($data['id'])) {

			$this->Payout_batch_model->detach_wallet_requests_for_wallet_transaction_id((int) $data['id']);

			$this->db->query("DELETE FROM wallet_recursion WHERE transaction_id = ". $data['id']);

			$this->db->query("DELETE FROM wallet_requests WHERE FIND_IN_SET(".$data['id'].",tran_ids)");

				$this->db->query("DELETE FROM wallet WHERE parent_id = ". $data['id']);

				$this->db->query("DELETE FROM wallet WHERE id = ". $data['id']);
			}
		}

		echo json_encode($json);
	}
}

public function info_recursion_tran(){
	$userdetails = $this->userdetails();
	if(empty($userdetails) || empty($userdetails['is_vendor'])) redirect('usercontrol/dashboard');

	$id = (int) $this->input->post("id",true);
	$wallet = $this->Wallet_model->getbyId($id);

	$market_vendor = $this->Product_model->getSettings('market_vendor');
	if(($wallet->comm_from == 'ex' && empty($wallet->is_action) 
		&& $wallet->reference_id_2 != '__general_click__'  && $market_vendor['marketvendorexternalordercampaign'])
		||  ($wallet->is_action && $market_vendor['marketvendoractionscampaign'])
		||  ($wallet->reference_id_2 == '__general_click__' && $market_vendor['marketvendorclickcampaign'])){

		$mainID = $this->input->post("id",true);
	
	$dataCollection = $this->Wallet_model->getDeleteData((int)$mainID, true);

	$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('user.transaction_id')."</td><td class='text-center'>".__('user.username')."</td><td class='text-center'> ".__('user.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";
	
	$amountTotal = 0;
	
	foreach ($dataCollection as $data) {

		$amountTotal += ($data['amount'] > 0) ? $data['amount'] : 0;

		$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $data['id'] .'</td><td class="text-center">'. $data['name'] .'</td><td class="text-center">'. c_format($data['amount']) .'</td></tr>';
	}

	$table .= "</tbody></table></div>";

	$html .= "<p><strong>".__('user.recursion_setting_for')." ".count($dataCollection)."</strong> ".__('user.transactions_having_total_amount')." <strong>".c_format($amountTotal)."</strong> ".__('user.wil_be_updated')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('user.see_details')."</a></p>";

	$html .= $table;
	
	$data['transactions_details'] = $html;
	
	$wallet_data = $this->Wallet_model->getbyId((int)$mainID);

	$recursion = $this->Wallet_model->GetTransactionRecursion($wallet_data->id);		


	$recursion_type	= array(

		"every_day"   => __("user.every_day"),

		"every_week"  => __("user.every_week"),

		"every_month" => __("user.every_month"),

		"every_year"  => __("user.every_year"),

		"custom_time" => __("user.custom_time")

	);



	$minutes = $recursion['custom_time'];

	$day = floor ($minutes / 1440);

	$hour = floor (($minutes - $day * 1440) / 60);

	$minute = $minutes - ($day * 1440) - ($hour * 60);



	$data['day'] = $day;

	$data['hour'] = $hour;

	$data['minute'] = $minute;

	$data['recursion_type'] = $recursion_type;

	$data['wallet_data'] = $wallet_data;

	$recursion['endtime'] = ($recursion['endtime'] == "0000-00-00 00:00:00") ? null : $recursion['endtime'];

	$data['recursion'] = $recursion;

	$json['html'] = $this->load->view("admincontrol/users/part/recurring", $data,true);

	$json['recursion_type'] = $recursion['type'];

	echo json_encode($json);
}
}

public function getRecurringTransaction(){
	$userdetails = $this->userdetails();
	if(empty($userdetails)){ redirect('/login'); }

	$id = (int)$this->input->post('id');
	$filter = array(
		//'user_id' => $userdetails['id'],
		'parent_id' => $id,
	);

	$data['recurring'] = $id;
	$data['request_status'] = $this->Wallet_model->request_status;
	$data['status'] = $this->Wallet_model->status();
	$data['status_icon'] = $this->Wallet_model->status_icon;
	$data['userdetails'] = $userdetails;
	$data['transaction'] = $this->Wallet_model->getTransaction($filter);

	
	$json['table'] = '';
	foreach ($data['transaction'] as $key => $value) {
		$data['class'] = 'child-recurring';
		$data['force_class'] = $_POST['ischild'] == 'true' ? 'child-arrow' : '';
		$data['recurring'] = $id;
		$data['value'] = $value;
		$data['wallet_status'] = $data['status'];
		$json['table'] .= $this->load->view("usercontrol/users/parts/new_wallet_tr", $data, true);
	}


	echo json_encode($json);
}
public function form(){
	$userdetails = $this->userdetails();
	if(empty($userdetails)){ redirect('/login'); }

	$store_setting = $this->Product_model->getSettings('store');
	if(!$store_setting['status']){ show_404(); }
	
	$this->load->model("Form_model");
	$data['forms'] = $this->Form_model->getForms($userdetails['id']);	

	foreach ($data['forms'] as $key => $value) { 			 
		$data['forms'][$key]['coupon_name'] = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
		$data['forms'][$key]['public_page'] = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
		$data['forms'][$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
		$data['forms'][$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
		$data['forms'][$key]['seo'] = str_replace('_', ' ', $value['seo']) ;
	}
	$this->load->view('usercontrol/includes/header', $data);
	$this->load->view('usercontrol/form/index', $data);
	$this->load->view('usercontrol/includes/footer', $data);
}
public function generateformcode($form = 0){
	$userdetails = $this->userdetails();
	if(empty($userdetails)){ redirect('/login'); }

	else {
		if($form){
			$this->load->model("Form_model");
			$data['getForm'] = $this->Form_model->getForm($form);
			if (empty($data['getForm']) || !$this->Product_model->user_can_promote_market_campaign($userdetails, $data['getForm'])) {
				show_404();
			}
			$data['form_id'] = $form;
			$data['user_id'] = $userdetails['id'];

			$this->load->view('usercontrol/form/generatecode', $data);
		}
	}
}
public function category_auto(){
	$userdetails = $this->userdetails();
	if(!$this->userdetails()){ redirect('/', 'refresh'); }
	$keyword = $this->input->get('term');
	if ($keyword === null) $keyword = $this->input->get('query');

	$data = $this->db->query(
		"SELECT id as value, name as label FROM categories WHERE name LIKE " .
		$this->db->escape('%' . $keyword . '%') .
		" ORDER BY name"
	)->result_array();

	echo json_encode($data); die;
}

public function store_products(){
	$userdetails = $this->userdetails();

	if(empty($userdetails)) redirect('usercontrol/dashboard');

	$vendor_setting = $this->Product_model->getSettings('vendor');
	$store_setting = $this->Product_model->getSettings('store');
	if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1) redirect('usercontrol/dashboard');

	
	if($store_setting['store_mode'] == 'sales') redirect('usercontrol/store_dashboard');

	$filter = array(
		'seller_id' => $userdetails['id'],
	);
	$get = $this->input->get(null,true);

	if(isset($get['category_id']) && $get['category_id'])
		$filter['category_id'] = (int)$this->input->get('category_id');

	$data['default_commition'] =$this->Product_model->getSettings('productsetting');
	$data['productlist'] = $this->Product_model->getAllProductForVendor($userdetails['id'], $userdetails['type'],$filter);

	$this->load->library("socialshare");				
	$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

	$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$userdetails['id'])->first();
	$vendor_product_count = $this->Product_model->countByField('product_affiliate','user_id',$userdetails['id']);
	if(isset($userPlan->plan->product) && $userPlan->plan->product <= $vendor_product_count){
		$this->load->helper('cookie');
		$cookie = get_cookie('product_count_alert_'.$userdetails['id']);
		$data['product_count_alert'] = __('user.reached_maximum_limit_package_upgrade').' <a href="'.base_url('usercontrol/purchase_plan').'">'.__('user.here').'</a>';
	}
	$data['currentTheme'] = User::getActiveTheme();
	$data['StoreStatus'] = User::getStoreStatus();

	$vendormanagereview= $this->db->query("SELECT * FROM setting WHERE  setting_key='vendormanagereview' and setting_type='market_vendor'")->row();
	$data['vendormanagereview'] = isset($vendormanagereview) && $vendormanagereview->setting_value==1 ? 1 : 0;
	$vendormanagereviewimage=$this->db->query("SELECT * FROM setting WHERE  setting_key='vendormanagereviewimage' and setting_type='market_vendor'")->row();
	$data['vendormanagereviewimage'] = isset($vendormanagereviewimage) && $vendormanagereviewimage->setting_value==1 ? 1 : 0;
	$this->view($data,'store/store_products','usercontrol');
}

public function sales_products(){

	$userdetails = $this->userdetails();

	if(empty($userdetails)) redirect('usercontrol/dashboard');

	$vendor_setting = $this->Product_model->getSettings('vendor');
	$store_setting = $this->Product_model->getSettings('store');

	if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1) redirect('usercontrol/dashboard');

	if($store_setting['store_mode'] == 'cart') redirect('usercontrol/store_dashboard');

	$filter = array(
		'seller_id' => $userdetails['id'],
	);
	$get = $this->input->get(null,true);

	if(isset($get['category_id']) && $get['category_id'])
		$filter['category_id'] = (int)$this->input->get('category_id');

	$data['default_commition'] =$this->Product_model->getSettings('productsetting');
	$data['productlist'] = $this->Product_model->getAllSaleProductForVendor($userdetails['id'], 'admin',$filter);

	$this->load->library("socialshare");				
	$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

	$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$userdetails['id'])->first();
	$vendor_product_count = $this->Product_model->countByField('product_affiliate','user_id',$userdetails['id']);
	if(isset($userPlan->plan->product) && $userPlan->plan->product <= $vendor_product_count){
		$this->load->helper('cookie');
		$cookie = get_cookie('product_count_alert_'.$userdetails['id']);
		$data['product_count_alert'] = __('user.reached_maximum_limit_package_upgrade').' <a href="'.base_url('usercontrol/purchase_plan').'">'.__('user.here').'</a>';
	}

	$data['currentTheme'] = User::getActiveTheme();
	$data['StoreStatus'] = User::getStoreStatus();

	$this->view($data,'store/sale_products','usercontrol');
}

public function update_sale_products($id = null){

		$userdetails = $this->userdetails();

		if(empty($userdetails)) redirect('usercontrol/dashboard');

		$this->load->model('Product_model');

		$product = $this->Product_model->getProductById($id);

		$product = json_decode(json_encode($product), true);

		$data['product'] = $this->Product_model->productDataWithMeta($product);

		$data['CurrencySymbol'] = $this->currency->getSymbol();

		if($data['product']){

			$data['seller'] = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product['product_id'] ." ")->row();

			$data['seller_setting'] = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$data['seller']->user_id ." ")->row();

			$data['product_state'] = $this->db->query("SELECT * FROM states WHERE id=". (int)$data['product']['state_id'] )->row();

			$data['states'] = $this->db->query("SELECT * FROM states WHERE country_id=". (int)$data['product_state']->country_id )->result();

			$data['categories'] =$this->Product_model->getProductCategory($product['product_id']);

		}

		$data['checkout_template'] = get_available_checkout_template();

		$data['setting'] = $this->Product_model->getSettings('productsetting');

		$data['vendor_setting'] = $this->Product_model->getSettings('vendor');

		$data['country_list'] = $this->db->query("SELECT name,id FROM countries")->result();

		$data['s3_setting'] = $this->Product_model->getSettings('s3_storage');

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		$data['needs_product_approval'] = (int)($market_vendor['marketaddnewstoreproduct'] ?? 0);

		$this->view($data,'store/create_sale_products','usercontrol');
}

public function bulkProductImportFromUrl() 
{
	$userdetails = $this->userdetails();
	if(empty($userdetails)) redirect('usercontrol/dashboard');

		$f_result = [
		'products_available' => 0,
		'products_managed' => 0,
		'status' => 'danger',
		'message' => 'something went wrong, please try again!',
		'data'  => [],
		'dataPreview' => ""
	];
	
	$bulkResult = [];
	$json=array();
	$post = $this->input->post(null,true); 
	if(!isset($post['txt_xmlurl'])){

		$json['warning'] = __('user.please_enter_xml_url'); 

	} 
	else {

		$xmlurl = $post['txt_xmlurl'];
		$featchurldata=file_get_contents($xmlurl);
		$xml=simplexml_load_string($featchurldata);

		if($xml)
		{
		 	$products=$xml;
 			if(isset($products))
 			{
 				foreach($products as $product) 
				{
					$productArray = [];
					foreach($product as $key => $value) 
					{
						$xmlobjvalue= (string)$value[0];
						if(isset($xmlobjvalue)) 
						{
							$productArray[$key] = $xmlobjvalue != null ? $xmlobjvalue : '';
						} 
						else {
							$productArray[$key] = '';
						} 
					} 

					if(!empty($productArray)) {
						$cdata = $this->initialProductImportCheck($productArray);
						$cdata['row'] = $productArray;
						$bulkResult[] = $cdata;
					} 
				}
 			}
 			else
 				$json['warning'] = __('user.not_valid_xm_format'); 
					
		}
		else 
		{  
			$json['warning'] = __('user.url_entered_not_valid_xml_content');
		}

	}


	$data['action'] = 'confirm';
	$data['products'] = $bulkResult;
	echo $this->load->view('admincontrol/product/bulk_upload_modal', $data, true);
}

public function bulkProductImport() {

	require_once APPPATH . '/core/phpspreadsheet/autoload.php';
	$extension="";
	if(!isset($_FILES['file']['error']) || $_FILES['file']['error'] != 0)
	{
		$json['warning'] = "Please Select Excel or Xml File..!";

	} else {

		$extension = pathinfo($_FILES['file']["name"], PATHINFO_EXTENSION);

		if($extension == 'xlsx' || $extension == 'xml')
		{}
		else
		{
			$json['warning'] = "Only xlsx or Xml files are allowed.!";
		}
	}
 
	$f_result = [
		'products_available' => 0,
		'products_managed' => 0,
		'status' => 'danger',
		'message' => 'something went wrong, please try again!',
		'data'  => [],
		'dataPreview' => ""
	];
	
	$bulkResult = [];

	if(!isset($json['warning'])){

		$inputFileName = $_FILES['file']['tmp_name'];

		if($extension == 'xlsx')
		{

			$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
			
			$objPHPExcel = $objReader->load($inputFileName);

			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();

			$xlsdata = [];            
			for ($row = 1; $row <= $highestRow; $row++){ 
				$xlsdata[] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
			}

			$indexData = $this->getProductXlsIndex($xlsdata[0]);
	
			for($proIndex = 1; $proIndex < sizeof($xlsdata); $proIndex++) 
			{
				
				$productArray = [];
				
				foreach($indexData as $key => $value) {
					if(isset($xlsdata[$proIndex][$value])) {
						$productArray[$key] = $xlsdata[$proIndex][$value] != null ? $xlsdata[$proIndex][$value] : '';
					} else {
						$productArray[$key] = '';
					}
				}

				if(!empty($productArray)) {
					$cdata = $this->initialProductImportCheck($productArray);
					$cdata['row'] = $productArray;
					$bulkResult[] = $cdata;
				}
			}
		}
		else if($extension == 'xml')
		{
			$xml = simplexml_load_file($inputFileName);
			if ($xml === false) 
			{
				$xmlerrrostring="";
				  $json['warning'] = "Failed loading XML!";
			  foreach(libxml_get_errors() as $error) 
			  {
			    $xmlerrrostring.= "<br>". $error->message;
			  }

			  $json['warning'] =$xmlerrrostring;
			} 
			else 
			{
				$products=$xml;
				foreach($products as $product) 
				{
					$productArray = [];
					foreach($product as $key => $value) 
					{
				  		$xmlobjvalue= (string)$value[0];
				  		if(isset($xmlobjvalue)) 
				  		{
							$productArray[$key] = $xmlobjvalue != null ? $xmlobjvalue : '';
						} else {
							$productArray[$key] = '';
						} 
				  	} 

				  	if(!empty($productArray)) 
				  	{
						$cdata = $this->initialProductImportCheck($productArray);
						if(isset($cdata) && is_array($cdata))
						$productArray['product_status']=$cdata['data']['product_status'];

						$cdata['row'] = $productArray;
						$bulkResult[] = $cdata;
					} 
				}
			}
		}

	} 
 
	
	$data['action'] = 'confirm';
	$data['products'] = $bulkResult;
	echo $this->load->view('admincontrol/product/bulk_upload_modal', $data, true);
}

public function initialProductImportCheck($post){

	try {
		
		$userdetails = $this->userdetails();
		
		if(!empty($post)){

			unset($this->validation);

			$product_id = (int) $post['product_id'];

			$product_status=0;		
			

			if($product_id > 0) 
			{
				$product_exist = $this->db->query('select product_status from product where product_created_by='.$userdetails['id'].' AND product_id='.$product_id)->row_array();
				if(empty($product_exist))
				 {
				 	 
					return [
						"status" => "error",
						"message" => "Product not available having Product ID you provided!"
					];

					$marketaddnewstoreproduct= $this->db->query("SELECT * FROM setting WHERE  setting_key='marketaddnewstoreproduct' and setting_type='market_vendor'")->row();
					if(isset($marketaddnewstoreproduct) && $marketaddnewstoreproduct->setting_value==1)
 							$product_status=0;
 					else 
 							$product_status=1;	
				}
				else
				{
					 
					 $product_status=$product_exist["product_status"];	
				}
			}
			else
			{
					$marketaddnewstoreproduct= $this->db->query("SELECT * FROM setting WHERE  setting_key='marketaddnewstoreproduct' and setting_type='market_vendor'")->row();
					if(isset($marketaddnewstoreproduct) && $marketaddnewstoreproduct->setting_value==1)
 							$product_status=0;
 					else 
 							$product_status=1;	
			}
 

			$this->load->helper(array('form', 'url'));
			
			$this->load->library('form_validation');
			
			$this->form_validation->reset_validation();

			$this->form_validation->set_rules('product_name', __('user.product_name_'), 'required');
			
			$this->form_validation->set_rules('product_description', __('user.product_description'), 'required');
			
			$this->form_validation->set_rules(
				
				'product_short_description', __('user.short_description'),
				
				'required|min_length[5]|max_length[150]',
				
				array(
					
					'required'      => 'Enter %s',
					
					'is_unique'     => 'This %s already exists.',
					
					'min_length'    => '%s: the minimum of characters is %s',
					
					'max_length'    => '%s: the maximum of characters is %s',
					
				)
				
			);
			
			$this->form_validation->set_rules('product_price', 'Product Price', 'required');
			
			$this->form_validation->set_rules('product_sku', 'Product SKU', 'required');
			
			if($post['allow_country'] == "1"){
				
				$this->form_validation->set_rules('state_id', 'State', 'required' );
				
			}
			
			$this->form_validation->set_data($post);
			
			if($this->form_validation->run()){
				
				$errors = array();
				
				if(isset($post['product_id']) && !empty($post['product_id']) && $post['product_id'] != 0){
					$pro_exist = $this->db->query('select product_id from product where product_created_by='.$userdetails['id'].' AND product_id='.$post['product_id'])->row_array();
					if(empty($pro_exist)) {
						$errors['product_id'] = "Product not available having Product ID you provided!";
					}
				}
				
				
				if(empty($post['product_variations']) || $post['product_variations'] == "[]") {
					$post['product_variations'] = json_encode([]);
				} else {
					$validJson = true;
					
					try{
						$variationJson = json_decode($post['product_variations']);
						$validJson = json_last_error() === JSON_ERROR_NONE;
					} catch(Exception $e) {
						$validJson = false;
					}
					
					if(!$validJson || !is_array($variationJson)) {
						$errors['product_variations'] = "Invalid json string provided for Product Variation!";
					}
				}
				
				if($post['allow_country'] == "1"){
					if($product_id > 0) {
						$state_exist = $this->db->query('select id from states where id='.$post['state_id'])->row_array();
						if(empty($state_exist)) {
							$errors['state_id'] = "State not available having State ID you provided!";
						}
					}
				}

			
				
				if(empty($errors)){
					
					$details = array(
						'product_id' => $post['product_id'],
						
						'product_name'                 =>  $post['product_name'],
						
						'product_description'          =>  $post['product_description'],
						
						'product_short_description'    =>  $post['product_short_description'],
						
						'product_msrp'                 =>  $post['product_msrp'],
						
						'product_price'                =>  $post['product_price'],
						
						'product_sku'                  =>  $post['product_sku'],
						
						'product_type'                 =>  $post['product_type'],
						
						'state_id'                     =>  $post['allow_country'] == "1" ? (int)$post['state_id'] : 0,
						
						'product_commision_type'       =>  'default',

						'product_commision_value'      =>  0,
						
						'product_click_commision_type' =>  'default',
						
						'product_click_commision_ppc'  =>  0,
						
						'product_click_commision_per'  =>  0,
						
						'on_store'                     =>  (int)$post['on_store'],
						
						'allow_shipping'               =>  (int)$post['allow_shipping'],
						
						'allow_upload_file'            =>  (int)$post['allow_upload_file'],
						
						'allow_comment'                =>  (int)$post['allow_comment'],
						
						'product_status'               =>  $product_status,
						
						'product_ipaddress'            =>  $_SERVER['REMOTE_ADDR'],
						
						'product_recursion_type'       =>  '',
						
						'recursion_endtime'       =>  null,
						
						'product_recursion'            =>  '',
						
						'recursion_custom_time'        =>  0,
						
						'product_variations'        =>  $post['product_variations'],
						
						'product_tags'        =>  json_encode($post['product_tags']),
						
						'product_created_by' => $userdetails['id'],
						
						'product_weight'               =>  isset($post['product_weight']) ? (float)$post['product_weight'] : 0.00,
						'product_length'               =>  isset($post['product_length']) ? (float)$post['product_length'] : 0.00,
						'product_width'                =>  isset($post['product_width']) ? (float)$post['product_width'] : 0.00,
						'product_height'               =>  isset($post['product_height']) ? (float)$post['product_height'] : 0.00,
						
					);
					
					if(isset($post['product_id']) && !empty($post['product_id']) && $post['product_id'] != 0){
						
						return [
							"status" => "Warning",
							"message" => "<span class='badge bg-warning'>update</span>",
							"data" => $details
						];

					} else {
						return [
							"status" => "Warning",
							"message" => "<span class='badge bg-success'>create</span>",
							"data" => $details
						];
					}
				} else {
					return [
						"status" => "error",
						"errors" => $errors
					];
				}
			} else {
				return [
					"status" => "error",
					"errors" => $this->form_validation->error_array()
				];
			}
		} else {
			return [
				"status" => "error",
				"errors" => ["Something went wrong"]
			];
		}
	} catch (Exception $e) {
		return [
			"status" => "error",
			"errors" => [$e->getMessage()]
		];
	}
}

public function bulkProductImportConfirm() {
	$data = json_decode(base64_decode($_POST['products']), true);
	
	$result = [
		'total_products' => 0,
		'created_products' => 0,
		'updated_products' => 0,
		'failed_products' => 0,
		'skipped_products' => 0,
		'details' => []
	];
	
	foreach($data as $d) {
		if($d['status'] !== 'error') {
			$r = $this->createUpdateImportedProduct($d['data']);
			if(isset($r['created'])) {
				$result['created_products']++;
			} else if(isset($r['updated'])) {
				$result['updated_products']++;
			} else {
				$result['failed_products']++;
			}
			
			$result['details'][] = [
				'product' => $d['data'],
				'result' => $r
			];
		} else {
			$result['skipped_products']++;
		}
		$result['total_products']++;
	}
	
	echo $this->load->view('admincontrol/product/bulk_upload_modal', $result, true);
}

public function createUpdateImportedProduct($post){

	try {
		
		$json['status'] = false;
		
		$userdetails = $this->userdetails();

		$old_product_data =[];
		
		$details = $post;
		
		

		if(isset($post['product_id']) && !empty($post['product_id']) && $post['product_id'] != 0){
			$product_id = $post['product_id'];

			unset($details['product_id']);

			$this->Product_model->update_data('product', $details, array('product_id' => $product_id));
			$details['product_created_date'] = date('Y-m-d H:i:s');
			
			$json['updated'] = true;
			$json['status'] = true;
			$json['success'] = __('user.product_updated_successfully');

		} else {

			copy('assets/images/dummy-product-img.jpg','assets/images/product/upload/thumb/dummy-product-img.jpg');
			$details['product_featured_image'] = 'dummy-product-img.jpg';
			$details['product_created_date'] = date('Y-m-d H:i:s');
			$details['product_updated_date'] = date('Y-m-d H:i:s');
			$product_id = $this->Product_model->create_data('product', $details);
			$json['created'] = true;
			$json['status'] = true;
			$json['success'] = __('user.product_added_successfully');

			$general_category = $this->db->query('SELECT id FROM categories WHERE name="General"')->row_array();
			
			if(!empty($general_category)) {
				$general_category_id = $general_category['id'];
			} else {
				copy('assets/images/dummy-product-img.jpg','assets/images/product/upload/thumb/dummy-product-img.jpg');
				$general_category_id = $this->Product_model->create_data('categories', array(
					'name'        =>  "General",
					'description' =>  "This is general products category.",
					'parent_id'   =>  0,
					'color'   	  =>  "#FFFFFF",
					'tag'   	  => 1,
					'slug'      => $this->friendly_seo_string('General-0')
				));
			}

			$category = array(
				'product_id' => $product_id,
				'category_id' => $general_category_id,
			);
			
			$this->Product_model->create_data('product_categories', $category);

			$store_setting = $this->Product_model->getSettings('store');

			if($store_setting['status']) {
				
				$notificationData = array(

					'notification_url'          => '/listproduct/'.$product_id,
					
					'notification_type'         =>  'product',
					
					'notification_title'        =>  __('user.new_product_added_in_affiliate_program'),
					
					'notification_view_user_id' =>  'all',
					
					'notification_viewfor'      =>  'user',
					
					'notification_actionID'     =>  $product_id,
					
					'notification_description'  =>  $post['product_name'].' product is addded by admin in affiliate Program on '.date('Y-m-d H:i:s'),
					
					'notification_is_read'      =>  '0',
					
					'notification_created_date' =>  date('Y-m-d H:i:s'),
					
					'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
					
				);

				$this->insertnotification($notificationData);
				
			}
			
			if($post['product_created_by'] !== 1) {
				$seller_comm = [
					'admin_sale_commission_type'      => "default",
					'admin_commission_value'          => 0,
					'admin_click_commission_type'     => "default",
					'admin_click_amount'              => 0,
					'admin_click_count'               => 0,
					'affiliate_click_commission_type' => "default",
					'affiliate_click_count'           => 0,
					'affiliate_click_amount'          => 0,
					'affiliate_sale_commission_type'  => "default",
					'affiliate_commission_value'      => 0,
				];
				
				$seller = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product_id ." ")->row();
				
				$this->Product_model->assignToSellerForce($product_id, $details, $post['product_created_by'], '', 'admin', $seller_comm);
			}
		}


		$seofilename = $this->friendly_seo_string($post['product_name']);
		$seofilename = strtolower($seofilename);
		$product_slug = $seofilename.'-'.$product_id;
		$this->db->query("UPDATE product SET product_slug = ". $this->db->escape($product_slug) ." WHERE product_id =". $product_id);
		
	} catch (Exception $e) {
		$json['status'] = false;
		$json['errors'] = $e->getMessage();
	}
	
	return $json;
	die;
}

private function getProductXlsIndex($xlsHeaders) {
	$headers = $this->productXLSheaders();
	$newHeaders = [];
	foreach($headers as $key => $value) {
		$newHeaders[$key] = array_search($value, $xlsHeaders);
	}
	
	return $newHeaders;
}

private function productXLSheaders() {
	return array(
		'product_id' => 'Product ID',

		'product_name' => 'Product Name',
		'product_sku' => 'Product Sku',
		'product_msrp' => 'Product MSRP',
		'product_price' => 'Product Price',
		'product_short_description' => 'Product Short Desc',
		'product_description' => 'Product Description',
		'product_tags' => 'Product Tags',
		'product_type' => 'Product Type',
		'product_variations' => 'Product Variations',
		
		'allow_comment' => 'Allow Comment',
		'allow_shipping' => 'Allow Shipping',
		'allow_upload_file' => 'Allow File Upload',
		'on_store' => 'Allow on Store',
		'state_id' => 'State ID',

	);
}

public function exportproduct(){

	$userdetails = $this->userdetails();
	if(empty($userdetails)) redirect('usercontrol/dashboard');

	$store_setting = $this->Product_model->getSettings('store');
	
	$json['structure_only'] = $structure_only = $this->input->post('structure_only');

	$filter = array(
		'seller_id' => $userdetails['id'],
	);
	
	if($structure_only == 1) {
		$productlist = [];
	} else {
		$productlist = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'],$filter);
	}

	$header = $this->productXLSheaders();

	$index = 0;

	$_exportData = array();

	$_exportData[$index] = array_values($header);

	require_once APPPATH . '/core/phpspreadsheet/autoload.php';

	foreach ($productlist as $key => $value) {

		$index++;

		foreach ($header as $name_key => $_value) {
			$val = '';

			if(isset($value[$name_key])){

				switch ($name_key) {
					case 'product_tags':
					$t = ( is_array(json_decode($value[$name_key], true)) ? json_decode($value[$name_key], true): [] );
					$val = implode(",", $t);
					break;
					default:
					$val = $value[$name_key];
					break;
				}
			} 

			$_exportData[$index][$name_key] = $val;

		}

	}


    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

	$objPHPExcel->getActiveSheet()->fromArray($_exportData, NULL, 'A1');

    $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);

	$alphas = range('A', 'Z');


	foreach(range('A',$alphas[count($header)]) as $columnID) {

		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		$objWriter->save(FCPATH.'assets/xml/export_products_structure.xlsx');

	}

	if($structure_only == 1) {
		$json['download'] = base_url('assets/xml/export_products_structure.xlsx');
	} else {
		$objWriter->save(FCPATH.'assets/xml/export_products.xlsx');
		$json['download'] = base_url('assets/xml/export_products.xlsx');
	}

	echo json_encode($json);
	
	exit;
}

public function exportproductXML(){
	$userdetails = $this->userdetails();
	if(empty($userdetails)) redirect('usercontrol/dashboard');
	$store_setting = $this->Product_model->getSettings('store');
	$json['structure_only'] = $structure_only = $this->input->post('structure_only');
	$filter = array(
		'seller_id' => $userdetails['id'],
	);
	
	if($structure_only == 1) {
		$productlist = [];
	} else {
		$productlist = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'],$filter);
	}

	$header = $this->productXLSheaders();

	$dom = new DOMDocument();
	$dom->encoding = 'utf-8';
	$dom->xmlVersion = '1.0';
	$dom->formatOutput = true;
	$root = $dom->createElement('products');
	
	if($structure_only == 1) 
	{
		$product_node = $dom->createElement('product');
		foreach ($header as $name_key => $_value) 
		{
			if($name_key!='product_short_description' && $name_key!='product_description' )
			{
				$child_node_title = $dom->createElement($name_key, $_value);
				 $product_node->appendChild($child_node_title);
			}
			else
			{
				$child_node_title = $dom->createElement($name_key);
				$cdataname     = $dom->createCDATASection($_value);
				$child_node_title->appendChild($cdataname);
				$product_node->appendChild($child_node_title);
				 
			}

		}
		$root->appendChild($product_node);
		$dom->appendChild($root);
		$dom->save(FCPATH.'assets/xml/export_vendor_products_structure.xml');
		$json['download'] = base_url('assets/xml/export_vendor_products_structure.xml');
		
	}
	else
	{

		$index = 0;
		$_exportData = array();
		$_exportData[$index] = array_values($header);
		foreach ($productlist as $key => $value) 
		{
			$product_node = $dom->createElement('product');
			$index++;
			foreach ($header as $name_key => $_value) 
			{
				$val = '';

				if(isset($value[$name_key])){

					switch ($name_key) {
						case 'product_tags':
						$t = ( is_array(json_decode($value[$name_key], true)) ? json_decode($value[$name_key], true): [] );
						$val = implode(",", $t);
						break;
						default:
						$val = $value[$name_key];
						break;
					}
				} 

				if($name_key!='product_short_description' && $name_key!='product_description' )
				{
					 $child_node_title = $dom->createElement($name_key, $val);
					 $product_node->appendChild($child_node_title);
				}
				else
				{
					
					$child_node_title = $dom->createElement($name_key);
					$cdataname     = $dom->createCDATASection($val);
					$child_node_title->appendChild($cdataname);
					$product_node->appendChild($child_node_title);
					 
				}
  			}

  			$root->appendChild($product_node);
		}

		$dom->appendChild($root);
		$dom->save(FCPATH.'assets/xml/export_vendor_products.xml');
		$json['download'] = base_url('assets/xml/export_vendor_products.xml');	
	}
  
	echo json_encode($json);
 	exit;
}

public function downloadproductxmlstructurefile($filename = NULL) {
    $userdetails = $this->userdetails();
	if(empty($userdetails)) redirect('usercontrol/dashboard');
    $this->load->helper('download');
    $data = file_get_contents(FCPATH.'assets/xml/export_vendor_products_structure.xml');
    force_download("export_vendor_products_structure.xml", $data);
}

public function downloadproductxmlfile($filename = NULL) {
    $userdetails = $this->userdetails();
	if(empty($userdetails)) redirect('usercontrol/dashboard');
    $this->load->helper('download');
    $data = file_get_contents(FCPATH.'assets/xml/export_vendor_products.xml');
    force_download("export_vendor_products.xml", $data);
}

public function check_duplicate_store() {
	$userdetails = $this->userdetails();
	$data = $this->input->post(null,true);
	$data['store_name'] = urldecode($data['store_name']);
	$json = [
		'store_name' => $data['store_name']
	];
	if(isset($data['store_name']) && !empty($data['store_name'])) {
		$store_name = $data['store_name'];
		$store_slug = slugifyThis($store_name);
		$checkStorename = $this->db->query("SELECT id FROM users WHERE store_slug like '".$store_slug."' AND id!=".$userdetails['id'])->num_rows();
		if($checkStorename > 0){ 
			$json['error'] = __('user.store_name_already_exists'); 
		}
	}
	echo json_encode($json); die;
}

public function store_setting(){
	$userdetails = $this->userdetails();

	if(empty($userdetails)) redirect('usercontrol/dashboard');

	$vendor_setting = $this->Product_model->getSettings('vendor');

	$store_setting = $this->Product_model->getSettings('store');

	$data['CurrencySymbol'] = $this->currency->getSymbol();
	
	if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1) redirect('usercontrol/dashboard');

	if($this->input->server('REQUEST_METHOD') == 'POST'){
		$json = [];
		$data = $this->input->post(null,true);

		if(isset($data['store_page_settings']) && $data['store_page_settings'] == 1) {
			$updateData = [];
			$storeMeta = [];
			if(isset($data['store_name']) && !empty($data['store_name'])) {
				$store_name = $data['store_name'];
				$store_contact_us_map = $data['store_contact_us_map'];
				$store_address = $data['store_address'];
				$store_email = $data['store_email'];
				$store_contact_number = $data['store_contact_number'];
				$store_terms_condition = $data['store_terms_condition'];
				$store_slug = slugifyThis($store_name);
				$checkStorename = $this->db->query("SELECT id FROM users WHERE store_slug like '".$store_slug."' AND id!=".$userdetails['id'])->num_rows();
				if($checkStorename > 0){ 
					$json['errors']['store_name'] = "Store Name Already Exist"; 
					echo json_encode($json); die;
				}

				$updateData = [
					'store_name' => $store_name,
					'store_contact_us_map' => $store_contact_us_map,
					'store_address' => $store_address,
					'store_email' => $store_email,
					'store_contact_number' => $store_contact_number,
					'store_terms_condition' => $store_terms_condition,
					'store_slug' => $store_slug
				];
			} else {
				$updateData = [
					'store_name' => null,
					'store_slug' => null
				];
			}

			$storeMeta['cover_text_color'] = (isset($data['cover_text_color']) && !empty($data['cover_text_color'])) ? $data['cover_text_color'] : "#FFFFFF";

			$storeMeta['cover_show_vendor_name'] = (int)$data['cover_show_vendor_name'] ?? 0;

			$filesForUpload = ['store_logo', 'cover_background'];

			if (!file_exists('path/to/directory')) {
				mkdir('assets/user_upload/vendor_store', 0644, true);
			}

			foreach($filesForUpload as $file) {
				
				$ext = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);

				if(isset($_FILES[$file]['name']) && !empty($_FILES[$file]['name'])){
					if( !in_array($ext, ['jpg','png','jpeg']) && $category_id == 0){
						$json['errors'][$file] = 'Only image file are allowed';
						echo json_encode($json); die;
					} else if(!empty($_FILES[$file]['name'])){
						$upload_response = $this->upload_photo($file,'assets/user_upload/vendor_store');
						if($upload_response['success']){
							$storeMeta[$file] = $upload_response['upload_data']['file_name'];
						}else{
							$json['errors'][$file] = $upload_response['msg'];
							echo json_encode($json); die;
						}
					}
				}
			}

			$updateData['store_meta'] = !empty($storeMeta) ? json_encode($storeMeta) : null;

			$this->db->where('id', $userdetails['id']);
			$this->db->update('users', $updateData);
			$json['success'] = __('user.settings_updated_successfully');
			$json['store_page_url'] = base_url('store/').$store_slug;
		} else {
			$update = [
				'vendor_status'                       => $data['vendor_status'],
				'affiliate_click_count'               => $data['affiliate_click_count'],
				'affiliate_click_amount'              => $data['affiliate_click_amount'],
				'affiliate_sale_commission_type'      => $data['affiliate_sale_commission_type'],
				'affiliate_commission_value'          => $data['affiliate_commission_value'],
				'form_affiliate_click_count'          => $data['form_affiliate_click_count'],
				'form_affiliate_click_amount'         => $data['form_affiliate_click_amount'],
				'form_affiliate_sale_commission_type' => $data['form_affiliate_sale_commission_type'],
				'form_affiliate_commission_value'     => $data['form_affiliate_commission_value'], 
				'user_id'                             => (int)$userdetails['id'],
			];

			$id = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row();
			
			if($id){
				$this->db->update("vendor_setting", $update, ['user_id'=> (int)$userdetails['id'] ]);
			} else{
				$this->db->insert("vendor_setting", $update);
			}
			
			$json['success'] = __('user.setting_saved_successfully');
		}

		echo json_encode($json);die;
	}

	$data['store_details'] = $this->db->query('SELECT store_name, store_contact_us_map, store_address, store_email, store_contact_number, store_terms_condition,store_slug, store_meta FROM users WHERE id='.$userdetails['id'])->row_array();

	$data['setting'] = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row_array();
	
	$this->view($data,'store/store_setting','usercontrol');
}

public function contact_us(){
	$userdetails = $this->userdetails();
	if(empty($userdetails)){ redirect('/login'); }

	$userdashboard_settings = $this->Common_model->getUserDashboardSettings();

	if(! isShowUserControlParts($userdashboard_settings['contact_us_page'])) {
		show_404();
	}

	if ($this->input->server('REQUEST_METHOD') == 'POST'){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('body', 'Mail Body', 'required' );

		if($this->form_validation->run()){
			$data = $this->input->post(null);

			if(isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {
				$fileNameArray = explode('.', $_FILES['attachment']['name']);

				$config['upload_path'] = 'assets/user_upload';

				$config['allowed_types'] = 'png|gif|jpeg|jpg|PNG|GIF|JPEG|JPG|ICO|ico|zip|doc|docs|pdf|xls|xlsx|ppt|pptx|txt';

				$config['max_size']      = 2048;

				$this->load->helper('string');

				$config['file_name']  = random_string('alnum', 32);

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (!$this->upload->do_upload('attachment')) {
					$errors = $this->upload->display_errors();
				} else {
					$data['attachment'] = base_url().'assets/user_upload/'.$config['file_name'].".".$fileNameArray[sizeof($fileNameArray)-1];
				}
			}

			if(!isset($errors) || empty($errors)) {
				$this->load->model('Mail_model');
				$data['email'] = $userdetails['email'];
				$data['firstname'] = $userdetails['firstname'];
				$data['lastname'] = $userdetails['lastname'];
				$mailRes = $this->Mail_model->send_store_contact_vendor($data);
				if(str_contains($mailRes, 'successfully')) {
					$json['success'] = $mailRes;
				} else {
					$json['errors'] = $mailRes;
				}
			} else {
				$json['errors']['attachment'] = $errors;
			}
		}else{
			$json['errors'] = $this->form_validation->error_array();
		}

		echo json_encode($json);die;
	}

	$data['notcheckapproval'] = 1; 
	$data['notcheckmember'] = 1;

	$data['userdetails'] = $this->Product_model->userdetails('user');
	$data['domain'] = base_url('/');
	$data['user_mobile'] = '';
	

	$this->load->model('PagebuilderModel');
	$register_form = $this->PagebuilderModel->getSettings('registration_builder');
	if($register_form){
		$customField = json_decode($register_form['registration_builder'],1);
		

		foreach ($customField as $_key => $_value) {
			$field_name = 'custom_'. $_value['name'];
			if(!isset($json['errors'][$field_name]) && $_value['mobile_validation']  == 'true'){
				$custom_val = json_decode($data['userdetails']['value'],1);
				$data['user_mobile'] = isset($custom_val[$field_name]) ? $custom_val[$field_name] : '';
			}
		}
	}

	$this->view($data,'store/store_contact','usercontrol');
}

public function create(){
	$userdetails = $this->userdetails();
	if(empty($userdetails)){ redirect('/login'); }
	$vendor_setting = $this->Product_model->getSettings('vendor');
	if((int)$vendor_setting['storestatus'] == 0) show_404();

	$data['checkout_template'] = get_available_checkout_template();
	$data['vendor_setting'] = $vendor_setting;
	$data['setting'] 	= $this->Product_model->getSettings('productsetting');
	$data['product'] = $this->Product_model->getProductById($product_id);
	$data['tags'] = $this->Product_model->getAllTags();
	$data['CurrencySymbol'] = $this->currency->getSymbol();
	$data['s3_setting'] = $this->Product_model->getSettings('s3_storage');

	if($data['product']){
		$data['seller'] = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$data['product']->product_id ." ")->row();
		if(!$data['seller'] || $data['seller']->user_id != $userdetails['id']){
			show_404();
		}

		$data['categories'] =$this->Product_model->getProductCategory($data['product']->product_id);
		$data['downloads'] = $this->Product_model->parseDownloads($data['product']->downloadable_files,$data['product']->product_type);
		$data['product_state'] = $this->db->query("SELECT * FROM states WHERE id=". (int)$data['product']->state_id )->row();
		$data['states'] = $this->db->query("SELECT * FROM states WHERE country_id=". (int)$data['product_state']->country_id )->result();
	}

	$data['country_list'] = $this->db->query("SELECT name,id FROM countries")->result();
	$data['seller_setting'] = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row();
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		$data['needs_product_approval'] = (int)($market_vendor['marketaddnewstoreproduct'] ?? 0);
		$this->view($data,'store/create_sale_products','usercontrol');
	}

	public function save_sale_product(){

		$userdetails = $this->userdetails();

		$post = $this->input->post(null,true);

		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		if(!empty($post)){

			$product_id = (int)$this->input->post('product_id',true);

			$this->load->helper(array('form', 'url'));

			$this->load->library('form_validation');

			$this->form_validation->set_rules('product_url', __('user.product_purchase_url'), 'required');

			$this->form_validation->set_rules('product_name', __('user.product_name_'), 'required');

			$this->form_validation->set_rules('category[]', "Category", "required");

			$this->form_validation->set_rules('product_description', __('user.product_description'), 'required' );

			$this->form_validation->set_rules('product_price', 'Product Price', 'required');

			$this->form_validation->set_rules('product_sku', 'Product SKU', 'required');

			
			if((isset($post['product_sale_period']) && !empty($post['product_sale_period'])) || (isset($post['product_sale_period_price']) && !empty($post['product_sale_period_price']))) {
				$this->form_validation->set_rules('product_sale_period', 'Sale Durataion', 'required');
				$this->form_validation->set_rules('product_sale_price', 'Sale Duration Price', 'required|numeric|greater_than[0]');
			}


			if($post['allow_country'] == "on"){

				$this->form_validation->set_rules('state_id', 'State', 'required' );

			}

			if( $post['product_recursion_type'] == 'custom' ){

				$this->form_validation->set_rules('product_recursion', 'Product Recursion', 'required');

				if( $post['product_recursion'] == 'custom_time' ){

					$this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');

				}

			}

			$product_recursion = ($post['product_recursion_type'] && $post['product_recursion_type'] != 'default') ? $post['product_recursion'] : "";

			$recursion_custom_time = ($product_recursion == 'custom_time' ) ? $post['recursion_custom_time'] : 0;


			if($this->form_validation->run()){

				$post = $this->input->post(null,true);			

				$errors = array();


				$downloadable_files = array();
				if($product_id){
					$product_details = $this->Product_model->getProductById($product_id);
					$_downloads = $this->Product_model->parseDownloads($product_details->downloadable_files,$product_details->product_type);
					foreach($_downloads as $key=> $value) {
						$isKeep =false;
						foreach($post['keep_files'] as $innerKey =>$innerValue) {
							if($innerValue == $key) {
								$downloadable_files[] = $_downloads[$key];
								unset($post['keep_files'][$innerKey]);
								$isKeep=true;
							}
							
						}
						if(!$isKeep){
							@unlink(APPPATH.'/downloads/'.$key);
						}
					}
				}

				$details = array(
					'is_campaign_product'		   => 1,
					'product_url'				   => $post['product_url'],
					'product_name'                 =>  $post['product_name'],
					'product_msrp'                 =>  $post['product_msrp'],
					'product_description'          =>  $post['product_description'],
					'product_price'                =>  $post['product_price'],
					'product_sku'                  =>  $post['product_sku'],
					'product_price'                =>  $post['product_price'],
					'product_type'                 =>  $post['product_type'],
					'state_id'                     =>  $post['allow_country'] == "on" ? (int)$post['state_id'] : 0,
					'product_commision_type'       =>  'default',
					'product_commision_value'      =>  0,
					'product_click_commision_type' =>  'default',
					'product_click_commision_ppc'  =>  0,
					'product_click_commision_per'  =>  0,
					'on_store'                     =>  (int)$post['on_store'],
					'allow_shipping'               =>  (int)$post['allow_shipping'],
					'allow_upload_file'            =>  (int)$post['allow_upload_file'],
					'allow_comment'                =>  (int)$post['allow_comment'],
					'product_status'               =>  isset($post['product_status']) ? (int)$post['product_status'] : 1,
					'product_ipaddress'            =>  $_SERVER['REMOTE_ADDR'],
					'product_recursion_type'       =>  $post['product_recursion_type'],
					'recursion_endtime'       =>  (isset($post['recursion_endtime_status']) && $post['recursion_endtime']) ? date("Y-m-d H:i:s",strtotime($post['recursion_endtime'])) : null,
					'product_recursion'            =>  $product_recursion,
					'recursion_custom_time'        =>  (int)$recursion_custom_time,
				);
				//use for update product on review
				$market_vendor = $this->Product_model->getSettings('market_vendor','marketaddnewstoreproduct');
				if($market_vendor['marketaddnewstoreproduct']){
					$details['product_status'] = 0;
				}else{
					$details['product_status'] = 1;
				}
				
				$details['product_featured_image'] = $post['product_featured_image_s3'];

				if($_FILES['product_featured_image']['error'] != 0 && $product_id == 0 && @$post['product_featured_image_s3'] == ""){

					$errors['product_featured_image'] = 'Select Featured Image File!';

				} else if(!empty($_FILES['product_featured_image']['name'])){

					$upload_response = $this->upload_photo('product_featured_image','assets/images/product/upload/thumb');

					if($upload_response['success']){

						$details['product_featured_image'] = $upload_response['upload_data']['file_name'];

					}else{

						$errors['product_featured_image'] = $upload_response['msg'];

					}
				}

				if(!empty($_FILES['downloadable_file'])){

					$files = $_FILES['downloadable_file'];

					if(isset($_FILES['downloadable_file']['name']) && is_array($_FILES['downloadable_file']['name']))
					$count_file = count($_FILES['downloadable_file']['name']);
					else
					$count_file=0;


					$this->load->helper('string');	

					for($i=0; $i<$count_file; $i++){

						$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
						if(!empty(trim($files['name'][$i]))){
							if($extension=='zip'){

								$FILES['downloadable_files']['name'] = md5(random_string('alnum', 10));

								$FILES['downloadable_files']['type'] = $files['type'][$i];

								$FILES['downloadable_files']['tmp_name'] = $files['tmp_name'][$i];

								$FILES['downloadable_files']['error'] = $files['error'][$i];

								$FILES['downloadable_files']['size'] = $files['size'][$i];    


								if(empty($FILES['downloadable_files']['error'])){

									move_uploaded_file($FILES['downloadable_files']['tmp_name'], APPPATH.'/downloads/'. $FILES['downloadable_files']['name']);

									$downloadable_files[] = array(

										'type' => $FILES['downloadable_files']['type'],

										'name' => $FILES['downloadable_files']['name'],

										'mask' => $files['name'][$i],

									);
									$details['downloadable_files']=json_encode($downloadable_filess);
								}else{

									$errors['downloadable_files'] = $FILES['downloadable_files']['error'];
								}

							} else {

								$zip = new ZipArchive();

								$zip_name = md5(random_string('alnum', 10));

								if ($zip->open(APPPATH.'/downloads/'.$zip_name, ZipArchive::CREATE) !== TRUE) {
									$errors['downloadable_files'] = "Sorry ZIP creation is not working currently.";
								}

								$zip->addFromString($files['name'][$i], file_get_contents($files['tmp_name'][$i]));

								$zip->close(); 

								$downloadable_files[] = array(

									'type' => 'application/x-zip-compressed',

									'name' =>$zip_name,

									'mask' => preg_replace('/\\.[^.\\s]{3,4}$/', '', $files['name'][$i]).'.zip',

								);
							}

						}
					}
										
					

				}
				// Process downloadable S3 files
				if (!empty($_POST['downloadable_file_s3'])) {
				    $s3Files = json_decode($_POST['downloadable_file_s3'], true);
				    
				    foreach ($s3Files as $s3file) {
				        // Generate a unique name for each file to prevent conflicts
				        $zip_name = md5(random_string('alnum', 10));

				        // Append S3 file details to the downloadable files list
				        $downloadable_files[] = [
				            'srctype' => 'AwsS3',
				            'name'    => $zip_name,
				            'url'     => $s3file['imageurl'],
				            'type'    => $s3file['type'],
				            'mask'    => $s3file['name'],
				        ];
				    }
				}

				$details['downloadable_files']=json_encode($downloadable_files);
				$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$userdetails['id'])->first();
				$plan_product_count = ($product_id) ? $userPlan->plan->product : $userPlan->plan->product - 1;
				$vendor_product_count = $this->Product_model->countByField('product_affiliate','user_id',$userdetails['id']);
				if(isset($userPlan->plan->product) && $plan_product_count < $vendor_product_count)
					$errors['upgrade_membership_plan'] = __('user.reached_maximum_limit_package_upgrade').' <a href="'.base_url('usercontrol/purchase_plan').'">'.__('user.here').'</a>';
				
				if(empty($errors)){

					$old_product_data =[];

					if($product_id){
						$old_product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();
						$details['product_updated_date'] = date('Y-m-d H:i:s');
						$this->Product_model->update_data('product', $details, array('product_id' => $product_id));
						$this->session->set_flashdata('success', __('user.product_campaign_updated_successfully'));
					} else {
						$details['product_created_by'] = $userdetails['id'];
						$details['product_updated_date'] = date('Y-m-d H:i:s');

						//Used for new product review
						$market_vendor = $this->Product_model->getSettings('market_vendor','marketaddnewstoreproduct');
						if($market_vendor['marketaddnewstoreproduct'])
							$details['product_status'] = 0;
						else 
							$details['product_status'] = 1;


						$details['product_created_date'] = date('Y-m-d H:i:s');

						$product_id = $this->Product_model->create_data('product', $details);
						$new_product_created = true;

						$notificationData = array(
							'notification_url'          => 'updateproduct/'.$product_id,
							'notification_type'         =>  'vendor_product',
							'notification_title'        =>  __('user.new_product_added_by_vendor'),
							'notification_viewfor'      =>  'admin',
							'notification_actionID'     =>  $product_id,
							'notification_description'  =>  $post['product_name'].' product is addded by '. $userdetails['username'] .' in store on '.date('Y-m-d H:i:s'),
							'notification_is_read'      =>  '0',
							'notification_created_date' =>  date('Y-m-d H:i:s'),
							'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
						);

						$this->insertnotification($notificationData);

						$this->session->set_flashdata('success', __('user.product_campaign_added_successfully'));
					}

					$seofilename = $this->friendly_seo_string($post['product_name']);

					$seofilename = strtolower($seofilename);

					$product_slug = $seofilename.'-'.$product_id;

					$this->db->query("UPDATE product SET product_slug = ". $this->db->escape($product_slug) ." WHERE product_id =". $product_id);

					$seller = '';

					if($product_id){

						$this->db->query("DELETE FROM product_categories WHERE product_id = {$product_id}");
						$this->db->query("DELETE FROM product_meta WHERE related_product_id = {$product_id} AND meta_key NOT IN ('funnel_upsells', 'funnel_price')");

						if(isset($post['product_sale_period']) && !empty($post['product_sale_period']) && isset($post['product_sale_price']) && !empty($post['product_sale_price'])) {
							$product_sale_period = explode(" - ", $post['product_sale_period']);
							$post['product_sale_start'] = date('Y-m-d H:i:s', strtotime($product_sale_period[0])); 
							$post['product_sale_end'] = date('Y-m-d H:i:s', strtotime($product_sale_period[1])); 
							$post['product_sale_price'] = $post['product_sale_price'];
						}


						if(isset($post['product_reviewer_name']) && !empty($post['product_reviewer_name'])) {
							$post['product_reviews'] = [];

							foreach ($post['product_reviewer_name'] as $key => $value) {
								array_push($post['product_reviews'], [
									'name' => $value,
									'comment' => $post['product_reviewer_comment'][$key],
								]);
							}

							$post['product_reviews'] = json_encode($post['product_reviews']);
						}
						if(isset($post['product_footer_name']) && !empty($post['product_footer_name'])) {
							$post['product_footer'] = [];

							foreach ($post['product_footer_name'] as $key => $value) {
								array_push($post['product_footer'], [
									'name' => $value,
									'description' => $post['product_footer_description'][$key],
								]);
							}

							$post['product_footer'] = json_encode($post['product_footer']);
						}

						$metaKeys = ['checkout_template', 'show_to_affiliates', 'product_launching_datetime', 'product_quantity', 'product_sale_start', 'product_sale_end', 'product_sale_price', 'product_checkout_terms', 'product_reviews','product_footer', 'show_to_featured'];

						foreach ($metaKeys as $metaKey) {
							if(isset($post[$metaKey]) && $post[$metaKey] != ""){

								if($metaKey === 'product_launching_datetime') {
									$post[$metaKey] = date('Y-m-d H:i:s', strtotime($post[$metaKey]));
								}

								$this->Product_model->create_data('product_meta', array(
									'related_product_id' => $product_id,
									'meta_key' => $metaKey,
									'meta_value' => $post[$metaKey]
								));
							}
						}

						
						if(isset($post['category']) && is_array($post['category'])){

							foreach ($post['category'] as $category_id) {

								$category = array(

									'product_id' => $product_id,

									'category_id' => $category_id,

								);

								$this->Product_model->create_data('product_categories', $category);
							}
						}

						$admin_comment = '';

						if(isset($post['admin_comment']) && $post['admin_comment']){

							$admin_comment = $post['admin_comment'];

						}

						$seller_comm = [
						'affiliate_click_commission_type' => $post['affiliate_click_commission_type'],
						'affiliate_click_count'           => $post['affiliate_click_count'],
						'affiliate_click_amount'          => $post['affiliate_click_amount'],
						'affiliate_sale_commission_type'  => $post['affiliate_sale_commission_type'],
						'affiliate_commission_value'      => $post['affiliate_commission_value'],
					];

					$this->Product_model->assignToSeller($product_id, $details, $userdetails['id'], $admin_comment,'affiliate', $seller_comm);

					if(empty($market_vendor['marketaddnewstoreproduct'])){
						$vendor_setting = $this->Product_model->getSettings('vendor');
						$seller = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=".$product_id." ")->row();

						$seller_comm = [

							'admin_sale_commission_type'      => $vendor_setting['admin_sale_commission_type'],

							'admin_commission_value'          => $vendor_setting['admin_commission_value'],

							'admin_click_commission_type'     => ($seller->admin_click_commission_type) ? $seller->admin_click_commission_type : 'default',

							'admin_click_amount'              => $vendor_setting['admin_click_amount'],

							'admin_click_count'               => $vendor_setting['admin_click_count'],

						];

						$this->Product_model->assignToSeller($product_id, $details, $userdetails['id'], $admin_comment,'admin', $seller_comm);
					}

					$this->load->model('Mail_model');
					if($new_product_created){
						$this->Mail_model->vendor_create_product($product_id);
					} else {
						$product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();

						if($old_product_data['product_status'] != $product_data['product_status'] && $product_data['product_status'] == 0)
							$this->Mail_model->vendor_product_status_change($product_id, 'admin',true);
					}
				}

				$json['location'] = base_url('usercontrol/sales_products');


				} else {
					$json['errors'] = $errors;
				}

			} else {

				$json['errors'] = $this->form_validation->error_array();

				if(isset($json['errors']['category[]'])){

					$json['errors']['category_auto'] = $json['errors']['category[]'];

				}

			}

			echo json_encode($json);

			die;

		}
	}


	public function integration_code_modal_sale(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$data['product'] = $this->db->query('select * from product where product_id='.(int)$this->input->post('id',true))->row();
		echo $this->load->view('admincontrol/product_campaign/integration_code_modal', $data, true);
		die;
	}

	public function duplicate_sale_product(){
		$json = array('success' => false);
		$userdetails = $this->userdetails();

		if(empty($userdetails)){
			$json['message'] = __('user.authentication_required');
			echo json_encode($json);
			die;
		}

		$product_id = (int)$this->input->post('product_id',true);

		if(empty($product_id)){
			$json['message'] = __('user.invalid_product');
			echo json_encode($json);
			die;
		}

		$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$userdetails['id'])->first();
		$plan_product_count = $userPlan->plan->product;
		$vendor_product_count = $this->Product_model->countByField('product_affiliate','user_id',$userdetails['id']);

		if(!empty($plan_product_count) && $vendor_product_count >= $plan_product_count){
			$json['message'] = __('user.reached_maximum_limit_package_upgrade');
			echo json_encode($json);
			die;
		}

		$product = $this->db->query("SELECT p.*, pa.* FROM product p LEFT JOIN product_affiliate pa ON p.product_id = pa.product_id WHERE p.product_id = " . (int)$product_id . " AND pa.user_id = " . (int)$userdetails['id'])->row_array();

		if(empty($product)){
			$json['message'] = __('user.product_not_found');
			echo json_encode($json);
			die;
		}

		$market_vendor = $this->Product_model->getSettings('market_vendor','marketaddnewstoreproduct');
		$status_review = $market_vendor['marketaddnewstoreproduct'] ? 0 : 1;

		$new_product = array(
			'is_campaign_product' => 1,
			'product_url' => $product['product_url'],
			'product_name' => $product['product_name'] . ' - Copy',
			'product_description' => $product['product_description'],
			'product_short_description' => $product['product_short_description'],
			'product_msrp' => $product['product_msrp'],
			'product_price' => $product['product_price'],
			'product_sku' => $product['product_sku'] . '-COPY-' . time(),
			'product_video' => $product['product_video'],
			'product_type' => $product['product_type'],
			'product_commision_type' => 'default',
			'product_commision_value' => 0,
			'product_click_commision_type' => 'default',
			'product_click_commision_ppc' => 0,
			'product_click_commision_per' => 0,
			'on_store' => 0,
			'allow_shipping' => $product['allow_shipping'],
			'allow_upload_file' => $product['allow_upload_file'],
			'allow_comment' => $product['allow_comment'],
			'product_status' => $status_review,
			'product_ipaddress' => $_SERVER['REMOTE_ADDR'],
			'product_recursion_type' => $product['product_recursion_type'],
			'recursion_endtime' => $product['recursion_endtime'],
			'product_recursion' => $product['product_recursion'],
			'recursion_custom_time' => $product['recursion_custom_time'],
			'product_featured_image' => $product['product_featured_image'],
			'downloadable_files' => $product['downloadable_files'],
			'state_id' => $product['state_id'],
			'product_created_by' => $userdetails['id'],
			'product_updated_date' => date('Y-m-d H:i:s'),
			'product_created_date' => date('Y-m-d H:i:s'),
		);

		$new_product_id = $this->Product_model->create_data('product', $new_product);

		if($new_product_id){
			$seofilename = $this->friendly_seo_string($new_product['product_name']);
			$seofilename = strtolower($seofilename);
			$product_slug = $seofilename.'-'.$new_product_id;
			$this->db->query("UPDATE product SET product_slug = ". $this->db->escape($product_slug) ." WHERE product_id = ". $new_product_id);

			$categories = $this->db->query("SELECT * FROM product_categories WHERE product_id = ". (int)$product_id)->result_array();
			foreach($categories as $category){
				$this->Product_model->create_data('product_categories', array(
					'product_id' => $new_product_id,
					'category_id' => $category['category_id'],
				));
			}

			$seller_comm = array(
				'affiliate_sale_commission_type' => 'default',
				'affiliate_commission_value' => 0,
				'affiliate_click_commission_type' => 'default',
				'affiliate_click_amount' => 0,
				'affiliate_click_count' => 0,
			);

			$this->Product_model->assignToSeller($new_product_id, $new_product, $userdetails['id'], '', 'affiliate', $seller_comm);

			if(empty($market_vendor['marketaddnewstoreproduct'])){
				$vendor_setting = $this->Product_model->getSettings('vendor');
				$seller_comm_admin = array(
					'admin_sale_commission_type' => $vendor_setting['admin_sale_commission_type'],
					'admin_commission_value' => $vendor_setting['admin_commission_value'],
					'admin_click_commission_type' => 'default',
					'admin_click_amount' => $vendor_setting['admin_click_amount'],
					'admin_click_count' => $vendor_setting['admin_click_count'],
				);
				$this->Product_model->assignToSeller($new_product_id, $new_product, $userdetails['id'], '', 'admin', $seller_comm_admin);
			}

			$this->load->model('Mail_model');
			$this->Mail_model->vendor_create_product($new_product_id);

			$json['success'] = true;
			$json['message'] = __('user.product_duplicated_successfully');
		} else {
			$json['message'] = __('user.error_occurred');
		}

		echo json_encode($json);
		die;
	}

	public function store_edit_product($product_id = 0){


	 	$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		$data['vendor_setting'] = $vendor_setting;
		$data['setting'] 	= $this->Product_model->getSettings('productsetting');
		$data['product'] = $this->Product_model->getProductById($product_id);
		if(!$data['product']){
			$data['product'] = $this->Product_model->getEmptyProduct();
		}
		$data['tags'] = $this->Product_model->getAllTags();
		$data['CurrencySymbol'] = $this->currency->getSymbol();
		$data['setting'] = $this->User_model->getS3Settings($userdetails['id']);

		if((int)$product_id > 0){
			$data['seller'] = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$data['product']->product_id ." ")->row();
			if(!$data['seller'] || $data['seller']->user_id != $userdetails['id']){
				show_404();
			}

			$data['categories'] = $this->Product_model->getProductCategory($data['product']->product_id);
			$data['downloads'] = $this->Product_model->parseDownloads($data['product']->downloadable_files,$data['product']->product_type);
			$data['product_state'] = $this->db->query("SELECT * FROM states WHERE id=". (int)$data['product']->state_id )->row();
			$data['states'] = $data['product_state'] ? $this->db->query("SELECT * FROM states WHERE country_id=". (int)$data['product_state']->country_id )->result() : [];
		} else {
			$data['categories'] = [];
			$data['downloads'] = [];
			$data['product_state'] = null;
			$data['states'] = [];
		}

		$data['country_list'] = $this->db->query("SELECT name,id FROM countries")->result();
		$data['seller_setting'] = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row();
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		$sv = isset($market_vendor['marketaddnewstoreproduct']) ? (string)$market_vendor['marketaddnewstoreproduct'] : '0';
		$data['needs_product_approval'] = ($sv === '1' || $sv === 1) ? 1 : 0;

		$this->view($data,'store/product_form','usercontrol');
	}


	public function store_save_product(){

		$userdetails = $this->userdetails();
		$post = $this->input->post(null,true);


		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();


		if(!empty($post)){
			$product_id = (int)$this->input->post('product_id',true);
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');

			$this->form_validation->set_rules('product_name', __('user.product_name_'), 'required');
			$this->form_validation->set_rules('product_description', __('user.product_description'), 'required' );

			if($post['allow_country'] == "1"){
				$this->form_validation->set_rules('state_id', 'State', 'required' );
			}
			$this->form_validation->set_rules('product_short_description', __('user.short_description'),'required|min_length[5]|max_length[150]',
				array(
					'required'      => 'Enter %s',
					'is_unique'     => 'This %s already exists.',
					'min_length' 	=> '%s: the minimum of characters is %s',
					'max_length' 	=> '%s: the maximum of characters is %s',
				)
			);
			$this->form_validation->set_rules('category[]',"Category", "required");
			$this->form_validation->set_rules('product_price', 'Product Price', 'required');
			$this->form_validation->set_rules('product_sku', 'Product SKU', 'required');
			$this->form_validation->set_rules('product_video', 'Product Video', 'trim');

			if( $post['product_recursion_type'] == 'custom' ){
				$this->form_validation->set_rules('product_recursion', 'Product Recursion', 'required');
				if( $post['product_recursion'] == 'custom_time' ){
					$this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');
				}
			}

			$variations = [];
			if(isset($post['variations']) && !empty($post['variations'])) {
				foreach($post['variations'] as $key => $value) {
					if(!empty($value)) {
						$new_value = [];
						if($key == 'colors') {
							for ($i=0; $i < sizeof($post['variations'][$key]['code']); $i++) { 
								if(!empty($post['variations'][$key]['code'][$i]) && $post['variations'][$key]['name'][$i]) {
									array_push($new_value, [
										'code'=>$post['variations'][$key]['code'][$i], 
										'name'=> $post['variations'][$key]['name'][$i],
										'price'=> $post['variations'][$key]['price'][$i]
									]);
								}
							}
						} else {
							for ($i=0; $i < sizeof($post['variations'][$key]['name']); $i++) { 
								if(!empty($post['variations'][$key]['name'][$i])) {
									array_push($new_value, [
										'name'=> $post['variations'][$key]['name'][$i],
										'price'=> $post['variations'][$key]['price'][$i]
									]);
								}
							}
						}
						$variations[$key] = $new_value;
					}
				}
			}
			

			$product_recursion = ($post['product_recursion_type'] && $post['product_recursion_type'] != 'default') ? $post['product_recursion'] : "";
			$recursion_custom_time = ($product_recursion == 'custom_time' ) ? $post['recursion_custom_time'] : 0;

			if($this->form_validation->run()){
				$post = $this->input->post(null,true);			


				$errors = array();
				$downloadable_files = array();

				if($product_id){

							$product_details = $this->Product_model->getProductById($product_id);

							$_downloads = $this->Product_model->parseDownloads($product_details->downloadable_files,$product_details->product_type);
							
							foreach ($post['keep_files'] as $key => $_value) {

								if(isset($_downloads[$_value])){
									if($post['product_type'] =='video' && $post['sub_product_type'] =="video"){
										$_downloads[$_value]['videotext'] = $post['videotext'][$key]??null;
										$downloadable_files[] = $_downloads[$_value];
									} else if($post['product_type'] =='video' && $post['sub_product_type'] =="videolink"){ 
										@unlink(APPPATH.'/downloads/'.$_value.".zip");
									} else {
										$downloadable_files[] = $_downloads[$_value];
									}


								} else{

									@unlink(APPPATH.'/downloads/'.$_value);

								}
							}

							if(isset($_downloads) && is_array($_downloads))	
							$allKeys=array_keys($_downloads);
							else
							$allKeys=array();	

							if(isset($post['keep_video_files']) && is_array($_downloads))	
							$keepKeys=array_keys($post['keep_video_files']);
							else
							$keepKeys=array();

							$deletedSectionKeys  = array_diff($allKeys,$keepKeys);
							$deletedSectionKeys = array_values($deletedSectionKeys);
							$_download_new=[];
							if(isset($post['keep_video_files'])) {
								
								foreach($post['keep_video_files'] as $innerKey =>$innerValue) {
									$keepVideo =[];
									for ($i=0; $i < count($innerValue); $i++) { 
										$key = array_search($innerValue[$i], array_column($_downloads[$innerKey]['data'], 'name'));
										if($key!=FALSE || $key ==0) {
											$keepVideo[]=$key;
										}
									}
									$deleteVideoFromSectionKey = array_diff(array_keys($_downloads[$innerKey]['data']),$keepVideo);

									// Remove video from Section
									foreach ($deleteVideoFromSectionKey as $key=>  $value) {
										if(file_exists(APPPATH.'/downloads/'.$_downloads[$innerKey]['data'][$value]['mask'])) {
											@unlink(APPPATH.'/downloads/'.$_downloads[$innerKey]['data'][$value]['mask']);
											@unlink(APPPATH.'/downloads/'.$_downloads[$innerKey]['data'][$value]['zip']['mask']);
										}
										unset($_downloads[$innerKey]['data'][$value]);
									}
									for ($i=0; $i < count($deletedSectionKeys) ; $i++) { 
										foreach ($_downloads[$deletedSectionKeys[$i]]['data'] as $key => $value) {
											if(file_exists(APPPATH."/downloads/").$value['mask']) {
												@unlink(APPPATH."/downloads/".$value['mask']);
												@unlink(APPPATH."/downloads/".$value['zip']['mask']);
											}
										}
										unset($_downloads[$deletedSectionKeys[$i]]);
									}
									// update title  
									$oldVideo= [];
									foreach ($keepVideo as $key => $value) {
										$zip = $_downloads[$innerKey]['data'][$value]['zip']??[];
										$zip['title'] = $post['VideoFileResourceText'][$innerKey][$value]??($_downloads[$innerKey]['data'][$value]['zip']['title']??'');
										$oldVideo[]=[
											'type'=>$_downloads[$innerKey]['data'][$value]['type'],
											'name'=>$_downloads[$innerKey]['data'][$value]['name'],
											'mask'=>$_downloads[$innerKey]['data'][$value]['mask'],
											'size'=>$_downloads[$innerKey]['data'][$value]['size'],
											'videotext'=>$post['videotext'][$innerKey][$value]??$_downloads[$innerKey]['data'][$value]['videotext'],
											'duration'=>$post['duration'][$innerKey][$value]??$_downloads[$innerKey]['data'][$value]['duration'],
											'description'=>$post['description'][$innerKey][$value]??$_downloads[$innerKey]['data'][$value]['description'],
											'zip'=> $zip,
										];
									}
									$_download_new[] = [
										'title'=>$post['section'][$innerKey],
										'data'=>$oldVideo
									];
								}

								
								$downloadable_files =$_download_new;
							}

						}

				// Process videolink (YouTube/Vimeo) for NEW product - existing videolink block assumes $downloadable_files has structure
				if(isset($post['product_type']) && $post['product_type'] == 'video' && empty($product_id) && !empty($post['sub_product_type']) && $post['sub_product_type'] == 'videolink') {
					if(!empty($post['videolink']) && isset($post['sectionlink'])) {
						$tmp_link = [];
						foreach ($post['sectionlink'] as $key => $sectionTitle) {
							if(isset($post['videolink'][$key]) && is_array($post['videolink'][$key])) {
								$tmp = [];
								$tmp['title'] = $sectionTitle;
								$tmp['data'] = [];
								foreach ($post['videolink'][$key] as $ki => $linkUrl) {
									if(!empty(trim($linkUrl)) && isset($post['videotext'][$key][$ki])) {
										$tmp['data'][] = [
											'type' => 'link',
											'name' => md5(random_string('alnum', 10)),
											'mask' => trim($linkUrl),
											'videotext' => $post['videotext'][$key][$ki],
											'description' => isset($post['description'][$key][$ki]) ? $post['description'][$key][$ki] : '',
											'zip' => [],
										];
									}
								}
								if(!empty($tmp['data'])) $tmp_link[] = $tmp;
							}
						}
						if(!empty($tmp_link)) {
							$downloadable_files = $tmp_link;
							$post['product_type'] = 'videolink';
						}
					}
				}

				// Set product_type to videolink when sub_product_type is videolink
				if(isset($post['product_type']) && $post['product_type'] == 'video' && !empty($post['sub_product_type']) && $post['sub_product_type'] == 'videolink') {
					$post['product_type'] = 'videolink';
				}

				$post['product_recursion_type'] = ($post['product_recursion_type']) ? $post['product_recursion_type'] : '';
				
				$details = array(
					'product_name'                 =>  $post['product_name'],
					'product_description'          =>  $post['product_description'],
					'product_short_description'    =>  $post['product_short_description'],
					'product_msrp'                =>  $post['product_msrp'],
					'product_price'                =>  $post['product_price'],
					'product_sku'                  =>  $post['product_sku'],
					'product_quantity'             =>  $post['product_quantity'],
					'product_video'                =>  $post['product_video'],
					'product_price'                =>  $post['product_price'],
					'product_type'                 =>  $post['product_type'],
					'state_id'                     =>  $post['allow_country'] == "1" ? (int)$post['state_id'] : 0,
					'product_commision_type'       =>  'default',
					'product_commision_value'      =>  0,
					'product_click_commision_type' =>  'default',
					'product_click_commision_ppc'  =>  0,
					'product_click_commision_per'  =>  0,
					'on_store'                     =>  (int)$post['on_store'],
					'allow_shipping'               =>  (int)$post['allow_shipping'],
					'allow_upload_file'            =>  (int)$post['allow_upload_file'],
					'allow_comment'                =>  (int)$post['allow_comment'],
					'product_status'               =>  isset($post['product_status']) ? (int)$post['product_status'] : 1,
					'product_ipaddress'            =>  $_SERVER['REMOTE_ADDR'],
					'product_recursion_type'       =>  $post['product_recursion_type'],
					'recursion_endtime'            =>  (isset($post['recursion_endtime_status']) && $post['recursion_endtime']) ? date("Y-m-d H:i:s",strtotime($post['recursion_endtime'])) : null,
					'product_recursion'            =>  $product_recursion,
					'recursion_custom_time'        =>  (int)$recursion_custom_time,
					'product_variations'        =>  json_encode($variations),
					'product_tags'        =>  json_encode($post['product_tags']),
					'product_weight'               =>  isset($post['product_weight']) ? (float)$post['product_weight'] : 0.00,
					'product_length'               =>  isset($post['product_length']) ? (float)$post['product_length'] : 0.00,
					'product_width'                =>  isset($post['product_width']) ? (float)$post['product_width'] : 0.00,
					'product_height'               =>  isset($post['product_height']) ? (float)$post['product_height'] : 0.00,
				);

				//product featured image s3/local code
				if(!empty($post['product_featured_image_s3'])) {
				    // An image was selected from S3
				    $details['product_featured_image'] = $post['product_featured_image_s3'];
				} else if(isset($_FILES['product_featured_image']) && $_FILES['product_featured_image']['error'] == 0) {
				    // A file was uploaded locally, attempt to upload
				    $upload_response = $this->upload_photo('product_featured_image', 'assets/images/product/upload/thumb');
				    if($upload_response['success']) {
				        // Upload successful, save the local file name
				        $details['product_featured_image'] = $upload_response['upload_data']['file_name'];
				    } else {
				        // Upload failed, save the error message
				        $errors['product_featured_image'] = $upload_response['msg'];
				    }
				} else if($product_id == 0) {
				    // No file was uploaded and no S3 image was selected, and this is a new product
				    $errors['product_featured_image'] = 'Select Featured Image File!';
				}
				//product featured image s3/local code



				if(!empty($_FILES['downloadable_file'])){

					$files = $_FILES['downloadable_file'];

					$count_file = count($_FILES['downloadable_file']['name'])/2;
					$keep_files_count =  isset($post['keep_files']) ?  count($post['keep_files']): 0 ;
					
					$this->load->helper('string');	

					for($i=0; $i<$count_file; $i++){

						$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
						if(!empty(trim($files['name'][$i]))){
							if($extension=='zip'){

								$FILES['downloadable_files']['name'] = md5(random_string('alnum', 10));

								$FILES['downloadable_files']['type'] = $files['type'][$i];

								$FILES['downloadable_files']['tmp_name'] = $files['tmp_name'][$i];

								$FILES['downloadable_files']['error'] = $files['error'][$i];

								$FILES['downloadable_files']['size'] = $files['size'][$i];    


								if(empty($FILES['downloadable_files']['error'])){

									move_uploaded_file($FILES['downloadable_files']['tmp_name'], APPPATH.'/downloads/'. $FILES['downloadable_files']['name']);

									if($post['product_type']=='video' || $post['sub_product_type']=='videolink') {
										$store_file_temp = [
											'type' => $FILES['downloadable_files']['type'],

											'name' => $FILES['downloadable_files']['name'],

											'mask' => $files['name'][$i]
										];

										if($post['product_type']=='video' && $post['sub_product_type'] !="videolink") {
											$store_file_temp['videotext'] = $post['videotext'][$keep_files_count+$i]; 
										} else {

										}
										$downloadable_files[] = $store_file_temp; 
									} else {

										$downloadable_files[] = array(

											'type' => $FILES['downloadable_files']['type'],

											'name' => $FILES['downloadable_files']['name'],

											'mask' => $files['name'][$i],

										);

									}
								}else{

									$errors['downloadable_files'] = $FILES['downloadable_files']['error'];

								}

							} else {

								$zip_name = md5(random_string('alnum', 10));

								if($post['product_type']=='video' || $post['sub_product_type']=='videolink') {

									$ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
									$fileName = md5(random_string('alnum', 10)).".$ext";

									move_uploaded_file($files['tmp_name'][$i], APPPATH.'/downloads/'. $fileName);

									$store_file_temp = [
										'type' => $files['type'][$i],

										'name' => $zip_name,

										'mask' => $fileName,

										'thumb' =>preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName).'.png',

									];
									if($post['product_type']=='video' && $post['sub_product_type'] !="videolink") {
										$store_file_temp['videotext'] = $post['videotext'][$keep_files_count+$i];
									}
									$downloadable_files[] = $store_file_temp; 
								} else {

									$downloadable_files[] = array(

										'type' => 'application/x-zip-compressed',

										'name' =>$zip_name,

										'mask' => preg_replace('/\\.[^.\\s]{3,4}$/', '', $files['name'][$i]).'.zip',

									);

								}
							}

						}
					}
				}

				// Process downloadable S3 files
				if (!empty($_POST['downloadable_file_s3'])) {
				    $s3Files = json_decode($_POST['downloadable_file_s3'], true);
				    
				    foreach ($s3Files as $s3file) {
				        // Generate a unique name for each file to prevent conflicts
				        $zip_name = md5(random_string('alnum', 10));

				        // Append S3 file details to the downloadable files list
				        $downloadable_files[] = [
				            'srctype' => 'AwsS3',
				            'name'    => $zip_name,
				            'url'     => $s3file['imageurl'],
				            'type'    => $s3file['type'],
				            'mask'    => $s3file['name'],
				        ];
				    }
				}

				$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$userdetails['id'])->first();
				$plan_product_count = ($product_id) ? $userPlan->plan->product : $userPlan->plan->product - 1;
				$vendor_product_count = $this->Product_model->countByField('product_affiliate','user_id',$userdetails['id']);
				if(isset($userPlan->plan->product) && $plan_product_count < $vendor_product_count)
					$errors['upgrade_membership_plan'] = __('user.reached_maximum_limit_package_upgrade').' <a href="'.base_url('usercontrol/purchase_plan').'">'.__('user.here').'</a>';

				$new_product_created = false;
				$old_product_data = [];


				if(!empty($_FILES['lms_videos_files'])){

					// Ensure downloads directory exists (same as admin)
					$downloadsDir = rtrim(APPPATH, '/\\') . DIRECTORY_SEPARATOR . 'downloads';
					if (!is_dir($downloadsDir)) {
						@mkdir($downloadsDir, 0755, true);
					}

					foreach ($_FILES['lms_videos_files']['name'] as $key => $value) {
						if(isset($_FILES['lms_videos_files']['name'][$key]) && !empty($_FILES['lms_videos_files']['name'][$key][0])) {
							$index = $key;
							for ($i=0; $i < count($_FILES['lms_videos_files']['name'][$key]); $i++) { 
								$ext = pathinfo($_FILES['lms_videos_files']['name'][$key][$i], PATHINFO_EXTENSION);
								$fileName = md5(random_string('alnum', 10)).".$ext";
								move_uploaded_file($_FILES['lms_videos_files']['tmp_name'][$key][$i], APPPATH.'/downloads/'. $fileName);
								if(!isset($downloadable_files[$index]) && $index!=0) {
									$index= (count($downloadable_files)-1) < $key ? $key : 0;
								}

								if(isset($post['keep_video_files']))
									$keepvidoefilescount=count($post['keep_video_files'][$index]);
								else 
									$keepvidoefilescount=0;
								
								$store_file_temp = [
									'type' => $_FILES['lms_videos_files']['type'][$key][$i],

									'name' => md5(random_string('alnum', 10)),

									'mask' => $fileName,

									'size' => format_filesize($_FILES['lms_videos_files']['size'][$key][$i]),

									'duration'=> $_POST['lms_videos_files_duration'][$key][$i],

									'videotext'=> $post['videotext'][$index][$keepvidoefilescount+$i],

									'description'=> $post['description'][$index][$keepvidoefilescount+$i] 
								];


								if(!empty($_FILES['lms_videos_files_zip']['name'][$key][$i])) {
									$ext = pathinfo($_FILES['lms_videos_files_zip']['name'][$key][$i], PATHINFO_EXTENSION);
									$fileName = md5(random_string('alnum', 10)).".$ext";
									move_uploaded_file($_FILES['lms_videos_files_zip']['tmp_name'][$key][$i], $downloadsDir . DIRECTORY_SEPARATOR . $fileName);

									$keepCount = isset($post['keep_video_files'][$index]) && is_array($post['keep_video_files'][$index]) ? count($post['keep_video_files'][$index]) : 0;
									$zipTitle = ($post['VideoFileResourceText'][$index] ?? [])[$keepCount + $i] ?? '';
									$store_file_temp['zip']= [
										'name'=> md5(random_string('alnum', 10)),
										'mask'=> $fileName,
										'title'=> $zipTitle,
										'type' => $_FILES['lms_videos_files_zip']['type'][$key][$i],
										'size' => format_filesize($_FILES['lms_videos_files_zip']['size'][$key][$i])
									];
								}
								$downloadable_files[$index]['data'][] = $store_file_temp;

							}
						}
						$downloadable_files[$key]['title'] = $post['section'][$key];

					}
				}

				if(!empty($_FILES['lms_videos_files_update'])){
					foreach ($_FILES['lms_videos_files_update']['name'] as $key => $value) {
						if(isset($_FILES['lms_videos_files_update']['name'][$key])) {
							foreach ($_FILES['lms_videos_files_update']['name'][$key] as $oldname => $newFile) {
								$ext = pathinfo($_FILES['lms_videos_files_update']['name'][$key][$oldname], PATHINFO_EXTENSION);
								$fileName = md5(random_string('alnum', 10)).".$ext";
								move_uploaded_file($_FILES['lms_videos_files_update']['tmp_name'][$key][$oldname], APPPATH.'/downloads/'. $fileName);
								foreach($downloadable_files[$key]['data'] as $dkey=>$datavalue) {
									if($datavalue['name'] == $oldname) {

										$downloadable_files[$key]['data'][$dkey]['name'] = md5(random_string('alnum', 10));
										$oldFileName = $downloadable_files[$key]['data'][$dkey]['mask']; 
										$downloadable_files[$key]['data'][$dkey]['mask'] = $fileName;
										$downloadable_files[$key]['data'][$dkey]['type'] = $_FILES['lms_videos_files_update']['type'][$key][$oldname];
										$downloadable_files[$key]['data'][$dkey]['size'] = format_filesize($_FILES['lms_videos_files_update']['size'][$key][$oldname]);
										$downloadable_files[$key]['data'][$dkey]['duration'] = $_POST['lms_videos_files_update_duration'][$key][$oldname];


										if(file_exists(APPPATH.'/downloads/'. $oldFileName)) {
											@unlink(APPPATH.'/downloads/'. $oldFileName);
										}
									}
								}
							}
						}
					}
				}
				// Only process lms_videos_files_zip_update when EDITING - for NEW videolink products
				// the form sends zip via lms_videos_files_zip_update[section][lessonIndex] but the
				// post['videolink'] block below handles it (avoiding double move + $_downloads undefined)
				if(!empty($_FILES['lms_videos_files_zip_update']) && $product_id){
					$downloadsDir = rtrim(APPPATH, '/\\') . DIRECTORY_SEPARATOR . 'downloads';
					if (!is_dir($downloadsDir)) {
						@mkdir($downloadsDir, 0755, true);
					}
					if(isset($_POST['sub_product_type']) && $_POST['sub_product_type']=='videolink'){
						$downloadable_files = $_downloads;
					}
					foreach ($_FILES['lms_videos_files_zip_update']['name'] as $key => $value) {
						if(isset($_FILES['lms_videos_files_zip_update']['name'][$key])) {
							foreach ($_FILES['lms_videos_files_zip_update']['name'][$key] as $oldname => $newFile) {
								$ext = pathinfo($_FILES['lms_videos_files_zip_update']['name'][$key][$oldname], PATHINFO_EXTENSION);
								$fileName = md5(random_string('alnum', 10)).".$ext";
								move_uploaded_file($_FILES['lms_videos_files_zip_update']['tmp_name'][$key][$oldname], $downloadsDir . DIRECTORY_SEPARATOR . $fileName);
								$isVideolink = (isset($_POST['sub_product_type']) && $_POST['sub_product_type'] == 'videolink');
								foreach($downloadable_files[$key]['data'] as $dkey=>$datavalue) {
									// Videolink form uses numeric index (data-old-name=innngerKey); video form uses lesson name (md5)
									$matches = $datavalue['name'] == $oldname || ($isVideolink && (string)$dkey === (string)$oldname);
									if($matches) {
										$downloadable_files[$key]['data'][$dkey]['zip']['name'] = md5(random_string('alnum', 10));
										$oldFileName = isset($downloadable_files[$key]['data'][$dkey]['zip']['mask']) ? $downloadable_files[$key]['data'][$dkey]['zip']['mask'] : '';
										$downloadable_files[$key]['data'][$dkey]['zip']['mask'] = $fileName;
										$downloadable_files[$key]['data'][$dkey]['zip']['type'] = $_FILES['lms_videos_files_zip_update']['type'][$key][$oldname];
										$downloadable_files[$key]['data'][$dkey]['zip']['size'] = format_filesize($_FILES['lms_videos_files_zip_update']['size'][$key][$oldname]);
										$downloadable_files[$key]['data'][$dkey]['zip']['title']= isset($post['VideoFileResourceText'][$key][$dkey]) ? $post['VideoFileResourceText'][$key][$dkey] : '';

										if($oldFileName && file_exists(APPPATH.'/downloads/'. $oldFileName)) {
											@unlink(APPPATH.'/downloads/'. $oldFileName);
										}
									} 
								}
							}
						}
					}
				}

				if(!empty($post['videolink'])) {
					$TmpDownloadable_files = $downloadable_files;
					$downloadable_files=[];
					$zipDir = rtrim(APPPATH, '/\\') . DIRECTORY_SEPARATOR . 'downloads';
					if (!is_dir($zipDir)) { @mkdir($zipDir, 0755, true); }
					foreach ($post['sectionlink'] as $key => $value) {
						$tmp['title'] = $value;
						foreach ($post['videolink'][$key] as $keyInner => $InnerValue) {
							if(!empty($post['videolink'][$key][$keyInner]) && !empty($post['videotext'][$key][$keyInner])) {
								$zip = isset($TmpDownloadable_files[$key]['data'][$keyInner]['zip']) ? $TmpDownloadable_files[$key]['data'][$keyInner]['zip'] : [];
								$tmpPath = null;
								$fileKey = null;
								// EDIT mode: lms_videos_files_zip_update block already moved the file above - use $zip from $TmpDownloadable_files
								$alreadyProcessedByLmsBlock = ($product_id && isset($_FILES['lms_videos_files_zip_update']['name'][$key][$keyInner]) && !empty($_FILES['lms_videos_files_zip_update']['name'][$key][$keyInner]));
								if (!$alreadyProcessedByLmsBlock) {
									if(isset($_FILES['lms_videos_files_zip_update']['name'][$key][$keyInner]) && !empty($_FILES['lms_videos_files_zip_update']['name'][$key][$keyInner])) {
										$tmpPath = $_FILES['lms_videos_files_zip_update']['tmp_name'][$key][$keyInner];
										$fileKey = 'lms_videos_files_zip_update';
									} elseif(isset($_FILES['lms_videos_files_zip']['name'][$key][$keyInner]) && !empty($_FILES['lms_videos_files_zip']['name'][$key][$keyInner])) {
										$tmpPath = $_FILES['lms_videos_files_zip']['tmp_name'][$key][$keyInner];
										$fileKey = 'lms_videos_files_zip';
									} elseif(isset($_FILES['VideoFileZip']['name'][$key][$keyInner]) && !empty($_FILES['VideoFileZip']['name'][$key][$keyInner])) {
										// Fallback: form native file input (VideoFileZip) when JS append not used
										$tmpPath = $_FILES['VideoFileZip']['tmp_name'][$key][$keyInner];
										$fileKey = 'VideoFileZip';
									}
								}
								if($fileKey && $tmpPath) {
									$ext = pathinfo($_FILES[$fileKey]['name'][$key][$keyInner], PATHINFO_EXTENSION);
									$fileName = md5(random_string('alnum', 10)).".$ext";
									$destPath = $zipDir . DIRECTORY_SEPARATOR . $fileName;
									$moved = move_uploaded_file($tmpPath, $destPath);
									if($moved && file_exists($destPath)) {
										$zip = [
											'name'=>md5(random_string('alnum', 10)),
											'mask'=>$fileName,
											'type' => $_FILES[$fileKey]['type'][$key][$keyInner],
											'size' => format_filesize($_FILES[$fileKey]['size'][$key][$keyInner]),
											'title'=> isset($post['VideoFileResourceText'][$key][$keyInner]) ? $post['VideoFileResourceText'][$key][$keyInner] : ''
										];
									} else {
										// Try copy as fallback when move_uploaded_file fails (e.g. is_uploaded_file edge cases)
										if(file_exists($tmpPath) && copy($tmpPath, $destPath)) {
											$zip = [
												'name'=>md5(random_string('alnum', 10)),
												'mask'=>$fileName,
												'type' => $_FILES[$fileKey]['type'][$key][$keyInner],
												'size' => format_filesize($_FILES[$fileKey]['size'][$key][$keyInner]),
												'title'=> isset($post['VideoFileResourceText'][$key][$keyInner]) ? $post['VideoFileResourceText'][$key][$keyInner] : ''
											];
										} else {
											$errors['lms_resource_zip'] = __('user.lesson_resource_not_received');
										}
									}
								}

								$tmp['data'][] = [
									'type' => 'link',

									'name' => (isset($TmpDownloadable_files[$key]['data'][$keyInner]['name']) ? $TmpDownloadable_files[$key]['data'][$keyInner]['name'] : md5(random_string('alnum', 10))),

									'mask' =>$post['videolink'][$key][$keyInner],

									'videotext'=> $post['videotext'][$key][$keyInner],

									'description'=> $post['description'][$key][$keyInner],

									'zip'=>$zip

								];
							}
						} 
						$downloadable_files[] = $tmp;
						$tmp=[];
					}
					$details['product_type'] = 'videolink';
				}

				if(empty($errors)){
					$details['downloadable_files'] = json_encode($downloadable_files);
					
					// Only set flash message for actions that redirect (not save_continue)
					$action = $this->input->post('action');
					if($action != 'save_continue'){
						$this->session->set_flashdata('success', __('user.product_added_successfully'));
					}

					$details['product_created_by'] = $userdetails['id'];
					$details['product_created_date'] = date('Y-m-d H:i:s');				

					$market_vendor = $this->Product_model->getSettings('market_vendor','marketaddnewstoreproduct');
					if($market_vendor['marketaddnewstoreproduct'])
						$details['product_status'] = 0;
					else 
						$details['product_status'] = 1;
					
					if($product_id){
						$old_product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();

						$this->Product_model->update_data('product', $details, array('product_id' => $product_id));
					}else{
						$product_id = $this->Product_model->create_data('product', $details);
						$new_product_created = true;

						$notificationData = array(
							'notification_url'          => 'updateproduct/'.$product_id,
							'notification_type'         =>  'vendor_product',
							'notification_title'        =>  __('user.new_product_added_by_vendor'),
							'notification_viewfor'      =>  'admin',
							'notification_actionID'     =>  $product_id,
							'notification_description'  =>  $post['product_name'].' product is addded by '. $userdetails['username'] .' in store on '.date('Y-m-d H:i:s'),
							'notification_is_read'      =>  '0',
							'notification_created_date' =>  date('Y-m-d H:i:s'),
							'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
						);

						$this->insertnotification($notificationData);
					}

					$seofilename = $this->friendly_seo_string($post['product_name']);
					$seofilename = strtolower($seofilename);
					$product_slug = $seofilename.'-'.$product_id;
					$this->db->query("UPDATE product SET product_slug = ". $this->db->escape($product_slug) ." WHERE product_id =". $product_id);

					if($product_id){
						$this->db->query("DELETE FROM product_categories WHERE product_id = {$product_id}");
						if(isset($post['category']) && is_array($post['category'])){
							foreach ($post['category'] as $category_id) {
								$category = array(
									'product_id' => $product_id,
									'category_id' => $category_id,
								);

								$this->Product_model->create_data('product_categories', $category);
							}
						}

						$admin_comment = '';
						if(isset($post['admin_comment']) && $post['admin_comment']){
							$admin_comment = $post['admin_comment'];
						}

						$seller_comm = [
							'affiliate_click_commission_type' => $post['affiliate_click_commission_type'],
							'affiliate_click_count'           => $post['affiliate_click_count'],
							'affiliate_click_amount'          => $post['affiliate_click_amount'],
							'affiliate_sale_commission_type'  => $post['affiliate_sale_commission_type'],
							'affiliate_commission_value'      => $post['affiliate_commission_value'],
						];

						$this->Product_model->assignToSeller($product_id, $details, $userdetails['id'], $admin_comment,'affiliate', $seller_comm);

						if(empty($market_vendor['marketaddnewstoreproduct']))
						{

						

							$vendor_setting = $this->Product_model->getSettings('vendor');
							$seller = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=".$product_id." ")->row();

							$seller_comm = [

								'admin_sale_commission_type'      => $post['admin_sale_commission_type'],

								'admin_commission_value'          => $post['admin_commission_value'],

								'admin_click_commission_type'     => ($seller->admin_click_commission_type) ? $seller->admin_click_commission_type : 'default',

								'admin_click_amount'              => $post['admin_click_amount'],

								'admin_click_count'               => $post['admin_click_count'],

							];


							$this->Product_model->assignToSeller($product_id, $details, $userdetails['id'], $admin_comment,'admin', $seller_comm);
						}

						$this->load->model('Mail_model');
						if($new_product_created){
							$this->Mail_model->vendor_create_product($product_id);
						} else {
							$product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();

							if($old_product_data['product_status'] != $product_data['product_status'] && $product_data['product_status'] == 0)
								$this->Mail_model->vendor_product_status_change($product_id, 'admin',true);
						}
					}		
	 	

					// Check if this is a save_continue action
					$action = $this->input->post('action');
					if($action != 'save_continue'){
						$json['location'] = base_url('usercontrol/store_products');
					} else {
						$json['success'] = true;
					}
				} else {
					$json['errors'] = $errors;
				}
			} else {
				$json['errors'] = $this->form_validation->error_array();

				if(isset($json['errors']['category[]'])){
					$json['errors']['category_auto'] = $json['errors']['category[]'];
				}
			}

			echo json_encode($json);die;
		}
	}


	public function productupload($id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('login'); }
		if(empty($id)){
			$this->session->set_flashdata('error', __('user.photo_can_not_be_uploaded'));
			redirect('usercontrol/store_products');
		}

		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		if(!empty($_FILES)){
			$errors= array();

			$details = array(
				'product_id'                        =>  $id,
				'product_media_upload_type'         =>  'image',
				'product_media_upload_status'       =>  1,
				'product_media_upload_os'           =>  $this->agent->platform(),
				'product_media_upload_browser'      =>  $this->agent->browser(),
				'product_media_upload_isp'          =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
				'product_media_upload_ipaddress'    =>  $_SERVER['REMOTE_ADDR'],
				'product_media_upload_created_by'   =>  $userdetails['id'],
				'product_media_upload_created_date' =>  date('Y-m-d H:i:s'),
			);

			$details['product_media_upload_created_by'] = $userdetails['id'];
			if(!empty($_FILES['product_multiple_image'])){
				$files = $_FILES;
				$cpt = count($_FILES['product_multiple_image']['name']);
				

				$this->load->helper('string');
				$config = array(
					'upload_path'   => 'assets/images/product/upload/',
					'allowed_types' => 'png|gif|jpeg|jpg|PNG|GIF|JPEG|JPG',
					'max_size'      => 2048,
					'file_name'     => random_string('alnum', 32),
				);

				$this->load->library('upload', $config);
				$this->load->library('image_lib');
				$this->upload->initialize($config);

				for($i=0; $i<$cpt; $i++){
					if($files['product_multiple_image']['error'][$i] == 0){
						$_FILES['product_multiple_images']['name'] = $files['product_multiple_image']['name'][$i];
						$_FILES['product_multiple_images']['type'] = $files['product_multiple_image']['type'][$i];
						$_FILES['product_multiple_images']['tmp_name'] = $files['product_multiple_image']['tmp_name'][$i];
						$_FILES['product_multiple_images']['error'] = $files['product_multiple_image']['error'][$i];
						$_FILES['product_multiple_images']['size'] = $files['product_multiple_image']['size'][$i];    
						
						$this->upload->do_upload('product_multiple_images');
						$upload_details = $this->upload->data();
						

						$config1 = array(
							'source_image'   => $upload_details['full_path'],
							'new_image'      => 'assets/images/product/upload/thumb',
							'maintain_ratio' => true,
							'width'          => 300,
							'dynamic_output' => 1,
							'height'         => 300
						);

						$this->image_lib->initialize($config1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						

						if($upload_details){
							$details['product_media_upload_path'] = $upload_details['file_name'];
						} else {
							$errors['avatar_error'] = $upload_details['msg'];
						}

						$details['product_media_upload_created_date'] = date('Y-m-d H:i:s');
						$this->Product_model->create_data('product_media_upload', $details);				

					}
				}
			}
			if(!empty($_POST['multiple_image_s3'])){

					$cpts3 = count(array_filter($_POST['multiple_image_s3']));
					if($cpts3 > 0){

						for($i=0; $i<$cpts3; $i++)
						{ 

							$details['product_media_upload_path'] = $_POST['multiple_image_s3'][$i];
							$details['product_media_upload_created_date'] = date('Y-m-d H:i:s');

							$this->Product_model->create_data('product_media_upload', $details);	
						}
					}
					
				}
			if(!empty($errors)){
				$this->session->set_flashdata('error', $errors['avatar_error']);
				redirect('usercontrol/productupload/'.$id);exit();
			}

			$this->session->set_flashdata('success', __('user.product_images_added_successfully'));
			redirect('usercontrol/productupload/'.$id);
		}

		$data['imageslist'] = $this->Product_model->getAllImages($id);
		$data['s3_setting'] = $this->Product_model->getSettings('s3_storage');
		$this->view($data,'store/productupload','usercontrol');
	}


	public function videoupload($id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect($this->admin_domain_url); }
		if(empty($id)){ redirect('usercontrol/store_products'); }

		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		$post = $this->input->post(null,true);

		if(!empty($post)){
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules('product_media_upload_video', __('user.product_video'), 'trim');
			if($this->form_validation->run())
			{
				$errors= array();
				$details = array(
					'product_id'                        => $id,
					'product_media_upload_path'         =>  $this->input->post('product_media_upload_path',true),
					'product_media_upload_type'         =>  'video',
					'product_media_upload_status'       =>  1,
					'product_media_upload_os'           =>  $this->agent->platform(),
					'product_media_upload_browser'      =>  $this->agent->browser(),
					'product_media_upload_isp'          =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
					'product_media_upload_ipaddress'    =>  $_SERVER['REMOTE_ADDR'],
					'product_media_upload_created_by'   =>  $userdetails['id'],
					'product_media_upload_created_date' =>  date('Y-m-d H:i:s'),
				);
				if(!empty($_FILES['video_thumbnail_image']['name'])){
					$upload_response = $this->upload_photo('video_thumbnail_image','assets/images/product/upload/thumb');
					if($upload_response['success']){
						$details['product_media_upload_video_image'] = $upload_response['upload_data']['file_name'];
					}
					else{
						$errors['avatar_error'] = $upload_response['msg'];
					}
				}
				if(!empty($errors)){
					$this->session->set_flashdata('error', $errors['avatar_error']);
					redirect('usercontrol/videoupload/'.$id);
					exit();
				}

				$this->session->set_flashdata('success', __('user.product_video_and_images_added_successfully'));
				$details['product_media_upload_created_by'] = $userdetails['id'];
				$details['product_media_upload_created_date'] = date('Y-m-d H:i:s');
				$this->Product_model->create_data('product_media_upload', $details);
				$data['productinfo'] = $this->Product_model->getProductByIdArray($id);

					redirect('usercontrol/videoupload/'.$id);
				} else {
					$this->session->set_flashdata('error', __('user.form_validation_error'));
					redirect('usercontrol/videoupload/'.$id);
				}

			} else {
				$data['videoimageslist'] = $this->Product_model->getAllVideoImages($id);
				$data['videoslist'] = $this->Product_model->getAllVideos($id);
				$data['user'] = $userdetails;
				

				$this->view($data,'store/videoupload','usercontrol');
			}
		}
		public function deleteAllproducts(){
			$post = $this->input->post(null,true);

			$vendor_setting = $this->Product_model->getSettings('vendor');
			if((int)$vendor_setting['storestatus'] == 0) show_404();
	 

			if(!empty($post['product']) || !empty($post['form'])){
				if(isset($post['product'])){
					foreach($post['product'] as $id){
	 
	 					if($id!='')
	 					{
	 						$orderProduct = $this->db->query('SELECT id FROM order_products WHERE product_id = '.$id)->row();
	 						if(empty($orderProduct)) {
							 	$this->Product_model->deleteproducts((int)$id);
							} else {
								$this->session->set_flashdata('error', __('user.some_order_product_not_deleted'));
							} 
	 					}
					}
				}
	 
				$this->session->set_flashdata('success', __('user.product_is_deleted_successfully'));
				redirect(base_url() . 'usercontrol/store_products');
			}
			else{
				$id = (int)$this->input->get('delete_id');
				$orderProduct = $this->db->query('SELECT id FROM order_products WHERE product_id = '.$id)->row();
				if(empty($orderProduct)) {
					$res = $this->Product_model->deleteproducts($id);
					$this->session->set_flashdata('success', __('user.product_is_deleted_successfully'));
				} else {
					$this->session->set_flashdata('error', __('user.order_product_not_deleted'));
				}
				redirect(base_url() . 'usercontrol/store_products');
			}

			$this->session->set_flashdata('error', __('user.product_delete_failed'));
			redirect(base_url() . 'usercontrol/store_products');
		}


	public function deleteALLSaleproducts(){
			$post = $this->input->post(null,true);

			$vendor_setting = $this->Product_model->getSettings('vendor');
			if((int)$vendor_setting['storestatus'] == 0) show_404();

			if(!empty($post['product']) || !empty($post['form'])){
				if(isset($post['product'])){
					foreach($post['product'] as $id){
						$orderProduct = $this->db->query('SELECT id FROM order_products WHERE product_id = '.$id)->row();

						if(empty($orderProduct)) {
							$this->Product_model->deleteproducts((int)$id);
						} else {
							$this->session->set_flashdata('error', __('user.some_order_product_not_deleted'));
						}
					}
				}

				$this->session->set_flashdata('success', __('user.product_is_deleted_successfully'));
				redirect(base_url() . 'usercontrol/sales_products');
			}
			else{
				$id = (int)$this->input->get('delete_id');
				$orderProduct = $this->db->query('SELECT id FROM order_products WHERE product_id = '.$id)->row();
				if(empty($orderProduct)) {
					$res = $this->Product_model->deleteproducts($id);
					$this->session->set_flashdata('success', __('user.product_is_deleted_successfully'));
				} else {
					$this->session->set_flashdata('error', __('user.order_product_not_deleted'));
				}
				redirect(base_url() . 'usercontrol/sales_products');
			}

			$this->session->set_flashdata('error', __('user.product_delete_failed'));
			redirect(base_url() . 'usercontrol/sales_products');
		}



		public function calc_commission(){
			$data = $this->input->post(null,true);
			$userdetails = $this->userdetails();

			$vendor_setting = $this->Product_model->getSettings('vendor');
			if((int)$vendor_setting['storestatus'] == 0) show_404();

			if (isset($data['product_id']) && (int)$data['product_id'] > 0) {
				$product = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$data['product_id'])->row();
				

				if($product){
					$data['admin_sale_commission_type']      = $product->admin_sale_commission_type;
					$data['admin_commission_value']          = $product->admin_commission_value;
					$data['admin_click_commission_type']     = $product->admin_click_commission_type;
					$data['admin_click_amount']              = $product->admin_click_amount;
					$data['admin_click_count']               = $product->admin_click_count;
					
				}
			} else {
				$data['admin_sale_commission_type']      = 'default';
				$data['admin_click_commission_type']     = 'default';
			}

			$setting = array(
				'product_id' => $data['product_id'],
				'product_price' => $data['product_price'],

				'admin_click_commission_type' => ($data['admin_click_commission_type'] != '' ? $data['admin_click_commission_type'] : 'default'),
				'admin_click_count'           => $data['admin_click_count'],
				'admin_click_amount'          => $data['admin_click_amount'],
				'admin_sale_commission_type'  => $data['admin_sale_commission_type'] != '' ? $data['admin_sale_commission_type'] : 'default',
				'admin_commission_value'      => $data['admin_commission_value'],

				'affiliate_click_commission_type' => $data['affiliate_click_commission_type'],
				'affiliate_click_count' => $data['affiliate_click_count'],
				'affiliate_click_amount' => $data['affiliate_click_amount'],
				'affiliate_sale_commission_type' => $data['affiliate_sale_commission_type'],
				'affiliate_commission_value' => $data['affiliate_commission_value'],
				'user_id' => (int)$userdetails['id'],
			);
	  
			$json['commission'] = $this->Product_model->calcVendorCommission($setting);
			$json['success'] = true;

			echo json_encode($json);
		}

		public function store_coupon_manage($coupon_id = 0){
			if(!$this->userdetails()){ redirect('/', 'refresh'); }
			$this->load->model("Coupon_model");
			$data['coupon'] = $this->Coupon_model->getCoupon($coupon_id);
			$data['product'] = $this->db->query("SELECT p.product_id,p.product_name FROM product p LEFT JOIN product_affiliate pa ON(pa.product_id = p.product_id) WHERE is_campaign_product=0 and pa.user_id = '".(int)$this->userdetails()['id']."'")->result_array();

			if(isset($data['coupon']['vendor_id']) && $data['coupon']['vendor_id'] != $this->userdetails()['id']){
				show_404();
			}
			$this->view($data,'store/coupon_form','usercontrol');
		}

		public function store_coupon_delete($coupon_id){
			if(!$this->userdetails()){ redirect('/', 'refresh'); }

			$this->load->model("Coupon_model");
			$this->Coupon_model->deleteCoupon($coupon_id);

			$this->session->set_flashdata('success', __('user.coupon_deleted_successfully'));
			

			redirect(base_url("usercontrol/store_coupon"));
		}
		public function store_coupon(){
			$userdetails = $this->userdetails();
			
			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$vendor_setting = $this->Product_model->getSettings('vendor');
			$store_setting = $this->Product_model->getSettings('store');
			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1) redirect('usercontrol/dashboard');

			if($store_setting['store_mode'] == 'sales') redirect('usercontrol/store_dashboard');

			$this->load->model("Coupon_model");
			$data['coupons'] = $this->Coupon_model->getCoupons((int)$userdetails['id']);
			$ptotal = $this->db->query('SELECT product_id FROM product WHERE is_campaign_product=0 and product_created_by='.$userdetails['id'])->num_rows();
			
			foreach ($data['coupons'] as $key => $value) {
				if(strtolower($value['allow_for']) == 's')
					$data['coupons'][$key]['product_count'] = count(explode(',', $value['products']));
				else
					$data['coupons'][$key]['product_count'] = $ptotal;

				$data['coupons'][$key]['count_coupon'] = $this->Coupon_model->getCouponCount($value['coupon_id']);
			}
			

			$this->view($data,'store/coupon_index','usercontrol');
		}

		public function store_dashboard(){
			$userdetails = $this->userdetails();

			if(empty($userdetails)) redirect('usercontrol/dashboard');
			
			$vendor_setting = $this->Product_model->getSettings('vendor');
			$store_setting = $this->Product_model->getSettings('store');
			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1) redirect('usercontrol/dashboard');

			$this->load->model('Total_model');
			$data['vendor_store_statistic'] = $this->Total_model->getVendorStoreStatistic($userdetails['id']);

			$this->view($data,'store/dashboard','usercontrol');
		}

		public function store_dashboard_order_list(){

			$userdetails = $this->userdetails();

			if(!$userdetails){ redirect('/', 'refresh'); }

			$get = $this->input->get(null,true);

			$post = $this->input->post(null,true);

			$filter = array(

				'vendor_id' => $userdetails['id'],

				'limit' => 50,

				'page' => isset($get['page']) ? (int)$get['page'] : 1,

			);

			$this->load->model('Order_model');

			$data['status'] = $this->Order_model->status();

			$getallorders = $this->Order_model->getOrders($filter);

			$data['orders'] = $getallorders['data'];

			$this->load->library('pagination');

			$this->pagination->cur_page = $filter['page'];

			$config['base_url'] = base_url('usercontrol/store_dashboard_order_list');

			$config['per_page'] = $filter['limit'];

			$config['total_rows'] = $getallorders['total'];

			$config['use_page_numbers'] = TRUE;

			$config['page_query_string'] = TRUE;

			$config['enable_query_strings'] = TRUE;

			$config['query_string_segment'] = 'page';

			$this->pagination->initialize($config);

			$data['pagination'] = $this->pagination->create_links();

			

			$json['view'] = $this->load->view("usercontrol/store/order_list_tr", $data, true);

			echo json_encode($json);
		}

		public function save_coupon(){
			if(!$this->userdetails()){ redirect('/', 'refresh'); }
			$this->load->library('form_validation');
			$data = $this->input->post(null,true);
			$json = array();

			$this->form_validation->set_rules('name', 'Name', 'required|trim');
			$this->form_validation->set_rules('code', 'Coupon Code', 'required|trim');
			$this->form_validation->set_rules('type', 'Type', 'required|trim');
			$this->form_validation->set_rules('allow_for', 'Allow For', 'required|trim');
			$this->form_validation->set_rules('discount', 'Discount', 'required|trim');
			$this->form_validation->set_rules('date_start', 'Start Date', 'required|trim');
			$this->form_validation->set_rules('date_end', 'End Date', 'required|trim');
			$this->form_validation->set_rules('status', 'Status', 'required|trim');
			
			if ($this->form_validation->run() == FALSE) {
				$json['errors'] = $this->form_validation->error_array();
			} else {

				//Custom validation for date comparison
		        $date_start = strtotime($data['date_start']);
		        $date_end = strtotime($data['date_end']);
		        
		        if ($date_start > $date_end) {
		            $json['errors'] = array("date_validation" => __("user.start_date_greater_error"));
		            echo json_encode($json);
		            die();
		        }
				
				if($data['allow_for']=='S' && $data['products']=="")
				{
					$json['errors']=array("select-product"=>__('user.please_select_at_least_one_product'));
					 echo json_encode($json);
					 die(); 
				}
	  
				if($data['allow_for']=='A')
				{
					$products = $this->db->query("SELECT p.product_id FROM product p LEFT JOIN product_affiliate pa ON(pa.product_id = p.product_id) WHERE is_campaign_product=0 and pa.user_id = '".(int)$this->userdetails()['id']."'")->result_array();

					$productStr='';
					foreach ($products as $product)
					{
						if($productStr!='')
							$productStr.=",";
						$productStr.=$product['product_id'];
					}
				}
				else
				{

					$productStr=implode(",", $data['products']);
				}

				$coupon = array(
					'vendor_id'  => $this->userdetails()['id'],
					'name'       => $data['name'],
					'code'       => $data['code'],
					'type'       => $data['type'],
					'allow_for'  => $data['allow_for'],
					'discount'   => $data['discount'],
					'date_start' => date("Y-m-d", strtotime($data['date_start'])),
					'date_end'   => date("Y-m-d", strtotime($data['date_end'])),
					'uses_total' => $data['uses_total'],
					'status'     => $data['status'],
					'products'   => $productStr,
					'date_added' => date("Y-m-d H:i:s"),
				);

				if($data['id'] > 0){
					unset($coupon['date_added']);
					$this->db->update("coupon",$coupon,['coupon_id' => $data['id']]);
				} else {
					$this->db->insert("coupon",$coupon);
					$coupon_id = $this->db->insert_id();
				}
				$json['location'] = base_url("usercontrol/store_coupon");
				$this->session->set_flashdata('success', __('user.coupon_saved_successfully'));
			}

			echo json_encode($json);
		}

		public function programs(){
			$userdetails = $this->userdetails();
			
			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$market_vendor['marketvendorstatus'] != 1)
				redirect('usercontrol/dashboard');
			
			$filter = [
				'vendor_id' => $userdetails['id'],
			];

			$data['programs'] = $this->IntegrationModel->getPrograms($filter);

			$this->view($data,'integration/programs','usercontrol');
		}

		public function programs_form($program_id = 0){
			$userdetails = $this->userdetails();
			if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();
			
			$data = array();
			if($program_id){
				$data['programs'] = $this->IntegrationModel->getProgramByID($program_id);
			}

			$data['CurrencySymbol'] = $this->currency->getSymbol();
			$data['market_vendor'] = $this->Product_model->getSettings('market_vendor');

			$this->view($data,'integration/programs_form','usercontrol');
		}

		public function delete_programs_form(){
			$userdetails = $this->userdetails();
			if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }
			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

			$program_id = (int)$this->input->post("id",true);
			$ads = $this->db->select("*")->from("integration_tools")->where("program_id",$program_id)->get()->num_rows();
			

			if($ads == 0){
				$this->db->query("DELETE FROM integration_programs WHERE id=". $program_id);
				$json['success'] = true;
			} else{
				$json['message'] = "There are {$ads} Integration Tools Assgin to This Program";
			}
			

			echo json_encode($json);
		}

		public function editProgram(){
		    $userdetails = $this->userdetails();
		    if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }
		    $market_vendor = $this->Product_model->getSettings('market_vendor');
		    if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		    $data = $this->input->post(null,true);

		    $this->form_validation->set_rules('name', 'Name', 'required|trim');
		    if($data['sale_status']){
		        $this->form_validation->set_rules('commission_type', 'Commission Type', 'required|trim');
		        $this->form_validation->set_rules('commission_sale', 'Sale Comission', 'required|trim|numeric|greater_than[0]');
		    }
		    if($data['click_status']){
		        $this->form_validation->set_rules('commission_number_of_click', 'Number of click', 'required|trim|numeric|greater_than[0]');
		        $this->form_validation->set_rules('commission_click_commission', 'Click Commission', 'required|trim|numeric|greater_than[0]');
		    }

		    if ($this->form_validation->run() == FALSE) {
		        $json['errors'] = $this->form_validation->error_array();
		    } else {
		        $program_id = (int)$data['program_id'];

		        $program_id = $this->IntegrationModel->editProgram($data,$program_id,'user',$userdetails['id']);
		        if($program_id){

		            $this->session->set_flashdata('success', __('user.program_saved_successfully'));

		            if(isset($data['add_program_to_form'])){
		                $market_vendor = $this->Product_model->getSettings('market_vendor','marketaddnewprogram');
		                if($market_vendor['marketaddnewprogram']){
		                    $json['message'] = __('user.vendor_program_send_to_review');
		                } else {
		                    $json['message'] = __('user.vendor_program_approved');
		                    
		                    $program = $this->IntegrationModel->getProgramByID($program_id);
		                    $program['commission_sale'] = ($program['commission_type'] == 'fixed') ? c_format($program['commission_sale']) : (int) $program['commission_sale']."%";
		                    $json['newOption'] = '<option 
		                    data-commission_type="'.$program['commission_type'].'"
		                    data-commission_sale="'.$program['commission_sale'].'"
		                    data-commission_number_of_click="'.$program['commission_number_of_click'].'"
		                    data-commission_click_commission="'.c_format($program['commission_click_commission']).'"
		                    data-click_status="'.$program['click_status'].'"
		                    data-sale_status="'.$program['sale_status'].'"
		                    value="'.$program['id'].'">'.$program['name'].'
		                    </option>';
		                }
		            } else {
		                $json['location'] = base_url("usercontrol/programs");
		            }
		        } else {
		            $json['errors']['name'] = "Something Wrong";
		        }
		    }

		    echo json_encode($json);
		}

		public function integration_tools($page= 1){
			$userdetails = $this->userdetails();

			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$market_vendor['marketvendorstatus'] != 1)
				redirect('usercontrol/dashboard');

			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				$post = $this->input->post(null,true);
				$get = $this->input->get(null,true);
				$filter = array(
					'page' => isset($get['page']) ? $get['page'] : $page,
					'limitdata' => 25,
					'vendor_id' => $userdetails['id'],
				);


				$filter['userdetails'] = $userdetails;
	 
				if ($market_vendor['marketvendorstatus'] == 1) {
					$filter['marketvendorstatus'] = 1;
				 }else{
				 	$filter['marketvendorstatus'] = 0;
				 }

				if(isset($post['category_id']))
					$filter['category_id'] = $post['category_id'];
				
				if(isset($post['ads_name']))
					$filter['ads_name'] = $post['ads_name'];
				
				if(isset($post['status']))
					$filter['status'] = $post['status'];
				 
				$json = array();
				list($data['tools'],$total) = $this->IntegrationModel->getProgramTools($filter);
				$data['integration_plugins'] = modules_list();	

				if($post['paginate']){
					$this->load->library('pagination');
					$this->pagination->cur_page = $filter['page'];
					$config['base_url'] = base_url('usercontrol/integration_tools');
					$config['per_page'] = $filter['limitdata'];
					$config['total_rows'] = $total;
					$config['use_page_numbers'] = TRUE;
					$config['page_query_string'] = TRUE;
					$config['enable_query_strings'] = TRUE;
					$_GET['page'] = $filter['page'];
					$config['query_string_segment'] = 'page';
					$this->pagination->initialize($config);
					$json = $this->pagination->create_links();
				} else {
					$json = $this->load->view("usercontrol/integration_tools/integration_tools_list", $data, true);
				}	

				echo $json;
				die;
			}
	 

			$data['categories'] = $this->db->query("SELECT DISTINCT integration_category.id  as value ,integration_category.name as label, CASE WHEN integration_category.parent_id=0 THEN integration_category.id ELSE integration_category.parent_id END AS pid FROM `integration_category`
				 inner JOIN integration_tools on integration_tools.category=	 integration_category.id 
			 order by pid,integration_category.id")->result_array();

			$this->load->library("socialshare");				
			$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

			$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$userdetails['id'])->first();
			$vendor_campaign_count = $this->Product_model->countByField('integration_tools','vendor_id',$userdetails['id']);
			if(isset($userPlan->plan->campaign) && $userPlan->plan->campaign <= $vendor_campaign_count){
				$this->load->helper('cookie');
				$cookie = get_cookie('campaign_count_alert_'.$userdetails['id']);
				$data['campaign_count_alert'] = __('user.reached_maximum_limit_package_upgrade').' <a href="'.base_url('usercontrol/purchase_plan').'">'.__('user.here').'</a>';
			}

			$this->view($data,'integration_tools/integration_tools','usercontrol');
		}

		public function getIntegrationMlmInfo(){
			if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

			$html = '';

			$tool = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));
			if($tool){
				$data['tool']['commission_type'] = $tool['commission_type'];
				$data['CurrencySymbol'] = $this->currency->getSymbol();
				if($tool['commission_type'] == 'custom'){
					$setting = $this->Product_model->getVendorSettings($tool['vendor_id'], 'referlevel');
					
					$data['tool']['referlevel'] = $tool['commission']['referlevel'];
					$data['tool']['referlevel']['levels'] = ($tool['commission']['referlevel']['levels']) ? $tool['commission']['referlevel']['levels'] : (isset($setting['levels']) ? (int)$setting['levels'] : 3);

					for ($i=1; $i <= $data['tool']['referlevel']['levels']; $i++) { 
						$data['tool']['referlevel_'. $i] = $tool['commission']['referlevel_'. $i];
					}
				} else {
					$commonSetting = array('referlevel','referlevel_1','referlevel_2','referlevel_3','referlevel_4','referlevel_5','referlevel_6','referlevel_7','referlevel_8','referlevel_9','referlevel_10','referlevel_11','referlevel_12','referlevel_13','referlevel_14','referlevel_15','referlevel_16','referlevel_17','referlevel_18','referlevel_19','referlevel_20','referlevel');

					foreach($commonSetting as $key => $value){
						$data['tool'][$value] 	= $this->Product_model->getVendorSettings($tool['vendor_id'], $value);
					}
				}

				$html = $this->load->view('usercontrol/integration_tools/integration_mlm_info',$data,true);
			}

			echo $html;
			die;
		}

		public function integration_terms_modal(){
			if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }

			$data['terms_data'] = $this->IntegrationModel->getTermsToolsByID((int)$this->input->post('id',true));
			
			$json['html'] = $this->load->view('admincontrol/integration/integration_terms_modal', $data, true);

			echo json_encode($json);die;
		}

		public function get_plugin_instructions_for_modal($module_key, $toolsname){
			if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

			$data['integration_modules'] = $this->modules_list();

			$data['module_key'] = $module_key;
			$data['toolsname'] = $toolsname;
			
			$data['action_codes'] = $this->db->select('integration_tools.action_code')
			->from('integration_tools')
			->where("tool_type",'action')
			->where("status",1)
			->get()
			->result_array();

			$data['general_codes'] = $this->db->select('integration_tools.general_code')
			->from('integration_tools')
			->where("tool_type",'general_click')
			->where("status",1)
			->get()
			->result_array();

			$data['module'] = $data['integration_modules'][$module_key];

			$data['views'] = '';
			
			$this->load->model('PagebuilderModel');

			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			
			$data['customField'] = json_decode($register_form['registration_builder'],1);

			return $this->load->view('admincontrol/integration/instructions', $data, TRUE);
		}


		public function integration_code_modal(){
			if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

			$data['action_code'] = 'action_code';
			$data['general_code'] = 'general_code';

			$tools = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));
			if($tools){
				

				$data['name'] = $tools['name'];
				$data['target_link'] = $tools['target_link'];
				$data['tool_type'] = $tools['tool_type'];
				if($tools['tool_type'] == 'action'){
					$data['action_code'] = $tools['action_code'];
				}
				if($tools['tool_type'] == 'general_click'){
					$data['general_code'] = $tools['general_code'];
				}
			}


		$json['tool'] = $data['tool'] = $tools;

		$skipNewViewFor = ['general_integration', 'laravel', 'cakephp', 'codeigniter'];

		if($tools['tool_type'] == 'program' && !empty($tools['tool_integration_plugin']) && !in_array($tools['tool_integration_plugin'], $skipNewViewFor)){
			$data['tool_integration_plugin_html'] = $this->get_plugin_instructions_for_modal($tools['tool_integration_plugin'], $tools['name']);
		}

		if($tools['tool_type'] == 'program' && !empty($tools['tool_integration_plugin']) && $tools['tool_integration_plugin'] == 'stripe'){
			$data['tool_integration_plugin_html'] = $this->load->view('usercontrol/integration_tools/stripe_integration_instructions', $data, true);
		}


		$data['integration_plugins'] = modules_list();

		$json['html'] = $this->load->view('usercontrol/integration/integration_code_modal', $data, true);

		echo json_encode($json);die;
		}

		public function integration_tools_form($type="banner", $tools_id = 0){
			$userdetails = $this->userdetails();
			if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();
			
			$setting = $this->Product_model->getVendorSettings($userdetails['id'],'referlevel');
			$data['levels'] = isset($setting['levels']) ? (int)$setting['levels'] : 3;

			if($tools_id){
				$data['tool'] = $this->IntegrationModel->getProgramToolsByID($tools_id);
				$category_ids = explode(",", $data['tool']['category']);
				if(count(array_filter($category_ids)) > 0){
					$data['categories'] = $this->db->query("SELECT id as value,name as label FROM integration_category WHERE id IN (". implode(",", $category_ids) .") ")->result_array();
				}
				
				$data['referlevel'] = $data['tool']['commission']['referlevel'];
				
				$data['levels'] = ($data['tool']['commission']['referlevel']['levels']) ? $data['tool']['commission']['referlevel']['levels'] : $data['levels'];
				for ($i=1; $i <= $data['levels']; $i++) { 
					$data['referlevel_'. $i] = $data['tool']['commission']['referlevel_'. $i];
				}
			}

			$commonSetting = array('referlevel','referlevel_1','referlevel_2','referlevel_3','referlevel_4','referlevel_5','referlevel_6','referlevel_7','referlevel_8','referlevel_9','referlevel_10','referlevel_11','referlevel_12','referlevel_13','referlevel_14','referlevel_15','referlevel_16','referlevel_17','referlevel_18','referlevel_19','referlevel_20','referlevel');
			foreach($commonSetting as $key => $value){
				$data['default'][$value] 	= $this->Product_model->getVendorSettings($userdetails['id'], $value);
			}

			$data['default_marketpostback'] = $this->Product_model->getSettings('marketpostback');
			$data['programs'] = $this->IntegrationModel->getPrograms(['vendor_id' => $userdetails['id'],'status' => 1]);
			$data['market_vendor'] = $this->Product_model->getSettings('market_vendor');
			$data['type'] = $type;
			$data['CurrencySymbol'] = $this->currency->getSymbol();
			$data['users'] = $this->db->query("SELECT username as name,id FROM users WHERE type='user'")->result_array();
			$data['integration_plugins'] = modules_list();
			$data['randome_code'] = generateRandomAlpahaNemericCode();

			$this->view($data,'integration_tools/integration_tools_form','usercontrol');
		}

		function valid_url_custom($url) {
			if(filter_var($url, FILTER_VALIDATE_URL)){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}

public function integration_tools_form_post() {
	
    $userdetails = $this->userdetails();
    if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

    $market_vendor = $this->Product_model->getSettings('market_vendor');
    if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

    $data = $this->input->post(null, true);

	switch($data['tool_period']) {
		case '1': // Always running
			$data['start_date'] = null;
			$data['end_date'] = null;
			break;

		case '2': // From today to custom end date
			$data['start_date'] = null;
			$data['end_date'] = !empty($data['end_date']) ? date('Y-m-d H:i:s', strtotime($data['end_date'])) : null;
			break;

		case '3': // From custom start date to lifetime
			$data['start_date'] = !empty($data['start_date']) ? date('Y-m-d H:i:s', strtotime($data['start_date'])) : null;
			$data['end_date'] = null;
			break;

		case '4': // Custom period
		default:
			$data['start_date'] = !empty($data['start_date']) ? date('Y-m-d H:i:s', strtotime($data['start_date'])) : null;
			$data['end_date'] = !empty($data['end_date']) ? date('Y-m-d H:i:s', strtotime($data['end_date'])) : null;
			break;
	}


    $program_tool_id = isset($data['program_tool_id']) ? (int)$data['program_tool_id'] : 0;

    $is_stripe = isset($data['tool_integration_plugin']) && $data['tool_integration_plugin'] == 'stripe';
    if(!$is_stripe){
        $this->form_validation->set_rules('target_link', 'Target Link', 'callback_valid_url_custom');
    }
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    $this->form_validation->set_rules('type', 'Type', 'required|trim');
    $this->form_validation->set_rules('tool_type', 'Tool Type', 'required|trim');

    if($data['tool_period'] == 2){
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
    } else if($data['tool_period'] == 3){
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
    } else if($data['tool_period'] == 4){ 
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
    }

    if($data['tool_type'] == 'action'){
        $this->form_validation->set_rules('action_click', 'Action Click', 'required|trim');
        $this->form_validation->set_rules('action_amount', 'Action Amount', 'required|trim');
        $this->form_validation->set_rules('action_code', 'Action Code', 'required|trim');
        $data['program_id'] = 0;
    } else if($data['tool_type'] == 'general_click'){
        $this->form_validation->set_rules('general_click', 'General Click', 'required|trim');
        $this->form_validation->set_rules('general_amount', 'General Amount', 'required|trim');
        $this->form_validation->set_rules('general_code', 'General Code', 'required|trim');
        $data['program_id'] = 0;
    } else if($data['tool_type'] == 'program'){
        $this->form_validation->set_rules('program_id', 'Program', 'required|trim');
        $this->form_validation->set_rules('tool_integration_plugin', 'Integration Plugin', 'required|trim');
    }

    if($data['type'] == 'text_ads'){
        $this->form_validation->set_rules('text_ads_content', 'Ads Content', 'required|trim');
        $this->form_validation->set_rules('text_color', 'Color', 'required|trim');
        $this->form_validation->set_rules('text_bg_color', 'Background color', 'required|trim');
        $this->form_validation->set_rules('text_border_color', 'Border color', 'required|trim');
        $this->form_validation->set_rules('text_size', 'Border color', 'required|trim');
    } else if($data['type'] == 'link_ads'){
        $this->form_validation->set_rules('link_title', 'Link Title', 'required|trim');
    } else if($data['type'] == 'video_ads'){
        $video_source_type = isset($data['video_source_type']) ? $data['video_source_type'] : 'youtube_vimeo';
        if($video_source_type == 'youtube_vimeo'){
        $this->form_validation->set_rules('video_link', 'Video Link', 'required|trim');
        } else if($video_source_type == 'mp4_url'){
            $this->form_validation->set_rules('mp4_url', 'MP4 URL', 'required|trim');
        }
        $this->form_validation->set_rules('button_text', 'Video Button Text', 'required|trim');
        $this->form_validation->set_rules('video_height', 'Video Height', 'required|trim');
        $this->form_validation->set_rules('video_width', 'Video Width', 'required|trim');
    }
    $this->form_validation->set_message('valid_url_custom', 'Enter a valid URL.');

    if ($data['recursion'] == 'custom_time') {
        $this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');
    }

    if ($this->form_validation->run() == FALSE) {
        $json['errors'] = $this->form_validation->error_array();
    } else {
        if($data['tool_type'] == 'program' && isset($data['tool_integration_plugin']) && $data['tool_integration_plugin'] == 'stripe'){
            $stripe_price = isset($data['stripe_price']) ? floatval($data['stripe_price']) : 0;
            $stripe_currency = isset($data['stripe_currency']) ? strtoupper(trim($data['stripe_currency'])) : 'USD';
            
            if(empty($stripe_price) || $stripe_price <= 0){
                $json['errors']['stripe_price'] = 'Price is required for Stripe campaigns';
            } else {
            $existing_commission = !empty($data['commission']) ? json_decode($data['commission'], true) : [];
            $data['commission'] = json_encode(array_merge($existing_commission, [
                'stripe_price' => $stripe_price,
                'stripe_currency' => $stripe_currency
            ]));
            $data['target_link'] = '#';
            }
        }

        // Process postback settings
        if (isset($data['marketpostback'])) {
            $marketpostback = is_array($data['marketpostback']) ? $data['marketpostback'] : json_decode($data['marketpostback'], true);

            if (isset($marketpostback['status']) && $marketpostback['status'] === 'custom') {
                // Validate URL
                if (!empty($marketpostback['url']) && !filter_var($marketpostback['url'], FILTER_VALIDATE_URL)) {
                    $json['errors']['marketpostback-url'] = 'Enter a valid postback URL';
                }

                // Validate integration type
                if (isset($marketpostback['integration_type'])) {
                    $valid_types = ['addClick', 'addClick?actionCode', 'addOrder', 'addClick?product_id'];
                    if (!in_array($marketpostback['integration_type'], $valid_types)) {
                        $json['errors']['integration_type'] = 'Invalid integration type';
                    }
                }

                // Enable security_status if custom postback is enabled
                $data['security_status'] = 2;
            } else {
                // Disable security_status if postback is not custom
                $data['security_status'] = 0;
            }

            // Filter dynamic and static parameters
            $marketpostback['dynamicparam'] = isset($marketpostback['dynamicparam']) ? array_filter($marketpostback['dynamicparam']) : [];
            $marketpostback['static'] = isset($marketpostback['static']) ? array_values(array_filter($marketpostback['static'], function($v) { 
                return !empty($v['key']) && !empty($v['value']); 
            })) : [];
            
            $data['marketpostback'] = json_encode($marketpostback);
        } else {
            $data['marketpostback'] = json_encode(['status' => '']);
            $data['security_status'] = 0;
        }

        // Set featured image based on tool type and integration plugin
        $featured_image = getDefaultCampaignImageByTool($data['tool_type'], $data['tool_integration_plugin'] ?? null);
        $data['deafult_featured_image'] = $featured_image;

        // Validate vendor plan limits
        $userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id', $userdetails['id'])->first();
        $plan_campaign_count = ($program_tool_id) ? $userPlan->plan->campaign : $userPlan->plan->campaign - 1;
        $vendor_campaign_count = $this->Product_model->countByField('integration_tools', 'vendor_id', $userdetails['id']);
        
        if(isset($userPlan->plan->campaign) && $plan_campaign_count < $vendor_campaign_count) {
            $json['error'] = __('user.reached_maximum_limit_package_upgrade').' <a href="'.base_url('usercontrol/purchase_plan').'">'.__('user.here').'</a>';
        }

        if(empty($json['errors']) && empty($json['error'])) {
            // Process file upload for featured image
            $data['featured_image'] = $data['old_featured_image'];
            if(!empty($_FILES['featured_image']['name'])) {
                $upload_response = $this->Product_model->upload_photo('featured_image', 'assets/images/product/upload/thumb');
                if($upload_response['success']){
                    $data['featured_image'] = $upload_response['upload_data']['file_name'];
                }
            } else if(empty($data['featured_image'])) {
                copy('assets/images/plugins_icons/'.$featured_image, 'assets/images/product/upload/thumb/'.$featured_image);
                $data['featured_image'] = $featured_image;
            }

            if($data['type'] == 'video_ads' && isset($data['video_source_type']) && $data['video_source_type'] == 'mp4_upload'){
                if(!empty($_FILES['video_file']['name'])){
                    $upload_path = 'assets/integration/uploads/videos/';
                    if(!is_dir($upload_path)) mkdir($upload_path, 0777, true);
                    
                    $config['upload_path'] = $upload_path;
                    $config['allowed_types'] = 'mp4|webm|ogg|mov|avi';
                    $config['max_size'] = 51200;
                    $this->load->helper('string');
                    $config['file_name'] = random_string('alnum', 32);
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    
                    if($this->upload->do_upload('video_file')){
                        $upload_data = $this->upload->data();
                        $data['uploaded_video_path'] = base_url($upload_path . $upload_data['file_name']);
                    } else {
                        $json['errors']['video_file'] = $this->upload->display_errors('', '');
                    }
                }
            }

            if (isset($data['integration_method'])) {
                switch ($data['integration_method']) {
                    case 's2s':
                        $data['s2s_enabled'] = 1;
                        $data['s2s_direct_mode'] = 0;
                        break;
                    case 's2s_direct':
                        $data['s2s_enabled'] = 1;
                        $data['s2s_direct_mode'] = 1;
                        break;
                    default:
                        if (!isset($data['s2s_enabled'])) $data['s2s_enabled'] = 0;
                        if (!isset($data['s2s_direct_mode'])) $data['s2s_direct_mode'] = 0;
                        break;
                }
            }

            $program_tool_id = $this->IntegrationModel->editProgramTools($data, $_FILES['custom_banner'], 'vendor', $userdetails['id']);

            if($program_tool_id) {
                $this->session->set_flashdata('success', __('user.campaign_saved_successfully'));
                if(isset($data['save_close'])) {
                    $json['location'] = base_url("usercontrol/integration_tools_form/". $data['type'] ."/". $program_tool_id);
                } else {
                    $json['location'] = base_url("usercontrol/integration_tools");
                }
            } else {
                $json['errors']['name'] = "Something went wrong";
            }
        }
    }

    echo json_encode($json);
}

		public function integration_tools_delete($tools_id){
			$userdetails = $this->userdetails();
			if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }
			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();
			$this->IntegrationModel->deleteTools($tools_id);
			$this->session->set_flashdata('success', __('user.campaign_deleted_successfully'));
			redirect(base_url("usercontrol/integration_tools"));
		}

		public function tool_get_code($control = 'usercontrol'){
			$tools_id = (int)$this->input->post("id",true);
			if($control == 'usercontrol'){
				if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }
				$data['user_id'] = $this->userdetails()['id'];
			}
			else if($control == 'usercontrol'){
				if(!$this->userlogins()){ redirect('usercontrol/dashboard', 'refresh'); }
				$data['user_id'] = $this->userlogins()['id'];
			}
			

			$data['tool'] = $this->IntegrationModel->getProgramToolsByID($tools_id);
			$json = array();
			$ud = $this->userdetails();
			if ($data['tool'] && !$this->Product_model->user_can_promote_market_campaign($ud, $data['tool'])) {
				$json['error'] = __('user.market_slug_locked');
				$json['html'] = '';
			} elseif($data['tool']){
				$json['html'] = $this->load->view("integration/code", $data, true);
			}
			

			echo json_encode($json);die;
		}

		public function integration_category_auto(){
			$userdetails = $this->userdetails();
			if(!$this->userdetails()){ redirect('/', 'refresh'); }
			$keyword = $this->input->get('query');
			

			$data = $this->db->query("SELECT integration_category.id as value,integration_category.name as label, CASE WHEN integration_category.parent_id=0 THEN integration_category.id ELSE integration_category.parent_id END AS pid FROM integration_category WHERE integration_category.name  like ". $this->db->escape("%".$keyword."%") ." order by pid,integration_category.id")->result_array();
			 
			echo json_encode($data);die;
		}

		public function integration_tools_duplicate($tools_id){
			$userdetails = $this->userdetails();
			
			$userPlan = App\MembershipUser::with("plan")->where('is_active',1)->where('user_id',$userdetails['id'])->first();

			$plan_campaign_count = ($userPlan && !empty($userPlan->plan)) ? $userPlan->plan->campaign : null;
			$vendor_campaign_count = $this->Product_model->countByField('integration_tools','vendor_id',$userdetails['id']);
			
			if(! empty($plan_campaign_count) && $plan_campaign_count <= $vendor_campaign_count){
				$_SESSION['error'] = __('user.reached_maximum_limit_package_upgrade').'<a href="'.base_url('usercontrol/purchase_plan').'"> '.__('user.here').'</a>';
			}else{
				$this->IntegrationModel->duplicate_tools($tools_id);
				$this->session->set_flashdata('success', __('user.add_duplicate_campaign_successfully'));
			}

			redirect(base_url('usercontrol/integration_tools'));
		}

		public function integration_code_modal_new(){
			$userdetails = $this->userdetails();
			if(!$userdetails){ redirect('/', 'refresh'); }

			$data['action_code'] = 'action_code';
			$data['single_action'] = 'single_action';
			$data['general_code'] = 'general_code';

			$tools = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));
			if($tools){
				
				$data['name'] = $tools['name'];
				$data['target_link'] = $tools['target_link'];
				$data['tool_type'] = $tools['tool_type'];
				
				if($tools['tool_type'] == 'action'){
					$data['action_code'] = $tools['action_code'];
				}
				if($tools['tool_type'] == 'single_action'){
					$data['action_code'] = $tools['action_code'];
				}
				if($tools['tool_type'] == 'general_click'){
					$data['general_code'] = $tools['general_code'];
				}
			}

		$json['tool'] = $data['tool'] = $tools;

		$skipNewViewFor = ['general_integration', 'laravel', 'cakephp', 'codeigniter'];

		if($tools['tool_type'] == 'program' && !empty($tools['tool_integration_plugin']) && !in_array($tools['tool_integration_plugin'], $skipNewViewFor)){
			$data['tool_integration_plugin_html'] = $this->get_plugin_instructions_for_modal($tools['tool_integration_plugin'], $tools['name']);
		}

		if($tools['tool_type'] == 'program' && !empty($tools['tool_integration_plugin']) && $tools['tool_integration_plugin'] == 'stripe'){
			$data['tool_integration_plugin_html'] = $this->load->view('usercontrol/integration_tools/stripe_integration_instructions', $data, true);
		}

		$data['integration_plugins'] = modules_list();

		$json['html'] = $this->load->view('usercontrol/integration/integration_code_modal', $data, true);

		echo json_encode($json);die;
		}

		public function integration_setup_modal(){
			$userdetails = $this->userdetails();
			if(!$userdetails){ redirect('/', 'refresh'); }

			$tools = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));
			if(!$tools || (isset($tools['vendor_id']) && $tools['vendor_id'] != $userdetails['id'])){
				echo '<div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-body text-center py-4 text-danger"><i class="bi bi-exclamation-triangle fs-1"></i><p class="mt-2">'.__('admin.campaign_not_found').'</p></div></div></div>';
				exit;
			}

			$data['tool'] = $tools;
			$data['method'] = isset($tools['integration_method']) ? $tools['integration_method'] : 'js_pixel';
			echo $this->load->view('admincontrol/integration/integration_setup_modal', $data, true);
			exit;
		}

		public function get_withdrawal_modal(){
			$userdetails = $this->userdetails();
			if(!$userdetails){ redirect('/', 'refresh'); }

			$site_setting = $this->Product_model->getSettings('site');
			
			$ids = $this->input->post("ids",true);

			if($ids==""){
				
				$data['danger'] =  __('user.please_select_at_least_one_wallet_record');
				$json['html'] = $this->load->view('usercontrol/users/parts/withdrawal_modal', $data, true);
				echo json_encode($json);die;
			}
			
			
			$data['ids']=$ids; 
			
			if($userdetails['is_vendor']){
				if($data['ids'] == 'all') {
					$transaction_total = $this->db->query("SELECT SUM(amount) total FROM wallet WHERE status = 1 AND amount > 0 AND user_id=".(int)$userdetails['id']  )->row()->total;
				} else {
					$transaction_total = $this->db->query("SELECT SUM(amount) total FROM wallet WHERE id IN (". $data['ids'] .") ")->row()->total;
				}
				
			} else {
				if($data['ids'] == 'all'){
					$transaction_total = $this->db->query("SELECT SUM(amount) total FROM wallet WHERE status = 1 AND user_id=".(int)$userdetails['id'])->row()->total;
				}else{
					$transaction_total = $this->db->query("SELECT SUM(amount) total FROM wallet WHERE id IN (". $data['ids'] .") ")->row()->total;
				}
			}
					
			if(isset($site_setting['wallet_max_amount']) && $site_setting['wallet_max_amount']>0 && (float)$transaction_total> (float)$site_setting['wallet_max_amount'])
			{
				$newwarningmessage=__('user.the_maximum_withdrawal_limit_is').": ".c_format($site_setting['wallet_max_amount']);
				$data['danger'] = $newwarningmessage;
			}
			else if( (float)$transaction_total >= (float)$site_setting['wallet_min_amount']){
				$this->load->model('Withdrawal_payment_model');
				$data['payment_methods'] = $this->Withdrawal_payment_model->getEnabledPaymentMethods([
					'get_user_setting' => true,
					'user_id' => $userdetails['id'],
				]);
				$data['transaction_total'] = $transaction_total;
			} else{

				if(isset($site_setting['wallet_min_message_new']) && $site_setting['wallet_min_message_new']!='')
					$newwarningmessage=$site_setting['wallet_min_message_new'].": ".c_format($site_setting['wallet_min_amount']);
				else
					$newwarningmessage=__('user.the_minimum_limit_is').": ".c_format($site_setting['wallet_min_amount']);
				$data['warning'] = $newwarningmessage;
			} 

		 $customSetting = $this->Product_model->getSettings('withdrawalpayment_bank_transfer');

			if (!empty($customSetting))
			{
				$data['setting_exist_status'] = 1;
				$data['get_custom_fiels'] = $customSetting;
			}
			else
			{
				$data['setting_exist_status'] = 0;
				$data['get_custom_fiels'] = array();
			}
			$data['PrimaryPaymentMethodStatus'] = $userdetails['primary_payment_method'];
			$data['paymentlist'] = $this->Product_model->getAllPayment($userdetails['id']);
			$data['paypalaccounts'] = $this->Product_model->getPaypalAccounts($userdetails['id']);
			
			$json['html'] = $this->load->view('usercontrol/users/parts/withdrawal_modal', $data, true);
			echo json_encode($json);die;
		}


		public function get_withdrwal_history($id){
			$status_history = $this->db->query("SELECT * FROM wallet_requests_history WHERE req_id={$id} ORDER BY id DESC ")->result_array();
			$json['html'] = '';

			foreach ($status_history as $key => $value) {
				$badge = $value['transaction_id'] ?  ' <span class="badge bg-secondary d-inline-block">Tran ID: '. $value['transaction_id'] .'</span>' : '';
				$json['html'].= '<tr>
						<td>'. withdrwal_status($value['status'])  .'</td>
						<td>'. $value['comment'] . $badge.'</td>
						</tr>';
					}

					echo json_encode($json);die;
				}

				public function purchase_plan(){
					$userdetails = $this->Product_model->userdetails('user',1);
					$membership = $this->Product_model->getSettings('membership');

					if(($membership['status'] == 1) || (($membership['status'] == 2) && ($userdetails['is_vendor'] == 1)) || (($membership['status'] == 3) && ($userdetails['is_vendor'] == 0))){
						$data = ['notcheckmember'=>1];
						$membership = $this->Product_model->getSettings('membership');
						if ((int)$membership['status'] == 0) {
							show_404();
						}
						$data['MembershipSetting'] =$this->Product_model->getSettings('membership');
						
						$user = App\User::auth();
						
						if((int)$user->plan_id == 0){
							
						}
						else if($user->plan_id == -1 && $userdetails['is_vendor'] == 1){
							$data['is_lifetime_plan'] = 1;
						} else if ($user) {
							$plan = $user->plan();
							if($plan){
								$data['plan']  = $plan;
							}
						}
						
						if($userdetails['is_vendor'] == 1)
							$data['plans'] = MembershipPlan::select('membership_plans.*','award_level.sale_comission_rate')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('status',1)->orderBy('sort_order', 'ASC')->get();
						else 
							$data['plans'] = MembershipPlan::select('membership_plans.*','award_level.sale_comission_rate')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('status',1)->where('user_type',1)->orderBy('sort_order', 'ASC')->get();
	 
						$data['payment_gateways'] = MembershipPlan::getPaymentMethods();
						
						$this->view($data,"membership/notaccess",'usercontrol');
					} else {
						if($membership['status'] == 1 || $membership['status'] == 3  || $membership['status'] == 2){
							$data = ['notcheckmember'=>1];
							$data['MembershipSetting'] =$this->Product_model->getSettings('membership');
							$user = App\User::auth();
							if((int)$user->plan_id == 0){
								
							}
							else if($user->plan_id == -1){
								$data['is_lifetime_plan'] = 1;
							} else if ($user) {
								$plan = $user->plan();
								if($plan){
									$data['plan']  = $plan;
								}
							}

							if($userdetails['is_vendor'] == 1)
								$data['plans'] = MembershipPlan::select('membership_plans.*','award_level.sale_comission_rate')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('status',1)->orderBy('sort_order', 'ASC')->get();
							else 
								$data['plans'] = MembershipPlan::select('membership_plans.*','award_level.sale_comission_rate')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('status',1)->where('user_type',1)->orderBy('sort_order', 'ASC')->get();

							$data['payment_gateways'] = MembershipPlan::getPaymentMethods();
							
							$this->view($data,"membership/notaccess",'usercontrol');
						} else {
							show_404();
						}
					}
				}

				public function purchase_plan_expire(){
					$data = ['notcheckmember'=>1];
					$membership = $this->Product_model->getSettings('membership');
					if ((int)$membership['status'] == 0) {
						show_404();
					}

					$user = App\User::auth();

					if((int)$user->plan_id == 0){
						redirect(base_url());	
					}
					else if($user->plan_id == -1){
						$data['is_lifetime_plan'] = 1;
					} else if ($user) {
						$plan = $user->plan();
						if($plan){
							$data['plan']  = $plan;

							if($plan->isExpire() || !$plan->strToTimeRemains() > 0)
							{}
							else
							redirect(base_url());	
						}
					}
	 				
					$this->view($data,"membership/purchase_plan_expire",'usercontrol');
				}

				public function purchase_history($page=1){
					$userdetails = $this->Product_model->userdetails('user',1);
					$membership = $this->Product_model->getSettings('membership');
					if(($membership['status'] == 1) || (($membership['status'] == 2) && ($userdetails['is_vendor'] == 1)) || (($membership['status'] == 3) && ($userdetails['is_vendor'] == 0))){
						$data = ['notcheckmember'=>1];
						$user = $this->checkLogin('user');
						$membership = $this->Product_model->getSettings('membership');
						if ((int)$membership['status'] == 0) {
							show_404();
						}
						$page = max((int)$page,1);

						\Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
							return $page;
						});

						$limit = 10;
						$query = App\MembershipUser::with("plan")->where("user_id",$user['id'])->orderBy("id","DESC")->paginate($limit);
						$data['links'] = $this->build_paginate($query, 'usercontrol/purchase_history',$page, $limit);
						$data['plans'] = $query;
						
						$this->view($data,"membership/purchase_history",'usercontrol');
					}else{
						show_404();
					}
				}

				public function membership_purchase_details($plan_id=1){
					if($this->Product_model->isMembershipAccess()){
						$data = ['notcheckmember'=>1];
						$membership = $this->Product_model->getSettings('membership');
						if((int)$membership['status'] == 0)
							show_404();

						$user = $this->checkLogin('user');
						$query = App\MembershipUser::select('membership_user.*','membership_plans.commission_sale_status','award_level.level_number')->join('membership_plans','membership_plans.id','=','membership_user.plan_id')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('membership_user.id', $plan_id)->where('membership_user.user_id', $user['id'])->first();
						if($query){
							$data['history'] = $query->status_history();
							$data['plan'] = $query;
							
							$this->view($data,"membership/purchase_detail",'usercontrol');
						} else {
							show_404();
						}
					} else {
						show_404();
					}
				}

		public function create_slug(){
			$json = array();

			$userdetails = $this->userdetails();
			$post = $this->input->post(null,true);
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('slug', 'Slug', 'callback__alpha_dash_space');

			if ($this->form_validation->run() == FALSE) {
			    $json['errors'] = $this->form_validation->error_array();
			} else {
				$campaign_row = null;
				$slug_type = isset($post['type']) ? (string) $post['type'] : '';
				$slug_related = isset($post['related_id']) ? (int) $post['related_id'] : 0;
				$integration_slug_types = array('program', 'action', 'single_action', 'general_click', 'banner', 'text_ads', 'link_ads', 'video_ads');
				if ($slug_type === 'form' && $slug_related > 0) {
					$this->load->model('Form_model');
					$campaign_row = $this->Form_model->getForm($slug_related);
					if (!is_array($campaign_row) || empty($campaign_row['form_id'])) {
						$json['error'] = __('user.something_went_wrong_please_try_again');
						echo json_encode($json);
						die;
					}
				} elseif ($slug_type === 'product' && $slug_related > 0) {
					$campaign_row = $this->Product_model->getProductByIdArray($slug_related);
					if (!is_array($campaign_row) || empty($campaign_row['product_id'])) {
						$json['error'] = __('user.something_went_wrong_please_try_again');
						echo json_encode($json);
						die;
					}
				} elseif ($slug_related > 0 && in_array($slug_type, $integration_slug_types, true)) {
					$this->load->model('IntegrationModel');
					$campaign_row = $this->IntegrationModel->getProgramToolsByID($slug_related);
					if (!is_array($campaign_row) || empty($campaign_row['id'])) {
						$json['error'] = __('user.something_went_wrong_please_try_again');
						echo json_encode($json);
						die;
					}
				}
				if (is_array($campaign_row) && !$this->Product_model->user_can_promote_market_campaign($userdetails, $campaign_row)) {
					$json['error'] = __('user.market_slug_locked');
					echo json_encode($json);
					die;
				}
			    // Check for existing slug from other users
			    $existingForOthers = Slug::where('slug', $post['slug'])
			                              ->where('type', $post['type'])
			                              ->where('user_id', '!=', (int)$userdetails['id'])
			                              ->first();

			    // Check for existing slug for the current user
			    $existingForUser = Slug::where('slug', $post['slug'])
			                            ->where('type', $post['type'])
			                            ->where('user_id', (int)$userdetails['id'])
			                            ->first();

				if ($existingForOthers) {
				    $json['error'] = __('user.slug_taken_by_another_user');
				} elseif ($existingForUser && (int)$existingForUser->related_id !== (int)$post['related_id']) {
				    $json['error'] = __('user.slug_already_used_choose_another');
			    } else {
			        // If no existing slug is found for other users, proceed to update or create new slug
			        $Slug = Slug::where('type', $post['type'])
			                    ->where('user_id', (int)$userdetails['id'])
			                    ->where('related_id', (int)$post['related_id'])
			                    ->first();

			        if ($Slug) {
			            // Update existing slug
			            $Slug->slug = $post['slug'];
			            $Slug->updated_at = date('Y-m-d H:i:s');
			            $Slug->save();
			        } else {
			            // Create new slug
			            $Slug = new Slug();
			            $Slug->user_id = (int)$userdetails['id'];
			            $Slug->related_id = (int)$post['related_id'];
			            $Slug->type = $post['type'];
			            $Slug->slug = $post['slug'];
			            $Slug->created_at = date('Y-m-d H:i:s');
			            $Slug->updated_at = date('Y-m-d H:i:s');
			            $Slug->save();
			        }

			        $json['slug_url'] = base_url($Slug->slug);
			        $json['success'] = __('user.slug_saved_successfully');
			    }
			}


			echo json_encode($json);
			die;
		}
				
		public function delete_slug(){
		    $json = array();

		    $userdetails = $this->userdetails();
		    $post = $this->input->post(null,true);

		    $Slug = Slug::where('type', $post['type'])
		                ->where('user_id', (int)$userdetails['id'])
		                ->where('related_id', (int)$post['related_id'])
		                ->first();

		    if(!$Slug){
		        $json['error'] = __('user.slug_not_found');
		    } else {
		        $url = ""; // Initialize URL
		        
		        if($Slug->type == 'register'){
		            $url = base_url('register/' . base64_encode($Slug->user_id));
		        } else if($Slug->type == 'store'){
		            $url = base_url('store/' . base64_encode($Slug->user_id));
		        } else if($Slug->type == 'product'){
		            $result = $this->db->query("SELECT product_slug FROM product WHERE `product_id` = '".(int)$Slug->related_id."'")->row();
		            $url = base_url('store/'.base64_encode($Slug->user_id).'/product/'.$result->product_slug);
		        } else if($Slug->type == 'form'){
		            $result = $this->db->query("SELECT seo FROM form WHERE `form_id` = '".(int)$Slug->related_id."'")->row();
		            $url = base_url('form/'.$result->seo.'/'.base64_encode($Slug->user_id));
		        } else {
		            $result = $this->db->query("SELECT target_link FROM integration_tools WHERE `id` = '".(int)$Slug->related_id."' AND `tool_type` = '".$Slug->type."'")->row();
		            if($result){
		                $url = $result->target_link;
		            }
		        }

		        $Slug->delete();
		        
		        $json['url'] = $url;
		        $json['success'] = __('user.slug_deleted_successfully');
		    }

		    echo json_encode($json);
		    die;
		}

		public function get_slug(){
		    $json = array();

		    $userdetails = $this->userdetails();
		    $post = $this->input->post(null, true);

		    $Slug = Slug::where('type', $post['type'])
		                ->where('user_id', (int)$userdetails['id'])
		                ->where('related_id', (int)$post['related_id'])
		                ->first();

		    if ($Slug) {
		        $json['slug'] = $Slug->slug;
		        $json['slug_url'] = base_url($Slug->slug);
		        $json['success'] = true;
		    } else {
		        $json['success'] = false;
		    }

		    echo json_encode($json);
		    die;
		}

		function _alpha_dash_space($str_in){
			$post = $this->input->post(null,true);

			$userdetails = $this->userdetails();
			$ignoreSlugs = array('store','usercontrol','admincontrol','product','auth','resetpassword','form','membership_callback','cronjob','admin','login','register','forget-password','default_controller','backend','page');

			if (!preg_match("/^([-a-z0-9])+$/i", $str_in)){
				$this->form_validation->set_message('_alpha_dash_space', 'The %s field may only contain alpha-numeric characters and dashes.');
				return FALSE;
			}else if(in_array($str_in, $ignoreSlugs)){
				$this->form_validation->set_message('_alpha_dash_space', 'You can\'t use specific word in slug');
				return FALSE;
			}else{
				$Slug = Slug::where('slug', 'like', $str_in)->where('type', '!=', $post['type'])->first();
				if($Slug){
					$this->form_validation->set_message('_alpha_dash_space', '%s already used, Enter unique slug.');
					return FALSE;
				}else{
					return TRUE;
				}
			}
		}


		public function downloadToolCode($id, $category) {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ die(); }

			$user_id = $userdetails['id'];

			$files = [];	

			if($category == 'form') {
				$this->load->model("Form_model");

				$data['user_id'] = $userdetails['id'];
				$getForm = $this->Form_model->getForm($id);
				if (empty($getForm) || !$this->Product_model->user_can_promote_market_campaign($userdetails, $getForm)) {
					show_404();
				}

				$code = array();
				$code[] = '<a href="'. base_url('form/'. $getForm['seo'] .'/'.base64_encode($user_id) ) .'">';
				$code[] = '    <h3>'. $getForm['title'] .'</h3>';
				if ($getForm['fevi_icon']) {
					$code[] = '    <img src="'. base_url('assets/images/form/favi/'.$getForm['fevi_icon']) .'" style="max-width:100%">';
				}
				$code[] = '</a>';

				$files[] = ["code.txt", implode("\n", $code)];

				$zip_file_name = str_replace(" ", "-", $getForm['title']);
				
			}

			if($category == 'product') {
				$getProduct 	= $this->Product_model->getProductByIdArray($id);
				if (empty($getProduct) || !$this->Product_model->user_can_promote_market_campaign($userdetails, $getProduct)) {
					show_404();
				}
				$productLink = base_url('store/'. base64_encode($user_id) .'/product/'.$getProduct['product_slug'] );
				$product_featured_image = $getProduct['product_featured_image'] != '' ? $getProduct['product_featured_image'] : '' ; 
				$product_featured_image = base_url('assets/images/product/upload/thumb/'. $product_featured_image);

				$code = array();
				$code[] = '<a href="'. $productLink .'">';
				$code[] = '<h3>'. ($getProduct['product_name'] ? $getProduct['product_name'] : '') .'</h3>';
				$code[] = '<img src="'. $product_featured_image .'" width="200" height="200" border="0" class="img-responsive" />';
				$code[] = '</a>';

				$files[] = ["code.txt", implode("\n", $code)];

				$zip_file_name = str_replace(" ", "-", $getProduct['product_name']);
			}

			if($category == 'tool') {
				$this->load->model("IntegrationModel");
				$tool = $this->IntegrationModel->getProgramToolsByID($id);
				if (empty($tool) || !$this->Product_model->user_can_promote_market_campaign($userdetails, $tool)) {
					show_404();
				}
				
				$zip_file_name = str_replace(" ", "-", $tool['name']);

				if($tool['type'] == 'banner') {
					foreach ($tool['ads'] as $key => $value) {
						$a_link = $this->prepareParamLink($tool['target_link'],"af_id",_encrypt_decrypt($user_id."-".$value['id']));

						$files[] = ["share_link.txt", $a_link];

						$code = '<a href="'.$a_link.'"><img src="'. $value['value'] .'" ></a>';

						$files[] = ["code.txt", $code];

						$imgSize = $value['size'];

						$imgArr = explode(".", $value['value']);

						$files[] = ["preview-".trim($imgSize).".".$imgArr[sizeof($imgArr) - 1], file_get_contents($value['value'])];
					}
				} else if($tool['type'] == 'text_ads') {
					$value = $tool['ads'][0];
					
					if($value){
						$style = array(
							'padding : 5px',
							'white-space : pre-line',
							'border : solid '. $value['text_border_color'] .' 1px',
							'display : inline-block',
							'line-height : 1',
							'color : '. $value['text_color'],
							'background-color :'. $value['text_bg_color'],
							'font-size :'. $value['text_size']."px",
						);
						
						$a_link = $this->prepareParamLink($tool['target_link'],"af_id",_encrypt_decrypt($user_id."-".$value['id']));

						$code = '<span style="'. implode(";", $style) .'"><a style="display: block;color: inherit;font-size: inherit;" href="'. $a_link .'">'. $value['value'] .'</a></span>';
						
						$files[] = ["share_link.txt", $a_link];
						$files[] = ["code.txt", $code];
					}
				} else if($tool['type'] == 'link_ads'){

					$value = $tool['ads'][0];
					if($value){
						$a_link = $this->prepareParamLink($tool['target_link'],"af_id",_encrypt_decrypt($user_id."-".$value['id']));
						$code = '<a style="display: block;font-size: 12px;" href="'. $a_link .'">'. $value['value'] .'</a>';
						$files[] = ["share_link.txt", $a_link];
						$files[] = ["code.txt", $code];
					} 

				} else if($tool['type'] == 'video_ads'){
					$value = $tool['ads'][0];
					if($value){
						$a_link = $this->prepareParamLink($tool['target_link'],"af_id",_encrypt_decrypt($user_id."-".$value['id']));

						$code = isset($value['iframe']) ? $value['iframe'] : '';
						$code .= '<div style="display:table;clear:both;"></div><br><a style="-moz-box-shadow:inset 0 1px 0 0 #fff;-webkit-box-shadow:inset 0 1px 0 0 #fff;box-shadow:inset 0 1px 0 0 #fff;background:-webkit-gradient(linear,left top,left bottom,color-stop(.05,#f9f9f9),color-stop(1,#e9e9e9));background:-moz-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-webkit-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-o-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-ms-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:linear-gradient(to bottom,#f9f9f9 5%,#e9e9e9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#f9f9f9\', endColorstr=\'#e9e9e9\', GradientType=0);background-color:#f9f9f9;-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;border:1px solid #dcdcdc;display:inline-block;cursor:pointer;color:#666;font-family:Arial;font-size:15px;font-weight:700;padding:6px 24px;text-decoration:none;text-shadow:0 1px 0 #fff" href="'. $a_link .'">'. $value['size'] .'</a>';
						$files[] = ["share_link.txt", $a_link];
						$files[] = ["code.txt", $code];
					}
				}
			}

			if (!file_exists('assets/user_upload/downloaded_tools')) {
				mkdir('assets/user_upload/downloaded_tools', 0777, true);
			}

			$existingfiles = glob('assets/user_upload/downloaded_tools/*');
			foreach($existingfiles as $existingfile) {
				if(is_file($existingfile)) {
					unlink($existingfile);
				}
			}

			if(!empty($files)) {
				$this->load->library('zip');

				foreach($files as $file) {
					$this->zip->add_data($file[0], $file[1]);
				}

				$this->zip->archive('assets/user_upload/downloaded_tools/'.$zip_file_name.'.zip');

				echo base_url('assets/user_upload/downloaded_tools/'.$zip_file_name.'.zip');
			}
		}

		private function prepareParamLink($url, $key, $value) {
			$url = preg_replace('/(.*)(?|&)'. $key .'=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
			$url = substr($url, 0, -1);
			
			if (strpos($url, '?') === false) {
				return ($url .'?'. $key .'='. $value);
			} else {
				return ($url .'&'. $key .'='. $value);
			}
		}

		public function get_payment_methods() {
			$vendorDepositStatus = $this->Product_model->getSettings('vendor', 'depositstatus');
			
			if($vendorDepositStatus['depositstatus']){
				$files = array();
				foreach (glob(APPPATH."/payment_gateway/controllers/*.php") as $file)
					$files[] = $file;

				$allPaymentGateways = array_unique($files);
				$activePaymentGateways = [];
				$defaultPaymntGateway = [];
				foreach($allPaymentGateways as $key => $filename){
					if(!str_contains($filename,'cod.php')){
						$paymentGateway = basename($filename,".php");

						$result = $this->Product_model->getSettings('payment_gateway_deposit_'.$paymentGateway,'status');
						$install = $this->Product_model->getSettings('payment_gateway_'.$paymentGateway,'is_install');
						if(isset($result['status']) && $result['status'] && $install['is_install']){
							require $filename;

							$object = new $paymentGateway($this);

							$activePaymentGateways[$paymentGateway] = $this->Product_model->getSettings('payment_gateway_'.$paymentGateway);

							$activePaymentGateways[$paymentGateway]['title'] = $object->title;
							$activePaymentGateways[$paymentGateway]['icon'] = $object->icon;
							$activePaymentGateways[$paymentGateway]['name']  = $paymentGateway;

							$where = array('setting_key'=>'status','setting_type'=>'payment_gateway_deposit_'.$paymentGateway,'setting_is_default'=>1);
							$is_default = $this->Common_model->get_total_rows('setting',$where);
							if($is_default){
								$defaultPaymntGateway[$paymentGateway] = $activePaymentGateways[$paymentGateway];
								unset($activePaymentGateways[$paymentGateway]);
							}
						}
					}
				}
				$data['payment_gateways'] = array_merge($defaultPaymntGateway,$activePaymentGateways);
				$this->session->set_userdata('payment_gateways',$data['payment_gateways']);

				$json['payment_gateways_count'] = count($data['payment_gateways']);
				$json['html'] = $this->load->view("usercontrol/payment/payment_methods", $data, true);
			} else {
				$json['error'] = true;
			}
			
			echo json_encode($json);
		}

		public function confirm_deposit(){

			$vendorDepositStatus = $this->Product_model->getSettings('vendor', 'depositstatus');
			if($vendorDepositStatus['depositstatus']){
				$data = $this->input->post(null,true);

				//maybe this is missing there? it was working fine many time...
				$userdetails = $this->Product_model->userdetails('user', true);

				if(!isset($userdetails['id']) || empty($userdetails['id'])){
					$json['error'] = __('user.unauthorized_access');
				} else if($data['payment_gateway'] == 'paystack' && !in_array($this->session->userdata('userCurrency'), 
					['GHS' , 'NGN', 'USD', 'ZAR' , 'KES'])){
					$json['error'] = "<div class='alert alert-danger'>".__('user.paystack_accept_only_currency')."</div>";
				} else if ($data['payment_gateway'] == 'xendit' && 
				         !in_array($this->session->userdata('userCurrency'), ['IDR', 'PHP', 'USD', 'VND', 'THB', 'MYR', 'SGD'])) {
				    $json['error'] = "<div class='alert alert-danger'>".__('user.xendit_accept_only_currency')."</div>";
				} else if($data['payment_gateway'] == 'yookassa' && $this->session->userdata('userCurrency') != 'RUB'){
					$json['error'] = "<div class='alert alert-danger'>".__('user.yookassa_accept_only_currency')."</div>";
				} else if((int)$data['amount'] > 0)
				
				{
					$vendorMinDepositAmt = $this->Product_model->getSettings('site', 'vendor_min_deposit');

					if(isset($vendorMinDepositAmt) && is_array($vendorMinDepositAmt))
						$vendorMinDepositAmt=$vendorMinDepositAmt['vendor_min_deposit'];
					else 
						$vendorMinDepositAmt=0;

					$total_deposited = $this->db->query("SELECT SUM(vd_amount) as total FROM vendor_deposit WHERE vd_status=1 AND vd_user_id=".(int) $userdetails['id'])->row()->total;

					$requiredeposit=$vendorMinDepositAmt-$total_deposited;
					 
					$v['vd_user_id'] = (int) $userdetails['id'];

					$default_currency = $this->db->query("SELECT `code` FROM currency WHERE is_default=1")->row_array();
					$vendor_deposit['vd_amount'] = $this->currency->convert($data['amount'],
						$this->session->userdata('userCurrency'),
						$default_currency['code']);

					$vendor_deposit['vd_status'] = 13;
					$vendor_deposit['vd_payment_method'] = $data['payment_gateway'];


					// Convert both amounts to a comparable numeric format without currency formatting
					$raw_deposit_amount = (float) $vendor_deposit["vd_amount"];
					$raw_required_deposit = (float) $requiredeposit;


					if($raw_deposit_amount >= $raw_required_deposit) {
						$paymentGateways = $this->session->userdata('payment_gateways');
						if($paymentGateways && isset($paymentGateways[$data['payment_gateway']])){
							require APPPATH."/payment_gateway/controllers/". $data['payment_gateway'] .".php";

							$paymentGateway = $data['payment_gateway'];
							$this->session->set_userdata('payment_gateway',$paymentGateway);

							$object = new $paymentGateway($this);

							$insert['payment_module'] = 2;
							$insert['user_id']= (int) $userdetails['id'];
							$insert['content']= serialize($vendor_deposit);
							$insert['datetime'] = date('Y-m-d H:i:s');
							$this->db->insert('uncompleted_payment',$insert);
							$uncompleted_id = $this->db->insert_id();

							$this->session->set_userdata('uncompleted_id',$uncompleted_id);

							$settingData = $paymentGateways[$paymentGateway];

							$country = $this->Product_model->getByField('states','id',$userdetails['Country']);
							$userdetails['sortname'] = $country['sortname'];

							require APPPATH.'/payment_gateway/module/deposit/view.php';
							$gatewayData = prepareDataForView($paymentGateway,$uncompleted_id,$userdetails,$vendor_deposit);
							
							ob_start();
							$object->getPaymentGatewayView($settingData,$gatewayData);
							$json['confirm'] = ob_get_clean();
						}

					}
					else
					{
						// Deposit is considered insufficient
					    $json['requireamt'] = $requiredeposit;
					    $json['error'] = "<p class='text-danger'>" . $this->session->userdata('userCurrency') . " " . $requiredeposit . " " . __('user.minimum_deposit_amount_required') . "</p>";
					}
						

				} else {
					$json['error'] = "<p class='text-danger'>".__('user.please_enter_valid_amount')."</p>";
				}
			} else {
				$json['error'] = __('user.deposit_module_disabled_info');
			}

			echo json_encode($json);
			die();
		}

		public function payment_confirmation(){

			$json = array();
			
			$meta = array();

			$post = $this->input->post(null,true);
			
			if(isset($post['comment']) && !empty($post['comment'])){
				if(is_array($post['comment'])) {
					$meta["comment"] = "";
					foreach($post['comment'] as $key => $value){
						if(empty($value['comment']))
							$json['errors']['comment'][$key] = "Comment can not be blank!";
						else
							$meta["comment"] .=  isset($value['comment']) ? $value['comment'] : $value;
					}
				} else {
					$meta["comment"] = $post['comment'];
				}
			}

			if(isset($post['bank_method']) && isset($post['bank_details'][$post['bank_method']])){
				$meta["bank_details"] = $post['bank_details'][$post['bank_method']];
				$meta["bank_details"] = str_replace("\r\n", "<br>", $meta["bank_details"]);
			}

			if(!$json['errors']){
				$uncompletedData = $this->Product_model->getByField('uncompleted_payment','id',$this->session->userdata('uncompleted_id'));
				$vendor_deposit = unserialize($uncompletedData['content']);

				$vendor_deposit['vd_meta'] = json_encode($meta,JSON_UNESCAPED_UNICODE);
				$uncompleted['content'] = serialize($vendor_deposit);
				$this->db->update('uncompleted_payment',$uncompleted,array('id' => $this->session->userdata('uncompleted_id')));

				$json['success'] = true;
			}else{
				$json['success'] = false;
			}

			echo json_encode($json);
			die;
		}

		public function confirm_payment(){

			$comment = $this->input->post('comment',true);

			$paymentGateways = $this->session->userdata('payment_gateways');
			$paymentGateway = $this->session->userdata('payment_gateway');
			if($paymentGateways && isset($paymentGateways[$paymentGateway])){
				$uncompletedData = $this->Product_model->getByField('uncompleted_payment','id',$this->session->userdata('uncompleted_id'));
				$vendor_deposit = unserialize($uncompletedData['content']);

				$file = isset($_FILES['payment_proof']) ? $_FILES['payment_proof'] : false;
				if((int)$payment_methods[$code]['setting_data']['proof'] == 1 && !$file){
					$json['errors']['payment_proof'] = 'Payment proof is required!';
				} else if($file){
					$extension = pathinfo($file["name"], PATHINFO_EXTENSION);
					$allow_ext = ['pdf', 'doc', 'docs', 'jpg', 'jpeg', 'png', 'PNG'];
					if(in_array($extension, $allow_ext)){
						$name = 'pp-'.time().$file['name'];
						move_uploaded_file($file['tmp_name'], FCPATH.'/assets/user_upload/'.$name);

						if(isset($meta->vd_meta))
							$meta = json_decode($meta->vd_meta, true);
						else
							$meta = [];

						$meta['payment_proof'] = $name;

						$vendor_deposit['vd_meta'] = json_encode($meta,JSON_UNESCAPED_UNICODE);
						$uncompleted['content'] = serialize($vendor_deposit);
						$this->db->update('uncompleted_payment',$uncompleted,array('id' => $this->session->userdata('uncompleted_id')));
					} else {
						$json['errors']['payment_proof'] = 'Allow only pdf | doc | docs | jpg | jpeg | png';
					}
				}

				if($this->input->post('bank_method') != ''){
					$this->session->set_userdata('bank_method_index', $this->input->post('bank_method'));
				}

				if(!isset($json['errors'])){
					if($this->session->userdata('uncompleted_id')){

						require APPPATH."/payment_gateway/controllers/". $paymentGateway .".php";
						$object = new $paymentGateway($this);

						$settingData = $paymentGateways[$paymentGateway];

						$userdetails = $this->Product_model->userdetails('user', true);

						require APPPATH.'/payment_gateway/module/deposit/request.php';
						$gatewayData = prepareDataForRequest($paymentGateway,$this->session->userdata('uncompleted_id'),$userdetails,$vendor_deposit);

						$json = $object->setPaymentGatewayRequest($settingData,$gatewayData);
					} else {
						$json['redirect'] = base_url('usercontrol/my_deposits');
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
				
				require APPPATH.'/payment_gateway/module/deposit/callback.php';
				$gatewayData = prepareDataForCallback($paymentGateway,$method,$uncompleted_id,$action);

				$object->$method($settingData,$gatewayData);
			}
		}
		
		public function confirmPaymentGateway($uncompleted_id, $status, $transaction_id = '', $comment = ''){
			
			$ex = new Exception();
			$trace = $ex->getTrace(); 
			if(!isset($trace[1]['class'])){ 
				return false; 
			}

			$paymentGateway = $trace[1]['class']; 
			$filename = APPPATH."/payment_gateway/controllers/{$paymentGateway}.php";
			require_once $filename;
			$userdetails = $this->userdetails();
			$object = new $paymentGateway($this);
			if($object->title){
				$uncompletedData = $this->Product_model->getByField('uncompleted_payment','id',$uncompleted_id);
				$vendor_deposit = unserialize($uncompletedData['content']);
				$vendor_deposit['vd_status'] = (int) $status;
				$vendor_deposit['vd_txn_id'] = $transaction_id;
				$vendor_deposit['vd_user_id'] = $userdetails['id'];

				$this->db->insert('vendor_deposit',$vendor_deposit);
				$vendor_deposit_id = $this->db->insert_id();

				$uncompleted['completed_id'] = $vendor_deposit_id;
				$this->db->update('uncompleted_payment',$uncompleted,array('id' => $uncompleted_id));

				$this->load->model('Deposit_payment_model');
				$this->Deposit_payment_model->apiAddVendorDepositHistory($vendor_deposit_id,[
					'status_id' => (int)$status,
					'comment' => (!empty($comment)) ? $comment : 'system generated status at time of payment',
					'transaction_id' => $transaction_id,
				]);

				$this->load->model('Mail_model');
				$deposit = $this->db->query('SELECT * FROM vendor_deposit WHERE vd_id='.$vendor_deposit_id)->row();

				$this->Mail_model->send_vendor_deposit_mail($deposit,'added');
				return true;
			}

			return false;
		}

		public function mlm_levels(){
			$userdetails = $this->userdetails();

			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			$vendor_setting = $this->Product_model->getSettings('vendor');


			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1)
				redirect('usercontrol/dashboard');

			if(!isset($market_vendor) || $market_vendor['vendormlmmodule'] != 1)
				redirect('usercontrol/dashboard');

			if((int)$market_vendor['marketvendorstatus'] != 1 && (int)$vendor_setting['storestatus'] != 1)
				redirect('usercontrol/dashboard');

			$commonSetting = array('referlevel','referlevel_1','referlevel_2','referlevel_3','referlevel_4','referlevel_5','referlevel_6','referlevel_7','referlevel_8','referlevel_9','referlevel_10','referlevel_11','referlevel_12','referlevel_13','referlevel_14','referlevel_15','referlevel_16','referlevel_17','referlevel_18','referlevel_19','referlevel_20','referlevel');

			$post = $this->input->post(null,true);
			if(!empty($post)){
				if(!isset($post['referlevel']['disabled_for'])){ 
					$post['referlevel']['disabled_for'] = array(); 
				}

				foreach ($post as $key => $value) {
					if (in_array($key, $commonSetting)) {
						$this->Setting_model->vendorSave($userdetails['id'], $key, $value);
					}
				}

				if(!isset($json['errors'])){
					$json['success'] =  __('user.setting_saved_successfully');
				}

				echo json_encode($json);die;

			} else {
				$data['vendorSettingTab'] = 'mlm_levels';
				$data['CurrencySymbol'] = $this->currency->getSymbol();

				foreach ($commonSetting as $key => $value)
					$data[$value] 	= $this->Product_model->getVendorSettings($userdetails['id'], $value);

				$this->view($data,'setting/mlm_levels','usercontrol');
			}
		}

		public function mlm_settings(){
			$userdetails = $this->userdetails();

			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			$vendor_setting = $this->Product_model->getSettings('vendor');

			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1)
				redirect('usercontrol/dashboard');

			if(!isset($market_vendor) || $market_vendor['vendormlmmodule'] != 1)
				redirect('usercontrol/dashboard');

			if((int)$market_vendor['marketvendorstatus'] != 1 && (int)$vendor_setting['storestatus'] != 1)
				redirect('usercontrol/dashboard');

			$post = $this->input->post(null,true);

			if(!empty($post)){
				if(!isset($post['referlevel']['disabled_for'])){ 
					$post['referlevel']['disabled_for'] = array(); 
				}

				foreach($post as $key => $value){
					if($key == 'referlevel')
						$this->Setting_model->vendorSave($userdetails['id'], $key, $value);
				}

				if(!isset($json['errors'])){
					$json['success'] =  __('user.setting_saved_successfully');
				}

				echo json_encode($json);die;
			} else {
				$data['vendorSettingTab'] = 'mlm_settings';
				$data['CurrencySymbol'] = $this->currency->getSymbol();

				$data['referlevel'] = $this->Product_model->getVendorSettings($userdetails['id'], 'referlevel');

				$this->view($data,'setting/mlm_settings','usercontrol');
			}
		}

		public function wallet_setting(){
			$userdetails = $this->userdetails();

			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$market_vendor['marketvendorstatus'] != 1)
				redirect('usercontrol/dashboard');
			
			$commonSetting = array('referlevel');

			$post = $this->input->post(null,true);
			if(!empty($post)){
				$json = array();
				if(!isset($json['errors'])){
					foreach($post as $key => $value){
						if(in_array($key,$commonSetting)){
							$this->Setting_model->vendorSave($userdetails['id'], $key, $value);
						}
					}
					if(!isset($json['errors']))
						$json['success'] =  __('user.setting_saved_successfully');
				}
				echo json_encode($json);
				die;
			}

			$data['vendorSettingTab'] = 'wallet_setting';
			$data['CurrencySymbol'] = $this->currency->getSymbol();
			foreach($commonSetting as $key => $value)
				$data[$value] 	= $this->Product_model->getVendorSettings($userdetails['id'], $value);

			$this->view($data,'setting/wallet_setting','usercontrol');
		}

		public function share_sales_setting(){

			$userdetails = $this->userdetails();

			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$market_vendor = $this->Product_model->getSettings('market_vendor');

			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$market_vendor['marketvendorstatus'] != 1)
				redirect('usercontrol/dashboard');
			 
			$post = $this->input->post(null,true);
			if(!empty($post)){
				$json = array();
				if(!isset($json['errors']))
				{
					$update = [
						'vendor_shares_sales_status' => $post['vendor_shares_sales_status']
					];

					$id = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row();


					if($id){
						$this->db->update("vendor_setting", $update, ['user_id'=> (int)$userdetails['id'] ]);
					} else{
						$update['user_id']=(int)$userdetails['id'];
						$this->db->insert("vendor_setting", $update);
					}


					 
					if(!isset($json['errors']))
						$json['success'] =  __('user.setting_saved_successfully');
				}
				echo json_encode($json);
				die;
			}

			$data['vendorSettingTab'] = 'share_sales_setting';
			$data['CurrencySymbol'] = $this->currency->getSymbol();
			$data['setting']=$this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row_array();

			$this->view($data,'setting/share_sales_setting','usercontrol');
		}


public function vendor_settings() {
    $userdetails = $this->userdetails();
    if(empty($userdetails)) redirect('usercontrol/dashboard');

    $post = $this->input->post(null, true);
    if(!empty($post)) {
        $json = [];

        // Load your model
        $this->load->model('User_model');

        // Call the update function in your model
        $result = $this->User_model->updateS3Settings($userdetails['id'], $post['s3_bucket_name'], $post['s3_region']);

        if($result) {
            $json['success'] = __('user.amazon_s3_settings_saved_successfully');
        } else {
            $json['errors'] = __('user.amazon_s3_settings_failed_saving');;
        }

        echo json_encode($json);
        die;
    }

    $data['setting'] = $this->db->query("SELECT s3_bucket_name, s3_region FROM users WHERE id=". (int)$userdetails['id'] ." ")->row_array();

    $this->view($data, 'setting/vendor_settings', 'usercontrol');
}

/**
 * Settings Hub — smart entry point for all user settings.
 * Vendors see a card grid of ALL their available settings sections.
 * Affiliates (non-vendors) are forwarded directly to their one setting (S3 storage).
 */
public function settings_hub() {
    $userdetails = $this->userdetails();
    if (empty($userdetails)) redirect('usercontrol/dashboard');

    // Affiliates only have S3/account settings — send them straight there
    if (empty($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1) {
        redirect('usercontrol/vendor_settings');
    }

    // Load all relevant system settings so the view can show enabled/disabled status
    $market_vendor  = $this->Product_model->getSettings('market_vendor');
    $vendor_setting = $this->Product_model->getSettings('vendor');
    $store_setting  = $this->Product_model->getSettings('store');

    $data['is_vendor']      = true;
    $data['market_vendor']  = $market_vendor;
    $data['vendor_setting'] = $vendor_setting;
    $data['store_setting']  = $store_setting;

    // Build the list of settings cards visible to this vendor
    $data['settings_cards'] = [];

    // --- Account / All-users ---
    $data['settings_cards'][] = [
        'section'  => 'account',
        'icon'     => 'fab fa-aws',
        'color'    => '#ff9f43',
        'bg'       => 'rgba(255,159,67,0.1)',
        'title'    => __('user.amazon_s3_settings'),
        'desc'     => __('user.s3_settings_desc'),
        'url'      => base_url('usercontrol/vendor_settings'),
        'enabled'  => true,
        'badge'    => '',
    ];

    // --- Vendor Store ---
    $store_enabled = (int)($vendor_setting['storestatus'] ?? 0) == 1 && (int)($store_setting['status'] ?? 0) == 1;
    $data['settings_cards'][] = [
        'section'  => 'vendor',
        'icon'     => 'fas fa-store',
        'color'    => '#7367f0',
        'bg'       => 'rgba(115,103,240,0.1)',
        'title'    => __('user.page_title_store_setting'),
        'desc'     => __('user.store_settings_desc'),
        'url'      => $store_enabled ? base_url('usercontrol/store_setting') : '#',
        'enabled'  => $store_enabled,
        'badge'    => $store_enabled ? '' : __('user.store_disabled'),
    ];

    // --- Market Vendor (Wallet / Share Sales / MLM) ---
    $market_active = (int)($market_vendor['marketvendorstatus'] ?? 0) == 1;
    $mlm_active    = $market_active && (int)($market_vendor['vendormlmmodule'] ?? 0) == 1;

    $data['settings_cards'][] = [
        'section'  => 'vendor',
        'icon'     => 'fas fa-wallet',
        'color'    => '#28c76f',
        'bg'       => 'rgba(40,199,111,0.1)',
        'title'    => __('user.page_title_vendor_wallet_settings'),
        'desc'     => __('user.wallet_settings_desc'),
        'url'      => $market_active ? base_url('usercontrol/wallet_setting') : '#',
        'enabled'  => $market_active,
        'badge'    => $market_active ? '' : __('user.market_vendor_disabled'),
    ];

    $data['settings_cards'][] = [
        'section'  => 'vendor',
        'icon'     => 'fas fa-share-alt',
        'color'    => '#00cfe8',
        'bg'       => 'rgba(0,207,232,0.1)',
        'title'    => __('user.page_title_vendor_share_sales_setting'),
        'desc'     => __('user.share_sales_desc'),
        'url'      => $market_active ? base_url('usercontrol/share_sales_setting') : '#',
        'enabled'  => $market_active,
        'badge'    => $market_active ? '' : __('user.market_vendor_disabled'),
    ];

    $data['settings_cards'][] = [
        'section'  => 'vendor',
        'icon'     => 'fas fa-sitemap',
        'color'    => '#ea5455',
        'bg'       => 'rgba(234,84,85,0.1)',
        'title'    => __('user.page_title_vendor_mlm_settings'),
        'desc'     => __('user.mlm_settings_desc'),
        'url'      => $mlm_active ? base_url('usercontrol/mlm_settings') : '#',
        'enabled'  => $mlm_active,
        'badge'    => $mlm_active ? '' : __('user.mlm_disabled'),
    ];

    $this->view($data, 'setting/settings_hub', 'usercontrol');
}


		public function setCookie(){
			$userdetails = $this->userdetails();
			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$this->load->helper('cookie');

			$name = $this->input->post('name',true).'_'.$userdetails['id'];
			setcookie($name,true,time() + 3600 * 24 * 30,'/');

			if(get_cookie($name))
				$result = true;
			else 
				$result = false;

			echo json_encode($result);
			die();
		}

		public function getSettings($key){
			return $this->Product_model->getSettings($key);
		}

		public function check_campaign_security_with_id($id) {
			$userdetails = $this->userdetails();
			if(empty($userdetails)) redirect('usercontrol/dashboard');

			$market_vendor = $this->Product_model->getSettings('market_vendor');
			if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		if((int) $id){
			$data = [];
			$tool = $this->IntegrationModel->getProgramToolsByID($id);

			if(!empty($tool) && $tool['vendor_id'] == $userdetails['id']){
				$im = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel';
				$methodStatus = getSecurityStatusByMethod($tool);

				if ($methodStatus !== null) {
					if ($tool['security_status'] != $methodStatus) {
						$this->db->query('UPDATE integration_tools SET security_status=' . (int)$methodStatus . ' WHERE id=' . $tool['id']);
					}
					$data['security_status'] = $methodStatus;
					$data['message'] = getSecurityStatusMethodLabel($im, $methodStatus);
					$data['statusClass'] = $methodStatus >= 1 ? 'badge bg-success' : 'badge bg-info';
					if ($methodStatus == 2) $data['statusClass'] = 'badge bg-primary';
				} elseif($tool['tool_type'] == 'program' && $tool['tool_integration_plugin'] == 'stripe'){
					$stripe_valid = true;
					$stripe_error = '';
					
					$commission_data = !empty($tool['commission']) ? json_decode($tool['commission'], true) : [];
					$stripe_price = isset($commission_data['stripe_price']) ? floatval($commission_data['stripe_price']) : 0;
					
					if(empty($stripe_price) || $stripe_price <= 0){
						$stripe_valid = false;
						$stripe_error = __('admin.stripe_campaign_price_not_set');
					}
					
					if($stripe_valid){
						$this->db->query('UPDATE integration_tools SET security_status=1 WHERE id='.$tool['id']);
						$data['security_status'] = 1;
						$data['statusClass'] = 'badge bg-success';
						$data['message'] = __('user.approved') . ' - ' . __('user.stripe_vendor_note_title');
					} else {
						$this->db->query('UPDATE integration_tools SET security_status=0 WHERE id='.$tool['id']);
						$data['security_status'] = 0;
						$data['statusClass'] = 'badge bg-info';
						$data['message'] = $stripe_error;
					}
				} else {
					$security_alerts = external_integration_security_check($tool['target_link']);
					$status = getSecurityStatus($security_alerts,$tool['tool_type'],$tool['tool_integration_plugin'],$tool['program_id']);

					if($tool['security_status'] == 1 && $status == 0){
						$this->db->query('UPDATE integration_tools SET security_status=0 WHERE id='.$tool['id']);
						$data['security_status'] = 0;
						$data['statusClass'] = 'badge bg-info';
						$data['message'] = __('user.pending_integration');
						$data['integration_code_button'] = '<button data-bs-toggle="tooltip" title="'.__('user.integration_code').'" 
						class="btn-show-code badge bg-info" data-id="'.$tool['id'].'">
						<i class="fa fa-code" aria-hidden="true"></i>
						</button>';
					}

					if($tool['security_status'] == 0 && $status == 1){
						$this->db->query('UPDATE integration_tools SET security_status=1 WHERE id='.$tool['id']);
						$data['security_status'] = 1;
						$data['statusClass'] = 'badge bg-success';
						$data['message'] = __('user.approved');
					}
				}
			}
			
			echo json_encode($data);
		}
		}

		public function check_campaign_security() {
		    $userdetails = $this->userdetails();
		    if (empty($userdetails)) redirect('usercontrol/dashboard');

		    $market_vendor = $this->Product_model->getSettings('market_vendor');
		    if ((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		    if ($this->input->server('REQUEST_METHOD') == 'POST') {
		        $result = [];
		        $post = $this->input->post(null, true);
		        $offset = isset($post['index']) ? $post['index'] - 1 : 0;

		        $tool = $this->db->query('SELECT * FROM integration_tools WHERE vendor_id = ' . $userdetails['id'] . ' LIMIT ' . $offset . ', 1')->row_array();

		        if (!empty($tool)) {
		            $integration_tools_count = $this->db->query('SELECT COUNT(id) as tools_count FROM integration_tools WHERE vendor_id = ' . $userdetails['id'])->row()->tools_count;

		            if ($integration_tools_count > $post['index'])
		                $result['index'] = $post['index'] + 1;

		            if ($integration_tools_count > 0)
		                $result['progress_percentage'] = (($post['index'] / $integration_tools_count) * 100) . "%";

		            // Decode the marketpostback JSON field
		            $marketpostback = json_decode($tool['marketpostback'], true);

		            // Set campaign name
		            $result['campaign_name'] = isset($tool['name']) ? $tool['name'] : 'Unnamed campaign';

		            $methodStatus = getSecurityStatusByMethod($tool);
		            $im = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel';

		            if (isset($marketpostback['status']) && $marketpostback['status'] === 'custom') {
		                $this->db->query('UPDATE integration_tools SET security_status=2 WHERE id=' . $tool['id']);
		                $tool['security_status'] = 2;
		                $result['security_status'] = 'postback';
		                $result['message'] = __('admin.postback_enabled');
		            } elseif ($methodStatus !== null) {
		                if ($tool['security_status'] != $methodStatus) {
		                    $this->db->query('UPDATE integration_tools SET security_status=' . (int)$methodStatus . ' WHERE id=' . $tool['id']);
		                    $tool['security_status'] = $methodStatus;
		                }
		                if ($methodStatus >= 1) {
		                    $result['security_status'] = 'approved';
		                    $result['message'] = getSecurityStatusMethodLabel($im, $methodStatus);
		                } else {
		                    $result['security_status'] = 'pending';
		                    $result['message'] = __('admin.intg_status_pending_setup');
		                }
		            } elseif ($tool['tool_type'] == 'program' && $tool['tool_integration_plugin'] == 'stripe') {
		                $stripe_valid = true;
		                $stripe_error = '';
		                
		                $commission_data = !empty($tool['commission']) ? json_decode($tool['commission'], true) : [];
		                $stripe_price = isset($commission_data['stripe_price']) ? floatval($commission_data['stripe_price']) : 0;
		                
		                if(empty($stripe_price) || $stripe_price <= 0){
		                    $stripe_valid = false;
		                    $stripe_error = __('admin.stripe_campaign_price_not_set');
		                }
		                
		                if($stripe_valid){
		                    $this->db->query('UPDATE integration_tools SET security_status=1 WHERE id=' . $tool['id']);
		                    $tool['security_status'] = 1;
		                    $result['security_status'] = 'approved';
		                    $result['message'] = __('admin.campaigns_verified_successfully') . ' - ' . __('user.stripe_vendor_note_title');
		                } else {
		                    $this->db->query('UPDATE integration_tools SET security_status=0 WHERE id=' . $tool['id']);
		                    $tool['security_status'] = 0;
		                    $result['security_status'] = 'pending';
		                    $result['message'] = $stripe_error;
		                }
		            } else {
		                $security_alerts = external_integration_security_check($tool['target_link']);
		                $status = getSecurityStatus($security_alerts, $tool['tool_type'], $tool['tool_integration_plugin'], $tool['program_id']);

		                if ($tool['security_status'] == 1 && $status == 0) {
		                    $this->db->query('UPDATE integration_tools SET security_status=0 WHERE id=' . $tool['id']);
		                    $tool['security_status'] = 0;
		                }

		                if ($tool['security_status'] == 0 && $status == 1) {
		                    $this->db->query('UPDATE integration_tools SET security_status=1 WHERE id=' . $tool['id']);
		                    $tool['security_status'] = 1;
		                }

		                if ($tool['security_status'] == 2) {
		                    $result['security_status'] = 'postback';
		                    $result['message'] = __('admin.postback_enabled');
		                } elseif ($tool['security_status'] == 1) {
		                    $result['security_status'] = 'approved';
		                    $result['message'] = __('admin.campaigns_verified_successfully');
		                } else {
		                    $result['security_status'] = 'pending';
		                    $result['message'] = __('admin.campaigns_in_pending_integration');
		                }
		            }
		        } else {
		            $result['warning'] = true;
		        }

		        echo json_encode($result);
		    }
		}


		public function updateComment() {

			if ($this->input->server('REQUEST_METHOD') === 'POST') {
				$comment = $this->input->post('comment');
				$index = $this->input->post('id');
				$tool_id = $this->input->post('tool_id');
				$old = $this->db->query("SELECT * FROM integration_tools WHERE id=". (int)$tool_id)->row(); 
				$oldcomment = json_decode($old->comment,1);
				$oldcomment[$index]['comment'] = $comment;
				$data = json_encode($oldcomment);
				$this->db->update("integration_tools",['comment'=>$data],['id' => $tool_id]);
				echo json_encode(['status'=>true]);
				exit;
				
			}
		}

		public function tickets() {

			$userdashboard_settings = $this->Common_model->getUserDashboardSettings();

			if(! isShowUserControlParts($userdashboard_settings['tickets_page'])) {
				redirect('usercontrol/dashboard');die;
			}

			$userdetails = $this->userdetails();
			$this->load->model('Tickets_model');
			if(empty($userdetails)){ redirect('/login'); }
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				$this->load->library('datatables');
				
				echo $res = $this->Tickets_model->getUserTickets($userdetails['id']);
				exit;	
			}
			$data['status'] = $this->Tickets_model->status();
			$data['subjects'] = $this->Tickets_model->getsubjectlist();

			$this->view($data,'tickets/tickets','usercontrol');
		}
		
		public function createticket(){
			$userdetails = $this->userdetails();

			if(empty($userdetails)){ redirect('/login'); }

			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('subject_id', 'Subject', 'required');
				$this->form_validation->set_rules('message', 'Message', 'required' );

				if($this->form_validation->run()){
					$data = $this->input->post(null);
					$imageFile=[];

					if(isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {


						$count_file = count($_FILES['attachment']['name']);
						$files = $_FILES['attachment'];	

						for($i=0; $i<$count_file; $i++){

							$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
							if(!empty(trim($files['name'][$i]))){
								if(in_array($extension, ['png','gif','jpeg','jpg','PNG','GIF','JPEG','JPG','ICO','ico','zip','doc','docs','pdf','xls','xlsx','ppt','pptx','txt'])) {

									$Fname= md5(random_string('alnum', 10)).'.'.$extension;
									$destinationPath = 'assets'.DIRECTORY_SEPARATOR.'user_upload'.DIRECTORY_SEPARATOR.$Fname;
									if(empty($files['error'][$i])){
										if(@move_uploaded_file($files['tmp_name'][$i], $destinationPath)) {
											$imageFile[] = $Fname;
										} else {
											echo json_encode(array('status' => false, 'message' => 'Fail to upload please try again'));
											exit;
										}
									} else {
										echo json_encode(array('status' => false, 'message' => 'Fail to upload please try again'));
										exit;
									}
								}

							}
						}
					}


					if(!isset($errors) || empty($errors)) {

						$data_to_add= [
							'user_id'=> $userdetails['id'],
							'subject_id'=> $data['subject_id'],
							'status'=> 1,
							'created_at'=>date('Y-m-d H:i:s'),
							'updated_at'=>date('Y-m-d H:i:s'),
							'ticket_id'=>'AF'.date(('Ymd'),time()).rand()
						];

						$is_generate = $this->Common_model->insert('tickets',$data_to_add);

						if($is_generate) {

							$data_to_replay= [
								'user_id'=> $userdetails['id'],
								'message'=> $data['message'],
								'attachment' => !empty($imageFile) ? json_encode($imageFile):'[]',
								'message_type' => !empty($imageFile) ? 2 : 1,
								'user_type'=> 2,
								'created_at'=>date('Y-m-d H:i:s'),
								'updated_at'=>date('Y-m-d H:i:s'),
								'ticket_id'=>$data_to_add['ticket_id']
							];

							$this->Common_model->insert('tickets_reply',$data_to_replay);
							
							$this->load->model('Mail_model');
							
							$this->Mail_model->send_ticket_mail($data_to_add['ticket_id'], 'ticket_created_email');

							$this->load->model('Product_model');
							$this->load->model('Tickets_model');
							
							$this->Product_model->sendTicketNotification([
								'id'	=> $data_to_add['ticket_id'],
								'type'	=> 'ticket_created',
								'title'	=> '#'.$data_to_add['ticket_id'].' '.__('user.new_ticket_created'),
								'desc'	=> $userdetails['username'].', '.__('user.has_created_new_ticket').', '.$this->Tickets_model->subject($data['subject_id']),
								'admin_notification'	=> 1,
								'user_notification'	=> $userdetails['id']
							]);

					$json['success'] = __('user.ticket_generated_successfully');

						} else {
							$json['errors'] = __('user.please_try_again');
						}
					} else {
						$json['errors']['attachment'] = $errors;
					}
				}else{
					$json['errors'] = $this->form_validation->error_array();
				}

				echo json_encode($json);die;
			}

			$data['notcheckapproval'] = 1; 
			$data['notcheckmember'] = 1;

			$data['userdetails'] = $this->Product_model->userdetails('user');
			

			$data['subjects'] = $this->Common_model->get_data_all_asc('tickets_subject',[],'id,subject','id');


			$this->view($data,'tickets/create','usercontrol');
		}
		public function ticketdetails($id){
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
			$user_id = $userdetails['id'];
			$id =  decryptString($id);
			$this->load->model('Tickets_model');

			$res = $this->Tickets_model->getTicketDetails($id,$user_id);
			
			if(!empty($res)) {
				$data['details'] = $res;
				$data['statusNAme'] = $this->Tickets_model->status()[$res['status']];
				$data['userName'] = $userdetails['firstname'].' '.$userdetails['lastname'];
				$this->view($data,'tickets/tickets-details','usercontrol');
			} else {
				redirect(base_url('usercontrol/tickets'),'refres');
			}
		}

		public function getTickestReply() {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				$user_id = $userdetails['id'];
				$ticket_id = $this->input->post('ticket_id');
				$this->load->model('Tickets_model');
				$res = $this->Tickets_model->getTicketDetails($ticket_id,$user_id);
				if(!empty($res)) {	
					$res = $this->Tickets_model->getTickestReply($ticket_id);
					echo json_encode($res);	
				}
				exit;
			}
		}

		public function sendMessage() {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
			if ($this->input->server('REQUEST_METHOD') == 'POST'){

				$user_id = $userdetails['id'];
				extract($this->input->post(),true);
				$imageFile = [];
				if (isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {

					$count_file = count($_FILES['attachment']['name']);
					$files = $_FILES['attachment'];	

					for($i=0; $i<$count_file; $i++){

						$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
						if(!empty(trim($files['name'][$i]))){
							if(in_array($extension, ['png','gif','jpeg','jpg','PNG','GIF','JPEG','JPG','ICO','ico','zip','doc','docs','pdf','xls','xlsx','ppt','pptx','txt'])) {

								$Fname= md5(random_string('alnum', 10)).'.'.$extension;
								$destinationPath = 'assets'.DIRECTORY_SEPARATOR.'user_upload'.DIRECTORY_SEPARATOR.$Fname;
								if(empty($files['error'][$i])){
									if(@move_uploaded_file($files['tmp_name'][$i], $destinationPath)) {
										$imageFile[] = $Fname;
									} else {
										echo json_encode(array('status' => false, 'message' => 'Fail to upload please try again'));
										exit;
									}
								} else {
									echo json_encode(array('status' => false, 'message' => 'Fail to upload please try again'));
									exit;
								}
							}

						}
					}
				}
				$data_to_add = array(
					'ticket_id' => $ticket_id,
					'user_id' => $user_id,
					'message' =>  $sendMessage,
					'attachment' => !empty($imageFile) ? json_encode($imageFile):'[]',
					'message_type' => !empty($imageFile) ? 2 :1,
					'created_at' => date('Y-m-d H:i:s'),
					'user_type'=>2
				);
				$res = $this->Common_model->insert('tickets_reply', $data_to_add);
				$this->load->model('Tickets_model');
				$old_status = $this->Tickets_model->getTicketDetails($ticket_id,$user_id);
				if($old_status['status'] == 3) {
					$update_data =['updated_at'=>date('Y-m-d H:i:s'),'status'=>1]; 
				} else{
					$update_data =['updated_at'=>date('Y-m-d H:i:s')]; 
				}
				$res = $this->Common_model->update('tickets',['ticket_id' => $ticket_id],$update_data);
				if ($res) {
					$this->load->model('Mail_model');
					$this->Mail_model->send_ticket_mail($ticket_id, 'ticket_reply_email');

					$this->load->model('Product_model');
					
					$this->Product_model->sendTicketNotification([
						'id'	=> $ticket_id,
						'type'	=> 'ticket_reply',
						'title'	=> __('user.new_replay_on_ticket').' #'.$ticket_id,
						'desc'	=> $userdetails['username'].', '.__('user.has_replyed_on_ticket'),
						'admin_notification'	=> 1,
					]);

					echo json_encode(array('status' => true, 'data' => [], 'message' => 'message replay successfully'));
				} else {
					echo json_encode(array('status' => false, 'message' => 'Please try again'));
				}
			}
		}

		public function closetickets() {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
			if ($this->input->server('REQUEST_METHOD') == 'POST'){
				$user_id = $userdetails['id'];
				$ticket_id = $this->input->post('ticket_id');
				$this->load->model('Tickets_model');
				$res = $this->Tickets_model->getTicketDetails($ticket_id,$user_id);
				if(!empty($res)) {	
					$res = $this->Common_model->update('tickets',['user_id'=>$user_id,'ticket_id'=>$ticket_id],['status'=>3,'updated_at'=>date('Y-m-d H:i:s')]);
					echo json_encode(array('status' => true));
					$this->load->model('Mail_model');
					$this->Mail_model->send_ticket_mail($ticket_id, 'ticket_status_email');

					$this->load->model('Tickets_model');

					$this->Product_model->sendTicketNotification([
						'id'	=> $ticket_id,
						'type'	=> 'ticket_status_updated',
						'title'	=> '#'.$ticket_id.' '.__('user.ticket_has_closed'),
						'desc'	=> $userdetails['username'].', '.__('user.has_closed_ticket_on').' '.date('d M Y'),
						'admin_notification' => 1,
						'user_notification'	 => $this->Tickets_model->ticket_owner($ticket_id)
					]);
				}
				exit;
			}
		}

		public function getStaticData() {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
			$user_id 	= $this->userdetails()['id'];
			$where = ['user_id'=>$user_id];
			$this->load->model('Tickets_model');
			$data['total'] = $this->Tickets_model->getTotalCountTickest(null,'tickets',$where)['total']??0;
			$data['totalopen'] = $this->Tickets_model->getTotalCountTickest(1,'tickets',$where)['total']??0;
			$data['totalclose'] = $this->Tickets_model->getTotalCountTickest(3,'tickets',$where)['total']??0;
			echo json_encode($data);
		}

		public function uncompleted_payments(){
			$userdetails = $this->userdetails();
			if(empty($userdetails)) redirect($this->admin_domain_url);

			$filter = $this->input->post(null,true);
			
			$this->load->model('Order_model');
			$this->load->library('pagination');

			$config['base_url'] = base_url('usercontrol/uncompleted_payments');
			$config['uri_segment'] = 3;
			$filter['limit'] = $config['per_page'] = 10;
			$filter['user'] = $userdetails['id'];

			$config['total_rows'] = $this->Wallet_model->getUncompletedPayment($filter, true);
			$config['use_page_numbers'] = TRUE;
			$config['page_query_string'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$_GET['page'] = $filter['page'];
			$config['query_string_segment'] = 'page';
			$this->pagination->initialize($config);
			$view['pagination'] = $this->pagination->create_links();
			
			$uncompleted_payments = $this->Wallet_model->getUncompletedPayment($filter);

			$this->load->model('Deposit_payment_model');

			$view['uncompleted_payments'] = $this->Wallet_model->prepareUncompletedPaymentData(
				$uncompleted_payments,
				$this->Deposit_payment_model->status_list
			);

			$view['payment_methods'] = $this->Order_model->PaymentMethods();

			$this->load->config('payment_gateway');
			$view['payment_module'] = $data['payment_module'] = config_item('payment_module');


			$html = $this->load->view("admincontrol/users/part/uncompleted_payments",$view,true);
			
			if(isset($filter['ajax'])){
				echo $html;
				die();
			}

			$data['html'] = $html;
			
			$data['users'] =  $this->db->query('SELECT id, CONCAT(firstname, " ", lastname) as username FROM users')->result_array();
			
			$this->view($data,'users/uncompleted_payments','usercontrol');
		}

		public function listclients($page = 1){

			$userdetails = $this->userdetails();
			if(empty($userdetails)) redirect('usercontrol/dashboard');
			$vendor_setting = $this->Product_model->getSettings('vendor');
			$store_setting = $this->Product_model->getSettings('store');
			if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1) redirect('usercontrol/dashboard');

			$data['user'] = $userdetails; 

			 
			if(isset($_POST['listclients'])) 
			{
				$vendor_id=$userdetails['id'];
					$page = max((int)$page,1);

				$filter = array(
					'limit' => 25,
					'page' => $page,
					'vendor_id' => $vendor_id 
				); 

				list($data['clientslist'],$total) = $this->Product_model->getVendorClients($filter);  
				$data['start_from'] = (($page-1) * $filter['limit'])+1; 
				$json['html'] = $this->load->view("usercontrol/clients/clients_list_tr", $data, true);

				$this->load->library('pagination');
				$config['base_url'] = base_url('usercontrol/listclients/');
				$config['per_page'] = $filter['limit'];
				$config['total_rows'] = $total;
				$config['use_page_numbers'] = TRUE;
				$config['enable_query_strings'] = TRUE;
				$this->pagination->initialize($config);
				$json['pagination'] = $this->pagination->create_links();
				echo json_encode($json);die;

				exit;
			} 

			$this->view($data,'clients/index','usercontrol'); 

		}

		public function getShippingDetails() {
			if($this->input->server('REQUEST_METHOD') === 'POST') {
				$user_id = $this->input->post('id');
				$data= $this->db->query("SELECT shipping_address.*,countries.name as country_name,states.name as state_name FROM shipping_address INNER JOIN countries ON countries.id=shipping_address.country_id INNER JOIN states ON states.id=shipping_address.state_id WHERE user_id = $user_id")->row_array();
				echo json_encode(['status'=>empty($data)?false:true,'data'=>$data]);
				exit;
			}
		}

		public function payment_details($id = null){

		   $userdetails = $this->userdetails();
		   if(empty($userdetails)){ redirect('/login'); }
		   $post = $this->input->post(null, true);

		   if (isset($post['add_paypal'])) {
		       $email = $this->input->post('paypal_email', true);
		       if ((int)$post['id'] > 0) {
		           $this->db->update("paypal_accounts", array(
		               'paypal_email' => $email,
		               'user_id' => $userdetails['id'],
		           ), array('id' => $post['id']));
		       } else {
		           $this->db->insert("paypal_accounts", array(
		               'paypal_email' => $email,
		               'user_id' => $userdetails['id'],
		           ));
		       }
		       $this->session->set_flashdata('success', __('user.paypal_account_saved_successfully'));
		       redirect('usercontrol/payment_details');
		   }
		   else if (isset($post['add_custom_gateway'])) {
		       $gateway_code = $this->input->post('gateway_code', true);
		       
		       if (empty($gateway_code)) {
		           $this->session->set_flashdata('error', 'Invalid gateway code');
		           redirect('usercontrol/payment_details');
		       }
		       
		       // Get all POST data for this gateway (remove system fields)
		       $gateway_data = array();
		       foreach ($post as $key => $value) {
		           if (strpos($key, $gateway_code . '_') === 0) {
		               $field_name = substr($key, strlen($gateway_code . '_'));
		               $gateway_data[$field_name] = $value;
		           }
		       }
		       
		       if (!empty($gateway_data)) {
		           $this->load->model('Payment_details_model');
		           $this->Payment_details_model->saveUserPaymentData(
		               $userdetails['id'], 
		               $gateway_code, 
		               $gateway_data
		           );
		           
		           $this->session->set_flashdata('success', __('user.payment_details_saved_successfully'));
		       } else {
		           $this->session->set_flashdata('error', 'No data provided');
		       }
		       
		       redirect('usercontrol/payment_details');
		   }
		   else if (isset($post['add_primary_payment'])) {
		       $primary_payment_method = $this->input->post('primary_payment_method', true);
		       if (!empty($primary_payment_method)) {
		           $this->db->update("users", array(
		               'primary_payment_method' => $primary_payment_method 
		           ), array('id' => $userdetails['id']));
		           $this->session->set_flashdata('success', __('user.primay_payment_method_saved_successfully'));
		       } else {
		           $this->session->set_flashdata('error', __('user.primay_payment_method_not_saved'));
		       }
		       redirect('usercontrol/payment_details');
		   }
		   else if(!empty($post)){
		       $this->load->helper(array('form', 'url'));
		       $this->load->library('form_validation');

		       // Get payment methods to check available country fields
		       $payment_methods = $this->Withdrawal_payment_model->getEnabledPaymentMethods();
		       
		       // Basic validation
		       $this->form_validation->set_rules('payment_bank_name', __('user.bank_name'), 'required');
		       $this->form_validation->set_rules('payment_account_number', __('user.account_number'), 'required');
		       $this->form_validation->set_rules('payment_account_name', __('user.account_name'), 'required');

		       // Validate country selection
		       if (isset($payment_methods['bank_transfer']['bank_transfer_mode']) && $payment_methods['bank_transfer']['bank_transfer_mode'] == 'specific') {
		           $this->form_validation->set_rules('payment_country', __('user.country'), 'required');
		       }

		       if($this->form_validation->run()) {
		           $errors = array();
		           $details = array(
		               'payment_bank_name'      => $this->input->post('payment_bank_name', true),
		               'payment_account_number' => $this->input->post('payment_account_number', true),
		               'payment_account_name'   => $this->input->post('payment_account_name', true),
		               'payment_status'         => 1,
		               'payment_ipaddress'      => $_SERVER['REMOTE_ADDR'],
		           );

		           if(isset($payment_methods['bank_transfer']['bank_transfer_mode']) && $payment_methods['bank_transfer']['bank_transfer_mode'] == 'specific') {
		               $details['payment_country'] = $this->input->post('payment_country', true);
		               
		               // Fetch all available country-specific fields from admin settings
		                $countryFieldMap = get_country_field_map();

		               $selected_country = $details['payment_country'];
		               if (isset($countryFieldMap[$selected_country])) {
		                   $field_name = $countryFieldMap[$selected_country];
		                   $details[$field_name] = $this->input->post($field_name, true);
		                   
		                   // Validate country-specific fields
		                   $this->form_validation->set_rules($field_name, __('user.'.$selected_country.'_field'), 'required');
		               }
		           }

		           if(empty($errors)) {
		               if ((int)$post['payment_id'] > 0) {
		                   $this->session->set_flashdata('success', __('user.payment_updated_successfully'));
		                   $details['payment_updated_by'] = $userdetails['id'];
		                   $details['payment_updated_date'] = date('Y-m-d H:i:s');
		                   $this->Product_model->update_data('payment_detail', $details, array('payment_id' => (int)$post['payment_id']));
		               } else {
		                   $this->session->set_flashdata('success', __('user.payment_added_successfully'));
		                   $details['payment_created_by'] = $userdetails['id'];
		                   $details['payment_created_date'] = date('Y-m-d H:i:s');
		                   $this->Product_model->create_data('payment_detail', $details);
		               }
		               redirect('usercontrol/payment_details');
		           } else {
		               $this->session->set_flashdata('error', implode(", ", $errors));
		               redirect('usercontrol/payment_details');
		           }
		       } else {
		           $this->session->set_flashdata('error', __('user.form_validation_error'));
		           redirect('usercontrol/payment_details');
		       }
		   }
		   else {
		       $data = array();
		       $data['payment_methods'] = $this->Withdrawal_payment_model->getEnabledPaymentMethods();
		       $data['primary_payment_method'] = $userdetails['primary_payment_method'];
		       $data['paymentlist'] = $this->Product_model->getAllPayment($userdetails['id']);

		       if (!empty($data['paymentlist'])) {
		           $data['paymentlist'] = array_merge($data['paymentlist'][0], [
		               'payment_routing_number' => $data['paymentlist'][0]['payment_routing_number'] ?? '',
		               'payment_sort_code'      => $data['paymentlist'][0]['payment_sort_code'] ?? '',
		               'payment_bsb_number'     => $data['paymentlist'][0]['payment_bsb_number'] ?? '',
		               'payment_transit_institution_number' => $data['paymentlist'][0]['payment_transit_institution_number'] ?? '',
		               'payment_iban_bic'       => $data['paymentlist'][0]['payment_iban_bic'] ?? '',
		               'payment_cnaps_code'     => $data['paymentlist'][0]['payment_cnaps_code'] ?? '',
		               'payment_swift_code'     => $data['paymentlist'][0]['payment_swift_code'] ?? '',
		               'payment_clearing_code'  => $data['paymentlist'][0]['payment_clearing_code'] ?? '',
		               'payment_bank_branch_number' => $data['paymentlist'][0]['payment_bank_branch_number'] ?? '',
		           ]);
		       }

		       $data['paypalaccounts'] = $this->Product_model->getPaypalAccounts($userdetails['id']);
		       $data['paypalaccounts'] = (!empty($data['paypalaccounts'])) ? $data['paypalaccounts'][0] : ['paypal_email' => '', 'id' => 0];

		       $data['paymentlist']['paypalaccounts'] = $data['paypalaccounts'];

		       // Load all custom gateway settings for the current user using unified system
		       $this->load->model('Payment_details_model');
		       $data['custom_gateway_settings'] = $this->Payment_details_model->getAllUserPaymentData($userdetails['id']);

		       $this->view($data, 'payment/payment_form', 'usercontrol');
		   }
		}

		public function listreviews_ajax($page = 1)
			{
				$userdetails = $this->userdetails();
				if(empty($userdetails)){ redirect('/login'); }
				$get = $this->input->get(null,true);
				$post = $this->input->post(null,true);
				
				$page=isset($get['page']) ? $get['page'] : $page;
				$limit=25;
				 
				$product_id=null;
				if(isset($post['product_name_review']) && $post['product_name_review']){
					$product_id = (int)$this->input->post('product_name_review');
		 	} 
		 	
			
			$filter=array("product_created_by"=>$userdetails['id']);

			$data = $this->Product_model->getAllReviewFilter($product_id,$limit,$page,$filter);

			$data['user_id']=$userdetails['id'];

			$vendormanagereviewimage=$this->db->query("SELECT * FROM setting WHERE  setting_key='vendormanagereviewimage' and setting_type='market_vendor'")->row();
			$data['vendormanagereviewimage'] = isset($vendormanagereviewimage) && $vendormanagereviewimage->setting_value==1 ? 1 : 0;	

			$json['view'] = $this->load->view("usercontrol/store/review_list", $data, true);
			
			$this->load->library('pagination');

			$this->pagination->cur_page = $page;

			$config['base_url'] = base_url('usercontrol/listreviews_ajax');

			$config['per_page'] = $limit;

			$config['total_rows'] = $data['total'];

			$config['use_page_numbers'] = TRUE;

			$config['page_query_string'] = TRUE;

			$config['enable_query_strings'] = TRUE;

			$_GET['page'] = $page;

			$config['query_string_segment'] = 'page';

			$this->pagination->initialize($config);

			$json['pagination'] = $this->pagination->create_links();

			$json['total']=$data['total'];
	 
			echo json_encode($json);
		}


		public function manage_review($id = null){

			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }

			$vendormanagereview= $this->db->query("SELECT * FROM setting WHERE  setting_key='vendormanagereview' and setting_type='market_vendor'")->row();
		  	$managereview=isset($vendormanagereview) && $vendormanagereview->setting_value==1 ? 1 : 0;
		  	if($managereview==0)
		  		redirect('usercontrol/dashboard');

			$post = $this->input->post(null,true);
		
			if(!empty($post) && isset($post['product_name'])){
	 			
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$this->form_validation->set_rules('product_name', __('user.product_name'), 'required');
				$this->form_validation->set_rules('firstname', __('user.firstname'), 'required' );
				$this->form_validation->set_rules('lastname', __('user.lastname'), 'required' );
				$this->form_validation->set_rules(
					'review_description', __('user.review_description'),
					'required|min_length[5]|max_length[150]',
					array(
						'required'      => 'Enter %s',
						'is_unique'     => 'This %s already exists.',
						'min_length' 	=> '%s: the minimum of characters is %s',
						'max_length' 	=> '%s: the maximum of characters is %s',
					)
				);

				
				$this->form_validation->set_rules('rating',__('user.rating'), "required"); 
				$this->form_validation->set_rules('rating_created',__('user.review_date_-_time'), "required"); 
				

				 if ($this->form_validation->run() == FALSE) {

					$json['errors'] = $this->form_validation->error_array();
					
				} 
				else 
				{ 
					$post = $this->input->post(null,true);	
					$rating_id = (int)$this->input->post('rating_id',true);
					$product_id = (int)$this->input->post('product_name',true); 

					$review=array();		

					$errors = array();
					$clientphoto=$post['user_image_hidden'];
					 if(isset($_FILES['user_image']) && !empty($_FILES['user_image']['name'])){
							$upload_response = $this->upload_photo('user_image','assets/images/users/');
							if($upload_response['success']){
								$clientphoto= $upload_response['upload_data']['file_name'];
							}else{
								$errors['user_image'] = $upload_response['msg'];
							}
						}

					if(count($errors)==0)	 
					{
	 					if($rating_id>0)
	 					{
	 					
	 						$user_id= $this->db->get_where('rating',array('rating_id'=>$rating_id))->row_array()['rating_user_id'];
	 						$this->db->where('id',$user_id)->update('users',array(
							'firstname' => $post['firstname'],
							'lastname'  => $post['lastname'],
							'avatar'  => $clientphoto  
							));  
								
							$review['products_id'] = $product_id;
							$review['rating_comments'] = $post['review_description'];
							$review['rating_number'] = $post['rating']; 
							$review['rating_status'] = 1; 
							$review['rating_updated_by'] = $userdetails['id'];
							$review['rating_created'] =  (isset($post['rating_created']) && $post['rating_created']) ? date("Y-m-d H:i:s",strtotime($post['rating_created'])) : null ;
							$review['rating_updated'] =  date("Y-m-d H:i:s");
							$review['rating_user_agent'] =  $this->agent->agent_string();
							$review['rating_os'] =  $this->agent->platform();
							$review['rating_browser'] =  $this->agent->browser();
							$review['rating_isp'] =  gethostbyaddr($_SERVER['REMOTE_ADDR']);
							$review['rating_ipaddress'] =  $_SERVER['REMOTE_ADDR'];

							$this->db->where('rating_id',$rating_id)->update('rating',$review);
							$this->Product_model->update_avg_rating($product_id);
							$this->session->set_flashdata('success', __('user.review_updated_successfully'));
							$json['location'] = base_url('usercontrol/store_products'); 
	 					}
	 					else
	 					{
	 						$data=$this->user->insert(array(

							'firstname' => $post['firstname'],
							'lastname'  => $post['lastname'],
							'avatar'  => $clientphoto, 
							'status'  => 1,
							'refid'     => 0,
							'type'      => 'client',
							));
							$insert_id = $this->db->insert_id(); 
								
							$review['products_id'] = $product_id;
							$review['rating_comments'] = $post['review_description'];
							$review['rating_number'] = $post['rating'];
							$review['rating_user_id'] = $insert_id; 
							$review['rating_status'] = 1; 
							$review['rating_created_by'] = $userdetails['id'];
							$review['rating_updated_by'] = $userdetails['id'];
							$review['rating_created'] =  (isset($post['rating_created']) && $post['rating_created']) ? date("Y-m-d H:i:s",strtotime($post['rating_created'])) : null ;
							$review['rating_updated'] =  date("Y-m-d H:i:s");
							$review['rating_user_agent'] =  $this->agent->agent_string();
							$review['rating_os'] =  $this->agent->platform();
							$review['rating_browser'] =  $this->agent->browser();
							$review['rating_isp'] =  gethostbyaddr($_SERVER['REMOTE_ADDR']);
							$review['rating_ipaddress'] =  $_SERVER['REMOTE_ADDR'];

							$this->Product_model->create_data('rating', $review);
							$this->Product_model->update_avg_rating($product_id);
							$this->session->set_flashdata('success', __('user.review_inserted_successfully'));
							$json['location'] = base_url('usercontrol/store_products'); 
	 					}
						
			 		}
			 	 
					
				}
				echo json_encode($json);
				exit; 
			}	
			$data['review'] = $this->Product_model->getReviewById($id)[0]; 
			$filter['product_status_in'] =	 '1';
			$filter['vendor_id']=$userdetails['id'];
			$data['products'] = $this->Product_model-> getAllProduct($userdetails['id'],'user',$filter);
			$vendormanagereviewimage=$this->db->query("SELECT * FROM setting WHERE  setting_key='vendormanagereviewimage' and setting_type='market_vendor'")->row();
			$data['vendormanagereviewimage'] = isset($vendormanagereviewimage) && $vendormanagereviewimage->setting_value==1 ? 1 : 0;	
			$data['setting'] = $this->Product_model->getSettings('productsetting'); 
			
			if(isset($data['review']['rating_created_by']) && $data['review']['rating_created_by']!= $userdetails['id'])
			{
	 			$this->session->set_flashdata('error', __('user.you_can_not_edit_other_user_review'));	
	 			redirect('usercontrol/store_products');
			}
			else{

	 			$this->view($data, 'store/add_review','usercontrol');
			}
		}

		public function deleteReview($id = null){
			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				if(empty($userdetails)){ redirect('/login'); }
			}

			if($id!="" && $id>0)
			{
				$res=$this->Product_model->deleteReview($id);
				if(isset($res))
					$this->session->set_flashdata('success', __('user.review_has_been_deleted_successfully'));
				else
	 				$this->session->set_flashdata('success', __('user.review_not_deleted'));
			} 
			redirect('usercontrol/store_products');
		}	

		public function exportReviewXML(){
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }

			$store_setting = $this->Product_model->getSettings('store');
			$json['structure_only'] = $structure_only = $this->input->post('structure_only');
			
			$datalist = [];
			if($structure_only == 1) {
				
			} else {
				$filter=array("product_created_by"=>$userdetails['id']);
				$data = $this->Product_model->getAllReviewFilter($product_id,$limit,$page,$filter);
				if(isset($data['reviews']))
				$datalist =$data['reviews'];
			}
	 
			$header = array(
				'rating_id' => 'Review ID [need to be empty for new]',
				'products_id' => 'Product ID [available in products export]',
				'firstname' => 'First Name',
				'lastname' => 'Last Name',
				'rating_number' => 'Rating Number [1 to 5]',
				'rating_comments' => 'Review Description',
				'rating_created' => 'Review Date and Time [ex. 2022-11-25 22:40:40]',
			); 

			$dom = new DOMDocument();
			$dom->encoding = 'utf-8';
			$dom->xmlVersion = '1.0';
			$dom->formatOutput = true;
			$root = $dom->createElement('reviews');
			
			if($structure_only == 1) 
			{
				$product_node = $dom->createElement('review');
				foreach ($header as $name_key => $_value) 
				{
					if($name_key!='product_short_description' && $name_key!='product_description' )
					{
						$child_node_title = $dom->createElement($name_key, $_value);
						$product_node->appendChild($child_node_title);
					}
					else
					{
						$child_node_title = $dom->createElement($name_key);
						$cdataname     = $dom->createCDATASection($_value);
						$child_node_title->appendChild($cdataname);
						$product_node->appendChild($child_node_title);
						 
					}

				}
				$root->appendChild($product_node);
				$dom->appendChild($root);
				
				$dom->save(FCPATH.'assets/xml/export_vendor_product_reviews_structure.xml');
				$json['download'] = base_url('assets/xml/export_vendor_product_reviews_structure.xml'); 
			}
			else
			{

				$index = 0;
				$_exportData = array();
				$_exportData[$index] = array_values($header);
				foreach ($datalist as $key => $value) 
				{
					$xml_node = $dom->createElement('review');
					$index++;
					foreach ($header as $name_key => $_value) 
					{
						$val = '';

						if(isset($value[$name_key])){

							switch ($name_key) {
								case 'product_tags':
								$t = ( is_array(json_decode($value[$name_key], true)) ? json_decode($value[$name_key], true): [] );
								$val = implode(",", $t);
								break;
								default:
								$val = $value[$name_key];
								break;
							}
						} 

						if($name_key!='product_short_description' && $name_key!='product_description' )
						{
							 $child_node_title = $dom->createElement($name_key, $val);
							 $xml_node->appendChild($child_node_title);
						}
						else
						{
							
							$child_node_title = $dom->createElement($name_key);
							$cdataname     = $dom->createCDATASection($val);
							$child_node_title->appendChild($cdataname);
							$xml_node->appendChild($child_node_title);
							 
						}
		  			}

		  			$root->appendChild($xml_node);
				}

				$dom->appendChild($root);
				$dom->save(FCPATH.'assets/xml/export_vendor_product_reviews.xml');
				$json['download'] = base_url('assets/xml/export_vendor_product_reviews.xml');	 
			}
		  
			echo json_encode($json);
		 	exit;
		}

		public function bulkReviewsImport() {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
	 
			$extension="";
			if(!isset($_FILES['file']['error']) || $_FILES['file']['error'] != 0)
			{
				$json['warning'] = __('user.please_select_xml_file');

			} else {

				$extension = pathinfo($_FILES['file']["name"], PATHINFO_EXTENSION);

				if($extension == 'xml')
				{}
				else
				{
					$json['warning'] = __('user.only_xml_file_are_allowed');
				}
			}
		 
			$f_result = [
				'products_available' => 0,
				'products_managed' => 0,
				'status' => 'danger',
				'message' => __('user.something_went_wrong_please_try_again!'),
				'data'  => [],
				'dataPreview' => ""
			];
			
			$bulkResult = [];

			if(!isset($json['warning'])){

				$inputFileName = $_FILES['file']['tmp_name'];

				if($extension == 'xml')
				{
					$xml = simplexml_load_file($inputFileName);
					if ($xml === false) 
					{
						$xmlerrrostring="";
						  $json['warning'] = __('user.failed_loading_xml');
					  foreach(libxml_get_errors() as $error) 
					  {
					    $xmlerrrostring.= "<br>". $error->message;
					  }

					  $json['warning'] =$xmlerrrostring;
					} 
					else 
					{
						$reviews=$xml;

						foreach($reviews as $review) 
						{
							$reviewArray = [];
							foreach($review as $key => $value) 
							{
						  		$xmlobjvalue= (string)$value[0];
						  		if(isset($xmlobjvalue)) 
						  		{
									$reviewArray[$key] = $xmlobjvalue != null ? $xmlobjvalue : '';
								} else {
									$reviewArray[$key] = '';
								} 
						  	} 

						  	if(!empty($reviewArray)) 
						  	{
								$cdata = $this->initialReviewImportCheck($reviewArray);
								if(isset($cdata) && is_array($cdata))
								$reviewArray['rating_status']=$cdata['data']['rating_status'];

								$cdata['row'] = $reviewArray;
								$bulkResult[] = $cdata;
							} 
						}
					}
				}

			} 
		 
			
			$data['action'] = 'confirm';
			$data['reviews'] = $bulkResult;
			echo $this->load->view('usercontrol/store/bulk_review_upload_modal', $data, true); 
		}

		public function bulkReviewImportFromUrl() 
		{
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }

				$f_result = [
				'reviews_available' => 0,
				'reviews_managed' => 0,
				'status' => 'danger',
				'message' => __('user.something_went_wrong_please_try_again'),
				'data'  => [],
				'dataPreview' => ""
			];
			
			$bulkResult = [];
			$json=array();
			$post = $this->input->post(null,true); 
			if(!isset($post['txt_review_xmlurl'])){
	 
				$json['warning'] = __('user.please_enter_xml_url'); 

			} 
			else {
	 
				$xmlurl = $post['txt_review_xmlurl'];
				$featchurldata=file_get_contents($xmlurl);
				$xml=simplexml_load_string($featchurldata);

				if($xml)
				{
				 	$reviews=$xml;
		 			if(isset($reviews))
		 			{
		 				foreach($reviews as $review) 
						{
							$reviewArray = [];
							foreach($review as $key => $value) 
							{
								$xmlobjvalue= (string)$value[0];
								if(isset($xmlobjvalue)) 
								{
									$reviewArray[$key] = $xmlobjvalue != null ? $xmlobjvalue : '';
								} 
								else {
									$reviewArray[$key] = '';
								} 
							} 

							if(!empty($reviewArray)) {
								$cdata = $this->initialReviewImportCheck($reviewArray);
								if(isset($cdata) && is_array($cdata))
								$reviewArray['rating_status']=$cdata['data']['rating_status'];

								$cdata['row'] = $reviewArray;
								$bulkResult[] = $cdata;
							} 
						}
		 			}
		 			else
		 				$json['warning'] = __('user.not_valid_xm_format'); 
							
				}
				else 
				{  
					$json['warning'] = __('user.url_entered_not_valid_xml_content');
				}

			}


			$data['action'] = 'confirm';
			$data['reviews'] = $bulkResult;
			echo $this->load->view('usercontrol/store/bulk_review_upload_modal', $data, true); 
		}

		public function downloadproductreviewxmlstructurefile($filename = NULL) {
		   $userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
		    $this->load->helper('download');
		    $data = file_get_contents(FCPATH .'assets/xml/export_vendor_product_reviews_structure.xml');
		    force_download("export_vendor_product_reviews_structure.xml", $data);

		}

		public function downloadproductreviewxmlfile($filename = NULL) {
		    $userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
		    $this->load->helper('download');
		    $data = file_get_contents(FCPATH .'assets/xml/export_vendor_product_reviews.xml');
		    force_download("export_vendor_product_reviews.xml", $data);
		}

		function checkDateTime($date)
		{
			 $format = 'Y-m-d H:i:s';
			 $d = DateTime::createFromFormat($format, $date);
	 		if($d && $d->format($format) == $date)
		    	return true; 
		    else
		    { 
		    	$this->form_validation->set_message('checkDateTime', __('user.invalid_date_format'));
		    	return false;
		    }
	         
		}

		public function initialReviewImportCheck($post){

		try {
				$userdetails = $this->userdetails();
				if(empty($userdetails)){ redirect('/login'); }
				if(!empty($post))
				{
					unset($this->validation);
					$rating_id = (int) $post['rating_id'];
					$product_id = (int) $post['products_id'];
					$rating_status=0;		
					if($rating_id > 0) 
					{
						$rating_exist = $this->db->query('select rating_created_by,products_id from rating where rating_id='.$rating_id)->row_array();
						if(empty($rating_exist))
						 {
						 	return [
								"status" => "error",
								"message" => __('user.review_not_available_having_rating_id_you_provided')
							];
								 	
						}
						else
						{ 	
							if($rating_exist["rating_created_by"]==$userdetails['id'])
								$rating_status=1;
							else
							return [
								"status" => "error",
								"message" => __('user.review_reated_by_other_can_not_be_change')
							];
						}
					}
					else
					{
						$product_owner = $this->db->query('SELECT product_created_by FROM `product` where `product_id`='.$product_id)->row_array(); 

						if(empty($product_owner)) 
				 		{
				 			return [
								"status" => "error",
								"message" => __('user.product_not_available_having_product_id_you_provided')
							];

				 		}
				 		else if ($product_owner["product_created_by"]!=$userdetails['id'])
				 			return [
								"status" => "error",
								"message" => __('user.review_can_not_be_add_on_product_created_by_other')
							];
						else  
							$rating_status=1;
					}
			 
		 			$this->load->helper(array('form', 'url'));
					$this->load->library('form_validation');
					$this->form_validation->reset_validation();

					$this->form_validation->set_rules('products_id', __('user.product_id'), 'required');
					$this->form_validation->set_rules('firstname', __('user.firstname'), 'required' );
					$this->form_validation->set_rules('lastname', __('user.lastname'), 'required' );
					$this->form_validation->set_rules(
						'rating_comments', __('user.review_description'),
						'required|min_length[5]|max_length[150]',
						array(
							'required'      => 'Enter %s',
							'is_unique'     => 'This %s already exists.',
							'min_length' 	=> '%s: the minimum of characters is %s',
							'max_length' 	=> '%s: the maximum of characters is %s',
						)
					);
					
					$this->form_validation->set_rules('rating_number',__('user.rating'), "required"); 
					
					$this->form_validation->set_rules('rating_created',__('user.review_date_-_time'),  'required|callback_checkDateTime'); 
				
					$this->form_validation->set_data($post);
					 if ($this->form_validation->run() == FALSE) {

						return [
								"status" => "error",
								"errors" => $this->form_validation->error_array()
							];
						
					}
					else 
					{
							
							$errors = array();
							
							  	$review=array();
								$review['rating_id'] = $post['rating_id']; 
								$review['products_id'] = $post['products_id'];  
								$review['rating_comments'] = $post['rating_comments'];
								$review['rating_number'] = $post['rating_number']; 
								$review['rating_status'] = 1; 
								$review['rating_created_by'] = $userdetails['id'];
								$review['rating_updated_by'] = $userdetails['id'];
								$review['rating_created'] =  (isset($post['rating_created']) && $post['rating_created']) ? date("Y-m-d H:i:s",strtotime($post['rating_created'])) : null ;
								$review['rating_updated'] =  date("Y-m-d H:i:s");
								$review['rating_user_agent'] =  $this->agent->agent_string();
								$review['rating_os'] =  $this->agent->platform();
								$review['rating_browser'] =  $this->agent->browser();
								$review['rating_isp'] =  gethostbyaddr($_SERVER['REMOTE_ADDR']);
								$review['rating_ipaddress'] =  $_SERVER['REMOTE_ADDR'];

								$review['firstname'] =  $post['firstname']; 
								$review['lastname'] =  $post['lastname'];  
						 
								
								if(isset($post['rating_id']) && !empty($post['rating_id']) && $post['rating_id'] != 0){
									
									return [
										"status" => "Warning",
										"message" => "<span class='badge bg-warning'>update</span>",
										"data" => $review
									];

								} else {
									return [
										"status" => "Warning",
										"message" => "<span class='badge bg-success'>create</span>",
										"data" => $review
									];
								}

						 
					} 
				}
				else
				{
					return [
						"status" => "error",
						"errors" => ["Something went wrong"]
					];
				}

			} catch (Exception $e) {
				return [
					"status" => "error",
					"errors" => [$e->getMessage()]
				];
			}
		}

		public function bulkReviewImportConfirm() 
		{
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }

			$data = json_decode(base64_decode($_POST['reviews']), true);

			$result = [
				'total_products' => 0,
				'created_products' => 0,
				'updated_products' => 0,
				'failed_products' => 0,
				'skipped_products' => 0,
				'details' => []
			];

			foreach($data as $d) {
				
				if($d['status'] !== 'error') {
					$r = $this->createUpdateImportedReview($d['data']);
					if(isset($r['created'])) {
						$result['created_products']++;
					} else if(isset($r['updated'])) {
						$result['updated_products']++;
					} else {
						$result['failed_products']++;
					}
					
					$result['details'][] = [
						'product' => $d['data'],
						'result' => $r
					];
				} else {
					$result['skipped_products']++;
				}
				$result['total_products']++;
			}
			
			echo $this->load->view('usercontrol/store/bulk_review_upload_modal', $result, true);
		}

		public function createUpdateImportedReview($post)
		{
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }
			try {
			
				$json['status'] = false;
				
				$userdetails = $this->userdetails();

				$old_product_data =[];
				
				$details = $post;

				if(isset($post['rating_id']) && !empty($post['rating_id']) && $post['rating_id'] != 0){
					$rating_id = $post['rating_id'];

					unset($details['rating_id']);

					$user_id= $this->db->get_where('rating',array('rating_id'=>$rating_id))->row_array()['rating_user_id'];
						$this->db->where('id',$user_id)->update('users',array(
					'firstname' => $post['firstname'],
					'lastname'  => $post['lastname'] 
					));  

					unset($details['firstname']);
					unset($details['lastname']);	
					$this->Product_model->update_data('rating', $details, array('rating_id' => $rating_id));

					$details['product_created_date'] = date('Y-m-d H:i:s');
					
					$json['updated'] = true;
					$json['status'] = true;
					$json['success'] = __('user.review_updated_successfully');

				} else {
		 
					$data=$this->user->insert(array(
					'firstname' => $post['firstname'],
					'lastname'  => $post['lastname'], 
					'status'  => 1,
					'refid'     => 0,
					'type'      => 'client',
					));
					$insert_id = $this->db->insert_id(); 

					$details['rating_user_id']=$insert_id;

					unset($details['firstname']);
					unset($details['lastname']);	

					$rating_id = $this->Product_model->create_data('rating', $details);
					$json['created'] = true;
					$json['status'] = true;
					$json['success'] = __('user.review_added_successfully');
				}
		 
				
			} catch (Exception $e) {
				$json['status'] = false;
				$json['errors'] = $e->getMessage();
			}
			
			return $json;
			die;
		}


		public function tutorial($id)
		{
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }

			$tutorial=$this->Tutorial_model->viewTutorial($userdetails,$id); 
			$data['title'] = $tutorial['title'];
			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('tutorial/display-tutorial', $tutorial);
			$this->load->view('usercontrol/includes/footer', $data); 
		}

		public function contactus($id)
		{
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }

			$data  = array();
			$where = array('notification_type'=>'contact_us','notification_id'=>$id);
			$data['notification_details'] = $this->Common_model->select_where_result('notification', $where);
			
			$data['title'] = _('user.contact_us');
			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('usercontrol/conatctus/conatctus_details', $data);
			$this->load->view('usercontrol/includes/footer', $data);
		}


		public function orders_notifications($id=null)
		{
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }

			if(isset($id) && $id>0)
			{
				$data  = array();
				$where = array('notification_view_user_id'=>$userdetails['id'],'notification_type'=>'integration_orders','notification_id'=>$id);
				 
				$notification = $this->Common_model->select_where_result('notification', $where);
				if(isset($notification) && is_array($notification) && count($notification)>0)
				{
					$order_id= $notification['notification_actionID'];
					$data['order']= $this->Order_model->getOrderDetails($order_id);
					$data['notification_title'] =$notification['notification_title'];
					$data['notification_details'] =$notification['notification_description'];

					$this->load->view('usercontrol/includes/header', $data);
					$this->load->view('usercontrol/notifications/ex_order_details', $data);
					$this->load->view('usercontrol/includes/footer', $data);

				}
				else
					redirect('/usercontrol/notification');
			}
			else
				redirect('/usercontrol/notification');
		}

		
		public function click_notification($id=null)
		{
			$userdetails = $this->userdetails();
			if(empty($userdetails)){ redirect('/login'); }

			if(isset($id) && $id>0)
			{
				$data  = array();
				$where = array('notification_view_user_id'=>$userdetails['id'],'notification_type'=>'integration_click','notification_id'=>$id);
				 
				$notification = $this->Common_model->select_where_result('notification', $where);
				if(isset($notification) && is_array($notification) && count($notification)>0)
				{
					$click_id= $notification['notification_actionID'];
					$data['order']= $this->Order_model->getClickActionDetails($click_id);
					$data['notification_title'] =$notification['notification_title'];
					$data['notification_details'] =$notification['notification_description'];

					$this->load->view('usercontrol/includes/header', $data);
					
					if($data['order']['click_type']=='action') 
						$this->load->view('usercontrol/notifications/ex_action_details', $data);	
					else
						$this->load->view('usercontrol/notifications/ex_click_details', $data);
					$this->load->view('usercontrol/includes/footer', $data);

				}
				else
					redirect('/usercontrol/notification');
			}
			else
				redirect('/usercontrol/notification');
		}
	
	// Helper method to extract user IDs from tree structure
	private function extractUserIdsFromTree($tree) {
		$userIds = array();
		
		if (is_array($tree)) {
			foreach ($tree as $node) {
				// Extract ID from name if it contains user ID
				if (isset($node['name'])) {
					// Try to extract user ID from the tree structure
					// The tree structure might have ID in different places
					if (isset($node['id'])) {
						$userIds[] = $node['id'];
					}
				}
				
				// Recursively get IDs from children
				if (isset($node['children']) && is_array($node['children'])) {
					$childIds = $this->extractUserIdsFromTree($node['children']);
					$userIds = array_merge($userIds, $childIds);
				}
			}
		}
		
		return array_unique($userIds);
	}
	
	// Helper method to get all users with online status
	private function getAllUsersWithOnlineStatus() {
	$query = "SELECT id, username, firstname, lastname, avatar, last_ping, created_at, created_at AS register_at 
			  FROM users 
			  WHERE type IN ('user','admin')";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}

	public function sales_funnels() {
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }
		
		$vendor_setting = $this->Product_model->getSettings('vendor');
		$store_setting = $this->Product_model->getSettings('store');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1 || $store_setting['store_mode'] != 'sales') {
			redirect('usercontrol/dashboard', 'refresh');
		}
		
		$data = [
			'title' => __('user.sales_funnels_management'),
			'userdetails' => $userdetails
		];

		$data['sales_products'] = $this->db->select('product_id, product_name, product_price, product_sku')
			->from('product')
			->where('is_campaign_product', 1)
			->where('product_status', 1)
			->where('product_created_by', $userdetails['id'])
			->order_by('product_name', 'ASC')
			->get()
			->result();

		$data['funnel_configs'] = $this->get_funnel_configurations($userdetails['id']);

		$this->view($data, 'sales_funnels/index', 'usercontrol');
	}

	public function save_funnel_config() {
		header('Content-Type: application/json');
		
		$userdetails = $this->userdetails();
		if(!$userdetails){
			echo json_encode([
				'success' => false,
				'message' => __('user.permission_denied')
			]);
			return;
		}

		$vendor_setting = $this->Product_model->getSettings('vendor');
		$store_setting = $this->Product_model->getSettings('store');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1 || $store_setting['store_mode'] != 'sales') {
			echo json_encode([
				'success' => false,
				'message' => __('user.permission_denied')
			]);
			return;
		}

		$frontend_product_id = $this->input->post('frontend_product_id');
		$upsell_product_ids = $this->input->post('upsell_product_ids');

		if (!$frontend_product_id) {
			echo json_encode([
				'success' => false,
				'message' => __('user.frontend_product_required')
			]);
			return;
		}

		$product_check = $this->db->select('product_id')
			->from('product')
			->where('product_id', $frontend_product_id)
			->where('product_created_by', $userdetails['id'])
			->get()
			->row();

		if (!$product_check) {
			echo json_encode([
				'success' => false,
				'message' => __('user.permission_denied')
			]);
			return;
		}

		$this->db->trans_start();

		$this->db->where('related_product_id', $frontend_product_id)
			->where('meta_key', 'funnel_upsells')
			->delete('product_meta');

		if (!empty($upsell_product_ids)) {
			$upsell_data = [
				'related_product_id' => $frontend_product_id,
				'meta_key' => 'funnel_upsells',
				'meta_value' => json_encode($upsell_product_ids)
			];
			$this->db->insert('product_meta', $upsell_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode([
				'success' => false,
				'message' => __('user.failed_to_save_funnel_configuration')
			]);
		} else {
			echo json_encode([
				'success' => true,
				'message' => __('user.funnel_configuration_saved_successfully')
			]);
		}
	}

	private function get_funnel_configurations($vendor_id) {
		$configs = [];
		
		$funnel_metas = $this->db->select('pm.related_product_id, pm.meta_value')
			->from('product_meta pm')
			->join('product p', 'p.product_id = pm.related_product_id')
			->where('pm.meta_key', 'funnel_upsells')
			->where('p.product_created_by', $vendor_id)
			->get()
			->result();

		foreach ($funnel_metas as $meta) {
			$upsell_ids = json_decode($meta->meta_value, true);
			$configs[$meta->related_product_id] = $upsell_ids;
		}

		return $configs;
	}

	public function funnel_pricing() {
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

		$vendor_setting = $this->Product_model->getSettings('vendor');
		$store_setting = $this->Product_model->getSettings('store');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1 || $store_setting['store_mode'] != 'sales') {
			redirect('usercontrol/dashboard', 'refresh');
		}

		$data = [
			'title' => __('user.sales_funnel_pricing'),
			'userdetails' => $userdetails
		];
		
		$data['sales_products'] = $this->db->select('product_id, product_name, product_price, product_sku')
			->from('product')
			->where('is_campaign_product', 1)
			->where('product_status', 1)
			->where('product_created_by', $userdetails['id'])
			->order_by('product_name')
			->get()
			->result();

		foreach ($data['sales_products'] as $product) {
			$funnel_price = $this->db->select('meta_value')
				->from('product_meta')
				->where('related_product_id', $product->product_id)
				->where('meta_key', 'funnel_price')
				->get()
				->row();

			$product->funnel_price = $funnel_price ? $funnel_price->meta_value : $product->product_price;
			$product->discount_percent = $product->product_price > 0 ? round((($product->product_price - $product->funnel_price) / $product->product_price) * 100) : 0;
		}

		$this->view($data, 'sales_funnels/pricing', 'usercontrol');
	}

	public function save_funnel_pricing() {
		$userdetails = $this->userdetails();
		
		if(!$userdetails) {
			echo json_encode(['success' => false, 'message' => __('user.permission_denied')]);
			return;
		}

		$vendor_setting = $this->Product_model->getSettings('vendor');
		$store_setting = $this->Product_model->getSettings('store');
		if(!isset($userdetails['is_vendor']) || $userdetails['is_vendor'] != 1 || (int)$vendor_setting['storestatus'] != 1 || (int)$store_setting['status'] != 1 || $store_setting['store_mode'] != 'sales') {
			echo json_encode(['success' => false, 'message' => __('user.permission_denied')]);
			return;
		}

		$product_id = $this->input->post('product_id');
		$funnel_price = $this->input->post('funnel_price');

		if (!$product_id || $funnel_price === null || $funnel_price === '') {
			echo json_encode(['success' => false, 'message' => __('user.invalid_data')]);
			return;
		}

		$product_check = $this->db->select('product_id')
			->from('product')
			->where('product_id', $product_id)
			->where('product_created_by', $userdetails['id'])
			->get()
			->row();

		if (!$product_check) {
			echo json_encode(['success' => false, 'message' => __('user.permission_denied')]);
			return;
		}

		$this->db->trans_start();

		$existing = $this->db->select('product_meta_id')
			->from('product_meta')
			->where('related_product_id', $product_id)
			->where('meta_key', 'funnel_price')
			->get()
			->row();

		if ($existing) {
			$this->db->where('product_meta_id', $existing->product_meta_id);
			$this->db->update('product_meta', ['meta_value' => $funnel_price]);
		} else {
			$this->db->insert('product_meta', [
				'related_product_id' => $product_id,
				'meta_key' => 'funnel_price',
				'meta_value' => $funnel_price
			]);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode(['success' => false, 'message' => __('user.save_failed')]);
		} else {
			echo json_encode(['success' => true, 'message' => __('user.funnel_price_saved_successfully')]);
		}
	}

	/**
	 * V14: Vendor Store Analytics Dashboard
	 */
	public function vendor_store_analytics() {
		$userdetails = $this->session->userdata('logged_in');
		if (empty($userdetails)) { redirect(base_url('usercontrol/dashboard')); return; }

		$vendor_id = (int)$userdetails['id'];
		$data['userdetails'] = $userdetails;
		$data['page_title'] = __('user.vendor_analytics');

		$month_ago = date('Y-m-d', strtotime('-30 days'));
		$prev_month = date('Y-m-d', strtotime('-60 days'));

		// Revenue stats - vendor orders come from order_products.vendor_id
		$q = $this->db->query("
			SELECT COUNT(DISTINCT o.id) as cnt, COALESCE(SUM(op.price * op.quantity),0) as rev 
			FROM order_products op
			INNER JOIN `order` o ON op.order_id = o.id
			WHERE o.status = 1 AND op.vendor_id = ? AND DATE(o.created_at) >= ?
		", [$vendor_id, $month_ago]);
		$current = $q->row();
		$data['month_orders'] = $current->cnt;
		$data['month_revenue'] = $current->rev;
		$data['avg_order_value'] = $data['month_orders'] > 0 ? round($data['month_revenue'] / $data['month_orders'], 2) : 0;

		// Previous period
		$q = $this->db->query("
			SELECT COALESCE(SUM(op.price * op.quantity),0) as rev 
			FROM order_products op
			INNER JOIN `order` o ON op.order_id = o.id
			WHERE o.status = 1 AND op.vendor_id = ? AND DATE(o.created_at) >= ? AND DATE(o.created_at) < ?
		", [$vendor_id, $prev_month, $month_ago]);
		$data['prev_revenue'] = $q->row()->rev;

		// Top products
		$data['top_products'] = $this->db->query("
			SELECT p.product_name, p.product_featured_image as product_image, SUM(op.quantity) as units, SUM(op.price * op.quantity) as revenue
			FROM order_products op
			INNER JOIN product p ON op.product_id = p.product_id
			INNER JOIN `order` o ON op.order_id = o.id
			WHERE o.status = 1 AND op.vendor_id = ? AND DATE(o.created_at) >= ?
			GROUP BY op.product_id ORDER BY revenue DESC LIMIT 10
		", [$vendor_id, $month_ago])->result_array();

		// Daily revenue for chart
		$data['daily_revenue'] = $this->db->query("
			SELECT DATE(o.created_at) as d, COALESCE(SUM(op.price * op.quantity),0) as revenue, COUNT(DISTINCT o.id) as orders
			FROM order_products op
			INNER JOIN `order` o ON op.order_id = o.id
			WHERE o.status = 1 AND op.vendor_id = ? AND DATE(o.created_at) >= ?
			GROUP BY DATE(o.created_at) ORDER BY d ASC
		", [$vendor_id, $month_ago])->result_array();

		// Repeat customers
		$data['repeat_customers'] = $this->db->query("
			SELECT COUNT(*) as cnt FROM (
				SELECT o.user_id FROM order_products op
				INNER JOIN `order` o ON op.order_id = o.id
				WHERE o.status = 1 AND op.vendor_id = ?
				GROUP BY o.user_id HAVING COUNT(DISTINCT o.id) > 1
			) t
		", [$vendor_id])->row()->cnt ?? 0;

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/store/vendor_analytics', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	/**
	 * V14: Vendor request payout
	 */
	public function request_payout() {
		$userdetails = $this->session->userdata('logged_in');
		if (empty($userdetails)) { echo json_encode(['success' => false]); return; }

		$vendor_id = (int)$userdetails['id'];
		$amount = (float)$this->input->post('amount');
		$method = $this->input->post('method', true) ?: 'bank';
		$note = $this->input->post('note', true);

		// Check minimum payout
		$store = $this->Product_model->getSettings('store');
		$min_amount = $store['min_payout_amount'] ?? 50;

		if ($amount < $min_amount) {
			echo json_encode(['success' => false, 'message' => sprintf(__('user.min_payout_not_met'), $min_amount)]); return;
		}

		// Check available balance
		$balance = $this->db->query("SELECT COALESCE(SUM(CASE WHEN type IN('vendor_sale_commission','vendor_shipping_reimbursement') AND status = 1 THEN amount ELSE 0 END),0) as total FROM wallet WHERE user_id = ?", [$vendor_id])->row()->total ?? 0;
		$already_requested = $this->db->query("SELECT COALESCE(SUM(amount),0) as total FROM vendor_payouts WHERE vendor_id = ? AND status IN('pending','approved')", [$vendor_id])->row()->total ?? 0;
		$available = $balance - $already_requested;

		if ($amount > $available) {
			echo json_encode(['success' => false, 'message' => 'Insufficient balance']); return;
		}

		$this->db->insert('vendor_payouts', [
			'vendor_id' => $vendor_id,
			'amount' => $amount,
			'currency' => 'USD',
			'method' => $method,
			'status' => 'pending',
			'vendor_note' => $note,
			'requested_at' => date('Y-m-d H:i:s')
		]);

		echo json_encode(['success' => true, 'message' => __('user.payout_requested_success')]);
	}

	/**
	 * V14: Vendor Payouts page
	 */
	public function vendor_payouts() {
		$userdetails = $this->session->userdata('logged_in');
		if (empty($userdetails)) { redirect(base_url('usercontrol/dashboard')); return; }

		$vendor_id = (int)$userdetails['id'];
		$data['userdetails'] = $userdetails;
		$data['page_title'] = __('user.payout_history');

		$data['payouts'] = $this->db->order_by('requested_at', 'DESC')
			->get_where('vendor_payouts', ['vendor_id' => $vendor_id])->result_array();

		// Available balance
		$balance = $this->db->query("SELECT COALESCE(SUM(CASE WHEN type IN('vendor_sale_commission','vendor_shipping_reimbursement') AND status = 1 THEN amount ELSE 0 END),0) as total FROM wallet WHERE user_id = ?", [$vendor_id])->row()->total ?? 0;
		$requested = $this->db->query("SELECT COALESCE(SUM(amount),0) as total FROM vendor_payouts WHERE vendor_id = ? AND status IN('pending','approved')", [$vendor_id])->row()->total ?? 0;
		$data['available_balance'] = $balance - $requested;

		$store = $this->Product_model->getSettings('store');
		$data['min_payout'] = $store['min_payout_amount'] ?? 50;
		$data['payout_methods'] = explode(',', $store['payout_methods'] ?? 'bank,paypal');

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/store/vendor_payouts', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	/**
	 * V14: Vendor save tracking info (AJAX)
	 */
	public function vendor_save_tracking() {
		$userdetails = $this->session->userdata('logged_in');
		if (empty($userdetails)) { echo json_encode(['success' => false]); return; }

		$order_id = (int)$this->input->post('order_id');
		$tracking_number = $this->input->post('tracking_number', true);
		$carrier = $this->input->post('carrier', true);
		$action = $this->input->post('action', true);

		if (!$order_id) { echo json_encode(['success' => false]); return; }

		// Verify vendor owns this order (vendor_id is in order_products, not order)
		$order = $this->db->query("
			SELECT o.id FROM `order` o 
			INNER JOIN order_products op ON op.order_id = o.id 
			WHERE o.id = ? AND op.vendor_id = ? LIMIT 1
		", [$order_id, (int)$userdetails['id']])->row();
		if (!$order) { echo json_encode(['success' => false, 'message' => 'Unauthorized']); return; }

		$update = [];
		if ($tracking_number !== null) $update['shipping_tracking_number'] = $tracking_number;
		if ($carrier !== null) $update['shipping_carrier'] = $carrier;
		if ($action === 'shipped') $update['shipped_at'] = date('Y-m-d H:i:s');
		elseif ($action === 'delivered') $update['delivered_at'] = date('Y-m-d H:i:s');

		if (!empty($update)) {
			$this->db->where('id', $order_id)->update('order', $update);
		}

		echo json_encode(['success' => true, 'message' => __('user.tracking_saved')]);
	}

}