<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_blocks_handler {

	public function merge_login_register_hooks(array &$data, string $front_template): void {
		$hooks = $this->build_login_register_hooks([
			'front_template' => $front_template,
			'login_live_stats_visible' => !empty($data['login_live_stats_visible']),
			'login_live_stats' => (isset($data['login_live_stats']) && is_array($data['login_live_stats'])) ? $data['login_live_stats'] : null,
			'login_top_earners_visible' => !empty($data['login_top_earners_visible']),
			'login_top_earners' => (isset($data['login_top_earners']) && is_array($data['login_top_earners'])) ? $data['login_top_earners'] : null,
		]);
		foreach ($hooks as $k => $v) {
			$data[$k] = $v;
		}
	}

	public function build_login_register_hooks(array $ctx): array {
		$CI = &get_instance();
		$CI->load->helper('login_page_blocks');
		$CI->load->model('Product_model');
		$login = $CI->Product_model->getSettings('login');
		if (!is_array($login)) {
			$login = [];
		}

		$ft = isset($ctx['front_template']) ? (string) $ctx['front_template'] : 'custom_1';
		$layout = login_theme_block_layout($ft);
		$safe_layout = preg_replace('/[^a-z0-9_-]/', '', $layout);
		if ($safe_layout === '') {
			$safe_layout = 'centered';
		}

		$is_theme_idx1 = (ctype_digit($ft) && (int) $ft === 1) || $ft === 'custom_1';

		$inner = '';
		if (!$is_theme_idx1) {
			if (!empty($ctx['login_live_stats_visible']) && isset($ctx['login_live_stats']) && is_array($ctx['login_live_stats'])) {
				$inner .= $CI->load->view('usercontrol/login/components/live_stats_component', [
					'login_live_stats' => $ctx['login_live_stats'],
					'login_blocks_layout' => $layout,
				], true);
			}
			if (!empty($ctx['login_top_earners_visible']) && !empty($ctx['login_top_earners']) && is_array($ctx['login_top_earners'])) {
				$inner .= $CI->load->view('usercontrol/login/components/top_earners_component', [
					'login_top_earners' => $ctx['login_top_earners'],
					'login_blocks_layout' => $layout,
				], true);
			}
		}

		$video_cfg = login_page_video_settings_parse($login['block_video_settings'] ?? null);
		$pulse_cfg = login_page_live_pulse_settings_parse($login['block_live_pulse_settings'] ?? null);
		$video_enabled = login_page_block_enabled($login, 'block_video_enabled', null);
		$pulse_enabled = login_page_block_enabled($login, 'block_live_pulse_enabled', null);
		$pulse_url = $pulse_enabled ? base_url('login_marketing_activity') : '';
		$pulse_active = $pulse_enabled && $pulse_url !== '';

		$wrapper_class = 'aff-theme-blocks aff-theme-blocks--form-bottom aff-theme-blocks--' . $safe_layout;
		if ($pulse_active && $inner !== '') {
			$wrapper_class .= ' aff-theme-blocks--pulse-on';
		}
		$hook_form_bottom = $inner !== ''
			? '<div class="' . htmlspecialchars($wrapper_class, ENT_QUOTES, 'UTF-8') . '">' . $inner . '</div>'
			: '';

		$video_items = [];
		if ($video_enabled) {
			if (!empty($video_cfg['use_demo_content'])) {
				$video_items = login_page_video_items_for_display(login_page_video_demo_seed_items(), !empty($video_cfg['autoplay']));
			} elseif (!empty($video_cfg['items'])) {
				$video_items = login_page_video_items_for_display($video_cfg['items'], !empty($video_cfg['autoplay']));
			}
		}

		$features_html = '';
		if (login_page_block_enabled($login, 'block_features_enabled', null)) {
			$raw_feat = login_page_features_settings_raw_array($login['block_features_settings'] ?? null);
			$feature_slots = !empty($raw_feat['use_demo_content'])
				? login_page_features_demo_slots_from_lang()
				: login_page_features_settings_decode($login['block_features_settings'] ?? null);
			$feature_items = login_page_features_items_for_display($feature_slots);
			if ($feature_items !== []) {
				$feature_display = login_page_features_display_options_decode($login['block_features_settings'] ?? null);
				$features_html = $CI->load->view('usercontrol/login/components/features_component', [
					'login_features_items' => $feature_items,
					'login_blocks_layout' => $layout,
					'login_features_display' => $feature_display,
				], true);
			}
		}

		$faq_html = '';
		if (login_page_block_enabled($login, 'block_faq_enabled', null)) {
			$faq_cfg = login_page_faq_settings_decode($login['block_faq_settings'] ?? null);
			$faq_raw_items = !empty($faq_cfg['use_demo_content'])
				? login_page_faq_demo_items_from_lang()
				: $faq_cfg['items'];
			$faq_items = login_page_faq_items_for_display($faq_raw_items);
			if ($faq_items !== []) {
				$faq_html = $CI->load->view('usercontrol/login/components/faq_component', [
					'login_faq_items' => $faq_items,
					'login_blocks_layout' => $layout,
					'login_faq_first_open' => !empty($faq_cfg['first_item_open']),
				], true);
			}
		}

		$hook_floating_pulse = $CI->load->view('usercontrol/login/components/hook_floating_pulse', [
			'login_blocks_layout' => $safe_layout,
			'login_live_pulse_enabled' => $pulse_enabled,
			'pulse_fetch_url' => $pulse_url,
			'pulse_poll_ms' => max(15000, min(120000, (int) $pulse_cfg['poll_interval_sec'] * 1000)),
			'pulse_toast_position' => $pulse_cfg['toast_position'],
		], true);

		$hook_page_footer = $CI->load->view('usercontrol/login/components/hook_page_footer', [
			'login_blocks_layout' => $safe_layout,
			'login_features_html' => $features_html,
			'login_faq_html' => $faq_html,
			'login_video_items' => $video_items,
			'login_video_columns' => (int) ($video_cfg['columns'] ?? 1),
			'login_video_max_width' => (int) ($video_cfg['max_width'] ?? 800),
		], true);

		return [
			'hook_floating_pulse' => $hook_floating_pulse,
			'hook_form_bottom' => $hook_form_bottom,
			'hook_page_footer' => $hook_page_footer,
			'login_page_block_layout' => $safe_layout,
		];
	}
}
