<?php
error_reporting(0);

include_once 'version.php';
define('__R__', __DIR__);

$ROOTDIR = str_replace(['install'], [''], __DIR__);

if (file_exists($ROOTDIR . '/application/license-easy-universal-client.php')) {
    require_once $ROOTDIR . '/application/license-easy-universal-client.php';
    if (!isset($GLOBALS['aff_license'])) {
        $GLOBALS['aff_license'] = new License_Easy_Universal_Client([
            'product_slug' => 'affiliateporsaas',
            'api_url' => 'https://affiliatepro.org/index.php?rest_route=/license-easy/v1/',
            'product_name' => 'AffiliatePorSaaS'
        ]);
    }
}

$configWriterHelper = $ROOTDIR . '/application/helpers/config_writer_helper.php';
if (file_exists($configWriterHelper)) {
    require_once $configWriterHelper;
}

if (!function_exists('license_easy_install_request')) {
    function license_easy_install_request($endpoint, $payload = array()) {
        $endpoint = ltrim($endpoint, '/');
        $url = 'https://affiliatepro.org/index.php?rest_route=/license-easy/v1/' . $endpoint;

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_TIMEOUT => 20,
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            return false;
        }

        $decoded = json_decode($response, true);
        return is_array($decoded) ? $decoded : false;
    }
}


function checkIsInstall(){
    $ROOTDIR = str_replace(['install'], [''], __DIR__);
    if(!defined('BASEPATH')){
        define('BASEPATH', $ROOTDIR);
    }
    $dir = $ROOTDIR . '/application/config/database.php';
    include $dir;

    return isset($db['default']['database']);
}



// Memory and File Size Functions
if (!function_exists('check_limit')) {
    function check_limit() {
        $memory_limit = ini_get('memory_limit');
        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'G') {
                $memory_limit = $matches[1] . ' ' . 'GB';
            } else if ($matches[2] == 'M') {
                $memory_limit = $matches[1] . ' ' . 'MB';
            } else if ($matches[2] == 'K') {
                $memory_limit = $matches[1] . ' ' . 'KB';
            } else if ($matches[2] == 'T') {
                $memory_limit = $matches[1] . ' ' . 'TB';
            } else if ($matches[2] == 'P') {
                $memory_limit = $matches[1] . ' ' . 'PB';
            }
        }
        return $memory_limit;
    }
}

if (!function_exists('format_php_size')) {
    function format_php_size($size) {
        if (!is_numeric($size)) {
            if (strpos($size, 'M') !== false) {
                $size = intval($size) * 1024 * 1024;
            } elseif (strpos($size, 'K') !== false) {
                $size = intval($size) * 1024;
            } elseif (strpos($size, 'G') !== false) {
                $size = intval($size) * 1024 * 1024 * 1024;
            }
        }

        return is_numeric($size) ? format_filesize($size) : $size;
    }
}

if (!function_exists('format_filesize')) {
    function format_filesize($bytes) {
        if (($bytes / pow(1024, 5)) > 1) {
            return number_format(($bytes / pow(1024, 5)), 0) . ' ' . 'PB';
        } elseif (($bytes / pow(1024, 4)) > 1) {
            return number_format(($bytes / pow(1024, 4)), 0) . ' ' . 'TB';
        } elseif (($bytes / pow(1024, 3)) > 1) {
            return number_format(($bytes / pow(1024, 3)), 0) . ' ' . 'GB';
        } elseif (($bytes / pow(1024, 2)) > 1) {
            return number_format(($bytes / pow(1024, 2)), 0) . ' ' . 'MB';
        } elseif ($bytes / 1024 > 1) {
            return number_format($bytes / 1024, 0) . ' ' . 'KB';
        } elseif ($bytes >= 0) {
            return number_format($bytes, 0) . ' ' . 'bytes';
        } else {
            return 'Unknown';
        }
    }
}

if (!function_exists('format_filesize_kB')) {
    function format_filesize_kB($kiloBytes) {
        if (($kiloBytes / pow(1024, 4)) > 1) {
            return number_format(($kiloBytes / pow(1024, 4)), 0) . ' ' . 'PB';
        } elseif (($kiloBytes / pow(1024, 3)) > 1) {
            return number_format(($kiloBytes / pow(1024, 3)), 0) . ' ' . 'TB';
        } elseif (($kiloBytes / pow(1024, 2)) > 1) {
            return number_format(($kiloBytes / pow(1024, 2)), 0) . ' ' . 'GB';
        } elseif (($kiloBytes / 1024) > 1) {
            return number_format($kiloBytes / 1024, 0) . ' ' . 'MB';
        } elseif ($kiloBytes >= 0) {
            return number_format($kiloBytes / 1, 0) . ' ' . 'KB';
        } else {
            return 'Unknown';
        }
    }
}

