<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('login_page_block_enabled')) {

	function login_page_block_enabled(array $login, string $key, ?string $legacy_key = null): bool {
		if (array_key_exists($key, $login)) {
			$v = $login[$key];
			if ($v === null || $v === '') {
				return false;
			}
			return (int) $v === 1;
		}
		if ($legacy_key !== null && array_key_exists($legacy_key, $login)) {
			return !empty($login[$legacy_key]) && (int) $login[$legacy_key] === 1;
		}
		return false;
	}
}

if (!function_exists('login_page_block_stats_enabled')) {

	function login_page_block_stats_enabled(array $login): bool {
		return login_page_block_enabled($login, 'block_stats_enabled', 'login_site_stats_visible');
	}
}

if (!function_exists('login_theme_block_layout')) {

	function login_theme_block_layout(string $front_template): string {
		$CI = &get_instance();
		$CI->config->load('login_theme_layouts', true);
		$map = $CI->config->item('login_theme_block_layouts', 'login_theme_layouts');
		if (!is_array($map)) {
			$map = [];
		}
		$key = $front_template;
		if (ctype_digit((string) $front_template)) {
			$key = 'custom_' . (int) $front_template;
		}
		if (!isset($map[$key])) {
			return 'centered';
		}
		$layout = (string) $map[$key];
		return in_array($layout, ['centered', 'split', 'full'], true) ? $layout : 'centered';
	}
}

if (!function_exists('login_page_top_earners_settings_parse')) {

	function login_page_top_earners_settings_parse($raw): array {
		$defaults = ['display_limit' => 5, 'privacy_mode' => 0, 'demo_rows' => 0];
		if ($raw === null || $raw === '') {
			return $defaults;
		}
		if (is_array($raw)) {
			$data = $raw;
		} else {
			$data = json_decode((string) $raw, true);
		}
		if (!is_array($data)) {
			return $defaults;
		}
		$lim = (int) ($data['display_limit'] ?? 5);
		if (in_array($lim, [3, 5, 10], true)) {
			$defaults['display_limit'] = $lim;
		}
		$defaults['privacy_mode'] = !empty($data['privacy_mode']) ? 1 : 0;
		$defaults['demo_rows'] = !empty($data['demo_rows']) ? 1 : 0;
		return $defaults;
	}
}

if (!function_exists('login_page_stats_settings_parse')) {

	function login_page_stats_settings_parse($raw): array {
		$out = ['active_label' => '', 'withdrawals_label' => '', 'demo_values' => 0];
		if ($raw === null || $raw === '') {
			return $out;
		}
		$data = is_array($raw) ? $raw : json_decode((string) $raw, true);
		if (!is_array($data)) {
			return $out;
		}
		$trim = static function ($v): string {
			$s = trim((string) $v);
			if (function_exists('mb_substr')) {
				return mb_substr($s, 0, 120, 'UTF-8');
			}
			return substr($s, 0, 120);
		};
		$out['active_label'] = $trim($data['active_label'] ?? '');
		$out['withdrawals_label'] = $trim($data['withdrawals_label'] ?? '');
		$out['demo_values'] = !empty($data['demo_values']) ? 1 : 0;
		return $out;
	}
}

if (!function_exists('login_page_top_earners_demo_seed_rows')) {

	/**
	 * Sample leaderboard rows for admin "demo data" (login/register preview when DB has no history).
	 *
	 * @return list<array{firstname:string,lastname:string,amount:float}>
	 */
	function login_page_top_earners_demo_seed_rows(): array {
		return [
			['firstname' => 'Alex', 'lastname' => 'Morgan', 'amount' => 48210.50],
			['firstname' => 'Jamie', 'lastname' => 'Chen', 'amount' => 36125.00],
			['firstname' => 'Riley', 'lastname' => 'Patel', 'amount' => 28990.25],
			['firstname' => 'Sam', 'lastname' => 'Okonkwo', 'amount' => 21440.00],
			['firstname' => 'Taylor', 'lastname' => 'Brooks', 'amount' => 18975.80],
			['firstname' => 'Jordan', 'lastname' => 'Silva', 'amount' => 15200.00],
			['firstname' => 'Casey', 'lastname' => 'Nguyen', 'amount' => 12850.40],
			['firstname' => 'Morgan', 'lastname' => 'Reed', 'amount' => 10120.00],
			['firstname' => 'Quinn', 'lastname' => 'Foster', 'amount' => 9650.75],
			['firstname' => 'Avery', 'lastname' => 'Kim', 'amount' => 8200.00],
		];
	}
}

