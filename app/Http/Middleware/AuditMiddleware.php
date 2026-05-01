<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditMiddleware {
    public function handle(Request $request, Closure $next): mixed {
        $response = $next($request);
        if (auth()->check() && $request->isMethod('post')) {
            AuditLog::record(
                'page_access',
                'Accessed: ' . $request->path() . ' [POST]',
            );
        }
        return $response;
    }
}