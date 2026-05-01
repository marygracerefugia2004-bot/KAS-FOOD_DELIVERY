<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Food;
use App\Models\AuditLog;
use App\Models\Announcement;

class AdminController extends Controller {

    public function dashboard() {
        $stats = [
            'users'          => User::where('role','user')->count(),
            'drivers'        => User::where('role','driver')->count(),
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('status','pending')->count(),
            'live_orders'    => Order::whereIn('status',['assigned','out_for_delivery'])->count(),
            'total_foods'    => Food::count(),
            'revenue'        => Order::where('status','delivered')->sum('total_price'),
        ];
        $recentOrders = Order::with('user','food','driver')->latest()->take(5)->get();
        $announcements = Announcement::latest()->take(3)->get();
        return view('admin.dashboard', compact('stats','recentOrders','announcements'));
    }

    public function users() {
        $users = User::where('role','user')->latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function drivers() {
        $drivers = User::where('role','driver')->latest()->paginate(15);
        return view('admin.drivers', compact('drivers'));
    }

    public function toggleActive(User $user) {
        $user->update(['is_active' => !$user->is_active]);
        AuditLog::record('user_toggle', "User {$user->id} active set to " . ($user->is_active ? '1':'0'));
        return back()->with('success', 'Status updated!');
    }

    public function monitor() {
        $activeUsers   = User::where('role','user')->where('is_active',true)->count();
        $activeDrivers = User::where('role','driver')->where('is_active',true)->count();
        $liveOrders    = Order::whereIn('status',['assigned','out_for_delivery'])->with('user','driver','food')->get();
        return view('admin.monitor', compact('activeUsers','activeDrivers','liveOrders'));
    }

    public function suspicious() {
        $logs = AuditLog::where('action','login')
            ->selectRaw('ip_address, count(*) as attempts, max(created_at) as last_seen')
            ->groupBy('ip_address')
            ->having('attempts', '>', 10)
            ->orderByDesc('attempts')
            ->paginate(20);
        return view('admin.suspicious', compact('logs'));
    }

    public function reports() {
        $monthly = Order::where('status','delivered')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as revenue, COUNT(*) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->take(12)->get();
        return view('admin.reports', compact('monthly'));
    }

    public function createDriver()
{
    return view('admin.drivers-create');
}

public function storeDriver(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
        'phone' => 'nullable|string',
    ]);
    
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'phone' => $request->phone,
        'role' => 'driver',
        'is_active' => true,
    ]);
    
    return redirect()->route('admin.drivers')->with('success', 'Driver added successfully!');
}

public function toggleDriver(User $user)
{
    $user->update(['is_active' => !$user->is_active]);
    return back()->with('success', 'Driver status updated!');
}

    public function destroyUser(Request $request, User $user)
    {
        $request->validate([
            'admin_password' => 'required|string',
        ]);

        // Verify admin password
        $admin = auth()->user();
        if (!Hash::check($request->admin_password, $admin->password)) {
            return back()->with('error', 'Invalid admin password.');
        }

        // Prevent self-deletion
        if ($user->id === $admin->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userEmail = $user->email;
        $user->delete();

        AuditLog::record('user_delete', "User {$userEmail} deleted by admin {$admin->email}");
        return back()->with('success', 'User deleted successfully!');
    }
}