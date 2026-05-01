<div class="<?php echo e($class ?? ''); ?>" style="position: relative; width: <?php echo e($size); ?>px; height: <?php echo e($size); ?>px;">
    <?php if($food && $food->image_path): ?>
        <?php if(str_starts_with($food->image_path, 'http')): ?>
            <img src="<?php echo e($food->image_path); ?>" 
                 alt="<?php echo e($food->name ?? 'Food'); ?>" 
                 class="absolute inset-0 w-full h-full object-cover rounded">
        <?php else: ?>
            <img src="<?php echo e(asset('storage/'.$food->image_path)); ?>" 
                 alt="<?php echo e($food->name ?? 'Food'); ?>" 
                 class="absolute inset-0 w-full h-full object-cover rounded">
        <?php endif; ?>
    <?php else: ?>
        <div class="absolute inset-0 w-full h-full flex items-center justify-center bg-gray-200 rounded">
            <i class="fas fa-utensils text-gray-400"></i>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/components/food-image.blade.php ENDPATH**/ ?>