<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIpIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request->ip());
        if(!in_array($request->ip(), config('api-config.trusted_ips'))) {
            if ($request->is('api/*')) {
                return response()->noContent();
            }
        }

        return $next($request);
    }
}
