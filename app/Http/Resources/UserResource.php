<?php

namespace App\Http\Resources;

use App\Http\Resources\MediaLibraryResource;
use App\Http\Resources\AlbumResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'ulid' => $this->ulid,
            'name' => $this->name,
            // 'email' => $this->email,
            // 'current_team_id' => $this->current_team_id,
            'profile_photo_path' => $this->profile_photo_path,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'default_url' => $this->default_url,
            'status' => $this->status,
            // 'albums' => AlbumResource::collection($this->whenLoaded('albums')),
            // 'medias' => MediaLibraryResource::collection($this->whenLoaded('medias')),
        ];
    }
}
