<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Administrative credentials required.'
                ], 403);
            }
            return redirect()->route('login')->with('error', 'Administrator access required. Please sign in with an admin account.');
        }

        return $next($request);
    }
}
