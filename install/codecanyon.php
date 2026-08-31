<?php

if(isset($_POST['code'])) {
    $code = $_POST['code'];
    $api_url = 'https://affiliatepro.org/index.php?rest_route=/license-easy/v1/validate';
    
    $website_url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    if (isset($_SERVER['REQUEST_SCHEME']) && isset($_SERVER['HTTP_HOST'])) {
        $website_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    }
    
    $data = array(
        'purchase_code' => $code,
        'product_slug' => 'affiliateporsaas',
        'domain' => $website_url
    );
    
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_TIMEOUT => 20
    ));
    
    $response = @curl_exec($ch);
    if (curl_errno($ch) > 0) { 
        $json['errors']['purchase_code'] = "Error connecting to API: " . curl_error($ch);
    }
    
    curl_close($ch);
    
    if (!isset($json['errors'])) {
        $result = @json_decode($response, true);
        if (isset($result['status']) && $result['status'] === 'success') {
            $json['response'] = array(
                'valid' => true,
                'buyer' => isset($result['buyer']) ? $result['buyer'] : '',
                'license' => isset($result['license_type']) ? $result['license_type'] : 'Regular License',
                'sold_at' => isset($result['purchase_date']) ? $result['purchase_date'] : '',
                'purchase_count' => isset($result['purchase_count']) ? $result['purchase_count'] : 1
            );
        } else {
            $error_msg = isset($result['message']) ? $result['message'] : 'Invalid purchase code';
            $json['errors']['purchase_code'] = $error_msg;
        }
    }
} else {
    $json['errors'] = "Access Denied!";
}

echo json_encode($json);

