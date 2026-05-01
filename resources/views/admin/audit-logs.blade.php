@extends('layouts.app')
@section('title','Audit Logs')
@section('content')
<div class="page-header">
    <h1><i class="fas fa-history" style="color:var(--orange)"></i> Audit Logs</h1>
    <p style="color:var(--danger);font-weight:700;font-size:.85rem"><i class="fas fa-lock"></i> Immutable — logs cannot be edited or deleted</p>
</div>
<div class="card" style="margin-bottom:1.5rem">
    <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap">
        <input name="action" class="form-control" style="max-width:160px" placeholder="Action..." value="{{ request('action') }}">
        <input name="date" type="date" class="form-control" style="max-width:160px" value="{{ request('date') }}">
        <input name="user_id" type="number" class="form-control" style="max-width:120px" placeholder="User ID" value="{{ request('user_id') }}">
        <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
    </form>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>User</th><th>Role</th><th>Action</th><th>Description</th><th>IP</th><th>Time</th></tr></thead>
            <tbody>
            @foreach($logs as $log)
            <tr>
                <td style="font-size:.8rem;color:var(--text-muted)">{{ $log->id }}</td>
                <td>{{ $log->user?->name ?? '—' }}</td>
                <td><span class="badge badge-{{ $log->user?->role ?? 'user' }}">{{ $log->user?->role ?? 'N/A' }}</span></td>
                <td><span style="font-weight:700;color:var(--navy)">{{ $log->action }}</span></td>
                <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $log->description }}</td>
                <td style="font-family:monospace;font-size:.75rem;color:var(--text-muted)">{{ $log->ip_address }}</td>
                <td style="font-size:.8rem;color:var(--text-muted)">{{ $log->created_at->format('M d, g:i A') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $logs->links() }}</div>
</div>
@endsection