<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Determine locale: route parameter -> cookie -> app default
        $locale = $request->route('locale') ?? $request->cookie('site_locale') ?? config('app.locale');

        // sanitize: only allow en/fr for now
        if (!in_array($locale, ['en', 'fr'])) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
