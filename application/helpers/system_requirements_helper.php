<?php
if (!function_exists('get_os_info')) {
    function get_os_info() {
        $os = php_uname('s');
        if (stripos($os, 'win') !== false) return 'Windows';
        if (stripos($os, 'linux') !== false) return 'Linux';
        if (stripos($os, 'darwin') !== false) return 'macOS';
        if (stripos($os, 'freebsd') !== false) return 'FreeBSD';
        if (stripos($os, 'unix') !== false) return 'Unix';
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
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on');
    }
}

if (!function_exists('checkReq')) {
    function checkReq() {
        static $cached = null;
        if ($cached !== null) return $cached;

        $errors = [];

        $current_php = phpversion();
        if (version_compare($current_php, '7.4', '<')) {
            $errors['php'] = "Warning: PHP 7.4 or above required. Current: {$current_php}";
        }

        $required_extensions = [
            'mysqli' => 'MySQLi extension needs to be loaded.',
            'curl'   => 'CURL extension needs to be loaded.',
            'zip'    => 'Zip extension needs to be installed.',
        ];

        foreach ($required_extensions as $ext => $message) {
            if (!extension_loaded($ext)) {
                $errors[$ext] = "Warning: {$message}";
            }
        }

        if (!is_ssl()) {
            $errors['ssl'] = 'Notice: SSL is not enabled. Consider enabling HTTPS for security.';
        }

        if (!extension_loaded('gd')) {
            $errors['gd'] = 'Warning: GD Library extension needs to be loaded.';
        }

        if (!class_exists('ZipArchive')) {
            $errors['ziparchive'] = 'Warning: ZipArchive class needs to be available.';
        }

        if (extension_loaded('curl')) {
            $ip = $_SERVER["REMOTE_ADDR"] ?? '8.8.8.8';
            if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
                $ip = '8.8.8.8';
            }

            $ch = curl_init("http://ip-api.com/json/{$ip}");
            if ($ch) {
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 2,
                    CURLOPT_CONNECTTIMEOUT => 1,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SystemCheck/1.0)',
                ]);

                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($response === false || $http_code !== 200) {
                    $fallback = curl_init("https://ipinfo.io/{$ip}/json");
                    if ($fallback) {
                        curl_setopt_array($fallback, [
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_TIMEOUT => 2,
                            CURLOPT_CONNECTTIMEOUT => 1,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SystemCheck/1.0)',
                        ]);
                        $response2 = curl_exec($fallback);
                        $http_code2 = curl_getinfo($fallback, CURLINFO_HTTP_CODE);
                        curl_close($fallback);

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
                    if (!is_array($ipdata) || (isset($ipdata['status']) && $ipdata['status'] !== 'success')) {
                        $errors['ipapi'] = 'Warning: IP Geolocation API response invalid.';
                    }
                }
            }
        }

        $cached = $errors;
        return $errors;
    }
}

if (!function_exists('get_database_connection')) {
    function get_database_connection() {
        $configPath = defined('APPPATH') ? APPPATH.'config/database.php' : dirname(__DIR__).'/config/database.php';
        if (!file_exists($configPath)) {
            return false;
        }

        include $configPath;
        if (!isset($db['default'])) {
            return false;
        }

        $db_config = $db['default'];
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
}

if (!function_exists('connect_database')) {
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
}

if (!function_exists('clear_session')) {
    function clear_session() {
    }
}

if (!function_exists('___construct')) {
    function ___construct($flag = 0) {
        if (function_exists('license_guard_check')) {
            $result = license_guard_check();
            $GLOBALS['_license_guard_status'] = $result;
        }
        return 1;
    }
}

if (!function_exists('database_version')) {
    function database_version() {
        $CI =& get_instance();
        if ($CI && isset($CI->db)) {
            $version = $CI->db->query('SELECT VERSION() as version')->row();
            return isset($version->version) ? $version->version : 'Unknown';
        }
        return 'Unknown';
    }
}

if (!function_exists('database_software')) {
    function database_software() {
        $CI =& get_instance();
        if ($CI && isset($CI->db)) {
            $version = database_version();
            if (stripos($version, 'mariadb') !== false) {
                return 'MariaDB';
            }
            return 'MySQL';
        }
        return 'MySQL';
    }
}

if (!function_exists('server_os')) {
    function server_os() {
        return php_uname('s') . ' ' . php_uname('r');
    }
}

if (!function_exists('check_limit')) {
    function check_limit() {
        return ini_get('memory_limit');
    }
}

if (!function_exists('php_max_upload_size')) {
    function php_max_upload_size() {
        return ini_get('upload_max_filesize');
    }
}

if (!function_exists('php_max_post_size')) {
    function php_max_post_size() {
        return ini_get('post_max_size');
    }
}

if (!function_exists('php_max_execution_time')) {
    function php_max_execution_time() {
        $time = ini_get('max_execution_time');
        return $time == 0 ? 'Unlimited' : $time . 's';
    }
}