
<?php $__env->startSection('title', 'Suspicious Activity'); ?>
<?php $__env->startSection('sl-security', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Suspicious Activity</h1>
            <p>Monitor failed login attempts and suspicious IP addresses</p>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Failed Attempts</th>
                        <th>Last Seen</th>
                        <th>Risk Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <code><?php echo e($log->ip_address); ?></code>
                         </div>
                        </td>
                        <td>
                            <span class="badge badge-danger"><?php echo e($log->attempts); ?> attempts</span>
                        </div>
                        </td>
                        <td><?php echo e($log->last_seen); ?></div>
                        </td>
                        <td>
                            <?php if($log->attempts > 50): ?>
                                <span class="badge badge-danger">High Risk</span>
                            <?php elseif($log->attempts > 20): ?>
                                <span class="badge badge-warning">Medium Risk</span>
                            <?php else: ?>
                                <span class="badge badge-info">Low Risk</span>
                            <?php endif; ?>
                         </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-shield-alt" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No suspicious activity detected.</p>
                            <p style="font-size: 0.85rem;">All systems are secure.</p>
                         </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            <?php echo e($logs->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\suspicious.blade.php ENDPATH**/ ?>