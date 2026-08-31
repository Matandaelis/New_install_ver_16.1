<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/Affiliate-Script-SDK/AffiliateScript.php';

class User extends REST_Controller {

        public function __construct() {
            parent::__construct();

            $this->load->model('user_model','user');
            $this->load->library('email');
            $this->load->library('form_validation');
            $this->load->model('Common_model');
            $this->load->model('Product_model');
            $this->load->model('Total_model');
            $this->load->library('user_agent');
            $this->load->model("Form_model");
            $this->load->model('IntegrationModel');
            $this->load->model('Report_model');
            $this->load->model('Wallet_model');
            $this->load->model('PagebuilderModel');
            $this->load->helper('reusable');
        }


        public function get_restricted_vendors(int $user_id = null, $product_slug=null) {
           
            $this->load->model('Product_model');
            $restricted_vendors = [];

            //get all vendors from database
            $vendors = $this->db->query('SELECT id from users where is_vendor = 1')->result_array();

            //restrict function for store mode
            if($product_slug) {
                $product = $this->db->query("SELECT * FROM product WHERE product_slug like '". $product_slug ."' ")->row_array();
                if(!empty($product)) {

                    $site_setting = $this->Product_model->getSettings('marketvendorstatus');
                    $store_setting = $this->Product_model->getSettings('store', 'store_mode');
                    $market_vendor = $this->Product_model->getSettings('market_vendor');
                    $vendor_setting = $this->Product_model->getSettings('vendor');

                    if ($vendor_setting['storestatus'] == 0) {
                        $restricted_vendors[] = $user_id;
                    }
                }
            }

            $vendoerMinDeposit = $this->Product_model->getSettings('site', 'vendor_min_deposit');

            $vendoerMinDeposit = isset($vendoerMinDeposit['vendor_min_deposit']) ? $vendoerMinDeposit['vendor_min_deposit'] : 0;

            $this->load->model('Total_model');

            //restrict function for deposit
            $vendorDepositStatus = $this->Product_model->getSettings('vendor', 'depositstatus');
            if($vendorDepositStatus['depositstatus'] == 1){
                if($user_id == null) {
                    foreach($vendors as $v) {
                        $balence = $this->Total_model->getUserBalance($v['id']);
                        if($balence < $vendoerMinDeposit) {
                            $restricted_vendors[] = $v['id'];
                        }
                    }
                } else {
                    
                    if($product_slug==null){
                        $balence = $this->Total_model->getUserBalance($user_id);
                        if($balence < $vendoerMinDeposit) {
                            $restricted_vendors[] = $user_id;
                        }
                    }
                }
            }

            $blocked_vendors = $this->Product_model->getBlockedVendors();           

            $restricted_vendors = array_unique(array_merge($blocked_vendors, $restricted_vendors));

            $MembershipSetting = $this->Product_model->getSettings('membership');

            if($MembershipSetting['status']){
                $noMembershipVendors = $this->getNoMembershipUsers($restricted_vendors); 
                $restricted_vendors = array_unique(array_merge($noMembershipVendors, $restricted_vendors));
            }

            return $restricted_vendors;
        }

        public function getNoMembershipUsers($alreadyRestricted) {
            $result = [];
            if(empty($alreadyRestricted)) {
                $users = $this->db->query('select id from users where type="user"')->result();
            } else {
                $users = $this->db->query('select id from users where type="user" and id NOT IN ('.implode(',', $alreadyRestricted).')')->result();
            }

            foreach($users as $v) {
                $user = App\User::find($v->id);

                if(empty($user) || (int)$user->plan_id == 0){
                    $result[] = $v->id;continue;
                }

                if((int)$user->plan_id > 0){
                    $plan = $user->plan();

                    if(empty($plan)){

                        $result[] = $v->id;continue;

                    } else if($plan->isExpire() || !$plan->strToTimeRemains() > 0){

                        $lifetime = ($plan->is_lifetime && $plan->status_id) ? true : false;

                        if(!$lifetime){
                            $result[] = $v->id;continue;
                        }

                    }

                }
            }

            return $result;
        }


