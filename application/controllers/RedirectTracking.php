<?php

if (!defined('BASEPATH')) exit ('No direct script access allowed');

ini_set('display_errors', 0);

use App\Slug;
class RedirectTracking extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		// Load utility helper for spam protection
		$this->load->helper('utility');
	}
	
	public function external_integration($code) {

		list($user_id,$ads_id) = explode("-",_encrypt_decrypt(parse_affiliate_id($code),'decrypt'));
		$integration_tools_ads = $this->db->query("SELECT * from integration_tools_ads WHERE id = ".$ads_id)->row_array();
		
		if(empty($integration_tools_ads)) {
			show_404();
		}

		$integration_tools = $this->db->query("SELECT * from integration_tools WHERE id = ".$integration_tools_ads['tools_id'])->row_array();

		if(empty($integration_tools)) {
			show_404();
		}

		$this->load->model('Product_model');
		if (!$this->Product_model->user_can_promote_market_campaign_for_user_id((int) $user_id, $integration_tools)) {
			market_promotion_http_block();
		}

		$this->load->model('IntegrationModel');
		
	// S2S Phase 1: Generate a unique click token for server-side tracking
	$click_token = generate_click_token();

	if($integration_tools['tool_integration_plugin'] == 'stripe'){
		$_SESSION['stripe_affiliate_id'] = $code;
		$_SESSION['stripe_user_id'] = $user_id;
		$_SESSION['stripe_campaign_id'] = $integration_tools['id'];
		$_SESSION['stripe_click_token'] = $click_token;
		$redirectLocation[] = base_url('stripe_integration/create_session/' . $integration_tools['id']);
	} else {
		$url = $this->IntegrationModel->addParams($integration_tools['target_link'], "af_id", $code);
		$url = $this->IntegrationModel->addParams($url, "click_token", $click_token);
		$redirectLocation[] = $url;

		// Persist click_token for server-side conversion lookup when S2S is enabled.
		if(!empty($integration_tools['s2s_enabled'])){
			$this->_register_s2s_pending_click(
				$click_token,
				$user_id,
				$ads_id,
				$integration_tools['id'],
				$integration_tools['target_link']
			);
		}
	}

		//new view unique counter 
		$this->saveToolsView($integration_tools_ads['tools_id'],$user_id);

		if($integration_tools['cookies_type'] == 1) {
			$_SESSION['aff_external_cookies_duration'] = $integration_tools['custom_cookies'];
		} else {
			$_SESSION['aff_external_cookies_duration'] = $this->IntegrationModel->getDefaultCookiesDuration();
		}

		?>

		<!DOCTYPE html>
		<html lang="en">
		  <head>
		    <meta charset="UTF-8">
		    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		    <meta http-equiv="X-UA-Compatible" content="ie=edge">
		    <meta charset="UTF-8" />
    		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<meta property='og:url' content='<?= current_url(); ?>'/>
			<meta property="og:title" content="<?= $integration_tools['name']; ?>"/>     
			<meta property="og:image" content="<?= base_url('assets/images/product/upload/thumb/'. $integration_tools['featured_image']); ?>"/>
			<meta name="image" property="og:image" content="<?= base_url('assets/images/product/upload/thumb/'. $integration_tools['featured_image']); ?>"> 
			<meta name="twitter:card" content="summary_large_image"/>
    		<title><?= $integration_tools['name']; ?></title>
		  </head>
		  <body>
			<script>
				window.location.replace('<?= $redirectLocation[0]; ?>');
			</script>
		  </body>
		</html>

		<?php
	}

