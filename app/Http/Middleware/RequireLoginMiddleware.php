<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Simpan URL yang diminta untuk redirect setelah login
            $request->session()->put('url.intended', $request->fullUrl());
            
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk melanjutkan.');
        }

        return $next($request);
    }
}