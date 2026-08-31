<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Default content for mail templates (used by Reset to Default in editor).
 * Keyed by unique_id. Only templates listed here support reset.
 */
$config['defaults'] = array();

$config['defaults']['forget_password'] = array(
    'subject'        => 'Password Reset Request',
    'admin_subject'  => 'Admin: Password Reset Request',
    'client_subject' => 'Password Reset Request',
    'text' =>
'<p>Dear [[firstname]],</p>
<p>We received a request to reset the password for your <strong>[[website_name]]</strong> account. Click the button below to choose a new password.</p>
[[reset_link]]
<p>If you did not request a password reset, you can safely ignore this email.</p>
<p>[[website_name]]<br />Support Team</p>',
    'admin_text' =>
'<p>Dear [[firstname]],</p>
<p>We received a request to reset the password for your <strong>[[website_name]]</strong> admin account. Click the button below to choose a new password.</p>
[[reset_link]]
<p>If you did not request a password reset, you can safely ignore this email.</p>
<p>[[website_name]]<br />Support Team</p>',
    'client_text' =>
'<p>Dear [[firstname]],</p>
<p>We received a request to reset the password for your <strong>[[website_name]]</strong> account. Click the button below to choose a new password.</p>
[[reset_link]]
<p>If you did not request a password reset, you can safely ignore this email.</p>
<p>[[website_name]]<br />Support Team</p>',
);

$config['defaults']['subscription_expire_notification'] = array(
    'subject' => 'Your Subscription Will Be Expired Soon.',
    'text' => '<p>Dear [[firstname]],</p>
<p>Your subscription for plan <strong>[[planname]]</strong> will expire soon.</p>
<p>Expiry Date: [[expire_at]]</p>
<p>Please renew to continue enjoying our services.</p>
<p><br />[[website_name]]<br />Support Team</p>',
);

$config['defaults']['subscription_buy'] = array(
    'subject' => 'Subscription Buy',
    'admin_subject' => 'New Subscription Buy From [[firstname]] [[lastname]]',
    'text' => '<h2>Thanks for your order</h2>
<p>Dear [[firstname]],</p>
<p>Welcome to [[website_name]]. Your subscription for <strong>[[planname]]</strong> has been activated.</p>
<p>Price: [[price]] | Valid until: [[expire_at]]</p>
<p>If you have any questions, simply reply to this email.</p>
<p><br />[[website_name]]<br />Support Team</p>',
    'admin_text' => '<h2>New Subscription Purchase</h2>
<p>[[firstname]] [[lastname]] purchased subscription: [[planname]]</p>
<p>Price: [[price]] | User: [[email]]</p>
<p><br />[[website_name]]<br />Support Team</p>',
);
