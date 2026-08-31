<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use App\User;
$getUrl = User::getLoginUrl();
$getFrontUrl = User::getFrontUrl();
$currentTheme = User::getActiveTheme();

if ($getUrl == '') {
	$route['admin'] = "AuthController/admin_login";
}else{
	$route[$getUrl] = "AuthController/admin_login";
}
if ($getFrontUrl == '') {
	$route['/'] = 'AuthController/user_index';
}else{
	$route[$getFrontUrl] = 'AuthController/user_index';
}

$route['404_override'] = 'admincontrol/page_404';
$route['default_controller'] = "AuthController/user_index";
$route['affiliate'] = "AuthController/user_index";

// Dynamic robots.txt route
$route['robots.txt'] = 'robots/index';


// OTP links
$route['auth/admin/otp_verify'] = 'AuthController/otp_verify';
$route['auth/admin/otp_validate'] = 'AuthController/otp_validate';
$route['auth/admin/resend_otp'] = 'AuthController/resend_otp';


// API
$route['api/admin_login'] = 'AuthController/api_admin_login';
$route['api/dashboard_data'] = 'admincontrol/api_dashboard_data';

// System Check
$route['systemcheck'] = 'Systemcheck/index';
$route['systemcheck/change_language/(:num)'] = 'Systemcheck/change_language/$1';


// common routes of sale and cart modes
$route['store/confirm_payment'] = "store/confirm_payment";
$route['store/payment_confirmation'] = "store/payment_confirmation";
$route['store/confirm_order'] = "store/confirm_order";
$route['store/ajax_register'] = "store/ajax_register";
$route['store/ajax_login'] = "store/ajax_login";
$route['store/guestCheckout'] = "store/guestCheckout";
$route['store/payment_gateway/(:any)/(:any)'] = "store/paymentGateway/$1/$2";
$route['store/payment_gateway/(:any)/(:any)/(:any)'] = "store/paymentGateway/$1/$2/$3";
$route['store/payment_gateway/(:any)/(:any)/(:any)/(:any)'] = "store/paymentGateway/$1/$2/$3/$4";
$route['store/get_payment_mothods'] = "store/get_payment_mothods";

// V14 AJAX endpoints — shared across all themes
$route['store/quick_view/(:num)'] = "store/quick_view/$1";
$route['store/instant_search'] = "store/instant_search";
$route['store/recommendations/(:num)'] = "store/recommendations/$1";
$route['store/track_cart'] = "store/track_cart";

