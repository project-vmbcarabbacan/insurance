<?php

use App\Shared\Domain\Exceptions\ApplicationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // GLOBAL middleware (all requests)
        $middleware->use([
            // ...
        ]);

        // API middleware group
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Global render callback
        $exceptions->render(function (ApplicationException $e, Request $request) {
            return response()->json(
                $e->toArray(),
                $e->statusCode()
            );
        });

        // Optionally handle other exceptions
        $exceptions->render(function (\Throwable $e, Request $request) {
            return response()->json([
                'error' => 'SERVER_ERROR',
                'message' => $e->getMessage(),
            ], 500);
        });
    })->create();
