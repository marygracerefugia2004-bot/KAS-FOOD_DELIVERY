<?php $__env->startSection('title', 'Messages'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-header">
        <h1><i class="fas fa-comments"></i> Messages</h1>
    </div>
    
    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 1rem; height: calc(100vh - 220px); min-height: 500px;">
        <!-- Contacts List -->
        <div class="card" style="overflow: hidden; display: flex; flex-direction: column;">
            <div class="card-header">
                <span style="font-weight: 700;">Conversations</span>
                <a href="<?php echo e(route('messages.index')); ?>" title="Refresh" style="margin-left: auto;">
                    <i class="fas fa-sync"></i>
                </a>
            </div>
            <div style="flex: 1; overflow-y: auto;" id="contacts-list">
                <?php
                    $myId = auth()->id();
                    $allContacts = \App\Models\Message::where('sender_id', $myId)
                        ->orWhere('receiver_id', $myId)
                        ->with('sender','receiver')
                        ->latest()
                        ->get()
                        ->map(fn($m) => $m->sender_id === $myId ? $m->receiver : $m->sender)
                        ->unique('id')
                        ->values();
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $allContacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('messages.conversation', $c)); ?>" 
                   class="contact-item <?php echo e($contact && $contact->id === $c->id ? 'active' : ''); ?>">
                    <div class="contact-avatar">
                        <?php echo e(strtoupper(substr($c->name, 0, 2))); ?>

                    </div>
                    <div class="contact-info">
                        <div class="contact-name"><?php echo e($c->name); ?></div>
                        <div class="contact-role"><?php echo e(ucfirst($c->role)); ?></div>
                    </div>
                    <?php
                        $unreadCount = \App\Models\Message::where('sender_id', $c->id)
                            ->where('receiver_id', $myId)
                            ->where('is_read', false)
                            ->count();
                    ?>
                    <?php if($unreadCount > 0): ?>
                    <span class="unread-badge"><?php echo e($unreadCount); ?></span>
                    <?php endif; ?>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.85rem;">
                    <i class="fas fa-inbox" style="font-size: 1.5rem; margin-bottom: 0.5rem; display: block;"></i>
                    No conversations yet
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Start New Conversation -->
            <div style="border-top: 1px solid var(--border); padding: 0.75rem;">
                <button onclick="document.getElementById('new-chat-modal').style.display='flex'" class="btn btn-primary btn-sm" style="width: 100%;">
                    <i class="fas fa-plus"></i> New Message
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="card" style="display: flex; flex-direction: column; overflow: hidden; padding: 0;">
            <!-- Chat Header -->
            <div style="padding: 1rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.75rem;">
                <div class="chat-hdr-avatar" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--navy), var(--navy2)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 0.8rem;">
                    <?php echo e(strtoupper(substr($contact->name, 0, 2))); ?>

                </div>
                <div>
                    <div style="font-weight: 700;"><?php echo e($contact->name); ?></div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                        <i class="fas fa-circle" style="color: var(--success); font-size: 0.5rem;"></i> <?php echo e(ucfirst($contact->role)); ?>

                    </div>
                </div>
            </div>
            
            <!-- Messages -->
            <div style="flex: 1; overflow-y: auto; padding: 1rem;" id="chat-messages">
                <?php $__empty_1 = true; $__currentLoopData = $msgs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="msg-row <?php echo e($msg->sender_id == auth()->id() ? 'mine' : ''); ?>">
                    <div class="msg-avatar">
                        <?php echo e(strtoupper(substr($msg->sender->name, 0, 2))); ?>

                    </div>
                    <div>
                        <div class="msg-bubble"><?php echo e($msg->message); ?></div>
                        <div class="msg-time"><?php echo e($msg->created_at->format('M d, h:i A')); ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align: center; color: var(--text-muted); padding: 2rem;">
                    No messages yet. Start the conversation!
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Input Form -->
            <form id="message-form" style="padding: 1rem; border-top: 1px solid var(--border); display: flex; gap: 0.5rem;">
                <input type="hidden" name="receiver_id" value="<?php echo e($contact->id); ?>">
                <input type="text" name="message" id="chat-input" class="form-control" placeholder="Type your message..." maxlength="500" style="flex: 1;" required>
                <button type="submit" class="btn btn-primary" id="send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    color: inherit;
    transition: background 0.15s;
}
.contact-item:hover { background: var(--bg); }
.contact-item.active {
    background: var(--orange-glow);
    border-left: 3px solid var(--orange);
}
.contact-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--orange), var(--orange2));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.8rem;
    flex-shrink: 0;
}
.contact-info { flex: 1; min-width: 0; }
.contact-name { font-weight: 600; font-size: 0.9rem; }
.contact-role { font-size: 0.75rem; color: var(--text-muted); }
.unread-badge {
    background: var(--orange);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
}
</style>

<!-- New Chat Modal -->
<div id="new-chat-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--surface); border-radius: 12px; padding: 1.5rem; width: 400px; max-width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-weight: 700;">New Message</h3>
            <button onclick="document.getElementById('new-chat-modal').style.display='none'" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        <form id="new-chat-form" method="POST" action="<?php echo e(route('messages.send')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">Select Recipient</label>
                <select name="receiver_id" class="form-control" required>
                    <option value="">Choose...</option>
                    <?php
                        $myId = auth()->id();
                        $otherUsers = \App\Models\User::where('id', '!=', $myId)->where('is_active', true)->orderBy('name')->get();
                    ?>
                    <?php $__currentLoopData = $otherUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?> (<?php echo e(ucfirst($u->role)); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="3" placeholder="Type your message..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
var chatMessages = document.getElementById('chat-messages');
if(chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;

document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var formData = new FormData(form);
    var submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch('<?php echo e(route("messages.send")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(function(res) { 
        console.log('Status:', res.status);
        return res.json(); 
    })
    .then(function(data) {
        console.log('Response:', data);
        if(data.ok) {
            var msg = document.createElement('div');
            msg.className = 'msg-row mine';
            msg.innerHTML = '<div class="msg-avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?></div>' +
                '<div><div class="msg-bubble">' + data.message + '</div><div class="msg-time">' + data.created_at + '</div></div>';
            chatMessages.appendChild(msg);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            form.reset();
        } else {
            alert('Error: ' + JSON.stringify(data));
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
    })
    .catch(function(err) {
        console.error('Error:', err);
        alert('Error: ' + err.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
    });
});

setInterval(function() {
    location.reload();
}, 10000);
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\shared\conversation.blade.php ENDPATH**/ ?>