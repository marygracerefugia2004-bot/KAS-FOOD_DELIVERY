<?php $__env->startSection('title','Manage Orders'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="fas fa-box" style="color:var(--orange)"></i> Manage Orders</h1>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Customer</th><th>Food</th><th>Status</th><th>Driver</th><th>Total</th><th>Assign Driver</th><th>Date</th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><strong>#<?php echo e($order->id); ?></strong></td>
                <td><?php echo e($order->user->name); ?></td>
                <td><?php echo e($order->food->name); ?></td>
                <td><span class="badge badge-<?php echo e($order->status); ?>"><?php echo e(str_replace('_',' ',$order->status)); ?></span></td>
                <td><?php echo e($order->driver?->name ?? '—'); ?></td>
                <td style="color:var(--orange);font-weight:700">₱<?php echo e(number_format($order->total_price,2)); ?></td>
                <td>
                    <?php if(in_array($order->status,['pending'])): ?>
                    <form action="<?php echo e(route('admin.orders.assign', $order)); ?>" method="POST" style="display:flex;gap:.4rem">
                        <?php echo csrf_field(); ?>
                        <select name="driver_id" class="form-control" style="font-size:.8rem;padding:.35rem" required>
                            <option value="">Select driver</option>
                            <?php $__currentLoopData = \App\Models\User::where('role','driver')->where('is_active',true)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-user-check"></i></button>
                    </form>
                    <?php else: ?> <span style="color:var(--text-muted);font-size:.8rem">N/A</span> <?php endif; ?>
                </td>
                <td style="font-size:.8rem;color:var(--text-muted)"><?php echo e($order->created_at->format('M d')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem"><?php echo e($orders->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>