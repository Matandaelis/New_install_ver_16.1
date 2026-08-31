<?php
$account_cards = array_filter($settings_cards, fn($c) => $c['section'] === 'account');
$vendor_cards  = array_filter($settings_cards, fn($c) => $c['section'] === 'vendor');
?>

<style>
/* ---- Settings Hub ---- */
.sh-hero {
    background: linear-gradient(135deg, #4facfe 0%, #667eea 50%, #764ba2 100%);
    border-radius: 16px;
    padding: 36px 32px;
    color: #fff;
    margin-bottom: 32px;
    position: relative;
    overflow: hidden;
}
.sh-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E") repeat;
}
.sh-hero-content { position: relative; z-index: 1; }
.sh-hero-icon {
    width: 56px; height: 56px;
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.35);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 14px;
}
.sh-hero h2 { font-size: 1.6rem; font-weight: 700; margin-bottom: 6px; }
.sh-hero p  { font-size: 0.9rem; opacity: 0.85; margin: 0; }
.sh-role-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.32);
    border-radius: 50rem; padding: 4px 14px;
    font-size: 0.78rem; font-weight: 600;
    margin-top: 10px;
}
.sh-section-title {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: var(--bs-secondary-color, #9aa0ac);
    margin-bottom: 14px;
    padding-left: 4px;
}
.sh-card {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--bs-body-bg, #fff);
    border: 1px solid var(--bs-border-color, #e9ecef);
    border-radius: 14px;
    padding: 18px 20px;
    text-decoration: none !important;
    color: var(--bs-body-color, #2d3748) !important;
    transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.sh-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--sh-accent, #667eea);
    border-radius: 14px 14px 0 0;
    opacity: 0;
    transition: opacity 0.18s;
}
.sh-card:hover,
.sh-card:focus {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.10);
    border-color: var(--sh-accent, #667eea);
}
.sh-card:hover::before,
.sh-card:focus::before { opacity: 1; }

.sh-card.sh-disabled {
    opacity: 0.55;
    cursor: not-allowed;
    pointer-events: none;
}
.sh-card-icon {
    width: 52px; height: 52px;
    border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.sh-card-body { flex: 1; min-width: 0; }
.sh-card-title {
    font-size: 0.9rem;
    font-weight: 700;
    margin-bottom: 4px;
    line-height: 1.3;
}
.sh-card-desc {
    font-size: 0.77rem;
    color: var(--bs-secondary-color, #9aa0ac);
    line-height: 1.4;
    margin: 0;
}
.sh-card-arrow {
    color: var(--bs-secondary-color, #c0c4cc);
    font-size: 0.85rem;
    flex-shrink: 0;
    transition: color 0.18s, transform 0.18s;
}
.sh-card:hover .sh-card-arrow {
    color: var(--sh-accent, #667eea);
    transform: translateX(3px);
}
.sh-disabled-badge {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 2px 8px;
    border-radius: 50rem;
    background: rgba(234,84,85,0.12);
    color: #ea5455;
    margin-top: 5px;
}
.sh-enabled-dot {
    display: inline-block;
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #28c76f;
    margin-right: 5px;
    vertical-align: middle;
}
</style>

<div class="container-fluid">

    <!-- Hero Banner -->
    <div class="sh-hero">
        <div class="sh-hero-content">
            <div class="sh-hero-icon"><i class="fas fa-sliders-h"></i></div>
            <h2><?= __('user.settings_hub_title') ?></h2>
            <p><?= __('user.settings_hub_desc') ?></p>
            <div class="sh-role-badge">
                <i class="fas fa-crown"></i>
                <?= __('user.vendor') ?>
            </div>
        </div>
    </div>

    <!-- Account Settings Section -->
    <?php if (!empty($account_cards)): ?>
    <div class="sh-section-title"><?= __('user.account_settings') ?></div>
    <div class="row g-3 mb-4">
        <?php foreach ($account_cards as $card): ?>
        <div class="col-12 col-md-6 col-xl-4">
            <a href="<?= $card['url'] ?>"
               class="sh-card<?= !$card['enabled'] ? ' sh-disabled' : '' ?>"
               style="--sh-accent:<?= $card['color'] ?>">
                <div class="sh-card-icon" style="background:<?= $card['bg'] ?>;color:<?= $card['color'] ?>">
                    <i class="<?= $card['icon'] ?>"></i>
                </div>
                <div class="sh-card-body">
                    <div class="sh-card-title">
                        <?php if ($card['enabled']): ?>
                            <span class="sh-enabled-dot"></span>
                        <?php endif; ?>
                        <?= $card['title'] ?>
                    </div>
                    <p class="sh-card-desc"><?= $card['desc'] ?></p>
                    <?php if (!empty($card['badge'])): ?>
                        <span class="sh-disabled-badge"><?= $card['badge'] ?></span>
                    <?php endif; ?>
                </div>
                <i class="fas fa-chevron-right sh-card-arrow"></i>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Vendor Settings Section -->
    <?php if (!empty($vendor_cards)): ?>
    <div class="sh-section-title"><?= __('user.vendor_settings_section') ?></div>
    <div class="row g-3 mb-4">
        <?php foreach ($vendor_cards as $card): ?>
        <div class="col-12 col-md-6 col-xl-4">
            <a href="<?= $card['url'] ?>"
               class="sh-card<?= !$card['enabled'] ? ' sh-disabled' : '' ?>"
               style="--sh-accent:<?= $card['color'] ?>">
                <div class="sh-card-icon" style="background:<?= $card['bg'] ?>;color:<?= $card['color'] ?>">
                    <i class="<?= $card['icon'] ?>"></i>
                </div>
                <div class="sh-card-body">
                    <div class="sh-card-title">
                        <?php if ($card['enabled']): ?>
                            <span class="sh-enabled-dot"></span>
                        <?php endif; ?>
                        <?= $card['title'] ?>
                    </div>
                    <p class="sh-card-desc"><?= $card['desc'] ?></p>
                    <?php if (!empty($card['badge'])): ?>
                        <span class="sh-disabled-badge"><?= $card['badge'] ?></span>
                    <?php endif; ?>
                </div>
                <i class="fas fa-chevron-right sh-card-arrow"></i>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>
