<?php $__env->startSection('title', 'Messages'); ?>
<?php $__env->startSection('sl-messages', 'active'); ?>
<?php $__env->startSection('content'); ?>

<style>
    :root {
        --orange: #FF6B2C;
        --orange2: #FF8B00;
        --bg: #080D1A;
        --surface: #0F172A;
        --border: #1E293B;
        --text: #fff;
        --muted: #94A3B8;
        --r: 12px;
        --r2: 20px;
        --font2: 'Space Grotesk', monospace;
    }

    .messages-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 1rem;
    }

    /* Header */
    .msg-header {
        background: linear-gradient(135deg, var(--surface) 0%, #111827 100%);
        border-radius: var(--r2);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border);
    }
    .msg-header h1 {
        color: var(--text);
        font-family: var(--font2);
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }
    .msg-header p {
        color: var(--muted);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    /* Search bar */
    .search-bar {
        margin-bottom: 1rem;
        position: relative;
    }
    .search-bar i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 0.9rem;
    }
    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 40px;
        color: var(--text);
        font-size: 0.9rem;
        transition: 0.2s;
    }
    .search-input:focus {
        outline: none;
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255,107,44,0.1);
    }

    /* Contact list */
    .contacts-list {
        background: var(--surface);
        border-radius: var(--r2);
        border: 1px solid var(--border);
        overflow: hidden;
    }
    .contact-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        background: transparent;
    }
    .contact-card:last-child {
        border-bottom: none;
    }
    .contact-card:hover {
        background: rgba(255,107,44,0.05);
        transform: translateX(4px);
    }
    .contact-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
        position: relative;
    }
    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background-color: #10b981;
        border: 2px solid var(--surface);
        border-radius: 50%;
    }
    .contact-info {
        flex: 1;
        min-width: 0;
    }
    .contact-name {
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .contact-role {
        font-size: 0.7rem;
        color: var(--muted);
        text-transform: capitalize;
        background: rgba(148,163,184,0.1);
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
    }
    .last-message {
        font-size: 0.8rem;
        color: var(--muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 250px;
    }
    .message-meta {
        text-align: right;
        flex-shrink: 0;
    }
    .timestamp {
        font-size: 0.7rem;
        color: var(--muted);
        white-space: nowrap;
    }
    .unread-badge {
        background: var(--orange);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        margin-top: 0.25rem;
        display: inline-block;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--muted);
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.4;
    }

    /* Buttons */
    .new-msg-btn {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        color: #fff;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 40px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(255,107,44,0.2);
    }
    .new-msg-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255,107,44,0.3);
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: var(--surface);
        border-radius: var(--r2);
        padding: 1.5rem;
        width: 450px;
        max-width: 90%;
        border: 1px solid var(--border);
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        animation: modalFadeIn 0.2s ease;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    .modal-title {
        font-family: var(--font2);
        font-size: 1.25rem;
        font-weight: 700;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--muted);
        transition: 0.2s;
    }
    .modal-close:hover {
        color: var(--orange);
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--muted);
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-select, .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--r);
        color: var(--text);
        font-size: 0.9rem;
        transition: 0.2s;
    }
    .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255,107,44,0.1);
    }
    .send-btn {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        color: #fff;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: var(--r);
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .btn-outline {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text);
        padding: 0.7rem 1.5rem;
        border-radius: var(--r);
        font-weight: 500;
        cursor: pointer;
    }
    .btn-outline:hover {
        border-color: var(--orange);
        color: var(--orange);
    }
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin-right: 0.5rem;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    @media (max-width: 640px) {
        .contact-card {
            flex-wrap: wrap;
        }
        .message-meta {
            margin-left: auto;
        }
        .last-message {
            max-width: 180px;
        }
    }
</style>

