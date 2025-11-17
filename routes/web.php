<?php

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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

// Serve the site under /portfolio and accept an optional locale after that
Route::group([
    'prefix' => 'portfolio/{locale?}',
    'where' => ['locale' => 'en|fr'],
    'middleware' => [\App\Http\Middleware\SetLocale::class]
], function () {

    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

    // Homepage is available at /portfolio and /portfolio/{locale}
    // If no locale was provided in the URL (i.e. visiting `/portfolio`),
    // show the French homepage without adding another `/portfolio` segment.
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

    // Page mentions légales available at /portfolio/mentions and /portfolio/{locale}/mentions
    Route::get('/mentions', function () {
        return view('mentions');
    })->name('mentions');

    // Catch-all within the /portfolio prefix to serve the SPA-like pages or legacy paths
    // Keep this at the end so specific routes above take precedence.
    Route::get('/{any?}', function () {
        return view('home');
    })->where('any', '.*');
});
