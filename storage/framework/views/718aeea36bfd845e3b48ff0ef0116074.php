<?php $__env->startSection('title', 'Order #<?php echo e($order->id); ?>'); ?>
<?php $__env->startSection('content'); ?>

<div style="max-width: 900px; margin: 0 auto; padding: 2rem">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="fas fa-box" style="color:var(--orange)"></i> Order #<?php echo e($order->id); ?></h1>
        <p>Placed <?php echo e($order->created_at->format('M d, Y \a\t h:i A')); ?></p>
    </div>

    <!-- Status Timeline -->
    <div class="card fade-in" style="margin-bottom: 1.5rem">
        <div class="card-title" style="margin-bottom: 1rem">Delivery Status</div>
        <?php
            $steps = ['pending', 'assigned', 'out_for_delivery', 'delivered'];
            $cur = array_search($order->status, $steps);
        ?>
        <div style="display: flex; align-items: center; margin: 1rem 0">
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="flex: 1; text-align: center;">
                    <div style="
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background: <?php echo e($i < $cur ? 'var(--orange)' : ($i === $cur ? 'var(--orange)' : 'var(--border)')); ?>;
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    ">
                        <?php if($i < $cur): ?> <i class="fas fa-check"></i>
                        <?php elseif($i === $cur): ?> <i class="fas fa-truck"></i>
                        <?php else: ?> <i class="fas fa-clock"></i>
                        <?php endif; ?>
                    </div>
                    <div style="font-size: 0.7rem; margin-top: 0.5rem;"><?php echo e(ucfirst(str_replace('_', ' ', $step))); ?></div>
                </div>
                <?php if(!$loop->last): ?>
                    <div style="flex: 1; height: 2px; background: <?php echo e($i < $cur ? 'var(--orange)' : 'var(--border)'); ?>;"></div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Order Details -->
    <div class="card fade-in">
        <div class="order-items-list" style="margin-bottom: 1rem;">
            <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                    <?php if($item->food->image_path && Storage::disk('public')->exists($item->food->image_path)): ?>
                        <img src="<?php echo e(asset('storage/'.$item->food->image_path)); ?>" style="width: 70px; height: 70px; border-radius: 10px; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 70px; height: 70px; border-radius: 10px; background: var(--orange); display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-utensils"></i>
                        </div>
                    <?php endif; ?>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; font-size: 1rem;"><?php echo e($item->food->name); ?></div>
                        <div style="color: var(--text-muted); font-size: 0.9rem;">Quantity: <?php echo e($item->quantity); ?></div>
                        <div style="color: var(--orange); font-weight: 600;">₱<?php echo e(number_format($item->price * $item->quantity, 2)); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                    <i class="fas fa-utensils" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>No items found in this order.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div style="background: var(--bg); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Subtotal:</span>
                <span>₱<?php echo e(number_format($order->total_price - 50 + ($order->discount ?? 0), 2)); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Delivery Fee:</span>
                <span>₱50.00</span>
            </div>
            <?php if($order->discount > 0): ?>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--success);">
                <span>Discount:</span>
                <span>-₱<?php echo e(number_format($order->discount, 2)); ?></span>
            </div>
            <?php endif; ?>
            <hr style="margin: 0.5rem 0; border: none; border-top: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; font-weight: 700;">
                <span>Total:</span>
                <span style="color: var(--orange);">₱<?php echo e(number_format($order->total_price, 2)); ?></span>
            </div>
        </div>
        
        <div style="border-top: 1px solid var(--border); padding-top: 1rem;">
            <div><strong>Delivery Address:</strong> <?php echo e($order->delivery_address); ?></div>
            <?php if($order->driver): ?>
                <div style="margin-top: 0.5rem;"><strong>Driver:</strong> <?php echo e($order->driver->name); ?></div>
                <div><strong>Driver Phone:</strong> <?php echo e($order->driver->phone ?? 'N/A'); ?></div>
            <?php endif; ?>
            <div style="margin-top: 0.5rem;"><strong>Status:</strong> 
                <span class="badge badge-<?php echo e($order->status); ?>"><?php echo e(str_replace('_', ' ', $order->status)); ?></span>
            </div>
            <div><strong>Placed on:</strong> <?php echo e($order->created_at->format('F d, Y h:i A')); ?></div>
        </div>
        
        <div style="margin-top: 1rem;">
            <a href="<?php echo e(route('user.orders.pdf', $order)); ?>" class="btn btn-outline btn-sm">
                <i class="fas fa-file-pdf"></i> Download Receipt
            </a>
        </div>
    </div>

    <!-- Chat Section (if driver assigned) -->
    <?php if($order->driver_id): ?>
    <div class="card fade-in" style="margin-top: 1.5rem;">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-comments" style="color:var(--orange)"></i> Message Driver</span>
        </div>
        
        <div id="chatMessages" style="height: 300px; overflow-y: auto; padding: 1rem; background: var(--bg); border-radius: 8px; margin-bottom: 1rem;">
            <?php $__empty_1 = true; $__currentLoopData = $orderMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="margin-bottom: 0.5rem; text-align: <?php echo e($msg->sender_id == auth()->id() ? 'right' : 'left'); ?>;">
                <div style="display: inline-block; max-width: 70%;">
                    <div style="font-size: 0.7rem; color: var(--text-muted);"><?php echo e($msg->sender->name); ?></div>
                    <div style="
                        background: <?php echo e($msg->sender_id == auth()->id() ? 'var(--orange)' : 'white'); ?>;
                        color: <?php echo e($msg->sender_id == auth()->id() ? 'white' : 'var(--text)'); ?>;
                        padding: 0.5rem 1rem;
                        border-radius: 10px;
                        word-wrap: break-word;
                    ">
                        <?php echo e($msg->message); ?>

                    </div>
                    <div style="font-size: 0.6rem; color: var(--text-muted);"><?php echo e($msg->created_at->format('h:i A')); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center; color: var(--text-muted); padding: 1rem;">
                No messages yet. Start a chat with your driver.
            </div>
            <?php endif; ?>
        </div>
        
        <div style="display: flex; gap: 0.5rem;">
            <input type="text" id="messageInput" class="form-control" placeholder="Type your message...">
            <button onclick="sendMessage()" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Send
            </button>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    const driverId = '<?php echo e($order->driver_id ?? null); ?>';
    const csrf = '<?php echo e(csrf_token()); ?>';
    const mapsKey = '<?php echo e(config("services.google_maps.key")); ?>';
    let map, marker;
    const defaultCenter = [<?php echo e($order->latitude ?? 14.5995); ?>, <?php echo e($order->longitude ?? 120.9842); ?>];
    
    function initMap() {
        map = L.map('order-map').setView(defaultCenter, 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        marker = L.marker(defaultCenter, {
            draggable: true,
            autoPan: true
        }).addTo(map);
        
        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            document.getElementById('lat').value = pos.lat;
            document.getElementById('lng').value = pos.lng;
            updateAddress(pos.lat, pos.lng);
        });
        
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 14);
                    marker.setLatLng([lat, lng]);
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                },
                function() {
                    map.setView(defaultCenter, 13);
                    document.getElementById('lat').value = defaultCenter[0];
                    document.getElementById('lng').value = defaultCenter[1];
                }
            );
        } else {
            document.getElementById('lat').value = defaultCenter[0];
            document.getElementById('lng').value = defaultCenter[1];
        }
        
        if(mapsKey) {
                geocodeAddress('<?php echo e($order->delivery_address); ?>');
        }
    }
    
    function geocodeAddress(address) {
        if(!mapsKey || !address) return;
        fetch('https://maps.googleapis.com/maps/api/geocode/json?address=' + encodeURIComponent(address) + '&key=' + mapsKey)
            .then(response => response.json())
            .then(data => {
                if(data.results && data.results[0]) {
                    const loc = data.results[0].geometry.location;
                    map.setView([loc.lat, loc.lng], 15);
                    marker.setLatLng([loc.lat, loc.lng]);
                }
            })
            .catch(() => {});
    }
    
    function updateAddress(lat, lng) {
        if(!mapsKey) return;
        fetch('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + lng + '&key=' + mapsKey)
            .then(response => response.json())
            .then(data => {
                if(data.results && data.results[0]) {
                    document.getElementById('final-address').value = data.results[0].formatted_address;
                }
            })
            .catch(() => {});
    }
    
    function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if(!message || !driverId) return;
    
    fetch('<?php echo e(route("messages.send")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
            receiver_id: driverId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success || data.ok) {
            location.reload();
        } else {
            alert('Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending message');
    });
    
    input.value = '';
}

// Send on Enter key
const messageInput = document.getElementById('messageInput');
if(messageInput) {
    messageInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            sendMessage();
        }
    });
}

// Scroll chat to bottom
const chatMessages = document.getElementById('chatMessages');
if(chatMessages) {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Auto-refresh every 10 seconds
<?php if($order->driver_id && !in_array($order->status, ['delivered', 'cancelled'])): ?>
setInterval(function() {
    location.reload();
}, 10000);
<?php endif; ?>
</script>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\user\order-show.blade.php ENDPATH**/ ?>