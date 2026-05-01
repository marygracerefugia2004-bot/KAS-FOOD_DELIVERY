<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller {
    public function toggle(Request $request) {
        $request->validate(['food_id' => 'required|exists:foods,id']);
        $fav = Favorite::where('user_id', auth()->id())->where('food_id', $request->food_id)->first();
        if ($fav) { $fav->delete(); $status = 'removed'; }
        else { Favorite::create(['user_id' => auth()->id(), 'food_id' => $request->food_id]); $status = 'added'; }
        return response()->json(['status' => $status]);
    }
    public function index() {
        $favorites = auth()->user()->favorites()->with('food')->paginate(12);
        return view('user.favorites', compact('favorites'));
    }
}