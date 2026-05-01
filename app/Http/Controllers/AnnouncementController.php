<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\AuditLog;

class AnnouncementController extends Controller {
    public function index() {
        $announcements = Announcement::with('admin')->latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }
    public function store(Request $request) {
        $request->validate(['title'=>'required|string','body'=>'required|string','target'=>'required|in:all,users,drivers']);
        $a = Announcement::create(['admin_id'=>auth()->id(),'title'=>strip_tags($request->title),'body'=>strip_tags($request->body),'target'=>$request->target]);
        AuditLog::record('announcement','Announcement posted: '.$a->title);
        return back()->with('success','Announcement posted!');
    }
    public function destroy(Announcement $announcement) {
        $announcement->delete();
        return back()->with('success','Deleted!');
    }
}