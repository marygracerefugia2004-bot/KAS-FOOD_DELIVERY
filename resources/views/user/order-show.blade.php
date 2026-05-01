@extends('layouts.app')
@section('title', 'Order #{{ $order->id }}')
@section('content')

<div style="max-width: 900px; margin: 0 auto; padding: 2rem">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="fas fa-box" style="color:var(--orange)"></i> Order #{{ $order->id }}</h1>
        <p>Placed {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
    </div>

    <!-- Status Timeline -->
    <div class="card fade-in" style="margin-bottom: 1.5rem">
        <div class="card-title" style="margin-bottom: 1rem">Delivery Status</div>
        @php
            $steps = ['pending', 'assigned', 'out_for_delivery', 'delivered'];
            $cur = array_search($order->status, $steps);
        @endphp
        <div style="display: flex; align-items: center; margin: 1rem 0">
            @foreach($steps as $i => $step)
                <div style="flex: 1; text-align: center;">
                    <div style="
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background: {{ $i < $cur ? 'var(--orange)' : ($i === $cur ? 'var(--orange)' : 'var(--border)') }};
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    ">
                        @if($i < $cur) <i class="fas fa-check"></i>
                        @elseif($i === $cur) <i class="fas fa-truck"></i>
                        @else <i class="fas fa-clock"></i>
                        @endif
                    </div>
                    <div style="font-size: 0.7rem; margin-top: 0.5rem;">{{ ucfirst(str_replace('_', ' ', $step)) }}</div>
                </div>
                @if(!$loop->last)
                    <div style="flex: 1; height: 2px; background: {{ $i < $cur ? 'var(--orange)' : 'var(--border)' }};"></div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Order Details -->
    <div class="card fade-in">
        <div class="order-items-list" style="margin-bottom: 1rem;">
            @forelse($order->items as $item)
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                    @if($item->food->image_path && Storage::disk('public')->exists($item->food->image_path))
                        <img src="{{ asset('storage/'.$item->food->image_path) }}" style="width: 70px; height: 70px; border-radius: 10px; object-fit: cover;">
                    @else
                        <div style="width: 70px; height: 70px; border-radius: 10px; background: var(--orange); display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-utensils"></i>
                        </div>
                    @endif
                    <div style="flex: 1;">
                        <div style="font-weight: 700; font-size: 1rem;">{{ $item->food->name }}</div>
                        <div style="color: var(--text-muted); font-size: 0.9rem;">Quantity: {{ $item->quantity }}</div>
                        <div style="color: var(--orange); font-weight: 600;">₱{{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                    <i class="fas fa-utensils" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>No items found in this order.</p>
                </div>
            @endforelse
        </div>

        <!-- Order Summary -->
        <div style="background: var(--bg); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Subtotal:</span>
                <span>₱{{ number_format($order->total_price - 50 + ($order->discount ?? 0), 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Delivery Fee:</span>
                <span>₱50.00</span>
            </div>
            @if($order->discount > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--success);">
                <span>Discount:</span>
                <span>-₱{{ number_format($order->discount, 2) }}</span>
            </div>
            @endif
            <hr style="margin: 0.5rem 0; border: none; border-top: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; font-weight: 700;">
                <span>Total:</span>
                <span style="color: var(--orange);">₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
        
        <div style="border-top: 1px solid var(--border); padding-top: 1rem;">
            <div><strong>Delivery Address:</strong> {{ $order->delivery_address }}</div>
            @if($order->driver)
                <div style="margin-top: 0.5rem;"><strong>Driver:</strong> {{ $order->driver->name }}</div>
                <div><strong>Driver Phone:</strong> {{ $order->driver->phone ?? 'N/A' }}</div>
            @endif
            <div style="margin-top: 0.5rem;"><strong>Status:</strong> 
                <span class="badge badge-{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
            </div>
            <div><strong>Placed on:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</div>
        </div>
        
        <div style="margin-top: 1rem;">
            <a href="{{ route('user.orders.pdf', $order) }}" class="btn btn-outline btn-sm">
                <i class="fas fa-file-pdf"></i> Download Receipt
            </a>
        </div>
    </div>

    <!-- Chat Section (if driver assigned) -->
    @if($order->driver_id)
    <div class="card fade-in" style="margin-top: 1.5rem;">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-comments" style="color:var(--orange)"></i> Message Driver</span>
        </div>
        
        <div id="chatMessages" style="height: 300px; overflow-y: auto; padding: 1rem; background: var(--bg); border-radius: 8px; margin-bottom: 1rem;">
            @forelse($orderMessages as $msg)
            <div style="margin-bottom: 0.5rem; text-align: {{ $msg->sender_id == auth()->id() ? 'right' : 'left' }};">
                <div style="display: inline-block; max-width: 70%;">
                    <div style="font-size: 0.7rem; color: var(--text-muted);">{{ $msg->sender->name }}</div>
                    <div style="
                        background: {{ $msg->sender_id == auth()->id() ? 'var(--orange)' : 'white' }};
                        color: {{ $msg->sender_id == auth()->id() ? 'white' : 'var(--text)' }};
                        padding: 0.5rem 1rem;
                        border-radius: 10px;
                        word-wrap: break-word;
                    ">
                        {{ $msg->message }}
                    </div>
                    <div style="font-size: 0.6rem; color: var(--text-muted);">{{ $msg->created_at->format('h:i A') }}</div>
                </div>
            </div>
            @empty
            <div style="text-align:center; color: var(--text-muted); padding: 1rem;">
                No messages yet. Start a chat with your driver.
            </div>
            @endforelse
        </div>
        
        <div style="display: flex; gap: 0.5rem;">
            <input type="text" id="messageInput" class="form-control" placeholder="Type your message...">
            <button onclick="sendMessage()" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Send
            </button>
        </div>
    </div>
    @endif
</div>

<script>
    const driverId = '{{ $order->driver_id ?? null }}';
    const csrf = '{{ csrf_token() }}';
    const mapsKey = '{{ config("services.google_maps.key") }}';
    let map, marker;
    const defaultCenter = [{{ $order->latitude ?? 14.5995 }}, {{ $order->longitude ?? 120.9842 }}];
    
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
                geocodeAddress('{{ $order->delivery_address }}');
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
    
    fetch('{{ route("messages.send") }}', {
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
@if($order->driver_id && !in_array($order->status, ['delivered', 'cancelled']))
setInterval(function() {
    location.reload();
}, 10000);
@endif
</script>

@endsection



