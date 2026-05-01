@extends('layouts.app')
@section('title', 'My Orders')
@section('sl-orders', 'active')
@section('content')

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>My Orders</h1>
            <p>View all your order history and track deliveries</p>
        </div>
        <a href="{{ route('user.foods') }}" class="btn btn-primary">
            <i class="fas fa-utensils"></i> Order More Food
        </a>
    </div>

    <!-- Filter Tabs -->
    <div style="display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap">
        <a href="{{ route('user.orders.history') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline' }}">
            All Orders
        </a>
        <a href="{{ route('user.orders.history', ['status' => 'active']) }}" class="btn btn-sm {{ request('status') == 'active' ? 'btn-primary' : 'btn-outline' }}">
            Active
        </a>
        <a href="{{ route('user.orders.history', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-primary' : 'btn-outline' }}">
            Completed
        </a>
        <a href="{{ route('user.orders.history', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-primary' : 'btn-outline' }}">
            Cancelled
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Food</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.5rem">
                                @if($order->food && $order->food->image_path)
                                    @if(str_starts_with($order->food->image_path, 'http'))
                                        <img src="{{ $order->food->image_path }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                                    @elseif(Storage::disk('public')->exists($order->food->image_path))
                                        <img src="{{ asset('storage/'.$order->food->image_path) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                                    @else
                                        <div style="width:40px;height:40px;border-radius:8px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                    @endif
                                @else
                                    <div style="width:40px;height:40px;border-radius:8px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                @endif
                                <span>{{ $order->food->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>{{ $order->quantity }}</td>
                        <td style="color: var(--orange); font-weight: 700;">₱{{ number_format($order->total_price, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ str_replace('_', ' ', ucfirst($order->status)) }}
                            </span>
                        </td>
                        <td style="font-size: 0.8rem;">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <div style="display:flex;gap:.25rem;flex-wrap:wrap">
                                <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Track
                                </a>
                                @if(in_array($order->status, ['completed', 'delivered']))
                                    <a href="{{ route('user.orders.reorder', $order) }}" class="btn btn-sm btn-outline" title="Reorder">
                                        <i class="fas fa-redo"></i> Reorder
                                    </a>
                                @endif
                                @if(in_array($order->status, ['pending', 'preparing']))
                                    <form action="{{ route('user.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-box-open" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>You haven't placed any orders yet.</p>
                            <a href="{{ route('user.foods') }}" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-utensils"></i> Start Ordering
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 1.5rem;">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</div>

@endsection