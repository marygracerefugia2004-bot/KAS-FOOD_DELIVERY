<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification — KAS Delivery</title>
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
            font-size: 24px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 12px;
        }
        .message {
            color: #334155;
            line-height: 1.5;
            margin-bottom: 28px;
            font-size: 16px;
        }
        .otp-box {
            background: #F8FAFE;
            border: 2px dashed #FF8B00;
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            color: #FF6B2C;
            margin-bottom: 12px;
        }
        .otp-code {
            font-family: 'Courier New', 'Space Grotesk', monospace;
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #0F172A;
            background: #FFFFFF;
            padding: 12px 16px;
            border-radius: 16px;
            display: inline-block;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            border: 1px solid #E2E8F0;
        }
        .expiry {
            background-color: #FFF7ED;
            border-left: 4px solid #FF6B2C;
            padding: 14px 18px;
            border-radius: 12px;
            margin: 24px 0;
            font-size: 14px;
            color: #9B3412;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer {
            background-color: #F8FAFC;
            padding: 20px 28px;
            text-align: center;
            font-size: 12px;
            color: #64748B;
            border-top: 1px solid #E2E8F0;
        }
        .footer a {
            color: #FF6B2C;
            text-decoration: none;
        }
        @media (max-width: 550px) {
            .email-container {
                border-radius: 0;
                margin: 0 12px;
            }
            .otp-code {
                font-size: 36px;
                letter-spacing: 4px;
            }
            .email-body {
                padding: 24px 20px;
            }
        }
    </style>
</head>
<body style="margin:0;padding:24px 12px;background-color:#F1F5F9;">
    <div class="email-container">
        <div class="email-header">
            <div class="logo">KAS <span>Delivery</span></div>
            <div class="tagline">Fast · Reliable · Smart Logistics</div>
        </div>

        <div class="email-body">
            <div class="greeting">Hello <?php echo e($name); ?>! 👋</div>
            <div class="message">
                Thanks for joining <strong>KAS Delivery</strong>. To complete your registration, please use the following One-Time Password (OTP). This helps us keep your account secure.
            </div>

            <div class="otp-box">
                <div class="otp-label">🔐 Your verification code</div>
                <div class="otp-code"><?php echo e($otp); ?></div>
            <div style="margin-top: 16px; font-size: 13px; color: #475569;">
                <strong>Important:</strong> This OTP is valid for 10 minutes only. Do not refresh the page or close this window.
            </div>
                <div style="margin-top: 16px; font-size: 13px; color: #475569;">Enter this code on the verification screen</div>
            </div>

            <div class="expiry">
                <span style="font-size: 22px;">⏰</span>
                <span>This code expires in <strong>10 minutes</strong>. For security, do not share this OTP with anyone.</span>
            </div>

            <!-- Simple instruction instead of a button (no $email variable needed) -->
            <div style="text-align: center; margin: 20px 0;">
                <p style="color: #475569;">Enter the 6‑digit OTP on the verification page.</p>
            </div>

            <div style="margin-top: 28px; font-size: 13px; color: #475569; text-align: center;">
                If you didn't request this, you can safely ignore this email. Your account will not be activated until you verify.
            </div>
        </div>

        <div class="footer">
            <p>KAS Delivery — Your trusted delivery partner</p>
            <p style="margin-top: 6px;">
                <a href="#">Help Center</a> &nbsp;|&nbsp;
                <a href="#">Privacy Policy</a>
            </p>
            <p style="margin-top: 12px;">&copy; <?php echo e(date('Y')); ?> KAS Delivery. All rights reserved.</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\emails\otp.blade.php ENDPATH**/ ?>