if($currentTheme=='cart') {
/*Store - cart mode*/
$route['store'] = "store/index";
$route['store/mini_cart'] = "store/mini_cart";
$route['store/checkout_shipping'] = "store/checkout_shipping";
$route['store/checkout_shipping/(:num)'] = "store/checkout_shipping/$1";
$route['store/checkout_confirm'] = "store/checkout_confirm";
$route['store/getState'] = "store/getState";
$route['store/checkout-cart'] = "store/checkoutCart";
$route['store/about'] = "store/about";
$route['store/profile'] = "store/profile";
$route['store/my_courses'] = "store/my_courses";
$route['store/order'] = "store/order";
$route['store/wishlist'] = "store/wishlist";
$route['store/login'] = "store/login";
$route['store/forgot'] = "store/forgot";
$route['store/logout'] = "store/logout";
$route['store/vieworder/(:any)'] = "store/vieworder/$1";
$route['store/vieworderdetails/(:any)'] = "store/vieworderdetails/$1";
$route['store/track_order'] = "store/track_order";
$route['store/contact'] = "store/contact";
$route['store/vendor_contact'] = "store/vendor_contact";
$route['store/policy'] = "store/policy";
$route['store/cart'] = "store/cart";
$route['store/product_ratting'] = "store/product_ratting";
$route['store/make_complete'] = "store/make_complete";
$route['store/continue_last_watch'] = "store/continue_last_watch";
$route['store/toggle_wishlist'] = "store/toggle_wishlist";
$route['store/page/(:any)'] = "store/page/$1";
$route['store/checkout'] = "store/checkout";
$route['store/add_coupon'] = "store/add_coupon";
$route['store/add_to_cart'] = "store/add_to_cart";
$route['store/thankyou/(:any)'] = "store/thankyou/$1";
$route['store/(:any)/product/(:any)'] = "store/product/$1/$2";
$route['store/product/(:any)'] = "store/product/$1";
$route['store/category'] = "store/category";
$route['store/category/(:any)'] = "store/category/$1";
$route['store/change_language/(:any)'] = "store/change_language/$1";
$route['store/change_currency/(:any)'] = "store/change_currency/$1";

$route['store/play'] = "store/play";
$route['store/shipping'] = "store/shipping";
$route['store/calculate_usps_rates'] = "store/calculate_usps_rates";
$route['store/update_shipping_cost'] = "store/update_shipping_cost";

/* Cart-mode theme API (versioned JSON + manifest) — must precede store/(:any) */
$route['store/api/v1/theme/manifest'] = 'store_api/theme_manifest';
$route['store/api/v1/theme/examples/(:any)'] = 'store_api/theme_example/$1';
$route['store/api/v1/pages/home'] = 'store_api/page_home';
$route['store/api/v1/pages/category/(:any)'] = 'store_api/page_category/$1';
$route['store/api/v1/pages/product/(:any)'] = 'store_api/page_product/$1';
$route['store/api/v1/fragments/product-grid'] = 'store_api/fragment_product_grid';
$route['store/api/v1/fragments/instant_search'] = 'store_api/fragment_instant_search';
$route['store/api/v1/fragments/quick_view/(:num)'] = 'store_api/fragment_quick_view/$1';
$route['store/api/v1/fragments/recommendations/(:num)'] = 'store_api/fragment_recommendations/$1';
$route['store/api/v1/pages/cart'] = 'store_api/page_cart';
$route['store/api/v1/pages/checkout'] = 'store_api/page_checkout';
$route['store/api/v1/pages/checkout_shipping'] = 'store_api/page_checkout_shipping';
$route['store/api/v1/pages/checkout_confirm'] = 'store_api/page_checkout_confirm';
$route['store/api/v1/pages/profile'] = 'store_api/page_profile';
$route['store/api/v1/pages/orders'] = 'store_api/page_orders';
$route['store/api/v1/pages/wishlist'] = 'store_api/page_wishlist';
$route['store/api/v1/pages/track_order'] = 'store_api/page_track_order';
$route['store/api/v1/pages/my_courses'] = 'store_api/page_my_courses';
$route['store/api/v1/pages/lms_play'] = 'store_api/page_lms_play';

$route['store/(:any)'] = "store/index/$1";
$route['store/(:any)/(:any)'] = "store/index/$2/$1";
/*Store - cart mode*/
} else {



/*Store - sales mode*/
$route['product-campaign/(:any)/(:any)'] = "Productsales/placeOrder/$1/$2";
$route['store'] 						 = "classified/home";
$route['store/login']					 = "classified/login";
$route['store/logout']					 = "classified/logout";
$route['store/register']				 = "classified/register";
$route['store/forgot'] 				 	 = "classified/forgot";
$route['store/about'] 				     = "classified/about";
$route['store/catalog'] 				 = "classified/catalog";
$route['store/contact'] 				 = "classified/contact";
$route['store/policy'] 				     = "classified/policy";
// Auth Route
$route['store/profile']			         = "classified/profile";
$route['store/orders'] 					 = "classified/orders";
$route['store/show_classified_buy_button/(:any)/(:any)'] = "classified/show_classified_buy_button/$1/$2";
$route['store/product/(:any)'] 			= "classified/product/$1";
$route['store/checkout'] 				= "classified/checkout";
$route['store/checkout_preview/(:any)'] 		= "classified/checkout_preview/$1";
$route['store/thankyou/(:any)'] 		= "classified/thankyou/$1";
$route['store/toggle_wishlist'] 		= "classified/toggle_wishlist";
$route['store/order-details/(:any)']	 = "classified/order_details/$1";
$route['store/catalog/category/(:any)']	 = "classified/catalog/category/$1";
$route['store/catalog/location/(:any)']	 = "classified/catalog/location/$1";
$route['store/wishlist']	 		    = "classified/wishlist";
$route['store/cart'] 					= "classified/cart";
$route['store/change_language/(:any)'] = "classified/change_language/$1"; 
/*Store - sales mode*/

// disabled cart store routes
$route['store/category'] = "classified/redirect_home";
$route['store/category/(:any)'] = "classified/redirect_home";
$route['store/checkout-cart'] = "classified/redirect_home";
$route['store/order'] = "classified/redirect_home";
$route['store/vieworder/(:any)'] = "classified/redirect_home";
$route['store/vieworderdetails/(:any)'] = "classified/redirect_home";
$route['store/vendor_contact'] = "classified/redirect_home";
$route['store/product_ratting'] = "classified/redirect_home";
$route['store/product_ratting'] = "classified/redirect_home";
$route['store/make_complete'] = "classified/redirect_home";
$route['store/continue_last_watch'] = "classified/redirect_home";
$route['store/toggle_wishlist'] = "classified/redirect_home";
$route['store/page/(:any)'] = "classified/redirect_home";

$route['store/(:any)'] = "classified/index/$1";
$route['store/(:any)/(:any)'] = "classified/index/$2/$1";
}

$route['store/print/(:any)'] = "store/thankyou/$1";

$route['product/views/(:any)/(:any)'] = "product/views/$1/$2";
$route['product/clicks/(:any)/(:any)'] = "product/clicks/$1/$2";
$route['product/thankyou/(:any)'] = "product/thankyou/$1";
$route['product/payment/(:any)/(:any)'] = "product/payment/$1/$2";
$route['product/(:any)/(:any)'] = "product/index/$1/$2";


