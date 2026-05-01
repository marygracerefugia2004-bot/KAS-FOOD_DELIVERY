@extends('layouts.app')
@section('title', 'My Profile')
@section('sl-profile', 'active')
@section('content')

<div style="max-width: 800px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>My Profile</h1>
            <p>Manage your account information</p>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Avatar Section -->
            <div class="form-group" style="text-align: center;">
                <label class="form-label">Profile Picture</label>
                <div style="margin-bottom: 1rem;">
                    @if($user->avatar)
                        <img src="{{ asset('storage/'.$user->avatar) }}" 
                             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--orange);">
                    @else
                        <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--orange), var(--orange2)); 
                                    display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 3rem; font-weight: bold;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <input type="file" name="avatar" class="form-control" accept="image/*">
                <small class="form-text text-muted">Upload a new profile picture (max 2MB)</small>
                @error('avatar') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <!-- Name -->
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                <small class="form-text text-muted">Email cannot be changed</small>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                    value="{{ old('phone', $user->phone) }}" placeholder="Enter your phone number">
                @error('phone') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <!-- Role -->
            <div class="form-group">
                <label class="form-label">Account Type</label>
                <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
            </div>

            <!-- Member Since -->
            <div class="form-group">
                <label class="form-label">Member Since</label>
                <input type="text" class="form-control" value="{{ $user->created_at->format('F d, Y') }}" disabled>
            </div>

            <!-- Stats -->
            <div style="background: var(--bg); border-radius: 8px; padding: 1rem; margin: 1rem 0;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: center;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--orange);">
                            {{ $user->orders()->count() }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Total Orders</div>
                    </div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--orange);">
                            {{ $user->favorites()->count() }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Favorites</div>
                    </div>
                </div>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="dark_mode" value="1" {{ $user->dark_mode ? 'checked' : '' }} onchange="this.form.submit()">
                    Dark Mode
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Change Password Section -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-key"></i> Change Password</span>
        </div>
        <form action="{{ route('user.password.update') }}" method="POST">
            
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-key"></i> Change Password
            </button>
        </form>
    </div>
</div>

@endsection