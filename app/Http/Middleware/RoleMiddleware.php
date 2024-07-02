<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->role === 'admin') {
            // Allow admin access
            return $next($request);
        } elseif ($user->role === 'moderator') {
            // Allow moderator access
            return $next($request);
        } else {
            // Deny access for users with other roles
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
