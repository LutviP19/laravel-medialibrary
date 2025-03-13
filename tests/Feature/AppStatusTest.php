<?php

use Illuminate\Testing\Fluent\AssertableJson;


it('has main page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});


test('check status web', function () {
    $response = $this->get('/status');

    $response->assertStatus(200);
});

test('check status api', function () {
    $response = $this->withHeaders([
                    env('APP_HEADER_CUSTOM_KEY') => env('APP_HEADER_CUSTOM_VALUE'),
                ])
                ->get('/api/status');

    $response->assertOk();

    // Assert response data
    $response->assertJson(['statusCode' => 200]);
});