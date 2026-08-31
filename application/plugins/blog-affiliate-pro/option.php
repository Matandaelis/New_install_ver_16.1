<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['blog_affiliate_pro_plugin_option'])) update_option('blog_affiliate_pro_plugin_option', $_POST['blog_affiliate_pro_plugin_option']);
    if(isset($_POST['blog_affiliate_pro_paywall_message'])) update_option('blog_affiliate_pro_paywall_message', wp_kses_post($_POST['blog_affiliate_pro_paywall_message']));
    if(isset($_POST['blog_affiliate_pro_base_url'])) update_option('blog_affiliate_pro_base_url', esc_url_raw($_POST['blog_affiliate_pro_base_url']));
    if(isset($_POST['blog_affiliate_pro_track_free_posts'])) update_option('blog_affiliate_pro_track_free_posts', $_POST['blog_affiliate_pro_track_free_posts']);
}
$blog_affiliate_pro_plugin_option = (int)get_option('blog_affiliate_pro_plugin_option', 0);
$blog_affiliate_pro_paywall_message = get_option('blog_affiliate_pro_paywall_message', 'This content is for paid members only. <a href="#">Subscribe now</a> to access.');
$blog_affiliate_pro_base_url = get_option('blog_affiliate_pro_base_url', '');
$blog_affiliate_pro_track_free_posts = (int)get_option('blog_affiliate_pro_track_free_posts', 0);
?>
<div class="wrap">
    <h1>Blog Affiliate Pro Settings</h1>
    <?php settings_errors(); ?>
    <form method="post" action="">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Plugin Status</th>
                <td>
                    <select name="blog_affiliate_pro_plugin_option" id="blog_affiliate_pro_plugin_option">
                        <option value="1" <?php selected($blog_affiliate_pro_plugin_option, 1); ?>>Enable</option>
                        <option value="0" <?php selected($blog_affiliate_pro_plugin_option, 0); ?>>Disable</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Paywall Message</th>
                <td>
                    <textarea name="blog_affiliate_pro_paywall_message" rows="5" cols="50"><?php echo esc_textarea($blog_affiliate_pro_paywall_message); ?></textarea>
                    <p class="description">HTML is allowed. Use this to customize your paywall message.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Affiliate Script Base URL</th>
                <td>
                    <input type="url" name="blog_affiliate_pro_base_url" value="<?php echo esc_url($blog_affiliate_pro_base_url); ?>" class="regular-text">
                    <p class="description">Enter the base URL of your affiliate script (e.g., http://localhost/your-project/).</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Track Free Posts</th>
                <td>
                    <select name="blog_affiliate_pro_track_free_posts" id="blog_affiliate_pro_track_free_posts">
                        <option value="1" <?php selected($blog_affiliate_pro_track_free_posts, 1); ?>>Yes</option>
                        <option value="0" <?php selected($blog_affiliate_pro_track_free_posts, 0); ?>>No</option>
                    </select>
                    <p class="description">Choose whether to track views on free posts as well.</p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>