if (!function_exists('return_bytes')) {
    function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = intval($val);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}

// PHP Configuration Functions
if (!function_exists('php_max_upload_size')) {
    function php_max_upload_size() {
        if (ini_get('upload_max_filesize')) {
            $php_max_upload_size = ini_get('upload_max_filesize');
            return format_php_size($php_max_upload_size);
        } else {
            return 'N/A';
        }
    }
}

if (!function_exists('php_max_post_size')) {
    function php_max_post_size() {
        if (ini_get('post_max_size')) {
            $php_max_post_size = ini_get('post_max_size');
            return format_php_size($php_max_post_size);
        }
        return 'N/A';
    }
}

if (!function_exists('php_max_execution_time')) {
    function php_max_execution_time() {
        if (ini_get('max_execution_time')) {
            return ini_get('max_execution_time');
        }
        return 'N/A';
    }
}

// Database Functions
if (!function_exists('database_software')) {
    function database_software($con = false) {
        if (function_exists('get_instance')) {
            $ci =& get_instance();
            $ci->load->database();

            $query = $ci->db->query("SHOW VARIABLES LIKE 'version_comment'");
            $db_software_dump = $query->row()->Value;

            if (!empty($db_software_dump)) {
                $db_soft_array = explode(" ", trim($db_software_dump));
                return $db_soft_array[0];
            }
        } else {
            if ($con) {
                $db = mysqli_query($con, "SHOW VARIABLES LIKE 'version_comment'");
                if ($db) {
                    $db_software_dump = $db->fetch_assoc();
                    if (!empty($db_software_dump)) {
                        $db_soft_array = explode(" ", trim($db_software_dump['Value']));
                        return $db_soft_array[0];
                    }
                }
            }
        }
        return 'N/A';
    }
}

if (!function_exists('database_version')) {
    function database_version($con = false) {
        if (function_exists('get_instance')) {
            $ci =& get_instance();
            $ci->load->database();

            $query = $ci->db->query("SELECT VERSION() AS version from DUAL");
            $db_software_dump = $query->row()->version;

            if (preg_match('/\d+(?:\.\d+)+/', $db_software_dump, $matches)) {
                return $matches[0];
            }
        } else {
            if ($con) {
                $db = mysqli_query($con, "SELECT VERSION() AS version from DUAL");
                if ($db) {
                    $db_software_dump = $db->fetch_assoc();
                    if (preg_match('/\d+(?:\.\d+)+/', $db_software_dump['version'], $matches)) {
                        return $matches[0];
                    }
                }
            }
        }
        return 'N/A';
    }
}

// System Information Functions
if (!function_exists('server_os')) {
    function server_os() {
        $os = php_uname('s');
        
        // Simplify common OS names
        if (stripos($os, 'windows') !== false) {
            return 'Windows';
        } elseif (stripos($os, 'linux') !== false) {
            return 'Linux';
        } elseif (stripos($os, 'darwin') !== false) {
            return 'macOS';
        } elseif (stripos($os, 'freebsd') !== false) {
            return 'FreeBSD';
        } elseif (stripos($os, 'unix') !== false) {
            return 'Unix';
        }
        
        // Fallback to original if not recognized
        return $os;
    }
}

if (!function_exists('check_server_ip')) {
    function check_server_ip() {
        return $_SERVER['SERVER_ADDR'] ?? ($_SERVER['LOCAL_ADDR'] ?? 'Unknown');
    }
}

if (!function_exists('is_ssl')) {
    function is_ssl() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || $_SERVER['SERVER_PORT'] == 443
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on');
    }
}

