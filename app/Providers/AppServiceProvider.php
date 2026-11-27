<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Contact form: at most 3 messages per visitor (IP address) every 10 minutes,
        // so a script cannot flood the inbox or burn the SMTP quota. Over the limit, the
        // contact dialog reopens with the text kept and the "send it from your email
        // app" fallback, like a failed send (see ContactController and home.blade.php).
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinutes(10, 3)
                ->by($request->ip())
                ->response(fn () => back()
                    ->withInput()
                    ->with('error', __('contact.throttled'))
                    ->with('contactFailed', true)
                    ->with('contactThrottled', true));
        });
    }
}
