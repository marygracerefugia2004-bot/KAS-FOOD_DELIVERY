@extends('layouts.app')
@section('title','Manage Orders')
@section('content')
<div class="page-header">
    <h1><i class="fas fa-box" style="color:var(--orange)"></i> Manage Orders</h1>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Customer</th><th>Food</th><th>Status</th><th>Driver</th><th>Total</th><th>Assign Driver</th><th>Date</th></tr></thead>
            <tbody>
            @foreach($orders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->food->name }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ str_replace('_',' ',$order->status) }}</span></td>
                <td>{{ $order->driver?->name ?? '—' }}</td>
                <td style="color:var(--orange);font-weight:700">₱{{ number_format($order->total_price,2) }}</td>
                <td>
                    @if(in_array($order->status,['pending']))
                    <form action="{{ route('admin.orders.assign', $order) }}" method="POST" style="display:flex;gap:.4rem">
                        @csrf
                        <select name="driver_id" class="form-control" style="font-size:.8rem;padding:.35rem" required>
                            <option value="">Select driver</option>
                            @foreach(\App\Models\User::where('role','driver')->where('is_active',true)->get() as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-user-check"></i></button>
                    </form>
                    @else <span style="color:var(--text-muted);font-size:.8rem">N/A</span> @endif
                </td>
                <td style="font-size:.8rem;color:var(--text-muted)">{{ $order->created_at->format('M d') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $orders->links() }}</div>
</div>
@endsection