<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Album;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Album::factory(10)->create();

        // Update user ulid
        $users = User::all();
        foreach($users as $user) {
            $user->ulid = (string) Str::ulid();
            $user->save();
        }

        // Use user ulid to album
        $users = User::all();
        foreach($users as $user) {
            Album::factory()->create([
                'user_ulid' => $user->ulid,
            ]);
        }
    }
}
