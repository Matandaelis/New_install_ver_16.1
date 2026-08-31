<?php defined('BASEPATH') OR exit('No direct script access allowed');
$aff_feature_icon_picker_json = isset($aff_feature_icon_picker_json) && is_array($aff_feature_icon_picker_json) ? $aff_feature_icon_picker_json : [];
$is_demo_mode = !empty($is_demo_mode);
?>
<script>
$(function() {
	var affBlocksDemoMode = <?= $is_demo_mode ? 'true' : 'false' ?>;
	var affBlocksDemoMsg = <?= json_encode(__('admin.demo_mode')) ?>;
	$(document).on('change', '.update_all_settings', function() {
		var $cb = $(this);
		var checked = $cb.is(':checked') ? 1 : 0;
		var setting_key = $cb.data('setting_key');
		var setting_type = $cb.data('setting_type');
		$.ajax({
			url: '<?= base_url('admincontrol/update_all_settings') ?>',
			type: 'POST',
			dataType: 'json',
			data: { action: 'update_all_settings', status: checked, setting_key: setting_key, setting_type: setting_type },
			success: function(json) {
				if (json && json.success) {
					if (typeof showToast === 'function') {
						showToast(<?= json_encode(__('admin.success')) ?>, json.success, 'success', 3000);
					} else if (typeof showPrintMessage === 'function') {
						showPrintMessage(json.success, 'success');
					}
				}
			},
			error: function() {
				$cb.prop('checked', !$cb.is(':checked'));
				if (typeof showToast === 'function') {
					showToast(<?= json_encode(__('admin.error')) ?>, <?= json_encode(__('admin.save_failed')) ?>, 'error', 5000);
				}
			}
		});
	});
	var blockSettingsModalTitleTpl = <?= json_encode(__('admin.login_page_block_settings_modal_title_tpl')) ?>;
	var blockSettingsModalBodyTpl = <?= json_encode(__('admin.login_page_block_settings_modal_body_tpl')) ?>;
	var affFeatureIconChoices = <?= json_encode($aff_feature_icon_picker_json, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
	var affFeatureIconsUi = <?= json_encode([
		'pickIcon' => __('admin.block_features_pick_icon'),
		'preview' => __('admin.block_features_preview_label'),
		'remove' => __('admin.block_features_remove_feature'),
		'itemLabel' => __('admin.block_features_item_label'),
		'maxReached' => __('admin.block_features_max_reached'),
		'iconLabel' => __('admin.block_features_icon_label'),
		'titleLabel' => __('admin.block_features_title_label'),
		'descLabel' => __('admin.block_features_desc_label'),
		'titlePh' => __('admin.block_features_title_placeholder'),
		'descPh' => __('admin.block_features_desc_placeholder'),
		'customIcon' => __('admin.block_features_icon_choice_custom'),
		'previewToggle' => __('admin.block_features_preview_toggle'),
	], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
	var affFaqUi = <?= json_encode([
		'remove' => __('admin.block_faq_remove_item'),
		'itemLabel' => __('admin.block_faq_item_label'),
		'questionLabel' => __('admin.block_faq_question_label'),
		'answerLabel' => __('admin.block_faq_answer_label'),
		'questionPh' => __('admin.block_faq_question_placeholder'),
		'answerPh' => __('admin.block_faq_answer_placeholder'),
		'maxReached' => __('admin.block_faq_max_reached'),
	], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
	var affVideoUi = <?= json_encode([
		'remove' => __('admin.block_video_remove_btn'),
		'itemLabel' => __('admin.block_video_item_label'),
		'urlLabel' => __('admin.block_video_url'),
		'titlePh' => __('admin.block_video_title_placeholder'),
		'maxReached' => __('admin.block_video_max_reached'),
	], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
	var affPlatformStatsDemoLabels = <?= json_encode([
		'active' => __('admin.block_stats_demo_label_active'),
		'withdrawals' => __('admin.block_stats_demo_label_withdrawals'),
	], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
	var affFiCardSeq = 0;
	function affFeatureIconsReadDisplay() {
		var raw = document.getElementById('aff-feature-icons-initial');
		if (!raw) { return {}; }
		try {
			var o = JSON.parse(raw.textContent);
			return (o.display && typeof o.display === 'object') ? o.display : {};
		} catch (e) { return {}; }
	}
	function affFiApplyDisplayFromObj(d) {
		d = d || {};
		var sm = parseInt(d.columns_sm, 10);
		if (sm >= 1 && sm <= 4) { $('#aff-fi-disp-cols-sm').val(String(sm)); }
		var md = parseInt(d.columns_md, 10);
		if (md >= 1 && md <= 4) { $('#aff-fi-disp-cols-md').val(String(md)); }
		var lg = parseInt(d.columns_lg, 10);
		if (lg >= 1 && lg <= 4) { $('#aff-fi-disp-cols-lg').val(String(lg)); }
		$('#aff-fi-disp-variant').val(d.variant === 'plain' ? 'plain' : 'cards');
		$('#aff-fi-disp-show-desc').prop('checked', d.show_description !== false && d.show_description !== 0 && d.show_description !== '0');
		var ist = d.icon_style || 'circle';
		if (['none', 'soft', 'circle'].indexOf(ist) === -1) { ist = 'circle'; }
		$('#aff-fi-disp-icon-style').val(ist);
	}
	function affFiEscapeAttr(s) {
		return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
	}
	function affFeatureIconsReadMax() {
		var raw = document.getElementById('aff-feature-icons-initial');
		if (!raw) { return 8; }
		try {
			var o = JSON.parse(raw.textContent);
			return parseInt(o.max, 10) || 8;
		} catch (e) { return 8; }
	}
	function affFeatureIconsReadUseDemo() {
		var raw = document.getElementById('aff-feature-icons-initial');
		if (!raw) { return false; }
		try {
			var o = JSON.parse(raw.textContent);
			return !!(o.use_demo_content === 1 || o.use_demo_content === '1' || o.use_demo_content === true);
		} catch (e) { return false; }
	}
	function affFeatureIconsReadItems() {
		var raw = document.getElementById('aff-feature-icons-initial');
		if (!raw) { return []; }
		try {
			var o = JSON.parse(raw.textContent);
			return Array.isArray(o.items) ? o.items : [];
		} catch (e) { return []; }
	}
	function affFiIconLabelForClass(c) {
		c = (c || '').trim();
		if (!c) { return affFeatureIconsUi.customIcon; }
		for (var i = 0; i < affFeatureIconChoices.length; i++) {
			if (affFeatureIconChoices[i].c === c) { return affFeatureIconChoices[i].l; }
		}
		return affFeatureIconsUi.customIcon;
	}
	function affFiIconGridHtml() {
		var h = '<div class="aff-feature-icon-grid">';
		for (var i = 0; i < affFeatureIconChoices.length; i++) {
			var row = affFeatureIconChoices[i];
			h += '<button type="button" class="btn aff-fi-opt" data-icon-class="' + affFiEscapeAttr(row.c) + '">' +
				'<i class="bi ' + affFiEscapeAttr(row.c) + ' d-block mb-1"></i><span class="small">' + affFiEscapeAttr(row.l) + '</span></button>';
		}
		h += '</div>';
		return h;
	}
	function affFiHighlightPickerSelection($card) {
		var cur = ($card.find('.feature-icon-class').val() || '').trim();
		$card.find('.aff-fi-opt').each(function() {
			$(this).toggleClass('active', $(this).attr('data-icon-class') === cur);
		});
	}
	function affFiClosePickerPanel($card) {
		var $c = $card.find('.aff-fi-picker-collapse');
		if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
			var inst = bootstrap.Collapse.getInstance($c[0]);
			if (inst) { inst.hide(); }
		}
	}
	function affFiSetCardIconUi($card, iconClass) {
		var c = (iconClass || '').trim();
		$card.find('.feature-icon-class').val(c);
		var $ico = $card.find('.aff-fi-trigger-bi');
		var $lbl = $card.find('.feature-icon-trigger-label');
		if (c) {
			$ico.attr('class', 'bi aff-fi-trigger-bi ' + c);
		} else {
			$ico.attr('class', 'bi bi-grid-3x3-gap aff-fi-trigger-bi text-muted');
		}
		$lbl.text(affFiIconLabelForClass(c));
		affFiHighlightPickerSelection($card);
	}
	function affFiSyncCardPreview($card) {
		var ic = ($card.find('.feature-icon-class').val() || '').trim();
		var $pi = $card.find('.aff-fi-preview-bi');
		if (ic) {
			$pi.attr('class', 'bi aff-fi-preview-bi text-primary ' + ic);
		} else {
			$pi.attr('class', 'bi bi-circle aff-fi-preview-bi text-muted');
		}
		var t = $card.find('.feature-title-input').val() || '';
		$card.find('.aff-fi-preview-title').text(t.trim() ? t : '—');
	}
	function affFiAppendCard(item) {
		var iconClass = item && item.icon ? String(item.icon) : '';
		var title = item && item.title ? String(item.title) : '';
		var desc = item && item.description ? String(item.description) : '';
		var grid = affFiIconGridHtml();
		var seq = ++affFiCardSeq;
		var cid = 'aff-fi-collapse-' + seq;
		var pid = 'aff-fi-preview-' + seq;
		var html = '<div class="feature-repeater-card aff-fi-card"><div class="aff-fi-card-inner">' +
			'<div class="d-flex justify-content-between align-items-center mb-2">' +
			'<span class="aff-fi-card-kicker">' + affFiEscapeAttr(affFeatureIconsUi.itemLabel) + '</span>' +
			'<button type="button" class="btn btn-sm btn-link text-danger feature-remove-btn">' + affFiEscapeAttr(affFeatureIconsUi.remove) + '</button></div>' +
			'<div class="row g-2 mb-1"><div class="col-md-5">' +
			'<label class="aff-fi-field-label d-block">' + affFiEscapeAttr(affFeatureIconsUi.iconLabel) + '</label>' +
			'<button type="button" class="btn btn-sm btn-outline-secondary w-100 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#' + cid + '">' +
			'<span class="d-flex align-items-center gap-2 min-w-0"><i class="aff-fi-trigger-bi bi"></i><span class="feature-icon-trigger-label text-truncate"></span></span>' +
			'<i class="bi bi-chevron-down"></i></button>' +
			'<div class="collapse aff-fi-picker-collapse mt-2" id="' + cid + '"><div class="border rounded p-2 bg-light">' +
			'<div class="small text-muted mb-2">' + affFiEscapeAttr(affFeatureIconsUi.pickIcon) + '</div>' + grid + '</div></div>' +
			'<input type="hidden" class="feature-icon-class" value=""></div>' +
			'<div class="col-md-7"><label class="aff-fi-field-label d-block">' + affFiEscapeAttr(affFeatureIconsUi.titleLabel) + '</label>' +
			'<input type="text" class="form-control form-control-sm feature-title-input" maxlength="120" placeholder="' + affFiEscapeAttr(affFeatureIconsUi.titlePh) + '"></div></div>' +
			'<label class="aff-fi-field-label d-block">' + affFiEscapeAttr(affFeatureIconsUi.descLabel) + '</label>' +
			'<textarea class="form-control form-control-sm feature-desc-input" rows="2" maxlength="400" placeholder="' + affFiEscapeAttr(affFeatureIconsUi.descPh) + '"></textarea>' +
			'<button type="button" class="btn btn-link btn-sm px-0" data-bs-toggle="collapse" data-bs-target="#' + pid + '">' + affFiEscapeAttr(affFeatureIconsUi.previewToggle) + '</button>' +
			'<div class="collapse" id="' + pid + '"><div class="d-flex gap-2 p-2 bg-light rounded"><i class="aff-fi-preview-bi bi fs-3"></i><div><div class="small text-muted">' + affFiEscapeAttr(affFeatureIconsUi.preview) + '</div><div class="aff-fi-preview-title fw-semibold"></div></div></div></div>' +
			'</div></div>';
		var $card = $(html);
		$('#feature-icons-repeater-list').append($card);
		affFiSetCardIconUi($card, iconClass);
		$card.find('.feature-title-input').val(title);
		$card.find('.feature-desc-input').val(desc);
		affFiSyncCardPreview($card);
	}
	function affFiUpdateAddButton(max) {
		var n = $('#feature-icons-repeater-list .feature-repeater-card').length;
		var $btn = $('#feature-icons-add-btn');
		if (n >= max) {
			$btn.prop('disabled', true).attr('title', affFeatureIconsUi.maxReached);
		} else {
			$btn.prop('disabled', false).removeAttr('title');
		}
	}
	function affInitFeatureIconsRepeater() {
		affFiCardSeq = 0;
		var max = affFeatureIconsReadMax();
		var items = affFeatureIconsReadItems();
		$('#feature-icons-repeater-list').empty();
		if (items.length === 0) {
			affFiAppendCard(null);
		} else {
			items.forEach(function(it) { affFiAppendCard(it); });
		}
		affFiUpdateAddButton(max);
		affFiApplyDisplayFromObj(affFeatureIconsReadDisplay());
		$('#feature-icons-use-demo-content').prop('checked', affFeatureIconsReadUseDemo());
	}
	$(document).on('click', '#feature-icons-repeater-list .aff-fi-opt', function(e) {
		e.preventDefault();
		var cls = $(this).attr('data-icon-class');
		var $card = $(this).closest('.feature-repeater-card');
		affFiSetCardIconUi($card, cls);
		affFiSyncCardPreview($card);
		affFiClosePickerPanel($card);
	});
	$(document).on('show.bs.collapse', '#login-page-block-settings-feature-icons .aff-fi-picker-collapse', function() {
		var opening = this;
		$('#login-page-block-settings-feature-icons .aff-fi-picker-collapse').each(function() {
			if (this !== opening && typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
				var inst = bootstrap.Collapse.getInstance(this);
				if (inst) { inst.hide(); }
			}
		});
		affFiHighlightPickerSelection($(opening).closest('.feature-repeater-card'));
	});
	$(document).on('click', '#feature-icons-add-btn', function() {
		var max = affFeatureIconsReadMax();
		if ($('#feature-icons-repeater-list .feature-repeater-card').length >= max) { return; }
		affFiAppendCard(null);
		affFiUpdateAddButton(max);
	});
	$(document).on('click', '#feature-icons-repeater-list .feature-remove-btn', function() {
		var max = affFeatureIconsReadMax();
		var $list = $('#feature-icons-repeater-list');
		$(this).closest('.feature-repeater-card').remove();
		if ($list.find('.feature-repeater-card').length === 0) { affFiAppendCard(null); }
		affFiUpdateAddButton(max);
	});
	$(document).on('input', '#feature-icons-repeater-list .feature-title-input', function() {
		affFiSyncCardPreview($(this).closest('.feature-repeater-card'));
	});
	function affFaqReadMax() {
		var raw = document.getElementById('aff-faq-initial');
		if (!raw) { return 12; }
		try { return parseInt(JSON.parse(raw.textContent).max, 10) || 12; } catch (e) { return 12; }
	}
	function affFaqReadItems() {
		var raw = document.getElementById('aff-faq-initial');
		if (!raw) { return []; }
		try {
			var o = JSON.parse(raw.textContent);
			return Array.isArray(o.items) ? o.items : [];
		} catch (e) { return []; }
	}
	function affFaqReadFirstOpen() {
		var raw = document.getElementById('aff-faq-initial');
		if (!raw) { return true; }
		try {
			var o = JSON.parse(raw.textContent);
			return o.first_item_open !== false && o.first_item_open !== 0 && o.first_item_open !== '0';
		} catch (e) { return true; }
	}
	function affFaqReadUseDemo() {
		var raw = document.getElementById('aff-faq-initial');
		if (!raw) { return false; }
		try {
			var o = JSON.parse(raw.textContent);
			return !!(o.use_demo_content === 1 || o.use_demo_content === '1' || o.use_demo_content === true);
		} catch (e) { return false; }
	}
	function affFaqAppendCard(item) {
		var question = item && item.question ? String(item.question) : '';
		var answer = item && item.answer ? String(item.answer) : '';
		var html = '<div class="aff-fi-card aff-faq-repeater-card"><div class="aff-fi-card-inner">' +
			'<div class="d-flex justify-content-between mb-2"><span class="aff-fi-card-kicker">' + affFiEscapeAttr(affFaqUi.itemLabel) + '</span>' +
			'<button type="button" class="btn btn-sm btn-link text-danger aff-faq-remove-btn">' + affFiEscapeAttr(affFaqUi.remove) + '</button></div>' +
			'<label class="aff-fi-field-label d-block">' + affFiEscapeAttr(affFaqUi.questionLabel) + '</label>' +
			'<input type="text" class="form-control form-control-sm aff-faq-q" maxlength="240" placeholder="' + affFiEscapeAttr(affFaqUi.questionPh) + '">' +
			'<label class="aff-fi-field-label d-block mt-2">' + affFiEscapeAttr(affFaqUi.answerLabel) + '</label>' +
			'<textarea class="form-control form-control-sm aff-faq-a" rows="3" maxlength="4000" placeholder="' + affFiEscapeAttr(affFaqUi.answerPh) + '"></textarea>' +
			'</div></div>';
		var $card = $(html);
		$('#faq-repeater-list').append($card);
		$card.find('.aff-faq-q').val(question);
		$card.find('.aff-faq-a').val(answer);
	}
	function affFaqUpdateAddButton(max) {
		var n = $('#faq-repeater-list .aff-faq-repeater-card').length;
		var $btn = $('#faq-repeater-add-btn');
		if (n >= max) {
			$btn.prop('disabled', true).attr('title', affFaqUi.maxReached);
		} else {
			$btn.prop('disabled', false).removeAttr('title');
		}
	}
	function affInitFaqRepeater() {
		var max = affFaqReadMax();
		var items = affFaqReadItems();
		$('#faq-repeater-list').empty();
		$('#aff-faq-first-open').prop('checked', affFaqReadFirstOpen());
		$('#aff-faq-use-demo-content').prop('checked', affFaqReadUseDemo());
		if (items.length === 0) {
			affFaqAppendCard(null);
		} else {
			items.forEach(function(it) { affFaqAppendCard(it); });
		}
		affFaqUpdateAddButton(max);
	}
	$(document).on('click', '#faq-repeater-add-btn', function() {
		var max = affFaqReadMax();
		if ($('#faq-repeater-list .aff-faq-repeater-card').length >= max) { return; }
		affFaqAppendCard(null);
		affFaqUpdateAddButton(max);
	});
	$(document).on('click', '#faq-repeater-list .aff-faq-remove-btn', function() {
		var max = affFaqReadMax();
		var $list = $('#faq-repeater-list');
		$(this).closest('.aff-faq-repeater-card').remove();
		if ($list.find('.aff-faq-repeater-card').length === 0) { affFaqAppendCard(null); }
		affFaqUpdateAddButton(max);
	});
	function affReadJsonScriptEl(id) {
		var el = document.getElementById(id);
		if (!el) { return null; }
		try { return JSON.parse(String(el.textContent).trim()); } catch (e) { return null; }
	}
	$(document).on('click', '#feature-icons-load-demo', function() {
		var demo = affReadJsonScriptEl('aff-feature-icons-demo-json');
		if (!Array.isArray(demo) || !demo.length) { return; }
		var max = affFeatureIconsReadMax();
		affFiCardSeq = 0;
		$('#feature-icons-repeater-list').empty();
		demo.slice(0, max).forEach(function(it) { affFiAppendCard(it && typeof it === 'object' ? it : null); });
		affFiUpdateAddButton(max);
		$('#feature-icons-use-demo-content').prop('checked', true);
	});
	$(document).on('click', '#faq-repeater-load-demo', function() {
		var demo = affReadJsonScriptEl('aff-faq-demo-json');
		if (!Array.isArray(demo) || !demo.length) { return; }
		var max = affFaqReadMax();
		$('#faq-repeater-list').empty();
		demo.slice(0, max).forEach(function(it) { affFaqAppendCard(it && typeof it === 'object' ? it : null); });
		affFaqUpdateAddButton(max);
		$('#aff-faq-first-open').prop('checked', true);
		$('#aff-faq-use-demo-content').prop('checked', true);
	});
	var affVideoCardSeq = 0;
	function affVideoReadSeed() {
		var el = document.getElementById('aff-video-initial');
		if (!el) { return {}; }
		try { return JSON.parse(el.textContent) || {}; } catch (e) { return {}; }
	}
	function affVideoReadMax() {
		var s = affVideoReadSeed();
		return parseInt(s.max, 10) || 4;
	}
	function affVideoReadItems() {
		var s = affVideoReadSeed();
		return Array.isArray(s.items) ? s.items : [];
	}
	function affVideoAppendCard(item) {
		var url = item && item.url ? String(item.url) : '';
		var title = item && item.title ? String(item.title) : '';
		var seq = ++affVideoCardSeq;
		var html = '<div class="aff-fi-card aff-video-repeater-card"><div class="aff-fi-card-inner">' +
			'<div class="d-flex justify-content-between align-items-center mb-2">' +
			'<span class="aff-fi-card-kicker">' + affFiEscapeAttr(affVideoUi.itemLabel.replace('%d', seq)) + '</span>' +
			'<button type="button" class="btn btn-sm btn-link text-danger aff-video-remove-btn">' + affFiEscapeAttr(affVideoUi.remove) + '</button></div>' +
			'<label class="aff-fi-field-label d-block">' + affFiEscapeAttr(affVideoUi.urlLabel) + '</label>' +
			'<input type="url" class="form-control form-control-sm aff-video-url mb-2" maxlength="500" placeholder="https://www.youtube.com/watch?v=… or https://vimeo.com/…">' +
			'<input type="text" class="form-control form-control-sm aff-video-title" maxlength="120" placeholder="' + affFiEscapeAttr(affVideoUi.titlePh) + '">' +
			'</div></div>';
		var $card = $(html);
		$('#video-repeater-list').append($card);
		$card.find('.aff-video-url').val(url);
		$card.find('.aff-video-title').val(title);
	}
	function affVideoUpdateAddButton(max) {
		var n = $('#video-repeater-list .aff-video-repeater-card').length;
		var $btn = $('#video-repeater-add-btn');
		if (n >= max) {
			$btn.prop('disabled', true).attr('title', affVideoUi.maxReached);
		} else {
			$btn.prop('disabled', false).removeAttr('title');
		}
	}
	function affInitVideoRepeater() {
		affVideoCardSeq = 0;
		var max = affVideoReadMax();
		var items = affVideoReadItems();
		var seed = affVideoReadSeed();
		$('#video-repeater-list').empty();
		if (items.length === 0) {
			affVideoAppendCard(null);
		} else {
			items.forEach(function(it) { affVideoAppendCard(it); });
		}
		affVideoUpdateAddButton(max);
		var cols = parseInt(seed.columns, 10) || 1;
		if ([1, 2, 3].indexOf(cols) !== -1) { $('#block-video-columns').val(String(cols)); }
		var mw = parseInt(seed.max_width, 10) || 800;
		if ([500, 800, 1100].indexOf(mw) !== -1) { $('#block-video-max-width').val(String(mw)); }
		$('#block-video-autoplay').prop('checked', !!seed.autoplay);
		$('#block-video-use-demo-content').prop('checked', !!(seed.use_demo_content === 1 || seed.use_demo_content === '1' || seed.use_demo_content === true));
	}
	function affInitLivePulseForm() {
		var o = affReadJsonScriptEl('aff-live-pulse-initial');
		if (!o || typeof o !== 'object') { return; }
		var sec = parseInt(o.poll_interval_sec, 10);
		if (!isNaN(sec) && sec >= 15 && sec <= 120) {
			$('#live-pulse-poll-interval').val(String(sec));
		}
		var pos = o.toast_position || 'bottom-right';
		if (['bottom-right', 'bottom-left', 'bottom-center'].indexOf(pos) !== -1) {
			$('#live-pulse-toast-position').val(pos);
		}
		$('#live-pulse-use-demo-content').prop('checked', !!(o.use_demo_content === 1 || o.use_demo_content === '1' || o.use_demo_content === true));
	}
	$(document).on('click', '#video-repeater-add-btn', function() {
		var max = affVideoReadMax();
		if ($('#video-repeater-list .aff-video-repeater-card').length >= max) { return; }
		affVideoAppendCard(null);
		affVideoUpdateAddButton(max);
	});
	$(document).on('click', '#video-repeater-list .aff-video-remove-btn', function() {
		var max = affVideoReadMax();
		var $list = $('#video-repeater-list');
		$(this).closest('.aff-video-repeater-card').remove();
		if ($list.find('.aff-video-repeater-card').length === 0) { affVideoAppendCard(null); }
		affVideoUpdateAddButton(max);
	});
	$(document).on('click', '#video-block-load-demo', function() {
		var max = affVideoReadMax();
		affVideoCardSeq = 0;
		$('#video-repeater-list').empty();
		affVideoAppendCard({ url: 'https://www.youtube.com/watch?v=TThQqFDD1t4', title: 'Getting Started with Affiliate Marketing' });
		affVideoAppendCard({ url: 'https://www.youtube.com/watch?v=wLm-yYco8tQ', title: 'Step-by-Step Affiliate Guide' });
		affVideoAppendCard({ url: 'https://www.youtube.com/watch?v=SJwwe1YXisA', title: 'Affiliate Marketing with AI' });
		affVideoUpdateAddButton(max);
		$('#block-video-columns').val('3');
		$('#block-video-max-width').val('1100');
		$('#block-video-autoplay').prop('checked', false);
		$('#block-video-use-demo-content').prop('checked', true);
	});
	$(document).on('click', '#platform-stats-load-demo', function() {
		if (!affPlatformStatsDemoLabels || typeof affPlatformStatsDemoLabels !== 'object') { return; }
		$('#platform-stats-active-label').val(affPlatformStatsDemoLabels.active || '');
		$('#platform-stats-withdrawals-label').val(affPlatformStatsDemoLabels.withdrawals || '');
		$('#platform-stats-use-demo-values').prop('checked', true);
	});
	$(document).on('click', '#top-earners-load-demo', function() {
		$('#top-earners-display-limit').val('5');
		$('#top-earners-privacy-mode').prop('checked', false);
		$('#top-earners-use-demo-rows').prop('checked', true);
	});
	var blockSettingsSaveMode = '';
	$(document).on('click', '.login-page-block-settings', function(e) {
		e.preventDefault();
		var blockTitle = $(this).attr('data-block-title') || '';
		var modalKind = $(this).attr('data-settings-modal') || '';
		var $modalDlg = $('#login-page-block-settings-modal-dialog');
		$modalDlg.removeClass('modal-xl modal-dialog-scrollable').addClass('modal-lg');
		document.getElementById('login-page-block-settings-modal-title').textContent = blockSettingsModalTitleTpl.replace('%s', blockTitle);
		var $gen = $('#login-page-block-settings-generic');
		var $ps = $('#login-page-block-settings-platform-stats');
		var $te = $('#login-page-block-settings-top-earners');
		var $vid = $('#login-page-block-settings-video');
		var $lp = $('#login-page-block-settings-live-pulse');
		var $feat = $('#login-page-block-settings-feature-icons');
		var $faq = $('#login-page-block-settings-faq');
		var $save = $('#login-page-block-settings-save-btn');
		blockSettingsSaveMode = '';
		$gen.addClass('d-none');
		$ps.addClass('d-none');
		$te.addClass('d-none');
		$vid.addClass('d-none');
		$lp.addClass('d-none');
		$feat.addClass('d-none');
		$faq.addClass('d-none');
		$save.addClass('d-none');
		if (modalKind === 'top_earners') {
			blockSettingsSaveMode = 'top_earners';
			$te.removeClass('d-none');
			$save.removeClass('d-none');
		} else if (modalKind === 'platform_stats') {
			blockSettingsSaveMode = 'platform_stats';
			$ps.removeClass('d-none');
			$save.removeClass('d-none');
		} else if (modalKind === 'video_block') {
			blockSettingsSaveMode = 'video_block';
			$vid.removeClass('d-none');
			$save.removeClass('d-none');
			$modalDlg.removeClass('modal-lg').addClass('modal-xl modal-dialog-scrollable');
			affInitVideoRepeater();
		} else if (modalKind === 'live_pulse') {
			blockSettingsSaveMode = 'live_pulse';
			$lp.removeClass('d-none');
			$save.removeClass('d-none');
			affInitLivePulseForm();
		} else if (modalKind === 'feature_icons') {
			blockSettingsSaveMode = 'feature_icons';
			$feat.removeClass('d-none');
			$save.removeClass('d-none');
			$modalDlg.removeClass('modal-lg').addClass('modal-xl modal-dialog-scrollable');
			affInitFeatureIconsRepeater();
		} else if (modalKind === 'faq_block') {
			blockSettingsSaveMode = 'faq_block';
			$faq.removeClass('d-none');
			$save.removeClass('d-none');
			$modalDlg.removeClass('modal-lg').addClass('modal-xl modal-dialog-scrollable');
			affInitFaqRepeater();
		} else {
			$gen.removeClass('d-none');
			document.getElementById('login-page-block-settings-generic-text').textContent = blockSettingsModalBodyTpl.replace('%s', blockTitle);
		}
		var modalEl = document.getElementById('login-page-block-settings-modal');
		if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
			bootstrap.Modal.getOrCreateInstance(modalEl).show();
		}
	});
	$('#login-page-block-settings-save-btn').on('click', function() {
		if (affBlocksDemoMode) {
			if (typeof showToast === 'function') {
				showToast(<?= json_encode(__('admin.error')) ?>, affBlocksDemoMsg, 'error', 4000);
			}
			return;
		}
		var $btn = $(this);
		$btn.prop('disabled', true);
		var url = '';
		var payload = {};
		var affFiSavedSeedPayload = null;
		var affFaqSavedSeedPayload = null;
		var affVideoSavedSeedPayload = null;
		var affPulseSavedSeedPayload = null;
		if (blockSettingsSaveMode === 'top_earners') {
			url = '<?= base_url('admincontrol/save_login_top_earners_block_settings') ?>';
			payload = {
				display_limit: $('#top-earners-display-limit').val(),
				privacy_mode: $('#top-earners-privacy-mode').is(':checked') ? 1 : 0,
				demo_rows: $('#top-earners-use-demo-rows').is(':checked') ? 1 : 0
			};
		} else if (blockSettingsSaveMode === 'platform_stats') {
			url = '<?= base_url('admincontrol/save_login_stats_block_settings') ?>';
			payload = {
				active_label: $('#platform-stats-active-label').val(),
				withdrawals_label: $('#platform-stats-withdrawals-label').val(),
				demo_values: $('#platform-stats-use-demo-values').is(':checked') ? 1 : 0
			};
		} else if (blockSettingsSaveMode === 'video_block') {
			url = '<?= base_url('admincontrol/save_login_video_block_settings') ?>';
			var videoItems = [];
			$('#video-repeater-list .aff-video-repeater-card').each(function() {
				var $c = $(this);
				var vu = ($c.find('.aff-video-url').val() || '').trim();
				var vt = ($c.find('.aff-video-title').val() || '').trim();
				if (!vu) { return; }
				videoItems.push({ url: vu, title: vt });
			});
			var videoCols = parseInt($('#block-video-columns').val(), 10) || 1;
			var videoAutoplay = $('#block-video-autoplay').is(':checked') ? 1 : 0;
			var videoMaxW = parseInt($('#block-video-max-width').val(), 10) || 800;
			var videoUseDemo = $('#block-video-use-demo-content').is(':checked') ? 1 : 0;
			affVideoSavedSeedPayload = { items: videoItems, max: affVideoReadMax(), columns: videoCols, autoplay: videoAutoplay, max_width: videoMaxW, use_demo_content: videoUseDemo };
			payload = { video_settings: JSON.stringify({ items: videoItems, columns: videoCols, autoplay: videoAutoplay, max_width: videoMaxW, use_demo_content: videoUseDemo }) };
		} else if (blockSettingsSaveMode === 'live_pulse') {
			url = '<?= base_url('admincontrol/save_login_live_pulse_block_settings') ?>';
			var pulseSec = parseInt($('#live-pulse-poll-interval').val(), 10) || 28;
			var pulsePos = $('#live-pulse-toast-position').val() || 'bottom-right';
			var pulseUseDemo = $('#live-pulse-use-demo-content').is(':checked') ? 1 : 0;
			affPulseSavedSeedPayload = { poll_interval_sec: pulseSec, toast_position: pulsePos, use_demo_content: pulseUseDemo };
			payload = { poll_interval_sec: $('#live-pulse-poll-interval').val(), toast_position: $('#live-pulse-toast-position').val(), use_demo_content: pulseUseDemo };
		} else if (blockSettingsSaveMode === 'feature_icons') {
			url = '<?= base_url('admincontrol/save_login_features_block_settings') ?>';
			var featItems = [];
			$('#feature-icons-repeater-list .feature-repeater-card').each(function() {
				var $c = $(this);
				var icon = ($c.find('.feature-icon-class').val() || '').trim();
				var title = ($c.find('.feature-title-input').val() || '').trim();
				var description = ($c.find('.feature-desc-input').val() || '').trim();
				if (!icon && !title && !description) { return; }
				featItems.push({ icon: icon, title: title, description: description });
			});
			var display = {
				columns_sm: parseInt($('#aff-fi-disp-cols-sm').val(), 10) || 2,
				columns_md: parseInt($('#aff-fi-disp-cols-md').val(), 10) || 4,
				columns_lg: parseInt($('#aff-fi-disp-cols-lg').val(), 10) || 4,
				variant: $('#aff-fi-disp-variant').val() === 'plain' ? 'plain' : 'cards',
				show_description: $('#aff-fi-disp-show-desc').is(':checked'),
				icon_style: $('#aff-fi-disp-icon-style').val() || 'circle'
			};
			var featUseDemo = $('#feature-icons-use-demo-content').is(':checked') ? 1 : 0;
			affFiSavedSeedPayload = { items: featItems, max: affFeatureIconsReadMax(), display: display, use_demo_content: featUseDemo };
			payload = { features_settings: JSON.stringify({
				items: featItems,
				columns_sm: display.columns_sm,
				columns_md: display.columns_md,
				columns_lg: display.columns_lg,
				variant: display.variant,
				show_description: display.show_description,
				icon_style: display.icon_style,
				use_demo_content: featUseDemo
			}) };
		} else if (blockSettingsSaveMode === 'faq_block') {
			url = '<?= base_url('admincontrol/save_login_faq_block_settings') ?>';
			var faqItems = [];
			$('#faq-repeater-list .aff-faq-repeater-card').each(function() {
				var $c = $(this);
				var question = ($c.find('.aff-faq-q').val() || '').trim();
				var answer = ($c.find('.aff-faq-a').val() || '').trim();
				if (!question && !answer) { return; }
				faqItems.push({ question: question, answer: answer });
			});
			var firstOpen = $('#aff-faq-first-open').is(':checked');
			var faqUseDemo = $('#aff-faq-use-demo-content').is(':checked') ? 1 : 0;
			affFaqSavedSeedPayload = { items: faqItems, max: affFaqReadMax(), first_item_open: firstOpen, use_demo_content: faqUseDemo };
			payload = { faq_settings: JSON.stringify({ items: faqItems, first_item_open: firstOpen, use_demo_content: faqUseDemo }) };
		} else {
			$btn.prop('disabled', false);
			return;
		}
		var affFiMaxForSeed = affFeatureIconsReadMax();
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'json',
			data: payload,
			success: function(json) {
				if (json && json.success) {
					if (affFiSavedSeedPayload !== null) {
						var seed = document.getElementById('aff-feature-icons-initial');
						if (seed) {
							affFiSavedSeedPayload.max = affFiMaxForSeed;
							seed.textContent = JSON.stringify(affFiSavedSeedPayload);
						}
					}
					if (affFaqSavedSeedPayload !== null) {
						var seedFaq = document.getElementById('aff-faq-initial');
						if (seedFaq) {
							affFaqSavedSeedPayload.max = affFaqReadMax();
							seedFaq.textContent = JSON.stringify(affFaqSavedSeedPayload);
						}
					}
					if (affVideoSavedSeedPayload !== null) {
						var seedVideo = document.getElementById('aff-video-initial');
						if (seedVideo) {
							seedVideo.textContent = JSON.stringify(affVideoSavedSeedPayload);
						}
					}
					if (affPulseSavedSeedPayload !== null) {
						var seedPulse = document.getElementById('aff-live-pulse-initial');
						if (seedPulse) {
							seedPulse.textContent = JSON.stringify(affPulseSavedSeedPayload);
						}
					}
					if (typeof showToast === 'function') {
						showToast(<?= json_encode(__('admin.success')) ?>, json.success, 'success', 4000);
					} else if (typeof showPrintMessage === 'function') {
						showPrintMessage(json.success, 'success');
					}
					var modalEl = document.getElementById('login-page-block-settings-modal');
					if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
						var inst = bootstrap.Modal.getInstance(modalEl);
						if (inst) { inst.hide(); }
					}
				} else if (json && json.message) {
					if (typeof showToast === 'function') {
						showToast(<?= json_encode(__('admin.error')) ?>, json.message, 'error', 5000);
					}
				}
			},
			error: function() {
				if (typeof showToast === 'function') {
					showToast(<?= json_encode(__('admin.error')) ?>, <?= json_encode(__('admin.save_failed')) ?>, 'error', 5000);
				}
			},
			complete: function() {
				$btn.prop('disabled', false);
			}
		});
	});
});
</script>
