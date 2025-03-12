<?php

use App\Models\User;
use App\Models\Album;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;

test('task search album can be retrieved', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );

    Album::factory(10)->create();
 
    $response = $this->json('post', '/api/album/search', ['q' => 'a'])->assertStatus(200);
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => []]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task list album can be retrieved', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );

    Album::factory(1)->create();
 
    $response = $this->get('/api/album');
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => [['id', 'name', 'description']]]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task list album can be show', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );
 
    $album = Album::factory(1)->create();
    $album_id = $album[0]->id;
    $response = $this->get('/api/album/'.$album_id);
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => ['id', 'name', 'description', 'can']]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task data album can be created', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['create']
    );
 
    // $album = Album::factory(1)->create();
    // $album_id = $album[0]->id;
    $name = 'Album-'.rand(99,1000);
    $response = $this->json('post', '/api/album', ['name' => $name, 'description' => 'Album description'])->assertStatus(201);

    // Assert response data
    $response->assertJsonStructure(['data' => ['id', 'name', 'description']]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task data album can be updated', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['update']
    );
 
    $album = Album::factory(1)->create();
    $album_id = $album[0]->id;
    $name = $album[0]->name;
    $response = $this->json('patch', '/api/album/'.$album_id, ['name' => $name, 'description' => 'Album description'])->assertStatus(200);

    // Assert response data
    $response->assertJson(fn (AssertableJson $json) =>
        $json->hasAll(['data', 'meta'])
            ->missingAll(['message', 'errors'])
    );
});

test('task data album can be deleted', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['delete']
    );
 
    $album = Album::factory(1)->create();
    $album_id = $album[0]->id;
    $response = $this->json('delete', '/api/album/'.$album_id)->assertStatus(200);

    // Assert response data
    $response->assertJson(['status' => 'success']);
});
