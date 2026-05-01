@extends('layouts.app')

@section('title', 'Vehicle Maintenance')
@section('page-title', 'Maintenance')
@section('sl-vehicle', 'active')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; padding: 2rem">
    @php
        $shouldShowAddForm = $errors->any() || old('service_type') || old('service_date');
    @endphp

    <div class="page-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem">
        <div>
            <h1><i class="fas fa-wrench" style="color: var(--orange)"></i> Vehicle Maintenance</h1>
            <p>Track service history and keep your vehicle in top condition</p>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('add-form').style.display='block'">
            <i class="fas fa-plus"></i> Add Record
        </button>
    </div>

    <!-- Stats Row -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: 2rem">
        <div class="stat-card">
            <div class="stat-ico ico-orange"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-val">{{ $stats['last_service'] ? \Carbon\Carbon::parse($stats['last_service'])->format('M d, Y') : 'N/A' }}</div>
            <div class="stat-lbl">Last Service</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-red"><i class="fas fa-calendar-exclamation"></i></div>
            <div class="stat-val">{{ $stats['next_service_due'] ? \Carbon\Carbon::parse($stats['next_service_due'])->format('M d, Y') : 'N/A' }}</div>
            <div class="stat-lbl">Next Service Due</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico ico-navy"><i class="fas fa-tachometer-alt"></i></div>
            <div class="stat-val">{{ $stats['last_service_km'] ?? 'N/A' }} km</div>
            <div class="stat-lbl">Last Odometer</div>
        </div>
        <div class="stat-card">
            <div class="stat-ico" style="background: #D1FAE5; color: #059669"><i class="fas fa-coins"></i></div>
            <div class="stat-val">₱{{ number_format($stats['total_maintenance_cost'] ?? 0, 0) }}</div>
            <div class="stat-lbl">Total Cost</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <div style="font-weight:700; margin-bottom:.2rem">Unable to save maintenance record.</div>
                <ul style="margin:0; padding-left:1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Add Form (collapsed by default) -->
    <div id="add-form" class="card" style="margin-bottom: 2rem; display: {{ $shouldShowAddForm ? 'block' : 'none' }}">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-plus-circle"></i> Add Maintenance Record</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('driver.vehicle.store') }}" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem">
                    <div class="form-group">
                        <label class="form-label">Service Type *</label>
                        <input type="text" name="service_type" class="form-control" required placeholder="e.g., Oil Change" value="{{ old('service_type') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service Date *</label>
                        <input type="date" name="service_date" class="form-control" required value="{{ old('service_date', date('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Odometer (km)</label>
                        <input type="number" name="odometer_km" class="form-control" min="0" value="{{ old('odometer_km') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Next Service Due</label>
                        <input type="date" name="next_service_due" class="form-control" value="{{ old('next_service_due') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cost (₱)</label>
                        <input type="number" name="cost" class="form-control" step="0.01" min="0" value="{{ old('cost') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service Center</label>
                        <input type="text" name="service_center" class="form-control" placeholder="Shop name" value="{{ old('service_center') }}">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 0.5rem">
                    <label class="form-label">Description / Notes</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Additional details...">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Receipt Image (optional)</label>
                    <input type="file" name="receipt_image" class="form-control" accept="image/*">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 1rem">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('add-form').style.display='none'">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Maintenance History -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-history"></i> Service History</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Odometer</th>
                        <th>Cost</th>
                        <th>Center</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $m)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($m->service_date)->format('M d, Y') }}</td>
                        <td><strong>{{ $m->service_type }}</strong></td>
                        <td style="font-size: 0.85rem; color: var(--muted)">{{ Str::limit($m->description, 40) }}</td>
                        <td>{{ $m->odometer_km ? $m->odometer_km . ' km' : '-' }}</td>
                        <td style="color: var(--orange); font-weight: 700">₱{{ number_format($m->cost, 2) }}</td>
                        <td>{{ $m->service_center ?? '-' }}</td>
                        <td>
                            <a href="{{ route('driver.vehicle.edit', $m) }}" class="btn btn-sm btn-outline">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align: center; color: var(--muted)">No maintenance records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem">
            {{ $maintenances->links() }}
        </div>
    </div>
</div>
@endsection
