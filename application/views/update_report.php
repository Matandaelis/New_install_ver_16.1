<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= __('admin.system_update_report') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap-icons.css') ?>?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/admin-dashboard-custom.css') ?>?v=<?= av() ?>">
</head>
<body class="update-report-page">

<?php
$update_attempted  = is_array($result) && !empty($result);
$is_success        = true;
$already_latest    = false;
$count_success     = 0;
$count_error       = 0;
$count_warning     = 0;
$count_info        = 0;

$log_errors = [];
$log_warnings = [];
if ($update_attempted) {
    $scan_ln = 0;
    foreach ($result as $row) {
        foreach ($row as $key => $val) {
            $scan_ln++;
            if ($key === 'success') $count_success++;
            if ($key === 'error')   { $count_error++; if ($is_success && str_contains($val,'already a latest version')) $already_latest=true; $is_success=false; $log_errors[] = ['line' => $scan_ln, 'msg' => $val]; }
            if ($key === 'warning') { $count_warning++; $log_warnings[] = ['line' => $scan_ln, 'msg' => $val]; }
            if ($key === 'info')    $count_info++;
        }
    }
}
$total_ops = $count_success + $count_error + $count_warning + $count_info;

if (!$update_attempted) { $zone='info'; }
elseif ($is_success)    { $zone='success'; }
else                    { $zone='danger'; }

$ur_trunc = function ($s, $max = 220) {
    $s = (string) $s;
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        return mb_strlen($s) > $max ? mb_substr($s, 0, $max) . '...' : $s;
    }
    return strlen($s) > $max ? substr($s, 0, $max) . '...' : $s;
};

$zone_icon = ['success'=>'bi-check-lg','danger'=>'bi-exclamation-lg','info'=>'bi-info-lg'][$zone];
$zone_ico_sm = ['success'=>'bi-check-circle-fill','danger'=>'bi-x-circle-fill','info'=>'bi-info-circle-fill'][$zone];
$zone_label = ['success'=>'Success','danger'=>'Issues Found','info'=>'No Data'][$zone];

$headline = __('admin.no_update_results');
$subline  = __('admin.no_update_logs_found');
if ($update_attempted) {
    if ($is_success)    { $headline = __('admin.update_completed_successfully'); $subline = __('admin.system_updated_to_version') . ' ' . SCRIPT_VERSION; }
    elseif ($already_latest){ $headline = __('admin.system_already_updated');   $subline = __('admin.system_running_version')   . ' ' . SCRIPT_VERSION; }
    else                { $headline = __('admin.update_completed_with_issues'); $subline = __('admin.review_console_output')    . ' ' . SCRIPT_VERSION; }
}
?>

<!-- Sticky top bar -->
<div class="ur-topbar">
    <a href="<?= base_url('admincontrol/dashboard') ?>" class="ur-back">
        <i class="bi bi-arrow-left"></i><?= __('admin.back_to_dashboard') ?>
    </a>
    <div class="ur-topbar-right">
        <?php if ($update_attempted): ?>
        <a href="<?= base_url('debug/sysupdatereport') ?>" class="ur-btn-secondary" style="padding:6px 14px;font-size:.78rem;" target="_blank">
            <i class="bi bi-terminal"></i><?= __('admin.view_detailed_logs') ?>
        </a>
        <?php endif; ?>
        <span class="ur-ver">v<?= SCRIPT_VERSION ?></span>
    </div>
</div>

