<?php

use App\Models\User;
use App\Models\Testing;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;

test('task test can be retrieved', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );

    Testing::factory(1)->create();
 
    $response = $this->withHeaders([
                    env('APP_HEADER_CUSTOM_KEY') => env('APP_HEADER_CUSTOM_VALUE'),
                ])
                ->get('/api/testing/test');
 
    $response->assertOk();

    // Assert response data
    $response->assertJson(['status' => 'success']);
});

test('task search can be retrieved', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );

    Testing::factory(10)->create();
 
    $response = $this->withHeaders([
                    env('APP_HEADER_CUSTOM_KEY') => env('APP_HEADER_CUSTOM_VALUE'),
                ])
                ->json('post', '/api/testing/search', ['q' => 'a'])->assertStatus(200);
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => [['id', 'name', 'description']]]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task list can be retrieved', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );

    Testing::factory(1)->create();
 
    $response = $this->withHeaders([
                    env('APP_HEADER_CUSTOM_KEY') => env('APP_HEADER_CUSTOM_VALUE'),
                ])
                ->get('/api/testing');
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => [['id', 'name', 'description']]]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task list can be show', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['read']
    );
 
    $testing = Testing::factory(1)->create();
    $testing_id = $testing[0]->id;
    $response = $this->withHeaders([
                    env('APP_HEADER_CUSTOM_KEY') => env('APP_HEADER_CUSTOM_VALUE'),
                ])
                ->get('/api/testing/'.$testing_id);
 
    $response->assertOk();

    // Assert response data
    $response->assertJsonStructure(['data' => ['id', 'name', 'description', 'can']]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task data can be created', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['create']
    );
 
    // $testing = Testing::factory(1)->create();
    // $testing_id = $testing[0]->id;
    $name = 'Testing-'.rand(99,1000);
    $response = $this->withHeaders([
                    env('APP_HEADER_CUSTOM_KEY') => env('APP_HEADER_CUSTOM_VALUE'),
                ])
                ->json('post', '/api/testing', ['name' => $name, 'description' => 'Testing description'])->assertStatus(201);

    // Assert response data
    $response->assertJsonStructure(['data' => ['id', 'name', 'description']]);
    $response->assertJsonPath('meta.app', env('APP_NAME', 'Laravel'));
});

test('task data can be updated', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['update']
    );
 
    $testing = Testing::factory(1)->create();
    $testing_id = $testing[0]->id;
    $name = $testing[0]->name;
    $response = $this->withHeaders([
                    env('APP_HEADER_CUSTOM_KEY') => env('APP_HEADER_CUSTOM_VALUE'),
                ])
                ->json('patch', '/api/testing/'.$testing_id, ['name' => $name, 'description' => 'Testing description'])->assertStatus(200);

    // Assert response data
    $response->assertJson(fn (AssertableJson $json) =>
        $json->hasAll(['data', 'meta'])
            ->missingAll(['message', 'errors'])
    );
});

test('task data can be deleted', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['delete']
    );
 
    $testing = Testing::factory(1)->create();
    $testing_id = $testing[0]->id;
    $response = $this->withHeaders([
                    env('APP_HEADER_CUSTOM_KEY') => env('APP_HEADER_CUSTOM_VALUE'),
                ])
                ->json('delete', '/api/testing/'.$testing_id)->assertStatus(200);

    // Assert response data
    $response->assertJson(['status' => 'success']);
});