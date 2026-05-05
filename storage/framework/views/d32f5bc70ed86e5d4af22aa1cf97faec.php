<?php $__env->startSection('title', 'Earnings History'); ?>
<?php $__env->startSection('page-title', 'Earnings'); ?>
<?php $__env->startSection('sl-earnings', 'active'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1000px; margin: 0 auto; padding: 2rem">
    <div class="page-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem">
        <div>
            <h1><i class="fas fa-money-bill-wave" style="color: var(--orange)"></i> My Earnings</h1>
            <p>Track your delivery income and payout history</p>
        </div>
        <div style="display: flex; gap: 0.5rem">
            <a href="<?php echo e(route('driver.earnings.wallet')); ?>" class="btn btn-navy">
                <i class="fas fa-wallet"></i> Wallet
            </a>
            <a href="<?php echo e(route('driver.earnings.withdrawals')); ?>" class="btn btn-outline">
                <i class="fas fa-list"></i> Withdrawals
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <!-- Stats Row -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: 2rem">
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-total-bill"></i></div>
            <div class="stat-val">₱<?php echo e(number_format($stats['total_earned'], 2)); ?></div>
            <div class="stat-lbl">Total Earned</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-val">₱<?php echo e(number_format($stats['available'], 2)); ?></div>
            <div class="stat-lbl">Available Balance</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-navy"><i class="fas fa-clock"></i></div>
            <div class="stat-val">₱<?php echo e(number_format($stats['pending'], 2)); ?></div>
            <div class="stat-lbl">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico" style="background: #FEF3C7; color: #92400E"><i class="fas fa-box"></i></div>
            <div class="stat-val"><?php echo e($stats['deliveries_count']); ?></div>
            <div class="stat-lbl">Deliveries</div>
        </div>
    </div>

    <!-- Earnings Table -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-list"></i> Earnings History</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Amount</th>
                        <th>Commission</th>
                        <th>Net</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $earnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#<?php echo e($e->order_id); ?></td>
                        <td>₱<?php echo e(number_format($e->order_amount, 2)); ?></td>
                        <td><?php echo e($e->commission_percent); ?>% (₱<?php echo e(number_format($e->commission_amount, 2)); ?>)</td>
                        <td style="color: var(--orange); font-weight: 700">₱<?php echo e(number_format($e->net_amount, 2)); ?></td>
                        <td>
                            <span class="badge badge-<?php echo e($e->status === 'paid' ? 'delivered' : 'pending'); ?>">
                                <?php echo e(ucfirst($e->status)); ?>

                            </span>
                        </td>
                        <td><?php echo e($e->created_at->format('M d, Y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" style="text-align: center; color: var(--muted)">No earnings yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem">
            <?php echo e($earnings->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\driver\earnings\index.blade.php ENDPATH**/ ?>