if (!function_exists('login_page_video_max_items')) {

	function login_page_video_max_items(): int {
		return 4;
	}
}

if (!function_exists('login_page_video_settings_parse')) {

	function login_page_video_settings_parse($raw): array {
		$out = ['items' => [], 'autoplay' => 0, 'max_width' => 800, 'columns' => 1, 'use_demo_content' => 0];
		if ($raw === null || $raw === '') {
			return $out;
		}
		$data = is_array($raw) ? $raw : json_decode((string) $raw, true);
		if (!is_array($data)) {
			return $out;
		}
		$trim_url = static function ($v): string {
			$s = trim((string) $v);
			return function_exists('mb_substr') ? mb_substr($s, 0, 500, 'UTF-8') : substr($s, 0, 500);
		};
		$trim_title = static function ($v): string {
			$s = trim((string) $v);
			return function_exists('mb_substr') ? mb_substr($s, 0, 120, 'UTF-8') : substr($s, 0, 120);
		};
		$max = login_page_video_max_items();
		if (isset($data['items']) && is_array($data['items'])) {
			foreach ($data['items'] as $row) {
				if (count($out['items']) >= $max) {
					break;
				}
				if (!is_array($row)) {
					continue;
				}
				$out['items'][] = [
					'url' => $trim_url($row['url'] ?? ''),
					'title' => $trim_title($row['title'] ?? ''),
				];
			}
		} elseif (isset($data['url']) && trim((string) $data['url']) !== '') {
			$out['items'][] = [
				'url' => $trim_url($data['url']),
				'title' => '',
			];
		}
		$out['autoplay'] = !empty($data['autoplay']) ? 1 : 0;
		$mw = (int) ($data['max_width'] ?? 800);
		if (!in_array($mw, [500, 800, 1100], true)) {
			$mw = 800;
		}
		$out['max_width'] = $mw;
		$cols = (int) ($data['columns'] ?? 1);
		if (!in_array($cols, [1, 2, 3], true)) {
			$cols = 1;
		}
		$out['columns'] = $cols;
		$out['use_demo_content'] = !empty($data['use_demo_content']) ? 1 : 0;
		return $out;
	}
}

if (!function_exists('login_page_lang_line_front')) {

	/**
	 * Read a line from the active language (front pack) with a plain-text fallback.
	 */
	function login_page_lang_line_front(string $key, string $fallback = ''): string {
		$CI = &get_instance();
		if (!isset($CI->lang)) {
			return $fallback;
		}
		$line = $CI->lang->line($key);
		if ($line === false || $line === null || $line === '') {
			return $fallback;
		}
		return (string) $line;
	}
}

if (!function_exists('login_page_video_demo_seed_items')) {

	/**
	 * Same sample videos as the admin "Load demo data" button (titles from language pack when available).
	 *
	 * @return list<array{url:string,title:string}>
	 */
	function login_page_video_demo_seed_items(): array {
		return [
			[
				'url' => 'https://www.youtube.com/watch?v=TThQqFDD1t4',
				'title' => login_page_lang_line_front('block_video_demo_title_1', 'Getting Started with Affiliate Marketing'),
			],
			[
				'url' => 'https://www.youtube.com/watch?v=wLm-yYco8tQ',
				'title' => login_page_lang_line_front('block_video_demo_title_2', 'Step-by-Step Affiliate Guide'),
			],
			[
				'url' => 'https://www.youtube.com/watch?v=SJwwe1YXisA',
				'title' => login_page_lang_line_front('block_video_demo_title_3', 'Affiliate Marketing with AI'),
			],
		];
	}
}

