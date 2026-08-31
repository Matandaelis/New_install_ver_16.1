<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Robots extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // Set content type to text/plain for robots.txt
        header('Content-Type: text/plain');
        
        // Get the current domain dynamically
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $base_url = $protocol . '://' . $host;
        
        // Read the robots.txt template file
        $template_path = APPPATH . 'config/robots_template.txt';
        
        if (file_exists($template_path)) {
            // Read template content
            $robots_content = file_get_contents($template_path);
            
            // Replace the placeholder with the actual sitemap URL
            $robots_content = str_replace('{DYNAMIC_SITEMAP_URL}', $base_url . '/sitemap.xml', $robots_content);
        } else {
            // Fallback if template doesn't exist
            $robots_content = "User-agent: *\n";
            $robots_content .= "Disallow: /application/\n";
            $robots_content .= "Disallow: /system/\n";
            $robots_content .= "Disallow: /install/\n";
            $robots_content .= "Disallow: /admincontrol/\n";
            $robots_content .= "Disallow: /usercontrol/\n";
            $robots_content .= "Disallow: /assets/user_upload/\n";
            $robots_content .= "Disallow: /assets/cache/\n";
            $robots_content .= "Allow: /store/\n";
            $robots_content .= "Allow: /sitemap.xml\n\n";
            $robots_content .= "# Sitemap location - Auto-detected domain\n";
            $robots_content .= "Sitemap: " . $base_url . "/sitemap.xml\n\n";
            $robots_content .= "# Crawl delay for better server performance\n";
            $robots_content .= "Crawl-delay: 1\n";
        }
        
        // Output the robots.txt content
        echo $robots_content;
    }
}