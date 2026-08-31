<?php
    $base_url    = str_replace('/install', '', base_path());
    $license_key = isset($license) ? $license : '';
?>

<!-- ── Progress Bar (full-width strip, both done) ── -->
<div style="background:#161b22;border-bottom:1px solid #21262d;padding:20px 48px;display:flex;align-items:center;justify-content:center;">
    <div class="ins-steps" style="margin-bottom:0;">
        <div class="ins-step-item">
            <div class="ins-step-circle done"><i class="bi bi-check-lg" style="font-size:.8rem;"></i></div>
            <div class="ins-step-label done">Configuration</div>
        </div>
        <div class="ins-step-line done"></div>
        <div class="ins-step-item">
            <div class="ins-step-circle done"><i class="bi bi-check-lg" style="font-size:.8rem;"></i></div>
            <div class="ins-step-label done">Complete</div>
        </div>
    </div>
</div>

<!-- ── Full-width Success Hero ── -->
<div class="ins-success-hero">
    <div class="ins-success-hero-icon">
        <i class="bi bi-check-lg"></i>
    </div>
    <div class="ins-success-hero-text">
        <h2>Your System is Ready!</h2>
        <p>AffiliatePro has been installed and configured on your server.</p>
        <div class="ins-success-hero-badges">
            <span class="ins-hero-badge green"><i class="bi bi-check-circle-fill"></i>Installation Complete</span>
            <span class="ins-hero-badge blue"><i class="bi bi-database-check"></i>Database Configured</span>
            <span class="ins-hero-badge green"><i class="bi bi-shield-check-fill"></i>License Activated</span>
        </div>
    </div>
</div>

<!-- ── 3-Column Action Grid ── -->
<div class="ins-action-grid">

    <!-- Col 1: Security Notice -->
    <div class="ins-action-col">
        <div class="ins-action-col-title">
            <i class="bi bi-shield-lock-fill" style="color:#e3b341;"></i>Security
        </div>

        <!-- NOTE: .alert class is REQUIRED for JS removeBtn.closest() selector -->
        <div class="ins-security-box alert alert-warning" id="installer-security-alert">
            <div style="display:flex;align-items:flex-start;gap:12px;">
                <i class="bi bi-exclamation-triangle-fill" style="color:#e3b341;font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div class="ins-security-title">Remove Installer</div>
                    <p class="ins-security-sub">
                        The <code style="background:#21262d;color:#e3b341;padding:1px 5px;border-radius:4px;font-size:.76rem;">/install</code> folder must be deleted to prevent unauthorized access or re-installation.
                    </p>
                    <button type="button"
                            class="ins-btn ins-btn-amber remove-installer-btn"
                            style="font-size:.82rem;padding:8px 16px;"
                            data-license="<?= htmlspecialchars($license_key, ENT_QUOTES, 'UTF-8') ?>"
                            data-endpoint="<?= rtrim($base_url, '/') ?>/index.php/installversion/remove_installer_dir">
                        <i class="bi bi-trash3"></i>Remove Now
                    </button>
                    <small class="remove-installer-feedback d-block mt-2" style="color:#8b949e;font-size:.74rem;"></small>
                </div>
            </div>
        </div>

        <!-- What was installed -->
        <div style="margin-top:16px;">
            <div style="font-size:.72rem;font-weight:700;color:#484860;text-transform:uppercase;letter-spacing:.07em;margin-bottom:10px;">
                Installed Modules
            </div>
            <?php
            $modules = [
                ['icon'=>'bi-people-fill',      'color'=>'#388bfd', 'label'=>'Affiliate Engine'],
                ['icon'=>'bi-bag-fill',          'color'=>'#3fb950', 'label'=>'E-Commerce Store'],
                ['icon'=>'bi-shop',              'color'=>'#39d0d8', 'label'=>'Multi-vendor Marketplace'],
                ['icon'=>'bi-credit-card-fill',  'color'=>'#bc8cff', 'label'=>'Payment Gateways'],
                ['icon'=>'bi-shield-fill-check', 'color'=>'#e3b341', 'label'=>'Fraud Protection Engine'],
            ];
            foreach ($modules as $m):
            ?>
            <div style="display:flex;align-items:center;gap:10px;padding:5px 0;border-bottom:1px solid #21262d;">
                <i class="bi <?= $m['icon'] ?>" style="color:<?= $m['color'] ?>;font-size:.8rem;width:14px;flex-shrink:0;"></i>
                <span style="font-size:.78rem;color:#c9d1d9;"><?= $m['label'] ?></span>
                <i class="bi bi-check-circle-fill ms-auto" style="color:#3fb950;font-size:.7rem;"></i>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Col 2: Primary Action Buttons (CTA) -->
    <div class="ins-action-col" style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">

        <!-- Logo / wordmark -->
        <div style="margin-bottom:24px;">
            <?php if (file_exists('../install/assets/aff_pro_logo.png')): ?>
                <img src="../install/assets/aff_pro_logo.png" alt="AffiliatePro" style="max-height:44px;">
            <?php else: ?>
                <div style="background:linear-gradient(135deg,#388bfd,#2a72e0);color:#fff;border-radius:10px;padding:8px 22px;font-weight:700;font-size:1rem;letter-spacing:.04em;display:inline-block;">
                    AFFILIATEPRO
                </div>
            <?php endif; ?>
        </div>

        <p style="color:#8b949e;font-size:.85rem;margin-bottom:28px;line-height:1.5;max-width:240px;">
            Log in to your admin panel to complete configuration and start building your affiliate network.
        </p>

        <div style="width:100%;max-width:260px;display:flex;flex-direction:column;gap:10px;">
            <a href="<?= $base_url . '/admin' ?>" class="ins-btn ins-btn-success btn-primary"
               style="justify-content:center;font-size:.95rem;padding:14px 24px;border-radius:12px;box-shadow:0 6px 24px rgba(63,185,80,.25);">
                <i class="bi bi-shield-lock-fill"></i>Access Admin Dashboard
            </a>
            <a href="<?= $base_url ?>" class="ins-btn ins-btn-outline btn-outline-secondary"
               style="justify-content:center;">
                <i class="bi bi-house-door-fill"></i>Visit Homepage
            </a>
        </div>

        <!-- Support links -->
        <div style="margin-top:24px;display:flex;gap:16px;font-size:.75rem;">
            <a href="https://affiliatepro.org/customer-portal/" target="_blank"
               style="color:#8b949e;text-decoration:none;display:flex;align-items:center;gap:4px;">
                <i class="bi bi-headset" style="font-size:.8rem;"></i>Support
            </a>
            <a href="https://affiliatepro.org/logs/" target="_blank"
               style="color:#8b949e;text-decoration:none;display:flex;align-items:center;gap:4px;">
                <i class="bi bi-journal-text" style="font-size:.8rem;"></i>Changelog
            </a>
        </div>
    </div>

    <!-- Col 3: Next Steps -->
    <div class="ins-action-col">
        <div class="ins-action-col-title">
            <i class="bi bi-lightbulb-fill" style="color:#388bfd;"></i>Next Steps
        </div>

        <div class="ins-next-step">
            <div class="ins-next-icon" style="background:rgba(56,139,253,.12);color:#388bfd;">
                <i class="bi bi-gear-fill"></i>
            </div>
            <div>
                <p class="ins-next-title">Configure Settings</p>
                <p class="ins-next-sub">Set up your system name, email, currency, and general preferences.</p>
            </div>
        </div>

        <div class="ins-next-step">
            <div class="ins-next-icon" style="background:rgba(63,185,80,.12);color:#3fb950;">
                <i class="bi bi-plus-circle-fill"></i>
            </div>
            <div>
                <p class="ins-next-title">Create Affiliate Programs</p>
                <p class="ins-next-sub">Define commission structures, tracking links, and campaigns.</p>
            </div>
        </div>

        <div class="ins-next-step">
            <div class="ins-next-icon" style="background:rgba(57,208,216,.12);color:#39d0d8;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <p class="ins-next-title">Invite Affiliates</p>
                <p class="ins-next-sub">Share the registration link and grow your partner network.</p>
            </div>
        </div>

        <div class="ins-next-step">
            <div class="ins-next-icon" style="background:rgba(188,140,255,.12);color:#bc8cff;">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
                <p class="ins-next-title">Monitor Performance</p>
                <p class="ins-next-sub">Track clicks, conversions, revenue, and fraud in real-time.</p>
            </div>
        </div>

        <div class="ins-next-step" style="margin-bottom:0;">
            <div class="ins-next-icon" style="background:rgba(248,81,73,.12);color:#f85149;">
                <i class="bi bi-shield-fill-check"></i>
            </div>
            <div>
                <p class="ins-next-title">Review Fraud Settings</p>
                <p class="ins-next-sub">Configure the AI Fraud Scoring Engine thresholds and alerts.</p>
            </div>
        </div>
    </div>

