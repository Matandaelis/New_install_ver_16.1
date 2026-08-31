<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            padding: 20px;
        }
        .success-card {
            background: white;
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 550px;
            width: 100%;
            animation: slideUp 0.6s ease-out;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .success-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: scaleIn 0.5s ease-out 0.2s both;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .checkmark {
            font-size: 45px;
            color: white;
            font-weight: bold;
        }
        .checkmark::before {
            content: '✓';
        }
        h1 {
            color: #1f2937;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .message {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .transaction-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }
        .transaction-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .transaction-label::before {
            content: '🧾 ';
            margin-right: 5px;
        }
        .transaction-id {
            color: #374151;
            font-size: 14px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            background: white;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .info-text {
            color: #9ca3af;
            font-size: 14px;
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .info-text::before {
            content: '✉️ ';
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">
            <span class="checkmark"></span>
        </div>
        
        <h1>Payment Successful!</h1>
        
        <p class="message"><?= $message ?></p>
        
        <?php if (!empty($session_id)): ?>
        <div class="transaction-box">
            <div class="transaction-label">
                Transaction ID
            </div>
            <div class="transaction-id"><?= htmlspecialchars($session_id) ?></div>
        </div>
        <?php endif; ?>
        
        <div class="info-text">
            <span>You will receive a confirmation email shortly</span>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <p style="color: #6b7280; font-size: 15px; margin-bottom: 8px; font-weight: 500;">✓ Transaction Complete</p>
            <p style="color: #9ca3af; font-size: 13px; margin: 0;">You can now close this tab/window</p>
        </div>
    </div>
</body>
</html>
