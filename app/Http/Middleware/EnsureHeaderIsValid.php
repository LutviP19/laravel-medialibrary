<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Encryption\DecryptException;
// use Illuminate\Support\Facades\Crypt;
use App\Customs\EncryptionCustom;

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

        // if route is api/*
        if ($request->is('api/*')) {
            // Check secure header status
            if (config('api-config.api_secure.header')) {
                $headerKey = config('api-config.header.header_custom_api.key');
                $headerValue = config('api-config.header.header_custom_api.value');

                // Encrypt key using custom encryption
                if (config('api-config.api_secure.encryption')) {
                    try {
                        if ($request->hasHeader($headerKey)) {
                            // $decrypted = Crypt::decryptString($request->header($headerKey)); // [deprecated]: Old stuff
                            $decrypted = EncryptionCustom::decrypt($request->header($headerKey)); // New stuff use custom encryption
                            if ($decrypted === $headerValue) 
                            {
                                return $response->header(
                                    $headerKey, 
                                    // Encrypted key
                                    EncryptionCustom::encrypt($headerValue)
                                );
                            }
        
                            goto invalid;
                        }
                        
                        goto invalid;
                    } catch (DecryptException $e) {
                        goto invalid;
                    }
                } else {
                    // Non encrypted key
                    if ($request->hasHeader($headerKey) 
                        && $request->header($headerKey) === $headerValue) {
                            return $response->header(
                                $headerKey, 
                                $headerValue
                            );
                    }

                    goto invalid;
                }
            } else {
                // Normal response
                return $response;
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
