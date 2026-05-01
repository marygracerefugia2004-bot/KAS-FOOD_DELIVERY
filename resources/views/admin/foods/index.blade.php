@extends('layouts.app')
@section('title', 'Manage Foods')
@section('sl-foods', 'active')
@section('content')

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Manage Foods</h1>
            <p>Add, edit, or remove food items from the menu</p>
        </div>
        <a href="{{ route('admin.foods.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Food
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Prep Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($foods as $food)
                    <tr>
                        <td>{{ $food->id }}</td>
                        <td>
                            @if($food->image_path)
                                @if(str_starts_with($food->image_path, 'http'))
                                    <img src="{{ $food->image_path }}" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/'.$food->image_path) }}" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                @endif
                            @else
                                <div style="width: 50px; height: 50px; background: var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            @endif
                         </td>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->category ?? 'Uncategorized' }}</td>
                        <td style="color: var(--orange); font-weight: 700;">₱{{ number_format($food->price, 2) }}</td>
                        <td>{{ $food->prep_time ?? 15 }} min</td>
                        <td>
                            @if($food->is_available)
                                <span class="badge badge-success">Available</span>
                            @else
                                <span class="badge badge-danger">Unavailable</span>
                            @endif
                         </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.foods.edit', $food) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.foods.destroy', $food) }}" method="POST" onsubmit="return confirm('Delete this food?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.foods.toggle', $food) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline" type="submit">
                                        <i class="fas {{ $food->is_available ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>
                            </div>
                         </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-utensils" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No food items found.</p>
                            <a href="{{ route('admin.foods.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i> Add First Food
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            {{ $foods->links() }}
        </div>
    </div>
</div>

@endsection