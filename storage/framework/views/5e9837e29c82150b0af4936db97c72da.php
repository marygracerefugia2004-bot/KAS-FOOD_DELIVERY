<?php $__env->startSection('title', 'Edit Maintenance Record'); ?>
<?php $__env->startSection('page-title', 'Edit Maintenance'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 700px; margin: 0 auto; padding: 2rem">
    <div class="page-header" style="margin-bottom: 2rem">
        <h1><i class="fas fa-edit" style="color: var(--orange)"></i> Edit Maintenance Record</h1>
        <p>Update service details</p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('driver.vehicle.update', $maintenance)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem">
                    <div class="form-group">
                        <label class="form-label">Service Type *</label>
                        <input type="text" name="service_type" class="form-control" required value="<?php echo e(old('service_type', $maintenance->service_type)); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service Date *</label>
                        <input type="date" name="service_date" class="form-control" required value="<?php echo e(old('service_date', $maintenance->service_date->format('Y-m-d'))); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Odometer (km)</label>
                        <input type="number" name="odometer_km" class="form-control" min="0" value="<?php echo e(old('odometer_km', $maintenance->odometer_km)); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Next Service Due</label>
                        <input type="date" name="next_service_due" class="form-control" value="<?php echo e(old('next_service_due', $maintenance->next_service_due?->format('Y-m-d'))); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cost (₱)</label>
                        <input type="number" name="cost" class="form-control" step="0.01" min="0" value="<?php echo e(old('cost', $maintenance->cost)); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service Center</label>
                        <input type="text" name="service_center" class="form-control" value="<?php echo e(old('service_center', $maintenance->service_center)); ?>">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 0.5rem">
                    <label class="form-label">Description / Notes</label>
                    <textarea name="description" class="form-control" rows="2"><?php echo e(old('description', $maintenance->description)); ?></textarea>
                </div>

                <?php if($maintenance->receipt_image): ?>
                <div class="form-group">
                    <label>Current Receipt</label><br>
                    <img src="<?php echo e(asset('storage/'.$maintenance->receipt_image)); ?>" style="max-width: 200px; border-radius: 8px; border: 1px solid var(--border)">
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label">Replace Receipt (optional)</label>
                    <input type="file" name="receipt_image" class="form-control" accept="image/*">
                </div>

                <div style="display: flex; gap: 0.5rem; margin-top: 1rem">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                    <form method="POST" action="<?php echo e(route('driver.vehicle.destroy', $maintenance)); ?>" onsubmit="return confirm('Delete this record?');" style="display:inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                    <a href="<?php echo e(route('driver.vehicle.index')); ?>" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\driver\vehicle\edit.blade.php ENDPATH**/ ?>