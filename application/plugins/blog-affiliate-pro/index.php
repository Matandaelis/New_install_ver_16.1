<?php
/*
Plugin Name: Blog Affiliate Pro
Plugin URI: 
Description: Blog Affiliate Pro for paid content integration.
Author: Your Name
Version: 1.0.0
Author URI: https://yourwebsite.com/
*/

function blog_affiliate_pro_action_links($links) {
    $links = array_merge(array('<a href="' . esc_url(admin_url('/options-general.php?page=blog_affiliate_pro_settings')) . '">' . __('Settings', 'textdomain') . '</a>'), $links);
    return $links;
}
add_action('plugin_action_links_' . plugin_basename(__FILE__), 'blog_affiliate_pro_action_links');

function blog_affiliate_pro_settings_page() {
    add_options_page('Blog Affiliate Pro Settings', 'Blog Affiliate Pro', 'manage_options', 'blog_affiliate_pro_settings', 'blog_affiliate_pro_settings_page_fun');
}
add_action('admin_menu', 'blog_affiliate_pro_settings_page');

function blog_affiliate_pro_settings_page_fun() {
    include "option.php";
}

add_action('init', 'blog_affiliate_pro_init_fun');
function blog_affiliate_pro_init_fun() {
    $blog_affiliate_pro_plugin_option = (int)get_option('blog_affiliate_pro_plugin_option');
    if ($blog_affiliate_pro_plugin_option) {
        add_action('wp_head', 'blog_affiliate_pro_wp_head_fun', 10);
        add_action('the_content', 'blog_affiliate_pro_content_filter');
        add_action('add_meta_boxes', 'blog_affiliate_pro_add_meta_box');
        add_action('save_post', 'blog_affiliate_pro_save_meta_box_data');
        add_action('wp', 'blog_affiliate_pro_track_view');
    }
}

function blog_affiliate_pro_wp_head_fun() {
    $blog_affiliate_pro_base_url = esc_url(get_option('blog_affiliate_pro_base_url', ''));
    if (!empty($blog_affiliate_pro_base_url)) {
        echo '<script type="text/javascript" src="' . $blog_affiliate_pro_base_url . 'integration/script"></script>';
    }
}

function blog_affiliate_pro_call_api($endpoint, $data) {
    global $wp;
    $blog_affiliate_pro_base_url = esc_url(get_option('blog_affiliate_pro_base_url', ''));
    if (empty($blog_affiliate_pro_base_url)) {
        error_log("Blog Affiliate Pro: base URL is not set.");
        return false;
    }
    $data['current_page_url'] = base64_encode(home_url($wp->request));
    $context_options = stream_context_create(array(
        'http' => array(
            'method' => "GET",
            'header' => "User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n" . "Referer: " . $data['current_page_url'] . "\r\n"
        )
    ));
    $url = $blog_affiliate_pro_base_url . $endpoint . "?" . http_build_query($data);
    error_log("Blog Affiliate Pro: Calling API: " . $url);
    
    try {
        $response = @file_get_contents($url, false, $context_options);
        if ($response === false) {
            $error = error_get_last();
            error_log("Blog Affiliate Pro: API call failed. Error: " . print_r($error, true));
            return false;
        }
        return $response;
    } catch (Exception $e) {
        error_log("Blog Affiliate Pro: Exception occurred during API call. " . $e->getMessage());
        return false;
    }
}

function blog_affiliate_pro_content_filter($content) {
    global $post;
    $access_level = get_post_meta($post->ID, '_blog_affiliate_pro_access', true);

    if ($access_level === 'paid' && !blog_affiliate_pro_user_has_access()) {
        return blog_affiliate_pro_get_paywall_content();
    }

    return $content;
}

function blog_affiliate_pro_user_has_access() {
    global $post;
    $vendor_id = get_post_meta($post->ID, '_blog_affiliate_pro_vendor_id', true);
    $access_level = get_post_meta($post->ID, '_blog_affiliate_pro_access', true);
    
    if ($access_level !== 'paid') {
        return true; // Free posts are always accessible
    }

    // Check vendor membership status
    $membership_status = blog_affiliate_pro_check_vendor_membership($vendor_id);
    return $membership_status === 'active';
}

