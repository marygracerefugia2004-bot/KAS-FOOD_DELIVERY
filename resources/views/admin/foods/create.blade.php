
@extends('layouts.app')
@section('title', 'Add Food')
@section('sl-foods', 'active')
@section('content')

<div style="max-width: 600px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <h1>Add New Food</h1>
        <p>Add a new item to the menu</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Food Name *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                    <option value="">Select Category</option>
                    <option value="burger">🍔 Burgers</option>
                    <option value="pizza">🍕 Pizza</option>
                    <option value="pasta">🍝 Pasta</option>
                    <option value="chicken">🍗 Chicken</option>
                    <option value="seafood">🦞 Seafood</option>
                    <option value="salad">🥗 Salads</option>
                    <option value="dessert">🍰 Desserts</option>
                    <option value="drinks">🥤 Drinks</option>
                </select>
                @error('category') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Price (₱) *</label>
                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                @error('price') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Preparation Time (minutes)</label>
                <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time', 15) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Food Image *</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                @error('image') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                    Available for ordering
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Food
                </button>
                <a href="{{ route('admin.foods.index') }}" class="btn btn-outline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection