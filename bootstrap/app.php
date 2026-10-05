<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function () {
            Route::middleware('api')
                ->prefix('api/dropdown')
                ->group(base_path('routes/dropdown.php'));
        },
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.verify' => JwtMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        /*
         * Render API exceptions as JSON
         */
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*')
        );

        /*
         * 404 - Route / resource not found
         */
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'No item found!',
                    'errors' => $e->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            }
        });

        /*
         * 405 - HTTP method not allowed
         */
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $allowedMethods = $e->getHeaders()['Allow'] ?? null;

                return response()->json([
                    'status' => false,
                    'message' => 'The requested HTTP method is not allowed.',
                    'errors' => [
                        'method' => [
                            "Method {$request->method()} is not allowed for this endpoint.",
                        ],
                        'allowed_methods' => $allowedMethods,
                    ],

                ], Response::HTTP_METHOD_NOT_ALLOWED);
            }
        });
    })
    ->create();
