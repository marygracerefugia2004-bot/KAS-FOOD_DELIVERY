<?php $__env->startSection('title','Audit Logs'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="fas fa-history" style="color:var(--orange)"></i> Audit Logs</h1>
    <p style="color:var(--danger);font-weight:700;font-size:.85rem"><i class="fas fa-lock"></i> Immutable — logs cannot be edited or deleted</p>
</div>
<div class="card" style="margin-bottom:1.5rem">
    <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap">
        <input name="action" class="form-control" style="max-width:160px" placeholder="Action..." value="<?php echo e(request('action')); ?>">
        <input name="date" type="date" class="form-control" style="max-width:160px" value="<?php echo e(request('date')); ?>">
        <input name="user_id" type="number" class="form-control" style="max-width:120px" placeholder="User ID" value="<?php echo e(request('user_id')); ?>">
        <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
    </form>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>User</th><th>Role</th><th>Action</th><th>Description</th><th>IP</th><th>Time</th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="font-size:.8rem;color:var(--text-muted)"><?php echo e($log->id); ?></td>
                <td><?php echo e($log->user?->name ?? '—'); ?></td>
                <td><span class="badge badge-<?php echo e($log->user?->role ?? 'user'); ?>"><?php echo e($log->user?->role ?? 'N/A'); ?></span></td>
                <td><span style="font-weight:700;color:var(--navy)"><?php echo e($log->action); ?></span></td>
                <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo e($log->description); ?></td>
                <td style="font-family:monospace;font-size:.75rem;color:var(--text-muted)"><?php echo e($log->ip_address); ?></td>
                <td style="font-size:.8rem;color:var(--text-muted)"><?php echo e($log->created_at->format('M d, g:i A')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem"><?php echo e($logs->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\audit-logs.blade.php ENDPATH**/ ?>