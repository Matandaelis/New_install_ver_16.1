<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geolocation_helper {
    
    private $services = [
        'ipapi_com' => [
            'url' => 'http://ip-api.com/json/{ip}',
            'name' => 'IP-API.com',
            'free_limit' => '1,000 requests/month',
            'status' => 'unknown',
            'last_used' => null,
            'response_time' => null,
            'success_count' => 0,
            'error_count' => 0,
            'total_requests' => 0,
            'daily_usage' => 0,
            'monthly_usage' => 0,
            'last_reset' => null
        ],
        'ipinfo' => [
            'url' => 'https://ipinfo.io/{ip}/json',
            'name' => 'IPInfo.io',
            'free_limit' => '50,000 requests/month',
            'status' => 'unknown',
            'last_used' => null,
            'response_time' => null,
            'success_count' => 0,
            'error_count' => 0,
            'total_requests' => 0,
            'daily_usage' => 0,
            'monthly_usage' => 0,
            'last_reset' => null
        ]
    ];
    
    private $current_service = 'ipapi_com';
    private $api_key = null;
    private $service_log = [];
    
    public function __construct() {
        // You can set API key for services that require it
        $this->api_key = null; // Set your API key here if needed
        
        // Load persistent data
        $this->load_persistent_data();
    }
    
    /**
     * Get geolocation information for an IP address
     * 
     * @param string $ip IP address
     * @param string $purpose Type of data needed (location, address, city, state, region, country, countrycode)
     * @param bool $deep_detect Whether to detect real IP if behind proxy
     * @return mixed Geolocation data based on purpose
     */
    public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        
        if (!in_array($purpose, $support)) {
            return false;
        }
        
        if ($ip === NULL) {
            $ip = $this->get_real_ip();
        }
        
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }
        
        // Try services in order of preference (IP-API.com first as it's more reliable)
        $services_to_try = ['ipapi_com', 'ipinfo'];
        
        foreach ($services_to_try as $service) {
            $result = $this->get_location_data($ip, $service);
            if ($result !== false) {
                return $this->format_output($result, $purpose);
            }
        }
        
        return false;
    }
    
    /**
     * Get real IP address
     */
    private function get_real_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }
    
    /**
     * Get location data from specified service
     */
    private function get_location_data($ip, $service) {
        $start_time = microtime(true);
        $url = str_replace('{ip}', $ip, $this->services[$service]['url']);
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; GeolocationHelper/1.0)');
        
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response_time = round((microtime(true) - $start_time) * 1000, 2);
        curl_close($curl);
        
        // Log the attempt
        $this->log_service_attempt($service, $ip, $http_code, $response_time, $response);
        
        // Update usage counters
        $this->update_usage_counters($service);
        
        if ($http_code !== 200 || !$response) {
            $this->services[$service]['status'] = 'error';
            $this->services[$service]['error_count']++;
            
            // Check for rate limiting
            if ($http_code === 429) {
                $this->services[$service]['last_error'] = 'Rate limited - too many requests';
            } else {
                $this->services[$service]['last_error'] = 'HTTP ' . $http_code . ' error';
            }
            
            $this->save_persistent_data();
            return false;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || isset($data['error'])) {
            $this->services[$service]['status'] = 'error';
            $this->services[$service]['error_count']++;
            $this->save_persistent_data();
            return false;
        }
        
        // Success
        $this->services[$service]['status'] = 'success';
        $this->services[$service]['last_used'] = date('Y-m-d H:i:s');
        $this->services[$service]['response_time'] = $response_time;
        $this->services[$service]['success_count']++;
        $this->save_persistent_data();
        
        return $this->normalize_data($data, $service);
    }
    
    /**
     * Normalize data from different services to common format
     */
    private function normalize_data($data, $service) {
        $normalized = array();
        
        switch ($service) {
            case 'ipapi_com':
                $normalized = array(
                    'city' => isset($data['city']) ? $data['city'] : '',
                    'state' => isset($data['regionName']) ? $data['regionName'] : '',
                    'country' => isset($data['country']) ? $data['country'] : '',
                    'country_code' => isset($data['countryCode']) ? $data['countryCode'] : '',
                    'continent' => isset($data['continent']) ? $data['continent'] : '',
                    'continent_code' => isset($data['continentCode']) ? $data['continentCode'] : '',
                    'latitude' => isset($data['lat']) ? $data['lat'] : '',
                    'longitude' => isset($data['lon']) ? $data['lon'] : ''
                );
                break;
                
            case 'ipinfo':
                $normalized = array(
                    'city' => isset($data['city']) ? $data['city'] : '',
                    'state' => isset($data['region']) ? $data['region'] : '',
                    'country' => isset($data['country']) ? $data['country'] : '',
                    'country_code' => isset($data['country']) ? $data['country'] : '',
                    'continent' => isset($data['continent']) ? $data['continent'] : '',
                    'continent_code' => isset($data['continent']) ? $data['continent'] : '',
                    'latitude' => isset($data['loc']) ? explode(',', $data['loc'])[0] : '',
                    'longitude' => isset($data['loc']) ? explode(',', $data['loc'])[1] : ''
                );
                break;
        }
        
        return $normalized;
    }
    
    /**
     * Format output based on purpose
     */
    private function format_output($data, $purpose) {
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica", 
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Oceania",
            "NA" => "North America",
            "SA" => "South America"
        );
        
        switch ($purpose) {
            case "location":
                $id = 0;
                $code = isset($data['country_code']) ? $data['country_code'] : '';
                
                // Get country ID from database if available
                if (strlen($code) == 2) {
                    if (function_exists('get_instance')) {
                        $CI =& get_instance();
                        if (isset($CI->db)) {
                            $country_data = $CI->db->query("SELECT id FROM countries WHERE sortname LIKE '{$code}'")->row();
                            if ($country_data) {
                                $id = $country_data->id;
                            }
                        }
                    }
                }
                
                return array(
                    "city" => $data['city'],
                    "state" => $data['state'],
                    "country" => $data['country'],
                    "country_code" => $data['country_code'],
                    "continent" => isset($continents[strtoupper($data['continent_code'])]) ? $continents[strtoupper($data['continent_code'])] : '',
                    "continent_code" => $data['continent_code'],
                    "id" => $id,
                    "latitude" => isset($data['latitude']) ? $data['latitude'] : '',
                    "longitude" => isset($data['longitude']) ? $data['longitude'] : ''
                );
                
            case "address":
                $address = array($data['country']);
                if (strlen($data['state']) >= 1) {
                    $address[] = $data['state'];
                }
                if (strlen($data['city']) >= 1) {
                    $address[] = $data['city'];
                }
                return implode(", ", array_reverse($address));
                
            case "city":
                return $data['city'];
                
            case "state":
            case "region":
                return $data['state'];
                
            case "country":
                $id = 0;
                $code = isset($data['country_code']) ? $data['country_code'] : '';
                
                if (strlen($code) == 2) {
                    if (function_exists('get_instance')) {
                        $CI =& get_instance();
                        if (isset($CI->db)) {
                            $country_data = $CI->db->query("SELECT id FROM countries WHERE sortname LIKE '{$code}'")->row();
                            if ($country_data) {
                                $id = $country_data->id;
                            }
                        }
                    }
                }
                return $id;
                
            case "countrycode":
                return $data['country_code'];
                
            default:
                return false;
        }
    }
    
    /**
     * Test geolocation service
     */
    public function test_service($ip = '8.8.8.8') {
        $result = $this->ip_info($ip, 'location');
        return $result;
    }
    
    /**
     * Log service attempt for monitoring
     */
    private function log_service_attempt($service, $ip, $http_code, $response_time, $response) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'service' => $service,
            'service_name' => $this->services[$service]['name'],
            'ip' => $ip,
            'http_code' => $http_code,
            'response_time' => $response_time,
            'success' => $http_code === 200,
            'response_preview' => substr($response, 0, 100) . '...'
        ];
        
        $this->service_log[] = $log_entry;
        
        // Keep only last 50 entries to prevent memory issues
        if (count($this->service_log) > 50) {
            array_shift($this->service_log);
        }
    }
    
    /**
     * Get service status and statistics
     */
    public function get_service_status() {
        // Auto-test services if they haven't been tested yet
        $this->auto_test_services_if_needed();
        
        return [
            'services' => $this->services,
            'current_service' => $this->current_service,
            'total_services' => count($this->services),
            'active_services' => count(array_filter($this->services, function($service) {
                return $service['status'] === 'success';
            })),
            'recent_logs' => array_slice($this->service_log, -10) // Last 10 attempts
        ];
    }
    
    /**
     * Auto-test services if they haven't been tested yet
     */
    private function auto_test_services_if_needed() {
        $all_untested = true;
        foreach ($this->services as $service) {
            if ($service['status'] !== 'unknown') {
                $all_untested = false;
                break;
            }
        }
        
        if ($all_untested) {
            // Test all services with a public IP
            $this->test_all_services('8.8.8.8');
        }
    }
    
    /**
     * Get detailed service information for admin panel
     */
    public function get_service_details() {
        // Auto-test services if they haven't been tested yet
        $this->auto_test_services_if_needed();
        
        $details = [];
        
        foreach ($this->services as $key => $service) {
            $details[] = [
                'id' => $key,
                'name' => $service['name'],
                'url' => $service['url'],
                'free_limit' => $service['free_limit'],
                'status' => $service['status'],
                'last_used' => $service['last_used'],
                'response_time' => $service['response_time'],
                'success_count' => $service['success_count'],
                'error_count' => $service['error_count'],
                'total_requests' => $service['total_requests'],
                'daily_usage' => $service['daily_usage'],
                'monthly_usage' => $service['monthly_usage'],
                'last_error' => isset($service['last_error']) ? $service['last_error'] : null,
                'success_rate' => $service['success_count'] + $service['error_count'] > 0 
                    ? round(($service['success_count'] / ($service['success_count'] + $service['error_count'])) * 100, 1) 
                    : 0
            ];
        }
        
        return $details;
    }
    
    /**
     * Test all services and return results
     */
    public function test_all_services($ip = '8.8.8.8') {
        $results = [];
        
        foreach (array_keys($this->services) as $service) {
            $start_time = microtime(true);
            $result = $this->get_location_data($ip, $service);
            $response_time = round((microtime(true) - $start_time) * 1000, 2);
            
            $results[$service] = [
                'service_name' => $this->services[$service]['name'],
                'success' => $result !== false,
                'response_time' => $response_time,
                'data' => $result,
                'status' => $this->services[$service]['status']
            ];
        }
        
        return $results;
    }
    
    
    /**
     * Get usage statistics
     */
    public function get_usage_stats() {
        $stats = [];
        
        foreach ($this->services as $key => $service) {
            $stats[$key] = [
                'name' => $service['name'],
                'free_limit' => $service['free_limit'],
                'total_requests' => $service['total_requests'],
                'daily_usage' => $service['daily_usage'],
                'monthly_usage' => $service['monthly_usage'],
                'success_count' => $service['success_count'],
                'error_count' => $service['error_count'],
                'success_rate' => $service['success_count'] + $service['error_count'] > 0 
                    ? round(($service['success_count'] / ($service['success_count'] + $service['error_count'])) * 100, 1) 
                    : 0,
                'last_used' => $service['last_used'],
                'response_time' => $service['response_time']
            ];
        }
        
        return $stats;
    }
    
    /**
     * Load persistent data from file
     */
    private function load_persistent_data() {
        $data_file = APPPATH . 'cache/geolocation_usage.json';
        
        if (file_exists($data_file)) {
            $data = json_decode(file_get_contents($data_file), true);
            if ($data && isset($data['services'])) {
                foreach ($data['services'] as $key => $service_data) {
                    if (isset($this->services[$key])) {
                        $this->services[$key] = array_merge($this->services[$key], $service_data);
                    }
                }
            }
        }
    }
    
    /**
     * Save persistent data to file
     */
    private function save_persistent_data() {
        $data_file = APPPATH . 'cache/geolocation_usage.json';
        $cache_dir = dirname($data_file);
        
        // Create cache directory if it doesn't exist
        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, 0755, true);
        }
        
        $data = [
            'services' => $this->services,
            'last_updated' => date('Y-m-d H:i:s')
        ];
        
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    /**
     * Update usage counters for a service
     */
    private function update_usage_counters($service) {
        $today = date('Y-m-d');
        $this_month = date('Y-m');
        
        // Initialize last_reset if not set
        if (!$this->services[$service]['last_reset']) {
            $this->services[$service]['last_reset'] = $today;
        }
        
        // Reset daily counters if new day
        if ($this->services[$service]['last_reset'] !== $today) {
            $this->services[$service]['daily_usage'] = 0;
            $this->services[$service]['last_reset'] = $today;
        }
        
        // Reset monthly counters if new month
        if (substr($this->services[$service]['last_reset'], 0, 7) !== $this_month) {
            $this->services[$service]['monthly_usage'] = 0;
        }
        
        // Increment counters
        $this->services[$service]['total_requests']++;
        $this->services[$service]['daily_usage']++;
        $this->services[$service]['monthly_usage']++;
        
        // Save to file
        $this->save_persistent_data();
    }
}
