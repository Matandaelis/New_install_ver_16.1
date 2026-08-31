<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Gateway Translation Helper
 * Automatically handles translation integration for custom payment gateways
 */
class Gateway_translation_helper {
    
    private $CI;
    
    public function __construct() {
        if (function_exists('get_instance')) {
            $this->CI =& get_instance();
        } else {
            // Mock CI for testing
            $this->CI = new stdClass();
            $this->CI->db = new stdClass();
        }
    }
    
    /**
     * Extract translation keys from gateway files
     * @param string $gateway_code The gateway code (e.g., 'test_gateway')
     * @param string $upload_path Optional upload path for temporary files
     * @return array Array of translation keys found
     */
    public function extract_translation_keys($gateway_code, $upload_path = null) {
        $translation_keys = [];
        
        // Define file paths to scan
        if ($upload_path) {
            // Use upload path for temporary files during installation
            $file_paths = [
                $upload_path . "upload/controllers/{$gateway_code}.php",
                $upload_path . "upload/admin_settings/{$gateway_code}.php",
                $upload_path . "upload/user_settings/{$gateway_code}.php",
                $upload_path . "upload/confirm_view/{$gateway_code}.php"
            ];
        } else {
            // Use final location for existing gateways
            $file_paths = [
                APPPATH . "withdrawal_payment/controllers/{$gateway_code}.php",
                APPPATH . "withdrawal_payment/admin_settings/{$gateway_code}.php",
                APPPATH . "withdrawal_payment/user_settings/{$gateway_code}.php",
                APPPATH . "withdrawal_payment/confirm_view/{$gateway_code}.php"
            ];
        }
        
        foreach ($file_paths as $file_path) {
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                $keys = $this->find_translation_keys($content);
                $translation_keys = array_merge($translation_keys, $keys);
            }
        }
        
