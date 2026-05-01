@extends('layouts.app')
@section('title', 'Edit Food')
@section('sl-foods', 'active')
@section('content')

<div style="max-width: 600px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <h1>Edit Food</h1>
        <p>Update food item details</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.foods.update', $food) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Food Name *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $food->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                    <option value="">Select Category</option>
                    <option value="burger" {{ ($food->category == 'burger') ? 'selected' : '' }}>🍔 Burgers</option>
                    <option value="pizza" {{ ($food->category == 'pizza') ? 'selected' : '' }}>🍕 Pizza</option>
                    <option value="pasta" {{ ($food->category == 'pasta') ? 'selected' : '' }}>🍝 Pasta</option>
                    <option value="chicken" {{ ($food->category == 'chicken') ? 'selected' : '' }}>🍗 Chicken</option>
                    <option value="seafood" {{ ($food->category == 'seafood') ? 'selected' : '' }}>🦞 Seafood</option>
                    <option value="salad" {{ ($food->category == 'salad') ? 'selected' : '' }}>🥗 Salads</option>
                    <option value="dessert" {{ ($food->category == 'dessert') ? 'selected' : '' }}>🍰 Desserts</option>
                    <option value="drinks" {{ ($food->category == 'drinks') ? 'selected' : '' }}>🥤 Drinks</option>
                </select>
                @error('category') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description', $food->description) }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Price (₱) *</label>
                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $food->price) }}" required>
                @error('price') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Preparation Time (minutes)</label>
                <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time', $food->prep_time ?? 15) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Current Image</label>
                @if($food->image_path)
                    <div style="margin-bottom: 0.5rem;">
                        @if(str_starts_with($food->image_path, 'http'))
                            <img src="{{ $food->image_path }}" style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover;">
                        @else
                            <img src="{{ asset('storage/'.$food->image_path) }}" style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover;">
                        @endif
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="form-text text-muted">Leave empty to keep current image</small>
                @error('image') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $food->is_available) ? 'checked' : '' }}>
                    Available for ordering
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Food
                </button>
                <a href="{{ route('admin.foods.index') }}" class="btn btn-outline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection