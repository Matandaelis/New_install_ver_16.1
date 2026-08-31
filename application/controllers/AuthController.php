<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\User;

class AuthController extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Product_model');
        $this->load->model('Report_model');
		$this->load->model('User_model');
		$this->load->model('Common_model');
		$this->load->model('theme');

		___construct(1);

		$this->login_settings = $this->Product_model->getSettings('login');

		if(!isset($_SESSION['userLang'])) {
			$this->Product_model->setBrowserLanguage();
		}
	}


	public function user_login(){
		if($this->login_settings['front_template'] != 'landing'){ redirect("/"); }
        
        $data['SiteSetting'] = $this->Product_model->getSettings('site');;
        $data['title'] = 'Affiliate login';
		$data['meta_keywords'] = $data['SiteSetting']['meta_keywords'];
		$data['meta_description'] = $data['SiteSetting']['meta_description'];
        $this->render_page('auth/user/templates/login', $data);
    }

    public function user_forget_password(){
		if($this->login_settings['front_template'] != 'landing'){ redirect("/"); }
		$data['SiteSetting'] = $this->Product_model->getSettings('site');
		$data['title'] = "Affiliate Login";
		$this->render_page('auth/user/templates/forget_password', $data);
	}

	public function privacy_policy(){
		if($this->login_settings['front_template'] != 'landing'){ redirect("/"); }
		$data['tnc'] = $this->Product_model->getSettings('tnc');
		$data['title'] = $data['tnc']['heading'];
		$this->render_page('auth/user/templates/privacy_policy', $data);
	}

	public function change_language($language_id = null) {
	    if ($language_id === null) {
	        show_404();
	        return;
	    }
	    $language = $this->db->query("SELECT * FROM language WHERE id=".$this->db->escape($language_id))->row_array();
	    if ($language) {
	        $_SESSION['userLang'] = $language_id;
	        $_SESSION['userLangName'] = $language['name'];
	        header('Location: ' . $_SERVER['HTTP_REFERER']);
	    } else {
	        show_404();
	    }
	}


	public function changeLanguage(){
		$language_id = $this->input->post('language_id');
		$language = $this->db->query("SELECT * FROM language WHERE id=".$language_id)->row_array();
		if($language){
			$_SESSION['userLang'] = $language_id;
		}
		print_r($language_id);
	}

	public function user_register($refid = null){
		$this->session->set_userdata(array(
			'login_data'=> array(
				'refid' => $refid,
			),
		));
		redirect(base_url('register'));
	}

	public function vendor_register($refid = null){

		if(!empty($refid)){
			$this->session->set_userdata(array(
				'login_data'=> array(
					'refid' => $refid,
				),
			));
		}

		$registration_status = $this->Product_model->getSettings('store','registration_status');
		$data['vendor_storestatus'] = $this->Product_model->getSettings('vendor','storestatus');
		$data['vendor_marketstatus'] = $this->Product_model->getSettings('market_vendor', 'marketvendorstatus');
 

		if ((int)$registration_status['registration_status']==2  && $data['vendor_storestatus']["storestatus"]==0 && $data['vendor_marketstatus']["marketvendorstatus"]==0)
		{
			redirect(base_url().'register');
			die();
		}

		else if( ($data['vendor_storestatus']["storestatus"]==1 || $data['vendor_marketstatus']["marketvendorstatus"] ==1 ) && $registration_status['registration_status'] == 1 || $registration_status['registration_status'] == 2){

 
			$data['login'] = $this->login_settings;
		
			$siteSetting = $this->Product_model->getSettings('site');

			$this->load->model('PagebuilderModel');

			$login_data = $this->session->userdata("login_data");

			if(isset($login_data['refid'])){
				$data['refid'] = $login_data['refid'];
			}
			
			$data['design'] = '';
			$data['register_fomm'] = '';
			
			/*Get loginclient and tnc data to front**/
			$language_id=1;
			 if (isset($this->session) && $this->session->userdata('userLang') !== FALSE)
				$language_id=$this->session->userdata('userLang');

			$data['setting'] = $this->Product_model->getSettingsWithLanaguage('loginclient',$language_id);
			$data['tnc'] = $this->Product_model->getSettingsWithLanaguage('tnc',$language_id); 
			/*Get loginclient and tnc data to front**/

	        $data['SiteSetting'] = $this->Product_model->getSettings('site');
	        $data['countries'] = $this->User_model->getCountries();
			$data['title'] = $data['SiteSetting']['name'];
			$data['meta_keywords'] = $data['SiteSetting']['meta_keywords'];
			$data['meta_author'] = $data['SiteSetting']['meta_author'];
			$data['meta_description'] = $data['SiteSetting']['meta_description'];
			$data['footer'] = $data['SiteSetting']['footer'];
			$data['store'] = $this->Product_model->getSettings('store');
			
			$front_template = $this->login_settings['front_template'];
			
			if(isset($_GET['tmp_theme'])){
				$front_template = $_GET['tmp_theme'];
			}

			$lang = $_SESSION['userLang'];
			
			$data['selected_language'] = $this->db->query("SELECT * FROM language WHERE status=1 AND id=". (int)$lang)->row_array();
			
			if($front_template == 'multiple_pages'){
			   require(APPPATH.'controllers/Themes.php');
			   $Themes = new Themes(false);
			   $Themes->multiPages($this, 'register', true);
			} else if(substr($front_template,0,7) == 'custom_'){
				$registration_builder['template_index'] = substr($front_template, 7);
				$register_form = $this->PagebuilderModel->getSettings('registration_builder');
				$registration_builder['data'] = array();
				$registration_builder['allow_back_to_login'] = true;
				$registration_builder['registration_status'] = $registration_status['registration_status'];
				$registration_builder['vendor_storestatus'] = $data['vendor_storestatus']['storestatus'];
				$registration_builder['vendor_marketstatus'] = $data['vendor_marketstatus']["marketvendorstatus"];
		 		if(isset($register_form['registration_builder'])){
		 			$registration_builder['data'] = json_decode($register_form['registration_builder'],1);
		 		}

		 		if($registration_status['registration_status']){
		 			$registration_builder['is_vendor_registration'] = true;
		 			$data['register_fomm'] = $this->load->view('auth/user/templates/register_form',$registration_builder, true);
		 		}
		 		$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
				$data['is_home'] = true;
	        		
        		if(!$lang) $lang = 1;
				$data['language'] = $this->db->query("SELECT * FROM language WHERE status=1")->result_array();
				$data['language_selected'] = $this->db->query("SELECT * FROM language WHERE status=1 AND id=". (int)$lang)->row_array();

				$this->load->view('usercontrol/login/index'.str_replace("custom_", "", $front_template).'/register', $data);
			} else {
				$register_form = $this->PagebuilderModel->getSettings('registration_builder');
				$registration_builder['data'] = array();

				$registration_builder['allow_back_to_login'] = true;
				$registration_builder['registration_status'] = $registration_status['registration_status'];
				$registration_builder['vendor_storestatus'] = $data['vendor_storestatus']['storestatus'];
		 		if(isset($register_form['registration_builder'])){
		 			$registration_builder['data'] = json_decode($register_form['registration_builder'],1);
		 		}
		 		if($registration_status['registration_status']){
		 			$registration_builder['is_vendor_registration'] = true;
		 			$data['register_fomm'] = $this->load->view('auth/user/templates/register_form',$registration_builder, true);
		 		}

		 		$this->load->view('usercontrol/login/login/register', $data);
			}
		} else {
			redirect(base_url().'register');
			die();
		}
	}

	public function render_page($file , $data = array()){
		$this->front_assets_url = base_url('application/views/auth/user/assets/');
		
		$data['assets_url'] = base_url('application/views/auth/user/assets/');
		$data['setting'] = $this->Product_model->getSettings('templates');
		$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
		$data['templates_url'] = $this->front_assets_url ."img/";
		$data['content'] = $this->load->view($file,$data, true);
		$this->load->view('auth/user/templates/layout', $data);
	}

	public function admin_login(){
	    $data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
	    $data['setting'] = $this->Product_model->getSettings('site');
	    $theme = $this->Product_model->getSettings('theme');
	    $data['theme'] = $theme;

	    // Demo mode: auto-login unless ?switch=1 (switch to sub-admin)
	    $show_switch_form = $this->input->get('switch') === '1';
	    if (defined('ENVIRONMENT') && ENVIRONMENT === 'demo' && !$show_switch_form) {
	        $username = 'admin';
	        $password = 'admin2018$';
	        if ($this->authentication->login($username, $password)) {
	            $this->load->model('user_model', 'user');
	            $user_details_array = $this->user->login($username);
	            $this->user->update_user_login($user_details_array['id']);
	            $user_details_array = admin_user_with_permissions($user_details_array);
	            $this->session->set_userdata(array('administrator' => $user_details_array));
	            redirect(base_url('admincontrol/dashboard'));
	            return;  // Important to exit here
	        }
	    }
	    $data['show_login_as_demo_admin'] = (defined('ENVIRONMENT') && ENVIRONMENT === 'demo' && $show_switch_form);
	    $data['admin_login_url'] = base_url($this->admin_domain_url);
	    $this->load->view('auth/admin/index', $data);
	}

	//API
	public function api_admin_login() {
	    header("Content-Type: application/json"); // Return JSON response

	    // Read JSON input from Python
	    $data = json_decode(file_get_contents("php://input"), true);
	    $username = $data['username'] ?? null;
	    $password = $data['password'] ?? null;

	    if (!$username || !$password) {
	        echo json_encode(["error" => "Username and password required"]);
	        return;
	    }

	    // Authenticate user
	    if ($this->authentication->login($username, $password)) {
	        $this->load->model('user_model', 'user');
	        $user_details_array = $this->user->login($username);
	        $this->user->update_user_login($user_details_array['id']);

	        // Generate JSON response
	        echo json_encode([
	            "status" => "success",
	            "message" => "Login successful",
	            "user_id" => $user_details_array['id'],
	            "username" => $user_details_array['username']
	        ]);
	    } else {
	        echo json_encode(["error" => "Invalid login credentials"]);
	    }
	}

	//OPT
	public function otp_verify() {
	    $data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
	    $data['setting'] = $this->Product_model->getSettings('site');
	    $theme = $this->Product_model->getSettings('theme');
	    $data['theme'] = $theme;

	    $this->load->view('auth/admin/otp_verify', $data);
	}

	public function otp_validate() {
	    $input_otp = $this->input->post('otp_code', true);
	    $session_otp = $this->session->userdata('otp_code');
	    $user = $this->session->userdata('otp_user');
	    $is_ajax = $this->input->is_ajax_request() || $this->input->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest';

		if ($input_otp && $session_otp && $input_otp == $session_otp && $user) {
		    // ✅ OTP is valid, complete login
		    $this->session->unset_userdata('otp_code');
		    $this->session->unset_userdata('otp_user');
		    $this->session->unset_userdata('otp_attempts'); // ✅ Reset resend attempt counter

		    $user = admin_user_with_permissions(is_array($user) ? $user : (array)$user);
		    $this->session->set_userdata('administrator', $user);
		    
		    if($is_ajax){
		        header('Content-Type: application/json; charset=utf-8');
		        echo json_encode(array('success' => true, 'message' => __('admin.verification_success'), 'redirect' => base_url('admincontrol/dashboard')));
		        exit;
		    }
		    
		    redirect(base_url('admincontrol/dashboard'));
		}
		else {
	        // OTP invalid
	        if($is_ajax){
	            header('Content-Type: application/json; charset=utf-8');
	            echo json_encode(array('success' => false, 'message' => __('admin.invalid_otp_code')));
	            exit;
	        }
	        
	        $this->session->set_flashdata('error', 'Invalid OTP code.');
	        
	        // reload data for consistent view rendering
	        $data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
	        $data['setting'] = $this->Product_model->getSettings('site');
	        $theme = $this->Product_model->getSettings('theme');
	        $data['theme'] = $theme;

	        $this->load->view('auth/admin/otp_verify', $data);
	    }
	}

	public function resend_otp() {
	    header('Content-Type: application/json');

	    $user = $this->session->userdata('otp_user');
	    if (!$user) {
	        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
	        return;
	    }

	    // ✅ Limit resend attempts per session
	    $otp_attempts = $this->session->userdata('otp_attempts') ?? 0;
	    if ($otp_attempts >= 3) {
	        echo json_encode(['status' => 'error', 'message' => 'Too many resend attempts. Please login again.']);
	        return;
	    }

	    // ✅ Update counter
	    $this->session->set_userdata('otp_attempts', $otp_attempts + 1);

	    // ✅ Regenerate OTP
	    $otp_code = rand(100000, 999999);
	    $this->session->set_userdata('otp_code', $otp_code);

	    $this->load->model('Mail_model');
	    $this->Mail_model->send_otp($user['email'], $otp_code);

	    echo json_encode(['status' => 'success']);
	}
	//OPT


	public function multiple_pages($slug= ''){
		$data['setting'] = $this->Product_model->getSettings('loginclient');
		$this->load->model('PagebuilderModel');
	}

	public function user_index($childPage = false){

		$slug = end($this->uri->segment_array());
		$getFrontUrl = User::getFrontUrl();
		
		if ($slug == '' && $getFrontUrl != '') {
			show_404();
		}
		
		$loginUser = $this->session->userdata('user');
		if(isset($loginUser['id'])) {
			redirect(base_url().'usercontrol/dashboard');
			die();
		}

		$registration_status = $this->Product_model->getSettings('store','registration_status');
		$data['vendor_storestatus'] = $this->Product_model->getSettings('vendor','storestatus');
		$data['vendor_marketstatus'] = $this->Product_model->getSettings('market_vendor', 'marketvendorstatus');
		
		if(($childPage == 'register' && $registration_status['registration_status'] == 0)
			|| ($childPage == 'register' && $registration_status['registration_status'] == 2 
										 && !$data['vendor_storestatus']['storestatus'])){
			redirect(base_url().'login');
			die();
		}

		if($childPage == 'register' && $registration_status['registration_status'] == 2){

			redirect(base_url().'register/vendor');
			die();
		}

		
		$data['login'] = $this->login_settings;
		$siteSetting = $this->Product_model->getSettings('site');

		if (isset($_POST['send_contact_form'])) {
			$googlerecaptcha = $this->Product_model->getSettings('googlerecaptcha');
			if (isset($googlerecaptcha['client_login']) && $googlerecaptcha['client_login']) {
				if($post['g-recaptcha-response'] == ''){
					$json['errors']['captch_response'] = 'Invalid Recaptcha';
				}
			}

			if( count($json['errors']) == 0 ){
				if ( isset($googlerecaptcha['client_login']) && $googlerecaptcha['client_login']) {
					$post = http_build_query(array (
						'response' => $post['g-recaptcha-response'],
						'secret'   => $googlerecaptcha['secretkey'],
						'remoteip' => $_SERVER['REMOTE_ADDR']
					));
					$opts = array('http' => array (
						'method' => 'POST',
						'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
						."Content-Length: " . strlen($post) . "\r\n",
						'content' => $post
					));
					$context = stream_context_create($opts);
					$serverResponse = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
					if (!$serverResponse) {
						$json['errors']['captch_response'] = 'Failed to validate Recaptcha';
					}
					$result = json_decode($serverResponse);

					if (!$result->success) {
						$json['errors']['captch_response'] = 'Invalid Recaptcha';
					}
				}
			}
			
			if(count($json['errors']) == 0){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('fname', 'First Name', 'required|min_length[2]');
			$this->form_validation->set_rules('lname', 'Last Name', 'required|min_length[2]');
			$this->form_validation->set_rules('phone', 'Phone Number', 'required');
			$this->form_validation->set_rules('subject', 'Subject', 'required');
			$this->form_validation->set_rules('body', 'Mail Body', 'required' );
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('terms', 'Terms', 'required');

			if($this->form_validation->run()){
				$data = $this->input->post(null);
				$this->load->model('Mail_model');
				$this->Mail_model->send_store_contact_vendor($data);
				$json['success'] = 'Mail sent Successfully';
			}else{
				
				$json['errors'] = $this->form_validation->error_array();
			}
			echo json_encode($json);die;
			}
			echo json_encode($json);die;
		}
		
		$this->load->model('PagebuilderModel');
		$login_data = $this->session->userdata("login_data");
		if(isset($login_data['refid'])){
			$data['refid'] = $login_data['refid'];
		}
		
		$data['design'] = '';
		$data['register_fomm'] = '';


		 $language_id=1;
		 if (isset($this->session) && $this->session->userdata('userLang') !== FALSE)
			$language_id=$this->session->userdata('userLang');
		
		$data['setting'] = $this->Product_model->getSettingsWithLanaguage('loginclient',$language_id); 
		$data['tnc'] = $this->Product_model->getSettingsWithLanaguage('tnc',$language_id); 
        $data['SiteSetting'] = $this->Product_model->getSettings('site');
        $data['countries'] = $this->User_model->getCountries();
		$data['title'] = $data['SiteSetting']['name'];
		$data['meta_keywords'] = $data['SiteSetting']['meta_keywords'];
		$data['meta_author'] = $data['SiteSetting']['meta_author'];
		$data['meta_description'] = $data['SiteSetting']['meta_description'];
		$data['footer'] = $data['SiteSetting']['footer'];
		$data['store'] = $this->Product_model->getSettings('store');

		$this->load->model('Login_stats_model');
		$this->load->helper('login_page_blocks');
		$loginForStats = $this->Product_model->getSettings('login');
		$data['login_live_stats_visible'] = login_page_block_stats_enabled($loginForStats);
		$data['login_top_earners_visible'] = login_page_block_enabled($loginForStats, 'block_top_earners_enabled', null);
		$cpRaw = $childPage ? (string) $childPage : '';
		if ($cpRaw === 'login') {
			$cpRaw = 'index';
		}
		$isLoginFrontPage = ($cpRaw === '' || $cpRaw === 'index');
		$isRegisterFrontPage = ($childPage === 'register');
		$isLoginOrRegisterFrontPage = $isLoginFrontPage || $isRegisterFrontPage;
		$data['login_live_stats'] = null;
		$data['login_top_earners'] = null;
		$front_template = $this->login_settings['front_template'];
		if (isset($_GET['tmp_theme'])) {
			$front_template = $_GET['tmp_theme'];
		}
		if ($front_template !== 'multiple_pages' && $isLoginOrRegisterFrontPage) {
			if ($data['login_live_stats_visible']) {
				$data['login_live_stats'] = $this->Login_stats_model->get_public_stats();
			}
			if ($data['login_top_earners_visible']) {
				$earner_rows = $this->Login_stats_model->get_top_earners();
				$data['login_top_earners'] = !empty($earner_rows) ? $earner_rows : null;
			}
			$this->load->library('theme_blocks_handler');
			$this->theme_blocks_handler->merge_login_register_hooks($data, (string) $front_template);
		}

		$lang = $_SESSION['userLang'];
		$data['selected_language'] = $this->db->query("SELECT * FROM language WHERE status=1 AND id=". (int)$lang)->row_array();

		if($front_template == 'multiple_pages'){
			$loginUser = $this->session->userdata('user');
			if(isset($loginUser['id']) && ($childPage == 'login' || $childPage == 'register')) {
				redirect(base_url().'usercontrol/dashboard');
				die();
			}

		   require(APPPATH.'controllers/Themes.php');
		   $Themes = new Themes(false);
		   $Themes->multiPages($this, $childPage);

		} else if(substr($front_template,0,7) == 'custom_'){

			$registration_builder['template_index'] = substr($front_template, 7);
			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			$registration_builder['data'] = array();
			$registration_builder['allow_back_to_login'] = true;
			$registration_builder['registration_status'] = $registration_status['registration_status'];
			$registration_builder['vendor_storestatus'] = $data['vendor_storestatus']['storestatus'];
			$registration_builder['vendor_marketstatus'] = $data['vendor_marketstatus']['marketvendorstatus'];
	 		if(isset($register_form['registration_builder'])){
	 			$registration_builder['data'] = json_decode($register_form['registration_builder'],1);
	 		}

	 		if($registration_status['registration_status']){
	 			
	 			$data['register_fomm'] = $this->load->view('auth/user/templates/register_form',$registration_builder, true);
	 		}
	 		$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
			$data['is_home'] = true;
			
    		if(!$lang) $lang = 1;
			$data['language'] = $this->db->query("SELECT * FROM language WHERE status=1")->result_array();
			$data['language_selected'] = $this->db->query("SELECT * FROM language WHERE status=1 AND id=". (int)$lang)->row_array();

			$childPage = ($childPage == "login") ? "index" : $childPage;

			$loginUser = $this->session->userdata('user');
			if(isset($loginUser['id']) && ($childPage == 'login' || $childPage == 'register')) {
				redirect(base_url().'usercontrol/dashboard');
				die();
			}
			
			$page_name = $childPage ? $childPage : "index";
			$allowed_pages = ['index', 'register', 'forget-password', 'terms-of-use'];
			if($childPage && !in_array($page_name, $allowed_pages)) {
				redirect(base_url('/'));
				return;
			}
 			$page=str_replace("custom_", "", $front_template);
 			
			$this->load->view('usercontrol/login/index'.str_replace("custom_", "", $front_template).'/'.$page_name, $data);
		} else {

			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			$registration_builder['data'] = array();

			$registration_builder['allow_back_to_login'] = true;
			$registration_builder['registration_status'] = $registration_status['registration_status'];
			$registration_builder['vendor_storestatus'] = $data['vendor_storestatus']['storestatus'];
			$registration_builder['vendor_marketstatus'] = $data['vendor_marketstatus']['marketvendorstatus'];
	 		if(isset($register_form['registration_builder'])){
	 			$registration_builder['data'] = json_decode($register_form['registration_builder'],1);
	 		}
	 		
	 		if($registration_status['registration_status']){
	 			$data['register_fomm'] = $this->load->view('auth/user/templates/register_form',$registration_builder, true);
	 		}

			$childNorm = ($childPage == "login") ? "index" : $childPage;
	 		$page_name = $childNorm ? $childNorm : "index";
	 		$allowed_pages = ['index', 'register', 'forget-password', 'terms-of-use'];
	 		if($childPage && !in_array($page_name, $allowed_pages)) {
	 			redirect(base_url('/'));
	 			return;
	 		}

			/* ?tmp_theme=1..13 — views live under usercontrol/login/index{N}/ (same as custom_N themes) */
			if (ctype_digit((string) $front_template)) {
				$loginUser = $this->session->userdata('user');
				if (isset($loginUser['id']) && ($childPage == 'login' || $childPage == 'register')) {
					redirect(base_url().'usercontrol/dashboard');
					die();
				}
				$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
				$data['is_home'] = true;
				if (!$lang) {
					$lang = 1;
				}
				$data['language'] = $this->db->query("SELECT * FROM language WHERE status=1")->result_array();
				$data['language_selected'] = $this->db->query("SELECT * FROM language WHERE status=1 AND id=". (int)$lang)->row_array();
				$this->load->view('usercontrol/login/index'.$front_template.'/'.$page_name, $data);
				return;
			}

	 		$this->load->view('usercontrol/login/login/'.$page_name, $data);
		}
    }

	public function page($slug){
		$this->load->model("PagebuilderModel");
		$data['design'] = '';
		$data['title'] = '';
		$siteSetting = $this->Product_model->getSettings('site');

		$theme_page = array();
		if($this->login_settings['front_template']){
			$theme_page = $this->PagebuilderModel->getThemePageBySlug($this->login_settings['front_template'],urldecode($slug));
  
		 	if($theme_page){
				$temp_data['design'] = $theme_page['design'];
				$temp_data['title'] = $theme_page['meta_tag_title'];
				$temp_data['login'] = $this->login_settings;
				$temp_data['favicon'] = $siteSetting['favicon'];
				
				$data['design'] = $this->PagebuilderModel->parseTemplate($temp_data);
		 	}
		}
		
		if($theme_page){
			$this->load->view('usercontrol/login/login', $data);
		}else{
			show_404();
		}
	}

	public function verify_registeration($id)
	{
		$where = array('id'=>$id);
		$data = array(
			'plan_id'=>1,
			'status'=>$id,
			'reg_approved'=>$id,
		);
		$verify_user = $this->Common_model->update('users', $where, $data);
		if($verify_user)
		{
			redirect(base_url().'login');
		}
	}

	public function direct_login()
	{
		$where = array('type'=>'admin');
		$user_details_array = $this->Common_model->select_where_result('users', $where);
		$user_details_array = admin_user_with_permissions($user_details_array);
		$this->session->set_userdata(array('administrator'=>$user_details_array));
		redirect(base_url('admincontrol/dashboard'), 'location');
	}

	public function unsubscribe($email) {
		$this->load->model('PagebuilderModel');
		$emailSettings = $this->PagebuilderModel->getSettings('email');

		$data = array(
			'title'   => isset($emailSettings['unsubscribed_page_title']) ? $emailSettings['unsubscribed_page_title'] : null,
			'message' => isset($emailSettings['unsubscribed_page_message']) ? $emailSettings['unsubscribed_page_message'] : null,
			'email_encoded' => $email,
			'resubscribe_done' => false,
		);
		$decoded = base64_decode($email);
		if (empty($decoded) || !filter_var($decoded, FILTER_VALIDATE_EMAIL)) {
			$data['email_encoded'] = null;
			$this->load->view('unsubscribed_success_template', $data);
			return;
		}
		$unsbscribed = $this->db->get_where('unsubscribed_emails', ['email' => $decoded])->row();
		if (empty($unsbscribed)) {
			$this->db->insert('unsubscribed_emails', [
				'email'           => $decoded,
				'unsubscribed_at' => date('Y-m-d H:i:s'),
				'source'          => 'email_link',
			]);
		}
		$this->load->view('unsubscribed_success_template', $data);
	}

	public function resubscribe($encoded) {
		$encoded = str_replace(' ', '+', rawurldecode((string) $encoded));
		$this->load->model('PagebuilderModel');
		$emailSettings = $this->PagebuilderModel->getSettings('email');
		$data = array(
			'title'   => isset($emailSettings['unsubscribed_page_title']) ? $emailSettings['unsubscribed_page_title'] : null,
			'message' => isset($emailSettings['unsubscribed_page_message']) ? $emailSettings['unsubscribed_page_message'] : null,
			'email_encoded' => null,
			'resubscribe_done' => true,
		);
		$email = base64_decode($encoded);
		if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$data['title'] = __('user.success');
			$data['message'] = __('user.resubscribe_invalid_link');
			$this->load->view('unsubscribed_success_template', $data);
			return;
		}
		$this->db->delete('unsubscribed_emails', array('email' => $email));
		$data['title'] = __('user.resubscribed_success_title');
		$data['message'] = __('user.resubscribed_success_message');
		$this->load->view('unsubscribed_success_template', $data);
	}

	/**
	 * Public JSON for login/register live activity toasts (admin: Live Activity Pulse).
	 */
	public function login_marketing_activity() {
		$this->output->set_content_type('application/json');
		$this->load->helper('login_page_blocks');
		$login = $this->Product_model->getSettings('login');
		if (!login_page_block_enabled($login, 'block_live_pulse_enabled', null)) {
			echo json_encode(['ok' => false, 'items' => []]);
			return;
		}
		$pulse_cfg = login_page_live_pulse_settings_parse($login['block_live_pulse_settings'] ?? null);
		if (!empty($pulse_cfg['use_demo_content'])) {
			echo json_encode(['ok' => true, 'items' => login_page_live_pulse_demo_items(12)]);
			return;
		}
		$this->load->model('Login_stats_model');
		$items = $this->Login_stats_model->get_recent_activity(12);
		echo json_encode(['ok' => true, 'items' => $items]);
	}
}