<?php
function getData($key, $default = '') {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

function getError($key, $error) {
    return isset($error[$key]) ? '<div class="invalid-feedback small d-block">'. $error[$key] .'</div>'  : '';
}

function safeGet($func, $default = 'N/A') {
    try {
        if (function_exists($func)) {
            $result = call_user_func($func);
            return ($result !== false && $result !== null && $result !== '') ? $result : $default;
        }
        return $default;
    } catch (Exception $e) {
        return $default;
    }
}

function safeGetValue($value, $default = 'N/A') {
    return ($value !== false && $value !== null && $value !== '') ? $value : $default;
}

$allow_installed = true;
$serverReq = [];
try {
    $serverReq = function_exists('checkReq') ? checkReq() : [];
} catch (Exception $e) {
    $serverReq = [];
}

$maxExecutionTime = safeGet('php_max_execution_time', 'N/A');
$architecture     = safeGetValue(php_uname('m'), 'Unknown');
$serverOS         = safeGet('server_os', 'Unknown');
$serverIP         = safeGet('check_server_ip', 'Unknown');
$memoryLimit      = safeGet('check_limit', 'Unknown');
$maxUploadSize    = safeGet('php_max_upload_size', 'N/A');
$maxPostSize      = safeGet('php_max_post_size', 'N/A');
$maxInputVars     = safeGetValue(ini_get('max_input_vars'), 'N/A');
$isSSL            = safeGet('is_ssl', false);
$phpVersion       = safeGetValue(PHP_VERSION, 'Unknown');

$existingConfig  = getExistingDbConfig();
$existingLicense = getExistingLicenseConfig();

$defaultHostname   = $existingConfig ? $existingConfig['hostname'] : 'localhost';
$defaultPort       = $existingConfig ? $existingConfig['port']     : '3306';
$defaultDbUsername = $existingConfig ? $existingConfig['username']  : '';
$defaultPassword   = $existingConfig ? $existingConfig['password']  : '';
$defaultDatabase   = $existingConfig ? $existingConfig['database']  : '';

$defaultPurchaseCode    = $existingLicense ? $existingLicense['purchase_code'] : '';
$defaultLicenseUsername = $existingLicense ? $existingLicense['username']      : 'admin';

// Compute ring state for system check
$hasFailures = count($serverReq) > 0;
$hasSslWarn  = !$isSSL;
$ringClass   = $hasFailures ? 'has-fail' : ($hasSslWarn ? 'has-warn' : 'all-pass');
$ringIcon    = $hasFailures ? 'bi-x-circle-fill' : ($hasSslWarn ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill');
$issueCount  = count($serverReq);
?>

<!-- ── Progress Bar (full-width strip) ── -->
<div style="background:#161b22;border-bottom:1px solid #21262d;padding:20px 48px;display:flex;align-items:center;justify-content:center;">
    <div class="ins-steps" style="margin-bottom:0;">
        <div class="ins-step-item">
            <div class="ins-step-circle active">1</div>
            <div class="ins-step-label active">Configuration</div>
        </div>
        <div class="ins-step-line active"></div>
        <div class="ins-step-item">
            <div class="ins-step-circle pending">2</div>
            <div class="ins-step-label pending">Complete</div>
        </div>
    </div>
</div>

<!-- ── True Split-Screen ── -->
<div class="ins-split">

    <!-- ══════════════════════════════════════════════
         LEFT PANEL — Installation Form
    ══════════════════════════════════════════════ -->
    <div class="ins-split-left">

        <!-- Page title -->
        <div style="margin-bottom:24px;">
            <h5 style="color:#e6edf3;font-weight:700;margin:0 0 4px;font-size:1.1rem;">
                <i class="bi bi-database-gear" style="color:#388bfd;margin-right:8px;"></i>Installation Configuration
            </h5>
            <p style="color:#8b949e;font-size:.82rem;margin:0;">
                Enter your license and database details to continue.
            </p>
        </div>

        <?php if($checkIsInstall ?? false): ?>
            <div class="alert alert-info mb-3" role="alert">
                <strong><i class="bi bi-arrow-repeat me-1"></i>Re-installation Detected</strong>
                <div class="mt-1" style="font-size:.82rem;">
                    Re-type your CodeCanyon details and database info, then continue.
                    <a class="alert-link" href="https://codecanyon.net/item/affiliate-management-system/25393355" target="_blank">Need a license?</a>
                </div>
            </div>
        <?php elseif($existingConfig || $existingLicense): ?>
            <div class="alert alert-success mb-3" role="alert">
                <strong><i class="bi bi-check-circle me-1"></i>Existing Configuration Detected</strong>
                <div class="mt-1" style="font-size:.82rem;">
                    Form fields have been pre-filled from your existing settings. Review and update as needed.
                </div>
            </div>

            <?php if($existingLicense && isset($existingLicense['license_type'])): ?>
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-shield-check me-2"></i>License Information
                    </h6>
                </div>
                <div class="card-body p-2">
                    <div class="row g-2 text-center">
                        <div class="col-3">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">License Type</small>
                                <small class="fw-bold text-success"><?= htmlspecialchars($existingLicense['license_type']) ?></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Owner</small>
                                <small class="fw-bold text-primary"><?= htmlspecialchars($existingLicense['username']) ?></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Purchased</small>
                                <small class="fw-bold text-info">
                                    <?php if(!empty($existingLicense['purchase_date'])): ?>
                                        <?= date('M Y', strtotime($existingLicense['purchase_date'])) ?>
                                    <?php else: ?>N/A<?php endif; ?>
                                </small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Usage</small>
                                <small class="fw-bold text-warning"><?= $existingLicense['purchase_count'] ?>x</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <form id="register_form">

            <!-- ─ CodeCanyon License section ─ -->
            <div style="margin-bottom:24px;">
                <div class="ins-section-label">
                    <i class="bi bi-key-fill"></i>CodeCanyon License
                </div>
                <div class="row g-2">
                    <div class="col-md-7">
                        <input type="text" name="purchase_code" class="form-control"
                               placeholder="Purchase code (XXXXXXXX-XXXX-...)"
                               value="<?= getData('purchase_code', $defaultPurchaseCode) ?>"
                               maxlength="36"
                               pattern="[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}"
                               autocomplete="off">
                        <div class="form-text">Format: XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX</div>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="username" class="form-control"
                               placeholder="Buyer username (auto-filled)"
                               value="<?= getData('username', $defaultLicenseUsername) ?>"
                               readonly autocomplete="off">
                        <div class="form-text">Auto-filled after license validation</div>
                    </div>
                </div>
            </div>

            <!-- ─ Database section ─ -->
            <div class="ins-db-section" style="margin-bottom:28px;">
                <div class="ins-section-label">
                    <i class="bi bi-database"></i>Database Configuration
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-8">
                        <input type="text" name="db_hostname" class="form-control"
                               placeholder="Hostname (e.g. localhost)"
                               value="<?= getData('db_hostname', $defaultHostname) ?>"
                               autocomplete="off">
                        <?= isset($error) ? getError('db_hostname', $error) : '' ?>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="db_port" class="form-control"
                               placeholder="Port (3306)"
                               value="<?= getData('db_port', $defaultPort) ?>"
                               autocomplete="off">
                        <?= isset($error) ? getError('db_port', $error) : '' ?>
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <input type="text" name="db_username" class="form-control"
                               placeholder="Database username"
                               value="<?= getData('db_username', $defaultDbUsername) ?>"
                               autocomplete="off">
                        <?= isset($error) ? getError('db_username', $error) : '' ?>
                    </div>
                    <div class="col-md-6">
                        <input type="password" name="db_password" class="form-control"
                               placeholder="Database password"
                               value="<?= getData('db_password', $defaultPassword) ?>"
                               autocomplete="new-password">
                    </div>
                </div>
                <input type="text" name="db_database" class="form-control"
                       placeholder="Database name"
                       value="<?= getData('db_database', $defaultDatabase) ?>"
                       autocomplete="off">
                <?= isset($error) ? getError('db_error', $error) : '' ?>
                <?= isset($error) ? getError('db_database', $error) : '' ?>
            </div>

            <button type="submit" class="ins-btn-submit">
                <i class="bi bi-arrow-right-circle-fill"></i>
                Continue Installation
            </button>

        </form>
    </div><!-- /ins-split-left -->

    <!-- ══════════════════════════════════════════════
         RIGHT PANEL — System Check Sidebar
    ══════════════════════════════════════════════ -->
    <div class="ins-split-right">

        <!-- Score ring + header -->
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            <div class="ins-score-ring <?= $ringClass ?>">
                <i class="bi <?= $ringIcon ?>"></i>
            </div>
            <div>
                <div style="font-size:.88rem;font-weight:700;color:#e6edf3;margin-bottom:2px;">System Check</div>
                <?php if($issueCount > 0): ?>
                <div style="font-size:.73rem;color:#8b949e;">
                    <?= $issueCount ?> issue<?= $issueCount !== 1 ? 's' : '' ?> detected
                </div>
                <?php else: ?>
                <div style="font-size:.73rem;color:#3fb950;">All systems ready</div>
                <?php endif; ?>
            </div>
            <?php if($issueCount > 0): ?>
            <div style="margin-left:auto;">
                <span style="background:rgba(227,179,65,.12);color:#e3b341;border:1px solid rgba(227,179,65,.28);border-radius:20px;font-size:.69rem;font-weight:700;padding:3px 9px;">
                    <?= $issueCount ?> issue<?= $issueCount !== 1 ? 's' : '' ?>
                </span>
            </div>
            <?php else: ?>
            <div style="margin-left:auto;">
                <span style="background:rgba(63,185,80,.1);color:#3fb950;border:1px solid rgba(63,185,80,.25);border-radius:20px;font-size:.69rem;font-weight:700;padding:3px 9px;">
                    All clear
                </span>
            </div>
            <?php endif; ?>
        </div>

        <!-- ── Requirements (2-col pill grid) ── -->
        <div class="ins-panel-section">
            <div class="ins-panel-section-title">
                <span>Requirements</span>
                <span style="font-size:.7rem;font-weight:500;">
                    <?php
                    $reqTotal = 7;
                    $displayedReqKeys = ['php','mysqli','curl','ssl','zip','gd','max_input_vars'];
                    $displayedFailures = count(array_intersect_key($serverReq, array_flip($displayedReqKeys)));
                    $reqPass = $reqTotal - $displayedFailures;
                    echo $reqPass . '/' . $reqTotal . ' pass';
                    ?>
                </span>
            </div>
            <div class="ins-req-grid">
                <?php
                $checks = [
                    ['key'=>'php',           'icon'=>'bi-code-slash',    'label'=>'PHP '.$phpVersion],
                    ['key'=>'mysqli',         'icon'=>'bi-database',      'label'=>'MySQLi'],
                    ['key'=>'curl',           'icon'=>'bi-globe',         'label'=>'cURL'],
                    ['key'=>'ssl',            'icon'=>'bi-shield-check',  'label'=>'SSL'],
                    ['key'=>'zip',            'icon'=>'bi-file-zip',      'label'=>'Zip'],
                    ['key'=>'gd',             'icon'=>'bi-image',         'label'=>'GD Lib'],
                    ['key'=>'max_input_vars', 'icon'=>'bi-input-cursor',  'label'=>'Max Vars'],
                ];
                foreach ($checks as $chk):
                    $isSslCheck = ($chk['key'] === 'ssl');
                    $hasProblem = $isSslCheck ? !$isSSL : array_key_exists($chk['key'], $serverReq);
                    $isWarn     = $isSslCheck;
                    $pillClass  = '';
                    if ($hasProblem) $pillClass = $isWarn ? 'warn' : 'fail';
                    $iconClass  = $hasProblem ? ($isWarn ? 'pico-warn bi-exclamation-triangle-fill' : 'pico-fail bi-x-circle-fill') : 'pico-ok bi-check-circle-fill';
                    $warnMsg    = ($hasProblem && !$isSslCheck && isset($serverReq[$chk['key']])) 
                                  ? str_replace('Warning: ', '', $serverReq[$chk['key']]) 
                                  : ($isSslCheck && !$isSSL ? 'HTTPS not detected' : '');
                ?>
                <div class="ins-req-pill <?= $pillClass ?>">
                    <div class="pname">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="bi <?= $chk['icon'] ?>" style="color:#8b949e;font-size:.68rem;width:12px;"></i>
                            <span><?= $chk['label'] ?></span>
                        </div>
                        <?php if($hasProblem && $warnMsg): ?>
                        <div class="pwarn-sub">— <?= htmlspecialchars($warnMsg) ?></div>
                        <?php endif; ?>
                    </div>
                    <i class="bi <?= $iconClass ?>"></i>
                </div>
                <?php endforeach; ?>
            </div>
            <?php
            $displayedKeys = $displayedReqKeys;
            $otherIssues = array_diff_key($serverReq, array_flip($displayedKeys));
            if (!empty($otherIssues)):
                $issueLabels = [
                    'ziparchive' => 'ZipArchive',
                    'ipapi' => 'IP Geolocation API',
                    'gzip' => 'Gzip compression',
                    'allow_url_fopen' => 'allow_url_fopen',
                    'upload_max_filesize' => 'Upload size',
                    'post_max_size' => 'Post size',
                    'writable' => 'Directory permissions',
                ];
            ?>
            <div class="ins-req-grid mt-2" style="margin-top:10px;">
                <?php foreach ($otherIssues as $key => $msg): ?>
                <div class="ins-req-pill fail">
                    <div class="pname">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-exclamation-triangle-fill" style="color:#8b949e;font-size:.68rem;width:12px;"></i>
                            <span><?= htmlspecialchars($issueLabels[$key] ?? $key) ?></span>
                        </div>
                        <div class="pwarn-sub">— <?= htmlspecialchars(str_replace(['Warning: ', 'Notice: '], '', $msg)) ?></div>
                    </div>
                    <i class="bi pico-fail bi-x-circle-fill"></i>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- ── PHP Config tiles ── -->
        <div class="ins-panel-section">
            <div class="ins-panel-section-title">
                <span>PHP Config</span>
            </div>
            <div class="ins-cfg-strip">
                <?php
                $cfgTiles = [
                    ['lbl'=>'Memory',  'val'=>$memoryLimit,           'key'=>null],
                    ['lbl'=>'Exec',    'val'=>$maxExecutionTime.'s',  'key'=>null],
                    ['lbl'=>'Upload',  'val'=>$maxUploadSize,         'key'=>'upload_max_filesize'],
                    ['lbl'=>'Post',    'val'=>$maxPostSize,           'key'=>'post_max_size'],
                ];
                foreach ($cfgTiles as $t):
                    $bad = $t['key'] && array_key_exists($t['key'], $serverReq);
                ?>
                <div class="ins-cfg-tile <?= $bad ? 'bad' : '' ?>">
                    <div class="ctlbl"><?= $t['lbl'] ?></div>
                    <div class="ctval"><?= $t['val'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ── Environment ── -->
        <div class="ins-panel-section" style="margin-bottom:0;">
            <div class="ins-panel-section-title">
                <span>Environment</span>
            </div>
            <div class="ins-env-strip">
                <span class="ins-env-chip"><i class="bi bi-cpu"></i>PHP <?= $phpVersion ?></span>
                <span class="ins-env-chip"><i class="bi bi-display"></i><?= $serverOS ?></span>
                <span class="ins-env-chip"><i class="bi bi-wifi"></i><?= $serverIP ?></span>
            </div>
        </div>

    </div><!-- /ins-split-right -->

</div><!-- /ins-split -->

<script type="text/javascript">
    // Form submission
    $("#register_form").submit(function(){
        $this = $(this);
        
        $this.find(".is-invalid").removeClass("is-invalid");
        $this.find(".invalid-feedback").remove();
        $this.find(".alert").remove();
        
        $.ajax({
            url:'proccess.php',
            type:'POST',
            dataType:'json',
            data:$this.serialize()+'&page=step2',
            beforeSend:function(){
                $this.find("button[type=submit]")
                    .prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...');
            },
            complete:function(){
                $this.find("button[type=submit]")
                    .prop('disabled', false)
                    .html('<i class="bi bi-arrow-right-circle-fill me-2"></i>Continue Installation');
            },
            success:function(json){
                if(json && json['html']){
                    $("#main").html(json['html']);
                    
                    setTimeout(function() {
                        const adminBtn = document.querySelector('.btn-primary');
                        if (adminBtn && !document.activeElement.matches('button, a, input')) {
                            adminBtn.focus();
                            adminBtn.style.boxShadow = '0 0 0 3px rgba(56,139,253,.25)';
                            setTimeout(function() { adminBtn.style.boxShadow = ''; }, 3000);
                        }
                        
                        const removeBtn = document.querySelector('.remove-installer-btn');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                
                                const feedback = document.querySelector('.remove-installer-feedback');
                                if (!removeBtn.dataset.license) {
                                    if (feedback) {
                                        feedback.classList.add('text-danger', 'fw-semibold');
                                        feedback.textContent = 'License key missing. Please remove the folder manually.';
                                    }
                                    removeBtn.disabled = false;
                                    return;
                                }
                                
                                removeBtn.disabled = true;
                                removeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Removing...';
                                
                                if (feedback) {
                                    feedback.classList.remove('text-success', 'text-danger');
                                    feedback.textContent = 'Removing installer directory...';
                                }

                                fetch(removeBtn.dataset.endpoint, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: 'license=' + encodeURIComponent(removeBtn.dataset.license)
                                })
                                .then(response => response.text())
                                .then(text => {
                                    let json;
                                    try { json = JSON.parse(text); } catch (e) {
                                        if (feedback) {
                                            feedback.classList.add('text-danger', 'fw-semibold');
                                            feedback.textContent = 'Server error: ' + text.substring(0, 100);
                                        }
                                        removeBtn.disabled = false;
                                        removeBtn.innerHTML = '<i class="bi bi-trash me-2"></i>Remove Installer Folder Now';
                                        return;
                                    }
                                    
                                    if (json.success) {
                                        const alertBox = removeBtn.closest('.ins-security-box, .alert');
                                        if (alertBox) {
                                            alertBox.className = 'ins-security-box secured';
                                            alertBox.innerHTML =
                                                '<div style="display:flex;align-items:center;gap:12px;">' +
                                                '<i class="bi bi-shield-check-fill" style="font-size:1.6rem;color:#3fb950;flex-shrink:0;"></i>' +
                                                '<div><div class="ins-security-title">Installation Secured</div>' +
                                                '<p class="ins-security-sub mb-0">' + (json.message || 'Installer directory removed successfully.') + '</p>' +
                                                '</div></div>';
                                        }
                                    } else {
                                        removeBtn.disabled = false;
                                        removeBtn.innerHTML = '<i class="bi bi-trash me-2"></i>Remove Installer Folder Now';
                                        if (feedback) {
                                            feedback.classList.add('text-danger', 'fw-semibold');
                                            feedback.textContent = json.message || 'Unable to remove installer directory.';
                                        }
                                    }
                                })
                                .catch(err => {
                                    removeBtn.disabled = false;
                                    removeBtn.innerHTML = '<i class="bi bi-trash me-2"></i>Remove Installer Folder Now';
                                    if (feedback) {
                                        feedback.classList.add('text-danger', 'fw-semibold');
                                        feedback.textContent = 'Request failed: ' + err.message;
                                    }
                                });
                            });
                        }
                    }, 100);
                }

                if(json && json['errors']){
                    $.each(json['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele.length){
                            $ele.addClass("is-invalid");
                            $ele.after('<div class="invalid-feedback small">'+ j +'</div>');
                        }
                    });
                    
                    if(Object.keys(json['errors']).length > 0) {
                        $this.prepend('<div class="alert alert-danger alert-dismissible fade show py-2" role="alert">' +
                            '<i class="bi bi-exclamation-triangle-fill me-2"></i>' +
                            'Please fix the errors below and try again.' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                    }
                }
            },
            error: function(xhr, status, error) {
                $this.prepend('<div class="alert alert-danger alert-dismissible fade show py-2" role="alert">' +
                    '<i class="bi bi-exclamation-triangle-fill me-2"></i>' +
                    'An error occurred while processing your request. Please try again.' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>');
            }
        });

        return false;
    });
    
    // Purchase code validation
    $('[name="purchase_code"]').on('input blur change', function(){
        $this = $(this);
        $form = $("#register_form");
        
        var purchaseCode = $this.val().trim();
        var isValidFormat = /^[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}$/i.test(purchaseCode);
        
        $this.removeClass('is-valid is-invalid');
        $this.siblings('.form-text').removeClass('text-success text-danger').addClass('text-muted');
        
        if (purchaseCode.length === 0) {
            $this.siblings('.form-text').text('Format: XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX').removeClass('text-success text-danger').addClass('text-muted');
            return;
        }
        
        if (purchaseCode.length !== 36) {
            $this.addClass('is-invalid');
            $this.siblings('.form-text').text('Purchase code must be exactly 36 characters').removeClass('text-muted text-success').addClass('text-danger');
            return;
        }
        
        if (!isValidFormat) {
            $this.addClass('is-invalid');
            $this.siblings('.form-text').text('Invalid format. Use: XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX').removeClass('text-muted text-success').addClass('text-danger');
            return;
        }
        
        if (isValidFormat && purchaseCode.length === 36) {
            $this.addClass('is-valid');
            $this.siblings('.form-text').text('Validating purchase code...').removeClass('text-muted text-danger').addClass('text-primary');
            
            $form.find(".is-invalid").not($this).removeClass("is-invalid");
            $form.find(".invalid-feedback").remove();
            $form.find(".alert").remove();
            
            $.ajax({
                url:'codecanyon.php',
                type:'POST',
                dataType:'json',
                data:{ code: purchaseCode },
                success:function(json){
                    if(json && json['errors']){
                        $('[name="username"]').val('');
                        $this.removeClass('is-valid').addClass('is-invalid');
                        $this.siblings('.form-text').text('Invalid purchase code').removeClass('text-primary text-success').addClass('text-danger');
                        
                        $.each(json['errors'], function(i,j){
                            $ele = $form.find('[name="'+ i +'"]');
                            if($ele.length && $ele[0] !== $this[0]){
                                $ele.addClass("is-invalid");
                                $ele.after('<div class="invalid-feedback small">'+ j +'</div>');
                            }
                        });
                    } else {
                        if(json && json.response && json.response.buyer){
                            $('[name="username"]').val(json.response.buyer);
                            $this.removeClass('is-invalid').addClass('is-valid');
                            $this.siblings('.form-text').text('Purchase code validated successfully!').removeClass('text-primary text-danger').addClass('text-success');
                            
                            var licenseInfo = json.response;
                            var licenseCard = '<div class="card border-success mb-3" id="license-info-card">' +
                                '<div class="card-header bg-success text-white py-2">' +
                                    '<h6 class="card-title mb-0"><i class="bi bi-shield-check me-2"></i>License Information</h6>' +
                                '</div>' +
                                '<div class="card-body p-2">' +
                                    '<div class="row g-2 text-center">' +
                                        '<div class="col-3"><div class="border rounded p-2">' +
                                            '<small class="text-muted d-block">License Type</small>' +
                                            '<small class="fw-bold text-success">' + (licenseInfo.license || 'Regular License') + '</small>' +
                                        '</div></div>' +
                                        '<div class="col-3"><div class="border rounded p-2">' +
                                            '<small class="text-muted d-block">Owner</small>' +
                                            '<small class="fw-bold text-primary">' + licenseInfo.buyer + '</small>' +
                                        '</div></div>' +
                                        '<div class="col-3"><div class="border rounded p-2">' +
                                            '<small class="text-muted d-block">Purchased</small>' +
                                            '<small class="fw-bold text-info">' + (licenseInfo.sold_at ? new Date(licenseInfo.sold_at).toLocaleDateString('en-US', {month: 'short', year: 'numeric'}) : 'N/A') + '</small>' +
                                        '</div></div>' +
                                        '<div class="col-3"><div class="border rounded p-2">' +
                                            '<small class="text-muted d-block">Usage</small>' +
                                            '<small class="fw-bold text-warning">' + (licenseInfo.purchase_count || 0) + 'x</small>' +
                                        '</div></div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                            
                            $('#license-info-card').remove();
                            $('.alert-success').after(licenseCard);
                            
                            <?php if($existingConfig): ?>
                            $('[name="db_hostname"]').val('<?= addslashes($existingConfig['hostname']) ?>');
                            $('[name="db_port"]').val('<?= addslashes($existingConfig['port']) ?>');
                            $('[name="db_username"]').val('<?= addslashes($existingConfig['username']) ?>');
                            $('[name="db_password"]').val('<?= addslashes($existingConfig['password']) ?>');
                            $('[name="db_database"]').val('<?= addslashes($existingConfig['database']) ?>');
                            $('.ins-db-section').find('.text-success').remove();
                            $('.ins-db-section').find('.ins-section-label').after(
                                '<div class="mb-2 text-success" style="font-size:.78rem;">' +
                                '<i class="bi bi-check-circle-fill me-1"></i>Database details auto-filled from existing configuration' +
                                '</div>'
                            );
                            <?php endif; ?>
                        }
                    }
                },
                error: function() {
                    $this.siblings('.form-text').text('Unable to validate purchase code at this time').removeClass('text-primary text-success').addClass('text-warning');
                }
            });
        }
    });
</script>
