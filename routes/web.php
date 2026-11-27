<?php

/*
|--------------------------------------------------------------------------
| URL map (read top to bottom: Laravel uses the first route that matches)
|--------------------------------------------------------------------------
|   GET  /                 French home page (no locale in the URL)
|   GET  /{fr|en}          home page in that language
|   GET  /{locale?}/cv     CV page
|   GET  /{locale?}/mentions  legal notice
|   POST /{locale?}/contact   contact form -> ContactController@send
|   GET  /{locale?}/{any}  anything else falls back to the home page
|
| The language itself is resolved by App\Http\Middleware\SetLocale
| (URL segment, then `site_locale` cookie, then config default).
|
| The site may be served under a sub-path in production (e.g. /portfolio):
| always build links with route() / asset(), never with hard-coded paths.
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// Serve root in dev as French homepage (keeps URL at http://127.0.0.1:8000)
Route::get('/', function () {
    app()->setLocale('fr');
    return view('home');
})->name('home.root');

// Compatibility redirects: accept `/en` or `/fr` at the *incoming* root and
// redirect to the same locale path while preserving any application base
// path (for example when the app is served under `/portfolio` by the host).
// Compatibility redirect block moved below the main route group so it does
// not greedily intercept non-locale paths (for example `/mentions`).

// Serve the site and accept an optional locale after that
Route::group([
    // Serve routes at the application root using an optional locale
    // so URLs are `/fr/...` or `/en/...` when the app is mounted at `/`.
    'prefix' => '{locale?}',
    'where' => ['locale' => 'en|fr'],
    'middleware' => [\App\Http\Middleware\SetLocale::class]
], function () {

    Route::post('/contact', [ContactController::class, 'send'])
        ->middleware('throttle:contact') // 3 messages / 10 min per IP (AppServiceProvider)
        ->name('contact.send');

    // Homepage is available at / and /{locale}
    // If no locale was provided in the URL (i.e. visiting `/`),
    // show the French homepage by default.
    Route::get('/', function () {
        $locale = request()->route('locale');
        if (empty($locale)) {
            app()->setLocale('fr');
            return view('home');
        }

        return view('home');
    })->name('home');

    Route::get('/cv', function () {
        return view('cv');
    })->name('cv');

    // Page mentions légales available at /mentions and /{locale}/mentions
    Route::get('/mentions', function () {
        return view('mentions');
    })->name('mentions');

    // Catch-all within the application root to serve the SPA-like pages or legacy paths
    // Keep this at the end so specific routes above take precedence.
    Route::get('/{any?}', function () {
        return view('home');
    })->where('any', '.*');
});

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

// Compatibility redirects: accept `/en` or `/fr` at the *incoming* root and
// redirect to the same locale path while preserving any application base
// path (for example when the app is served under `/portfolio` by the host).
Route::get('{locale}/{any?}', function (Request $request, $locale, $any = null) {
    if (!in_array($locale, ['en', 'fr'])) {
        abort(404);
    }
    // Preserve any base path (eg. '/portfolio') and redirect accordingly.
    // If the application is mounted at the root (base is empty) then return
    // the home view with the requested locale so `/en` works in dev.
    $base = rtrim($request->getBasePath(), '/');

    // Normalised request path (without query string)
    $currentPath = parse_url($request->getRequestUri(), PHP_URL_PATH) ?: '/';

    if (empty($base)) {
        App::setLocale($locale);
        return view('home');
    }

    // If the current request already contains the desired base+locale
    // (for example `/portfolio/en`), do not redirect — just serve the page
    // with the requested locale. This avoids producing `/portfolio/portfolio/...`.
    $expectedPrefix = $base . '/' . $locale;
    if (strpos($currentPath, $expectedPrefix) === 0) {
        App::setLocale($locale);
        return view('home');
    }

    // Otherwise redirect to the canonical path under the base.
    $target = $base . '/' . $locale . ($any ? '/' . $any : '');
    return redirect($target, 302);
})->where(['locale' => 'en|fr', 'any' => '.*']);
