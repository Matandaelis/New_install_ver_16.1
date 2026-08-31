<?php
/**
 * License Easy - Universal Client Library
 * 
 * @version 1.0.0
 * @package License Easy
 */

if (!class_exists('License_Easy_Universal_Client')) {

class License_Easy_Universal_Client {
    
    private $product_slug;
    private $api_url;
    private $product_name;
    private $license_file;
    private $is_wordpress = false;
    private $is_plugin = false;
    private $is_theme = false;
    
    public function __construct($config = array()) {
        $this->product_slug = isset($config['product_slug']) ? $config['product_slug'] : '';
        $this->api_url = isset($config['api_url']) ? rtrim($config['api_url'], '/') . '/' : '';
        $this->product_name = isset($config['product_name']) ? $config['product_name'] : 'This Product';
        
        if (empty($this->product_slug) || empty($this->api_url)) {
            die('License Easy Client Error: product_slug and api_url are required.');
        }
        
        $this->license_file = dirname(__FILE__) . '/license-easy-data-' . sanitize_file_name($this->product_slug) . '.json';
        
        $this->detect_environment();
        
        $this->init();
    }
    
    private function detect_environment() {
        $this->is_wordpress = function_exists('add_action');
        
        if ($this->is_wordpress) {
            $this->is_plugin = defined('ABSPATH') && !defined('WP_USE_THEMES');
            $this->is_theme = defined('WP_USE_THEMES');
            
            add_action('init', array($this, 'remove_default_ui'), 999);
            add_action('init', array($this, 'handle_inline_license_actions'));
            add_action('admin_init', array($this, 'handle_license_form'));
            add_action('admin_footer', array($this, 'enqueue_notification_system'));
        }
    }
    
    private function init() {
        // Handle standalone request in non-WordPress environments OR when explicitly requested
        if (!$this->is_wordpress || (isset($_GET['license_easy_page']) && $_GET['license_easy_page'] === $this->product_slug)) {
            $this->handle_standalone_request();
        }
    }
    
    public function remove_default_ui() {
        remove_action('admin_notices', array($this, 'show_activation_notice'));
        remove_action('admin_menu', array($this, 'add_license_menu'));
    }
    
    public function handle_inline_license_actions() {
        if (isset($_POST['license_easy_action']) && is_admin()) {
            if (!isset($_POST['license_easy_product'])) {
                return;
            }
            
            if ($_POST['license_easy_product'] !== $this->product_slug) {
                return;
            }
            
            if (isset($_POST['license_easy_nonce']) && wp_verify_nonce($_POST['license_easy_nonce'], 'license_easy_action')) {
                if ($_POST['license_easy_action'] === 'activate' && isset($_POST['license_key']) && !empty($_POST['license_key'])) {
                    $result = $this->activate_license($_POST['license_key']);
                    if ($result['success']) {
                        set_transient('license_easy_message_activated_' . $this->product_slug, true, 10);
                        wp_redirect($_SERVER['REQUEST_URI']);
                        exit;
                    } else {
                        set_transient('license_easy_message_error_' . $this->product_slug, $result['message'], 10);
                        wp_redirect($_SERVER['REQUEST_URI']);
                        exit;
                    }
                } elseif ($_POST['license_easy_action'] === 'deactivate') {
                    $result = $this->deactivate_license();
                    if ($result['success']) {
                        set_transient('license_easy_message_deactivated_' . $this->product_slug, true, 10);
                        wp_redirect($_SERVER['REQUEST_URI']);
                        exit;
                    } else {
                        set_transient('license_easy_message_error_' . $this->product_slug, $result['message'], 10);
                        wp_redirect($_SERVER['REQUEST_URI']);
                        exit;
                    }
                }
            }
        }
        
        if (get_transient('license_easy_message_activated_' . $this->product_slug)) {
            delete_transient('license_easy_message_activated_' . $this->product_slug);
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>✅ <strong>License activated successfully!</strong></p></div>';
            });
        }
        if (get_transient('license_easy_message_deactivated_' . $this->product_slug)) {
            delete_transient('license_easy_message_deactivated_' . $this->product_slug);
            add_action('admin_notices', function() {
                echo '<div class="notice notice-info is-dismissible"><p>ℹ️ <strong>License deactivated successfully!</strong></p></div>';
            });
        }
        $error_message = get_transient('license_easy_message_error_' . $this->product_slug);
        if ($error_message) {
            delete_transient('license_easy_message_error_' . $this->product_slug);
            add_action('admin_notices', function() use ($error_message) {
                echo '<div class="notice notice-error is-dismissible"><p>❌ <strong>' . esc_html($error_message) . '</strong></p></div>';
            });
        }
    }
    
    public function is_active() {
        $license_data = $this->get_license_data();
        return isset($license_data['status']) && $license_data['status'] === 'active';
    }
    
    public function get_license_data() {
        if (!file_exists($this->license_file)) {
            return array('status' => 'inactive', 'license_key' => '', 'website_url' => '', 'activated_at' => '');
        }
        
        $data = @file_get_contents($this->license_file);
        if (!$data) {
            return array('status' => 'inactive', 'license_key' => '', 'website_url' => '', 'activated_at' => '');
        }
        
        $decoded = json_decode($data, true);
        return is_array($decoded) ? $decoded : array('status' => 'inactive', 'license_key' => '', 'website_url' => '', 'activated_at' => '');
    }
    
    public function render_license_box() {
        $error_message = get_transient('license_easy_message_error_' . $this->product_slug);
        $success_message = get_transient('license_easy_message_activated_' . $this->product_slug);
        $unique_id = 'license-easy-' . $this->product_slug . '-' . mt_rand(1000, 9999);
        
        if (!$this->is_active()) {
            ?>
            <div class="license-easy-wrapper" style="all: initial; display: block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif; line-height: 1.5; margin: 0; padding: 0;">
            <div class="license-easy-box license-easy-box-<?php echo esc_attr($this->product_slug); ?>">
                <?php if ($error_message): ?>
                <div class="license-easy-inline-error" style="width: 100%; padding: 8px 12px; background: #fee; border: 1px solid #fcc; border-radius: 3px; color: #c00; font-size: 13px; margin-bottom: 10px;">
                    ❌ <strong><?php echo esc_html($error_message); ?></strong>
                </div>
                <?php endif; ?>
                <div class="license-easy-label">
                    <svg class="license-easy-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>License Required</span>
                </div>
                <form method="post" action="" class="license-easy-form" id="<?php echo esc_attr($unique_id); ?>-form">
                    <?php wp_nonce_field('license_easy_action', 'license_easy_nonce'); ?>
                    <input type="hidden" name="license_easy_action" value="activate">
                    <input type="hidden" name="license_easy_product" value="<?php echo esc_attr($this->product_slug); ?>">
                    <div class="license-easy-input-wrapper" style="position: relative; flex: 1; display: flex; align-items: center;">
                        <input type="text" 
                               name="license_key" 
                               id="<?php echo esc_attr($unique_id); ?>-input"
                               placeholder="<?php esc_attr_e('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'); ?>" 
                               required 
                               class="license-easy-input" 
                               style="padding-right: 40px; width: 100%;">
                        <span class="license-easy-validation-icon" id="<?php echo esc_attr($unique_id); ?>-icon" style="display: none; position: absolute; right: 12px; font-size: 18px; line-height: 1;"></span>
                        <span class="license-easy-validation-spinner" id="<?php echo esc_attr($unique_id); ?>-spinner" style="display: none; position: absolute; right: 12px; width: 18px; height: 18px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" opacity="0.25"/>
                                <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </div>
                    <button type="submit" class="button button-primary license-easy-btn" id="<?php echo esc_attr($unique_id); ?>-btn" disabled>Activate</button>
                </form>
                <small class="license-easy-hint" id="<?php echo esc_attr($unique_id); ?>-hint" style="display: block; margin-top: 6px; font-size: 12px; color: #666;">
                    <?php _e('Enter your Envato purchase code', 'license-easy'); ?>
                </small>
            </div>
            </div>
            <script>
            (function() {
                var validationTimeout;
                var isValid = false;
                var $ = jQuery;
                var uniqueId = '<?php echo esc_js($unique_id); ?>';
                var productSlug = '<?php echo esc_js($this->product_slug); ?>';
                var apiUrl = '<?php echo esc_js($this->api_url); ?>';
                
                $('#' + uniqueId + '-input').on('input', function() {
                    var $input = $(this);
                    var $icon = $('#' + uniqueId + '-icon');
                    var $spinner = $('#' + uniqueId + '-spinner');
                    var $hint = $('#' + uniqueId + '-hint');
                    var $btn = $('#' + uniqueId + '-btn');
                    var licenseKey = $input.val().trim();
                    
                    clearTimeout(validationTimeout);
                    
                    $icon.hide().html('');
                    $spinner.hide();
                    $input.css({'border-color': '#ddd', 'background': '#fff'});
                    $btn.prop('disabled', true);
                    isValid = false;
                    
                    if (licenseKey.length === 0) {
                        $hint.html('<?php _e('Enter your Envato purchase code', 'license-easy'); ?>')
                             .css('color', '#666');
                        return;
                    }
                    
                    $spinner.show();
                    $hint.html('<?php _e('Validating...', 'license-easy'); ?>')
                         .css('color', '#666');
                    
                    validationTimeout = setTimeout(function() {
                        $.ajax({
                            url: apiUrl + 'validate',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                purchase_code: licenseKey,
                                product_slug: productSlug,
                                domain: window.location.hostname
                            },
                            success: function(response) {
                                $spinner.hide();
                                
                                if (response.status === 'success') {
                                    $icon.html('✅').css('color', '#10b981').show();
                                    $input.css({'border-color': '#10b981', 'background': '#f0fdf4'});
                                    $hint.html('<?php _e('Valid license! Click Activate to continue.', 'license-easy'); ?>')
                                         .css('color', '#10b981');
                                    $btn.prop('disabled', false);
                                    isValid = true;
                                } else {
                                    $icon.html('❌').css('color', '#ef4444').show();
                                    $input.css({'border-color': '#ef4444', 'background': '#fef2f2'});
                                    var errorMsg = response.message || '<?php _e('Invalid license', 'license-easy'); ?>';
                                    $hint.html(errorMsg).css('color', '#ef4444');
                                    $btn.prop('disabled', true);
                                    isValid = false;
                                }
                            },
                            error: function() {
                                $spinner.hide();
                                $icon.html('⚠️').css('color', '#f59e0b').show();
                                $input.css({'border-color': '#f59e0b', 'background': '#fffbeb'});
                                $hint.html('<?php _e('Could not validate. Check your connection.', 'license-easy'); ?>')
                                     .css('color', '#f59e0b');
                                $btn.prop('disabled', true);
                            }
                        });
                    }, 1000);
                });
                
                $('#' + uniqueId + '-form').on('submit', function(e) {
                    if (!isValid) {
                        e.preventDefault();
                        $('#' + uniqueId + '-hint').html('<?php _e('Please enter a valid license key first.', 'license-easy'); ?>')
                             .css('color', '#ef4444');
                        return false;
                    }
                });
            })();
            </script>
            <?php
        } else {
            $license_data = $this->get_license_data();
            ?>
            <div class="license-easy-wrapper" style="all: initial; display: block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif; line-height: 1.5; margin: 0; padding: 0;">
            <div class="license-easy-box license-easy-box-active">
                <div class="license-easy-label">
                    <svg class="license-easy-icon" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Active License:</span>
                    <code class="license-easy-code"><?php echo esc_html(substr($license_data['license_key'], 0, 18)); ?>...</code>
                </div>
                <form method="post" action="" class="license-easy-form">
                    <?php wp_nonce_field('license_easy_action', 'license_easy_nonce'); ?>
                    <input type="hidden" name="license_easy_action" value="deactivate">
                    <input type="hidden" name="license_easy_product" value="<?php echo esc_attr($this->product_slug); ?>">
                    <button type="submit" onclick="return confirm('Deactivate license?');" class="button license-easy-btn-small">Deactivate</button>
                </form>
            </div>
            </div>
            <?php
        }
    }
    
    private function save_license_data($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        @file_put_contents($this->license_file, $json);
        @chmod($this->license_file, 0600);
    }
    
    public function add_license_menu() {
        add_menu_page(
            $this->product_name . ' License',
            $this->product_name . ' License',
            'manage_options',
            'license-easy-' . sanitize_title($this->product_slug),
            array($this, 'render_license_page'),
            'dashicons-admin-network',
            99
        );
    }
    
    public function show_activation_notice() {
        if (!$this->is_active()) {
            $current_screen = function_exists('get_current_screen') ? get_current_screen() : null;
            
            if ($current_screen && strpos($current_screen->id, 'license-easy') !== false) {
                return;
            }
            
            $menu_url = admin_url('admin.php?page=license-easy-' . sanitize_title($this->product_slug));
            echo '<div id="license-easy-notice-' . esc_attr($this->product_slug) . '" style="background: #fff3cd; border-left: 4px solid #f59e0b; padding: 12px 20px; margin: 5px 15px 2px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">';
            echo '<p style="margin: 0; font-size: 13px;"><strong style="color: #92400e;">🔐 ' . esc_html($this->product_name) . ':</strong> ';
            echo 'Please <a href="' . esc_url($menu_url) . '" style="color: #2563eb; text-decoration: none; font-weight: 600;">activate your license</a> to unlock all features.</p>';
            echo '</div>';
            echo '<script>
            (function() {
                var notice = document.getElementById("license-easy-notice-' . esc_js($this->product_slug) . '");
                if (notice) {
                    notice.style.display = "block";
                    notice.style.opacity = "1";
                    setInterval(function() {
                        if (notice.style.display === "none" || notice.style.opacity === "0") {
                            notice.style.display = "block";
                            notice.style.opacity = "1";
                        }
                    }, 100);
                }
            })();
            </script>';
        }
    }
    
    public function enqueue_notification_system() {
        ?>
        <style>
        .license-easy-wrapper {
            all: initial !important;
            display: block !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif !important;
            line-height: 1.5 !important;
            margin: 16px 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }
        .license-easy-wrapper * {
            box-sizing: border-box !important;
        }
        .license-easy-box {
            margin: 0 !important;
            padding: 10px 16px !important;
            background: #fafafa !important;
            border: 1px solid #e0e0e0 !important;
            border-radius: 3px !important;
            display: flex !important;
            align-items: center !important;
            gap: 16px !important;
            font-size: 13px !important;
            line-height: 1.5 !important;
        }
        .license-easy-box-active {
            padding: 8px 16px !important;
            justify-content: space-between !important;
        }
        .license-easy-label {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            color: #666 !important;
            font-size: 13px !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .license-easy-icon {
            width: 14px !important;
            height: 14px !important;
            flex-shrink: 0 !important;
        }
        .license-easy-form {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            flex: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .license-easy-input {
            flex: 1 !important;
            max-width: 320px !important;
            padding: 6px 10px !important;
            border: 1px solid #d0d0d0 !important;
            border-radius: 3px !important;
            font-size: 13px !important;
            font-family: monospace !important;
            background: #fff !important;
            line-height: 1.4 !important;
            height: auto !important;
        }
        .license-easy-code {
            font-size: 12px !important;
            color: #333 !important;
            background: #f5f5f5 !important;
            padding: 3px 10px !important;
            border-radius: 3px !important;
            font-family: monospace !important;
            border: 1px solid #e0e0e0 !important;
        }
        .license-easy-btn {
            padding: 6px 16px !important;
            font-size: 13px !important;
            height: auto !important;
            line-height: 1.4 !important;
            white-space: nowrap !important;
        }
        .license-easy-btn-small {
            padding: 4px 12px !important;
            font-size: 12px !important;
            height: auto !important;
            color: #666 !important;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        </style>
        <script type="text/javascript">
        (function() {
            // IMMEDIATE EXECUTION - Runs before ANY other scripts
            // Use native JavaScript with capture phase to intercept FIRST
            document.addEventListener('DOMContentLoaded', function() {
                var forms = document.querySelectorAll('.license-easy-form');
                
                forms.forEach(function(form) {
                    // Handle button clicks with HIGHEST priority (capture phase)
                    var buttons = form.querySelectorAll('button[type="submit"]');
                    buttons.forEach(function(button) {
                        button.addEventListener('click', function(e) {
                            // Check if button has onclick with confirm
                            var onclickAttr = button.getAttribute('onclick');
                            if (onclickAttr && onclickAttr.indexOf('confirm') !== -1) {
                                // Extract confirm message and show it
                                var match = onclickAttr.match(/confirm\(['"](.+?)['"]\)/);
                                if (match && match[1]) {
                                    if (!confirm(match[1])) {
                                        e.stopImmediatePropagation();
                                        e.preventDefault();
                                        return false;
                                    }
                                }
                            }
                            
                            e.stopImmediatePropagation(); // Block all other handlers
                            e.preventDefault(); // Prevent default behavior
                            
                            // Manually submit the form
                            form.submit();
                        }, true); // true = capture phase (runs FIRST)
                    });
                    
                    // Also handle form submission directly
                    form.addEventListener('submit', function(e) {
                        e.stopImmediatePropagation(); // Block all other handlers
                        // Let it submit naturally
                    }, true); // true = capture phase (runs FIRST)
                });
            });
            
            if (typeof jQuery === 'undefined') return;
            
            jQuery(document).ready(function($) {
                if (window.licenseEasyNotificationLoaded) return;
                window.licenseEasyNotificationLoaded = true;
                
                function showLicenseNotification(message, type) {
                    type = type || 'error';
                    var icon = type === 'error' ? '🔒' : '✅';
                    var className = type === 'error' ? 'notice-error' : 'notice-success';
                    
                    var $notice = $('<div class="notice ' + className + ' is-dismissible" style="position: fixed; top: 32px; right: 20px; z-index: 99999; min-width: 300px; animation: slideIn 0.3s ease-out;"><p>' + icon + ' ' + message + '</p></div>');
                    
                    $('body').append($notice);
                    
                    setTimeout(function() {
                        $notice.fadeOut(400, function() { 
                            $(this).remove(); 
                        });
                    }, 4000);
                    
                    $notice.find('.notice-dismiss').on('click', function() {
                        $notice.fadeOut(200, function() { 
                            $(this).remove(); 
                        });
                    });
                }
                
                $(document).ajaxComplete(function(event, xhr, settings) {
                    try {
                        var response = xhr.responseJSON;
                        if (!response && xhr.responseText) {
                            try {
                                response = JSON.parse(xhr.responseText);
                            } catch(e) {}
                        }
                        
                        if (response && response.success === false) {
                            var errorMsg = response.data;
                            
                            if (typeof errorMsg === 'string' && 
                                (errorMsg.toLowerCase().indexOf('license') !== -1 || 
                                 errorMsg.toLowerCase().indexOf('activate') !== -1)) {
                                showLicenseNotification(errorMsg, 'error');
                            }
                        }
                    } catch(e) {}
                });
                
                window.showLicenseNotification = showLicenseNotification;
            });
        })();
        </script>
        <style>
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        </style>
        <?php
    }
    
    public function handle_license_form() {
        if (!isset($_POST['license_easy_action']) || !isset($_POST['license_easy_nonce'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['license_easy_nonce'], 'license_easy_action')) {
            return;
        }
        
        $action = sanitize_text_field($_POST['license_easy_action']);
        
        if ($action === 'activate') {
            $this->activate_license(sanitize_text_field($_POST['license_key']));
            if (function_exists('wp_safe_redirect')) {
                wp_safe_redirect(add_query_arg('license_action', 'activated', $_SERVER['REQUEST_URI']));
                exit;
            }
        } elseif ($action === 'deactivate') {
            $this->deactivate_license();
            if (function_exists('wp_safe_redirect')) {
                wp_safe_redirect(add_query_arg('license_action', 'deactivated', $_SERVER['REQUEST_URI']));
                exit;
            }
        }
    }
    
    public function activate_license($license_key) {
        if (empty($license_key)) {
            $this->add_admin_notice('Please enter a license key.', 'error');
            return array('success' => false, 'message' => 'Please enter a license key.');
        }
        
        $website_url = $this->get_website_url();
        
        $response = $this->api_request('activate', array(
            'purchase_code' => $license_key,
            'product_slug' => $this->product_slug,
            'domain' => $website_url
        ));
        
        if (isset($response['status']) && $response['status'] === 'success') {
            $license_data = array_merge($response, array(
                'status' => 'active',
                'license_key' => $license_key,
                'website_url' => $website_url,
                'activated_at' => date('Y-m-d H:i:s')
            ));
            unset($license_data['message']);
            $this->save_license_data($license_data);
            $this->add_admin_notice($response['message'], 'success');
            return array('success' => true, 'message' => $response['message']);
        } else {
            $message = isset($response['message']) ? $response['message'] : 'License activation failed.';
            $this->add_admin_notice($message, 'error');
            return array('success' => false, 'message' => $message);
        }
    }
    
    public function deactivate_license() {
        $license_data = $this->get_license_data();
        
        if (empty($license_data['license_key'])) {
            $this->add_admin_notice('No license to deactivate.', 'error');
            return array('success' => false, 'message' => 'No license to deactivate.');
        }
        
        $response = $this->api_request('deactivate', array(
            'purchase_code' => $license_data['license_key'],
            'product_slug' => $this->product_slug,
            'domain' => $license_data['website_url']
        ));
        
        if (isset($response['status']) && $response['status'] === 'success') {
            $this->save_license_data(array(
                'status' => 'inactive',
                'license_key' => '',
                'website_url' => '',
                'activated_at' => ''
            ));
            $this->add_admin_notice($response['message'], 'success');
            return array('success' => true, 'message' => $response['message']);
        } else {
            $message = isset($response['message']) ? $response['message'] : 'License deactivation failed.';
            
            $normalized = strtolower($message);
            if (strpos($normalized, 'not found') !== false || strpos($normalized, 'already') !== false) {
                $this->save_license_data(array(
                    'status' => 'inactive',
                    'license_key' => '',
                    'website_url' => '',
                    'activated_at' => ''
                ));
                $this->add_admin_notice('License removed from this site.', 'success');
                return array('success' => true, 'message' => 'License removed from this site.');
            }
            
            $this->add_admin_notice($message, 'error');
            return array('success' => false, 'message' => $message);
        }
    }
    
    private function api_request($endpoint, $data) {
        $url = $this->api_url . $endpoint;
        
        $args = array(
            'body' => json_encode($data),
            'headers' => array('Content-Type' => 'application/json'),
            'timeout' => 30,
            'method' => 'POST'
        );
        
        if (function_exists('wp_remote_post')) {
            $response = wp_remote_post($url, $args);
            
            if (is_wp_error($response)) {
                return array('success' => false, 'message' => $response->get_error_message());
            }
            
            $code = wp_remote_retrieve_response_code($response);
            $body = wp_remote_retrieve_body($response);
        } else {
            $body = $this->curl_request($url, json_encode($data));
            
            if ($body === false) {
                return array('success' => false, 'message' => 'Unable to connect to license server.');
            }
            $code = 200;
        }
        
        $result = json_decode($body, true);
        if (!is_array($result)) {
            $snippet = trim(substr($body, 0, 200));
            $msg = 'Invalid response from server. HTTP ' . intval($code) . '. Body: ' . $snippet;
            return array('success' => false, 'message' => $msg);
        }
        return $result;
    }
    
    private function curl_request($url, $json_data) {
        if (!function_exists('curl_init')) {
            return false;
        }
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
    
    private function get_website_url() {
        if (function_exists('home_url')) {
            return home_url();
        }
        
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $path = '';
        
        if (isset($_SERVER['SCRIPT_NAME'])) {
            $path = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            $path = rtrim($path, '/');
        }
        
        return $protocol . $host . $path . '/';
    }
    
    private function add_admin_notice($message, $type = 'info') {
        if (function_exists('add_settings_error')) {
            add_settings_error('license_easy_messages', 'license_easy_message', $message, $type);
        } else {
            $_SESSION['license_easy_message'] = array('message' => $message, 'type' => $type);
        }
    }
    
    public function render_license_page() {
        $license_data = $this->get_license_data();
        $is_active = $this->is_active();
        
        if (function_exists('settings_errors')) {
            settings_errors('license_easy_messages');
        }
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html($this->product_name); ?> - License Activation</h1>
            
            <div style="background: #fff; border: 1px solid #ccd0d4; border-radius: 4px; padding: 20px; max-width: 600px; margin-top: 20px;">
                
                <?php if ($is_active): ?>
                    <div style="background: #d1fae5; border-left: 4px solid #10b981; padding: 12px 16px; margin-bottom: 20px;">
                        <strong style="color: #065f46;">✅ License Active</strong>
                        <p style="margin: 8px 0 0 0; color: #065f46;">Your license is activated and this product is ready to use.</p>
                    </div>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">License Key</th>
                            <td><code><?php echo esc_html($license_data['license_key']); ?></code></td>
                        </tr>
                        <tr>
                            <th scope="row">Website URL</th>
                            <td><code><?php echo esc_html($license_data['website_url']); ?></code></td>
                        </tr>
                        <tr>
                            <th scope="row">Activated At</th>
                            <td><?php echo esc_html($license_data['activated_at']); ?></td>
                        </tr>
                    </table>
                    
                    <form method="post" action="">
                        <?php wp_nonce_field('license_easy_action', 'license_easy_nonce'); ?>
                        <input type="hidden" name="license_easy_action" value="deactivate">
                        <button type="submit" class="button button-secondary" onclick="return confirm('Are you sure you want to deactivate your license?');">
                            Deactivate License
                        </button>
                    </form>
                    
                <?php else: ?>
                    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; margin-bottom: 20px;">
                        <strong style="color: #92400e;">⚠️ License Not Activated</strong>
                        <p style="margin: 8px 0 0 0; color: #92400e;">Please activate your license to use this product.</p>
                    </div>
                    
                    <form method="post" action="">
                        <?php wp_nonce_field('license_easy_action', 'license_easy_nonce'); ?>
                        <input type="hidden" name="license_easy_action" value="activate">
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="license_key">License Key *</label>
                                </th>
                                <td>
                                    <input type="text" 
                                           id="license_key" 
                                           name="license_key" 
                                           class="regular-text" 
                                           placeholder="Enter your purchase code"
                                           required>
                                    <p class="description">Enter your Envato purchase code to activate this product.</p>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="submit" class="button button-primary">Activate License</button>
                        </p>
                    </form>
                <?php endif; ?>
                
            </div>
            
            <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 16px; max-width: 600px; margin-top: 20px;">
                <h3 style="margin-top: 0;">ℹ️ About License Activation</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Your license key is your Envato purchase code</li>
                    <li>One license can be activated on one website at a time</li>
                    <li>Deactivate the license before moving to a different website</li>
                    <li>License validation is handled securely via License Easy</li>
                </ul>
            </div>
        </div>
        <?php
    }
    
    private function handle_standalone_request() {
        if (!isset($_GET['license_easy_page']) || $_GET['license_easy_page'] !== $this->product_slug) {
            return;
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = sanitize_file_name($_POST['action']);
            
            if ($action === 'activate' && isset($_POST['license_key'])) {
                $this->activate_license_standalone(sanitize_text_field($_POST['license_key']));
            } elseif ($action === 'deactivate') {
                $this->deactivate_license_standalone();
            }
        }
        
        $this->render_standalone_page();
        exit;
    }
    
    private function activate_license_standalone($license_key) {
        if (empty($license_key)) {
            $_SESSION['license_message'] = array('type' => 'error', 'message' => 'Please enter a license key.');
            return;
        }
        
        $website_url = $this->get_website_url();
        
        $response = $this->api_request('activate', array(
            'purchase_code' => $license_key,
            'product_slug' => $this->product_slug,
            'domain' => $website_url
        ));
        
        if (isset($response['status']) && $response['status'] === 'success') {
            $this->save_license_data(array(
                'status' => 'active',
                'license_key' => $license_key,
                'website_url' => $website_url,
                'activated_at' => date('Y-m-d H:i:s')
            ));
            $_SESSION['license_message'] = array('type' => 'success', 'message' => $response['message']);
        } else {
            $message = isset($response['message']) ? $response['message'] : 'License activation failed.';
            $_SESSION['license_message'] = array('type' => 'error', 'message' => $message);
        }
    }
    
    private function deactivate_license_standalone() {
        $license_data = $this->get_license_data();
        
        if (empty($license_data['license_key'])) {
            $_SESSION['license_message'] = array('type' => 'error', 'message' => 'No license to deactivate.');
            return;
        }
        
        $response = $this->api_request('deactivate', array(
            'purchase_code' => $license_data['license_key'],
            'product_slug' => $this->product_slug,
            'domain' => $license_data['website_url']
        ));
        
        if (isset($response['status']) && $response['status'] === 'success') {
            $this->save_license_data(array(
                'status' => 'inactive',
                'license_key' => '',
                'website_url' => '',
                'activated_at' => ''
            ));
            $_SESSION['license_message'] = array('type' => 'success', 'message' => $response['message']);
        } else {
            $message = isset($response['message']) ? $response['message'] : 'License deactivation failed.';
            $_SESSION['license_message'] = array('type' => 'error', 'message' => $message);
        }
    }
    
    private function render_standalone_page() {
        $license_data = $this->get_license_data();
        $is_active = $this->is_active();
        $message = isset($_SESSION['license_message']) ? $_SESSION['license_message'] : null;
        unset($_SESSION['license_message']);
        
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($this->product_name); ?> - License Activation</title>
            <style>
                * { box-sizing: border-box; }
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: 40px auto; }
                .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 20px; }
                h1 { margin: 0 0 8px 0; font-size: 24px; color: #111827; }
                .subtitle { color: #6b7280; margin: 0 0 24px 0; }
                .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid; }
                .alert-success { background: #d1fae5; border-color: #10b981; color: #065f46; }
                .alert-error { background: #fee2e2; border-color: #ef4444; color: #991b1b; }
                .alert-warning { background: #fef3c7; border-color: #f59e0b; color: #92400e; }
                .form-group { margin-bottom: 20px; }
                label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; }
                input[type="text"] { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
                input[type="text"]:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
                .btn { padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
                .btn-primary { background: #3b82f6; color: #fff; }
                .btn-primary:hover { background: #2563eb; }
                .btn-secondary { background: #6b7280; color: #fff; }
                .btn-secondary:hover { background: #4b5563; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
                th { font-weight: 600; color: #374151; }
                td { color: #6b7280; }
                code { background: #f3f4f6; padding: 2px 6px; border-radius: 3px; font-size: 13px; color: #1f2937; }
                .info-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 16px; }
                .info-box h3 { margin: 0 0 12px 0; font-size: 16px; color: #111827; }
                .info-box ul { margin: 0; padding-left: 20px; color: #6b7280; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="card">
                    <h1><?php echo htmlspecialchars($this->product_name); ?></h1>
                    <p class="subtitle">License Activation</p>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
                            <?php echo htmlspecialchars($message['message']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($is_active): ?>
                        <div class="alert alert-success">
                            <strong>✅ License Active</strong><br>
                            Your license is activated and this product is ready to use.
                        </div>
                        
                        <table>
                            <tr>
                                <th>License Key</th>
                                <td><code><?php echo htmlspecialchars($license_data['license_key']); ?></code></td>
                            </tr>
                            <tr>
                                <th>Website URL</th>
                                <td><code><?php echo htmlspecialchars($license_data['website_url']); ?></code></td>
                            </tr>
                            <tr>
                                <th>Activated At</th>
                                <td><?php echo htmlspecialchars($license_data['activated_at']); ?></td>
                            </tr>
                        </table>
                        
                        <form method="post" onsubmit="return confirm('Are you sure you want to deactivate your license?');">
                            <input type="hidden" name="action" value="deactivate">
                            <button type="submit" class="btn btn-secondary">Deactivate License</button>
                        </form>
                        
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <strong>⚠️ License Not Activated</strong><br>
                            Please activate your license to use this product.
                        </div>
                        
                        <form method="post">
                            <input type="hidden" name="action" value="activate">
                            
                            <div class="form-group">
                                <label for="license_key">License Key *</label>
                                <input type="text" id="license_key" name="license_key" placeholder="Enter your purchase code" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Activate License</button>
                        </form>
                    <?php endif; ?>
                </div>
                
                <div class="info-box">
                    <h3>ℹ️ About License Activation</h3>
                    <ul>
                        <li>Your license key is your Envato purchase code</li>
                        <li>One license can be activated on one website at a time</li>
                        <li>Deactivate the license before moving to a different website</li>
                        <li>License validation is handled securely via License Easy</li>
                    </ul>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
    
    public function block_if_not_active($die_message = null) {
        if (!$this->is_active()) {
            if ($this->is_wordpress) {
                wp_die(
                    $die_message ? $die_message : 'Please activate your license to use ' . esc_html($this->product_name) . '.',
                    'License Activation Required',
                    array('response' => 403)
                );
            } else {
                die($die_message ? $die_message : 'Please activate your license to use ' . htmlspecialchars($this->product_name) . '. <a href="?license_easy_page=' . urlencode($this->product_slug) . '">Activate Now</a>');
            }
        }
    }
}

} // End if (!class_exists('License_Easy_Universal_Client'))

if (!function_exists('sanitize_file_name')) {
    function sanitize_file_name($filename) {
        return preg_replace('/[^a-z0-9_\-]/', '', strtolower($filename));
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($text) {
        return trim(strip_tags($text));
    }
}

if (!function_exists('sanitize_title')) {
    function sanitize_title($title) {
        return preg_replace('/[^a-z0-9_\-]/', '', strtolower(str_replace(' ', '-', $title)));
    }
}