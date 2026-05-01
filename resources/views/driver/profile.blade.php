@extends('layouts.app')

@section('title', 'Driver Profile')
@section('page-title', 'Profile')
@section('sl-profile', 'active')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 100%);
        border-radius: var(--r2);
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 107, 44, 0.15);
        border-radius: 50%;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #fff;
        border: 4px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 1rem;
    }
    .profile-name {
        font-family: var(--font2);
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.25rem;
    }
    .profile-email {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
    }
    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 107, 44, 0.25);
        border: 1px solid rgba(255, 107, 44, 0.4);
        color: var(--orange);
        padding: 0.4rem 1rem;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-top: 1rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    .info-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r);
        padding: 1.25rem;
        transition: 0.2s;
    }
    .info-card:hover {
        border-color: rgba(255, 107, 44, 0.3);
        box-shadow: 0 4px 20px rgba(255, 107, 44, 0.08);
    }
    .info-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
    }
    .info-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        background: var(--orange-glow);
        color: var(--orange);
    }
    .info-card-title {
        font-family: var(--font2);
        font-weight: 700;
        font-size: 0.95rem;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
    .form-group-full {
        grid-column: 1 / -1;
    }
    .btn-save {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        color: #fff;
        border: none;
        padding: 0.9rem 2rem;
        border-radius: var(--r);
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 107, 44, 0.3);
    }
    .success-alert {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: var(--success);
        padding: 1rem 1.25rem;
        border-radius: var(--r);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .required-star {
        color: var(--danger);
    }
</style>

<div class="main" style="max-width: 900px; margin: 0 auto;">
    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:1rem;">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <div style="font-weight:700; margin-bottom:.2rem;">Could not save profile.</div>
                <ul style="margin:0; padding-left:1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle" style="font-size: 1.1rem;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-header">
        <div style="display: flex; align-items: center; gap: 1.5rem; position: relative; z-index: 1;">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
                <div class="profile-badge">
                    <i class="fas fa-id-card"></i>
                    {{ ucfirst($user->role) }} Driver
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('driver.profile.update') }}" style="margin-top: 1.5rem;">
        @csrf
        @method('PUT')

        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <span class="info-card-title">Vehicle Information</span>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            Vehicle Type <span class="required-star">*</span>
                        </label>
                        <select name="vehicle_type" class="form-control" style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: var(--r); background: var(--bg); color: var(--text); font-size: 0.9rem;">
                            <option value="">Select type...</option>
                            <option value="motorcycle" {{ old('vehicle_type', $user->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            <option value="car" {{ old('vehicle_type', $user->vehicle_type) == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="bicycle" {{ old('vehicle_type', $user->vehicle_type) == 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            Vehicle Model
                        </label>
                        <input type="text" name="vehicle_model" class="form-control" value="{{ old('vehicle_model', $user->vehicle_model) }}" placeholder="e.g., Honda Beat 2020" style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: var(--r); background: var(--bg); color: var(--text); font-size: 0.9rem;">
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            License Plate <span class="required-star">*</span>
                        </label>
                        <input type="text" name="license_plate" class="form-control" value="{{ old('license_plate', $user->license_plate) }}" placeholder="e.g., ABC-1234" style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: var(--r); background: var(--bg); color: var(--text); font-size: 0.9rem; text-transform: uppercase;">
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            Years of Experience
                        </label>
                        <input type="number" name="years_of_experience" class="form-control" value="{{ old('years_of_experience', $user->years_of_experience) }}" min="0" max="50" placeholder="0" style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: var(--r); background: var(--bg); color: var(--text); font-size: 0.9rem;">
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <span class="info-card-title">Driver License</span>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            License Number <span class="required-star">*</span>
                        </label>
                        <input type="text" name="driver_license_number" class="form-control" value="{{ old('driver_license_number', $user->driver_license_number) }}" placeholder="License number" style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: var(--r); background: var(--bg); color: var(--text); font-size: 0.9rem; text-transform: uppercase;">
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            Expiry Date <span class="required-star">*</span>
                        </label>
                        <input type="date" name="driver_license_expiry" class="form-control" value="{{ old('driver_license_expiry', $user->driver_license_expiry?->format('Y-m-d')) }}" style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: var(--r); background: var(--bg); color: var(--text); font-size: 0.9rem;">
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection