<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'hooks/Affiliate_Hook.php';

class Integration extends MY_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		$this->load->model('Report_model');
		$this->load->model('IntegrationModel');
		$this->load->model('Tutorial_model');
		$this->load->model('Withdrawal_payment_model');
		___construct(1);

		$this->checkSessionTimeout();
		if (function_exists('check_admin_permission')) {
			check_admin_permission();
		}
	}

	public function userdetails(){ return $this->session->userdata('administrator'); }
	public function userlogins(){ return $this->session->userdata('user'); }

	public function script() {
        $this->integration('script');
    }
    
    public function show_affiliate_id() {
        $data = $this->input->get(null);
        $data['script'] = "general_integration";
        header('Content-Type: application/javascript');
    
        $data['aff_external_cookies_duration'] = $this->get_aff_external_cookies_duration();
        $this->load->view('integration/show_id', $data);
    }

    public function general_integration() {
        $data['script'] = "general_integration";
        header('Content-Type: application/javascript');
    
        $aff_external_cookies_duration = $this->get_aff_external_cookies_duration();
    
        echo "var aff_external_cookies_duration = " . $aff_external_cookies_duration . ";\n";
    
        $data['aff_external_cookies_duration'] = $aff_external_cookies_duration;
        $this->load->view('integration/general_integration', $data);
    }
    
    public function shopify() {
        $this->integration('shopify');
    }
    
    public function xcart() {
        $this->integration('xcart');
    }
    
    public function zencart() {
        $this->integration('zencart');
    }
    
    public function paypal() {
        $this->integration('paypal');
    }
    
    public function bigcommerce() {
        $this->integration('bigcommerce');
    }
    
    public function oscommerce() {
        $this->integration('oscommerce');
    }
    
    private function integration($script) {
        $data['script'] = $script;
        header('Content-Type: application/javascript');
    
        $data['aff_external_cookies_duration'] = $this->get_aff_external_cookies_duration();
        $this->load->view('integration/general_integration', $data);
    }
    
    private function get_aff_external_cookies_duration() {
        if (isset($_SESSION['aff_external_cookies_duration'])) {
            return $_SESSION['aff_external_cookies_duration'];
        } else {
            try {
                return $this->IntegrationModel->getDefaultCookiesDuration();
            } catch (Exception $e) {
                // Log the error and set a default value
                // log_message('Error fetching default cookies duration: ' . $e->getMessage());
                return 30;
            }
        }
    }


	public function wp_status() {
	    header('Content-Type: application/json');
	    header('Access-Control-Allow-Origin: *');
	    
	    echo json_encode([
	        'status' => 'connected',
	        'message' => 'WordPress integration operational',
	        'platform' => 'wordpress',
	        'timestamp' => date('Y-m-d H:i:s'),
	        'system_info' => ['version' => '2.0.0']
	    ]);
	}

	//This function protects the system for generating actions/clicks from vpn&proxy servers
	public function checkProxyStatus($ip) {
	    // Fetch settings from the database
	    $settings = $this->Product_model->getSettings('proxy_services');
	    
	    // Fetch settings for Service 1
	    $service1Enabled = isset($settings['service1_enabled']) ? $settings['service1_enabled'] : 0;
	    $apiKeyService1 = isset($settings['service1_api_key']) ? $settings['service1_api_key'] : '';
	    $urlService1 = isset($settings['service1_url']) ? $settings['service1_url'] : 'https://api.isproxyip.com/v1/check.php';
	    
	    // Fetch settings for Service 2
	    $service2Enabled = isset($settings['service2_enabled']) ? $settings['service2_enabled'] : 0;
	    $apiKeyService2 = isset($settings['service2_api_key']) ? $settings['service2_api_key'] : '';
	    $urlService2 = isset($settings['service2_url']) ? $settings['service2_url'] : 'https://vpnapi.io/api';

	    $proxyDetected = false;

	    // Check Service 1 if enabled
	    if ($service1Enabled) {
	        $urlService1 = "{$urlService1}?key={$apiKeyService1}&ip={$ip}&format=json";
	        if ($this->proxyCheckHelper($urlService1, 'service1')) {
	            error_log("Proxy or VPN detected by Service 1 for IP: {$ip}");
	            $proxyDetected = true;
	        }
	    }

	    // Check Service 2 if enabled
	    if ($service2Enabled) {
	        $urlService2 = "{$urlService2}?ip={$ip}&key={$apiKeyService2}";
	        if ($this->proxyCheckHelper($urlService2, 'service2')) {
	            error_log("Proxy or VPN detected by Service 2 for IP: {$ip}");
	            $proxyDetected = true;
	        }
	    }

	    return $proxyDetected;
	}

	private function proxyCheckHelper($url, $serviceType) {
	    $response = file_get_contents($url);
	    if ($response === false) {
	        error_log("Error contacting the API service for $serviceType.");
	        return false;
	    }

	    $data = json_decode($response, true);

	    if ($serviceType === 'service1' && isset($data['status']) && $data['status'] == 'success' && $data['proxy'] == 1) {
	        return true;
	    } elseif ($serviceType === 'service2' and isset($data['security']) && ($data['security']['proxy'] || $data['security']['vpn'] || $data['security']['tor'])) {
	        return true;
	    }

	    return false;
	}

	private function which_country() {
	    if (extension_loaded('curl')) {
	        $this->load->helper('geolocation');
	        $geolocation = new Geolocation_helper();
	        
	        $ip = $_SERVER["REMOTE_ADDR"];
	        $result = $geolocation->ip_info($ip, 'location');
	        
	        if (!$result) {
	            return 'Warning: IP Geolocation API not working.';
	        }

	        // Return the result in the expected format
	        return [
	            'ip' => $ip,
	            'country' => $result['country'],
	            'country_code' => $result['country_code'],
	            'city' => $result['city'],
	            'region' => $result['state'],
	            'region_code' => $result['country_code'],
	            'latitude' => isset($result['latitude']) ? $result['latitude'] : '',
	            'longitude' => isset($result['longitude']) ? $result['longitude'] : '',
	            'currency_code' => '',
	            'currency_symbol' => ''
	        ];
	    } else {
	        return 'Warning: cURL extension not loaded.';
	    }
	}


	public function addClick(){

		// [v15] Fraud score accumulator — signals populate before any kill fires
		$score_signals = [];

		// Get the user's IP address at the start
	    $user_ip = $this->input->ip_address();

		//reject the campaign click commission if MarketTools is disable
	    $market_tools_status = $this->Product_model->getSettings('market_tools', 'status');
	    
	    if ($market_tools_status === null || !isset($market_tools_status['status'])) {
	        $market_tools_status['status'] = 1;
	    }
	    
	    if (!$market_tools_status['status']) {
	        echo '<script>console.log("MarketTools is disabled. Click aborted.");</script>';
	        return;
	    }
	    //reject the campaign click commission if MarketTools is disable

		$content = file_get_contents("php://input");

		if($content){
		    parse_str($content, $data);
		}else{
		    $data = $this->input->get(null);
		}

		if(isset($data['af_id'])) {

			list($user_id,$ads_id) = explode("-", _encrypt_decrypt(parse_affiliate_id($data['af_id']),'decrypt'));
		}

		$restricted_vendors= $this->get_restricted_vendors();
		/* Marketing tools : Other Affiliates Selling My Products? */
			if($user_id>0)
			{
				$userrefidarray = $this->db->query('SELECT refid FROM users WHERE id='.$user_id)->row_array();

				if(is_array($userrefidarray))
					$userrefidcommon=$userrefidarray["refid"];
				else
					$userrefidcommon=0;	

				$escapevendorscommon = $this->db->query('SELECT user_id,vendor_shares_sales_status FROM vendor_setting WHERE vendor_shares_sales_status>0')->result_array();
	  		 
	  			$vendors = $this->db->query('SELECT id from users where is_vendor = 1')->result_array();

				$allowVendorscommon = [];
				foreach($escapevendorscommon as $esc) 
				{
					if($esc['vendor_shares_sales_status']==1)
						$allowVendorscommon[] = $esc['user_id'];
					else if($esc['vendor_shares_sales_status']==2 && $esc['user_id']==$userrefidcommon)
						$allowVendorscommon[] = $esc['user_id'];

				}
	 
				$escapeUserscommon = [];
				foreach($vendors as $v) {
					if(!in_array($v['id'], $allowVendorscommon))
						$escapeUserscommon[] = $v['id'];
				} 

		 		$restricted_vendors=array_unique(array_merge($restricted_vendors, $escapeUserscommon));
			}
		/* Marketing tools : Other Affiliates Selling My Products? */

		$data['restricted_vendors'] = $restricted_vendors;

		$Affiliate_Hook = new Affiliate_Hook;

		// [v15] Load geo + campaign early — needed for both scoring and country check
	    $geoData        = $this->which_country();
	    $integration_tool = $this->IntegrationModel->getIntegrationTool($ads_id ?? 0);

		if (!empty($integration_tool) && isset($user_id) && (int) $user_id > 0) {
			if (!$this->Product_model->user_can_promote_market_campaign_for_user_id((int) $user_id, $integration_tool)) {
				market_promotion_http_block();
			}
		}

		// [v15] GATHER SIGNALS (non-blocking versions — originals untouched)
		if ($this->checkProxyStatus($user_ip))                                  $score_signals['proxy']       = 40;
		if ($this->_market_FraudDetection_signal($data))                        $score_signals['fingerprint'] = 40;
		if ($Affiliate_Hook->is_localhost()['shouldBlock'])                     $score_signals['localhost']   = 50;
		if ($this->_market_ClickFrequencyExceeded_signal($user_id ?? 0, $data)) $score_signals['rate']        = 25;

		// [v15] COMPUTE SCORE & ZONE
		$score_result = $this->_score_event($score_signals, $user_id ?? 0);
		$fs_score     = $score_result['score'];
		$fs_zone      = $score_result['zone'];

		// [v15] ENFORCE KILLS — Red zone fires original blocking logic; Green/Amber passes through
		if ($fs_zone === 'red') {
			if (!empty($score_signals['proxy']))       exit('Access denied. Proxy use detected.');
			if (!empty($score_signals['fingerprint'])) $this->market_FraudDetection($data); // dies internally
			if (!empty($score_signals['localhost']))   exit('Access from localhost is not allowed as per system settings.');
			if (!empty($score_signals['rate']))        $this->market_ClickFrequencyExceeded($user_id ?? 0, $data); // exits internally
		}

		// Business-rule kills — always enforced regardless of score
		if (in_array($user_id ?? 0, $data['restricted_vendors'])) {
		    exit('Integration blocked by admin!');
		}

		//Campaign per country start
		$postCountries = (isset($integration_tool['country_sortname']) && $integration_tool['country_sortname'] != "")? explode(",",$integration_tool['country_sortname']):'';
		if(is_array($postCountries) && !empty($postCountries)){

			if (in_array($geoData['country_code'], $postCountries)) {
				$this->IntegrationModel->addClick($data);
			}
			else{
				exit('Campaign ' . $ads_id . ' is blocked to ' . $geoData['country_code'] . ' country');
			}
		}else{
			$this->IntegrationModel->addClick($data);
		}
	    //Campaign per country end

		// [v15] Persist fraud score on the click record
		$click_id = $this->db->insert_id();
		if ($click_id && $fs_score > 0) {
			$this->db->query(
				'UPDATE integration_clicks_action SET fraud_score = ? WHERE id = ?',
				[(int)$fs_score, (int)$click_id]
			);
		}
	}

	/**
	 * Update time spent on page for the most recent click from this user/IP.
	 * Called automatically by AffTracker JS when user leaves the page.
	 */
	public function updateTimeSpent(){
		$content = file_get_contents("php://input");

		if($content){
			parse_str($content, $data);
		}else{
			$data = $this->input->get(null);
		}

		if(!isset($data['af_id']) || !$data['af_id']){
			die("noafid");
		}

		$time_spent = isset($data['time_spent']) ? (int)$data['time_spent'] : 0;
		$page_open_time = isset($data['page_open_time']) ? trim($data['page_open_time']) : '';
		$page_close_time = isset($data['page_close_time']) ? trim($data['page_close_time']) : '';

		// Validate time_spent is reasonable (between 1 second and 24 hours)
		if($time_spent < 1 || $time_spent > 86400){
			die("invalid_time");
		}

		// Validate and convert ISO datetime strings to MySQL format
		$open_ts = strtotime($page_open_time);
		$close_ts = strtotime($page_close_time);

		if(!$open_ts || !$close_ts){
			die("invalid_dates");
		}

		$page_open_time_mysql = date('Y-m-d H:i:s', $open_ts);
		$page_close_time_mysql = date('Y-m-d H:i:s', $close_ts);

		list($user_id, $ads_id) = explode("-", _encrypt_decrypt(parse_affiliate_id($data['af_id']), 'decrypt'));

		$ip = $this->input->ip_address();

		$domain_name = isset($data['base_url']) ? url_to_domain(base64_decode($data['base_url'])) : '';

		$this->IntegrationModel->updateTimeSpent($user_id, $ip, $domain_name, $page_open_time_mysql, $page_close_time_mysql, $time_spent);

		die("OK");
	}


