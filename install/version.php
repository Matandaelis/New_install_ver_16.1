<?php
$script_version = '0.0.0';
$config_path = dirname(__DIR__) . '/application/config/config.php';
if (file_exists($config_path)) {
    $config_content = file_get_contents($config_path);
    if (preg_match("/\\['app_version'\]\s*=\s*['\"]([^'\"]+)['\"]/", $config_content, $matches)) {
        $script_version = $matches[1];
    }
}
if (!defined('SCRIPT_VERSION')) define('SCRIPT_VERSION', $script_version);
if (!defined('CODECANYON_LICENCE')) define('CODECANYON_LICENCE', '');
if (!defined('LICENSE_EASY_KEY')) define('LICENSE_EASY_KEY', CODECANYON_LICENCE);