<!-- Hero — all critical info above the fold -->
<div class="ur-hero <?= $zone ?>">
    <div class="ur-hero-bg">
        <span style="width:200px;height:200px;top:-60px;right:-40px;background:<?= $zone==='success'?'rgba(63,185,80,.05)':($zone==='danger'?'rgba(248,81,73,.05)':'rgba(88,166,255,.05)') ?>;"></span>
        <span style="width:120px;height:120px;bottom:-30px;left:40px;background:<?= $zone==='success'?'rgba(63,185,80,.03)':($zone==='danger'?'rgba(248,81,73,.03)':'rgba(88,166,255,.03)') ?>;border-radius:50%;"></span>
    </div>

    <!-- Status icon -->
    <div class="ur-icon-ring <?= $zone ?>">
        <i class="bi <?= $zone_icon ?>"></i>
    </div>

    <!-- Text + stats -->
    <div class="ur-hero-body">
        <div class="ur-status-badge <?= $zone ?>">
            <i class="bi <?= $zone_ico_sm ?>"></i><?= $zone_label ?>
        </div>
        <h2 class="ur-title"><?= $headline ?></h2>
        <p class="ur-sub"><?= $subline ?> &nbsp;&bull;&nbsp; <i class="bi bi-calendar3 me-1"></i><?= date('F j, Y \a\t g:i A') ?></p>

        <?php if ($update_attempted && $total_ops > 0): ?>
        <div class="ur-stats">
            <div class="ur-stat">
                <div class="ur-stat-val" style="color:#3fb950;"><?= $count_success ?></div>
                <div class="ur-stat-lbl"><i class="bi bi-check-circle me-1"></i>Completed</div>
            </div>
            <div class="ur-stat" style="width:1px;background:#21262d;align-self:stretch;"></div>
            <div class="ur-stat <?= $count_error > 0 ? 'ur-stat-clickable' : '' ?>" id="ur-stat-errors" <?= $count_error > 0 ? 'role="button" tabindex="0" title="Jump to first error"' : '' ?>>
                <div class="ur-stat-val" style="color:<?= $count_error>0?'#f85149':'#484860' ?>;"><?= $count_error ?></div>
                <div class="ur-stat-lbl"><i class="bi bi-x-circle me-1"></i>Errors<?= $count_error > 0 ? ' <i class="bi bi-arrow-down-circle" style="font-size:.65rem;opacity:.7;"></i>' : '' ?></div>
            </div>
            <div class="ur-stat" style="width:1px;background:#21262d;align-self:stretch;"></div>
            <div class="ur-stat <?= $count_warning > 0 ? 'ur-stat-clickable' : '' ?>" id="ur-stat-warnings" <?= $count_warning > 0 ? 'role="button" tabindex="0" title="Jump to first warning"' : '' ?>>
                <div class="ur-stat-val" style="color:<?= $count_warning>0?'#e3b341':'#484860' ?>;"><?= $count_warning ?></div>
                <div class="ur-stat-lbl"><i class="bi bi-exclamation-triangle me-1"></i>Warnings<?= $count_warning > 0 ? ' <i class="bi bi-arrow-down-circle" style="font-size:.65rem;opacity:.7;"></i>' : '' ?></div>
            </div>
            <div class="ur-stat" style="width:1px;background:#21262d;align-self:stretch;"></div>
            <div class="ur-stat">
                <div class="ur-stat-val" style="color:#8b949e;"><?= $count_info ?></div>
                <div class="ur-stat-lbl"><i class="bi bi-info-circle me-1"></i>Info</div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- CTA buttons — always visible, never requires scrolling -->
    <div class="ur-actions">
        <a href="<?= base_url('admincontrol/dashboard') ?>" class="ur-btn-primary <?= $zone==='danger'?'danger':'' ?>">
            <i class="bi bi-columns-gap"></i><?= __('admin.back_to_dashboard') ?>
        </a>
        <?php if ($update_attempted): ?>
        <button type="button" class="ur-btn-secondary" id="toggle-console-btn" onclick="toggleConsole()">
            <i class="bi bi-terminal-x" id="console-btn-icon"></i>
            <span id="console-btn-text">Hide Console</span>
        </button>
        <?php endif; ?>
    </div>
</div>

<!-- Security notice (if installer directory still present) -->
<?php if ($update_attempted && $is_success && file_exists(FCPATH . 'install/')): ?>
<div class="ur-security" id="installer-alert">
    <div class="ur-security-icon"><i class="bi bi-shield-exclamation" style="color:#e3b341;"></i></div>
    <div style="flex:1;">
        <div class="ur-security-title"><?= __('admin.secure_your_installation') ?></div>
        <div class="ur-security-sub"><?= __('admin.remove_installer_recommendation') ?></div>
        <button type="button" class="ur-sec-btn" id="remove-installer-btn">
            <i class="bi bi-trash3"></i><?= __('admin.remove_installer_folder_now') ?>
        </button>
        <small class="d-block mt-2" id="remove-feedback" style="color:#8b949e;"></small>
    </div>
</div>
<?php elseif ($update_attempted && $is_success): ?>
<div class="ur-security secured">
    <div class="ur-security-icon"><i class="bi bi-shield-check" style="color:#3fb950;"></i></div>
    <div>
        <div class="ur-security-title"><?= __('admin.installation_secured') ?></div>
        <div class="ur-security-sub" style="margin-bottom:0;"><?= __('admin.installer_removed_successfully') ?></div>
    </div>
</div>
<?php endif; ?>

