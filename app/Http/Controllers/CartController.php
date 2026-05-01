<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Food;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('food')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->food->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate(['food_id' => 'required|exists:foods,id']);

        $food = Food::findOrFail($request->food_id);

        $cart = Cart::where('user_id', auth()->id())
            ->where('food_id', $food->id)
            ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'food_id' => $food->id,
                'quantity' => 1,
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'Added to cart!']);
        }

        return back()->with('success', 'Added to cart!');
    }

    public function update(Cart $cart, Request $request)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $action = $request->input('action');
        $quantity = $request->input('quantity', $cart->quantity);

        if ($action === 'increase') {
            $cart->increment('quantity');
        } elseif ($action === 'decrease' && $cart->quantity > 1) {
            $cart->decrement('quantity');
        } elseif ($request->has('quantity') && $request->quantity > 0) {
            $cart->update(['quantity' => $request->quantity]);
        }

        return back()->with('success', 'Cart updated!');
    }

    public function remove(Cart $cart)
    {
        $cart->delete();

        return back();
    }

    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json(['count' => $count]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())->with('food')->get();
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->food->price * $item->quantity;
        });
        $deliveryFee = 50.00;
        $discount = 0;

        // Validate promo code
        if ($request->promo_code) {
            $promo = PromoCode::where('code', strtoupper($request->promo_code))->first();
            if ($promo && $promo->isValid()) {
                $discount = $subtotal * ($promo->discount_percent / 100);
                $promo->increment('used_count');
            } else {
                return back()->with('error', 'Invalid or expired promo code.');
            }
        }

        $total = $subtotal + $deliveryFee - $discount;

        // Calculate estimated arrival based on the longest prep time
        $maxPrepTime = $cartItems->max(function ($item) {
            return $item->food->prep_time ?? 15;
        });

        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),
            'food_id' => $cartItems->first()->food_id, // Primary food for compatibility
            'delivery_address' => strip_tags($request->delivery_address),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'quantity' => $cartItems->sum('quantity'),
            'total_price' => $total,
            'promo_code' => $request->promo_code,
            'discount' => $discount,
            'estimated_arrival' => Carbon::now()->addMinutes(($maxPrepTime ?? 15) + 20),
            'special_instructions' => strip_tags($request->special_instructions ?? ''),
        ]);

        // Create order items for each cart item
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $cartItem->food_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->food->price,
            ]);
        }

        // Clear the cart
        Cart::where('user_id', auth()->id())->delete();

        // Create notification
        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Order Placed!',
            'body' => 'Your order has been placed successfully.',
            'type' => 'order',
        ]);

        return redirect()->route('user.orders.show', $order)->with('success', 'Order placed successfully!');
    }
}