        return array_unique($translation_keys);
    }
    
    /**
     * Find translation keys in file content using regex
     * @param string $content File content
     * @return array Array of translation keys
     */
    private function find_translation_keys($content) {
        $keys = [];
        
        // Pattern to match __('admin.key_name') or __('client.key_name') etc.
        $pattern = '/__\([\'"]([a-z_]+)\.([a-z_]+)[\'"]\)/i';
        preg_match_all($pattern, $content, $matches);
        
        if (!empty($matches[1]) && !empty($matches[2])) {
            foreach ($matches[1] as $index => $prefix) {
                $key = $prefix . '.' . $matches[2][$index];
                $keys[] = $key;
            }
        }
        
        return $keys;
    }
    
    /**
     * Add translation keys to language files
     * @param string $gateway_code The gateway code
     * @param array $translation_keys Array of translation keys
     * @return bool Success status
     */
    public function add_gateway_translations($gateway_code, $translation_keys) {
        if (empty($translation_keys)) {
            return true; // No translations to add
        }
        
        // Get all active languages
        $languages = [];
        if (function_exists('get_instance') && method_exists($this->CI->db, 'query')) {
            $result = $this->CI->db->query("SELECT id, name FROM language WHERE status = 1");
            if ($result) {
                $languages = $result->result_array();
            }
        } else {
            // Fallback: just add to default language
            $languages = [['id' => 1, 'name' => 'English']];
        }
        
        foreach ($languages as $language) {
            $this->add_translations_to_language($language['id'], $translation_keys, $gateway_code);
        }
        
        return true;
    }
    
    /**
     * Add translations to specific language file
     * @param int $language_id Language ID
     * @param array $translation_keys Array of translation keys
     * @param string $gateway_code Gateway code for default values
     */
    private function add_translations_to_language($language_id, $translation_keys, $gateway_code) {
        // Use default language path
        $lang_file_path = APPPATH . "language/default/admin.php";
        
        // Check if file exists
        if (!file_exists($lang_file_path)) {
            return false;
        }
        
        // Read existing translations to check for duplicates
        $existing_translations = [];
        $content = file_get_contents($lang_file_path);
        
        // Parse existing translations more carefully
        preg_match_all('/\$lang\[[\'"]([^\'"]+)[\'"]\]\s*=\s*([^;]+);/', $content, $matches);
        foreach ($matches[1] as $index => $key) {
            $value = trim($matches[2][$index], "'\"");
            $existing_translations[$key] = $value;
        }
        
        // Add only new translations that don't already exist
        $new_translations = [];
        foreach ($translation_keys as $key) {
            // Strip the prefix (admin., client., etc.) from the key for storage
            $storage_key = $key;
            if (strpos($key, '.') !== false) {
                $parts = explode('.', $key, 2);
                $storage_key = $parts[1]; // Use only the part after the first dot
            }
            
            if (!isset($existing_translations[$storage_key])) {
                $new_translations[$storage_key] = $this->generate_default_translation($key, $gateway_code);
            }
        }
        
        // Only add if there are new translations
        if (!empty($new_translations)) {
            $this->write_translations_to_file($lang_file_path, $existing_translations, $new_translations);
            return true;
        }
        
        return false;
    }
    
    /**
     * Generate default translation value
     * @param string $key Translation key
     * @param string $gateway_code Gateway code
     * @return string Default translation value
     */
    private function generate_default_translation($key, $gateway_code) {
        // Extract the key name (after the last dot)
        $key_parts = explode('.', $key);
        $key_name = end($key_parts);
        
        // Remove gateway code prefix from key name if present
        if (strpos($key_name, $gateway_code . '_') === 0) {
            $key_name = substr($key_name, strlen($gateway_code . '_'));
        }
        
        // Special handling for specific keys
        $special_keys = [
            'account_help' => 'Optional: Your payment account number',
            'account_placeholder' => 'Enter your account number',
            'name_placeholder' => 'Enter your full name',
            'email_placeholder' => 'Enter your email address',
            'phone_placeholder' => 'Enter your phone number',
            'name_label' => 'Full Name',
            'email_label' => 'Email Address',
            'phone_label' => 'Phone Number',
            'account_label' => 'Account Number',
            'api_key_help' => 'Your payment gateway API key',
            'secret_key_help' => 'Your payment gateway secret key',
            'mode_help' => 'Select sandbox for testing or live for production'
        ];
        
        if (isset($special_keys[$key_name])) {
            return $special_keys[$key_name];
        }
        
        // Convert snake_case to Title Case for other keys
        $title_case = str_replace('_', ' ', $key_name);
        $title_case = ucwords($title_case);
        
        return $title_case;
    }
    
    /**
     * Create empty language file
     * @param string $file_path File path
     */
    private function create_language_file($file_path) {
        $dir = dirname($file_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $content = "<?php\n";
        $content .= "// Auto-generated language file\n";
        $content .= "// Generated on: " . date('Y-m-d H:i:s') . "\n\n";
        
        file_put_contents($file_path, $content);
    }
    
    /**
     * Write translations to language file
     * @param string $file_path File path
     * @param array $existing_translations Existing translations
     * @param array $new_translations New translations to add
     */
    private function write_translations_to_file($file_path, $existing_translations, $new_translations) {
        // Add new translations to the end of the file
        $new_content = "\n\n// Auto-added gateway translations - " . date('Y-m-d H:i:s') . "\n";
        foreach ($new_translations as $key => $value) {
            $escaped_value = "'" . addslashes($value) . "'";
            $new_content .= "\$lang['{$key}'] = " . $escaped_value . ";\n";
        }
        
        // Append only new translations to existing file
        file_put_contents($file_path, $new_content, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Remove gateway translations when uninstalling
     * @param string $gateway_code The gateway code
     * @return bool Success status
     */
    public function remove_gateway_translations($gateway_code) {
        // Remove from default language file
        $this->remove_translations_from_language('default', $gateway_code);
        
        return true;
    }
    
    /**
     * Remove translations from specific language file
     * @param string $language_name Language name (e.g., 'default')
     * @param string $gateway_code Gateway code
     */
    private function remove_translations_from_language($language_name, $gateway_code) {
        $lang_file_path = APPPATH . "language/{$language_name}/admin.php";
        
        if (!file_exists($lang_file_path)) {
            return;
        }
        
        // Read the file content
        $content = file_get_contents($lang_file_path);
        
        // Remove lines that contain the gateway code pattern
        $lines = explode("\n", $content);
        $filtered_lines = [];
        $skip_next_empty_lines = false;
        
        foreach ($lines as $line) {
            // Skip lines that contain the gateway code pattern (e.g., test_gateway_)
            if (strpos($line, $gateway_code . '_') !== false || 
                strpos($line, 'admin.' . $gateway_code . '_') !== false) {
                $skip_next_empty_lines = true;
                continue;
            }
            
            // Skip auto-added translation comments
            if (strpos($line, '// Auto-added gateway translations') !== false) {
                $skip_next_empty_lines = true;
                continue;
            }
            
            // Skip empty lines after removing translations
            if ($skip_next_empty_lines && trim($line) === '') {
                continue;
            }
            
            // Reset skip flag when we hit a non-empty line
            if (trim($line) !== '') {
                $skip_next_empty_lines = false;
            }
            
            $filtered_lines[] = $line;
        }
        
        // Write filtered content back
        file_put_contents($lang_file_path, implode("\n", $filtered_lines));
    }
}