public function addOrder() {
    // Reject the campaign order commission if MarketTools is disabled
    $market_tools_status = $this->Product_model->getSettings('market_tools', 'status');
    
    if ($market_tools_status === null || !isset($market_tools_status['status'])) {
        $market_tools_status['status'] = 1;
    }
    
    if (!$market_tools_status['status']) {
        echo '<script>console.log("MarketTools is disabled. Order aborted.");</script>';
        return;
    }

    // Get input data
    $content = file_get_contents("php://input");
    if ($content) {
        parse_str($content, $data);
    } else {
        $data = $this->input->get(null, true);
    }

    // Get restricted vendors
    $restricted_vendors = $this->get_restricted_vendors();
    $data['restricted_vendors'] = $restricted_vendors;

    // Marketing tools: Other Affiliates Selling My Products?
    if (isset($data['af_id'])) {
        list($user_id, $ads_id) = explode("-", $data['af_id']);
        $userrefidarray = $this->db->query('SELECT refid FROM users WHERE id = ?', [$user_id])->row_array();
        $userrefidcommon = is_array($userrefidarray) ? $userrefidarray["refid"] : 0;

        $vendors = $this->db->query('SELECT id FROM users WHERE is_vendor = 1')->result_array();
        $escapevendorscommon = $this->db->query('SELECT user_id, vendor_shares_sales_status FROM vendor_setting WHERE vendor_shares_sales_status > 0')->result_array();

        $allowVendorscommon = [];
        foreach ($escapevendorscommon as $esc) {
            if ($esc['vendor_shares_sales_status'] == 1 || ($esc['vendor_shares_sales_status'] == 2 && $esc['user_id'] == $userrefidcommon)) {
                $allowVendorscommon[] = $esc['user_id'];
            }
        }

        $escapeUserscommon = [];
        foreach ($vendors as $v) {
            if (!in_array($v['id'], $allowVendorscommon)) {
                $escapeUserscommon[] = $v['id'];
            }
        }

        $restricted_vendors = array_unique(array_merge($restricted_vendors, $escapeUserscommon));
        $data['restricted_vendors'] = $restricted_vendors;
    }


	// [v15] Fraud score accumulator for order
	$order_score_signals = [];

	$Affiliate_Hook = new Affiliate_Hook;

	// [v15] Load geo + campaign early — needed for scoring and country check
	$geoData          = $this->which_country();
	$integration_tool = $this->IntegrationModel->getIntegrationTool($ads_id ?? 0);

	if (!empty($integration_tool) && isset($user_id) && (int) $user_id > 0) {
		if (!$this->Product_model->user_can_promote_market_campaign_for_user_id((int) $user_id, $integration_tool)) {
			market_promotion_http_block();
		}
	}

	// [v15] GATHER SIGNALS (non-blocking)
	if ($this->_market_FraudDetection_signal($data))                                $order_score_signals['fingerprint'] = 40;
	if ($Affiliate_Hook->is_localhost()['shouldBlock'])                             $order_score_signals['localhost']   = 50;
	if ($this->_market_ClickFrequencyExceeded_signal($user_id ?? 0, $data))         $order_score_signals['rate']        = 25;
	if (isset($user_id) && in_array($user_id, $data['restricted_vendors']))         $order_score_signals['vendor_block']= 10;

	// [v15] COMPUTE SCORE & ZONE
	$order_score_result = $this->_score_event($order_score_signals, $user_id ?? 0);
	$order_fs_score     = $order_score_result['score'];
	$order_fs_zone      = $order_score_result['zone'];

	// [v15] ENFORCE KILLS — Red zone only for fraud signals
	if ($order_fs_zone === 'red') {
		if (!empty($order_score_signals['fingerprint'])) $this->market_FraudDetection($data); // dies internally
		if (!empty($order_score_signals['localhost']))   exit('Access from localhost is not allowed as per system settings.');
		if (!empty($order_score_signals['rate']))        $this->market_ClickFrequencyExceeded($user_id ?? 0, $data); // exits internally
	}

	// Business-rule kills — always enforced
    if (isset($user_id) && in_array($user_id, $data['restricted_vendors'])) {
        exit('Integration blocked by admin!');
    }

    // Campaign per country start
    $postCountries = (isset($integration_tool['country_sortname']) && $integration_tool['country_sortname'] != "") ? explode(",", $integration_tool['country_sortname']) : '';

    if (is_array($postCountries) && !empty($postCountries)) {
        if (in_array($geoData['country_code'], $postCountries)) {
            $this->processPostback($integration_tool, $data);
            $this->IntegrationModel->addOrder($data);
        } else {
            exit('Campaign ' . $ads_id . ' is blocked to ' . $geoData['country_code'] . ' country');
        }
    } else {
        $this->processPostback($integration_tool, $data);
        $postbackResponse = $this->processPostback($integration_tool, $data);
        $this->IntegrationModel->addOrder($data);
    }
    // Campaign per country end

	// [v15] Persist fraud score on the order record
	$order_insert_id = $this->db->insert_id();
	if ($order_insert_id && $order_fs_score > 0) {
		$this->db->query(
			'UPDATE integration_orders SET fraud_score = ? WHERE id = ?',
			[(int)$order_fs_score, (int)$order_insert_id]
		);
	}
}

// ─────────────────────────────────────────────────────────────────────────────
// v15 — AI Smart Fraud Scoring Engine (private helpers)
// Original detection methods above are NOT modified.
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Non-blocking mirror of market_FraudDetection().
 * Runs the same cross-browser / fingerprint DB query but returns a bool
 * instead of calling die(). The original method is completely untouched.
 */
private function _market_FraudDetection_signal($data) {
	$settings = $this->Product_model->getSettings('site');
	$block_cross = isset($settings['block_click_across_browser']) ? (int)$settings['block_click_across_browser'] : 0;
	if (!$block_cross) return false;

	if (!isset($data['af_id'])) return false;

	$logData = method_exists($this, 'prepareLogsUagentData') ? $this->prepareLogsUagentData() : [];
	$data    = array_merge($data, $logData);

	$parts = explode("-", _encrypt_decrypt(parse_affiliate_id($data['af_id']), 'decrypt'));
	if (count($parts) < 2) return false;
	list($user_id,) = $parts;

	$base_url = preg_replace("(^https?://)", "", base64_decode($data['base_url'] ?? ''));
	$link     = urldecode(base64_decode($data['current_page_url'] ?? ''));

	$offset = 0;
	do {
		$rows = $this->db->query(
			"SELECT ip, agent, browserName, browserVersion, osPlatform, osVersion
			 FROM integration_clicks_logs
			 WHERE user_id = ? AND base_url = ? AND link = ?
			 AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
			 LIMIT 1000 OFFSET {$offset}",
			[$user_id, $base_url, $link]
		)->result_array();

		foreach ($rows as $row) {
			if (isset($data['enableLocalhostProtection']) && $data['enableLocalhostProtection']
				&& in_array($row['ip'], ['::1', '127.0.0.1'])) {
				return true;
			}
			if ($row['ip'] !== ($data['ip'] ?? '') && $row['agent'] === ($data['agent'] ?? '')) {
				$match = true;
				foreach (['browserName','browserVersion','osPlatform','osVersion'] as $f) {
					if (isset($row[$f], $data[$f]) && $row[$f] !== $data[$f]) { $match = false; break; }
				}
				if ($match) return true;
			}
		}
		$offset += 1000;
	} while (!empty($rows));

	return false;
}

/**
 * Non-blocking mirror of market_ClickFrequencyExceeded().
 * Returns true if the rate limit would be triggered; false otherwise.
 * The original method is completely untouched.
 */
private function _market_ClickFrequencyExceeded_signal($user_id, $data) {
	if (empty($user_id)) return false;

	$keys = ['max_clicks','max_actions','frequency_unit_clicks','frequency_unit_actions',
	         'enable_action_control','enable_click_control'];
	$rows = $this->db->select('setting_key, setting_value')
	                  ->where_in('setting_key', $keys)
	                  ->get('setting')->result();
	$s = [];
	foreach ($rows as $r) $s[$r->setting_key] = $r->setting_value;

	$isAction = isset($data['actionCode']) && $data['actionCode'] !== '';
	if ($isAction  && ($s['enable_action_control'] ?? '1') !== '1') return false;
	if (!$isAction && ($s['enable_click_control']  ?? '1') !== '1') return false;

	$maxVal  = $isAction ? (int)($s['max_actions'] ?? 1) : (int)($s['max_clicks'] ?? 1);
	$freqKey = $isAction ? ($s['frequency_unit_actions'] ?? 'minute') : ($s['frequency_unit_clicks'] ?? 'minute');

	$now  = new DateTime();
	$past = (clone $now)->modify('-1 ' . $freqKey);

	$this->db->where('user_id', $user_id)
	         ->where('created_at >=', $past->format('Y-m-d H:i:s'))
	         ->where('created_at <=', $now->format('Y-m-d H:i:s'));
	if ($isAction) $this->db->where('action_code', $data['actionCode']);

	$count = $this->db->count_all_results('integration_clicks_action');
	return ($count >= $maxVal);
}

/**
 * Compute a 0-100 fraud score from the gathered signal array.
 * Applies account-age and purchase-history mitigations from the users table.
 *
 * @param  array $signals  Associative array: ['proxy'=>40, 'rate'=>25, ...]
 * @param  int   $user_id
 * @return array           ['score' => int, 'zone' => 'green'|'amber'|'red']
 */
private function _score_event($signals, $user_id) {
	$raw = 0;
	foreach ($signals as $pts) $raw += (int)$pts;

	// Mitigations — reward established, trusted affiliates
	if ($user_id > 0) {
		$user = $this->db->query(
			'SELECT created_at, product_total_sale FROM users WHERE id = ? LIMIT 1',
			[(int)$user_id]
		)->row_array();
		if ($user) {
			$age_days = (time() - strtotime($user['created_at'])) / 86400;
			if ($age_days        > 180) $raw -= 10; // account > 6 months
			if ((int)($user['product_total_sale'] ?? 0) >= 10) $raw -= 7;  // 10+ real sales
		}
	}

	$score = max(0, min(100, $raw));

	if      ($score <= 30) $zone = 'green';
	elseif  ($score <= 70) $zone = 'amber';
	else                   $zone = 'red';

	return ['score' => $score, 'zone' => $zone];
}

// ─────────────────────────────────────────────────────────────────────────────
// End v15 helpers
// ─────────────────────────────────────────────────────────────────────────────

/**
 * S2S Server-to-Server Conversion Endpoint.
 * Supports two modes:
 *   1. Classic: click_token from a prior browser click
 *   2. Direct:  affiliate_id + campaign_id (for mobile apps / cookieless environments)
 *
 * POST /integration/s2sConvert
 * Required: api_key, order_id, order_total, order_currency
 * Plus ONE of:
 *   - click_token            (classic mode)
 *   - affiliate_id + campaign_id  (direct mode — requires s2s_direct_mode enabled)
 * Optional: product_ids
 * Returns: JSON { status: "success"|"error"|"duplicate", message: "..." }
 */
