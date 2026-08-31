<?php
/**
 * Affiliate ID Display Functions
 * File: show_aff_id.php
 */
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add affiliate ID display script to head
 */
function affi_add_affiliate_display_script() {
    // Get settings from database
    $position = get_option('affi_display_position', 'bottom');
    $custom_text = get_option('affi_display_text', 'Affiliate ID is {id}');
    
    $position_map = [
        'top left' => 'top-left',
        'top right' => 'top-right', 
        'bottom left' => 'bottom-left',
        'bottom right' => 'bottom-right'
    ];
    
    $script_position = isset($position_map[$position]) ? $position_map[$position] : $position;
    
    ?>
    <script type="text/javascript" src="__baseurl__integration/show_affiliate_id"></script>
    <script type="text/javascript">
        var af_df_setting = {
          position: '<?php echo esc_js($script_position); ?>',
          text: '<?php echo esc_js($custom_text); ?>',
        }
    </script>
    <?php
}

/**
 * Initialize affiliate ID display
 */
function affi_init_display() {
    // Check if both plugin and display are enabled
    $affi_plugin_option = (int)get_option('affi_plugin_option');
    $affi_display_enabled = (int)get_option('affi_display_enabled');
    
    if ($affi_plugin_option && $affi_display_enabled) {
        add_action('wp_head', 'affi_add_affiliate_display_script');
    }
}

/**
 * Store affiliate ID from cookie on order completion
 */
function affi_store_affiliate_on_order($order_id) {
    // Check if Plugin Core is enabled
    $affi_plugin_option = (int)get_option('affi_plugin_option');
    if (!$affi_plugin_option) {
        return; // Exit if Plugin Core is disabled
    }
    
    // Check if WooCommerce or CartFlows is enabled
    $affi_woo_option = (int)get_option('affi_woo_option');
    $affi_cartflows_option = (int)get_option('affi_cartflows_option');
    if (!$affi_woo_option && !$affi_cartflows_option) {
        return; // Exit if neither WooCommerce nor CartFlows is enabled
    }
    
    if (isset($_COOKIE['af_id']) && !empty($_COOKIE['af_id'])) {
        $affiliate_id = sanitize_text_field($_COOKIE['af_id']);
        
        // Use WooCommerce order API for HPOS compatibility
        $order = wc_get_order($order_id);
        if ($order) {
            $order->update_meta_data('_affiliate_id', $affiliate_id);
            $order->save();
        }
    }
}

/**
 * Get decoded affiliate ID
 */
function affi_get_decoded_id($encoded_id) {
    $base_url = affi_get_base_url();
    
    if (!empty($base_url) && strpos($base_url, '__baseurl__') === false) {
        $api_url = $base_url . "/integration/get_affiliate_info?id=" . urlencode($encoded_id);
        
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 3,
                'ignore_errors' => true
            ]
        ]);
        
        $response = @file_get_contents($api_url, false, $context);
        
        if ($response) {
            $data = json_decode($response, true);
            if ($data && isset($data['affiliate_id'])) {
                $full_id = $data['affiliate_id'];
                $parts = explode('-', $full_id);
                return $parts[0];
            }
        }
    }
    
    // Fallback decoding
    $parts = explode('-', $encoded_id);
    $last_part = end($parts);
    
    if (base64_encode(base64_decode($last_part, true)) === $last_part) {
        $decoded = base64_decode($last_part);
        $decoded_parts = explode('-', $decoded);
        return $decoded_parts[0];
    }
    
    return $last_part;
}

/**
 * Display affiliate ID in order details
 */
function affi_display_affiliate_in_order($order) {
    // Check if admin order details display is enabled
    $admin_details = (int)get_option('affi_admin_order_details');
    if (!$admin_details) {
        return;
    }
    
    $affiliate_id = $order->get_meta('_affiliate_id');
    if ($affiliate_id) {
        $decoded_id = affi_get_decoded_id($affiliate_id);
        echo '<div class="address">';
        echo '<p><strong>Affiliate ID: </strong><span style="color: #0073aa; font-weight: bold;">' . esc_html($decoded_id) . '</span></p>';
        echo '</div>';
    }
}

/**
 * Add affiliate ID column to orders list
 */
function affi_add_affiliate_column($columns) {
    // Check if admin orders list display is enabled
    $admin_list = (int)get_option('affi_admin_orders_list');
    if (!$admin_list) {
        return $columns;
    }
    
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'order_status') {
            $new_columns['affiliate_id'] = __('Affiliate ID', 'woocommerce');
        }
    }
    return $new_columns;
}

/**
 * Display affiliate ID in orders list column
 */
function affi_display_affiliate_column($column, $post_id) {
    if ($column === 'affiliate_id') {
        $order = wc_get_order($post_id);
        if ($order) {
            $affiliate_id = $order->get_meta('_affiliate_id');
            if ($affiliate_id) {
                $decoded_id = affi_get_decoded_id($affiliate_id);
                echo '<span style="color: #0073aa; font-weight: bold;">' . esc_html($decoded_id) . '</span>';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
        } else {
            echo '<span style="color: #999;">—</span>';
        }
    }
}


/**
 * Initialize affiliate column functionality
 */
function affi_init_admin_columns() {
    // Check if Plugin Core is enabled first
    $affi_plugin_option = (int)get_option('affi_plugin_option');
    if (!$affi_plugin_option) {
        return; // Exit if Plugin Core is disabled
    }
    
    // Check if WooCommerce or CartFlows is enabled
    $affi_woo_option = (int)get_option('affi_woo_option');
    $affi_cartflows_option = (int)get_option('affi_cartflows_option');
    if (!$affi_woo_option && !$affi_cartflows_option) {
        return; // Exit if neither WooCommerce nor CartFlows is enabled
    }
    
    // Check admin display settings
    $admin_details = (int)get_option('affi_admin_order_details');
    $admin_list = (int)get_option('affi_admin_orders_list');
    
    // Only add hooks if at least one admin feature is enabled
    if ($admin_details || $admin_list) {
        // Order details display
        if ($admin_details) {
            add_action('woocommerce_admin_order_data_after_billing_address', 'affi_display_affiliate_in_order');
        }
        
        // Orders list column
        if ($admin_list) {
            // HPOS hooks (WooCommerce 8.2+)
            add_filter('manage_woocommerce_page_wc-orders_columns', 'affi_add_affiliate_column');
            add_action('manage_woocommerce_page_wc-orders_custom_column', 'affi_display_affiliate_column', 10, 2);
            
            // Legacy hooks (older WooCommerce versions)
            add_filter('manage_shop_order_posts_columns', 'affi_add_affiliate_column');
            add_action('manage_shop_order_posts_custom_column', 'affi_display_affiliate_column', 10, 2);
        }
    }
}


// Initialize functionality
add_action('woocommerce_thankyou', 'affi_store_affiliate_on_order');
add_action('admin_init', 'affi_init_admin_columns');