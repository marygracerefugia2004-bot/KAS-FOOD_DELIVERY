<?php $__env->startSection('title', 'Support Chat'); ?>
<?php $__env->startSection('page-title', 'Support'); ?>
<?php $__env->startSection('sl-support', 'active'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .support-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 100%);
        border-radius: var(--r2);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .support-header h1 {
        color: #fff;
        font-family: var(--font2);
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }
    .support-header p {
        color: rgba(255,255,255,0.6);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    .chat-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r2);
        overflow: hidden;
    }
    .chat-messages {
        height: 450px;
        overflow-y: auto;
        padding: 1.25rem;
        background: var(--bg);
    }
    .chat-row {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
        max-width: 85%;
    }
    .chat-row.mine {
        margin-left: auto;
        flex-direction: row-reverse;
    }
    .chat-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
        font-size: 0.8rem;
    }
    .chat-row.mine .chat-avatar {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
    }
    .chat-row.theirs .chat-avatar {
        background: linear-gradient(135deg, var(--navy), var(--navy2));
    }
    .chat-bubble {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 0.75rem 1rem;
        position: relative;
    }
    .chat-row.mine .chat-bubble {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        color: #fff;
        border: none;
        border-bottom-right-radius: 4px;
    }
    .chat-row.theirs .chat-bubble {
        border-bottom-left-radius: 4px;
    }
    .chat-meta {
        font-size: 0.65rem;
        margin-bottom: 0.25rem;
    }
    .chat-row.mine .chat-meta {
        color: rgba(255,255,255,0.7);
    }
    .chat-row.theirs .chat-meta {
        color: var(--muted);
    }
    .chat-text {
        line-height: 1.5;
        font-size: 0.9rem;
    }
    .chat-input-area {
        padding: 1rem;
        border-top: 1px solid var(--border);
        background: var(--surface);
        display: flex;
        gap: 0.75rem;
    }
    .chat-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--r2);
        background: var(--bg);
        color: var(--text);
        font-size: 0.9rem;
        font-family: inherit;
    }
    .chat-input:focus {
        outline: none;
        border-color: var(--orange);
    }
    .chat-send-btn {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        color: #fff;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: var(--r);
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .chat-send-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 44, 0.3);
    }
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--muted);
    }
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
        opacity: 0.5;
    }
</style>

<div class="main" style="max-width: 800px; margin: 0 auto;">
    <div class="support-header">
        <h1><i class="fas fa-headset" style="margin-right: 0.5rem;"></i> Support Chat</h1>
        <p>Need help? Send a message to our admin team.</p>
    </div>

    <?php if(session('success')): ?>
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: var(--success); padding: 1rem; border-radius: var(--r); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="chat-container">
        <div class="chat-messages">
            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="chat-row <?php echo e($msg->direction == 'driver_to_admin' ? 'mine' : 'theirs'); ?>">
                    <div class="chat-avatar">
                        <i class="fas fa-<?php echo e($msg->direction == 'driver_to_admin' ? 'user' : 'headset'); ?>"></i>
                    </div>
                    <div>
                        <div class="chat-bubble">
                            <div class="chat-meta">
                                <?php echo e($msg->direction == 'driver_to_admin' ? 'You' : 'Support'); ?> · <?php echo e($msg->created_at->format('M d, h:i A')); ?>

                            </div>
                            <div class="chat-text"><?php echo e($msg->message); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state">
                    <i class="fas fa-comment-slash"></i>
                    <p>No messages yet. Start the conversation below!</p>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" action="<?php echo e(route('driver.support.send')); ?>" class="chat-input-area">
            <?php echo csrf_field(); ?>
            <input type="text" name="message" class="chat-input" placeholder="Type your message..." required autofocus>
            <button type="submit" class="chat-send-btn">
                <i class="fas fa-paper-plane"></i> Send
            </button>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\driver\support\index.blade.php ENDPATH**/ ?>