<?php $__env->startSection('title', 'Forgot Password'); ?>
<?php $__env->startSection('content'); ?>

<style>
    :root {
        --orange: #FF6B2C;
        --orange2: #FF8B00;
        --navy: #0D1B4B;
        --navy2: #15277A;
        --bg: #080D1A;
        --surface: #0F172A;
        --border: #1E2D4A;
        --text: #E2E8F0;
        --muted: #94A3B8;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--bg);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        padding: 1rem;
    }

    .forgot-card {
        width: 100%;
        max-width: 440px;
        background: rgba(12, 19, 33, 0.85);
        backdrop-filter: blur(18px);
        border: 1px solid rgba(30, 41, 59, 0.6);
        border-radius: 24px;
        padding: 2.5rem 2rem;
        box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.5);
    }

    .forgot-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .forgot-icon {
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

    .forgot-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: white;
        letter-spacing: -0.3px;
    }

    .forgot-subtitle {
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

    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 14px;
        font-size: 0.95rem;
        background: #0f172a;
        color: var(--text);
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255, 107, 44, 0.2);
    }

    .form-control::placeholder {
        color: #3b455e;
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

    .alert {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #34D399;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #F87171;
    }

    .back-link {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1.5rem;
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

    .email-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 107, 44, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: var(--orange);
        font-size: 1.3rem;
    }
</style>

<div class="forgot-card">
    <div class="forgot-header">
        <div class="forgot-icon">
            <i class="fas fa-key"></i>
        </div>
        <h1 class="forgot-title">Forgot Password?</h1>
        <p class="forgot-subtitle">Enter your email to reset your password</p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.email')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="email-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <input type="email" id="email" name="email" class="form-control" 
                placeholder="you@example.com" value="<?php echo e(old('email')); ?>" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Send Reset Link
        </button>
    </form>

    <div class="back-link">
        <a href="<?php echo e(route('login')); ?>">
            <i class="fas fa-arrow-left"></i> Back to Login
        </a>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>