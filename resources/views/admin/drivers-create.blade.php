@extends('layouts.app')
@section('title', 'Add Driver')
@section('sl-drivers', 'active')
@section('content')

<div style="max-width: 600px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <h1>Add New Driver</h1>
        <p>Register a new delivery driver</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.drivers.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Password (Driver ID#) *</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Driver
                </button>
                <a href="{{ route('admin.drivers') }}" class="btn btn-outline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection