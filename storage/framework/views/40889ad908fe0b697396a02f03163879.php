<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('sl-reports', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Sales Reports</h1>
            <p>Monthly revenue and order statistics</p>
        </div>
    </div>

    <div class="stats-grid" style="margin-bottom: 1.5rem;">
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-chart-line"></i></div>
            <div class="stat-val">₱<?php echo e(number_format($monthly->sum('revenue'), 2)); ?></div>
            <div class="stat-lbl">Total Revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-navy"><i class="fas fa-box"></i></div>
            <div class="stat-val"><?php echo e($monthly->sum('total')); ?></div>
            <div class="stat-lbl">Total Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-green"><i class="fas fa-chart-line"></i></div>
            <div class="stat-val">₱<?php echo e(number_format($monthly->avg('revenue'), 2)); ?></div>
            <div class="stat-lbl">Avg Monthly</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Monthly Report</span>
        </div>
        <div style="padding: 0 1.25rem 1.25rem;">
            <canvas id="adminMonthlyChart" height="110"></canvas>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                        <th>Average Order Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $monthly; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($report->year); ?></td>
                        <td>
                            <?php
                                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            ?>
                            <?php echo e($months[$report->month - 1]); ?>

                        </td>
                        <td><?php echo e($report->total); ?></td>
                        <td style="color: var(--orange); font-weight: 700;">₱<?php echo e(number_format($report->revenue, 2)); ?></td>
                        <td>₱<?php echo e(number_format($report->revenue / $report->total, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-chart-line" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No sales data available yet.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<?php
    $chartRows = $monthly->reverse()->values();
    $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $chartLabels = $chartRows->map(function ($report) use ($monthNames) {
        return $monthNames[$report->month - 1] . ' ' . $report->year;
    })->values();
    $chartRevenue = $chartRows->pluck('revenue')->values();
    $chartOrders = $chartRows->pluck('total')->values();
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(() => {
    const ctx = document.getElementById('adminMonthlyChart');
    if (!ctx || typeof Chart === 'undefined') return;

    const labels = <?php echo json_encode($chartLabels, 15, 512) ?>;
    const revenueData = <?php echo json_encode($chartRevenue, 15, 512) ?>;
    const ordersData = <?php echo json_encode($chartOrders, 15, 512) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Revenue (PHP)',
                    data: revenueData,
                    borderColor: '#FF6B2C',
                    backgroundColor: 'rgba(255,107,44,0.12)',
                    borderWidth: 3,
                    tension: 0.35,
                    yAxisID: 'yRevenue',
                    fill: true,
                    pointRadius: 3
                },
                {
                    label: 'Orders',
                    data: ordersData,
                    backgroundColor: 'rgba(13,27,75,0.75)',
                    borderRadius: 8,
                    yAxisID: 'yOrders'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                yRevenue: {
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        callback: (value) => 'P' + Number(value).toLocaleString()
                    }
                },
                yOrders: {
                    type: 'linear',
                    position: 'right',
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\reports.blade.php ENDPATH**/ ?>