// Main Requirements Check Function
if (!function_exists('checkReq')) {
    function checkReq() {
        $errors = [];
        
        // PHP version check
        $current_php = phpversion();
        $required_php = '7.4';
        if (version_compare($current_php, $required_php, '<')) {
            $errors['php'] = "Warning: PHP version {$required_php} or above is required. Current: {$current_php}";
        }
        
        // Required extensions
        $required_extensions = [
            'mysqli' => 'MySQLi extension needs to be loaded.',
            'curl' => 'CURL extension needs to be loaded.',
            'zip' => 'Zip extension needs to be installed.',
        ];
        
        foreach ($required_extensions as $ext => $message) {
            if (!extension_loaded($ext)) {
                $errors[$ext] = "Warning: {$message}";
            }
        }

        // SSL Status check
        if (!is_ssl()) {
            $errors['ssl'] = 'Notice: SSL is not enabled. Consider enabling HTTPS for security.';
        }
        
        // GD Library check
        if (!extension_loaded('gd')) {
            $errors['gd'] = 'Warning: GD Library extension needs to be loaded.';
        }
        
        // ZipArchive check
        if (!class_exists('ZipArchive')) {
            $errors['ziparchive'] = 'Warning: ZipArchive class needs to be available.';
        }
        
        // IP Geolocation API test
        if (extension_loaded('curl')) {
            $ip = $_SERVER["REMOTE_ADDR"] ?? '8.8.8.8';
            
            // Use a public IP for testing if we're on localhost
            if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
                $ip = '8.8.8.8';
            }
            
            // Test ip-api.com (primary service - more reliable)
            $ch = curl_init("http://ip-api.com/json/{$ip}");
            if ($ch !== false) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; SystemCheck/1.0)');
                
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($response === false || $http_code !== 200) {
                    // Try fallback service (ipinfo.io)
                    $ch2 = curl_init("https://ipinfo.io/{$ip}/json");
                    if ($ch2 !== false) {
                        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);
                        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; SystemCheck/1.0)');
                        
                        $response2 = curl_exec($ch2);
                        $http_code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                        curl_close($ch2);
                        
                        if ($response2 === false || $http_code2 !== 200) {
                            $errors['ipapi'] = 'Warning: IP Geolocation API connectivity test failed.';
                        } else {
                            $ipdata2 = json_decode($response2, true);
                            if (!is_array($ipdata2) || (!isset($ipdata2['country']) && !isset($ipdata2['countryCode']))) {
                                $errors['ipapi'] = 'Warning: IP Geolocation API response invalid.';
                            }
                        }
                    } else {
                        $errors['ipapi'] = 'Warning: CURL initialization failed.';
                    }
                } else {
                    $ipdata = json_decode($response, true);
                    if (!is_array($ipdata) || (!isset($ipdata['country']) && !isset($ipdata['status']))) {
                        $errors['ipapi'] = 'Warning: IP Geolocation API response invalid.';
                    } elseif (isset($ipdata['status']) && $ipdata['status'] !== 'success') {
                        $errors['ipapi'] = 'Warning: IP Geolocation API returned error: ' . ($ipdata['message'] ?? 'Unknown error');
                    }
                }
            } else {
                $errors['ipapi'] = 'Warning: CURL initialization failed.';
            }
        }
        
        // GZIP compression check
        if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) || 
            strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === false) {
            $errors['gzip'] = 'Warning: Enable Gzip compression for better performance.';
        }
        
        // PHP configuration checks
        if (!ini_get('allow_url_fopen')) {
            $errors['allow_url_fopen'] = 'Warning: Enable allow_url_fopen for integration scripts.';
        }
        
        // max_input_vars check
        $max_input_vars = ini_get('max_input_vars');
        $required_input_vars = 5000;
        if ($max_input_vars < $required_input_vars) {
            $errors['max_input_vars'] = "Warning: max_input_vars should be at least {$required_input_vars}. Current: {$max_input_vars}";
        }
        
        // upload_max_filesize check
        $upload_max = ini_get('upload_max_filesize');
        $required_upload = '128M';
        if ($upload_max) {
            $upload_bytes = return_bytes($upload_max);
            $required_bytes = 128 * 1024 * 1024; // 128MB
            if ($upload_bytes < $required_bytes) {
                $current_formatted = php_max_upload_size(); // Use the same function as view
                $errors['upload_max_filesize'] = "Warning: upload_max_filesize should be at least {$required_upload}. Current: {$current_formatted}";
            }
        }

        // post_max_size check
        $post_max = ini_get('post_max_size');
        $required_post = '128M';
        if ($post_max) {
            $post_bytes = return_bytes($post_max);
            $required_bytes = 128 * 1024 * 1024; // 128MB
            if ($post_bytes < $required_bytes) {
                $current_formatted = php_max_post_size(); // Use the same function as view
                $errors['post_max_size'] = "Warning: post_max_size should be at least {$required_post}. Current: {$current_formatted}";
            }
        }
        
        // Directory permissions check – auto-create missing dirs to reduce buyer stress
        $base = str_replace('install', '', __DIR__);
        $check_dirs = [
            'application/session',
            'application/backup/mysql',
            'application/downloads',
            'application/config',
            'application/language',
            'application/cache',
            'application/market_cache',
            'application/downloads_order',
            'assets/images/site',
            'assets/user_upload',
        ];

        foreach ($check_dirs as $dir) {
            $full_path = rtrim($base, '/') . '/' . $dir;
            if (!is_dir($full_path)) {
                if (@mkdir($full_path, 0755, true)) {
                    continue;
                }
                $errors['writable'] = "Warning: Directory {$dir} does not exist and could not be created. Create it manually and set permissions to 755.";
                break;
            } elseif (!is_writable($full_path)) {
                $errors['writable'] = "Warning: {$dir} is not writable. Set proper permissions (755 or 777).";
                break;
            }
        }
	
	return $errors;
	}
}

/**
 * Get geolocation data for installation page
 */
