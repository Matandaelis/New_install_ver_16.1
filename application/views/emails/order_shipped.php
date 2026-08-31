<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;padding:0;background:#f4f4f7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <div style="max-width:600px;margin:0 auto;padding:20px;">
        <div style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            <div style="background:linear-gradient(135deg,#198754,#20c997);padding:30px;text-align:center;">
                <h1 style="color:#fff;margin:0;font-size:24px;">📦 Your Order Has Shipped!</h1>
            </div>
            <div style="padding:30px;">
                <p style="font-size:16px;color:#333;">Hi <?= htmlspecialchars($customer_name ?? 'there') ?>,</p>
                <p style="font-size:14px;color:#666;line-height:1.6;">Great news! Your order <strong>#<?= $order_id ?? '' ?></strong> has been shipped.</p>
                
                <?php if(!empty($tracking_number)): ?>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:20px;margin:20px 0;text-align:center;">
                    <p style="font-size:12px;color:#666;margin:0 0 5px;">Tracking Number</p>
                    <p style="font-size:20px;font-weight:bold;color:#166534;margin:0;letter-spacing:1px;"><?= htmlspecialchars($tracking_number) ?></p>
                    <?php if(!empty($carrier)): ?>
                    <p style="font-size:13px;color:#666;margin:8px 0 0;">Carrier: <?= htmlspecialchars($carrier) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div style="text-align:center;margin:25px 0;">
                    <a href="<?= $order_url ?? '#' ?>" style="display:inline-block;background:#198754;color:#fff;text-decoration:none;padding:12px 30px;border-radius:50px;font-weight:bold;font-size:14px;">
                        View Order Details
                    </a>
                </div>
            </div>
            <div style="background:#f8f9fa;padding:20px;text-align:center;border-top:1px solid #e9ecef;">
                <p style="font-size:12px;color:#999;margin:0;">&copy; <?= date('Y') ?> <?= $store_name ?? '' ?>. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
