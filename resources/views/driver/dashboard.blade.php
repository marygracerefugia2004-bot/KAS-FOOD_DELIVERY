@extends('layouts.app')

@section('title', 'Driver Dashboard')
@section('page-title', 'Dashboard')
@section('sl-dashboard', 'active')

@section('content')
<div class="driver-dashboard-wrap">
    <div class="page-hdr fade-up">
        <div class="page-hdr-left">
            <div class="page-breadcrumb">
                <a href="{{ route('driver.dashboard') }}">Driver</a>
                <span>/</span>
                <span>Dashboard</span>
            </div>
            <h1><i class="fas fa-motorcycle" style="color:var(--orange)"></i> Delivery Command Center</h1>
            <p>Active deliveries and progress for {{ auth()->user()->name }}</p>
        </div>
        {{-- Availability Toggle --}}
        <form action="{{ route('driver.toggle-availability') }}" method="POST">
            @csrf
            <button class="btn {{ auth()->user()->is_available ? 'btn-success' : 'btn-secondary' }}">
                <i class="fas {{ auth()->user()->is_available ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                {{ auth()->user()->is_available ? 'Online' : 'Offline' }}
            </button>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card fade-up">
            <div class="stat-ico ico-orange"><i class="fas fa-truck-fast"></i></div>
            <div class="stat-val">{{ $todayDeliveries ?? 0 }}</div>
            <div class="stat-lbl">Today's Deliveries</div>
        </div>
        <div class="stat-card fade-up-1">
            <div class="stat-ico ico-green"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-val">₱{{ number_format($todayEarnings ?? 0, 2) }}</div>
            <div class="stat-lbl">Today's Earnings</div>
        </div>
        <div class="stat-card fade-up-2">
            <div class="stat-ico ico-navy"><i class="fas fa-star"></i></div>
            <div class="stat-val">{{ number_format($avgRating ?? 0, 1) }} ★</div>
            <div class="stat-lbl">Your Rating</div>
        </div>
    </div>

    <div class="card fade-up" style="margin-bottom:1rem;">
        <div class="card-header">
            <span class="card-title">
                <span class="card-icon"><i class="fas fa-chart-line"></i></span>
                Last 7 Days Performance
            </span>
        </div>
        <div style="padding: 0 1.25rem 1.25rem;">
            <canvas id="driverTrendChart" height="110"></canvas>
        </div>
    </div>

    <div class="orders-container">
        @if($orders->isEmpty())
            <div class="card fade-up" style="text-align:center;padding:3rem">
                <i class="fas fa-inbox" style="font-size:2.8rem;color:var(--orange);margin-bottom:1rem;display:block"></i>
                <h3 style="font-weight:700;margin-bottom:.3rem">No active deliveries</h3>
                <p style="color:var(--muted)">You'll see assigned orders here once admin assigns one.</p>
            </div>
        @endif

        @foreach($orders as $order)
        <div class="card fade-up order-card" style="margin-bottom:1rem" id="order-{{ $order->id }}">
            <div class="driver-order-head">
                <div style="flex:2">
                    <span class="badge badge-{{ $order->status }}" style="margin-bottom:.5rem">{{ str_replace('_',' ',$order->status) }}</span>
                    <h3 style="font-size:1rem;font-weight:700;margin-bottom:.3rem">Order #{{ $order->id }} — {{ $order->food->name ?? 'Deleted Item' }}</h3>

                    @if($order->items && $order->items->count())
                    <div style="font-size:.84rem; margin-top:.5rem">
                        <strong>Items:</strong>
                        <ul style="margin:.3rem 0 0 1.2rem">
                            @foreach($order->items as $item)
                                <li>{{ $item->quantity }}x {{ $item->product_name ?? $item->food->name ?? 'Item' }}
                                    @if($item->notes) <span style="color:var(--muted)">({{ $item->notes }})</span> @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <p style="font-size:.85rem;color:var(--muted);margin-top:.45rem">
                        <i class="fas fa-user"></i> {{ $order->user->name ?? 'Unknown' }} · {{ $order->user->phone ?? 'N/A' }}
                        @if($order->user->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->user->phone) }}" target="_blank" class="btn btn-sm" style="background:#25D366; color:#fff; padding:.24rem .6rem; margin-left:.5rem">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        @endif
                        <br>
                        <i class="fas fa-map-marker-alt"></i> {{ $order->delivery_address }}
                    </p>

                    @if($order->estimated_arrival)
                        <p style="font-size:.82rem;color:var(--orange);font-weight:700;margin-top:.3rem">
                            <i class="fas fa-clock"></i> ETA: {{ $order->estimated_arrival->format('h:i A') }}
                        </p>
                    @endif

                    @if($order->status === 'out_for_delivery' && $order->accepted_at)
                        <div class="delivery-timer" data-accepted-at="{{ $order->accepted_at->toIso8601String() }}">
                            <i class="fas fa-hourglass-half"></i>
                            <span class="timer-display">--:--</span> on delivery
                        </div>
                    @endif
                </div>

                <div style="display:flex;flex-direction:column;gap:.5rem;min-width:170px" class="order-actions">
                    @if($order->status === 'assigned')
                        <button class="btn btn-success btn-sm accept-btn" data-id="{{ $order->id }}">
                            <i class="fas fa-check"></i> Accept
                        </button>
                        <button class="btn btn-danger btn-sm reject-btn" data-id="{{ $order->id }}">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    @endif

                    @if($order->status === 'out_for_delivery')
                        <div class="deliver-form">
                            <input type="file" name="proof" accept="image/*" class="form-control proof-input" style="margin-bottom:.4rem;font-size:.76rem">
                            <img class="proof-preview" style="max-width:100px;display:none;margin-bottom:.4rem;border-radius:8px">
                            <button class="btn btn-primary btn-sm deliver-btn" data-id="{{ $order->id }}">
                                <i class="fas fa-check-double"></i> Mark Delivered
                            </button>
                        </div>
                    @endif

                    @if($order->latitude)
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}&travelmode=driving" target="_blank" class="btn btn-navy btn-sm">
                            <i class="fas fa-directions"></i> Navigate
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card fade-up">
        <div class="card-header">
            <span class="card-title">
                <span class="card-icon"><i class="fas fa-history"></i></span>
                Delivery History
            </span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>#</th><th>Food</th><th>Customer</th><th>Total</th><th>Date</th></tr>
                </thead>
                <tbody>
                @forelse($history as $o)
                    <tr>
                        <td>#{{ $o->id }}</td>
                        <td>{{ $o->food->name ?? 'Deleted' }}</td>
                        <td>{{ $o->user->name ?? 'Unknown' }}</td>
                        <td style="color:var(--orange);font-weight:700">₱{{ number_format($o->total_price,2) }}</td>
                        <td style="font-size:.8rem;color:var(--muted)">{{ $o->updated_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted)">No deliveries yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
.driver-dashboard-wrap { max-width: 1040px; margin: 0 auto; }
.driver-order-head { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.delivery-timer {
    margin-top: .5rem;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .3rem .55rem;
    border-radius: 999px;
    background: rgba(255, 107, 44, .12);
    color: var(--orange);
    font-size: .75rem;
    font-weight: 700;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const trendLabels = @json($trendLabels ?? []);
const trendDeliveries = @json($trendDeliveries ?? []);
const trendEarnings = @json($trendEarnings ?? []);

(() => {
    const chartCanvas = document.getElementById('driverTrendChart');
    if (!chartCanvas || typeof Chart === 'undefined') return;

    new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Deliveries',
                    data: trendDeliveries,
                    borderColor: '#0D1B4B',
                    backgroundColor: 'rgba(13,27,75,0.1)',
                    tension: 0.35,
                    borderWidth: 3,
                    fill: true,
                    yAxisID: 'yDeliveries'
                },
                {
                    label: 'Earnings (PHP)',
                    data: trendEarnings,
                    borderColor: '#FF6B2C',
                    backgroundColor: 'rgba(255,107,44,0.15)',
                    tension: 0.35,
                    borderWidth: 3,
                    fill: true,
                    yAxisID: 'yEarnings'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                yDeliveries: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true
                },
                yEarnings: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: {
                        callback: (value) => 'P' + Number(value).toLocaleString()
                    }
                }
            }
        }
    });
})();