function getInstallationGeoData() {
	$ip = $_SERVER["REMOTE_ADDR"];
	if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
		$ip = $_SERVER['HTTP_CLIENT_IP'];

	// Use a public IP for testing if we're on localhost
	if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0 || strpos($ip, '172.') === 0) {
		$ip = '8.8.8.8'; // Google DNS - good for testing
	}

	// Try IP-API.com first (primary service)
	$curl = curl_init("http://ip-api.com/json/{$ip}");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_TIMEOUT, 5);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; InstallHelper/1.0)');
	
	$response = curl_exec($curl);
	$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	
	$ipdat = null;
	$service = 'Unknown';
	
	if ($http_code === 200 && $response) {
		$ipdat = json_decode($response, true);
		if ($ipdat && isset($ipdat['country'])) {
			$service = 'IP-API.com';
		}
	}
	
	// If IP-API.com fails, try IPInfo.io as fallback
	if (!$ipdat || !isset($ipdat['country'])) {
		$curl2 = curl_init("https://ipinfo.io/{$ip}/json");
		curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl2, CURLOPT_HEADER, false);
		curl_setopt($curl2, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl2, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; InstallHelper/1.0)');
		
		$response2 = curl_exec($curl2);
		$http_code2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
		curl_close($curl2);
		
		if ($http_code2 === 200 && $response2) {
			$ipdat = json_decode($response2, true);
			if ($ipdat && isset($ipdat['country'])) {
				$service = 'IPInfo.io';
				// Normalize IPInfo.io response to match IP-API.com format
				$ipdat = array(
					'city' => $ipdat['city'] ?? '',
					'regionName' => $ipdat['region'] ?? '',
					'country' => $ipdat['country'] ?? '',
					'countryCode' => $ipdat['country'] ?? '',
					'continentCode' => $ipdat['continent'] ?? ''
				);
			}
		}
	}
	
	if ($ipdat && isset($ipdat['country'])) {
		return array(
			'success' => true,
			'country' => $ipdat['country'],
			'city' => $ipdat['city'] ?? '',
			'state' => $ipdat['regionName'] ?? '',
			'country_code' => $ipdat['countryCode'] ?? '',
			'service' => $service
		);
	}
	
	return array('success' => false);
}


if (!function_exists('getExistingDbConfig')) {
    function getExistingDbConfig() {
        try {
            $ROOTDIR = str_replace(['install'], [''], __DIR__);
            $configFile = $ROOTDIR . '/application/config/database.php';
            
            if (file_exists($configFile)) {
                // Read the file content and parse it manually to avoid BASEPATH check
                $content = file_get_contents($configFile);
                
                // Extract database configuration using regex (only uncommented lines)
                $config = [];
                if (preg_match('/^[^\/]*\$db\["default"\]\["hostname"\]\s*=\s*"([^"]*)"/m', $content, $matches)) {
                    $config['hostname'] = $matches[1];
                }
                if (preg_match('/^[^\/]*\$db\["default"\]\["username"\]\s*=\s*"([^"]*)"/m', $content, $matches)) {
                    $config['username'] = $matches[1];
                }
                if (preg_match('/^[^\/]*\$db\["default"\]\["password"\]\s*=\s*"([^"]*)"/m', $content, $matches)) {
                    $config['password'] = $matches[1];
                }
                if (preg_match('/^[^\/]*\$db\["default"\]\["database"\]\s*=\s*"([^"]*)"/m', $content, $matches)) {
                    $config['database'] = $matches[1];
                }
                if (preg_match('/^[^\/]*\$db\["default"\]\["dbport"\]\s*=\s*"([^"]*)"/m', $content, $matches)) {
                    $config['port'] = $matches[1];
                }
                
                if (!empty($config['hostname'])) {
                    return [
                        'hostname' => $config['hostname'] ?? 'localhost',
                        'username' => $config['username'] ?? '',
                        'password' => $config['password'] ?? '',
                        'database' => $config['database'] ?? '',
                        'port' => $config['port'] ?? '3306'
                    ];
                }
            }
        } catch (Exception $e) {
            return false;
        }
        
        return false;
    }
}

