<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportChatController extends Controller
{
    public function index()
    {
        $driver = Auth::user();

        $messages = SupportMessage::with('admin')
            ->where('driver_id', $driver->id)
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        SupportMessage::where('driver_id', $driver->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $admins = User::where('role', 'admin')->get();

        return view('driver.support.index', compact('messages', 'admins'));
    }

    public function send(Request $request)
    {
        $driver = Auth::user();

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'admin_id' => 'nullable|exists:users,id',
        ]);

        if (empty($validated['admin_id'])) {
            $admin = User::where('role', 'admin')->first();
            $validated['admin_id'] = $admin ? $admin->id : null;
        }

        SupportMessage::create([
            'driver_id' => $driver->id,
            'admin_id' => $validated['admin_id'],
            'direction' => 'driver_to_admin',
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Message sent to support.');
    }
}
