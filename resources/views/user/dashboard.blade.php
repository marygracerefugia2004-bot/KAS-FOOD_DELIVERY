@extends('layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'Dashboard')
@section('sl-dashboard', 'active')

@section('content')
<div class="dashboard-container">
    <div class="page-hdr fade-in">
        <div class="page-hdr-left">
            <div class="page-breadcrumb">
                <a href="{{ route('user.dashboard') }}">User</a>
                <span>/</span>
                <span>Dashboard</span>
            </div>
            <h1>Food Dashboard</h1>
            <p>Track orders, favorites, and updates in one place.</p>
        </div>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap">
            <a href="{{ route('user.orders.history') }}" class="btn btn-outline">
                <i class="fas fa-history"></i> Order History
            </a>
            <a href="{{ route('user.foods') }}" class="btn btn-primary">
                <i class="fas fa-utensils"></i> Browse Menu
            </a>
        </div>
    </div>

    {{-- Welcome Banner --}}
    <div class="welcome-banner fade-in">
        <div class="welcome-banner__decoration welcome-banner__decoration--1"></div>
        <div class="welcome-banner__decoration welcome-banner__decoration--2"></div>
        
        <h2 class="welcome-banner__greeting">
            @php
                $greeting = $greeting ?? 'there';
            @endphp
            Good {{ $greeting }},
            @auth {{ auth()->user()->first_name ?? explode(' ', trim(auth()->user()->name))[0] }} @else Guest @endauth ! 👋
        </h2>
        <p class="welcome-banner__subtitle">What would you like to eat today?</p>
        <a href="{{ route('user.foods') }}" class="btn btn-primary">
            <i class="fas fa-utensils"></i> Order Now
        </a>
    </div>

    {{-- Quick Actions --}}
    <div class="stats-grid">
        <x-stat-card :href="route('user.cart')" icon="fas fa-shopping-cart" :value="$cartCount" label="My Cart" delay="0" />
        <x-stat-card :href="route('user.favorites')" icon="fas fa-heart" :value="$favoritesCount" label="Favorites" delay="0.1" />
        <x-stat-card :href="route('user.orders.history')" icon="fas fa-history" :value="$totalOrdersCount" label="Order History" delay="0.2" />
        <x-stat-card :href="route('user.profile')" icon="fas fa-user-cog" value="" label="Settings" :iconOnly="true" delay="0.3" />
    </div>

    {{-- Key Statistics --}}
    <div class="stats-overview">
        <div class="stat-highlight" style="animation-delay: 0.1s">
            <div class="stat-highlight__icon">
                <i class="fas fa-peso-sign"></i>
            </div>
            <div class="stat-highlight__content">
                <div class="stat-highlight__value">₱{{ number_format($totalSpent, 2) }}</div>
                <div class="stat-highlight__label">Total Spent</div>
            </div>
        </div>
        <div class="stat-highlight" style="animation-delay: 0.2s">
            <div class="stat-highlight__icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-highlight__content">
                <div class="stat-highlight__value">{{ $totalOrdersCount }}</div>
                <div class="stat-highlight__label">Total Orders</div>
            </div>
        </div>
        <div class="stat-highlight" style="animation-delay: 0.3s">
            <div class="stat-highlight__icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-highlight__content">
                <div class="stat-highlight__value">{{ $unreadNotifications }}</div>
                <div class="stat-highlight__label">Notifications</div>
            </div>
        </div>
        <div class="stat-highlight" style="animation-delay: 0.4s">
            <div class="stat-highlight__icon">
                <i class="fas fa-truck"></i>
            </div>
            <div class="stat-highlight__content">
                <div class="stat-highlight__value">{{ $activeOrdersCount }}</div>
                <div class="stat-highlight__label">Active Orders</div>
            </div>
        </div>
    </div>

    @auth
        {{-- Active Order Tracking --}}
        @if($activeOrder)
        <div class="card fade-in active-order-card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-truck-moving" style="color: var(--orange)"></i> Active Order</span>
                <a href="{{ route('user.orders.show', $activeOrder) }}" class="btn btn-sm btn-primary">Track Order</a>
            </div>
            <div class="active-order-card__content">
                <div class="active-order-card__item">
                    <x-food-image :food="$activeOrder->food" size="60" />
                    <div class="active-order-card__details">
                        <div class="active-order-card__name">{{ $activeOrder->food->name ?? 'Food Item' }}</div>
                        <div class="active-order-card__meta">
                            Order #{{ $activeOrder->id }} · Qty: {{ $activeOrder->quantity }} · 
                            <span class="badge badge-{{ $activeOrder->status }}">
                                {{ str_replace('_', ' ', ucfirst($activeOrder->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($activeOrder->driver && in_array($activeOrder->status, ['assigned', 'out_for_delivery']))
                <div class="driver-info">
                    <div class="driver-info__icon"><i class="fas fa-motorcycle"></i></div>
                    <div class="driver-info__text">
                        <div class="driver-info__label">Your Driver</div>
                        <div class="driver-info__name">{{ $activeOrder->driver->name }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    @endauth

    {{-- Main Content Grid --}}
    <div class="dashboard-grid">
        {{-- Recent Orders --}}
        <div class="card fade-in">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-box" style="color: var(--orange)"></i> Recent Orders</span>
                <a href="{{ route('user.orders.history') }}" class="btn btn-sm btn-outline">View All</a>
            </div>

            @auth
                @forelse($recentOrders as $order)
                <div class="order-item">
                    <x-food-image :food="$order->food" size="52" />
                    <div class="order-item__details">
                        <div class="order-item__name">{{ $order->food->name ?? 'Food Item' }}</div>
                        <div class="order-item__meta">
                            {{ $order->created_at->diffForHumans() }} · Qty: {{ $order->quantity }}
                        </div>
                    </div>
                    <span class="badge badge-{{ $order->status }}">
                        {{ str_replace('_', ' ', ucfirst($order->status)) }}
                    </span>
                    <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-navy">Track</a>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No orders yet. <a href="{{ route('user.foods') }}">Order now!</a></p>
                </div>
                @endforelse
            @else
                <div class="empty-state">
                    <i class="fas fa-lock"></i>
                    <p>Please <a href="{{ route('login') }}">login</a> to view your orders.</p>
                </div>
            @endauth
        </div>

        {{-- Favorites Preview --}}
        @auth
            @if($favorites->isNotEmpty())
            <div class="card fade-in">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-heart" style="color: var(--orange)"></i> My Favorites</span>
                    <a href="{{ route('user.favorites') }}" class="btn btn-sm btn-outline">View All</a>
                </div>
                <div class="favorites-grid">
                    @foreach($favorites as $favorite)
                        @if($favorite->food)
                        <a href="{{ route('user.foods.show', $favorite->food) }}" class="favorite-card">
                            <x-food-image :food="$favorite->food" size="100" class="favorite-card__image" />
                            <div class="favorite-card__info">
                                <div class="favorite-card__name">{{ $favorite->food->name }}</div>
                                <div class="favorite-card__price">₱{{ number_format($favorite->food->price, 2) }}</div>
                            </div>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        @endauth
    </div>

    {{-- Help & Support --}}
    <div class="card fade-in">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-headset" style="color: var(--orange)"></i> Help & Support</span>
        </div>
        <div class="support-grid">
            <a href="{{ route('support') }}" class="support-card">
                <div class="support-card__icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="support-card__title">Contact Support</div>
                    <div class="support-card__desc">Get help with your orders</div>
                </div>
            </a>
            <a href="{{ route('user.profile') }}" class="support-card">
                <div class="support-card__icon"><i class="fas fa-question-circle"></i></div>
                <div>
                    <div class="support-card__title">FAQ</div>
                    <div class="support-card__desc">Frequently asked questions</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

{{-- Optional: Inline Styles (can be moved to main CSS) --}}
@push('styles')
<style>
  /* ── TYPOGRAPHY ─────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap');

:root {
    --orange: #ff6b2c;
    --orange-light: rgba(255, 107, 44, 0.1);
    --navy: #0d1b3e;
    --navy-mid: #1a2f6e;
    --border: rgba(0, 0, 0, 0.07);
    --text-muted: #8892a4;
    --radius: 10px;
    --radius-lg: 16px;
    --transition: all 0.2s ease;
}

/* ── LAYOUT ─────────────────────────────── */
.dashboard-container {
    padding: 2rem;
    max-width: 1140px;
    margin: 0 auto;
}

.page-hdr {
    background: var(--surface);
    border: 1px solid rgba(13, 27, 62, 0.08);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.4rem;
    margin-bottom: 1rem;
}

/* ── WELCOME BANNER ─────────────────────── */
.welcome-banner {
    background: linear-gradient(130deg, var(--navy) 0%, var(--navy-mid) 55%, var(--navy) 100%);
    border-radius: var(--radius-lg);
    padding: 2rem 2.25rem;
    margin-bottom: 1.25rem;
    position: relative;
    overflow: hidden;
}

.welcome-banner__decoration--1 {
    position: absolute;
    right: -24px; top: -24px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,107,44,0.35), transparent 70%);
}

.welcome-banner__decoration--2 {
    position: absolute;
    right: 110px; bottom: -50px;
    width: 140px; height: 140px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,107,44,0.18), transparent 70%);
}

.welcome-banner__tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,107,44,0.18);
    border: 1px solid rgba(255,107,44,0.35);
    color: #ff9a6b;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    margin-bottom: 10px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.welcome-banner__greeting {
    font-family: 'Sora', sans-serif;
    font-size: 1.65rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    margin-bottom: 6px;
}

