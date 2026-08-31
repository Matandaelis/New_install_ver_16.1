<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Storeapp {

    private $data;

    private $CI;

    public function __construct(){

        $this->CI =& get_instance();

    }

    public function view($page, $data = array(), $skip_layout = false, $return = false){


        $this->CI->load->model("Product_model");

        $this->CI->load->model("User_model");

        $data['page'] = $page;

        $data['LanguageHtml'] = $this->CI->Product_model->getLanguageHtml('store', 'store-default');

        $data['CurrencyHtml'] = $this->CI->Product_model->getCurrencyHtml('store', 'store-default');

        $data['languages'] = $this->CI->Product_model->getLanguageHtml('store', 'store-default', true);

        // Same loading rules as Product_model::getSettings (includes inactive rows when nothing is published)
        $settingdata = $this->CI->Product_model->getSettings('store');
        $data['SiteSetting'] = $this->CI->Product_model->getSettings('site');

        $data['add_tocart_url'] = $this->CI->cart->getStoreUrl('add_to_cart');

        // Cart item count — shown in navbar badge on all themed pages
        $data['cart_count'] = count($this->CI->cart->getProducts());

        $data['store_setting'] = $settingdata;

        $data['is_logged'] = $this->CI->cart->is_logged();

        $data['home_link'] = base_url("store/");

        $data['base_url'] = base_url("store/");

    	$data['referid'] = (int)$this->CI->cart->getReferId();

        $data['client'] = $this->CI->User_model->get_user_by_id($this->CI->cart->getUserId());

        // Currency sign (session-aware) — computed once for all theme layouts
        $_sess_curr = isset($_SESSION['userCurrency']) ? $_SESSION['userCurrency'] : '';
        $_curr_row  = !empty($_sess_curr)
            ? $this->CI->db->query("SELECT symbol_left FROM currency WHERE code=?", [$_sess_curr])->row_array()
            : null;
        if (empty($_curr_row)) {
            $_curr_row = $this->CI->db->query("SELECT symbol_left FROM currency WHERE is_default=1")->row_array();
        }
        $data['store_currency'] = !empty($_curr_row['symbol_left']) ? $_curr_row['symbol_left'] : ($settingdata['currency_sign'] ?? '$');

        // Google reCAPTCHA settings — used by login, contact, checkout views
        $data['googlerecaptcha'] = $this->CI->Product_model->getSettings('googlerecaptcha');

        // Category tree fallback — ensures $category_tree is always set in layouts
        if (!isset($data['category_tree']) || empty($data['category_tree'])) {
            $data['category_tree'] = $this->CI->Product_model->getCategoryTree();
        }

        // Consume affiliate localStorage flash-session var (session write stays here, not in view)
        if (isset($_SESSION['setLocalStorageAffiliateAjax'])) {
            $affiliateInitJson = $_SESSION['setLocalStorageAffiliateAjax'];
            $affiliateDecoded  = json_decode($affiliateInitJson);
            if ($affiliateDecoded) {
                $_SESSION['localStorageAffiliate'] = (int)$affiliateDecoded[0];
            }
            $data['affiliate_localstorage_init'] = $affiliateInitJson;
            unset($_SESSION['setLocalStorageAffiliateAjax']);
        } else {
            $data['affiliate_localstorage_init'] = null;
        }

        $theme = isset($settingdata['theme']) ? $settingdata['theme'] : '0';
        $theme = ($theme !== '' && $theme !== null) ? $theme : '0';
        $path = "store/{$theme}/{$page}.php";

        if($theme != '0' && !empty($theme)){
            $path = "store/{$theme}/{$page}.php";
            if(!file_exists(APPPATH."views/".$path)){
                $path = "store/default/{$page}.php";

            }

        } else {

            $path = "store/default/{$page}.php";

        }
       
        
        $this->data['content'] = $this->CI->load->view($path,  $data, true);

        if($skip_layout === 'classified-checkout') {
            $this->CI->load->view("store/classified/checkout_layout.php", $this->data);
        } else if($skip_layout === 'lms') {
            $this->CI->load->view("store/default/lms/template-1", $this->data);
        } else {
            if($skip_layout){
                if($return)  return $this->data['content'];
                echo $this->data['content'];
                die;

            } else {
                $path = '';
                if($theme != '0' && !empty($theme)){
                    $path = "store/{$theme}/layout.php";
                    if(!file_exists(APPPATH."views/".$path)){
                        $path = "store/default/layout.php";
                    }
                } else {
                    $path = "store/default/layout.php";
                } 
                
                $this->CI->load->view($path, $this->data);   
            }
        }
    }
} 