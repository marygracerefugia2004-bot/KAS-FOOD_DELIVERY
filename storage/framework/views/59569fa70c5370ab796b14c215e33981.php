<?php $__env->startSection('title', 'My Favorites'); ?>
<?php $__env->startSection('sl-favs', 'active'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>My Favorites</h1>
            <p>Your saved food items</p>
        </div>
        <a href="<?php echo e(route('user.foods')); ?>" class="btn btn-primary">
            <i class="fas fa-utensils"></i> Browse Menu
        </a>
    </div>

    <div class="card">
        <?php $__empty_1 = true; $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fav): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if($fav->food): ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1.5rem;padding:1rem">
                <div style="border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:var(--transition)">
                    <a href="<?php echo e(route('user.foods.show', $fav->food)); ?>" style="text-decoration:none">
                        <?php if($fav->food->image_path): ?>
                            <?php if(str_starts_with($fav->food->image_path, 'http')): ?>
                                <img src="<?php echo e($fav->food->image_path); ?>" style="width:100%;height:180px;object-fit:cover">
                            <?php elseif(Storage::disk('public')->exists($fav->food->image_path)): ?>
                                <img src="<?php echo e(asset('storage/'.$fav->food->image_path)); ?>" style="width:100%;height:180px;object-fit:cover">
                            <?php else: ?>
                                <div style="width:100%;height:180px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="width:100%;height:180px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem">
                                <i class="fas fa-utensils"></i>
                            </div>
                        <?php endif; ?>
                        <div style="padding:1rem">
                            <div style="font-weight:700;font-size:1rem;margin-bottom:.5rem"><?php echo e($fav->food->name); ?></div>
                            <div style="color:var(--text-muted);font-size:.85rem;margin-bottom:.75rem">
                                <?php echo e($fav->food->category->name ?? 'Food'); ?>

                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <span style="color:var(--orange);font-weight:700;font-size:1.1rem">₱<?php echo e(number_format($fav->food->price, 2)); ?></span>
                                <button onclick="event.preventDefault();document.getElementById('remove-fav-<?php echo e($fav->id); ?>').submit();" class="btn btn-sm btn-outline" style="color:var(--red)">
                                    <i class="fas fa-heart-broken"></i> Remove
                                </button>
                                <form id="remove-fav-<?php echo e($fav->id); ?>" action="<?php echo e(route('user.favorites.toggle')); ?>" method="POST" style="display:none">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="food_id" value="<?php echo e($fav->food_id); ?>">
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:4rem;color:var(--text-muted)">
                <i class="fas fa-heart" style="font-size:4rem;display:block;margin-bottom:1rem;color:var(--orange)"></i>
                <h3 style="margin-bottom:.5rem">No favorites yet</h3>
                <p style="margin-bottom:1.5rem">Start adding your favorite foods!</p>
                <a href="<?php echo e(route('user.foods')); ?>" class="btn btn-primary">
                    <i class="fas fa-utensils"></i> Browse Menu
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if($favorites->hasPages()): ?>
    <div style="margin-top:1.5rem">
        <?php echo e($favorites->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\user\favorites.blade.php ENDPATH**/ ?>