.welcome-banner__subtitle {
    color: rgba(255,255,255,0.6);
    font-size: 0.88rem;
    margin-bottom: 1.25rem;
}

/* ── STAT CARDS ─────────────────────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 1rem;
}

/* ── STATISTICS OVERVIEW ─────────────────── */
.stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 1.2rem;
}

.stat-highlight {
    background: var(--surface);
    border: 0.5px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: var(--transition);
    animation: fadeInUp 0.4s ease forwards;
    opacity: 0;
}

.stat-highlight:hover {
    transform: translateY(-2px);
    border-color: var(--orange);
    box-shadow: 0 4px 16px rgba(255, 107, 44, 0.08);
}

.stat-highlight__icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--orange-light), rgba(255,107,44,0.2));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--orange);
    flex-shrink: 0;
}

.stat-highlight__content {
    flex: 1;
}

.stat-highlight__value {
    font-family: 'Sora', sans-serif;
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--navy);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-highlight__label {
    font-size: 0.85rem;
    color: var(--text-muted);
    font-weight: 500;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--surface);
    padding: 1rem 1.1rem;
    border-radius: var(--radius-lg);
    border: 1px solid rgba(13, 27, 62, 0.08);
    text-decoration: none;
    color: inherit;
    transition: var(--transition);
    animation: fadeInUp 0.4s ease forwards;
    opacity: 0;
}

