<?php $__env->startSection('title', 'Chat with ' . $contact->name); ?>
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

    /* Chat container */
    .chat-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 1rem;
    }

    /* Header */
    .chat-header {
        background: linear-gradient(135deg, var(--surface) 0%, #111827 100%);
        border: 1px solid var(--border);
        border-radius: var(--r2);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .chat-back-btn {
        background: rgba(255,255,255,0.08);
        border: none;
        color: var(--text);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .chat-back-btn:hover {
        background: rgba(255,107,44,0.2);
        color: var(--orange);
    }
    .chat-avatar {
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
        position: relative;
        flex-shrink: 0;
    }
    .online-indicator {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 12px;
        height: 12px;
        background-color: #10b981;
        border: 2px solid var(--surface);
        border-radius: 50%;
    }
    .chat-user-info h2 {
        color: var(--text);
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
    }
    .chat-user-info p {
        color: var(--muted);
        font-size: 0.75rem;
        margin: 0;
        text-transform: capitalize;
    }
    .typing-indicator {
        font-size: 0.7rem;
        color: var(--orange);
        margin-top: 0.2rem;
        display: none;
    }

    /* Messages container */
    .messages-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r2);
        height: calc(100vh - 280px);
        min-height: 450px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .messages-list {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        scroll-behavior: smooth;
    }
    /* Message row */
    .message-row {
        display: flex;
        gap: 0.75rem;
        max-width: 80%;
        animation: fadeInUp 0.2s ease;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .message-row.mine {
        margin-left: auto;
        flex-direction: row-reverse;
    }
    .message-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
        align-self: flex-end;
    }
    .message-bubble {
        padding: 0.75rem 1rem;
        border-radius: 18px;
        font-size: 0.9rem;
        line-height: 1.45;
        word-wrap: break-word;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .mine .message-bubble {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    .theirs .message-bubble {
        background: var(--bg);
        color: var(--text);
        border-bottom-left-radius: 4px;
        border: 1px solid var(--border);
    }
    .message-time {
        font-size: 0.65rem;
        color: var(--muted);
        margin-top: 0.25rem;
        text-align: right;
        letter-spacing: 0.3px;
    }
    .mine .message-time {
        text-align: left;
    }
    /* Input area */
    .message-input-area {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 0.75rem;
        background: var(--surface);
    }
    .message-input {
        flex: 1;
        padding: 0.8rem 1rem;
        border: 1px solid var(--border);
        border-radius: 40px;
        background: var(--bg);
        color: var(--text);
        font-size: 0.9rem;
        font-family: inherit;
        transition: 0.2s;
    }
    .message-input:focus {
        outline: none;
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255,107,44,0.1);
    }
    .send-btn {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        color: #fff;
        border: none;
        padding: 0 1.5rem;
        border-radius: 40px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .send-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255,107,44,0.3);
    }
    .send-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .empty-chat {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        text-align: center;
        padding: 2rem;
    }
    .empty-chat i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.4;
    }
    /* Scrollbar */
    .messages-list::-webkit-scrollbar {
        width: 6px;
    }
    .messages-list::-webkit-scrollbar-track {
        background: var(--bg);
        border-radius: 4px;
    }
    .messages-list::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 4px;
    }
    @media (max-width: 640px) {
        .message-row {
            max-width: 90%;
        }
        .chat-header {
            padding: 0.8rem 1rem;
        }
        .chat-avatar {
            width: 44px;
            height: 44px;
        }
        .message-input-area {
            padding: 0.8rem;
        }
    }
</style>

<div class="chat-wrapper">
    <div class="chat-header">
        <a href="<?php echo e(route('messages.index')); ?>" class="chat-back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="chat-avatar">
            <?php echo e(strtoupper(substr($contact->name, 0, 2))); ?>

            <span class="online-indicator" style="background-color: <?php echo e($contact->is_online ?? false ? '#10b981' : '#64748b'); ?>"></span>
        </div>
        <div class="chat-user-info">
            <h2><?php echo e($contact->name); ?></h2>
            <p><?php echo e(ucfirst($contact->role)); ?></p>
            <div class="typing-indicator" id="typingIndicator">
                <i class="fas fa-ellipsis-h"></i> Typing...
            </div>
        </div>
    </div>

    <div class="messages-container">
        <div class="messages-list" id="messagesContainer">
            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="message-row <?php echo e($message->sender_id == auth()->id() ? 'mine' : 'theirs'); ?>" data-message-id="<?php echo e($message->id); ?>">
                    <div class="message-avatar">
                        <?php echo e(strtoupper(substr($message->sender->name, 0, 2))); ?>

                    </div>
                    <div>
                        <div class="message-bubble"><?php echo e($message->message); ?></div>
                        <div class="message-time"><?php echo e($message->created_at->format('M d, h:i A')); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-chat">
                    <div>
                        <i class="fas fa-comment-dots"></i>
                        <p>No messages yet.<br>Start the conversation!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <form id="messageForm" class="message-input-area">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="receiver_id" value="<?php echo e($contact->id); ?>">
            <input type="text" id="messageInput" name="message" class="message-input" placeholder="Type your message..." required autocomplete="off">
            <button type="submit" class="send-btn" id="sendBtn">
                <i class="fas fa-paper-plane"></i> Send
            </button>
        </form>
    </div>
</div>

<script>
    // Scroll to bottom on load
    const container = document.getElementById('messagesContainer');
    function scrollToBottom() {
        container.scrollTop = container.scrollHeight;
    }
    scrollToBottom();

    // Send message via AJAX (no page reload)
    const form = document.getElementById('messageForm');
    const sendBtn = document.getElementById('sendBtn');
    const messageInput = document.getElementById('messageInput');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Disable send button and show loading
        sendBtn.disabled = true;
        const originalBtnHtml = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch('<?php echo e(route("messages.send")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok && (data.ok || data.success)) {
                // Clear input
                messageInput.value = '';
                // Refresh messages (polling will update, but we can also append optimistically)
                fetchMessages();
            } else {
                alert('Error: ' + (data.message || 'Failed to send message'));
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalBtnHtml;
            }
        } catch (error) {
            console.error('Send error:', error);
            alert('Network error. Please try again.');
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalBtnHtml;
        }
    });
    
    // Optional: send on Ctrl+Enter or Cmd+Enter
    messageInput.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            form.dispatchEvent(new Event('submit'));
        }
    });
    
    // Fetch latest messages (polling)
    let lastMessageCount = <?php echo e(count($messages)); ?>;
    
    async function fetchMessages() {
        try {
            const response = await fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newMessagesContainer = doc.getElementById('messagesContainer');
            
            if (newMessagesContainer) {
                const oldHtml = container.innerHTML;
                const newHtml = newMessagesContainer.innerHTML;
                if (oldHtml !== newHtml) {
                    container.innerHTML = newHtml;
                    scrollToBottom();
                }
            }
        } catch (err) {
            // silent fail
        }
    }
    
    // Poll every 5 seconds
    setInterval(fetchMessages, 5000);
    
    // Simulate typing indicator (optional – requires backend integration)
    let typingTimeout;
    messageInput.addEventListener('input', function() {
        // You could send an AJAX request to a "typing" endpoint here
        // For now, just UI demo
        const indicator = document.getElementById('typingIndicator');
        indicator.style.display = 'block';
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            indicator.style.display = 'none';
        }, 1000);
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\messages\conversation.blade.php ENDPATH**/ ?>