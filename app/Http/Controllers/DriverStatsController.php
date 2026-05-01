<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DriverStatsController extends Controller
{
    public function index()
    {
        $driver = Auth::user();

        $totalDeliveries = $driver->driverOrders()->where('status', 'delivered')->count();
        $totalEarnings = $driver->total_earnings;
        $availableBalance = $driver->available_balance;
        $pendingBalance = $driver->pending_balance;

        $avgRating = $driver->ratings()->avg('delivery_rating') ?: 0;
        $ratingsCount = $driver->ratings()->count();

        $deliveredOrders = $driver->driverOrders()
            ->where('status', 'delivered')
            ->whereNotNull('delivered_at')
            ->whereNotNull('estimated_arrival')
            ->get();

        $onTimeCount = 0;
        foreach ($deliveredOrders as $order) {
            if ($order->delivered_at && $order->estimated_arrival && $order->delivered_at->lte($order->estimated_arrival)) {
                $onTimeCount++;
            }
        }
        $onTimeRate = $deliveredOrders->count() > 0 ? round(($onTimeCount / $deliveredOrders->count()) * 100, 1) : 0;

        $weeklyEarnings = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            $amount = $driver->earnings()
                ->where('status', 'paid')
                ->whereBetween('paid_at', [$weekStart, $weekEnd])
                ->sum('net_amount');
            $weeklyEarnings[] = [
                'week' => $weekStart->format('M d'),
                'amount' => $amount,
            ];
        }

        $dailyEarnings = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->toDateString();
            $amount = $driver->earnings()
                ->where('status', 'paid')
                ->whereDate('paid_at', $day)
                ->sum('net_amount');
            $dailyEarnings[] = [
                'date' => $day,
                'amount' => $amount,
            ];
        }

        $bestDay = collect($dailyEarnings)->sortByDesc('amount')->first();
        $bestDayAmount = $bestDay ? $bestDay['amount'] : 0;

        $stats = compact(
            'totalDeliveries', 'totalEarnings', 'availableBalance', 'pendingBalance',
            'avgRating', 'ratingsCount', 'onTimeRate', 'bestDayAmount'
        );

        return view('driver.stats.index', compact('stats', 'weeklyEarnings', 'dailyEarnings'));
    }
}
