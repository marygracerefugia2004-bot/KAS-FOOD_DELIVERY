@extends('layouts.app')
@section('title','Browse Menu')
@section('sl-foods','active')
@section('content')
<div style="max-width:1200px;margin:0 auto;padding:2rem">
    <div class="page-header">
        <h1><i class="fas fa-search" style="color:var(--orange)"></i> Browse Menu</h1>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom:1.5rem">
        <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end">
            <div style="flex:1;min-width:200px">
                <label class="form-label">Search</label>
                <input name="search" class="form-control" placeholder="Search food..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Category</label>
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <option value="main" {{ request('category')=='main'?'selected':'' }}>Main Course</option>
                    <option value="snack" {{ request('category')=='snack'?'selected':'' }}>Snacks</option>
                    <option value="drink" {{ request('category')=='drink'?'selected':'' }}>Drinks</option>
                    <option value="dessert" {{ request('category')=='dessert'?'selected':'' }}>Dessert</option>
                </select>
            </div>
            <button class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('user.foods') }}" class="btn btn-outline">Clear</a>
        </form>
    </div>

    <div class="food-grid">
        @forelse($foods as $food)
        <div class="food-card fade-in">
            @if($food->image_path)
                @if(str_starts_with($food->image_path, 'http'))
                    <img class="food-img" src="{{ $food->image_path }}" alt="{{ $food->name }}">
                @else
                    <img class="food-img" src="{{ asset('storage/'.$food->image_path) }}" alt="{{ $food->name }}">
                @endif
            @else
                <div class="food-img-placeholder"><i class="fas fa-utensils"></i></div>
            @endif
            <div class="food-body">
                <div class="food-name">{{ $food->name }}</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin:.3rem 0">{{ Str::limit($food->description,60) }}</div>
                <div style="display:flex;align-items:center;gap:.5rem;margin:.4rem 0">
                    <span class="stars">{{ str_repeat('★',$food->avgRating()) }}{{ str_repeat('☆',5-$food->avgRating()) }}</span>
                    <span style="font-size:.75rem;color:var(--text-muted)">({{ $food->ratings_count }})</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <span class="food-price">₱{{ number_format($food->price,2) }}</span>
                    <span style="font-size:.75rem;color:var(--text-muted)"><i class="fas fa-clock"></i> ~{{ $food->prep_time }}min</span>
                </div>
                <div class="food-actions">
                    <a href="{{ route('user.order.create', $food) }}" class="btn btn-success btn-sm" style="flex:1;justify-content:center;text-align:center">
                        <i class="fas fa-bolt"></i> Buy Now
                    </a>
                    <button class="btn btn-primary btn-sm add-to-cart" data-id="{{ $food->id }}" style="flex:1;justify-content:center">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline btn-sm fav-btn" data-id="{{ $food->id }}" title="Favorite">
                        <i class="{{ in_array($food->id, $favIds) ? 'fas' : 'far' }} fa-heart" style="color:var(--orange)"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted)">
            <i class="fas fa-search" style="font-size:2.5rem;margin-bottom:1rem;display:block"></i>
            No foods found.
        </div>
        @endforelse
    </div>
    <div style="margin-top:1.5rem">{{ $foods->appends(request()->all())->links() }}</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart
    document.querySelectorAll('.add-to-cart').forEach(function(button) {
        button.addEventListener('click', async function() {
            var btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            const res = await fetch('{{ route("user.cart.add") }}', {
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
@endpush
@endsection