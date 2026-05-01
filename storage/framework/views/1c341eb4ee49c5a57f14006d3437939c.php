<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password — KAS Delivery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
            background-color: #F1F5F9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 520px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #FF6B2C 0%, #FF8B00 100%);
            padding: 32px 24px;
            text-align: center;
            color: white;
        }
        .logo {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            font-family: 'Space Grotesk', monospace;
            margin-bottom: 8px;
        }
        .logo span {
            font-weight: 400;
            opacity: 0.9;
        }
        .tagline {
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 16px;
            color: #1E293B;
            margin-bottom: 16px;
        }
        .message {
            font-size: 15px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .button-container {
            text-align: center;
            margin: 28px 0;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #FF6B2C 0%, #FF8B00 100%);
            color: white !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 6px 14px rgba(255, 107, 44, 0.35);
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px -8px rgba(255, 107, 44, 0.5);
        }
        .warning {
            background: #FEF3C7;
            border: 1px solid #FDE68A;
            border-radius: 12px;
            padding: 16px;
            margin: 24px 0;
        }
        .warning-title {
            font-size: 14px;
            font-weight: 700;
            color: #92400E;
            margin-bottom: 6px;
        }
        .warning-text {
            font-size: 13px;
            color: #92400E;
        }
        .footer {
            text-align: center;
            padding: 20px 28px;
            background: #F8FAFC;
            border-top: 1px solid #E2E8F0;
        }
        .footer-text {
            font-size: 13px;
            color: #94A3B8;
        }
        .footer-link {
            color: #FF6B2C;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">KAS<span>Delivery</span></div>
            <div class="tagline">Fast & Secure Food Delivery</div>
        </div>
        
        <div class="email-body">
            <p class="greeting">Hi <?php echo e($name); ?>,</p>
            
            <p class="message">
                We received a request to reset your password for your KAS Delivery account. 
                Click the button below to create a new password:
            </p>
            
            <div class="button-container">
                <a href="<?php echo e($resetUrl); ?>" class="reset-button">Reset Password</a>
            </div>
            
            <p class="message">
                If the button doesn't work, you can copy and paste this link into your browser:
            </p>
            <p class="message" style="word-break: break-all; font-size: 13px;">
                <?php echo e($resetUrl); ?>

            </p>
            
            <div class="warning">
                <div class="warning-title">⚠️ Important:</div>
                <div class="warning-text">
                    This reset link will expire in 30 minutes for security reasons. 
                    If you didn't request a password reset, please ignore this email or contact support if you have concerns.
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p class="footer-text">
                © <?php echo e(date('Y')); ?> KAS Delivery. All rights reserved.<br>
                Need help? Contact us at <a href="mailto:support@kasdelivery.com" class="footer-link">support@kasdelivery.com</a>
            </p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/emails/password-reset.blade.php ENDPATH**/ ?>