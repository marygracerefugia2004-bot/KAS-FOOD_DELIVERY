<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Admin Dashboard'); ?>
<?php $__env->startSection('sl-dashboard', 'active'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-hdr fade-up">
    <div class="page-hdr-left">
        <div class="page-breadcrumb">
            <a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a>
            <span>/</span>
            <span>Dashboard</span>
        </div>
        <h1>Operations Overview</h1>
        <p>Track platform health, orders, and growth in one place.</p>
    </div>
    <div style="display:flex;gap:.55rem;flex-wrap:wrap">
        <a href="<?php echo e(route('admin.monitor')); ?>" class="btn btn-outline">
            <i class="fas fa-satellite-dish"></i> Live Monitor
        </a>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-primary">
            <i class="fas fa-box"></i> Manage Orders
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card fade-up">
        <div class="stat-ico ico-orange"><i class="fas fa-users"></i></div>
        <div class="stat-val"><?php echo e($stats['users']); ?></div>
        <div class="stat-lbl">Registered Users</div>
    </div>
    <div class="stat-card fade-up-1">
        <div class="stat-ico ico-navy"><i class="fas fa-motorcycle"></i></div>
        <div class="stat-val"><?php echo e($stats['drivers']); ?></div>
        <div class="stat-lbl">Active Drivers</div>
    </div>
    <div class="stat-card fade-up-2">
        <div class="stat-ico ico-navy"><i class="fas fa-box"></i></div>
        <div class="stat-val"><?php echo e($stats['total_orders']); ?></div>
        <div class="stat-lbl">Total Orders</div>
    </div>
    <div class="stat-card fade-up-3">
        <div class="stat-ico ico-red"><i class="fas fa-satellite-dish"></i></div>
        <div class="stat-val" style="color:var(--danger)"><?php echo e($stats['live_orders']); ?></div>
        <div class="stat-lbl">Live Orders</div>
    </div>
    <div class="stat-card fade-up-4">
        <div class="stat-ico ico-orange"><i class="fas fa-clock"></i></div>
        <div class="stat-val" style="color:var(--warning)"><?php echo e($stats['pending_orders']); ?></div>
        <div class="stat-lbl">Pending Orders</div>
    </div>
    <div class="stat-card fade-up-4">
        <div class="stat-ico ico-green"><i class="fas fa-peso-sign"></i></div>
        <div class="stat-val" style="color:var(--success)">₱<?php echo e(number_format($stats['revenue'], 0)); ?></div>
        <div class="stat-lbl">Revenue</div>
    </div>
</div>

<div class="card fade-up" style="margin-bottom:1rem">
    <div class="card-header" style="margin-bottom:0">
        <span class="card-title">
            <span class="card-icon"><i class="fas fa-bolt"></i></span>
            Quick Actions
        </span>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="<?php echo e(route('admin.foods.index')); ?>" class="btn btn-outline btn-sm"><i class="fas fa-hamburger"></i> Foods</a>
        <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline btn-sm"><i class="fas fa-users"></i> Users</a>
        <a href="<?php echo e(route('admin.drivers')); ?>" class="btn btn-outline btn-sm"><i class="fas fa-motorcycle"></i> Drivers</a>
        <a href="<?php echo e(route('admin.reports')); ?>" class="btn btn-outline btn-sm"><i class="fas fa-chart-line"></i> Reports</a>
    </div>
</div>

<div class="card fade-up">
    <div class="card-header">
        <span class="card-title">
            <span class="card-icon"><i class="fas fa-box"></i></span>
            Recent Orders
        </span>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Food</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong>#<?php echo e($order->id); ?></strong></td>
                        <td><?php echo e($order->user->name ?? 'Unknown'); ?></td>
                        <td><?php echo e($order->food->name ?? 'Unavailable Item'); ?></td>
                        <td>
                            <?php if($order->driver): ?>
                                <?php echo e($order->driver->name); ?>

                            <?php else: ?>
                                <span style="color:var(--muted)">Unassigned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e($order->status); ?>">
                                <?php echo e(str_replace('_', ' ', $order->status)); ?>

                            </span>
                        </td>
                        <td style="font-weight:700;color:var(--orange)">₱<?php echo e(number_format($order->total_price, 2)); ?></td>
                        <td style="font-size:.8rem;color:var(--muted)"><?php echo e($order->created_at->diffForHumans()); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--muted)">No recent orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>