<?php

namespace App\Http\Controllers;

use App\Events\DriverLocationUpdated;
use App\Models\AuditLog;
use App\Models\DriverEarning;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Driver dashboard with active orders, history, and stats.
     */
    public function dashboard()
    {
        $driver = Auth::user();

        // Active orders (assigned or out_for_delivery)
        $orders = $driver->driverOrders()
            ->with(['user', 'food'])
            ->whereIn('status', ['assigned', 'out_for_delivery'])
            ->get();

        // Delivery history (last 10 delivered)
        $history = $driver->driverOrders()
            ->with(['user', 'food'])
            ->where('status', 'delivered')
            ->latest()
            ->take(10)
            ->get();

        // Stats for the dashboard cards
        $totalDeliveries = $driver->driverOrders()->where('status', 'delivered')->count();
        $totalEarnings = round($driver->driverOrders()->where('status', 'delivered')->sum('total_price') * 0.1, 2);

        // Today's deliveries and earnings (using updated_at as fallback)
        $todayDeliveries = $driver->driverOrders()
            ->where('status', 'delivered')
            ->whereDate('updated_at', today())   // change delivered_at → updated_at
            ->count();

        $todayEarnings = round($driver->driverOrders()
            ->where('status', 'delivered')
            ->whereDate('updated_at', today())   // change delivered_at → updated_at
            ->sum('total_price') * 0.1, 2);

        // Average rating (if you have a reviews table with driver_id and rating)
        $avgRating = Review::where('driver_id', $driver->id)->avg('rating') ?? 0;

        // Last 7-day trend for charts
        $startDate = Carbon::today()->subDays(6);
        $dailyRows = $driver->driverOrders()
            ->where('status', 'delivered')
            ->whereDate('updated_at', '>=', $startDate)
            ->selectRaw('DATE(updated_at) as day, COUNT(*) as deliveries, SUM(total_price) as revenue')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $trendLabels = [];
        $trendDeliveries = [];
        $trendEarnings = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startDate->copy()->addDays($i);
            $dayKey = $day->toDateString();
            $row = $dailyRows->get($dayKey);

            $trendLabels[] = $day->format('M d');
            $trendDeliveries[] = (int) ($row->deliveries ?? 0);
            $trendEarnings[] = round(((float) ($row->revenue ?? 0)) * 0.1, 2);
        }

        // Google Maps API key (for embedded maps if needed)
        $mapsKey = config('services.google_maps.key');

        return view('driver.dashboard', compact(
            'driver', 'orders', 'history',
            'totalDeliveries', 'totalEarnings',
            'todayDeliveries', 'todayEarnings',
            'avgRating', 'mapsKey',
            'trendLabels', 'trendDeliveries', 'trendEarnings'
        ));
    }

    /**
     * Accept an assigned order.
     */
    public function acceptOrder(Order $order)
    {
        $order->update([
            'status' => 'out_for_delivery',
            'accepted_at' => now(),          // needed for delivery timer
        ]);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Driver On The Way!',
            'body' => 'Your order is out for delivery.',
            'type' => 'order',
        ]);

        AuditLog::record('order_accepted', "Driver accepted Order #{$order->id}");

        return response()->json(['ok' => true, 'status' => 'out_for_delivery']);
    }

    /**
     * Reject an assigned order (free it for other drivers).
     */
    public function rejectOrder(Order $order)
    {
        $order->update([
            'status' => 'pending',
            'driver_id' => null,
        ]);

        AuditLog::record('order_rejected', "Driver rejected Order #{$order->id}");

        return response()->json(['ok' => true]);
    }

    /**
     * Mark an order as delivered, optionally with a photo proof.
     */
    public function markDelivered(Request $request, Order $order)
    {
        $data = [
            'status' => 'delivered',
            'delivered_at' => now(),
        ];

        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('proofs', 'public');
            $data['delivery_proof'] = $path;
        }

        $order->update($data);

        // Create earnings record for driver (20% commission)
        $driver = Auth::user();
        $orderAmount = $order->total_price;
        $commissionPercent = 20;
        $commissionAmount = $orderAmount * ($commissionPercent / 100);
        $netAmount = $orderAmount - $commissionAmount;

        DriverEarning::create([
            'driver_id' => $driver->id,
            'order_id' => $order->id,
            'order_amount' => $orderAmount,
            'commission_percent' => $commissionPercent,
            'commission_amount' => $commissionAmount,
            'net_amount' => $netAmount,
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => 'wallet',
        ]);

        // Update driver's earnings balance
        $driver->increment('total_earnings', $netAmount);
        $driver->increment('available_balance', $netAmount);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Delivered!',
            'body' => 'Your food has been delivered!',
            'type' => 'order',
        ]);

        AuditLog::record('order_delivered', "Order #{$order->id} delivered");

        return response()->json(['ok' => true]);
    }

    /**
     * Update driver's live location for a specific order.
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'order_id' => 'required|exists:orders,id',
        ]);

        $updated = Order::where('id', $request->order_id)
            ->where('driver_id', auth()->id())
            ->update([
                'driver_latitude' => $request->lat,
                'driver_longitude' => $request->lng,
            ]);

        if ($updated) {
            broadcast(new DriverLocationUpdated($request->order_id, $request->lat, $request->lng));
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Toggle driver availability (online / offline).
     */
    public function toggleAvailability()
    {
        $driver = auth()->user();
        $driver->is_available = ! $driver->is_available;
        $driver->save();

        return back()->with('success', 'You are now '.($driver->is_available ? 'online' : 'offline'));
    }
}
