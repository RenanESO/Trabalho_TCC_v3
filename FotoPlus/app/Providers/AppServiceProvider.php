<?php

namespace App\Providers;

use App\Events\DeleteGoogleTokens;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
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
        Event::listen(
            Logout::class,
            function ($event) {
                Log::info('Logout event fired for user: ' . $event->user->id);
                (new DeleteGoogleTokens)();  // Chama seu evento
            }
        );
    }
}
