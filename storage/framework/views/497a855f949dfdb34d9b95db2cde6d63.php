<?php $__env->startSection('title','Browse Menu'); ?>
<?php $__env->startSection('sl-foods','active'); ?>
<?php $__env->startSection('content'); ?>
<div style="max-width:1200px;margin:0 auto;padding:2rem">
    <div class="page-header">
        <h1><i class="fas fa-search" style="color:var(--orange)"></i> Browse Menu</h1>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom:1.5rem">
        <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end">
            <div style="flex:1;min-width:200px">
                <label class="form-label">Search</label>
                <input name="search" class="form-control" placeholder="Search food..." value="<?php echo e(request('search')); ?>">
            </div>
            <div>
                <label class="form-label">Category</label>
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <option value="main" <?php echo e(request('category')=='main'?'selected':''); ?>>Main Course</option>
                    <option value="snack" <?php echo e(request('category')=='snack'?'selected':''); ?>>Snacks</option>
                    <option value="drink" <?php echo e(request('category')=='drink'?'selected':''); ?>>Drinks</option>
                    <option value="dessert" <?php echo e(request('category')=='dessert'?'selected':''); ?>>Dessert</option>
                </select>
            </div>
            <button class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="<?php echo e(route('user.foods')); ?>" class="btn btn-outline">Clear</a>
        </form>
    </div>

    <div class="food-grid">
        <?php $__empty_1 = true; $__currentLoopData = $foods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $food): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="food-card fade-in">
            <?php if($food->image_path): ?>
                <?php if(str_starts_with($food->image_path, 'http')): ?>
                    <img class="food-img" src="<?php echo e($food->image_path); ?>" alt="<?php echo e($food->name); ?>">
                <?php else: ?>
                    <img class="food-img" src="<?php echo e(asset('storage/'.$food->image_path)); ?>" alt="<?php echo e($food->name); ?>">
                <?php endif; ?>
            <?php else: ?>
                <div class="food-img-placeholder"><i class="fas fa-utensils"></i></div>
            <?php endif; ?>
            <div class="food-body">
                <div class="food-name"><?php echo e($food->name); ?></div>
                <div style="font-size:.78rem;color:var(--text-muted);margin:.3rem 0"><?php echo e(Str::limit($food->description,60)); ?></div>
                <div style="display:flex;align-items:center;gap:.5rem;margin:.4rem 0">
                    <span class="stars"><?php echo e(str_repeat('★',$food->avgRating())); ?><?php echo e(str_repeat('☆',5-$food->avgRating())); ?></span>
                    <span style="font-size:.75rem;color:var(--text-muted)">(<?php echo e($food->ratings_count); ?>)</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <span class="food-price">₱<?php echo e(number_format($food->price,2)); ?></span>
                    <span style="font-size:.75rem;color:var(--text-muted)"><i class="fas fa-clock"></i> ~<?php echo e($food->prep_time); ?>min</span>
                </div>
                <div class="food-actions">
                    <a href="<?php echo e(route('user.order.create', $food)); ?>" class="btn btn-success btn-sm" style="flex:1;justify-content:center;text-align:center">
                        <i class="fas fa-bolt"></i> Buy Now
                    </a>
                    <button class="btn btn-primary btn-sm add-to-cart" data-id="<?php echo e($food->id); ?>" style="flex:1;justify-content:center">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline btn-sm fav-btn" data-id="<?php echo e($food->id); ?>" title="Favorite">
                        <i class="<?php echo e(in_array($food->id, $favIds) ? 'fas' : 'far'); ?> fa-heart" style="color:var(--orange)"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted)">
            <i class="fas fa-search" style="font-size:2.5rem;margin-bottom:1rem;display:block"></i>
            No foods found.
        </div>
        <?php endif; ?>
    </div>
    <div style="margin-top:1.5rem"><?php echo e($foods->appends(request()->all())->links()); ?></div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart
    document.querySelectorAll('.add-to-cart').forEach(function(button) {
        button.addEventListener('click', async function() {
            var btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            const res = await fetch('<?php echo e(route("user.cart.add")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ food_id: btn.dataset.id })
            });
            
            const data = await res.json();
            if (data.ok) {
                btn.innerHTML = '<i class="fas fa-check"></i> Added';
                // Update cart count if available
                if (data.cart_count) {
                    var cartBadge = document.querySelector('.nav-cart-count, [class*="cart-count"]');
                    if (cartBadge) cartBadge.textContent = data.cart_count;
                }
                setTimeout(function() {
                    btn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
                    btn.disabled = false;
                }, 1500);
            } else {
                alert('Error adding to cart');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
            }
        });
    });

    // Favorite toggle
    document.querySelectorAll('.fav-btn').forEach(function(btn) {
        btn.addEventListener('click', async function() {
            const res = await fetch('/user/favorites', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ food_id: btn.dataset.id })
            });
            const data = await res.json();
            const icon = btn.querySelector('i');
            if (data.status === 'added') {
                icon.className = 'fas fa-heart';
                icon.style.color = 'var(--orange)';
            } else {
                icon.className = 'far fa-heart';
                icon.style.color = '';
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/user/foods.blade.php ENDPATH**/ ?>