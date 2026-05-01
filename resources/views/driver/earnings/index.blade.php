@extends('layouts.app')

@section('title', 'Earnings History')
@section('page-title', 'Earnings')
@section('sl-earnings', 'active')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 2rem">
    <div class="page-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem">
        <div>
            <h1><i class="fas fa-money-bill-wave" style="color: var(--orange)"></i> My Earnings</h1>
            <p>Track your delivery income and payout history</p>
        </div>
        <div style="display: flex; gap: 0.5rem">
            <a href="{{ route('driver.earnings.wallet') }}" class="btn btn-navy">
                <i class="fas fa-wallet"></i> Wallet
            </a>
            <a href="{{ route('driver.earnings.withdrawals') }}" class="btn btn-outline">
                <i class="fas fa-list"></i> Withdrawals
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <!-- Stats Row -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: 2rem">
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-total-bill"></i></div>
            <div class="stat-val">₱{{ number_format($stats['total_earned'], 2) }}</div>
            <div class="stat-lbl">Total Earned</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-val">₱{{ number_format($stats['available'], 2) }}</div>
            <div class="stat-lbl">Available Balance</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-navy"><i class="fas fa-clock"></i></div>
            <div class="stat-val">₱{{ number_format($stats['pending'], 2) }}</div>
            <div class="stat-lbl">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico" style="background: #FEF3C7; color: #92400E"><i class="fas fa-box"></i></div>
            <div class="stat-val">{{ $stats['deliveries_count'] }}</div>
            <div class="stat-lbl">Deliveries</div>
        </div>
    </div>

    <!-- Earnings Table -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-list"></i> Earnings History</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Amount</th>
                        <th>Commission</th>
                        <th>Net</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($earnings as $e)
                    <tr>
                        <td>#{{ $e->order_id }}</td>
                        <td>₱{{ number_format($e->order_amount, 2) }}</td>
                        <td>{{ $e->commission_percent }}% (₱{{ number_format($e->commission_amount, 2) }})</td>
                        <td style="color: var(--orange); font-weight: 700">₱{{ number_format($e->net_amount, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $e->status === 'paid' ? 'delivered' : 'pending' }}">
                                {{ ucfirst($e->status) }}
                            </span>
                        </td>
                        <td>{{ $e->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; color: var(--muted)">No earnings yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem">
            {{ $earnings->links() }}
        </div>
    </div>
</div>
@endsection