if (!function_exists('login_page_features_demo_slots_from_lang')) {

	/**
	 * @return list<array{icon:string,title:string,description:string}>
	 */
	function login_page_features_demo_slots_from_lang(): array {
		return [
			[
				'icon' => 'bi-lightning-charge-fill',
				'title' => login_page_lang_line_front('block_features_demo_1_title', 'Fast payouts'),
				'description' => login_page_lang_line_front('block_features_demo_1_desc', ''),
			],
			[
				'icon' => 'bi-shield-check',
				'title' => login_page_lang_line_front('block_features_demo_2_title', 'Bank-grade security'),
				'description' => login_page_lang_line_front('block_features_demo_2_desc', ''),
			],
			[
				'icon' => 'bi-graph-up-arrow',
				'title' => login_page_lang_line_front('block_features_demo_3_title', 'Real-time dashboard'),
				'description' => login_page_lang_line_front('block_features_demo_3_desc', ''),
			],
			[
				'icon' => 'bi-headset',
				'title' => login_page_lang_line_front('block_features_demo_4_title', 'Dedicated support'),
				'description' => login_page_lang_line_front('block_features_demo_4_desc', ''),
			],
		];
	}
}

if (!function_exists('login_page_faq_demo_items_from_lang')) {

	/**
	 * @return list<array{question:string,answer:string}>
	 */
	function login_page_faq_demo_items_from_lang(): array {
		return [
			[
				'question' => login_page_lang_line_front('block_faq_demo_1_q', ''),
				'answer' => login_page_lang_line_front('block_faq_demo_1_a', ''),
			],
			[
				'question' => login_page_lang_line_front('block_faq_demo_2_q', ''),
				'answer' => login_page_lang_line_front('block_faq_demo_2_a', ''),
			],
			[
				'question' => login_page_lang_line_front('block_faq_demo_3_q', ''),
				'answer' => login_page_lang_line_front('block_faq_demo_3_a', ''),
			],
			[
				'question' => login_page_lang_line_front('block_faq_demo_4_q', ''),
				'answer' => login_page_lang_line_front('block_faq_demo_4_a', ''),
			],
		];
	}
}

if (!function_exists('login_page_live_pulse_demo_items')) {

	/**
	 * Fixed toast lines for admin "sample activity" mode (login/register pulse).
	 *
	 * @return list<array{type:string,text:string}>
	 */
	function login_page_live_pulse_demo_items(int $limit = 12): array {
		$limit = max(4, min(20, $limit));
		$pairs = [
			['type' => 'registration', 'key' => 'block_live_pulse_demo_1', 'fb' => 'Alex M. from Canada just joined'],
			['type' => 'commission', 'key' => 'block_live_pulse_demo_2', 'fb' => 'Jamie C. from the United States just earned $124.30'],
			['type' => 'registration', 'key' => 'block_live_pulse_demo_3', 'fb' => 'Sam K. from the United Kingdom just joined'],
			['type' => 'commission', 'key' => 'block_live_pulse_demo_4', 'fb' => 'Riley P. from Australia just earned $89.50'],
			['type' => 'registration', 'key' => 'block_live_pulse_demo_5', 'fb' => 'Taylor B. from Germany just joined'],
			['type' => 'commission', 'key' => 'block_live_pulse_demo_6', 'fb' => 'Jordan S. from Brazil just earned $210.00'],
			['type' => 'registration', 'key' => 'block_live_pulse_demo_7', 'fb' => 'Casey N. from Japan just joined'],
			['type' => 'commission', 'key' => 'block_live_pulse_demo_8', 'fb' => 'Morgan R. from France just earned $56.75'],
			['type' => 'registration', 'key' => 'block_live_pulse_demo_9', 'fb' => 'Quinn F. from Spain just joined'],
			['type' => 'commission', 'key' => 'block_live_pulse_demo_10', 'fb' => 'Avery K. from Italy just earned $312.10'],
		];
		$lines = [];
		foreach ($pairs as $p) {
			$t = login_page_lang_line_front($p['key'], $p['fb']);
			if (trim($t) === '') {
				continue;
			}
			$lines[] = ['type' => $p['type'], 'text' => $t];
		}
		if ($lines === []) {
			return [];
		}
		shuffle($lines);
		return array_slice($lines, 0, $limit);
	}
}

