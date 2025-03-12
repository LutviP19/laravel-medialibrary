<?php

use App\Models\User;
use App\Models\Album;
use App\Models\MediaLibrary;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;

test('task search media can be retrieved', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );

    MediaLibrary::factory(10)->create();
 
    $response = $this->json('post', '/api/media/search', ['q' => 'a'])->assertStatus(200);
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => []]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task list media can be retrieved', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );

    MediaLibrary::factory(1)->create();
 
    $response = $this->get('/api/media');
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => [['id', 'name', 'description']]]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task list media can be show', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );
 
    $media = MediaLibrary::factory(1)->create();
    $media_id = $media[0]->id;
    $response = $this->get('/api/media/'.$media_id);
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => ['id', 'name', 'description', 'can']]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task data media can be created', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['create']
    );
 
    $album = Album::factory(1)->create();
    $album_id = $album[0]->id;
    
    $name = 'MediaLibrary-'.rand(99,1000);
    $response = $this->json('post', '/api/media', ['album_id' => $album_id, 'name' => $name, 'description' => 'MediaLibrary description'])->assertStatus(201);

    // Assert response data
    $response->assertJsonStructure(['data' => ['id', 'name', 'description']]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task data media can be updated', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['update']
    );

    $album = Album::factory(1)->create();
    $album_id = $album[0]->id;
 
    $media = MediaLibrary::factory(1)->create();
    $media_id = $media[0]->id;
    $name = $media[0]->name;
    $response = $this->json('patch', '/api/media/'.$media_id, ['album_id' => $album_id, 'name' => $name, 'description' => 'MediaLibrary description'])->assertStatus(200);

    // Assert response data
    $response->assertJson(fn (AssertableJson $json) =>
        $json->hasAll(['data', 'meta'])
            ->missingAll(['message', 'errors'])
    );
});

test('task data media can be deleted', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['delete']
    );
 
    $media = MediaLibrary::factory(1)->create();
    $media_id = $media[0]->id;
    $response = $this->json('delete', '/api/media/'.$media_id)->assertStatus(200);

    // Assert response data
    $response->assertJson(['status' => 'success']);
});