public function s2sConvert() {

    $data = $this->_parse_s2s_input();

    // Validate always-required fields
    $alwaysRequired = ['api_key', 'order_id', 'order_total', 'order_currency'];
    foreach ($alwaysRequired as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            $this->_s2s_json_response(['status' => 'error', 'message' => "Missing required field: {$field}"]);
        }
    }

    $api_key        = trim($data['api_key']);
    $order_id       = trim($data['order_id']);
    $order_total    = (float) $data['order_total'];
    $order_currency = strtoupper(trim($data['order_currency']));
    $product_ids    = isset($data['product_ids']) ? trim($data['product_ids']) : '';

    $has_click_token  = isset($data['click_token']) && trim($data['click_token']) !== '';
    $has_direct_ids   = isset($data['affiliate_id']) && trim($data['affiliate_id']) !== ''
                     && isset($data['campaign_id'])  && trim($data['campaign_id'])  !== '';

    if (!$has_click_token && !$has_direct_ids) {
        $this->_s2s_json_response([
            'status'  => 'error',
            'message' => 'Provide either click_token (classic mode) or affiliate_id + campaign_id (direct mode).'
        ]);
    }

    // --- Resolve user_id, ads_id, domain_name depending on mode ---
    if ($has_click_token) {
        // Classic click_token flow
        $click_token = trim($data['click_token']);
        $clickRecord = $this->IntegrationModel->lookupClickToken($click_token);
        if (empty($clickRecord)) {
            $this->_s2s_json_response(['status' => 'error', 'message' => 'Invalid or expired click_token']);
        }
        $user_id     = $clickRecord['user_id'];
        $ads_id      = $clickRecord['ads_id'];
        $domain_name = $clickRecord['base_url'];
    } else {
        // Direct mode — affiliate_id + campaign_id
        $user_id     = (int) trim($data['affiliate_id']);
        $campaign_id = (int) trim($data['campaign_id']);

        $integration_tool_direct = $this->_load_and_validate_campaign_direct($campaign_id, $api_key);
        $ads_id      = $integration_tool_direct['_ads_id'];
        $domain_name = parse_url($integration_tool_direct['target_link'], PHP_URL_HOST) ?: $integration_tool_direct['target_link'];

        $userRow = $this->db->query("SELECT id FROM users WHERE type='user' AND id = ?", [$user_id])->row();
        if (empty($userRow)) {
            $this->_s2s_json_response(['status' => 'error', 'message' => 'Affiliate user not found']);
        }
    }

    // Load campaign (may already be loaded in direct mode, but needed for click_token mode)
    if (!isset($integration_tool_direct)) {
        $integration_tool = $this->IntegrationModel->getIntegrationTool($ads_id);
        if (empty($integration_tool)) {
            $this->_s2s_json_response(['status' => 'error', 'message' => 'Campaign not found for this click']);
        }
        if (empty($integration_tool['s2s_enabled'])) {
            $this->_s2s_json_response(['status' => 'error', 'message' => 'S2S tracking is not enabled for this campaign']);
        }
        if (empty($integration_tool['api_key']) || $integration_tool['api_key'] !== $api_key) {
            $this->_s2s_json_response(['status' => 'error', 'message' => 'Invalid API key']);
        }
    } else {
        $integration_tool = $integration_tool_direct;
    }

    // Check if MarketTools is enabled
    $market_tools_status = $this->Product_model->getSettings('market_tools', 'status');
    if ($market_tools_status === null || !isset($market_tools_status['status'])) {
        $market_tools_status['status'] = 1;
    }
    if (!$market_tools_status['status']) {
        $this->_s2s_json_response(['status' => 'error', 'message' => 'MarketTools is disabled']);
    }

    // Cross-source deduplication
    $orderAlready = $this->db->query(
        "SELECT id FROM integration_orders WHERE order_id = ? AND base_url LIKE ?",
        [$order_id, '%' . $domain_name . '%']
    )->num_rows();

    if ($orderAlready > 0) {
        $this->_s2s_json_response(['status' => 'duplicate', 'message' => 'Order already recorded']);
    }

    $af_id = _encrypt_decrypt($user_id . "-" . $ads_id);

    $orderData = [
        'af_id'            => $af_id,
        'order_id'         => $order_id,
        'order_total'      => $order_total,
        'order_currency'   => $order_currency,
        'product_ids'      => $product_ids,
        'script_name'      => $has_click_token ? 's2s' : 's2s_direct',
        'base_url'         => base64_encode($domain_name),
        'current_page_url' => base64_encode($domain_name),
        'ip'               => $_SERVER['REMOTE_ADDR'],
        'restricted_vendors' => $this->get_restricted_vendors(),
    ];

    // Vendor restrictions for this affiliate
    if ($user_id > 0) {
        $userrefidarray = $this->db->query('SELECT refid FROM users WHERE id = ?', [$user_id])->row_array();
        $userrefidcommon = is_array($userrefidarray) ? $userrefidarray["refid"] : 0;

        $vendors = $this->db->query('SELECT id FROM users WHERE is_vendor = 1')->result_array();
        $escapevendorscommon = $this->db->query('SELECT user_id, vendor_shares_sales_status FROM vendor_setting WHERE vendor_shares_sales_status > 0')->result_array();

        $allowVendorscommon = [];
        foreach ($escapevendorscommon as $esc) {
            if ($esc['vendor_shares_sales_status'] == 1 || ($esc['vendor_shares_sales_status'] == 2 && $esc['user_id'] == $userrefidcommon)) {
                $allowVendorscommon[] = $esc['user_id'];
            }
        }

        $escapeUserscommon = [];
        foreach ($vendors as $v) {
            if (!in_array($v['id'], $allowVendorscommon)) {
                $escapeUserscommon[] = $v['id'];
            }
        }

        $orderData['restricted_vendors'] = array_unique(array_merge($orderData['restricted_vendors'], $escapeUserscommon));
    }

    // Campaign per country check
    $geoData = $this->which_country();
    $postCountries = (isset($integration_tool['country_sortname']) && $integration_tool['country_sortname'] != "") ? explode(",", $integration_tool['country_sortname']) : '';

    if (is_array($postCountries) && !empty($postCountries)) {
        if (!in_array($geoData['country_code'], $postCountries)) {
            $this->_s2s_json_response(['status' => 'error', 'message' => 'Campaign is blocked for country: ' . $geoData['country_code']]);
        }
    }

    $this->IntegrationModel->addOrder($orderData);

    $this->_s2s_json_response(['status' => 'success', 'message' => 'S2S conversion recorded successfully', 'order_id' => $order_id]);
}

/**
 * S2S Register Click — server-side referral/click registration for mobile apps.
 * Creates a click record and returns a click_token that can later be used with s2sConvert.
 *
 * POST /integration/s2sRegisterClick
 * Required: api_key, campaign_id, affiliate_id
 * Optional: visitor_id, ip
 * Returns: JSON { status, click_token }
 */
public function s2sRegisterClick() {

    $data = $this->_parse_s2s_input();

    $required = ['api_key', 'campaign_id', 'affiliate_id'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            $this->_s2s_json_response(['status' => 'error', 'message' => "Missing required field: {$field}"]);
        }
    }

    $api_key      = trim($data['api_key']);
    $campaign_id  = (int) trim($data['campaign_id']);
    $affiliate_id = (int) trim($data['affiliate_id']);
    $visitor_id   = isset($data['visitor_id']) ? trim($data['visitor_id']) : '';

    $integration_tool = $this->_load_and_validate_campaign_direct($campaign_id, $api_key);
    $ads_id = $integration_tool['_ads_id'];

    $userRow = $this->db->query("SELECT id FROM users WHERE type='user' AND id = ?", [$affiliate_id])->row();
    if (empty($userRow)) {
        $this->_s2s_json_response(['status' => 'error', 'message' => 'Affiliate user not found']);
    }

    $click_token = generate_click_token();
    $domain_name = parse_url($integration_tool['target_link'], PHP_URL_HOST) ?: $integration_tool['target_link'];
    $ip = isset($data['ip']) ? trim($data['ip']) : $_SERVER['REMOTE_ADDR'];
    $_ip = $this->Product_model->ip_info($ip);

    $clickData = [
        'product_id'   => 0,
        'script_name'  => 's2s_direct',
        'action_code'  => $visitor_id,
        'page_name'    => 's2s_register_click',
        'user_id'      => $affiliate_id,
        'commission'   => 0,
        'ads_id'       => $ads_id,
        'is_action'    => 0,
        'tools_id'     => $campaign_id,
        'created_at'   => date('Y-m-d H:i:s'),
        'base_url'     => $domain_name,
        'ip'           => $ip,
        'country_code' => isset($_ip['country_code']) ? $_ip['country_code'] : '',
        'custom_data'  => json_encode([]),
        'click_token'  => $click_token,
    ];

    $this->db->insert('integration_clicks_action', $clickData);

    $this->_s2s_json_response([
        'status'      => 'success',
        'message'     => 'Click registered successfully',
        'click_token' => $click_token,
    ]);
}

/**
 * Parse incoming S2S request body (JSON, form-encoded, or query string).
 */
private function _parse_s2s_input() {
    $content = file_get_contents("php://input");
    $data = [];
    if ($content) {
        $jsonData = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
            $data = $jsonData;
        } else {
            parse_str($content, $data);
        }
    }
    if (empty($data)) {
        $data = $this->input->post(null, true);
    }
    if (empty($data)) {
        $data = $this->input->get(null, true);
    }
    return $data;
}

/**
 * Load and validate a campaign for S2S Direct mode (by integration_tools.id).
 * Verifies: exists, S2S enabled, s2s_direct_mode enabled, API key matches.
 */
private function _load_and_validate_campaign_direct($campaign_id, $api_key) {
    $tool = $this->db->query("SELECT * FROM integration_tools WHERE id = ?", [$campaign_id])->row_array();
    if (empty($tool)) {
        $this->_s2s_json_response(['status' => 'error', 'message' => 'Campaign not found']);
    }
    if (empty($tool['s2s_enabled'])) {
        $this->_s2s_json_response(['status' => 'error', 'message' => 'S2S tracking is not enabled for this campaign']);
    }
    if (empty($tool['s2s_direct_mode'])) {
        $this->_s2s_json_response(['status' => 'error', 'message' => 'S2S Direct Mode is not enabled for this campaign. Enable it in admin settings.']);
    }
    if (empty($tool['api_key']) || $tool['api_key'] !== $api_key) {
        $this->_s2s_json_response(['status' => 'error', 'message' => 'Invalid API key']);
    }
    if ((int) $tool['status'] !== 1) {
        $this->_s2s_json_response(['status' => 'error', 'message' => 'Campaign is not active']);
    }

    $ads = $this->db->query("SELECT id FROM integration_tools_ads WHERE tools_id = ? LIMIT 1", [$campaign_id])->row();
    $tool['_ads_id'] = $ads ? $ads->id : $campaign_id;

    return $tool;
}

/**
 * Send a clean JSON response for S2S endpoint.
 * Cleans ALL output buffers right before sending to prevent any garbage text.
 */
private function _s2s_json_response($data) {
    // Discard ALL buffered output (constructor license checks, etc.)
    while (ob_get_level()) {
        ob_end_clean();
    }
    // Send clean headers and JSON
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    echo json_encode($data);
    exit;
}

// NEW CODE: Helper function to process postback
private function processPostback($integration_tool, $data) {
    $postbackSettings = json_decode($integration_tool['marketpostback'], true);
    $this->log_message("Postback Settings: " . json_encode($postbackSettings));

    if ($postbackSettings['status'] == 'custom' || $postbackSettings['status'] == 'default') {
        $postbackUrl = $postbackSettings['status'] == 'custom' ? $postbackSettings['url'] : $this->getDefaultPostbackUrl();
        $postbackParams = $this->getPostbackParams($postbackSettings, $data);

        $this->log_message("Sending postback to: " . $postbackUrl);
        $this->log_message("Postback params: " . json_encode($postbackParams));

        $response = $this->sendPostback($postbackUrl, $postbackParams);
        $this->log_message("Postback response: " . $response);

        return $response;
    } else {
        $this->log_message("Postback is disabled");
        return "POSTBACK_DISABLED";
    }
}

// NEW CODE: Helper function to get default postback URL
private function getDefaultPostbackUrl() {
    $defaultSettings = $this->Product_model->getSettings('marketpostback');
    return $defaultSettings['url'] ?? '';
}

// NEW CODE: Helper function to prepare postback parameters
private function getPostbackParams($settings, $orderData) {
    $params = [];
    if (isset($settings['dynamicparam']) && is_array($settings['dynamicparam'])) {
        foreach ($settings['dynamicparam'] as $key => $value) {
            if ($value) {
                $params[$key] = $orderData[$key] ?? '';
            }
        }
    }
    if (isset($settings['static']) && is_array($settings['static'])) {
        foreach ($settings['static'] as $param) {
            $params[$param['key']] = $param['value'];
        }
    }
    return $params;
}

// NEW CODE: Helper function to send postback
private function sendPostback($url, $params) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