if (!function_exists('login_page_video_items_for_display')) {

	function login_page_video_items_for_display(array $items, bool $autoplay): array {
		$max = login_page_video_max_items();
		$out = [];
		foreach ($items as $row) {
			if (count($out) >= $max) {
				break;
			}
			$url = trim((string) ($row['url'] ?? ''));
			if ($url === '') {
				continue;
			}
			$embed = login_page_video_embed_src($url, $autoplay);
			if ($embed === null) {
				continue;
			}
			$out[] = [
				'embed_src' => $embed,
				'title' => trim((string) ($row['title'] ?? '')),
			];
		}
		return $out;
	}
}

if (!function_exists('login_page_video_sanitize_settings_for_save')) {

	function login_page_video_sanitize_settings_for_save(array $payload): array {
		$max = login_page_video_max_items();
		$trim_url = static function ($v): string {
			$s = trim((string) $v);
			return function_exists('mb_substr') ? mb_substr($s, 0, 500, 'UTF-8') : substr($s, 0, 500);
		};
		$trim_title = static function ($v): string {
			$s = trim(strip_tags((string) $v));
			return function_exists('mb_substr') ? mb_substr($s, 0, 120, 'UTF-8') : substr($s, 0, 120);
		};
		$items = [];
		$raw_items = (isset($payload['items']) && is_array($payload['items'])) ? $payload['items'] : [];
		foreach (array_slice($raw_items, 0, $max) as $row) {
			if (!is_array($row)) {
				continue;
			}
			$u = $trim_url($row['url'] ?? '');
			$t = $trim_title($row['title'] ?? '');
			if ($u === '') {
				continue;
			}
			$items[] = ['url' => $u, 'title' => $t];
		}
		$autoplay = !empty($payload['autoplay']) ? 1 : 0;
		$mw = (int) ($payload['max_width'] ?? 800);
		if (!in_array($mw, [500, 800, 1100], true)) {
			$mw = 800;
		}
		$cols = (int) ($payload['columns'] ?? 1);
		if (!in_array($cols, [1, 2, 3], true)) {
			$cols = 1;
		}
		return [
			'items' => $items,
			'autoplay' => $autoplay,
			'max_width' => $mw,
			'columns' => $cols,
			'use_demo_content' => !empty($payload['use_demo_content']) ? 1 : 0,
		];
	}
}

if (!function_exists('login_page_live_pulse_settings_parse')) {

	function login_page_live_pulse_settings_parse($raw): array {
		$out = ['poll_interval_sec' => 28, 'toast_position' => 'bottom-right', 'use_demo_content' => 0];
		if ($raw === null || $raw === '') {
			return $out;
		}
		$data = is_array($raw) ? $raw : json_decode((string) $raw, true);
		if (!is_array($data)) {
			return $out;
		}
		$sec = (int) ($data['poll_interval_sec'] ?? 28);
		if ($sec < 15) {
			$sec = 15;
		}
		if ($sec > 120) {
			$sec = 120;
		}
		$out['poll_interval_sec'] = $sec;
		$pos = (string) ($data['toast_position'] ?? 'bottom-right');
		if (!in_array($pos, ['bottom-right', 'bottom-left', 'bottom-center'], true)) {
			$pos = 'bottom-right';
		}
		$out['toast_position'] = $pos;
		$out['use_demo_content'] = !empty($data['use_demo_content']) ? 1 : 0;
		return $out;
	}
}

