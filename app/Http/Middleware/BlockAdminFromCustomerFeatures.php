<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockAdminFromCustomerFeatures
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            // For AJAX requests, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Access Denied',
                    'message' => 'Admin tidak dapat mengakses fitur customer.',
                    'redirect_url' => route('dashboard')
                ], 403);
            }
            
            // For regular requests, redirect to admin dashboard
            return redirect()->route('dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur customer. Anda telah diarahkan ke dashboard admin.');
        }

        return $next($request);
    }
}