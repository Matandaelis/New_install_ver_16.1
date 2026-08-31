<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Block layout for login/register theme hooks (Theme_blocks_handler).
 * Keys match Product_model login setting front_template (custom_1 … custom_13, multiple_pages).
 * Values: centered | split | full — used for wrapper CSS classes only (no per-theme SQL/CSS files).
 */
$config['login_theme_block_layouts'] = [
	'custom_1' => 'centered',
	'custom_2' => 'centered',
	'custom_3' => 'centered',
	'custom_4' => 'split',
	'custom_5' => 'centered',
	'custom_6' => 'centered',
	'custom_7' => 'centered',
	'custom_8' => 'centered',
	'custom_9' => 'centered',
	'custom_10' => 'centered',
	'custom_11' => 'centered',
	'custom_12' => 'centered',
	'custom_13' => 'centered',
	'multiple_pages' => 'full',
];
