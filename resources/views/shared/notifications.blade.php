@extends('layouts.app')
@section('title', 'Notifications')
@section('sl-notifs', 'active')
@section('content')

<div style="max-width: 800px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Notifications</h1>
            <p>Stay updated with your orders and promotions</p>
        </div>
    </div>

    <div class="card">
        @forelse($notifs as $notif)
        <div class="notification-item {{ !$notif->is_read ? 'unread' : '' }}" style="
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            {{ !$notif->is_read ? 'background: var(--orange-glow);' : '' }}
        ">
            <div class="notification-icon" style="
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background: {{ $notif->type == 'order' ? 'var(--orange)' : 'var(--navy)' }};
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                flex-shrink: 0;
            ">
                <i class="fas {{ $notif->type == 'order' ? 'fa-box' : ($notif->type == 'promo' ? 'fa-ticket-alt' : 'fa-bell') }}"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-weight: {{ !$notif->is_read ? '700' : '500' }}; margin-bottom: 0.25rem;">
                    {{ $notif->title }}
                </div>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                    {{ $notif->message }}
                </div>
                <div style="font-size: 0.7rem; color: var(--text-muted);">
                    <i class="far fa-clock"></i> {{ $notif->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem;">
            <i class="fas fa-bell-slash" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
            <p style="color: var(--text-muted);">No notifications yet</p>
            <p style="font-size: 0.85rem; color: var(--text-muted);">When you place orders or receive updates, they'll appear here.</p>
        </div>
        @endforelse
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $notifs->links() }}
    </div>
</div>

<style>
.notification-item:hover {
    background: var(--bg);
}
</style>

@endsection