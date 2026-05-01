@php
$pendingRequests = \App\Models\DriverRequest::with('user')->where('status', 'pending')->get();
@endphp
@if($pendingRequests->count())
<div class="card" style="margin-bottom: 1.5rem; background: #fff3e0; border-left: 4px solid var(--orange);">
    <div class="card-header" style="background: transparent;">
        <h3><i class="fas fa-user-clock"></i> Pending Driver Requests</h3>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>User</th><th>Email</th><th>Message</th><th>Requested</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($pendingRequests as $req)
                <tr>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->user->email }}</td>
                    <td>{{ $req->message ?? 'No message' }}</td>
                    <td>{{ $req->created_at->diffForHumans() }}</td>
                    <td>
                        <form action="{{ route('admin.driver-requests.approve', $req) }}" method="POST" style="display:inline-block">
                            @csrf
                            <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Approve</button>
                        </form>
                        <form action="{{ route('admin.driver-requests.reject', $req) }}" method="POST" style="display:inline-block">
                            @csrf
                            <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Reject</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif  