<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use App\Customs\EncryptionCustom;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AlbumCollection extends ResourceCollection
{
     /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Set additional meta and compact resultset
        if (config('api-config.api_secure.meta')) {
            // Encrypted resultset using KEY=meta_key
            if (config('api-config.api_secure.encryption')) {
                return [
                    'contents' => base64_encode(
                                    EncryptionCustom::encrypt(
                                        $this->collection, 
                                        config('api-config.meta_key') // Custom key to decrypt resultset
                                    )
                                  )
                ];
            } else {
                // Convert resultset to base64 and insert addtional meta-key on it
                $meta = config('api-config.custom_meta')[key(config('api-config.custom_meta'))];
                return [
                    'contents' => $meta . base64_encode($this->collection) . $meta
                ];
            }
        }

        // Default Response
        return ['data' => $this->collection];
    }

    // Additional Meta :
    // -----------------
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array    
    {
        // Add custom API meta_key
        if ($request->is('api/*')) {
            // Secure Meta
            if (config('api-config.api_secure.meta')) {
                // Encrypted meta
                if (config('api-config.api_secure.encryption')) {
                    // Encrypted meta key
                    return [
                        'meta' => array_merge(
                                        config('api-config.meta'), 
                                        [
                                            // Meta key tobe encrypted
                                            key(config('api-config.custom_meta')) => EncryptionCustom::encrypt(
                                                        // Generate meta_key with additional 16-alphanum ramdom string
                                                        config('api-config.meta_key') . Str::random(16)
                                                    )
                                        ]
                                    )
                    ];
                } else {
                    // Non encrypted meta key
                    $meta = config('api-config.custom_meta')[key(config('api-config.custom_meta'))];
                    return [
                        'meta' => array_merge(
                                        config('api-config.meta'), 
                                        [
                                            // insert additional alphanum ramdom string on it
                                            key(config('api-config.custom_meta')) => Str::random(16) . $meta . Str::random(16)
                                        ]
                                    )
                    ];
                }
            }

            // Default meta
            return [
                'meta' => config('api-config.meta')
            ];
        }
    }

}
