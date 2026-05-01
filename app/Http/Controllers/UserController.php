<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $greeting = $user ? 'User' : 'there';
        $cartCount = $user->cartItems()->count();
        $favoritesCount = $user->favorites()->count();
        $totalOrdersCount = $user->orders()->count();
        $activeOrdersCount = $user->orders()->whereIn('status', ['pending', 'confirmed', 'assigned', 'out_for_delivery'])->count();
        $totalSpent = $user->orders()->sum(DB::raw('COALESCE(total_price, 0)'));
        $orders = $user->orders()->with('food', 'driver')->latest()->paginate(5);
        $unread = $user->notifications()->where('is_read', false)->count();
        $unreadNotifications = $unread;
        $favoritesCount = $user->favorites()->count();
        $favorites = $user->favorites()->with('food')->latest()->take(5)->get();
        $activeOrder = $user->orders()->whereIn('status', ['pending', 'assigned', 'out_for_delivery'])->latest()->first();
        $recentOrders = $user->orders()->with('food', 'driver')->latest()->take(5)->get();

        return view('user.dashboard', compact('user', 'orders', 'unread', 'greeting', 'cartCount', 'favoritesCount', 'totalOrdersCount', 'activeOrdersCount', 'totalSpent', 'unreadNotifications', 'activeOrder', 'recentOrders', 'favorites'));
    }

    public function profile()
    {
        return view('user.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = ['name' => strip_tags($request->name), 'phone' => $request->phone];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $old = $user->only(array_keys($data));
        $user->update($data);
        AuditLog::record('profile_update', 'User updated profile', $old, $data);

        return back()->with('success', 'Profile updated!');
    }

    public function toggleDarkMode(Request $request)
    {
        auth()->user()->update(['dark_mode' => $request->dark_mode]);

        return response()->json(['ok' => true]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
