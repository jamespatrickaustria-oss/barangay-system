<?php

namespace App\Providers;

use App\Services\ImageUploadService;
use App\Services\UserActivityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
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
        app(ImageUploadService::class)->ensureDirectoriesExist();

        Event::listen(Login::class, function (Login $event): void {
            UserActivityLogger::log(
                'Logged in',
                request(),
                $event->user,
                ['guard' => $event->guard, 'remember' => $event->remember]
            );
        });

        Event::listen(Logout::class, function (Logout $event): void {
            UserActivityLogger::log(
                'Logged out',
                request(),
                $event->user,
                ['guard' => $event->guard]
            );
        });

        Event::listen(Failed::class, function (Failed $event): void {
            UserActivityLogger::log(
                'Failed login attempt',
                request(),
                $event->user,
                [
                    'guard' => $event->guard,
                    'email' => $event->credentials['email'] ?? null,
                ]
            );
        });
    }
}
