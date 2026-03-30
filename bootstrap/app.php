<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\DebugCsrfToken::class,
            \App\Http\Middleware\RedirectPanelLoginMiddleware::class,
            \App\Http\Middleware\LogUserActivity::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'official' => \App\Http\Middleware\OfficialMiddleware::class,
            'resident' => \App\Http\Middleware\ResidentMiddleware::class,
            'guest.redirect' => \App\Http\Middleware\RedirectIfAuthenticatedToDashboard::class,
            'nocache' => \App\Http\Middleware\PreventBackHistory::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
