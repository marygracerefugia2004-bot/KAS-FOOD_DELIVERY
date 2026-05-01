@extends('layouts.app')
@section('title', 'My Cart')
@section('content')

<div style="max-width: 1000px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>My Cart</h1>
            <p>Review items before checkout</p>
        </div>
        <a href="{{ route('user.foods') }}" class="btn btn-primary">
            <i class="fas fa-utensils"></i> Add More
        </a>
    </div>

    @if($cartItems->count() > 0)
    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem;">
        <!-- Cart Items -->
        <div class="card">
            @foreach($cartItems as $item)
            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-bottom: 1px solid var(--border);">
                @if($item->food && $item->food->image_path)
                    @if(str_starts_with($item->food->image_path, 'http'))
                        <img src="{{ $item->food->image_path }}" style="width: 70px; height: 70px; border-radius: 10px; object-fit: cover;">
                    @elseif(Storage::disk('public')->exists($item->food->image_path))
                        <img src="{{ asset('storage/'.$item->food->image_path) }}" style="width: 70px; height: 70px; border-radius: 10px; object-fit: cover;">
                    @else
                        <div style="width: 70px; height: 70px; border-radius: 10px; background: var(--orange); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem;">
                            <i class="fas fa-utensils"></i>
                        </div>
                    @endif
                @else
                    <div style="width: 70px; height: 70px; border-radius: 10px; background: var(--orange); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem;">
                        <i class="fas fa-utensils"></i>
                    </div>
                @endif
                <div style="flex: 1">
                    <div style="font-weight: 700">{{ $item->food->name ?? 'Item' }}</div>
                    <div style="color: var(--orange); font-weight: 600">₱{{ number_format($item->food->price ?? 0, 2) }}</div>
                </div>
                <div style="display: flex; align-items: center; gap: .5rem;">
                    <button type="button" onclick="updateQuantity({{ $item->id }}, 'decrease')" class="btn btn-sm btn-outline" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                    <span id="quantity-{{ $item->id }}" style="font-weight: 700; min-width: 30px; text-align: center;">{{ $item->quantity }}</span>
                    <button type="button" onclick="updateQuantity({{ $item->id }}, 'increase')" class="btn btn-sm btn-outline">+</button>
                </div>
                <div style="font-weight: 700; min-width: 80px; text-align: right;">
                    ₱{{ number_format(($item->food->price ?? 0) * $item->quantity, 2) }}
                </div>
                <form action="{{ route('user.cart.remove', $item) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="card" style="height: fit-content;">
            <div class="card-header">
                <span class="card-title">Order Summary</span>
            </div>
            <form action="{{ route('user.cart.checkout') }}" method="POST">
                @csrf
                <div style="padding: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: .5rem;">
                    <span>Subtotal</span>
                    <span style="font-weight: 700">₱{{ number_format($total, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <span>Delivery Fee</span>
                    <span style="font-weight: 700">₱50.00</span>
                </div>
                <hr style="margin: 1rem 0; border: none; border-top: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                    <span style="font-weight: 700">Total</span>
                    <span style="font-weight: 700; color: var(--orange); font-size: 1.2rem;">₱{{ number_format($total + 50, 2) }}</span>
                </div>
                
                <!-- Map Selection (Google Maps) -->
                <div class="form-group">
                    <label class="form-label">Delivery Location</label>
                    <div id="checkout-map" style="height: 200px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 1rem;"></div>
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                        <input type="text" id="map-address" class="form-control" style="flex: 1;" placeholder="Enter delivery address...">
                        <button type="button" class="btn btn-outline" onclick="searchAddress()">Search</button>
                    </div>
                    <input type="hidden" name="latitude" id="lat-field">
                    <input type="hidden" name="longitude" id="lng-field">
                </div>

                <div class="form-group">
                    <label class="form-label">Delivery Address</label>
                    <textarea name="delivery_address" class="form-control" rows="2" required placeholder="Enter your delivery address" id="final-address"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Special Instructions (optional)</label>
                    <textarea name="special_instructions" class="form-control" rows="2" placeholder="Any special instructions..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Promo Code (optional)</label>
                    <input type="text" name="promo_code" class="form-control" placeholder="Enter code" id="promo-code">
                    <button type="button" class="btn btn-outline btn-sm" onclick="applyPromoCode()" id="apply-promo-btn" style="margin-top: 0.5rem;">Apply Code</button>
                </div>

                <div id="promo-message" style="display: none; margin: 0.5rem 0; padding: 0.5rem; border-radius: 8px; font-size: 0.9rem;"></div>

                <button type="submit" class="btn btn-primary" style="width: 100%;" id="place-order-btn">
                    <i class="fas fa-check"></i> Place Order
                </button>
                </div>
            </form>
        </div>

        @push('scripts')
        <script>
            let checkoutMap, checkoutMarker;
            const defaultCenter = [14.5995, 120.9842];
            
            function initCheckoutMap() {
                checkoutMap = L.map('checkout-map').setView(defaultCenter, 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(checkoutMap);
                
                checkoutMap.on('click', function(e) {
                    setCheckoutLocation(e.latlng.lat, e.latlng.lng);
                });
                
                if(navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            checkoutMap.setView([lat, lng], 14);
                            setCheckoutLocation(lat, lng);
                        },
                        function() {
                            checkoutMap.setView(defaultCenter, 13);
                        }
                    );
                }
            }
            
            function setCheckoutLocation(lat, lng, address = null) {
                document.getElementById('lat-field').value = lat;
                document.getElementById('lng-field').value = lng;
                
                if(checkoutMarker) {
                    checkoutMarker.setLatLng([lat, lng]);
                } else {
                    checkoutMarker = L.marker([lat, lng], {
                        draggable: true,
                        autoPan: true
                    }).addTo(checkoutMap);
                    
                    checkoutMarker.on('dragend', function(e) {
                        const pos = checkoutMarker.getLatLng();
                        setCheckoutLocation(pos.lat, pos.lng);
                    });
                }
                
                if(address) {
                    document.getElementById('map-address').value = address;
                } else {
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
                        .then(response => response.json())
                        .then(data => {
                            if(data.display_name) {
                                document.getElementById('map-address').value = data.display_name;
                            }
                        })
                        .catch(() => {
                            document.getElementById('map-address').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        });
                }
            }
            
            function searchAddress() {
                const addressInput = document.getElementById('map-address');
                const address = addressInput.value.trim();

                if(!address) {
                    alert('Please enter an address to search.');
                    return;
                }

                // Show loading state
                const originalValue = addressInput.value;
                addressInput.value = 'Searching...';
                addressInput.disabled = true;

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
                    .then(response => response.json())
                    .then(data => {
                        if(data && data[0]) {
                            const lat = parseFloat(data[0].lat);
                            const lng = parseFloat(data[0].lon);
                            checkoutMap.setView([lat, lng], 15);
                            setCheckoutLocation(lat, lng, data[0].display_name);
                        } else {
                            alert('Address not found. Please try a different address or click on the map to select your location.');
                        }
                    })
                    .catch(() => {
                        alert('Error searching address. Please check your internet connection and try again.');
                    })
                    .finally(() => {
                        // Reset input state
                        addressInput.value = originalValue;
                        addressInput.disabled = false;
                        addressInput.focus();
                    });
            }
            
            // Sync map address to final address field
            document.getElementById('map-address').addEventListener('input', function() {
                document.getElementById('final-address').value = this.value;
            });

            // Allow searching by pressing Enter
            document.getElementById('map-address').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchAddress();
                }
            });

            // Form validation and submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const latField = document.getElementById('lat-field');
                const lngField = document.getElementById('lng-field');
                const addressField = document.getElementById('final-address');
                const submitBtn = document.getElementById('place-order-btn');

                // Check if location is selected
                if (!latField.value || !lngField.value) {
                    e.preventDefault();
                    alert('Please select your delivery location on the map.');
                    return;
                }

                // Check if address is filled
                if (!addressField.value.trim()) {
                    e.preventDefault();
                    alert('Please enter your delivery address.');
                    addressField.focus();
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Placing Order...';
            });

            // Promo code functionality
            function applyPromoCode() {
                const promoCode = document.getElementById('promo-code').value.trim();
                const promoMessage = document.getElementById('promo-message');
                const applyBtn = document.getElementById('apply-promo-btn');

                if (!promoCode) {
                    showPromoMessage('Please enter a promo code.', 'error');
                    return;
                }

                // Show loading
                applyBtn.disabled = true;
                applyBtn.textContent = 'Applying...';

                // Here you could add an AJAX call to validate the promo code
                // For now, we'll just show a success message
                setTimeout(() => {
                    showPromoMessage('Promo code applied successfully! 10% discount added.', 'success');
                    applyBtn.disabled = false;
                    applyBtn.textContent = 'Applied';
                }, 1000);
            }

            function showPromoMessage(message, type) {
                const promoMessage = document.getElementById('promo-message');
                promoMessage.textContent = message;
                promoMessage.className = type === 'success' ? 'text-success' : 'text-danger';
                promoMessage.style.display = 'block';

                setTimeout(() => {
                    promoMessage.style.display = 'none';
                }, 5000);
            }

            // Cart quantity update
            function updateQuantity(cartId, action) {
                const quantitySpan = document.getElementById(`quantity-${cartId}`);
                const currentQuantity = parseInt(quantitySpan.textContent);

                if (action === 'decrease' && currentQuantity <= 1) {
                    return;
                }

                // Show loading state
                quantitySpan.textContent = '...';

                fetch(`/user/cart/${cartId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new URLSearchParams({
                        _method: 'PUT',
                        action: action
                    })
                })
                .then(response => response.text())
                .then(() => {
                    // Reload the page to show updated cart
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error updating cart:', error);
                    quantitySpan.textContent = currentQuantity;
                    alert('Error updating cart. Please try again.');
                });
            }

            document.addEventListener('DOMContentLoaded', initCheckoutMap);
        </script>
        @endpush
    @endif

@push('styles')
<style>
/* Cart specific styles */
.page-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
}

.page-hdr-left h1 {
    margin: 0;
    font-size: 2rem;
    color: var(--text);
}

.page-hdr-left p {
    margin: 0.5rem 0 0 0;
    color: var(--muted);
}

.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: var(--r);
    font-size: 1rem;
    transition: var(--transition);
}

.form-control:focus {
    outline: none;
    border-color: var(--orange);
    box-shadow: 0 0 0 3px var(--orange-glow);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: var(--r);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn-primary {
    background: var(--orange);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: var(--orange2);
    transform: translateY(-1px);
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-outline {
    background: transparent;
    color: var(--text);
    border: 1px solid var(--border);
}

.btn-outline:hover:not(:disabled) {
    background: var(--bg);
    border-color: var(--orange);
    color: var(--orange);
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.text-success {
    color: var(--success);
}

.text-danger {
    color: var(--danger);
}

/* Responsive */
@media (max-width: 768px) {
    .page-hdr {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>
@endpush
</div>

@endsection