const csrf = document.querySelector('meta[name="csrf-token"]').content;

function reattachEventListeners() {
    document.querySelectorAll('.accept-btn').forEach(btn => {
        btn.removeEventListener('click', acceptHandler);
        btn.addEventListener('click', acceptHandler);
    });
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.removeEventListener('click', rejectHandler);
        btn.addEventListener('click', rejectHandler);
    });
    document.querySelectorAll('.deliver-btn').forEach(btn => {
        btn.removeEventListener('click', deliverHandler);
        btn.addEventListener('click', deliverHandler);
    });
    initProofPreview();
}

function acceptHandler(e) {
    const btn = e.currentTarget;
    const orderId = btn.dataset.id;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/driver/orders/${orderId}/accept`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) location.reload();
        else alert('Error: ' + (data.error || 'Failed to accept'));
    })
    .catch(err => alert('Error: ' + err.message))
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-check"></i> Accept'; });
}

function rejectHandler(e) {
    if (!confirm('Are you sure you want to reject this order?')) return;
    const btn = e.currentTarget;
    const orderId = btn.dataset.id;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/driver/orders/${orderId}/reject`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) location.reload();
        else alert('Error: ' + (data.error || 'Failed to reject'));
    })
    .catch(err => alert('Error: ' + err.message))
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-times"></i> Reject'; });
}

