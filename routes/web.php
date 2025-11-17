<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// Serve root in dev as French homepage (keeps URL at http://127.0.0.1:8000)
Route::get('/', function () {
    app()->setLocale('fr');
    return view('home');
})->name('home.root');

// Compatibility redirects: accept /en or /fr (and their subpaths) at the site root
// and redirect them under the `/portfolio/{locale}` prefix. This helps local
// development where the app may be served at the domain root while production
// expects `/portfolio/{locale}`.
Route::get('{locale}/{any?}', function ($locale, $any = null) {
    if (!in_array($locale, ['en', 'fr'])) {
        abort(404);
    }

    $target = '/portfolio/' . $locale . ($any ? '/' . $any : '');
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
