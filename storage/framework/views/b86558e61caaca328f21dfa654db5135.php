<?php $__env->startSection('title', 'Withdrawal History'); ?>
<?php $__env->startSection('page-title', 'Withdrawals'); ?>
<?php $__env->startSection('sl-earnings', 'active'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 900px; margin: 0 auto; padding: 2rem">
    <div class="page-header" style="margin-bottom: 2rem">
        <h1><i class="fas fa-list" style="color: var(--orange)"></i> Withdrawal History</h1>
        <p>All your payout requests</p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-history"></i> Withdrawal Requests</span>
            <a href="<?php echo e(route('driver.earnings.wallet')); ?>" class="btn btn-sm btn-primary">New Withdrawal</a>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#<?php echo e($w->id); ?></td>
                        <td style="font-weight: 700; color: var(--orange)">₱<?php echo e(number_format($w->amount, 2)); ?></td>
                        <td><?php echo e(ucfirst(str_replace('_', ' ', $w->payment_method))); ?></td>
                        <td style="font-size: 0.85rem"><?php echo e(Str::limit($w->payment_details, 30)); ?></td>
                        <td>
                            <span class="badge badge-<?php echo e($w->status == 'completed' ? 'delivered' : ($w->status == 'rejected' ? 'cancelled' : ($w->status == 'processing' ? 'assigned' : 'pending'))); ?>">
                                <?php echo e(ucfirst($w->status)); ?>

                            </span>
                            <?php if($w->admin_notes): ?>
                                <small style="display: block; color: var(--muted); font-size: 0.75rem"><?php echo e($w->admin_notes); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($w->created_at->format('M d, Y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" style="text-align: center; color: var(--muted)">No withdrawal requests.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem">
            <?php echo e($withdrawals->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\driver\earnings\withdrawals.blade.php ENDPATH**/ ?>