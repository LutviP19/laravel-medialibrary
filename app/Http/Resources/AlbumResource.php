<?php

namespace App\Http\Resources;

use App\Http\Resources\MediaLibraryResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
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
        // ======= DEMO Simple
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
        ]; 
        // ======= END DEMO


        // Get Medias
        // $mediaCollections = 'albums';
        // $mediaItems = $this->getMedia($mediaCollections);
        // $image = isset($mediaItems[0]) ? $mediaItems[0]->getFullUrl() : null;

        // Modifying Attribute Visibility
        $owner_visible = ['ulid', 'status', 'current_team_id', 'profile_photo_url', 'default_url', 'name', 'first_name', 'last_name', 'email'];
        $media_visible = ['id', 'user_ulid', 'album_id', 'url_path', 'name', 'intro'];

        // dd($this->resource);

        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "url_path" => $this->url_path,
            "dir_path" => $this->dir_path,
            // "image" => $image,
            "created_at" => $this->created_at,
            // "updated_at" => $this->updated_at,
            $this->mergeWhen($request->user()->tokenCan("read", $this->resource), [
                // 'owner' => !empty($this->owner) ? $this->owner->setVisible($owner_visible)->toArray() : null,
                // 'medias' => !empty($this->medias) ? $this->medias->setVisible($media_visible)->toArray() : null,

                'owner' =>  UserResource::collection($this->whenLoaded('owner')),
                'medias' => MediaLibraryResource::collection($this->whenLoaded('medias')),
            ]),
            $this->mergeWhen($this->withAuthorizations,
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

    // // Additional Meta :
    // // -----------------
    // /**
    //  * Get additional data that should be returned with the resource array.
    //  *
    //  * @return array<string, mixed>
    //  */
    // public function with(Request $request): array    
    // {
    //     return [
    //         'meta' => config('api-config.meta')
    //     ];
    // }
}
