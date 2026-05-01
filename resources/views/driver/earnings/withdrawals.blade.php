@extends('layouts.app')

@section('title', 'Withdrawal History')
@section('page-title', 'Withdrawals')
@section('sl-earnings', 'active')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 2rem">
    <div class="page-header" style="margin-bottom: 2rem">
        <h1><i class="fas fa-list" style="color: var(--orange)"></i> Withdrawal History</h1>
        <p>All your payout requests</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-history"></i> Withdrawal Requests</span>
            <a href="{{ route('driver.earnings.wallet') }}" class="btn btn-sm btn-primary">New Withdrawal</a>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                    <tr>
                        <td>#{{ $w->id }}</td>
                        <td style="font-weight: 700; color: var(--orange)">₱{{ number_format($w->amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $w->payment_method)) }}</td>
                        <td style="font-size: 0.85rem">{{ Str::limit($w->payment_details, 30) }}</td>
                        <td>
                            <span class="badge badge-{{ $w->status == 'completed' ? 'delivered' : ($w->status == 'rejected' ? 'cancelled' : ($w->status == 'processing' ? 'assigned' : 'pending')) }}">
                                {{ ucfirst($w->status) }}
                            </span>
                            @if($w->admin_notes)
                                <small style="display: block; color: var(--muted); font-size: 0.75rem">{{ $w->admin_notes }}</small>
                            @endif
                        </td>
                        <td>{{ $w->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; color: var(--muted)">No withdrawal requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>
@endsection
