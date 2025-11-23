<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     * Allow both 'admin' and 'user' roles to access.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check if user has either 'admin' or 'user' role
        if (!auth()->user()->hasAnyRole(['admin', 'user']) && !in_array(auth()->user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