if (!function_exists('getExistingLicenseConfig')) {
    function getExistingLicenseConfig() {
        try {
            $ROOTDIR = str_replace(['install'], [''], __DIR__);
            $versionFile = $ROOTDIR . '/install/version.php';
            $licenseFile = $ROOTDIR . '/application/license-easy-data-affiliateporsaas.json';

            if (!file_exists($versionFile) || !file_exists($licenseFile)) {
                return false;
            }

            include $versionFile;
            $storedLicense = defined('CODECANYON_LICENCE') ? CODECANYON_LICENCE : '';

            $localData = json_decode(file_get_contents($licenseFile), true);
            $licenseKey = $storedLicense ?: ($localData['license_key'] ?? '');

            if (!$licenseKey) {
                return false;
            }

            $details = license_easy_install_request('validate', array(
                'purchase_code' => $licenseKey,
                'product_slug' => 'affiliateporsaas',
                'domain' => getBaseUrl()
            ));

            if (!is_array($details)) {
                $details = array();
            }

            return [
                'purchase_code' => $licenseKey,
                'email' => '',
                'username' => isset($details['buyer']) ? $details['buyer'] : 'admin',
                'license_type' => isset($details['license_type']) ? $details['license_type'] : '',
                'purchase_date' => isset($details['purchase_date']) ? $details['purchase_date'] : '',
                'support_until' => isset($details['supported_until']) ? $details['supported_until'] : '',
                'purchase_count' => isset($details['purchase_count']) ? $details['purchase_count'] : 1,
                'amount' => isset($details['amount']) ? $details['amount'] : '',
                'item_name' => isset($details['item_name']) ? $details['item_name'] : '',
                'author_username' => isset($details['author_username']) ? $details['author_username'] : '',
                'version' => defined('SCRIPT_VERSION') ? SCRIPT_VERSION : ''
            ];
        } catch (Exception $e) {
            return false;
        }
    }
}

// Legacy Envato helper functions removed (License Easy now handles license metadata)

function base_path($remove = ''){  
    $root=(isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
    $root.= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    
    // FIX: Clean up multiple /install/ before removing
    $root = preg_replace('/\/install\/+/', '/install/', $root);
    
    return str_replace($remove, '', trim($root,'/'));
}

function getBaseUrl($remove = true) { 
    $url = base_path();
    if($remove) $url = str_replace(basename($url),"",$url);
    return trim(str_replace('/install','',$url),"/");
}

function root_url(){
    $root_url = strtok(trim(str_replace('/install', '', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']),"/"),"?");
    $root_url = str_replace("proccess.php","", $root_url);
    $root_url = trim( $root_url,"/");
    $root_url = trim(str_replace(['https','http',':','//','www.','index.php','helper.php'],['','','','','','',''],$root_url),"/");

    return trim($root_url,"/");
}

// Helper function for converting PHP size values to bytes
if (!function_exists('return_bytes')) {
    function return_bytes($size_str) {
        if (empty($size_str)) return 0;
        
        $size_str = trim($size_str);
        $last = strtolower($size_str[strlen($size_str) - 1]);
        $size_str = (int) $size_str;
        
        switch($last) {
            case 'g': $size_str *= 1024;
            case 'm': $size_str *= 1024;
            case 'k': $size_str *= 1024;
        }
        
        return $size_str;
    }
}


function view($file, $data = array()){
    ob_start();
    $safe_keys = array_diff_key($data, array_flip(['file', 'data', 'file_path', 'include']));
    extract($safe_keys);
    include $file.".php";
    $output = ob_get_contents();
    ob_clean();
    return $output;
}


if(!function_exists('is_ssl')){
    function is_ssl() {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( 'on' == strtolower($_SERVER['HTTPS']) )
                return true;
            if ( '1' == $_SERVER['HTTPS'] )
                return true;
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        }
        return false;
    }
}

if(!function_exists('phpinfo_array')){
    function phpinfo_array($return=false){
        ob_start(); 
        phpinfo(-1);

        $pi = preg_replace(
            array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
                '#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
                "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
                '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
                .'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
                '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
                '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
                "# +#", '#<tr>#', '#</tr>#'),
            array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
              '<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
              "\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
              '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
              '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
              '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
            ob_get_clean());

        $sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
        unset($sections[0]);

        $pi = array();
        foreach($sections as $section){
         $n = substr($section, 0, strpos($section, '</h2>'));
         preg_match_all(
             '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
             $section, $askapache, PREG_SET_ORDER);
         foreach($askapache as $m)
             $pi[$n][$m[1]]=(!isset($m[3])||$m[2]==$m[3])?$m[2]:array_slice($m,2);
     }

     return ($return === false) ? print_r($pi) : $pi;
 }
}

function b2o($string, $action = 'e')
{
    if ($string === null) {
        return false;
    }

    $secret_key = '()*()*)@)((@&*&*&$';
    $secret_iv = '@%^%^^*&#^(@)(_)($)($*)(@&*)&)';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'e') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'd') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}


function clear_session(){
    $session_path = str_replace(['install'], ['application/session'], __DIR__);
    $files = glob($session_path.'/*');
    foreach($files as $file){
        if(is_file($file)) unlink($file);
    }
}

if(isset($_GET['call']) && $_GET['call'] === 'optimizeDB') optimizeDB();

function ___construct($rr=0){
    return session_prepares($rr);
}

function optimizeDB(){
    $j = ___construct(1,1,1);
    echo json_encode($j);die;
}

