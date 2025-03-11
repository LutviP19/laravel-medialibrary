<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\File;

class Album extends Model implements HasMedia 
{
    /** @use HasFactory<\Database\Factories\AlbumFactory> */
    use HasFactory;
    use HasUlids;
    use SoftDeletes;
    use InteractsWithMedia;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        'user_ulid',
        'name',
        'description',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('albums')
            ->acceptsMimeTypes(['image/jpeg','image/png']);
    }

    public function user(): HasOne
    {
        return $this->HasOne(User::class, 'ulid', 'user_ulid');
    }

    public function media_libraries(): hasMany
    {
        return $this->hasMany(MediaLibrary::class, 'album_id', 'id');
    }
}
