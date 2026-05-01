<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverEarningsController extends Controller
{
    // Earnings history (all deliveries)
    public function index()
    {
        $driver = Auth::user();
        $earnings = $driver->earnings()
            ->with('order.food')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_earned' => $driver->total_earnings,
            'available' => $driver->available_balance,
            'pending' => $driver->pending_balance,
            'deliveries_count' => $driver->earnings()->count(),
            'paid_count' => $driver->earnings()->where('status', 'paid')->sum('net_amount'),
        ];

        return view('driver.earnings.index', compact('earnings', 'stats'));
    }

    // Wallet / withdrawal page
    public function wallet()
    {
        $driver = Auth::user();
        $withdrawals = $driver->withdrawalRequests()
            ->latest()
            ->take(10)
            ->get();

        return view('driver.earnings.wallet', compact('driver', 'withdrawals'));
    }

    // Submit withdrawal request
    public function withdraw(Request $request)
    {
        $driver = Auth::user();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:100|max:'.$driver->available_balance,
            'payment_method' => 'required|string|in:bank_transfer,gcash,paymaya,cash',
            'payment_details' => 'required|string|max:255',
        ]);

        // Create withdrawal request
        $withdrawal = WithdrawalRequest::create([
            'driver_id' => $driver->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_details' => $validated['payment_details'],
            'status' => 'pending',
        ]);

        // Move amount from available to pending
        $driver->update([
            'available_balance' => $driver->available_balance - $validated['amount'],
            'pending_balance' => $driver->pending_balance + $validated['amount'],
        ]);

        // Notify admin (optional via AuditLog)
        AuditLog::record('withdrawal_request', "Driver {$driver->id} requested ₱".number_format($validated['amount'], 2));

        return back()->with('success', 'Withdrawal request submitted successfully.');
    }

    // List all withdrawal requests
    public function withdrawals()
    {
        $driver = Auth::user();
        $withdrawals = $driver->withdrawalRequests()
            ->latest()
            ->paginate(15);

        return view('driver.earnings.withdrawals', compact('withdrawals'));
    }
}