$route['usercontrol/contact-us'] = "usercontrol/contact_us";
$route['usercontrol/wallet/withdraw'] = "usercontrol/wallet_withdraw";

$route['admincontrol/wallet/withdraw'] = "admincontrol/wallet_withdraw";
$route['admincontrol/wallet/withdraw/(:any)'] = "admincontrol/wallet_withdraw_detail/$1";

$route['form/thankyou/(:any)'] = "form/thankyou/$1";
$route['form/checkout_cart'] = "form/checkoutCart";
$route['form/checkout_cart/(:any)'] = "form/checkoutCart/$1";
$route['form/checkout_shipping'] = "form/checkoutShipping";
$route['form/checkout_shipping/(:any)'] = "form/checkoutShipping/$1";
$route['form/confirm_order'] = "form/confirm_order";
$route['form/ajax_login'] = "form/ajax_login";
$route['form/ajax_register'] = "form/ajax_register";
$route['form/cart'] = "form/cart";
$route['form/add_coupon'] = "form/add_coupon";
$route['form/(:any)/(:any)'] = "form/index/$1/$2";

$route['membership/payment_gateway/(:any)/(:any)'] = "membership/paymentGateway/$1/$2";
$route['membership/payment_gateway/(:any)/(:any)/(:any)'] = "membership/paymentGateway/$1/$2/$3";
$route['membership/payment_gateway/(:any)/(:any)/(:any)/(:any)'] = "membership/paymentGateway/$1/$2/$3/$4";
//$route['membership/buy_membership/(:any)/(:any)'] = "membership/paymentGateway/$1/$2";

$route['resetpassword'] = "usercontrol/resetpassword";
$route['resetpassword/(:any)'] = "usercontrol/resetpassword/$1";
$route['auth/(:any)'] = "usercontrol/auth/$1";

$route['cronjob/expire_package_notification'] = "CronJob/expire_package_notification";

$route['get_state'] = "usercontrol/getState";

$route['backend/(:any)'] = "Pagebuilder/custom/$1";

$route['page/(:any)'] = "AuthController/page/$1";
$route['p/(:any)'] = "AuthController/user_index/$1";

$route['faq'] = "AuthController/user_index/faq";
$route['contact'] = "AuthController/user_index/contact";
$route['terms-of-use'] = "AuthController/user_index/terms-of-use";
$route['forget-password'] = "AuthController/user_index/forget-password";
$route['forgot-password'] = "AuthController/user_index/forgot-password";
$route['privacy-policy'] = "AuthController/privacy_policy";
$route['term-condition'] = "common/term_condition";

$route['login_marketing_activity'] = 'AuthController/login_marketing_activity';
$route['login'] = "AuthController/user_index/login";
$route['register'] = "AuthController/user_index/register";
$route['register/vendor/(:any)'] = "AuthController/vendor_register/$1";
$route['register/vendor'] = "AuthController/vendor_register";
$route['register/(:any)'] = "AuthController/user_register/$1";

$route['unsubscribe/(:any)'] = "AuthController/unsubscribe/$1";
$route['resubscribe/(:any)'] = "AuthController/resubscribe/$1";
$route['AuthController/change_language/(:any)'] = 'AuthController/change_language/$1';
$route['admincontrol/change_language/(:any)'] = 'admincontrol/change_language/$1';

$route['bigcommerce.js'] = "integration/bigcommerce";
$route['integration'] = "integration/index";
$route['integration/s2sRegisterClick'] = "integration/s2sRegisterClick";
$route['incomereport'] = "incomereport/index";
$route['filemanager'] = "filemanager/index";


//redirect old theme design to the new theme design
$route['admincontrol/theme_setting'] = "admincontrol/paymentsetting";
//redirect old theme design to the new theme design

$route['update'] = "Manualcontrol/index";
$route['api-home'] = "common/api_home";
$route['api-document'] = "common/api_document"; 
$route['admin-api-document'] = "common/admin_api_document";
$route['debug'] = "Manualcontrol/debug";
$route['debug/(:any)'] = "Manualcontrol/debug/$1";
$route['debug/(:any)/(:any)'] = "Manualcontrol/debug/$1/$2";

$route['usercontrol/payment_gateway/(:any)/(:any)'] = "usercontrol/paymentGateway/$1/$2";
$route['usercontrol/payment_gateway/(:any)/(:any)/(:any)'] = "usercontrol/paymentGateway/$1/$2/$3";
$route['usercontrol/payment_gateway/(:any)/(:any)/(:any)/(:any)'] = "usercontrol/paymentGateway/$1/$2/$3/$4";
// Admin Routes 

$route['sitemap\.xml'] = "sitemap/index";
$route['robots\.txt'] = "robots/index";
$route['robots'] = "robots/index";

global $DB_ROUTES;
if(!empty($DB_ROUTES)) $route = array_merge($route,$DB_ROUTES);

$route['ref/(:any)'] = "RedirectTracking/external_integration/$1";
$route['(:any)'] = "RedirectTracking/redirect_tracking_url/$1";