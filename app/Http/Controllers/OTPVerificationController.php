<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OTPVerificationController extends Controller
{
    public function showVerifyForm($email)
    {
        return view('auth.verify-otp', compact('email'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if email is already verified first
        if ($user->email_verified_at) {
            return redirect()->route('login')->with('message', 'Email already verified. Please login.');
        }

        // Check OTP exists and matches first, then check expiry
        if (! $user->otp || $user->otp != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please request a new one.']);
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Expired OTP. Please request a new one.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return redirect()->route('login')->with('success', 'Email verified! You can now log in.');
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();

        // Check if email is already verified first
        if ($user->email_verified_at) {
            return redirect()->route('login')->with('message', 'Email already verified.');
        }

        // Check if existing OTP is still valid before generating new one
        $existingOtp = $user->otp;
        $existingExpires = $user->otp_expires_at;
        $canResend = ! $existingOtp || now()->gt($existingExpires);

        if ($canResend) {
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ]);
        } else {
            // Extend existing OTP expiry instead of generating new one
            $otp = $existingOtp;
            $user->update(['otp_expires_at' => now()->addMinutes(10)]);
        }

        Mail::send('emails.otp', ['otp' => $otp, 'name' => $user->name], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP for KAS Delivery');
        });

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}
