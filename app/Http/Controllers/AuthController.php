<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|digits:11',   // exactly 11 digits
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'role' => 'required|in:user,driver',
        ]);

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create user (unverified)
        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'is_active' => true,
            'email_verified_at' => null,
        ]);

        // Store OTP in user model
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        Mail::send('emails.otp', ['otp' => $otp, 'name' => $user->name], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP for KAS Delivery');
        });

        // Redirect to OTP verification page
        return redirect()->route('otp.form', ['email' => $user->email])
            ->with('success', 'Account created! Please verify your email with the OTP sent to '.$user->email);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'login:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors(['email' => "Too many attempts. Retry in {$seconds}s."]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && $user->isLocked()) {
            return back()->withErrors(['email' => 'Account is temporarily locked.']);
        }

        // **NO OTP CHECK HERE** – login only requires email/password
        // (Email verification is only needed during registration)
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            RateLimiter::clear($key);
            $user = Auth::user();
            $user->update([
                'login_attempts' => 0,
                'last_login_at' => now(),
                'locked_until' => null,
            ]);
            $request->session()->regenerate();
            AuditLog::record('login', 'User logged in: '.$user->email);

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'driver' => redirect()->route('driver.dashboard'),
                default => redirect()->route('user.dashboard'),
            };
        }

        RateLimiter::hit($key, 300);
        if ($user) {
            $user->increment('login_attempts');
            if ($user->login_attempts >= 5) {
                $user->update(['locked_until' => now()->addMinutes(15)]);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        AuditLog::record('logout', 'User logged out: '.auth()->user()->email);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
