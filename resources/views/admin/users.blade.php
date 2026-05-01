@extends('layouts.app')
@section('title', 'Manage Users')
@section('sl-users', 'active')
@section('content')

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem;">
    <div class="page-hdr">
        <div class="page-hdr-left">
            <h1>Manage Users</h1>
            <p>View and manage all registered customers</p>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table">
                 <thead>
                     <tr>
                         <th>ID</th>
                         <th>Name</th>
                         <th>Email</th>
                         <th>Phone</th>
                         <th>Role</th>
                         <th>Orders</th>
                         <th>Joined</th>
                         <th>Status</th>
                         <th>Actions</th>
                     </tr>
                 </thead>
                <tbody>
                     @forelse($users as $user)
                     <tr>
                         <td>{{ $user->id }}</td>
                         <td>{{ $user->name }}</td>
                         <td>{{ $user->email }}</td>
                         <td>{{ $user->phone ?? 'N/A' }}</td>
                         <td>
                             <form action="{{ route('admin.users.update-role', $user) }}" method="POST" style="display: inline;">
                                 @csrf
                                 @method('PATCH')
                                 <select name="role" onchange="this.form.submit()" style="padding: 0.25rem; border: 1px solid var(--border); border-radius: 4px; background: var(--surface); color: var(--text);">
                                     <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                     <option value="driver" {{ $user->role === 'driver' ? 'selected' : '' }}>Driver</option>
                                     <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                 </select>
                             </form>
                         </td>
                         <td>{{ $user->orders()->count() }}</td>
                         <td>{{ $user->created_at->format('M d, Y') }}</td>
                         <td>
                             @if($user->is_active ?? true)
                                 <span class="badge badge-success">Active</span>
                             @else
                                 <span class="badge badge-danger">Inactive</span>
                             @endif
                         </td>
                         <td>
                             <div style="display: flex; gap: 0.5rem;">
                                 <form action="{{ route('admin.users.toggle', $user) }}" method="POST" onsubmit="return confirm('Toggle user status?')">
                                     @csrf
                                     @method('PATCH')
                                     <button class="btn btn-sm btn-outline" type="submit">
                                         <i class="fas {{ ($user->is_active ?? true) ? 'fa-ban' : 'fa-check' }}"></i>
                                     </button>
                                 </form>
                                 <button class="btn btn-sm btn-outline" type="button" onclick="showDeleteModal({{ $user->id }}, '{{ $user->name }}')" style="color: var(--danger);">
                                     <i class="fas fa-trash"></i>
                                 </button>
                             </div>
                         </td>
                     </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-users" style="font-size: 3rem; display: block; margin-bottom: 1rem; color: var(--text-muted);"></i>
                            <p>No users found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1.5rem;">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; inset: 0; z-index: 1000; align-items: center; justify-content: center; background: rgba(0,0,0,0.5);">
    <div style="background: var(--surface); border-radius: 12px; padding: 1.5rem; max-width: 400px; width: 90%; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <h3 style="margin-bottom: 0.5rem; font-size: 1.25rem;">Delete User</h3>
        <p style="color: var(--muted); margin-bottom: 1rem;">Are you sure you want to delete <strong id="deleteUserName"></strong>? This action cannot be undone.</p>
        <form id="deleteForm" method="POST" style="margin-bottom: 1rem;">
            @csrf
            @method('DELETE')
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Enter your admin password:</label>
            <input type="password" name="admin_password" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 1rem;" placeholder="Admin password">
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete User</button>
            </div>
        </form>
    </div>
</div>

<script>
function showDeleteModal(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = '/admin/users/' + userId;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

@endsection