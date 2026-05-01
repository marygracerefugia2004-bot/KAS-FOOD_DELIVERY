@extends('layouts.app')
@section('title', 'Manage Drivers')
@section('sl-drivers', 'active')
@section('content')

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Manage Drivers</h1>
            <p>View and manage all delivery drivers</p>
        </div>
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Driver
        </a>
    </div>

    <!-- Tabs -->
    <div style="display:flex; gap:0.5rem; margin-bottom:1.5rem; border-bottom:1px solid var(--border); padding-bottom:0.5rem;">
        <a href="{{ route('admin.drivers') }}" class="tab-btn @if(!request('tab') || request('tab') == 'drivers')active @endif" data-tab="drivers" style="background:none; border:none; padding:0.5rem 1rem; font-weight:700; color:var(--orange); border-bottom:2px solid var(--orange); cursor:pointer; text-decoration:none;">
            <i class="fas fa-motorcycle"></i> Drivers
        </a>
        <a href="{{ route('admin.drivers', ['tab' => 'requests']) }}" class="tab-btn @if(request('tab') == 'requests')active @endif" data-tab="requests" style="background:none; border:none; padding:0.5rem 1rem; font-weight:600; color:var(--muted); cursor:pointer; text-decoration:none;">
            <i class="fas fa-clipboard-check"></i> Driver Requests
            @if(($pendingCount ?? 0) > 0)
                <span class="badge badge-warning" style="margin-left:0.5rem">{{ $pendingCount }}</span>
            @endif
        </a>
    </div>

    <!-- Drivers Table -->
    <div id="drivers-tab" class="tab-content" @if(request('tab') == 'requests') style="display:none;" @endif>
        <div class="card">
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Vehicle Info</th>
                            <th>License</th>
                            <th>Contact</th>
                            <th>Performance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--orange), var(--orange2)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 0.75rem;">
                                        {{ strtoupper(substr($driver->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $driver->name }}</div>
                                        <div style="font-size: 0.7rem; color: var(--muted);">ID: {{ $driver->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.8rem;">
                                    <div style="display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.25rem;">
                                        <i class="fas fa-{{ $driver->vehicle_type == 'motorcycle' ? 'motorcycle' : ($driver->vehicle_type == 'car' ? 'car' : 'bicycle') }}"></i>
                                        <span style="font-weight: 600; text-transform: capitalize;">{{ $driver->vehicle_type ?? 'N/A' }}</span>
                                    </div>
                                    @if($driver->vehicle_model)
                                        <div style="color: var(--muted); font-size: 0.75rem;">{{ $driver->vehicle_model }}</div>
                                    @endif
                                    @if($driver->license_plate)
                                        <div style="background: var(--bg); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; display: inline-block; margin-top: 0.25rem;">{{ $driver->license_plate }}</div>
                                    @endif
                                    <div style="margin-top:.35rem; color:var(--muted); font-size:.72rem;">
                                        Experience: {{ $driver->years_of_experience ?? 0 }} years
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.8rem;">
                                    @if($driver->driver_license_number)
                                        <div style="font-weight: 600;">{{ $driver->driver_license_number }}</div>
                                    @else
                                        <div style="color: var(--muted);">Not set</div>
                                    @endif
                                    @if($driver->driver_license_expiry)
                                        <div style="font-size: 0.7rem; color: {{ $driver->driver_license_expiry->isPast() ? 'var(--danger)' : 'var(--success)' }}">
                                            Exp: {{ $driver->driver_license_expiry->format('M d, Y') }}
                                        </div>
                                    @endif
                                    <div style="font-size:.68rem; color:var(--muted); margin-top:.25rem;">
                                        Read-only (updated by driver)
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.8rem;">
                                    <div>{{ $driver->email }}</div>
                                    <div style="color: var(--muted);">{{ $driver->phone ?? 'No phone' }}</div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.8rem;">
                                    <div style="display: flex; gap: 1rem;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 1rem;">{{ $driver->orders()->where('status','delivered')->count() }}</div>
                                            <div style="color: var(--muted); font-size: 0.7rem;">Deliveries</div>
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; font-size: 1rem; color: var(--success);">₱{{ number_format($driver->orders()->where('status','delivered')->sum('total_price'), 0) }}</div>
                                            <div style="color: var(--muted); font-size: 0.7rem;">Total Sales</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($driver->is_active ?? true)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <form action="{{ route('admin.drivers.toggle', $driver) }}" method="POST" onsubmit="return confirm('Toggle driver status?')">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline" type="submit">
                                            <i class="fas {{ ($driver->is_active ?? true) ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem;">
                                <i class="fas fa-motorcycle" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                                <p>No drivers found.</p>
                                <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                                    <i class="fas fa-plus"></i> Add First Driver
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 1.5rem;">
                {{ $drivers->links() }}
            </div>
        </div>
    </div>

    <!-- Driver Requests Table -->
    <div id="requests-tab" class="tab-content" @if(request('tab') != 'requests') style="display:none;" @endif>
        <div class="card">
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests ?? \App\Models\DriverRequest::with('user')->latest()->paginate(15) as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->user->email }}</td>
                            <td>{{ $request->user->phone ?? 'N/A' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline view-msg-btn" 
                                    data-msg="{{ $request->message ?? 'No message' }}"
                                    data-name="{{ $request->user->name }}"
                                    data-email="{{ $request->user->email }}"
                                    data-date="{{ $request->created_at->format('M d, Y H:i') }}">
                                    View Message
                                </button>
                            </td>
                            <td>{{ $request->created_at->diffForHumans() }}</td>
                            <td>
                                @if($request->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($request->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    @if($request->status === 'pending')
                                    <form action="{{ route('admin.driver-requests.approve', $request) }}" method="POST" onsubmit="return confirm('Approve this driver request? The user will be promoted to driver.')">
                                        @csrf
                                        <button class="btn btn-sm btn-success" type="submit">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.driver-requests.reject', $request) }}" method="POST" onsubmit="return confirm('Reject this driver request?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" type="submit">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-muted">Processed</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem;">
                                <i class="fas fa-clipboard-check" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                                <p>No driver requests found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 1.5rem;">
                {{ ($requests ?? \App\Models\DriverRequest::with('user')->latest()->paginate(15))->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Simple Message Modal -->
<div id="msgModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--surface); border-radius:var(--r2); max-width:500px; width:90%; padding:1.5rem; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin:0; font-family:var(--font2);">Driver Request Message</h3>
            <button onclick="closeMsgModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:var(--muted);">&times;</button>
        </div>
        <div>
            <strong>From:</strong> <span id="modalName"></span><br>
            <strong>Email:</strong> <span id="modalEmail"></span><br>
            <strong>Applied:</strong> <span id="modalDate"></span><br><br>
            <strong>Message:</strong><br>
            <p id="modalMessage" style="white-space: pre-wrap; background:var(--bg); padding:0.75rem; border-radius:8px;"></p>
        </div>
        <div style="margin-top:1.5rem; text-align:right;">
            <button class="btn btn-outline" onclick="closeMsgModal()">Close</button>
        </div>
    </div>
</div>

<script>
// Message modal
document.querySelectorAll('.view-msg-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('modalName').textContent = this.dataset.name;
        document.getElementById('modalEmail').textContent = this.dataset.email;
        document.getElementById('modalDate').textContent = this.dataset.date;
        document.getElementById('modalMessage').textContent = this.dataset.msg || 'No message provided.';
        document.getElementById('msgModal').style.display = 'flex';
    });
});
function closeMsgModal() {
    document.getElementById('msgModal').style.display = 'none';
}
document.getElementById('msgModal').addEventListener('click', function(e) {
    if(e.target === this) closeMsgModal();
});
</script>

@endsection
