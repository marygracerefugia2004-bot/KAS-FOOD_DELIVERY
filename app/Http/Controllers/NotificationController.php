<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        $notifs = auth()->user()->notifications()->latest()->paginate(20);
        return view('shared.notifications', compact('notifs'));
    }
    
    public function count() {
        return response()->json(['count' => auth()->user()->notifications()->where('is_read', false)->count()]);
    }
    
    public function markAllAsRead() {
        auth()->user()->notifications()->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read!');
    }
    
    public function markAsRead(Notification $notification) {
        $notification->update(['is_read' => true]);
        return back()->with('success', 'Notification marked as read!');
    }
}