        public function all_transaction_get()
        {
            // Get request headers
            $headers = $this->input->request_headers();

            // Verify the request
            $verify_data = verify_request();
            if (isset($verify_data['status']) && $verify_data['status'] == 401) {
                $this->response(['status' => 401, 'message' => 'Unauthorized Access!'], 401);
                return;
            }

            // Extract user details
            $userdetails = $verify_data['userdata'];

            // Load the pagination library
            $this->load->library('pagination');

            // Fetch 'page_id' and 'per_page' query parameters from the URL
            $page_id = $this->input->get('page_id', TRUE);
            $per_page = $this->input->get('per_page', TRUE);

            if (!$page_id) {
                $page_id = 1;
            }
            if (!$per_page) {
                $per_page = 10;
            }

            $config['per_page'] = $per_page;
            $offset = ($page_id - 1) * $per_page;

            $all_transactions = $this->Wallet_model->getAllTransaction($userdetails, $offset, $config['per_page']);

            // Respond based on the fetched data
            if (!empty($all_transactions)) {
                $this->response(array_merge([
                    'status' => TRUE,
                    'message' => 'Transactions fetched successfully',
                    'data' => $all_transactions
                ], get_currency_settings()), REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No transactions found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }


        public function get_registration_form_get()
        {
            $json = array(
                'status' => FALSE,
                'message' => 'registration form not prepared',
                'errors' => array()
            );

            $register_form = $this->PagebuilderModel->getSettings('registration_builder');

            if($register_form && count($json['errors']) == 0) {
                $customField = json_decode($register_form['registration_builder'],1);
                $json = array(
                    'status' => TRUE,
                    'data' => $customField,
                );
            }

            $this->response($json, REST_Controller::HTTP_OK);
        }

        public function get_payment_details_get() {
            $headers = $this->input->request_headers();
            $verify_data = verify_request();

            if (isset($verify_data['status']) && $verify_data['status'] == 401) {
                $response = array(
                    'status' => 401,
                    'message' => 'Unauthorized Access!',
                );
                $this->response($response, 401); 
            } else {
                $user_id = $verify_data['userdata']['id'];
            }

            $data = array();

            // Load payment methods for countries
            $this->load->model('Withdrawal_payment_model');
            $payment_methods = $this->Withdrawal_payment_model->getEnabledPaymentMethods();

            $country_list = [];

            if (
                isset($payment_methods['bank_transfer']['bank_transfer_mode']) &&
                $payment_methods['bank_transfer']['bank_transfer_mode'] == 'specific' &&
                isset($payment_methods['bank_transfer']['bank_transfer_countries'])
            ) {
                foreach ($payment_methods['bank_transfer']['bank_transfer_countries'] as $country => $status) {
                    if ($status == 1) {
                        $country_list[] = $country;
                    }
                }
            }

            $data['payment_methods'] = $payment_methods;
            $data['available_countries'] = $country_list;

            // Primary Payment Method
            $data['primary_payment_method'] = @$verify_data['userdata']['primary_payment_method'];
            if (empty($data['primary_payment_method'])) {
                $data['notification']['primary_payment_method'] = 'Primary payment method is not set!';
            }

            // Payment List (Bank details)
            $data['paymentlist'] = $this->Product_model->getAllPayment($user_id);
            if (isset($data['paymentlist'][0])) {
                $row = $data['paymentlist'][0];
                $data['paymentlist'] = array(
                    'payment_id' => $row['payment_id'],
                    'payment_bank_name' => $row['payment_bank_name'],
                    'payment_account_number' => $row['payment_account_number'],
                    'payment_account_name' => $row['payment_account_name'],
                    'payment_country' => $row['payment_country'] ?? '',

                    // Country-specific fields
                    'payment_routing_number' => $row['payment_routing_number'] ?? '',
                    'payment_ifsc_code' => $row['payment_ifsc_code'] ?? '',
                    'payment_sort_code' => $row['payment_sort_code'] ?? '',
                    'payment_bsb_number' => $row['payment_bsb_number'] ?? '',
                    'payment_transit_institution_number' => $row['payment_transit_institution_number'] ?? '',
                    'payment_iban_bic' => $row['payment_iban_bic'] ?? '',
                    'payment_cnaps_code' => $row['payment_cnaps_code'] ?? '',
                    'payment_swift_code' => $row['payment_swift_code'] ?? '',
                    'payment_clearing_code' => $row['payment_clearing_code'] ?? '',
                    'payment_bank_branch_number' => $row['payment_bank_branch_number'] ?? '',
                );
            } else {
                $data['paymentlist'] = array(
                    'payment_id' => 0,
                    'payment_bank_name' => '',
                    'payment_account_number' => '',
                    'payment_account_name' => '',
                    'payment_country' => '',

                    // Country-specific fields empty
                    'payment_routing_number' => '',
                    'payment_ifsc_code' => '',
                    'payment_sort_code' => '',
                    'payment_bsb_number' => '',
                    'payment_transit_institution_number' => '',
                    'payment_iban_bic' => '',
                    'payment_cnaps_code' => '',
                    'payment_swift_code' => '',
                    'payment_clearing_code' => '',
                    'payment_bank_branch_number' => '',
                );
                $data['notification']['paymentlist'] = 'Bank details are not set!';
            }

            // PayPal Accounts
            $data['paypalaccounts'] = $this->Product_model->getPaypalAccounts($user_id);
            if (isset($data['paypalaccounts'][0])) {
                $data['paypalaccounts'] = array(
                    'paypal_email' => $data['paypalaccounts'][0]['paypal_email'],
                    'id' => $data['paypalaccounts'][0]['id'],
                );
            } else {
                $data['paypalaccounts'] = array(
                    'paypal_email' => '',
                    'id' => 0,
                );
                $data['notification']['paypalaccounts'] = 'PayPal account details are not set!';
            }

            $data['paymentlist']['paypalaccounts'] = $data['paypalaccounts'];

            // Load custom gateway settings
            $this->load->model('Payment_details_model');
            $data['custom_gateway_settings'] = $this->Payment_details_model->getAllUserPaymentData($user_id);

            $json = array(
                'status' => TRUE,
                'data' => $data,
            );

            $this->response($json, REST_Controller::HTTP_OK);
        }

        public function payment_details_post(){

            $json = array(
                'status' => FALSE,
                'message' => '',
                'errors' => array()
            );

            $headers = $this->input->request_headers();
            $verify_data = verify_request();

            if(isset($verify_data['status']) && $verify_data['status'] == 401) {
                $response = array(
                    'status' => 401,
                    'message' => 'Unauthorized Access!',
                );
                $this->response($response, 401);
            
            } else {
                $user_id = $verify_data['userdata']['id'];
            }

            $post = $this->input->post(null, true);

            if (isset($post['add_paypal'])) {
                $email = $this->input->post('paypal_email', true);
                if ((int)$post['id'] > 0) {
                    $this->db->update("paypal_accounts", array(
                        'paypal_email' => $email,
                        'user_id' => $user_id,
                    ), array('id' => $post['id']));
                } else {
                    $this->db->insert("paypal_accounts", array(
                        'paypal_email' => $email,
                        'user_id' => $user_id,
                    ));
                }
                $json = array(
                    'status' => TRUE,
                    'message' => 'Paypal Account saved successfully',
                    'errors' => array()
                );

            } elseif (isset($post['add_custom_gateway'])) {
                $gateway_code = $this->input->post('gateway_code', true);
                
                if (empty($gateway_code)) {
                    $json = array(
                        'status' => FALSE,
                        'message' => 'Invalid gateway code',
                        'errors' => array()
                    );
                } else {
                    $gateway_data = array();
                    foreach ($post as $key => $value) {
                        if (strpos($key, $gateway_code . '_') === 0) {
                            $field_name = substr($key, strlen($gateway_code . '_'));
                            $gateway_data[$field_name] = $value;
                        }
                    }
                    
                    if (!empty($gateway_data)) {
                        $this->load->model('Payment_details_model');
                        $this->Payment_details_model->saveUserPaymentData($user_id, $gateway_code, $gateway_data);
                        
                        $json = array(
                            'status' => TRUE,
                            'message' => 'Payment details saved successfully',
                            'errors' => array()
                        );
                    } else {
                        $json = array(
                            'status' => FALSE,
                            'message' => 'No data provided',
                            'errors' => array()
                        );
                    }
                }

            } elseif (isset($post['add_primary_payment'])) {
                $primary_payment_method = $this->input->post('primary_payment_method', true);
                
                if (!empty($primary_payment_method)) {
                    $this->db->update("users", array(
                        'primary_payment_method' => $primary_payment_method
                    ), array('id' => $user_id));
                    
                    $json = array(
                        'status' => TRUE,
                        'message' => 'Primary payment method saved successfully',
                        'errors' => array()
                    );
                } else {
                    $json = array(
                        'status' => FALSE,
                        'message' => 'Primary payment method is required',
                        'errors' => array()
                    );
                }

            } else {
                $this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');
                $this->load->model('Withdrawal_payment_model');

                $payment_methods = $this->Withdrawal_payment_model->getEnabledPaymentMethods();
                
                $this->form_validation->set_rules('payment_bank_name', 'Bank Name', 'required');
                $this->form_validation->set_rules('payment_account_number', 'Account Number', 'required');
                $this->form_validation->set_rules('payment_account_name', 'Account Name', 'required');

                if (isset($payment_methods['bank_transfer']['bank_transfer_mode']) && 
                    $payment_methods['bank_transfer']['bank_transfer_mode'] == 'specific') {
                    $this->form_validation->set_rules('payment_country', 'Country', 'required');
                }

                if($this->form_validation->run()) {
                    $details = array(
                        'payment_bank_name'      => $this->input->post('payment_bank_name', true),
                        'payment_account_number' => $this->input->post('payment_account_number', true),
                        'payment_account_name'   => $this->input->post('payment_account_name', true),
                        'payment_status'         => 1,
                        'payment_ipaddress'      => $this->input->ip_address(),
                    );

                    if(isset($payment_methods['bank_transfer']['bank_transfer_mode']) && 
                       $payment_methods['bank_transfer']['bank_transfer_mode'] == 'specific') {
                        $details['payment_country'] = $this->input->post('payment_country', true);
                        
                        $countryFieldMap = get_country_field_map();
                        $selected_country = $details['payment_country'];
                        
                        if (isset($countryFieldMap[$selected_country])) {
                            $field_name = $countryFieldMap[$selected_country];
                            $field_value = $this->input->post($field_name, true);
                            if(!empty($field_value)){
                                $details[$field_name] = $field_value;
                            }
                        }
                    }

                    if ((int)$post['payment_id'] > 0) {
                        $details['payment_updated_by'] = $user_id;
                        $details['payment_updated_date'] = date('Y-m-d H:i:s');
                        $this->Product_model->update_data('payment_detail', $details, array('payment_id' => (int)$post['payment_id']));
                        
                        $json = array(
                            'status' => TRUE,
                            'message' => 'Payment details updated successfully',
                            'errors' => array()
                        );
                    } else {
                        $details['payment_created_by'] = $user_id;
                        $details['payment_created_date'] = date('Y-m-d H:i:s');
                        $this->Product_model->create_data('payment_detail', $details);
                        
                        $json = array(
                            'status' => TRUE,
                            'message' => 'Payment details added successfully',
                            'errors' => array()
                        );
                    }
                } else {
                    $json = array(
                        'status' => FALSE,
                        'message' => validation_errors(),
                        'errors' => array()
                    );
                }
            }

            $this->response($json, REST_Controller::HTTP_OK);
        }


        public function license_validate_post() {
            header('Content-Type: application/json');
            
            $json = ['status' => false, 'message' => 'Invalid license key'];

            $post = file_get_contents('php://input');
            $data = json_decode($post, true);
            if (!is_array($data)) {
                $data = $this->input->post(null, true);
            }

            $license_key = isset($data['license_key']) ? trim($data['license_key']) : '';
            $base_url = isset($data['base_url']) ? trim($data['base_url']) : '';

            $stored_key = $this->_get_stored_license_key();

            if ($stored_key !== '' && $license_key !== '' && strcasecmp($license_key, $stored_key) === 0) {
                $domain_ok = true;
                if ($base_url !== '') {
                    $stored_domain = $this->_get_license_domain();
                    if ($stored_domain !== '') {
                        $mobile_host = parse_url($base_url, PHP_URL_HOST);
                        if (!$mobile_host) {
                            $mobile_host = parse_url('http://' . $base_url, PHP_URL_HOST);
                        }
                        $stored_host = parse_url($stored_domain, PHP_URL_HOST);
                        if (!$stored_host) {
                            $stored_host = $stored_domain;
                        }
                        $dev_hosts = ['127.0.0.1', 'localhost', '::1', '10.0.2.2'];
                        $mobile_is_dev = in_array($mobile_host, $dev_hosts, true);
                        $stored_is_dev = in_array($stored_host, $dev_hosts, true);
                        if ($mobile_is_dev && $stored_is_dev) {
                            $domain_ok = true;
                        } elseif ($mobile_host && $stored_host && strcasecmp($mobile_host, $stored_host) !== 0) {
                            $domain_ok = false;
                        }
                    }
                }

                if ($domain_ok) {
                    $json = [
                        'status' => true,
                        'message' => 'License valid',
                    ];
                    $this->log_mobile_app_connection($base_url, $license_key);
                } else {
                    $json = [
                        'status' => false,
                        'message' => 'License not valid for this domain',
                    ];
                }
            }

            echo json_encode($json);
            exit;
        }

        private function _get_stored_license_key() {
            $license_cache = APPPATH . 'license-easy-data-affiliateporsaas.json';
            if (file_exists($license_cache)) {
                $cache_data = @json_decode(file_get_contents($license_cache), true);
                if ($cache_data && isset($cache_data['license_key']) && $cache_data['license_key'] !== '') {
                    return trim($cache_data['license_key']);
                }
            }

            $cfg_key = $this->config->item('codecanyon_license');
            if ($cfg_key && $cfg_key !== '') {
                return trim($cfg_key);
            }

            if (defined('CODECANYON_LICENCE') && CODECANYON_LICENCE !== '') {
                return trim(CODECANYON_LICENCE);
            }

            if (defined('LICENSE_EASY_KEY') && LICENSE_EASY_KEY !== '') {
                return trim(LICENSE_EASY_KEY);
            }

            return '';
        }

        private function _get_license_domain() {
            $license_cache = APPPATH . 'license-easy-data-affiliateporsaas.json';
            if (file_exists($license_cache)) {
                $cache_data = @json_decode(file_get_contents($license_cache), true);
                if ($cache_data && isset($cache_data['website_url']) && $cache_data['website_url'] !== '') {
                    return trim($cache_data['website_url']);
                }
            }

            $guard_cache = APPPATH . 'cache/license_check.json';
            if (file_exists($guard_cache)) {
                $guard_data = @json_decode(file_get_contents($guard_cache), true);
                if ($guard_data && isset($guard_data['domain']) && $guard_data['domain'] !== '') {
                    return trim($guard_data['domain']);
                }
            }

            if (isset($_SERVER['HTTP_HOST'])) {
                return $_SERVER['HTTP_HOST'];
            }

            return '';
        }
        
        private function log_mobile_app_connection($base_url, $license_key) {
            try {
                // Get client IP and user agent
                $ip_address = $this->input->ip_address();
                $user_agent = $this->input->user_agent();
                
                // Check if this is a mobile app connection (not web browser)
                $is_mobile_app = $this->is_mobile_app_request($user_agent);
                
                if ($is_mobile_app) {
                    $file_path = FCPATH . 'application/cache/mobile_app_status.json';
                    $current_time = date('Y-m-d H:i:s');
                    
                    // Load existing status
                    $status = [];
                    if (file_exists($file_path)) {
                        $content = file_get_contents($file_path);
                        $status = json_decode($content, true) ?: [];
                    }
                    
                    // Initialize if needed
                    if (!isset($status['users'])) {
                        $status['users'] = [];
                    }
                    
                    // Get user ID from API request if available
                    $user_id = null;
                    $verify_data = verify_request();
                    if (isset($verify_data['userdata']['id'])) {
                        $user_id = $verify_data['userdata']['id'];
                    }
                    
                    // Create unique identifier (IP or user_id)
                    $user_key = $user_id ?: $ip_address;
                    
                    // Update user record
                    $platform = $this->detect_platform($user_agent);
                    $app_type = $this->detect_app_type($user_agent);
                    
                    if (!isset($status['users'][$user_key])) {
                        $status['users'][$user_key] = [
                            'first_seen' => $current_time,
                            'platform' => $platform,
                            'app_type' => $app_type,
                            'connection_count' => 0
                        ];
                    }
                    
                    $status['users'][$user_key]['last_seen'] = $current_time;
                    $status['users'][$user_key]['platform'] = $platform;
                    $status['users'][$user_key]['app_type'] = $app_type;
                    $status['users'][$user_key]['connection_count']++;
                    
                    // Update summary
                    $status['connected'] = true;
                    $status['last_connected'] = $current_time;
                    $status['total_users'] = count($status['users']);
                    
                    // Count active users (last 24 hours)
                    $active_count = 0;
                    $ios_count = 0;
                    $android_count = 0;
                    
                    foreach ($status['users'] as $user) {
                        if (strtotime($user['last_seen']) > strtotime('-24 hours')) {
                            $active_count++;
                        }
                        if ($user['platform'] === 'iOS') $ios_count++;
                        if ($user['platform'] === 'Android') $android_count++;
                    }
                    
                    $status['active_users'] = $active_count;
                    $status['ios_users'] = $ios_count;
                    $status['android_users'] = $android_count;
                    $status['app_type'] = $app_type;
                    $status['platform'] = $platform;
                    
                    // Keep only last 100 users
                    if (count($status['users']) > 100) {
                        uasort($status['users'], function($a, $b) {
                            return strtotime($b['last_seen']) - strtotime($a['last_seen']);
                        });
                        $status['users'] = array_slice($status['users'], 0, 100, true);
                    }
                    
                    file_put_contents($file_path, json_encode($status));
                }
            } catch (Exception $e) {
                // Log error but don't break the API response
                error_log('Mobile app connection logging failed: ' . $e->getMessage());
            }
        }
        
        private function detect_app_type($user_agent) {
            $user_agent_lower = strtolower($user_agent);
            
            if (strpos($user_agent_lower, 'okhttp') !== false) return 'Android';
            if (strpos($user_agent_lower, 'dart') !== false) return 'Flutter';
            if (strpos($user_agent_lower, 'flutter') !== false) return 'Flutter';
            if (strpos($user_agent_lower, 'react-native') !== false) return 'React Native';
            
            return 'Mobile App';
        }
        
        private function detect_platform($user_agent) {
            $user_agent_lower = strtolower($user_agent);
            
            if (strpos($user_agent_lower, 'android') !== false) return 'Android';
            if (strpos($user_agent_lower, 'ios') !== false) return 'iOS';
            if (strpos($user_agent_lower, 'iphone') !== false) return 'iOS';
            if (strpos($user_agent_lower, 'ipad') !== false) return 'iOS';
            
            return 'Unknown';
        }
        
        private function is_mobile_app_request($user_agent) {
            // Check if this is likely a mobile app request (not a web browser)
            $mobile_app_indicators = [
                'okhttp', 'retrofit', 'dart', 'flutter', 'react-native', 
                'xamarin', 'cordova', 'phonegap', 'ionic'
            ];
            
            $user_agent_lower = strtolower($user_agent);
            
            foreach ($mobile_app_indicators as $indicator) {
                if (strpos($user_agent_lower, $indicator) !== false) {
                    return true;
                }
            }
            
            // If no specific mobile app indicators, check if it's not a common web browser
            $web_browsers = [
                'mozilla', 'chrome', 'safari', 'firefox', 'edge', 'opera', 'internet explorer'
            ];
            
            foreach ($web_browsers as $browser) {
                if (strpos($user_agent_lower, $browser) !== false) {
                    return false;
                }
            }
            
            // If no web browser indicators found, assume it might be a mobile app
            return true;
        }


        public function registarion_post() 
        {
            $json = array(
                'status' => FALSE,
                'message' => 'user registration failed',
                'errors' => array()
            );

            $post = $this->input->post(null,true);
            
            if(!$post){
                $_POST = json_decode(file_get_contents('php://input'), TRUE);
                $post = $_POST;
            }

            $post['user_type'] = 'user';

            $post['affiliate_id'] = $refid = reh_fetchRefferer();

            if(isset($post['refid']) && !empty($post['refid'])){
                $sql = "SELECT id,email FROM users WHERE id = ?";
                $checkRefUsername = $this->db->query($sql,(int) $post['refid'])->row_array();
                if($checkRefUsername)
                    $refid = ($refid == 0) ? $post['refid'] : $refid;
            }
            
            $this->load->library('form_validation');
            
            $form_validation_rules = vfor_user_registration_api();

            $this->form_validation->set_rules($form_validation_rules);

            $json['errors'] = array();

            if ($this->form_validation->run() == FALSE) {
                $json['errors'] = $this->form_validation->error_array();
            }

            $register_form = $this->PagebuilderModel->getSettings('registration_builder');
            
            if($register_form && count($json['errors']) == 0) {
                
                $customField = json_decode($register_form['registration_builder'],1);
                
                $filesAttached = [];

                foreach ($customField as $_key => $_value) {

                    if(isset($_value['hide_on_registration']) && $_value['hide_on_registration']) continue;

                    $field_name = 'custom_'. $_value['name'];

                    $config = reh_user_registration_file_upload_config();

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

                                    $this->CI->load->library('upload', $config);

                                    $this->CI->upload->initialize($config);

                                    if (!$this->CI->upload->do_upload('attahced_multi_azkja')) {
                                        $error = $this->CI->upload->display_errors();
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

                                $filesAttached[$field_name] = $attahced_multi_azkja;
                            } else {
                                if(isset($_value['required']) && $_value['required'] && (!isset($filesAttached[$field_name]) || empty($filesAttached[$field_name]))) {
                                    $json['errors'][$field_name] = "Please select file for upload!";
                                    break;
                                }
                            }
                        } else {
                            if(isset($_FILES[$field_name]['name']) && !empty($_FILES[$field_name]['name'])) {

                                $config['file_name']  = random_string('alnum', 32);

                                $this->CI->load->library('upload', $config);

                                $this->CI->upload->initialize($config);

                                if (!$this->CI->upload->do_upload($field_name)) {
                                    $error = $this->CI->upload->display_errors();
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

                                $filesAttached[$field_name] = $attahced_multi_azkja;
                            } else {
                                if(isset($_value['required']) && $_value['required'] && (!isset($filesAttached[$field_name]) || empty($filesAttached[$field_name]))) {
                                    $json['errors'][$field_name] = "Please select file for upload!";
                                    break;
                                }
                            }
                        }

                        
                    } else {
                        $json = vfor_user_registration_custom_fields($json, $post, $_value, $field_name);
                    }

                }
            }

            if( count($json['errors']) == 0){
                    $checkEmail = $this->db->query("SELECT id FROM users WHERE email like ". $this->db->escape($this->input->post('email',true)) ." ")->num_rows();
                    if($checkEmail > 0){ $json['errors']['email'] = "Email Already Exist"; }
                    $checkUsername = $this->db->query("SELECT id FROM users WHERE username like ". $this->db->escape($this->input->post('username',true)) ." ")->num_rows();
                    if($checkUsername > 0){ $json['errors']['username'] = "Username Already Exist"; }

                    if(count($json['errors']) == 0){    
                        $user_type = 'user';
                        $geo = $this->ip_info();
                        
                        $phone = $this->input->post('phone', true);
                        
                        if((empty($geo['id']) || $geo['id'] == 0) && !empty($phone)) {
                            $phone_prefix = '';
                            if(preg_match('/^\+(\d+)/', $phone, $matches)) {
                                $phone_prefix = $matches[1];
                            }
                            
                            if(!empty($phone_prefix)) {
                                $country_from_phone = $this->db->query("SELECT id, name FROM countries WHERE phonecode = " . $this->db->escape($phone_prefix) . " LIMIT 1")->row_array();
                                if(!empty($country_from_phone)) {
                                    $geo['id'] = $country_from_phone['id'];
                                    $geo['country'] = $country_from_phone['name'];
                                }
                            }
                        }
                        
                        $commition_setting = $this->Product_model->getSettings('referlevel');

                        $disabled_for = json_decode( (isset($commition_setting['disabled_for']) ? $commition_setting['disabled_for'] : '[]'),1); 
                        
                        if((int)$commition_setting['status'] == 0){ $refid  = 0; }
                        else if((int)$commition_setting['status'] == 2 && in_array($refid, $disabled_for)){ $refid = 0; }

                        $custom_fields = array();
                        foreach ($this->input->post(null,true) as $key => $value) {
                            if(!in_array($key, array('affiliate_id','terms','cpassword','firstname','lastname','email','username','password'))){
                                $custom_fields[$key] = $value;
                            }
                        }

                        $membership = $this->Product_model->getSettings('membership');

                        $allMembershipPlans = App\MembershipPlan::all();

                        $is_vendor = $this->input->post('is_vendor',true);

                        if(empty($is_vendor)) {
                            $is_vendor = 0;
                        }

                        $plan_id = -1;
                        
                        if($is_vendor == 1 && ($membership['status'] == 0 || $membership['status'] == 3)) {
                            $plan_id = -1;
                        } else if($membership['status']){
                            $plan_id = 0;
                        }

                        $is_approval_needed = $this->Product_model->getSettings('store', 'registration_approval');

                        $registration_approval = (isset($is_approval_needed['registration_approval']) && $is_approval_needed['registration_approval'] == 0) ? 1 : 0;

                        $defaultUserGroup = $this->user->getDefaultGroup();

                        $userGroups = (!empty($defaultUserGroup)) ? $defaultUserGroup->id : null;

                        $data = $this->user->insert(array(
                            'firstname'                 => $this->input->post('firstname',true),
                            'lastname'                  => $this->input->post('lastname',true),
                            'email'                     => $this->input->post('email',true),
                            'username'                  => $this->input->post('username',true),
                            'password'                  => sha1($this->input->post('password',true)),
                            'device_type'               => $this->input->post('device_type',true),
                            'device_token'              => $this->input->post('device_token',true),
                            'refid'                     => $refid,
                            'plan_id'                   => (int)$plan_id,
                            'type'                      => $user_type,
                            'Country'                   => $geo['id'],
                            'City'                      => (string)$geo['city'],
                            'phone'                     => $phone,
                            'twaddress'                 => '',
                            'address1'                  => '',
                            'address2'                  => '',
                            'ucity'                     => $geo['city'],
                            'ucountry'                  => $geo['id'],
                            'state'                     => $geo['state'],
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
                            'PhoneNumber'               => $phone,
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
                            'is_vendor'                 =>  $is_vendor,
                            'reg_approved'              => $registration_approval,
                            'value'                     => json_encode(array_merge($custom_fields, $filesAttached)),
                            'groups'                    => $userGroups
                        ));

                       $last_user_id = $this->db->insert_id();

                       if((int)$is_vendor == 1 && !empty($last_user_id)) {
                           $existing_vendor_setting = $this->db->get_where('vendor_setting', ['user_id' => $last_user_id])->row_array();
                           if(empty($existing_vendor_setting)) {
                               $this->db->insert('vendor_setting', [
                                   'user_id' => $last_user_id,
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

                       if($membership['status'] && $registration_approval == 1){
                            if((int)$is_vendor == 1) {
                                $plan_id = $membership['default_vendor_plan_id'] ?? $membership['default_plan_id'];
                            } else {
                                $plan_id = $membership['default_affiliate_plan_id'] ?? $membership['default_plan_id'];
                            }
                            if($plan_id > 0){
                                $plan = App\MembershipPlan::find($plan_id);
                                if($plan){
                                    $user = App\User::find($data);
                                    $plan->buy($user,1, 'Default plan started','Default');

                                    // process fixed and custom % comission
                                    $processReferComission = true;
                                }
                            }
                        }

                        if(empty($allMembershipPlans) || !isset($membership['status']) || empty($membership['status']) || !$membership['status']) {
                            // process fixed and custom % comission
                            $processReferComission = true;      
                        }

                        $json['processReferComission'] = $processReferComission;

                        if(!empty($data) && $user_type == 'user'){

                            // Telegram message
                            $username = $this->input->post('username', true);
                            $email    = $this->input->post('email', true);

                            $message = buildTelegramUserRegisterMessage($username, $email, $is_vendor);
                            sendTelegramNotification($message, 'user_register');
                            // Telegram message

                            if(isset($processReferComission) && (int)$refid > 0) {
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
                                                    'status'       => (int)$registration_approval,
                                                    'user_id'      => $levelUser,
                                                    'amount'       => $_giveAmount,
                                                    'dis_type'     => '',
                                                    'comment'      => "Level {$l} : ".'Commission for new affiliate registrion Id ='. $last_user_id .' | Name : '. $this->input->post('firstname',true) ." " .$this->input->post('lastname',true),
                                                    'type'         => 'refer_registration_commission',
                                                    'reference_id' => $last_user_id,
                                                    'group_id' => $comission_group_id,
                                                ));
                                            }
                                        }
                                    }
                                }
                            }

                            $notificationData = array(
                                'notification_url'          => '/userslist/'.$data,
                                'notification_type'         =>  'user',
                                'notification_title'        =>  __('user.new_user_registration'),
                                'notification_viewfor'      =>  'admin',
                                'notification_actionID'     =>  $data,
                                'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' register as a  on affiliate Program on '.date('Y-m-d H:i:s'),
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

                            $json['success']  =  "You've Successfully registered";
                            $user_details_array=$this->user->login($this->input->post('username',true));

                            $this->user->update_user_login($user_details_array['id']);
                            
                            $this->load->model('Mail_model');

                            // 1 == enable (Approval For Registration)

                            $where = array('id'=>$last_user_id);
                            $new_post = $this->Common_model->select_where_result('users', $where);
                            $new_post['user_type'] = $new_post['type'];
                            if($registration_approval == 0) {
                                $this->Mail_model->send_registration_request_mail($post);
                            } else {
                                $this->Mail_model->send_register_mail($post,__('user.welcome_to_new_user_registration'));
                            }

                            $json = array(
                                'status' => TRUE,
                                'message' => 'user registration successfully'
                            );
                        }
                    }
            }

            $this->response($json, REST_Controller::HTTP_OK);
        }

