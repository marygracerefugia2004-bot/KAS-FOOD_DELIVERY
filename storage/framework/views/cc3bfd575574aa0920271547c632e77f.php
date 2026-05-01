<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP — KAS Delivery</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* reuse your existing CSS variables and card style */
        :root{
            --orange:#FF6B2C; --orange2:#FF8B00;
            --bg:#080D1A; --text:#fff; --muted:#94A3B8;
            --surface:#0F172A; --border:#1E293B;
        }
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);display:flex;align-items:center;justify-content:center;min-height:100vh;padding:2rem}
        .card{max-width:420px;width:100%;background:rgba(15,23,42,.8);backdrop-filter:blur(20px);border:1px solid var(--border);border-radius:20px;padding:2rem}
        .btn-primary{background:linear-gradient(135deg,var(--orange),var(--orange2));width:100%;padding:.8rem;border-radius:10px;border:none;color:#fff;font-weight:700;cursor:pointer}
        .form-control{width:100%;padding:.75rem;background:var(--surface);border:1px solid var(--border);border-radius:10px;color:#fff}
    </style>
</head>
<body>
<div class="card">
    <h2 style="margin-bottom:1rem">📧 Verify Your Email</h2>
    <p>We sent a 6‑digit code to <strong><?php echo e($email); ?></strong></p>

    <?php if(session('success')): ?>
        <div style="background:#05966920;border-color:#059669;color:#6EE7B7;padding:.75rem;border-radius:10px;margin-bottom:1rem"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('otp.verify')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="email" value="<?php echo e($email); ?>">
        <div class="form-group" style="margin-bottom:1rem">
            <label>OTP Code</label>
            <input type="text" name="otp" class="form-control" placeholder="123456" required maxlength="6" autocomplete="one-time-code">
            <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color:#F87171;font-size:.8rem"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <button type="submit" class="btn-primary">Verify & Activate</button>
    </form>

    <form method="POST" action="<?php echo e(route('otp.resend')); ?>" style="margin-top:1rem">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="email" value="<?php echo e($email); ?>">
        <button type="submit" style="background:none;border:none;color:var(--orange);cursor:pointer;font-size:.85rem">⟳ Resend OTP</button>
    </form>
</div>
</body>
</html><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/auth/verify-otp.blade.php ENDPATH**/ ?>