<?php if ($update_attempted && ($count_error > 0 || $count_warning > 0)): ?>
<div class="ur-error-panel <?= $count_error === 0 && $count_warning > 0 ? 'has-warn-only' : '' ?>" id="ur-error-panel">
    <div class="ur-error-panel-title">
        <i class="bi bi-lightning-charge-fill" style="color:#f85149;"></i>
        Quick view
        <?php if ($count_error > 0): ?><span class="badge bg-danger"><?= (int)$count_error ?> error<?= $count_error !== 1 ? 's' : '' ?></span><?php endif; ?>
        <?php if ($count_warning > 0): ?><span class="badge bg-warning text-dark"><?= (int)$count_warning ?> warning<?= $count_warning !== 1 ? 's' : '' ?></span><?php endif; ?>
    </div>
    <div class="ur-error-panel-actions">
        <?php if ($count_error > 0): ?>
        <button type="button" class="btn btn-sm btn-outline-danger" id="ur-btn-first-error"><i class="bi bi-skip-end-fill me-1"></i>Jump to first error</button>
        <button type="button" class="btn btn-sm btn-outline-danger" id="ur-btn-filter-errors"><i class="bi bi-funnel me-1"></i>Show errors only</button>
        <?php endif; ?>
        <?php if ($count_warning > 0): ?>
        <button type="button" class="btn btn-sm btn-outline-warning" id="ur-btn-first-warning"><i class="bi bi-skip-end-fill me-1"></i>Jump to first warning</button>
        <button type="button" class="btn btn-sm btn-outline-warning" id="ur-btn-filter-warnings"><i class="bi bi-funnel me-1"></i>Show warnings only</button>
        <?php endif; ?>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="ur-btn-filter-all"><i class="bi bi-list-ul me-1"></i>Show all lines</button>
        <?php if ($count_error > 1): ?>
        <button type="button" class="btn btn-sm btn-outline-light" id="ur-btn-next-error" style="border-color:#30363d;"><i class="bi bi-chevron-down me-1"></i>Next error</button>
        <?php endif; ?>
    </div>
    <?php if ($count_error > 0): ?>
    <ul class="ur-error-list">
        <?php foreach ($log_errors as $e): ?>
        <li><a href="#ur-line-<?= (int)$e['line'] ?>" class="ur-jump-line" data-line="<?= (int)$e['line'] ?>">Line <?= (int)$e['line'] ?></a> &mdash; <?= htmlspecialchars($ur_trunc($e['msg'])) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <?php if ($count_warning > 0): ?>
    <?php if ($count_error > 0): ?><div class="small text-muted mt-2 mb-1">Warnings</div><?php endif; ?>
    <ul class="ur-error-list">
        <?php foreach ($log_warnings as $w): ?>
        <li><a href="#ur-line-<?= (int)$w['line'] ?>" class="ur-jump-line ur-warn-link" data-line="<?= (int)$w['line'] ?>">Line <?= (int)$w['line'] ?></a> &mdash; <?= htmlspecialchars($ur_trunc($w['msg'])) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Console — collapsed by default, expands on demand -->