private function log_message($message) {
    error_log($message);
}


	//Fruad functions section start
	public function market_FraudDetection($data) {

	    // Fetch the setting from the database
	    $settings = $this->Product_model->getSettings('site');
	    $block_click_across_browser = isset($settings['block_click_across_browser']) ? $settings['block_click_across_browser'] : 0;
	    $send_fraud_alert_email = isset($settings['send_fraud_alert_email']) ? $settings['send_fraud_alert_email'] : 0;

	    // Skip fraud check if cross-browser click blocking is off.
	    if (!$block_click_across_browser) {
	        return; // Early return if the setting is not enabled
	    }

	    // Prepare user-agent data
	    $logData = $this->prepareLogsUagentData();
	    $data = array_merge($data, $logData);

			// Custom error handling logic
			$handleError = function($message) use (&$data, $send_fraud_alert_email) {
			    // Prepare email data
			$_data = array(
			    'error_message'    => $message,
			    'ip_address'       => $data['user_ip'],
			    'user_id'          => $data['user_id'] ?? null,
			    'browser'          => $data['browserName'],
			    'browser_version'  => $data['browserVersion'],
			    'system_string'    => $data['systemString'],
			    'os_platform'      => $data['osPlatform'],
			    'os_version'       => $data['osVersion'],
			    'mobile_name'      => $data['mobileName'],
			    'os_arch'          => $data['osArch'],
			    'is_intel'         => $data['isIntel'],
			    'is_mobile'        => $data['isMobile'],
			    'is_amd'           => $data['isAMD'],
			    'is_ppc'           => $data['isPPC'],
			    'action_code'      => $data['actionCode'],
			    'custom_fields'    => $data['customFields'],
			    'current_page_url' => $data['current_page_url'],
			    'base_url'         => $data['base_url'],
			    'af_id'            => $data['af_id'],
			    'script_name'      => $data['script_name'],
			    'restricted_vendors'=> $data['restricted_vendors']
			);

		        if ($send_fraud_alert_email) {
		            // Load mail model and send fraud alert
		            $this->load->model('Mail_model');
		            $this->Mail_model->send_fraud_alert($_data);
		        }

			    // Log error and handle gracefully
			    log_message('error', $message);
			    die($message);
			};


	    // Explode af_id and decrypt if necessary
	    $af_id_parts = explode('-', $data['af_id']);
	    if (count($af_id_parts) > 1) {
	        list($user_id, $ads_id) = explode("-", _encrypt_decrypt(parse_affiliate_id($data['af_id']), 'decrypt'));
	        $data['user_id'] = $user_id;
	    } else {
	        $handleError("User ID is missing.");
	    }

	    $data['link'] = urldecode(base64_decode($data['current_page_url']));
	    $base_url = preg_replace("(^https?://)", "", base64_decode($data['base_url']));

	    // Base SQL query
	    $sql = "SELECT ip, agent, browserName, browserVersion, osPlatform, osVersion FROM integration_clicks_logs WHERE 
	            user_id = ? AND
	            base_url = ? AND
	            link = ? AND
	            created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";

	    // Loop to fetch and process data in chunks
	    $offset = 0;
	    $limit = 1000;
	    do {
	        $paged_sql = $sql . " LIMIT $limit OFFSET $offset";
	        $query = $this->db->query($paged_sql, array($data['user_id'], $base_url, $data['link']));
	        $rows = $query->result_array();

	        // Custom fraud checking logic
	        if (!empty($rows)) {
	            $isFraudDetected = false;

	            foreach ($rows as $row) {
	                if ($isFraudDetected) {
	                    break;  // Early exit if fraud is detected
	                }

	                $ip1 = $row['ip'];
	                $ip2 = $data['ip'];

	                // Special handling for localhost
	                if (isset($data['enableLocalhostProtection']) && $data['enableLocalhostProtection'] && ($ip1 === '::1' || $ip1 === '127.0.0.1')) {
	                    $handleError("Potential fraud detected. Clicks from localhost are not allowed.");
	                    $isFraudDetected = true;
	                    break;
	                }

	                if ($ip1 !== $ip2 && $row['agent'] === $data['agent']) {
	                    // Additional checks for optional fields
	                    $optionalFields = ['browserName', 'browserVersion', 'osPlatform', 'osVersion'];
	                    $optionalFieldsMatch = true;

	                    foreach ($optionalFields as $field) {
	                        if (isset($row[$field], $data[$field]) && $row[$field] !== $data[$field]) {
	                            $optionalFieldsMatch = false;
	                            break;
	                        }
	                    }

	                    if ($optionalFieldsMatch) {
	                        $handleError("Potential fraud detected. Repeated clicks within last 24 hours with different IP addresses but similar other attributes.");
	                        $isFraudDetected = true;
	                    }
	                }
	            }
	        }

	        $offset += $limit;
	    } while (!empty($rows));
	}



	public function market_ClickFrequencyExceeded($user_id, $data) {
	    // Fetch settings from the database
	    $settingsKeys = [
	        'max_clicks',
	        'max_actions',
	        'frequency_unit_clicks',
	        'frequency_unit_actions',
	        'enable_action_control',
	        'enable_click_control'
	    ];
	    
	    // Use `where_in` to get all necessary settings in one query
	    $settings = $this->db->select('setting_key, setting_value')
	                         ->where_in('setting_key', $settingsKeys)
	                         ->get('setting')
	                         ->result();

	    // Convert settings results into an associative array
	    $settingsArray = [];
	    foreach ($settings as $setting) {
	        $settingsArray[$setting->setting_key] = $setting->setting_value;
	    }
	    
	    // Extract settings into variables
	    $maxClicks = (int)($settingsArray['max_clicks'] ?? 1);
	    $maxActions = (int)($settingsArray['max_actions'] ?? 1);
	    $frequencyUnitClicks = $settingsArray['frequency_unit_clicks'] ?? 'minute';
	    $frequencyUnitActions = $settingsArray['frequency_unit_actions'] ?? 'minute';
	    $enableActionControl = $settingsArray['enable_action_control'] ?? '1';
	    $enableClickControl = $settingsArray['enable_click_control'] ?? '1';

	    // Determine event type based on data provided
	    $eventType = isset($data['actionCode']) ? 'action' : (isset($data['page_name']) ? 'click' : null);

	    if (!$eventType) {
	        return false; // No event type provided
	    }

	    // Check if the campaign control is enabled for the specific event type
	    if ($eventType === 'click' && $enableClickControl !== '1') {
	        return false; // Click campaign control is disabled
	    }
	    if ($eventType === 'action' && $enableActionControl !== '1') {
	        return false; // Action campaign control is disabled
	    }

	    // Construct date range for checking clicks/actions
	    $currentDateTime = new DateTime();
	    $pastDateTimeClicks = (clone $currentDateTime)->modify("-1 {$frequencyUnitClicks}");
	    $pastDateTimeActions = (clone $currentDateTime)->modify("-1 {$frequencyUnitActions}");


		// Prepare the query for counting clicks or actions
		$this->db->where('user_id', $user_id);

		// Check event type and prepare the query accordingly
		if ($eventType === 'action') {
		    $this->db->where('created_at >=', $pastDateTimeActions->format('Y-m-d H:i:s'))
		             ->where('created_at <=', $currentDateTime->format('Y-m-d H:i:s'))
		             ->where('action_code', $data['actionCode']);
		    $countLimit = $maxActions;
		} elseif ($eventType === 'click') {
		    $this->db->where('created_at >=', $pastDateTimeClicks->format('Y-m-d H:i:s'))
		             ->where('created_at <=', $currentDateTime->format('Y-m-d H:i:s'))
		             ->where('page_name', $data['page_name']);
		    $countLimit = $maxClicks;
		}

	    // Count the clicks or actions in the specified timeframe
	    $eventCount = $this->db->count_all_results('integration_clicks_action');

	    // Check if the click or action frequency is exceeded
	    if ($eventCount >= $countLimit) {
	        echo ucfirst($eventType) . ' limit exceeded. Please try again later.';
	        exit; // Stop script execution
	    }

	    // If all checks pass, return true
	    return true;
	}

	public function prepareLogsUagentData() {
		
		$user_ip = $this->input->ip_address();
		$this->load->library('Uagent');
		$this->uagent->init();
		
		$uagentString = $this->uagent->string;

		if(empty($uagentString)) {
			$logData = $this->session->userdata('uncompleted_uagent');
		} else {
			$logData = array(
				'user_ip'        => $user_ip, // Add the user's IP address to the logData array
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
			);
		}

		return $logData;
	}
	//Fruad functions section start


	public function stopRecurring(){
		$content = file_get_contents("php://input");
		if($content){
			parse_str($content, $data);
		}else{
			$data = $this->input->get(null);
		}
		$this->IntegrationModel->stopRecurring($data);
	}

	public function addUser(){
		$content = file_get_contents("php://input");
		if($content){
			parse_str($content, $data);
		}else{
			$data = $this->input->get(null);
		}

		list($firstname, $lastname) = explode(" ", $data['display_name']);
		$username = $data['user_login'];
		$password = rand(11111111,99999999);

		$geo = $this->ip_info();
		
		$_data = array(
			'firstname'                 => $firstname,
			'lastname'                  => $lastname ? $lastname : $firstname,
			'email'                     => $data['user_email'],
			'username'                  => $username,
			'password'                  => sha1($password),
			'refid'                     => 0,
			'type'                      => 'user',
			'Country'                   => $geo['id'],
			'City'                      => (string)$geo['city'],
			'phone'                     => $geo['city'],
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
			'value'                     => json_encode(array()),
		);

		$json = array();

		$checkEmail = $this->db->query("SELECT id FROM users WHERE email like ". $this->db->escape($_data['email']))->num_rows();
		if($checkEmail > 0){ $json['error'][] = "Email Already Exist"; }

		$checkUsername = $this->db->query("SELECT id FROM users WHERE username like ". $this->db->escape($_data['username']))->num_rows();
		if($checkUsername > 0){ $json['error'][] = "Username Already Exist"; }

		if(!isset($json['error'])){
			$this->user->insert($_data);

			$_data['password'] = $password;
			$this->load->model('Product_model');
			$this->load->model('Mail_model');

			$this->Mail_model->send_register_integration_mail($_data,__('user.welcome_to_new_user_registration'));

			$notificationData = array(
				'notification_url'          => '/userslist/',
				'notification_type'         =>  'user',
				'notification_title'        =>  __('user.new_user_registration'),
				'notification_viewfor'      =>  'admin',
				'notification_actionID'     =>  0,
				'notification_description'  =>  $_data['firstname'].' '.$_data['lastname'].' register as a  on affiliate Program on '.date('Y-m-d H:i:s'),
				'notification_is_read'      =>  '0',
				'notification_created_date' =>  date('Y-m-d H:i:s'),
				'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
			);

			$this->Product_model->create_data('notification', $notificationData);
		} else {
			echo "<pre>"; print_r($json); echo "</pre>";die; 
		}

	}

	public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
		$this->load->helper('geolocation');
		$geolocation = new Geolocation_helper();
		return $geolocation->ip_info($ip, $purpose, $deep_detect);
	}

	public function addOrderPaypal(){
		$post = $this->input->post(null,true);
		$paypalData = json_decode($post['post'] , 1);

		if($paypalData && isset($paypalData['payment_status'])){
			switch ($paypalData['payment_status']) {
				case 'Completed':
				case 'Pending':
				$this->IntegrationModel->addOrder(array(
					'script_name'    => 'paypal',
					'order_currency' => $paypalData['mc_currency'],
					'order_total'    => $paypalData['auth_amount'],
					'af_id'          => $post['af_id'],
					'order_id'       => $post['order_id'],
					'base_url'       => $post['base_url'],
					'product_ids'    => $post['product_ids'],
				));

				break;
				default:
				echo $paypalData['payment_status'];
				break;
			}
		}
		
	}

	public function programs(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		
		$filter = array();

		$name = isset($_GET['name']) ? $_GET['name'] : '';
		$is_admin = isset($_GET['is_admin']) ? $_GET['is_admin'] : '';
		$status = isset($_GET['status']) ? $_GET['status'] : '';
		
		if($name != '')
		{
			$filter['name'] = $name;
		}
		if($is_admin != '')
		{
			$filter['is_admin'] = $is_admin;
		}
		if($status != '')
		{
			$filter['status'] = $status;
		}

		// Pagination settings
		$per_page = 20;
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$offset = ($page - 1) * $per_page;
		
		// Get total count for pagination
		$total_rows = $this->IntegrationModel->getProgramsCount($filter);
		
		// Get programs with pagination
		$filter['limit'] = $per_page;
		$filter['offset'] = $offset;
		$data['programs'] = $this->IntegrationModel->getPrograms($filter);
		
		// Generate pagination
		$this->load->helper('utility');
		$pagination_settings = [
			'base_url' => base_url('integration/programs'),
			'total_rows' => $total_rows,
			'per_page' => $per_page,
			'current_page' => $page,
			'use_get_params' => true,
			'preserve_query' => true
		];
		
		$data['pagination'] = easy_pagination(
			$pagination_settings['base_url'],
			$pagination_settings['total_rows'],
			$offset,
			$pagination_settings
		);
		
		// Add pagination info for display
		$data['pagination_info'] = [
			'showing_start' => $offset + 1,
			'showing_end' => min($offset + $per_page, $total_rows),
			'total_rows' => $total_rows
		];
		
		$this->view($data,'integration/programs');
	}


	public function search_programs()
	{
		$filter = array();
		
		// Get all filter parameters
		$name = $this->input->post('name');
		$is_admin = $this->input->post('is_admin');
		$status = $this->input->post('status');
		
		if(!empty($name))
		{
			$filter['name'] = $name;
		}
		if($is_admin !== '')
		{
			$filter['is_admin'] = $is_admin;
		}
		if($status !== '')
		{
			$filter['status'] = $status;
		}
		
		$data['programs'] = $this->IntegrationModel->getPrograms($filter);

		$row=$this->load->view('admincontrol/integration/search_programs_row', $data,true);

		echo json_encode($row);
	}

	public function programs_form($program_id = 0){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$data = array();
		if($program_id){
			$data['programs'] = $this->IntegrationModel->getProgramByID($program_id);
		}
		
		$this->Report_model->view('admincontrol/integration/programs_form', $data);
	}

	public function delete_programs_form(){

		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
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
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$data = $this->input->post(null,true);
		$json=array();

		$program_id = (int)$data['program_id'];
		$programs = $this->IntegrationModel->getProgramByID($program_id);

		if(empty($program_id))
			$this->form_validation->set_rules('name', 'Name', 'required|trim');
		else 
			$this->form_validation->set_rules('program_id', 'Program', 'required|trim|integer');

		if($data['sale_status']){
			$this->form_validation->set_rules('commission_type', 'Name', 'required|trim');
			$this->form_validation->set_rules('commission_sale', 'Name', 'required|trim');
		}
		if($data['click_status']){
			$this->form_validation->set_rules('commission_number_of_click', 'Name', 'required|trim');
			$this->form_validation->set_rules('commission_click_commission', 'Name', 'required|trim');
		}

		if($programs['vendor_id']){
			if((float)$data['admin_commission_sale'] > 0 && $data['admin_commission_sale'] > $programs['commission_sale']){
				$custom_errors['admin_commission_sale'] = 'Must be less than vendor commission ('. c_format($programs['commission_sale']) .')';
			}

			if((float)$data['admin_commission_click_commission'] > 0 && $data['admin_commission_click_commission'] > $programs['commission_click_commission']){
				$custom_errors['admin_commission_click_commission'] = 'Must be less than vendor commission ('. c_format($programs['commission_click_commission']) .')';
			}

			if((float)$data['admin_commission_number_of_click'] > 0 && $data['admin_commission_number_of_click'] > $programs['commission_number_of_click']){
				$custom_errors['admin_commission_number_of_click'] = 'Must be less than vendor commission ('. (int)$programs['commission_number_of_click'] .')';
			}
		}
		
		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();
			foreach ($custom_errors as $key => $value) { $json['errors'][$key] = $value; }
		} else {
			foreach ($custom_errors as $key => $value) { $json['errors'][$key] = $value; }

			if(count($json)==0 || count($json['errors']) == 0){
				$program_id = $this->IntegrationModel->editProgram($data,$program_id, 'admin');
				if($program_id){
					if(isset($data['add_program_to_form'])){
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
					} else {
						$this->session->set_flashdata('success',__('admin.program_saved_successfully'));
						$json['location'] = base_url('integration/programs');
					}
				} else{
					$this->session->set_flashdata('success',__('admin.something_wrong'));
				}
			}

		}

		echo json_encode($json);
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

		$data['views'] = '';
		
		$this->load->model('PagebuilderModel');

		$register_form = $this->PagebuilderModel->getSettings('registration_builder');

		$data['customField'] = json_decode($register_form['registration_builder'],1);

		$this->Report_model->view('admincontrol/integration/instructions', $data);
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
		
		$integration_modules['stripe'] = array(
			'name' => "Stripe Direct Checkout",
			'image' => base_url('assets/payment_gateway/stripe.png'),
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

	public function integration_tools($page= 1){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$userdetails = $this->userdetails();

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$market_vendor = $this->Product_model->getSettings('market_vendor');
			$post = $this->input->post(null,true);
			$get = $this->input->get(null,true);

			$filter = array(
				'page' => isset($get['page']) ? $get['page'] : $page,
				'limitdata' => 25,
				'userdetails' => $userdetails
			);

			if(isset($post['category_id']))
				$filter['category_id'] = $post['category_id'];

			if(isset($post['ads_name']))
				$filter['ads_name'] = $post['ads_name'];

			if(isset($post['vendor_id']) && $post['vendor_id'] == 'only_admins')
				$filter['show_only'] = 'admin';
			
			else if (isset($post['vendor_id']) && $post['vendor_id'] == 'only_vendors')
				$filter['show_only'] = 'vendor';
			
			if(isset($post['groups']) && !empty($post['groups']))
				$filter['groups'] = $post['groups'];

			if(isset($post['show_only']) && $post['show_only'] == 'true')
				$filter['show_only'] = 'admin';

			if (isset($post['status']))
				$filter['status'] = $post['status'];

			if ($market_vendor['marketvendorstatus'] == 1) {
				$filter['marketvendorstatus'] = 1;
				if (isset($post['vendor_id'])) {
					$filter['vendor_id'] = $post['vendor_id'];
				}
			 }else{
			 	$filter['marketvendorstatus'] = 0;
			 }


			$json = array();
			list($data['tools'],$total) = $this->IntegrationModel->getProgramTools($filter);
			
			$json['tools'] = $data['tools'];
			$data['integration_plugins'] = $this->modules_list();
			
			if($post['paginate']){
				$this->load->library('pagination');
				$this->pagination->cur_page = $filter['page'];
				$config['base_url'] = base_url('integration/integration_tools');
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
				$json = $this->load->view("admincontrol/integration/integration_tools_list", $data, true);
			}
			
			echo $json;
			exit;
		}

		$data['vendors'] = $this->db->query("SELECT users.id, CONCAT(users.firstname,' ',users.lastname) as name FROM `integration_tools` LEFT JOIN users ON users.id=vendor_id WHERE vendor_id > 0 && users.id > 0 GROUP by vendor_id")->result_array();

			$data['categories'] = $this->db->query("SELECT DISTINCT integration_category.id  as value ,integration_category.name as label, CASE WHEN integration_category.parent_id=0 THEN integration_category.id ELSE integration_category.parent_id END AS pid FROM `integration_category`
			 inner JOIN integration_tools on integration_tools.category=	 integration_category.id 
		 order by pid,integration_category.id")->result_array();

		$data['status'] = $this->db->query("SELECT id as value,name as label FROM integration_category ")->result_array();

		$groups = $this->db->query("SELECT id, group_name FROM user_groups")->result_array();

		$data['groups'] = [];
		foreach($groups as $g) {
			$data['groups'][$g['id']] = $g['group_name'];
		}

		$this->load->library("socialshare");				
		$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();

		$this->view($data,'integration/integration_tools');
	}

	public function getIntegrationMlmInfo(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$html = '';

		$tool = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));
		if($tool){
			$data['tool']['name'] = $tool['name'];
			$data['tool']['commission_type'] = $tool['commission_type'];
			$data['tool']['main_commission_type'] = isset($tool['main_commission_type']) ? $tool['main_commission_type'] : '';
			$data['tool']['vendor_id'] = $tool['vendor_id'];
			$data['tool']['vendor_name'] = isset($tool['vendor_name']) ? $tool['vendor_name'] : '';
			$data['CurrencySymbol'] = $this->currency->getSymbol();

			// Check MLM enabled status
			if($tool['vendor_id']){
				// Vendor campaign: check vendor MLM module (admin setting) + vendor's own MLM status
				$vendor_mlm_module = $this->Product_model->getSettings('market_vendor', 'vendormlmmodule');
				$data['vendor_module_enabled'] = isset($vendor_mlm_module['vendormlmmodule']) ? (int)$vendor_mlm_module['vendormlmmodule'] : 1;
				$vendor_mlm_check = $this->Product_model->getVendorSettings($tool['vendor_id'], 'referlevel');
				$data['vendor_mlm_enabled'] = isset($vendor_mlm_check['status']) ? (int)$vendor_mlm_check['status'] : 1;
				$data['mlm_enabled'] = ($data['vendor_module_enabled'] && $data['vendor_mlm_enabled']) ? 1 : 0;
				$data['admin_mlm_enabled'] = 1;
			} else {
				// Admin campaign: check admin's own MLM status
				$admin_mlm_check = $this->Product_model->getSettings('referlevel', 'status');
				$data['admin_mlm_enabled'] = isset($admin_mlm_check['status']) ? (int)$admin_mlm_check['status'] : 1;
				$data['vendor_mlm_enabled'] = 1;
				$data['vendor_module_enabled'] = 1;
				$data['mlm_enabled'] = $data['admin_mlm_enabled'];
			}

			if($tool['commission_type'] == 'custom'){
				if($tool['vendor_id'])
					$setting = $this->Product_model->getVendorSettings($tool['vendor_id'], 'referlevel');
				else 
					$setting = $this->Product_model->getSettings('referlevel');
				
				$data['tool']['referlevel'] = $tool['commission']['referlevel'];
				$data['tool']['referlevel']['levels'] = ($tool['commission']['referlevel']['levels']) ? $tool['commission']['referlevel']['levels'] : (isset($setting['levels']) ? (int)$setting['levels'] : 3);

				for ($i=1; $i <= $data['tool']['referlevel']['levels']; $i++) { 
					$data['tool']['referlevel_'. $i] = $tool['commission']['referlevel_'. $i];
				}
			} else {
				$commonSetting = array('referlevel','referlevel_1','referlevel_2','referlevel_3','referlevel_4','referlevel_5','referlevel_6','referlevel_7','referlevel_8','referlevel_9','referlevel_10','referlevel_11','referlevel_12','referlevel_13','referlevel_14','referlevel_15','referlevel_16','referlevel_17','referlevel_18','referlevel_19','referlevel_20','referlevel');

				foreach($commonSetting as $key => $value){
					if($tool['vendor_id'])
						$data['tool'][$value] 	= $this->Product_model->getVendorSettings($tool['vendor_id'], $value);
					else
						$data['tool'][$value] 	= $this->Product_model->getSettings($value);
				}
			}

			$html = $this->load->view('admincontrol/integration/integration_mlm_info',$data,true);
		}

		echo $html;
		die;
	}

	public function integration_code_modal(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$data['action_code'] = 'action_code';
		$data['general_code'] = 'general_code';

		$tools = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));

		if($tools){
			$data['program_id'] = $tools['program_id'];
			$data['name'] = $tools['name'];
			$data['target_link'] = $tools['target_link'];
			$data['tool_type'] = $tools['tool_type'];
			if($tools['tool_type'] == 'action' || $tools['tool_type'] == 'single_action'){
				$data['action_code'] = $tools['action_code'];
			}
			if($tools['tool_type'] == 'general_click'){
				$data['general_code'] = $tools['general_code'];
			}
		}

		$data['tool'] = $tools;
		
		$skipNewViewFor = ['general_integration', 'laravel', 'cakephp', 'codeigniter', 'stripe'];

		if($tools['tool_type'] == 'program' && !empty($tools['tool_integration_plugin']) && !in_array($tools['tool_integration_plugin'], $skipNewViewFor)){
			$data['tool_integration_plugin_html'] = $this->get_plugin_instructions_for_modal($tools['tool_integration_plugin'], $tools['name']);
		}
		
		// Special handling for Stripe Direct Checkout
		if($tools['tool_type'] == 'program' && !empty($tools['tool_integration_plugin']) && $tools['tool_integration_plugin'] == 'stripe'){
			$data['tool_integration_plugin_html'] = $this->load->view('admincontrol/integration/stripe_integration_instructions', $data, true);
		}
		$data['integration_plugins'] = $this->modules_list();
		echo $this->load->view('admincontrol/integration/integration_code_modal', $data, true);
		exit;
	}

	public function integration_setup_modal(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$tools = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));
		if(!$tools){
			echo '<div class="modal-content"><div class="modal-body text-center py-4 text-danger"><i class="bi bi-exclamation-triangle fs-1"></i><p class="mt-2">'.__('admin.campaign_not_found').'</p></div></div>';
			exit;
		}

		$data['tool'] = $tools;
		$data['method'] = isset($tools['integration_method']) ? $tools['integration_method'] : 'js_pixel';
		echo $this->load->view('admincontrol/integration/integration_setup_modal', $data, true);
		exit;
	}

	public function integration_terms_modal(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$data['terms_data'] = $this->IntegrationModel->getTermsToolsByID((int)$this->input->post('id',true));
		
		$json['html'] = $this->load->view('admincontrol/integration/integration_terms_modal', $data, true);

		echo json_encode($json);die;
	}

	public function integration_tools_duplicate($tools_id){
		$this->IntegrationModel->duplicate_tools($tools_id);
		$this->session->set_flashdata('success', __('admin.add_duplicate_campaign_successfully'));
		redirect(base_url('integration/integration_tools'));
		exit;
	}

	/**
	 * POST: create one admin demo campaign (program + general integration + link ad) for onboarding / demos.
	 */
	public function integration_tools_load_demo(){
		if (!$this->userdetails()) {
			redirect('admincontrol/dashboard', 'refresh');
			return;
		}
		if (strtoupper($this->input->server('REQUEST_METHOD')) !== 'POST') {
			redirect(base_url('integration/integration_tools'));
			return;
		}
		$target = $this->input->post('demo_target_url', true);
		$this->load->library('integration_demo_seeder');
		$result = $this->integration_demo_seeder->seed_admin_demo_campaign($target ? $target : '');
		if (!empty($result['success'])) {
			if (!empty($result['already'])) {
				// Still runs auto-fix: demo program commissions + campaign → program link
				$this->session->set_flashdata('success', __('admin.demo_campaign_refreshed'));
			} elseif (!empty($result['program_created'])) {
				$pname = isset($result['program_name']) ? $result['program_name'] : '';
				$this->session->set_flashdata(
					'success',
					sprintf(__('admin.demo_campaign_created_with_new_program'), htmlspecialchars($pname, ENT_QUOTES, 'UTF-8'))
				);
			} else {
				$this->session->set_flashdata('success', __('admin.demo_campaign_created'));
			}
		} else {
			$key = isset($result['error']) ? $result['error'] : 'save_failed';
			$msg = ($key === 'no_program')
				? __('admin.demo_campaign_no_program')
				: __('admin.demo_campaign_failed');
			$this->session->set_flashdata('error', $msg);
		}
		redirect(base_url('integration/integration_tools'));
	}

	public function integration_tools_form($type="banner", $tools_id = 0){

		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$setting = $this->Product_model->getSettings('referlevel');

		$data['levels'] = isset($setting['levels']) ? (int)$setting['levels'] : 3;

		$data['country_list'] = $this->db->query("SELECT id, name, sortname FROM countries")->result();

		$program_filter = [];

		if($tools_id){

			$data['tool'] = $this->IntegrationModel->getProgramToolsByID($tools_id);

			$program_filter['vendor_id'] = (int)$data['tool']['vendor_id'];

			$category_ids = explode(",", $data['tool']['category']);
			if(count(array_filter($category_ids)) > 0){
				$data['categories'] = $this->db->query("SELECT id as value,name as label FROM integration_category WHERE id IN (". implode(",", $category_ids) .") ")->result_array();
			}

			$data['country_sortname'] = explode(",", $data['tool']['country_sortname']);
			$data['country_name'] = explode(",", $data['tool']['country_name']);
			$data['country_message'] = (empty(trim($data['tool']['country_sortname']))) ? 'No Countries Added' : '';

			$data['referlevel'] = $data['tool']['commission']['referlevel'];

			$data['levels'] = ($data['tool']['commission']['referlevel']['levels']) ? $data['tool']['commission']['referlevel']['levels'] : $data['levels'];
			for ($i=1; $i <= $data['levels']; $i++) { 
				$data['referlevel_'. $i] = $data['tool']['commission']['referlevel_'. $i];
			}
		} else {
			$program_filter['vendor_id'] = 0;
		}

		$commonSetting = array('referlevel','referlevel_1','referlevel_2','referlevel_3','referlevel_4','referlevel_5','referlevel_6','referlevel_7','referlevel_8','referlevel_9','referlevel_10','referlevel_11','referlevel_12','referlevel_13','referlevel_14','referlevel_15','referlevel_16','referlevel_17','referlevel_18','referlevel_19','referlevel_20','referlevel');
		foreach($commonSetting as $key => $value){
			if($data['tool']['vendor_id'])
				$data['default'][$value] = $this->Product_model->getVendorSettings($data['tool']['vendor_id'], $value);
			else
				$data['default'][$value] = $this->Product_model->getSettings($value);
		}

		$data['default_marketpostback'] = $this->Product_model->getSettings('marketpostback');

	  	if($tools_id){
	        $data['tool'] = $this->IntegrationModel->getProgramToolsByID($tools_id);
	        if($data['tool']){
	            $data['tool']['marketpostback'] = json_decode($data['tool']['marketpostback'], true);
	        }
	    }

		$data['cookie_setting'] = $this->Product_model->getSettings('store', 'affiliate_cookie');
		$data['programs'] = $this->IntegrationModel->getPrograms($program_filter);
		$data['type'] = $type;
		$data['p_categories'] = $this->db->query("SELECT id,name FROM integration_category ".$where)->result_array();
		$data['CurrencySymbol'] = $this->currency->getSymbol();
		$data['integration_plugins'] = modules_list();
		$data['randome_code'] = generateRandomAlpahaNemericCode();

        // Fetch country location data by tools_id
        $data['country_location'] = $this->IntegrationModel->getCountryLocationById($tools_id);
        //Need also to check if no data so to show on view side no limitaion message
        /*

        add code here
        */

		$award_level = $this->Product_model->getSettings('award_level', 'status');
		$data['award_level_status'] = !empty($award_level['status']);
		$data['award_levels_list'] = $data['award_level_status'] ? $this->Product_model->getAll('award_level', false, 0, 'minimum_earning ASC') : [];

		$this->Report_model->view('admincontrol/integration/integration_tools_form', $data);
	}


	public function integration_tools_form_post() {

		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		
		$json =array();
		$custom_errors = [];
		$data = $this->input->post(null,true);

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
		$this->form_validation->set_rules('status', 'Status', 'required|trim');
		$this->form_validation->set_rules('type', 'Type', 'required|trim');
		$this->form_validation->set_rules('tool_type', 'Tool Type', 'required|trim');

		$old = $this->db->query("SELECT * FROM integration_tools WHERE id=". (int)$program_tool_id)->row();

		if($data['tool_period'] == 2){
			$this->form_validation->set_rules('end_date', 'End Date', 'required');
		} else if($data['tool_period'] == 3){
			$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		} else if($data['tool_period'] == 4){ 
			$this->form_validation->set_rules('start_date', 'Start Date', 'required');
			$this->form_validation->set_rules('end_date', 'End Date', 'required');
		}

		if($data['cookies_type'] == 1){
			$this->form_validation->set_rules('custom_cookies', __('admin.custom_cookies_tracker_in_days'), 'required');
		} else {
			$data['custom_cookies'] = null;
		}

		if(($data['tool_type'] == 'action') || ($data['tool_type'] == 'single_action')){
			if($old->vendor_id){
				$this->form_validation->set_rules('admin_action_click', 'Action Click', 'required|trim');
				$this->form_validation->set_rules('admin_action_amount', 'Action Amount', 'required|trim');

				if((float)$data['admin_action_amount'] > 0 && $old->action_amount < (float)$data['admin_action_amount']){
					$custom_errors['admin_action_amount'] = 'Must be less than vendor commission ('. c_format($old->action_amount) .')';
				}

			} else{
				$this->form_validation->set_rules('action_click', 'Action Click', 'required|trim');
				$this->form_validation->set_rules('action_amount', 'Action Amount', 'required|trim');
			}
			$this->form_validation->set_rules('action_code', 'Action Code', 'required|trim');
			$data['program_id'] = 0;
		} else if($data['tool_type'] == 'general_click'){
			if($old->vendor_id){
				$this->form_validation->set_rules('admin_general_click', 'General Click', 'required|trim');
				$this->form_validation->set_rules('admin_general_amount', 'General Amount', 'required|trim');

				if((float)$data['admin_general_amount'] > 0 && $old->general_amount < (float)$data['admin_general_amount']){
					$custom_errors['admin_general_amount'] = 'Must be less than vendor commission ('. c_format($old->general_amount) .')';
				}

			} else{
				$this->form_validation->set_rules('general_click', 'General Click', 'required|trim');
				$this->form_validation->set_rules('general_amount', 'General Amount', 'required|trim');
			}

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

		$this->form_validation->set_message('valid_url_custom','Enter a valid URL.');


		if( $data['recursion'] == 'custom_time' ){
			$this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');
		}
		
		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();
			foreach ($custom_errors as $key => $value) { $json['errors'][$key] = $value; }
		} else {
			
			$checkActionCode = 0;

			foreach ($custom_errors as $key => $value) { $json['errors'][$key] = $value; }

			if($data['tool_type'] == 'action' || $data['tool_type'] == 'single_action'){
				$checkActionCode = $this->db->query("SELECT * FROM integration_tools WHERE action_code like ". $this->db->escape($data['action_code']) ." AND id != ". $program_tool_id)->num_rows();
				if($checkActionCode > 0)  $json['errors']['action_code'] = "Action code to be unique";
			}  else if($data['tool_type'] == 'general_click'){
				$checkActionCode = $this->db->query("SELECT * FROM integration_tools WHERE general_code like ". $this->db->escape($data['general_code']) ." AND id != ". $program_tool_id)->num_rows();
				if($checkActionCode > 0) $json['errors']['general_code'] = "General code to be unique";
			}

			if($data['tool_type'] == 'single_action' || $data['tool_type'] == 'action'){
				$featured_image = 'action.jpg';
			} else if($data['tool_type'] == 'general_click') {
				$featured_image = 'click.jpg';
			} else if($data['tool_type'] == 'program'){
				switch ($data['tool_integration_plugin']){
					case 'woocommerce':
					$featured_image = 'woo.png';
					break;
					case 'prestashop':
					$featured_image = 'prestashop.png';
					break;
					case 'opencart':
					$featured_image = 'opencart.png';
					break;
					case 'magento':
					$featured_image = 'magento.png';
					break;
					case 'shopify':
					$featured_image = 'shopify.png';
					break;
					case 'bigcommerce':
					$featured_image = 'Big-Commerce.jpg';
					break;
					case 'paypal':
					$featured_image = 'paypal.png';
					break;
					case 'oscommerce':
					$featured_image = 'oscommerce.png';
					break;
					case 'zencart':
					$featured_image = 'zencart.png';
					break;
					case 'xcart':
					$featured_image = 'xcart.png';
					break;
					case 'laravel':
					$featured_image = 'laravel.png';
					break;
					case 'cakephp':
					$featured_image = 'cackphp.png';
					break;
				case 'codeigniter':
				$featured_image = 'codeigniter.png';
				break;
				case 'stripe':
				$featured_image = 'stripe.png';
				break;
				default:
				$featured_image = 'order.jpg';
			}
			}
			$data['deafult_featured_image'] = $featured_image;

			// Process postback settings
			if (isset($data['marketpostback'])) {
			    $marketpostback = is_array($data['marketpostback']) ? $data['marketpostback'] : json_decode($data['marketpostback'], true);

			    if (isset($marketpostback['status']) && $marketpostback['status'] === 'custom') {
			        // Postback is enabled
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

			        // Set security_status to 2 if postback is enabled
			        $data['security_status'] = 2;
			    } else {
			        // Postback is disabled
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
			    $data['security_status'] = 0; // Set default security_status if postback isn't set
			}

			if(count($json)==0 || count($json['errors']) == 0){
				
				log_message('debug', 'Integration Save: tool_type=' . ($data['tool_type'] ?? 'not set') . ', plugin=' . ($data['tool_integration_plugin'] ?? 'not set'));
				
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
			// Stripe campaigns start as pending - admin must click validation to verify configuration
			// Don't auto-approve, let the validation button check the configuration
			}
		}
				
				if(!isset($data['target_link']) || empty($data['target_link'])){
					if($data['tool_type'] == 'program' && isset($data['tool_integration_plugin']) && $data['tool_integration_plugin'] == 'stripe'){
						$data['target_link'] = '#';
					}
				}
				
				if(count($json)==0 || count($json['errors']) == 0){
					$data['featured_image'] = $data['old_featured_image'];
					
					if(!empty($_FILES['featured_image']['name'])){
						$upload_response = $this->Product_model->upload_photo('featured_image','assets/images/product/upload/thumb');
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
	 				
				// Auto-set s2s flags based on integration_method selection
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

				$program_tool_id = $this->IntegrationModel->editProgramTools($data, $_FILES['custom_banner']);

					if($program_tool_id){
						if(isset($data['save_close'])){
							$json['location'] = base_url("integration/integration_tools_form/". $data['type'] ."/". $program_tool_id);
						} else{
							$json['location'] = base_url("integration/integration_tools");
						}
					} else{
						$json['errors']['name'] = "Something Wrong";
					}
				}
			}
		}

		//debug($data); exit;

		echo json_encode($json);
	}


	public function generateRandomCodeApi()
	{
		$post = $this->input->post();
		if(isset($post['tool_type']) && isset($post['program_tool_id']))
		{
			$tooltype=$post['tool_type'];
			$program_tool_id=$post['program_tool_id'];

			start:
			$randome_code= generateRandomAlpahaNemericCode();
			if($tooltype == 'action' || $tooltype == 'single_action'){
				$checkActionCode = $this->db->query("SELECT * FROM integration_tools WHERE action_code like ". $this->db->escape($randome_code) ." AND id != ". $program_tool_id)->num_rows();
				if($checkActionCode > 0) 
					goto start;	

			}  else if($data['tool_type'] == 'general_click'){
				$checkActionCode = $this->db->query("SELECT * FROM integration_tools WHERE general_code like ". $this->db->escape($$randome_code) ." AND id != ". $program_tool_id)->num_rows();
				if($checkActionCode > 0) 
					goto start;	
			}

		}
		else
			$randome_code= generateRandomAlpahaNemericCode();
		
		echo json_encode($randome_code);
	}


	function get_users_for_integration() {
		$post = $this->input->post();
		$where = "";
		if(isset($post['users_name_string']) && !empty($post['users_name_string'])) {
			$where = " AND username LIKE '%".$post['users_name_string']."%'";
		}
		$users = $this->db->query("SELECT username as name,id FROM users WHERE type='user' ".$where)->result_array();
		echo json_encode($users);
		exit;
	}

	function get_groups_for_integration() {
		$post = $this->input->post();
		$where = "";
		if(isset($post['group_name_string']) && !empty($post['group_name_string'])) {
			$where = " AND group_name LIKE '%".$post['group_name_string']."%'";
		}
		$users = $this->db->query("SELECT group_name, id FROM user_groups WHERE id!=0 ".$where)->result_array();
		echo json_encode($users);
		exit;
	}

	function valid_url_custom($url) {
		if(filter_var($url, FILTER_VALIDATE_URL)){
			return TRUE;
		}
		else{
			return TRUE;
		}
	}

	public function integration_tools_delete($tools_id){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$this->IntegrationModel->deleteTools($tools_id);
		$this->session->set_flashdata('success', __('admin.campaign_deleted_successfully'));
		redirect(base_url("integration/integration_tools"));
	}

	public function tool_get_code($control = 'admincontrol'){
		$tools_id = (int)$this->input->post("id",true);
		if($control == 'admincontrol'){
			if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
			$data['user_id'] = $this->userdetails()['id'];
		}
		else if($control == 'usercontrol'){
			if(!$this->userlogins()){ redirect('usercontrol/dashboard', 'refresh'); }
			$data['user_id'] = $this->userlogins()['id'];
		}

		$this->load->library("socialshare");
		$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();
		$data['tool'] = $this->IntegrationModel->getProgramToolsByID($tools_id,$data['user_id']);
		$json = array();
		if ($control == 'usercontrol' && !empty($data['tool'])) {
			$ud = $this->userlogins();
			if (is_array($ud) && !$this->Product_model->user_can_promote_market_campaign($ud, $data['tool'])) {
				$json['error'] = __('user.market_slug_locked');
				$json['html'] = '';
				echo json_encode($json);
				die;
			}
		}
		if(!empty($data['tool'])){
		 	
			$json['html'] = $this->load->view("integration/code", $data, true);
		}
	
		echo json_encode($json);die;
	}

	public function tool_get_terms($control = 'usercontrol'){
		$tools_id = (int)$this->input->post("id",true);
		if($control == 'usercontrol'){
			if(!$this->userlogins()){ redirect('usercontrol/dashboard', 'refresh'); }
			$data['user_id'] = $this->userlogins()['id'];
		}
		
		$data['terms_data'] = $this->IntegrationModel->getTermsToolsByID($tools_id);
		if($data['terms_data']){
			$json['html'] = $this->load->view("integration/terms", $data, true);
		}
		
		echo json_encode($json);die;
	}

	public function user_integration_tools(){
		$user = $this->userlogins();
		if(!$user){ redirect('usercontrol/dashboard', 'refresh'); }
		
		$data['tools'] = $this->IntegrationModel->getProgramTools([
			'user_id' => $user['id'],
			'status' => 1,
			'redirectLocation'=> 1,
			'restrict'=> $user['id'],
		]);

		$this->Report_model->view('usercontrol/integration/integration_tools', $data,'usercontrol');
	}

	public function orders(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$json = array();
			$orders = $this->IntegrationModel->getDeleteOrders($this->input->post('ids',true));
			$html = '<table class="table table-sm table-bordered"><tr><td>Id</td><td>Commission</td><td>Refer Commission</td></tr>';
			foreach ($orders as $key => $value) {
				$html .= '<tr>';
				$html .= '	<td>'. $key ."</td>";
				$html .= '	<td>'. c_format($value['commission']) ."</td>";
				$html .= '	<td>'. c_format($value['refer_commission']) ."</td>";
				$html .= '</tr>';
			}
			$html .= '</table>';

			$json['html'] = $html;
			echo json_encode($json);die;
		}

		$data['orders'] = $this->IntegrationModel->getOrders();
		$this->Report_model->view('admincontrol/integration/orders', $data);
	}
	
	public function deleteOrdersConfirm(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$json = array();
			$orders = $this->IntegrationModel->getDeleteOrders($this->input->post('ids',true));
			
			foreach ($orders as $key => $value) {
				foreach ($value['sql'] as $key => $sql) {
					$this->db->query($sql);
				}
			}
		}		

		echo json_encode($json);die;
	}
	
	public function user_orders(){
		$user = $this->userlogins();
		if(!$user){ redirect('usercontrol/dashboard', 'refresh'); }

		$data['orders'] = $this->IntegrationModel->getOrders(['user_id' => $user['id']]);
		$this->Report_model->view('usercontrol/integration/orders', $data,'usercontrol');
	}

	public function logs(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$this->load->library('pagination');
		$this->load->helper('url');

		$filter['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
		if(isset($_GET['type']) && $_GET['type']){
			$filter['type'] = $_GET['type'];
		}

		$_data = $this->IntegrationModel->getLogs($filter);

		$config['base_url'] = base_url('integration/logs');
		$config['per_page'] = 50;
		$config['total_rows'] = $_data['total'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$data['logs'] = $_data['records'];

		$this->Report_model->view('admincontrol/integration/logs', $data);
	}

	public function click_logs(){
		$user = $this->userlogins();
		if(!$user){ redirect('usercontrol/dashboard', 'refresh'); }
		$this->load->library('pagination');
		$this->load->helper('url');

		$filter['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
		$filter['user_id'] = $user['id'];

		if(isset($_GET['type']) && $_GET['type']){
			$filter['type'] = $_GET['type'];
		}

		$_data = $this->IntegrationModel->getLogs($filter);


		$config['base_url'] = base_url('integration/click_logs');
		$config['per_page'] = 50;
		$config['total_rows'] = $_data['total'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$data['logs'] = $_data['records'];

		$this->Report_model->view('usercontrol/integration/logs', $data ,'usercontrol');
	}

	public function delete_log(){
		$ids = (array)$this->input->post('ids',true);
		if($ids){
			$ids = implode(",", $ids);

			$this->db->query("DELETE FROM integration_clicks_logs WHERE id IN ({$ids})");
		}

		echo json_encode(array());		 
	}


	public function _zip($archive_folder, $archive_name){
	    $zip = new ZipArchive;
	    $archive_path = APPPATH . "cache/" . $archive_name;
	    if ($zip->open($archive_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
	        $rootPath = realpath($archive_folder);
	        $files = new RecursiveIteratorIterator(
	            new RecursiveDirectoryIterator($rootPath),
	            RecursiveIteratorIterator::SELF_FIRST
	        );

	        foreach ($files as $file) {
	            $file = realpath($file);
	            // Skip directories since they will be added automatically by adding files
	            if (is_file($file) === true) {
	                $relativePath = substr($file, strlen($rootPath) + 1);
	                $content = str_replace('__baseurl__', base_url(), file_get_contents($file));
	                $zip->addFromString($relativePath, $content);
	            }
	        }

	        if ($zip->status == ZIPARCHIVE::ER_OK) {
	            $zip->close();

	            // Serve the file as download
	            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
	            header("Content-Type: application/zip");
	            header("Content-Transfer-Encoding: Binary");
	            header("Content-Length: " . filesize($archive_path));
	            header("Content-Disposition: attachment; filename=\"" . basename($archive_path) . "\"");
	            header('Expires: 0');
	            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	            header('Pragma: public');

	            ob_end_clean();
	            readfile($archive_path);
	            @unlink($archive_path);
	            exit;
	        } else {
	            echo 'Error, can\'t create a zip file!';
	        }
	    }
	}


	public function download_plugin($script, $version = 0){
		if($script == 'woocommerce'){
			$path = "application/plugins/affiliatepro_woocommerce/";
			$this->_zip($path,'AffiliatePro_WooCommerce.zip');
		}
		else if($script == 'php_api_library'){
			$path = "application/plugins/php_api_library/";
			$this->_zip($path,'php_api_library.zip');
		}
		else if($script == 'wp_user_register'){
			$path = "application/plugins/wp_user_register/";
			$this->_zip($path,'WordpressWoocommerceRegistrationBridge.zip');
		}
		else if($script == 'show_affiliate_id'){
			$path = "application/plugins/show-affiliate-id/";
			$this->_zip($path,'WordpressShowAffiliateID.zip');
		}
		else if($script == 'prestashop'){
			$path = "application/plugins/ps_aff/";
			$this->_zip($path,'ps_aff.zip');
		}
		else if($script == 'magento'){
			if($version == 1){
				$path = "application/plugins/magento1/";
				$this->_zip($path,'AffiliatePro_Magento.zip');
			} else{
				$path = "application/plugins/magento/";
				$this->_zip($path,'AffiliatePro_Magento.zip');
			}
		}
		else if($script == 'opencart'){
			if($version  == 1){
				$path = "application/plugins/opencart-1564-2200/";
				$this->_zip($path,'AffiliatePro_Opencart-1564-2200.ocmod.zip');
			}
			else if($version  == 2){
				$path = "application/plugins/opencart-2300-3011/";
				$this->_zip($path,'AffiliatePro_Opencart-2300-3011.ocmod.zip');
			}
		}
	}

	public function integration_category_delete($category_id = 0){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect($this->admin_domain_url, 'refresh'); }

		if($category_id > 0){
			$data['category'] = $this->db->query("DELETE FROM integration_category WHERE id = ". (int)$category_id);
		}

		$this->session->set_flashdata('success',__('admin.category_deleted_successfully'));
		redirect(base_url('integration/integration_category'));
	}

	public function category_auto(){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect($this->admin_domain_url, 'refresh'); }
		$keyword = $this->input->get('query');
		
		 
		$data = $this->db->query("SELECT integration_category.id as value,integration_category.name as label, CASE WHEN integration_category.parent_id=0 THEN integration_category.id ELSE integration_category.parent_id END AS pid FROM integration_category WHERE integration_category.name  like ". $this->db->escape("%".$keyword."%") ." order by pid,integration_category.id")->result_array();
		
		echo json_encode($data);die;
	}


	public function country_auto() {
	    $userdetails = $this->userdetails();
	    if (!$userdetails) { 
	        redirect($this->admin_domain_url, 'refresh'); 
	    }
	    $keyword = $this->input->get('query');

	    $data = $this->db->query("SELECT country.id as value, country.name as label FROM country WHERE country.name LIKE " . $this->db->escape('%' . $keyword . '%') . " ORDER BY country.name ASC")->result_array();

	    echo json_encode($data);
	    die;
	}


	public function integration_category_add($category_id = 0){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect($this->admin_domain_url, 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Category Name', 'required');
			
			if($this->form_validation->run()){
				$details = array(
					'name' =>  $this->input->post('name',true),
					'parent_id' =>  $this->input->post('parent_id',true),
				);

				if($category_id){
					$this->Product_model->update_data('integration_category', $details, array('id' => $category_id));
				}else{
					$details['created_at'] = date('Y-m-d H:i:s');
					$category_id = $this->Product_model->create_data('integration_category', $details);
				}

				$add_category_to_form = $this->input->post('add_category_to_form',true);
				if(isset($add_category_to_form)){
					$category = $this->db->query("SELECT id,name FROM integration_category WHERE id = ". (int) $category_id)->row_array();
					$json['message'] = __('admin.category_save_successfully');
					$json['newOption'] = '<option value="'.$category['id'].'">'.$category['name'].'</option>';
				} else {
					$this->session->set_flashdata('success',__('admin.category_save_successfully'));
					$json['location'] = base_url('integration/integration_category');
				}
			} else {
				$json['errors'] = $this->form_validation->error_array();
			}

			echo json_encode($json);die;
		}

		$data['category'] = array();

		$where = "";
		
		if($category_id > 0){
			$data['category'] = $this->db->query("SELECT * FROM integration_category WHERE id = ". (int)$category_id)->row_array();
			
			$where = " where id != ".$category_id." and ( parent_id != ".$category_id." OR parent_id IS NULL)";

		}

		$data['p_categories'] = $this->db->query("SELECT id,name FROM integration_category where parent_id=0")->result_array();
		
		$data['categories'] = $this->db->query("SELECT id,name FROM integration_category")->result_array();

		$this->view($data, 'integration/category_add');
	}

	public function integration_category($page = 1){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect($this->admin_domain_url, 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$page = max((int)$page,1);
			$per_page = 20;
			$offset = ($page - 1) * $per_page;
			
			$filter = array(
				'limit' => $per_page,
				'offset' => $offset,
				'page' => $page,
			);

			list($data['categories'],$total) = $this->Product_model->getIntegrationCategory($filter);
			$data['start_from'] = $offset + 1;
			$json['html'] = $this->load->view("admincontrol/integration/category_list.php",$data,true);

			// Generate pagination using utility helper
			$this->load->helper('utility');
			$pagination_settings = [
				'base_url' => base_url('integration/integration_category'),
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

			echo json_encode($json);die;
		}
		
		$this->view($data, 'integration/integration_category');
	}


	public function check_campaign_security_with_id($id) {
		if(!$this->userdetails()){ die(); }

		if((int) $id){
			$data = [];

			$tool = $this->IntegrationModel->getProgramToolsByID($id);
			
			if(!empty($tool)){
				$im = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel';
				$methodStatus = getSecurityStatusByMethod($tool);

				if ($methodStatus !== null) {
					// S2S, Mobile, Conversion API, or Postback method
					if ($tool['security_status'] != $methodStatus) {
						$this->db->query('UPDATE integration_tools SET security_status=' . (int)$methodStatus . ' WHERE id=' . $tool['id']);
					}
					$data['security_status'] = $methodStatus;
					$data['message'] = getSecurityStatusMethodLabel($im, $methodStatus);
					$data['statusClass'] = $methodStatus >= 1 ? 'badge bg-success' : 'badge bg-info';
					if ($methodStatus == 2) $data['statusClass'] = 'badge bg-primary';
				} elseif($tool['tool_type'] == 'program' && $tool['tool_integration_plugin'] == 'stripe'){
					// Stripe Direct Checkout validation
					$stripe_valid = true;
					$stripe_error = '';
					
					$stripe_settings = $this->Product_model->getSettings('payment_gateway_integration_stripe');
					if(empty($stripe_settings) || $stripe_settings['status'] != 1){
						$stripe_valid = false;
						$stripe_error = __('admin.stripe_not_enabled_for_integration');
					}
					
					if($stripe_valid){
						$stripe_keys = $this->Product_model->getSettings('payment_gateway_stripe');
						
						if(empty($stripe_keys)){
							$stripe_valid = false;
							$stripe_error = __('admin.stripe_api_keys_not_configured');
						} else {
							$secret_key = '';
							$is_test_mode = (isset($stripe_keys['environment']) && $stripe_keys['environment'] == 0) || 
							                (isset($stripe_keys['test_mode']) && $stripe_keys['test_mode'] == 1);
							
							if($is_test_mode){
								$secret_key = isset($stripe_keys['test_secret_key']) ? $stripe_keys['test_secret_key'] : '';
							} else {
								$secret_key = isset($stripe_keys['live_secret_key']) ? $stripe_keys['live_secret_key'] : '';
							}
							
							if(empty($secret_key)){
								$stripe_valid = false;
								$stripe_error = __('admin.stripe_secret_key_missing');
							}
							
							if($stripe_valid){
								$webhook_secret = '';
								if(isset($stripe_keys['integration_webhook_secret']) && !empty($stripe_keys['integration_webhook_secret'])){
									$webhook_secret = $stripe_keys['integration_webhook_secret'];
								} elseif(isset($stripe_keys['webhook_secret']) && !empty($stripe_keys['webhook_secret'])){
									$webhook_secret = $stripe_keys['webhook_secret'];
								}
								
								if(empty($webhook_secret)){
									$stripe_valid = false;
									$stripe_error = __('admin.stripe_webhook_secret_missing');
								}
							}
						}
					}
					
					if($stripe_valid){
						$commission = !empty($tool['commission']) ? (is_string($tool['commission']) ? json_decode($tool['commission'], true) : $tool['commission']) : [];
						$stripe_price = isset($commission['stripe_price']) ? floatval($commission['stripe_price']) : 0;
						
						if(empty($stripe_price) || $stripe_price <= 0){
							$stripe_valid = false;
							$stripe_error = __('admin.stripe_campaign_price_not_set');
						}
					}
					
				if($stripe_valid){
					if($tool['security_status'] != 1){
						$this->db->query('UPDATE integration_tools SET security_status=1 WHERE id='.$tool['id']);
					}
					$data['security_status'] = 1;
					$data['statusClass'] = 'badge bg-success';
					$data['message'] = __('admin.approved');
				} else {
					if($tool['security_status'] != 0){
						$this->db->query('UPDATE integration_tools SET security_status=0 WHERE id='.$tool['id']);
					}
					$data['security_status'] = 0;
					$data['statusClass'] = 'badge bg-danger';
					$data['message'] = $stripe_error;
					$data['integration_code_button'] = '<button data-toggle="tooltip" title="'.__('admin.integration_code').'" 
					class="btn-show-code badge bg-info" data-id="'.$tool['id'].'">
					<i class="fa fa-code" aria-hidden="true"></i>
					</button>';
				}
				} else {
					// JS Pixel: cURL target URL and check for integration code
					$security_alerts = external_integration_security_check($tool['target_link']);
					$status = getSecurityStatus($security_alerts,$tool['tool_type'],$tool['tool_integration_plugin'],$tool['program_id']);

					if($tool['security_status'] == 1 && $status == 0){
						$this->db->query('UPDATE integration_tools SET security_status=0 WHERE id='.$tool['id']);
						$data['security_status'] = 0;
						$data['statusClass'] = 'badge bg-info';
						$data['message'] = __('admin.pending_integration');
						$data['integration_code_button'] = '<button data-toggle="tooltip" title="'.__('admin.integration_code').'" 
						class="btn-show-code badge bg-info" data-id="'.$tool['id'].'">
						<i class="fa fa-code" aria-hidden="true"></i>
						</button>';
					}

					if($tool['security_status'] == 0 && $status == 1){
						$this->db->query('UPDATE integration_tools SET security_status=1 WHERE id='.$tool['id']);
						$data['security_status'] = 1;
						$data['statusClass'] = 'badge bg-success';
						$data['message'] = __('admin.approved');
					}
				}
			}
			
			echo json_encode($data);
		}
	}

	public function check_campaign_security() {
	    if (!$this->userdetails()) { die(); }

	    if ($this->input->server('REQUEST_METHOD') == 'POST') {
	        $result = [];
	        $post = $this->input->post(null, true);
	        $offset = isset($post['index']) ? $post['index'] - 1 : 0;

	        $tool = $this->db->query('SELECT * FROM integration_tools LIMIT ' . $offset . ', 1')->row_array();

	        if (!empty($tool)) {
	            $integration_tools_count = $this->db->query('SELECT COUNT(id) as tools_count FROM integration_tools')->row()->tools_count;

	            if ($integration_tools_count > $post['index'])
	                $result['index'] = $post['index'] + 1;

	            if ($integration_tools_count > 0)
	                $result['progress_percentage'] = (($post['index'] / $integration_tools_count) * 100) . "%";

	            // Decode the marketpostback JSON field
	            $marketpostback = json_decode($tool['marketpostback'], true);

	            // Set the campaign name based on the correct field in $tool
	            $result['campaign_name'] = isset($tool['name']) ? $tool['name'] : 'Unnamed campaign'; // Replace 'name' with actual field name if different

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
	                
	                $stripe_settings = $this->Product_model->getSettings('payment_gateway_integration_stripe');
	                if(empty($stripe_settings) || $stripe_settings['status'] != 1){
	                    $stripe_valid = false;
	                    $stripe_error = __('admin.stripe_not_enabled_for_integration');
	                }
	                
	                if($stripe_valid){
	                    $stripe_keys = $this->Product_model->getSettings('payment_gateway_stripe');
	                    if(empty($stripe_keys)){
	                        $stripe_valid = false;
	                        $stripe_error = __('admin.stripe_api_keys_not_configured');
	                    } else {
	                        $secret_key = '';
	                        $is_test_mode = (isset($stripe_keys['environment']) && $stripe_keys['environment'] == 0) || 
	                                        (isset($stripe_keys['test_mode']) && $stripe_keys['test_mode'] == 1);
	                        
	                        if($is_test_mode){
	                            $secret_key = isset($stripe_keys['test_secret_key']) ? $stripe_keys['test_secret_key'] : '';
	                        } else {
	                            $secret_key = isset($stripe_keys['live_secret_key']) ? $stripe_keys['live_secret_key'] : '';
	                        }
	                        
	                        if(empty($secret_key)){
	                            $stripe_valid = false;
	                            $stripe_error = __('admin.stripe_secret_key_missing');
	                        }
	                        
	                        if($stripe_valid){
	                            $webhook_secret = '';
	                            if(isset($stripe_keys['integration_webhook_secret']) && !empty($stripe_keys['integration_webhook_secret'])){
	                                $webhook_secret = $stripe_keys['integration_webhook_secret'];
	                            } elseif(isset($stripe_keys['webhook_secret']) && !empty($stripe_keys['webhook_secret'])){
	                                $webhook_secret = $stripe_keys['webhook_secret'];
	                            }
	                            
	                            if(empty($webhook_secret)){
	                                $stripe_valid = false;
	                                $stripe_error = __('admin.stripe_webhook_secret_missing');
	                            }
	                        }
	                    }
	                }
	                
	                if($stripe_valid){
	                    $commission_data = !empty($tool['commission']) ? json_decode($tool['commission'], true) : [];
	                    $stripe_price = isset($commission_data['stripe_price']) ? floatval($commission_data['stripe_price']) : 0;
	                    
	                    if(empty($stripe_price) || $stripe_price <= 0){
	                        $stripe_valid = false;
	                        $stripe_error = __('admin.stripe_campaign_price_not_set');
	                    }
	                }
	                
	                if($stripe_valid){
	                    if ($tool['security_status'] != 1) {
	                        $this->db->query('UPDATE integration_tools SET security_status=1 WHERE id=' . $tool['id']);
	                        $tool['security_status'] = 1;
	                    }
	                    $result['security_status'] = 'approved';
	                    $result['message'] = __('admin.campaigns_verified_successfully');
	                } else {
	                    if ($tool['security_status'] != 0) {
	                        $this->db->query('UPDATE integration_tools SET security_status=0 WHERE id=' . $tool['id']);
	                        $tool['security_status'] = 0;
	                    }
	                    $result['security_status'] = 'pending';
	                    $result['message'] = $stripe_error;
	                }
	            } else {
	                // JS Pixel: cURL target URL and check for integration code
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
}