<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('driver.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'vehicle_type' => 'nullable|string|in:motorcycle,car,bicycle',
            'vehicle_model' => 'nullable|string|max:100',
            'license_plate' => 'nullable|string|max:20',
            'driver_license_number' => 'nullable|string|max:50',
            'driver_license_expiry' => 'nullable|date|after_or_equal:today',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
        ]);

        if (!empty($validated['license_plate'])) {
            $validated['license_plate'] = strtoupper(trim($validated['license_plate']));
        }

        if (!empty($validated['driver_license_number'])) {
            $validated['driver_license_number'] = strtoupper(trim($validated['driver_license_number']));
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
