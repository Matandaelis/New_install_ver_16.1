<?php
/*
Plugin Name: AffiliateProSaaS Integration
Description: Official integration plugin for AffiliateProSaaS script connecting WooCommerce affiliate ProSaaS sale campaigns to your WooCommerce site.
Author: All4Business
Version: 3.0.0
Author URI: https://affiliatepro.org
*/

// Include the affiliate ID display functionality
require_once plugin_dir_path(__FILE__) . 'show_aff_id.php';

function affi_load_textdomain() {
    load_plugin_textdomain('affiliateprosaas-integration', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'affi_load_textdomain');

function affi_action_links( $links ) {
	$links = array_merge( array('<a href="' . esc_url( admin_url( '/options-general.php?page=affi_settings_plugin' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'affi_action_links' );

function affi_settings_page() {
	add_menu_page(
	    'AffiliateProSaaS Settings',       // Page title
	    'AffiliateProSaaS',                // Menu title in sidebar
	    'manage_options',                  // Capability
	    'affi_settings_plugin',            // Menu slug
	    'affi_settings_page_fun',          // Function callback
	    'dashicons-networking',           // Icon (or customize)
	    60                                 // Menu position
	);
}
add_action( 'admin_menu', 'affi_settings_page' );

function affi_settings_page_fun(){
	include "option.php";
}

add_action('init','affi_init_fun');
function affi_init_fun(){
	$affi_woo_option = (int)get_option('affi_woo_option');
	$affi_plugin_option = (int)get_option('affi_plugin_option');
	$affi_cartflows_option = (int)get_option('affi_cartflows_option');
	
	// Initialize affiliate ID display functionality
	affi_init_display();

	if($affi_plugin_option){
		if($affi_woo_option || $affi_cartflows_option){
			add_action('wp_head','affi_wp_head_fun',10);
			if($affi_woo_option){
				add_action('woocommerce_thankyou','affi_woocommerce_thankyou_fun');
			}
			add_action( 'woocommerce_single_product_summary', 'fun_affiliate', 4 );
		}
	}
}

function affi_wp_head_fun(){ ?>
	<script type="text/javascript" src="__baseurl__integration/script"></script>
<?php }

/**
 * Get the base URL from the script output
 */
function affi_get_base_url() {
	$script_content = '';
	if (function_exists('affi_wp_head_fun')) {
		ob_start();
		affi_wp_head_fun();
		$script_content = ob_get_clean();
	}
	
	if (preg_match('/src="([^"]+)integration\/script"/', $script_content, $matches)) {
		return rtrim($matches[1], '/');
	}
	
	return '';
}

/**
 * Check connection status with affiliate system
 */
function affi_check_connection_status() {
	$base_url = affi_get_base_url();
	
	if (empty($base_url) || strpos($base_url, '__baseurl__') !== false) {
		return [
			'status' => 'not_configured',
			'message' => 'Plugin not downloaded from affiliate system yet',
			'base_url' => '__baseurl__ (placeholder)',
			'connected' => false
		];
	}
	
	$api_url = $base_url . "/integration/wp_status?site_url=" . urlencode(get_site_url());
	
	$response = wp_remote_get($api_url, [
		'timeout' => 15,
		'sslverify' => false,
		'headers' => [
			'User-Agent' => 'WordPress/' . get_bloginfo('version')
		]
	]);
	
	if (is_wp_error($response)) {
		return [
			'status' => 'disconnected',
			'message' => 'Connection error: ' . $response->get_error_message(),
			'base_url' => $base_url,
			'connected' => false
		];
	}
	
	$status_code = wp_remote_retrieve_response_code($response);
	$body = wp_remote_retrieve_body($response);
	
	if ($status_code === 200 && !empty($body)) {
		$data = json_decode($body, true);
		if ($data && isset($data['status']) && $data['status'] === 'connected') {
			$data['base_url'] = $base_url;
			$data['connected'] = true;
			return $data;
		}
	}
	
	return [
		'status' => 'disconnected', 
		'message' => 'Cannot connect to affiliate system (HTTP ' . $status_code . ')',
		'base_url' => $base_url,
		'connected' => false
	];
}

if(!function_exists('call_affiliate_api')){
	function call_affiliate_api($endpoint, $data){
		global $wp;
	    $data['current_page_url'] = base64_encode(home_url( $wp->request ));
		
		$base_url = affi_get_base_url();
		if (!empty($base_url) && strpos($base_url, '__baseurl__') === false) {
			$api_url = $base_url . '/' . $endpoint . '?' . http_build_query($data);
			
			wp_remote_get($api_url, [
				'timeout' => 10,
				'blocking' => false,
				'sslverify' => false,
				'headers' => [
					'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
					'Referer' => $data['current_page_url']
				]
			]);
		}
	}
}

function affi_woocommerce_thankyou_fun($order_ID){ 
	$order = new WC_Order($order_ID);

	$ipaddress = '';
	if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");
	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");
	else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");
	else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");
	else $ipaddress = "UNKNOWN";

	$affiliateData = array(
		"order_id"       => $order->get_id(),
		"order_currency" => $order->get_order_currency(),
		"order_total"    => ($order->get_total() - $order->get_shipping_total() - $order->get_total_tax()),
		"product_ids"    => array(),
		"af_id"          => isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : null,
		"ip"             => $ipaddress,
		"base_url"       => base64_encode(get_site_url()),
		"script_name"    => "woocommerce",
	);

	foreach ($order->get_items() as $item) {
		$product = $item->get_product();
		if ($product) $affiliateData["product_ids"][] = $product->get_id();
	}

	$enabled_fields = get_option('affi_enabled_client_fields', []);
	$custom_data = [];

	if (in_array('client_name', $enabled_fields))
		$custom_data[__('Client Name', 'affiliateprosaas-integration')] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
	if (in_array('client_email', $enabled_fields))
		$custom_data[__('Client Email', 'affiliateprosaas-integration')] = $order->get_billing_email();
	if (in_array('client_phone', $enabled_fields))
		$custom_data[__('Client Phone', 'affiliateprosaas-integration')] = $order->get_billing_phone();

	if (in_array('billing_address', $enabled_fields))
		$custom_data[__('Billing Address', 'affiliateprosaas-integration')] = $order->get_billing_address_1();
	if (in_array('billing_city', $enabled_fields))
		$custom_data[__('Billing City', 'affiliateprosaas-integration')] = $order->get_billing_city();
	if (in_array('billing_state', $enabled_fields))
		$custom_data[__('Billing State', 'affiliateprosaas-integration')] = $order->get_billing_state();
	if (in_array('billing_postcode', $enabled_fields))
		$custom_data[__('Billing Postcode', 'affiliateprosaas-integration')] = $order->get_billing_postcode();
	if (in_array('billing_country', $enabled_fields))
		$custom_data[__('Billing Country', 'affiliateprosaas-integration')] = $order->get_billing_country();

	if (in_array('shipping_address', $enabled_fields))
		$custom_data[__('Shipping Address', 'affiliateprosaas-integration')] = $order->get_shipping_address_1();
	if (in_array('shipping_city', $enabled_fields))
		$custom_data[__('Shipping City', 'affiliateprosaas-integration')] = $order->get_shipping_city();
	if (in_array('shipping_state', $enabled_fields))
		$custom_data[__('Shipping State', 'affiliateprosaas-integration')] = $order->get_shipping_state();
	if (in_array('shipping_postcode', $enabled_fields))
		$custom_data[__('Shipping Postcode', 'affiliateprosaas-integration')] = $order->get_shipping_postcode();
	if (in_array('shipping_country', $enabled_fields))
		$custom_data[__('Shipping Country', 'affiliateprosaas-integration')] = $order->get_shipping_country();

	if (in_array('payment_method', $enabled_fields))
		$custom_data[__('Payment Method', 'affiliateprosaas-integration')] = $order->get_payment_method_title();
	if (in_array('customer_note', $enabled_fields))
		$custom_data[__('Customer Note', 'affiliateprosaas-integration')] = $order->get_customer_note();
	if (in_array('user_id', $enabled_fields)) {
	    if ($order->get_user_id()) {
	        $custom_data[__('User ID', 'affiliateprosaas-integration')] = $order->get_user_id();
	    } else {
	        $custom_data[__('User ID', 'affiliateprosaas-integration')] = __('Guest Order', 'affiliateprosaas-integration');
	    }
	}

	if (in_array('user_email', $enabled_fields)) {
	    if ($order->get_user_id()) {
	        $custom_data[__('User Email', 'affiliateprosaas-integration')] = get_userdata($order->get_user_id())->user_email;
	    } else {
	        $custom_data[__('User Email', 'affiliateprosaas-integration')] = $order->get_billing_email() . ' (' . __('Guest', 'affiliateprosaas-integration') . ')';
	    }
	}

	if (in_array('profile_url', $enabled_fields)) {
	    if ($order->get_user_id()) {
	        $custom_data[__('Profile URL', 'affiliateprosaas-integration')] = get_edit_user_link($order->get_user_id());
	    } else {
	        $custom_data[__('Profile URL', 'affiliateprosaas-integration')] = __('No Profile - Guest Order', 'affiliateprosaas-integration');
	    }
	}

	$affiliateData["customFields"] = json_encode($custom_data);

	call_affiliate_api('integration/addOrder', $affiliateData);
}

function fun_affiliate(){
	global $product, $post;

	$affi_woo_option = (int)get_option('affi_woo_option');
	$affi_cartflows_option = (int)get_option('affi_cartflows_option');

	$allow_page = array();
	if($affi_woo_option) $allow_page[] = "product";
	if($affi_cartflows_option) $allow_page[] = "cartflows_step";

	if($affi_cartflows_option && 
	   isset($_GET['wcf-key'],$_GET['wcf-order']) && $_GET['wcf-order'] && 
	   (function_exists('_is_wcf_thankyou_type') && _is_wcf_thankyou_type())
	){
		affi_woocommerce_thankyou_fun($_GET['wcf-order']);
	}else if($post && in_array($post->post_type, $allow_page) && is_single()){
		if (is_singular('product') && get_queried_object_id() == $post->ID) {
			$ipaddress = "";
			if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");
			else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");
			else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");
			else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");
			else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");
			else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");
			else $ipaddress = "UNKNOWN";

			$affiliateData = array(
				"product_id"  => $product->get_id(),
				"af_id"       => isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : null,
				"ip"          => $ipaddress,
				"base_url"    => base64_encode(get_site_url()),
				"script_name" => "woocommerce",
			);

			call_affiliate_api('integration/addClick', $affiliateData);
		}
	} 
}