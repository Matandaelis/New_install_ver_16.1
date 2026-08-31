<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('front.site_maintenance') ?></title>
    <?php include(APPPATH.'views/includes/layout.php'); ?>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Floating orbs */
        .mnt-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .18;
            animation: orbFloat 8s ease-in-out infinite;
            pointer-events: none;
        }
        .mnt-orb-1 { width: 400px; height: 400px; background: #6366f1; top: -100px; left: -100px; animation-delay: 0s; }
        .mnt-orb-2 { width: 300px; height: 300px; background: #8b5cf6; bottom: -80px; right: -80px; animation-delay: -3s; }
        .mnt-orb-3 { width: 200px; height: 200px; background: #06b6d4; top: 50%; left: 50%; transform: translate(-50%,-50%); animation-delay: -5s; }
        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }
        .mnt-orb-3 { animation-name: orbFloat3; }
        @keyframes orbFloat3 {
            0%, 100% { transform: translate(-50%,-50%) scale(1); }
            50%       { transform: translate(-50%,-60%) scale(1.1); }
        }

        /* Card */
        .mnt-card {
            position: relative;
            z-index: 10;
            background: rgba(255,255,255,.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 24px;
            padding: 3rem 2.5rem 2.5rem;
            max-width: 520px;
            width: 90vw;
            text-align: center;
            box-shadow: 0 32px 80px rgba(0,0,0,.4), 0 0 0 1px rgba(255,255,255,.06) inset;
            animation: cardIn .6s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(30px) scale(.96); }
            to   { opacity: 1; transform: translateY(0)    scale(1);   }
        }

        /* Icon ring */
        .mnt-icon-ring {
            width: 90px; height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.75rem;
            box-shadow: 0 0 0 12px rgba(99,102,241,.15), 0 0 0 24px rgba(99,102,241,.07);
            animation: iconPulse 3s ease-in-out infinite;
            font-size: 2.4rem; color: #fff;
        }
        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 0 0 12px rgba(99,102,241,.15), 0 0 0 24px rgba(99,102,241,.07); }
            50%       { box-shadow: 0 0 0 16px rgba(99,102,241,.2),  0 0 0 32px rgba(99,102,241,.09); }
        }

        /* Text */
        .mnt-title {
            font-size: 1.9rem; font-weight: 700;
            color: #f1f5f9; letter-spacing: -.4px; margin-bottom: .6rem;
        }
        .mnt-sub {
            color: #94a3b8; font-size: 1rem; line-height: 1.7; margin-bottom: 2rem;
        }

        /* Divider */
        .mnt-divider {
            border: none; border-top: 1px solid rgba(255,255,255,.1); margin: 1.75rem 0;
        }

        /* Dot loader */
        .mnt-dots { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 1.5rem; }
        .mnt-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: #6366f1; animation: dotBounce 1.4s ease-in-out infinite;
        }
        .mnt-dot:nth-child(2) { animation-delay: .2s; background: #8b5cf6; }
        .mnt-dot:nth-child(3) { animation-delay: .4s; background: #06b6d4; }
        @keyframes dotBounce {
            0%, 80%, 100% { transform: scale(.8); opacity: .5; }
            40%            { transform: scale(1.2); opacity: 1; }
        }

        /* Status badge */
        .mnt-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(16,185,129,.15); border: 1px solid rgba(16,185,129,.3);
            color: #34d399; border-radius: 50px; padding: 6px 16px;
            font-size: .8rem; font-weight: 600; letter-spacing: .4px; text-transform: uppercase;
        }
        .mnt-badge-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #34d399; animation: blinkDot 1.5s ease-in-out infinite;
        }
        @keyframes blinkDot { 0%,100%{opacity:1} 50%{opacity:.2} }

        /* Footer */
        .mnt-footer { color: #475569; font-size: .8rem; margin-top: .5rem; }
    </style>
</head>

<body>
    <div class="mnt-orb mnt-orb-1" aria-hidden="true"></div>
    <div class="mnt-orb mnt-orb-2" aria-hidden="true"></div>
    <div class="mnt-orb mnt-orb-3" aria-hidden="true"></div>

    <div class="mnt-card">

        <div class="mnt-icon-ring" aria-hidden="true">
            <i class="fas fa-tools"></i>
        </div>

        <div class="mnt-badge mb-4">
            <span class="mnt-badge-dot"></span>
            <?= __('front.site_maintenance') ?>
        </div>

        <h1 class="mnt-title"><?= __('front.we_will_be_back_soon') ?></h1>
        <p class="mnt-sub"><?= __('front.sorry_for_offline') ?></p>

        <div class="mnt-dots" aria-label="Loading">
            <span class="mnt-dot"></span>
            <span class="mnt-dot"></span>
            <span class="mnt-dot"></span>
        </div>

        <hr class="mnt-divider">

        <p class="mnt-footer">
            <i class="fas fa-headset me-1"></i>
            <?= __('front.support_team') ?>
        </p>

    </div>
</body>
</html>