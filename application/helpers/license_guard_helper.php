<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * License Guard Helper
 *
 * Performs periodic, Envato-policy-compliant license checks.
 * Runtime checks are SOFT (warning-only, never block functionality).
 * Hard gating happens only at installation time.
 */

if (!function_exists('license_guard_check')) {
    /**
     * Check license validity and domain binding.
     * Returns an associative array -- never blocks script execution.
     *
     * @return array ['valid' => bool, 'warning' => string|null]
     */
    function license_guard_check() {
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

        $dev_hosts = ['127.0.0.1', 'localhost', '::1', '10.0.2.2'];
        $host_without_port = explode(':', $host)[0];
        if (in_array($host_without_port, $dev_hosts, true)) {
            return ['valid' => true, 'warning' => null];
        }

        $cache_file = APPPATH . 'cache/license_check.json';
        $cache = _lg_read_cache($cache_file);

        if ($cache && isset($cache['last_check']) && isset($cache['domain']) && isset($cache['status'])) {
            $age = time() - (int)$cache['last_check'];
            $is_fresh = $age < 86400; // 24 hours
            $domain_match = (strcasecmp($cache['domain'], $host) === 0);

            if ($is_fresh && $domain_match && $cache['status'] === 'valid') {
                return ['valid' => true, 'warning' => null];
            }
        }

        $license_key = _lg_get_license_key();
        if ($license_key === '') {
            if ($cache && isset($cache['last_check'])) {
                $grace_age = time() - (int)$cache['last_check'];
                if ($grace_age < 604800) { // 7 days
                    return ['valid' => true, 'warning' => 'License key could not be found. Please verify your installation license.'];
                }
            }
            return ['valid' => false, 'warning' => 'No license key found. This installation may not be properly licensed.'];
        }

        $result = _lg_verify_with_license_easy($license_key, $host);

        if ($result === null) {
            if ($cache && isset($cache['last_check'])) {
                $grace_age = time() - (int)$cache['last_check'];
                if ($grace_age < 604800) {
                    return ['valid' => true, 'warning' => null];
                }
            }
            return ['valid' => false, 'warning' => 'License verification server unreachable. Please check your internet connection.'];
        }

        if ($result['valid']) {
            _lg_write_cache($cache_file, [
                'last_check'  => time(),
                'domain'      => $host,
                'status'      => 'valid',
                'license_key' => $license_key,
            ]);
            return ['valid' => true, 'warning' => null];
        }

        $msg = isset($result['message']) ? $result['message'] : 'License is not valid for this domain.';
        _lg_write_cache($cache_file, [
            'last_check'  => time(),
            'domain'      => $host,
            'status'      => 'invalid',
            'license_key' => $license_key,
        ]);
        return ['valid' => false, 'warning' => $msg];
    }
}

if (!function_exists('license_guard_get_cached_status')) {
    /**
     * Quick read of the cached license status without triggering a new check.
     * Used by the admin header to avoid repeated HTTP calls.
     *
     * @return array|null  Cached data or null if no cache exists
     */
    function license_guard_get_cached_status() {
        $cache_file = APPPATH . 'cache/license_check.json';
        return _lg_read_cache($cache_file);
    }
}

if (!function_exists('license_guard_get_domain')) {
    /**
     * Get the domain stored in the license cache.
     *
     * @return string  The cached domain or empty string
     */
    function license_guard_get_domain() {
        $cache = license_guard_get_cached_status();
        return ($cache && isset($cache['domain'])) ? $cache['domain'] : '';
    }
}


// ── Private helpers ──

if (!function_exists('_lg_read_cache')) {
    function _lg_read_cache($file) {
        if (!file_exists($file)) {
            return null;
        }
        $data = @file_get_contents($file);
        if (!$data) {
            return null;
        }
        $decoded = @json_decode($data, true);
        return is_array($decoded) ? $decoded : null;
    }
}

if (!function_exists('_lg_write_cache')) {
    function _lg_write_cache($file, $data) {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    }
}

if (!function_exists('_lg_get_license_key')) {
    /**
     * Retrieve the stored license key using the same fallback chain
     * as User.php::_get_stored_license_key().
     */
    function _lg_get_license_key() {
        $license_cache = APPPATH . 'license-easy-data-affiliateporsaas.json';
        if (file_exists($license_cache)) {
            $cache_data = @json_decode(file_get_contents($license_cache), true);
            if ($cache_data && isset($cache_data['license_key']) && $cache_data['license_key'] !== '') {
                return trim($cache_data['license_key']);
            }
        }

        $CI =& get_instance();
        if ($CI) {
            $cfg_key = $CI->config->item('codecanyon_license');
            if ($cfg_key && $cfg_key !== '') {
                return trim($cfg_key);
            }
        }

        if (defined('CODECANYON_LICENCE') && CODECANYON_LICENCE !== '') {
            return trim(CODECANYON_LICENCE);
        }

        if (defined('LICENSE_EASY_KEY') && LICENSE_EASY_KEY !== '') {
            return trim(LICENSE_EASY_KEY);
        }

        return '';
    }
}

if (!function_exists('_lg_verify_with_license_easy')) {
    /**
     * Validate the license via the License Easy API (the same server used during installation).
     * Returns ['valid' => bool, 'message' => string] or null on network failure.
     */
    function _lg_verify_with_license_easy($license_key, $domain) {
        $api_url = 'https://affiliatepro.org/index.php?rest_route=/license-easy/v1/validate';

        $payload = json_encode([
            'purchase_code' => $license_key,
            'product_slug'  => 'affiliateporsaas',
            'domain'        => $domain,
        ]);

        $ch = curl_init($api_url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_errno($ch);
        curl_close($ch);

        if ($curl_error || !$response || $http_code < 200 || $http_code >= 500) {
            return null; // Network / server error → grace period applies
        }

        $decoded = @json_decode($response, true);
        if (!is_array($decoded)) {
            return null;
        }

        $is_valid = (isset($decoded['status']) && $decoded['status'] === 'success');

        return [
            'valid'   => $is_valid,
            'message' => isset($decoded['message']) ? $decoded['message'] : ($is_valid ? 'Valid' : 'License is not valid for this domain.'),
        ];
    }
}