.stat-card:hover {
    transform: translateY(-3px);
    border-color: var(--orange);
    box-shadow: 0 6px 20px rgba(255, 107, 44, 0.12);
}

.stat-card__icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    background: var(--orange-light);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    color: var(--orange);
    flex-shrink: 0;
}

.stat-card__value {
    font-family: 'Sora', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--navy);
    line-height: 1;
}

.stat-card__label {
    font-size: 0.74rem;
    color: var(--text-muted);
    font-weight: 500;
    margin-top: 3px;
}

/* ── CARDS ──────────────────────────────── */
.card {
    background: var(--surface);
    border: 1px solid rgba(13, 27, 62, 0.08);
    border-radius: var(--radius-lg);
    margin-bottom: 1rem;
    overflow: hidden;
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(13, 27, 62, 0.06);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    color: var(--navy);
    font-size: 0.95rem;
}

/* ── ACTIVE ORDER CARD ──────────────────── */
.active-order-card {
    border-left: 4px solid var(--orange);
    background: linear-gradient(to right, rgba(255,107,44,0.03), transparent);
}

/* progress tracker */
.order-progress {
    display: flex;
    align-items: flex-start;
    padding: 1.25rem 1.25rem 0.5rem;
    position: relative;
}

.prog-step {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.prog-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 13px;
    left: calc(50% + 14px);
    right: calc(-50% + 14px);
    height: 2px;
    background: #f0f2f5;
}

.prog-step.done::after { background: var(--orange); }

.prog-dot {
    width: 28px; height: 28px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
}

