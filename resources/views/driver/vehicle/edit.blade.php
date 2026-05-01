@extends('layouts.app')

@section('title', 'Edit Maintenance Record')
@section('page-title', 'Edit Maintenance')

@section('content')
<div style="max-width: 700px; margin: 0 auto; padding: 2rem">
    <div class="page-header" style="margin-bottom: 2rem">
        <h1><i class="fas fa-edit" style="color: var(--orange)"></i> Edit Maintenance Record</h1>
        <p>Update service details</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('driver.vehicle.update', $maintenance) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem">
                    <div class="form-group">
                        <label class="form-label">Service Type *</label>
                        <input type="text" name="service_type" class="form-control" required value="{{ old('service_type', $maintenance->service_type) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service Date *</label>
                        <input type="date" name="service_date" class="form-control" required value="{{ old('service_date', $maintenance->service_date->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Odometer (km)</label>
                        <input type="number" name="odometer_km" class="form-control" min="0" value="{{ old('odometer_km', $maintenance->odometer_km) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Next Service Due</label>
                        <input type="date" name="next_service_due" class="form-control" value="{{ old('next_service_due', $maintenance->next_service_due?->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cost (₱)</label>
                        <input type="number" name="cost" class="form-control" step="0.01" min="0" value="{{ old('cost', $maintenance->cost) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service Center</label>
                        <input type="text" name="service_center" class="form-control" value="{{ old('service_center', $maintenance->service_center) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 0.5rem">
                    <label class="form-label">Description / Notes</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $maintenance->description) }}</textarea>
                </div>

                @if($maintenance->receipt_image)
                <div class="form-group">
                    <label>Current Receipt</label><br>
                    <img src="{{ asset('storage/'.$maintenance->receipt_image) }}" style="max-width: 200px; border-radius: 8px; border: 1px solid var(--border)">
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Replace Receipt (optional)</label>
                    <input type="file" name="receipt_image" class="form-control" accept="image/*">
                </div>

                <div style="display: flex; gap: 0.5rem; margin-top: 1rem">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                    <form method="POST" action="{{ route('driver.vehicle.destroy', $maintenance) }}" onsubmit="return confirm('Delete this record?');" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                    <a href="{{ route('driver.vehicle.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
