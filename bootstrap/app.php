<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use PDOException;
use Psr\Log\LogLevel;
//use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Cache\RateLimiting\Limit;
//use Throwable;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->statefulApi();
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        // $exceptions->level(PDOException::class, LogLevel::CRITICAL);
        $exceptions->dontReportDuplicates();
        $exceptions->dontReport([
            //InvalidOrderException::class,
        ]);

        // $exceptions->throttle(function (Throwable $e) {
        //     // if ($e instanceof BroadcastException) {
        //     //     return Limit::perMinute(300)->by($e->getMessage());
        //     // }
        // });

        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.',
                    // 'errors' => '404',
                    // 'message' => $e->getMessage(),
                    'errors' => (string)$e->getStatusCode(),
                    'exception' => $e->getMessage()
                ], 405)->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        }); 

        // $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'message' => 'Record not found.',
        //             'errors' => '404',
        //             //'exception' => $e->getMessage()
        //         ], 404)->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
        //     }
        // });

        //
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => '401',
                    //'exception' => $e->getMessage()
                ], 403)->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        });

        //
        $exceptions->renderable(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => '403',
                    //'exception' => $e->getMessage()
                ], 403)->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        });

        //
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.',
                    'errors' => '404',
                    //'exception' => $e->getMessage()
                ], 404)->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        });
    })->create();
