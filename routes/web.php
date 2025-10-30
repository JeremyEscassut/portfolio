<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/', function () {
    return view('home');
});

Route::get('/cv', function () {
    return view('cv');
});

// Safety fallback when the app is served under a subdirectory (e.g. /portfolio/)
// Some hosts forward the subpath to Laravel which then sees `/portfolio` as the
// requested path. Provide a GET fallback so the homepage is served correctly.
Route::get('/portfolio/{any?}', function () {
    return view('home');
})->where('any', '.*');
