<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // $user = Auth::user();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Kama user ana role yoyote kati ya zilizotajwa
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Kama hana permission
        abort(403, 'Unauthorized. You do not have the required role.');
    }
}