public function redirect_tracking_url($slug){

    $Slug = Slug::where('slug', 'like', $slug)->first();
    if(!$Slug){
        show_404();
    }else{
        $this->load->model('Product_model');
        if($Slug->type == 'register'){
            header("Location: ".base_url('register/' . base64_encode($Slug->user_id)));
            exit();
        }else if($Slug->type == 'store'){
            header("Location: ".base_url('store/' . base64_encode($Slug->user_id)));
            exit();
        }else if($Slug->type == 'product'){
            $result = $this->db->query("SELECT product_id,is_campaign_product,product_slug,product_name,product_url,product_featured_image FROM product WHERE `product_id` = '".(int)$Slug->related_id."'")->row();

            if($result->is_campaign_product != 1) {
                header("Location: ".base_url('store/'.base64_encode($Slug->user_id).'/product/'.$result->product_slug));
                exit();
            } else {
                $af_id = _encrypt_decrypt($Slug->user_id."-".$Slug->related_id);
                $productFull = $this->db->query("SELECT * FROM product WHERE product_id = ?", [(int) $Slug->related_id])->row_array();
                if (!empty($productFull) && !$this->Product_model->user_can_promote_market_campaign_for_user_id((int) $Slug->user_id, $productFull)) {
                    market_promotion_http_block();
                }
            }
        }else if($Slug->type == 'form'){
            $formRow = $this->db->query("SELECT * FROM form WHERE form_id = ?", [(int) $Slug->related_id])->row_array();
            if (empty($formRow)) {
                show_404();
            }
            if (!$this->Product_model->user_can_promote_market_campaign_for_user_id((int) $Slug->user_id, $formRow)) {
                market_promotion_http_block();
            }
            header("Location: ".base_url('form/'.$formRow['seo'].'/'.base64_encode($Slug->user_id)));
            exit();
        } else if($Slug->type != 'product') {
            $result = $this->db->query("SELECT * FROM integration_tools WHERE `id` = '".(int)$Slug->related_id."' AND `tool_type` = '".$Slug->type."'")->row();
            if (empty($result)) {
                show_404();
            }
            if (!$this->Product_model->user_can_promote_market_campaign_for_user_id((int) $Slug->user_id, (array) $result)) {
                market_promotion_http_block();
            }
            if($result->cookies_type == 1) {
                $_SESSION['aff_external_cookies_duration'] = $result->custom_cookies;
            } else {
                $this->load->model('IntegrationModel');
                $_SESSION['aff_external_cookies_duration'] = $this->IntegrationModel->getDefaultCookiesDuration();
            }
            $af_id = _encrypt_decrypt($Slug->user_id."-".$result->id);
        }

        if(isset($result)){
            // S2S Phase 1: Generate a unique click token for server-side tracking
            $click_token = generate_click_token();

            if(($Slug->type != 'product')) {
                $adQuery = $this->db->query("SELECT id FROM integration_tools_ads WHERE tools_id = {$result->id}")->row();
                if($adQuery) {
                    $af_id = _encrypt_decrypt($Slug->user_id."-".$adQuery->id);
                }
                $this->db->query("UPDATE integration_tools SET trigger_count = trigger_count + 1  WHERE id = ".$result->id);
                $title = $result->name;
                
                if($result->tool_integration_plugin == 'stripe'){
                    $_SESSION['stripe_affiliate_id'] = $af_id;
                    $_SESSION['stripe_user_id'] = $Slug->user_id;
                    $_SESSION['stripe_campaign_id'] = $result->id;
                    $_SESSION['stripe_click_token'] = $click_token;
                    $url = base_url('stripe_integration/create_session/' . $result->id);
                } else {
                    $url = $this->addParams($result->target_link,"af_id",$af_id);
                    $url = $this->addParams($url, "click_token", $click_token);

                    // Persist click_token for server-side conversion lookup when S2S is enabled.
                    if(!empty($result->s2s_enabled)){
                        $this->_register_s2s_pending_click(
                            $click_token,
                            $Slug->user_id,
                            isset($adQuery->id) ? $adQuery->id : 0,
                            $result->id,
                            $result->target_link
                        );
                    }
                }
                $image = "assets/image_cache/cache/assets/images/product/upload/thumb/".$adQuery->value;
            } else {
                $title = $result->product_name;
                $url = $this->addParams($result->product_url,"af_id",$af_id);
                $url = $this->addParams($url, "click_token", $click_token);
                $image = "/assets/images/product/upload/thumb/".$result->product_featured_image;
            }
            
            ?>

            <!DOCTYPE html>
            <html lang="en">
              <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <meta charset="UTF-8" />
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

                <meta property='og:url' content='<?= current_url(); ?>'/>
                <meta property="og:title" content="<?= $title; ?>"/>     
                <meta property="og:image" content="<?= base_url($image); ?>"/>  
                <meta name="image" property="og:image" content="<?= base_url($image); ?>"/> 
                <meta name="twitter:card" content="summary_large_image"/>
                <title><?= $title; ?></title>
              </head>
              <body>
                <script>
                    window.location.href = '<?= $url; ?>';
                </script>
              </body>
            </html>

            <?php

            die();
        }
    }
}


	public function addParams($url, $key, $value) {
		$url = preg_replace('/(.*)(?|&)'. $key .'=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
		$url = substr($url, 0, -1);
		
		if (strpos($url, '?') === false) {
			return ($url .'?'. $key .'='. $value);
		} else {
			return ($url .'&'. $key .'='. $value);
		}
	}

	public function saveToolsView($tools_id,$user_id)
	{
		if(isset($tools_id) && isset($user_id))
		{
			if(isset($_COOKIE))
			$session_id = $_COOKIE['tools_view_ck'];
			else
				$session_id=null;
			$Affiliate_Hook = new Affiliate_Hook; 

			if(!isset($session_id))
			{
				$session_id = uniqid().rand();
				$time = (60 * 60);
				setcookie("tools_view_ck", $session_id, time()+$time, "/" ); 
			}
				
			$ip = $Affiliate_Hook->get_client_ip();
			$viewData = array(
	            'user_id'  => (int)$user_id,
	            'tools_id'  => (int)$tools_id,
	            'link' => '', 
	            'ip'      =>  $ip,
	            'session_id'      =>  $session_id, 
	            'created_at'      =>  date('Y-m-d H:m:s'),

	        );

			$viewcheck = spam_protect_and_log($viewData, 'tool');
			if($viewcheck!=2)
			{
				$this->db->query("UPDATE integration_tools SET trigger_count = trigger_count + 1  WHERE id = ".$tools_id); 
			}
		}	
	}

	/**
	 * Insert a click record into integration_clicks_action keyed by click_token,
	 * so server-side s2sConvert can resolve it on conversion postback.
	 */
	private function _register_s2s_pending_click($click_token, $user_id, $ads_id, $tools_id, $target_link)
	{
		if (empty($click_token) || empty($user_id) || empty($tools_id)) {
			return;
		}

		$this->load->model('Product_model');

		$domain_name = parse_url($target_link, PHP_URL_HOST);
		if (empty($domain_name)) {
			$domain_name = (string) $target_link;
		}

		$ip      = $this->input->ip_address();
		$ip_info = $this->Product_model->ip_info($ip);

		$row = array(
			'click_token'  => $click_token,
			'user_id'      => (int) $user_id,
			'ads_id'       => (int) $ads_id,
			'tools_id'     => (int) $tools_id,
			'product_id'   => 0,
			'script_name'  => 's2s',
			'page_name'    => 's2s_redirect',
			'action_code'  => '',
			'commission'   => 0,
			'is_action'    => 0,
			'created_at'   => date('Y-m-d H:i:s'),
			'base_url'     => $domain_name,
			'ip'           => $ip,
			'country_code' => isset($ip_info['country_code']) ? $ip_info['country_code'] : '',
			'custom_data'  => json_encode(array()),
		);

		$this->db->insert('integration_clicks_action', $row);
	}
}