.prog-dot--done  { background: var(--orange); color: #fff; }
.prog-dot--active {
    background: #fff;
    border: 2px solid var(--orange);
    color: var(--orange);
    box-shadow: 0 0 0 4px rgba(255,107,44,0.15);
}
.prog-dot--todo  { background: #f0f2f5; color: var(--text-muted); }

.prog-label {
    font-size: 0.65rem;
    color: var(--text-muted);
    margin-top: 6px;
    font-weight: 500;
    text-align: center;
}
.prog-label--active { color: var(--orange); font-weight: 700; }

/* ── DRIVER INFO ─────────────────────────── */
.driver-info {
    margin: 0.5rem 1.25rem 1rem;
    padding: 0.85rem 1rem;
    background: #f8f9fc;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    gap: 12px;
}

.driver-info__avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.driver-info__label { font-size: 0.72rem; color: var(--text-muted); }
.driver-info__name  { font-weight: 700; font-size: 0.85rem; color: var(--navy); }

.driver-info__rating {
    background: #fff8ee;
    color: #b86e00;
    border: 1px solid #ffd580;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    margin-left: auto;
}

/* ── ORDER ITEMS ─────────────────────────── */
.order-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid rgba(13, 27, 62, 0.06);
}
.order-item:last-child { border-bottom: none; }

.order-item__thumb {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ffe0cc, #ffc1a0);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.order-item__name { font-weight: 600; font-size: 0.88rem; color: var(--navy); }
.order-item__meta { font-size: 0.73rem; color: var(--text-muted); margin-top: 2px; }

/* ── BADGES ──────────────────────────────── */
.badge {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    white-space: nowrap;
}

.badge-pending    { background: #fff4e0; color: #b86e00; }
.badge-confirmed  { background: #e8edff; color: #2d4aab; }
.badge-preparing  { background: #e8edff; color: #2d4aab; }
.badge-assigned   { background: #e8edff; color: #2d4aab; }
.badge-out_for_delivery { background: #fff4e0; color: #b86e00; }
.badge-delivered  { background: #e6f9ee; color: #1a7a40; }
.badge-cancelled  { background: #ffe8e8; color: #b83232; }

/* ── TWO COLUMN ROW ──────────────────────── */
.dashboard-row-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

/* ── DASHBOARD GRID ─────────────────────── */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

/* ── FAVORITES ───────────────────────────── */
.favorites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
    padding: 1rem 1.25rem;
}

.favorite-card {
    text-decoration: none;
    border: 0.5px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: var(--transition);
    height: 160px;
    display: flex;
    flex-direction: column;
}
.favorite-card:hover {
    transform: translateY(-2px);
    border-color: var(--orange);
    box-shadow: 0 4px 14px rgba(255,107,44,0.1);
}

.favorite-card__image {
    width: 100%;
    height: 100px;
    object-fit: cover;
    display: block;
}

.favorite-card__info { padding: 10px 12px; }
.favorite-card__name {
    font-weight: 700;
    font-size: 0.8rem;
    color: var(--navy);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.favorite-card__price { font-size: 0.75rem; color: var(--orange); font-weight: 700; margin-top: 2px; }

/* ── SUPPORT ─────────────────────────────── */
.support-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 1rem 1.25rem;
}

.support-card {
    text-decoration: none;
    padding: 1rem;
    border: 0.5px solid var(--border);
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: var(--transition);
}
.support-card:hover { border-color: var(--orange); background: rgba(255,107,44,0.03); }

.support-card__icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: var(--orange-light);
    display: flex; align-items: center; justify-content: center;
    color: var(--orange);
    font-size: 1.1rem;
    flex-shrink: 0;
}

.support-card__title { font-weight: 700; font-size: 0.85rem; color: var(--navy); }
.support-card__desc  { font-size: 0.72rem; color: var(--text-muted); margin-top: 2px; }

/* ── EMPTY STATE ─────────────────────────── */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-muted);
}
.empty-state i { font-size: 2rem; margin-bottom: 0.5rem; display: block; color: var(--orange); }
.empty-state a  { color: var(--orange); font-weight: 700; }

/* ── ANIMATIONS ──────────────────────────── */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── RESPONSIVE ──────────────────────────── */
@media (max-width: 1024px) {
    .dashboard-grid { grid-template-columns: 1fr; }
    .stats-overview { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
}

@media (max-width: 768px) {
    .dashboard-container { padding: 1rem; }
    .page-hdr { padding: 1rem; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .stats-overview { grid-template-columns: 1fr; gap: 12px; }
    .dashboard-grid { grid-template-columns: 1fr; gap: 1rem; }
    .welcome-banner__greeting { font-size: 1.3rem; }
    .stat-highlight { padding: 1rem; }
    .stat-highlight__value { font-size: 1.5rem; }
    .stat-highlight__icon { width: 48px; height: 48px; font-size: 1.25rem; }
}
</style>
@endpush