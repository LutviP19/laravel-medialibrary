<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Chat;
use App\Models\Room;
use App\Models\Member;
use App\Models\Testing;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        // User::factory()->withPersonalTeam()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Testing::factory(5)->create();

        // Chat::factory()->count(10)->create(['room_id' => 1]); // only uncomment once
    }
}
