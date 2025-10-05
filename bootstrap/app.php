<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'web.role' => \App\Http\Middleware\EnsureWebRole::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'pimpinan' => \App\Http\Middleware\EnsurePimpinanRole::class,
            'operator' => \App\Http\Middleware\EnsureOperatorRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