        public function insertnotification($postData = null){
            if(!empty($postData)){
                $data['custom'] = $this->Product_model->create_data('notification', $postData);
            }
        }
            
        public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
            $this->load->helper('geolocation');
            $geolocation = new Geolocation_helper();
            return $geolocation->ip_info($ip, $purpose, $deep_detect);
        }
         
        public function login_post()
        {

            $this->form_validation->set_rules('username', 'username', 'required|trim',
                array('required'      => 'Oops ! user name is required.'
            ));

            $this->form_validation->set_rules('password', 'password', 'required|trim',
                array('required'      => 'Oops ! password is required.'
            ));

            $this->form_validation->set_rules('device_type', 'device_type', 'required|trim',
                array('required'      => 'Oops ! device type is required.'
            ));

            $this->form_validation->set_rules('device_token', 'device_token', 'required|trim',
                array('required'      => 'Oops ! device token is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                if(!empty(form_error('username')))$errors['username'] =form_error('username');
                if(!empty(form_error('password')))$errors['password'] =form_error('password');
                if(!empty(form_error('device_type')))$errors['device_type'] =form_error('device_type');
                if(!empty(form_error('device_token')))$errors['device_token'] =form_error('device_token');

                $response['message'] = "Please required field";
                $response['errors'] = $errors;

                $this->response($response, 422);
            }
            else
            {
                $created_date = date('Y-m-d H:i:s');

                extract($_POST);
                $uniquestring = $created_date.$username;

                $where = array(
                    'username'=>$username,
                    'password'=>sha1($password)
                );
                $count = $this->Common_model->get_total_rows('users', $where);

                if($count == 0)
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'username and password something went wrong'
                    );
                    $this->response($response, REST_Controller::HTTP_OK);
                }
                else
                {
                    $get_user_data = $this->Common_model->get_data_row('users', $where, $field = '*', 'id');
                    if(isset($get_user_data['status']) && $get_user_data['status'] == 0)
                    {
                        $response = array(
                            'status' => FALSE,
                            'message' => 'Your account has been blocked by admin'
                        );
                        $this->response($response, REST_Controller::HTTP_OK);
                        return;
                    }

                    if(isset($get_user_data['reg_approved']) && $get_user_data['reg_approved'] == 0)
                    {
                        $response = array(
                            'status' => FALSE,
                            'message' => 'Your account is pending approval by admin'
                        );
                        $this->response($response, REST_Controller::HTTP_OK);
                        return;
                    }

                    $token = AUTHORIZATION::generateToken($uniquestring);
                    $data = array('token'=>$token,'device_type'=>$device_type,'device_token'=>$device_token);
                    $update_token = $this->Common_model->update('users', $where, $data);
                    if($update_token)
                    {
                        $country_code = '';
                        $country_flag = '';
                        $country_name = '';
                        $country_id = !empty($get_user_data['ucountry']) ? $get_user_data['ucountry'] : (!empty($get_user_data['Country']) ? $get_user_data['Country'] : '');
                        
                        if(!empty($country_id)){
                            $country_data = $this->db->query("SELECT sortname, name FROM countries WHERE id = ?", array($country_id))->row();
                            if($country_data){
                                $country_code = $country_data->sortname;
                                $country_name = $country_data->name;
                                $country_flag = base_url('assets/template/images/flags/'. strtolower($country_code) .'.png');
                            }
                        }

                        $phone_val = !empty($get_user_data['phone']) ? $get_user_data['phone'] : (!empty($get_user_data['PhoneNumber']) ? $get_user_data['PhoneNumber'] : '');
                        $city_val = !empty($get_user_data['ucity']) ? $get_user_data['ucity'] : (!empty($get_user_data['City']) ? $get_user_data['City'] : '');
                        $zip_val = !empty($get_user_data['uzip']) ? $get_user_data['uzip'] : (!empty($get_user_data['Zip']) ? $get_user_data['Zip'] : '');

                        $response = array(
                            'status' => TRUE,
                            'message' => 'user login successfully',
                            'data' => array_merge([
                                'token' => $token,
                                'user_status' => AffiliateScript::usersProfileStatus(['userdetails'=>$get_user_data])['status'],
                                'firstname' => $get_user_data['firstname'],
                                'lastname' => $get_user_data['lastname'],
                                'email' => $get_user_data['email'],
                                'PhoneNumber' => $phone_val,
                                'is_vendor' => $get_user_data['is_vendor'],
                                'role' => $get_user_data['type'],
                                'profile_avatar' =>  (!empty($get_user_data['avatar'])) ? base_url('assets/images/users/'.$get_user_data['avatar']) : base_url('assets/template/images/no-image.jpg'),
                                'country_id' => $country_id,
                                'country_code' => $country_code,
                                'country_name' => $country_name,
                                'country_flag' => $country_flag,
                                'city' => $city_val,
                                'pincode' => $zip_val,
                            ], get_currency_settings())
                        );
                        $this->response($response, REST_Controller::HTTP_OK);
                    }
                    else
                    {
                        $response = array(
                            'status' => FALSE,
                            'message' => 'user login failed'
                        );
                        $this->response($response, REST_Controller::HTTP_OK);
                    }
                }
            }
        }

        public function dashboard_get()
        {

            $invalidRequest = FALSE;

            $availableKeys = ['plan_details', 'totals_count', 'top_affiliate', 'chart_data', 'notifications', 'market_tools'];

            $requestKeys = isset($this->_get_args['includes']) ? $this->_get_args['includes'] : null;

            if(!empty($requestKeys)) {
                $requestKeys = explode(',',$requestKeys);
            }

            if(!empty($requestKeys)) {
                foreach ($requestKeys as $key) {
                    if(empty($key))
                    {
                        unset($requestKeys[$key]);
                    } else if(! in_array($key, $availableKeys)){
                        $invalidRequest = TRUE;
                        
                        $response = array(
                            'status' => 401,
                            'message' => 'Invalid includes arguments provided!',
                        );

                        $this->response($response, 401);
                    }
                }
            }

            $headers = $this->input->request_headers();
            $verify_data = verify_request();
            
            if(isset($verify_data['status']) && $verify_data['status'] == 401 && !$invalidRequest) {
                $response = array(
                    'status' => 401,
                    'message' => 'Unauthorized Access!',
                );

                $this->response($response, 401);
            }
            else
            {
                $id = $verify_data['userdata']['id'];

                $hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
                $fun_c_format = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'user')) ? 'c_format_nosym' : 'c_format';

                // Set session userCurrency for API context
                $user_setting = $this->Product_model->getSettings('site', $id);
                if(isset($user_setting['site_currency']) && !empty($user_setting['site_currency'])) {
                    $_SESSION['userCurrency'] = $user_setting['site_currency'];
                } else {
                    $default_currency = $this->db->query("SELECT code FROM currency WHERE is_default=1")->row_array();
                    $_SESSION['userCurrency'] = $default_currency['code'];
                }

                $plan_id = $verify_data['userdata']['plan_id'];
                $type = $verify_data['userdata']['type'];
                $store_slug_url=$verify_data['userdata']['store_slug'];
                $userdashboard_settings = $this->Common_model->getUserDashboardSettings();
                $store = $this->Product_model->getSettings('store');
                $referlevelSettings = $this->Product_model->getSettings('referlevel');
                $refer_status = reh_fetchReffererStatus($referlevelSettings, $id);

                $store_slug = $this->db->query("SELECT slug FROM slugs WHERE user_id = '".(int)$id."' AND type = 'store'")->row()->slug;
                $invitationlinkid=0;
                if(isset($userdashboard_settings) && isset($userdashboard_settings['invitation_link_id']))
                {
                  $invitationlinkidarray=$userdashboard_settings['invitation_link_id'];
                  $invitationlinkid=$invitationlinkidarray['setting_value'];
                }
                
                if(($store['status']) || $refer_status){
                    if($store_slug){
                        $share_url = base_url($store_slug);
                    } else {
                        $share_url = base_url('store/' . base64_encode($id));
                    }

                    if($invitationlinkid == 1){
                        
                        $share_url=$share_url.'/?id='.(int)$id;
                    }
                    if($invitationlinkid == 0){
                        $share_url=$share_url;
                    }
                    $data['affiliate_store_url'] = $share_url;

                    if(!empty($store_slug_url)){
                        $store_page_url = base_url('store/' .$store_slug_url.'/'.base64_encode($id));
                        if($invitationlinkid == 1){
                        
                        $store_page_url=$store_page_url.'/?id='.(int)$id;
                        }
                        if($invitationlinkid == 0){
                            $store_page_url=$store_page_url;
                        }
                        $data['store_page_url'] = $store_page_url;
                    }
                }

                $market_vendor = $this->Product_model->getSettings('market_vendor');
                $marketvendorpanelmode = $market_vendor['marketvendorpanelmode'] ?? 0;

                $referlevelSettings = $this->Product_model->getSettings('referlevel');
               
                $data['refer_status'] = reh_fetchReffererStatus($referlevelSettings, $id);

                $register_slug = $this->db->query("SELECT slug FROM slugs WHERE user_id = '".(int)$id."' AND type = 'register'")->row()->slug;

                if($data['refer_status'] && allowMarketVendorPanelSections($marketvendorpanelmode, $verify_data['userdata']['is_vendor'])){ 
                    if($register_slug){
                        $unique_reseller_link = base_url($register_slug);
                    } else {
                        $unique_reseller_link = base_url('register/' . base64_encode($id));
                    }
                    if($invitationlinkid == 1){
                        
                    $unique_reseller_link=$unique_reseller_link.'/?id='.(int)$id;
                    }
                    if($invitationlinkid == 0){
                        $unique_reseller_link=$unique_reseller_link;
                    }
                    
                    $data['unique_reseller_link'] = $unique_reseller_link;
                }
     

                if(in_array('top_affiliate', $requestKeys)) {
                    $data['top_affiliate'] = $this->Product_model->getPopulerUsers(["limit" => 10]);
                }

                if(in_array('plan_details', $requestKeys)) {
                    $data['isMembershipAccess'] = $this->Product_model->isMembershipAccess();

                    if($plan_id == -1){
                        $data['is_lifetime_plan'] = 1;
                    }
                    else if($plan_id != -1) 
                    {
                        $where_plan = array('user_id'=>$id);
                        $user_plan = $this->Common_model->get_data_row('membership_user', $where_plan, 'total_day,expire_at,started_at,status_id,is_active,is_lifetime', 'id');
                        $data['user_plan'] = $user_plan;
                    }
                }

                if(in_array('totals_count', $requestKeys)) {
                    $data['user_totals'] = $this->Total_model->getUserTotals((int)$id);
                    $data['refer_total'] = $this->Product_model->getReferalTotals($id);
                    $data['user_totals']['click_action_commission'] = c_format($data['user_totals']['click_action_commission']);
                    $data['user_totals']['click_external_commission'] = c_format($data['user_totals']['click_external_commission']);


                    $hcurrency = $this->Product_model->getSettings('site', 'hide_currency_from');
                    $fun_c_format = (isset($hcurrency['hide_currency_from']) && str_contains($hcurrency['hide_currency_from'], 'user')) ? 'c_format_nosym' : 'c_format';

                    $data['user_totals']['user_balance'] = $fun_c_format($data['user_totals']['user_balance']);
                    $data['user_totals']['total_clicks_commission'] = $fun_c_format($data['user_totals']['total_clicks_commission']);

                    $data['user_totals_week'] = c_format($this->Total_model->getUserBalance((int)$id, ['week' => 1]));
                    $data['user_totals_month'] = c_format($this->Total_model->getUserBalance((int)$id, ['month' => 1]));
                    $data['user_totals_year'] = c_format($this->Total_model->getUserBalance((int)$id, ['year' => 1]));
                }

                if(in_array('notifications', $requestKeys)) {
                    $data['notifications'] = $this->user->getAllNotification($userdetails['id']);
                }

                if (in_array('chart_data', $requestKeys)) {
                    $data['chart'] = $this->Total_model->chartUser((int)$id, [
                        'year' => date('Y'),
                        'group' => 'month'
                    ]);
                    echo json_encode($data);die;
                }

                if (in_array('market_tools', $requestKeys)) {
                    $data['market_tools'] = $this->generate_market_tools($id, $verify_data['userdata'], [], 15);
                }

                $data = array_merge($data, get_currency_settings());

                $response = array(
                    'status' => TRUE,
                    'message' => 'dahsboard data get successfully',
                    'data' => $data
                );

                $this->response($response, REST_Controller::HTTP_OK);
            }
        }

        public function my_affiliate_links_post()
        {
            $headers = $this->input->request_headers();
            $verify_data = verify_request();
            if(isset($verify_data['status']) && $verify_data['status'] == 401) {
                $response = array(
                    'status' => 401,
                    'message' => 'Unauthorized Access!',
                );

                $this->response($response, 401); 
            } else {
                $id = $verify_data['userdata']['id'];

                $type = $verify_data['userdata']['type'];

                $requestFilter = array(
                    'req_category_id' => $this->input->post('category_id'),
                    'req_market_category_id' => $this->input->post('market_category_id'),
                    'req_ads_name' => $this->input->post('ads_name'),
                    'req_check_vendor' => $this->input->post('check_vendor')
                );

                $json = $this->generate_market_tools($id, $verify_data['userdata'], $requestFilter);
                
                $response = array(
                    'status' => TRUE,
                    'message' => 'my affiliate links get successfully',
                    'data' => $json
                );

                $this->response($response, REST_Controller::HTTP_OK);
            }
        }

        /**
         * GET /user/get_referral_info
         * Returns the authenticated user's referral code, shareable links, and campaign links.
         * Designed for third-party / mobile app integrations.
         */
        public function get_referral_info_get()
        {
            $verify_data = verify_request();
            if (isset($verify_data['status']) && $verify_data['status'] == 401) {
                $this->response(['status' => 401, 'message' => 'Unauthorized Access!'], 401);
                return;
            }

            $id = $verify_data['userdata']['id'];
            $userdetails = $verify_data['userdata'];

            $referlevelSettings = $this->Product_model->getSettings('referlevel');
            $refer_status = reh_fetchReffererStatus($referlevelSettings, $id);

            $market_vendor = $this->Product_model->getSettings('market_vendor');
            $marketvendorpanelmode = $market_vendor['marketvendorpanelmode'] ?? 0;

            $userdashboard_settings = $this->Common_model->getUserDashboardSettings();
            $invitationlinkid = 0;
            if (isset($userdashboard_settings, $userdashboard_settings['invitation_link_id'])) {
                $invitationlinkid = $userdashboard_settings['invitation_link_id']['setting_value'];
            }

            $result = [
                'user_id'       => (int) $id,
                'referral_code' => base64_encode($id),
                'refer_status'  => $refer_status,
            ];

            // Registration link
            if ($refer_status && allowMarketVendorPanelSections($marketvendorpanelmode, $userdetails['is_vendor'])) {
                $register_slug_row = $this->db->query("SELECT slug FROM slugs WHERE user_id = ? AND type = 'register'", [$id])->row();
                if ($register_slug_row) {
                    $result['registration_link'] = base_url($register_slug_row->slug);
                } else {
                    $result['registration_link'] = base_url('register/' . base64_encode($id));
                }
                if ($invitationlinkid == 1) {
                    $result['registration_link'] .= '/?id=' . (int) $id;
                }
            }

            // Store link
            $store = $this->Product_model->getSettings('store');
            if ($store['status'] || $refer_status) {
                $store_slug_row = $this->db->query("SELECT slug FROM slugs WHERE user_id = ? AND type = 'store'", [$id])->row();
                if ($store_slug_row) {
                    $result['store_link'] = base_url($store_slug_row->slug);
                } else {
                    $result['store_link'] = base_url('store/' . base64_encode($id));
                }
                if ($invitationlinkid == 1) {
                    $result['store_link'] .= '/?id=' . (int) $id;
                }
            }

            // Campaign / market tool links
            $campaigns = $this->db->query(
                "SELECT it.id as campaign_id, it.ads_name, it.target_link, it.s2s_enabled, it.s2s_direct_mode,
                        ita.id as ads_id
                 FROM integration_tools it
                 JOIN integration_tools_ads ita ON ita.tools_id = it.id
                 WHERE it.status = 1
                 ORDER BY it.id DESC"
            )->result_array();

            $campaign_links = [];
            foreach ($campaigns as $c) {
                $encrypted = _encrypt_decrypt($id . '-' . $c['ads_id']);
                $link = base_url('ref/' . $encrypted);
                $campaign_links[] = [
                    'campaign_id'     => (int) $c['campaign_id'],
                    'name'            => $c['ads_name'],
                    'link'            => $link,
                    's2s_enabled'     => (bool) $c['s2s_enabled'],
                    's2s_direct_mode' => (bool) (isset($c['s2s_direct_mode']) ? $c['s2s_direct_mode'] : false),
                ];
            }
            $result['campaign_links'] = $campaign_links;

            $this->response([
                'status'  => true,
                'message' => 'Referral info fetched successfully',
                'data'    => $result,
            ], REST_Controller::HTTP_OK);
        }

        private function generate_market_tools($id, $userdetails, $req_fileter = array(), $limited = false) {

            if($limited !== false) {
                $remain = $limited;
            }

            $market_tools = [];
            
            $market_tools['userdetails'] = $userdetails;

            $market_tools['form_setting'] = $market_tools['form_default_commission'] = $this->Product_model->getSettings('formsetting');

            $market_tools['pro_setting'] = $market_tools['default_commition'] = $this->Product_model->getSettings('productsetting');
            $market_vendor = $this->Product_model->getSettings('market_vendor');

            $restricted_vendors = $this->get_restricted_vendors();

            $filter = [
                'user_id'          => $id,
                'restrict'         => $id,
                'redirectLocation' => 1,
                'status'           => 1,
                'restrict_vendors' => $restricted_vendors,
                'not_show_my'      => $id,
                'userdetails' => $userdetails
            ];
         

            if($req_fileter['check_vendor'] == 'true'){
                unset($filter['not_show_my']);
            }

            if($limited !== false) {
                $filter['limit'] = ceil($remain / 3);
                $filter['start'] = 0;
            }
             //new code... 
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
            $filtertools=$filter;
            if (isset($post['vendor_id'])) 
            {
                $filtertools['vendor_id'] = $post['vendor_id'];
            }
            
            $tools = $this->IntegrationModel->getProgramTools($filtertools);
         
            $filter = [];
            
            if($limited !== false) {                
                $remain = $remain - count($tools);
                $filter = array(
                    'limit' => ceil($remain / 2),
                    'start' => 0,
                );
            }

            if (isset($req_fileter['category_id']) && !empty($req_fileter['category_id'])) {
                $filter['category_id'] = $req_fileter['category_id'];
            }

            if (isset($req_fileter['ads_name']) && !empty($req_fileter['ads_name'])) {
                $filter['ads_name'] = $req_fileter['ads_name'];
            }
                   
            $forms = $this->Form_model->getForms($id, $filter);


            $filter = array(
                'product_status' => 1,
                'not_show_my' => $id
            );
            
            if($req_fileter['check_vendor'] == 'true'){
                unset($filter['not_show_my']);
            }

            if($limited !== false) {                
                $remain = $remain - count($forms);
                $filter['limit'] = $remain;
                $filter['start'] = 0;
            }

            if (isset($req_fileter['market_category_id']) && !empty($req_fileter['market_category_id'])) {
                $filter['category_id'] = $req_fileter['market_category_id'];
            }
                    
            $store_setting = $this->Product_model->getSettings('store');

            if(is_array($store_setting) && $store_setting['status'])
            { 
                if($store_setting['store_mode']=="sales")
                 $filter['is_campaign_product'] = 1;

                $products = $this->Product_model->getAllProduct($id, $type, $filter);
                foreach ($products as $key => $value) {
                    $slug_query = $this->db->query("SELECT slug FROM slugs WHERE type = 'product' AND related_id = '".(int)$value['product_id']."' AND user_id = '".(int)$id."'")->row();
                    $products[$key]['slug'] = $slug_query ? $slug_query->slug : '';
                    $products[$key]['is_product'] = 1;
                }
            } else {
                $products = [];
            }

            foreach ($forms as $key => $value) {
                $slug_query = $this->db->query("SELECT slug FROM slugs WHERE type = 'form' AND related_id = '".(int)$value['form_id']."' AND user_id = '".(int)$id."'")->row();
                $forms[$key]['slug'] = $slug_query ? $slug_query->slug : '';
                $forms[$key]['coupon_name']  = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
                $forms[$key]['public_page']  = base_url('form/'.$value['seo'].'/'.base64_encode($id));
                $forms[$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id'],$id);
                if($value['coupon']){
                    $forms[$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
                }
                $forms[$key]['seo'] = str_replace('_', ' ', $value['seo']);
                $forms[$key]['is_form'] = 1;
                $forms[$key]['product_created_date'] = $value['created_at'];
            }
            
            if(is_array($store_setting) && $store_setting['status'] && $store_setting['store_mode']=="sales"){

                $data_list = array_merge($products,$tools);   
                } 
             else{

                $data_list = array_merge($products,$forms,$tools);
            }

            usort($data_list,function($a, $b){
                $ad = strtotime($a['product_created_date']);
                $bd = strtotime($b['product_created_date']);
                return ($ad-$bd);
            });

            $market_tools['data_list'] = array_reverse($data_list);


            $market_tools['Product_model'] = $this->Product_model;

            return AffiliateScript::usersMarketTools($market_tools);
        }

        public function change_password_post()
        {
            $headers = $this->input->request_headers();
            $verify_data = verify_request();
            if(isset($verify_data['status']) && $verify_data['status'] == 401) {
                $response = array(
                    'status' => 401,
                    'message' => 'Unauthorized Access!',
                );

                $this->response($response, 401); 
            }
            else
            {   
                $this->form_validation->set_rules('old_pass', 'old_pass', 'required|trim',
                array('required'      => 'Oops ! old pass is required.'
                ));

                $this->form_validation->set_rules('password', 'password', 'required|trim',
                array('required'      => 'Oops ! password is required.'
                ));

                $this->form_validation->set_rules('conf_password', 'conf_password', 'required|trim|matches[password]',
                array('required'      => 'Oops ! conf password is required.'
                ));

                $this->form_validation->set_error_delimiters('', '');
                if($this->form_validation->run()== false)
                {
                    if(!empty(form_error('old_pass')))$errors['old_pass'] =form_error('old_pass');
                    if(!empty(form_error('password')))$errors['password'] =form_error('password');
                    if(!empty(form_error('conf_password')))$errors['conf_password'] =form_error('conf_password');

                    $response['message'] = "Please required field";
                    $response['errors'] = $errors;

                    $this->response($response, 422);
                }
                else
                {
                    extract($_POST);

                    $id = $verify_data['userdata']['id'];

                    $admin = $this->db->from('users')->where('id',$id)->get()->row_array();
                    if($admin['password'] == sha1($old_pass))
                    {
                        $res = array('password'=>sha1($password));
                        $this->db->where('id',$id);
                        $update = $this->db->update('users',$res);

                        if($update)
                        {
                            $response = array(
                                'status' => TRUE,
                                'message' => 'password change successfully',
                            );
                        }
                        else
                        {
                            $response = array(
                                'status' => FALSE,
                                'message' => 'password change failed',
                            );
                        }
                    }
                    else
                    {
                        $response = array(
                            'status' => FALSE,
                            'message' => 'enter password not valid',
                        );
                    }
                    
                    $this->response($response, REST_Controller::HTTP_OK);
                }
            }
        }


        public function get_my_profile_details_get()
        {

            // Get the request headers
            $headers = $this->input->request_headers();
            
            // Log the request headers to check if the Flutter app is sending the request correctly
            error_log('Request Headers: ' . print_r($headers, true));
            
            // Log the client's IP address to check if it's from the Flutter app
            $client_ip = $this->input->ip_address();
            error_log('Client IP: ' . $client_ip);
           
            $verify_data = verify_request();

            // Debug the verification result
            error_log('verify_data: ' . print_r($verify_data, true)); 

            if (isset($verify_data['status']) && $verify_data['status'] == 401) {
                $response = array(
                    'status' => 401,
                    'message' => 'Unauthorized Access!',
                );
                $this->response($response, 401); 
            } else {
                $id = $verify_data['userdata']['id'];
                $user_details = (array)$this->user->get($id);

                $country_code = '';
                $country_flag = '';
                $country_name = '';
                $country_id = !empty($user_details['ucountry']) ? $user_details['ucountry'] : (!empty($user_details['Country']) ? $user_details['Country'] : '');
                
                if(!empty($country_id)){
                    $country_data = $this->db->query("SELECT sortname, name FROM countries WHERE id = ?", array($country_id))->row();
                    if($country_data){
                        $country_code = $country_data->sortname;
                        $country_name = $country_data->name;
                        $country_flag = base_url('assets/template/images/flags/'. strtolower($country_code) .'.png');
                    }
                }

                $phone_val = !empty($user_details['phone']) ? $user_details['phone'] : (!empty($user_details['PhoneNumber']) ? $user_details['PhoneNumber'] : '');
                $city_val = !empty($user_details['ucity']) ? $user_details['ucity'] : (!empty($user_details['City']) ? $user_details['City'] : '');
                $zip_val = !empty($user_details['uzip']) ? $user_details['uzip'] : (!empty($user_details['Zip']) ? $user_details['Zip'] : '');

                $response = array(
                    'status' => TRUE,
                    'message' => 'user details get successfully',
                    'data' => [
                        'user_status' => AffiliateScript::usersProfileStatus(['userdetails'=>$user_details])['status'],
                        'firstname' => $user_details['firstname'],
                        'lastname' => $user_details['lastname'],
                        'email' => $user_details['email'],
                        'PhoneNumber' => $phone_val,
                        'is_vendor' => $user_details['is_vendor'],
                        'profile_image' =>  (!empty($user_details['avatar'])) ? base_url('assets/images/users/'.$user_details['avatar']) : base_url('assets/template/images/no-image.jpg'),
                        'country_id' => $country_id,
                        'country_code' => $country_code,
                        'country_name' => $country_name,
                        'country_flag' => $country_flag,
                        'city' => $city_val,
                        'pincode' => $zip_val
                    ]
                );

                $this->response($response, REST_Controller::HTTP_OK);
            }
        }


        public function update_my_profile_post()
        {
            $headers = $this->input->request_headers();
            $verify_data = verify_request();
            if(isset($verify_data['status']) && $verify_data['status'] == 401) {
                $response = array(
                    'status' => 401,
                    'message' => 'Unauthorized Access!',
                );
                $this->response($response, 401); 
            }
            else
            {
                $id = $verify_data['userdata']['id'];
                $password = $this->input->post('password');

                // Set validation rules
                $this->form_validation->set_rules('firstname', 'firstname', 'required|trim',
                    array('required' => 'Oops ! firstname is required.')
                );
                $this->form_validation->set_rules('lastname', 'lastname', 'required|trim',
                    array('required' => 'Oops ! lastname is required.')
                );
                $this->form_validation->set_rules('username', 'username', 'required|trim',
                    array('required' => 'Oops ! username is required.')
                );
                $this->form_validation->set_rules('email', 'email', 'required|valid_email|xss_clean',
                    array('required' => 'Oops ! email is required.')
                );
                $this->form_validation->set_rules('country_id', 'country_id', 'required|trim',
                    array('required' => 'Oops ! country id is required.')
                );
                // New validation rule for phone number
                $this->form_validation->set_rules('phone', 'phone number', 'required|trim',
                    array('required' => 'Oops ! phone number is required.')
                );

                if($password != ''){
                    $this->form_validation->set_rules('password', 'Password', 'required|trim',
                        array('required' => '%s is required')
                    );
                    $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim',
                        array('required' => '%s is required')
                    );
                    $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]',
                        array('required' => '%s is required')
                    );
                }

                $this->form_validation->set_error_delimiters('', '');
                if($this->form_validation->run() == false)
                {
                    if(!empty(form_error('firstname')))
                        $errors['firstname'] = form_error('firstname');
                    if(!empty(form_error('lastname')))
                        $errors['lastname'] = form_error('lastname');
                    if(!empty(form_error('username')))
                        $errors['username'] = form_error('username');
                    if(!empty(form_error('email')))
                        $errors['email'] = form_error('email');
                    if(!empty(form_error('country_id')))
                        $errors['country_id'] = form_error('country_id');
                    if(!empty(form_error('phone')))
                        $errors['phone'] = form_error('phone');
                    if($password != ''){
                        if(!empty(form_error('password')))
                            $errors['password'] = form_error('password');
                        if(!empty(form_error('cpassword')))
                            $errors['cpassword'] = form_error('cpassword');
                    }

                    $response['message'] = "Please required field";
                    $response['errors'] = $errors;
                    $this->response($response, 422);
                }
                else
                {
                    $id = $verify_data['userdata']['id'];
                    $email = $this->input->post('email');
                    $username = $this->input->post('username');

                    $checkmail = $this->Product_model->checkmail($email, $id);
                    $checkuser = $this->Product_model->checkuser($username, $id);
                
                    if(!empty($checkmail)){
                        $response = array(
                            'status' => FALSE,
                            'message' => 'email already exist'
                        );
                        $this->response($response, REST_Controller::HTTP_OK);
                    }

                    if(!empty($checkuser)){
                        $response = array(
                            'status' => FALSE,
                            'message' => 'username already exist'
                        );
                        $this->response($response, REST_Controller::HTTP_OK);
                    }

                    $phone_input = $this->input->post('phone', true);
                    $country_input = $this->input->post('country_id');

                    $userArray = array(
                        'firstname'       => $this->input->post('firstname'),
                        'lastname'        => $this->input->post('lastname'),
                        'email'           => $this->input->post('email'),
                        'username'        => $this->input->post('username'),
                        'phone'           => $phone_input,
                        'PhoneNumber'     => $phone_input,
                        'ucountry'        => $country_input,
                        'Country'         => $country_input
                    );

                    if($this->input->post('city') !== null){
                        $city_input = $this->input->post('city', true);
                        $userArray['ucity'] = $city_input;
                        $userArray['City'] = $city_input;
                    }
                    if($this->input->post('pincode') !== null){
                        $zip_input = $this->input->post('pincode', true);
                        $userArray['uzip'] = $zip_input;
                        $userArray['Zip'] = $zip_input;
                    }

                    if($password != ''){
                        $userArray['password'] = sha1($password);
                    }

                    if(!empty($_FILES['avatar']['name'])){
                        $upload_response = $this->upload_photo('avatar','assets/images/users');
                        if($upload_response['success']){
                            $userArray['avatar'] = $upload_response['upload_data']['file_name'];
                        }
                    }

                    $this->user->update_user($id, $userArray);
                    $user_details = $this->db->query("SELECT * FROM users WHERE id = " . (int)$id)->row_array();

                    $country_code = '';
                    $country_flag = '';
                    $country_name = '';
                    $country_id = !empty($user_details['ucountry']) ? $user_details['ucountry'] : (!empty($user_details['Country']) ? $user_details['Country'] : '');
                    
                    if(!empty($country_id)){
                        $country_data = $this->db->query("SELECT sortname, name FROM countries WHERE id = ?", array($country_id))->row();
                        if($country_data){
                            $country_code = $country_data->sortname;
                            $country_name = $country_data->name;
                            $country_flag = base_url('assets/template/images/flags/'. strtolower($country_code) .'.png');
                        }
                    }

                    $phone_val = !empty($user_details['phone']) ? $user_details['phone'] : (!empty($user_details['PhoneNumber']) ? $user_details['PhoneNumber'] : '');
                    $city_val = !empty($user_details['ucity']) ? $user_details['ucity'] : (!empty($user_details['City']) ? $user_details['City'] : '');
                    $zip_val = !empty($user_details['uzip']) ? $user_details['uzip'] : (!empty($user_details['Zip']) ? $user_details['Zip'] : '');

                    $response = array(
                        'status' => TRUE,
                        'message' => 'user details update successfully',
                        'data' => [
                            'user_status' => AffiliateScript::usersProfileStatus(['userdetails'=>$user_details])['status'],
                            'firstname' => $user_details['firstname'],
                            'lastname' => $user_details['lastname'],
                            'email' => $user_details['email'],
                            'PhoneNumber' => $phone_val,
                            'is_vendor' => $user_details['is_vendor'],
                            'profile_image' =>  (!empty($user_details['avatar'])) ? base_url('assets/images/users/'.$user_details['avatar']) : base_url('assets/template/images/no-image.jpg'),
                            'country_id' => $country_id,
                            'country_code' => $country_code,
                            'country_name' => $country_name,
                            'country_flag' => $country_flag,
                            'city' => $city_val,
                            'pincode' => $zip_val
                        ]
                    );
                    
                    $this->response($response, REST_Controller::HTTP_OK);
                }
            }
        }

        public function upload_photo($fieldname,$path) {
        
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'png|gif|jpeg|jpg';
        
            $this->load->helper('string');
            $config['file_name']  = random_string('alnum', 32);
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
        

            if (!$this->upload->do_upload($fieldname)) {
                $response = array('success' => FALSE, 'message' => $this->upload->display_errors());
                $this->response($response, REST_Controller::HTTP_OK);
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

    }
?>