function session_get(){
    $session_file = b2o(__R__,'e');
    $session_key = b2o($key,'e');
    $session_path = str_replace(['install'], ['application/session'], __DIR__);

    if(file_exists($session_path."/".$session_file)){
        $data= unserialize(b2o(file_get_contents($session_path."/".$session_file),'d'));
        if(!defined('SCRIPT_VERSION')){
            require_once 'version.php';
        }

        $data['version'] = SCRIPT_VERSION;

        echo json_encode($data);
    }
}


function session_prepares($rd=0){
    $session_file = b2o(__R__,'e');
    $session_path = str_replace(['install'], ['application/session'], __DIR__);

    $HTTPSurl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? "https" : "http";
    $rootURL = "{$HTTPSurl}://".$_SERVER['HTTP_HOST'];
    $rootURL .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
    
    if(file_exists($session_path."/".$session_file)){
        $content = unserialize(b2o(file_get_contents($session_path."/".$session_file),'d'));
        if(isset($content['path']) && $content['path'] == __R__){
            return 1;
        }
    }

    if($rd){
        $currentURL = $_SERVER['REQUEST_URI'];
        
        // Prevent infinite redirects by checking if we're already in install directory
        if(strpos($currentURL, '/install/') !== false) {
            return 0;
        }
        
        // Build clean install URL
        $baseURL = $HTTPSurl . '://' . $_SERVER['HTTP_HOST'];
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $installURL = $baseURL . $scriptDir . '/install/index.php';
        
        header("location: {$installURL}");
        die;
    }
    return 0;
}


function isLocalHost(){
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );

    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

function updateVersiontoserver($version, $codecanyon_licence){
    // Legacy endpoint removed. Function kept for backward compatibility.
    return true;
}