<?php if ($update_attempted): ?>
<div class="ur-console-wrap">
    <div class="ur-console-toggle open" id="console-toggle-bar" onclick="toggleConsole()">
        <div class="ur-console-toggle-left">
            <div class="ur-console-dots">
                <span style="background:#ff5f57;"></span>
                <span style="background:#febc2e;"></span>
                <span style="background:#28c840;"></span>
            </div>
            <span style="font-family:monospace;font-size:.8rem;color:#8b949e;">
                <i class="bi bi-terminal me-1"></i><?= __('admin.update_console') ?>
                <span style="margin-left:8px;color:#484860;">&mdash; <?= $total_ops ?> operation<?= $total_ops!==1?'s':'' ?></span>
            </span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;color:#484860;font-size:.78rem;">
            <span id="toggle-label">Click to collapse</span>
            <i class="bi bi-chevron-down toggle-icon" id="toggle-chevron" style="transform:rotate(180deg);"></i>
        </div>
    </div>
    <div class="ur-console-toolbar open" id="ur-console-toolbar">
        <span class="ur-console-toolbar-label">Console filter:</span>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-ur-filter="all">All</button>
        <button type="button" class="btn btn-sm btn-outline-danger" data-ur-filter="errors">Errors</button>
        <button type="button" class="btn btn-sm btn-outline-warning text-dark" data-ur-filter="warnings">Warnings</button>
        <button type="button" class="btn btn-sm btn-outline-info" data-ur-filter="info">Info only</button>
    </div>
    <div class="ur-console-body open" id="console-body">
        <?php
        $ln = 1;
        foreach ($result as $row) {
            foreach ($row as $key => $val) {
                $cls = 'nfo'; $ico = 'bi-info-circle-fill';
                switch($key) {
                    case 'success': $cls='ok';   $ico='bi-check-circle-fill'; break;
                    case 'error':   $cls='err';  $ico='bi-x-circle-fill';     break;
                    case 'warning': $cls='warn'; $ico='bi-exclamation-triangle-fill'; break;
                }
                echo '<div class="ur-cline '.$cls.'" id="ur-line-'.$ln.'" data-log-kind="'.htmlspecialchars($key, ENT_QUOTES, 'UTF-8').'">';
                echo '<span class="ts">'.str_pad($ln++,4,'0',STR_PAD_LEFT).'</span>';
                echo '<i class="bi '.$ico.' flex-shrink-0" style="font-size:.65rem;margin-top:5px;"></i>';
                echo '<span class="msg">'.htmlspecialchars($val).'</span>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>
<?php endif; ?>

<!-- No results state -->
<?php if (!$update_attempted): ?>
<div class="ur-empty">
    <i class="bi bi-inbox" style="font-size:3rem;color:#30363d;display:block;margin-bottom:16px;"></i>
    <h4 style="color:#e6edf3;margin-bottom:8px;"><?= __('admin.no_update_results') ?></h4>
    <p style="color:#8b949e;margin:0;"><?= __('admin.no_update_logs_found') ?></p>
    <a href="<?= base_url('admincontrol/dashboard') ?>" class="ur-btn-primary" style="display:inline-flex;margin-top:24px;">
        <i class="bi bi-columns-gap"></i><?= __('admin.back_to_dashboard') ?>
    </a>
</div>
<?php endif; ?>

<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js'); ?>"></script>
<script>
var consoleOpen = true;

function toggleConsole() {
    consoleOpen = !consoleOpen;
    var body     = document.getElementById('console-body');
    var toolbar  = document.getElementById('ur-console-toolbar');
    var bar      = document.getElementById('console-toggle-bar');
    var chevron  = document.getElementById('toggle-chevron');
    var label    = document.getElementById('toggle-label');
    var btnText  = document.getElementById('console-btn-text');
    var btnIcon  = document.getElementById('console-btn-icon');

    if (consoleOpen) {
        body.classList.add('open');
        if (toolbar) toolbar.classList.add('open');
        bar.classList.add('open');
        chevron.style.transform = 'rotate(180deg)';
        label.textContent = 'Click to collapse';
        if(btnText) btnText.textContent = 'Hide Console';
        if(btnIcon) btnIcon.className   = 'bi bi-terminal-x';
        setTimeout(function(){ body.scrollTop = body.scrollHeight; }, 60);
    } else {
        body.classList.remove('open');
        if (toolbar) toolbar.classList.remove('open');
        bar.classList.remove('open');
        chevron.style.transform = '';
        label.textContent = 'Click to expand';
        if(btnText) btnText.textContent = 'Show Console';
        if(btnIcon) btnIcon.className   = 'bi bi-terminal';
    }
}

function urApplyConsoleFilter(mode) {
    var body = document.getElementById('console-body');
    if (!body) return;
    body.classList.remove('ur-filter-errors', 'ur-filter-warnings', 'ur-filter-info');
    if (mode === 'errors') body.classList.add('ur-filter-errors');
    else if (mode === 'warnings') body.classList.add('ur-filter-warnings');
    else if (mode === 'info') body.classList.add('ur-filter-info');
}

function urScrollToLine(lineNum) {
    var el = document.getElementById('ur-line-' + lineNum);
    var body = document.getElementById('console-body');
    if (!el || !body) return;
    if (!body.classList.contains('open')) {
        toggleConsole();
    }
    el.classList.remove('ur-line-flash');
    void el.offsetWidth;
    el.classList.add('ur-line-flash');
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    setTimeout(function(){ el.classList.remove('ur-line-flash'); }, 1400);
}

document.addEventListener('DOMContentLoaded', function(){
    var c = document.getElementById('console-body');
    var panel = document.getElementById('ur-error-panel');
    if (panel) {
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else if (c) {
        c.scrollTop = c.scrollHeight;
    }

    var errNodes = document.querySelectorAll('.ur-cline.err');
    var warnNodes = document.querySelectorAll('.ur-cline.warn');
    var errIdx = -1;

    function bindClick(id, fn) {
        var n = document.getElementById(id);
        if (n) n.addEventListener('click', fn);
        if (n) n.addEventListener('keydown', function(ev) {
            if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); fn(); }
        });
    }

    bindClick('ur-stat-errors', function() {
        if (errNodes.length) urScrollToLine(parseInt(errNodes[0].id.replace('ur-line-',''), 10));
    });
    bindClick('ur-stat-warnings', function() {
        if (warnNodes.length) urScrollToLine(parseInt(warnNodes[0].id.replace('ur-line-',''), 10));
    });

    var b1 = document.getElementById('ur-btn-first-error');
    if (b1) b1.addEventListener('click', function() {
        if (errNodes.length) urScrollToLine(parseInt(errNodes[0].id.replace('ur-line-',''), 10));
    });
    var b1w = document.getElementById('ur-btn-first-warning');
    if (b1w) b1w.addEventListener('click', function() {
        if (warnNodes.length) urScrollToLine(parseInt(warnNodes[0].id.replace('ur-line-',''), 10));
    });
    var bf = document.getElementById('ur-btn-filter-errors');
    if (bf) bf.addEventListener('click', function() { urApplyConsoleFilter('errors'); });
    var bfw = document.getElementById('ur-btn-filter-warnings');
    if (bfw) bfw.addEventListener('click', function() { urApplyConsoleFilter('warnings'); });
    var ba = document.getElementById('ur-btn-filter-all');
    if (ba) ba.addEventListener('click', function() { urApplyConsoleFilter('all'); });

    var bn = document.getElementById('ur-btn-next-error');
    if (bn) bn.addEventListener('click', function() {
        var nodes = document.querySelectorAll('.ur-cline.err');
        if (!nodes.length) return;
        errIdx = (errIdx + 1) % nodes.length;
        urScrollToLine(parseInt(nodes[errIdx].id.replace('ur-line-',''), 10));
    });

    document.querySelectorAll('[data-ur-filter]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var m = btn.getAttribute('data-ur-filter');
            if (m === 'all') urApplyConsoleFilter('all');
            else if (m === 'errors') urApplyConsoleFilter('errors');
            else if (m === 'warnings') urApplyConsoleFilter('warnings');
            else if (m === 'info') urApplyConsoleFilter('info');
        });
    });

    document.querySelectorAll('a.ur-jump-line').forEach(function(a) {
        a.addEventListener('click', function(ev) {
            ev.preventDefault();
            var line = parseInt(a.getAttribute('data-line'), 10);
            if (line) urScrollToLine(line);
        });
    });
});

