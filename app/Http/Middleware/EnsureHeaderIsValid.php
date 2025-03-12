<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHeaderIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('api/*')) {
            $headerKey = config('api-config.header.header_custom_api.key');
            $headerValue = config('api-config.header.header_custom_api.value');
            if ($request->hasHeader($headerKey)
                && $request->header($headerKey) === $headerValue ) 
            {
                return $response;
            }

            return response()->json([
                'message' => 'Access Denied!',
                'errors' => '403'
            ], 403);
        }

        return $response;
    }
}
