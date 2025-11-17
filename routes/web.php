<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// Wrap existing routes in an optional locale prefix and apply the SetLocale middleware
Route::group([
    'prefix' => '{locale?}',
    'where' => ['locale' => 'en|fr'],
    'middleware' => [\App\Http\Middleware\SetLocale::class]
], function () {

    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/cv', function () {
        return view('cv');
    })->name('cv');

    // Page mentions légales
    Route::get('/mentions', function () {
        return view('mentions');
    })->name('mentions');

    // Safety fallback when the app is served under a subdirectory (e.g. /portfolio/)
    // Some hosts forward the subpath to Laravel which then sees `/portfolio` as the
    // requested path. Provide a GET fallback so the homepage is served correctly.
    Route::get('/portfolio/{any?}', function () {
        return view('home');
    })->where('any', '.*');
});