<div class="messages-container">
    <div class="msg-header">
        <h1><i class="fas fa-comments" style="margin-right: 0.5rem;"></i> Messages</h1>
        <p>
            <?php if(auth()->user()->role === 'user'): ?>
                Chat with admin and your assigned driver.
            <?php elseif(auth()->user()->role === 'driver'): ?>
                Chat with admin and your assigned customers.
            <?php else: ?>
                Chat with users and drivers.
            <?php endif; ?>
        </p>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <div class="search-bar" style="flex: 1; max-width: 300px;">
            <i class="fas fa-search"></i>
            <input type="text" id="searchContacts" class="search-input" placeholder="Search conversations...">
        </div>
        <button onclick="openModal()" class="new-msg-btn">
            <i class="fas fa-plus"></i> New Message
        </button>
    </div>

    <div class="contacts-list" id="contactsList">
        <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('messages.conversation', $contact->id)); ?>" class="contact-card" data-contact-name="<?php echo e(strtolower($contact->name)); ?>">
                <div class="contact-avatar">
                    <?php echo e(strtoupper(substr($contact->name, 0, 2))); ?>

                    <!-- Example online indicator – you'd need real presence logic -->
                    <span class="online-indicator" style="background-color: <?php echo e($contact->is_online ?? false ? '#10b981' : '#64748b'); ?>"></span>
                </div>
                <div class="contact-info">
                    <div class="contact-name">
                        <?php echo e($contact->name); ?>

                        <span class="contact-role"><?php echo e(ucfirst($contact->role)); ?></span>
                    </div>
                    <div class="last-message">
                        
                        <?php echo e($contact->last_message ?? 'No messages yet'); ?>

                    </div>
                </div>
                <div class="message-meta">
                    <div class="timestamp">
                        <?php echo e($contact->last_message_time ? \Carbon\Carbon::parse($contact->last_message_time)->diffForHumans() : ''); ?>

                    </div>
                    <?php if(($contact->unread_count ?? 0) > 0): ?>
                        <div class="unread-badge"><?php echo e($contact->unread_count); ?></div>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No conversations yet</h3>
                <p>Start a new conversation with another user</p>
            
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- New Message Modal -->
<div id="messageModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-title">New Message</span>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>

        <div class="form-group">
            <label class="form-label">Select Recipient</label>
            <select id="recipient_id" class="form-select">
                <option value="">Choose a user...</option>
                <?php $__currentLoopData = $allowedRecipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e(ucfirst($user->role)); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Message</label>
            <textarea id="message_text" class="form-textarea" rows="4" placeholder="Type your message..."></textarea>
        </div>

        <div class="action-buttons">
            <button id="sendMsgBtn" onclick="sendNewMessage()" class="send-btn">
                <i class="fas fa-paper-plane"></i> Send
            </button>
            <button onclick="closeModal()" class="btn-outline">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    // Search/filter
    const searchInput = document.getElementById('searchContacts');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const cards = document.querySelectorAll('.contact-card');
            cards.forEach(card => {
                const name = card.getAttribute('data-contact-name') || '';
                if (name.includes(filter)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Modal functions
    function openModal() {
        document.getElementById('messageModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('messageModal').style.display = 'none';
        document.body.style.overflow = '';
        document.getElementById('recipient_id').value = '';
        document.getElementById('message_text').value = '';
    }

    async function sendNewMessage() {
        const recipientId = document.getElementById('recipient_id').value;
        const message = document.getElementById('message_text').value.trim();
        const sendBtn = document.getElementById('sendMsgBtn');
        
        if (!recipientId) {
            alert('Please select a recipient');
            return;
        }
        if (!message) {
            alert('Please enter a message');
            return;
        }
        
        // Disable button and show loading
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<span class="loading-spinner"></span> Sending...';
        
        try {
            const response = await fetch('<?php echo e(route("messages.send")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    receiver_id: recipientId,
                    message: message
                })
            });
            
            const data = await response.json();
            
            if (response.ok && (data.success || data.ok)) {
                window.location.href = '/messages/' + recipientId;
            } else {
                alert(data.message || 'Failed to send message');
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Network error. Please try again.');
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
        }
    }

    // Close modal when clicking outside
    document.getElementById('messageModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/messages/index.blade.php ENDPATH**/ ?>