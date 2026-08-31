<?php

function verify_request()
{
    $ci = & get_instance();
    $ci->load->database();

    // Get all the headers
    $headers = $ci->input->request_headers();
    
    // Check for Authorization header in different cases
    if (!isset($headers['Authorization'])) {
        // Sometimes the header is available under a different key
        if (isset($headers['authorization'])) {
            $headers['Authorization'] = $headers['authorization'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers['Authorization'] = $_SERVER['HTTP_AUTHORIZATION'];
        } else {
            $status = 401;
            $response = array('status' => $status, 'errors' => 'Unauthorized Access!');
            return $response;
            exit();
        }
    }

    // Extract the token
    $token = $headers['Authorization'];

    // Validate the token
    // Successful validation will return the decoded user data else returns false
    $data = AUTHORIZATION::validateToken($token);
    if ($data === false) {
        $status = 401;
        $response = array('status' => $status, 'errors' => 'Unauthorized Access!');
        return $response;
        exit();
    } else {
        $ci->load->model('Common_model');
        $where = array('token' => $token);
        $query_count = $ci->Common_model->get_total_rows('users', $where);  

        if ($query_count != 1) {
            $status = 401;
            $response = array('status' => $status, 'errors' => 'Unauthorized Access!');
            return $response;
            exit();
        } else {
            $ci->load->model('Common_model');
            $where = array('token' => $token);
            $query = $ci->Common_model->get_data_row('users', $where, '*', 'id');

            $status = 200;
            $response = array('status' => $status, 'errors' => 'Authorized Access!', 'userdata' => $query);
            return $response;
            exit();
        }
    }
}

function send_push_android($device_id,$title,$message)
{
        $url = FCM_URL;
        $api_key = FCM_API_KEY;

        $fields = array (
            'registration_ids' => array (
                    $device_id
            ),
            'data' => array (
                    "title" => $title,
                    "message" => $message,
            )
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
}

function send_push_ios($device_id,$title,$message)
{
    $url = FCM_URL;
    $api_key = FCM_API_KEY;

    $msg = array ( 'title' => $title, 'body' => $message);

    $message = array(
        "message" => $title,
        "data" => $message,
    );

    $data = array('registration_ids' => array($device_id));
    $data['data'] = $message;
    $data['notification'] = $msg;
    $data['notification']['sound'] = "default";

    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
}

function parseBannerTypeAsCampaign($string) {
    $returnString = "";

    switch ($string) {
        case 'banner':
            $returnString = __('admin.banner_campaign');
            break;
        case 'link ads':
            $returnString = __('admin.link_campaign');
            break;
        case 'text ads':
            $returnString = __('admin.text_campaign');
            break;
        case 'video ads':
            $returnString = __('admin.video_campaign');
            break;
        default:
            $returnString = $string;
            break;
    }

    return ucwords($returnString);
}

function printR($data,$signer = false){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if($signer)
        echo $signer;
    echo "</br>";
}

function debug($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}

function get_available_checkout_template() {
    $ci = &get_instance();
    $ci->load->helper('directory');;
    $files = directory_map('./application/views/store/classified/classified-checkout');
    
    $templates = [];
    foreach($files as $key => $value) {
        if(is_string($value)) {
            $value = str_replace('.php', '', $value);
            $templates[$value] = $value;
        }
    }
    return $templates;
}

function generatePaginationLinks($baseUrl, $totalResults, $resultsPerPage, $currentPage, $queryStringArray=[]) {

    $totalPages = ceil($totalResults/$resultsPerPage);
    
    if($totalPages <=1 ) {
        return [];
    }
 
    $queryString = '';
    
    if($queryStringArray) {
        $queryString = '?'.http_build_query($queryStringArray);
    }

    $rightLinks = $currentPage+3;

    $previousLinks = $currentPage-3;

    if($rightLinks > $totalPages) {
        $previousLinks -= ($rightLinks - $totalPages);
    } else if($previousLinks < 1) {
        $rightLinks -= ($previousLinks - 1);
    }

    $pagination_links = [];
    
    $pagination_links['first'] = false;
    $pagination_links['previous'] = false;
    $pagination_links['left_links'] = [];
    $pagination_links['current'] = false;
    $pagination_links['right_links'] = [];
    $pagination_links['next'] = false;
    $pagination_links['last'] = false;

    
    if($currentPage > 1) {
        $pagination_links['first'] = $baseUrl.'/1'.$queryString;
        $pagination_links['previous'] = $baseUrl.'/'.($currentPage-1).$queryString;
    }

    for($i = 1; $i <= $totalPages; $i++){
        if($i < $currentPage && $i >= $previousLinks) {
            $pagination_links['left_links'][] = ['index'=>$i, 'link' =>$baseUrl.'/'.$i.$queryString];
        }

        if($i == $currentPage) {
            $pagination_links['current'] = ['index'=>$i, 'link' => 'javascript:voide(0);'];
        }

        if($i > $currentPage && $i <= $rightLinks) {
            $pagination_links['right_links'][] = ['index'=>$i, 'link' =>$baseUrl.'/'.$i.$queryString];
        }          
    }
    
    if($currentPage < $totalPages) { 
        $pagination_links['next'] = $baseUrl.'/'.($currentPage+1).$queryString;
        $pagination_links['last'] = $baseUrl.'/'.$totalPages.$queryString;
    }

    return $pagination_links;
}

/**
 * Parse product_tags from database into an array of tag strings.
 * Handles: JSON array (from admin save), comma-separated string, or empty.
 *
 * @param string|null $product_tags Raw value from product.product_tags
 * @return array List of trimmed, non-empty tag strings
 */
if (!function_exists('parse_product_tags')) {
    function parse_product_tags($product_tags) {
        if (empty($product_tags) || !is_string($product_tags)) {
            return [];
        }
        $raw = trim($product_tags);
        if ($raw === '' || $raw === '[]') {
            return [];
        }
        if ($raw[0] === '[') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $out = [];
                foreach ($decoded as $t) {
                    $t = trim(is_string($t) ? $t : (string)$t);
                    if ($t !== '') {
                        $out[] = $t;
                    }
                }
                return $out;
            }
        }
        $parts = array_map('trim', explode(',', $raw));
        return array_values(array_filter($parts, function ($p) { return $p !== ''; }));
    }
}