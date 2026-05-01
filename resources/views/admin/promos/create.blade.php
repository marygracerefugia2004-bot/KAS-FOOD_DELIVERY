@extends('layouts.app')
@section('title', 'Add Promo Code')
@section('sl-promos', 'active')
@section('content')

<div style="max-width: 600px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <h1>Add Promo Code</h1>
        <p>Create a new discount code for customers</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.promos.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Promo Code *</label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                    value="{{ old('code') }}" placeholder="e.g., SAVE20, WELCOME10" required style="text-transform: uppercase">
                <small class="form-text text-muted">Use uppercase letters and numbers only</small>
                @error('code') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Discount Percentage *</label>
                <input type="number" name="discount_percent" class="form-control @error('discount_percent') is-invalid @enderror" 
                    value="{{ old('discount_percent') }}" min="1" max="100" step="1" required>
                <small class="form-text text-muted">Enter a number between 1 and 100</small>
                @error('discount_percent') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Maximum Uses *</label>
                <input type="number" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror" 
                    value="{{ old('max_uses', 100) }}" min="1" required>
                <small class="form-text text-muted">How many times this code can be used</small>
                @error('max_uses') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Expiration Date (Optional)</label>
                <input type="datetime-local" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" 
                    value="{{ old('expires_at') }}">
                <small class="form-text text-muted">Leave empty for no expiration</small>
                @error('expires_at') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Promo Code
                </button>
                <a href="{{ route('admin.promos.index') }}" class="btn btn-outline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection