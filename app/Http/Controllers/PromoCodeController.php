<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoCode;
use App\Models\AuditLog;

class PromoCodeController extends Controller {
    public function index() {
        $promos = PromoCode::latest()->paginate(15);
        return view('admin.promos.index', compact('promos'));
    }
    public function create() { return view('admin.promos.create'); }
    public function store(Request $request) {
        $request->validate(['code'=>'required|unique:promo_codes','discount_percent'=>'required|numeric|min:1|max:100','max_uses'=>'required|integer','expires_at'=>'nullable|date']);
        $p = PromoCode::create($request->only('code','discount_percent','max_uses','expires_at'));
        AuditLog::record('promo_create','Promo code created: '.$p->code);
        return redirect()->route('admin.promos.index')->with('success','Promo code created!');
    }
    public function destroy(PromoCode $promo) {
        AuditLog::record('promo_delete','Promo code deleted: '.$promo->code);
        $promo->delete();
        return back()->with('success','Deleted!');
    }
}