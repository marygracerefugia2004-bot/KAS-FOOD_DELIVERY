
<?php $__env->startSection('title', 'Manage Users'); ?>
<?php $__env->startSection('sl-users', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Manage Users</h1>
            <p>View and manage all registered customers</p>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                 <thead>
                     <tr>
                         <th>ID</th>
                         <th>Name</th>
                         <th>Email</th>
                         <th>Phone</th>
                         <th>Role</th>
                         <th>Orders</th>
                         <th>Joined</th>
                         <th>Status</th>
                         <th>Actions</th>
                     </tr>
                 </thead>
                <tbody>
                     <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                     <tr>
                         <td><?php echo e($user->id); ?></td>
                         <td><?php echo e($user->name); ?></td>
                         <td><?php echo e($user->email); ?></td>
                         <td><?php echo e($user->phone ?? 'N/A'); ?></td>
                         <td>
                             <form action="<?php echo e(route('admin.users.update-role', $user)); ?>" method="POST" style="display: inline;">
                                 <?php echo csrf_field(); ?>
                                 <?php echo method_field('PATCH'); ?>
                                 <select name="role" onchange="this.form.submit()" style="padding: 0.25rem; border: 1px solid var(--border); border-radius: 4px; background: var(--surface); color: var(--text);">
                                     <option value="user" <?php echo e($user->role === 'user' ? 'selected' : ''); ?>>User</option>
                                     <option value="driver" <?php echo e($user->role === 'driver' ? 'selected' : ''); ?>>Driver</option>
                                     <option value="admin" <?php echo e($user->role === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                 </select>
                             </form>
                         </td>
                         <td><?php echo e($user->orders()->count()); ?></td>
                         <td><?php echo e($user->created_at->format('M d, Y')); ?></td>
                         <td>
                             <?php if($user->is_active ?? true): ?>
                                 <span class="badge badge-success">Active</span>
                             <?php else: ?>
                                 <span class="badge badge-danger">Inactive</span>
                             <?php endif; ?>
                         </td>
                         <td>
                             <div style="display: flex; gap: 0.5rem;">
                                 <form action="<?php echo e(route('admin.users.toggle', $user)); ?>" method="POST" onsubmit="return confirm('Toggle user status?')">
                                     <?php echo csrf_field(); ?>
                                     <?php echo method_field('PATCH'); ?>
                                     <button class="btn btn-sm btn-outline" type="submit">
                                         <i class="fas <?php echo e(($user->is_active ?? true) ? 'fa-ban' : 'fa-check'); ?>"></i>
                                     </button>
                                 </form>
                                 <button class="btn btn-sm btn-outline" type="button" onclick="showDeleteModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')" style="color: var(--danger);">
                                     <i class="fas fa-trash"></i>
                                 </button>
                             </div>
                         </td>
                     </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-users" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No users found.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            <?php echo e($users->links()); ?>

        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; inset: 0; z-index: 1000; align-items: center; justify-content: center; background: rgba(0,0,0,0.5);">
    <div style="background: var(--surface); border-radius: 12px; padding: 1.5rem; max-width: 400px; width: 90%; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <h3 style="margin-bottom: 0.5rem; font-size: 1.25rem;">Delete User</h3>
        <p style="color: var(--muted); margin-bottom: 1rem;">Are you sure you want to delete <strong id="deleteUserName"></strong>? This action cannot be undone.</p>
        <form id="deleteForm" method="POST" style="margin-bottom: 1rem;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Enter your admin password:</label>
            <input type="password" name="admin_password" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 1rem;" placeholder="Admin password">
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete User</button>
            </div>
        </form>
    </div>
</div>

<script>
function showDeleteModal(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = '/admin/users/' + userId;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/admin/users.blade.php ENDPATH**/ ?>