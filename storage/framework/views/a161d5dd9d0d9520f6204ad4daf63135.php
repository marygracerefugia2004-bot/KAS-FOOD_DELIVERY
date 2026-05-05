<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — KAS Delivery</title>
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

        /* background pattern exactly like login */
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

        /* unified card style (same as login) */
        .register-card {
            width: 100%;
            max-width: 480px;
            background: var(--card-bg);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(30, 41, 59, 0.6);
            border-radius: 32px;
            padding: 2.2rem 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.2s ease;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        /* icon style - gradient orange same as login */
        .register-icon {
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

        .register-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: -0.3px;
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

        /* input wrapper for eye and any relative positioning */
        .input-wrapper {
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
            letter-spacing: 0.2px;
        }

        /* ensure space for eye icon when toggle is present */
        .input-wrapper .form-control {
            padding-right: 3rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 44, 0.2);
        }

        .form-control.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        .form-control.success {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }

        .form-control::placeholder {
            color: #3b455e;
            font-weight: 400;
        }

        /* eye toggle button - same style as login */
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

        /* error / hint messages */
        .error-message,
        .confirm-error {
            display: block;
            font-size: 0.75rem;
            color: #f87171;
            margin-top: 0.25rem;
        }

        /* password strength meter */
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-meter {
            width: 100%;
            height: 4px;
            background: #1e293b;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .strength-text {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .strength-text.weak {
            color: #ef4444;
        }

        .strength-text.medium {
            color: #f59e0b;
        }

        .strength-text.strong {
            color: #10b981;
        }

        .password-hint {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 0.5rem;
            line-height: 1.4;
        }

        /* account type radio group */
        .radio-group {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.3rem;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text);
        }

        .radio-group input {
            accent-color: var(--orange);
            width: 18px;
            height: 18px;
            margin: 0;
        }

        /* primary button */
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

        .register-footer {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .register-footer a {
            color: var(--orange);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }

        .register-footer a:hover {
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

        /* alert box for laravel errors (preserved style) */
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

        /* mobile adjustments */
        @media (max-width: 480px) {
            .register-card {
                padding: 1.8rem 1.4rem;
            }

            .toggle-password {
                right: 12px;
                width: 36px;
                height: 36px;
            }
        }

        /* additional consistency */
        .form-control {
            background: #0f172a;
            border-color: #243049;
        }

        .register-card {
            backdrop-filter: blur(16px);
            background: rgba(12, 19, 33, 0.75);
        }
    </style>
</head>
<body>

<div class="bg-pattern"></div>

<div class="register-card">
    <div class="register-header">
        <div class="register-icon"><i class="fas fa-user-plus"></i></div>
        <h1 class="register-title">Create Account</h1>
        <p class="login-subtitle" style="color: var(--muted); font-size: 0.9rem; margin-top: 0.3rem;">Join KAS Delivery</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert"><i class="fas fa-exclamation-circle"></i> <?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Doe" value="<?php echo e(old('name')); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="you@email.com" value="<?php echo e(old('email')); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Phone (exactly 11 digits)</label>
            <div class="input-wrapper">
                <input type="tel" name="phone" id="phone" class="form-control" placeholder="09123456789" value="<?php echo e(old('phone')); ?>" maxlength="11" pattern="\d*" inputmode="numeric">
            </div>
            <div class="error-message" id="phoneError" style="display:none;">Phone number must be exactly 11 digits (numbers only).</div>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrapper">
                <input type="password" name="password" id="password" class="form-control" placeholder="Min. 8 characters" required>
                <button type="button" class="toggle-password" data-target="password"><i class="far fa-eye-slash"></i></button>
            </div>
            <div class="password-strength">
                <div class="strength-meter">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
                <div class="strength-text" id="strengthText">Password strength: None</div>
            </div>
            <div class="password-hint">
                <i class="fas fa-info-circle"></i> Use at least 8 chars with uppercase, lowercase, number and special character.
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="input-wrapper">
                <input type="password" name="password_confirmation" id="passwordConfirmation" class="form-control" placeholder="Repeat password" required>
                <button type="button" class="toggle-password" data-target="passwordConfirmation"><i class="far fa-eye-slash"></i></button>
            </div>
            <div class="error-message" id="confirmError" style="display:none;">Passwords do not match.</div>
        </div>

        <div class="form-group">
            <label class="form-label">Account Type</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="role" value="user" checked> Customer
                </label>
                <label>
                    <input type="radio" name="role" value="driver"> Driver
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-rocket"></i> Create Account
        </button>
    </form>

    <p class="register-footer">
        Already have an account? <a href="<?php echo e(route('login')); ?>">Login</a>
    </p>

    <div class="back-link">
        <a href="<?php echo e(route('home')); ?>"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
</div>

<script>
    // ----- Phone validation (exactly 11 digits, numbers only) -----
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phoneError');

    function validatePhone() {
        let phone = phoneInput.value.trim();
        // Remove any non-digit characters (sanitize)
        phone = phone.replace(/\D/g, '');
        if (phone.length === 0) {
            phoneError.style.display = 'none';
            phoneInput.classList.remove('error', 'success');
            return true; // will be caught by required rule later
        }
        if (phone.length !== 11) {
            phoneError.style.display = 'block';
            phoneInput.classList.add('error');
            phoneInput.classList.remove('success');
            return false;
        } else {
            phoneError.style.display = 'none';
            phoneInput.classList.remove('error');
            phoneInput.classList.add('success');
            return true;
        }
    }

    phoneInput.addEventListener('input', function(e) {
        // Allow only digits and limit to 11 chars
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
        validatePhone();
    });

    // ----- Password strength meter -----
    const passwordField = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthTextSpan = document.getElementById('strengthText');

    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!%?]+/)) strength++;

        if (password.length === 0) return { level: 0, text: 'None', class: '' };
        if (strength <= 2) return { level: 1, text: 'Weak', class: 'weak' };
        if (strength === 3 || strength === 4) return { level: 2, text: 'Medium', class: 'medium' };
        return { level: 3, text: 'Strong', class: 'strong' };
    }

    function updateStrengthMeter() {
        const password = passwordField.value;
        const strength = checkPasswordStrength(password);
        if (strength.level === 1) {
            strengthBar.style.width = '33%';
            strengthBar.style.backgroundColor = '#ef4444';
            strengthTextSpan.innerHTML = `Password strength: <strong class="weak">Weak</strong>`;
        } else if (strength.level === 2) {
            strengthBar.style.width = '66%';
            strengthBar.style.backgroundColor = '#f59e0b';
            strengthTextSpan.innerHTML = `Password strength: <strong class="medium">Medium</strong>`;
        } else if (strength.level === 3) {
            strengthBar.style.width = '100%';
            strengthBar.style.backgroundColor = '#10b981';
            strengthTextSpan.innerHTML = `Password strength: <strong class="strong">Strong</strong>`;
        } else {
            strengthBar.style.width = '0%';
            strengthBar.style.backgroundColor = '#1e293b';
            strengthTextSpan.innerHTML = `Password strength: None`;
        }
        // Re-check confirm password match (real-time)
        validateConfirmPassword();
    }

    passwordField.addEventListener('input', updateStrengthMeter);

    // ----- Confirm password match -----
    const confirmField = document.getElementById('passwordConfirmation');
    const confirmErrorDiv = document.getElementById('confirmError');

    function validateConfirmPassword() {
        if (confirmField.value === '') {
            confirmErrorDiv.style.display = 'none';
            confirmField.classList.remove('error', 'success');
            return true;
        }
        if (passwordField.value !== confirmField.value) {
            confirmErrorDiv.style.display = 'block';
            confirmField.classList.add('error');
            confirmField.classList.remove('success');
            return false;
        } else {
            confirmErrorDiv.style.display = 'none';
            confirmField.classList.remove('error');
            confirmField.classList.add('success');
            return true;
        }
    }

    confirmField.addEventListener('input', validateConfirmPassword);
    passwordField.addEventListener('input', validateConfirmPassword);

    // ----- Toggle password visibility (eye) with updated style (identical function) -----
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });

    // ----- Form submit validation (preserving original validation logic) -----
    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        // Phone validation (exactly 11 digits and not empty)
        const phoneRaw = phoneInput.value.trim();
        if (phoneRaw === '') {
            phoneError.style.display = 'block';
            phoneError.innerText = 'Phone number is required.';
            phoneInput.classList.add('error');
            isValid = false;
        } else if (phoneRaw.replace(/\D/g, '').length !== 11) {
            phoneError.style.display = 'block';
            phoneError.innerText = 'Phone number must be exactly 11 digits.';
            phoneInput.classList.add('error');
            isValid = false;
        } else {
            // update actual value to digits only for consistency
            phoneInput.value = phoneRaw.replace(/\D/g, '').slice(0, 11);
            if (!validatePhone()) isValid = false;
        }
        
        // Password length check (at least 8)
        if (passwordField.value.length < 8) {
            alert('Password must be at least 8 characters long.');
            isValid = false;
        }
        
        // Confirm password match
        if (!validateConfirmPassword()) {
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
</script>
</body>
</html><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\auth\register.blade.php ENDPATH**/ ?>