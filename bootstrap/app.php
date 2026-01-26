<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiAuth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Illuminate\Support\Facades\Route::middleware('web')
            ->prefix('admin')
            ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
          $middleware->alias([
        'auth.api' => ApiAuth::class,
    ]);
    
        $middleware->alias([
    'auth.token' => \App\Http\Middleware\AuthTokenMiddleware::class,
    ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
