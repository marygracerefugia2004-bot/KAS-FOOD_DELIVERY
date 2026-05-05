<?php $__env->startSection('title', 'My Profile'); ?>
<?php $__env->startSection('sl-profile', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 800px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>My Profile</h1>
            <p>Manage your account information</p>
        </div>
    </div>

    <div class="card">
        <form action="<?php echo e(route('user.profile.update')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <!-- Avatar Section -->
            <div class="form-group" style="text-align: center;">
                <label class="form-label">Profile Picture</label>
                <div style="margin-bottom: 1rem;">
                    <?php if($user->avatar): ?>
                        <img src="<?php echo e(asset('storage/'.$user->avatar)); ?>" 
                             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--orange);">
                    <?php else: ?>
                        <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--orange), var(--orange2)); 
                                    display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 3rem; font-weight: bold;">
                            <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <input type="file" name="avatar" class="form-control" accept="image/*">
                <small class="form-text text-muted">Upload a new profile picture (max 2MB)</small>
                <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Name -->
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    value="<?php echo e(old('name', $user->name)); ?>" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" value="<?php echo e($user->email); ?>" disabled>
                <small class="form-text text-muted">Email cannot be changed</small>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    value="<?php echo e(old('phone', $user->phone)); ?>" placeholder="Enter your phone number">
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label class="form-label">Account Type</label>
                <input type="text" class="form-control" value="<?php echo e(ucfirst($user->role)); ?>" disabled>
            </div>

            <!-- Member Since -->
            <div class="form-group">
                <label class="form-label">Member Since</label>
                <input type="text" class="form-control" value="<?php echo e($user->created_at->format('F d, Y')); ?>" disabled>
            </div>

            <!-- Stats -->
            <div style="background: var(--bg); border-radius: 8px; padding: 1rem; margin: 1rem 0;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: center;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--orange);">
                            <?php echo e($user->orders()->count()); ?>

                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Total Orders</div>
                    </div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--orange);">
                            <?php echo e($user->favorites()->count()); ?>

                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Favorites</div>
                    </div>
                </div>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="dark_mode" value="1" <?php echo e($user->dark_mode ? 'checked' : ''); ?> onchange="this.form.submit()">
                    Dark Mode
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
                <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-outline">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Change Password Section -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-key"></i> Change Password</span>
        </div>
        <form action="<?php echo e(route('user.password.update')); ?>" method="POST">
            
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-key"></i> Change Password
            </button>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\user\profile.blade.php ENDPATH**/ ?>