function installScript($_data){
    $json = array();
    $root_url = root_url();
    $base_url = getBaseUrl();
    $ROOTDIR = str_replace(['install'], [''], __DIR__);
    
    if (!isset($GLOBALS['aff_license'])) {
        $json['errors']['purchase_code'] = "License Easy client not loaded";
        return $json;
    }
    
    $website_url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    if (isset($_SERVER['REQUEST_SCHEME']) && isset($_SERVER['HTTP_HOST'])) {
        $website_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    }

    $envato_valid = _installer_verify_envato_purchase($_data['purchase_code']);
    if ($envato_valid !== true) {
        $json['errors']['purchase_code'] = $envato_valid;
        return $json;
    }
    
    $activation_result = $GLOBALS['aff_license']->activate_license($_data['purchase_code']);
    
    if (!$activation_result || !isset($activation_result['success']) || !$activation_result['success']) {
        $error_msg = isset($activation_result['message']) ? $activation_result['message'] : 'License activation failed';
        $json['errors']['purchase_code'] = $error_msg;
        return $json;
    }
    
    $response = array('success' => 1);

    if ($response && is_array($response) && (int)$response['success'] == 1) {
        $output = '<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");' . "\n";
        $output .= '$db["default"]["hostname"] = "' . $_data['db_hostname'] . '";' . "\n";
        $output .= '$db["default"]["username"] = "' . $_data['db_username'] . '";' . "\n";
        $output .= '$db["default"]["password"] = ';
        $output .= "'".$_data["db_password"]."';". "\n";
        $output .= '$db["default"]["database"] = "' . $_data['db_database'] . '";' . "\n";
        $output .= '$db["default"]["dbport"] = "' . $_data['db_port'] . '";' . "\n";
        $output .= '$db["default"]["dbdriver"] = "mysqli";' . "\n";
        $output .= '$db["default"]["dbprefix"] = "";' . "\n";
        $output .= '$db["default"]["pconnect"] = FALSE;' . "\n";
        $output .= '$db["default"]["db_debug"] = TRUE;' . "\n";
        $output .= '$db["default"]["cache_on"] = FALSE;' . "\n";
        $output .= '$db["default"]["stricton"] = FALSE;' . "\n";
        $output .= '$db["default"]["cachedir"] = "";' . "\n";
        $output .= '$db["default"]["char_set"] = "utf8";' . "\n";
        $output .= '$db["default"]["dbcollat"] = "utf8_general_ci";' . "\n";
        $output .= '$active_group = "default";' . "\n";
        $output .= '$active_record = TRUE;' . "\n";

        $dir = $ROOTDIR . '/application/config/database.php';
        
        $sql_file = $ROOTDIR . '/install/database.sql';
        $databse_sql = '';
        if (file_exists($sql_file)) {
            $databse_sql = file_get_contents($sql_file);
        }

        $con = mysqli_connect($_data['db_hostname'], $_data['db_username'], $_data['db_password'], $_data['db_database'], $_data['db_port']);

        $file = fopen($dir, 'w');
        fwrite($file, $output);
        fclose($file);

        $res = mysqli_query($con, "SHOW TABLES");
        if (mysqli_num_rows($res) == 0) {
            $lines = explode("\n", $databse_sql);
            $sql_query = '';
            foreach($lines as $line) {
                if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                    $sql_query .= $line;
                    if (preg_match('/;\s*$/', $line)) {
                        mysqli_query($con, $sql_query);
                        $sql_query = '';
                    }
                }
            }
        }
        
        $ip = $_SERVER["REMOTE_ADDR"];
        if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
            $ip = $_SERVER['HTTP_CLIENT_IP'];

        // Try IP-API.com first (primary service)
        $curl = curl_init("http://ip-api.com/json/{$ip}");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; InstallHelper/1.0)');
        
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        $ipdat = null;
        if ($http_code === 200 && $response) {
            $ipdat = json_decode($response, true);
        }
        
        // If IP-API.com fails, try IPInfo.io as fallback
        if (!$ipdat || !isset($ipdat['country'])) {
            $curl2 = curl_init("https://ipinfo.io/{$ip}/json");
            curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl2, CURLOPT_HEADER, false);
            curl_setopt($curl2, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl2, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; InstallHelper/1.0)');
            
            $response2 = curl_exec($curl2);
            $http_code2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
            curl_close($curl2);
            
            if ($http_code2 === 200 && $response2) {
                $ipdat = json_decode($response2, true);
                // Normalize IPInfo.io response to match IP-API.com format
                if ($ipdat) {
                    $ipdat = array(
                        'city' => $ipdat['city'] ?? '',
                        'regionName' => $ipdat['region'] ?? '',
                        'country' => $ipdat['country'] ?? '',
                        'countryCode' => $ipdat['country'] ?? '',
                        'continentCode' => $ipdat['continent'] ?? '',
                        'lat' => isset($ipdat['loc']) ? explode(',', $ipdat['loc'])[0] : '',
                        'lon' => isset($ipdat['loc']) ? explode(',', $ipdat['loc'])[1] : ''
                    );
                }
            }
        }
        
        $sys_country_code = null;
        if ($ipdat && isset($ipdat['country'])) {
            $country_name = $ipdat['country'];
            $country = mysqli_query($con, "select id from countries where name='".mysqli_real_escape_string($con, $country_name)."'");
            while($code = $country->fetch_assoc()) {
                $sys_country_code = $code['id'];
            }
        }

        $output = array(
            "city"             => $ipdat['city'] ?? '',
            "state"            => $ipdat['regionName'] ?? '',
            "country"          => $ipdat['country'] ?? '',
            "country_code"     => $ipdat['countryCode'] ?? '',
            "continent"        => isset($ipdat['continentCode']) ? @$continents[strtoupper($ipdat['continentCode'])] : '',
            "continent_code"   => $ipdat['continentCode'] ?? '',
            "sys_country_code" => $sys_country_code
        );

        $admin_email = isset($_data['email']) && $_data['email'] !== '' ? filter_var($_data['email'], FILTER_VALIDATE_EMAIL) : 'admin@example.com';
        if (!$admin_email) $admin_email = 'admin@example.com';
        $admin_email_escaped = mysqli_real_escape_string($con, $admin_email);
        $country_escaped = mysqli_real_escape_string($con, $output['sys_country_code']);
        $city_escaped = mysqli_real_escape_string($con, $output['city']);
        $sql_query = 'UPDATE users SET email="'.$admin_email_escaped.'", Country="'.$country_escaped.'", City="'.$city_escaped.'" WHERE type="admin"';
        mysqli_query($con, $sql_query);
        

        $dir = $ROOTDIR . '/application/config/config.php';
        $handle = fopen($dir, "r");
        $ci_config = '$config[\'base_url\']';
        $new_congif = '';
        $len = strlen($ci_config);

        if ($handle) {
            $found = false;
            while (($line = fgets($handle)) !== false) {
                if (!$found && strpos($line, $ci_config) !== false) {
                    $found = true;
                    $line = '$config[\'base_url\']  = \''. getBaseUrl() .'\';/*';
                }
                $new_congif .= PHP_EOL. $line;
            }
            fclose($handle);

            $new_congif = preg_replace("/[\r\n]+/", "\n", $new_congif);
            $new_congif = trim($new_congif);
            file_put_contents($dir, $new_congif);
        }

        update_config_option('codecanyon_license', $_data['purchase_code']);
        update_config_option('license_easy_key', $_data['purchase_code']);
        
        // Don't require old version.php - we're about to overwrite it
        // require_once 'version.php';
        
        $version = "<?php\n";
        $version .= "\$script_version = '0.0.0';\n";
        $version .= "\$config_path = dirname(__DIR__) . '/application/config/config.php';\n";
        $version .= "if (file_exists(\$config_path)) {\n";
        $version .= "    \$config_content = file_get_contents(\$config_path);\n";
        $version .= "    if (preg_match(\"/\\$config\\['app_version'\\]\\s*=\\s*['\\\"]([^'\\\"]+)['\\\"]/\", \$config_content, \$matches)) {\n";
        $version .= "        \$script_version = \$matches[1];\n";
        $version .= "    }\n";
        $version .= "}\n";
        $version .= "if (!defined('SCRIPT_VERSION')) define('SCRIPT_VERSION', \$script_version);\n";
        $version .= "if (!defined('CODECANYON_LICENCE')) define('CODECANYON_LICENCE', '". $_data['purchase_code'] ."');\n";
        $version .= "if (!defined('LICENSE_EASY_KEY')) define('LICENSE_EASY_KEY', CODECANYON_LICENCE);\n";

        file_put_contents($ROOTDIR."/install/version.php", $version);
        $json['success'] = true;

        $session_path = str_replace(['install'], ['application/session'], __DIR__);
        if (!file_exists($session_path)) {
            mkdir($session_path, 0777, true);
        }
        $session_file = b2o(__R__,'e');
        $session_key = b2o($key,'e');
        $session_data['key'] = $_data['purchase_code'];
        $session_data['path'] = __R__;

        clear_session();
        file_put_contents($session_path."/".$session_file, b2o(serialize($session_data),'e') .PHP_EOL , FILE_APPEND | LOCK_EX);

        $cache_dir = $ROOTDIR . '/application/cache';
        if (!is_dir($cache_dir)) {
            @mkdir($cache_dir, 0755, true);
        }
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        @file_put_contents($cache_dir . '/license_check.json', json_encode([
            'last_check'  => time(),
            'domain'      => $host,
            'status'      => 'valid',
            'license_key' => $_data['purchase_code'],
        ], JSON_PRETTY_PRINT), LOCK_EX);
    } else {
        // Handle different types of error responses
        if (is_string($response)) {
            $json['errors']['purchase_code'] = 'API Error: ' . $response;
        } elseif (is_array($response) && isset($response['error'])) {
            $json['errors']['purchase_code'] = $response['error'];
        } else {
            $json['errors']['purchase_code'] = 'Unknown Error: Invalid API response format';
        }
    }

    return $json;
}

