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
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->web(append: [
            'throttle:30,1',
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);
        $middleware->redirectTo(
            guests: 'cendikiaByRidwanullah/login',
            users: '/cendikiaByRidwanullah'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
