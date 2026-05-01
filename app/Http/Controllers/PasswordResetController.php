<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to user's email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate reset token
        $token = Str::random(64);
        
        // Store token in password_resets table (Laravel default)
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // Generate reset URL
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // Send email with reset link
        Mail::send('emails.password-reset', [
            'name' => $user->name,
            'resetUrl' => $resetUrl,
            'token' => $token,
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Reset Your KAS Delivery Password');
        });

        return back()->with('success', 'Password reset link sent to your email!');
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        // Verify token exists
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired reset token.');
        }

        // Check if token is expired (30 minutes)
        if ($resetRecord->created_at->diffInMinutes(now()) > 30) {
            return redirect()->route('login')
                ->with('error', 'Reset token has expired. Please request a new one.');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Reset the user's password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Verify token
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->with('error', 'Invalid reset token.');
        }

        // Check if token is expired
        if ($resetRecord->created_at->diffInMinutes(now()) > 30) {
            return back()->with('error', 'Reset token has expired. Please request a new one.');
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the used token
        \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Log audit
        \App\Models\AuditLog::record('password_reset', 'Password reset for: '.$user->email);

        return redirect()->route('login')
            ->with('success', 'Password reset successful! Please login with your new password.');
    }
}