<?php $__env->startSection('title', 'Manage Foods'); ?>
<?php $__env->startSection('sl-foods', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Manage Foods</h1>
            <p>Add, edit, or remove food items from the menu</p>
        </div>
        <a href="<?php echo e(route('admin.foods.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Food
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Prep Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $foods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $food): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($food->id); ?></td>
                        <td>
                            <?php if($food->image_path): ?>
                                <?php if(str_starts_with($food->image_path, 'http')): ?>
                                    <img src="<?php echo e($food->image_path); ?>" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('storage/'.$food->image_path)); ?>" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                <?php endif; ?>
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                         </td>
                        <td><?php echo e($food->name); ?></td>
                        <td><?php echo e($food->category ?? 'Uncategorized'); ?></td>
                        <td style="color: var(--orange); font-weight: 700;">₱<?php echo e(number_format($food->price, 2)); ?></td>
                        <td><?php echo e($food->prep_time ?? 15); ?> min</td>
                        <td>
                            <?php if($food->is_available): ?>
                                <span class="badge badge-success">Available</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Unavailable</span>
                            <?php endif; ?>
                         </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="<?php echo e(route('admin.foods.edit', $food)); ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.foods.destroy', $food)); ?>" method="POST" onsubmit="return confirm('Delete this food?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <form action="<?php echo e(route('admin.foods.toggle', $food)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button class="btn btn-sm btn-outline" type="submit">
                                        <i class="fas <?php echo e($food->is_available ? 'fa-eye-slash' : 'fa-eye'); ?>"></i>
                                    </button>
                                </form>
                            </div>
                         </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-utensils" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No food items found.</p>
                            <a href="<?php echo e(route('admin.foods.create')); ?>" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i> Add First Food
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            <?php echo e($foods->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\foods\index.blade.php ENDPATH**/ ?>