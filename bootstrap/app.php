<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['api', 'auth:sanctum', 'perfil:admin'])->prefix('api/usuarios')->group(base_path('routes/api/usuarios.php'));
            Route::middleware(['api', 'auth:sanctum'])->prefix('api/periodos')->group(base_path('routes/api/periodos.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(HandleCors::class);
        $middleware->statefulApi();
        $middleware->alias(['perfil' => \App\Http\Middleware\VerificaPerfil::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
