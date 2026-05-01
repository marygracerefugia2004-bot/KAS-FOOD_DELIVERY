<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class LoginThrottleMiddleware {
    public function handle(Request $request, Closure $next): mixed {
        if ($request->isMethod('post') && str_contains($request->path(), 'login')) {
            $user = User::where('email', $request->email)->first();
            if ($user && $user->isLocked()) {
                return back()->withErrors(['email' => 'Account locked. Try again after ' . $user->locked_until->format('H:i')]);
            }
        }
        return $next($request);
    }
}