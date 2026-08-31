<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;padding:0;background:#f4f4f7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <div style="max-width:600px;margin:0 auto;padding:20px;">
        <div style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            <!-- Header -->
            <div style="background:linear-gradient(135deg,#0d6efd,#6610f2);padding:30px;text-align:center;">
                <h1 style="color:#fff;margin:0;font-size:24px;">🛒 You Left Items Behind!</h1>
            </div>
            <!-- Body -->
            <div style="padding:30px;">
                <p style="font-size:16px;color:#333;margin-bottom:20px;">Hi <?= htmlspecialchars($customer_name ?? 'there') ?>,</p>
                <p style="font-size:14px;color:#666;line-height:1.6;">You have items waiting in your cart. Don't let them get away — complete your purchase today!</p>
                
                <?php if(!empty($cart_items)): ?>
                <div style="background:#f8f9fa;border-radius:8px;padding:15px;margin:20px 0;">
                    <h3 style="font-size:14px;color:#333;margin:0 0 10px;">Your Cart Items:</h3>
                    <?php foreach($cart_items as $item): ?>
                    <div style="display:flex;align-items:center;padding:8px 0;border-bottom:1px solid #e9ecef;">
                        <span style="font-size:14px;color:#333;"><?= htmlspecialchars($item['name'] ?? 'Product') ?></span>
                        <span style="margin-left:auto;font-weight:bold;color:#0d6efd;"> x<?= $item['qty'] ?? 1 ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div style="text-align:center;margin:30px 0;">
                    <a href="<?= $recovery_url ?? '#' ?>" style="display:inline-block;background:#0d6efd;color:#fff;text-decoration:none;padding:14px 40px;border-radius:50px;font-weight:bold;font-size:16px;">
                        Complete My Purchase →
                    </a>
                </div>

                <p style="font-size:12px;color:#999;text-align:center;">This link will expire in 7 days. If you didn't add these items, please ignore this email.</p>
            </div>
            <!-- Footer -->
            <div style="background:#f8f9fa;padding:20px;text-align:center;border-top:1px solid #e9ecef;">
                <p style="font-size:12px;color:#999;margin:0;">&copy; <?= date('Y') ?> <?= $store_name ?? '' ?>. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
