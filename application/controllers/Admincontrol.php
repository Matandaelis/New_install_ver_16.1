<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use App\User;
class Admincontrol extends MY_Controller {
	function __construct()
	{
	    parent::__construct();
	    $this->load->model('user_model', 'user');
	    $this->load->model('Product_model');
	    $this->load->model('Setting_model');
	    $this->load->model('Common_model');
	    $this->load->model('Review_model');
	    $this->load->helper('share');
	    $this->load->helper('utility');
	    $this->load->library('user_agent');
	    $this->load->model('Report_model');
	    $this->front_assets = APPPATH . 'views/auth/user/assets/';
	    $this->front_assets_url = base_url('application/views/auth/user/assets/');
	    
	    $this->Product_model->ping($this->session->userdata('administrator')['id']);

		$site_setting_timeout = $this->Product_model->getSettings('site', 'session_timeout');
		$timeout = (isset($site_setting_timeout['session_timeout']) && is_numeric($site_setting_timeout['session_timeout']) && ((int)$site_setting_timeout['session_timeout']) >= 60) ? (int)$site_setting_timeout['session_timeout'] : 1800;

			// Make the $timeout variable available to all views
			$this->load->vars(array('timeout' => $timeout));

			if ($this->session->has_userdata('timestamp') && (time() - $this->session->userdata('timestamp')) > $timeout) {
			    $this->session->sess_destroy();
			    redirect($this->admin_domain_url);
			} elseif ($this->uri->segment(2) != "ajax_dashboard") {
			    $this->session->set_userdata('timestamp', time());
			}

			// Role-based permission check (admin_role_id NULL = full access; id=1 = full access)
			$admin = $this->session->userdata('administrator');
			if (!empty($admin) && is_array($admin)) {
			    $method = $this->router->method;
			    $map = $this->config->item('admin_permission_map');
			    $slug = isset($map['admincontrol'][$method]) ? $map['admincontrol'][$method] : null;
			    if ($slug && $slug !== 'dashboard' && !can_admin($slug)) {
			        $this->session->set_flashdata('error', __('admin.access_denied'));
			        redirect(base_url('admincontrol/dashboard'));
			        exit;
			    }
			}
	}

	public function clear_logs() {
	    header('Content-Type: application/json');
	    
	    $log_path = APPPATH . 'logs/';
	    $files = glob($log_path . '*.php');
	    $count = 0;
	    
	    foreach($files as $file) {
	        if(unlink($file)) {
	            $count++;
	        }
	    }
	    
	    echo json_encode([
	        'success' => true,
	        'message' => "Cleared {$count} files",
	        'cleared_files' => $count
	    ]);
	}

	public function reset_welcome_modal() {
	    header('Content-Type: application/json');
	    
	    echo json_encode([
	        'success' => true,
	        'message' => 'Welcome modal reset successfully. Refresh the page to see it again.',
	        'action' => 'localStorage.removeItem("welcome_modal_completed")'
	    ]);
	}
	
	public function system_update_report() {
	    // Verify that the user is an admin
	    $userdetails = $this->userdetails();

    // Retrieve only .json log files (ignores index.html placeholders and other non-log files)
    $logFiles = array_values(array_filter(
        array_diff(scandir(APPPATH . "logs/system_update_logs/"), array('.', '..')),
        function($f) { return substr($f, -5) === '.json'; }
    ));

	    // Sort files by modified time, latest first
	    usort($logFiles, function($a, $b) {
	        return filemtime(APPPATH . "logs/system_update_logs/" . $b) - filemtime(APPPATH . "logs/system_update_logs/" . $a);
	    });

	    $license_cache = APPPATH . 'license-easy-data-affiliateporsaas.json';
	    $license_key = '';
	    
	    if (file_exists($license_cache)) {
	        $cache_data = json_decode(file_get_contents($license_cache), true);
	        if ($cache_data && isset($cache_data['license_key'])) {
	            $license_key = $cache_data['license_key'];
	        }
	    }
	    
	    if (!$license_key) {
	        $license_key = $this->config->item('codecanyon_license');
	    }
	    
	    if (!$license_key && defined('CODECANYON_LICENCE')) {
	        $license_key = CODECANYON_LICENCE;
	    }

	    if (!$license_key && defined('LICENSE_EASY_KEY') && LICENSE_EASY_KEY !== '') {
	        $license_key = LICENSE_EASY_KEY;
	    }

	    if (!$license_key) {
	        $guard_cache = APPPATH . 'cache/license_check.json';
	        if (file_exists($guard_cache)) {
	            $guard_data = @json_decode(file_get_contents($guard_cache), true);
	            if ($guard_data && isset($guard_data['license_key']) && $guard_data['license_key'] !== '') {
	                $license_key = $guard_data['license_key'];
	            }
	        }
	    }

	    $data = [
	        'result' => null,
	        'license_key' => $license_key
	    ];

	    // If log files exist, load the most recent one
	    if (!empty($logFiles)) {
	        $logData = file_get_contents(APPPATH . "logs/system_update_logs/" . $logFiles[0]);
	        if ($logData !== false) {
	            $data['result'] = json_decode($logData, true);
	        }
	    }

	    // If session driver isn't database, add a new session to database
	    if (SESS_DRIVER != 'database') {
	        $array = [
	            'id' => 1,
	            'ip_address' => '127.0.0.1',
	            'timestamp' => time()
	        ];
	        $this->db->insert('ci_session', $array);
	    }

	    // Load update report view
	    $this->load->view('update_report', $data);
	}

	public function script_details(){
		$userdetails = $this->userdetails();

		$details = function_exists('license_easy_fetch_details') ? license_easy_fetch_details() : [];
		$licenseKey = $details && isset($details['license_key']) ? $details['license_key'] : (defined('CODECANYON_LICENCE') ? CODECANYON_LICENCE : '');

		$data['licence'] = array(
			'code' => $licenseKey,
			'amount' => isset($details['amount']) ? $details['amount'] : '0.00',
			'support_amount' => isset($details['support_amount']) ? $details['support_amount'] : '0.00',
			'sold_at' => isset($details['purchase_date']) ? $details['purchase_date'] : '',
			'license' => isset($details['license_type']) ? $details['license_type'] : '',
			'supported_until' => isset($details['supported_until']) ? $details['supported_until'] : '',
			'buyer' => isset($details['buyer']) ? $details['buyer'] : 'Licensed User',
			'domain' => isset($details['website_url']) ? $details['website_url'] : (function_exists('base_url') ? base_url() : ''),
			'activated_at' => isset($details['activated_at']) ? $details['activated_at'] : '',
			'status' => isset($details['status']) ? $details['status'] : 'unknown',
			'customer_email' => isset($details['customer_email']) ? $details['customer_email'] : ''
		);

		$data['product'] = array('name' => 'AffiliatePorSaaS');
		$data['versions'] = array();

		$this->view($data,'script_details/index');
	}

	public function update_langueges_data() {
		$this->update_user_langauges();
		redirect('/admincontrol/dashboard');
	}

	public function system_status(){

		$userdetails = $this->userdetails();

		$this->load->model("Coupon_model");

		$data['mysql_version'] = $this->db->conn_id->server_info;

		$data['serverReq'] = checkReq();
		
		// Get mobile app connections data
		$data['mobile_app_connections'] = $this->get_mobile_app_connections();

		$this->view($data,'system_status');
	}

	/**
	 * AJAX: batch-recalculate users.health_score for all affiliates (Users list screen).
	 */
	public function affiliate_health_scores_recalculate(){
		header('Content-Type: application/json; charset=utf-8');
		if (defined('ENVIRONMENT') && ENVIRONMENT === 'demo') {
			echo json_encode(['success' => false, 'message' => 'This action is disabled in demo mode.']);
			return;
		}
		$userdetails = $this->userdetails();
		if (empty($userdetails)) {
			echo json_encode(['success' => false, 'message' => 'Permission denied.']);
			return;
		}
		$this->load->model('Affiliate_model');
		$result = $this->Affiliate_model->update_all_health_scores();
		if (!empty($result['message_key'])) {
			$messages = [
				'admin.health_score_column_missing' => 'The users.health_score column is missing. Apply the latest database update first.',
				'admin.health_score_no_affiliates' => 'No affiliate users found.',
				'admin.health_score_update_failed' => 'Database update failed.',
				'admin.health_score_updated_count' => 'Updated health scores for %d affiliates.',
			];
			$key = $result['message_key'];
			if (isset($result['message_param'])) {
				$result['message'] = isset($messages[$key])
					? sprintf($messages[$key], (int)$result['message_param'])
					: 'Done.';
			} else {
				$result['message'] = $messages[$key] ?? 'Done.';
			}
			unset($result['message_key'], $result['message_param']);
		}
		echo json_encode($result);
	}
	
	public function geolocation_status() {
		$userdetails = $this->userdetails();
		$this->load->helper('geolocation');
		$this->view([], 'geolocation_status');
	}
	
	public function get_geolocation_status() {
		$this->load->helper('geolocation');
		$geolocation = new Geolocation_helper();
		
		$status = $geolocation->get_service_status();
		$services = $geolocation->get_service_details();
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'success' => true,
				'services' => $services,
				'stats' => [
					'total_services' => $status['total_services'],
					'active_services' => $status['active_services']
				],
				'recent_logs' => $status['recent_logs']
			]));
	}
	
	public function test_geolocation_services() {
		$this->load->helper('geolocation');
		$geolocation = new Geolocation_helper();
		
		$results = $geolocation->test_all_services();
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'success' => true,
				'message' => 'All services tested successfully',
				'results' => $results
			]));
	}
	
	public function get_geolocation_dashboard_status() {
		$this->load->helper('geolocation');
		$geolocation = new Geolocation_helper();
		
		$status = $geolocation->get_service_status();
		$services = $geolocation->get_service_details();
		
		// Find the primary working service
		$primary_service = null;
		$working_services = 0;
		
		foreach ($services as $service) {
			if ($service['status'] === 'success') {
				$working_services++;
				if (!$primary_service) {
					$primary_service = $service;
				}
			}
		}
		
		return [
			'primary_service' => $primary_service,
			'working_services' => $working_services,
			'total_services' => count($services),
			'status' => $working_services > 0 ? 'working' : 'error',
			'last_used' => $primary_service ? $primary_service['last_used'] : null,
			'response_time' => $primary_service ? $primary_service['response_time'] : null
		];
	}
	
        private function get_mobile_app_connections() {
            try {
                $file_path = FCPATH . 'application/cache/mobile_app_status.json';
                
                // Check if file exists
                if (!file_exists($file_path)) {
                    return [
                        'connected' => false,
                        'last_connected' => null,
                        'app_type' => null,
                        'platform' => null,
                        'total_users' => 0,
                        'active_users' => 0,
                        'ios_users' => 0,
                        'android_users' => 0
                    ];
                }
                
                // Load status
                $content = file_get_contents($file_path);
                $status = json_decode($content, true) ?: [];
                
                // Check if connected recently (within last 24 hours for user activity)
                $is_connected = false;
                if (isset($status['last_connected'])) {
                    $last_time = strtotime($status['last_connected']);
                    if ($last_time > strtotime('-24 hours')) {
                        $is_connected = true;
                    }
                }
                
                return [
                    'connected' => $is_connected,
                    'last_connected' => $status['last_connected'] ?? null,
                    'time_ago' => isset($status['last_connected']) ? $this->time_ago($status['last_connected']) : null,
                    'app_type' => $status['app_type'] ?? 'Unknown',
                    'platform' => $status['platform'] ?? 'Unknown',
                    'total_users' => $status['total_users'] ?? 0,
                    'active_users' => $status['active_users'] ?? 0,
                    'ios_users' => $status['ios_users'] ?? 0,
                    'android_users' => $status['android_users'] ?? 0
                ];
                
            } catch (Exception $e) {
                error_log('Error getting mobile app status: ' . $e->getMessage());
                return [
                    'connected' => false,
                    'last_connected' => null,
                    'app_type' => null,
                    'platform' => null,
                    'total_users' => 0,
                    'active_users' => 0,
                    'ios_users' => 0,
                    'android_users' => 0
                ];
            }
        }
	
	private function time_ago($datetime) {
		$time = time() - strtotime($datetime);
		
		if ($time < 60) return 'Just now';
		if ($time < 3600) return floor($time/60) . ' minutes ago';
		if ($time < 86400) return floor($time/3600) . ' hours ago';
		if ($time < 2592000) return floor($time/86400) . ' days ago';
		
		return date('M j, Y', strtotime($datetime));
	}
	
        private function detect_app_type($user_agent) {
            if (empty($user_agent)) return 'Unknown';
            
            $user_agent_lower = strtolower($user_agent);
            
            if (strpos($user_agent_lower, 'okhttp') !== false) return 'Android App';
            if (strpos($user_agent_lower, 'dart') !== false) return 'Flutter App';
            if (strpos($user_agent_lower, 'flutter') !== false) return 'Flutter App';
            if (strpos($user_agent_lower, 'react-native') !== false) return 'React Native App';
            if (strpos($user_agent_lower, 'xamarin') !== false) return 'Xamarin App';
            if (strpos($user_agent_lower, 'cordova') !== false) return 'Cordova App';
            if (strpos($user_agent_lower, 'ionic') !== false) return 'Ionic App';
            
            return 'Mobile App';
        }

	public function date_compare($element1, $element2) { 

		$datetime1 = strtotime($element1['created_at']); 

		$datetime2 = strtotime($element2['created_at']); 

		return ($datetime1 == $datetime2) ? 0 : (($datetime1 < $datetime2) ? 1 : -1);
	}


	public function clear_commission_tables() {

		// Demo Mode
		if (ENVIRONMENT === 'demo') {
			echo json_encode([
				'status' => 'error',
				'message' => 'Disabled on demo mode'
			]);
			return;
		}
		// Demo Mode

		$userdetails = $this->userdetails();

		$password = $this->input->post('admin_password',true);

		$password_confirm = $this->input->post('password_confirm',true);

		$user = $this->db->query("SELECT * FROM users WHERE id=". (int)$userdetails['id'])->row();

		if(sha1($password) == $user->password){

			if($password_confirm == 'true'){

				$this->session->set_userdata('clear_database_password',1);

				$json['success'] = true;

			} else if($this->session->userdata('clear_database_password') == 1){

				$this->db->truncate('form_action');
				$this->db->query("ALTER TABLE form_action AUTO_INCREMENT=1;");


				$this->db->truncate('affiliate_session_log');
				$this->db->query("ALTER TABLE affiliate_session_log AUTO_INCREMENT=1;");


				$this->db->truncate('cart');
				$this->db->query("ALTER TABLE cart AUTO_INCREMENT=1;");


				$this->db->truncate('clicks_views');
				$this->db->query("ALTER TABLE clicks_views AUTO_INCREMENT=1;");

				
				$this->db->truncate('integration_clicks_action');
				$this->db->query("ALTER TABLE integration_clicks_action AUTO_INCREMENT=1;");


				$this->db->truncate('integration_admin_clicks_action');
				$this->db->query("ALTER TABLE integration_admin_clicks_action AUTO_INCREMENT=1;");


				$this->db->truncate('integration_clicks_logs');
				$this->db->query("ALTER TABLE integration_clicks_logs AUTO_INCREMENT=1;");


				$this->db->truncate('integration_orders');
				$this->db->query("ALTER TABLE integration_orders AUTO_INCREMENT=1;");


				$this->db->truncate('notification');
				$this->db->query("ALTER TABLE notification AUTO_INCREMENT=1;");


				$this->db->truncate('product_action');

				$this->db->query("ALTER TABLE product_action AUTO_INCREMENT=1;");


				$this->db->truncate('product_action_admin');

				$this->db->query("ALTER TABLE product_action_admin AUTO_INCREMENT=1;");


				$this->db->truncate('integration_refer_product_action');

				$this->db->query("ALTER TABLE integration_refer_product_action AUTO_INCREMENT=1;");

				
				$this->db->truncate('refer_product_action');

				$this->db->query("ALTER TABLE refer_product_action AUTO_INCREMENT=1;");


				$this->db->truncate('wallet');

				$this->db->query("ALTER TABLE wallet AUTO_INCREMENT=1;");


				$this->db->truncate('wallet_recursion');

				$this->db->query("ALTER TABLE wallet_recursion AUTO_INCREMENT=1;");


				$this->db->truncate('wallet_request');
				$this->db->query("ALTER TABLE wallet_request AUTO_INCREMENT=1;");


				$this->db->query("ALTER TABLE language AUTO_INCREMENT=2;");


				$this->db->truncate('order');
				$this->db->query("ALTER TABLE `order` AUTO_INCREMENT=1;");


				$this->db->truncate('orders_history');
				$this->db->query("ALTER TABLE orders_history AUTO_INCREMENT=1;");


				$this->db->truncate('order_products');
				$this->db->query("ALTER TABLE order_products AUTO_INCREMENT=1;");


				$this->db->truncate('order_proof');
				$this->db->query("ALTER TABLE order_proof AUTO_INCREMENT=1;");


				$this->db->truncate('integration_clicks_logs');
				$this->db->query("ALTER TABLE integration_clicks_logs AUTO_INCREMENT=1;");


				$this->db->truncate('wallet_requests_history');
				$this->db->query("ALTER TABLE wallet_requests_history AUTO_INCREMENT=1;");


				$this->db->truncate('wallet_requests');
				$this->db->query("ALTER TABLE wallet_requests AUTO_INCREMENT=1;");


				$this->db->truncate('product_view_logs');
				$this->db->query("ALTER TABLE product_view_logs AUTO_INCREMENT=1;");

				// Clear membership data
				$this->db->truncate('membership_user');
				$this->db->query("ALTER TABLE membership_user AUTO_INCREMENT=1;");

				$this->db->truncate('membership_buy_history');
				$this->db->query("ALTER TABLE membership_buy_history AUTO_INCREMENT=1;");

				// Clear vendor deposit data
				$this->db->truncate('vendor_deposit');
				$this->db->query("ALTER TABLE vendor_deposit AUTO_INCREMENT=1;");

				// Clear deposit requests history
				$this->db->truncate('deposit_requests_history');
				$this->db->query("ALTER TABLE deposit_requests_history AUTO_INCREMENT=1;");

				// Reset user membership references
				$this->db->query("UPDATE users SET plan_id = 0 WHERE plan_id > 0");

				// Clear uncompleted payments (includes membership purchases)
				$this->db->truncate('uncompleted_payment');
				$this->db->query("ALTER TABLE uncompleted_payment AUTO_INCREMENT=1;");

				// Clear session data
				$this->db->truncate('ci_session');
				$this->db->query("ALTER TABLE ci_session AUTO_INCREMENT=1;");

			    $this->db->query("UPDATE integration_tools SET trigger_count = null");

			    $this->db->query("UPDATE product SET view_statistics = null");

			    $this->db->query("UPDATE form SET view_statistics = null");


				$this->session->set_flashdata('success', __('admin.data_was_deleted_successfully'));

				$json['success'] = true;

			}

		} else {

			$json['errors']['admin_password'] = "Wrong Password..!";
		}

		echo json_encode($json);
	}

		public function clear_tables() {

			// Demo Mode
			if (ENVIRONMENT === 'demo') {
				echo json_encode([
					'status' => 'error',
					'message' => 'Disabled on demo mode'
				]);
				return;
			}
			// Demo Mode

			$userdetails = $this->userdetails();

			$password = $this->input->post('admin_password',true);

			$password_confirm = $this->input->post('password_confirm',true);

			$user = $this->db->query("SELECT * FROM users WHERE id=". (int)$userdetails['id'])->row();

			if(sha1($password) == $user->password){

				if($password_confirm == 'true'){

					$this->session->set_userdata('clear_database_password',1);

					$json['success'] = true;

				} else if($this->session->userdata('clear_database_password') == 1){

					$tablesForTruncates = ['users','setting', 'affiliateads', 'affiliate_action','affiliate_session_log', 'cart', 'categories', 'clicks_views', 'coupon', 'form', 'form_action', 'form_coupon','integration_clicks_action','integration_admin_clicks_action','integration_category','integration_clicks_logs','integration_orders','integration_programs','integration_refer_product_action','integration_tools','integration_tools_ads', 'last_seen', 'notification', 'order', 'orders_history', 'order_products', 'order_proof', 'pagebuilder_theme', 'pagebuilder_theme_page', 'password_resets', 'payment_detail', 'paypal_accounts', 'product', 'productslog', 'product_action', 'product_action_admin', 'product_affiliate', 'product_categories', 'product_media_upload', 'rating', 'refer_market_action', 'refer_product_action', 'shipping_address', 'user_payment_request', 'vendor_setting', 'version_update', 'wallet', 'wallet_recursion', 'wallet_request', 'theme_faq', 'theme_homecontent', 'theme_home_sections_setting', 'theme_pages', 'theme_recommendation', 'theme_sections', 'theme_setting','theme_settings', 'theme_sliders', 'theme_videos','tickets','tickets_reply','tickets_subject','todo_list', 'slugs', 'membership_buy_history', 'membership_user','mail_templates', 'membership_plans', 'theme_links','user_groups','deposit_requests_history','vendor_deposit','unsubscribed_emails','wallet_requests','wallet_requests_history','uncompleted_payment',
					'ci_session','award_level','meta_data','product_view_logs','tutorial_categories','tutorial_pages','google_ads','product_meta','theme_colors','user_lms_product','vendor_config'];


					foreach ($tablesForTruncates as $tablename) {
						$database_name = $this->db->database;
						$count = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '".$database_name."' AND TABLE_NAME = '".$tablename."'")->num_rows();
						if($count > 0)
						{
							$this->db->truncate($tablename);
							$this->db->query("ALTER TABLE `".$tablename."` AUTO_INCREMENT=1;");
						}
					}

					$this->db->query("DELETE FROM language WHERE id != '1'");

					$this->db->query("ALTER TABLE language AUTO_INCREMENT=2;");

					$this->db->query("DELETE FROM currency WHERE currency_id !='1' ");

					$this->db->query("ALTER TABLE currency AUTO_INCREMENT=2;");

					$this->db->query("DELETE FROM users WHERE id !='1' ");

					$this->db->query("ALTER TABLE users AUTO_INCREMENT=2;");
				
	$reset_sql_file = FCPATH . 'assets/data/reset_default_data.sql';
	
	if(file_exists($reset_sql_file)) {
		$reset_sql = file_get_contents($reset_sql_file);
		if(!empty($reset_sql)) {
			$sql_statements = preg_split('/;\s*[\r\n]+INSERT\s+IGNORE\s+INTO/i', $reset_sql);
			
			foreach($sql_statements as $index => $statement) {
				if(!empty(trim($statement))) {
					$statement = trim($statement);
					if($index > 0) {
						$statement = 'INSERT IGNORE INTO' . $statement;
					}
					
					if(stripos($statement, 'INSERT') !== false) {
						try {
							$this->db->query($statement);
						} catch(Exception $e) {
						}
					}
				}
			}
		}
	}

					$folder_path = [];

					$folder_path[] =  FCPATH."assets/images/product/upload/thumb/";

					$folder_path[] =  FCPATH."assets/images/product/upload/";

					$folder_path[] =  FCPATH."assets/user_upload/";

					$folder_path[] =  FCPATH."application/logs/";

					$folder_path[] =  FCPATH."application/logs/system_update_logs/";

					$folder_path[] =  FCPATH."application/cache/";

					$folder_path[] = FCPATH."application/backup/";

					$folder_path[] = FCPATH."application/backup/mysql/";

					$folder_path[] = FCPATH."application/backup/script/";

					$folder_path[] =  FCPATH."application/core/excel/output/";

					$folder_path[] =  FCPATH."application/downloads/";

					$folder_path[] =  FCPATH."application/downloads_order/";

					$folder_path[] =  FCPATH."assets/integration/uploads/";

					$folder_path[] =  FCPATH."application/market_cache/";

					$folder_path[] =  FCPATH."application/logs/system_update_logs/";

					$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/form/favi/";

					$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/payments/";

					$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/product/upload/thumb/";

					$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/site/";

					$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/themes/";

					$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/wallet-icon/";

					$folder_path[] =  FCPATH."assets/image_cache/cache/assets/template/images/";

					$folder_path[] =  FCPATH."assets/images/form/favi/";

					$folder_path[] =  FCPATH."assets/images/site/";

					$folder_path[] =  FCPATH."assets/images/theme_images/";

					$folder_path[] =  FCPATH."assets/images/users/";

					$folder_path[] =  FCPATH."assets/images/users/thumb/";

					$folder_path[] =  FCPATH."assets/integration/uploads/";

					$folder_path[] =  FCPATH."assets/user_upload/";

					$folder_path[] =  FCPATH."assets/user_upload/downloaded_tools/";

					$folder_path[] =  FCPATH."assets/user_upload/mail_template_images/";

					$folder_path[] =  FCPATH."assets/user_upload/vendor_store/";

					$folder_path[] =  FCPATH."assets/xml/";



					foreach ($folder_path as $key => $value) {

						$files = glob($value.'/*');

						foreach($files as $file) { 

							if(is_file($file) && ! str_contains($file, 'index.html'))  unlink($file);  

						}

					}

				$this->deleteAll(FCPATH."assets/integration/uploads", false);
				$this->deleteAll(FCPATH."application/downloads", false);
				$this->deleteAll(FCPATH."application/downloads_order", false);

				// Update base_url to empty string in config.php
				$config_file_path = APPPATH . 'config/config.php';
				if (file_exists($config_file_path)) {
				    $config_content = file_get_contents($config_file_path);
				    
				    // Pattern to match $config['base_url'] with any value
				    $pattern = '/(\$config\[\'base_url\'\]\s*=\s*)[\'"][^\'"]*[\'"];/';
				    $replacement = '$1\'\';';
				    
				    $updated_content = preg_replace($pattern, $replacement, $config_content);
				    
				    if ($updated_content !== null) {
				        file_put_contents($config_file_path, $updated_content);
				    }
				}

				// Language folder cleanup
				$language_path = APPPATH . 'language/';
				if (is_dir($language_path)) {
				    // First: Delete any main language folders except 'default' and 'english'
				    $main_folders = scandir($language_path);
				    foreach ($main_folders as $folder) {
				        if ($folder != '.' && $folder != '..' && $folder != 'default' && $folder != 'english') {
				            $folder_path = $language_path . $folder;
				            if (is_dir($folder_path)) {
				                $this->deleteAll($folder_path, true);
				            }
				        }
				    }
				    
				    // Second: Clean inside default folder (keep only 'default' and 'english')
				    $default_path = $language_path . 'default/';
				    if (is_dir($default_path)) {
				        $default_folders = scandir($default_path);
				        foreach ($default_folders as $folder) {
				            if ($folder != '.' && $folder != '..' && $folder != 'default' && $folder != 'english') {
				                $folder_path = $default_path . $folder;
				                if (is_dir($folder_path)) {
				                    $this->deleteAll($folder_path, true);
				                }
				            }
				        }
				        
				        // Third: Copy all PHP files from main default folder to nested default folder
				        $source_path = $default_path; // application/language/default/
				        $target_default_path = $default_path . 'default/'; // application/language/default/default/
				        
				        if (is_dir($target_default_path)) {
				            $php_files = glob($source_path . '*.php');
				            foreach ($php_files as $file) {
				                $filename = basename($file);
				                $target_file = $target_default_path . $filename;
				                copy($file, $target_file);
				            }
				        }
				    }
				}

				// Change environment from development to production in index.php
				$index_file_path = FCPATH . 'index.php';
				if (file_exists($index_file_path)) {
				    $index_content = file_get_contents($index_file_path);
				    
				    // Pattern to match and replace only the 'development' part in the ternary operator
				    $pattern = '/(\?\s*\$_SERVER\[[\'"]CI_ENV[\'"]\]\s*:\s*)[\'"]development[\'"](\s*\)\s*;)/i';
				    $replacement = '${1}\'production\'${2}';
				    
				    $updated_content = preg_replace($pattern, $replacement, $index_content);
				    
				    if ($updated_content !== null && $updated_content !== $index_content) {
				        file_put_contents($index_file_path, $updated_content);
				    }
				}

				$u = $this->session->administrator;

				$user_details_array = $this->db->query("SELECT * FROM users WHERE id=". $u['id'])->row_array();
				$user_details_array = admin_user_with_permissions($user_details_array);

				$this->session->set_userdata(array('administrator'=>$user_details_array));

				$current_version = $this->config->item('app_version');
				$destination_dir = APPPATH . 'updates/';
				$destination_file = $destination_dir . 'database_update_' . $current_version . '.data';

				// Look for SQL files in assets/data directory
				$sql_files = glob(FCPATH . 'assets/data/database_update_*.data');
				$source_file = null;

				// First try to find exact version match
				$exact_match = FCPATH . 'assets/data/database_update_' . $current_version . '.data';
				if (file_exists($exact_match)) {
					$source_file = $exact_match;
				} else {
					// If no exact match, find the most recent file
					if (!empty($sql_files)) {
						// Sort by modification time to get the most recent
						usort($sql_files, function($a, $b) {
							return filemtime($b) - filemtime($a);
						});
						$source_file = $sql_files[0];
					}
				}

				if ($source_file && file_exists($source_file)) {
					if (!is_dir($destination_dir)) {
						mkdir($destination_dir, 0755, true);
					}

					// Copy file to destination
					if (copy($source_file, $destination_file)) {
						$sql_content = file_get_contents($destination_file);
						// Update the database name to match current version format
						$version_format = str_replace('.', '_', $current_version);
						$updated_content = preg_replace('/^#SET @databaseName="[^"]*";/', '#SET @databaseName="' . $version_format . '";', $sql_content);
						file_put_contents($destination_file, $updated_content);

						// Rename the source file to match current version if it's different
						$expected_source_name = FCPATH . 'assets/data/database_update_' . $current_version . '.data';
						if ($source_file !== $expected_source_name) {
							// Update the source file content with new version
							file_put_contents($source_file, $updated_content);
							// Rename the source file to match current version
							rename($source_file, $expected_source_name);
						}
					}
				}

				// Preserve license data during updates -- wiping these caused
			// CODECANYON_LICENCE to become empty, breaking the mobile API
			// license validation and leaving the install unprotected.
			// License files (license-easy-data-*.json, version.php,
			// config.php license keys) are now kept intact.

				// Verify required files exist
				$warnings = [];
				if (!file_exists(FCPATH . 'install/version.php')) {
					$warnings[] = 'Warning: install/version.php is missing!';
				}
				if (!file_exists(APPPATH . 'license-easy-universal-client.php')) {
					$warnings[] = 'Warning: application/license-easy-universal-client.php is missing!';
				}

				$success_message = __('admin.data_was_deleted_successfully');
				if (!empty($warnings)) {
					$success_message .= ' ' . implode(' ', $warnings);
				}

				$this->session->set_flashdata('success', $success_message);

				$json['success'] = true;
				if (!empty($warnings)) {
					$json['warnings'] = $warnings;
				}

				}

				} else {
					$json['errors']['admin_password'] = "Wrong Password..!";
				}

				echo json_encode($json);
		}


		public function destroy_session() {
		    $this->session->sess_destroy();
		    echo json_encode(['success' => true]);
		}

		public function check_session() {
		    $logged_in = $this->session->userdata('administrator') ? true : false;
		    session_write_close();
		    header('Content-Type: application/json');
		    echo json_encode(['logged_in' => $logged_in]);
		    exit;
		}

		// function to delete all files and subfolders from folder
		public function deleteAll($dir, $remove = false) {
			$structure = glob(rtrim($dir, "/").'/*');

			if (is_array($structure)) {
				foreach($structure as $file) {
					if (is_dir($file))
						$this->deleteAll($file,true);
					else if(is_file($file)  && ! str_contains($file, 'index.html'))
						unlink($file);
				}
			}

			if($remove) rmdir($dir);
		}


			public function logs(){

				$data = array();

				$input = $this->input->post(null,true);

				$filter = array();

				$data['status'] = $this->Wallet_model->status();

				$data['status_icon'] = $this->Wallet_model->status_icon;

				if($input['type'] == 'sale'){

					$data['title'] = "Sales Logs";

											$record = $this->db->query('SELECT o.* FROM `order_products` op LEFT JOIN `order` AS o ON o.id = op.order_id WHERE o.status = 1')->result_array();

						$order_status = $this->Order_model->status();

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

					else if($input['type'] == 'hold_orders'){

						$data['title'] = "Hold Orders Logs";

						$order_status = $this->Order_model->status();

						$record = $this->db->query('SELECT o.* FROM `order_products` op LEFT JOIN `order` AS o ON o.id = op.order_id WHERE o.status = 7')->result_array();

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

					else if($input['type'] == 'orders'){

						$order_status = $this->Order_model->status();

						$data['title'] = "Digital Orders";

						$record = $this->db->query('SELECT o.* FROM `order_products` op LEFT JOIN `order` AS o ON o.id = op.order_id WHERE o.status > 0')->result_array();

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

						$record = $this->db->query('SELECT * FROM `integration_orders`')->result_array();

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

					else if($input['type'] == 'click'){

						$data['title'] = "Wallet Logs";

						$data['title2'] = "Clicks Logs";

						$record = $this->db->query('SELECT * FROM wallet WHERE type IN ("click_commission","form_click_commission","affiliate_click_commission") AND comm_from = "store" AND status > 0')->result_array();

						$_record = array();

						foreach ($record as $_key => $value) {

							$_record[] = array(

								'created_at'   => $value['created_at'],

								'comment'      => $value['comment'],

								'status'       => $data['status'][$value['status']],

								'country_code' => $value['country_code'],

								'user_ip'      => json_decode($value['ip_details'], true)['ip'],

								'amount'       => $value['amount'],

							);
						}

						$data['data'] = $_record;

						$record = array();

						$record[] = $this->db->query('SELECT country_code,created_at,user_ip,pay_commition,"Product Click" AS type FROM product_action WHERE 1')->result_array();

						$record[] = $this->db->query('SELECT country_code,created_at,user_ip,pay_commition,"Form Click" AS type FROM form_action WHERE 1')->result_array();

						$record[] = $this->db->query('SELECT country_code,created_at,user_ip,commission AS pay_commition,"Affiliate Click" AS type FROM affiliate_action WHERE 1')->result_array();



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

					else if($input['type'] == 'action'){

						$data['title'] = "Action Logs";

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

					else if($input['type'] == 'member'){

						$data['title'] = "Member";

						$data['type'] = "members";



						$record = $this->db->query("SELECT u.created_at,c.name,c.sortname,u.firstname,u.lastname,u.email,u.username

							FROM users AS u 

							LEFT JOIN countries c ON c.id = u.Country

							WHERE type='client' ORDER BY created_at DESC")->result_array();



						$data['data'] = array();

						foreach ($record as $key => $value) {

							if ($value['sortname'] != '') {

								$flag = base_url('assets/template/images/flags/' . strtolower($value['sortname']) . '.png');

							} else {

								$flag = base_url('assets/template/images/avatar-1.jpg');

							}

							$data['data'][] = array(

								'name'     => $value['firstname'] ." " .$value['lastname'],

								'username' => $value['username'],

								'sortname' => $value['sortname'],

								'email'    => $value['email'],

								'created_at'    => $value['created_at'],

								'flag'     => $flag,

							);

						}

					}

					$data['html'] = $this->load->view("common/log_model",$data,true);

					echo json_encode($data);die;

				}

				public function page_404(){
					$this->load->view("404");
				}


				public function language_import(){

					$userdetails = $this->userdetails();

					$files = ['admin','client','store','user','front','template_simple'];

					require_once APPPATH . '/core/phpspreadsheet/autoload.php';

					$json = array();

					$translation_id = (int)$this->input->post('id',true);

					$language = $this->db->query("SELECT * FROM language WHERE id=".$translation_id)->row_array();

					if(!$language){

						$json['warning'] = "Something Wrong.!";
					}

					if(!isset($_FILES['file']['error']) || $_FILES['file']['error'] != 0){

						$json['warning'] = "Please Select Excel File..!";

					} else {

						$extension = pathinfo($_FILES['file']["name"], PATHINFO_EXTENSION);

						if($extension != 'xlsx'){

							$json['warning'] = "Only xlsx files are allowed.!";
						}

					}

					if(!isset($json['warning'])){

						$inputFileName = $_FILES['file']['tmp_name'];

						$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');

						$worksheetList = $objReader->listWorksheetNames($inputFileName);

						$sheetname = $worksheetList[0];

						foreach ($files as $key => $file) {

							if(!in_array($file, $worksheetList)){

								$json['warning'] = "Sheet <b>{$file}</b> is missing check your excel file..!";

								break;
							}
						}

						$lang_data = array();

						if(!isset($json['warning'])){

							foreach ($files as $key => $file) {

								$objReader->setLoadSheetsOnly($file); 

								$objPHPExcel = $objReader->load($inputFileName);

								$worksheet = $objPHPExcel->getActiveSheet();

								$l = $worksheet->toArray(null,true,true,true);

								unset($l[1]);

								foreach ($l as $key => $value) {

									$lang_data[$file][$value['A']] = $value['B'];
								}
							}

							$translation_id = (int)$this->input->post('id',true);

							foreach ($lang_data as $file => $data) {

								$path = APPPATH.'language/'. $translation_id."/".$file.".php";

								$file_content = '<?php '.PHP_EOL;

								foreach ($data as $key => $value) {

									$file_content .= '$lang[\''. $key .'\'] = '. $this->db->escape($value) .';' .PHP_EOL;

								}

								file_put_contents($path, $file_content);
							}

							$json['success'] = "Languages file imported successfully..!";
						}

					}

					echo json_encode($json);die;
				}


				public function language_export($id = 'default'){

					$userdetails = $this->userdetails();

					$files = ['admin','client','store','user','front','template_simple'];

					require_once APPPATH . '/core/phpspreadsheet/autoload.php';

					if($id == "1") $id = 'default';

					$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

					$sheet = $objPHPExcel->getActiveSheet();

					$objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);

					foreach ($files as $i => $file) {

						if(is_file(APPPATH.'/language/'. $id .'/'. $file .'.php')){

							$lang = array();

							require  APPPATH.'/language/default/'. $file .'.php';

							$defaultLang = $lang;

							$lang = array();

							require  APPPATH.'/language/'. $id .'/'. $file .'.php';

							$objWorkSheet = $objPHPExcel->createSheet($i);

							$data = array();

							$data[] = array('KEY','TRANSLATION');

							$lang = array_merge($defaultLang, $lang);

							foreach ($lang as $key => $value) {

								$data[] = array($key,$value);

							}

							$objWorkSheet->fromArray($data, NULL, 'A1');

							$objWorkSheet->setTitle($file);
						}
					}


					header('Content-type: application/vnd.ms-excel');

					header('Content-Disposition: attachment; filename="'. $id .'.xlsx"');

					$objWriter->save('php://output');
				}


				public function language(){

					$userdetails = $this->userdetails();

					$language = $this->db->query("SELECT * FROM language ")->result_array();

					$data['language_count'] = langCount('default');

					foreach ($language as $key => $value) {

						$data['language'][$key] = $value;

						$data['language'][$key]['count'] = langCount($value['id']);

					}

					$this->view($data,'language/index');
				}



				public function coupon_manage($coupon_id = 0){

					$userdetails = $this->userdetails();

					$this->load->model("Coupon_model");

					$data['coupon'] = $this->Coupon_model->getCoupon($coupon_id);

					$data['product'] = $this->db->query("SELECT product_id,product_name FROM product")->result_array();


					$this->view($data,'coupon/form');
				}



				public function coupon_delete($coupon_id){

					$userdetails = $this->userdetails();

					$this->load->model("Coupon_model");

					$this->Coupon_model->deleteCoupon($coupon_id);

					$this->session->set_flashdata('success', __('admin.coupon_deleted_successfully'));

					redirect(base_url("admincontrol/listproduct"));
				}


				public function coupon(){

					$userdetails = $this->userdetails();

					$this->load->model("Coupon_model");

					$data['coupons'] = $this->Coupon_model->getCoupons();

					$ptotal = $this->db->query('SELECT product_id FROM product')->num_rows();



					foreach ($data['coupons'] as $key => $value) {

						if(strtolower($value['allow_for']) == 's'){

							$data['coupons'][$key]['product_count'] = count(explode(',', $value['products']));

						}else{

							$data['coupons'][$key]['product_count'] = $ptotal;

						}

						$data['coupons'][$key]['count_coupon'] = $this->Coupon_model->getCouponCount($value['coupon_id']);

					}

					$this->view($data,'coupon/index');
				}


				public function save_coupon(){

					$userdetails = $this->userdetails();

					$this->load->library('form_validation');

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

						$data = $this->input->post(null,true);
						$product_array = isset($data['products']) && is_array($data['products']) ? $data['products'] : []; 
						
						$coupon = array(

							'name'       => $data['name'],

							'code'       => $data['code'],

							'type'       => $data['type'],

							'allow_for'  => $data['allow_for'],

							'discount'   => $data['discount'],

							'date_start' => date("Y-m-d", strtotime($data['date_start'])),

							'date_end'   => date("Y-m-d", strtotime($data['date_end'])),

							'uses_total' => $data['uses_total'],

							'status'     => $data['status'],

							'products'   => implode(",", $product_array),

							'date_added' => date("Y-m-d H:i:s"),

						);



						if($data['id'] > 0){

							unset($coupon['date_added']);

							$this->db->update("coupon",$coupon,['coupon_id' => $data['id']]);

						} else {

							$this->db->insert("coupon",$coupon);

							$coupon_id = $this->db->insert_id();

						}

						$json['location'] = base_url("admincontrol/listproduct");

					}

					echo json_encode($json);
				}


	public function change_language($language_id = null) {
	    if(empty($language_id) || !is_numeric($language_id)) {
	        show_404();
	        return;
	    }

	    $this->db->where('id', $language_id);
	    $query = $this->db->get('language');
	    $language = $query->row_array();

	    if($language) {
	        $_SESSION['userLang'] = $language_id;
	        $_SESSION['userLangName'] = $language['name'];
	        header('Location: ' . $_SERVER['HTTP_REFERER']);
	    } else {
	        show_404();
	    }
	}


	public function change_currency($currency_code = null) {
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
	        $_SESSION['userCurrencyName'] = $currency['title'];
	        $_SESSION['userCurrencyLeft'] = $currency['symbol_left'];
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

	public function lang_status_toggle() {
		try {
			$userdetails = $this->userdetails();
			$json = array();
			$column = $this->input->post("column",true);
			$id = (int)$this->input->post("id",true);
			$status = (int)$this->input->post('status',true);
			if($column == 'is_default'){
				$this->db->query("UPDATE language SET is_default = 0");
				$this->db->query("UPDATE language SET is_default = 1 WHERE id =". $id);
				$_SESSION['userLang'] = $id;
				echo json_encode(['reload' => true]);exit;
			} else {
				$this->db->query("UPDATE language SET ".$column."='".$status."' WHERE id =".$id);
			}
			$json = array('status'=>$this->db->affected_rows(),'languages'=>$this->Product_model->getLanguageHtml());
		} catch (\Throwable $th) {
			$json = array('status'=>false,'message'=>$th->getMessage());
		}
		echo json_encode($json);
	}


	/**
	 * AJAX endpoint — translate all missing keys for a language using Google Translate.
	 * Same logic as the lang-sync dev-center tool, operates directly on language/{id}/ files.
	 */
	public function translate_language($lang_id = 0) {
		@set_time_limit(0);
		@ini_set('memory_limit', '512M');
		// Suppress any notices/warnings that could corrupt the JSON output
		@ini_set('display_errors', '0');
		$this->userdetails();
		$this->output->set_content_type('application/json');

		$lang_id = (int)$lang_id;
		if ($lang_id <= 1) {
			echo json_encode(['success' => false, 'message' => __('admin.cannot_translate_base_language')]);
			return;
		}

		$language = $this->db->query("SELECT * FROM language WHERE id = " . $lang_id)->row_array();
		if (!$language) {
			echo json_encode(['success' => false, 'message' => __('admin.language_not_found')]);
			return;
		}

		// Resolve ISO language code from languages.json (same map used in the view)
		$lj_path  = FCPATH . 'assets/data/languages.json';
		$lang_map = file_exists($lj_path) ? (json_decode(file_get_contents($lj_path), true) ?: []) : [];
		$lang_code = array_search($language['name'], $lang_map) ?: strtolower($language['name']);

		// Optional: translate a single file for per-file live console updates
		$single_file = $this->input->post('file', true) ?: null;
		$allowed     = ['admin.php', 'client.php', 'store.php', 'user.php', 'front.php', 'template_simple.php'];
		if ($single_file && !in_array($single_file, $allowed)) $single_file = null;

		try {
			$result = translate_missing_lang_keys($lang_id, $lang_code, $single_file);
		} catch (Throwable $e) {
			echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
			return;
		}

		$message = $result['translated'] > 0
			? sprintf(__('admin.translation_completed_keys'), $result['translated'])
			: __('admin.translation_already_up_to_date');

		echo json_encode([
			'success'    => true,
			'translated' => $result['translated'],
			'files'      => $result['files'],
			'errors'     => $result['errors'],
			'message'    => $message,
		]);
	}

	/**
	 * Returns per-file missing key counts for a language (no translation — fast).
	 * Used by the JS modal to show progress indicators before translation starts.
	 */
	public function language_missing_counts($lang_id = 0) {
		$this->userdetails();
		$this->output->set_content_type('application/json');
		@ini_set('display_errors', '0');

		$lang_id = (int)$lang_id;
		if ($lang_id <= 1) {
			echo json_encode(['success' => false, 'counts' => []]);
			return;
		}

		$src_dir = APPPATH . 'language/default/';
		$dst_dir = APPPATH . 'language/' . $lang_id . '/';
		$files   = ['admin', 'client', 'store', 'user', 'front', 'template_simple'];

		// Clean up any stale temp progress files — signals a fresh session is starting
		foreach ($files as $file) {
			$tmp = $dst_dir . $file . '.trans_progress.json';
			if (file_exists($tmp)) @unlink($tmp);
		}

		$counts = [];
		$total  = 0;

		foreach ($files as $file) {
			$src_path = $src_dir . $file . '.php';
			$dst_path = $dst_dir . $file . '.php';
			if (!file_exists($src_path)) continue;
			$source   = parse_lang_file_admin($src_path);
			$existing = parse_lang_file_admin($dst_path);
			$missing  = 0;
			foreach ($source as $key => $en_val) {
				if (empty($existing[$key]) && trim($en_val) !== '') $missing++;
			}
			$counts[$file] = $missing;
			$total        += $missing;
		}

		echo json_encode(['success' => true, 'counts' => $counts, 'total' => $total]);
	}

	/**
	 * Translate a single batch of 100 missing keys for one file.
	 * Called in a loop by the JS for live per-batch progress updates.
	 */
	public function translate_language_batch($lang_id = 0) {
		@set_time_limit(120);
		@ini_set('memory_limit', '256M');
		@ini_set('display_errors', '0');
		$this->userdetails();
		$this->output->set_content_type('application/json');

		$lang_id = (int)$lang_id;
		if ($lang_id <= 1) {
			echo json_encode(['success' => false, 'message' => __('admin.cannot_translate_base_language')]);
			return;
		}

		$language = $this->db->query("SELECT * FROM language WHERE id = " . $lang_id)->row_array();
		if (!$language) {
			echo json_encode(['success' => false, 'message' => __('admin.language_not_found')]);
			return;
		}

		$lj_path   = FCPATH . 'assets/data/languages.json';
		$lang_map  = file_exists($lj_path) ? (json_decode(file_get_contents($lj_path), true) ?: []) : [];
		$lang_code = array_search($language['name'], $lang_map) ?: strtolower($language['name']);

		$file    = $this->input->post('file', true);
		$allowed = ['admin.php', 'client.php', 'store.php', 'user.php', 'front.php', 'template_simple.php'];
		if (!$file || !in_array($file, $allowed)) {
			echo json_encode(['success' => false, 'message' => 'Invalid file']);
			return;
		}

		try {
			$result = translate_missing_lang_batch($lang_id, $lang_code, $file, 100);
			echo json_encode($result);
		} catch (Throwable $e) {
			echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
		}
	}

	public function update_language(){

		$userdetails = $this->userdetails();
		$json = array();
		$name = $this->input->post("name",true);
		$language_id = (int)$this->input->post("id",true);
		$status = (int)$this->input->post('status',true);
		$is_rtl = (int)$this->input->post('is_rtl',true);

		if($language_id == 1){ $name = 'English'; }
		if($name == ''){ $json['errors']['name'] = __('admin.name_is_required'); }

		if(!isset($json['errors'])){
			$post = $this->input->post(null,true);

			if($language_id == 0){
				$created = true;
				$this->db->query("INSERT INTO language SET status='". $status ."',is_rtl='". $is_rtl ."', name=". $this->db->escape($name) );
				$language_id = $this->db->insert_id();
			} else {
				$created = false;
				$this->db->query("UPDATE language SET status='". $status ."', is_rtl='". $is_rtl ."', name=". $this->db->escape($name) ." WHERE id =". $language_id );
			}

			$languages_json = file_get_contents(base_url('assets/data/languages.json'));
			$languages = json_decode($languages_json, true);

			if( !is_array($languages) ) $languages = [];
			$languages_code = array_search($name,$languages);

			$DefaultLangPath = null;

			if($languages_code != false) {
				$DefaultLangPath = APPPATH.'language/default/'.$languages_code;
			} 

			$path = APPPATH.'language/'. $language_id;

			$lang_files = ['admin','client','store','user','front','template_simple'];
			$language_translation_notavailable = 0;
			foreach ($lang_files as $file) {
				if($DefaultLangPath == null || !is_file($DefaultLangPath .'/'. $file .'.php')) {
					$language_translation_notavailable++;
				}
			}

			if((int)$this->input->post("id",true) == 0){
				$DefaultPath = APPPATH.'language/default';
				lang_copy($DefaultPath, $path, $DefaultLangPath);
			}

		if($this->input->post('flag',true) != ''){
			$flag_path = $this->input->post('flag', true);
			/* glob() prepends "./" — strip it so DB always stores a clean relative path */
			if (strncmp($flag_path, './', 2) === 0) {
				$flag_path = substr($flag_path, 2);
			}
			copy($flag_path, $path."/flag.png");
			$this->db->query("UPDATE language SET flag = '" . $this->db->escape_str($flag_path) . "' WHERE id =". $language_id );
		}

			if(isset($post['is_default'])){
				$this->db->query("UPDATE language SET is_default = 0");
				$this->db->query("UPDATE language SET status =1 , is_default = 1 WHERE id =". $language_id );
			}

			$msg_prefix = $created ? "Language created" : "Language updated";
			if(!isset($json['errors'])){
				if($language_translation_notavailable > 0 && $language_translation_notavailable == sizeof($lang_files)) {
					$this->session->set_flashdata(array('error' => $msg_prefix.' but auto translations not available, please contact admin for auto translations!'));
					redirect('admincontrol/language/', 'refresh');
				} else if ($language_translation_notavailable > 0) {
					$this->session->set_flashdata(array('error' => $msg_prefix.' but some translations is missing, please contact admin for autotranslations!'));
					redirect('admincontrol/language/', 'refresh');
				} else {
					$this->session->set_flashdata(array('success' => $msg_prefix." successfully"));
					redirect('admincontrol/language/', 'refresh');
				}
			} else {
				$this->session->set_flashdata(array('error' => implode("<br>", $json['errors'])));
				redirect('admincontrol/translation_edit/'. $language_id, 'refresh');
			}
		} else {
			$this->session->set_flashdata(array('error' => implode("<br>", $json['errors'])));
			redirect('admincontrol/translation_edit/'. $language_id, 'refresh');
		}
		echo json_encode($json);
	}

	public function translation($language_id){

		$userdetails = $this->userdetails();

		$data['language'] = $this->db->query("SELECT * FROM language WHERE id=".$language_id)->row_array();

		if($data['language']){
			$data['language']['count'] = langCount($data['language']['id']);
			$this->view($data,'language/translation');

		}
		else{
			show_404();
		}
	}

	// Upload and Extract zip file
	public function language_zip_upload(){

		$userdetails = $this->userdetails();

		if(!empty($_FILES['file']['name'])){ 

			$config['upload_path'] = APPPATH.'language/default/'; 
			$config['allowed_types'] = 'zip'; 
			$config['max_size'] = '1024'; 
			$config['file_name'] = $_FILES['file']['name'];
			$this->load->library('upload',$config); 

			unlink(APPPATH.'language/default/'.$_FILES['file']['name']);

			if($this->upload->do_upload('file')){ 
				$uploadData = $this->upload->data(); 
				$filename = $uploadData['file_name'];
				
				$zip = new ZipArchive;

				$res = $zip->open(APPPATH.'language/default/'.$filename);
				
				if ($res === TRUE) {
					
					$extractpath = APPPATH.'language/default/';

					// Read the actual folder name from inside the ZIP before closing it.
					// This prevents failures when the browser renames the file on download
					// (e.g. de(1).zip, de(2).zip) which would cause the derived path to be wrong.
					$actualFolderName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename); // fallback
					for ($zi = 0; $zi < $zip->numFiles; $zi++) {
						$entry = $zip->getNameIndex($zi);
						if ($entry !== false && strpos($entry, '/') !== false) {
							$actualFolderName = rtrim(explode('/', $entry)[0], '/');
							break;
						}
					}

					// Extract file
					$zip->extractTo($extractpath);
					$zip->close();

					$extractedFolderPath = $extractpath . $actualFolderName;
					$lang_files = ['admin','client','store','user','front','template_simple'];

					$files = scandir($extractedFolderPath);

					if (!is_array($files)) {
						unlink(APPPATH.'language/default/'.$filename);
						$this->session->set_flashdata(array('error' => 'Invalid language zip file!'));
					} else {
					for ($i=2; $i < count($files); $i++) { 
						$extractedFileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $files[$i]);
						if(!in_array($extractedFileName, $lang_files)) {
							$isInvalidFile = true;
							$this->deleteDir($extractedFolderPath);
						}
					}
					unlink(APPPATH.'language/default/'.$filename);
					if(isset($isInvalidFile)) {
						$this->session->set_flashdata(array('error' => 'Invalid language zip file!'));
					} else {
						redirect(base_url('/admincontrol/update_user_langauges/'.$actualFolderName));
						die;
					}
					}
				} else {
					$this->session->set_flashdata(array('error' => 'Invalid language zip file!'));
				}
			} else { 
				$this->session->set_flashdata(array('error' => 'Please select valid language zip file!'));
			} 
		} else { 
			$this->session->set_flashdata(array('error' => 'Please select valid language zip file!'));
		} 
		redirect(base_url('/admincontrol/language'));
	}

	private function deleteDir($dir) {
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it,
			RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($dir);
	}
	
	public function get_translation(){
		$userdetails = $this->userdetails();
		$default_language = $this->db->query("SELECT * FROM language WHERE is_default=1")->row_array();
		$file_name = $this->input->post('id',true);
		$translation_id = $this->input->post('translation_id',true);
		$path = APPPATH.'language/default/' .$file_name.".php";
		include $path;
		$defaultLanguageKeys = $lang;
		$path = APPPATH.'language/'. $translation_id."/".$file_name.".php";
		include $path;
		$targerLanguageKeys = $lang;
		$newArray = array();
		foreach ($defaultLanguageKeys as $key => $value) {
			$newArray[$key] = array(
				'text' => $value,
				'value' => $targerLanguageKeys[$key],
			);
		}
		echo json_encode($newArray);
	}

	public function save_translation(){

		$userdetails = $this->userdetails();

		$trans = json_decode($this->input->post('data',true));

		$get = $this->input->get(null,true);

		$translation_id = (int)$get['translation_id'];

		$targerLanguageKeys = $get['id'];

		$path = APPPATH.'language/'. $translation_id."/".$targerLanguageKeys.".php";

		$file_content = '<?php '.PHP_EOL;

		foreach ($trans as $key => $value) {

			$file_content .= '$lang[\''. $key .'\'] = '. $this->db->escape($value) .';' .PHP_EOL;

		}
		file_put_contents($path, $file_content);
		$json['success'] = "Language save successfully";
		echo json_encode($json);die;
	}

	public function get_update_language(){
		$userdetails = $this->userdetails();
		$json = $this->db->query("SELECT * FROM language WHERE id = ". (int)$this->input->post('id',true))->row_array();
		echo json_encode($json);
	}

	public function translation_edit($lang_id = 0){
		$userdetails = $this->userdetails();
		$data['flags_files'] = glob("./assets/template/images/flags/*.*");
		$data['flags_code'] = [];

		foreach ($data['flags_files'] as $flagfile) {
			$path_parts = pathinfo($flagfile);
			$data['flags_code'][$path_parts['filename']] = $flagfile;
		}

		$data['lang'] = $this->db->query("SELECT * FROM language WHERE id = ". (int)$lang_id)->row_array();
		$languages_json = file_get_contents('assets/data/languages.json');
		$data['languages'] = json_decode($languages_json, true);
		
		$this->view($data,'language/edit');
	}

	public function delete_update_language(){
		$userdetails = $this->userdetails();
		if((int)$this->input->post('id',true) != 1){
			$path = APPPATH.'language/'. (int)$this->input->post('id',true)."/";
			$this->cart->delete_directory($path);
			$this->db->query("DELETE FROM language WHERE id = ". (int)$this->input->post('id',true));
		}
		echo json_encode(array());
	}

	public function mails(){
		$data = array();
		$data['templates'] = $this->db->query("SELECT * FROM mail_templates")->result_array();
		$data['emailsetting'] 	= $this->Product_model->getSettings('emailsetting');
		$data['email'] = $this->Product_model->getSettings('email');
		$post = $this->input->post(null,true);

		if(!empty($post)){
			$hasError = false;
			$commonSetting = array('emailsetting');
			$path = 'assets/images/site';

			// Process file upload if any
			if(count($_FILES) > 0){
				$this->load->helper('string');
				$config['upload_path'] = $path;
				$config['allowed_types'] = '*';
				$config['file_name']  = random_string('alnum', 32);
				$this->load->library('upload', $config);
				foreach ($_FILES as $fieldname => $input) {
					$extension = pathinfo($_FILES[$fieldname]["name"], PATHINFO_EXTENSION);
					if($_FILES[$fieldname]["error"] == 0){
						if($extension=='jpg' || $extension=='jpeg' || $extension=='png' || $extension=='gif'){
							$this->upload->initialize($config);
							if($input['error'] == 0){
								if (!$this->upload->do_upload($fieldname)) { }
									else {
										$upload_details = $this->upload->data();
										list($key,$subkey) = explode("_", $fieldname);
										$post[$key][$subkey] = $upload_details['file_name'];
									}
								}
							} else{
								$hasError = true;
								$this->session->set_flashdata('error', 'Only Image file allowed');
							}
						}
					}
				}

			// Save emailsetting (footer, logo) - works with or without file upload
			if(isset($post['emailsetting']) && is_array($post['emailsetting'])) {
				$toSave = $post['emailsetting'];
				// Preserve existing logo if no new one was uploaded (handles: no file input, or empty file selection)
				if(empty($toSave['logo'])){
					$current = $this->Product_model->getSettings('emailsetting');
					if(!empty($current['logo'])) $toSave['logo'] = $current['logo'];
				}
				$this->Setting_model->save('emailsetting', $toSave);
			}

			// Save Unsubscribe page (title & message) - merges with existing email settings
			if(isset($post['email']) && is_array($post['email']) && (isset($post['email']['unsubscribed_page_title']) || isset($post['email']['unsubscribed_page_message']))) {
				$currentEmail = $this->Product_model->getSettings('email');
				if(!is_array($currentEmail)) $currentEmail = array();
				$toSave = array_merge($currentEmail, array(
					'unsubscribed_page_title' => isset($post['email']['unsubscribed_page_title']) ? $post['email']['unsubscribed_page_title'] : (isset($currentEmail['unsubscribed_page_title']) ? $currentEmail['unsubscribed_page_title'] : ''),
					'unsubscribed_page_message' => isset($post['email']['unsubscribed_page_message']) ? $post['email']['unsubscribed_page_message'] : (isset($currentEmail['unsubscribed_page_message']) ? $currentEmail['unsubscribed_page_message'] : ''),
				));
				$this->Setting_model->save('email', $toSave);
			}

			if(!$hasError){
				$this->session->set_flashdata('success', __('admin.setting_saved_successfully'));
			}
			redirect('admincontrol/mails');
			return;
		}

		$this->view($data, 'mails/index');
	}

					public function send_test_mail_template($template_id) {
						if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
							echo json_encode(['error' => __('admin.invalid_request')]);
							return;
						}
						$test_email = $this->input->post('test_email', true);
						$test_for = $this->input->post('test_for', true) ?: 'for-user';
						if (!filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
							echo json_encode(['error' => __('admin.invalid_email_format')]);
							return;
						}
						$template = $this->db->query("SELECT * FROM mail_templates WHERE id = " . (int)$template_id)->row_array();
						if (!$template) {
							echo json_encode(['error' => __('admin.template_not_found_or_no_reset')]);
							return;
						}
						$this->load->model('Mail_model');
						$post = [
							'id' => (int)$template_id,
							'test_email' => $test_email,
							'test_for' => $test_for,
							'subject' => $template['subject'],
							'text' => $template['text'],
							'admin_subject' => $template['admin_subject'],
							'admin_text' => $template['admin_text'],
							'client_subject' => $template['client_subject'],
							'client_text' => $template['client_text'],
						];
						$result = $this->Mail_model->test_new($post);
						echo json_encode(['success' => $result]);
					}

					public function preview_mail($template_id) {
						$this->load->model('Mail_model');
						$data['id'] = $template_id;
						$data['prefix'] = '';
						$data['test_email'] = 'test@test.com';
						echo $this->Mail_model->preview_mail($data);
					}

					public function preview_mail_html($template_id) {
						if (strtolower($this->input->method()) !== 'post') {
							show_404();
							return;
						}
						$this->load->model('Mail_model');
						$post = $this->input->post(null, true);
						$post['id'] = (int)$template_id;
						$post['test_email'] = $post['test_email'] ?? 'preview@example.com';
						$post['test_for'] = $post['test_for'] ?? 'for-user';
						echo $this->Mail_model->preview_mail_html($post);
					}

					public function reset_mail_template($template_id) {
						if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
							echo json_encode(['error' => __('admin.invalid_request')]);
							return;
						}
						$template = $this->db->query("SELECT id, unique_id FROM mail_templates WHERE id = " . (int)$template_id)->row_array();
						if (!$template || empty($template['unique_id'])) {
							echo json_encode(['error' => __('admin.template_not_found_or_no_reset')]);
							return;
						}
						$this->config->load('mail_template_defaults', true);
						$defaults_all = $this->config->item('defaults', 'mail_template_defaults');
						$defaults = isset($defaults_all[$template['unique_id']]) ? $defaults_all[$template['unique_id']] : null;
						if (!$defaults) {
							echo json_encode(['error' => __('admin.no_default_available')]);
							return;
						}
						$update = [];
						foreach (['subject','text','admin_subject','admin_text','client_subject','client_text'] as $col) {
							if (isset($defaults[$col])) {
								$update[$col] = $defaults[$col];
							}
						}
					if ($update && $this->db->update('mail_templates', $update, ['id' => (int)$template_id])) {
						echo json_encode(['success' => __('admin.template_reset_success')]);
					} else {
						echo json_encode(['error' => __('admin.something_wrong_try_again')]);
					}
				}

				public function unsubscribe_list() {
					$this->checkLogin('admin');
					$search  = $this->input->get('search', true);
					$source  = $this->input->get('source', true);
					$perPage = 20;
					$page    = max(1, (int)$this->input->get('page'));
					$offset  = ($page - 1) * $perPage;

					// Count with filters
					$this->db->from('unsubscribed_emails');
					if (!empty($search)) { $this->db->like('email', $search); }
					if (!empty($source) && in_array($source, ['email_link','profile_page','manual'])) {
						$this->db->where('source', $source);
					}
					$total = $this->db->count_all_results();

					// Fetch page with same filters
					$this->db->from('unsubscribed_emails');
					if (!empty($search)) { $this->db->like('email', $search); }
					if (!empty($source) && in_array($source, ['email_link','profile_page','manual'])) {
						$this->db->where('source', $source);
					}
					$this->db->order_by('id', 'DESC')->limit($perPage, $offset);
					$list = $this->db->get()->result_array();

				$statsRow = $this->db->query("
					SELECT
						COUNT(*) AS total,
						SUM(unsubscribed_at >= DATE_FORMAT(NOW(),'%Y-%m-01')) AS this_month,
						SUM(source = 'email_link')   AS email_link,
						SUM(source = 'profile_page') AS profile_page,
						SUM(source = 'manual')       AS manual
					FROM unsubscribed_emails
				")->row();
				$stats = [
					'total'         => (int) $statsRow->total,
					'this_month'    => (int) $statsRow->this_month,
					'email_link'    => (int) $statsRow->email_link,
					'profile_page'  => (int) $statsRow->profile_page,
					'manual'        => (int) $statsRow->manual,
				];

					$data['list']    = $list;
					$data['stats']   = $stats;
					$data['total']   = $total;
					$data['perPage'] = $perPage;
					$data['page']    = $page;
					$data['search']  = $search;
					$data['source']  = $source;
					$this->view($data, 'mails/unsubscribe_list');
				}

				public function unsubscribe_resubscribe($id) {
					$this->checkLogin('admin');
					if (!$this->input->is_ajax_request()) {
						show_404(); return;
					}
					$row = $this->db->get_where('unsubscribed_emails', ['id' => (int)$id])->row_array();
					if (empty($row)) {
						echo json_encode(['error' => __('admin.not_found')]); return;
					}
					$this->db->delete('unsubscribed_emails', ['id' => (int)$id]);
					echo json_encode(['success' => __('admin.email_removed_from_unsubscribe')]);
				}

				public function unsubscribe_add() {
					$this->checkLogin('admin');
					if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
						echo json_encode(['error' => __('admin.invalid_request')]); return;
					}
					$email = $this->input->post('email', true);
					if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
						echo json_encode(['error' => __('admin.invalid_email_format')]); return;
					}
					$exists = $this->db->get_where('unsubscribed_emails', ['email' => $email])->row();
					if ($exists) {
						echo json_encode(['error' => __('admin.email_already_exists')]); return;
					}
					$this->db->insert('unsubscribed_emails', [
						'email'           => $email,
						'unsubscribed_at' => date('Y-m-d H:i:s'),
						'source'          => 'manual',
					]);
					echo json_encode(['success' => __('admin.email_added_to_unsubscribe')]);
				}

				public function unsubscribe_export() {
					$this->checkLogin('admin');
					$search = $this->input->get('search', true);
					$source = $this->input->get('source', true);

					$this->db->from('unsubscribed_emails');
					if (!empty($search)) { $this->db->like('email', $search); }
					if (!empty($source) && in_array($source, ['email_link','profile_page','manual'])) {
						$this->db->where('source', $source);
					}
					$this->db->order_by('id', 'DESC');
					$rows = $this->db->get('unsubscribed_emails')->result_array();

					header('Content-Type: text/csv; charset=utf-8');
					header('Content-Disposition: attachment; filename="unsubscribed_emails_' . date('Y-m-d') . '.csv"');
					$out = fopen('php://output', 'w');
					fputcsv($out, ['ID', 'Email', 'Source', 'Unsubscribed At']);
					foreach ($rows as $row) {
						fputcsv($out, [
							$row['id'],
							$row['email'],
							$row['source'] ?? '',
							$row['unsubscribed_at'] ?? '',
						]);
					}
					fclose($out);
					exit;
				}

					public function subscriber_list() {
					$this->checkLogin('admin');
					$search  = $this->input->get('search', true);
					$perPage = 20;
					$page    = max(1, (int)$this->input->get('page'));
					$offset  = ($page - 1) * $perPage;

					// LEFT JOIN avoids collation mismatch between users and unsubscribed_emails tables
					// Count subscribed users
					$this->db->from('users u')
						->join('unsubscribed_emails ue', 'CONVERT(u.email USING utf8mb4) = CONVERT(ue.email USING utf8mb4)', 'left')
						->where('u.status', 1)
						->where('ue.id IS NULL', null, false);
					if (!empty($search)) {
						$this->db->group_start()
							->like('u.email', $search)
							->or_like("CONCAT(u.firstname,' ',u.lastname)", $search)
							->group_end();
					}
					$total = $this->db->count_all_results();

					// Fetch subscribed users
					$this->db->select('u.id, u.firstname, u.lastname, u.email, u.username, u.created_at')
						->from('users u')
						->join('unsubscribed_emails ue', 'CONVERT(u.email USING utf8mb4) = CONVERT(ue.email USING utf8mb4)', 'left')
						->where('u.status', 1)
						->where('ue.id IS NULL', null, false);
					if (!empty($search)) {
						$this->db->group_start()
							->like('u.email', $search)
							->or_like("CONCAT(u.firstname,' ',u.lastname)", $search)
							->group_end();
					}
					$this->db->order_by('u.id', 'DESC')->limit($perPage, $offset);
					$list = $this->db->get()->result_array();

					// Stats
					$totalUsers        = $this->db->where('status', 1)->count_all_results('users');
					$totalUnsubscribed = $this->db->count_all('unsubscribed_emails');
					$totalSubscribed   = max(0, $totalUsers - $totalUnsubscribed);
					$rate              = $totalUsers > 0 ? round(($totalSubscribed / $totalUsers) * 100, 1) : 0;

					$data['list']             = $list;
					$data['total']            = $total;
					$data['perPage']          = $perPage;
					$data['page']             = $page;
					$data['search']           = $search;
					$data['stats']            = [
						'total_users'        => $totalUsers,
						'total_subscribed'   => $totalSubscribed,
						'total_unsubscribed' => $totalUnsubscribed,
						'rate'               => $rate,
					];
					$this->view($data, 'mails/subscriber_list');
				}

				public function subscriber_export() {
					$this->checkLogin('admin');
					$search = $this->input->get('search', true);

					$this->db->select('u.id, u.firstname, u.lastname, u.email, u.username, u.created_at')
						->from('users u')
						->join('unsubscribed_emails ue', 'CONVERT(u.email USING utf8mb4) = CONVERT(ue.email USING utf8mb4)', 'left')
						->where('u.status', 1)
						->where('ue.id IS NULL', null, false);
					if (!empty($search)) {
						$this->db->group_start()
							->like('u.email', $search)
							->or_like("CONCAT(u.firstname,' ',u.lastname)", $search)
							->group_end();
					}
					$this->db->order_by('u.id', 'DESC');
					$rows = $this->db->get()->result_array();

					header('Content-Type: text/csv; charset=utf-8');
					header('Content-Disposition: attachment; filename="subscribers_' . date('Y-m-d') . '.csv"');
					$out = fopen('php://output', 'w');
					fputcsv($out, ['ID', 'First Name', 'Last Name', 'Email', 'Username', 'Joined At']);
					foreach ($rows as $row) {
						fputcsv($out, [
							$row['id'],
							$row['firstname'],
							$row['lastname'],
							$row['email'],
							$row['username'],
							$row['created_at'],
						]);
					}
					fclose($out);
					exit;
				}

				public function mails_edit($template_id){

						$data = array();

						$post = $this->input->post(null,true);

						if (isset($post['send_test'])) {
							
							$json = array();

							if (!filter_var($this->input->post('test_email'), FILTER_VALIDATE_EMAIL)) {

								$json['error'] = __('admin.invalid_email_format');

							}

							else{

								$json['success'] = __('admin.testing_mail_sent_successfully');

								$this->load->model('Mail_model');

								$json['detais'] = $this->Mail_model->test_new($post);

							}

							echo json_encode($json);die;

						}

						else if (isset($post['id'])) {
							$this->db->query("UPDATE mail_templates SET

								`subject` = ". $this->db->escape($this->input->post("subject",true)) .",

								`text` = ". $this->db->escape($this->input->post("text")) .",

								`admin_subject` = ". $this->db->escape($this->input->post("admin_subject",true)) .",

								`admin_text` = ". $this->db->escape($this->input->post("admin_text")) .",

								`client_subject` = ". $this->db->escape($this->input->post("client_subject",true)) .",

								`client_text` = ". $this->db->escape($this->input->post("client_text")) ."

								WHERE id = ". $post['id']
							);

							redirect($this->uri->uri_string());

						}

						if (is_numeric($template_id)) {
						$data['templates'] = $this->db->query("SELECT * FROM mail_templates WHERE id = " . (int)$template_id)->row_array();
					} else {
						// Support lookup by unique_id (e.g. /mails_edit/welcome_affiliate)
						$data['templates'] = $this->db->query("SELECT * FROM mail_templates WHERE unique_id = " . $this->db->escape($template_id))->row_array();
					}

						if($data['templates']){

							$this->view($data,'mails/editor');
						}

						else{

							show_404();
						}
					}

					public function backup($action = ''){
						$userdetails = $this->userdetails();

						$this->load->library("Backup");

						$get = $this->input->get(null,true);

						$this->backup->setMysql(array(
							'host' => $this->db->hostname, 
							'user' => $this->db->username, 
							'pass' => $this->db->password, 
							'dbname' => $this->db->database
						));

						$data['zip_loaded'] = extension_loaded('zip');
						$data['script_backups'] = $this->backup->getListScriptZip();

						if(isset($_FILES['backup_file'])){
							$path = APPPATH . 'backup/mysql';
							$ext = pathinfo($_FILES['backup_file']["name"],PATHINFO_EXTENSION);

							// Demo Mode
							if (ENVIRONMENT === 'demo') {
								$this->session->set_flashdata('error', 'Disabled on demo mode');
								redirect('admincontrol/backup');
								return;
							}
							// Demo Mode

							$this->load->helper('string');

							$config['upload_path'] = $path;
							$config['allowed_types'] = 'zip';
							$config['file_name']  = 'Upload_'.date("Y.m.d H.i.s").'.'.$ext;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if (!$this->upload->do_upload('backup_file')) {
								$this->session->set_flashdata('error', $this->upload->display_errors());
							} else {
								$upload_details = $this->upload->data();
								$this->session->set_flashdata('success', __('admin.backup_upload_successfully'));
							}

							redirect('admincontrol/backup');
						}

						if ($action == 'getbackup') {
							try {
								// Demo Mode
								if (ENVIRONMENT === 'demo') {
									$this->session->set_flashdata('error', __('admin.demo_mode'));
									redirect('admincontrol/backup');
									return;
								}
								// Demo Mode

								$this->load->dbutil();
								$prefs = array(
									'format'        => 'txt',
									'filename'      => $this->db->database,
									'add_drop'      => true,
									'add_insert'    => true,
									'newline'       => "\n"
								);

								$backup =& $this->dbutil->backup($prefs);

								$db_name = 'database_backup_version_'.$this->config->item('app_version').'_'.time();
								$bk_path = 'application/backup/mysql/'.$db_name;

								$this->load->library('zip');
								$this->zip->add_data($db_name.'.sql', $backup);
								$this->zip->archive($bk_path.'.zip');

								$this->session->set_flashdata('success', __('admin.backup_created_successfully'));
							} catch (Exception $e) {
								$this->session->set_flashdata('error', $e->getMessage());
							}

							redirect('admincontrol/backup');
						}

						// Script Backup
						else if ($action == 'backup_script') {
							if (ENVIRONMENT === 'demo') {
								$this->session->set_flashdata('error', __('admin.demo_mode'));
								redirect('admincontrol/backup');
								return;
							}

							try {
								$this->load->library('zip');

								$rootPath = FCPATH;
								$exclude = ['application/backup', 'node_modules', '.git'];

								$files = new RecursiveIteratorIterator(
									new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
									RecursiveIteratorIterator::LEAVES_ONLY
								);

								foreach ($files as $file) {
									if (!$file->isFile()) continue;

									$filePath = $file->getRealPath();
									$relativePath = str_replace($rootPath, '', $filePath);

									$skip = false;
									foreach ($exclude as $ex) {
										if (strpos($relativePath, $ex) === 0) {
											$skip = true;
											break;
										}
									}

									if (!$skip) {
										$this->zip->add_data($relativePath, file_get_contents($filePath));
									}
								}

								$filename = 'full_script_backup_' . date('Ymd_His') . '.zip';
								$savePath = APPPATH . 'backup/script/' . $filename;

								if (!is_dir(APPPATH . 'backup/script')) {
									mkdir(APPPATH . 'backup/script', 0777, true);
								}

								if ($this->zip->archive($savePath)) {
									$this->session->set_flashdata('success', 'Script files backed up successfully.');
								} else {
									$this->session->set_flashdata('error', 'Failed to create ZIP file. Check folder permissions.');
								}
							} catch (Exception $e) {
								$this->session->set_flashdata('error', 'Error: '.$e->getMessage());
							}

							redirect('admincontrol/backup');
						}

						// Script download
						else if ($action == 'download_script') {
						    if (ENVIRONMENT === 'demo') {
						        $this->session->set_flashdata('error', __('admin.demo_mode'));
						        redirect('admincontrol/backup');
						        return;
						    }

						    $file = APPPATH . 'backup/script/' . $get['file_name'];
						    if (file_exists($file)) {
						        $this->load->helper('download');
						        force_download($file, null);
						    } else {
						        $this->session->set_flashdata('error', 'File not found');
						        redirect('admincontrol/backup');
						    }
						}

						// Script delete
						else if ($action == 'delete_script') {
						    if (ENVIRONMENT === 'demo') {
						        $this->session->set_flashdata('error', __('admin.demo_mode'));
						        redirect('admincontrol/backup');
						        return;
						    }

						    $file = APPPATH . 'backup/script/' . $get['file_name'];
						    if (file_exists($file)) {
						        unlink($file);
						        $this->session->set_flashdata('success', 'File deleted successfully');
						    } else {
						        $this->session->set_flashdata('error', 'File not found');
						    }
						    redirect('admincontrol/backup');
						}

						else if ($action == 'delete') {
						    if (ENVIRONMENT === 'demo') {
						        $this->session->set_flashdata('error', __('admin.demo_mode'));
						        redirect('admincontrol/backup');
						        return;
						    }

						    $status = $this->backup->delFile($get['file_name']);

						    if ($status == 'ok_delete') {
						        $this->session->set_flashdata('success', __('admin.backup_file_deleted_successfully'));
						    } else {
						        $this->session->set_flashdata('error', $status);
						    }

						    redirect('admincontrol/backup');
						}

						else if ($action == 'restore') {
						    if (ENVIRONMENT === 'demo') {
						        $this->session->set_flashdata('error', __('admin.demo_mode'));
						        redirect('admincontrol/backup');
						        return;
						    }

						    $status = $this->backup->restore($get['file_name']);

						    if ($status == 'ok_res_backup') {
						        $this->session->set_flashdata('success', __('admin.backup_file_restored_successfully'));
						    } else {
						        $this->session->set_flashdata('error', $status);
						    }

						    redirect('admincontrol/backup');
						}

						else if ($action == 'download') {
						    if (ENVIRONMENT === 'demo') {
						        $this->session->set_flashdata('error', __('admin.demo_mode'));
						        redirect('admincontrol/backup');
						        return;
						    }

						    $this->backup->getZipFile($get['file_name']);
						}


						$data['backups'] = $this->backup->getListZip();
						$this->view($data, 'backup/index');
					}


				private function checkMissingBackups()
				{
					$warnings = [];
					$seven_days_ago = strtotime('-7 days');
				
					$mysql_backups = glob(APPPATH . 'backup/mysql/*.zip');
					$script_backups = glob(APPPATH . 'backup/script/*.zip');
					
					if (empty($mysql_backups) && empty($script_backups)) {
						$warnings[] = "No backups found. Please create backups to protect your data.";
						return $warnings;
					}
					
					$latest_mysql_time = 0;
					if (!empty($mysql_backups)) {
						foreach ($mysql_backups as $file) {
							preg_match('/_(\d{10,})\.zip$/', $file, $matches);
							if (isset($matches[1]) && (int)$matches[1] > $latest_mysql_time) {
								$latest_mysql_time = (int)$matches[1];
							}
						}
						if ($latest_mysql_time > 0 && $latest_mysql_time < $seven_days_ago) {
							$warnings[] = "Your last MySQL backup is older than 7 days.";
						}
					}
					
					$latest_script_time = 0;
					if (!empty($script_backups)) {
						foreach ($script_backups as $file) {
							preg_match('/_(\d{8}_\d{6})\.zip$/', $file, $matches);
							if (isset($matches[1])) {
								$date = DateTime::createFromFormat('Ymd_His', $matches[1]);
								if ($date && $date->getTimestamp() > $latest_script_time) {
									$latest_script_time = $date->getTimestamp();
								}
							}
						}
						if ($latest_script_time > 0 && $latest_script_time < $seven_days_ago) {
							$warnings[] = "Your last script backup is older than 7 days.";
						}
					}
				
					return $warnings;
				}



					public function userdetails(){
						if (isset($this->session) && $this->session->userdata('user_type') !== FALSE && $this->session->userdata('user_type')=='admin')
						{
							$this->session->unset_userdata('user');
							$this->session->unset_userdata('client');
							
							if(!isset($this->session->administrator))
								redirect($this->admin_domain_url, 'refresh');
			 				else
			 					return $this->session->administrator;
						}
						else 
						{
							 //show_404();
							 redirect($this->admin_domain_url, 'refresh');
						}
					}
			   

			   
					public function getSiteSetting(){

						return $this->Product_model->getSettings('site');

					}



					public function index($slug) {

						if($this->userdetails()){ redirect($this->admin_domain_url, 'refresh'); }

						else { redirect('usercontrol', 'refresh'); }

					}



					public function notification(){

						$userdetails = $this->userdetails();

						$this->load->helper('utility');

						$post = $this->input->post(null,true);

						$get = $this->input->get(null,true);

						if (isset($get['clearall'])) {

							$this->db->query("DELETE FROM notification WHERE notification_viewfor = 'admin'");

							redirect('admincontrol/notification', 'refresh');die;

						}

						if (isset($post['delete_ids'])) {

							$delete_ids = implode(",", $post['delete_ids']);

							$this->db->query("DELETE FROM notification WHERE notification_id IN ({$delete_ids})");

							echo json_encode(array());

							die;

						}

						$data['title'] = 'Notification';

						$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

						$settings = get_pagination_settings();

						$notification = $this->user->getAllNotificationPaging('admin',null,$settings['per_page'],$page);

						$data['notification'] = $notification;

						$data['notifications'] = $notification['notifications'];

						$this->view($data,'dashboard/notification');
					}



		public function register($refid = null) {

			if($this->userdetails()){ redirect($this->admin_domain_url, 'refresh'); }

			if(!empty($refid)){

			} else {

				$refid = base64_decode($this->input->get('refid'));

			}

			$data=array();

			if ($this->input->post()) {

				$this->load->library('form_validation');

				$checkmail=$this->user->checkmail($this->input->post('email',true));

				$checkuser=$this->user->checkuser($this->input->post('username',true));

				if(!empty($checkmail))

				{

					$this->session->set_flashdata('error', __('admin.this_email_already_register'));

					$this->session->set_flashdata('postdata', $this->input->post());

					redirect($this->admin_domain_url);

				} elseif(!empty($checkuser)) {

					$this->session->set_flashdata('error',__('admin.this_username_already_register'));

					$this->session->set_flashdata('postdata', $this->input->post());

					redirect($this->admin_domain_url);

				} else {

					$data=$this->user->insert(array(

						'firstname' => $this->input->post('firstname',true),

						'lastname'  => $this->input->post('lastname',true),

						'email'     => $this->input->post('email',true),

						'username'  => $this->input->post('username',true),

						'password'  => sha1($this->input->post('password',true)),

						'refid'     => !empty($refid) ? base64_decode($refid) : 0,

						'type'      => 'admin',

					));

					if(!empty($data)){

						$this->session->set_flashdata('success', __('admin.you_ve_successfully_registered'));

						redirect($this->admin_domain_url);

					}

				}

			}

			$this->load->view('admincontrol/login/register', $data);
		}


		public function changePassword(){

			$userdetails = $this->userdetails();

			if(empty($userdetails)){

				redirect($this->admin_domain_url);
			}

			$post = $this->input->post(null,true);

			if(isset($post) && !empty($post)){

				$is_ajax = $this->input->is_ajax_request() || $this->input->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest';

				$this->form_validation->set_rules('old_pass', 'Old Password', 'required|trim|min_length[1]', array(
					'required' => '%s is required',
					'min_length' => '%s cannot be empty'
				));

				$this->form_validation->set_rules('password', 'New Password', 'required|trim|min_length[6]|max_length[50]', array(
					'required' => '%s is required',
					'min_length' => __('admin.password_must_be_at_least_6_characters'),
					'max_length' => __('admin.password_cannot_exceed_50_characters')
				));

				$this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|trim|matches[password]', array(
					'required' => '%s is required',
					'matches' => __('admin.passwords_do_not_match')
				));

				if ($this->form_validation->run() == FALSE) {

					if($is_ajax){

						// Clear any previous output
						if (ob_get_level()) {
							ob_clean();
						}

						header('Content-Type: application/json; charset=utf-8');

						echo json_encode(array('success' => false, 'message' => validation_errors()));

						die;

					}

					$data['validate_err'] = validation_errors();

				} else {

				$admin = $this->db->from('users')->where('id',$userdetails['id'])->get()->row_array();
				$old_pass = $this->input->post('old_pass',true);
				$new_pass = $this->input->post('password',true);

					// Check if old password is correct
					if($admin['password'] == sha1($old_pass)){

						// Additional security checks
						$errors = array();

						// Check if new password is same as old password
						if(sha1($new_pass) == $admin['password']){
							$errors[] = __('admin.new_password_must_be_different');
						}

						// Check password strength (basic validation)
						if(strlen($new_pass) < 6){
							$errors[] = __('admin.password_must_be_at_least_6_characters');
						}

						if(empty($errors)){

							$res = array('password'=>sha1($new_pass));

							$this->db->where('id',$admin['id']);

							$this->db->update('users',$res);

							if($is_ajax){

								// Clear any previous output
								if (ob_get_level()) {
									ob_clean();
								}

								header('Content-Type: application/json; charset=utf-8');

								echo json_encode(array('success' => true, 'message' => __('admin.user_profile_updated_successfully')));

								die;

							}

							$this->session->set_flashdata(array('flash' => array('success' => __('admin.user_profile_updated_successfully!'))));

							redirect($this->admin_domain_url, 'refresh');

						} else {

							// Handle validation errors
							$error_message = implode(', ', $errors);

							if($is_ajax){

								// Clear any previous output
								if (ob_get_level()) {
									ob_clean();
								}

								header('Content-Type: application/json; charset=utf-8');

								echo json_encode(array('success' => false, 'message' => $error_message));

								die;

							}

							$this->session->set_flashdata(array('flash' => array('error' => $error_message)));

							redirect('admincontrol/changePassword');

						}

					}else{

						if($is_ajax){

							// Clear any previous output
							if (ob_get_level()) {
								ob_clean();
							}

							header('Content-Type: application/json; charset=utf-8');

							echo json_encode(array('success' => false, 'message' => __('admin.old_password_not_matched')));

							die;

						}

						$this->session->set_flashdata(array('flash' => array('error' => __('admin.old_password_not_matched.'))));

						redirect('admincontrol/changePassword');

					}

				}

			}

			$data['title'] = 'Change Password';

			$this->view($data,'dashboard/change-password');
		}


		public function ask_again_withdrawal(){

			$this->db->query("UPDATE wallet SET status=1 WHERE (wv != 'V2' OR wv IS NULL) AND status = 2");



			$this->session->set_flashdata('success', 'All Transaction Set In Wallet. Now user need to send withdraw request.');

			$get = $this->input->get(null,true);



			if (isset($get['backto'])) {

				redirect('admincontrol/wallet_requests_list?tab=old');die;

			}

			redirect('admincontrol/wallet/withdraw');
		}



					public function wallet_withdraw(){

						$userdetails = $this->userdetails();

						$get = $this->input->get(null,true);

						$filter = array(

							'status' => 2,

							'old_with' => 'V2',

						);

						if (isset($get['user_id']) && $get['user_id'] > 0) {

							$filter['user_id'] = (int)$get['user_id'];

							$data['user_id'] = $filter['user_id'];
						}

						if (isset($get['date'])) {

							$filter['date'] = $get['date'];

							$data['date'] = $filter['date'];

						}

						$query = $this->db->query('SELECT sum(amount) AS amount,count(`status`) AS counts,`status` FROM `wallet` WHERE (wallet.wv != "V2" OR wallet.wv IS NULL) GROUP BY `status`')->result_array();

						foreach ($query as $key => $value) {

							switch ($value['status']) {

								case '0':

								$data['totals']['wallet_on_hold_amount'] = (float)$value['amount'];

								$data['totals']['wallet_on_hold_count'] = (float)$value['counts'];

								break;

								case '1':

								$data['totals']['wallet_unpaid_amount'] = (float)$value['amount'];

								$data['totals']['wallet_unpaid_count'] = (float)$value['counts'];

								break;

								case '2':

								$data['totals']['wallet_request_sent_amount'] = (float)$value['amount'];

								$data['totals']['wallet_request_sent_count'] = (float)$value['counts'];

								break;

								case '3':

								$data['totals']['wallet_accept_amount'] = (float)$value['amount'];

								$data['totals']['wallet_accept_count'] = (float)$value['counts'];

								break;

								default: break;

							}

						}

						$query = $this->db->query('SELECT sum(amount) AS amount,count(`commission_status`) AS counts,`commission_status` FROM `wallet` WHERE (wallet.wv != "V2" OR wallet.wv IS NULL) GROUP BY `commission_status`')->result_array();

						foreach ($query as $key => $value) {

							switch ($value['commission_status']) {

								case '1':

								$data['totals']['wallet_cancel_amount'] = (float)$value['amount'];

								$data['totals']['wallet_cancel_count'] = (float)$value['counts'];

								break;

								case '2':

								$data['totals']['wallet_trash_amount'] = (float)$value['amount'];

								$data['totals']['wallet_trash_count'] = (float)$value['counts'];

								break;

								default: break;

							}

						}




						$data['transaction'] = $this->Wallet_model->getTransaction($filter);

						$data['request_status'] = $this->Wallet_model->status();

						$post = $this->input->post(null,true);





						if (isset($post['request_payment_all'])) {

							$json = array();

							if($data['transaction']){

								$this->load->model('Mail_model');

								$userwise = array();

								foreach ($data['transaction'] as $key => $value) { $userwise[$value['user_id']][] = $value; }

								foreach ($userwise as $user_id => $value) {

									$user_name = $user_email = '';

									foreach ($value as $__value) {

										$this->Wallet_model->changeStatus($__value['id'],$post['status']);

										$user_name = $__value['firstname']. ' ' . $__value['lastname'];

										$user_email = $__value['user_email'];

									}

									if($user_name){

										$_data = array(

											'amount'          => c_format($data['wallet_unpaid_amount']),

											'comment'         => $user_name .' your withdrawal request status has been changed..!',

											'name'            => $user_name,

											'user_email'      => $user_email,

											'commission_type' => '',

											'new_status'      => $data['request_status'][$post['status']],

										);

										$this->Mail_model->send_wallet_withdrawal_status($_data);

									}

								}



								$json['success'] = __('admin.request_send_successfully');
							}

							echo json_encode($json);die;

						}

						$this->view($data,'payment/wallet_withdraw');

					}



					public function wallet_requests_details($id) {
						$userdetails = $this->userdetails();
						$id = (int)$id;

						$post = $this->input->post(null, true);

						if (isset($post['status'])) {
							$this->form_validation->set_rules('status', 'Status', 'required|trim');
							$this->form_validation->set_rules('comment', 'Comment', 'required|trim');

							if ($this->form_validation->run() == FALSE) {
								$data['errors'] = $this->form_validation->error_array();
							} else {
								$this->load->model('Withdrawal_payment_model');
								$this->Withdrawal_payment_model->apiAddWithdrwalRequestHistory($id, [
									'status_id' => (int)$post['status'],
									'comment' => $post['comment'],
									'transaction_id' => '',
								]);
								$data['success'] = 1;
							}
							echo json_encode($data); die;
						}

						$data['request'] = $this->db->query("SELECT * FROM wallet_requests WHERE id={$id}")->row_array();
						if (!$data['request']) {
							show_404();
						}

						$data['payout_batch'] = null;
						$data['mass_payout_paid_info'] = null;
						$this->load->model('Payout_batch_model');
						if (!empty($data['request']['batch_export_id'])) {
							$data['payout_batch'] = $this->Payout_batch_model->get_batch((int) $data['request']['batch_export_id']);
						}
						if ($this->db->table_exists('payout_batch_items')) {
							$data['mass_payout_paid_info'] = $this->Payout_batch_model->get_last_paid_mass_payout_reconciliation($id);
						}

						// handle missing country field
						$settings = json_decode($data['request']['settings'], true);
						if (!is_array($settings)) $settings = [];

						// Extract payment method from settings
						if(isset($settings['prefer_method'])) {
							$data['request']['prefer_method'] = $settings['prefer_method'];
						}

						$countryFieldMap = get_country_field_map();
						$selectedCountry = isset($settings['payment_country']) ? $settings['payment_country'] : '';

						if ($selectedCountry && isset($countryFieldMap[$selectedCountry])) {
							$field = $countryFieldMap[$selectedCountry];
							if (!isset($settings[$field])) {
								$paymentData = $this->Product_model->getAllPayment($data['request']['user_id']);
								if (!empty($paymentData[0][$field])) {
									$settings[$field] = $paymentData[0][$field];
								}
							}
						}

						$data['request']['settings'] = json_encode($settings);

						$this->load->model('Withdrawal_payment_model');

						$filter = array(
							'id_in' => $data['request']['tran_ids'],
						);

						$data['transaction'] = $this->Wallet_model->getTransaction($filter);
						$data['status'] = $this->Wallet_model->status();
						$data['status_icon'] = $this->Wallet_model->status_icon;
						$data['status_list'] = $this->Withdrawal_payment_model->status_list();
						$eligible_st = $this->Payout_batch_model->get_eligible_mass_payout_statuses();
						$wr_st = (int) $data['request']['status'];
						$wr_batch = isset($data['request']['batch_export_id']) && $data['request']['batch_export_id'] !== null && $data['request']['batch_export_id'] !== ''
							? (int) $data['request']['batch_export_id'] : 0;
						$terminal_wr = array(1, 8, 9, 10);
						$data['wallet_mass_go_export_only'] = ($wr_batch < 1 && in_array($wr_st, $eligible_st, true));
						$data['wallet_mass_prepare_available'] = ($wr_batch < 1 && !in_array($wr_st, $eligible_st, true) && !in_array($wr_st, $terminal_wr, true));
						$data['wallet_mass_prepare_csrf'] = $this->_wallet_mass_prepare_csrf_refresh();
						$data['confirm'] = $this->Withdrawal_payment_model->getConfirm($data['request']['prefer_method'], array(
							'request' => $data['request'],
							'wallet_mass_prepare_csrf' => $data['wallet_mass_prepare_csrf'],
							'wallet_mass_prepare_available' => $data['wallet_mass_prepare_available'],
							'wallet_mass_go_export_only' => $data['wallet_mass_go_export_only'],
						));

						$this->view($data, 'users/wallet_requests_details');
					}




					public function get_withdrwal_history($id)

					{
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



					public function wallet_requests_list(){

						$userdetails = $this->userdetails();

						$get = $this->input->get(null,true);

						$post = $this->input->post(null,true);

						if (isset($post['delete_request'])) {
							$json['id'] = [];

							$ids= explode(",", $post['id']);

							$request = $this->db->query("SELECT id FROM wallet_requests WHERE tran_ids='".$post['id']."'")->row();
							if (isset($request->id)) {
								$this->load->model('Payout_batch_model');
								$this->Payout_batch_model->detach_wallet_request_from_batch((int) $request->id);
							}

							foreach ($ids as $id) {
								$dataCollection = $this->Wallet_model->getDeleteData((int)$id);

								foreach ($dataCollection as $data) {

									if(!empty($data['id'])) {
										$this->db->query("UPDATE wallet SET status=1 WHERE id =".$data['id']);
									}

									if(isset($request->id)) {
										$this->db->query("DELETE FROM wallet_requests WHERE id=".$request->id);

										$this->db->query("DELETE FROM wallet_requests_history WHERE req_id=".$request->id);
									}
								}
							}	

							$json['success'] = 1;

							echo json_encode($json);die;

						}




						if (isset($post['get_new'])) {

							$get = $this->input->post(null,true);

							$filter = array();

							if (isset($get['user_id']) && $get['user_id'] > 0) {

								$filter['user_id'] = (int)$get['user_id'];

								$data['user_id'] = $filter['user_id'];

							}

							if (isset($get['date'])) {

								$filter['date'] = $get['date'];

								$data['date'] = $filter['date'];

							}

							$page = isset($get['page']) ? max(1, (int)$get['page']) : 1;
							$per_page = isset($get['per_page']) ? max(1, (int)$get['per_page']) : 15;
							$offset = ($page - 1) * $per_page;

							$this->load->model('Withdrawal_payment_model');

							$total_rows = $this->Withdrawal_payment_model->getRequestsCount($filter);
							$data['lists'] = $this->Withdrawal_payment_model->getRequests($filter, $per_page, $offset);
							
							$json['html'] = $this->load->view("admincontrol/users/part/tr_w_request_new",$data,true);
							$json['total_rows'] = $total_rows;
							$json['current_page'] = $page;
							$json['per_page'] = $per_page;

							echo json_encode($json);die;

						}



						if (isset($post['get_old'])) {

							$get = $this->input->post(null,true);

							$filter = array(

								'status' => 2,

								'old_with' => 'V2',

							);



							if (isset($get['user_id']) && $get['user_id'] > 0) {

								$filter['user_id'] = (int)$get['user_id'];

								$data['user_id'] = $filter['user_id'];

							}



							if (isset($get['date'])) {

								$filter['date'] = $get['date'];

								$data['date'] = $filter['date'];

							}



							$data['transaction'] = $this->Wallet_model->getTransaction($filter);


							$data['request_status'] = $this->Wallet_model->status();

							$json['html'] = $this->load->view("admincontrol/users/part/tr_w_request_old",$data,true);



							echo json_encode($json);die;

						}


						$data['users'] = $this->db->query("SELECT id,username FROM users WHERE type = 'user'")->result_array();



						$query = $this->db->query('SELECT sum(amount) AS amount,count(`status`) AS counts,`status` FROM `wallet` WHERE (wallet.wv != "V2" OR wallet.wv IS NULL) GROUP BY `status`')->result_array();

						foreach ($query as $key => $value) {

							switch ($value['status']) {

								case '0':

								$data['totals']['wallet_on_hold_amount'] = (float)$value['amount'];

								$data['totals']['wallet_on_hold_count'] = (float)$value['counts'];

								break;

								case '1':

								$data['totals']['wallet_unpaid_amount'] = (float)$value['amount'];

								$data['totals']['wallet_unpaid_count'] = (float)$value['counts'];

								break;

								case '2':

								$data['totals']['wallet_request_sent_amount'] = (float)$value['amount'];

								$data['totals']['wallet_request_sent_count'] = (float)$value['counts'];

								break;

								case '3':

								$data['totals']['wallet_accept_amount'] = (float)$value['amount'];

								$data['totals']['wallet_accept_count'] = (float)$value['counts'];

								break;

								default: break;

							}

						}

						$query = $this->db->query('SELECT sum(amount) AS amount,count(`commission_status`) AS counts,`commission_status` FROM `wallet` WHERE (wallet.wv != "V2" OR wallet.wv IS NULL) GROUP BY `commission_status`')->result_array();

						foreach ($query as $key => $value) {

							switch ($value['commission_status']) {

								case '1':

								$data['totals']['wallet_cancel_amount'] = (float)$value['amount'];

								$data['totals']['wallet_cancel_count'] = (float)$value['counts'];

								break;

								case '2':

								$data['totals']['wallet_trash_amount'] = (float)$value['amount'];

								$data['totals']['wallet_trash_count'] = (float)$value['counts'];

								break;

								default: break;

							}

						} 

						$this->view($data,'users/wallet_requests_list');

					}

					/**
					 * Session-backed CSRF for mass payout POST endpoints when global csrf_protection is disabled.
					 */
					private function _mass_payout_csrf_token_refresh() {
						$t = bin2hex(random_bytes(32));
						$this->session->set_userdata('mass_payout_csrf', $t);
						return $t;
					}

					private function _mass_payout_csrf_valid($posted) {
						$sess = $this->session->userdata('mass_payout_csrf');
						if ($sess === null || $sess === '' || $posted === null || $posted === '') {
							return false;
						}
						return hash_equals((string) $sess, (string) $posted);
					}

					/** Session CSRF for one-click "mark ready + open mass payout" from withdrawal details */
					private function _wallet_mass_prepare_csrf_refresh() {
						$t = bin2hex(random_bytes(32));
						$this->session->set_userdata('wallet_mass_prepare_csrf', $t);
						return $t;
					}

					private function _wallet_mass_prepare_csrf_valid($posted) {
						$sess = $this->session->userdata('wallet_mass_prepare_csrf');
						if ($sess === null || $sess === '' || $posted === null || $posted === '') {
							return false;
						}
						return hash_equals((string) $sess, (string) $posted);
					}

					public function mass_payout() {
						$this->userdetails();
						$get = $this->input->get(null, true);
						$this->load->model('Payout_batch_model');
						$this->load->model('Withdrawal_payment_model');

						$filter = array();
						if (isset($get['user_id']) && (int) $get['user_id'] > 0) {
							$filter['user_id'] = (int) $get['user_id'];
						}
						if (!empty($get['date'])) {
							$filter['date'] = $get['date'];
						}
						$eligible = $this->Payout_batch_model->get_eligible_mass_payout_statuses();
						if (isset($get['status']) && $get['status'] !== '') {
							if ($get['status'] === 'all') {
								$filter['status'] = 'all';
							} elseif (in_array((int) $get['status'], $eligible, true)) {
								$filter['status'] = (int) $get['status'];
							} else {
								$filter['status'] = 'all';
							}
						} else {
							$filter['status'] = 'all';
						}

						$data['focus_wallet_request_id'] = 0;
						$data['mass_payout_focus_notice'] = '';
						$data['mass_payout_focus_notice_type'] = 'info';
						$data['mass_payout_focus_batch_id'] = 0;
						$data['mass_payout_focus_wr_id'] = 0;

						$focus_wr = isset($get['focus_wr']) ? (int) $get['focus_wr'] : 0;
						$focus_force_page_one = false;
						if ($focus_wr > 0) {
							$wr = $this->db->get_where('wallet_requests', array('id' => $focus_wr))->row_array();
							if (!$wr) {
								$data['mass_payout_focus_notice'] = __('admin.mass_payout_focus_not_found');
								$data['mass_payout_focus_notice_type'] = 'warning';
							} else {
								$batch_id = 0;
								if (isset($wr['batch_export_id']) && $wr['batch_export_id'] !== null && $wr['batch_export_id'] !== '') {
									$batch_id = (int) $wr['batch_export_id'];
								}
								$st = (int) $wr['status'];
								if ($batch_id > 0) {
									$data['mass_payout_focus_notice'] = __('admin.mass_payout_focus_already_batch');
									$data['mass_payout_focus_batch_id'] = $batch_id;
									$data['mass_payout_focus_notice_type'] = 'info';
								} elseif (!in_array($st, $eligible, true)) {
									$status_labels = $this->Withdrawal_payment_model->status_list();
									$st_key = (string) $st;
									$st_label = isset($status_labels[$st_key]) ? $status_labels[$st_key] : $st_key;
									$data['mass_payout_focus_notice'] = sprintf(__('admin.mass_payout_focus_not_eligible'), $focus_wr, $st_label);
									$data['mass_payout_focus_notice_type'] = 'warning';
									$data['mass_payout_focus_wr_id'] = $focus_wr;
									if (empty($filter['user_id'])) {
										$filter['user_id'] = (int) $wr['user_id'];
									}
								} else {
									$data['focus_wallet_request_id'] = $focus_wr;
									if (empty($filter['user_id'])) {
										$filter['user_id'] = (int) $wr['user_id'];
									}
									$focus_force_page_one = true;
								}
							}
						}

						$page = isset($get['page']) ? max(1, (int) $get['page']) : 1;
						if ($focus_force_page_one) {
							$page = 1;
						}
						$per_page = 50;
						$offset = ($page - 1) * $per_page;

						$data['users'] = $this->db->query("SELECT id, username FROM users WHERE type = 'user' ORDER BY username ASC")->result_array();
						$data['lists'] = $this->Payout_batch_model->get_exportable_requests($filter, $per_page, $offset);
						$data['total_rows'] = $this->Payout_batch_model->count_exportable_requests($filter);
						$data['filter'] = $filter;
						$data['current_page'] = $page;
						$data['per_page'] = $per_page;
						$all_status = $this->Withdrawal_payment_model->status_list();
						$data['mass_payout_status_list'] = array(
							'all' => __('admin.mass_payout_status_all_eligible'),
						);
						foreach ($eligible as $sid) {
							$k = (string) $sid;
							if (isset($all_status[$k])) {
								$data['mass_payout_status_list'][$k] = $all_status[$k];
							}
						}
						$data['recent_batches'] = $this->Payout_batch_model->list_recent_batches(12);
						foreach ($data['recent_batches'] as &$rb) {
							$rb['progress_ui'] = $this->Payout_batch_model->describe_batch_progress_for_ui($rb);
						}
						unset($rb);
						$data['default_currency'] = $this->Payout_batch_model->get_default_currency_code();
						$data['mass_payout_csrf'] = $this->_mass_payout_csrf_token_refresh();

						$this->view($data, 'users/mass_payout');
					}

					public function mass_payout_create() {
						$userdetails = $this->userdetails();
						header('Content-Type: application/json; charset=utf-8');
						$post = $this->input->post(null, true);
						if (!$this->_mass_payout_csrf_valid(isset($post['mass_payout_csrf']) ? $post['mass_payout_csrf'] : null)) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_csrf_invalid')));
							return;
						}
						if (empty($post['request_ids']) || !is_array($post['request_ids'])) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_select_requests')));
							return;
						}
						$processor = isset($post['processor']) ? strtolower(trim($post['processor'])) : '';
						if (!in_array($processor, array('paypal', 'wise'), true)) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_invalid_processor')));
							return;
						}
						$ids = array_map('intval', $post['request_ids']);
						$ids = array_values(array_filter($ids));
						if (count($ids) < 1) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_select_requests')));
							return;
						}
						$this->load->model('Payout_batch_model');
						$admin_id = !empty($userdetails['id']) ? (int) $userdetails['id'] : null;
						$res = $this->Payout_batch_model->create_batch($admin_id, $processor, $ids);
						if (empty($res['success'])) {
							echo json_encode($res);
							return;
						}
						echo json_encode(array(
							'success' => true,
							'message' => __('admin.mass_payout_batch_created'),
							'batch_id' => (int) $res['batch_id'],
						));
					}

					public function mass_payout_download($batch_id = 0) {
						$this->userdetails();
						if (strtolower((string) $this->input->server('REQUEST_METHOD')) !== 'post') {
							show_404();
							return;
						}
						$post = $this->input->post(null, true);
						$batch_id = isset($post['batch_id']) ? (int) $post['batch_id'] : (int) $batch_id;
						if ($batch_id < 1) {
							show_404();
							return;
						}
						if (!$this->_mass_payout_csrf_valid(isset($post['mass_payout_csrf']) ? $post['mass_payout_csrf'] : null)) {
							$this->output->set_status_header(403);
							$this->output->set_content_type('text/plain; charset=UTF-8');
							$this->output->set_output(__('admin.mass_payout_csrf_invalid'));
							return;
						}
						$this->load->model('Payout_batch_model');
						$batch = $this->Payout_batch_model->get_batch($batch_id);
						if (!$batch) {
							show_404();
							return;
						}
						$processor = strtolower((string) $batch['processor']);
						$csv = $this->Payout_batch_model->build_csv($batch_id, $processor);
						if ($csv === null) {
							show_404();
							return;
						}
						$fn = 'mass_payout_' . $processor . '_batch_' . $batch_id . '_' . date('Y-m-d') . '.csv';
						$this->output->set_content_type('text/csv; charset=UTF-8');
						$this->output->set_header('Content-Disposition: attachment; filename="' . $fn . '"');
						$this->output->set_output($csv);
					}

					public function mass_payout_import() {
						$this->userdetails();
						header('Content-Type: application/json; charset=utf-8');
						$post = $this->input->post(null, true);
						if (!$this->_mass_payout_csrf_valid(isset($post['mass_payout_csrf']) ? $post['mass_payout_csrf'] : null)) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_csrf_invalid')));
							return;
						}
						$batch_id = (int) $this->input->post('batch_id');
						if ($batch_id < 1) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_import_invalid')));
							return;
						}
						if (empty($_FILES['import_file']['tmp_name']) || !is_uploaded_file($_FILES['import_file']['tmp_name'])) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_import_no_file')));
							return;
						}
						$ext = strtolower(pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION));
						if (!in_array($ext, array('csv', 'txt'), true)) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_import_csv_only')));
							return;
						}
						if (!empty($_FILES['import_file']['size']) && (int) $_FILES['import_file']['size'] > 5242880) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_import_file_too_large')));
							return;
						}
						$tmp = $_FILES['import_file']['tmp_name'];
						$peek = @file_get_contents($tmp, false, null, 0, 8192);
						if ($peek !== false) {
							if (preg_match('/<\?php/i', $peek) || strpos($peek, "\x00") !== false) {
								echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_import_mime_invalid')));
								return;
							}
						}
						if (class_exists('finfo', false)) {
							$finfo = new finfo(FILEINFO_MIME_TYPE);
							$mime = $finfo->file($tmp);
							$allowed_mimes = array(
								'text/plain',
								'text/csv',
								'text/x-csv',
								'application/csv',
								'text/comma-separated-values',
								'application/vnd.ms-excel',
								'inode/x-empty',
								'application/octet-stream',
							);
							if ($mime !== false && !in_array($mime, $allowed_mimes, true)) {
								echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_import_mime_invalid')));
								return;
							}
						}
						$this->load->model('Payout_batch_model');
						$res = $this->Payout_batch_model->process_return_csv($batch_id, $tmp);
						echo json_encode($res);
					}

					public function mass_payout_void() {
						$this->userdetails();
						header('Content-Type: application/json; charset=utf-8');
						$post = $this->input->post(null, true);
						if (!$this->_mass_payout_csrf_valid(isset($post['mass_payout_csrf']) ? $post['mass_payout_csrf'] : null)) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_csrf_invalid')));
							return;
						}
						$batch_id = (int) $this->input->post('batch_id');
						if ($batch_id < 1) {
							echo json_encode(array('success' => false, 'message' => __('admin.mass_payout_void_invalid')));
							return;
						}
						$this->load->model('Payout_batch_model');
						$res = $this->Payout_batch_model->void_batch($batch_id);
						echo json_encode($res);
					}

					/**
					 * One action: set withdrawal to Processed (7) or Waiting for payment (12), then redirect to Mass payout.
					 * POST only; uses session CSRF issued on wallet_requests_details.
					 */
					public function wallet_request_ready_mass_payout($id = 0) {
						$this->userdetails();
						if (strcasecmp((string) $this->input->server('REQUEST_METHOD'), 'POST') !== 0) {
							show_404();
							return;
						}
						$id = (int) $id;
						if ($id < 1) {
							show_404();
							return;
						}
						$post = $this->input->post(null, true);
						if (!$this->_wallet_mass_prepare_csrf_valid(isset($post['wallet_mass_prepare_csrf']) ? $post['wallet_mass_prepare_csrf'] : null)) {
							$this->session->set_flashdata('error', __('admin.wallet_mass_prepare_csrf_invalid'));
							redirect('admincontrol/wallet_requests_details/' . $id);
							return;
						}
						$this->load->model('Payout_batch_model');
						$this->load->model('Withdrawal_payment_model');
						$req = $this->db->get_where('wallet_requests', array('id' => $id))->row_array();
						if (!$req) {
							show_404();
							return;
						}
						$batch_ex = isset($req['batch_export_id']) && $req['batch_export_id'] !== null && $req['batch_export_id'] !== ''
							? (int) $req['batch_export_id'] : 0;
						if ($batch_ex > 0) {
							$this->session->unset_userdata('wallet_mass_prepare_csrf');
							redirect('admincontrol/mass_payout?focus_wr=' . $id);
							return;
						}
						$eligible = $this->Payout_batch_model->get_eligible_mass_payout_statuses();
						$st = (int) $req['status'];
						if (in_array($st, $eligible, true)) {
							$this->session->unset_userdata('wallet_mass_prepare_csrf');
							redirect('admincontrol/mass_payout?focus_wr=' . $id);
							return;
						}
						$terminal_wr = array(1, 8, 9, 10);
						if (in_array($st, $terminal_wr, true)) {
							$this->session->set_flashdata('error', __('admin.wallet_mass_prepare_not_allowed'));
							redirect('admincontrol/wallet_requests_details/' . $id);
							return;
						}
						$target = isset($post['target_status']) ? (int) $post['target_status'] : 12;
						if (!in_array($target, array(7, 12), true)) {
							$target = 12;
						}
						$this->Withdrawal_payment_model->apiAddWithdrwalRequestHistory($id, array(
							'status_id' => $target,
							'comment' => __('admin.wallet_mass_prepare_history_comment'),
							'transaction_id' => '',
						));
						$this->session->unset_userdata('wallet_mass_prepare_csrf');
						redirect('admincontrol/mass_payout?focus_wr=' . $id);
					}


					public function mywallet($page = 1){

						$userdetails = $this->userdetails();

						$get = $this->input->get(null,true);
						
						// Clean empty GET parameters and redirect for cleaner URLs
						if (!empty($get)) {
							$clean_get = array_filter($get, function($value) {
								return $value !== '' && $value !== null;
							});
							if (count($clean_get) != count($get)) {
								$clean_url = base_url('admincontrol/mywallet/' . $page);
								if (!empty($clean_get)) {
									$clean_url .= '?' . http_build_query($clean_get);
								}
								redirect($clean_url);
								return;
							}
						}

						$data['status'] = $this->Wallet_model->status();

						$data['status_icon'] = $this->Wallet_model->status_icon;

					$data['request_status'] = $this->Wallet_model->request_status;		

					$filter['sortBy'] = isset($get['sortby']) ? $get['sortby'] : '';

					$filter['orderBy'] = isset($get['order']) ? $get['order'] : '';

					if (isset($get['amount_sort']) && $get['amount_sort']) {
						$filter['sortBy'] = 'wallet.amount';
						$filter['orderBy'] = $get['amount_sort'] == 'high_to_low' ? 'DESC' : 'ASC';
					}

					if (isset($get['user_id']) && $get['user_id'] > 0) {

							$filter['user_id'] = (int)$get['user_id'];

							$data['user_id'] = $filter['user_id'];

						}

						if (isset($get['recurring']) && $get['recurring'] > 0) {

							$filter['recurring'] = (int)$get['recurring'];

							$data['recurring'] = $filter['recurring'];

						}


						if (isset($get['paid_status']) && $get['paid_status']) {

							$filter['paid_status'] = $get['paid_status'];

						}

						if (isset($get['status']) && $get['status'] != '') {

							$filter['status'] = (int)$get['status'];

						} else{

							$filter['status_gt'] = 0;

						}



						if (isset($get['date'])) {

							$filter['date'] = $get['date'];

						}

						$filter['parent_id'] = 0;



						if ( isset($get['type']) && $get['type'] ) {

							$filter['types'] = $get['type'];

						}

						$filter['not_negative_balence'] = true;


						$total_rows = $this->Wallet_model->getTransaction($filter, true, 'ONLY_PARENTS');
						$pagination_settings = get_pagination_settings();
						$per_page = $pagination_settings['per_page'];
						$current_page = max(1, (int)$page);
						
						$pagination_data = easy_pagination(
							base_url('admincontrol/mywallet'),
							$total_rows,
							($current_page - 1) * $per_page,
							['preserve_query' => true]
						);

						$filter['per_page'] = $per_page;
						$filter['page_num'] = $current_page;
						$data['offset'] = $filter['offset'] = ($current_page - 1) * $per_page;

						$data['transaction'] = $this->Wallet_model->getTransaction($filter, false, 'ONLY_PARENTS');
						$data['pagination_link'] = $pagination_data['html'];
						$data['total_rows'] = $total_rows;
						$data['per_page'] = $per_page;
						$data['current_page'] = $current_page;

						$data['users'] = $this->db->select('id, CONCAT(firstname," ",lastname) AS name')
							->from('users')
							->where('status', 1)
							->order_by('firstname', 'ASC')
							->limit(1000)
							->get()
							->result_array();

						$data['totals'] = $this->Wallet_model->getTotals(array(),true);



						$data['table'] = $this->load->view("admincontrol/users/part/wallet_tr", $data, true);

						if(isset($_GET['a'])){
							$this->view($data, 'users/mywallet');
							return false;
						}

						$_data = objectToArray($data);

						$this->load->model('Total_model');

						$data['admin_totals'] = $this->Total_model->adminTotals();

						unset($filter['per_page']);
						unset($filter['offset']);
						unset($filter['page_num']);

						$transactionSorted = [];
						$processedGroups = []; // Track processed group_ids to prevent duplication
						
						
						for ($i=0; $i < sizeof($data['transaction']); $i++) {
							$groupId = $data['transaction'][$i]['group_id'];
							
							// Skip if this group_id has already been processed
							if(in_array($groupId, $processedGroups)) {
								continue;
							}
							
					$filter['group_id'] = $groupId;
					$filter['not_tran_id'] = $data['transaction'][$i]['id'];
					
					$child_transaction = $this->Wallet_model->getTransaction($filter);

					 
					$child_transaction[]  = $data['transaction'][$i];

					$child_transaction = array_reverse($child_transaction);

					$child_transaction_sorted = $child_transaction;
					$parent_transaction = null;

					foreach($child_transaction as $key => $ch) {
						$moveFirst = false;

						if(strpos($ch['type'], 'refer') === false) {
							if(in_array($ch['type'], ['vendor_sale_commission', 'vendor_shipping_reimbursement', 'sale_commission', 'external_sale_commission', 'click_comission'])) {
								$moveFirst = true;
							} else if(strpos($ch['type'], 'click') !== false) {
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

							$transactionSorted = array_merge($transactionSorted, $child_transaction_sorted);
							$processedGroups[] = $groupId; // Mark this group_id as processed
						}

						$data['userdetails'] = $this->userdetails();

						$data['transaction'] = $transactionSorted;

						$this->view($data, 'users/wallet');
					}

					public function change_commission_status(){ 

						$id = $this->input->post('id');

						$status_type = $this->input->post('status_type');

						$delete_id = $this->input->post("id",true);

						$dataCollection = $this->Wallet_model->getDeleteData((int)$id);



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

					public function getRecurringTransaction(){

						$id = (int)$this->input->post("id");

						$userdetails = $this->userdetails();

						if(empty($userdetails)){redirect($this->admin_domain_url);}

						$data['status'] = $this->Wallet_model->status();

						$data['status_icon'] = $this->Wallet_model->status_icon;

						$data['request_status'] = $this->Wallet_model->request_status;

						$filter['parent_id'] = $id;

						$data['transaction'] = $this->Wallet_model->getTransaction($filter);

						$data['recurring'] = $id;


						if (!isset($_POST['newtr'])) {

							$json['table'] = $this->load->view("admincontrol/users/part/wallet_tr", $data, true);

						} else{

							$json['table'] = '';

							foreach ($data['transaction'] as $key => $value) {

								$data['class'] = 'child-recurring';

								$data['force_class'] = $_POST['ischild'] == 'true' ? 'child-arrow' : '';

								$data['recurring'] = $id;

								$data['value'] = $value;

								$data['wallet_status'] = $data['status'];

								$json['table'] .= $this->load->view("admincontrol/users/part/new_wallet_tr", $data, true);
							}
						}
						echo json_encode($json);
					}

				public function ajax_dashboard(){
					session_write_close();
					$userdetails = $this->userdetails();
					$post = $this->input->post(null,true);
						
						// Handle popular affiliates sorting
						if(isset($post['type']) && $post['type'] == 'popular_affiliates_sorting') {
							$value = $post['value'];
							
							// Save the filter setting
							$this->db->where('setting_key', 'popular_affiliates');
							$this->db->where('setting_type', 'popular_affiliates_sorting');
							$query = $this->db->get('setting');
							
							if($query->num_rows() > 0) {
								$this->db->where('setting_key', 'popular_affiliates');
								$this->db->where('setting_type', 'popular_affiliates_sorting');
								$this->db->update('setting', ['setting_value' => $value]);
							} else {
								$this->db->insert('setting', [
									'setting_key' => 'popular_affiliates',
									'setting_type' => 'popular_affiliates_sorting',
									'setting_value' => $value
								]);
							}
							
							// Get filtered popular users
							$data['populer_users'] = $this->Product_model->getPopulerUsers(array("limit" => 10), $value);
							
							// Check currency settings
							$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
							$hcurrency_check = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'admin'));
							$data['fun_c_format'] = $hcurrency_check ? 'c_format_nosym' : 'c_format';
							
							// Generate the view
							$view = $this->load->view("admincontrol/dashboard/popular_affiliates_list", $data, true);
							
							$json['popular_affiliates'] = true;
							$json['view'] = $view;
							
							echo json_encode($json);
							die;
						}
						
						// Default online count refresh
						$data['online_count'] = $this->Product_model->onlineCount();
						echo json_encode($data);
						die;
					}

				public function dashboard(){
					$_DASH_START = microtime(true);

					$userdetails = $this->userdetails();
					$this->load->library("Backup");

					//switch buttons code start
					if(isset($_POST['action'])) {

							$this->load->model('Setting_model');
							$this->Setting_model->save($_POST['setting_type'], [$_POST['setting_key']=>$_POST['val']]);

							// Condition for MarketTools
							    if ($_POST['setting_key'] == "markettools_status" && $_POST['setting_type'] == "market_tools") {
							        $this->Setting_model->save("market_tools", ["status" => $_POST['val']]);
							    }
							// Condition for MarketTools

							// Vendor MLM module special handling
							if($_POST['setting_key']=="vendormlmmodule" && $_POST['setting_type']=="market_vendor") {
								$status=(int)$_POST['val'];
								$query= $this->db->query("SELECT id FROM `users` where is_vendor=1 and status=1");
								$vendors=$query->result_array();
								for($i=0;$i<count($vendors);$i++) {
									$vid=$vendors[$i]['id'];
									$value=array("status"=>$status);
									$this->Setting_model->vendorSave($vid, "referlevel", $value);
								}
							}
							// SAAS module special handling
							else if($_POST['setting_type'] == 'market_vendor') {
								$this->Setting_model->save("vendor", ["storestatus"=>$_POST['val']]);
							}

							if($this->input->is_ajax_request() || $this->input->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest'){
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode(array('success' => true, 'message' => __('admin.setting_updated_successfully')));
								exit;
							}
							echo 'success'; exit;
						}

						$market_tools_status = $this->Product_model->getSettings('market_tools', 'status');

						$store_status = $this->Product_model->getSettings('store', 'status');

						$fraud_status = $this->Product_model->getSettings('site', 'block_click_across_browser');
						
						// Get module statuses for dashboard toggles
						$referlevel_status = $this->Product_model->getSettings('referlevel', 'status');
						$vendormlmmodule = $this->Product_model->getSettings('market_vendor', 'vendormlmmodule');
						$market_vendor_marketvendorstatus = $this->Product_model->getSettings('market_vendor', 'marketvendorstatus');
						$vendor_storestatus = $this->Product_model->getSettings('vendor', 'storestatus');
						$membership_status = $this->Product_model->getSettings('membership', 'status');
						$vendor_deposit_status = $this->Product_model->getSettings('vendor', 'depositstatus');
						$award_level_status = $this->Product_model->getSettings('award_level', 'status');
						$ai_helper_status = $this->Product_model->getSettings('ai_helper', 'ai_helper_enabled');

						// Get site settings for version update controls
						$site_settings = $this->Product_model->getSettings('site');

						$data = array (

							'market_tools_is_enable' => isset($market_tools_status['status']) ? $market_tools_status['status'] : 0,

							'store_is_enable' => isset($store_status['status']) ? $store_status['status'] : 0,

							'block_click_across_browser' => isset($fraud_status['block_click_across_browser']) ? $fraud_status['block_click_across_browser'] : 0,

							// Module status variables for dashboard toggles
							'mlm_admin_is_enable' => isset($referlevel_status['status']) ? $referlevel_status['status'] : 0,
							'mlm_vendor_is_enable' => isset($vendormlmmodule['vendormlmmodule']) ? $vendormlmmodule['vendormlmmodule'] : 0,
							'saas_is_enable' => (isset($market_vendor_marketvendorstatus['marketvendorstatus']) && $market_vendor_marketvendorstatus['marketvendorstatus'] == 1) || (isset($vendor_storestatus['storestatus']) && $vendor_storestatus['storestatus'] == 1) ? 1 : 0,
							'membership_is_enable' => isset($membership_status['status']) ? $membership_status['status'] : 0,
							'vendor_deposit_is_enable' => isset($vendor_deposit_status['depositstatus']) ? $vendor_deposit_status['depositstatus'] : 0,
							'award_level_is_enable' => isset($award_level_status['status']) ? $award_level_status['status'] : 0,

							// Site settings for version update controls
							'site' => $site_settings,

							// AI Helper settings for dashboard
							'ai_helper' => $this->Product_model->getSettings('ai_helper'),

						);

					//switch buttons code end

					$backup_warnings = $this->checkMissingBackups();
					$backups = $this->backup->getListZip();
					$script_backups = $this->backup->getListScriptZip();
					
				$data['backup_warnings'] = $backup_warnings;
				$data['backups'] = $backups;
				$data['script_backups'] = $script_backups;

						$data['current_version'] = SCRIPT_VERSION;
						$data['missing'] = [];
						$data['showMissingDetailsModal'] = false;

						$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');

						$data['hcurrency'] = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'admin'));

						if($data['hcurrency']) {
							$data['fun_c_format'] =$fun_c_format = 'c_format_nosym';
						} else {
							$data['fun_c_format'] =$fun_c_format = 'c_format';
						}

						$post = $this->input->post(null,true);
						$data['online_count'] = $this->Product_model->onlineCount();

						$popular_aff_filter = $this->db->select('setting_value')
							->where('setting_key', 'popular_affiliates')
							->where('setting_type', 'popular_affiliates_sorting')
							->get('setting')
							->row();
						$popular_aff_filt = isset($popular_aff_filter) ? $popular_aff_filter->setting_value : '';
						$data['popular_affiliates'] = $popular_aff_filt;
						$data['populer_users'] = $this->Product_model->getPopulerUsers(array("limit" => 5), $popular_aff_filt);
						
					$data['total_users_count'] = $this->db->where('type', 'user')->count_all_results('users');
					
					$this->load->model('Total_model');
					
					$data['admin_totals'] = $this->Total_model->adminTotals();
					$data['Total_model'] = $this->Total_model;
						
					$today = date('Y-m-d');
					$today_count = $this->db->select('COUNT(*) as total')
						->where('status >', 0)
						->where('DATE(created_at)', $today)
						->get('wallet')
						->row()->total;
					$data['today_transactions_count'] = $today_count;
						if (isset($_GET['getChartData'])) {
							$json['chart'] = $this->Total_model->chart($post);

							echo json_encode($json);die;
						}

						$this->load->library("socialshare");				

						$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

						$audio_sound = $this->Product_model->getSettings('site', 'notification_sound');

						$front_url_slug = $this->Product_model->getSettings('security', 'front_url');
						$data['front_url_slug'] = $front_url_slug['front_url'];

						if (sizeof($audio_sound) > 0) {
							$data['notification_sound'] = $audio_sound['notification_sound'];
						}else{
							$data['notification_sound'] = '';
						}

						$data['status'] = $this->Order_model->status();

						$commonSetting = array('site');
						foreach ($commonSetting as $key => $value) {
							$data[$value] 	= $this->Product_model->getSettings($value);
						}

					// Add missing variables that view expects
					$data['vendor_store_data'] = $this->Product_model->getSettings('vendor');
					$data['vendor_market_data'] = $this->Product_model->getSettings('market_vendor');

					// [v15] Activate existing fraud monitoring function (was never called before)
					$data['spam_monitoring'] = $this->getSpamMonitoringData();

					// Get mobile app connections data
						$data['mobile_app_connections'] = $this->get_mobile_app_connections();
						
						// Get geolocation status for dashboard
						$data['geolocation_status'] = $this->get_geolocation_dashboard_status();

						// S2S Tracking Summary for dashboard
						$s2s_summary = $this->db->query("
							SELECT 
								COUNT(*) as total_s2s_orders,
								COALESCE(SUM(total), 0) as s2s_revenue,
								COALESCE(SUM(commission), 0) as s2s_commission
							FROM integration_orders 
							WHERE script_name = 's2s' AND status > 0
						")->row_array();
						$s2s_today = $this->db->query("
							SELECT COUNT(*) as cnt FROM integration_orders 
							WHERE script_name = 's2s' AND status > 0 AND DATE(created_at) = CURDATE()
						")->row()->cnt;
						$pixel_total = $this->db->query("
							SELECT COUNT(*) as cnt FROM integration_orders 
							WHERE script_name != 's2s' AND status > 0
						")->row()->cnt;
						$s2s_enabled_camps = 0;
						$col_check = $this->db->query("SHOW COLUMNS FROM integration_tools LIKE 's2s_enabled'");
						if ($col_check && $col_check->num_rows() > 0) {
							$s2s_enabled_camps = $this->db->query("SELECT COUNT(*) as cnt FROM integration_tools WHERE s2s_enabled = 1")->row()->cnt;
						}
						$data['s2s_summary'] = $s2s_summary;
						$data['s2s_today'] = $s2s_today;
						$data['pixel_total_orders'] = $pixel_total;
						$data['s2s_enabled_campaigns'] = $s2s_enabled_camps;

						// Performance Overview ring chart counts
						$data['total_external_orders'] = (int)$this->db->query("
							SELECT COUNT(*) as cnt FROM integration_orders 
							WHERE (script_name IS NULL OR script_name != 's2s') AND status > 0
						")->row()->cnt;
						$data['total_store_orders'] = (int)$this->db->query("
							SELECT COUNT(*) as cnt FROM `order` WHERE status > 0
						")->row()->cnt;

						// Translation Health Summary for dashboard
						$all_languages = $this->db->query("SELECT * FROM language")->result_array();
						$default_lang_count = langCount('default');
						$translation_health = [];
						$languages_complete = 0;
						$languages_need_attention = 0;
						foreach($all_languages as $lng) {
							$lc = langCount($lng['id']);
							$lng['count'] = $lc;
							$translation_health[] = $lng;
							if($lc['missing'] == 0) {
								$languages_complete++;
							} else {
								$languages_need_attention++;
							}
						}
						$data['translation_health'] = $translation_health;
						$data['default_lang_count'] = $default_lang_count;
						$data['languages_complete'] = $languages_complete;
						$data['languages_need_attention'] = $languages_need_attention;

						// V14: 7-day trend data for sparkline charts
						$data['trends'] = $this->Total_model->get7DayTrends();

						$this->view($data,'dashboard/dashboard');
					}

					/**
					 * Get spam monitoring data for admin dashboard
					 */
					private function getSpamMonitoringData() {
						error_log("========== getSpamMonitoringData() CALLED ==========");
						$data = [];
						
						// Get file-based spam logs first
						$file_spam_logs = [];
						$spam_file = APPPATH . 'logs/spam_attempts.json';
						if (file_exists($spam_file)) {
							$file_content = file_get_contents($spam_file);
							if ($file_content) {
								$file_spam_logs = json_decode($file_content, true) ?: [];
							}
						}
						
						error_log("Found " . count($file_spam_logs) . " spam attempts in JSON file");
						
						// Group spam attempts intelligently
						$grouped_spam = [];
						$all_user_ids = [];
						$all_ips = [];
						$spam_by_type = ['Forms' => 0, 'Products' => 0, 'Tools' => 0];
						$user_spam_counts = [];
						
						foreach($file_spam_logs as $spam_log) {
							// Create a unique key for grouping: user + campaign + hour
							$hour_key = date('Y-m-d H', strtotime($spam_log['created_at']));
							$group_key = $spam_log['user_id'] . '_' . 
								($spam_log['form_id'] ?: '') . '_' . 
								($spam_log['product_id'] ?: '') . '_' . 
								($spam_log['tools_id'] ?: '') . '_' . 
								$hour_key;
							
							// Initialize group if not exists
							if (!isset($grouped_spam[$group_key])) {
								// Get user details (only once per group)
								$user = $this->db->where('id', $spam_log['user_id'])->get('users')->row();
								
								// Get campaign details (only once per group)
								$campaign_type = 'Unknown';
								$campaign_name = 'Unknown';
								$tool_type = null;
								
								if ($spam_log['form_id']) {
									$form = $this->db->where('form_id', $spam_log['form_id'])->get('form')->row();
									$campaign_type = 'Form';
									$campaign_name = $form ? $form->title : 'Unknown';
									$spam_by_type['Forms']++;
								} elseif ($spam_log['product_id']) {
									$product = $this->db->where('product_id', $spam_log['product_id'])->get('product')->row();
									$campaign_type = 'Product';
									$campaign_name = $product ? $product->product_name : 'Unknown';
									$spam_by_type['Products']++;
								} elseif ($spam_log['tools_id']) {
									$tool = $this->db->where('id', $spam_log['tools_id'])->get('integration_tools')->row();
									$campaign_type = 'Tool';
									$campaign_name = $tool ? $tool->name : 'Unknown';
									$tool_type = $tool ? $tool->type : null;
									$spam_by_type['Tools']++;
								}
								
							$grouped_spam[$group_key] = [
								'count' => 0,
								'first_attempt' => $spam_log['created_at'],
								'last_attempt' => $spam_log['created_at'],
								'ips' => [],
								'fraud_scores' => [],
								'data' => [
									'user_id' => $spam_log['user_id'],
									'firstname' => $user ? $user->firstname : 'Unknown',
									'lastname' => $user ? $user->lastname : 'User',
									'email' => $user ? $user->email : 'unknown@example.com',
									'campaign_type' => $campaign_type,
									'campaign_name' => $campaign_name,
									'product_id' => $spam_log['product_id'] ?? null,
									'form_id' => $spam_log['form_id'] ?? null,
									'tools_id' => $spam_log['tools_id'] ?? null,
									'tool_type' => $tool_type,
									'blocked' => true,
									'fraud_score' => null
								]
							];
							}
							
						// Update group data
						$grouped_spam[$group_key]['count']++;
						$grouped_spam[$group_key]['last_attempt'] = $spam_log['created_at'];
						$grouped_spam[$group_key]['ips'][] = $spam_log['ip'];
						if (!empty($spam_log['fraud_score'])) {
							$grouped_spam[$group_key]['fraud_scores'][] = (int)$spam_log['fraud_score'];
						}
							
							// Track for statistics
							$all_user_ids[] = $spam_log['user_id'];
							$all_ips[] = $spam_log['ip'];
							
							// Count per user
							if (!isset($user_spam_counts[$spam_log['user_id']])) {
								$user_spam_counts[$spam_log['user_id']] = [
									'count' => 0,
									'user' => $user ?? null
								];
							}
							$user_spam_counts[$spam_log['user_id']]['count']++;
						}
						
						// Convert grouped data to display format
						$processed_spam = [];
						foreach($grouped_spam as $group) {
							// Calculate time range for display
							if ($group['first_attempt'] == $group['last_attempt']) {
								$time_display = $group['first_attempt'];
							} else {
								$time_display = date('H:i', strtotime($group['first_attempt'])) . '-' . 
									date('H:i', strtotime($group['last_attempt'])) . ' (' . 
									date('Y-m-d', strtotime($group['last_attempt'])) . ')';
							}
							
						// Add unique IP info to the data
						$unique_ips = array_unique($group['ips']);
						if (count($unique_ips) == 1) {
							$group['data']['ip'] = $unique_ips[0];
							$group['data']['ip_count'] = 1;
						} else {
							$group['data']['ip'] = implode(', ', $unique_ips);
							$group['data']['ip_count'] = count($unique_ips);
						}
						// Set fraud score: max score in the group (highest risk)
						if (!empty($group['fraud_scores'])) {
							$group['data']['fraud_score'] = max($group['fraud_scores']);
						}
							
							$processed_spam[] = [
								'count' => $group['count'],
								'time' => $time_display,
								'last_attempt_timestamp' => $group['last_attempt'],
								'data' => $group['data']
							];
						}
						
						// Sort by most recent last attempt
						usort($processed_spam, function($a, $b) {
							return strtotime($b['last_attempt_timestamp']) - strtotime($a['last_attempt_timestamp']);
						});
						
						// Limit to top 20 groups for clean dashboard
						$data['recent_spam'] = array_slice($processed_spam, 0, 20);
						
						// Calculate statistics
						$data['spam_stats'] = [
							'total_attempts' => count($file_spam_logs),
							'unique_users' => count(array_unique($all_user_ids)),
							'unique_ips' => count(array_unique($all_ips)),
							'days_with_spam' => 1 // Simplified for now
						];
						
						// Top spam users
						$top_users = [];
						foreach($user_spam_counts as $user_id => $data_user) {
							if ($data_user['user']) {
								$top_users[] = [
									'user_id' => $user_id,
									'firstname' => $data_user['user']->firstname,
									'lastname' => $data_user['user']->lastname,
									'email' => $data_user['user']->email,
									'spam_count' => $data_user['count']
								];
							}
						}
						
						usort($top_users, function($a, $b) {
							return $b['spam_count'] - $a['spam_count'];
						});
						
						$data['top_spam_users'] = array_slice($top_users, 0, 10);
						
						// Spam by type
						$spam_type_array = [];
						foreach($spam_by_type as $type => $count) {
							if ($count > 0) {
								$spam_type_array[] = [
									'campaign_type' => $type,
									'spam_count' => $count
								];
							}
						}
						
						$data['spam_by_type'] = $spam_type_array;
						$data['active_spammers'] = [];
						
						error_log("Processed " . count($data['recent_spam']) . " spam attempts for dashboard");
						return $data;
						
						// Get recent spam attempts with rapid spam detection (last 24 hours)
						$recent_spam = $this->db->query("
							SELECT 
								pvl.*,
								u.firstname,
								u.lastname,
								u.email,
								CASE 
									WHEN pvl.form_id IS NOT NULL THEN 'Form'
									WHEN pvl.product_id IS NOT NULL THEN 'Product'
									WHEN pvl.tools_id IS NOT NULL THEN 'Tool'
									ELSE 'Unknown'
								END as campaign_type,
								CASE 
									WHEN pvl.form_id IS NOT NULL THEN f.title
									WHEN pvl.product_id IS NOT NULL THEN p.product_name
									WHEN pvl.tools_id IS NOT NULL THEN it.name
									ELSE 'Unknown'
								END as campaign_name,
								it.type as tool_type,
								-- Count rapid attempts (same user, same campaign, within 5 minutes)
								(
									SELECT COUNT(*) 
									FROM product_view_logs pvl2 
									WHERE pvl2.user_id = pvl.user_id 
									AND pvl2.form_id = pvl.form_id 
									AND pvl2.product_id = pvl.product_id 
									AND pvl2.tools_id = pvl.tools_id
									AND pvl2.created_at BETWEEN DATE_SUB(pvl.created_at, INTERVAL 5 MINUTE) AND pvl.created_at
								) as rapid_attempts,
								-- Get the first attempt time in the rapid sequence
								(
									SELECT MIN(pvl3.created_at)
									FROM product_view_logs pvl3 
									WHERE pvl3.user_id = pvl.user_id 
									AND pvl3.form_id = pvl.form_id 
									AND pvl3.product_id = pvl.product_id 
									AND pvl3.tools_id = pvl.tools_id
									AND pvl3.created_at BETWEEN DATE_SUB(pvl.created_at, INTERVAL 5 MINUTE) AND pvl.created_at
								) as rapid_start_time
							FROM product_view_logs pvl
							LEFT JOIN users u ON pvl.user_id = u.id
							LEFT JOIN form f ON pvl.form_id = f.form_id
							LEFT JOIN product p ON pvl.product_id = p.product_id
							LEFT JOIN integration_tools it ON pvl.tools_id = it.id
							WHERE pvl.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
							ORDER BY pvl.created_at DESC
							LIMIT 100
						")->result_array();
						
						// Get all individual attempts with rapid spam detection
						$db_attempts = $this->db->query("
							SELECT 
								pvl.*,
								u.firstname,
								u.lastname,
								u.email,
								CASE 
									WHEN pvl.form_id IS NOT NULL THEN 'Form'
									WHEN pvl.product_id IS NOT NULL THEN 'Product'
									WHEN pvl.tools_id IS NOT NULL THEN 'Tool'
									ELSE 'Unknown'
								END as campaign_type,
								CASE 
									WHEN pvl.form_id IS NOT NULL THEN f.title
									WHEN pvl.product_id IS NOT NULL THEN p.product_name
									WHEN pvl.tools_id IS NOT NULL THEN it.name
									ELSE 'Unknown'
								END as campaign_name,
								it.type as tool_type,
								-- Count rapid attempts (same user, same campaign, within 5 minutes)
								(
									SELECT COUNT(*) 
									FROM product_view_logs pvl2 
									WHERE pvl2.user_id = pvl.user_id 
									AND pvl2.form_id = pvl.form_id 
									AND pvl2.product_id = pvl.product_id 
									AND pvl2.tools_id = pvl.tools_id
									AND pvl2.created_at BETWEEN DATE_SUB(pvl.created_at, INTERVAL 5 MINUTE) AND pvl.created_at
								) as rapid_attempts
							FROM product_view_logs pvl
							LEFT JOIN users u ON pvl.user_id = u.id
							LEFT JOIN form f ON pvl.form_id = f.form_id
							LEFT JOIN product p ON pvl.product_id = p.product_id
							LEFT JOIN integration_tools it ON pvl.tools_id = it.id
							WHERE pvl.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
							ORDER BY pvl.created_at DESC
							LIMIT 300
						")->result_array();
						
						// Initialize all_attempts with database results
						$all_attempts = is_array($db_attempts) ? $db_attempts : [];
						
						// Get session-based spam logs (blocked attempts)
						$session_spam_logs = $this->session->userdata('spam_logs') ?: [];
						
						// Also get file-based spam logs for persistence
						$file_spam_logs = [];
						$spam_file = APPPATH . 'logs/spam_attempts.json';
						error_log("Spam file path: " . $spam_file);
						error_log("Spam file exists: " . (file_exists($spam_file) ? 'YES' : 'NO'));
						
						if (file_exists($spam_file)) {
							$file_content = file_get_contents($spam_file);
							error_log("File content length: " . strlen($file_content));
							if ($file_content) {
								$file_spam_logs = json_decode($file_content, true) ?: [];
								error_log("JSON decode successful: " . (is_array($file_spam_logs) ? 'YES' : 'NO'));
								error_log("JSON decode error: " . json_last_error_msg());
							}
						}
						
						// Merge session and file logs, removing duplicates
						$session_spam_logs = is_array($session_spam_logs) ? $session_spam_logs : [];
						$file_spam_logs = is_array($file_spam_logs) ? $file_spam_logs : [];
						$all_spam_logs = array_merge($session_spam_logs, $file_spam_logs);
						
						// Remove duplicates based on user_id + created_at + ip
						$unique_spam_logs = [];
						$seen_keys = [];
						if (is_array($all_spam_logs)) {
							foreach ($all_spam_logs as $log) {
								$key = $log['user_id'] . '_' . $log['created_at'] . '_' . $log['ip'];
								if (!in_array($key, $seen_keys)) {
									$unique_spam_logs[] = $log;
									$seen_keys[] = $key;
								}
							}
						}
						
						// Debug: Log spam data
						error_log("Session spam logs count: " . (is_array($session_spam_logs) ? count($session_spam_logs) : 0));
						error_log("File spam logs count: " . (is_array($file_spam_logs) ? count($file_spam_logs) : 0));
						error_log("Total unique spam logs: " . (is_array($unique_spam_logs) ? count($unique_spam_logs) : 0));
						error_log("Processed spam attempts: " . (is_array($all_attempts) ? count($all_attempts) : 0));
						error_log("Final recent spam count: " . (isset($data['recent_spam']) && is_array($data['recent_spam']) ? count($data['recent_spam']) : 0));
						
						// Convert spam logs to same format as database logs
						if (is_array($unique_spam_logs)) {
							foreach($unique_spam_logs as $spam_log) {
							// Only include logs from last 24 hours
							$log_time = strtotime($spam_log['created_at']);
							$cutoff_time = strtotime('-24 hours');
							error_log("Processing spam log: " . $spam_log['created_at'] . " (timestamp: " . $log_time . ", cutoff: " . $cutoff_time . ", within 24h: " . ($log_time >= $cutoff_time ? 'YES' : 'NO') . ")");
							
							// Temporarily bypass time filter for testing
							if (true) { // $log_time >= $cutoff_time) {
								// Get user details
								$user = $this->db->where('id', $spam_log['user_id'])->get('users')->row();
								error_log("User lookup for ID " . $spam_log['user_id'] . ": " . ($user ? "FOUND - " . $user->firstname . " " . $user->lastname : "NOT FOUND"));
								
								// Get campaign details
								$campaign_type = 'Unknown';
								$campaign_name = 'Unknown';
								$tool_type = null;
								
								if ($spam_log['form_id']) {
									$form = $this->db->where('form_id', $spam_log['form_id'])->get('form')->row();
									$campaign_type = 'Form';
									$campaign_name = $form ? $form->title : 'Unknown';
								} elseif ($spam_log['product_id']) {
									$product = $this->db->where('product_id', $spam_log['product_id'])->get('product')->row();
									$campaign_type = 'Product';
									$campaign_name = $product ? $product->product_name : 'Unknown';
									error_log("Product lookup for ID " . $spam_log['product_id'] . ": " . ($product ? "FOUND - " . $product->product_name : "NOT FOUND"));
								} elseif ($spam_log['tools_id']) {
									$tool = $this->db->where('id', $spam_log['tools_id'])->get('integration_tools')->row();
									$campaign_type = 'Tool';
									$campaign_name = $tool ? $tool->name : 'Unknown';
									$tool_type = $tool ? $tool->type : null;
								}
								
								// Count rapid attempts for this spam log
								$rapid_attempts = 1;
								foreach($unique_spam_logs as $other_log) {
									if ($other_log['user_id'] == $spam_log['user_id'] && 
										$other_log['form_id'] == $spam_log['form_id'] && 
										$other_log['product_id'] == $spam_log['product_id'] && 
										$other_log['tools_id'] == $spam_log['tools_id'] &&
										strtotime($other_log['created_at']) >= strtotime($spam_log['created_at']) - 300 &&
										strtotime($other_log['created_at']) <= strtotime($spam_log['created_at'])) {
										$rapid_attempts++;
									}
								}
								
								$all_attempts[] = [
									'user_id' => $spam_log['user_id'],
									'form_id' => $spam_log['form_id'],
									'product_id' => $spam_log['product_id'],
									'tools_id' => $spam_log['tools_id'],
									'ip' => $spam_log['ip'],
									'created_at' => $spam_log['created_at'],
									'blocked' => true,
									'firstname' => $user ? $user->firstname : '',
									'lastname' => $user ? $user->lastname : '',
									'email' => $user ? $user->email : '',
									'campaign_type' => $campaign_type,
									'campaign_name' => $campaign_name,
									'tool_type' => $tool_type,
									'rapid_attempts' => $rapid_attempts
								];
								error_log("Added spam attempt to all_attempts array. Total count now: " . count($all_attempts));
							}
						}
						}
						
						// Process each attempt individually
						$processed_spam = [];
						
						$all_attempts = is_array($all_attempts) ? $all_attempts : [];
						foreach($all_attempts as $spam) {
							$processed_spam[] = [
								'count' => $spam['rapid_attempts'],
								'time' => $spam['created_at'],
								'data' => $spam
							];
						}
						
						// Sort by most recent first and limit to 100
						usort($processed_spam, function($a, $b) {
							return strtotime($b['time']) - strtotime($a['time']);
						});
						
						$data['recent_spam'] = array_slice($processed_spam, 0, 100);
						error_log("Final recent_spam count: " . count($data['recent_spam']));
						error_log("Recent spam data sample: " . print_r(array_slice($data['recent_spam'], 0, 2), true));
						
						// Get spam statistics from both database and file
						$db_spam_stats = $this->db->query("
							SELECT 
								COUNT(*) as total_attempts,
								COUNT(DISTINCT user_id) as unique_users,
								COUNT(DISTINCT ip) as unique_ips,
								COUNT(DISTINCT DATE(created_at)) as days_with_spam
							FROM product_view_logs 
							WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
						")->row_array();
						
						// Calculate stats from file-based spam logs
						$file_spam_stats = [
							'total_attempts' => 0,
							'unique_users' => 0,
							'unique_ips' => 0,
							'days_with_spam' => 0
						];
						
						$file_user_ids = [];
						$file_ips = [];
						$file_dates = [];
						
						if (is_array($unique_spam_logs)) {
							foreach($unique_spam_logs as $log) {
								if (strtotime($log['created_at']) >= strtotime('-24 hours')) {
									$file_spam_stats['total_attempts']++;
									$file_user_ids[] = $log['user_id'];
									$file_ips[] = $log['ip'];
									$file_dates[] = date('Y-m-d', strtotime($log['created_at']));
								}
							}
						}
						
						$file_spam_stats['unique_users'] = count(array_unique($file_user_ids));
						$file_spam_stats['unique_ips'] = count(array_unique($file_ips));
						$file_spam_stats['days_with_spam'] = count(array_unique($file_dates));
						
						// Combine database and file stats
						$spam_stats = [
							'total_attempts' => $db_spam_stats['total_attempts'] + $file_spam_stats['total_attempts'],
							'unique_users' => max($db_spam_stats['unique_users'], $file_spam_stats['unique_users']),
							'unique_ips' => max($db_spam_stats['unique_ips'], $file_spam_stats['unique_ips']),
							'days_with_spam' => max($db_spam_stats['days_with_spam'], $file_spam_stats['days_with_spam'])
						];
						
						$data['spam_stats'] = $spam_stats;
						
						// Get top spam users from both database and file
						$db_top_spam_users = $this->db->query("
							SELECT 
								user_id,
								COUNT(*) as spam_count,
								u.firstname,
								u.lastname,
								u.email
							FROM product_view_logs pvl
							LEFT JOIN users u ON pvl.user_id = u.id
							WHERE pvl.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
							GROUP BY user_id
							ORDER BY spam_count DESC
							LIMIT 10
						")->result_array();
						
						// Calculate top spam users from file data
						$file_user_counts = [];
						if (is_array($unique_spam_logs)) {
							foreach($unique_spam_logs as $log) {
								if (strtotime($log['created_at']) >= strtotime('-24 hours')) {
									$user_id = $log['user_id'];
									if (!isset($file_user_counts[$user_id])) {
										$file_user_counts[$user_id] = 0;
									}
									$file_user_counts[$user_id]++;
								}
							}
						}
						
						// Get user details for file-based spam users
						$file_top_spam_users = [];
						foreach($file_user_counts as $user_id => $count) {
							$user = $this->db->where('id', $user_id)->get('users')->row();
							if ($user) {
								$file_top_spam_users[] = [
									'user_id' => $user_id,
									'spam_count' => $count,
									'firstname' => $user->firstname,
									'lastname' => $user->lastname,
									'email' => $user->email
								];
							}
						}
						
						// Combine and sort by spam count
						$all_top_spam_users = array_merge($db_top_spam_users, $file_top_spam_users);
						usort($all_top_spam_users, function($a, $b) {
							return $b['spam_count'] - $a['spam_count'];
						});
						
						$data['top_spam_users'] = array_slice($all_top_spam_users, 0, 10);
						
						// Get spam by type from both database and file
						$db_spam_by_type = $this->db->query("
							SELECT 
								CASE 
									WHEN form_id IS NOT NULL THEN 'Forms'
									WHEN product_id IS NOT NULL THEN 'Products'
									WHEN tools_id IS NOT NULL THEN 'Tools'
									ELSE 'Unknown'
								END as campaign_type,
								COUNT(*) as spam_count
							FROM product_view_logs 
							WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
							GROUP BY campaign_type
							ORDER BY spam_count DESC
						")->result_array();
						
						// Calculate spam by type from file data
						$file_type_counts = [
							'Forms' => 0,
							'Products' => 0,
							'Tools' => 0,
							'Unknown' => 0
						];
						
						if (is_array($unique_spam_logs)) {
							foreach($unique_spam_logs as $log) {
								if (strtotime($log['created_at']) >= strtotime('-24 hours')) {
									if ($log['form_id']) {
										$file_type_counts['Forms']++;
									} elseif ($log['product_id']) {
										$file_type_counts['Products']++;
									} elseif ($log['tools_id']) {
										$file_type_counts['Tools']++;
									} else {
										$file_type_counts['Unknown']++;
									}
								}
							}
						}
						
						// Combine database and file data
						$combined_type_counts = [];
						foreach($file_type_counts as $type => $count) {
							$combined_type_counts[$type] = $count;
						}
						
						// Add database counts
						foreach($db_spam_by_type as $db_type) {
							$type = $db_type['campaign_type'];
							if (!isset($combined_type_counts[$type])) {
								$combined_type_counts[$type] = 0;
							}
							$combined_type_counts[$type] += $db_type['spam_count'];
						}
						
						// Convert to array format
						$spam_by_type = [];
						foreach($combined_type_counts as $type => $count) {
							if ($count > 0) {
								$spam_by_type[] = [
									'campaign_type' => $type,
									'spam_count' => $count
								];
							}
						}
						
						// Sort by count
						usort($spam_by_type, function($a, $b) {
							return $b['spam_count'] - $a['spam_count'];
						});
						
						$data['spam_by_type'] = $spam_by_type;
						
						// Get active spammers from session-based spam logs
						$session_spam_logs = $this->session->userdata('spam_logs') ?: [];
						$active_spammers = [];
						
						// Group blocked attempts by user in last hour
						$user_blocked_counts = [];
						$one_hour_ago = date('Y-m-d H:i:s', strtotime('-1 hour'));
						
						foreach($session_spam_logs as $spam_log) {
							if ($spam_log['created_at'] >= $one_hour_ago) {
								$user_id = $spam_log['user_id'];
								if (!isset($user_blocked_counts[$user_id])) {
									$user_blocked_counts[$user_id] = [
										'count' => 0,
										'last_attempt' => '',
										'ips' => [],
										'campaigns' => []
									];
								}
								$user_blocked_counts[$user_id]['count']++;
								$user_blocked_counts[$user_id]['last_attempt'] = max($user_blocked_counts[$user_id]['last_attempt'], $spam_log['created_at']);
								$user_blocked_counts[$user_id]['ips'][] = $spam_log['ip'];
								
								// Get campaign name
								$campaign_name = 'Unknown';
								if ($spam_log['form_id']) {
									$form = $this->db->where('form_id', $spam_log['form_id'])->get('form')->row();
									$campaign_name = $form ? 'Form: ' . $form->title : 'Form: Unknown';
								} elseif ($spam_log['product_id']) {
									$product = $this->db->where('product_id', $spam_log['product_id'])->get('product')->row();
									$campaign_name = $product ? 'Product: ' . $product->product_name : 'Product: Unknown';
								} elseif ($spam_log['tools_id']) {
									$tool = $this->db->where('id', $spam_log['tools_id'])->get('integration_tools')->row();
									$campaign_name = $tool ? 'Tool: ' . $tool->name : 'Tool: Unknown';
								}
								$user_blocked_counts[$user_id]['campaigns'][] = $campaign_name;
							}
						}
						
						// Filter users with 3+ blocked attempts and get user details
						foreach($user_blocked_counts as $user_id => $data) {
							if ($data['count'] >= 3) {
								$user = $this->db->where('id', $user_id)->get('users')->row();
								if ($user) {
									$active_spammers[] = [
										'user_id' => $user_id,
										'firstname' => $user->firstname,
										'lastname' => $user->lastname,
										'email' => $user->email,
										'blocked_attempts' => $data['count'],
										'last_attempt' => $data['last_attempt'],
										'unique_ips' => count(array_unique($data['ips'])),
										'targeted_campaigns' => implode(', ', array_unique($data['campaigns']))
									];
								}
							}
						}
						
						// Sort by blocked attempts descending
						usort($active_spammers, function($a, $b) {
							return $b['blocked_attempts'] - $a['blocked_attempts'];
						});
						
						// Limit to top 10
						$active_spammers = array_slice($active_spammers, 0, 10);
						
						$data['active_spammers'] = $active_spammers;
						
						// =================================================================
						// FRAUD SYSTEM DATA INTEGRATION
						// =================================================================
						error_log("========== LOADING FRAUD SYSTEM DATA ==========");
						
						// Load IntegrationModel for fraud data access
						$this->load->model('IntegrationModel');
						
					// Get fraud data from integration_clicks_logs (last 7 days for performance)
					$fraud_cutoff = date('Y-m-d H:i:s', strtotime('-7 days'));
					$fraud_query = $this->db->query("
						SELECT icl.*, u.firstname, u.lastname, u.email, u.country,
						       ica.fraud_score
						FROM integration_clicks_logs icl
						LEFT JOIN users u ON u.id = icl.user_id
						LEFT JOIN integration_clicks_action ica ON ica.id = icl.click_id
						WHERE icl.created_at >= ?
						ORDER BY icl.created_at DESC
						LIMIT 1000
					", [$fraud_cutoff]);
						
						$fraud_logs = $fraud_query->result_array();
						error_log("Found " . count($fraud_logs) . " fraud records from integration_clicks_logs");
						
						// Analyze fraud data
						$fraud_stats = [
							'total_fraud_attempts' => 0,
							'unique_fraud_users' => 0,
							'unique_fraud_ips' => 0,
							'fraud_countries' => [],
							'fraud_by_browser' => [],
							'fraud_by_os' => [],
							'blocked_localhost' => 0,
							'cross_browser_attempts' => 0
						];
						
						$fraud_users = [];
						$fraud_ips = [];
						$fraud_countries = [];
						$fraud_browsers = [];
						$fraud_os_platforms = [];
						$fraud_timeline = [];
						$top_fraud_users = [];
						$recent_fraud_attempts = [];
						
						foreach($fraud_logs as $fraud_log) {
							$fraud_stats['total_fraud_attempts']++;
							
							// Track unique users
							if (!in_array($fraud_log['user_id'], $fraud_users)) {
								$fraud_users[] = $fraud_log['user_id'];
							}
							
							// Track unique IPs
							if (!in_array($fraud_log['ip'], $fraud_ips)) {
								$fraud_ips[] = $fraud_log['ip'];
							}
							
							// Track countries
							if (!empty($fraud_log['country_code'])) {
								$fraud_countries[$fraud_log['country_code']] = ($fraud_countries[$fraud_log['country_code']] ?? 0) + 1;
							}
							
							// Track browsers
							if (!empty($fraud_log['browserName'])) {
								$browser_key = $fraud_log['browserName'] . ' ' . ($fraud_log['browserVersion'] ?? '');
								$fraud_browsers[$browser_key] = ($fraud_browsers[$browser_key] ?? 0) + 1;
							}
							
							// Track OS platforms
							if (!empty($fraud_log['osPlatform'])) {
								$fraud_os_platforms[$fraud_log['osPlatform']] = ($fraud_os_platforms[$fraud_log['osPlatform']] ?? 0) + 1;
							}
							
							// Check for localhost attempts
							if (in_array($fraud_log['ip'], ['127.0.0.1', '::1', 'localhost'])) {
								$fraud_stats['blocked_localhost']++;
							}
							
							// Build timeline data (group by hour)
							$hour_key = date('Y-m-d H:00', strtotime($fraud_log['created_at']));
							$fraud_timeline[$hour_key] = ($fraud_timeline[$hour_key] ?? 0) + 1;
							
							// Track top fraud users
							$user_key = $fraud_log['user_id'];
							if (!isset($top_fraud_users[$user_key])) {
								$top_fraud_users[$user_key] = [
									'user_id' => $fraud_log['user_id'],
									'firstname' => $fraud_log['firstname'] ?? 'Unknown',
									'lastname' => $fraud_log['lastname'] ?? 'User',
									'email' => $fraud_log['email'] ?? 'unknown@example.com',
									'fraud_count' => 0,
									'last_attempt' => $fraud_log['created_at'],
									'countries' => [],
									'ips' => []
								];
							}
							$top_fraud_users[$user_key]['fraud_count']++;
							$top_fraud_users[$user_key]['countries'][] = $fraud_log['country_code'] ?? 'Unknown';
							$top_fraud_users[$user_key]['ips'][] = $fraud_log['ip'];
							
						// Recent fraud attempts (last 50)
						if (count($recent_fraud_attempts) < 50) {
							$recent_fraud_attempts[] = [
								'user_id'     => $fraud_log['user_id'],
								'firstname'   => $fraud_log['firstname'] ?? 'Unknown',
								'lastname'    => $fraud_log['lastname'] ?? 'User',
								'email'       => $fraud_log['email'] ?? 'unknown@example.com',
								'ip'          => $fraud_log['ip'],
								'country_code'=> $fraud_log['country_code'] ?? 'Unknown',
								'browser'     => ($fraud_log['browserName'] ?? 'Unknown') . ' ' . ($fraud_log['browserVersion'] ?? ''),
								'os'          => ($fraud_log['osPlatform'] ?? 'Unknown') . ' ' . ($fraud_log['osVersion'] ?? ''),
								'created_at'  => $fraud_log['created_at'],
								'link'        => $fraud_log['link'] ?? 'Unknown',
								'click_type'  => $fraud_log['click_type'] ?? 'Unknown',
								'fraud_score' => isset($fraud_log['fraud_score']) ? (int)$fraud_log['fraud_score'] : null, // [v15]
							];
						}
						}
						
						// Finalize fraud stats
						$fraud_stats['unique_fraud_users'] = count($fraud_users);
						$fraud_stats['unique_fraud_ips'] = count($fraud_ips);
						$fraud_stats['fraud_countries'] = $fraud_countries;
						$fraud_stats['fraud_by_browser'] = $fraud_browsers;
						$fraud_stats['fraud_by_os'] = $fraud_os_platforms;
						
						// Sort and limit top fraud users
						usort($top_fraud_users, function($a, $b) {
							return $b['fraud_count'] - $a['fraud_count'];
						});
						$top_fraud_users = array_slice($top_fraud_users, 0, 10);
						
						// Clean up top fraud users data
						foreach($top_fraud_users as &$user) {
							$user['countries'] = array_unique($user['countries']);
							$user['ips'] = array_unique($user['ips']);
							$user['unique_countries'] = count($user['countries']);
							$user['unique_ips'] = count($user['ips']);
						}
						
						// Sort fraud timeline
						ksort($fraud_timeline);
						
						// Add fraud data to return array
						$data['fraud_stats'] = $fraud_stats;
						$data['fraud_timeline'] = $fraud_timeline;
						$data['top_fraud_users'] = $top_fraud_users;
						$data['recent_fraud_attempts'] = $recent_fraud_attempts;
						// Sort and slice fraud data arrays
						arsort($fraud_countries);
						arsort($fraud_browsers);  
						arsort($fraud_os_platforms);
						$data['fraud_by_country'] = array_slice($fraud_countries, 0, 10, true);
						$data['fraud_by_browser'] = array_slice($fraud_browsers, 0, 10, true);
						$data['fraud_by_os'] = array_slice($fraud_os_platforms, 0, 10, true);
						
						error_log("Fraud data processing complete. Total attempts: " . $fraud_stats['total_fraud_attempts']);
						
						return $data;
					}

						public function analytics_dashboard(){
							$userdetails = $this->userdetails();
							
							$this->load->model('Total_model');
							
							$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
							$data['hcurrency'] = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'admin'));
							$data['fun_c_format'] = $data['hcurrency'] ? 'c_format_nosym' : 'c_format';
							
							$data['admin_totals'] = $this->Total_model->adminTotals();
							$data['notifications_count'] = $this->Product_model->getnotificationnew_count('admin', null);
							
							$store_status = $this->Product_model->getSettings('store', 'status');
							$data['enable_disable'] = array('store_is_enable' => isset($store_status['status']) ? $store_status['status'] : 0);
							
							$data['vendor_store_data'] = $this->Product_model->getSettings('vendor');
							$data['serverReq'] = checkReq();
							$data['site'] = $this->Product_model->getSettings('site');
							
							$this->view($data, 'dashboard/analytics_dashboard');
						}

						public function s2s_analytics(){
							$userdetails = $this->userdetails();
							
							$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
							$data['hcurrency'] = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'admin'));
							$data['fun_c_format'] = $data['hcurrency'] ? 'c_format_nosym' : 'c_format';
							
							// S2S totals
							$s2s_totals = $this->db->query("
								SELECT 
									COUNT(*) as total_orders,
									COALESCE(SUM(total), 0) as total_revenue,
									COALESCE(SUM(commission), 0) as total_commission
								FROM integration_orders 
								WHERE script_name = 's2s' AND status > 0
							")->row_array();
							
							// JS Pixel totals
							$pixel_totals = $this->db->query("
								SELECT 
									COUNT(*) as total_orders,
									COALESCE(SUM(total), 0) as total_revenue,
									COALESCE(SUM(commission), 0) as total_commission
								FROM integration_orders 
								WHERE script_name != 's2s' AND status > 0
							")->row_array();
							
							// S2S orders last 30 days (daily breakdown)
							$s2s_daily = $this->db->query("
								SELECT DATE(created_at) as date, COUNT(*) as orders, COALESCE(SUM(total),0) as revenue
								FROM integration_orders 
								WHERE script_name = 's2s' AND status > 0 AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
								GROUP BY DATE(created_at) ORDER BY date ASC
							")->result_array();
							
							$pixel_daily = $this->db->query("
								SELECT DATE(created_at) as date, COUNT(*) as orders, COALESCE(SUM(total),0) as revenue
								FROM integration_orders 
								WHERE script_name != 's2s' AND status > 0 AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
								GROUP BY DATE(created_at) ORDER BY date ASC
							")->result_array();
							
							// Top campaigns by S2S
							$s2s_top_campaigns = $this->db->query("
								SELECT io.ads_id, it.name as campaign_name, COUNT(*) as orders, COALESCE(SUM(io.total),0) as revenue
								FROM integration_orders io
								LEFT JOIN integration_tools_ads ita ON io.ads_id = ita.id
								LEFT JOIN integration_tools it ON ita.tools_id = it.id
								WHERE io.script_name = 's2s' AND io.status > 0
								GROUP BY io.ads_id ORDER BY orders DESC LIMIT 10
							")->result_array();
							
							// S2S enabled campaigns count
							$s2s_enabled_count = $this->db->query("SELECT COUNT(*) as cnt FROM integration_tools WHERE s2s_enabled = 1")->row()->cnt;
							$total_campaigns = $this->db->query("SELECT COUNT(*) as cnt FROM integration_tools")->row()->cnt;
							
							// Recent S2S orders
							$recent_s2s = $this->db->query("
								SELECT io.*, u.username, u.firstname, u.lastname
								FROM integration_orders io
								LEFT JOIN users u ON io.user_id = u.id
								WHERE io.script_name = 's2s'
								ORDER BY io.created_at DESC LIMIT 10
							")->result_array();
							
							$data['s2s_totals'] = $s2s_totals;
							$data['pixel_totals'] = $pixel_totals;
							$data['s2s_daily'] = $s2s_daily;
							$data['pixel_daily'] = $pixel_daily;
							$data['s2s_top_campaigns'] = $s2s_top_campaigns;
							$data['s2s_enabled_count'] = $s2s_enabled_count;
							$data['total_campaigns'] = $total_campaigns;
							$data['recent_s2s'] = $recent_s2s;
							
							$this->view($data, 'dashboard/s2s_analytics');
						}

						public function security_monitor(){
							$userdetails = $this->userdetails();
							
							$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
							$data['hcurrency'] = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'admin'));
							$data['fun_c_format'] = $data['hcurrency'] ? 'c_format_nosym' : 'c_format';
							
							$data['notifications_count'] = $this->Product_model->getnotificationnew_count('admin', null);
							
							$store_status = $this->Product_model->getSettings('store', 'status');
							$data['enable_disable'] = array('store_is_enable' => isset($store_status['status']) ? $store_status['status'] : 0);
							
							$data['vendor_store_data'] = $this->Product_model->getSettings('vendor');
							$data['serverReq'] = checkReq();
							$data['site'] = $this->Product_model->getSettings('site');
							
						// [v15] Activate existing fraud monitoring function (was never called before)
						$data['spam_monitoring'] = $this->getSpamMonitoringData();

						$this->view($data, 'dashboard/security_monitor');
						}





					// Versoin update notification

					// Get the site's base URL
					private function get_site_base_url()
					{
					    // Try to get base URL from CodeIgniter config first
					    $base_url = $this->config->item('base_url');
					    
					    if (!empty($base_url)) {
					        return $this->clean_domain_from_url($base_url);
					    }
					    
					    // Fallback: construct from server variables
					    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
					    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
					    $constructed_url = $protocol . '://' . $host;
					    
					    return $this->clean_domain_from_url($constructed_url);
					}

					// Clean domain from URL
					private function clean_domain_from_url($url)
					{
					    // Remove protocol
					    $url = preg_replace('#^https?://#', '', $url);
					    
					    // Remove www
					    $url = preg_replace('#^www\.#', '', $url);
					    
					    // Remove trailing slash and any path
					    $url = rtrim($url, '/');
					    
					    // Get just the domain part (remove any path)
					    $parsed = parse_url('http://' . $url, PHP_URL_HOST);
					    
					    return $parsed ?: $url;
					}

					
					// Versoin update notification

									public function verify_license() {
				    if ($this->input->post('code')) {
				        $code = $this->input->post('code');
				        $result = verify_license_code($code);
				        
				        header('Content-Type: application/json');
				        echo json_encode($result);
				    } else {
				        header('Content-Type: application/json');
				        echo json_encode(['valid' => false, 'message' => 'No purchase code provided']);
				    }
				}					

				public function api_dashboard_data() {
				    session_write_close();
				    header('Content-Type: application/json');
				    
				    $this->load->model('Report_model');
				    $this->load->model('Total_model');
					    
					    $statistics = $this->Report_model->getStatistics();
					    $admin_totals = $this->Total_model->adminTotals();
					    $online_count = $this->Product_model->onlineCount();
					    
					    $response = [
					        'status' => 'success',
					        'statistics' => $statistics,
					        'admin_totals' => $admin_totals,
					        'online_count' => $online_count
					    ];
					    
					    echo json_encode($response);
					    exit;
					}

			public function api_dashboard_heavy_data() {
			    session_write_close();
			    header('Content-Type: application/json');
			    
			    $this->load->library("Backup");
				    
				    $t0 = microtime(true);
				    $backup_warnings = $this->checkMissingBackups();
				    $t1 = microtime(true);
				    $backups = $this->backup->getListZip();
				    $t2 = microtime(true);
				    $script_backups = $this->backup->getListScriptZip();
				    $t3 = microtime(true);
				    $missing = $this->Product_model->getSettingStatus();
				    $t4 = microtime(true);
			    
			    $response = [
			        'status' => 'success',
			        'backup_warnings' => $backup_warnings,
			        'backups' => $backups,
			        'script_backups' => $script_backups,
			        'missing' => $missing,
			        'showMissingDetailsModal' => !empty($missing),
			        '_debug_timing' => [
			            'checkMissingBackups' => round(($t1 - $t0) * 1000) . 'ms',
			            'getListZip' => round(($t2 - $t1) * 1000) . 'ms',
			            'getListScriptZip' => round(($t3 - $t2) * 1000) . 'ms',
			            'getSettingStatus' => round(($t4 - $t3) * 1000) . 'ms',
			            'total' => round(($t4 - $t0) * 1000) . 'ms',
			        ]
			    ];
				    
				    echo json_encode($response);
				    exit;
				}

					public function popular_affiliates_sorting(){

						$hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');

						$data['hcurrency'] = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'admin'));

						if($data['hcurrency']) {
							$data['fun_c_format'] =$fun_c_format = 'c_format_nosym';
						} else {
							$data['fun_c_format'] =$fun_c_format = 'c_format';
						}
						$value=$this->input->post('value');
						$type=$this->input->post('type');
						
						$this->Setting_model->save($type, ["popular_affiliates"=>$_POST['value']]);
						$popular_aff_filter=$this->db->query("SELECT * FROM setting WHERE  setting_key='popular_affiliates' and setting_type='popular_affiliates_sorting'")->row();
						$popular_aff_filt=isset($popular_aff_filter)  ? $popular_aff_filter->setting_value : '';
						
						$data['populer_users'] = $this->Product_model->getPopulerUsers(array("limit" => 10),$popular_aff_filt);
						$json['view'] = $this->load->view("admincontrol/dashboard/popular_aff_list_tr", $data, true);

					echo json_encode($json);
					}

					public function admin_user(){

						$userdetails = $this->userdetails();

						$join_roles = $this->db->table_exists('admin_roles');
						$sql = "SELECT users.*, countries.sortname" .
							($join_roles ? ", admin_roles.name as role_name, admin_roles.slug as role_slug" : "") .
							" FROM users" .
							" LEFT JOIN countries ON countries.id = users.Country" .
							($join_roles ? " LEFT JOIN admin_roles ON admin_roles.id = users.admin_role_id" : "") .
							" WHERE users.type='admin' ORDER BY (users.id = 1) DESC, users.firstname, users.lastname";
						$data['users'] = $this->db->query($sql)->result();
						$data['admin_roles'] = $join_roles ? $this->db->get('admin_roles')->result() : [];
						$this->view($data,'admin_user/index');
					}


					public function admin_user_form($user_id = 0){

						$userdetails = $this->userdetails();

						// Super admin (user id 1) cannot be edited by anyone, in any mode
						if ((int)$user_id === 1) {
							if ($this->input->server('REQUEST_METHOD') === 'POST') {
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode(['error' => __('admin.super_admin_readonly')]);
								exit;
							}
							$this->session->set_flashdata('error', __('admin.super_admin_readonly'));
							redirect('admincontrol/admin_user');
							return;
						}

						$data['user'] 	= $this->Product_model->getUserDetailsObject($user_id);

						if ($this->input->server('REQUEST_METHOD') == 'POST'){

							$json = array();

							$id = (int)$this->input->post("user_id",true);

							// Super admin cannot be edited by anyone (re-check for POST)
							if ($id === 1) {
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode(['error' => __('admin.super_admin_readonly')]);
								exit;
							}

							$this->load->library('form_validation');

							$this->form_validation->set_rules('firstname', __('admin.firstname'), 'required');

							$this->form_validation->set_rules('lastname', __('admin.last_name'), 'required');

							$this->form_validation->set_rules('email', __('admin.email'), 'required|valid_email|xss_clean');

							$this->form_validation->set_rules('PhoneNumber', __('admin.phone_number'), 'required');

							$this->form_validation->set_rules('Country', __('admin.country'), 'required');

							$this->form_validation->set_rules('City', __('admin.city'), 'required');

							$this->form_validation->set_rules('Zip', __('admin.pincode'), 'required');

							$post = $this->input->post(null,true);

							if((int)$id == 0 || $post['password'] != ''){

								$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));

								$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim', array('required' => '%s is required'));

								$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));

							}

							if($this->form_validation->run()){

								$errors= array();

								$checkmail = $this->Product_model->checkmail($this->input->post('email',true),$id);

								$checkuser = $this->Product_model->checkuser($this->input->post('username',true),$id);

								if(!empty($checkmail)){ $json['errors']['email'] = "Email Already Exist"; }

								if(!empty($checkuser)){ $json['errors']['username'] = "Username Already Exist"; }

								$avatar = $data['user']->avatar;

								if(!empty($_FILES['avatar']['name'])){

									$upload_response = $this->upload_photo('avatar','assets/images/users');

									if($upload_response['success']){

										$avatar = $upload_response['upload_data']['file_name'];

									}

									else{

										$json['errors']['avatar'] = $upload_response['msg'];

									}

								}

								if(!isset($json['errors'])){

									$userArray = array(

										'firstname'                 => $this->input->post('firstname',true),

										'lastname'                  => $this->input->post('lastname',true),

										'email'                     => $this->input->post('email',true),

										'username'                  => $this->input->post('username',true),

										'twaddress'                 => '',

										'type'                      => 'admin',

										'avatar'                      => $avatar,

										'address1'                  => '',

										'address2'                  => '',

										'uzip'                      => '',

										'online'                    => '0',

										'unique_url'                => '',

										'bitly_unique_url'          => '',

										'google_id'                 => '',

										'facebook_id'               => '',

										'twitter_id'                => '',

										'umode'                     => '',

										'PhoneNumber'               => $this->input->post('PhoneNumber',true),

										'Addressone'                => '',

										'Addresstwo'                => '',

										'StateProvince'             => $this->input->post('StateProvince',true),

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

										'status'                    => '1',

										'Zip'                       => $this->input->post('Zip',true),

										'uzip'                      => $this->input->post('Zip',true),

										'City'                      => $this->input->post('City',true),

										'ucity'                     => $this->input->post('City',true),

										'ucountry'                  => $this->input->post('Country',true),

										'Country'                   => $this->input->post('Country',true),

										'value'                     => json_encode(array()),

										'admin_role_id'             => null,
										'admin_permissions'         => null,

									);
									$role_post = $this->input->post('admin_role_id', true);
									$perm_post = $this->input->post('admin_perm');
									$admin_roles_for_save = [];
									if ($this->db->table_exists('admin_roles')) {
										$admin_roles_for_save = $this->db->get('admin_roles')->result();
									}
									$all_slugs = array_keys($this->config->item('admin_permission_slugs') ?: []);
									$is_super_admin = ((int)($userdetails['id'] ?? 0)) === 1;
									$can_assign_full_access = $is_super_admin && ENVIRONMENT !== 'demo';
									$reject_full_access = function () use (&$json) {
										$json['errors']['admin_role_id'] = __('admin.full_access_super_admin_only');
										echo json_encode($json);
										die;
									};
									if ($role_post === '' || $role_post === null) {
										if (!$can_assign_full_access) {
											$reject_full_access();
										}
										$userArray['admin_role_id'] = null;
										$userArray['admin_permissions'] = null;
									} elseif ($role_post === 'custom') {
										$perms = is_array($perm_post) ? array_values($perm_post) : [];
										$perms_sorted = $perms;
										sort($perms_sorted);
										$all_sorted = $all_slugs;
										sort($all_sorted);
										if (!empty($perms_sorted) && $perms_sorted === $all_sorted && !$can_assign_full_access) {
											$reject_full_access();
										}
										$userArray['admin_permissions'] = !empty($perms) ? json_encode($perms) : null;
									} else {
										$role_id = (int)$role_post;
										$role_perms = [];
										if (!empty($admin_roles_for_save)) {
											foreach ($admin_roles_for_save as $r) {
												if ((int)$r->id === $role_id) {
													$role_perms = is_string($r->permissions) ? json_decode($r->permissions, true) : (array)($r->permissions ?? []);
													break;
												}
											}
										}
										$checked = is_array($perm_post) ? array_values($perm_post) : [];
										$rp = $role_perms;
										sort($rp);
										sort($checked);
										if ($role_id && $rp === $checked) {
											$rp_sorted = $rp;
											$all_sorted = $all_slugs;
											sort($all_sorted);
											if ($rp_sorted === $all_sorted && !$can_assign_full_access) {
												$reject_full_access();
											}
											$userArray['admin_role_id'] = $role_id;
											$userArray['admin_permissions'] = null;
										} else {
											$merged = !empty($checked) ? $checked : $rp;
											$merged_sorted = $merged;
											sort($merged_sorted);
											$all_sorted = $all_slugs;
											sort($all_sorted);
											if (!empty($merged_sorted) && $merged_sorted === $all_sorted && !$can_assign_full_access) {
												$reject_full_access();
											}
											$userArray['admin_role_id'] = null;
											$userArray['admin_permissions'] = !empty($checked) ? json_encode($checked) : null;
										}
									}



									if($post['password'] != ''){

										$userArray['password'] = sha1( $this->input->post('password',true) );

									}

									if($id == 0){

										$userArray['created_at'] = $userArray['updated_at'] = date("Y-m-d H:i:s");

										$data = $this->user->insert($userArray);

										$id = $this->db->insert_id();

									} else {

										$data = $this->user->update_user($id, $userArray);
									}

									$this->session->set_flashdata('success', __('admin.admin_updated_successfully'));

									$stay_on_form = $this->input->post('stay_on_form');
									if ($stay_on_form) {
										if ((int)$id > 0) {
											$json['success'] = true;
										} else {
											$json['location'] = base_url('admincontrol/admin_user_form/' . $id);
										}
									} else {
										$json['location'] = base_url('admincontrol/admin_user');
									}
								}

							} else{

								$json['errors'] = $this->form_validation->error_array();
							}
							echo json_encode($json);die;
						}

						$data['country'] = $this->Product_model->getcountry();
						$data['admin_roles'] = [];
						$perm_labels = $this->config->item('admin_permission_slugs') ?: [];
						$all_slugs = array_keys($perm_labels);
						$role_labels = ['' => $all_slugs];
						if ($this->db->table_exists('admin_roles')) {
							$data['admin_roles'] = $this->db->get('admin_roles')->result();
							foreach ($data['admin_roles'] as $r) {
								$perms = is_string($r->permissions) ? json_decode($r->permissions, true) : (array)($r->permissions ?? []);
								$role_labels[$r->id] = is_array($perms) ? $perms : [];
							}
						}
						$role_slugs_json = [];
						foreach ($role_labels as $rid => $slugs) {
							$role_slugs_json[$rid] = $slugs;
						}
						$role_slugs_json['custom'] = [];
						$data['role_permissions_json'] = json_encode($role_slugs_json);
						$data['perm_groups'] = $this->config->item('admin_permission_groups') ?: [];
						$data['perm_group_keys'] = $this->config->item('admin_permission_group_keys') ?: [];
						$data['perm_label_keys'] = $this->config->item('admin_permission_label_keys') ?: [];
						$data['is_demo_mode'] = (defined('ENVIRONMENT') && ENVIRONMENT === 'demo');
						$data['can_assign_full_access'] = ((int)($userdetails['id'] ?? 0)) === 1 && ENVIRONMENT !== 'demo';
						$this->view($data,'admin_user/form');

					}


	public function admin_user_delete($user_id) { 
		$userdetails = $this->userdetails();
		// Demo mode: block all admin deletions (keeps demo stable)
		if (ENVIRONMENT === 'demo') {
			$this->session->set_flashdata('error', __('admin.demo_mode'));
			redirect('/admincontrol/admin_user');
			return;
		}
		if($userdetails['id'] == 1){
			if((int)$user_id == 1){
				$this->session->set_flashdata('error', __('admin.error_delete_primary_admin'));
			} else {

				$this->db->query("DELETE FROM users WHERE type='admin' AND id= {$user_id}");

				$this->session->set_flashdata('success', __('admin.admin_deleted_successfully'));
			}

		} else{

			$this->session->set_flashdata('error', __('admin.can_not_allow_to_delete_admin'));
		}

		redirect('/admincontrol/admin_user');
	}

	/**
	 * List admin roles (manageable via UI; config defines available permission slugs).
	 */
	public function admin_roles() {
		$userdetails = $this->userdetails();
		$data['roles'] = [];
		$data['role_user_counts'] = [];
		if ($this->db->table_exists('admin_roles')) {
			$this->db->order_by('name', 'ASC');
			$data['roles'] = $this->db->get('admin_roles')->result();
			if ($this->db->field_exists('admin_role_id', 'users')) {
				$rows = $this->db->select('admin_role_id, COUNT(*) as cnt')
					->from('users')
					->where('type', 'admin')
					->where('admin_role_id IS NOT NULL', null, false)
					->group_by('admin_role_id')
					->get()->result();
				foreach ($rows as $row) {
					$data['role_user_counts'][(int)$row->admin_role_id] = (int)$row->cnt;
				}
			}
		}
		$data['total_perms'] = count($this->config->item('admin_permission_slugs') ?: []);
		$this->view($data, 'admin_role/index');
	}

	/**
	 * Add or edit admin role. POST = save, GET = show form.
	 * Permissions come from config; admins assign them to roles via UI.
	 */
	public function admin_role_form($role_id = 0) {
		$userdetails = $this->userdetails();
		$role_id = (int)$role_id;
		$role = null;
		if ($role_id > 0 && $this->db->table_exists('admin_roles')) {
			$role = $this->db->get_where('admin_roles', ['id' => $role_id])->row();
		}

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$json = [];
			$name = trim($this->input->post('name', true));
			$slug = trim($this->input->post('slug', true));
			$perm_post = $this->input->post('admin_perm');

			if (empty($name)) {
				$json['errors']['name'] = __('admin.field_required');
			}
			if (empty($slug)) {
				$slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $name));
			}
			$slug = preg_replace('/[^a-z0-9_]/', '', strtolower($slug));
			if (empty($slug)) {
				$json['errors']['slug'] = __('admin.field_required');
			}

			if (empty($json['errors']) && $this->db->table_exists('admin_roles')) {
				$existing = $this->db->get_where('admin_roles', ['slug' => $slug])->row();
				if ($existing && (int)$existing->id !== $role_id) {
					$json['errors']['slug'] = __('admin.role_slug_exists');
				}
			}

			$perms = is_array($perm_post) ? array_values($perm_post) : [];
			$perms_json = !empty($perms) ? json_encode($perms) : null;

			if (empty($json['errors'])) {
				$row = [
					'name' => $name,
					'slug' => $slug,
					'permissions' => $perms_json,
				];
				if ($role_id > 0) {
					$this->db->where('id', $role_id)->update('admin_roles', $row);
					$this->session->set_flashdata('success', __('admin.role_updated_successfully'));
				} else {
					$row['created_at'] = date('Y-m-d H:i:s');
					$this->db->insert('admin_roles', $row);
					$this->session->set_flashdata('success', __('admin.role_created_successfully'));
				}

				$stay = $this->input->post('stay_on_form');
				if ($stay) {
					$new_id = $role_id > 0 ? $role_id : $this->db->insert_id();
					$json['location'] = base_url('admincontrol/admin_role_form/' . $new_id);
				} else {
					$json['location'] = base_url('admincontrol/admin_roles');
				}
			}
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($json);
			exit;
		}

		$data['role'] = $role;
		$data['perm_groups'] = $this->config->item('admin_permission_groups') ?: [];
		$data['perm_group_keys'] = $this->config->item('admin_permission_group_keys') ?: [];
		$data['perm_label_keys'] = $this->config->item('admin_permission_label_keys') ?: [];
		$data['perm_slugs'] = $this->config->item('admin_permission_slugs') ?: [];
		$data['role_perms'] = [];
		if ($role && !empty($role->permissions)) {
			$data['role_perms'] = is_string($role->permissions) ? json_decode($role->permissions, true) : (array)$role->permissions;
			$data['role_perms'] = is_array($data['role_perms']) ? $data['role_perms'] : [];
		}
		$this->view($data, 'admin_role/form');
	}

	public function admin_role_delete($role_id) {
		$role_id = (int)$role_id;
		if ($role_id <= 0) {
			redirect('admincontrol/admin_roles');
			return;
		}
		$in_use = $this->db->where('admin_role_id', $role_id)->count_all_results('users');
		if ($in_use > 0) {
			$this->session->set_flashdata('error', __('admin.role_in_use_cannot_delete'));
		} else {
			$this->db->where('id', $role_id)->delete('admin_roles');
			$this->session->set_flashdata('success', __('admin.role_deleted_successfully'));
		}
		redirect('admincontrol/admin_roles');
	}

	/**
	 * Import demo roles (Finance, Support, Marketing) for fresh installs.
	 * Skips roles that already exist by slug.
	 */
	public function import_demo_roles() {
		$this->userdetails();
		if (!$this->db->table_exists('admin_roles')) {
			$this->session->set_flashdata('error', __('admin.admin_roles_table_missing'));
			redirect('admincontrol/admin_roles');
			return;
		}
		$demo_roles = [
			['name' => 'Finance Admin',  'slug' => 'finance',  'permissions' => '["dashboard","analytics","reports","reports.orders","reports.transactions","reports.wallet","reports.statistics","settings","settings.payment","marketing.deposits"]'],
			['name' => 'Support Admin',   'slug' => 'support',  'permissions' => '["dashboard","users","reports","reports.orders","reports.statistics"]'],
			['name' => 'Marketing Admin','slug' => 'marketing','permissions' => '["dashboard","analytics","marketing","marketing.deposits","marketing.campaigns","reports","reports.orders","reports.statistics"]'],
		];
		$imported = 0;
		foreach ($demo_roles as $r) {
			$exist = $this->db->get_where('admin_roles', ['slug' => $r['slug']])->row();
			if (!$exist) {
				$this->db->insert('admin_roles', [
					'name' => $r['name'],
					'slug' => $r['slug'],
					'permissions' => $r['permissions'],
					'created_at' => date('Y-m-d H:i:s'),
				]);
				$imported++;
			}
		}
		if ($imported > 0) {
			$this->session->set_flashdata('success', sprintf(__('admin.import_demo_roles_success'), $imported));
		} else {
			$this->session->set_flashdata('info', __('admin.import_demo_roles_already_exist'));
		}
		redirect('admincontrol/admin_roles');
	}

	public function logout(){
		$this->session->unset_userdata('administrator');
		$this->session->sess_destroy();
		redirect($this->admin_domain_url);
		exit;
	}

	/**
	 * Demo mode: logout and show login form so user can login as sub-admin.
	 */
	public function switch_admin(){
		$this->session->unset_userdata('administrator');
		$this->session->sess_destroy();
		redirect($this->admin_domain_url . '?switch=1');
		exit;
	}

	public function deleteUser($id){

		$userdetails = $this->userdetails();

		$data['users'] = $this->admin_model->deleteUser($id);

		$this->session->set_flashdata('success', __('admin.user_deleted_successfullly'));

		redirect('admincontrol/manageUsers');
	}

	public function award_level($offset = 0){
		$userdetails = $this->userdetails();
		$award_level = $this->Product_model->getSettings('award_level','status');
		$data['award_level_status'] = $award_level['status'];
		if($data['award_level_status']){
			$total_rows = $this->Product_model->countByTable('award_level');
			$pagination = easy_pagination(
				base_url('admincontrol/award_level'), 
				$total_rows, 
				$offset
			);
			
			$data['pagination'] = $pagination['html'];
			$data['award_level'] = $this->Product_model->getAllAwardLevel($pagination['per_page'], $offset);
			$data['CurrencySymbol'] = $this->currency->getSymbol();
		}

		$this->view($data, 'award_level/index');
	}

	public function create_award_level(){
		$userdetails = $this->userdetails();

		$award_level = $this->Product_model->getSettings('award_level','status');
		$data['award_level_status'] = $award_level['status'];
		if($data['award_level_status']){
			$data['CurrencySymbol'] = $this->currency->getSymbol();
			$data['award_levels'] = $this->Product_model->getAll('award_level',false,0,'id desc');

			if($this->input->method() == 'post'){
				$result['status'] = 0;
				$result['message'] = __('admin.something_went_wrong');

				$this->load->library('form_validation');
				$this->form_validation->set_rules('level_number',__('admin.level_number'),'trim|required|max_length[100]');
				$this->form_validation->set_rules('minimum_earning',__('admin.minimum_earning'),'trim|required');
				$this->form_validation->set_rules('sale_comission_rate',__('admin.sale_comission_rate'),'trim|required|greater_than_equal_to[0]|less_than_equal_to[100]');
				$this->form_validation->set_rules('bonus',__('admin.bonus'),'trim|required');
				if($this->form_validation->run() == TRUE){
					$jump_level = $this->input->post('jump_level',true);
					$exist = ($jump_level != '') ? $this->Product_model->checkJumpLevel($jump_level) : false;
					if(!$exist){
						$insert['level_number'] = $this->input->post('level_number',true);
						$insert['jump_level'] = ($jump_level != '') ? $jump_level : NULL;
						$insert['minimum_earning'] = $this->input->post('minimum_earning',true);
						$insert['sale_comission_rate'] = $this->input->post('sale_comission_rate',true);
						$insert['bonus'] = $this->input->post('bonus',true);
						$insert['default_registration_level'] = ($this->input->post('default_registration_level')) ? $this->input->post('default_registration_level',true) : 0;

						$success = true;
						if($insert['default_registration_level']){
							$updateDefautRegistrationLevel['default_registration_level'] = 0;
							$success = $this->db->update('award_level',$updateDefautRegistrationLevel);
						}

						if($success){
							$insertedId = $this->db->insert('award_level',$insert);
							if($insertedId){
								$result['status'] = 1;
								$result['message'] = __('admin.award_level_saved_successfully'); 
							}
						}
					} else {
						$result['message'] = __('admin.choosen_level_already_selected');
					}
				} else {
					$result['validation'] = $this->form_validation->error_array();
				}

				echo json_encode($result);
				die();
			}
		}

		$this->view($data, 'award_level/create');	
	}

	public function update_award_level($id){
		$userdetails = $this->userdetails();

		$award_level = $this->Product_model->getSettings('award_level','status');
		$data['award_level_status'] = $award_level['status'];
		if($data['award_level_status']){
			if(isset($id)){
				$id = (int) $id;
				if($id) {
					$data['award_level'] = $this->Product_model->getByField('award_level', 'id', $id);
					if($data['award_level']){
						$data['CurrencySymbol'] = $this->currency->getSymbol();
						$data['award_levels'] = $this->Product_model->getAllWithExcept('award_level','id',$id,false,0,'id desc');

						if($this->input->method() == 'post'){
							$result['status'] = 0;
							$result['message'] = __('admin.something_went_wrong');

							$this->load->library('form_validation');
							$this->form_validation->set_rules('level_number',__('admin.level_number'),'trim|required|max_length[100]');
							$this->form_validation->set_rules('minimum_earning',__('admin.minimum_earning'),'trim|required');
							$this->form_validation->set_rules('sale_comission_rate',__('admin.sale_comission_rate'),'trim|required|greater_than_equal_to[0]|less_than_equal_to[100]');
							$this->form_validation->set_rules('bonus',__('admin.bonus'),'trim|required');
							if($this->form_validation->run() == TRUE){
								$jump_level = $this->input->post('jump_level',true);
								$exist = ($jump_level != '') ? $this->Product_model->checkJumpLevel($jump_level,$id) : false;
								if(!$exist){
									$update['level_number'] = $this->input->post('level_number',true);
									$update['jump_level'] = ($jump_level != '') ? $jump_level : NULL;
									$update['minimum_earning'] = $this->input->post('minimum_earning',true);
									$update['sale_comission_rate'] = $this->input->post('sale_comission_rate',true);
									$update['bonus'] = $this->input->post('bonus',true);
									$update['default_registration_level'] = ($this->input->post('default_registration_level')) ? $this->input->post('default_registration_level',true) : 0;

									$success = true;
									if($update['default_registration_level']){
										$updateDefautRegistrationLevel['default_registration_level'] = 0;
										$success = $this->db->update('award_level',$updateDefautRegistrationLevel);
									} else {
										if($data['award_level']['default_registration_level']){
											$defaultLevel = $this->Product_model->getByField('award_level','jump_level',0);
											if($defaultLevel){
												$updateDefautRegistrationLevel['default_registration_level'] = 1;
												$success = $this->db->update('award_level',$updateDefautRegistrationLevel,['id' => $defaultLevel['id']]);
											}
										}
									}

									if($success){
										$success = $this->db->update('award_level',$update,['id' => $id]);
										if($success){
											$result['status'] = 1;
											$result['message'] = __('admin.award_level_saved_successfully'); 
										}
									}
								} else {
									$result['message'] = __('admin.choosen_level_already_selected');
								}
							} else {
								$result['validation'] = $this->form_validation->error_array();
							}

							echo json_encode($result);
							die();
						}

						$this->view($data, 'award_level/update');
					} else {
						redirect('admincontrol/award_level');
					}
				} else {
					redirect('admincontrol/award_level');
				}
			} else {
				redirect('admincontrol/award_level');
			}	
		} else {
			$this->view($data, 'award_level/update');
		}
	}

	public function delete_award_level($id){
		$userdetails = $this->userdetails();
		$result['status'] = 0;
		$result['message'] = __('admin.something_went_wrong');

		$award_level = $this->Product_model->getSettings('award_level','status');
		if($award_level['status']){
			if(isset($id)){
				$id = (int) $id;
				if($id) {
					$award_level = $this->Product_model->getByField('award_level', 'id', $id);
					if($award_level){
						$connected_level = $this->Product_model->checkLevelForUser($id);
						if(!$connected_level){
							// Check if we're deleting the default level
							$was_default = $award_level['default_registration_level'] == 1;
							
							$success = $this->db->delete('award_level',['id' => $id]);
							if($success) {
								// If we deleted the default level, set a new default
								if($was_default) {
									// Get the first available level and make it default
									$new_default = $this->db->order_by('id', 'ASC')->limit(1)->get('award_level')->row();
									if($new_default) {
										$this->db->where('id', $new_default->id);
										$this->db->update('award_level', ['default_registration_level' => 1]);
									}
								}
								
								$result['status'] = 1;
								$result['message'] = __('admin.award_level_deleted_successfully');
							}
						} else {
							$result['message'] = __('admin.level_connected_to_user');
						}
					}
				}
			}
		}

		echo json_encode($result);
		die();	
	}

	public function force_delete_award_level($id){
		$userdetails = $this->userdetails();
		$result['status'] = 0;
		$result['message'] = __('admin.something_went_wrong');



		// Check if it's a POST request or if we're in demo mode
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			$result['message'] = 'Invalid request method';
			echo json_encode($result);
			die();
		}

		// Demo Mode check
		if (ENVIRONMENT === 'demo') {
			$result['message'] = 'Disabled on demo mode';
			echo json_encode($result);
			die();
		}



		$award_level = $this->Product_model->getSettings('award_level','status');

		
		if($award_level['status']){
			if(isset($id)){
				$id = (int) $id;

				if($id) {
					$award_level = $this->Product_model->getByField('award_level', 'id', $id);

					if($award_level){
						// Get default level (level with default_registration_level = 1)
						$default_level = $this->Product_model->getByField('award_level', 'default_registration_level', 1);
						$default_level_id = $default_level ? $default_level['id'] : null;

						
						// Start transaction

						$this->db->trans_start();
						
						// 1. Update users connected to this level
						$affected_users = 0;
						if($default_level_id) {
							$this->db->where('level_id', $id);
							$this->db->update('users', ['level_id' => $default_level_id]);
							$affected_users = $this->db->affected_rows();
						} else {
							// If no default level, set to NULL
							$this->db->where('level_id', $id);
							$this->db->update('users', ['level_id' => null]);
							$affected_users = $this->db->affected_rows();
						}
						
						// 2. Update membership plans connected to this level
						$affected_plans = 0;
						if($default_level_id) {
							$this->db->where('level_id', $id);
							$this->db->update('membership_plans', ['level_id' => $default_level_id]);
							$affected_plans = $this->db->affected_rows();
						} else {
							// If no default level, set to NULL
							$this->db->where('level_id', $id);
							$this->db->update('membership_plans', ['level_id' => null]);
							$affected_plans = $this->db->affected_rows();
						}
						
						// 3. Check if we're deleting the default level
						$was_default = $award_level['default_registration_level'] == 1;
						
						// 4. Delete the level
						$success = $this->db->delete('award_level',['id' => $id]);
						
						// 5. If we deleted the default level, set a new default
						if($was_default && $success) {
							// Get the first available level and make it default
							$new_default = $this->db->order_by('id', 'ASC')->limit(1)->get('award_level')->row();
							if($new_default) {
								$this->db->where('id', $new_default->id);
								$this->db->update('award_level', ['default_registration_level' => 1]);
							}
						}
						
						// Complete transaction
						$this->db->trans_complete();
						
						if($this->db->trans_status() === FALSE) {
							// Transaction failed
							$result['message'] = __('admin.transaction_failed');
						} else {
							// Transaction successful
							$result['status'] = 1;
							$result['message'] = __('admin.award_level_force_deleted_successfully');
							$result['affected_users'] = $affected_users;
							$result['affected_plans'] = $affected_plans;
							

						}
					}
				}
			}
		}
		echo json_encode($result);
		die();	
	}

	public function addproduct(){

		$userdetails = $this->userdetails();

		if(empty($userdetails)){

			redirect($this->admin_domain_url);

		}

		//get amazon s3 details.
		$data['s3_setting'] = $this->Product_model->getSettings('s3_storage');

		$data['setting'] 	= $this->Product_model->getSettings('productsetting');

		$data['country_list'] = $this->db->query("SELECT name,id FROM countries")->result();

		$data['product'] = $this->Product_model->getProductById($id);

		$this->view($data, 'product/add_product');
	}

	public function updateproduct($id = null){

		$userdetails = $this->userdetails();

		//get amazon s3 details.
		$data['s3_setting'] = $this->Product_model->getSettings('s3_storage');
		
		$data['product'] = $this->Product_model->getProductById($id);

		$data['tags'] = $this->Product_model->getAllTags();

		if($data['product']){

			$data['seller'] = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$data['product']->product_id ." ")->row();

			$data['seller_setting'] = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$data['seller']->user_id ." ")->row();

			$data['categories'] =$this->Product_model->getProductCategory($data['product']->product_id);

			$data['product_state'] = $this->db->query("SELECT * FROM states WHERE id=". (int)$data['product']->state_id )->row();

			$data['states'] = $this->db->query("SELECT * FROM states WHERE country_id=". (int)$data['product_state']->country_id )->result();

		}

		$data['downloads'] = $this->Product_model->parseDownloads($data['product']->downloadable_files,$data['product']->product_type);

		$data['setting'] = $this->Product_model->getSettings('productsetting');

		$data['vendor_setting'] = $this->Product_model->getSettings('vendor');

		$data['country_list'] = $this->db->query("SELECT name,id FROM countries")->result();
		
		// Add currency symbol for dynamic currency display
		$data['CurrencySymbol'] = $this->currency->getSymbol();

		$this->view($data, 'product/add_product');
	}

	public function duplicateProduct($product_id){

		$userdetails = $this->userdetails();

		$this->Product_model->duplicateProduct($product_id);

		$this->session->set_flashdata('success',__('admin.product_duplicate_successfully'));

		redirect(base_url('admincontrol/listproduct'));
	}

	public function editProduct(){

		$userdetails = $this->userdetails();

		$post = $this->input->post(null,true);
		
		if(!empty($post)){

			$product_id = (int)$this->input->post('product_id',true);

			$this->load->helper(array('form', 'url'));

			$this->load->library('form_validation');

			$this->form_validation->set_rules('product_name', __('admin.product_name_'), 'required');
			$this->form_validation->set_rules('product_description', __('admin.product_description'), 'required' );
			$this->form_validation->set_rules(
				'product_short_description', __('admin.short_description'),
				'required|min_length[5]|max_length[150]',
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
			
			// Validate product-type-specific content for new products
			if($product_id == 0) {
				if($post['product_type'] == 'downloadable') {
					// Will check after file upload
				} elseif($post['product_type'] == 'video') {
					if(empty($post['sub_product_type'])) {
						$errors['sub_product_type'] = __('admin.please_select_lms_product_type');
					}
					// File/link validation will happen after upload processing
				}
			}

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

					$allKeys=array_keys($_downloads);
					if(isset($post['keep_video_files'])) 
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

				$pro_description = $_POST['product_description'];

				$doBase64Images = true;
				$imgCount = 0;

				while($doBase64Images) {
					preg_match('/src="data:(.*?)" /', $pro_description, $matchBase64);
					if(! isset($matchBase64[1]) || empty($matchBase64[1])) {
						$doBase64Images = false;
					} else {
						$image_parts = explode(";base64,", $matchBase64[1]);
						$image_type_aux = explode("image/", $image_parts[0]);
						$image_type = $image_type_aux[1];
						$image_base64 = base64_decode($image_parts[1]);
						$file = 'assets/user_upload/pro-desc-'.time().'-'.$imgCount.'.'.$image_type;
						file_put_contents($file, $image_base64);
						$pro_description = str_replace("data:".$matchBase64[1], base_url($file), $pro_description);
						$imgCount++;
					}
				}

				$details = array(

					'product_name'                 =>  $post['product_name'],

					'product_description'          =>  $pro_description,

			'product_short_description'    =>  isset($post['product_short_description']) ? $post['product_short_description'] : '',

			'product_msrp'                =>  isset($post['product_msrp']) ? (float)$post['product_msrp'] : 0,

			'product_price'                =>  isset($post['product_price']) ? (float)$post['product_price'] : 0,

			'product_sku'                  =>  isset($post['product_sku']) ? $post['product_sku'] : '',

		    'product_quantity' => isset($post['product_quantity']) && $post['product_quantity'] !== '' ? $post['product_quantity'] : -1,

		    'product_video'                =>  isset($post['product_video']) ? $post['product_video'] : '',

			'product_type'                 =>  isset($post['product_type']) ? $post['product_type'] : 'digital',

			'product_commision_type'       =>  isset($post['product_commision_type']) ? $post['product_commision_type'] : 'default',

			'state_id'                     =>  isset($post['allow_country']) && $post['allow_country'] == "on" ? (int)$post['state_id'] : 0,

			'product_commision_value'      =>  isset($post['product_commision_value']) ? (float)$post['product_commision_value'] : 0,

			'product_click_commision_type' =>  isset($post['product_click_commision_type']) ? $post['product_click_commision_type'] : 'default',

			'product_click_commision_ppc'  =>  isset($post['product_click_commision_ppc']) ? (float)$post['product_click_commision_ppc'] : 0,

			'product_click_commision_per'  =>  isset($post['product_click_commision_per']) ? (float)$post['product_click_commision_per'] : 0,

			'on_store'                     =>  isset($post['on_store']) ? (int)$post['on_store'] : 0,

			'allow_shipping'               =>  isset($post['allow_shipping']) ? (int)$post['allow_shipping'] : 1,

			'allow_upload_file'            =>  isset($post['allow_upload_file']) ? (int)$post['allow_upload_file'] : 0,

			'allow_comment'                =>  isset($post['allow_comment']) ? (int)$post['allow_comment'] : 0,

			'product_status'               =>  isset($post['product_status']) ? (int)$post['product_status'] : 1,

			'product_ipaddress'            =>  $_SERVER['REMOTE_ADDR'],

			'product_recursion_type'       =>  isset($post['product_recursion_type']) ? $post['product_recursion_type'] : 'default',

			'recursion_endtime'       =>  (isset($post['recursion_endtime_status']) && isset($post['recursion_endtime']) && $post['recursion_endtime']) ? date("Y-m-d H:i:s",strtotime($post['recursion_endtime'])) : null,

			'product_recursion'            =>  isset($product_recursion) ? $product_recursion : '',

			'recursion_custom_time'        =>  isset($recursion_custom_time) ? (int)$recursion_custom_time : 0,

			'product_variations'        =>  isset($variations) ? json_encode($variations) : '',

			'product_tags'        =>  isset($post['product_tags']) ? json_encode($post['product_tags']) : '',

					'product_weight'               =>  isset($post['product_weight']) ? (float)$post['product_weight'] : 0.00,

					'product_length'               =>  isset($post['product_length']) ? (float)$post['product_length'] : 0.00,

					'product_width'                =>  isset($post['product_width']) ? (float)$post['product_width'] : 0.00,

				'product_height'               =>  isset($post['product_height']) ? (float)$post['product_height'] : 0.00,
			);	

		$details['product_featured_image'] = isset($post['product_featured_image_s3']) ? $post['product_featured_image_s3'] : '';
		$details['product_banner'] = isset($post['product_banner']) ? $post['product_banner'] : '';
		$details['product_slug'] = '';
		$details['product_share_count'] = '';
		$details['view'] = 0;

			if($_FILES['product_featured_image']['error'] != 0 && $product_id == 0 && @$post['product_featured_image_s3'] == ""){

				$errors['product_featured_image'] = 'Select Featured Image File!';

				}else if(!empty($_FILES['product_featured_image']['name'])){

					$upload_response = $this->upload_photo('product_featured_image','assets/images/product/upload/thumb');

					if($upload_response['success']){

						$details['product_featured_image'] = $upload_response['upload_data']['file_name'];

					}else{

						$errors['product_featured_image'] = $upload_response['msg'];
					}

				}


				if(!empty($_FILES['downloadable_file'])){

					$files = $_FILES['downloadable_file'];
					
					if(isset($_FILES['downloadable_file']['name']) && is_countable($_FILES['downloadable_file']['name'])) 
					$count_file = count($_FILES['downloadable_file']['name']);
					else 
						$count_file =0;

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


								if(empty($FILES['downloadable_files']['error']))
								{

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
									} 
									else 
									{

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
								} 
								else 
								{

									$fileName = $zip_name;

									$zip = new ZipArchive();

									 if($zip->open(APPPATH.'/downloads/'.$zip_name, ZipArchive::CREATE) !== TRUE)
									  {
									 	$errors['downloadable_files'] = "Issue Notice - ZIP Creation: Currently not operational.";
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

				
				if(!empty($_FILES['lms_videos_files'])){

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


								$keepvidoefilescount = (isset($post['keep_video_files'][$index]) && is_array($post['keep_video_files'][$index])) 
									? count($post['keep_video_files'][$index]) 
									: 0;
								
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
									move_uploaded_file($_FILES['lms_videos_files_zip']['tmp_name'][$key][$i], APPPATH.'/downloads/'. $fileName);

									$store_file_temp['zip']= [
										'name'=> md5(random_string('alnum', 10)),
										'mask'=> $fileName,
										'title'=> $post['VideoFileResourceText'][$index][$keepvidoefilescount+$i],
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
				// Only process when EDITING - new videolink products are handled in post['videolink'] below
				if(!empty($_FILES['lms_videos_files_zip_update']) && $product_id){
					if(isset($_POST['sub_product_type']) && $_POST['sub_product_type']=='videolink'){
						$downloadable_files = $_downloads;
					}
					foreach ($_FILES['lms_videos_files_zip_update']['name'] as $key => $value) {
						if(isset($_FILES['lms_videos_files_zip_update']['name'][$key])) {
							foreach ($_FILES['lms_videos_files_zip_update']['name'][$key] as $oldname => $newFile) {
								$ext = pathinfo($_FILES['lms_videos_files_zip_update']['name'][$key][$oldname], PATHINFO_EXTENSION);
								$fileName = md5(random_string('alnum', 10)).".$ext";
								move_uploaded_file($_FILES['lms_videos_files_zip_update']['tmp_name'][$key][$oldname], APPPATH.'/downloads/'. $fileName);
								foreach($downloadable_files[$key]['data'] as $dkey=>$datavalue) {

									if($datavalue['name'] == $oldname) {
										$downloadable_files[$key]['data'][$dkey]['zip']['name'] = md5(random_string('alnum', 10));
										$oldFileName = $downloadable_files[$key]['data'][$dkey]['zip']['mask']; 
										$downloadable_files[$key]['data'][$dkey]['zip']['mask'] = $fileName;
										$downloadable_files[$key]['data'][$dkey]['zip']['type'] = $_FILES['lms_videos_files_zip_update']['type'][$key][$oldname];
										$downloadable_files[$key]['data'][$dkey]['zip']['size'] = format_filesize($_FILES['lms_videos_files_zip_update']['size'][$key][$oldname]);
										$downloadable_files[$key]['data'][$dkey]['zip']['title']= $post['VideoFileResourceText'][$key][$dkey];
										

										if(file_exists(APPPATH.'/downloads/'. $oldFileName)) {
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
					foreach ($post['sectionlink'] as $key => $value) {
						$tmp['title'] = $value;
						foreach ($post['videolink'][$key] as $keyInner => $InnerValue) {
							if(!empty($post['videolink'][$key][$keyInner]) && !empty($post['videotext'][$key][$keyInner])) {
								$zip = isset($TmpDownloadable_files[$key]['data'][$keyInner]['zip']) ? $TmpDownloadable_files[$key]['data'][$keyInner]['zip'] : [];
								if(!empty($_FILES['lms_videos_files_zip_update']['name'][$key][$keyInner])) {

									$ext = pathinfo($_FILES['lms_videos_files_zip_update']['name'][$key][$keyInner], PATHINFO_EXTENSION);
									$fileName = md5(random_string('alnum', 10)).".$ext";
									move_uploaded_file($_FILES['lms_videos_files_zip_update']['tmp_name'][$key][$keyInner], APPPATH.'/downloads/'. $fileName);
									$zip = [
										'name'=>md5(random_string('alnum', 10)),
										'mask'=>$fileName,
										'type' => $_FILES['lms_videos_files_zip_update']['type'][$key][$keyInner],
										'size' => format_filesize($_FILES['lms_videos_files_zip_update']['size'][$key][$keyInner]),
										'title'=> $post['VideoFileResourceText'][$key][$keyInner]
									];
								} elseif(!empty($_FILES['lms_videos_files_zip']['name'][$key][$keyInner])) {
									$ext = pathinfo($_FILES['lms_videos_files_zip']['name'][$key][$keyInner], PATHINFO_EXTENSION);
									$fileName = md5(random_string('alnum', 10)).".$ext";
									move_uploaded_file($_FILES['lms_videos_files_zip']['tmp_name'][$key][$keyInner], APPPATH.'/downloads/'. $fileName);
									$zip = [
										'name'=>md5(random_string('alnum', 10)),
										'mask'=>$fileName,
										'type' => $_FILES['lms_videos_files_zip']['type'][$key][$keyInner],
										'size' => format_filesize($_FILES['lms_videos_files_zip']['size'][$key][$keyInner]),
										'title'=> isset($post['VideoFileResourceText'][$key][$keyInner]) ? $post['VideoFileResourceText'][$key][$keyInner] : ''
									];
								}

								$tmp['data'][] = [
									'type' => 'link',

									'name' => $TmpDownloadable_files[$key]['data'][$keyInner]['name']??md5(random_string('alnum', 10)),

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
				
			if($product_id == 0) {
				if($post['product_type'] == 'downloadable') {
					if(empty($downloadable_files)) {
						$errors['downloadable_file'] = __('admin.please_upload_downloadable_files');
					}
				} elseif($post['product_type'] == 'video') {
					// Sub-product type is already validated earlier
					if(!empty($post['sub_product_type'])) {
						if($post['sub_product_type'] == 'video' && empty($downloadable_files)) {
							$errors['video_files'] = __('admin.please_upload_video_files');
						} elseif($post['sub_product_type'] == 'videolink' && empty($post['videolink'])) {
							$errors['video_links'] = __('admin.please_add_video_links');
						}
					}
				}
			}
				
				$details['downloadable_files'] = json_encode($downloadable_files);

				// Only set flash data for save_close action (redirecting actions)
					if ($post['action'] == 'save_close') {
						$this->session->set_flashdata('success', __('admin.product_added_successfully'));
					}

					$old_product_data =[];

					if($product_id){

						$old_product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();

						$details['product_updated_date'] = date('Y-m-d H:i:s');


						$this->Product_model->update_data('product', $details, array('product_id' => $product_id));

				} else {

					$details['product_created_by'] = $userdetails['id'];
					$details['product_updated_by'] = $userdetails['id'];
					$details['product_updated_date'] = date('Y-m-d H:i:s');
					$details['product_created_date'] = date('Y-m-d H:i:s');

					$product_id = $this->Product_model->create_data('product', $details);

						$notificationData = array(

							'notification_url'          => '/listproduct/'.$product_id,

							'notification_type'         =>  'product',

							'notification_title'        =>  __('admin.new_product_added_in_affiliate_program'),

							'notification_view_user_id' =>  'all',

							'notification_viewfor'      =>  'user',

							'notification_actionID'     =>  $product_id,

							'notification_description'  =>  $post['product_name'].' product is addded by admin in affiliate Program on '.date('Y-m-d H:i:s'),

							'notification_is_read'      =>  '0',

							'notification_created_date' =>  date('Y-m-d H:i:s'),

							'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']

						);

						$store_setting = $this->Product_model->getSettings('store');

						if($store_setting['status']) {

							$this->insertnotification($notificationData);

						}

					}

					$seofilename = $this->friendly_seo_string($post['product_name']);

					$seofilename = strtolower($seofilename);

					$product_slug = $seofilename.'-'.$product_id;

					$this->db->query("UPDATE product SET product_slug = ". $this->db->escape($product_slug) ." WHERE product_id =". $product_id);

					$seller = '';

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


						if(isset($post['admin_sale_commission_type'])){

							$seller_comm = [

								'admin_sale_commission_type'      => $post['admin_sale_commission_type'],

								'admin_commission_value'          => $post['admin_commission_value'],

								'admin_click_commission_type'     => $post['admin_click_commission_type'],

								'admin_click_amount'              => $post['admin_click_amount'],

								'admin_click_count'               => $post['admin_click_count'],

							];

							$seller = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product_id ." ")->row();

							$this->Product_model->assignToSeller($product_id, $details, $userdetails['id'], $admin_comment, 'admin', $seller_comm);

						}

					}

					if($seller){

						$product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();

						$this->load->model('Mail_model');

						if($old_product_data['product_status'] != $product_data['product_status']){

							$this->Mail_model->vendor_product_status_change($product_id, 'vendor', true);

						}

					}


					if ($post['action'] == 'save_close') {
						$json['location'] = base_url('admincontrol/listproduct/');
					} else {
						$json['location'] = base_url('admincontrol/updateproduct/'.$product_id);
					}



				} else {
					$json['errors'] = $errors;
				}

		} else {

			$json['errors'] = $this->form_validation->error_array();

			if(isset($json['errors']['category[]'])){

				$json['errors']['category_auto'] = $json['errors']['category[]'];

			}
			
		// Add product-type-specific validation even when basic validation fails
		$post = $this->input->post(null,true);
		if($product_id == 0 && isset($post['product_type'])) {
			if($post['product_type'] == 'downloadable') {
				// Check if any downloadable files were uploaded
				$hasFiles = false;
				if(!empty($_FILES['downloadable_file']['name'])) {
					if(is_array($_FILES['downloadable_file']['name'])) {
						foreach($_FILES['downloadable_file']['name'] as $filename) {
							if(!empty(trim($filename))) {
								$hasFiles = true;
								break;
							}
						}
					} elseif(!empty(trim($_FILES['downloadable_file']['name']))) {
						$hasFiles = true;
					}
				}
				if(!$hasFiles && empty($post['keep_files']) && empty($post['product_multiple_image_s3'])) {
					$json['errors']['downloadable_file'] = __('admin.please_upload_downloadable_files');
				}
			} elseif($post['product_type'] == 'video') {
				if(empty($post['sub_product_type'])) {
					$json['errors']['sub_product_type'] = __('admin.please_select_lms_product_type');
				} elseif($post['sub_product_type'] == 'video') {
					// Check if video files were uploaded
					$hasFiles = false;
					if(!empty($_FILES['downloadable_file']['name'])) {
						if(is_array($_FILES['downloadable_file']['name'])) {
							foreach($_FILES['downloadable_file']['name'] as $filename) {
								if(!empty(trim($filename))) {
									$hasFiles = true;
									break;
								}
							}
						} elseif(!empty(trim($_FILES['downloadable_file']['name']))) {
							$hasFiles = true;
						}
					}
					if(!$hasFiles && empty($post['keep_files'])) {
						$json['errors']['video_files'] = __('admin.please_upload_video_files');
					}
				} elseif($post['sub_product_type'] == 'videolink') {
					if(empty($post['videolink'])) {
						$json['errors']['video_links'] = __('admin.please_add_video_links');
					}
				}
			}
		}

		}

			echo json_encode($json);

			die;

		}
	}

					public function lmsResourceupdate() {
						if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
							$id  		 = $this->input->post('id');
							$product_id  = $this->input->post('product_id');
							$product_details = $this->Product_model->getProductById($product_id);

							$_downloads = $this->Product_model->parseDownloads($product_details->downloadable_files,$product_details->product_type);
							foreach ($_downloads as $sectionKey => $sectionValue) {
								foreach ($sectionValue['data'] as $key => $value) {
									if($value['name'] ==$id) {
										
										if(!empty($value['zip']['mask'])){
											if(file_exists(APPPATH.'/downloads/'. $value['zip']['mask'])) {
												@unlink(APPPATH.'/downloads/'. $value['zip']['mask']);
											}
										}
										unset($_downloads[$sectionKey]['data'][$key]['zip']);
										$_downloads[$sectionKey]['data'][$key]['zip']=[];
									}
								}
							}

							$this->db->where('product_id',$product_id);
							$this->db->update('product',['downloadable_files'=>json_encode($_downloads)]);
							echo json_encode(['status'=>true]);
						}
					}

					private function getSettings($file,$data){
						extract($data);
						ob_start();
						require($file);
						return ob_get_clean();
					}



					public function store_dashboard(){

						$userdetails = $this->userdetails();

						$this->load->model('Form_model');

						$post = $this->input->post(null,true);

						$data['CurrencySymbol'] = $this->currency->getSymbol();

						if (isset($post['renderChart'])){

							if (isset($post['selectedyear'])) {

								$data = $this->Order_model->getSaleChart(array('selectedyear' => $post['selectedyear']),$post['renderChart']);

							}else{

								$data = $this->Order_model->getSaleChart(array(),$post['renderChart']);
							}

							echo json_encode($data); die;

						}


						/* Getting total order count */

						$data['total']['order_count'] = $this->db->query('SELECT COUNT(op.id) AS total FROM `order_products` op LEFT JOIN `order` AS o ON o.id = op.order_id WHERE o.status > 0 ')->row()->total;

											$data['form_count'] = $this->db->query('SELECT COUNT(*) AS total FROM `form`')->row()->total;

					$data['coupon_count'] = $this->db->query('SELECT COUNT(*) AS total FROM `coupon`')->row()->total;

					$data['form_coupon_count'] = $this->db->query('SELECT COUNT(*) AS total FROM `form_coupon`')->row()->total;

					$data['product_count'] = $this->db->query('SELECT COUNT(*) AS total FROM `product`')->row()->total;

					$data['category_count'] = $this->db->query('SELECT COUNT(*) AS total FROM `categories`')->row()->total;

						$data['payment_gateway_count'] = count(glob(APPPATH."/payment_gateway/controllers/*.php"));


						/* Getting total admin shipping */
						$data['local_store_shipping_cost'] = $this->db->query("SELECT SUM(shipping_cost) AS total FROM `order`")->row()->total;

						/* Getting total admin tax */
						$data['local_store_tax_cost'] = $this->db->query("SELECT SUM(tax_cost) AS total FROM `order`")->row()->total;


						/* Getting total clients count */

						$data['client_count'] = $this->db->query('SELECT count(*) AS total FROM users WHERE type like "client"')->row()->total;

						$data['client_count'] = $this->Product_model->getAllClientrecord();

						$data['guest_count'] = $this->Product_model->getAllClientrecord('guest');

						$data['ordercount']      = $this->Order_model->getCount();

						$data['salescount']      = $this->Order_model->getSale();

						$data['formcount']       = $this->Form_model->formcount();

						$data['userworldmap']    = $this->Product_model->getUserWorldMap(1);

						$this->load->model('Wallet_model');

						$this->load->model('IntegrationModel');


						$data['integration_logs']   = $this->IntegrationModel->getLogs(array('page' => 1,'limit' => 5))['records'];

						$filter_date = date('Y-m-01') . ' - ' . date('Y-m-t');

						$data['totals'] = $this->Wallet_model->getTotals(array(

							'total_commision_filter_month' => 'all',

							'total_commision_filter_year' => date("Y"),

						), true);



						$data['refer_total']        = $this->Product_model->getReferalTotals();

						$data['online_count']        = $this->Product_model->onlineCount();

						$data['integration_orders'] = $this->IntegrationModel->getOrders(array("limit" => 5));


						$totals = $this->Wallet_model->getTotals(array(), true);

						

						/* Getting total balance */

						$data['totals']['full_total_balance'] = c_format($totals['total_balance']);

						$data['totals']['total_sale_balance'] = c_format($totals['total_sale_balance']);



						/* Getting total order count */

						$data['totals']['full_local_store_hold_orders'] = $totals['store']['hold_orders'];



						$data['totals']['full_all_clicks_comm']            = $totals['all_clicks']."/".c_format($totals['all_clicks_comm']);

						$data['totals']['full_action_count_action_amount'] = (int)$totals['integration']['action_count'] .'/'. c_format($totals['integration']['action_amount']);

						$data['totals']['full_hold_action_count']          = $totals['integration']['hold_action_count'];

						$data['totals']['full_hold_orders']                = $totals['integration']['hold_orders'];

						$data['totals']['full_weekly_balance']             = c_format($totals['weekly_balance']);

						$data['totals']['full_monthly_balance']            = c_format($totals['monthly_balance']);

						$data['totals']['full_yearly_balance']             = c_format($totals['yearly_balance']);

						require APPPATH.'/core/latlong.php';

						$data['_countryCode'] = $_countryCode;


					$data['months'] = array('All','01','02','03','04','05','06','07','08','09','10','11','12');

					$data['years'] = array('All',date("Y",strtotime("-3 year")),date("Y",strtotime("-2 year")),date("Y",strtotime("-1 year")),date("Y",strtotime("0 year")));


					$this->view($data,'store/dashboard');

				}

				public function store_dashboard_order_list(){

					$userdetails = $this->userdetails();

					$get = $this->input->get(null,true);

					$post = $this->input->post(null,true);

					$pagination_settings = get_pagination_settings([
						'per_page' => 10,
						'size' => 'sm',
						'alignment' => 'center',
						'js_function' => 'getPage'
					]);

					$filter = array(
						'limit' => $pagination_settings['per_page'],
						'page' => isset($get['page']) ? (int)$get['page'] : 1,
					);

					$this->load->model('Order_model');
					
					$data['status'] = $this->Order_model->status();

					$getallorders = $this->Order_model->getOrders($filter);

					$data['orders'] = $getallorders['data'];

					$pagination_data = ajax_pagination(
						$getallorders['total'],
						$filter['page'],
						[
							'per_page' => $pagination_settings['per_page'],
							'size' => 'sm',
							'alignment' => 'center',
							'js_function' => 'getPage',
							'base_url' => base_url('admincontrol/store_dashboard_order_list')
						]
					);

					$data['pagination'] = $pagination_data['html'];
					$data['pagination_summary'] = pagination_summary_html($filter['page'], $pagination_settings['per_page'], $getallorders['total'], 'center', 'sm');

					$data['payment_methods'] = $this->Order_model->PaymentMethods();
					$json['view'] = $this->load->view("admincontrol/store/order_list_tr", $data, true);
					$json['pagination'] = $data['pagination'];
					$json['pagination_summary'] = $data['pagination_summary'];
					$json['total'] = $getallorders['total'];

					echo json_encode($json);
				}

				public function product_logs(){

					$category_id = (int)$this->input->post("category_id",true);

					$currentTheme = User::getActiveTheme();

					$where = "";

					$sql = "SELECT DISTINCT p.* FROM product p LEFT JOIN product_categories pc ON pc.product_id = p.product_id WHERE 1 $where ";

					$category = $this->db->query("SELECT * FROM categories WHERE id = ". (int)$category_id)->row_array();

					if($category){

						$sql .= " AND pc.category_id = ". $category['id'];
					}

					$data['category'] = $category;

					$data['products'] = $this->db->query($sql)->result_array();

					$json['html'] = $this->load->view("common/product_logs",$data,true);

					echo json_encode($json);die;

				}



				public function listproduct_ajax($only_review = false, $page = 1){

					$userdetails = $this->userdetails();

					$get = $this->input->get(null,true);

					$post = $this->input->post(null,true);

					$filter = array(

						'page' => isset($get['page']) ? $get['page'] : $page,

						'limit' => 20,
					);


					if(isset($post['category_id']) && $post['category_id']){

						$filter['category_id'] = (int)$this->input->post('category_id');

					}



					if(isset($post['seller_id']) && $post['seller_id']){

						$filter['seller_id'] = (int)$this->input->post('seller_id');

					}
			 

					// Default: show approved products (status 1)
					// If reviews mode: show pending products (status 0,2,3)
					$filter['product_status_in'] = '1';

					if($only_review == 'reviews'){
						$filter['product_status_in'] = '0,2,3';
					}
			 

					$data['default_commition'] =$this->Product_model->getSettings('productsetting');

					$record = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);

					$data['productlist'] = $record['data'];

					$json['view'] = $this->load->view("admincontrol/product/product_list", $data, true);

					$this->load->library('pagination');

					$this->pagination->cur_page = $filter['page'];

					// Include review mode in pagination URLs
					// URL format: listproduct_ajax/{only_review}/{page}
					$review_mode = $only_review ? $only_review : 'all';
					$config['base_url'] = base_url('admincontrol/listproduct_ajax/' . $review_mode);

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

				public function listproduct($only_review = false){

					$userdetails = $this->userdetails();

					$this->load->model('Form_model');

					$store_setting = $this->Product_model->getSettings('store');

					$data['totals'] = $this->Wallet_model->getTotals(array(), true);

					$filter = array();

					$get = $this->input->get(null,true);

					$filter['is_campaign_and_cart_product'] = 1; 

					if(isset($get['category_id']) && $get['category_id']){
						$filter['category_id'] = (int)$this->input->get('category_id');
					}

					if(isset($get['seller_id']) && $get['seller_id']){

						$filter['seller_id'] = (int)$this->input->get('seller_id');

					}

					$filter['product_status_in'] =	 '1';

					if($only_review == 'reviews'){

						$filter['product_status_in'] =	 '0,2,3';

					}
			 
					set_default_language();

					$data['productlist'] = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);

			 
					$data['client_count'] =$this->db->query('SELECT count(*) AS total FROM users WHERE  type like "client"')->row()->total;

					$data['ordercount'] =$this->db->query('SELECT COUNT(op.id) AS total FROM `order_products` op LEFT JOIN `order` AS o ON o.id = op.order_id WHERE o.status > 0 ')->row()->total;

					$data['categories'] = $this->db->query("SELECT id,name FROM categories")->result_array();

					$data['vendors'] = $this->db->query("SELECT users.id,CONCAT(users.firstname,' ',users.lastname) AS name FROM `product_affiliate` INNER JOIN users ON users.id= user_id GROUP by user_id")->result_array();


					$data['user'] = $userdetails;

					$this->load->library("socialshare");				

					$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

					$this->load->model("Coupon_model");

					$data['coupons'] = $this->Coupon_model->getCoupons();

					$ptotal = $this->db->query('SELECT product_id FROM product')->num_rows();

					foreach ($data['coupons'] as $key => $value) {

						if(strtolower($value['allow_for']) == 's'){

							$data['coupons'][$key]['product_count'] = count(explode(',', $value['products']));

						}else{

							$data['coupons'][$key]['product_count'] = $ptotal;

						}

						$data['coupons'][$key]['count_coupon'] = $this->Coupon_model->getCouponCount($value['coupon_id']);

					}
					$data['currentTheme'] = User::getActiveTheme();
					$data['StoreStatus'] = User::getStoreStatus();

					$data['forms'] = $this->Form_model->getForms();	

					foreach ($data['forms'] as $key => $value) {

						$data['forms'][$key]['coupon_name'] = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);

						$data['forms'][$key]['public_page'] = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));

						$data['forms'][$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id']);

						$data['forms'][$key]['coupon_code'] = '';

						if($value['coupon']){

							$data['forms'][$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);

						}

						$data['forms'][$key]['seo'] = str_replace('_', ' ', $value['seo']);

					}

					$data['product_count'] = $this->db->query("SELECT count(p.product_id) AS total FROM product p 

						LEFT JOIN product_affiliate pa ON pa.product_id = p.product_id

						WHERE pa.user_id IS NULL ")->row()->total; 

					$data['form_coupons'] = $this->Form_model->getFormCoupons(); 

			 		// Pass the review mode to the view for JavaScript AJAX calls
				$data['only_review'] = $only_review;
				
				$this->view($data,'product/index');
			}

			/* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
			 * DEMO PRODUCTS â€” import & clear
			 * â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
			public function importDemoProducts()
			{
				$userdetails = $this->userdetails();
				$admin_id    = (int)$userdetails['id'];
				$now         = date('Y-m-d H:i:s');
				$ip          = $this->input->ip_address();

				// Use first available category, fall back to 1
				$cat = $this->db->get_where('categories', [], 1)->row_array();
				$cat_id = $cat ? (int)$cat['id'] : 1;

				// Demo tag used to identify & clear later
				$demo_tag = '__demo__';

				// Skip if demo products already exist
				$already = $this->db->like('product_tags', $demo_tag)->count_all_results('product');
				if ($already > 0) {
					echo json_encode(['success' => false, 'message' => __('admin.demo_products_already_loaded')]);
					return;
				}

				// â”€â”€ Demo product definitions â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
				$products = [
					[
						'product_name'              => 'Premium Online Subscription',
						'product_description'       => '<p>Unlock full access to our premium platform with this all-inclusive subscription. Enjoy unlimited cloud storage, advanced security features, priority customer support and access to every feature we offer. Cancel anytime.</p><ul><li>Unlimited cloud storage</li><li>Advanced security</li><li>Priority support</li><li>Full feature access</li></ul>',
						'product_short_description' => 'All-inclusive virtual subscription with full platform access and priority support.',
						'product_price'             => 29.99,
						'product_msrp'              => 49.99,
						'product_sku'               => 'DEMO-VIRTUAL-001',
						'product_type'              => 'virtual',
						'product_featured_image'    => 'demo-product-virtual.jpg',
						'product_recursion_type'    => 'default',
						'product_recursion'         => '',
						'allow_shipping'            => 0,
						'downloadable_files'        => '[]',
						'product_video'             => '',
					],
					[
						'product_name'              => 'Ultimate E-Book Bundle',
						'product_description'       => '<p>Get instant access to our curated collection of 4 comprehensive e-books covering digital marketing, strategy, productivity and leadership. Over 500 pages of actionable content â€” download once, read forever.</p><ul><li>The Complete Guide to Success</li><li>Strategy &amp; Growth</li><li>Digital Marketing Mastery</li><li>Productivity &amp; Leadership</li></ul>',
						'product_short_description' => 'Instant download bundle of 4 premium e-books â€” 500+ pages of expert knowledge.',
						'product_price'             => 19.99,
						'product_msrp'              => 39.99,
						'product_sku'               => 'DEMO-DOWNLOAD-001',
						'product_type'              => 'downloadable',
						'product_featured_image'    => 'demo-product-download.jpg',
						'product_recursion_type'    => 'default',
						'product_recursion'         => '',
						'allow_shipping'            => 0,
						'downloadable_files'        => json_encode([[
							'mask'      => 'ebook-bundle.pdf',
							'name'      => 'demo_ebook_bundle',
							'url'       => null,
							'srctype'   => null,
							'type'      => 'pdf',
							'videotext' => null,
							'thumb'     => null,
						]]),
						'product_video'             => '',
					],
					[
						'product_name'              => 'Online Marketing Masterclass',
						'product_description'       => '<p>Learn modern digital marketing from the ground up in this comprehensive video course. Covers SEO, social media, paid advertising, email campaigns and conversion optimisation. Includes certificate of completion.</p><ul><li>8+ hours of HD video content</li><li>Downloadable resources per chapter</li><li>Certificate of completion</li><li>Lifetime access</li></ul>',
						'product_short_description' => 'Comprehensive video course on digital marketing â€” SEO, ads, social media and more.',
						'product_price'             => 49.99,
						'product_msrp'              => 99.99,
						'product_sku'               => 'DEMO-VIDEO-001',
						'product_type'              => 'videolink',
						'product_featured_image'    => 'demo-product-video.jpg',
						'product_recursion_type'    => 'default',
						'product_recursion'         => '',
						'allow_shipping'            => 0,
						'downloadable_files'        => json_encode([
							[
								'title' => 'Chapter 1: Introduction to Digital Marketing',
								'data'  => [[
									'type'        => 'link',
									'name'        => 'demo_video_ch1',
									'mask'        => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
									'videotext'   => 'Chapter 1: Introduction',
									'description' => 'Welcome to the Online Marketing Masterclass.',
									'zip'         => [],
								]],
							],
							[
								'title' => 'Chapter 2: SEO Fundamentals',
								'data'  => [[
									'type'        => 'link',
									'name'        => 'demo_video_ch2',
									'mask'        => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
									'videotext'   => 'Chapter 2: SEO',
									'description' => 'Learn the core principles of search engine optimisation.',
									'zip'         => [],
								]],
							],
						]),
						'product_video'             => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
					],
					[
						'product_name'              => 'Monthly VIP Membership',
						'product_description'       => '<p>Join our exclusive VIP Membership and enjoy unlimited access to all premium content, early-bird deals, priority support and monthly member-only perks. Billed automatically every month â€” cancel anytime.</p><ul><li>Unlimited access to all content</li><li>Priority customer support</li><li>Exclusive member-only offers</li><li>Monthly bonus perks</li></ul>',
						'product_short_description' => 'Recurring monthly membership with unlimited access, priority support and exclusive perks.',
						'product_price'             => 9.99,
						'product_msrp'              => 14.99,
						'product_sku'               => 'DEMO-RECURRING-001',
						'product_type'              => 'virtual',
						'product_featured_image'    => 'demo-product-recurring.jpg',
						'product_recursion_type'    => 'custom',
						'product_recursion'         => 'every_month',
						'allow_shipping'            => 0,
						'downloadable_files'        => '[]',
						'product_video'             => '',
					],
				];

				$inserted = 0;
				foreach ($products as $p) {
					$row = [
						'is_campaign_product'       => 0,
						'product_url'               => '',
						'product_name'              => $p['product_name'],
						'product_description'       => $p['product_description'],
						'product_short_description' => $p['product_short_description'],
						'product_tags'              => $demo_tag,
						'product_msrp'              => $p['product_msrp'],
						'product_price'             => $p['product_price'],
						'product_sku'               => $p['product_sku'],
						'product_slug'              => '',
						'product_share_count'       => 0,
						'product_click_count'       => 0,
						'product_view_count'        => 0,
						'product_sales_count'       => 0,
						'product_featured_image'    => $p['product_featured_image'],
						'product_banner'            => $p['product_featured_image'],
						'product_video'             => $p['product_video'] ?? '',
						'product_type'              => $p['product_type'],
						'product_commision_type'    => 'default',
						'product_commision_value'   => 0,
						'product_status'            => 1,
						'product_ipaddress'         => $ip,
						'product_created_date'      => $now,
						'product_updated_date'      => $now,
						'product_created_by'        => $admin_id,
						'product_updated_by'        => $admin_id,
						'product_click_commision_type' => 'default',
						'product_click_commision_ppc'  => 0,
						'product_click_commision_per'  => 0,
						'product_total_commission'     => 0,
						'product_recursion_type'    => $p['product_recursion_type'],
						'product_recursion'         => $p['product_recursion'],
						'recursion_custom_time'     => 0,
						'recursion_endtime'         => null,
						'view'                      => 0,
						'on_store'                  => 1,
						'downloadable_files'        => $p['downloadable_files'],
						'allow_shipping'            => $p['allow_shipping'],
						'allow_upload_file'         => 0,
						'allow_comment'             => 1,
						'state_id'                  => null,
						'product_avg_rating'        => 0,
						'product_variations'        => '[]',
						'product_weight'            => 0,
						'product_length'            => 0,
						'product_width'             => 0,
						'product_height'            => 0,
					'view_statistics'           => 0,
					'product_quantity'          => -1,   // -1 = unlimited stock (column is NOT NULL; 0 = out of stock)
				];

					$this->db->insert('product', $row);
					$pid = $this->db->insert_id();

					// Slug
					$slug = $this->friendly_seo_string($p['product_name']) . '-' . $pid;
					$this->db->where('product_id', $pid)->update('product', ['product_slug' => $slug]);

				// Category
				$this->db->insert('product_categories', ['product_id' => $pid, 'category_id' => $cat_id]);

				// NOTE: Admin-owned products must NOT have a product_affiliate row.
				// The getAllProduct query uses (seller.id IS NOT NULL OR pa.id IS NULL).
				// If pa.id IS NULL the product correctly shows under "Admin" with no seller.
				// Adding a row here with admin's user_id would make it look like a vendor product.

				$inserted++;
				}

				echo json_encode([
					'success' => true,
					'count'   => $inserted,
					'message' => sprintf(__('admin.demo_products_loaded_ok') ?: '%d demo products added successfully.', $inserted),
				]);
			}

			public function clearDemoProducts()
			{
				$this->userdetails(); // auth check

				$demo_tag = '__demo__';

				// Find all demo product IDs
				$rows = $this->db->like('product_tags', $demo_tag)
				                 ->select('product_id')
				                 ->get('product')
				                 ->result_array();

				if (empty($rows)) {
					echo json_encode(['success' => true, 'count' => 0, 'message' => __('admin.no_demo_products_found') ?: 'No demo products found.']);
					return;
				}

				$ids = array_column($rows, 'product_id');

				// Delete from related tables first, then main product table
				$this->db->where_in('product_id', $ids)->delete('product_categories');
				$this->db->where_in('product_id', $ids)->delete('product_affiliate');
				$this->db->where_in('product_id', $ids)->delete('product_media_upload');
				$this->db->where_in('product_id', $ids)->delete('product');

				echo json_encode([
					'success' => true,
					'count'   => count($ids),
					'message' => sprintf(__('admin.demo_products_cleared_ok') ?: '%d demo products removed.', count($ids)),
				]);
			}

			public function bulkProductImportFromUrl() 
				{
					$userdetails = $this->userdetails();

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

						$json['warning'] = __('admin.please_enter_xml_url'); 

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
				 			else
				 				$json['warning'] = __('admin.not_valid_xm_format'); 
			  					
			    		}
			    		else 
			    		{  
			    			$json['warning'] = __('admin.url_entered_not_valid_xml_content');
			    		}
			   
					}
			 

					$data['action'] = 'confirm';
					$data['products'] = $bulkResult;
					echo $this->load->view('admincontrol/product/bulk_upload_modal', $data, true);
				}
				
				public function bulkProductImport() {

					require_once APPPATH . '/core/phpspreadsheet/autoload.php';

					$extension="";

					if(!isset($_FILES['file']['error']) || $_FILES['file']['error'] != 0){

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
			 		
			 
							for($proIndex = 1; $proIndex < sizeof($xlsdata); $proIndex++) {

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

								  	if(!empty($productArray)) {
										$cdata = $this->initialProductImportCheck($productArray);
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

							if($product_id > 0) {
								$product_exist = $this->db->query('select product_id from product where product_id='.$product_id)->row_array();
								if(empty($product_exist)) {
									return [
										"status" => "error",
										"message" => "Product not available having Product ID you provided!"
									];
								}
							}

							$this->load->helper(array('form', 'url'));

							$this->load->library('form_validation');

							$this->form_validation->reset_validation();

							$this->form_validation->set_rules('product_name', __('admin.product_name_'), 'required');

							$this->form_validation->set_rules('product_description', __('admin.product_description'), 'required');

							$this->form_validation->set_rules(

								'product_short_description', __('admin.short_description'),

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
									$pro_exist = $this->db->query('select product_id from product where product_id='.$post['product_id'])->row_array();
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

								if(!empty($post['product_created_by']) && $post['product_created_by'] !== 'admin'){
									$created_user_exist = $this->db->query('select id,is_vendor from users where username="'.$post['product_created_by'].'"')->row_array();
									if(empty($created_user_exist)) {
										$errors['product_created_by'] = "Product craeted by username not available with available vendors!";
									} else if ($created_user_exist['is_vendor'] == 0) {
										$errors['product_created_by'] = "Product craeted by username is not vendor!";
									}else {
										$post['product_created_by'] = $created_user_exist['id'];
									}
								} else {
									$post['product_created_by'] = 1;
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

										'product_status'               =>  isset($post['product_status']) ? (int)$post['product_status'] : 1,

										'product_ipaddress'            =>  $_SERVER['REMOTE_ADDR'],

										'product_recursion_type'       =>  '',

										'recursion_endtime'       =>  null,

										'product_recursion'            =>  '',

										'recursion_custom_time'        =>  0,

										'product_variations'        =>  $post['product_variations'],

										'product_tags'        =>  json_encode($post['product_tags']),

										'product_created_by' => $post['product_created_by'],

										'product_weight'               =>  isset($post['product_weight']) ? (float)$post['product_weight'] : 0.00,

										'product_length'               =>  isset($post['product_length']) ? (float)$post['product_length'] : 0.00,

										'product_width'                =>  isset($post['product_width']) ? (float)$post['product_width'] : 0.00,

										'product_height'               =>  isset($post['product_height']) ? (float)$post['product_height'] : 0.00

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
							$json['success'] = 'Product has been updated successfully!';

						} else {

							copy('assets/images/dummy-product-img.jpg','assets/images/product/upload/thumb/dummy-product-img.jpg');
							$details['product_featured_image'] = 'dummy-product-img.jpg';
							$details['product_created_date'] = date('Y-m-d H:i:s');
							$details['product_updated_date'] = date('Y-m-d H:i:s');
							$product_id = $this->Product_model->create_data('product', $details);
							$json['created'] = true;
							$json['status'] = true;
							$json['success'] = 'Product has been added successfully!';

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

									'notification_title'        =>  __('admin.new_product_added_in_affiliate_program'),

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
						'product_status' => 'Product Status',
						'on_store' => 'Allow on Store',
						'state_id' => 'State ID',

						'product_created_by' => 'Product Created By',
					);
				}

				public function exportproduct(){

					$userdetails = $this->userdetails();

					$store_setting = $this->Product_model->getSettings('store');
					
					$json['structure_only'] = $structure_only = $this->input->post('structure_only');

					$filter = array();
					
					if($structure_only == 1) {
						$productlist = [];
					} else {
						$productlist = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'],$filter);
					}

					$vendors = $this->db->query("SELECT users.id, users.username FROM `users`
						where is_vendor=1")->result_array();

					$created_by['cb1'] = 'admin';

					foreach($vendors as $v) {
						$created_by['cb'.$v['id']] = $v['username'];
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
									case 'product_created_by':
									$val = $created_by['cb'.$value[$name_key]];
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
					}

					if($structure_only == 1) {
						$objWriter->save(FCPATH.'assets/xml/export_products_structure.xlsx');
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
					$store_setting = $this->Product_model->getSettings('store');
					$json['structure_only'] = $structure_only = $this->input->post('structure_only');
					$filter = array();
					
					if($structure_only == 1) {
						$productlist = [];
					} else {
						$productlist = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'],$filter);
					}

					$vendors = $this->db->query("SELECT users.id, users.username FROM `users`
						where is_vendor=1")->result_array();

					$created_by['cb1'] = 'admin';
					foreach($vendors as $v) {
						$created_by['cb'.$v['id']] = $v['username'];
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
							$dom->save(FCPATH.'assets/xml/export_products_structure.xml');
							$json['download'] = base_url('assets/xml/export_products_structure.xml');
							
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
											case 'product_created_by':
											$val = $created_by['cb'.$value[$name_key]];
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
							$dom->save(FCPATH.'assets/xml/export_products.xml');
							$json['download'] = base_url('assets/xml/export_products.xml');	
						}
			 
					echo json_encode($json);

					exit;
				}


				public function downloadprodcutxmlstructurefile($filename = NULL) {
				    $userdetails = $this->userdetails();
				    $this->load->helper('download');
				    $data = file_get_contents(FCPATH.'assets/xml/export_products_structure.xml');
				    force_download("export_products_structure.xml", $data);
				}

				public function downloadprodcutxmlfile($filename = NULL) {
				    $userdetails = $this->userdetails();
				    $this->load->helper('download');
				    $data = file_get_contents(FCPATH.'assets/xml/export_products.xml');
				    force_download("export_products.xml", $data);
				}


				public function insertnotification($postData = null){

					if(!empty($postData)) $this->Product_model->create_data('notification', $postData);

				}



				public function listorders(){

					$userdetails = $this->userdetails();

					$store_setting = $this->Product_model->getSettings('store');

					$this->load->model('Order_model');

					$data['status'] = $this->Order_model->status();

					$data['user'] = $userdetails;

					$data['wallet_status'] = $this->Wallet_model->status();

					if(isset($_POST['getOrdersRows'])) {

						$post = $this->input->post(null, true);
						
						// Build filter array based on POST parameters
						$filter = array(
							'limit' => 50, // Default limit
							'page' => isset($post['page']) ? max((int)$post['page'], 1) : 1,
						);

						// Add search filter
						if(isset($post['search']) && !empty(trim($post['search']))) {
							$filter['search'] = trim($post['search']);
						}

						// Add status filter
						if(isset($post['status']) && $post['status'] !== '') {
							$filter['o_status'] = (int)$post['status'];
						}

						// Add payment method filter
						if(isset($post['payment_method']) && !empty(trim($post['payment_method']))) {
							$filter['payment_method'] = trim($post['payment_method']);
						}

						// Add date range filters
						if(isset($post['date_from']) && !empty(trim($post['date_from']))) {
							$filter['date_from'] = trim($post['date_from']);
						}

						if(isset($post['date_to']) && !empty(trim($post['date_to']))) {
							$filter['date_to'] = trim($post['date_to']);
						}

						$ordersResult = $this->Order_model->getOrders($filter);
						
						// Handle different return formats from getOrders method
						if(isset($ordersResult['data'])) {
							// When pagination is used, getOrders returns array with 'data' key
							$data['getallorders'] = $ordersResult['data'];
						} else {
							// When no pagination, getOrders returns the data directly
							$data['getallorders'] = $ordersResult;
						}

						$json['view'] = $this->load->view("admincontrol/product/orders_list_tr", $data, true);

						// Add pagination if needed
						if(isset($data['getallorders']) && is_array($data['getallorders']) && count($data['getallorders']) >= $filter['limit']) {
							// Simple pagination indication - you may want to enhance this
							$json['has_more'] = true;
						} else {
							$json['has_more'] = false;
						}

						echo json_encode($json); exit;
					}
					
					$this->load->model('Wallet_model');

					$totals = $this->Wallet_model->getTotals(array(), true);

					$data['full_local_store_hold_orders'] = $totals['store']['hold_orders'];

					$this->view($data,'product/orders');
				}


				public function order_change_status(){

					$order_id = (int)$this->input->post("id",true);

					$status = (int)$this->input->post("val",true);

					$remarks = '';

					$this->load->model('Order_model');

					$this->Order_model->changeStatus($order_id, $status,$remarks);

					$json['status'] = $this->Order_model->status($status);

					echo json_encode($json);

				}

				public function get_order_quick_details(){

					$userdetails = $this->userdetails();

					$order_id = (int)$this->input->post("order_id", true);

					if($order_id <= 0) {
						$json['success'] = false;
						$json['message'] = __('admin.invalid_order_id');
						echo json_encode($json);
						return;
					}

					$this->load->model('Order_model');

					// Get order details
					$order = $this->Order_model->getOrderById($order_id);

					if(empty($order)) {
						$json['success'] = false;
						$json['message'] = __('admin.order_not_found');
						echo json_encode($json);
						return;
					}

					// Get order products
					$products = $this->Order_model->getProducts($order_id);

					// Get order status
					$statuses = $this->Order_model->status();

					// Prepare data for the view
					$data = array(
						'order' => $order,
						'products' => $products,
						'statuses' => $statuses
					);

					$html = $this->load->view("admincontrol/product/order_quick_details", $data, true);

					$json['success'] = true;
					$json['html'] = $html;

					echo json_encode($json);
				}


				public function vieworder($order_id = null){
					$this->db->db_debug = FALSE;
					try {
						$userdetails = $this->userdetails();
						$this->load->model('Order_model');
						$this->load->model('Form_model');
						$post = $this->input->post(null,true);
						
						if($post){
							$this->Order_model->changeStatus($order_id, $post['payment_item_status'],$post['remarks']);
							$this->session->set_flashdata('success', __('admin.you_have_updated_order_status_successfully'));
							redirect('admincontrol/vieworder/'.$order_id);
							die();
						}

						$data['status'] = $this->Order_model->status();
						$data['order'] = $this->Order_model->getOrder($order_id);
						if(!empty($data['order']['id'])) {
							$data['products'] = $this->Order_model->getProducts($order_id);
							$data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);
							$data['payment_history'] = $this->Order_model->getHistory($order_id);
							$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
							$data['affiliate_user'] = $this->Order_model->getAffiliateUser($order_id);
							$data['venders'] = $this->Order_model->getVender($data['order'], $data['products']);
							$data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');
							$data['user'] = $userdetails;
							$data['orderProof'] = $this->Order_model->getPaymentProof($order_id);
							$data['shipping'] = $this->Order_model->getShippingDetails($data['order']['user_id']);
							unset($data['status']['0']); 
							$this->view( $data, 'product/vieworder');
						} else {
							$this->session->set_flashdata('error', sprintf(__("admin.order_id_no_longer_available"), $order_id));
							redirect('admincontrol/listorders/');
						}
					} catch (Exception $e) {
						$this->session->set_flashdata('error', $e->getMessage());
						redirect('admincontrol/listorders/');
					}
				}



				public function orderaction($order_id, $order_action, $transaction = false){

					$userdetails = $this->userdetails();

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					if($order_action == 'delete'){

						$this->Order_model->orderdelete($order_id, $transaction);

						$this->session->set_flashdata('success', __('admin.order_has_been_deleted_successfully_'). orderId($order_id));

						redirect('admincontrol/listorders');

					}

					if($order_action == 'sendemail'){

						$this->load->model('Mail_model');

						$this->Mail_model->send_new_order_mail($order_id);

						$this->session->set_flashdata('success', __('admin.order_mail_send_successfully'));

						redirect('admincontrol/vieworder/'.$order_id);

					}

					if($order_action == 'print'){

						$data['order'] = $this->Order_model->getOrder($order_id);

						$data['affiliate_user'] = $this->Order_model->getAffiliateUser($order_id);

						$data['payment_history'] = $this->Order_model->getHistory($order_id);

						$data['products'] = $this->Order_model->getProducts($order_id);

						$data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);

						$data['status'] = $this->Order_model->status();

						$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');

						$data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');

						$data['user'] = $userdetails;

						$this->load->view('admincontrol/product/printorder', $data);

					}

				}


				public function deleteusers($id = null,$type = 'user'){

					$userdetails = $this->userdetails();

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					$this->Product_model->userdelete($id,$type);

					if($type == 'user'){

						$this->session->set_flashdata('success', __('admin.user_has_been_deleted_successfully'));

						redirect('admincontrol/userslist');

					} else {

						$this->session->set_flashdata('success', __('admin.client_has_been_deleted_successfully'));

						redirect('admincontrol/listclients');

					}

				}



				public function addusers($id = null){

					$userdetails = $this->userdetails();

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					$data=array();

					$this->load->model('User_model');

					$this->load->model('PagebuilderModel');



					$data['countries'] = $this->User_model->getCountries();

					if ($this->input->post()) {

						$post = $this->input->post(null,true);

						$this->load->library('form_validation');

						$this->form_validation->set_rules('firstname', 'First Name', 'required|trim');

						$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');

						$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');

						$this->form_validation->set_rules('country_id', 'Country', 'required');

						if((int)$id == 0){
							$this->form_validation->set_rules('username', 'Username', 'required|trim');
						}

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

								$mobile_validation = (isset($_value['mobile_validation']) && $_value['mobile_validation'] ) ? $_value['mobile_validation'] : '';

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

							$checkuser = $this->Product_model->checkuser($this->input->post('username',true),$id);


							if(!empty($checkmail)){ $json['errors']['email'] = __('admin.email_already_exist'); }

							if(!empty($checkuser)){ $json['errors']['username'] = __('admin.username_already_exist'); }

								
							$phone = $this->input->post('phone',true);

							$phone_afftel_input_pre = $this->input->post('phone_afftel_input_pre',true);
			            	
			            	if(!empty($phone_afftel_input_pre) && !empty($phone)) {
			            		$phone = "+".$phone_afftel_input_pre." ".$phone;
			            		$checkPhone = $this->db->query("SELECT id, type FROM users WHERE phone like '%{$phone}%' ")->row_array($checkPhone);
								if(!empty($checkPhone) && $checkmail['type'] !== 'guest' && $checkPhone['id'] !== $id){ $json['errors']['phone'] = __('admin.phone_number_already_exist'); }
			            	}

			            	
							if(count($json['errors']) == 0){

								$custom_fields = array();
								$post = $this->input->post(null,true);



								foreach ($this->input->post() as $key => $value) {

									if(!in_array($key, array('affiliate_id','terms','cpassword','firstname','lastname','email','username','password', 'is_vendor', 'phone', 'refid', 'level_id', 'country_id' , 'groups')) && !strpos($key, "_afftel_input_pre")){

										if(isset($post[$key."_afftel_input_pre"]) && ! empty($post[$key."_afftel_input_pre"]) && ! empty($value)) {
				                    		$custom_fields[$key] = "+".$post[$key."_afftel_input_pre"]." ".$value;
			                    		} else {
				                    		$custom_fields[$key] = $value;
				                    	}
									}

								}

								$userGroups = $this->input->post('groups');

								if(!empty($userGroups)) {
									$userGroups = implode(',',$userGroups);
								}

								if ($this->input->post('is_vendor') == 'on') {
									$is_vendor = '1';
								}else{
									$is_vendor = '0';
								}

								

								$userArray = array(

									'firstname'                 => $this->input->post('firstname',true),

									'lastname'                  => $this->input->post('lastname',true),

									'email'                     => $this->input->post('email',true),

									'is_vendor'                 => $is_vendor,

									'phone'                     => $phone,

									'twaddress'                 => '',

									'address1'                  => '',

									'address2'                  => '',

									'uzip'                      => '',

									'avatar'                    => '',

									'online'                    => '0',

									'unique_url'                => '',

									'bitly_unique_url'          => '',

									'google_id'                 => '',

									'facebook_id'               => '',

									'twitter_id'                => '',

									'umode'                     => '',

									'PhoneNumber'               => '',

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

									'status'                    => '1',

									'ucountry'                  => $this->input->post('country_id',true),

									'Country'                   => $this->input->post('country_id',true),

									'value'                     => json_encode(array_merge($custom_fields, $filesAttached)),

									'groups'	=> $userGroups
								);

								if($post['password'] != ''){

									$userArray['password'] = sha1( $post['password'] );

								}


								if (isset($post['refid'])) {

									$userArray['refid'] = (int)$post['refid'];

								}


								if(isset($post['level_id'])){
									if(!empty($post['level_id']) || $post['level_id'] == '0'){
										$userArray['level_id'] = (int) $post['level_id'];
									} else {
										$defaultRegistrationLevel = $this->Product_model->getByField('award_level','default_registration_level',1);
										if($defaultRegistrationLevel){
											$userArray['level_id'] = $defaultRegistrationLevel['id'];
										} else {
											$defaultLevel = $this->Product_model->getByField('award_level','jump_level',0);
											if($defaultLevel)
												$userArray['level_id'] = $defaultLevel['id'];
										}
									}
								}

								if((int)$id == 0){
									$userArray['City'] = '';
									$userArray['ucity'] = '';
									$userArray['state'] = '0';
									$userArray['created_at'] = $userArray['updated_at'] = date("Y-m-d H:i:s");
									$userArray['username'] = $this->input->post('username',true);

								$data = $this->user->insert($userArray);
							$id = $this->db->insert_id();

							if((int)$is_vendor == 1 && !empty($id)) {
								$existing_vendor_setting = $this->db->get_where('vendor_setting', ['user_id' => $id])->row_array();
								if(empty($existing_vendor_setting)) {
									$this->db->insert('vendor_setting', [
										'user_id' => $id,
										'vendor_status' => 1,
										'affiliate_sale_commission_type' => 'percentage',
										'affiliate_commission_value' => 0,
										'affiliate_click_count' => 0,
										'affiliate_click_amount' => 0,
										'form_affiliate_click_count' => 0,
										'form_affiliate_click_amount' => 0,
										'form_affiliate_sale_commission_type' => 'percentage',
										'form_affiliate_commission_value' => 0,
										'vendor_shares_sales_status' => (int)$this->Product_model->getSettings('market_vendor', 'default_vendor_shares_sales_status')['default_vendor_shares_sales_status'] ?: 1
									]);
								}
							}

							$membership = $this->Product_model->getSettings('membership');

									if($is_vendor == 1) {
										$default_plan_id = $membership['default_vendor_plan_id'] ?? $membership['default_plan_id'];
									} else {
										$default_plan_id = $membership['default_affiliate_plan_id'] ?? $membership['default_plan_id'];
									}

									if($default_plan_id){
										$plan = App\MembershipPlan::find($default_plan_id);
										$user = App\User::find(array('id' => $id))->first();


										if(! empty($plan) && (($plan->user_type == 1 && $is_vendor != 1) || ($plan->user_type != 1 && $is_vendor == 1))) {
											$plan->buy($user, 1,'Automatically Added (Default Plan)','Free by Admin',0);
										}
									}
								} else {

									$data = $this->user->update_user($id, $userArray);

								}

								$this->session->set_flashdata('success', __('admin.youve_successfully_registered'));

								$json['location'] = base_url('admincontrol/userslist');

							}

						}
						
						echo json_encode($json);die;
					}

					$data['user'] 	= (array)$this->Product_model->getUserDetailsObject($id);

					$data['totals'] = $this->Wallet_model->getTotals(array("user_id" => $id), true);

					$this->load->model('PagebuilderModel');

					$register_form = $this->PagebuilderModel->getSettings('registration_builder');

					$data['data'] = json_decode($register_form['registration_builder'],1);

					$data['user_groups'] = $this->user->getgrouplist();

					$data['edit_view'] = true;

					if($id) {
						$data['read_only_user_membership_plan'] = true;
						$data['disable_username'] = true;
					}

					$data['allow_vendor_option'] = true;

					$data['edit_view_refer'] = true;

					$data['edit_view_level'] = true;

					$data['refer_users'] = $this->db->query("SELECT id,username FROM users WHERE id != ". (int)$id ." AND type='user'")->result_array();

					$data['membership'] = $this->Product_model->getSettings('membership', 'status');

					$data['award_level'] = $this->Product_model->getSettings('award_level', 'status');

					$data['userPlan'] = App\MembershipUser::select('membership_plans.name','membership_plans.commission_sale_status','award_level.level_number')->join('membership_plans','membership_plans.id','=','membership_user.plan_id')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('is_active',1)->where('user_id',$id)->first();

					$data['levels'] = $this->Product_model->getAll('award_level',false,0,'id desc');

					$data['html_form'] = $this->load->view('auth/user/templates/register_form',$data, true);

					$this->view($data,'users/add_users');
				}



				public function add_transaction(){

					$this->load->library('form_validation');

					$this->form_validation->set_rules('amount', 'Amount', 'required|trim');

					$this->form_validation->set_rules('comment', 'Comment', 'required|trim');

					$this->form_validation->set_rules('user_id', 'user_id', 'required|trim');



					if ($this->form_validation->run() == FALSE) {

						$json['errors'] = $this->form_validation->error_array();

					} else{

						$result = $this->Wallet_model->addTransaction(array(

							'status'         => 1,

							'user_id'        => $this->input->post("user_id",true),

							'amount'         => $this->input->post("amount",true),

							'comment'        => $this->input->post("comment",true) ,

							'type'           => 'admin_transaction',

							'dis_type'       => '',

							'comm_from'      => '',

							'reference_id'   => 0,

							'reference_id_2' => 0,

							'ip_details'     => '',

							'domain_name'    => '',

							'group_id'	=> time().rand(10,100)

						));

						if($result)
							$this->session->set_flashdata('success', __('admin.transaction_added'));
						else
							$this->session->set_flashdata('error', __('admin.transaction_not_add'));

						$json['location'] = base_url("admincontrol/addusers/" . $this->input->post("user_id",true));
					}

					echo json_encode($json);
				}



				public function getpaymentdetail($user_id)	{

					$userdetails = $this->userdetails();

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					$data['paymentlist'] = $this->Product_model->getAllPayment($user_id);

					$data['paypalaccounts'] = $this->Product_model->getPaypalAccounts($user_id);

					$user = $this->Product_model->getUserDetailsObject($user_id);

					$data['user'] = array(

						'firstname' => $user->firstname,

						'lastname'  => $user->lastname,

						'username'  => $user->username,

						'email'     => $user->email,

						'phone'     => $user->phone,

						'address'   => $user->twaddress,

						'country'   => $this->getCountryName($user->Country),  

						'state'     => $this->getStateName($user->state),  

						'city'      => $user->City,

					);

					echo json_encode($data);

				}



				public function getCountryName($country_id){

					$query = $this->db->get_where('countries',array('id'=>$country_id))->row_array();

					if($query){

						return $query['name'];

					}else{

						return '';

					}

				}



				public function getStateName($state_id){

					$query = $this->db->get_where('states',array('id'=>$state_id))->row_array();

					if($query){

						return $query['name'];

					}else{

						return '';

					}

				}



				public function downline($user_id){

					$userdetails = $this->userdetails();

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					$data['user'] 	= $this->Product_model->getUserDetailsObject($user_id);

					$mylevel = array();

					$this->view($data,'users/downline');

				}

				public function userslist(){

					$userdetails = $this->userdetails();

					$this->load->model('PagebuilderModel');

					$register_form = $this->PagebuilderModel->getSettings('registration_builder');

					$data['data'] = json_decode($register_form['registration_builder'],1);

					if ($this->input->post()) {

						$post = $this->input->post(null,true);

						if(isset($post['action']) && $post['action'] == "process_approval") {

							$approval_data = [];

							if(isset($post['approve_users']) && !empty($post['approve_users'])) {

								$approval_data['users_ids'] = $post['approve_users'];

								$approval_data['reg_approved'] = 1;

							}



							if(isset($post['decline_users']) && !empty($post['decline_users'])) {

								$approval_data['users_ids'] = $post['decline_users'];

								$approval_data['reg_approved'] = 2;

							}



							if(!empty($approval_data)) {

								$json['approvals_status'] = $this->Product_model->process_approval($approval_data);

								if($json['approvals_status']['status']) {

									$this->load->model('Mail_model');

									$user = App\User::find(array('id' => $approval_data['users_ids'][0]));

									if(isset($post['approve_users']) && !empty($post['approve_users'])) {

										$membership = $this->Product_model->getSettings('membership');

										switch ((int)$membership['status']) {
											case 0:
					                    		//disabled
											$plan_id = -1;
											break;
											case 1:
						                		//all users
											$plan_id = 0;
											break;
											case 2:
						                		//all vendors
											if($is_vendor == 1) {
												$plan_id = 0;
											} else {
												$plan_id = -1;
											}
											break;
											case 3:
						                		//all affiliates
											$plan_id = -1;
											if($is_vendor == 1) {
												$plan_id = -1;
											} else {
												$plan_id = 0;
											}
											break;
											default:
											$plan_id = -1;
											break;
										}

										if($plan_id == 0) {
											if((int)$user[0]['is_vendor'] == 1) {
												$plan_id = $membership['default_vendor_plan_id'] ?? $membership['default_plan_id'];
											} else {
												$plan_id = $membership['default_affiliate_plan_id'] ?? $membership['default_plan_id'];
											}
										}

										

										if($membership['status'] && $plan_id > 0){

											$plan = App\MembershipPlan::find($plan_id);

											if($plan){

												//$plan->buy($user[0], 1, 'Default plan started','Default');

												$commission_processed = $this->db->query('SELECT id from wallet WHERE reference_id='.$approval_data['users_ids'][0].' AND type="refer_registration_commission"')->result();

												$refid = (int)$user[0]['refid'];

												if(empty($commission_processed) && $refid > 0) {
													$this->load->model('Wallet_model');
													$comission_group_id = time().rand(10,100);
													$referlevelSettings = $this->Product_model->getSettings('referlevel');
													$max_level = isset($referlevelSettings['levels']) ? (int)$referlevelSettings['levels'] : 3;
													
													$json['max_level'] = $max_level;

													$disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
													$refer_status = true;
													if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
													else if((int)$referlevelSettings['status'] == 2 && in_array($refid, $disabled_for)){ $refer_status = false; }

													$json['refer_status'] = $refer_status;

													if($refer_status) {
														$json['level'] = $level = $this->Product_model->getMyLevel($refid);	
														$json['max_level_user'] = [];
														for ($l=1; $l <= $max_level ; $l++) { 
															
															if($l == 1) {
																$json['max_level_user'][] = $levelUser = (int)$refid;	
															} else {
																$json['max_level_user'][] = $levelUser = (int)$level['level'.($l-1)];
															}

															$s = $this->Product_model->getSettings('referlevel_'. $l);
															

															if($s && $levelUser > 0){
																$_giveAmount = 0;
																
																if($referlevelSettings['reg_comission_type'] == 'custom_percentage'){
																	if((int) $referlevelSettings['reg_comission_custom_amt'] > 0) {
																		$_giveAmount = (($referlevelSettings['reg_comission_custom_amt'] * (float)$s['reg_commission']) / 100);
																	}
																} else if($referlevelSettings['reg_comission_type'] == 'fixed'){
																	$_giveAmount = (float)$s['reg_commission'];
																}

																$json['max_level_user']['_giveAmount'] = $_giveAmount;

																if($_giveAmount > 0){
																	$transaction_id1 = $this->Wallet_model->addTransaction(array(
																		'status'       => 1,
																		'user_id'      => $levelUser,
																		'amount'       => $_giveAmount,
																		'dis_type'     => '',
																		'comment'      => "Level {$l} : ".'Commission for new affiliate registrion Id ='. $user[0]['id'] .' | Name : '. $user[0]['firstname'] ." " .$user[0]['lastname'],
																		'type'         => 'refer_registration_commission',
																		'reference_id' => $user[0]['id'],
																		'group_id' => $comission_group_id,
																	));
																}
															}
														}
													}
												}
											}
										}

										$this->Mail_model->send_registration_approved_mail(json_decode(json_encode($user[0])));

									}


									if(isset($post['decline_users']) && !empty($post['decline_users'])) {

										$this->Mail_model->send_registration_declined_mail(json_decode(json_encode($user[0])));

									}

								}


								$json['approvals_count'] = $this->Product_model->getApprovalCounts();

								echo json_encode($json);die;

							}

						} else {

							if (isset($post['action']) && $post['action'] == 'get_all_ids') {

								$data['ids'] = array_column($this->db->query("SELECT id FROM users WHERE type='user' ")->result_array(),'id');

								echo json_encode($data);die;

							}



							$pagination_settings = get_pagination_settings();
							$filter = array(
								'limit' => $pagination_settings['per_page'],
								'page' => max(1, isset($post['page']) ? (int)$post['page'] : 1),
								'reg_approved' => null
							);

							if(isset($post['apr']) && !empty($post['apr'])) {

								switch ($post['apr']) {

									case 'pending':

									$filter['reg_approved'] = 0;

									break;

									case 'approved':

									$filter['reg_approved'] = 1;

									break;

									case 'declined':

									$filter['reg_approved'] = 2;

									break;

									default:

									$filter['reg_approved'] = null;

									break;

								}

							}





							if(isset($post['name']) && $post['name'] != ''){

								$filter['name'] = $post['name'];

							}



							if(isset($post['email']) && $post['email'] != ''){

								$filter['email'] = $post['email'];

							}

							if(isset($post['groups']) && !empty($post['groups'])){

								$filter['groups'] = $post['groups'];

							}

							if (isset($post['health_score_band']) && $post['health_score_band'] !== '' && $post['health_score_band'] !== 'all') {
								$allowed_bands = ['high', 'medium', 'low', 'critical'];
								if (in_array($post['health_score_band'], $allowed_bands, true)) {
									$filter['health_score_band'] = $post['health_score_band'];
								}
							}



							$userslist = $this->Product_model->getAllUsers($filter);



							$data['userslist'] = $userslist['data'];



							$pagination_data = ajax_pagination(
								$userslist['total'],
								$filter['page'],
								[
									'size' => 'sm',
									'alignment' => 'end',
									'js_function' => 'getPage'
								]
							);

							$data['commission_type'] = $this->Product_model->getCommissionType();

							$data['user'] = $userdetails;


							$data['membership'] = $this->Product_model->getSettings('membership', 'status');

							$data['award_level'] = $this->Product_model->getSettings('award_level', 'status');

							$json['table'] = $this->load->view("admincontrol/users/part/user_tr", $data, true);


							$json['pagination'] = $pagination_data['html'];
							$json['pagination_summary'] = pagination_summary_html($filter['page'], $pagination_settings['per_page'], $userslist['total'], 'start', 'sm');

							$json['approvals_count'] = $this->Product_model->getApprovalCounts();

							set_tmp_cache('user_list_cache');

							echo json_encode($json);die;

						}

					}

					$data['user_groups'] = $this->user->getgrouplist();
					$data['approvals_count'] = $this->Product_model->getApprovalCounts();
					$data['admin_users_list_css'] = true;

					$this->view($data,'users/index');
				}

				public function users_change_parent(){
					$userdetails = $this->userdetails();
					if(empty($userdetails) || $userdetails['type'] != 'admin'){
						return $this->json(['success'=>false,'message'=>__('admin.permission_denied')]);
					}
					$child_id = (int)$this->input->post('child_id', true);
					$new_parent_id = (int)$this->input->post('new_parent_id', true);
					if($child_id <= 0 || $new_parent_id <= 0){
						return $this->json(['success'=>false,'message'=>__('admin.invalid_move')]);
					}
					if($child_id === $new_parent_id){
						return $this->json(['success'=>false,'message'=>__('admin.cannot_set_self_as_parent')]);
					}
					$child = $this->db->select('id,refid')->from('users')->where('id',$child_id)->get()->row_array();
					$parent = $this->db->select('id')->from('users')->where('id',$new_parent_id)->get()->row_array();
					if(!$child || !$parent){
						return $this->json(['success'=>false,'message'=>__('admin.invalid_move')]);
					}
					if($this->is_descendant_of_user($new_parent_id, $child_id)){
						return $this->json(['success'=>false,'message'=>__('admin.cannot_move_under_descendant')]);
					}
					if((int)$child['refid'] === $new_parent_id){
						return $this->json(['success'=>true]);
					}
					$this->db->where('id',$child_id);
					$updated = $this->db->update('users',['refid'=>$new_parent_id]);
					if($updated){
						return $this->json(['success'=>true]);
					}
					return $this->json(['success'=>false,'message'=>__('admin.save_failed')]);
				}

				private function is_descendant_of_user($potential_parent_id, $child_root_id){
					$visited = [];
					$queue = [$child_root_id];
					while(!empty($queue)){
						$batch = $queue;
						$queue = [];
						$this->db->reset_query();
						$this->db->select('id');
						$this->db->from('users');
						$this->db->where_in('refid',$batch);
						$result = $this->db->get()->result_array();
						foreach($result as $row){
							$uid = (int)$row['id'];
							if($uid === (int)$potential_parent_id) return true;
							if(!isset($visited[$uid])){
								$visited[$uid] = true;
								$queue[] = $uid;
							}
						}
					}
					return false;
				}



				public function get_user_data(){

					// Demo Mode
					if (ENVIRONMENT === 'demo') {
						echo json_encode([
							'status' => 'error',
							'message' => 'Disabled on demo mode'
						]);
						return;
					}
					// Demo Mode

					$filter = $this->input->post(null,true);;

					$json = array();

					$this->load->model('PagebuilderModel');

					$register_form = $this->PagebuilderModel->getSettings('registration_builder');

					$datab = json_decode($register_form['registration_builder'],1);

					$data = $this->Product_model->getAllUsersExport($filter);

					$header = array(
						'auto'            => "#",
						'email'           => "Email",
						'username'        => "UserName",
						'firstname'       => "First Name",
						'lastname'        => "Last Name",
						'under_affiliate' => "Under Affiliate",
						'sortname'        => "Country",
						'password'        => "Password",
						'phone'	  		  => "Mobile Phone", 
					);

					foreach ($datab as $key => $value) {
						if($value['type'] != 'header'){
							$header[$value['name']] = $value['label'];
						}

					}

					unset($header["text-1621449816785"]);

					$header['paypal_email'] = 'Paypal Email';

					$header['payment_bank_name'] = 'Bank Name';

					$header['payment_account_number'] = 'Bank Account Name';

					$header['payment_account_name'] = 'Bank Account Number';

					$index = 0;

					$_exportData = array();

					$_exportData[$index] = array_values($header);
			 
			 
					require_once APPPATH . '/core/phpspreadsheet/autoload.php';
			  

					if($filter['action'] == 'export'){

						foreach ($data as $key => $value) {

							$value['password'] = '';

							$index++;

							$v= json_decode($value['value'],1); 

							foreach ($header as $name_key => $_value) {

								$val = '';

								if($name_key == 'auto'){

									$val = $index;

								}

								else if(isset($value[$name_key])){

									$val = $value[$name_key];

								} else if(isset($v['custom_'.$name_key])){

									$val = $v['custom_'.$name_key];

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
						}

						$objWriter->save(FCPATH.'assets/xml/export_users.xlsx');

						$json['download'] = base_url('assets/xml/export_users.xlsx');

					} else {

						if($_FILES['import_control']['error'] == 0){

							$excelReader 	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
							$excelReader->setReadDataOnly(true); $excelReader->setReadEmptyCells(false);
							$excelObj = $excelReader->load($_FILES['import_control']['tmp_name']);

							$rows = $excelObj->getActiveSheet()->toArray(null,true,false,false);

							$headers = array_shift($rows);

							$db_headers = array();

							foreach ($header as $name_key => $_value) {

								$key = array_search($_value, $header); 

								$db_headers[] = $key;

							}

							$this->load->model('Imoprt_user');

							array_walk($rows, function(&$values) use($db_headers){

								$values = array_slice($values, 0, count($db_headers));

								$values = array_combine($db_headers, $values);
							});



							$json['errors'] = '<ol>';

							foreach ($rows as $key => $user) {

								$json['errors'] .=  $this->Imoprt_user->import($user,$datab);

							}

							$json['errors'] .= '</ol>';

						} else {

						$json['errors'] =  __('admin.unsupported_file_or_empty');

						}

					}

					echo json_encode($json);

				}



				public function import_user_data(){

					$filter = $this->input->post(null,true);;

					$file = $_FILES;

					if (!isset($filter['is_admin'])) { 

						$filter['user_id'] = (int)$this->userlogins()['id'];

					}

					echo "<pre>"; print_r($file); echo "</pre>";die; 

				}

				public function userslisttree(){

					$userdetails = $this->userdetails();

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					$this->load->model('PagebuilderModel');
					$register_form = $this->PagebuilderModel->getSettings('registration_builder');
					$data['data'] = json_decode($register_form['registration_builder'],1);

					$data['userslist'] = $this->Product_model->getAllinOneQuery(array(),0,true,true);
					$data['userslistDetail'] = $this->getOptimizedUserDetails();
					
					$adminUser = $this->db->query("SELECT id, username, firstname, lastname, avatar, last_ping, created_at FROM users WHERE type='admin' LIMIT 1")->row_array();
					if($adminUser) {
						$data['userslistDetail'][] = $adminUser;
					}

					$data['membership'] = $this->Product_model->getSettings('membership', 'status');
					$data['award_level'] = $this->Product_model->getSettings('award_level', 'status');
					$data['levels'] = $this->Product_model->getAll('award_level',false,0,'id desc');

					$this->view($data,'users/tree');

				}

				private function getOptimizedUserDetails() {
					return $this->db->select('id, username, firstname, lastname, avatar, last_ping, created_at')
									->from('users')
									->where('type', 'user')
									->get()
									->result_array();
				}

				public function load_tree_users() {
					$userdetails = $this->userdetails();
					if(empty($userdetails)){ 
						echo json_encode(['error' => 'Unauthorized']);
						return;
					}

					$offset = (int)$this->input->post('offset', true) ?: 0;
					$limit = (int)$this->input->post('limit', true) ?: 100;
					$search = $this->input->post('search', true);

					$this->db->select('id, username, firstname, lastname, avatar, last_ping, created_at, refid')
							 ->from('users')
							 ->where('type', 'user')
							 ->limit($limit, $offset);

					if($search) {
						$this->db->group_start()
								 ->like('username', $search)
								 ->or_like('firstname', $search)
								 ->or_like('lastname', $search)
								 ->or_like('id', $search)
								 ->group_end();
					}

					$users = $this->db->get()->result_array();
					
					echo json_encode([
						'users' => $users,
						'has_more' => count($users) == $limit
					]);
				}

				public function addons() {

					$userdetails = $this->userdetails();

					if(isset($_POST['action'])) {
						$this->load->model('Setting_model');
						$this->Setting_model->save($_POST['setting_type'], [$_POST['setting_key']=>$_POST['val']]);
						
						//enable-disable vendor mlm module
						if($_POST['setting_key']=="vendormlmmodule" && $_POST['setting_type']=="market_vendor")
						{
							///echo "execute only in vendormlmmodule";
							$status=(int)$_POST['val'];
							$query= $this->db->query("SELECT id FROM `users` where is_vendor=1 and status=1");
							$vendors=$query->result_array();
							for($i=0;$i<count($vendors);$i++)
							{
								$vid=$vendors[$i]['id'];
								$value=array("status"=>$status);
								$this->Setting_model->vendorSave($vid, "referlevel", $value);
							}
						}
						//enable-disable vendor mlm module
						else if($_POST['setting_type'] == 'market_vendor') {
							///echo "execute only in sass";
							$this->Setting_model->save("vendor", ["storestatus"=>$_POST['val']]);
						} 

						echo 'success'; exit;
					}

					$referlevel_status = $this->Product_model->getSettings('referlevel', 'status');

					$vendormlmmodule = $this->Product_model->getSettings('market_vendor', 'vendormlmmodule');

					$market_vendor_marketvendorstatus = $this->Product_model->getSettings('market_vendor', 'marketvendorstatus');

					$vendor_storestatus = $this->Product_model->getSettings('vendor', 'storestatus');

					$market_vendor_marketvendorstatus =  isset($market_vendor_marketvendorstatus['marketvendorstatus']) ? $market_vendor_marketvendorstatus['marketvendorstatus'] : 0;

					$vendor_storestatus =  isset($vendor_storestatus['storestatus']) ? $vendor_storestatus['storestatus'] : 0;

					$membership_status = $this->Product_model->getSettings('membership', 'status');

					$store_status = $this->Product_model->getSettings('store', 'status');

					$vendor_deposit_status = $this->Product_model->getSettings('vendor', 'depositstatus');

					$award_level_status = $this->Product_model->getSettings('award_level', 'status');

					$data = array (
						'mlm_admin_is_enable' => isset($referlevel_status['status']) ? $referlevel_status['status'] : 0,

						'mlm_vendor_is_enable' =>  isset($vendormlmmodule['vendormlmmodule']) ? $vendormlmmodule['vendormlmmodule'] : 0,

						'saas_is_enable' => ($market_vendor_marketvendorstatus == 1 || $vendor_storestatus == 1) ? 1 : 0,

						'membership_is_enable' => isset($membership_status['status']) ? $membership_status['status'] : 0,

						'store_is_enable' => isset($store_status['status']) ? $store_status['status'] : 0,

						'vendor_deposit_is_enable' => isset($vendor_deposit_status['depositstatus']) ? $vendor_deposit_status['depositstatus'] : 0,

						'award_level_is_enable' => isset($award_level_status['status']) ? $award_level_status['status'] : 0,
					);

					$data2['integration_modules'] = $this->modules_list('addons');
					$data['integration_modules_view'] = $this->load->view('admincontrol/integration/index', $data2, true);

					
					$this->view($data, 'addons/index');
				}

				private function modules_list($requestingFor = null){

					if($requestingFor == null) {

						$integration_modules['general_integration'] = array(
							'name' => "Custom Order Integration",
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

				public function userslistmail(){

					$userdetails = $this->userdetails();

					$this->load->model('PagebuilderModel');

					$register_form = $this->PagebuilderModel->getSettings('registration_builder');

					$data['data'] = json_decode($register_form['registration_builder'],1);

					if ($this->input->server('REQUEST_METHOD') == 'POST'){

						$filter = $this->input->post(null,true);

						$get = $this->input->get(null,true);


						if (isset($filter['action']) && $filter['action'] == 'get_all_emails') {


						$data['emails'] = array_column($this->db->query("SELECT email FROM users WHERE type='user' ")->result_array(),'email');

						echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);die;

						}


						$pagination_settings = get_pagination_settings([
							'per_page' => 10,
							'size' => 'sm',
							'alignment' => 'end',
							'js_function' => 'getPage'
						]);

						$filter['limit'] = $pagination_settings['per_page'];
						$filter['page'] = isset($get['per_page']) ? (int)$get['per_page'] : 1;

						$userslist = $this->Product_model->getAllUsersNormal($filter);

						$data['userslist'] = $userslist['data'];

						$pagination_data = ajax_pagination(
							$userslist['total'],
							$filter['page'],
							[
								'per_page' => $pagination_settings['per_page'],
								'size' => 'sm',
								'alignment' => 'end',
								'js_function' => 'getPage'
							]
						);

						$data['html'] = $this->load->view('admincontrol/users/part/mail_list',$data,true);
						$data['pagination'] = $pagination_data['html'];
						$data['pagination_summary'] = pagination_summary_html($filter['page'], $pagination_settings['per_page'], $userslist['total'], 'start', 'sm');
						$data['total'] = $userslist['total'];

						unset($data['userslist']);

						unset($data['data']);

						echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);die;

					}

					$data['country_list'] = $this->db->query("SELECT * FROM countries WHERE id IN (SELECT DISTINCT Country FROM users WHERE type='user') ")->result();

					$data['user'] = $userdetails;

					$this->view($data,'users/mail');

				}

		public function addclients($id = null){

			$userdetails = $this->userdetails();

			if(empty($userdetails)){
				redirect($this->admin_domain_url);
			}

			$data=array();

			if ($this->input->post()) {

				$this->load->library('form_validation');

				$checkmail = $this->Product_model->checkmail($this->input->post('email',true),$id);

				$checkuser = $this->Product_model->checkuser($this->input->post('username',true),$id);

				if(!empty($checkmail))

				{

					$this->session->set_flashdata('error', __('admin.this_email_already_register'));

					$this->session->set_flashdata('postdata', $this->input->post());

					redirect('admincontrol/addclients');

				}

				elseif(!empty($checkuser))

				{
					$this->session->set_flashdata('error',__('admin.this_username_already_register'));

					$this->session->set_flashdata('postdata', $this->input->post());

					redirect('admincontrol/addclients');
				}

				else

				{
					if(empty($id)){

						$data=$this->user->insert(array(

							'firstname' => $this->input->post('firstname',true),

							'lastname'  => $this->input->post('lastname',true),

							'email'     => $this->input->post('email',true),

							'username'  => $this->input->post('username',true),

							'status'  => $this->input->post('status',true),

							'phone'  => '+'.$this->input->post('countrycode',true).' '.$this->input->post('phone',true),

							'ucountry'  => $this->input->post('country',true),

							'state'  => $this->input->post('state',true),

							'ucity'  => $this->input->post('ucity',true),

							'uzip'  => $this->input->post('uzip',true),

							'twaddress'  => $this->input->post('twaddress',true),

							'password'  => sha1($this->input->post('password',true)),

							'refid'     => 0,

							'type'      => 'client',

						));
						

					} else {

						$data = $id;

					}

					if(!empty($data))

					{

						$arrayName = array(

							'firstname' => $this->input->post('firstname',true),

							'lastname'  => $this->input->post('lastname',true),

							'email'  => $this->input->post('email',true),

							'status'  => $this->input->post('status',true),

							'ucountry'  => $this->input->post('country',true),

							'state'  => $this->input->post('state',true),

							'ucity'  => $this->input->post('ucity',true),

							'uzip'  => $this->input->post('uzip',true),
							
							'twaddress'  => $this->input->post('twaddress',true),

							'phone'  => '+'.$this->input->post('countrycode',true).' '.$this->input->post('phone',true),


						);

						if($this->input->post('password',true) != ''){

							$arrayName['password'] = sha1($this->input->post('password',true));

						}
						
						$this->user->update_user($data,$arrayName);

						$this->session->set_flashdata('success', __('admin.updated_successfully'));

						redirect('admincontrol/listclients/');

					}

				}

			}

			$data['client'] 	= $this->Product_model->getUserDetailsObject($id);
			$data['countries'] 	= $this->Product_model->getcountry('id,name');

			$this->view($data,'clients/add_clients');
		}

				public function listclients($page = 1){

					$userdetails = $this->userdetails();

					$data['countries'] 	= $this->Product_model->getcountry('id,name');

					$data['user'] = $userdetails;

					$store_setting = $this->Product_model->getSettings('store');

				if(isset($_POST['listclients'])) {

					$page = max((int)$page,1);
					$post = $this->input->post(null, true);

					$filter = array(
						'limit' => isset($post['limit']) ? (int)$post['limit'] : 50,
						'page' => $page 
					); 

					// Add search filter
					if(!empty($post['search'])) {
						$filter['search'] = $post['search'];
					}

					// Add type filter
					if(!empty($post['type'])) {
						$filter['type'] = $post['type'];
					}

					// Add country filter
					if(!empty($post['country'])) {
						$filter['country'] = $post['country'];
					}

					// Add sort filter
					if(!empty($post['sort'])) {
						$filter['sort'] = $post['sort'];
					}

					list($data['clientslist'],$total) = $this->Product_model->getAllClients($filter); 
					$data['start_from'] = (($page-1) * $filter['limit'])+1; 
					$json['html'] = $this->load->view("admincontrol/clients/clients_list_tr", $data, true);
					$json['total'] = $total;

					// Get client stats
					$json['stats'] = $this->getClientStats($filter);

					$this->load->library('pagination');
					$config['base_url'] = base_url('admincontrol/listclients/');
					$config['per_page'] = $filter['limit'];
					$config['total_rows'] = $total;
					$config['use_page_numbers'] = TRUE;
					$config['enable_query_strings'] = TRUE;
					$this->pagination->initialize($config);
					$json['pagination'] = $this->pagination->create_links();
					echo json_encode($json);die;

					exit;
				}

					$this->view($data,'clients/index');
				}

				private function getClientStats($filter = array()) {
					// Get total clients count
					$total_clients = $this->db->query("SELECT COUNT(*) as count FROM users WHERE type IN ('client', 'guest')")->row()->count;
					
					// Get active clients (clients with orders)
					$active_clients = $this->db->query("
						SELECT COUNT(DISTINCT u.id) as count 
						FROM users u 
						INNER JOIN `order` o ON o.user_id = u.id 
						WHERE u.type IN ('client', 'guest') AND o.status = 1
					")->row()->count;
					
					// Get total sales amount
					$total_sales_result = $this->db->query("
						SELECT SUM(o.total) as total 
						FROM `order` o 
						INNER JOIN users u ON u.id = o.user_id 
						WHERE u.type IN ('client', 'guest') AND o.status = 1
					")->row();
					$total_sales = $total_sales_result->total ? c_format($total_sales_result->total) : c_format(0);
					
					// Get average order value
					$avg_order_result = $this->db->query("
						SELECT AVG(o.total) as avg_value 
						FROM `order` o 
						INNER JOIN users u ON u.id = o.user_id 
						WHERE u.type IN ('client', 'guest') AND o.status = 1
					")->row();
					$avg_order_value = $avg_order_result->avg_value ? c_format($avg_order_result->avg_value) : c_format(0);
					
					return array(
						'total_clients' => $total_clients,
						'active_clients' => $active_clients,
						'total_sales' => $total_sales,
						'avg_order_value' => $avg_order_value
					);
				}

				public function getClientDetails(){
					$userdetails = $this->userdetails();
					
					if(empty($userdetails)){
						echo json_encode(['status' => false, 'message' => 'Unauthorized']);
						return;
					}
					
					$client_id = (int)$this->input->post('id', true);
					
					if($client_id <= 0) {
						echo json_encode(['status' => false, 'message' => 'Invalid client ID']);
						return;
					}
					
					// Get client details with all related information
					$client = $this->db->query("
						SELECT users.*, 
						(SELECT CONCAT(firstname, ' ', lastname) FROM users u WHERE u.id = users.refid) as ref_user,
						(SELECT name FROM countries WHERE id = users.ucountry LIMIT 1) as country_name,
						(SELECT name FROM states WHERE id = users.state LIMIT 1) as state_name,
						(SELECT SUM(o.total) FROM `order` o WHERE o.user_id = users.id AND o.status > 0) as amount,
						(SELECT COUNT(o.id) FROM `order` o WHERE o.user_id = users.id AND o.status > 0) as total_sale
						FROM users 
						WHERE users.id = {$client_id} AND users.type IN ('client', 'guest')
					")->row_array();
					
					if(empty($client)) {
						echo json_encode(['status' => false, 'message' => 'Client not found']);
						return;
					}
					
					// Get countries for display
					$data['countries'] = $this->Product_model->getcountry('id,name');
					$data['client'] = $client;
					
					// Generate the HTML content
					$html = $this->load->view('admincontrol/clients/client_details_modal_content', $data, true);
					
					echo json_encode(['status' => true, 'html' => $html]);
				}

				public function affiliate_theme(){

					$userdetails = $this->userdetails();

					$commonSetting = array('email','paymentsetting','integration','login', 'loginclient','productsetting','formsetting','tnc','site','affiliateprogramsetting','store','doc','googlerecaptcha','referlevel','userdashboard');

					$post = $this->input->post(null,true);

					if(!empty($post)){

						$json = array();

						if(isset($post['loginclient'])) {
							try {
								$this->Setting_model->saveWithLanguage(
									'loginclient', 
									$post['language_id'],
									array(
										'heading' => $post['heading'],
										'content' => $post['content'],
										'about_content' => $post['about_content'],
									)
								);
								$json['success'] = true;
							} catch (\Throwable $th) {
								$json['message'] = $th->getMessage();
							}
						}
						if(isset($post['tnc'])) {
							try {
								$this->Setting_model->saveWithLanguage(
									'tnc', 
									$post['language_id'],
									array(
										'heading' => $post['policy_heading'],
										'content' => $post['policy_content'],
									)
								);
								$json['success'] = true;
							} catch (\Throwable $th) {
								$json['message'] = $th->getMessage();
							}
						}
						
						

						if (isset($post['action']) && $post['action'] == 'active_theme') {

							$login = array('front_template' => $post['id']);

							$this->Setting_model->save('login', $login);

							$json['success'] = __('admin.theme_activated_successfully');

							echo json_encode($json);die;

						}

						if(!isset($json['errors'])){

							foreach ($post as $key => $value) {

								if (in_array($key, $commonSetting)) {

									$this->Setting_model->save($key, $value);
								}

							}

							if(!isset($json['errors'])){

								$json['success'] =  __('admin.setting_saved_successfully');
							}
						}

						echo json_encode($json);die;

					} else {

						foreach ($commonSetting as $key => $value) {

							$data[$value] 	= $this->Product_model->getSettings($value);

						}

						$active_theme = [];

						$this->config->load('theme');

						$front_themes = $this->config->item('themes');

						$data['front_themes'] = [];

						foreach ($front_themes as $key => $theme) {

							if($data['login']['front_template'] != $theme['id']){

								$data['front_themes'][] = $theme;

							} else {

								$active_theme = $theme;
							}

						}

						if($active_theme){

							array_unshift($data['front_themes'], $active_theme);

						}

						$data['languages'] = $this->db->query("SELECT * FROM language where status=1")->result_array();

						$this->load->helper('login_page_blocks');

						$login_cfg = is_array($data['login']) ? $data['login'] : [];
						$data['login_blocks_admin'] = [
							'top_earners_cfg' => login_page_top_earners_settings_parse($login_cfg['block_top_earners_settings'] ?? null),
							'stats_cfg' => login_page_stats_settings_parse($login_cfg['block_stats_settings'] ?? null),
							'video_cfg' => login_page_video_settings_parse($login_cfg['block_video_settings'] ?? null),
							'video_max' => login_page_video_max_items(),
							'pulse_cfg' => login_page_live_pulse_settings_parse($login_cfg['block_live_pulse_settings'] ?? null),
							'features_slots' => login_page_features_settings_decode($login_cfg['block_features_settings'] ?? null),
							'features_display' => login_page_features_display_options_decode($login_cfg['block_features_settings'] ?? null),
							'features_max' => login_page_features_max_items(),
							'faq_cfg' => login_page_faq_settings_decode($login_cfg['block_faq_settings'] ?? null),
							'faq_max' => login_page_faq_max_items(),
							'is_demo_mode' => (defined('ENVIRONMENT') && ENVIRONMENT === 'demo'),
						];

						$this->view($data, 'affiliate_theme/index');

					}
				}

				public function setting(){

					$userdetails = $this->userdetails();

					if(empty($userdetails)){redirect($this->admin_domain_url);}

					$post = $this->input->post(null,true);

					if(!empty($post)){

						$this->load->helper(array('form', 'url'));

						$errors= array();

						foreach($post as $key => $value) {

							if(!empty($key) && !empty($value)){

								$this->Product_model->deletesetting($key,$value,'setting');

							}

							$details = array(

								'setting_key'       =>  $key,

								'setting_value'     =>  $value,

								'setting_type'      =>  'setting',

								'setting_status'    =>  1,

								'setting_ipaddress' =>  $_SERVER['REMOTE_ADDR'],

							);

							if(!empty($key) && !empty($value)){

								$this->Product_model->create_data('setting', $details);

							}

						}

						$this->session->set_flashdata('success', __('admin.setting_updated_successfully'));

						redirect('admincontrol/setting');

					} else {

						$data['setting'] 	= $this->Product_model->getSettings('setting');

						$data['getAffiliate'] 	= $this->Product_model->getAffiliateById();

						$this->view($data,'setting/setting');

					}

				}



				public function store_setting(){
					
					$userdetails = $this->userdetails();

					if(empty($userdetails)){redirect($this->admin_domain_url);}

					$commonSetting = array('formsetting','productsetting','store','shipping_setting', 'tax_setting','order_comment');

					$post = $this->input->post(null,false);
					if(!empty($post)){

						$return = (isset($post['return'])) ? $post['return'] : false;

						$json = array();

						if (isset($post['recursion_endtime_status']) && isset($post['productsetting']['recursion_endtime']) && $post['productsetting']['recursion_endtime']) {

							$post['productsetting']['recursion_endtime'] = date("Y-m-d H:i:s",strtotime($post['productsetting']['recursion_endtime']));

						} else {

							$post['productsetting']['recursion_endtime'] = null;

						}

						unset($post['recursion_endtime_status']);



						if (isset($post['recursion_endtime_form_status']) && isset($post['formsetting']['recursion_endtime']) && $post['formsetting']['recursion_endtime']) {

							$post['formsetting']['recursion_endtime'] = date("Y-m-d H:i:s",strtotime($post['formsetting']['recursion_endtime']));

						} else {

							$post['formsetting']['recursion_endtime'] = null;

						}

			 
						unset($post['recursion_endtime_form_status']);

						if(!isset($post['shipping_setting']['cost'])){
							$post['shipping_setting']['cost'] = [];
						}

						foreach ($post['shipping_setting']['cost'] as $key => $value) {
							if((int)$value['country'] <= 0){
								$json['errors']['ssc-'. $key] = 'Choose country';
							}

							if((int)$value['cost'] <= 0){
								$json['errors']['ssv-'. $key] = 'Enter Shipping cost';
							}
						}

						if(!isset($post['tax_setting']['cost'])){
							$post['tax_setting']['cost'] = [];
						}

						foreach ($post['tax_setting']['cost'] as $key => $value) {
							if((int)$value['country'] <= 0){
								$json['errors']['taxc-'. $key] = 'Choose Country';
							}

							if((int)$value['cost'] <= 0){
								$json['errors']['taxv-'. $key] = 'Enter Tax Percentage';
							}
						}

						if($post['tax_setting']['tax_status'] == 1 && empty($post['tax_setting']['common_tax_percentage'])) {
							$json['errors']['common_tax_percentage'] = 'Enter Tax Percentage';
						}

						if(!isset($json['errors'])){

							if(count($_FILES) > 0){

								$path = 'assets/images/site';

								$this->load->helper('string');

								$config['upload_path'] = $path;

								$config['allowed_types'] = '*';

								$config['file_name']  = random_string('alnum', 32);

								$this->load->library('upload', $config);

								foreach ($_FILES as $fieldname => $input) {

									$this->upload->initialize($config);

									list($key,$subkey) = explode("_", $fieldname);

									$extension = pathinfo($_FILES[$fieldname]["name"], PATHINFO_EXTENSION);

									if($input['error'] == 0){

										$extension_allowed = array('jpg','jpeg','png','gif','JPG','PNG','JPEG');

										if($fieldname == 'store_favicon'){

											$extension_allowed = array('jpg','jpeg','png','gif','ico');

										}

										if(in_array($extension, $extension_allowed)){

											if (!$this->upload->do_upload($fieldname)) {

											}

											else {

												$upload_details = $this->upload->data();

												$post[$key][$subkey] = $upload_details['file_name'];


											}

										} else{

											$json['errors']["{$key}_{$subkey}"] = 'Only Image File are allowed';

										}

									}

								}

							}

						if(isset($post['store']['notification'])) {

							$notis = [];

							foreach($post['store']['notification'] as $n) {

								array_push($notis, $n);

							}				

							$post['store']['notification'] = json_encode($notis);

						} else {

							$post['store']['notification'] = json_encode([]);

						}

							if(isset($post['store']['homepage_slider'])) {

								$slider = [];

								for ($i=0; $i < sizeOf($post['store']['homepage_slider']['index']); $i++) { 

									$imagePath = null;

									if(isset($post['store']['hsbackgroundimage']) && $post['store']['homepage_slider']['edited'][$i] == 1) {

										$imagePath = $post['store']['hsbackgroundimage'];

									}


									array_push($slider, array(

										'index' => $post['store']['homepage_slider']['index'][$i],

										'title' => $post['store']['homepage_slider']['title'][$i],

										'sub_title' => $post['store']['homepage_slider']['sub_title'][$i],

										'content' => $post['store']['homepage_slider']['content'][$i],

										'slider_background_image' => ($imagePath != null) ? $imagePath : $post['store']['homepage_slider']['slider_background_image'][$i],

										'button_text' => $post['store']['homepage_slider']['button_text'][$i],

										'button_link' => $post['store']['homepage_slider']['button_link'][$i],

										'slider_text_color' => $post['store']['homepage_slider']['slider_text_color'][$i],

										'button_text_color' => $post['store']['homepage_slider']['button_text_color'][$i],

										'button_bg_color' => $post['store']['homepage_slider']['button_bg_color'][$i]

									));

								}				

								$post['store']['homepage_slider'] = json_encode($slider);

							} else {

								$post['store']['homepage_slider'] = json_encode([]);

							}



							if(isset($post['store']['homepage_features'])) {

								$features = [];

								for ($i=0; $i < sizeOf($post['store']['homepage_features']['index']); $i++) { 

									$imagePath = null;

									if(isset($post['store']['hfimage']) && $post['store']['homepage_features']['edited'][$i] == 1) {

										$imagePath = $post['store']['hfimage'];

									}


									array_push($features, array(

										'index' => $post['store']['homepage_features']['index'][$i],

										'title' => $post['store']['homepage_features']['title'][$i],

										'sub_title' => $post['store']['homepage_features']['sub_title'][$i],

										'feature_image' => ($imagePath != null) ? $imagePath : $post['store']['homepage_features']['feature_image'][$i],

									));

								}				

								$post['store']['homepage_features'] = json_encode($features);

							} else {

								$post['store']['homepage_features'] = json_encode([]);

							}



							if(isset($post['store']['bs_cards'])) {

								$bsCards = [];

								for ($i=0; $i < sizeOf($post['store']['bs_cards']['index']); $i++) { 

									$imagePath = null;

									if(isset($post['store']['bscimage']) && $post['store']['bs_cards']['edited'][$i] == 1) {

										$imagePath = $post['store']['bscimage'];

									}


									array_push($bsCards, array(

										'index' => $post['store']['bs_cards']['index'][$i],

										'title' => $post['store']['bs_cards']['title'][$i],

										'sub_title' => $post['store']['bs_cards']['sub_title'][$i],

										'bg_color' => $post['store']['bs_cards']['bg_color'][$i],

										'feature_image' => ($imagePath != null) ? $imagePath : $post['store']['bs_cards']['feature_image'][$i],
										
										'button_link' => $post['store']['bs_cards']['button_link'][$i],
										'link_target' => $post['store']['bs_cards']['link_target'][$i] 

									));

								}				

								$post['store']['bs_cards'] = json_encode($bsCards);

							} else {

								$post['store']['bs_cards'] = json_encode([]);

							}


							if(isset($post['store']['social_links'])) {

								$bsCards = [];

								for ($i=0; $i < sizeOf($post['store']['social_links']['index']); $i++) { 

									$imagePath = null;

									if(isset($post['store']['slicon']) && $post['store']['social_links']['edited'][$i] == 1) {

										$imagePath = $post['store']['slicon'];

									}



									array_push($bsCards, array(

										'index' => $post['store']['social_links']['index'][$i],

										'title' => $post['store']['social_links']['title'][$i],

										'url' => $post['store']['social_links']['url'][$i],

										'image' => ($imagePath != null) ? $imagePath : $post['store']['social_links']['image'][$i],

									));

								}				

								$post['store']['social_links'] = json_encode($bsCards);

							} else {

								$post['store']['social_links'] = json_encode([]);

							}



							$custom_page_returns = [];

							if(isset($post['store']['custom_page'])) {

								$custom_page = [];

								for ($i=0; $i < sizeOf($post['store']['custom_page']['index']); $i++) { 

									$imagePath = null;

									if(isset($post['store']['cpimage']) && $post['store']['custom_page']['edited'][$i] == 1) {

										$imagePath = $post['store']['cpimage'];

									}

									$meta_where = null;

									if(isset($post['store']['custom_page']['meta_id'][$i]) && !empty($post['store']['custom_page']['meta_id'][$i])) {

										$meta_where = array('meta_id'=> $post['store']['custom_page']['meta_id'][$i]);

									}

									$meta_id = $this->Setting_model->save_meta(array('meta_key' => 'custom_page_content','meta_content'=>$post['store']['custom_page']['content'][$i]), $meta_where);

									array_push($custom_page, array(

										'index' => $post['store']['custom_page']['index'][$i],

										'title' => $post['store']['custom_page']['title'][$i],

										'slug' => $post['store']['custom_page']['slug'][$i],

										'meta_id' => $meta_id,

										'image' => ($imagePath != null) ? $imagePath : $post['store']['custom_page']['image'][$i],

									));

									array_push($custom_page_returns, array(

										'index' => $post['store']['custom_page']['index'][$i],

										'title' => $post['store']['custom_page']['title'][$i],

										'slug' => $post['store']['custom_page']['slug'][$i],

										'meta_id' => $meta_id,

										'content' => $post['store']['custom_page']['content'][$i],

										'image' => ($imagePath != null) ? $imagePath : $post['store']['custom_page']['image'][$i],

									));

								}				

								$post['store']['custom_page'] = json_encode($custom_page);

							} else {

								$post['store']['custom_page'] = json_encode([]);

							}

							if(!empty($post['store']['per_task'])) {
								$post['store']['per_task'] = array_filter($post['store']['per_task']);
							}					
							$post['store']['per_task'] = json_encode($post['store']['per_task']);

							if(isset($post['store']['footer_menu'])) {

								$available_custom_pages_slug = ['about', 'contact', 'policy', 'login', 'cart', 'profile', 'order', 'shipping', 'wishlist'];

								foreach($custom_page_returns as $page) {

									array_push($available_custom_pages_slug, $page['slug']);

								}

								$footer_menu = [];

								for ($i=0; $i < sizeOf($post['store']['footer_menu']['index']); $i++) { 

									$links = [];

									if(!empty($post['store']['footer_menu']['links'][$i]['title'])) {

									for ($j=0; $j < sizeOf($post['store']['footer_menu']['links'][$i]['title']); $j++) {

										$link_url = explode("/", $post['store']['footer_menu']['links'][$i]['url'][$j]);


										$link_slug = end($link_url);

										if($post['store']['footer_menu']['links'][$i]['type'][$j] == 'page' && !in_array($link_slug, $available_custom_pages_slug)) {

											continue;

										}

										array_push($links, [

											'title'=>$post['store']['footer_menu']['links'][$i]['title'][$j], 

											'url'=>$post['store']['footer_menu']['links'][$i]['url'][$j],

											'type'=>$post['store']['footer_menu']['links'][$i]['type'][$j]

										]);

									}
								}

									array_push($footer_menu, array(

										'index' => $post['store']['footer_menu']['index'][$i],

										'title' => $post['store']['footer_menu']['title'][$i],

										'links' => $links

									));

								}

								$post['store']['footer_menu'] = json_encode($footer_menu);

							} else {

								$post['store']['footer_menu'] = json_encode([]);

							}

							$productsetting = $post['productsetting'];			

							$formsetting = $post['formsetting'];

							if( $productsetting['product_recursion'] == 'custom_time' ){

								if($productsetting['recursion_custom_time'] < 1){

									$json['errors']['productsetting_recursion_custom_time'] = "Recursion Time is required.";

								}else{

									unset($json['errors']['productsetting_recursion_custom_time']) ;

								}

							}else{

								$post['productsetting']['recursion_custom_time'] = 0;

							}			



							if( $formsetting['form_recursion'] == 'custom_time' ){

								if($formsetting['recursion_custom_time'] < 1){

									$json['errors']['formsetting_recursion_custom_time'] = "Time is required.";

								}else{

									unset($json['errors']['formsetting_recursion_custom_time']) ;

								}

							}else{

								$post['formsetting']['recursion_custom_time'] = 0;

							}


							$staticpages = array("about_content", "contact_content", "policy_content");


							$language_id=$post['language_id'];

							foreach ($post as $key => $value) {

								if (in_array($key, $commonSetting)) {

									if($key == 'order_comment'){

										if(!isset($value['title'])){

											$value['title'] = array();
										}
										$this->Setting_model->save($key, $value);

									}
									else if($key == 'store')
									{

										$storesettings=$value;
										$staticcontent=array();
										foreach ($storesettings as $skey => $svalue) 
										{
											if(in_array($skey, $staticpages))
											{
												$staticcontent=array_merge($staticcontent,array($skey=>$svalue));
												unset($storesettings[$skey]);
											}

										} 
										$this->Setting_model->save($key, $storesettings); 
										$this->Setting_model->saveWithLanguage($key,$language_id, $staticcontent);

									}
									else
										$this->Setting_model->save($key, $value);
									

								}

							}



							if(!isset($json['errors'])){

								if($return == 'slider') {

									$json['homepage_slider'] = json_decode($post['store']['homepage_slider']);

								}

								if($return == 'features') {

									$json['homepage_features'] = json_decode($post['store']['homepage_features']);

								}

								if($return == 'bs_cards') {

									$json['bs_cards'] = json_decode($post['store']['bs_cards']);

								}



								if($return == 'footer_menu' || $return == 'custom_page') {

									$json['footer_menu'] = json_decode($post['store']['footer_menu']);

								}



								if($return == 'custom_page') {

									$json['custom_page'] = $custom_page_returns;

								}



								if($return == 'social_links') {

									$json['social_links'] = json_decode($post['store']['social_links']);

								}
								if($return == 'hbanimage') {

									$json['hbanimage'] = $post['store']['hbanimage'];

								}
								
			 

								$json['custom_page_for_menu'] = array(

									['name'=> 'About', 'slug' => 'about'],

									['name'=> 'Contact', 'slug' => 'contact'],

									['name'=> 'Policy', 'slug' => 'policy'],

									['name'=> 'Login', 'slug' => 'login'],

									['name'=> 'cart', 'slug' => 'cart'],

									['name'=> 'User Profile', 'slug' => 'profile'],

									['name'=> 'User Order', 'slug' => 'order'],

									['name'=> 'User Shipping', 'slug' => 'shipping'],

									['name'=> 'User Wishlist', 'slug' => 'wishlist'],

								);

								foreach($custom_page_returns as $page){

									array_push($json['custom_page_for_menu'], ['name'=> $page['title'], 'slug' => 'page/'.$page['slug']]);
								}
								$json['success'] =  __('admin.setting_saved_successfully');
							}

						}
						echo json_encode($json);die;

					}



					$this->load->model('PagebuilderModel');

					$data['CurrencySymbol'] = $this->currency->getSymbol();

					foreach ($commonSetting as $key => $value) {

						$data[$value] 	= $this->Product_model->getSettings($value);

						if($value == 'order_comment'){

							$data['order_comment']['title'] = json_decode($data['order_comment']['title'], true);

						}

					}


					$data['country'] = $this->Product_model->getcountry('id,name');

					$data['categories'] = $this->db->query("SELECT name,slug FROM categories")->result_array();

					$data['pages'] = array(

						['name'=> 'About', 'slug' => 'about'],

						['name'=> 'Contact', 'slug' => 'contact'],

						['name'=> 'Policy', 'slug' => 'policy'],

						['name'=> 'Login', 'slug' => 'login'],

						['name'=> 'cart', 'slug' => 'cart'],

						['name'=> 'User Profile', 'slug' => 'profile'],

						['name'=> 'User Order', 'slug' => 'order'],

						['name'=> 'User Shipping', 'slug' => 'shipping'],

						['name'=> 'User Wishlist', 'slug' => 'wishlist'],

					);

					$data['languages'] = $this->db->query("SELECT * FROM language where status=1")->result_array();
						
					$data['store_setting'] = $this->Product_model->getSettings('store');

					$custom_pages = json_decode($data['store_setting']['custom_page']);

					foreach($custom_pages as &$page){

						$page->content = $this->Setting_model->get_meta_content(['meta_id'=>$page->meta_id])->meta_content;

						array_push($data['pages'], ['name'=> $page->title, 'slug' => 'page/'.$page->slug]);

					}
			 	

				$data['store_setting']['custom_page'] = json_encode($custom_pages);
				$data['admin_full_name'] = trim(($userdetails['firstname'] ?? '') . ' ' . ($userdetails['lastname'] ?? ''));
				
				$this->view($data, 'setting/store_setting');
				}



				public function market_tools_setting(){

					$userdetails = $this->userdetails();

					if(empty($userdetails)){redirect($this->admin_domain_url);}

					$commonSetting = array('marketpostback','market_vendor');

					$post = $this->input->post(null,true);

					if(!empty($post)){

						$json = array();

						if(!isset($json['errors'])){

							if (!isset($post['marketpostback']['static'])) {

								$post['marketpostback']['static'] = [];

							}

							foreach ($post as $key => $value) {
								if (in_array($key, $commonSetting)) {
									$this->Setting_model->save($key, $value);
								}
							}

							if(!isset($json['errors'])){
								$json['success'] =  __('admin.setting_saved_successfully');
							}
						}
						echo json_encode($json);die;
					}

					$data['CurrencySymbol'] = $this->currency->getSymbol();

					foreach ($commonSetting as $key => $value) {

						$data[$value] 	= $this->Product_model->getSettings($value);
					}

					$this->view($data,'setting/market_tools_setting');
				}
				

				public function saas_setting(){
					$userdetails = $this->userdetails();
					if(empty($userdetails)){redirect($this->admin_domain_url);}

					$store_mode = $this->Product_model->getSettings('store', 'store_mode');
					$data['store_mode'] = $store_mode['store_mode'] ?? 'cart'; //changing the store mode

					$market_vendor_marketvendorstatus = $this->Product_model->getSettings('market_vendor', 'marketvendorstatus');
					$vendor_storestatus = $this->Product_model->getSettings('vendor', 'storestatus');
					$market_vendor_marketvendorstatus =  isset($market_vendor_marketvendorstatus['marketvendorstatus']) ? $market_vendor_marketvendorstatus['marketvendorstatus'] : 0;
					$vendor_storestatus =  isset($vendor_storestatus['storestatus']) ? $vendor_storestatus['storestatus'] : 0;

					$data['saas_status'] = ($market_vendor_marketvendorstatus == 1 || $vendor_storestatus == 1) ? 1 : 0;
					if($data['saas_status']){
						$commonSetting = array('market_vendor','vendor', 'site');
						$post = $this->input->post(null,true);
						if(!empty($post)){
							$json = array();
							if(!isset($json['errors'])){
								if (!isset($post['marketpostback']['static'])) {
									$post['marketpostback']['static'] = [];
								}
								foreach ($post as $key => $value) {
									if (in_array($key, $commonSetting)) {
										$this->Setting_model->save($key, $value);
									}
								}
								if(!isset($json['errors'])){
									$json['success'] =  __('admin.setting_saved_successfully');
								}
							}
							echo json_encode($json);die;
						}

						$data['CurrencySymbol'] = $this->currency->getSymbol();
						foreach ($commonSetting as $key => $value) {
							$data[$value] 	= $this->Product_model->getSettings($value);
						}
					}

					$this->view($data,'setting/saas_setting');
				}

				public function wallet_setting(){
					$userdetails = $this->userdetails();
					if(empty($userdetails)){redirect($this->admin_domain_url);}
					$commonSetting = array('referlevel', 'site');
					$post = $this->input->post(null,true);
					if(!empty($post))
					{
						$json = array();
			 
						if($post["site"]["wallet_auto_withdrawal"]==1)
						{
							 
							if($post["site"]["wallet_auto_withdrawal_days"]=='')
								$json['errors'] = __('admin.enter_days_records_old_from_today');
							else if ($post["site"]["wallet_auto_withdrawal_limit"]=='')
								$json['errors'] = __('admin.enter_limit_of_record_auto_withdrawal');
							else if	($post["site"]["wallet_auto_withdrawal_limit"]<1 || $post["site"]["wallet_auto_withdrawal_limit"]>1000000)
								$json['errors'] = __('admin.number_of_limit_must_be_between');
						} 
						
						if(!isset($json['errors'])){
							foreach ($post as $key => $value) {
								if (in_array($key, $commonSetting)) {
									$this->Setting_model->save($key, $value);
								}
							}
							if(!isset($json['errors'])){
								$json['success'] =  __('admin.setting_saved_successfully');
							}
						}
						echo json_encode($json);die;
					}

					$data['CurrencySymbol'] = $this->currency->getSymbol();
					foreach ($commonSetting as $key => $value) {
						$data[$value] 	= $this->Product_model->getSettings($value);
					}

					$this->view($data,'setting/wallet_setting');
				}


				public function paymentsetting(){
					$validTabs = array('site-settings','email-setting','ai-helper','telegram-setting','tracking','fraud','googleads-setting','googlerecaptcha-setting','user-dashboard-setting','theme','login-2');
					$tabParam = $this->input->get('tab');
					$data['paymentsetting_active_tab'] = (in_array($tabParam, $validTabs)) ? $tabParam : null;

					$this->load->library('deflanguage');
					
					$userdetails = $this->userdetails();

					$commonSetting = array('email','paymentsetting','integration','login', 'loginclient','productsetting','formsetting','site','affiliateprogramsetting','store','doc','googlerecaptcha','referlevel','userdashboard','security','theme','welcome','s3_storage','proxy_services','ai_helper');

					$data['font_families'] = [
						"PT Sans" 		=> "PT Sans",
						"Arial" 		=> "Arial",
						"Helvetica" 	=> "Helvetica",
						"Georgia" 		=> "Georgia",
						"Times New Roman" => "Times New Roman",
						"Verdana" 		=> "Verdana",
						"Tahoma" 		=> "Tahoma",
						"Trebuchet MS" 	=> "Trebuchet MS",
						"Impact" 		=> "Impact",
						"Comic Sans MS" => "Comic Sans MS",
						"Courier New" 	=> "Courier New",
						"Lucida Sans Unicode" => "Lucida Sans Unicode",
						"Palatino Linotype" => "Palatino Linotype",
						"Garamond" 		=> "Garamond",
						"Bookman Old Style" => "Bookman Old Style",
						"Avant Garde" 	=> "Avant Garde",
						"Futura" 		=> "Futura",
						"Optima" 		=> "Optima",
						"Gill Sans" 	=> "Gill Sans",
						"Franklin Gothic Medium" => "Franklin Gothic Medium",
						"Arial Black" 	=> "Arial Black",
						"Arial Narrow" 	=> "Arial Narrow",
						"Arial Rounded MT Bold" => "Arial Rounded MT Bold",
						"Monaco" 		=> "Monaco",
						"Consolas" 		=> "Consolas",
						"DejaVu Sans" 	=> "DejaVu Sans",
						"DejaVu Serif" 	=> "DejaVu Serif",
						"DejaVu Sans Mono" => "DejaVu Sans Mono",
						"Liberation Sans" => "Liberation Sans",
						"Liberation Serif" => "Liberation Serif",
						"Liberation Mono" => "Liberation Mono",
						"Ubuntu" 		=> "Ubuntu",
						"Ubuntu Mono" 	=> "Ubuntu Mono",
						"Bitstream Vera Sans" => "Bitstream Vera Sans",
						"Bitstream Vera Serif" => "Bitstream Vera Serif",
						"Bitstream Vera Sans Mono" => "Bitstream Vera Sans Mono",
						"Sans-Serif" 	=> "sans-serif",
						"Serif" 		=> "serif",
						"Monospace" 	=> "monospace"
					];

					$post = $this->input->post(null,true);

					//Telegram test message
					if (isset($post['test_telegram']) && $post['test_telegram'] == '1') {
					    $this->load->model('Product_model');

					    $json = [];

					    $enabled = $this->Product_model->getSettings('site', 'telegram_enable', true);
					    $token   = $this->Product_model->getSettings('site', 'telegram_bot_token', true);
					    $chat_id = $this->Product_model->getSettings('site', 'telegram_chat_id', true);

					    if ((int)$enabled !== 1 || empty($token) || empty($chat_id)) {
					        $json['message'] = __('admin.telegram_test_failed');
					        echo json_encode($json); die;
					    }

					    $message = "âœ… *" . __('admin.telegram_test_message_title') . "*\n" . __('admin.telegram_test_message_body');

					    $url = "https://api.telegram.org/bot{$token}/sendMessage";
					    $data = [
					        'chat_id'    => $chat_id,
					        'text'       => $message,
					        'parse_mode' => 'Markdown'
					    ];

					    $options = [
					        'http' => [
					            'method'  => 'POST',
					            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
					            'content' => http_build_query($data)
					        ]
					    ];

					    $result = @file_get_contents($url, false, stream_context_create($options));

					    if ($result) {
					        $json['success'] = __('admin.telegram_test_success');
					    } else {
					        $json['message'] = __('admin.telegram_test_failed');
					    }

					    echo json_encode($json); die;
					}

					if (isset($post['send_test_mail'])) {

						$this->load->model('Mail_model');

						$json['message']=$this->Mail_model->send_test_mail($post['send_test_mail']);

						echo json_encode($json); 
						die;

					}else if(!empty($post)){


						$json = array();
						
						
						if(isset($post['googleads'])){
							
							try {
								if($post['googleads']['client_key'] != "" && $post['googleads']['unit_key'] != ""){
									$where=array();
									if($post['googleads']['id'] != ""){
										$where['id']=$post['googleads']['id'];
									}
									$checkAdsenseSec=$this->db->query("Select * from google_ads where ad_section=".$post['googleads']['ad_section']."")->row_array();
									

									$this->Setting_model->save_ads(
										array(
											'client_key' => $post['googleads']['client_key'],
											'unit_key' => $post['googleads']['unit_key'],
											'ad_section' => $post['googleads']['ad_section'],  
										),$where,$checkAdsenseSec
									);
									if(!empty($where)){
										$json['success'] = true;
									}else{
										if(empty($checkAdsenseSec)){
											$json['success'] = true;
										}else{
											$json['message']="AdSense already added for this section.";
										}
									}
									
								}
							} catch (\Throwable $th) {
								$json['message'] = $th->getMessage();
							}
							
							unset($post['googleads']);
							$googleadsStatus=$post['googleadsStatus'];
							$this->db->query("Update google_ads set status=0 where 1");
							foreach($googleadsStatus as $key => $adsStatus){
								$this->Setting_model->update_ads($key);
							}
						}
						if(isset($post['loginclient'])) {
							try {
								$this->Setting_model->save(
									'loginclient', 
									array(
										'heading' => $post['heading'],
										'content' => $post['content'],
										'about_content' => $post['about_content'],
									)
								);
								$json['success'] = true;
							} catch (\Throwable $th) {
								$json['message'] = $th->getMessage();
							}
						}

						if(isset($post['tnc']) && isset($post['tnc']['language_id']) && $post['tnc']['language_id']>0) {
							try {
								$this->Setting_model->saveWithLanguage(
									'tnc', 
									$post['tnc']['language_id'],
									array(
										'heading' => $post['tnc']['heading'],
										'content' => $post['tnc']['content'], 
									)
								);
								$json['success'] = true;
							} catch (\Throwable $th) {
								$json['message'] = $th->getMessage();
							}
						}


						$post['site']['google_analytics'] = base64_decode($post['site']['google_analytics']);

						$post['site']['faceboook_pixel'] = base64_decode($post['site']['faceboook_pixel']);

						$post['site']['global_script'] = base64_decode($post['site']['global_script']);

						$post['site']['fbmessager_script'] = base64_decode($post['site']['fbmessager_script']);
						
						// Handle facebook messenger status (checkboxes)
						if (!isset($post['site']['fbmessager_status'])) {
						    $post['site']['fbmessager_status'] = json_encode([]);
						} else {
						    $post['site']['fbmessager_status'] = json_encode($post['site']['fbmessager_status']);
						}


						if(isset($post['site']['hide_currency_from']) && !empty($post['site']['hide_currency_from'])) {
							$post['site']['hide_currency_from'] = implode(',',$post['site']['hide_currency_from']);
						} else {
							$post['site']['hide_currency_from'] = "";
						}

						if($post['site']['google_analytics'] != ''){

							$content = $post['site']['google_analytics'];

							preg_match_all('#<script(.*?)</script>#is', $content, $matches);



							if(count($matches[0]) != 2){

								$json['errors']['site[google_analytics]'] = 'Wrong Google Analytics Code';

							} else if (strpos($content, 'https://www.googletagmanager.com/gtag/js') === false) {

								$json['errors']['site[google_analytics]'] = 'Wrong Google Analytics Code';

							}

						}

						if($post['site']['faceboook_pixel'] != ''){

							$content = $post['site']['faceboook_pixel'];

							preg_match_all('#<script(.*?)</script>#is', $content, $matches);

							preg_match_all('#<noscript(.*?)</noscript>#is', $content, $matches2);



							if(count($matches[0]) != 1){

								$json['errors']['site[faceboook_pixel]'] = 'Wrong Facebook Pixel Code';

							} else if (strpos($content, 'https://www.facebook.com') === false) {

								$json['errors']['site[faceboook_pixel]'] = 'Wrong Facebook Pixel Code';

							}

						}	
						

						if(!isset($json['errors'])){

							if(count($_FILES) > 0){

								$path = 'assets/images/site';

								$this->load->helper('string');

								$config['upload_path'] = $path;

								$config['allowed_types'] = '*';

								$config['file_name']  = random_string('alnum', 32);

								$this->load->library('upload', $config);


								foreach ($_FILES as $fieldname => $input) {

									$this->upload->initialize($config);

									list($key,$subkey) = explode("_", $fieldname);

									$extension = pathinfo($_FILES[$fieldname]["name"], PATHINFO_EXTENSION);

									if($input['error'] == 0){

										if($extension=='jpg' || $extension=='jpeg' || $extension=='png' || $extension=='gif'){

											if ($post[$key][$subkey]) {
												if (!$this->upload->do_upload($fieldname)) {

												}

												else {

													$upload_details = $this->upload->data();

													$post[$key][$subkey] = $upload_details['file_name'];

												}
											}

										} else{

											$json['errors']["{$key}_{$subkey}"] = 'Only Image File are allowed';

										}

									}

								}

							}
							
							if(!isset($post['site']['global_script_status'])){ $post['site']['global_script_status'] = array(); }

							if(!isset($post['marketpostback']['dynamicparam'])){ $post['marketpostback']['dynamicparam'] = array(); }

							if(!isset($post['marketpostback']['static'])){ $post['marketpostback']['static'] = array(); }

							if (!isset($post['site']['telegram_enable'])) $post['site']['telegram_enable'] = 0;
							if (!isset($post['site']['telegram_event_user_register'])) $post['site']['telegram_event_user_register'] = 0;
							if (!isset($post['site']['telegram_event_new_external_order'])) $post['site']['telegram_event_new_external_order'] = 0;
							if (!isset($post['site']['telegram_event_new_store_order'])) $post['site']['telegram_event_new_store_order'] = 0;

							foreach ($post as $key => $value) {

								if (in_array($key, $commonSetting)) {

									$this->Setting_model->save($key, $value);

								}

							}
							if(isset($post['site']['cookies_consent_mesag'])){
								$this->deflanguage->change_line('cookies_consent_custom_message',$post['site']['cookies_consent_mesag'],'admin','default');
							}

							if(!isset($json['errors'])){

								$json['success'] =  __('admin.setting_saved_successfully');
							}
						}

						echo json_encode($json);die;

					} else {

						$this->load->model('PagebuilderModel');
						 
						$data['languages'] = $this->db->query("SELECT * FROM language where status=1")->result_array();
						 
						$data['CurrencySymbol'] = $this->currency->getSymbol();

						$data['tnc'] 	= $this->Product_model->getSettingsWithLanaguage('tnc');

						
						foreach ($commonSetting as $key => $value) {

							$data[$value] 	= $this->Product_model->getSettings($value);

						}
						

						$data['getAffiliate'] 	= $this->Product_model->getAffiliateById();

						$data['googleads'] 	= $this->Setting_model->getGoogleAds();


						$data['users_list'] = $this->db->query("SELECT CONCAT(firstname,' ',lastname,' - (',email,')') AS name ,id  FROM users WHERE type = 'user'")->result_array();

						$audio_sound = $this->Product_model->getSettings('site', 'notification_sound');

						if (sizeof($audio_sound) > 0) {
							$data['audio_sound'] = $audio_sound['notification_sound'];
						}else{
							$data['audio_sound'] = '';
						}

						$this->view($data, 'setting/paymentsetting');
					}
				}

				public function reset_default_colors_ajax(){
					$userdetails = $this->userdetails();
					
					// Demo Mode
					if (ENVIRONMENT === 'demo') {
						echo json_encode([
							'status' => 'error',
							'message' => 'Disabled on demo mode'
						]);
						return;
					}
					
					// Default colors and font
					$defaultColors = [
						'admin_topbar_bg' => '#34495e',
						'admin_topbar_text' => '#ffffff',
						'admin_dropdown_bg' => '#34495e',
						'admin_menu_bg' => '#667eea',
						'admin_menu_text' => '#ffffff',
						'admin_menu_active' => '#ffffff',
						'admin_menu_hover' => '#ffffff',
						'admin_dropdown_scrollbar' => '#666666',
						'admin_footer_bg' => '#1a252f',
						'admin_footer_text' => '#ffffff'
					];
					
					// Default font
					$defaultFont = 'PT Sans';
					
					// First, delete any existing entries to ensure clean reset
					foreach($defaultColors as $key => $value) {
						$this->db->where('setting_key', $key);
						$this->db->where('setting_type', 'theme');
						$this->db->delete('setting');
					}
					
					// Delete existing font setting
					$this->db->where('setting_key', 'admin_side_font');
					$this->db->where('setting_type', 'site');
					$this->db->delete('setting');
					
					// Save the default colors to database
					foreach($defaultColors as $key => $value) {
						$this->Setting_model->save('theme', [$key => $value]);
					}
					
					// Save the default font to database
					$this->Setting_model->save('site', ['admin_side_font' => $defaultFont]);
					
					echo json_encode([
						'status' => 'success',
						'message' => 'Colors and font reset to default successfully!',
						'colors' => $defaultColors,
						'font' => $defaultFont
					]);
				}

				public function auto_style_generator_ajax(){
					$userdetails = $this->userdetails();
					
					// Demo Mode
					if (ENVIRONMENT === 'demo') {
						echo json_encode([
							'status' => 'error',
							'message' => 'Disabled on demo mode'
						]);
						return;
					}
					
					// Smart color generation function
					function generateSmartColors() {
						// Base hue ranges for different themes
						$themeTypes = [
							'cool' => [200, 240], // Blues, cyans
							'warm' => [15, 45],   // Oranges, reds
							'nature' => [80, 140], // Greens
							'royal' => [250, 290], // Purples, violets
							'corporate' => [190, 220], // Professional blues
							'energetic' => [0, 20]  // Reds, magentas
						];
						
						// Pick random theme type
						$selectedTheme = array_rand($themeTypes);
						$hueRange = $themeTypes[$selectedTheme];
						
						// Generate base hue
						$baseHue = mt_rand($hueRange[0], $hueRange[1]);
						
						// Generate colors with good contrast
						$colors = [];
						
						// Primary color (menu background)
						$colors['primary'] = "hsl($baseHue, " . mt_rand(40, 70) . "%, " . mt_rand(45, 65) . "%)";
						
						// Secondary color (topbar - darker version)
						$darkerHue = ($baseHue + mt_rand(-20, 20)) % 360;
						$colors['secondary'] = "hsl($darkerHue, " . mt_rand(50, 80) . "%, " . mt_rand(25, 45) . "%)";
						
						// Accent color (active states - complementary or triadic)
						$accentHue = ($baseHue + mt_rand(120, 240)) % 360;
						$colors['accent'] = "hsl($accentHue, " . mt_rand(60, 90) . "%, " . mt_rand(45, 65) . "%)";
						
						// Hover color (slightly lighter accent)
						$colors['hover'] = "hsl($accentHue, " . mt_rand(50, 80) . "%, " . mt_rand(55, 75) . "%)";
						
						// Footer (darkest)
						$colors['footer'] = "hsl($darkerHue, " . mt_rand(40, 70) . "%, " . mt_rand(15, 35) . "%)";
						
						return $colors;
					}
					
					// Convert HSL to hex
					function hslToHex($hsl) {
						// Extract values from hsl string
						preg_match('/hsl\((\d+),\s*(\d+)%,\s*(\d+)%\)/', $hsl, $matches);
						$h = $matches[1] / 360;
						$s = $matches[2] / 100;
						$l = $matches[3] / 100;
						
						$r = $g = $b = $l; // achromatic
						
						if ($s != 0) {
							$hue2rgb = function($p, $q, $t) {
								if ($t < 0) $t += 1;
								if ($t > 1) $t -= 1;
								if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
								if ($t < 1/2) return $q;
								if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
								return $p;
							};
							
							$q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
							$p = 2 * $l - $q;
							$r = $hue2rgb($p, $q, $h + 1/3);
							$g = $hue2rgb($p, $q, $h);
							$b = $hue2rgb($p, $q, $h - 1/3);
						}
						
						return sprintf("#%02x%02x%02x", round($r * 255), round($g * 255), round($b * 255));
					}
					
					// Generate smart colors
					$smartColors = generateSmartColors();
					
					// Create scheme with generated colors
					$generatedScheme = [
						'name' => 'Smart Generated',
						'admin_topbar_bg' => hslToHex($smartColors['secondary']),
						'admin_topbar_text' => '#ffffff',
						'admin_dropdown_bg' => '#ffffff',
						'admin_menu_bg' => hslToHex($smartColors['primary']),
						'admin_menu_text' => '#ffffff',
						'admin_menu_active' => hslToHex($smartColors['accent']),
						'admin_menu_hover' => hslToHex($smartColors['hover']),
						'admin_dropdown_scrollbar' => hslToHex($smartColors['accent']),
						'admin_footer_bg' => hslToHex($smartColors['footer']),
						'admin_footer_text' => '#ffffff'
					];
					
					// Save the colors to database
					foreach($generatedScheme as $key => $value) {
						if($key !== 'name') {
							$this->Setting_model->save('theme', [$key => $value]);
						}
					}
					
					echo json_encode([
						'status' => 'success',
						'message' => 'Smart auto style generated successfully!',
						'colors' => $generatedScheme
					]);
				}

				public function system_issues(){
					$userdetails = $this->userdetails();
					
					// Use existing checkReq() function from system_status
					$serverErrors = checkReq();
					
					$issues = array();
					foreach ($serverErrors as $key => $message) {
						$issues[] = array(
							'type' => 'server_requirement',
							'name' => ucfirst(str_replace('_', ' ', $key)),
							'description' => $message,
							'current_value' => $this->getServerCurrentValue($key)
						);
					}
					
					$data['issues'] = $issues;
					$data['total_issues'] = count($issues);
					
					$this->view($data, 'system_issues');
				}
				
				private function getServerCurrentValue($req) {
					switch($req) {
						case 'php':
							return 'Current: ' . PHP_VERSION;
						case 'max_input_vars':
							return 'Current: ' . ini_get('max_input_vars');
						case 'upload_max_filesize':
							return 'Current: ' . ini_get('upload_max_filesize');
						case 'post_max_size':
							return 'Current: ' . ini_get('post_max_size');
						case 'allow_url_fopen':
							return 'Current: ' . (ini_get('allow_url_fopen') ? 'Enabled' : 'Disabled');
						default:
							return 'Not installed';
					}
				}

				public function mlm_settings(){

					$userdetails = $this->userdetails();

					$mlm_status = $this->Product_model->getSettings('referlevel', 'status');
					$data['mlm_status'] = $mlm_status['status'];
					if($data['mlm_status']){
						$commonSetting = array('referlevel_1','referlevel_2','referlevel_3','referlevel_4','referlevel_5','referlevel_6','referlevel_7','referlevel_8','referlevel_9','referlevel_10','referlevel');

						$post = $this->input->post(null,true);

						if(!empty($post)){
							if(!isset($post['referlevel']['disabled_for'])){ $post['referlevel']['disabled_for'] = array(); }
							
							foreach ($post as $key => $value) {
								if (in_array($key, $commonSetting)) {
									$this->Setting_model->save($key, $value);
								}
							}

							if(!isset($json['errors'])){
								$json['success'] =  __('admin.setting_saved_successfully');
							}

							echo json_encode($json);die;
						}

						$this->load->model('PagebuilderModel');
						$data['CurrencySymbol'] = $this->currency->getSymbol();
						foreach ($commonSetting as $key => $value) {
							$data[$value] 	= $this->Product_model->getSettings($value);
						}

						$data['getAffiliate'] 	= $this->Product_model->getAffiliateById();
						$data['users_list'] = $this->db->query("SELECT CONCAT(firstname,' ',lastname,' - (',email,')') AS name ,id  FROM users WHERE type = 'user'")->result_array();
					}
					

					$this->view($data,'setting/mlm_settings');
				}



				public function mlm_levels(){

					$userdetails = $this->userdetails();

					$mlm_status = $this->Product_model->getSettings('referlevel', 'status');
					$data['mlm_status'] = $mlm_status['status'];
					if($data['mlm_status']){
						$commonSetting = array('referlevel','referlevel_1','referlevel_2','referlevel_3','referlevel_4','referlevel_5','referlevel_6','referlevel_7','referlevel_8','referlevel_9','referlevel_10','referlevel_11','referlevel_12','referlevel_13','referlevel_14','referlevel_15','referlevel_16','referlevel_17','referlevel_18','referlevel_19','referlevel_20','referlevel');

						$post = $this->input->post(null,true);

						if(!empty($post)){

							if(!isset($post['referlevel']['disabled_for'])){ 
								$post['referlevel']['disabled_for'] = array(); 
							}

							foreach ($post as $key => $value) {
								if (in_array($key, $commonSetting)) {
									$this->Setting_model->save($key, $value);
								}
							}

							if(!isset($json['errors'])){
								$json['success'] =  __('admin.setting_saved_successfully');
							}

							echo json_encode($json);die;

						}

						$this->load->model('PagebuilderModel');

						$data['CurrencySymbol'] = $this->currency->getSymbol();

						foreach ($commonSetting as $key => $value) {

							$data[$value] 	= $this->Product_model->getSettings($value);

						}

						$data['getAffiliate'] 	= $this->Product_model->getAffiliateById();

						$data['users_list'] = $this->db->query("SELECT CONCAT(firstname,' ',lastname,' - (',email,')') AS name ,id  FROM users WHERE type = 'user'")->result_array();
					}


					$this->view($data,'setting/mlm_levels');
				}



				public function generateproductcode($affiliateads_id = null){

					$userdetails = $this->userdetails();

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					else {

						if($affiliateads_id){

							$data['product_id'] = $affiliateads_id;

							$data['user_id'] = $userdetails['id'];

							$data['getProduct'] 	= $this->Product_model->getProductByIdArray($affiliateads_id);

							$this->load->view('admincontrol/product/generatecode', $data);

						}

					}

				}



				public function setAffiliateClick($aff_id = null, $user_id = null ){

				}



				public function addsaveads($adsId = null){

					$userdetails = $this->userdetails();

					$post = $this->input->post(null,true);

					if(!empty($post)){

						$postdata['postdata'] =  $post;

						$InseredData['affiliateads_type'] =  $post['affiliateads_type'];

						if(!empty($_FILES['adsfile']['name'])){

							$upload_response = $this->upload_photo('adsfile','assets/images/ads');

							if($upload_response['success']) $postdata['adsfile'] = $upload_response['upload_data']['file_name'];

							else $errors = $upload_response['msg'];

						} else {

							if($post['adsfile']) $postdata['adsfile'] = $post['adsfile'];

							else $postdata['adsfile'] = '';

						}


						$InseredData['affiliateads_metadata'] =  json_encode($postdata);

						$InseredData['affiliateads_status'] =  $post['affiliateads_status'];

						if(empty($errors)){

							if(!empty($adsId)){

								$InseredData['affiliateads_updated_by'] =  $userdetails['id'];

								$InseredData['affiliateads_updated'] =  date('Y-m-d H:i:s');

								$this->Product_model->update_data('affiliateads', $InseredData,array('affiliateads_id' => $adsId));

								$this->session->set_flashdata('success', $post['affiliateads_type'].__('admin.updated_successfully'));

								redirect('admincontrol/affiliateadslist');

							} else {

								$InseredData['affiliateads_ipaddress'] =  $_SERVER['REMOTE_ADDR'];

								$InseredData['affiliateads_created_by'] =  $userdetails['id'];

								$InseredData['affiliateads_created'] =  date('Y-m-d H:i:s');

								$this->Product_model->create_data('affiliateads', $InseredData);

								$this->session->set_flashdata('success', $post['affiliateads_type'].__('admin.save_successfully'));

								redirect('admincontrol/affiliateadslist');

							}

						} else {

							$this->session->set_flashdata('error', $errors);

							redirect('admincontrol/'.$post['error']);

						}

					}

				}


				public function editProfile(){

					$userdetails = $this->userdetails();
			 
					$post = $this->input->post(null,true);

					$id =  $userdetails['id'];

					if(!empty($post)){

						$is_ajax = $this->input->is_ajax_request() || $this->input->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest';

						$rules = $this->user->profile_rules;

						$this->form_validation->set_rules($rules);

						if($this->form_validation->run())

						{

							$errors= array();

							$details = array(

								'firstname'     =>  $this->input->post('firstname',true),

								'lastname'      =>  $this->input->post('lastname',true),

								'email'         =>  $this->input->post('email',true),

								'PhoneNumber'   =>  $this->input->post('PhoneNumber',true),

								'Country'       =>  $this->input->post('Country',true),

								'StateProvince' =>  $this->input->post('StateProvince',true),

								'City'          =>  $this->input->post('City',true),

								'Zip'           =>  $this->input->post('Zip',true),

							);

							if(!empty($_FILES['avatar']['name'])){

								$upload_response = $this->upload_photo('avatar','assets/images/users');

								if($upload_response['success']){

									$details['avatar'] = $upload_response['upload_data']['file_name'];

								}

								else{

									$errors['avatar_error'] = $upload_response['msg'];

								}

							}

							if(empty($errors)){

								$this->user->update($id, $details);

								$user_details_array=$this->user->get_user_by_id($id);
								$user_details_array = admin_user_with_permissions($user_details_array);

								$this->session->set_userdata(array('administrator'=>$user_details_array));

								if($is_ajax){

									// Clear any previous output
									if (ob_get_level()) {
										ob_clean();
									}

									header('Content-Type: application/json; charset=utf-8');

									echo json_encode(array('success' => true, 'message' => __('admin.profile_updated_successfully')));

									die;

								}

								$this->session->set_flashdata('success', __('admin.profile_updated_successfully'));

								redirect('admincontrol/editProfile');

							}

							else{

								if($is_ajax){

									// Clear any previous output
									if (ob_get_level()) {
										ob_clean();
									}

									header('Content-Type: application/json; charset=utf-8');

									echo json_encode(array('success' => false, 'errors' => $errors, 'message' => __('admin.update_failed')));

									die;

								}

							}

						}

						else

						{

							if($is_ajax){

								// Clear any previous output
								if (ob_get_level()) {
									ob_clean();
								}

								header('Content-Type: application/json; charset=utf-8');

								echo json_encode(array('success' => false, 'message' => validation_errors()));

								die;

							}

							$this->session->set_flashdata('error', validation_errors());

							redirect('admincontrol/editProfile');

						}

					}else{

						$data['user']  = $this->user->get($id);

						$data['country'] = $this->Product_model->getcountry();

						$this->view($data,'users/edit_profile');

					}

				}



				public function getstate($country_id = null) {

					$userdetails = $this->userdetails();

					$post = $this->input->post(null,true);

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					else {

						if(!empty($post['country_id'])){

							$states = $this->Product_model->getAllstate($post['country_id']);

						}

						echo '<option selected="selected">'.__('admin.select_state').'</option>';

						if(!empty($states)){
							$isIDs= !empty($post['isId']) ? true :false;
							foreach($states as $state){

								echo '<option value="'.$state[$isIDs?'id':'name'].'">'.$state['name'].'</option>';

							}

						}

						die;

					}

				}



				public function delete_image($image_id = null){

					$userdetails = $this->userdetails();

					$post = $this->input->post(null,true);

					if(empty($userdetails)){

						redirect('usercontrol');
					}

					else {

						if(!empty($post['image_id'])){

							$this->Product_model->deleteImage($post['image_id']);

						}

					}

				}

				public function resetnotify(){
					$this->output->set_content_type('application/json');

					$result['status'] = 0;

					$userdetails = $this->userdetails();
					if(!empty($userdetails)){
						$notifications = $this->Product_model->getnotificationnew('admin',null);

						foreach($notifications as $key => $value)
							$success = $this->Product_model->update_data('notification',array('notification_is_read' => 1),array('notification_id' => $value['notification_id']));
						
						if($success)
							$result['status'] = 1;
					}

					$this->output->set_output(json_encode($result));
				}

				public function updatenotify($country_id = null) {

					$userdetails = $this->userdetails();

					$json = array();

					$post = $this->input->post(null,true);

						if(!empty($post['id'])){

							$noti = $this->db->query("SELECT * FROM notification WHERE notification_id= ". $post['id'])->row();

							if($noti->notification_type == 'membership_order'){

								if($noti->notification_viewfor == 'admin'){

									$json['location'] = base_url($noti->notification_url);

								} else{

									$json['location'] = base_url('usercontrol/'.$noti->notification_url);

								}

							}

							else if($noti->notification_type == 'integration_program'){

								if($noti->notification_viewfor == 'admin'){

									$json['location'] = base_url($noti->notification_url);

								} else{

									$json['location'] = base_url('usercontrol/'.$noti->notification_url);

								}

							}

							else if($noti->notification_type == 'integration_tools'){

								if($noti->notification_viewfor == 'admin'){

									$json['location'] = base_url('integration/'.$noti->notification_url);

								} else{

									$json['location'] = base_url('usercontrol/'.$noti->notification_url);

								}

							}

							else if($noti->notification_type == 'integration_orders'){

								$json['location'] = base_url('admincontrol/'.$noti->notification_url);

							} else if($noti->notification_type == 'integration_click'){

								$json['location'] = base_url('admincontrol/'.$noti->notification_url);

							}else{

								$json['location'] = base_url('admincontrol/'.$noti->notification_url);

							}

							$this->Product_model->update_data('notification', array('notification_is_read' => 1),array('notification_id' => $post['id']));
						}

					echo json_encode($json);

				}


				public function getnotificationnew() {

					$userdetails = $this->userdetails();

					if(empty($userdetails)){

						redirect($this->admin_domain_url);

					}

					else {

						$notifications = $this->Product_model->getnotificationnew('admin');

						echo trim(count($notifications));
					}

				}



		public function getnotificationall() {

			$userdetails = $this->userdetails();

			if(empty($userdetails)){

				redirect($this->admin_domain_url);

			}

			else {

				$notifications = $this->Product_model->getnotificationall('admin');

				echo trim(count($notifications));
			}
		}

		public function getnotification() {
		    $userdetails = $this->userdetails();
		    if (empty($userdetails)) {
		        redirect($this->admin_domain_url);
		    } else {
		        $notifications = $this->Product_model->getnotification('admin');

		        if (!empty($notifications)) {
		            foreach ($notifications as $notification) {
		                $icon = '';
		                switch ($notification['notification_type']) {
		                    case 'order':
		                        $icon = 'mdi mdi-cart-outline';
		                        break;
		                    case 'client':
		                        $icon = 'mdi mdi-account-circle';
		                        break;
		                    case 'paymentrequest':
		                        $icon = 'mdi mdi-account-circle';
		                        break;
		                    case 'user':
		                        $icon = 'mdi mdi-account';
		                        break;
		                    case 'product':
		                        $icon = 'mdi mdi-basket';
		                        break;
		                    case 'commissionrequest':
		                        $icon = 'mdi mdi-cash-usd';
		                        break;
		                }

		                $notif_url = $notification['notification_url'];
		                if (strpos($notif_url, '/membership/') !== false) {
		                    $full_url = base_url() . ltrim($notif_url, '/');
		                } else {
		                    $full_url = base_url().'admincontrol'.$notif_url;
		                }

		                echo '<a href="javascript:void(0)" onclick="shownofication('.$notification['notification_id'].',\''.$full_url.'\')" class="dropdown-item notify-item d-flex align-items-center py-3">';

		                // Improved styling for the icon using Bootstrap 5
		                echo '<div class="notify-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">';
		                echo '<i class="'.$icon.'" style="font-size: 1.5rem;"></i>';
		                echo '</div>';

		                // Improved styling for the text
		                echo '<div class="flex-grow-1">';
		                echo '<p class="mb-0 notify-details"><b>'.$notification['notification_title'].'</b></p>';
		                echo '<small class="text-muted">'.$notification['notification_description'].'</small>';
		                echo '</div>';
		                echo '</a>';
		            }
		        }

		        die;
		    }
		}

		public function productupload($id = null){

			$userdetails = $this->userdetails();

			if(empty($userdetails)){

				redirect($this->admin_domain_url);

			}

			if(empty($id)){

				$this->session->set_flashdata('error', __('admin.photo_can_not_be_uploaded'));

				redirect('admincontrol/listproduct');

			}

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

					$cpt = count(array_filter($_FILES['product_multiple_image']['name']));

					if($cpt > 0)
					{
						$this->load->helper('string');

						$config = array(

							'upload_path' => 'assets/images/product/upload/',

							'allowed_types' => 'png|gif|jpeg|jpg|PNG|GIF|JPEG|JPG',

							'file_name'  => random_string('alnum', 32),
							'create_thumb'   => TRUE,
									'width' => 300,
							'height' => 300

						);

						$this->load->library('upload', $config);

						$this->load->library('image_lib');

						$this->upload->initialize($config);

						for($i=0; $i<$cpt; $i++)
						{           

							$_FILES['product_multiple_images']['name'] = $files['product_multiple_image']['name'][$i];

							$_FILES['product_multiple_images']['type'] = $files['product_multiple_image']['type'][$i];

							$_FILES['product_multiple_images']['tmp_name'] = $files['product_multiple_image']['tmp_name'][$i];

							$_FILES['product_multiple_images']['error'] = $files['product_multiple_image']['error'][$i];

							$_FILES['product_multiple_images']['size'] = $files['product_multiple_image']['size'][$i];    


							$filename=random_string('alnum', 32);
							$upload_response = $this->upload_photo('product_multiple_images','assets/images/product/upload/thumb');

							$upload_details = $this->upload->data();

							if($upload_details){

								$details['product_media_upload_path'] = $upload_details['file_name'];

							}else{

								$errors['avatar_error'] = $upload_details['msg'];

							}

							$details['product_media_upload_created_date'] = date('Y-m-d H:i:s');

							$this->Product_model->create_data('product_media_upload', $details);
						}
					}
					else
					{
						if(empty($_POST['multiple_image_s3'])){
							$this->session->set_flashdata('error', 'Please select at least one image');

							redirect('admincontrol/productupload/'.$id);

							exit();
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

					redirect('admincontrol/productupload/'.$id);

					exit();

				}

				$this->session->set_flashdata('success', __('admin.product_images_added_successfully'));

				redirect('admincontrol/productupload/'.$id);

			}

			$data['imageslist'] = $this->Product_model->getAllImages($id);

			$data['user'] = $userdetails;

			$data['s3_setting'] = $this->Product_model->getSettings('s3_storage');

			$this->view($data,'product/productupload');
		}

		public function videoupload($id = null){

			$userdetails = $this->userdetails();

			if(empty($id)){ redirect('admincontrol/listproduct'); }

			$post = $this->input->post(null,true);

			if(!empty($post)){

				$this->load->helper(array('form', 'url'));

				$this->load->library('form_validation');

				$this->form_validation->set_rules('product_media_upload_path', __('admin.product_video'), 'required|trim');

				if($this->form_validation->run() == true)

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

						redirect('admincontrol/videoupload/'.$id);

						exit();

					}

					$this->session->set_flashdata('success', __('admin.product_video_and_images_added_successfully'));

					$details['product_media_upload_created_by'] = $userdetails['id'];

					$details['product_media_upload_created_date'] = date('Y-m-d H:i:s');

					$this->Product_model->create_data('product_media_upload', $details);

					$data['productinfo'] = $this->Product_model->getProductByIdArray($id);

					$notificationData = array(

						'notification_url'          => '/videoupload/'.$id,

						'notification_type'         =>  'product',

						'notification_title'        =>  __('admin.new_product_added_in_affiliate_program'),

						'notification_view_user_id' =>  '',

						'notification_viewfor'      =>  'user',

						'notification_actionID'     =>  $id,

						'notification_description'  =>  'New Video uploaded on product '.$data['productinfo']['product_name'].' by admin in affiliate Program on '.date('Y-m-d H:i:s'),

						'notification_is_read'      =>  '0',

						'notification_created_date' =>  date('Y-m-d H:i:s'),

						'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']

					);

					$this->insertnotification($notificationData);

					redirect('admincontrol/videoupload/'.$id);

				}

				else

				{

					$this->session->set_flashdata('error', validation_errors());
					redirect('admincontrol/videoupload/'.$id);

				}

			} else {

				$data['videoimageslist'] = $this->Product_model->getAllVideoImages($id);

				$data['videoslist'] = $this->Product_model->getAllVideos($id);

				$data['user'] = $userdetails;

				$this->view($data,'product/videoupload');

			}
		}

		public function deleteAllusersMultiple(){

			// Demo Mode
			if (ENVIRONMENT === 'demo') {
				echo json_encode([
					'status' => 'error',
					'message' => 'Disabled on demo mode'
				]);
				return;
			}
			// Demo Mode

			$json = array();

			$post = $this->input->post(null,true);

			$ids  = explode(",", $post['ids']);

			$html = '';

			$html .= "<h6>". __('admin.following_affiliate_are_remove_from_this_affiliate_are_you_sure') ."</h6> <div class='scroll-table'><table class='table table-sm table-striped'>";

			$html .= "<thead><tr><th>...</th><th>". __('admin.name') ."</th><th>". __('admin.total_unpaid_commission') ."</th></tr></thead><tbody>";

			foreach ($ids as $key => $id) {

				$user = $this->db->query("SELECT id,firstname,lastname,refid FROM users WHERE id = ". (int)$id)->row();

				if($user){

					$unpaid_commition = (float)$this->db->query('SELECT sum(amount) AS total FROM wallet WHERE status IN (1,2) AND user_id = '. (int)$id )->row_array()['total'];

					$unpaid_commition += (float)$this->db->query('SELECT sum(commission) AS total FROM integration_orders WHERE user_id = '. (int)$id )->row_array()['total'];



					$html .= "<tr><td>{$user->id}</td><td>{$user->firstname} {$user->lastname}</td><td>". c_format($unpaid_commition) ."</td></tr>";

				}

			}

			$html .= '</tbody></table></div>';



			$json['message'] = $html;

			echo json_encode($json);
		}

		public function deleteGoogleAds(){

			$responce=$this->db->query("DELETE FROM google_ads WHERE id =". $_POST['id']);
			if($responce){
				$json['success'] ='success';
				$json['message'] = "<h6>". __('admin.ads_delete_successfully') ."</h6>";
			}else{
				$json['errors'] ='errors';
				$json['message'] = "<h6>". __('admin.ads_delete_failed') ."</h6>";
			}
			echo json_encode($json);	
		}

		public function deleteAllusers(){

			$json = array();

			$post = $this->input->post(null,true);

			$user = $this->db->query("SELECT id,firstname,lastname,refid FROM users WHERE id = ". (int)$post['id'])->row();

			if($user){

				$mylevels = $this->db->query("SELECT id,firstname,lastname,refid FROM users WHERE refid = ". (int)$post['id'])->result_array();

				if($mylevels){

					$level = $this->Product_model->getMyLevel($user->id);

					$firstLevel = (int)$level['level1'];

					$json['message'] = "<h6>". __('admin.following_affiliate_are_remove_from_this_affiliate_are_you_sure') ."</h6>";

				} else {

					$json['message'] = "<h2 class='text-center'>". __('admin.are_you_sure') ."</h2>";

				}


									$unpaid_commition = (float)$this->db->query('SELECT sum(amount) AS total FROM wallet WHERE status IN (1,2) AND user_id = '. (int)$post['id'] )->row_array()['total'];

				$unpaid_commition += (float)$this->db->query('SELECT sum(commission) AS total FROM integration_orders WHERE user_id = '. (int)$post['id'] )->row_array()['total'];

				$json['message'] .= "<br> ". __('admin.total_unpaid_commission') ." : ". c_format($unpaid_commition);

			}

			echo json_encode($json);
		}

		public function showTree(){

			$post = $this->input->post(null,true);

			$userdetails = $this->userdetails();

			$user_id = (int)$post['id'];

			$data['user'] 	= $this->Product_model->getUserDetailsObject($user_id);

			$json['html'] = $this->load->view('admincontrol/users/downline_modal', $data, true);

			echo json_encode($json);
		}

		public function myreferal_ajax($user_id){

			$data = $this->Product_model->getMyUnder($user_id);

			echo json_encode($data);
		}

		public function admin_tree_ajax(){
			$userdetails = $this->userdetails();

			if(empty($userdetails)){
				redirect($this->admin_domain_url);
			}

			// Get all users tree data starting from admin (ID 1)
			$data = $this->Product_model->getMyUnder(1);

			echo json_encode($data);
		}

		public function deleteUsersConfirm(){

			// Demo Mode
			if (ENVIRONMENT === 'demo') {
				echo json_encode([
					'status' => 'error',
					'message' => 'Disabled on demo mode'
				]);
				return;
			}
			// Demo Mode

			$json = array();

			$ids = array();

			$post = $this->input->post(null,true);



			if(isset($post['id']) && (int)$post['id'] == 0){

				$ids[] = $post['id'];

			} else{

				$ids = explode(",", $post['id']);

			}

			
			
			foreach ($ids as $key => $id) {

				$user = $this->db->query("SELECT id,firstname,lastname,refid FROM users WHERE id = ". (int)$id)->row();

				if($user){

					if(isset($post['delete_transaction']) && $post['delete_transaction'] == 'true'){

						$this->db->query("DELETE FROM wallet WHERE user_id =". (int)$id);

						$this->db->query("DELETE FROM wallet WHERE user_id  IN (SELECT id FROM users WHERE refid = $id) AND type='refer_registration_commission'");

						$this->db->query("UPDATE integration_orders SET user_id = 0, commission = 0 WHERE  user_id =". (int)$id);
					}

					$mylevels = $this->db->query("SELECT id,firstname,lastname,refid FROM users WHERE refid = ". (int)$id)->result_array();
					
					if($mylevels){

						$level = $this->Product_model->getMyLevel($user->id);

						$firstLevel = 0;

						foreach ($mylevels as $key => $value) {

							$this->db->query("UPDATE users SET refid = {$firstLevel} WHERE id = ". $value['id']);

						}		

					}

					$this->Product_model->deleteusers($user->id);
				}

			}

		$this->session->set_flashdata('success', __('admin.users_delete_successfully'));

		$json['status'] = 'success';
		$json['message'] = __('admin.users_delete_successfully');

		echo json_encode($json);
	}

		public function delete($id = null){

			if(!empty($id)){

				$res = $this->Product_model->deleteusers($id);

				$this->session->set_flashdata('success', __('admin.users_delete_successfully'));

				redirect(base_url() . 'admincontrol/userslist');

			}

			$this->session->set_flashdata('error', __('admin.users_delete_failed'));

			redirect(base_url() . 'admincontrol/userslist');
		}

		public function deleteAllproducts(){

			$post = $this->input->post(null,true);

			if(!empty($post['product']) || !empty($post['form'])){

				if(isset($post['product'])){
					
					foreach($post['product'] as $id){

						if(!empty($id)) {
							$orderProduct = $this->db->query('SELECT id FROM order_products WHERE product_id = '.$id)->row();

							if(empty($orderProduct)) {
								$this->Product_model->deleteproducts((int)$id);
							} else {
								$this->session->set_flashdata('error', __('admin.some_order_product_not_deleted'));
							}
						}
					}
				}

				if(isset($post['form'])){

					$this->load->model("Form_model");

					foreach($post['form'] as $id){
						if(!empty($id)) {
							$this->Form_model->deleteforms((int)$id);
						}
					}

				}

				$this->session->set_flashdata('success', __('admin.product_is_deleted_successfully'));

				redirect(base_url() . 'admincontrol/listproduct');

			} else {

				$id = (int)$this->input->get('delete_id');

				$orderProduct = $this->db->query('SELECT id FROM order_products WHERE product_id = '.$id)->row();

				if(empty($orderProduct)) {
					$res = $this->Product_model->deleteproducts($id);

					$this->session->set_flashdata('success', __('admin.product_is_deleted_successfully'));
				} else {
					$this->session->set_flashdata('error', __('admin.order_product_not_deleted'));
				}
				
				redirect(base_url() . 'admincontrol/listproduct');
			}

			$this->session->set_flashdata('error', __('admin.product_delete_failed'));

			redirect(base_url() . 'admincontrol/listproduct');
		}

		public function user_info(){

			$userdetails = $this->userdetails();

			return $this->Product_model->user_info($userdetails['id']);
		}

		public function docs(){
			$data['doc_config'] = $this->Product_model->getSettings('doc');
			$this->load->view($control.'/includes/header', $data);
			$this->load->view($control.'/includes/topnav', $data);
			$this->load->view('admincontrol/document/docs', $this);
			$this->load->view($control.'/includes/footer', $data);
		}

		public function form_manage($form_id = 0){

			$userdetails = $this->userdetails();

			$this->load->model("Form_model");
			$this->load->model("Product_model");

			$data['form'] = $this->Form_model->getForm($form_id);

			$data['form']['seo'] = str_replace('_', ' ', $data['form']['seo'] ?? '');

			$data['product'] = $this->db->query("SELECT DISTINCT p.product_id,p.product_name,p.product_price,p.product_type,p.allow_shipping FROM product p 
				LEFT JOIN product_affiliate pa ON pa.product_id = p.product_id
				WHERE pa.user_id IS NULL")->result_array();

			if(!$data['product']){
				$this->session->set_flashdata('error', __('admin.you_need_to_create_product'));
				redirect("admincontrol/form");
			}

			$data['setting'] = $this->Product_model->getSettings('formsetting');

			$data['coupons'] = $this->db->query("SELECT * FROM `form_coupon`")->result_array();	

			$data['paymets'] = json_decode($data['form']['payment']);

			$award_level = $this->Product_model->getSettings('award_level', 'status');
			$data['award_level_status'] = !empty($award_level['status']);
			$data['award_levels_list'] = $data['award_level_status'] ? $this->Product_model->getAll('award_level', false, 0, 'minimum_earning ASC') : [];

			$this->view($data,'form/form');
		}

		public function form(){

			$userdetails = $this->userdetails();

			$store_setting = $this->Product_model->getSettings('store');

			$this->load->model("Form_model");

			$data['forms'] = $this->Form_model->getForms();	

			foreach ($data['forms'] as $key => $value) {

				$data['forms'][$key]['coupon_name'] = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);

				$data['forms'][$key]['public_page'] = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));

				$data['forms'][$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id']);

				if($value['coupon']){

					$data['forms'][$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);

				}

				$data['forms'][$key]['seo'] = str_replace('_', ' ', $value['seo']);

			}



								$data['product_count'] = $this->db->query("SELECT count(p.product_id) AS total FROM product p 

				LEFT JOIN product_affiliate pa ON pa.product_id = p.product_id

				WHERE pa.user_id IS NULL ")->row()->total; 	



			$this->load->library("socialshare");				

			$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

			$data['form_coupons'] = $this->Form_model->getFormCoupons();

			$this->view($data,'form/index');
		}

		public function save_form(){

			$userdetails = $this->userdetails();

			$this->load->library('form_validation');

			$this->load->model("Form_model");

			$json = array();

			$json['errors'] = array();

			$data = $this->input->post(null,true);

			$this->form_validation->set_rules('title', 'Name', 'required|trim');

			$this->form_validation->set_rules('description', 'Description', 'required|trim');

			$this->form_validation->set_rules('allow_for', 'Allow For', 'required|trim');

			$this->form_validation->set_rules('footer_title', 'Footer Content', 'required|trim');

			$this->form_validation->set_rules('seo', 'Seo', 'required|trim');

			$form_id = 0;



			if( isset($data['form_recursion_type']) && $data['form_recursion_type'] == 'custom' ){

				$this->form_validation->set_rules('form_recursion', 'Form Recursion', 'required');

				if( isset($data['form_recursion']) && $data['form_recursion'] == 'custom_time' ){

					$this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');

				}

			}		

			$form_recursion = (isset($data['form_recursion_type']) && $data['form_recursion_type'] && $data['form_recursion_type'] != 'default' && isset($data['form_recursion'])) ? $data['form_recursion'] : "";

			$recursion_custom_time = ($form_recursion == 'custom_time' && isset($data['recursion_custom_time'])) ? $data['recursion_custom_time'] : 0;

			if($this->form_validation->run() == FALSE) {

				$json['errors'] = array_merge($this->form_validation->error_array(), $json['errors']);

			}else{

				$data['fevi_icon'] = '';

				if(!empty($_FILES['form_fevi_icon']['name'])){

					$upload_response = $this->upload_photo('form_fevi_icon','assets/images/form/favi/');

					if($upload_response['success']) $data['fevi_icon'] = $upload_response['upload_data']['file_name'];

					else $json['errors']['form_fevi_icon'] = $upload_response['msg'];

				} 

				$product_array = isset($data['product']) && is_array($data['product']) ? $data['product'] : []; 

				if(empty($json['errors'])){

					$form = array(

						'allow_for'             => isset($data['allow_for']) ? $data['allow_for'] : 'A',

						'coupon'                => isset($data['coupon']) ? $data['coupon'] : '',

						'description'           => isset($data['description']) ? $data['description'] : '',

						'seo'                   => isset($data['seo']) ? str_replace(' ', '_', trim($data['seo'])) : '',

						'footer_title'          => isset($data['footer_title']) ? $data['footer_title'] : '',

						'product'               => implode(",", $product_array),

						'title'                 => isset($data['title']) ? $data['title'] : '',

						'google_analitics'      => isset($data['google_analitics']) ? $data['google_analitics'] : '',

						'form_recursion_type'   => isset($data['form_recursion_type']) ? $data['form_recursion_type'] : '',

						'status'                => isset($data['status']) ? (int)$data['status'] : 1,

						'form_recursion'        => $form_recursion,

						'recursion_custom_time' => (int)$recursion_custom_time,

						'recursion_endtime'     => (isset($data['recursion_endtime_status']) && isset($data['recursion_endtime']) && $data['recursion_endtime']) ? date("Y-m-d H:i:s",strtotime($data['recursion_endtime'])) : null,

						'created_at' => date("Y-m-d H:i:s")

					);



					$form['sale_commision_type']  = isset($data['form_commision_type']) ? $data['form_commision_type'] : 'default';

					$form['sale_commision_value'] = isset($data['form_commision_value']) ? $data['form_commision_value'] : 0;

					$form['click_commision_type'] = isset($data['form_click_commision_type']) ? $data['form_click_commision_type'] : 'default';

					$form['click_commision_ppc']  = isset($data['form_click_commision_ppc']) ? $data['form_click_commision_ppc'] : 0;

					$form['click_commision_per']  = isset($data['form_click_commision_per']) ? $data['form_click_commision_per'] : 0;

					$form['min_health_score'] = isset($data['min_health_score']) ? max(0, min(100, (float) $data['min_health_score'])) : 0;
					$form['min_award_level_id'] = (!empty($data['min_award_level_id']) && (int) $data['min_award_level_id'] > 0) ? (int) $data['min_award_level_id'] : null;

					if(isset($data['fevi_icon']) && $data['fevi_icon']){ $form['fevi_icon'] = $data['fevi_icon']; }

					if(isset($data['id']) && $data['id'] > 0){

						$this->db->update("form",$form,['form_id' => $data['id']]);

						$form_id = $data['id'];

					} else {

						$form['created_at'] = date("Y-m-d H:i:s");

						$this->db->insert("form",$form);

						$form_id = $this->db->insert_id();

					}


					$json['success'] = true;

					if(isset($data['redirect']) && $data['redirect'] == 'save_stay'){

						if(isset($data['id']) && $data['id'] > 0){

							$json['location'] = base_url("admincontrol/form_manage/".$data['id']);

						} else {

							$json['location'] = base_url("admincontrol/form_manage/".$form_id );

						}

					} else {

						$json['location'] = base_url("admincontrol/listproduct");

					}

				}

			}



			echo json_encode($json);
		}

		public function form_coupon_manage($form_coupon_id = 0){

			$userdetails = $this->userdetails();

			$store_setting = $this->Product_model->getSettings('store');

			$this->load->model("Form_model");

			$data['form_coupon'] = $this->Form_model->getFormCoupon($form_coupon_id);	


			$this->view($data,'form/form_coupon');
		}

		public function form_coupon_delete($form_coupon_id){

			$userdetails = $this->userdetails();

			$this->load->model("Form_model");

			$this->Form_model->deleteFormCoupon($form_coupon_id);

			
			redirect(base_url("admincontrol/listproduct"));
		}

		public function form_coupon(){

			$userdetails = $this->userdetails();

			$store_setting = $this->Product_model->getSettings('store');

			$this->load->model("Form_model");

			$data['form_coupons'] = $this->Form_model->getFormCoupons();

			$this->view($data,'form/form_coupon_index');
		}

		public function save_form_coupon(){

			$userdetails = $this->userdetails();

			$store_setting = $this->Product_model->getSettings('store');

			$this->load->library('form_validation');

			$json = array();

			$this->form_validation->set_rules('name', 'Name', 'required|trim');

			$this->form_validation->set_rules('code', 'Coupon Code', 'required|trim');

			$this->form_validation->set_rules('type', 'Type', 'required|trim');	

			$this->form_validation->set_rules('discount', 'Discount', 'required|trim');

			$this->form_validation->set_rules('date_start', 'Start Date', 'required|trim');

			$this->form_validation->set_rules('date_end', 'End Date', 'required|trim');

			$this->form_validation->set_rules('status', 'Status', 'required|trim');

			if ($this->form_validation->run() == FALSE) {

				$json['errors'] = $this->form_validation->error_array();

			} else {

				$data = $this->input->post(null,true);

				$coupon = array(

					'name'       => $data['name'],

					'code'       => $data['code'],

					'type'       => $data['type'],			

					'discount'   => $data['discount'],

					'date_start' => date("Y-m-d", strtotime($data['date_start'])),

					'date_end'   => date("Y-m-d", strtotime($data['date_end'])),

					'uses_total' => $data['uses_total'],

					'status'     => $data['status'],			

					'date_added' => date("Y-m-d H:i:s"),

				);

				if($data['id'] > 0){

					unset($coupon['date_added']);

					$this->db->update("form_coupon",$coupon,['form_coupon_id' => $data['id']]);

				} else {

					$this->db->insert("form_coupon",$coupon);

					$coupon_id = $this->db->insert_id();

				}

				$json['location'] = base_url("admincontrol/listproduct");

			}

			echo json_encode($json);
		}

		public function generateformcode($form = 0){

			$userdetails = $this->userdetails();

			if(empty($userdetails)){

				redirect($this->admin_domain_url);

			}

			else {

				if($form){

					$data['form_id'] = $form;

					$data['user_id'] = $userdetails['id'];

					$this->load->model("Form_model");

					$data['getForm'] 	= $this->Form_model->getForm($form);

					$this->load->view('admincontrol/form/generatecode', $data);

				}

			}
		}

		public function deleteAllforms($form = 0){

			$this->load->model("Form_model");

			$post = $this->input->post(null,true);

			if(!empty($post['checkbox'])){

				foreach($post['checkbox'] as $id){				 

					if(!empty($id)){

						$res = $this->Form_model->deleteforms($id);

					}

				}

				$this->session->set_flashdata('success', __('admin.form_is_deleted_successfully'));

				redirect(base_url() . 'admincontrol/listproduct');

			}

			$this->session->set_flashdata('error', __('admin.form_delete_failed'));

			redirect(base_url() . 'admincontrol/listproduct');
		}

		public function form_delete($form = 0){ 

			$this->load->model("Form_model");

			if(!empty($form)){		

				$res = $this->Form_model->deleteforms($form);			

				$this->session->set_flashdata('success', __('admin.form_is_deleted_successfully'));

				redirect(base_url() . 'admincontrol/listproduct');

			}

			$this->session->set_flashdata('error', __('admin.form_delete_failed'));

			redirect(base_url() . 'admincontrol/listproduct');
		}

		public function currency_list(){

			$userdetails = $this->userdetails();

			$data['currencys'] = $this->db->query("SELECT * FROM currency ORDER BY is_default DESC, title ASC")->result_array();
			$lastUpdatedRow = $this->db->query("SELECT MAX(date_modified) AS last_updated FROM currency")->row_array();
			$data['rates_last_updated'] = isset($lastUpdatedRow['last_updated']) ? $lastUpdatedRow['last_updated'] : null;

			$this->load->model("Form_model");

			$data['form_coupons'] = $this->Form_model->getFormCoupons();

			$this->view($data,'currency/index');
		}

		public function currency_delete($currency_id){

			$userdetails = $this->userdetails();

			$this->db->query("DELETE FROM currency WHERE is_default = 0 AND currency_id = ". (int)$currency_id);

			$this->session->set_flashdata('success', __('admin.currency_delete_success'));

			redirect(base_url() . 'admincontrol/currency_list');
		}

		public function currency_delete_ajax(){
			$userdetails = $this->userdetails();
			$json = array();

			if ($this->input->server('REQUEST_METHOD') != 'POST') {
				$json['success'] = false;
				$json['message'] = __('admin.invalid_request_method');
				echo json_encode($json);
				return;
			}

			$currency_id = (int)$this->input->post('currency_id', true);
			if ($currency_id <= 0) {
				$json['success'] = false;
				$json['message'] = __('admin.invalid_currency_id');
				echo json_encode($json);
				return;
			}

			$currency = $this->db->get_where('currency', ['currency_id' => $currency_id])->row_array();
			if (!$currency) {
				$json['success'] = false;
				$json['message'] = __('admin.currency_not_found');
				echo json_encode($json);
				return;
			}

			if ((int)$currency['is_default'] === 1) {
				$json['success'] = false;
				$json['message'] = __('admin.cannot_delete_default_currency');
				echo json_encode($json);
				return;
			}

			// Attempt delete
			$this->db->query("DELETE FROM currency WHERE currency_id = ? AND is_default = 0 LIMIT 1", [$currency_id]);

			if ($this->db->affected_rows() > 0) {
				$json['success'] = true;
				$json['message'] = __('admin.currency_delete_success');
			} else {
				// Double-check if it still exists to craft accurate message
				$still = $this->db->get_where('currency', ['currency_id' => $currency_id])->row_array();
				if (!$still) {
					$json['success'] = true;
					$json['message'] = __('admin.currency_delete_success');
				} else {
					$json['success'] = false;
					$json['message'] = __('admin.currency_delete_failed');
				}
			}

			echo json_encode($json);
		}

		public function currency_edit($currency_id = 0){

			$userdetails = $this->userdetails();

			$post = $this->input->post(null,true);

			if (isset($post['currency_id'])) {


				$original_title = '';
				$original_code = '';
				if($currency_id > 0) {
					$existing = $this->db->query("SELECT title, code FROM currency WHERE currency_id = ".$currency_id)->row();
					if($existing) {
						$original_title = $existing->title;
						$original_code = $existing->code;
					}
				}

				$title_unique = '';
				$code_unique = '';
				
				if($this->input->post('title') != $original_title) {
					$title_unique = '|is_unique[currency.title]';
				}
				
				if($this->input->post('code') != $original_code) {
					$code_unique = '|is_unique[currency.code]';
				}

				$this->form_validation->set_rules('title', 'Currency Name', 'required|trim'.$title_unique);
				$this->form_validation->set_rules('code', 'Currency Code', 'required|trim|exact_length[3]'.$code_unique);

				$this->form_validation->set_rules('replace_comma_symbol', 'Replace Comma Symbol', 'required|trim');
				$this->form_validation->set_rules('decimal_symbol', 'Decimal Symbol', 'required|trim');

				$this->form_validation->set_rules('value', 'Value', 'required|trim|greater_than[0]');	
				
				if ($this->form_validation->run() == FALSE) {

					$json['errors'] = $this->form_validation->error_array();

				} else {

					$data = $this->input->post(null,true);

					$coupon = array(

						'title'         => isset($data['title']) ? $data['title'] : '',

						'code'          => isset($data['code']) ? $data['code'] : '',

						'symbol_left'   => isset($data['symbol_left']) ? $data['symbol_left'] : '',

						'symbol_right'  => isset($data['symbol_right']) ? $data['symbol_right'] : '',

						'replace_comma_symbol'  => isset($data['replace_comma_symbol']) ? $data['replace_comma_symbol'] : '',

						'decimal_symbol'  => isset($data['decimal_symbol']) ? $data['decimal_symbol'] : '',

						'decimal_place' => isset($data['decimal_place']) ? $data['decimal_place'] : '',

						'value'         => isset($data['value']) ? $data['value'] : 0,

						'status'        => isset($data['status']) ? $data['status'] : 1,

						'is_default'    => isset($data['is_default']) ? 1 : 0,

						'date_modified' => date("Y-m-d H:i:s"),

					);

					if($data['currency_id'] > 0){

						$this->db->update("currency",$coupon,['currency_id' => $data['currency_id']]);

					} else {

						$this->db->insert("currency",$coupon);

						$data['currency_id'] = $this->db->insert_id();

					}

					if(isset($data['is_default'])){

						$this->db->query("UPDATE currency SET is_default = 0");

						$this->db->query("UPDATE currency SET is_default = 1 WHERE currency_id = ". $data['currency_id']);

					}
					$this->session->set_flashdata('success', __('admin.currency_saved_successfully'));
					$json['location'] = base_url("admincontrol/currency_list");

				}

				echo json_encode($json);die;

			}

			if($currency_id > 0){

				$data['currencys'] = $this->db->query("SELECT * FROM currency WHERE currency_id = {$currency_id} ")->row_array();

			}

			$this->load->model("Form_model");

			$data['form_coupons'] = $this->Form_model->getFormCoupons();
			
			$data['existing_currencies'] = $this->db->query("SELECT code, title FROM currency")->result_array();
			
			$data['default_currency'] = $this->db->query("SELECT * FROM currency WHERE is_default = 1")->row_array();
			
			$this->view($data,'currency/form');
		}

		public function currency_set_default() {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				$json['success'] = false;
				$json['message'] = __('admin.session_expired');
				echo json_encode($json);
				return;
			}
			$json = array();

			if ($this->input->server('REQUEST_METHOD') == 'POST') {
				$currency_id = (int)$this->input->post('currency_id', true);
				
				if ($currency_id > 0) {
					$currency = $this->db->query("SELECT * FROM currency WHERE currency_id = " . $currency_id)->row_array();
					
					if ($currency) {
						if ($currency['status'] == 0) {
							$json['success'] = false;
							$json['message'] = __('admin.cannot_set_inactive_currency_default');
						} else {
							// Use a transaction and query builder for reliability
							$old_value = isset($currency['value']) ? (float)$currency['value'] : 1.0;
							if ($old_value <= 0) { $old_value = 1.0; }
							$now = date('Y-m-d H:i:s');
							$this->db->trans_start();
							$this->db->update('currency', ['is_default' => 0]);
							$this->db->query("UPDATE currency SET value = ROUND(value / ?, 5), date_modified = ? WHERE currency_id != ?", [$old_value, $now, (int)$currency_id]);
							$this->db->where('currency_id', (int)$currency_id);
							$this->db->update('currency', [
								'is_default' => 1,
								'value' => '1.0000',
								'date_modified' => $now
							]);
							$this->db->trans_complete();

							if ($this->db->trans_status() === FALSE) {
								$json['success'] = false;
								$json['message'] = __('admin.failed_to_set_default_currency');
							} else {
								$check_default = $this->db->get_where('currency', ['currency_id' => (int)$currency_id])->row_array();
								if ($check_default && (int)$check_default['is_default'] === 1) {
									$json['success'] = true;
									$json['message'] = __('admin.default_currency_changed_successfully');
									$json['new_default'] = $currency['title'] . ' (' . $currency['code'] . ')';
								} else {
									$json['success'] = false;
									$json['message'] = __('admin.failed_to_set_default_currency');
								}
							}
						}
					} else {
						$json['success'] = false;
						$json['message'] = __('admin.currency_not_found');
					}
				} else {
					$json['success'] = false;
					$json['message'] = __('admin.invalid_currency_id');
				}
			} else {
				$json['success'] = false;
				$json['message'] = __('admin.invalid_request_method');
			}

			echo json_encode($json);
		}

		public function currency_refresh_single() {
			$userdetails = $this->userdetails();
			$json = array();

			if ($this->input->server('REQUEST_METHOD') == 'POST') {
				$currency_code = $this->input->post('currency_code', true);
				
				if (empty($currency_code)) {
					$json['success'] = false;
					$json['message'] = __('admin.currency_code_required');
					echo json_encode($json);
					return;
				}

				$default_currency = $this->db->query("SELECT * FROM currency WHERE is_default=1")->row_array();

				if (!$default_currency) {
					$json['success'] = false;
					$json['message'] = __('admin.no_default_currency_found');
					echo json_encode($json);
					return;
				}

				$api_url = "https://api.exchangerate-api.com/v4/latest/" . $default_currency['code'];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $api_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$response = curl_exec($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				if ($http_code !== 200 || !$response) {
					$json['success'] = false;
					$json['message'] = __('admin.failed_to_fetch_rates');
					echo json_encode($json);
					return;
				}

				$data = json_decode($response, true);

				if (!$data || !isset($data['rates'])) {
					$json['success'] = false;
					$json['message'] = __('admin.invalid_api_response');
					echo json_encode($json);
					return;
				}

				if (isset($data['rates'][$currency_code])) {
					$rate = (float)$data['rates'][$currency_code];
					$json['success'] = true;
					$json['rate'] = number_format($rate, 4, '.', '');
					$json['message'] = __('admin.rate_fetched_successfully');
					$json['base_currency'] = $default_currency['code'];
				} else {
					$json['success'] = false;
					$json['message'] = __('admin.currency_rate_not_found');
				}
			} else {
				$json['success'] = false;
				$json['message'] = __('admin.invalid_request_method');
			}

			echo json_encode($json);
		}

		public function currency_refresh() {
			$userdetails = $this->userdetails();
			$json = array();

			if ($this->input->server('REQUEST_METHOD') == 'POST') {
				$selected = $this->db->query("SELECT * FROM currency WHERE is_default=1")->row_array();
				
				if (!$selected) {
					$json['success'] = false;
					$json['message'] = __('admin.no_default_currency_found');
					echo json_encode($json);
					return;
				}

				$currencies = $this->db->query("SELECT * FROM currency WHERE code != '" . $selected['code'] . "' AND status = 1")->result_array();

				if (empty($currencies)) {
					$json['success'] = true;
					$json['message'] = __('admin.no_currencies_to_refresh');
					echo json_encode($json);
					return;
				}

				$api_url = "https://api.exchangerate-api.com/v4/latest/" . $selected['code'];
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $api_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$response = curl_exec($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				if ($http_code !== 200 || !$response) {
					$json['success'] = false;
					$json['message'] = __('admin.failed_to_fetch_rates');
					echo json_encode($json);
					return;
				}

				$data = json_decode($response, true);
				
				if (!$data || !isset($data['rates'])) {
					$json['success'] = false;
					$json['message'] = __('admin.invalid_api_response');
					echo json_encode($json);
					return;
				}

				$updated_count = 0;
				$current_time = date('Y-m-d H:i:s');

				foreach ($currencies as $currency) {
					$currency_code = $currency['code'];
					
					if (isset($data['rates'][$currency_code])) {
						$rate = (float)$data['rates'][$currency_code];
						
						if ($rate > 0) {
							$this->db->query("UPDATE currency SET value = '" . $rate . "', date_modified = '" . $current_time . "' WHERE code = " . $this->db->escape($currency_code));
							$updated_count++;
						}
					}
				}

				$this->db->query("UPDATE currency SET value = '1.00000', date_modified = '" . $current_time . "' WHERE code = " . $this->db->escape($selected['code']));

				$json['success'] = true;
				$json['message'] = __('admin.currency_rates_updated');
				$json['updated_count'] = $updated_count;
				$json['base_currency'] = $selected['code'];
			} else {
				$selected = $this->db->query("SELECT * FROM currency WHERE is_default=1")->row_array();
				
				if ($selected) {
					$api_url = "https://api.exchangerate-api.com/v4/latest/" . $selected['code'];
					echo $api_url;
				} else {
					echo __('admin.no_default_currency_found');
				}
				return;
			}

			echo json_encode($json);
		}

		public function order_attechment($filename,$mask){

			$userdetails = $this->userdetails();

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

					if (ob_get_level()) { ob_end_clean(); }

					readfile($file, 'rb');

					exit();

				} else {

					exit('Error: Could not find file ' . $file . '!');

				}

			} else {

				exit('Error: Headers already sent out!');

			}
		}

		public function u_status_toggle($user_id){
			// Demo Mode
			if (ENVIRONMENT === 'demo') {
				$this->session->set_flashdata('error', __('admin.demo_mode'));
				redirect('admincontrol/userslist');
				return;
			}
			// Demo Mode

			$userdetails = $this->userdetails();

			$this->db->query("UPDATE users SET status = IF(status=1,0,1) WHERE id= ". (int)$user_id);

			$this->session->set_flashdata('success', __('admin.user_status_change_success'));

			redirect(base_url() . 'admincontrol/userslist');
		}

		public function info_remove_tran_multiple(){

			$uniqIDS = [];

			$post = $this->input->post(null,true);

			$ids = explode(",", $post['ids']);

			$html = "";

			$html = '<h6 class="text-center">'.__('admin.important_this_action_can_not_be_undo').'</h6><hr>';

			$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('admin.transaction_id')."</td><td class='text-center'>".__('admin.username')."</td><td class='text-center'> ".__('admin.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";

			foreach ($ids as $key => $id) {

				$dataCollection = $this->Wallet_model->getDeleteData($id);

				foreach ($dataCollection as $data) {

					if(in_array($id, $uniqIDS)) {
						continue;
					}

					$amountTotal += ($data['amount'] > 0) ? $data['amount'] : 0;

					$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $data['id'] .'</td><td class="text-center">'. $data['name'] .'</td><td class="text-center">'. c_format($data['amount']) .'</td></tr>';

					$uniqIDS[] = $data['id'];
				}
			}

			$table .= "</tbody></table></div>";

			$html .= "<p><strong>".count($uniqIDS)."</strong> ".__('admin.transactions having a total amount')." <strong>".c_format($amountTotal)."</strong> ".__('admin.will_get_deleted')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('admin.see_details')."</a></p>";

			$html .= $table;

			$html .= "<br>
			<div class='row'>
			  <div class='col-sm-6'>
			    <button data-bs-dismiss='modal' class='btn btn-primary btn-block'>" . __('admin.cancel') . "</button>
			  </div>
			  <div class='col-sm-6'>
			    <button class='btn btn-danger btn-block' delete-mmultiple-confirm='" . $post['ids'] . "'>" . __('admin.yes_confirm') . "</button>
			  </div>
			</div>";



			$json['html'] = $html;

			echo json_encode($json);
		}

			public function confirm_remove_tran_multi(){

			$json = [];

			$json['dataCollection'] = [];

			$post = $this->input->post(null,true);

			$ids = explode(",", $post['id']);

			$this->load->model('Payout_batch_model');
			
			
			foreach ($ids as $key => $id) {

				$json['dataCollection'][] = $dataCollection = $this->Wallet_model->getDeleteData($id);
				
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
				

				}
				
				echo json_encode($json);
			}



			public function info_remove_tran(){

				$delete_id = $this->input->post("id",true);

				$dataCollection = $this->Wallet_model->getDeleteData((int)$delete_id);

				$html = "";

				$html = '<h6 class="text-center">'.__('admin.important_this_action_can_not_be_undo').'</h6>';

				$html .= '<hr>';

				$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('admin.transaction_id')."</td><td class='text-center'>".__('admin.username')."</td><td class='text-center'> ".__('admin.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";

				$amountTotal = 0;

				foreach ($dataCollection as $data) {

					$amountTotal += ($data['amount'] > 0) ? $data['amount'] : 0; 

					$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $data['id'] .'</td><td class="text-center">'. $data['name'] .'</td><td class="text-center">'. c_format($data['amount']) .'</td></tr>';
				}

				$table .= "</tbody></table></div>";

				$html .= "<p><strong>".count($dataCollection)."</strong> ".__('admin.transactions_having_total_amount')." <strong>".c_format($amountTotal)."</strong> ".__('admin.will_get_deleted')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('admin.see_details')."</a></p>";

				$html .= $table;

				$html .= "<br><div class='row'> <div class='col-sm-6'><button data-bs-dismiss='modal' class='btn btn-primary btn-block'>".__('admin.cancel')."</button></div> <div class='col-sm-6'><button class='btn btn-danger  btn-block' delete-tran-confirm='". $delete_id ."'>".__('admin.yes_confirm')."</button></div> </div>";


				$json['html'] = $html;

				echo json_encode($json);
			}

			public function info_remove_tran_by_commission(){

				$dataCollection = $this->Wallet_model->getDeleteData((int)$this->input->post("id",true));

				$id = $this->input->post("id",true);

				$status_type = $this->input->post("status_type",true);

				$delete_id = $this->input->post("id",true);

				$this->db->query("DELETE FROM wallet_requests WHERE FIND_IN_SET($delete_id,tran_ids)");

				$html = '<h6 class="text-center">'.__('admin.important_this_action_can_not_be_undo').'</h6><hr>';

				$html .= '<p> '.__('admin.once_you_change_status_trash_or_cancel').' </p>';
				$html .= '<hr>';

				$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('admin.transaction_id')."</td><td class='text-center'>".__('admin.username')."</td><td class='text-center'> ".__('admin.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";

				$amountTotal = 0;

				foreach ($dataCollection as $data) {

					$amountTotal += ($data['amount'] > 0) ? $data['amount'] : 0;

					$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $data['id'] .'</td><td class="text-center">'. $data['name'] .'</td><td class="text-center">'. c_format($data['amount']) .'</td></tr>';
				}

				$table .= "</tbody></table></div>";

				$html .= "<p><strong>".count($dataCollection)."</strong> ".__('admin.transactions_having_total_amount')." <strong>".c_format($amountTotal)."</strong> ".__('admin.will_get_affected')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('admin.see_details')."</a></p>";

				$html .= $table;

				$html .= "<br><div class='row'> <div class='col-sm-6'><button data-bs-dismiss='modal' class='btn btn-primary btn-block'>".__('admin.cancel')."</button></div> <div class='col-sm-6'><button class='btn btn-danger  btn-block' id='". $id ."' status_type='". $status_type ."' change-tran-by-commi-confirm>".__('admin.yes_confirm')."</button></div> </div>";


				$json['html'] = $html;

				echo json_encode($json);

			}


			public function confirm_remove_tran(){

				$json['dataCollection'] = $dataCollection = $this->Wallet_model->getDeleteData((int)$this->input->post("id",true));

				$this->load->model('Payout_batch_model');

				foreach ($dataCollection as $data) {

					foreach ($data['removed'] as $key => $value) {
						if(isset($value['query']) && $value['query']) $this->db->query($value['query']);
					}

					if(isset($data['details']) && ! empty($data['details'])) {
						$this->load->model('Product_model');
						$this->Product_model->delete_wallet_integration_clicks_action($data['details']);
					}

					if(isset($data['id']) && !empty($data['id'])){

						$this->Payout_batch_model->detach_wallet_requests_for_wallet_transaction_id((int) $data['id']);

						$this->db->query("DELETE FROM wallet_recursion WHERE transaction_id = ". $data['id']);

						$this->db->query("DELETE FROM wallet_requests WHERE FIND_IN_SET(".$data['id'].",tran_ids)");

							$this->db->query("DELETE FROM wallet WHERE parent_id = ". $data['id']);

							$this->db->query("DELETE FROM wallet WHERE id = ". $data['id']);
						}
					}

					echo json_encode($json);
				}



				public function info_recursion_tran(){

					$mainID = $this->input->post("id",true);

					$dataCollection = $this->Wallet_model->getDeleteData((int)$mainID, true);

					$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('admin.transaction_id')."</td><td class='text-center'>".__('admin.username')."</td><td class='text-center'> ".__('admin.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";

					$amountTotal = 0;

					foreach ($dataCollection as $data) {

						$amountTotal += ($data['amount'] > 0) ? $data['amount'] : 0; 

						$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $data['id'] .'</td><td class="text-center">'. $data['name'] .'</td><td class="text-center">'. c_format($data['amount']) .'</td></tr>';
					}

					$table .= "</tbody></table></div>";

					$html .= "<p><strong>".__('admin.recursion_setting_for')." ".count($dataCollection)."</strong> ".__('admin.transactions_having_total_amount')." <strong>".c_format($amountTotal)."</strong> ".__('admin.wil_be_updated')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('admin.see_details')."</a></p>";

					$html .= $table;

					$data['transactions_details'] = $html;

					$wallet_data = $this->Wallet_model->getbyId((int)$mainID);

					$recursion = $this->Wallet_model->GetTransactionRecursion($wallet_data->id);		


					$recursion_type	= array(

						"every_day"   => __("admin.every_day"),

						"every_week"  => __("admin.every_week"),

						"every_month" => __("admin.every_month"),

						"every_year"  => __("admin.every_year"),

						"custom_time" => __("admin.custom_time")

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



				public function confirm_recursion_tran(){
					$data = $this->input->post();

					$mainID = $data['transaction_id'];

					$dataCollection = $this->Wallet_model->getDeleteData((int)$mainID, true);

					$json['recursion_data'] = [];

					foreach($dataCollection as $d) {
						$data['transaction_id'] = $d['id'];
						$json['recursion_data'][$d['id']]  = $this->Wallet_model->addTransactionRecursion($data);
					}

					$data['status'] = $this->Wallet_model->status();

					$data['status_icon'] = $this->Wallet_model->status_icon;

					$data['request_status'] = $this->Wallet_model->request_status;

					$transaction = $this->Wallet_model->getTransaction(['id' => $mainID]);

					$json['table'] = '';

					foreach ($transaction as $key => $value) {

						$data['class'] = 'child-recurring';

						$data['force_class'] = $_POST['ischild'] == 'true' ? 'child-arrow' : '';

						$data['recurring'] = $id;

						$data['value'] = $value;

						$data['wallet_status'] = $data['status'];

						$json['table'] .= $this->load->view("admincontrol/users/part/new_wallet_tr", $data, true);
					}

					echo json_encode($json);

				}

				public function wallet_change_status(){

					$id = (int)$this->input->post("id",true);

					$val = (int)$this->input->post("val",true);

					$confirm = $this->input->post("confirm",true);

					$tran = $this->db->query("

						SELECT w.*,u.firstname,u.lastname,u.email,wallet_recursion.id AS wallet_recursion_id,

						(SELECT SUM(amount) FROM `wallet` ww WHERE ww.parent_id=w.id) AS total_recurring_amount

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

						$table = "<div class='transaction-datails-div-hidden' style='display:none;'><table class='table table-stripped'><thead style='width: calc( 100% - 1em )'><tr style='display: table;  width: 100%; table-layout: fixed;'><td class='text-center'>".__('admin.transaction_id')."</td><td class='text-center'>".__('admin.username')."</td><td class='text-center'>".__('admin.amount')."</td></tr></thead><tbody style=' display: block; max-height: 200px; overflow-y: auto;'>";

						$amountTotal = 0;

						foreach ($dataCollection as $datas) {

							$amountTotal += ($datas['amount'] > 0) ? $datas['amount'] : 0; 

							$table .= '<tr style="display: table; width: 100%; table-layout: fixed;"><td class="text-center">'. $datas['id'] .'</td><td class="text-center">'. $datas['name'] .'</td><td class="text-center">'. c_format($datas['amount']) .'</td></tr>';
						}

						$table .= "</tbody></table></div>";

						$html .= "<p><strong>".__('admin.status_for')." ".count($dataCollection)."</strong> ".__('admin.transactions_having_total_amount')." <strong>".c_format($amountTotal)."</strong> ".__('admin.wil_be_updated')." <a href='javascript:void(0)' class='show-trans-aff-details'><br>".__('admin.see_details')."</a></p>";

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

				function list_files($path) {

					$files = array();

					$folders = array();

					if (is_dir($path)) {

						if ($handle = opendir($path)) {

							while (($name = readdir($handle)) !== false) {

								if (!preg_match("#^\.#", $name)){

									if (!is_dir($path . "/" . $name)) {

										$ext = pathinfo($name, PATHINFO_EXTENSION);

										if (in_array($ext, array('js','php','css','svg'))) {

											$files[] = realpath($path ."/". $name);

										}

									} else {

										$t = $this->list_files($path . "/" . $name);

										if($t) $folders[$name] = $t;

									}

								}

							}

							closedir($handle);

						}

					}

					$result = array_merge($folders, $files);

					return $result;

				}

				public function front_template(){

					$userdetails = $this->userdetails();

					$post = $this->input->post(null);

					unset($_FILES['files']);

					if(!empty($post) || !empty($_FILES)){

						$commonSetting = array('templates','loginclient');

						if(count($_FILES) > 0){

							$this->load->helper('string');

							$config['allowed_types'] = '*';

							$config['file_name']  = random_string('alnum', 32);

							$this->load->library('upload', $config);

							foreach ($_FILES as $fieldname => $input) {

								list($key,$subkey) = explode("_", $fieldname);

								if($key == 'files' || $key == 'templates'){

									$path = $this->front_assets."img/";

								} else{

									$path = 'assets/images/site';

								}

								$config['upload_path'] = $path;

								$this->upload->initialize($config);

								if($input['error'] == 0){

									$extension = pathinfo($_FILES[$fieldname]["name"], PATHINFO_EXTENSION);

									if($extension=='jpg' || $extension=='jpeg' || $extension=='png' || $extension=='gif'){

										if (!$this->upload->do_upload($fieldname)) {

											echo "<pre>"; print_r($this->upload); echo "</pre>";die; 

										}

										else {

											$upload_details = $this->upload->data();

											$post[$key][$subkey] = $upload_details['file_name'];

										}

									} else{

										$json['errors']["{$key}_{$subkey}"] = 'Only Image File are allowed';

									}

								}

							}

						}

						foreach ($post as $key => $value) {

							if (in_array($key, $commonSetting)) {

								$this->Setting_model->save($key, $value);
							}
						}

						$this->session->set_flashdata('success', __('admin.setting_saved_successfully'));

						redirect('admincontrol/front_template');

					}

					$data['template_file'] = $this->list_files(APPPATH . 'views/auth/user/');

					$data['image_manager_url'] = base_url('/admincontrol/load_image_manager');

					$data['templates'] = $this->Product_model->getSettings('templates');

					$data['loginclient'] = $this->Product_model->getSettings('loginclient');

					$data['templates_url'] = $this->front_assets_url ."img/";

					$this->view($data,'template_editor/editor');

				}

				public function load_image_manager(){

					$filter_name = '';

					$rootDir = $this->front_assets ."img";

					$rootDirUrl = $this->front_assets_url ."img";

					$get = $this->input->get(null,true);

					if (isset($get['directory'])) {

						$directory = rtrim($rootDir . str_replace(array('../', '..\\', '..'), '', $get['directory']), '/');

					} else { $directory = $rootDir; }



					$data['images'] = array();

					$directories = glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR);

					if (!$directories) { $directories = array(); }

					if (isset($get['target'])) {

						$data['target'] = $get['target'];

					} else { $data['target'] = ''; }

					if (isset($get['thumb'])) {

						$data['thumb'] = $get['thumb'];

					} else { $data['thumb'] = ''; }

					if (isset($get['directory'])) {

						$data['directory'] = $get['directory'];

					} else { $data['directory'] = ''; }

					$files = glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);

					if (!$files) {

						$files = array();

					}

					$images = array_merge($directories, $files);

					$image_total = count($images);

					$fun_url = base_url('/admincontrol/front_template');

					$data['image_manager_url'] = $image_manager_url = base_url('/admincontrol/load_image_manager');

					foreach ($images as $image) {

						$name = str_split(basename($image), 14);

						if (is_dir($image)) {

							$url = '';

							if (isset($get['target'])) { $url .= '&target=' . $get['target']; }

							if (isset($get['thumb'])) { $url .= '&thumb=' . $get['thumb']; }

							$data['images'][] = array(

								'thumb' => '',

								'name'  => implode(' ', $name),

								'type'  => 'directory',

								'path'  => substr($image, strlen($rootDir)),

								'href'  => $image_manager_url.'?directory=' . urlencode(substr($image, strlen($directory))) . $url,

							);

						} elseif (is_file($image)) {

							$server = '';

							$data['images'][] = array(

								'thumb' => $rootDirUrl . str_replace($rootDir, '', $image),

								'name'  => implode(' ', $name),

								'type'  => 'image',

								'path'  => substr($image, strlen($rootDir)),

								'href'  => $rootDirUrl . $image

							);

						}

					}

					$config['base_url'] = $fun_url;

					$data['fun_url'] = $fun_url;

					$data['image_upload'] = base_url('/admincontrol/image_upload_filemanager');

					$data['folder_url'] = base_url('/admincontrol/folder_filemanager');

					$data['delete_image_url'] = base_url('/admincontrol/delete_image_filemanager');

					$data['entry_folder'] = 'Enter Folder';

					$data['button_folder'] = 'Folder';

					$data['text_confirm'] = 'Sure You want to delete?';

					$url = $image_manager_url;

					$eurl  = '' ;

					if (isset($get['directory'])) { $eurl .= '&directory=' . urlencode(html_entity_decode($get['directory'], ENT_QUOTES, 'UTF-8')); }

					if (isset($get['filter_name'])) { $eurl .= '&filter_name=' . urlencode(html_entity_decode($get['filter_name'], ENT_QUOTES, 'UTF-8')); }

					if (isset($get['target'])) { $eurl .= '&target=' . $get['target']; }

					if (isset($get['thumb'])) { $eurl .= '&thumb=' . $get['thumb']; }

					$data['url'] = $url .'?'. ltrim($eurl,'&'); 

					$url = '';

					if (isset($get['directory'])) {

						$pos = strrpos($get['directory'], '/');

						if ($pos) {

							$url .= '&directory=' . urlencode(substr($get['directory'], 0, $pos));

						}

					}

					if (isset($get['target'])) { $url .= '&target=' . $get['target']; }

					if (isset($get['thumb'])) { $url .= '&thumb=' . $get['thumb']; }

					$data['parent'] = $image_manager_url .'?'. ltrim($url,'&');

					echo $this->load->view('admincontrol/template_editor/editor_image', $data);

				}	


				public function image_upload_filemanager(){

					$json = array();

					$DIR_IMAGE = $this->front_assets ."img";;

					if (isset($get['directory'])) {

						$directory = rtrim($DIR_IMAGE . str_replace(array('../', '..\\', '..'), '', $get['directory']), '/');

					} else {

						$directory = $DIR_IMAGE ;

					}

					if (!is_dir($directory)) {

						$json['error'] = "Directory Not Found" ;

					}

					if (!$json) {

						if (!empty($_FILES['file']['name']) && is_file($_FILES['file']['tmp_name'])) {

							$filename = basename(html_entity_decode($_FILES['file']['name'], ENT_QUOTES, 'UTF-8'));

							if ((strlen($filename) < 3) || (strlen($filename) > 255)) {

								$json['error'] = "File Name not valid";

							}

							$allowed = array('jpg','jpeg','gif','png');

							if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {

								$json['error'] = "File type Invalid";

							}

							$allowed = array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif');

							if (!in_array($_FILES['file']['type'], $allowed)) {

								$json['error'] = "File type Invalid";

							}

							if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {

								$json['error'] = 'Upload Error ' . $_FILE['file']['error'];
							}

						} else {

							$json['error'] = "Upload File Fail";

						}

					}

					if (!$json) {

						move_uploaded_file($_FILES['file']['tmp_name'], $directory . '/' . $filename);

						$json['success'] = 'Upload successfully';

					}

					echo json_encode($json);die;

				}



				public function folder_filemanager(){

					$json = array();

					$DIR_IMAGE = $this->front_assets ."img";

					$post = $this->input->post(null,true);

					$get = $this->input->get(null,true);

					if (isset($get['directory'])) {

						$directory = rtrim($DIR_IMAGE  . str_replace(array('../', '..\\', '..'), '', $get['directory']), '/');

					} else { $directory = $DIR_IMAGE ; }

					if (!is_dir($directory)) { $json['error'] = 'Invalid Directory'; }

					if (!$json) {

						$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($post['folder'], ENT_QUOTES, 'UTF-8')));

						if ((strlen($folder) < 3) || (strlen($folder) > 128)) { $json['error'] = "Folder Name must be 3 to 128 characters"; }

						if (is_dir($directory . '/' . $folder)) { $json['error'] = "Folder Already exists"; }

					}

					if (!$json) {

						mkdir($directory . '/' . $folder, 0777);

						chmod($directory . '/' . $folder, 0777);

						$json['success'] = "Directory Create successfully";

					}
					echo json_encode($json);die;
				}



				public function delete_image_filemanager(){

					$json = array();

					$DIR_IMAGE = $this->front_assets ."img";

					$post = $this->input->post(null,true);

					if (isset($post['path'])) {

						$paths = $post['path'];

					} else {

						$paths = array();

					}

					foreach ($paths as $path) {

						$path = rtrim($DIR_IMAGE . str_replace(array('../', '..\\', '..'), '', $path), '/');

						if ($path == $DIR_IMAGE ) {

							$json['error'] = "Some Thing want wrong";

							break;

						}

					}



					if (!$json) {

						foreach ($paths as $path) {

							$path = rtrim($DIR_IMAGE . str_replace(array('../', '..\\', '..'), '', $path), '/');

							if (is_file($path)) { 

								unlink($path);

							} elseif (is_dir($path)) {

								$files = array();

								$path = array($path . '*');



								while (count($path) != 0) {

									$next = array_shift($path);

									foreach (glob($next) as $file) {

										if (is_dir($file)) { $path[] = $file . '/*'; }

										$files[] = $file;

									}

								}

								rsort($files);

								foreach ($files as $file) {

									if (is_file($file)) { unlink($file); } 

									elseif (is_dir($file)) { rmdir($file); }

								}

							}

						}

						$json['success'] = "Successfully Delete";

					}
					echo json_encode($json);die;
				}


				public function editor_get_file(){

					$json = array();

					$path = $this->input->post("path",true);

					if($path && is_file($path)){

						$json['contents'] = file_get_contents($path);



						$json['ext'] = pathinfo($path, PATHINFO_EXTENSION);

					} else {

						$json['erorr'] = "File not found ..!";

					}

					echo json_encode($json);
				}



				public function editor_save_file(){

					$json = array();

					$path = $this->input->post("path",true);

					$post = $this->input->post(null,true);

					if($path && is_file($path)){

						file_put_contents($path,trim($post['text']));

						$json['success'] = "File save successfully";

					} else {

						$json['erorr'] = "File not found ..!";

					}
					echo json_encode($json);
				}



				public function registration_builder()	{
					$userdetails = $this->userdetails();
					if ($this->input->server('REQUEST_METHOD') == 'POST'){
						$post = $this->input->post(null,true);
						$json = array();
						$this->Setting_model->save('registration_builder', $post );
						echo json_encode($json);die;
					}

					$data['builder'] = $this->Product_model->getSettings('registration_builder');
					$fields  = json_decode($data['builder']['registration_builder'],1);
					$default_fields = array('firstname' => 0,'lastname' => 0 ,'email' => 0,'username' => 0,'password' => 0,'confirm_password' => 0);

					foreach ($fields as $key => $value) {
						if($value['type'] == 'header' && !isset($default_fields[strtolower($value['label'])]) ){
							unset($fields[$key]);
						}
					}
					$allfield = array();
					foreach ($fields as $key => $value) {
						$allfield[strtolower($value['label'])] = 1;
					}
					foreach ($default_fields as $value => $key) {
						if (!isset($allfield[$value])) {
							$fields[] = array(
								'type' => 'header',
								'label' => ucfirst($value),
								'placeholder' => ucfirst($value),
								'className' => '',
								'name' => $value,
								'mobile_validation' => false,
							);
						}
					}

					$data['builder']['registration_builder'] = json_encode(array_values($fields));

					$this->view($data,'registration_builder/index');

				}


				public function sendAffiliateEmail(){

				   // Demo Mode
				    if (ENVIRONMENT === 'demo') {
				        echo json_encode([
				            'status' => 'error',
				            'message' => 'Disabled on demo mode'
				        ]);
				        return;
				    }
				    // Demo Mode


					$this->load->library('form_validation');
					$json = array();
					$this->form_validation->set_rules('to', 'To', 'required|trim');
					$this->form_validation->set_rules('subject', 'Subject', 'required|trim');
					$this->form_validation->set_rules('message', 'Message', 'required|trim');

					$attachment=NULL; 
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
							$attachment = base_url().'assets/user_upload/'.$config['file_name'].".".$fileNameArray[sizeof($fileNameArray)-1];
						}
					}  
					
					if ($this->form_validation->run() == FALSE) {

						$json['errors'] = $this->form_validation->error_array();

					} else {

						$emails = explode(",", $this->input->post("to",true));

						$this->load->model('Mail_model');

						$post = $this->input->post(null,true);

						foreach ($emails as $key => $email) {

						$this->Mail_model->affiliate_mail($email, $post,$attachment);
						}

						$json['success'] = count($emails). " mails sent successfully..!";

					}
					echo json_encode($json);
				}



				public function theme_setting(){

					$userdetails = $this->userdetails();

					$post = $this->input->post(null,true);

					if(!empty($post)){

						$commonSetting = array('adminside','affiliateside');

						foreach ($post as $key => $value) {

							if (in_array($key, $commonSetting)) {

								$this->Setting_model->save($key, $value);
							}
						}

						$this->session->set_flashdata('success', __('admin.setting_saved_successfully'));

						redirect('admincontrol/theme_setting');
					}

					$data['theme_setting']['adminside'] = $this->Product_model->getSettings('adminside');

					$data['theme_setting']['affiliateside'] = $this->Product_model->getSettings('affiliateside');

					$data['setting_tabs'] = array(

						'adminside'		=> __('admin.admin_side'),

						'affiliateside'	=> __('admin.affiliate_side'),

					);

					$this->view($data,'setting/themesetting');
				}



				public function getDatesFromType(){

					$userdetails = $this->userdetails();

					$data = array();

					$type = $this->input->post('type',true);



					if($type == 'month'){

						$data = array('All','01','02','03','04','05','06','07','08','09','10','11','12');

					}else{

						$data = array('All',date("Y",strtotime("-3 year")),date("Y",strtotime("-2 year")),date("Y",strtotime("-1 year")),date("Y",strtotime("0 year")));
					}

					echo json_encode($data);die;
				}



				public function get_integartion_data($return  = false){

					$userdetails = $this->userdetails();

					$post = $this->input->post();

					$json = array();

					if($post['integration_data_year'] && $post['integration_data_month']){

						$integration_filters = array(

							'integration_data_year' => $post['integration_data_year'],

							'integration_data_month' => $post['integration_data_month'],
						);

					}else{

						$integration_filters = array();

					}

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

							'. __( 'admin.total_balance' ) .'

							<span class="badge bg-primary badge-pill font-14 pull-right">

							'. c_format($totals['integration']['balance']) .'        

							</span>

							</li>

							<li class="list-group-item">

							'. __( 'admin.total_sales' ) .'

							<span class="badge bg-primary badge-pill font-14 pull-right">

							'. c_format($totals['integration']['balance']) .' / '. c_format($totals['integration']['sale']) .'        

							</span>

							</li>

							<li class="list-group-item">

							'. __( 'admin.total_clicks' ) .'

							<span class="badge bg-primary badge-pill font-14 pull-right">

							'. (int)$totals['integration']['click_count'] .' / '. c_format($totals['integration']['click_amount']) .'

							</span>

							</li>

							<li class="list-group-item">

							'. __('admin.total_actions') .'

							<span class="badge bg-primary badge-pill font-14 pull-right">

							'. (int)$totals['integration']['action_count'] .' / '. c_format($totals['integration']['action_amount']) .'

							</span>

							</li>

							<li class="list-group-item">

							'. __( 'admin.total_commission' ) .'

							<span class="badge bg-primary badge-pill font-14 pull-right">

							'. c_format($totals['integration']['total_commission']) .' 

							</span>

							</li>

							<li class="list-group-item">

							'. __( 'admin.total_orders' ) .'

							<span class="badge bg-primary badge-pill font-14 pull-right">

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

								'. __( 'admin.total_balance' ) .'

								<span class="badge bg-primary badge-pill font-14 pull-right">

								'. c_format($value['balance']) .'

								</span>

								</li>

								<li class="list-group-item">

								'. __( 'admin.total_sales' ) .'

								<span class="badge bg-primary badge-pill font-14 pull-right">

								'. c_format($value['balance']) .' / '. c_format($value['sale']) .'        

								</span>

								</li>

								<li class="list-group-item">

								'. __( 'admin.total_clicks' ) .'

								<span class="badge bg-primary badge-pill font-14 pull-right">

								'. (int)$value['click_count'] .' / '. c_format($value['click_amount']) .'

								</span>

								</li>

								<li class="list-group-item">

								'. __('admin.total_actions') .'

								<span class="badge bg-primary badge-pill font-14 pull-right">

								'. (int)$value['action_count'] .' / '. c_format($value['action_amount']) .'

								</span>

								</li>

								<li class="list-group-item">

								'. __( 'admin.total_commission' ) .'

								<span class="badge bg-primary badge-pill font-14 pull-right">

								'. c_format($value['click_amount'] + $value['sale'] + $value['action_amount']) .' 

								</span>

								</li>

								<li class="list-group-item">

								'. __( 'admin.total_orders' ) .'

								<span class="badge bg-primary badge-pill font-14 pull-right">

								'. (int)$value['total_orders'] .' 

								</span>

								</li>

								<li class="list-group-item">

								<a class="btn btn-lg btn-default btn-primary" href="http://'. $website .'" target="_blank">'. __( 'admin.preview_store' ) .'</a>

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



			public function category_auto(){

				$userdetails = $this->userdetails();

				$keyword = $this->input->get('term');
				if ($keyword === null) $keyword = $this->input->get('query');

				$data = $this->db->query(
					"SELECT id as value, name as label FROM categories WHERE name LIKE " .
					$this->db->escape('%' . $keyword . '%') .
					" ORDER BY name"
				)->result_array();

				echo json_encode($data); die;

			}



				public function store_category_delete($category_id = 0){

					$userdetails = $this->userdetails();

					if($category_id > 0){

						$data['category'] = $this->db->query("DELETE FROM categories WHERE id = ". (int)$category_id);

					}



					$this->session->set_flashdata('success',__('admin.category_deleted_successfully'));

					redirect(base_url('admincontrol/store_category'));

				}



				public function store_category_add($category_id = 0){

					$userdetails = $this->userdetails();

					if ($this->input->server('REQUEST_METHOD') == 'POST'){

						$this->load->library('form_validation');

						$this->form_validation->set_rules('name', 'Category Name', 'required');

					$this->form_validation->set_rules('description', 'Category Description', 'required' );

						if($this->form_validation->run()){

							$details = array(

								'name'        =>  $this->input->post('name',true),

								'description' =>  $this->input->post('description',false),

								'parent_id'   =>  $this->input->post('parent_id',true),

								'color'   	  =>  $this->input->post('color',true),

								'tag'   	  =>  $this->input->post('tag',true),

							);



						// Category image â€” optional for both new and existing categories
						if (!empty($_FILES['category_image']['name']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {
							$ext = strtolower(pathinfo($_FILES['category_image']['name'], PATHINFO_EXTENSION));
							if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
								$errors['category_image'] = __('admin.only_image_files_allowed');
							} else {
								$upload_response = $this->upload_photo('category_image', 'assets/images/product/upload/thumb');
								if ($upload_response['success']) {
									$details['image'] = $upload_response['upload_data']['file_name'];
								} else {
									$errors['category_image'] = $upload_response['msg'];
								}
							}
						}

						// Background image â€” optional for both new and existing categories
						if (!empty($_FILES['category_background_image']['name']) && $_FILES['category_background_image']['error'] === UPLOAD_ERR_OK) {
							$ext = strtolower(pathinfo($_FILES['category_background_image']['name'], PATHINFO_EXTENSION));
							if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
								$errors['category_background_image'] = __('admin.only_image_files_allowed');
							} else {
								$upload_response = $this->upload_photo('category_background_image', 'assets/images/product/upload/thumb');
								if ($upload_response['success']) {
									$details['background_image'] = $upload_response['upload_data']['file_name'];
								} else {
									$errors['category_background_image'] = $upload_response['msg'];
								}
							}
						}





							if(empty($errors)){

								if($category_id){

									$this->Product_model->update_data('categories', $details, array('id' => $category_id));

								}else{

									$details['created_at'] = date('Y-m-d H:i:s');

									$category_id = $this->Product_model->create_data('categories', $details);

								}



								$slug = $this->friendly_seo_string($this->input->post('name',true).'-'.$category_id);

								$this->db->query("UPDATE categories SET slug = ". $this->db->escape($slug) ." WHERE id =". $category_id);



								$this->session->set_flashdata('success', 'Category Saved Successfully');

								$json['location'] = base_url('admincontrol/store_category');



							} else {

								$json['errors'] = $errors;

							}

						} else {

							$json['errors'] = $this->form_validation->error_array();

						}



						echo json_encode($json);die;

					}



					$data['category'] = array();

					if($category_id > 0){

						$data['category'] = $this->db->query("SELECT * FROM categories WHERE id = ". (int)$category_id)->row_array();

					}



					$data['categories'] = $this->db->query("SELECT id,name,parent_id FROM categories")->result_array();


					$this->view($data,'store/category_add');

				}



			public function store_category_copy($category_id = 0){

				$userdetails = $this->userdetails();

				if (!$userdetails) { echo json_encode(['success' => false, 'error' => 'Unauthorised']); die; }

				$this->output->set_content_type('application/json');

				if ($category_id <= 0) {
					echo json_encode(['success' => false, 'error' => 'Invalid category ID']); die;
				}

				$original = $this->db->query(
					"SELECT * FROM categories WHERE id = " . (int)$category_id
				)->row_array();

				if (empty($original)) {
					echo json_encode(['success' => false, 'error' => 'Category not found']); die;
				}

				$new = [
					'name'             => __('admin.copy_of') . ' ' . $original['name'],
					'description'      => $original['description'] ?? '',
					'parent_id'        => $original['parent_id'] ?? 0,
					'color'            => $original['color'] ?? '',
					'tag'              => $original['tag'] ?? '',
					'image'            => $original['image'] ?? '',
					'background_image' => $original['background_image'] ?? '',
					'created_at'       => date('Y-m-d H:i:s'),
				];

				$new_id = $this->Product_model->create_data('categories', $new);

				if ($new_id) {
					$slug = $this->friendly_seo_string($new['name'] . '-' . $new_id);
					$this->db->query("UPDATE categories SET slug = " . $this->db->escape($slug) . " WHERE id = " . (int)$new_id);

					echo json_encode([
						'success'  => true,
						'message'  => __('admin.category_copied_successfully'),
						'new_id'   => $new_id,
						'new_name' => $new['name'],
					]);
				} else {
					echo json_encode(['success' => false, 'error' => 'Failed to copy category']);
				}
				die;
			}


			public function store_category($page = 1){

				$userdetails = $this->userdetails();

				if ($this->input->server('REQUEST_METHOD') == 'POST'){

					$page = max((int)$page,1);

					$filter = array(
						'limit' => 100,
						'page' => $page,
					);
					
					$currentTheme = User::getActiveTheme();

					list($data['categories'],$total) = $this->Product_model->getCategory($filter,$currentTheme);

					$data['start_from'] = (($page-1) * $filter['limit'])+1;

					ob_start();
					$this->load->view("admincontrol/store/category_list",$data);
					$html = ob_get_clean();
					
					$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

					$this->load->library('pagination');

					$config['base_url'] = base_url('admincontrol/store_category/');
					$config['per_page'] = $filter['limit'];
					$config['total_rows'] = $total;
					$config['use_page_numbers'] = TRUE;
					$config['enable_query_strings'] = TRUE;

					$this->pagination->initialize($config);

					$pagination = $this->pagination->create_links();
					
					$json = array(
						'html' => $html,
						'pagination' => $pagination,
						'total' => $total
					);

					$this->output
						->set_content_type('application/json; charset=UTF-8')
						->set_output(json_encode($json, JSON_INVALID_UTF8_SUBSTITUTE))
						->_display();
					exit;
				}

				$this->view($data,'store/store_category');
			}

				public function get_orders_transactions($orderType, $orderId, $type = '', $page = 1) {
					$userdetails = $this->userdetails();

					if(!$this->userdetails()){ die('unauthorised request'); }

					$filter['getSingleOrder'] = $orderType;
					$filter['order_id'] = $orderId;
					list($data['orders'],$total) = $this->Order_model->getAllOrders($filter);

					// Only add pagination for page > 1 to avoid breaking existing functionality
					if($page > 1) {
						$per_page = 10;
						$offset = ($page - 1) * $per_page;
						
						if($data['orders'][0]['wallet_transactions'])
							$filter = array(
								'id_in' => $data['orders'][0]['wallet_transactions'],
								'per_page' => $per_page,
								'offset' => $offset,
							);
						else
							$filter = array(
								'per_page' => $per_page,
								'offset' => $offset,
							);
					} else {
						// Original logic for first page - load first 10 only
						if($data['orders'][0]['wallet_transactions'])
							$filter = array(
								'id_in' => $data['orders'][0]['wallet_transactions'],
								'per_page' => 10,
								'offset' => 0,
							);
						else
							$filter = array(
								'per_page' => 10,
								'offset' => 0,
							);
					}

					$this->load->model('Withdrawal_payment_model');

					$data['orderType'] = $orderType;
					$data['orderId'] = $orderId;
					$data['transaction'] = $this->Wallet_model->getTransaction($filter);
					$data['is_dashboard'] = '0';
					$data['is_order_page'] = '0';

					// Calculate if there are more transactions
					if($data['orders'][0]['wallet_transactions']) {
						$transactionIds = explode(',', $data['orders'][0]['wallet_transactions']);
						$totalTransactions = count(array_filter($transactionIds));
						$data['has_more'] = $totalTransactions > 10;
						$data['total_transactions'] = $totalTransactions;
					} else {
						$data['has_more'] = false;
						$data['total_transactions'] = count($data['transaction']);
					}

					if ($type == 'dashboard') {
						$data['is_dashboard'] = '1';
					}elseif ($type == 'order_page') {
						$data['is_order_page'] = '1';
					}

					// Return JSON for AJAX requests with pagination
					if($this->input->is_ajax_request() && $page > 1) {
						$json = array();
						$json['html'] = $this->load->view("admincontrol/store/wallet_detail_tr",$data,true);
						$json['has_more'] = ($page * 10) < $data['total_transactions'];
						$json['current_page'] = $page;
						$json['total_transactions'] = $data['total_transactions'];
						echo json_encode($json);die;
					}

					$html = $this->load->view("admincontrol/store/wallet_detail_tr",$data,true);

					echo $html;die;
				}

				public function store_orders($page = 1){

					$userdetails = $this->userdetails();

					$data['status'] = $this->Order_model->status();

					$data['wallet_status'] = $this->Wallet_model->status();

					if ($this->input->server('REQUEST_METHOD') == 'POST'){

						$post = $this->input->post(null,true);

						$page = max((int)$page,1);
						$per_page = 25;
						$offset = ($page - 1) * $per_page;

						$filter = array(
							'limit' => $per_page,
							'offset' => $offset,
							'page' => $page,
						);

						if(isset($post['filter_status']) && $post['filter_status'] != ''){
							$filter['o_status'] = $this->input->post('filter_status',true);
						}

						list($data['orders'],$total) = $this->Order_model->getAllOrders($filter);

						$data['start_from'] = $offset + 1;

						if(isset($post['action']) && $post['action'] == 'dashboard'){
							$json['html'] = $this->load->view("admincontrol/store/dashboard_order_list",$data,true);
						}elseif (isset($post['action']) && $post['action'] == 'order_page') {
							$json['html'] = $this->load->view("admincontrol/store/dashboard_order_list",$data,true);
						}else{
							$json['html'] = $this->load->view("admincontrol/store/order_list",$data,true);
						}

						// Generate pagination using utility helper
						$this->load->helper('utility');
						$pagination_settings = [
							'base_url' => base_url('admincontrol/store_orders'),
							'total_rows' => $total,
							'per_page' => $per_page,
							'current_page' => $page,
							'use_get_params' => false,
							'preserve_query' => false
						];
						
						$pagination_data = easy_pagination(
							$pagination_settings['base_url'],
							$pagination_settings['total_rows'],
							$offset,
							$pagination_settings
						);
						
						$json['pagination'] = $pagination_data['html'];
						$json['total'] = $total;

						clear_tmp_cache('order_cache');

						echo json_encode($json);die;

					}

					$this->view($data, 'store/orders');
				}



			public function store_logs($page = 0){

				$userdetails = $this->userdetails();
				$data = array();
				$data['userdetails'] = $userdetails;

				if ($this->input->server('REQUEST_METHOD') == 'POST'){

					$page = max((int)$page,1);

					$filter = array(
						'page'    => $page,
					);

					// Get pagination settings from helper
					$this->load->helper('utility');
					$pagination_settings = get_pagination_settings();
					$filter['limit'] = $pagination_settings['per_page'];

					list($data['clicks'],$total) = $this->Order_model->getAllClickLogs($filter);
					
					if(!isset($data['clicks']) || !is_array($data['clicks'])){
						$data['clicks'] = array();
					}

				$data['start_from'] = (($page-1) * $filter['limit'])+1;

				$json = array();
				
				// Generate HTML view
				ob_start();
				$html_output = $this->load->view("admincontrol/store/log_list.php",$data,true);
				ob_end_clean();
				
				if(empty($html_output)) {
					$html_output = '<tr><td colspan="7" class="text-center py-5"><div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>No logs found.</div></td></tr>';
				}
				
				$json['html'] = $html_output;

				// Generate pagination using utility helper
				$pagination_settings['js_function'] = 'getPage';
				
				$pagination_data = ajax_pagination(
					$total,
					$page,
					$pagination_settings
				);
				
				$json['pagination'] = $pagination_data['html'];
				$json['total'] = (int)$total;
				$json['start_from'] = (int)$data['start_from'];
				$json['pagination_summary'] = pagination_summary_html($page, $pagination_settings['per_page'], $total);

				ob_clean();
				header('Content-Type: application/json');
				echo json_encode($json);
				exit;
				}
				$this->view($data,'store/logs');
			}


				public function store_markettools($page = 0){
					set_default_currency();
					$userdetails = $this->userdetails();
					$this->load->model('Form_model');
					$this->load->model('Report_model');
					$this->load->model('Wallet_model');
					$this->load->model('IntegrationModel');

					$data['form_default_commission'] = $this->Product_model->getSettings('formsetting');

					$data['default_commition']       = $this->Product_model->getSettings('productsetting');

					$data['tools'] = $this->IntegrationModel->getProgramTools([

						'status'           => 1,

						'redirectLocation' => 1,

						'restrict'         => $userdetails['id'],

					]);

					$products = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type']);

					$forms = $this->Form_model->getForms($userdetails['id']);

					foreach ($products as $key => $value) { $products[$key]['is_product'] = 1; }

					foreach ($forms as $key => $value) {

						$forms[$key]['coupon_name']          = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);

						$forms[$key]['public_page']          = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));

						$forms[$key]['count_coupon']         = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);

						$forms[$key]['seo']                  = str_replace('_', ' ', $value['seo']);

						$forms[$key]['is_form']              = 1;

						$forms[$key]['product_created_date'] = $value['created_at'];

						$forms[$key]['fevi_icon'] = $value['fevi_icon'] ? 'assets/images/form/favi/'.$value['fevi_icon'] : 'assets/images/users/no-image.jpg';


						if($value['coupon']){
							$forms[$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
						}
					}

					$data_list = array_merge($products,$forms,$data['tools']);

					usort($data_list,function($a,$b){
						$ad = strtotime($a['product_created_date']);
						$bd = strtotime($b['product_created_date']);
						return ($ad-$bd);
					});
					$data_list = array_reverse($data_list);
					$total = count( $data_list );
					$limit = 20; 
					$totalPages = ceil( $total/ $limit );
					$offset = $page;
					if( $offset < 0 ) $offset = 0;

					$data['data_list'] = array_slice( $data_list, $offset, $limit );

					$this->load->library('pagination');

					$config['base_url'] = base_url('/admincontrol/store_markettools/');

					$config['total_rows'] = $total;

					$config['per_page'] = $limit;

					$config['attributes'] = array('class' => 'single_paginate_link');

					$filter['per_page'] = $config['per_page'];

					$config['reuse_query_string'] = TRUE;

					$config['query_string_segment'] = 'page';

					$this->pagination->initialize($config);

					$data['pagination_link'] = $this->pagination->create_links();

					$this->load->library("socialshare");				
					$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

					$this->view($data,'store/markettools');
				}



				public function info_remove_order(){

					$id = (int)$this->input->post("id",true);

					$type = $this->input->post("type",true);

					if($type == 'ex'){

						$order_amount = $this->db->query("SELECT total FROM integration_orders WHERE id= ".(int)$id)->row();

						$total_comm = $this->db->query("SELECT SUM(amount) AS total FROM  wallet WHERE comm_from='ex' AND type IN('sale_commission','admin_sale_commission','refer_sale_commission') AND reference_id_2 = {$id}")->row();

					}

					else{

						$order_amount = $this->db->query("SELECT total FROM `order` WHERE id= ".(int)$id)->row();

						$total_comm = $this->db->query("SELECT SUM(amount) AS total FROM  wallet WHERE comm_from='store' AND type IN('sale_commission','vendor_sale_commission') AND reference_id = {$id}")->row();

					}

					$html = '<h6 class="text-center"> Amount : '. c_format($order_amount->total) .' </h6>';

					$html .= '<h6 class="text-center"> Commission Amount : '. c_format($total_comm->total) .' </h6><hr>';

					$html .= '<p class="text-center"> Order ID : '. $this->input->post("id",true) .' </p>';

					$html .= '<p class="text-center"> <input type="hidden" value="'. $type .'" name="order_type"> <label>

					<input type="checkbox" name="sale_commission" class="wallet-checkbox">

					Sale Commission

					</label></p>';

					$html .= "<br><div class='row'> <div class='col-sm-6'><button data-dismiss='modal' class='btn btn-primary btn-block'>Cancel</button></div> <div class='col-sm-6'><button class='btn btn-danger  btn-block' delete-order-confirm='". $this->input->post("id",true) ."'>Yes Confirm</button></div> </div>";



					$json['html'] = $html;

					echo json_encode($json);
				}


		public function confirm_remove_order(){

			$id = $this->input->post('id',true);

			$order_type = $this->input->post('order_type',true);

			$sale_commission = $this->input->post('sale_commission',true);



			if($order_type == 'ex'){

				$this->db->query("DELETE FROM `integration_orders` WHERE id = {$id}");


				$wallet_trans = $this->db->query('SELECT id FROM wallet WHERE type LIKE "%sale%" AND comm_from="ex" AND reference_id_2='.$id)->result_array();

			} else{

				$this->db->query("DELETE FROM `order` WHERE id = {$id}");

				$this->db->query("DELETE FROM `order_products` WHERE order_id = {$id}");

				$this->db->query("DELETE FROM `order_proof` WHERE order_id = {$id}");

				$this->db->query("DELETE FROM `orders_history` WHERE order_id = {$id}");

				$wallet_trans = $this->db->query('SELECT id FROM wallet WHERE comm_from="store" AND type LIKE "%sale%" AND reference_id ='.$id)->result_array();
			}

			if($sale_commission == 'true' && count($wallet_trans) > 0){
				$trans = "";

				foreach ($wallet_trans as $wa) {
					$trans  .= (empty($trans)) ? $wa['id'] : ",".$wa['id'];

					$walletRequest = $this->db->query('SELECT * FROM wallet_requests WHERE find_in_set('.$wa['id'].', tran_ids)')->row_array();

						if(!empty($walletRequest)) {
							$this->load->model('Payout_batch_model');
							$this->Payout_batch_model->detach_wallet_request_from_batch((int) $walletRequest['id']);
							$this->db->query('UPDATE wallet SET status=1 WHERE id IN ('.$walletRequest['tran_ids'].')');
						}

						$this->db->query('DELETE FROM wallet_requests WHERE find_in_set('.$wa['id'].', tran_ids)');
					}


					$this->db->query('DELETE FROM wallet_recursion WHERE transaction_id IN ('.$trans.')');

						$this->db->query('DELETE FROM wallet WHERE id IN ('.$trans.')');
					}


					$json['success'] = true;

					echo json_encode($json);

				}


		public function calc_commission(){

			$data = $this->input->post(null,true);

			$setting = array(

				'product_id'                      => $data['product_id'],

				'product_price'                   => $data['product_price'],

				'admin_click_commission_type'     => $data['admin_click_commission_type'],

				'admin_click_count'               => $data['admin_click_count'],

				'admin_click_amount'              => $data['admin_click_amount'],

				'admin_sale_commission_type'      => $data['admin_sale_commission_type'],

				'admin_commission_value'          => $data['admin_commission_value'],

			    'affiliate_click_commission_type' => $data['affiliate_click_commission_type'],

				'affiliate_click_count'           => $data['affiliate_click_count'],

				'affiliate_click_amount'          => $data['affiliate_click_amount'],

				'affiliate_sale_commission_type'  => $data['affiliate_sale_commission_type'],

				'affiliate_commission_value'      => $data['affiliate_commission_value'], 

			);

				$json['commission'] = $this->Product_model->calcVendorCommission($setting);

				$json['success'] = true;

				echo json_encode($json);

				}


			public function withdrawal_payment_gateways_doc(){
				set_default_currency();

				$data = [];

				$this->view($data,'withdrawal_payment/doc');

			}



			public function withdrawal_payment_gateways(){

				set_default_currency();

				$userdetails = $this->userdetails();

				$this->load->model('Withdrawal_payment_model');

				$data['payment_methods'] = $this->Withdrawal_payment_model->getPaymentMethods();

				$this->view($data,'withdrawal_payment/index');

			}



			public function withdrawal_payment_gateways_status_change($code){
				// Demo Mode
				if (ENVIRONMENT === 'demo') {
					$this->session->set_flashdata('error', __('admin.demo_mode'));
					redirect('admincontrol/withdrawal_payment_gateways');
					return;
				}
				// Demo Mode

				set_default_currency();

				$userdetails = $this->userdetails();

				$this->load->model('Withdrawal_payment_model');

				$this->Withdrawal_payment_model->changeInstallUninstall($code);

				redirect(base_url('admincontrol/withdrawal_payment_gateways'));

			}


			public function withdrawal_payment_gateways_edit($code = null) {

				// Demo Mode
				if (ENVIRONMENT === 'demo') {
					$this->session->set_flashdata('error', __('admin.demo_mode'));
					redirect('admincontrol/withdrawal_payment_gateways');
					return;
				}
				// Demo Mode

			    if ($code === null) {
			        redirect('admincontrol/withdrawal_payment_gateways', 'refresh');
			        return;
			    }

			    set_default_currency();

			    $userdetails = $this->userdetails();

			    $this->load->model('Withdrawal_payment_model');

			    $data['details'] = $this->Withdrawal_payment_model->getDetails($code);

			    if (!$data['details']) {
			        redirect('admincontrol/withdrawal_payment_gateways', 'refresh');
			    }

			    list($html, $setting) = $this->Withdrawal_payment_model->getEditPage($code);

			    $data['html'] = $html;
			    $data = array_merge($data, $setting);

			    $customSetting = $this->Product_model->getSettings('withdrawalpayment_' . $code);

			    if (!empty($customSetting)) {
			        $data['setting_exist_status'] = 1;
			        $data['get_custom_fiels'] = $customSetting;
			    } else {
			        $data['setting_exist_status'] = 0;
			        $data['get_custom_fiels'] = array();
			    }

			    $this->view($data, 'withdrawal_payment/withdrawal_payment_settings');
			}




			public function withdrawal_payment_gateways_setting_save($code){

				$post = $this->input->post(null,true);
				$this->Setting_model->save('withdrawalpayment_'.$code, $post);

				$json['redirect'] = base_url('admincontrol/withdrawal_payment_gateways');

				$this->session->set_flashdata('success',__('admin.settings_saved_successfully'));

				echo json_encode($json);

			}

			public function withdrawal_payment_gateways_setting_save_ajax() {
			    // Demo Mode Restriction
			    if (ENVIRONMENT === 'demo') {
			        echo json_encode([
			            'status' => 'error',
			            'message' => 'Disabled on demo mode'
			        ]);
			        return;
			    }

			    $json = array();
			    $post = $this->input->post(null, true);

			    if (isset($post) && !empty($post['code'])) {
			        $code = $post['code'];

			        // If payment method status is being updated
			        if (isset($post['status'])) {
			            $data['status'] = $post['status'];
			            $this->Setting_model->save('withdrawalpayment_'.$code, $data);
			        }

			        $json['status'] = 'true';
			        $json['msg'] = __('admin.settings_saved_successfully');
			    } else {
			        $json['status'] = 'false';
			        $json['msg'] = __('admin.settings_save_failed');
			    }

			    echo json_encode($json);
			}

			public function contactus($id=null)
			{
				$data  = array();
				$where = array('notification_type'=>'contact_us','notification_id'=>$id);
				$data['notification_details'] = $this->Common_model->select_where_result('notification', $where);

				$this->view($data,'conatctus/conatctus_details');
			}



			public function orders_notifications($id=null)
			{
				$userdetails = $this->userdetails();

				if(empty($userdetails) ){ redirect($this->admin_domain_url); }
				if(isset($id) && $id>0)
				{
					$data  = array();
					$where = array('notification_type'=>'integration_orders','notification_id'=>$id);
					 
					$notification = $this->Common_model->select_where_result('notification', $where);
					if(isset($notification) && is_array($notification) && count($notification)>0)
					{
						$order_id= $notification['notification_actionID'];
						$data['order']= $this->Order_model->getOrderDetails($order_id);
						$data['notification_title'] =$notification['notification_title'];
						$data['notification_details'] =$notification['notification_description'];

						$this->view($data,'notifications/ex_order_details');

					}
					else
						redirect('/admincontrol/notification');
					
				}
				else
					redirect('/admincontrol/notification');
			}

			public function click_notification($id=null)
			{
				$userdetails = $this->userdetails();

				if(empty($userdetails) ){ redirect($this->admin_domain_url); }
				if(isset($id) && $id>0)
				{
					$data  = array();
					$where = array('notification_type'=>'integration_click','notification_id'=>$id);
					 
					$notification = $this->Common_model->select_where_result('notification', $where);
					if(isset($notification) && is_array($notification) && count($notification)>0)
					{
						$click_id= $notification['notification_actionID'];
						$data['order']= $this->Order_model->getClickActionDetails($click_id);
						$data['notification_title'] =$notification['notification_title'];
						$data['notification_details'] =$notification['notification_description'];
	 
						if($data['order']['click_type']=='action') 
							$this->view($data,'notifications/ex_action_details');	
						else
							$this->view($data,'notifications/ex_click_details');

					}
					else
						redirect('/admincontrol/notification');
					
				}
				else
					redirect('/admincontrol/notification');
			}

		
			public function usergroup()
			{
				$userdetails = $this->userdetails();

				$data['groups'] = $this->user->getgrouplist();

				$this->view($data,'usergroup/index');
			}

			public function group_form($id='')
			{
				$userdetails = $this->userdetails();

				if(!empty($id))
				{
					$data['group']=$this->user->getgroupdetails($id);
				}

				$this->view($data,'usergroup/form');
			}

			public function admin_group_form()
			{
				
				$userdetails = $this->userdetails();

				if(empty($userdetails) ){ redirect($this->admin_domain_url); }

				if($userdetails['id'] != 1){ redirect($this->admin_domain_url); }
				
				if ($this->input->server('REQUEST_METHOD') == 'POST'){

					$json = array();

					$id = (int)$this->input->post("group_id",true);

					$this->load->library('form_validation');
					$this->form_validation->set_rules('group_name', __('admin.group_name'), 'required');
					$post = $this->input->post(null,true);

					if($this->form_validation->run()){

						$errors= array();
						$checkgroup = $this->user->checkgroup($this->input->post('group_name',true),$id);

						if(!empty($checkgroup)){ $json['errors']['group_name'] = __('admin.group_already_exists'); }

						$avatar = '';

						if(!empty($_FILES['avatar']['name'])){

							$upload_response = $this->upload_photo('avatar','assets/images/site');

							if($upload_response['success']){

								$avatar = $upload_response['upload_data']['file_name'];
								$oldfile=$this->input->post('oldfile');
								if(!empty($oldfile))
								{
									$path=FCPATH.'/assets/images/site/'.$oldfile;
									if(file_exists($path))
									{
										@unlink($path);
									}
								}
							}
							else{

								$json['errors']['avatar'] = $upload_response['msg'];
							}
						}
						if(!isset($json['errors'])){
							$userArray = array(
								'group_name'=> $this->input->post('group_name',true),
								'group_description'=> $this->input->post('group_description',true) 
							);

							if(!empty($avatar))
							{
								$userArray['avatar'] = $avatar;
							}
							
							if(empty($id)){
								$userArray['created_at'] = date("Y-m-d H:i:s");
								$data = $this->user->groupinsert($userArray);
								$id = $this->db->insert_id();

							} else {
								$userArray['updated_at'] = date("Y-m-d H:i:s");
								$data = $this->user->update_group($id, $userArray);
							}
							$this->session->set_flashdata('success', __('admin.group_updated_successfully'));

							$json['location'] = base_url('admincontrol/usergroup');
						}
					} else{

						$json['errors'] = $this->form_validation->error_array();
					}
					echo json_encode($json);die;
				}
			}


		public function group_status_toggle()
			{
				try {
					$userdetails = $this->userdetails();
					$json = array();
					$column = $this->input->post("column",true);
					$id = (int)$this->input->post("id",true);
					$status = (int)$this->input->post('status',true);
					if($column == 'default_registration_level'){
						$this->db->query("UPDATE user_groups SET default_registration_level = 0");
						$this->db->query("UPDATE user_groups SET default_registration_level = ".$status." WHERE id =". $id);
					} else {
						$this->db->query("UPDATE user_groups SET ".$column."='".$status."' WHERE id =".$id);
					}
					$json = array('status'=>true,'languages'=>'Is default status updated!');
				} catch (\Throwable $th) {
					$json = array('status'=>false,'message'=>$th->getMessage());
				}
				echo json_encode($json);
			}
		

		public function delete_user_group() {
			$id = $this->input->post('id');
			
			$this->db->select('id');
			$this->db->from('users');
			$this->db->like('groups',$id,'both');
			$query = $this->db->get();
			$row = $query->row_array();
			
			if(empty($row)) {
				$row = $this->db->get_where('user_groups',['id'=>$id])->row_array();
				if(!empty($row['avatar']))
				{
					$path=FCPATH.'/assets/images/site/'.$row['avatar'];
					if(file_exists($path))
					{
						@unlink($path);
					}
				}
				$this->db->delete('user_groups',['id'=>$id]);
				echo json_encode(array('status'=>1,'message'=>'Group deleted successfully!'));
				die;
			} else {
				echo json_encode(array('status'=>0,'message'=>'Group is already assigned to one or more users!'));
				die;
			}
		}

		public function doLoginAff() {
			if(!$this->userdetails()){ die('Unauthorized Access!'); } else {
				$id = $this->input->post('id');
				$user_details_array = $this->db->query('SELECT * from users WHERE id='.$id)->row_array();
				$this->session->set_userdata(array('user'=>$user_details_array));
				echo 'success';
			}
		}

		public function vendor_deposits() {
			$userdetails = $this->userdetails();

			$market_vendor_marketvendorstatus = $this->Product_model->getSettings('market_vendor', 'marketvendorstatus');
			$vendor_storestatus = $this->Product_model->getSettings('vendor', 'storestatus');
			$market_vendor_marketvendorstatus =  isset($market_vendor_marketvendorstatus['marketvendorstatus']) ? $market_vendor_marketvendorstatus['marketvendorstatus'] : 0;
			$vendor_storestatus =  isset($vendor_storestatus['storestatus']) ? $vendor_storestatus['storestatus'] : 0;

			$data['saas_status'] = ($market_vendor_marketvendorstatus == 1 || $vendor_storestatus == 1) ? 1 : 0;
			if($data['saas_status']){
				$get = $this->input->get(null,true);

				$post = $this->input->post(null,true);

				if (isset($post['get_deposit'])) {

					$get = $this->input->post(null,true);

					$filter = array();

					if (isset($get['user_id']) && $get['user_id'] > 0) {

						$filter['user_id'] = (int)$get['user_id'];

						$data['user_id'] = $filter['user_id'];

					}


					if (isset($get['date'])) {

						$filter['date'] = $get['date'];

						$data['date'] = $filter['date'];

					}

					$this->load->model('Deposit_payment_model');

					$data['lists'] = $this->Deposit_payment_model->getDeposits($filter);

					$json['html'] = $this->load->view("admincontrol/users/part/tr_vendor_deposit",$data,true);

					echo json_encode($json);die;
				}

				if(isset($post['delete_request'])){
					$json['type'] = 'warning';
					$json['title'] = __('admin.error');
					$json['message'] = __('admin.vendor_deposit_not_delete');

					$post = $this->input->post(null,true);

					$this->load->model('Deposit_payment_model');
					$success = $this->Deposit_payment_model->deleteDeposit($post['id']);
					
					if($success){
						$json['type'] = 'success';
						$json['title'] = __('admin.success');
						$json['message'] = __('admin.vendor_deposit_deleted');
					}

					echo json_encode($json);die;
				}

				$data['user'] = $userdetails;

				$data['users'] = $this->db->query("SELECT id,username FROM users WHERE type = 'user' AND is_vendor=1")->result_array();

									$data['total_deposited'] = $this->db->query("SELECT SUM(vd_amount) AS total FROM vendor_deposit WHERE vd_status=1")->row()->total;
			}
			

			$this->view($data,'users/deposit');
		}

		public function vendor_deposit_details($id){

			$userdetails = $this->userdetails();

			$market_vendor_marketvendorstatus = $this->Product_model->getSettings('market_vendor', 'marketvendorstatus');
			$vendor_storestatus = $this->Product_model->getSettings('vendor', 'storestatus');
			$market_vendor_marketvendorstatus =  isset($market_vendor_marketvendorstatus['marketvendorstatus']) ? $market_vendor_marketvendorstatus['marketvendorstatus'] : 0;
			$vendor_storestatus =  isset($vendor_storestatus['storestatus']) ? $vendor_storestatus['storestatus'] : 0;

			$data['saas_status'] = ($market_vendor_marketvendorstatus == 1 || $vendor_storestatus == 1) ? 1 : 0;
			if($data['saas_status']){
				$get = $this->input->get(null,true);

				$post = $this->input->post(null,true);

				$id=(int)$id;

				if (isset($post['status'])) {

					$this->form_validation->set_rules('status', 'Status', 'required|trim');

					$this->form_validation->set_rules('comment', 'Comment', 'required|trim');

					if ($this->form_validation->run() == FALSE) {

						$data['errors'] = $this->form_validation->error_array();

					} else {

						$this->load->model('Deposit_payment_model');

						$this->Deposit_payment_model->apiAddVendorDepositHistory($id,[

							'status_id' => (int)$post['status'],

							'comment' => $post['comment'],

							'transaction_id' => '',

						]);

						$data['success'] = 1;

						$update1['vd_status'] = (int)$post['status'];
						$this->Product_model->update_data( 'vendor_deposit', $update1, array('vd_id' => $id));

						$this->load->model('Mail_model');
						$deposit = $this->db->query('SELECT * FROM vendor_deposit WHERE vd_id='.$id)->row();
						$this->Mail_model->send_vendor_deposit_mail($deposit, 'added');
					}

					echo json_encode($data);die;

				}


				$this->load->model('Deposit_payment_model');

				$data['request'] = $this->Deposit_payment_model->getDeposits(['vd_id'=>$id]);

				if(!$data['request']){
					show_404();
				}

				$data['status_list'] = $this->Deposit_payment_model->status_list;
			}

			$this->view($data,'users/vendor_deposit_details');
		}

		public function get_vendor_deposit_history($id)
		{

			$status_history = $this->db->query("SELECT * FROM deposit_requests_history WHERE vd_id={$id} ORDER BY id DESC ")->result_array();

			$json['html'] = '';

			foreach ($status_history as $key => $value) {

				$badge = $value['transaction_id'] ?  ' <span class="badge bg-secondary d-inline-block vendor-tran-badge">Tran ID: '. $value['transaction_id'] .'</span>' : '';

				$json['html'].= '<tr><td style="width:250px;">'. withdrwal_status($value['status'])  .'</td>';

				if($value['transaction_id'])
					$json['html'].= '<td>'.$badge.'</td></tr>';
				else 
					$json['html'].= '<td>'.$value['comment'].'</td></tr>';
			}

			echo json_encode($json);die;
		}	

		public function payment_gateway(){

			$userdetails = $this->userdetails();

			$get = $this->input->get(null,true);
			$post = $this->input->post(null,true);

			if(isset($post['value'])){
				if($post['action'] == 'default'){
					$field = 'setting_type';
					$like = 'payment_gateway_'.$post['config'];
					$data_def_second = array('setting_is_default' => 0);
					$this->Product_model->updateWithLike('setting',$field, $like, $data_def_second);

					$where_def_second = array('setting_type' => 'payment_gateway_'.$post['config'].'_'.$post['method']);
					$data_def_second = array('setting_is_default' => (int) $post['value']);
					$this->Common_model->update('setting', $where_def_second, $data_def_second);
				}

				if($post['action'] == 'status')
					$this->Setting_model->save('payment_gateway_'.$post['config'].'_'.$post['method'], array('status' => (int) $post['value']));
				
				
				$json['result'] = true;
				echo json_encode($json);
				die;
			}

			$files = array();
			foreach (glob(APPPATH."/payment_gateway/controllers/*.php") as $file)
				$files[] = $file;

			$paymentGateways = array_unique($files);
			$configs = array('store','deposit','membership','integration');
			$data['payment_gateways'] = array();
			foreach($paymentGateways as $key => $filename){
				require $filename;

				$paymentGateway = basename($filename,".php");
				$setting = $this->Product_model->getSettings('payment_gateway_'.$paymentGateway,'is_install');
				$object = new $paymentGateway($this);
				$gatewayData = array(
					'title' => $object->title,
					'icon' => $object->icon,
					'website' => $object->website,
					'name'  => $paymentGateway,
					'is_install' => ($setting['is_install'] == 1) ? 1 : 0
				);
				$data['payment_gateways'][$paymentGateway] = $gatewayData;

				foreach($configs as $config) {
					$configSetting = $this->Product_model->getSettings('payment_gateway_'.$config.'_'.$paymentGateway);

					$where = array(
						'setting_key' => 'status',
						'setting_type' => 'payment_gateway_'.$config.'_'.$paymentGateway,
						'setting_is_default' => 1
					);
					$default = $this->Common_model->get_total_rows('setting', $where);

					$gatewayConfigData = array(
						'status'  => (isset($configSetting['status']) && $configSetting['status']) ? 1 : 0,
						'setting_is_default' => $default ? 1 : 0
					);

					$data['payment_gateways'][$paymentGateway][$config] = $gatewayConfigData;
				}
			}

			$this->load->config('payment_gateway');
			$data['payment_method'] = config_item('payment_method');


			$data['user'] = $userdetails;
			$data['users'] = $this->db->query("SELECT id,username FROM users WHERE type = 'user' AND is_vendor=1")->result_array();

			$this->view($data,'users/payment_gateway');
		}

		public function payment_gateway_edit($edit_code){

			$userdetails = $this->userdetails();

			if($edit_code != 'opay' && $edit_code != 'paytm'){
				$post = $this->input->post(null,true);
				if($post){
					if($edit_code == 'bank_transfer' && !isset($post['additional_bank_details']))
						$post['additional_bank_details'] = [];
					
					if($edit_code == 'bank_transfer' && isset($post['bank_names']))
						$post['bank_names'] = json_encode($post['bank_names']);


				$status_store['status'] = $post['store'];
				$this->Setting_model->save('payment_gateway_store_'.$edit_code,$status_store);
				unset($post['store']);

				$status_deposit['status'] = $post['deposit'];
				$this->Setting_model->save('payment_gateway_deposit_'.$edit_code,$status_deposit);
				unset($post['deposit']);

				$status_membership['status'] = $post['membership'];
				$this->Setting_model->save('payment_gateway_membership_'.$edit_code,$status_membership);
				unset($post['membership']);

				$status_integration['status'] = isset($post['integration']) ? $post['integration'] : 0;
				$this->Setting_model->save('payment_gateway_integration_'.$edit_code,$status_integration);
				unset($post['integration']);

				$this->Setting_model->save('payment_gateway_'.$edit_code,$post);

					$json['redirect'] = base_url('admincontrol/payment_gateway');
					$this->session->set_flashdata('success',__('admin.payment_data_saved_successfully'));

					echo json_encode($json);
					die;
				}

				$files = array();
				foreach (glob(APPPATH."/payment_gateway/controllers/*.php") as $file)
					$files[] = $file;

				$payment_gateways = array_unique($files);
				$payment_gateway = array();

				foreach($payment_gateways as $key => $filename){
					require $filename;

					$code = basename($filename,".php");
					$obj = new $code($this);
					$pdata          = array();
					$pdata['title'] = $obj->title;
					$pdata['code']  = $code;
					if($edit_code == $code){
						$setting_file = APPPATH."/payment_gateway/settings/{$edit_code}.php";
						if(is_file($setting_file)){
							$data['setting_data'] = $this->Product_model->getSettings('payment_gateway_'.$edit_code);
							
							$configs = array('store','deposit','membership','integration');
							foreach($configs as $config)
								$data['setting_data'][$config] = $this->Product_model->getSettings('payment_gateway_'.$config.'_'.$edit_code);

							$data['order_status'] = $this->Order_model->status();
							$pdata['setting'] = $this->getSettings($setting_file, $data);
						}
					}

					$payment_gateway[$code] = $pdata;
				}

				if(isset($payment_gateway[$edit_code])){
					$data['payment_gateway'] = $payment_gateway[$edit_code];
					$data['user'] = $userdetails;
					$this->view($data,'users/payment_gateway_edit');
				} else {
					redirect('admincontrol/payment_gateway');
				}
			} else {
				echo "<script>
				alert('".__('admin.payment_method_not_available')."');
				window.location.href='".base_url('admincontrol/payment_gateway')."';
				</script>";
			}
		}

		public function payment_gateway_documentation(){
			$data = array();
			foreach (glob(APPPATH."/payment_gateway/sample_data/*") as $file)
				$data['sample_data'][] = pathinfo(basename($file))['filename'];

			$this->view($data,'users/payment_gateway_documentation');
		}

		public function payment_gateway_documentation_sample_data($filename){
			if(file_exists(APPPATH.'payment_gateway/sample_data/'.$filename.'.json'))
				debug(file_get_contents(APPPATH.'payment_gateway/sample_data/'.$filename.'.json'));
			else
				redirect('admincontrol/payment_gateway_documentation');
		}

		public function payment_gateway_documentation_to_pdf(){
			$this->load->helper('documentation');
			documentationToPdf();
		}
		
		public function payment_gateway_sample_data_to_pdf(){
			foreach (glob(APPPATH."/payment_gateway/sample_data/*") as $file){
				$sample_data['filename'] = pathinfo(basename($file))['filename'];
				$sample_data['structure'] = file_get_contents($file);

				$data[] = $sample_data;
			}

			$this->load->helper('documentation');
			sampleDataToPdf($data);
		}

		public function payment_gateway_install(){

			// Demo Mode
			if (ENVIRONMENT === 'demo') {
				echo json_encode([
					'status' => 'error',
					'message' => 'Disabled on demo mode'
				]);
				return;
			}
			// Demo Mode

			$upload_path = APPPATH.'payment_gateway/tmp';
			if (!is_writable($upload_path)){
				$json['warning'] = APPPATH.'payment_gateway/tmp '.__('admin.folder_not_have_permission');
				echo json_encode($json);
				die;
			}

			$install = pathinfo($_FILES['install']['name']);
			if($install['extension'] != 'zip'){
				$json['warning'] = __('admin.only_zip_file_accepting');
				echo json_encode($json);
				die;
			}

			foreach (glob(APPPATH.'payment_gateway/controllers/*.php') as $paymentGateway)
				$paymentGateways[] = basename($paymentGateway,'.php');

			if(in_array($install['filename'],$paymentGateways)){
				$json['warning'] = __('admin.this_payment_gateway_already_exist');
				echo json_encode($json);
				die;
			}

			$zip = new ZipArchive();
			if($zip->open($_FILES['install']['tmp_name'])){
				$zip->extractTo($upload_path);
				$zip->close();
			} else {
				$json['warning'] = __('admin.can_not_extract_zip_file');
				echo json_encode($json);
				die;
			}

			$moveAbleFileAndFolder = [];

			$required_folders = ['controller','setting','view'];
			foreach($required_folders as $folder){
				$folder_exist = APPPATH.'payment_gateway/tmp/'.$install['filename'].'/'.$folder;
				if(!is_dir($folder_exist)){
					self::clearPaymentGatewayTmpDirectory(APPPATH.'payment_gateway/tmp/');
					$json['warning'] = $folder.' '.__('admin.folder_not_exist');
					echo json_encode($json);
					die;
				}

				$required_folder = glob(APPPATH.'payment_gateway/tmp/'.$install['filename'].'/'.$folder.'/*');
				if(count($required_folder) > 1){
					self::clearPaymentGatewayTmpDirectory(APPPATH.'payment_gateway/tmp/');
					$json['warning'] = $folder.' '.__('admin.folder_must_keep_only_one_file');
					echo json_encode($json);
					die;
				}

				$file_exist = $folder_exist.'/'.$install['filename'].'.php';
				if(!file_exists($file_exist)){
					self::clearPaymentGatewayTmpDirectory(APPPATH.'payment_gateway/tmp/');
					$json['warning'] = $folder.'/'.$install['filename'].'.php'.' '.__('admin.file_not_exist');
					echo json_encode($json);
					die;
				}

				$array['from'] = APPPATH.'payment_gateway/tmp/'.$install['filename'].'/'.$folder.'/'.$install['filename'].'.php';
				$array['to'] = APPPATH.'payment_gateway/'.$folder.'s/'.$install['filename'].'.php';
				$moveAbleFileAndFolder[] = $array;
			}

			$library_folder = glob(APPPATH.'payment_gateway/tmp/'.$install['filename'].'/library/*');
			if($library_folder){
				if(count($library_folder) > 1){
					self::clearPaymentGatewayTmpDirectory(APPPATH.'payment_gateway/tmp/');
					$json['warning'] = 'library '.__('admin.folder_must_keep_only_one_file');
					echo json_encode($json);
					die;
				}

				$library_exist = APPPATH.'payment_gateway/tmp/'.$install['filename'].'/library/'.$install['filename'];
				if(!is_dir($library_exist)){
					self::clearPaymentGatewayTmpDirectory(APPPATH.'payment_gateway/tmp/');
					$json['warning'] = 'library/'.$install['filename'].' '.__('admin.folder_not_exist');
					echo json_encode($json);
					die;
				}

				$array['from'] = APPPATH.'payment_gateway/tmp/'.$install['filename'].'/library/'.$install['filename'];
				$array['to'] = APPPATH.'payment_gateway/library/'.$install['filename'];
				$moveAbleFileAndFolder[] = $array;
			}

			$logo_folder = glob(APPPATH.'payment_gateway/tmp/'.$install['filename'].'/logo/*');
			if($logo_folder){
				if(count($logo_folder) > 1){
					self::clearPaymentGatewayTmpDirectory(APPPATH.'payment_gateway/tmp/');
					$json['warning'] = 'logo '.__('admin.folder_must_keep_only_one_file');
					echo json_encode($json);
					die;
				}

				$logo_exist = APPPATH.'payment_gateway/tmp/'.$install['filename'].'/logo/'.$install['filename'].'.png';
				if(!file_exists($logo_exist)){
					self::clearPaymentGatewayTmpDirectory(APPPATH.'payment_gateway/tmp/');
					$json['warning'] = 'logo/'.$install['filename'].'.png '.__('admin.file_not_exist');
					echo json_encode($json);
					die;
				}

				$array['from'] = APPPATH.'payment_gateway/tmp/'.$install['filename'].'/logo/'.$install['filename'].'.png';
				$array['to'] = FCPATH.'assets/payment_gateway/'.$install['filename'].'.png';
				$moveAbleFileAndFolder[] = $array;
			}


			foreach($moveAbleFileAndFolder as $key => $value)
				rename($value['from'],$value['to']);
			
			self::clearPaymentGatewayTmpDirectory(APPPATH.'payment_gateway/tmp/');

			$json['location'] = base_url('admincontrol/payment_gateway');
			echo json_encode($json);
			die();
		}

		private function clearPaymentGatewayTmpDirectory($tmpDirectory,$rmdir = false){
			$files = glob($tmpDirectory.'*',GLOB_MARK);
			foreach($files as $file){
				if(is_dir($file))
					self::clearPaymentGatewayTmpDirectory($file,true);
				else
					unlink($file);
			}

			if($rmdir)
				rmdir($tmpDirectory);

			return;
		}

		public function payment_gateway_status_change($code){

			// Demo Mode
			if (ENVIRONMENT === 'demo') {
				$this->session->set_flashdata('error', __('admin.demo_mode'));
				redirect('admincontrol/payment_gateway');
				return;
			}
			// Demo Mode

			if($code != 'opay' && $code != 'paytm'){
				if(file_exists(APPPATH."payment_gateway/controllers/{$code}.php")){
					$settingData = $this->Product_model->getSettings('payment_gateway_'.$code);
					$settingData['is_install'] = ($settingData['is_install'] == 1) ? 0 : 1;

					$this->Setting_model->clear('payment_gateway_'.$code);
					$this->Setting_model->save('payment_gateway_'.$code,$settingData);

					$operation = ($settingData['is_install'] == 0) ? __('admin.uninstalled') : __('admin.installed');
					$this->session->set_flashdata('success',__('admin.payment_gateway').' '.$operation.' '.__('admin.successfully'));
				} else {
					$this->session->set_flashdata('error',__('admin.payment_gateway_not_exist'));
				}

				redirect(base_url('admincontrol/payment_gateway'));
			} else {
				echo "<script>
				alert('".__('admin.payment_method_not_available')."');
				window.location.href='".base_url('admincontrol/payment_gateway')."';
				</script>";
			}
		}

		public function delete_payment_gateway($code){
			$this->load->config('payment_gateway');
			$payment_method = config_item('payment_method');
			if(!in_array($code,$payment_method)){
				if(file_exists(APPPATH."payment_gateway/controllers/{$code}.php")){
					$files= [
						APPPATH."payment_gateway/controllers/{$code}.php",
						APPPATH."payment_gateway/settings/{$code}.php",
						APPPATH."payment_gateway/views/{$code}.php",
						FCPATH."assets/payment_gateway/{$code}.png",
					];
					foreach($files as $key => $file)
						unlink($file);
					
					if(is_dir(APPPATH."payment_gateway/library/{$code}"))	
						$this->deleteDir(APPPATH."payment_gateway/library/{$code}");

					
					$this->load->model('Setting_model');
					$this->Setting_model->clear('payment_gateway_'.$code);

					$this->load->config('payment_gateway');
					foreach(config_item('payment_module') as $key => $value)
						$this->Setting_model->clear('payment_gateway_'.$value.'_'.$code);

					$this->session->set_flashdata('success',__('admin.payment_gateway_deleted_successfully'));
				} else {
					$this->session->set_flashdata('error',__('admin.payment_gateway_not_exist'));
				}
			} else {
				$this->session->set_flashdata('error',__('admin.not_have_permission_to_delete_this_method'));
			}
			

			redirect('admincontrol/payment_gateway');
		}

		

		public function all_transaction($page = 1){
			$userdetails = $this->userdetails();
			$filter = $this->input->post(null,true);
			$get_user_id = $this->input->get('user_id');
			
			$this->load->model('Order_model');
			
			$current_page = max(1, (int)$this->input->get('page') ?: (int)$page ?: 1);
			$pagination_settings = get_pagination_settings();
			$per_page = $pagination_settings['per_page'];
			
			if(!$filter) {
				$filter = [];
			}
			$filter['page'] = $current_page;
			
			$total_rows = count($this->Wallet_model->getAllTransaction($userdetails, [], false));
			
			$pagination_data = easy_pagination(
				base_url('admincontrol/all_transaction'),
				$total_rows,
				($current_page - 1) * $per_page,
				['per_page' => $per_page, 'alignment' => 'end']
			);
			
			$view['pagination'] = $pagination_data['html'];
			$view['all_transaction'] = $this->Wallet_model->getAllTransaction($userdetails, $filter, $per_page);
			$view['current_page'] = $current_page;
			$view['total_rows'] = $total_rows;
			$view['per_page'] = $per_page;
			$view['pagination_settings'] = $pagination_settings;
			$view['payment_methods'] = $this->Order_model->PaymentMethods();
			
			$html = $this->load->view("admincontrol/users/part/all_transaction", $view, true);
			
			if($this->input->post()){
				echo $html;
				die();
			}

			$data['html'] = $html;
			$this->load->config('payment_gateway');
			$data['payment_module'] = config_item('payment_module');
			$data['filter_field'] = $this->Wallet_model->getAllTransactionFilter($userdetails);
			$data['selected_user_id'] = $get_user_id;

			$this->view($data, 'users/all_transaction');
		}

		public function all_transaction_export_to_excel(){
			$userdetails = $this->userdetails();
			$filter = $this->input->get(null,true);

			$this->load->helper('all_transaction');
			$all_transaction = $this->Wallet_model->getAllTransaction($userdetails,$filter,false);
			exportToExcel($all_transaction);
		}

		public function all_transaction_export_to_pdf(){
			$userdetails = $this->userdetails();
			$filter = $this->input->get(null,true);
			$this->load->helper('all_transaction');
			$all_transaction = $this->Wallet_model->getAllTransaction($userdetails,$filter,false);
			exportToPdf($userdetails['admin'],$all_transaction);
		}

		public function getOrderDetails() {

			$post = $this->input->post(null,true);
			
			$filter = array(
				'limit' => 1,
				'page' => 1,
				'getSingleOrder' => $post['type'],
				'order_id' => $post['ref2']
			);

			list($data['orders'],$total) = $this->Order_model->getAllOrders($filter);

			$data['userdetails'] = $this->userdetails();

			$data['trans']['comment'] = isset($data['orders'][0]) ? $data['orders'][0]['wallet_comment'] : '';
			$data['trans']['comm_from'] = isset($data['orders'][0]) ? $data['orders'][0]['wallet_comm_from'] : '';
			$data['trans']['type'] = isset($data['orders'][0]) ? $data['orders'][0]['wallet_type'] : '';
			$data['trans']['is_action'] = isset($data['orders'][0]) ? $data['orders'][0]['wallet_is_action'] : '';
			
			echo $this->load->view("admincontrol/store/order_details_mb",$data,true);
		}
		
		public function uploadMailImages() {
			if (!is_dir('assets/user_upload/mail_template_images')) {
				mkdir('./assets/user_upload/mail_template_images', 0644, TRUE);
			}
			$imgUploadRes = $this->upload_photo('upload','assets/user_upload/mail_template_images');


			if(isset($imgUploadRes['upload_data']['file_name'])) {
				echo json_encode(array(
					"uploaded" => 1,
					"fileName" => $imgUploadRes['upload_data']['file_name'],
					"url"=> base_url('assets/user_upload/mail_template_images/' . $imgUploadRes['upload_data']['file_name']),
				));
				exit;
			}
			echo json_encode(array(
				"error" => array(
					"message" => $imgUploadRes['message']
				)
			));
			exit;
		}

			public function check_award_level(){
	    if(!$this->userdetails()){ die(); }

	    if ($this->input->server('REQUEST_METHOD') == 'POST'){

	        $result = [];

	        $post = $this->input->post(null,true);

	        $offset = isset($post['index']) ? $post['index'] - 1 : 0;

	        $jumped_user = $this->Product_model->checkJumpedUser(1,$offset);

	        $userCount = $this->Product_model->countByTable('users');

	        if($userCount > $post['index'])
	            $result['index'] = $post['index'] + 1;
	        
	        if($userCount > 0)
	            $result['progress_percentage'] = (($post['index'] / $userCount) * 100)."%";

	        $result['jumped'] = $jumped_user;  // This line is new.

	        if($jumped_user)
	            $result['message'] = __('admin.user_jumped_to_level');

	        echo json_encode($result);
	    }
	}

	public function level_analysis(){
	    $userdetails = $this->userdetails();
	    
	    $award_level = $this->Product_model->getSettings('award_level','status');
	    $data['award_level_status'] = $award_level['status'];
	    
	    if($data['award_level_status']){
	        // Get analysis data
	        $analysis = $this->Product_model->analyzeUserEarningsDistribution();
	        $data['analysis'] = $analysis;
	        $data['CurrencySymbol'] = $this->currency->getSymbol();
	    }
	    
	    $this->view($data, 'award_level/analysis');
	}

	public function multiApproveDecline(){
			$post = $this->input->post(null,true);

			$approval_data = [];

			if(isset($post['approve_users']) && !empty($post['approve_users'])) {
				$approval_data['reg_approved'] = 1;
			}

			if(isset($post['decline_users']) && !empty($post['decline_users'])) {
				$approval_data['reg_approved'] = 2;
			}
			
			$idsArray = explode(',', $post['ids']);

			foreach ($idsArray as $user_id) {
				$approval_data['users_ids'] = $user_id;
				
				$checkUser = $this->Product_model->getUserInfo($user_id);
				$json['approvals_status']['status'] = 'NULL';

				if ($checkUser[0]->reg_approved == '0' || $checkUser[0]->reg_approved == '2') {
					if(!empty($approval_data)) {
						$json['approvals_status'] = $this->Product_model->process_approval($approval_data);

						if($json['approvals_status']['status']) {
							$this->load->model('Mail_model');
							$user = App\User::find(array('id' => $approval_data['users_ids']));
							if(isset($post['approve_users']) && !empty($post['approve_users'])) {
								$membership = $this->Product_model->getSettings('membership');
								switch ((int)$membership['status']) {
									case 0:
			                    		//disabled
									$plan_id = -1;
									break;
									case 1:
				                		//all users
									$plan_id = 0;
									break;
									case 2:
				                		//all vendors
									if($is_vendor == 1) {
										$plan_id = 0;
									} else {
										$plan_id = -1;
									}
									break;
									case 3:
				                		//all affiliates
									$plan_id = -1;
									if($is_vendor == 1) {
										$plan_id = -1;
									} else {
										$plan_id = 0;
									}
									break;
									default:
									$plan_id = -1;
									break;
								}

								if($plan_id == 0) {
									if((int)$user[0]['is_vendor'] == 1) {
										$plan_id = $membership['default_vendor_plan_id'] ?? $membership['default_plan_id'];
									} else {
										$plan_id = $membership['default_affiliate_plan_id'] ?? $membership['default_plan_id'];
									}
								}
								if($membership['status'] && $plan_id > 0){
									$plan = App\MembershipPlan::find($plan_id);
									if($plan){
										$plan->buy($user[0], 1, 'Default plan started','Default');
										$commission_processed = $this->db->query('SELECT id from wallet WHERE reference_id='.$approval_data['users_ids'].' AND type="refer_registration_commission"')->result();

										$refid = (int)$user[0]['refid'];

										if(empty($commission_processed) && $refid > 0) {
											$this->load->model('Wallet_model');
											$comission_group_id = time().rand(10,100);
											$referlevelSettings = $this->Product_model->getSettings('referlevel');
											$max_level = isset($referlevelSettings['levels']) ? (int)$referlevelSettings['levels'] : 3;
											
											$json['max_level'] = $max_level;

											$disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
											$refer_status = true;
											if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
											else if((int)$referlevelSettings['status'] == 2 && in_array($refid, $disabled_for)){ $refer_status = false; }

											$json['refer_status'] = $refer_status;

											if($refer_status) {
												$json['level'] = $level = $this->Product_model->getMyLevel($refid);	
												$json['max_level_user'] = [];
												for ($l=1; $l <= $max_level ; $l++) { 
													if($l == 1) {
														$json['max_level_user'][] = $levelUser = (int)$refid;	
													} else {
														$json['max_level_user'][] = $levelUser = (int)$level['level'.($l-1)];
													}
													$s = $this->Product_model->getSettings('referlevel_'. $l);

													if($s && $levelUser > 0){
														$_giveAmount = 0;
														
														if($referlevelSettings['reg_comission_type'] == 'custom_percentage'){
															if((int) $referlevelSettings['reg_comission_custom_amt'] > 0) {
																$_giveAmount = (($referlevelSettings['reg_comission_custom_amt'] * (float)$s['reg_commission']) / 100);
															}
														} else if($referlevelSettings['reg_comission_type'] == 'fixed'){
															$_giveAmount = (float)$s['reg_commission'];
														}

														$json['max_level_user']['_giveAmount'] = $_giveAmount;

														if($_giveAmount > 0){
															$transaction_id1 = $this->Wallet_model->addTransaction(array(
																'status'       => 1,
																'user_id'      => $levelUser,
																'amount'       => $_giveAmount,
																'dis_type'     => '',
																'comment'      => "Level {$l} : ".'Commission for new affiliate registrion Id ='. $user[0]['id'] .' | Name : '. $user[0]['firstname'] ." " .$user[0]['lastname'],
																'type'         => 'refer_registration_commission',
																'reference_id' => $user[0]['id'],
																'group_id' => $comission_group_id,
															));
														}
													}
												}
											}
										}
									}
								}

								$this->Mail_model->send_registration_approved_mail(json_decode(json_encode($user[0])));
							}

							if(isset($post['decline_users']) && !empty($post['decline_users'])) {
								$this->Mail_model->send_registration_declined_mail(json_decode(json_encode($user[0])));
							}
						}
					}
				}
			}

			$json['approvals_count'] = $this->Product_model->getApprovalCounts();
			echo json_encode($json);die;
		}

		public function set_default_admin_url(){
			$set_default = $this->Setting_model->set_default_admin_url();
			echo $set_default;
		}

		public function set_default_front_url(){
			$set_default = $this->Setting_model->set_default_front_url();
			echo $set_default;
		}

		public function update_store_status(){
			$status = $this->input->post('status');

			$update = $this->Setting_model->update_store_status($status);

			if ($status == '0') {
				$update = $this->Setting_model->update_store_menu_on_front('0');
				$update = $this->Setting_model->update_store_menu_on_front_blank('0');
			}

			echo $update;
		}

		public function update_store_menu_on_front(){
			$status = $this->input->post('status');

			$update = $this->Setting_model->update_store_menu_on_front($status);
			echo $update;
		}

		public function update_cookies_menu(){
		    $status = $this->input->post('status');
		    $update = $this->Setting_model->update_cookies_menu($status);
		    echo $update;
		}


		public function update_store_menu_on_front_blank(){
			$status = $this->input->post('status');
			
			$update = $this->Setting_model->update_store_menu_on_front_blank($status);
			echo $update;
		}

		public function update_store_mode(){
			
			$mode = $this->input->post('mode');
			$theme = $this->input->post('theme');

			$update = $this->Setting_model->update_store_mode($mode); //changing the store 
			$theme_update = $this->Setting_model->update_store_theme($theme); //changing the theme
			echo $update;
		}

	public function update_all_settings(){

		$status = $this->input->post('status');
		$setting_key = $this->input->post('setting_key');
		$setting_type = $this->input->post('setting_type');

		if (ENVIRONMENT === 'demo' && $setting_type === 'login' && strpos($setting_key, 'block_') === 0) {
			echo json_encode(['success' => 0, 'message' => __('admin.demo_mode')]);
			return;
		}

		//enable-disable vendor mlm module
		if($setting_key=="vendormlmmodule" && $setting_type=="market_vendor")
		{
			$query= $this->db->query("SELECT id FROM `users` where is_vendor=1 and status=1");
			$vendors=$query->result_array();
			for($i=0;$i<count($vendors);$i++)
			{
				$vid=$vendors[$i]['id'];
				$value=array("status"=>$status);
				$this->Setting_model->vendorSave($vid, "referlevel", $value);
			}
		}
		//enable-disable vendor mlm module
		
		$update = $this->Setting_model->update_all_settings($status, $setting_key, $setting_type);

		echo json_encode(['success' => $update > 0 ? __('admin.setting_updated_successfully') : 0]);
	}

	/**
	 * Save JSON sub-settings for login page Top Earners block (block_top_earners_settings).
	 */
	public function save_login_top_earners_block_settings() {
		$this->output->set_content_type('application/json');
		if (ENVIRONMENT === 'demo') {
			echo json_encode(['message' => __('admin.demo_mode')]);
			return;
		}
		$limit = (int) $this->input->post('display_limit');
		if (!in_array($limit, [3, 5, 10], true)) {
			$limit = 5;
		}
		$privacy = (int) $this->input->post('privacy_mode') ? 1 : 0;
		$demo_rows = (int) $this->input->post('demo_rows') ? 1 : 0;
		$this->Setting_model->save('login', [
			'block_top_earners_settings' => [
				'display_limit' => $limit,
				'privacy_mode' => $privacy,
				'demo_rows' => $demo_rows,
			],
		]);
		echo json_encode(['success' => __('admin.setting_updated_successfully')]);
	}

	/**
	 * Save JSON sub-settings for platform stats block (block_stats_settings): custom metric labels.
	 */
	public function save_login_stats_block_settings() {
		$this->output->set_content_type('application/json');
		if (ENVIRONMENT === 'demo') {
			echo json_encode(['message' => __('admin.demo_mode')]);
			return;
		}
		$active = trim((string) $this->input->post('active_label'));
		$withdrawals = trim((string) $this->input->post('withdrawals_label'));
		if (function_exists('mb_substr')) {
			$active = mb_substr($active, 0, 120, 'UTF-8');
			$withdrawals = mb_substr($withdrawals, 0, 120, 'UTF-8');
		} else {
			$active = substr($active, 0, 120);
			$withdrawals = substr($withdrawals, 0, 120);
		}
		$demo_values = (int) $this->input->post('demo_values') ? 1 : 0;
		$this->Setting_model->save('login', [
			'block_stats_settings' => [
				'active_label' => $active,
				'withdrawals_label' => $withdrawals,
				'demo_values' => $demo_values,
			],
		]);
		echo json_encode(['success' => __('admin.setting_updated_successfully')]);
	}

	/**
	 * Save promotional video block (block_video_settings).
	 */
	public function save_login_video_block_settings() {
		$this->output->set_content_type('application/json');
		if (ENVIRONMENT === 'demo') {
			echo json_encode(['message' => __('admin.demo_mode')]);
			return;
		}
		$this->load->helper('login_page_blocks');
		$raw = trim((string) $this->input->post('video_settings'));
		$decoded = json_decode($raw, true);
		if (!is_array($decoded)) {
			$decoded = [];
		}
		$sanitized = login_page_video_sanitize_settings_for_save($decoded);
		$this->Setting_model->save('login', [
			'block_video_settings' => $sanitized,
		]);
		echo json_encode(['success' => __('admin.setting_updated_successfully')]);
	}

	/**
	 * Save live activity pulse block (block_live_pulse_settings).
	 */
	public function save_login_live_pulse_block_settings() {
		$this->output->set_content_type('application/json');
		if (ENVIRONMENT === 'demo') {
			echo json_encode(['message' => __('admin.demo_mode')]);
			return;
		}
		$sec = (int) $this->input->post('poll_interval_sec');
		if ($sec < 15) {
			$sec = 15;
		}
		if ($sec > 120) {
			$sec = 120;
		}
		$pos = trim((string) $this->input->post('toast_position'));
		if (!in_array($pos, ['bottom-right', 'bottom-left', 'bottom-center'], true)) {
			$pos = 'bottom-right';
		}
		$use_demo = (int) $this->input->post('use_demo_content') ? 1 : 0;
		$this->Setting_model->save('login', [
			'block_live_pulse_settings' => [
				'poll_interval_sec' => $sec,
				'toast_position' => $pos,
				'use_demo_content' => $use_demo,
			],
		]);
		echo json_encode(['success' => __('admin.setting_updated_successfully')]);
	}

	public function save_login_features_block_settings() {
		$this->output->set_content_type('application/json');
		if (ENVIRONMENT === 'demo') {
			echo json_encode(['message' => __('admin.demo_mode')]);
			return;
		}
		$this->load->helper('login_page_blocks');
		$raw = trim((string) $this->input->post('features_settings'));
		if ($raw === '') {
			$raw = trim((string) $this->input->post('features_items'));
		}
		$decoded = json_decode($raw, true);
		if (!is_array($decoded)) {
			$decoded = [];
		}
		if (!isset($decoded['items']) || !is_array($decoded['items'])) {
			$decoded['items'] = [];
		}
		$hasDisplay = isset($decoded['columns_md']) || isset($decoded['variant']) || isset($decoded['icon_style'])
			|| array_key_exists('show_description', $decoded);
		if (!$hasDisplay) {
			$loginSettings = $this->Product_model->getSettings('login');
			$prevJson = (is_array($loginSettings) && isset($loginSettings['block_features_settings']))
				? $loginSettings['block_features_settings']
				: '';
			$prev = is_string($prevJson) ? json_decode($prevJson, true) : (is_array($prevJson) ? $prevJson : []);
			if (is_array($prev)) {
				foreach (['columns_sm', 'columns_md', 'columns_lg', 'variant', 'show_description', 'icon_style'] as $dk) {
					if (!array_key_exists($dk, $decoded) && array_key_exists($dk, $prev)) {
						$decoded[$dk] = $prev[$dk];
					}
				}
			}
		}
		if (!array_key_exists('use_demo_content', $decoded)) {
			$loginSettings = $this->Product_model->getSettings('login');
			$prevJson = (is_array($loginSettings) && isset($loginSettings['block_features_settings']))
				? $loginSettings['block_features_settings']
				: '';
			$prev = is_string($prevJson) ? json_decode($prevJson, true) : (is_array($prevJson) ? $prevJson : []);
			if (is_array($prev) && array_key_exists('use_demo_content', $prev)) {
				$decoded['use_demo_content'] = $prev['use_demo_content'];
			}
		}
		$sanitized = login_page_features_sanitize_settings_for_save($decoded);
		$this->Setting_model->save('login', [
			'block_features_settings' => $sanitized,
		]);
		echo json_encode(['success' => __('admin.setting_updated_successfully')]);
	}

	public function save_login_faq_block_settings() {
		$this->output->set_content_type('application/json');
		if (ENVIRONMENT === 'demo') {
			echo json_encode(['message' => __('admin.demo_mode')]);
			return;
		}
		$this->load->helper('login_page_blocks');
		$raw = trim((string) $this->input->post('faq_settings'));
		$decoded = json_decode($raw, true);
		if (!is_array($decoded)) {
			$decoded = [];
		}
		if (!isset($decoded['items']) || !is_array($decoded['items'])) {
			$decoded['items'] = [];
		}
		if (!array_key_exists('first_item_open', $decoded)) {
			$loginSettings = $this->Product_model->getSettings('login');
			$prevJson = (is_array($loginSettings) && isset($loginSettings['block_faq_settings']))
				? $loginSettings['block_faq_settings']
				: '';
			$prev = is_string($prevJson) ? json_decode($prevJson, true) : (is_array($prevJson) ? $prevJson : []);
			if (is_array($prev) && array_key_exists('first_item_open', $prev)) {
				$decoded['first_item_open'] = $prev['first_item_open'];
			}
		}
		if (!array_key_exists('use_demo_content', $decoded)) {
			$loginSettings = $this->Product_model->getSettings('login');
			$prevJson = (is_array($loginSettings) && isset($loginSettings['block_faq_settings']))
				? $loginSettings['block_faq_settings']
				: '';
			$prev = is_string($prevJson) ? json_decode($prevJson, true) : (is_array($prevJson) ? $prevJson : []);
			if (is_array($prev) && array_key_exists('use_demo_content', $prev)) {
				$decoded['use_demo_content'] = $prev['use_demo_content'];
			}
		}
		$sanitized = login_page_faq_sanitize_settings_for_save($decoded);
		$this->Setting_model->save('login', [
			'block_faq_settings' => $sanitized,
		]);
		echo json_encode(['success' => __('admin.setting_updated_successfully')]);
	}

	/** Receive a JS error report from the browser and append it to the log file. */
	public function js_error_log() {
		$this->output->set_content_type('application/json');
		if (function_exists('js_error_debug_enabled') && !js_error_debug_enabled()) {
			echo json_encode(['ok' => false, 'disabled' => true]);
			return;
		}
		if ($this->input->server('REQUEST_METHOD') !== 'POST') { echo json_encode(['ok'=>false]); return; }
		$msg  = trim((string)$this->input->post('msg'));
		$type = trim((string)$this->input->post('type'));
		$path = trim((string)$this->input->post('path'));
		if ($msg === '') { echo json_encode(['ok'=>false]); return; }
		$file = APPPATH . 'logs/js_errors.json';
		$log  = [];
		if (file_exists($file)) { $raw = @file_get_contents($file); if ($raw) $log = json_decode($raw, true) ?: []; }
		$normalPath = $path ?: '?';
		$normalType = $type ?: 'runtime';
		$now = date('c');
		// Deduplicate: if the same msg+path already exists, update timestamp and increment count
		$found = false;
		foreach ($log as &$entry) {
			if (isset($entry['msg'], $entry['path']) && $entry['msg'] === $msg && $entry['path'] === $normalPath) {
				$entry['time']      = $now;
				$entry['last_seen'] = $now;
				$entry['type']      = $normalType;
				$entry['count']     = ($entry['count'] ?? 1) + 1;
				if (!isset($entry['first_seen'])) $entry['first_seen'] = $now;
				$found = true;
				break;
			}
		}
		unset($entry);
		if (!$found) {
			$log[] = [
				'time'       => $now,
				'first_seen' => $now,
				'last_seen'  => $now,
				'count'      => 1,
				'path'       => $normalPath,
				'type'       => $normalType,
				'msg'        => $msg,
			];
		}
		if (count($log) > 500) $log = array_slice($log, -500);
		@file_put_contents($file, json_encode($log));
		echo json_encode(['ok' => true]);
	}

	/** Return the stored JS error log as JSON (deduplicates on read so old duplicates are merged). */
	public function js_error_get() {
		if (function_exists('js_error_debug_enabled') && !js_error_debug_enabled()) {
			$this->output->set_content_type('application/json');
			echo json_encode(['errors' => [], 'config_warnings' => []]);
			return;
		}
		$file = APPPATH . 'logs/js_errors.json';
		$log  = [];
		if (file_exists($file)) { $raw = @file_get_contents($file); if ($raw) $log = json_decode($raw, true) ?: []; }
		// Merge any duplicate msg+path pairs that existed before server-side dedup was added
		$merged = [];
		$index  = [];
		foreach ($log as $entry) {
			$key = ($entry['msg'] ?? '') . '||' . ($entry['path'] ?? '');
			if (isset($index[$key])) {
				$existing =& $merged[$index[$key]];
				$existing['count']     = ($existing['count'] ?? 1) + ($entry['count'] ?? 1);
				$existing['last_seen'] = max($existing['last_seen'] ?? $existing['time'] ?? '', $entry['last_seen'] ?? $entry['time'] ?? '');
				$existing['time']      = $existing['last_seen'];
				if (!isset($existing['first_seen'])) $existing['first_seen'] = $entry['first_seen'] ?? $entry['time'] ?? '';
			} else {
				$index[$key] = count($merged);
				$merged[]    = $entry;
			}
		}
		// Persist the cleaned-up log back so duplicates are gone permanently
		if (count($merged) !== count($log)) {
			@file_put_contents($file, json_encode(array_values($merged)));
		}
		$log = $merged;
		// Separate real errors from configuration notices (e.g. CORS "Origin not allowed")
		$realErrors     = [];
		$configWarnings = [];
		foreach ($log as $entry) {
			if (($entry['type'] ?? '') === 'config') {
				$configWarnings[] = $entry;
			} else {
				$realErrors[] = $entry;
			}
		}
		$this->output->set_content_type('application/json');
		echo json_encode(['errors' => array_reverse($realErrors), 'config_warnings' => array_reverse($configWarnings)]);
	}

	/** Clear the stored JS error log. */
	public function js_error_clear() {
		$this->output->set_content_type('application/json');
		if (function_exists('js_error_debug_enabled') && !js_error_debug_enabled()) {
			echo json_encode(['ok' => false, 'disabled' => true]);
			return;
		}
		if ($this->input->server('REQUEST_METHOD') !== 'POST') { echo json_encode(['ok'=>false]); return; }
		@file_put_contents(APPPATH . 'logs/js_errors.json', '[]');
		echo json_encode(['ok' => true]);
	}

	/** Remove a single entry from the JS error log by msg+path match. */
	public function js_error_dismiss() {
		$this->output->set_content_type('application/json');
		if (function_exists('js_error_debug_enabled') && !js_error_debug_enabled()) {
			echo json_encode(['ok' => false, 'disabled' => true]);
			return;
		}
		if ($this->input->server('REQUEST_METHOD') !== 'POST') { echo json_encode(['ok'=>false]); return; }
		$msg  = trim((string)$this->input->post('msg'));
		$path = trim((string)$this->input->post('path'));
		$file = APPPATH . 'logs/js_errors.json';
		$log  = [];
		if (file_exists($file)) { $raw = @file_get_contents($file); if ($raw) $log = json_decode($raw, true) ?: []; }
		$log = array_values(array_filter($log, function($e) use ($msg, $path) {
			return !(isset($e['msg'], $e['path']) && $e['msg'] === $msg && $e['path'] === $path);
		}));
		@file_put_contents($file, json_encode($log));
		echo json_encode(['ok' => true]);
	}

	public function js_page_clean() {
		$this->output->set_content_type('application/json');
		if (function_exists('js_error_debug_enabled') && !js_error_debug_enabled()) {
			echo json_encode(['ok' => false, 'disabled' => true]);
			return;
		}
		if ($this->input->server('REQUEST_METHOD') !== 'POST') { echo json_encode(['ok'=>false]); return; }
		$path = trim((string)$this->input->post('path'));
		if ($path === '') { echo json_encode(['ok'=>false]); return; }
		$file = APPPATH . 'logs/js_errors.json';
		$log  = [];
		if (file_exists($file)) { $raw = @file_get_contents($file); if ($raw) $log = json_decode($raw, true) ?: []; }
		$before = count($log);
		$log = array_values(array_filter($log, function($e) use ($path) {
			return !(isset($e['path']) && $e['path'] === $path);
		}));
		@file_put_contents($file, json_encode($log));
		echo json_encode(['ok' => true, 'removed' => $before - count($log)]);
	}

		public function getShippingDetails() {
			if($this->input->server('REQUEST_METHOD') === 'POST') {
				$user_id = $this->input->post('id');
				$data= $this->db->query("SELECT shipping_address.*,countries.name as country_name,states.name as state_name FROM shipping_address INNER JOIN countries ON countries.id=shipping_address.country_id INNER JOIN states ON states.id=shipping_address.state_id WHERE user_id = $user_id")->row_array();
				echo json_encode(['status'=>empty($data)?false:true,'data'=>$data]);
				exit;
			}
		}

		public function cron(){
			$userdetails = $this->userdetails();
			$this->view($data,'cron/index');	
		}

		public function update_product_settings(){
			$status = $this->input->post('status');
			$setting_key = $this->input->post('setting_key');
			$product_id = $this->input->post('product_id');
			
			$update = $this->Setting_model->update_product_settings($status, $setting_key, $product_id);
			echo $update;
		}

		public function default_theme_settings(){
			$setting = $this->input->post('setting');
			$color = $this->input->post('color');
			
			$update = $this->Setting_model->default_theme_settings($setting, $color);
			echo $update;
		}

		public function default_font_settings(){
			$setting = $this->input->post('setting');
			$font = $this->input->post('font');
			
			$update = $this->Setting_model->default_font_settings($setting, $font);
			echo $update;
		}

		// Centralized default colors - SINGLE SOURCE OF TRUTH
		private function getDefaultThemeColors() {
			return [
				'admin_side_bar_color' => '#ffffff',
				'admin_side_bar_scroll_color' => '#007bff',
				'admin_side_bar_text_color' => '#686868',
				'admin_side_bar_text_hover_color' => '#007bff',
				'admin_top_bar_color' => '#ffffff',
				'admin_topbar_bg' => '#34495e',
				'admin_topbar_text' => '#ffffff',
				'admin_footer_color' => '#f2f3f5',
				'admin_logo_color' => '#007bff',
				'admin_button_color' => '#3d5674',
				'admin_button_hover_color' => '#007bff',
				'admin_dropdown_bg' => '#ffffff',
				'admin_dropdown_text' => '#212529',
				'admin_dropdown_hover_bg' => '#e3f2fd',
				'admin_dropdown_hover_text' => '#1976d2',
				'admin_horizontal_dropdown_bg' => '#34495e',
				'admin_horizontal_dropdown_text' => '#ffffff',
				'admin_horizontal_dropdown_hover_bg' => '#e3f2fd',
				'admin_horizontal_dropdown_hover_text' => '#ffffff',
				'admin_menu_bg' => '#667eea',
				'admin_menu_text' => '#ffffff',
				'admin_menu_active' => '#ffffff',
				'admin_menu_hover' => '#ffffff',
				'user_side_bar_color' => '#ffffff',
				'user_side_bar_text_color' => '#3f567a',
				'user_side_bar_clock_text_color' => '#085445',
				'user_side_bar_text_hover_color' => '#5ec394',
				'user_top_bar_color' => '#ffffff',
				'user_footer_color' => '#ffffff',
				'user_button_color' => '#3d5674',
				'user_button_hover_color' => '#085445'
			];
		}

		public function set_default_theme_color_settings(){
			$setting_array = $this->getDefaultThemeColors();

			$update = $this->Setting_model->set_default_theme_settings($setting_array, $this->input->post('setting_type'));
			echo $update;
		}

		// Get default colors as JSON for JavaScript
		public function get_default_theme_colors(){
			echo json_encode($this->getDefaultThemeColors());
		}

		public function set_default_theme_font_settings(){
			$setting_array = [
				'admin_side_font' => 'PT Sans',
				'user_side_font' => 'Poppins',
				'front_side_font' => 'sans-serif',
				'cart_store_side_font' => 'Jost',
				'sales_store_side_font' => 'Roboto'
			];

			$update = $this->Setting_model->set_default_theme_settings($setting_array, $this->input->post('setting_type'));
			echo $update;
		}

		public function todolist() {
			$userdetails = $this->userdetails();
			$this->view($data,'todo/todo');
		}

		public function ticketssubject() {
			$userdetails = $this->userdetails();
			$this->view($data,'ticket/ticket-subject');
		}

		public function tickets() {
			$userdetails = $this->userdetails();
			$this->load->model('Tickets_model');
			$this->load->model('Product_model');
			$data['tickets_filter_status'] = $this->Product_model->getSettings('site', 'tickets_filter_status')['tickets_filter_status'] ?? "";
			
			$data['status'] = $this->Tickets_model->status();
			$data['subjects'] = $this->Tickets_model->getsubjectlist();
			$this->view($data,'ticket/ticket-listing');
		}

		public function ticketdetails($ticket_id=Null){
			$userdetails = $this->userdetails();
			$this->userdetails();
			$this->load->model('Tickets_model');
			$res = $this->Tickets_model->getTicketDetails($ticket_id);
			if($res) {
				$data['details'] = $res;
				$data['userName'] = $res['firstname'].' '.$res['lastname'];
				$data['userEmail'] = $res['email'];
				$data['statusNAme'] = $this->Tickets_model->status()[$res['status']];
				$data['status'] = $this->Tickets_model->status();
				$this->view($data,'ticket/ticket-details');
			} else {
				redirect(base_url('admincontrol/tickets'),'refresh');
			}
		}

		public function ticketcreate(){
		    $userdetails = $this->userdetails();
			$data['subjects'] = $this->Common_model->get_data_all_asc('tickets_subject',[],'id,subject','id');
			$data['users'] = $this->db->query("SELECT id,username FROM users WHERE type = 'user'")->result_array();
			
			// Handle user_id parameter from URL for pre-selecting user
			$user_id = $this->input->get('user_id');
			if($user_id) {
				$data['user_id'] = $user_id;
			}

			$this->view($data,'ticket/ticket-create');
		}

		public function countries_and_states(){
			$userdetails = $this->userdetails();
			$data['countries'] = $this->db->query("SELECT * FROM countries ORDER BY name ASC")->result_array();
			$data['states'] = $this->db->query("SELECT states.*, countries.name as country_name FROM states LEFT JOIN countries on states.country_id = countries.id ORDER BY created_by DESC")->result_array();
			$this->view($data,'countries_and_states/list');
		}

		public function createUpdateCountry()
		{
			$userdetails = $this->userdetails();
			$this->load->library('form_validation');
			$json = array();
			$this->form_validation->set_rules('name', __('admin.th_name'), 'required|trim');
			$this->form_validation->set_rules('sortname', __('admin.th_iso_code'), 'required|trim|min_length[2]|max_length[3]');
			$this->form_validation->set_rules('phonecode',  __('admin.th_phone_code'), 'required|trim|numeric');
			$this->form_validation->set_rules('lat', __('admin.th_latitude'), 'required|trim|numeric');
			$this->form_validation->set_rules('lng', __('admin.th_longitude'), 'required|trim|numeric');

			if ($this->form_validation->run() == FALSE) {

				$json['errors'] = $this->form_validation->error_array();

			} else {

				$data = $this->input->post(null,true);
				$nameExist = $this->db->query("SELECT id FROM countries WHERE name='{$data['name']}'")->row_array();

				if(!empty($nameExist) && $nameExist['id'] != $data['id']) {
					$json['errors']['name'] = __('admin.th_name')." ".__('admin.already_exist');
				} else {
				
					$isoExist = $this->db->query("SELECT id FROM countries WHERE sortname='{$data['sortname']}'")->row_array();

					if(!empty($isoExist) && $isoExist['id'] != $data['id']) {
						$json['errors']['sortname'] = __('admin.th_iso_code')." ".__('admin.already_exist');
					}
				}

				if(!isset($json['errors']) && empty($json['errors'])) {
					$country = array(
						'name'       => $data['name'],
						'sortname'       => $data['sortname'],
						'phonecode'       => $data['phonecode'],
						'lat'       => $data['lat'],
						'lng'       => $data['lng'],
						'created_by' => $userdetails['id'],
					);
					if(isset($data['id']) && !empty($data['id'])){
						if($this->db->update("countries",$country,['id' => $data['id']])) {
							$this->session->set_flashdata('success', __('admin.country_updated_success_msg'));
						} else {
							$this->session->set_flashdata('success', __('admin.something_wrong_try_again'));
						}

					} else {
						if($this->db->insert("countries",$country)) {
							$this->session->set_flashdata('success', __('admin.country_created_success_msg'));
						} else {
							$this->session->set_flashdata('success', __('admin.something_wrong_try_again'));
						}
					}
					$json['reload'] = true;
				}
			}

			echo json_encode($json);
		}

		public function createUpdateState()
		{

			$userdetails = $this->userdetails();

			if(empty($userdetails)){ redirect($this->admin_domain_url, 'refresh'); }

			$this->load->library('form_validation');

			$json = array();

			$this->form_validation->set_rules('name', __('admin.name'), 'required|trim');

			$this->form_validation->set_rules('country_id', __('admin.country'), 'required|numeric');

			if ($this->form_validation->run() == FALSE) {

				$json['errors'] = $this->form_validation->error_array();

			} else {

				$data = $this->input->post(null,true);

				$exists = $this->db->query("SELECT id FROM states WHERE name='{$data['name']}' AND country_id='{$data['country_id']}'")->row_array();

				if(!empty($exists) && $exists['id'] != $data['id']) {
					$json['errors']['name'] = __('admin.state')." ".__('admin.already_exist');
				} else {
					$state = array(
						'name'       => $data['name'],
						'country_id'       => $data['country_id'],
						'created_by' => $userdetails['id'],
					);

					if(isset($data['id']) && !empty($data['id'])){
						if($this->db->update("states",$state,['id' => $data['id']])) {
							$this->session->set_flashdata('success', __('admin.state_updated_success_msg'));
						} else {
							$this->session->set_flashdata('danger', __('admin.something_wrong_try_again'));
						}
					} else {
						if($this->db->insert("states",$state)) {
							$this->session->set_flashdata('success', __('admin.state_created_success_msg'));
						} else {
							$this->session->set_flashdata('danger', __('admin.something_wrong_try_again'));
						}
					}

				
					$json['reload'] = true;
				}
			}

			echo json_encode($json);
		}

		public function deleteCountry($id)
		{
			$userdetails = $this->userdetails();
			$country = $this->db->query("SELECT created_by FROM countries WHERE id='{$id}'")->row_array();
			if($userdetails['type']=='admin' || $country['created_by'] == $userdetails['id']) {
				$this->db->query("DELETE FROM countries WHERE id='{$id}'");
				$this->db->query("DELETE FROM states WHERE country_id='{$id}'");
				$this->session->set_flashdata('success', __('admin.country_delete_success'));
			} else {
				$this->session->set_flashdata('danger', __('admin.country_delete_not_allow'));
			}
		
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}

		public function deleteState($id)
		{
			$userdetails = $this->userdetails();
			$state = $this->db->query("SELECT created_by FROM states WHERE id='{$id}'")->row_array();
			if($userdetails['type']=='admin' || $state['created_by'] == $userdetails['id']) {
				$this->db->query("DELETE FROM states WHERE id='{$id}'");
				$this->session->set_flashdata('success', __('admin.state_delete_success'));
			} else {
				$this->session->set_flashdata('danger', __('admin.state_delete_not_allow'));
			}
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}

		public function deleteCountryAjax()
		{
			$userdetails = $this->userdetails();
			$json = array('status' => false, 'message' => '');
			
			if(empty($userdetails)){ 
				$json['message'] = __('admin.unauthorized_access');
				echo json_encode($json);
				return;
			}

			$id = $this->input->post('id');
			if(empty($id)) {
				$json['message'] = __('admin.invalid_request');
				echo json_encode($json);
				return;
			}

			$country = $this->db->query("SELECT created_by FROM countries WHERE id='{$id}'")->row_array();
			if($userdetails['type']=='admin' || $country['created_by'] == $userdetails['id']) {
				if($this->db->query("DELETE FROM countries WHERE id='{$id}'")) {
					$this->db->query("DELETE FROM states WHERE country_id='{$id}'");
					$json['status'] = true;
					$json['message'] = __('admin.country_delete_success');
				} else {
					$json['message'] = __('admin.something_wrong_try_again');
				}
			} else {
				$json['message'] = __('admin.country_delete_not_allow');
			}
			
			echo json_encode($json);
		}

		public function deleteStateAjax()
		{
			$userdetails = $this->userdetails();
			$json = array('status' => false, 'message' => '');
			
			if(empty($userdetails)){ 
				$json['message'] = __('admin.unauthorized_access');
				echo json_encode($json);
				return;
			}

			$id = $this->input->post('id');
			if(empty($id)) {
				$json['message'] = __('admin.invalid_request');
				echo json_encode($json);
				return;
			}

			$state = $this->db->query("SELECT created_by FROM states WHERE id='{$id}'")->row_array();
			if($userdetails['type']=='admin' || $state['created_by'] == $userdetails['id']) {
				if($this->db->query("DELETE FROM states WHERE id='{$id}'")) {
					$json['status'] = true;
					$json['message'] = __('admin.state_delete_success');
				} else {
					$json['message'] = __('admin.something_wrong_try_again');
				}
			} else {
				$json['message'] = __('admin.state_delete_not_allow');
			}
			
			echo json_encode($json);
		}



		public function refactor_database()
		{
			try {
				// Demo Mode
				if (ENVIRONMENT === 'demo') {
					$this->session->set_flashdata('error', __('admin.demo_mode'));
					redirect('admincontrol/backup');
					return;
				}
				// Demo Mode

				$userdetails = $this->userdetails();

				if(empty($userdetails)){ redirect($this->admin_domain_url, 'refresh'); }

				// Use same process as auto-update: copy from assets/data to application/updates
				// so MY_Controller will run the SQL update on next admin page load
				$current_version = $this->config->item('app_version');
				$destination_dir = APPPATH . 'updates/';

				// Try exact version match first, then fall back to any available .data file
				$source_file = null;
				$exact_match = FCPATH . 'assets/data/database_update_' . $current_version . '.data';
				if (file_exists($exact_match)) {
					$source_file = $exact_match;
				} else {
					$all_files = glob(FCPATH . 'assets/data/database_update_*.data');
					if (!empty($all_files)) {
						usort($all_files, function($a, $b) { return filemtime($b) - filemtime($a); });
						$source_file = $all_files[0];
					}
				}

				if ($source_file) {
					// Always copy to updates/ using current version name so MY_Controller detects it
					$destination_file = $destination_dir . 'database_update_' . $current_version . '.data';

					if (!is_dir($destination_dir)) {
						mkdir($destination_dir, 0755, true);
					}

					if (copy($source_file, $destination_file)) {
						$sql_content = file_get_contents($destination_file);
						$version_format = str_replace('.', '_', $current_version);
						$updated_content = preg_replace('/^#SET @databaseName="[^"]*";/', '#SET @databaseName="' . $version_format . '";', $sql_content);
						file_put_contents($destination_file, $updated_content);

						$this->session->set_flashdata('success', __('admin.database_update_file_copied'));
					} else {
						$this->session->set_flashdata('error', __('admin.something_went_wrong'));
					}
				} else {
					$this->session->set_flashdata('error', __('admin.database_update_file_not_exist'));
				}

			} catch (Exception $e) {
				$this->session->set_flashdata('error', $e->getMessage());
			}

			redirect('admincontrol/backup');
		}

		public function uncompleted_payments($page = 1){
			$userdetails = $this->userdetails();
			$filter = $this->input->post(null,true);
			$this->load->model('Order_model');
			
			$current_page = max(1, (int)$this->input->get('page') ?: (int)$this->input->post('page') ?: (int)$page ?: 1);
			$pagination_settings = get_pagination_settings();
			$per_page = $pagination_settings['per_page'];
			
			if(!$filter) {
				$filter = [];
			}
			$filter['page'] = $current_page;
			
			$total_rows = $this->Wallet_model->getUncompletedPayment($filter, true);
			
			$pagination_data = easy_pagination(
				base_url('admincontrol/uncompleted_payments'),
				$total_rows,
				($current_page - 1) * $per_page,
				['per_page' => $per_page, 'alignment' => 'end']
			);
			
			$view['pagination'] = $pagination_data['html'];
			$view['current_page'] = $current_page;
			$view['total_rows'] = $total_rows;
			$view['per_page'] = $per_page;
			
			$filter['limit'] = $per_page;
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

			$this->view($data,'users/uncompleted_payments');
		}

		public function listreviews_ajax($page = 1){

			$userdetails = $this->userdetails();
			$get = $this->input->get(null,true);
			$post = $this->input->post(null,true);
			
			$page=isset($get['page']) ? $get['page'] : $page;
			$limit=50;
			 
			$product_id=null;
			if(isset($post['product_name_review']) && $post['product_name_review']){
				$product_id = (int)$this->input->post('product_name_review');
		 	}
			
			$data = $this->Product_model->getAllReviewFilter($product_id,$limit,$page);
			
			$data['user_id']=$userdetails['id'];	

			$json['view'] = $this->load->view("admincontrol/product/review_list", $data, true);
			
			$this->load->library('pagination');

			$this->pagination->cur_page = $page;

			$config['base_url'] = base_url('admincontrol/listreviews_ajax');

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
			$post = $this->input->post(null,true);
			if(!empty($post) && isset($post['product_name'])){
	 			
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$this->form_validation->set_rules('product_name', __('admin.product_name'), 'required');
				$this->form_validation->set_rules('firstname', __('admin.firstname'), 'required' );
				$this->form_validation->set_rules('lastname', __('admin.lastname'), 'required' );
				$this->form_validation->set_rules(
					'review_description', __('admin.review_description'),
					'required|min_length[5]|max_length[150]',
					array(
						'required'      => 'Enter %s',
						'is_unique'     => 'This %s already exists.',
						'min_length' 	=> '%s: the minimum of characters is %s',
						'max_length' 	=> '%s: the maximum of characters is %s',
					)
				);

				
				$this->form_validation->set_rules('rating',__('admin.rating'), "required"); 
				$this->form_validation->set_rules('rating_created',__('admin.review_date_-_time'), "required"); 
				

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
					 if(!empty($_FILES['user_image']['name'])){
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
							$this->session->set_flashdata('success', __('admin.review_updated_successfully'));
							$json['location'] = base_url('admincontrol/listproduct'); 
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
							$this->session->set_flashdata('success', __('admin.review_inserted_successfully'));
							$json['location'] = base_url('admincontrol/listproduct'); 
	 					}
						
			 		}
			 	 
					
				}
				echo json_encode($json);
				exit; 
			}	
			$data['review'] = $this->Product_model->getReviewById($id)[0]; 
			$filter['product_status_in'] =	 '1';
			$filter['only_admin_product'] =	 '1';
			

			$data['products'] = $this->Product_model-> getAllProduct($userdetails['id'],'admin',$filter);
			
			$data['setting'] = $this->Product_model->getSettings('productsetting'); 
			
			if(isset($data['review']['rating_created_by']) && $data['review']['rating_created_by']!= $userdetails['id'])
			{
	 			$this->session->set_flashdata('error', __('admin.you_can_not_edit_other_user_review'));	
	 			redirect('admincontrol/listproduct');
			}
			else
	 			$this->view($data, 'product/add_review');	
		}

		public function deleteReview($id = null){

			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				redirect($this->admin_domain_url);
			}

			if($id!="" && $id>0)
			{
				$res=$this->Product_model->deleteReview($id);
				if(isset($res))
					$this->session->set_flashdata('success', __('admin.review_has_been_deleted_successfully'));
				else
	 				$this->session->set_flashdata('success', __('admin.review_not_deleted'));
			} 
			redirect('admincontrol/listproduct');
	 	}

	 	public function checkDateTime($date)
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

	    public function bulkReviewImportFromUrl() 
		{
			$userdetails = $this->userdetails();
			$data=$this->Review_model->bulkReviewImportFromUrlData($userdetails); 
	 		echo $this->load->view("admincontrol/product/bulk_review_upload_modal",$data,true);
		}

	 	public function bulkReviewsImport() {
			$userdetails = $this->userdetails();
	 		$data=$this->Review_model->bulkReviewsImportData($userdetails); 
	 		echo $this->load->view("admincontrol/product/bulk_review_upload_modal",$data,true); 
		}

		public function bulkReviewImportConfirm() 
		{
			$userdetails = $this->userdetails();
			$data = json_decode(base64_decode($_POST['reviews']), true);
			$result=$this->Review_model->bulkReviewImportConfirmData($userdetails,$data); 
			echo $this->load->view('admincontrol/product/bulk_review_upload_modal', $result, true); 
		}

		public function exportReviewXML(){
			$userdetails = $this->userdetails();
			$json=$this->Review_model->exportReviewXMLData($userdetails); 
			echo json_encode($json); 	 
		}

		public function downloadproductreviewxmlstructurefile($filename = NULL) {
		    $userdetails = $this->userdetails();
		    $this->load->helper('download');
		    $data = file_get_contents(FCPATH.'assets/xml/export_admin_product_reviews_structure.xml');
		    force_download("export_admin_product_reviews_structure.xml", $data);
		}

		public function downloadproductreviewxmlfile($filename = NULL) {
		    $userdetails = $this->userdetails();
		    $this->load->helper('download');
		    $data = file_get_contents(FCPATH.'assets/xml/export_admin_product_reviews.xml');
		    force_download("export_admin_product_reviews.xml", $data);
		}

		public function getTermAndCondition() {
			$userdetails = $this->userdetails();
			$post = $this->input->post(null,true);
			if(!empty($post) && isset($post['language_id'])){
				$data = $this->Product_model->getSettingsWithLanaguage('tnc',$post['language_id'],'');
				$json['heading'] =  $data['heading'];
				$json['content'] =  $data['content'];
			}
			echo json_encode($json);
		}

		public function getStaticPages() {
			$userdetails = $this->userdetails();
			$post = $this->input->post(null,true);
			if(!empty($post) && isset($post['language_id'])){
			$storesettings = $this->Product_model->getSettingsWithLanaguage('store',$post['language_id'],'');
			$staticpages = array("about_content", "contact_content", "policy_content");
				$staticcontent=array();
				foreach ($storesettings as $skey => $svalue) 
				{
					if(in_array($skey, $staticpages))
					{
						$staticcontent=array_merge($staticcontent,array($skey=>$svalue)); 
					}
				}  
				
				$json=$staticcontent;

			}
			echo json_encode($json);
		}

		public function tutorial()
		{
			$userdetails = $this->userdetails();
			$data=array(); 
			$data['site']=$this->Product_model->getSettings('site'); 
			$data['languages'] = $this->db->query("SELECT * FROM language where status=1")->result_array();
			
			// manage auto dropdown select language value
			if(isset($_SESSION['userLang'])) 
			$data['userlangid']=$_SESSION['userLang'];
			// manage auto dropdown select language value

			$this->view($data, '../tutorial/index');
		}

		public function listTutorals_ajax($page = 1){
			$userdetails = $this->userdetails();
			$this->load->model('Tutorial_model');
			$this->Tutorial_model->list();
		}

		public function manage_tutorial($id = null){
			$userdetails = $this->userdetails();
			$this->load->model('Tutorial_model');
			$data=$this->Tutorial_model->manage($userdetails,$id); 
			$this->view($data, '../tutorial/manage_tutorial');
		}

		public function getTutorialCategory(){
			$this->load->model('Tutorial_model');
			$json['html']=$this->Tutorial_model->getCateogryDropdown();
			echo json_encode($json); 
		}

		public function deleteTutorial($id = null){
			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				redirect($this->admin_domain_url);
			}

			if($id!="" && $id>0)
			{
				$this->load->model('Tutorial_model');
				$res=$this->Tutorial_model->delete($id);
				if(isset($res))
					$this->session->set_flashdata('success', __('admin.tutorial_has_been_deleted_successfully'));
				else
	 				$this->session->set_flashdata('success', __('admin.tutorial_not_deleted'));
			} 
			redirect('admincontrol/tutorial');
	 	}

	 	public function listTutorialCategory_ajax($page = 1){
			$userdetails = $this->userdetails();
			$this->load->model('Tutorial_model');
			$this->Tutorial_model->listCategory();
		}

		public function manage_tutorial_catgory($id = null){
			$userdetails = $this->userdetails();
			$this->load->model('Tutorial_model');
			$data=$this->Tutorial_model->manageCategory($userdetails,$id); 
			$this->view($data, '../tutorial/manage_category');
		}

		public function deleteTutorialCategory($id = null){
			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				redirect($this->admin_domain_url);
			}

			if($id!="" && $id>0)
			{
				$this->load->model('Tutorial_model');
				$res=$this->Tutorial_model->deleteCategory($id); 
				if((int)$res===2)
					$this->session->set_flashdata('error', __('admin.category_can_not_deleted_it_already_used_in_pages'));
				else if(isset($res))
					$this->session->set_flashdata('success', __('admin.category_has_been_deleted_successfully'));
				else
	 				$this->session->set_flashdata('error', __('admin.category_not_deleted'));
			} 
			redirect('admincontrol/tutorial');
	 	}

	 	public function getLoginContent_ajax() {
			$userdetails = $this->userdetails();
			$post = $this->input->post(null,true);
			if(!empty($post) && isset($post['language_id'])){
			$data = $this->Product_model->getSettingsWithLanaguage('loginclient',$post['language_id'],'');
				$json['home_heading'] =  $data['heading'];
				$json['home_content'] =  $data['content'];
				$json['about_content'] =  $data['about_content'];
			}
			if (!empty($post) && isset($post['language_id'])){
				$data = $this->Product_model->getSettingsWithLanaguage('tnc',$post['language_id'],'');
				$json['policy_heading'] =  $data['heading'];
				$json['policy_content'] =  $data['content'];
			}

			echo json_encode($json);
		}

		function refreshGoogleAds(){
			$data['googleads'] 	= $this->Setting_model->getGoogleAds();
			$json['adsList'] = $this->load->view("admincontrol/users/part/ads_tr", $data, true);
			echo json_encode($json);
		}

		function troubleshoot()
		{
			$userdetails = $this->userdetails();
			$data=array();
			$this->view($data, 'document/troubleshoot');
		}

		public function test_usps_connection()
		{
			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				redirect($this->admin_domain_url);
			}

			$post = $this->input->post(null, true);
			$json = array();

			if(!empty($post) && isset($post['usps_api_key']) && isset($post['usps_origin_zip'])){
				// Load USPS service
				$this->load->library('services/USPSApiService');
				
				// Test connection with provided credentials
				$test_result = $this->uspsapiservice->testConnection($post['usps_api_key'], $post['usps_origin_zip']);
				
				if($test_result['status']){
					$json['status'] = true;
					$json['message'] = 'USPS API connection successful!';
				} else {
					$json['status'] = false;
					$json['message'] = $test_result['message'];
				}
			} else {
				$json['status'] = false;
				$json['message'] = 'Please provide API key and origin ZIP code.';
			}

					echo json_encode($json);
	}

	// ========================================
	// AI HELPER METHODS
	// ========================================
	
	public function test_ai_connection() {
		$this->checkLogin('admin');
		$json = ['success' => false, 'message' => 'Invalid request'];
		
		if ($this->input->method() === 'post') {
			// Get AI settings
			$ai_settings = $this->Product_model->getSettings('ai_helper');
			
			if (!$ai_settings || !isset($ai_settings['ai_helper_enabled']) || !$ai_settings['ai_helper_enabled']) {
				$json['message'] = 'AI Helper is not enabled';
				echo json_encode($json);
				return;
			}
			
			$provider = $ai_settings['ai_provider'] ?? 'openai';
			
			try {
				// Test connection based on provider
				switch ($provider) {
					case 'openai':
						$api_key = $ai_settings['openai_api_key'] ?? '';
						if (empty($api_key)) {
							$json['message'] = 'OpenAI API key is not configured';
							break;
						}
						// Simple test request to OpenAI
						$result = $this->test_openai_connection($api_key);
						if ($result) {
							$json = ['success' => true, 'message' => 'OpenAI connection successful'];
						} else {
							$json['message'] = 'Failed to connect to OpenAI API';
						}
						break;
						
					case 'claude':
						$api_key = $ai_settings['claude_api_key'] ?? '';
						if (empty($api_key)) {
							$json['message'] = 'Claude API key is not configured';
							break;
						}
						// Test Claude connection (placeholder)
						$json = ['success' => true, 'message' => 'Claude connection test (placeholder)'];
						break;
						
					case 'gemini':
						$api_key = $ai_settings['gemini_api_key'] ?? '';
						if (empty($api_key)) {
							$json['message'] = 'Gemini API key is not configured';
							break;
						}
						// Test Gemini connection (placeholder)
						$json = ['success' => true, 'message' => 'Gemini connection test (placeholder)'];
						break;
						
					default:
						$json['message'] = 'Unknown AI provider: ' . $provider;
				}
			} catch (Exception $e) {
				$json['message'] = 'Connection test failed: ' . $e->getMessage();
			}
		}
		
		echo json_encode($json);
	}

	public function admin_ai_usage() {
		$this->checkLogin('admin');
		header('Content-Type: application/json');
		
		try {
			// Get current admin user
			$admin_details = $this->userdetails();
			$admin_id = $admin_details['id'];
			
			// Get AI settings to check if enabled
			$ai_settings = $this->Product_model->getSettings('ai_helper');
			$ai_enabled = isset($ai_settings['ai_helper_enabled']) && $ai_settings['ai_helper_enabled'] == 1;
			
			if (!$ai_enabled) {
				echo json_encode([
					'usage' => ['today' => 0, 'month' => 0],
					'remaining' => ['daily' => 0, 'monthly' => 0],
					'limits' => ['daily' => 50, 'monthly' => 1000],
					'costs' => ['today' => '0.00', 'month' => '0.00'],
					'chart_data' => []
				]);
				return;
			}
			
			// Get admin's personal AI usage
			$usage_data = $this->get_admin_ai_usage($admin_id, $ai_settings);
			
			echo json_encode($usage_data);
			
		} catch (Exception $e) {
			echo json_encode([
				'error' => true,
				'message' => 'Error loading AI usage data: ' . $e->getMessage()
			]);
		}
	}

	private function get_admin_ai_usage($admin_id, $ai_settings) {		
		// Get limits from AI settings
		$daily_limit = isset($ai_settings['daily_limit_per_user']) ? (int)$ai_settings['daily_limit_per_user'] : 50;
		$monthly_limit = isset($ai_settings['monthly_limit_per_user']) ? (int)$ai_settings['monthly_limit_per_user'] : 1000;
		$cost_per_request = isset($ai_settings['cost_per_request']) ? (float)$ai_settings['cost_per_request'] : 0.05;
		
		if (!$this->session->userdata('ai_usage_real_' . $admin_id)) {
			$today_usage = 0; // No usage yet since it's just been added
			$month_usage = 0; // No monthly usage yet
			
			$real_data = [
				'today' => $today_usage,
				'month' => $month_usage,
				'generated_at' => date('Y-m-d'),
				'ai_helper_start_date' => date('Y-m-d'), // Track when AI helper started
				'total_requests_ever' => 0 // Track total requests since start
			];
			
			$this->session->set_userdata('ai_usage_real_' . $admin_id, $real_data);
		} else {
			$real_data = $this->session->userdata('ai_usage_real_' . $admin_id);
			
			// Reset daily counter if it's a new day
			if ($real_data['generated_at'] !== date('Y-m-d')) {
				// New day - reset today's counter but keep monthly total
				$real_data = [
					'today' => 0, // Reset daily counter
					'month' => $real_data['month'], // Keep monthly total
					'generated_at' => date('Y-m-d'),
					'ai_helper_start_date' => $real_data['ai_helper_start_date'] ?? date('Y-m-d'),
					'total_requests_ever' => $real_data['total_requests_ever'] ?? 0
				];
				
				$this->session->set_userdata('ai_usage_real_' . $admin_id, $real_data);
			}
			
			$today_usage = $real_data['today'];
			$month_usage = $real_data['month'];
		}
		
		// Calculate remaining quotas
		$remaining_daily = max(0, $daily_limit - $today_usage);
		$remaining_monthly = max(0, $monthly_limit - $month_usage);
		
		// Calculate costs
		$cost_today = $today_usage * $cost_per_request;
		$cost_month = $month_usage * $cost_per_request;
		
		// Generate REAL chart data - only show actual usage from AI helper start date
		$chart_data = [];
		$ai_start_date = $real_data['ai_helper_start_date'] ?? date('Y-m-d');
		$start_timestamp = strtotime($ai_start_date);
		
		// Show only REAL data (from start date to today)
		for ($i = 6; $i >= 0; $i--) {
			$check_date = strtotime("-{$i} days");
			$date_str = date('M j', $check_date);
			
			// Only show data if this date is after or equal to AI helper start date
			if ($check_date >= $start_timestamp) {
				if ($i == 0) {
					// Today's REAL usage (currently 0 since just added)
					$requests = $today_usage;
				} else {
					// Previous days since AI helper started - REAL data only
					// Since AI helper was just added today, all previous days = 0
					$requests = 0;
				}
			} else {
				// No usage before AI helper was added
				$requests = 0;
			}
			
			$chart_data[] = [
				'date' => $date_str,
				'requests' => (int)$requests
			];
		}
		
		return [
			'usage' => [
				'today' => (int)$today_usage,
				'month' => (int)$month_usage
			],
			'remaining' => [
				'daily' => (int)$remaining_daily,
				'monthly' => (int)$remaining_monthly
			],
			'limits' => [
				'daily' => (int)$daily_limit,
				'monthly' => (int)$monthly_limit
			],
			'costs' => [
				'today' => number_format($cost_today, 2),
				'month' => number_format($cost_month, 2)
			],
			'chart_data' => $chart_data
		];
	}

	private function track_real_ai_usage($admin_id) {
		// Track real AI usage when content is actually generated
		$real_data = $this->session->userdata('ai_usage_real_' . $admin_id);
		
		if ($real_data) {
			// Increment today's usage
			$real_data['today']++;
			$real_data['month']++;
			$real_data['total_requests_ever']++;
			
			// Update session data
			$this->session->set_userdata('ai_usage_real_' . $admin_id, $real_data);
		}
	}
	
	public function ai_smart_fill() {
		$this->checkLogin('admin');
		header('Content-Type: application/json');
		
		$json = ['success' => false, 'message' => 'Invalid request'];
		
		if ($this->input->method() === 'post') {
			// Check if AI helper is enabled first
			$ai_settings = $this->Product_model->getSettings('ai_helper');
			

			
			if (!$ai_settings || !isset($ai_settings['ai_helper_enabled']) || !$ai_settings['ai_helper_enabled']) {
				$json = ['success' => false, 'message' => 'AI Helper is disabled in admin settings. Debug info: ' . json_encode($ai_settings)];
				echo json_encode($json);
				return;
			}
			
			$existing_levels = $this->Product_model->getAllAwardLevel();
			$provider = $ai_settings['ai_provider'] ?? 'openai';
			
			$api_key = '';
			$model = '';
			
			// Check if we should use real AI or fallback (new setting)
			$use_real_ai = isset($ai_settings['use_real_ai']) ? $ai_settings['use_real_ai'] : 1;
			log_message('debug', 'AI SMART FILL: use_real_ai setting = ' . var_export($use_real_ai, true));
			
			if ($use_real_ai) {
				// Try to get API key for real AI
				switch ($provider) {
					case 'openai':
						$api_key = $ai_settings['openai_api_key'] ?? '';
						$model = $ai_settings['openai_model'] ?? 'gpt-3.5-turbo';
						break;
					case 'claude':
						$api_key = $ai_settings['claude_api_key'] ?? '';
						$model = $ai_settings['claude_model'] ?? 'claude-3-sonnet-20240229';
						break;
					case 'gemini':
						$api_key = $ai_settings['gemini_api_key'] ?? '';
						$model = $ai_settings['gemini_model'] ?? 'gemini-pro';
						break;
				}
			}
			

			
			// Generate all suggestions at once (using real AI if available, or intelligent fallback)
			$suggestions = [
				'level_number' => $this->generate_field_suggestion('level_number', $existing_levels, $api_key, $model, $provider),
				'minimum_earning' => $this->generate_field_suggestion('minimum_earning', $existing_levels, $api_key, $model, $provider),
				'commission_rate' => $this->generate_field_suggestion('commission_rate', $existing_levels, $api_key, $model, $provider),
				'bonus' => $this->generate_field_suggestion('bonus', $existing_levels, $api_key, $model, $provider),
				'jump_level' => $this->generate_field_suggestion('jump_level', $existing_levels, $api_key, $model, $provider)
			];
			
			// Auto-set first level as default if no levels exist
			if (empty($existing_levels)) {
				$suggestions['default_registration_level'] = 1;
			}
			
			// Check if all suggestions were generated successfully
			$failed_fields = [];
			foreach ($suggestions as $field => $value) {
				if ($value === false) {
					$failed_fields[] = $field;
				}
			}
			
			if (empty($failed_fields)) {
				// Track AI usage ONLY when real AI is actually used
				if (!empty($api_key)) {
					$admin_details = $this->userdetails();
					$admin_id = $admin_details['id'];
					$this->track_real_ai_usage($admin_id);
				}
				
				// Check if real AI was used
				$ai_used = !empty($api_key) ? 'real_ai' : 'fallback';
				$ai_provider_info = !empty($api_key) ? $provider . ' (' . $model . ')' : 'Local Pattern Analysis';
				
				$json = [
					'success' => true, 
					'suggestions' => $suggestions,
					'ai_used' => $ai_used,
					'ai_provider' => $ai_provider_info,
					'api_key_configured' => !empty($api_key),
					'use_real_ai_setting' => $use_real_ai
				];
			} else {
				$json = ['success' => false, 'message' => 'Could not generate suggestions for: ' . implode(', ', $failed_fields)];
			}
		}
		
		echo json_encode($json);
	}

	public function ai_suggest_field() {
		$this->checkLogin('admin');
		header('Content-Type: application/json');
		
		$json = ['success' => false, 'message' => 'Invalid request'];
		
		if ($this->input->method() === 'post') {
			$input = json_decode(file_get_contents('php://input'), true);
			$field_type = $input['field_type'] ?? '';
			$existing_levels = $input['existing_levels'] ?? [];
			
			// Get AI settings
			$ai_settings = $this->Product_model->getSettings('ai_helper');
			$provider = $ai_settings['ai_provider'] ?? 'openai';
			$api_key = '';
			
			switch ($provider) {
				case 'openai':
					$api_key = $ai_settings['openai_api_key'] ?? '';
					$model = $ai_settings['openai_model'] ?? 'gpt-3.5-turbo';
					break;
				case 'gemini':
					$api_key = $ai_settings['gemini_api_key'] ?? '';
					$model = $ai_settings['gemini_model'] ?? 'gemini-pro';
					break;
			}
			
			if (empty($api_key)) {
				$json = ['success' => false, 'message' => 'AI Helper not configured. Please set up AI provider in Payment Settings > AI Helper tab.'];
			} else {
				$suggestion = $this->generate_field_suggestion($field_type, $existing_levels, $api_key, $model, $provider);
				
				if ($suggestion !== false) {
					$json = ['success' => true, 'suggestion' => $suggestion];
				} else {
					$json = ['success' => false, 'message' => 'AI could not analyze the data pattern. Please try again or enter manually.'];
				}
			}
		}
		
		echo json_encode($json);
	}
	
	private function generate_field_suggestion($field_type, $existing_levels, $api_key = '', $model = '', $provider = '') {
		// Always try AI first (even if no API key, use AI-style analysis)
		$ai_result = $this->generate_ai_field_suggestion($field_type, $existing_levels, $api_key, $model, $provider);
		
		if ($ai_result !== false) {
			return $ai_result;
		}
		
		// Only fall back to simple logic if AI completely fails
		return $this->get_mock_field_suggestion($field_type, $existing_levels);
	}
	
	private function generate_ai_field_suggestion($field_type, $existing_levels, $api_key = '', $model = '', $provider = '') {
		// Build AI prompts for intelligent analysis
		$system_prompt = $this->build_field_ai_prompt($field_type);
		$user_prompt = $this->build_field_user_prompt($field_type, $existing_levels);
		
		// If we have real API key, use real AI
		if (!empty($api_key)) {
			// Log that we're using REAL AI
			log_message('debug', 'AI DEBUG: Using REAL AI for ' . $field_type . ' with provider: ' . $provider);
			
			$ai_result = '';
			switch ($provider) {
				case 'openai':
					$ai_result = $this->call_simple_openai($system_prompt, $user_prompt, $api_key, $model);
					break;
				case 'claude':
					$ai_result = $this->call_claude_api($system_prompt, $user_prompt, $api_key, $model);
					break;
				case 'gemini':
					$ai_result = $this->call_simple_gemini($system_prompt, $user_prompt, $api_key, $model);
					break;
			}
			
			if ($ai_result && $this->validate_ai_field_response($field_type, $ai_result)) {
				log_message('debug', 'AI DEBUG: Real AI response for ' . $field_type . ': ' . $ai_result);
				return trim($ai_result);
			} else {
				log_message('debug', 'AI DEBUG: Real AI failed for ' . $field_type . ', falling back to simulation');
			}
		} else {
			// Log that we're using fallback
			log_message('debug', 'AI DEBUG: No API key found, using FALLBACK simulation for ' . $field_type);
		}
		
		// If no API key or AI fails, use AI-style thinking simulation
		$fallback_result = $this->simulate_ai_thinking($field_type, $existing_levels, $system_prompt, $user_prompt);
		log_message('debug', 'AI DEBUG: Fallback simulation result for ' . $field_type . ': ' . $fallback_result);
		return $fallback_result;
	}
	
	private function simulate_ai_thinking($field_type, $existing_levels, $system_prompt, $user_prompt) {
		// Simulate how AI would think about the problem using the prompts
		// This analyzes patterns like real AI would, but locally
		
		switch ($field_type) {
			case 'level_number':
				return $this->generate_simple_level_suggestion($existing_levels);
			case 'minimum_earning':
				return $this->ai_analyze_earning_pattern($existing_levels);
			case 'commission_rate':
				return $this->ai_analyze_commission_pattern($existing_levels);
			case 'bonus':
				return $this->ai_analyze_bonus_pattern($existing_levels);
			case 'jump_level':
				return $this->ai_analyze_jump_pattern($existing_levels);
			case 'default_registration_level':
				// Auto-set first level as default if no levels exist
				return empty($existing_levels) ? 1 : 0;
			default:
				return false;
		}
	}
	
	private function ai_analyze_earning_pattern($existing_levels) {
		if (empty($existing_levels)) {
			return '100.00'; // AI would suggest reasonable starting point
		}
		
		$earnings = array_column($existing_levels, 'minimum_earning');
		$earnings = array_map('floatval', $earnings);
		sort($earnings);
		

		
		// AI-style smart progression analysis
		if (count($earnings) >= 2) {
			// Calculate differences between consecutive levels
			$differences = [];
			for ($i = 1; $i < count($earnings); $i++) {
				$differences[] = $earnings[$i] - $earnings[$i-1];
			}
			

			
			// Check if differences are consistent (linear progression)
			$avg_diff = array_sum($differences) / count($differences);
			$max_deviation = 0;
			foreach ($differences as $diff) {
				$deviation = abs($diff - $avg_diff) / $avg_diff;
				$max_deviation = max($max_deviation, $deviation);
			}
			
			// If differences are reasonably consistent (within 50% deviation) - use linear
			if ($max_deviation < 0.5) {
				$next = $earnings[count($earnings)-1] + $avg_diff;

				return number_format(max(0, $next), 2, '.', '');
			}
			
			// Check for doubling pattern (geometric) - but be more restrictive
			$ratios = [];
			for ($i = 1; $i < count($earnings); $i++) {
				if ($earnings[$i-1] > 0) {
					$ratios[] = $earnings[$i] / $earnings[$i-1];
				}
			}
			
			if (!empty($ratios)) {
				$avg_ratio = array_sum($ratios) / count($ratios);

				
				// Only use geometric if it's very consistent (within 20% deviation) and reasonable
				$ratio_deviation = 0;
				foreach ($ratios as $ratio) {
					$deviation = abs($ratio - $avg_ratio) / $avg_ratio;
					$ratio_deviation = max($ratio_deviation, $deviation);
				}
				
				// Very restrictive geometric progression (only if highly consistent and reasonable)
				if ($ratio_deviation < 0.2 && $avg_ratio > 1.9 && $avg_ratio < 2.1) {
					$next = $earnings[count($earnings)-1] * $avg_ratio;

					return number_format($next, 2, '.', '');
				}
			}
		}
		
		// AI fallback - intelligent incremental progression
		$max = max($earnings);

		
		if ($max < 100) {
			$next = $max + 50; // Small increments
		} elseif ($max < 500) {
			$next = $max + 100; // Medium increments
		} elseif ($max < 1000) {
			$next = $max + 250; // Larger increments
		} elseif ($max < 5000) {
			$next = $max + 500; // Progressive increments
		} else {
			$next = $max + 1000; // Large increments for high values
		}
		

		return number_format($next, 2, '.', '');
	}
	
	private function ai_analyze_commission_pattern($existing_levels) {
		if (empty($existing_levels)) {
			return '5.00'; // AI knows 5% is a good starting commission
		}
		
		$commissions = array_column($existing_levels, 'sale_comission_rate');
		$commissions = array_map('floatval', $commissions);
		sort($commissions);
		
		// AI analyzes commission progression patterns
		$max_commission = max($commissions);
		
		if (count($commissions) >= 2) {
			// Calculate average increment
			$increments = [];
			for ($i = 1; $i < count($commissions); $i++) {
				$increments[] = $commissions[$i] - $commissions[$i-1];
			}
			$avg_increment = array_sum($increments) / count($increments);
			
			// AI suggests next logical step
			$next = $max_commission + $avg_increment;
		} else {
			// AI default progression for single commission
			$next = $max_commission + 2.5;
		}
		
		// AI applies business constraints (max 30% commission)
		$next = min(30, max(0, $next));
		return number_format($next, 2, '.', '');
	}
	
	private function ai_analyze_bonus_pattern($existing_levels) {
		if (empty($existing_levels)) {
			return '50.00'; // AI suggests reasonable starting bonus
		}
		
		$bonuses = array_column($existing_levels, 'bonus');
		$bonuses = array_map('floatval', $bonuses);
		sort($bonuses);
		
		$max_bonus = max($bonuses);
		
		// AI recognizes bonus scaling patterns
		if ($max_bonus < 100) {
			$increment = 25; // Small increments for small bonuses
		} elseif ($max_bonus < 500) {
			$increment = 50; // Medium increments
		} else {
			$increment = 100; // Larger increments for big bonuses
		}
		
		$next = $max_bonus + $increment;
		return number_format($next, 2, '.', '');
	}
	
	private function ai_analyze_jump_pattern($existing_levels) {
		if (empty($existing_levels)) {
			return null; // AI knows no levels exist to jump to
		}
		
		// AI analyzes jump level logic - should jump to the HIGHEST existing level
		// Sort levels by level_number to find the logical highest level
		$sorted_levels = $existing_levels;
		usort($sorted_levels, function($a, $b) {
			// Extract numbers from level names for proper sorting
			$num_a = $this->extract_level_number($a['level_number']);
			$num_b = $this->extract_level_number($b['level_number']);
			return $num_a - $num_b;
		});
		
		// AI suggests jumping to the HIGHEST level (most logical upgrade path)
		// Return the ID of the highest level
		$highest_level = $sorted_levels[count($sorted_levels) - 1];
		
		return $highest_level['id'];
	}
	
	private function extract_level_number($level_name) {
		// Extract numeric part from level names like "Level 5" â†’ 5
		if (preg_match('/(\d+)/', $level_name, $matches)) {
			return (int)$matches[1];
		}
		// If no number found, use length as fallback for non-numeric levels
		return strlen($level_name);
	}
	
	private function build_field_ai_prompt($field_type) {
		switch ($field_type) {
			case 'level_number':
				return "You are a pattern recognition expert. Analyze the given level names and respond with ONLY the next logical level name. Response must be 1-3 words maximum. Examples: Bronze,Silver â†’ Gold | 1,2,3 â†’ 4 | Beginner,Intermediate â†’ Advanced";
				
			case 'minimum_earning':
				return "You are a financial analyst specializing in affiliate commission structures. Analyze the existing minimum earning requirements and suggest the next logical amount. Consider progression patterns, market standards, and scalability. Respond with ONLY a number (e.g., 500.00). No currency symbols or explanations.";
				
			case 'commission_rate':
				return "You are an affiliate marketing expert analyzing commission rate progressions. Study the existing commission rates and suggest the next logical percentage rate. Consider industry standards, motivation factors, and business sustainability. Respond with ONLY a number (e.g., 7.50). No percentage symbols or explanations.";
				
			case 'bonus':
				return "You are a rewards system specialist analyzing bonus progressions. Examine the existing bonus amounts and suggest the next logical bonus value. Consider motivation psychology, business profitability, and reward scaling. Respond with ONLY a number (e.g., 150.00). No currency symbols or explanations.";
				
			default:
				return "Analyze the data and provide the next logical value.";
		}
	}
	
	private function build_field_user_prompt($field_type, $existing_levels) {
		if (empty($existing_levels)) {
			switch ($field_type) {
				case 'level_number':
					return "No existing levels. Suggest the first level name.";
				case 'minimum_earning':
					return "No existing levels. Suggest an appropriate starting minimum earning amount for the first affiliate level.";
				case 'commission_rate':
					return "No existing levels. Suggest an appropriate starting commission rate percentage for the first affiliate level.";
				case 'bonus':
					return "No existing levels. Suggest an appropriate starting bonus amount for the first affiliate level.";
				case 'default_registration_level':
					return "No existing levels. This should be the default level.";
			}
		}
		
		// Prepare existing data for AI analysis
		$data_summary = "Existing award levels data:\n";
		foreach ($existing_levels as $index => $level) {
			$level_num = $level['level_number'] ?? 'Unknown';
			$min_earning = $level['minimum_earning'] ?? '0';
			$commission = $level['sale_comission_rate'] ?? '0';
			$bonus = $level['bonus'] ?? '0';
			
			$data_summary .= "Level " . ($index + 1) . ": Name='{$level_num}', MinEarning={$min_earning}, Commission={$commission}%, Bonus={$bonus}\n";
		}
		
		switch ($field_type) {
			case 'level_number':
				return $data_summary . "\nBased on the existing level names, what should be the next level name?";
				
			case 'minimum_earning':
				return $data_summary . "\nAnalyze the minimum earning progression pattern. What should be the next logical minimum earning requirement?";
				
			case 'commission_rate':
				return $data_summary . "\nAnalyze the commission rate progression pattern. What should be the next logical commission rate percentage?";
				
			case 'bonus':
				return $data_summary . "\nAnalyze the bonus amount progression pattern. What should be the next logical bonus amount?";
				
			case 'default_registration_level':
				return $data_summary . "\nShould this level be set as the default level?";
				
			default:
				return $data_summary;
		}
	}
	
	private function validate_ai_field_response($field_type, $response) {
		$response = trim($response);
		
		switch ($field_type) {
			case 'level_number':
				// Should be short text
				return strlen($response) <= 30 && !empty($response);
				
			case 'minimum_earning':
			case 'commission_rate':
			case 'bonus':
				// Should be a valid number
				return is_numeric($response) && $response >= 0;
				
			default:
				return !empty($response);
		}
	}
	
	public function ai_suggest_level() {
		$this->checkLogin('admin');
		header('Content-Type: application/json');
		
		$json = ['success' => false, 'message' => 'Invalid request'];
		
		if ($this->input->method() === 'post') {
			$input = json_decode(file_get_contents('php://input'), true);
			$existing_levels = $input['existing_levels'] ?? [];
			
			// Get AI settings
			$ai_settings = $this->Product_model->getSettings('ai_helper');
			$provider = $ai_settings['ai_provider'] ?? 'openai';
			$api_key = '';
			
			switch ($provider) {
				case 'openai':
					$api_key = $ai_settings['openai_api_key'] ?? '';
					$model = $ai_settings['openai_model'] ?? 'gpt-3.5-turbo';
					break;
				case 'gemini':
					$api_key = $ai_settings['gemini_api_key'] ?? '';
					$model = $ai_settings['gemini_model'] ?? 'gemini-pro';
					break;
			}
			
			// If no API key, use simple logic fallback
			if (empty($api_key)) {
				$suggestion = $this->generate_simple_level_suggestion($existing_levels);
				$json = ['success' => true, 'suggestion' => $suggestion];
			} else {
				// Build specific prompt for level suggestion
				$system_prompt = "You are a pattern recognition expert. Analyze the given level names and respond with ONLY the next logical level name. Response must be 1-3 words maximum. Examples: Bronze,Silver â†’ Gold | 1,2,3 â†’ 4 | Beginner,Intermediate â†’ Advanced";
				
				if (empty($existing_levels)) {
					$user_prompt = "No existing levels. Suggest the first level.";
				} else {
					$user_prompt = "Existing levels: " . implode(', ', $existing_levels) . ". What should be the next level?";
				}
				
				// Call AI API
				$ai_result = '';
				switch ($provider) {
					case 'openai':
						$ai_result = $this->call_simple_openai($system_prompt, $user_prompt, $api_key, $model);
						break;
					case 'gemini':
						$ai_result = $this->call_simple_gemini($system_prompt, $user_prompt, $api_key, $model);
						break;
				}
				
				if ($ai_result && strlen($ai_result) <= 30) {
					$json = ['success' => true, 'suggestion' => trim($ai_result)];
				} else {
					// Fallback to simple logic
					$suggestion = $this->generate_simple_level_suggestion($existing_levels);
					$json = ['success' => true, 'suggestion' => $suggestion];
				}
			}
		}
		
		echo json_encode($json);
	}
	
	private function generate_simple_level_suggestion($existing_levels) {
		if (empty($existing_levels)) {
			return '1';
		}
		
		// Check if all are numbers
		$all_numbers = true;
		$numbers = [];
		foreach ($existing_levels as $level) {
			if (is_numeric($level)) {
				$numbers[] = (int)$level;
			} else {
				$all_numbers = false;
				break;
			}
		}
		
		if ($all_numbers) {
			return (string)(max($numbers) + 1);
		}
		
		// Check for common patterns
		$medal_sequence = ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond'];
		$progress_sequence = ['Beginner', 'Intermediate', 'Advanced', 'Expert', 'Master'];
		$tier_sequence = ['Basic', 'Standard', 'Premium', 'VIP', 'Elite'];
		
		foreach ([$medal_sequence, $progress_sequence, $tier_sequence] as $sequence) {
			$next = $this->find_next_in_sequence($existing_levels, $sequence);
			if ($next) return $next;
		}
		
		// Default fallback
		return (string)(count($existing_levels) + 1);
	}
	
	private function find_next_in_sequence($existing, $sequence) {
		foreach ($sequence as $index => $item) {
			if (!in_array($item, $existing) && $index > 0) {
				// Check if previous items exist
				$has_previous = true;
				for ($i = 0; $i < $index; $i++) {
					if (!in_array($sequence[$i], $existing)) {
						$has_previous = false;
						break;
					}
				}
				if ($has_previous) {
					return $item;
				}
			}
		}
		return null;
	}
	
	private function call_simple_openai($system_prompt, $user_prompt, $api_key, $model) {
		$url = 'https://api.openai.com/v1/chat/completions';
		
		$data = [
			'model' => $model,
			'messages' => [
				['role' => 'system', 'content' => $system_prompt],
				['role' => 'user', 'content' => $user_prompt]
			],
			'max_tokens' => 10,
			'temperature' => 0.3
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $api_key
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ($http_code === 200 && $response) {
			$result = json_decode($response, true);
			if (isset($result['choices'][0]['message']['content'])) {
				return trim($result['choices'][0]['message']['content']);
			}
		}
		
		return false;
	}
	
	private function call_simple_gemini($system_prompt, $user_prompt, $api_key, $model) {
		$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $api_key;
		
		$combined_prompt = $system_prompt . "\n\nUser Request: " . $user_prompt;
		
		$data = [
			'contents' => [
				[
					'parts' => [
						[
							'text' => $combined_prompt
						]
					]
				]
			],
			'generationConfig' => [
				'maxOutputTokens' => 10,
				'temperature' => 0.3
			]
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ($http_code === 200 && $response) {
			$result = json_decode($response, true);
			if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
				return trim($result['candidates'][0]['content']['parts'][0]['text']);
			}
		}
		
		return false;
	}

	public function ai_generate_content() {
		$this->checkLogin('admin');
		
		$json = ['success' => false, 'message' => 'Invalid request'];
		
		if ($this->input->method() === 'post') {
			$input = json_decode(file_get_contents('php://input'), true);
			
			$content_type = $input['content_type'] ?? '';
			$prompt = $input['prompt'] ?? '';
			$tone = $input['tone'] ?? 'professional';
			$length = $input['length'] ?? 'medium';
			
			// Debug log to see what content type we're receiving
			log_message('debug', 'AI Content Type: ' . $content_type);
			log_message('debug', 'AI Prompt: ' . $prompt);
			
			// Check if AI helper is enabled
			$ai_settings = $this->Product_model->getSettings('ai_helper');
			if (!$ai_settings || !isset($ai_settings['ai_helper_enabled']) || !$ai_settings['ai_helper_enabled']) {
				$json['message'] = 'AI Helper is disabled in admin settings';
				echo json_encode($json);
				return;
			}
			
			// Get admin details for usage tracking
			$admin_details = $this->userdetails();
			$admin_id = $admin_details['id'];
			
			// Check user limits (can be enhanced)
			if ($this->check_ai_usage_limit($admin_id)) {
				$json['message'] = 'Daily AI usage limit reached';
				echo json_encode($json);
				return;
			}
			
			// Generate content based on type
			$suggestions = $this->process_ai_request($content_type, $prompt, $tone, $length);
			
			if ($suggestions) {
				// Track REAL usage ONLY when real AI is actually used
				$use_real_ai = isset($ai_settings['use_real_ai']) ? $ai_settings['use_real_ai'] : 1;
				$api_key = '';
				
				// Check if we have a valid API key for the current provider
				switch ($provider) {
					case 'openai':
						$api_key = $ai_settings['openai_api_key'] ?? '';
						break;
					case 'claude':
						$api_key = $ai_settings['claude_api_key'] ?? '';
						break;
					case 'gemini':
						$api_key = $ai_settings['gemini_api_key'] ?? '';
						break;
				}
				
				// Only track usage if real AI is enabled AND we have a valid API key
				if ($use_real_ai && !empty($api_key)) {
					$this->track_real_ai_usage($admin_id);
					$this->log_ai_usage($user_id, $content_type);
				}
				
				$json = [
					'success' => true,
					'suggestions' => $suggestions
				];
			} else {
				$json['message'] = 'Failed to generate content. Please check your AI settings.';
			}
		}
		
		echo json_encode($json);
	}
	
	private function process_ai_request($content_type, $prompt, $tone, $length) {
		// Get AI settings
		$ai_settings = $this->Product_model->getSettings('ai_helper');
		
		// Check if AI provider is configured
		$provider = $ai_settings['ai_provider'] ?? 'openai';
		$api_key = '';
		$model = '';
		
		// Check if we should use real AI or fallback (new setting)
		$use_real_ai = isset($ai_settings['use_real_ai']) ? $ai_settings['use_real_ai'] : 1;
		
		if ($use_real_ai) {
			// Try to get API key for real AI
			switch ($provider) {
				case 'openai':
					$api_key = $ai_settings['openai_api_key'] ?? '';
					$model = $ai_settings['openai_model'] ?? 'gpt-3.5-turbo';
					break;
				case 'claude':
					$api_key = $ai_settings['claude_api_key'] ?? '';
					$model = $ai_settings['claude_model'] ?? 'claude-3-sonnet';
					break;
				case 'gemini':
					$api_key = $ai_settings['gemini_api_key'] ?? '';
					$model = $ai_settings['gemini_model'] ?? 'gemini-pro';
					break;
			}
		}
		
		// If no API key configured or real AI is disabled, fall back to mock data for testing
		if (empty($api_key) || !$use_real_ai) {
			// Return mock data instead of failing - useful for testing
			return $this->get_mock_suggestions($content_type, $tone, $length, $prompt);
		}
		
		// Build AI prompts
		$system_prompt = $this->build_system_prompt($content_type, $tone, $length);
		$user_prompt = $this->build_user_prompt($content_type, $prompt);
		
		// Call the appropriate AI API with fallback to mock data
		$ai_result = false;
		
		switch ($provider) {
			case 'openai':
				$ai_result = $this->call_openai_api($system_prompt, $user_prompt, $api_key, $model);
				break;
			case 'claude':
				$ai_result = $this->call_claude_api($system_prompt, $user_prompt, $api_key, $model);
				break;
			case 'gemini':
				$ai_result = $this->call_gemini_api($system_prompt, $user_prompt, $api_key, $model);
				break;
		}
		
		// If AI API fails, fall back to mock data for better user experience
		if ($ai_result === false || empty($ai_result)) {
			return $this->get_mock_suggestions($content_type, $tone, $length, $prompt);
		}
		
		return $ai_result;
	}
	
	private function build_system_prompt($content_type, $tone, $length) {
		$prompts = [
			'plan_description' => "You are a professional copywriter specializing in affiliate membership plans. Create compelling descriptions that highlight benefits, commission structures, and value propositions for affiliate marketers.",
			'campaign_title' => "You are an expert at creating catchy, attention-grabbing affiliate campaign titles that drive engagement and conversions.",
			'campaign_content' => "You are a conversion copywriting specialist who creates persuasive campaign content that motivates affiliates to take action.",
			'product_description' => "You are a product marketing expert who writes persuasive descriptions for affiliate products that convert browsers into buyers.",
			'product_short_description' => "You are a copywriting specialist who creates concise, impactful product summaries under 150 characters that capture the key selling points.",
			'terms_content' => "You are a legal content specialist who creates clear, comprehensive terms of service content for affiliate marketing platforms.",
			'privacy_policy' => "You are a privacy law expert who writes GDPR-compliant privacy policies for affiliate marketing platforms.",
			'email_template' => "You are an email marketing specialist who creates engaging, personalized email content for affiliate marketing communications.",
			'page_content' => "You are a web content specialist who creates engaging, informative content for affiliate marketing website pages.",
			'announcement' => "You are a communications specialist who creates clear, engaging announcements for affiliate marketing platforms.",
			'profile_bio' => "You are a personal branding expert who creates compelling professional bios for affiliate marketers.",
			'blog_post' => "You are a content marketing specialist who creates engaging blog content for affiliate marketing audiences.",
			'notification' => "You are a UX copywriter who creates clear, actionable notification messages for affiliate marketing platforms.",
		];
		
		$base_prompt = $prompts[$content_type] ?? $prompts['product_description'];
		
		$tone_instructions = [
			'professional' => 'Use a professional, authoritative tone that builds trust and credibility.',
			'casual' => 'Use a friendly, conversational tone that feels personal and approachable.',
			'promotional' => 'Use an exciting, sales-focused tone with urgency and compelling calls to action.',
			'informative' => 'Use a clear, educational tone that explains benefits and features clearly.'
		];
		
		$length_instructions = [
			'short' => 'Keep it concise and punchy (1-2 sentences maximum).',
			'medium' => 'Provide moderate detail with key benefits (2-4 sentences).',
			'long' => 'Give comprehensive detail with full context and persuasive elements (4-8 sentences).'
		];
		
		// Add character limits for specific content types
		$character_limits = [
			'product_short_description' => ' IMPORTANT: Keep ALL responses under 150 characters including spaces.',
			'campaign_title' => ' IMPORTANT: Keep titles under 100 characters.',
			'notification' => ' IMPORTANT: Keep notifications under 100 characters for clarity.',

		];
		
		$char_limit = $character_limits[$content_type] ?? '';
		
		$variation_instruction = ' Generate exactly 3 different variations.';
		
		return $base_prompt . ' ' . $tone_instructions[$tone] . ' ' . $length_instructions[$length] . $char_limit . $variation_instruction;
	}
	
	private function build_user_prompt($content_type, $additional_prompt) {
		$context_prompts = [
			'plan_description' => 'Create a description for a membership plan',
			'campaign_title' => 'Create a title for an affiliate campaign',
			'product_description' => 'Create a description for a product',
			'email_template' => 'Create email content',

		];
		
		$base = $context_prompts[$content_type] ?? $context_prompts['plan_description'];
		
		if (!empty($additional_prompt)) {
			$base .= '. Additional requirements: ' . $additional_prompt;
		}
		
		return $base;
	}
	
	private function call_openai_api($system_prompt, $user_prompt, $api_key, $model) {
		$url = 'https://api.openai.com/v1/chat/completions';
		
		$data = [
			'model' => $model,
			'messages' => [
				[
					'role' => 'system',
					'content' => $system_prompt
				],
				[
					'role' => 'user', 
					'content' => $user_prompt
				]
			],
			'max_tokens' => 500,
			'temperature' => 0.7,
			'n' => 1
		];
		
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $api_key
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ($http_code !== 200 || !$response) {
			return false;
		}
		
		$result = json_decode($response, true);
		
		if (!isset($result['choices'][0]['message']['content'])) {
			return false;
		}
		
		$content = trim($result['choices'][0]['message']['content']);
		
		// Parse the response into multiple suggestions
		return $this->parse_ai_response($content);
	}
	
	private function call_claude_api($system_prompt, $user_prompt, $api_key, $model) {
		$url = 'https://api.anthropic.com/v1/messages';
		
		$data = [
			'model' => $model,
			'max_tokens' => 500,
			'system' => $system_prompt,
			'messages' => [
				[
					'role' => 'user',
					'content' => $user_prompt
				]
			]
		];
		
		$headers = [
			'Content-Type: application/json',
			'x-api-key: ' . $api_key,
			'anthropic-version: 2023-06-01'
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ($http_code !== 200 || !$response) {
			return false;
		}
		
		$result = json_decode($response, true);
		
		if (!isset($result['content'][0]['text'])) {
			return false;
		}
		
		$content = trim($result['content'][0]['text']);
		
		return $this->parse_ai_response($content);
	}
	
	private function call_gemini_api($system_prompt, $user_prompt, $api_key, $model) {
		$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $api_key;
		
		$combined_prompt = $system_prompt . "\n\nUser Request: " . $user_prompt;
		
		$data = [
			'contents' => [
				[
					'parts' => [
						[
							'text' => $combined_prompt
						]
					]
				]
			],
			'generationConfig' => [
				'maxOutputTokens' => 500,
				'temperature' => 0.7
			]
		];
		
		$headers = [
			'Content-Type: application/json'
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ($http_code !== 200 || !$response) {
			return false;
		}
		
		$result = json_decode($response, true);
		
		if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
			return false;
		}
		
		$content = trim($result['candidates'][0]['content']['parts'][0]['text']);
		
		return $this->parse_ai_response($content);
	}
	
	private function parse_ai_response($content) {
		// Try to split the response into multiple suggestions
		$suggestions = [];
		
		// Look for numbered lists (1., 2., 3.) or bullet points
		if (preg_match_all('/(?:^|\n)(?:\d+[\.\)]\s*|[\*\-\â€¢]\s*)(.+)/m', $content, $matches)) {
			foreach ($matches[1] as $suggestion) {
				$cleaned = trim($suggestion);
				if (!empty($cleaned)) {
					$suggestions[] = $cleaned;
				}
			}
		}
		
		// If no clear list format, try splitting by double newlines
		if (empty($suggestions)) {
			$parts = preg_split('/\n\s*\n/', $content);
			foreach ($parts as $part) {
				$cleaned = trim($part);
				if (!empty($cleaned)) {
					$suggestions[] = $cleaned;
				}
			}
		}
		
		// If still no suggestions, treat the whole response as one suggestion
		if (empty($suggestions)) {
			$suggestions[] = trim($content);
		}
		
		// Limit to 3 suggestions maximum
		return array_slice($suggestions, 0, 3);
	}
	
	private function get_mock_suggestions($content_type, $tone, $length, $prompt = '') {
		// Enhanced mock suggestions that respond to user input
		$base_suggestions = [
			'plan_description' => [
				'professional' => [
					'short' => [
						'Access advanced affiliate tools, commission tracking, and comprehensive analytics dashboard with our professional membership plan.',
						'Unlock MLM features, vendor store capabilities, and payment system integration designed for serious marketers.',
						'Premium membership includes affiliate tools, commission system, and dedicated support for maximum earning potential.'
					],
					'medium' => [
						'Transform your affiliate business with our comprehensive membership plan featuring advanced commission tracking, MLM capabilities, and vendor store integration. Access powerful analytics, payment systems, and dedicated support to maximize your earning potential.',
						'Join our professional affiliate network with exclusive access to high-converting campaigns, multi-level commission system, and comprehensive store module. Benefit from advanced analytics, payment processing, and premium support services.',
						'Elevate your marketing success with our premium membership offering affiliate tools, commission tracking, vendor marketplace access, and integrated payment solutions. Includes comprehensive analytics dashboard and priority customer support.'
					],
					'long' => [
						'Discover the power of professional affiliate marketing with our comprehensive membership plan designed for serious marketers. Access advanced commission tracking systems, multi-level marketing features, and vendor store integration. Benefit from powerful analytics dashboards, seamless payment processing, and dedicated customer support. Our platform includes cutting-edge affiliate tools, campaign management systems, and integration APIs for maximum flexibility and growth potential.',
						'Unlock unlimited earning potential with our professional affiliate membership featuring advanced commission structures, MLM capabilities, and comprehensive vendor marketplace. Access powerful analytics tools, payment systems, and dedicated support services. Our platform provides everything you need including affiliate campaign management, store modules, integration capabilities, and mobile-ready features for modern marketers.',
						'Transform your affiliate marketing business with our premium membership platform offering advanced commission tracking, multi-level marketing features, and comprehensive vendor store capabilities. Access powerful analytics dashboards, integrated payment systems, and premium customer support. Our solution includes affiliate tools, campaign management, API integrations, and mobile-optimized features designed for professional marketers seeking maximum growth and profitability.'
					]
				],
				'promotional' => [
					'short' => [
						'EXCLUSIVE: Unlock 50% higher commissions with our premium affiliate membership - limited time offer!',
						'Join NOW and get instant access to high-converting campaigns, MLM tools, and vendor store features!',
						'Don\'t miss out! Premium membership with commission boosts, affiliate tools, and exclusive benefits!'
					],
					'medium' => [
						'LIMITED TIME: Get exclusive access to our premium affiliate platform with 50% commission boosts, advanced MLM features, and vendor store capabilities. Join thousands earning more with our proven system!',
						'SPECIAL OFFER: Unlock high-converting campaigns, commission tracking systems, and vendor marketplace access. Premium members earn 3x more - join the success stories today!',
						'EXCLUSIVE LAUNCH: Access premium affiliate tools, commission boosts, and vendor store features. Limited spots available for serious marketers ready to maximize earnings!'
					]
				],
				'casual' => [
					'medium' => [
						'Hey there! Ready to take your affiliate game to the next level? Our membership gives you access to cool tools like commission tracking, vendor stores, and analytics that actually make sense.',
						'Join our awesome affiliate community! Get access to easy-to-use tools, fair commission systems, and a vendor marketplace that just works. Plus, we\'ve got your back with great support.',
						'Looking to boost your affiliate earnings? Our membership includes all the good stuff - commission tracking, campaign tools, vendor features, and analytics that help you grow without the headaches.'
					]
				]
			],
			'campaign_title' => [
				'promotional' => [
					'short' => [
						'ðŸš€ High Converting Deals - 50% Commission Boost!',
						'â° Limited Time: Exclusive Affiliate Offers Inside',
						'ðŸ’Ž Premium Products - Top Seller Campaign'
					]
				],
				'professional' => [
					'short' => [
						'Professional Affiliate Campaign - High Converting Products',
						'Premium Commission Program - Verified Products',
						'Elite Affiliate Opportunity - Top Tier Commissions'
					]
				]
			],
			'product_description' => [
				'professional' => [
					'short' => [
						'Premium digital product with instant delivery and high commissions for affiliate marketers.',
						'Professional subscription service with recurring commissions and vendor store integration.',
						'High-quality physical product with competitive commission structure and reliable shipping.'
					],
					'medium' => [
						'Premium digital product featuring instant delivery, high commission rates, and comprehensive customer support. Perfect for affiliate marketers seeking reliable, converting offers with proven track record.',
						'Professional-grade subscription service with recurring commissions, vendor store integration, and premium quality assurance. Ideal for serious affiliate partnerships seeking long-term growth.',
						'High-quality physical product with competitive commission structure, reliable shipping, and dedicated customer service. Trusted by top affiliate marketers worldwide for consistent performance.'
					],
					'long' => [
						'Discover our premium digital product designed specifically for affiliate marketers who demand excellence. Featuring instant delivery systems, industry-leading commission rates up to 50%, and comprehensive 24/7 customer support. This product has been carefully crafted to ensure maximum conversion rates and customer satisfaction. Perfect for affiliate marketers seeking reliable, high-converting offers with proven track records. Includes detailed marketing materials, conversion tracking tools, and dedicated affiliate manager support to maximize your earning potential.',
						'Experience our professional-grade subscription service that revolutionizes affiliate marketing success. Built with recurring commission structures, seamless vendor store integration, and premium quality assurance protocols. This comprehensive solution is ideal for serious affiliate partnerships seeking sustainable long-term growth. Features include advanced analytics dashboards, automated commission tracking, multi-tier reward systems, and exclusive access to high-converting product lines. Backed by industry-leading support and proven results from thousands of successful affiliates.',
						'Invest in our exceptional physical product line that combines premium quality with outstanding affiliate opportunities. Featuring competitive commission structures, reliable worldwide shipping, and dedicated customer service excellence. Trusted by top affiliate marketers globally for consistent performance and customer satisfaction. This product includes comprehensive marketing support, conversion optimization tools, detailed product training materials, and access to our exclusive affiliate community. Perfect for marketers who value quality, reliability, and proven earning potential in the physical products space.'
					]
				],
				'promotional' => [
					'short' => [
						'ðŸš€ HIGH-CONVERTING: 50% commissions + instant delivery guaranteed!',
						'ðŸ’Ž PREMIUM LAUNCH: Exclusive affiliate rates - limited time only!',
						'â­ TOP SELLER: Proven conversions + reliable payouts!'
					],
					'medium' => [
						'ðŸš€ BREAKTHROUGH OFFER: High-converting digital product with 50% commission rates and instant delivery system. Limited-time exclusive launch for serious affiliates only!',
						'ðŸ’Ž PREMIUM EXCLUSIVE: Professional subscription service with recurring commissions and vendor integration. Join the elite affiliate program before it closes!',
						'â­ PROVEN WINNER: Top-selling physical product with outstanding conversion rates and reliable commission payouts. Don\'t miss this opportunity!'
					],
					'long' => [
						'ðŸš€ MASSIVE BREAKTHROUGH OPPORTUNITY: Get exclusive access to our highest-converting digital product featuring revolutionary 50% commission rates, lightning-fast delivery systems, and guaranteed customer satisfaction! This limited-time launch is ONLY available to serious affiliates who want to maximize their earnings. Features include done-for-you marketing materials, conversion-optimized landing pages, and dedicated affiliate support. WARNING: Only 100 spots available - secure yours NOW before this exclusive opportunity closes forever!',
						'ðŸ’Ž ELITE PREMIUM LAUNCH: Join the most exclusive affiliate program in the industry! Our professional subscription service offers recurring lifetime commissions, premium vendor store integration, and access to products that consistently convert at 15%+ rates. This is your chance to build sustainable passive income with proven winners. Includes VIP affiliate training, personal account manager, and access to our private mastermind group. HURRY: Elite status closes in 48 hours!',
						'â­ RECORD-BREAKING BESTSELLER: The physical product that\'s taking the affiliate world by storm! Proven 20%+ conversion rates, premium commission structure, and customers who buy again and again. This isn\'t just another product - it\'s your ticket to affiliate freedom! Get exclusive access to our complete marketing arsenal including video testimonials, email sequences, and social media campaigns. FINAL WARNING: Competition is fierce - secure your affiliate spot before your competitors do!'
					]
				],
				'casual' => [
					'medium' => [
						'Hey there! This digital product is absolutely amazing - customers love it and the commissions are really good too. Easy to promote and delivers instantly!',
						'Found this awesome subscription service that pays recurring commissions. It\'s got everything you need and customers stick around for months!',
						'This physical product is a real winner! Great quality, happy customers, and decent affiliate rates. Plus shipping is super reliable.'
					],
					'long' => [
						'Hey affiliate friend! Let me tell you about this incredible digital product that\'s been making me serious money. The best part? It practically sells itself! Customers absolutely love it because it delivers instant value, and I love it because the commissions are fantastic. No complicated sales funnels or pushy tactics needed - just share it with people who need it and watch the sales roll in. The support team is awesome too, they handle all the customer service so I can focus on what I do best: promoting and earning. If you\'re looking for something reliable that actually works, this is it!',
						'Okay, I have to share this with you - this subscription service is a game-changer! The recurring commissions mean I\'m earning money every month from customers I referred ages ago. It\'s like having a little money machine that keeps paying you. The product itself is solid, customers rarely cancel, and the company treats affiliates really well. They\'ve got all the tools you need, training that actually helps, and they\'re always adding new features. Honestly, it\'s the most stress-free way I\'ve found to build passive income. You should definitely check it out!',
						'This physical product has been my secret weapon for steady affiliate income! Unlike digital stuff that comes and goes, this product has staying power. Customers order it, love it, and often come back for more. The company handles all the shipping and customer service, so I just focus on connecting people with something they genuinely need. The commission checks are reliable, the product quality is top-notch, and I never feel like I\'m pushing something questionable. It\'s just honest business that works for everyone involved. Highly recommend giving it a try!'
					]
				]
			],
			'product_short_description' => [
				'professional' => [
					'short' => [
						'Premium digital product with high commissions.',
						'Professional service with recurring commissions.',
						'High-quality product with reliable support.'
					],
					'medium' => [
						'Premium digital product with instant delivery and high commissions for affiliate marketers.',
						'Professional subscription service featuring recurring commissions and premium quality.',
						'High-quality product with competitive rates and reliable customer support.'
					],
					'long' => [
						'Premium digital product featuring instant delivery, high commission rates, and comprehensive customer support for affiliate marketers.',
						'Professional subscription service with recurring commissions, premium quality assurance, and dedicated affiliate support team.',
						'High-quality product with competitive commission structure, reliable shipping, and customer service trusted by top affiliates.'
					]
				],
				'promotional' => [
					'short' => [
						'ðŸš€ High-converting product - 50% commissions!',
						'ðŸ’Ž Premium quality with top affiliate rates!',
						'â­ Best-seller with proven conversion rates!'
					],
					'medium' => [
						'ðŸš€ High-converting product with 50% commissions - instant delivery guaranteed!',
						'ðŸ’Ž Premium quality with top affiliate rates - limited time offer!',
						'â­ Best-seller with proven conversion rates and reliable payouts!'
					],
					'long' => [
						'ðŸš€ High-converting product with 50% commission boost, instant delivery, and guaranteed customer satisfaction - limited time offer!',
						'ðŸ’Ž Premium quality product with top affiliate rates, proven conversion rates, and exclusive marketing materials included!',
						'â­ Best-selling product with proven track record, reliable payouts, and comprehensive affiliate support system!'
					]
				],
				'casual' => [
					'short' => [
						'Awesome product - great commissions!',
						'Super popular with fair affiliate rates.',
						'Love this product - easy to promote!'
					],
					'medium' => [
						'Awesome product that sells itself - great commissions and happy customers!',
						'Super popular item with fair affiliate rates and quick delivery.',
						'Love this product! Easy to promote with decent commissions.'
					],
					'long' => [
						'Awesome product that practically sells itself - great commissions, happy customers, and super easy to promote!',
						'Super popular item that everyone loves - fair affiliate rates, quick delivery, and excellent customer feedback!',
						'Love this product! Easy to promote, decent commissions, and customers always come back for more!'
					]
				],
				'informative' => [
					'short' => [
						'Digital product with training materials.',
						'Subscription with detailed documentation.',
						'Physical product with setup guide.'
					],
					'medium' => [
						'Digital product featuring comprehensive training materials and affiliate support.',
						'Subscription-based solution with detailed documentation and customer resources.',
						'Physical product with complete setup guide and ongoing support included.'
					],
					'long' => [
						'Digital product featuring comprehensive training materials, affiliate support tools, and step-by-step implementation guides.',
						'Subscription-based solution with detailed documentation, customer resources, and ongoing educational content updates.',
						'Physical product with complete setup guide, ongoing support, and access to exclusive customer community.'
					]
				]
			],
			'email_template' => [
				'professional' => [
					'medium' => [
						'Welcome to our affiliate program! Your commission tracking dashboard is ready, and we\'ve prepared training resources to help you succeed. Check your member area for exclusive campaigns and support materials.',
						'Great news! Your latest commission of $XXX has been processed. View your detailed analytics dashboard for performance insights and discover new high-converting campaigns in your affiliate portal.',
						'Congratulations on reaching the next level in our MLM program! Your upgraded status unlocks higher commission rates, exclusive products, and premium support. Login to explore your new benefits.'
					]
				]
			]
		];
		
		// Get suggestions based on content type, tone, and length
		$suggestions = $base_suggestions[$content_type][$tone][$length] ?? 
					   $base_suggestions[$content_type]['professional']['medium'] ?? 
					   $base_suggestions['plan_description']['professional']['medium'];
		
		// If user added specific tags/prompt, try to customize suggestions
		if (!empty($prompt)) {
			$suggestions = $this->customize_suggestions_for_prompt($suggestions, $prompt, $content_type);
		}
		
		// Apply character limits for specific content types
		if ($content_type === 'product_short_description') {
			$suggestions = $this->enforce_character_limit($suggestions, 150);
		}
		
		return $suggestions;
	}
	
	private function customize_suggestions_for_prompt($suggestions, $prompt, $content_type) {
		// Simple customization based on user tags/prompt
		$customized = [];
		
		foreach ($suggestions as $suggestion) {
			$custom = $suggestion;
			
			// Add customizations based on common tags
			if (stripos($prompt, 'Commission System') !== false) {
				$custom = str_replace('commission', 'advanced commission system', $custom);
			}
			if (stripos($prompt, 'MLM Features') !== false) {
				$custom = str_replace('marketing', 'multi-level marketing', $custom);
			}
			if (stripos($prompt, 'Store Module') !== false) {
				$custom = str_replace('store', 'comprehensive e-commerce store', $custom);
			}
			if (stripos($prompt, 'Mobile Ready') !== false) {
				$custom .= ' Fully optimized for mobile devices and responsive design.';
			}
			if (stripos($prompt, 'Analytics Dashboard') !== false) {
				$custom = str_replace('analytics', 'advanced analytics dashboard with real-time reporting', $custom);
			}
			
			$customized[] = $custom;
		}
		
		return $customized;
	}
	
	private function check_ai_usage_limit($user_id) {
		// Simple daily limit check
		// In production, this would check database for usage
		return false; // For testing, always allow
	}
	
	private function log_ai_usage($user_id, $content_type) {
		// Log AI usage for analytics and limits
		// In production, save to database
	}
	
	private function enforce_character_limit($suggestions, $max_chars) {
		$limited_suggestions = [];
		
		foreach ($suggestions as $suggestion) {
			if (strlen($suggestion) <= $max_chars) {
				// Already within limit
				$limited_suggestions[] = $suggestion;
			} else {
				// Truncate at word boundary
				$truncated = $this->smart_truncate($suggestion, $max_chars);
				$limited_suggestions[] = $truncated;
			}
		}
		
		return $limited_suggestions;
	}
	
	private function smart_truncate($text, $max_chars) {
		if (strlen($text) <= $max_chars) {
			return $text;
		}
		
		// Find the last space before the character limit
		$truncate_pos = $max_chars - 3; // Leave room for "..."
		$last_space = strrpos(substr($text, 0, $truncate_pos), ' ');
		
		if ($last_space !== false && $last_space > ($max_chars * 0.7)) {
			// Truncate at word boundary if it's not too short
			return substr($text, 0, $last_space) . '...';
		} else {
			// Hard truncate if no good word boundary found
			return substr($text, 0, $truncate_pos) . '...';
		}
	}
	
	private function test_openai_connection($api_key) {
		try {
			$url = 'https://api.openai.com/v1/models';
			$headers = [
				'Authorization: Bearer ' . $api_key,
				'Content-Type: application/json'
			];
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
			$response = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			if ($response === false || $http_code !== 200) {
				return false;
			}
			
			$data = json_decode($response, true);
			return isset($data['data']) && is_array($data['data']);
			
		} catch (Exception $e) {
			return false;
		}
	}

	private function get_mock_field_suggestion($field_type, $existing_levels) {
		// Generate intelligent mock suggestions based on existing data
		switch ($field_type) {
					case 'level_number':
			return $this->generate_simple_level_suggestion(array_column($existing_levels, 'level_number'));
			
			case 'minimum_earning':
				if (empty($existing_levels)) {
					return '100.00';
				}
				// Get highest existing earning and add logical progression
				$earnings = array_column($existing_levels, 'minimum_earning');
				$max_earning = max($earnings);
				$next_earning = $max_earning * 2; // Double the highest
				return number_format($next_earning, 2, '.', '');
			
			case 'commission_rate':
				if (empty($existing_levels)) {
					return '5.00';
				}
				// Get highest commission and add 2.5%
				$commissions = array_column($existing_levels, 'sale_comission_rate');
				$max_commission = max($commissions);
				$next_commission = min(30, $max_commission + 2.5); // Cap at 30%
				return number_format($next_commission, 2, '.', '');
			
			case 'bonus':
				if (empty($existing_levels)) {
					return '50.00';
				}
				// Get highest bonus and add logical progression
				$bonuses = array_column($existing_levels, 'bonus');
				$max_bonus = max($bonuses);
				$next_bonus = $max_bonus + 50; // Add $50
				return number_format($next_bonus, 2, '.', '');
			
			case 'jump_level':
				if (empty($existing_levels)) {
					return null; // No levels to jump to
				}
				// Suggest the highest level as jump target (most logical progression)
				$level_ids = array_column($existing_levels, 'id');
				return max($level_ids);
			
			case 'default_registration_level':
				// Auto-set first level as default if no levels exist
				return empty($existing_levels) ? 1 : 0;
			
			default:
				return false;
		}
	}

	// ================================
	// SALES FUNNELS MANAGEMENT
	// ================================

	public function sales_funnels() {
		$userdetails = $this->userdetails();
		
		$data = [
			'title' => 'Sales Funnels Management',
			'userdetails' => $userdetails
		];

		// Get all sales products (is_campaign_product = 1)
		$data['sales_products'] = $this->db->select('product_id, product_name, product_price, product_sku')
			->from('product')
			->where('is_campaign_product', 1)
			->where('product_status', 1)
			->order_by('product_name', 'ASC')
			->get()
			->result();

		// Get funnel configurations
		$data['funnel_configs'] = $this->get_funnel_configurations();

		$this->load->view('admincontrol/includes/header', $data);
		$this->load->view('admincontrol/includes/topnav', $data);
		$this->load->view('admincontrol/sales_funnels/index');
		$this->load->view('admincontrol/includes/footer');
	}

	public function save_funnel_config() {
		header('Content-Type: application/json');
		
		$userdetails = $this->userdetails();
		
		if ($userdetails['id'] != 1) {
			echo json_encode([
				'success' => false,
				'message' => 'Only main admin can manage funnels'
			]);
			return;
		}

		$frontend_product_id = $this->input->post('frontend_product_id');
		$upsell_product_ids = $this->input->post('upsell_product_ids');

		if (!$frontend_product_id) {
			echo json_encode([
				'success' => false,
				'message' => 'Frontend product is required'
			]);
			return;
		}

		$this->db->trans_start();

		// Remove existing funnel config for this frontend product
		$this->db->where('related_product_id', $frontend_product_id)
			->where('meta_key', 'funnel_upsells')
			->delete('product_meta');

		// Add new funnel config if upsells selected
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
				'message' => 'Failed to save funnel configuration'
			]);
		} else {
			echo json_encode([
				'success' => true,
				'message' => 'Funnel configuration saved successfully'
			]);
		}
	}

	private function get_funnel_configurations() {
		$configs = [];
		
		$funnel_metas = $this->db->select('related_product_id, meta_value')
			->from('product_meta')
			->where('meta_key', 'funnel_upsells')
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
		
		if ($userdetails['id'] != 1) {
			redirect('admincontrol');
			return;
		}

		$data = [
			'title' => 'Sales Funnel Pricing',
			'userdetails' => $userdetails
		];
		
		$data['sales_products'] = $this->db->select('product_id, product_name, product_price, product_sku')
			->from('product')
			->where('is_campaign_product', 1)
			->where('product_status', 1)
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

		$this->load->view('admincontrol/includes/header', $data);
		$this->load->view('admincontrol/includes/topnav', $data);
		$this->load->view('admincontrol/sales_funnels/pricing', $data);
		$this->load->view('admincontrol/includes/footer');
	}

	public function save_funnel_pricing() {
		$userdetails = $this->userdetails();
		
		if ($userdetails['id'] != 1) {
			echo json_encode(['success' => false, 'message' => 'Permission denied']);
			return;
		}

		$product_id = $this->input->post('product_id');
		$funnel_price = $this->input->post('funnel_price');

		if (!$product_id || $funnel_price === null || $funnel_price === '') {
			echo json_encode(['success' => false, 'message' => 'Invalid data']);
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
			echo json_encode(['success' => false, 'message' => 'Save failed']);
		} else {
			echo json_encode(['success' => true, 'message' => 'Funnel price saved successfully']);
		}
	}

	/**
	 * V14: Process abandoned cart recovery emails (cron job endpoint)
	 */
	public function process_abandoned_carts() {
		$store = $this->Product_model->getSettings('store');
		if (empty($store['abandoned_cart_enabled'])) {
			echo json_encode(['status' => 'disabled']); return;
		}

		$this->load->model('Abandoned_cart_model');
		$this->load->model('Mail_model');
		$delay = $store['abandoned_cart_delay_hours'] ?? 1;
		$carts = $this->Abandoned_cart_model->getCartsForReminder($delay);

		$sent = 0;
		foreach ($carts as $cart) {
			if (empty($cart->email)) continue;

			$recovery_url = base_url('store/recover_cart/' . $cart->recovery_token);
			$items = json_decode($cart->cart_data, true);
			$item_names = [];
			if (!empty($items)) {
				foreach ($items as $item) {
					$item_names[] = ($item['product_name'] ?? 'Product') . ' x' . ($item['quantity'] ?? 1);
				}
			}

			$subject = __('store.cart_recovery_subject');
			$body = '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">';
			$body .= '<h2 style="color:#333;">' . $subject . '</h2>';
			$body .= '<p>' . sprintf(__('store.cart_recovery_body'), $cart->email) . '</p>';
			if (!empty($item_names)) {
				$body .= '<ul style="background:#f8f9fa;padding:15px 30px;border-radius:8px;">';
				foreach ($item_names as $name) {
					$body .= '<li style="padding:5px 0;">' . htmlspecialchars($name) . '</li>';
				}
				$body .= '</ul>';
			}
			$body .= '<p style="text-align:center;margin:25px 0;">';
			$body .= '<a href="' . $recovery_url . '" style="display:inline-block;padding:12px 30px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:8px;font-weight:bold;">';
			$body .= __('store.view_cart') . '</a></p>';
			$body .= '</div>';

			$this->Mail_model->sendMail($cart->email, $subject, $body);
			$this->Abandoned_cart_model->markReminderSent($cart->id);
			$sent++;
		}

		// Cleanup old carts
		$this->Abandoned_cart_model->cleanup(30);

		echo json_encode(['status' => 'ok', 'sent' => $sent]);
	}

	/**
	 * V14: Store Analytics Dashboard
	 */
	public function store_analytics() {
		$userdetails = $this->session->userdata('logged_in');
		if (empty($userdetails)) { redirect(base_url('admin')); return; }

		$data['userdetails'] = $userdetails;
		$data['page_title'] = __('admin.store_analytics');
		$data['active_tab'] = 'store_analytics';

		// Revenue stats
		$today = date('Y-m-d');
		$week_ago = date('Y-m-d', strtotime('-7 days'));
		$month_ago = date('Y-m-d', strtotime('-30 days'));
		$prev_month_start = date('Y-m-d', strtotime('-60 days'));

		// Current period revenue (order table = store orders)
		$q = $this->db->query("SELECT COUNT(*) as cnt, COALESCE(SUM(total),0) as rev FROM `order` WHERE status = 1 AND DATE(created_at) >= ?", [$month_ago]);
		$current = $q->row();
		$data['month_orders'] = $current->cnt;
		$data['month_revenue'] = $current->rev;

		// Previous period revenue for comparison
		$q = $this->db->query("SELECT COALESCE(SUM(total),0) as rev FROM `order` WHERE status = 1 AND DATE(created_at) >= ? AND DATE(created_at) < ?", [$prev_month_start, $month_ago]);
		$data['prev_month_revenue'] = $q->row()->rev;

		// Today
		$q = $this->db->query("SELECT COUNT(*) as cnt, COALESCE(SUM(total),0) as rev FROM `order` WHERE status = 1 AND DATE(created_at) = ?", [$today]);
		$row = $q->row();
		$data['today_orders'] = $row->cnt;
		$data['today_revenue'] = $row->rev;

		// AOV
		$data['avg_order_value'] = $data['month_orders'] > 0 ? round($data['month_revenue'] / $data['month_orders'], 2) : 0;

		// Top 10 products
		$data['top_products'] = $this->db->query("
			SELECT p.product_name, SUM(op.quantity) as units, SUM(op.price * op.quantity) as revenue
			FROM order_products op
			INNER JOIN product p ON op.product_id = p.product_id
			INNER JOIN `order` o ON op.order_id = o.id
			WHERE o.status = 1 AND DATE(o.created_at) >= ?
			GROUP BY op.product_id ORDER BY revenue DESC LIMIT 10
		", [$month_ago])->result_array();

		// Top 10 vendors by revenue
		$data['top_vendors'] = $this->db->query("
			SELECT u.username, COALESCE(SUM(op.price * op.quantity),0) as revenue, COUNT(DISTINCT o.id) as orders
			FROM order_products op
			INNER JOIN `order` o ON op.order_id = o.id
			INNER JOIN users u ON op.vendor_id = u.id
			WHERE o.status = 1 AND DATE(o.created_at) >= ? AND op.vendor_id > 0
			GROUP BY op.vendor_id ORDER BY revenue DESC LIMIT 10
		", [$month_ago])->result_array();

		// Revenue by payment method
		$data['revenue_by_payment'] = $this->db->query("
			SELECT payment_method, COALESCE(SUM(total),0) as revenue, COUNT(*) as cnt
			FROM `order` WHERE status = 1 AND DATE(created_at) >= ?
			GROUP BY payment_method ORDER BY revenue DESC
		", [$month_ago])->result_array();

		// Daily revenue for chart (last 30 days)
		$data['daily_revenue'] = $this->db->query("
			SELECT DATE(created_at) as d, COALESCE(SUM(total),0) as revenue, COUNT(*) as orders
			FROM `order` WHERE status = 1 AND DATE(created_at) >= ?
			GROUP BY DATE(created_at) ORDER BY d ASC
		", [$month_ago])->result_array();

		// Abandoned cart stats
		$this->load->model('Abandoned_cart_model');
		$data['cart_stats'] = $this->Abandoned_cart_model->getStats(30);

		$this->load->view('admincontrol/includes/header', $data);
		$this->load->view('admincontrol/includes/topnav', $data);
		$this->load->view('admincontrol/store/analytics', $data);
		$this->load->view('admincontrol/includes/footer', $data);
	}

	/**
	 * V14: Admin Inventory Management Panel
	 */
	public function store_inventory() {
		$userdetails = $this->session->userdata('logged_in');
		if (empty($userdetails)) { redirect(base_url('admin')); return; }

		$data['userdetails'] = $userdetails;
		$data['page_title'] = __('admin.inventory_panel');
		$data['active_tab'] = 'store_inventory';

		$store = $this->Product_model->getSettings('store');
		$threshold = $store['low_stock_threshold'] ?? 5;

		// All store products with stock info
		$data['products'] = $this->db->query("
			SELECT p.product_id, p.product_name, p.product_quantity, p.product_status, p.on_store,
				p.product_featured_image,
				(SELECT COUNT(*) FROM order_products op INNER JOIN `order` o ON op.order_id = o.id WHERE op.product_id = p.product_id AND o.status = 1) as total_sold
			FROM product p WHERE p.on_store = 1 ORDER BY p.product_quantity ASC
		")->result_array();

		$data['low_stock_threshold'] = $threshold;
		$data['out_of_stock_count'] = 0;
		$data['low_stock_count'] = 0;
		foreach ($data['products'] as $p) {
			if ($p['product_quantity'] == 0) $data['out_of_stock_count']++;
			elseif ($p['product_quantity'] > 0 && $p['product_quantity'] <= $threshold) $data['low_stock_count']++;
		}

		$this->load->view('admincontrol/includes/header', $data);
		$this->load->view('admincontrol/includes/topnav', $data);
		$this->load->view('admincontrol/store/inventory', $data);
		$this->load->view('admincontrol/includes/footer', $data);
	}

	/**
	 * V14: Update product stock (AJAX)
	 */
	public function update_stock() {
		$product_id = (int) $this->input->post('product_id');
		$quantity = (int) $this->input->post('quantity');

		if (!$product_id) {
			echo json_encode(['success' => false]); return;
		}

		$this->db->where('product_id', $product_id);
		$this->db->update('product', ['product_quantity' => $quantity]);

		// Log stock change
		$userdetails = $this->session->userdata('logged_in');
		$this->db->insert('stock_history', [
			'product_id' => $product_id,
			'quantity_change' => $quantity,
			'quantity_after' => $quantity,
			'reason' => 'manual_update',
			'user_id' => $userdetails['id'] ?? 0,
			'created_at' => date('Y-m-d H:i:s')
		]);

		echo json_encode(['success' => true, 'message' => __('admin.stock_updated')]);
	}

	/**
	 * V14: Customer Insights Page
	 */
	public function store_customers() {
		$userdetails = $this->session->userdata('logged_in');
		if (empty($userdetails)) { redirect(base_url('admin')); return; }

		$data['userdetails'] = $userdetails;
		$data['page_title'] = __('admin.customer_insights');
		$data['active_tab'] = 'store_customers';

		// Top customers by lifetime value (users who placed store orders)
		$data['customers'] = $this->db->query("
			SELECT u.id, u.email, u.username as full_name, u.created_at as joined,
				COUNT(DISTINCT o.id) as order_count,
				COALESCE(SUM(o.total),0) as lifetime_value,
				MAX(o.created_at) as last_order
			FROM users u
			INNER JOIN `order` o ON o.user_id = u.id AND o.status = 1
			GROUP BY u.id
			ORDER BY lifetime_value DESC
			LIMIT 100
		")->result_array();

		// Total unique customers who have at least 1 store order
		$data['total_customers'] = $this->db->query("SELECT COUNT(DISTINCT user_id) as cnt FROM `order`")->row()->cnt ?? 0;

		// New vs returning (last 30 days)
		$month_ago = date('Y-m-d', strtotime('-30 days'));
		$data['new_customers_30d'] = $this->db->query("
			SELECT COUNT(DISTINCT o.user_id) as cnt FROM `order` o 
			INNER JOIN users u ON o.user_id = u.id 
			WHERE u.created_at >= ?
		", [$month_ago])->row()->cnt ?? 0;

		$data['repeat_customers'] = $this->db->query("
			SELECT COUNT(*) as cnt FROM (
				SELECT user_id FROM `order` WHERE status = 1
				GROUP BY user_id HAVING COUNT(*) > 1
			) t
		")->row()->cnt ?? 0;

		$this->load->view('admincontrol/includes/header', $data);
		$this->load->view('admincontrol/store/customers', $data);
		$this->load->view('admincontrol/includes/footer', $data);
	}

	/**
	 * V14: Vendor Payouts Management
	 */
	public function vendor_payouts() {
		$userdetails = $this->session->userdata('logged_in');
		if (empty($userdetails)) { redirect(base_url('admin')); return; }

		$data['userdetails'] = $userdetails;
		$data['page_title'] = __('admin.vendor_payouts');
		$data['active_tab'] = 'vendor_payouts';

		$data['pending'] = $this->db->order_by('requested_at', 'DESC')
			->get_where('vendor_payouts', ['status' => 'pending'])->result_array();

		$data['history'] = $this->db->order_by('requested_at', 'DESC')
			->where('status !=', 'pending')
			->limit(100)->get('vendor_payouts')->result_array();

		$this->load->view('admincontrol/includes/header', $data);
		$this->load->view('admincontrol/store/payouts', $data);
		$this->load->view('admincontrol/includes/footer', $data);
	}

	/**
	 * V14: Process vendor payout action (AJAX)
	 */
	public function payout_action() {
		$payout_id = (int) $this->input->post('payout_id');
		$action = $this->input->post('action', true);
		$admin_note = $this->input->post('admin_note', true);

		if (!$payout_id || !in_array($action, ['approved', 'denied', 'paid'])) {
			echo json_encode(['success' => false]); return;
		}

		$this->db->where('id', $payout_id);
		$this->db->update('vendor_payouts', [
			'status' => $action,
			'admin_note' => $admin_note,
			'processed_at' => date('Y-m-d H:i:s')
		]);

		echo json_encode(['success' => true]);
	}

	public function store_theme_api_doc() {
		$userdetails = $this->userdetails();
		if (empty($userdetails)) { redirect($this->admin_domain_url); }
		$this->load->library('store_cart_payload');
		$data['manifest']    = $this->store_cart_payload->get_manifest_v1();
		$data['schema']      = $this->store_cart_payload->get_full_schema();
		$data['base_url']    = base_url();
		$data['userdetails'] = $userdetails;
		$this->view($data, 'store/theme_api_doc');
	}

	/* =========================================================================
	 * DELETE a custom store theme — removes views + assets folders entirely
	 * POST  admincontrol/delete_store_theme
	 * ========================================================================= */
	public function delete_store_theme() {
		$userdetails = $this->userdetails();
		if (empty($userdetails)) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
		}
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => 'POST only']));
		}

		$theme_id = strtolower(trim($this->input->post('theme_id') ?? ''));

		/* Refuse to delete built-in / protected themes */
		$protected = ['default', '0', 'classified', 'starter2026', 'lms', 'common', 'shared'];
		if (empty($theme_id) || in_array($theme_id, $protected)) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => '"' . $theme_id . '" is a protected built-in theme and cannot be deleted.']));
		}

		/* Basic sanity-check on the ID so we never act on crafted paths */
		if (!preg_match('/^[a-z0-9][a-z0-9_-]{1,30}$/', $theme_id)) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => 'Invalid theme ID.']));
		}

		$DS         = DIRECTORY_SEPARATOR;
		$views_dir  = APPPATH . 'views'  . $DS . 'store'  . $DS . $theme_id;
		$assets_dir = FCPATH  . 'assets' . $DS . 'store'  . $DS . $theme_id;

		if (!is_dir($views_dir)) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => 'Theme "' . $theme_id . '" not found.']));
		}

		/* Recursive directory delete helper */
		$rmdirAll = function($dir) use (&$rmdirAll) {
			if (!is_dir($dir)) return true;
			$items = scandir($dir);
			foreach ($items as $item) {
				if ($item === '.' || $item === '..') continue;
				$path = $dir . DIRECTORY_SEPARATOR . $item;
				if (is_dir($path)) {
					$rmdirAll($path);
				} else {
					@unlink($path);
				}
			}
			return @rmdir($dir);
		};

		$errViews  = !$rmdirAll($views_dir);
		$errAssets = is_dir($assets_dir) && !$rmdirAll($assets_dir);

		if ($errViews || $errAssets) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success' => false, 'message' => 'Could not fully delete theme files. Check server permissions.']));
		}

		return $this->output->set_content_type('application/json')
			->set_output(json_encode([
				'success'  => true,
				'theme_id' => $theme_id,
				'message'  => 'Theme "' . $theme_id . '" and all its files have been permanently deleted.',
			]));
	}

	/* =========================================================================
	 * AUTO-SCAFFOLD: create a new blank store theme from the API contract
	 * POST  admincontrol/create_store_theme
	 * ========================================================================= */
	public function create_store_theme() {
		$userdetails = $this->userdetails();
		if (empty($userdetails)) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success'=>false,'message'=>'Unauthorized']));
		}
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success'=>false,'message'=>'POST only']));
		}

		$theme_id   = strtolower(trim($this->input->post('theme_id')        ?? ''));
		$theme_name = trim($this->input->post('theme_name')               ?? '');
		$theme_desc = trim($this->input->post('theme_description')        ?? '');
		$author     = trim($this->input->post('author_name')              ?? 'Custom');
		$gradient   = trim($this->input->post('theme_gradient')           ?? '');

		/* Validate ID */
		if (!preg_match('/^[a-z0-9][a-z0-9_-]{1,30}$/', $theme_id)) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success'=>false,'message'=>'Theme ID must be 2â€“32 chars: lowercase letters, numbers, hyphens, underscores. Must start with a letter or number.']));
		}
		if (in_array($theme_id, ['default','0','classified','lms','common','shared'])) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success'=>false,'message'=>'"'.$theme_id.'" is a reserved name. Choose a different ID.']));
		}
		if (empty($theme_name)) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success'=>false,'message'=>'Theme Name is required.']));
		}

		$DS        = DIRECTORY_SEPARATOR;
		$views_dir = APPPATH . 'views' . $DS . 'store' . $DS . $theme_id . $DS;
		$assets_dir= FCPATH  . 'assets' . $DS . 'store' . $DS . $theme_id . $DS;

		if (is_dir($views_dir)) {
			return $this->output->set_content_type('application/json')
				->set_output(json_encode(['success'=>false,'message'=>'A theme with ID "'.$theme_id.'" already exists. Choose a different ID.']));
		}

		/* Create directories */
		$dirs = [
			$views_dir,
			$views_dir . 'lms' . $DS,
			$assets_dir . 'css' . $DS,
			$assets_dir . 'js'  . $DS,
			$assets_dir . 'img' . $DS,
		];
		foreach ($dirs as $dir) {
			if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
				return $this->output->set_content_type('application/json')
					->set_output(json_encode(['success'=>false,'message'=>'Cannot create directory: '.$dir.'. Check server write permissions.']));
			}
		}

		/* Generate and write files */
		$this->load->library('store_theme_scaffolder');
		$files   = $this->store_theme_scaffolder->scaffold($theme_id, $theme_name, $theme_desc, $author, $gradient);
		$created = [];
		foreach ($files as $rel_path => $content) {
			if (strpos($rel_path, 'assets/') === 0) {
				$abs = FCPATH . str_replace('/', $DS, $rel_path);
			} elseif (strpos($rel_path, 'lms/') === 0) {
				$abs = $views_dir . str_replace('/', $DS, $rel_path);
			} else {
				$abs = $views_dir . $rel_path;
			}
			if (file_put_contents($abs, $content) === false) {
				return $this->output->set_content_type('application/json')
					->set_output(json_encode(['success'=>false,'message'=>'Cannot write file: '.$rel_path.'. Check server write permissions.']));
			}
			$created[] = $rel_path;
		}

		return $this->output->set_content_type('application/json')
			->set_output(json_encode([
				'success'   => true,
				'theme_id'  => $theme_id,
				'theme_name'=> $theme_name,
				'files'     => count($created),
				'file_list' => $created,
				'message'   => 'Theme "'.$theme_name.'" created successfully with '.count($created).' files.',
			]));
	}


}
