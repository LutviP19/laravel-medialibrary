<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\MediaLibrary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // MediaLibrary::factory(10)->create();

        // Insert album_id to media
        $albums = Album::all();
        foreach($albums as $album) {
            MediaLibrary::factory()->create([
                'user_ulid' => $album->user_ulid,
                'album_id' => $album->id,
            ]);
        }
    }
}
