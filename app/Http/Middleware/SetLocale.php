<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Pick the language of the page.
     *
     * Order of precedence:
     *   1. the {locale} segment of the URL (/fr/..., /en/...),
     *   2. the `site_locale` cookie written by the language button (home.blade.php),
     *   3. config('app.locale').
     * Anything other than "fr" or "en" falls back to the default.
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