</div><!-- /ins-action-grid -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const adminBtn = document.querySelector('a.ins-btn-success');
        if (adminBtn) {
            adminBtn.style.boxShadow = '0 0 0 4px rgba(63,185,80,.2), 0 6px 24px rgba(63,185,80,.25)';
            setTimeout(function() {
                adminBtn.style.boxShadow = '0 6px 24px rgba(63,185,80,.25)';
            }, 3000);
        }
    }, 200);

    const removeBtn = document.querySelector('.remove-installer-btn');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            const feedback = document.querySelector('.remove-installer-feedback');
            if (!removeBtn.dataset.license) {
                if (feedback) feedback.textContent = 'License key missing. Please remove the folder manually.';
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
            .then(response => response.json())
            .then(json => {
                if (json.success) {
                    const alertBox = removeBtn.closest('#installer-security-alert');
                    if (alertBox) {
                        alertBox.className = 'ins-security-box secured';
                        alertBox.innerHTML =
                            '<div style="display:flex;align-items:center;gap:14px;">' +
                            '<i class="bi bi-shield-check-fill" style="font-size:2rem;color:#3fb950;flex-shrink:0;"></i>' +
                            '<div>' +
                            '<div class="ins-security-title">Installation Secured</div>' +
                            '<p class="ins-security-sub mb-0">' + (json.message || 'Installer directory removed. Your installation is now secure.') + '</p>' +
                            '</div></div>';
                    }
                } else {
                    removeBtn.disabled = false;
                    removeBtn.innerHTML = '<i class="bi bi-trash3 me-1"></i>Remove Now';
                    if (feedback) {
                        feedback.classList.add('text-danger');
                        feedback.textContent = json.message || 'Unable to remove installer directory.';
                    }
                }
            })
            .catch(() => {
                removeBtn.disabled = false;
                removeBtn.innerHTML = '<i class="bi bi-trash3 me-1"></i>Remove Now';
                if (feedback) {
                    feedback.classList.add('text-danger');
                    feedback.textContent = 'Request failed. Please remove manually.';
                }
            });
        });
    }
});
</script>