// Remove installer handler
var removeBtn = document.getElementById('remove-installer-btn');
if (removeBtn) {
    removeBtn.addEventListener('click', function() {
        if (!confirm('<?= __('admin.confirm_remove_installer') ?>')) return;
        var feedback = document.getElementById('remove-feedback');
        var alertBox = document.getElementById('installer-alert');
        removeBtn.disabled = true;
        removeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width:.8rem;height:.8rem;"></span><?= __('admin.removing') ?>...';
        if (feedback) feedback.textContent = '<?= __('admin.removing_installer_directory') ?>...';

        var licenseKey = '<?= isset($license_key) ? $license_key : "" ?>';
        if (!licenseKey) {
            if (feedback) { feedback.style.color='#f85149'; feedback.textContent = '<?= __('admin.unable_to_remove_installer') ?> - License key not found.'; }
            removeBtn.disabled = false;
            removeBtn.innerHTML = '<i class="bi bi-trash3"></i><?= __('admin.remove_installer_folder_now') ?>';
            return;
        }

        fetch('<?= base_url("installversion/remove_installer_dir") ?>', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8','X-Requested-With':'XMLHttpRequest'},
            body: 'license=' + encodeURIComponent(licenseKey)
        })
        .then(function(r){ return r.json(); })
        .then(function(json) {
            if (json.success) {
                if (alertBox) {
                    alertBox.className = 'ur-security secured';
                    alertBox.innerHTML =
                        '<div class="ur-security-icon"><i class="bi bi-shield-check" style="color:#3fb950;"></i></div>' +
                        '<div><div class="ur-security-title"><?= __('admin.installation_secured') ?></div>' +
                        '<div class="ur-security-sub" style="margin-bottom:0;">' + (json.message || '<?= __('admin.installer_removed_successfully') ?>') + '</div></div>';
                }
            } else {
                removeBtn.disabled = false;
                removeBtn.innerHTML = '<i class="bi bi-trash3"></i><?= __('admin.remove_installer_folder_now') ?>';
                if (feedback) { feedback.style.color='#f85149'; feedback.textContent = json.message || '<?= __('admin.unable_to_remove_installer') ?>'; }
            }
        })
        .catch(function(err) {
            removeBtn.disabled = false;
            removeBtn.innerHTML = '<i class="bi bi-trash3"></i><?= __('admin.remove_installer_folder_now') ?>';
            if (feedback) { feedback.style.color='#f85149'; feedback.textContent = '<?= __('admin.request_failed') ?>: ' + err.message; }
        });
    });
}
</script>
</body>
</html>
