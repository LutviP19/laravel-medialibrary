<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

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

            if (config('api-config.api_secure.encryption')) {
                try {
                    if ($request->hasHeader($headerKey)) {
                        $decrypted = Crypt::decryptString($request->header($headerKey));
                        if ($decrypted === $headerValue) 
                        {
                            return $response;
                        }
    
                        goto invalid;
                    }
                    
                    goto invalid;
                } catch (DecryptException $e) {
                    goto invalid;
                }
            } else {
                if ($request->hasHeader($headerKey) 
                    && $request->header($headerKey) === $headerValue) {
                        return $response;
                }

                goto invalid;
            }

            // Invalid request
            invalid: 
            return response()->json([
                'message' => 'Access Denied!',
                'statusCode' => 403,
                'errors' => 'Invalid request.',
            ], 403);
        }

        return $response;
    }
}
