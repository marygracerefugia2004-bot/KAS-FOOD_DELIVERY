@extends('layouts.app')
@section('title', 'My Favorites')
@section('sl-favs', 'active')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>My Favorites</h1>
            <p>Your saved food items</p>
        </div>
        <a href="{{ route('user.foods') }}" class="btn btn-primary">
            <i class="fas fa-utensils"></i> Browse Menu
        </a>
    </div>

    <div class="card">
        @forelse($favorites as $fav)
            @if($fav->food)
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1.5rem;padding:1rem">
                <div style="border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:var(--transition)">
                    <a href="{{ route('user.foods.show', $fav->food) }}" style="text-decoration:none">
                        @if($fav->food->image_path)
                            @if(str_starts_with($fav->food->image_path, 'http'))
                                <img src="{{ $fav->food->image_path }}" style="width:100%;height:180px;object-fit:cover">
                            @elseif(Storage::disk('public')->exists($fav->food->image_path))
                                <img src="{{ asset('storage/'.$fav->food->image_path) }}" style="width:100%;height:180px;object-fit:cover">
                            @else
                                <div style="width:100%;height:180px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            @endif
                        @else
                            <div style="width:100%;height:180px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem">
                                <i class="fas fa-utensils"></i>
                            </div>
                        @endif
                        <div style="padding:1rem">
                            <div style="font-weight:700;font-size:1rem;margin-bottom:.5rem">{{ $fav->food->name }}</div>
                            <div style="color:var(--text-muted);font-size:.85rem;margin-bottom:.75rem">
                                {{ $fav->food->category->name ?? 'Food' }}
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <span style="color:var(--orange);font-weight:700;font-size:1.1rem">₱{{ number_format($fav->food->price, 2) }}</span>
                                <button onclick="event.preventDefault();document.getElementById('remove-fav-{{ $fav->id }}').submit();" class="btn btn-sm btn-outline" style="color:var(--red)">
                                    <i class="fas fa-heart-broken"></i> Remove
                                </button>
                                <form id="remove-fav-{{ $fav->id }}" action="{{ route('user.favorites.toggle') }}" method="POST" style="display:none">
                                    @csrf
                                    <input type="hidden" name="food_id" value="{{ $fav->food_id }}">
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endif
        @empty
            <div style="text-align:center;padding:4rem;color:var(--text-muted)">
                <i class="fas fa-heart" style="font-size:4rem;display:block;margin-bottom:1rem;color:var(--orange)"></i>
                <h3 style="margin-bottom:.5rem">No favorites yet</h3>
                <p style="margin-bottom:1.5rem">Start adding your favorite foods!</p>
                <a href="{{ route('user.foods') }}" class="btn btn-primary">
                    <i class="fas fa-utensils"></i> Browse Menu
                </a>
            </div>
        @endforelse
    </div>

    @if($favorites->hasPages())
    <div style="margin-top:1.5rem">
        {{ $favorites->links() }}
    </div>
    @endif
</div>

@endsection