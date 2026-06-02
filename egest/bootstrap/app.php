<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IdentifyTenant;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // --- LA CORRECTION TECHNIQUE ICI ---
        // On pousse le middleware au tout début (prepend) du groupe global 'web'
        // pour qu'il intercepte le sous-domaine AVANT l'authentification et les sessions.
        $middleware->web(prepend: [
            IdentifyTenant::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
