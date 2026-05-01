<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Order;

class RatingController extends Controller {
    public function store(Request $request, Order $order) {
        $request->validate(['stars' => 'required|integer|min:1|max:5', 'review' => 'nullable|string|max:1000']);
        Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'order_id' => $order->id],
            ['food_id' => $order->food_id, 'stars' => $request->stars, 'review' => strip_tags($request->review ?? '')]
        );
        return back()->with('success', 'Rating submitted!');
    }
}