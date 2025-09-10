<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $hasRole = $user->roles()->whereIn('name', $roles)->exists();
        if (!$hasRole) {
            abort(403, 'Forbidden: insufficient role');
        }

        return $next($request);
    }
}