if (!function_exists('login_page_sanitize_bi_icon_class')) {

	function login_page_sanitize_bi_icon_class(string $icon): string {
		$icon = trim(strtolower($icon));
		$icon = preg_replace('/\s+/', '', $icon);
		if ($icon === '') {
			return '';
		}
		if (strpos($icon, 'bi-') !== 0) {
			$icon = 'bi-' . preg_replace('/^bi-/', '', $icon);
		}
		if (!preg_match('/^bi-[a-z0-9-]{1,48}$/', $icon)) {
			return '';
		}
		return $icon;
	}
}

if (!function_exists('login_page_features_max_items')) {

	function login_page_features_max_items(): int {
		return 8;
	}
}

if (!function_exists('login_page_features_settings_raw_array')) {

	function login_page_features_settings_raw_array($raw): array {
		if ($raw === null || $raw === '') {
			return [];
		}
		$data = is_array($raw) ? $raw : json_decode((string) $raw, true);

		return is_array($data) ? $data : [];
	}
}

if (!function_exists('login_page_features_settings_decode')) {

	function login_page_features_settings_decode($raw): array {
		$max = login_page_features_max_items();
		$out = [];
		$data = login_page_features_settings_raw_array($raw);
		if ($data === [] || empty($data['items']) || !is_array($data['items'])) {
			return $out;
		}
		$trim = static function (string $s, int $maxLen): string {
			if (function_exists('mb_substr')) {
				return mb_substr($s, 0, $maxLen, 'UTF-8');
			}
			return substr($s, 0, $maxLen);
		};
		foreach ($data['items'] as $row) {
			if (count($out) >= $max) {
				break;
			}
			if (!is_array($row)) {
				continue;
			}
			$icon = login_page_sanitize_bi_icon_class((string) ($row['icon'] ?? ''));
			$title = $trim(trim((string) ($row['title'] ?? '')), 120);
			$desc = $trim(trim((string) ($row['description'] ?? '')), 400);
			$out[] = [
				'icon' => $icon,
				'title' => $title,
				'description' => $desc,
			];
		}
		return $out;
	}
}

if (!function_exists('login_page_features_items_for_display')) {

	function login_page_features_items_for_display(array $slots): array {
		$max = login_page_features_max_items();
		$out = [];
		foreach ($slots as $s) {
			if (!is_array($s) || trim((string) ($s['title'] ?? '')) === '') {
				continue;
			}
			$icon = login_page_sanitize_bi_icon_class((string) ($s['icon'] ?? ''));
			if ($icon === '') {
				$icon = 'bi-star-fill';
			}
			$out[] = [
				'icon' => $icon,
				'title' => trim((string) $s['title']),
				'description' => trim((string) ($s['description'] ?? '')),
			];
			if (count($out) >= $max) {
				break;
			}
		}
		return $out;
	}
}

if (!function_exists('login_page_features_sanitize_post_items')) {

	function login_page_features_sanitize_post_items($items): array {
		$max = login_page_features_max_items();
		$slots = [];
		if (!is_array($items)) {
			return $slots;
		}
		$trim = static function (string $s, int $maxLen): string {
			if (function_exists('mb_substr')) {
				return mb_substr($s, 0, $maxLen, 'UTF-8');
			}
			return substr($s, 0, $maxLen);
		};
		foreach (array_slice($items, 0, $max) as $row) {
			if (!is_array($row)) {
				continue;
			}
			$icon = login_page_sanitize_bi_icon_class((string) ($row['icon'] ?? ''));
			$title = $trim(trim((string) ($row['title'] ?? '')), 120);
			$desc = $trim(trim((string) ($row['description'] ?? '')), 400);
			$slots[] = [
				'icon' => $icon,
				'title' => $title,
				'description' => $desc,
			];
		}
		return $slots;
	}
}

