<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller {
    public function index(Request $request) {
        $logs = AuditLog::with('user')->latest('created_at');
        if ($request->action)  { $logs->where('action', $request->action); }
        if ($request->user_id) { $logs->where('user_id', $request->user_id); }
        if ($request->date)    { $logs->whereDate('created_at', $request->date); }
        $logs = $logs->paginate(30);
        return view('admin.audit-logs', compact('logs'));
    }
}