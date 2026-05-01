@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('sl-dashboard', 'active')

@section('content')
<div class="page-hdr fade-up">
    <div class="page-hdr-left">
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Dashboard</span>
        </div>
        <h1>Operations Overview</h1>
        <p>Track platform health, orders, and growth in one place.</p>
    </div>
    <div style="display:flex;gap:.55rem;flex-wrap:wrap">
        <a href="{{ route('admin.monitor') }}" class="btn btn-outline">
            <i class="fas fa-satellite-dish"></i> Live Monitor
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
            <i class="fas fa-box"></i> Manage Orders
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card fade-up">
        <div class="stat-ico ico-orange"><i class="fas fa-users"></i></div>
        <div class="stat-val">{{ $stats['users'] }}</div>
        <div class="stat-lbl">Registered Users</div>
    </div>
    <div class="stat-card fade-up-1">
        <div class="stat-ico ico-navy"><i class="fas fa-motorcycle"></i></div>
        <div class="stat-val">{{ $stats['drivers'] }}</div>
        <div class="stat-lbl">Active Drivers</div>
    </div>
    <div class="stat-card fade-up-2">
        <div class="stat-ico ico-navy"><i class="fas fa-box"></i></div>
        <div class="stat-val">{{ $stats['total_orders'] }}</div>
        <div class="stat-lbl">Total Orders</div>
    </div>
    <div class="stat-card fade-up-3">
        <div class="stat-ico ico-red"><i class="fas fa-satellite-dish"></i></div>
        <div class="stat-val" style="color:var(--danger)">{{ $stats['live_orders'] }}</div>
        <div class="stat-lbl">Live Orders</div>
    </div>
    <div class="stat-card fade-up-4">
        <div class="stat-ico ico-orange"><i class="fas fa-clock"></i></div>
        <div class="stat-val" style="color:var(--warning)">{{ $stats['pending_orders'] }}</div>
        <div class="stat-lbl">Pending Orders</div>
    </div>
    <div class="stat-card fade-up-4">
        <div class="stat-ico ico-green"><i class="fas fa-peso-sign"></i></div>
        <div class="stat-val" style="color:var(--success)">₱{{ number_format($stats['revenue'], 0) }}</div>
        <div class="stat-lbl">Revenue</div>
    </div>
</div>

<div class="card fade-up" style="margin-bottom:1rem">
    <div class="card-header" style="margin-bottom:0">
        <span class="card-title">
            <span class="card-icon"><i class="fas fa-bolt"></i></span>
            Quick Actions
        </span>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.foods.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-hamburger"></i> Foods</a>
        <a href="{{ route('admin.users') }}" class="btn btn-outline btn-sm"><i class="fas fa-users"></i> Users</a>
        <a href="{{ route('admin.drivers') }}" class="btn btn-outline btn-sm"><i class="fas fa-motorcycle"></i> Drivers</a>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline btn-sm"><i class="fas fa-chart-line"></i> Reports</a>
    </div>
</div>

<div class="card fade-up">
    <div class="card-header">
        <span class="card-title">
            <span class="card-icon"><i class="fas fa-box"></i></span>
            Recent Orders
        </span>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Food</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td>{{ $order->food->name ?? 'Unavailable Item' }}</td>
                        <td>
                            @if($order->driver)
                                {{ $order->driver->name }}
                            @else
                                <span style="color:var(--muted)">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </td>
                        <td style="font-weight:700;color:var(--orange)">₱{{ number_format($order->total_price, 2) }}</td>
                        <td style="font-size:.8rem;color:var(--muted)">{{ $order->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--muted)">No recent orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection