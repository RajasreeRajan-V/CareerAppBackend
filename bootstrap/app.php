<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\ApiAuth;
use App\Http\Middleware\AuthTokenMiddleware;
use App\Http\Middleware\CollegePasswordChanged;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {

            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->prefix('college')
                ->group(base_path('routes/college.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.api'                 => ApiAuth::class,
            'auth.token'               => AuthTokenMiddleware::class,
            'college.password_changed' => CollegePasswordChanged::class,
        ]);

        // Add this line
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('college/*')) {
                return url('collegelogin');

            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