if (!function_exists('login_page_features_display_defaults')) {

	function login_page_features_display_defaults(): array {
		return [
			'columns_sm' => 2,
			'columns_md' => 4,
			'columns_lg' => 4,
			'variant' => 'cards',
			'show_description' => true,
			'icon_style' => 'circle',
		];
	}
}

if (!function_exists('login_page_features_display_options_decode')) {

	function login_page_features_display_options_decode($raw): array {
		$d = login_page_features_display_defaults();
		$data = login_page_features_settings_raw_array($raw);
		foreach (['columns_sm', 'columns_md', 'columns_lg'] as $k) {
			if (isset($data[$k])) {
				$v = (int) $data[$k];
				if (in_array($v, [1, 2, 3, 4], true)) {
					$d[$k] = $v;
				}
			}
		}
		if (isset($data['variant']) && in_array((string) $data['variant'], ['plain', 'cards'], true)) {
			$d['variant'] = (string) $data['variant'];
		}
		if (array_key_exists('show_description', $data)) {
			$sd = $data['show_description'];
			$d['show_description'] = ($sd === true || $sd === 1 || $sd === '1' || $sd === 'true');
		}
		if (isset($data['icon_style']) && in_array((string) $data['icon_style'], ['none', 'soft', 'circle'], true)) {
			$d['icon_style'] = (string) $data['icon_style'];
		}

		return $d;
	}
}

if (!function_exists('login_page_features_sanitize_settings_for_save')) {

	function login_page_features_sanitize_settings_for_save(array $payload): array {
		$items = (isset($payload['items']) && is_array($payload['items'])) ? $payload['items'] : [];
		$def = login_page_features_display_defaults();
		$out = [
			'items' => login_page_features_sanitize_post_items($items),
		];
		foreach (['columns_sm', 'columns_md', 'columns_lg'] as $k) {
			$v = isset($payload[$k]) ? (int) $payload[$k] : $def[$k];
			if (!in_array($v, [1, 2, 3, 4], true)) {
				$v = $def[$k];
			}
			$out[$k] = $v;
		}
		$var = isset($payload['variant']) ? (string) $payload['variant'] : $def['variant'];
		$out['variant'] = in_array($var, ['plain', 'cards'], true) ? $var : $def['variant'];
		if (array_key_exists('show_description', $payload)) {
			$sd = $payload['show_description'];
			$out['show_description'] = ($sd === true || $sd === 1 || $sd === '1' || $sd === 'true');
		} else {
			$out['show_description'] = $def['show_description'];
		}
		$ist = isset($payload['icon_style']) ? (string) $payload['icon_style'] : $def['icon_style'];
		$out['icon_style'] = in_array($ist, ['none', 'soft', 'circle'], true) ? $ist : $def['icon_style'];
		$out['use_demo_content'] = !empty($payload['use_demo_content']) ? 1 : 0;

		return $out;
	}
}

if (!function_exists('login_page_faq_max_items')) {

	function login_page_faq_max_items(): int {
		return 12;
	}
}

if (!function_exists('login_page_faq_settings_decode')) {

	function login_page_faq_settings_decode($raw): array {
		$defaults = ['items' => [], 'first_item_open' => true, 'use_demo_content' => 0];
		if ($raw === null || $raw === '') {
			return $defaults;
		}
		if (is_array($raw)) {
			$data = $raw;
		} else {
			$data = json_decode((string) $raw, true);
		}
		if (!is_array($data)) {
			return $defaults;
		}
		$items = (isset($data['items']) && is_array($data['items'])) ? $data['items'] : [];
		$clean = [];
		foreach ($items as $row) {
			if (!is_array($row)) {
				continue;
			}
			$clean[] = [
				'question' => (string) ($row['question'] ?? ''),
				'answer' => (string) ($row['answer'] ?? ''),
			];
		}
		$fio = $data['first_item_open'] ?? true;
		$first_open = ($fio === true || $fio === 1 || $fio === '1' || $fio === 'true');
		$use_demo = !empty($data['use_demo_content']);

		return [
			'items' => $clean,
			'first_item_open' => $first_open,
			'use_demo_content' => $use_demo ? 1 : 0,
		];
	}
}