function deliverHandler(e) {
    const btn = e.currentTarget;
    const orderId = btn.dataset.id;
    const form = btn.closest('.deliver-form');
    const proofInput = form.querySelector('.proof-input');
    const formData = new FormData();
    formData.append('_token', csrf);
    if (proofInput.files.length > 0) formData.append('proof', proofInput.files[0]);

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/driver/orders/${orderId}/delivered`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) location.reload();
        else alert('Error: ' + (data.error || 'Failed to mark delivered'));
    })
    .catch(err => alert('Error: ' + err.message))
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-check-double"></i> Mark Delivered'; });
}

function initProofPreview() {
    document.querySelectorAll('.proof-input').forEach(input => {
        input.removeEventListener('change', previewChangeHandler);
        input.addEventListener('change', previewChangeHandler);
    });
}
function previewChangeHandler(e) {
    const input = e.currentTarget;
    const preview = input.closest('.deliver-form').querySelector('.proof-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = ev => { preview.src = ev.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function updateTimers() {
    document.querySelectorAll('.delivery-timer').forEach(el => {
        const acceptedAt = new Date(el.dataset.acceptedAt);
        const now = new Date();
        const diffSeconds = Math.floor((now - acceptedAt) / 1000);
        if (diffSeconds > 0) {
            const mins = Math.floor(diffSeconds / 60);
            const secs = diffSeconds % 60;
            el.querySelector('.timer-display').innerText = `${mins}:${secs.toString().padStart(2,'0')}`;
        }
    });
}
setInterval(updateTimers, 1000);
updateTimers();

@if($orders->count())
function sendLocation(lat, lng) {
    const orderId = {{ $orders->first()->id }};
    fetch('/driver/location', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ lat, lng, order_id: orderId })
    });
}
if (navigator.geolocation) {
    setInterval(() => {
        navigator.geolocation.getCurrentPosition(p => sendLocation(p.coords.latitude, p.coords.longitude));
    }, 15000);
}
@endif

setInterval(() => {
    fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newOrdersHtml = doc.querySelector('.orders-container')?.innerHTML;
            if (newOrdersHtml) {
                document.querySelector('.orders-container').innerHTML = newOrdersHtml;
                reattachEventListeners();
                updateTimers();
            }
        })
        .catch(err => console.warn('Polling error:', err));
}, 30000);

reattachEventListeners();
</script>
@endpush
@endsection
