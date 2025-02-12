<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestingResource extends JsonResource
{
    private bool $withAuthorizations = true;
  
    public function withAuthorizations(bool $withAuthorizations): self
    {
        $this->withAuthorizations = $withAuthorizations;
        
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "image" => $this->image,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            $this->mergeWhen(
              $this->withAuthorizations,
              function() use ($request) {
                return [
                "can" => [
                    "read" => $request->user()->tokenCan("read", $this->resource),
                    "create" => $request->user()->tokenCan("create", $this->resource),
                    "update" => $request->user()->tokenCan("update", $this->resource),
                    "delete" => $request->user()->tokenCan("delete", $this->resource)
                  ]
                ];
              }
            ),
        ];
    }

    // Additional Meta & Header:
    // -------------------------
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array    
    {
        return [
            'meta' => [
                'key' => 'value',
            ],
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     */
    public function withResponse($request, $response): void
    {
        $response->header('X-Value', env('APP_HEADER_CUSTOM_VALUE'));
    }
}
