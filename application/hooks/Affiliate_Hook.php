<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Affiliate_Hook {

	public function __construct() {
	    $this->initialize();
	}

	private $CI;

    // Constructor-like method for hooks
    private function initialize() {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        $this->CI->load->library('user_agent');

        // Load necessary resources here
        $this->CI->load->model('Mail_model');
        
    }


	public function is_suspicious_click($user_id) {
	    $this->CI =& get_instance();

	    // Get the setting for sending fraud alert emails
	    $send_fraud_alert_email_setting = $this->CI->db
	        ->get_where('setting', [
	            'setting_key' => 'send_fraud_alert_email',
	            'setting_type' => 'site'
	        ])->row();

	    // Prepare user data
	    $userSysDetails = $this->preparefraudUserData();

	    // Query for the most recent affiliate session log entry for the user with the same IP and OS
	    $similarSysDetails = $this->CI->db
	        ->order_by('time', 'DESC')
	        ->get_where('affiliate_session_log', [
	            'user_id' => $user_id,
	            'user_ip' => $userSysDetails['user_ip'],
	            'user_os' => $userSysDetails['user_os']
	        ])->row();

	    // Get the block settings for clicking across browsers
	    $blockSettings = $this->CI->db
	        ->get_where('setting', [
	            'setting_key' => 'block_click_across_browser',
	            'setting_type' => 'site'
	        ])->row();

	    $suspiciousClickDetected = false;

	    // Check if the session details exist and whether the user agent matches
	    if (!empty($similarSysDetails) && isset($similarSysDetails->user_agent)) {
	        $registeredUserAgents = explode(',', $similarSysDetails->user_agent);
	        if (in_array($userSysDetails['user_agent'], $registeredUserAgents)) {
	            $suspiciousClickDetected = true; // Suspicious click identified
	        }
	    }

	    // Check if the block settings are enabled
	    if (!$suspiciousClickDetected && !empty($blockSettings) && (int)$blockSettings->setting_value === 1) {
	        $suspiciousClickDetected = true; // Click should be blocked based on settings
	    }


	    // If suspicious activity is found and email alerts are enabled, send the email
	    if ($suspiciousClickDetected && !empty($send_fraud_alert_email_setting) && (int)$send_fraud_alert_email_setting->setting_value === 1) {

	        // Prepare the data for the email
			  $emailData = [
			        'user_id'         => $user_id,
			        'ip_address'      => $userSysDetails['ip_address'],
			        'system_string'   => $userSysDetails['system_string'],
			        'os_platform'     => $userSysDetails['os_platform'],
			        'browser'         => $userSysDetails['browser'],
			        'browser_version' => $userSysDetails['browser_version'],
			        'time'            => $userSysDetails['time'],
			        'is_mobile'		  => $userSysDetails['is_mobile'],
			    ];

			// Load mail model and send fraud alert
	        $this->CI->Mail_model->send_fraud_alert($emailData);
	    }

	    return $suspiciousClickDetected;
	}


	public function is_localhost() {
	    $this->CI =& get_instance();

	    // Fetch the setting for localhost protection from the database
	    $enableLocalhostProtection = $this->CI->db->get_where('setting', array(
	        'setting_key' => 'enable_localhost_protection',
	        'setting_type' => 'site'
	    ))->row();

	    // Get the user's IP address
	    $user_ip = $this->CI->input->ip_address();

	    // Determine if the IP address corresponds to localhost and the setting to block localhost is enabled
	    $isLocalhost = ($user_ip === '::1' || $user_ip === '127.0.0.1');
	    $shouldBlock = $isLocalhost && isset($enableLocalhostProtection->setting_value) && (int)$enableLocalhostProtection->setting_value === 1;

	    // Return an array with the 'shouldBlock' key set based on the conditions checked
	    return array(
	        'isLocalhost' => $isLocalhost,
	        'shouldBlock' => $shouldBlock
	    );
	}

	public function sync_aff_session() {
	    // Get CodeIgniter instance
	    $this->CI =& get_instance();

	    // Prepare user data
	    $data = $this->prepareUserData();

	    // Get login user data
	    $loginUser = $this->CI->session->userdata("user");

	    // Check if user is logged in and is of type 'user'
	    if (isset($loginUser) && $loginUser['type'] === 'user') {

	        // Cast user_id to integer for safety
	        $data['user_id'] = (int)$loginUser['id'];

	        // Use query binding to prevent SQL injection
	        $query = $this->CI->db->query('SELECT * FROM affiliate_session_log WHERE user_id = ?', array($data['user_id']));
	        $previousLog = $query->row();

	        // If a previous log exists
	        if (isset($previousLog->id)) {

	            // Process user agent data
	            if (!empty($previousLog->user_agent)) {
	                $previousUserAgents = explode(',', $previousLog->user_agent);
	            } else {
	                $previousUserAgents = [];
	            }

	            if (!in_array($data['user_agent'], $previousUserAgents)) {
	                $previousUserAgents[] = $data['user_agent'];
	            }

	            if (sizeof($previousUserAgents) > 10) {
	                array_slice($previousUserAgents, -10, 10, true);
	            }

	            $data['user_agent'] = implode(",", $previousUserAgents);

	            // Update affiliate session log
	            $this->CI->db->update('affiliate_session_log', $data, array('id' => $previousLog->id));

	        } else {
	            // Insert new record
	            $this->CI->db->insert('affiliate_session_log', $data);
	        }
	    }
	}


	public function preparefraudUserData() {
	    $this->CI->load->library('user_agent');

	    if ($this->CI->agent->is_browser()) {
	        $agent = $this->CI->agent->browser() . ' ' . $this->CI->agent->version();
	    } elseif ($this->CI->agent->is_robot()) {
	        $agent = $this->CI->agent->robot();
	    } elseif ($this->CI->agent->is_mobile()) {
	        $agent = $this->CI->agent->mobile();
	    } else {
	        $agent = 'Unidentified User Agent';
	    }

	    return array(
	        'ip_address'      => $this->CI->input->ip_address(),
	        'system_string'   => md5(trim(strtolower($this->CI->agent->agent_string()))),
	        'os_platform'     => $this->CI->agent->platform(),
	        'browser'         => $this->CI->agent->browser(),
	        'browser_version' => $this->CI->agent->version(),
	        'mobile_name'     => $this->CI->agent->mobile(),
	        'is_mobile'       => $this->CI->agent->is_mobile(),
	        'is_robot'        => $this->CI->agent->is_robot(),
	        'agent_string'    => $agent,
	        'referrer'        => $this->CI->agent->referrer(),
	        'languages'       => $this->CI->agent->languages(),
	        'charsets'        => $this->CI->agent->charsets(),
	        'time'            => time(),
	    );
	}

	//not change this fucntion
	public function prepareUserData() {
		return array(
			'user_ip' => $this->get_client_ip(),
			'user_agent' => md5(trim(strtolower($_SERVER['HTTP_USER_AGENT']))),
			'user_os' => $this->getOS(),
			'time' => time(),
		);
	}



	function getOS() { 

		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$os_platform  = "Unknown OS Platform";

		$os_array     = array(
			'/windows nt 10/i'      =>  'Windows 10',
			'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile'
		);

		foreach ($os_array as $regex => $value)
			if (preg_match($regex, $user_agent))
				$os_platform = $value;

			return trim(strtolower($os_platform));
		}

		function get_client_ip() {
			$ipaddress = '';
			if (isset($_SERVER['HTTP_CLIENT_IP']))
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if(isset($_SERVER['REMOTE_ADDR']))
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return ($ipaddress == "::1" || $ipaddress == "127.0.0.1") ? "localhost" : $ipaddress;
		}
	}