
<?php $__env->startSection('title', 'Notifications'); ?>
<?php $__env->startSection('sl-notifs', 'active'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 800px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Notifications</h1>
            <p>Stay updated with your orders and promotions</p>
        </div>
    </div>

    <div class="card">
        <?php $__empty_1 = true; $__currentLoopData = $notifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="notification-item <?php echo e(!$notif->is_read ? 'unread' : ''); ?>" style="
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            <?php echo e(!$notif->is_read ? 'background: var(--orange-glow);' : ''); ?>

        ">
            <div class="notification-icon" style="
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background: <?php echo e($notif->type == 'order' ? 'var(--orange)' : 'var(--navy)'); ?>;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                flex-shrink: 0;
            ">
                <i class="fas <?php echo e($notif->type == 'order' ? 'fa-box' : ($notif->type == 'promo' ? 'fa-ticket-alt' : 'fa-bell')); ?>"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-weight: <?php echo e(!$notif->is_read ? '700' : '500'); ?>; margin-bottom: 0.25rem;">
                    <?php echo e($notif->title); ?>

                </div>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                    <?php echo e($notif->message); ?>

                </div>
                <div style="font-size: 0.7rem; color: var(--text-muted);">
                    <i class="far fa-clock"></i> <?php echo e($notif->created_at->diffForHumans()); ?>

                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div style="text-align: center; padding: 3rem;">
            <i class="fas fa-bell-slash" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
            <p style="color: var(--text-muted);">No notifications yet</p>
            <p style="font-size: 0.85rem; color: var(--text-muted);">When you place orders or receive updates, they'll appear here.</p>
        </div>
        <?php endif; ?>
    </div>

    <div style="margin-top: 1.5rem;">
        <?php echo e($notifs->links()); ?>

    </div>
</div>

<style>
.notification-item:hover {
    background: var(--bg);
}
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\shared\notifications.blade.php ENDPATH**/ ?>