/**
 * Establish database connection using default configuration
 * @return mysqli|false Returns mysqli connection object on success, false on failure
 */
function get_database_connection() {
    // Include database config if not already loaded
    if (!isset($GLOBALS['db'])) {
        require_once BASEPATH . '/application/config/database.php';
        $GLOBALS['db'] = $db;
    }
    
    $db_config = $GLOBALS['db']['default'];
    
    $connection = mysqli_connect(
        $db_config["hostname"],
        $db_config['username'],
        $db_config['password'],
        $db_config['database'],
        $db_config["dbport"]
    );
    
    if (mysqli_connect_errno()) {
        return false;
    }
    
    return $connection;
}

/**
 * Alternative version that accepts custom database config
 * @param array $db_config Database configuration array
 * @return mysqli|false Returns mysqli connection object on success, false on failure
 */
function connect_database($db_config = null) {
    if ($db_config === null) {
        return get_database_connection();
    }
    
    $connection = mysqli_connect(
        $db_config["hostname"],
        $db_config['username'],
        $db_config['password'],
        $db_config['database'],
        $db_config["dbport"] ?? 3306
    );
    
    if (mysqli_connect_errno()) {
        return false;
    }
    
    return $connection;
}

/**
 * Verify a purchase code against the Envato API during installation.
 * Returns true on success, or an error message string on failure.
 *
 * @param  string $purchase_code
 * @return true|string
 */
function _installer_verify_envato_purchase($purchase_code) {
    if (empty($purchase_code)) {
        return 'Purchase code is required.';
    }

    $ROOTDIR = str_replace(['install'], [''], __DIR__);
    $token_file = $ROOTDIR . '/application/config/envato_token.php';
    $envato_token = '';

    if (file_exists($token_file)) {
        @include $token_file;
        if (isset($config['envato_personal_token'])) {
            $envato_token = $config['envato_personal_token'];
        }
    }

    if (empty($envato_token)) {
        return true;
    }

    $url = 'https://api.envato.com/v3/market/author/sale?code=' . urlencode($purchase_code);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $envato_token,
            'User-Agent: AffiliatePro-Installer/1.0',
        ],
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_errno($ch);
    curl_close($ch);

    if ($curl_error || $http_code >= 500) {
        return true;
    }

    if ($http_code === 404) {
        return 'Invalid purchase code. Please enter a valid Envato/CodeCanyon purchase code.';
    }

    if ($http_code !== 200 || !$response) {
        return true;
    }

    $data = @json_decode($response, true);
    if (!is_array($data)) {
        return true;
    }

    if (isset($data['item']) && isset($data['item']['id'])) {
        return true;
    }

    return 'Purchase code could not be verified. Please check your purchase code and try again.';
}