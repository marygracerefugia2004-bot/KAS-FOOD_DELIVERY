<?php $__env->startSection('title', 'Statistics & Insights'); ?>
<?php $__env->startSection('page-title', 'Statistics'); ?>
<?php $__env->startSection('sl-stats', 'active'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1100px; margin: 0 auto; padding: 2rem">
    <div class="page-header" style="margin-bottom: 2rem">
        <h1><i class="fas fa-chart-line" style="color: var(--orange)"></i> Statistics & Insights</h1>
        <p>Your performance metrics and earnings analytics</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); margin-bottom: 2rem">
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-check"></i></div>
            <div class="stat-val"><?php echo e($stats['totalDeliveries']); ?></div>
            <div class="stat-lbl">Deliveries</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-green"><i class="fas fa-money-bill"></i></div>
            <div class="stat-val">₱<?php echo e(number_format($stats['totalEarnings'], 2)); ?></div>
            <div class="stat-lbl">Total Earnings</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-navy"><i class="fas fa-wallet"></i></div>
            <div class="stat-val">₱<?php echo e(number_format($stats['availableBalance'], 2)); ?></div>
            <div class="stat-lbl">Available</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-clock"></i></div>
            <div class="stat-val"><?php echo e($stats['onTimeRate']); ?>%</div>
            <div class="stat-lbl">On-Time Rate</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico" style="background: #FFF1EB; color: #C2410C"><i class="fas fa-star"></i></div>
            <div class="stat-val"><?php echo e(number_format($stats['avgRating'], 1)); ?> ★</div>
            <div class="stat-lbl">Avg Rating</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico" style="background: #FEF3C7; color: #92400E"><i class="fas fa-trophy"></i></div>
            <div class="stat-val">₱<?php echo e(number_format($stats['bestDayAmount'], 2)); ?></div>
            <div class="stat-lbl">Best Day</div>
        </div>
    </div>

    <!-- Weekly Earnings Chart -->
    <div class="card" style="margin-bottom: 2rem">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Weekly Earnings (Last 8 Weeks)</span>
        </div>
        <div class="card-body" style="overflow-x: auto">
            <table class="table">
                <thead>
                    <tr>
                        <?php $__currentLoopData = $weeklyEarnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th><?php echo e($week['week']); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php $__currentLoopData = $weeklyEarnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td style="text-align: center; font-weight: 700; color: var(--orange)">
                            ₱<?php echo e(number_format($week['amount'], 0)); ?>

                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Days -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-calendar-day"></i> Performance Summary</span>
        </div>
        <div class="card-body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem">
            <div>
                <div style="font-size: 0.85rem; color: var(--muted)">Total Deliveries</div>
                <div style="font-size: 1.8rem; font-weight: 800; color: var(--text)"><?php echo e($stats['totalDeliveries']); ?></div>
            </div>
            <div>
                <div style="font-size: 0.85rem; color: var(--muted)">Total Earnings</div>
                <div style="font-size: 1.8rem; font-weight: 800; color: var(--orange)">₱<?php echo e(number_format($stats['totalEarnings'], 2)); ?></div>
            </div>
            <div>
                <div style="font-size: 0.85rem; color: var(--muted)">Ratings</div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #f5b042"><?php echo e(number_format($stats['avgRating'], 1)); ?> ★</div>
            </div>
            <div>
                <div style="font-size: 0.85rem; color: var(--muted)">On-Time Rate</div>
                <div style="font-size: 1.8rem; font-weight: 800; color: var(--success)"><?php echo e($stats['onTimeRate']); ?>%</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\driver\stats\index.blade.php ENDPATH**/ ?>