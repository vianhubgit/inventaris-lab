<?php

use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Custom role middleware alias: route('...')->middleware('role:admin')
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);

        // Trim & convert empty strings already default; keep default web stack.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
