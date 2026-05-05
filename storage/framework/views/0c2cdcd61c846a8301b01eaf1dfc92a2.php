<?php $__env->startSection('title', 'Edit Food'); ?>
<?php $__env->startSection('sl-foods', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 600px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <h1>Edit Food</h1>
        <p>Update food item details</p>
    </div>

    <div class="card">
        <form action="<?php echo e(route('admin.foods.update', $food)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="form-group">
                <label class="form-label">Food Name *</label>
                <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $food->name)); ?>" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category" class="form-control <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Select Category</option>
                    <option value="burger" <?php echo e(($food->category == 'burger') ? 'selected' : ''); ?>>🍔 Burgers</option>
                    <option value="pizza" <?php echo e(($food->category == 'pizza') ? 'selected' : ''); ?>>🍕 Pizza</option>
                    <option value="pasta" <?php echo e(($food->category == 'pasta') ? 'selected' : ''); ?>>🍝 Pasta</option>
                    <option value="chicken" <?php echo e(($food->category == 'chicken') ? 'selected' : ''); ?>>🍗 Chicken</option>
                    <option value="seafood" <?php echo e(($food->category == 'seafood') ? 'selected' : ''); ?>>🦞 Seafood</option>
                    <option value="salad" <?php echo e(($food->category == 'salad') ? 'selected' : ''); ?>>🥗 Salads</option>
                    <option value="dessert" <?php echo e(($food->category == 'dessert') ? 'selected' : ''); ?>>🍰 Desserts</option>
                    <option value="drinks" <?php echo e(($food->category == 'drinks') ? 'selected' : ''); ?>>🥤 Drinks</option>
                </select>
                <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3" required><?php echo e(old('description', $food->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Price (₱) *</label>
                <input type="number" step="0.01" name="price" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('price', $food->price)); ?>" required>
                <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Preparation Time (minutes)</label>
                <input type="number" name="prep_time" class="form-control" value="<?php echo e(old('prep_time', $food->prep_time ?? 15)); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Current Image</label>
                <?php if($food->image_path): ?>
                    <div style="margin-bottom: 0.5rem;">
                        <?php if(str_starts_with($food->image_path, 'http')): ?>
                            <img src="<?php echo e($food->image_path); ?>" style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover;">
                        <?php else: ?>
                            <img src="<?php echo e(asset('storage/'.$food->image_path)); ?>" style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="form-text text-muted">Leave empty to keep current image</small>
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_available" value="1" <?php echo e(old('is_available', $food->is_available) ? 'checked' : ''); ?>>
                    Available for ordering
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Food
                </button>
                <a href="<?php echo e(route('admin.foods.index')); ?>" class="btn btn-outline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\foods\edit.blade.php ENDPATH**/ ?>