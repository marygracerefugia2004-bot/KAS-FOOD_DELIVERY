<?php $__env->startSection('title','Place Order'); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startPush('styles'); ?>
<style>
    #place-map {
        width: 100%;
        height: 300px;
        border-radius: var(--radius);
        border: 1px solid var(--border);
    }
    .leaflet-control-geocoder-form {
        border-radius: 8px !important;
    }
    .leaflet-control-geocoder-form input {
        padding: 6px 10px !important;
        font-size: 14px !important;
    }
</style>
<?php $__env->stopPush(); ?>

<div style="max-width:680px;margin:0 auto;padding:2rem">
    <div class="page-header">
        <h1><i class="fas fa-shopping-cart" style="color:var(--orange)"></i> Place Order</h1>
    </div>
    
    <!-- Food summary -->
    <div class="card" style="margin-bottom:1.5rem;display:flex;gap:1rem;align-items:center">
        <?php if($food->image_path): ?>
            <?php if(str_starts_with($food->image_path, 'http')): ?>
                <img src="<?php echo e($food->image_path); ?>" style="width:80px;height:80px;border-radius:10px;object-fit:cover">
            <?php else: ?>
                <img src="<?php echo e(asset('storage/'.$food->image_path)); ?>" style="width:80px;height:80px;border-radius:10px;object-fit:cover">
            <?php endif; ?>
        <?php endif; ?>
        <div>
            <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1.1rem"><?php echo e($food->name); ?></div>
            <div style="color:var(--orange);font-size:1.2rem;font-weight:800">₱<?php echo e(number_format($food->price,2)); ?></div>
            <div style="font-size:.8rem;color:var(--text-muted)"><i class="fas fa-clock"></i> ~<?php echo e($food->prep_time + 20); ?> min estimated delivery</div>
        </div>
    </div>
    
    <div class="card fade-in">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo e($errors->first()); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo e(route('user.order.store')); ?>" id="order-form">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="food_id" value="<?php echo e($food->id); ?>">
            <input type="hidden" name="latitude" id="lat-field" value="14.5995">
            <input type="hidden" name="longitude" id="lng-field" value="120.9842">

            <div class="form-group">
                <label class="form-label">Delivery Address</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" name="delivery_address" id="address-input" class="form-control" 
                        placeholder="Click map or type address..." value="<?php echo e(old('delivery_address')); ?>" required style="flex: 1;">
                    <button type="button" class="btn btn-outline" onclick="searchAddress()" style="white-space: nowrap;">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
            
            <!-- Map -->
            <div class="form-group">
                <label class="form-label">Pick Location on Map</label>
                <div id="place-map"></div>
                <p style="font-size:.78rem;color:var(--text-muted);margin-top:.4rem">
                    <i class="fas fa-info-circle"></i> Click anywhere on the map to set your delivery point
                </p>
            </div>
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" min="1" max="20" value="<?php echo e(old('quantity',1)); ?>" required id="qty">
                </div>
                <div class="form-group">
                    <label class="form-label">Promo Code (optional)</label>
                    <input type="text" name="promo_code" class="form-control" placeholder="e.g. SAVE20" value="<?php echo e(old('promo_code')); ?>" style="text-transform: uppercase">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Special Instructions</label>
                <textarea name="special_instructions" class="form-control" rows="2" placeholder="Allergies, special requests..."><?php echo e(old('special_instructions')); ?></textarea>
            </div>
            
            <!-- Live Total -->
            <div style="background:var(--navy);border-radius:10px;padding:1rem;margin-bottom:1.25rem;display:flex;justify-content:space-between;align-items:center">
                <span style="color:rgba(255,255,255,.7);font-weight:700">Estimated Total</span>
                <span style="color:var(--orange);font-size:1.3rem;font-weight:800" id="total-display">₱<?php echo e(number_format($food->price,2)); ?></span>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.8rem" id="submit-btn">
                <i class="fas fa-check-circle"></i> Confirm Order
            </button>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<!-- Leaflet CSS & JS (FREE - No API Key Required) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet Geocoder for address search -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
const unitPrice = <?php echo e($food->price); ?>;
let map, marker, geocoder;

// Initialize map
function initOrderMap() {
    const defaultCenter = [14.5995, 120.9842];
    
    map = L.map('place-map').setView(defaultCenter, 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        placeholder: 'Search for an address...',
        errorMessage: 'Address not found',
        position: 'topright'
    }).on('markgeocode', function(e) {
        const center = e.geocode.center;
        map.setView(center, 15);
        setLocation(center.lat, center.lng, e.geocode.name);
    }).addTo(map);
    
    map.on('click', function(e) {
        setLocation(e.latlng.lat, e.latlng.lng);
    });
    
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 14);
                setLocation(lat, lng);
            },
            function(error) {
                setLocation(defaultCenter[0], defaultCenter[1]);
            }
        );
    } else {
        setLocation(defaultCenter[0], defaultCenter[1]);
    }
}

function setLocation(lat, lng, address = null) {
    document.getElementById('lat-field').value = lat;
    document.getElementById('lng-field').value = lng;
    
    if(marker) {
        marker.setLatLng([lat, lng]);
    } else {
        marker = L.marker([lat, lng], {
            draggable: true,
            autoPan: true
        }).addTo(map);
        
        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            setLocation(pos.lat, pos.lng);
        });
    }
    
    if(address) {
        document.getElementById('address-input').value = address;
    } else {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if(data.display_name) {
                    document.getElementById('address-input').value = data.display_name;
                } else {
                    document.getElementById('address-input').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                }
            })
            .catch(() => {
                document.getElementById('address-input').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            });
    }
}

function searchAddress() {
    const address = prompt('Enter your delivery address:');
    if(address) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
            .then(response => response.json())
            .then(data => {
                if(data && data[0]) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    map.setView([lat, lng], 15);
                    setLocation(lat, lng, data[0].display_name);
                } else {
                    alert('Address not found. Please try a different address or click on the map.');
                }
            })
            .catch(() => {
                alert('Error searching address. Please click on the map to select your location.');
            });
    }
}

document.getElementById('qty').addEventListener('input', function() {
    const total = unitPrice * this.value;
    document.getElementById('total-display').textContent = '₱' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
});

let submitted = false;

document.getElementById('order-form').addEventListener('submit', function(e) {
    if(!document.getElementById('lat-field').value) {
        e.preventDefault();
        alert('Please select your delivery location on the map.');
        return false;
    }
    if(submitted) return false;
    submitted = true;
    document.getElementById('submit-btn').disabled = true;
    document.getElementById('submit-btn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Placing order...';
});

document.addEventListener('DOMContentLoaded', initOrderMap);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/user/order-create.blade.php ENDPATH**/ ?>