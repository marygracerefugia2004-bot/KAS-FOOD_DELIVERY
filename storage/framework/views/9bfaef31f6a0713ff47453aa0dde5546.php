<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>KAS Delivery — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --orange: #FF6B2C;
            --orange2: #FF8B00;
            --bg: #080D1A;
            --text: #fff;
            --muted: #94A3B8;
            --surface: #0F172A;
            --border: #1E293B;
            --card-bg: rgba(15, 23, 42, 0.85);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
        }

        .bg-pattern {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: radial-gradient(circle at 20% 40%, #0f172a, #030712);
        }

        .bg-pattern::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -10%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255, 107, 44, 0.2) 0%, transparent 70%);
            filter: blur(70px);
            z-index: -1;
        }

        .bg-pattern::after {
            content: '';
            position: absolute;
            bottom: -10%;
            right: -5%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(255, 139, 0, 0.15) 0%, transparent 70%);
            filter: blur(80px);
            z-index: -1;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(30, 41, 59, 0.6);
            border-radius: 32px;
            padding: 2.2rem 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-icon {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--orange), var(--orange2));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin-bottom: 1.2rem;
            box-shadow: 0 10px 20px -5px rgba(255, 107, 44, 0.3);
        }

        .login-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .login-subtitle {
            color: var(--muted);
            margin-top: 0.4rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.4rem;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 0.95rem;
            background: var(--surface);
            color: var(--text);
            transition: all 0.2s ease;
            font-family: 'Plus Jakarta Sans', monospace;
        }

        .password-wrapper .form-control {
            padding-right: 3rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 44, 0.2);
        }

        .form-control::placeholder {
            color: #3b455e;
            font-weight: 400;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(21, 31, 52, 0.7);
            backdrop-filter: blur(2px);
            border: none;
            color: var(--muted);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            width: 32px;
            height: 32px;
            border-radius: 40px;
            z-index: 2;
        }

        .toggle-password:hover {
            color: var(--orange);
            background: rgba(255, 107, 44, 0.2);
        }

        .toggle-password:active {
            transform: translateY(-50%) scale(0.96);
        }

        .toggle-password:focus-visible {
            outline: 2px solid #FF6B2C;
            outline-offset: 2px;
            border-radius: 30px;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.8rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--muted);
            cursor: pointer;
        }

        .remember input {
            accent-color: var(--orange);
            width: 16px;
            height: 16px;
            margin: 0;
        }

        .btn {
            width: 100%;
            padding: 0.9rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            border: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--orange), var(--orange2));
            color: white;
            box-shadow: 0 6px 14px rgba(255, 107, 44, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px -8px rgba(255, 107, 44, 0.5);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .login-footer a {
            color: var(--orange);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            text-decoration: underline;
            color: #ff9040;
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1.2rem;
        }

        .back-link a {
            color: var(--muted);
            font-size: 0.85rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: white;
        }

        .alert {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #F87171;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 1.8rem 1.4rem;
            }
            .toggle-password {
                right: 12px;
                width: 36px;
                height: 36px;
            }
        }

        .form-control {
            background: #0f172a;
            border-color: #243049;
        }

        .login-card {
            backdrop-filter: blur(16px);
            background: rgba(12, 19, 33, 0.75);
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <h1 class="login-title">Welcome back</h1>
            <p class="login-subtitle">KAS Delivery • fast & secure</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert"><i class="fas fa-exclamation-circle"></i> <?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" value="<?php echo e(old('email')); ?>" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" id="togglePasswordBtn" aria-label="Show/hide password">
                        <i class="fas fa-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-row">
                <label class="remember">
                    <input type="checkbox" name="remember" id="rememberCheck"> Remember me
                </label>
                <a href="<?php echo e(route('password.request')); ?>" style="color: #94A3B8; font-size: 0.8rem; text-decoration: none;">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <p class="login-footer">
            No account? <a href="<?php echo e(route('register')); ?>">Register here</a>
        </p>

        <div class="back-link">
            <a href="<?php echo e(route('home')); ?>"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>

    <script>
        // Password eye toggle functionality (purely visual, does not interfere with Laravel)
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', function() {
                const currentType = passwordInput.getAttribute('type');
                if (currentType === 'password') {
                    passwordInput.setAttribute('type', 'text');
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                } else {
                    passwordInput.setAttribute('type', 'password');
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                }
                passwordInput.focus();
            });
        }

        // Optional: disable browser autofill suggestions (doesn't break backend)
        document.getElementById('email')?.setAttribute('autocomplete', 'off');
        document.getElementById('password')?.setAttribute('autocomplete', 'new-password');
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\auth\login.blade.php ENDPATH**/ ?>