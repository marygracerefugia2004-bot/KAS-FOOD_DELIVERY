@extends('layouts.app')

@section('title', 'My Wallet')
@section('page-title', 'Wallet')
@section('sl-earnings', 'active')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 2rem">
    <div class="page-header" style="margin-bottom: 2rem">
        <h1><i class="fas fa-wallet" style="color: var(--orange)"></i> My Wallet</h1>
        <p>Manage your earnings and request withdrawals</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <!-- Balance Cards -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 2rem">
        <div class="stat-card">
            <div class="stat-ico ico-green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-val">₱{{ number_format($driver->available_balance, 2) }}</div>
            <div class="stat-lbl">Available Balance</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-clock"></i></div>
            <div class="stat-val">₱{{ number_format($driver->pending_balance, 2) }}</div>
            <div class="stat-lbl">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico" style="background: #EEF2FF; color: #3730A3"><i class="fas fa-sack"></i></div>
            <div class="stat-val">₱{{ number_format($driver->total_earnings, 2) }}</div>
            <div class="stat-lbl">Total Earned</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem">
        <!-- Withdrawal Form -->
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-money-check"></i> Request Withdrawal</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('driver.earnings.withdraw') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Amount (Min ₱100)</label>
                        <input type="number" name="amount" class="form-control" min="100" max="{{ $driver->available_balance }}" required>
                        <small style="color: var(--muted)">Available: ₱{{ number_format($driver->available_balance, 2) }}</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="">Select method...</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gcash">GCash</option>
                            <option value="paymaya">PayMaya</option>
                            <option value="cash">Cash (Pickup)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Account / Details</label>
                        <input type="text" name="payment_details" class="form-control" placeholder="Account number, phone, or address" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </form>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-history"></i> Recent Withdrawals</span>
                <a href="{{ route('driver.earnings.withdrawals') }}" class="btn btn-sm btn-outline">View All</a>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $w)
                        <tr>
                            <td>₱{{ number_format($w->amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $w->payment_method)) }}</td>
                            <td>
                                <span class="badge badge-{{ $w->status == 'completed' ? 'delivered' : ($w->status == 'rejected' ? 'cancelled' : 'pending') }}">
                                    {{ ucfirst($w->status) }}
                                </span>
                            </td>
                            <td>{{ $w->created_at->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align: center; color: var(--muted)">No withdrawals yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
