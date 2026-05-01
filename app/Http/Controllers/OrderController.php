<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Food;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Order;
use App\Models\PromoCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Food $food)
    {
        return view('user.order-create', compact('food'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'delivery_address' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        $food = Food::findOrFail($request->food_id);
        $price = $food->price * $request->quantity;
        $discount = 0;

        if ($request->promo_code) {
            $promo = PromoCode::where('code', strtoupper($request->promo_code))->first();
            if ($promo && $promo->isValid()) {
                $discount = $price * ($promo->discount_percent / 100);
                $promo->increment('used_count');
            }
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'food_id' => $food->id,
            'delivery_address' => strip_tags($request->delivery_address),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'quantity' => $request->quantity,
            'total_price' => $price - $discount,
            'promo_code' => $request->promo_code,
            'discount' => $discount,
            'estimated_arrival' => Carbon::now()->addMinutes($food->prep_time + 20),
            'special_instructions' => strip_tags($request->special_instructions ?? ''),
        ]);

        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Order Placed!',
            'body' => "Your order for {$food->name} has been placed.",
            'type' => 'order',
        ]);

        AuditLog::record('order_create', "Order #{$order->id} placed for food: {$food->name}", null, $order->toArray());

        return redirect()->route('user.orders.show', $order)->with('success', 'Order placed!');
    }

    public function show(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load('items.food', 'driver');
        $orderMessages = collect();

        if ($order->driver_id) {
            $orderMessages = Message::with('sender')
                ->where(function ($query) use ($order) {
                    $query->where('sender_id', $order->user_id)
                        ->where('receiver_id', $order->driver_id);
                })
                ->orWhere(function ($query) use ($order) {
                    $query->where('sender_id', $order->driver_id)
                        ->where('receiver_id', $order->user_id);
                })
                ->orderBy('created_at')
                ->get();
        }

        $mapsKey = config('services.google_maps.key');

        return view('user.order-show', compact('order', 'mapsKey', 'orderMessages'));
    }

    public function history(Request $request)
    {
        $query = auth()->user()->orders()->with('food', 'rating');

        if ($request->status === 'active') {
            $query->whereIn('status', ['pending', 'preparing', 'ready', 'assigned', 'out_for_delivery']);
        } elseif ($request->status === 'completed') {
            $query->whereIn('status', ['delivered', 'completed']);
        } elseif ($request->status === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $orders = $query->latest()->paginate(10);

        return view('user.order-history', compact('orders'));
    }

    public function reorder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (! $order->food) {
            return back()->with('error', 'Food item no longer available.');
        }

        $newOrder = Order::create([
            'user_id' => auth()->id(),
            'food_id' => $order->food_id,
            'delivery_address' => $order->delivery_address,
            'quantity' => $order->quantity,
            'total_price' => $order->food->price * $order->quantity,
            'special_instructions' => $order->special_instructions,
            'estimated_arrival' => Carbon::now()->addMinutes($order->food->prep_time + 20),
        ]);

        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Order Reordered!',
            'body' => "Your order for {$order->food->name} has been reordered.",
            'type' => 'order',
        ]);

        return redirect()->route('user.orders.show', $newOrder)->with('success', 'Order reordered successfully!');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (! in_array($order->status, ['pending', 'preparing'])) {
            return back()->with('error', 'Order cannot be cancelled at this stage.');
        }

        $old = $order->toArray();
        $order->update(['status' => 'cancelled']);

        AuditLog::record('order_cancel', "Order #{$order->id} cancelled by user", $old, $order->fresh()->toArray());

        return redirect()->route('user.orders.history')->with('success', 'Order cancelled successfully.');
    }

    public function pdf(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('food', 'user', 'driver');
        $pdf = Pdf::loadView('user.order-pdf', compact('order'));

        return $pdf->download("receipt-order-{$order->id}.pdf");
    }

    // Admin
    public function adminIndex()
    {
        $orders = Order::with('user', 'food', 'driver')->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function assignDriver(Request $request, Order $order)
    {
        $request->validate(['driver_id' => 'required|exists:users,id']);
        $old = $order->toArray();
        $order->update([
            'driver_id' => $request->driver_id,
            'status' => 'assigned',
        ]);
        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Driver Assigned',
            'body' => 'A driver has been assigned to your order.',
            'type' => 'order',
        ]);
        AuditLog::record('driver_assign', "Driver {$request->driver_id} assigned to Order #{$order->id}", $old, $order->fresh()->toArray());

        return back()->with('success', 'Driver assigned!');
    }
}
