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
        // All laravel Middleware Features
        $middleware->use([
            \Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks::class,
            \Illuminate\Http\Middleware\TrustHosts::class, // Newly Used
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        
        $middleware->statefulApi();

        // Middleware Aliases
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);

        // Trust Proxies Check
        $middleware->trustProxies(at: '*');
        // $middleware->trustProxies(at: [
        //     '192.168.1.1',
        //     '10.0.0.0/8',
        // ]);
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

        // Allow Request under Maintenance Mode
        $middleware->preventRequestsDuringMaintenance(except: [
            // 'stripe/*',
            'api/status', 
            'status',
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

        // MethodNotAllowedHttpException
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Invalid method.',
                    'statusCode' => $e->getStatusCode(),
                    'errors' => $e->getMessage(),
                    // 'exception' => "MethodNotAllowedHttpException file: ". $e->getFile() ." line: ". $e->getLine()
                ], $e->getStatusCode())->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        }); 

        // NotFoundHttpException
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.',
                    'statusCode' => $e->getStatusCode(),
                    'errors' => 'Not found.',
                    // 'exception' => "NotFoundHttpException file: ". $e->getFile() ." line: ". $e->getLine()
                ], $e->getStatusCode())->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        });

        // AuthenticationException
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Forbidden',
                    'statusCode' => 403,
                    'errors' => $e->getMessage(),
                    // 'exception' => "AuthenticationException file: ". $e->getFile() ." line: ". $e->getLine()
                ], 403)->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        });

        // AccessDeniedHttpException
        $exceptions->renderable(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Not accessable.',
                    'statusCode' => $e->getStatusCode(),
                    'errors' => $e->getMessage(),
                    // 'exception' => "AccessDeniedHttpException file: ". $e->getFile() ." line: ". $e->getLine()
                ], $e->getStatusCode())->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        });

        // ModelNotFoundException (force 404 status code for API)
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.',
                    'statusCode' => 404,
                    'errors' => 'Not found.',
                    // 'exception' =>  "ModelNotFoundException file: ". $e->getFile() ." line: ". $e->getLine()
                ], 404)->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
            }
        });
    })
    ->booted(function (Application $app) {
        // \Illuminate\Foundation\Configuration\Middleware::trustProxies(at: [
        //     config('api-config.trusted_proxies')
        // ]);
    })
    ->create();
