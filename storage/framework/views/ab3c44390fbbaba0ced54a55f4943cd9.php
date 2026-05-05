<?php
$pendingRequests = \App\Models\DriverRequest::with('user')->where('status', 'pending')->get();
?>
<?php if($pendingRequests->count()): ?>
<div class="card" style="margin-bottom: 1.5rem; background: #fff3e0; border-left: 4px solid var(--orange);">
    <div class="card-header" style="background: transparent;">
        <h3><i class="fas fa-user-clock"></i> Pending Driver Requests</h3>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>User</th><th>Email</th><th>Message</th><th>Requested</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($req->user->name); ?></td>
                    <td><?php echo e($req->user->email); ?></td>
                    <td><?php echo e($req->message ?? 'No message'); ?></td>
                    <td><?php echo e($req->created_at->diffForHumans()); ?></td>
                    <td>
                        <form action="<?php echo e(route('admin.driver-requests.approve', $req)); ?>" method="POST" style="display:inline-block">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Approve</button>
                        </form>
                        <form action="<?php echo e(route('admin.driver-requests.reject', $req)); ?>" method="POST" style="display:inline-block">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Reject</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>  <?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\admin\driver-requests.blade.php ENDPATH**/ ?>