<?php

namespace App\Http\Controllers;

use App\Models\VehicleMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverVehicleController extends Controller
{
    public function index()
    {
        $driver = Auth::user();
        $maintenances = $driver->vehicleMaintenances()
            ->latest()
            ->paginate(15);

        $stats = [
            'last_service' => $driver->last_service_date,
            'next_service_due' => $driver->next_service_due,
            'last_service_km' => $driver->last_service_km,
            'service_interval_km' => $driver->service_interval_km,
            'total_maintenance_cost' => $driver->vehicleMaintenances()->sum('cost'),
        ];

        return view('driver.vehicle.index', compact('maintenances', 'stats'));
    }

    public function store(Request $request)
    {
        $driver = Auth::user();

        $validated = $request->validate([
            'service_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'odometer_km' => 'nullable|integer|min:0',
            'service_date' => 'required|date',
            'next_service_due' => 'nullable|date|after_or_equal:service_date',
            'service_center' => 'nullable|string|max:150',
            'receipt_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('receipt_image')) {
            $path = $request->file('receipt_image')->store('receipts', 'public');
            $validated['receipt_image'] = $path;
        }

        $maintenance = $driver->vehicleMaintenances()->create($validated);

        // Update user's last_service info since this is the newest record
        $driver->update([
            'last_service_date' => $maintenance->service_date,
            'last_service_km' => $maintenance->odometer_km,
            'next_service_due' => $maintenance->next_service_due,
        ]);

        return back()->with('success', 'Maintenance record added.');
    }

    public function edit(VehicleMaintenance $maintenance)
    {
        if ($maintenance->driver_id !== Auth::id()) {
            abort(403);
        }

        return view('driver.vehicle.edit', compact('maintenance'));
    }

    public function update(Request $request, VehicleMaintenance $maintenance)
    {
        if ($maintenance->driver_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'service_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'odometer_km' => 'nullable|integer|min:0',
            'service_date' => 'required|date',
            'next_service_due' => 'nullable|date|after_or_equal:service_date',
            'service_center' => 'nullable|string|max:150',
            'receipt_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('receipt_image')) {
            if ($maintenance->receipt_image) {
                \Storage::disk('public')->delete($maintenance->receipt_image);
            }
            $validated['receipt_image'] = $request->file('receipt_image')->store('receipts', 'public');
        }

        $maintenance->update($validated);

        return redirect()->route('driver.vehicle.index')->with('success', 'Record updated.');
    }

    public function destroy(VehicleMaintenance $maintenance)
    {
        if ($maintenance->driver_id !== Auth::id()) {
            abort(403);
        }

        if ($maintenance->receipt_image) {
            \Storage::disk('public')->delete($maintenance->receipt_image);
        }

        $maintenance->delete();

        return back()->with('success', 'Record deleted.');
    }
}
