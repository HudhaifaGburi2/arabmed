<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang')
            ?? $request->header('X-Locale')
            ?? $request->getPreferredLanguage(['ar', 'en'])
            ?? 'ar';
        App::setLocale(in_array($locale, ['ar','en']) ? $locale : 'ar');
        return $next($request);
    }
}
