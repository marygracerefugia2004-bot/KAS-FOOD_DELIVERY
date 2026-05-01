@extends('layouts.app')
@section('title', 'Promo Codes')
@section('sl-promos', 'active')
@section('content')

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Promo Codes</h1>
            <p>Manage discount codes and promotions</p>
        </div>
        <a href="{{ route('admin.promos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Promo Code
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Uses</th>
                        <th>Max Uses</th>
                        <th>Status</th>
                        <th>Expires At</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $promo)
                    <tr>
                        <td>{{ $promo->id }}</td>
                        <td>
                            <strong>{{ $promo->code }}</strong>
                         </div>
                        </td>
                        <td style="color: var(--orange); font-weight: 700;">{{ $promo->discount_percent }}%</td>
                        <td>{{ $promo->used_count ?? 0 }} / {{ $promo->max_uses }}</td>
                        <td>
                            @if($promo->is_active && (!$promo->expires_at || $promo->expires_at->isFuture()))
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Expired</span>
                            @endif
                         </div>
                        <td>
                            @if($promo->expires_at)
                                {{ $promo->expires_at->format('M d, Y') }}
                            @else
                                <span class="badge badge-info">Never</span>
                            @endif
                         </div>
                        <td>{{ $promo->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" onsubmit="return confirm('Delete this promo code?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                         </div>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-ticket-alt" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No promo codes found.</p>
                            <a href="{{ route('admin.promos.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i> Create First Promo
                            </a>
                         </div>
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            {{ $promos->links() }}
        </div>
    </div>
</div>

@endsection