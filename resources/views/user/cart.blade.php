@extends('layouts.app')
@section('title','Shopping Cart')
@section('content')
<div style="max-width:900px;margin:0 auto;padding:2rem">
    <div class="page-header">
        <h1><i class="fas fa-shopping-cart" style="color:var(--orange)"></i> Shopping Cart</h1>
    </div>

    @if($cartItems->isEmpty())
    <div class="card" style="text-align:center;padding:3rem">
        <i class="fas fa-shopping-cart" style="font-size:3rem;color:var(--text-muted);margin-bottom:1rem;display:block"></i>
        <h3 style="font-weight:700">Your cart is empty</h3>
        <p style="color:var(--text-muted)">Add some delicious foods to get started!</p>
        <a href="{{ route('user.foods') }}" class="btn btn-primary" style="margin-top:1rem">Browse Menu</a>
    </div>
    @else
    <div style="display:grid;grid-template-columns:1fr 350px;gap:1.5rem">
        <!-- Cart Items -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Items ({{ $cartItems->sum('quantity') }})</span>
            </div>
            @foreach($cartItems as $item)
            <div style="display:flex;gap:1rem;padding:1rem;border-bottom:1px solid var(--border);align-items:center">
                @if($item->food->image_path)
                    @if(str_starts_with($item->food->image_path, 'http'))
                        <img src="{{ $item->food->image_path }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px">
                    @else
                        <img src="{{ asset('storage/'.$item->food->image_path) }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px">
                    @endif
                @else
                    <div style="width:80px;height:80px;background:var(--bg);border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-utensils" style="color:var(--text-muted)"></i>
                    </div>
                @endif
                <div style="flex:1">
                    <div style="font-weight:700">{{ $item->food->name }}</div>
                    <div style="color:var(--text-muted);font-size:.85rem">{{ $item->food->category }}</div>
                    <div style="color:var(--orange);font-weight:700">₱{{ number_format($item->food->price,2) }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:.5rem">
                    <button class="btn btn-outline btn-sm qty-btn" data-id="{{ $item->id }}" data-action="minus">-</button>
                    <span style="font-weight:700;min-width:30px;text-align:center">{{ $item->quantity }}</span>
                    <button class="btn btn-outline btn-sm qty-btn" data-id="{{ $item->id }}" data-action="plus">+</button>
                </div>
                <div style="font-weight:700;color:var(--orange)">₱{{ number_format($item->food->price * $item->quantity,2) }}</div>
                <form action="{{ route('user.cart.remove', $item) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Checkout Form -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Checkout</span>
            </div>
            <form action="{{ route('user.cart.checkout') }}" method="POST" id="checkout-form">
                @csrf
                <div class="form-group">
                    <label class="form-label">Delivery Address</label>
                    <textarea name="delivery_address" class="form-control" rows="3" placeholder="Enter your delivery address..." required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Promo Code (Optional)</label>
                    <input type="text" name="promo_code" class="form-control" placeholder="Enter promo code">
                </div>
                <div style="border-top:1px solid var(--border);padding-top:1rem;margin-top:1rem">
                    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($total,2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;color:var(--success)">
                        <span>Discount</span>
                        <span>-₱0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:1.2rem;font-weight:700;border-top:1px solid var(--border);padding-top:.5rem;margin-top:.5rem">
                        <span>Total</span>
                        <span style="color:var(--orange)">₱{{ number_format($total,2) }}</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-top:1rem">
                    <i class="fas fa-check"></i> Place Order
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.querySelectorAll('.qty-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var action = this.dataset.action;
        var id = this.dataset.id;
        var currentQty = parseInt(this.parentElement.querySelector('span').textContent);
        var newQty = action === 'plus' ? currentQty + 1 : Math.max(1, currentQty - 1);
        
        fetch('/cart/' + id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: newQty })
        }).then(function() { location.reload(); });
    });
});
</script>
@endpush
@endsection