if (!function_exists('login_page_faq_items_for_display')) {

	function login_page_faq_items_for_display(array $slots): array {
		$out = [];
		foreach ($slots as $row) {
			$q = trim((string) ($row['question'] ?? ''));
			if ($q === '') {
				continue;
			}
			$out[] = [
				'question' => $q,
				'answer' => trim((string) ($row['answer'] ?? '')),
			];
		}
		return $out;
	}
}

if (!function_exists('login_page_faq_sanitize_post_items')) {

	function login_page_faq_sanitize_post_items(array $items): array {
		$max = login_page_faq_max_items();
		$out = [];
		$n = 0;
		foreach ($items as $row) {
			if ($n >= $max) {
				break;
			}
			if (!is_array($row)) {
				continue;
			}
			$q = trim(strip_tags((string) ($row['question'] ?? '')));
			$a = trim(strip_tags((string) ($row['answer'] ?? '')));
			if (function_exists('mb_strlen')) {
				if (mb_strlen($q) > 240) {
					$q = mb_substr($q, 0, 240);
				}
				if (mb_strlen($a) > 4000) {
					$a = mb_substr($a, 0, 4000);
				}
			} else {
				if (strlen($q) > 240) {
					$q = substr($q, 0, 240);
				}
				if (strlen($a) > 4000) {
					$a = substr($a, 0, 4000);
				}
			}
			if ($q === '' && $a === '') {
				continue;
			}
			if ($q === '') {
				continue;
			}
			$out[] = ['question' => $q, 'answer' => $a];
			$n++;
		}
		return $out;
	}
}

if (!function_exists('login_page_faq_sanitize_settings_for_save')) {

	function login_page_faq_sanitize_settings_for_save(array $payload): array {
		$items = (isset($payload['items']) && is_array($payload['items'])) ? $payload['items'] : [];
		$fio = $payload['first_item_open'] ?? true;
		$first_open = ($fio === true || $fio === 1 || $fio === '1' || $fio === 'true');

		return [
			'items' => login_page_faq_sanitize_post_items($items),
			'first_item_open' => $first_open,
			'use_demo_content' => !empty($payload['use_demo_content']) ? 1 : 0,
		];
	}
}

if (!function_exists('login_page_currency_number_from_formatted')) {

	function login_page_currency_number_from_formatted(string $formatted, string $symbol_left, string $symbol_right): string {
		$out = $formatted;
		$sl = trim($symbol_left);
		$sr = trim($symbol_right);
		if ($sl !== '' && strlen($out) >= strlen($sl) && strncmp($out, $sl, strlen($sl)) === 0) {
			$out = substr($out, strlen($sl));
		}
		if ($sr !== '' && strlen($out) >= strlen($sr) && substr($out, -strlen($sr)) === $sr) {
			$out = substr($out, 0, -strlen($sr));
		}
		return trim($out);
	}
}

if (!function_exists('login_page_video_embed_src')) {

	function login_page_video_embed_src(string $url, bool $autoplay): ?string {
		$url = trim($url);
		if ($url === '') {
			return null;
		}
		$ap = $autoplay ? 1 : 0;
		if (preg_match('~(?:youtube\.com/(?:watch\?(?:[^&]*&)*v=|embed/|v/)|youtu\.be/)([a-zA-Z0-9_-]{11})~', $url, $m)) {
			$id = $m[1];
			$q = 'rel=0&modestbranding=1';
			if ($ap) {
				$q .= '&autoplay=1&mute=1&playsinline=1';
			}
			return 'https://www.youtube-nocookie.com/embed/' . $id . '?' . $q;
		}
		if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
			$vid = $m[1];
			$q = 'title=0&byline=0&portrait=0';
			if ($ap) {
				$q .= '&autoplay=1&muted=1';
			}
			return 'https://player.vimeo.com/video/' . $vid . '?' . $q;
		}
		return null;
	}
}
