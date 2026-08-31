<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
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
        .error-card {
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
        .error-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: scaleIn 0.5s ease-out 0.2s both;
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
            position: relative;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .error-symbol {
            font-size: 45px;
            color: white;
            font-weight: bold;
        }
        .error-symbol::before {
            content: '⚠';
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
        .info-box {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
        }
        .info-box h6 {
            color: #92400e;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .info-box h6::before {
            content: 'ℹ️ ';
            margin-right: 8px;
        }
        .info-box p {
            color: #78350f;
            font-size: 13px;
            margin: 0;
            line-height: 1.5;
        }
        .contact-text {
            color: #9ca3af;
            font-size: 14px;
            margin-top: 25px;
        }
        .contact-text::before {
            content: '🎧 ';
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <span class="error-symbol"></span>
        </div>
        
        <h1><?= htmlspecialchars($title) ?></h1>
        
        <p class="message"><?= htmlspecialchars($message) ?></p>
        
        <div class="info-box">
            <h6>What happened?</h6>
            <p>The payment system is currently unavailable or not properly configured. This is usually a temporary issue that the administrator can resolve.</p>
        </div>
        
        <div class="contact-text">
            <span>Please contact the site administrator for assistance</span>
        </div>
    </div>
</body>
</html>
