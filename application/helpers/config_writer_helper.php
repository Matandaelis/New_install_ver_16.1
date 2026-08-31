<?php
if (!function_exists('update_config_option')) {
    function update_config_option($key, $value, $configPath = null) {
        if (!$configPath) {
            if (defined('APPPATH')) {
                $configPath = rtrim(APPPATH, '/\\') . '/config/config.php';
            } else {
                $configPath = dirname(__DIR__) . '/config/config.php';
            }
        }

        if (!file_exists($configPath)) {
            return false;
        }

        $content = file_get_contents($configPath);
        $escapedKey = preg_quote($key, '/');
        $escapedValue = str_replace("'", "\\'", $value);
        $newLine = "\$config['{$key}'] = '{$escapedValue}';";
        
        $pattern = "/\\\$config\\[(['\"])" . $escapedKey . "\\1\\]\\s*=\\s*[^;]+;/";
        
        $matchCount = preg_match_all($pattern, $content, $matches);
        
        if ($matchCount > 0) {
            $content = preg_replace($pattern, '', $content);
        }
        
        $insertBeforeBoot = in_array($key, ['codecanyon_license', 'license_easy_key']);
        
        if ($insertBeforeBoot) {
            // Match the unique part of the marker to be safe against newline differences
            $bootMarkerUnique = 'Script metadata (appended for installer)';
            $bootPos = strpos($content, $bootMarkerUnique);
            
            if ($bootPos !== false) {
                // Find the start of the comment block (search backwards for '/*')
                $blockStart = strrpos(substr($content, 0, $bootPos), '/*');
                if ($blockStart !== false) {
                     // Find the start of that line
                     $lineStart = strrpos(substr($content, 0, $blockStart), "\n");
                     $lineStart = ($lineStart === false) ? 0 : $lineStart + 1;
                     $content = substr_replace($content, $newLine . "\n", $lineStart, 0);
                } else {
                     // Fallback: just insert before the unique string's line if comment start not found
                     $lineStart = strrpos(substr($content, 0, $bootPos), "\n");
                     $lineStart = ($lineStart === false) ? 0 : $lineStart + 1;
                     $content = substr_replace($content, $newLine . "\n", $lineStart, 0);
                }
            } else {
                $marker = '/* End of file config.php */';
                $markerPos = strpos($content, $marker);
                if ($markerPos !== false) {
                    $content = substr_replace($content, $newLine . "\n", $markerPos, 0);
                } else {
                    $content = rtrim($content) . "\n" . $newLine . "\n";
                }
            }
        } else {
            $marker = '/* End of file config.php */';
            $markerPos = strpos($content, $marker);
            
            if ($markerPos !== false) {
                $content = substr_replace($content, $newLine . "\n", $markerPos, 0);
            } else {
                $content = rtrim($content) . "\n" . $newLine . "\n";
            }
        }

        return file_put_contents($configPath, $content) !== false;
    }
}

