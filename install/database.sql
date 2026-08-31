#
# TABLE STRUCTURE FOR: abandoned_carts
#

CREATE TABLE `abandoned_carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cart_data` text DEFAULT NULL,
  `recovery_token` varchar(64) DEFAULT NULL,
  `status` enum('abandoned','recovered','expired') NOT NULL DEFAULT 'abandoned',
  `reminder_count` int(11) NOT NULL DEFAULT 0,
  `reminder_sent_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `recovered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ac_status` (`status`),
  KEY `idx_ac_token` (`recovery_token`),
  KEY `idx_ac_email` (`email`),
  KEY `idx_ac_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: admin_roles
#

CREATE TABLE `admin_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Array of permission slugs, e.g. ["dashboard","reports","users"]' CHECK (json_valid(`permissions`)),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: affiliate_action
#

CREATE TABLE `affiliate_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `country_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `commission` double(22,0) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: affiliate_session_log
#

CREATE TABLE `affiliate_session_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(25) NOT NULL,
  `user_os` varchar(25) NOT NULL,
  `user_agent` text NOT NULL,
  `time` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: affiliateads
#

CREATE TABLE `affiliateads` (
  `affiliateads_id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliateads_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `affiliateads_metadata` longtext CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `affiliateads_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `affiliateads_ipaddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `affiliateads_created_by` int(11) DEFAULT NULL,
  `affiliateads_updated_by` int(11) DEFAULT NULL,
  `affiliateads_created` datetime NOT NULL,
  `affiliateads_updated` datetime NOT NULL,
  PRIMARY KEY (`affiliateads_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: award_level
#

CREATE TABLE `award_level` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `level_number` varchar(100) NOT NULL,
  `jump_level` smallint(5) unsigned DEFAULT NULL,
  `minimum_earning` double unsigned NOT NULL,
  `sale_comission_rate` double unsigned NOT NULL,
  `bonus` double unsigned NOT NULL,
  `default_registration_level` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: cart
#

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_variation` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `refer_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total` double NOT NULL,
  `coupon_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `coupon_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `coupon_discount` double DEFAULT 0,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: categories
#

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `background_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '#000000',
  `parent_id` int(11) NOT NULL,
  `tag` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: ci_session
#

CREATE TABLE `ci_session` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `ci_session_timestamp` (`timestamp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: cities
#

CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: clicks_views
#

CREATE TABLE `clicks_views` (
  `clicks_views_id` int(11) NOT NULL AUTO_INCREMENT,
  `clicks_views_refuser_id` int(11) DEFAULT NULL,
  `clicks_views_action_id` int(11) DEFAULT NULL,
  `clicks_views_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_status` int(11) DEFAULT NULL,
  `clicks_views_click` int(11) DEFAULT NULL,
  `clicks_views_view` int(11) NOT NULL,
  `clicks_views_sale` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_referrer` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_user_agent` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_os` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_browser` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_isp` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_ipaddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_created_by` int(11) DEFAULT NULL,
  `clicks_views_updated_by` int(11) DEFAULT NULL,
  `clicks_views_created` datetime NOT NULL,
  `clicks_views_updated` datetime NOT NULL,
  `clicks_views_click_commission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_sale_commission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_data_commission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `clicks_views_view_commission` int(11) NOT NULL,
  PRIMARY KEY (`clicks_views_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: countries
#

CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortname` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phonecode` int(11) NOT NULL,
  `lat` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lng` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: coupon
#

CREATE TABLE `coupon` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `uses_total` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `products` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `vendor_id` int(11) DEFAULT 0,
  `allow_for` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: currency
#

CREATE TABLE `currency` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `symbol_left` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `symbol_right` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `decimal_place` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` float DEFAULT 0,
  `status` tinyint(1) NOT NULL,
  `is_default` int(11) NOT NULL,
  `date_modified` datetime NOT NULL,
  `replace_comma_symbol` varchar(1) NOT NULL DEFAULT ',',
  `decimal_symbol` varchar(1) NOT NULL DEFAULT ',',
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: deposit_requests_history
#

CREATE TABLE `deposit_requests_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vd_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `comment` varchar(355) NOT NULL,
  `transaction_id` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: email_logs
#

CREATE TABLE `email_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `template_unique_id` varchar(100) DEFAULT NULL,
  `status` enum('sent','failed','queued') NOT NULL DEFAULT 'sent',
  `error_message` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_recipient` (`recipient`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# TABLE STRUCTURE FOR: form
#

CREATE TABLE `form` (
  `form_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `seo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fevi_icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sale_commision_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `sale_commision_value` float DEFAULT 0,
  `click_commision_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `click_commision_ppc` float DEFAULT 0,
  `click_commision_per` float DEFAULT 0,
  `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `form_recursion_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `form_recursion` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `recursion_custom_time` bigint(20) NOT NULL,
  `recursion_endtime` varchar(255) DEFAULT NULL,
  `product` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `coupon` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `allow_for` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `min_health_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `min_award_level_id` smallint(5) unsigned DEFAULT NULL,
  `footer_title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `google_analitics` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `view_statistics` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: form_action
#

CREATE TABLE `form_action` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_type` text DEFAULT NULL,
  `form_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` text DEFAULT NULL,
  `viewer_id` int(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `pay_commition` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `country_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: form_coupon
#

CREATE TABLE `form_coupon` (
  `form_coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `uses_total` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`form_coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: google_ads
#

CREATE TABLE `google_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_key` varchar(250) DEFAULT NULL,
  `unit_key` varchar(250) DEFAULT NULL,
  `ad_section` int(11) DEFAULT NULL COMMENT '1 for side bar top 2 for side bar bottom 3 for footer 4 for right side',
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# TABLE STRUCTURE FOR: integration_admin_clicks_action
#

CREATE TABLE `integration_admin_clicks_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `click_token` varchar(64) DEFAULT NULL,
  `base_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_id` double(22,0) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commission` float DEFAULT 0,
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ads_id` int(11) NOT NULL,
  `tools_id` int(11) NOT NULL,
  `country_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_action` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `page_open_time` datetime DEFAULT NULL,
  `page_close_time` datetime DEFAULT NULL,
  `time_spent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_click_token` (`click_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: integration_category
#

CREATE TABLE `integration_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: integration_clicks_action
#

CREATE TABLE `integration_clicks_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `click_token` varchar(64) DEFAULT NULL,
  `base_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_id` double(22,0) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commission` float DEFAULT 0,
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ads_id` int(11) NOT NULL,
  `tools_id` int(11) NOT NULL,
  `country_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_action` int(11) NOT NULL,
  `custom_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `page_open_time` datetime DEFAULT NULL,
  `page_close_time` datetime DEFAULT NULL,
  `time_spent` int(11) DEFAULT NULL,
  `fraud_score` tinyint(3) unsigned DEFAULT NULL COMMENT 'v15: 0-100 fraud score at time of click. NULL = pre-v15 record.',
  PRIMARY KEY (`id`),
  KEY `idx_click_token` (`click_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: integration_clicks_logs
#

CREATE TABLE `integration_clicks_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `base_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(555) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `browserName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browserVersion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `systemString` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `osPlatform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `osVersion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `osShortVersion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isMobile` int(11) NOT NULL,
  `mobileName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `osArch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isIntel` int(11) NOT NULL,
  `isAMD` int(11) NOT NULL,
  `isPPC` int(11) NOT NULL,
  `ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `page_open_time` datetime DEFAULT NULL,
  `page_close_time` datetime DEFAULT NULL,
  `time_spent` int(11) DEFAULT NULL,
  `click_id` int(11) NOT NULL,
  `click_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: integration_orders
#

CREATE TABLE `integration_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_ids` varchar(888) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` double NOT NULL,
  `currency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `commission_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission` double NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ads_id` int(11) NOT NULL,
  `script_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `admin_tran` int(11) DEFAULT NULL,
  `affiliate_tran` int(11) DEFAULT NULL,
  `custom_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fraud_score` tinyint(3) unsigned DEFAULT NULL COMMENT 'v15: 0-100 fraud score at time of order. NULL = pre-v15 record.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: integration_programs
#

CREATE TABLE `integration_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_sale` float DEFAULT 0,
  `sale_status` int(11) DEFAULT NULL,
  `commission_number_of_click` int(11) DEFAULT NULL,
  `commission_click_commission` float DEFAULT 0,
  `click_status` int(11) DEFAULT NULL,
  `admin_commission_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_commission_sale` float DEFAULT 0,
  `admin_commission_number_of_click` int(11) DEFAULT NULL,
  `admin_commission_click_commission` float DEFAULT 0,
  `admin_click_status` int(11) DEFAULT NULL,
  `admin_sale_status` int(11) DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `click_allow` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: integration_refer_product_action
#

CREATE TABLE `integration_refer_product_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(20) NOT NULL,
  `script_name` varchar(50) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `base_url` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` text DEFAULT NULL,
  `viewer_id` int(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `pay_commition` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `count_for` varchar(255) DEFAULT NULL,
  `action_code` varchar(255) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `ads_id` int(11) NOT NULL,
  `is_action` int(11) NOT NULL,
  `tools_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: integration_tools
#

CREATE TABLE `integration_tools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `program_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_link` varchar(500) NOT NULL,
  `status` int(11) NOT NULL,
  `security_status` tinyint(1) NOT NULL DEFAULT 0,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tool_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'program',
  `action_click` int(11) NOT NULL,
  `action_amount` double NOT NULL,
  `general_click` int(11) NOT NULL,
  `general_amount` double NOT NULL,
  `general_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_allow_group` tinyint(1) NOT NULL DEFAULT 0,
  `allow_groups` varchar(255) DEFAULT NULL,
  `allow_for` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recursion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recursion_custom_time` bigint(20) NOT NULL,
  `recursion_endtime` varchar(255) DEFAULT NULL,
  `marketpostback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_action_click` int(11) DEFAULT NULL,
  `admin_action_amount` float DEFAULT 0,
  `admin_general_click` int(11) DEFAULT NULL,
  `admin_general_amount` float DEFAULT 0,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `tool_integration_plugin` varchar(50) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `trigger_count` int(11) NOT NULL DEFAULT 0,
  `security_check_perform_on` varchar(20) DEFAULT NULL,
  `cookies_type` tinyint(1) NOT NULL DEFAULT 0,
  `custom_cookies` int(11) NOT NULL,
  `country_sortname` varchar(255) DEFAULT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `api_key` varchar(64) DEFAULT NULL,
  `s2s_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `s2s_direct_mode` tinyint(1) NOT NULL DEFAULT 0,
  `integration_method` varchar(20) NOT NULL DEFAULT 'js_pixel',
  `min_health_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `min_award_level_id` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: integration_tools_ads
#

CREATE TABLE `integration_tools_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tools_id` int(11) NOT NULL,
  `ads_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: language
#

CREATE TABLE `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_rtl` int(11) NOT NULL DEFAULT 0,
  `flag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: last_seen
#

CREATE TABLE `last_seen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: mail_templates
#

CREATE TABLE `mail_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(355) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `admin_subject` varchar(355) DEFAULT NULL,
  `client_subject` varchar(355) DEFAULT NULL,
  `client_text` text DEFAULT NULL,
  `admin_text` text DEFAULT NULL,
  `shortcode` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: membership_buy_history
#

CREATE TABLE `membership_buy_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buy_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: membership_plans
#

CREATE TABLE `membership_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `billing_period` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` double DEFAULT 0,
  `special` double DEFAULT 0,
  `custom_period` double DEFAULT 0,
  `have_trail` int(11) DEFAULT NULL,
  `free_trail` double DEFAULT 0,
  `total_day` int(11) DEFAULT NULL,
  `bonus` double NOT NULL,
  `commission_sale_status` tinyint(1) NOT NULL DEFAULT 0,
  `level_id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `user_type` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `campaign` int(10) unsigned DEFAULT NULL,
  `product` int(10) unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `plan_icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `label_text` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `label_background` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '#28A745',
  `label_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '#FFFFFF',
  `sort_order` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: membership_user
#

CREATE TABLE `membership_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_day` int(11) DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
  `started_at` datetime NOT NULL,
  `status_id` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `is_lifetime` int(11) NOT NULL DEFAULT 0,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total` double DEFAULT 0,
  `bonus_commission` double DEFAULT 0,
  `expire_mail_sent` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: meta_data
#

CREATE TABLE `meta_data` (
  `meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: notification
#

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_viewfor` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `notification_view_user_id` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `notification_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `notification_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `notification_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `notification_actionID` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `notification_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `store_contactus_description` longtext DEFAULT NULL,
  `notification_is_read` int(11) NOT NULL,
  `notification_ipaddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `notification_created_date` datetime NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: order
#

CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `txn_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `zip_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `shipping_method` varchar(100) NOT NULL DEFAULT 'flat_rate',
  `shipping_tracking` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `shipping_cost` float NOT NULL DEFAULT 0,
  `actual_shipping_cost` float NOT NULL DEFAULT 0,
  `tax_cost` float NOT NULL DEFAULT 0,
  `total` float NOT NULL DEFAULT 0,
  `coupon_discount` double NOT NULL,
  `total_commition` double NOT NULL,
  `shipping_charge` double NOT NULL,
  `currency_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `allow_shipping` int(11) NOT NULL DEFAULT 1,
  `ip` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `country_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `files` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `shipping_tracking_number` varchar(255) DEFAULT NULL,
  `shipping_carrier` varchar(100) DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_status_created` (`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: order_products
#

CREATE TABLE `order_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variation` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `refer_id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `form_id` int(11) NOT NULL,
  `msrp` double NOT NULL,
  `price` double NOT NULL,
  `total` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `commission` double NOT NULL,
  `commission_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `admin_commission` float DEFAULT 0,
  `admin_commission_type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `vendor_commission` float DEFAULT 0,
  `vendor_commission_type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `coupon_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `coupon_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `coupon_discount` double NOT NULL DEFAULT 0,
  `allow_shipping` int(11) NOT NULL DEFAULT 1,
  `actual_shipping_cost` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_op_product_order` (`product_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: order_proof
#

CREATE TABLE `order_proof` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `proof` varchar(355) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: orders_history
#

CREATE TABLE `orders_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_mode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'paypal',
  `history_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'payment',
  `paypal_status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `order_status_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: pagebuilder_theme
#

CREATE TABLE `pagebuilder_theme` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: pagebuilder_theme_page
#

CREATE TABLE `pagebuilder_theme_page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_tab` int(11) NOT NULL DEFAULT 0,
  `meta_tag_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_tag_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_tag_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `design` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `home_page` int(11) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: password_resets
#

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(555) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: payment_detail
#

CREATE TABLE `payment_detail` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_bank_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payment_account_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payment_account_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payment_ifsc_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payment_country` varchar(10) DEFAULT NULL,
  `payment_bank_branch_number` varchar(50) DEFAULT NULL,
  `payment_clearing_code` varchar(50) DEFAULT NULL,
  `payment_swift_code` varchar(50) DEFAULT NULL,
  `payment_cnaps_code` varchar(50) DEFAULT NULL,
  `payment_iban_bic` varchar(50) DEFAULT NULL,
  `payment_transit_institution_number` varchar(50) DEFAULT NULL,
  `payment_bsb_number` varchar(50) DEFAULT NULL,
  `payment_sort_code` varchar(50) DEFAULT NULL,
  `payment_routing_number` varchar(50) DEFAULT NULL,
  `payment_status` int(11) NOT NULL,
  `payment_ipaddress` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payment_created_date` datetime NOT NULL,
  `payment_updated_date` datetime NOT NULL,
  `payment_created_by` int(11) NOT NULL,
  `payment_updated_by` int(11) NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: payout_batch_items
#

CREATE TABLE `payout_batch_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL,
  `wallet_request_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `receiver_snapshot` varchar(255) NOT NULL,
  `reconciliation_status` varchar(20) NOT NULL DEFAULT 'pending',
  `reconciliation_detail` text DEFAULT NULL,
  `reconciliation_at` datetime DEFAULT NULL,
  `provider_txn_id` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pbi_batch_wr` (`batch_id`,`wallet_request_id`),
  KEY `idx_pbi_batch` (`batch_id`),
  KEY `idx_pbi_wr` (`wallet_request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# TABLE STRUCTURE FOR: payout_batches
#

CREATE TABLE `payout_batches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by_admin_id` int(11) DEFAULT NULL,
  `processor` varchar(20) NOT NULL,
  `currency_code` varchar(10) NOT NULL DEFAULT 'USD',
  `row_count` int(11) NOT NULL DEFAULT 0,
  `total_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `status` varchar(20) NOT NULL DEFAULT 'exported',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pb_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# TABLE STRUCTURE FOR: paypal_accounts
#

CREATE TABLE `paypal_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: product
#

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_campaign_product` tinyint(1) NOT NULL DEFAULT 0,
  `min_health_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `min_award_level_id` smallint(5) unsigned DEFAULT NULL,
  `product_url` text DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_short_description` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_tags` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_msrp` double NOT NULL,
  `product_price` double NOT NULL,
  `product_sku` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_share_count` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_click_count` int(11) DEFAULT 0,
  `product_view_count` int(11) DEFAULT 0,
  `product_sales_count` int(11) DEFAULT 0,
  `product_featured_image` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_banner` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_video` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_commision_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_commision_value` double NOT NULL,
  `product_status` int(11) NOT NULL,
  `product_ipaddress` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_created_date` datetime NOT NULL,
  `product_updated_date` datetime NOT NULL,
  `product_created_by` int(11) NOT NULL,
  `product_updated_by` int(11) NOT NULL,
  `product_click_commision_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_click_commision_ppc` double NOT NULL,
  `product_click_commision_per` double NOT NULL,
  `product_total_commission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0',
  `product_recursion_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_recursion` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `recursion_custom_time` bigint(20) NOT NULL,
  `recursion_endtime` varchar(255) DEFAULT NULL,
  `view` int(11) NOT NULL,
  `on_store` int(11) NOT NULL DEFAULT 0,
  `downloadable_files` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `allow_shipping` int(11) NOT NULL DEFAULT 1,
  `allow_upload_file` int(11) NOT NULL,
  `allow_comment` int(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `product_avg_rating` int(11) NOT NULL DEFAULT 0,
  `product_variations` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_weight` decimal(8,2) NOT NULL DEFAULT 0.00,
  `product_length` decimal(8,2) NOT NULL DEFAULT 0.00,
  `product_width` decimal(8,2) NOT NULL DEFAULT 0.00,
  `product_height` decimal(8,2) NOT NULL DEFAULT 0.00,
  `view_statistics` int(11) NOT NULL DEFAULT 0,
  `product_quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`product_id`),
  KEY `idx_product_store_status` (`on_store`,`product_status`,`is_campaign_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: product_action
#

CREATE TABLE `product_action` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_type` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` text DEFAULT NULL,
  `viewer_id` int(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `pay_commition` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `country_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: product_action_admin
#

CREATE TABLE `product_action_admin` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_type` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` text DEFAULT NULL,
  `viewer_id` int(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `pay_commition` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `country_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: product_affiliate
#

CREATE TABLE `product_affiliate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_sale_commission_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `admin_commission_value` float DEFAULT 0,
  `admin_click_commission_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `admin_click_amount` float DEFAULT 0,
  `admin_click_count` int(11) DEFAULT NULL,
  `affiliate_sale_commission_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `affiliate_commission_value` float DEFAULT 0,
  `affiliate_click_commission_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `affiliate_click_count` int(11) DEFAULT NULL,
  `affiliate_click_amount` float DEFAULT 0,
  `comment` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: product_categories
#

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `category_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: product_media_upload
#

CREATE TABLE `product_media_upload` (
  `product_media_upload_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_media_upload_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_media_upload_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_media_upload_video_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'no-image.jpg',
  `product_media_upload_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_media_upload_ipaddress` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_media_upload_created_date` datetime NOT NULL,
  `product_media_upload_created_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_media_upload_os` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_media_upload_browser` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_media_upload_isp` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`product_media_upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: product_meta
#

CREATE TABLE `product_meta` (
  `product_meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `related_product_id` int(11) NOT NULL,
  `meta_key` varchar(100) NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`product_meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: product_view_logs
#

CREATE TABLE `product_view_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `form_id` int(11) DEFAULT NULL,
  `tools_id` int(11) DEFAULT NULL,
  `link` varchar(555) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `browserName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browserVersion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `systemString` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `osPlatform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `osVersion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `osShortVersion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isMobile` int(11) DEFAULT NULL,
  `mobileName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `custom_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: productslog
#

CREATE TABLE `productslog` (
  `productslog_id` int(11) NOT NULL AUTO_INCREMENT,
  `productslog_user_id` int(11) DEFAULT NULL,
  `products_id` int(11) DEFAULT NULL,
  `productslog_status` int(11) DEFAULT NULL,
  `productslog_click` int(11) DEFAULT NULL,
  `productslog_view` int(11) DEFAULT NULL,
  `productslog_referrer` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `productslog_user_agent` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `productslog_os` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `productslog_browser` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `productslog_isp` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `productslog_ipaddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `productslog_created_by` int(11) DEFAULT NULL,
  `productslog_updated_by` int(11) DEFAULT NULL,
  `productslog_created` datetime NOT NULL,
  `productslog_updated` datetime NOT NULL,
  PRIMARY KEY (`productslog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: rating
#

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `rating_user_id` int(11) DEFAULT NULL,
  `rating_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `rating_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `products_id` int(11) DEFAULT NULL,
  `rating_status` int(11) DEFAULT NULL,
  `rating_number` int(11) NOT NULL,
  `rating_comments` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `rating_referrer` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `rating_user_agent` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `rating_os` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `rating_browser` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `rating_isp` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `rating_ipaddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `rating_created_by` int(11) DEFAULT NULL,
  `rating_updated_by` int(11) DEFAULT NULL,
  `rating_created` datetime NOT NULL,
  `rating_updated` datetime NOT NULL,
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: refer_market_action
#

CREATE TABLE `refer_market_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliate_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `commission` double(22,0) NOT NULL DEFAULT 0,
  `count_for` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: refer_product_action
#

CREATE TABLE `refer_product_action` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_type` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` text DEFAULT NULL,
  `viewer_id` int(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `pay_commition` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `count_for` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: setting
#

CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `setting_value` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `setting_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `setting_status` int(11) NOT NULL,
  `setting_ipaddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `setting_is_default` int(11) NOT NULL DEFAULT 0,
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB AUTO_INCREMENT=496 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: shipping_address
#

CREATE TABLE `shipping_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `zip_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: shipping_rates_cache
#

CREATE TABLE `shipping_rates_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_key` varchar(255) NOT NULL,
  `cache_value` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_key_index` (`cache_key`),
  KEY `created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: slugs
#

CREATE TABLE `slugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: states
#

CREATE TABLE `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4122 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: stock_history
#

CREATE TABLE `stock_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `quantity_change` int(11) NOT NULL DEFAULT 0,
  `quantity_after` int(11) NOT NULL DEFAULT 0,
  `reason` varchar(100) NOT NULL DEFAULT 'manual',
  `reference_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sh_product` (`product_id`),
  KEY `idx_sh_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: store_messages
#

CREATE TABLE `store_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('customer','vendor','admin') NOT NULL DEFAULT 'customer',
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sm_order` (`order_id`),
  KEY `idx_sm_sender` (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: stripe_sessions
#

CREATE TABLE `stripe_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `affiliate_id` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_colors
#

CREATE TABLE `theme_colors` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text NOT NULL,
  `setting_type` varchar(255) NOT NULL,
  `setting_status` int(11) NOT NULL,
  `setting_ipaddress` varchar(255) NOT NULL,
  `setting_is_default` int(11) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: theme_faq
#

CREATE TABLE `theme_faq` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_theme_id` int(11) NOT NULL,
  `faq_question` text NOT NULL,
  `faq_answer` text NOT NULL,
  `position` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '1',
  `Created` datetime NOT NULL DEFAULT current_timestamp(),
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_home_sections_setting
#

CREATE TABLE `theme_home_sections_setting` (
  `sec_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `sec_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sec_position` tinyint(1) NOT NULL,
  `sec_is_enable` tinyint(1) NOT NULL,
  PRIMARY KEY (`sec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_homecontent
#

CREATE TABLE `theme_homecontent` (
  `homecontent_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(250) NOT NULL,
  `position` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT current_timestamp(),
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`homecontent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_links
#

CREATE TABLE `theme_links` (
  `tlink_id` int(11) NOT NULL AUTO_INCREMENT,
  `tlink_title` varchar(250) NOT NULL,
  `tlink_url` text NOT NULL,
  `tlink_position` tinyint(4) NOT NULL,
  `tlink_status` tinyint(1) NOT NULL DEFAULT 1,
  `tlink_target_blank` tinyint(1) NOT NULL DEFAULT 1,
  `tlink_created_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`tlink_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_pages
#

CREATE TABLE `theme_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `top_banner_title` varchar(255) NOT NULL,
  `top_banner_sub_title` varchar(255) NOT NULL,
  `page_content_title` varchar(255) NOT NULL,
  `page_content` longtext NOT NULL,
  `link_footer_section` varchar(200) NOT NULL,
  `is_header_menu` tinyint(1) NOT NULL DEFAULT 0,
  `is_header_dropdown` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(11) NOT NULL COMMENT 'moved var(50) to int(11)',
  `page_type` enum('editable','fixed') DEFAULT 'editable',
  `page_banner_image` varchar(250) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`page_id`),
  KEY `status` (`status`) USING BTREE,
  KEY `is_header_menu` (`is_header_menu`) USING BTREE,
  KEY `is_header_dropdown` (`is_header_dropdown`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_recommendation
#

CREATE TABLE `theme_recommendation` (
  `recommendation_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `position` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`recommendation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_sections
#

CREATE TABLE `theme_sections` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `link` varchar(200) NOT NULL,
  `button_text` varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_setting
#

CREATE TABLE `theme_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text NOT NULL,
  `setting_type` varchar(255) NOT NULL,
  `setting_status` int(11) NOT NULL,
  `setting_ipaddress` varchar(255) NOT NULL,
  `setting_is_default` int(11) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: theme_settings
#

CREATE TABLE `theme_settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `custom_logo_size` tinyint(1) NOT NULL DEFAULT 0,
  `log_custom_height` smallint(6) NOT NULL,
  `log_custom_width` smallint(6) NOT NULL,
  `top_banner_slider` text NOT NULL,
  `membership_top_title` text NOT NULL,
  `membership_sub_title` text NOT NULL,
  `contact_us_t_title` text NOT NULL,
  `contact_us_slug_title` text NOT NULL,
  `contact_sec_title` varchar(250) NOT NULL,
  `contact_sec_subtitle` text NOT NULL,
  `contact_us_email` varchar(250) NOT NULL,
  `contact_us_full_address` text NOT NULL,
  `contact_us_phone` varchar(200) NOT NULL,
  `contact_us_iframe` text NOT NULL,
  `contact_banner_image` varchar(250) NOT NULL,
  `youtube_link` varchar(200) NOT NULL,
  `facebook_link` varchar(200) NOT NULL,
  `twitter_link` varchar(200) NOT NULL,
  `instegram_link` varchar(200) NOT NULL,
  `whatsapp_number` varchar(20) NOT NULL,
  `whatsapp_default_msg` varchar(250) NOT NULL,
  `footer_about_title` text NOT NULL,
  `footer_about_text` text NOT NULL,
  `footer_menu_title_a` text NOT NULL,
  `footer_menu_title_b` text NOT NULL,
  `footer_menu_title_c` text NOT NULL,
  `footer_menu_title_d` text NOT NULL,
  `banner_bottom_title` text NOT NULL,
  `banner_bottom_slug` text NOT NULL,
  `banner_button_text` text NOT NULL,
  `banner_button_link` varchar(200) NOT NULL,
  `copyright` text NOT NULL,
  `video_title` varchar(200) NOT NULL,
  `video_sub_title` varchar(200) NOT NULL,
  `login_img` varchar(250) NOT NULL,
  `reg_img` varchar(250) NOT NULL,
  `terms_img` varchar(250) NOT NULL,
  `login_content` text NOT NULL,
  `reg_content` text NOT NULL,
  `terms_content` text NOT NULL,
  `home_section_title` varchar(200) NOT NULL,
  `home_section_subtitle` varchar(250) NOT NULL,
  `recommendation_section_title` varchar(200) NOT NULL,
  `recommendation_section_subtitle` varchar(250) NOT NULL,
  `faq_banner_title` varchar(250) NOT NULL,
  `faq_section_title` varchar(250) NOT NULL,
  `faq_section_subtitle` varchar(250) NOT NULL,
  `faq_banner_image` varchar(250) NOT NULL,
  `homepage_video_section_bg` varchar(250) NOT NULL,
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_sliders
#

CREATE TABLE `theme_sliders` (
  `slider_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `link` varchar(200) NOT NULL,
  `button_text` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`slider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: theme_videos
#

CREATE TABLE `theme_videos` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `video_title` varchar(200) NOT NULL,
  `video_sub_title` text NOT NULL,
  `video_link` varchar(200) NOT NULL,
  `position` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `language_id` int(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: tickets
#

CREATE TABLE `tickets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ticket_id` text NOT NULL COMMENT 'like AF10254',
  `user_id` bigint(20) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `last_replay` text DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1=open, 2=pending, 3 =close',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: tickets_reply
#

CREATE TABLE `tickets_reply` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ticket_id` text NOT NULL,
  `message` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `message_type` int(11) NOT NULL COMMENT '1 for text ,2 files ',
  `user_id` bigint(20) NOT NULL,
  `user_type` int(11) NOT NULL COMMENT '1 for admin ,2 affiliate/vendor',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: tickets_subject
#

CREATE TABLE `tickets_subject` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` text NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0 deactivate, 1 active ',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: todo_list
#

CREATE TABLE `todo_list` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `notes` text NOT NULL,
  `is_done` enum('0','1') NOT NULL COMMENT '0 for open, 1 for close',
  `todo_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: tutorial_categories
#

CREATE TABLE `tutorial_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `language_id` int(3) NOT NULL DEFAULT 1,
  `updated_at` datetime DEFAULT NULL,
  `position` bigint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: tutorial_pages
#

CREATE TABLE `tutorial_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `language_id` int(3) NOT NULL DEFAULT 1,
  `status` int(1) NOT NULL DEFAULT 1,
  `position` bigint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: uncompleted_payment
#

CREATE TABLE `uncompleted_payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `payment_module` tinyint(2) unsigned NOT NULL,
  `completed_id` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `additional_info` text NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: unsubscribed_emails
#

CREATE TABLE `unsubscribed_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `unsubscribed_at` datetime DEFAULT current_timestamp(),
  `source` varchar(50) NOT NULL DEFAULT 'email_link',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: user_groups
#

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(60) NOT NULL,
  `group_description` text DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `is_default` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1=>for set default,0=>not set default',
  `created_at` datetime NOT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: user_lms_product
#

CREATE TABLE `user_lms_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `lms_product` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: user_payment_details
#

CREATE TABLE `user_payment_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gateway_code` varchar(50) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_value` text DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_gateway_field` (`user_id`,`gateway_code`,`field_name`),
  KEY `user_gateway` (`user_id`,`gateway_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# TABLE STRUCTURE FOR: user_payment_request
#

CREATE TABLE `user_payment_request` (
  `user_payment_request_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_payment_request_user_id` int(11) DEFAULT NULL,
  `user_payment_request_amount` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_amount_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_amount_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_payment_mode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_ipaddress` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_created_date` datetime NOT NULL,
  `user_payment_request_updated_date` datetime NOT NULL,
  `user_payment_request_user_agent` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_os` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_browser` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_isp` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_payment_request_created_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_payment_request_updated_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`user_payment_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: users
#

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL DEFAULT -1,
  `refid` int(11) NOT NULL,
  `level_id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'user',
  `firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `twaddress` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address1` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address2` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ucity` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ucountry` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `state` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `uzip` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `online` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `unique_url` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `bitly_unique_url` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `google_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `facebook_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `twitter_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `umode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PhoneNumber` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Addressone` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Addresstwo` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `City` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Country` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `StateProvince` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Zip` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `f_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `t_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `l_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `products_wishlist` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_commission` float DEFAULT 0,
  `affiliate_commission` float DEFAULT 0,
  `product_commission_paid` float DEFAULT 0,
  `affiliate_commission_paid` float DEFAULT 0,
  `product_total_click` int(11) DEFAULT NULL,
  `product_total_sale` int(11) DEFAULT NULL,
  `affiliate_total_click` int(11) DEFAULT NULL,
  `sale_commission` float DEFAULT 0,
  `sale_commission_paid` float DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `reg_approved` tinyint(1) NOT NULL DEFAULT 1,
  `is_vendor` tinyint(1) NOT NULL,
  `store_meta` varchar(255) DEFAULT NULL,
  `store_slug` varchar(255) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `store_contact_us_map` varchar(500) DEFAULT NULL,
  `store_address` varchar(255) DEFAULT NULL,
  `store_email` varchar(100) DEFAULT NULL,
  `store_contact_number` varchar(100) DEFAULT NULL,
  `store_terms_condition` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `last_ping` datetime DEFAULT NULL,
  `install_location_details` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `token` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `device_type` int(11) NOT NULL DEFAULT 1 COMMENT '1 = android, 2 = ios',
  `device_token` text DEFAULT NULL,
  `groups` varchar(255) DEFAULT NULL,
  `verification_id` varchar(50) DEFAULT NULL,
  `primary_payment_method` varchar(100) DEFAULT NULL,
  `s3_bucket_name` varchar(255) DEFAULT NULL,
  `s3_region` varchar(255) DEFAULT NULL,
  `admin_role_id` int(11) DEFAULT NULL COMMENT 'Admin role; NULL = full access (backward compatible)',
  `admin_permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Custom permissions override; when set, used instead of role' CHECK (json_valid(`admin_permissions`)),
  `theme_preference` varchar(10) NOT NULL DEFAULT 'light',
  `total_fraud_blocks` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT 'v15: Running count of Red-zone auto-blocked events for this affiliate.',
  `health_score` decimal(5,2) DEFAULT NULL COMMENT 'Affiliate health index 0-100, NULL if never calculated.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: vendor_config
#

CREATE TABLE `vendor_config` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `setting_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_status` int(11) NOT NULL,
  `setting_ipaddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_is_default` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`setting_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: vendor_deposit
#

CREATE TABLE `vendor_deposit` (
  `vd_id` int(11) NOT NULL AUTO_INCREMENT,
  `vd_user_id` int(11) NOT NULL,
  `vd_amount` double NOT NULL,
  `vd_status` int(11) NOT NULL,
  `vd_payment_method` varchar(50) NOT NULL,
  `vd_txn_id` varchar(50) DEFAULT NULL,
  `vd_meta` text DEFAULT NULL,
  `vd_created_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`vd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# TABLE STRUCTURE FOR: vendor_payouts
#

CREATE TABLE `vendor_payouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `method` varchar(50) NOT NULL DEFAULT 'bank',
  `status` enum('pending','approved','denied','paid') NOT NULL DEFAULT 'pending',
  `reference` varchar(255) DEFAULT NULL,
  `vendor_note` text DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `requested_at` datetime NOT NULL DEFAULT current_timestamp(),
  `processed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_vp_vendor` (`vendor_id`),
  KEY `idx_vp_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: vendor_setting
#

CREATE TABLE `vendor_setting` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_status` int(11) NOT NULL DEFAULT 0,
  `affiliate_sale_commission_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `affiliate_commission_value` float DEFAULT 0,
  `affiliate_click_count` int(11) DEFAULT NULL,
  `affiliate_click_amount` float DEFAULT 0,
  `form_affiliate_click_count` int(11) DEFAULT NULL,
  `form_affiliate_click_amount` float DEFAULT 0,
  `form_affiliate_sale_commission_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `form_affiliate_commission_value` float DEFAULT 0,
  `vendor_shares_sales_status` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: version_update
#

CREATE TABLE `version_update` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `script_version` varchar(20) NOT NULL,
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: wallet
#

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `amount` double NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dis_type` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `commission_status` int(11) NOT NULL DEFAULT 0 COMMENT '1 = Cancel, 2 = Trash',
  `reference_id` int(11) NOT NULL,
  `reference_id_2` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ip_details` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `comm_from` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'store',
  `domain_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `page_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `is_action` int(11) NOT NULL DEFAULT 0,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `group_id` double(22,0) DEFAULT 0,
  `is_vendor` int(11) NOT NULL DEFAULT 0,
  `wv` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: wallet_recursion
#

CREATE TABLE `wallet_recursion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `custom_time` bigint(20) DEFAULT NULL,
  `next_transaction` datetime NOT NULL,
  `last_transaction` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: wallet_request
#

CREATE TABLE `wallet_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: wallet_requests
#

CREATE TABLE `wallet_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tran_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` double NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prefer_method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `batch_export_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_wr_batch_export` (`batch_export_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: wallet_requests_history
#

CREATE TABLE `wallet_requests_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `req_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `comment` varchar(355) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `currency` SET `currency_id`= '1',`title`= 'US Dollar',`code`= 'USD',`symbol_left`= '$',`symbol_right`= '',`decimal_place`= '2',`value`= '1',`status`= '1',`is_default`= '1',`date_modified`= '2026-02-22 22:50:41',`replace_comma_symbol`= ',',`decimal_symbol`= '.';
INSERT INTO `language` SET `id`= '1',`name`= 'English',`is_default`= '1',`is_rtl`= '0',`flag`= 'assets/template/images/flags/us.png',`status`= '1';
INSERT INTO `users` SET `id`= '1',`plan_id`= '-1',`refid`= '0',`level_id`= '0',`type`= 'admin',`firstname`= 'Admin',`lastname`= 'Admin',`email`= 'admin@gmail.com',`username`= 'admin',`password`= '7479305b3e914c467c0cb2eba57b352b58e1ff37',`phone`= '',`twaddress`= '',`address1`= '',`address2`= '',`ucity`= '',`ucountry`= '',`state`= '',`uzip`= '',`avatar`= NULL,`online`= '1',`unique_url`= '',`bitly_unique_url`= '',`updated_at`= '2021-01-01 16:15:31',`google_id`= '',`facebook_id`= '',`twitter_id`= '',`umode`= '',`PhoneNumber`= '+1 201-555-0123',`Addressone`= '',`Addresstwo`= '',`City`= 'Test City',`Country`= '13',`StateProvince`= NULL,`Zip`= '123456',`f_link`= '',`t_link`= '',`l_link`= '',`products_wishlist`= NULL,`product_commission`= '0',`affiliate_commission`= '0',`product_commission_paid`= '0',`affiliate_commission_paid`= '0',`product_total_click`= '0',`product_total_sale`= '0',`affiliate_total_click`= '0',`sale_commission`= '0',`sale_commission_paid`= '0',`status`= '1',`reg_approved`= '1',`is_vendor`= '0',`store_meta`= NULL,`store_slug`= NULL,`store_name`= NULL,`store_contact_us_map`= NULL,`store_address`= NULL,`store_email`= NULL,`store_contact_number`= NULL,`store_terms_condition`= '',`value`= '',`last_ping`= '2026-05-09 17:29:30',`install_location_details`= '',`token`= NULL,`created_at`= '2021-01-01 16:15:31',`device_type`= '1',`device_token`= NULL,`groups`= NULL,`verification_id`= NULL,`primary_payment_method`= NULL,`s3_bucket_name`= NULL,`s3_region`= NULL,`admin_role_id`= NULL,`admin_permissions`= NULL,`theme_preference`= 'light',`total_fraud_blocks`= '0',`health_score`= NULL;
INSERT INTO `countries` SET `id`= '3',`sortname`= 'DZ',`name`= 'Algeria',`phonecode`= '213',`lat`= '28.033886',`lng`= '1.659626',`created_by`= '1';
INSERT INTO `countries` SET `id`= '4',`sortname`= 'AS',`name`= 'American Samoa',`phonecode`= '1684',`lat`= '-14.270972',`lng`= '-170.132217',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '5',`sortname`= 'AD',`name`= 'Andorra',`phonecode`= '376',`lat`= '42.546245',`lng`= '1.601554',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '6',`sortname`= 'AO',`name`= 'Angola',`phonecode`= '244',`lat`= '-11.202692',`lng`= '17.873887',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '7',`sortname`= 'AI',`name`= 'Anguilla',`phonecode`= '1264',`lat`= '18.220554',`lng`= '-63.068615',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '8',`sortname`= 'AQ',`name`= 'Antarctica',`phonecode`= '0',`lat`= '-75.250973',`lng`= '-0.071389',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '9',`sortname`= 'AG',`name`= 'Antigua And Barbuda',`phonecode`= '1268',`lat`= '17.060816',`lng`= '-61.796428',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '10',`sortname`= 'AR',`name`= 'Argentina',`phonecode`= '54',`lat`= '-38.416097',`lng`= '-63.616672',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '11',`sortname`= 'AM',`name`= 'Armenia',`phonecode`= '374',`lat`= '40.069099',`lng`= '45.038189',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '12',`sortname`= 'AW',`name`= 'Aruba',`phonecode`= '297',`lat`= '12.52111',`lng`= '-69.968338',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '13',`sortname`= 'AU',`name`= 'Australia',`phonecode`= '61',`lat`= '-25.274398',`lng`= '133.775136',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '14',`sortname`= 'AT',`name`= 'Austria',`phonecode`= '43',`lat`= '47.516231',`lng`= '14.550072',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '15',`sortname`= 'AZ',`name`= 'Azerbaijan',`phonecode`= '994',`lat`= '40.143105',`lng`= '47.576927',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '16',`sortname`= 'BS',`name`= 'Bahamas The',`phonecode`= '1242',`lat`= '25.03428',`lng`= '-77.39628',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '17',`sortname`= 'BH',`name`= 'Bahrain',`phonecode`= '973',`lat`= '25.930414',`lng`= '50.637772',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '18',`sortname`= 'BD',`name`= 'Bangladesh',`phonecode`= '880',`lat`= '23.684994',`lng`= '90.356331',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '19',`sortname`= 'BB',`name`= 'Barbados',`phonecode`= '1246',`lat`= '13.193887',`lng`= '-59.543198',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '20',`sortname`= 'BY',`name`= 'Belarus',`phonecode`= '375',`lat`= '53.709807',`lng`= '27.953389',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '21',`sortname`= 'BE',`name`= 'Belgium',`phonecode`= '32',`lat`= '50.503887',`lng`= '4.469936',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '22',`sortname`= 'BZ',`name`= 'Belize',`phonecode`= '501',`lat`= '17.189877',`lng`= '-88.49765',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '23',`sortname`= 'BJ',`name`= 'Benin',`phonecode`= '229',`lat`= '9.30769',`lng`= '2.315834',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '24',`sortname`= 'BM',`name`= 'Bermuda',`phonecode`= '1441',`lat`= '32.321384',`lng`= '-64.75737',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '25',`sortname`= 'BT',`name`= 'Bhutan',`phonecode`= '975',`lat`= '27.514162',`lng`= '90.433601',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '26',`sortname`= 'BO',`name`= 'Bolivia',`phonecode`= '591',`lat`= '-16.290154',`lng`= '-63.588653',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '27',`sortname`= 'BA',`name`= 'Bosnia and Herzegovina',`phonecode`= '387',`lat`= '43.915886',`lng`= '17.679076',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '28',`sortname`= 'BW',`name`= 'Botswana',`phonecode`= '267',`lat`= '-22.328474',`lng`= '24.684866',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '29',`sortname`= 'BV',`name`= 'Bouvet Island',`phonecode`= '0',`lat`= '-54.423199',`lng`= '3.413194',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '30',`sortname`= 'BR',`name`= 'Brazil',`phonecode`= '55',`lat`= '-14.235004',`lng`= '-51.92528',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '31',`sortname`= 'IO',`name`= 'British Indian Ocean Territory',`phonecode`= '246',`lat`= '-6.343194',`lng`= '71.876519',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '32',`sortname`= 'BN',`name`= 'Brunei',`phonecode`= '673',`lat`= '4.535277',`lng`= '114.727669',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '33',`sortname`= 'BG',`name`= 'Bulgaria',`phonecode`= '359',`lat`= '42.733883',`lng`= '25.48583',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '34',`sortname`= 'BF',`name`= 'Burkina Faso',`phonecode`= '226',`lat`= '12.238333',`lng`= '-1.561593',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '35',`sortname`= 'BI',`name`= 'Burundi',`phonecode`= '257',`lat`= '-3.373056',`lng`= '29.918886',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '36',`sortname`= 'KH',`name`= 'Cambodia',`phonecode`= '855',`lat`= '12.565679',`lng`= '104.990963',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '37',`sortname`= 'CM',`name`= 'Cameroon',`phonecode`= '237',`lat`= '7.369722',`lng`= '12.354722',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '38',`sortname`= 'CA',`name`= 'Canada',`phonecode`= '1',`lat`= '56.130366',`lng`= '-106.346771',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '39',`sortname`= 'CV',`name`= 'Cape Verde',`phonecode`= '238',`lat`= '16.002082',`lng`= '-24.013197',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '40',`sortname`= 'KY',`name`= 'Cayman Islands',`phonecode`= '1345',`lat`= '19.513469',`lng`= '-80.566956',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '41',`sortname`= 'CF',`name`= 'Central African Republic',`phonecode`= '236',`lat`= '6.611111',`lng`= '20.939444',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '42',`sortname`= 'TD',`name`= 'Chad',`phonecode`= '235',`lat`= '15.454166',`lng`= '18.732207',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '43',`sortname`= 'CL',`name`= 'Chile',`phonecode`= '56',`lat`= '-35.675147',`lng`= '-71.542969',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '44',`sortname`= 'CN',`name`= 'China',`phonecode`= '86',`lat`= '35.86166',`lng`= '104.195397',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '45',`sortname`= 'CX',`name`= 'Christmas Island',`phonecode`= '61',`lat`= '-10.447525',`lng`= '105.690449',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '46',`sortname`= 'CC',`name`= 'Cocos (Keeling) Islands',`phonecode`= '672',`lat`= '-12.164165',`lng`= '96.870956',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '47',`sortname`= 'CO',`name`= 'Colombia',`phonecode`= '57',`lat`= '4.570868',`lng`= '-74.297333',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '48',`sortname`= 'KM',`name`= 'Comoros',`phonecode`= '269',`lat`= '-11.875001',`lng`= '43.872219',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '49',`sortname`= 'CG',`name`= 'Republic Of The Congo',`phonecode`= '242',`lat`= '-0.228021',`lng`= '15.827659',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '50',`sortname`= 'CD',`name`= 'Democratic Republic Of The Congo',`phonecode`= '242',`lat`= '-4.038333',`lng`= '21.758664',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '51',`sortname`= 'CK',`name`= 'Cook Islands',`phonecode`= '682',`lat`= '-21.236736',`lng`= '-159.777671',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '52',`sortname`= 'CR',`name`= 'Costa Rica',`phonecode`= '506',`lat`= '9.748917',`lng`= '-83.753428',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '53',`sortname`= 'CI',`name`= 'Cote D\'Ivoire (Ivory Coast)',`phonecode`= '225',`lat`= '7.539989',`lng`= '-5.54708',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '54',`sortname`= 'HR',`name`= 'Croatia (Hrvatska)',`phonecode`= '385',`lat`= '45.1',`lng`= '15.2',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '55',`sortname`= 'CU',`name`= 'Cuba',`phonecode`= '53',`lat`= '21.521757',`lng`= '-77.781167',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '56',`sortname`= 'CY',`name`= 'Cyprus',`phonecode`= '357',`lat`= '35.126413',`lng`= '33.429859',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '57',`sortname`= 'CZ',`name`= 'Czech Republic',`phonecode`= '420',`lat`= '49.817492',`lng`= '15.472962',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '58',`sortname`= 'DK',`name`= 'Denmark',`phonecode`= '45',`lat`= '56.26392',`lng`= '9.501785',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '59',`sortname`= 'DJ',`name`= 'Djibouti',`phonecode`= '253',`lat`= '11.825138',`lng`= '42.590275',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '60',`sortname`= 'DM',`name`= 'Dominica',`phonecode`= '1767',`lat`= '15.414999',`lng`= '-61.370976',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '61',`sortname`= 'DO',`name`= 'Dominican Republic',`phonecode`= '1809',`lat`= '18.735693',`lng`= '-70.162651',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '62',`sortname`= 'TP',`name`= 'East Timor',`phonecode`= '670',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '63',`sortname`= 'EC',`name`= 'Ecuador',`phonecode`= '593',`lat`= '-1.831239',`lng`= '-78.183406',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '64',`sortname`= 'EG',`name`= 'Egypt',`phonecode`= '20',`lat`= '26.820553',`lng`= '30.802498',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '65',`sortname`= 'SV',`name`= 'El Salvador',`phonecode`= '503',`lat`= '13.794185',`lng`= '-88.89653',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '66',`sortname`= 'GQ',`name`= 'Equatorial Guinea',`phonecode`= '240',`lat`= '1.650801',`lng`= '10.267895',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '67',`sortname`= 'ER',`name`= 'Eritrea',`phonecode`= '291',`lat`= '15.179384',`lng`= '39.782334',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '68',`sortname`= 'EE',`name`= 'Estonia',`phonecode`= '372',`lat`= '58.595272',`lng`= '25.013607',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '69',`sortname`= 'ET',`name`= 'Ethiopia',`phonecode`= '251',`lat`= '9.145',`lng`= '40.489673',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '70',`sortname`= 'XA',`name`= 'External Territories of Australia',`phonecode`= '61',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '71',`sortname`= 'FK',`name`= 'Falkland Islands',`phonecode`= '500',`lat`= '-51.796253',`lng`= '-59.523613',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '72',`sortname`= 'FO',`name`= 'Faroe Islands',`phonecode`= '298',`lat`= '61.892635',`lng`= '-6.911806',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '73',`sortname`= 'FJ',`name`= 'Fiji Islands',`phonecode`= '679',`lat`= '-16.578193',`lng`= '179.414413',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '74',`sortname`= 'FI',`name`= 'Finland',`phonecode`= '358',`lat`= '61.92411',`lng`= '25.748151',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '75',`sortname`= 'FR',`name`= 'France',`phonecode`= '33',`lat`= '46.227638',`lng`= '2.213749',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '76',`sortname`= 'GF',`name`= 'French Guiana',`phonecode`= '594',`lat`= '3.933889',`lng`= '-53.125782',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '77',`sortname`= 'PF',`name`= 'French Polynesia',`phonecode`= '689',`lat`= '-17.679742',`lng`= '-149.406843',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '78',`sortname`= 'TF',`name`= 'French Southern Territories',`phonecode`= '0',`lat`= '-49.280366',`lng`= '69.348557',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '79',`sortname`= 'GA',`name`= 'Gabon',`phonecode`= '241',`lat`= '-0.803689',`lng`= '11.609444',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '80',`sortname`= 'GM',`name`= 'Gambia The',`phonecode`= '220',`lat`= '13.443182',`lng`= '-15.310139',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '81',`sortname`= 'GE',`name`= 'Georgia',`phonecode`= '995',`lat`= '42.315407',`lng`= '43.356892',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '82',`sortname`= 'DE',`name`= 'Germany',`phonecode`= '49',`lat`= '51.165691',`lng`= '10.451526',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '83',`sortname`= 'GH',`name`= 'Ghana',`phonecode`= '233',`lat`= '7.946527',`lng`= '-1.023194',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '84',`sortname`= 'GI',`name`= 'Gibraltar',`phonecode`= '350',`lat`= '36.137741',`lng`= '-5.345374',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '85',`sortname`= 'GR',`name`= 'Greece',`phonecode`= '30',`lat`= '39.074208',`lng`= '21.824312',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '86',`sortname`= 'GL',`name`= 'Greenland',`phonecode`= '299',`lat`= '71.706936',`lng`= '-42.604303',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '87',`sortname`= 'GD',`name`= 'Grenada',`phonecode`= '1473',`lat`= '12.262776',`lng`= '-61.604171',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '88',`sortname`= 'GP',`name`= 'Guadeloupe',`phonecode`= '590',`lat`= '16.995971',`lng`= '-62.067641',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '89',`sortname`= 'GU',`name`= 'Guam',`phonecode`= '1671',`lat`= '13.444304',`lng`= '144.793731',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '90',`sortname`= 'GT',`name`= 'Guatemala',`phonecode`= '502',`lat`= '15.783471',`lng`= '-90.230759',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '91',`sortname`= 'XU',`name`= 'Guernsey and Alderney',`phonecode`= '44',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '92',`sortname`= 'GN',`name`= 'Guinea',`phonecode`= '224',`lat`= '9.945587',`lng`= '-9.696645',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '93',`sortname`= 'GW',`name`= 'Guinea-Bissau',`phonecode`= '245',`lat`= '11.803749',`lng`= '-15.180413',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '94',`sortname`= 'GY',`name`= 'Guyana',`phonecode`= '592',`lat`= '4.860416',`lng`= '-58.93018',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '95',`sortname`= 'HT',`name`= 'Haiti',`phonecode`= '509',`lat`= '18.971187',`lng`= '-72.285215',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '96',`sortname`= 'HM',`name`= 'Heard and McDonald Islands',`phonecode`= '0',`lat`= '-53.08181',`lng`= '73.504158',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '97',`sortname`= 'HN',`name`= 'Honduras',`phonecode`= '504',`lat`= '15.199999',`lng`= '-86.241905',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '98',`sortname`= 'HK',`name`= 'Hong Kong S.A.R.',`phonecode`= '852',`lat`= '22.396428',`lng`= '114.109497',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '99',`sortname`= 'HU',`name`= 'Hungary',`phonecode`= '36',`lat`= '47.162494',`lng`= '19.503304',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '100',`sortname`= 'IS',`name`= 'Iceland',`phonecode`= '354',`lat`= '64.963051',`lng`= '-19.020835',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '101',`sortname`= 'IN',`name`= 'India',`phonecode`= '91',`lat`= '20.593684',`lng`= '78.96288',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '102',`sortname`= 'ID',`name`= 'Indonesia',`phonecode`= '62',`lat`= '-0.789275',`lng`= '113.921327',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '103',`sortname`= 'IR',`name`= 'Iran',`phonecode`= '98',`lat`= '32.427908',`lng`= '53.688046',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '104',`sortname`= 'IQ',`name`= 'Iraq',`phonecode`= '964',`lat`= '33.223191',`lng`= '43.679291',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '105',`sortname`= 'IE',`name`= 'Ireland',`phonecode`= '353',`lat`= '53.41291',`lng`= '-8.24389',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '106',`sortname`= 'IL',`name`= 'Israel',`phonecode`= '972',`lat`= '31.046051',`lng`= '34.851612',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '107',`sortname`= 'IT',`name`= 'Italy',`phonecode`= '39',`lat`= '41.87194',`lng`= '12.56738',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '108',`sortname`= 'JM',`name`= 'Jamaica',`phonecode`= '1876',`lat`= '18.109581',`lng`= '-77.297508',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '109',`sortname`= 'JP',`name`= 'Japan',`phonecode`= '81',`lat`= '36.204824',`lng`= '138.252924',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '110',`sortname`= 'XJ',`name`= 'Jersey',`phonecode`= '44',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '111',`sortname`= 'JO',`name`= 'Jordan',`phonecode`= '962',`lat`= '30.585164',`lng`= '36.238414',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '112',`sortname`= 'KZ',`name`= 'Kazakhstan',`phonecode`= '7',`lat`= '48.019573',`lng`= '66.923684',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '113',`sortname`= 'KE',`name`= 'Kenya',`phonecode`= '254',`lat`= '-0.023559',`lng`= '37.906193',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '114',`sortname`= 'KI',`name`= 'Kiribati',`phonecode`= '686',`lat`= '-3.370417',`lng`= '-168.734039',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '115',`sortname`= 'KP',`name`= 'Korea North',`phonecode`= '850',`lat`= '40.339852',`lng`= '127.510093',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '116',`sortname`= 'KR',`name`= 'Korea South',`phonecode`= '82',`lat`= '35.907757',`lng`= '127.766922',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '117',`sortname`= 'KW',`name`= 'Kuwait',`phonecode`= '965',`lat`= '29.31166',`lng`= '47.481766',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '118',`sortname`= 'KG',`name`= 'Kyrgyzstan',`phonecode`= '996',`lat`= '41.20438',`lng`= '74.766098',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '119',`sortname`= 'LA',`name`= 'Laos',`phonecode`= '856',`lat`= '19.85627',`lng`= '102.495496',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '120',`sortname`= 'LV',`name`= 'Latvia',`phonecode`= '371',`lat`= '56.879635',`lng`= '24.603189',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '121',`sortname`= 'LB',`name`= 'Lebanon',`phonecode`= '961',`lat`= '33.854721',`lng`= '35.862285',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '122',`sortname`= 'LS',`name`= 'Lesotho',`phonecode`= '266',`lat`= '-29.609988',`lng`= '28.233608',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '123',`sortname`= 'LR',`name`= 'Liberia',`phonecode`= '231',`lat`= '6.428055',`lng`= '-9.429499',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '124',`sortname`= 'LY',`name`= 'Libya',`phonecode`= '218',`lat`= '26.3351',`lng`= '17.228331',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '125',`sortname`= 'LI',`name`= 'Liechtenstein',`phonecode`= '423',`lat`= '47.166',`lng`= '9.555373',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '126',`sortname`= 'LT',`name`= 'Lithuania',`phonecode`= '370',`lat`= '55.169438',`lng`= '23.881275',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '127',`sortname`= 'LU',`name`= 'Luxembourg',`phonecode`= '352',`lat`= '49.815273',`lng`= '6.129583',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '128',`sortname`= 'MO',`name`= 'Macau S.A.R.',`phonecode`= '853',`lat`= '22.198745',`lng`= '113.543873',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '129',`sortname`= 'MK',`name`= 'Macedonia',`phonecode`= '389',`lat`= '41.608635',`lng`= '21.745275',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '130',`sortname`= 'MG',`name`= 'Madagascar',`phonecode`= '261',`lat`= '-18.766947',`lng`= '46.869107',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '131',`sortname`= 'MW',`name`= 'Malawi',`phonecode`= '265',`lat`= '-13.254308',`lng`= '34.301525',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '132',`sortname`= 'MY',`name`= 'Malaysia',`phonecode`= '60',`lat`= '4.210484',`lng`= '101.975766',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '133',`sortname`= 'MV',`name`= 'Maldives',`phonecode`= '960',`lat`= '3.202778',`lng`= '73.22068',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '134',`sortname`= 'ML',`name`= 'Mali',`phonecode`= '223',`lat`= '17.570692',`lng`= '-3.996166',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '135',`sortname`= 'MT',`name`= 'Malta',`phonecode`= '356',`lat`= '35.937496',`lng`= '14.375416',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '136',`sortname`= 'XM',`name`= 'Man (Isle of)',`phonecode`= '44',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '137',`sortname`= 'MH',`name`= 'Marshall Islands',`phonecode`= '692',`lat`= '7.131474',`lng`= '171.184478',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '138',`sortname`= 'MQ',`name`= 'Martinique',`phonecode`= '596',`lat`= '14.641528',`lng`= '-61.024174',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '139',`sortname`= 'MR',`name`= 'Mauritania',`phonecode`= '222',`lat`= '21.00789',`lng`= '-10.940835',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '140',`sortname`= 'MU',`name`= 'Mauritius',`phonecode`= '230',`lat`= '-20.348404',`lng`= '57.552152',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '141',`sortname`= 'YT',`name`= 'Mayotte',`phonecode`= '269',`lat`= '-12.8275',`lng`= '45.166244',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '142',`sortname`= 'MX',`name`= 'Mexico',`phonecode`= '52',`lat`= '23.634501',`lng`= '-102.552784',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '143',`sortname`= 'FM',`name`= 'Micronesia',`phonecode`= '691',`lat`= '7.425554',`lng`= '150.550812',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '144',`sortname`= 'MD',`name`= 'Moldova',`phonecode`= '373',`lat`= '47.411631',`lng`= '28.369885',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '145',`sortname`= 'MC',`name`= 'Monaco',`phonecode`= '377',`lat`= '43.750298',`lng`= '7.412841',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '146',`sortname`= 'MN',`name`= 'Mongolia',`phonecode`= '976',`lat`= '46.862496',`lng`= '103.846656',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '147',`sortname`= 'MS',`name`= 'Montserrat',`phonecode`= '1664',`lat`= '16.742498',`lng`= '-62.187366',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '148',`sortname`= 'MA',`name`= 'Morocco',`phonecode`= '212',`lat`= '31.791702',`lng`= '-7.09262',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '149',`sortname`= 'MZ',`name`= 'Mozambique',`phonecode`= '258',`lat`= '-18.665695',`lng`= '35.529562',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '150',`sortname`= 'MM',`name`= 'Myanmar',`phonecode`= '95',`lat`= '21.913965',`lng`= '95.956223',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '151',`sortname`= 'NA',`name`= 'Namibia',`phonecode`= '264',`lat`= '-22.95764',`lng`= '18.49041',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '152',`sortname`= 'NR',`name`= 'Nauru',`phonecode`= '674',`lat`= '-0.522778',`lng`= '166.931503',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '153',`sortname`= 'NP',`name`= 'Nepal',`phonecode`= '977',`lat`= '28.394857',`lng`= '84.124008',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '154',`sortname`= 'AN',`name`= 'Netherlands Antilles',`phonecode`= '599',`lat`= '12.226079',`lng`= '-69.060087',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '155',`sortname`= 'NL',`name`= 'Netherlands',`phonecode`= '31',`lat`= '52.132633',`lng`= '5.291266',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '156',`sortname`= 'NC',`name`= 'New Caledonia',`phonecode`= '687',`lat`= '-20.904305',`lng`= '165.618042',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '157',`sortname`= 'NZ',`name`= 'New Zealand',`phonecode`= '64',`lat`= '-40.900557',`lng`= '174.885971',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '158',`sortname`= 'NI',`name`= 'Nicaragua',`phonecode`= '505',`lat`= '12.865416',`lng`= '-85.207229',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '159',`sortname`= 'NE',`name`= 'Niger',`phonecode`= '227',`lat`= '17.607789',`lng`= '8.081666',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '160',`sortname`= 'NG',`name`= 'Nigeria',`phonecode`= '234',`lat`= '9.081999',`lng`= '8.675277',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '161',`sortname`= 'NU',`name`= 'Niue',`phonecode`= '683',`lat`= '-19.054445',`lng`= '-169.867233',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '162',`sortname`= 'NF',`name`= 'Norfolk Island',`phonecode`= '672',`lat`= '-29.040835',`lng`= '167.954712',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '163',`sortname`= 'MP',`name`= 'Northern Mariana Islands',`phonecode`= '1670',`lat`= '17.33083',`lng`= '145.38469',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '164',`sortname`= 'NO',`name`= 'Norway',`phonecode`= '47',`lat`= '60.472024',`lng`= '8.468946',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '165',`sortname`= 'OM',`name`= 'Oman',`phonecode`= '968',`lat`= '21.512583',`lng`= '55.923255',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '166',`sortname`= 'PK',`name`= 'Pakistan',`phonecode`= '92',`lat`= '30.375321',`lng`= '69.345116',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '167',`sortname`= 'PW',`name`= 'Palau',`phonecode`= '680',`lat`= '7.51498',`lng`= '134.58252',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '168',`sortname`= 'PS',`name`= 'Palestinian Territory Occupied',`phonecode`= '970',`lat`= '31.952162',`lng`= '35.233154',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '169',`sortname`= 'PA',`name`= 'Panama',`phonecode`= '507',`lat`= '8.537981',`lng`= '-80.782127',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '170',`sortname`= 'PG',`name`= 'Papua new Guinea',`phonecode`= '675',`lat`= '-6.314993',`lng`= '143.95555',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '171',`sortname`= 'PY',`name`= 'Paraguay',`phonecode`= '595',`lat`= '-23.442503',`lng`= '-58.443832',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '172',`sortname`= 'PE',`name`= 'Peru',`phonecode`= '51',`lat`= '-9.189967',`lng`= '-75.015152',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '173',`sortname`= 'PH',`name`= 'Philippines',`phonecode`= '63',`lat`= '12.879721',`lng`= '121.774017',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '174',`sortname`= 'PN',`name`= 'Pitcairn Island',`phonecode`= '0',`lat`= '-24.703615',`lng`= '-127.439308',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '175',`sortname`= 'PL',`name`= 'Poland',`phonecode`= '48',`lat`= '51.919438',`lng`= '19.145136',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '176',`sortname`= 'PT',`name`= 'Portugal',`phonecode`= '351',`lat`= '39.399872',`lng`= '-8.224454',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '177',`sortname`= 'PR',`name`= 'Puerto Rico',`phonecode`= '1787',`lat`= '18.220833',`lng`= '-66.590149',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '178',`sortname`= 'QA',`name`= 'Qatar',`phonecode`= '974',`lat`= '25.354826',`lng`= '51.183884',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '179',`sortname`= 'RE',`name`= 'Reunion',`phonecode`= '262',`lat`= '-21.115141',`lng`= '55.536384',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '180',`sortname`= 'RO',`name`= 'Romania',`phonecode`= '40',`lat`= '45.943161',`lng`= '24.96676',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '181',`sortname`= 'RU',`name`= 'Russia',`phonecode`= '70',`lat`= '61.52401',`lng`= '105.318756',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '182',`sortname`= 'RW',`name`= 'Rwanda',`phonecode`= '250',`lat`= '-1.940278',`lng`= '29.873888',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '183',`sortname`= 'SH',`name`= 'Saint Helena',`phonecode`= '290',`lat`= '-24.143474',`lng`= '-10.030696',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '184',`sortname`= 'KN',`name`= 'Saint Kitts And Nevis',`phonecode`= '1869',`lat`= '17.357822',`lng`= '-62.782998',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '185',`sortname`= 'LC',`name`= 'Saint Lucia',`phonecode`= '1758',`lat`= '13.909444',`lng`= '-60.978893',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '186',`sortname`= 'PM',`name`= 'Saint Pierre and Miquelon',`phonecode`= '508',`lat`= '46.941936',`lng`= '-56.27111',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '187',`sortname`= 'VC',`name`= 'Saint Vincent And The Grenadines',`phonecode`= '1784',`lat`= '12.984305',`lng`= '-61.287228',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '188',`sortname`= 'WS',`name`= 'Samoa',`phonecode`= '684',`lat`= '-13.759029',`lng`= '-172.104629',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '189',`sortname`= 'SM',`name`= 'San Marino',`phonecode`= '378',`lat`= '43.94236',`lng`= '12.457777',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '190',`sortname`= 'ST',`name`= 'Sao Tome and Principe',`phonecode`= '239',`lat`= '0.18636',`lng`= '6.613081',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '191',`sortname`= 'SA',`name`= 'Saudi Arabia',`phonecode`= '966',`lat`= '23.885942',`lng`= '45.079162',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '192',`sortname`= 'SN',`name`= 'Senegal',`phonecode`= '221',`lat`= '14.497401',`lng`= '-14.452362',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '193',`sortname`= 'RS',`name`= 'Serbia',`phonecode`= '381',`lat`= '44.016521',`lng`= '21.005859',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '194',`sortname`= 'SC',`name`= 'Seychelles',`phonecode`= '248',`lat`= '-4.679574',`lng`= '55.491977',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '195',`sortname`= 'SL',`name`= 'Sierra Leone',`phonecode`= '232',`lat`= '8.460555',`lng`= '-11.779889',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '196',`sortname`= 'SG',`name`= 'Singapore',`phonecode`= '65',`lat`= '1.352083',`lng`= '103.819836',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '197',`sortname`= 'SK',`name`= 'Slovakia',`phonecode`= '421',`lat`= '48.669026',`lng`= '19.699024',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '198',`sortname`= 'SI',`name`= 'Slovenia',`phonecode`= '386',`lat`= '46.151241',`lng`= '14.995463',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '199',`sortname`= 'XG',`name`= 'Smaller Territories of the UK',`phonecode`= '44',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '200',`sortname`= 'SB',`name`= 'Solomon Islands',`phonecode`= '677',`lat`= '-9.64571',`lng`= '160.156194',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '201',`sortname`= 'SO',`name`= 'Somalia',`phonecode`= '252',`lat`= '5.152149',`lng`= '46.199616',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '202',`sortname`= 'ZA',`name`= 'South Africa',`phonecode`= '27',`lat`= '-30.559482',`lng`= '22.937506',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '203',`sortname`= 'GS',`name`= 'South Georgia',`phonecode`= '0',`lat`= '-54.429579',`lng`= '-36.587909',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '204',`sortname`= 'SS',`name`= 'South Sudan',`phonecode`= '211',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '205',`sortname`= 'ES',`name`= 'Spain',`phonecode`= '34',`lat`= '40.463667',`lng`= '-3.74922',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '206',`sortname`= 'LK',`name`= 'Sri Lanka',`phonecode`= '94',`lat`= '7.873054',`lng`= '80.771797',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '207',`sortname`= 'SD',`name`= 'Sudan',`phonecode`= '249',`lat`= '12.862807',`lng`= '30.217636',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '208',`sortname`= 'SR',`name`= 'Suriname',`phonecode`= '597',`lat`= '3.919305',`lng`= '-56.027783',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '209',`sortname`= 'SJ',`name`= 'Svalbard And Jan Mayen Islands',`phonecode`= '47',`lat`= '77.553604',`lng`= '23.670272',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '210',`sortname`= 'SZ',`name`= 'Swaziland',`phonecode`= '268',`lat`= '-26.522503',`lng`= '31.465866',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '211',`sortname`= 'SE',`name`= 'Sweden',`phonecode`= '46',`lat`= '60.128161',`lng`= '18.643501',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '212',`sortname`= 'CH',`name`= 'Switzerland',`phonecode`= '41',`lat`= '46.818188',`lng`= '8.227512',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '213',`sortname`= 'SY',`name`= 'Syria',`phonecode`= '963',`lat`= '34.802075',`lng`= '38.996815',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '214',`sortname`= 'TW',`name`= 'Taiwan',`phonecode`= '886',`lat`= '23.69781',`lng`= '120.960515',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '215',`sortname`= 'TJ',`name`= 'Tajikistan',`phonecode`= '992',`lat`= '38.861034',`lng`= '71.276093',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '216',`sortname`= 'TZ',`name`= 'Tanzania',`phonecode`= '255',`lat`= '-6.369028',`lng`= '34.888822',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '217',`sortname`= 'TH',`name`= 'Thailand',`phonecode`= '66',`lat`= '15.870032',`lng`= '100.992541',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '218',`sortname`= 'TG',`name`= 'Togo',`phonecode`= '228',`lat`= '8.619543',`lng`= '0.824782',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '219',`sortname`= 'TK',`name`= 'Tokelau',`phonecode`= '690',`lat`= '-8.967363',`lng`= '-171.855881',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '220',`sortname`= 'TO',`name`= 'Tonga',`phonecode`= '676',`lat`= '-21.178986',`lng`= '-175.198242',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '221',`sortname`= 'TT',`name`= 'Trinidad And Tobago',`phonecode`= '1868',`lat`= '10.691803',`lng`= '-61.222503',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '222',`sortname`= 'TN',`name`= 'Tunisia',`phonecode`= '216',`lat`= '33.886917',`lng`= '9.537499',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '223',`sortname`= 'TR',`name`= 'Turkey',`phonecode`= '90',`lat`= '38.963745',`lng`= '35.243322',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '224',`sortname`= 'TM',`name`= 'Turkmenistan',`phonecode`= '7370',`lat`= '38.969719',`lng`= '59.556278',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '225',`sortname`= 'TC',`name`= 'Turks And Caicos Islands',`phonecode`= '1649',`lat`= '21.694025',`lng`= '-71.797928',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '226',`sortname`= 'TV',`name`= 'Tuvalu',`phonecode`= '688',`lat`= '-7.109535',`lng`= '177.64933',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '227',`sortname`= 'UG',`name`= 'Uganda',`phonecode`= '256',`lat`= '1.373333',`lng`= '32.290275',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '228',`sortname`= 'UA',`name`= 'Ukraine',`phonecode`= '380',`lat`= '48.379433',`lng`= '31.16558',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '229',`sortname`= 'AE',`name`= 'United Arab Emirates',`phonecode`= '971',`lat`= '23.424076',`lng`= '53.847818',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '230',`sortname`= 'GB',`name`= 'United Kingdom',`phonecode`= '44',`lat`= '55.378051',`lng`= '-3.435973',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '231',`sortname`= 'US',`name`= 'United States',`phonecode`= '1',`lat`= '37.09024',`lng`= '-95.712891',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '232',`sortname`= 'UM',`name`= 'United States Minor Outlying Islands',`phonecode`= '1',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '233',`sortname`= 'UY',`name`= 'Uruguay',`phonecode`= '598',`lat`= '-32.522779',`lng`= '-55.765835',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '234',`sortname`= 'UZ',`name`= 'Uzbekistan',`phonecode`= '998',`lat`= '41.377491',`lng`= '64.585262',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '235',`sortname`= 'VU',`name`= 'Vanuatu',`phonecode`= '678',`lat`= '-15.376706',`lng`= '166.959158',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '236',`sortname`= 'VA',`name`= 'Vatican City State (Holy See)',`phonecode`= '39',`lat`= '41.902916',`lng`= '12.453389',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '237',`sortname`= 'VE',`name`= 'Venezuela',`phonecode`= '58',`lat`= '6.42375',`lng`= '-66.58973',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '238',`sortname`= 'VN',`name`= 'Vietnam',`phonecode`= '84',`lat`= '14.058324',`lng`= '108.277199',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '239',`sortname`= 'VG',`name`= 'Virgin Islands (British)',`phonecode`= '1284',`lat`= '18.420695',`lng`= '-64.639968',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '240',`sortname`= 'VI',`name`= 'Virgin Islands (US)',`phonecode`= '1340',`lat`= '18.335765',`lng`= '-64.896335',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '241',`sortname`= 'WF',`name`= 'Wallis And Futuna Islands',`phonecode`= '681',`lat`= '-13.768752',`lng`= '-177.156097',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '242',`sortname`= 'EH',`name`= 'Western Sahara',`phonecode`= '212',`lat`= '24.215527',`lng`= '-12.885834',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '243',`sortname`= 'YE',`name`= 'Yemen',`phonecode`= '967',`lat`= '15.552727',`lng`= '48.516388',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '244',`sortname`= 'YU',`name`= 'Yugoslavia',`phonecode`= '38',`lat`= '',`lng`= '',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '245',`sortname`= 'ZM',`name`= 'Zambia',`phonecode`= '260',`lat`= '-13.133897',`lng`= '27.849332',`created_by`= NULL;
INSERT INTO `countries` SET `id`= '246',`sortname`= 'ZW',`name`= 'Zimbabwe',`phonecode`= '263',`lat`= '-19.015438',`lng`= '29.154857',`created_by`= NULL;
INSERT INTO `mail_templates` SET `id`= '1',`unique_id`= '',`name`= 'User Registration',`subject`= 'User Registration Successfully',`text`= '<p>Dear [[firstname]],</p>\r\n\r\n<p>Your new affiliate user account has been created welcome to the [[website_name]]</p>\r\n\r\n<p>your account details:</p>\r\n\r\n<p>================</p>\r\n\r\n<p>[[firstname]]</p>\r\n\r\n<p>[[username]]</p>\r\n\r\n<p>[[email]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= 'Admin : New affiliate user Register',`client_subject`= NULL,`client_text`= NULL,`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>&nbsp;New affiliate user Register on your site&nbsp;[[website_name]]</p>\r\n\r\n<p>Affiliate details:</p>\r\n\r\n<p>============</p>\r\n\r\n<p>[[firstname]]</p>\r\n\r\n<p>[[username]]</p>\r\n\r\n<p>[[email]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`shortcode`= 'firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '2',`unique_id`= '',`name`= 'Client Registration',`subject`= 'New Client Register Under you',`text`= '<p>Dear [[firstname]],</p>\r\n\r\n<p>New client account has been created under you</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= 'Admin : New Client Register',`client_subject`= 'Dear [[firstname]], Welcome To Our Store',`client_text`= '<p>Dear [[firstname]],</p>\r\n\r\n<p>welcome to the [[website_name]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>New client has been registered on your store</p>\r\n\r\n<p>[[firstname]] ,&nbsp;[[lastname]]&nbsp;</p>\r\n\r\n<p>[[email]] | [[username]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`shortcode`= 'firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '3',`unique_id`= '',`name`= 'Forget Password',`subject`= 'User Forget Password',`text`= '<p>Dear [[firstname]],</p>\r\n\r\n<p>You recently request to reset your password from your [[website_name]] account click the below link to reset password</p>\r\n\r\n<p>[[reset_link]]</p>\r\n\r\n<p>If you did not request a password rest, please ignore this email or reply us know.</p>\r\n\r\n<p>[[website_name]]</p>\r\n\r\n<p>If you did not request a password rest, please ignore this email or reply us know.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks<br />\r\n[[website_name]]</p>\r\n',`admin_subject`= 'Admin : Forget Password',`client_subject`= 'Client : Forget Password',`client_text`= '<p>Dear [[firstname]],</p>\r\n\r\n<p>You recently request to reset your password from your [[website_name]] account click the below link to reset password</p>\r\n\r\n<p>[[reset_link]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>If you did not request a password rest, please ignore this email or reply us know.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks<br />\r\n[[website_name]]</p>\r\n',`admin_text`= '<p>Dear [[firstname]],</p>\r\n\r\n<p>You recently request to reset your password from your [[website_name]] account click the below link to reset password</p>\r\n\r\n<p>[[reset_link]]</p>\r\n\r\n<p>If you did not request a password rest, please ignore this email or reply us know.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks<br />\r\n[[website_name]]</p>\r\n',`shortcode`= 'reset_link,firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '4',`unique_id`= '',`name`= 'Send Wallet withdrawal Request',`subject`= 'Send Wallet Withdrawal Request',`text`= '<p>Dear [[name]],</p>\r\n\r\n<p>Your withdrawal request is accept successfully and procced shortly</p>\r\n\r\n<p>Amount : [[amount]]</p>\r\n\r\n<p>Thanks<br />\r\n[[website_name]]</p>\r\n',`admin_subject`= 'Admin : Send Wallet Withdrawal Request',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear [[name]],</p>\r\n\r\n<p>Your withdrawal request is accept successfully and procced shortly</p>\r\n\r\n<p>Amount : [[amount]]</p>\r\n\r\n<p>Thanks<br />\r\n[[website_name]]</p>\r\n',`shortcode`= 'amount,comment,name,user_email,commission_type,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '5',`unique_id`= '',`name`= 'withdrawal request status change',`subject`= 'Your withdrawal request status change',`text`= '<p>Dear [[name]],</p>\r\n\r\n<p>Your withdrawal request status has been change to : <strong>[[new_status]]</strong></p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= 'Admin side',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear [[name]],</p>\r\n\r\n<p>Withdrawal request status has been change to : <strong>[[new_status]]</strong></p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`shortcode`= 'amount,comment,name,user_email,commission_type,website_name,website_logo,new_status';
INSERT INTO `mail_templates` SET `id`= '6',`unique_id`= '',`name`= 'Store Contact Us',`subject`= '',`text`= '',`admin_subject`= 'Admin : Store Contact Us',`client_subject`= 'We will contact to you shortly ..!',`client_text`= '<p>&nbsp;</p>\r\n\r\n<p><strong>Name </strong>: [[name]]</p>\r\n\r\n<p><strong>Email </strong>: [[email]]</p>\r\n\r\n<p><strong>Phone </strong>: [[phone]]</p>\r\n\r\n<p><strong>Message</strong> :</p>\r\n\r\n<p>[[message]]</p>\r\n\r\n<p>&nbsp;</p>\r\n',`admin_text`= '<p>Hey Admin <strong>[[name]] </strong>trying to contact you.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Name </strong>: [[name]]</p>\r\n\r\n<p><strong>Email </strong>: [[email]]</p>\r\n\r\n<p><strong>Phone </strong>: [[phone]]</p>\r\n\r\n<p><strong>Message</strong> :</p>\r\n\r\n<p>[[message]]</p>\r\n\r\n<p>&nbsp;</p>\r\n',`shortcode`= 'name,email,phone,message,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '7',`unique_id`= '',`name`= 'Order Status Has Been Change',`subject`= 'Your Order Status Has Been Change',`text`= '<p>Hello<strong>&nbsp;[[firstname]] [[lastname]]</strong></p>\r\n\r\n<p>Your Order Status Has Been Change to <strong>[[status]]</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>[[comment]]</p>\r\n\r\n<p><br />\r\norder Id :<strong> [[order_id]]</strong></p>\r\n',`admin_subject`= 'Admin : Your Order Status Has Been Change',`client_subject`= 'Client: Your Order Status Has Been Change',`client_text`= '<p>Hello<strong>&nbsp;[[firstname]] [[lastname]]</strong></p>\r\n\r\n<p>Your Order Status Has Been Change to <strong>[[status]]</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>[[comment]]</p>\r\n\r\n<p><br />\r\norder Id :<strong> [[order_id]]</strong></p>\r\n',`admin_text`= '<p>Hello<strong>&nbsp;[[firstname]] [[lastname]]</strong></p>\r\n\r\n<p>Your Order Status Has Been Change to <strong>[[status]]</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>[[comment]]</p>\r\n\r\n<p><br />\r\norder Id :<strong> [[order_id]]</strong></p>\r\n',`shortcode`= 'order_id,status,order_link,product_name,product_description,commission_type,PhoneNumber,firstname,lastname,commission,total,currency_code,txn_id,website_name,website_logo,comment';
INSERT INTO `mail_templates` SET `id`= '8',`unique_id`= '',`name`= 'New Order',`subject`= 'Affiliate: New Order Commission From [[firstname]] [[lastname]]',`text`= '<p>Hello Affiliate,</p>\r\n\r\n<p>you got new order Commission from sale thats done under [[firstname]] [[lastname]]</p>\r\n\r\n<p>Commission: [[commission]] -&nbsp;[[commission_type]]</p>\r\n\r\n<p><strong>Commission for product_name :&nbsp;</strong>[[product_name]]&nbsp;[[variation_details]]</p>\r\n\r\n<p><strong>product_description</strong> : [[product_description]]</p>\r\n',`admin_subject`= 'Admin : New Order [[order_id]] has been successfully placed.',`client_subject`= 'Client : New Order [[order_id]] has been successfully placed.',`client_text`= '<p>Dear Client,</p>\r\n\r\n<p>New Order <strong>[[order_id]] </strong>has been successfully placed on your site [[website_name]] .</p>\r\n\r\n<p><strong>Order Status</strong> : [[status]]<br />\r\n<strong>Total Amount</strong> : [[total]]<br />\r\n<strong>Transaction ID</strong> : [[txn_id]]</p>\r\n\r\n<p>[[order_link]]</p>\r\n',`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>New Order <strong>[[order_id]] </strong>has been successfully placed on your site [[website_name]] .</p>\r\n\r\n<p><strong>Order Status</strong> : [[status]]<br />\r\n<strong>Total Amount</strong> : [[total]]<br />\r\n<strong>Transaction ID</strong> : [[txn_id]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>[[order_link]]</p>\r\n',`shortcode`= 'order_id,status,order_link,product_name,variation_details,product_description,commission_type,PhoneNumber,firstname,lastname,commission,total,currency_code,txn_id,website_name,website_logo,order_id';
INSERT INTO `mail_templates` SET `id`= '10',`unique_id`= '',`name`= 'get market click notification',`subject`= 'Get market click notification',`text`= '<p>Dear [[name]],</p>\r\n\r\n<p>[[firstname]] [[lastname]] got commition from market [[affiliateads_type]] click</p>\r\n\r\n<p>Commition : [[affiliate_commission]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= 'Admin : Get market click notification',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear [[name]],</p>\r\n\r\n<p>[[firstname]] [[lastname]] got commition from market [[affiliateads_type]] click</p>\r\n\r\n<p>Commition : [[affiliate_commission]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`shortcode`= 'affiliateads_type,affiliate_commission,firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '11',`unique_id`= '',`name`= 'External Website New Order',`subject`= 'External Website New Order [[external_website_name]]',`text`= '<p>Hey&nbsp;[[username]]</p>\r\n\r\n<p>You have got&nbsp;[[commission]] from [[external_website_name]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Thanks&nbsp;</strong></p>\r\n\r\n<p>[[website_name]]</p>\r\n',`admin_subject`= 'External Website New Order [[external_website_name]]',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Hey New Order Placed at&nbsp;[[external_website_name]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>User </strong>:&nbsp;[[username]]</p>\r\n\r\n<p><strong>Website </strong>:&nbsp;[[external_website_name]]</p>\r\n\r\n<p><strong>commission </strong>: [[commission]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Thanks&nbsp;</strong></p>\r\n\r\n<p>[[website_name]]</p>\r\n',`shortcode`= 'external_website_name,commission,username,website_name,website_logo,product_ids,total,currency,commission_type,script_name';
INSERT INTO `mail_templates` SET `id`= '12',`unique_id`= '',`name`= 'wallet status change to in wallet',`subject`= '[[amount]] credited in your wallet',`text`= '<p>Dear [[name]],</p>\r\n\r\n<p>[[comment]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= '',`client_subject`= '',`client_text`= '',`admin_text`= '',`shortcode`= 'amount,comment,name,user_email,website_name,website_logo,new_status';
INSERT INTO `mail_templates` SET `id`= '13',`unique_id`= '',`name`= 'User Registration From Integration',`subject`= 'User Registration Successfully',`text`= '<p>Dear [[firstname]],</p>\r\n\r\n<p>Your new affiliate user account has been created welcome to the [[website_name]]</p>\r\n\r\n<p>your account details:</p>\r\n\r\n<p>================</p>\r\n\r\n<p>[[firstname]]</p>\r\n\r\n<p>[[username]]</p>\r\n\r\n<p>[[email]]</p>\r\n\r\n<h2>password is :&nbsp;<strong>[[password]]</strong></h2>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= 'Admin : New affiliate user Register From Integration',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>&nbsp;New affiliate user Register on your site&nbsp;[[website_name]]</p>\r\n\r\n<p>Affiliate details:</p>\r\n\r\n<p>============</p>\r\n\r\n<p>[[firstname]]</p>\r\n\r\n<p>[[username]]</p>\r\n\r\n<p>[[email]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`shortcode`= 'firstname,lastname,email,username,password,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '36',`unique_id`= 'new_order_for_vendor',`name`= 'Vendor Got New Order',`subject`= 'Vendor: You have new order from [[firstname]] [[lastname]]',`text`= '<p>Hello Vendor,</p>\r\n                    <p>you got new order from [[firstname]] [[lastname]]</p>\r\n                    <p>Commission: [[vendor_commission]] </p>\r\n                    <p>Order Status: [[status]] </p>\r\n                    <p><strong>Commission for product_name :&nbsp;</strong>[[product_name]]</p>\r\n                    [[website_name]]<br />\r\n                            Support Team</p>\r\n                ',`admin_subject`= '',`client_subject`= '',`client_text`= '',`admin_text`= '',`shortcode`= 'vendor_firstname,vendor_lastname,vendor_commission,order_id,status,order_link,product_name,PhoneNumber,firstname,lastname,commission,total,currency_code,txn_id,website_name,website_logo,order_id';
INSERT INTO `mail_templates` SET `id`= '37',`unique_id`= 'vendor_form_status_1',`name`= 'Vendor Form Status Change To Approved',`subject`= 'Form Status Change To Approved',`text`= '<p>Dear, [[username]]</p>\r\n                                <p>Form Status Change to Approved</p>\r\n                                <p>Name [[title]]</p>\r\n                                <p><br />\r\n                            [[website_name]]<br />\r\n                            Support Team</p>\r\n                        ',`admin_subject`= '',`client_subject`= '',`client_text`= '',`admin_text`= '',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,title';
INSERT INTO `mail_templates` SET `id`= '38',`unique_id`= 'vendor_create_product',`name`= 'Vendor Create new product',`subject`= '',`text`= '',`admin_subject`= 'New Product Created By Vendor',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin,</p>\r\n                                <p>New Product has been created</p>\r\n                                <p>Name [[product_name]]</p>\r\n                                <p>Username [[username]]</p>\r\n                                <p><br />\r\n                            [[website_name]]<br />\r\n                            Support Team</p>\r\n                        ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,product_name,product_short_description,product_price,product_sku,product_id';
INSERT INTO `mail_templates` SET `id`= '39',`unique_id`= 'vendor_product_status_1',`name`= 'Vendor Product Status Change To Approved',`subject`= 'Product Status Change To Approved',`text`= '<p>Dear, [[username]]</p>\r\n                                <p>Product Status Change to Approved</p>\r\n                                <p>Name [[product_name]]</p>\r\n                                <p><br />\r\n                            [[website_name]]<br />\r\n                            Support Team</p>\r\n                        ',`admin_subject`= '',`client_subject`= '',`client_text`= '',`admin_text`= '',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,product_name,product_short_description,product_price,product_sku,product_id';
INSERT INTO `mail_templates` SET `id`= '40',`unique_id`= 'vendor_create_form',`name`= 'Vendor Create new product',`subject`= '',`text`= '',`admin_subject`= 'New Form Created By Vendor',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin,</p>\r\n                                <p>New Form has been created</p>\r\n                                <p>Name [[title]]</p>\r\n                                <p>Username [[username]]</p>\r\n                                <p><br />\r\n                            [[website_name]]<br />\r\n                            Support Team</p>\r\n                        ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,title';
INSERT INTO `mail_templates` SET `id`= '41',`unique_id`= 'vendor_form_status_0',`name`= 'Vendor Form Status Change To In Review',`subject`= 'Form Status Change To In Review',`text`= '<p>Dear,</p>\r\n                                <p>Form Status Change to In Review</p>\r\n                                <p>Name [[title]]</p>\r\n                                <p><br />\r\n                            [[website_name]]<br />\r\n                            Support Team</p>\r\n                        ',`admin_subject`= 'Form Status Change To In Review',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear,</p>\r\n                                <p>Form Status Change to In Review</p>\r\n                                <p>Name [[title]]</p>\r\n                                <p><br />\r\n                            [[website_name]]<br />\r\n                            Support Team</p>\r\n                        ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,title';
INSERT INTO `mail_templates` SET `id`= '42',`unique_id`= 'vendor_form_status_2',`name`= 'Vendor Form Status Change To Denied',`subject`= 'Form Status Change To Denied',`text`= '<p>Dear, [[username]]</p>\r\n                                <p>Form Status Change to Denied</p>\r\n                                <p>Name [[title]]</p>\r\n                                <p><br />\r\n                            [[website_name]]<br />\r\n                            Support Team</p>\r\n                        ',`admin_subject`= '',`client_subject`= '',`client_text`= '',`admin_text`= '',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,title';
INSERT INTO `mail_templates` SET `id`= '43',`unique_id`= 'vendor_order_status_complete',`name`= 'Vendor Order Status Has Been Change',`subject`= 'Vendor: New Order Commission From [[firstname]] [[lastname]]',`text`= '<p>Hello Vendor,</p>\r\n                    <p>you got new order Sale Commission from sale thats done under [[firstname]] [[lastname]]</p>\r\n                    <p>Commission: [[vendor_commission]] </p>\r\n                    <p><strong>Commission for product_name :&nbsp;</strong>[[product_name]]</p>\r\n                    [[website_name]]<br />\r\n                            Support Team</p>\r\n                ',`admin_subject`= '',`client_subject`= '',`client_text`= '',`admin_text`= '',`shortcode`= 'vendor_firstname,vendor_lastname,vendor_commission,order_id,status,order_link,product_name,commission_type,PhoneNumber,firstname,lastname,commission,total,currency_code,txn_id,website_name,website_logo,order_id';
INSERT INTO `mail_templates` SET `id`= '45',`unique_id`= 'vendor_create_program',`name`= 'Vendor Create new product',`subject`= '',`text`= '',`admin_subject`= 'New Program Created By Vendor : [[name]]',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin,</p>\r\n                    <p>New Program has been created</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p>Username [[username]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '46',`unique_id`= 'vendor_program_status_2',`name`= 'Vendor Program Status Change To Denied',`subject`= 'Program Status Change To Denied',`text`= '<p>Dear,</p>\r\n                    <p>Program Status Change to Denied</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= 'Program Status Change To Denied',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear,</p>\r\n                    <p>Program Status Change to Denied</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '47',`unique_id`= 'vendor_program_status_3',`name`= 'Vendor Program Status Change To Ask To Edit',`subject`= 'Program Status Change To Ask To Edit',`text`= '<p>Dear,</p>\r\n                    <p>Program Status Change to Ask To Edit</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= 'Program Status Change To Ask To Edit',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear,</p>\r\n                    <p>Program Status Change to Ask To Edit</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '48',`unique_id`= 'vendor_program_status_0',`name`= 'Vendor Program Status Change To In Review',`subject`= 'Program Status Change To In Review',`text`= '<p>Dear,</p>\r\n                    <p>Program Status Change to In Review</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= 'Program Status Change To In Review',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear,</p>\r\n                    <p>Program Status Change to In Review</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '49',`unique_id`= 'vendor_program_status_1',`name`= 'Vendor Program Status Change To Approved',`subject`= 'Program Status Change To Approved',`text`= '<p>Dear,</p>\r\n                    <p>Program Status Change to Approved</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= 'Program Status Change To Approved',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear,</p>\r\n                    <p>Program Status Change to Approved</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '51',`unique_id`= 'vendor_create_ads',`name`= 'Vendor Create Ads (Banner, Text, Link, Video)',`subject`= '',`text`= '',`admin_subject`= 'New Ads ([[type]]) Created By Vendor',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin,</p>\r\n                    <p>New Ads - [[type]] has been created</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p>Username [[username]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type';
INSERT INTO `mail_templates` SET `id`= '52',`unique_id`= 'vendor_ads_status_1',`name`= 'Vendor Ads (Banner, Text, Link, Video) Status Change To Approved',`subject`= 'Ads ([[type]]) Status Change To Approved',`text`= '<p>Dear</p>\r\n                    <p>Ads - [[type]] Status Change to Approved </p>\r\n                    <p>Name [[name]]</p>\r\n                    <p>Username [[username]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= 'Ads ([[type]]) Status Change To Approved',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear</p>\r\n                    <p>Ads - [[type]] Status Change to Approved </p>\r\n                    <p>Name [[name]]</p>\r\n                    <p>Username [[username]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type';
INSERT INTO `mail_templates` SET `id`= '53',`unique_id`= 'vendor_ads_status_0',`name`= 'Vendor Ads (Banner, Text, Link, Video) Status Change To In Review',`subject`= 'Ads ([[type]]) Status Change To In Review',`text`= '<p>Dear</p>\r\n                    <p>Ads - [[type]] Status Change to In Review </p>\r\n                    <p>Name [[name]]</p>\r\n                    <p>Username [[username]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= 'Ads ([[type]]) Status Change To In Review',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear</p>\r\n                    <p>Ads - [[type]] Status Change to In Review </p>\r\n                    <p>Name [[name]]</p>\r\n                    <p>Username [[username]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type';
INSERT INTO `mail_templates` SET `id`= '54',`unique_id`= 'vendor_ads_status_3',`name`= 'Vendor Ads (Banner, Text, Link, Video) Status Change To Ask To Edit',`subject`= 'Ads ([[type]]) Status Change To Ask To Edit',`text`= '<p>Dear</p>\r\n                    <p>Ads - [[type]] Status Change to Ask To Edit</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p>Username [[username]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= 'Ads ([[type]]) Status Change To Ask To Edit',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear</p>\r\n                    <p>Ads - [[type]] Status Change to Ask To Edit</p>\r\n                    <p>Name [[name]]</p>\r\n                    <p>Username [[username]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`shortcode`= 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type';
INSERT INTO `mail_templates` SET `id`= '55',`unique_id`= 'order_on_vendor_program',`name`= 'New Order in Vendor Program',`subject`= 'New Order Create In Your Program',`text`= '<p>Dear Vendor,</p>\r\n                    <p>New Order Created under your Program</p>\r\n                    <p><b>Website</b> : [[external_website_name]]</p>\r\n                    <p><b>Total</b> : [[total]]</p>\r\n                    <p><br />\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= '',`client_subject`= '',`client_text`= '',`admin_text`= '',`shortcode`= 'external_website_name,commission,username,website_name,website_logo,product_ids,total,currency,commission_type,script_name';
INSERT INTO `mail_templates` SET `id`= '57',`unique_id`= 'withdrwal_status_change',`name`= 'Withdrawal Request Status Changed',`subject`= 'Withdrawal Request Status Changed',`text`= '<p>Dear,</p>\r\n                <p>Your Withdrawal Request #([[request_id]]) Status has been change to <b><i>[[status]]</i></b></p>\r\n\r\n                    <p>Comment: [[comment]] </p>\r\n                [[website_name]]<br />\r\n                Support Team</p>\r\n            ',`admin_subject`= '',`client_subject`= NULL,`client_text`= NULL,`admin_text`= '',`shortcode`= 'comment,status,request_id,firstname,lastname,email,username,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '59',`unique_id`= 'send_register_mail_api',`name`= 'User Registration (API)',`subject`= 'Your Account Created Successfully On [[website_name]]',`text`= '<p>Welcome to [[website_name]]</p>\r\n\r\n<p>Dear [[firstname]],</p>\r\n\r\n<p>Thanks for signing up [[website_name]].</p>\r\n\r\n<p>Your&nbsp;Login&nbsp;credentials:</p>\r\n\r\n<p>Username:&nbsp;<strong>[[username]]</strong><br />\r\nPassword:&nbsp;<strong>*******</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><a href=\"[[website_url]]\">Login To [[website_name]]</a></p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= 'User Registration Successfully',`client_subject`= NULL,`client_text`= NULL,`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>New affiliate user Register on your site&nbsp;[[website_name]]</p>\r\n\r\n<p>Affiliate details:</p>\r\n\r\n<p>============</p>\r\n\r\n<p>[[firstname]]</p>\r\n\r\n<p>[[username]]</p>\r\n\r\n<p>[[email]]</p>\r\n\r\n<p><br />\r\n[[website_name]]<br />\r\nSupport Team</p>\r\n',`shortcode`= 'firstname,lastname,email,username,website_url,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '63',`unique_id`= 'subscription_status_change',`name`= 'Subscription Status Changed',`subject`= 'Subscription Status Changed',`text`= '<p>Dear [[firstname]],</p>\r\n                <p>Your subscription status has been changed to [[status_text]]</p>\r\n                <p>Comment: [[comment]] </p>\r\n                [[website_name]]<br />\r\n                Support Team</p>',`admin_subject`= '',`client_subject`= NULL,`client_text`= NULL,`admin_text`= '',`shortcode`= 'comment,planname,price,expire_at,started_at,status_text,firstname,lastname,email,username,website_url,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '64',`unique_id`= 'subscription_buy',`name`= 'Subscription Buy',`subject`= 'Subscription Buy',`text`= '<h2>Thanks for your order</h2>\r\n\r\n<p>Welcome to Prime. As a Prime member, enjoy these great benefits. If you have any questions, call us any time at or simply reply to this email.</p>\r\n',`admin_subject`= 'New Subscription Buy From [[firstname]] [[lastname]]',`client_subject`= NULL,`client_text`= NULL,`admin_text`= '<h2>Thanks for your order</h2>\r\n\r\n<p>Welcome to Prime. As a Prime member, enjoy these great benefits. If you have any questions, call us any time at or simply reply to this email.</p>\r\n',`shortcode`= 'planname,price,expire_at,started_at,firstname,lastname,email,username,website_url,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '65',`unique_id`= 'subscription_expire_notification',`name`= 'Subscription Expire Notification',`subject`= 'Your Subscription Will Be Expired Soon.',`text`= '<p>customText</p>\r\n',`admin_subject`= NULL,`client_subject`= NULL,`client_text`= NULL,`admin_text`= NULL,`shortcode`= 'planname,price,expire_at,started_at,firstname,lastname,email,username,website_url,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '66',`unique_id`= 'wallet_noti_on_hold_wallet',`name`= 'Wallet Status Change To On Hold',`subject`= '[[amount]] is put on hold in your wallet',`text`= '<p>Dear [[name]],</p>\n        <p>Transactions #[[id]] status changed to [[new_status]]. amount is [[amount]]</p>\n        <p><br />\n        [[website_name]]<br />\n        Support Team</p>\n',`admin_subject`= '',`client_subject`= NULL,`client_text`= NULL,`admin_text`= NULL,`shortcode`= 'amount,id,name,new_status,user_email,website_name,website_logo,name';
INSERT INTO `mail_templates` SET `id`= '67',`unique_id`= 'new_user_request',`name`= 'New User Request',`subject`= 'User Registration Successfull',`text`= '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>User account has been registered successfully on [[website_name]], please wait while system admin apporve&nbsp;your request.<br />\r\nWe will inform you once account has been approved, Thank You.</p>\r\n\r\n<p>Support Team<br />\r\n[[website_name]]</p>\r\n',`admin_subject`= 'New User Registration - Approval Pending',`client_subject`= NULL,`client_text`= NULL,`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>New user has been registered on [[website_name]], apporval is pending yet!</p>\r\n\r\n<p>User Details</p>\r\n\r\n<p>Name : [[firstname]][[lastname]]<br />\r\nEmail :&nbsp;[[email]]<br />\r\nUsername : [[username]]<br />\r\nSupport Team<br />\r\n[[website_name]]</p>',`shortcode`= 'firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '68',`unique_id`= 'new_user_approved',`name`= 'New User Request Approved',`subject`= 'User Account Approved',`text`= '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>Your new user account registration request is accepted by admin, you can login and use services.</p>\r\n\r\n<p>[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= 'User Account Approved',`client_subject`= NULL,`client_text`= NULL,`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>You have approced registration request of user having</p>\r\n\r\n<p>Name : [[firstname]]&nbsp;[[lastname]]</p>\r\n\r\n<p>Email : [[email]]</p>\r\n\r\n<p>Username : [[username]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Support Team</p>\r\n\r\n<p>[[website_name]]</p>\r\n',`shortcode`= 'firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '69',`unique_id`= 'new_user_declined',`name`= 'New User Request Declined',`subject`= 'User Account Declined',`text`= '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>Your new user account registration request is declined by admin, for more information please contact supprt team</p>\r\n\r\n<p>[[website_name]]<br />\r\nSupport Team</p>\r\n',`admin_subject`= 'User Account Declined',`client_subject`= NULL,`client_text`= NULL,`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>You have declined registration request of user having</p>\r\n\r\n<p>Name : [[firstname]]&nbsp;[[lastname]]</p>\r\n\r\n<p>Email : [[email]]</p>\r\n\r\n<p>Username : [[username]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Support Team</p>\r\n\r\n<p>[[website_name]]</p>\r\n',`shortcode`= 'firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '70',`unique_id`= 'new_vendor_deposit_request',`name`= 'New Vendor Deposit Request',`subject`= 'New Deposit Request Added',`text`= '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>Your deposit request of amount [[amount]] is added, if your balance not updated please contact support team</p>\r\n\r\n<p>[[website_name]]<br /> \r\n Support Team</p>',`admin_subject`= 'New Deposit Request Added',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>You have new deposit request of amount [[amount]] from vendor having</p>\r\n\r\n<p>Name : [[firstname]]&nbsp;[[lastname]]</p>\r\n\r\n<p>Email : [[email]]</p>\r\n\r\n<p>Username : [[username]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Support Team</p>\r\n\r\n<p>[[website_name]]</p>',`shortcode`= 'status,amount,firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '71',`unique_id`= 'vendor_deposit_request_updated',`name`= 'Deposit Request Updated',`subject`= 'Deposit Request Updated',`text`= '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>Your deposit request of amount [[amount]] is updated to [[status]], if have any queries please contact support team</p>\r\n\r\n<p>[[website_name]]<br /> \r\n Support Team</p>',`admin_subject`= 'Deposit Request Updated',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin,</p>\r\n\r\n<p>You have changed status of deposit request to [[status]] from vendor having</p>\r\n\r\n<p>Name : [[firstname]]&nbsp;[[lastname]]</p>\r\n\r\n<p>Email : [[email]]</p>\r\n\r\n<p>Username : [[username]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Support Team</p>\r\n\r\n<p>[[website_name]]</p>',`shortcode`= 'status,amount,firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '72',`unique_id`= 'user_level_changed',`name`= 'Change user level',`subject`= 'Your user level changed',`text`= '<p>Dear,</p><p>Your level changed from [[from_level]] to [[to_level]]</p>                     <p><br>                 [[website_name]]<br>                 Support Team</p>             ',`admin_subject`= '',`client_subject`= '',`client_text`= '',`admin_text`= '',`shortcode`= 'from_level,to_level,website_name';
INSERT INTO `mail_templates` SET `id`= '73',`unique_id`= 'ticket_created_email',`name`= 'Ticket Created Email',`subject`= 'New ticket #[[ticket_id]] has been created',`text`= '<p>Dear [[firstname]],&nbsp;</p><p><br></p><p>Your ticket has been created successfully on the system. Please note down below the ticket number for future reference.</p><p><br></p><p>Ticket ID:</p><p><span style=\"font-size: 1rem;\">[[ticket_id]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Ticket Status:</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_status]]</span><br></p><p><br></p><p><br></p><p>Subject :</p><p><span style=\"font-size: 1rem;\">[[ticket_subject]]</span><br></p><p><br></p><p><br></p><p>Message:</p><p><span style=\"font-size: 1rem;\">[[ticket_body]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">We will contact you very soon.</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Thank You</span><br></p><p><span style=\"font-size: 1rem;\">Support Team</span><br></p>',`admin_subject`= 'New user ticket #[[ticket_id]] has been created',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin, </p><p><br></p><p>The user has created a new ticket on your site [[website_name]]. <br></p><p><br></p><p>Username:</p><p><span style=\"font-size: 1rem;\">[[username]]</span><br></p><p><br></p><p>Email:</p><p><span style=\"font-size: 1rem;\">[[email]]</span><br></p><p><br></p><p>Name:</p><p><span style=\"font-size: 1rem;\">[[firstname]] [[lastname]]</span><br></p><p><br></p><p>Ticket ID:</p><p><span style=\"font-size: 1rem;\">[[ticket_id]]</span><br></p><p><br></p><p>Ticket Status:</p><p><span style=\"font-size: 1rem;\">[[ticket_status]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Subject :</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_subject]]</span><br></p><p><br></p><p><br></p><p>Message:</p><p><span style=\"font-size: 1rem;\">[[ticket_body]]</span><br></p><p><br></p><p><br></p><p>Thank You</p><p><span style=\"font-size: 1rem;\">[[website_name]]</span><br></p><p><br></p>',`shortcode`= 'ticket_id,ticket_status,ticket_subject,ticket_body,ticket_datetime,firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '74',`unique_id`= 'ticket_reply_email',`name`= 'Ticket Replied Email',`subject`= 'You have a new reply on ticket #[[ticket_id]]',`text`= '<p>Dear [[firstname]], </p><p><br></p><p>You have a reply from the support team on your ticket #[[ticket_id]]</p><p><br></p><p>Ticket ID:</p><p><span style=\"font-size: 1rem;\">[[ticket_id]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Ticket Status:</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_status]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Subject :</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_subject]]</span><br></p><p><br></p><p>Message from the support team<br></p><p><span style=\"font-size: 1rem;\">[[ticket_reply_message]]</span></p><p><span style=\"font-size: 1rem;\"><br></span></p><p><span style=\"font-size: 1rem;\">Time</span></p><p><span style=\"font-size: 1rem;\">[[reply_datetime]]</span></p><p><span style=\"font-size: 1rem;\"><br></span></p><p><span style=\"font-size: 1rem;\">Thank You</span><br></p>',`admin_subject`= 'User added a new reply on ticket #[[ticket_id]]',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin, </p><p><br></p><p>User added a new reply on ticket #[[ticket_id]]</p><p><br></p><p>Username:</p><p><span style=\"font-size: 1rem;\">[[username]]</span><br></p><p><br></p><p>Email:</p><p><span style=\"font-size: 1rem;\">[[email]]</span><br></p><p><br></p><p>Name:</p><p><span style=\"font-size: 1rem;\">[[firstname]] [[lastname]]</span></p><p><br></p><p>Ticket ID:</p><p><span style=\"font-size: 1rem;\">[[ticket_id]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Ticket Status:</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_status]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Subject :</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_subject]]</span><br></p><p><br></p><p>Message from user<br></p><p><span style=\"font-size: 1rem;\">[[ticket_reply_message]]</span></p><p><span style=\"font-size: 1rem;\"><br></span></p><p><span style=\"font-size: 1rem;\">Time</span></p><p><span style=\"font-size: 1rem;\">[[reply_datetime]]</span></p><p><span style=\"font-size: 1rem;\"><br></span></p><p><span style=\"font-size: 1rem;\">Thank You</span></p>',`shortcode`= 'ticket_id,ticket_status,ticket_subject,ticket_body,ticket_reply_message,reply_datetime,firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `mail_templates` SET `id`= '75',`unique_id`= 'ticket_status_email',`name`= 'Ticket Status Change Email',`subject`= 'Ticket #[[ticket_id]] status has been updated',`text`= '<p>Dear [[firstname]],&nbsp;</p><p><br></p><p>The status of a ticket having id [[ticket_id]] has been updated, please log in to [[website_name]] to see full details of the ticket.</p><p><br></p><p>Ticket ID:</p><p><span style=\"font-size: 1rem;\">[[ticket_id]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Ticket Status:</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_status]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Subject :</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_subject]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Thank You</span></p><p><span style=\"font-size: 1rem;\">Support Team<br></span><br></p>',`admin_subject`= 'Ticket #[[ticket_id]] status has been updated',`client_subject`= '',`client_text`= '',`admin_text`= '<p>Dear Admin,&nbsp;</p><p><br></p><p>The status of the ticket having id [[ticket_id]] has been updated.</p><p><br></p><p>Username:</p><p><span style=\"font-size: 1rem;\">[[username]]</span><br></p><p><br></p><p>Email:</p><p><span style=\"font-size: 1rem;\">[[email]]</span><br></p><p><br></p><p>Name:</p><p><span style=\"font-size: 1rem;\">[[firstname]] [[lastname]]</span></p><p><span style=\"font-size: 1rem;\"><br></span></p><p>Ticket ID:</p><p><span style=\"font-size: 1rem;\">[[ticket_id]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Ticket Status:</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_status]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Subject :</span><br></p><p><span style=\"font-size: 1rem;\">[[ticket_subject]]</span><br></p><p><br></p><p><span style=\"font-size: 1rem;\">Thank You</span></p><p><span style=\"font-size: 1rem;\">Support Team<br></span></p>',`shortcode`= 'ticket_id,ticket_status,ticket_subject,ticket_body,firstname,lastname,email,username,website_name,website_logo';
INSERT INTO `setting` SET `setting_id`= '1',`setting_key`= 'front_template',`setting_value`= 'custom_13',`setting_type`= 'login',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '2',`setting_key`= 'top_affiliate',`setting_value`= '1',`setting_type`= 'userdashboard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '3',`setting_key`= 'wallet_min_amount',`setting_value`= '200',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '4',`setting_key`= 'wallet_min_message',`setting_value`= '<p>The minimum limit is: 100</p>',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '5',`setting_key`= 'name',`setting_value`= 'Affiliate Script',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '6',`setting_key`= 'maintenance_mode',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '7',`setting_key`= 'store_maintenance_mode',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '8',`setting_key`= 'notify_email',`setting_value`= 'admin@gmail.com',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '9',`setting_key`= 'session_timeout',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '10',`setting_key`= 'footer',`setting_value`= 'Copyright © 2026 Affiliate Script @ Company Name',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '11',`setting_key`= 'time_zone',`setting_value`= 'Africa/Abidjan',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '12',`setting_key`= 'meta_description',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '13',`setting_key`= 'meta_keywords',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '14',`setting_key`= 'meta_author',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '15',`setting_key`= 'google_analytics',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '16',`setting_key`= 'faceboook_pixel',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '17',`setting_key`= 'fbmessager_script',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '18',`setting_key`= 'global_script',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '19',`setting_key`= 'global_script_status',`setting_value`= '[]',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '20',`setting_key`= 'mail_type',`setting_value`= 'php_mailer',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '21',`setting_key`= 'from_email',`setting_value`= 'admin@gmail.com',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '22',`setting_key`= 'from_name',`setting_value`= 'ADMIN SUPPORT',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '23',`setting_key`= 'smtp_hostname',`setting_value`= '',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '24',`setting_key`= 'smtp_username',`setting_value`= '',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '25',`setting_key`= 'smtp_password',`setting_value`= '',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '26',`setting_key`= 'smtp_port',`setting_value`= '',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '27',`setting_key`= 'smtp_crypto',`setting_value`= '',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '28',`setting_key`= 'registration_status',`setting_value`= '1',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '29',`setting_key`= 'registration_approval',`setting_value`= '0',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '30',`setting_key`= 'language_status',`setting_value`= '1',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '31',`setting_key`= 'affiliate_cookie',`setting_value`= '30',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '32',`setting_key`= 'default_action_status',`setting_value`= '0',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '33',`setting_key`= 'default_external_order_status',`setting_value`= '0',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '34',`setting_key`= 'heading',`setting_value`= 'Affiliate Script Terms',`setting_type`= 'tnc',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '35',`setting_key`= 'content',`setting_value`= '<p>Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms Affiliate Script Terms</p>',`setting_type`= 'tnc',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '36',`setting_key`= 'sitekey',`setting_value`= '',`setting_type`= 'googlerecaptcha',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '37',`setting_key`= 'secretkey',`setting_value`= '',`setting_type`= 'googlerecaptcha',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '38',`setting_key`= 'admin_login',`setting_value`= '0',`setting_type`= 'googlerecaptcha',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '39',`setting_key`= 'affiliate_login',`setting_value`= '0',`setting_type`= 'googlerecaptcha',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '40',`setting_key`= 'affiliate_register',`setting_value`= '0',`setting_type`= 'googlerecaptcha',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '41',`setting_key`= 'client_login',`setting_value`= '0',`setting_type`= 'googlerecaptcha',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '42',`setting_key`= 'client_register',`setting_value`= '0',`setting_type`= 'googlerecaptcha',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '43',`setting_key`= 'heading',`setting_value`= 'Affiliate Script Home Page',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '44',`setting_key`= 'content',`setting_value`= 'Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo Home content demo                                                           ',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '45',`setting_key`= 'about_content',`setting_value`= 'About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content DemoAbout Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo About Content Demo',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '46',`setting_key`= 'heading_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '47',`setting_key`= 'input_text_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '48',`setting_key`= 'input_bg_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '49',`setting_key`= 'input_label_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '50',`setting_key`= 'bg_left',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '51',`setting_key`= 'bg_right',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '52',`setting_key`= 'footer_bf',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '53',`setting_key`= 'footer_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '54',`setting_key`= 'btn_sendmail_bg',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '55',`setting_key`= 'btn_sendmail_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '56',`setting_key`= 'btn_backlogin_bg',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '57',`setting_key`= 'btn_backlogin_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '58',`setting_key`= 'btn_forgotlink_bg',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '59',`setting_key`= 'btn_forgotlink_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '60',`setting_key`= 'btn_signin_bg',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '61',`setting_key`= 'btn_signin_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '62',`setting_key`= 'btn_signup_bg',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '63',`setting_key`= 'btn_signup_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '64',`setting_key`= 'btn_registersubmit_bg',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '65',`setting_key`= 'btn_registersubmit_color',`setting_value`= '',`setting_type`= 'loginclient',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '66',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '67',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '68',`setting_key`= 'registration_builder',`setting_value`= '[{\"type\":\"header\",\"label\":\"Firstname\"},{\"type\":\"header\",\"label\":\"Lastname\"},{\"type\":\"header\",\"label\":\"Email\"},{\"type\":\"text\",\"label\":\"Mobile Phone\",\"placeholder\":\"Enter your mobile number\",\"className\":\"form-control\",\"name\":\"text-1621449816785\",\"mobile_validation\":\"true\"},{\"type\":\"header\",\"label\":\"Username\"},{\"type\":\"header\",\"label\":\"Password\"},{\"type\":\"header\",\"label\":\"Confirm_password\"}]',`setting_type`= 'registration_builder',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '69',`setting_key`= 'admin_sound_status',`setting_value`= '1',`setting_type`= 'live_dashboard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '70',`setting_key`= 'admin_action_status',`setting_value`= '1',`setting_type`= 'live_dashboard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '71',`setting_key`= 'admin_integration_order_status',`setting_value`= '1',`setting_type`= 'live_dashboard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '72',`setting_key`= 'admin_affiliate_register_status',`setting_value`= '1',`setting_type`= 'live_dashboard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '73',`setting_key`= 'admin_local_store_order_status',`setting_value`= '1',`setting_type`= 'live_dashboard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '74',`setting_key`= 'admin_data_load_interval',`setting_value`= '15',`setting_type`= 'live_dashboard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '75',`setting_key`= 'admin_integration_logs',`setting_value`= '1',`setting_type`= 'live_log',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '76',`setting_key`= 'admin_integration_orders',`setting_value`= '1',`setting_type`= 'live_log',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '77',`setting_key`= 'admin_newuser',`setting_value`= '1',`setting_type`= 'live_log',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '78',`setting_key`= 'levels',`setting_value`= '20',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '79',`setting_key`= 'sale_type',`setting_value`= 'percentage',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '80',`setting_key`= 'disabled_for',`setting_value`= '[]',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '81',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_1',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '82',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_1',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '83',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_1',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '84',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_1',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '85',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_2',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '86',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_2',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '87',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_2',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '88',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_2',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '89',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_3',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '90',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_3',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '91',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_3',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '92',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_3',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '93',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_4',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '94',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_4',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '95',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_4',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '96',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_4',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '97',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_5',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '98',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_5',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '99',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_5',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '100',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_5',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '101',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_6',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '102',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_6',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '103',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_6',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '104',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_6',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '105',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_7',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '106',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_7',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '107',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_7',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '108',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_7',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '109',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_8',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '110',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_8',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '111',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_8',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '112',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_8',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '113',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_9',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '114',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_9',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '115',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_9',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '116',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_9',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '117',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_10',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '118',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_10',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '119',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_10',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '120',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_10',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '121',`setting_key`= 'marketvendorstatus',`setting_value`= '1',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '122',`setting_key`= 'commission_type',`setting_value`= '',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '123',`setting_key`= 'commission_sale',`setting_value`= '',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '124',`setting_key`= 'sale_status',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '125',`setting_key`= 'click_allow',`setting_value`= 'single',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '126',`setting_key`= 'commission_number_of_click',`setting_value`= '',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '127',`setting_key`= 'commission_click_commission',`setting_value`= '',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '128',`setting_key`= 'click_status',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '129',`setting_key`= 'storestatus',`setting_value`= '1',`setting_type`= 'vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '130',`setting_key`= 'admin_click_count',`setting_value`= '',`setting_type`= 'vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '131',`setting_key`= 'admin_click_amount',`setting_value`= '0.00',`setting_type`= 'vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '132',`setting_key`= 'admin_sale_commission_type',`setting_value`= 'percentage',`setting_type`= 'vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '133',`setting_key`= 'admin_commission_value',`setting_value`= '',`setting_type`= 'vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '134',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'withdrawalpayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '135',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '136',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'withdrawalpayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '137',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '138',`setting_key`= 'ClientID',`setting_value`= '',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '139',`setting_key`= 'ClientSecret',`setting_value`= '',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '140',`setting_key`= 'denied_status_id',`setting_value`= '0',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '141',`setting_key`= 'pending_status_id',`setting_value`= '0',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '142',`setting_key`= 'processing_status_id',`setting_value`= '0',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '143',`setting_key`= 'success_status_id',`setting_value`= '0',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '144',`setting_key`= 'canceled_status_id',`setting_value`= '0',`setting_type`= 'withdrawalpayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '145',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'membershippayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '146',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '147',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '148',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'membershippayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '149',`setting_key`= 'bank_details',`setting_value`= '',`setting_type`= 'membershippayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '150',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '151',`setting_key`= 'api_username',`setting_value`= '',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '152',`setting_key`= 'api_password',`setting_value`= '',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '153',`setting_key`= 'api_signature',`setting_value`= '',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '154',`setting_key`= 'payment_currency',`setting_value`= 'USD',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '155',`setting_key`= 'denied_status_id',`setting_value`= '0',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '156',`setting_key`= 'pending_status_id',`setting_value`= '0',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '157',`setting_key`= 'processing_status_id',`setting_value`= '0',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '158',`setting_key`= 'success_status_id',`setting_value`= '0',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '159',`setting_key`= 'canceled_status_id',`setting_value`= '0',`setting_type`= 'membershippayment_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '160',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '161',`setting_key`= 'environment',`setting_value`= '0',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '162',`setting_key`= 'test_public_key',`setting_value`= '',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '163',`setting_key`= 'test_secret_key',`setting_value`= '',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '164',`setting_key`= 'live_public_key',`setting_value`= '',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '165',`setting_key`= 'live_secret_key',`setting_value`= '',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '166',`setting_key`= 'order_success_status',`setting_value`= '0',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '167',`setting_key`= 'order_failed_status',`setting_value`= '0',`setting_type`= 'membershippayment_stripe_payment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '168',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'storepayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '169',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'storepayment_cod',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '170',`setting_key`= 'shipping_in_limited',`setting_value`= '0',`setting_type`= 'shipping_setting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '171',`setting_key`= 'shipping_error_message',`setting_value`= 'Our store shipping service does not support your country!',`setting_type`= 'shipping_setting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '172',`setting_key`= 'cost',`setting_value`= '[]',`setting_type`= 'shipping_setting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '173',`setting_key`= 'name',`setting_value`= 'Affiliate Script Store',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '174',`setting_key`= 'menu_on_front',`setting_value`= '1',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '175',`setting_key`= 'menu_on_front_blank',`setting_value`= '1',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '176',`setting_key`= 'theme',`setting_value`= 'starter2026',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '177',`setting_key`= 'google_analytics',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '178',`setting_key`= 'footer',`setting_value`= 'Copyright © 2024 Affiliate Script Store @ Store Name',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '179',`setting_key`= 'contact_us_map',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '180',`setting_key`= 'address',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '181',`setting_key`= 'email',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '182',`setting_key`= 'contact_number',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '183',`setting_key`= 'is_variation_filter',`setting_value`= '1',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '184',`setting_key`= 'homepage_banner',`setting_value`= '{\"title\":\"\",\"content\":\"\",\"button_text\":\"\",\"button_link\":\"\"}',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '185',`setting_key`= 'homepage_bottom_section',`setting_value`= '{\"content\":\"\"}',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '186',`setting_key`= 'about_content',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '187',`setting_key`= 'contact_content',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '188',`setting_key`= 'policy_content',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '189',`setting_key`= 'homepage_slider',`setting_value`= '[]',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '190',`setting_key`= 'homepage_features',`setting_value`= '[]',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '191',`setting_key`= 'bs_cards',`setting_value`= '[]',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '192',`setting_key`= 'social_links',`setting_value`= '[]',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '193',`setting_key`= 'custom_page',`setting_value`= '[]',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '194',`setting_key`= 'per_task',`setting_value`= 'null',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '195',`setting_key`= 'footer_menu',`setting_value`= '[]',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '196',`setting_key`= 'recaptcha',`setting_value`= '',`setting_type`= 'formsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '197',`setting_key`= 'product_commission_type',`setting_value`= '',`setting_type`= 'formsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '198',`setting_key`= 'product_commission',`setting_value`= '',`setting_type`= 'formsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '199',`setting_key`= 'product_ppc',`setting_value`= '',`setting_type`= 'formsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '200',`setting_key`= 'product_noofpercommission',`setting_value`= '',`setting_type`= 'formsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '201',`setting_key`= 'form_recursion',`setting_value`= '',`setting_type`= 'formsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '202',`setting_key`= 'recursion_custom_time',`setting_value`= '0',`setting_type`= 'formsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '203',`setting_key`= 'recursion_endtime',`setting_value`= NULL,`setting_type`= 'formsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '204',`setting_key`= 'click_allow',`setting_value`= 'single',`setting_type`= 'productsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '205',`setting_key`= 'product_commission_type',`setting_value`= '',`setting_type`= 'productsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '206',`setting_key`= 'product_commission',`setting_value`= '',`setting_type`= 'productsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '207',`setting_key`= 'product_ppc',`setting_value`= '',`setting_type`= 'productsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '208',`setting_key`= 'product_noofpercommission',`setting_value`= '',`setting_type`= 'productsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '209',`setting_key`= 'product_recursion',`setting_value`= '',`setting_type`= 'productsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '210',`setting_key`= 'recursion_custom_time',`setting_value`= '0',`setting_type`= 'productsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '211',`setting_key`= 'recursion_endtime',`setting_value`= NULL,`setting_type`= 'productsetting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '212',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'order_comment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '213',`setting_key`= 'title',`setting_value`= '{\"1\":\"Add Important Comments\"}',`setting_type`= 'order_comment',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '214',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'membership',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '215',`setting_key`= 'custom_logo_size',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '216',`setting_key`= 'log_custom_height',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '217',`setting_key`= 'log_custom_width',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '218',`setting_key`= 'affiliate_tracking_place',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '219',`setting_key`= 'block_click_across_browser',`setting_value`= '1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '220',`setting_key`= 'hide_currency_from',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '221',`setting_key`= 'unsubscribed_page_title',`setting_value`= 'Welcome To Our Unsubscribed Page',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '222',`setting_key`= 'unsubscribed_page_message',`setting_value`= 'We are sorry you go but we respect your decision! You are now unsubscribed from our list.\r\nyou always can enable it back from your user profile page. Thank you',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '223',`setting_key`= 'vendor_min_deposit',`setting_value`= '500',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '225',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'depositpayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '1',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '226',`setting_key`= 'proof',`setting_value`= '0',`setting_type`= 'depositpayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '227',`setting_key`= 'bank_names',`setting_value`= '[\"Bank Transfer Details\"]',`setting_type`= 'depositpayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '228',`setting_key`= 'bank_details',`setting_value`= 'Bank Transfer Details\r\nBank Transfer Details\r\nBank Transfer Details\r\nBank Transfer Details',`setting_type`= 'depositpayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '229',`setting_key`= 'additional_bank_details',`setting_value`= '[]',`setting_type`= 'depositpayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '230',`setting_key`= 'autoacceptlocalstore',`setting_value`= '0',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '231',`setting_key`= 'autoacceptexternalstore',`setting_value`= '0',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '232',`setting_key`= 'autoacceptaction',`setting_value`= '0',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '233',`setting_key`= 'show_sponser',`setting_value`= '',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '234',`setting_key`= 'sponser_name',`setting_value`= 'System Admin',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '235',`setting_key`= 'reg_comission_type',`setting_value`= 'disabled',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '236',`setting_key`= 'reg_comission_custom_amt',`setting_value`= '0',`setting_type`= 'referlevel',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '237',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_1',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '238',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_2',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '239',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_3',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '240',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_4',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '241',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_5',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '242',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_6',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '243',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_7',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '244',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_8',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '245',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_9',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '246',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_10',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '247',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_11',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '248',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_11',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '249',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_11',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '250',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_11',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '251',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_11',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '252',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_12',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '253',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_12',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '254',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_12',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '255',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_12',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '256',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_12',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '257',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_13',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '258',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_13',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '259',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_13',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '260',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_13',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '261',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_13',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '262',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_14',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '263',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_14',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '264',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_14',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '265',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_14',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '266',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_14',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '267',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_15',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '268',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_15',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '269',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_15',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '270',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_15',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '271',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_15',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '272',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_16',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '273',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_16',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '274',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_16',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '275',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_16',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '276',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_16',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '277',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_17',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '278',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_17',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '279',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_17',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '280',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_17',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '281',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_17',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '282',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_18',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '283',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_18',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '284',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_18',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '285',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_18',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '286',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_18',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '287',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_19',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '288',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_19',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '289',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_19',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '290',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_19',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '291',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_19',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '292',`setting_key`= 'reg_commission',`setting_value`= '',`setting_type`= 'referlevel_20',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '293',`setting_key`= 'sale_commition',`setting_value`= '',`setting_type`= 'referlevel_20',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '294',`setting_key`= 'commition',`setting_value`= '',`setting_type`= 'referlevel_20',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '295',`setting_key`= 'ex_commition',`setting_value`= '',`setting_type`= 'referlevel_20',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '296',`setting_key`= 'ex_action_commition',`setting_value`= '',`setting_type`= 'referlevel_20',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '297',`setting_key`= 'tax_status',`setting_value`= '0',`setting_type`= 'tax_setting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '298',`setting_key`= 'common_tax_percentage',`setting_value`= '',`setting_type`= 'tax_setting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '299',`setting_key`= 'cost',`setting_value`= '[]',`setting_type`= 'tax_setting',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '300',`setting_key`= 'top_tags_limit',`setting_value`= '10',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '301',`setting_key`= 'notificationbefore',`setting_value`= '10',`setting_type`= 'membership',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '302',`setting_key`= 'default_plan_id',`setting_value`= '1',`setting_type`= 'membership',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '303',`setting_key`= 'depositstatus',`setting_value`= '1',`setting_type`= 'vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '304',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'award_level',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '305',`setting_key`= 'marketaddnewprogram',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '306',`setting_key`= 'marketaddnewcampaign',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '307',`setting_key`= 'marketaddnewstoreproduct',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '308',`setting_key`= 'marketvendorexternalordercampaign',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '309',`setting_key`= 'marketvendoractionscampaign',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '310',`setting_key`= 'marketvendorclickcampaign',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '311',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '312',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_cod',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '313',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_flutterwave',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '314',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '315',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_paypalstandard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '316',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_paystack',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '317',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_razorpay',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '318',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_yappy',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '319',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_skrill',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '320',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_stripe',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '321',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_xendit',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '322',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_yookassa',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '323',`setting_key`= 'user_session_timeout',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '324',`setting_key`= 'admin-side-logo',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '325',`setting_key`= 'front-side-themes-logo',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '326',`setting_key`= 'front_custom_logo_size',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '327',`setting_key`= 'front_log_custom_height',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '328',`setting_key`= 'front_log_custom_width',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '329',`setting_key`= 'favicon',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '330',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '1',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '331',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_cod',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '332',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_flutterwave',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '333',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '334',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_paypalstandard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '335',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_paystack',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '336',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_razorpay',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '337',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_skrill',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '338',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_stripe',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '339',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_xendit',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '340',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_yappy',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '341',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_yookassa',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '342',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_yookassa',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '343',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_yappy',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '344',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_yappy',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '345',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_yookassa',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '346',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_xendit',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '347',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_xendit',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '348',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_stripe',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '349',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_stripe',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '350',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_skrill',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '351',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_skrill',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '352',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_razorpay',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '353',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_razorpay',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '354',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_paystack',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '355',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_paystack',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '356',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_paypalstandard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '357',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_paypalstandard',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '358',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '359',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_paypal',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '360',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_flutterwave',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '361',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_flutterwave',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '362',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_cod',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '363',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_cod',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '364',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '1',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '365',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '1',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '366',`setting_key`= 'admin_side_bar_color',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '367',`setting_key`= 'admin_side_bar_scroll_color',`setting_value`= '#007bff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '368',`setting_key`= 'admin_side_bar_text_color',`setting_value`= '#686868',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '369',`setting_key`= 'admin_side_bar_text_hover_color',`setting_value`= '#007bff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '370',`setting_key`= 'admin_top_bar_color',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '371',`setting_key`= 'admin_footer_color',`setting_value`= '#f2f3f5',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '372',`setting_key`= 'admin_logo_color',`setting_value`= '#007bff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '373',`setting_key`= 'user_side_bar_color',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '374',`setting_key`= 'user_side_bar_text_color',`setting_value`= '#3f567a',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '375',`setting_key`= 'user_side_bar_clock_text_color',`setting_value`= '#085445',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '376',`setting_key`= 'user_side_bar_text_hover_color',`setting_value`= '#5ec394',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '377',`setting_key`= 'user_top_bar_color',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '378',`setting_key`= 'user_footer_color',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '380',`setting_key`= 'user_side_font',`setting_value`= 'sans-serif',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '381',`setting_key`= 'front_side_font',`setting_value`= 'sans-serif',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '382',`setting_key`= 'cart_store_side_font',`setting_value`= 'Jost',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '383',`setting_key`= 'sales_store_side_font',`setting_value`= 'PT Sans',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '384',`setting_key`= 'notification_sound',`setting_value`= 'notify2.mp3',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '385',`setting_key`= 'admin_url',`setting_value`= 'admin',`setting_type`= 'security',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '386',`setting_key`= 'front_url',`setting_value`= '',`setting_type`= 'security',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '387',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_store_toyyibpay',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '388',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_deposit_toyyibpay',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '389',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'payment_gateway_membership_toyyibpay',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '390',`setting_key`= 'withdrawal_proof',`setting_value`= '0',`setting_type`= 'withdrawalpayment_bank_transfer',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '391',`setting_key`= 'classified_banner_title',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '392',`setting_key`= 'classified_banner_subtitle',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '393',`setting_key`= 'store_mode',`setting_value`= 'cart',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '394',`setting_key`= 'admin_button_color',`setting_value`= '#3d5674',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '395',`setting_key`= 'admin_button_hover_color',`setting_value`= '#007bff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '396',`setting_key`= 'user_button_color',`setting_value`= '#0d6efd',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '397',`setting_key`= 'user_button_hover_color',`setting_value`= '#0b5ed7',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '398',`setting_key`= 'contact_us_page',`setting_value`= '1',`setting_type`= 'userdashboard',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '399',`setting_key`= 'tickets_page',`setting_value`= '1',`setting_type`= 'userdashboard',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '400',`setting_key`= 'invitation_link_id',`setting_value`= '1',`setting_type`= 'userdashboard',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '401',`setting_key`= 'admin_login_box_background_color',`setting_value`= '#7a90a8',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '402',`setting_key`= 'admin_login_background_option',`setting_value`= '0',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '403',`setting_key`= 'admin_login_background_color',`setting_value`= '#5e7590',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '404',`setting_key`= 'admin-login-background-image',`setting_value`= '',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '405',`setting_key`= 'show_popup',`setting_value`= 'enable',`setting_type`= 'welcome',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '406',`setting_key`= 'heading',`setting_value`= 'Popup Welcome Title',`setting_type`= 'welcome',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '407',`setting_key`= 'video_link',`setting_value`= '',`setting_type`= 'welcome',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '408',`setting_key`= 'content',`setting_value`= 'Popup Welcome Content\r\nPopup Welcome Content\r\nPopup Welcome Content',`setting_type`= 'welcome',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '409',`setting_key`= 'mail_send_option',`setting_value`= 'enable',`setting_type`= 'email',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '410',`setting_key`= 'tickets_filter_status',`setting_value`= '',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '411',`setting_key`= 'default_affiliate_plan_id',`setting_value`= '1',`setting_type`= 'membership',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '412',`setting_key`= 'default_vendor_plan_id',`setting_value`= '2',`setting_type`= 'membership',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '413',`setting_key`= 'wallet_auto_withdrawal',`setting_value`= '1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '414',`setting_key`= 'wallet_auto_withdrawal_days',`setting_value`= '45',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '415',`setting_key`= 'wallet_auto_withdrawal_limit',`setting_value`= '1000',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '416',`setting_key`= 'wallet_min_message_new',`setting_value`= 'The minimum limit is',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '417',`setting_key`= 'wallet_max_amount',`setting_value`= '500',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '418',`setting_key`= 'vendormlmmodule',`setting_value`= '1',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '419',`setting_key`= 'cookies_consent_mesag',`setting_value`= 'We use cookies to improve your browsing experience. By continuing to use our site, you agree to our use of cookies.',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '420',`setting_key`= 'logo',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '421',`setting_key`= 'store_custom_logo_size',`setting_value`= '0',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '422',`setting_key`= 'store_logo_custom_width',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '423',`setting_key`= 'store_logo_custom_height',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '424',`setting_key`= 'cartimage',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '425',`setting_key`= 'favicon',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '426',`setting_key`= 'hbanimage',`setting_value`= '',`setting_type`= 'store',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '427',`setting_key`= 'enable_shorten_numbers',`setting_value`= '1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '428',`setting_key`= 'cookies_menu',`setting_value`= '1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '429',`setting_key`= 'cookies_consent',`setting_value`= '1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '430',`setting_key`= 'markettools_status',`setting_value`= '1',`setting_type`= 'market_tools',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '431',`setting_key`= 'status',`setting_value`= '1',`setting_type`= 'market_tools',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '432',`setting_key`= 'marketvendorpanelmode',`setting_value`= '0',`setting_type`= 'market_vendor',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '433',`setting_key`= 'is_install',`setting_value`= '1',`setting_type`= 'payment_gateway_toyyibpay',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '434',`setting_key`= 'enable_localhost_protection',`setting_value`= '1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '435',`setting_key`= 'enable_action_control',`setting_value`= '1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '436',`setting_key`= 'enable_click_control',`setting_value`= '1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '437',`setting_key`= 'ai_helper_enabled',`setting_value`= '1',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '441',`setting_key`= 'admin_dropdown_text',`setting_value`= '#212529',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '442',`setting_key`= 'admin_dropdown_hover_bg',`setting_value`= '#e3f2fd',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '443',`setting_key`= 'admin_dropdown_hover_text',`setting_value`= '#1976d2',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '444',`setting_key`= 'admin_horizontal_dropdown_bg',`setting_value`= '#34495e',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '445',`setting_key`= 'admin_horizontal_dropdown_text',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '446',`setting_key`= 'admin_horizontal_dropdown_hover_bg',`setting_value`= '#e3f2fd',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '447',`setting_key`= 'admin_horizontal_dropdown_hover_text',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '452',`setting_key`= 'admin_topbar_bg',`setting_value`= '#34495e',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '453',`setting_key`= 'admin_topbar_text',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '454',`setting_key`= 'admin_dropdown_bg',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '455',`setting_key`= 'admin_menu_bg',`setting_value`= '#667eea',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '456',`setting_key`= 'admin_menu_text',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '457',`setting_key`= 'admin_menu_active',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '458',`setting_key`= 'admin_menu_hover',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '459',`setting_key`= 'admin_dropdown_scrollbar',`setting_value`= '#666666',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '460',`setting_key`= 'admin_footer_bg',`setting_value`= '#1a252f',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '461',`setting_key`= 'admin_footer_text',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '462',`setting_key`= 'admin_side_font',`setting_value`= 'PT Sans',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '463',`setting_key`= 'user_top_navbar_bg',`setting_value`= '#0d6efd',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '464',`setting_key`= 'user_top_navbar_text',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '465',`setting_key`= 'user_top_navbar_button_bg',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '466',`setting_key`= 'user_top_navbar_button_text',`setting_value`= '#212529',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '467',`setting_key`= 'user_horizontal_menu_bg',`setting_value`= '#212529',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '468',`setting_key`= 'user_horizontal_menu_text',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '469',`setting_key`= 'user_horizontal_menu_hover_bg',`setting_value`= '#0b5ed7',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '470',`setting_key`= 'user_horizontal_menu_hover_text',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '471',`setting_key`= 'user_dropdown_bg',`setting_value`= '#ffffff',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '472',`setting_key`= 'user_dropdown_text',`setting_value`= '#212529',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '473',`setting_key`= 'user_footer_bg',`setting_value`= '#f8f9fa',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '474',`setting_key`= 'user_footer_text',`setting_value`= '#6c757d',`setting_type`= 'theme',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '475',`setting_key`= 'messenger_button_position',`setting_value`= 'bottom-right',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '476',`setting_key`= 'messenger_icon_style',`setting_value`= 'icon1',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '477',`setting_key`= 'telegram_enable',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '478',`setting_key`= 'frequency_unit_clicks',`setting_value`= 'minutes',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '479',`setting_key`= 'frequency_unit_actions',`setting_value`= 'minutes',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '480',`setting_key`= 'fbmessager_status',`setting_value`= '[]',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '481',`setting_key`= 'telegram_event_user_register',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '482',`setting_key`= 'telegram_event_new_external_order',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '483',`setting_key`= 'telegram_event_new_store_order',`setting_value`= '0',`setting_type`= 'site',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '484',`setting_key`= 'otp_admin_max_attempts',`setting_value`= '3',`setting_type`= 'security',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '485',`setting_key`= 'otp_admin_cooldown_seconds',`setting_value`= '180',`setting_type`= 'security',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '486',`setting_key`= 'ai_provider',`setting_value`= 'openai',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '487',`setting_key`= 'openai_model',`setting_value`= 'gpt-3.5-turbo',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '488',`setting_key`= 'claude_model',`setting_value`= 'claude-3-haiku-20240307',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '489',`setting_key`= 'gemini_model',`setting_value`= 'gemini-pro',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '490',`setting_key`= 'daily_limit_per_user',`setting_value`= '50',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '491',`setting_key`= 'monthly_limit_per_user',`setting_value`= '1000',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '492',`setting_key`= 'cost_per_request',`setting_value`= '0.02',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '493',`setting_key`= 'basic_plan_daily_limit',`setting_value`= '10',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '494',`setting_key`= 'premium_plan_daily_limit',`setting_value`= '100',`setting_type`= 'ai_helper',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `setting` SET `setting_id`= '495',`setting_key`= 'version',`setting_value`= 'v2',`setting_type`= 'googlerecaptcha',`setting_status`= '1',`setting_ipaddress`= '::1',`setting_is_default`= '0',`language_id`= '1';
INSERT INTO `states` SET `id`= '3',`name`= 'Arunachal Pradesh',`country_id`= '101',`created_by`= '1';
INSERT INTO `states` SET `id`= '5',`name`= 'Bihar',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '6',`name`= 'Chandigarh',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '7',`name`= 'Chhattisgarh',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '8',`name`= 'Dadra and Nagar Haveli',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '9',`name`= 'Daman and Diu',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '10',`name`= 'Delhi',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '11',`name`= 'Goa',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '12',`name`= 'Gujarat',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '13',`name`= 'Haryana',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '14',`name`= 'Himachal Pradesh',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '15',`name`= 'Jammu and Kashmir',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '16',`name`= 'Jharkhand',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '17',`name`= 'Karnataka',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '18',`name`= 'Kenmore',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '19',`name`= 'Kerala',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '20',`name`= 'Lakshadweep',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '21',`name`= 'Madhya Pradesh',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '22',`name`= 'Maharashtra',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '23',`name`= 'Manipur',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '24',`name`= 'Meghalaya',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '25',`name`= 'Mizoram',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '26',`name`= 'Nagaland',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '27',`name`= 'Narora',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '28',`name`= 'Natwar',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '29',`name`= 'Odisha',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '30',`name`= 'Paschim Medinipur',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '31',`name`= 'Pondicherry',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '32',`name`= 'Punjab',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '33',`name`= 'Rajasthan',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '34',`name`= 'Sikkim',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '35',`name`= 'Tamil Nadu',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '36',`name`= 'Telangana',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '37',`name`= 'Tripura',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '38',`name`= 'Uttar Pradesh',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '39',`name`= 'Uttarakhand',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '40',`name`= 'Vaishali',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '41',`name`= 'West Bengal',`country_id`= '101',`created_by`= NULL;
INSERT INTO `states` SET `id`= '110',`name`= '\'Ayn Daflah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '111',`name`= '\'Ayn Tamushanat',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '112',`name`= 'Adrar',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '113',`name`= 'Algiers',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '114',`name`= 'Annabah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '115',`name`= 'Bashshar',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '116',`name`= 'Batnah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '117',`name`= 'Bijayah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '118',`name`= 'Biskrah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '119',`name`= 'Blidah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '120',`name`= 'Buirah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '121',`name`= 'Bumardas',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '122',`name`= 'Burj Bu Arririj',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '123',`name`= 'Ghalizan',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '124',`name`= 'Ghardayah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '125',`name`= 'Ilizi',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '126',`name`= 'Jijili',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '127',`name`= 'Jilfah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '128',`name`= 'Khanshalah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '129',`name`= 'Masilah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '130',`name`= 'Midyah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '131',`name`= 'Milah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '132',`name`= 'Muaskar',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '133',`name`= 'Mustaghanam',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '134',`name`= 'Naama',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '135',`name`= 'Oran',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '136',`name`= 'Ouargla',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '137',`name`= 'Qalmah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '138',`name`= 'Qustantinah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '139',`name`= 'Sakikdah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '140',`name`= 'Satif',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '141',`name`= 'Sayda\'',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '142',`name`= 'Sidi ban-al-\'Abbas',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '143',`name`= 'Suq Ahras',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '144',`name`= 'Tamanghasat',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '145',`name`= 'Tibazah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '146',`name`= 'Tibissah',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '147',`name`= 'Tilimsan',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '148',`name`= 'Tinduf',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '149',`name`= 'Tisamsilt',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '150',`name`= 'Tiyarat',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '151',`name`= 'Tizi Wazu',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '152',`name`= 'Umm-al-Bawaghi',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '153',`name`= 'Wahran',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '154',`name`= 'Warqla',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '155',`name`= 'Wilaya d Alger',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '156',`name`= 'Wilaya de Bejaia',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '157',`name`= 'Wilaya de Constantine',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '158',`name`= 'al-Aghwat',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '159',`name`= 'al-Bayadh',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '160',`name`= 'al-Jaza\'ir',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '161',`name`= 'al-Wad',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '162',`name`= 'ash-Shalif',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '163',`name`= 'at-Tarif',`country_id`= '3',`created_by`= NULL;
INSERT INTO `states` SET `id`= '164',`name`= 'Eastern',`country_id`= '4',`created_by`= NULL;
INSERT INTO `states` SET `id`= '165',`name`= 'Manu\'a',`country_id`= '4',`created_by`= NULL;
INSERT INTO `states` SET `id`= '166',`name`= 'Swains Island',`country_id`= '4',`created_by`= NULL;
INSERT INTO `states` SET `id`= '167',`name`= 'Western',`country_id`= '4',`created_by`= NULL;
INSERT INTO `states` SET `id`= '168',`name`= 'Andorra la Vella',`country_id`= '5',`created_by`= NULL;
INSERT INTO `states` SET `id`= '169',`name`= 'Canillo',`country_id`= '5',`created_by`= NULL;
INSERT INTO `states` SET `id`= '170',`name`= 'Encamp',`country_id`= '5',`created_by`= NULL;
INSERT INTO `states` SET `id`= '171',`name`= 'La Massana',`country_id`= '5',`created_by`= NULL;
INSERT INTO `states` SET `id`= '172',`name`= 'Les Escaldes',`country_id`= '5',`created_by`= NULL;
INSERT INTO `states` SET `id`= '173',`name`= 'Ordino',`country_id`= '5',`created_by`= NULL;
INSERT INTO `states` SET `id`= '174',`name`= 'Sant Julia de Loria',`country_id`= '5',`created_by`= NULL;
INSERT INTO `states` SET `id`= '175',`name`= 'Bengo',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '176',`name`= 'Benguela',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '177',`name`= 'Bie',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '178',`name`= 'Cabinda',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '179',`name`= 'Cunene',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '180',`name`= 'Huambo',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '181',`name`= 'Huila',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '182',`name`= 'Kuando-Kubango',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '183',`name`= 'Kwanza Norte',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '184',`name`= 'Kwanza Sul',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '185',`name`= 'Luanda',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '186',`name`= 'Lunda Norte',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '187',`name`= 'Lunda Sul',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '188',`name`= 'Malanje',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '189',`name`= 'Moxico',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '190',`name`= 'Namibe',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '191',`name`= 'Uige',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '192',`name`= 'Zaire',`country_id`= '6',`created_by`= NULL;
INSERT INTO `states` SET `id`= '193',`name`= 'Other Provinces',`country_id`= '7',`created_by`= NULL;
INSERT INTO `states` SET `id`= '194',`name`= 'Sector claimed by Argentina/Ch',`country_id`= '8',`created_by`= NULL;
INSERT INTO `states` SET `id`= '195',`name`= 'Sector claimed by Argentina/UK',`country_id`= '8',`created_by`= NULL;
INSERT INTO `states` SET `id`= '196',`name`= 'Sector claimed by Australia',`country_id`= '8',`created_by`= NULL;
INSERT INTO `states` SET `id`= '197',`name`= 'Sector claimed by France',`country_id`= '8',`created_by`= NULL;
INSERT INTO `states` SET `id`= '198',`name`= 'Sector claimed by New Zealand',`country_id`= '8',`created_by`= NULL;
INSERT INTO `states` SET `id`= '199',`name`= 'Sector claimed by Norway',`country_id`= '8',`created_by`= NULL;
INSERT INTO `states` SET `id`= '200',`name`= 'Unclaimed Sector',`country_id`= '8',`created_by`= NULL;
INSERT INTO `states` SET `id`= '201',`name`= 'Barbuda',`country_id`= '9',`created_by`= NULL;
INSERT INTO `states` SET `id`= '202',`name`= 'Saint George',`country_id`= '9',`created_by`= NULL;
INSERT INTO `states` SET `id`= '203',`name`= 'Saint John',`country_id`= '9',`created_by`= NULL;
INSERT INTO `states` SET `id`= '204',`name`= 'Saint Mary',`country_id`= '9',`created_by`= NULL;
INSERT INTO `states` SET `id`= '205',`name`= 'Saint Paul',`country_id`= '9',`created_by`= NULL;
INSERT INTO `states` SET `id`= '206',`name`= 'Saint Peter',`country_id`= '9',`created_by`= NULL;
INSERT INTO `states` SET `id`= '207',`name`= 'Saint Philip',`country_id`= '9',`created_by`= NULL;
INSERT INTO `states` SET `id`= '208',`name`= 'Buenos Aires',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '209',`name`= 'Catamarca',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '210',`name`= 'Chaco',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '211',`name`= 'Chubut',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '212',`name`= 'Cordoba',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '213',`name`= 'Corrientes',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '214',`name`= 'Distrito Federal',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '215',`name`= 'Entre Rios',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '216',`name`= 'Formosa',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '217',`name`= 'Jujuy',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '218',`name`= 'La Pampa',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '219',`name`= 'La Rioja',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '220',`name`= 'Mendoza',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '221',`name`= 'Misiones',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '222',`name`= 'Neuquen',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '223',`name`= 'Rio Negro',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '224',`name`= 'Salta',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '225',`name`= 'San Juan',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '226',`name`= 'San Luis',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '227',`name`= 'Santa Cruz',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '228',`name`= 'Santa Fe',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '229',`name`= 'Santiago del Estero',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '230',`name`= 'Tierra del Fuego',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '231',`name`= 'Tucuman',`country_id`= '10',`created_by`= NULL;
INSERT INTO `states` SET `id`= '232',`name`= 'Aragatsotn',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '233',`name`= 'Ararat',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '234',`name`= 'Armavir',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '235',`name`= 'Gegharkunik',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '236',`name`= 'Kotaik',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '237',`name`= 'Lori',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '238',`name`= 'Shirak',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '239',`name`= 'Stepanakert',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '240',`name`= 'Syunik',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '241',`name`= 'Tavush',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '242',`name`= 'Vayots Dzor',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '243',`name`= 'Yerevan',`country_id`= '11',`created_by`= NULL;
INSERT INTO `states` SET `id`= '244',`name`= 'Aruba',`country_id`= '12',`created_by`= NULL;
INSERT INTO `states` SET `id`= '245',`name`= 'Auckland',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '246',`name`= 'Australian Capital Territory',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '247',`name`= 'Balgowlah',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '248',`name`= 'Balmain',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '249',`name`= 'Bankstown',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '250',`name`= 'Baulkham Hills',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '251',`name`= 'Bonnet Bay',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '252',`name`= 'Camberwell',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '253',`name`= 'Carole Park',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '254',`name`= 'Castle Hill',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '255',`name`= 'Caulfield',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '256',`name`= 'Chatswood',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '257',`name`= 'Cheltenham',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '258',`name`= 'Cherrybrook',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '259',`name`= 'Clayton',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '260',`name`= 'Collingwood',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '261',`name`= 'Frenchs Forest',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '262',`name`= 'Hawthorn',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '263',`name`= 'Jannnali',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '264',`name`= 'Knoxfield',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '265',`name`= 'Melbourne',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '266',`name`= 'New South Wales',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '267',`name`= 'Northern Territory',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '268',`name`= 'Perth',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '269',`name`= 'Queensland',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '270',`name`= 'South Australia',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '271',`name`= 'Tasmania',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '272',`name`= 'Templestowe',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '273',`name`= 'Victoria',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '274',`name`= 'Werribee south',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '275',`name`= 'Western Australia',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '276',`name`= 'Wheeler',`country_id`= '13',`created_by`= NULL;
INSERT INTO `states` SET `id`= '277',`name`= 'Bundesland Salzburg',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '278',`name`= 'Bundesland Steiermark',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '279',`name`= 'Bundesland Tirol',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '280',`name`= 'Burgenland',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '281',`name`= 'Carinthia',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '282',`name`= 'Karnten',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '283',`name`= 'Liezen',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '284',`name`= 'Lower Austria',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '285',`name`= 'Niederosterreich',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '286',`name`= 'Oberosterreich',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '287',`name`= 'Salzburg',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '288',`name`= 'Schleswig-Holstein',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '289',`name`= 'Steiermark',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '290',`name`= 'Styria',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '291',`name`= 'Tirol',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '292',`name`= 'Upper Austria',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '293',`name`= 'Vorarlberg',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '294',`name`= 'Wien',`country_id`= '14',`created_by`= NULL;
INSERT INTO `states` SET `id`= '295',`name`= 'Abseron',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '296',`name`= 'Baki Sahari',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '297',`name`= 'Ganca',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '298',`name`= 'Ganja',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '299',`name`= 'Kalbacar',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '300',`name`= 'Lankaran',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '301',`name`= 'Mil-Qarabax',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '302',`name`= 'Mugan-Salyan',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '303',`name`= 'Nagorni-Qarabax',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '304',`name`= 'Naxcivan',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '305',`name`= 'Priaraks',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '306',`name`= 'Qazax',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '307',`name`= 'Saki',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '308',`name`= 'Sirvan',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '309',`name`= 'Xacmaz',`country_id`= '15',`created_by`= NULL;
INSERT INTO `states` SET `id`= '310',`name`= 'Abaco',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '311',`name`= 'Acklins Island',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '312',`name`= 'Andros',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '313',`name`= 'Berry Islands',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '314',`name`= 'Biminis',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '315',`name`= 'Cat Island',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '316',`name`= 'Crooked Island',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '317',`name`= 'Eleuthera',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '318',`name`= 'Exuma and Cays',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '319',`name`= 'Grand Bahama',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '320',`name`= 'Inagua Islands',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '321',`name`= 'Long Island',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '322',`name`= 'Mayaguana',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '323',`name`= 'New Providence',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '324',`name`= 'Ragged Island',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '325',`name`= 'Rum Cay',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '326',`name`= 'San Salvador',`country_id`= '16',`created_by`= NULL;
INSERT INTO `states` SET `id`= '327',`name`= '\'Isa',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '328',`name`= 'Badiyah',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '329',`name`= 'Hidd',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '330',`name`= 'Jidd Hafs',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '331',`name`= 'Mahama',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '332',`name`= 'Manama',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '333',`name`= 'Sitrah',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '334',`name`= 'al-Manamah',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '335',`name`= 'al-Muharraq',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '336',`name`= 'ar-Rifa\'a',`country_id`= '17',`created_by`= NULL;
INSERT INTO `states` SET `id`= '337',`name`= 'Bagar Hat',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '338',`name`= 'Bandarban',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '339',`name`= 'Barguna',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '340',`name`= 'Barisal',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '341',`name`= 'Bhola',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '342',`name`= 'Bogora',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '343',`name`= 'Brahman Bariya',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '344',`name`= 'Chandpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '345',`name`= 'Chattagam',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '346',`name`= 'Chittagong Division',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '347',`name`= 'Chuadanga',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '348',`name`= 'Dhaka',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '349',`name`= 'Dinajpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '350',`name`= 'Faridpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '351',`name`= 'Feni',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '352',`name`= 'Gaybanda',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '353',`name`= 'Gazipur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '354',`name`= 'Gopalganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '355',`name`= 'Habiganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '356',`name`= 'Jaipur Hat',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '357',`name`= 'Jamalpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '358',`name`= 'Jessor',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '359',`name`= 'Jhalakati',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '360',`name`= 'Jhanaydah',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '361',`name`= 'Khagrachhari',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '362',`name`= 'Khulna',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '363',`name`= 'Kishorganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '364',`name`= 'Koks Bazar',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '365',`name`= 'Komilla',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '366',`name`= 'Kurigram',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '367',`name`= 'Kushtiya',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '368',`name`= 'Lakshmipur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '369',`name`= 'Lalmanir Hat',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '370',`name`= 'Madaripur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '371',`name`= 'Magura',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '372',`name`= 'Maimansingh',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '373',`name`= 'Manikganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '374',`name`= 'Maulvi Bazar',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '375',`name`= 'Meherpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '376',`name`= 'Munshiganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '377',`name`= 'Naral',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '378',`name`= 'Narayanganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '379',`name`= 'Narsingdi',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '380',`name`= 'Nator',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '381',`name`= 'Naugaon',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '382',`name`= 'Nawabganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '383',`name`= 'Netrakona',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '384',`name`= 'Nilphamari',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '385',`name`= 'Noakhali',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '386',`name`= 'Pabna',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '387',`name`= 'Panchagarh',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '388',`name`= 'Patuakhali',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '389',`name`= 'Pirojpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '390',`name`= 'Rajbari',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '391',`name`= 'Rajshahi',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '392',`name`= 'Rangamati',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '393',`name`= 'Rangpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '394',`name`= 'Satkhira',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '395',`name`= 'Shariatpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '396',`name`= 'Sherpur',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '397',`name`= 'Silhat',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '398',`name`= 'Sirajganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '399',`name`= 'Sunamganj',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '400',`name`= 'Tangayal',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '401',`name`= 'Thakurgaon',`country_id`= '18',`created_by`= NULL;
INSERT INTO `states` SET `id`= '402',`name`= 'Christ Church',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '403',`name`= 'Saint Andrew',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '404',`name`= 'Saint George',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '405',`name`= 'Saint James',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '406',`name`= 'Saint John',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '407',`name`= 'Saint Joseph',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '408',`name`= 'Saint Lucy',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '409',`name`= 'Saint Michael',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '410',`name`= 'Saint Peter',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '411',`name`= 'Saint Philip',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '412',`name`= 'Saint Thomas',`country_id`= '19',`created_by`= NULL;
INSERT INTO `states` SET `id`= '413',`name`= 'Brest',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '414',`name`= 'Homjel\'',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '415',`name`= 'Hrodna',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '416',`name`= 'Mahiljow',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '417',`name`= 'Mahilyowskaya Voblasts',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '418',`name`= 'Minsk',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '419',`name`= 'Minskaja Voblasts\'',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '420',`name`= 'Petrik',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '421',`name`= 'Vicebsk',`country_id`= '20',`created_by`= NULL;
INSERT INTO `states` SET `id`= '422',`name`= 'Antwerpen',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '423',`name`= 'Berchem',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '424',`name`= 'Brabant',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '425',`name`= 'Brabant Wallon',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '426',`name`= 'Brussel',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '427',`name`= 'East Flanders',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '428',`name`= 'Hainaut',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '429',`name`= 'Liege',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '430',`name`= 'Limburg',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '431',`name`= 'Luxembourg',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '432',`name`= 'Namur',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '433',`name`= 'Ontario',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '434',`name`= 'Oost-Vlaanderen',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '435',`name`= 'Provincie Brabant',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '436',`name`= 'Vlaams-Brabant',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '437',`name`= 'Wallonne',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '438',`name`= 'West-Vlaanderen',`country_id`= '21',`created_by`= NULL;
INSERT INTO `states` SET `id`= '439',`name`= 'Belize',`country_id`= '22',`created_by`= NULL;
INSERT INTO `states` SET `id`= '440',`name`= 'Cayo',`country_id`= '22',`created_by`= NULL;
INSERT INTO `states` SET `id`= '441',`name`= 'Corozal',`country_id`= '22',`created_by`= NULL;
INSERT INTO `states` SET `id`= '442',`name`= 'Orange Walk',`country_id`= '22',`created_by`= NULL;
INSERT INTO `states` SET `id`= '443',`name`= 'Stann Creek',`country_id`= '22',`created_by`= NULL;
INSERT INTO `states` SET `id`= '444',`name`= 'Toledo',`country_id`= '22',`created_by`= NULL;
INSERT INTO `states` SET `id`= '445',`name`= 'Alibori',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '446',`name`= 'Atacora',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '447',`name`= 'Atlantique',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '448',`name`= 'Borgou',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '449',`name`= 'Collines',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '450',`name`= 'Couffo',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '451',`name`= 'Donga',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '452',`name`= 'Littoral',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '453',`name`= 'Mono',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '454',`name`= 'Oueme',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '455',`name`= 'Plateau',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '456',`name`= 'Zou',`country_id`= '23',`created_by`= NULL;
INSERT INTO `states` SET `id`= '457',`name`= 'Hamilton',`country_id`= '24',`created_by`= NULL;
INSERT INTO `states` SET `id`= '458',`name`= 'Saint George',`country_id`= '24',`created_by`= NULL;
INSERT INTO `states` SET `id`= '459',`name`= 'Bumthang',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '460',`name`= 'Chhukha',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '461',`name`= 'Chirang',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '462',`name`= 'Daga',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '463',`name`= 'Geylegphug',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '464',`name`= 'Ha',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '465',`name`= 'Lhuntshi',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '466',`name`= 'Mongar',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '467',`name`= 'Pemagatsel',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '468',`name`= 'Punakha',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '469',`name`= 'Rinpung',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '470',`name`= 'Samchi',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '471',`name`= 'Samdrup Jongkhar',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '472',`name`= 'Shemgang',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '473',`name`= 'Tashigang',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '474',`name`= 'Timphu',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '475',`name`= 'Tongsa',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '476',`name`= 'Wangdiphodrang',`country_id`= '25',`created_by`= NULL;
INSERT INTO `states` SET `id`= '477',`name`= 'Beni',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '478',`name`= 'Chuquisaca',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '479',`name`= 'Cochabamba',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '480',`name`= 'La Paz',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '481',`name`= 'Oruro',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '482',`name`= 'Pando',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '483',`name`= 'Potosi',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '484',`name`= 'Santa Cruz',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '485',`name`= 'Tarija',`country_id`= '26',`created_by`= NULL;
INSERT INTO `states` SET `id`= '486',`name`= 'Federacija Bosna i Hercegovina',`country_id`= '27',`created_by`= NULL;
INSERT INTO `states` SET `id`= '487',`name`= 'Republika Srpska',`country_id`= '27',`created_by`= NULL;
INSERT INTO `states` SET `id`= '488',`name`= 'Central Bobonong',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '489',`name`= 'Central Boteti',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '490',`name`= 'Central Mahalapye',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '491',`name`= 'Central Serowe-Palapye',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '492',`name`= 'Central Tutume',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '493',`name`= 'Chobe',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '494',`name`= 'Francistown',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '495',`name`= 'Gaborone',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '496',`name`= 'Ghanzi',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '497',`name`= 'Jwaneng',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '498',`name`= 'Kgalagadi North',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '499',`name`= 'Kgalagadi South',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '500',`name`= 'Kgatleng',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '501',`name`= 'Kweneng',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '502',`name`= 'Lobatse',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '503',`name`= 'Ngamiland',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '504',`name`= 'Ngwaketse',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '505',`name`= 'North East',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '506',`name`= 'Okavango',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '507',`name`= 'Orapa',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '508',`name`= 'Selibe Phikwe',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '509',`name`= 'South East',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '510',`name`= 'Sowa',`country_id`= '28',`created_by`= NULL;
INSERT INTO `states` SET `id`= '511',`name`= 'Bouvet Island',`country_id`= '29',`created_by`= NULL;
INSERT INTO `states` SET `id`= '512',`name`= 'Acre',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '513',`name`= 'Alagoas',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '514',`name`= 'Amapa',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '515',`name`= 'Amazonas',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '516',`name`= 'Bahia',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '517',`name`= 'Ceara',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '518',`name`= 'Distrito Federal',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '519',`name`= 'Espirito Santo',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '520',`name`= 'Estado de Sao Paulo',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '521',`name`= 'Goias',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '522',`name`= 'Maranhao',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '523',`name`= 'Mato Grosso',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '524',`name`= 'Mato Grosso do Sul',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '525',`name`= 'Minas Gerais',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '526',`name`= 'Para',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '527',`name`= 'Paraiba',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '528',`name`= 'Parana',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '529',`name`= 'Pernambuco',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '530',`name`= 'Piaui',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '531',`name`= 'Rio Grande do Norte',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '532',`name`= 'Rio Grande do Sul',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '533',`name`= 'Rio de Janeiro',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '534',`name`= 'Rondonia',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '535',`name`= 'Roraima',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '536',`name`= 'Santa Catarina',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '537',`name`= 'Sao Paulo',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '538',`name`= 'Sergipe',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '539',`name`= 'Tocantins',`country_id`= '30',`created_by`= NULL;
INSERT INTO `states` SET `id`= '540',`name`= 'British Indian Ocean Territory',`country_id`= '31',`created_by`= NULL;
INSERT INTO `states` SET `id`= '541',`name`= 'Belait',`country_id`= '32',`created_by`= NULL;
INSERT INTO `states` SET `id`= '542',`name`= 'Brunei-Muara',`country_id`= '32',`created_by`= NULL;
INSERT INTO `states` SET `id`= '543',`name`= 'Temburong',`country_id`= '32',`created_by`= NULL;
INSERT INTO `states` SET `id`= '544',`name`= 'Tutong',`country_id`= '32',`created_by`= NULL;
INSERT INTO `states` SET `id`= '545',`name`= 'Blagoevgrad',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '546',`name`= 'Burgas',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '547',`name`= 'Dobrich',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '548',`name`= 'Gabrovo',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '549',`name`= 'Haskovo',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '550',`name`= 'Jambol',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '551',`name`= 'Kardzhali',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '552',`name`= 'Kjustendil',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '553',`name`= 'Lovech',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '554',`name`= 'Montana',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '555',`name`= 'Oblast Sofiya-Grad',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '556',`name`= 'Pazardzhik',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '557',`name`= 'Pernik',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '558',`name`= 'Pleven',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '559',`name`= 'Plovdiv',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '560',`name`= 'Razgrad',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '561',`name`= 'Ruse',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '562',`name`= 'Shumen',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '563',`name`= 'Silistra',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '564',`name`= 'Sliven',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '565',`name`= 'Smoljan',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '566',`name`= 'Sofija grad',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '567',`name`= 'Sofijska oblast',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '568',`name`= 'Stara Zagora',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '569',`name`= 'Targovishte',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '570',`name`= 'Varna',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '571',`name`= 'Veliko Tarnovo',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '572',`name`= 'Vidin',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '573',`name`= 'Vraca',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '574',`name`= 'Yablaniza',`country_id`= '33',`created_by`= NULL;
INSERT INTO `states` SET `id`= '575',`name`= 'Bale',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '576',`name`= 'Bam',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '577',`name`= 'Bazega',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '578',`name`= 'Bougouriba',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '579',`name`= 'Boulgou',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '580',`name`= 'Boulkiemde',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '581',`name`= 'Comoe',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '582',`name`= 'Ganzourgou',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '583',`name`= 'Gnagna',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '584',`name`= 'Gourma',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '585',`name`= 'Houet',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '586',`name`= 'Ioba',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '587',`name`= 'Kadiogo',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '588',`name`= 'Kenedougou',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '589',`name`= 'Komandjari',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '590',`name`= 'Kompienga',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '591',`name`= 'Kossi',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '592',`name`= 'Kouritenga',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '593',`name`= 'Kourweogo',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '594',`name`= 'Leraba',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '595',`name`= 'Mouhoun',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '596',`name`= 'Nahouri',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '597',`name`= 'Namentenga',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '598',`name`= 'Noumbiel',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '599',`name`= 'Oubritenga',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '600',`name`= 'Oudalan',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '601',`name`= 'Passore',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '602',`name`= 'Poni',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '603',`name`= 'Sanguie',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '604',`name`= 'Sanmatenga',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '605',`name`= 'Seno',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '606',`name`= 'Sissili',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '607',`name`= 'Soum',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '608',`name`= 'Sourou',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '609',`name`= 'Tapoa',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '610',`name`= 'Tuy',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '611',`name`= 'Yatenga',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '612',`name`= 'Zondoma',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '613',`name`= 'Zoundweogo',`country_id`= '34',`created_by`= NULL;
INSERT INTO `states` SET `id`= '614',`name`= 'Bubanza',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '615',`name`= 'Bujumbura',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '616',`name`= 'Bururi',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '617',`name`= 'Cankuzo',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '618',`name`= 'Cibitoke',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '619',`name`= 'Gitega',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '620',`name`= 'Karuzi',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '621',`name`= 'Kayanza',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '622',`name`= 'Kirundo',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '623',`name`= 'Makamba',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '624',`name`= 'Muramvya',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '625',`name`= 'Muyinga',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '626',`name`= 'Ngozi',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '627',`name`= 'Rutana',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '628',`name`= 'Ruyigi',`country_id`= '35',`created_by`= NULL;
INSERT INTO `states` SET `id`= '629',`name`= 'Banteay Mean Chey',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '630',`name`= 'Bat Dambang',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '631',`name`= 'Kampong Cham',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '632',`name`= 'Kampong Chhnang',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '633',`name`= 'Kampong Spoeu',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '634',`name`= 'Kampong Thum',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '635',`name`= 'Kampot',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '636',`name`= 'Kandal',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '637',`name`= 'Kaoh Kong',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '638',`name`= 'Kracheh',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '639',`name`= 'Krong Kaeb',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '640',`name`= 'Krong Pailin',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '641',`name`= 'Krong Preah Sihanouk',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '642',`name`= 'Mondol Kiri',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '643',`name`= 'Otdar Mean Chey',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '644',`name`= 'Phnum Penh',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '645',`name`= 'Pousat',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '646',`name`= 'Preah Vihear',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '647',`name`= 'Prey Veaeng',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '648',`name`= 'Rotanak Kiri',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '649',`name`= 'Siem Reab',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '650',`name`= 'Stueng Traeng',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '651',`name`= 'Svay Rieng',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '652',`name`= 'Takaev',`country_id`= '36',`created_by`= NULL;
INSERT INTO `states` SET `id`= '653',`name`= 'Adamaoua',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '654',`name`= 'Centre',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '655',`name`= 'Est',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '656',`name`= 'Littoral',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '657',`name`= 'Nord',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '658',`name`= 'Nord Extreme',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '659',`name`= 'Nordouest',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '660',`name`= 'Ouest',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '661',`name`= 'Sud',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '662',`name`= 'Sudouest',`country_id`= '37',`created_by`= NULL;
INSERT INTO `states` SET `id`= '663',`name`= 'Alberta',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '664',`name`= 'British Columbia',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '665',`name`= 'Manitoba',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '666',`name`= 'New Brunswick',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '667',`name`= 'Newfoundland and Labrador',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '668',`name`= 'Northwest Territories',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '669',`name`= 'Nova Scotia',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '670',`name`= 'Nunavut',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '671',`name`= 'Ontario',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '672',`name`= 'Prince Edward Island',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '673',`name`= 'Quebec',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '674',`name`= 'Saskatchewan',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '675',`name`= 'Yukon',`country_id`= '38',`created_by`= NULL;
INSERT INTO `states` SET `id`= '676',`name`= 'Boavista',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '677',`name`= 'Brava',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '678',`name`= 'Fogo',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '679',`name`= 'Maio',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '680',`name`= 'Sal',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '681',`name`= 'Santo Antao',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '682',`name`= 'Sao Nicolau',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '683',`name`= 'Sao Tiago',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '684',`name`= 'Sao Vicente',`country_id`= '39',`created_by`= NULL;
INSERT INTO `states` SET `id`= '685',`name`= 'Grand Cayman',`country_id`= '40',`created_by`= NULL;
INSERT INTO `states` SET `id`= '686',`name`= 'Bamingui-Bangoran',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '687',`name`= 'Bangui',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '688',`name`= 'Basse-Kotto',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '689',`name`= 'Haut-Mbomou',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '690',`name`= 'Haute-Kotto',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '691',`name`= 'Kemo',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '692',`name`= 'Lobaye',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '693',`name`= 'Mambere-Kadei',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '694',`name`= 'Mbomou',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '695',`name`= 'Nana-Gribizi',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '696',`name`= 'Nana-Mambere',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '697',`name`= 'Ombella Mpoko',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '698',`name`= 'Ouaka',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '699',`name`= 'Ouham',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '700',`name`= 'Ouham-Pende',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '701',`name`= 'Sangha-Mbaere',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '702',`name`= 'Vakaga',`country_id`= '41',`created_by`= NULL;
INSERT INTO `states` SET `id`= '703',`name`= 'Batha',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '704',`name`= 'Biltine',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '705',`name`= 'Bourkou-Ennedi-Tibesti',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '706',`name`= 'Chari-Baguirmi',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '707',`name`= 'Guera',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '708',`name`= 'Kanem',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '709',`name`= 'Lac',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '710',`name`= 'Logone Occidental',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '711',`name`= 'Logone Oriental',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '712',`name`= 'Mayo-Kebbi',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '713',`name`= 'Moyen-Chari',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '714',`name`= 'Ouaddai',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '715',`name`= 'Salamat',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '716',`name`= 'Tandjile',`country_id`= '42',`created_by`= NULL;
INSERT INTO `states` SET `id`= '717',`name`= 'Aisen',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '718',`name`= 'Antofagasta',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '719',`name`= 'Araucania',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '720',`name`= 'Atacama',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '721',`name`= 'Bio Bio',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '722',`name`= 'Coquimbo',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '723',`name`= 'Libertador General Bernardo O\'',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '724',`name`= 'Los Lagos',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '725',`name`= 'Magellanes',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '726',`name`= 'Maule',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '727',`name`= 'Metropolitana',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '728',`name`= 'Metropolitana de Santiago',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '729',`name`= 'Tarapaca',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '730',`name`= 'Valparaiso',`country_id`= '43',`created_by`= NULL;
INSERT INTO `states` SET `id`= '731',`name`= 'Anhui',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '732',`name`= 'Anhui Province',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '733',`name`= 'Anhui Sheng',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '734',`name`= 'Aomen',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '735',`name`= 'Beijing',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '736',`name`= 'Beijing Shi',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '737',`name`= 'Chongqing',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '738',`name`= 'Fujian',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '739',`name`= 'Fujian Sheng',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '740',`name`= 'Gansu',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '741',`name`= 'Guangdong',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '742',`name`= 'Guangdong Sheng',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '743',`name`= 'Guangxi',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '744',`name`= 'Guizhou',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '745',`name`= 'Hainan',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '746',`name`= 'Hebei',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '747',`name`= 'Heilongjiang',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '748',`name`= 'Henan',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '749',`name`= 'Hubei',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '750',`name`= 'Hunan',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '751',`name`= 'Jiangsu',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '752',`name`= 'Jiangsu Sheng',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '753',`name`= 'Jiangxi',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '754',`name`= 'Jilin',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '755',`name`= 'Liaoning',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '756',`name`= 'Liaoning Sheng',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '757',`name`= 'Nei Monggol',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '758',`name`= 'Ningxia Hui',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '759',`name`= 'Qinghai',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '760',`name`= 'Shaanxi',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '761',`name`= 'Shandong',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '762',`name`= 'Shandong Sheng',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '763',`name`= 'Shanghai',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '764',`name`= 'Shanxi',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '765',`name`= 'Sichuan',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '766',`name`= 'Tianjin',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '767',`name`= 'Xianggang',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '768',`name`= 'Xinjiang',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '769',`name`= 'Xizang',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '770',`name`= 'Yunnan',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '771',`name`= 'Zhejiang',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '772',`name`= 'Zhejiang Sheng',`country_id`= '44',`created_by`= NULL;
INSERT INTO `states` SET `id`= '773',`name`= 'Christmas Island',`country_id`= '45',`created_by`= NULL;
INSERT INTO `states` SET `id`= '774',`name`= 'Cocos (Keeling) Islands',`country_id`= '46',`created_by`= NULL;
INSERT INTO `states` SET `id`= '775',`name`= 'Amazonas',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '776',`name`= 'Antioquia',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '777',`name`= 'Arauca',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '778',`name`= 'Atlantico',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '779',`name`= 'Bogota',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '780',`name`= 'Bolivar',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '781',`name`= 'Boyaca',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '782',`name`= 'Caldas',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '783',`name`= 'Caqueta',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '784',`name`= 'Casanare',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '785',`name`= 'Cauca',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '786',`name`= 'Cesar',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '787',`name`= 'Choco',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '788',`name`= 'Cordoba',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '789',`name`= 'Cundinamarca',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '790',`name`= 'Guainia',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '791',`name`= 'Guaviare',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '792',`name`= 'Huila',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '793',`name`= 'La Guajira',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '794',`name`= 'Magdalena',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '795',`name`= 'Meta',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '796',`name`= 'Narino',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '797',`name`= 'Norte de Santander',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '798',`name`= 'Putumayo',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '799',`name`= 'Quindio',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '800',`name`= 'Risaralda',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '801',`name`= 'San Andres y Providencia',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '802',`name`= 'Santander',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '803',`name`= 'Sucre',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '804',`name`= 'Tolima',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '805',`name`= 'Valle del Cauca',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '806',`name`= 'Vaupes',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '807',`name`= 'Vichada',`country_id`= '47',`created_by`= NULL;
INSERT INTO `states` SET `id`= '808',`name`= 'Mwali',`country_id`= '48',`created_by`= NULL;
INSERT INTO `states` SET `id`= '809',`name`= 'Njazidja',`country_id`= '48',`created_by`= NULL;
INSERT INTO `states` SET `id`= '810',`name`= 'Nzwani',`country_id`= '48',`created_by`= NULL;
INSERT INTO `states` SET `id`= '811',`name`= 'Bouenza',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '812',`name`= 'Brazzaville',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '813',`name`= 'Cuvette',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '814',`name`= 'Kouilou',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '815',`name`= 'Lekoumou',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '816',`name`= 'Likouala',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '817',`name`= 'Niari',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '818',`name`= 'Plateaux',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '819',`name`= 'Pool',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '820',`name`= 'Sangha',`country_id`= '49',`created_by`= NULL;
INSERT INTO `states` SET `id`= '821',`name`= 'Bandundu',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '822',`name`= 'Bas-Congo',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '823',`name`= 'Equateur',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '824',`name`= 'Haut-Congo',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '825',`name`= 'Kasai-Occidental',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '826',`name`= 'Kasai-Oriental',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '827',`name`= 'Katanga',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '828',`name`= 'Kinshasa',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '829',`name`= 'Maniema',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '830',`name`= 'Nord-Kivu',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '831',`name`= 'Sud-Kivu',`country_id`= '50',`created_by`= NULL;
INSERT INTO `states` SET `id`= '832',`name`= 'Aitutaki',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '833',`name`= 'Atiu',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '834',`name`= 'Mangaia',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '835',`name`= 'Manihiki',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '836',`name`= 'Mauke',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '837',`name`= 'Mitiaro',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '838',`name`= 'Nassau',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '839',`name`= 'Pukapuka',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '840',`name`= 'Rakahanga',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '841',`name`= 'Rarotonga',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '842',`name`= 'Tongareva',`country_id`= '51',`created_by`= NULL;
INSERT INTO `states` SET `id`= '843',`name`= 'Alajuela',`country_id`= '52',`created_by`= NULL;
INSERT INTO `states` SET `id`= '844',`name`= 'Cartago',`country_id`= '52',`created_by`= NULL;
INSERT INTO `states` SET `id`= '845',`name`= 'Guanacaste',`country_id`= '52',`created_by`= NULL;
INSERT INTO `states` SET `id`= '846',`name`= 'Heredia',`country_id`= '52',`created_by`= NULL;
INSERT INTO `states` SET `id`= '847',`name`= 'Limon',`country_id`= '52',`created_by`= NULL;
INSERT INTO `states` SET `id`= '848',`name`= 'Puntarenas',`country_id`= '52',`created_by`= NULL;
INSERT INTO `states` SET `id`= '849',`name`= 'San Jose',`country_id`= '52',`created_by`= NULL;
INSERT INTO `states` SET `id`= '850',`name`= 'Abidjan',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '851',`name`= 'Agneby',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '852',`name`= 'Bafing',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '853',`name`= 'Denguele',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '854',`name`= 'Dix-huit Montagnes',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '855',`name`= 'Fromager',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '856',`name`= 'Haut-Sassandra',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '857',`name`= 'Lacs',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '858',`name`= 'Lagunes',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '859',`name`= 'Marahoue',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '860',`name`= 'Moyen-Cavally',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '861',`name`= 'Moyen-Comoe',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '862',`name`= 'N\'zi-Comoe',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '863',`name`= 'Sassandra',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '864',`name`= 'Savanes',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '865',`name`= 'Sud-Bandama',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '866',`name`= 'Sud-Comoe',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '867',`name`= 'Vallee du Bandama',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '868',`name`= 'Worodougou',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '869',`name`= 'Zanzan',`country_id`= '53',`created_by`= NULL;
INSERT INTO `states` SET `id`= '870',`name`= 'Bjelovar-Bilogora',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '871',`name`= 'Dubrovnik-Neretva',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '872',`name`= 'Grad Zagreb',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '873',`name`= 'Istra',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '874',`name`= 'Karlovac',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '875',`name`= 'Koprivnica-Krizhevci',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '876',`name`= 'Krapina-Zagorje',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '877',`name`= 'Lika-Senj',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '878',`name`= 'Medhimurje',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '879',`name`= 'Medimurska Zupanija',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '880',`name`= 'Osijek-Baranja',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '881',`name`= 'Osjecko-Baranjska Zupanija',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '882',`name`= 'Pozhega-Slavonija',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '883',`name`= 'Primorje-Gorski Kotar',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '884',`name`= 'Shibenik-Knin',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '885',`name`= 'Sisak-Moslavina',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '886',`name`= 'Slavonski Brod-Posavina',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '887',`name`= 'Split-Dalmacija',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '888',`name`= 'Varazhdin',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '889',`name`= 'Virovitica-Podravina',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '890',`name`= 'Vukovar-Srijem',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '891',`name`= 'Zadar',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '892',`name`= 'Zagreb',`country_id`= '54',`created_by`= NULL;
INSERT INTO `states` SET `id`= '893',`name`= 'Camaguey',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '894',`name`= 'Ciego de Avila',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '895',`name`= 'Cienfuegos',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '896',`name`= 'Ciudad de la Habana',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '897',`name`= 'Granma',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '898',`name`= 'Guantanamo',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '899',`name`= 'Habana',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '900',`name`= 'Holguin',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '901',`name`= 'Isla de la Juventud',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '902',`name`= 'La Habana',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '903',`name`= 'Las Tunas',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '904',`name`= 'Matanzas',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '905',`name`= 'Pinar del Rio',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '906',`name`= 'Sancti Spiritus',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '907',`name`= 'Santiago de Cuba',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '908',`name`= 'Villa Clara',`country_id`= '55',`created_by`= NULL;
INSERT INTO `states` SET `id`= '909',`name`= 'Government controlled area',`country_id`= '56',`created_by`= NULL;
INSERT INTO `states` SET `id`= '910',`name`= 'Limassol',`country_id`= '56',`created_by`= NULL;
INSERT INTO `states` SET `id`= '911',`name`= 'Nicosia District',`country_id`= '56',`created_by`= NULL;
INSERT INTO `states` SET `id`= '912',`name`= 'Paphos',`country_id`= '56',`created_by`= NULL;
INSERT INTO `states` SET `id`= '913',`name`= 'Turkish controlled area',`country_id`= '56',`created_by`= NULL;
INSERT INTO `states` SET `id`= '914',`name`= 'Central Bohemian',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '915',`name`= 'Frycovice',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '916',`name`= 'Jihocesky Kraj',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '917',`name`= 'Jihochesky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '918',`name`= 'Jihomoravsky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '919',`name`= 'Karlovarsky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '920',`name`= 'Klecany',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '921',`name`= 'Kralovehradecky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '922',`name`= 'Liberecky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '923',`name`= 'Lipov',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '924',`name`= 'Moravskoslezsky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '925',`name`= 'Olomoucky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '926',`name`= 'Olomoucky Kraj',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '927',`name`= 'Pardubicky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '928',`name`= 'Plzensky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '929',`name`= 'Praha',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '930',`name`= 'Rajhrad',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '931',`name`= 'Smirice',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '932',`name`= 'South Moravian',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '933',`name`= 'Straz nad Nisou',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '934',`name`= 'Stredochesky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '935',`name`= 'Unicov',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '936',`name`= 'Ustecky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '937',`name`= 'Valletta',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '938',`name`= 'Velesin',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '939',`name`= 'Vysochina',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '940',`name`= 'Zlinsky',`country_id`= '57',`created_by`= NULL;
INSERT INTO `states` SET `id`= '941',`name`= 'Arhus',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '942',`name`= 'Bornholm',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '943',`name`= 'Frederiksborg',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '944',`name`= 'Fyn',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '945',`name`= 'Hovedstaden',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '946',`name`= 'Kobenhavn',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '947',`name`= 'Kobenhavns Amt',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '948',`name`= 'Kobenhavns Kommune',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '949',`name`= 'Nordjylland',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '950',`name`= 'Ribe',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '951',`name`= 'Ringkobing',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '952',`name`= 'Roervig',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '953',`name`= 'Roskilde',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '954',`name`= 'Roslev',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '955',`name`= 'Sjaelland',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '956',`name`= 'Soeborg',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '957',`name`= 'Sonderjylland',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '958',`name`= 'Storstrom',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '959',`name`= 'Syddanmark',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '960',`name`= 'Toelloese',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '961',`name`= 'Vejle',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '962',`name`= 'Vestsjalland',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '963',`name`= 'Viborg',`country_id`= '58',`created_by`= NULL;
INSERT INTO `states` SET `id`= '964',`name`= '\'Ali Sabih',`country_id`= '59',`created_by`= NULL;
INSERT INTO `states` SET `id`= '965',`name`= 'Dikhil',`country_id`= '59',`created_by`= NULL;
INSERT INTO `states` SET `id`= '966',`name`= 'Jibuti',`country_id`= '59',`created_by`= NULL;
INSERT INTO `states` SET `id`= '967',`name`= 'Tajurah',`country_id`= '59',`created_by`= NULL;
INSERT INTO `states` SET `id`= '968',`name`= 'Ubuk',`country_id`= '59',`created_by`= NULL;
INSERT INTO `states` SET `id`= '969',`name`= 'Saint Andrew',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '970',`name`= 'Saint David',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '971',`name`= 'Saint George',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '972',`name`= 'Saint John',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '973',`name`= 'Saint Joseph',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '974',`name`= 'Saint Luke',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '975',`name`= 'Saint Mark',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '976',`name`= 'Saint Patrick',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '977',`name`= 'Saint Paul',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '978',`name`= 'Saint Peter',`country_id`= '60',`created_by`= NULL;
INSERT INTO `states` SET `id`= '979',`name`= 'Azua',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '980',`name`= 'Bahoruco',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '981',`name`= 'Barahona',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '982',`name`= 'Dajabon',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '983',`name`= 'Distrito Nacional',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '984',`name`= 'Duarte',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '985',`name`= 'El Seybo',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '986',`name`= 'Elias Pina',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '987',`name`= 'Espaillat',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '988',`name`= 'Hato Mayor',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '989',`name`= 'Independencia',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '990',`name`= 'La Altagracia',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '991',`name`= 'La Romana',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '992',`name`= 'La Vega',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '993',`name`= 'Maria Trinidad Sanchez',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '994',`name`= 'Monsenor Nouel',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '995',`name`= 'Monte Cristi',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '996',`name`= 'Monte Plata',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '997',`name`= 'Pedernales',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '998',`name`= 'Peravia',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '999',`name`= 'Puerto Plata',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1000',`name`= 'Salcedo',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1001',`name`= 'Samana',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1002',`name`= 'San Cristobal',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1003',`name`= 'San Juan',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1004',`name`= 'San Pedro de Macoris',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1005',`name`= 'Sanchez Ramirez',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1006',`name`= 'Santiago',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1007',`name`= 'Santiago Rodriguez',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1008',`name`= 'Valverde',`country_id`= '61',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1009',`name`= 'Aileu',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1010',`name`= 'Ainaro',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1011',`name`= 'Ambeno',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1012',`name`= 'Baucau',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1013',`name`= 'Bobonaro',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1014',`name`= 'Cova Lima',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1015',`name`= 'Dili',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1016',`name`= 'Ermera',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1017',`name`= 'Lautem',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1018',`name`= 'Liquica',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1019',`name`= 'Manatuto',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1020',`name`= 'Manufahi',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1021',`name`= 'Viqueque',`country_id`= '62',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1022',`name`= 'Azuay',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1023',`name`= 'Bolivar',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1024',`name`= 'Canar',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1025',`name`= 'Carchi',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1026',`name`= 'Chimborazo',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1027',`name`= 'Cotopaxi',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1028',`name`= 'El Oro',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1029',`name`= 'Esmeraldas',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1030',`name`= 'Galapagos',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1031',`name`= 'Guayas',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1032',`name`= 'Imbabura',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1033',`name`= 'Loja',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1034',`name`= 'Los Rios',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1035',`name`= 'Manabi',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1036',`name`= 'Morona Santiago',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1037',`name`= 'Napo',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1038',`name`= 'Orellana',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1039',`name`= 'Pastaza',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1040',`name`= 'Pichincha',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1041',`name`= 'Sucumbios',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1042',`name`= 'Tungurahua',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1043',`name`= 'Zamora Chinchipe',`country_id`= '63',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1044',`name`= 'Aswan',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1045',`name`= 'Asyut',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1046',`name`= 'Bani Suwayf',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1047',`name`= 'Bur Sa\'id',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1048',`name`= 'Cairo',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1049',`name`= 'Dumyat',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1050',`name`= 'Kafr-ash-Shaykh',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1051',`name`= 'Matruh',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1052',`name`= 'Muhafazat ad Daqahliyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1053',`name`= 'Muhafazat al Fayyum',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1054',`name`= 'Muhafazat al Gharbiyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1055',`name`= 'Muhafazat al Iskandariyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1056',`name`= 'Muhafazat al Qahirah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1057',`name`= 'Qina',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1058',`name`= 'Sawhaj',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1059',`name`= 'Sina al-Janubiyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1060',`name`= 'Sina ash-Shamaliyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1061',`name`= 'ad-Daqahliyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1062',`name`= 'al-Bahr-al-Ahmar',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1063',`name`= 'al-Buhayrah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1064',`name`= 'al-Fayyum',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1065',`name`= 'al-Gharbiyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1066',`name`= 'al-Iskandariyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1067',`name`= 'al-Ismailiyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1068',`name`= 'al-Jizah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1069',`name`= 'al-Minufiyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1070',`name`= 'al-Minya',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1071',`name`= 'al-Qahira',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1072',`name`= 'al-Qalyubiyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1073',`name`= 'al-Uqsur',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1074',`name`= 'al-Wadi al-Jadid',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1075',`name`= 'as-Suways',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1076',`name`= 'ash-Sharqiyah',`country_id`= '64',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1077',`name`= 'Ahuachapan',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1078',`name`= 'Cabanas',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1079',`name`= 'Chalatenango',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1080',`name`= 'Cuscatlan',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1081',`name`= 'La Libertad',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1082',`name`= 'La Paz',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1083',`name`= 'La Union',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1084',`name`= 'Morazan',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1085',`name`= 'San Miguel',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1086',`name`= 'San Salvador',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1087',`name`= 'San Vicente',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1088',`name`= 'Santa Ana',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1089',`name`= 'Sonsonate',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1090',`name`= 'Usulutan',`country_id`= '65',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1091',`name`= 'Annobon',`country_id`= '66',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1092',`name`= 'Bioko Norte',`country_id`= '66',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1093',`name`= 'Bioko Sur',`country_id`= '66',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1094',`name`= 'Centro Sur',`country_id`= '66',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1095',`name`= 'Kie-Ntem',`country_id`= '66',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1096',`name`= 'Litoral',`country_id`= '66',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1097',`name`= 'Wele-Nzas',`country_id`= '66',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1098',`name`= 'Anseba',`country_id`= '67',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1099',`name`= 'Debub',`country_id`= '67',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1100',`name`= 'Debub-Keih-Bahri',`country_id`= '67',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1101',`name`= 'Gash-Barka',`country_id`= '67',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1102',`name`= 'Maekel',`country_id`= '67',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1103',`name`= 'Semien-Keih-Bahri',`country_id`= '67',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1104',`name`= 'Harju',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1105',`name`= 'Hiiu',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1106',`name`= 'Ida-Viru',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1107',`name`= 'Jarva',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1108',`name`= 'Jogeva',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1109',`name`= 'Laane',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1110',`name`= 'Laane-Viru',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1111',`name`= 'Parnu',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1112',`name`= 'Polva',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1113',`name`= 'Rapla',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1114',`name`= 'Saare',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1115',`name`= 'Tartu',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1116',`name`= 'Valga',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1117',`name`= 'Viljandi',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1118',`name`= 'Voru',`country_id`= '68',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1119',`name`= 'Addis Abeba',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1120',`name`= 'Afar',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1121',`name`= 'Amhara',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1122',`name`= 'Benishangul',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1123',`name`= 'Diredawa',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1124',`name`= 'Gambella',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1125',`name`= 'Harar',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1126',`name`= 'Jigjiga',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1127',`name`= 'Mekele',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1128',`name`= 'Oromia',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1129',`name`= 'Somali',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1130',`name`= 'Southern',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1131',`name`= 'Tigray',`country_id`= '69',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1132',`name`= 'Christmas Island',`country_id`= '70',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1133',`name`= 'Cocos Islands',`country_id`= '70',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1134',`name`= 'Coral Sea Islands',`country_id`= '70',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1135',`name`= 'Falkland Islands',`country_id`= '71',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1136',`name`= 'South Georgia',`country_id`= '71',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1137',`name`= 'Klaksvik',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1138',`name`= 'Nor ara Eysturoy',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1139',`name`= 'Nor oy',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1140',`name`= 'Sandoy',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1141',`name`= 'Streymoy',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1142',`name`= 'Su uroy',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1143',`name`= 'Sy ra Eysturoy',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1144',`name`= 'Torshavn',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1145',`name`= 'Vaga',`country_id`= '72',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1146',`name`= 'Central',`country_id`= '73',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1147',`name`= 'Eastern',`country_id`= '73',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1148',`name`= 'Northern',`country_id`= '73',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1149',`name`= 'South Pacific',`country_id`= '73',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1150',`name`= 'Western',`country_id`= '73',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1151',`name`= 'Ahvenanmaa',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1152',`name`= 'Etela-Karjala',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1153',`name`= 'Etela-Pohjanmaa',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1154',`name`= 'Etela-Savo',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1155',`name`= 'Etela-Suomen Laani',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1156',`name`= 'Ita-Suomen Laani',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1157',`name`= 'Ita-Uusimaa',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1158',`name`= 'Kainuu',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1159',`name`= 'Kanta-Hame',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1160',`name`= 'Keski-Pohjanmaa',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1161',`name`= 'Keski-Suomi',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1162',`name`= 'Kymenlaakso',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1163',`name`= 'Lansi-Suomen Laani',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1164',`name`= 'Lappi',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1165',`name`= 'Northern Savonia',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1166',`name`= 'Ostrobothnia',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1167',`name`= 'Oulun Laani',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1168',`name`= 'Paijat-Hame',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1169',`name`= 'Pirkanmaa',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1170',`name`= 'Pohjanmaa',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1171',`name`= 'Pohjois-Karjala',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1172',`name`= 'Pohjois-Pohjanmaa',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1173',`name`= 'Pohjois-Savo',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1174',`name`= 'Saarijarvi',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1175',`name`= 'Satakunta',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1176',`name`= 'Southern Savonia',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1177',`name`= 'Tavastia Proper',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1178',`name`= 'Uleaborgs Lan',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1179',`name`= 'Uusimaa',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1180',`name`= 'Varsinais-Suomi',`country_id`= '74',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1181',`name`= 'Ain',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1182',`name`= 'Aisne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1183',`name`= 'Albi Le Sequestre',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1184',`name`= 'Allier',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1185',`name`= 'Alpes-Cote dAzur',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1186',`name`= 'Alpes-Maritimes',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1187',`name`= 'Alpes-de-Haute-Provence',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1188',`name`= 'Alsace',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1189',`name`= 'Aquitaine',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1190',`name`= 'Ardeche',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1191',`name`= 'Ardennes',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1192',`name`= 'Ariege',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1193',`name`= 'Aube',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1194',`name`= 'Aude',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1195',`name`= 'Auvergne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1196',`name`= 'Aveyron',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1197',`name`= 'Bas-Rhin',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1198',`name`= 'Basse-Normandie',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1199',`name`= 'Bouches-du-Rhone',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1200',`name`= 'Bourgogne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1201',`name`= 'Bretagne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1202',`name`= 'Brittany',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1203',`name`= 'Burgundy',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1204',`name`= 'Calvados',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1205',`name`= 'Cantal',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1206',`name`= 'Cedex',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1207',`name`= 'Centre',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1208',`name`= 'Charente',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1209',`name`= 'Charente-Maritime',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1210',`name`= 'Cher',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1211',`name`= 'Correze',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1212',`name`= 'Corse-du-Sud',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1213',`name`= 'Cote-d\'Or',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1214',`name`= 'Cotes-d\'Armor',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1215',`name`= 'Creuse',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1216',`name`= 'Crolles',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1217',`name`= 'Deux-Sevres',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1218',`name`= 'Dordogne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1219',`name`= 'Doubs',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1220',`name`= 'Drome',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1221',`name`= 'Essonne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1222',`name`= 'Eure',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1223',`name`= 'Eure-et-Loir',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1224',`name`= 'Feucherolles',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1225',`name`= 'Finistere',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1226',`name`= 'Franche-Comte',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1227',`name`= 'Gard',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1228',`name`= 'Gers',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1229',`name`= 'Gironde',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1230',`name`= 'Haut-Rhin',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1231',`name`= 'Haute-Corse',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1232',`name`= 'Haute-Garonne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1233',`name`= 'Haute-Loire',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1234',`name`= 'Haute-Marne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1235',`name`= 'Haute-Saone',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1236',`name`= 'Haute-Savoie',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1237',`name`= 'Haute-Vienne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1238',`name`= 'Hautes-Alpes',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1239',`name`= 'Hautes-Pyrenees',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1240',`name`= 'Hauts-de-Seine',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1241',`name`= 'Herault',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1242',`name`= 'Ile-de-France',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1243',`name`= 'Ille-et-Vilaine',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1244',`name`= 'Indre',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1245',`name`= 'Indre-et-Loire',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1246',`name`= 'Isere',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1247',`name`= 'Jura',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1248',`name`= 'Klagenfurt',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1249',`name`= 'Landes',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1250',`name`= 'Languedoc-Roussillon',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1251',`name`= 'Larcay',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1252',`name`= 'Le Castellet',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1253',`name`= 'Le Creusot',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1254',`name`= 'Limousin',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1255',`name`= 'Loir-et-Cher',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1256',`name`= 'Loire',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1257',`name`= 'Loire-Atlantique',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1258',`name`= 'Loiret',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1259',`name`= 'Lorraine',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1260',`name`= 'Lot',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1261',`name`= 'Lot-et-Garonne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1262',`name`= 'Lower Normandy',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1263',`name`= 'Lozere',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1264',`name`= 'Maine-et-Loire',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1265',`name`= 'Manche',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1266',`name`= 'Marne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1267',`name`= 'Mayenne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1268',`name`= 'Meurthe-et-Moselle',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1269',`name`= 'Meuse',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1270',`name`= 'Midi-Pyrenees',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1271',`name`= 'Morbihan',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1272',`name`= 'Moselle',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1273',`name`= 'Nievre',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1274',`name`= 'Nord',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1275',`name`= 'Nord-Pas-de-Calais',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1276',`name`= 'Oise',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1277',`name`= 'Orne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1278',`name`= 'Paris',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1279',`name`= 'Pas-de-Calais',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1280',`name`= 'Pays de la Loire',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1281',`name`= 'Pays-de-la-Loire',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1282',`name`= 'Picardy',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1283',`name`= 'Puy-de-Dome',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1284',`name`= 'Pyrenees-Atlantiques',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1285',`name`= 'Pyrenees-Orientales',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1286',`name`= 'Quelmes',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1287',`name`= 'Rhone',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1288',`name`= 'Rhone-Alpes',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1289',`name`= 'Saint Ouen',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1290',`name`= 'Saint Viatre',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1291',`name`= 'Saone-et-Loire',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1292',`name`= 'Sarthe',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1293',`name`= 'Savoie',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1294',`name`= 'Seine-Maritime',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1295',`name`= 'Seine-Saint-Denis',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1296',`name`= 'Seine-et-Marne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1297',`name`= 'Somme',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1298',`name`= 'Sophia Antipolis',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1299',`name`= 'Souvans',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1300',`name`= 'Tarn',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1301',`name`= 'Tarn-et-Garonne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1302',`name`= 'Territoire de Belfort',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1303',`name`= 'Treignac',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1304',`name`= 'Upper Normandy',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1305',`name`= 'Val-d\'Oise',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1306',`name`= 'Val-de-Marne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1307',`name`= 'Var',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1308',`name`= 'Vaucluse',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1309',`name`= 'Vellise',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1310',`name`= 'Vendee',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1311',`name`= 'Vienne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1312',`name`= 'Vosges',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1313',`name`= 'Yonne',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1314',`name`= 'Yvelines',`country_id`= '75',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1315',`name`= 'Cayenne',`country_id`= '76',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1316',`name`= 'Saint-Laurent-du-Maroni',`country_id`= '76',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1317',`name`= 'Iles du Vent',`country_id`= '77',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1318',`name`= 'Iles sous le Vent',`country_id`= '77',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1319',`name`= 'Marquesas',`country_id`= '77',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1320',`name`= 'Tuamotu',`country_id`= '77',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1321',`name`= 'Tubuai',`country_id`= '77',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1322',`name`= 'Amsterdam',`country_id`= '78',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1323',`name`= 'Crozet Islands',`country_id`= '78',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1324',`name`= 'Kerguelen',`country_id`= '78',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1325',`name`= 'Estuaire',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1326',`name`= 'Haut-Ogooue',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1327',`name`= 'Moyen-Ogooue',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1328',`name`= 'Ngounie',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1329',`name`= 'Nyanga',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1330',`name`= 'Ogooue-Ivindo',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1331',`name`= 'Ogooue-Lolo',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1332',`name`= 'Ogooue-Maritime',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1333',`name`= 'Woleu-Ntem',`country_id`= '79',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1334',`name`= 'Banjul',`country_id`= '80',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1335',`name`= 'Basse',`country_id`= '80',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1336',`name`= 'Brikama',`country_id`= '80',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1337',`name`= 'Janjanbureh',`country_id`= '80',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1338',`name`= 'Kanifing',`country_id`= '80',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1339',`name`= 'Kerewan',`country_id`= '80',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1340',`name`= 'Kuntaur',`country_id`= '80',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1341',`name`= 'Mansakonko',`country_id`= '80',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1342',`name`= 'Abhasia',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1343',`name`= 'Ajaria',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1344',`name`= 'Guria',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1345',`name`= 'Imereti',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1346',`name`= 'Kaheti',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1347',`name`= 'Kvemo Kartli',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1348',`name`= 'Mcheta-Mtianeti',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1349',`name`= 'Racha',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1350',`name`= 'Samagrelo-Zemo Svaneti',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1351',`name`= 'Samche-Zhavaheti',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1352',`name`= 'Shida Kartli',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1353',`name`= 'Tbilisi',`country_id`= '81',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1354',`name`= 'Auvergne',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1355',`name`= 'Baden-Wurttemberg',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1356',`name`= 'Bavaria',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1357',`name`= 'Bayern',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1358',`name`= 'Beilstein Wurtt',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1359',`name`= 'Berlin',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1360',`name`= 'Brandenburg',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1361',`name`= 'Bremen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1362',`name`= 'Dreisbach',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1363',`name`= 'Freistaat Bayern',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1364',`name`= 'Hamburg',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1365',`name`= 'Hannover',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1366',`name`= 'Heroldstatt',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1367',`name`= 'Hessen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1368',`name`= 'Kortenberg',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1369',`name`= 'Laasdorf',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1370',`name`= 'Land Baden-Wurttemberg',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1371',`name`= 'Land Bayern',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1372',`name`= 'Land Brandenburg',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1373',`name`= 'Land Hessen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1374',`name`= 'Land Mecklenburg-Vorpommern',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1375',`name`= 'Land Nordrhein-Westfalen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1376',`name`= 'Land Rheinland-Pfalz',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1377',`name`= 'Land Sachsen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1378',`name`= 'Land Sachsen-Anhalt',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1379',`name`= 'Land Thuringen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1380',`name`= 'Lower Saxony',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1381',`name`= 'Mecklenburg-Vorpommern',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1382',`name`= 'Mulfingen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1383',`name`= 'Munich',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1384',`name`= 'Neubeuern',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1385',`name`= 'Niedersachsen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1386',`name`= 'Noord-Holland',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1387',`name`= 'Nordrhein-Westfalen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1388',`name`= 'North Rhine-Westphalia',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1389',`name`= 'Osterode',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1390',`name`= 'Rheinland-Pfalz',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1391',`name`= 'Rhineland-Palatinate',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1392',`name`= 'Saarland',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1393',`name`= 'Sachsen',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1394',`name`= 'Sachsen-Anhalt',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1395',`name`= 'Saxony',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1396',`name`= 'Schleswig-Holstein',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1397',`name`= 'Thuringia',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1398',`name`= 'Webling',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1399',`name`= 'Weinstrabe',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1400',`name`= 'schlobborn',`country_id`= '82',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1401',`name`= 'Ashanti',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1402',`name`= 'Brong-Ahafo',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1403',`name`= 'Central',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1404',`name`= 'Eastern',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1405',`name`= 'Greater Accra',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1406',`name`= 'Northern',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1407',`name`= 'Upper East',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1408',`name`= 'Upper West',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1409',`name`= 'Volta',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1410',`name`= 'Western',`country_id`= '83',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1411',`name`= 'Gibraltar',`country_id`= '84',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1412',`name`= 'Acharnes',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1413',`name`= 'Ahaia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1414',`name`= 'Aitolia kai Akarnania',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1415',`name`= 'Argolis',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1416',`name`= 'Arkadia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1417',`name`= 'Arta',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1418',`name`= 'Attica',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1419',`name`= 'Attiki',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1420',`name`= 'Ayion Oros',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1421',`name`= 'Crete',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1422',`name`= 'Dodekanisos',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1423',`name`= 'Drama',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1424',`name`= 'Evia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1425',`name`= 'Evritania',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1426',`name`= 'Evros',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1427',`name`= 'Evvoia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1428',`name`= 'Florina',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1429',`name`= 'Fokis',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1430',`name`= 'Fthiotis',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1431',`name`= 'Grevena',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1432',`name`= 'Halandri',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1433',`name`= 'Halkidiki',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1434',`name`= 'Hania',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1435',`name`= 'Heraklion',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1436',`name`= 'Hios',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1437',`name`= 'Ilia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1438',`name`= 'Imathia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1439',`name`= 'Ioannina',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1440',`name`= 'Iraklion',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1441',`name`= 'Karditsa',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1442',`name`= 'Kastoria',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1443',`name`= 'Kavala',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1444',`name`= 'Kefallinia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1445',`name`= 'Kerkira',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1446',`name`= 'Kiklades',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1447',`name`= 'Kilkis',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1448',`name`= 'Korinthia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1449',`name`= 'Kozani',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1450',`name`= 'Lakonia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1451',`name`= 'Larisa',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1452',`name`= 'Lasithi',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1453',`name`= 'Lesvos',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1454',`name`= 'Levkas',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1455',`name`= 'Magnisia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1456',`name`= 'Messinia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1457',`name`= 'Nomos Attikis',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1458',`name`= 'Nomos Zakynthou',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1459',`name`= 'Pella',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1460',`name`= 'Pieria',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1461',`name`= 'Piraios',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1462',`name`= 'Preveza',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1463',`name`= 'Rethimni',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1464',`name`= 'Rodopi',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1465',`name`= 'Samos',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1466',`name`= 'Serrai',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1467',`name`= 'Thesprotia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1468',`name`= 'Thessaloniki',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1469',`name`= 'Trikala',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1470',`name`= 'Voiotia',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1471',`name`= 'West Greece',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1472',`name`= 'Xanthi',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1473',`name`= 'Zakinthos',`country_id`= '85',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1474',`name`= 'Aasiaat',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1475',`name`= 'Ammassalik',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1476',`name`= 'Illoqqortoormiut',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1477',`name`= 'Ilulissat',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1478',`name`= 'Ivittuut',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1479',`name`= 'Kangaatsiaq',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1480',`name`= 'Maniitsoq',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1481',`name`= 'Nanortalik',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1482',`name`= 'Narsaq',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1483',`name`= 'Nuuk',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1484',`name`= 'Paamiut',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1485',`name`= 'Qaanaaq',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1486',`name`= 'Qaqortoq',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1487',`name`= 'Qasigiannguit',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1488',`name`= 'Qeqertarsuaq',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1489',`name`= 'Sisimiut',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1490',`name`= 'Udenfor kommunal inddeling',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1491',`name`= 'Upernavik',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1492',`name`= 'Uummannaq',`country_id`= '86',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1493',`name`= 'Carriacou-Petite Martinique',`country_id`= '87',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1494',`name`= 'Saint Andrew',`country_id`= '87',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1495',`name`= 'Saint Davids',`country_id`= '87',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1496',`name`= 'Saint George\'s',`country_id`= '87',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1497',`name`= 'Saint John',`country_id`= '87',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1498',`name`= 'Saint Mark',`country_id`= '87',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1499',`name`= 'Saint Patrick',`country_id`= '87',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1500',`name`= 'Basse-Terre',`country_id`= '88',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1501',`name`= 'Grande-Terre',`country_id`= '88',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1502',`name`= 'Iles des Saintes',`country_id`= '88',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1503',`name`= 'La Desirade',`country_id`= '88',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1504',`name`= 'Marie-Galante',`country_id`= '88',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1505',`name`= 'Saint Barthelemy',`country_id`= '88',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1506',`name`= 'Saint Martin',`country_id`= '88',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1507',`name`= 'Agana Heights',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1508',`name`= 'Agat',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1509',`name`= 'Barrigada',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1510',`name`= 'Chalan-Pago-Ordot',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1511',`name`= 'Dededo',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1512',`name`= 'Hagatna',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1513',`name`= 'Inarajan',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1514',`name`= 'Mangilao',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1515',`name`= 'Merizo',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1516',`name`= 'Mongmong-Toto-Maite',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1517',`name`= 'Santa Rita',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1518',`name`= 'Sinajana',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1519',`name`= 'Talofofo',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1520',`name`= 'Tamuning',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1521',`name`= 'Yigo',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1522',`name`= 'Yona',`country_id`= '89',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1523',`name`= 'Alta Verapaz',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1524',`name`= 'Baja Verapaz',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1525',`name`= 'Chimaltenango',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1526',`name`= 'Chiquimula',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1527',`name`= 'El Progreso',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1528',`name`= 'Escuintla',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1529',`name`= 'Guatemala',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1530',`name`= 'Huehuetenango',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1531',`name`= 'Izabal',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1532',`name`= 'Jalapa',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1533',`name`= 'Jutiapa',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1534',`name`= 'Peten',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1535',`name`= 'Quezaltenango',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1536',`name`= 'Quiche',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1537',`name`= 'Retalhuleu',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1538',`name`= 'Sacatepequez',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1539',`name`= 'San Marcos',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1540',`name`= 'Santa Rosa',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1541',`name`= 'Solola',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1542',`name`= 'Suchitepequez',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1543',`name`= 'Totonicapan',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1544',`name`= 'Zacapa',`country_id`= '90',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1545',`name`= 'Alderney',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1546',`name`= 'Castel',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1547',`name`= 'Forest',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1548',`name`= 'Saint Andrew',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1549',`name`= 'Saint Martin',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1550',`name`= 'Saint Peter Port',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1551',`name`= 'Saint Pierre du Bois',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1552',`name`= 'Saint Sampson',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1553',`name`= 'Saint Saviour',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1554',`name`= 'Sark',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1555',`name`= 'Torteval',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1556',`name`= 'Vale',`country_id`= '91',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1557',`name`= 'Beyla',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1558',`name`= 'Boffa',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1559',`name`= 'Boke',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1560',`name`= 'Conakry',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1561',`name`= 'Coyah',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1562',`name`= 'Dabola',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1563',`name`= 'Dalaba',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1564',`name`= 'Dinguiraye',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1565',`name`= 'Faranah',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1566',`name`= 'Forecariah',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1567',`name`= 'Fria',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1568',`name`= 'Gaoual',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1569',`name`= 'Gueckedou',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1570',`name`= 'Kankan',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1571',`name`= 'Kerouane',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1572',`name`= 'Kindia',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1573',`name`= 'Kissidougou',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1574',`name`= 'Koubia',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1575',`name`= 'Koundara',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1576',`name`= 'Kouroussa',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1577',`name`= 'Labe',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1578',`name`= 'Lola',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1579',`name`= 'Macenta',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1580',`name`= 'Mali',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1581',`name`= 'Mamou',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1582',`name`= 'Mandiana',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1583',`name`= 'Nzerekore',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1584',`name`= 'Pita',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1585',`name`= 'Siguiri',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1586',`name`= 'Telimele',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1587',`name`= 'Tougue',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1588',`name`= 'Yomou',`country_id`= '92',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1589',`name`= 'Bafata',`country_id`= '93',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1590',`name`= 'Bissau',`country_id`= '93',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1591',`name`= 'Bolama',`country_id`= '93',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1592',`name`= 'Cacheu',`country_id`= '93',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1593',`name`= 'Gabu',`country_id`= '93',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1594',`name`= 'Oio',`country_id`= '93',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1595',`name`= 'Quinara',`country_id`= '93',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1596',`name`= 'Tombali',`country_id`= '93',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1597',`name`= 'Barima-Waini',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1598',`name`= 'Cuyuni-Mazaruni',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1599',`name`= 'Demerara-Mahaica',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1600',`name`= 'East Berbice-Corentyne',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1601',`name`= 'Essequibo Islands-West Demerar',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1602',`name`= 'Mahaica-Berbice',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1603',`name`= 'Pomeroon-Supenaam',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1604',`name`= 'Potaro-Siparuni',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1605',`name`= 'Upper Demerara-Berbice',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1606',`name`= 'Upper Takutu-Upper Essequibo',`country_id`= '94',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1607',`name`= 'Artibonite',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1608',`name`= 'Centre',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1609',`name`= 'Grand\'Anse',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1610',`name`= 'Nord',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1611',`name`= 'Nord-Est',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1612',`name`= 'Nord-Ouest',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1613',`name`= 'Ouest',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1614',`name`= 'Sud',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1615',`name`= 'Sud-Est',`country_id`= '95',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1616',`name`= 'Heard and McDonald Islands',`country_id`= '96',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1617',`name`= 'Atlantida',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1618',`name`= 'Choluteca',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1619',`name`= 'Colon',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1620',`name`= 'Comayagua',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1621',`name`= 'Copan',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1622',`name`= 'Cortes',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1623',`name`= 'Distrito Central',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1624',`name`= 'El Paraiso',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1625',`name`= 'Francisco Morazan',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1626',`name`= 'Gracias a Dios',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1627',`name`= 'Intibuca',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1628',`name`= 'Islas de la Bahia',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1629',`name`= 'La Paz',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1630',`name`= 'Lempira',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1631',`name`= 'Ocotepeque',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1632',`name`= 'Olancho',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1633',`name`= 'Santa Barbara',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1634',`name`= 'Valle',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1635',`name`= 'Yoro',`country_id`= '97',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1636',`name`= 'Hong Kong',`country_id`= '98',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1637',`name`= 'Bacs-Kiskun',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1638',`name`= 'Baranya',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1639',`name`= 'Bekes',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1640',`name`= 'Borsod-Abauj-Zemplen',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1641',`name`= 'Budapest',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1642',`name`= 'Csongrad',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1643',`name`= 'Fejer',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1644',`name`= 'Gyor-Moson-Sopron',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1645',`name`= 'Hajdu-Bihar',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1646',`name`= 'Heves',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1647',`name`= 'Jasz-Nagykun-Szolnok',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1648',`name`= 'Komarom-Esztergom',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1649',`name`= 'Nograd',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1650',`name`= 'Pest',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1651',`name`= 'Somogy',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1652',`name`= 'Szabolcs-Szatmar-Bereg',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1653',`name`= 'Tolna',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1654',`name`= 'Vas',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1655',`name`= 'Veszprem',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1656',`name`= 'Zala',`country_id`= '99',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1657',`name`= 'Austurland',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1658',`name`= 'Gullbringusysla',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1659',`name`= 'Hofu borgarsva i',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1660',`name`= 'Nor urland eystra',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1661',`name`= 'Nor urland vestra',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1662',`name`= 'Su urland',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1663',`name`= 'Su urnes',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1664',`name`= 'Vestfir ir',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1665',`name`= 'Vesturland',`country_id`= '100',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1666',`name`= 'Aceh',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1667',`name`= 'Bali',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1668',`name`= 'Bangka-Belitung',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1669',`name`= 'Banten',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1670',`name`= 'Bengkulu',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1671',`name`= 'Gandaria',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1672',`name`= 'Gorontalo',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1673',`name`= 'Jakarta',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1674',`name`= 'Jambi',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1675',`name`= 'Jawa Barat',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1676',`name`= 'Jawa Tengah',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1677',`name`= 'Jawa Timur',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1678',`name`= 'Kalimantan Barat',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1679',`name`= 'Kalimantan Selatan',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1680',`name`= 'Kalimantan Tengah',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1681',`name`= 'Kalimantan Timur',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1682',`name`= 'Kendal',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1683',`name`= 'Lampung',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1684',`name`= 'Maluku',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1685',`name`= 'Maluku Utara',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1686',`name`= 'Nusa Tenggara Barat',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1687',`name`= 'Nusa Tenggara Timur',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1688',`name`= 'Papua',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1689',`name`= 'Riau',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1690',`name`= 'Riau Kepulauan',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1691',`name`= 'Solo',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1692',`name`= 'Sulawesi Selatan',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1693',`name`= 'Sulawesi Tengah',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1694',`name`= 'Sulawesi Tenggara',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1695',`name`= 'Sulawesi Utara',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1696',`name`= 'Sumatera Barat',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1697',`name`= 'Sumatera Selatan',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1698',`name`= 'Sumatera Utara',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1699',`name`= 'Yogyakarta',`country_id`= '102',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1700',`name`= 'Ardabil',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1701',`name`= 'Azarbayjan-e Bakhtari',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1702',`name`= 'Azarbayjan-e Khavari',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1703',`name`= 'Bushehr',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1704',`name`= 'Chahar Mahal-e Bakhtiari',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1705',`name`= 'Esfahan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1706',`name`= 'Fars',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1707',`name`= 'Gilan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1708',`name`= 'Golestan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1709',`name`= 'Hamadan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1710',`name`= 'Hormozgan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1711',`name`= 'Ilam',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1712',`name`= 'Kerman',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1713',`name`= 'Kermanshah',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1714',`name`= 'Khorasan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1715',`name`= 'Khuzestan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1716',`name`= 'Kohgiluyeh-e Boyerahmad',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1717',`name`= 'Kordestan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1718',`name`= 'Lorestan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1719',`name`= 'Markazi',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1720',`name`= 'Mazandaran',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1721',`name`= 'Ostan-e Esfahan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1722',`name`= 'Qazvin',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1723',`name`= 'Qom',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1724',`name`= 'Semnan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1725',`name`= 'Sistan-e Baluchestan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1726',`name`= 'Tehran',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1727',`name`= 'Yazd',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1728',`name`= 'Zanjan',`country_id`= '103',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1729',`name`= 'Babil',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1730',`name`= 'Baghdad',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1731',`name`= 'Dahuk',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1732',`name`= 'Dhi Qar',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1733',`name`= 'Diyala',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1734',`name`= 'Erbil',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1735',`name`= 'Irbil',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1736',`name`= 'Karbala',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1737',`name`= 'Kurdistan',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1738',`name`= 'Maysan',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1739',`name`= 'Ninawa',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1740',`name`= 'Salah-ad-Din',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1741',`name`= 'Wasit',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1742',`name`= 'al-Anbar',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1743',`name`= 'al-Basrah',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1744',`name`= 'al-Muthanna',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1745',`name`= 'al-Qadisiyah',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1746',`name`= 'an-Najaf',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1747',`name`= 'as-Sulaymaniyah',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1748',`name`= 'at-Ta\'mim',`country_id`= '104',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1749',`name`= 'Armagh',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1750',`name`= 'Carlow',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1751',`name`= 'Cavan',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1752',`name`= 'Clare',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1753',`name`= 'Cork',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1754',`name`= 'Donegal',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1755',`name`= 'Dublin',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1756',`name`= 'Galway',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1757',`name`= 'Kerry',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1758',`name`= 'Kildare',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1759',`name`= 'Kilkenny',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1760',`name`= 'Laois',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1761',`name`= 'Leinster',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1762',`name`= 'Leitrim',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1763',`name`= 'Limerick',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1764',`name`= 'Loch Garman',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1765',`name`= 'Longford',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1766',`name`= 'Louth',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1767',`name`= 'Mayo',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1768',`name`= 'Meath',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1769',`name`= 'Monaghan',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1770',`name`= 'Offaly',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1771',`name`= 'Roscommon',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1772',`name`= 'Sligo',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1773',`name`= 'Tipperary North Riding',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1774',`name`= 'Tipperary South Riding',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1775',`name`= 'Ulster',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1776',`name`= 'Waterford',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1777',`name`= 'Westmeath',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1778',`name`= 'Wexford',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1779',`name`= 'Wicklow',`country_id`= '105',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1780',`name`= 'Beit Hanania',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1781',`name`= 'Ben Gurion Airport',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1782',`name`= 'Bethlehem',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1783',`name`= 'Caesarea',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1784',`name`= 'Centre',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1785',`name`= 'Gaza',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1786',`name`= 'Hadaron',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1787',`name`= 'Haifa District',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1788',`name`= 'Hamerkaz',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1789',`name`= 'Hazafon',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1790',`name`= 'Hebron',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1791',`name`= 'Jaffa',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1792',`name`= 'Jerusalem',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1793',`name`= 'Khefa',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1794',`name`= 'Kiryat Yam',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1795',`name`= 'Lower Galilee',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1796',`name`= 'Qalqilya',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1797',`name`= 'Talme Elazar',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1798',`name`= 'Tel Aviv',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1799',`name`= 'Tsafon',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1800',`name`= 'Umm El Fahem',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1801',`name`= 'Yerushalayim',`country_id`= '106',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1802',`name`= 'Abruzzi',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1803',`name`= 'Abruzzo',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1804',`name`= 'Agrigento',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1805',`name`= 'Alessandria',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1806',`name`= 'Ancona',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1807',`name`= 'Arezzo',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1808',`name`= 'Ascoli Piceno',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1809',`name`= 'Asti',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1810',`name`= 'Avellino',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1811',`name`= 'Bari',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1812',`name`= 'Basilicata',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1813',`name`= 'Belluno',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1814',`name`= 'Benevento',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1815',`name`= 'Bergamo',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1816',`name`= 'Biella',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1817',`name`= 'Bologna',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1818',`name`= 'Bolzano',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1819',`name`= 'Brescia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1820',`name`= 'Brindisi',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1821',`name`= 'Calabria',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1822',`name`= 'Campania',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1823',`name`= 'Cartoceto',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1824',`name`= 'Caserta',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1825',`name`= 'Catania',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1826',`name`= 'Chieti',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1827',`name`= 'Como',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1828',`name`= 'Cosenza',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1829',`name`= 'Cremona',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1830',`name`= 'Cuneo',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1831',`name`= 'Emilia-Romagna',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1832',`name`= 'Ferrara',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1833',`name`= 'Firenze',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1834',`name`= 'Florence',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1835',`name`= 'Forli-Cesena ',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1836',`name`= 'Friuli-Venezia Giulia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1837',`name`= 'Frosinone',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1838',`name`= 'Genoa',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1839',`name`= 'Gorizia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1840',`name`= 'L\'Aquila',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1841',`name`= 'Lazio',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1842',`name`= 'Lecce',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1843',`name`= 'Lecco',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1844',`name`= 'Lecco Province',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1845',`name`= 'Liguria',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1846',`name`= 'Lodi',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1847',`name`= 'Lombardia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1848',`name`= 'Lombardy',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1849',`name`= 'Macerata',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1850',`name`= 'Mantova',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1851',`name`= 'Marche',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1852',`name`= 'Messina',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1853',`name`= 'Milan',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1854',`name`= 'Modena',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1855',`name`= 'Molise',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1856',`name`= 'Molteno',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1857',`name`= 'Montenegro',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1858',`name`= 'Monza and Brianza',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1859',`name`= 'Naples',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1860',`name`= 'Novara',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1861',`name`= 'Padova',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1862',`name`= 'Parma',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1863',`name`= 'Pavia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1864',`name`= 'Perugia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1865',`name`= 'Pesaro-Urbino',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1866',`name`= 'Piacenza',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1867',`name`= 'Piedmont',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1868',`name`= 'Piemonte',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1869',`name`= 'Pisa',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1870',`name`= 'Pordenone',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1871',`name`= 'Potenza',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1872',`name`= 'Puglia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1873',`name`= 'Reggio Emilia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1874',`name`= 'Rimini',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1875',`name`= 'Roma',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1876',`name`= 'Salerno',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1877',`name`= 'Sardegna',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1878',`name`= 'Sassari',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1879',`name`= 'Savona',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1880',`name`= 'Sicilia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1881',`name`= 'Siena',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1882',`name`= 'Sondrio',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1883',`name`= 'South Tyrol',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1884',`name`= 'Taranto',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1885',`name`= 'Teramo',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1886',`name`= 'Torino',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1887',`name`= 'Toscana',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1888',`name`= 'Trapani',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1889',`name`= 'Trentino-Alto Adige',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1890',`name`= 'Trento',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1891',`name`= 'Treviso',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1892',`name`= 'Udine',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1893',`name`= 'Umbria',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1894',`name`= 'Valle d\'Aosta',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1895',`name`= 'Varese',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1896',`name`= 'Veneto',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1897',`name`= 'Venezia',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1898',`name`= 'Verbano-Cusio-Ossola',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1899',`name`= 'Vercelli',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1900',`name`= 'Verona',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1901',`name`= 'Vicenza',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1902',`name`= 'Viterbo',`country_id`= '107',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1903',`name`= 'Buxoro Viloyati',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1904',`name`= 'Clarendon',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1905',`name`= 'Hanover',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1906',`name`= 'Kingston',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1907',`name`= 'Manchester',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1908',`name`= 'Portland',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1909',`name`= 'Saint Andrews',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1910',`name`= 'Saint Ann',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1911',`name`= 'Saint Catherine',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1912',`name`= 'Saint Elizabeth',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1913',`name`= 'Saint James',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1914',`name`= 'Saint Mary',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1915',`name`= 'Saint Thomas',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1916',`name`= 'Trelawney',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1917',`name`= 'Westmoreland',`country_id`= '108',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1918',`name`= 'Aichi',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1919',`name`= 'Akita',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1920',`name`= 'Aomori',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1921',`name`= 'Chiba',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1922',`name`= 'Ehime',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1923',`name`= 'Fukui',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1924',`name`= 'Fukuoka',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1925',`name`= 'Fukushima',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1926',`name`= 'Gifu',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1927',`name`= 'Gumma',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1928',`name`= 'Hiroshima',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1929',`name`= 'Hokkaido',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1930',`name`= 'Hyogo',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1931',`name`= 'Ibaraki',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1932',`name`= 'Ishikawa',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1933',`name`= 'Iwate',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1934',`name`= 'Kagawa',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1935',`name`= 'Kagoshima',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1936',`name`= 'Kanagawa',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1937',`name`= 'Kanto',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1938',`name`= 'Kochi',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1939',`name`= 'Kumamoto',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1940',`name`= 'Kyoto',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1941',`name`= 'Mie',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1942',`name`= 'Miyagi',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1943',`name`= 'Miyazaki',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1944',`name`= 'Nagano',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1945',`name`= 'Nagasaki',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1946',`name`= 'Nara',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1947',`name`= 'Niigata',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1948',`name`= 'Oita',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1949',`name`= 'Okayama',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1950',`name`= 'Okinawa',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1951',`name`= 'Osaka',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1952',`name`= 'Saga',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1953',`name`= 'Saitama',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1954',`name`= 'Shiga',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1955',`name`= 'Shimane',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1956',`name`= 'Shizuoka',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1957',`name`= 'Tochigi',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1958',`name`= 'Tokushima',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1959',`name`= 'Tokyo',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1960',`name`= 'Tottori',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1961',`name`= 'Toyama',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1962',`name`= 'Wakayama',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1963',`name`= 'Yamagata',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1964',`name`= 'Yamaguchi',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1965',`name`= 'Yamanashi',`country_id`= '109',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1966',`name`= 'Grouville',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1967',`name`= 'Saint Brelade',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1968',`name`= 'Saint Clement',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1969',`name`= 'Saint Helier',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1970',`name`= 'Saint John',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1971',`name`= 'Saint Lawrence',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1972',`name`= 'Saint Martin',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1973',`name`= 'Saint Mary',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1974',`name`= 'Saint Peter',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1975',`name`= 'Saint Saviour',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1976',`name`= 'Trinity',`country_id`= '110',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1977',`name`= '\'Ajlun',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1978',`name`= 'Amman',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1979',`name`= 'Irbid',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1980',`name`= 'Jarash',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1981',`name`= 'Ma\'an',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1982',`name`= 'Madaba',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1983',`name`= 'al-\'Aqabah',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1984',`name`= 'al-Balqa\'',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1985',`name`= 'al-Karak',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1986',`name`= 'al-Mafraq',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1987',`name`= 'at-Tafilah',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1988',`name`= 'az-Zarqa\'',`country_id`= '111',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1989',`name`= 'Akmecet',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1990',`name`= 'Akmola',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1991',`name`= 'Aktobe',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1992',`name`= 'Almati',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1993',`name`= 'Atirau',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1994',`name`= 'Batis Kazakstan',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1995',`name`= 'Burlinsky Region',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1996',`name`= 'Karagandi',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1997',`name`= 'Kostanay',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1998',`name`= 'Mankistau',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '1999',`name`= 'Ontustik Kazakstan',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2000',`name`= 'Pavlodar',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2001',`name`= 'Sigis Kazakstan',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2002',`name`= 'Soltustik Kazakstan',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2003',`name`= 'Taraz',`country_id`= '112',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2004',`name`= 'Central',`country_id`= '113',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2005',`name`= 'Coast',`country_id`= '113',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2006',`name`= 'Eastern',`country_id`= '113',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2007',`name`= 'Nairobi',`country_id`= '113',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2008',`name`= 'North Eastern',`country_id`= '113',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2009',`name`= 'Nyanza',`country_id`= '113',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2010',`name`= 'Rift Valley',`country_id`= '113',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2011',`name`= 'Western',`country_id`= '113',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2012',`name`= 'Abaiang',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2013',`name`= 'Abemana',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2014',`name`= 'Aranuka',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2015',`name`= 'Arorae',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2016',`name`= 'Banaba',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2017',`name`= 'Beru',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2018',`name`= 'Butaritari',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2019',`name`= 'Kiritimati',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2020',`name`= 'Kuria',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2021',`name`= 'Maiana',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2022',`name`= 'Makin',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2023',`name`= 'Marakei',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2024',`name`= 'Nikunau',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2025',`name`= 'Nonouti',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2026',`name`= 'Onotoa',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2027',`name`= 'Phoenix Islands',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2028',`name`= 'Tabiteuea North',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2029',`name`= 'Tabiteuea South',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2030',`name`= 'Tabuaeran',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2031',`name`= 'Tamana',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2032',`name`= 'Tarawa North',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2033',`name`= 'Tarawa South',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2034',`name`= 'Teraina',`country_id`= '114',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2035',`name`= 'Chagangdo',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2036',`name`= 'Hamgyeongbukto',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2037',`name`= 'Hamgyeongnamdo',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2038',`name`= 'Hwanghaebukto',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2039',`name`= 'Hwanghaenamdo',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2040',`name`= 'Kaeseong',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2041',`name`= 'Kangweon',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2042',`name`= 'Nampo',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2043',`name`= 'Pyeonganbukto',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2044',`name`= 'Pyeongannamdo',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2045',`name`= 'Pyeongyang',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2046',`name`= 'Yanggang',`country_id`= '115',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2047',`name`= 'Busan',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2048',`name`= 'Cheju',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2049',`name`= 'Chollabuk',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2050',`name`= 'Chollanam',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2051',`name`= 'Chungbuk',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2052',`name`= 'Chungcheongbuk',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2053',`name`= 'Chungcheongnam',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2054',`name`= 'Chungnam',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2055',`name`= 'Daegu',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2056',`name`= 'Gangwon-do',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2057',`name`= 'Goyang-si',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2058',`name`= 'Gyeonggi-do',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2059',`name`= 'Gyeongsang ',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2060',`name`= 'Gyeongsangnam-do',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2061',`name`= 'Incheon',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2062',`name`= 'Jeju-Si',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2063',`name`= 'Jeonbuk',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2064',`name`= 'Kangweon',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2065',`name`= 'Kwangju',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2066',`name`= 'Kyeonggi',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2067',`name`= 'Kyeongsangbuk',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2068',`name`= 'Kyeongsangnam',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2069',`name`= 'Kyonggi-do',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2070',`name`= 'Kyungbuk-Do',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2071',`name`= 'Kyunggi-Do',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2072',`name`= 'Kyunggi-do',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2073',`name`= 'Pusan',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2074',`name`= 'Seoul',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2075',`name`= 'Sudogwon',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2076',`name`= 'Taegu',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2077',`name`= 'Taejeon',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2078',`name`= 'Taejon-gwangyoksi',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2079',`name`= 'Ulsan',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2080',`name`= 'Wonju',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2081',`name`= 'gwangyoksi',`country_id`= '116',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2082',`name`= 'Al Asimah',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2083',`name`= 'Hawalli',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2084',`name`= 'Mishref',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2085',`name`= 'Qadesiya',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2086',`name`= 'Safat',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2087',`name`= 'Salmiya',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2088',`name`= 'al-Ahmadi',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2089',`name`= 'al-Farwaniyah',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2090',`name`= 'al-Jahra',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2091',`name`= 'al-Kuwayt',`country_id`= '117',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2092',`name`= 'Batken',`country_id`= '118',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2093',`name`= 'Bishkek',`country_id`= '118',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2094',`name`= 'Chui',`country_id`= '118',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2095',`name`= 'Issyk-Kul',`country_id`= '118',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2096',`name`= 'Jalal-Abad',`country_id`= '118',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2097',`name`= 'Naryn',`country_id`= '118',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2098',`name`= 'Osh',`country_id`= '118',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2099',`name`= 'Talas',`country_id`= '118',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2100',`name`= 'Attopu',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2101',`name`= 'Bokeo',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2102',`name`= 'Bolikhamsay',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2103',`name`= 'Champasak',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2104',`name`= 'Houaphanh',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2105',`name`= 'Khammouane',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2106',`name`= 'Luang Nam Tha',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2107',`name`= 'Luang Prabang',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2108',`name`= 'Oudomxay',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2109',`name`= 'Phongsaly',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2110',`name`= 'Saravan',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2111',`name`= 'Savannakhet',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2112',`name`= 'Sekong',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2113',`name`= 'Viangchan Prefecture',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2114',`name`= 'Viangchan Province',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2115',`name`= 'Xaignabury',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2116',`name`= 'Xiang Khuang',`country_id`= '119',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2117',`name`= 'Aizkraukles',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2118',`name`= 'Aluksnes',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2119',`name`= 'Balvu',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2120',`name`= 'Bauskas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2121',`name`= 'Cesu',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2122',`name`= 'Daugavpils',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2123',`name`= 'Daugavpils City',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2124',`name`= 'Dobeles',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2125',`name`= 'Gulbenes',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2126',`name`= 'Jekabspils',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2127',`name`= 'Jelgava',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2128',`name`= 'Jelgavas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2129',`name`= 'Jurmala City',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2130',`name`= 'Kraslavas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2131',`name`= 'Kuldigas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2132',`name`= 'Liepaja',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2133',`name`= 'Liepajas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2134',`name`= 'Limbazhu',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2135',`name`= 'Ludzas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2136',`name`= 'Madonas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2137',`name`= 'Ogres',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2138',`name`= 'Preilu',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2139',`name`= 'Rezekne',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2140',`name`= 'Rezeknes',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2141',`name`= 'Riga',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2142',`name`= 'Rigas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2143',`name`= 'Saldus',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2144',`name`= 'Talsu',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2145',`name`= 'Tukuma',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2146',`name`= 'Valkas',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2147',`name`= 'Valmieras',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2148',`name`= 'Ventspils',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2149',`name`= 'Ventspils City',`country_id`= '120',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2150',`name`= 'Beirut',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2151',`name`= 'Jabal Lubnan',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2152',`name`= 'Mohafazat Liban-Nord',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2153',`name`= 'Mohafazat Mont-Liban',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2154',`name`= 'Sidon',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2155',`name`= 'al-Biqa',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2156',`name`= 'al-Janub',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2157',`name`= 'an-Nabatiyah',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2158',`name`= 'ash-Shamal',`country_id`= '121',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2159',`name`= 'Berea',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2160',`name`= 'Butha-Buthe',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2161',`name`= 'Leribe',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2162',`name`= 'Mafeteng',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2163',`name`= 'Maseru',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2164',`name`= 'Mohale\'s Hoek',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2165',`name`= 'Mokhotlong',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2166',`name`= 'Qacha\'s Nek',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2167',`name`= 'Quthing',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2168',`name`= 'Thaba-Tseka',`country_id`= '122',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2169',`name`= 'Bomi',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2170',`name`= 'Bong',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2171',`name`= 'Grand Bassa',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2172',`name`= 'Grand Cape Mount',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2173',`name`= 'Grand Gedeh',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2174',`name`= 'Loffa',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2175',`name`= 'Margibi',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2176',`name`= 'Maryland and Grand Kru',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2177',`name`= 'Montserrado',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2178',`name`= 'Nimba',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2179',`name`= 'Rivercess',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2180',`name`= 'Sinoe',`country_id`= '123',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2181',`name`= 'Ajdabiya',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2182',`name`= 'Fezzan',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2183',`name`= 'Banghazi',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2184',`name`= 'Darnah',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2185',`name`= 'Ghadamis',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2186',`name`= 'Gharyan',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2187',`name`= 'Misratah',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2188',`name`= 'Murzuq',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2189',`name`= 'Sabha',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2190',`name`= 'Sawfajjin',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2191',`name`= 'Surt',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2192',`name`= 'Tarabulus',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2193',`name`= 'Tarhunah',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2194',`name`= 'Tripolitania',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2195',`name`= 'Tubruq',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2196',`name`= 'Yafran',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2197',`name`= 'Zlitan',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2198',`name`= 'al-\'Aziziyah',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2199',`name`= 'al-Fatih',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2200',`name`= 'al-Jabal al Akhdar',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2201',`name`= 'al-Jufrah',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2202',`name`= 'al-Khums',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2203',`name`= 'al-Kufrah',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2204',`name`= 'an-Nuqat al-Khams',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2205',`name`= 'ash-Shati\'',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2206',`name`= 'az-Zawiyah',`country_id`= '124',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2207',`name`= 'Balzers',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2208',`name`= 'Eschen',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2209',`name`= 'Gamprin',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2210',`name`= 'Mauren',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2211',`name`= 'Planken',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2212',`name`= 'Ruggell',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2213',`name`= 'Schaan',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2214',`name`= 'Schellenberg',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2215',`name`= 'Triesen',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2216',`name`= 'Triesenberg',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2217',`name`= 'Vaduz',`country_id`= '125',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2218',`name`= 'Alytaus',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2219',`name`= 'Anyksciai',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2220',`name`= 'Kauno',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2221',`name`= 'Klaipedos',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2222',`name`= 'Marijampoles',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2223',`name`= 'Panevezhio',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2224',`name`= 'Panevezys',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2225',`name`= 'Shiauliu',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2226',`name`= 'Taurages',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2227',`name`= 'Telshiu',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2228',`name`= 'Telsiai',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2229',`name`= 'Utenos',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2230',`name`= 'Vilniaus',`country_id`= '126',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2231',`name`= 'Capellen',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2232',`name`= 'Clervaux',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2233',`name`= 'Diekirch',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2234',`name`= 'Echternach',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2235',`name`= 'Esch-sur-Alzette',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2236',`name`= 'Grevenmacher',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2237',`name`= 'Luxembourg',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2238',`name`= 'Mersch',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2239',`name`= 'Redange',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2240',`name`= 'Remich',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2241',`name`= 'Vianden',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2242',`name`= 'Wiltz',`country_id`= '127',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2243',`name`= 'Macau',`country_id`= '128',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2244',`name`= 'Berovo',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2245',`name`= 'Bitola',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2246',`name`= 'Brod',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2247',`name`= 'Debar',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2248',`name`= 'Delchevo',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2249',`name`= 'Demir Hisar',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2250',`name`= 'Gevgelija',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2251',`name`= 'Gostivar',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2252',`name`= 'Kavadarci',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2253',`name`= 'Kichevo',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2254',`name`= 'Kochani',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2255',`name`= 'Kratovo',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2256',`name`= 'Kriva Palanka',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2257',`name`= 'Krushevo',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2258',`name`= 'Kumanovo',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2259',`name`= 'Negotino',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2260',`name`= 'Ohrid',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2261',`name`= 'Prilep',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2262',`name`= 'Probishtip',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2263',`name`= 'Radovish',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2264',`name`= 'Resen',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2265',`name`= 'Shtip',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2266',`name`= 'Skopje',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2267',`name`= 'Struga',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2268',`name`= 'Strumica',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2269',`name`= 'Sveti Nikole',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2270',`name`= 'Tetovo',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2271',`name`= 'Valandovo',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2272',`name`= 'Veles',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2273',`name`= 'Vinica',`country_id`= '129',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2274',`name`= 'Antananarivo',`country_id`= '130',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2275',`name`= 'Antsiranana',`country_id`= '130',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2276',`name`= 'Fianarantsoa',`country_id`= '130',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2277',`name`= 'Mahajanga',`country_id`= '130',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2278',`name`= 'Toamasina',`country_id`= '130',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2279',`name`= 'Toliary',`country_id`= '130',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2280',`name`= 'Balaka',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2281',`name`= 'Blantyre City',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2282',`name`= 'Chikwawa',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2283',`name`= 'Chiradzulu',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2284',`name`= 'Chitipa',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2285',`name`= 'Dedza',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2286',`name`= 'Dowa',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2287',`name`= 'Karonga',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2288',`name`= 'Kasungu',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2289',`name`= 'Lilongwe City',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2290',`name`= 'Machinga',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2291',`name`= 'Mangochi',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2292',`name`= 'Mchinji',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2293',`name`= 'Mulanje',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2294',`name`= 'Mwanza',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2295',`name`= 'Mzimba',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2296',`name`= 'Mzuzu City',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2297',`name`= 'Nkhata Bay',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2298',`name`= 'Nkhotakota',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2299',`name`= 'Nsanje',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2300',`name`= 'Ntcheu',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2301',`name`= 'Ntchisi',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2302',`name`= 'Phalombe',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2303',`name`= 'Rumphi',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2304',`name`= 'Salima',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2305',`name`= 'Thyolo',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2306',`name`= 'Zomba Municipality',`country_id`= '131',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2307',`name`= 'Johor',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2308',`name`= 'Kedah',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2309',`name`= 'Kelantan',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2310',`name`= 'Kuala Lumpur',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2311',`name`= 'Labuan',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2312',`name`= 'Melaka',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2313',`name`= 'Negeri Johor',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2314',`name`= 'Negeri Sembilan',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2315',`name`= 'Pahang',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2316',`name`= 'Penang',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2317',`name`= 'Perak',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2318',`name`= 'Perlis',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2319',`name`= 'Pulau Pinang',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2320',`name`= 'Sabah',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2321',`name`= 'Sarawak',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2322',`name`= 'Selangor',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2323',`name`= 'Sembilan',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2324',`name`= 'Terengganu',`country_id`= '132',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2325',`name`= 'Alif Alif',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2326',`name`= 'Alif Dhaal',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2327',`name`= 'Baa',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2328',`name`= 'Dhaal',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2329',`name`= 'Faaf',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2330',`name`= 'Gaaf Alif',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2331',`name`= 'Gaaf Dhaal',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2332',`name`= 'Ghaviyani',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2333',`name`= 'Haa Alif',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2334',`name`= 'Haa Dhaal',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2335',`name`= 'Kaaf',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2336',`name`= 'Laam',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2337',`name`= 'Lhaviyani',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2338',`name`= 'Male',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2339',`name`= 'Miim',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2340',`name`= 'Nuun',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2341',`name`= 'Raa',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2342',`name`= 'Shaviyani',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2343',`name`= 'Siin',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2344',`name`= 'Thaa',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2345',`name`= 'Vaav',`country_id`= '133',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2346',`name`= 'Bamako',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2347',`name`= 'Gao',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2348',`name`= 'Kayes',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2349',`name`= 'Kidal',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2350',`name`= 'Koulikoro',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2351',`name`= 'Mopti',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2352',`name`= 'Segou',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2353',`name`= 'Sikasso',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2354',`name`= 'Tombouctou',`country_id`= '134',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2355',`name`= 'Gozo and Comino',`country_id`= '135',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2356',`name`= 'Inner Harbour',`country_id`= '135',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2357',`name`= 'Northern',`country_id`= '135',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2358',`name`= 'Outer Harbour',`country_id`= '135',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2359',`name`= 'South Eastern',`country_id`= '135',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2360',`name`= 'Valletta',`country_id`= '135',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2361',`name`= 'Western',`country_id`= '135',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2362',`name`= 'Castletown',`country_id`= '136',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2363',`name`= 'Douglas',`country_id`= '136',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2364',`name`= 'Laxey',`country_id`= '136',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2365',`name`= 'Onchan',`country_id`= '136',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2366',`name`= 'Peel',`country_id`= '136',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2367',`name`= 'Port Erin',`country_id`= '136',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2368',`name`= 'Port Saint Mary',`country_id`= '136',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2369',`name`= 'Ramsey',`country_id`= '136',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2370',`name`= 'Ailinlaplap',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2371',`name`= 'Ailuk',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2372',`name`= 'Arno',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2373',`name`= 'Aur',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2374',`name`= 'Bikini',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2375',`name`= 'Ebon',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2376',`name`= 'Enewetak',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2377',`name`= 'Jabat',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2378',`name`= 'Jaluit',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2379',`name`= 'Kili',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2380',`name`= 'Kwajalein',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2381',`name`= 'Lae',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2382',`name`= 'Lib',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2383',`name`= 'Likiep',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2384',`name`= 'Majuro',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2385',`name`= 'Maloelap',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2386',`name`= 'Mejit',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2387',`name`= 'Mili',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2388',`name`= 'Namorik',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2389',`name`= 'Namu',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2390',`name`= 'Rongelap',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2391',`name`= 'Ujae',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2392',`name`= 'Utrik',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2393',`name`= 'Wotho',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2394',`name`= 'Wotje',`country_id`= '137',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2395',`name`= 'Fort-de-France',`country_id`= '138',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2396',`name`= 'La Trinite',`country_id`= '138',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2397',`name`= 'Le Marin',`country_id`= '138',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2398',`name`= 'Saint-Pierre',`country_id`= '138',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2399',`name`= 'Adrar',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2400',`name`= 'Assaba',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2401',`name`= 'Brakna',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2402',`name`= 'Dhakhlat Nawadibu',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2403',`name`= 'Hudh-al-Gharbi',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2404',`name`= 'Hudh-ash-Sharqi',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2405',`name`= 'Inshiri',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2406',`name`= 'Nawakshut',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2407',`name`= 'Qidimagha',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2408',`name`= 'Qurqul',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2409',`name`= 'Taqant',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2410',`name`= 'Tiris Zammur',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2411',`name`= 'Trarza',`country_id`= '139',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2412',`name`= 'Black River',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2413',`name`= 'Eau Coulee',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2414',`name`= 'Flacq',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2415',`name`= 'Floreal',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2416',`name`= 'Grand Port',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2417',`name`= 'Moka',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2418',`name`= 'Pamplempousses',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2419',`name`= 'Plaines Wilhelm',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2420',`name`= 'Port Louis',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2421',`name`= 'Riviere du Rempart',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2422',`name`= 'Rodrigues',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2423',`name`= 'Rose Hill',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2424',`name`= 'Savanne',`country_id`= '140',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2425',`name`= 'Mayotte',`country_id`= '141',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2426',`name`= 'Pamanzi',`country_id`= '141',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2427',`name`= 'Aguascalientes',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2428',`name`= 'Baja California',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2429',`name`= 'Baja California Sur',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2430',`name`= 'Campeche',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2431',`name`= 'Chiapas',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2432',`name`= 'Chihuahua',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2433',`name`= 'Coahuila',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2434',`name`= 'Colima',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2435',`name`= 'Distrito Federal',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2436',`name`= 'Durango',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2437',`name`= 'Estado de Mexico',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2438',`name`= 'Guanajuato',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2439',`name`= 'Guerrero',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2440',`name`= 'Hidalgo',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2441',`name`= 'Jalisco',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2442',`name`= 'Mexico',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2443',`name`= 'Michoacan',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2444',`name`= 'Morelos',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2445',`name`= 'Nayarit',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2446',`name`= 'Nuevo Leon',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2447',`name`= 'Oaxaca',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2448',`name`= 'Puebla',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2449',`name`= 'Queretaro',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2450',`name`= 'Quintana Roo',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2451',`name`= 'San Luis Potosi',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2452',`name`= 'Sinaloa',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2453',`name`= 'Sonora',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2454',`name`= 'Tabasco',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2455',`name`= 'Tamaulipas',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2456',`name`= 'Tlaxcala',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2457',`name`= 'Veracruz',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2458',`name`= 'Yucatan',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2459',`name`= 'Zacatecas',`country_id`= '142',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2460',`name`= 'Chuuk',`country_id`= '143',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2461',`name`= 'Kusaie',`country_id`= '143',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2462',`name`= 'Pohnpei',`country_id`= '143',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2463',`name`= 'Yap',`country_id`= '143',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2464',`name`= 'Balti',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2465',`name`= 'Cahul',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2466',`name`= 'Chisinau',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2467',`name`= 'Chisinau Oras',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2468',`name`= 'Edinet',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2469',`name`= 'Gagauzia',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2470',`name`= 'Lapusna',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2471',`name`= 'Orhei',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2472',`name`= 'Soroca',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2473',`name`= 'Taraclia',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2474',`name`= 'Tighina',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2475',`name`= 'Transnistria',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2476',`name`= 'Ungheni',`country_id`= '144',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2477',`name`= 'Fontvieille',`country_id`= '145',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2478',`name`= 'La Condamine',`country_id`= '145',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2479',`name`= 'Monaco-Ville',`country_id`= '145',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2480',`name`= 'Monte Carlo',`country_id`= '145',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2481',`name`= 'Arhangaj',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2482',`name`= 'Bajan-Olgij',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2483',`name`= 'Bajanhongor',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2484',`name`= 'Bulgan',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2485',`name`= 'Darhan-Uul',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2486',`name`= 'Dornod',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2487',`name`= 'Dornogovi',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2488',`name`= 'Dundgovi',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2489',`name`= 'Govi-Altaj',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2490',`name`= 'Govisumber',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2491',`name`= 'Hentij',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2492',`name`= 'Hovd',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2493',`name`= 'Hovsgol',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2494',`name`= 'Omnogovi',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2495',`name`= 'Orhon',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2496',`name`= 'Ovorhangaj',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2497',`name`= 'Selenge',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2498',`name`= 'Suhbaatar',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2499',`name`= 'Tov',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2500',`name`= 'Ulaanbaatar',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2501',`name`= 'Uvs',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2502',`name`= 'Zavhan',`country_id`= '146',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2503',`name`= 'Montserrat',`country_id`= '147',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2504',`name`= 'Agadir',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2505',`name`= 'Casablanca',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2506',`name`= 'Chaouia-Ouardigha',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2507',`name`= 'Doukkala-Abda',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2508',`name`= 'Fes-Boulemane',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2509',`name`= 'Gharb-Chrarda-Beni Hssen',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2510',`name`= 'Guelmim',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2511',`name`= 'Kenitra',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2512',`name`= 'Marrakech-Tensift-Al Haouz',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2513',`name`= 'Meknes-Tafilalet',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2514',`name`= 'Oriental',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2515',`name`= 'Oujda',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2516',`name`= 'Province de Tanger',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2517',`name`= 'Rabat-Sale-Zammour-Zaer',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2518',`name`= 'Sala Al Jadida',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2519',`name`= 'Settat',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2520',`name`= 'Souss Massa-Draa',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2521',`name`= 'Tadla-Azilal',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2522',`name`= 'Tangier-Tetouan',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2523',`name`= 'Taza-Al Hoceima-Taounate',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2524',`name`= 'Wilaya de Casablanca',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2525',`name`= 'Wilaya de Rabat-Sale',`country_id`= '148',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2526',`name`= 'Cabo Delgado',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2527',`name`= 'Gaza',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2528',`name`= 'Inhambane',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2529',`name`= 'Manica',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2530',`name`= 'Maputo',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2531',`name`= 'Maputo Provincia',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2532',`name`= 'Nampula',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2533',`name`= 'Niassa',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2534',`name`= 'Sofala',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2535',`name`= 'Tete',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2536',`name`= 'Zambezia',`country_id`= '149',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2537',`name`= 'Ayeyarwady',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2538',`name`= 'Bago',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2539',`name`= 'Chin',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2540',`name`= 'Kachin',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2541',`name`= 'Kayah',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2542',`name`= 'Kayin',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2543',`name`= 'Magway',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2544',`name`= 'Mandalay',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2545',`name`= 'Mon',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2546',`name`= 'Nay Pyi Taw',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2547',`name`= 'Rakhine',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2548',`name`= 'Sagaing',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2549',`name`= 'Shan',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2550',`name`= 'Tanintharyi',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2551',`name`= 'Yangon',`country_id`= '150',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2552',`name`= 'Caprivi',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2553',`name`= 'Erongo',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2554',`name`= 'Hardap',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2555',`name`= 'Karas',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2556',`name`= 'Kavango',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2557',`name`= 'Khomas',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2558',`name`= 'Kunene',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2559',`name`= 'Ohangwena',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2560',`name`= 'Omaheke',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2561',`name`= 'Omusati',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2562',`name`= 'Oshana',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2563',`name`= 'Oshikoto',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2564',`name`= 'Otjozondjupa',`country_id`= '151',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2565',`name`= 'Yaren',`country_id`= '152',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2566',`name`= 'Bagmati',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2567',`name`= 'Bheri',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2568',`name`= 'Dhawalagiri',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2569',`name`= 'Gandaki',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2570',`name`= 'Janakpur',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2571',`name`= 'Karnali',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2572',`name`= 'Koshi',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2573',`name`= 'Lumbini',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2574',`name`= 'Mahakali',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2575',`name`= 'Mechi',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2576',`name`= 'Narayani',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2577',`name`= 'Rapti',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2578',`name`= 'Sagarmatha',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2579',`name`= 'Seti',`country_id`= '153',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2580',`name`= 'Bonaire',`country_id`= '154',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2581',`name`= 'Curacao',`country_id`= '154',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2582',`name`= 'Saba',`country_id`= '154',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2583',`name`= 'Sint Eustatius',`country_id`= '154',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2584',`name`= 'Sint Maarten',`country_id`= '154',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2585',`name`= 'Amsterdam',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2586',`name`= 'Benelux',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2587',`name`= 'Drenthe',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2588',`name`= 'Flevoland',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2589',`name`= 'Friesland',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2590',`name`= 'Gelderland',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2591',`name`= 'Groningen',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2592',`name`= 'Limburg',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2593',`name`= 'Noord-Brabant',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2594',`name`= 'Noord-Holland',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2595',`name`= 'Overijssel',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2596',`name`= 'South Holland',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2597',`name`= 'Utrecht',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2598',`name`= 'Zeeland',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2599',`name`= 'Zuid-Holland',`country_id`= '155',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2600',`name`= 'Iles',`country_id`= '156',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2601',`name`= 'Nord',`country_id`= '156',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2602',`name`= 'Sud',`country_id`= '156',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2603',`name`= 'Area Outside Region',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2604',`name`= 'Auckland',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2605',`name`= 'Bay of Plenty',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2606',`name`= 'Canterbury',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2607',`name`= 'Christchurch',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2608',`name`= 'Gisborne',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2609',`name`= 'Hawke\'s Bay',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2610',`name`= 'Manawatu-Wanganui',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2611',`name`= 'Marlborough',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2612',`name`= 'Nelson',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2613',`name`= 'Northland',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2614',`name`= 'Otago',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2615',`name`= 'Rodney',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2616',`name`= 'Southland',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2617',`name`= 'Taranaki',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2618',`name`= 'Tasman',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2619',`name`= 'Waikato',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2620',`name`= 'Wellington',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2621',`name`= 'West Coast',`country_id`= '157',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2622',`name`= 'Atlantico Norte',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2623',`name`= 'Atlantico Sur',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2624',`name`= 'Boaco',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2625',`name`= 'Carazo',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2626',`name`= 'Chinandega',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2627',`name`= 'Chontales',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2628',`name`= 'Esteli',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2629',`name`= 'Granada',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2630',`name`= 'Jinotega',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2631',`name`= 'Leon',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2632',`name`= 'Madriz',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2633',`name`= 'Managua',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2634',`name`= 'Masaya',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2635',`name`= 'Matagalpa',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2636',`name`= 'Nueva Segovia',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2637',`name`= 'Rio San Juan',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2638',`name`= 'Rivas',`country_id`= '158',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2639',`name`= 'Agadez',`country_id`= '159',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2640',`name`= 'Diffa',`country_id`= '159',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2641',`name`= 'Dosso',`country_id`= '159',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2642',`name`= 'Maradi',`country_id`= '159',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2643',`name`= 'Niamey',`country_id`= '159',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2644',`name`= 'Tahoua',`country_id`= '159',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2645',`name`= 'Tillabery',`country_id`= '159',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2646',`name`= 'Zinder',`country_id`= '159',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2647',`name`= 'Abia',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2648',`name`= 'Abuja Federal Capital Territor',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2649',`name`= 'Adamawa',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2650',`name`= 'Akwa Ibom',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2651',`name`= 'Anambra',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2652',`name`= 'Bauchi',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2653',`name`= 'Bayelsa',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2654',`name`= 'Benue',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2655',`name`= 'Borno',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2656',`name`= 'Cross River',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2657',`name`= 'Delta',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2658',`name`= 'Ebonyi',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2659',`name`= 'Edo',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2660',`name`= 'Ekiti',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2661',`name`= 'Enugu',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2662',`name`= 'Gombe',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2663',`name`= 'Imo',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2664',`name`= 'Jigawa',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2665',`name`= 'Kaduna',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2666',`name`= 'Kano',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2667',`name`= 'Katsina',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2668',`name`= 'Kebbi',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2669',`name`= 'Kogi',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2670',`name`= 'Kwara',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2671',`name`= 'Lagos',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2672',`name`= 'Nassarawa',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2673',`name`= 'Niger',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2674',`name`= 'Ogun',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2675',`name`= 'Ondo',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2676',`name`= 'Osun',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2677',`name`= 'Oyo',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2678',`name`= 'Plateau',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2679',`name`= 'Rivers',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2680',`name`= 'Sokoto',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2681',`name`= 'Taraba',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2682',`name`= 'Yobe',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2683',`name`= 'Zamfara',`country_id`= '160',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2684',`name`= 'Niue',`country_id`= '161',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2685',`name`= 'Norfolk Island',`country_id`= '162',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2686',`name`= 'Northern Islands',`country_id`= '163',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2687',`name`= 'Rota',`country_id`= '163',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2688',`name`= 'Saipan',`country_id`= '163',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2689',`name`= 'Tinian',`country_id`= '163',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2690',`name`= 'Akershus',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2691',`name`= 'Aust Agder',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2692',`name`= 'Bergen',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2693',`name`= 'Buskerud',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2694',`name`= 'Finnmark',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2695',`name`= 'Hedmark',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2696',`name`= 'Hordaland',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2697',`name`= 'Moere og Romsdal',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2698',`name`= 'Nord Trondelag',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2699',`name`= 'Nordland',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2700',`name`= 'Oestfold',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2701',`name`= 'Oppland',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2702',`name`= 'Oslo',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2703',`name`= 'Rogaland',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2704',`name`= 'Soer Troendelag',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2705',`name`= 'Sogn og Fjordane',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2706',`name`= 'Stavern',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2707',`name`= 'Sykkylven',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2708',`name`= 'Telemark',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2709',`name`= 'Troms',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2710',`name`= 'Vest Agder',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2711',`name`= 'Vestfold',`country_id`= '164',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2713',`name`= 'Al Buraimi',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2714',`name`= 'Dhufar',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2715',`name`= 'Masqat',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2716',`name`= 'Musandam',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2717',`name`= 'Rusayl',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2718',`name`= 'Wadi Kabir',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2719',`name`= 'ad-Dakhiliyah',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2720',`name`= 'adh-Dhahirah',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2721',`name`= 'al-Batinah',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2722',`name`= 'ash-Sharqiyah',`country_id`= '165',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2723',`name`= 'Baluchistan',`country_id`= '166',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2724',`name`= 'Federal Capital Area',`country_id`= '166',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2725',`name`= 'Federally administered Tribal ',`country_id`= '166',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2726',`name`= 'North-West Frontier',`country_id`= '166',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2727',`name`= 'Northern Areas',`country_id`= '166',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2728',`name`= 'Punjab',`country_id`= '166',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2729',`name`= 'Sind',`country_id`= '166',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2730',`name`= 'Aimeliik',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2731',`name`= 'Airai',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2732',`name`= 'Angaur',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2733',`name`= 'Hatobohei',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2734',`name`= 'Kayangel',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2735',`name`= 'Koror',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2736',`name`= 'Melekeok',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2737',`name`= 'Ngaraard',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2738',`name`= 'Ngardmau',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2739',`name`= 'Ngaremlengui',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2740',`name`= 'Ngatpang',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2741',`name`= 'Ngchesar',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2742',`name`= 'Ngerchelong',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2743',`name`= 'Ngiwal',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2744',`name`= 'Peleliu',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2745',`name`= 'Sonsorol',`country_id`= '167',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2746',`name`= 'Ariha',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2747',`name`= 'Bayt Lahm',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2748',`name`= 'Bethlehem',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2749',`name`= 'Dayr-al-Balah',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2750',`name`= 'Ghazzah',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2751',`name`= 'Ghazzah ash-Shamaliyah',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2752',`name`= 'Janin',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2753',`name`= 'Khan Yunis',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2754',`name`= 'Nabulus',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2755',`name`= 'Qalqilyah',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2756',`name`= 'Rafah',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2757',`name`= 'Ram Allah wal-Birah',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2758',`name`= 'Salfit',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2759',`name`= 'Tubas',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2760',`name`= 'Tulkarm',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2761',`name`= 'al-Khalil',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2762',`name`= 'al-Quds',`country_id`= '168',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2763',`name`= 'Bocas del Toro',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2764',`name`= 'Chiriqui',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2765',`name`= 'Cocle',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2766',`name`= 'Colon',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2767',`name`= 'Darien',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2768',`name`= 'Embera',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2769',`name`= 'Herrera',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2770',`name`= 'Kuna Yala',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2771',`name`= 'Los Santos',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2772',`name`= 'Ngobe Bugle',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2773',`name`= 'Panama',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2774',`name`= 'Veraguas',`country_id`= '169',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2775',`name`= 'East New Britain',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2776',`name`= 'East Sepik',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2777',`name`= 'Eastern Highlands',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2778',`name`= 'Enga',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2779',`name`= 'Fly River',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2780',`name`= 'Gulf',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2781',`name`= 'Madang',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2782',`name`= 'Manus',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2783',`name`= 'Milne Bay',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2784',`name`= 'Morobe',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2785',`name`= 'National Capital District',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2786',`name`= 'New Ireland',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2787',`name`= 'North Solomons',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2788',`name`= 'Oro',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2789',`name`= 'Sandaun',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2790',`name`= 'Simbu',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2791',`name`= 'Southern Highlands',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2792',`name`= 'West New Britain',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2793',`name`= 'Western Highlands',`country_id`= '170',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2794',`name`= 'Alto Paraguay',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2795',`name`= 'Alto Parana',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2796',`name`= 'Amambay',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2797',`name`= 'Asuncion',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2798',`name`= 'Boqueron',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2799',`name`= 'Caaguazu',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2800',`name`= 'Caazapa',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2801',`name`= 'Canendiyu',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2802',`name`= 'Central',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2803',`name`= 'Concepcion',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2804',`name`= 'Cordillera',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2805',`name`= 'Guaira',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2806',`name`= 'Itapua',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2807',`name`= 'Misiones',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2808',`name`= 'Neembucu',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2809',`name`= 'Paraguari',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2810',`name`= 'Presidente Hayes',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2811',`name`= 'San Pedro',`country_id`= '171',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2812',`name`= 'Amazonas',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2813',`name`= 'Ancash',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2814',`name`= 'Apurimac',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2815',`name`= 'Arequipa',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2816',`name`= 'Ayacucho',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2817',`name`= 'Cajamarca',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2818',`name`= 'Cusco',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2819',`name`= 'Huancavelica',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2820',`name`= 'Huanuco',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2821',`name`= 'Ica',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2822',`name`= 'Junin',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2823',`name`= 'La Libertad',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2824',`name`= 'Lambayeque',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2825',`name`= 'Lima y Callao',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2826',`name`= 'Loreto',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2827',`name`= 'Madre de Dios',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2828',`name`= 'Moquegua',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2829',`name`= 'Pasco',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2830',`name`= 'Piura',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2831',`name`= 'Puno',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2832',`name`= 'San Martin',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2833',`name`= 'Tacna',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2834',`name`= 'Tumbes',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2835',`name`= 'Ucayali',`country_id`= '172',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2836',`name`= 'Batangas',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2837',`name`= 'Bicol',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2838',`name`= 'Bulacan',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2839',`name`= 'Cagayan',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2840',`name`= 'Caraga',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2841',`name`= 'Central Luzon',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2842',`name`= 'Central Mindanao',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2843',`name`= 'Central Visayas',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2844',`name`= 'Cordillera',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2845',`name`= 'Davao',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2846',`name`= 'Eastern Visayas',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2847',`name`= 'Greater Metropolitan Area',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2848',`name`= 'Ilocos',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2849',`name`= 'Laguna',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2850',`name`= 'Luzon',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2851',`name`= 'Mactan',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2852',`name`= 'Metropolitan Manila Area',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2853',`name`= 'Muslim Mindanao',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2854',`name`= 'Northern Mindanao',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2855',`name`= 'Southern Mindanao',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2856',`name`= 'Southern Tagalog',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2857',`name`= 'Western Mindanao',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2858',`name`= 'Western Visayas',`country_id`= '173',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2859',`name`= 'Pitcairn Island',`country_id`= '174',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2860',`name`= 'Biale Blota',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2861',`name`= 'Dobroszyce',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2862',`name`= 'Dolnoslaskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2863',`name`= 'Dziekanow Lesny',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2864',`name`= 'Hopowo',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2865',`name`= 'Kartuzy',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2866',`name`= 'Koscian',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2867',`name`= 'Krakow',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2868',`name`= 'Kujawsko-Pomorskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2869',`name`= 'Lodzkie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2870',`name`= 'Lubelskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2871',`name`= 'Lubuskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2872',`name`= 'Malomice',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2873',`name`= 'Malopolskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2874',`name`= 'Mazowieckie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2875',`name`= 'Mirkow',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2876',`name`= 'Opolskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2877',`name`= 'Ostrowiec',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2878',`name`= 'Podkarpackie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2879',`name`= 'Podlaskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2880',`name`= 'Polska',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2881',`name`= 'Pomorskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2882',`name`= 'Poznan',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2883',`name`= 'Pruszkow',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2884',`name`= 'Rymanowska',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2885',`name`= 'Rzeszow',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2886',`name`= 'Slaskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2887',`name`= 'Stare Pole',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2888',`name`= 'Swietokrzyskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2889',`name`= 'Warminsko-Mazurskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2890',`name`= 'Warsaw',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2891',`name`= 'Wejherowo',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2892',`name`= 'Wielkopolskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2893',`name`= 'Wroclaw',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2894',`name`= 'Zachodnio-Pomorskie',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2895',`name`= 'Zukowo',`country_id`= '175',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2896',`name`= 'Abrantes',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2897',`name`= 'Acores',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2898',`name`= 'Alentejo',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2899',`name`= 'Algarve',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2900',`name`= 'Braga',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2901',`name`= 'Centro',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2902',`name`= 'Distrito de Leiria',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2903',`name`= 'Distrito de Viana do Castelo',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2904',`name`= 'Distrito de Vila Real',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2905',`name`= 'Distrito do Porto',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2906',`name`= 'Lisboa e Vale do Tejo',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2907',`name`= 'Madeira',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2908',`name`= 'Norte',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2909',`name`= 'Paivas',`country_id`= '176',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2910',`name`= 'Arecibo',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2911',`name`= 'Bayamon',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2912',`name`= 'Carolina',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2913',`name`= 'Florida',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2914',`name`= 'Guayama',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2915',`name`= 'Humacao',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2916',`name`= 'Mayaguez-Aguadilla',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2917',`name`= 'Ponce',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2918',`name`= 'Salinas',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2919',`name`= 'San Juan',`country_id`= '177',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2920',`name`= 'Doha',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2921',`name`= 'Jarian-al-Batnah',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2922',`name`= 'Umm Salal',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2923',`name`= 'ad-Dawhah',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2924',`name`= 'al-Ghuwayriyah',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2925',`name`= 'al-Jumayliyah',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2926',`name`= 'al-Khawr',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2927',`name`= 'al-Wakrah',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2928',`name`= 'ar-Rayyan',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2929',`name`= 'ash-Shamal',`country_id`= '178',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2930',`name`= 'Saint-Benoit',`country_id`= '179',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2931',`name`= 'Saint-Denis',`country_id`= '179',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2932',`name`= 'Saint-Paul',`country_id`= '179',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2933',`name`= 'Saint-Pierre',`country_id`= '179',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2934',`name`= 'Alba',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2935',`name`= 'Arad',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2936',`name`= 'Arges',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2937',`name`= 'Bacau',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2938',`name`= 'Bihor',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2939',`name`= 'Bistrita-Nasaud',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2940',`name`= 'Botosani',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2941',`name`= 'Braila',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2942',`name`= 'Brasov',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2943',`name`= 'Bucuresti',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2944',`name`= 'Buzau',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2945',`name`= 'Calarasi',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2946',`name`= 'Caras-Severin',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2947',`name`= 'Cluj',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2948',`name`= 'Constanta',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2949',`name`= 'Covasna',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2950',`name`= 'Dambovita',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2951',`name`= 'Dolj',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2952',`name`= 'Galati',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2953',`name`= 'Giurgiu',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2954',`name`= 'Gorj',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2955',`name`= 'Harghita',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2956',`name`= 'Hunedoara',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2957',`name`= 'Ialomita',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2958',`name`= 'Iasi',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2959',`name`= 'Ilfov',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2960',`name`= 'Maramures',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2961',`name`= 'Mehedinti',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2962',`name`= 'Mures',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2963',`name`= 'Neamt',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2964',`name`= 'Olt',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2965',`name`= 'Prahova',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2966',`name`= 'Salaj',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2967',`name`= 'Satu Mare',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2968',`name`= 'Sibiu',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2969',`name`= 'Sondelor',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2970',`name`= 'Suceava',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2971',`name`= 'Teleorman',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2972',`name`= 'Timis',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2973',`name`= 'Tulcea',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2974',`name`= 'Valcea',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2975',`name`= 'Vaslui',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2976',`name`= 'Vrancea',`country_id`= '180',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2977',`name`= 'Adygeja',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2978',`name`= 'Aga',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2979',`name`= 'Alanija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2980',`name`= 'Altaj',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2981',`name`= 'Amur',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2982',`name`= 'Arhangelsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2983',`name`= 'Astrahan',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2984',`name`= 'Bashkortostan',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2985',`name`= 'Belgorod',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2986',`name`= 'Brjansk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2987',`name`= 'Burjatija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2988',`name`= 'Chechenija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2989',`name`= 'Cheljabinsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2990',`name`= 'Chita',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2991',`name`= 'Chukotka',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2992',`name`= 'Chuvashija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2993',`name`= 'Dagestan',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2994',`name`= 'Evenkija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2995',`name`= 'Gorno-Altaj',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2996',`name`= 'Habarovsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2997',`name`= 'Hakasija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2998',`name`= 'Hanty-Mansija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '2999',`name`= 'Ingusetija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3000',`name`= 'Irkutsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3001',`name`= 'Ivanovo',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3002',`name`= 'Jamalo-Nenets',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3003',`name`= 'Jaroslavl',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3004',`name`= 'Jevrej',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3005',`name`= 'Kabardino-Balkarija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3006',`name`= 'Kaliningrad',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3007',`name`= 'Kalmykija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3008',`name`= 'Kaluga',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3009',`name`= 'Kamchatka',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3010',`name`= 'Karachaj-Cherkessija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3011',`name`= 'Karelija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3012',`name`= 'Kemerovo',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3013',`name`= 'Khabarovskiy Kray',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3014',`name`= 'Kirov',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3015',`name`= 'Komi',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3016',`name`= 'Komi-Permjakija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3017',`name`= 'Korjakija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3018',`name`= 'Kostroma',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3019',`name`= 'Krasnodar',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3020',`name`= 'Krasnojarsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3021',`name`= 'Krasnoyarskiy Kray',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3022',`name`= 'Kurgan',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3023',`name`= 'Kursk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3024',`name`= 'Leningrad',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3025',`name`= 'Lipeck',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3026',`name`= 'Magadan',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3027',`name`= 'Marij El',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3028',`name`= 'Mordovija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3029',`name`= 'Moscow',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3030',`name`= 'Moskovskaja Oblast',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3031',`name`= 'Moskovskaya Oblast',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3032',`name`= 'Moskva',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3033',`name`= 'Murmansk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3034',`name`= 'Nenets',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3035',`name`= 'Nizhnij Novgorod',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3036',`name`= 'Novgorod',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3037',`name`= 'Novokusnezk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3038',`name`= 'Novosibirsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3039',`name`= 'Omsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3040',`name`= 'Orenburg',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3041',`name`= 'Orjol',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3042',`name`= 'Penza',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3043',`name`= 'Perm',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3044',`name`= 'Primorje',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3045',`name`= 'Pskov',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3046',`name`= 'Pskovskaya Oblast',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3047',`name`= 'Rjazan',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3048',`name`= 'Rostov',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3049',`name`= 'Saha',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3050',`name`= 'Sahalin',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3051',`name`= 'Samara',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3052',`name`= 'Samarskaya',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3053',`name`= 'Sankt-Peterburg',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3054',`name`= 'Saratov',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3055',`name`= 'Smolensk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3056',`name`= 'Stavropol',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3057',`name`= 'Sverdlovsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3058',`name`= 'Tajmyrija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3059',`name`= 'Tambov',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3060',`name`= 'Tatarstan',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3061',`name`= 'Tjumen',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3062',`name`= 'Tomsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3063',`name`= 'Tula',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3064',`name`= 'Tver',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3065',`name`= 'Tyva',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3066',`name`= 'Udmurtija',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3067',`name`= 'Uljanovsk',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3068',`name`= 'Ulyanovskaya Oblast',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3069',`name`= 'Ust-Orda',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3070',`name`= 'Vladimir',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3071',`name`= 'Volgograd',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3072',`name`= 'Vologda',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3073',`name`= 'Voronezh',`country_id`= '181',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3074',`name`= 'Butare',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3075',`name`= 'Byumba',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3076',`name`= 'Cyangugu',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3077',`name`= 'Gikongoro',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3078',`name`= 'Gisenyi',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3079',`name`= 'Gitarama',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3080',`name`= 'Kibungo',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3081',`name`= 'Kibuye',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3082',`name`= 'Kigali-ngali',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3083',`name`= 'Ruhengeri',`country_id`= '182',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3084',`name`= 'Ascension',`country_id`= '183',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3085',`name`= 'Gough Island',`country_id`= '183',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3086',`name`= 'Saint Helena',`country_id`= '183',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3087',`name`= 'Tristan da Cunha',`country_id`= '183',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3088',`name`= 'Christ Church Nichola Town',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3089',`name`= 'Saint Anne Sandy Point',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3090',`name`= 'Saint George Basseterre',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3091',`name`= 'Saint George Gingerland',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3092',`name`= 'Saint James Windward',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3093',`name`= 'Saint John Capesterre',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3094',`name`= 'Saint John Figtree',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3095',`name`= 'Saint Mary Cayon',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3096',`name`= 'Saint Paul Capesterre',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3097',`name`= 'Saint Paul Charlestown',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3098',`name`= 'Saint Peter Basseterre',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3099',`name`= 'Saint Thomas Lowland',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3100',`name`= 'Saint Thomas Middle Island',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3101',`name`= 'Trinity Palmetto Point',`country_id`= '184',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3102',`name`= 'Anse-la-Raye',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3103',`name`= 'Canaries',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3104',`name`= 'Castries',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3105',`name`= 'Choiseul',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3106',`name`= 'Dennery',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3107',`name`= 'Gros Inlet',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3108',`name`= 'Laborie',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3109',`name`= 'Micoud',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3110',`name`= 'Soufriere',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3111',`name`= 'Vieux Fort',`country_id`= '185',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3112',`name`= 'Miquelon-Langlade',`country_id`= '186',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3113',`name`= 'Saint-Pierre',`country_id`= '186',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3114',`name`= 'Charlotte',`country_id`= '187',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3115',`name`= 'Grenadines',`country_id`= '187',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3116',`name`= 'Saint Andrew',`country_id`= '187',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3117',`name`= 'Saint David',`country_id`= '187',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3118',`name`= 'Saint George',`country_id`= '187',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3119',`name`= 'Saint Patrick',`country_id`= '187',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3120',`name`= 'A\'ana',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3121',`name`= 'Aiga-i-le-Tai',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3122',`name`= 'Atua',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3123',`name`= 'Fa\'asaleleaga',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3124',`name`= 'Gaga\'emauga',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3125',`name`= 'Gagaifomauga',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3126',`name`= 'Palauli',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3127',`name`= 'Satupa\'itea',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3128',`name`= 'Tuamasaga',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3129',`name`= 'Va\'a-o-Fonoti',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3130',`name`= 'Vaisigano',`country_id`= '188',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3131',`name`= 'Acquaviva',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3132',`name`= 'Borgo Maggiore',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3133',`name`= 'Chiesanuova',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3134',`name`= 'Domagnano',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3135',`name`= 'Faetano',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3136',`name`= 'Fiorentino',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3137',`name`= 'Montegiardino',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3138',`name`= 'San Marino',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3139',`name`= 'Serravalle',`country_id`= '189',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3140',`name`= 'Agua Grande',`country_id`= '190',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3141',`name`= 'Cantagalo',`country_id`= '190',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3142',`name`= 'Lemba',`country_id`= '190',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3143',`name`= 'Lobata',`country_id`= '190',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3144',`name`= 'Me-Zochi',`country_id`= '190',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3145',`name`= 'Pague',`country_id`= '190',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3146',`name`= 'Al Khobar',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3147',`name`= 'Aseer',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3148',`name`= 'Ash Sharqiyah',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3149',`name`= 'Asir',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3150',`name`= 'Central Province',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3151',`name`= 'Eastern Province',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3152',`name`= 'Ha\'il',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3153',`name`= 'Jawf',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3154',`name`= 'Jizan',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3155',`name`= 'Makkah',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3156',`name`= 'Najran',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3157',`name`= 'Qasim',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3158',`name`= 'Tabuk',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3159',`name`= 'Western Province',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3160',`name`= 'al-Bahah',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3161',`name`= 'al-Hudud-ash-Shamaliyah',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3162',`name`= 'al-Madinah',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3163',`name`= 'ar-Riyad',`country_id`= '191',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3164',`name`= 'Dakar',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3165',`name`= 'Diourbel',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3166',`name`= 'Fatick',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3167',`name`= 'Kaolack',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3168',`name`= 'Kolda',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3169',`name`= 'Louga',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3170',`name`= 'Saint-Louis',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3171',`name`= 'Tambacounda',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3172',`name`= 'Thies',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3173',`name`= 'Ziguinchor',`country_id`= '192',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3174',`name`= 'Central Serbia',`country_id`= '193',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3175',`name`= 'Kosovo and Metohija',`country_id`= '193',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3176',`name`= 'Vojvodina',`country_id`= '193',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3177',`name`= 'Anse Boileau',`country_id`= '194',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3178',`name`= 'Anse Royale',`country_id`= '194',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3179',`name`= 'Cascade',`country_id`= '194',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3180',`name`= 'Takamaka',`country_id`= '194',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3181',`name`= 'Victoria',`country_id`= '194',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3182',`name`= 'Eastern',`country_id`= '195',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3183',`name`= 'Northern',`country_id`= '195',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3184',`name`= 'Southern',`country_id`= '195',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3185',`name`= 'Western',`country_id`= '195',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3186',`name`= 'Singapore',`country_id`= '196',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3187',`name`= 'Banskobystricky',`country_id`= '197',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3188',`name`= 'Bratislavsky',`country_id`= '197',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3189',`name`= 'Kosicky',`country_id`= '197',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3190',`name`= 'Nitriansky',`country_id`= '197',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3191',`name`= 'Presovsky',`country_id`= '197',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3192',`name`= 'Trenciansky',`country_id`= '197',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3193',`name`= 'Trnavsky',`country_id`= '197',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3194',`name`= 'Zilinsky',`country_id`= '197',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3195',`name`= 'Benedikt',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3196',`name`= 'Gorenjska',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3197',`name`= 'Gorishka',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3198',`name`= 'Jugovzhodna Slovenija',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3199',`name`= 'Koroshka',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3200',`name`= 'Notranjsko-krashka',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3201',`name`= 'Obalno-krashka',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3202',`name`= 'Obcina Domzale',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3203',`name`= 'Obcina Vitanje',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3204',`name`= 'Osrednjeslovenska',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3205',`name`= 'Podravska',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3206',`name`= 'Pomurska',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3207',`name`= 'Savinjska',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3208',`name`= 'Slovenian Littoral',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3209',`name`= 'Spodnjeposavska',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3210',`name`= 'Zasavska',`country_id`= '198',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3211',`name`= 'Pitcairn',`country_id`= '199',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3212',`name`= 'Central',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3213',`name`= 'Choiseul',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3214',`name`= 'Guadalcanal',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3215',`name`= 'Isabel',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3216',`name`= 'Makira and Ulawa',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3217',`name`= 'Malaita',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3218',`name`= 'Rennell and Bellona',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3219',`name`= 'Temotu',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3220',`name`= 'Western',`country_id`= '200',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3221',`name`= 'Awdal',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3222',`name`= 'Bakol',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3223',`name`= 'Banadir',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3224',`name`= 'Bari',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3225',`name`= 'Bay',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3226',`name`= 'Galgudug',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3227',`name`= 'Gedo',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3228',`name`= 'Hiran',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3229',`name`= 'Jubbada Hose',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3230',`name`= 'Jubbadha Dexe',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3231',`name`= 'Mudug',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3232',`name`= 'Nugal',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3233',`name`= 'Sanag',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3234',`name`= 'Shabellaha Dhexe',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3235',`name`= 'Shabellaha Hose',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3236',`name`= 'Togdher',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3237',`name`= 'Woqoyi Galbed',`country_id`= '201',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3238',`name`= 'Eastern Cape',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3239',`name`= 'Free State',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3240',`name`= 'Gauteng',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3241',`name`= 'Kempton Park',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3242',`name`= 'Kramerville',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3243',`name`= 'KwaZulu Natal',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3244',`name`= 'Limpopo',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3245',`name`= 'Mpumalanga',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3246',`name`= 'North West',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3247',`name`= 'Northern Cape',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3248',`name`= 'Parow',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3249',`name`= 'Table View',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3250',`name`= 'Umtentweni',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3251',`name`= 'Western Cape',`country_id`= '202',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3252',`name`= 'South Georgia',`country_id`= '203',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3253',`name`= 'Central Equatoria',`country_id`= '204',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3254',`name`= 'A Coruna',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3255',`name`= 'Alacant',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3256',`name`= 'Alava',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3257',`name`= 'Albacete',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3258',`name`= 'Almeria',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3259',`name`= 'Andalucia',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3260',`name`= 'Asturias',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3261',`name`= 'Avila',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3262',`name`= 'Badajoz',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3263',`name`= 'Balears',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3264',`name`= 'Barcelona',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3265',`name`= 'Bertamirans',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3266',`name`= 'Biscay',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3267',`name`= 'Burgos',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3268',`name`= 'Caceres',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3269',`name`= 'Cadiz',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3270',`name`= 'Cantabria',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3271',`name`= 'Castello',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3272',`name`= 'Catalunya',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3273',`name`= 'Ceuta',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3274',`name`= 'Ciudad Real',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3275',`name`= 'Comunidad Autonoma de Canarias',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3276',`name`= 'Comunidad Autonoma de Cataluna',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3277',`name`= 'Comunidad Autonoma de Galicia',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3278',`name`= 'Comunidad Autonoma de las Isla',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3279',`name`= 'Comunidad Autonoma del Princip',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3280',`name`= 'Comunidad Valenciana',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3281',`name`= 'Cordoba',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3282',`name`= 'Cuenca',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3283',`name`= 'Gipuzkoa',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3284',`name`= 'Girona',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3285',`name`= 'Granada',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3286',`name`= 'Guadalajara',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3287',`name`= 'Guipuzcoa',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3288',`name`= 'Huelva',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3289',`name`= 'Huesca',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3290',`name`= 'Jaen',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3291',`name`= 'La Rioja',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3292',`name`= 'Las Palmas',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3293',`name`= 'Leon',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3294',`name`= 'Lerida',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3295',`name`= 'Lleida',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3296',`name`= 'Lugo',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3297',`name`= 'Madrid',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3298',`name`= 'Malaga',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3299',`name`= 'Melilla',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3300',`name`= 'Murcia',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3301',`name`= 'Navarra',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3302',`name`= 'Ourense',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3303',`name`= 'Pais Vasco',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3304',`name`= 'Palencia',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3305',`name`= 'Pontevedra',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3306',`name`= 'Salamanca',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3307',`name`= 'Santa Cruz de Tenerife',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3308',`name`= 'Segovia',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3309',`name`= 'Sevilla',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3310',`name`= 'Soria',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3311',`name`= 'Tarragona',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3312',`name`= 'Tenerife',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3313',`name`= 'Teruel',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3314',`name`= 'Toledo',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3315',`name`= 'Valencia',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3316',`name`= 'Valladolid',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3317',`name`= 'Vizcaya',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3318',`name`= 'Zamora',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3319',`name`= 'Zaragoza',`country_id`= '205',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3320',`name`= 'Amparai',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3321',`name`= 'Anuradhapuraya',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3322',`name`= 'Badulla',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3323',`name`= 'Boralesgamuwa',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3324',`name`= 'Colombo',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3325',`name`= 'Galla',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3326',`name`= 'Gampaha',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3327',`name`= 'Hambantota',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3328',`name`= 'Kalatura',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3329',`name`= 'Kegalla',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3330',`name`= 'Kilinochchi',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3331',`name`= 'Kurunegala',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3332',`name`= 'Madakalpuwa',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3333',`name`= 'Maha Nuwara',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3334',`name`= 'Malwana',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3335',`name`= 'Mannarama',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3336',`name`= 'Matale',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3337',`name`= 'Matara',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3338',`name`= 'Monaragala',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3339',`name`= 'Mullaitivu',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3340',`name`= 'North Eastern Province',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3341',`name`= 'North Western Province',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3342',`name`= 'Nuwara Eliya',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3343',`name`= 'Polonnaruwa',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3344',`name`= 'Puttalama',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3345',`name`= 'Ratnapuraya',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3346',`name`= 'Southern Province',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3347',`name`= 'Tirikunamalaya',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3348',`name`= 'Tuscany',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3349',`name`= 'Vavuniyawa',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3350',`name`= 'Western Province',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3351',`name`= 'Yapanaya',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3352',`name`= 'kadawatha',`country_id`= '206',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3353',`name`= 'A\'ali-an-Nil',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3354',`name`= 'Bahr-al-Jabal',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3355',`name`= 'Central Equatoria',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3356',`name`= 'Gharb Bahr-al-Ghazal',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3357',`name`= 'Gharb Darfur',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3358',`name`= 'Gharb Kurdufan',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3359',`name`= 'Gharb-al-Istiwa\'iyah',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3360',`name`= 'Janub Darfur',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3361',`name`= 'Janub Kurdufan',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3362',`name`= 'Junqali',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3363',`name`= 'Kassala',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3364',`name`= 'Nahr-an-Nil',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3365',`name`= 'Shamal Bahr-al-Ghazal',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3366',`name`= 'Shamal Darfur',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3367',`name`= 'Shamal Kurdufan',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3368',`name`= 'Sharq-al-Istiwa\'iyah',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3369',`name`= 'Sinnar',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3370',`name`= 'Warab',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3371',`name`= 'Wilayat al Khartum',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3372',`name`= 'al-Bahr-al-Ahmar',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3373',`name`= 'al-Buhayrat',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3374',`name`= 'al-Jazirah',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3375',`name`= 'al-Khartum',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3376',`name`= 'al-Qadarif',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3377',`name`= 'al-Wahdah',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3378',`name`= 'an-Nil-al-Abyad',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3379',`name`= 'an-Nil-al-Azraq',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3380',`name`= 'ash-Shamaliyah',`country_id`= '207',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3381',`name`= 'Brokopondo',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3382',`name`= 'Commewijne',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3383',`name`= 'Coronie',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3384',`name`= 'Marowijne',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3385',`name`= 'Nickerie',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3386',`name`= 'Para',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3387',`name`= 'Paramaribo',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3388',`name`= 'Saramacca',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3389',`name`= 'Wanica',`country_id`= '208',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3390',`name`= 'Svalbard',`country_id`= '209',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3391',`name`= 'Hhohho',`country_id`= '210',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3392',`name`= 'Lubombo',`country_id`= '210',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3393',`name`= 'Manzini',`country_id`= '210',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3394',`name`= 'Shiselweni',`country_id`= '210',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3395',`name`= 'Alvsborgs Lan',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3396',`name`= 'Angermanland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3397',`name`= 'Blekinge',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3398',`name`= 'Bohuslan',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3399',`name`= 'Dalarna',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3400',`name`= 'Gavleborg',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3401',`name`= 'Gaza',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3402',`name`= 'Gotland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3403',`name`= 'Halland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3404',`name`= 'Jamtland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3405',`name`= 'Jonkoping',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3406',`name`= 'Kalmar',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3407',`name`= 'Kristianstads',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3408',`name`= 'Kronoberg',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3409',`name`= 'Norrbotten',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3410',`name`= 'Orebro',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3411',`name`= 'Ostergotland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3412',`name`= 'Saltsjo-Boo',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3413',`name`= 'Skane',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3414',`name`= 'Smaland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3415',`name`= 'Sodermanland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3416',`name`= 'Stockholm',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3417',`name`= 'Uppsala',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3418',`name`= 'Varmland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3419',`name`= 'Vasterbotten',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3420',`name`= 'Vastergotland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3421',`name`= 'Vasternorrland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3422',`name`= 'Vastmanland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3423',`name`= 'Vastra Gotaland',`country_id`= '211',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3424',`name`= 'Aargau',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3425',`name`= 'Appenzell Inner-Rhoden',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3426',`name`= 'Appenzell-Ausser Rhoden',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3427',`name`= 'Basel-Landschaft',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3428',`name`= 'Basel-Stadt',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3429',`name`= 'Bern',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3430',`name`= 'Canton Ticino',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3431',`name`= 'Fribourg',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3432',`name`= 'Geneve',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3433',`name`= 'Glarus',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3434',`name`= 'Graubunden',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3435',`name`= 'Heerbrugg',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3436',`name`= 'Jura',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3437',`name`= 'Kanton Aargau',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3438',`name`= 'Luzern',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3439',`name`= 'Morbio Inferiore',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3440',`name`= 'Muhen',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3441',`name`= 'Neuchatel',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3442',`name`= 'Nidwalden',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3443',`name`= 'Obwalden',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3444',`name`= 'Sankt Gallen',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3445',`name`= 'Schaffhausen',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3446',`name`= 'Schwyz',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3447',`name`= 'Solothurn',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3448',`name`= 'Thurgau',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3449',`name`= 'Ticino',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3450',`name`= 'Uri',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3451',`name`= 'Valais',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3452',`name`= 'Vaud',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3453',`name`= 'Vauffelin',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3454',`name`= 'Zug',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3455',`name`= 'Zurich',`country_id`= '212',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3456',`name`= 'Aleppo',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3457',`name`= 'Dar\'a',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3458',`name`= 'Dayr-az-Zawr',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3459',`name`= 'Dimashq',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3460',`name`= 'Halab',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3461',`name`= 'Hamah',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3462',`name`= 'Hims',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3463',`name`= 'Idlib',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3464',`name`= 'Madinat Dimashq',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3465',`name`= 'Tartus',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3466',`name`= 'al-Hasakah',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3467',`name`= 'al-Ladhiqiyah',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3468',`name`= 'al-Qunaytirah',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3469',`name`= 'ar-Raqqah',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3470',`name`= 'as-Suwayda',`country_id`= '213',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3471',`name`= 'Changhwa',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3472',`name`= 'Chiayi Hsien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3473',`name`= 'Chiayi Shih',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3474',`name`= 'Eastern Taipei',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3475',`name`= 'Hsinchu Hsien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3476',`name`= 'Hsinchu Shih',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3477',`name`= 'Hualien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3478',`name`= 'Ilan',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3479',`name`= 'Kaohsiung Hsien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3480',`name`= 'Kaohsiung Shih',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3481',`name`= 'Keelung Shih',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3482',`name`= 'Kinmen',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3483',`name`= 'Miaoli',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3484',`name`= 'Nantou',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3485',`name`= 'Northern Taiwan',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3486',`name`= 'Penghu',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3487',`name`= 'Pingtung',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3488',`name`= 'Taichung',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3489',`name`= 'Taichung Hsien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3490',`name`= 'Taichung Shih',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3491',`name`= 'Tainan Hsien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3492',`name`= 'Tainan Shih',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3493',`name`= 'Taipei Hsien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3494',`name`= 'Taipei Shih / Taipei Hsien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3495',`name`= 'Taitung',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3496',`name`= 'Taoyuan',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3497',`name`= 'Yilan',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3498',`name`= 'Yun-Lin Hsien',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3499',`name`= 'Yunlin',`country_id`= '214',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3500',`name`= 'Dushanbe',`country_id`= '215',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3501',`name`= 'Gorno-Badakhshan',`country_id`= '215',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3502',`name`= 'Karotegin',`country_id`= '215',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3503',`name`= 'Khatlon',`country_id`= '215',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3504',`name`= 'Sughd',`country_id`= '215',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3505',`name`= 'Arusha',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3506',`name`= 'Dar es Salaam',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3507',`name`= 'Dodoma',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3508',`name`= 'Iringa',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3509',`name`= 'Kagera',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3510',`name`= 'Kigoma',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3511',`name`= 'Kilimanjaro',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3512',`name`= 'Lindi',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3513',`name`= 'Mara',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3514',`name`= 'Mbeya',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3515',`name`= 'Morogoro',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3516',`name`= 'Mtwara',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3517',`name`= 'Mwanza',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3518',`name`= 'Pwani',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3519',`name`= 'Rukwa',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3520',`name`= 'Ruvuma',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3521',`name`= 'Shinyanga',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3522',`name`= 'Singida',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3523',`name`= 'Tabora',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3524',`name`= 'Tanga',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3525',`name`= 'Zanzibar and Pemba',`country_id`= '216',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3526',`name`= 'Amnat Charoen',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3527',`name`= 'Ang Thong',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3528',`name`= 'Bangkok',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3529',`name`= 'Buri Ram',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3530',`name`= 'Chachoengsao',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3531',`name`= 'Chai Nat',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3532',`name`= 'Chaiyaphum',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3533',`name`= 'Changwat Chaiyaphum',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3534',`name`= 'Chanthaburi',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3535',`name`= 'Chiang Mai',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3536',`name`= 'Chiang Rai',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3537',`name`= 'Chon Buri',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3538',`name`= 'Chumphon',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3539',`name`= 'Kalasin',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3540',`name`= 'Kamphaeng Phet',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3541',`name`= 'Kanchanaburi',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3542',`name`= 'Khon Kaen',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3543',`name`= 'Krabi',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3544',`name`= 'Krung Thep',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3545',`name`= 'Lampang',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3546',`name`= 'Lamphun',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3547',`name`= 'Loei',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3548',`name`= 'Lop Buri',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3549',`name`= 'Mae Hong Son',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3550',`name`= 'Maha Sarakham',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3551',`name`= 'Mukdahan',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3552',`name`= 'Nakhon Nayok',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3553',`name`= 'Nakhon Pathom',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3554',`name`= 'Nakhon Phanom',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3555',`name`= 'Nakhon Ratchasima',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3556',`name`= 'Nakhon Sawan',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3557',`name`= 'Nakhon Si Thammarat',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3558',`name`= 'Nan',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3559',`name`= 'Narathiwat',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3560',`name`= 'Nong Bua Lam Phu',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3561',`name`= 'Nong Khai',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3562',`name`= 'Nonthaburi',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3563',`name`= 'Pathum Thani',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3564',`name`= 'Pattani',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3565',`name`= 'Phangnga',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3566',`name`= 'Phatthalung',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3567',`name`= 'Phayao',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3568',`name`= 'Phetchabun',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3569',`name`= 'Phetchaburi',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3570',`name`= 'Phichit',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3571',`name`= 'Phitsanulok',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3572',`name`= 'Phra Nakhon Si Ayutthaya',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3573',`name`= 'Phrae',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3574',`name`= 'Phuket',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3575',`name`= 'Prachin Buri',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3576',`name`= 'Prachuap Khiri Khan',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3577',`name`= 'Ranong',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3578',`name`= 'Ratchaburi',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3579',`name`= 'Rayong',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3580',`name`= 'Roi Et',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3581',`name`= 'Sa Kaeo',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3582',`name`= 'Sakon Nakhon',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3583',`name`= 'Samut Prakan',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3584',`name`= 'Samut Sakhon',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3585',`name`= 'Samut Songkhran',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3586',`name`= 'Saraburi',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3587',`name`= 'Satun',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3588',`name`= 'Si Sa Ket',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3589',`name`= 'Sing Buri',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3590',`name`= 'Songkhla',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3591',`name`= 'Sukhothai',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3592',`name`= 'Suphan Buri',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3593',`name`= 'Surat Thani',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3594',`name`= 'Surin',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3595',`name`= 'Tak',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3596',`name`= 'Trang',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3597',`name`= 'Trat',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3598',`name`= 'Ubon Ratchathani',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3599',`name`= 'Udon Thani',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3600',`name`= 'Uthai Thani',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3601',`name`= 'Uttaradit',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3602',`name`= 'Yala',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3603',`name`= 'Yasothon',`country_id`= '217',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3604',`name`= 'Centre',`country_id`= '218',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3605',`name`= 'Kara',`country_id`= '218',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3606',`name`= 'Maritime',`country_id`= '218',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3607',`name`= 'Plateaux',`country_id`= '218',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3608',`name`= 'Savanes',`country_id`= '218',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3609',`name`= 'Atafu',`country_id`= '219',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3610',`name`= 'Fakaofo',`country_id`= '219',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3611',`name`= 'Nukunonu',`country_id`= '219',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3612',`name`= 'Eua',`country_id`= '220',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3613',`name`= 'Ha\'apai',`country_id`= '220',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3614',`name`= 'Niuas',`country_id`= '220',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3615',`name`= 'Tongatapu',`country_id`= '220',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3616',`name`= 'Vava\'u',`country_id`= '220',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3617',`name`= 'Arima-Tunapuna-Piarco',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3618',`name`= 'Caroni',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3619',`name`= 'Chaguanas',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3620',`name`= 'Couva-Tabaquite-Talparo',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3621',`name`= 'Diego Martin',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3622',`name`= 'Glencoe',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3623',`name`= 'Penal Debe',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3624',`name`= 'Point Fortin',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3625',`name`= 'Port of Spain',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3626',`name`= 'Princes Town',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3627',`name`= 'Saint George',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3628',`name`= 'San Fernando',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3629',`name`= 'San Juan',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3630',`name`= 'Sangre Grande',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3631',`name`= 'Siparia',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3632',`name`= 'Tobago',`country_id`= '221',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3633',`name`= 'Aryanah',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3634',`name`= 'Bajah',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3635',`name`= 'Bin \'Arus',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3636',`name`= 'Binzart',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3637',`name`= 'Gouvernorat de Ariana',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3638',`name`= 'Gouvernorat de Nabeul',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3639',`name`= 'Gouvernorat de Sousse',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3640',`name`= 'Hammamet Yasmine',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3641',`name`= 'Jundubah',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3642',`name`= 'Madaniyin',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3643',`name`= 'Manubah',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3644',`name`= 'Monastir',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3645',`name`= 'Nabul',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3646',`name`= 'Qabis',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3647',`name`= 'Qafsah',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3648',`name`= 'Qibili',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3649',`name`= 'Safaqis',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3650',`name`= 'Sfax',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3651',`name`= 'Sidi Bu Zayd',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3652',`name`= 'Silyanah',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3653',`name`= 'Susah',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3654',`name`= 'Tatawin',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3655',`name`= 'Tawzar',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3656',`name`= 'Tunis',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3657',`name`= 'Zaghwan',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3658',`name`= 'al-Kaf',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3659',`name`= 'al-Mahdiyah',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3660',`name`= 'al-Munastir',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3661',`name`= 'al-Qasrayn',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3662',`name`= 'al-Qayrawan',`country_id`= '222',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3663',`name`= 'Adana',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3664',`name`= 'Adiyaman',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3665',`name`= 'Afyon',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3666',`name`= 'Agri',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3667',`name`= 'Aksaray',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3668',`name`= 'Amasya',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3669',`name`= 'Ankara',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3670',`name`= 'Antalya',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3671',`name`= 'Ardahan',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3672',`name`= 'Artvin',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3673',`name`= 'Aydin',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3674',`name`= 'Balikesir',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3675',`name`= 'Bartin',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3676',`name`= 'Batman',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3677',`name`= 'Bayburt',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3678',`name`= 'Bilecik',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3679',`name`= 'Bingol',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3680',`name`= 'Bitlis',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3681',`name`= 'Bolu',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3682',`name`= 'Burdur',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3683',`name`= 'Bursa',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3684',`name`= 'Canakkale',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3685',`name`= 'Cankiri',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3686',`name`= 'Corum',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3687',`name`= 'Denizli',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3688',`name`= 'Diyarbakir',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3689',`name`= 'Duzce',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3690',`name`= 'Edirne',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3691',`name`= 'Elazig',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3692',`name`= 'Erzincan',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3693',`name`= 'Erzurum',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3694',`name`= 'Eskisehir',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3695',`name`= 'Gaziantep',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3696',`name`= 'Giresun',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3697',`name`= 'Gumushane',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3698',`name`= 'Hakkari',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3699',`name`= 'Hatay',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3700',`name`= 'Icel',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3701',`name`= 'Igdir',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3702',`name`= 'Isparta',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3703',`name`= 'Istanbul',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3704',`name`= 'Izmir',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3705',`name`= 'Kahramanmaras',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3706',`name`= 'Karabuk',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3707',`name`= 'Karaman',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3708',`name`= 'Kars',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3709',`name`= 'Karsiyaka',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3710',`name`= 'Kastamonu',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3711',`name`= 'Kayseri',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3712',`name`= 'Kilis',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3713',`name`= 'Kirikkale',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3714',`name`= 'Kirklareli',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3715',`name`= 'Kirsehir',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3716',`name`= 'Kocaeli',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3717',`name`= 'Konya',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3718',`name`= 'Kutahya',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3719',`name`= 'Lefkosa',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3720',`name`= 'Malatya',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3721',`name`= 'Manisa',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3722',`name`= 'Mardin',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3723',`name`= 'Mugla',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3724',`name`= 'Mus',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3725',`name`= 'Nevsehir',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3726',`name`= 'Nigde',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3727',`name`= 'Ordu',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3728',`name`= 'Osmaniye',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3729',`name`= 'Rize',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3730',`name`= 'Sakarya',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3731',`name`= 'Samsun',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3732',`name`= 'Sanliurfa',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3733',`name`= 'Siirt',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3734',`name`= 'Sinop',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3735',`name`= 'Sirnak',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3736',`name`= 'Sivas',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3737',`name`= 'Tekirdag',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3738',`name`= 'Tokat',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3739',`name`= 'Trabzon',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3740',`name`= 'Tunceli',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3741',`name`= 'Usak',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3742',`name`= 'Van',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3743',`name`= 'Yalova',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3744',`name`= 'Yozgat',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3745',`name`= 'Zonguldak',`country_id`= '223',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3746',`name`= 'Ahal',`country_id`= '224',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3747',`name`= 'Asgabat',`country_id`= '224',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3748',`name`= 'Balkan',`country_id`= '224',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3749',`name`= 'Dasoguz',`country_id`= '224',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3750',`name`= 'Lebap',`country_id`= '224',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3751',`name`= 'Mari',`country_id`= '224',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3752',`name`= 'Grand Turk',`country_id`= '225',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3753',`name`= 'South Caicos and East Caicos',`country_id`= '225',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3754',`name`= 'Funafuti',`country_id`= '226',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3755',`name`= 'Nanumanga',`country_id`= '226',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3756',`name`= 'Nanumea',`country_id`= '226',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3757',`name`= 'Niutao',`country_id`= '226',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3758',`name`= 'Nui',`country_id`= '226',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3759',`name`= 'Nukufetau',`country_id`= '226',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3760',`name`= 'Nukulaelae',`country_id`= '226',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3761',`name`= 'Vaitupu',`country_id`= '226',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3762',`name`= 'Central',`country_id`= '227',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3763',`name`= 'Eastern',`country_id`= '227',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3764',`name`= 'Northern',`country_id`= '227',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3765',`name`= 'Western',`country_id`= '227',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3766',`name`= 'Cherkas\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3767',`name`= 'Chernihivs\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3768',`name`= 'Chernivets\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3769',`name`= 'Crimea',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3770',`name`= 'Dnipropetrovska',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3771',`name`= 'Donets\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3772',`name`= 'Ivano-Frankivs\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3773',`name`= 'Kharkiv',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3774',`name`= 'Kharkov',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3775',`name`= 'Khersonska',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3776',`name`= 'Khmel\'nyts\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3777',`name`= 'Kirovohrad',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3778',`name`= 'Krym',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3779',`name`= 'Kyyiv',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3780',`name`= 'Kyyivs\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3781',`name`= 'L\'vivs\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3782',`name`= 'Luhans\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3783',`name`= 'Mykolayivs\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3784',`name`= 'Odes\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3785',`name`= 'Odessa',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3786',`name`= 'Poltavs\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3787',`name`= 'Rivnens\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3788',`name`= 'Sevastopol\'',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3789',`name`= 'Sums\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3790',`name`= 'Ternopil\'s\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3791',`name`= 'Volyns\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3792',`name`= 'Vynnyts\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3793',`name`= 'Zakarpats\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3794',`name`= 'Zaporizhia',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3795',`name`= 'Zhytomyrs\'ka',`country_id`= '228',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3796',`name`= 'Abu Zabi',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3797',`name`= 'Ajman',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3798',`name`= 'Dubai',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3799',`name`= 'Ras al-Khaymah',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3800',`name`= 'Sharjah',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3801',`name`= 'Sharjha',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3802',`name`= 'Umm al Qaywayn',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3803',`name`= 'al-Fujayrah',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3804',`name`= 'ash-Shariqah',`country_id`= '229',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3805',`name`= 'Aberdeen',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3806',`name`= 'Aberdeenshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3807',`name`= 'Argyll',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3808',`name`= 'Armagh',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3809',`name`= 'Bedfordshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3810',`name`= 'Belfast',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3811',`name`= 'Berkshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3812',`name`= 'Birmingham',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3813',`name`= 'Brechin',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3814',`name`= 'Bridgnorth',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3815',`name`= 'Bristol',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3816',`name`= 'Buckinghamshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3817',`name`= 'Cambridge',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3818',`name`= 'Cambridgeshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3819',`name`= 'Channel Islands',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3820',`name`= 'Cheshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3821',`name`= 'Cleveland',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3822',`name`= 'Co Fermanagh',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3823',`name`= 'Conwy',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3824',`name`= 'Cornwall',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3825',`name`= 'Coventry',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3826',`name`= 'Craven Arms',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3827',`name`= 'Cumbria',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3828',`name`= 'Denbighshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3829',`name`= 'Derby',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3830',`name`= 'Derbyshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3831',`name`= 'Devon',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3832',`name`= 'Dial Code Dungannon',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3833',`name`= 'Didcot',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3834',`name`= 'Dorset',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3835',`name`= 'Dunbartonshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3836',`name`= 'Durham',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3837',`name`= 'East Dunbartonshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3838',`name`= 'East Lothian',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3839',`name`= 'East Midlands',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3840',`name`= 'East Sussex',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3841',`name`= 'East Yorkshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3842',`name`= 'England',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3843',`name`= 'Essex',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3844',`name`= 'Fermanagh',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3845',`name`= 'Fife',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3846',`name`= 'Flintshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3847',`name`= 'Fulham',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3848',`name`= 'Gainsborough',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3849',`name`= 'Glocestershire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3850',`name`= 'Gwent',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3851',`name`= 'Hampshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3852',`name`= 'Hants',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3853',`name`= 'Herefordshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3854',`name`= 'Hertfordshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3855',`name`= 'Ireland',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3856',`name`= 'Isle Of Man',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3857',`name`= 'Isle of Wight',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3858',`name`= 'Kenford',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3859',`name`= 'Kent',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3860',`name`= 'Kilmarnock',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3861',`name`= 'Lanarkshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3862',`name`= 'Lancashire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3863',`name`= 'Leicestershire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3864',`name`= 'Lincolnshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3865',`name`= 'Llanymynech',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3866',`name`= 'London',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3867',`name`= 'Ludlow',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3868',`name`= 'Manchester',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3869',`name`= 'Mayfair',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3870',`name`= 'Merseyside',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3871',`name`= 'Mid Glamorgan',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3872',`name`= 'Middlesex',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3873',`name`= 'Mildenhall',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3874',`name`= 'Monmouthshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3875',`name`= 'Newton Stewart',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3876',`name`= 'Norfolk',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3877',`name`= 'North Humberside',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3878',`name`= 'North Yorkshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3879',`name`= 'Northamptonshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3880',`name`= 'Northants',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3881',`name`= 'Northern Ireland',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3882',`name`= 'Northumberland',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3883',`name`= 'Nottinghamshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3884',`name`= 'Oxford',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3885',`name`= 'Powys',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3886',`name`= 'Roos-shire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3887',`name`= 'SUSSEX',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3888',`name`= 'Sark',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3889',`name`= 'Scotland',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3890',`name`= 'Scottish Borders',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3891',`name`= 'Shropshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3892',`name`= 'Somerset',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3893',`name`= 'South Glamorgan',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3894',`name`= 'South Wales',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3895',`name`= 'South Yorkshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3896',`name`= 'Southwell',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3897',`name`= 'Staffordshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3898',`name`= 'Strabane',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3899',`name`= 'Suffolk',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3900',`name`= 'Surrey',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3901',`name`= 'Sussex',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3902',`name`= 'Twickenham',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3903',`name`= 'Tyne and Wear',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3904',`name`= 'Tyrone',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3905',`name`= 'Utah',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3906',`name`= 'Wales',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3907',`name`= 'Warwickshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3908',`name`= 'West Lothian',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3909',`name`= 'West Midlands',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3910',`name`= 'West Sussex',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3911',`name`= 'West Yorkshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3912',`name`= 'Whissendine',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3913',`name`= 'Wiltshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3914',`name`= 'Wokingham',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3915',`name`= 'Worcestershire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3916',`name`= 'Wrexham',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3917',`name`= 'Wurttemberg',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3918',`name`= 'Yorkshire',`country_id`= '230',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3919',`name`= 'Alabama',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3920',`name`= 'Alaska',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3921',`name`= 'Arizona',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3922',`name`= 'Arkansas',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3923',`name`= 'Byram',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3924',`name`= 'California',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3925',`name`= 'Cokato',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3926',`name`= 'Colorado',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3927',`name`= 'Connecticut',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3928',`name`= 'Delaware',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3929',`name`= 'District of Columbia',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3930',`name`= 'Florida',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3931',`name`= 'Georgia',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3932',`name`= 'Hawaii',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3933',`name`= 'Idaho',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3934',`name`= 'Illinois',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3935',`name`= 'Indiana',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3936',`name`= 'Iowa',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3937',`name`= 'Kansas',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3938',`name`= 'Kentucky',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3939',`name`= 'Louisiana',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3940',`name`= 'Lowa',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3941',`name`= 'Maine',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3942',`name`= 'Maryland',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3943',`name`= 'Massachusetts',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3944',`name`= 'Medfield',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3945',`name`= 'Michigan',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3946',`name`= 'Minnesota',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3947',`name`= 'Mississippi',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3948',`name`= 'Missouri',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3949',`name`= 'Montana',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3950',`name`= 'Nebraska',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3951',`name`= 'Nevada',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3952',`name`= 'New Hampshire',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3953',`name`= 'New Jersey',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3954',`name`= 'New Jersy',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3955',`name`= 'New Mexico',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3956',`name`= 'New York',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3957',`name`= 'North Carolina',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3958',`name`= 'North Dakota',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3959',`name`= 'Ohio',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3960',`name`= 'Oklahoma',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3961',`name`= 'Ontario',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3962',`name`= 'Oregon',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3963',`name`= 'Pennsylvania',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3964',`name`= 'Ramey',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3965',`name`= 'Rhode Island',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3966',`name`= 'South Carolina',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3967',`name`= 'South Dakota',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3968',`name`= 'Sublimity',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3969',`name`= 'Tennessee',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3970',`name`= 'Texas',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3971',`name`= 'Trimble',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3972',`name`= 'Utah',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3973',`name`= 'Vermont',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3974',`name`= 'Virginia',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3975',`name`= 'Washington',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3976',`name`= 'West Virginia',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3977',`name`= 'Wisconsin',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3978',`name`= 'Wyoming',`country_id`= '231',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3979',`name`= 'United States Minor Outlying I',`country_id`= '232',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3980',`name`= 'Artigas',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3981',`name`= 'Canelones',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3982',`name`= 'Cerro Largo',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3983',`name`= 'Colonia',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3984',`name`= 'Durazno',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3985',`name`= 'FLorida',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3986',`name`= 'Flores',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3987',`name`= 'Lavalleja',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3988',`name`= 'Maldonado',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3989',`name`= 'Montevideo',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3990',`name`= 'Paysandu',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3991',`name`= 'Rio Negro',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3992',`name`= 'Rivera',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3993',`name`= 'Rocha',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3994',`name`= 'Salto',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3995',`name`= 'San Jose',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3996',`name`= 'Soriano',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3997',`name`= 'Tacuarembo',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3998',`name`= 'Treinta y Tres',`country_id`= '233',`created_by`= NULL;
INSERT INTO `states` SET `id`= '3999',`name`= 'Andijon',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4000',`name`= 'Buhoro',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4001',`name`= 'Buxoro Viloyati',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4002',`name`= 'Cizah',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4003',`name`= 'Fargona',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4004',`name`= 'Horazm',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4005',`name`= 'Kaskadar',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4006',`name`= 'Korakalpogiston',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4007',`name`= 'Namangan',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4008',`name`= 'Navoi',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4009',`name`= 'Samarkand',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4010',`name`= 'Sirdare',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4011',`name`= 'Surhondar',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4012',`name`= 'Toskent',`country_id`= '234',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4013',`name`= 'Malampa',`country_id`= '235',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4014',`name`= 'Penama',`country_id`= '235',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4015',`name`= 'Sanma',`country_id`= '235',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4016',`name`= 'Shefa',`country_id`= '235',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4017',`name`= 'Tafea',`country_id`= '235',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4018',`name`= 'Torba',`country_id`= '235',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4019',`name`= 'Vatican City State (Holy See)',`country_id`= '236',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4020',`name`= 'Amazonas',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4021',`name`= 'Anzoategui',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4022',`name`= 'Apure',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4023',`name`= 'Aragua',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4024',`name`= 'Barinas',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4025',`name`= 'Bolivar',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4026',`name`= 'Carabobo',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4027',`name`= 'Cojedes',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4028',`name`= 'Delta Amacuro',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4029',`name`= 'Distrito Federal',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4030',`name`= 'Falcon',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4031',`name`= 'Guarico',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4032',`name`= 'Lara',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4033',`name`= 'Merida',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4034',`name`= 'Miranda',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4035',`name`= 'Monagas',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4036',`name`= 'Nueva Esparta',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4037',`name`= 'Portuguesa',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4038',`name`= 'Sucre',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4039',`name`= 'Tachira',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4040',`name`= 'Trujillo',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4041',`name`= 'Vargas',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4042',`name`= 'Yaracuy',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4043',`name`= 'Zulia',`country_id`= '237',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4044',`name`= 'Bac Giang',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4045',`name`= 'Binh Dinh',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4046',`name`= 'Binh Duong',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4047',`name`= 'Da Nang',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4048',`name`= 'Dong Bang Song Cuu Long',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4049',`name`= 'Dong Bang Song Hong',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4050',`name`= 'Dong Nai',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4051',`name`= 'Dong Nam Bo',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4052',`name`= 'Duyen Hai Mien Trung',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4053',`name`= 'Hanoi',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4054',`name`= 'Hung Yen',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4055',`name`= 'Khu Bon Cu',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4056',`name`= 'Long An',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4057',`name`= 'Mien Nui Va Trung Du',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4058',`name`= 'Thai Nguyen',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4059',`name`= 'Thanh Pho Ho Chi Minh',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4060',`name`= 'Thu Do Ha Noi',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4061',`name`= 'Tinh Can Tho',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4062',`name`= 'Tinh Da Nang',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4063',`name`= 'Tinh Gia Lai',`country_id`= '238',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4064',`name`= 'Anegada',`country_id`= '239',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4065',`name`= 'Jost van Dyke',`country_id`= '239',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4066',`name`= 'Tortola',`country_id`= '239',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4067',`name`= 'Saint Croix',`country_id`= '240',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4068',`name`= 'Saint John',`country_id`= '240',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4069',`name`= 'Saint Thomas',`country_id`= '240',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4070',`name`= 'Alo',`country_id`= '241',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4071',`name`= 'Singave',`country_id`= '241',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4072',`name`= 'Wallis',`country_id`= '241',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4073',`name`= 'Bu Jaydur',`country_id`= '242',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4074',`name`= 'Wad-adh-Dhahab',`country_id`= '242',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4075',`name`= 'al-\'Ayun',`country_id`= '242',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4076',`name`= 'as-Samarah',`country_id`= '242',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4077',`name`= '\'Adan',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4078',`name`= 'Abyan',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4079',`name`= 'Dhamar',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4080',`name`= 'Hadramaut',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4081',`name`= 'Hajjah',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4082',`name`= 'Hudaydah',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4083',`name`= 'Ibb',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4084',`name`= 'Lahij',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4085',`name`= 'Ma\'rib',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4086',`name`= 'Madinat San\'a',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4087',`name`= 'Sa\'dah',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4088',`name`= 'Sana',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4089',`name`= 'Shabwah',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4090',`name`= 'Ta\'izz',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4091',`name`= 'al-Bayda',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4092',`name`= 'al-Hudaydah',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4093',`name`= 'al-Jawf',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4094',`name`= 'al-Mahrah',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4095',`name`= 'al-Mahwit',`country_id`= '243',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4096',`name`= 'Central Serbia',`country_id`= '244',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4097',`name`= 'Kosovo and Metohija',`country_id`= '244',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4098',`name`= 'Montenegro',`country_id`= '244',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4099',`name`= 'Republic of Serbia',`country_id`= '244',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4100',`name`= 'Serbia',`country_id`= '244',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4101',`name`= 'Vojvodina',`country_id`= '244',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4102',`name`= 'Central',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4103',`name`= 'Copperbelt',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4104',`name`= 'Eastern',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4105',`name`= 'Luapala',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4106',`name`= 'Lusaka',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4107',`name`= 'North-Western',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4108',`name`= 'Northern',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4109',`name`= 'Southern',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4110',`name`= 'Western',`country_id`= '245',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4111',`name`= 'Bulawayo',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4112',`name`= 'Harare',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4113',`name`= 'Manicaland',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4114',`name`= 'Mashonaland Central',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4115',`name`= 'Mashonaland East',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4116',`name`= 'Mashonaland West',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4117',`name`= 'Masvingo',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4118',`name`= 'Matabeleland North',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4119',`name`= 'Matabeleland South',`country_id`= '246',`created_by`= NULL;
INSERT INTO `states` SET `id`= '4120',`name`= 'Midlands',`country_id`= '246',`created_by`= NULL;
INSERT INTO `theme_pages` SET `page_id`= '1',`theme_id`= '0',`page_name`= 'Home',`slug`= '/',`parent_id`= '0',`top_banner_title`= '',`top_banner_sub_title`= '',`page_content_title`= '',`page_content`= '',`link_footer_section`= '',`is_header_menu`= '1',`is_header_dropdown`= '0',`position`= '1',`page_type`= 'fixed',`page_banner_image`= NULL,`created`= '2021-03-15 05:34:48',`status`= '1',`language_id`= '1';
INSERT INTO `theme_pages` SET `page_id`= '2',`theme_id`= '0',`page_name`= 'Faq',`slug`= 'faq',`parent_id`= '0',`top_banner_title`= '',`top_banner_sub_title`= '',`page_content_title`= '',`page_content`= '',`link_footer_section`= '',`is_header_menu`= '1',`is_header_dropdown`= '0',`position`= '2',`page_type`= 'fixed',`page_banner_image`= NULL,`created`= '2021-03-15 05:40:51',`status`= '1',`language_id`= '1';
INSERT INTO `theme_pages` SET `page_id`= '3',`theme_id`= '0',`page_name`= 'Terms',`slug`= 'terms-of-use',`parent_id`= NULL,`top_banner_title`= '',`top_banner_sub_title`= '',`page_content_title`= '',`page_content`= '',`link_footer_section`= '',`is_header_menu`= '1',`is_header_dropdown`= '0',`position`= '3',`page_type`= 'fixed',`page_banner_image`= NULL,`created`= '2021-03-15 05:46:09',`status`= '1',`language_id`= '1';
INSERT INTO `theme_pages` SET `page_id`= '4',`theme_id`= '0',`page_name`= 'Contact',`slug`= 'contact',`parent_id`= NULL,`top_banner_title`= '',`top_banner_sub_title`= '',`page_content_title`= '',`page_content`= '',`link_footer_section`= '',`is_header_menu`= '1',`is_header_dropdown`= '0',`position`= '4',`page_type`= 'fixed',`page_banner_image`= NULL,`created`= '2021-03-15 05:48:16',`status`= '1',`language_id`= '1';
