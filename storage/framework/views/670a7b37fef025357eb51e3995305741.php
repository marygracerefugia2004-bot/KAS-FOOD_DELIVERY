
<?php $__env->startSection('title', 'Live Monitor'); ?>
<?php $__env->startSection('sl-monitor', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Live Order Monitor</h1>
            <p>Real-time tracking of active deliveries</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-users"></i></div>
            <div class="stat-val"><?php echo e($activeUsers ?? 0); ?></div>
            <div class="stat-lbl">Active Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-navy"><i class="fas fa-motorcycle"></i></div>
            <div class="stat-val"><?php echo e($activeDrivers ?? 0); ?></div>
            <div class="stat-lbl">Active Drivers</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-green"><i class="fas fa-truck"></i></div>
            <div class="stat-val"><?php echo e($liveOrders->count() ?? 0); ?></div>
            <div class="stat-lbl">Live Orders</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-shipping-fast"></i> Active Deliveries</span>
        </div>
        <?php $__empty_1 = true; $__currentLoopData = $liveOrders ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="border: 1px solid var(--border); border-radius: 8px; margin-bottom: 1rem; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <strong>Order #<?php echo e($order->id); ?></strong>
                <span class="badge badge-<?php echo e($order->status); ?>"><?php echo e(str_replace('_', ' ', $order->status)); ?></span>
            </div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                <div><strong>Food:</strong> <?php echo e($order->food->name); ?></div>
                <div><strong>Customer:</strong> <?php echo e($order->user->name); ?></div>
                <?php if($order->driver): ?>
                <div><strong>Driver:</strong> <?php echo e($order->driver->name); ?></div>
                <?php endif; ?>
                <div><strong>Address:</strong> <?php echo e($order->delivery_address); ?></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div style="text-align: center; padding: 3rem;">
            <i class="fas fa-truck" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
            <p>No active deliveries at the moment.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Auto-refresh every 10 seconds
setInterval(() => {
    location.reload();
}, 10000);
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\monitor.blade.php ENDPATH**/ ?>