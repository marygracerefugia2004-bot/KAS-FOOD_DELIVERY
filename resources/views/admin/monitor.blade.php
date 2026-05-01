@extends('layouts.app')
@section('title', 'Live Monitor')
@section('sl-monitor', 'active')
@section('content')

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Live Order Monitor</h1>
            <p>Real-time tracking of active deliveries</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-users"></i></div>
            <div class="stat-val">{{ $activeUsers ?? 0 }}</div>
            <div class="stat-lbl">Active Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-navy"><i class="fas fa-motorcycle"></i></div>
            <div class="stat-val">{{ $activeDrivers ?? 0 }}</div>
            <div class="stat-lbl">Active Drivers</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-green"><i class="fas fa-truck"></i></div>
            <div class="stat-val">{{ $liveOrders->count() ?? 0 }}</div>
            <div class="stat-lbl">Live Orders</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-shipping-fast"></i> Active Deliveries</span>
        </div>
        @forelse($liveOrders ?? [] as $order)
        <div style="border: 1px solid var(--border); border-radius: 8px; margin-bottom: 1rem; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <strong>Order #{{ $order->id }}</strong>
                <span class="badge badge-{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
            </div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                <div><strong>Food:</strong> {{ $order->food->name }}</div>
                <div><strong>Customer:</strong> {{ $order->user->name }}</div>
                @if($order->driver)
                <div><strong>Driver:</strong> {{ $order->driver->name }}</div>
                @endif
                <div><strong>Address:</strong> {{ $order->delivery_address }}</div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem;">
            <i class="fas fa-truck" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
            <p>No active deliveries at the moment.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
// Auto-refresh every 10 seconds
setInterval(() => {
    location.reload();
}, 10000);
</script>

@endsection