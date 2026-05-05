<?php $__env->startSection('title', 'My Orders'); ?>
<?php $__env->startSection('sl-orders', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>My Orders</h1>
            <p>View all your order history and track deliveries</p>
        </div>
        <a href="<?php echo e(route('user.foods')); ?>" class="btn btn-primary">
            <i class="fas fa-utensils"></i> Order More Food
        </a>
    </div>

    <!-- Filter Tabs -->
    <div style="display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap">
        <a href="<?php echo e(route('user.orders.history')); ?>" class="btn btn-sm <?php echo e(!request('status') ? 'btn-primary' : 'btn-outline'); ?>">
            All Orders
        </a>
        <a href="<?php echo e(route('user.orders.history', ['status' => 'active'])); ?>" class="btn btn-sm <?php echo e(request('status') == 'active' ? 'btn-primary' : 'btn-outline'); ?>">
            Active
        </a>
        <a href="<?php echo e(route('user.orders.history', ['status' => 'completed'])); ?>" class="btn btn-sm <?php echo e(request('status') == 'completed' ? 'btn-primary' : 'btn-outline'); ?>">
            Completed
        </a>
        <a href="<?php echo e(route('user.orders.history', ['status' => 'cancelled'])); ?>" class="btn btn-sm <?php echo e(request('status') == 'cancelled' ? 'btn-primary' : 'btn-outline'); ?>">
            Cancelled
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Food</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong>#<?php echo e($order->id); ?></strong></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.5rem">
                                <?php if($order->food && $order->food->image_path): ?>
                                    <?php if(str_starts_with($order->food->image_path, 'http')): ?>
                                        <img src="<?php echo e($order->food->image_path); ?>" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                                    <?php elseif(Storage::disk('public')->exists($order->food->image_path)): ?>
                                        <img src="<?php echo e(asset('storage/'.$order->food->image_path)); ?>" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                                    <?php else: ?>
                                        <div style="width:40px;height:40px;border-radius:8px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div style="width:40px;height:40px;border-radius:8px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                <?php endif; ?>
                                <span><?php echo e($order->food->name ?? 'N/A'); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($order->quantity); ?></td>
                        <td style="color: var(--orange); font-weight: 700;">₱<?php echo e(number_format($order->total_price, 2)); ?></td>
                        <td>
                            <span class="badge badge-<?php echo e($order->status); ?>">
                                <?php echo e(str_replace('_', ' ', ucfirst($order->status))); ?>

                            </span>
                        </td>
                        <td style="font-size: 0.8rem;"><?php echo e($order->created_at->format('M d, Y h:i A')); ?></td>
                        <td>
                            <div style="display:flex;gap:.25rem;flex-wrap:wrap">
                                <a href="<?php echo e(route('user.orders.show', $order)); ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Track
                                </a>
                                <?php if(in_array($order->status, ['completed', 'delivered'])): ?>
                                    <a href="<?php echo e(route('user.orders.reorder', $order)); ?>" class="btn btn-sm btn-outline" title="Reorder">
                                        <i class="fas fa-redo"></i> Reorder
                                    </a>
                                <?php endif; ?>
                                <?php if(in_array($order->status, ['pending', 'preparing'])): ?>
                                    <form action="<?php echo e(route('user.orders.cancel', $order)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-box-open" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>You haven't placed any orders yet.</p>
                            <a href="<?php echo e(route('user.foods')); ?>" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-utensils"></i> Start Ordering
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 1.5rem;">
            <?php echo e($orders->withQueryString()->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\user\order-history.blade.php ENDPATH**/ ?>