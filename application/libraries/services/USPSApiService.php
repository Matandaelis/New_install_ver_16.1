<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class USPSApiService {
    
    private $api_url = 'http://production.shippingapis.com/ShippingAPI.dll';
    private $api_key = '';
    private $origin_zip = '';
    private $ci;
    
    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->model('Product_model');
        
        // Load USPS settings
        $settings = $this->ci->Product_model->getSettings('shipping_setting');
        $this->api_key = isset($settings['usps_api_key']) ? $settings['usps_api_key'] : '';
        $this->origin_zip = isset($settings['usps_origin_zip']) ? $settings['usps_origin_zip'] : '';
    }
    
    /**
     * Get USPS shipping rates (now with dimensions)
     * @param string $dest_zip Destination ZIP code
     * @param float $weight Package weight in pounds
     * @param float $length Package length in inches
     * @param float $width Package width in inches
     * @param float $height Package height in inches
     * @param string $service Service type (Priority, First-Class, etc.)
     * @return array|false Rate information or false on error
     */
    public function getRate($dest_zip, $weight, $length = 6, $width = 6, $height = 6, $service = 'Priority') {
        error_log("USPS Service Debug: getRate called with ZIP: $dest_zip, Weight: $weight, L:$length W:$width H:$height, Service: $service");
        error_log("USPS Service Debug: API Key: " . substr($this->api_key, 0, 10) . "...");
        error_log("USPS Service Debug: Origin ZIP: $this->origin_zip");
        if (empty($this->api_key) || empty($this->origin_zip)) {
            error_log("USPS Service Debug: USPS not enabled - API key or origin ZIP empty");
            return false;
        }
        if (empty($dest_zip) || empty($weight) || $weight <= 0) {
            error_log("USPS Service Debug: Invalid inputs - ZIP: $dest_zip, Weight: $weight");
            return false;
        }
        if ($this->api_key === '9400100000000000000000') {
            error_log("USPS Service Debug: Using demo mode");
            return $this->getDemoRate($dest_zip, $weight, $length, $width, $height, $service);
        }
        $cache_key = "usps_rate_{$this->origin_zip}_{$dest_zip}_{$weight}_{$length}_{$width}_{$height}_{$service}";
        $cached_rate = $this->getCachedRate($cache_key);
        if ($cached_rate !== false) {
            return $cached_rate;
        }
        $xml = $this->buildRateRequest($dest_zip, $weight, $length, $width, $height, $service);
        $response = $this->makeApiCall($xml);
        if ($response === false) {
            return false;
        }
        $rate = $this->parseRateResponse($response, $service);
        if ($rate !== false) {
            $this->cacheRate($cache_key, $rate);
        }
        return $rate;
    }

    /**
     * Build XML request for USPS API (now with dimensions)
     */
    private function buildRateRequest($dest_zip, $weight, $length = 6, $width = 6, $height = 6, $service = 'Priority') {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<RateV4Request USERID="' . $this->api_key . '">';
        $xml .= '<Revision>2</Revision>';
        $xml .= '<Package ID="1ST">';
        $xml .= '<Service>' . $service . '</Service>';
        $xml .= '<FirstClassMailType>PACKAGE</FirstClassMailType>';
        $xml .= '<ZipOrigination>' . $this->origin_zip . '</ZipOrigination>';
        $xml .= '<ZipDestination>' . $dest_zip . '</ZipDestination>';
        $xml .= '<Pounds>' . floor($weight) . '</Pounds>';
        $xml .= '<Ounces>' . round(($weight - floor($weight)) * 16, 2) . '</Ounces>';
        $xml .= '<Container></Container>';
        $xml .= '<Size>' . ($length > 12 || $width > 12 || $height > 12 ? 'LARGE' : 'REGULAR') . '</Size>';
        $xml .= '<Width>' . $width . '</Width>';
        $xml .= '<Length>' . $length . '</Length>';
        $xml .= '<Height>' . $height . '</Height>';
        $xml .= '<Machinable>TRUE</Machinable>';
        $xml .= '</Package>';
        $xml .= '</RateV4Request>';
        return $xml;
    }
    
    /**
     * Make API call to USPS
     */
    private function makeApiCall($xml) {
        $url = $this->api_url . '?API=RateV4&XML=' . urlencode($xml);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        // Log for debugging
        error_log("USPS API Call - URL: " . $url);
        error_log("USPS API Call - HTTP Code: " . $http_code);
        error_log("USPS API Call - Response: " . $response);
        if ($curl_error) {
            error_log("USPS API Call - CURL Error: " . $curl_error);
        }
        
        if ($http_code !== 200 || empty($response)) {
            return false;
        }
        
        return $response;
    }
    
    /**
     * Parse USPS API response
     */
    private function parseRateResponse($response, $service) {
        $xml = simplexml_load_string($response);
        
        if ($xml === false) {
            error_log("USPS API - Failed to parse XML response");
            return false;
        }
        
        // Check for errors
        if (isset($xml->Package->Error)) {
            $error_desc = isset($xml->Package->Error->Description) ? (string)$xml->Package->Error->Description : 'Unknown error';
            error_log("USPS API Error: " . $error_desc);
            return false;
        }
        
        // Extract rate
        if (isset($xml->Package->Postage->Rate)) {
            $rate = (float)$xml->Package->Postage->Rate;
            $delivery_days = isset($xml->Package->Postage->CommitmentDate) ? 
                $this->calculateDeliveryDays($xml->Package->Postage->CommitmentDate) : 0;
            
            return [
                'rate' => $rate,
                'service' => $service,
                'delivery_days' => $delivery_days,
                'currency' => 'USD'
            ];
        }
        
        error_log("USPS API - No rate found in response");
        return false;
    }
    
    /**
     * Calculate delivery days from commitment date
     */
    private function calculateDeliveryDays($commitment_date) {
        if (empty($commitment_date)) {
            return 0;
        }
        
        $commitment = new DateTime($commitment_date);
        $today = new DateTime();
        $diff = $today->diff($commitment);
        
        return $diff->days;
    }
    
    /**
     * Get cached rate
     */
    private function getCachedRate($cache_key) {
        $cache_time = 900; // 15 minutes
        
        $cached = $this->ci->db->query("
            SELECT cache_value, created_at 
            FROM shipping_rates_cache 
            WHERE cache_key = ? AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
        ", [$cache_key, $cache_time])->row();
        
        if ($cached) {
            return json_decode($cached->cache_value, true);
        }
        
        return false;
    }
    
    /**
     * Cache rate result
     */
    private function cacheRate($cache_key, $rate_data) {
        $data = [
            'cache_key' => $cache_key,
            'cache_value' => json_encode($rate_data),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->ci->db->insert('shipping_rates_cache', $data);
    }
    
    /**
     * Get multiple service rates (now with dimensions)
     */
    public function getMultipleRates($dest_zip, $weight, $length = 6, $width = 6, $height = 6) {
        error_log("USPS Service Debug: getMultipleRates called with ZIP: $dest_zip, Weight: $weight, L:$length W:$width H:$height");
        $services = ['Priority', 'First-Class', 'Media Mail'];
        $rates = [];
        foreach ($services as $service) {
            error_log("USPS Service Debug: Getting rate for service: $service");
            $rate = $this->getRate($dest_zip, $weight, $length, $width, $height, $service);
            if ($rate !== false) {
                $rates[] = [
                    'service' => $rate['service'],
                    'cost' => $rate['rate'],
                    'delivery_time' => $rate['delivery_days'] > 0 ? $rate['delivery_days'] . ' days' : '3-5 days'
                ];
                error_log("USPS Service Debug: Rate found for $service: " . print_r($rate, true));
            } else {
                error_log("USPS Service Debug: No rate found for $service");
            }
        }
        error_log("USPS Service Debug: Final rates array: " . print_r($rates, true));
        if (empty($rates)) {
            return ['status' => false, 'message' => 'No rates available for this destination'];
        }
        return ['status' => true, 'rates' => $rates];
    }
    
    /**
     * Get demo rates for testing purposes (now with dimensions)
     */
    private function getDemoRate($dest_zip, $weight, $length = 6, $width = 6, $height = 6, $service = 'Priority') {
        error_log("USPS Service Debug: getDemoRate called with ZIP: $dest_zip, Weight: $weight, L:$length W:$width H:$height, Service: $service");
        
        $base_rates = [
            'Priority' => 8.50,
            'First-Class' => 4.50,
            'Media Mail' => 3.50
        ];
        
        $base_rate = isset($base_rates[$service]) ? $base_rates[$service] : 8.50;
        
        // Add weight-based adjustment
        $weight_adjustment = ($weight - 1) * 0.50;
        
        // Add dimension-based adjustment (dimensional weight calculation)
        $volume = $length * $width * $height;
        $dimensional_weight = $volume / 166; // USPS dimensional weight divisor
        
        // Use the greater of actual weight or dimensional weight
        $effective_weight = max($weight, $dimensional_weight);
        $dimension_adjustment = ($effective_weight - $weight) * 0.25;
        
        // Add size-based adjustment for large packages
        $size_adjustment = 0;
        if ($length > 12 || $width > 12 || $height > 12) {
            $size_adjustment = 2.00; // Additional charge for large packages
        }
        
        $final_rate = $base_rate + $weight_adjustment + $dimension_adjustment + $size_adjustment;
        
        $result = [
            'rate' => $final_rate,
            'service' => $service,
            'delivery_days' => $service === 'Priority' ? 2 : ($service === 'First-Class' ? 3 : 7),
            'currency' => 'USD'
        ];
        
        error_log("USPS Service Debug: Demo rate calculated with dimensions - Base: $base_rate, Weight Adj: $weight_adjustment, Dimension Adj: $dimension_adjustment, Size Adj: $size_adjustment, Final: $final_rate");
        return $result;
    }
    
    /**
     * Validate ZIP code format
     */
    public function validateZipCode($zip) {
        return preg_match('/^\d{5}(-\d{4})?$/', $zip);
    }
    
    /**
     * Test API connection
     */
    public function testConnection($api_key = null, $origin_zip = null) {
        // Use provided values or fall back to stored values
        $test_api_key = $api_key ?: $this->api_key;
        $test_origin_zip = $origin_zip ?: $this->origin_zip;
        
        if (empty($test_api_key) || empty($test_origin_zip)) {
            return ['status' => false, 'message' => 'API key or origin ZIP not configured'];
        }
        
        // For demo/testing purposes, if using the demo key, return a simulated success
        if ($test_api_key === '9400100000000000000000') {
            return [
                'status' => true, 
                'message' => 'Demo mode: API connection successful! (Using simulated rates for testing)',
                'demo_mode' => true
            ];
        }
        
        // Temporarily set the values for testing
        $original_api_key = $this->api_key;
        $original_origin_zip = $this->origin_zip;
        
        $this->api_key = $test_api_key;
        $this->origin_zip = $test_origin_zip;
        
        // Test with a simple rate request
        $test_rate = $this->getRate('10001', 1, 'Priority');
        
        // Restore original values
        $this->api_key = $original_api_key;
        $this->origin_zip = $original_origin_zip;
        
        if ($test_rate !== false) {
            return ['status' => true, 'message' => 'API connection successful! Test rate: $' . number_format($test_rate['rate'], 2)];
        } else {
            return ['status' => false, 'message' => 'API connection failed. Please check your API key and origin ZIP code.'];
        }
    }
} 