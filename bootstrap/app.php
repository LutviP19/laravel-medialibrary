<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use App\Http\Middleware\EnsureIpIsValid;
use App\Http\Middleware\EnsureHeaderIsValid;
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
        $middleware->use([
            \Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks::class,
            \Illuminate\Http\Middleware\TrustHosts::class, // New Added
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);
        $middleware->statefulApi();
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);

        // Trust Proxy Check
        $middleware->trustProxies(at: [
            '192.168.1.1',
            '10.0.0.0/8',
        ]);
        // $middleware->trustProxies(headers: Request::HEADER_X_FORWARDED_FOR |
        //     Request::HEADER_X_FORWARDED_HOST |
        //     Request::HEADER_X_FORWARDED_PORT |
        //     Request::HEADER_X_FORWARDED_PROTO |
        //     Request::HEADER_X_FORWARDED_AWS_ELB
        // );

        // Trust Host Check
        $middleware->trustHosts(at: fn () => config('api-config.trusted_hosts'));

        // Append Customs Middleware
        $middleware->append(EnsureIpIsValid::class);
        $middleware->append(EnsureHeaderIsValid::class);
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

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.',
                    'errors' => '404',
                    //'exception' => $e->getMessage()
                ], 404)->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        });

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
