<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Maps unique_id to category for template grouping on index page.
 * Category keys must exist in admin language (template_category_*).
 */
$config['categories'] = array(
    'user_management' => array(
        'forget_password', 'send_register_mail_api', 'new_user_request', 'new_user_approved', 'new_user_declined',
        'user_level_changed'
    ),
    'subscription' => array(
        'subscription_buy', 'subscription_expire_notification', 'subscription_status_change'
    ),
    'withdrawal' => array('withdrwal_status_change'),
    'wallet' => array('wallet_noti_on_hold_wallet'),
    'vendor_deposit' => array('new_vendor_deposit_request', 'vendor_deposit_request_updated'),
    'vendor_products' => array(
        'vendor_create_product', 'vendor_product_status_0', 'vendor_product_status_1',
        'vendor_product_status_2', 'vendor_product_status_3', 'vendor_order_status_complete',
        'new_order_for_vendor'
    ),
    'vendor_forms' => array(
        'vendor_create_form', 'vendor_form_status_0', 'vendor_form_status_1',
        'vendor_form_status_2', 'vendor_form_status_3'
    ),
    'vendor_ads' => array(
        'vendor_create_ads', 'vendor_ads_status_0', 'vendor_ads_status_1',
        'vendor_ads_status_2', 'vendor_ads_status_3'
    ),
    'vendor_programs' => array(
        'vendor_create_program', 'vendor_program_status_0', 'vendor_program_status_1',
        'vendor_program_status_2', 'vendor_program_status_3', 'order_on_vendor_program'
    ),
    'support' => array('ticket_created_email', 'ticket_reply_email', 'ticket_status_email'),
);