function blog_affiliate_pro_check_vendor_membership($vendor_id) {
    $blog_affiliate_pro_base_url = esc_url(get_option('blog_affiliate_pro_base_url', ''));
    $endpoint = 'integration/checkVendorMembership';
    $data = array(
        'vendor_id' => $vendor_id,
    );
    
    $response = wp_remote_get($blog_affiliate_pro_base_url . $endpoint . '?' . http_build_query($data));
    
    if (is_wp_error($response)) {
        error_log('Blog Affiliate Pro: Error checking vendor membership - ' . $response->get_error_message());
        return 'inactive'; // Assume inactive if there's an error
    }
    
    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);
    
    return isset($result['status']) ? $result['status'] : 'inactive';
}

function blog_affiliate_pro_get_paywall_content() {
    return wp_kses_post(get_option('blog_affiliate_pro_paywall_message', 'This content is for paid members only. <a href="#">Subscribe now</a> to access.'));
}

function blog_affiliate_pro_add_meta_box() {
    add_meta_box(
        'blog_affiliate_pro_meta_box',
        'Blog Affiliate Pro Settings',
        'blog_affiliate_pro_meta_box_callback',
        'post',
        'side',
        'high'
    );
}

function blog_affiliate_pro_meta_box_callback($post) {
    wp_nonce_field('blog_affiliate_pro_save_meta_box_data', 'blog_affiliate_pro_meta_box_nonce');
    $access_level = get_post_meta($post->ID, '_blog_affiliate_pro_access', true);
    $vendor_id = get_post_meta($post->ID, '_blog_affiliate_pro_vendor_id', true);
    ?>
    <p>
        <label for="blog_affiliate_pro_access_field">Access Level:</label>
        <select name="blog_affiliate_pro_access_field" id="blog_affiliate_pro_access_field">
            <option value="free" <?php selected($access_level, 'free'); ?>>Free</option>
            <option value="paid" <?php selected($access_level, 'paid'); ?>>Paid</option>
        </select>
    </p>
    <p>
        <label for="blog_affiliate_pro_vendor_id_field">Vendor ID:</label>
        <input type="number" name="blog_affiliate_pro_vendor_id_field" id="blog_affiliate_pro_vendor_id_field" value="<?php echo esc_attr($vendor_id); ?>">
    </p>
    <?php
}

function blog_affiliate_pro_save_meta_box_data($post_id) {
    if (!isset($_POST['blog_affiliate_pro_meta_box_nonce']) || !wp_verify_nonce($_POST['blog_affiliate_pro_meta_box_nonce'], 'blog_affiliate_pro_save_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['blog_affiliate_pro_access_field'])) {
        update_post_meta($post_id, '_blog_affiliate_pro_access', sanitize_text_field($_POST['blog_affiliate_pro_access_field']));
    }
    if (isset($_POST['blog_affiliate_pro_vendor_id_field'])) {
        update_post_meta($post_id, '_blog_affiliate_pro_vendor_id', intval($_POST['blog_affiliate_pro_vendor_id_field']));
    }
}

function blog_affiliate_pro_track_view() {
    global $post;
    
    if (!is_single()) {
        return;
    }

    $access_level = get_post_meta($post->ID, '_blog_affiliate_pro_access', true);
    $vendor_id = get_post_meta($post->ID, '_blog_affiliate_pro_vendor_id', true);
    $track_free_posts = (int)get_option('blog_affiliate_pro_track_free_posts', 0);
    
    if ($access_level !== 'paid' && !$track_free_posts) {
        return;
    }

    $ipaddress = $_SERVER['REMOTE_ADDR'];
    $af_id = isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : null;

    // If af_id is not set, create one
    if (!$af_id) {
        $current_user_id = get_current_user_id();
        $af_id = $current_user_id . '-' . $post->ID;
        $af_id = blog_affiliate_pro_encrypt($af_id); // Use your encryption function here
        setcookie("af_id", $af_id, time() + (86400 * 30), "/"); // Set cookie for 30 days
    }

    $viewData = array(
        "post_id"     => $post->ID,
        "af_id"       => $af_id,
        "ip"          => $ipaddress,
        "base_url"    => base64_encode(get_site_url()),
        "script_name" => "blog_affiliate_pro",
        "is_paid"     => $access_level === 'paid' ? 1 : 0,
        "vendor_id"   => $vendor_id,
        "user_agent"  => $_SERVER['HTTP_USER_AGENT'],
        "request_uri" => $_SERVER['REQUEST_URI'],
    );

    blog_affiliate_pro_call_api('integration/addBlogView', $viewData);
}

// Placeholder encryption function
function blog_affiliate_pro_encrypt($data) {
    // Replace this with your actual encryption logic
    return base64_encode($data);
}