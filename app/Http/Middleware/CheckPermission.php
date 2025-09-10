<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $hasPermission = $user->roles()
            ->whereHas('permissions', function ($q) use ($permissions) {
                $q->whereIn('name', $permissions);
            })
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Forbidden: insufficient permission');
        }

        return $next($request);
    }
}
