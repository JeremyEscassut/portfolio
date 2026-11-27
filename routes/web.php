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
|   anything else          404 page (resources/views/errors/404.blade.php)
|
| The language itself is resolved by App\Http\Middleware\SetLocale
| (URL segment, then `site_locale` cookie, then config default).
|
| The site may be served under a sub-path in production (e.g. /portfolio):
| always build links with route() / asset(), never with hard-coded paths.
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// `/` is the French home page (canonical URL: /fr, see layouts/app.blade.php)
Route::get('/', function () {
    app()->setLocale('fr');
    return view('home');
})->name('home.root');

// Serve the site and accept an optional locale after that
Route::group([
    // Serve routes at the application root using an optional locale
    // so URLs are `/fr/...` or `/en/...` when the app is mounted at `/`.
    'prefix' => '{locale?}',
    'where' => ['locale' => 'en|fr'],
    'middleware' => [\App\Http\Middleware\SetLocale::class]
], function () {

    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

    // Home page at /{locale} (the language is set by SetLocale)
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/cv', function () {
        return view('cv');
    })->name('cv');

    // Page mentions légales available at /mentions and /{locale}/mentions
    Route::get('/mentions', function () {
        return view('mentions');
    })->name('mentions');
});
