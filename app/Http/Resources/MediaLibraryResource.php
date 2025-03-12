<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaLibraryResource extends JsonResource
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
        // // Get Medias
        // $mediaCollections = 'media-libraries';
        // $mediaItems = $this->getMedia($mediaCollections);
        // $image = isset($mediaItems[0]) ? $mediaItems[0]->getFullUrl() : null;

        // Modifying Attribute Visibility
        $owner_visible = ['ulid', 'status', 'current_team_id', 'profile_photo_url', 'default_url', 'name', 'first_name', 'last_name', 'email'];
        $album_visible = ['id', 'name', 'description', 'url_path', 'dir_path', 'created_at'];


        return [
            "id" => $this->id,
            "name" => $this->name,
            "intro" => $this->intro,
            "description" => $this->description,
            "url_path" => $this->url_path,
            "dir_path" => $this->dir_path,
            // "image" => $image,
            "created_at" => $this->created_at,
            // "updated_at" => $this->updated_at,
            $this->mergeWhen($request->user()->tokenCan("read", $this->resource), [
                'owner' => !empty($this->user) ? $this->user->setVisible($owner_visible)->toArray() : null,
                'album' => !empty($this->album) ? $this->album->setVisible($album_visible)->toArray() : null,
            ]),
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
            'meta' => config('api-config.meta')
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     */
    public function withResponse($request, $response): void
    {
        $response->header(config('api-config.header.header_custom_api.key'), config('api-config.header.header_custom_api.value'));
    }
}
