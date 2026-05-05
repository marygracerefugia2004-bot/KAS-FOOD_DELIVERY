
<?php $__env->startSection('title', 'Promo Codes'); ?>
<?php $__env->startSection('sl-promos', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Promo Codes</h1>
            <p>Manage discount codes and promotions</p>
        </div>
        <a href="<?php echo e(route('admin.promos.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Promo Code
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Uses</th>
                        <th>Max Uses</th>
                        <th>Status</th>
                        <th>Expires At</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($promo->id); ?></td>
                        <td>
                            <strong><?php echo e($promo->code); ?></strong>
                         </div>
                        </td>
                        <td style="color: var(--orange); font-weight: 700;"><?php echo e($promo->discount_percent); ?>%</td>
                        <td><?php echo e($promo->used_count ?? 0); ?> / <?php echo e($promo->max_uses); ?></td>
                        <td>
                            <?php if($promo->is_active && (!$promo->expires_at || $promo->expires_at->isFuture())): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Expired</span>
                            <?php endif; ?>
                         </div>
                        <td>
                            <?php if($promo->expires_at): ?>
                                <?php echo e($promo->expires_at->format('M d, Y')); ?>

                            <?php else: ?>
                                <span class="badge badge-info">Never</span>
                            <?php endif; ?>
                         </div>
                        <td><?php echo e($promo->created_at->format('M d, Y')); ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <form action="<?php echo e(route('admin.promos.destroy', $promo)); ?>" method="POST" onsubmit="return confirm('Delete this promo code?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                         </div>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-ticket-alt" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No promo codes found.</p>
                            <a href="<?php echo e(route('admin.promos.create')); ?>" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i> Create First Promo
                            </a>
                         </div>
                        </td>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            <?php echo e($promos->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\promos\index.blade.php ENDPATH**/ ?>